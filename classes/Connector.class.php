<?php

class Connector {

	public $url;
	public $api_key;
	public $output = "json";

	function __construct($url, $api_key) {
		$this->url = (preg_match("/\/$/", $url)) ? "{$url}admin/api.php?api_key={$api_key}" : "{$url}/admin/api.php?api_key={$api_key}";
		$this->api_key = $api_key;
	}

	public function credentials_test() {
		$test_url = "{$this->url}&api_action=group_view&api_output={$this->output}&id=3";
		$r = $this->curl($test_url);
		if (is_object($r) && (int)$r->result_code) {
			// successful
			$r = true;
		}
		else {
			// failed
			$r = false;
		}
		return $r;
	}

	public function curl($url, $post_data = array()) {
		$request = curl_init();
		curl_setopt($request, CURLOPT_URL, $url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		if ($post_data) {
			curl_setopt($request, CURLOPT_POST, 1);
			$data = "";
			foreach($post_data as $key => $value) $data .= $key . "=" . urlencode($value) . "&";
			$data = rtrim($data, "& ");
			curl_setopt($request, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
		$response = curl_exec($request);
//dbg($response);
		$http_code = curl_getinfo($request, CURLINFO_HTTP_CODE);
		curl_close($request);
		$object = json_decode($response);
		return $object;
	}

}

?>