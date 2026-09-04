<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/providers/DhruClient.php';
require_once __DIR__ . '/../lib/header_admin.php';

$provider = $conn->query("SELECT * FROM provider WHERE UPPER(code)='DHRU' LIMIT 1")->fetch_assoc() ?: null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $link = trim((string)($_POST['link'] ?? ''));
        $username = trim((string)($_POST['api_id'] ?? ''));
        $key = trim((string)($_POST['api_key'] ?? ''));
        $status = ($_POST['status'] ?? 'active') === 'active' ? 'active' : 'disabled';
        if ($link === '' || $username === '' || $key === '') {
            $_SESSION['hasil'] = ['alert'=>'danger','judul'=>'DHRU belum lengkap','pesan'=>'API URL, Username dan API Key wajib diisi.'];
        } else {
            if ($provider) {
                $stmt=$conn->prepare("UPDATE provider SET link=?,api_id=?,api_key=?,status=?,updated_at=NOW() WHERE id=?");
                $stmt->bind_param('ssssi',$link,$username,$key,$status,$provider['id']);
            } else {
                $code='DHRU';
                $stmt=$conn->prepare("INSERT INTO provider (code,link,api_key,api_id,secret_key,status,updated_at) VALUES (?,?,?,?,?,?,NOW())");
                $secret=''; $stmt->bind_param('ssssss',$code,$link,$key,$username,$secret,$status);
            }
            $ok=$stmt->execute(); $stmt->close();
            $_SESSION['hasil']=$ok?['alert'=>'success','judul'=>'DHRU tersimpan','pesan'=>'Konfigurasi DHRU berhasil disimpan.']:['alert'=>'danger','judul'=>'Gagal menyimpan','pesan'=>'Database menolak perubahan.'];
        }
        header('Location: dhru-settings.php'); exit;
    }
    if ($action === 'test') {
        if (!$provider || empty($provider['link']) || empty($provider['api_id']) || empty($provider['api_key'])) {
            $_SESSION['hasil']=['alert'=>'danger','judul'=>'DHRU belum dikonfigurasi','pesan'=>'Isi konfigurasi DHRU terlebih dahulu.'];
        } else {
            try {
                $client=new DhruClient((string)$provider['link'],(string)$provider['api_id'],(string)$provider['api_key']);
                $r=$client->accountInfo();
                $info=$r['SUCCESS'][0]['AccountInfo'] ?? [];
                $_SESSION['hasil']=['alert'=>'success','judul'=>'Koneksi DHRU OK','pesan'=>'Username: '.htmlspecialchars((string)($info['username']??$provider['api_id'])).'<br>Credit: '.htmlspecialchars((string)($info['credit']??'0')).' '.htmlspecialchars((string)($info['currency']??''))];
            } catch(Throwable $e) { $_SESSION['hasil']=['alert'=>'danger','judul'=>'Koneksi DHRU gagal','pesan'=>htmlspecialchars($e->getMessage())]; }
        }
        header('Location: dhru-settings.php'); exit;
    }
}

$provider = $conn->query("SELECT * FROM provider WHERE UPPER(code)='DHRU' LIMIT 1")->fetch_assoc() ?: null;
?>
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-4"><div><h2 class="mb-1">DHRU Provider</h2><small>Konfigurasi provider DHRU upstream yang dipakai untuk order.</small></div><a class="btn btn-light" href="action-provider.php">← Provider</a></div>
  <div class="row">
    <div class="col-lg-8"><div class="card"><div class="card-body">
      <h4 class="header-title">Connection Settings</h4><hr>
      <form method="post"><input type="hidden" name="action" value="save">
        <div class="form-group"><label>API URL</label><input name="link" class="form-control" value="<?=htmlspecialchars((string)($provider['link']??''))?>" placeholder="https://provider.example.com"></div>
        <div class="form-group"><label>Username</label><input name="api_id" class="form-control" value="<?=htmlspecialchars((string)($provider['api_id']??''))?>" autocomplete="off"></div>
        <div class="form-group"><label>API Access Key</label><input type="password" name="api_key" class="form-control" value="<?=htmlspecialchars((string)($provider['api_key']??''))?>" autocomplete="new-password"></div>
        <div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="active" <?=($provider['status']??'active')==='active'?'selected':''?>>Active</option><option value="disabled" <?=($provider['status']??'')==='disabled'?'selected':''?>>Disabled</option></select></div>
        <button class="btn btn-primary" type="submit">Simpan Konfigurasi</button>
      </form>
      <form method="post" class="mt-2"><input type="hidden" name="action" value="test"><button class="btn btn-success" type="submit">Test Connection & Account Info</button></form>
    </div></div></div>
    <div class="col-lg-4"><div class="card"><div class="card-body"><h4 class="header-title">Alur DHRU</h4><hr><p>Provider DHRU di sini adalah <b>upstream</b>. Reseller memakai endpoint DHRU Panel CEIR secara terpisah.</p><ol><li>Sync service</li><li>Atur harga jual</li><li>Publish produk</li><li>Reseller order</li><li>Panel kirim ke upstream</li><li>Status direkonsiliasi</li></ol><a class="btn btn-info btn-block" href="dhru-products.php">Kelola Produk</a><a class="btn btn-dark btn-block" href="dhru-orders.php">Monitor Order</a></div></div></div>
  </div>
</div>
<?php require_once __DIR__ . '/../lib/footer_admin.php'; ?>
