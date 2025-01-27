<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?></title>
<meta name="description" content="Berikut adalah produk dan layanan jasa optimasi Sosial Media, Toko Online, Payment Point Online Bank (PPOB), dan berbagai produk virtual yang tersedia di <?php echo $data['title']; ?>."/>

<!--OG2-->
<meta content="Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Berikut adalah Jasa Sosoal Media, Toko Online, Payment Point Online Bank (PPOB), dan berbagai produk virtual yang tersedia di <?php echo $data['title']; ?>." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/produk-dan-layanan.png" property="og:image"/>
<meta content="Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Berikut adalah produk dan layanan jasa optimasi Sosial Media, Toko Online, Payment Point Online Bank (PPOB), dan berbagai produk virtual yang tersedia di <?php echo $data['title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/produk-dan-layanan.png" property="twitter:image"/>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
                        <a href="/halaman/produk-dan-layanan" title="<?php echo $data['short_title'];?>" alt="<?php echo $data['title'];?>"> <img src="/assets/images/halaman/produk-dan-layanan.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
                    </div>
	                <?php 
					//Call Produk dan Layanan
					$CallPage = Show('halaman', "id = '3'");
					echo "".$CallPage['konten']."";
					?>
                    Silakan pilih kategori terlebih dahulu
				</center>

				<!--Navigasi v2-->
				<center>
					<div style="min-width: 200px;max-width: 400px;">
						<div class="input-group btn btn-dark">
							<div class="input-group-prepend">
								<span class="btn btn-primary" id="navigasi" ><i aria-hidden="true" class="fa fa-cog fa-spin"></i></span>
							</div>
							<select name="category" class="form-control" id="filter_kategori">
								<option value="Pilih">PILIH KATEGORI</option>
								<option value="SosialMedia">SOSIAL MEDIA 1</option>
								<option value="SosialMedia2">SOSIAL MEDIA 2</option>
								<option value="SosialMedia3">SOSIAL MEDIA 3</option>
								<option value="PulsaReguler">PULSA REGULER</option>
								<option value="PulsaTransfer">PULSA TRANSFER</option>
								<option value="VoucherGames">TOP UP GAMES</option>
								<option value="PaketInternet">PAKET INTERNET</option>
								<option value="TeleponSMS">TELEPON & SMS</option>
								<option value="SaldoEMoney">TOP UP E-MONEY</option>
								<option value="TokenPLN">TOKEN PLN</option>
								<option value="ProviderGlobal">PROVIDER GLOBAL</option>
								<option value="VoucherGames">TOP UP GAMES</option>
								<option value="VoucherDigital">VOUCHER DIGITAL</option>
								<option value="AkunPremium">AKUN PREMIUM</option>
							</select>
						</div>
					</div>
				</center>

				<div class="tab-content">
					<div id="Pilih" class="tab-pane active">
						<center>
							<h2 class="text-primary header-title" style="text-transform: uppercase;"><b><?php echo $data['title']; ?></b></h2>
							<br/>
							<div class="table-responsive" style="max-width: uppercase;">
								<div class="col-sm-12">
									Platform bisnis yang menyediakan berbagai Layanan Multi Payment, Produk Digital PPOB, Saldo E-Money Lokal & Global, Voucher Games, Pulsa All Operator, Exchanger Voucher Indodax, Convert Pulsa, dan Jasa Pemasaran Sosial Media.
									<br/><br/>
									Anda dapat menjadi member, reseller atau agen berbagai jasa pembayaran yang terkoneksi PPOB, serta jasa optimasi media sosial dan toko online. Tersedia produk PPOB multi provider, e-money, voucher, pulsa, dan jasa exchanger.
								</div>
							</div>
						</center>
					</div>
					<div id="SosialMedia" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>JASA SOSIAL MEDIA</b></h2>
						</center>
						<div style="text-align:left;">
							Sudah coba berbagai cara tapi jumlah <b>followers, subscribe, viewers, liker</b> atau <b>comment</b> di akun kamu nggak nambah-nambah? Coba gunakan tool dari situs <?php echo $data['short_title']; ?> dibawah ini untuk membuatmu populer di sosial media dengan cepat! Kami merekomendasikan tool sosial media terbaik dan organik, serta aman digunakan yang bisa kamu coba. <br/><br/>
							Siapa sih yang nggak mau followers, subscribe, liker atau comment dengan instant dan cepat. Bahkan, kamu mungkin udah kenal dengan aplikasi auto followers gratis di internet untuk dapat ribuan followers gratis tiap hari!<br/><br/>
							Tapi ingat ya, jika <b style="color:red">memasukkan username dan password</b>, akunmu tidak akan aman karena situs yang kamu gunakan mungkin menyimpannya.<br/><br/>
							Nah, disini <?php echo $data['short_title']; ?> memberikan tool <b style="color:green">tanpa harus login akun</b>, <?php echo $data['short_title']; ?> tidak meminta password akun sosial mediamu, tapi hanya <b>username</b> saja jika ingin menambah <b>followers atau subscribe</b>, atau <b>link</b> postinganmu jika ingin menambah <b>like, viewers, atau comment</b> dengan cepat.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Sosial Media'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Jasa Sosial Media <?= $dpr['nama']; ?> Permanen dan Aman</h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_sosmed WHERE kategori = '".$dpr['kode']."' AND tipe = 'Sosial Media' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Layanan</th>
											<th width="100">Harga/1000</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Aktif") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Jasa <?= $dprl['layanan']; ?> Terbaik & Terpercaya</td>
												<td>Rp.<?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="SosialMedia2" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>JASA SOSIAL MEDIA 2</b></h2>
						</center>
						<div style="text-align:left;">
							Sudah coba berbagai cara tapi jumlah <b>followers, subscribe, viewers, liker</b> atau <b>comment</b> di akun kamu nggak nambah-nambah? Coba gunakan tool dari situs <?php echo $data['short_title']; ?> dibawah ini untuk membuatmu populer di sosial media dengan cepat! Kami merekomendasikan tool sosial media terbaik dan organik, serta aman digunakan yang bisa kamu coba. <br/><br/>
							Siapa sih yang nggak mau followers, subscribe, liker atau comment dengan instant dan cepat. Bahkan, kamu mungkin udah kenal dengan aplikasi auto followers gratis di internet untuk dapat ribuan followers gratis tiap hari!<br/><br/>
							Tapi ingat ya, jika <b style="color:red">memasukkan username dan password</b>, akunmu tidak akan aman karena situs yang kamu gunakan mungkin menyimpannya.<br/><br/>
							Nah, disini <?php echo $data['short_title']; ?> memberikan tool <b style="color:green">tanpa harus login akun</b>, <?php echo $data['short_title']; ?> tidak meminta password akun sosial mediamu, tapi hanya <b>username</b> saja jika ingin menambah <b>followers atau subscribe</b>, atau <b>link</b> postinganmu jika ingin menambah <b>like, viewers, atau comment</b> dengan cepat.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan2 WHERE tipe = 'Sosial Media'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Jasa Sosial Media <?= $dpr['nama']; ?> Permanen dan Aman</h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_sosmed2 WHERE kategori = '".$dpr['kode']."' AND tipe = 'Sosial Media' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Layanan</th>
											<th width="100">Harga/1000</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Aktif") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Jasa <?= $dprl['layanan']; ?> Terbaik & Terpercaya</td>
												<td>Rp.<?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="SosialMedia3" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>JASA SOSIAL MEDIA 3</b></h2>
						</center>
						<div style="text-align:left;">
							Sudah coba berbagai cara tapi jumlah <b>followers, subscribe, viewers, liker</b> atau <b>comment</b> di akun kamu nggak nambah-nambah? Coba gunakan tool dari situs <?php echo $data['short_title']; ?> dibawah ini untuk membuatmu populer di sosial media dengan cepat! Kami merekomendasikan tool sosial media terbaik dan organik, serta aman digunakan yang bisa kamu coba. <br/><br/>
							Siapa sih yang nggak mau followers, subscribe, liker atau comment dengan instant dan cepat. Bahkan, kamu mungkin udah kenal dengan aplikasi auto followers gratis di internet untuk dapat ribuan followers gratis tiap hari!<br/><br/>
							Tapi ingat ya, jika <b style="color:red">memasukkan username dan password</b>, akunmu tidak akan aman karena situs yang kamu gunakan mungkin menyimpannya.<br/><br/>
							Nah, disini <?php echo $data['short_title']; ?> memberikan tool <b style="color:green">tanpa harus login akun</b>, <?php echo $data['short_title']; ?> tidak meminta password akun sosial mediamu, tapi hanya <b>username</b> saja jika ingin menambah <b>followers atau subscribe</b>, atau <b>link</b> postinganmu jika ingin menambah <b>like, viewers, atau comment</b> dengan cepat.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan3 WHERE tipe = 'Sosial Media'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Jasa Sosial Media <?= $dpr['nama']; ?> Permanen dan Aman</h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_sosmed3 WHERE kategori = '".$dpr['kode']."' AND tipe = 'Sosial Media' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Layanan</th>
											<th width="100">Harga/1000</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Aktif") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Jasa <?= $dprl['layanan']; ?> Terbaik & Terpercaya</td>
												<td>Rp.<?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="AkunPremium" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK AKUN PREMIUM, MURAH DAN LEGAL</b></h2>
						</center>
						<div style="text-align:left;">
							Layanan <?php echo $data['short_title']; ?> menyediakan berbagai akun sosial media premium untuk Netflix, Spotify, dan Youtube Premium dengan harga yang murah dan pastinya 100% legal.<br/><br/>
							Akun premium yang kami sediakan berlaku untuk semua member dengan masa aktif mulai dari 1 bulan hingga 12 bulan. Kamu tidak perlu repot untuk mendaftar dan membeli akun premium secara langsung di masing-masing sosial media, karena di <?php echo $data['short_title']; ?> sudah tersedia berbagai akun premium (all in one).
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Digital'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Akun <?= $dpr['nama']; ?> Premium Murah dan Legal</h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_digital WHERE operator = '".$dpr['kode']."' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Akun <?= $dprl['layanan']; ?> Termurah</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="PaketInternet" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PAKET INTERNET MURAH, 3G & 4G (24 JAM) ALL OPERATOR</b></h2>
						</center>
						<div style="text-align:left;">
							Kehadiran paket internet yang dijual langsung secara online tentu saja sangat mudah untuk menjalani aktivitas sehari-hari. Jika aktivitas kamu membutuhkan komunikasi mobile dengan internet, ini akan merepotkan jika paket data internet kamu tiba-tiba habis.<br/><br/>
							Sebuah layanan pembelian paket internet online di <?php echo $data['short_title']; ?> bisa kamu coba, kamu tidak perlu repot untuk mendaftar paket internet dengan pulsa. Hanya memilih paket internet yang tersedia di <?php echo $data['short_title']; ?> dan bayar dengan harga murah.<br/><br/>
							<?php echo $data['short_title']; ?> menyediakan Paket Data Internet Telkomsel, Indosat, XL, 3 (Tri) dan Smart fren, dengan harga termurah dan proses cepat. Berbagai pillihan jenis paket data, durasi, dan kuota membuat kamu lebih leluasa memilih.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Data'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PAKET <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Data' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Paket Internet Murah <?= $dprl['layanan']; ?> Terbaru</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="PulsaReguler" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PULSA ONLINE 24 JAM ALL OPERATOR</b></h2>
						</center>
						<div style="text-align:left;">
							Butuh pulsa mendadak untuk berbagai operator dan jumlah? Isi pulsa bisa jadi lebih cepat, mudah, dan lebih praktis. KM menyediakan berbagai metode pembayaran. Sekarang kamu order pembelian pulsa dengan menggunakan <?php echo $data['short_title']; ?>, dengan pilihan semua operator seluler di Indonesia, banyak jenis pulsa dan harga jual yang sesuai dengan kebutuhan kamu.<br/><br/>
							Membeli pulsa online di <?php echo $data['short_title']; ?> lebih mudah dan terpercaya, hanya dengan memasukkan nomor dan nominal, pulsa segera dikirim ke telepon dalam waktu singkat. Mengisi pulsa dapat dilakukan kapan saja, dimana saja melalui browser ponsel, smartphone dan tablet, PC / laptop di kantor dan di rumah dengan aplikasi <?php echo $data['short_title']; ?> versi Web dan Mobile.<br/><br/>
							Dapatkan harga pulsa termurah, diskon, hingga cashback setiap pembelian. Di <?php echo $data['short_title']; ?> kamu bisa beli pulsa hp lewat internet tanpa perlu pusing waktu & tempat, karna <?php echo $data['short_title']; ?> online 24 jam. Metode pembayaran pulsa bisa dengan Paypal, Cryptocurrency, Voucher Indodax, E-Money, dan Bank.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Pulsa'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PULSA <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Pulsa' AND jenis = 'Umum' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Pulsa <?= $dprl['layanan']; ?> Online 24 Jam</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="PulsaTransfer" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PULSA TRANSFER ONLINE 24 JAM ALL OPERATOR</b></h2>
						</center>
						<div style="text-align:left;">
							Butuh pulsa mendadak untuk berbagai operator dan jumlah? Isi pulsa bisa jadi lebih cepat, mudah, dan lebih praktis. KM menyediakan berbagai metode pembayaran. Sekarang kamu order pembelian pulsa dengan menggunakan <?php echo $data['short_title']; ?>, dengan pilihan semua operator seluler di Indonesia, banyak jenis pulsa dan harga jual yang sesuai dengan kebutuhan kamu.<br/><br/>
							Membeli pulsa online di <?php echo $data['short_title']; ?> lebih mudah dan terpercaya, hanya dengan memasukkan nomor dan nominal, pulsa segera dikirim ke telepon dalam waktu singkat. Mengisi pulsa dapat dilakukan kapan saja, dimana saja melalui browser ponsel, smartphone dan tablet, PC / laptop di kantor dan di rumah dengan aplikasi <?php echo $data['short_title']; ?> versi Web dan Mobile.<br/><br/>
							Dapatkan harga pulsa termurah, diskon, hingga cashback setiap pembelian. Di <?php echo $data['short_title']; ?> kamu bisa beli pulsa hp lewat internet tanpa perlu pusing waktu & tempat, karna <?php echo $data['short_title']; ?> online 24 jam. Metode pembayaran pulsa bisa dengan Paypal, Cryptocurrency, Voucher Indodax, E-Money, dan Bank.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Pulsa'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PULSA <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Pulsa' AND jenis = 'Pulsa Transfer' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Pulsa <?= $dprl['layanan']; ?> Online 24 Jam</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="TeleponSMS" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PAKET TELEPON & SMS TERMURAH ALL OPERATOR</b></h2>
						</center>
						<div style="text-align:left;">
							Kehadiran paket telepon dan sms yang dijual langsung secara online tentu saja sangat memudahkan untuk menjalani aktivitas sehari-hari. Jika aktivitas kamu membutuhkan komunikasi mobile, ini akan merepotkan jika paket telepon atau sms di ponselmu tiba-tiba habis.<br/><br/>
							Sebuah layanan pembelian paket telepon dan sms secara online ini bisa kamu coba, kamu tidak perlu repot untuk mendaftar paket dengan pulsa. Hanya memilih paket telepon dan sms yang tersedia dan bayar dengan harga termurah.<br/><br/>
							<?php echo $data['short_title']; ?> menyediakan Paket telepon dan sms untuk operator Telkomsel, Indosat, XL, 3 (Tri) dan Smart fren, dengan harga murah dan proses cepat. Berbagai pillihan jenis paket dan durasi membuat kamu lebih leluasa memilih sesuai kebutuhan.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Paket SMS & Telepon'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PAKET <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Paket SMS & Telepon' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Paket <?= $dprl['layanan']; ?> Termurah</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="SaldoEMoney" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN TOP UP SALDO E-MONEY ONLINE 24 JAM</b></h2>
						</center>
						<div style="text-align:left;">
							E-Money pada saat ini dapat dikatakan menjadi kebutuhan bagi sebagian masyarakat Indonesia. Jumlah promosi yang ditawarkan oleh masing-masing penyedia layanan uang elektronik dan kemudahan cara pembayaran sebagai alasan utama mereka menggunakan uang elektronik.<br/><br/>
							Namun banyak yang kadang-kadang sulit untuk melakukan pengisian saldo <b>E-Money</b>. Banyak cara yang bisa dilakukan untuk mengisi saldo e-money. Salah satunya adalah melalui Panel <?php echo $data['short_title']; ?>. Isi ulang saldo E-Money kamu dengan nyaman dan praktis.<br/><br/>
							Aplikasi dan situs web <?php echo $data['short_title']; ?> sangat difasilitasi dalam segala bentuk pembayaran uang elektronik seperti <b>Top Up DANA, Go Pay, Go Pay Driver, OVO, Grab Pay, TIX ID, PAY Shopee, Mandiri E-Toll, LinkAja, Tapcash BNI</b> dan banyak lagi.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'E-Money'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">TOP UP <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'E-Money' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Top Up Saldo <?= $dprl['layanan']; ?> Tercepat</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="TokenPLN" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN TOP UP TOKEN PLN ONLINE 24 JAM</b></h2>
						</center>
						<div style="text-align:left;">
							Beli token listrik (PLN) di Agen kini jadi bagian yang tak terlepas dari keseharian. Tak heran banyak yang menjadi agen token listrik PLN untuk mempermudah Top-Up listrik rumah. Apalagi, menjadi agen token listrik PLN bisa memberikan keuntungan yang lumayan.<br/><br/>
							Tertarik untuk <b>Jadi Agen Penjual Token Listrik PLN?</b> Tak perlu bingung karena kamu juga bisa menjadi agen penjual token listrik PLN dengan mudah dan praktis.<br/><br/>
							Cukup dengan satu aplikasi <?php echo $data['short_title']; ?>, kamu bisa mulai menjadi agen/reseller, dan juga member kami dengan membeli <b>Token PLN Harga Termurah</b>. Berikut daftar harga token listrik yang kami sediakan.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'PLN'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">TOP UP <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'PLN' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Top Up Token <?= $dprl['layanan']; ?> Online 24 Jam</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="ProviderGlobal" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN TOP UP PROVIDER GLOBAL ALL OPERATOR</b></h2>
						</center>
						<div style="text-align:left;">
							Selain sebagai penyedia produk digital dari indonesia, kami juga menyediakan berbagai layanan jasa Top Up, E-Payment, dan E-Wallet untuk produk digital di Asia.<br/><br/>
							Layanan dari <?php echo $data['short_title']; ?> akan terus dikembangkan menjadi lebih baik, agar kami bisa menyediakan banyak produk digital untuk Provider Global. Saat ini terdapat layanan untuk operator Digi, Maxis, Celcom, Umobile, Tunetalk, dan Steam Wallet.<br/><br/>
							Dan layanan perbankan dari provider indonesia dalam kategori ini yaitu Top Up saldo BRIZZI dari Bank BRI.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe IN ('Malaysia TOPUP','Philippines TOPUP','Singapore TOPUP','Thailand TOPUP','China TOPUP')"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">OPERATOR <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe IN ('Malaysia TOPUP','Philippines TOPUP','Singapore TOPUP','Thailand TOPUP','China TOPUP') ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Top Up <?= $dprl['layanan']; ?> Online 24 Jam</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="VoucherGames" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>TOP UP VOUCHER GAMES TERMURAH DAN TERPERCAYA</b></h2>
						</center>
						<div style="text-align:left;">
							Melakukan pembelian untuk item game dapat dilakukan dengan berbagai cara, yang paling umum adalah dengan kartu kredit atau transfer via bank. Jika kamu membeli dengan jumlah sedikit mungkin kamu dapat menggunakan transfer pulsa.<br/><br/>
							Di situs <?php echo $data['short_title']; ?>, kamu dapat <b>Top Up Games</b> dengan cepat dan mudah, kami menyediakan berbagai produk games dengan pembayaran melalui bank, e-payment, hingga pulsa transfer.<br/><br/>
							Dalam game kamu biasanya dapat membeli banyak barang-barang mulai dari hero, diamond, dan pernak-pernik lainnya. Sekarang banyak gamer indonesia yang mencari <b>Situs Top Up Game</b> yang murah dan terpercaya. <?php echo $data['short_title']; ?> adalah solusinya!
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Games'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">VOUCHER <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Games' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Top Up Voucher <?= $dprl['layanan']; ?> Termurah</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="VoucherDigital" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN TOP UP VOUCHER DIGITAL TERMURAH DAN AMAN</b></h2>
						</center>
						<div style="text-align:left;">
							Melakukan pembelian voucher digital bisa dilakukan dengan berbagai cara, yang paling umum menggunakan kartu kredit atau transfer via bank. Jika kamu membeli dengan jumlah sedikit mungkin dapat menggunakan pulsa transfer.<br/><br/>
							Di situs <?php echo $data['short_title']; ?>, kamu dapat <b>Top Up Voucher Digital</b> dengan cepat dan mudah, kami menyediakan berbagai produk voucher seperti voucher wifi id, alfamart, indomaret, bukalapak, grab driver, voucher internet all operator, dan lebih banyak lagi.
						</div>
						<br />
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Voucher'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">VOUCHER <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Voucher' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="100">ID</th>
											<th width="600">Produk</th>
											<th width="100">Harga</th>
										</tr>
									</thead>
									<tbody>
										<?php while($dprl = $cprl->fetch_assoc()) { ?>
											<?php
											if ($dprl['status'] == "Normal") {
												$label = "primary";
											} else {
												$label = "danger";
											}
											?>
											<tr>
												<td><?= $dprl['provider_id']; ?></td>
												<td>Top Up <?= $dprl['layanan']; ?> Online 24 Jam</td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!--Filter Kategori Navigasi v2-->
<script>
	$(document).ready(function(){
		$('#filter_kategori').on('change',function(){
			var selected = this.value;

			$('[class="tab-pane active"]').removeClass('active');
			$('#'+selected).addClass('active');
		});
	});
</script>

</div>
<!-- end container -->
</div>
<!-- end content -->

<?php
require '../lib/footer.php';
?>