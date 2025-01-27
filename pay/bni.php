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
	$post_nopengirim = $conn->real_escape_string(trim(filter($_POST['nopengirim'])));

	$cek_metod = $conn->query("SELECT * FROM metode_depo WHERE id = '$post_provider'");
	$data_metod = $cek_metod->fetch_assoc();
	$cek_metod_rows = mysqli_num_rows($cek_metod);

	$cek_provider = $conn->query("SELECT * FROM provider WHERE code = 'DPEDIA'");
	$data_provider = mysqli_fetch_assoc($cek_provider);

	$cek_depo = $conn->query("SELECT * FROM deposit WHERE username = '$sess_username' AND status = 'Pending'");
	$data_depo = $cek_depo->fetch_assoc();
	$count_depo = mysqli_num_rows($cek_depo);

	$kode = acak_nomor(3).acak_nomor(3);
	$acakin = acak_nomor(2).acak_nomor(1);

	if (!$post_provider || !$post_pembayaran || !$post_jumlah || !$post_pengirim || !$post_nopengirim) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Bank Tujuan <br /> - Pembayaran <br /> - Jumlah <br /> - Pengirim <br /> - No. Pengirim');

	} else if ($cek_metod_rows == 0) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Metode Deposit Tidak Tersedia.');

	} else if ($count_depo >= 1) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Terdapat Deposit Yang Berstatus Pending.');
		exit(header("Location: ".$config['web']['url']."invoice"));
		
	} else if ($post_jumlah < 100000) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Minimal Deposit Bank 100000.');

    } else if ($post_jumlah > 50000000) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Maksimal Deposit Bank Rp50.000.000.');

	} else {

		$postdata = "key=".$data_provider['api_key']."&secret=".$data_provider['secret_key']."&method_id=5&amount=$post_jumlah&sender_name=$post_pengirim&sender_account=$post_nopengirim";

/*
{
    "id": 4,
    "name": "BANK BCA"
},
{	
	method_id=5
    "id": 5,
    "name": "BANK BNI"
}
*/

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://d-pedia.co.id/api/v1/deposit/create");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$chresult = curl_exec($ch);
		//echo $chresult;
		curl_close($ch);
		$json_result = json_decode($chresult, true);

		if ($json_result['status'] !== "ok") {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => $json_result['message']);
		} elseif ($json_result['status'] == "ok") {
			$pesan = $json_result['message'];
			$provider_oid = $json_result['data']['id'];
			$u_pengirim = $json_result['data']['sender_name'];
			$u_nopengirim = $json_result['data']['sender_account'];
			$u_uniq = $json_result['data']['uniq'];
			$amount = $json_result['data']['amount'];
			$get_saldo = $json_result['data']['get_amount'];
			$jumlah_tf = $json_result['data']['pay_amount'];
			$status = $json_result['data']['status'];
			$u_created_at = $json_result['created_at'];
			$tujuan = $json_result['data']['payment_target'];
			$trxline = $json_result['data']['transaction_line'];

			$insert = $conn->query("INSERT INTO deposit VALUES ('','$provider_oid', '$sess_username', '".$data_metod['tipe']."', '".$data_metod['provider']."' ,'".$data_metod['nama']."', '$u_nopengirim','$tujuan','$jumlah_tf', '$get_saldo', '$status', '$trxline', '$date', '$time')");
			if ($insert == true) {
				exit(header("Location: ".$config['web']['url']."invoice"));
			} else {
				$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Error System(Insert To Database).');
			}
		} else {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => $json_result['message']);
		}
	}
}
require("../lib/header.php");
?>

<!--Title-->
<title>Deposit via Bank Bni</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-body">
				<h4 class="text-uppercase text-center header-title"><i class="ti-wallet text-primary"></i> DEPOSIT BANK BNI

				<?php
                    //INFO STATUS DEPOSIT
                    $cek_status = $conn->query("SELECT * FROM metode_depo WHERE tujuan IN ('DPEDIA') AND tipe = 'Bank' AND keterangan = 'OFF' AND provider='BNI'");
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
								$cek_kategori = $conn->query("SELECT * FROM metode_depo WHERE tujuan IN ('DPEDIA') AND tipe = 'Bank' AND keterangan = 'ON' AND provider='BNI' ORDER BY nama ASC");
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
                                <input type="number" class="form-control" name="jumlah" placeholder="Jumlah Deposit">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12 control-label">Pengirim *</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="pengirim" placeholder="Nama Rek. Pengirim">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12 control-label">No. Pengirim *</label>
                        <div class="col-md-12">
                            <input type="number" class="form-control" name="nopengirim" placeholder="Nomor Rek. Pengirim">
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
                        <li>Pilih bank tujuan & pembayaran.</li>
                        <li>Masukkan jumlah deposit.</li>
                        <li>Masukkan nama pengirim & nomor rekening.</li>
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