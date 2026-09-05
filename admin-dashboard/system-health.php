<?php
declare(strict_types=1);
session_start();
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../lib/session_login_admin.php';
$checks=[];
$checks['PHP']=['ok'=>version_compare(PHP_VERSION,'8.2.0','>='),'value'=>PHP_VERSION];
$checks['MySQLi']=['ok'=>isset($conn)&&$conn instanceof mysqli,'value'=>isset($conn)&&$conn instanceof mysqli?'Connected':'Unavailable'];
if(isset($conn)&&$conn instanceof mysqli){
 $q=$conn->query('SELECT VERSION() AS v');$checks['Database']=['ok'=>(bool)$q,'value'=>$q?((string)($q->fetch_assoc()['v']??'Unknown')):'Query failed'];if($q)$q->free();
 foreach(['users','layanan_digital','pembelian_digital','provider','wallet_ledger','provider_orders_v2','order_menu','api_clients','payment_transactions','order_events'] as $table){$r=$conn->query("SELECT 1 FROM `{$table}` LIMIT 1");$checks[$table]=['ok'=>(bool)$r,'value'=>$r?'Ready':'Missing / migration belum dijalankan'];if($r)$r->free();}
}
require_once __DIR__.'/../lib/header_admin.php';
?>
<div class="p4-page"><section class="p4-hero"><div class="p4-eyebrow">System</div><h1 class="p4-title">System Health</h1><p class="p4-subtitle">Pemeriksaan cepat PHP, koneksi database, dan tabel fondasi transaksi.</p></section><div class="p4-grid" style="margin-top:18px"><?php foreach($checks as $name=>$c):?><div class="p4-card p4-card-body"><div class="d-flex justify-content-between align-items-center"><div><div class="p4-meta"><?=htmlspecialchars($name)?></div><strong style="font-size:18px"><?=htmlspecialchars((string)$c['value'])?></strong></div><span class="badge <?= $c['ok']?'badge-success':'badge-danger' ?>" style="border-radius:10px;padding:7px 10px"><?= $c['ok']?'OK':'FAIL' ?></span></div></div><?php endforeach;?></div><div class="p4-card p4-card-body" style="margin-top:18px"><b>Catatan</b><p class="p4-muted mb-0 mt-2">Jika tabel berstatus Missing, jalankan migration di database/migrations sesuai urutan V2 → V3 → DHRU → foundation.</p></div></div>
<?php require_once __DIR__.'/../lib/footer_admin.php'; ?>