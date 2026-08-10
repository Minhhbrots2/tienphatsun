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
class StrSession extends dbBasic{
}
class StringSessionID extends dbBasic{
	function StringSessionID(){
		$this->pkey = "session_key_id";
		$this->tbl = "session_key";
	}	
	function checkStringSessionIDExist($session_id) {
		$arrOneCheck = $this->getByCond("sid='".$session_id."'");
		if(is_array($arrOneCheck) && count($arrOneCheck)>0)
			return $arrOneCheck["session_key_id"];
		else
			return 0;
	}
	function getSessionID($session_key_id) {
		$arrOneCheck = $this->getOne($session_key_id);
		if(is_array($arrOneCheck) && count($arrOneCheck)>0)
			return $arrOneCheck["sid"];
		else
			return "";
	}
}
?>