<?php
require("../config.php");
$cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE status IN ('','Pending','Processing') AND provider = 'roamerimei'");

if (mysqli_num_rows($cek_pesanan) == 0) {
  die("Order Pending Tidak Ditemukan.");
} else {
  while($data_pesanan = $cek_pesanan->fetch_assoc()) {
    $poid =  $data_pesanan['provider_oid'];
    $oid =  $data_pesanan['oid'];
    $id =  $data_pesanan['id'];
    $o_provider =  $data_pesanan['provider'];

    if ($o_provider == "MANUAL") {
      echo "Order manual<br />";
    } else {

      $cek_provider = $conn->query("SELECT * FROM provider WHERE code = 'roamerimei'");
      $data_provider = $cek_provider->fetch_assoc();
      $action = 'status';

      if ($o_provider !== "MANUAL") {
        $api_postdata = "api_key=".$data_provider['api_key']."&action=$action&id=$poid";
      } else {
        die("System error");
      }

            $url = 'https://roamerimei.xyz/api/produk-digital';
                
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
       //   curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $api_postdata);

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
        $u_status = $json_result['data']['status'];
        $u_catatan = $json_result['data']['catatan'];
      }

      if($u_status == 'Pending'){
        $status = 'Pending';
      }else if($u_status == 'Processing'){
        $status = 'Processing';
      }else if($u_status == 'Error'){
        $status = 'Error';
      }else if($u_status == 'Partial'){
        $status = 'Partial';
      }else if($u_status == 'Success'){
        $status = 'Success';
      }else if($u_status == ''){
        $status = 'Pending';
      }else{
        $status = 'Pending';
      }

      $update_pesanan = $conn->query("UPDATE pembelian_digital SET keterangan = '$u_catatan', status = '$u_status' WHERE provider_oid = '$poid' AND provider = 'roamerimei'");
      if ($update_pesanan == TRUE) {
        echo "<b>Status Order Diupdate</b> <br/>
        Provider ID: $poid <br/>
        Order ID: $oid <br/>
        Status: $u_status <br/>
        Catatan: $u_catatan <br/><br/>";
      } else {
        echo "Error database";
      }
    }
  }
}

?>