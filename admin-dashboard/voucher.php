<?php
session_start();
require '../config.php';
require '../lib/session_login_admin.php';
require '../lib/header_admin.php';

if (isset($_POST['tambah'])) {
    $nominal = $conn->real_escape_string(filter($_POST['nominal']));

    if (!$nominal) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Kode voucher gagal diproses');
    } else {

        $status = "active";
        $voc = acak_nomor(6).acak_nomor(7);

        if ($conn->query("INSERT INTO voucher VALUES ('', 'VC-$voc', '$nominal', '$status', '-', '$date', '$time')") == true) {
            $_SESSION['hasil'] = array(
                'alert' => 'success', 
                'judul' => 'Berhasil',
                'pesan' => '
                Kode voucher berhasil dibuat <br/><hr>
                <b>Kode</b> : VC-'.$voc.' <br />
                <b>Nominal</b> : '.$nominal.' <br />   
                <b>Status</b> : '.$status.' <br />
                ');

        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Kode voucher gagal dibuat');
        }
    }
}

?>

<!-- Page-Title -->
<div class="row">
    <div class="col-md-12">
        <br /><h2 class="text-center">Manajemen dan Laporan Data Deposit Voucher Member</h2><br/>
    </div>
</div>
<!-- End-Page-Title -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i class="ti-wallet text-primary"></i> Generate Voucher</h4><hr>
                <form class="form-horizontal" role="form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Masukkan Nominal</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="nominal" placeholder="Masukkan Nominal" id="nominal">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-12">
                            <button type="submit" class="pull-right btn btn-primary btn-block waves-effect w-md waves-light" name="tambah"><i class="ti-wallet"></i> Generate</button>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3"><i class=" mdi mdi-history "></i> Daftar Voucher</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hovered nowrap mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Voucher</th>
                                <th>Saldo</th>
                                <th>Status</th>
                                <th>Pengguna</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $nomer = 1; ?>
                            <?php $cek_voc = $conn->query("SELECT * FROM voucher ORDER BY id DESC"); ?>
                            <?php while ($data_voc = $cek_voc->fetch_assoc()) { ?>
                                <?php if ($data_voc['status'] == "active"){ $color = "success"; }
                                else if ($data_voc['status'] == "sudah di redeem"){ $color = "danger";
                            } ?>
                            <tr>
                                <td><?= $data_voc['id']; ?></td>
                                <td><?= $data_voc['date']." ".$data_voc['time']; ?></td>
                                <td style="min-width: 180px;">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" value="<?php echo $data_voc['voucher']; ?>" id="voucher-<?php echo $data_voc['id']; ?>" readonly="">
                                        <button data-toggle="tooltip" title="Copy" class="btn btn-xs btn-primary" type="button" onclick="copy_to_clipboard('voucher-<?php echo $data_voc['id']; ?>')"><i class="mdi mdi-content-copy"></i></button>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($data_voc['saldo'],0,',','.'); ?></td>
                                <td><label class="badge badge-<?= $color; ?>"><?= $data_voc['status']; ?></label></td>
                                <td><?= $data_voc['user']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
function copy_to_clipboard(element) {
    var copyText = document.getElementById(element);
    copyText.select();
    document.execCommand("copy");
}
</script>

<?php 
require '../lib/footer_admin.php';
?>