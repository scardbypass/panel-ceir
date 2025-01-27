<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Cara Deposit di <?php echo $data['short_title']; ?></title>
<meta name="description" content="Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen semua produk dan layanan dengan fitur terlengkap, berikut adalah panduan transaksi deposit di <?php echo $data['short_title']; ?>."/>

<!--OG2-->
<meta content="Cara Deposit <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen semua produk dan layanan dengan fitur terlengkap, berikut adalah panduan transaksi deposit di <?php echo $data['short_title']; ?>." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Cara Deposit <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/cara-deposit.png" property="og:image"/>
<meta content="Cara Deposit <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen semua produk dan layanan dengan fitur terlengkap, berikut adalah panduan transaksi deposit di <?php echo $data['short_title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/cara-deposit.png" property="twitter:image"/>

<div class="row">
    <div class="col-sm-12">
        <div class="card">    
            <div class="card-body table-responsive">                            
                <center>
                    <h1 class="m-t-0 text-uppercase text-center header-title"><b>Cara Deposit DI <?php echo $data['short_title']; ?></b></h1>
                    <div class="box-content text-center">
                        <a href="/halaman/cara-deposit" title="<?php echo $data['short_title'];?>" alt="<?php echo $data['title'];?>"> <img src="/assets/images/halaman/cara-deposit.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
                    </div><br/>
                    Setelah mendaftar akun di <?php echo $data['short_title']; ?>, selanjutnya anda bisa melakukan deposit saldo agar dapat melakukan pembelian produk & layanan yang tersedia.<br/><br/>
                </center>
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-edit fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Memilih Metode Deposit</h2>
                            <p>Lakukan permintaan deposit pada halaman member dengan memilih menu <a href="<?php echo $config['web']['url'];?>pay/index">Deposit Baru</a>. Lalu memilih metode dan nominal deposit. Sistem akan menampilkan detail deposit berupa nominal transfer & tujuan transfer sesuai metode pembayaran yang Anda pilih.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-paper-plane fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Transfer Pembayaran</h2>
                            <p>Anda akan diminta untuk melakukan transfer sejumlah nominal yang tertera pada detail deposit, nominal transfer ditambah dengan <b>3 Angka Unik</b> di belakang agar konfirmasi pembayaran otomatis. Disarankan transfer pembayaran sesuai nominal yang tertera.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <span aria-hidden="true" class="fa fa-check fa-3x text-primary"></span>
                            </div>                            
                            <h2 class="header-title text-uppercase"> Konfirmasi Pembayaran</h2>
                            <p>Konfirmasi pembayaran cukup dengan klik tombol konfirmasi pada halaman invoice setelah transfer. Jika jumlah transfer sesuai dan valid, maka saldo akan bertambah otomatis. Jika ada kesalahan nominal dan gagal, silakan buat tiket bantuan di halaman <a href="<?php echo $config['web']['url'];?>halaman/hubungi-kami">Hubungi CS</a>.</p>
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
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <center><h2 class="header-title text-uppercase"> <b>TUTORIAL DEPOSIT VIA GOPAY</b></h2></center>
                            </div>
                            <div style="text-align:center;">
                                <iframe width="95%" height="315" src="https://www.youtube.com/embed/eFBcVEwiID8" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
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
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="box-content text-center">
                            <div class="block-icon">
                                <center><h3 class="header-title text-uppercase"> <b>DUKUNGAN PEMBAYARAN</b></h3></center>
                            </div>
                            <div style="text-align:center;padding-top:20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-bni.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-bri.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-bca.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-gopay.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-ovo.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-dana.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-telkomsel.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-xl.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                                <img src="/assets/images/pembayaran/kitadigital-deposit-axis.png" alt="Cara Deposit di <?php echo $data['short_title']; ?>" title="Cara Deposit di <?php echo $data['short_title']; ?>" style="height:100%;max-height:80px;margin:10px 20px">
                            </div>
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

