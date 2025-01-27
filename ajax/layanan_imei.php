<?php


session_start();
require_once '../config.php';

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!isset($_POST['operator'])) {
		exit("No direct script access allowed!");
	}
	if (isset($_POST['operator'])) {
		$post_operator = $conn->real_escape_string($_POST['operator']);
		$cek_layanan = $conn->query("SELECT * FROM layanan_digital WHERE operator =  AND tipe = 'digital' AND status = 'Normal' ORDER BY harga ASC");
		?>
		<option value="0">Pilih Salah Satu</option>
		<?php
		while ($data_layanan = mysqli_fetch_assoc($cek_layanan)) {
			?>
			<option value="<?php echo $data_layanan['provider_id'];?>"><?php echo $data_layanan['layanan'];?></option>
			<?php
		}
	} else {
		?>
		<option value="0">Layanan Tidak Tersedia</option>
		<?php
	}
} else {
	exit("Not Access!");
}