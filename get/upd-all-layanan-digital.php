<!--Viewport -->
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<h3>
    <div style="text-align: center;">
        <a href="../admin-dashboard/action-provider"><b>Kembali</b></a><br/>
    </div>
</h3>

<?php
session_start();
require_once ("../config.php");
require_once ("../lib/session_login_admin.php");

$cek_harga_website = $conn->query("SELECT * FROM setting_profit WHERE kategori = 'WEBSITE' AND tipe = 'Digital'");
$data_harga_website = mysqli_fetch_assoc($cek_harga_website);

$cek_harga_api = $conn->query("SELECT * FROM setting_profit WHERE kategori = 'API' AND tipe = 'Digital'");
$data_harga_api = mysqli_fetch_assoc($cek_harga_api);

$harga_website_profit = $data_harga_website['harga'];
$harga_api_profit = $data_harga_api['harga'];

$cekarie = $conn->query("SELECT * FROM provider WHERE code = 'ceirgo'");
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
    $no = 1; 
    foreach ($json_result['data'] as $item) {
        $id = $item['sid'];
        $oprator = $item['operator'];
        $name = $item['layanan'];
        $price = $item['harga'];
        $tipe = $item['tipe'];
        $catatan = $item['catatan'];
        $status = $item['status'];
        $provider = "ceirgo";
        
        $rate_asli = $price * $harga_website_profit; // Menghitung harga berdasarkan profit website
        $harga_api = $price * $harga_api_profit;     // Menghitung harga berdasarkan profit API
        $status_asli = htmlspecialchars($status);

        // Cek apakah layanan sudah ada di database
        $check_layanan_digital = mysqli_query($conn, "SELECT * FROM layanan_digital WHERE provider_id = '$id' AND provider = '$provider'");
        
        if (mysqli_num_rows($check_layanan_digital) > 0) {
            // Update data jika sudah ada
            $update = mysqli_query($conn, "UPDATE layanan_digital SET harga = '$rate_asli', harga_api = '$harga_api', catatan = '$catatan', status = '$status_asli' WHERE provider_id = '$id' AND provider = '$provider'");
            if ($update) {
                echo "<b>Produk & Harga Berhasil Diupdate</b> <br/>
                Provider ID: " . htmlspecialchars($id) . " <br/>
                Operator: " . htmlspecialchars($oprator) . " <br/>
                Nama: " . htmlspecialchars($name) . ".<br/>
                Status Provider: " . htmlspecialchars($status) . " <br/>
                Harga Provider: " . htmlspecialchars($price) . " <br/>
                Tipe: " . htmlspecialchars($tipe) . ".<br/>
                Catatan: " . htmlspecialchars($catatan) . " <br/>
                Provider: " . htmlspecialchars($provider) . " <br/>
                Harga Web: " . htmlspecialchars($rate_asli) . " <br/>
                Harga Api: " . htmlspecialchars($harga_api) . " <br/>
                Status Web: " . htmlspecialchars($status_asli) . " <br/><br/>";
            } else {
                echo "<b>Produk & Harga Gagal Diupdate</b>: " . mysqli_error($conn) . "<br/>";
            }
        } else {
            // Insert data jika belum ada
            $insert = mysqli_query($conn, "INSERT INTO layanan_digital(service_id, provider_id, operator, layanan, harga, harga_api, status, provider, tipe, catatan) VALUES ('$id','$id','$oprator','$name','$rate_asli','$harga_api','$status_asli','$provider','$tipe','$catatan')");
            if ($insert) {
                echo "<b>Produk Berhasil Disimpan</b> <br/>
                Provider ID: " . htmlspecialchars($id) . " <br/>
                Operator: " . htmlspecialchars($oprator) . " <br/>
                Nama: " . htmlspecialchars($name) . " <br/>
                Status Provider: " . htmlspecialchars($status) . " <br/>
                Harga Provider: " . htmlspecialchars($price) . " <br/>
                Tipe: " . htmlspecialchars($tipe) . " <br/>
                Catatan: " . htmlspecialchars($catatan) . " <br/>
                Provider: " . htmlspecialchars($provider) . " <br/>
                Harga Web: " . htmlspecialchars($rate_asli) . " <br/>
                Harga Api: " . htmlspecialchars($harga_api) . " <br/>
                Status Web: " . htmlspecialchars($status_asli) . " <br/><br/>";
            } else {
                echo "<b>Produk Gagal Disimpan</b> <br/>" . mysqli_error($conn) . "<br/>";
            }
        }
    }
} else {
    echo "<b>Gagal mendapatkan data dari API.</b><br/>";
}
?>
