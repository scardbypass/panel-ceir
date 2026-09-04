<?php
declare(strict_types=1);

final class DhruServer
{
    public function __construct(private mysqli $db) {}

    public function handle(array $input): string
    {
        $username = trim((string)($input['username'] ?? $input['api_username'] ?? $input['user'] ?? ''));
        $key = trim((string)($input['apiaccesskey'] ?? $input['key'] ?? $input['api_key'] ?? $input['apikey'] ?? $input['accesskey'] ?? ''));
        $action = strtolower(trim((string)($input['action'] ?? '')));
        $format = strtoupper(trim((string)($input['requestformat'] ?? 'JSON')));

        if ($username === '' || $key === '') {
            return $this->respond($this->error('Authentication credentials are required.'), $format);
        }

        try {
            $account = $this->authenticate($username, $key);
            if (!$account) return $this->respond($this->error('Invalid API credentials.'), $format);

            $result = match ($action) {
                'accountinfo' => $this->accountInfo($account),
                'imeiservicelist', 'servicelist' => $this->serviceList($account),
                'placeimeiorder', 'placeorder' => $this->placeOrder($account, $input),
                'placeimeiorderbulk', 'placeorderbulk' => $this->placeBulkOrder($account, $input),
                'getimeiorder', 'getorder', 'orderstatus' => $this->orderStatus($account, $input),
                'getimeiorderbulk', 'getorderbulk', 'orderstatusbulk' => $this->bulkOrderStatus($account, $input),
                default => $this->error('Unsupported action.'),
            };
        } catch (Throwable $e) {
            $result = $this->error($e->getMessage());
        }

        return $this->respond($result, $format);
    }

    private function authenticate(string $username, string $key): ?array
    {
        // V2 keys are stored as SHA-256 hashes. Raw keys never need to be stored in Git.
        $hashes = [hash('sha256', $key)];
        if (str_contains($key, '.')) $hashes[] = hash('sha256', substr($key, strrpos($key, '.') + 1));

        foreach (array_unique($hashes) as $hash) {
            $stmt = $this->db->prepare("SELECT u.id,u.username,u.nama,u.email,u.saldo,ac.id AS api_client_id FROM api_clients ac INNER JOIN users u ON u.id=ac.user_id WHERE u.username=? AND ac.api_key_hash=? AND ac.status='active' AND u.status='Aktif' LIMIT 1");
            if (!$stmt) continue;
            $stmt->bind_param('ss', $username, $hash);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($row) {
                $touch = $this->db->prepare('UPDATE api_clients SET last_used_at=NOW() WHERE id=?');
                if ($touch) { $touch->bind_param('i', $row['api_client_id']); $touch->execute(); $touch->close(); }
                return $row;
            }
        }

        // Legacy compatibility. Existing users.api_key can continue working while keys are migrated.
        $stmt = $this->db->prepare("SELECT id,username,nama,email,saldo FROM users WHERE username=? AND api_key=? AND status='Aktif' LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param('ss', $username, $key);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    private function accountInfo(array $account): array
    {
        return ['SUCCESS' => [[
            'MESSAGE' => 'Your Account Info',
            'AccountInfo' => [
                'credit' => number_format((float)$account['saldo'], 2, '.', ''),
                'creditraw' => (float)$account['saldo'],
                'mail' => (string)($account['email'] ?? ''),
                'currency' => 'IDR',
                'username' => (string)$account['username'],
            ],
        ]]];
    }

    private function serviceList(array $account): array
    {
        $groups = [];
        $q = $this->db->query("SELECT id,service_id,provider_id,operator,layanan,harga_api,catatan FROM layanan_digital WHERE status='Normal' AND public_visible=1 ORDER BY sort_order ASC,id ASC");
        if (!$q) throw new RuntimeException('Service catalog is unavailable.');

        while ($r = $q->fetch_assoc()) {
            $group = trim((string)$r['operator']) ?: 'DHRU';
            $sid = (string)($r['service_id'] ?: $r['provider_id'] ?: $r['id']);
            $groups[$group]['GROUPNAME'] = $group;
            $groups[$group]['GROUPTYPE'] = 'IMEI';
            $groups[$group]['SERVICES'][] = [
                'SERVICEID' => $sid,
                'SERVICETYPE' => 'IMEI',
                'SERVICENAME' => (string)$r['layanan'],
                'CREDIT' => (float)$r['harga_api'],
                'TIME' => '',
                'MINQNT' => 1,
                'MAXQNT' => 1,
                'CUSTOM' => ['customname' => 'IMEI', 'customvalue' => 'IMEI'],
                'Requires' => ['Custom' => [['fieldname' => 'IMEI', 'fieldtype' => 'text', 'required' => 'on']]],
                'INFO' => trim((string)($r['catatan'] ?? '')),
                'PRODUCTID' => (string)$r['id'],
            ];
        }

        return ['SUCCESS' => [[
            'MESSAGE' => 'IMEI Service List',
            'LIST' => array_values($groups),
            'ACCOUNTINFO' => [[
                'credit' => number_format((float)$account['saldo'], 2, '.', ''),
                'creditraw' => (float)$account['saldo'],
                'mail' => (string)($account['email'] ?? ''),
                'currency' => 'IDR',
                'username' => (string)$account['username'],
            ]],
        ]]];
    }

    private function placeOrder(array $account, array $input): array
    {
        $data = $this->parseParameters($input['parameters'] ?? '');
        $serviceId = trim((string)($data['ID'] ?? $data['id'] ?? ''));
        $target = preg_replace('/\D+/', '', (string)($data['IMEI'] ?? $data['imei'] ?? $data['TARGET'] ?? $data['target'] ?? ''));
        if ($serviceId === '' || $target === '') return $this->error('Service ID and IMEI are required.');
        if (!preg_match('/^\d{14,16}$/', $target)) return $this->error('Invalid IMEI.');

        $service = $this->findService($serviceId);
        if (!$service) return $this->error('Service not found or inactive.');
        $price = (int)round((float)$service['harga_api']);
        if ($price <= 0) return $this->error('Service price is invalid.');

        $balance = new BalanceService($this->db);
        $oid = 'DHRU-' . date('ymdHis') . '-' . random_int(1000, 9999);
        $localId = $this->createLocalOrder($account, $service, $target, $price, $oid);

        try {
            $balance->debitByUsername($account['username'], $price, 'dhru:debit:' . $oid, 'DHRU API order ' . $oid);
            $providerResult = $this->dispatchToProvider($service, $target);
            $providerId = (string)($providerResult['provider_order_id'] ?? '');
            if ($providerId === '') throw new RuntimeException('Provider did not return an order ID.');
            $this->markAccepted($oid, $providerId, $providerResult['message'] ?? 'Order diterima provider.');
            return ['SUCCESS' => [[
                'MESSAGE' => 'Order received',
                'REFERENCEID' => $localId,
            ]]];
        } catch (Throwable $e) {
            $transport = $this->isTransportError($e->getMessage());
            if ($transport) {
                $this->markPending($oid, $e->getMessage());
            } else {
                $this->markFailed($oid, $e->getMessage());
                try { $balance->refundByUsername($account['username'], $price, 'dhru:debit:' . $oid, 'Refund DHRU order ' . $oid); } catch (Throwable $ignored) {}
            }
            return $this->error($e->getMessage());
        }
    }

    private function placeBulkOrder(array $account, array $input): array
    {
        $raw = $input['parameters'] ?? '';
        $items = is_array($raw) ? $raw : (json_decode((string)$raw, true) ?: []);
        if (isset($items['ID']) || isset($items['id'])) $items = [$items];
        if (!$items) return $this->error('Bulk parameters are empty.');

        $success = [];
        $errors = [];
        foreach ($items as $item) {
            $result = $this->placeOrder($account, ['parameters' => $item]);
            if (isset($result['SUCCESS'])) $success[] = $result['SUCCESS'][0];
            else $errors[] = $result['ERROR'][0] ?? ['MESSAGE' => 'Order failed.'];
        }
        $out = [];
        if ($success) $out['SUCCESS'] = $success;
        if ($errors) $out['ERROR'] = $errors;
        return $out ?: $this->error('No bulk order was accepted.');
    }

    private function orderStatus(array $account, array $input): array
    {
        $data = $this->parseParameters($input['parameters'] ?? '');
        $id = trim((string)($data['ID'] ?? $data['id'] ?? ''));
        if ($id === '') return $this->error('Order ID is required.');

        $stmt = $this->db->prepare("SELECT p.id,p.oid,p.provider_oid,p.status,p.keterangan,p.target,p.provider,po.provider_order_id FROM pembelian_digital p LEFT JOIN provider_orders_v2 po ON po.local_order_id=p.oid WHERE p.user=? AND (p.id=? OR p.oid=? OR p.provider_oid=? OR po.provider_order_id=?) LIMIT 1");
        $stmt->bind_param('sisss', $account['username'], $id, $id, $id, $id);
        $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$row) return $this->error('Order not found.');

        $this->refreshProviderStatus($row);
        $stmt = $this->db->prepare("SELECT p.id,p.oid,p.provider_oid,p.status,p.keterangan,p.target FROM pembelian_digital p WHERE p.oid=? AND p.user=? LIMIT 1");
        $stmt->bind_param('ss', $row['oid'], $account['username']); $stmt->execute(); $fresh = $stmt->get_result()->fetch_assoc(); $stmt->close();
        $row = $fresh ?: $row;

        $status = match (strtolower((string)$row['status'])) {
            'success' => 4,
            'processing', 'partial' => 1,
            'error', 'failed', 'rejected' => 3,
            default => 0,
        };
        return ['SUCCESS' => [[
            'STATUS' => $status,
            'REFERENCEID' => (int)$row['id'],
            'MESSAGE' => (string)($row['keterangan'] ?: $row['status']),
            'CODE' => (string)$row['status'],
            'TARGET' => (string)$row['target'],
        ]]];
    }

    private function bulkOrderStatus(array $account, array $input): array
    {
        $raw = $input['parameters'] ?? '';
        $items = is_array($raw) ? $raw : (json_decode((string)$raw, true) ?: $this->parseParameters((string)$raw));
        if (isset($items['ID']) || isset($items['id'])) $items = [$items];
        if (!$items) return $this->error('Bulk order IDs are empty.');
        $success = [];
        $errors = [];
        foreach ($items as $item) {
            $result = $this->orderStatus($account, ['parameters' => $item]);
            if (isset($result['SUCCESS'])) $success[] = $result['SUCCESS'][0];
            else $errors[] = $result['ERROR'][0] ?? ['MESSAGE' => 'Order not found.'];
        }
        $out = [];
        if ($success) $out['SUCCESS'] = $success;
        if ($errors) $out['ERROR'] = $errors;
        return $out ?: $this->error('No order status found.');
    }

    private function findService(string $serviceId): ?array
    {
        if (ctype_digit($serviceId)) {
            $stmt = $this->db->prepare("SELECT id,service_id,provider_id,layanan,harga_api,provider,status FROM layanan_digital WHERE status='Normal' AND public_visible=1 AND id=? LIMIT 1");
            $id = (int)$serviceId;
            $stmt->bind_param('i', $id);
        } else {
            $stmt = $this->db->prepare("SELECT id,service_id,provider_id,layanan,harga_api,provider,status FROM layanan_digital WHERE status='Normal' AND public_visible=1 AND (service_id=? OR provider_id=?) LIMIT 1");
            $stmt->bind_param('ss', $serviceId, $serviceId);
        }
        $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        return $row ?: null;
    }

    private function createLocalOrder(array $account, array $service, string $target, int $price, string $oid): int
    {
        $stmt = $this->db->prepare("INSERT INTO pembelian_digital (oid,provider_oid,user,layanan,harga,profit,target,no_meter,keterangan,status,date,time,place_from,provider,refund) VALUES (?, '', ?, ?, ?, 0, ?, '', 'DHRU API order received', 'Pending', CURDATE(), CURTIME(), 'DHRU API', ?, 0)");
        $stmt->bind_param('sssiss', $oid, $account['username'], $service['layanan'], $price, $target, $service['provider']);
        $stmt->execute();
        $id = (int)$this->db->insert_id;
        $stmt->close();

        $p = $this->db->prepare("INSERT INTO provider_orders_v2 (local_order_id,provider,user_id,service_id,target,cost,sell_price,status) VALUES (?,?,?,?,?,?,?,'pending')");
        $p->bind_param('ssissii', $oid, $service['provider'], $account['id'], $service['provider_id'], $target, $price, $price);
        $p->execute(); $p->close();
        return $id;
    }

    private function dispatchToProvider(array $service, string $target): array
    {
        $provider = strtolower(trim((string)$service['provider']));
        if ($provider === 'manual') return ['provider_order_id' => $service['id'], 'message' => 'Menunggu proses manual.'];

        if ($provider === 'ceirgo') {
            require_once __DIR__ . '/CeirGoClient.php';
            $row = $this->db->query("SELECT api_key,link FROM provider WHERE code='ceirgo' LIMIT 1")->fetch_assoc();
            if (!$row || trim((string)$row['api_key']) === '') throw new RuntimeException('Provider CEIRGo belum dikonfigurasi.');
            $client = new CeirGoClient((string)$row['api_key'], (string)($row['link'] ?? 'https://ceirgo.id'));
            $response = $client->createOrder((string)$service['provider_id'], ['imeis' => [$target]]);
            $id = $response['order_id'] ?? $response['data']['order_id'] ?? $response['data']['id'] ?? null;
            if ($id === null || $id === '') throw new RuntimeException('CEIRGo tidak mengembalikan Order ID.');
            return ['provider_order_id' => (string)$id, 'message' => 'Order diterima CEIRGo.'];
        }

        if ($provider === 'dhru') {
            require_once __DIR__ . '/DhruClient.php';
            $row = $this->db->query("SELECT api_key,api_id,link FROM provider WHERE code='DHRU' LIMIT 1")->fetch_assoc();
            if (!$row || trim((string)$row['api_key']) === '') throw new RuntimeException('Provider DHRU upstream belum dikonfigurasi.');
            $client = new DhruClient((string)$row['link'], (string)($row['api_id'] ?: 'DHRU'), (string)$row['api_key']);
            $response = $client->placeOrder(['ID' => (string)$service['provider_id'], 'IMEI' => $target]);
            $id = $response['SUCCESS'][0]['REFERENCEID'] ?? null;
            if ($id === null || $id === '') throw new RuntimeException('DHRU upstream tidak mengembalikan Order ID.');
            return ['provider_order_id' => (string)$id, 'message' => 'Order diterima DHRU upstream.'];
        }

        throw new RuntimeException('Provider produk belum didukung: ' . $service['provider']);
    }

    private function markAccepted(string $oid, string $providerId, string $message): void
    {
        $s = $this->db->prepare("UPDATE pembelian_digital SET provider_oid=?,status='Processing',keterangan=? WHERE oid=? AND status='Pending'");
        $s->bind_param('sss', $providerId, $message, $oid); $s->execute(); $s->close();
        $s = $this->db->prepare("UPDATE provider_orders_v2 SET provider_order_id=?,status='processing',response_message=? WHERE local_order_id=?");
        $s->bind_param('sss', $providerId, $message, $oid); $s->execute(); $s->close();
    }

    private function markPending(string $oid, string $message): void
    {
        $s = $this->db->prepare("UPDATE pembelian_digital SET status='Pending',keterangan=? WHERE oid=? AND status='Pending'");
        $s->bind_param('ss', $message, $oid); $s->execute(); $s->close();
        $s = $this->db->prepare("UPDATE provider_orders_v2 SET status='pending',response_message=? WHERE local_order_id=?");
        $s->bind_param('ss', $message, $oid); $s->execute(); $s->close();
    }

    private function markFailed(string $oid, string $message): void
    {
        $s = $this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=? WHERE oid=? AND status IN ('Pending','Processing')");
        $s->bind_param('ss', $message, $oid); $s->execute(); $s->close();
        $s = $this->db->prepare("UPDATE provider_orders_v2 SET status='failed',response_message=? WHERE local_order_id=?");
        $s->bind_param('ss', $message, $oid); $s->execute(); $s->close();
    }

    private function refreshProviderStatus(array $row): void
    {
        $providerId = trim((string)($row['provider_order_id'] ?? $row['provider_oid'] ?? ''));
        $provider = strtolower(trim((string)$row['provider']));
        if ($providerId === '' || !in_array($provider, ['ceirgo','dhru'], true)) return;

        try {
            $status = null; $message = '';
            if ($provider === 'ceirgo') {
                require_once __DIR__ . '/CeirGoClient.php';
                $p = $this->db->query("SELECT api_key,link FROM provider WHERE code='ceirgo' LIMIT 1")->fetch_assoc();
                if (!$p || trim((string)$p['api_key']) === '') return;
                $client = new CeirGoClient((string)$p['api_key'], (string)($p['link'] ?? 'https://ceirgo.id'));
                $r = $client->order((int)$providerId);
                $d = is_array($r['data'] ?? null) ? $r['data'] : $r;
                $status = strtolower((string)($d['status'] ?? ''));
                $message = (string)($d['result'] ?? $d['message'] ?? '');
            } else {
                require_once __DIR__ . '/DhruClient.php';
                $p = $this->db->query("SELECT api_key,api_id,link FROM provider WHERE code='DHRU' LIMIT 1")->fetch_assoc();
                if (!$p || trim((string)$p['api_key']) === '') return;
                $client = new DhruClient((string)$p['link'], (string)($p['api_id'] ?: 'DHRU'), (string)$p['api_key']);
                $r = $client->getOrder($providerId);
                $x = $r['SUCCESS'][0] ?? [];
                $code = (int)($x['STATUS'] ?? 0);
                $status = match ($code) { 4 => 'success', 3 => 'error', 1 => 'processing', default => 'pending' };
                $message = (string)($x['MESSAGE'] ?? $x['CODE'] ?? '');
            }
            if ($status !== null) $this->applyStatus($row['oid'], $status, $message);
        } catch (Throwable $ignored) {
            // Polling failures must not turn a valid order into a false failure.
        }
    }

    private function applyStatus(string $oid, string $status, string $message): void
    {
        $status = strtolower($status);
        $local = match ($status) {
            'success', 'completed' => 'Success',
            'partial' => 'Partial',
            'failed', 'error', 'rejected', 'cancelled' => 'Error',
            'processing', 'in_process' => 'Processing',
            default => 'Pending',
        };
        $s = $this->db->prepare("UPDATE pembelian_digital SET status=?,keterangan=? WHERE oid=? AND status NOT IN ('Success','Error')");
        $s->bind_param('sss', $local, $message, $oid); $s->execute(); $s->close();
        $s = $this->db->prepare("UPDATE provider_orders_v2 SET status=?,response_message=? WHERE local_order_id=?");
        $v2 = match ($local) { 'Success' => 'success', 'Error' => 'failed', 'Partial' => 'processing', 'Processing' => 'processing', default => 'pending' };
        $s->bind_param('sss', $v2, $message, $oid); $s->execute(); $s->close();
    }

    private function parseParameters(mixed $raw): array
    {
        if (is_array($raw)) return $raw;
        $raw = trim((string)$raw);
        if ($raw === '') return [];
        $json = json_decode($raw, true);
        if (is_array($json)) return $json;
        if (!str_contains($raw, '<')) return [];
        $xml = @simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NONET);
        if (!$xml) return [];
        $out = [];
        foreach ($xml->children() as $key => $value) $out[(string)$key] = (string)$value;
        return $out;
    }

    private function isTransportError(string $message): bool
    {
        return (bool)preg_match('/timeout|timed out|connection|cURL|curl|network|could not resolve|failed to connect/i', $message);
    }

    private function error(string $message): array { return ['ERROR' => [['MESSAGE' => $message]]]; }

    private function respond(array $data, string $format): string
    {
        if ($format === 'XML') return $this->xml($data);
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function xml(array $data): string
    {
        $xml = new SimpleXMLElement('<RESPONSE/>');
        $this->arrayToXml($data, $xml);
        return $xml->asXML() ?: '<RESPONSE><ERROR><MESSAGE>XML encoding failed.</MESSAGE></ERROR></RESPONSE>';
    }

    private function arrayToXml(array $data, SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            $key = preg_replace('/[^A-Za-z0-9_.-]/', '_', (string)$key) ?: 'ITEM';
            if (is_array($value)) {
                if (array_is_list($value)) {
                    foreach ($value as $item) {
                        $node = $xml->addChild($key);
                        if (is_array($item)) $this->arrayToXml($item, $node);
                        else $node[0] = htmlspecialchars((string)$item, ENT_XML1);
                    }
                } else {
                    $node = $xml->addChild($key);
                    $this->arrayToXml($value, $node);
                }
            } else {
                $xml->addChild($key, htmlspecialchars((string)$value, ENT_XML1));
            }
        }
    }
}
