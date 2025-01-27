<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';
?>     
  
<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Pengaturan Halaman Website Lainnya</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-gears"></i>    Pengaturan Halaman </b></h4>                             

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <thead>
                            <tr>
                                <th>Halaman</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $CekData = $conn->query("SELECT * FROM halaman ORDER BY id ASC"); // edit
                                while ($ShowData = $CekData->fetch_assoc()) {
                                    if ($ShowData['id'] == "1") {
                                        $IniHalaman = "Tentang Kami";
                                    } else if ($ShowData['id'] == "2") {
                                        $IniHalaman = "Platform Aplikasi";
                                    } else if ($ShowData['id'] == "3") {
                                        $IniHalaman = "Produk dan Layanan";
                                    } else if ($ShowData['id'] == "4") {
                                        $IniHalaman = "Mitra dan Jaringan";
                                    } else if ($ShowData['id'] == "5") {
                                        $IniHalaman = "Dukungan Teknologi";
                                    } else if ($ShowData['id'] == "6") {
                                        $IniHalaman = "Dukungan Pembayaran";
                                    }
                                ?>
                            <tr> 
                                <td><?php echo $IniHalaman; ?></td>
                                <td><a href="<?php echo $config['web']['url'];?>admin-dashboard/ajax/halaman-lain/edit.php?id=<?php echo $ShowData['id']; ?>" class="btn btn-warning btn-xs"><i aria-hidden="true" class="fa fa-edit"></i></a></td>                               
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../lib/footer_admin.php';
?>