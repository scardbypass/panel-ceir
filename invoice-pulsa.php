<?php
session_start();
require 'config.php';
require 'lib/session_user.php';
require 'lib/session_login.php';

$cek_depo = $conn->query("SELECT * FROM deposit_tsel WHERE username = '$sess_username' AND status = 'Pending'");
$data_depo = $cek_depo->fetch_assoc();
$depo = $cek_depo->num_rows;

$get_id = $data_depo['kode_deposit'];
$saldo = $data_depo['get_saldo'];
$username = $data_depo['username'];

if ($depo == 0) {  
	$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Deposit Pending Tidak Ditemukan', 'pesan' => 'Pilih Metode Pembayaran Untuk Mengisi Saldo');
	exit(header("Location: pay"));
} else {

	if ($data_depo['status'] == "Pending") {
		$label = "warning";
	} else if ($data_depo['status'] == "Error") {
		$label = "danger";     
	}
	if ($data_depo['status'] == "Pending") {
		$message = "Menunggu Pembayaran";
	} else if ($data_depo['status'] == "Error") {
		$message = "Permintaan Dibatalkan";
	} 

	if (isset($_POST['cancel'])) {

		if ($conn->query("UPDATE deposit_tsel SET status = 'Error'  WHERE kode_deposit = '$get_id'") == true){

			$_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Deposit Dibatalkan', 'pesan' => 'Anda Telah Membatalkan Deposit');
			exit(header("location: pay"));
			}
		}
	}

require 'lib/header.php';
?>

<!--Title-->
<title>Invoice Deposit Pulsa</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="clearfix">
					<div class="text-center">
						<h1 class="m-t-0 text-uppercase text-center header-title">Invoice #<?php echo $data_depo['kode_deposit']; ?></h1>
						<img src="/assets/images/pembayaran/kitadigital-deposit-pulsa.png" alt="Invoice" height="100">
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="float-left mt-3">
							<p><b>Hallo <?php echo $data_user['nama']; ?> (<?php echo $sess_username; ?>)</b></p>
							<p>Silakan lakukan pembayaran dengan detail faktur sebagai berikut: </p>
							<p><b>ID Deposit:</b> #<?php echo $data_depo['kode_deposit']; ?></p>
							<p><b>Tanggal:</b> <?php echo $data_depo['date']; ?></p>
						</div>
					</div><!-- end col -->

					<div class="col-md-6">
						<div class="mt-3 text-md-right">
							<p><b>Metode Pembayaran</b><br/><?php echo $data_depo['payment']; ?></p>
							<p><b class="badge bg-<?php echo $label; ?>"><?php echo $message; ?></b></p>
						</div>
					</div><!-- end col -->
				</div>
				<!-- end row -->

				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-centered mt-3">
								<thead>
									<tr>
										<th>Tujuan</th>
										<th>Jumlah Transfer</th>
										<th>Saldo Diterima</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><font color='#005CAA'><b><?php echo $data_depo['tujuan']; ?></b></font></td>
										<td>Rp. <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?></td>
										<td>Rp. <?php echo number_format($data_depo['get_saldo'],0,',','.'); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="clearfix pt-3">
							<h5 class="text-uppercase header-title">Catatan</h5>
							<p>
								Lakukan pembayaran selambat-lambatnya 3 jam setelah faktur ini dibuat.<br/><br/>Silakan transfer sejumlah <b>Rp. <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?></b>.
							</p>
							<p>
								Jangan <span class="badge badge-danger"><b>Batalkan</b></span> jika transfer sukses. Saldo masuk <span class="badge badge-success"><b>Otomatis</b></span>.
							</p>
							<p>
								Deposit belum masuk hingga 10 menit? Konfirmasi via <a href="/tiket" target="_blank"><b> Tiket</b></a> atau <a href="<?php echo $data['url_whatsapp']; ?>" target="_blank"><b> WA</b></a>.
							</p>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="float-right pt-3">
							<h3>Total <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?> IDR</h3><hr>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<div class="d-print-none m-t-30 m-b-30">
					<div class="text-right">
						<form method="POST">
							<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
							<button class="btn btn-danger" name="cancel"> BATALKAN </button>
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>
<!-- end row -->

</div> <!-- end container-fluid -->
</div>
<!-- end wrapper -->

<?php
require 'lib/footer.php';
?>           