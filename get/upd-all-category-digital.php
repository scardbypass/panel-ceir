<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/session_login_admin.php';

$ceirgo = $conn->query("SELECT api_key FROM provider WHERE LOWER(code)='ceirgo' LIMIT 1");
$provider = $ceirgo ? ($ceirgo->fetch_assoc() ?: null) : null;
if ($ceirgo) $ceirgo->free();
if (!$provider || trim((string)$provider['api_key']) === '') {
    http_response_code(503);
    exit('Provider CEIRGo belum dikonfigurasi.');
}

$postdata = http_build_query(['api_key' => $provider['api_key'], 'action' => 'layanan']);
$curl = curl_init('https://ceirgo.id/api/produk-digital');
curl_setopt_array($curl, [
    CURLOPT_HEADER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postdata,
]);
$response = curl_exec($curl);
$curlError = curl_error($curl);
curl_close($curl);

if ($response === false) exit('<b>Gagal mendapatkan data dari API.</b><br/>' . htmlspecialchars($curlError, ENT_QUOTES, 'UTF-8'));
$json = json_decode($response, true);
if (!is_array($json) || !isset($json['data']) || !is_array($json['data'])) {
    exit('<b>Gagal mendapatkan data dari API.</b><br/>Respons API tidak valid.');
}
?>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<h3><div style="text-align:center"><a href="../admin-dashboard/action-provider"><b>Kembali</b></a></div></h3>
<?php
foreach ($json['data'] as $item) {
    $kategori = trim((string)($item['operator'] ?? ''));
    $kode = $kategori;
    $tipe = trim((string)($item['tipe'] ?? ''));
    if ($kategori === '' || $tipe === '') continue;

    $stmt = $conn->prepare("SELECT id FROM kategori_layanan WHERE nama=? AND tipe=? LIMIT 1");
    if (!$stmt) { echo '<b>Query gagal.</b><br/>'; continue; }
    $stmt->bind_param('ss', $kategori, $tipe);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();

    $safeKategori = htmlspecialchars($kategori, ENT_QUOTES, 'UTF-8');
    $safeKode = htmlspecialchars($kode, ENT_QUOTES, 'UTF-8');
    $safeTipe = htmlspecialchars($tipe, ENT_QUOTES, 'UTF-8');

    if ($exists) {
        echo "<b>Kategori Sudah Ada</b><br/>Kategori: {$safeKategori}<br/>Kode: {$safeKode}<br/>Tipe: {$safeTipe}<br/><br/>";
        continue;
    }

    $insert = $conn->prepare("INSERT INTO kategori_layanan (nama,kode,tipe) VALUES (?,?,?)");
    if ($insert && $insert->bind_param('sss', $kategori, $kode, $tipe) && $insert->execute()) {
        echo "<b>Kategori Disimpan</b><br/>Kategori: {$safeKategori}<br/>Kode: {$safeKode}<br/>Tipe: {$safeTipe}<br/><br/>";
    } else {
        echo '<b>Kategori Gagal Disimpan</b><br/>' . htmlspecialchars($insert ? $insert->error : $conn->error, ENT_QUOTES, 'UTF-8') . '<br/>';
    }
    if ($insert) $insert->close();
}
?>