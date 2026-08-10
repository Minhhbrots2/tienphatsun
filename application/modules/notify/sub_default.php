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
	$clsClassTable = new News();
	$pkeyTable = $clsClassTable->pkey;
	#
	$cond = "`post_type`='notification'";
	
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
		'link'	=> str_replace(".html","/",$clsISO->getLink("notify"))
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
	$title_page = 'Quản lý thông báo nội bộ - '.PAGE_NAME;
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
	$clsClassTable = new News();
	$pkeyTable = $clsClassTable->pkey;
	
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);
	$time_after_send = 30;
	
	$end_date = date("H:i",strtotime("+30 minutes"));
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
		$more_information = $clsISO->to_array_json($oneItem["more_information"]);
		$send_notify = !empty($more_information["send_notify"]) ? $more_information["send_notify"] : "";
		$time_after_send = !empty($more_information["time_after_send"]) ? $more_information["time_after_send"] : 30;
		$oneItem["send_notify"] = $send_notify;
		$time_after_send = $time_after_send;
		$images = $clsISO->to_array_json($oneItem["images"]);
		$oneItem["image"] = !empty($images) ? $images[0] : "";
//		$clsISO->print_pre($oneItem);die;
//		$share_ids = $clsISO->getArrayByTextSlash($oneItem["share_ids"]);
//		$oneItem["share_ids"] = $share_ids;
		$end_date = date("H:i",$oneItem["end_date"]);
		$assign_list["oneItem"] = $oneItem;
	}
	#
	$assign_list["uid"] = $uid;
	$assign_list["end_date"] = $end_date;
	$assign_list["table_id"] = $table_id;
	$assign_list["time_after_send"] = $time_after_send;
	$assign_list["pkeyTable"] = $pkeyTable;
	
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
	$clsClassTable = new News();
	#
	$uid = $clsISO->getUniqid();
	$table_id = (int) Input::post("table_id", 0);
	$title = Input::post("title", "");
	$content = $_POST["content"];
	$image = Input::post("image", "");
	$send_notify = Input::post("send_notify", "");
//	$time_after_send = Input::post("time_after_send", 30);
	$is_trash = Input::post("is_trash", 1);
	$time_end = Input::post("time_end", date("H:i"));
	$res = ["result"	=> false,"msg"=>"Lỗi"];
	$start_date = time();
	$end_date = strtotime("+ {$time_after_send} minutes");
	$end_date = strtotime(date(sprintf("d-m-Y %s",$time_end)));
	if(!empty($table_id)) {
		$oneItem = $clsClassTable->getOne($table_id);
		$more_information = $clsISO->to_array_json($oneItem["more_information"]);
		$data_upd = [
			"title"				=>	$title,
			"content"			=>	$content,
			"images"			=>	json_encode([$image]),
			"user_id_update"	=>	$profile_id,		
			"upd_date"			=>	time(),				
		];		
		if($oneItem["is_online"] == 1) {
			$data_upd["is_trash"] = $is_trash;
			$data_upd["start_date"] = $start_date;
			$data_upd["end_date"] = $end_date;			
			$more_information["send_notify"] = $send_notify;
//			$more_information["time_after_send"] = $time_after_send;
			$data_upd["more_information"] = json_encode($more_information,JSON_UNESCAPED_UNICODE);
		}
		if($clsClassTable->updateOne($table_id,$data_upd)) {
			$res = ["result"	=> true,"msg"=>"Cập nhật thành công"];
		}
	}else{		
		$more_information = [
			"send_notify" => $send_notify,
//			"time_after_send" => $time_after_send,
		];
		$data_upd = [
			$clsClassTable->pkey=>	$clsClassTable->getMaxID(),
			"post_type"			=>	'notification',
			"title"				=>	$title,
			"content"			=>	$content,
			"images"				=>	json_encode([$image]),
			"more_information"	=>	json_encode($more_information,JSON_UNESCAPED_UNICODE),	
			"is_online"			=>	1,	
			"is_trash"			=>	$is_trash,	
			"user_id"			=>	$profile_id,				
			"reg_date"			=>	time(),		
			"upd_date"			=>	time(),			
			"start_date"		=>	$start_date,			
			"end_date"			=>	$end_date,		
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
				$up = $clsUploadFile->uploadItem($image,"/thong-bao","jpg,jpeg,gif,png", array(
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