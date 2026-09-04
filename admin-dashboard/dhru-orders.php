<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/header_admin.php';
$statusFilter=trim((string)($_GET['status']??''));
$where="p.provider='DHRU'";$params=[];$types='';
if($statusFilter!==''){$where.=' AND p.status=?';$params[]=$statusFilter;$types.='s';}
$sql="SELECT p.id,p.oid,p.provider_oid,p.user,p.layanan,p.harga,p.target,p.status,p.keterangan,p.date,p.time,po.provider_order_id,po.status AS v2_status FROM pembelian_digital p LEFT JOIN provider_orders_v2 po ON po.local_order_id=p.oid WHERE $where ORDER BY p.id DESC LIMIT 200";
$s=$conn->prepare($sql);if($params)$s->bind_param($types,...$params);$s->execute();$q=$s->get_result();
?>
<div class="container-fluid py-3"><div class="d-flex justify-content-between align-items-center mb-4"><div><h2 class="mb-1">DHRU Orders</h2><small>Monitoring order yang datang dari atau dikirim melalui jalur DHRU.</small></div><div><a class="btn btn-dark" href="dhru-settings.php">⚙ Provider</a> <a class="btn btn-info" href="dhru-products.php">Produk</a></div></div>
<div class="row mb-3"><div class="col"><a class="btn btn-outline-secondary" href="dhru-orders.php">Semua</a> <a class="btn btn-outline-warning" href="?status=Pending">Pending</a> <a class="btn btn-outline-info" href="?status=Processing">Processing</a> <a class="btn btn-outline-success" href="?status=Success">Success</a> <a class="btn btn-outline-danger" href="?status=Error">Error</a></div></div>
<div class="card"><div class="card-body table-responsive"><table class="table table-hover"><thead><tr><th>Order</th><th>Reseller</th><th>Produk</th><th>Target</th><th>Harga</th><th>Provider ID</th><th>Status</th><th>Waktu</th><th>Keterangan</th></tr></thead><tbody><?php while($r=$q->fetch_assoc()):?><tr><td><b>#<?=htmlspecialchars($r['oid'])?></b><br><small>local ID <?=$r['id']?></small></td><td><?=htmlspecialchars($r['user'])?></td><td><?=htmlspecialchars($r['layanan'])?></td><td><?=htmlspecialchars($r['target'])?></td><td>Rp <?=number_format((float)$r['harga'],0,',','.')?></td><td><?=htmlspecialchars((string)($r['provider_order_id']?:$r['provider_oid']?:'-'))?></td><td><span class="badge badge-<?=strtolower($r['status'])==='success'?'success':(strtolower($r['status'])==='error'?'danger':'warning')?>"><?=htmlspecialchars($r['status'])?></span></td><td><?=htmlspecialchars($r['date'].' '.$r['time'])?></td><td><?=htmlspecialchars((string)$r['keterangan'])?></td></tr><?php endwhile;?></tbody></table></div></div></div>
<?php $s->close();require_once __DIR__ . '/../lib/footer_admin.php'; ?>
