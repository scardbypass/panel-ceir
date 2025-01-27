<?php

require 'session_login.php';
require 'database.php';
require 'csrf_token.php';
//get oke
$oke = mysqli_query($conn, "SELECT * FROM oke ORDER BY id DESC");
$fk = mysqli_fetch_assoc($oke);
$o_pin = $fk['pin'];
$o_ids = $fk['member_id'];
$o_pw = $fk['password'];
$last_oke_order = mysqli_query($conn, "SELECT * FROM pembelian_pulsa WHERE provider = 'okeconnect' AND user = '".$data_user['username']."' ORDER BY id DESC");
$fl = mysqli_fetch_assoc($last_oke_order);
//get produk
$qs = mysqli_query($conn, "SELECT * FROM layanan_pulsa WHERE layanan = '".$fl['layanan']."'");
$fss= mysqli_fetch_assoc($qs);
$w_produk = $fss['provider_id'];
$w_target = $fl['target'];
$reff_id = $fl['oid'];

if($fl['status'] == 'Pending' OR $fl['status'] == 'Processing' OR $fl['status'] == 'Success'){
    $url = "https://h2h.okeconnect.com/trx?memberID=$o_ids&pin=$o_pin&password=$o_pw&product=$w_produk&dest=$w_target&refID=$reff_id";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);


   curl_close($ch);
  // Cek SN
$query_service = mysqli_query($conn, "SELECT * FROM layanan_pulsa WHERE layanan = 'Pulsa Tri 1k'");
$fq = mysqli_fetch_assoc($query_service);
$nq = mysqli_num_rows($query_service);
$al_service = $fq['id'];
$query_sn = mysqli_query($conn, "SELECT * FROM edit_sn WHERE layanan = '$al_service' OR type = 'all'");

$fs = mysqli_fetch_assoc($query_sn);
$num_sn = mysqli_num_rows($query_sn); 
if($fs['type'] == 'all'){
    $sn = $fs['msg'];
} else if($fs['type'] == 'particular'){
    $sn = $fs['msg'];
} else{
   $sn = $response; 
}
// $sn = $response; // Default response jika tidak ada yang cocok

// AL CAPONE


   //Parsing
   // Cek apakah ada kata "GAGAL" dalam string
if (strpos($response, 'GAGAL') !== false) {
    $update_status = mysqli_query($conn, "UPDATE `pembelian_pulsa` SET `status` = 'Error' WHERE `pembelian_pulsa`.`id` = ".$fl['id']."");
} else if(strpos($response, 'Menunggu Jawaban') !== false){
    $update_status = mysqli_query($conn, "UPDATE `pembelian_pulsa` SET `status` = 'Processing' WHERE `pembelian_pulsa`.`id` = ".$fl['id']."");
} else if(strpos($response, 'Sukses') !== false){
    $update_status = mysqli_query($conn, "UPDATE `pembelian_pulsa` SET `keterangan` = '".$sn."', `status` = 'Success' WHERE `pembelian_pulsa`.`id` = ".$fl['id']."");
} else{
    $update_status = mysqli_query($conn, "UPDATE `pembelian_pulsa` SET `status` = 'Error' WHERE `pembelian_pulsa`.`id` = ".$fl['id']."");
}
}
?>
 
<!DOCTYPE html>
<html lang="id-ID" xml:lang="id-ID">
    <head>

        <?php
        include_once 'SEOSecretIDN-meta-all.php';
        ?>

        <!-- App css -->
        <link href="/assets/css/scroll.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/bootstrap-kmp.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/app-kmp.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/css/remixiconcolab.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/css/remixicondefault.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/dataTables.bootstrap4.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/responsive.bootstrap4.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/buttons.bootstrap4.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/select.bootstrap4.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>
    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <div class="navbar-custom">

                <?php
                if (isset($_SESSION['user'])) {
                    ?>
                    <ul class="list-unstyled topnav-menu float-right mb-0">
                        <!--NAVIGASI AKUN-->
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="/assets/images/users/user.png" alt="Profil Pengguna" class="rounded-circle">
                                <span class="pro-user-name ml-1">
                                    <font color ='white'><?php echo $data_user['nama']; ?><i class="mdi mdi-chevron-down"></i></font>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                                <!-- title-->
                                <div class="dropdown-header noti-title bg-light">
                                    <div class="text-overflow m-0">
                                        <i aria-hidden="true" class="fa fa-user-circle"></i> <?php echo $sess_username; ?>
                                        <br/><br/>
                                        <i aria-hidden="true" class="fa fa-wallet"></i> <b><?php echo number_format($data_user['saldo'],0,',','.'); ?></b> IDR
                                    </div>
                                </div>
                                <!-- item-->
                                <a href="/user/setting" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-cog fa-spin"></i> <span>Profil Saya</span>
                                </a>
                                <!-- item-->
                                <a href="/user/mutasi" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-wallet"></i> <span>Mutasi Saldo</span>
                                </a>
                                <!-- item-->
                                <a href="/user/log" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-history"></i> <span>Log Aktivitas</span>
                                </a>
                                <!-- item-->
                                <a href="/logout" class="dropdown-item notify-item">
                                    <i class="ri-shut-down-line"></i> <span>Keluar</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                <?php } ?>
                <div class="logo-box">
                    <a href="/" class="logo text-center">
                        <span class="logo-lg">
                            <img src="/assets/images/kincaimedia/webkmpanelwhite.png" alt="SMM Panel PPOB Auto Subscribe dan Follower Indonesia" title="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman" width="200">
                        </span>
                        <span class="logo-sm">
                            <img src="/assets/images/kincaimedia/logo.png" alt="SMM Panel PPOB Auto Subscribe dan Follower Indonesia" title="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman" width="60">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button aria-label="Top Navigation" class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                    <!--NAVIGASI HALAMAN-->
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="/assets/images/kincaimedia/kincaimedia256.png" alt=""<?php echo $data['short_title']; ?>" class="rounded-circle">
                       
                            </a>
                        </div>
                    </li>
                </ul>

                <?php
                if (!isset($_SESSION['user'])) {
                    ?>
                    <ul class="list-unstyled topnav-menu float-right mb-0">
                        <!--NAVIGASI PARTNER-->
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                                <!-- item-->
                                <a href="https://scard-project.id" target="_blank" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-star"></i> <span>scard-project.id</span>
                                </a>
                                <!-- item-->
                                <a href="https://safelink.asia" target="_blank" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-star"></i> <span>Safelink.asia</span>
                                </a>
                                <!-- item-->
                                <a href="https://safelinku.top" target="_blank" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-star"></i> <span>Safelinku.top</span>
                                </a>
                                <!-- item-->
                                <a href="https://mycoding.net" target="_blank" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-star"></i> <span>Mycoding.net</span>
                                </a>
                                <!-- item-->
                                <a href="https://mycoding.id" target="_blank" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-star"></i> <span>Mycoding.id</span>
                                </a>
                            </div>
                        </li>
                        <!--NAVIGASI AKUN-->
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="/assets/images/users/user.png" alt="Profil Pengguna" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                                <!-- item-->
                                <a href="/" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-home"></i> <span>Beranda</span>
                                </a>
                                <!-- item-->
                                <a href="/auth/register" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-user-plus"></i> <span>Registrasi</span>
                                </a>
                                <!-- item-->
                                <a href="/auth/login" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-user"></i> <span>Member Area</span>
                                </a>

                                <!-- item-->
                                <a href="/halaman/daftar-harga" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-tags"></i> <span>Daftar Harga</span>
                                </a>
                                <!-- item-->
                                <a href="/halaman/hubungi-kami" class="dropdown-item notify-item">
                                    <i class="fas fa-comments"></i> <span>Hubungi Kami</span>
                                </a>
                                <!-- item-->
                                <a href="/halaman/memulai-transaksi" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-question-circle"></i></i> <span>Mulai Transaksi</span>
                                </a>
                                <!-- item-->
                                <a href="/halaman/cara-deposit" class="dropdown-item notify-item">
                                    <i aria-hidden="true" class="fa fa-question-circle"></i></i> <span>Cara Deposit</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                <?php } ?>
            </div>

            <div class="left-side-menu">
                <div class="slimscroll-menu">
                    <div id="sidebar-menu">
                        <ul class="metismenu" id="side-menu">
                            <?php
                            if (isset($_SESSION['user'])) {
                                ?>    
                                <?php
                                if ($data_user['level'] == "Developers") {
                                    ?>            
                                    <li class="menu-title"> Developer</li>
                                    <li>
                                        <a href="/admin-dashboard">
                                            <i class="ri-terminal-box-fill"></i>
                                            <span> Developer </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript: void(0);" class="waves-effect">
                                            <i aria-hidden="true" class="fa fa-file-invoice"></i>
                                            <span> Deposit </span>
                                            <?php if (mysqli_num_rows($AllDepositUsersBank) !== 0) { ?><span class="badge badge-success"><?php echo mysqli_num_rows($AllDepositUsersBank); ?></span> <?php } ?>
                                            <?php if (mysqli_num_rows($AllDepositUsersEmoney) !== 0) { ?><span class="badge badge-primary"><?php echo mysqli_num_rows($AllDepositUsersEmoney); ?></span> <?php } ?>
                                            <?php if (mysqli_num_rows($AllDepositUsersEpayment) !== 0) { ?><span class="badge badge-primary"><?php echo mysqli_num_rows($AllDepositUsersEpayment); ?></span> <?php } ?>
                                            <?php if (mysqli_num_rows($AllDepositUsersTsel) !== 0) { ?><span class="badge badge-danger"><?php echo mysqli_num_rows($AllDepositUsersTsel); ?></span> <?php } ?>
                                            <?php if (mysqli_num_rows($AllDepositUsersVoucher) !== 0) { ?><span class="badge badge-info"><?php echo mysqli_num_rows($AllDepositUsersVoucher); ?></span> <?php } ?>
                                            <?php if (mysqli_num_rows($AllDepositUsersLain) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllDepositUsersLain); ?></span> <?php } ?>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <ul class="nav-second-level nav" aria-expanded="false">
                                            <li>
                                                <a href="/admin-dashboard/deposit-bank"> Deposit Bank 
                                                    <?php if (mysqli_num_rows($AllDepositUsersBank) !== 0) { ?><span class="badge badge-success badge-pill notif-tiket"><?php echo mysqli_num_rows($AllDepositUsersBank); ?></span><?php } ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/admin-dashboard/deposit-emoney"> Deposit Emoney 
                                                    <?php if (mysqli_num_rows($AllDepositUsersEmoney) !== 0) { ?><span class="badge badge-primary badge-pill notif-tiket"><?php echo mysqli_num_rows($AllDepositUsersEmoney); ?></span><?php } ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/admin-dashboard/deposit-epayment"> Deposit Epayment 
                                                    <?php if (mysqli_num_rows($AllDepositUsersEpayment) !== 0) { ?><span class="badge badge-primary badge-pill notif-tiket"><?php echo mysqli_num_rows($AllDepositUsersEpayment); ?></span><?php } ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/admin-dashboard/deposit-tsel"> Deposit Telkomsel 
                                                    <?php if (mysqli_num_rows($AllDepositUsersTsel) !== 0) { ?><span class="badge badge-danger badge-pill notif-tiket"><?php echo mysqli_num_rows($AllDepositUsersTsel); ?></span><?php } ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/admin-dashboard/deposit-voucher"> Deposit Voucher 
                                                    <?php if (mysqli_num_rows($AllDepositUsersVoucher) !== 0) { ?><span class="badge badge-info badge-pill notif-tiket"><?php echo mysqli_num_rows($AllDepositUsersVoucher); ?></span><?php } ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/admin-dashboard/deposit"> Deposit Lainnya 
                                                    <?php if (mysqli_num_rows($AllDepositUsersLain) !== 0) { ?><span class="badge badge-warning badge-pill notif-tiket"><?php echo mysqli_num_rows($AllDepositUsersLain); ?></span><?php } ?>
                                                </a>
                                            </li>                                        
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="/admin-dashboard/tiket"><i class="ri-customer-service-fill"></i> <span>Tiket </span>
                                                                                <a href="/admin-dashboard/digital"><i class="ri-customer-service-fill"></i> <span>proses imei </span>
                                                                                <a href="/cronsjob/status_digital"><i class="memory-alert-hexagon"></i> <span>updete status </span>
                                            <?php if (mysqli_num_rows($AllTiketUsersPending) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllTiketUsersPending); ?></span> <?php } ?>
                                            <?php if (mysqli_num_rows($AllTiketUsersWaiting) !== 0) { ?><span class="badge badge-info"><?php echo mysqli_num_rows($AllTiketUsersWaiting); ?></span> <?php } ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php
                                if ($data_user['level'] != "Member") {
                                    ?> 
                                    <li class="menu-title"> Agen & Reseller</li>
                                    <li>
                                        <a href="javascript: void(0);" class="waves-effect">
                                            <i class="ri-team-fill"></i>
                                            <span> Manajemen </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <ul class="nav-second-level nav" aria-expanded="false">
                                            <li>
                                                <a href="/staff/tambah-pengguna">Tambah Member</a>
                                            </li>
                                            <li>
                                                <a href="/staff/transfer-saldo">Transfer Saldo</a>
                                            </li>
                                            <li>
                                                <a href="/riwayat/transfer-saldo">Riwayat Transfer</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                      
                                <?php } ?>                            
                                <li class="menu-title">Menu Utama</li>
                                <li>
                                    <a href="/">
                                        <i class="mdi mdi-view-dashboard"></i>
                                        <span> Beranda </span>
                                    </a>
                                </li>
                                <li>                                 <a href="/pemesanan/imei">
                                        <i aria-hidden="true" class="fa fa-shopping-cart"></i>
                                        <span> UNLOCK IMEI 3B </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/pemesanan/unblockip">
                                        <i aria-hidden="true" class="fa fa-shopping-cart"></i>
                                        <span> Order Ceir </span>
                                    </a>
                                </li>
                                <li>
                                                                      <a href="/pemesanan/cekimei">
                                        <i aria-hidden="true" class="fa fa-shopping-cart"></i>
                                        <span> Order cek apple </span>
                                    </a>
                                </li>
                                <li>
                                       <a href="/riwayat/pemesanan-digital">
                                        <i aria-hidden="true" class="fa fa-history"></i>
                                        <span> Riwayat Order</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/user/mutasi">
                                        <i aria-hidden="true" class="fa fa-file-invoice"></i>
                                        <span> Riwayat Saldo</span>
                                    </a>
                                </li>
                                <li>
                                 
                                <li class="menu-title"> Bantuan</li>
                                <li>
                                    <a href="/tiket">
                                        <i class="ri-customer-service-fill"></i>
                                        <span> Tiket </span>
                                        <?php if (mysqli_num_rows($CallDBTiketResponded) !== 0) { ?><span class="badge badge-info badge-pill notif-tiket"><?php echo mysqli_num_rows($CallDBTiketResponded); ?></span><?php } ?>
                                    </a>
                                </li>
                                <li>
                                   
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i aria-hidden="true" class="fa fa-fire"></i>
                                        <span> Dokumentasi API </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level nav" aria-expanded="false">                                    
                                        <li>
                                            <a href="/halaman/api-dokumentasi-profile">Informasi Profil</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/api-dokumentasi-digital">Produk Roamer</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                            } else {
                                ?>
                                <li class="menu-title">Menu Utama</li>
                                <li>
                                    <a href="/" target="_blank">
                                        <i aria-hidden="true" class="fa fa-home"></i>
                                        <span> Beranda </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/auth/login">
                                        <i aria-hidden="true" class="fa fa-user"></i>
                                        <span> Masuk </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/auth/register">
                                        <i aria-hidden="true" class="fa fa-user-plus "></i>
                                        <span> Daftar </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="/halaman/produk-dan-layanan">
                                        <i aria-hidden="true" class="fa fa-tags"></i>
                                        <span> Layanan </span>
                                    </a>
                                </li>
                                <li class="menu-title"> Bantuan</li>
                                <li>
                                    <a href="/halaman/hubungi-kami">
                                        <i class="fas fa-comments"></i>
                                        <span> Kontak </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i aria-hidden="true" class="fa fa-question-circle"></i>
                                        <span> Panduan </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level nav" aria-expanded="false">
                                        <li>
                                            <a href="/halaman/memulai-transaksi">Mulai Transaksi</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/cara-deposit">Cara Deposit</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-title"> Halaman</li>    
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i aria-hidden="true" class="fa fa-info-circle"></i>
                                        <span> Informasi </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level nav" aria-expanded="false">                      
                                        <li>
                                            <a href="/halaman/tentang-kami">Tentang Kami</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/dukungan-teknologi">Dukungan Teknologi</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/dukungan-pembayaran">Dukungan Pembayaran</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/platform-aplikasi">Platform Aplikasi</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/mitra-dan-jaringan">Mitra & Jaringan</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/ketentuan-layanan">Ketentuan Layanan</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/pertanyaan-umum">Pertanyaan Umum</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="waves-effect">
                                        <i aria-hidden="true" class="fa fa-fire"></i>
                                        <span> Dokumentasi API </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <ul class="nav-second-level nav" aria-expanded="false">                      
                                        <li>
                                            <a href="/halaman/api-dokumentasi-profile">Informasi Profil</a>
                                        </li>
                                        <li>
                                            <a href="/halaman/api-dokumentasi-digital">Produk Digital</a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <br />

                        <?php
                        if (isset($_SESSION['hasil'])) {
                            ?>
                            <div class="alert alert-<?php echo $_SESSION['hasil']['alert'] ?> alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
                                <strong>Respon : </strong><?php echo $_SESSION['hasil']['judul'] ?><br /> <strong>Pesan : </strong> <?php echo $_SESSION['hasil']['pesan'] ?>
                            </div>
                            <?php
                            unset($_SESSION['hasil']);
                        }
                        ?>

                        <?php
                        $time = microtime();
                        $time = explode(' ', $time);
                        $time = $time[1] + $time[0];
                        $start = $time;
                        ?>
                    