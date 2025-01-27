<?php
require("../config.php");
require '../lib/class.phpmailer.php';
require '../lib/database.php';

	$cek_emoney= $conn->query("SELECT * FROM deposit_emoney WHERE status IN ('','','Pending') AND tipe = 'EMoney'");

if (mysqli_num_rows($cek_emoney) == 0) {
  die("Order Pending Tidak Ditemukan.");
} else {
  while($data_emoney = $cek_emoney->fetch_assoc()) {
    $id =  $data_emoney['id'];
    $kodedeposit =  $data_emoney['kode_deposit'];
    $username =  $data_emoney['username'];
    $provider =  $data_emoney['provider'];
    $jumlah =  $data_emoney['jumlah_transfer'];
    $tujuan =  $data_emoney['tujuan'];
    $status =  $data_emoney['status'];


    if($u_status == 'Pending'){
        $status = 'Pending';
      }else if($u_status == 'processing'){
        $status = 'Processing';
      }else if($u_status == 'Gagal'){
        $status = 'Error';
      }else if($u_status == 'partial'){
        $status = 'Partial';
      }else if($u_status == 'Sukses'){
        $status = 'Success';
      }else if($u_status == ''){
        $status = 'Pending';
      }else{
        $status = 'Pending';
      }
	 
	 if ($status == "Pending") {
	      
            $cekwa = $conn->query("SELECT * FROM provider WHERE code = 'WAGATEWAY'");
            $datawa = mysqli_fetch_assoc($cekwa);
            $license = $datawa['api_key'];
            $apiwa = $datawa['api_id'];

          
            $cek_mail = $conn->query("SELECT * FROM setting_web");
            $data_mail = mysqli_fetch_assoc($cek_mail);
	        $nohp = $data_mail['wa_notif'];
			
			
            //NOTIFIKASI WHATSAPP
$token = $license;
$phone = $nohp;
$message = ("*APPROVE DEPOSIT VIA EMONEY $provider*

Member Deposit Via EMONEY $provider
Mohon Segera DiProses Admin ".$data_mail['short_title']."
Member Pasti Akan Menunggu
Segera Cek Mutasi Di $provider
            	
Kode Deposit    : *".$data_emoney['kode_deposit']."*
Username        : *".$data_emoney['username']."*
Bank            : *$provider*
Jumlah          : *Rp $jumlah*
Tujuan Rekening : *$tujuan*
Status          : *$status*
            ");
            
    $url = 'https://wa2.wisender.link/send-message';       
	$curl = curl_init();
	$gateway = [
		'api_key'    => $license,
		'sender'    => $apiwa,
        'number'     => $phone,
        'message'   => $message
			];

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $gateway);

	    	$response = curl_exec($curl);
            curl_close($curl);
			/*
			echo $response;
			*/
	 }

    }
  }

?>