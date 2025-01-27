<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php';

        if (isset($_POST['ubah'])) {
            $id = $conn->real_escape_string($_GET['this_id']);
            $kategori = $conn->real_escape_string($_POST['kategori']);
            $tipe = $conn->real_escape_string($_POST['tipe']);
            $harga = $conn->real_escape_string(filter($_POST['rate']));

            $cek_data = $conn->query("SELECT * FROM setting_profit WHERE id = '$id'");

            if ($cek_data->num_rows == 0) {
                $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Data Tidak Di Temukan.');
            } else {

                if ($conn->query("UPDATE setting_profit SET kategori = '$kategori', tipe = '$tipe', harga = '$harga' WHERE id = '$id'") == true) {
                    $_SESSION['hasil'] = array('alert' => 'success', 'pesan' => 'Sip, Keuntungan Harga Layanan Berhasil Di Ubah.');
                } else {
                    $_SESSION['hasil'] = array('alert' => 'danger', 'pesan' => 'Ups, Gagal! Sistem Kami Sedang Mengalami Gangguan.<script>swal("Ups Gagal!", "Sistem Kami Sedang Mengalami Gangguan.", "error");</script>');
                }
            }
        }

        require '../lib/header_admin.php';

?>
 </br>
        </br>
            </br>
                </br>

        <!-- Start Content -->
        <div class="kt-container kt-grid__item kt-grid__item--fluid">

        <!-- Start Page Data Setting Profit Month -->
        <div class="row">
	        <div class="col-lg-12">
		        <div class="kt-portlet">
			        <div class="kt-portlet__head">
				        <div class="kt-portlet__head-label">
					        <h3 class="kt-portlet__head-title">
					            <i class="fa fa-money-bill text-primary"></i>
					            Daftar Keuntungan Layanan (Profit)
					        </h3>
				        </div>
			        </div>
			        <div class="kt-portlet__body">
                    <?php
                    if (isset($_SESSION['hasil'])) {
                    ?>
                    <div class="alert alert-<?php echo $_SESSION['hasil']['alert'] ?> alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $_SESSION['hasil']['pesan'] ?>
                    </div>
                    <?php
                    unset($_SESSION['hasil']);
                    }
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Jumlah Keuntungan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$no = 1;
    $CallDB_Provider = $conn->query("SELECT * FROM setting_profit ORDER BY id DESC"); // edit
    while ($ShowData = $CallDB_Provider->fetch_assoc()) {
?>                                        
                                <tr>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>?this_id=<?php echo $ShowData['id']; ?>" class="form-inline" role="form" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                                    <td><input type="text" class="form-control" style="width: 200px;" name="kategori" value="<?php echo $ShowData['kategori']; ?>" readonly></td>
                                    <td><input type="text" class="form-control" style="width: 200px;" name="tipe" value="<?php echo $ShowData['tipe']; ?>" readonly></td>
                                    <td><input type="text" class="form-control" style="width: 200px;" name="rate" value="<?php echo $ShowData['harga']; ?>"></td>
                                    <td align="center">
                                        <button data-toggle="tooltip" title="Update" type="submit" name="ubah" class="btn btn-sm btn-bordred btn-warning"><i aria-hidden="true" class="fa fa-edit" title="Ubah"></i></button>
                                    </td>
                                    </form>
                                </tr>
<?php } ?>                                        
                            </tbody>
                        </table>
                        
                         <div class="col-lg-3">
                 <table class="table table-striped table-bordered nowrap m-0">
                        <tbody>
                            <h4>Perhitungan Jika Profit Dalam Bentuk Persen (%)</h4>
                            <tr>
                                <th width="200">Nilai</th>
                                <td>Jumlah Profit</td>
                            </tr>
                            <tr>
                                <th width="200">1.05</th>
                                <td>5%</td>
                            </tr>
                            <tr>
                                <th>1.1</th>
                                <td>10%</td>
                            </tr>
                            <tr>
                                <th>1.15</th>
                                <td>15%</td>
                            </tr>
                            <tr>
                                <th>1.2</th>
                                <td>20%</td>
                            </tr>

                    `   </tbody>
                    </table> 
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Data Setting Profit Month -->

        </div>
        <!-- End Content -->

<?php
require '../lib/footer_admin.php';
?>