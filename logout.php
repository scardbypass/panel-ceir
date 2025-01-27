<?php
session_start();
require("config.php");

if(isset($_SESSION['user'])) {

   	// Sesi user
   	$sess_username = $_SESSION['user']['username'];

   	// Save log activity
	$insert_user = $conn->query("INSERT INTO log VALUES ('', '".$sess_username."', 'Logout', '".get_client_ip()."', '$date', '$time')");

   	// Delete sesi token
   	$delete_token = $conn->query("DELETE FROM users_token WHERE username = '".$sess_username."'");

	// Hapus semua sesi
	if ($insert_user == true AND $delete_token == true){
		unset($_SESSION['user']);
		session_destroy();
		exit(header("Location: ".$config['web']['url']."home"));
	}
} else {
	unset($_SESSION['user']);
	session_destroy();
	exit(header("Location: ".$config['web']['url']."home"));
}

?>