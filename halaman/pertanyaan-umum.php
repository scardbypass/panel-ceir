<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Pertanyaan Umum Mengenai <?php echo $data['short_title']; ?></title>
<meta name="description" content="Bagaimana cara mendaftar akun <?php echo $data['short_title']; ?>? Bagaimana cara melakukan deposit dan order di <?php echo $data['short_title']; ?>, dan Bagaimana Jika Orderan Error?"/>

<!--OG2-->
<meta content="Pertanyaan Umum Mengenai Layanan <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Bagaimana cara mendaftar akun <?php echo $data['short_title']; ?>? Bagaimana cara melakukan deposit dan order di <?php echo $data['short_title']; ?>, dan Bagaimana Jika Orderan Error?" property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Pertanyaan Umum Mengenai Layanan <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Pertanyaan Umum Mengenai Layanan <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Bagaimana cara mendaftar akun <?php echo $data['short_title']; ?>? Bagaimana cara melakukan deposit dan order di <?php echo $data['short_title']; ?>, dan Bagaimana Jika Orderan Error?" property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row">
    <div class="col-md-12">
        <div class="card-box">

            <!--SEOSecretIDN FAQPage-->
            <span id='faq' itemscope='' itemtype='https://schema.org/FAQPage'> <!--SEOSecretIDN-->
                <center>                                
                    <span itemprop='name'><h1 class="m-t-0 text-uppercase header-title"><b>Pertanyaan Umum Mengenai <?php echo $data['short_title']; ?></b></h1></span> <!--SEOSecretIDN-->
                </center>
                <div class="box-content text-center">
                    <a href="/halaman/pertanyaan-umum" title="Pertanyaan Umum <?php echo $data['short_title'];?>" alt="Pertanyaan Umum <?php echo $data['short_title'];?>"> <img src="/assets/images/halaman/pertanyaan-umum.png" class="img-responsive" style="max-width: 95%; min-width: 250px;"></a>
                </div><br/>

                <ul class="faqlist nav nav-pills navtab-bg"> <!--SEOSecretIDN-->
                    <li class="nav-item">
                        <div class="row pt-2">
                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h2 class="text-uppercase text-center header-title"><span id='faqapaitu' itemprop='name'><b>Apa Itu <?php echo $data['short_title']; ?>?</b></span></h2>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>
                                                    <p class="mb-4"><?php echo $data['short_title']; ?> adalah penyedia layanan optimasi & otomatisasi digital berteknologi AI, berkualitas, cepat & aman. Kami berkomitmen mempopulerkan bisnis Anda di internet. Layanan unggulan kami dapat melakukan Optimasi Sosial Media & E-commerce, penyedia Produk Virtual Terkoneksi PPOB.</p>
                                                </span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h2 class="text-uppercase text-center header-title"><span id='faqapa' itemprop='name'><b>Apa Kelebihan <?php echo $data['short_title']; ?>?</b></span></h2>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                    
                                                    <p class="mb-4">Dibanding jasa serupa lainnya, <?php echo $data['short_title']; ?> memberikan harga termurah, berteknologi bot AI tercepat, serta keamanan transaksi perbankan & PPOB yang terjamin. <?php echo $data['short_title']; ?> juga menyediakan edukasi bisnis dan panduan penggunaan sistem untuk Member, Agen, dan Reseller di Website Portal Bisnis.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h2 class="text-uppercase text-center header-title"><span id='faqkelebihan' itemprop='name'><b>Apakah <?php echo $data['short_title']; ?> Gratis?</b></span></h2>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                
                                                    <p class="mb-4">Akses layanan gratis untuk semua pengguna dengan sistem Penambahan Saldo & giveaway. Lama waktu layanan gratis mengikuti aturan yang ditetapkan, kami memberikan layanan gratis apabila ada event tertentu, fitur baru, atau perayaan momen spesial.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h2 class="text-uppercase text-center header-title"><span id='faqbiaya' itemprop='name'><b>Bagaimana Cara Deposit di <?php echo $data['short_title']; ?>?</b></span></h2>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                    
                                                    <p class="mb-4">Klik menu Deposit, pilih metode, pilih sistem konfirmasi (Otomatis/Manual), isi nominal, klik submit. Lakukan pembayaran. Setelah transfer selesai klik konfirmasi di menu Tagihan. Metode "Otomatis" akan dikonfirmasi detik itu juga jika pembayaran valid! Baca selengkapnya di halaman <a href="/halaman/cara-deposit" alt="Cara deposit di <?php echo $data['short_title']; ?>" title="Cara deposit di <?php echo $data['short_title']; ?>" target="_blank">Cara Transaksi di <?php echo $data['short_title']; ?></a>. Metode pembayaran yang tersedia diantaranya E-Money, Pulsa Transfer, Bank, dan Voucher <?php echo $data['short_title']; ?>.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h2 class="text-uppercase text-center header-title"><span id='faqdeposit' itemprop='name'><b>Bagaimana Cara Order di <?php echo $data['short_title']; ?>?</b></span></h2>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                    
                                                    <p class="mb-4">Untuk melakukan order Anda harus memiliki saldo yang cukup. Klik Dasboard atau menu Order Baru, pilih Kategori, pilih Produk/Layanan, masukkan jumlah orderan, lalu klik Order. Setelah itu akan muncul status order, Anda juga bisa melihat informasi pemesanan pada menu Riwayat Order. Baca selengkapnya di halaman <a href="/halaman/memulai-transaksi" alt="Cara order di <?php echo $data['short_title']; ?>" title="Cara order di  <?php echo $data['short_title']; ?>" target="_blank">Cara Deposit di <?php echo $data['short_title']; ?></a>.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h3 class="text-uppercase text-center header-title"><span id='faqserver' itemprop='name'><b>Bagaimana Kinerja Server <?php echo $data['short_title']; ?>?</b></span></h3>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                    
                                                    <p class="mb-4">Order sukses tercepat mulai 1 sampai 5 menit (jika sedang tidak melakukan sinkronisasi data), kami akan terus meningkatkan kinerja server. Jika orderan pending, mohon menunggu paling lama 1x24 jam.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h3 class="text-uppercase text-center header-title"><span id='faqstatus' itemprop='name'><b>Bagaimana Jika Status Partial/Error?</b></span></h3>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                    
                                                    <p class="mb-4">Dari semua layanan, pada kondisi tertentu respon server kami tidak dapat memproses orderan hingga sukses. Ini karena relasi server tersebut maintenance. Jika Status Order terjadi Partial/Error, sistem akan otomatis mengembalikan dana/refund detik itu juga.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->

                            <div class="col-lg-6">
                                <div class="p-lg-2">
                                    <!-- Question/Answer -->
                                    <div>
                                        <span itemprop='mainEntity' itemscope='' itemtype='https://schema.org/Question'>
                                            <h3 class="text-uppercase text-center header-title"><span id='faqgangguan' itemprop='name'><b>Bagaimana Jika Order Tidak Ada Status?</b></span></h3>
                                            <span itemprop='acceptedAnswer' itemscope='' itemtype='https://schema.org/Answer'>
                                                <span itemprop='text'>                                    
                                                    <p class="mb-4">Jika lebih dari 1x24 jam orderan stuck, kami bisa membantu memproses secara manual. Ini biasanya karena server relasi kami sedang melakukan sinkronisasi database. Jika lebih dari 2x24 jam orderan tetap stuck, segera mengirim pesan pada halaman Tiket.</p>
                                                </span>
                                            </span>                                    
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--/col-md-6-->
                        </div> <!-- /row pt-2 -->
                    </li>
                </ul>
            </span>
            <!--SEOSecretIDN FAQPage-->

        </div> <!-- card-box -->
    </div> <!-- /col-md-12 -->
</div> <!-- /row -->

</div>
<!-- end container -->
</div>
<!-- end content -->

<?php
require '../lib/footer.php';
?>               