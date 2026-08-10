<?php if (!defined('ABSPATH')) exit('No direct script access allowed');


/*======================================================================*\


|| #################################################################### ||


|| # The Classes configurations of the MaxxCMS                        # ||


|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||


|| # ---------------------------------------------------------------- # ||


|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||


|| # This file may not be redistributed in whole or significant part. # ||


|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||


|| #################################################################### ||


\*======================================================================*/


class ZaloOA {


	var $app_id 	= '4477899232273918818';


	var $secret_key = 'BItY6oVR2GkP3tYqKH5M';


	function __construct(){


		// Some code


	}


	function generate_pkce() {


		// tạo code_verifier (43 ký tự)


		$code_verifier = substr(str_replace(


			['+','/','='],


			'',


			base64_encode(random_bytes(32))


		),0,43);


		// SHA256 hash


		$hash = hash('sha256', $code_verifier, true);


		// base64url encode


		$code_challenge = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');


		return [


			'code_verifier'  => $code_verifier,


			'code_challenge' => $code_challenge


		];


	}


	function get_oauth_link(){


		$redirect_uri = sprintf("%s", PCMS_URL);


		$code_verifier = bin2hex(random_bytes(32));


		$hash = hash('sha256', $code_verifier, true);


		$code_challenge = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');


		// session_start();


		vnSessionSetVar('code_verifier', $code_verifier);


		$url = "https://oauth.zaloapp.com/v4/oa/permission?"


			. "app_id=".$this->app_id


			. "&redirect_uri=".urlencode($redirect_uri)


			. "&code_challenge=".$code_challenge


			. "&code_challenge_method=S256"


			. "&state=TPS";


		return $url;


	}


	function get_access_token(){


		global $core, $dbconn, $clsISO, $clsConfiguration;


		$code = $_GET['code'];


		$code_verifier = vnSessionGetVar('code_verifier');


		$curl = new \Curl\Curl();


		$curl->setHeaders(array(


			'secret_key' => $this->secret_key,


			'Content-Type' => 'application/x-www-form-urlencoded'


		));


		$url = "https://oauth.zaloapp.com/v4/oa/access_token";


		$data = [


			"code" => $code,


			"app_id" => $this->app_id,


			"grant_type" => "authorization_code",


			"code_verifier" => $code_verifier


		];


		$response = array();


		$curl->post($url, $data);


		if(!$curl->error){


			$response = toArray($curl->response);


			$access_token = $core->get_field($response, "access_token", "");


			$refresh_token = $core->get_field($response, "refresh_token", "");


			$clsConfiguration->updateValue("zalo_oa_access_token", $access_token);


			$clsConfiguration->updateValue("zalo_oa_refresh_token", $refresh_token);


		}


		return $response;


	}


	function refresh_token($refresh_token,$app_id){


		$url = "https://oauth.zaloapp.com/v4/oa/access_token";


		$data = [


			"app_id" => $app_id,


			"refresh_token" => $refresh_token,


			"grant_type" => "refresh_token"


		];


		$curl = new \Curl\Curl();


		$curl->setHeaders(array(


			'secret_key' => 'BItY6oVR2GkP3tYqKH5M'


		));


		$curl->post($url, $data);


		$response = $curl->response;


		return $response;


	}


	function get_followers(){


		global $core, $dbconn, $clsISO, $clsConfiguration;


		$access_token = $clsConfiguration->getValue('zalo_oa_access_token');


		$curl = new \Curl\Curl();


		$curl->setHeaders(array(


			'access_token' => $access_token,


			'Content-Type' => 'application/json'


		));


		$curl->get('https://openapi.zalo.me/v3.0/oa/user/getlist?data={"offset":"0","count":"50","tag_name":"CBNVFH","is_follower":"true"}', []);


		$response = $curl->response;


		$clsISO->print_pre($response); die();





		echo $result;


	}


	function get_follower_detail($user_id){


		global $core, $dbconn, $clsISO, $clsConfiguration;


		$access_token = $clsConfiguration->getValue('zalo_oa_access_token');


		$curl = new \Curl\Curl();


		$curl->setHeaders(array(


			'access_token' => $access_token,


			'Content-Type' => 'application/json'


		));


		$curl->get('https://openapi.zalo.me/v3.0/oa/user/detail?data={"user_id":"'.$user_id.'"}', []);


		$response = $curl->response;


		$clsISO->print_pre($response); die();





		echo $result;


	}


	function send_message($zaloId, $message){


		global $core, $dbconn, $clsISO, $clsConfiguration;


		$access_token = $clsConfiguration->getValue('zalo_oa_access_token');


		$data = [


			"recipient"=>[


				"user_id"=> $zaloId


			], "message"=>[


				"text" => $message


			]


		];


		$curl = new \Curl\Curl();


		$curl->setHeaders(array(


			'access_token' => $access_token,


			'Content-Type' => 'application/json'


		));


		$curl->post("https://openapi.zalo.me/v3.0/oa/message/cs", $data);


		$response = $curl->response;


		$clsISO->print_pre($response); die();


	}


}





