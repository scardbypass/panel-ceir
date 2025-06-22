
<!--Viewport -->
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<h3>
    <div style="text-align: center;">
        <a href="../admin-dashboard/action-provider"><b>Kembali</b></a><br/>
    </div>
</h3>

<?php
session_start();
require_once("../config.php");
require_once("../lib/session_login_admin.php");

$cekarie = $conn->query("SELECT * FROM provider WHERE code = 'roamerimei'");
$dataarie = mysqli_fetch_assoc($cekarie);
$action = 'layanan';

$postdata = "api_key=" . urlencode($dataarie['api_key']) . "&action=" . urlencode($action);
$url = 'https://ceirgo.id/api/produk-digital';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

$response = curl_exec($curl);
curl_close($curl);

$json_result = json_decode($response, true);

// Periksa apakah respons API valid
if ($json_result && isset($json_result['data'])) {
    foreach ($json_result['data'] as $item) {
        $kategori = htmlspecialchars($item['operator']);
        $kode = htmlspecialchars($item['operator']);
        $tipe = htmlspecialchars($item['tipe']);

        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("SELECT * FROM kategori_layanan WHERE nama = ? AND tipe = ?");
        $stmt->bind_param("ss", $kategori, $tipe);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<b>Kategori Sudah Ada</b> <br/>
            Kategori: $kategori <br/>
            Kode: $kode <br/>
            Tipe: $tipe <br/><br/>";
        } else {
            // Memasukkan ke Database
            $insert_stmt = $conn->prepare("INSERT INTO kategori_layanan (nama, kode, tipe) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $kategori, $kode, $tipe);
            if ($insert_stmt->execute()) {
                echo "<b>Kategori Disimpan</b> <br/>
                Kategori: $kategori <br/>
                Kode: $kode <br/>
                Tipe: $tipe <br/><br/>";
            } else {
                echo "<b>Kategori Gagal Disimpan</b> <br/>" . mysqli_error($conn) . "<br/>";
            }
        }
        $stmt->close();
    }
} else {
    echo "<b>Gagal mendapatkan data dari API.</b><br/>";
}
?>
