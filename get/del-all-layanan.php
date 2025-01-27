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

//DELL ALL KATEGORI
$delet_kategori_layanan = mysqli_query($conn, "DELETE FROM kategori_layanan");
if($delet_kategori_layanan == TRUE){
    echo"Kategori Dihapus <br/>";
}else{
    echo"Kategori Gagal Dihapus <br/>";
}

//DELL ALL LAYANAN DIGITAL
$delet_layanan_digital = mysqli_query($conn, "DELETE FROM layanan_digital");
if($delet_layanan_digital == TRUE){
    echo"Layanan Digital Dihapus <br/>";
}else{
    echo"Layanan Digital Gagal Dihapus <br/>";
}

?>