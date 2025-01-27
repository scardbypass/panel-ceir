<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
require '../lib/header.php';

//TOP SOSMED BULANAN
$pembelian_sosmed_month = $conn->query("SELECT SUM(pembelian_sosmed.harga) AS total_pembelian, count(pembelian_sosmed.id) AS tcount, pembelian_sosmed.user, users.nama FROM pembelian_sosmed JOIN users ON pembelian_sosmed.user = users.username WHERE MONTH(pembelian_sosmed.date) = '".date('m')."' AND YEAR(pembelian_sosmed.date) = '".date('Y')."' GROUP BY pembelian_sosmed.user ORDER BY total_pembelian DESC LIMIT 10");

//TOP SOSMED TAHUNAN
$pembelian_sosmed_year = $conn->query("SELECT SUM(pembelian_sosmed.harga) AS total_pembelian, count(pembelian_sosmed.id) AS tcount, pembelian_sosmed.user, users.nama FROM pembelian_sosmed JOIN users ON pembelian_sosmed.user = users.username WHERE YEAR(pembelian_sosmed.date) = '".date('Y')."' GROUP BY pembelian_sosmed.user ORDER BY total_pembelian DESC LIMIT 10");

//TOP PULSA BULANAN
$pembelian_pulsa_month = $conn->query("SELECT SUM(pembelian_pulsa.harga) AS total_pembelian, count(pembelian_pulsa.id) AS tcount, pembelian_pulsa.user, users.nama FROM pembelian_pulsa JOIN users ON pembelian_pulsa.user = users.username WHERE MONTH(pembelian_pulsa.date) = '".date('m')."' AND YEAR(pembelian_pulsa.date) = '".date('Y')."' GROUP BY pembelian_pulsa.user ORDER BY total_pembelian DESC LIMIT 10");

//TOP PULSA TAHUNAN
$pembelian_pulsa_year = $conn->query("SELECT SUM(pembelian_pulsa.harga) AS total_pembelian, count(pembelian_pulsa.id) AS tcount, pembelian_pulsa.user, users.nama FROM pembelian_pulsa JOIN users ON pembelian_pulsa.user = users.username WHERE YEAR(pembelian_pulsa.date) = '".date('Y')."' GROUP BY pembelian_pulsa.user ORDER BY total_pembelian DESC LIMIT 10");

?>

<!--Title-->
<title>Top 10 Pengguna <?php echo $data['short_title']; ?></title>
<meta name="description" content="Top 10 Pengguna <?php echo $data['short_title']; ?>"/>

<!--OG2-->
<meta content="Top 10 Pengguna <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Top 10 Pengguna <?php echo $data['short_title']; ?>" property="og:description"/>
<meta content="Top 10 Pengguna <?php echo $data['short_title']; ?>"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Top 10 Pengguna <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Top 10 Pengguna <?php echo $data['short_title']; ?>" property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row">
	<div class="col-md-12">
		<div class="card-box">
			<center>                                
				<h1 class="m-t-0 text-uppercase text-center header-title"><i class="ti-medall text-primary"></i> <b>Top 10 Pengguna <?php echo $data['short_title']; ?></b><br/>Minggu Ini</h1>
			</center>
			<br/>

			<div class="card">
				<div class="card-body">
					<h4 class="m-t-0 text-uppercase text-center header-title">Transaksi Sosial Media</h4><hr>
					<div class="table-responsive">
						<table class="table table-striped table-bordered nowrap m-0">
							<thead>
								<tr>
									<th>Rank</th>
									<th>Nama</th>
									<th>Jumlah</th>
									<th>Nominal</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = 1;
								while($data = mysqli_fetch_array($pembelian_sosmed_year)) {
									$total_pembelian = number_format($data['total_pembelian'],0,',','.');
									$tcount = number_format($data['tcount'],0,',','.');
									?>
									<tr>
										<td><span class="badge badge-primary"><?php echo $no++; ?></span></td>
										<td><b><?php echo $data['nama']; ?></b></td>
										<td><?php echo $tcount; ?> Pesanan</td>
										<td><span class="btn btn-primary btn-xs">Rp. <?php echo $total_pembelian; ?></span></td>
									</tr>

								<?php } ?>
							</tbody>
						</table>
					</div>
				</ul>
			</div>

			<div class="card">
				<div class="card-body">
					<h4 class="m-t-0 text-uppercase text-center header-title"><i class="ti-medall text-primary"></i> Transaksi Pulsa & PPOB</h4><hr>
					<div class="table-responsive">
						<table class="table table-striped table-bordered nowrap m-0">
							<thead>
								<tr>
									<th>Rank</th>
									<th>Nama</th>
									<th>Jumlah</th>
									<th>Nominal</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$no = 1;
								while($data = mysqli_fetch_array($pembelian_pulsa_year)) {
									$total_pembelian = number_format($data['total_pembelian'],0,',','.');
									$tcount = number_format($data['tcount'],0,',','.');
									?>
									<tr>
										<td><span class="badge badge-primary"><?php echo $no++; ?></span></td>
										<td><b><?php echo $data['nama']; ?></b></td>
										<td><?php echo $tcount; ?> Pesanan</td>
										<td><span class="btn btn-primary btn-xs">Rp. <?php echo $total_pembelian; ?></span></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</ul>
			</div>

		</div> <!-- card-box -->
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->

<?php
require '../lib/footer.php';
?>