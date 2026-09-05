<?php
session_start();
require '../config.php';
require '../lib/session_login.php';
require '../lib/session_user.php';
require '../lib/BalanceService.php';
if ($data_user['level'] === 'Member') { $_SESSION['hasil']=['alert'=>'danger','judul'=>'Gagal!','pesan'=>'Dilarang Mengakses!.']; exit(header('Location: '.$config['web']['url'])); }
if (isset($_POST['transfer'])) {
    $tujuan=strtolower(trim((string)($_POST['tujuan']??'')));
    $jumlah=(int)($_POST['jumlah']??0);
    try {
        if($tujuan===''||$jumlah<5000)throw new InvalidArgumentException('Username tujuan dan jumlah minimal Rp 5.000 wajib diisi.');
        if($tujuan===$sess_username)throw new InvalidArgumentException('Tidak bisa transfer ke username sendiri.');
        if(!preg_match('/^[a-z0-9]+$/',$tujuan))throw new InvalidArgumentException('Format username tujuan tidak valid.');
        $ref='transfer:'.$sess_username.':'.$tujuan.':'.hash('sha256',(string)$jumlah.':'.date('YmdHi'));
        (new BalanceService($conn))->transfer($sess_username,$tujuan,$jumlah,$ref);
        $_SESSION['hasil']=['alert'=>'success','judul'=>'Transfer Berhasil','pesan'=>'Transfer <b>Rp '.number_format($jumlah,0,',','.').'</b> ke <b>'.htmlspecialchars($tujuan).'</b> berhasil.'];
    }catch(Throwable $e){$_SESSION['hasil']=['alert'=>'danger','judul'=>'Transfer Gagal','pesan'=>htmlspecialchars($e->getMessage())];}
}
require '../lib/header.php';
?>
<title>Transfer Saldo</title>
<style>.wallet-shell{max-width:760px;margin:28px auto}.wallet-card{border-radius:24px;border:1px solid rgba(127,127,127,.16);box-shadow:0 14px 45px rgba(0,0,0,.07)}.wallet-head{padding:24px;border-radius:24px 24px 0 0;background:linear-gradient(135deg,rgba(37,99,235,.12),rgba(124,58,237,.10))}.wallet-field{margin-bottom:18px}.wallet-field label{font-weight:700}.wallet-field input{height:50px;border-radius:14px}.wallet-btn{height:50px;border-radius:14px;font-weight:800}@media(max-width:767px){.wallet-shell{margin:12px auto}.wallet-head{padding:20px}}</style>
<div class="wallet-shell"><div class="wallet-card card"><div class="wallet-head"><h4 class="mb-1"><i class="mdi mdi-wallet-outline"></i> Transfer Saldo</h4><div class="text-muted">Kirim saldo dengan aman. Sistem mencegah saldo minus dan transfer ganda.</div></div><div class="card-body p-4"><form method="POST"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($config['csrf_token']??'') ?>"><div class="wallet-field"><label>Username Tujuan</label><input type="text" name="tujuan" class="form-control" placeholder="username penerima" autocomplete="off" required></div><div class="wallet-field"><label>Jumlah</label><input type="number" name="jumlah" class="form-control" min="5000" step="1" placeholder="Minimal Rp 5.000" required></div><button class="btn btn-primary btn-block wallet-btn" name="transfer"><i class="mdi mdi-send"></i> Transfer Saldo</button></form></div></div></div>
<?php require '../lib/footer.php'; ?>
