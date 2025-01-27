<?php
require_once 'config.php';
 

$deposit = mysqli_query($conn, "SELECT * FROM deposit WHERE status = 'Pending' ORDER BY id DESC LIMIT 3");
while($fetch = mysqli_fetch_assoc($deposit)){
if($fetch['status'] == 'Success'){
    $cekwa = $conn->query("SELECT * FROM provider WHERE code = 'ScardWaget'");
$datawa = mysqli_fetch_assoc($cekwa);
            $license = $datawa['api_key'];
            $apiwa = $datawa['api_id'];

$phone = $fl['no_hp'];
				
            //NOTIFIKASI WHATSAPP BERHASIL
$message = (" Hai *".$fetch['username']."*
Deposit Anda Telah Diterima!
Waktu      : ".$fetch['date']."/".$fetch['time']."
Deposit ID        : *".$fetch['kode_deposit']."*
Pambayaran         : *".$fetch['payment']."*
Jumlah Pembayaran     : *".$fetch['jumlah_transfer']."*
Status          : *".$fetch['status']."*

_Ini Adalah Struk Resmi Dari Biller ".$data_mail['short_title']."_
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
 }
}

//Refund
$order = mysqli_query($conn, "SELECT * FROM pembelian_pulsa WHERE status = 'Error' ORDER BY id DESC LIMIT 5");
while($forder = mysqli_fetch_assoc($order)){
    $ids = $forder['id'];
    $cek = mysqli_query($conn, "SELECT * FROM wa WHERE data_id = '$ids'");
    $num = mysqli_num_rows($cek);
    if($num < 0){
        $user = $forder['user'];
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user'");
        $fuser = mysqli_fetch_assoc($cek_user);
        $real_balance = $fuser['saldo'];
        $user_id = $fuser['id'];
        $balance = $real_balance + $forder['harga'];
        $update_saldo = mysqli_query($conn, "UPDATE `users` SET `saldo` = '$balance' WHERE `users`.`id` = $user_id");
        $insert_log = mysqli_query($conn, "INSERT INTO `history_saldo` (`id`, `username`, `aksi`, `nominal`, `pesan`, `date`, `time`) VALUES (NULL, 'scardflasher2', 'Penambahan Saldo', '7000', 'Refund Order ID ".$foder['id']." Produk PPOB', '".date('Y-m-d')."', '".date('H:i:s')."')");
        
    }
}

$igital = mysqli_query($conn, "SELECT * FROM pembelian_digital WHERE status = 'Error' ORDER BY id DESC LIMIT 5");
while($forder = mysqli_fetch_assoc($idital)){
    $ids = $forder['id'];
    $cek = mysqli_query($conn, "SELECT * FROM wa WHERE data_id = '$ids'");
    $num = mysqli_num_rows($cek);
    if($num < 0){
        $user = $forder['user'];
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user'");
        $fuser = mysqli_fetch_assoc($cek_user);
        $real_balance = $fuser['saldo'];
        $user_id = $fuser['id'];
        $balance = $real_balance + $forder['harga'];
        $update_saldo = mysqli_query($conn, "UPDATE `users` SET `saldo` = '$balance' WHERE `users`.`id` = $user_id");
        $insert_log = mysqli_query($conn, "INSERT INTO `history_saldo` (`id`, `username`, `aksi`, `nominal`, `pesan`, `date`, `time`) VALUES (NULL, 'scardflasher2', 'Penambahan Saldo', '7000', 'Refund Order ID ".$foder['id']." Produk DIGITAL', '".date('Y-m-d')."', '".date('H:i:s')."')");
        
    }
}

$digital = mysqli_query($conn, "SELECT * FROM pembelian_digital WHERE status = 'Pending' ORDER BY id DESC LIMIT 5");
while($forder = mysqli_fetch_assoc($didital)){
    $ids = $forder['id'];
    $note = $forder['keterangan'];
    $cek = mysqli_query($conn, "SELECT * FROM wa WHERE data_id = '$ids'");
    $num = mysqli_num_rows($cek);
    if($num < 0){
        $user = $forder['user'];
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$user'");
        $fuser = mysqli_fetch_assoc($cek_user);
        $real_balance = $fuser['saldo'];
        $user_id = $fuser['id'];
        $no = $fuser['no_hp'];
        
        
            $cekwa = $conn->query("SELECT * FROM provider WHERE code = 'ScardWaget'");
$datawa = mysqli_fetch_assoc($cekwa);
            $license = $datawa['api_key'];
            $apiwa = $datawa['api_id'];

$phone = $fl['no_hp'];
				
            //NOTIFIKASI WHATSAPP BERHASIL
$message = (" Hai *".$fuser['username']."*
Pesanan Anda Telah Sukses!
Waktu      : ".$forder['date']."/".$forder['time']."
Order ID        : *".$forder['id']."*
Layanan         : *".$forder['layanan']."*
Harga     : *".$forder['harga']."*
SN : *$note*
Status          : *Success*

_Ini Adalah Struk Resmi Dari Biller ".$data_mail['short_title']."_
            ");
            
    $url = 'https://scardwaget.my.id/send-message';       
	$curl = curl_init();
	$gateway = [
		'api_key'    => $license,
		'sender'    => $apiwa,
        'number'     => $no,
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
        
        
        $balance = $real_balance + $forder['harga'];
        $update_saldo = mysqli_query($conn, "UPDATE `pembelian_digital` SET `status` = 'Success' WHERE `pembelian_digital`.`id` = ".$forder['id']."");
        
        
    }
}

?>
