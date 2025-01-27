<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Dukungan Teknologi dan Fitur Unggulan <?php echo $data['short_title']; ?></title>
<meta name="description" content="Fitur unggulan dan teknologi yang mendukung ini tentunya adalah bagian dari Platform <?php echo $data['short_title']; ?> yang telah memberi dukungan penyimpanan dan pengelolaan data, cloud web hosting, metode algoritma, sistem pembayaran, e-payment, dan lainnya yang akan terus kami tingkatkan kualitasnya."/>

<!--OG2-->
<meta content="Fitur Unggulan dan Teknologi dibalik <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Fitur unggulan dan teknologi yang mendukung ini tentunya adalah bagian dari Platform <?php echo $data['short_title']; ?> yang telah memberi dukungan penyimpanan dan pengelolaan data, cloud web hosting, metode algoritma, sistem pembayaran, e-payment, dan lainnya yang akan terus kami tingkatkan kualitasnya." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Fitur Unggulan dan Teknologi dibalik <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/dukungan-teknologi.png" property="og:image"/>
<meta content="Fitur Unggulan dan Teknologi dibalik <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Fitur unggulan dan teknologi yang mendukung ini tentunya adalah bagian dari Platform <?php echo $data['short_title']; ?> yang telah memberi dukungan penyimpanan dan pengelolaan data, cloud web hosting, metode algoritma, sistem pembayaran, e-payment, dan lainnya yang akan terus kami tingkatkan kualitasnya." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/dukungan-teknologi.png" property="twitter:image"/>

<link href="/assets/css/stylepage.css" rel="stylesheet" type="text/css"/>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Dukungan Teknologi dan Fitur Unggulan <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
						<a href="/halaman/dukungan-teknologi" title="Dukungan Teknologi <?php echo $data['short_title']; ?>" alt="Dukungan Teknologi <?php echo $data['short_title']; ?>"> <img src="/assets/images/halaman/dukungan-teknologi.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
					</div><br/>
				</center>
				<?php 
				//Call Dukungan Teknologi
				$CallPage = Show('halaman', "id = '5'");
				echo "".$CallPage['konten']."";
				?>
			</div>

			<div class="card-body">
				<!-- Teknologi -->
				<section class="mini-feature__style-2">
					<div class="container">
						<h2 class="header-title text-uppercase text-center"><i aria-hidden="true" class="fa fa-star"></i> Dukungan Teknologi</h2><br/>
						<div class="row text-center" style="font-weight: bold;">
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="data-box">
									<h3 class="header-title">AI Technology</h3>
									<span>
										Artificial Intelligence
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Intel</h3>
									<span>
										Intel Corporation
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Domainesia</h3>
									<span>
										Cloud Hosting
									</span>
								</div>
								<br/><br/>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="data-box">
									<h3 class="header-title">Cloudlinux</h3>
									<span>
										Hosting & Data Center
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Cloudflare</h3>
									<span>
										Content Delivery Network
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Cpanel</h3>
									<span>
										Host Control Panel
									</span>
								</div>
								<br/><br/>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="data-box">
									<h3 class="header-title">PHP</h3>
									<span>
										Hypertext Preprocessor
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">MySQL</h3>
									<span>
										Database Management System
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Let's Encrypt</h3>
									<span>
										Transport Layer Security
									</span>
								</div>
								<br/><br/>
							</div>
						</div>
					</div>
				</section>
			</div>

			<br/><br/>

			<div class="card-body">
				<!-- Feature -->
				<section class="mini-feature__style-2">
					<div class="container">
						<h2 class="header-title text-uppercase text-center"><i aria-hidden="true" class="fa fa-star"></i> Fitur Unggulan</h2><br/>
						<div class="row text-center">
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="data-box">
									<h3 class="header-title">Keamanan Transaksi</h3>
									<span>
										Perlindungan keamanan adalah prioritas, kami menjamin privasi data dan transaksi divalidasi & disetujui pengguna sendiri.
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Robot <?php echo $data['short_title']; ?></h3>
									<span>
										Fitur otomatisasi dengan AI diterapkan untuk mempopulerkan bisnis Anda di Sosial Media, Toko Online dan Marketplace.
									</span>
								</div>
								<br/><br/>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="data-box">
									<h3 class="header-title">Sistem PPOB</h3>
									<span>
										Setiap pesanan di proses secara Otomatis dan Instan dengan PPOB, langsung pada Server utama kami tanpa penundaan.
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Integritas API & H2H</h3>
									<span>
										Mendukung transaksi melalui Application Programming Interface (API). Sangat cocok untuk sistem Host to Host (H2H).
									</span>
								</div>
								<br/><br/>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="data-box">
									<h3 class="header-title">Mudah Digunakan</h3>
									<span>
										Transaksi dapat diakses cepat & responsive semua perangkat, tersedia App Mobile Android yang mudah digunakan.
									</span>
								</div>
								<br/><br/>
								<div class="data-box">
									<h3 class="header-title">Metode Pembayaran</h3>
									<span>
										Kami mendukung berbagai metode pembayaran, mulai dari pulsa, paypal, e-money, bank, e-payment dan cryptocurrency.
									</span>
								</div>
								<br/><br/>
							</div>
						</div>
					</div>
				</section>
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