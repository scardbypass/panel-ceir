<?php
session_start();
require '../../../config.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!isset($_SESSION['user'])) {
        exit("Anda Tidak Memiliki Akses!+");
    }
    if (!isset($_GET['kode_deposit'])) {
        exit("Anda Tidak Memiliki Akses!-");
    } 
    $get_id = $conn->real_escape_string(filter($_GET['kode_deposit']));
    $cek_depo = $conn->query("SELECT * FROM deposit WHERE kode_deposit = '$get_id'");
    while($data_depo = $cek_depo->fetch_assoc()) {
        if ($data_depo['place_from'] == "Website") {
            $icon = "close";
            $label = "danger";
        } else if ($data_depo['place_from'] == "API") {
            $icon = "check";
            $label = "success";
        }	
        if ($data_depo['refund'] == "0") {
            $icon2 = "close";
            $label2 = "danger"; 
        } else if ($data_depo['refund'] == "1") {
            $icon2 = "check";
            $label2 = "success";        
        }
        if ($data_depo['status'] == "Pending") {
            $badge = "warning";
        } else if ($data_depo['status'] == "Partial") {
            $badge = "danger";
        } else if ($data_depo['status'] == "Error") {
            $badge = "danger";    
        } else if ($data_depo['status'] == "Processing") {
            $badge = "info";    
        } else if ($data_depo['status'] == "Success") {
            $badge = "success";    
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST">     
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-box">
                            <tr>
                                <th class="table-detail" width="50%">ID DEPOSIT</th>
                                <td class="table-detail"><?php echo $data_depo['id']; ?></td>
                            </tr>
                            <tr>
                                <th class="table-detail" width="50%">ID INVOICE</th>
                                <td class="table-detail"><?php echo $data_depo['kode_deposit']; ?></td>
                            </tr>
                            <tr>
                                <th class="table-detail">USER</th>
                                <td class="table-detail"><div class="text-purple"><?php echo $data_depo['username']; ?></div></td>
                            </tr>
                            <th class="table-detail">JENIS PAYMENT</th>
                            <td class="table-detail"><?php echo $data_depo['tipe']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">PROVIDER PAYMENT</th>
                            <td class="table-detail"><?php echo $data_depo['provider']; ?></td>
                        </tr>
                        <th class="table-detail">PAYMENT</th>
                        <td class="table-detail"><?php echo $data_depo['payment']; ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">PENGIRIM</th>
                        <td class="table-detail"><?php echo $data_depo['nomor_pengirim']; ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">TUJUAN</th>
                        <td class="table-detail"><?php echo $data_depo['tujuan']; ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">JUMLAH</th>
                        <td class="table-detail">Rp <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">AMOUNT</th>
                        <td class="table-detail">Rp <?php echo number_format($data_depo['get_saldo'],0,',','.'); ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">STATUS</th>
                        <td class="table-detail"><badge class="badge badge-<?php echo $badge; ?>"><?php echo $data_depo['status']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">DATE & TIME</th>
                            <td class="table-detail"><?php echo tanggal_indo($data_depo['date']); ?>, <?php echo $data_depo['time']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">VIA API</th>
                            <td class="table-detail"><span class="badge badge-<?php echo $label; ?>"><i class="mdi mdi-<?php echo $icon; ?>"></i></span></td>
                        </tr>
                    </table>
                </div>

            </form>
        </div>
    </div>       
    <?php 
}
} else {
    exit("Anda Tidak Memiliki Akses!?");
}
