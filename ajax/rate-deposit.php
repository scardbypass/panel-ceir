<?php


session_start();
require_once '../config.php';

$pembayaran = $conn->real_escape_string($_POST['pembayaran']);
$nominal = $conn->real_escape_string($_POST['jumlah']);
$cek_rate = $conn->query("SELECT * FROM metode_depo WHERE id = '$pembayaran'");
$cek_hasil = $cek_rate->fetch_array();
$menghitung = $nominal * $cek_hasil['rate'];
echo $result;
?>