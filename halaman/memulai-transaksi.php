<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Memulai Transaksi di <?php echo $data['short_title']; ?></title>
<meta name="description" content="Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen semua produk dan layanan dengan fitur terlengkap, berikut adalah panduan transaksi di <?php echo $data['short_title']; ?>."/>

<!--OG2-->
<meta content="Memulai Transaksi di <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen semua produk dan layanan dengan fitur terlengkap, berikut adalah panduan transaksi di <?php echo $data['short_title']; ?>." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Memulai Transaksi <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Memulai Transaksi di <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen semua produk dan layanan dengan fitur terlengkap, berikut adalah panduan transaksi di <?php echo $data['short_title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row">
    <div class="col-sm-12">
        <div class="card">    
            <div class="card-body table-responsive">                            
                <center>
                    <h1 class="m-t-0 text-uppercase text-center header-title"><b>Memulai Transaksi di <?php echo $data['short_title']; ?></b></h1>
                    <div class="box-content text-center">
                        <a href="/halaman/memulai-transaksi" title="Panduan Transaksi <?php echo $data['short_title'];?>" alt="Panduan Transaksi <?php echo $data['short_title'];?>"> <img src="/assets/images/halaman/memulai-transaksi.png" class="img-responsive" style="max-width: 95%; min-width: 250px;"></a>
                    </div><br/>
                    Tahap-tahap dibawah ini wajib dilakukan untuk memulai transaksi dan menjadi member di <?php echo $data['short_title']; ?>.<br/><br/>
                </center>
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-user-plus fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Daftar Akun</h2>
                            <p>Segera daftarkan diri Anda menjadi Member/Agen/Reseller di <?php echo $data['short_title']; ?>, pendaftaran member tidak akan dikenakan biaya apapun <b>[GRATIS]</b> selamanya.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-money-bill-wave fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Deposit Saldo</h2>
                            <p>Silakan login akun Anda dan menuju ke halaman <a href="<?php echo $config['web']['url'];?>pay/index">Deposit Baru</a>. Kami menyediakan metode deposit melalui Bank, E-Money, Pulsa & Voucher dengan berbagai sistem.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-cart-plus fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Mulai Transaksi</h2>
                            <p>Setelah 2 langkah sebelumnya selesai, Anda dapat melakukan transaksi pembelian semua <a href="<?php echo $config['web']['url'];?>halaman/daftar-harga">Layanan Jasa & Produk Digital/Virtual</a> yang tersedia di <?php echo $data['short_title']; ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">    
            <div class="card-body table-responsive">                            
                <center>
                    <h1 class="m-t-0 text-uppercase text-center header-title"><b>PLATFORM TRANSAKSI</b></h1>
                    Berikut ini adalah platform aplikasi dan jalur transaksi bagi semua member <?php echo $data['short_title']; ?>.<br/><br/>
                </center>
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fas fa-globe-asia fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Aplikasi Berbasis Web</h2>
                            <p>Transaksi dapat dilakukan melalui website official <?php echo $data['short_title']; ?> (<a href="<?php echo $config['web']['url'];?>"><?php echo $config['web']['url'];?></a>) yang bisa di akses melalui perangkat desktop maupun mobile di browser.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fab fa-android fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Aplikasi Android Webview</h2>
                            <p>Transaksi juga dapat dilakukan melalui <a href="platform-aplikasi">Aplikasi Android <?php echo $data['short_title']; ?></a> yang bisa dijalankan di perangkat mobile android.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-fire fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Website API (H2H)</h2>
                            <p>Bagi Anda yang memiliki website SMM, PPOB, atau Web Pulsa, transaksi via API bisa dilakukan dengan mengikuti panduan <a href="/user/setting">Dokumentasi API <?php echo $data['short_title']; ?></a>.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</div>
<!-- end container -->
</div>
<!-- end content -->

<?php
require '../lib/footer.php';
?>        