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
	,$keyword_page,$clsConfiguration,$clsISO, $deviceType;
	$clsProfile = new Profile();
	##
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	if($cmd=="_detail"){
		$overtime_id = Input::get('overtime_id', 0);
		$scriptJs.= '<a class="autoclick_'.$course_id.'"" overtime_id="'.$overtime_id.'" 
		onClick="$Core.overtime.view(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$course_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}
	$assign_list["scriptJs"] = $scriptJs;
	$permiss_view_all_overtime = $clsISO->checkPermission('view_all_overtime') ? 1 : 0;
	$assign_list['permiss_view_all_overtime'] = $permiss_view_all_overtime;
	##
	$list_preloaders = array();
	for($i= 0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	// $clsISO->print_pre($list_preloaders); die();
	$columnNum = ($deviceType=='phone') ? 1 : 2;
	$assign_list["columnNum"] = $columnNum;
	
	$field = "{$clsProfile->pkey},`code`,`full_name`,`first_name`,`last_name`,`role_id`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='"._DEPARTMENT_BO_ID."' or `department_id`='"._DEPARTMENT_TECH_ID."' or department_id='"._DEPARTMENT_MKT_ID."')", $field);
	$assign_list["list_staffs"] = $list_staffs;
	$permiss_view_all_overtime = $clsISO->checkPermission('view_all_overtime') ? 1 : 0;
	$assign_list["permiss_view_all_overtime"] = $permiss_view_all_overtime;
	/*=============Title & Description Page==================*/
	$title_page = 'Đăng ký tăng ca | '. PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_config(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	
	$setting = Input::get("setting");
	$Overtime_Notes= $clsConfiguration->getValue(sprintf('SiteMsg_%s', $setting));
	// Return
	echo html_entity_decode($Overtime_Notes); die();
}
function default_list(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsOvertime = new Overtime();
	$clsProperty = new Property();
	$smarty->assign('clsOvertime', $clsOvertime);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$permiss_view_all_overtime = $clsISO->checkPermission('view_all_overtime') ? 1 : 0;
	$smarty->assign('permiss_view_all_overtime', $permiss_view_all_overtime);
	###
	$staff_id = (int) Input::post('staff_id', 0);
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	$cond = "`is_trash`=0"; // and FROM_UNIXTIME(`reg_date`,'%Y')=''
	if($staff_id > 0) $cond.= " and `profile_id`='{$staff_id}'";
	if($start_time > 0 && $end_time){
		$cond.= " and `regis_date`>{$start_time}";
	} else if($start_time == 0 && $end_time > 0){
		$cond.= " and `regis_date`<{$end_time}";
	} else if($start_time > 0 && $end_time > 0){
		$cond.= " and (`regis_date` between {$start_time} and {$end_time})";
	}
	#- Begin Pagination
	$current_page = Input::post('page', 1);
	$per_page = Input::post('per_page', 100);
	$total_record = $clsOvertime->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	
	$list_items = $clsOvertime->getAll("{$cond} order by `reg_date` DESC".$limitCond);
	if(!empty($list_items)){
		$arr_profile_cached = $arr_bgcolor_cached = $arr_property_cached = array();
		foreach($list_items as $key => $val){
			$usr_id = $val['profile_id'];
			$status_id = $val['status_id'];
			if($permiss_view_all_overtime == 1){}
			if(!isset($arr_profile_cached[$usr_id])){
				$oProfile = $clsProfile->getOne($usr_id, "full_name,first_name,last_name,code,department_id,role_id");
				$arr_profile_cached[$usr_id] = array(
					'code' => $oProfile['code'],
					'full_name' => $clsProfile->getFullName($usr_id, $oProfile),
					'role_name' => $clsProperty->getTitle($oProfile['role_id']),
					'department_name' => $clsProperty->getTitle($oProfile['department_id'])
				);
			}
			$list_items[$key]['oProfile'] = $arr_profile_cached[$usr_id];
			if(!isset($arr_property_cached[$status_id])){
				$oStatus = $clsProperty->getOne($status_id,"title,textcolor,bgcolor");
				$arr_property_cached[$status_id] = $clsOvertime->getStatus($status_id, $oStatus);
				$arr_bgcolor_cached[$status_id] = $oStatus['bgcolor'];
			}
			$list_items[$key]['bgcolor'] = $arr_bgcolor_cached[$status_id];
			$list_items[$key]['status_name'] = $arr_property_cached[$status_id];
		}
	}
	$smarty->assign('list_items', $list_items);
	// $clsISO->print_pre($list_items); die();
	// Return
	$html = $core->build('_ajax.list.tpl');
	echo json_encode(array(
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	)); die();
}
function default_open(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsOvertime = new Overtime();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$uid = $clsISO->getUniqid();
	$overtime_id = (int) Input::post('overtime_id', 0);
	###
	$action = '_add';
	$start_date = strtotime(sprintf('%s %s', date('d-m-Y'), '18:00:00'));
	if(time() >= $start_date) $start_date = time();
	$oneItem = array(
		'is_fullday' => 0,
		'code' => $clsOvertime->genCode(),
		'regis_date' => time(),
		'start_date' => $start_date,
		'due_date' => strtotime('+2 hours', $start_date)
	);
	#- Cached approver
	$more_information = isset($oneProfile['more_information']) 
		? $oneProfile['more_information'] : array();
	$list_approver_arrs = isset($more_information['overtime_approver']) 
		? $more_information['overtime_approver'] : array();
	$list_confirmed_arrs = isset($more_information['overtime_confirmed']) 
		? $more_information['overtime_confirmed'] : array();
	if($overtime_id > 0){
		$action = '_edit';
		$oneItem = $clsOvertime->getOne($overtime_id);
		$list_approver_id = $oneItem['list_approver_id'];
		$list_confirmed_id = $oneItem['list_confirmed_id'];
		$list_approver_arrs = !empty($list_approver_id) 
			? $clsISO->getArrayByTextSlash($list_approver_id) : array();
		$list_confirmed_arrs = !empty($list_confirmed_id) 
			? $clsISO->getArrayByTextSlash($list_confirmed_id) : array();
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('overtime_id', $overtime_id);
	$smarty->assign('list_approver_arrs', $list_approver_arrs);
	$smarty->assign('list_confirmed_arrs', $list_confirmed_arrs);
	###
	$field = "{$clsProfile->pkey},full_name,first_name,last_name";
	$list_profile_approval = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(",",_PROFILE_APPROVAL_OVERTIME_ID).")", $field);
	$smarty->assign('list_profile_approval', $list_profile_approval);
	$list_profile_confirmed = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(",",_PROFILE_APPROVAL_OVERTIME_RESULT_ID).")", $field);
	$smarty->assign('list_profile_confirmed', $list_profile_confirmed);
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOvertime = new Overtime();
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$overtime_id = (int) Input::post('overtime_id', 0);
	$smarty->assign('uid', $uid);
	$smarty->assign('overtime_id', $overtime_id);
	###
	$regis_date = Input::post('regis_date');
	$start_date = Input::post('start_date');
	$due_date = Input::post('due_date');
	$is_fullday = (int) Input::post('is_fullday', 0);
	$list_approver_id = Input::post('list_approver_id');
	$list_confirmed_id = Input::post('list_confirmed_id');
	
	$regis_date = !empty($regis_date) ? $clsISO->toTime($regis_date) : 0;
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$due_date = !empty($due_date) ? $clsISO->toTime($due_date) : 0;
	#- Cached approver
	// $clsISO->print_pre($oneProfile); die();
	$more_information = isset($oneProfile['more_information']) 
		? $oneProfile['more_information'] : array();
	$more_information['overtime_approver'] = $list_approver_id;
	$more_information['overtime_confirmed'] = $list_confirmed_id;
	$clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	));
	$list_approver_id_slash = !empty($list_approver_id) 
		? $clsISO->makeSlashListFromArray($list_approver_id) : "";
	$list_confirmed_id_slash = !empty($list_confirmed_id) 
		? $clsISO->makeSlashListFromArray($list_confirmed_id) : "";
	###
	if($overtime_id == 0){
		$overtime_id = $clsOvertime->getMaxId();
		if($clsOvertime->insert(array(
			$clsOvertime->pkey => $overtime_id,
			'code' => Input::post('code'),
			'profile_id' => $profile_id,
			'regis_date' => $regis_date,
			'start_date' => $start_date,
			'due_date' => $due_date,
			'is_fullday' => $is_fullday,
			'time_off' => Input::post('time_off'),
			'content' => Input::post('content'),
			'priority_id' => Input::post('priority_id', 0),
			'status_id' => _STATUS_OVERTIME_PENDING_ID,
			'list_approver_id' => $list_approver_id_slash,
			'list_confirmed_id' => $list_confirmed_id_slash,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
			$titleNoty = sprintf('<strong>%s</strong> đăng ký tăng ca <strong>%s</strong> đang chờ bạn xác nhận ngày <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $content, $clsISO->convertTimeToText($regis_date));
			$clsNotify->insertNotify('Overtime',$clsOvertime->pkey, $overtime_id, $titleNoty, time(), $list_approver_id);
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Đăng ký tăng ca",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . sprintf('/overtime/%s.html', $overtime_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_approver_id);
		}
	} else {
		if($clsOvertime->updateOne($overtime_id, array(
			'code' => Input::post('code'),
			'regis_date' => $regis_date,
			'start_date' => $start_date,
			'due_date' => $due_date,
			'is_fullday' => $is_fullday,
			'time_off' => Input::post('time_off'),
			'content' => Input::post('content'),
			'priority_id' => Input::post('priority_id', 0),
			'list_approver_id' => $list_approver_id_slash,
			'list_confirmed_id' => $list_confirmed_id_slash,
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_view(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOvertime = new Overtime();
	###
	$uid = $clsISO->getUniqid();
	$overtime_id = (int) Input::post('overtime_id', 0);
	$oneItem = $clsOvertime->getOne($overtime_id);
	$more_information = (!empty($oneItem['more_information'])) ? $clsISO->to_array_json($oneItem['more_information']) : [];
	$list_approver_id = $oneItem['list_approver_id'];
	$list_confirmed_id = $oneItem['list_confirmed_id'];
	$list_approver_arrs = !empty($list_approver_id) 
		? $clsISO->getArrayByTextSlash($list_approver_id) 
		: array();
	$list_confirmed_arrs = !empty($list_confirmed_id) 
		? $clsISO->getArrayByTextSlash($list_confirmed_id) 
		: array();
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('overtime_id', $overtime_id);
	$smarty->assign('clsOvertime', $clsOvertime);
	$smarty->assign('list_approver_arrs', $list_approver_arrs);
	$smarty->assign('list_confirmed_arrs', $list_confirmed_arrs);
	// Return
	$html = $core->build('_ajax.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOvertime = new Overtime();
	###
	$msg = "_error";
	$overtime_id = (int) Input::post('overtime_id', 0);
	$oneItem = $clsOvertime->getOne($overtime_id);
	if($clsOvertime->deleteOne($overtime_id)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_do_action(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOvertime = new Overtime();
	###
	$msg = "_error";
	$holderG = Input::post('holderG', 'approved');
	$overtime_id = (int) Input::post('overtime_id', 0);
	if($overtime_id > 0){
		$field = "regis_date,profile_id,more_information";
		$oItem = $clsOvertime->getOne($overtime_id, $field);
		$profile_id = $oItem['profile_id'];
		$regis_date = $oItem['regis_date'];
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$upd_field = array('upd_date' => time());
		if($holderG == 'confirmed'){
			$upd_field['is_confirmed'] = 1;
			$more_information[$holderG] = array(
				'profile_id' => $profile_id,
				'confirmed_date' => time()
			);
		} else {
			$status_id = ($holderG == 'approved') 
				? _STATUS_OVERTIME_APPROVED_ID : _STATUS_OVERTIME_REFUSE_ID;
			$upd_field['status_id'] = $status_id;
			$more_information[$holderG] = array(
				'profile_id' => $profile_id,
				'status_date' => time()
			);
		}
		$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
		if($clsOvertime->updateOne($overtime_id, $upd_field)){
			$msg = "_success";
			$clsNotify = new Notify();
			if(in_array($holderG, array('approved','refure'))){
				$list_user_notify = sprintf('|%s|', $profile_id);
				$titleNoty = sprintf('<strong>%s</strong> đã <strong>%s</strong> yêu cầu tăng ca của bạn vào ngày <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsProperty->getTitle($status_id),
					$clsISO->convertTimeToText($regis_date)
				);
				$msg.= "|||" . $clsOvertime->getStatus($status_id);
			} else {
				$list_approver_id = $oItem['list_approver_id'];
				$list_user_notify = !empty($list_approver_id) 
					? $clsISO->getArrayByTextSlash($list_approver_id) : array();
				$titleNoty = sprintf('<strong>%s</strong> đã xác nhận hoàn thành tăng ca của bạn ngày <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsISO->convertTimeToText($regis_date)
				);
			}
			$clsNotify->insertNotify('Overtime',$clsOvertime->pkey, $overtime_id, $titleNoty, time(), $list_user_notify);
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Duyệt đăng ký tăng ca",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . sprintf('/overtime/%s.html', $overtime_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_user_notify);
		}
	}
	// Return
	echo $msg; die();
}
function default_load_done_ratio(){
    global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsOvertime = new Overtime();
    $overtime_id = Input::request('overtime_id',0);
    $done_ratio = $clsOvertime->getOneField('done_ratio', $overtime_id);
    $html = '<form class="p-2" method="post">
        <div class="form-group mb-2">
           <div class="range-slider">
				<input type="range" class="range-slider__range" name="done_ratio" 
				min="0" max="100" step="10" value="'.$done_ratio.'">
				<span class="range-slider__value">0</span>
			</div>
        </div>
		<hr class="my-3" />
        <div class="form-group">
            <button type="button" class="btn btn-block btn-primary" onclick="$Core.overtime.upd_done_ratio(this,event);" 
			overtime_id="'.$overtime_id.'">'.$core->makeIcon('check', 'Cập nhật').' </button>
        </div>
    </form>';
    // Return
    echo  $html; die();
}
function default_done(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOvertime = new Overtime();
	###
	$msg = "_error";
	$overtime_id = (int) Input::post('overtime_id', 0);
	$notes = Input::post('notes', "");
	if($overtime_id > 0){
		$oItem = $clsOvertime->getOne($overtime_id);
		
		$more_information = (!empty($oItem['more_information'])) ? $clsISO->to_array_json($oItem['more_information']) : [];
		$more_information['notes'] = $notes;
		$regis_date = $oItem['regis_date'];
		$list_confirmed_id = $oItem['list_confirmed_id'];
		$list_confirmed_id = !empty($list_confirmed_id) ? $clsISO->getArrayByTextSlash($list_confirmed_id) : array();
		if($clsOvertime->updateOne($overtime_id, array(
			'is_done' 			=> 1,
			'done_ratio' 		=> 100,
			'more_information'	=>	json_encode($more_information)
		))){
			$msg = "_success";
			$titleNoty = sprintf('<strong>%s</strong> đã hoàn thành công việc tăng ca <strong>%s</strong> vào ngày <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $oItem['content'], $clsISO->convertTimeToText($regis_date));
			$clsNotify->insertNotify('Overtime', $clsOvertime->pkey, $overtime_id, $titleNoty, time(), $list_confirmed_id);
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Hoàn thành tăng ca",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . sprintf('/overtime/%s.html', $overtime_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_confirmed_id);
		}
	}
	// Return
	echo $msg; die();
}
function default_upd_done_ratio(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOvertime = new Overtime();
	###
	$msg = "_error";
	$done_ratio = (int) Input::post('done_ratio', 0);
	$overtime_id = (int) Input::post('overtime_id', 0);
	if($overtime_id > 0){
		$oItem = $clsOvertime->getOne($overtime_id);
		$regis_date = $oItem['regis_date'];
		$list_confirmed_id = $oItem['list_confirmed_id'];
		$list_confirmed_id = !empty($list_confirmed_id) 
			? $clsISO->getArrayByTextSlash($list_confirmed_id) : array();
		$is_done = ($done_ratio == 100) ? 1 : 0;
		if($clsOvertime->updateOne($overtime_id, array(
			'is_done' => $is_done,
			'done_ratio' => $done_ratio
		))){
			$msg = "_success";
			if($is_done == 1){
				$titleNoty = sprintf('<strong>%s</strong> đã hoàn thành công việc tăng ca <strong>%s</strong> vào ngày <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $oItem['content'], $clsISO->convertTimeToText($regis_date));
				$clsNotify->insertNotify('Overtime',$clsOvertime->pkey, $overtime_id, $titleNoty, time(), $list_confirmed_id);
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Hoàn thành tăng ca",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . sprintf('/overtime/%s.html', $overtime_id)
				];
				$clsNotification->doPushMessagingUser($params,$list_confirmed_id);
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_report(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsOvertime = new Overtime();
	$current_year = (int) Input::get('year', date('Y'));	
	$start_date = strtotime(sprintf('01-01-%s', $current_year));
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_date = strtotime(sprintf('%s-12-%s', $end_day, $current_year));
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$assign_list["current_year"] = $current_year;
	
	$field = "{$clsProfile->pkey},`code`,`full_name`,`first_name`,`last_name`,`role_id`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='"._DEPARTMENT_BO_ID."' or `department_id`='"._DEPARTMENT_TECH_ID."' or department_id='"._DEPARTMENT_MKT_ID."')", $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$profile_id = $val[$clsProfile->pkey];
			$score = $clsOvertime->getScore($profile_id, $current_year);
			$list_staffs[$key]['score'] = $score;
		}
	}
	$score_arrs = @array_column($list_staffs, 'score');
	@array_multisort($score_arrs, SORT_DESC, $list_staffs);
	$assign_list["list_staffs"] = $list_staffs;
	/*=============Title & Description Page==================*/
	$title_page = 'Thi đua khối BO | '. PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}