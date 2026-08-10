<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsDocs = new Docs();
	$clsProfile = new Profile();
	$lstCategory_doc = $clsProperty->getCacheItems("_DOCUMENT");
	$list_staffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $clsProfile->pkey.",full_name");
	foreach ($lstCategory_doc as $key => $val) {
		$total = $clsDocs->countItem("cat_id = '{$val['property_id']}'");
		$lstCategory_doc[$key]['total_doc'] = $total;
	}
	$_ss_view_docs = 'grid';
	if(vnSessionExist('_ss_view_docs')){
		$_ss_view_docs = vnSessionGetVar('_ss_view_docs');
	}
//	$clsISO->print_pre($lstCategory_doc);die;
	
	$assign_list['_ss_view_docs'] = $_ss_view_docs;
	$assign_list['lstCategory_doc'] = $lstCategory_doc;
	$assign_list['list_staffs'] = $list_staffs;
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsDocs'] = $clsDocs;
//	$clsGoogleDrive = new GoogleDrive();
//	$gg_doc_id = $clsGoogleDrive->getIdGoogleDoc("1b4HizG5lBl7Emg20Q4xaIztiFIVI8-Gk");
//	echo $gg_doc_id;die;
	/*=============Title & Description Page==================*/
	$title_page = 'Văn bản hệ thống - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_set_view(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	###
	$view = Input::post('view', 'grid');
	vnSessionSetVar('_ss_view_docs', $view);
	// Return
	echo 1; die();
}
function default_pin_doc(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	###
	$doc_id = (int)Input::post('doc_id', 0);
	$status = (int)Input::post('status', 0);
	$more_information = $oneProfile['more_information'];
	$res = [
		"result"	=>	false
	];
	if(!empty($doc_id)) {
		$pinned_document = !empty($more_information['pinned_document']) ? $more_information['pinned_document'] : array();
		if($status == 0) {
			if(($key = array_search($doc_id,$pinned_document)) !== false) {
				unset($pinned_document[$key]);			
				$more_information['pinned_document'] = array_values($pinned_document);
				if($clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information)])) {
					$res = [
						"result"	=>	true,
						"text" 		=> "Ghim"
					];
				}				
			}
		}else{
			if(!$clsISO->checkItemInArray($doc_id,$pinned_document)) {
				$pinned_document[] = $doc_id;
				$more_information['pinned_document'] = array_values($pinned_document);
				if($clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information)])) {
					$res = [
						"result"	=>	true,
						"text" 		=> "Bỏ ghim"
					];
				}
				
			}
		}
	}
	$oneProfile['more_information'] = $more_information;
	
	echo json_encode($res);die;
}
function default_folder(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;
	$clsProperty = new Property();
	$clsDocs = new Docs();
	$clsProfile = new Profile();
	$clsFolder = new Folder();
	#
	$list_department_id = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	$cond = "(`user_id`='{$profile_id}' OR `type`='0'";
	if(!empty($list_department_id)) {
		$cond .= " OR (`type`='1' AND (";
		foreach($list_department_id as $k => $department_id) {
			$cond .= (($k == 0) ? "" : " OR ") . "`department_ids` LIKE '%|{$department_id}|%'";
		}
		$cond .="))";
	}
	$cond .=")";
	###
	$lstFolder = $clsFolder->getAll($cond);
	$list_staffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $clsProfile->pkey.",full_name");	
	$assign_list['lstFolder'] = $lstFolder;
	$assign_list['list_staffs'] = $list_staffs;
	#
	$show = Input::get("show","cat");
	$cat_id = (int)Input::get("cat_id",0);
	$authorized_person = (int)Input::get("p",0);
	$effective_date = Input::get("d","");
	$keyword = Input::get("k","");
	if($show=="cat") {
		$slug = Input::get("slug","");
		$oneCat = $clsFolder->getByCond($cond." AND `slug`='{$slug}' AND `folder_id`='{$cat_id}'");
		if(empty($oneCat)) {
			header("Location: ".$clsISO->getLink("document"));
			exit();
		}
	}
//	$clsISO->print_pre(vnSessionGetVar('_ss_view_docs'));die;
	$_ss_view_docs = 'grid';
	if(vnSessionExist('_ss_view_docs')){
		$_ss_view_docs = vnSessionGetVar('_ss_view_docs');
	}
	
	$assign_list['_ss_view_docs'] = $_ss_view_docs;
	$assign_list['show'] = $show;
	$assign_list['oneCat'] = $oneCat;
	$assign_list['cat_id'] = $cat_id;
	$assign_list['authorized_person'] = $authorized_person;
	$assign_list['effective_date'] = $effective_date;
	$assign_list['keyword'] = $keyword;
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsDocs'] = $clsDocs;
	$assign_list['lstDocs'] = $lstDocs;
	
	#=============detail doc===========================
	$string_doc = Input::get("doc","");
	$assign_list['string_doc'] = $string_doc;
	$scriptJs = "";
	if($string_doc) {
		$doc_id_view = !empty($string_doc) ? $core->decryptID($string_doc) : 0;
		$oneDoc = $clsDocs->getOne($doc_id_view);
		$scriptJs= '<a class="autoclick_'.$doc_id_view.'" doc_id="'.$doc_id_view.'" onclick="$Core.docs.view_doc_detail(this,event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$doc_id_view.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}	
	$assign_list['scriptJs'] = $scriptJs;
	#==================================================
	/*=============Title & Description Page==================*/
	if($show=="cat") {
		$title_page = $oneCat["title"].' - Văn bản hệ thống - '.PAGE_NAME;
	}else{
		$title_page = 'Tìm kiếm - Văn bản hệ thống - '.PAGE_NAME;
	}
	
	$assign_list["title_page"] = $title_page;
}
function default_open_doc(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsProperty = new Property();
	$clsDocs = new Docs();
	$clsProfile = new Profile();
	$clsFolder = new Folder();
	##
	$uid = $clsISO->getUniqid();
	$cat_id = (int)Input::post('cat_id',0);
	$doc_id = (int)Input::post('doc_id',0);
	##
	$list_department_id = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	$cond = "(`user_id`='{$profile_id}' OR `type`='0'";
	if(!empty($list_department_id)) {
		$cond .= " OR (`type`='1' AND (";
		foreach($list_department_id as $k => $department_id) {
			$cond .= (($k == 0) ? "" : " OR ") . "`department_ids` LIKE '%|{$department_id}|%'";
		}
		$cond .="))";
	}
	$cond .=")";
	$lstCategory_doc = $clsFolder->getAll($cond);
	
	$titlePage = 'Thêm tài liệu';
	$oneItem = $more_information = array();
	if(!empty($doc_id)){
		$titlePage = 'Chỉnh sửa tài liệu';
		$oneItem = $clsDocs->getOne($doc_id);
		$cat_id = !empty($oneItem['cat_id']) ? $oneItem['cat_id'] : $cat_id;
		$list_department_id = !empty($oneItem['list_department_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_department_id']) : array();
		$lstDepartment = array();
		if(!empty($list_department_id)) {
			$lstDepartment = $clsProperty->getAllCache("`property_id` IN (".implode(",",$list_department_id).")", $clsProperty->pkey.',title');
		}
		$oneItem['department'] = $list_department_id;
		$oneItem['list_tags'] = trim($oneItem['list_tags'],"|");
		
		$list_role_id = !empty($oneItem['list_role_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_role_id']) : array();
		$oneItem['list_role_id'] = $list_role_id;
		if(!empty($list_department_id)) {
			/*$lst_role = $clsProperty->getAll("`property_type`='_ROLE' AND `parent_id` IN (SELECT `for_id` FROM `default_property` WHERE `property_id` IN (".implode(',',$list_department_id)."))");
			$smarty->assign('lst_role', $lst_role);*/
			$lst_for_id = $clsProperty->getAll("`property_id` IN (".implode(',',$list_department_id).")");
			$html_role = '';
			if(!empty($lst_for_id)) {
				foreach($lst_for_id as $k => $_oRole) {
					$for_id = $_oRole["for_id"];
					$arrOption = array();
					$clsProperty->getmakeOption($for_id, '_ROLE', 0, 0, $arrOption);
					if(!empty($arrOption)){
						$html_role .= '<optgroup class="select2-result-selectable" label="'.$_oRole["title"].'">';
						foreach($arrOption as $key => $val){
							$selected = "";			
							if($clsISO->checkItemInArray($_oRole["title"]."-".$key,$list_role_id)) {
								$selected = "selected";
							}
							$html_role .= sprintf('<option value="%s" %s>%s</option>', $_oRole["title"]."-".$key,$selected, "[".$_oRole["title"]."]".$val);
						}
						$html_role .= '</optgroup>';
					}
				}			
			}
			$smarty->assign('html_role', $html_role);
		}
		$more_information = $clsISO->to_array_json($oneItem['more_information']);
		$oneItem['file_doc'] = !empty($more_information['file_doc']) ? $more_information['file_doc'] : array();
	}
	$list_staffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $clsProfile->pkey.",full_name");
	$smarty->assign('list_staffs', $list_staffs);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('media_id', $media_id);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('lstCategory_doc', $lstCategory_doc);
	// Return
	$html = $core->build('_ajax.open_document.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_option_role(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$department_id = Input::post("department_id",array());
	$role_ids = Input::post("role_ids",array());
	#
	$html = '';
	if(!empty($department_id)){
		$lst_for_id = $clsProperty->getAll("`property_id` IN (".implode(',',$department_id).")");
//		$clsISO->print_pre($lst_for_id);die;
		if(!empty($lst_for_id)) {
			foreach($lst_for_id as $k => $_oRole) {
				$for_id = $_oRole["for_id"];
				$arrOption = array();
				$clsProperty->getmakeOption($for_id, '_ROLE', 0, 0, $arrOption);
				if(!empty($arrOption)){
					$html .= '<optgroup class="select2-result-selectable" label="'.$_oRole["title"].'">';
					foreach($arrOption as $key => $val){
						$selected = "";
						if($clsISO->checkItemInArray($val['property_id'],$role_ids)) {
							$selected = "selected";
						}
						$html .= sprintf('<option value="%s" %s>%s</option>', $_oRole["title"]."-".$key,$selected, "[".$_oRole["title"]."]".$val);
					}
					$html .= '</optgroup>';
				}
			}			
		}	
	}
	// Return
	echo json_encode(array("html" => $html)); die();	
}
function default_search_doc(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsDocs = new Docs();
	$clsFolder = new Folder();
	$keyword = Input::post("keyword","");
	$cat_id = Input::post("cat_id",0);
	$authorized_person = Input::post("authorized_person",0);
	$effective_date = Input::post("effective_date","");
	#
	$url = "/van-ban-he-thong/search";
	if(!empty($cat_id)){
		$url = $clsFolder->getLink($cat_id);
	}
	$first = 0;
	if(!empty($keyword)) {
		$url .= (($first == 0) ? "?" : "&") . "&k=".str_replace(" ","+",$keyword);
		$first = 1;
	}
	if(!empty($authorized_person)) {
		$url .= (($first == 0) ? "?" : "&") . "p=".$authorized_person;
		$first = 1;
	}
	if(!empty($effective_date)) {
		$url .= (($first == 0) ? "?" : "&") . "d=".$effective_date;
		$first = 1;
	}
	// Return
	echo json_encode(array("url" => $url)); die();	
}

function default_save_docs(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsDocs = new Docs();
	$clsFolder = new Folder();
	$clsProfile = new Profile();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	###
	$doc_id = (int) Input::post('doc_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	$title = Input::post('title', "");
	$department_id = Input::post('department_id', array());
	$list_department_id = $clsISO->makeSlashListFromArrayRoot($department_id);
	$role_ids = Input::post('role_ids', array());
	$list_role_id = $clsISO->makeSlashListFromArrayRoot($role_ids);
	$content = trim(Input::post("content",""));
	$document_number = Input::post("document_number","");
	$effective_date = Input::post("effective_date","");
	$authorized_person = (int)Input::post("authorized_person",0);
	$is_important = (int)Input::post("is_important",0);
	$is_top = (int)Input::post("is_top",0);
	$tags = Input::post("tags","");
	$tags = !empty($tags) ? $clsISO->makeSlashListFromArray(explode("|",$tags)) : "";
	$more = array(); $msg = "_error";
	#
	$assign_to_id = $clsDocs->getUserFollowDoc($department_id,$role_ids,1);
//	$clsISO->print_pre($assign_to_id);die;
//	$assign_to_id = [0];
		
	if($doc_id > 0){
		$oneDoc = $clsDocs->getOne($doc_id);
		$more_information = $oneDoc['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		#logs
		$uid = $clsISO->getUniqid();
		$logs = !empty($more_information['logs']) ? $more_information['logs'] : array();
		$data_log = array();
		if($oneDoc['title'] != trim($title)){
			$data_log[] = array(
				'from_value' => $oneDoc['title'],
				'to_value' => $title,
				'field' => 'title'
			);
		}
		if($more_information['document_number'] != $document_number){
			$data_log[] = array(
				'from_value' => $more_information['document_number'],
				'to_value' => $document_number,
				'field' => 'document_number'
			);
		}
		if($oneDoc['content'] != $content){
			$data_log[] = array(
				'from_value' => $oneDoc['content'],
				'to_value' => $content,
				'field' => 'content'
			);
		}
		if($oneDoc['cat_id'] != $cat_id){
			$data_log[] = array(
				'from_value' => $oneDoc['cat_id'],
				'to_value' => $cat_id,
				'field' => 'cat_id'
			);
		}
		if($oneDoc['list_department_id'] != $list_department_id){
			$data_log[] = array(
				'from_value' => $oneDoc['list_department_id'],
				'to_value' => $list_department_id,
				'field' => 'list_department_id'
			);
		}
		if($oneDoc['list_role_id'] != $list_role_id){
			$data_log[] = array(
				'from_value' => $oneDoc['list_role_id'],
				'to_value' => $list_role_id,
				'field' => 'list_role_id'
			);
		}
		if($oneDoc['list_tags'] != $tags){
			$data_log[] = array(
				'from_value' => $oneDoc['list_tags'],
				'to_value' => $tags,
				'field' => 'list_tags'
			);
		}
		if($more_information['effective_date'] != $effective_date){
			$data_log[] = array(
				'from_value' => $more_information['effective_date'],
				'to_value' => $effective_date,
				'field' => 'effective_date'
			);
		}
		if($more_information['authorized_person'] != $authorized_person){
			$data_log[] = array(
				'from_value' => $more_information['authorized_person'],
				'to_value' => $authorized_person,
				'field' => 'authorized_person'
			);
		}
		if(empty($_FILES['files']['name']) && !empty($data_log)){
			$logs[$uid]['logs'] = $data_log; 
			$logs[$uid] = array_merge($logs[$uid], array(
				'reg_date' => time(),
				'user_id' => $profile_id,
				'action' => "update"
			));
			$more_information["logs"] = $logs;
		}
		#end logs
		
		$more_information["document_number"] = $document_number;
		$more_information["effective_date"] = $effective_date;
		$more_information["authorized_person"] = $authorized_person;
		$more = array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		);
//		$clsDocs->setDeBug(1);
		if($clsDocs->updateOne($doc_id, array_merge($more, array(
			'cat_id' => $cat_id,
			'title' => trim($title),
			'content' => $content,
			'list_department_id' => $list_department_id,
			'list_role_id' => $list_role_id,
			'is_important' => $is_important,
			'is_top' => $is_top,
			'list_tags' => $tags,
			'effective_date' => !empty($authorized_person) ? strtotime($authorized_person) : 0,
			'upd_date' => time(),
			'user_id_update' => $profile_id,
		)))){
			$msg = '_success';
			if(!empty($_FILES['files']['name'])){				
				$file_doc = $more_information['file_doc'];
				$clsUploadFile = new UploadFile();
				$clsGoogleDrive = new GoogleDrive();
				for($i=0;$i<count($_FILES['files']['name']);$i++){
					$file = array();
					$file["name"] = $_FILES['files']['name'][$i];
					$file["type"] = $_FILES['files']['type'][$i];
					$file["tmp_name"] = $_FILES['files']['tmp_name'][$i];
					$file["error"] = $_FILES['files']['error'][$i];
					$file["size"] = $_FILES['files']['size'][$i];
					if(is_uploaded_file($_FILES['files']['tmp_name'][$i])){
						$image = $clsUploadFile->uploadItem($file,"/DOCS",EXTENSION_FILE_UPLOAD);
						$title_file = $file["name"];
						$mimeType = $file["type"];
						if(empty($list_department_id) && empty($role_ids)) {
							if(!empty($image) && file_exists(ROOTPATH . $image)){
								// Set the file metadata for drive
								$createdFile = $clsGoogleDrive->upload($title_file, $mimeType, ROOTPATH.$image,GOOGLE_DRIVE_FOLDER_DOCS_ID);
								$file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
								$file_doc[] = array('name' => $title_file, 'mimeType' => $mimeType, 'url' => $file);
								@unlink(ROOTPATH . $image);
								$type = "drive";
							}
						}else{
							$file_doc[] = array(
								'name' => $title_file,
								'mimeType' => $mimeType,
								'url' => $image
							);
							$type = "file";
						}
					}
					$more_information["type"] = $type;
				}
				#
				$mimeType = "";
				foreach($file_doc as $k => $v) {
					if($mimeType == "" && $v['mimeType'] != ""){
						$mimeType = $v['mimeType'];
						break;
					}			
				}
				$more_information["mimeType"] = $mimeType;
				#log file
				if($more_information['file_doc'] != $file_doc){
					$data_log[] = array(
						'from_value' => $more_information['file_doc'],
						'to_value' => $file_doc,
						'field' => 'file_doc',
					);
				}
				if(!empty($data_log)) {					
					$logs[$uid]['reg_date'] = time(); 
					$logs[$uid]['user_id'] = $profile_id; 
					$logs[$uid]['action'] = "update"; 
					$logs[$uid]['logs'] = $data_log; 
					$more_information["logs"] = $logs;
				}
				$more_information["file_doc"] = $file_doc;
				$clsDocs->updateOne($doc_id, array(
					"more_information" => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				));
			}	
			
			#notify
			/** Gửi thông báo tới người theo dõi */
			$clsDocs->sendEmail($doc_id,$assign_to_id,"update");
			$titleNoty = sprintf('<strong>%s</strong> sửa đổi văn bản tài liệu [<strong>%s</strong>]', $clsProfile->getFullName($profile_id, $oneProfile), $title);
			$clsNotify->insertNotify('Docs', $clsDocs->pkey, $doc_id, $titleNoty, time(), $assign_to_id);
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id` IN (".implode(',',$assign_to_id).") and `token`<>''", "token");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => "Thông báo văn bản tài liệu",
					'message' => strip_tags($titleNoty),
					'url' => $clsFolder->getLink($cat_id)."?doc=".$core->encryptId($doc_id)
				), $subscribers);
			}
			/** =============================== */
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Thông báo văn bản tài liệu",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . $clsFolder->getLink($cat_id)."?doc=".$core->encryptId($doc_id)
			];
			$clsNotification->doPushMessagingUser($params,$assign_to_id);
			
			#activity log			
			/*$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Docs","update",$_POST);*/
		}
	} else {
		$more_information = array();		
		$more_information["document_number"] = $document_number;
		$more_information["effective_date"] = $effective_date;
		$more_information["authorized_person"] = $authorized_person;
		
		#log
		$logs[$clsISO->getUniqid()] = [
			"reg_date"	=> time(),
			"user_id" 	=> $profile_id,
			"action" 	=> "insert"
		];		
		$more_information["logs"] = $logs;
		
		$more = array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		);
		$doc_id = $clsDocs->getMaxId();
		if($clsDocs->insert(array_merge($more, array(
			$clsDocs->pkey => $doc_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'cat_id' => $cat_id,
			'title' => trim($title),
			'content' => $content,
			'list_department_id' => $list_department_id,
			'list_role_id' => $list_role_id,
			'is_important' => $is_important,
			'is_top' => $is_top,
			'list_tags' => $tags,
			'effective_date' => !empty($authorized_person) ? strtotime($authorized_person) : 0,
			'reg_date' => time(),			
			'upd_date' => time(),		
		)))){
			$msg = '_success';	
			if(!empty($_FILES['files']['name'])){				
				$file_doc = array();
				$clsUploadFile = new UploadFile();
				$clsGoogleDrive = new GoogleDrive();
				for($i=0;$i<count($_FILES['files']['name']);$i++){
					$file = array();
					$file["name"] = $_FILES['files']['name'][$i];
					$file["type"] = $_FILES['files']['type'][$i];
					$file["tmp_name"] = $_FILES['files']['tmp_name'][$i];
					$file["error"] = $_FILES['files']['error'][$i];
					$file["size"] = $_FILES['files']['size'][$i];
					if(is_uploaded_file($_FILES['files']['tmp_name'][$i])){
						$image = $clsUploadFile->uploadItem($file,"/DOCS",EXTENSION_FILE_UPLOAD);
						$title = $file["name"];
						$mimeType = $file["type"];
						if(empty($list_department_id) && empty($role_ids)) {
							if(!empty($image) && file_exists(ROOTPATH . $image)){
								// Set the file metadata for drive
								$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image,GOOGLE_DRIVE_FOLDER_DOCS_ID);
								$file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
								$file_doc[] = array('name' => $title, 'mimeType' => $mimeType, 'url' => $file);
								@unlink(ROOTPATH . $image);
								$type = "drive";
							}
						}else{
							$file_doc[] = array(
								'name' => $title,
								'mimeType' => $mimeType,
								'url' => $image
							);
							$type = "file";
						}
					}
					$more_information["type"] = $type;
				}
				$more_information["file_doc"] = $file_doc;				
				
				#
				$mimeType = "";
				foreach($file_doc as $k => $v) {
					if($mimeType == "" && $v['mimeType'] != ""){
						$mimeType = $v['mimeType'];
						break;
					}			
				}
				$more_information["mimeType"] = $mimeType;
				
				$clsDocs->updateOne($doc_id, array(
					"more_information" => json_encode($more_information,JSON_UNESCAPED_UNICODE)
				));
			}	
			
			#notify
			/** Gửi thông báo tới người theo dõi */
			$clsDocs->sendEmail($doc_id,$assign_to_id,"insert");
			$titleNoty = sprintf('<strong>%s</strong> thêm văn bản tài liệu [<strong>%s</strong>]', $clsProfile->getFullName($profile_id, $oneProfile), $title);
			$clsNotify->insertNotify('Docs', $clsDocs->pkey, $doc_id, $titleNoty, time(), $assign_to_id);
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id` IN (".implode(',',$assign_to_id).") and `token`<>''", "token");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => "Thông báo văn bản tài liệu",
					'message' => strip_tags($titleNoty),
					'url' => $clsFolder->getLink($cat_id)."?doc=".$core->encryptId($doc_id)
				), $subscribers);
			}
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Thông báo văn bản tài liệu",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . $clsFolder->getLink($cat_id)."?doc=".$core->encryptId($doc_id)
			];
			$clsNotification->doPushMessagingUser($params,$assign_to_id);
			/** =============================== */
			
			#activity log			
			/*$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("News","insert",$_POST);*/
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsDocs = new Docs();
	$clsFolder = new Folder();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	###
	$url = Input::post('url');
	$doc_id = (int) Input::post('doc_id');
	$res = ["result" =>	false];
	if($doc_id > 0){
		$oneItem = $clsDocs->getOne($doc_id, "title,more_information,list_department_id,list_role_id,cat_id");
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$file_doc = !empty($more_information['file_doc']) 
			? $more_information['file_doc'] : array();
		if(!empty($file_doc)){
			foreach($file_doc as $key => $val){
				if($val['url'] == $url){
					unset($file_doc[$key]);
					if($more_information['type'] == 'file') {
//						@unlink(ROOTPATH . $val['url']);
					}
					break;
				}
			}
		}
		$file_doc = @array_values($file_doc);
		#logs
		$logs = !empty($more_information['logs']) ? $more_information['logs'] : array();
		if($more_information['file_doc'] != $file_doc){
			$logs[$clsISO->getUniqid()]= array(
				'reg_date' => time(), 
				'user_id' => $profile_id,
				'action' => 'delete',
				'logs' => [
					[
						'from_value' => $more_information['file_doc'],
						'to_value' => $file_doc,
						'field' => 'file_doc',
					]
				]
			);
			$more_information["logs"] = $logs;
		}
		$more_information["file_doc"] = $file_doc;
		$mimeType = "";
		foreach($file_doc as $k => $v) {
			if($mimeType == "" && $v['mimeType'] != ""){
				$mimeType = $v['mimeType'];
				break;
			}			
		}
		$more_information["mimeType"] = $mimeType;
		
		if($clsDocs->updateOne($doc_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result" =>	true];
			
			#notify
			/** Gửi thông báo tới người theo dõi */
			$department_id = !empty($oneItem['list_department_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_department_id']) : array();
			$role_ids = !empty($oneItem['list_role_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_role_id']) : array();
			$assign_to_id = $clsDocs->getUserFollowDoc($department_id,$role_ids,1);
//			$assign_to_id = [289];
			$title = $oneItem['title'];
			$clsDocs->sendEmail($doc_id,$assign_to_id,"update");
			$titleNoty = sprintf('<strong>%s</strong> sửa đổi văn bản tài liệu [<strong>%s</strong>]', $clsProfile->getFullName($profile_id, $oneProfile), $title);
			$clsNotify->insertNotify('Docs', $clsDocs->pkey, $doc_id, $titleNoty, time(), $assign_to_id);
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id` IN (".implode(',',$assign_to_id).") and `token`<>''", "token");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => "Thông báo văn bản tài liệu",
					'message' => strip_tags($titleNoty),
					'url' => $clsFolder->getLink($oneItem["cat_id"])."?doc=".$core->encryptId($doc_id)
				), $subscribers);
			}
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Thông báo văn bản tài liệu",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . $clsFolder->getLink($oneItem["cat_id"])."?doc=".$core->encryptId($doc_id)
			];
			$clsNotification->doPushMessagingUser($params,$assign_to_id);
			/** =============================== */
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_delete_doc(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsDocs = new Docs();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsProject = new Project();
	###
	$doc_id = (int) Input::post('doc_id');
	$res = ["result" =>	false];
	if($doc_id > 0){
		$oneItem = $clsDocs->getOne($doc_id, "more_information");
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$file_doc = !empty($more_information['file_doc']) 
			? $more_information['file_doc'] : array();
		if(!empty($file_doc)){
			foreach($file_doc as $key => $val){
				if($more_information['type'] == 'file') {
//					@unlink(ROOTPATH . $val['url']);
				}
			}
		}
		$file_doc = @array_values($file_doc);
		$more_information["file_doc"] = $file_doc;
		if($clsDocs->deleteOne($doc_id)){
			$res = ["result" =>	true];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_load_docs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsProperty = new Property();
	$clsDocs = new Docs();
	$clsFolder = new Folder();
	$cat_id = (int)Input::post("cat_id",0);
	$authorized_person = (int)Input::post("authorized_person",0);
	$effective_date = Input::post("effective_date","");
	$keyword = Input::post("keyword","");
	$type = Input::post("type","");
	$string_doc = Input::post("string_doc","");
	$_ss_view_docs = 'grid';
	if(vnSessionExist('_ss_view_docs')){
		$_ss_view_docs = vnSessionGetVar('_ss_view_docs');
	}
	$more_information = $oneProfile['more_information'];
	$pinned_document = !empty($more_information['pinned_document']) ? $more_information['pinned_document'] : array();
	$department_profile_id = $oneProfile['department_id'];
	$oneDepartment = $clsProperty->getOne($department_profile_id,"title");
	$list_department_id = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	$role_id = $oneProfile['role_id'];
	$cond = "(((`list_department_id` = '' AND `list_role_id` = '') ";
	
	if(!empty($list_department_id) || !empty($role_id)) {
		$cond .= " OR (";
		if(!empty($list_department_id)) {
			$cond .= "(";
			foreach($list_department_id as $k => $department_id) {
				$cond .= (($k > 0) ? " OR " : "") . "`list_department_id` LIKE '%|".$department_id."|%'";
			}
			$cond .= ")";
		}
		if(!empty($role_id)) {
			$cond .= " AND (`list_role_id` LIKE '%|".$oneDepartment['title'] . "-" . $role_id."|%' OR `list_role_id`='')";
		}
		$cond .= ")";
	}	
	$cond .= " OR `user_id`='{$profile_id}')";
	$cond .= ")";
	
	$url = "/van-ban-he-thong/search";
	if($type == "pin") {
		$cond .= " AND doc_id IN (".implode(",",$pinned_document).") ";
		$url = "/van-ban-he-thong.html";
	}else if($type == "top") {
		$cond .= " AND is_top = '1' ";
		$url = "/van-ban-he-thong.html";
	}	
	
	$first = 0;
	$breadcrumd_name = "Tìm kiếm";
	if(!empty($cat_id)) {
		$oneCat = $clsFolder->getOne($cat_id);
		$cond .= " AND `cat_id` = '{$cat_id}'";
		$url = $clsFolder->getLink($cat_id,$oneCat);
		if(!empty($string_doc)){
			$url .= "?doc=".$string_doc;
		}
		$breadcrumd_name = $oneCat["title"];
	}
	if(!empty($keyword)) {
		$cond .= " AND (`title` LIKE '{$keyword}' OR `list_tags` LIKE '%|{$keyword}|%')";
		$url .= (($first == 0) ? "?" : "&") . "&k=".str_replace(" ","+",$keyword);
		$first = 1;
	}
	if(!empty($authorized_person)) {
		$cond .= " AND JSON_EXTRACT(`more_information`,'$.authorized_person') = '$authorized_person'";
		$url .= (($first == 0) ? "?" : "&") . "p=".$authorized_person;
		$first = 1;
	}
	if(!empty($effective_date)) {
		$cond .= " AND  FROM_UNIXTIME(`effective_date`,'%Y-%m-%d')='".$effective_date."'";
		$url .= (($first == 0) ? "?" : "&") . "d=".$effective_date;
		$first = 1;
	}
//	$clsDocs->setDeBug(1);
	$lstDocs = $clsDocs->getAll($cond);
//	$clsISO->print_pre($lstDocs);die;
	foreach ($lstDocs as $k => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		foreach($more_information['file_doc'] as $k_file => $v_file){
			$mimeType = $clsDocs->getTypeUrl($v_file['mimeType']);
			$more_information['file_doc'][$k_file]['file_type'] = $mimeType;
			if($more_information['type'] != "file") {
				$gg_key = $clsISO->getGoogleId($v_file['url']);
				if($mimeType == "doc" || $mimeType == "excel") {
					$more_information['file_doc'][$k_file]['link'] = $clsDocs->getIframeGoogleDoc($v_file['url'],$mimeType);
				}else{
					$more_information['file_doc'][$k_file]['link'] = $clsISO->getIframeUrl($v_file['url']);
				}
				
			}else{
				$more_information['file_doc'][$k_file]['link'] = $v_file['url'];
			}
		}
		$lstDocs[$k]['file_doc'] = !empty($more_information['file_doc']) ? $more_information['file_doc'] : array();		
		$mimeType = $more_information['mimeType'];
		if(preg_match('/\/(pdf)$/i', $mimeType)) {
			$icon = URL_IMAGES."/image_PDF.png";
		}else if(preg_match('/\/(msword|vnd.openxmlformats-officedocument.wordprocessingml.document|plain)$/i', $mimeType)){
			$icon =  URL_IMAGES."/image_DOC.png";
		}else if(preg_match('/\/(vnd.ms-excel|vnd.openxmlformats-officedocument.spreadsheetml.sheet|xlsx)$/i', $mimeType)){
			$icon =  URL_IMAGES."/image_EXCEL.png";
		}else if(preg_match('/\/(mp4|x-msvideo|webm)$/i', $mimeType)){
			$icon =  URL_IMAGES."/image_VIDEO.png";
		}else{
			$icon =  URL_IMAGES."/image-IMAGE.png";
		}
		$lstDocs[$k]['icon'] = $icon;
		$lstDocs[$k]['type'] = !empty($more_information['type']) ? $more_information['type'] : "file";
		
		$lstDocs[$k]['time'] = date("H:i d",$val['reg_date'])." thg ".date("n, Y",$val['reg_date']);
		if($clsISO->checkItemInArray($val['doc_id'],$pinned_document)) {
			$lstDocs[$k]['pinned'] = 1;
		}else{
			$lstDocs[$k]['pinned'] = 0;
		}		
	}
	$assign_list['_ss_view_docs'] = $_ss_view_docs;
	$assign_list['oneCat'] = $oneCat;
	$assign_list['cat_id'] = $cat_id;
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsDocs'] = $clsDocs;
	$assign_list['lstDocs'] = $lstDocs;
	$assign_list['pinned_document'] = $pinned_document;
	$html = $core->build('_ajax.load_docs.tpl');
	echo json_encode(array(
		"cond"=>$cond,
		"html" => $html,
		"url"=>$url,
		"total"=> !empty($lstDocs) ? count($lstDocs) : 0,
		"breadcrumd_name"=>$breadcrumd_name,
		"type"=>$type,
	));
}

function default_open_folder(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFolder = new Folder();
	##
	$uid = $clsISO->getUniqid();
	$folder_id = (int)Input::post('folder_id',0);
	##
	$titlePage = 'Thêm thư mục';
	$oneItem = $more_information = array();
	$type = 0;
	if(!empty($folder_id)){
		$titlePage = 'Chỉnh sửa thư mục';
		$oneItem = $clsFolder->getOne($folder_id);
		$type = $oneItem['type'];
	}
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('folder_id', $folder_id);
	$smarty->assign('type', $type);
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$html = $core->build('_ajax.open_folder.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_folder(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsDocs = new Docs();
	$clsProfile = new Profile();
	$clsFolder = new Folder();
	###
	$folder_id = (int) Input::post('folder_id', 0);
	$title = Input::post('title', "");
	$type = (int)Input::post('type', 1);
	$content = trim(Input::post("content",""));	
	$list_department_id = $oneProfile['list_department_id'];
	$more = array(); $msg = "_error";
	if($folder_id > 0){
		$oneItem = $clsFolder->getOne($folder_id);
		if($clsFolder->updateOne($folder_id, array(
			'title' => addslashes($title),
			'slug' => $clsISO->replaceSpace($title),
			'content' => $content,
			'type' => $type,
			'department_ids' => $list_department_id,
			'upd_date' => time(),
		))){
			$msg = '_success';
		}
	} else {
		$folder_id = $clsFolder->getMaxId();
		if($clsFolder->insert(array(
			$clsFolder->pkey => $doc_id,
			'user_id' => $profile_id,
			'title' => addslashes($title),
			'slug' => $clsISO->replaceSpace($title),
			'content' => $content,
			'type' => $type,
			'department_ids' => $list_department_id,
			'reg_date' => time(),			
			'upd_date' => time(),		
		))){
			$msg = '_success';	
		}
	}
	// Return
	echo $msg; die();
}
function default_load_folder(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsFolder = new Folder();
	$clsDocs = new Docs();
	$type = (int)Input::post("type",0);
	$list_department_id = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	
	$cond = "";
	if($type == 0) {
		$cond .= " `type`='0'";
	}elseif(!empty($list_department_id)) {
		$cond .= "`type`='1' AND (";
		foreach($list_department_id as $k => $department_id) {
			$cond .= (($k == 0) ? "" : " OR ") . "`department_ids` LIKE '%|{$department_id}|%'";
		}
		$cond .=")";
	}
	
	###
	$lstFolder = $clsFolder->getAll($cond);
	foreach ($lstFolder as $key => $val) {
		$total = $clsDocs->countItem("cat_id = '{$val['folder_id']}'");
		$lstFolder[$key]['total_doc'] = $total;
	}
	$total = !empty($lstFolder) ? count($lstFolder) : 0;
	$smarty->assign('clsFolder', $clsFolder);
	$smarty->assign('lstFolder', $lstFolder);
	$html = $core->build('_ajax.load_folder.tpl');
	// Return
	echo json_encode(['html'=>$html,'total'=>$total]); die();
}
function default_search_tag(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsTag = new Tag();
	$clsProject = new Project();
	$clsDocs = new Docs();
	$clsFolder = new Folder();
	###
	$results = array();
	
	$field = "{$clsDocs->pkey},`title`,`list_tags`";
	$list_tags = $clsDocs->getAll("`list_tags` <> ''", $field);
	$html = "";
	if(!empty($list_tags)) {
		foreach($list_tags as $key => $val) {			
			$tags = trim($val['list_tags'],"|");
			$arr_tags = @explode('|', $tags);
			foreach($arr_tags as $tag){
				$results[] = array(
					'id' => $tag,
					'text' => $tag
				);
			}			
		}
	}
	// Return
	echo json_encode($results); die();
}
function default_load_intro(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	##
	$id = (int) Input::get('id', 0);
	$table = Input::get('table', "Folder");
	$clsClassTable = new $table();
	$oneItem = $clsClassTable->getOne($id);
	$html = "";
	if(!empty($oneItem)) {
		$content = $oneItem['content'];
	
		$html = '<div clas="profile-wrap">
			<h3 class="fs-5 mb-1 text-main">'.$oneItem["title"].'</h3>
			<div class="">
				'.$oneItem["content"].'
			</div>
		</div>';
	}	
	// Return
	echo $html; die();
}
function default_search_suggest(){
	
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	##
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsFolder = new Folder();
	$keyword = Input::post('keyword', "");
	$is_important = (int)Input::post('is_important', 0);
	$cat_id = (int)Input::post('cat_id', 0);
	$authorized_person = (int)Input::post("authorized_person",0);
	$effective_date = Input::post("effective_date","");
	$clsDocs = new Docs();	
	$department_profile_id = $oneProfile['department_id'];
	$oneDepartment = $clsProperty->getOne($department_profile_id,"title");
	$list_department_id = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	$role_id = $oneProfile['role_id'];
	$cond = "(((`list_department_id` = '' AND `list_role_id` = '') ";	
	$condFolder = "(`department_ids` = ''";
	if(!empty($list_department_id) || !empty($role_id)) {
		$cond .= " OR (";
		if(!empty($list_department_id)) {
			$cond .= "(";
			$condFolder .= " OR (";
			foreach($list_department_id as $k => $department_id) {
				$cond .= (($k > 0) ? " OR " : "") . "`list_department_id` LIKE '%|".$department_id."|%'";
				$condFolder .= (($k > 0) ? " OR " : "") . "`department_ids` LIKE '%|".$department_id."|%'";
			}
			$cond .= ")";
			$condFolder .= ")";
		}
		if(!empty($role_id)) {
			$cond .= " AND (`list_role_id` LIKE '%|".$oneDepartment['title'] . "-" . $role_id."|%' OR `list_role_id`='')";
		}
		$cond .= ")";
	}	
	$cond .= " OR `user_id`='{$profile_id}')";
	$cond .= ")";
	$condFolder .= ")";
	
	if($keyword != "") {
		$cond .= " AND (`title` LIKE '%{$keyword}%' OR `list_tags` LIKE '%|{$keyword}|%')";
		$condFolder .= " AND (`title` LIKE '%{$keyword}%' OR `slug` LIKE '%|{$keyword}|%')";
	}
	if(!empty($is_important)) {
		$cond .= " AND `is_important` ='{$is_important}'";
	}
	if(!empty($cat_id)) {
		$oneCat = $clsFolder->getOne($cat_id);
		$cond .= " AND `cat_id` = '{$cat_id}'";
	}
	if(!empty($authorized_person)) {
		$cond .= " AND JSON_EXTRACT(`more_information`,'$.authorized_person') = '$authorized_person'";
	}
	if(!empty($effective_date)) {
		$cond .= " AND  FROM_UNIXTIME(`effective_date`,'%Y-%m-%d')='".$effective_date."'";
	}
//	$clsDocs->setDeBug(1);
	$lstItem = $clsDocs->getAll($cond);
//	$clsISO->print_pre($lstItem);die;
	$html = "";
	$arr_cache_user = $arr_cache_folder = array();
	if(!empty($lstItem)) {
		foreach($lstItem as $k => $val){
			$more_information = $clsISO->to_array_json($val['more_information']);
			foreach($more_information['file_doc'] as $k_file => $v_file){
				$mimeType = $clsDocs->getTypeUrl($v_file['mimeType']);
				$more_information['file_doc'][$k_file]['file_type'] = $mimeType;
				if($more_information['type'] != "file") {
					$gg_key = $clsISO->getGoogleId($v_file['url']);
					if($mimeType == "doc" || $mimeType == "excel") {
						$more_information['file_doc'][$k_file]['link'] = $clsDocs->getIframeGoogleDoc($v_file['url'],$mimeType);
					}else{
						$more_information['file_doc'][$k_file]['link'] = $clsISO->getIframeUrl($v_file['url']);
					}

				}else{
					$more_information['file_doc'][$k_file]['link'] = $v_file['url'];
				}
			}
			$lstItem[$k]['file_doc'] = !empty($more_information['file_doc']) ? $more_information['file_doc'] : array();		
			$mimeType = $more_information['mimeType'];
			if(preg_match('/\/(pdf)$/i', $mimeType)) {
				$icon = URL_IMAGES."/image_PDF.png";
			}else if(preg_match('/\/(msword|vnd.openxmlformats-officedocument.wordprocessingml.document|plain)$/i', $mimeType)){
				$icon =  URL_IMAGES."/image_DOC.png";
			}else if(preg_match('/\/(vnd.ms-excel|vnd.openxmlformats-officedocument.spreadsheetml.sheet|xlsx)$/i', $mimeType)){
				$icon =  URL_IMAGES."/image_EXCEL.png";
			}else if(preg_match('/\/(mp4|x-msvideo|webm)$/i', $mimeType)){
				$icon =  URL_IMAGES."/image_VIDEO.png";
			}else{
				$icon =  URL_IMAGES."/image-IMAGE.png";
			}
			$lstItem[$k]['icon'] = $icon;
			$lstItem[$k]['type'] = !empty($more_information['type']) ? $more_information['type'] : "file";

			$lstItem[$k]['time'] = date("H:i d",$val['reg_date'])." thg ".date("n, Y",$val['reg_date']);
			if($clsISO->checkItemInArray($val['doc_id'],$pinned_document)) {
				$lstItem[$k]['pinned'] = 1;
			}else{
				$lstItem[$k]['pinned'] = 0;
			}	
			if(!isset($arr_cache_user[$val['user_id']])) {
				$arr_cache_user[$val['user_id']] = $clsProfile->getOne($val['user_id']);
			}
			$lstItem[$k]['full_name'] = $clsProfile->getFullName($val['user_id'],$arr_cache_user[$val['user_id']]);
			$lstItem[$k]['avatar'] = $clsProfile->getAvatar($val['user_id'],$arr_cache_user[$val['user_id']]);
			
			if(!isset($arr_cache_folder[$val['cat_id']])) {
				$arr_cache_folder[$val['cat_id']] = $clsFolder->getOne($val['cat_id'],"title");
			}
			$lstItem[$k]['folder_name'] = $arr_cache_folder[$val['cat_id']]["title"];
		}
	}
//	 $clsFolder->setDeBug(1);
//	$lstFolder = $clsFolder->getAll($condFolder);
	$smarty->assign("lstItem",$lstItem);
	$smarty->assign("lstFolder",$lstFolder);
	$smarty->assign("clsFolder",$clsFolder);
	$html = $core->build("_ajax.load_suggest.tpl");
	// Return
	echo json_encode(array(
		"html"	=>	$html
	)); die();
}
function default_view_doc_detail(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsFolder = new Folder();
	$clsDocs = new Docs();
	$smarty->assign('clsProfile', $clsProfile);
	#
	$arrDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	
	$uid = $clsISO->getUniqid();
	$doc_id = (int) Input::post('doc_id', 0);
	$oneItem = $clsDocs->getOne($doc_id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$oneCat = $clsFolder->getOne($oneItem['cat_id']);
	$oneItem['reg_date'] = date("H:i d",$oneItem['reg_date'])." thg ".date("n, Y",$oneItem['reg_date']);
	$oneItem['upd_date'] = date("H:i d",$oneItem['upd_date'])." thg ".date("n, Y",$oneItem['upd_date']);
	
	if(!empty($oneItem['list_department_id'])) {
		$list_dep_id = $clsISO->getArrayByTextSlash($oneItem["list_department_id"]);
		$arr_role = [];
		if(!empty($oneItem['list_role_id'])) {
			$list_role_id = $clsISO->getArrayByTextSlash($oneItem["list_role_id"]);
			foreach($list_dep_id as $dep_id) {
				$tmp = $clsDocs->getRoleDoc($list_role_id,$dep_id);
				$arr_role = array_merge($arr_role,$tmp);
			}
		}else{
			foreach ($list_dep_id as $dep_id) {
				if(!empty($arrDepartment[$dep_id])) {
					$arr_role[] = "Phòng ".$arrDepartment[$dep_id]['title'];
				}				 
			}
		}
		if(!empty($arr_role)) {
			$text_role = implode("<br>",$arr_role);
		}
//		$clsISO->print_pre($text_role);die;
	}else{
		$text_role = "Tất cả nhân viên";
	}
	
	#file đính kèm
	foreach($more_information['file_doc'] as $k_file => $v_file){
		$mimeType = $clsDocs->getTypeUrl($v_file['mimeType']);
		$more_information['file_doc'][$k_file]['file_type'] = $mimeType;
		if($more_information['type'] != "file") {
			$gg_key = $clsISO->getGoogleId($v_file['url']);
			if($mimeType == "doc" || $mimeType == "excel") {
				$more_information['file_doc'][$k_file]['link'] = $clsDocs->getIframeGoogleDoc($v_file['url'],$mimeType);
			}else{
				$more_information['file_doc'][$k_file]['link'] = $clsISO->getIframeUrl($v_file['url']);
			}

		}else{
			$more_information['file_doc'][$k_file]['link'] = $v_file['url'];
		}
		if(preg_match('/\/(pdf)$/i', $v_file['mimeType'])) {
			$icon = URL_IMAGES."/image_PDF.png";
		}else if(preg_match('/\/(msword|vnd.openxmlformats-officedocument.wordprocessingml.document|plain)$/i', $v_file['mimeType'])){
			$icon =  URL_IMAGES."/image_DOC.png";
		}else if(preg_match('/\/(vnd.ms-excel|vnd.openxmlformats-officedocument.spreadsheetml.sheet|xlsx)$/i', $v_file['mimeType'])){
			$icon =  URL_IMAGES."/image_EXCEL.png";
		}else if(preg_match('/\/(mp4|x-msvideo|webm)$/i', $v_file['mimeType'])){
			$icon =  URL_IMAGES."/image_VIDEO.png";
		}else{
			$icon =  URL_IMAGES."/image-IMAGE.png";
		}
		$more_information['file_doc'][$k_file]['icon'] = $icon;
	}	
//	$clsISO->print_pre($more_information);die;	
	$file_doc = $more_information['file_doc'];
	$arr_cache_profile = array();
	$logs = array();
	if(!empty($more_information['logs'])) {
		$logs = array_reverse($more_information['logs']);
		foreach ($logs as $k => $val) {
			if(!isset($arr_cache_profile[$val['user_id']])) {
				$arr_cache_profile[$val['user_id']] = $clsProfile->getOne($val['user_id']);
			}
			$logs[$k]['full_name'] = ($val['user_id'] == $profile_id) ? "Bạn" : $arr_cache_profile[$val['user_id']]["full_name"];
			$logs[$k]['avatar'] = $clsProfile->getAvatar($val['user_id'], $arr_cache_profile[$val['user_id']]);
			$logs[$k]["reg_date"] =  date("H:i d",$val['reg_date'])." thg ".date("n, Y",$val['reg_date']);
			if($val['action'] == "insert") {
				$logs[$k]['text'] = " đã tạo văn bản";
			}else{
				$logs[$k]['text'] = " đã sửa đổi văn bản";
				if(!empty($val['logs'])) {
					$lstLog = $val['logs'];
					foreach($lstLog as $k_log => $v_log) {
						$lstLog[$k_log]['field_label'] = $clsDocs->getLabelLog($v_log['field']);
						if($v_log['field'] == "list_tags") {
							$lstLog[$k_log]['from_value'] = str_replace("|",", ",trim($v_log['from_value'],"|"));
							$lstLog[$k_log]['to_value'] = str_replace("|",", ",trim($v_log['to_value'],"|"));
						}else if($v_log['field'] == "list_department_id") {
							$from_value = $clsISO->getArrayByTextSlash($v_log['from_value']);
							$to_value = $clsISO->getArrayByTextSlash($v_log['to_value']);
							$txt_from = $txt_to = "";
							foreach ($from_value as $dep_id) {
								if(isset($arrDepartment[$dep_id])){
									$txt_from .= (($txt_from != "") ? ", ":"") . $arrDepartment[$dep_id]['title'];
								}
							}
							foreach ($to_value as $dep_id) {
								if(isset($arrDepartment[$dep_id])){
									$txt_to .= (($txt_to != "") ? ", ":"") . $arrDepartment[$dep_id]['title'];
								}
							}
							$lstLog[$k_log]['from_value'] = $txt_from;
							$lstLog[$k_log]['to_value'] = $txt_to;
						}else if($v_log['field'] == "list_role_id") {
							$from_value = $clsISO->getArrayByTextSlash($v_log['from_value']);
							$to_value = $clsISO->getArrayByTextSlash($v_log['to_value']);
							$from_value = $clsDocs->getRoleProfile2($from_value);
							$to_value = $clsDocs->getRoleProfile2($to_value);
							
							$lstLog[$k_log]['from_value'] = implode(", ",$from_value);
							$lstLog[$k_log]['to_value'] = implode(", ",$to_value);
						}else if($v_log['field'] == "file_doc") {
							$from_value = $v_log['from_value'];
							$to_value = $v_log['to_value'];
							$txt_from = $txt_to = "";
							foreach ($from_value as $k_file => $v_file) {
								$txt_from .= (($txt_from != "") ? ", ":"") . $v_file["name"];
							}
							foreach ($to_value as $k_file => $v_file) {
								$txt_to .= (($txt_to != "") ? ", ":"") . $v_file["name"];
							}
							
							$lstLog[$k_log]['from_value'] = $txt_from;
							$lstLog[$k_log]['to_value'] = $txt_to;
						}
					}
//					$clsISO->print_pre($lstLog);die;
					$logs[$k]["logs"] = $lstLog;
				}
			}
		}
	}
	
//	$clsISO->print_pre($logs);die;
	// Return
	$smarty->assign('logs', $logs);
	$smarty->assign('clsFolder', $clsFolder);
	$smarty->assign('clsDocs', $clsDocs);
	$smarty->assign('text_role', $text_role);
	$smarty->assign('doc_id', $doc_id);
	$smarty->assign('oneCat', $oneCat);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('file_doc', $file_doc);
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.view_doc_detail.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}