<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Kemitraan Usaha dan Jaringan Bisnis <?php echo $data['short_title']; ?></title>
<meta name="description" content="Berikut adalah daftar partner dan jaringan kerjasama kami yang telah terdaftar. Jangan ragu menghubungi kami melalui whatsapp, halaman kontak, atau via email support."/>

<!--OG2-->
<meta content="Informasi Kemitraan Usaha dan Jaringan Bisnis <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Berikut adalah daftar partner dan jaringan kerjasama kami yang telah terdaftar. Jangan ragu menghubungi kami melalui whatsapp, halaman kontak, atau via email support." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Informasi Kemitraan Usaha dan Jaringan Bisnis <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/mitra-dan-jaringan.png" property="og:image"/>
<meta content="Informasi Kemitraan Usaha dan Jaringan Bisnis <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Berikut adalah daftar partner dan jaringan kerjasama kami yang telah terdaftar. Jangan ragu menghubungi kami melalui whatsapp, halaman kontak, atau via email support." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/mitra-dan-jaringan.png" property="twitter:image"/>

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Kemitraan Usaha dan Jaringan Bisnis <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
                        <a href="/halaman/mitra-dan-jaringan" title="Kemitraan dan Jaringan <?php echo $data['short_title']; ?>" alt="Kemitraan dan Jaringan <?php echo $data['short_title']; ?>"> <img src="/assets/images/halaman/mitra-dan-jaringan.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
                    </div><br/>
				</center>
				<?php 
				//Call Mintra dan Jaringan
				$CallPage = Show('halaman', "id = '4'");
				echo "".$CallPage['konten']."";
				?>
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