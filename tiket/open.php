<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
$post_idtarget = $conn->real_escape_string($_GET['id']);
require '../lib/session_login.php';
$cek_tiket = $conn->query("SELECT * FROM tiket WHERE id = '$post_idtarget' AND user = '$sess_username'");
$data_tiket = $cek_tiket->fetch_assoc();

if (mysqli_num_rows($cek_tiket) == 0) {
	$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Tiket Tidak Ditemukan');
	exit(header("Location: ".$config['web']['url']."tiket/"));
} else {
	$conn->query("UPDATE tiket SET this_user = '1' WHERE id = '$post_idtarget'");
	if (isset($_POST['balas'])) {
		$pesan = $conn->real_escape_string(trim(filter($_POST['pesan'])));
		if ($data_tiket['status'] == "Closed") {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Tiket Telah Ditutup. Silakan Membuat Tiket Baru');
			exit(header("Location: ".$config['web']['url']."tiket"));
		} else if (!$pesan) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Pesan');
		} else if (strlen($pesan) > 5000) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Maksimal Isi Pesan Hanya 5000 Karakter');
		} else {
			$update_terakhir = "$date $time";
			$insert_tiket = $conn->query("INSERT INTO pesan_tiket VALUES ('', '$post_idtarget', 'Member', '$sess_username', '$pesan',  '$date', '$time','$update_terakhir')");
			$update_tiket = $conn->query("UPDATE tiket SET update_terakhir = '$update_terakhir', status = 'Waiting', this_user = '0' WHERE id = '$post_idtarget'");
			if ($insert_tiket == TRUE) {
				$_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Tiket Terkirim', 'pesan' => 'Kami Akan Segera Merespon..');
			} else {
				$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pengiriman Gagal', 'pesan' => 'Tiket Tidak Valid, Mohon Membuat Tiket Baru');
			}
		}
	}
}
require '../lib/header.php';
?>

<!--Title-->
<title>Tiket Bantuan</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<!-- end row -->
<div class="row">
	<div class="offset-md-2 col-md-8">
		<div class="card">
			<div class="card-body">
				<h4 class="header-title mb-3"><i class="mdi mdi-email-open text-primary"></i> <?php echo $data_tiket['subjek']; ?></h4>
				<div style="max-height: 400px; overflow: auto;">
					<div class="alert alert-info text-right">
						<b><?php echo $data_tiket['user']; ?></b><br/>
						<span style="font-size: 16px;">
							<?php echo nl2br($data_tiket['pesan']); ?>
						</span><br/>
						<i style="font-size: 13px;">
							<?php echo tanggal_indo($data_tiket['date']); ?>, <?php echo $data_tiket['time']; ?>
						</i>
					</div>
					<?php
						$cek_pesan = $conn->query("SELECT * FROM pesan_tiket WHERE id_tiket = '$post_idtarget'");
						while ($data_pesan = $cek_pesan->fetch_assoc()) {
							if ($data_pesan['pengirim'] == "Admin") {
								$alert = "success";
								$text = "text-left";
								$pengirim = "Customer Services";
							} else {
								$alert = "info";
								$text = "text-right";
								$pengirim = $data_pesan['user'];
							}
							?>
							<div class="alert alert-<?php echo $alert; ?> <?php echo $text; ?>">
								<b><?php echo $pengirim; ?></b><br/>
								<span style="font-size: 16px;">
									<?php echo nl2br($data_pesan['pesan']); ?>
								</span><br/>
								<i style="font-size: 13px;">
									<?php echo tanggal_indo($data_pesan['date']); ?>, <?php echo $data_tiket['time']; ?>
								</i>
							</div>
							<?php
							}
					?>
				</div>
				<hr>
				<center>
					<i class="fas fa-spinner fa-pulse"></i>
				</center>
				<form class="form-horizontal" role="form" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="pesan" class="form-control" placeholder="Pesan" rows="3"></textarea>
						</div>
					</div>
					<div class="card-footer text-muted">
						<a href="<?php echo $config['web']['url'];?>tiket/" class="btn btn-danger"><i  class="mdi mdi-arrow-left-bold"></i> Kembali</a>
						<button type="submit" class="pull-right btn btn-success" name="balas"><i  class="fa fa-send"></i> Balas</button>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

<?php 
include '../lib/footer.php';
?>