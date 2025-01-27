<?php
session_start();
require '../../../config.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!isset($_SESSION['user'])) {
        exit("Anda Tidak Memiliki Akses!");
    }
    if (!isset($_GET['id_layanan'])) {
        exit("Anda Tidak Memiliki Akses!");
    } 		
    $get_idlayanan = $conn->real_escape_string(filter($_GET['id_layanan']));
    $cek_layanan = $conn->query("SELECT * FROM layanan_digital WHERE service_id = '$get_idlayanan'");
    $data_layanan = $cek_layanan->fetch_assoc();
    if (mysqli_num_rows($cek_layanan) == 0) {
        exit("Data Tidak Ditemukan");
    } else {
        ?>										
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Service ID</label>
                        <div class="col-md-10">
                            <input type="text" name="sid" class="form-control" value="<?php echo $data_layanan['service_id']; ?>" readonly> 
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Provider ID</label>
                        <div class="col-md-10">
                            <input type="text" name="pid" class="form-control" value="<?php echo $data_layanan['provider_id']; ?>">
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-2 control-label">Operator</label>
                        <div class="col-md-10">
                            <select class="form-control" name="operator">
                                <option value="<?php echo $data_layanan['operator']; ?>"><?php echo $data_layanan['operator']; ?></option>
                                <?php
                                $cek_kategori = $conn->query("SELECT * FROM kategori_layanan WHERE tipe = 'Digital' ORDER BY nama ASC");
                                while ($data_kategori = $cek_kategori->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $data_kategori['kode']; ?>"><?php echo $data_kategori['nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Layanan</label>
                        <div class="col-md-10">
                            <input type="text" name="layanan" class="form-control" value="<?php echo $data_layanan['layanan']; ?>">
                        </div>
                    </div>                                                       
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Web</label>
                        <div class="col-md-10">
                            <input type="text" name="harga" class="form-control" value="<?php echo $data_layanan['harga']; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Api</label>
                        <div class="col-md-10">
                            <input type="text" name="harga_api" class="form-control" value="<?php echo $data_layanan['harga_api']; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Profit</label>
                        <div class="col-md-10">
                            <input type="text" name="profit" class="form-control"value="<?php echo $data_layanan['profit']; ?>">
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-2 control-label">Status</label>
                        <div class="col-md-10">
                            <select class="form-control" name="status">
                                <option value="<?php echo $data_layanan['status']; ?>"><?php echo $data_layanan['status']; ?></option>
                                <option value="Normal">Normal</option>
                                <option value="Gangguan">Gangguan</option>
                            </select>
                        </div>
                    </div>      
                    <div class="form-group">
                        <label class="col-md-2 control-label">Provider</label>
                        <div class="col-md-10">
                            <select class="form-control" name="provider">
                                <option value="<?php echo $data_layanan['provider']; ?>"><?php echo $data_layanan['provider']; ?></option>
                                <?php
                                $cek_provider = $conn->query("SELECT * FROM provider ORDER BY id ASC");
                                while ($data_provider = $cek_provider->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $data_provider['code']; ?>"><?php echo $data_provider['code']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>                                                                        
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning btn-bordred waves-effect w-md waves-light" name="edit"><i aria-hidden="true" class="fa fa-pencil"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
        <?php 
    }
} else {
    exit("Anda Tidak Memiliki Akses!");
}