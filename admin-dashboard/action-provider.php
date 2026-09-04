<?php
declare(strict_types=1);
session_start();
require '../config.php';
require '../lib/session_login_admin.php';
require '../lib/providers/CeirGoClient.php';
require '../lib/header_admin.php';
$ceirgo=$conn->query("SELECT * FROM provider WHERE code='ceirgo' LIMIT 1")->fetch_assoc()?:null;$dhru=$conn->query("SELECT * FROM provider WHERE UPPER(code)='DHRU' LIMIT 1")->fetch_assoc()?:null;$wagateway=$conn->query("SELECT * FROM provider WHERE code='WAGATEWAY' LIMIT 1")->fetch_assoc()?:null;
$profile=null;$wallet=null;$error=null;if($ceirgo&&!empty($ceirgo['api_key'])){try{$client=new CeirGoClient((string)$ceirgo['api_key'],trim((string)($ceirgo['link']??''))?:'https://ceirgo.id');$profile=$client->me();$wallet=$client->wallet();}catch(Throwable $e){$error=$e->getMessage();}}
$p=is_array($profile['data']??null)?$profile['data']:($profile?:[]);$w=is_array($wallet['data']??null)?$wallet['data']:($wallet?:[]);$balance=$w['balance']??$w['saldo']??0;
?>
<div class="container-fluid py-3"><div class="d-flex justify-content-between align-items-center mb-4"><div><h2>Provider Center</h2><small>Semua provider upstream dan jalur DHRU dikelola dari sini.</small></div><a class="btn btn-secondary" href="provider.php">Provider Legacy</a></div>
<div class="row"><div class="col-lg-4"><div class="card h-100"><div class="card-body"><h4 class="header-title">CEIRGo</h4><hr><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif;?><div class="btn btn-primary btn-block">ceirgo.id</div><p class="mt-3 mb-1"><b>Username:</b> <?=htmlspecialchars((string)($p['username']??$p['name']??'-'))?></p><p class="mb-1"><b>Email:</b> <?=htmlspecialchars((string)($p['email']??'-'))?></p><p><b>Saldo:</b> Rp <?=number_format((float)$balance,0,',','.')?></p><a class="btn btn-info btn-block" href="provider.php">Edit Provider</a></div></div></div>
<div class="col-lg-4"><div class="card h-100"><div class="card-body"><h4 class="header-title">DHRU UPSTREAM</h4><hr><div class="btn btn-dark btn-block">DHRU Provider</div><p class="mt-3"><b>Status:</b> <?=htmlspecialchars((string)($dhru['status']??'belum dikonfigurasi'))?></p><p><b>Endpoint:</b> <?=htmlspecialchars((string)($dhru['link']??'-'))?></p><a class="btn btn-primary btn-block" href="dhru-settings.php">⚙ Setting DHRU</a><a class="btn btn-info btn-block" href="dhru-products.php">📦 Produk DHRU</a><a class="btn btn-success btn-block" href="dhru-orders.php">🛒 Order DHRU</a></div></div></div>
<div class="col-lg-4"><div class="card h-100"><div class="card-body"><h4 class="header-title">TOOLS</h4><hr><a class="btn btn-info btn-block" href="../get/sync-dhru.php" target="_blank">↻ Sync Produk DHRU</a><a class="btn btn-success btn-block" href="../cronsjob/status_digital" target="_blank">Status Order CEIRGo</a><a class="btn btn-secondary btn-block" href="../api/dhru" target="_blank">DHRU API Endpoint</a><?php if($wagateway):?><hr><b>WAGATEWAY aktif</b><?php endif;?></div></div></div></div></div>
<?php require '../lib/footer_admin.php'; ?>
