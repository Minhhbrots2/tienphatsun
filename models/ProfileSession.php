
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
class ProfileSession extends dbBasic{
	function __construct(){
		$this->pkey = "profile_session_id";
		$this->tbl = DB_PREFIX."profile_session";
	}
	function insertLog($profile_id, $act='login'){
		$profile_log_id = $this->getMaxId();
		$this->insert(array(
			$this->pkey => $profile_log_id,
			'profile_id' => $profile_id,
			'act' => $act,
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'browser' => $_SERVER['HTTP_USER_AGENT'],
			'reg_date' => time()
		));
		return $profile_log_id;
	}
}		
?>