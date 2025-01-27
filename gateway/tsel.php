<?php

require_once "mainconfig.php";
require_once "EnvayaSMS.php";
require_once "../config.php";

$request = EnvayaSMS::get_request();
header("Content-Type: {$request->get_response_type()}");
if (!$request->is_validated($PASSWORD))
{
    header("HTTP/1.1 403 Forbidden");
    error_log("Invalid password");    
    echo $request->render_error_response("Invalid password");
    return;
}

$action = $request->get_action();
switch ($action->type)
{
    case EnvayaSMS::ACTION_INCOMING:
    //Send an auto-reply for each incoming message.
    $type = strtoupper($action->message_type);
    $isi_pesan = $action->message;
    if($action->from == '858' AND preg_match("/Anda mendapatkan penambahan pulsa Rp/i", $isi_pesan)) {
        $pesan_isi = $action->message;
        //Memasukan pesan ke database
        $simpan_pesan = $conn->query("INSERT INTO pesan_tsel (isi, status, date) VALUES ('$pesan_isi', 'UNREAD', '$date')");
        //Cek deposit pending
        $cek_deposit = $conn->query("SELECT * FROM deposit_tsel WHERE tipe = 'Pulsa' AND status = 'Pending' AND date = '$date'");
        if (mysqli_num_rows($cek_deposit) == 0) {
            error_log("Deposit Pending Tidak Ditemukan");
        } else {
            while($datadeposit = mysqli_fetch_assoc($cek_deposit)) {
                $kode = $datadeposit['kode_deposit'];
                $pengirim = $datadeposit['nomor_pengirim'];
                $user = $datadeposit['username'];
                $saldo = $datadeposit['get_saldo'];
                $date = $datadeposit['date'];
                $nominal = $datadeposit['jumlah_transfer'];
                $cekpesan = preg_match("/Anda mendapatkan penambahan pulsa Rp $nominal dari nomor $pengirim tgl $date/i", $isi_pesan);
                //Jika Deposit Ditemukan
                if($cekpesan == TRUE) {
                    $konfirmasi_deposit = $conn->query("UPDATE deposit_tsel SET status = 'Success' WHERE kode_deposit = '$kode'");
                    $konfirmasi_deposit = $conn->query("UPDATE users SET saldo = saldo+$saldo WHERE username = '$user'");
                    $konfirmasi_deposit = $conn->query("INSERT INTO history_saldo VALUES ('', '$user', 'Penambahan Saldo', '$saldo', 'Penambahan Saldo. Deposit ID $kode', '$date', '$time')");
                    //konfirmasi deposit
                    if($konfirmasi_deposit == TRUE) {
                        error_log("Saldo $user Telah Ditambahkan Sebesar $saldo");
                    } else {
                        error_log("System Error");
                    }
                } else {
                    error_log("Deposit Belum Ditemukan");
                }
            }
        }
    } else {
        error_log("Received: $type from {$action->from}");
        error_log("Message: {$action->message}");
    }                     

    return;
}