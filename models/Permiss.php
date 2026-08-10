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
class Permiss extends dbBasic{
	function __construct(){
		$this->pkey = "permiss_id";
		$this->tbl = DB_PREFIX."permiss";
	}
	// Cây quyền theo hệ: nhóm cha (parent_id=0) -> list_items con. $profile_type: 'MOC' | 'MF' | 'user.fh'.
	function getTree($profile_type){
		$profile_type = preg_replace('/[^A-Za-z0-9_.]/', '', $profile_type);
		$field = "{$this->pkey},title,code";
		$groups = $this->getAll("`profile_type`='{$profile_type}' AND `parent_id`=0 AND `is_trash`=0 ORDER BY `order_no` ASC", $field);
		if(empty($groups)) return array();
		foreach($groups as $key => $val){
			$parent_id = $val[$this->pkey];
			$groups[$key]['list_items'] = $this->getAll("`profile_type`='{$profile_type}' AND `parent_id`='{$parent_id}' AND `is_trash`=0 ORDER BY `order_no` ASC", $field);
		}
		return $groups;
	}
	// Toàn bộ code quyền (lá) của 1 hệ — dùng để tính diff khi lưu.
	function getAllCodes($profile_type){
		$profile_type = preg_replace('/[^A-Za-z0-9_.]/', '', $profile_type);
		$rows = $this->getAll("`profile_type`='{$profile_type}' AND `parent_id`<>0 AND `is_trash`=0", "code");
		$codes = array();
		if(!empty($rows)){
			foreach($rows as $r){ if($r['code'] !== '') $codes[] = $r['code']; }
		}
		return $codes;
	}
	// Code quyen (la) dang bi an (is_active=0) — de cho luu chua, khong go nham.
	function getHiddenCodes($profile_type){
		$profile_type = preg_replace('/[^A-Za-z0-9_.]/', '', $profile_type);
		$rows = $this->getAll("`profile_type`='{$profile_type}' AND `parent_id`<>0 AND `is_trash`=0 AND `is_active`=0", "code");
		$codes = array();
		if(!empty($rows)){
			foreach($rows as $r){ if($r['code'] !== '') $codes[] = $r['code']; }
		}
		return $codes;
	}
}