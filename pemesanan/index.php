<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_user.php';

$search = trim((string)($_GET['q'] ?? ''));
$category = trim((string)($_GET['category'] ?? ''));
$products = [];
$categories = [];

// Avoid mysqli_stmt::get_result() so the catalog also works on VPS builds without mysqlnd.
$cat = $conn->query("SELECT DISTINCT operator FROM layanan_digital WHERE status='Normal' AND operator IS NOT NULL AND operator<>'' ORDER BY operator ASC");
if ($cat) {
    while ($r = $cat->fetch_assoc()) $categories[] = (string)$r['operator'];
    $cat->free();
}

$where = ["status='Normal'"];
if ($category !== '') {
    $safeCategory = $conn->real_escape_string($category);
    $where[] = "operator='{$safeCategory}'";
}
if ($search !== '') {
    $like = '%' . $conn->real_escape_string($search) . '%';
    $where[] = "(layanan LIKE '{$like}' OR provider_id LIKE '{$like}' OR operator LIKE '{$like}')";
}

$sql = "SELECT provider_id,layanan,operator,harga,harga_api,provider,catatan,image_url,menu_label,menu_icon
        FROM layanan_digital
        WHERE " . implode(' AND ', $where) . "
        ORDER BY operator ASC, sort_order ASC, layanan ASC";

$res = $conn->query($sql);
if ($res) {
    while ($r = $res->fetch_assoc()) $products[] = $r;
    $res->free();
}

require_once __DIR__ . '/../lib/header.php';
?>
<title>Layanan Order</title>
<meta name="description" content="Pilih layanan dan lakukan order dari katalog digital.">
<style>
.catalog-v4{max-width:1180px;margin:18px auto 55px}.catalog-hero{border-radius:26px;padding:28px 30px;background:linear-gradient(135deg,#0f172a,#7f1d1d);color:#fff;box-shadow:0 18px 50px rgba(15,23,42,.16);position:relative;overflow:hidden}.catalog-hero:after{content:"";position:absolute;width:280px;height:280px;border-radius:50%;right:-100px;top:-150px;background:rgba(255,255,255,.08)}.catalog-kicker{font-size:11px;letter-spacing:.14em;text-transform:uppercase;font-weight:800;opacity:.65}.catalog-hero h1{font-size:30px;font-weight:850;margin:7px 0}.catalog-hero p{margin:0;opacity:.78}.catalog-tools{display:flex;gap:10px;flex-wrap:wrap;margin:18px 0}.catalog-tools form{display:flex;gap:8px;flex:1;min-width:250px}.catalog-tools input,.catalog-tools select{height:46px;border:1px solid #e2e8f0;border-radius:14px;background:#fff}.catalog-tools input{flex:1}.catalog-tools select{min-width:170px}.catalog-tools button{border:0;border-radius:14px;padding:0 18px;background:#0f172a;color:#fff;font-weight:700}.catalog-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px}.service-card{background:#fff;border:1px solid #e7eaf0;border-radius:20px;padding:18px;box-shadow:0 10px 32px rgba(15,23,42,.06);display:flex;flex-direction:column;min-height:205px}.service-top{display:flex;gap:12px;align-items:flex-start}.service-icon{width:48px;height:48px;border-radius:15px;background:#f1f5f9;display:grid;place-items:center;font-size:22px;flex:none}.service-title{font-weight:800;font-size:16px;line-height:1.3}.service-meta{font-size:12px;color:#64748b;margin-top:4px}.service-desc{color:#64748b;font-size:13px;margin:14px 0;min-height:38px}.service-bottom{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:10px}.service-price{font-size:18px;font-weight:850}.order-link{border:0;border-radius:12px;padding:10px 15px;background:#111827;color:#fff!important;text-decoration:none;font-weight:800}.empty{background:#fff;border:1px dashed #cbd5e1;border-radius:20px;padding:50px 20px;text-align:center;color:#64748b}@media(max-width:950px){.catalog-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:600px){.catalog-hero{padding:22px}.catalog-hero h1{font-size:25px}.catalog-grid{grid-template-columns:1fr}.catalog-tools form{flex-direction:column}.catalog-tools select,.catalog-tools button{height:46px;width:100%}}
</style>
<div class="catalog-v4">
 <section class="catalog-hero"><div class="catalog-kicker">Service Catalog</div><h1>Pilih Layanan</h1><p>Semua layanan aktif tersedia di sini. Setiap produk membuka form order sesuai kebutuhan layanan.</p></section>
 <div class="catalog-tools"><form method="get"><input name="q" value="<?=htmlspecialchars($search, ENT_QUOTES, 'UTF-8')?>" placeholder="Cari layanan, service ID..."><select name="category"><option value="">Semua kategori</option><?php foreach($categories as $c):?><option value="<?=htmlspecialchars($c, ENT_QUOTES, 'UTF-8')?>" <?=$category===$c?'selected':''?>><?=htmlspecialchars($c, ENT_QUOTES, 'UTF-8')?></option><?php endforeach;?></select><button type="submit"><i class="mdi mdi-magnify"></i> Cari</button></form></div>
 <?php if(!$products): ?><div class="empty"><i class="mdi mdi-package-variant-closed" style="font-size:48px"></i><h4 class="mt-3">Belum ada layanan aktif</h4><p class="mb-0">Admin perlu mengaktifkan produk dengan status <b>Normal</b>.</p></div><?php else: ?><div class="catalog-grid">
 <?php foreach($products as $p): ?><article class="service-card"><div class="service-top"><span class="service-icon"><i class="<?=htmlspecialchars($p['menu_icon']?:'mdi mdi-cellphone-link', ENT_QUOTES, 'UTF-8')?>"></i></span><div><div class="service-title"><?=htmlspecialchars($p['menu_label']?:$p['layanan'], ENT_QUOTES, 'UTF-8')?></div><div class="service-meta"><?=htmlspecialchars($p['operator']?:'Digital Service', ENT_QUOTES, 'UTF-8')?> · <?=htmlspecialchars(strtoupper($p['provider']?:'MANUAL'), ENT_QUOTES, 'UTF-8')?></div></div></div><div class="service-desc"><?=htmlspecialchars($p['catatan']?:'Layanan siap diproses. Form order akan menyesuaikan konfigurasi produk.', ENT_QUOTES, 'UTF-8')?></div><div class="service-bottom"><div><small class="text-muted">Mulai</small><div class="service-price">Rp <?=number_format((int)$p['harga'],0,',','.')?></div></div><a class="order-link" href="/pemesanan/order?service=<?=rawurlencode((string)$p['provider_id'])?>">Order <i class="mdi mdi-arrow-right"></i></a></div></article><?php endforeach; ?>
 </div><?php endif; ?>
</div>
<?php require_once __DIR__ . '/../lib/footer.php'; ?>
