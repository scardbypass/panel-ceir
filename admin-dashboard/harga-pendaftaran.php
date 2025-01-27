<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php'; 
require '../lib/header_admin.php';
?>    

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Informasi Harga Pendaftaran Member Baru</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 header-title"><b><i aria-hidden="true" class="fa fa-gears"></i>    Harga Pendaftaran </b></h4>                             

                <div class="table-responsive">
                    <table class="table table-striped table-bordered nowrap m-0">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Harga Pendaftaran</th>
                                <th>Bonus Saldo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $CekData = $conn->query("SELECT * FROM harga_pendaftaran ORDER BY id ASC"); // edit
                                while ($ShowData = $CekData->fetch_assoc()) {
                                ?>
                                <tr> 
                                    <td><?php echo $ShowData['level']; ?></td>
                                    <td><?php echo $ShowData['harga']; ?></td>
                                    <td><?php echo $ShowData['bonus']; ?></td>
                                    <td><a href="<?php echo $config['web']['url'];?>admin-dashboard/ajax/harga-pendaftaran/edit.php?id=<?php echo $ShowData['id']; ?>" class="btn btn-warning btn-xs"><i aria-hidden="true" class="fa fa-edit"></i></a></td>
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