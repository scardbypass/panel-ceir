<?php
session_start();
require '../config.php';
require '../lib/class.phpmailer.php';

if (isset($_SESSION['user'])) {
	header("Location: ".$config['web']['url']."user/setting");
} else {
	if (isset($_POST['reset'])) {
		if(isset($_POST['g-recaptcha-response'])){
			$captcha=$_POST['g-recaptcha-response'];
		}

		if(!$captcha){
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Lakukan Verifikasi Captcha');
			exit(header("Location: lupa-password"));
		}

		$secret_key = $config['captcha']['secretkey'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . $captcha;
		$response = file_get_contents($url);
		$responseKeys = json_decode($response,true);

		//Call email form validation
		$PostEmail = $conn->real_escape_string(filter(trim($_POST['email'])));
		$cek_email = $conn->query("SELECT * FROM users WHERE email = '$PostEmail'");
		$email = $cek_email->fetch_assoc();

		//Call username form validation
		$PostUsername = $conn->real_escape_string(filter(trim($_POST['username'])));
		$cek_username = $conn->query("SELECT * FROM users WHERE username = '$PostUsername'");
		$user = $cek_username->fetch_assoc();

		//Send new pass to email by username or email method
		$CallPostEmailUsername1 = $conn->real_escape_string(filter(trim($_POST['email'])));
		$CallPostEmailUsername2 = $conn->real_escape_string(filter(trim($_POST['username'])));
		$cek_emailusername = $conn->query("SELECT * FROM users WHERE username = '$CallPostEmailUsername2' OR email = '$CallPostEmailUsername1'");
		$useremail = $cek_emailusername->fetch_assoc();

		//Validation form
		if (!$PostEmail && !$PostUsername) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br /> - Email<br/> <b>Atau</b><br/> - Username');
		} else if ($cek_email->num_rows == 0 && !$PostUsername) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Email <strong>'.$PostEmail.' </strong> Tidak Terdaftar'); 
		} else if ($cek_username->num_rows == 0 && !$PostEmail) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Username <strong>'.$PostUsername.' </strong> Tidak Terdaftar');
		} else if ($cek_email->num_rows == 0 && $cek_username->num_rows == 0) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset Password Gagal', 'pesan' => 'Email <strong>'.$PostEmail.' </strong> atau Username <strong>'.$PostUsername.' </strong> Tidak Terdaftar');
		} else {
			$acakin_password = acak(10).acak_nomor(10);
			$hash_pass = password_hash($acakin_password, PASSWORD_DEFAULT);
			//Email user
			$to = $useremail['email'];

			$mail = new PHPMailer;
            $mail->IsSMTP();
	        $mail->SMTPSecure = $config['email']['enkripsi'];
            $mail->Host = $config['email']['mailhost'];
	        $mail->SMTPDebug = 2;
	        $mail->Port = $config['email']['mailport'];
	        $mail->SMTPAuth = true;
	        $mail->Username = $config['email']['mailusername'];
	        $mail->Password = $config['email']['mailpassword'];
	        $mail->SetFrom($config['email']['mailusername'],$data['short_title']);
            $mail->Subject = "Reset Password Berhasil";
            $mail->AddAddress("$to","");
            $mail->MsgHTML("
            	<p>Hai ".$useremail['username']."</p>
            	<p>Anda telah membuat permintaan <b>Reset Password</b> untuk akun ".$useremail['email'].".</p>
            	<p>Gunakan <b>Kode Unik</b> dibawah ini sebagai password baru, kemudian login dan mengatur ulang kata sandi di menu pengaturan.</p>
            	<p>
            	Email: ".$useremail['email']."<br/>
            	Username: ".$useremail['username']."<br/>
            	Password: $acakin_password<br/>
            	Halaman Login: ".$config['web']['url_canonical']."
            	</p>
            ");
			if ($mail->Send());
				if ($conn->query("UPDATE users SET password = '$hash_pass', random_kode = '$acakin_password' WHERE email = '".$useremail['email']."' ") == true) {
					$_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Reset password berhasil!', 'pesan' => 'Cek email untuk mengetahui password baru');
					exit(header("Location: login"));
				} else {
					$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Reset password gagal!', 'pesan' => 'Permintaan ganti password gagal');
					exit(header("Location: lupa-password"));
				}
			}
		}
	}

require '../lib/header.php';

?>

<!--Title-->
<title>Reset Password <?php echo $data['short_title']; ?></title>
<meta name="description" content="Ganti Password Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>

<!--OG2-->
<meta content="Ganti Password <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Ganti Password Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="Ganti Password <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Ganti Password <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Ganti Password Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row justify-content-center">
	<div class="col-md-8 col-lg-6 col-xl-5">
		<div class="card-box">
			<h1 class="m-t-0 m-b-30 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-key"></i> Reset Password <?php echo $data['short_title']; ?></h1>
			<div style="text-align: center;">
				<img src="/assets/images/kincaimedia/webkmpanelblack.png" alt="Login <?php echo $data['short_title']; ?>" title="Login <?php echo $data['short_title']; ?>" width="200">
			</div>
			<form class="form-horizontal" role="form" method="POST">
				<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
				<form id="reset" class="form-horizontal m-t-20" role="form" method="POST">
					<div class="form-group">
						<label>Email</label>
						<input id="email" type="text" name="email" class="form-control" placeholder="Email" autocomplete="email">
					</div>
					<center><b>ATAU</b></center>
					<div class="form-group">
						<label>Username</label>
						<input id="user" type="text" name="username" class="form-control" placeholder="Username" autocomplete="username">
					</div>
					<div class="form-group">
						<!--sitekey google apikey-->
						<small>Memastikan bahwa anda bukan robot.</small>
						<div class="g-recaptcha" data-sitekey="<?php echo $config['captcha']['sitekey'] ?>"></div>
						<button type="submit" class="btn btn-danger btn-block" name="reset"> Reset</button>
						<br/>
						<div style="text-align: center;">
							<i aria-hidden="true" class="fa fa-user"></i> Sudah ingat password?
							<a href="login" class="btn btn-success btn-block">Login</a>
						</div>
					</div>
				</form>
			</form>
		</div>         
	</div> <!-- end col -->
</div>
<!-- end row -->

<script type="text/javascript">
	//No space input
	$("#email,#user").on({
		keydown: function(e) {
			if (e.which === 32)
				return false;
		},
		keyup: function(){
			this.value = this.value.toLowerCase();
		},
		change: function() {
			this.value = this.value.replace(/\s/g, "");
			
		}
	});
</script>

<?php
require '../lib/footer.php';
?>