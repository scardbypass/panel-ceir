<?php
session_start();
require '../../../config.php';
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!isset($_SESSION['user'])) {
        exit("Anda Tidak Memiliki Akses!+");
    }
    if (!isset($_GET['oid'])) {
        exit("Anda Tidak Memiliki Akses!-");
    } 
    $get_oid = $conn->real_escape_string(filter($_GET['oid']));
    $cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE oid = '$get_oid'");
    while($data_pesanan = $cek_pesanan->fetch_assoc()) {
        if ($data_pesanan['place_from'] == "Website") {
            $icon = "close";
            $label = "danger";
        } else if ($data_pesanan['place_from'] == "API") {
            $icon = "check";
            $label = "success";
        }	
        if ($data_pesanan['refund'] == "0") {
            $icon2 = "close";
            $label2 = "danger"; 
        } else if ($data_pesanan['refund'] == "1") {
            $icon2 = "check";
            $label2 = "success";        
        }
        if ($data_pesanan['status'] == "Pending") {
            $badge = "warning";
        } else if ($data_pesanan['status'] == "Partial") {
            $badge = "danger";
        } else if ($data_pesanan['status'] == "Error") {
            $badge = "danger";    
        } else if ($data_pesanan['status'] == "Processing") {
            $badge = "info";    
        } else if ($data_pesanan['status'] == "Success") {
            $badge = "success";    
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST">     
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-box">
                            <tr>
                                <th class="table-detail" width="50%">ID ORDER</th>
                                <td class="table-detail"><?php echo $data_pesanan['oid']; ?></td>
                            </tr>
                            <tr>
                                <th class="table-detail" width="50%">ID PROVIDER</th>
                                <td class="table-detail"><?php echo $data_pesanan['provider_oid']; ?></td>
                            </tr>
                            <tr>
                                <th class="table-detail">USER</th>
                                <td class="table-detail"><div class="text-purple"><?php echo $data_pesanan['user']; ?></div></td>
                            </tr>
                            <th class="table-detail">SERVICE</th>
                            <td class="table-detail"><?php echo $data_pesanan['layanan']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">TARGET</th>
                            <td class="table-detail"><?php echo $data_pesanan['target']; ?></td>
                        </tr>
                        <th class="table-detail">CATATAN/SN</th>
                        <td class="table-detail"><?php echo $data_pesanan['keterangan']; ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">HARGA</th>
                        <td class="table-detail">Rp <?php echo number_format($data_pesanan['harga'],0,',','.'); ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">PROFIT</th>
                        <td class="table-detail">Rp <?php echo number_format($data_pesanan['profit'],0,',','.'); ?></td>
                    </tr>
                    <tr>
                        <th class="table-detail">STATUS</th>
                        <td class="table-detail"><badge class="badge badge-<?php echo $badge; ?>"><?php echo $data_pesanan['status']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">PROVIDER</th>
                            <td class="table-detail"><?php echo $data_pesanan['provider']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">DATE & TIME</th>
                            <td class="table-detail"><?php echo tanggal_indo($data_pesanan['date']); ?>, <?php echo $data_pesanan['time']; ?></td>
                        </tr>
                        <tr>
                            <th class="table-detail">REFFUND</th>
                            <td class="table-detail"><span class="badge badge-<?php echo $label2; ?>"><i class="mdi mdi-<?php echo $icon2; ?>"></i></span></td>
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
