<?php

require 'session_login_admin.php';
require 'database.php';
?>

<!DOCTYPE html>
<html lang="id-ID" xml:lang="id-ID">
    <head>

        <?php
        include_once 'SEOSecretIDN-meta-all.php';
        include_once 'SEOSecretIDN-meta-homepageonly.php';
        ?>

        <!-- App css -->
        <link href="/assets/css/scroll.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/css/style.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/default/bootstrap-kmp.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/> 
        <link href="/assets/css/remixiconcolab.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/assets/css/icons.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <link href="/plugins/morris/morris.css?v<?php echo $versi; ?>" rel="stylesheet" type="text/css" async/>
        <!-- jQuery  -->
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/js/waves.js"></script>
        <script src="/assets/js/jquery.slimscroll.js"></script>
        <!-- Counter js  -->
        <script src="/plugins/waypoints/jquery.waypoints.min.js"></script>
        <script src="/plugins/counterup/jquery.counterup.min.js"></script>
        <!-- Dashboard init -->
        <script src="/assets/pages/jquery.dashboard.js"></script>  
        <!-- Modal-Effect -->
        <link href="/plugins/custombox/css/custombox.min.css?v<?php echo $versi; ?>" rel="stylesheet" async/>
        <script src="/plugins/custombox/js/custombox.min.js"></script>
        <script src="/plugins/custombox/js/legacy.min.js"></script>
        <!--Morris Chart CSS -->
        <script src="/plugins/morris/morris.min.js"></script>
        <script src="/plugins/raphael/raphael-min.js"></script> 
        <script src="/assets/js/modernizr.min.js"></script>
    </head>

    <body>

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main navbar m-b-0 b-0">
                <div class="container-fluid">

                    <!-- LOGO -->
                    <div class="logo">
                        <a href="/" class="logo">
                            <span class="logo-small"><i class="mdi mdi-cart"></i></span>
                            <span class="logo-large"><i class="mdi mdi-cart"></i> <?php echo $data['short_title']; ?></span>
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <div class="menu-extras">
                        <ul class="nav navbar-right list-inline">
                            <li class="dropdown user-box list-inline-item">
                                <a href="#" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown" aria-expanded="true">
                                    <img src="/assets/images/users/user.png" alt="user-img" class="rounded-circle user-img">
                                    <span class="pro-user-name ml-1">
                                    <font color ='white'><?php echo $data_user['nama']; ?><i class="mdi mdi-chevron-down"></i></font>
                                </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                                    <!-- title-->
                                    <div class="dropdown-header noti-title">
                                        <span class="text-overflow m-0"><?php echo $sess_username; ?><br/>Rp. <b><?php echo number_format($data_user['saldo'],0,',','.'); ?></b></span>
                                    </div>
                                    <li><a href="/user/setting" class="dropdown-item"><i aria-hidden="true" class="fa fa-user-circle"></i> Profil Saya</a></li>
                                    <li><a href="/user/mutasi" class="dropdown-item"><i class="ri-wallet-3-fill"></i> Mutasi Saldo</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a href="/user/log" class="dropdown-item"><i aria-hidden="true" class="fa fa-history"></i> Log Aktivitas</a></li>
                                    <li><a href="/logout" class="dropdown-item"><i class="ri-shut-down-line"></i> Keluar</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="navbar-custom">
                <div class="container-fluid">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">                            
                            <li class="has-submenu">
                                <a href="/admin-dashboard/"><i class="fi-air-play"></i> <span>  Dashboard</span> </a>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-server-security"></i> <span> Provider </span> </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/action-provider">Kelola Layanan</a></li>
                                    <li><a href="/admin-dashboard/provider">Kelola Provider</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-cart-plus"></i> <span> Layanan </span> </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/kategori-layanan">Kategori Layanan</a></li>
                                    <li><a href="/admin-dashboard/layanan-digital">Digital</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-account-multiple"></i> <span> Pengguna </span> </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/pengguna">Kelola Pengguna</a></li>
                                    <li><a href="/admin-dashboard/harga-pendaftaran">Harga Pendaftaran</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-wallet"></i> <span> Deposit </span>
                                <?php if (mysqli_num_rows($AllDepositUsersBank) !== 0) { ?><span class="badge badge-success"><?php echo mysqli_num_rows($AllDepositUsersBank); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllDepositUsersEmoney) !== 0) { ?><span class="badge badge-primary"><?php echo mysqli_num_rows($AllDepositUsersEmoney); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllDepositUsersEpayment) !== 0) { ?><span class="badge badge-primary"><?php echo mysqli_num_rows($AllDepositUsersEpayment); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllDepositUsersTsel) !== 0) { ?><span class="badge badge-danger"><?php echo mysqli_num_rows($AllDepositUsersTsel); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllDepositUsersVoucher) !== 0) { ?><span class="badge badge-danger"><?php echo mysqli_num_rows($AllDepositUsersVoucher); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllDepositUsersLain) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllDepositUsersLain); ?></span> <?php } ?>
                                </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/metode-deposit">Kelola Metode</a></li>
                                    <li><a href="/admin-dashboard/voucher">Kelola Voucher</a></li>
                                    <li><a href="/admin-dashboard/deposit-bank">Deposit Bank</a></li>
                                    <li><a href="/admin-dashboard/deposit-emoney">Deposit Emoney</a></li>
                                    <li><a href="/admin-dashboard/deposit-epayment">Deposit Epayment</a></li>
                                    <li><a href="/admin-dashboard/deposit-tsel">Deposit Tsel</a></li>
                                    <li><a href="/admin-dashboard/deposit-voucher">Deposit Voucher</a></li>
                                    <li><a href="/admin-dashboard/deposit">Deposit Lainnya</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-cart-plus"></i> <span> Pesanan </span> 
                                <?php if (mysqli_num_rows($AllOrderSosmedPending) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllOrderSosmedPending); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllOrderSosmedProcessing) !== 0) { ?><span class="badge badge-info"><?php echo mysqli_num_rows($AllOrderSosmedProcessing); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllOrderSosmedInprogress) !== 0) { ?><span class="badge badge-success"><?php echo mysqli_num_rows($AllOrderSosmedInprogress); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllOrderPulsaPending) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllOrderPulsaPending); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllOrderPulsaProcessing) !== 0) { ?><span class="badge badge-info"><?php echo mysqli_num_rows($AllOrderPulsaProcessing); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllOrderDigitalPending) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllOrderDigitalPending); ?></span> <?php } ?>
                                <?php if (mysqli_num_rows($AllOrderDigitalProcessing) !== 0) { ?><span class="badge badge-info"><?php echo mysqli_num_rows($AllOrderDigitalProcessing); ?></span> <?php } ?>
                                </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/digital">Digital</a></li>
                                </ul>
                            </li>

                            <li class="has-submenu">
                                <a href="/admin-dashboard/tiket"><i class="dripicons-ticket"></i> <span>Tiket</span>
                                    <?php if (mysqli_num_rows($AllTiketUsersPending) !== 0) { ?><span class="badge badge-warning"><?php echo mysqli_num_rows($AllTiketUsersPending); ?></span><?php } ?> 
                                    <?php if (mysqli_num_rows($AllTiketUsersWaiting) !== 0) { ?><span class="badge badge-info"><?php echo mysqli_num_rows($AllTiketUsersWaiting); ?></span><?php } ?>
                                </a>
                            </li>

                            <li class="has-submenu">
                                <a href="#"><i aria-hidden="true" class="fa fa-book"></i> <span> Laporan </span> </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/laporan-digital"> Digital</a></li>
                                </ul>
                            </li>                                                                                 
                            <li class="has-submenu">
                                <a href="#"><i class="mdi mdi-file-multiple"></i> <span> Aktivitas </span> </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/aktifitas-pengguna">Semua Pengguna</a></li>
                                    <li><a href="/admin-dashboard/penggunaan-saldo">Mutasi Saldo</a></li>
                                    <li><a href="/admin-dashboard/transfer-saldo">Transfer Saldo</a></li>
                                </ul>
                            </li>        

                            <li class="has-submenu">
                                <a href="#"><i aria-hidden="true" class="fa fa-list"></i> <span> Lainnya </span> </a>
                                <ul class="submenu">
                                    <li><a href="/admin-dashboard/berita">Kelola Berita</a></li>
                                    <li><a href="/admin-dashboard/halaman-lain">Kelola Halaman</a></li>
                                    <li><a href="/admin-dashboard/pengaturan-website">Pengaturan Web</a></li>
                                </ul>
                            </li>
                            
                            <li class="has-submenu">
                                <a href="/admin-dashboard/pengaturan-profit"><i aria-hidden="true" class="mdi mdi-wallet"></i> <span> Pengaturan Profit </span> </a>
                            </li>

                        </ul>
                        <!-- End navigation menu  -->

                    </div>
                </div>
            </div>
        </header>

        <!-- End Navigation Bar-->
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-20">
                            <a href="/" class="btn btn-primary btn-bordred"><i aria-hidden="true" class="fa fa-reply"></i> Dashboard</a>
                        </div>
                    </div>
                </div>

                <?php
                if (isset($_SESSION['hasil'])) {
                    ?>
                    <div class="alert alert-<?php echo $_SESSION['hasil']['alert'] ?> alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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