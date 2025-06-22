<?php
	date_default_timezone_set('Asia/Jakarta');
	error_reporting(0);

	// status
	$maintenance = 0; //Ganti jadi 1 jika sedang MT
	if($maintenance == 1) {
		header("location: /offline"); //Ganti dengan landing page maintenance Anda
	}

	// database
	$config['db'] = array(
		'host' => 'localhost',
		'name' => 'pabr8932_gmj', //Ganti dengan nama database anda
		'username' => 'pabr8932_gmj', //Ganti dengan username database anda
		'password' => '829341@Scard' //Ganti dengan password database anda
	);

	$conn = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['name']);
	if(!$conn) {
		die("Koneksi Gagal : ".mysqli_connect_error());
	}

	// Konfigurasi url domain
	$config['web'] = array(
		'url' => 'https://gmjroamer.my.id/', //diakhiri garis miring. ex: https://ceirgo.id/
		'url_canonical' => 'hhttps://gmjroamer.my.id' //tanpa garis miring. ex: https://ceirgo.id
	);

	// date & time
	$date = date("Y-m-d");
	$time = date("H:i:s");

	// API google captcha v2 Checkbox: https://www.google.com/recaptcha/admin/create
	$config['captcha'] = array(
		'sitekey' => '6LdpOj8qAAAAAPoXpPJDWd2MhRkWJdFLv1_G-MDH',
		'secretkey' => '6LdpOj8qAAAAAH8V0Hn96Gk5mN0moBS9OuMhcu8H'
	);

	// Email SMTP, Keamanan SSL, PORT 465
	// EMAIL MAILER INI DIREKOMENDASIKAN MENGGUNAKAN EMAIL SMTP HOSTING
	// INFORMASI PORT DAN HOST DIDAPAT SETELAH MEMBUAT EMAIL DI CPANEL
	$config['email'] = array(
		'enkripsi' => 'ssl', //ssl atau tls, direkomendasikan ssl
		'mailhost' => 'mail.opotopup.com', //host mail, ex: mail.kincaiseluler.my.id
		'mailport' => '465', //port email, ex: 465
		'mailusername' => 'verifikasi@opotopup.com', //email, ex: support@kincaiseluler.my.id
		'mailpassword' => 'Rahasiaku123' //password email
	);

	// versi
	$versi = '1';

	require("lib/function.php");

?>