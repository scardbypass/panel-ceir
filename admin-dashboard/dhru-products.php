<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';
require_once __DIR__ . '/../lib/header_admin.php';

function dhruFlash(string $alert,string $title,string $message): void { $_SESSION['hasil']=['alert'=>$alert,'judul'=>$title,'pesan'=>$message]; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action=(string)($_POST['action']??'');
    try {
        if ($action==='visibility') {
            $id=(int)($_POST['id']??0); $visible=(int)!empty($_POST['public_visible']);
            $stmt=$conn->prepare("UPDATE layanan_digital SET public_visible=?,updated_at=NOW() WHERE id=? AND provider='DHRU'"); $stmt->bind_param('ii',$visible,$id); $stmt->execute(); $stmt->close();
        } elseif ($action==='save') {
            $id=(int)($_POST['id']??0); $sid=trim((string)($_POST['service_id']??'')); $group=trim((string)($_POST['dhru_group']??'DHRU')); $name=trim((string)($_POST['layanan']??''));
            $cost=max(0,(float)($_POST['harga_api']??0)); $sell=max(0,(float)($_POST['harga']??0)); $status=($_POST['status']??'Normal')==='Normal'?'Normal':'Gangguan'; $visible=(int)!empty($_POST['public_visible']); $note=trim((string)($_POST['catatan']??''));
            if($sid===''||$name==='') throw new RuntimeException('Service ID dan nama produk wajib diisi.');
            if($sell<=0) $sell=$cost;
            if($id>0){$stmt=$conn->prepare("UPDATE layanan_digital SET service_id=?,provider_id=?,operator=?,dhru_group=?,layanan=?,harga=?,harga_api=?,profit=?,status=?,tipe='Digital',catatan=?,public_visible=?,updated_at=NOW() WHERE id=? AND provider='DHRU'"); $profit=$sell-$cost; $stmt->bind_param('sssssdddsii',$sid,$sid,$group,$group,$name,$sell,$cost,$profit,$status,$note,$visible,$id);}
            else{$provider='DHRU';$profit=$sell-$cost;$stmt=$conn->prepare("INSERT INTO layanan_digital (service_id,provider_id,operator,dhru_group,layanan,harga,harga_api,profit,status,provider,tipe,catatan,public_visible,sort_order,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?, 'Digital',?,?,0,NOW())");$stmt->bind_param('ssssdddsssi',$sid,$sid,$group,$group,$name,$sell,$cost,$profit,$status,$provider,$note,$visible);}
            $stmt->execute();$stmt->close();dhruFlash('success','Produk tersimpan','Produk DHRU berhasil disimpan.');
        } elseif ($action==='bulk') {
            $ids=$_POST['ids']??[]; $mode=(string)($_POST['mode']??''); $visible=$mode==='show'?1:0;
            if(is_array($ids)&&$ids){$stmt=$conn->prepare("UPDATE layanan_digital SET public_visible=?,updated_at=NOW() WHERE id=? AND provider='DHRU'");foreach($ids as $id){$id=(int)$id;$stmt->bind_param('ii',$visible,$id);$stmt->execute();}$stmt->close();}
        }
    } catch(Throwable $e){dhruFlash('danger','Gagal',''.htmlspecialchars($e->getMessage()));}
    header('Location: dhru-products.php');exit;
}

$edit=null;if(isset($_GET['edit'])){$id=(int)$_GET['edit'];$stmt=$conn->prepare("SELECT * FROM layanan_digital WHERE id=? AND provider='DHRU' LIMIT 1");$stmt->bind_param('i',$id);$stmt->execute();$edit=$stmt->get_result()->fetch_assoc();$stmt->close();}
$q=$conn->query("SELECT id,service_id,provider_id,operator,dhru_group,layanan,harga,harga_api,profit,status,public_visible FROM layanan_digital WHERE provider='DHRU' ORDER BY operator ASC,sort_order ASC,id DESC");
?>
<div class="container-fluid py-3">
 <div class="d-flex justify-content-between align-items-center mb-4"><div><h2 class="mb-1">DHRU Products</h2><small>Sync katalog provider, atur markup, lalu publish hanya produk yang siap dijual.</small></div><div><a class="btn btn-dark" href="dhru-settings.php">⚙ Provider</a> <a class="btn btn-success" href="../get/sync-dhru.php" target="_blank">↻ Sync Provider</a></div></div>
 <div class="card mb-4"><div class="card-body"><h4 class="header-title"><?= $edit?'Edit Produk':'Tambah Produk Manual' ?></h4><hr><form method="post"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id']??0)?>"><div class="row"><div class="col-md-2 form-group"><label>Service ID</label><input required name="service_id" class="form-control" value="<?=htmlspecialchars((string)($edit['service_id']??''))?>"></div><div class="col-md-2 form-group"><label>Group/Kategori</label><input name="dhru_group" class="form-control" value="<?=htmlspecialchars((string)($edit['dhru_group']??$edit['operator']??'DHRU'))?>"></div><div class="col-md-4 form-group"><label>Nama Produk</label><input required name="layanan" class="form-control" value="<?=htmlspecialchars((string)($edit['layanan']??''))?>"></div><div class="col-md-2 form-group"><label>Harga Provider</label><input type="number" min="0" step="1" name="harga_api" class="form-control" value="<?=htmlspecialchars((string)($edit['harga_api']??0))?>"></div><div class="col-md-2 form-group"><label>Harga Jual</label><input type="number" min="0" step="1" name="harga" class="form-control" value="<?=htmlspecialchars((string)($edit['harga']??0))?>"></div></div><div class="row"><div class="col-md-3 form-group"><label>Status</label><select name="status" class="form-control"><option value="Normal" <?=($edit['status']??'Normal')==='Normal'?'selected':''?>>Normal</option><option value="Gangguan" <?=($edit['status']??'')==='Gangguan'?'selected':''?>>Gangguan</option></select></div><div class="col-md-5 form-group"><label>Catatan</label><input name="catatan" class="form-control" value="<?=htmlspecialchars((string)($edit['catatan']??''))?>"></div><div class="col-md-2 form-group"><label>Publish</label><div class="pt-2"><label><input type="checkbox" name="public_visible" value="1" <?=!empty($edit['public_visible'])?'checked':''?>> Tampilkan</label></div></div><div class="col-md-2 form-group pt-4"><button class="btn btn-primary btn-block">Simpan</button><?php if($edit):?><a href="dhru-products.php" class="btn btn-light btn-block">Batal</a><?php endif;?></div></div></form></div></div>
 <form method="post"><input type="hidden" name="action" value="bulk"><div class="card"><div class="card-body table-responsive"><div class="d-flex justify-content-between mb-3"><h4 class="header-title mb-0">Catalog</h4><div><button name="mode" value="show" class="btn btn-sm btn-success">Publish dipilih</button> <button name="mode" value="hide" class="btn btn-sm btn-warning">Hide dipilih</button></div></div><table class="table table-hover"><thead><tr><th><input type="checkbox" onclick="document.querySelectorAll('.pick').forEach(x=>x.checked=this.checked)"></th><th>Produk</th><th>Group</th><th>Modal</th><th>Jual</th><th>Profit</th><th>Status</th><th>Publish</th><th></th></tr></thead><tbody><?php while($r=$q->fetch_assoc()):?><tr><td><input class="pick" type="checkbox" name="ids[]" value="<?=$r['id']?>"></td><td><b><?=htmlspecialchars($r['layanan'])?></b><br><small><?=htmlspecialchars($r['service_id'])?></small></td><td><?=htmlspecialchars((string)($r['dhru_group']?:$r['operator']))?></td><td>Rp <?=number_format((float)$r['harga_api'],0,',','.')?></td><td>Rp <?=number_format((float)$r['harga'],0,',','.')?></td><td>Rp <?=number_format((float)$r['profit'],0,',','.')?></td><td><?=htmlspecialchars($r['status'])?></td><td><form method="post"><input type="hidden" name="action" value="visibility"><input type="hidden" name="id" value="<?=$r['id']?>"><input type="checkbox" name="public_visible" value="1" onchange="this.form.submit()" <?=$r['public_visible']?'checked':''?>></form></td><td><a class="btn btn-sm btn-info" href="?edit=<?=$r['id']?>">Edit</a></td></tr><?php endwhile;?></tbody></table></div></div></form>
</div>
<?php require_once __DIR__ . '/../lib/footer_admin.php'; ?>
