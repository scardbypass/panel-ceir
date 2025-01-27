<?php
session_start();
require '../../../config.php';
require '../../../lib/session_login_admin.php'; 
if (!isset($_GET['id'])) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    exit(header("Location: ".$config['web']['url']."admin-dashboard/harga-pendaftaran"));
} 
$Target = $conn->real_escape_string(filter($_GET['id']));
$CallDatabase = $conn->query("SELECT * FROM harga_pendaftaran WHERE id = '$Target'");
$ShowData = $CallDatabase->fetch_assoc();
if (mysqli_num_rows($CallDatabase) == 0) {
    $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    exit(header("Location: ".$config['web']['url']."admin-dashboard/harga-pendaftaran"));
}
if ($_GET['id'] == '1') {
    $page = "Member";
} elseif ($_GET['id'] == '2') {
    $page = "Agen";
} elseif ($_GET['id'] == '3') {
    $page = "Reseller";
} elseif ($_GET['id'] == '4') {
    $page = "Admin";
} elseif ($_GET['id'] == '5') {
    $page = "Developers";
}

if (isset($_POST['edit'])) {
    $PostHarga = $conn->real_escape_string($_POST['set_harga']);
    $PostBonus = $conn->real_escape_string($_POST['set_bonus']);
    if ($conn->query("UPDATE harga_pendaftaran SET harga = '$PostHarga', bonus = '$PostBonus' WHERE id = '".$conn->real_escape_string($_GET['id'])."'") == true) {
        $_SESSION['hasil'] = array(
            'alert' => 'success', 
            'judul' => 'Permintaan Berhasil', 
            'pesan' => '
            Harga Pendaftaran Pengguna Telah Berhasil Diubah <br />
            - Harga Pendaftaran : '.$PostHarga.' <br />
            - Bonus Saldo : '.$PostBonus.' <br />                     
            ');
        exit(header("Location: ".$config['web']['url']."admin-dashboard/harga-pendaftaran"));
    } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
    }
}

require '../../../lib/header_admin.php';
?>  
<a href="<?php echo $config['web']['url'];?>admin-dashboard/harga-pendaftaran" class="btn btn-info" style="margin-bottom: 20px;"><i aria-hidden="true" class="fa fa-arrow-left fa-fw"></i> Kembali</a>     
<div class="row">
    <div class="offset-md-2 col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-gears"></i> <?php echo $page; ?> </b></h4>                             
                <form class="form-horizontal" method="POST">        
                    <div class="form-group">
                        <label class="col-md-10 control-label">Harga Pendaftaran</label>
                        <div class="col-md-10">
                            <input type="number" name="set_harga" class="form-control" value="<?php echo $ShowData['harga']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Bonus Saldo</label>
                        <div class="col-md-10">
                            <input type="number" name="set_bonus" class="form-control" value="<?php echo $ShowData['bonus']; ?>">
                        </div>
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