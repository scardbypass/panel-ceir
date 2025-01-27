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

    $cek_depo = $conn->query("SELECT * FROM deposit_epayment WHERE username = '$sess_username' AND status = 'Pending'");
    $data_depo = $cek_depo->fetch_assoc();
    $count_depo = mysqli_num_rows($cek_depo);

    $kode = acak_nomor(3).acak_nomor(3);

    if (!$post_provider || !$post_pembayaran || !$post_jumlah) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Provider <br /> - Pembayaran <br /> - Email Pengirim <br /> - Jumlah');
        
    } else if ($cek_metod_rows == 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Metode Deposit Tidak Tersedia.');
        
    } else if ($count_depo >= 1) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Terdapat Deposit Yang Berstatus Pending.');
        exit(header("Location: ".$config['web']['url']."invoice-pulsa"));

    } else if ($post_jumlah < 10) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Minimal Deposit Epayment $10.');
        
    } else if ($post_jumlah > 1000) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Maksimal Deposit Epayment $1000.');

    } else {
        
        $metodnya = $data_metod['nama'];
        $get_saldo = $post_jumlah * $data_metod['rate'];
        $insert = $conn->query("INSERT INTO deposit_epayment VALUES ('','$kode', '$sess_username', '".$data_metod['tipe']."', '".$data_metod['provider']."' ,'$metodnya', '$post_pengirim','".$data_metod['tujuan']."','$post_jumlah', '$get_saldo', 'Pending', 'Website', '$date', '$time')");
        if ($insert == TRUE) {
            exit(header("Location: ".$config['web']['url']."invoice-epayment"));
            
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Deposit Gagal');
        }
    }
}
require("../lib/header.php");
?>

<!--Title-->
<title>Deposit via E-Payment</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="m-t-0 text-uppercase text-center header-title"><i class="ti-wallet text-primary"></i> DEPOSIT E-PAYMENT

                <?php
                    //INFO STATUS DEPOSIT
                    $cek_status = $conn->query("SELECT * FROM metode_depo WHERE tipe = 'EPayment' AND keterangan = 'ON'");
                    $data_status = $cek_status->fetch_assoc();
                    $count_status = mysqli_num_rows($cek_status);
                    if ($count_status >= 1) {
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

                <form class="form-horizontal" role="form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                    <div class="form-group">
                        <label class="col-md-12 control-label">Provider *</label>
                        <div class="col-md-12">
                            <select class="form-control" name="provider" id="provider">
                                <option value="0">Pilih Salah Satu</option>
                                <?php
                                $cek_kategori = $conn->query("SELECT * FROM metode_depo WHERE tipe IN ('EPayment') AND keterangan = 'ON' ORDER BY nama ASC");
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
                        <label class="col-md-12 control-label">Email Pengirim *<br/>
                            <small class="text-danger">Gunakan Email Akun Pengirim</small>
                        </label>
                        <div class="col-md-12">
                            <input type="email" class="form-control" name="pengirim" placeholder="<?php echo $data['email']; ?>" id="pengirim">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-md-12 col-form-label">Jumlah *</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control" name="jumlah" placeholder="Jumlah Deposit" id="jumlah">
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="col-md-12 col-form-label">Saldo yang didapat</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control"  name="saldo" placeholder="Saldo Yang Didapat" id="rate" readonly>
                                </div>
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
                    Gunakan koneksi internet yang stabil agar metode pembayaran sinkron dengan provider yang dipilih.<hr>
                </center>

                <!--CARA-->
                <div class="table-responsive">
                    <center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Cara Melakukan Deposit</b></center>
                    <ol class="list-p">
                        <li>Pilih salah satu provider & pembayaran.</li>
                        <li>Masukkan email pengirim.</li>
                        <li>Masukkan jumlah deposit.</li>
                        <li>Klik <span class="badge badge-primary"><b>Deposit</b></span></li>
                    </ol>
                </div>

                <!--KETENTUAN-->
                <div class="table-responsive">
                    <center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Syarat & Ketentuan Deposit</b></center>
                    <ol class="list-p">
                        <li>Minimal deposit $10.</li>
                        <li>Fee ditanggung oleh <?php echo $data['short_title']; ?></li>
                        <li>Saldo yang diterima mengikuti rate yang kami tentukan.</li>
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