<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/header_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $visible = (int)!empty($_POST['public_visible']);
    $stmt = $conn->prepare("UPDATE layanan_digital SET public_visible=?, updated_at=NOW() WHERE id=? AND provider='DHRU'");
    $stmt->bind_param('ii', $visible, $id); $stmt->execute(); $stmt->close();
    header('Location: dhru-products.php'); exit;
}

$q = $conn->query("SELECT id,provider_id,operator,layanan,harga,harga_api,status,public_visible FROM layanan_digital WHERE provider='DHRU' ORDER BY sort_order ASC,id DESC");
?>
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div><h3 class="mb-1">DHRU Products</h3><small>Atur produk DHRU yang boleh tampil di katalog depan.</small></div>
    <a class="btn btn-primary" href="../get/sync-dhru.php" target="_blank">Sync Produk</a>
  </div>
  <div class="card"><div class="card-body table-responsive">
    <table class="table table-hover align-middle"><thead><tr><th>Produk</th><th>Kategori</th><th>Harga API</th><th>Status</th><th>Depan</th></tr></thead><tbody>
    <?php while($r=$q->fetch_assoc()): ?>
      <tr><td><b><?=htmlspecialchars($r['layanan'])?></b><br><small><?=htmlspecialchars($r['provider_id'])?></small></td><td><?=htmlspecialchars($r['operator'])?></td><td>Rp <?=number_format((float)$r['harga_api'],0,',','.')?></td><td><?=htmlspecialchars($r['status'])?></td><td><form method="post"><input type="hidden" name="id" value="<?=$r['id']?>"><label><input type="checkbox" name="public_visible" value="1" onchange="this.form.submit()" <?=$r['public_visible']?'checked':''?>> Tampilkan</label></form></td></tr>
    <?php endwhile; ?>
    </tbody></table>
  </div></div>
</div>
<?php require_once __DIR__ . '/../lib/footer_admin.php'; ?>
