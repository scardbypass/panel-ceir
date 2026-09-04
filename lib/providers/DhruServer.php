<?php
declare(strict_types=1);

final class DhruServer
{
    public function __construct(private mysqli $db) {}

    public function handle(array $input): string
    {
        $username = trim((string)($input['username'] ?? $input['api_username'] ?? $input['user'] ?? ''));
        $key = trim((string)($input['apiaccesskey'] ?? $input['key'] ?? $input['api_key'] ?? $input['apikey'] ?? ''));
        $action = strtolower(trim((string)($input['action'] ?? '')));
        $format = strtoupper((string)($input['requestformat'] ?? 'JSON'));
        if ($username === '' || $key === '') return $this->respond($this->error('Authentication credentials are required.'), $format);
        $account = $this->account($username, $key);
        if (!$account) return $this->respond($this->error('Invalid API credentials.'), $format);

        $result = match ($action) {
            'accountinfo' => $this->accountInfo($account),
            'imeiservicelist', 'servicelist' => $this->serviceList(),
            'placeimeiorder', 'placeorder' => $this->placeOrder($account, $input),
            'getimeiorder', 'getorder', 'orderstatus' => $this->orderStatus($account, $input),
            default => $this->error('Unsupported action.'),
        };
        return $this->respond($result, $format);
    }

    private function account(string $username, string $key): ?array
    {
        $stmt = $this->db->prepare("SELECT id, username, nama, email, saldo FROM users WHERE username = ? AND api_key = ? AND status = 'Aktif' LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param('ss', $username, $key); $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        return $row ?: null;
    }

    private function accountInfo(array $account): array
    {
        return ['SUCCESS' => [['message' => 'Your Account Info', 'AccountInfo' => [
            'credit' => number_format((float)$account['saldo'], 2, '.', ''),
            'creditraw' => (float)$account['saldo'], 'mail' => $account['email'], 'currency' => 'IDR', 'username' => $account['username']
        ]]]];
    }

    private function serviceList(): array
    {
        $rows = [];
        $q = $this->db->query("SELECT id, layanan, harga_api, status, catatan, operator FROM layanan_digital WHERE status = 'Normal' ORDER BY id ASC");
        if ($q) while ($r = $q->fetch_assoc()) $rows[] = [
            'SERVICEID' => (string)$r['id'], 'SERVICETYPE' => 'IMEI', 'SERVICENAME' => $r['layanan'],
            'CREDIT' => (float)$r['harga_api'], 'TIME' => '', 'MINQNT' => 1, 'MAXQNT' => 1,
            'GROUPNAME' => $r['operator'], 'INFO' => $r['catatan']
        ];
        return ['SUCCESS' => [['LIST' => $rows]]];
    }

    private function placeOrder(array $account, array $input): array
    {
        $raw = $input['parameters'] ?? '';
        $data = is_array($raw) ? $raw : (json_decode((string)$raw, true) ?: $this->parseXmlParameters((string)$raw));
        $serviceId = trim((string)($data['ID'] ?? $data['id'] ?? ''));
        $target = trim((string)($data['IMEI'] ?? $data['imei'] ?? $data['TARGET'] ?? $data['target'] ?? ''));
        if ($serviceId === '' || $target === '') return $this->error('Service ID and IMEI are required.');
        if (!preg_match('/^\d{14,16}$/', $target)) return $this->error('Invalid IMEI.');

        $stmt = $this->db->prepare("SELECT id, layanan, harga_api FROM layanan_digital WHERE id = ? AND status = 'Normal' LIMIT 1");
        $stmt->bind_param('s', $serviceId); $stmt->execute(); $service = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$service) return $this->error('Service not found or inactive.');
        $price = (float)$service['harga_api'];
        if ($price <= 0) return $this->error('Service price is invalid.');

        $this->db->begin_transaction();
        try {
            $lock = $this->db->prepare('SELECT saldo FROM users WHERE id = ? AND status = \'Aktif\' FOR UPDATE');
            $lock->bind_param('i', $account['id']); $lock->execute(); $wallet = $lock->get_result()->fetch_assoc(); $lock->close();
            if (!$wallet || (float)$wallet['saldo'] < $price) throw new RuntimeException('Insufficient balance.');

            $oid = 'D' . date('ymdHis') . random_int(1000, 9999);
            $insert = $this->db->prepare("INSERT INTO pembelian_digital (oid, provider_oid, user, layanan, harga, profit, target, no_meter, keterangan, status, date, time, place_from, provider, refund) VALUES (?, '', ?, ?, ?, 0, ?, '', '', 'Pending', CURDATE(), CURTIME(), 'DHRU API', 'LOCAL', 0)");
            $insert->bind_param('sssds', $oid, $account['username'], $service['layanan'], $price, $target); $insert->execute(); $insert->close();
            $upd = $this->db->prepare('UPDATE users SET saldo = saldo - ?, pemakaian_saldo = pemakaian_saldo + ? WHERE id = ? AND saldo >= ?');
            $upd->bind_param('ddid', $price, $price, $account['id'], $price); $upd->execute();
            if ($upd->affected_rows !== 1) { $upd->close(); throw new RuntimeException('Balance changed, please retry.'); }
            $upd->close();
            $ledger = $this->db->prepare("INSERT INTO history_saldo (username, aksi, nominal, pesan, date, time) VALUES (?, 'Pengurangan Saldo', ?, ?, CURDATE(), CURTIME())");
            $message = 'Order ID ' . $oid . ' Produk Digital via DHRU';
            $ledger->bind_param('sds', $account['username'], $price, $message); $ledger->execute(); $ledger->close();
            $this->db->commit();
            return ['SUCCESS' => [['MESSAGE' => 'Order received', 'REFERENCEID' => $oid]]];
        } catch (Throwable $e) { $this->db->rollback(); return $this->error($e->getMessage()); }
    }

    private function orderStatus(array $account, array $input): array
    {
        $raw = (string)($input['parameters'] ?? ''); $data = json_decode($raw, true) ?: $this->parseXmlParameters($raw);
        $id = trim((string)($data['ID'] ?? $data['id'] ?? ''));
        $stmt = $this->db->prepare('SELECT oid, status, keterangan FROM pembelian_digital WHERE oid = ? AND user = ? LIMIT 1');
        $stmt->bind_param('ss', $id, $account['username']); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$row) return $this->error('Order not found.');
        $status = match (strtolower($row['status'])) { 'success' => 4, 'error', 'failed', 'rejected' => 3, 'processing' => 1, default => 0 };
        return ['SUCCESS' => [['STATUS' => $status, 'REFERENCEID' => $row['oid'], 'MESSAGE' => $row['keterangan'] ?: $row['status']]]];
    }

    private function parseXmlParameters(string $xml): array
    {
        if ($xml === '' || !str_contains($xml, '<')) return [];
        $s = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NONET); if (!$s) return [];
        $out = []; foreach ($s->children() as $k => $v) $out[(string)$k] = (string)$v; return $out;
    }
    private function error(string $message): array { return ['ERROR' => [['MESSAGE' => $message]]]; }
    private function respond(array $data, string $format): string { return $format === 'XML' ? $this->xml($data) : json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); }
    private function xml(array $data): string { $xml = new SimpleXMLElement('<RESPONSE/>'); $this->arrayToXml($data, $xml); return $xml->asXML(); }
    private function arrayToXml(array $data, SimpleXMLElement $xml): void { foreach ($data as $key => $value) { $key = preg_replace('/[^A-Za-z0-9_]/', '_', (string)$key) ?: 'ITEM'; if (is_array($value)) { $node = $xml->addChild($key); $this->arrayToXml($value, $node); } else $xml->addChild($key, htmlspecialchars((string)$value, ENT_XML1)); } }
}
