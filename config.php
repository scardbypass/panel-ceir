<?php
	date_default_timezone_set('Asia/Jakarta');
	error_reporting(0);

	// status
	$maintenance = (int) (getenv('APP_MAINTENANCE') !== false ? getenv('APP_MAINTENANCE') : 0);
	if($maintenance == 1) {
		header("location: /offline");
		exit;
	}

	// database - configure through VPS environment variables (.env is not committed)
	$config['db'] = array(
		'host' => getenv('DB_HOST') ?: 'localhost',
		'name' => getenv('DB_NAME') ?: '',
		'username' => getenv('DB_USERNAME') ?: '',
		'password' => getenv('DB_PASSWORD') ?: ''
	);

	$conn = mysqli_connect($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['name']);
	if(!$conn) {
		die("Koneksi Gagal : ".mysqli_connect_error());
	}

	// Konfigurasi URL domain
	$config['web'] = array(
		'url' => rtrim(getenv('APP_URL') ?: 'http://localhost/', '/') . '/',
		'url_canonical' => rtrim(getenv('APP_CANONICAL_URL') ?: 'http://localhost', '/')
	);

	// date & time
	$date = date("Y-m-d");
	$time = date("H:i:s");

	// Google reCAPTCHA v2 Checkbox
	$config['captcha'] = array(
		'sitekey' => getenv('RECAPTCHA_SITEKEY') ?: '',
		'secretkey' => getenv('RECAPTCHA_SECRETKEY') ?: ''
	);

	// Email SMTP
	$config['email'] = array(
		'enkripsi' => getenv('SMTP_ENCRYPTION') ?: 'ssl',
		'mailhost' => getenv('SMTP_HOST') ?: '',
		'mailport' => getenv('SMTP_PORT') ?: '465',
		'mailusername' => getenv('SMTP_USERNAME') ?: '',
		'mailpassword' => getenv('SMTP_PASSWORD') ?: ''
	);

	// versi
	$versi = getenv('APP_VERSION') ?: '2';

	require("lib/function.php");
?>
