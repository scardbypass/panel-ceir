<?php
require("../config.php");
$cek_pesanan = $conn->query("SELECT * FROM pembelian_otp WHERE status IN ('','Pending','Processing') AND provider = 'ARIETOPUP'");

if (mysqli_num_rows($cek_pesanan) == 0) {
  die("Order Pending Tidak Ditemukan.");
} else {
  while($data_pesanan = $cek_pesanan->fetch_assoc()) {
    $poid =  $data_pesanan['provider_oid'];
    $oid =  $data_pesanan['oid'];
    $id =  $data_pesanan['id'];
    $o_provider =  "ARIETOPUP";

    if ($o_provider == "MANUAL") {
      echo "Order manual<br />";
    } else {

      $cek_provider = $conn->query("SELECT * FROM provider WHERE code = 'ARIETOPUP'");
      $data_provider = $cek_provider->fetch_assoc();
      $action = 'remains';

      if ($o_provider !== "MANUAL") {
        $postdata = "api_key=".$data_provider['api_key']."&action=$action&id=$poid";
      } else {
        die("System error");
      }

           $url = 'https://arietopup.com/api/produk-otp';
                
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
   //     $u_service = $json_result['data']['service'];
  //      $u_phone = $json_result['data']['target'];
        $time = $json_result['data']['sisa_waktu'];
      }

      $update_pesanan = $conn->query("UPDATE pembelian_otp SET remains = '$time' WHERE provider_oid = '$poid' AND provider = 'ARIETOPUP'");
      if ($update_pesanan == TRUE) {
        echo "<b>Status Order Diupdate</b> <br/>
        Provider ID: $poid <br/>
        Sisa Waktu: $time <br/><br/>";
      } else {
        echo "Error database";
      }
    }
  }
}

?>