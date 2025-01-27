<?php
session_start();
require("../config.php");
require '../lib/session_user.php';
if (isset($_POST['request'])) {
	require '../lib/session_login.php';

	$post_voucher = $conn->real_escape_string(trim(filter($_POST['voucher'])));
	$cek_voucher = $conn->query("SELECT * FROM voucher WHERE voucher = '$post_voucher'");
	$data_voucher = $cek_voucher->fetch_assoc();
	$cek_voucher_rows = mysqli_num_rows($cek_voucher);

	$post_balance = $data_voucher['saldo'];
	$post_voc = $data_voucher['voucher'];

	if (!$post_voucher) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Kode Voucher');

	} else if ($cek_voucher_rows == 0) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Kode Voucher Tidak Valid.');

	} else if ($data_voucher['status'] == "sudah di redeem") { 
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Kode Voucher Sudah Digunakan');

	} else {

		$kode = acak_nomor(3).acak_nomor(3);

		$insert_depo = $conn->query("UPDATE voucher set status = 'sudah di redeem', user = '$sess_username', date = '$date', time = '$time' WHERE voucher = '$post_voucher'");
		$insert_depo = $conn->query("INSERT INTO deposit_voucher VALUES ('','$kode', '$sess_username', 'Voucher', 'KMPANEL' ,'Deposit Voucher', '-','-','$post_balance', '$post_balance', 'Success', 'Website', '$date', '$time')");
		$insert_depo = $conn->query("INSERT INTO history_saldo VALUES ('', '$sess_username', 'Penambahan Saldo', '$post_balance', 'Penambahan Saldo Deposit Voucher $post_balance', '$date', '$time')");
		$insert_depo = $conn->query("UPDATE users set saldo = saldo + $post_balance WHERE username = '$sess_username'");

		if ($insert_depo == TRUE) {
			$_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Redeem Voucher Berhasil', 'pesan' => 'Saldo Ditambah Sejumlah Rp '.$post_balance );
			exit(header("Location: ".$config['web']['url']."riwayat/deposit-saldo-voucher"));
		} else {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Redeem Voucher Gagal');
		}
	}
}
require("../lib/header.php");
?>

<!--Title-->
<title>Redeem Voucher Deposit</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-body">
				<h4 class="text-uppercase text-center header-title"><i class="ti-wallet text-primary"></i> REDEEM VOUCHER

					<div class="text-success">
						(ONLINE)
					</div>

				</h4>
				<hr>
				<form class="form-horizontal" role="form" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
					<div class="form-group">
						<label class="col-md-12 control-label">Kode Voucher *</label>
						<div class="col-md-12">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">KMP</span>
								</div>
								<input type="text" class="form-control" name="voucher" placeholder="VC-030609XXXXXXX" id="voucher">
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-md-12">
							<button type="submit" class="pull-right btn btn-primary btn-block waves-effect w-md waves-light" name="request"><i class="ti-wallet"></i> Redeem</button>
						</div>
					</div>    
				</form>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h4 class="text-uppercase text-center header-title"><i class="ti-wallet text-primary"></i> Beli Voucher</h4><hr>
				<center>
					<h4>Anda bisa membeli voucher deposit di partner <?php echo $data['short_title']; ?> melalui WA dibawah</h4>
					<p>
						<i aria-hidden="true" class="fa fa-arrow-circle-down fa-2x text-primary"></i>
					</p>
					<div class="btn-group m-t-20">
						<a class="btn btn-success waves-effect w-md waves-light" href="<?php echo $data['url_whatsapp']; ?>"><i class="mdi mdi-whatsapp" aria-hidden="true"></i> WhatsApp</a>
					</div><br/><br/>
					<p>
						<i aria-hidden="true" class="fa fa-arrow-circle-up fa-2x text-primary"></i>
					</p>
					<h4>Tersedia Voucher Rp 25.000 sampai Rp 25.000.000</h4>
				</center>
			</div>
		</div>
	</div>

	<!-- INFORMASI ORDER -->
	<div class="col-md-5">
		<div class="card">
			<div class="card-body">

				<center><h4 class="m-t-0 text-uppercase header-title"><i aria-hidden="true" class="fa fa-info-circle"></i><b> Informasi Deposit</h4></b>
					Online 24 jam setiap hari<hr>
				</center>

				<!--CARA-->
				<div class="table-responsive">
					<center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Langkah Melakukan Redeem</b></center>
					<ol class="list-p">
						<li>Lakukan pembelian di partner <?php echo $data['short_title']; ?>.</li>
						<li>Masukkan kode voucher yang valid (belum digunakan).</li>
						<li>Klik <span class="badge badge-primary"><b>Redeem</b></span></li>
					</ol>
				</div>

				<!--KETENTUAN-->
				<div class="table-responsive">
					<center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Syarat & Ketentuan Redeem</b></center>
					<ol class="list-p">
						<li>Dilarang menggunakan voucher yang sudah di redeem.</li>
						<li>Jumlah deposit ditambahkan ke saldo akun jika kode valid.</li>
						<li>Detail redeem voucher tampil di riwayat deposit.</li>
					</ol>
				</div>

				<div class="table-responsive">
					<center><i aria-hidden="true" class="fa fa-check-circle"></i><b> Syarat & Ketentuan Beli Voucher</b></center>
					<ol class="list-p">
						<li>Pembelian hanya di partner <?php echo $data['short_title']; ?>.</li>
						<li>Minimal saldo voucher Rp 25.000, maksimal Rp 25.000.000.</li>
						<li>Jumlah pembelian tanpa batas.</li>
						<li>Mengirim bukti pembayaran melalui WhatsApp.</li>
						<li>Metode pembayaran voucher:</li>
					</ol>
					<li>Bank BCA & BTPN/Jenius.</li>
					<li>Gopay, Ovo, Dana & LinkAja.</li>
				</div>

			</div>
		</div>
	</div>
	<!-- INFORMASI ORDER -->
</div>

<?php
    require ("../lib/footer.php");
?>