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
class IssueTarget extends dbBasic{
	function __construct(){
		$this->pkey = "issue_target_id";
		$this->tbl = DB_PREFIX."issue_target";
	}
	function getTitle($issue_target_id, $oDataTable = array()){
		global $core, $dbconn;
		if(!isset($oDataTable['title']))
			$oDataTable = $this->getOne($issue_target_id, "title");
		return $oDataTable['title'];
	}
}