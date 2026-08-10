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
class StockCheck extends dbBasic {
	function __construct () {
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."stock_check";
	}
	function insertLog($from_site='user.FH', $stock_id, $agency_id = 0){
		global $dbconn, $profile_id, $loggedIn;
		if($loggedIn != 1) $profile_id = 0;
		// $dbconn->debug=true;
		$this->insert(array(
			$this->pkey => $this->getMaxId(),
			'from_site' => $from_site,
			'stock_id' => $stock_id,
			'user_id' => $profile_id,
			'reg_date' => time()
		));
	}
} 