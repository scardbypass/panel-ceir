<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

$maintenance = (int) (getenv('APP_MAINTENANCE') !== false ? getenv('APP_MAINTENANCE') : 0);
if ($maintenance === 1) {
    header('Location: /offline');
    exit;
}

// Central application configuration. Production secrets should be supplied by VPS environment variables.
$config['db'] = array(
    'host' => getenv('DB_HOST') ?: 'localhost',
    'name' => getenv('DB_NAME') ?: 'scar1544_ppob',
    'username' => getenv('DB_USERNAME') ?: 'scar1544_ppob',
    'password' => getenv('DB_PASSWORD') ?: 'scar1544_pppb'
);

$conn = mysqli_connect(
    $config['db']['host'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['name']
);

if (!$conn) {
    die('Koneksi Gagal : ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');

$config['web'] = array(
    'url' => rtrim(getenv('APP_URL') ?: 'http://scard-project.id/', '/') . '/',
    'url_canonical' => rtrim(getenv('APP_CANONICAL_URL') ?: 'http://scard-project.id', '/')
);

$date = date('Y-m-d');
$time = date('H:i:s');

$config['email'] = array(
    'enkripsi' => getenv('SMTP_ENCRYPTION') ?: 'ssl',
    'mailhost' => getenv('SMTP_HOST') ?: '',
    'mailport' => getenv('SMTP_PORT') ?: '465',
    'mailusername' => getenv('SMTP_USERNAME') ?: '',
    'mailpassword' => getenv('SMTP_PASSWORD') ?: ''
);

$versi = getenv('APP_VERSION') ?: '3';

// Always resolve from project root; pages can safely include config from any directory.
require_once __DIR__ . '/lib/function.php';
?>