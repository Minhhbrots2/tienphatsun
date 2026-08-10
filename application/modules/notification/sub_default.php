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
	$clsClassTable = new Notification();
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
		'link'	=> str_replace(".html","/",$clsISO->getLink("notification"))
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
	$title_page = 'Thông báo - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsClassTable = new Notification();
	$pkeyTable = $clsClassTable->pkey;
	#
	$uid = $clsISO->getUniqid();
	$notification_id = (int) Input::post("notification_id", 0);
	#
	$action = "_add"; $oneItem = array();
	if($notification_id > 0) {
		$action = "_edit";
		$oneItem = $clsClassTable->getOne($notification_id);
	}
	#
	$assign_list["uid"] = $uid;
	$assign_list["action"] = $action;
	$assign_list["notification_id"] = $notification_id;
	$assign_list["oneItem"] = $oneItem;
	#
	$html = $core->build("_ajax.open.tpl");
	echo json_encode(array(
		"uid"	=>	$uid,
		"html"	=>	$html,
	));die;
}
function default_save() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsClassTable = new Notification();
	#
	$notification_id = (int) Input::post("notification_id", 0);
	$title = Input::post("title", "");
	$content = $_POST["content"];
	$link = Input::post("link", "");
	#
	$res = ["result" => false, "msg" => "Lỗi"];
	if($notification_id > 0) {
		$oneItem = $clsClassTable->getOne($notification_id);
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$data_upd = [
			"title"				=>	$title,
			"content"			=>	$content,
			"link"				=>	$link,
			"user_id_update"	=>	$profile_id,		
			"upd_date"			=>	time(),				
		];	
		if($clsClassTable->updateOne($notification_id,$data_upd)) {
			$res = ["result" => true, "msg" => "Cập nhật thành công"];
		}
	}else{	
		$notification_id = $clsClassTable->getMaxID();
		$data_ins = [
			$clsClassTable->pkey=>	$notification_id,
			"title"				=>	$title,
			"content"			=>	$content,
			"link"				=>	$link,
			"user_id"			=>	$profile_id,				
			"reg_date"			=>	time(),		
			"upd_date"			=>	time()	
		];
		if($clsClassTable->insert($data_ins)) {
			$res = ["result" => true, "msg" => "Thêm thành công"];
		}
	}
	// Return
	echo json_encode($res);die;
}
function default_delete() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsClassTable = new Notification();
	#
	$notification_id = (int) Input::post("notification_id", 0);	
	$res = ["result" => false, "msg" => "Lỗi"];
	if ($notification_id > 0){
		if($clsClassTable->deleteOne($notification_id)) {
			$res = [
				"result" => true, 
				"msg" => "Xóa thành công"
			];
		}
	}
	// Return
	echo json_encode($res);die;
}
function default_send() {
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsClassTable = new Notification();
	$clsAccessToken = new AccessToken();
	#
	$msg = "_error";
	$notification_id = (int) Input::post("notification_id", 0);	
	if ($notification_id > 0){
		$oneItem = $clsClassTable->getOne($notification_id);
		$arr_tokens = $clsAccessToken->getAll("`firebase_token`<>'' AND `expires`>='".time()."' GROUP BY `firebase_token`", "firebase_token"); 
		if(!empty($arr_tokens) && !empty($oneItem)){
			$msg = "_success";
			$clsClassTable->updateOne($notification_id, array(
				'is_send' => 1,
				'send_date' => time()
			));
			foreach($arr_tokens as $key => $val){
				$firebase_token = $val['firebase_token'];
				$clsClassTable->doPushMessaging($firebase_token, array(
					'title' => $oneItem['title'],
					'body' => $oneItem['content'],
					'link' => $oneItem['link']
				));
			}
			unset($arr_tokens);
		}
	}
	// Return
	echo json_encode($res);die;
}
