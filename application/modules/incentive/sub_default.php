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
	$clsClassTable = new Incentive();
	$pkeyTable = $clsClassTable->pkey;
	#
	$cond = "1=1";	
	#
	$current_page = Input::get('page',1);
	$per_page  = 20;
	$total_record = $clsClassTable->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	$order_by = " ORDER BY upd_date DESC ";
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> str_replace(".html","/",$clsISO->getLink("incentive"))
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(1,$str_url);
	$lstItem = $clsClassTable->getAll($cond.$order_by.$limitCond);
	foreach ($lstItem as $key => $val) {
		
	}
//	$clsISO->print_pre($lstItem);die;
	#
	$assign_list["lstItem"] = $lstItem;
	$assign_list['total_record'] = $total_record;
	$assign_list["html_pager"] = $html_pager;
	$assign_list['clsClassTable'] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	
    /*=============Title & Description Page==================*/
	$title_page = 'Quản lý chương trình thi đua - '.PAGE_NAME;
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
	$clsClassTable = new Incentive();
	$pkeyTable = $clsClassTable->pkey;
	
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
		$assign_list["oneItem"] = $oneItem;
	}
	#
	$assign_list["uid"] = $uid;
	$assign_list["pkeyTable"] = $pkeyTable;
	$assign_list["table_id"] = $table_id;	
	#
	$html = $core->build("_ajax.open.tpl");
	
	echo json_encode(array(
		"uid"	=>	$uid,
		"html"	=>	$html,
	));die;
}
function default_ajSave() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	#
	$clsClassTable = new Incentive();
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);
	$title = Input::post("title", "");
	$image = Input::post("image", "");
	$start_time = Input::post("start_time", date("Y-m-d 00:00"));
	$end_time = Input::post("end_time", date("Y-m-d 23:59"));
	$is_active = Input::post("is_active", 0);
	$res = ["result"	=> false,"msg"=>"Lỗi"];
	$start_time = strtotime($start_time);
	$end_time = strtotime($end_time);
	$data_upd = [
		"title"				=>	$title,
		"image"				=>	$image,
		"start_time"		=>	$start_time,
		"end_time"			=>	$end_time,
		"is_active"			=>	$is_active,
		"user_id_update"	=>	$profile_id,		
		"upd_date"			=>	time(),				
	];
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);	
		if($clsClassTable->updateOne($table_id,$data_upd)) {
			$res = ["result"	=> true,"msg"=>"Cập nhật thành công"];
		}
	}else{	
		$data_upd[$clsClassTable->pkey] = $clsClassTable->getMaxID();
		$data_upd["user_id"] = $profile_id;
		$data_upd["reg_date"] = time();
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
	$clsClassTable = new News();
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
function default_set_status() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	#
	$clsClassTable = new Incentive();
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);	
	$is_active = (int) Input::post("is_active", 0);	
	$res = ["result"	=> false,"msg"=>"Lỗi"];
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
		if(!empty($oneItem) && $clsClassTable->updateOne($table_id,["is_active"=>$is_active])) {
			$res = ["result"	=> true,"msg"=>"Thành công"];
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
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	
	$res = ["result"=>false,"msg"=>"Lỗi"];
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$image = $_FILES['image'];
		if(!empty($image['name'])){ 
			$error = 0;
			if($image['size'] > 4194304){
				$res = ["result"=>false,"msg"=>"Kích thước file quá lớn"];
				$error = 1;
			}
			if(empty($error)){
				$results = array();
				$clsUploadFile = new UploadFile();
				$up = $clsUploadFile->uploadItem($image,"/incentive","jpg,jpeg,gif,png", array(
					'resize' => false,
					'resize_x' => 847,
					'resize_y' => 510,
					'watermark' => true,
				));
//				$up = "/images/thong-bao/fh_1760329296_capital-square-danang-5.jpg";
				// Return
				$html = '';
				if(!empty($up) && @file_exists(ABSPATH . $up)){
					$res = [
						"result"	=>	true,
						"image"		=>	$up
					];
				}
			}
		}
	}
	// Return
	echo json_encode($res, JSON_UNESCAPED_UNICODE); die();
}