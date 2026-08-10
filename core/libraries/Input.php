<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class Input{
	/*
	|--------------------------------------------
	| Protocol (http or https)
	| 
	| @return  string
	|---------------------------------------------
	|
	*/
	static public function protocol() {
		$secure = (self::server('HTTP_HOST') && self::server('HTTPS') && strtolower(self::server('HTTPS')) !== 'off') ? true : false;
		return ($secure) ? 'https' : 'http';
	}
	/*
	|--------------------------------------------
	| Is AJAX request
	| 
	| @return  bool
	|---------------------------------------------
	|
	*/
	static public function isAjax() {
		return (self::server('HTTP_X_REQUESTED_WITH') && strtolower(self::server('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') ? true : false;
	}
	/*
	|--------------------------------------------
	| Request method (usually GET or POST)
	| 
	| @param   bool    $upper
	| @return  string
	|---------------------------------------------
	|
	*/
	static public function method($upper = true) {
		$method = self::server('REQUEST_METHOD');
		return ($upper) ? strtoupper($method) : strtolower($method);
	}
	/*
	|--------------------------------------------
	| Exists Var
	| @param   string  $var
	| @param   string  $type
	| @return  bool
	|---------------------------------------------
	|
	*/
	public static function exists($var=null, $type = 'post'){
		if(strtoupper($type)=='POST'){
			return (is_null(self::post($var, null, false))) ? false : true;
		}else if(strtoupper($type)=='GET'){
			return (is_null(self::get($var, null, false))) ? false : true;
		}else
			return false;
	}
	/*
	|--------------------------------------------
	| Find $_SERVER value
	| @param  string  $index
	| @param  string  $default
	| @return	mixed
	|---------------------------------------------
	|
	*/
	static public function server($index = '', $def = null) {
		return self::_findFromArray($_SERVER, $index, $def, [], false);
	}
	/*
	|--------------------------------------------
	| Referrer
	| 
	| @param   string  $default
	| @return  string
	|---------------------------------------------
	|
	*/
	static public function referrer($default = null) {
		// If HTTP_REFERER is not found in $_SERVER, $default will be returned
		$referrer = self::server('HTTP_REFERER', $default);
		// If the referrer is null, and the default value is not null, set the referrer to $default
		if (is_null($referrer) && ! is_null($default)) {
			$referrer = $default;
		}
		return $referrer;
	}
	/*
	|--------------------------------------------
	| Find $_GET/$_POST value (similar to $_REQUEST except it does not include $_COOKIE)
	| 
	| @param   string  $item
	| @param   string  $default
	| @param   bool    $xss_clean
	| @return  string
	|---------------------------------------------
	|
	*/
	static public function request($item = null, $default = null, $xss_clean = true) {
		$request = array_merge($_GET, $_POST);
		return self::_findFromArray($request, $item, $default, [], $xss_clean);
	}
	/*
	|--------------------------------------------
	| Find $_POST value
	| 
	| @param   string  $item
	| @param   string  $default
	| @param   bool    $xss_clean
	| @return  mixed
	|---------------------------------------------
	|
	*/
	static public function post($var=null, $default = '', $options = [], $xss_clean = true){
		return self::_findFromArray($_POST, $var, $default, $options, $xss_clean);
	}
	/*
	|--------------------------------------------
	| Find $_GET value
	| 
	| @param   string  $item
	| @param   string  $default
	| @param   bool    $xss_clean
	| @return  mixed
	|---------------------------------------------
	|
	*/
	static public function get($var=null, $default = '', $options = array(), $xss_clean = true){
		return self::_findFromArray($_GET, $var, $default, $options, $xss_clean);
	}
	/*
	|--------------------------------------------
	| Find $_FILE value. If a file field was submitted without a file selected, this may still return a value. It is best to use this method along with Input::hasFile()
	| 
	| @param   string  $item
	| @param   string  $default
	| @return  string
	|---------------------------------------------
	|
	*/
	static public function file($item = null, $default = null) {
		return self::_findFromArray($_FILES, $item, $default, [], false);
	}
	public static function xssClean($str = '') {
		// No data? We're done here
		if (is_string($str) && trim($str) === '') {
			return $str;
		}
		// Recursive sanitize if this is an array
		if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[$key] = self::xssClean($value);
			}
			return $str;
		}
		$str = str_replace(array(
			'&amp;',
			'&lt;',
			'&gt;'
		), array(
			'&amp;amp;',
			'&amp;lt;',
			'&amp;gt;'
		), $str);
		// Fix &entitiy\n;
		$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u', '$1;', $str);
		$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu', '$1$2;', $str);
		$str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
		// remove any attribute starting with "on" or xmlns
		$str = preg_replace('#(<[^>]+[\x00-\x20\"\'\/])(on|xmlns)[^>]*>#iUu', '$1>', $str);
		// remove javascript: and vbscript: protocol
		$str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2nojavascript...', $str);
		$str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu', '$1=$2novbscript...', $str);
		$str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*-moz-binding[\x00-\x20]*:#Uu', '$1=$2nomozbinding...', $str);
		$str = preg_replace('#([a-z]*)[\x00-\x20\/]*=[\x00-\x20\/]*([\`\'\"]*)[\x00-\x20\/]*data[\x00-\x20]*:#Uu', '$1=$2nodata...', $str);
		// Remove any style attributes, IE allows too much stupid things in them, eg.
		// <span style="width: expression( alert( 'Ping!' ));"></span>
		// and in general you really don't want style declarations in your UGC
		$str = preg_replace('#(<[^>]+[\x00-\x20\"\'\/])style[^>]*>#iUu', '$1>', $str);
		// Remove namespaced elements (we do not need them...)
		$str = preg_replace('#</*\w+:\w[^>]*>#i', '', $str);
		// Remove really unwanted tags
		do {
			$oldstring = $str;
			$str = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', '', $str);
		}
		while ($oldstring != $str);
		return $str;
	}
	static private function _findFromArray($array = array(), $item = '', $default = null, $options = array(), $xss_clean = true) {
		// If the array is empty, we are done. Return default value
		if (empty($array)) {
			return $default;
		}
		// Check if an item has been provided. If not, return the entire sanitized array
		if ( ! $item) {
			$arr = array();
			// loop through the full array
			foreach (array_keys($array) as $key) {
				$arr[$key] = self::_fetchFromArray($array, $key, $default, $options, $xss_clean);
			}
			return $arr;
		}
		// Return sanitized item
		return self::_fetchFromArray($array, $item, $default, $options, $xss_clean);
	}
	static private function _fetchFromArray($array, $item = '', $default = null, $options = array(), $xss_clean = true) {
		// Not found. Return default
		if ( ! isset($array[$item])) {
			return $default;
		}
		// Found it! Now clean it
		if ($xss_clean) {
			return self::xssClean($array[$item]);
		}
		// Found it! Return uncleaned
		if(isset($options['ex']) && $options['ex'] == 'no_empty'){
			if(!empty($array[$item]))
				return $array[$item];
			return $default;
		} else {
			return $array[$item];
		}
	}
}
?>