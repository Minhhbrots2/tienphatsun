<?php
	/**
	*  Created by   :
	*  @author		: Technical Group (technical@aboutpro.com)
	*  @date		: 2009/1/18
	*  @version		: 2.1.1
	*/
	class vnSession {
		/**
		* Check the session exist
		* @return bool
		*/
		public static function exists($name){
			return (isset($_SESSION[$name])) ? true : false;
		}

		/**
		* Write the session
		* @param string name
		* @param string value
		* @return bool
		*/
		public static function put($name, $value){
			return $_SESSION[$name] = $value;
		}
		
		/**
		* Get value of session
		* @param string name
		* @return options
		*/
		public static function get($name){
			return $_SESSION[$name];
		}
		
		/**
		* Delete the session exist
		* @param string name
		* @method void
		*/
		public static function delete($name){
			if(self::exists($name)){
				unset($_SESSION[$name]);
			}
		}

		/**
		* Function to create and display error and success messages
		* @access public
		* @param string session name
		* @param string message
		* @param string display class
		* @return string message
		*/
		public static function flash($name,$type = null,$string = '',$add = false){
			if(self::exists($name)){
				$flash = (array) self::get($name);
				if($add){
					$flash[] = array("type" => $type,"message" => $string);
					self::put($name, $flash);
				}else{
					self::delete($name);
				}
				return $flash;
			}else{
				self::put($name, array(array("type" => $type,"message" => $string)));
			}
		}
	}
	/*
	* Set up session handling
	*/
	if(!isset($SESSION_NAME)){
		$SESSION_NAME = "VNNC";
	}
	/**
	* Initialise session
	*/
	function vnSessionSetup(){
		global $SESSION_NAME, $SESSION_COOKIE, $SESSION_TIME_OUT;
		if(version_compare(PHP_VERSION, '5.4.0', '>=')){
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
		}else{
			if(session_id() == '') {
				session_start();
			}
		}
		return true;
	} 
	/**
	* Get a session variable
	* @param name $ name of the session variable to get
	*/
	function vnSessionGetVar($name){
		global $SESSION_NAME;
		if(vnSession::exists($SESSION_NAME.'_'.$name)){
			return vnSession::get($SESSION_NAME.'_'.$name);
		}
		return false;
	}
	/**
	* Determine a session variable is set or not
	* @param name $ name of the session variable
	*/
	function vnSessionExist($name) {
		global $SESSION_NAME;
		if(vnSession::exists($SESSION_NAME.'_'.$name)){
			return true;
		}
	  	return false;
	}

	/**
	* Set a session variable
	* @param name $ name of the session variable to set
	* @param value $ value to set the named session variable
	*/
	function vnSessionSetVar($name, $value){
		global $SESSION_NAME;
		if(vnSessionExist($name)){
			vnSessionDelVar($name);	
		}
		return vnSession::put($SESSION_NAME.'_'.$name, $value);
	} 
	/**
	* Delete a session variable
	* @param name $ name of the session variable to delete
	*/
	function vnSessionDelVar($name){
		global $SESSION_NAME;
		if(vnSessionExist($name)){
			vnSession::delete($SESSION_NAME.'_'.$name);
			unset($GLOBALS[$SESSION_NAME.'_'.$name]);
		}
		return true;
	}
	
	/**
	* Swich to new url
	* @access: public 
	* @param string $url;
	* @param bool $permanent;
	*/
	if(!function_exists('redirect')){
		function redirect($url = '/', $permanent = false){
			if($permanent) {
				header('HTTP/1.1 301 Moved Permanently');
			}
			header('Location: '.$url);
			exit();
		}
	}
?>