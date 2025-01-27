<?php
require("../config.php");
$cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE status IN ('Error') AND refund = '0'");

if (mysqli_num_rows($cek_pesanan) == 0) {
	die("Status Error/Partial Tidak Ditemukan");
} else {
	while($data_pesanan = mysqli_fetch_assoc($cek_pesanan)) {

		$harga = $data_pesanan['harga'];
		$profit = $data_pesanan['profit'];

		$update_user = $conn->query("UPDATE users SET pemakaian_saldo = pemakaian_saldo - $harga WHERE user = '".$data_pesanan['user']."'");
		$update_user = $conn->query("UPDATE users SET saldo = saldo + $harga WHERE username = '".$data_pesanan['user']."'");
		$update_order = $conn->query("INSERT INTO history_saldo VALUES ('', '".$data_pesanan['user']."', 'Penambahan Saldo', '$harga', 'Pengembalian Dana. Order ID ".$data_pesanan['oid']."', '$date', '$time')");
		$update_order = $conn->query("UPDATE pembelian_digital SET refund = '1', profit = profit - $profit  WHERE oid = '".$data_pesanan['oid']."'");
		if ($update_order AND $update_user == TRUE) {
			echo "Dikembalikan: Rp $harga <br/>
			Order ID: ".$data_pesanan['oid']." <br/>
			Untuk: ".$data_pesanan['user']." <br/><br/>";
		} else {
			echo "Error database";
		}
	}
}

?>