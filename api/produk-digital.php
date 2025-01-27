<?php


require '../config.php';
header('Content-Type: application/json');
if ($maintenance == 1) {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Maintenance'));
	exit(json_encode($hasilnya, JSON_PRETTY_PRINT));
}
if (isset($_POST['api_key']) AND isset($_POST['action'])) {
	$apinya = $conn->real_escape_string($_POST['api_key']);
	$aksinya = $_POST['action'];

	if (!$apinya || !$aksinya) {
		$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan Tidak Sesuai'));

	} else {
		$cek_usernya = $conn->query("SELECT * FROM users WHERE api_key = '$apinya'");
		$datanya = $cek_usernya->fetch_assoc();
		if (mysqli_num_rows($cek_usernya) == 1) {
			if ($aksinya == 'pemesanan') {
				if (isset($_POST['layanan']) AND isset($_POST['target'])) {
					$layanan = $conn->real_escape_string(trim(filter($_POST['layanan'])));
					$target = $conn->real_escape_string(trim(filter($_POST['target'])));
					$nometer = $conn->real_escape_string(trim(filter($_POST['no_meter'])));
					
					if (!$layanan || !$target) {
						$hasilnya = array('status' => false, 'data' => array('pesan' => 'Permintaan Tidak Sesuai'));
					} else {
						$cek_layanan = $conn->query("SELECT * FROM layanan_digital WHERE provider_id = '$layanan' AND status = 'Normal'");
						$data_layanan = $cek_layanan->fetch_assoc();
						if (mysqli_num_rows($cek_layanan) == 0) {
							$hasilnya = array('status' => false, 'data' => array('pesan' =>'Layanan tidak tersedia'));
						} else {
							$order_id = acak_nomor(3).acak_nomor(4);
							$username = $datanya['username'];
							$provider = $data_layanan['provider'];
							$cek_profit = $data_layanan['profit'];
							$cek_harga = $data_layanan['harga_api'];
							$profit = $cek_profit;
							$harga = $cek_harga;
                        	$nohp = $datanya['no_hp'];
                        	$harga_ap = $data_layanan['harga_api'];
                        	$action = 'pemesanan';
							if ($datanya['saldo'] < $data_layanan['harga_api']) {
								$hasilnya = array('status' => false, 'data' => array('pesan' =>'Saldo Tidak Mencukupi'));
							} else {
								$cek_provider = $conn->query("SELECT * FROM provider WHERE code = '$provider'");
								$data_provider = mysqli_fetch_assoc($cek_provider);

		if ($provider == "MANUAL") {
		        	$postdata = "";
		    	} else if ($provider == "roamerimei") {
		if ($post_nometer == false) {
			$postdata = "api_key=".$data_provider['api_key']."&action=$action&layanan=".$data_layanan['provider_id']."&target=$target$nometer";
			} else if ($post_nometer == true) {
			$postdata = "api_key=".$data_provider['api_key']."&action=$action&layanan=".$data_layanan['provider_id']."&target=$target&no_moter=$nometer";
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
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

	    	$response = curl_exec($curl);
            curl_close($curl);
			/*
			echo $response;
			*/
            $json_result = json_decode($response, true);
								}
								
								if ($provider = "KITADIGITAL" AND $json_result['status'] == false) {
									$hasilnya = array('status' => false, 'data' => array('pesan' => ''.$json_result['data']['pesan']));
								} else {
									if ($provider == "KITADIGITAL") {
										$provider_oid = $json_result['data']['id'];
									}
									if ($conn->query("INSERT INTO pembelian_digital VALUES ('','$order_id', '$provider_oid', '$username', '".$data_layanan['layanan']."', '".$data_layanan['harga_api']."', '".$data_layanan['profit']."', '$target', '$nometer', '$pesan', 'Pending', '$date', '$time', 'API', '$provider', '0')") == true) {
										$conn->query("UPDATE users set saldo = saldo - ".$data_layanan['harga_api'].", pemakaian_saldo = pemakaian_saldo + ".$data_layanan['harga_api']." WHERE username = '".$datanya['username']."'");
					                    $conn->query("INSERT INTO history_saldo VALUES ('', '$username', 'Pengurangan Saldo', '".$data_layanan['harga_api']."', 'Order ID $order_id Digital', '$date', '$time')");
					                    
										$hasilnya = array('status' => true, 'data' => array('id' => $order_id, 'target' => $target, 'layanan' => $data_layanan['layanan'], 'harga' => $data_layanan['harga_api']));
									} else {
										$hasilnya = array('status' => false, 'data' => array('pesan' => 'System Error'));
									}
								}
							}
						}
					}
				} else {
					$hasilnya = array('status' => false, 'data' => array('pesan' => 'System Error'));
				}
			} else if ($aksinya == 'status') {
				if (isset($_POST['id'])) {
					$order_id = $conn->real_escape_string(trim($_POST['id']));
					$cek_pesanan = $conn->query("SELECT * FROM pembelian_digital WHERE oid = '$order_id' AND user = '".$datanya['username']."'");
					$data_pesanan = mysqli_fetch_array($cek_pesanan);

					if (mysqli_num_rows($cek_pesanan) == 0) {
						$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Kode Pesanan Kamu Tidak Di Temukan.'));
					} else {
						$hasilnya = array('status' => true, 'data' => array("id" => $data_pesanan['oid'], 'status' => $data_pesanan['status'], 'catatan' => $data_pesanan['keterangan']));
					}
				} else {
					$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Permintaan Tidak Sesuai.'));
				}

			} else if ($aksinya == 'layanan') {
					$cek_layanan = $conn->query("SELECT * FROM layanan_digital ORDER BY provider_id ASC");
					while($rows = mysqli_fetch_array($cek_layanan)){
					$hasilnya = "-";
					$this_data[] = array('sid' => $rows['provider_id'], 'operator' => $rows['operator'], 'layanan' => $rows['layanan'], 'harga' => $rows['harga_api'],'status' => $rows['status'],'tipe' => $rows['tipe'],'catatan' => $rows['catatan']);
                }
					$hasilnya = array('status' => true, 'data' => $this_data);
			} else {
				$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Permintaan Salah.'));
			}
		} else {
			$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Api Key Kamu Salah.'));
		}
	}
} else {
	$hasilnya = array('status' => false, 'data' => array('pesan' => 'Ups, Permintaan Tidak Sesuai.'));
}

print(json_encode($hasilnya, JSON_PRETTY_PRINT));