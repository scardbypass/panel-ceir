<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/OrderCatalog.php';
require_once __DIR__ . '/../lib/header.php';

$search = trim((string)($_GET['search'] ?? ''));
$sql = "SELECT provider_id,layanan,operator,harga,status,provider FROM layanan_digital WHERE status='Normal' AND COALESCE(public_visible,1)=1";
if ($search !== '') $sql .= " AND layanan LIKE '%" . $conn->real_escape_string($search) . "%'";
$sql .= " ORDER BY operator ASC,sort_order ASC,layanan ASC";
$q = $conn->query($sql);
$products = [];
if ($q) while ($row=$q->fetch_assoc()) $products[]=$row;
?>
<title>Produk & Harga <?php echo htmlspecialchars($data['short_title']); ?></title>
<meta name="description" content="Daftar produk dan layanan digital yang tersedia.">
<style>
.price-v3{max-width:1180px;margin:18px auto 50px}.price-hero{padding:28px;border-radius:24px;background:linear-gradient(135deg,#111827,#334155);color:#fff;box-shadow:0 18px 45px rgba(15,23,42,.18)}.price-hero h1{font-weight:850;margin:0 0 6px}.price-hero p{margin:0;opacity:.75}.price-search{margin-top:16px;display:flex;gap:8px}.price-search input{height:48px;border:0;border-radius:14px;padding:0 16px;flex:1}.price-search button{border:0;border-radius:14px;padding:0 20px;font-weight:700}.price-table{margin-top:18px;background:#fff;border:1px solid #e7eaf0;border-radius:20px;box-shadow:0 12px 40px rgba(15,23,42,.07);overflow:hidden}.price-table table{margin:0}.price-table th{border:0;background:#f8fafc;color:#64748b;font-size:12px;text-transform:uppercase}.price-table td{vertical-align:middle}.service-name{font-weight:750}.service-id{font-size:12px;color:#94a3b8}.buy-btn{border-radius:11px;font-weight:700}.empty{padding:60px;text-align:center;color:#64748b}@media(max-width:700px){.price-hero{padding:21px;border-radius:20px}.price-table{overflow:auto}.price-table table{min-width:700px}}
</style>
<div class="price-v3">
 <div class="price-hero"><h1>Produk & Harga</h1><p>Catalog produk aktif. Harga provider/internal tidak ditampilkan kepada publik.</p><form class="price-search" method="get"><input name="search" value="<?=htmlspecialchars($search)?>" placeholder="Cari produk..."><button class="btn btn-light" type="submit">Cari</button></form></div>
 <div class="price-table"><table class="table"><thead><tr><th>Produk</th><th>Kategori</th><th>Provider</th><th>Harga</th><th></th></tr></thead><tbody>
 <?php if(!$products): ?><tr><td colspan="5" class="empty">Produk tidak ditemukan.</td></tr><?php else: foreach($products as $p): ?><tr><td><div class="service-name"><?=htmlspecialchars($p['layanan'])?></div><div class="service-id">Service ID <?=htmlspecialchars($p['provider_id'])?></div></td><td><?=htmlspecialchars($p['operator'])?></td><td><?=htmlspecialchars(strtoupper($p['provider']))?></td><td><b>Rp <?=number_format((int)$p['harga'],0,',','.')?></b></td><td><?php if(isset($_SESSION['user'])): ?><a class="btn btn-primary btn-sm buy-btn" href="/pemesanan/order?service=<?=rawurlencode($p['provider_id'])?>">Order</a><?php endif; ?></td></tr><?php endforeach; endif; ?>
 </tbody></table></div>
</div>
<?php require_once __DIR__ . '/../lib/footer.php'; ?>
