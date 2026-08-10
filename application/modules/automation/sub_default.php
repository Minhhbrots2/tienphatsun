<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	#
	$clsTemplate = new Template();
	#
	$cond .= "`user_id`='{$profile_id}' OR (`is_share` = 1) ";
	
	#
	$current_page = Input::get('page',1);
	$per_page  = 20;
	$total_record = $clsTemplate->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	$order_by = " ORDER BY upd_date DESC ";
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> str_replace(".html","/",$clsISO->getLink("template"))
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(1,$str_url);
	
	$lstItem = $clsTemplate->getAll($cond.$order_by.$limitCond);
//	$clsISO->print_pre($lstItem);die;
	#
	$assign_list["lstItem"] = $lstItem;
	$assign_list['total_record'] = $total_record;
	$assign_list["html_pager"] = $html_pager;
	$assign_list['clsTemplate'] = $clsTemplate;
	
    /*=============Title & Description Page==================*/
	$title_page = 'Quản lý mẫu lời chúc - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_ajOpen() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	#
	$clsAutomation = new Automation();
	
	#
	$uid = $clsISO->getUniqid();
	$id = (int) Input::post("id", 0);
	if(!empty($id)) {
		$oneItem = $clsAutomation->getOne($id);
		$assign_list["oneItem"] = $oneItem;
	}
	#
	$assign_list["uid"] = $uid;
	$assign_list["id"] = $id;
	
	#
	$html = $core->build("_ajax.open.tpl");
	
	echo json_encode(array(
		"uid"	=>	$uid,
		"html"	=>	$html,
	));die;
}
function default_loadObject() {
	ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	#
	$clsProperty = new Property();
	$clsAutomation = new Automation();
	$apply_to = Input::post("apply_to",'all');
	$to_value = Input::post("to_value","");
	$arr_value = $clsISO->getArrayByTextSlash($to_value);
	$clsGroupProfile = new GroupProfile();
	
	$html = "";
	if($apply_to == "department") {
		$html = '<label class="w-100 form-label mb-1">Phòng ban</label>
				<select name="to_value" class="form-select form-control iso-select2" data-placeholder="Chọn phòng ban" multiple>';
		$html .= $clsProperty->getSelectByPropertyV2("_DEPARTMENT",$arr_value);		
		$html .='</select>';
	}else if($apply_to == "group") {
		$lstGroup = $clsGroupProfile->getAll("`is_trash`='0' AND `is_online`='1'");
		$html = '<label class="w-100 form-label mb-1">Nhóm</label>
				<select name="to_value" class="form-select form-control iso-select2" data-placeholder="Chọn nhóm nhân viên" multiple>';
		foreach ($lstGroup as $key => $val) {
			$select = "";
			if($clsISO->checkItemInArray($val[$clsGroupProfile->pkey],$arr_value)) {
				$select = " selected ";
			}
			$html .= '<option value="'.$val[$clsGroupProfile->pkey].'" '.$select.'>'.$val["title"].'</option>';
		}		
		$html .='</select>';
	}	
	echo json_encode(array(
		"html"	=>	$html,
	));die;
}
function default_ajSaveTemplate() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	#
	$clsTemplate = new Template();	
	#
	$uid = $clsISO->getUniqid();
	$template_id = (int) Input::post("template_id", 0);
	$title = Input::post("title", "");
	$content = Input::post("content", "");
	$is_share = (int) Input::post("is_share", 0);
	
	$res = ["result"	=> false,"msg"=>"Lỗi"];
	if(!empty($template_id)) {
		$oneItem = $clsTemplate->getOne($template_id);
		$data_upd = [
			"title"	=>	$title,
			"content"	=>	addslashes($content),
			"_type"		=>	"_wish",
			"is_share"	=> $is_share,
			"user_update_id"	=>	$profile_id,		
			"upd_date"	=>	time(),		
		];
		if($clsTemplate->updateOne($template_id,$data_upd)) {
			$res = ["result"	=> true,"msg"=>"Cập nhật thành công"];
		}
	}else{
		$data_upd = [
			$clsTemplate->pkey	=>	$clsTemplate->getMaxID(),
			"title"	=>	$title,
			"content"	=>	addslashes($content),
			"_type"		=>	"_wish",
			"is_share"	=> $is_share,
			"user_id"	=>	$profile_id,		
			"user_update_id"	=>	$profile_id,		
			"reg_date"	=>	time(),		
			"upd_date"	=>	time(),		
		];
		if($clsTemplate->insert($data_upd)) {
			$res = ["result"	=> true,"msg"=>"Thêm thành công"];
		}
	}
	
	echo json_encode($res);die;
}