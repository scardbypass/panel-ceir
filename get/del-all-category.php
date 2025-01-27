<!--Viewport -->
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<h3>
	<div style="text-align: center;">
		<a href="../admin-dashboard/action-provider"><b>Kembali</b></a><br/>
	</div>
</h3>

<?php
session_start();
require_once ("../config.php");
require_once ("../lib/session_login_admin.php");

$delet_kategori_layanan = mysqli_query($conn, "DELETE FROM kategori_layanan");
if($delet_kategori_layanan == TRUE){
	echo"Kategori Dihapus <br/>";
}else{
	echo"Kategori Gagal Dihapus <br/>";
}

?> 