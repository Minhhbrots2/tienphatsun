<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class Notification extends dbBasic{
	function __construct(){
		$this->pkey = "notification_id";
		$this->tbl = DB_PREFIX."notification";
	}
	function getAccessToken() {
		$jsonKeyFilePath = 'future-homes-d041a-f698b64eac08.json';
//		$jsonKeyFilePath = 'futurehomes-6c42a-firebase-adminsdk-fbsvc-c80ff66e85.json';
		$jsonKey = json_decode(file_get_contents($jsonKeyFilePath), true);
		$jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
		$jwtClaim = base64_encode(json_encode([
			'iss' => $jsonKey['client_email'],
			'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
			'aud' => $jsonKey['token_uri'],
			'exp' => time() + 3600,
			'iat' => time(),
		]));
		$signature = '';
		openssl_sign($jwtHeader . '.' . $jwtClaim, $signature, $jsonKey['private_key'], 'SHA256');
		$jwt = $jwtHeader . '.' . $jwtClaim . '.' . base64_encode($signature);
		// Lấy access token
		$response = json_decode(file_get_contents($jsonKey['token_uri'], false, stream_context_create([
			'http' => [
				'method' => 'POST',
				'header' => 'Content-Type: application/x-www-form-urlencoded',
				'content' => http_build_query([
					'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
					'assertion' => $jwt,
				])
			]
		])), true);
		return $response['access_token'];
	}
	function doPushMessaging($device_token, $params){
		global $core, $dbconn, $clsISO;
		$msg = "_error";
		$title = $params['title'];
		$body = $params['body'];
		$link = $params['link'];
		
		$payload = [
			"message" => [
				"token" => $device_token,
				"notification" => [
					"title" => $title,
					"body" => $body,
				], 
				"data" => [
					'link' => $link
				],
			]
		];
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', $this->getAccessToken())
		));
		$curl->post('https://fcm.googleapis.com/v1/projects/future-homes-d041a/messages:send', $payload);
//		$curl->post('https://fcm.googleapis.com/v1/projects/futurehomes-6c42a/messages:send', $payload);
		// $clsISO->print_pre($curl); die();
		if(!$curl->error){
			$response = $curl->response;
			$msg = "_success";
		}
		return $msg;
	}
	function doPushMessagingUser($params, $arr_profile_id=[]){
		global $core, $dbconn, $clsISO;
		$clsAccessToken = new AccessToken();
		$cond_access_token = "`firebase_token`<>'' AND `expires`>='".time()."'";
		if(!empty($arr_profile_id)) {
			$cond_access_token .= " AND `profile_id` IN (".implode(',',$arr_profile_id).")";
		}
		$arr_tokens = $clsAccessToken->getAll($cond_access_token." GROUP BY `firebase_token`", "firebase_token"); 
		if(!empty($arr_tokens)) {
			foreach($arr_tokens as $key => $val){
				$firebase_token = $val['firebase_token'];
				$this->doPushMessaging($firebase_token, $params);
			}
		}
	}
	function sendZaloContent($content,$group_zalo_id){
		global $clsISO;
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MDExYjFkYjQ5YzhlZTdkM2VhYzAxMCIsImlhdCI6MTc0NDkwMjk0MSwiZXhwIjoxNzc2NDM4OTQxfQ.lv4JEMevPdhqzWlWBS7vJGwFkwmYCL4ZD6_aaujVtrA'
		));
		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' 		=> $content,
			'group_id' 		=> $group_zalo_id,
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && $response['status'] == 200){
				return 1;
			}
		}
		// Return
		return 0;
	}
	function sendZaloImage($image,$group_zalo_id){
		global $clsISO;
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTQ5MjY4NTAxOTEwMmQ0MjhmOWY5ZiIsImlhdCI6MTc1NDU2NzI3MiwiZXhwIjoxNzg2MTAzMjcyfQ.fmEaolefHG9tAJf9oHD0m7Yn-bMjxZhphx7pf7pG_hg'
		));
		$curl->post('https://public-api.bizflow.vn/functions/689492685019102d428f9f9f', array(
			'url'		 	=> $image,
			'desc' 			=> "",
			'group_id' 		=> $group_zalo_id,
			'groupLayoutId' => 0,
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && $response['status'] == 200){
				return 1;
			}
		}
		// Return
		return 0;
	}
}
?>