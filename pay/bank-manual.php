<?php
session_start();
require("../config.php");
require '../lib/session_user.php';
if (isset($_POST['request'])) {
    require '../lib/session_login.php';

    $post_provider = $conn->real_escape_string($_POST['provider']);
    $post_pembayaran = $conn->real_escape_string($_POST['pembayaran']);
    $post_jumlah = $conn->real_escape_string(trim(filter($_POST['jumlah'])));
    $post_pengirim = $conn->real_escape_string(trim(filter($_POST['pengirim'])));

    $cek_metod = $conn->query("SELECT * FROM metode_depo WHERE id = '$post_provider'");
    $data_metod = $cek_metod->fetch_assoc();
    $cek_metod_rows = mysqli_num_rows($cek_metod);

    $cek_depo = $conn->query("SELECT * FROM deposit_bank WHERE username = '$sess_username' AND status = 'Pending'");
    $data_depo = $cek_depo->fetch_assoc();
    $count_depo = mysqli_num_rows($cek_depo);

    $kode = acak_nomor(3).acak_nomor(3);
    $acakin = acak_nomor(2).acak_nomor(1);

    if (!$post_provider || !$post_pembayaran || !$post_jumlah) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Bank Tujuan <br /> - Pembayaran <br /> - Jumlah');

    } else if ($cek_metod_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Metode Deposit Tidak Tersedia.');

    } else if ($count_depo >= 1) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Terdapat Deposit Yang Berstatus Pending.');
        exit(header("Location: ".$config['web']['url']."invoice-bank"));
        
    } else if ($post_jumlah < 100000) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Minimal Deposit Bank Rp100.000.');

    } else if ($post_jumlah > 50000000) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Maksimal Deposit Bank Rp50.000.000.');

    } else {

        $metodnya = $data_metod['nama'];
        $get_saldo = $post_jumlah * $data_metod['rate'];
        $amount = $acakin + $get_saldo;
        $reg = $acakin + $post_jumlah;
        $insert = $conn->query("INSERT INTO deposit_bank VALUES ('','$kode', '$sess_username', '".$data_metod['tipe']."', '".$data_metod['provider']."' ,'$metodnya', '-','".$data_metod['tujuan']."','$reg', '$amount', 'Pending', 'Website', '$date', '$time')");
        if ($insert == TRUE) {
            exit(header("Location: ".$config['web']['url']."invoice-bank"));

        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Deposit Gagal');
        }
    }
}
require("../lib/header.php");
?>

<!--Title-->
<title>Deposit via Bank</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="text-uppercase text-center header-title"><i class="ti-wallet text-primary"></i> DEPOSIT BANK

                <?php
                    //INFO STATUS DEPOSIT
                    $cek_status = $conn->query("SELECT * FROM metode_depo WHERE tujuan NOT IN ('DPEDIA') AND tipe = 'Bank' AND keterangan = 'OFF'");
                    $data_status = $cek_status->fetch_assoc();
                    $count_status = mysqli_num_rows($cek_status);
                    if ($count_status == 0) {
                        ?>
                        <div class="text-success">
                            (ONLINE)
                        </div>
                    <?php }
                    else {
                        ?>
                        <div class="text-danger">
                            (OFFLINE)
                        </div>
                    <?php }
                ?>

                </h4>
                <hr>
                <form class="form-horizontal" role="form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Bank Tujuan *</label>
                        <div class="col-md-12">
                            <select class="form-control" name="provider" id="provider">
                                <option value="0">Pilih Salah Satu</option>
                                <?php
                                $cek_kategori = $conn->query("SELECT * FROM metode_depo WHERE tujuan NOT IN ('DPEDIA') AND tipe = 'Bank' AND keterangan = 'ON' ORDER BY nama ASC");
                                while ($data_metode = $cek_kategori->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $data_metode['id'];?>"><?php echo $data_metode['provider'];?></option>
                                <?php } ?>  
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-12 control-label">Pembayaran *</label>
                        <div class="col-md-12">
                            <select class="form-control" name="pembayaran" id="pembayaran">
                                <option value="0">Pilih Provider Pembayaran</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12 control-label">Jumlah *</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" name="jumlah" placeholder="Jumlah Deposit" id="jumlah">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-12">
                            <button type="submit" class="pull-right btn btn-primary btn-block waves-effect w-md waves-light" name="request"><i class="ti-wallet"></i> Deposit</button>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>  
    <!-- end col -->

    <!-- INFORMASI ORDER -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">

                <center><h4 class="m-t-0 text-uppercase header-title"><i aria-hidden="true" class="fa fa-info-circle"></i><b> Informasi Deposit</h4></b>
                    Gunakan koneksi internet yang stabil agar metode pembayaran sinkron dengan bank tujuan yang dipilih.<hr>
                </center>

                <!--CARA-->
                <div class="table-responsive">
                    <center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Cara Melakukan Deposit</b></center>
                    <ol class="list-p">
                        <li>Pilih salah satu bank tujuan & pembayaran.</li>
                        <li>Masukkan jumlah deposit.</li>
                        <li>Klik <span class="badge badge-primary"><b>Deposit</b></span></li>
                    </ol>
                </div>

                <!--KETENTUAN-->
                <div class="table-responsive">
                    <center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Syarat & Ketentuan Deposit</b></center>
                    <ol class="list-p">
                        <li>Minimal deposit Rp.100.000.</li>
                        <li>Jumlah deposit ditambahkan dengan 3 angka unik.</li>
                        <li>Detail faktur tampil setelah klik deposit.</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- INFORMASI ORDER -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#tipe").change(function() {
            var tipe = $("#tipe").val();
            $.ajax({
                url: '<?php echo $config['web']['url'];?>ajax/provider-deposit.php',
                data: 'tipe=' + tipe,
                type: 'POST',
                dataType: 'html',
                success: function(msg) {
                    $("#provider").html(msg);
                }
            });
        });
        $("#provider").change(function() {
            var provider = $("#provider").val();
            $.ajax({
                url: '<?php echo $config['web']['url'];?>ajax/pembayaran-deposit.php',
                data: 'provider=' + provider,
                type: 'POST',
                dataType: 'html',
                success: function(msg) {
                    $("#pembayaran").html(msg);
                }
            });
        });
        $("#jumlah").change(function(){
            var pembayaran = $("#pembayaran").val();
            var jumlah = $("#jumlah").val();
            $.ajax({
                url : '<?php echo $config['web']['url'];?>ajax/rate-deposit.php',
                type  : 'POST',
                dataType: 'html',
                data  : 'pembayaran='+pembayaran+'&jumlah='+jumlah,
                success : function(result){
                    $("#rate").val(result);
                }
            });
        });  
    });

</script>

<?php
    require ("../lib/footer.php");
?>