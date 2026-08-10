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
class RequestPTG extends dbBasic{
	function __construct(){
		global $_LANG_ID;
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."request_ptg";
	}
	function checkRequest($stock_id){
		global $profile_id;
		$check = $this->getByCond("`status_id`='0' AND `user_id`='{$profile_id}' AND `stock_id`='{$stock_id}'");
		if(!empty($check)) {
			return 1;
		}
		return 0;
	}
}
?>
