<?php
session_start();
require '../config.php';
require '../lib/session_user.php';
if (isset($_POST['pesan'])) {
	require '../lib/session_login.php';
	$post_operator = $conn->real_escape_string(trim(filter($_POST['operator'])));
	$post_layanan = $conn->real_escape_string(trim(filter($_POST['layanan'])));
	$post_target = $conn->real_escape_string(trim(filter($_POST['target'])));
	$post_nometer = $conn->real_escape_string(trim(filter($_POST['no_meter'])));

	$cek_layanan = $conn->query("SELECT * FROM layanan_digital WHERE provider_id = '$post_layanan' AND status = 'Normal'");
	$data_layanan = mysqli_fetch_assoc($cek_layanan);				

	$order_id = acak_nomor(3).acak_nomor(4);
	$provider = $data_layanan['provider'];

	$cek_provider = $conn->query("SELECT * FROM provider WHERE code = '$provider'");
	$data_provider = mysqli_fetch_assoc($cek_provider);
	$action = 'pemesanan';

	$cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE user = '$sess_username' AND target = '$post_target' AND status = 'Pending' AND provider = '$provider'");
	$data_pesanan = mysqli_fetch_assoc($cek_pesanan);

	if (!$post_target || !$post_layanan || !$post_operator) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Kategori <br /> - Produk <br /> - Target');

	} else if (mysqli_num_rows($cek_layanan) == 0) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Produk Tidak Tersedia');

	} else if (mysqli_num_rows($cek_provider) == 0) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Server Sedang Maintenance');

	} else if ($data_user['saldo'] < $data_layanan['harga']) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Saldo Tidak Mencukupi');

	//} else if (mysqli_num_rows($cek_pesanan) == 1) {
		//$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Orderan Sebelumnya di Produk & Target yang Sama Masih Pending');

	} else {

		if ($provider == "MANUAL") {
		        	$postdata = "";
		    	} else if ($provider == "roamerimei") {
		if ($post_nometer == false) {
			$postdata = "api_key=".$data_provider['api_key']."&action=$action&layanan=".$data_layanan['provider_id']."&target=$post_target";
			} else if ($post_nometer == true) {
			$postdata = "api_key=".$data_provider['api_key']."&action=$action&layanan=".$data_layanan['provider_id']."&target=$post_target";
			}

           $url = 'https://ceirgo.id/api/produk-digital'; 
                
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	    	$response = curl_exec($curl);
            curl_close($curl);
			/*
			echo $response;
			*/
            $json_result = json_decode($response, true);

		} else {
			die("System Error!");
		}
		if ($provider == "roamerimei" AND $json_result['status'] == false) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => ''.$json_result['data']['pesan']);
		} else {
			if ($provider == "roamerimei") {
				$provider_oid = $json_result['data']['id'];
			}
			
				if ($conn->query("INSERT INTO pembelian_digital VALUES ('','$order_id', '$provider_oid', '$sess_username', '".$data_layanan['layanan']."', '".$data_layanan['harga']."', '".$data_layanan['profit']."', '$post_target', '$post_nometer', '$pesan', 'Pending', '$date', '$time', 'Website', '$provider', '0')") == true) {

					$conn->query("UPDATE users SET saldo = saldo - ".$data_layanan['harga'].", pemakaian_saldo = pemakaian_saldo + ".$data_layanan['harga']." WHERE username = '$sess_username'");
					$conn->query("INSERT INTO history_saldo VALUES ('', '$sess_username', 'Pengurangan Saldo', '".$data_layanan['harga']."', 'Order ID $order_id Produk Digital', '$date', '$time')");

					$harga = number_format($data_layanan['harga'],0,',','.');
					$_SESSION['hasil'] = array(
						'alert' => 'success',
						'judul' => 'Order Berhasil', 
						'pesan' => '<br/> 
						<b>Order ID : </b> '.$order_id.'<br />
						<b>Layanan : </b> '.$data_layanan['layanan'].'<br />
						<b>Target : </b> '.$post_target.'<br />
						<b>Harga : </b> Rp '.$harga.'');
				} else {
					$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Order Gagal', 'pesan' => 'Server Sedang Maintenance. Coba Lagi 15 Menit Kedepan.');
				}
			}
		}
	}


require("../lib/header.php");
?>

<!--Title-->
<title>UNBLOCK IMEI</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-body">
				<h4 class="text-uppercase text-center header-title"><i class="mdi mdi-cellphone-iphone mr1 text-primary"></i><b> Cek ceir</b></h4><hr>
				<form class="form-horizontal" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">   										    
					<div class="form-group">
						<label class="col-md-12 control-label">Kategori *</label>
						<div class="col-md-12">
							<select class="form-control" name="operator" id="operator">
								<option value="0">Pilih Salah Satu</option>
								<?php
								$cek_kategori = $conn->query("SELECT * FROM kategori_layanan WHERE nama IN ('Ceir') ORDER BY nama ASC");
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

					<div id="catatan">
					</div>
					
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="col-md-12 col-form-label">Nomor Imei *</label>
							<div class="col-md-12">
								<input type="text" name="target" class="form-control" placeholder="Input Nomor Imei HP">
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

	<!-- INFORMASI ORDER -->
	<div class="col-md-5" id="informasiorder">
		<div class="card">
			<div class="card-body">

				<center><h4 class="m-t-0 text-uppercase header-title"><i aria-hidden="true" class="fa fa-info-circle"></i><b> Informasi Order</h4></b>
					Gunakan koneksi internet yang stabil agar daftar produk sinkron dengan kategori yang dipilih.<hr>
				</center>

	
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