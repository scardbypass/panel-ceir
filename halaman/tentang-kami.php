<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Sekilas Tentang <?php echo $data['short_title']; ?></title>
<meta name="description" content="Sekilas Tentang <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>

<!--OG2-->
<meta content="Sekilas Tentang <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Sekilas Tentang <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="Sekilas Tentang <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Sekilas Tentang <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Sekilas Tentang <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<link href="/assets/css/stylepage.css" rel="stylesheet" type="text/css"/>
<div class="row">
	<div class="col-sm-12">
		<div class="card">

			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Sekilas Tentang <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
                        <a href="/halaman/tentang-kami" title="Sekilas Tentang <?php echo $data['short_title'];?>" alt="Sekilas Tentang <?php echo $data['short_title'];?>"> <img src="/assets/images/halaman/tentang-kami.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
                    </div><br/>
				</center>
				<?php 
				//Call Tentang Kami
				$CallPage = Show('halaman', "id = '1'");
				echo "".$CallPage['konten']."";
				?>
			</div>

			<div class="card-body table-responsive">
				<div class="container">
					<div class="section-content row">

						<div class="col-sm-6 align-left">
							<div>
								<h2 class="text-uppercase text-center header-title"><b>Mengapa Memilih Kami?</b></h2>
								Kami menyediakan layanan otomatisasi berteknologi AI, berkualitas, cepat & aman. Dengan mendaftar, Anda bisa menjadi Member, Reseller atau Agen layanan jasa otomatisasi Sosial Media, optimasi Toko Online, jasa Pembayaran Online dan penyedia <a title="Produk Digital <?php echo $data['short_title']; ?>" target="_blank" alt="Produk Digital <?php echo $data['short_title']; ?>" href="/halaman/daftar-harga">Produk Digital</a>.<br><br>
								<?php echo $data['short_title']; ?> adalah startup terbaru generasi milenial 4.0 yang diluncurkan <a title="KITADIGITAL Group" alt="KITADIGITAL Group" href="http://www.kitadigital.my.id" target="_blank">KITADIGITAL Group</a>, kami berkomitmen membantu Anda dalam pengembangan bisnis di internet, baik untuk perusahaan, merk/brand, bisnis lokal, public figure, maupun perorangan/pribadi.<br><br>

								<h2 class="text-uppercase text-center header-title"><b>Optimasi Sosmed & Toko Online</b></h2>
								Tersedia layanan <a title="Jasa <?php echo $data['short_title']; ?>" alt="Jasa <?php echo $data['short_title']; ?>" target="_blank" href="/halaman/daftar-harga#SosialMedia">Sosial Media Marketing</a> All-In-One untuk optimasi Youtube, Facebook, Instagram, Twitter, Pinterest, Tiktok dan WhatsApp. Juga terdapat layanan toko online untuk Shopee, Tokopedia, Bukalapak, dan Olshop di Sosial Media.<br><br>

								<h3 class="text-uppercase text-center header-title"><b>Konten Panduan <?php echo $data['short_title']; ?></b></h3>
								Kami menyediakan blog panduan menggunakan fitur dan layanan, diantaranya cara mengoptimalkan sosial media, subscribe youtube, trafik website, hingga panduan transaksi jasa dan layanan di <?php echo $data['short_title']; ?> yang bisa Anda pelajari.<br><br>
							</div>
						</div>

						<div class="col-sm-6 pull-right">
							<div>
								<h2 class="text-uppercase text-center header-title"><b>Produk Digital Terkoneksi PPOB</b></h2>
								Tersedia berbagai Produk Digital dengan sistem PPOB untuk transaksi E-Money (Gojek, Grab, Dana, Ovo & Steam Wallet), <a title="Voucher All Games <?php echo $data['short_title']; ?>" target="_blank" alt="Voucher All Games <?php echo $data['short_title']; ?>" href="/halaman/daftar-harga">Voucher All Games</a>, Token PLN, <a title="Pulsa Online Voucher All Games <?php echo $data['short_title']; ?>" alt="Pulsa Online Voucher All Games <?php echo $data['short_title']; ?>" target="_blank" href="/halaman/daftar-harga">Pulsa & Paket Internet All Operator</a> Prabayar & Pascabayar, Paket Telepon & SMS, hingga <a title="Produk Operator Global <?php echo $data['short_title']; ?>" alt="Produk Operator Global <?php echo $data['short_title']; ?>" target="_blank" href="/halaman/daftar-harga">Produk Operator Global</a>.<br><br>
								Sistem PPOB adalah metode pembayaran yang terkoneksi secara online dalam waktu cepat & aman, dengan menjalin kerjasama dengan berbagai pihak, setiap transaksi valid akan secara otomatis terkonfirmasi sehingga Anda tidak perlu melakukan konfirmasi lagi.<br><br>

								<h2 class="text-uppercase text-center header-title"><b>Layanan Exchanger & Perbankan</b></h2>
								Layanan exchange tersedia untuk pertukaran (jual/beli) dollar, cryptocurrency, dan voucher indodax. Sedangkan pada jasa perbankan hanya E-Money Changer dan BRI Brizzi. Tersedia untuk Member, Reseller atau Agen.<br><br>

								<h3 class="text-uppercase text-center header-title"><b>Portal Edukasi Mediabisnis.co.id</b></h3>
								Kami juga berfokus mengembangan situs portal bisnis dan investasi. Dapatkan panduan Internet Marketing, Digital Marketing, Bisnis Affiliate, Cryptocurrency, Forex dan panduan Saham di <a title="Portal Edukasi Bisnis dan Investasi" alt="Portal Edukasi Bisnis dan Investasi" href="https://kitadigital.id" target="_blank">Portal Edukasi Bisnis & Investasi.</a><br><br>
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