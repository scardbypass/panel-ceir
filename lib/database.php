<?php
declare(strict_types=1);

/**
 * Shared legacy database bootstrap.
 * Optional/legacy tables may not exist on a fresh installation, so every
 * aggregate query fails closed instead of passing false to fetch_assoc().
 */
if (!isset($conn) || !($conn instanceof mysqli)) {
    error_log('Panel CEIR database.php loaded without a valid mysqli connection.');
    return;
}

if (!function_exists('panel_db_query')) {
    function panel_db_query(mysqli $db, string $sql): mysqli_result|bool
    {
        $result = $db->query($sql);
        if ($result === false) {
            error_log('Panel CEIR SQL error: ' . $db->error . ' | SQL: ' . preg_replace('/\s+/', ' ', $sql));
        }
        return $result;
    }
}

if (!function_exists('panel_db_count')) {
    function panel_db_count(mysqli $db, string $table, string $where = '1=1'): int
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $table) || !preg_match('/^[A-Za-z0-9_ =\'().<>-]+$/', $where)) return 0;
        $result = panel_db_query($db, "SELECT COUNT(*) AS total FROM `{$table}` WHERE {$where}");
        if (!$result) return 0;
        $row = $result->fetch_assoc();
        $result->free();
        return (int)($row['total'] ?? 0);
    }
}

if (!function_exists('panel_db_sum')) {
    function panel_db_sum(mysqli $db, string $table, string $column, string $where = '1=1'): float
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $table) || !preg_match('/^[A-Za-z0-9_]+$/', $column)) return 0.0;
        $result = panel_db_query($db, "SELECT COALESCE(SUM(`{$column}`),0) AS total FROM `{$table}` WHERE {$where}");
        if (!$result) return 0.0;
        $row = $result->fetch_assoc();
        $result->free();
        return (float)($row['total'] ?? 0);
    }
}

$data = ['short_title' => 'SCARD PROJECT', 'title' => 'SCARD PROJECT'];
$q = panel_db_query($conn, "SELECT * FROM `setting_web` WHERE id=1 LIMIT 1");
if ($q) {
    $row = $q->fetch_assoc();
    $q->free();
    if (is_array($row)) $data = array_merge($data, $row);
}

$sess_username = trim((string)($sess_username ?? ($_SESSION['user']['username'] ?? '')));
$data_user = [
    'username' => $sess_username,
    'nama' => '',
    'email' => '',
    'no_hp' => '',
    'saldo' => 0,
    'level' => 'Member',
    'status' => 'Aktif',
    'api_key' => '',
];
if ($sess_username !== '') {
    $safeUser = $conn->real_escape_string($sess_username);
    $q = panel_db_query($conn, "SELECT * FROM `users` WHERE username='{$safeUser}' LIMIT 1");
    if ($q) {
        $row = $q->fetch_assoc();
        $q->free();
        if (is_array($row)) $data_user = array_merge($data_user, $row);
    }
}

// ===== Admin/dashboard counters =====
$layanan_sosmed = panel_db_count($conn, 'layanan_sosmed');
$layanan_sosmed2 = panel_db_count($conn, 'layanan_sosmed2');
$layanan_sosmed3 = panel_db_count($conn, 'layanan_sosmed3');
$layanan_pulsa = panel_db_count($conn, 'layanan_pulsa');
$layanan_digital = panel_db_count($conn, 'layanan_digital');
$total_layanan = $layanan_sosmed + $layanan_sosmed2 + $layanan_sosmed3 + $layanan_pulsa + $layanan_digital;
$total_pengguna = panel_db_count($conn, 'users');

$jumlah_deposit_bank_member = panel_db_count($conn, 'deposit_bank', "status='Success'");
$jumlah_deposit_emoney_member = panel_db_count($conn, 'deposit_emoney', "status='Success'");
$jumlah_deposit_epayment_member = panel_db_count($conn, 'deposit_epayment', "status='Success'");
$jumlah_deposit_tsel_member = panel_db_count($conn, 'deposit_tsel', "status='Success'");
$jumlah_deposit_voucher_member = panel_db_count($conn, 'voucher', "status='sudah di redeem'");
$jumlah_deposit_lain_member = panel_db_count($conn, 'deposit', "status='Success'");
$jumlah_deposit_member = $jumlah_deposit_bank_member + $jumlah_deposit_emoney_member + $jumlah_deposit_epayment_member + $jumlah_deposit_tsel_member + $jumlah_deposit_voucher_member + $jumlah_deposit_lain_member;

$data_deposit_bank = ['total' => panel_db_sum($conn, 'deposit_bank', 'jumlah_transfer', "status='Success'")];
$data_deposit_emoney = ['total' => panel_db_sum($conn, 'deposit_emoney', 'jumlah_transfer', "status='Success'")];
$data_deposit_epayment = ['total' => panel_db_sum($conn, 'deposit_epayment', 'jumlah_transfer', "status='Success'")];
$data_deposit_tsel = ['total' => panel_db_sum($conn, 'deposit_tsel', 'jumlah_transfer', "status='Success'")];
$data_deposit_voucher = ['total' => panel_db_sum($conn, 'voucher', 'saldo', "status='sudah di redeem'")];
$data_deposit = ['total' => panel_db_sum($conn, 'deposit', 'jumlah_transfer', "status='Success'")];

$jumlah_pesanan_pulsa = panel_db_count($conn, 'pembelian_pulsa', "status='Success'");
$jumlah_pesanan_sosmed = panel_db_count($conn, 'pembelian_sosmed', "status='Success'");
$data_pesanan_pulsa = ['total' => panel_db_sum($conn, 'pembelian_pulsa', 'harga', "status='Success'")];
$data_pesanan_sosmed = ['total' => panel_db_sum($conn, 'pembelian_sosmed', 'harga', "status='Success'")];
$data_saldo_member = ['total' => panel_db_sum($conn, 'users', 'saldo', "level IN ('Member','Agen','Reseller')")];
$data_transaksi_member = ['total' => panel_db_sum($conn, 'users', 'pemakaian_saldo', "level IN ('Member','Agen','Reseller')")];

$month = date('m');
$year = date('Y');
$ProfitPulsa = ['total' => panel_db_sum($conn, 'pembelian_pulsa', 'profit', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'")];
$AllPulsa = ['total' => panel_db_sum($conn, 'pembelian_pulsa', 'harga', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'")];
$CountProfitPulsa = panel_db_count($conn, 'pembelian_pulsa', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'");
$ProfitSosmed = ['total' => panel_db_sum($conn, 'pembelian_sosmed', 'profit', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'")];
$AllSosmed = ['total' => panel_db_sum($conn, 'pembelian_sosmed', 'harga', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'")];
$CountProfitSosmed = panel_db_count($conn, 'pembelian_sosmed', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'");
$ProfitDigital = ['total' => panel_db_sum($conn, 'pembelian_digital', 'profit', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'")];
$AllDigital = ['total' => panel_db_sum($conn, 'pembelian_digital', 'harga', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'")];
$CountProfitDigital = panel_db_count($conn, 'pembelian_digital', "MONTH(`date`)='{$month}' AND YEAR(`date`)='{$year}'");

// ===== Current user counters =====
$u = $conn->real_escape_string($sess_username);
$jumlah_order_sosmed = panel_db_count($conn, 'pembelian_sosmed', "user='{$u}'");
$jumlah_order_pulsa = panel_db_count($conn, 'pembelian_pulsa', "user='{$u}'");
$jumlah_order_digital = panel_db_count($conn, 'pembelian_digital', "user='{$u}'");
$jumlah_deposit_lain = panel_db_count($conn, 'deposit', "username='{$u}' AND status='Success'");
$jumlah_deposit_bank = panel_db_count($conn, 'deposit_bank', "username='{$u}' AND status='Success'");
$jumlah_deposit_emoney = panel_db_count($conn, 'deposit_emoney', "username='{$u}' AND status='Success'");
$jumlah_deposit_epayment = panel_db_count($conn, 'deposit_epayment', "username='{$u}' AND status='Success'");
$jumlah_deposit_tsel = panel_db_count($conn, 'deposit_tsel', "username='{$u}' AND status='Success'");
$jumlah_deposit_voucher = panel_db_count($conn, 'deposit_voucher', "username='{$u}' AND status='Success'");
$jumlah_deposit_user = $jumlah_deposit_lain + $jumlah_deposit_bank + $jumlah_deposit_emoney + $jumlah_deposit_epayment + $jumlah_deposit_tsel + $jumlah_deposit_voucher;

$data_order_sosmed = ['total' => panel_db_sum($conn, 'pembelian_sosmed', 'harga', "user='{$u}'")];
$data_order_pulsa = ['total' => panel_db_sum($conn, 'pembelian_pulsa', 'harga', "user='{$u}'")];
$data_order_digital = ['total' => panel_db_sum($conn, 'pembelian_digital', 'harga', "user='{$u}'")];

// Empty result set for legacy pages. These names are populated with real queries
// only when the corresponding table/query succeeds.
$empty = $conn->query('SELECT 1 WHERE 0');
$legacyResultNames = [
    'CallDBDepositBankPending','CallDBDepositEmoneyPending','CallDBDepositEpaymentPending','CallDBDepositTselPending','CallDBDepositOperPending',
    'AllOrderSosmedPending','AllOrderSosmedProcessing','AllOrderSosmedInprogress','AllOrderSosmedError','AllOrderSosmedPartial','AllOrderSosmedSuccess',
    'AllOrderPulsaPending','AllOrderPulsaProcessing','AllOrderPulsaInprogress','AllOrderPulsaError','AllOrderPulsaPartial','AllOrderPulsaSuccess',
    'AllOrderDigitalPending','AllOrderDigitalProcessing','AllOrderDigitalError','AllOrderDigitalPartial','AllOrderDigitalSuccess',
    'CallDBOrderSosmedPending','CallDBOrderSosmedProcessing','CallDBOrderSosmedError','CallDBOrderSosmedPartial','CallDBOrderSosmedSuccess',
    'CallDBOrderPulsaPending','CallDBOrderPulsaProcessing','CallDBOrderPulsaError','CallDBOrderPulsaPartial','CallDBOrderPulsaSuccess',
    'CallDBOrderDigitalPending','CallDBOrderDigitalProcessing','CallDBOrderDigitalError','CallDBOrderDigitalPartial','CallDBOrderDigitalSuccess',
    'AllDepositUsersBank','AllDepositUsersEmoney','AllDepositUsersEpayment','AllDepositUsersTsel','AllDepositUsersVoucher','AllDepositUsersLain',
    'AllTiketUsersClosed','AllTiketUsersPending','AllTiketUsersOpen','AllTiketUsersProcessing','AllTiketUsersSolved',
];
foreach ($legacyResultNames as $name) ${$name} = $empty;

// Contact page fallback.
$PageContact = [];
$q = panel_db_query($conn, "SELECT * FROM halaman WHERE id='1'");
if ($q) {
    $PageContact = $q->fetch_assoc() ?: [];
    $q->free();
}

// User deposit pending result sets.
$pendingQueries = [
    'CallDBDepositBankPending' => "SELECT * FROM deposit_bank WHERE username='{$u}' AND status='Pending'",
    'CallDBDepositEmoneyPending' => "SELECT * FROM deposit_emoney WHERE username='{$u}' AND status='Pending'",
    'CallDBDepositEpaymentPending' => "SELECT * FROM deposit_epayment WHERE username='{$u}' AND status='Pending'",
    'CallDBDepositTselPending' => "SELECT * FROM deposit_tsel WHERE username='{$u}' AND status='Pending'",
    'CallDBDepositOperPending' => "SELECT * FROM deposit WHERE username='{$u}' AND status='Pending'",
];
foreach ($pendingQueries as $name => $sql) {
    $q = panel_db_query($conn, $sql);
    if ($q) ${$name} = $q;
}
?>