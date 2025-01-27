<?php
if (!isset($_SESSION['user'])) {
	$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Diperlukan Autentikasi', 'pesan' => 'Silakan Login Terlebih Dahulu');
	session_destroy();
	exit(header("Location: ".$config['web']['url']."auth/login"));
}

?>