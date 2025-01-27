<?php
require("../config.php");
require '../lib/class.phpmailer.php';
require '../lib/database.php';

	$cek_pesanan = $conn->query("SELECT * FROM deposit_bank WHERE status IN ('','','Pending') AND tipe = 'Bank'");

if (mysqli_num_rows($cek_pesanan) == 0) {
  die("Order Pending Tidak Ditemukan.");
} else {
  while($data_pesanan = $cek_pesanan->fetch_assoc()) {
    $id =  $data_pesanan['id'];
    $kodedeposit =  $data_pesanan['kode_deposit'];
    $username =  $data_pesanan['username'];
    $provider =  $data_pesanan['provider'];
    $jumlah =  $data_pesanan['jumlah_transfer'];
    $tujuan =  $data_pesanan['tujuan'];
    $status =  $data_pesanan['status'];


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
$phone = $nohp;
            
$message = (" Hai *".$data_pesanan['username']."*
*APPROVE DEPOSIT VIA BANK $provider*

Member Deposit Via Bank $provider
Mohon Segera DiProses Admin ".$data_mail['short_title']."
Member Pasti Akan Menunggu
Segera Cek Mutasi Di $provider
            	
Kode Deposit    : *".$data_pesanan['kode_deposit']."*
Username        : *".$data_pesanan['username']."*
Bank            : *$provider*
Jumlah          : Rp *$jumlah*
Tujuan Rekening : *$tujuan*
Status          : *$status*
            ");
            
    $url = 'https://scardwaget.my.id/send-message';       
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