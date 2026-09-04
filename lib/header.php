<?php
require 'session_login.php';
require 'database.php';
require 'csrf_token.php';

$orderMenu = [];
if (isset($_SESSION['user'])) {
    $qMenu = $conn->query("SELECT m.service_id,m.label,m.icon,m.group_name,m.sort_order FROM order_menu m JOIN layanan_digital l ON l.provider_id=m.service_id WHERE m.is_visible=1 AND l.status='Normal' AND COALESCE(l.public_visible,1)=1 ORDER BY m.group_name ASC,m.sort_order ASC,m.id ASC");
    if ($qMenu) while ($menuRow = $qMenu->fetch_assoc()) $orderMenu[] = $menuRow;
}
$menuGroups = [];
foreach ($orderMenu as $item) $menuGroups[$item['group_name']][] = $item;
?>
<!DOCTYPE html>
<html lang="id-ID">
<head>
<?php include_once 'SEOSecretIDN-meta-all.php'; ?>
<link href="/assets/css/scroll.css?v<?php echo $versi; ?>" rel="stylesheet">
<link href="/assets/default/bootstrap-kmp.css?v<?php echo $versi; ?>" rel="stylesheet">
<link href="/assets/default/app-kmp.css?v<?php echo $versi; ?>" rel="stylesheet">
<link href="/assets/css/remixiconcolab.css?v<?php echo $versi; ?>" rel="stylesheet">
<link href="/assets/css/remixicondefault.css?v<?php echo $versi; ?>" rel="stylesheet">
<link href="/assets/default/dataTables.bootstrap4.css?v<?php echo $versi; ?>" rel="stylesheet">
<script src="/assets/js/jquery.min.js"></script>
<style>
:root{--nav1:#111827;--nav2:#b91c1c;--accent:#dc2626;--soft:#f8fafc;--ink:#1f2937}.v3-top{background:linear-gradient(135deg,var(--nav1),var(--nav2));box-shadow:0 8px 28px rgba(15,23,42,.16)}.v3-top .logo-box{background:transparent}.v3-brand{display:flex!important;align-items:center;gap:10px}.v3-brand img{max-width:150px;height:auto}.v3-sidebar{background:#fff;border-right:1px solid #e8ebf0}.v3-sidebar .menu-title{font-size:11px;letter-spacing:.1em;font-weight:800;color:#94a3b8;padding:18px 20px 8px}.v3-sidebar a{border-radius:12px;margin:3px 10px;padding:11px 14px!important;color:#334155}.v3-sidebar a:hover{background:#f8fafc;color:#b91c1c}.v3-sidebar li.mm-active>a,.v3-sidebar li.active>a{background:#fef2f2;color:#b91c1c;font-weight:700}.v3-sidebar .nav-second-level a{font-size:13px;margin-left:28px}.v3-menu-section{font-size:12px;font-weight:800;color:#64748b;margin:10px 18px 6px;text-transform:uppercase}.v3-order-item{display:flex;align-items:center;gap:9px}.v3-order-icon{width:30px;text-align:center}.v3-content{background:#f6f7f9}.v3-alert{border:0;border-radius:14px;box-shadow:0 8px 22px rgba(15,23,42,.06)}@media(max-width:767px){.v3-brand img{max-width:125px}.v3-sidebar a{margin-left:7px;margin-right:7px}}
</style>
</head>
<body>
<div id="wrapper">
<header id="topnav" class="v3-top">
 <div class="navbar-custom">
  <div class="container-fluid d-flex align-items-center justify-content-between">
   <div class="logo-box p-0"><a href="/" class="logo v3-brand"><img src="/assets/images/kincaimedia/webkmpanelwhite.png" alt="<?php echo htmlspecialchars($data['short_title']); ?>"></a></div>
   <div class="d-flex align-items-center">
    <?php if(isset($_SESSION['user'])): ?><div class="mr-3 text-white d-none d-md-block"><small>Saldo</small><br><b>Rp <?php echo number_format((int)$data_user['saldo'],0,',','.'); ?></b></div><div class="dropdown"><a href="#" class="text-white dropdown-toggle" data-toggle="dropdown"><img src="/assets/images/users/user.png" width="34" height="34" class="rounded-circle" alt="Profil"> <span class="d-none d-sm-inline ml-1"><?php echo htmlspecialchars($data_user['nama']); ?></span></a><div class="dropdown-menu dropdown-menu-right"><div class="dropdown-header"><b><?php echo htmlspecialchars($sess_username); ?></b><br>Rp <?php echo number_format((int)$data_user['saldo'],0,',','.'); ?></div><a class="dropdown-item" href="/user/setting"><i class="fa fa-user-circle"></i> Profil</a><a class="dropdown-item" href="/user/mutasi"><i class="fa fa-wallet"></i> Mutasi Saldo</a><a class="dropdown-item" href="/user/log"><i class="fa fa-history"></i> Aktivitas</a><div class="dropdown-divider"></div><a class="dropdown-item" href="/logout"><i class="ri-shut-down-line"></i> Keluar</a></div></div><?php endif; ?>
    <button type="button" class="button-menu-mobile waves-effect waves-light ml-3"><i class="fe-menu"></i></button>
   </div>
  </div>
 </div>
</header>
<div class="left-side-menu v3-sidebar"><div class="slimscroll-menu"><div id="sidebar-menu"><ul class="metismenu" id="side-menu">
<?php if(isset($_SESSION['user'])): ?>
<li class="menu-title">Workspace</li>
<li><a href="/"><i class="mdi mdi-view-dashboard-outline"></i><span>Beranda</span></a></li>
<?php if(($data_user['level'] ?? '') !== 'Member'): ?><li><a href="/staff/tambah-pengguna"><i class="ri-team-fill"></i><span>Manajemen Member</span></a></li><li><a href="/staff/transfer-saldo"><i class="ri-exchange-dollar-line"></i><span>Transfer Saldo</span></a></li><?php endif; ?>
<li class="menu-title">Order</li>
<?php if($orderMenu): foreach($menuGroups as $group=>$items): ?><li class="v3-menu-section"><?php echo htmlspecialchars($group); ?></li><?php foreach($items as $item): ?><li><a class="v3-order-item" href="/pemesanan/order?service=<?php echo rawurlencode($item['service_id']); ?>"><span class="v3-order-icon"><i class="<?php echo htmlspecialchars($item['icon'] ?: 'mdi mdi-cart-outline'); ?>"></i></span><span><?php echo htmlspecialchars($item['label']); ?></span></a></li><?php endforeach; endforeach; else: ?><li><a href="/halaman/produk-dan-layanan"><i class="mdi mdi-cart-outline"></i><span>Produk & Layanan</span></a></li><?php endif; ?>
<li><a href="/riwayat/pemesanan-digital"><i class="mdi mdi-history"></i><span>Riwayat Order</span></a></li><li><a href="/user/mutasi"><i class="mdi mdi-file-document-outline"></i><span>Riwayat Saldo</span></a></li>
<li class="menu-title">Bantuan</li><li><a href="/tiket"><i class="ri-customer-service-2-fill"></i><span>Tiket</span></a></li><li><a href="/halaman/api-dokumentasi-digital"><i class="mdi mdi-api"></i><span>Dokumentasi API</span></a></li>
<?php if(($data_user['level'] ?? '') === 'Developers'): ?><li class="menu-title">Developer</li><li><a href="/admin-dashboard"><i class="ri-terminal-box-fill"></i><span>Admin Dashboard</span></a></li><?php endif; ?>
<li><a href="/logout"><i class="ri-shut-down-line"></i><span>Keluar</span></a></li>
<?php else: ?>
<li class="menu-title">Menu Utama</li><li><a href="/"><i class="fa fa-home"></i><span>Beranda</span></a></li><li><a href="/auth/login"><i class="fa fa-user"></i><span>Masuk</span></a></li><li><a href="/auth/register"><i class="fa fa-user-plus"></i><span>Daftar</span></a></li><li><a href="/halaman/produk-dan-layanan"><i class="fa fa-tags"></i><span>Layanan</span></a></li><li class="menu-title">Bantuan</li><li><a href="/halaman/hubungi-kami"><i class="fas fa-comments"></i><span>Kontak</span></a></li><li><a href="/halaman/memulai-transaksi"><i class="fa fa-question-circle"></i><span>Panduan</span></a></li>
<?php endif; ?>
</ul></div><div class="clearfix"></div></div></div>
<div class="content-page v3-content"><div class="content"><div class="container-fluid"><br>
<?php if(isset($_SESSION['hasil'])): ?><div class="alert alert-<?php echo htmlspecialchars($_SESSION['hasil']['alert']); ?> alert-dismissible v3-alert"><button type="button" class="close" data-dismiss="alert">&times;</button><b><?php echo htmlspecialchars($_SESSION['hasil']['judul']); ?></b><br><?php echo $_SESSION['hasil']['pesan']; ?></div><?php unset($_SESSION['hasil']); endif; ?>
