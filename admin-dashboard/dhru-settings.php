<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/providers/DhruClient.php';

$provider = null;
$q = $conn->query("SELECT id,code,link,api_key,api_id,secret_key FROM provider WHERE UPPER(code)='DHRU' LIMIT 1");
if ($q) $provider = $q->fetch_assoc() ?: null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'save') {
        $link = trim((string)($_POST['link'] ?? ''));
        $username = trim((string)($_POST['api_id'] ?? ''));
        $key = trim((string)($_POST['api_key'] ?? ''));
        if ($link === '' || $username === '' || $key === '') {
            $_SESSION['hasil'] = ['alert'=>'danger','judul'=>'DHRU belum lengkap','pesan'=>'API URL, Username dan API Access Key wajib diisi.'];
        } elseif (!filter_var($link, FILTER_VALIDATE_URL)) {
            $_SESSION['hasil'] = ['alert'=>'danger','judul'=>'API URL tidak valid','pesan'=>'Gunakan URL lengkap, misalnya https://provider.example.com.'];
        } else {
            if ($provider) {
                $stmt = $conn->prepare("UPDATE provider SET link=?,api_id=?,api_key=? WHERE id=?");
                $stmt->bind_param('sssi', $link, $username, $key, $provider['id']);
            } else {
                $code = 'DHRU'; $secret = '';
                $stmt = $conn->prepare("INSERT INTO provider (code,link,api_key,api_id,secret_key) VALUES (?,?,?,?,?)");
                $stmt->bind_param('sssss', $code, $link, $key, $username, $secret);
            }
            $ok = $stmt && $stmt->execute();
            if ($stmt) $stmt->close();
            $_SESSION['hasil'] = $ok
                ? ['alert'=>'success','judul'=>'DHRU tersimpan','pesan'=>'Konfigurasi DHRU berhasil disimpan.']
                : ['alert'=>'danger','judul'=>'Gagal menyimpan','pesan'=>'Database menolak perubahan. Pastikan tabel provider memiliki kolom standar.'];
        }
        header('Location: dhru-settings.php'); exit;
    }
    if ($action === 'test') {
        $link = trim((string)($_POST['link'] ?? ''));
        $username = trim((string)($_POST['api_id'] ?? ''));
        $key = trim((string)($_POST['api_key'] ?? ''));
        if ($link === '' || $username === '' || $key === '') {
            $q = $conn->query("SELECT link,api_id,api_key FROM provider WHERE UPPER(code)='DHRU' LIMIT 1");
            $cfg = $q ? ($q->fetch_assoc() ?: null) : null;
            $link = trim((string)($cfg['link'] ?? '')); $username = trim((string)($cfg['api_id'] ?? '')); $key = trim((string)($cfg['api_key'] ?? ''));
        }
        try {
            if ($link === '' || $username === '' || $key === '') throw new RuntimeException('Konfigurasi DHRU belum lengkap.');
            $client = new DhruClient($link, $username, $key);
            $r = $client->accountInfo();
            $info = $r['SUCCESS'][0]['AccountInfo'] ?? [];
            $_SESSION['hasil'] = ['alert'=>'success','judul'=>'Koneksi DHRU OK','pesan'=>'Username: '.htmlspecialchars((string)($info['username'] ?? $username)).'<br>Credit: '.htmlspecialchars((string)($info['credit'] ?? '0')).' '.htmlspecialchars((string)($info['currency'] ?? ''))];
        } catch (Throwable $e) {
            $_SESSION['hasil'] = ['alert'=>'danger','judul'=>'Koneksi DHRU gagal','pesan'=>htmlspecialchars($e->getMessage())];
        }
        header('Location: dhru-settings.php'); exit;
    }
}

$q = $conn->query("SELECT id,code,link,api_key,api_id,secret_key FROM provider WHERE UPPER(code)='DHRU' LIMIT 1");
$provider = $q ? ($q->fetch_assoc() ?: null) : null;
require_once __DIR__ . '/../lib/header_admin.php';
?>
<style>
.dh-settings{max-width:1180px;margin:22px auto 55px}.dh-hero{position:relative;overflow:hidden;border-radius:26px;padding:28px;background:linear-gradient(135deg,#0f172a,#312e81);color:#fff;box-shadow:0 22px 55px rgba(15,23,42,.18)}.dh-hero:after{content:"";position:absolute;width:260px;height:260px;border-radius:50%;right:-80px;top:-130px;background:rgba(255,255,255,.08)}.dh-hero h2{font-weight:850;margin:0 0 7px}.dh-hero p{margin:0;opacity:.76}.dh-grid{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(280px,.6fr);gap:18px;margin-top:18px}.dh-card{background:rgba(255,255,255,.96);border:1px solid #e8ebf0;border-radius:22px;box-shadow:0 14px 45px rgba(15,23,42,.07);padding:22px}.dh-card h4{font-weight:800}.dh-field{margin-bottom:17px}.dh-field label{font-weight:750}.dh-field .form-control{height:50px;border-radius:14px}.dh-actions{display:flex;gap:10px;flex-wrap:wrap}.dh-actions .btn{border-radius:13px;font-weight:750}.dh-flow{display:grid;gap:10px}.dh-step{display:flex;gap:11px;align-items:flex-start;padding:13px;border-radius:15px;background:#f8fafc}.dh-num{width:30px;height:30px;border-radius:10px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:800;flex:none}.dh-code{padding:12px;border-radius:14px;background:#0f172a;color:#e2e8f0;font-size:12px;overflow:auto}.dh-note{font-size:13px;color:#64748b}@media(max-width:850px){.dh-grid{grid-template-columns:1fr}.dh-settings{margin:14px 10px 40px}.dh-card{padding:17px}.dh-hero{padding:21px;border-radius:20px}}
</style>
<div class="dh-settings">
 <div class="dh-hero"><div style="font-size:11px;letter-spacing:.14em;text-transform:uppercase;opacity:.65;font-weight:800">Provider Gateway</div><h2>DHRU Connection</h2><p>Hubungkan panel ke DHRU upstream, cek Account Info, lalu sinkronkan service ke katalog reseller.</p></div>
 <div class="dh-grid">
  <div class="dh-card">
   <div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="mb-1">Connection</h4><small class="dh-note">Konfigurasi mengikuti struktur tabel provider lama agar kompatibel dengan instalasi existing.</small></div><span class="badge badge-light">DHRU Fusion</span></div>
   <form method="post"><input type="hidden" name="action" value="save">
    <div class="dh-field"><label>API Endpoint</label><input name="link" class="form-control" value="<?=htmlspecialchars((string)($provider['link']??''))?>" placeholder="https://provider.example.com"></div>
    <div class="dh-field"><label>Username</label><input name="api_id" class="form-control" value="<?=htmlspecialchars((string)($provider['api_id']??''))?>" autocomplete="off"></div>
    <div class="dh-field"><label>API Access Key</label><input type="password" name="api_key" class="form-control" value="<?=htmlspecialchars((string)($provider['api_key']??''))?>" autocomplete="new-password"></div>
    <div class="dh-actions"><button class="btn btn-primary" type="submit"><i class="mdi mdi-content-save-outline"></i> Simpan</button><button class="btn btn-success" type="submit" name="action" value="test"><i class="mdi mdi-connection"></i> Test Connection</button><a class="btn btn-light" href="action-provider.php"><i class="mdi mdi-arrow-left"></i> Provider</a></div>
   </form>
  </div>
  <div class="dh-card"><h4>Alur DHRU</h4><p class="dh-note">DHRU di halaman ini adalah upstream. Member/reseller menggunakan API DHRU milik panel secara terpisah.</p><div class="dh-flow"><div class="dh-step"><span class="dh-num">1</span><div><b>Test Connection</b><br><small class="dh-note">Account Info harus merespons.</small></div></div><div class="dh-step"><span class="dh-num">2</span><div><b>Sync Services</b><br><small class="dh-note">Ambil service dari upstream.</small></div></div><div class="dh-step"><span class="dh-num">3</span><div><b>Atur Harga</b><br><small class="dh-note">Modal dan harga reseller.</small></div></div><div class="dh-step"><span class="dh-num">4</span><div><b>Publish</b><br><small class="dh-note">Produk muncul di katalog/member.</small></div></div></div><div class="mt-3"><a class="btn btn-info btn-block" href="dhru-products.php"><i class="mdi mdi-package-variant"></i> Kelola Produk</a><a class="btn btn-dark btn-block" href="dhru-orders.php"><i class="mdi mdi-receipt-text-outline"></i> Monitor Order</a></div></div>
 </div>
</div>
<?php require_once __DIR__ . '/../lib/footer_admin.php'; ?>