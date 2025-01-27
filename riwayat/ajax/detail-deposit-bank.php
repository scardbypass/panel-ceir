<?php
session_start();
require '../../config.php';
require '../../lib/session_user.php';
if (isset($_POST['id'])) {
    $post_id = $conn->real_escape_string($_POST['id']);
    $cek_depo = $conn->query("SELECT * FROM deposit_bank WHERE id = '$post_id'");
    while ($data_depo = mysqli_fetch_assoc($cek_depo)) {
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
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-box">
                <tr>
                    <th class="table-detail" width="50%">Deposit ID</th>
                    <td class="table-detail text-primary"><?php echo $data_depo['kode_deposit']; ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Pengguna</th>
                    <td class="table-detail"><?php echo $data_depo['username']; ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Pembayaran</th>
                    <td class="table-detail"><?php echo $data_depo['payment']; ?><br /><?php echo $data_depo['tujuan']; ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Pengirim</th>
                    <td class="table-detail"><?php echo $data_depo['nomor_pengirim']; ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Jumlah Transfer</th>
                    <td class="table-detail">Rp <?php echo number_format($data_depo['jumlah_transfer'],0,',','.'); ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Saldo Yang Di Dapat</th>
                    <td class="table-detail">Rp <?php echo number_format($data_depo['get_saldo'],0,',','.'); ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Waktu</th>
                    <td class="table-detail"><?php echo tanggal_indo($data_depo['date']).','.tanggal_indo($data_depo['time']); ?></td>
                </tr>
            </tr>
            <tr>
                <th class="table-detail">Status</th>
                <td class="table-detail"><badge class="badge badge-<?php echo $badge; ?>"><?php echo $data_depo['status']; ?></td>
                </tr>
            </table>
        </div>
        <?php
    } 
}