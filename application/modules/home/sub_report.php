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
function report_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProperty = new Property();
	###
	$Current_Now = time();
	$Current_Month = date('n');
	$Current_Year = date('Y');
	$list_months = $list_years = $list_weeks = array();
	for($i=1; $i<=$Current_Month; $i++){
		$list_months[] = $i;
	}
	$Start_Year = 2024;
	for($i=$Start_Year; $i<=$Current_Year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = date('Y-m-d', strtotime('-1 day'));
	$end_date = date('Y-m-d', strtotime('-1 day'));
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$assign_list["Current_Year"] = $Current_Year;
	$assign_list["Current_Month"] = $Current_Month;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	$assign_list["list_blocks"] = $list_blocks;
	###
	$list_sale_departments = array();
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$field = "{$clsProperty->pkey},`title`";
		$list_sale_departments = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
			AND (`parent_id`='"._DEPARTMENT_SALE_ID."' OR `property_id`='"._DEPARTMENT_PARTNER."'
		) AND `is_locked`=0 ORDER BY `order_no` ASC", $field);
	}
	$assign_list["list_sale_departments"] = $list_sale_departments;
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[]= $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo hiệu quả bán hàng';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function report_load_time_range(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$date_type = Input::post('date_type');
	if($date_type == "") {
		echo json_encode(array(
			'start_date' => "",
			'end_date' => "",
		)); die();
	}
	if($date_type == 'today'){
		$start_date = time(); 
		$end_date 	= time(); 
	} else if($date_type == 'yesterday'){
		$start_date = strtotime('-1 day'); 
		$end_date = strtotime('-1 day'); 
	} else if($date_type == '7days'){
		$start_date = strtotime('-7 days'); 
		$end_date = time(); 
	} else if($date_type == '15days'){
		$start_date = strtotime('-15 days'); 
		$end_date = time(); 
	} else if($date_type == '30days'){
		$start_date = strtotime('-30 days'); 
		$end_date = time(); 
	} else if($date_type == 'current_year'){
		$start_date = strtotime(sprintf("01-01-%s",date("Y"))); 
		$end_date = strtotime(sprintf("31-12-%s",date("Y"))); 
	} else if($date_type == 'previous_year'){
		$start_date = strtotime(sprintf("01-01-%s",date("Y")-1)); 
		$end_date = strtotime(sprintf("31-12-%s",date("Y")-1)); 
	}
	// Return
	echo json_encode(array(
		'start_date' => date('Y-m-d', $start_date),
		'end_date' => date('Y-m-d', $end_date),
	)); die();
}
function report_load_date_range(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$month = Input::post('month', date('n'));
	$year = Input::post('year', date('Y'));
	###
	$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$start_date = strtotime(sprintf('%s-%s-%s', '01', $month, $year));
	$end_date = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
	// Return
	echo json_encode(array(
		'start_date' => date('Y-m-d', $start_date),
		'end_date' => date('Y-m-d', $end_date),
	)); die();
}
function report_list_reports(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsReport = new Report();
	$clsProfile = new Profile();
	##
	$current_now = strtotime(date('d-m-Y'));
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', 'yesterday');
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	if(!empty($start_date) && !empty($end_date)){
		if($date_type == 'yesterday'){
			$start_time = strtotime('-7 days');
			$end_time = strtotime(date('d-m-Y'));
		} else {
			$start_time = $clsISO->toTime($start_date);
			$end_time = $clsISO->toTime($end_date);
		}
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$start_time = strtotime(sprintf('%s-%s-%s', '01', $month, $year));
		$end_time = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
	}
	$list_dates = array();
	for($i=$end_time; $i>=$start_time; $i = strtotime('-1 day', $i)){
		$list_dates[] = $i;
	}
	$html = "";
	$cond = "`is_trash`=0 and `user_id`='{$profile_id}'";
	foreach($list_dates as $report_date){
		$oneReport = $clsReport->getByCond("{$cond} and FROM_UNIXTIME(`report_date`,'%d/%m/%Y')='".date('d/m/Y',$report_date)."'");
		$html.= '<tr>
			<td class="text-left">'.date('d/m/Y', $report_date).'</td>
			<td class="text-left">'.(!empty($oneReport) ? '<a class="text-link js__add-report" onClick="$Core.global.report.view(this, event)" report_id="'.$oneReport[$clsReport->pkey].'">Báo cáo ngày '.date('d/m/Y', $oneReport['report_date']).' | '.$oneReport['report_code'].'</a>': '<a class="js__add-report'.((($report_date <= $current_now) && !$clsReport->check_time_send_report_date($report_date)) || ($report_date > $current_now && !$clsReport->check_time_send_report()) ? ' disabled text-muted':'').'" onclick="$Core.global.report.open(this, event)" tp="_add" report_date="'.$report_date.'"><span class="icon mr-1"><i class="fa fa-plus" aria-hidden="true"></i></span> Thêm báo cáo</a>').'</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'per_page' => $per_page,
		'current_page' => $current_page
	));
}
function report_load_sfs_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id;
	$clsReport = new Report();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	###
	$uid = $clsISO->getUniqid();
	$call_from = Input::post("call_from", "_report");
	$department_id = (int) Input::post('department_id', 0);
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$staffId = (int) Input::post('staff_id', 0);
	###
	if($call_from == "_report_group"){
		$group_id = (int) Input::post('group_id', _PROFILE_DEFAULT_GROUP_ID);
		$oneGroup = $clsGroupProfile->getOne($group_id);
		$list_profile_id = $oneGroup['list_profile_id'];
		$cond = "`is_trash`=0 and status_id<>'"._STATUS_STAFF_OFF_ID."'";
		$arr_profile_groups = $clsISO->getArrayByTextSlash($list_profile_id);
		if(!empty($arr_profile_groups)) 
			$cond.= " and {$clsProfile->pkey} in(".implode(',', $arr_profile_groups).")";
		$field = "{$clsProfile->pkey},first_name,last_name,full_name";
		if($staffId > 0){
			$cond .= " and `profile_id`='".$staffId."' ";
		}
		$list_staffs = $clsProfile->getAll($cond, $field);
	} else {
		if($clsISO->checkPermissionGroup('DIRECTOR')){
			if($department_id == 0){
				$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."|%'";
			} else {
				$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
					and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
			}
		} else if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
			$department_id = $oneProfile['department_id'];
			$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%'
			)";
		}
		$cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."' and {$clsProfile->pkey}<>'"._PROFILE_NDT_ID."'";
		$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
		if($staffId > 0){
			$cond .= " and `profile_id`='".$staffId."' ";
		}
		$list_staffs = $clsProfile->getAll($cond, $field);
	}
	###
	$cond = "`is_trash`=0";
	if(!empty($start_date) && !empty($end_date)){
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date, "23:59:59");
		$cond.= " and (`report_date` between {$start_time} AND {$end_time})";
	} else {
		if($month > 0){
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " and FROM_UNIXTIME(`report_date`,'%m/%Y')='{$m}'";
		} else {
			$cond.= " and FROM_UNIXTIME(`report_date`,'%Y')='{$year}'";
		}
	}
	$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
	if(!empty($list_cols)){
		foreach($list_cols as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$permiss_role = $more_information['permiss_role'];
			if(!$clsISO->checkPermissionGroup('DIRECTOR') && !in_array($oneProfile['role_id'], $permiss_role)){
				unset($list_cols[$key]);
			} else {
				$list_cols[$key]['unit_name'] = $more_information['unit_name'];
			}
		}
	}
	$html = '<div class="table-container overflow-x-auto relative">
	<table cellspacing="0" cellpadding="0" class="table table-sort text-nowrap">
		<thead><tr>
			<th class="text-center align-center nosort bg-lighter">Nhân viên</th>';
			foreach($list_cols as $key => $val){
				$html.= '<th class="text-center align-center sortable'.($val[$clsProperty->pkey]==_REPORT_COLUMN_CUSTOMER_NEW_ID?' js__th-sort-clickable':'').' bg-lighter"">'.$val['title'].'</th>';
			}
	$html.= '</tr>
		</thead>';
	$total_arrs = array();
	foreach($list_cols as $prop){
		$prop_id = $prop[$clsProperty->pkey];
		$total_arrs[$prop_id] = 0;
	}
	foreach($list_staffs as $key => $val){
		$total_in_arrs = array();
		$staff_id = $val[$clsProfile->pkey];
		foreach($list_cols as $prop){
			$prop_id = $prop[$clsProperty->pkey];
			$total_in_arrs[$prop_id] = 0;
		}
		$list_reports = $clsReport->getAll("{$cond} and `user_id`='{$staff_id}'", "report_store");
		if(!empty($list_reports)){
			foreach($list_reports as $okey => $oval){
				$report_store = $oval['report_store'];
				$report_store = $clsISO->to_array_json($report_store);
				foreach($total_in_arrs as $prop_id => $score){
					if(isset($report_store[$prop_id]) && !empty($report_store[$prop_id])){
						if($prop_id == _REPORT_COLUMN_ADS_ID){
							$total_arrs[$prop_id] += $clsISO->processSmartNumber($report_store[$prop_id]);
							$total_in_arrs[$prop_id] += $clsISO->processSmartNumber($report_store[$prop_id]);
						} else {
							$total_arrs[$prop_id] += $clsISO->convertToNumber($report_store[$prop_id]);
							$total_in_arrs[$prop_id] += $clsISO->convertToNumber($report_store[$prop_id]);
						}
					}
				}
			}
			unset($list_reports);
		}
		$html.= '<tr>
			<td class="text-left fw-bold">'.$clsProfile->getFullName($staff_id, $val).'</td>';
			foreach($list_cols as $key => $val){
				$prop_id = $val[$clsProperty->pkey];
				$total_results = $total_in_arrs[$prop_id];
				$html.= '<td class="align-center text-center">
					<span class="text-'.(!empty($total_results)?'main fw-bold':'muted').'">
						'.($prop_id==_REPORT_COLUMN_ADS_ID ? $clsISO->formatPrice($total_results) : $total_results).'
					</span> '.$val['unit_name'].'
				</td>';
			}
		$html.= '</tr>';
	}
	$html.= '<tr>
		<td class="fw-bold text-main bg-lighter">Tổng số</td>';
		foreach($list_cols as $key => $val){
			$prop_id = $val[$clsProperty->pkey];
			$total_results = $total_arrs[$prop_id];
			$html.='<td class="fw-bold bg-lighter text-main text-center">
				<strong>'.($prop_id==_REPORT_COLUMN_ADS_ID ? $clsISO->formatPrice($total_results) : $total_results).'</strong> '.$val['unit_name'].' 
			</td>';
		}
	$html.= '</tr>
	</table></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 0,
		'multichart' => 0
	)); die();
}
function report_load_report_today(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id;
	$clsReport = new Report();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	###
	$tp = Input::get("tp", "_sale");
	$date_type = Input::post('date_type', 'yesterday');
	$department_id = (int) Input::post('department_id', 0);
	if($date_type == 'yesterday'){
		$start_time = strtotime('-7 days');
		$end_time = strtotime('-1 day');
	} else {
		$start_date = Input::post('start_date');
		$end_date = Input::post('end_date');
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date);
	}
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		if($department_id == 0){
			$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
				and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."|%'";
		} else {
			$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
				and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
		}
	} else {
		$department_id = $oneProfile['department_id'];
		$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
			and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
	}
	$cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."' and {$clsProfile->pkey}<>'"._PROFILE_NDT_ID."'";
	$html = '<div class="table-container overflow-x-auto">
	<table class="table table-bordered" border="0" cellpadding="0" cellspacing="0">
		<thead><tr>
			<th class="align-center">Họ và tên</th>
			<th class="align-center text-center">Tổng</th>';
			$total_days = 0;
			for($i=$end_time; $i >= $start_time; $i = strtotime("-1 days", $i)){
				$total_days += 1;
				$html.= '<th class="align-center text-center">'.date('d/m/Y', $i).'</th>';
			}
	$html.= '</tr></thead>';
	$list_staffs = $clsProfile->getAll($cond, $field);
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		$oProfile = $clsProfile->getOne($staff_id, "`full_name`,`first_name`,`last_name`,`avatar`");
		$html_staff = '<tr class="text-nowrap">
			<td class="text-left fw-bold">'.$clsProfile->getFullName($staff_id, $oProfile).'</td>
			<td class="text-center fw-bold">
				<span class="text-main">[%total_report%]</span>/<span>'.$total_days.'</span>
			</td>';
		$total_sends = 0;	
		for($i=$end_time; $i >= $start_time; $i = strtotime("-1 days", $i)){
			$oneReport = $clsReport->getByCond("`is_trash`=0 and `user_id`='{$staff_id}' 
				and FROM_UNIXTIME(`report_date`,'%d/%m/%Y')='".date('d/m/Y',$i)."'", "{$clsReport->pkey},report_code");
			if(!empty($oneReport)){
				$total_sends += 1;
				$html_staff .= '<td class="text-center">
					<a href="javascript:void(0);" onClick="$Core.global.report.view(this, event)" report_id="'.$oneReport[$clsReport->pkey].'" class="text-link">'.$oneReport['report_code'].'</a>
				</td>';
			} else {
				$html_staff.= '<td class="text-center">
					<span class="text-muted">Chưa gửi </span>
				</td>';
			}
		}
		$html_staff = str_replace('[%total_report%]', $total_sends, $html_staff);
		$html.= $html_staff;
		$html.= '</tr>';
	}
	$html.= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function report_load_total_reports(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType,$oneProfile,$profile_id;
	$clsReport = new Report();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', "yesterday");
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	###
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$department_id = $oneProfile['department_id'];
		$cond = "`is_trash`=0 AND `user_id` in (
			SELECT {$clsProfile->pkey} FROM {$clsProfile->tbl} 
			WHERE `is_trash`=0 AND `department_id`='{$department_id}' 
			AND `list_department_id` like '%|{$department_id}|%'
		)";
	} else {
		$cond = "`is_trash`=0 and `user_id`='{$profile_id}'";
	}
	if(!empty($start_date) && !empty($end_date)){
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date, "23:59:59");
		$cond.= " and (`report_date` between {$start_time} AND {$end_time})";
	} else {
		if($month > 0){
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " and FROM_UNIXTIME(`report_date`,'%m/%Y')='{$m}'";
		} else {
			$cond.= " and FROM_UNIXTIME(`report_date`,'%Y')='{$year}'";
		}
	}
	$total_arrs = array();
	$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
	foreach($list_cols as $key => $val){
		$prop_id = $val[$clsProperty->pkey];
		$more_information = $val['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$permiss_role = $more_information['permiss_role'];
		if(!$clsISO->checkPermissionGroup('DIRECTOR') && !in_array($oneProfile['role_id'], $permiss_role)){
			unset($list_cols[$key]);
		} else {
			$total_arrs[$prop_id] = 0;
			$list_cols[$key]['unit_name'] = $more_information['unit_name'];
		}
	}
	$list_reports = $clsReport->getAll($cond, "report_store");
	if(!empty($list_reports)){
		foreach($list_reports as $key => $val){
			$report_store = $val['report_store'];
			$report_store = $clsISO->to_array_json($report_store);
			foreach($total_arrs as $prop_id => $score){
				if(isset($report_store[$prop_id]) && !empty($report_store[$prop_id])){
					if($prop_id == _REPORT_COLUMN_ADS_ID){
						$total_arrs[$prop_id]+= $clsISO->processSmartNumber($report_store[$prop_id]);
					} else {
						$total_arrs[$prop_id]+= $clsISO->convertToNumber($report_store[$prop_id]);
					}
				}
			}
		}
	}
	$html_warning = "";
	if($clsISO->checkSale()){
		$today = strtotime(date('d-m-Y'));
		$timeline_arrs = array(
			'1day' => strtotime("-1 day", $today),
			'2days' => strtotime('-2 days', $today),
			'3days' => strtotime('-3 days', $today)
		);
		$total_mis = 0;
		foreach($timeline_arrs as $key => $val){
			if($clsReport->countItem("`user_id`='{$profile_id}' and `report_date`='{$val}'") == 0){
				$total_mis += 1;
			}
		}
		if($total_mis >= 2 && 1==2){
			$html_warning.= '<div class="alert alert-warning">
				<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Bạn đã '.$total_mis.' ngày chưa báo cáo!
			</div>';
		}
	}
	$html = $html_warning. '<div class="table-wrapper">
	<table border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
		<thead><tr>
			<th class="align-center bg-lighter text-center" width="3%">STT</th>
			<th class="align-center bg-lighter">Tiêu chí</th>
			<th class="align-center bg-lighter text-center">Tổng</th>
			<th class="align-center bg-lighter text-center">Đơn vị</th>
		</tr></thead>';
		$ii = 0;
		foreach($list_cols as $key => $val){
			$prop_id = $val[$clsProperty->pkey];
			$html.= '<tr>
				<td class="text-center">'.($ii+1).'</td>
				<td class="text-left">'.($deviceType=='phone' ? $val['title_vn'] : strip_tags($val['title'])).'</td>
				<td class="text-center">
					<strong class="text-main">'.($prop_id == _REPORT_COLUMN_ADS_ID ? $clsISO->formatPrice($total_arrs[$prop_id]) : $total_arrs[$prop_id]).'</strong>
				</td>
				<td class="text-center">'.$val['unit_name'].'</td>
			</tr>';
			++$ii;
		}
	$html.= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		// 'cond' => $cond
	)); die();
}
function report_load_chart_reports(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType,$profile_id,$oneProfile;
	$clsReport = new Report();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$type_id = (int) Input::post('type_id', _REPORT_COLUMN_CUSTOMER_NEW_ID);
	###
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$html = '<div id="'.$uid.'" class="chartContainer mb-2" style="height:250px"></div>
	<div class="js__search-select-box re__search-box-select relative">
		<div class="overflow-x-auto hide-scroll-thumb">
			<div class="d-flex dragable gap-1 align-items-center">';
	###
	$criteria_arrs = array(); $unit_arrs = array();
	$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
	foreach($list_cols as $key => $val){
		$prop_id = $val[$clsProperty->pkey];
		$more_information = $val['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$permiss_role = $more_information['permiss_role'];
		if(!$clsISO->checkPermissionGroup('DIRECTOR') && !in_array($oneProfile['role_id'], $permiss_role)){
			unset($list_cols[$key]);
		} else {
			$unit_arrs[$prop_id] = $more_information['unit_name'];
			$criteria_arrs[$prop_id] = $val['title_vn'];
			$html.= '<a href="javascript:void(0)" onClick="$Core.report.handle_click(this, event)" tp="chart_reports" type_id="'.$val[$clsProperty->pkey].'" class="'.($type_id==$val[$clsProperty->pkey] ? 'btn-outline-primary ': '').'text-nowrap text-body px-2 py-1 border rounded-pill mztvVEqTks">'.$val['title_vn'].'</a>';
		}
	}
	$html .= '</div>
		</div>
	</div>';
	###
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$department_id = $oneProfile['department_id'];
		$cond = "`is_trash`=0 and `user_id` in (
			select {$clsProfile->pkey} from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='{$department_id}' and `list_department_id` like '%|{$department_id}|%'
		)";
	} else {
		$cond = "`is_trash`=0 and `user_id`='{$profile_id}'";
	}
	if(!empty($start_date) && !empty($end_date)){
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date, "23:59:59");
		for($i=$start_time; $i <= $end_time; $i = strtotime("+1 day", $i)){
			$oneReport = $clsReport->getByCond("{$cond} and FROM_UNIXTIME(`report_date`,'%d/%m/%Y')='".date('d/m/Y', $i)."'", "report_store");
			if(!empty($oneReport)){
				$report_store = $oneReport['report_store'];
				$report_store = $clsISO->to_array_json($report_store);
				if($type_id == _REPORT_COLUMN_ADS_ID){
					$point_score = isset($report_store[$type_id]) 
						? $clsISO->processSmartNumber($report_store[$type_id]) : 0;
				} else {
					$point_score = isset($report_store[$type_id]) 
						? $clsISO->convertToNumber($report_store[$type_id]) : 0;
				}
			}
			$dataPoints[] = array(
				'y'	=> $point_score,
				'label'	=> date('d/m', $i)
			);
		}
	} else {
		if($month > 0){
			$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			for($i=1; $i<= $end_day; $i++){
				$point_score = 0;
				$d = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
				$oneReport = $clsReport->getByCond("{$cond} and FROM_UNIXTIME(`report_date`,'%d/%m/%Y')='{$d}'", "report_store");
				if(!empty($oneReport)){
					$report_store = $oneReport['report_store'];
					$report_store = $clsISO->to_array_json($report_store);
					if($type_id == _REPORT_COLUMN_ADS_ID){
						$point_score = isset($report_store[$type_id]) 
							? $clsISO->processSmartNumber($report_store[$type_id]) : 0;
					} else {
						$point_score = isset($report_store[$type_id]) 
							? $clsISO->convertToNumber($report_store[$type_id]) : 0;
					}
				}
				$dataPoints[] = array(
					'y'	=> $point_score,
					'label'	=> sprintf('%s/%s', $i, $month)
				);
			}
		} else {
			for($i=1; $i<12; $i++){
				$dataPoints[] = array(
					'label'	=> sprintf('%s/%s', $i, $month),
					'y'	=> mt_rand(1,200)
				);
			}
		}
	}
	$data['type'] = 'spline';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = sprintf('%s: {y}', $criteria_arrs[$type_id]);
	// $data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'html_criteria_options' => $html_criteria_options
	)); die();
}
function report_load_person_chart(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType,$profile_id,$oneProfile;
	$clsReport = new Report();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$gId = Input::post('gId', $uid);
	$type_id = (int) Input::post('type_id', _REPORT_COLUMN_CUSTOMER_NEW_ID);
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
	// $clsISO->print_pre($list_cols); die();
	$html = '<div id="'.$uid.'" class="chartContainer w-100 h-px-300 mb-2"></div>
	<div class="js__search-select-box re__search-box-select relative">
		<div class="overflow-x-auto hide-scroll-thumb">
			<div class="d-flex dragable gap-1 align-items-center">';
				foreach($list_cols as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$permiss_role = $more_information['permiss_role'];
					if(!in_array($oneProfile['role_id'], $permiss_role)){
						unset($list_cols[$key]);
					} else {
						$html.= '<a href="javascript:void(0)" onClick="$Core.dashboard.handle_person_chart(this, event)" gId="'.$gId.'" type_id="'.$val[$clsProperty->pkey].'" class="'.($type_id==$val[$clsProperty->pkey] ? 'btn-outline-primary ': '').'text-nowrap text-body px-2 py-1 border rounded-pill mztvVEqTks">'.strip_tags($val['title_vn']).'</a>';
					}
				}
			$html.= '</div>
		</div>
	</div>';
	$list_months = array();
	$current_month = date('n');
	$current_year = date('Y');
	for($i=1; $i<=$current_month; $i++){
		$list_months[] = sprintf('%s/%s', $clsISO->parseNumber($i), $current_year);
	}
	foreach($list_months as $month){
		$total_results = 0;
		$list_reports = $clsReport->getAll("`user_id`='{$profile_id}' and FROM_UNIXTIME(`report_date`,'%m/%Y')='{$month}'", "report_store");
		if(!empty($list_reports)){
			foreach($list_reports as $key => $val){
				$report_store = $val['report_store'];
				$report_store = $clsISO->to_array_json($report_store);
				if($type_id == _REPORT_COLUMN_ADS_ID){
					$point_score = isset($report_store[$type_id]) 
						? $clsISO->processSmartNumber($report_store[$type_id]) : 0;
				} else {
					$point_score = isset($report_store[$type_id]) 
						? $clsISO->convertToNumber($report_store[$type_id]) : 0;
				}
				$total_results += $point_score;
			}
		}
		$dataPoints[] = array(
			'label' => $month,
			'y' => $total_results * 1,
			'indexLabel' => $total_results . ""
		);
	}
	$barChartData['data'] = array(
		array(
			"type"    => "stackedArea",
			//"indexLabel" => "{y}",
			//"color"	 => "#1d6a01",
			"showInLegend"    => false,
			"dataPoints"    => $dataPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function report_load_chart_top_reports(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType,$profile_id,$oneProfile;
	$clsReport = new Report();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$department_id = (int) Input::post('department_id', 0);
	$type_id = (int) Input::post('type_id', _REPORT_COLUMN_CUSTOMER_NEW_ID);
	###
	$dataPoints = $barChartData = array();
	$profPoints = $barChartProfs = array();
	$barChartData['animationEnabled'] = true;
	$html = '<div id="'.$uid.'" class="chartContainer " style="height:300px"></div>
		<div class="js__search-select-box re__search-box-select relative">
			<div class="overflow-x-auto hide-scroll-thumb">
				<div class="d-flex dragable gap-2 align-items-center">';
		$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
		foreach($list_cols as $key => $val){
			$html.= '<a href="javascript:void(0)" onClick="$Core.report.handle_click(this, event)" tp="top_reports" 
			type_id="'.$val[$clsProperty->pkey].'" class="'.($type_id==$val[$clsProperty->pkey] ? 'btn-outline-primary ': '').'text-nowrap 
			text-body px-2 py-1 border rounded-pill mztvVEqTks">'.$val['title_vn'].'</a>';
		}
	$html .= '</div>
		</div>
	</div>';
	$html_profs = '<div id="profs_'.$uid.'" class="chartContainer" style="height:300px"></div>';
	$cond = "`is_trash`=0";
	if(!empty($start_date) && !empty($end_date)){
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date, "23:59:59");
		$cond.= " and (`report_date` between {$start_time} AND {$end_time})";
	} else {
		if($month > 0){
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " and FROM_UNIXTIME(`report_date`,'%m/%Y')='{$m}'";
		} else {
			$cond.= " and FROM_UNIXTIME(`report_date`,'%Y')='{$year}'";
		}
	}
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	$sql_string = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$department_id = $oneProfile['department_id'];
		$sql_string.= " and `department_id`='{$department_id}'";
	} else {
		if($department_id > 0){
			$sql_string.= " and `department_id`='{$department_id}'";
		} else {
			$sql_string.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."|%'";
		}
	}
	$list_staffs = $clsProfile->getAll($sql_string, $field);
	if(!empty($list_staffs)){
		$list_profs = $list_staffs;
		foreach($list_staffs as $key => $val){
			$total_scores = $total_prices = 0;
			$staff_id = $val[$clsProfile->pkey];
			$list_reports = $clsReport->getAll("{$cond} and `user_id`='{$staff_id}'", "`report_store`");
			if(!empty($list_reports)){
				foreach($list_reports as $okey => $oval){
					$report_store = $oval['report_store'];
					$report_store = $clsISO->to_array_json($report_store);
					$total_scores += isset($report_store[$type_id]) 
						? $clsISO->convertToNumber($report_store[$type_id]) : 0;
				}
			}
			$list_profs[$key]['total_prices'] = $total_prices;
			$list_staffs[$key]['total_scores'] = $total_scores;
		}
		$total_price_arrs = @array_column($list_profs, 'total_prices');
		@array_multisort($total_price_arrs, SORT_DESC, $list_profs);
		$total_score_arrs = @array_column($list_staffs, 'total_scores');
		@array_multisort($total_score_arrs, SORT_DESC, $list_staffs);
		$ii = 0;
		foreach($list_staffs as $key => $val){
			if($ii<=10){
				$dataPoints[] = array(
					'y'	=> $val['total_scores'],
					'indexLabel' => $val['total_scores'] . "",
					'label'	=> $clsProfile->getLastName($val[$clsProfile->pkey], $val, false)
				);
			} else {
				break;
			}
			++$ii;
		}
		$ii = 0;
		foreach($list_profs as $key => $val){
			if($ii<=10){
				$profPoints[] = array(
					'y'	=> $val['total_prices'],
					'indexLabel' => $clsISO->shortNumber($val['total_prices']) . "triệu",
					'label'	=> $clsProfile->getLastName($val[$clsProfile->pkey], $val, false)
				);
			} else {
				break;
			}
			++$ii;
		}
	}
	$data['type'] = 'column';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = 'Hiệu quả: {y}';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	###
	$data_profs['type'] = 'spline';
	$data_profs['indexLabel'] = '{y}';
	$data_profs['title']['fontSize'] = '14';
	$data_profs['title']['fontColor'] = 'rgb(159,34,58)';
	$data_profs['toolTipContent'] = 'Ngân sách quảng cáo {label}: {y} triệu';
	$data_profs['indexLabelFontColor'] = '#36454F';
	$data_profs['dataPoints'] = $profPoints;
	$barChartProfs['data'] = $data_profs;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'html_profs' => $html_profs,
		'barChartData' => $barChartData,
		'barChartProfs' => $barChartProfs
	)); die();
}
function report_open_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType,$oneProfile,$profile_id;
	$clsReport = new Report();
	$clsProperty = new Property();
	##
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', "_list");
	$report_id = (int) Input::post('report_id',0);
	$report_date = Input::post('report_date', time());
	##
	$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
	if(!empty($list_cols)){
		foreach($list_cols as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_cols[$key]['unit_name'] = $more_information['unit_name'];
			$permiss_role = $more_information['permiss_role'];
			if(!in_array($oneProfile['role_id'], $permiss_role)){
				unset($list_cols[$key]);
			}
		}
	}
	$smarty->assign('list_cols', $list_cols);
	// $clsISO->print_pre($list_cols); die();
	##
	$action = '_add';
	$oneReport = array(
		'attachments' => array(),
		'report_store' => array(),
		'report_date' => $report_date);
	$Current_Now = time();
	if($report_id > 0){
		$action = '_edit';
		$oneReport = $clsReport->getOne($report_id);
		$attachments = $oneReport['attachments'];
		$report_store = $oneReport['report_store'];
		$attachments = $clsISO->to_array_json($attachments);
		$report_store = $clsISO->to_array_json($report_store);
		$oneReport['attachments'] = $attachments;
		$oneReport['report_store'] = $report_store;
	}
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('report_id', $report_id);
	$smarty->assign('oneReport', $oneReport);
	$smarty->assign('clsReport', $clsReport);
	$smarty->assign('column_width', ($deviceType=='phone' ? 60 : 40));
	$smarty->assign('title_field', ($deviceType=='phone') ? 'title_vn' : 'title');
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('report'.DS.'_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function report_pop_save_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsReport = new Report();
	$msg = "_error";
	$current_now = time();
	$report_id = (int) Input::post('report_id',0);
	$content_notes = Input::post('content_notes');
	$report_code = Input::post('report_code');
	$report_date = Input::post('report_date');
	$report_date = !empty($report_date) 
		? $clsISO->toTime($report_date) : 0;
	// $clsISO->print_pre($report_date); die();
	$report_store = Input::post('report_store');
	if(isset($_POST['submit']) && $_POST['submit'] == 'Update'){
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(!is_uploaded_file($file['name'])){
					$clsUploadFile = new UploadFile(); // attachments
					$up = $clsUploadFile->uploadItem($file,'/demo',"pdf,doc,docx,xls,xlsx,csv,txt,zip,jpg,jpeg,png,gif");
					if(!empty($up) && file_exists(ABSPATH . $up)){
						$attachments[] = $up;
					}
				}
			}
		}
		if($report_id==0){
			$report_id = $clsReport->getMaxId();
			if(!empty($content_notes)){
				$notes[$clsISO->getUniqid()] = array(
					'content' => $content_notes,
					'reg_date' => time(),
					'upd_date' => time(),
					'user_id' => $profile_id,
					'user_id_update' => $profile_id
				);
			}
			if($clsReport->insert(array(
				'report_id' => $report_id,
				'report_date' => $report_date,
				'report_code' => $report_code,
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'notes' => json_encode($notes, JSON_UNESCAPED_UNICODE),
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'report_store' => json_encode($report_store, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'upd_date' => time()
			))){
				$msg = "_success";
			}
		} else {
			$more = array();
			$oneReport = $clsReport->getOne($report_id);
			$list_notes = $oneReport['notes'];
			$old_attachments = $oneReport['attachments'];
			$old_report_store = $oneReport['report_store'];
			$more_information  = $oneReport['more_information'];
			$list_notes = $clsISO->to_array_json($list_notes);
			$old_attachments = $clsISO->to_array_json($old_attachments);
			$old_report_store = $clsISO->to_array_json($old_report_store);
			$more_information = $clsISO->to_array_json($more_information);
			if(!empty($content_notes)){
				$list_notes[$clsISO->getUniqid()] = array(
					'content' => $content_notes,
					'reg_date' => time(),
					'user_id' => $profile_id,
					'upd_date' => time(),
					'user_id_update' => $profile_id
				);
				$more['notes'] = json_encode($list_notes, JSON_UNESCAPED_UNICODE);
			} 
			if(!empty($old_attachments) && !empty($attachments)){
				$attachments = array_merge($attachments, $old_attachments);
				$more['attachments'] = json_encode($attachments, JSON_UNESCAPED_UNICODE);
			}
			$more_information['report_store'] = $old_report_store;
			$attachments = array_merge($attachments, $tmp);
			if($clsReport->updateOne($report_id, array_merge($more, array(
				'report_code' => $report_code,
				'report_date' => $report_date,
				'user_id_update' => $profile_id,
				'report_store' => json_encode($report_store, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'upd_date' => time()
			)))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo $msg; die();
}
function report_view_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsReport = new Report();
	$clsProperty = new Property(); 
	###
	$uid = $clsISO->getUniqid();
	$report_id = (int) Input::post('report_id',0);
	###
	$list_cols = $clsProperty->getCacheItems("_REPORT_TEMPLATE");
	if(!empty($list_cols)){
		foreach($list_cols as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$permiss_role = isset($more_information['permiss_role']) 
				? $more_information['permiss_role'] : array();
			if(!$clsISO->checkPermissionGroup('DIRECTOR') && !in_array($oneProfile['role_id'], $permiss_role)){
				unset($list_cols[$key]);
			} else {
				$list_cols[$key]['unit_name'] = $more_information['unit_name'];
			}
		}
	}
	$smarty->assign('list_cols', $list_cols);
	###
	$oneReport = $clsReport->getOne($report_id);
	$attachments = $oneReport['attachments'];
	$report_store = $oneReport['report_store'];
	$more_information = $oneReport['more_information'];
	$list_attachments = $clsISO->to_array_json($attachments);
	$report_store = $clsISO->to_array_json($report_store);
	$more_information = $clsISO->to_array_json($more_information);
	if(!empty($list_attachments)){
		$tmp = $list_attachments;
		$list_attachments = array();
		foreach($tmp as $file){
			$list_attachments[] = array(
				'name' => basename($file),
				'url' => $file
			);
		}
	}
	$log_views = isset($more_information['log_views']) 
		? $more_information['log_views'] : array();
	if(!array_key_exists($profile_id, $log_views)){
		$log_views[$profile_id] = time();
		$more_information['log_views'] = $log_views;
		$clsReport->updateOne($report_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('report_id', $report_id);
	$smarty->assign('oneReport', $oneReport);
	$smarty->assign('clsReport', $clsReport);
	$smarty->assign('report_store', $report_store);
	$smarty->assign('list_attachments', $list_attachments);
	// Return
	$html = $core->build('report'.DS.'_ajax.report.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'report_id' => $report_id
	)); die();
}
function report_loadStaffGroup(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$group_id = (int)Input::post('group_id', 0);
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	$html = '<option value="0">Chọn thành viên</option>';
	if($group_id > 0) {
		$oneGroup = $clsGroupProfile->getOne($group_id,$clsGroupProfile->pkey.",list_profile_id");
		if(!empty($oneGroup)) {
			$list_profile_id = (!empty($oneGroup['list_profile_id'])) ? $clsISO->getArrayByTextSlash($oneGroup['list_profile_id']) : array();
//			$clsISO->print_pre($list_profile_id);die;
			if(!empty($list_profile_id)) {				
				$lstProfile = $clsProfile->getAll("is_trash='0' AND `is_active`='1' AND `{$clsProfile->pkey}` IN (".implode(",",$list_profile_id).")",$clsProfile->pkey.',full_name');
				foreach ($lstProfile as $key => $val) {
					$html .= '<option value="'.$val[$clsProfile->pkey].'">'.$val["full_name"].'</option>';
				}
			}
		}
	}
	###
	echo $html;die;
}
function report_agent_log(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsAdminLog = new AdminLog();
	$clsProperty  = new Property();
	$clsUser = new User();
	$clsProfile = new Profile();
	
	$arr_time = [
		date("d/m/Y"),
		date("d/m/Y",strtotime("-1 days")),
		date("d/m/Y",strtotime("-2 days")), 
		date("d/m/Y",strtotime("-3 days")),
		date("d/m/Y",strtotime("-4 days"))
	];
	$start_time = date("d-m-Y 00:00:00",strtotime("-4 days"));
	$start_time = strtotime($start_time);
	$end_time = date("d-m-Y 23:59:59");
	$end_time = strtotime($end_time);
	$smarty->assign("arr_time",$arr_time);
	$project_id = Input::post('project_id', 0);
	$stock_type = (int) Input::post('stock_type', 0);
	$cond = "`action`='update_stock'";
	if($stock_type > 0) $cond.= " and `stock_type`='{$stock_type}'";
	if($project_id > 0) $cond.= " and `project_id`='{$project_id}'";
	#
	$array_data = [];
	$lstAdminLog = $clsAdminLog->getAll("{$cond} AND (`date` BETWEEN {$start_time} AND {$end_time})","date,target_id,from_site,user_id,FROM_UNIXTIME(`date`,'%d/%m/%Y') as `day`");
	$array_cache = $array_cache_admin = $array_cache_site = $arr_null = $arr_agent = [];
	foreach($lstAdminLog as $key => $value){
		if(!isset($array_cache[$value['target_id']])){
			$array_cache[$value['target_id']] = $clsProperty->getTitle($value['target_id']);
			$arr_agent[] = $value['target_id'];
		}
		$array_data[$value['target_id']]['title'] = $array_cache[$value['target_id']];
		$admin_name = "";
		if($value['from_site'] == "_admin") {
			if(!isset($array_cache_admin[$value['user_id']])) {
				$array_cache_admin[$value['user_id']] = $clsUser->getOne($value['user_id']);
			}
			$oneAdmin = $array_cache_admin[$value['user_id']];
			$admin_name = $oneAdmin["last_name"];
		}else{
			if(!isset($array_cache_site[$value['user_id']])) {
				$array_cache_site[$value['user_id']] = $clsProfile->getOne($value['user_id']);
			}
			$oneAdmin_site = $array_cache_site[$value['user_id']];
			$admin_name = $oneAdmin_site["last_name"];
		}
		
		for($i=0; $i<count($arr_time);$i++){
			if($value['day'] == $arr_time[$i]){
				$array_data[$value['target_id']][$arr_time[$i]][] = "(".$admin_name.") ".date("H:i",$value['date']);
			}
		}
	}
	for($i=0; $i<count($arr_time);$i++){
		$arr_null[$arr_time[$i]] = null;
	}
	$cond_agent = "";
	if(!empty($arr_agent)) {
		$cond_agent = " AND property_id NOT IN (".implode(',',$arr_agent).")";
	}
	$lstAgent_null = $clsProperty->getAll("`property_type`='_AGENCY' AND `is_locked`=0".$cond_agent,$clsProperty->pkey.',title,more_information');
	foreach($lstAgent_null as $k => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		if(!empty($more_information['spreadsheetId'])) {
			$array_data[$val['property_id']] = $arr_null;
			$array_data[$val['property_id']]['title'] = $val['title'];
		}	
		unset($more_information);
	}
	$smarty->assign("array_data",$array_data);
	$html = $core->build('report'.DS.'_ajax.agent_log.tpl');
//	var_dump($html);die;
	echo json_encode( array(
		'html' => $html, 
	));
}
function report_report_login(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProperty = new Property();
	###
	$Current_Now = time();
	$Current_Month = date('n');
	$Current_Year = date('Y');
	$list_months = $list_years = $list_weeks = array();
	for($i=1; $i<=$Current_Month; $i++){
		$list_months[] = $i;
	}
	$Start_Year = 2024;
	for($i=$Start_Year; $i<=$Current_Year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = date('Y-m-d', time());
	$end_date = date('Y-m-d', time());
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$assign_list["Current_Year"] = $Current_Year;
	$assign_list["Current_Month"] = $Current_Month;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	$assign_list["list_blocks"] = $list_blocks;
	###
	$field = "{$clsProperty->pkey},`title`";
	$list_sale_departments = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
		AND `property_id` NOT IN (361,362,1360,1463) AND `is_locked`=0 ORDER BY `order_no` ASC", $field);
	$assign_list["list_sale_departments"] = $list_sale_departments;
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[]= $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo tần suất truy cập';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function report_load_reports_login(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$deviceType;
	global $profile_id, $oneProfile;
	$clsLog = new Log();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProfileLog = new ProfileLog();
	##
	$current_now = strtotime(date('d-m-Y'));
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', 'yesterday');
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$department_id = Input::post('department_id',0);
	if(!empty($start_date) && !empty($end_date)){
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date,"23:59:59");
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$start_time = strtotime(sprintf('%s-%s-%s', '01', $month, $year));
		$end_time = strtotime(sprintf('%s-%s-%s %s', $end_day, $month, $year,"23:59:59"));
	}
	//	echo date("d/m/Y H:i",$start_time)."-".date("d/m/Y H:i",$end_time);die;
	$html = "";
	$arr_department = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$cond = "`is_trash`=0 AND `status_id`='"._STATUS_STAFF_ON_ID."' 
		and {$clsProfile->pkey} not in (".implode(',',_PROFILE_NOT_LOG).")";
	if($department_id > 0) {
		$cond.= " AND `department_id`='{$department_id}'";
	}
	$field = "{$clsProfile->pkey},full_name,department_id";
	$lstProfile = $clsProfile->getAll($cond, $field);
	$report_arrs = [];
	foreach ($lstProfile as $key => $val) {
		$prof_id = $val[$clsProfile->pkey];
		$total_login = $clsProfileLog->countItem("`profile_id`='{$prof_id}' AND (`reg_date` BETWEEN {$start_time} AND {$end_time})");
		$total_search = $clsLog->countItem("`user_id`='{$prof_id}' AND (`reg_date` BETWEEN {$start_time} AND {$end_time})");
		$link_log = sprintf('%s?user_id=%s&start_date=%s&end_date=%s', 
			$clsISO->getLink("log-sale"), $prof_id, date("d/m/Y",$start_time), date("d/m/Y",$end_time)
		);
		$report_arrs[] = [
			"profile_id"	=>	$prof_id,
			"full_name"	=>	$val["full_name"],
			"department_id"	=>	$val["department_id"],
			"department_name"	=>	$arr_department[$val["department_id"]]["title"],
			"total_login"	=>	$total_login,
			"total_search"	=>	$total_search,
			"link_log"	=>	$link_log,
		];
		unset($total_login, $total_search, $link_log);
	}
	$total_price_arrs = @array_column($report_arrs, 'total_login');
	@array_multisort($total_price_arrs, SORT_DESC, $report_arrs);
	foreach($report_arrs as $key => $val){
		$html.= '<tr>
			'.(($deviceType != "phone")?'<td class="text-center">'.($key + 1).'</td>' : "").'
			<td class="align-center text-left">'.$val["full_name"].'</td>
			<td class="align-center text-center">'.$val["department_name"].'</td>
			<td class="align-center text-center fw-bold '.(($val["total_login"] == 0) ? 'text-main' : "text-success").'">'.$val["total_login"].(($val["total_login"] > 0) ? ' <button class="btn btn-outline-none p-1" type="button" onclick="$Core.report.loadTimeLogin(this,event)" data-profile_id="'.$val["profile_id"].'">
				<i class="bx bx-info-circle text-warning"></i></button>' : "").'
			</td>
			<td class="text-center fw-bold '.(($val["total_search"] == 0) ? 'text-main' : "text-success").'">
				'.$val["total_search"].' <a class="btn btn-outline-none p-1" href="'.$val["link_log"].'" target="_blank"><i class="bx bx-link-external text-primary"></i></a>
			</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	));
}
function report_loadTimeLogin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsProfileLog = new ProfileLog();
	##
	$uid = $clsISO->getUniqid();
	$current_now = strtotime(date('d-m-Y'));
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$view_profile_id = (int)Input::post('profile_id',0);
	if(!empty($start_date) && !empty($end_date)){
		$start_time = $clsISO->toTime($start_date);
		$end_time = $clsISO->toTime($end_date,"23:59:59");
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$start_time = strtotime(sprintf('%s-%s-%s', '01', $month, $year));
		$end_time = strtotime(sprintf('%s-%s-%s %s', $end_day, $month, $year,"23:59:59"));
	}
	$list_reports = $clsProfileLog->getAll("`profile_id`='{$view_profile_id}'
		AND (`reg_date` BETWEEN {$start_time} AND {$end_time}) ORDER BY `reg_date` DESC");
	$smarty->assign('list_reports', $list_reports);
	// $clsISO->print_pre($list_reports); 
	// Return
	$html = $core->build('report'.DS.'_ajax.loadTimeLogin.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
}
function report_get_total(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	
	$html = "";
	$tp = Input::post("tp", "");
	if($tp == "total_stock"){
		$total_stocks = $clsStock->countItem("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `agency_id`>0 
			and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `status_id`<>'"._STOCK_STATUS_NON_ID."')");
		$html = sprintf('<span class="fw-bold fs-3">%s</span>', $clsISO->formatNumberToEasyRead($total_stocks));
	} else if($tp=='total_stock_sold'){
		$total_solds = mt_rand(5,10);
		$html = sprintf('<span class="fw-bold fs-3">%s</span>', $clsISO->formatNumberToEasyRead($total_solds));
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function report_get_agency_chart_total(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	$field = "{$clsProperty->pkey},`title`,`title_vn`";
	$list_agency = $clsProperty->getAll("`is_trash`=0 and `property_type`='_AGENCY' and `is_locked`=0", $field);
	if(!empty($list_agency)){
		foreach($list_agency as $key => $val){
			$agency_id = $val[$clsProperty->pkey];
			$total_stocks = $clsStock->countItem("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' 
			AND `status_id`<>'"._STOCK_STATUS_NON_ID."')");
			if($total_stocks > 0){
				$dataPoints[] = array(
					'label' => $val['title_vn'],
					'y' => $total_stocks * 1,
					'indexLabel' => $total_stocks. ""
				);
			}
		}
	}
	$data['type'] = 'column';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '12';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	// $data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 0,
		'barChartData' => $barChartData,
	)); die();
}
function report_get_project_chart(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsProject =  new Project();
	$clsProperty = new Property();
	
	$ii = 0; $uid = $clsISO->getUniqid();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	$arr_projects = array(
		_PROJECT_BLOCK_LSB_ID => 'block',
		_PROJECT_BLOCK_MLS_ID => 'block',
		_PROJECT_BLOCK_MTS_ID => 'block',
		_PROJECT_DEF_ID => 'project'
	);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	foreach($arr_projects as $id => $tp){
		$sql_string = "`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `agency_id`>0 
			AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `status_id`<>'"._STOCK_STATUS_NON_ID."')";
		if($tp=='block'){
			$sql_string.= " AND `block_id`='{$id}'";
			$label = $clsProperty->getCode($id);
		} else {
			$sql_string.= " AND `project_id`='{$id}'";
			$label = $clsProject->getCode($id);
		}
		$total_stocks = $clsStock->countItem($sql_string);
		$exploded = ($ii==0) ? true : false;
		$dataPoints[] = array(
			'name' => $label,
			'exploded' => $exploded,
			'y' => $total_stocks * 1,
			'indexLabel' => "{name} = {y} căn",
		);
		++$ii;
	}
	$data['type'] = 'pie';
	$data['showInLegend'] = 'true';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '12';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	// $data['indexLabel'] = '{name} - {y}%';
	// $data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 0,
		'barChartData' => $barChartData,
	)); die();
}
function report_get_agency_table_total(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsStockLog = new StockLog();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	$html = '';
	$field = "{$clsProperty->pkey},`title`,`title_vn`";
	$list_agency = $clsProperty->getAll("`is_trash`=0 and `property_type`='_AGENCY' and `is_locked`=0", $field);
	if(!empty($list_agency)){
		foreach($list_agency as $key => $val){
			$agency_id = $val[$clsProperty->pkey];
			$list_stocks = $clsStock->getAll("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' 
			AND `status_id`<>'"._STOCK_STATUS_NON_ID."')", $clsStock->pkey);
			$total_stocks = 0; $arr_today_stock = array();
			if(!empty($list_stocks)){
				$total_stocks = count($list_stocks);
				foreach($list_stocks as $mkey => $mval){
					$arr_today_stock[] = $mval[$clsStock->pkey];
				}
			}
			$list_agency[$key]['total_stocks'] = $total_stocks;
			###
			$total_solds = $total_adds = 0; 
			$arr_blocks = $arr_stocks = array();
			$tmp = $clsStockLog->getByCond("`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
				AND `agency_id`='{$agency_id}' ORDER BY reg_date DESC limit 0,1", "reg_date");
			if(!empty($tmp)){
				$reg_date = $tmp['reg_date'];
				$field = "`block_id`,`more_information`";
				$list_logs = $clsStockLog->getAll("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
					AND `agency_id`='{$agency_id}' AND FROM_UNIXTIME(`reg_date`,'%d-%m-%Y')='".date('d-m-Y', $reg_date)."'", $field);
				if(!empty($list_logs)){
					foreach($list_logs as $okey => $oval){
						$arr_blocks[] = $oval['block_id'];
						$more_information = $oval['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						if(!empty($more_information)){
							foreach($more_information as $stock_id){
								if(!in_array($stock_id, $arr_stocks)){
									$arr_stocks[] = $stock_id;									
								}
							}
						}
					}
				}
				#- Mảng không cập nhật
				$total_stocks_not_updated = $total_stocks;
				if(!empty($arr_blocks)){
					// $dbconn->debug = true;
					$total_stocks_not_updated = $clsStock->countItem("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
					AND `agency_id`='{$agency_id}' AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' 
					AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `block_id` NOT IN(".implode(',',$arr_blocks).")");
				}
				#- Nhập thêm
				$diff_arrs = array();
				if($arr_today_stock){
					if(!empty($arr_stocks)){
						$diff_arrs = @array_diff($arr_today_stock, $arr_stocks);
					} else {
						$diff_arrs = $arr_today_stock;
					}
				}
				$total_adds = !empty($diff_arrs) ? count($diff_arrs) : 0;
				$total_adds -= $total_stocks_not_updated;
			}
			#- Đã bán
			if(!empty($arr_stocks) && !empty($arr_today_stock)){
				$diff_arrs = @array_diff($arr_stocks, $arr_today_stock);
				$total_solds = !empty($diff_arrs) ? count($diff_arrs) : 0;	
			}
			$list_agency[$key]['total_solds'] = $total_solds;
			$list_agency[$key]['total_adds'] = $total_adds;
		}
		$ks = array_column($list_agency, "total_stocks");
		array_multisort($ks, SORT_DESC, $list_agency);
		foreach($list_agency as $key => $val){
			if($val['total_stocks'] > 0 || $val['total_solds'] > 0){
				$html.= '<tr>
					<td class="align-center">'.$val['title'].'</td>
					<td class="align-center text-center fw-bold text-main">'.$val['total_stocks'].'</td>
					<td class="align-center text-center">'.$val['total_solds'].'</td>
					<td class="align-center text-center">'.$val['total_adds'].'</td>
				</tr>';
			}
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function report_load_top_stock_logs(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsLog = new Log();
	$clsStock = new Stock();
	
	$uid = $clsISO->getUniqid();
	$html = '<div id="'.$uid.'" class="table-container overflow-y-auto h-px-300 text-nowrap no-shadow">
	<table cellpadding="0" cellspacing="0" class="table">
		<thead><tr>
			<th class="align-center h-px-35 bg-lighter">Mã căn</th>
			<th class="align-center text-center h-px-35 bg-lighter">Lượt check</th>
		</tr></thead>';
	$sql_cond= "`t2`.`is_trash`=0 AND `t2`.`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `t2`.`status_id`>0 
		AND `t2`.`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND FROM_UNIXTIME(`t1`.`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'"; 
	$field = "COUNT(`t1`.`target_id`) as `total_search`,`t2`.`stock_id`,`t2`.`ms_code`";
	$list_stocks = $dbconn->getAll("SELECT {$field} FROM {$clsLog->tbl} AS `t1` 
		INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`target_id`=`t2`.`stock_id` AND `t1`.`type`='view_stock' 
		WHERE {$sql_cond} GROUP BY `t1`.`target_id` HAVING `total_search`>0 order by `total_search` DESC limit 0,10");
	if(!empty($list_stocks)){ $ii = 1;
		foreach($list_stocks as $key => $val){
			$html.= '<tr>
				<td class="align-center text-left">
					<a href="javascript:void(0)" title="Xem chi tiết" class="text-link" 
						onClick="$Core.helper.open_stock('.$val['stock_id'].');">'.$val['ms_code'].'<a/>
				</td>
				<td class="align-center text-center">
					'.$val['total_search'].'
				</td>';
			++$ii;
		}	
	}	
	$html .= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => '
		const verticalExample = document.getElementById(\''.$uid.'\');
		if (verticalExample) {
			new PerfectScrollbar(verticalExample, {
				wheelPropagation: false
			});
		}'
	)); die();
}
function report_top(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProperty = new Property();
	###
	$slug = Input::get("slug","");
	$listBlockPage = $clsISO->getListBlockPage();
	$block_name = $title_block_page = $type = "";
	if(!empty($slug)) {
		foreach ($listBlockPage as $key => $val) {
			if($slug == $val["slug"]) {
				$block_name = $val["block_name"];
				$title_block_page = $val["title_page"];
				$type = $val["type"];
				break;
			}
		}
	}
	if($block_name == "" || $title_block_page == "") {
		header("Location: /");exit();
	}
	$assign_list["slug"] = $slug;
	$assign_list["block_name"] = $block_name;
	$assign_list["type"] = $type;
	$assign_list["title_block_page"] = $title_block_page;
	$title_page = $title_block_page;
	/*=============Title & Description Page==================*/
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function report_revenue(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$Current_Now = time();
	$Current_Month = date('n');
	$Current_Year = date('Y');
	$list_months = $list_years = $list_weeks = array();
	for($i=1; $i<=$Current_Month; $i++){
		$list_months[] = $i;
	}
	$Start_Year = 2024;
	for($i=$Start_Year; $i<=$Current_Year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = date('Y-m-d', strtotime('-1 day'));
	$end_date = date('Y-m-d', strtotime('-1 day'));
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$assign_list["Current_Year"] = $Current_Year;
	$assign_list["Current_Month"] = $Current_Month;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	$assign_list["list_blocks"] = $list_blocks;
	###
	$field = "{$clsProperty->pkey},`title`";
	$list_sale_departments = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
		AND (`parent_id`='"._DEPARTMENT_SALE_ID."' OR `property_id`='"._DEPARTMENT_PARTNER."'
	) AND `is_locked`=0 ORDER BY `order_no` ASC", $field);
	$assign_list["list_sale_departments"] = $list_sale_departments;
	
	$lstProfile = $clsProfile->getProfileCached("active");
	$assign_list["lstProfile"] = $lstProfile;
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[]= $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo doanh số';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function report_load_revenue(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$department_id = (int)Input::post('department_id', 0);
	$staff_id = (int) Input::post('profile_id', 0);
	$billing_source = (int) Input::post('billing_source', 0);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
	$start_time = strtotime(sprintf('01-01-%s', $year));
	$end_time = strtotime(sprintf('%s-%s-%s', $end_day, 12, $year));	
	#
	$date_type = Input::post('date_type', '_month');
	if($date_type == '_month'){
		$quarter = 0; // Init value
		$month  = (int) Input::post('month', 0);
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Init value
		$quarter  = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	###	
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `billing_type`<>'"._BILLING_TYPE_SOP_ID."'";	
	if($date_type == '_month' && $month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
	} else if($date_type == '_half_year' && $half_year > 0){
		if($half_year == 1){
			$start_month = 1;
			$end_month = 6;
		} else if($half_year == 2){
			$start_month = 7;
			$end_month = 12;
		}
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
		$cond.= " AND (`deposit_date` BETWEEN {$start_date} AND {$end_date})";
	} else if($date_type == '_quarter' && $quarter > 0){
		if($quarter == 1){
			$start_month = 1;
			$end_month = 3;
		} else if($quarter == 2){
			$start_month = 4;
			$end_month = 6;
		} else if($quarter == 3){
			$start_month = 7;
			$end_month = 9;
		} else if($quarter == 4){
			$start_month = 10;
			$end_month = 12;
		}
		$start_time = date(sprintf('%s-%s-01 00:00', $year, $clsISO->parseNumber($start_month)));
		$end_time = date(sprintf('%s-%s-t 23:59:59', $year, $clsISO->parseNumber($end_month)));
		$cond.= " and `deposit_date` BETWEEN ".strtotime($start_time)." AND ".strtotime($end_time);
	} else {
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	if(!empty($billing_source)) {
		$cond .= " AND `billing_source_id`='{$billing_source}'";
	}
	
	###
	$lstProjectMas = $clsProperty->getAllCache("JSON_EXTRACT(`more_information`,\"$.on_sale\") ='1' AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' ", "`{$clsProperty->pkey}`,`title`,`property_code`");
	$arr_project = [];
	foreach ($lstProjectMas as $key => $val) {
		if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_MSQ_ARRAY)) {
			if(!isset($arr_project["_PROJECT_BLOCK_MSQ_ARRAY"])) {
				$arr_project["_PROJECT_BLOCK_MSQ_ARRAY"]["property_code"] = "MSQ";
			}
		}else if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_CSD_ARRAY)) {
			if(!isset($arr_project["_PROJECT_BLOCK_CSD_ARRAY"])) {
				$arr_project["_PROJECT_BLOCK_CSD_ARRAY"]["property_code"] = "CSĐ";
			}
		}else if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_SUNSHINE_ARRAY)) {
			if(!isset($arr_project["_PROJECT_BLOCK_SUNSHINE_ARRAY"])) {
				$arr_project["_PROJECT_BLOCK_SUNSHINE_ARRAY"]["property_code"] = "SLC";
			}
		}else if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_MIK_ARRAY)) {
			if(!isset($arr_project["_PROJECT_BLOCK_MIK_ARRAY"])) {
				$arr_project["_PROJECT_BLOCK_MIK_ARRAY"]["property_code"] = "MIK";
			}
		}else{
			$arr_project[$val[$clsProperty->pkey]] = $val;
		}
	}
	$arr_project["_BLOCK_TYPE_LOWFLOOR_SALE"] = [
		"title"	=>	"Thấp tầng",
		"property_code"	=>	"Thấp tầng",
	];
	if(!empty($department_id)) {
		$cond.= " and `staff_id` in (
				select `profile_id` from ".$clsProfile->tbl." 
				where (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%'))";
	}	
	if(!empty($staff_id)) {
		$cond .= " AND `staff_id`='".$staff_id."'";
	}
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
//	echo $cond;die;
	$total_billings = $total_sales = 0;
	foreach($arr_project as $key => $val){
		$total_billing = $total_sale = 0;
		$field = "{$clsBilling->pkey},`totalgrand`";
		if($key == "_PROJECT_BLOCK_MSQ_ARRAY") {
			$arr_project[$key]["title"] = "Masteri Sky Quarter";
			$list_billings = $clsBilling->getAll("{$cond} AND JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',_PROJECT_BLOCK_MSQ_ARRAY).")", $field);
		}else if($key == "_PROJECT_BLOCK_CSD_ARRAY") {
			$arr_project[$key]["title"] = "Capital Square";
			$list_billings = $clsBilling->getAll("{$cond} AND JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',_PROJECT_BLOCK_CSD_ARRAY).")", $field);
		}else if($key == "_PROJECT_BLOCK_SUNSHINE_ARRAY") {
			$arr_project[$key]["title"] = "Sunshine Legend City";
			$list_billings = $clsBilling->getAll("{$cond} AND JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',_PROJECT_BLOCK_SUNSHINE_ARRAY).")", $field);
		}else if($key == "_PROJECT_BLOCK_MIK_ARRAY") {
			$arr_project[$key]["title"] = "The Parkland";
			$list_billings = $clsBilling->getAll("{$cond} AND JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',_PROJECT_BLOCK_MIK_ARRAY).")", $field);
		}else if($key == "_BLOCK_TYPE_LOWFLOOR_SALE") {
			$list_billings = $clsBilling->getAll("{$cond} AND `billing_type`='"._BILLING_TYPE_TT_ID."'", $field);
		}else{
			$list_billings = $clsBilling->getAll("{$cond} AND JSON_EXTRACT(`more_information`,\"$.block_id\")='".$val[$clsProperty->pkey]."'", $field);
		}
		if(!empty($list_billings)){
			$total_billings += count($list_billings);
			$total_billing = count($list_billings);
			foreach($list_billings as $k => $v_billing){
				$totalgrand = $clsISO->convertToNumber($v_billing['totalgrand']);
				$total_sales += $totalgrand;
				$total_sale += $totalgrand;
			}
			unset($list_billings);
		}
		$dataPoints[] = array(
			'label'	=> $val["property_code"],
			'y'	=> $total_sale*1,
			'indexLabel' => $clsISO->shortNumber($total_sale)
		);
		$dataTotalPoints[] = array(
			'label'	=> $val["property_code"],
			'y' => $total_billing*1,
			'indexLabel' => (string) $total_billing."GD"
		);
		$arr_project[$key]["total_sale"] = $total_sale;
		$arr_project[$key]["total_billing"] = $total_billing;
	}
	$total_arrs = @array_column($arr_project, 'total_billing');
	@array_multisort($total_arrs, SORT_DESC, $arr_project);
//	$clsISO->print_pre($arr_project);die;
	#
	$barChartData['animationEnabled'] = true;
	$barChartData['dataPointMaxWidth'] = 60;
	$barChartData['zoomEnabled'] = true;
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	$barChartData['axisY2'][] = array(
		'title' => 'SL giao dịch(GD)',
		'titleFontColor' => '#C00000',
		'titleFontSize' => '16',
		'lineColor' => '#C00000',
		'labelFontColor' => '#C00000',
		'tickColor' => '#C00000',
		'maximum' => 400
	);
	$barChartData['data'] = array(
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataTotalPoints
		),
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		)
	);
	$smarty->assign("uid",$uid);
	$smarty->assign("arr_project",$arr_project);
	$smarty->assign("total_billings",$total_billings);
	$smarty->assign("total_sales",$total_sales);
	$html = $core->build("report".DS."_ajax.report_revenue.tpl");
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}