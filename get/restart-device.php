<!--Viewport -->
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<h3>
	<div style="text-align: center;">
		<a href="../admin-dashboard/action-provider"><b>Kembali</b></a><br/>
		<a><b>DEVICE BERHASIL DIRESTART</b></a><br/>
	</div>
</h3>

<?php
session_start();
require_once ("../config.php");
require_once ("../lib/session_login_admin.php");

$cek_mail = $conn->query("SELECT * FROM setting_web");
$data_mail = mysqli_fetch_assoc($cek_mail);

$cekwa = $conn->query("SELECT * FROM provider WHERE code = 'WAGATEWAY'");
$datawa = mysqli_fetch_assoc($cekwa);

            $postdata = "api_key=".$data_mail['license']."&device_key=".$datawa['api_key']."";
            $url = 'https://home.scarddwaget.my.id/api/v1/restart-device';
                
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
       //   curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

	    	$response = curl_exec($curl);
            curl_close($curl);
			/*
			echo $response;
			*/
            $json_result = json_decode($response, true);


?>