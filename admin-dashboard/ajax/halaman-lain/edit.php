<?php
session_start();
require '../../../config.php';
require '../../../lib/session_login_admin.php'; 
if (!isset($_GET['id'])) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    exit(header("Location: ".$config['web']['url']."admin-dashboard/halaman-lain"));
} 
$Target = $conn->real_escape_string(filter($_GET['id']));
$CallDatabase = $conn->query("SELECT * FROM halaman WHERE id = '$Target'");
$ShowData = $CallDatabase->fetch_assoc();
if (mysqli_num_rows($CallDatabase) == 0) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    exit(header("Location: ".$config['web']['url']."admin-dashboard/halaman-lain"));
}
if ($_GET['id'] == '1') {
    $page = "Tentang Kami";
} elseif ($_GET['id'] == '2') {
    $page = "Platform Aplikasi";
} elseif ($_GET['id'] == '3') {
    $page = "Produk dan Layanan";
} elseif ($_GET['id'] == '4') {
    $page = "Mitra dan Jaringan";
} elseif ($_GET['id'] == '5') {
    $page = "Dukungan Teknologi";
} elseif ($_GET['id'] == '6') {
    $page = "Dukungan Pembayaran";
}

if (isset($_POST['edit'])) {
    $PostKonten = $conn->real_escape_string($_POST['set_konten']);
    if ($conn->query("UPDATE halaman SET konten = '$PostKonten' WHERE id = '".$conn->real_escape_string($_GET['id'])."'") == true) {
        $_SESSION['hasil'] = array(
            'alert' => 'success', 
            'judul' => 'Permintaan Berhasil', 
            'pesan' => '
            Isi Konten '.$page.' Berhasil Di Update <br />                  
            ');
        exit(header("Location: ".$config['web']['url']."admin-dashboard/halaman-lain"));
    } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
    }
}
require '../../../lib/header_admin.php';
?>  
<a href="<?php echo $config['web']['url'];?>admin-dashboard/halaman-lain" class="btn btn-info" style="margin-bottom: 20px;"><i aria-hidden="true" class="fa fa-arrow-left fa-fw"></i> Kembali</a>     
<div class="row">
    <div class="offset-md-2 col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-gears"></i> <?php echo $page; ?> </b></h4>                             
                <form class="form-horizontal" method="POST">        
                    <div class="form-group">
                        <div class="col-lg-12"><textarea class="form-control" name="set_konten" rows="15"><?php echo $ShowData['konten'] ?></textarea></div>
                    </div>                       
                    <div class="pull-right">
                        <button type="submit" class="btn btn-warning waves-effect w-md waves-light" name="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Update</button>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require '../../../lib/footer_admin.php';
?>