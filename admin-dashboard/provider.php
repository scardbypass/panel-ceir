<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';

if (isset($_POST['tambah'])) {
    $PostCode = $conn->real_escape_string(filter($_POST['code']));
    $PostLink = $conn->real_escape_string($_POST['link']);
    $GetKey = $conn->real_escape_string(filter($_POST['api_key']));
    $GetApiID = $conn->real_escape_string(filter($_POST['api_id']));
    $GetSecretKey = $conn->real_escape_string(filter($_POST['secret_key']));

    if (!$PostCode || !$PostLink || !$GetKey) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Kode Provider <br /> - Link Provider <br /> - Api Key');
    } else {
        if ($conn->query("INSERT INTO provider VALUES ('', '$PostCode', '$PostLink', '$GetKey', '$GetApiID', '$GetSecretKey')") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Berhasil Menambahkan Provider Baru');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
        }
    }

} else if (isset($_POST['update'])) {
    $GetID = $conn->real_escape_string($_GET['this_id']);
    $PostCode = $conn->real_escape_string(filter($_POST['code']));
    $PostLink = $conn->real_escape_string($_POST['link']);
    $GetKey = $conn->real_escape_string(filter($_POST['api_key']));
    $GetApiID = $conn->real_escape_string(filter($_POST['api_id']));
    $GetSecretKey = $conn->real_escape_string(filter($_POST['secret_key']));
    if ($conn->query("UPDATE provider SET code = '$PostCode', link = '$PostLink', api_key = '$GetKey', api_id = '$GetApiID', secret_key = '$GetSecretKey' WHERE id = '$GetID'") == true) {
        $_SESSION['hasil'] = array(
            'alert' => 'success', 
            'judul' => 'Permintaan Berhasil', 
            'pesan' => 'Provider Berhasil Di Update');
    } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
    }

} else if (isset($_POST['hapus'])) {
    $GetID = $conn->real_escape_string($_GET['this_id']);
    $CheckData = $conn->query("SELECT * FROM provider WHERE id = '$GetID'");
    if ($CheckData->num_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Data Tidak Di Temukan');
    } else {
        if ($conn->query("DELETE FROM provider WHERE id = '$GetID'") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Permintaan Berhasil', 
                'pesan' => 'Provider Berhasil Di Hapus');
        }
    }
}

?>

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen Provider Server Pusat dan API Key</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-12">
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" method="POST">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Code Provider</label>
                                <div class="col-md-10">
                                    <input type="text" name="code" class="form-control" placeholder="Code Provider">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-md-2 control-label">Link Provider</label>
                                <div class="col-md-10">
                                    <input type="text" name="link" class="form-control" placeholder="Link Provider">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-md-10 control-label">API Key</label>
                                <div class="col-md-10">
                                    <input type="text" name="api_key" class="form-control" placeholder="API Key">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-md-10 control-label">API ID <small class="text-danger">*Kosongkan jika tidak dibutuhkan.</small></label>
                                <div class="col-md-10">
                                    <input type="text" name="api_id" class="form-control" placeholder="API ID">
                                </div>
                            </div>  
                            <div class="form-group">
                                <label class="col-md-10 control-label">Secret Key <small class="text-danger">*Kosongkan jika tidak dibutuhkan.</small></label>
                                <div class="col-md-10">
                                    <input type="text" name="secret_key" class="form-control" placeholder="Secret Key">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="reset" class="btn btn-danger btn-bordred waves-effect"><i aria-hidden="true" class="fa fa-refresh"></i> Reset</button>
                                <button type="submit" class="btn btn-info btn-bordred waves-effect w-md waves-light" name="tambah"><i aria-hidden="true" class="fa fa-add"></i> Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-gears"></i>    Kelola Provider ceirgo</b></h4>        
            <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">

                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th>Kode Provider</th>
                                <th>Link Provider</th>
                                <th>Api Key</th>
                                <th>Api ID</th>
                                <th>Secret Key</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $CallDB_Provider = $conn->query("SELECT * FROM provider ORDER BY id ASC"); // edit
                                while ($ShowData = $CallDB_Provider->fetch_assoc()) {
                            ?>                                        
                        <tr>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?this_id=<?php echo $ShowData['id']; ?>" class="form-inline" role="form" method="POST">
                                <td scope="row"><?php echo $no++; ?></td>
                                <td><input type="text" class="form-control" style="width: 200px;" name="code" value="<?php echo $ShowData['code']; ?>">
                                </td>
                                <td><input type="text" class="form-control" style="width: 300;" name="link" value="<?php echo $ShowData['link']; ?>">
                                </td>
                                <td><input type="text" class="form-control" style="width: 200px;" name="api_key" value="<?php echo $ShowData['api_key']; ?>">
                                </td>
                                <td><input type="text" class="form-control" style="width: 200px;" name="api_id" value="<?php echo $ShowData['api_id']; ?>">
                                </td>
                                <td><input type="text" class="form-control" style="width: 200px;" name="secret_key" value="<?php echo $ShowData['secret_key']; ?>">
                                </td>
                                <td align="center">
                                    <button data-toggle="tooltip" title="Update" type="submit" name="update" class="btn btn-sm btn-bordred btn-info"><i aria-hidden="true" class="fa fa-edit"></i></button>
                                    <button data-toggle="tooltip" title="Hapus" type="submit" name="hapus" class="btn btn-sm btn-bordred btn-danger"><i aria-hidden="true" class="fa fa-trash"></i></button>
                                </td> 
                            </form>
                        </tr>
                        <?php } ?>                                        
                    </tbody>
                </table>
            </div>                                     
        </div>
    </div>
</div>

<?php require '../lib/footer_admin.php'; ?>