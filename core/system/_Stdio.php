<?php
class Stdio{
	var $GET	=	array();
	var $POST	=	array();
	var $allow_unicode = true;
	/**
	* Init class
	*/
	function __construct(){}
	/**
	* Parse all _GET, _POST
	*/
	function parse_incoming($flag=true){
		global $HTTP_GET_VARS, $HTTP_POST_VARS;
		$HTTP_GET_VARS = $_GET;
		$HTTP_POST_VARS = $_POST;
		$return = array();
		if($flag ==true && is_array($HTTP_GET_VARS)){
			while( list($k, $v) = each($HTTP_GET_VARS)){
				if( is_array($HTTP_GET_VARS[$k]) ){
					while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) ){
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}else{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		// Overwrite GET data with post data
		if($flag!=true && is_array($HTTP_POST_VARS)){
			while( list($k, $v) = each($HTTP_POST_VARS)){
				if ( is_array($HTTP_POST_VARS[$k])){
					while( list($k2, $v2) = each($HTTP_POST_VARS[$k])){
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}else{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		return $return;
	}
	/**
	* Clean key
	*/
	function clean_key($key) {
		if ($key=="0") return $key;
		if ($key == ""){
			return "";
		}
		$key = preg_replace( "/\.\./"           , ""  , $key );
		$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
		$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
		return $key;
	}
	/**
	* Clean value
	*/
	function clean_value($val) {
		if ($val == ""){
			return "";
		}
		$val = str_replace( "&#032;", " ", $val );
		$val = str_replace( chr(0xCA), "", $val );  //Remove sneaky spaces
		$val = str_replace( "&"	, "&amp;"         , $val );
		$val = str_replace( "<!--" , "&#60;&#33;--"  , $val );
		$val = str_replace( "-->" , "--&#62;"       , $val );
		$val = preg_replace( "/<script/i" , "&#60;script"   , $val );
		$val = str_replace( ">" , "&gt;"          , $val );
		$val = str_replace( "<" , "&lt;"          , $val );
		$val = str_replace( "\"" , "&quot;"        , $val );
		$val = preg_replace( "/\n/" , "<br>"          , $val ); // Convert literal newlines
		$val = preg_replace( "/\\\$/", "&#036;"        , $val );
		$val = preg_replace( "/\r/"  , ""              , $val ); // Remove literal carriage returns
		$val = str_replace( "!" , "&#33;"         , $val );
		$val = str_replace( "'" , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
		// Ensure unicode chars are OK
		if ( $this->allow_unicode ){
			$val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val );
		}
		// Strip slashes if not already done so.
		if ( get_magic_quotes_gpc()){
			$val = stripslashes($val);
		}
		// Swop user inputted backslashes
		$val = preg_replace( "/\\\(?!&amp;#|\?#)/s", "&#092;", $val );
		$val = preg_replace( "/<br\s?\/?>/s", "", $val );
		return $val;
	}
	//Customer Function
	function GET($var, $def=""){
		return isset($_GET[$var])? $_GET[$var] : $def;
	}
	function POST($var, $def=""){
		return isset($_POST[$var])? $_POST[$var] : $def;
	}
}
?>