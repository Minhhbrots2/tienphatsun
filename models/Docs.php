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
class Docs extends dbBasic{
	function __construct(){
		$this->pkey = "doc_id";
		$this->tbl = DB_PREFIX."docs";
	}
	function getLink($doc_id) {
		global $core;
		return "/van-ban-he-thong.html?doc=".$core->encryptId($doc_id);
	}
	function getLinkFolder($cat_id,$one=null) {
		$clsProperty = new Property();
		if(!isset($one['slug'])) {
			$one = $clsProperty->getOne($cat_id,"slug");
		}
		return "/van-ban-he-thong/folder/".$one['slug']."-d".$cat_id.".html";
	}
	function getTypeUrl($mimeType){
		if(preg_match('/\/(pdf)$/i', $mimeType)) {
			return "pdf";
		}else if(preg_match('/\/(msword|vnd.openxmlformats-officedocument.wordprocessingml.document|plain)$/i', $mimeType)){
			return "doc";
		}else if(preg_match('/\/(vnd.ms-excel|vnd.openxmlformats-officedocument.spreadsheetml.sheet|xlsx)$/i', $mimeType)){
			return "excel";
		}else if(preg_match('/\/(mp4|x-msvideo|webm)$/i', $mimeType)){
			return "video";
		}else{
			return "image";
		}
	}
	function getIframeGoogleDoc($url,$type="doc"){
		global  $clsISO;
		$gg_id = $clsISO->getGoogleId($url);
		if($type == "doc") {
			return sprintf('https://docs.google.com/document/d/%s/edit', $gg_id);
		}else if($type == "excel") {
			return sprintf('https://docs.google.com/spreadsheets/d/%s/edit', $gg_id);
		}
	}
	function getRoleProfile($arr_role_docs,$dep_id){
		global $clsISO;
		$clsProperty = new Property();
		$arrDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		$result = [];
		if(!empty($arrDepartment[$dep_id])) {
			$title_dep = $arrDepartment[$dep_id]['title'];
			$result = array_filter($arr_role_docs, function($item) use ($title_dep) {
				return strpos($item, $title_dep."-") !== false;
			});
			$result = array_map(function($item) {
				return explode("-", $item)[1];
			}, $result);
		}
		
		return $result;
	}
	function getRoleProfile2($arr_role_docs){
		global $clsISO;
		$clsProperty = new Property();
		$arrRole = $clsProperty->getArraySearchByKey("_ROLE");
		$result = [];
		foreach ($arr_role_docs as $item) {
			$tmp = explode("-",$item);
			if(count($tmp) == 2) {
				$title_role = $arrRole[$tmp[1]]['title'];
				$result[] = "[".$tmp[0]."]".$title_role;
			}
		}
		/*if(!empty($arrDepartment[$dep_id])) {
			$title_dep = $arrDepartment[$dep_id]['title'];
			$result = array_filter($arr_role_docs, function($item) use ($title_dep) {
				return strpos($item, $title_dep."-") !== false;
			});
			$result = array_map(function($item) {
				return explode("-", $item)[1];
			}, $result);
		}*/
		
		return $result;
	}
	function getRoleDoc($arr_role_docs,$dep_id){
		global $clsISO;
		$clsProperty = new Property();
		$arrDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		$arrRole = $clsProperty->getArraySearchByKey("_ROLE");
		$result = [];
		if(!empty($arrDepartment[$dep_id])) {
			$title_dep = $arrDepartment[$dep_id]['title'];
			$result = array_filter($arr_role_docs, function($item) use ($title_dep) {
				return strpos($item, $title_dep."-") !== false;
			});
			$result = array_map(function($item) use ($arrRole)  {
				$tmp = explode("-", $item);
				$role_id = $tmp[1];
				$role_name = $arrRole[$role_id]["title"];
				return "[".$tmp[0]."] Chức vụ ".$role_name;
			}, $result);
		}
		
		return $result;
	}
	function getUserFollowDoc($arr_deparment_id=array(),$arr_role_id=array(),$is_notify=false){
		global $profile_id;
		$clsProfile = new Profile();
		$assign_to_id = array();
		$cond = "`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		if($is_notify){
			$cond .= " AND `{$clsProfile->pkey}`<>'{$profile_id}'";
		}
		if(!empty($arr_deparment_id)){
			$cond .= " AND (";
			$first = 0;
			foreach ($arr_deparment_id as $k_dep => $dep_id) {
				if(!empty($arr_role_id)) {
					$arr_role = $this->getRoleProfile($arr_role_id,$dep_id);
					if(!empty($arr_role)) {
						$cond .= (($first > 0) ? " OR " : "") . " (`list_department_id` LIKE '|%".$dep_id."%|'  AND `role_id` IN (".implode(',',$arr_role)."))";
						$first = 1;
					}
				}else{
					$cond .= (($k_dep > 0) ? " OR " : "") . " `list_department_id` LIKE '|%".$dep_id."%|'";
				}
				
			}
			$cond .= " )";		
			
		}
//		$clsProfile->setDeBug(1);
		$lstProfile = $clsProfile->getAll($cond,$clsProfile->pkey);
//		$clsISO->print_pre($lstProfile);die;
		foreach($lstProfile as $k => $val) {
			$assign_to_id[] = $val[$clsProfile->pkey];
		}
		return $assign_to_id;
		
	}
	function sendEmail($doc_id,$assign_to_id,$action){
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		$clsFolder = new Folder();
		$clsEmailTemplate = new EmailTemplate();
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_NOTIFY_DOC);
		$subject = $clsEmailTemplate->getSubject(_MAIL_NOTIFY_DOC,$oneEmailTemplate);
		$message = $clsEmailTemplate->getContent(_MAIL_NOTIFY_DOC,$oneEmailTemplate);	
		$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_NOTIFY_DOC,$oneEmailTemplate);	
		$fromName = $clsEmailTemplate->getFromName(_MAIL_NOTIFY_DOC,$oneEmailTemplate);	
//		 $clsISO->print_pre($oneEmailTemplate); die();
		$oneItem = $this->getOne($doc_id);
		if($action == "update") {
			$action_doc = "Sửa đổi văn bản hệ thống";
		}else if($action == "insert") {
			$action_doc = "Thêm mới văn bản hệ thống";
		}
		$link_detail = $clsFolder->getLink($oneItem["cat_id"])."?doc=".$core->encryptId($doc_id);
		$replace_fields = array(
			'{action_doc}' => $action_doc,
			'{doc_title}' => $oneItem["title"],
			'{user_create_doc}' => $oneProfile["full_name"],
			'{doc_link}' => sprintf('<a href="%s">%s</a>',DOMAIN_URL.$link_detail, DOMAIN_URL.$link_detail),
			'{PCMS_URL}' => sprintf('<a href="%s">%s</a>',DOMAIN_URL, DOMAIN_SESSION),
		);
		foreach($replace_fields as $key => $val){
			$subject = str_replace($key, $val, $subject);
			$message = str_replace($key, $val, $message);
		}
//		$clsISO->print_pre($message);die;
		// Send Email
		if(!empty($assign_to_id)) {
			$lstAssign = $clsProfile->getAll("`{$clsProfile->pkey}` IN (".implode(',',$assign_to_id).")",'code,full_name,first_name,last_name,email');
			if(!empty($lstAssign)) {
				foreach ($lstAssign as $key => $val) {
					$toemail = $val["email"];
					$toname = $clsProfile->getIndentityV2($val["profile_id"], $val, false);
					$is_send_email = $clsISO->sendEmailSystem($fromemail,$fromName,$toemail, $toname, $subject, $message);
				}
			}
		}
		
		// $clsISO->print_pre($is_send_email); die();
		return $is_send_email;
	}
	function getLabelLog($field){
		switch($field) {
			case "title": 
				$label = "Tiêu đề";
				break;
			case "document_number": 
				$label = "Số hiệu";
				break;
			case "cat_id": 
				$label = "Danh mục";
				break;
			case "list_department_id": 
				$label = "Phòng ban";
				break;
			case "list_role_id": 
				$label = "Vai trò";
				break;
			case "list_tags": 
				$label = "Tags";
				break;
			case "effective_date": 
				$label = "Ngày ban hành";
				break;
			case "authorized_person": 
				$label = "Người ban hành";
				break;
			case "file_doc": 
				$label = "File đính kèm";
				break;
			case "content": 
				$label = "Nội dung";
				break;
			default:
				$label = "";
		}
		return $label;
	}
}