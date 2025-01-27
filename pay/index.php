<?php

session_start();
require("../config.php");
require '../lib/session_user.php';
require '../lib/session_login.php';

require("../lib/header.php");
?>

<!--Title-->
<title>Deposit Saldo</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="col-12">
		<div class="card card-body">
			<center>
				<h1 class="m-t-0 text-uppercase text-center header-title"><b>Pilih Metode Deposit</b></h1>
			</center>

<!--START DEPO MANUAL KE NO PRIBADI-->
			<div class="row">
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="pulsa" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-telkomsel.png" class="img-responsive" width="200"/>
						</center>
						<h4>Pulsa TSEL</h4>
					</a>
				</div>
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="emoney-manual" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-emoney.png" class="img-responsive" width="200"/>
						</center>
						<h4>E-Money Manual</h4>
					</a>
				</div>
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="bank-manual" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-bank.png" class="img-responsive" width="200"/>
						</center>
						<h4>Bank Manual</h4>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="epayment-manual" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-epayment.png" class="img-responsive" width="200"/>
						</center>
						<h4>E-Payment Global</h4>
					</a>
				</div>
				<div class="col-lg-4 col-sm-12">
					<a class="card card-box text-center text-danger" onclick="alert('Sedang Maintenance..');" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-cryptocurrency.png" class="img-responsive" width="200"/>
						</center>
						<h4>Cryptocurrency</h4>
					</a>
				</div>
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="redeem" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-voucher.png" class="img-responsive" width="200"/>
						</center>
						<h4>Redeem Voucher</h4>
					</a>
				</div>
			</div>
<!--END DEPO MANUAL KE NO PRIBADI-->

<!--START DEPO OTOMATIS KE DPEDIA-->
			<div class="row">
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="bca" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-bca.png" class="img-responsive" width="200"/>
						</center>
						<h4>BCA Auto Confirm</h4>
					</a>
				</div>
				<div class="col-lg-4 col-sm-12">
					<a class="card card-body text-center text-success" href="bni" style="height:200px;">
						<center>
							<img src="/assets/images/pembayaran/kitadigital-deposit-bni.png" class="img-responsive" width="200"/>
						</center>
						<h4>BNI Auto Confirm</h4>
					</a>
				</div>
			</div>
<!--END DEPO OTOMATIS KE DPEDIA-->

		</div>
	</div>
</div>

<?php
require ("../lib/footer.php");
?>