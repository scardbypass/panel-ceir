<?php


session_start();
require_once '../config.php';

if (!isset($_SESSION['user'])) {
	die("Anda Tidak Memiliki Akses!");
}

if (isset($_POST['provider'])) {
	$post_provider = $conn->real_escape_string($_POST['provider']);
	$cek_metode = $conn->query("SELECT * FROM metode_depo WHERE id = '$post_provider' ORDER BY id ASC");
	?>
	<option value="0">Pilih Salah Satu</option>
	<?php
	while ($data_metode = $cek_metode->fetch_assoc()) {
		?>
		<option value="<?php echo $data_metode['id'];?>"><?php echo $data_metode['nama'];?></option>
		<?php
	}
} else {
	?>
	<option value="0">Metode Tidak Tersedia</option>
	<?php
}