<?php
require '../config.php';
header('Content-Type: application/json');
if ($maintenance == 1) {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Maintenance'));
	exit(json_encode($hasilnya, JSON_PRETTY_PRINT));
}
if (isset($_POST['api_key']) AND isset($_POST['action'])) {
	$apinya = $conn->real_escape_string($_POST['api_key']);
	$aksinya = $_POST['action'];

	if (!$apinya || !$aksinya) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan Tidak Sesuai'));

	} else {
	    
		$cek_usernya = $conn->query("SELECT * FROM users WHERE api_key = '$apinya'");
		$datanya = $cek_usernya->fetch_assoc();
		
		if (mysqli_num_rows($cek_usernya) == 1) {
			if ($aksinya == 'profile') {
			    	$order_id = $conn->real_escape_string(trim($_POST['api_key']));
	            	$CallUsers = $conn->query("SELECT * FROM users WHERE username = '".$datanya['username']."'");
					$ThisData = mysqli_fetch_array($CallUsers);
			if (mysqli_num_rows($CallUsers) == 0) {
			$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Pengguna Tidak Di Temukan.'));
		} else {
			$hasilnya = array('status' => true, 'data' => array("username" => $ThisData['username'], 'sisa_saldo' => $ThisData['saldo'], 'nama' => $ThisData['nama'], 'email' => $ThisData['email'], 'no_hp' => $ThisData['no_hp'], 'total_pemakaian' => $ThisData['pemakaian_saldo']));
		}
		} else {
			$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Permintaan Tidak Sesuai.'));
		}
		} else {
			$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Api Key Kamu Salah.'));
		}
	}
} else {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Permintaan Tidak Sesuai.'));
}

print(json_encode($hasilnya, JSON_PRETTY_PRINT));