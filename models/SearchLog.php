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
class SearchLog extends dbBasic {
	function __construct () {
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."search_logs";
	}
	function insertLog($keyword){
		global $profile_id, $loggedIn;
		if($loggedIn != 1) $profile_id = 0;
		$this->insert(array(
			$this->pkey => $this->getMaxId(),
			'keyword' => $keyword,
			'reg_date' => time(),
			'user_id' => $profile_id,
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'_from' => "FH",
		));
	}
} 