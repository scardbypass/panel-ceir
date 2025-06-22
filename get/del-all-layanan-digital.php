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

$delet_kategori_layanan = mysqli_query($conn, "DELETE FROM kategori_layanan WHERE tipe = 'Digital'");
if($delet_kategori_layanan == TRUE){
	echo"Kategori ceirgoL Dihapus <br/>";
}else{
	echo"Kategori ceirgo Gagal Dihapus <br/>";
}

$delet_layanan_pulsa = mysqli_query($conn, "DELETE FROM layanan_pulsa WHERE provider = 'ceirgo'");
if($delet_layanan_pulsa == TRUE){
	echo"Produk ceirgo Dihapus <br/>";
}else{
	echo"Produk ceirgo Gagal Dihapus <br/>";
}

?> 