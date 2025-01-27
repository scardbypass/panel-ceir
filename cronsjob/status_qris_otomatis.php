<?php
require("../config.php");

	$cek_deposit = $conn->query("SELECT * FROM deposit_otomatis WHERE status IN ('Pending') AND provider = 'QRIS OTOMATIS'");

if (mysqli_num_rows($cek_deposit) == 0) {
  die("Deposit Pending Tidak Ditemukan.");
} else {
  while($data_deposit = $cek_deposit->fetch_assoc()) {
    $poid =  $data_deposit['kode_deposit'];
    $oid =  $data_deposit['kode_deposit'];
    $jumlah_tf =  $data_deposit['jumlah_transfer'];
    $fee_server =  $data_deposit['fee'];
    $fee_admin =  $data_deposit['fee_admin'];
    $getsaldo =  $data_deposit['get_saldo'];
    $layanan =  $data_deposit['layanan'];
    $email =  $data_deposit['email'];
    $saldoawal =  $data_deposit['saldo_awal'];
    $saldoakhir =  $data_deposit['saldo_akhir'];
    $uplink =  $data_deposit['uplink'];
    $bonus =  $data_deposit['bonus'];
    $email =  $data_deposit['email'];
    

    if ($o_provider == "MANUAL") {
      echo "Order manual<br />";
    } else {

      $cek_provider = $conn->query("SELECT * FROM provider WHERE code = 'QRIS OTOMATIS'");
      $data_provider = $cek_provider->fetch_assoc();

      if ($o_provider !== "MANUAL") {
        $p_apikey = $data_provider['api_key'];
		$p_service = $data_provider['api_id'];

		$sign = md5($data_provider['api_key'].$poid.'StatusTransaction');
		$postdata = "key=".$data_provider['api_key']."&request=status&unique_code=$poid&signature=$sign";

$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://paydisini.co.id/api/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS , array('key' => $p_apikey,'request' => 'status','unique_code' => $poid,'signature' => $sign));
		$chresult = curl_exec($ch);
		//echo $chresult;
		curl_close($ch);
		$json_result = json_decode($chresult, true);
      }

      if ($o_provider !== "MANUAL") {
            $pesan = $json_result['msg'];
			$provider_oid = $json_result['data']['pay_id'];
			$kode_unik = $json_result['data']['unique_code'];
			$amountnya = $json_result['data']['amount'];
			$ser_name = $json_result['data']['service_name'];
			$balan = $json_result['data']['balance'];
			$feenya = $json_result['data']['fee'];
			$u_status = $json_result['data']['status'];
			$expir = $json_result['data']['expired'];
      

    if($u_status == 'Pending'){
        $status = 'Pending';
      }else if($u_status == 'Canceled'){
        $status = 'Error';
      }else if($u_status == 'Success'){
        $status = 'Success';
      }else{
        $status = 'Pending';
      }
      
      if ($status == "Success") {
          
             $cek_mail = $conn->query("SELECT * FROM setting_web");
	        $data_mail = mysqli_fetch_assoc($cek_mail);
	        $tokenwa = $data_mail['token_wa'];
	        $deviceid = $data_mail['device_id'];
          
        $update_user = $conn->query("UPDATE users SET saldo = saldo + $getsaldo WHERE username = '".$data_deposit['username']."'");
		$update_order = $conn->query("INSERT INTO history_saldo VALUES ('', '".$data_deposit['username']."', 'Penambahan Saldo', '$getsaldo', 'Penambahan Dana. Order ID ".$data_deposit['kode_deposit']."', '$date', '$time')");
	
 $cekwa = $conn->query("SELECT * FROM provider WHERE code = 'ARIEGATEWAY'");
$datawa = mysqli_fetch_assoc($cekwa);
            $license = $datawa['api_key'];
            $apiwa = $datawa['api_id'];
            
             $token = $tokenwa;
             $device = $deviceid;
             $phone = $data_deposit['no_hp'];
 $message = ("Hai *".$data_deposit['username']."*
Deposit Kamu *BERHASIL*
Kode Depo   : *".$data_deposit['kode_deposit']."*
Jumlah      : *Rp ".$data_deposit['jumlah_transfer']."*
Fee Admin   : *Rp ".$data_deposit['fee_admin']."*
Fee Server  : *Rp ".$data_deposit['fee']."*
Saldo Didapat : *Rp ".$data_deposit['get_saldo']."*
Status      :*$u_status*

_Ini Adalah Struk Resmi Dari Biller ".$data_mail['short_title']."_
             ");
            
     $url = 'https://ariegateway.my.id/send-message';

     $curl = curl_init();

         curl_setopt_array($curl, array(
         CURLOPT_URL =>  $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => array('api_key' => $license, 'sender' => $apiwa, 'number' => $phone, 'message' => $message),
         ));

     $response = curl_exec($curl);

     curl_close($curl);
     echo $response;
	 }
	 
// 	 if ($status == "Error") {
	     
// 	        $cek_mail = $conn->query("SELECT * FROM setting_web");
// 	        $data_mail = mysqli_fetch_assoc($cek_mail);
// 	        $tokenwa = $data_mail['token_wa'];
// 	        $deviceid = $data_mail['device_id'];
	     
// 	    $harga = $data_deposit['harga'];
// 		$profit = $data_deposit['profit'];

// 		$update_user = $conn->query("UPDATE users SET pemakaian_saldo = pemakaian_saldo - $harga WHERE user = '".$data_deposit['user']."'");
// 		$update_user = $conn->query("UPDATE users SET saldo = saldo + $harga WHERE username = '".$data_deposit['user']."'");
// 		$update_order = $conn->query("INSERT INTO history_saldo VALUES ('', '".$data_deposit['user']."', 'Penambahan Saldo', '$harga', 'Pengembalian Dana. Order ID ".$data_deposit['oid']."', '$date', '$time')");
// 		$update_order = $conn->query("UPDATE pembelian_pulsa SET refund = '1', profit = profit - $profit  WHERE oid = '".$data_deposit['oid']."'");
	      
// 			$to = $email;

// 			$mail = new PHPMailer;
//             $mail->IsSMTP();
// 	        $mail->SMTPSecure = $config['emailinfo']['enkripsi'];
//             $mail->Host = $config['emailinfo']['mailhost'];
// 	        $mail->SMTPDebug = 2;
// 	        $mail->Port = $config['emailinfo']['mailport'];
// 	        $mail->SMTPAuth = true;
// 	        $mail->Username = $config['emailinfo']['mailusername'];
// 	        $mail->Password = $config['emailinfo']['mailpassword'];
// 	        $mail->SetFrom($config['emailinfo']['mailusername'],$data_mail['short_title']);
//             $mail->Subject = "TRANSAKSI GAGAL";
//             $mail->AddAddress("$to","");
//             $mail->MsgHTML("
//             	<p>Hai ".$data_deposit['user']."</p>
//             	<p>Transaksi Kamu Gagal</p>
//             	<p>Silahkan Cek Kembali Transaksi Kamu</p>
//             	<p>
            	
//             	Order ID: ".$data_deposit['oid']."<br/>
//             	Username: ".$data_deposit['user']."<br/>
//             	Target: $u_phone <br/>
//             	Layanan: ".$data_deposit['layanan']."<br/>
//             	DiRefund: Rp ".$data_deposit['harga']."<br/>
//                 Status: $u_status <br/>
//                 Catatan: Periksa Kembali Nomor Tujuan Dan Coba Lagi Nanti <br/><br/>
//                 Saldo       : Rp $saldoakhir * Rp ".$data_deposit['harga']." <br/>
//                 Sisa Saldo  : Rp $saldoawal <br/><br/>
//             	</p>
//             ");
// 			if ($mail->Send());
			
// 			//NOTOFIKASI WHATAPPS GAGAL
//             $cekwa = $conn->query("SELECT * FROM provider WHERE code = 'WAGATEWAY'");
//             $datawa = mysqli_fetch_assoc($cekwa);
//             $license = $datawa['api_key'];
//             $apiwa = $datawa['api_id'];
			
// 			$token = $tokenwa;
// 			$device = $deviceid;
//             $phone = $data_deposit['no_hp'];
//             $message = (" Hai *".$data_deposit['user']."*
// Transaksi Kamu *GAGAL*
// Waktu       : ".$data_deposit['date']."/".$data_deposit['time']."
// Order ID        : *".$data_deposit['oid']."*
// Target          : *$u_phone*
// Layanan     : *".$data_deposit['layanan']."*
// DiRefund    : *Rp ".$data_deposit['harga']."*
// Status          : *$u_status*
// Catatan: *Periksa Nomor Tujuan Anda Dan Coba Lagi Nanti*

// Saldo       : *Rp $saldoakhir * Rp ".$data_deposit['harga']."*
// Sisa Saldo  : *Rp $saldoawal*
//             ");
            
            
//     $url = $datawa['link'];       

//     $curl = curl_init();

//         curl_setopt_array($curl, array(
//         CURLOPT_URL =>  $url,
//         CURLOPT_RETURNTRANSFER => true,
//         CURLOPT_ENCODING => '',
//         CURLOPT_MAXREDIRS => 10,
//         CURLOPT_TIMEOUT => 0,
//         CURLOPT_FOLLOWLOCATION => true,
//         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//         CURLOPT_CUSTOMREQUEST => 'POST',
//         CURLOPT_POSTFIELDS => array('api_key' => $license, 'sender' => $apiwa, 'number' => $phone, 'message' => $message),
//         ));

//     $response = curl_exec($curl);

//     curl_close($curl);
//     echo $response;
// 	 }

      $update_pesanan = $conn->query("UPDATE deposit_otomatis SET status = '$status' WHERE kode_deposit = '$poid' AND provider = 'QRIS OTOMATIS'");
      if ($update_pesanan == TRUE) {
        echo "<b>Status Order Diupdate</b> <br/>
        Provider ID: $poid <br/>
        Status: $u_status <br/><br/>";
      } else {
        echo "Error database";
      }
    }
  }
}
}

?>