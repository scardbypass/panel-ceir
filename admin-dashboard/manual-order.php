<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/OrderCatalog.php';
require_once __DIR__ . '/../lib/OrderService.php';

if (($data_user['level'] ?? '') !== 'Developers') { http_response_code(403); exit('Akses ditolak.'); }

$selectedId = trim((string)($_GET['service'] ?? $_POST['service_id'] ?? ''));
$message = null;
$users=[];$products=[];
$q=$conn->query("SELECT username,nama,saldo FROM users WHERE status='Aktif' ORDER BY username ASC");
if($q) while($r=$q->fetch_assoc()) $users[]=$r;
$q=$conn->query("SELECT provider_id,layanan,operator,harga,provider,order_form_json FROM layanan_digital WHERE status='Normal' ORDER BY operator ASC,layanan ASC");
if($q) while($r=$q->fetch_assoc()) $products[]=$r;
$selected=null; foreach($products as $p) if($p['provider_id']===$selectedId){$selected=$p;break;}
$fields=$selected?OrderCatalog::schema((string)$selected['order_form_json']):[];
if($selected&&!$fields)$fields=OrderCatalog::defaultsForProduct((string)$selected['layanan'],(string)$selected['operator']);

if($_SERVER['REQUEST_METHOD']==='POST'){
  try{
    $username=trim((string)($_POST['username']??''));
    $serviceId=trim((string)($_POST['service_id']??''));
    $selected=OrderCatalog::service($conn,$serviceId);
    if(!$selected) throw new RuntimeException('Produk tidak tersedia.');
    $fields=OrderCatalog::schema((string)$selected['order_form_json']);
    if(!$fields)$fields=OrderCatalog::defaultsForProduct((string)$selected['layanan'],(string)$selected['operator']);
    $values=[];
    foreach($fields as $f){$v=trim((string)($_POST[$f['name']]??''));if($f['required']&&$v==='')throw new InvalidArgumentException($f['label'].' wajib diisi.');if($f['type']==='tel')$v=preg_replace('/\D+/','',$v);if($f['type']==='email'&&$v!==''&&!filter_var($v,FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException($f['label'].' tidak valid.');if($f['type']==='select'&&$v!==''&&!in_array($v,$f['options'],true))throw new InvalidArgumentException('Pilihan '.$f['label'].' tidak valid.');$values[$f['name']]=$v;}
    $target='';foreach($fields as $f)if(($values[$f['name']]??'')!==''){$target=(string)$values[$f['name']];break;}
    if($target==='')throw new InvalidArgumentException('Data order belum diisi.');
    $note=trim((string)($_POST['note']??''));
    $payload=json_encode(['manual_by'=>$sess_username,'fields'=>$values,'note'=>$note],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
    $orders=new OrderService($conn);
    $order=$orders->createPendingDigitalGeneric($username,$serviceId,$target,'','Admin Manual',$payload);
    $orders->markProviderAccepted($order['oid'],'MANUAL-'.$order['oid'],'Order dibuat manual oleh admin. '.$note);
    $_SESSION['hasil']=['alert'=>'success','judul'=>'Manual order berhasil','pesan'=>'Order <b>'.htmlspecialchars($order['oid']).'</b> dibuat untuk <b>'.htmlspecialchars($username).'</b> dan saldo member telah dipotong.'];
    header('Location: /admin-dashboard/manual-order');exit;
  }catch(Throwable $e){$message=$e->getMessage();$selectedId=trim((string)($_POST['service_id']??''));$selected=$selectedId?OrderCatalog::service($conn,$selectedId):null;$fields=$selected?OrderCatalog::schema((string)$selected['order_form_json']):[];if($selected&&!$fields)$fields=OrderCatalog::defaultsForProduct((string)$selected['layanan'],(string)$selected['operator']);}
}
require_once __DIR__.'/../lib/header_admin.php';
?>
<style>
.mo{max-width:1100px;margin:22px auto 55px}.mo-hero{padding:27px;border-radius:24px;background:linear-gradient(135deg,#111827,#7f1d1d);color:#fff;box-shadow:0 18px 45px rgba(15,23,42,.16)}.mo-hero h2{margin:0 0 6px;font-weight:850}.mo-hero p{margin:0;opacity:.75}.mo-grid{display:grid;grid-template-columns:.85fr 1.15fr;gap:18px;margin-top:18px}.mo-card{background:#fff;border:1px solid #e7eaf0;border-radius:20px;box-shadow:0 10px 35px rgba(15,23,42,.06);padding:20px}.product-list{max-height:620px;overflow:auto}.product{display:block;padding:13px;border:1px solid #edf0f4;border-radius:14px;margin-bottom:9px;color:#1f2937;text-decoration:none}.product:hover,.product.active{background:#f8fafc;border-color:#cbd5e1;text-decoration:none}.product strong{display:block}.product small{color:#64748b}.price{font-size:20px;font-weight:850}.field{margin-bottom:15px}.field label{font-weight:750}.form-control{border-radius:13px;height:47px}.note{height:100px!important}.manual-btn{height:50px;border:0;border-radius:14px;width:100%;font-weight:800;background:#111827;color:#fff}.empty{text-align:center;padding:55px 15px;color:#64748b}@media(max-width:850px){.mo-grid{grid-template-columns:1fr}}
</style>
<div class="mo">
 <div class="mo-hero"><h2><i class="mdi mdi-account-cash-outline"></i> Manual Order</h2><p>Admin membuat order atas nama member. Harga tetap memakai harga jual produk dan saldo member dipotong otomatis.</p></div>
 <?php if($message):?><div class="alert alert-danger mt-3"><b>Order gagal:</b> <?=htmlspecialchars($message)?></div><?php endif;?>
 <div class="mo-grid"><div class="mo-card"><h4 class="mb-1">1. Pilih Produk</h4><small class="text-muted">Semua produk berstatus Normal.</small><div class="product-list mt-3">
 <?php foreach($products as $p):?><a class="product <?=$selectedId===$p['provider_id']?'active':''?>" href="?service=<?=rawurlencode($p['provider_id'])?>"><strong><?=htmlspecialchars($p['layanan'])?></strong><small><?=htmlspecialchars($p['operator'])?> · <?=htmlspecialchars(strtoupper($p['provider']?:'MANUAL'))?> · ID <?=htmlspecialchars($p['provider_id'])?></small><div class="price mt-1">Rp <?=number_format((int)$p['harga'],0,',','.')?></div></a><?php endforeach;?>
 </div></div>
 <div class="mo-card"><h4 class="mb-1">2. Data Order</h4>
 <?php if(!$selected):?><div class="empty"><i class="mdi mdi-cursor-default-click-outline" style="font-size:45px"></i><h5 class="mt-3">Pilih produk dahulu</h5><p>Form order akan mengikuti konfigurasi produk.</p></div><?php else:?>
 <div class="alert alert-light border"><b><?=htmlspecialchars($selected['layanan'])?></b><br><span class="text-muted">Harga member: Rp <?=number_format((int)$selected['harga'],0,',','.')?></span></div>
 <form method="post"><input type="hidden" name="service_id" value="<?=htmlspecialchars($selected['provider_id'])?>"><div class="field"><label>Member</label><select class="form-control" name="username" required><option value="">Pilih member</option><?php foreach($users as $u):?><option value="<?=htmlspecialchars($u['username'])?>" <?=((string)($_POST['username']??'')===$u['username'])?'selected':''?>><?=htmlspecialchars($u['username'].' — '.$u['nama'].' — Rp '.number_format((int)$u['saldo'],0,',','.'))?></option><?php endforeach;?></select></div>
 <?php foreach($fields as $f):?><div class="field"><label><?=htmlspecialchars($f['label'])?><?= $f['required']?' *':''?></label><?php if($f['type']==='textarea'):?><textarea class="form-control note" name="<?=htmlspecialchars($f['name'])?>" placeholder="<?=htmlspecialchars($f['placeholder'])?>" <?=$f['required']?'required':''?>><?=htmlspecialchars((string)($_POST[$f['name']]??''))?></textarea><?php elseif($f['type']==='select'):?><select class="form-control" name="<?=htmlspecialchars($f['name'])?>" <?=$f['required']?'required':''?>><option value="">Pilih <?=htmlspecialchars($f['label'])?></option><?php foreach($f['options'] as $o):?><option value="<?=htmlspecialchars($o)?>"><?=htmlspecialchars($o)?></option><?php endforeach;?></select><?php else:?><input class="form-control" type="<?=htmlspecialchars($f['type'])?>" name="<?=htmlspecialchars($f['name'])?>" placeholder="<?=htmlspecialchars($f['placeholder'])?>" value="<?=htmlspecialchars((string)($_POST[$f['name']]??''))?>" <?=$f['required']?'required':''?>><?php endif;?></div><?php endforeach;?>
 <div class="field"><label>Catatan Admin</label><textarea class="form-control note" name="note" placeholder="Contoh: diproses manual via provider lain..."><?=htmlspecialchars((string)($_POST['note']??''))?></textarea></div><button class="manual-btn" type="submit"><i class="mdi mdi-check-circle-outline"></i> Buat Manual Order · Rp <?=number_format((int)$selected['harga'],0,',','.')?></button></form>
 <?php endif;?></div></div>
</div>
<?php require_once __DIR__.'/../lib/footer_admin.php'; ?>
