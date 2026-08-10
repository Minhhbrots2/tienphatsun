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
function calendar_default(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$today = date("d/m/Y");
	#
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	$cond = " AND (FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$today}' OR FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$today}')";
	if(!$clsISO->checkPermission('calendar_billing')) {
		header("Location: /#not_permission");
		exit();
	}
	$is_project_dir = 0; // Giám đốc dự án
	$list_BILLING_TYPE = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	$arr_billing_type = $arr_billing_type_permiss = $arr_projects_permiss = array();
	if($clsISO->checkPermissionGroup('DIRECTOR') || $profile_id == 289){
		$arr_billing_type = $list_BILLING_TYPE;
	} else if(in_array($role_id, array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT, _ROLE_GD_SALE))){
		$is_project_dir = 1;
		$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
		$arr_projects_permiss = array_keys($permiss_billing);
		foreach($permiss_billing as $key => $val){
			$arr_billing_type_permiss = array_merge($arr_billing_type_permiss, $val);
		}
		foreach($list_BILLING_TYPE as $key => $val) {
			if($clsISO->checkItemInArray($val["property_id"], $arr_billing_type_permiss)) {
				$arr_billing_type[] = $val;
			}			
		}
	}
	$sql_project = "`is_trash`=0";
	if(!empty($arr_projects_permiss)){
		$sql_project.= " AND `{$clsProject->pkey}` IN (".implode(',', $arr_projects_permiss).")";
	}
	$arr_projects = $clsProject->getAll($sql_project, "{$clsProject->pkey},`title`");
	$assign_list['is_project_dir'] = $is_project_dir;
	$assign_list['arr_projects'] = $arr_projects;
	$assign_list['arr_billing_type'] = $arr_billing_type;
	##
	$list_filters = array();
	$list_filters['THIS_WEEK'] = $core->get_Lang('ThisWeek');
	$list_filters['THIS_MONTH'] = $core->get_Lang('ThisMonth');
	$list_filters['THIS_YEAR'] = $core->get_Lang('ThisYear');
	##
	$list_preloaders = array();
	for($i=0; $i<10; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["clsProperty"] = $clsProperty;
	$assign_list["list_filters"] = $list_filters;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Lịch ký HĐMB VBTT | ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function calendar_load_calendar(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$deviceType;
	$uid = Input::post("uid", $clsISO->getUniqid());
	$date_type = Input::post("date_type","_all");
	$billing_type = (int) Input::post("billing_type",0);
	$start = Input::post("start",0);
	$end = Input::post("end",0);
	$current_time = (int) Input::post("current_time",0);
	$project_id = (int) Input::post("project_id",0);
	$html = '<div id="calendar_'.$uid.'"></div>';
	$max_event = ($deviceType == "phone") ? "0" : "2";
	$setTime = "";
	if($current_time > 0) {
		$setTime = "initialDate: `".date("Y",$current_time)."-".date("m",$current_time)."-01`,";
	}
	$query_string = "";
	if($date_type != "_all") {
		$query_string .= "&date_type=".$date_type;
	}
	if($billing_type > 0) {
		$query_string .= "&billing_type=".$billing_type;
	}
	if($project_id > 0) {
		$query_string .= "&project_id=".$project_id;
	}
	$edit = "";
	if($clsISO->checkPermissionGroup('DIRECTOR') || $oneProfile['role_id'] == _ROLE_STAFF_ADMIN || $profile_id == 289) {
		$edit.= '<div class="dropdown zindex-6" style="z-index:999999 !important">
			<button class="btn btn-sm btn-icon btn-link rounded-pill dropdown-toggle hide-arrow" 
			data-bs-toggle="dropdown" title="Thêm công cụ">
				<i class=\'bx bx-dots-vertical-rounded text-muted\'></i>
			</button>
			<ul class="dropdown-menu w-px-150">
				<li><a href="javascript:void(0);" onclick="$Core.calendar.confirm_signed(this,event)" billing_id="`+arg.event.extendedProps.billing_id+`" sign_type="`+arg.event.extendedProps.sign_type+`" cal_id="'.$uid.'" class="dropdown-item text-success`+(arg.event.extendedProps.status_sign==1?\' disabled\':\'\')+`" title="Xác nhận ký">
					<i class=\'bx bx-check-circle\'></i> Xác nhận ký
				</a></li>
				<li><a href="javascript:void(0);" onclick="$Core.calendar.open_sign_date(this,event)" billing_id="`+arg.event.extendedProps.billing_id+`" sign_type="`+arg.event.extendedProps.sign_type+`" 
					class="dropdown-item text-danger`+(arg.event.extendedProps.status_sign==1?\' disabled\':\'\')+`" cal_id="'.$uid.'" tp="_cancel" title="Hủy lịch ký" sign_date="`+arg.event.extendedProps.sign_date+`"><i class=\'bx bx-trash\'></i> Thay đổi lịch ký
				</a></li>
				<li class="dropdown-divider"></li>
				<li><a href="javascript:void(0);" onclick="$Core.billing.add_info(this,event)" billing_id="`+arg.event.extendedProps.billing_id+`" 
					class="dropdown-item" title="Chỉnh sửa thông tin" uid="'.$uid.'">
					<i class="bx bx-pencil"></i> Sửa thông tin
				</a></li>
			</ul>
		</div>';
	}
	$callback = 'var settings = {'.$setTime.'
		timeZone: "UTC",
		locale: "vi",
		height:\'auto\',
		contentHeight: \'auto\',
		initialView: "dayGridMonth",
		events: "'.PCMS_URL.'/index.php?mod=home&sub=calendar&act=load_billing_calendar&openFrom=_billing'.$query_string.'",
		dayMaxEvents: '.$max_event.',
		editable: false,
		selectable: true,
		buttonText: { today: "Tháng này" },
		eventContent: function(arg) {
			//console.log(arg);
			return {
				html: `<a class=\"badge rounded-0 fc-event-badge `+arg.event.extendedProps.cls+` cursor-pointer\" onclick=\"view_billing(this, event)\" billing_id=\"`+arg.event.extendedProps.billing_id+`\" uid="'.$uid.'" title=\"`+arg.event.extendedProps.tooltip+`\">`+arg.event.title+`</a>'.$edit.'`
			};
		}, dayCellContent: function(arg) {
			return {
				html: `<div class="d-flex align-items-center gap-1 gap-lg-2 justify-content-between">
				'.($deviceType=='phone'?'':'<span class="number_item fw-bold btn btn-icon btn-xs btn-danger fs-10 rounded-pill d-none">0</span>').'
				<div class="d-flex align-items-center gap-1 gap-lg-2 flex-fill justify-content-end">
					<span>${arg.date.getDate()}</span>
					<button class="btn btn-'.($deviceType=='phone'?'xs':'sm').' btn-icon btn-outline-default" sign_date="${Math.floor(arg.date.getTime()/1000)}" 
					onclick="$Core.calendar.open_sign_date(this, event)" tp="_add" cal_id="'.$uid.'" title="Thêm lịch ký">+</button>
				</div></div>`
			};
		}, datesSet: function(info) {
			var current_time = info.view.currentStart;
			current_time = current_time.getTime()/1000;
			var first_date = info.start;
			first_date = first_date.getTime()/1000;
			var last_date =  info.end;	
			last_date = last_date.getTime()/1000;
			$("#start_time").val(first_date);
			$("#end_time").val(last_date);
			$("#current_time").val(current_time);
			$Core.calendar.load_sidebar();
			$Core.calendar.load_summary();
		},
		moreLinkText: function(dayCount) {';
		if($deviceType == "phone"){
			$callback .= 'return ""+dayCount+"";';
		}else{
			$callback .= 'return "Xem thêm ("+dayCount+")";';
		}
	$callback .='
		},
	};
	var calendarEl = document.getElementById("calendar_'.$uid.'"),
		calendar = new FullCalendar.Calendar(calendarEl, settings);
	calendarEl._calendar = calendar;
	calendar.render();';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback
	)); die();
}
function calendar_get_billing_type(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$profile_id,$deviceType;
	global $profile_id, $oneProfile;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$billing_type = 0; $msg = "_error";
	$project_id = (int) Input::post('project_id', 0);
	$more_information = $oneProfile['more_information'];
	$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
	if(!empty($permiss_billing)){
		$one_permiss = $permiss_billing[$project_id];
		if(!empty($one_permiss) && count($one_permiss) == 1){
			$msg = "_ok";
			$billing_type = $one_permiss[0];
		}
	}
	# Return
	echo json_encode(array(
		'msg' => $msg,
		'billing_type' => $billing_type
	)); die();
}
function calendar_confirm_signed(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$profile_id,$deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$msg = "_error";
	$sign_type = Input::post('sign_type', '_contract');
	$billing_id = (int) Input::post('billing_id', 0);
	if($billing_id > 0){
		$field = "`logs`,`admin_id`";
		$oneBilling = $clsBilling->getOne($billing_id, $field);
		$admin_id = (int) $oneBilling['admin_id'];
		$logs = $oneBilling['logs'];
		$logs = $clsISO->to_array_json($logs);
		if($admin_id == $profile_id){
			$update_field = array();
			if($sign_type == '_contract'){
				$status_name = $clsProperty->getTitle(_CONTRACT_STATUS_DONE_ID);
				$update_field['contract_status_id'] = _CONTRACT_STATUS_DONE_ID;
			} else if($sign_type == '_text'){
				$status_name = $clsProperty->getTitle(_CONTRACT_STATUS_AGREE_SIGNED_ID);
				$update_field['agree_status_id'] = _CONTRACT_STATUS_AGREE_SIGNED_ID;
			}
			$content = sprintf(
				'<strong>%s</strong> thay đổi tình trạng ký thành %s', 
				$clsProfile->getFullName($profile_id, $oneProfile), $status_name
			);
			$logs[$clsISO->getUniqid()] = array(
				'profile_id' => $profile_id,
				'reg_date' => time(),
				'content' => $content
			);
			$update_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
			if($clsBilling->updateOne($billing_id, $update_field)){
				$msg = '_success';
			}
		} else {
			$msg = "_invalid";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function calendar_open_contract_confirm(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', '_add');
	$cal_id = Input::post('cal_id');	
	$sign_type = Input::post('sign_type', '');
	$billing_id = (int) Input::post('billing_id', 0);
	if($billing_id > 0 /*&& $sign_type != "_contract"*/){
		$oneBilling = $clsBilling->getOne($billing_id);
//		$clsISO->print_pre($oneBilling);die;
		$more_information = $clsISO->to_array_json($oneBilling["more_information"]);
		$smarty->assign('more_information', $more_information);
		$smarty->assign('oneBilling', $oneBilling);
	}
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	$smarty->assign('cal_id', $cal_id);
	$smarty->assign('sign_type', $sign_type);
	$smarty->assign('billing_id', $billing_id);
	// Return
	$html = $core->build('calendar'.DS.'_ajax.open_contract_confirm.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function calendar_save_contract_confirm(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$profile_id,$deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$msg = "_error";
	$sign_type = Input::post('sign_type', '_contract');
	$billing_id = (int) Input::post('billing_id', 0);
	$billing_method = (int) Input::post('billing_method', 0);
	$bank_guarantee_id = (int) Input::post('bank_guarantee_id', 0);
	$totalgrand = Input::post('totalgrand', 0);
	if($billing_id > 0){
		$oBilling = $clsBilling->getOne($billing_id);
		$more_information = $oBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$dep_logs = $core->get_field($more_information, "dep_logs", []);
		$change_logs = $core->get_field($more_information, "change_logs", []);
		$project_director_id = (int) $core->get_field($dep_logs, "project_director_id", 0);
		#
		$admin_id = (int) $oBilling['admin_id'];
		$logs = $oBilling['logs'];
		$logs = $clsISO->to_array_json($logs);
		if($admin_id == $profile_id){	
			$action_logs = [];
			if($oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)){
				if($project_director_id > 0) {
					$more_profile = $clsProfile->getOneField("more_information", $project_director_id);
					$more_profile = $clsISO->to_array_json($more_profile);
					$billing_changing_confirms = $core->get_field($more_profile, "billing_changing_confirms", []);
					if(!in_array($billing_id, $billing_changing_confirms)){
						$billing_changing_confirms[] = $billing_id;
						$more_profile['billing_changing_confirms'] = $billing_changing_confirms;
						$clsProfile->updateOne($project_director_id, array(
							'more_information' => json_encode($more_profile, JSON_UNESCAPED_UNICODE)
						));
					}	
				}
				$action_logs[] = 'tổng tiền: '.$totalgrand;	
			}
			$update_field = array();
			if($sign_type == '_contract'){
				$status_name = $clsProperty->getTitle(_CONTRACT_STATUS_DONE_ID);
				$update_field['contract_status_id'] = _CONTRACT_STATUS_DONE_ID;
			} else if($sign_type == '_text'){
				$status_name = $clsProperty->getTitle(_CONTRACT_STATUS_AGREE_SIGNED_ID);
				$update_field['agree_status_id'] = _CONTRACT_STATUS_AGREE_SIGNED_ID;
			}
			$action_logs[] = 'tình trạng ký thành '.$status_name;				
			#
			if(!empty($action_logs)){
				$content = sprintf('<strong>%s</strong> thay đổi %s', $clsProfile->getFullName($profile_id, $oneProfile), implode(', ', $action_logs));
				$logs[$clsISO->getUniqid()] = array(
					'profile_id' => $profile_id,
					'reg_date' => time(),
					'content' => $content
				);
			}			
			$update_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);			
			$more_information["billing_method"] = $billing_method;
			$more_information["bank_guarantee_id"] = $bank_guarantee_id;
			$update_field["more_information"] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
			$update_field["billing_method"] = $billing_method;
			if($clsBilling->updateOne($billing_id, $update_field)){
				$msg = '_success';
			}
		} else {
			$msg = "_invalid";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function calendar_open_sign_date(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', '_add');
	$cal_id = Input::post('cal_id');
	$sign_date = Input::post('sign_date');
	$sign_type = Input::post('sign_type', '_contract');
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	$smarty->assign('cal_id', $cal_id);
	$smarty->assign('sign_type', $sign_type);
	$smarty->assign('sign_date', sprintf('%sT%s', date('Y-m-d', $sign_date), date('H:i')));
	$project_id = $block_id = $billing_id = 0; $stock_code = "";
	$titlePage = "Thêm lịch ký giao dịch";
	if($tp == '_cancel'){
		$billing_id = Input::post('billing_id');
		$oBilling = $clsBilling->getOne($billing_id);
		$more_information = $clsISO->to_array_json($oBilling["more_information"]);
		$stock_code = $oBilling['stock_code'];
		$project_id = $oBilling['project_id'];
		$block_id = $more_information['block_id'];
		$titlePage = "Đổi lịch ký giao dịch";
		$lstBlock = $clsProperty->getOItems("_BLOCK",$project_id);
		$smarty->assign('lstBlock', $lstBlock);
	}
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('stock_code', $stock_code);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('block_id', $block_id);
	// Return
	$html = $core->build('calendar'.DS.'_ajax.sign_date.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function calendar_save_sign_date(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$deviceType,$dbconn;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsBillingMeta = new BillingMeta();
	###
	$msg = "_error";
	$tp = Input::post('tp', "_add");
	$billing_id = (int) Input::post('billing_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$stock_code = Input::post('stock_code');
	$date_type = Input::post('date_type', '_contract');
	$agree_date = Input::post('agree_date');
	$estimate_date = Input::post('estimate_date');
	$contract_date = Input::post('contract_date');
	if($tp == '_cancel'){
		if($billing_id > 0){
			$field = "`admin_id`,`logs`,`agree_date`,`contract_date`,`more_information`";
			$oBilling = $clsBilling->getOne($billing_id, $field);
			$admin_id = $oBilling['admin_id'];
			$old_agree_date = $oBilling['agree_date'];
			$old_contract_date = $oBilling['contract_date'];
			$logs = $oBilling['logs'];
			$more_information = $oBilling['more_information'];
			$logs = $clsISO->to_array_json($logs);
			$more_information = $clsISO->to_array_json($more_information);
			$is_changed = false; $update_field = array();
			if($date_type == '_contract'){
				$meta_value = $old_contract_date;
				$estimate_date = !empty($estimate_date) ? $clsISO->toTime($estimate_date) : 0;
				$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
				$update_field['estimate_date'] = $estimate_date;
				$update_field['contract_date'] = $contract_date;
				$content_log = sprintf('%s thay đổi ngày ký HĐMB từ %s tới %s', 
					$clsProfile->getFullName($profile_id, $oneProfile),
					$clsISO->convertTimeToText($old_contract_date, true), 
					$clsISO->convertTimeToText($contract_date, true)
				);
				if($contract_date > 0 && $old_contract_date > 0 
					&& date('dmY', $contract_date) != date('dmY', $old_contract_date)){
					$is_changed = true;
				}
			} else if($date_type == '_text'){
				$meta_value = $old_agree_date;
				$agree_date = !empty($agree_date) ? $clsISO->toTime($agree_date) : 0;
				$update_field['agree_date'] = $agree_date;
				$content_log = sprintf('%s thay đổi ngày ký VBTT từ %s tới %s', 
					$clsProfile->getFullName($profile_id, $oneProfile),
					$clsISO->convertTimeToText($old_agree_date, true), 
					$clsISO->convertTimeToText($agree_date, true)
				);
				if($agree_date > 0 && $old_agree_date > 0 
					&& date('dmY', $agree_date) != date('dmY', $old_agree_date)){
					$is_changed = true;
				}
			}
			$logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(),
				'profile_id' => $profile_id,
				'content' => $content_log
			);
			if($is_changed){
				$clsBillingMeta->insert(array(
					'billing_id' => $billing_id,
					'meta_key' => sprintf('%s_date_changed', $date_type),
					'meta_value' => $meta_value
				));
			}
			$update_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
			// $clsISO->print_pre($logs); die();
			if($clsBilling->updateOne($billing_id, $update_field)){
				$msg = "_success";
			}
		} else {
			$msg = "_empty";
		}
	} else if($tp == "_add"){
		if(!empty($stock_code)){
//			$dbconn->debug=true;
			$tmp = $clsBilling->getByCond("`is_trash`=0 AND `is_cancel`=0 AND `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."' AND TRIM(`stock_code`)='{$stock_code}' AND `project_id`='{$project_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.block_id\"))='{$block_id}'");
//			 $clsISO->print_pre($tmp); die();
			if(!empty($tmp)){
				$update_field = array();
				if($date_type == '_contract'){
					$estimate_date = !empty($estimate_date) ? $clsISO->toTime($estimate_date) : 0;
					$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
					$update_field['estimate_date'] = $estimate_date;
					$update_field['contract_date'] = $contract_date;
				} else if($date_type == '_text'){
					$agree_date = !empty($agree_date) ? $clsISO->toTime($agree_date) : 0;
					$update_field['agree_date'] = $agree_date;
				}
				if($clsBilling->updateOne($tmp[$clsBilling->pkey], $update_field)){
					$msg = "_success";
				}
			} else {
				$msg = "_exists";
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function sub_str_replace_first($search, $replace, $subject)	{
	$search = '/'.preg_quote($search, '/').'/';
	return preg_replace($search, $replace, $subject, 1);
}
function sub_mask_code($stock_code){
	global $core, $clsISO;
	return $stock_code;
	if($clsISO->checkContainer($stock_code,"-","")){
		$tmp = @explode('-', $stock_code);
		$code = $tmp[1];
		$code = preg_replace('/[0-9]/','X', $code);
		return sprintf('%s-%s', $tmp[0], $code);
	} else {
		$clsStock = new Stock();
		$field = "{$clsStock->pkey},floor";
		$oStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", $field);
		if(!empty($oStock)){
			$code = $oStock['code'];
			$floor = $oStock['floor'];
			return sub_str_replace_first($floor, 'XX', $stock_code);
		}
	}
	return $stock_code;
}
function calendar_load_billing_calendar(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$current_now = time();
	$start = Input::get('start',"");
	$end = Input::get('end',"");
	$start = strtotime($start);
	$start = strtotime(date("d-m-Y",$start));
	$end = strtotime($end);
	$end = strtotime(date("d-m-Y",$end));
	$date_type = Input::get('date_type', "_all");
	$openFrom = Input::get('openFrom', "_billing");
	$billing_type = (int) Input::get('billing_type', 0);
	$project_id = (int) Input::get('project_id', 0);
	$sql_string = "`is_trash`=0 AND `is_cancel`=0";
	if($billing_type > 0) $sql_string.= " AND `billing_type`='{$billing_type}'";
	if($project_id > 0) $sql_string.= " AND `project_id`='{$project_id}'";
	#
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	#get by role
	$arr_billing_type = array();
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		// Next
	} else if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		if($clsISO->_DEV()){
			$block_permiss = [];
			$arr_cache_block = $clsProperty->getArraySearchByKey("_BLOCK");
			foreach($arr_cache_block as $key => $val) {
				$more_block = $val["more_information"];
				$project_admins = $core->get_field($more_block, "project_admins", []);
				if($clsISO->checkItemInArray($profile_id,$project_admins) 
					|| ($profile_id == _PROFILE_MY_LINH_ADMIN_ID && $val["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE)){
					$block_permiss[] = $val[$clsProperty->pkey];
				}
			}
			$sql_string.= " AND JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',$block_permiss).")";
		}else{
			$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
			if(!empty($permiss_billing)) {
				foreach ($permiss_billing as $k => $val) {
					$arr_billing_type = array_merge($arr_billing_type, $val);
				}
			}
			if(!empty($arr_billing_type)) {
				$sql_string.= " AND `billing_type` IN (".implode(',',$arr_billing_type).")";
			}else{
				$sql_string.= " AND `billing_type` = '0'";
			}
		}
	} else {
		$sql_string.= " AND `billing_type` = '0'";
	}
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		// $sql_string.= " AND `admin_id`='{$profile_id}'";
	}
	###
	$results = array();
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$date = date('d/m/Y',$i);
		$total_billings = $total_registered = $total_unregisted = 0;
		$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`
		,`agree_date`,`agree_status_id`,`billing_source_id`,`billing_type`,`admin_id`";
		$cond = " AND ((`contract_date`>0 AND FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$date}') OR (`agree_date`>0 AND FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$date}'))";
		if($date_type == '_contract'){
			$cond = " AND (`contract_date`>0 AND FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$date}')";
		} else if($date_type == '_text'){
			$cond = " and (`agree_date`>0 AND FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$date}')";
		}
		// $dbconn->debug = true;
		if($clsISO->_DEV()){
//			$dbconn->debug=true;
		}
		$list_billings = $clsBilling->getAll($sql_string.$cond, $field);
		if($clsISO->_DEV()){
//			$clsISO->print_pre($list_billings);die;
		}
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			$arr_admins = array(); 
			// AND `is_active`='1'  AND `status_id` <> '"._STATUS_STAFF_OFF_ID."'
			$list_admins = $clsProfile->getAll("`is_trash`='0' AND `role_id`='"._ROLE_STAFF_ADMIN."'","{$clsProfile->pkey},`full_name`,`avatar`");
			if(!empty($list_admins)){
				foreach($list_admins as $key => $val){
					$admin_id = $val[$clsProfile->pkey];
					$arr_admins[$admin_id] = $val;
				}
			}
			// $clsISO->print_pre($arr_admins); die();
			foreach($list_billings as $key => $val){
				$admin_id = (int) $val['admin_id'];
				if($admin_id == 0) $admin_id = _PROFILE_ADMIN_ID;
				$billing_type = $val['billing_type'];
				$admin = $arr_admins[$admin_id];
				$html_admin = '<span class=\'avatar d-inline-block me-1 avatar-xxs pull-up\' data-url=\'/index.php?mod=home&act=load_profile_popover&user_id='.$admin["profile_id"].'\' data-toggle=\'webui-popover\' data-trigger=\'hover\' data-width=\'300\' data-target=\'webuiPopover'.$admin["profile_id"].'\'>
					<img class=\'mr-2 rounded-pill\' src=\''. $clsProfile->getAvatar($admin['profile_id'], $admin).'\' >
				</span>';
				$stock_code = $val['stock_code'];
				$agree_date = $val['agree_date'];
				$contract_date = $val['contract_date'];
				$estimate_date = $val['estimate_date'];
				$billing_source_id = (int) $val['billing_source_id'];
				$agree_status_id = (int) $val['agree_status_id'];
				$contract_status_id = (int) $val['contract_status_id'];
				if($date_type == '_all'){
					if(date('d/m/Y',$contract_date) == $date){
						if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
							$status_sign = 1;
							$total_registered += 1;
							$tooltip = "Đã ký HĐMB";
							$cls = "bg-blue text-white item_calendar signed";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s', sub_mask_code($stock_code));
							}
						} else {
							$status_sign = 0;
							$total_unregisted += 1;
							$tooltip = "Chưa ký HĐMB";
							$cls = "bg-yellow text-main item_calendar unsigned";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s %s', date('H:i', $contract_date), sub_mask_code($stock_code));
							} else {
								$stock_code = sprintf('%s %s', date('H:i', $contract_date), sub_mask_code($stock_code));
							}
						}
						$results[] = array(
							'flag' => 1,
							'cls' => $cls,
							'tooltip' => $tooltip,
							'sign_type' => '_contract',
							'status_sign' => $status_sign,
							'total_registered' => $total_registered,
							'total_unregisted' => $total_unregisted,
							'billing_id' => $val[$clsBilling->pkey],
							'title' => $html_admin.$stock_code,
							'date_f' => date('Y-m-d', $i),
							'start' => date('Y-m-d H:i:s',$i),
							'sign_date' => $i
						);
					}
					if($agree_date > 0 && date('d/m/Y',$agree_date) == $date){
						if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
							$status_sign = 1;
							$total_registered += 1;
							$cls = "bg-success text-white item_calendar signed";
							$tooltip = "Đã ký VBTT";
						} else {
							$status_sign = 0;
							$total_unregisted += 1;
							$cls = "bg-danger text-white item_calendar unsigned";
							$tooltip = "Ngày ký VBTT";
						}
						$results[] = array(
							'flag' => 1,
							'cls' => $cls,
							'sign_type' => '_text',
							'tooltip' => $tooltip,
							'status_sign' => $status_sign,
							'billing_id' => $val[$clsBilling->pkey],
							'title' => $html_admin.sub_mask_code($stock_code),
							'date_f' => date('Y-m-d', $i),
							'start' => date('Y-m-d H:i:s', $i),
							'sign_date' => $i
						);
					}
				} else if($date_type == '_contract'){
					if(date('d/m/Y',$contract_date) == $date){
						if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
							$status_sign = 1;
							$total_registered += 1;
							$tooltip = "Đã ký HĐMB";
							$cls = "bg-blue text-white item_calendar signed";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s', sub_mask_code($val['stock_code']));
							}
						} else {
							$status_sign = 0;
							$total_unregisted += 1;
							$tooltip = "Chưa ký HĐMB";
							$cls = "bg-yellow text-main item_calendar unsigned";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s %s',date('H:i', $contract_date), sub_mask_code($stock_code));
							} else {
								$stock_code = sprintf('%s %s', date('H:i', $contract_date), sub_mask_code($stock_code));
							}
						}
						$results[] = array(
							'flag' => 1,
							'cls' => $cls,
							'tooltip' => $tooltip,
							'sign_type' => '_contract',
							'status_sign' => $status_sign,
							'total_registered' => $total_registered,
							'total_unregisted' => $total_unregisted,
							'billing_id' => $val[$clsBilling->pkey],
							'title' => $html_admin.$stock_code,
							'date_f' => date('Y-m-d',$i),
							'start' => date('Y-m-d H:i:s',$i),
							'sign_date' => $i
						);
					}
				} else if($date_type == '_text'){
					if($agree_date > 0 && date('d/m/Y',$agree_date) == $date){
						if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
							$status_sign = 1;
							$total_registered += 1;
							$cls = "bg-success text-white item_calendar signed ";
							$tooltip = "Đã ký VBTT";
						} else {
							$status_sign = 0;
							$total_unregisted += 1;
							$cls = "bg-danger text-white item_calendar unsigned ";
							$tooltip = "Ngày ký VBTT";
						}
						$results[] = array(
							'flag' => 1,
							'cls' => $cls,
							'tooltip' => $tooltip,
							'sign_type' => '_text',
							'status_sign' => $status_sign,
							'total_registered' => $total_registered,
							'total_unregisted' => $total_unregisted,
							'billing_id' => $val[$clsBilling->pkey],
							'title' => $html_admin.sub_mask_code($stock_code),
							'date_f' => date('Y-m-d', $i),
							'start' => date('Y-m-d H:i:s',$i),
							'sign_date' => $i
						);
					}
				}
			}
		}
	}
	// Return
	echo json_encode($results);
	die();
}
function calendar_load_sidebar(){
	global $clsISO,$assign_list,$core,$oneProfile;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProperty  = new Property ();
	#
	$current_now = time();
	$today = date("d/m/Y");
	$start = (int) Input::post('start',0);
	$end = (int) Input::post('end',0);
	$date_type = Input::post('date_type', "_all");
	$billing_type = (int) Input::post('billing_type', 0);
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	#
	$arr_date = [];
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$arr_date[] = date('d/m/Y',$i);
	}
	$cond = " AND ((`contract_date` BETWEEN ".$start." AND ".$end.") OR (`agree_date` BETWEEN ".$start." AND ".$end."))";
	if($date_type == '_contract'){
		$cond = " and ((`contract_date` BETWEEN ".$start." AND ".$end.")) ";
	} else if($date_type == '_text'){
		$cond = " and (`agree_date` BETWEEN ".$start." AND ".$end.")";
	}
	$sql_string = "`is_trash`=0 and `is_cancel`=0";	
	$lstBillingType = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	$arr_billing_type = $arr_permiss = array();
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$arr_billing_type = array_values($lstBillingType);
	}else if(in_array($oneProfile["role_id"], array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
		if(!empty($permiss_billing)) {
			foreach ($permiss_billing as $k => $val) {
				$arr_permiss = array_merge($arr_permiss,$val);
			}
		}
		if(!empty($arr_permiss)) {
			$sql_string .= " AND `billing_type` IN (".implode(',',$arr_permiss).")";
		}else{
			$sql_string .= " AND `billing_type` = '0'";
		}
		foreach($lstBillingType as $key => $val) {
			if($clsISO->checkItemInArray($val["property_id"],$arr_permiss)) {
				$arr_billing_type[] = $val;
			}			
		}
	}
	if($billing_type > 0) {
		$arr_billing_type = [$lstBillingType[$billing_type]];
	}
//	$clsISO->print_pre($arr_billing_type);die;
	//$arr_billing_type = $lstBillingType;//demo
	if(!empty($arr_billing_type)) {
		if($date_type == '_contract'){
			$field = " COUNT(CASE WHEN `contract_date` BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_contract ";
		} else if($date_type == '_text'){
			$field = " COUNT(CASE WHEN agree_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_agree_date ";
		}else{
			$field = " COUNT(CASE WHEN agree_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_agree_date, COUNT(CASE WHEN contract_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_contract ";
		}
		foreach($arr_billing_type as $key => $val) {
			$arrTotal = $clsBilling->getByCond("`is_trash`=0 and `is_cancel`=0 AND `billing_type` = '".$val["property_id"]."'".$cond,$field);
			$arr_billing_type[$key]['lst_number_billing'] = $arrTotal;
			unset($arrTotal);
		}			
	}
	$assign_list['total_today'] = $total_today;
	$assign_list['lst_admin'] = $lst_admin;
	$assign_list['arr_billing_type'] = $arr_billing_type;
	$assign_list['lstBillingType'] = $lstBillingType;
	$assign_list['date_type'] = $date_type;
	$html = $core->build('calendar'.DS.'_ajax.menu_calendar.tpl');
	echo json_encode(["html"=>$html]);die;
}
function calendar_load_chart(){
	global $clsISO,$assign_list,$core,$oneProfile,$profile_id;
	$uid = $clsISO->getUniqid();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProperty  = new Property ();
	#
	$current_now = time();
	$today = date("d/m/Y");
	$start = (int) Input::post('start',0);
	$end = (int) Input::post('end',0);
	$date_type = Input::post('date_type', "_all");
	$billing_type = (int) Input::post('billing_type', 0);
	#
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	#
	$arr_date = [];
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$arr_date[] = date('d/m/Y',$i);
	}
	// var_dump($arr_date);die;
	$cond = " and ((`contract_date` BETWEEN ".$start." AND ".$end.") 
			or (agree_date > 0 AND `agree_date` BETWEEN ".$start." AND ".$end.") )";
	if($date_type == '_contract'){
		$cond = " and ((`contract_date` BETWEEN ".$start." AND ".$end."))  ";
	} else if($date_type == '_text'){
		$cond = " and (agree_date > 0 AND `agree_date` BETWEEN ".$start." AND ".$end.")";
	}
	if($billing_type > 0) {
		$cond .= " AND `billing_type` = '".$billing_type."'";
	}
	$sql_string = "`is_trash`=0 and `is_cancel`=0";	
	$lstBillingType = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	$arr_billing_type = $arr_permiss = array();
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$arr_billing_type = $lstBillingType;
	}else if(in_array($role_id, array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
		if(!empty($permiss_billing)) {
			foreach ($permiss_billing as $k => $val) {
				$arr_permiss = array_merge($arr_permiss,$val);
			}
		}
		if(!empty($arr_permiss)) {
			$sql_string .= " AND `billing_type` IN (".implode(',',$arr_permiss).")";
		}else{
			$sql_string .= " AND `billing_type` = '0'";
		}
		foreach($lstBillingType as $key => $val) {
			if($clsISO->checkItemInArray($val["property_id"],$arr_permiss)) {
				$arr_billing_type[] = $val;
			}			
		}
	}
	//$arr_billing_type = $lstBillingType;//demo
	$data_contract = $data_estimate = $data_agree = [];
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$date = date('d/m/Y',$i);
		$total_billings = $total_registered = $total_unregisted = 0;
		$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`,`agree_date`,`billing_source_id`";
		$total_estimate = $total_contract = $total_agree = 0;
		$list_billings = $clsBilling->getAll($sql_string.$cond, $field);
		if(!empty($list_billings)){
			foreach($list_billings as $key => $val){
				$stock_code = $val['stock_code'];
				$agree_date = $val['agree_date'];
				$contract_date = $val['contract_date'];
				$estimate_date = $val['estimate_date'];
				$billing_source_id = $val['billing_source_id'];
				$contract_status_id = $val['contract_status_id'];
				if($date_type == '_all'){
					if(date('d/m/Y',$estimate_date) == $date){
						++$total_estimate;
					}
					if(date('d/m/Y',$contract_date) == $date){
						++$total_contract;
					}
					if(date('d/m/Y',$agree_date) == $date){
						++$total_estimate;
					}
				} else if($date_type == '_contract'){
					if(date('d/m/Y',$estimate_date) == $date){
						++$total_estimate;
					}
					if(date('d/m/Y',$contract_date) == $date){
						++$total_contract;
					}
				} else if($date_type == '_text'){
					if(date('d/m/Y',$agree_date) == $date){
						++$total_agree;
					}
				}
			}
		}
		$data_contract[] = ["label"	=>	$date, "y"		=>	$total_contract];
		$data_estimate[] = ["label"	=>	$date, "y"		=>	$total_estimate];
		$data_agree[] = ["label"	=>	$date, "y"		=>	$total_agree];
	}
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	if($date_type == '_all'){
		$data = [
			[
				"name" => "Ký HĐMB",
				"type"    => "spline",
				"indexLabel" => "{y}",
				"showInLegend"    => true,
				"dataPoints" => $data_contract
			],
			[
				"name" => "Ký VBTT",
				"type"    => "spline",
				"indexLabel" => "{y}",
				"showInLegend"    => true,
				"dataPoints" => $data_agree
			]
		];
	} else if($date_type == '_contract'){
		$data = [
			[
				"name" => "Ký HĐMB",
				"type"    => "spline",
				"indexLabel" => "{y}",
				"showInLegend"    => true,
				"dataPoints" => $data_contract
			]
		];
	} else if($date_type == '_text'){
		$data = [
			[
				"name" => "Ký VBTT",
				"type"    => "spline",
				"indexLabel" => "{y}",
				"showInLegend"    => true,
				"dataPoints" => $data_agree
			],
		];
	}
	$barChartData['data'] =$data;
//	$clsISO->print_pre($barChartData);die;
	echo json_encode(array(
		'uid' => $uid, 
		'barChartData' => $barChartData,
		'callback'	=>	'  var chart = new CanvasJS.Chart("chart_calendar", {
			title: {
				text: ""
			},
			axisX: {
				title: "",
				interval: 1,
				labelFontSize: 11,
				includeZero: true
			},
			axisY: {
				minimum: 0,
				labelFormatter: function(e) {
				console.log(e)
					return "";
				}
			},
			toolTip:{
				contentFormatter: function ( e ) {
					return e.entries[0].dataPoint.type+": "+e.entries[0].dataPoint.label_tooltip;  
				}  
			},
			data: '.json_encode($data).'
		});
    chart.render();'	
	),JSON_UNESCAPED_UNICODE);
}
function calendar_load_chart_billing_type(){
	global $clsISO,$assign_list,$core,$oneProfile,$profile_id;
	$uid = $clsISO->getUniqid();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProperty  = new Property ();
	#
	$current_now = time();
	$today = date("d/m/Y");
	$start = (int) Input::post('start',0);
	$end = (int) Input::post('end',0);
	$date_type = Input::post('date_type', "_all");
	$billing_type = (int) Input::post('billing_type', 0);
	#
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	#
	$arr_date = [];
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$arr_date[] = date('d/m/Y',$i);
	}
	$cond = " AND ((`contract_date` BETWEEN ".$start." AND ".$end.") OR (`agree_date` BETWEEN ".$start." AND ".$end.") )";
	if($date_type == '_contract'){
		$cond = " and ((`contract_date` BETWEEN ".$start." AND ".$end."))  ";
	} else if($date_type == '_text'){
		$cond = " and (`agree_date` BETWEEN ".$start." AND ".$end.")";
	}
	$sql_string = "`is_trash`=0 AND `is_cancel`=0";	
	$arr_billing_type = $arr_permiss = array();
	$lstBillingType = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$arr_billing_type = $lstBillingType;
	}else if(in_array($role_id, array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
		if(!empty($permiss_billing)) {
			foreach ($permiss_billing as $k => $val) {
				$arr_permiss = array_merge($arr_permiss,$val);
			}
		}
		foreach($lstBillingType as $key => $val) {
			if($clsISO->checkItemInArray($val["property_id"],$arr_permiss)) {
				$arr_billing_type[] = $val;
			}			
		}
	}
	if($billing_type > 0) {
		$cond .= " AND `billing_type` = '".$billing_type."'";
		$arr_billing_type = [$lstBillingType[$billing_type]];
	}
	//$arr_billing_type = $lstBillingType;//demo	
	$dataPoints = array();
	$number_billing_default = array("total_agree_date"=>0,"total_estimate"=>0);
	if(!empty($arr_billing_type)) {
		if($date_type == '_contract'){
			$field = " COUNT(CASE WHEN contract_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_contract ";
		} else if($date_type == '_text'){
			$field = " COUNT(CASE WHEN agree_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_agree_date ";
		}else{
			$field = " COUNT(CASE WHEN agree_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_agree_date, COUNT(CASE WHEN contract_date BETWEEN ".$start." AND ".$end." THEN 1 END) AS total_contract ";
		}
		foreach($arr_billing_type as $key => $val) {
			$arrTotal = $clsBilling->getByCond("`is_trash`=0 and `is_cancel`=0 AND `billing_type` = '".$val["property_id"]."'".$cond,$field);
			$arr_billing_type[$key]['lst_number_billing'] = $arrTotal;
			$arr_value_billing = array_values($arrTotal);
			$dataPoints[] = [
				'label'	=> $val["title"],
				'y'		=> array_sum($arr_value_billing)
			];			
			unset($arrTotal,$arr_value_billing);
		}			
	}
	$data = [
		[
			"type" 					=>  "pie",
			"showInLegend" 			=> "true",
			"legendText"			=> "{label}",
			"indexLabelFontSize"	=> 16,
			"indexLabel"			=> 	"{label} - {y}",
			"dataPoints"			=> 	$dataPoints
		]
	];
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['data'] = $data;
	// $clsISO->print_pre($barChartData);die;
	echo json_encode(array(
		'uid' => $uid, 
		'barChartData' => $barChartData,
	));
}
function calendar_load_chart_billing(){
	global $clsISO,$assign_list,$core,$oneProfile;
	$uid = $clsISO->getUniqid();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProperty  = new Property ();
	#
	$current_now = time();
	$today = date("d/m/Y");
	$start = (int) Input::post('start',0);
	$end = (int) Input::post('end',0);
	$date_type = Input::post('date_type', "_all");
	$billing_type = (int) Input::post('billing_type', 0);
	#
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	#
	$arr_date = [];
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$arr_date[] = date('d/m/Y',$i);
	}
	$cond = " and ((`contract_date` BETWEEN ".$start." AND ".$end.")  
			or (`agree_date` BETWEEN ".$start." AND ".$end.") )";
	if($date_type == '_contract'){
		$cond = " and ((`contract_date` BETWEEN ".$start." AND ".$end."))  ";
	} else if($date_type == '_text'){
		$cond = " and (`agree_date` BETWEEN ".$start." AND ".$end.")";
	}
	$sql_string = "`is_trash`=0 and `is_cancel`=0";	
	$lstBillingType = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	$arr_billing_type = $arr_permiss = array();
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$arr_billing_type = $lstBillingType;
	}else if(in_array($role_id, array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$permiss_billing = $core->get_field($more_information, "permiss_billing", []);
		if(!empty($permiss_billing)) {
			foreach ($permiss_billing as $k => $val) {
				$arr_permiss = array_merge($arr_permiss,$val);
			}
		}
		foreach($lstBillingType as $key => $val) {
			if($clsISO->checkItemInArray($val["property_id"], $arr_permiss)) {
				$arr_billing_type[] = $val;
			}			
		}
	}
	if($billing_type > 0) {
		$cond .= " AND `billing_type` = '".$billing_type."'";
		$arr_billing_type = [$lstBillingType[$billing_type]];
	}
	$time_type = Input::post('time_type', 'THIS_WEEK');
	$time_range = $clsISO->getRangeTime($time_type);
	$start_date  = $time_range['start_date'];
	$due_date  = $time_range['due_date'];
	//$arr_billing_type = $lstBillingType;//demo	
	$dataPoints = array();
	if(in_array($time_type, ['THIS_WEEK','PREV_WEEK','THIS_MONTH','PREV_MONTH'])){
		for($ii=$start_date; $ii<= $due_date; $ii = strtotime("+1 day", $ii)){	
			if($date_type == '_contract'){
				$field = " COUNT(CASE WHEN FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='".date("d/m/Y",$ii)."' THEN 1 END) AS total_contract ";
			} else if($date_type == '_text'){
				$field = " COUNT(CASE WHEN FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='".date("d/m/Y",$ii)."' THEN 1 END) AS total_agree_date ";
			}else{
				$field = " COUNT(CASE WHEN FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='".date("d/m/Y",$ii)."' THEN 1 END) AS total_agree_date, COUNT(CASE WHEN FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='".date("d/m/Y",$ii)."' THEN 1 END) AS total_contract ";
			}
			$arrTotal = $clsBilling->getByCond("`is_trash`=0 and `is_cancel`=0".$cond,$field);
			// $clsISO->print_pre($arrTotal);die;
			$arr_billing_type[$key]['lst_number_billing'] = $arrTotal;
			$arr_value_billing = array_values($arrTotal);
			if(in_array($time_type, ['THIS_WEEK','PREV_WEEK'])){
				$label = $clsISO->getDayOfWeekAcronym($ii);
			} else if(in_array($time_type, ['THIS_MONTH','PREV_MONTH'])){
				$label = date('d', $ii);
			}
			$dataPoints[] = array(
				'label'	=> $label,
				'y'	=> array_sum($arr_value_billing)
			);
		}
	} else if(in_array($time_type, ['THIS_YEAR','PREV_YEAR'])){
		for($ii=1; $ii < 12; $ii++){
			$y = ($time_type=='THIS_YEAR') ? date('Y') : date('Y') - 1;
			$my = sprintf('%s/%s', $clsISO->parseNumber($ii), $y);
			if($date_type == '_contract'){
				$field = " COUNT(CASE WHEN FROM_UNIXTIME(`contract_date`,'%m/%Y')='".$my."' THEN 1 END) AS total_contract ";
			} else if($date_type == '_text'){
				$field = " COUNT(CASE WHEN FROM_UNIXTIME(`agree_date`,'%m/%Y')='".$my."' THEN 1 END) AS total_agree_date ";
			}else{
				$field = " COUNT(CASE WHEN FROM_UNIXTIME(`agree_date`,'%m/%Y')='".$my."' THEN 1 END) AS total_agree_date, COUNT(CASE WHEN FROM_UNIXTIME(`contract_date`,'%m/%Y')='".$my."' THEN 1 END) AS total_contract ";
			}
			$arrTotal = $clsBilling->getByCond("`is_trash`=0 and `is_cancel`=0 ".$cond,$field);
			$arr_billing_type[$key]['lst_number_billing'] = $arrTotal;
			$arr_value_billing = array_values($arrTotal);
			$dataPoints[] = array(
				'label'	=> sprintf('T%s', $ii),
				'y'	=> array_sum($arr_value_billing)
			);
		}
	}	
	$data = [
		[
			"type" 					=>  "spline",
			"showInLegend" 			=> "true",
			"legendText"			=> "{label}",
			"indexLabelFontSize"	=> 16,
			"indexLabel"			=> 	"{y}",
			"dataPoints"			=> 	$dataPoints
		]
	];
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['data'] = $data;
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	));
}
function calendar_load_rescheduling_reason(){
    global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
	###
	$clsBilling = new Billing();
	$clsProfile = new Profile();
    $billing_id = Input::request('billing_id',0);
	$field = "contract_date,estimate_date,otp_date,agree_date,contract_status_id,more_information";
	$oBilling = $clsBilling->getOne($billing_id, $field);
	$more_information = $clsISO->to_array_json($oBilling["more_information"]);
	$otp_date = $oBilling['otp_date'];
	$agree_date = $oBilling['agree_date'];
	$contract_date = $oBilling['contract_date'];
	$estimate_date = $oBilling['estimate_date'];
	$contract_status_id = $oBilling['contract_status_id'];
	$rescheduling_reason = !empty($more_information['rescheduling_reason']) ? $more_information['rescheduling_reason'] : "";
	###
	$html = '<div class="card no-shadow">'; 
	$html .= '<div class="card-header border-bottom mb-1"><h5 class="text-main mb-0">Ghi chú</h5></div>
				<div class="card-body">';
	if(!empty($rescheduling_reason)) {
		foreach ($rescheduling_reason as $key => $val ) {
			$html .= '<div class="form-group mb-2 border-bottom">
				<div class="fs-16">'.$val["note"].'</div>
				<div class="d-flex align-items-center gap-1 flex-wrap">
					<div class="d-flex align-items-start gap-1 fs-11">
						<i class="bx bx-user" ></i>
						'.$clsProfile->getFullName($val['user_id']).'
					</div>
					<div class="d-flex align-items-start gap-1 fs-11">
						<i class="material-icons-outlined fs-13 no-translate">more_time</i>
						'.$clsISO->formatDate($val['reg_date'],4).'
					</div>
				</div>
			</div>';
		}
	}
	$html .='</div></div>';
    // Return
    echo  $html; die();
}
function calendar_load_calendar_today(){
	global $clsISO,$assign_list,$core,$oneProfile,$smarty,$dbconn;
	$clsProfile  = new Profile();
	$clsCustomer = new Customer();
	$clsProject = new Project();
	$clsProperty  = new Property ();
	$clsBilling = new Billing();
	$clsBillingMeta = new BillingMeta();
	#
	$date = date('d/m/Y');
	$uid = $clsISO->getUniqid();
	$sign_date = Input::post("sign_date", date('d/m/Y'));
	$call_from = Input::request('call_from', '_sign_page');
	$total_billings = $total_registered = $total_unregisted = 0;	
	$lstBillingType = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	#
	$role_id = $oneProfile['role_id'];
	$more_information = $oneProfile['more_information'];
	$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`
	,`agree_date`,`billing_source_id`,`billing_type`,`agree_status_id`,`more_information`,`admin_id`,`staff_id`";
	$cond = "`is_trash`=0 AND `is_cancel`=0";	
	$sql_query = "SELECT {$field},'_new' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond}";
	$sql_union_query = "SELECT {$field},'_old' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond}";
	if(!empty($sign_date)){
		$sql_query.= " AND ((FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$sign_date}') OR (FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$sign_date}'))";
		$sql_union_query.= " AND EXISTS (SELECT 1 FROM {$clsBillingMeta->tbl} WHERE FROM_UNIXTIME(`meta_value`, '%d/%m/%Y')='{$sign_date}' AND {$clsBilling->tbl}.`billing_id`={$clsBillingMeta->tbl}.`billing_id`)";
	}
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$permiss_billing = $core->get_field($more_information, 'permiss_billing', []);
		if(!empty($permiss_billing)){
			$arr_billing_types = array();
			$arr_projects = array_keys($permiss_billing);
			foreach($permiss_billing as $key => $val){
				$arr_billing_types = array_merge($arr_billing_types, $val);
			}
			if(!empty($arr_projects)){
				$sql_query.= " AND `project_id` IN (".implode(',',$arr_projects).")";
			}
			if(!empty($arr_billing_types)){
				$arr_billing_types = array_unique($arr_billing_types);
				$sql_query.= " AND `billing_type` IN (".implode(',',$arr_billing_types).")";
			}
		}
	}
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		// $sql_query.= " AND `admin_id`='{$profile_id}'";
	}
	$is_edit = $total = 0;
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$is_edit = 1;
	}
	$data = $ar_cache_billing_type = array();
	$list_billings = $dbconn->getAll("{$sql_query} UNION ALL {$sql_union_query}");
	if(!empty($list_billings)){
		$arr_admins = array();
		$field = "{$clsProfile->pkey},`full_name`,`avatar`,`more_information`";
		$list_admins = $clsProfile->getAll("`is_trash`='0' AND `role_id`='"._ROLE_STAFF_ADMIN."'", $field);
		if(!empty($list_admins)){
			foreach($list_admins as $key => $val){
				$admin_id = $val[$clsProfile->pkey];
				$arr_admins[$admin_id] = $val;
			}
		}
		$arr_cache_profile = [];
		// $clsISO->print_pre($arr_admins); die();
		foreach($list_billings as $key => $val) {
			$admin_id = (int) $val['admin_id'];
			if($admin_id == 0) $admin_id = _PROFILE_ADMIN_ID;
			$sign_type = $val['sign_type'];
			$billing_type = (int) $val['billing_type'];
			$agree_date = (int) $val['agree_date'];
			$contract_date = (int) $val['contract_date'];
			$agree_status_id = (int) $val['agree_status_id'];
			$contract_status_id = (int) $val['contract_status_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$text_type = array(); $time = $status = "";
			$is_note = !empty($more_information['rescheduling_reason']) ? 1 : 0;
			$list_billings[$key]["is_note"] = $is_note;
			if($contract_date > 0) { // && date('d/m/Y', $contract_date) == $date
				$text_type[] = 'HĐMB';
				if(!empty($estimate_date)){
					$time = $clsISO->formatDate($estimate_date,4);
				}
				if(!empty($contract_date)){
					$time = $clsISO->formatDate($contract_date,4);
				}
				if($sign_type == '_new'){
					$status = "<span class=\"text-danger\">Chưa ký</span>";
					if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
						$status = "<span class=\"text-success\">Đã ký</span>";
					}
				} else {
					$status = "<span class=\"text-muted\">Hủy ký</span>";
				}
			} else if($agree_date > 0){ //  && date('d/m/Y', $agree_date) == $date
				if($sign_type == '_new'){
					$status = "<span class=\"text-danger\">Chưa ký</span>";
					if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
						$status = "<span class=\"text-success\">Đã ký</span>";
					}
				} else {
					$status = "<span class=\"text-muted\">Hủy ký</span>";
				}
				$text_type[] = 'VBTT</span>';
				$time = $clsISO->formatDate($agree_date,4);
			}
			if(!isset($arr_cache_profile[$val["staff_id"]])) {
				$_oProfile = $clsProfile->getOne($val["staff_id"],$clsProfile->pkey.',full_name,department_id');
				if(!isset($arr_cache_dep[$_oProfile["department_id"]])) {
					$arr_cache_dep[$val["staff_id"]] = $clsProperty->getTitle($_oProfile['department_id']);
				}
				$_oProfile["depart_name"] = $arr_cache_dep[$val["staff_id"]];
				$arr_cache_profile[$val["staff_id"]] = $_oProfile;
				unset($_oProfile);				
			}
			$list_billings[$key]["staff"] = $arr_cache_profile[$val["staff_id"]];
			$list_billings[$key]["time"] = $time;
			$list_billings[$key]["status"] = $status;
			$admin = $arr_admins[$admin_id];
			$list_billings[$key]["admin"] = $admin;
			$list_billings[$key]["text_type"] = implode(',', $text_type);
			++$total;
		}
	}
//	$clsISO->print_pre($list_billings);die;
	$smarty->assign("uid",$uid);
	$smarty->assign("is_edit",$is_edit);
	$smarty->assign("call_from",$call_from);
	$smarty->assign("list_billings",$list_billings);
	// Return
	$html = $core->build("calendar".DS."_ajax.load_calendar_today.tpl");
	echo json_encode(array("html" => $html,"total" => $total));die;
}
function calendar_load_total_calendar(){
	global $clsISO,$assign_list,$core,$oneProfile,$smarty;
	$uid = $clsISO->getUniqid();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProperty  = new Property ();
	$clsProfile  = new Profile();
	$date_type = Input::post('date_type', "_all");
	$time_type = Input::post('time_type', 'THIS_WEEK');
	$time_range = $clsISO->getRangeTime($time_type);
	$start = $time_range['start_date'];
	$end = $time_range['due_date'];
	##
	$cond = "`is_trash`=0 and `is_cancel`=0";
	if($date_type == '_contract'){
		$cond .= " and ((`contract_date` BETWEEN ".$start." AND ".$end."))  ";
	} else if($date_type == '_text'){
		$cond .= " and (`agree_date` > 0 AND `agree_date` BETWEEN ".$start." AND ".$end.")";
	}else{		
		$cond .= " and ((`contract_date` BETWEEN ".$start." AND ".$end.") 
			or (`agree_date` > 0 AND `agree_date` BETWEEN ".$start." AND ".$end.") )";
	}
	//$arr_billing_type = $lstBillingType;//demo	
	$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`
	,`agree_date`,`billing_source_id`,`billing_type`,`admin_id`";
	$list_billings = $clsBilling->getAll($cond,$field);
	// $clsISO->print_pre($list_billings);die;
	// echo $cond;die;
	$lstBillingType = $clsProperty->getArraySearchBykey("_BILLING_TYPE");
	$arr_billing_type = $arr_permiss = array();
	$arr_data_admin = [];
	if(!empty($list_billings)){
		foreach($list_billings as $key => $val){
			$lstAdmin = $clsProfile->getAll("`is_trash`='0' AND `role_id`='"._ROLE_STAFF_ADMIN."' AND `status_id` <> '"._STATUS_STAFF_OFF_ID."' AND  JSON_SEARCH(JSON_UNQUOTE(JSON_EXTRACT(more_information,'$.permiss_billing')),'one','".$val['billing_type']."') IS NOT NULL",$clsProfile->pkey.',full_name,avatar');
			$arr_admin = [];
			if(!empty($lstAdmin)){
				foreach ($lstAdmin as $k_ad => $v_ad) {
					$arr_admin[] = $v_ad["profile_id"];
				}
			}
			$admin_id = ($clsISO->checkItemInArray($val['admin_id'],$arr_admin)) ? $val['admin_id'] : $arr_admin[0];
			if(!isset($arr_data_admin[$admin_id])) {
				$arr_data_admin[$admin_id] = [
					'total_contract' =>	0,
					'total_agree'	=>	0,
				];
			}
			$stock_code = $val['stock_code'];
			$agree_date = $val['agree_date'];
			$contract_date = $val['contract_date'];
			$estimate_date = $val['estimate_date'];
			$billing_source_id = $val['billing_source_id'];
			$contract_status_id = $val['contract_status_id'];
			if($date_type == '_all'){
				if($val['estimate_date'] > 0 || $val['contract_date'] > 0){
					$arr_data_admin[$admin_id]['total_contract'] += 1;
				}
				if($val['agree_date'] > 0){
					$arr_data_admin[$admin_id]['total_agree'] += 1;
				}
			} else if($date_type == '_contract'){
				if($val['estimate_date'] > 0 || $val['contract_date'] > 0){
					$arr_data_admin[$admin_id]['total_contract'] += 1;
				}
			} else if($date_type == '_text'){
				if($val['agree_date'] > 0){
					$arr_data_admin[$admin_id]['total_agree'] += 1;
				}
			}
		}
	}
	foreach ($arr_data_admin as $key => $val) {
		$arr_data_admin[$key]["admin_name"] =  $clsProfile->getFullName($key);
	}
	$smarty->assign("arr_data_admin",$arr_data_admin);
	// Return
	$html = $core->build("calendar".DS."_ajax.load_total_calendar.tpl");
	echo json_encode(array("html" => $html));die;
}