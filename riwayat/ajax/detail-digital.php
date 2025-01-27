<?php
session_start();
require '../../config.php';
require '../../lib/session_user.php';
if (isset($_POST['id'])) {
    $post_id = $conn->real_escape_string($_POST['id']);
    $cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE id = '$post_id'");
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
        if($data_user['level'] == 'Admin' || $data_user['level'] == 'Developer'){
        ?>
        <form action="" method="POST">
         <input type="hidden" name="csrf_token" value="<?php echo$_SESSION['token']; ?>">
         <input type="hidden" name="i" value="<?php echo $data_pesanan['id']; ?>">   
         <div class="table-responsive">
            <table class="table table-striped table-bordered table-box">
                <tr>
                    <th class="table-detail" width="50%">Order ID</th>
                    <td class="table-detail"><?php echo $data_pesanan['oid']; ?></td>
                </tr>
                <tr>
                    <th class="table-detail">Pengguna</th>
                    <td class="table-detail"><?php echo $data_pesanan['user']; ?></td>
                </tr>
                <th class="table-detail">Layanan</th>
                <td class="table-detail"><?php echo $data_pesanan['layanan']; ?></td>
            </tr>
            <tr>
                <th class="table-detail">Target</th>
                <td class="table-detail"><?php echo $data_pesanan['target']; ?></td>
            </tr>
            <th class="table-detail">Catatan/SN</th>
            <td class="table-detail"><textarea class="form-control" name="sn" id="sn"><?php echo $data_pesanan['keterangan']; ?></textarea></td>
        </tr>
        <tr>
            <th class="table-detail">Harga</th>
            <td class="table-detail">Rp <?php echo number_format($data_pesanan['harga'],0,',','.'); ?></td>
        </tr>
        <tr>
            <th class="table-detail">Status</th>
            <td class="table-detail"><badge class="badge badge-<?php echo $badge; ?>"><?php echo $data_pesanan['status']; ?></badge> <a href="/../halaman/status-order" target="_blank"><b><i aria-hidden="true" class="fa fa-question-circle"></i></b></a></td>
            </tr>
            <tr>
                <th class="table-detail">Tanggal & Waktu</th>
                <td class="table-detail"><?php echo tanggal_indo($data_pesanan['date']); ?>, <?php echo $data_pesanan['time']; ?></td>
            </tr>
            <tr>
                <th class="table-detail">Refund</th>
                <td class="table-detail"><span class="badge badge-<?php echo $label2; ?>"><i class="mdi mdi-<?php echo $icon2; ?>"></i></span></td>
            </tr>
            <tr>
                <th class="table-detail">Via API</th>
                <td class="table-detail"><span class="badge badge-<?php echo $label; ?>"><i class="mdi mdi-<?php echo $icon; ?>"></i></span></td>
            </tr>
        </table>
        <div class="text-right">
          <button type="submit" name="editz" class="btn btn-success">Edit SN</button>    
          <span class="btn btn-primary" onclick="copyText()">Copy imei</span>
        </div>
    </div>
    </form>
    <script>
     function copyText() {
       var textarea = document.getElementById("sn");
       textarea.select();
       document.execCommand("copy");
        alert("TEXT BERHASIL DI COPY!");
      }
</script>
    <?php
        } else{
?>
<b>ID TRANSACTION:</b> <?php echo $data_pesanan['oid']; ?><br>
<b>IMEI:</b> <?php echo $data_pesanan['target']; ?>
<br>
<br>
<b>Result:</b>
<br>
<input type="hidden" value="<?php echo $data_pesanan['keterangan']; ?>" id="sn">
<div id="sns"><?php echo $data_pesanan['keterangan']; ?></div>
<br>
<b>Status:</b> <?php echo $data_pesanan['status']; ?><br>
    <!--     <div class="table-responsive">-->
    <!--        <table class="table table-striped table-bordered table-box">-->
    <!--            <tr>-->
    <!--                <th class="table-detail" width="50%">Order ID</th>-->
    <!--                <td class="table-detail"><?php echo $data_pesanan['oid']; ?></td>-->
    <!--            </tr>-->
    <!--            <tr>-->
    <!--                <th class="table-detail">Pengguna</th>-->
    <!--                <td class="table-detail"><?php echo $data_pesanan['user']; ?></td>-->
    <!--            </tr>-->
    <!--            <th class="table-detail">Layanan</th>-->
    <!--            <td class="table-detail"><?php echo $data_pesanan['layanan']; ?></td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <th class="table-detail">Tujuan</th>-->
    <!--            <td class="table-detail"><?php echo $data_pesanan['target']; ?></td>-->
    <!--        </tr>-->
    <!--        <th class="table-detail">Result</th>-->
    <!--        <td class="table-detail"><textarea class="form-control" id="sn" readonly><?php echo $data_pesanan['keterangan']; ?></textarea></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th class="table-detail">Harga</th>-->
    <!--        <td class="table-detail">Rp <?php echo number_format($data_pesanan['harga'],0,',','.'); ?></td>-->
    <!--    </tr>-->
    <!--    <tr>-->
    <!--        <th class="table-detail">Status</th>-->
    <!--        <td class="table-detail"><badge class="badge badge-<?php echo $badge; ?>"><?php echo $data_pesanan['status']; ?></badge> <a href="/../halaman/status-order" target="_blank"><b><i aria-hidden="true" class="fa fa-question-circle"></i></b></a></td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <th class="table-detail">Tanggal & Waktu</th>-->
    <!--            <td class="table-detail"><?php echo tanggal_indo($data_pesanan['date']); ?>, <?php echo $data_pesanan['time']; ?></td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <th class="table-detail">Refund</th>-->
    <!--            <td class="table-detail"><span class="badge badge-<?php echo $label2; ?>"><i class="mdi mdi-<?php echo $icon2; ?>"></i></span></td>-->
    <!--        </tr>-->
    <!--        <tr>-->
    <!--            <th class="table-detail">Via API</th>-->
    <!--            <td class="table-detail"><span class="badge badge-<?php echo $label; ?>"><i class="mdi mdi-<?php echo $icon; ?>"></i></span></td>-->
    <!--        </tr>-->
    <!--    </table>-->
        <div class="text-right">   
          <span class="btn btn-primary" onclick="copyText()">Copy Imei</span>
        </div>
    <!--</div>-->
    
    <script>
    //  function copyText() {
    //   var textarea = document.getElementById("sn");
    //   textarea.select();
    //   document.execCommand("copy");
    //     alert("IMEI BERHASIL DI COPY!");
    //   }
</script>
<script>
        function copyText() {
            var content = document.getElementById("sns").innerText;
            navigator.clipboard.writeText(content).then(() => {
                alert("Teks disalin: " + content);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }
    </script>
<?php
        }
}
}
?>