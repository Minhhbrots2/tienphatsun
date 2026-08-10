<?php
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
	error_reporting(0);
	ini_set('display_errors',0);
	define('IS_ADMIN_PAGE', 0);
	define("_SITE_ROOT", 'API');
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	require_once(ABSPATH . DS . 'init.php');
	define('URL_IMAGES', URL_THEMES . '/images');
	define('URL_CSS', URL_THEMES . '/css');
	define('URL_CSS', URL_THEMES . '/js');
	// Defined api_key for client access
    define('API_KEY', md5('VietISO-'.DB_NAME));
    //var_dump(API_KEY);die();
    //echo API_KEY; die();
	define("ADODB_DEBUG", 	false);//debug or not
	define("STOP_APP_IF_ERROR", 1);//stop if error happen 0: no, 1: yes
    function getHeaders(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = $_SERVER;
        }  elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $headers = array_combine(
                array_map('ucwords', array_keys($requestHeaders)),
                array_values($requestHeaders)
            );
        }
        return $headers;
    }
	/*
	|-------------------------------------------------------
	| LANGUAGE
	|-------------------------------------------------------
	*/
    $getHeaders = getHeaders();
    #- Required Language
    $_LANG_ID = 'vn';
	// !empty($getHeaders['Accept-Language']) ? $getHeaders['Accept-Language'] : 
    require_once (DIR_LANG.DS."{$_LANG_ID}.php");
    function ___($key){
        global $_FRONTLANG;
        if(isset($_FRONTLANG[$key])){
            return $_FRONTLANG[$key];
        } else {
            return $key;
        }
    }
	/*
	|-------------------------------------------------------
	| Core Requirement
	|-------------------------------------------------------
	*/
	require_once(DIR_COMMON."/DbBasic.php");
	require_once(DIR_COMMON."/App.php");
	require_once(DIR_COMMON."/Core.php");
	// require_once(DIR_COMMON."/download.php");
	require_once(DIR_COMMON."/Upload.php");
	// require_once(DIR_COMMON."/Response.php");
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	require_once DIR_INCLUDES."/curl/vendor/autoload.php";
	require_once DIR_INCLUDES."/carbon/vendor/autoload.php";
	require_once(DIR_INCLUDES.'/php-simple-redis-cache/vendor/autoload.php');
	/*
	|-------------------------------------------------------
	| Class Requirement
	|-------------------------------------------------------
	*/
	if(function_exists('spl_autoload_register')){
		function autoload($model){
			if(file_exists(DIR_MODELS.DS.$model.'.php')){
				require_once(DIR_MODELS.DS.$model.'.php');
			} else if(file_exists(DIR_MODELS.'/class.'.$model.'.php')){
				require_once(DIR_MODELS.'/class.'.$model.'.php');
			}
		}
		spl_autoload_register('autoload');
	}
	/*
	|-------------------------------------------------------
	| Helper Requirement
	|-------------------------------------------------------
	*/
	require_once(DIR_COMMON.DS.'Common.php');
	if (is_dir(DIR_LIB)){
		$customLibArray = array();
		if ($dh = opendir(DIR_LIB)) {
			while (($file = readdir($dh)) !== false) {
				if (substr($file, -3)=='php')
				array_push($customLibArray, $file);
			}
			closedir($dh);
		}
		if(!empty($customLibArray)){
			foreach ($customLibArray as $customLib){
				require_once(DIR_LIB."/".$customLib);
			}
		}
	}
	/*
	|-------------------------------------------------------
	| Helper Tocken
	|-------------------------------------------------------
	*/
	require_once(DIR_INCLUDES.DS.'FirebaseJWT/BeforeValidException.php');
	require_once(DIR_INCLUDES.DS.'FirebaseJWT/ExpiredException.php');
	require_once(DIR_INCLUDES.DS.'FirebaseJWT/SignatureInvalidException.php');
	require_once(DIR_INCLUDES.DS.'FirebaseJWT/JWT.php');
	#DriverDatabase
	require_once(DIR_ADODB."/adodb.inc.php");
	$dbconn = ADONewConnection(DB_TYPE);
	$dbconn->debug = ADODB_DEBUG;
	if (isset($dbinfo) && is_array($dbinfo)){
		$dbconn->connect($dbinfo['host'], $dbinfo['user'], $dbinfo['pass'], $dbinfo['db']);
		// $dbconn->EXECUTE("set names 'utf8'");
	}else{
		$dbconn->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		// $dbconn->EXECUTE("set names 'utf8'");
	}
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$offset="+7:00";
	$dbconn->Execute("SET time_zone='".$offset."';");
	##
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;
	require DIR_INCLUDES.DS.'Slim3.0'.DS.'/vendor/autoload.php';
	$config = ['settings' => [
		'addContentLengthHeader' => false,
		'displayErrorDetails' => true
	]];
	$core = new Core();
    $clsISO = new ISO();
    $clsConfiguration = new Configuration();
	$app = new \Slim\App($config);
	/**
	 * Adding Middle Layer to authenticate every request
	 * Checking if the request has valid api key in the 'Authorization' header
	 */
	/** Get X-XPI-KEY */
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
    // Getting request headers
    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(
                array_map('ucwords', array_keys($requestHeaders)),
                array_values($requestHeaders)
            );
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    function getBearerToken() {
        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
	function echoResponse($status_code, $response) {
		$http = array(
			100 => 'HTTP/1.1 100 Continue',
			101 => 'HTTP/1.1 101 Switching Protocols',
			200 => 'HTTP/1.1 200 OK',
			201 => 'HTTP/1.1 201 Created',
			202 => 'HTTP/1.1 202 Accepted',
			203 => 'HTTP/1.1 203 Non-Authoritative Information',
			204 => 'HTTP/1.1 204 No Content',
			205 => 'HTTP/1.1 205 Reset Content',
			206 => 'HTTP/1.1 206 Partial Content',
			300 => 'HTTP/1.1 300 Multiple Choices',
			301 => 'HTTP/1.1 301 Moved Permanently',
			302 => 'HTTP/1.1 302 Found',
			303 => 'HTTP/1.1 303 See Other',
			304 => 'HTTP/1.1 304 Not Modified',
			305 => 'HTTP/1.1 305 Use Proxy',
			307 => 'HTTP/1.1 307 Temporary Redirect',
			400 => 'HTTP/1.1 400 Bad Request',
			401 => 'HTTP/1.1 401 Unauthorized',
			402 => 'HTTP/1.1 402 Payment Required',
			403 => 'HTTP/1.1 403 Forbidden',
			404 => 'HTTP/1.1 404 Not Found',
			405 => 'HTTP/1.1 405 Method Not Allowed',
			406 => 'HTTP/1.1 406 Not Acceptable',
			407 => 'HTTP/1.1 407 Proxy Authentication Required',
			408 => 'HTTP/1.1 408 Request Time-out',
			409 => 'HTTP/1.1 409 Conflict',
			410 => 'HTTP/1.1 410 Gone',
			411 => 'HTTP/1.1 411 Length Required',
			412 => 'HTTP/1.1 412 Precondition Failed',
			413 => 'HTTP/1.1 413 Request Entity Too Large',
			414 => 'HTTP/1.1 414 Request-URI Too Large',
			415 => 'HTTP/1.1 415 Unsupported Media Type',
			416 => 'HTTP/1.1 416 Requested Range Not Satisfiable',
			417 => 'HTTP/1.1 417 Expectation Failed',
			500 => 'HTTP/1.1 500 Internal Server Error',
			501 => 'HTTP/1.1 501 Not Implemented',
			502 => 'HTTP/1.1 502 Bad Gateway',
			503 => 'HTTP/1.1 503 Service Unavailable',
			504 => 'HTTP/1.1 504 Gateway Time-out',
			505 => 'HTTP/1.1 505 HTTP Version Not Supported',
		);
		header($http[$status_code]);
		header('Content-Type: application/json');
		echo @json_encode($response, JSON_UNESCAPED_UNICODE); die();
	}
	/** Middleware Check User LoggedIn */
	$oauth = function($request, $response, $next){
		$license_key = get_license_header();
		if(!empty($license_key)){
			if($license_key != X_API_KEY){
				echoResponse(200, array(
					'error' => 1,
					'result' => 'error',
					'message' => __('Api key invalid'),
				));
			} else {
				$response = $next($request, $response);
			}
		} else {
			echoResponse(200, array(
				'error' => 1,
				'result' => 'error',
				'message' => __('Api key not found')
			));
		}
		return $response;
	};
	$authenticate = function($request, $response, $next) use ($app){
		global $clsISO;
		// $JWT = new \Firebase\JWT\JWT();
		$access_token = getBearerToken();
		if(empty($access_token)){
			echoResponse(403, array(
				'error' => 1,
				'result' => 'error',
				'message' => 'Authorization Not Empty'
			));
		} else {
			if($access_token == LICENSE_KEY){
				$response = $next($request, $response);
			} else {
				echoResponse(404, array(
					'error' => 1,
					'result' => 'error',
					'message' => 'Authorization Not Found'
				));
			}
		}
		return $response;
	};
?>