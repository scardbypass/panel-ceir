<?php 
session_start();
require '../config.php';
require '../lib/session_login.php';
require '../lib/session_user.php';
if ($data_user['level'] == 'Member') {
	$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Gagal!', 'pesan' => 'Dilarang Mengakses!.');
	exit(header("Location: ".$config['web']['url']));
}

if (isset($_POST['tambah'])) {
	$email = $conn->real_escape_string(trim($_POST['email']));
	$username = $conn->real_escape_string(filter($_POST['username']));
	$password = $conn->real_escape_string(trim($_POST['password']));
	$level = trim($_POST['level']);

	$cek_username = $conn->query("SELECT * FROM users WHERE username = '$username'");
	$cek_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
	$hash_pass = password_hash($password, PASSWORD_DEFAULT);
	$api_key =  acak(32);
	$terdaftar = date("Y-m-d H:i:s");

	$cek_pendaftaran= $conn->query("SELECT * FROM harga_pendaftaran WHERE level = '$level'");
	$datanya = $cek_pendaftaran->fetch_assoc();

	if (!$email || !$username || !$password || !$level) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Level <br /> - Email <br /> - Username <br /> - Password');
	} else if ($level != "Member" AND $level != "Reseller" AND $level != "Admin" AND $level != "Agen") {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Input Tidak Sesuai');
	} else if ($cek_username->num_rows > 0) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Username <strong>'.$username.' </strong> Telah Terdaftar'); 
	} else if ($cek_email->num_rows > 0) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Email <strong> '.$email.' </strong> Telah Terdaftar');            
	} else if (strlen($username) < 5) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Username Minimal 5 Karakter Tanpa Spasi');
	} else if (strlen($password) < 5) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Password Minimal 5 Karakter Tanpa Spasi');
	} else if ($data_user['saldo'] < $datanya['harga']) {
		$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Saldo Tidak Mencukupi');
	} else {
		if ($conn->query("INSERT INTO users VALUES (NULL, '', '$email', '$username', '$hash_pass', '".$datanya['bonus']."', '0', '$level', 'Aktif', '$api_key', '$sess_username', '$terdaftar', '0','')") == true) {
			$conn->query("INSERT INTO history_saldo VALUES ('', '$sess_username', 'Pengurangan Saldo', '".$datanya['harga']."', 'Penambahan Pengguna Dengan Username $username level $level ', '$date', '$time')");	
			$conn->query("UPDATE users SET saldo = saldo - ".$datanya['harga'].", pemakaian_saldo = pemakaian_saldo + ".$datanya['harga']." WHERE username = '$sess_username'");
			$_SESSION['hasil'] = array(
				'alert' => 'success', 
				'judul' => 'Permintaan Berhasil', 
				'pesan' => '
				Pengguna Baru Berhasil Ditambahkan <br />
				Email: '.$email.' <br />
				Username: '.$username.' <br />
				Password: '.$password.' <br />
				Level: '.$level.' <br />
				Saldo : '.$datanya['bonus'].' <br />
				');
		} else {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Permintaan Gagal', 'pesan' => 'Permintaan Gagal');
		}
	}
}
require '../lib/header.php';
?>

<!--Title-->
<title>Tambah Pengguna</title>
<meta name="description" content="Platform Layanan Digital All in One, Berkualitas, Cepat & Aman. Menyediakan Produk & Layanan Pemasaran Sosial Media, Payment Point Online Bank, Layanan Pembayaran Elektronik, Optimalisasi Toko Online, Voucher Game dan Produk Digital."/>

<div class="row">
	<div class="offset-lg-2 col-lg-8">
		<div class="card">
			<div class="card-body">
				<h4 class="m-t-0 text-uppercase text-center header-title"><i class="mdi mdi-account-plus text-primary"></i> Tambah Pengguna</h4><hr>
				<form class="form-horizontal" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">  		<div class="form-group">
						<label class="col-md-2 control-label">Level</label>
						<div class="col-md-12">
							<select class="form-control" name="level" id="level" required>
								<option value="">Pilih Salah Satu</option>
								<?php if ($data_user['level'] == "Member"){ ?>
								<?php } else if ($data_user['level'] == "Agen") { ?>
									<option value="Member">Member</option>
								<?php } else if ($data_user['level'] == "Reseller") { ?>
									<option value="Member">Member</option>
									<option value="Agen">Agen</option>
								<?php } else if ($data_user['level'] == "Admin") { ?>
									<option value="Member">Member</option>
									<option value="Agen">Agen</option>
									<option value="Reseller">Reseller</option>
								<?php } else if ($data_user['level'] == "Developers") { ?>
									<option value="Member">Member</option>
									<option value="Agen">Agen</option>
									<option value="Reseller">Reseller</option>
									<option value="Admin">Admin</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div id="catatan">
					</div>											
					<div class="form-group">
						<label class="col-md-12 control-label">Email</label>
						<div class="col-md-12">
							<input type="email" name="email" class="form-control" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12 control-label">Username</label>
						<div class="col-md-12">
							<input type="text" name="username" class="form-control" placeholder="Username">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-12 control-label">Password</label>
						<div class="col-md-12">
							<input type="text" name="password" class="form-control" placeholder="Password">
						</div>
					</div>											
					<div class="col-md-12">
						<button type="submit" class="pull-right btn btn-block btn--md btn-primary waves-effect waves-light" name="tambah"><i class="mdi mdi-account-plus"></i> Tambah Pengguna</button>
					</div> 
				</form>
			</div>
		</div>
	</div> <!-- end col -->
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#level").change(function() {
			var level = $("#level").val();
			$.ajax({
				url: '<?php echo $config['web']['url'];?>ajax/tambah-pengguna.php',
				data: 'level=' + level,
				type: 'POST',
				dataType: 'html',
				success: function(msg) {
					$("#catatan").html(msg);
				}
			});
		});
	});
</script>

<?php
require '../lib/footer.php';