<?php if(!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Maxx Techical Team (vanthiembui.it@gmail.com)      # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 Maxx Media JSC.          # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Api {
	private static $_instance = null;
	function __construct(){
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: *");
		//header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		// Some code
		$this->method = $_SERVER['REQUEST_METHOD'];
	}
	public static function getInstance(){
		if(null == self::$_instance){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	function getParam($params, $field, $def=0){
		if(isset($params[$field]) && !empty($params[$field]))
			return $params[$field];
		return $def;
	}
	public function validateLoginInformation(){
		global $core;
		$JWT = new \Firebase\JWT\JWT();
		/*$token = array('user_id' => 13, 'license_key' => TRAVELMASTER_LICENSE_KEY);
		echo $JWT->encode($token, LICENSE_KEY);die;*/
		$access_token = $this->get_bearer_tocken();
		if(!$access_token){
			$this->echoResponse(401, array(
				'result' => 'error',
				'message' => $core->get_Lang('Authorization required')
			));
		} else {
			try {
				$tokenDecode = $JWT::decode($access_token, LICENSE_KEY, array('HS256'));
				if(!$tokenDecode || !$tokenDecode->member_id){
					$apiresults = array('result' => 'error', 'message' => $core->get_Lang('UserId not found'));
					$this->echoResponse(400, $apiresults);
					exit();
				}else{
					$clsMember = new Member();
					$field = "{$clsMember->pkey},user_name,user_pass,first_name,last_name,full_name,email,address,phone,avatar,is_active";
					$member_info = $clsMember->getOne($tokenDecode->member_id, $field);
					if(!empty($member_info) && $member_info['is_active']==0){
						$apiresults = array('result' => 'error', 'message' => $core->get_Lang('UserId not active'));
						$this->echoResponse(400, $apiresults);
					}else{
						$core->_USER = $member_info;
					}
				}
			} catch (Exception $e){
				$this->echoResponse(401, array(
					'result' => 'error',
					'message' => $core->get_Lang('Unauthorized')
				));
			}
		}
	}
	function get_license_header(){
		$license_key = null;
		if (isset($_SERVER['x-api-key'])) {
			$license_key = trim($_SERVER['x-api-key']);
		} else if(isset($_SERVER['HTTP_X_API_KEY'])){
			$license_key = trim($_SERVER['HTTP_X_API_KEY']);
		} elseif (function_exists('apache_request_headers')) { // Nginx
			$headers = apache_request_headers();
			if (isset($headers['x-api-key'])) {
				$license_key = trim($headers['x-api-key']);
			}
		}
		return $license_key;
	}
	public function get_authorization_header(){
		$Authorization = null;
		if (isset($_SERVER['Authorization'])) {
			$Authorization = trim($_SERVER["Authorization"]);
		} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
			$Authorization = trim($_SERVER["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			if (isset($requestHeaders['Authorization'])) {
				$Authorization = trim($requestHeaders['Authorization']);
			}
		}
		return $Authorization;
	}
	public function get_bearer_tocken() {
		$headers = $this->get_authorization_header();
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		return null;
	}
	public function echoResponse($status_code, $response, $content_type = null,$unescaped_unicode=false) {
		Response::echoResponse($status_code, $response, $content_type,$unescaped_unicode);
		exit();
	}
	function toInt($str, $def = 0){
		if(!empty($str))
			return intval($str);
		return $def;
	}
	function valid_method($method = array('GET','POST')){
		if(!in_array($this->method, $method)){
			$this->echoResponse(405, array(
				'result' => 'error',
				'message' => $core->get_Lang('Method Not Allowed')
			)); die();
		}
	}
	function parseURL($url){
		$url = str_replace('//', '/', $url);
		$url = str_replace(DOMAIN_NAME,'', $url);
		$url = DOMAIN_NAME.$url;
		return $url;
	}
}
?>