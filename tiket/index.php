<?php
session_start();
require '../config.php';
require '../lib/session_user.php';

if (isset($_POST['kirim'])) {
    require '../lib/session_login.php';
    $post_subjek = $conn->real_escape_string(trim(filter($_POST['subjek'])));
    $post_pesan = $conn->real_escape_string(trim(filter($_POST['pesan'])));

    $cek_tiket = $conn->query("SELECT * FROM tiket WHERE user = '$sess_username' AND subjek = '$post_subjek' AND status IN ('Pending', 'Responded', 'Waiting') ");

    if (!$post_subjek || !$post_pesan) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Subjek <br /> - Pesan');	
    } else if (strlen($post_pesan) > 5000) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Maksimal Isi Pesan Hanya 5000 Karakter');
    } else if (mysqli_num_rows($cek_tiket) == 1) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Tiket Sebelumnya Belum Ditutup');
    } else {
        $insert_tiket = $conn->query("INSERT INTO tiket VALUES ('', '$sess_username', '$post_subjek', '$post_pesan', '$date', '$time', '$date $time', 'Pending','1','0')");
        if ($insert_tiket == TRUE) {
            $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Tiket Terkirim', 'pesan' => 'Kami Akan Segera Merespon..');
        } else {
            $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pengiriman Gagal', 'pesan' => 'Tiket Tidak Valid, Silakan Membuat Tiket Baru');
        }
    }
}

require("../lib/header.php");
?>

<!--Title-->
<title>Tiket Bantuan</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
    <div class="col-md-5">
        <div class="card-box">
            <center>
                <h4 class="header-title mb-3"><i class="mdi mdi-email-open text-primary"></i><b> TIKET BANTUAN</b></h4>
            </center>
            <hr>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover m-0">
                    <thead>
                        <form class="form-default" role="form" method="POST">
                            <input aria-label="Form Input Data" type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Subjek</label>
                                <div class="col-md-12">
                                    <select class="form-control" type="text" name="subjek" id="layanan">
                                        <option value="UMUM">UMUM</option>
                                        <option value="PRODUK">PRODUK</option>
                                        <option value="LAYANAN">LAYANAN</option>
                                        <option value="DEPOSIT">DEPOSIT</option>
                                        <option value="PESANAN">PESANAN</option>
                                        <option value="KERJASAMA">KERJASAMA</option>
                                        <option value="LAINNYA">LAINNYA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Pesan</label>
                                <div class="col-md-12">
                                    <small class="text-danger">Sertakan ID Jika Terkait Deposit/Order</small>
                                    <textarea class="form-control" name="pesan" rows="5" placeholder="Isi pesan"></textarea>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-block btn-primary waves-effect w-md waves-light" name="kirim"><i class="mdi mdi-send"></i> Kirim</button>
                                </div>
                            </div>
                        </form>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card-box">
            <center>
                <h4 class="header-title mb-3"><b>DAFTAR TIKET</b></h4>
            </center>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover m-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subjek</th>
                            <th>Pesan</th>   
                            <th>Update</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php 
                                // start paging config
                                if (isset($_GET['search'])) {
                                    $cari = $conn->real_escape_string(filter($_GET['search']));
                                    $cari_status = $conn->real_escape_string(filter($_GET['status']));
                                    $cek_tiket = "SELECT * FROM tiket WHERE subjek LIKE '%$cari%' AND status LIKE '%$cari_status%' AND user = '$sess_username' ORDER BY id DESC"; // edit
                                } else {
                                    $cek_tiket = "SELECT * FROM tiket WHERE user = '$sess_username' ORDER BY id DESC"; // edit
                                }
                                if (isset($_GET['search'])) {
                                    $cari_urut = $conn->real_escape_string(filter($_GET['tampil']));
                                    $records_per_page = $cari_urut; // edit
                                } else {
                                    $records_per_page = 10; // edit
                                }
                                $starting_position = 0;
                                if(isset($_GET["halaman"])) {
                                    $starting_position = ($conn->real_escape_string(filter($_GET["halaman"]))-1) * $records_per_page;
                                }
                                $new_query = $cek_tiket." LIMIT $starting_position, $records_per_page";
                                $new_query = $conn->query($new_query);
                                // end paging config
                                while ($data_tiket = $new_query->fetch_assoc()) {
                                    if ($data_tiket['status'] == "Pending") {
                                        $label = "warning";
                                        $btn = "";
                                    } else if ($data_tiket['status'] == "Closed") {
                                        $label = "danger";
                                        $btn = "disabled";
                                    } else if ($data_tiket['status'] == "Waiting") {
                                        $label = "info";   
                                        $btn = ""; 
                                    } else if ($data_tiket['status'] == "Responded") {
                                        $label = "success";
                                        $btn = "";
                                    }
                            ?>
                            <tr>
                                <td>
                                    <?php echo $data_tiket['id']; ?>
                                </td>
                                <td><?php echo $data_tiket['subjek']; ?></td>
                                <td><?php echo $data_tiket['pesan']; ?></td>
                                <td><?php echo time_elapsed_string($data_tiket['update_terakhir']); ?></td>
                                <td>
                                    <span class="btn btn-xs btn-<?php echo $label; ?>"><?php echo $data_tiket['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo $config['web']['url'];?>tiket/view?id=<?php echo $data_tiket['id']; ?>" class="btn btn-xs btn-warning"><i aria-hidden="true" class="fa fa-eye"></i></a>
                                    <a href="<?php echo $config['web']['url'];?>tiket/open?id=<?php echo $data_tiket['id']; ?>" class="btn btn-xs btn-primary <?php echo $btn; ?>"><i aria-hidden="true" class="fa fa-reply"></i></a>
                                </td>
                            </tr>   
                            <?php 
                                } 
                            ?>
                    </tbody>
                </table>
            </div>
            <center><br/>
                <?php if (mysqli_num_rows($CallDBTiketPending) !== 0) { ?>
                <span class="badge badge-warning badge-pill notif-tiket"><?php echo mysqli_num_rows($CallDBTiketPending); ?> </span> Pending <?php } ?>
                <?php if (mysqli_num_rows($CallDBTiketWaiting) !== 0) { ?>
                <span class="badge badge-info badge-pill notif-tiket"><?php echo mysqli_num_rows($CallDBTiketWaiting); ?> </span> Waiting <?php } ?>
                <?php if (mysqli_num_rows($CallDBTiketResponded) !== 0) { ?>
                <span class="badge badge-success badge-pill notif-tiket"><?php echo mysqli_num_rows($CallDBTiketResponded); ?> </span> Responded <?php } ?>
                <?php if (mysqli_num_rows($CallDBTiketClosed) !== 0) { ?>
                <span class="badge badge-danger badge-pill notif-tiket"><?php echo mysqli_num_rows($CallDBTiketClosed); ?> </span> Closed <?php } ?>
            </center>
        </div>
    </div>
</div>

<?php 
include '../lib/footer.php';
?>