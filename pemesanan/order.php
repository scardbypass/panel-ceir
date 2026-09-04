<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_user.php';
require_once __DIR__ . '/../lib/OrderCatalog.php';
require_once __DIR__ . '/../lib/OrderService.php';
require_once __DIR__ . '/../lib/BalanceService.php';
require_once __DIR__ . '/../lib/providers/CeirGoClient.php';
require_once __DIR__ . '/../lib/providers/DhruClient.php';
require_once __DIR__ . '/../lib/csrf_token.php';

$serviceId = trim((string)($_GET['service'] ?? $_POST['service_id'] ?? ''));
$service = $serviceId !== '' ? OrderCatalog::service($conn, $serviceId) : null;
if (!$service) {
    http_response_code(404);
    exit('Produk tidak ditemukan atau sedang tidak tersedia.');
}

$fields = OrderCatalog::schema((string)($service['order_form_json'] ?? ''));
if (!$fields) $fields = OrderCatalog::defaultsForProduct((string)$service['layanan'], (string)$service['tipe']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan'])) {
    require_once __DIR__ . '/../lib/session_login.php';
    $values = [];
    $oid = '';

    try {
        foreach ($fields as $field) {
            $name = $field['name'];
            $value = trim((string)($_POST[$name] ?? ''));

            if ($field['required'] && $value === '') {
                throw new InvalidArgumentException($field['label'] . ' wajib diisi.');
            }
            if ($field['type'] === 'tel') {
                $value = preg_replace('/\D+/', '', $value) ?? '';
            }
            if ($value !== '' && $field['min'] !== null && $field['type'] !== 'text' && strlen($value) < $field['min']) {
                throw new InvalidArgumentException($field['label'] . ' terlalu pendek.');
            }
            if ($value !== '' && $field['max'] !== null && strlen($value) > $field['max']) {
                throw new InvalidArgumentException($field['label'] . ' terlalu panjang.');
            }
            if ($field['type'] === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException($field['label'] . ' tidak valid.');
            }
            if ($field['type'] === 'select' && $value !== '' && !in_array($value, $field['options'], true)) {
                throw new InvalidArgumentException('Pilihan ' . $field['label'] . ' tidak valid.');
            }
            $values[$name] = $value;
        }

        $target = '';
        foreach ($fields as $field) {
            if (($values[$field['name']] ?? '') !== '') {
                $target = (string)$values[$field['name']];
                break;
            }
        }
        if ($target === '') throw new InvalidArgumentException('Data order belum diisi.');

        $payload = json_encode($values, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $orders = new OrderService($conn);
        $order = $orders->createPendingDigitalGeneric($sess_username, $service['provider_id'], $target, '', 'Website', $payload);
        $oid = (string)$order['oid'];
        $provider = strtolower(trim((string)$service['provider']));
        $providerOid = '';

        if ($provider === 'ceirgo') {
            $q = $conn->query("SELECT api_key,link FROM provider WHERE LOWER(code)='ceirgo' LIMIT 1");
            $cfg = $q ? ($q->fetch_assoc() ?: null) : null;
            if ($q) $q->free();
            if (!$cfg || trim((string)$cfg['api_key']) === '') {
                throw new RuntimeException('Provider CEIRGo belum dikonfigurasi.');
            }

            $params = [];
            foreach ($values as $key => $value) {
                if ($value === '') continue;
                if (strtolower($key) === 'imei') {
                    $params['imeis'] = [$value];
                } elseif (strtolower($key) === 'imeis') {
                    $params['imeis'] = array_values(array_filter(preg_split('/[,\s]+/', $value) ?: []));
                } else {
                    $params[$key] = $value;
                }
            }

            $client = new CeirGoClient((string)$cfg['api_key'], trim((string)($cfg['link'] ?? '')) ?: 'https://ceirgo.id');
            $result = $client->createOrder((string)$service['provider_id'], $params);
            $providerOid = (string)($result['order_id'] ?? $result['data']['order_id'] ?? $result['data']['id'] ?? '');
            if ($providerOid === '') throw new RuntimeException('CEIRGo tidak mengembalikan Order ID.');
        } elseif ($provider === 'dhru') {
            $q = $conn->query("SELECT link,api_id,api_key,status FROM provider WHERE UPPER(code)='DHRU' LIMIT 1");
            $cfg = $q ? ($q->fetch_assoc() ?: null) : null;
            if ($q) $q->free();
            if (!$cfg || strtolower((string)($cfg['status'] ?? 'active')) === 'disabled') {
                throw new RuntimeException('Provider DHRU sedang dinonaktifkan.');
            }

            $client = new DhruClient((string)$cfg['link'], (string)$cfg['api_id'], (string)$cfg['api_key']);
            $params = ['ID' => (string)$service['provider_id']];
            foreach ($values as $key => $value) {
                if ($value !== '') $params[strtoupper($key)] = $value;
            }
            $result = $client->placeOrder($params);
            $providerOid = (string)($result['SUCCESS'][0]['REFERENCEID'] ?? $result['SUCCESS'][0]['referenceid'] ?? '');
            if ($providerOid === '') throw new RuntimeException('DHRU tidak mengembalikan REFERENCEID.');
        } elseif ($provider === 'manual') {
            $providerOid = $oid;
        } else {
            throw new RuntimeException('Provider produk belum didukung: ' . $service['provider']);
        }

        $orders->markProviderAccepted($oid, $providerOid, 'Order diterima provider. Data: ' . $payload);
        $_SESSION['hasil'] = [
            'alert' => 'success',
            'judul' => 'Order berhasil',
            'pesan' => '<b>Order ID:</b> ' . htmlspecialchars($oid, ENT_QUOTES, 'UTF-8') . '<br><b>Produk:</b> ' . htmlspecialchars($service['layanan'], ENT_QUOTES, 'UTF-8') . '<br><b>Total:</b> Rp ' . number_format((int)$order['price'], 0, ',', '.') . '<br><b>Status:</b> Processing',
        ];
        header('Location: /riwayat/pemesanan-digital');
        exit;
    } catch (Throwable $e) {
        error_log('Digital order failed [' . ($oid ?: 'no-oid') . ']: ' . $e->getMessage());
        if ($oid !== '') {
            try {
                (new OrderService($conn))->markFailed($oid, $e->getMessage());
            } catch (Throwable $ignored) {
                error_log('Could not mark failed order [' . $oid . ']: ' . $ignored->getMessage());
            }
        }
        $_SESSION['hasil'] = ['alert' => 'danger', 'judul' => 'Order gagal', 'pesan' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')];
    }
}

require_once __DIR__ . '/../lib/header.php';
?>
<title><?=htmlspecialchars((string)$service['layanan'], ENT_QUOTES, 'UTF-8')?> — Order</title>
<meta name="description" content="Order <?=htmlspecialchars((string)$service['layanan'], ENT_QUOTES, 'UTF-8')?> dengan proses otomatis.">
<style>
.order-v3{max-width:1100px;margin:18px auto 50px}.order-hero{border-radius:24px;padding:26px;background:linear-gradient(135deg,#111827,#334155);color:#fff;box-shadow:0 18px 45px rgba(15,23,42,.18);position:relative;overflow:hidden}.order-hero:after{content:"";position:absolute;width:180px;height:180px;border-radius:50%;right:-60px;top:-70px;background:rgba(255,255,255,.09)}.order-kicker{font-size:12px;text-transform:uppercase;letter-spacing:.12em;opacity:.7;font-weight:800}.order-hero h1{font-size:27px;font-weight:850;margin:7px 0}.order-hero p{margin:0;opacity:.76}.order-grid{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(270px,.65fr);gap:18px;margin-top:18px}.order-card{background:#fff;border:1px solid #e7eaf0;border-radius:22px;box-shadow:0 12px 40px rgba(15,23,42,.07);overflow:hidden}.order-body{padding:24px}.field{margin-bottom:17px}.field label{font-weight:750;margin-bottom:7px}.field .form-control{height:50px;border-radius:14px;border:1px solid #dce1e8;box-shadow:none}.field textarea.form-control{height:110px;padding-top:12px}.field .form-control:focus{border-color:#64748b;box-shadow:0 0 0 3px rgba(100,116,139,.10)}.price-box{display:flex;align-items:center;justify-content:space-between;padding:17px;border-radius:16px;background:#f8fafc;border:1px solid #eef2f7;margin:4px 0 16px}.price-box strong{font-size:22px}.order-btn{height:52px;border:0;border-radius:16px;font-weight:800;width:100%;background:linear-gradient(135deg,#334155,#111827);color:#fff}.order-btn:disabled{opacity:.5}.info-item{display:flex;gap:12px;padding:13px 0;border-bottom:1px solid #eef0f4}.info-item:last-child{border-bottom:0}.info-icon{width:38px;height:38px;border-radius:12px;background:#f1f5f9;display:grid;place-items:center;flex:none}.muted{color:#64748b}@media(max-width:800px){.order-grid{grid-template-columns:1fr}.order-body{padding:18px}.order-hero{padding:21px;border-radius:20px}}
</style>
<div class="order-v3">
 <div class="order-hero"><div class="order-kicker">Digital Service</div><h1><?=htmlspecialchars((string)($service['menu_label'] ?: $service['layanan']), ENT_QUOTES, 'UTF-8')?></h1><p>Form order dibuat otomatis dari konfigurasi produk. Pilih data yang diperlukan, lalu kirim sekali.</p></div>
 <div class="order-grid">
  <div class="order-card"><div class="order-body"><form method="post" id="orderForm"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars((string)($config['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8')?>"><input type="hidden" name="service_id" value="<?=htmlspecialchars((string)$service['provider_id'], ENT_QUOTES, 'UTF-8')?>">
   <?php foreach($fields as $f): ?><div class="field"><label><?=htmlspecialchars((string)$f['label'], ENT_QUOTES, 'UTF-8')?><?= $f['required']?' *':'' ?></label><?php if($f['type']==='textarea'): ?><textarea class="form-control" name="<?=htmlspecialchars((string)$f['name'], ENT_QUOTES, 'UTF-8')?>" placeholder="<?=htmlspecialchars((string)$f['placeholder'], ENT_QUOTES, 'UTF-8')?>" <?= $f['required']?'required':'' ?>></textarea><?php elseif($f['type']==='select'): ?><select class="form-control" name="<?=htmlspecialchars((string)$f['name'], ENT_QUOTES, 'UTF-8')?>" <?= $f['required']?'required':'' ?>><option value="">Pilih <?=htmlspecialchars((string)$f['label'], ENT_QUOTES, 'UTF-8')?></option><?php foreach($f['options'] as $o): ?><option value="<?=htmlspecialchars((string)$o, ENT_QUOTES, 'UTF-8')?>"><?=htmlspecialchars((string)$o, ENT_QUOTES, 'UTF-8')?></option><?php endforeach; ?></select><?php else: ?><input class="form-control" type="<?=htmlspecialchars((string)$f['type'], ENT_QUOTES, 'UTF-8')?>" name="<?=htmlspecialchars((string)$f['name'], ENT_QUOTES, 'UTF-8')?>" inputmode="<?= $f['type']==='tel'?'numeric':'text' ?>" placeholder="<?=htmlspecialchars((string)$f['placeholder'], ENT_QUOTES, 'UTF-8')?>" <?= $f['min']!==null?'minlength="'.(int)$f['min'].'"':'' ?> <?= $f['max']!==null?'maxlength="'.(int)$f['max'].'"':'' ?> <?= $f['required']?'required':'' ?>><?php endif; ?></div><?php endforeach; ?>
   <div class="price-box"><span class="muted">Total pembayaran</span><strong>Rp <?=number_format((int)$service['harga'],0,',','.')?></strong></div><button class="order-btn" type="submit" name="pesan" id="orderBtn"><i class="mdi mdi-cart-check-outline"></i> Order Sekarang</button>
   <p class="text-center muted mt-3 mb-0" style="font-size:13px">Dengan melakukan order, saldo akan dipotong sesuai harga produk.</p>
  </form></div></div>
  <div class="order-card"><div class="order-body"><h4 class="mb-1">Informasi Produk</h4><p class="muted mb-3"><?=htmlspecialchars((string)($service['catatan']??''), ENT_QUOTES, 'UTF-8')?>&nbsp;</p><div class="info-item"><span class="info-icon"><i class="mdi mdi-tag-outline"></i></span><div><b>Service ID</b><div class="muted"><?=htmlspecialchars((string)$service['provider_id'], ENT_QUOTES, 'UTF-8')?></div></div></div><div class="info-item"><span class="info-icon"><i class="mdi mdi-server-outline"></i></span><div><b>Provider</b><div class="muted"><?=htmlspecialchars(strtoupper((string)$service['provider']), ENT_QUOTES, 'UTF-8')?></div></div></div><div class="info-item"><span class="info-icon"><i class="mdi mdi-shield-check-outline"></i></span><div><b>Status</b><div class="muted">Tersedia & siap dipesan</div></div></div><div class="info-item"><span class="info-icon"><i class="mdi mdi-alert-circle-outline"></i></span><div><b>Periksa data</b><div class="muted">Pastikan data yang dimasukkan benar sebelum menekan Order.</div></div></div></div></div>
 </div>
</div>
<script>document.getElementById('orderForm').addEventListener('submit',function(){var b=document.getElementById('orderBtn');b.disabled=true;b.innerHTML='<i class="mdi mdi-loading mdi-spin"></i> Memproses Order...';});</script>
<?php require_once __DIR__ . '/../lib/footer.php'; ?>
