<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/ 
$app->post('/auth/google', function ($request, $response, $args) use ($app) {
	global $clsISO, $dbconn, $_LANG_ID;
	$clsProfile = new Profile();
	$clsAccessToken = new AccessToken();
	$JWT = new \Firebase\JWT\JWT();
	$status_code = 400;
	$apiresults = array(
		"result" => "error", 
		'error' => 1, 
		"message" => "Login failed"
	);
	#
	$inputs = $request->getParsedBody();
	$credential = isset($inputs['credential']) ? trim($inputs['credential']) : "";
	$firebase_token  = isset($inputs['firebase_token']) ? trim($inputs['firebase_token']) : "";
	if (!empty($credential)) {
		$json_decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $credential)[1]))));
		$payload = toArray($json_decoded);
		$_id = $payload['sub'];//user id google
		$email = isset($payload['email']) ? $payload['email'] : "";
		$avatar = isset($payload['picture']) ? $payload['picture'] : "";
		$given_name = isset($payload['given_name']) ? $payload['given_name'] : "";
		$family_name = isset($payload['family_name']) ? $payload['family_name'] : "";
		$full_name = sprintf('%s %s', $family_name, $given_name);
		//$gender = isset($payload['gender']) ? $payload['gender'] : "";
		$verified_email = isset($payload['email_verified']) ? $payload['email_verified'] : "";
		$hd = isset($payload['hd']) ? $payload['hd'] : "";
		$link = isset($payload['link']) ? $payload['link'] : "";
		if(!empty($email)){
			$field = "{$clsProfile->pkey},`code`,`email`,`full_name`,`phone`,`address`,`birthday`,`avatar`,`CCID`,`is_active`";
			$oneProfile = $clsProfile->getByCond("`is_trash`=0 AND `is_active`='1' AND `status_id`='"._STATUS_STAFF_ON_ID."' 
				AND (`user_name`='{$email}' OR `email`='{$email}')", $field);
			if(!empty($oneProfile)){ //đã tồn tại
				$status_code = 200;
				$profile_id = $oneProfile[$clsProfile->pkey];
				/** Khởi Tạo Token */
				$expires = time() + TOKEN_LIFTTIME;
				$access_token = $JWT->encode( array(
					'profile_id' => $profile_id,
					'expires' => $expires
				), LICENSE_KEY);
				/** Tạo refresh tocken có thời hạn 1 năm */
				$refresh_token = $JWT->encode( array(
					'profile_id' => $profile_id,
					'expires' => time() + 365*24*60*60
				), LICENSE_KEY);
				# Gennerate access_token
				// $clsAccessToken->deleteByCond("`expires`<'".time()."'");
				// $dbconn->debug = true;
				$clsAccessToken->insert(array(
					'id' => $clsAccessToken->getMaxId(),
					'access_token' => $access_token,
					'refresh_token' => $refresh_token,
					'firebase_token' => $firebase_token,
					'profile_id' => $profile_id,
					'data' => json_encode($oneProfile, JSON_UNESCAPED_UNICODE),
					'expires' => $expires,
					'reg_date' => time(),
					'upd_date' => time()
				));
				// Signup success
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					"logged_data" => $oneProfile,
					'access_token' => $access_token,
					'refresh_token' => $refresh_token,
					'firebase_token' => $firebase_token,
					'message' => "Login success"
				);
			} else {
				$apiresults['message'] = "E-mail address not found";
			}
		} else {
			$apiresults['message'] = "E-mail address not be empty";
		}
	}
	// Return
	echo echoResponse($status_code, $apiresults);
})->add($oauth);
$app->post('/auth/refresh', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO, $_LANG_ID;
	$clsProfile = new Profile();
	$clsAccessToken = new AccessToken();
	/** Default message & status */
	$status_code = 400;
	$apiresults = array("result" => "error", 'error' => 1, "message" => "Login failed");
	/** Request from user */
	$inputs = $request->getParsedBody();
	$refresh = isset($inputs['refresh']) ? $inputs['refresh'] : "";
	if(!empty($refresh)){
        $JWT = new \Firebase\JWT\JWT();
        $tokenDecode = $JWT::decode($refresh, LICENSE_KEY, array('HS256'));
        if ($tokenDecode->expires < time()) {
            return $this->response->withJson([
                'error' => 1,
                'result' => 'token_expired',
                'message' => 'Refresh token expires'
            ], 401);
        }
		$field = "*";
		$tmp = $clsAccessToken->getByCond("`refresh_token`='{$refresh}'", $field);
		if(!empty($tmp)){
			$status_code = 200;
			$expires = time() + TOKEN_LIFTTIME;
			$access_token = $JWT->encode( array(
				'profile_id' => $tmp['profile_id'],
				'expires' => $expires
			), LICENSE_KEY);
			$clsAccessToken->updateOne($tmp[$clsAccessToken->pkey], array(
				'access_token' => $access_token,
				'expires' => $expires,
				'upd_date' => time()
			));
			$apiresults = array(
				"error" => 0,
				"result" => "success",
				"message" => "Refresh token success",
				"access_token" => $access_token,
				"refresh_token" => $refresh
			);
		} else {
			$apiresults = array(
				'error' => 1,
				'result' => 'error',
				'message' => "Access token not found"
			);
		}
	} else {
		$apiresults['message'] = "Refresh token not empty";
	}
	// Return
	return $this->response->withJson($apiresults, 200);
})->add($oauth);
$app->get('/auth/userinfo', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO, $_LANG_ID;
	$clsProfile = new Profile();
	$clsAccessToken = new AccessToken();
	/** Default message & status */
	$status_code = 400;
	$apiresults = array("result" => "error", 'error' => 1, "message" => "Login failed");
	/** Get access token from user */
	$access_token = getBearerToken();
	if(!empty($access_token)){
		$field = "*";
		$tmp = $clsAccessToken->getByCond("`access_token`='{$access_token}'", $field);
		if(!empty($tmp)){
			if ($tmp['expires'] < time()) {
				return $this->response->withJson([
					'error' => 1,
					'result' => 'token_expired',
					'message' => 'Access token expires'
				], 401);
			} else {
				$status_code = 200;
				$logged_data = $tmp['data'];
				$logged_data = $clsISO->to_array_json($logged_data);
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					"logged_data" => $logged_data,
					'access_token' => $tmp['access_token'],
					'refresh_token' => $tmp['refresh_token'],
					'firebase_token' => $tmp['firebase_token']
				);
			}
		} else {
			$apiresults['message'] = 'Access not found';
		}
	} else {
		$apiresults['message'] = 'Access token empty';
	}
	// Return
	return $this->response->withJson($apiresults, $status_code);
})->add($oauth);