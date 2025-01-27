<!--Viewport -->
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<h3>
	<div style="text-align: center;">
		<a href="../admin-dashboard/metode-deposit"><b>Kembali</b></a><br/>
	</div>
</h3>

<?php
session_start();
require_once ("../config.php");
require_once ("../lib/session_login_admin.php");

$cek_metode = $conn->query("SELECT * FROM metode_depo WHERE keterangan = 'ON'");
	if(mysqli_num_rows($cek_metode) > 0){
		$update_status = mysqli_query($conn, "UPDATE metode_depo SET keterangan = 'OFF' WHERE keterangan = 'ON'");
			if($update_status == TRUE){
			echo"Semua Status Deposit OFF <br/>";
		}else{
			echo"Status Deposit Gagal Diupdate <br/>";
		}
	}else {
		echo"Status Deposit Telah OFF <br/>";
	}
?>