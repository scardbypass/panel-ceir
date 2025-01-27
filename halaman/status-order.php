<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Informasi Status Order</title>
<meta name="description" content="Informasi Status Order Layanan SMM Panel Indonesia dan Pulsa H2H Sistem PPOB di <?php echo $data['title']; ?>."/>

<!--OG2-->
<meta content="Informasi Status Order Produk dan Layanan <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Informasi Status Order Layanan SMM Panel Indonesia dan Pulsa H2H Sistem PPOB di <?php echo $data['title']; ?>." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Informasi Status Order Produk dan Layanan <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Informasi Status Order Produk dan Layanan <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Informasi Status Order Layanan SMM Panel Indonesia dan Pulsa H2H Sistem PPOB di <?php echo $data['title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Informasi Status Order</b></h1>
                <div class="box-content text-center">
                    <a href="/halaman/status-order" title="Status Order <?php echo $data['short_title'];?>" alt="Status Order <?php echo $data['short_title'];?>"> <img src="/assets/images/halaman/status-order.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
                </div><br/>
            </center>

            <div class="card">
                <div class="card-body table-responsive">
                    <ul class="nav nav-pills navtab-bg">
                        <li class="nav-item">
                            <div class="row pt-2">

                                <div class="col-lg-6">
                                    <div class="p-lg-2">

                                        <!-- Question/Answer -->
                                        <div>
                                            <p class="mb-4"> <span class="badge badge-success">SUCCESS</span> Pesanan selesai diproses. Jika layanan tersebut adalah refill, maka kami isi ulang otomatis jika ada yang berkurang dalam waktu tertentu (sesuai deskripsi layanan tersebut).</p>
                                        </div>

                                        <!-- Question/Answer -->
                                        <div>
                                            <p class="mb-4"> <span class="badge badge-info">PROCESSING</span> Pesanan sedang di proses. Lama proses tergantung pada masing-masing layanan, ini dijelaskan di deskripsi saat melakukan order. Jika tidak ditampilkan, kemungkinan pengerjaan akan lebih lama dari layanan yang terdapat dibawahnya. 
                                            </p>
                                        </div>

                                        <!-- Question/Answer -->
                                        <div>
                                            <p class="mb-4"> <span class="badge badge-warning">PENDING</span> Pesanan dalam antrian untuk di proses. Orderan Anda telah berhasil, tetapi belum di proses. </p>
                                        </div>

                                    </div>
                                </div>
                                <!--/col-md-6-->

                                <div class="col-lg-6">
                                    <div class="p-lg-2">
                                        <!-- Question/Answer -->
                                        <div>
                                            <p class="mb-4"> <span class="badge badge-danger">PARTIAL</span> Pesanan hanya masuk sebagian dari jumlah yang di order. Anda hanya membayar yang masuk saja, dan sebagian dana akan kami kembalikan.</p>
                                        </div>
                                        <!-- Question/Answer -->
                                        <div>
                                            <p class="mb-4"> <span class="badge badge-danger">ERROR</span> Terjadi kesalahan sistem / orderan terlalu padat pada suatu layanan, sehingga harus kami batalkan untuk menghindari proses yang bisa memakan waktu lama. Jika error, saldo otomatis kembali ke akun Anda.</p>
                                        </div>
                                        <!-- Question/Answer -->
                                        <div>
                                            <p class="mb-4"> <span class="badge badge-primary">REFILL</span> Jika Anda membeli layanan refill dan ternyata beberapa hari jumlah berkurang, maka akan otomatis di refill/isi ulang gratis. <font color="#000000"><b>Catatan:</b> kami hanya akan mengisi ulang jika layanan yang dibeli adalah layanan refill.</font></p>
                                        </div>
                                
                                    </div>
                                </div>
                                <!--/col-md-6-->

                            </div> <!-- /row pt-2 -->
                        </li>
                    </ul>
                </div>
            </div>

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