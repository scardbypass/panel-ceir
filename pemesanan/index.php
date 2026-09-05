<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_user.php';
$search=trim((string)($_GET['q']??''));$category=trim((string)($_GET['category']??''));$categories=[];$products=[];
$q=$conn->query("SELECT DISTINCT operator FROM layanan_digital WHERE status='Normal' AND operator IS NOT NULL AND operator<>'' ORDER BY operator");if($q){while($r=$q->fetch_assoc())$categories[]=(string)$r['operator'];$q->free();}
$where=["status='Normal'"];
if($category!=='')$where[]="operator='".$conn->real_escape_string($category)."'";
if($search!==''){ $like='%'.$conn->real_escape_string($search).'%';$where[]="(layanan LIKE '$like' OR provider_id LIKE '$like' OR operator LIKE '$like' OR menu_label LIKE '$like')"; }
$sql="SELECT provider_id,layanan,operator,harga,provider,catatan,image_url,menu_label,menu_icon FROM layanan_digital WHERE ".implode(' AND ',$where)." ORDER BY operator,sort_order,layanan";$q=$conn->query($sql);if($q){while($r=$q->fetch_assoc())$products[]=$r;$q->free();}
require_once __DIR__ . '/../lib/header.php';
?>
<title>Layanan — Order Center</title><meta name="description" content="Pilih layanan digital dan buat order.">
<div class="p4-page">
<section class="p4-hero"><div class="p4-eyebrow">Order Center</div><h1 class="p4-title">Layanan Digital</h1><p class="p4-subtitle">Pilih layanan, isi data yang diminta, lalu sistem memproses order ke provider secara otomatis.</p></section>
<div class="p4-toolbar"><form class="p4-search" method="get"><input class="form-control p4-input" name="q" value="<?=htmlspecialchars($search,ENT_QUOTES,'UTF-8')?>" placeholder="Cari layanan atau Service ID..."><select class="form-control p4-select" name="category"><option value="">Semua operator</option><?php foreach($categories as $c):?><option value="<?=htmlspecialchars($c,ENT_QUOTES,'UTF-8')?>" <?=$category===$c?'selected':''?>><?=htmlspecialchars($c,ENT_QUOTES,'UTF-8')?></option><?php endforeach;?></select><button class="p4-btn p4-btn-primary" type="submit"><i class="mdi mdi-magnify"></i>Cari</button></form></div>
<?php if(!$products):?><div class="p4-card p4-empty"><i class="mdi mdi-package-variant-closed"></i><h4 class="mt-3">Layanan tidak ditemukan</h4><p class="p4-muted mb-0">Coba kata kunci atau kategori lain.</p></div><?php else:?><div class="p4-grid"><?php foreach($products as $p):?><article class="p4-card p4-service"><div class="p4-service-top"><div class="p4-icon"><i class="<?=htmlspecialchars($p['menu_icon']?:'mdi mdi-cellphone-link',ENT_QUOTES,'UTF-8')?>"></i></div><div><h3><?=htmlspecialchars($p['menu_label']?:$p['layanan'],ENT_QUOTES,'UTF-8')?></h3><div class="p4-meta"><?=htmlspecialchars($p['operator']?:'Digital Service',ENT_QUOTES,'UTF-8')?> · <?=htmlspecialchars(strtoupper($p['provider']?:'MANUAL'),ENT_QUOTES,'UTF-8')?></div></div></div><div class="p4-desc"><?=htmlspecialchars($p['catatan']?:'Layanan siap diproses. Form order menyesuaikan konfigurasi produk.',ENT_QUOTES,'UTF-8')?></div><div class="p4-service-bottom"><div class="p4-price"><small>Harga</small><strong>Rp <?=number_format((int)$p['harga'],0,',','.')?></strong></div><a class="p4-btn p4-btn-dark" href="/pemesanan/order?service=<?=rawurlencode((string)$p['provider_id'])?>">Order <i class="mdi mdi-arrow-right"></i></a></div></article><?php endforeach;?></div><?php endif;?>
</div>
<nav class="p4-mobile-bottom"><a href="/"><i class="mdi mdi-home-outline"></i>Home</a><a class="active" href="/pemesanan"><i class="mdi mdi-storefront-outline"></i>Layanan</a><a href="/riwayat/pemesanan-digital"><i class="mdi mdi-receipt-text-outline"></i>Order</a><a href="/user/mutasi"><i class="mdi mdi-wallet-outline"></i>Saldo</a></nav>
<?php require_once __DIR__ . '/../lib/footer.php'; ?>