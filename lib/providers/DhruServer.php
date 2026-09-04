<?php
declare(strict_types=1);

final class DhruServer
{
    public function __construct(private mysqli $db) {}

    public function handle(array $input): string
    {
        $username = trim((string)($input['username'] ?? $input['api_username'] ?? $input['user'] ?? ''));
        $key = trim((string)($input['apiaccesskey'] ?? $input['key'] ?? $input['api_key'] ?? $input['apikey'] ?? $input['accesskey'] ?? ''));
        // CeirGO/DHRU compatibility also accepts the complete username.key value.
        if ($username === '' && str_contains($key, '.')) {
            [$username, $key] = explode('.', $key, 2);
        } elseif (str_contains($key, '.') && str_starts_with($key, $username . '.')) {
            $key = substr($key, strlen($username) + 1);
        }

        $action = strtolower(trim((string)($input['action'] ?? '')));
        $format = strtoupper(trim((string)($input['requestformat'] ?? 'JSON')));
        if ($username === '' || $key === '') return $this->respond($this->error('Authentication credentials are required.'), $format);

        try {
            $account = $this->authenticate($username, $key);
            if (!$account) return $this->respond($this->error('Invalid API credentials.'), $format);
            $result = match ($action) {
                'accountinfo' => $this->accountInfo($account),
                'imeiservicelist', 'servicelist' => $this->serviceList($account),
                'placeimeiorder', 'placeorder' => $this->placeOrder($account, $input),
                'placeimeiorderbulk', 'placeorderbulk' => $this->placeBulk($account, $input),
                'getimeiorder', 'getorder', 'getserverorder', 'orderstatus' => $this->orderStatus($account, $input),
                'getimeiorderbulk', 'getorderbulk', 'getserverorderbulk', 'orderstatusbulk' => $this->bulkStatus($account, $input),
                default => $this->error('Unsupported action.'),
            };
        } catch (Throwable $e) {
            $result = $this->error($e->getMessage());
        }
        return $this->respond($result, $format);
    }

    private function authenticate(string $username, string $key): ?array
    {
        $hash = hash('sha256', $key);
        $stmt = $this->db->prepare("SELECT u.id,u.username,u.nama,u.email,u.saldo,ac.id api_client_id FROM api_clients ac INNER JOIN users u ON u.id=ac.user_id WHERE u.username=? AND ac.api_key_hash=? AND ac.status='active' AND u.status='Aktif' LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('ss', $username, $hash); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
            if ($row) {
                $touch = $this->db->prepare('UPDATE api_clients SET last_used_at=NOW() WHERE id=?');
                if ($touch) { $touch->bind_param('i', $row['api_client_id']); $touch->execute(); $touch->close(); }
                return $row;
            }
        }

        // Legacy fallback for existing reseller keys.
        $stmt = $this->db->prepare("SELECT id,username,nama,email,saldo FROM users WHERE username=? AND api_key=? AND status='Aktif' LIMIT 1");
        if (!$stmt) return null;
        $stmt->bind_param('ss', $username, $key); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        return $row ?: null;
    }

    private function accountInfo(array $a): array
    {
        return ['SUCCESS' => [[
            'MESSAGE' => 'Your Account Info',
            'AccountInfo' => [
                'credit' => number_format((float)$a['saldo'], 2, '.', ''),
                'creditraw' => (float)$a['saldo'],
                'mail' => (string)($a['email'] ?? ''),
                'currency' => 'IDR',
                'username' => (string)$a['username'],
            ],
        ]]];
    }

    private function serviceList(array $a): array
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
                'TIME' => '', 'MINQNT' => 1, 'MAXQNT' => 1,
                'Requires' => ['Custom' => [['fieldname' => 'IMEI', 'fieldtype' => 'text', 'required' => 'on']]],
                'INFO' => trim((string)($r['catatan'] ?? '')),
                'PRODUCTID' => (string)$r['id'],
            ];
        }
        return ['SUCCESS' => [[
            'MESSAGE' => 'IMEI Service List',
            'LIST' => array_values($groups),
            'ACCOUNTINFO' => [[
                'credit' => number_format((float)$a['saldo'], 2, '.', ''),
                'creditraw' => (float)$a['saldo'],
                'mail' => (string)($a['email'] ?? ''),
                'currency' => 'IDR',
                'username' => (string)$a['username'],
            ]],
        ]]];
    }

    private function placeOrder(array $a, array $input): array
    {
        $p = $this->parameters($input['parameters'] ?? '');
        $serviceId = trim((string)($p['ID'] ?? $p['id'] ?? ''));
        $target = preg_replace('/\D+/', '', (string)($p['IMEI'] ?? $p['imei'] ?? $p['TARGET'] ?? $p['target'] ?? ''));
        if ($serviceId === '' || $target === '') return $this->error('Service ID and IMEI are required.');
        if (!preg_match('/^\d{14,16}$/', $target)) return $this->error('Invalid IMEI.');

        $service = $this->findService($serviceId);
        if (!$service) return $this->error('Service not found or inactive.');
        $price = (int)round((float)$service['harga_api']);
        if ($price <= 0) return $this->error('Service price is invalid.');

        $oid = 'DHRU-' . date('ymdHis') . '-' . random_int(1000, 9999);
        $localId = $this->createOrder($a, $service, $target, $price, $oid);
        $wallet = new BalanceService($this->db);
        try {
            $wallet->debitByUsername($a['username'], $price, 'dhru:debit:' . $oid, 'DHRU API order ' . $oid);
            $provider = $this->dispatch($service, $target);
            $providerId = (string)($provider['provider_order_id'] ?? '');
            if ($providerId === '') throw new RuntimeException('Provider did not return an order ID.');
            $this->accepted($oid, $providerId, (string)($provider['message'] ?? 'Order diterima provider.'));
            return ['SUCCESS' => [['MESSAGE' => 'Order received', 'REFERENCEID' => $localId]]];
        } catch (Throwable $e) {
            if ($this->transportError($e->getMessage())) {
                $this->pending($oid, $e->getMessage());
                return ['SUCCESS' => [['MESSAGE' => 'Order queued for provider reconciliation', 'REFERENCEID' => $localId]]];
            }
            $this->failed($oid, $e->getMessage());
            try { $wallet->refundByUsername($a['username'], $price, 'dhru:debit:' . $oid, 'Refund DHRU order ' . $oid); } catch (Throwable $ignored) {}
            return $this->error($e->getMessage());
        }
    }

    private function placeBulk(array $a, array $input): array
    {
        $raw = $input['parameters'] ?? '';
        $items = is_array($raw) ? $raw : (json_decode((string)$raw, true) ?: []);
        if (isset($items['ID']) || isset($items['id'])) $items = [$items];
        if (!$items) return $this->error('Bulk parameters are empty.');
        $success = []; $errors = [];
        foreach ($items as $item) {
            $r = $this->placeOrder($a, ['parameters' => $item]);
            if (isset($r['SUCCESS'])) $success[] = $r['SUCCESS'][0]; else $errors[] = $r['ERROR'][0] ?? ['MESSAGE' => 'Order failed.'];
        }
        $out = []; if ($success) $out['SUCCESS'] = $success; if ($errors) $out['ERROR'] = $errors; return $out ?: $this->error('No bulk order was accepted.');
    }

    private function orderStatus(array $a, array $input): array
    {
        $p = $this->parameters($input['parameters'] ?? '');
        $id = trim((string)($p['ID'] ?? $p['id'] ?? $input['orderid'] ?? $input['order_id'] ?? ''));
        if ($id === '') return $this->error('Order ID is required.');
        $stmt = $this->db->prepare("SELECT p.id,p.oid,p.provider_oid,p.status,p.keterangan,p.target,p.provider,po.provider_order_id FROM pembelian_digital p LEFT JOIN provider_orders_v2 po ON po.local_order_id=p.oid WHERE p.user=? AND (p.id=? OR p.oid=? OR p.provider_oid=? OR po.provider_order_id=?) LIMIT 1");
        $stmt->bind_param('sisss', $a['username'], $id, $id, $id, $id); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
        if (!$row) return $this->error('Order not found.');
        $this->refresh($row);
        $stmt = $this->db->prepare('SELECT id,oid,provider_oid,status,keterangan,target FROM pembelian_digital WHERE oid=? AND user=? LIMIT 1');
        $stmt->bind_param('ss', $row['oid'], $a['username']); $stmt->execute(); $fresh = $stmt->get_result()->fetch_assoc(); $stmt->close(); $row = $fresh ?: $row;
        $code = match (strtolower((string)$row['status'])) { 'success' => 4, 'processing','partial' => 1, 'error','failed','rejected' => 3, default => 0 };
        return ['SUCCESS' => [['STATUS' => (string)$code, 'REFERENCEID' => (string)$row['id'], 'MESSAGE' => (string)($row['keterangan'] ?: $row['status']), 'CODE' => strtolower((string)$row['status']), 'TARGET' => (string)$row['target']]]];
    }

    private function bulkStatus(array $a, array $input): array
    {
        $raw = $input['parameters'] ?? '';
        $items = is_array($raw) ? $raw : (json_decode((string)$raw, true) ?: []);
        if (isset($items['ID']) || isset($items['id'])) $items = [$items];
        if (!$items) return $this->error('Bulk order IDs are empty.');
        $success = []; $errors = [];
        foreach ($items as $item) { $r = $this->orderStatus($a, ['parameters' => $item]); if (isset($r['SUCCESS'])) $success[] = $r['SUCCESS'][0]; else $errors[] = $r['ERROR'][0] ?? ['MESSAGE' => 'Order not found.']; }
        $out = []; if ($success) $out['SUCCESS'] = $success; if ($errors) $out['ERROR'] = $errors; return $out ?: $this->error('No order status found.');
    }

    private function findService(string $id): ?array
    {
        if (ctype_digit($id)) { $s = $this->db->prepare("SELECT id,service_id,provider_id,layanan,harga_api,provider FROM layanan_digital WHERE status='Normal' AND public_visible=1 AND id=? LIMIT 1"); $n=(int)$id; $s->bind_param('i',$n); }
        else { $s = $this->db->prepare("SELECT id,service_id,provider_id,layanan,harga_api,provider FROM layanan_digital WHERE status='Normal' AND public_visible=1 AND (service_id=? OR provider_id=?) LIMIT 1"); $s->bind_param('ss',$id,$id); }
        $s->execute(); $r=$s->get_result()->fetch_assoc(); $s->close(); return $r ?: null;
    }

    private function createOrder(array $a, array $s, string $target, int $price, string $oid): int
    {
        $i=$this->db->prepare("INSERT INTO pembelian_digital (oid,provider_oid,user,layanan,harga,profit,target,no_meter,keterangan,status,date,time,place_from,provider,refund) VALUES (?, '', ?, ?, ?, 0, ?, '', 'DHRU API order received', 'Pending', CURDATE(), CURTIME(), 'DHRU API', ?, 0)");
        $i->bind_param('sssiss',$oid,$a['username'],$s['layanan'],$price,$target,$s['provider']); $i->execute(); $id=(int)$this->db->insert_id; $i->close();
        $p=$this->db->prepare("INSERT INTO provider_orders_v2 (local_order_id,provider,user_id,service_id,target,cost,sell_price,status) VALUES (?,?,?,?,?,?,?,'pending')");
        $p->bind_param('ssissii',$oid,$s['provider'],$a['id'],$s['provider_id'],$target,$price,$price); $p->execute(); $p->close(); return $id;
    }

    private function dispatch(array $s, string $target): array
    {
        $provider=strtolower(trim((string)$s['provider']));
        if ($provider==='manual') return ['provider_order_id'=>(string)$s['id'],'message'=>'Menunggu proses manual.'];
        if ($provider==='ceirgo') {
            require_once __DIR__.'/CeirGoClient.php'; $p=$this->db->query("SELECT api_key,link FROM provider WHERE code='ceirgo' LIMIT 1")->fetch_assoc();
            if (!$p || trim((string)$p['api_key'])==='') throw new RuntimeException('Provider CEIRGo belum dikonfigurasi.');
            $r=(new CeirGoClient((string)$p['api_key'],(string)($p['link']??'https://ceirgo.id')))->createOrder((string)$s['provider_id'],['imeis'=>[$target]]);
            $id=$r['order_id']??$r['data']['order_id']??$r['data']['id']??null; if($id===null||$id==='') throw new RuntimeException('CEIRGo tidak mengembalikan Order ID.');
            return ['provider_order_id'=>(string)$id,'message'=>'Order diterima CEIRGo.'];
        }
        if ($provider==='dhru') {
            require_once __DIR__.'/DhruClient.php'; $p=$this->db->query("SELECT api_key,api_id,link FROM provider WHERE code='DHRU' LIMIT 1")->fetch_assoc();
            if (!$p || trim((string)$p['api_key'])==='') throw new RuntimeException('Provider DHRU upstream belum dikonfigurasi.');
            $r=(new DhruClient((string)$p['link'],(string)($p['api_id']?:'DHRU'),(string)$p['api_key']))->placeOrder(['ID'=>(string)$s['provider_id'],'IMEI'=>$target]);
            $id=$r['SUCCESS'][0]['REFERENCEID']??null; if($id===null||$id==='') throw new RuntimeException('DHRU upstream tidak mengembalikan Order ID.');
            return ['provider_order_id'=>(string)$id,'message'=>'Order diterima DHRU upstream.'];
        }
        throw new RuntimeException('Provider produk belum didukung: '.$s['provider']);
    }

    private function refresh(array $row): void
    {
        $pid=trim((string)($row['provider_order_id']??$row['provider_oid']??'')); $provider=strtolower(trim((string)$row['provider']));
        if($pid===''||!in_array($provider,['ceirgo','dhru'],true)) return;
        try {
            if($provider==='ceirgo') { require_once __DIR__.'/CeirGoClient.php'; $p=$this->db->query("SELECT api_key,link FROM provider WHERE code='ceirgo' LIMIT 1")->fetch_assoc(); if(!$p)return; $r=(new CeirGoClient((string)$p['api_key'],(string)($p['link']??'https://ceirgo.id')))->order((int)$pid); $d=is_array($r['data']??null)?$r['data']:$r; $status=strtolower((string)($d['status']??'')); $msg=(string)($d['result']??$d['message']??''); }
            else { require_once __DIR__.'/DhruClient.php'; $p=$this->db->query("SELECT api_key,api_id,link FROM provider WHERE code='DHRU' LIMIT 1")->fetch_assoc(); if(!$p)return; $r=(new DhruClient((string)$p['link'],(string)($p['api_id']?:'DHRU'),(string)$p['api_key']))->getOrder($pid); $x=$r['SUCCESS'][0]??[]; $status=match((int)($x['STATUS']??0)){4=>'success',3=>'error',1=>'processing',default=>'pending'}; $msg=(string)($x['MESSAGE']??$x['CODE']??''); }
            $this->applyStatus((string)$row['oid'],$status,$msg);
        } catch(Throwable $ignored) {}
    }

    private function applyStatus(string $oid,string $status,string $message): void
    {
        $local=match(strtolower($status)){ 'success','completed'=>'Success','partial'=>'Partial','failed','error','rejected','cancelled'=>'Error','processing','in_process'=>'Processing',default=>'Pending' };
        $s=$this->db->prepare("UPDATE pembelian_digital SET status=?,keterangan=? WHERE oid=? AND status NOT IN ('Success','Error')"); $s->bind_param('sss',$local,$message,$oid); $s->execute(); $s->close();
        $v2=match($local){'Success'=>'success','Error'=>'failed','Partial','Processing'=>'processing',default=>'pending'};
        $s=$this->db->prepare('UPDATE provider_orders_v2 SET status=?,response_message=? WHERE local_order_id=?'); $s->bind_param('sss',$v2,$message,$oid); $s->execute(); $s->close();
    }

    private function accepted(string $oid,string $pid,string $message): void { $s=$this->db->prepare("UPDATE pembelian_digital SET provider_oid=?,status='Processing',keterangan=? WHERE oid=? AND status='Pending'"); $s->bind_param('sss',$pid,$message,$oid); $s->execute(); $s->close(); $s=$this->db->prepare("UPDATE provider_orders_v2 SET provider_order_id=?,status='processing',response_message=? WHERE local_order_id=?"); $s->bind_param('sss',$pid,$message,$oid); $s->execute(); $s->close(); }
    private function pending(string $oid,string $message): void { $s=$this->db->prepare("UPDATE pembelian_digital SET status='Pending',keterangan=? WHERE oid=?"); $s->bind_param('ss',$message,$oid); $s->execute(); $s->close(); }
    private function failed(string $oid,string $message): void { $s=$this->db->prepare("UPDATE pembelian_digital SET status='Error',keterangan=? WHERE oid=?"); $s->bind_param('ss',$message,$oid); $s->execute(); $s->close(); $s=$this->db->prepare("UPDATE provider_orders_v2 SET status='failed',response_message=? WHERE local_order_id=?"); $s->bind_param('ss',$message,$oid); $s->execute(); $s->close(); }
    private function transportError(string $m): bool { return (bool)preg_match('/timeout|timed out|connection|cURL|curl|network|resolve|connect/i',$m); }
    private function parameters(mixed $raw): array { if(is_array($raw))return $raw; $raw=trim((string)$raw); if($raw==='')return []; $j=json_decode($raw,true); if(is_array($j))return $j; if(!str_contains($raw,'<'))return []; $x=@simplexml_load_string($raw,'SimpleXMLElement',LIBXML_NONET); if(!$x)return []; $o=[]; foreach($x->children() as $k=>$v)$o[(string)$k]=(string)$v; return $o; }
    private function error(string $m): array { return ['ERROR'=>[['MESSAGE'=>$m]]]; }
    private function respond(array $data,string $format): string { $data['apiversion']='8.2'; return $format==='XML'?$this->xml($data):json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); }
    private function xml(array $data): string { $x=new SimpleXMLElement('<RESPONSE/>'); $this->xmlNodes($data,$x); return $x->asXML()?:'<RESPONSE/>'; }
    private function xmlNodes(array $data,SimpleXMLElement $x): void { foreach($data as $k=>$v){$k=preg_replace('/[^A-Za-z0-9_.-]/','_',str_replace([' ','[',']'],'_', (string)$k))?:'ITEM'; if(is_array($v)){if(array_is_list($v)){foreach($v as $item){$n=$x->addChild($k); if(is_array($item))$this->xmlNodes($item,$n);else$n[0]=htmlspecialchars((string)$item,ENT_XML1);}}else{$n=$x->addChild($k);$this->xmlNodes($v,$n);}}else$x->addChild($k,htmlspecialchars((string)$v,ENT_XML1));} }
}
