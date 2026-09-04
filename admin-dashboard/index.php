<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';

function adminCount(mysqli $db, string $table): int {
    $allowed=['users','layanan_digital','pembelian_digital','provider_orders_v2','order_menu'];
    if(!in_array($table,$allowed,true)) return 0;
    $q=$db->query("SELECT COUNT(*) c FROM `$table`");
    return $q ? (int)$q->fetch_assoc()['c'] : 0;
}

$stats=[
 'products'=>adminCount($conn,'layanan_digital'),
 'users'=>adminCount($conn,'users'),
 'orders'=>adminCount($conn,'pembelian_digital'),
 'providerOrders'=>adminCount($conn,'provider_orders_v2'),
 'menu'=>adminCount($conn,'order_menu')
];
$providerCount=0;$q=$conn->query("SELECT COUNT(*) c FROM provider");if($q)$providerCount=(int)$q->fetch_assoc()['c'];
require_once __DIR__ . '/../lib/header_admin.php';
?>
<style>
.ad-v3{max-width:1240px;margin:22px auto 50px}.ad-hero{border-radius:26px;padding:30px;background:linear-gradient(135deg,#111827,#7f1d1d);color:#fff;box-shadow:0 20px 55px rgba(15,23,42,.2);position:relative;overflow:hidden}.ad-hero:after{content:"";position:absolute;width:260px;height:260px;border-radius:50%;right:-90px;top:-120px;background:rgba(255,255,255,.07)}.ad-hero h2{font-weight:850;margin:0 0 7px}.ad-hero p{margin:0;opacity:.75}.ad-actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:20px}.ad-actions a{border-radius:12px;font-weight:700}.ad-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin:18px 0}.ad-stat{background:#fff;border:1px solid #e8ebf0;border-radius:18px;padding:19px;box-shadow:0 10px 32px rgba(15,23,42,.06)}.ad-stat .num{font-size:28px;font-weight:850}.ad-stat .lbl{font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#64748b}.ad-layout{display:grid;grid-template-columns:1fr 1fr;gap:18px}.ad-card{background:#fff;border:1px solid #e8ebf0;border-radius:20px;padding:20px;box-shadow:0 10px 32px rgba(15,23,42,.06)}.ad-card h4{font-weight:800}.ad-link{display:flex;align-items:center;gap:12px;padding:14px;border-radius:14px;background:#f8fafc;margin-top:9px;color:#1f2937;text-decoration:none}.ad-link:hover{text-decoration:none;background:#f1f5f9;color:#991b1b}.ad-icon{width:40px;height:40px;border-radius:12px;background:#fff;display:grid;place-items:center;font-size:20px}.ad-note{color:#64748b;font-size:13px}@media(max-width:900px){.ad-grid{grid-template-columns:1fr 1fr}.ad-layout{grid-template-columns:1fr}}@media(max-width:500px){.ad-grid{grid-template-columns:1fr}}
</style>
<div class="ad-v3">
 <div class="ad-hero"><h2>Control Center</h2><p>Panel CEIR V3 — pusat kontrol produk, menu order, provider, DHRU API dan transaksi.</p><div class="ad-actions"><a href="order-menu.php" class="btn btn-light"><i class="mdi mdi-view-dashboard-outline"></i> Order Menu Builder</a><a href="dhru-settings.php" class="btn btn-outline-light"><i class="mdi mdi-server-security"></i> DHRU Provider</a><a href="dhru-products.php" class="btn btn-outline-light"><i class="mdi mdi-package-variant"></i> Produk DHRU</a><a href="dhru-orders.php" class="btn btn-outline-light"><i class="mdi mdi-receipt-text-outline"></i> Order DHRU</a></div></div>
 <div class="ad-grid"><div class="ad-stat"><div class="lbl">Produk Digital</div><div class="num"><?=$stats['products']?></div></div><div class="ad-stat"><div class="lbl">Produk Menu</div><div class="num"><?=$stats['menu']?></div></div><div class="ad-stat"><div class="lbl">Total Order</div><div class="num"><?=$stats['orders']?></div></div><div class="ad-stat"><div class="lbl">Pengguna</div><div class="num"><?=$stats['users']?></div></div></div>
 <div class="ad-layout"><div class="ad-card"><h4>⚡ Quick Management</h4><p class="ad-note">Pengaturan yang paling sering dipakai admin.</p><a class="ad-link" href="order-menu.php"><span class="ad-icon"><i class="mdi mdi-menu-open"></i></span><span><b>Menu & Form Order</b><br><small>Atur produk yang tampil di sidebar + field form.</small></span></a><a class="ad-link" href="layanan-digital.php"><span class="ad-icon"><i class="mdi mdi-package-variant"></i></span><span><b>Semua Produk Digital</b><br><small>Edit harga, status, provider dan service ID.</small></span></a><a class="ad-link" href="action-provider.php"><span class="ad-icon"><i class="mdi mdi-server-network"></i></span><span><b>Provider Center</b><br><small>Kelola koneksi provider.</small></span></a></div><div class="ad-card"><h4>🔌 DHRU & API</h4><p class="ad-note">Alur reseller DHRU dibuat terpisah dari provider upstream.</p><a class="ad-link" href="dhru-settings.php"><span class="ad-icon"><i class="mdi mdi-api"></i></span><span><b>DHRU Upstream</b><br><small>Connection, account info dan test.</small></span></a><a class="ad-link" href="dhru-products.php"><span class="ad-icon"><i class="mdi mdi-sync"></i></span><span><b>Sync Produk</b><br><small>Ambil service dari DHRU tanpa menimpa harga jual.</small></span></a><a class="ad-link" href="dhru-orders.php"><span class="ad-icon"><i class="mdi mdi-format-list-bulleted"></i></span><span><b>Monitoring Order</b><br><small>Monitor status order provider.</small></span></a><div class="ad-note mt-3">Provider terdaftar: <b><?=$providerCount?></b> · Provider orders V2: <b><?=$stats['providerOrders']?></b></div></div></div>
</div>
<?php require_once __DIR__ . '/../lib/footer_admin.php'; ?>
