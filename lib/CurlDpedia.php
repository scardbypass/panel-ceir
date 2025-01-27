<?php

class CurlDpedia {
	
	public function orderPulsa($url, $api_key, $service_id, $target, $no_meter){
		$postdata = array(
				'api_key' => $api_key,
				'service' => $service_id,
				'phone' => $target,
				'phone2' => $no_meter
				);
		$curl = $this->connectPost($url.'order/pulsa', $postdata);
		if ($curl['error'] == true) {
			return ['status' => false, 'data' => $curl['error']];
		} else {
			return ['status' => true, 'data' => $curl['code_trx']];
		}
	}

	public function orderEmoney($url, $api_key, $tipe, $target, $nominal){
		$postdata = array(
				'api_key' => $api_key,
				'tipe' => $tipe,
				'nomor' => $target,
				'nominal' => $nominal
		);
		$curl = $this->connectPost($url.'order/saldo', $postdata);
		if ($curl['error'] == true) {
			return ['status' => false, 'data' => $curl['error']];
		} else {
			return ['status' => true, 'data' => $curl['code_trx']];
		}
	}
	
	public function statusPulsa($url, $api_key, $order_id){
		$postdata = array(
				'api_key' => $api_key,
				'code' => $order_id
				);
		$curl = $this->connectPost($url.'status/pulsa', $postdata);
		return $curl;

	}

	public function servicePulsa($url, $api_key){
		$postdata = array(
				'api_key' => $api_key,
				);
		$curl = $this->connectPost($url.'service/pulsa', $postdata);
		return $curl;
	}

    private function connectPost($end_point,$postdata) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $end_point);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $chresult = curl_exec($ch);
        curl_close($ch);
        $json_result = json_decode($chresult, true);
        return $json_result;
    }
}

?>