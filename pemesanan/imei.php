<?php
session_start();
require '../config.php';
require '../lib/session_user.php';

function sendWhatsAppNotification($license, $apiwa, $phone, $message) {
    $notif_url = 'https://scardwaget.my.id/send-message';
    $gateway = [
        'api_key' => $license,
        'sender' => $apiwa,
        'number' => $phone,
        'message' => $message
    ];

    // cURL request
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $notif_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $gateway,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4
    ]);

    // Eksekusi cURL dan tangani error
    $response = curl_exec($curl);
    if ($response === false) {
        echo "Error sending WhatsApp notification: " . curl_error($curl);
    }
    curl_close($curl);
}

if (isset($_POST['pesan'])) {
    require '../lib/session_login.php';

    $post_operator = $conn->real_escape_string(trim(filter($_POST['operator'])));
    $post_layanan = $conn->real_escape_string(trim(filter($_POST['layanan'])));
    $post_target = $conn->real_escape_string(trim(filter($_POST['target'])));
    $post_nometer = $conn->real_escape_string(trim(filter($_POST['no_meter'])));

    $cek_layanan = $conn->query("SELECT * FROM layanan_digital WHERE provider_id = '$post_layanan' AND status = 'Normal'");
    $data_layanan = mysqli_fetch_assoc($cek_layanan);

    if (!$data_layanan) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Produk Tidak Tersedia');
        require("../lib/header.php");
        exit;
    }

    $order_id = acak_nomor(3) . acak_nomor(4);
    $provider = $data_layanan['provider'];

    $cek_provider = $conn->query("SELECT * FROM provider WHERE code = '$provider'");
    $data_provider = mysqli_fetch_assoc($cek_provider);

    if (!$data_provider) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Server Sedang Maintenance');
        require("../lib/header.php");
        exit;
    }

    $cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE user = '$sess_username' AND target = '$post_target' AND status = 'Pending' AND provider = '$provider'");
    $data_pesanan = mysqli_fetch_assoc($cek_pesanan);

    if (!$post_target || !$post_layanan || !$post_operator) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Kategori <br /> - Produk <br /> - Target');
    } else if ($data_user['saldo'] < $data_layanan['harga']) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Saldo Tidak Mencukupi');
    } else {
        // Kirim notifikasi ke pengguna
        $cekwa = $conn->query("SELECT * FROM provider WHERE code = 'ScardWaget'");
        if ($cekwa->num_rows > 0) {
            $datawa = $cekwa->fetch_assoc();
            $license = 'V9HT3NZXSY2tRjWz45QT1dUnRuzyb2';
            $apiwa = '628982765556';
            $q_phone = mysqli_query($conn, "SELECT * FROM users WHERE username = '$sess_username'");
            $f = mysqli_fetch_assoc($q_phone);
            $phone = $f['no_hp'];

            // Pesan untuk pengguna
            $message = "Hai *" . $sess_username . "*\n" .
                "Transaksi Kamu *BERHASIL*\n" .
                "Order ID: *" . $order_id . "*\n" .
                "Target: *" . $post_target . "*\n" .
                "Layanan: *" . $data_layanan['layanan'] . "*\n" .
                "Harga: *Rp " . number_format($data_layanan['harga'], 0, ',', '.') . "*\n" .
                "Status: *Pending*\n" .
                "Catatan: *Pesanan Akan Segera Diproses Kami Akan Mengirim Notifikasi!*";

            sendWhatsAppNotification($license, $apiwa, $phone, $message);
        }

        // Insert pembelian ke database
        if ($conn->query("INSERT INTO pembelian_digital VALUES ('','$order_id', '$order_id', '$sess_username', '".$data_layanan['layanan']."', '".$data_layanan['harga']."', '".$data_layanan['profit']."', '$post_target', '$post_nometer', '$pesan', 'Pending', '$date', '$time', 'Website', '$provider', '0')") === true) {
            $conn->query("UPDATE users SET saldo = saldo - ".$data_layanan['harga'].", pemakaian_saldo = pemakaian_saldo + ".$data_layanan['harga']." WHERE username = '$sess_username'");
            $conn->query("INSERT INTO history_saldo VALUES ('', '$sess_username', 'Pengurangan Saldo', '".$data_layanan['harga']."', 'Order ID $order_id Produk Digital', '$date', '$time')");

            $_SESSION['hasil'] = array(
                'alert' => 'success',
                'judul' => 'Order Berhasil',
                'pesan' => '<b>Order ID : </b> '.$order_id.'<br /><b>Layanan : </b> '.$data_layanan['layanan'].'<br /><b>Target : </b> '.$post_target.'<br /><b>Harga : </b> Rp '.number_format($data_layanan['harga'], 0, ',', '.').''
            );

            // Notifikasi untuk Admin
            $phone_admin = '6282132687740'; // Ganti dengan nomor admin yang sesuai
            $message_admin = "Hai *Admin*\n" .
                "Transaksi Baru Nih!*\n" .
                "Order ID: *" . $order_id . "*\n" .
                "Target: *" . $post_target . "*\n" .
                "Layanan: *" . $data_layanan['layanan'] . "*\n" .
                "Harga: *Rp " . number_format($data_layanan['harga'], 0, ',', '.') . "*\n" .
                "Status: *Pending*\n" .
                "Catatan: *Segera Diproses Ya Min!*";

            sendWhatsAppNotification($license, $apiwa, $phone_admin, $message_admin);
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Server Sedang Maintenance. Coba Lagi 15 Menit Kedepan.');
        }
    }
}

require("../lib/header.php");
?>

<!-- Bagian HTML dan JavaScript tetap sama -->
<title>UNLOCKTOOL</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="text-uppercase text-center header-title"><i class="mdi mdi-cellphone-iphone mr1 text-primary"></i><b> UNLOCK IMEI 3 bulan</b></h4><hr>
                <form class="form-horizontal" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">    
                    <div class="form-group">
                        <label class="col-md-12 control-label">Kategori *</label>
                        <div class="col-md-12">
                            <select class="form-control" name="operator" id="operator">
                                <option value="0">Pilih Salah Satu</option>
                                <?php
                                $cek_kategori = $conn->query("SELECT * FROM kategori_layanan WHERE nama IN ('Unlock') ORDER BY nama ASC");
                                while ($data_kategori = $cek_kategori->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $data_kategori['kode']; ?>"><?php echo $data_kategori['nama']; ?></option>
                                <?php } ?>                                                    
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12 control-label">Produk *</label>
                        <div class="col-md-12">
                            <select class="form-control" name="layanan" id="layanan">
                                <option value="0">Pilih Kategori</option>
                            </select>
                        </div>
                    </div>

                    <div id="catatan"></div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-md-12 col-form-label">Target *</label>
                            <div class="col-md-12">
                                <input type="number" name="target" class="form-control" placeholder="No.imei 15 angka">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="col-md-12 col-form-label">Total</label>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp </span>
                                    </div>
                                    <input type="number" class="form-control" id="harga" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-block btn-primary waves-effect w-md waves-light" name="pesan" id="checkout" disabled="disabled"><i class="mdi mdi-cart"></i>Order</button>
                        </div>
                    </div>
                </form>

                <div style="text-align: center;">
                    <br/>Dengan melakukan order, Anda telah memahami dan menyetujui <a href="#informasiorder"><b>Syarat & Ketentuan</b></a>, serta mengikuti <a href="#informasiorder"><b>Cara Melakukan Order</b></a> sesuai panduan.
                </div>
                
            </div>
        </div>
    </div>          
    <!-- end col -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#operator").change(function() {
            var operator = $("#operator").val();
            $.ajax({
                url: '<?php echo $config['web']['url'];?>ajax/layanan_produkdigital.php',
                data: 'operator=' + operator,
                type: 'POST',
                dataType: 'html',
                success: function(msg) {
                    $("#layanan").html(msg);
                }
            });
        });
        $("#layanan").change(function() {
            var layanan = $("#layanan").val();
            $.ajax({
                url: '<?php echo $config['web']['url'];?>ajax/harga_digital.php',
                data: 'layanan=' + layanan,
                type: 'POST',
                dataType: 'html',
                success: function(msg) {
                    $("#harga").val(msg);
                }
            });
        });
        $("#layanan").change(function() {
            var layanan = $("#layanan").val();
            $.ajax({
                url: '<?php echo $config['web']['url'];?>ajax/catatan-digital.php',
                data: 'layanan=' + layanan,
                type: 'POST',
                dataType: 'html',
                success: function(msg) {
                    $("#catatan").html(msg);
                }
            });
        });
    });
</script>

<script type="text/javascript">
    //Disable button
    (function() {
        $('input').keyup(function() {
            var empty = false;
            $('input').each(function() {
                if ($(this).val() == '') {
                    empty = true;
                }
            });
            //Id button
            if (empty) {
                $('#checkout').attr('disabled', 'disabled');
            } else {
                $('#checkout').removeAttr('disabled');
            }
        });
    })()
</script>

<?php
require ("../lib/footer.php");
?>
```