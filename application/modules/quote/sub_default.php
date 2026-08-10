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
	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	#
	$clsClassTable = new Quote();
	$cachedFile = DIR_CACHE_JSON.'/account/quote/quote_profile_'.$profile_id.'.json';
	$decoder = new Webmozart\Json\JsonDecoder();
	if(file_exists($cachedFile)){
		$lstQuoteProfile = $decoder->decodeFile($cachedFile);
	}
	$arr_quote = !empty($lstQuoteProfile[$profile_id]) ? $lstQuoteProfile[$profile_id] : array();
	#
	$cond = "`user_id`='{$profile_id}' OR `apply_to`='all' OR (`apply_to` = 'department' AND `share_ids` LIKE '%|{$oneProfile["department_id"]}|%') OR (`apply_to` = 'profile' AND `share_ids` LIKE '%|{$profile_id}|%') ";
	
	#
	$current_page = Input::get('page',1);
	$per_page  = 500;
	$total_record = $clsClassTable->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	$order_by = " ORDER BY upd_date DESC ";
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> str_replace(".html","/",$clsISO->getLink("quote"))
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(1,$str_url);
	$lstItem = $clsClassTable->getAll($cond.$order_by.$limitCond);
	$total_show = 0;
	foreach ($lstItem as $key => $val) {
		$apply_to = $val["apply_to"];
		$share_ids = $clsISO->getArrayByTextSlash($val["share_ids"]);
		if($apply_to == "department") {
			$html_share = '<span data-url="/index.php?mod=quote&act=load_list_share&table_id='.$val[$clsClassTable->pkey].'&apply_to='.$apply_to.'" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile text-nowrap cursor-pointer"><i class="bx bx-user align-top"></i>'.count($share_ids).' phòng ban</span>';
		}elseif($apply_to == "group") {
			$html_share = '<span data-url="/index.php?mod=quote&act=load_list_share&table_id='.$val[$clsClassTable->pkey].'&apply_to='.$apply_to.'" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile text-nowrap cursor-pointer"><i class="bx bx-user align-top"></i>'.count($share_ids).' nhóm</span>';
		}elseif($apply_to == "profile") {
			$html_share = '<span data-url="/index.php?mod=quote&act=load_list_share&table_id='.$val[$clsClassTable->pkey].'&apply_to='.$apply_to.'" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile text-nowrap cursor-pointer"><i class="bx bx-user align-top"></i>'.count($share_ids).' người</span>';
		}else {
			$html_share = '<span class=" text-nowrap" ><i class="bx bx-user align-top"></i> Tất cả</span>';
		}
		$lstItem[$key]["html_share"] = $html_share;
		if($clsISO->checkItemInArray($val[$clsClassTable->pkey],$arr_quote)){
			$lstItem[$key]["has_show"] = 1;	
			++$total_show;
		}else{
			$lstItem[$key]["has_show"] = 0;
		}
		
	}
	$order_no_arrs = @array_column($lstItem, 'has_show');
	@array_multisort($order_no_arrs, SORT_DESC, $lstItem);
//	$clsISO->print_pre($lstItem);die;
	#
	$assign_list["lstItem"] = $lstItem;
	$assign_list["total_show"] = $total_show;
	$assign_list['total_record'] = $total_record;
	$assign_list["html_pager"] = $html_pager;
	$assign_list['clsClassTable'] = $clsClassTable;
	
    /*=============Title & Description Page==================*/
	$title_page = 'Danh sách lời trích dẫn - '.PAGE_NAME;
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
	$clsClassTable = new Quote();
	
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
//		$clsISO->print_pre($oneItem);die;
//		$share_ids = $clsISO->getArrayByTextSlash($oneItem["share_ids"]);
//		$oneItem["share_ids"] = $share_ids;
		$assign_list["oneItem"] = $oneItem;
	}
	#
	$assign_list["uid"] = $uid;
	$assign_list["table_id"] = $table_id;
	
	#
	$html = $core->build("_ajax.open.tpl");
	
	echo json_encode(array(
		"uid"	=>	$uid,
		"html"	=>	$html,
	));die;
}
function default_loadObject() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	#
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsAutomation = new Automation();
	$apply_to = Input::post("apply_to",'all');
	$share_ids = Input::post("share_ids","");
	$arr_value = $clsISO->getArrayByTextSlash($share_ids);
	$clsGroupProfile = new GroupProfile();
	
	$html = "";
	if($apply_to == "department") {
		$html = '<div class="form-group mb-2">
					<label class="w-100 form-label mb-1">Chia sẻ</label>
					<select name="share_ids[]" class="form-select form-control multiselect required" data-header="true" data-filter="true" data-placeholder="Chọn phòng ban" data-field="share_ids[]" data-selected_text="phòng" data-nonSelectedText="phòng" data-allSelected_text="Tất cả phòng ban" multiple>';
		$html .= $clsProperty->getSelectByPropertyV2("_DEPARTMENT",$arr_value);		
		$html .='</select>
				</div>';
	}else if($apply_to == "group") {
		$lstGroup = $clsGroupProfile->getAll("`is_trash`='0' AND `is_online`='1'");
		$html = '<div class="form-group mb-2">
					<label class="w-100 form-label mb-1">Chia sẻ</label>
					<select name="share_ids[]" class="form-select form-control multiselect required" data-header="true" data-filter="true" data-placeholder="Chọn nhóm" data-field="share_ids[]" data-selected_text="nhóm" data-nonSelectedText="nhóm" data-allSelected_text="Tất cả nhóm" multiple>';
		foreach ($lstGroup as $key => $val) {
			$select = "";
			if($clsISO->checkItemInArray($val[$clsGroupProfile->pkey],$arr_value)) {
				$select = " selected ";
			}
			$html .= '<option value="'.$val[$clsGroupProfile->pkey].'" '.$select.'>'.$val["title"].'</option>';
		}		
		$html .='</select>
				</div>';
	}else if($apply_to == "profile") {
		$lstProfile = $clsProfile->getAll("`is_trash`='0' AND `is_active`='1' AND `status_id` <> '"._STATUS_STAFF_OFF_ID."'",$clsProfile->pkey.",full_name");
		$html = '<div class="form-group mb-2">
					<label class="w-100 form-label mb-1">Chia sẻ</label>
					<select name="share_ids[]" class="form-select form-control multiselect required" data-header="true" data-filter="true" data-placeholder="Chọn nhân viên" data-field="share_ids[]" data-selected_text="người" data-nonSelectedText="người" data-allSelected_text="Tất cả nhân viên" multiple>';
		foreach ($lstProfile as $key => $val) {
			$select = "";
			if($clsISO->checkItemInArray($val[$clsProfile->pkey],$arr_value)) {
				$select = " selected ";
			}
			$html .= '<option value="'.$val[$clsProfile->pkey].'" '.$select.'>'.$val["full_name"].'</option>';
		}		
		$html .='</select>
				</div>';
	}	
	echo json_encode(array(
		"html"	=>	$html,
	));die;
}
function default_ajSave() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	#
	$clsClassTable = new Quote();
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);
	$author = Input::post("author", "");
	$content = Input::post("content", "");
	$apply_to = Input::post("apply_to", "all");
	$share_ids = Input::post("share_ids", array());
	
	$res = ["result"	=> false,"msg"=>"Lỗi"];
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
		$data_upd = [
			"author"	=>	$author,
			"content"	=>	addslashes($content),
			"apply_to"		=>	$apply_to,
			"share_ids"	=> $clsISO->makeSlashListFromArrayRoot($share_ids),	
			"upd_date"	=>	time(),		
		];
		if($clsClassTable->updateOne($table_id,$data_upd)) {
			$res = ["result"	=> true,"msg"=>"Cập nhật thành công"];
		}
	}else{
		$data_upd = [
			$clsClassTable->pkey	=>	$clsClassTable->getMaxID(),
			"author"	=>	$author,
			"content"	=>	addslashes($content),
			"apply_to"		=>	$apply_to,
			"share_ids"	=> $clsISO->makeSlashListFromArrayRoot($share_ids),
			"user_id"	=>	$profile_id,				
			"reg_date"	=>	time(),		
			"upd_date"	=>	time(),		
		];
		if($clsClassTable->insert($data_upd)) {
			$res = ["result"	=> true,"msg"=>"Thêm thành công"];
		}
	}
	
	echo json_encode($res);die;
}
function default_ajDelete() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	#
	$clsClassTable = new Quote();
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);	
	$res = ["result"	=> false,"msg"=>"Lỗi"];
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
		if(!empty($oneItem) && $clsClassTable->deleteOne($table_id)) {
			$res = ["result"	=> true,"msg"=>"Xóa thành công"];
		}
	}	
	echo json_encode($res);die;
}

function default_load_list_share(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsClassTable = new Quote();
	$clsGroupProfile = new GroupProfile(); 
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$field = "{$clsProfile->pkey},`more_information`";
	$table_id = (int)Input::get("table_id",0);
	$oneItem = $clsClassTable->getOne($table_id,"{$clsClassTable->pkey},apply_to,share_ids");
	$apply_to = !empty($oneItem["apply_to"]) ? $oneItem["apply_to"] : "all";
	$share_ids = $clsISO->getArrayByTextSlash($oneItem["share_ids"]);
	$html = "";
	if(!empty($share_ids)) {
		if($apply_to == "department"){
			$lstItem = $clsProperty->getAll("`property_id` IN (".implode(',',$share_ids).")");
			if(!empty($lstItem)) {	
				$html = "<ul class='list-unstyled p-2 mb-0 dropdown-scrollable'>";
				foreach($lstItem as $key => $val){
					$html .= "<li>".$val['title']."</li>";
				}
				$html .= "</ul>";
			}
		}elseif($apply_to == "group"){
			$lstItem = $clsGroupProfile->getAll("`group_profile_id` IN (".implode(',',$share_ids).")");
			if(!empty($lstItem)) {	
				$html = "<ul class='list-unstyled p-2 mb-0 dropdown-scrollable'>";
				foreach($lstItem as $key => $val){
					$html .= "<li>".$val['title']."</li>";
				}
				$html .= "</ul>";
			}
		}elseif($apply_to == "profile"){
			$lstItem = $clsProfile->getAll("`profile_id` IN (".implode(',',$share_ids).")");
			if(!empty($lstItem)) {	
				$html = "<ul class='list-unstyled p-2 mb-0 dropdown-scrollable'>";
				foreach($lstItem as $key => $val){
					$html .= "<li>".$clsProfile->getIndentityV5($val['profile_id'], $val)."</li>";
				}
				$html .= "</ul>";
			}
		}
	}
	echo $html;die;
}