<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| ##################################################################### ||
|| # The Classes configurations of the MaxxCMS                         # ||
|| # MaxxCMS 6.0.0 Future Tech Team (vanthiembui.it@gmail.com)         # ||
|| # ----------------------------------------------------------------  # ||
|| # All PHP code in this file is ©2007-2014 Future Tech JSC.          # ||
|| # This file may not be redistributed in whole or significant part.  # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class ProfileLog extends dbBasic{
	function ProfileLog(){
		global $dbconn;
		$this->pkey = "profile_log_id";
		$this->tbl = DB_PREFIX."profile_log";
	}
	function init(){
		global $core, $mod, $act, $sub, $dbconn, $profile_id, $clsISO, $oneProfile;
		$url = $_SERVER["REQUEST_URI"];
		// $url = strtok($_SERVER["REQUEST_URI"], '?');
		if(!in_array($profile_id, _PROFILE_NOT_ACESS_LOGS_ID) 
			&& !$core->isAjax()
			&& ($mod != 'auth')
			&& $url != '/'
		){
			$url = strtok($_SERVER["REQUEST_URI"], '?');
			$this->insert(array(
				'url' => $url,
				'profile_id' => $profile_id,
				'reg_date' => time()
			));
		}
	}
}		
?>