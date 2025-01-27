<?php
session_start();
require '../config.php';

if (isset($_SESSION['user'])) {
	header("Location: ".$config['web']['url']);
} else {
	if (isset($_POST['daftar'])) {
		if(isset($_POST['g-recaptcha-response'])){
			$captcha=$_POST['g-recaptcha-response'];
		}
		if(!$captcha){
			$_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Pendaftaran Gagal', 'pesan' => 'Lakukan Verifikasi Captcha');
			exit(header("location: register"));
		}
		$secret_key = $config['captcha']['secretkey'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secret_key) . '&response=' . $captcha;
		$response = file_get_contents($url);
		$responseKeys = json_decode($response,true);

		if (daftar($_POST) > 0) {
			$_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Pendaftaran Berhasil!', 'pesan' => 'Silakan Login Akun Anda');
			exit(header("location: login"));
		}
		else {
			echo mysqli_error($conn);
		}
	}
}

require '../lib/header.php';
?>

<!--Title-->
<title>Register <?php echo $data['short_title']; ?></title>
<meta name="description" content="Registrasi Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>"/>

<!--OG2-->
<meta content="Registrasi <?php echo $data['short_title']; ?>" property="og:title"/>
<meta content="Registrasi Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="og:description"/>
<meta content="Registrasi <?php echo $data['short_title']; ?>" property="og:headline"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="og:image"/>
<meta content="Registrasi <?php echo $data['short_title']; ?>" property="twitter:title"/>
<meta content="Registrasi Akun <?php echo $data['short_title']; ?> - <?php echo $data['title']; ?>" property="twitter:description"/>
<meta content="<?php echo $config['web']['url'];?>assets/images/halaman/tentang-kami.png" property="twitter:image"/>

<div class="row justify-content-center">
	<div class="col-md-8 col-lg-6 col-xl-5">
		<div class="card-box">
			<h1 class="m-t-0 m-b-30 text-uppercase text-center header-title"><i aria-hidden="true" class="fa fa-user-plus"></i> Register <?php echo $data['short_title']; ?></h1>
			<div style="text-align: center;">
				<img src="/assets/images/kincaimedia/webkmpanelblack.png" alt="Login <?php echo $data['short_title']; ?>" title="Login <?php echo $data['short_title']; ?>" width="200">
			</div>
			<form class="form-horizontal" role="form" method="POST">
				<input type="hidden" name="csrf_token" value="<?php echo $config['csrf_token'] ?>">
				<form id="daftar" class="form-horizontal m-t-20" role="form" method="POST">
					<div class="form-group">
						<label>Nama</label>
						<input id="nama" type="text" name="nama" class="form-control" placeholder="Nama Lengkap" autocomplete="name" onkeyup="saveValue(this);">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input id="email" type="email" name="email" class="form-control" placeholder="Email Aktif" autocomplete="email" onkeyup="saveValue(this);">
					</div>
					<div class="form-group">
						<label>No Whatsapp</label>
						<input id="no_hp" type="number" name="no_hp" class="form-control" placeholder="Whatsapp Aktif" autocomplete="no_hp" onkeyup="saveValue(this);">
					</div>
					<div class="form-group">
						<label>Username</label>
						<input id="user" type="text" name="username" class="form-control" placeholder="Username" autocomplete="username" onkeyup="saveValue(this);">
					</div>
					<div class="form-group">
						<label>Password</label>
						<span class="badge badge-success float-right" id="mybtn" type="button" onclick="change()"><i class="mdi mdi-eye"></i> Show
						</span>
						<input id="pass" type="password" name="password" class="form-control" placeholder="Password" autocomplete="new-password" onkeyup="saveValue(this);">
					</div>
					<div class="form-group">
						<label>Ulangi Password</label>
						<input id="pass2" type="password" name="password2" class="form-control" placeholder="Konfirmasi Password" autocomplete="new-password" onkeyup="saveValue(this);">
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<!--sitekey google apikey-->
							<small>Memastikan bahwa anda bukan robot.</small>
							<div class="g-recaptcha" data-sitekey="<?php echo $config['captcha']['sitekey'] ?>"></div>
							<button type="submit" class="btn btn-primary btn-block" name="daftar"> Register</button>
							<br>
							<div style="text-align: center;">
								<i aria-hidden="true" class="fa fa-user"></i> Sudah punya akun?
								<a href="login" class="btn btn-success btn-block">Login</a>
							</div>
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
		var x = document.getElementById('pass','pass2').type;
		if (x == 'password')
		{
			document.getElementById('pass').type = 'text';
			document.getElementById('pass2').type = 'text';
			document.getElementById('mybtn').innerHTML = '<i class="mdi mdi-eye-off"></i> Hide';
		}
		else
		{
			document.getElementById('pass').type = 'password';
			document.getElementById('pass2').type = 'password';
			document.getElementById('mybtn').innerHTML = '<i class="mdi mdi-eye"></i> Show';
		}
	}
</script>

<script type="text/javascript">
	//No space input
	$("#email,#user,#pass,#pass2").on({
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

<script type="text/javascript">
	//Save input value after refresh page
	//Set the value to this input
    document.getElementById("nama").value = getSavedValue("nama");
    document.getElementById("no_hp").value = getSavedValue("no_hp");
    document.getElementById("email").value = getSavedValue("email");
    document.getElementById("user").value = getSavedValue("user");
    document.getElementById("pass").value = getSavedValue("pass");
    document.getElementById("pass2").value = getSavedValue("pass2");
    //Save the value function - save it to localStorage as (ID, VALUE)
    function saveValue(e) {
    	// get the sender's id to save it.
        var id = e.id;
        // get the value.
        var val = e.value;
        // every time user writing something, the localStorage's value will override.
        localStorage.setItem(id, val);
    }
    //Get the saved value function - return the value of "v" from localStorage. 
    function getSavedValue(v) {
        if (!localStorage.getItem(v)) {
        	// You can change this to your defualt value.
            return "";
        }
        return localStorage.getItem(v);
    }
</script>

<?php
require '../lib/footer.php';
?>