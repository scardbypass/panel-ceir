<?php
session_start();
include '../config.php';

if (isset($_SESSION['user'])) {
	header("Location: ".$config['web']['url']);
} else {
	if (isset($_POST['login'])) {
		if(isset($_POST['g-recaptcha-response'])){
			$captcha=$_POST['g-recaptcha-response'];
		}
		if(!$captcha){
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Lakukan Verifikasi Captcha');
			exit(header("location: login"));
		}
		
		$secret_key = $config['captcha']['secretkey'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . $captcha;
		$response = file_get_contents($url);
		$responseKeys = json_decode($response,true);

		$post_username = $conn->real_escape_string(trim(filter($_POST['username'])));
		$post_password = $conn->real_escape_string(trim(filter($_POST['password'])));
		$check_user = $conn->query("SELECT * FROM users WHERE username = '$post_username'");
		$check_user_rows = mysqli_num_rows($check_user);
		$data_user = mysqli_fetch_assoc($check_user);
		$verif_pass = password_verify($post_password, $data_user['password']);
		if (!$post_username || !$post_password) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Username<br/> - Password');
		} else if ($check_user_rows == 0) {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Akun Tidak Ditemukan');
		} else if ($data_user['status'] == "Tidak Aktif") {
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Akun Telah Dinonaktifkan');
		} else {
			if ($check_user_rows == 1){
				if ($verif_pass == true) {

					// Generate tokenuser
					function getToken($length){
						$tokenuser = "";
						$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
						$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
						$codeAlphabet.= "0123456789";
				  		$max = strlen($codeAlphabet); // edited
				  		for ($i=0; $i < $length; $i++) {
				  			$tokenuser .= $codeAlphabet[random_int(0, $max-1)];
				  		}
			  			return $tokenuser;
				  	}

				  	// Generate session
					$tokenuser = getToken(10);
					$_SESSION['user'] = $data_user;
					$_SESSION['tokenuser'] = $tokenuser;
					$tanggal = date("Y-m-d H:i:s");

					$result_token = $conn->query("SELECT count(*) as allcount FROM users_token WHERE username='".$post_username."' ");
					$row_token = mysqli_fetch_assoc($result_token);

					// Update token user
					if($row_token['allcount'] > 0) {
						$conn->query("UPDATE users_token SET tokenuser='".$tokenuser."' WHERE username='".$post_username."'");
						$conn->query("INSERT INTO log VALUES ('','$post_username', 'Login', '".get_client_ip()."','$date','$time')");
						exit(header("Location: ".$config['web']['url']));
					// Insert token user
					} else {
						$conn->query("INSERT INTO users_token VALUES ('','$post_username', '".$tokenuser."', '$tanggal')");
						$conn->query("INSERT INTO log VALUES ('','$post_username', 'Login', '".get_client_ip()."','$date','$time')");
						exit(header("Location: ".$config['web']['url']));
					}
				} else {
					$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Username / Password Salah');
				}
			}
		}
	}

}

require '../lib/header.php';
?>

<!--Title-->
<title>Login <?php echo $data['short_title']; ?></title>
<meta name="description" content="Login Member Area <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>

<!--OG2-->
<meta content="Login <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Login Member Area <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="Login <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Login <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Login Member Area <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row justify-content-center">
	<div class="col-md-8 col-lg-6 col-xl-5">
		<div class="card-box">
			<h1 class="m-t-0 m-b-30 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-user"></i> Login <?php echo $data['short_title']; ?></h1>
			<div style="text-align: center;">
				<img src="/assets/images/kincaimedia/webkmpanelblack.png" alt="Login <?php echo $data['short_title']; ?>" title="Login <?php echo $data['short_title']; ?>" width="200">
			</div>
			<form class="form-horizontal" role="form" method="POST">
				<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
				<form id="login" class="form-horizontal m-t-20" role="form" method="POST">
					<div class="form-group">
						<label>Username</label>
						<input id="user" name="username" type="text" class="form-control" placeholder="Username" autocomplete="username">
					</div>
					<div class="form-group">
						<label>Password</label>
						<span class="badge badge-success float-right" id="mybtn" type="button" onclick="change()"><i class="mdi mdi-eye"></i> Show
						</span>
						<input id="pass" name="password" type="password" class="form-control" placeholder="Password" autocomplete="current-password">
					</div>
					<div class="form-group">
						<!--sitekey google apikey-->
						<small>Memastikan bahwa anda bukan robot.</small>
						<div class="g-recaptcha" data-sitekey="<?php echo $config['captcha']['sitekey'] ?>"></div>
						<button id="login" name="login" type="submit" class="btn btn-success btn-block"> Login</button>
						<br/>
						<div style="text-align: center;">
							<i aria-hidden="true" class="fa fa-key"></i> Lupa password?
							<a href="lupa-password" class="btn btn-danger btn-block">Reset</a>
							<br/>
							<i aria-hidden="true" class="fa fa-user-plus "></i> Belum punya akun?
							<a href="register" class="btn btn-primary btn-block">Register</a>
						</div>
					</div>                     
				</form>
			</form>
		</div>                      
	</div> <!-- end col -->
</div>
<!-- end row -->

<script type="text/javascript">
	//Show hide password
	function change()
	{
		var x = document.getElementById('pass').type;
		if (x == 'password')
		{
			document.getElementById('pass').type = 'text';
			document.getElementById('mybtn').innerHTML = '<i class="mdi mdi-eye-off"></i> Hide';
		}
		else
		{
			document.getElementById('pass').type = 'password';
			document.getElementById('mybtn').innerHTML = '<i class="mdi mdi-eye"></i> Show';
		}
	}
</script>

<?php
require '../lib/footer.php';
?>