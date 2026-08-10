<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class AdminLog extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."adminlog";
	}
	function insertLog($action, $stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE,
		$project_id = 0, $target_id = 0, $description = array(), $from_site = '_admin'){
		global $core, $dbconn, $profile_id;
		if($from_site == '_admin'){
			$user_id = $core->_USER['user_id'];
		} else {
			$user_id = $profile_id;
		}
		$this->insert(array(
			$this->pkey => $this->getMaxId(),
			'action' => $action,
			'stock_type' => $stock_type,
			'project_id' => $project_id,
			'target_id' => $target_id,
			'description' => json_encode($description, JSON_UNESCAPED_UNICODE),
			'user_id' => $user_id,
			'from_site' => $from_site,
			'date' => time()
		));
	}
}