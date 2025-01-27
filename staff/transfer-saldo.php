<?php 
session_start();
require '../config.php';
require '../lib/session_login.php';
require '../lib/session_user.php';
if ($data_user['level'] == 'Member') {
	$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Gagal!', 'pesan' => 'Dilarang Mengakses!.');
	exit(header("Location: ".$config['web']['url']));
}

if (isset($_POST['transfer'])) {
	$tujuan = $conn->real_escape_string(filter($_POST['tujuan']));
	$jumlah = $conn->real_escape_string(filter($_POST['jumlah']));

	$cek_tujuan = $conn->query("SELECT * FROM users WHERE username = '$tujuan'");
	$cek_tujuan_rows = mysqli_num_rows($cek_tujuan);

	if (!$tujuan || !$jumlah) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Username Tujuan <br /> - Jumlah Transfer');
	} else if ($cek_tujuan_rows == 0 ) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Username Tujuan Tidak Ditemukan');

	} else if (strtolower($sess_username) == $tujuan ) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Tidak Bisa Transfer ke Username Tujuan');

	//validasi huruf kecil saja
	} else if (!preg_match("/^[a-z 0-9]*$/",$tujuan)) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Format Tujuan Salah, Masukkan Huruf Kecil!');

	} else if ($jumlah < 5000 ) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Minimal Transfer Saldo Sebesar Rp 5.000');
	} else if ($data_user['saldo'] < $jumlah ) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Saldo Tidak Mencukupi');	
	} else {
		if ($conn->query("UPDATE users set saldo = saldo + $jumlah WHERE username = '$tujuan'") == true) {
			$conn->query("UPDATE users set saldo = saldo - $jumlah, pemakaian_saldo = pemakaian_saldo + $jumlah  WHERE username = '$sess_username'");
			$conn->query("INSERT INTO history_saldo VALUES ('', '$sess_username', 'Pengurangan Saldo', '$jumlah', 'Transfer Saldo Kepada $tujuan Sejumlah $jumlah ', '$date', '$time')");	
			$conn->query("INSERT INTO history_saldo VALUES ('', '$tujuan', 'Penambahan Saldo', '$jumlah', 'Mendapatkan Transfer Saldo Dari $sess_username Sejumlah $jumlah', '$date', '$time')");
			$conn->query("INSERT INTO riwayat_transfer VALUES ('', '$sess_username', '$tujuan', '$jumlah','$date', '$time')");
			$_SESSION['hasil'] = array(
				'alert' => 'success', 
				'judul' => 'Permintaan Berhasil', 
				'pesan' => '
				Transfer Saldo Berhasil <br />
				<b>Tujuan</b> : '.$tujuan.' <br />
				<b>Jumlah</b> : Rp '.$jumlah.'
				');
		} else {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Gagal');	
		}
	}
}
require '../lib/header.php';
?>

<!--Title-->
<title>Transfer Saldo</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="offset-lg-2 col-lg-8">
		<div class="card">
			<div class="card-body">
				<h4 class="text-uppercase text-center header-title"><i class="mdi mdi-account-switch text-primary"></i> Transfer Saldo</h4><hr>
				<form class="form-horizontal" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
					<div class="form-group">
						<label class="col-md-12 control-label">Username Tujuan</label>
						<div class="col-md-12">
							<input type="text" name="tujuan" class="form-control" placeholder="Username Tujuan">
						</div>
					</div>	                              										
					<div class="form-group">
						<label class="col-md-12 control-label">Jumlah Transfer</label>
						<div class="col-md-12">
							<input type="number" name="jumlah" class="form-control" placeholder="Jumlah Transfer">
						</div>
					</div>										
					<div class="col-md-12">
						<button type="submit" class="pull-right btn btn-block btn--md btn-primary waves-effect waves-light" name="transfer"><i class="mdi mdi-checkbox-marked-circle"></i> Transfer Saldo</button>
					</div> 
				</form>
			</div>
		</div>
	</div> <!-- end col -->
</div>
<?php
require '../lib/footer.php';