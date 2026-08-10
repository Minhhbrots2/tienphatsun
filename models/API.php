<?php 
class API {
	public $api_url = 'https://myoceancity.vn/api/';
	public $api_key = '';
	function __construct($config=''){
		if(is_array($config)){
			foreach($config as $k=>$v){
				$this->{$k} = $v;
			}
		}
	}
	// Get configuration
	function _getConfig($config_key){
		return $this->{$config_key};
	}
	// Set configuration
	function _setConfig($config_key, $config_value){
		$this->{$config_key} = $config_value;
	}
	function get($request_url, $params = array()){
		$curl = curl_init();
		$url = $this->api_url. $request_url;
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_POSTFIELDS => json_encode($params, JSON_UNESCAPED_UNICODE),
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer ".$this->api_key,
				"Content-Type: application/json"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			return $response;	
		}	
	}
	function post($request_url, $params = array()){
		$curl = curl_init();
		$url = $this->api_url . $request_url;
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($params, JSON_UNESCAPED_UNICODE),
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer ".$this->api_key,
				"Content-Type: application/json"
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			return $response;	
		}	
	}
}
?>