<?php
session_start();
require '../config.php';
require '../lib/header.php';
?>
<!--Title-->
<title>Daftar Produk dan Layanan <?php echo $data['short_title']; ?></title>
<meta name="description" content="Berikut adalah daftar layanan jasa kami, dan berbagai produk virtual yang tersedia di <?php echo $data['short_title']; ?>."/>

<!--OG2-->
<meta content="Daftar Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Berikut adalah daftar layanan jasa Sosial Media, Toko Online, Payment Point Online Bank (PPOB), dan berbagai produk virtual yang tersedia di <?php echo $data['short_title']; ?>." property="og:description"/>
<meta content="<?php echo $data['short_title']; ?> - Daftar Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/daftar-harga.png" property="og:image"/>
<meta content="Daftar Produk PPOB dan Layanan SMM <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Berikut adalah daftar layanan jasa Sosial Media, Toko Online, Payment Point Online Bank (PPOB), dan berbagai produk virtual yang tersedia di <?php echo $data['short_title']; ?>." property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/daftar-harga.png" property="twitter:image"/>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body table-responsive">
				<center><h1 class="m-t-0 text-uppercase text-center header-title"><b>Daftar Produk dan Layanan <?php echo $data['short_title']; ?></b></h1>
					<div class="box-content text-center">
                        <a href="/halaman/daftar-harga" title="<?php echo $data['short_title'];?>" alt="<?php echo $data['title'];?>"> <img src="/assets/images/halaman/daftar-harga.png" class="img-responsive" style="max-width: 95%;min-width: 250px;"></a>
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
								<option value="Unlock">Unlock imei</option>
								<option value="Ceir">cek ceir</option>
								<option value="Cekimei">imei apple cek</option>
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
									Platform bisnis yang menyediakan berbagai Layanan Multi Payment, Produk Digital dan Jasa.
									<br/><br/>
									Anda dapat menjadi member, reseller atau agen berbagai jasa pembayaran yang terkoneksi H2H, serta jasa optimasi media sosial dan toko online. Tersedia produk multi provider, dan jasa exchanger.
								</div>
							</div>
						</center>
					</div>
					<div id="Unlock" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN ublock imei</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Unlock'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Jasa <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_digital WHERE kategori = '".$dpr['kode']."' AND tipe = 'Unlock' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Layanan</th>
											<th width="200">Harga/1000</th>
											<th width="200">Harga API/1000</th>
											<th width="200">Min. Order</th>
											<th width="200">Mak. Order</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><?= number_format($dprl['min'],0,',','.'); ?></td>
												<td><?= number_format($dprl['max'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="SosialMedia2" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN SOSIAL MEDIA 2</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan2 WHERE tipe = 'Sosial Media'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Jasa <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_sosmed2 WHERE kategori = '".$dpr['kode']."' AND tipe = 'Sosial Media' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Layanan</th>
											<th width="200">Harga/1000</th>
											<th width="200">Harga API/1000</th>
											<th width="200">Min. Order</th>
											<th width="200">Mak. Order</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><?= number_format($dprl['min'],0,',','.'); ?></td>
												<td><?= number_format($dprl['max'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="SosialMedia3" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>LAYANAN SOSIAL MEDIA 3</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan3 WHERE tipe = 'Sosial Media'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">Jasa <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_sosmed3 WHERE kategori = '".$dpr['kode']."' AND tipe = 'Sosial Media' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Layanan</th>
											<th width="200">Harga/1000</th>
											<th width="200">Harga API/1000</th>
											<th width="200">Min. Order</th>
											<th width="200">Mak. Order</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><?= number_format($dprl['min'],0,',','.'); ?></td>
												<td><?= number_format($dprl['max'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="AkunPremium" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>AKUN PREMIUM</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Digital'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">AKUN <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_digital WHERE operator = '".$dpr['kode']."' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="PaketInternet" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK PAKET INTERNET</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Data'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PAKET <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Data' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="PulsaReguler" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK PULSA REGULER</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Pulsa'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PULSA <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Pulsa' AND jenis = 'Umum' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="PulsaTransfer" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK PULSA TRANSFER</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Pulsa'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PULSA <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Pulsa' AND jenis = 'Pulsa Transfer' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="TeleponSMS" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK TELEPON & SMS</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Paket SMS & Telpon'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">PAKET <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'Paket SMS & Telpon' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>  
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="SaldoEMoney" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK E-MONEY</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'E-Money'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">TOP UP <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'E-Money' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>
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
												<td><?= $dprl['layanan']; ?></td>
												<td>Rp. <?= number_format($dprl['harga'],0,',','.'); ?></td>
												<td>Rp. <?= number_format($dprl['harga_api'],0,',','.'); ?></td>
												<td><label class="btn btn-xs btn-<?= $label; ?>"><?= $dprl['status']; ?></label></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						<?php } ?>
					</div>
					<div id="TokenPLN" class="tab-pane">
						<center>
							<h2 class="text-primary header-title"><b>PRODUK TOKEN PLN</b></h2>
						</center>
						<?php $cpr = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'PLN'"); ?>
						<?php while ($dpr = $cpr->fetch_assoc()) { ?>
							<br/><center><h3 class="header-title">TOP UP <?= $dpr['nama']; ?></h3></center>
							<?php $cprl = $conn->query("SELECT * FROM layanan_pulsa WHERE operator = '".$dpr['kode']."' AND tipe = 'PLN' ORDER BY harga ASC"); ?>
							<div class="table-responsive">
								<table class="table table-bordered table-hovered mb-1">
									<thead>
										<tr>
											<th width="200">ID</th>
											<th width="500">Produk</th>
											<th width="200">Harga</th>
											<th width="200">Harga API</th>
											<th width="100">Status</th>
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
												<