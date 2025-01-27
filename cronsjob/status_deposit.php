<?php
require("../config.php");
$cek_deposit = $conn->query("SELECT * FROM deposit WHERE status IN ('Pending')");

if (mysqli_num_rows($cek_deposit) == 0) {
  die("Deposit Pending Tidak Ditemukan");
} else {
  while($data_deposit = $cek_deposit->fetch_assoc()) {
    $poid =  $data_deposit['kode_deposit'];
    $user =  $data_deposit['username'];
    $id =  $data_deposit['id'];
    $get_saldo =  $data_deposit['get_saldo'];
    $o_provider =  $data_deposit['provider'];

    if ($o_provider == "MANUAL") {
      echo "Order manual<br />";
    } else {
      $cek_provider = $conn->query("SELECT * FROM provider WHERE code = 'DPEDIA'");
      $data_provider = $cek_provider->fetch_assoc();

      if ($o_provider !== "MANUAL") {
        $api_postdata = "key=".$data_provider['api_key']."&secret=".$data_provider['secret_key']."&id=$poid";
      } else {
        die("System error");
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://d-pedia.co.id/api/v1/deposit/status");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $chresult = curl_exec($ch);
      //echo $chresult;
      curl_close($ch);
      $json_result = json_decode($chresult, true);
      //print_r($json_result);

      if ($o_provider !== "MANUAL") {
        $provider_oid = $json_result['data']['id'];
        $u_pengirim = $json_result['data']['sender_name'];
        $u_nopengirim = $json_result['data']['sender_account'];
        $u_uniq = $json_result['data']['uniq'];
        $amount = $json_result['data']['amount'];
        $get_saldo = $json_result['data']['get_amount'];
        $jumlah = $json_result['data']['pay_amount'];
        $u_status = $json_result['data']['status'];
        $u_created_at = $json_result['created_at'];
        $tujuan = $json_result['data']['payment_target'];
        $trxline = $json_result['data']['transaction_line'];
        $confirm_time = $json_result['confirm_time'];
        $cancel_time = $json_result['data']['cancel_time'];
        $updated_at = $json_result['data']['updated_at'];
        $already_transfer = $json_result['data']['already_transfer'];
        $method = $json_result['data']['method'];
      }

      if($u_status == 'pending') {
        $status = 'Pending';
      } else if($u_status == 'expired') {
        $status = 'Error';
      } else if($u_status == 'confirmed') {
        $status = 'Success';
      } else if($u_status == '') {
        $status = 'Pending';
      } else {
        $status = 'Pending';
      }

      if ($json_result['status'] !== "ok") {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Deposit Belum Ditemukan', 'pesan' => $json_result['message']);
        echo "Dana Belum Ditemukan";
      } elseif ($json_result['status'] == "ok") {
        if ($conn->query("UPDATE deposit SET status = '$status' WHERE kode_deposit = '$poid'") == true) {
          if ($json_result['data']['status'] == "confirmed") {
            $conn->query("UPDATE users set saldo = saldo + $get_saldo WHERE username = '$user'");
            $conn->query("INSERT INTO history_saldo VALUES ('', '$user', 'Penambahan Saldo', '$get_saldo', 'Penambahan Saldo. Deposit ID $poid', '$date', '$time')");
            echo "<b>Status Deposit Sukses</b> <br/>
            Deposit ID: $poid <br/>
            Status: $status <br/>
            Nama Pengirim: $u_pengirim <br/>
            No Pengirim: $u_nopengirim <br/>
            Tujuan: $tujuan <br/>
            Jumlah: $jumlah <br/>
            Get Saldo: $get_saldo <br/>
            Metode: $method <br/><br/>";
          } else {
            echo "<b>Status Deposit Diupdate</b> <br/>
            Deposit ID: $poid <br/>
            Status: $status <br/>
            Nama Pengirim: $u_pengirim <br/>
            No Pengirim: $u_nopengirim <br/>
            Tujuan: $tujuan <br/>
            Jumlah: $jumlah <br/>
            Get Saldo: $get_saldo <br/>
            Metode: $method <br/><br/>";
          }
        } else {
          echo "Error database";
        }
      } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Deposit Belum Ditemukan', 'pesan' => $json_result['message']);
        echo "Dana Belum Ditemukan";
      }
    }
  } 
}

?>