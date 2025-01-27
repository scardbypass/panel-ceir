<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Dukungan Pembayaran <?php echo $data['short_title']; ?></title>
<meta name="description" content="Platform <?php echo $data['short_title']; ?> menyediakan berbagai metode pembayaran, mulai dari Transfer Pulsa, Cryptocurrency Exchanger, PPOB, Paypal, hingga Bank Transfer."/>

<!--OG2-->
<meta content="Dukungan Pembayaran <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Platform <?php echo $data['short_title']; ?> menyediakan berbagai metode pembayaran, mulai dari Transfer Pulsa, Cryptocurrency Exchanger, PPOB, Paypal, hingga Bank Transfer." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Dukungan Pembayaran <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/dukungan-pembayaran.jpg" property="og:image"/>
<meta content="Dukungan Pembayaran <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Platform <?php echo $data['short_title']; ?> menyediakan berbagai metode pembayaran, mulai dari Transfer Pulsa, Cryptocurrency Exchanger, PPOB, Paypal, hingga Bank Transfer." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/dukungan-pembayaran.jpg" property="twitter:image"/>

<link href="/assets/css/stylepage.css" rel="stylesheet" type="text/css"/>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Dukungan Pembayaran <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
						<a href="/halaman/dukungan-pembayaran" title="Metode Pembayaran <?php echo $data['short_title'];?>" alt="Metode Pembayaran <?php echo $data['short_title'];?>"> <img src="/assets/images/halaman/dukungan-pembayaran.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
					</div><br/>
				</center>
				<?php 
				//Call Dukungan Pembayaran
				$CallPage = Show('halaman', "id = '6'");
				echo "".$CallPage['konten']."";
				?>
			</div>

			<div class="card-body">
				<!-- Teknologi -->
				<section class="mini-feature__style-2">
					<div class="container">
						<div class="row" style="font-weight: bold;">
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="BNI" src="/assets/images/pembayaran/kitadigital-deposit-bni.png">
										<img alt="BNI" src="/assets/images/pembayaran/kitadigital-deposit-bni.png">
									</div>
									<div class="data-box">
										<h2 class="header-title">BNI</h2>
										<span>
											PT. Bank Negara Indonesia (Persero), Tbk.
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="BRI" src="/assets/images/pembayaran/kitadigital-deposit-bri.png">
										<img alt="BRI" src="/assets/images/pembayaran/kitadigital-deposit-bri.png">
									</div>
									<div class="data-box">
										<h2 class="header-title">BRI</h2>
										<span>
											PT. Bank Rakyat Indonesia (Persero), Tbk.
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="BCA" src="/assets/images/pembayaran/kitadigital-deposit-bca.png">
										<img alt="BCA" src="/assets/images/pembayaran/kitadigital-deposit-bca.png">
									</div>
									<div class="data-box">
										<h2 class="header-title">BCA</h2>
										<span>
											PT. Bank Central Asia, Tbk.
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="GOPAY" src="/assets/images/pembayaran/kitadigital-deposit-gopay.png">
										<img alt="GOPAY" src="/assets/images/pembayaran/kitadigital-deposit-gopay.png">
									</div>
									<div class="data-box">
										<h2 class="header-title">GOPAY</h2>
										<span>
											PT. Dompet Anak Bangsa
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="OVO" src="/assets/images/pembayaran/kitadigital-deposit-ovo.png">
										<img alt="OVO" src="/assets/images/pembayaran/kitadigital-deposit-ovo.png">
									</div>
									<div class="data-box">
										<h2 class="header-title">OVO</h2>
										<span>
											PT. Visionet Internasional
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="DANA" src="/assets/images/pembayaran/kitadigital-deposit-dana.png">
										<img alt="DANA" src="/assets/images/pembayaran/kitadigital-deposit-dana.png">
									</div>
									<div class="data-box">
										<h2 class="header-title">DANA</h2>
										<span>
											PT. Espay Debit Indonesia Koe
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="TELKOMSEL" src="/assets/images/pembayaran/kitadigital-deposit-telkomsel.png">
										<img alt="TELKOMSEL" src="/assets/images/pembayaran/kitadigital-deposit-telkomsel.png">
									</div>
									<div class="data-box">
										<h3 class="header-title">TELKOMSEL</h3>
										<span>
											PT. Telekomunikasi Selular.
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="XL AXIATA" src="/assets/images/pembayaran/kitadigital-deposit-xl.png">
										<img alt="XL AXIATA" src="/assets/images/pembayaran/kitadigital-deposit-xl.png">
									</div>
									<div class="data-box">
										<h3 class="header-title">XL AXIATA</h3>
										<span>
											PT. XL Axiata, Tbk.
										</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6 wow fadeInUp">
								<div class="mini-feature-box">
									<div class="icon-box">
										<img alt="AXIS" src="/assets/images/pembayaran/kitadigital-deposit-axis.png">
										<img alt="AXIS" src="/assets/images/pembayaran/kitadigital-deposit-axis.png">
									</div>
									<div class="data-box">
										<h3 class="header-title">AXIS</h3>
										<span>
											PT. XL Axiata, Tbk.
										</span>
									</div>
								</div>
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