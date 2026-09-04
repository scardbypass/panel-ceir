<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

$maintenance = (int) (getenv('APP_MAINTENANCE') !== false ? getenv('APP_MAINTENANCE') : 0);
if ($maintenance === 1) {
    header('Location: /offline');
    exit;
}

// Central application configuration. Production secrets should be supplied by VPS environment variables.
$config['db'] = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'name' => getenv('DB_NAME') ?: 'scar1544_ppob',
    'username' => getenv('DB_USERNAME') ?: 'scar1544_ppob',
    'password' => getenv('DB_PASSWORD') ?: 'scar1544_ppob',
];

// Keep database failures from becoming uncaught mysqli exceptions on legacy pages.
mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect(
    $config['db']['host'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['name']
);

if (!$conn) {
    error_log('Panel CEIR database connection failed: ' . mysqli_connect_errno() . ' - ' . mysqli_connect_error());
    http_response_code(503);
    exit('Layanan database sedang tidak tersedia. Silakan coba lagi beberapa saat.');
}

if (!mysqli_set_charset($conn, 'utf8mb4')) {
    error_log('Panel CEIR could not set database charset to utf8mb4: ' . mysqli_error($conn));
}

$config['web'] = [
    'url' => rtrim(getenv('APP_URL') ?: 'http://scard-project.id/', '/') . '/',
    'url_canonical' => rtrim(getenv('APP_CANONICAL_URL') ?: 'http://scard-project.id', '/'),
];

$date = date('Y-m-d');
$time = date('H:i:s');

$config['email'] = [
    'enkripsi' => getenv('SMTP_ENCRYPTION') ?: 'ssl',
    'mailhost' => getenv('SMTP_HOST') ?: '',
    'mailport' => getenv('SMTP_PORT') ?: '465',
    'mailusername' => getenv('SMTP_USERNAME') ?: '',
    'mailpassword' => getenv('SMTP_PASSWORD') ?: '',
];

$versi = getenv('APP_VERSION') ?: '3';

// Always resolve from project root; pages can safely include config from any directory.
require_once __DIR__ . '/lib/function.php';
?>