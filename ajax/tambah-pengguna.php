<?php


session_start();
require_once '../config.php';

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_SESSION['user'])) {
		exit("No direct script access allowed!");
	}
	if (!isset($_POST['level'])) {
		exit("No direct script access allowed!");
	}

	$post_level = $conn->real_escape_string($_POST['level']);
	$cek_pendaftaran = $conn->query("SELECT * FROM harga_pendaftaran WHERE level = '$post_level'");

	if (mysqli_num_rows($cek_pendaftaran) == 1) {
		$data_pendaftaran = mysqli_fetch_assoc($cek_pendaftaran);
		?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<b>Level Pengguna : </b> <?php echo $data_pendaftaran['level']; ?><br />
			<b>Biaya Pendaftaran : </b> Rp. <?php echo number_format($data_pendaftaran['harga'],0,',','.'); ?><br />
			<b>Bonus Untuk <?php echo $data_pendaftaran['level']; ?> : </b> Rp. <?php echo number_format($data_pendaftaran['bonus'],0,',','.'); ?> <br />
			<b>Penting : </b>Pendaftaran <?php echo $data_pendaftaran['level']; ?> Mengurangi Saldo Anda Sebesar Rp. <?php echo number_format($data_pendaftaran['harga'],0,',','.'); ?>
		</div>

		<?php
	} else {
		?>
		<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<i class="mdi mdi-block-helper"></i>
			<b>Permintaan Gagal :</b> Fitur Dalam Perbaikan
		</div>
		<?php
	}
}