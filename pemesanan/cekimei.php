<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/OrderService.php';
require '../lib/BalanceService.php';

if (isset($_POST['pesan'])) {
    require '../lib/session_login.php';

    $operator = trim((string)($_POST['operator'] ?? ''));
    $layanan = trim((string)($_POST['layanan'] ?? ''));
    $target = preg_replace('/\D+/', '', (string)($_POST['target'] ?? ''));
    $noMeter = trim((string)($_POST['no_meter'] ?? ''));

    try {
        if ($operator === '' || $operator === '0' || $layanan === '' || $layanan === '0' || $target === '') {
            throw new InvalidArgumentException('Lengkapi kategori, produk, dan nomor IMEI.');
        }
        if (!preg_match('/^\d{14,16}$/', $target)) throw new InvalidArgumentException('Nomor IMEI harus 14–16 digit.');

        $orders = new OrderService($conn);
        $order = $orders->createPendingDigital($sess_username, $layanan, $operator, $target, $noMeter, 'Website');
        $oid = $order['oid'];
        $service = $order['service'];
        $providerOid = '';

        if (strtolower((string)$service['provider']) === 'ceirgo') {
            $providerStmt = $conn->prepare("SELECT api_key,link FROM provider WHERE code='ceirgo' LIMIT 1");
            $providerStmt->execute(); $provider = $providerStmt->get_result()->fetch_assoc(); $providerStmt->close();
            if (!$provider || trim((string)$provider['api_key']) === '') throw new RuntimeException('Provider CEIRGo belum dikonfigurasi.');

            $client = new CeirGoClient((string)$provider['api_key'], !empty($provider['link']) ? (string)$provider['link'] : 'https://ceirgo.id');
            $result = $client->createOrder((string)$service['provider_id'], ['imeis' => [$target]]);
            $providerOid = (string)($result['order_id'] ?? $result['data']['order_id'] ?? $result['data']['id'] ?? '');
            if ($providerOid === '') throw new RuntimeException('Provider tidak mengembalikan Order ID.');
            $orders->markProviderAccepted($oid, $providerOid, 'Order diterima provider.');
        } elseif (strtoupper((string)$service['provider']) === 'MANUAL') {
            $orders->markProviderAccepted($oid, $oid, 'Menunggu proses manual.');
        } else {
            throw new RuntimeException('Provider produk belum didukung: ' . $service['provider']);
        }

        $_SESSION['hasil'] = [
            'alert' => 'success',
            'judul' => 'Order Berhasil',
            'pesan' => '<b>Order ID:</b> ' . htmlspecialchars($oid) . '<br>' .
                       '<b>Layanan:</b> ' . htmlspecialchars($service['layanan']) . '<br>' .
                       '<b>Target:</b> ' . htmlspecialchars($target) . '<br>' .
                       '<b>Harga:</b> Rp ' . number_format((int)$order['price'], 0, ',', '.') . '<br>' .
                       '<b>Status:</b> Processing'
        ];
    } catch (Throwable $e) {
        if (isset($oid) && isset($order)) {
            try { (new OrderService($conn))->markFailed($oid, $e->getMessage()); } catch (Throwable $ignored) {}
        }
        $_SESSION['hasil'] = ['alert'=>'danger','judul'=>'Order Gagal','pesan'=>htmlspecialchars($e->getMessage())];
    }
}

require '../lib/header.php';
?>
<title>Cek CEIR</title>
<meta name="description" content="Cek status IMEI dengan cepat dan aman.">
<style>
.ceir-shell{max-width:1080px;margin:24px auto}.ceir-hero{border-radius:24px;padding:28px;background:linear-gradient(135deg,rgba(37,99,235,.14),rgba(124,58,237,.12));border:1px solid rgba(127,127,127,.18);margin-bottom:18px}.ceir-grid{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(280px,.65fr);gap:18px}.ceir-card{border-radius:22px;border:1px solid rgba(127,127,127,.16);box-shadow:0 12px 40px rgba(0,0,0,.06);overflow:hidden}.ceir-card .card-body{padding:24px}.ceir-title{font-weight:800;margin:0}.ceir-sub{opacity:.7;margin:6px 0 0}.ceir-field{margin-bottom:18px}.ceir-field label{font-weight:700;margin-bottom:8px}.ceir-field .form-control{height:48px;border-radius:14px}.ceir-total{display:flex;align-items:center;justify-content:space-between;padding:16px;border-radius:16px;background:rgba(127,127,127,.07);margin-bottom:18px}.ceir-total strong{font-size:20px}.ceir-btn{height:50px;border-radius:15px;font-weight:800}.ceir-tip{padding:16px;border-radius:16px;background:rgba(37,99,235,.07);margin-bottom:14px}.ceir-list{padding-left:20px;margin-bottom:0}.ceir-list li{margin:9px 0}.ceir-loading{opacity:.6;pointer-events:none}@media(max-width:767px){.ceir-shell{margin:12px auto}.ceir-grid{grid-template-columns:1fr}.ceir-hero{padding:20px;border-radius:20px}.ceir-card .card-body{padding:18px}}
</style>
<div class="ceir-shell">
  <div class="ceir-hero"><div class="ceir-title h3">Cek CEIR</div><div class="ceir-sub">Pilih layanan, masukkan IMEI, lalu kirim order. Saldo dipotong otomatis setelah order tervalidasi.</div></div>
  <div class="ceir-grid">
    <div class="ceir-card card"><div class="card-body">
      <form method="POST" id="ceirForm">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($config['csrf_token'] ?? '') ?>">
        <div class="ceir-field"><label>Kategori</label><select class="form-control" name="operator" id="operator"><option value="0">Pilih kategori</option><?php $q=$conn->query("SELECT * FROM kategori_layanan WHERE nama IN ('Cekimei') ORDER BY nama ASC"); while($r=$q->fetch_assoc()): ?><option value="<?= htmlspecialchars($r['kode']) ?>"><?= htmlspecialchars($r['nama']) ?></option><?php endwhile; ?></select></div>
        <div class="ceir-field"><label>Produk</label><select class="form-control" name="layanan" id="layanan"><option value="0">Pilih kategori terlebih dahulu</option></select></div>
        <div id="catatan"></div>
        <div class="ceir-field"><label>Nomor IMEI</label><input class="form-control" type="text" inputmode="numeric" maxlength="16" name="target" id="target" placeholder="Masukkan 15 digit IMEI" autocomplete="off"></div>
        <div class="ceir-total"><span>Total pembayaran</span><strong>Rp <span id="hargaText">0</span></strong></div>
        <button class="btn btn-primary btn-block ceir-btn" type="submit" name="pesan" id="checkout" disabled><i class="mdi mdi-cart-outline"></i> Konfirmasi Order</button>
      </form>
    </div></div>
    <div class="ceir-card card"><div class="card-body"><h5 class="ceir-title">Informasi Order</h5><p class="ceir-sub">Periksa kembali data sebelum mengirim.</p><hr><div class="ceir-tip"><b>Saldo aman</b><br><small>Debit saldo dilakukan secara atomic untuk mencegah saldo minus atau terpotong dua kali.</small></div><ul class="ceir-list"><li>Pastikan IMEI benar.</li><li>Jangan tekan tombol order berkali-kali.</li><li>Order yang masih berjalan untuk target yang sama akan ditolak.</li><li>Jika provider gagal, sistem dapat melakukan refund melalui wallet.</li></ul></div></div>
  </div>
</div>
<script>
$(function(){
 const $form=$('#ceirForm'),$btn=$('#checkout'),$target=$('#target');
 function check(){ $btn.prop('disabled',$('#operator').val()==='0'||$('#layanan').val()==='0'||!/^\d{14,16}$/.test($target.val().replace(/\D/g,''))); }
 $('#operator').on('change',function(){ $('#layanan').html('<option>Memuat produk...</option>'); $.post('<?= htmlspecialchars($config['web']['url']) ?>ajax/layanan_produkdigital.php',{operator:this.value},function(x){$('#layanan').html(x);check();}); });
 $('#layanan').on('change',function(){const id=this.value; $.post('<?= htmlspecialchars($config['web']['url']) ?>ajax/harga_digital.php',{layanan:id},function(x){$('#hargaText').text(Number(x||0).toLocaleString('id-ID'));}); $.post('<?= htmlspecialchars($config['web']['url']) ?>ajax/catatan-digital.php',{layanan:id},function(x){$('#catatan').html(x);}); check();});
 $target.on('input',function(){this.value=this.value.replace(/\D/g,'').slice(0,16);check();});
 $form.on('submit',function(){if($btn.prop('disabled'))return false;$btn.prop('disabled',true).addClass('ceir-loading').html('<i class="mdi mdi-loading mdi-spin"></i> Memproses...');});
});
</script>
<?php require '../lib/footer.php'; ?>
