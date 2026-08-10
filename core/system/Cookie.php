<?php
function toArray($d){
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	} else {
		// Return array
		return $d;
	}
}
class VnCookie{
	var $name  		=  	"";
	var $value		= 	"";
	var $arrVar    	=  	array();
	var $expires	=  	0;
	/**
	 * Init class
	 */
	function __construct($_name, $_expires = ""){
		$this->name  =  $_name;
		if($_expires){
			$this->expires  =  $_expires;
		}else{
			$this->expires  =  time() + 60*60*24*5;//~5 days
		}
		$this->extractAll($this->name, false);
	}
	/**
	 * Extract all cookie var
	 */
	function extractAll($name = "", $register_global=false){
		if(!isset($_COOKIE)){
			global $_COOKIE;
			$_COOKIE  =  $GLOBALS["HTTP_COOKIE_VARS"];
		}
		if(!empty($_COOKIE[$name])){
			if(get_magic_quotes_gpc()){
				$_COOKIE[$name] = stripslashes($_COOKIE[$name]);
			}
			$JWT = new \Firebase\JWT\JWT();
			$arr = toArray($JWT::decode($_COOKIE[$name], ENCRYPTION_KEY, array('HS256')));
			//check regist global
			if ($register_global){		
				if($arr!== false && is_array($arr)){		
					foreach($arr as $var  => $val){								
						$_COOKIE[$var] = $val;			
						if(isset($GLOBALS["PHP_SELF"])){
							$GLOBALS[$var] = $val;
						}
					}
				}	
			}
			$this->arrVar = $arr;
		} 
		unset($_COOKIE[$name]);
		unset($GLOBALS[$name]);
	}
	/**
	 * Get var
	 */
	function getVar($var, $def=null){
		if(isset($this->arrVar[$var])){
			return $this->arrVar[$var];
		}
		return $def;
	}
	/**
	 * Put $var = $value
	 */
	function putVar($var, $value=""){
		$_COOKIE[$var] = $value;
		$this->arrVar["$var"] = $value;
		if(isset($GLOBALS["PHP_SELF"])){
			$GLOBALS[$var] = $value;
		}
		if(empty($value)){
			unset($this->arrVar[$var]);
		}
	}
	/**
	 * Clear all value
	 */
	function clearVar(){
		$this->arrVar = array();
		@setcookie("{$this->name}", "", time() - 3600, "/", DOMAIN_SESSION);
	}
	/**
	 * Set cookie after put
	 */
	function setVar(){
		global $clsISO;
		if(!empty($this->arrVar)){
			$JWT = new \Firebase\JWT\JWT();
			$this->value = $JWT::encode($this->arrVar, ENCRYPTION_KEY);
			if(strlen($this->value)>4*1024){
				//error length of cookie variable
				return 0;
			}
			@setcookie("{$this->name}", "{$this->value}", $this->expires, "/", DOMAIN_SESSION);
		}
	}
}
?>