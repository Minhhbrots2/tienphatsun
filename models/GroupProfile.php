<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class GroupProfile extends dbBasic{
	function __construct(){
		$this->pkey = "group_profile_id";
		$this->tbl = DB_PREFIX."group_profile";
	}
	function getTitle($group_id, $one=null) {
		if(!isset($one['title'])) {
			$one = $this->getOne($group_id,"title");
		}
		return $one['title'];
	} 
}