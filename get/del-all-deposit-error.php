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

//Dell deposit Bank
$delet_deposit_bank = mysqli_query($conn, "DELETE FROM deposit_bank WHERE status = 'Error'");
if($delet_deposit_bank == TRUE){
	echo"Deposit Bank Error Dihapus <br/>";
}else{
	echo"Deposit Bank Error Gagal Dihapus <br/>";
}

//Dell deposit Emoney
$delet_deposit_emoney = mysqli_query($conn, "DELETE FROM deposit_emoney WHERE status = 'Error'");
if($delet_deposit_emoney == TRUE){
	echo"Deposit Emoney Error Dihapus <br/>";
}else{
	echo"Deposit Emoney Error Gagal Dihapus <br/>";
}

//Dell deposit Epayment
$delet_deposit_epayment = mysqli_query($conn, "DELETE FROM deposit_epayment WHERE status = 'Error'");
if($delet_deposit_epayment == TRUE){
	echo"Deposit Epayment Error Dihapus <br/>";
}else{
	echo"Deposit Epayment Error Gagal Dihapus <br/>";
}

//Dell deposit Tsel
$delet_deposit_tsel = mysqli_query($conn, "DELETE FROM deposit_tsel WHERE status = 'Error'");
if($delet_deposit_tsel == TRUE){
	echo"Deposit Tsel Error Dihapus <br/>";
}else{
	echo"Deposit Tsel Error Gagal Dihapus <br/>";
}

//Dell deposit Lain
$delet_deposit = mysqli_query($conn, "DELETE FROM deposit WHERE status = 'Error'");
if($delet_deposit == TRUE){
	echo"Deposit Lain Error Dihapus <br/>";
}else{
	echo"Deposit Lain Error Gagal Dihapus <br/>";
}

?>