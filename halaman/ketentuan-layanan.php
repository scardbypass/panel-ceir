<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Ketentuan Layanan <?php echo $data['short_title']; ?></title>
<meta name="description" content="Dengan mendaftar dan menggunakan layanan <?php echo $data['short_title']; ?>, Anda secara otomatis menyetujui semua Ketentuan Layanan kami."/>

<!--OG2-->
<meta content="Ketentuan Layanan <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Dengan mendaftar dan menggunakan layanan <?php echo $data['short_title']; ?>, Anda secara otomatis menyetujui semua Ketentuan Layanan kami." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Ketentuan Layanan <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Ketentuan Layanan <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Dengan mendaftar dan menggunakan layanan <?php echo $data['short_title']; ?>, Anda secara otomatis menyetujui semua Ketentuan Layanan kami." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <center>                                
                <h1 class="m-t-0 text-uppercase text-center header-title"><b>Ketentuan Layanan <?php echo $data['short_title']; ?></b></h1>
                <div class="box-content text-center">
                    <a href="/halaman/ketentuan-layanan" title="Ketentuan Layanan <?php echo $data['short_title'];?>" alt="Ketentuan Layanan <?php echo $data['short_title'];?>"> <img src="../assets/images/halaman/ketentuan-layanan.png" class="img-responsive" style="max-width: 95%; min-width: 250px;"></a>
                </div><br/>
            </center>
            <br/>
            <ul class="nav nav-pills navtab-bg">
                <li class="nav-item">
                    <div class="row pt-2">
                        <div class="col-lg-6">
                            <div class="p-lg-2">

                                <!-- Question/Answer -->
                                <div>
                                    <h2 class="text-uppercase text-center header-title"><b>Ketentuan Umum</b></h2>
                                    <p class="mb-4"><b>1.</b> Dengan mendaftar dan menggunakan layanan <?php echo $data['short_title']; ?>, Anda secara otomatis menyetujui semua Ketentuan Layanan kami. Kami berhak mengubah Ketentuan Layanan ini tanpa pemberitahuan terlebih dahulu. Anda diharapkan membaca semua Ketentuan Layanan kami sebelum membuat pesanan.</p>
                                </div>

                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>2.</b> <?php echo $data['short_title']; ?> tidak akan bertanggung jawab jika Anda mengalami kerugian dalam bisnis Anda. 
                                    </p>
                                </div>

                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>3.</b> <?php echo $data['short_title']; ?> tidak bertanggung jawab jika Anda mengalami suspensi akun atau penghapusan kiriman yang dilakukan oleh Instagram, Twitter, Facebook, Youtube, dan lain-lain. </p>
                                </div>

                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>4.</b> Layanan sosial media <?php echo $data['short_title']; ?> hanya membantu untuk membuat akun sosial anda lebih terlihat menarik, dan tidak ada jaminan bahwa pengikut yang bertambah akan dapat berinteraksi dengan anda. </p>
                                </div>

                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>5.</b> Layanan <?php echo $data['short_title']; ?> tidak pernah berafiliasi atau bekerjasama dengan pihak manapun untuk menjual / membeli panel, pendaftaran bersifat gratis dan apabila ada pihak yang mengatasnamakan <?php echo $data['short_title']; ?> untuk penjualan berjenis kerjasama maka itu bukanlah kami dan kami tidak bertanggung jawab jika hal itu terjadi. </p>
                                </div>
                            </div>
                        </div>
                        <!--/col-md-6-->

                        <div class="col-lg-6">
                            <div class="p-lg-2">
                                <!-- Question/Answer -->
                                <div>
                                    <h2 class="text-uppercase text-center header-title"><b>Kebijakan Privasi</b></h2>
                                    <p class="mb-4"><b>1.</b> Kami menjaga privasi Anda dengan serius dan akan mengambil semua tindakan untuk melindungi informasi pribadi Anda.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>2.</b> Setiap informasi pribadi yang diterima hanya akan digunakan untuk mengisi pesanan Anda. Kami tidak akan menjual atau mendistribusikan ulang informasi Anda kepada siapa pun. Semua informasi dienkripsi dan disimpan di server yang aman.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>3.</b> Cookie adalah satu bagian data yang disimpan di komputer pengguna yang berkaitan dengan informasi tentang pengguna tersebut. Kami menggunakan session ID cookie ketika pengguna menutup browser, cookie tersebut dihapus. Cookie juga digunakan untuk manajemen session di website kami. Pengguna harus mengaktifkan cookie untuk menggunakan website kami.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>4.</b> <?php echo $data['short_title']; ?> menyimpan IP (Internet Protocol) address, atau lokasi komputer Anda di Internet, untuk keperluan administrasi sistem dan troubleshooting. Kami menggunakan IP address secara keseluruhan (agregat) untuk mengetahui lokasi-lokasi yang mengakses website kami.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>5.</b> Pada data log files, data ini hanya akan digunakan dalam bentuk agregat (keseluruhan) untuk menganalisa aktivitas login dan logout penggunaan situs.</p>
                                </div>
                            </div>
                        </div>
                        <!--/col-md-6-->

                        <div class="col-lg-6">
                            <div class="p-lg-2">
                                <!-- Question/Answer -->
                                <div>
                                    <h2 class="text-uppercase text-center header-title"><b>Ketentuan Layanan</b></h2>
                                    <p class="mb-4"><b>1.</b> Penggunaan layanan yang disediakan oleh <?php echo $data['short_title']; ?> membuat kesepakatan untuk persyaratan ini. Dengan mendaftar atau menggunakan layanan kami, Anda setuju bahwa Anda telah membaca dan memahami sepenuhnya Ketentuan Layanan.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>2.</b> <?php echo $data['short_title']; ?> tidak menjamin pengikut yang Anda beli akan berinteraksi dengan Anda, kami hanya menjamin bahwa Anda mendapatkan pengikut/layanan yang Anda beli, dan tidak menjamin itu akan bertahan lama (permanen), karna bisa saja akan turun (drop) dalam waktu tertentu. </p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>3.</b> <?php echo $data['short_title']; ?> tidak menerima permintaan pembatalan/pengembalian dana setelah pesanan masuk ke sistem kami. Kami memberikan pengembalian dana yang sesuai jika pesanan tidak dapat diselesaikan.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>4.</b> Harap membaca dan memperhatikan setiap diskripsi yang ada pada setiap layanan di <?php echo $data['short_title']; ?>, Anda terikat dengan peraturan pada deskripsi tersebut, bukan pada nama layanan nya, deskripsi layanan muncul pada halaman pemesanan setelah memilih kategori dan nama layanan. Harap membaca, memahami, dan mengikuti FAQs layanan <?php echo $data['short_title']; ?> di halaman <a href="/halaman/pertanyaan-umum" alt="Faqs <?php echo $data['short_title']; ?>" title="Faqs <?php echo $data['short_title']; ?>" target="_blank">Pertanyaan Umum Mengenai Layanan <?php echo $data['short_title']; ?></a>.</p>
                                </div>
                            </div>
                        </div>
                        <!--/col-md-6-->

                        <div class="col-lg-6">
                            <div class="p-lg-2">
                                <!-- Question/Answer -->
                                <div>
                                    <h2 class="text-uppercase text-center header-title"><b>Status Reffil & Refund</b></h2>
                                    <p class="mb-4"><b>1.</b> Semua proses refill atau isi ulang dilakukan secara otomatis untuk layanan sosial media, ini berlaku jika data pesanan anda adalah layanan refill. Kami tidak memiliki estimasi waktu kapan pesanan akan selesai di refill, semua pesanan yang ada dalam daftar laporan akan kami konfirmasi otomatis.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>2.</b> Kami tidak akan memberikan pengembalian dana pada pesanan Anda, kecuali jika kami memberi informasi pengembalian dana pada layanan tersebut. Semua layanan bersifat otomatis, jika terjadi Error atau Partial maka refund otomatis sesuai jumlah yang belum diproses sistem.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>3.</b> Kami tidak melakukan garansi untuk layanan Non Refill atau tanpa keterangan Refill, seperti yang telah dijelaskan pada deskripsi layanan. Jika layanan refill, maka pengisian ulang akan dilakukan otomatis selama waktu yang dijelaskan di deskripsi layanan, setelah waktu tersebut kami tidak akan melakukan refill otomatis lagi.</p>
                                </div>
                                <!-- Question/Answer -->
                                <div>
                                    <p class="mb-4"><b>4.</b> Kesalahan input oleh pengguna bukan tanggung jawab kami, dengan melakukan order artinya pengguna telah melakukan cek sebelum diinput, berakibat tidak akan refund karena kesalahan input.</p>
                                </div>
                            </div>
                        </div>
                        <!--/col-md-6-->
                    </div> <!-- /row pt-2 -->
                </li>
            </ul>

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