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
class Calendar extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."calendar";	
	}
	function get_staffs($list_department_id, $list_group_id, $list_staff_id){
		global $core, $dbconn, $clsISO, $profile_id;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		$clsGroupProfile = new GroupProfile();
		
		$list_staffs = $list_staff_id;
		if(!empty($list_group_id)){
			foreach($list_group_id as $group_id){
				$one_group = $clsGroupProfile->getOne($group_id);
				$list_profile_id = $one_group['list_profile_id'];
				$arrs = $clsISO->getArrayByTextSlash($list_profile_id);
				if(!empty($arrs)){
					foreach($arrs as $staff_id){
						if(!in_array($staff_id, $list_staffs)){
							$list_staffs[] = $staff_id;
						}
					}
				}
			}
		}
		if(!empty($list_department_id)){
			foreach($list_department_id as $dep_id){
				$arrs = $clsProfile->getAll("`is_trash`=0 and `status_id`='"._STATUS_STAFF_ON_ID."' 
					and `department_id`='{$dep_id}'", $clsProfile->pkey);
				if(!empty($arrs)){
					foreach($arrs as $val){
						if(!in_array($val[$clsProfile->pkey], $list_staffs)){
							$list_staffs[] = $val[$clsProfile->pkey];
						}
					}
				}
			}
		}
		if(!empty($list_staffs))
			$list_staffs = array_diff($list_staffs, array($profile_id));
		// Return
		return $list_staffs;
	}
	function sendEmail($calendar_id, $staff_id){
		
	}
}