<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # IZCMS Module Private By Technical Group(buivanthiem.it@gmail.com)# ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 by Technical Group       # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- NOT FREE SOFTWARE ----------------              # ||
|| #################################################################### ||
\*======================================================================*/
class Download{
	//private $agent = "Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36";
	private $agent = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.11) Gecko/20071204 Ubuntu/7.10 (gutsy) Firefox/2.0.0.11";
	function __Construct(){
		if(!class_exists('Configuration')){
			require_once(DIR_CLASSES.'/class_Configuration.php');
		}
	}
	function cURLcheckBasicFunctions(){
	  if( !function_exists("curl_init") &&
		  !function_exists("curl_setopt") &&
		  !function_exists("curl_exec") &&
		  !function_exists("curl_close")) return false;
	  else 
		return true;
	} 
	
	/*
	* function make request
	* url : string | url request
	* params : array | params request
	* method : string(POST,GET) | method request
	*/
	private function makeRequest($url, $params, $method = 'POST'){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); // Time out 60s
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); // connect time out 60s

		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_error($ch)) {
			return false;
		}
		if ($status != 200) {
			curl_close($ch);
			return false;
		}
		// close curl
		curl_close($ch);
		return $result;
	}
	/*
	* Returns string status information.
	* Can be changed to int or bool return types.
	*/
	function cURLdownload($url, $file){
		if( !$this->cURLcheckBasicFunctions()) {
			return "UNAVAILABLE: cURL Basic Functions";
		}
		$ch = curl_init();
		if($ch){
			$fp = fopen($file, "w");
			if($fp){
				if(!curl_setopt($ch, CURLOPT_URL, $url)){
					fclose($fp); // to match fopen()
					curl_close($ch); // to match curl_init()
					return "FAIL: curl_setopt(CURLOPT_URL)";
				}
				if ((!ini_get('open_basedir') && !ini_get('safe_mode')) || $redirects < 1) {
					curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					//curl_setopt($ch, CURLOPT_REFERER, 'http://domain.com/');
					if( !curl_setopt($ch, CURLOPT_HEADER, $curlopt_header)) return "FAIL: curl_setopt(CURLOPT_HEADER)";
					if( !curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $redirects > 0)) return "FAIL: curl_setopt(CURLOPT_FOLLOWLOCATION)";
					if( !curl_setopt($ch, CURLOPT_FILE, $fp) ) return "FAIL: curl_setopt(CURLOPT_FILE)";
					if( !curl_setopt($ch, CURLOPT_MAXREDIRS, $redirects) ) return "FAIL: curl_setopt(CURLOPT_MAXREDIRS)";
					return curl_exec($ch);
				} else {
					curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					//curl_setopt($ch, CURLOPT_REFERER, 'http://domain.com/');
					if( !curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false)) return "FAIL: curl_setopt(CURLOPT_FOLLOWLOCATION)";
					if( !curl_setopt($ch, CURLOPT_FILE, $fp) ) return "FAIL: curl_setopt(CURLOPT_FILE)";
					if( !curl_setopt($ch, CURLOPT_HEADER, true)) return "FAIL: curl_setopt(CURLOPT_HEADER)";
					if( !curl_setopt($ch, CURLOPT_RETURNTRANSFER, true)) return "FAIL: curl_setopt(CURLOPT_RETURNTRANSFER)";
					if( !curl_setopt($ch, CURLOPT_FORBID_REUSE, false)) return "FAIL: curl_setopt(CURLOPT_FORBID_REUSE)";
					curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
				}
				// if( !curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true) ) return "FAIL: curl_setopt(CURLOPT_FOLLOWLOCATION)";
				// if( !curl_setopt($ch, CURLOPT_FILE, $fp) ) return "FAIL: curl_setopt(CURLOPT_FILE)";
				// if( !curl_setopt($ch, CURLOPT_HEADER, 0) ) return "FAIL: curl_setopt(CURLOPT_HEADER)";
				if( !curl_exec($ch) ) return "FAIL: curl_exec()";
				curl_close($ch);
				fclose($fp);
				return "SUCCESS: $file [$url]";
			}
			else return "FAIL: fopen()";
		}
		else return "FAIL: curl_init()";
	}
}
?>