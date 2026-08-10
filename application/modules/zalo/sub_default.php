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
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	###
	$load_preloaders = array();
	for($i=0; $i<100; $i++){
		$load_preloaders[] = $i;
	}
	$smarty->assign('load_preloaders', $load_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý nhóm check nguồn Zalo - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_manager_group(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$uid = $clsISO->getUniqid();
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.manager_group.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_manager_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsChatLog = new ChatLog();
	$clsZaloGroup = new ZaloGroup();
	#
	$html = "";
	$mod_page = Input::post('mod_page');
	$len = ($mod_page == 'tool') ? 25 : 40;
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$cond = "`is_company_group`=1";
	} else {
		$cond = "`is_company_group`=0 AND `user_id`='{$profile_id}'";
	}
	$list_groups = $clsZaloGroup->getAll($cond. " ORDER BY `reg_date` DESC");
	if(!empty($list_groups)){
		$arr_setting_cached = array();
		$tmp = $clsSetting->getAll("`_type`='_ZALO_GROUP_TYPE'");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_setting_cached[$val[$clsSetting->pkey]] = $clsSetting->getLabel($val[$clsSetting->pkey], $val, "", true);;
			}
			unset($tmp);
		}
		foreach($list_groups as $key => $val){
			$group_id = $val[$clsZaloGroup->pkey];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$type_id = (int) $core->get_field($more_information, "type_id", 0);
			$is_can_list = (int) $core->get_field($more_information, "is_can_list", 0);
			$is_reply_owner = (int) $core->get_field($more_information, "is_reply_owner", 0);
			$is_check_full = (int) $core->get_field($more_information, "is_check_full", 0);
			$is_check_source = (int) $core->get_field($more_information, "is_check_source", 0);
			$html.= '<tr>
				<td class="align-center" title="'.$val['name_group'].'">
					'.($arr_setting_cached[$type_id]).' '.$clsISO->truncate($val['name_group'], $len).'
				</td>
				'.($mod_page == 'tool' ? '<td class="align-center">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
				<td class="align-center text-center"><label class="switch">
					<input type="checkbox" onChange="$Core.zalo.set_status(this, event)" to_field="is_reply_owner" 
						group_id="'.$group_id.'" value="1"'.($is_reply_owner==1?' checked':'').'>
					<span class="slider round"></span>
				</label></td>
				<td class="align-center text-center"><label class="switch">
					<input type="checkbox" onChange="$Core.zalo.set_status(this, event)" to_field="is_check_full" 
						group_id="'.$group_id.'" value="1"'.($is_check_full==1?' checked':'').'>
					<span class="slider round"></span>
				</label></td>
				<td class="align-center text-center"><label class="switch">
					<input type="checkbox" onChange="$Core.zalo.set_status(this, event)" to_field="is_check_source" 
						group_id="'.$group_id.'" value="1"'.($is_check_source==1?' checked':'').'>
					<span class="slider round"></span>
				</label></td>
				<td class="align-center text-center"><label class="switch">
					<input type="checkbox" onChange="$Core.zalo.set_status(this, event)" group_id="'.$group_id.'" 
					to_field="status" value="1"'.($val['status']==1?' checked':'').'>
					<span class="slider round"></span>
				</label></td>
				<td class="align-center text-center"><label class="switch">
					<input type="checkbox" onChange="$Core.zalo.set_status(this, event)" group_id="'.$group_id.'" 
					to_field="is_can_list" value="1"'.($is_can_list==1?' checked':'').'>
					<span class="slider round"></span>
				</label></td>' : '').'
				<td class="align-center">
					<button group_id="'.$group_id.'" onClick="$Core.zalo.open_group(this, event)" title="Sửa nhóm" 
						class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-pencil"></i></button>
					<button group_id="'.$group_id.'" onClick="$Core.zalo.delete_group(this, event)" title="Xóa nhóm" 
						class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-trash"></i></button>
				</td>
			</tr>';
		}
	} else {
		$html.= '<tr>
			<td colspan="4" class="text-center">
				<img class="mb-2 w-px-150" src="'.URL_IMAGES.'/listing-empty.svg" />
				<p class="text-muted">Chưa có bản ghi nào!</p>
			</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();	
}
function default_open_group(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsSetting = new Setting();
	$clsZaloGroup = new ZaloGroup();
	###
	$uid = $clsISO->getUniqid();
	$group_id = (int) Input::post('group_id', 0);
	$id_group = Input::post('id_group');
	$name_group = Input::post('name_group');
	$type_id = (int) Input::post('type_id', 0);
	if($group_id > 0){
		$oneGroup = $clsZaloGroup->getOne($group_id);
		$id_group = $oneGroup['id_group'];
		$name_group = $oneGroup['name_group'];
		$more_information = $oneGroup['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$type_id = (int) $core->get_field($more_information, "type_id", 0);
	}
	$html = '<div class="modal-dialog modal-dialog-centered modal-sm">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header border-bottom d-flex align-items-center justify-content-between">
			<h5 class="modal-title" id="modalTopTitle">Thêm mới nhóm</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label class="form-label mb-1">Link nhóm</label>
				<div class="input-group">
					<input class="form-control" uid="'.$uid.'" name="link_group" value="" placeholder="https://" />
					<button type="button" uid="'.$uid.'" onClick="$Core.zalo.get_group_by_link(this, event)" 
						class="btn btn-outline-default">Get ID</button>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">Tên nhóm</label>
				<input class="form-control required" uid="'.$uid.'" name="name_group" 
					placeholder="Tên nhóm" value="'.$name_group.'" />
			</div>
			<div class="form-group mb-2">
				<label class="form-label mb-1">ID nhóm</label>
				<input class="form-control required" uid="'.$uid.'" name="id_group" 
					placeholder="Id nhóm" value="'.$id_group.'" />
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Loại nhóm</label>
				<select class="form-control form-select required" uid="'.$uid.'" name="type_id">
					'.$clsSetting->makeSelect('_ZALO_GROUP_TYPE', $type_id).'
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Hủy bỏ</button>
			<button type="button" class="btn btn-primary" title="Lưu lại" group_id="'.$group_id.'" 
				onClick="$Core.zalo.save_group(this, event)"><i class="bx bx-check"></i> Lưu lại</button>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_get_group_by_link(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	
	$msg = "_error"; 
	$id_group = ""; $name_group = "";
	$link_group = Input::post('link_group');
	if(!empty($link_group)){
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4NzFjMTJlYzI1YTc4N2Y1NDQ0NjdiMSIsImlhdCI6MTc1MjI4NTQ4NiwiZXhwIjoxNzgzODIxNDg2fQ.Y9gJDXAwKBUS0BNanxF3yJGh-0Pc7vXR5BR9ABho7fI'
		));
		$curl->post('https://public-api.bizflow.vn/functions/6871c12ec25a787f544467b1', array(
			'group_link' => $link_group
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && (int) $response['status'] == 200){
				$msg = "_success";
				$id_group = $response['data']['data']['groupId'];
				$name_group = $response['data']['data']['name'];
			}
		} else {
			$msg = "_curl_error";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'id_group' => $id_group,
		'name_group' => $name_group
	)); die();
}
function default_save_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsZaloGroup = new ZaloGroup();
	#
	$msg = "_error";
	$group_id = (int) Input::post('group_id', 0);
	$id_group = Input::post('id_group');
	$name_group = Input::post('name_group');
	$type_id = (int) Input::post('type_id', 0);
	$is_company_group = 0; // Là nhóm nội bộ
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$is_company_group = 1;
	}
	if($group_id > 0){
		$oneGroup = $clsZaloGroup->getOne($group_id);
		$more_information = $oneGroup['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information["type_id"] = $type_id;
		$more_information["id_group"] = $id_group;
		$more_information["name_group"] = $name_group;
		if($clsZaloGroup->updateOne($group_id, array(
			'id_group' => $id_group,
			'name_group' => $name_group,
			'is_company_group' => $is_company_group,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	} else {
		$found = false;
		$tmp = $clsZaloGroup->getByCond("`id_group`='{$id_group}'");
		if(!empty($tmp)){
			$more_information = $tmp['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information["type_id"] = $type_id;
			$more_information["id_group"] = $id_group;
			$more_information["name_group"] = $name_group;
			if($clsZaloGroup->updateOne($tmp[$clsZaloGroup->pkey], array(
				'id_group' => $id_group,
				'name_group' => $name_group,
				'is_company_group' => $is_company_group,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
		} else {
			$more_information = array();
			$more_information["type_id"] = $type_id;
			$more_information["id_group"] = $id_group;
			$more_information["name_group"] = $name_group;
			$dbconn->debug = true;
			if($clsZaloGroup->insert(array(
				'id_group' => $id_group,
				'name_group' => $name_group,
				'is_company_group' => $is_company_group,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'user_id' => $profile_id,
				'reg_date' => time(),
			))){
				$msg = "_success";
			}
		}
		if(!$found){
			
		} else {
			$msg = "_duplicated";
		}
	}
	// Return 
	echo $msg; die();
}
function default_set_field(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration;
	$clsZaloGroup = new ZaloGroup();
	##
	$msg = "_error";
	$group_id = (int) Input::post('group_id', 0);
	$to_field = Input::post('to_field', "status");
	$to_value = (int) Input::post('to_value', 0);
	if($group_id > 0 && !empty($to_field)){
		if($to_field == 'status'){
			$upd_field = array($to_field => $to_value);
		} else {
			$more_information = $clsZaloGroup->getOneField('more_information', $group_id);
			$more_information = $clsISO->to_array_json($more_information);
			$more_information[$to_field] = $to_value;
			$upd_field = array('more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE));
		}
		// $clsISO->print_pre($upd_field); die();
		if($clsZaloGroup->updateOne($group_id, $upd_field)){
			$msg = '_success';
		}
	}
	// Return 
	echo $msg; die();
}
function default_delete_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration;
	###
	$msg = "_error";
	$group_id = (int) Input::post('group_id', 0);
	if($group_id > 0){
		if($clsZaloGroup->deleteOne($group_id)){
			$msg = '_success';
		}
	}
	// Return 
	echo $msg; die();
}
function default_send_group(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	###
	$load_preloaders = array();
	for($i=0; $i<100; $i++){
		$load_preloaders[] = $i;
	}
	$assign_list["load_preloaders"] = $load_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý nhóm check nguồn Zalo - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function default_open_msg(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile,$deviceType;
	$clsSetting = new Setting();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsZaloGroup = new ZaloGroup();
	$clsZaloMessage = new ZaloMessage();
	###
	$uid = $clsISO->getUniqid();
	$msg_id = (int) Input::post('msg_id', 0);
	$send_type = Input::post('send_type', "");
	if($send_type == 'send_customer'){
		$cond = "`admin_id`='{$profile_id}' AND (`phone`<>'' AND `phone` IS NOT NULL)";
		$field = "{$clsCustomer->pkey},`name`,`phone`,`status_id`";
		$list_customers = $clsCustomer->getAll($cond, $field);
		if(!empty($list_customers)){
			$arr_property_cached = array();
			$tmp = $clsProperty->getAll("`property_type`='CUSTOMER_STATUS'", "{$clsProperty->pkey},`property_code`,`title`,`bgcolor`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_property_cached[$val[$clsProperty->pkey]] = $clsProperty->getLabelV2($val[$clsProperty->pkey], $val, "", false);
				}
				unset($tmp);
			}
			foreach($list_customers as $key => $_oCus){
				$status_id = (int) $_oCus['status_id'];
				$status_name = ($status_id > 0 && $arr_property_cached[$status_id]) ? $arr_property_cached[$status_id] : "";
				$list_customers[$key]['status_name'] = $status_name;
			}
		}
		$smarty->assign('list_customers', $list_customers);
	} else {
		if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
			$cond = "`is_company_group`=1";
		} else {
			$cond = "`is_company_group`=0 AND `user_id`='{$profile_id}'";
		}
		$list_groups = $clsZaloGroup->getAll($cond." ORDER BY `reg_date` DESC");
		if(!empty($list_groups)){
			$arr_setting_cached = array();
			$tmp = $clsSetting->getAll("`_type`='_ZALO_GROUP_TYPE'");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_setting_cached[$val[$clsSetting->pkey]] = $clsSetting->getLabel($val[$clsSetting->pkey], $val, "", true);;
				}
				unset($tmp);
			}
			foreach($list_groups as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$type_id = (int) $core->get_field($more_information, "type_id", 0);
				$type_name = ($type_id > 0 && isset($arr_setting_cached[$type_id])) ? $arr_setting_cached[$type_id] : "[N/A]";
				$list_groups[$key]['type_name'] = $type_name;
			}
		}
		$smarty->assign('list_groups', $list_groups);
	}
	$arr_types = array(
		'send_group' => 'Gửi nhóm',
		'send_customer' => ($deviceType == 'phone' ? 'Gửi KH' : 'Gửi khách hàng'),
		'send_customer_group' => ($deviceType == 'phone' ? 'Gửi TV' : 'Gửi thành viên'),
	);
	$arr_timers = array(
		'_minute' => 'phút',
		'_hour'	=> 'giờ',
		// '_day' => 'ngày',
		// '_week' => 'tuần'
	);
	$action = "_add";
	$more_information = $arr_images = $arr_groups = $arr_customers = array();
	$oneMsg = array('send_type' => 'send_group');
	$more_information['message'] = "";
	$more_information['send_status'] = '_now';
	$more_information['repeat_date'] = date('Y-m-d\TH:i');
	$more_information['repeat_interval'] = 1;
	$more_information['repeat_unit'] = '_minute';
	$manager_ids = array();
	if($msg_id > 0){
		$action = "_edit";
		$oneMsg = $clsZaloMessage->getOne($msg_id);
		$manager_ids = $oneMsg['manager_ids'];
		$manager_ids = $clsISO->getArrayByTextSlash($manager_ids, ",", []);
		$more_information = $oneMsg['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$arr_images = $core->get_field($more_information, "images", []);
		if($send_type == 'send_customer'){
			$arr_customers  = $core->get_field($more_information, "list_customer_id", []);
		} else {
			$arr_groups = $core->get_field($more_information, "list_group_id", []);
		}
		$more_information['repeat_date'] = date('Y-m-d\TH:i', $more_information['repeat_date']);
	}
	$oneMsg['manager_ids'] = $manager_ids;
	$smarty->assign('oneMsg', $oneMsg);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('arr_images', $arr_images);
	$smarty->assign('arr_groups', $arr_groups);
	$smarty->assign('arr_customers', $arr_customers);
	// $clsISO->print_pre($arr_customers); die();
	##
	$smarty->assign('uid', $uid);
	$smarty->assign('msg_id', $msg_id);
	$smarty->assign('action', $action);
	$smarty->assign('send_type', $send_type);
	$smarty->assign('arr_types', $arr_types);
	$smarty->assign('arr_timers', $arr_timers);
	// Return
	$html = $core->build('_ajax.open_msg.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_msg(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id;
	$clsZaloGroup = new ZaloGroup();
	$clsZaloMessage = new ZaloMessage();
	##
	$msg = "_error";
	$msg_id = (int) Input::post('msg_id', 0);
	$send_type = Input::post('send_type', 'send_group');
	$title = Input::post('title');
	$images = Input::post('images');
	$message = Input::post('message');
	$manager_ids = Input::post('manager_ids', []);
	$list_group_id = Input::post('list_group_id');
	$list_customer_id = Input::post('list_customer_id');
	$schedule_type = Input::post('schedule_type', 0);
	$time_start = Input::post('time_start', "");
	$time_end = Input::post('time_end', "");
	$repeat_interval = (int) Input::post('repeat_interval', 0);
	$repeat_unit = Input::post('repeat_unit', "_minute");
	// $clsISO->print_pre($_POST); die();
	if($msg_id > 0){
		$oneMsg = $clsZaloMessage->getOne($msg_id);
		$is_active = $oneMsg['is_active'];
		$more_information = $oneMsg['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['message'] = $message;
		$more_information['images'] = $images;
		if($send_type == 'send_customer'){
			$more_information['list_customer_id'] = $list_customer_id;
		} else {
			$more_information['list_group_id'] = $list_group_id;
		}
		$more_information['schedule_type'] = $schedule_type;
		$more_information['time_start'] = $time_start;
		$more_information['time_end'] = $time_end;
		$more_information['repeat_interval'] = $repeat_interval;
		$more_information['repeat_unit'] = $repeat_unit;
		if($schedule_type == '_repeat'){
			$is_changed = 0;
			if(!$clsISO->timeEquals($time_start, $oneMsg['time_start']) 
				|| !$clsISO->timeEquals($time_end, $oneMsg['time_end']) 
				|| $repeat_unit != $oneMsg['repeat_unit'] 
				|| $repeat_interval != $oneMsg['repeat_interval']){
				$is_changed = 1;
			}
			$is_active = ($is_changed == 1) ? 0 : $is_active;
		}
		// $clsISO->print_pre($clsISO->makeSlashListFromArray($manager_ids)); die();
		if($clsZaloMessage->updateOne($msg_id, array(
			'send_type' => $send_type,
			'title' => $title,
			'title' => $title,
			'manager_ids' => $clsISO->makeSlashListFromArray($manager_ids),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'schedule_type' => $schedule_type,
			'time_start' => $time_start,
			'time_end' => $time_end,
			'repeat_interval' => $repeat_interval,
			'repeat_unit' => $repeat_unit,
			'is_active' => $is_active,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		$msg_id = $clsZaloMessage->getMaxId();
		$more_information = array();
		$more_information['message'] = $message;
		$more_information['images'] = $images;
		if($send_type = 'send_customer'){
			$more_information['list_customer_id'] = $list_customer_id;
		} else {
			$more_information['list_group_id'] = $list_group_id;
		}
		$more_information['schedule_type'] = $schedule_type;
		$more_information['time_start'] = $time_start;
		$more_information['time_end'] = $time_end;
		$more_information['repeat_interval'] = $repeat_interval;
		$more_information['repeat_unit'] = $repeat_unit;
		// $dbconn->debug = true;
		if($clsZaloMessage->insert(array(
			$clsZaloMessage->pkey => $msg_id,
			'send_type' => $send_type,
			'title' => $title,
			'manager_ids' => $clsISO->makeSlashListFromArray($manager_ids),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'schedule_type' => $schedule_type,
			'time_start' => $time_start,
			'time_end' => $time_end,
			'repeat_interval' => $repeat_interval,
			'repeat_unit' => $repeat_unit,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_load_send_recipients(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsZaloGroup = new ZaloGroup();
	$clsZaloMessage = new ZaloMessage();
	#
	$html = '';
	$msg_id = (int) Input::post('msg_id', 0);
	$send_type = Input::post('send_type', 'send_group');
	$oneMsg = ($msg_id > 0) ? $clsZaloMessage->getOne($msg_id) : array();
	if($send_type == 'send_group' || $send_type == 'send_customer_group'){
		$more_information = $core->get_field($oneMsg, "more_information", []);
		$more_information = $clsISO->to_array_json($more_information);
		$list_group_arrs = $core->get_field($more_information, "list_group_id", []);
		if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
			$cond = "`is_company_group`=1";
		} else {
			$cond = "`is_company_group`=0 AND `user_id`='{$profile_id}'";
		}
		$list_groups = $clsZaloGroup->getAll($cond." ORDER BY `reg_date` DESC");
		if(!empty($list_groups)){
			$arr_setting_cached = array();
			$tmp = $clsSetting->getAll("`_type`='_ZALO_GROUP_TYPE'");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_setting_cached[$val[$clsSetting->pkey]] = $clsSetting->getLabel($val[$clsSetting->pkey], $val, "", true);;
				}
				unset($tmp);
			}
			foreach($list_groups as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$type_id = (int) $core->get_field($more_information, "type_id", 0);
				$type_name = ($type_id > 0 && isset($arr_setting_cached[$type_id])) ? $arr_setting_cached[$type_id] : "[N/A]";
				$list_groups[$key]['type_name'] = $type_name;
			}
			foreach($list_groups as $key => $_oGroup){
				$html.= '<div class="d-flex mb-2">
					<label class="d-flex align-items-center gap-2 ant-checkbox">
						<input'.(in_array($_oGroup['id_group'], $list_group_arrs) ? ' checked': '').' name="list_group_id[]" 
							value="'.$_oGroup['id_group'].'" type="checkbox" />
						<span class="text-nowrap line-clamp-1">'.$_oGroup['type_name'].' '.$_oGroup['name_group'].'</span>
					<label>
				</div>';
			}
		}
	} else {
		$cond = "`admin_id`='{$profile_id}' AND (`phone`<>'' AND `phone` IS NOT NULL)";
		$field = "{$clsCustomer->pkey},`name`,`phone`,`status_id`";
		$list_customers = $clsCustomer->getAll($cond, $field);
		if(!empty($list_customers)){
			$more_information = $core->get_field($oneMsg, "more_information", []);
			$more_information = $clsISO->to_array_json($more_information);
			$list_customer_arrs = $core->get_field($more_information, "list_customer_id", []);
			#
			$arr_property_cached = array();
			$tmp = $clsProperty->getAll("`property_type`='CUSTOMER_STATUS'", "{$clsProperty->pkey},`property_code`,`title`,`bgcolor`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_property_cached[$val[$clsProperty->pkey]] = $clsProperty->getLabelV2($val[$clsProperty->pkey], $val, "", false);
				}
				unset($tmp);
			}
			foreach($list_customers as $key => $_oCus){
				$status_id = (int) $_oCus['status_id'];
				$status_name = ($status_id > 0 && $arr_property_cached[$status_id]) ? $arr_property_cached[$status_id] : "";
				$html.= '<div class="d-flex mb-2">
					<label class="d-flex align-items-center gap-2 ant-checkbox">
						<input'.(in_array($_oCus[$clsCustomer->pkey], $list_customer_arrs) ? ' checked' : '').' name="list_customer_id[]" 
							value="'.$_oCus[$clsCustomer->pkey].'" type="checkbox" />
						<span class="text-nowrap line-clamp-1">'.$status_name." ".$_oCus['name'].'</span>
					<label>
				</div>';
			}
			unset($list_customers);
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function default_upload_image(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
    #
	$html = ''; $msg = "_error"; 
    if(!empty($_FILES['images'])){
        $images = $_FILES['images'];
		$total_images = (int) Input::post('total_images', 0);
        if(!empty($images['name']) && array_sum($images['error'])==0){
			$results = array();
            for($i=0; $i<count($images['name']); $i++){
                $img = array();
                $img['name'] = $images['name'][$i];
                $img['type'] = $images['type'][$i];
                $img['tmp_name'] = $images['tmp_name'][$i];
                $img['error'] = $images['error'][$i];
                $img['size'] = $images['size'][$i];
				$total_images+= 1;
                if(is_uploaded_file($img['tmp_name']) && $total_images <= 20){
					$total_upload+= 1;
                    $clsUploadFile = new UploadFile();
                    $image = $clsUploadFile->uploadItem($img,"/zalo",EXTENSION_FILE_UPLOAD, array(
						//'image_watermark_text' => array(
						//	'image_text' => 'Bảng hàng Masteri Homes',
						//	'image_text_font' => ABSPATH . '/tahoma.ttf',
						//	'image_text_size' => 12,
						//	'height' => 30,
						//),
						//'image_watermark_position' => 'B',
						// 'image_text_size' => 15,
						//'image_text_alignment' => 'L',
						// 'image_text_opacity' => '50',
						//'image_text_color' => '#FFFFFF',
						//'image_text_background' => '#000000',
						//'image_text_background_opacity' => 80,
						//'image_text_font' => ABSPATH . '/ARIAL.TTF',
						//'image_text_padding_x' => 20,
						//'image_text_padding_y' => 10,
						//'image_text_position' => 'BL',
						// 'image_text_x' => 10,
						// 'image_text_y' => -5,
					));
					if(!empty($image) && @file_exists(ROOTPATH.$image)){
						$msg = "_success";
						$html.= '<div class="item bg-lightest">
							<img src="'.$image.'" />
							<input type="hidden" name="images[]" value="'.$image.'" />
							<a class="delete" src="'.$image.'" onClick="$Core.zalo.delete_image(this, event)">
								<i class="bx bx-x"></i>
							</a>
						</div>';
					}
                }
            }
        }
    }
    // Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html
	)); die();
}
function default_delete_image(){
	global $clsISO,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration;
	$src = Input::post('src');
	if(!empty($src) && file_exists(ROOTPATH . $src)){
		@unlink(ROOTPATH . $src);
	}
	// Return
	echo 1; die();
}
function default_delete_msg(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration,$profile_id;
	$clsProfile = new Profile();
	$clsZaloMessage = new ZaloMessage();
	#
	$msg = "_error";
	$msg_id = (int) Input::post('msg_id', 0);
	if($msg_id > 0){
		$clsZaloMessage->deleteOne($msg_id);
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_load_msg(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration,$profile_id;
	$clsProfile = new Profile();
	$clsZaloGroup = new ZaloGroup();
	$clsZaloMessage = new ZaloMessage();
	$cond = "1=1";
	if(!$clsISO->checkPermissionGroup("DIRECTOR") || !$clsISO->checkDEV()){
		$cond = "(`user_id`='{$profile_id}' OR `manager_ids` LIKE '%|{$profile_id}|%')";
	}
	#- Paginatiom
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('page', 20);
	$total_record = $clsZaloMessage->countItem($cond);
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Paginatiom
	$list_message = $clsZaloMessage->getAll($cond." order by `reg_date` DESC".$limitCond);
	if(!empty($list_message)){
		$arr_groups = $arr_profile_cached = array();
		$tmp = $clsZaloGroup->getAll("1=1", "`id_group`,`name_group`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_groups[$val['id_group']] = $val['name_group'];
			}
			unset($tmp);
		}
		foreach($list_message as $key => $val){
			$user_id = $val["user_id"];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_groups = $core->get_field($more_information, "list_group_id", []);
			$name_group = ""; $arr_more_groups = array();
			if(!empty($list_groups)){ $ii = 0;
				foreach($list_groups as $id_group){
					if($ii==0) $name_group = $arr_groups[$id_group];
					$arr_more_groups[] = $arr_groups[$id_group];
					++$ii;
				}
				$list_message[$key]['total_groups'] = $ii;
				$list_message[$key]['name_group'] = $name_group;
				$list_message[$key]['html_more_group'] = sprintf('<li class=\'text-left\'>%s</li>', implode('</li><li class=\'text-left\'>', $arr_more_groups));
			} else {
				$list_message[$key]['total_groups'] = 0;
				$list_message[$key]['name_group'] = "Chưa chọn nhóm";
				$list_message[$key]['html_more_group'] = "";
			}
			$list_message[$key]['more_information'] = $more_information;
			if(!isset($arr_profile_cached[$user_id])){
				$arr_profile_cached[$user_id] = $clsProfile->getFullName($user_id);
			}
			$list_message[$key]['full_name'] = $arr_profile_cached[$user_id];
		}
	}
	// $clsISO->print_pre($list_message); die();
	$smarty->assign('list_message', $list_message);
	// Return
	$html = $core->build('_ajax.msg.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_record' => $total_record,
	)); die();
}
function default_set_status_msg(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $profile_id, $oneProfile;
	$clsZaloMessage = new ZaloMessage();
	$clsZaloMessageSchedule = new ZaloMessageSchedule();
	#
	$msg = "_error";
	$msg_id = (int) Input::post('msg_id', 0);
	$oneMsg = $clsZaloMessage->getOne($msg_id);
	$send_type = $oneMsg['send_type'];
	$more_information = $oneMsg['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	#
	$status = ($oneMsg['is_active'] == 0) ? 1 : 0;
	$next_run_at = $clsZaloMessage->initNextRunAt($oneMsg['time_start'], $oneMsg['time_end']);
	// $clsISO->print_pre(date('d/m/Y H:i:s', $next_run_at)); die();
	if($clsZaloMessage->updateOne($msg_id, array(
		'upd_date' => time(),
		'is_active' => $status,
		'last_run_at' => time(),
		'next_run_at' => $next_run_at,
		'user_id_update' => $profile_id,
	))){
		$msg = "_success";
	}
	$html_status = ($status == 1) ? '<span class="badge bg-label-success w-100">Đang chạy</span>' : '<span class="badge bg-label-dark w-100">Đang dừng</span>';
	$html = ($status == 1) ? '<button data-toggle="ripple" class="btn btn-sm btn-outline-primary w-px-100" onClick="$Core.zalo.set_status_msg(this, event)" msg_id="'.$msg_id.'"><i class="bx bx-pause"></i> Tạm dừng</button>' : '<button class="btn btn-sm btn-outline-default w-px-100" onClick="$Core.zalo.set_status_msg(this, event)" msg_id="'.$msg_id.'"><i class="bx bx-play"></i> Bắt đầu</button>';
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
		'html_status' => $html_status
	)); die();
}
function default_do_send_msg(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $profile_id, $oneProfile;
	$clsZalo = new Zalo();
	$clsCustomer = new Customer();
	$clsZaloMessage = new ZaloMessage();
	$clsZaloMessageSchedule = new ZaloMessageSchedule();
	#
	$msg = "_error"; $html_status = "";
	$msg_id = (int) Input::post('msg_id', 0);
	$oneMsg = $clsZaloMessage->getOne($msg_id);
	$send_type = $oneMsg['send_type'];
	$more_information = $oneMsg['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$is_dir_permiss = $clsISO->checkPermissionGroup('DIRECTOR') ? 0 : 0;
	$is_admin_permiss = $clsISO->checkPermissionGroup('ADMIN_PROJECT') ? 1 : 0;
	// Update is_active
	$clsZaloMessage->updateOne($msg_id, array(
		'is_send' => 1,
		'upd_date' => time(),
		'user_id_update' => $profile_id
	));
	if($send_type == 'send_group'){
		$html_status = '<span class="badge bg-label-success w-100">Đã gửi</span>';
		$arr_groups = $core->get_field($more_information, "list_group_id", []);
		$images = $core->get_field($more_information, "images", []);
		$message = $core->get_field($more_information, "message", "");
		if(!empty($arr_groups) && !empty($message)){
			foreach($arr_groups as $id_group){
				$curl = new \Curl\Curl();
				$curl->setHeaders(array(
					'Content-Type' => 'application/json',
					'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
				));
				$curl->post('https://public-api.func.vn/functions/68011b1db49c8ee7d3eac010', array(
					'message' => $message,
					'group_id' => $id_group
				));
				if(!empty($images)){
					$images_2 = array_map(function($img) { 
						return PCMS_URL . $img; 
					}, $images);
					$total_images = count($images);
					if($total_images == 1){
						$curl = new \Curl\Curl();
						$curl->setHeaders(array(
							'Content-Type' => 'application/json',
							'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_IMAGE_API_KEY)
						));
						$curl->post('https://public-api.func.vn/functions/6846ab85edb876b1e5d40921', array(
							"url" => $images_2[0],
							"desc" => "",
							"groupLayoutId" => 0,
							"group_id" => $id_group
						));
					} else {
						$curl = new \Curl\Curl();
						$curl->setHeaders(array(
							'Content-Type' => 'application/json',
							'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_IMAGES_API_KEY)
						));
						$curl->post('https://public-api.func.vn/functions/6846b624edb876b1e5d42a75', array(
							"urls" => $images_2,
							"group_id" => $id_group
						));
					}
				}
			}
		}
	} else if($send_type == 'send_customer'){
		$message = $oneMsg['message'];
		$html_status = '<span class="badge bg-label-success w-100">Đã gửi</span>';
		$arr_customers = $core->get_field($more_information, "list_customer_id", []);
		$images = $core->get_field($more_information, "images", []);
		// $clsISO->print_pre($arr_customers); die();
		if(!empty($arr_customers) && !empty($message)){
			$list_customers = $clsCustomer->getAll("{$clsCustomer->pkey} in ('".implode(',', $arr_customers)."')", "{$clsCustomer->pkey},`phone`");
			$arr_customers = array();
			if(!empty($list_customers)){
				foreach($list_customers as $key => $val){
					$arr_customers[$val[$clsCustomer->pkey]] = $val['phone'];
				}
			}
			foreach($arr_customers as $id_cus => $phone_cus){
				if($is_dir_permiss || $is_admin_permiss){
					$ZALO_API_URL = 'https://public-api.func.vn/functions/680b66ab9ce6fa24b6b8f63a';
					$ZALO_API_KEY = ZALO_GROUP_SEND_MESSAGE_API_KEY;
				} else {
					$ZALO_API_URL = $clsZalo->get_config_field('USER_SEND_MESSAGE', 'api_url');
					$ZALO_API_KEY = $clsZalo->get_config_field('USER_SEND_MESSAGE', 'api_key');
				}
				if(!empty($ZALO_API_URL) && !empty($ZALO_API_KEY)){
					$curl = new \Curl\Curl();
					$curl->setHeaders(array(
						'Content-Type' => 'application/json',
						'Authorization' => sprintf('Bearer %s', $ZALO_API_KEY)
					));
					$curl->post($ZALO_API_URL, array(
						'message' => $message,
						"phone_number" => $phone_cus,
					));
				}
				if(!empty($images)){
					$images_2 = array_map(function($img) { 
						return PCMS_URL . $img; 
					}, $images);
					$total_images = count($images);
					if($total_images == 1){
						if($is_dir_permiss || $is_admin_permiss){
							$ZALO_API_URL = 'https://public-api.func.vn/functions/697b2dd37a52c8631cd6841f';
							$ZALO_API_KEY = ZALO_GROUP_SEND_IMAGE_API_KEY;
						} else {
							$ZALO_API_URL = $clsZalo->get_config_field('USER_SEND_IMAGE', 'api_url');
							$ZALO_API_KEY = $clsZalo->get_config_field('USER_SEND_IMAGE', 'api_key');
						}
						if(!empty($ZALO_API_URL) && !empty($ZALO_API_KEY)){
							$curl = new \Curl\Curl();
							$curl->setHeaders(array(
								'Content-Type' => 'application/json',
								'Authorization' => sprintf('Bearer %s', $ZALO_API_KEY)
							));
							$curl->post($ZALO_API_URL, array(
								"url" => $images_2[0],
								"desc" => "",
								"phone_number" => $phone_cus
							));
						}
					} else {
						if($is_dir_permiss || $is_admin_permiss){
							$ZALO_API_URL = 'https://public-api.func.vn/functions/697b2e287a52c8631cd6842c';
							$ZALO_API_KEY = ZALO_GROUP_SEND_IMAGES_API_KEY;
						} else {
							$ZALO_API_URL = $clsZalo->get_config_field('USER_SEND_IMAGES', 'api_url');
							$ZALO_API_KEY = $clsZalo->get_config_field('USER_SEND_IMAGES', 'api_key');
						}
						if(!empty($ZALO_API_URL) && !empty($ZALO_API_KEY)){
							$curl = new \Curl\Curl();
							$curl->setHeaders(array(
								'Content-Type' => 'application/json',
								'Authorization' => sprintf('Bearer %s', $ZALO_API_KEY)
							));
							$curl->post($ZALO_API_URL, array(
								"urls" => $images_2,
								"phone_number" => $phone_cus
							));
						}
					}
				}
			}
		}
	} else if($send_type == 'send_customer_group'){
		
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html_status' => $html_status
	)); die();
}
function default_load_chatlogs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsZaloGroup = new ZaloGroup();
	$clsZaloChatLog = new ZaloChatLog();
	
	$html = ""; $total_record = 0;
	$list_chatlogs = $dbconn->getAll("select `t1`.*,`t2`.`name_group` from {$clsZaloChatLog->tbl} as `t1` 
		inner join {$clsZaloGroup->tbl} as `t2` on `t1`.`group_id`=`t2`.`id` 
		where 1=1 and `t2`.`is_blocked`='0' order by `t1`.`created_at` DESC limit 0,1000");
	// $clsISO->print_pre($list_chatlogs); die();
	if(!empty($list_chatlogs)){
		$total_record = count($list_chatlogs);
		foreach($list_chatlogs as $key => $val){
			$chatlog_id = $val[$clsZaloChatLog->pkey];
			$data_logs = $val['data_logs'];
			$data_logs = $clsISO->to_array_json($data_logs);
			$id_group = $val['id_group'];
			$group_id = $val['group_id'];
			$sender_id = $val['sender_id'];
			$name_group = $val['name_group'];
			$dName = $data_logs['data']['data']['groupMsgs'][0]['dName'];
			$content = $data_logs['data']['data']['groupMsgs'][0]['content'];
			// $clsISO->print_pre($data_logs);
			$html.= '<tr>
				<td class="align-center">'.$name_group.'
					<a onClick="$Core.zalo.block_group(this, event)" title="Chặn group này" id_group="'.$id_group.'" group_id="'.$group_id.'" class="btn btn-icon btn-sm btn-outline-gray btn-pinned rounded-pill"><i class=\'bx bx-block\'></i></a>
				</td>
				<td class="align-center text-nowrap">'.$dName.'</td>
				<td class="align-center">
					<div class="readmore-content" data-height="58px">'.nl2br(html_entity_decode($content)).'</div>
				</td>
				<td class="align-center text-nowrap">'.$clsISO->convertTimeToText($val['created_at'], true).'</td>
				<td class="align-center text-center text-nowrap">
					<button onClick="$Core.zalo.open_chat(this, event)" sender_id="'.$sender_id.'" 
						title="Chat với người này" class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-chat"></i></button>
					<button onClick="$Core.zalo.delete_chatlogs(this, event)" chatlog_id="'.$chatlog_id.'" title="Xóa" class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-trash"></i></button>
				</td>
			</tr>';
		}
	} else {
		$html.= '<tr>
			<td colspan="5" class="text-center">
				<img class="mb-2 w-px-150" src="'.URL_IMAGES.'/listing-empty.svg" />
				<p class="text-muted">Chưa có bản ghi nào!</p>
			</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_record' => $total_record
	)); die();	
}
function default_block_group(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsChatLog = new ChatLog();
	$clsZaloGroup = new ZaloGroup();
	###
	$msg = "_error";
	$group_id = (int) Input::post('group_id', 0);
	$oGroup = $clsZaloGroup->getOne($group_id);
	if($oGroup['is_blocked'] == 1){
		$is_blocked = 0;
	} else {
		$is_blocked = 1;
	}
	if($clsZaloGroup->updateOne($group_id, array(
		'is_blocked' => $is_blocked
	))){
		$msg = "_success";
	}
	// Return 
	echo $msg; die();
}
function default_delete_chatlogs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsZaloGroup = new ZaloGroup();
	$clsZaloChatLog = new ZaloChatLog();
	###
	$msg = "_error";
	$chatlog_id = (int) Input::post('chatlog_id');
	if($chatlog_id > 0 && $clsZaloChatLog->deleteOne($chatlog_id)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}