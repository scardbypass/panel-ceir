<?php
session_start();
require '../../../config.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['level'] !== 'Developers') {
        exit("Anda Tidak Memiliki Akses!");
    }
    if (!isset($_GET['data'])) {
        exit("Anda Tidak Memiliki Akses!");
    } 		
    $get_id = $conn->real_escape_string(filter($_GET['data']));
    $cek_depo = $conn->query("SELECT * FROM metode_depo WHERE id = '$get_id'");
    $data_depo = $cek_depo->fetch_assoc();
    if (mysqli_num_rows($cek_depo) == 0) {
        exit("Data Tidak Ditemukan");
    } else {
        ?>										
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST">
                    <div class="form-group">
                        <label class="col-md-2 control-label">ID</label>
                        <div class="col-md-10">
                            <input type="text" name="id" class="form-control" value="<?php echo $data_depo['id']; ?>" readonly>
                        </div>
                    </div>                                      
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tipe Provider</label>
                        <div class="col-md-10">
                            <select class="form-control" name="tipe">
                                <option value="<?php echo $data_depo['tipe']; ?>"><?php echo $data_depo['tipe']; ?></option>
                                <option value="Pulsa">Pulsa</option>
                                <option value="Bank">Bank</option>
                                <option value="EMoney">EMoney</option>
                                <option value="EPayment">EPayment</option>
                                <option value="ECurrency">ECurrency</option>
                            </select>
                        </div>
                    </div>     
                    <div class="form-group">
                        <label class="col-md-2 control-label">Provider</label>
                        <div class="col-md-10">
                            <input type="text" name="provider" class="form-control" value="<?php echo $data_depo['provider']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jalur</label>
                        <div class="col-md-10">
                            <select class="form-control" name="jalur">
                                <option value="<?php echo $data_depo['jalur']; ?>"><?php echo $data_depo['jalur']; ?></option>
                                <option value="Auto">Auto</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-10 control-label">Nama Provider</label>
                        <div class="col-md-10">
                            <input type="text" name="nama" class="form-control" value="<?php echo $data_depo['nama']; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Rate</label>
                        <div class="col-md-10">
                            <input type="text" name="rate" class="form-control" value="<?php echo $data_depo['rate']; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-2 control-label">Keterangan</label>
                        <div class="col-md-10">
                            <input type="text" name="keterangan" class="form-control" value="<?php echo $data_depo['keterangan']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tujuan</label>
                        <div class="col-md-10">
                            <input type="text" name="tujuan" class="form-control" value="<?php echo $data_depo['tujuan']; ?>">
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