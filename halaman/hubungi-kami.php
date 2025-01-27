<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Hubungi <?php echo $data['short_title']; ?></title>
<meta name="description" content="Jangan ragu menghubungi kami melalui whatsapp (deposit manual & kerjasama), halaman tiket (info produk, layanan & orderan), atau melalui email <?php echo $data['email']; ?>. CS kami siap membantu Anda non stop 24 jam setiap hari."/>

<!--OG2-->
<meta content="Hubungi <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Jangan ragu menghubungi kami melalui whatsapp (deposit manual & kerjasama), halaman tiket (informasi produk, layanan & orderan), atau melalui email <?php echo $data['email']; ?>. CS kami siap membantu Anda non stop 24 jam setiap hari." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Hubungi <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital256.png" property="og:image"/>
<meta content="Hubungi <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Jangan ragu menghubungi kami melalui whatsapp (deposit manual & kerjasama), halaman tiket (info produk, layanan & orderan), atau melalui email <?php echo $data['email']; ?>. CS kami siap membantu Anda non stop 24 jam setiap hari." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/kitadigital/kitadigital256.png" property="twitter:image"/>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-responsive">
                <center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Hubungi <?php echo $data['short_title']; ?></b></h1>
                    <div class="box-content text-center">
                        <a href="/halaman/hubungi-kami" title="Kontak Dukungan Pelanggan <?php echo $data['short_title'];?>" alt="Kontak Dukungan Pelanggan <?php echo $data['short_title'];?>"> <img src="../assets/images/halaman/hubungi-kami.png" class="img-responsive" style="max-width: 95%; min-width: 250px;"></a>
                    </div><br/>
                </center>
            </div>

            <div class="card-body table-responsive">
                <center>
                    <div class="m-t-0 header-title"><b> Kontak </b></div>
                    Jangan ragu menghubungi kami melalui whatsapp (deposit manual & kerjasama), halaman tiket (informasi produk, layanan & pemesanan), atau melalui email <?php echo $data['email']; ?>. CS kami siap membantu Anda non stop 24 jam setiap hari.
                </center>
                <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <tbody>
                        <tr>
                            <td align="right" style="width: 50%;">
                                <a href="<?php echo $data['url_email']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 115px" target="_blank"><i class="mdi mdi-email"></i> Email</a>
                            </td>
                            <td align="left" style="width: 50%">
                                <a href="<?php echo $data['url_whatsapp']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 115px" target="_blank"><i class="mdi mdi-whatsapp"></i> Whatsapp</a>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" style="width: 50%;">
                                <a href="https://m.me/<?php echo $data['facebook']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 118px" target="_blank"><i class="mdi mdi-facebook-messenger"></i> Messenger</a>
                            </td>
                            <td align="left" style="width: 50%">
                                <a href="<?php echo $data['url_whatsapp']; ?>" alt="Kerja Sama Periklanan" alt="Kerja Sama Periklanan" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 118px" target="_blank" rel="dofollow"><i class="mdi mdi-heart"></i> Iklan</a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <center>
                    <div class="m-t-0 header-title"><b> Sosial Media </b></div>
                </center>
                <table class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <tbody>
                        <tr>
                            <td align="right" style="width: 50%;">
                                <a href="<?php echo $data['url_facebook']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 118px" target="_blank"><i class="mdi mdi-facebook"></i> Facebook</a>
                            </td>
                            <td align="left" style="width: 50%;">
                                <a href="<?php echo $data['url_instagram']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 118px" target="_blank"><i class="mdi mdi-instagram"></i> Instagram</a>
                            </td>
                        </tr>

                        <tr>
                            <td align="right" style="width: 50%;">
                                <a href="<?php echo $data['url_youtube']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 118px" target="_blank"><i class="mdi mdi-youtube"></i> Youtube</a>
                            </td>
                            <td align="left" style="width: 50%;">
                                <a href="<?php echo $data['url_twitter']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 118px" target="_blank"><i class="mdi mdi-twitter"></i> Twitter</a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <center>
                    <div class="m-t-0 header-title"><b> Alamat </b></div>
                    <div><?php echo $data['alamat']; ?><br/>
                        <a href="<?php echo $data['url_alamat']; ?>" class="btn btn-primary btn-bordred btn-rounded waves-effect waves-light" style="width: 200px" target="_blank"><i class="mdi mdi-google-maps"></i> Google Maps</a>
                    </div>
                </center>

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