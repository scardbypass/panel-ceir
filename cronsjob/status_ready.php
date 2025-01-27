<?php
session_start();
require("../config.php");
require '../lib/session_user_ready.php';
require '../lib/session_login_ready.php';

$cek_pesanan = $conn->query("SELECT * FROM pembelian_otp WHERE status IN ('','Pending','') AND refund = '0' AND provider = 'ARIETOPUP' AND user = '$sess_username'" );
$data_pesanan = mysqli_fetch_assoc($cek_pesanan);
$poid =  $data_pesanan['provider_oid'];

$cekdigi = $conn->query("SELECT * FROM provider WHERE code = 'ARIETOPUP'");
$datadigi = mysqli_fetch_assoc($cekdigi);
$p_apikey = $datadigi['api_key'];

$url = 'https://arietopup.com/api/produk-otp';
$postdata = "api_key=$p_apikey&action=ready&id=$poid";


           $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // tambahan curl ipv4
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, POST);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	    	$response = curl_exec($curl);
            curl_close($curl);
			/*
			echo $response;
			*/
            $json_result = json_decode($response, true);

   if ($o_provider !== "MANUAL") {
        $u_id = $json_result['data']['id'];
        $msg = $json_result['data']['catatan'];
      }

     $update_pesanan = $conn->query("UPDATE pembelian_otp SET keterangan = '$msg', status = 'Pending' WHERE oid = '$poid' AND provider = 'ARIETOPUP'");
      if ($update_pesanan == TRUE) {
        echo "<b>Status Order Diupdate</b> <br/>
        Provider ID: $poid <br/>
        Status: Pending <br/>
        Catatan: $msg <br/><br/>";
        exit(header("location: /riwayat/pemesanan-otp"));
      } else {
        echo "Error database";
      }
	
?>