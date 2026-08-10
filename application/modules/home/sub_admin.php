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
function admin_open_full(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$tp = Input::post('tp', "load_billing_chart");
	$titlePage = ($tp == 'load_billing_chart') 
		? 'Biểu đồ tăng trưởng doanh số' 
		: 'Thống kê theo loại giao dịch';
	$smarty->assign('tp', $tp);
	$smarty->assign('titlePage', $titlePage);
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	$smarty->assign('list_years', $list_years);
	$smarty->assign('list_months', $list_months);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.full.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function admin_load_billing(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBilling', $clsBilling);
	###
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	##
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',10);
	$cond.= "`is_trash`=0 and `staff_id` in (
		select `profile_id` from ".$clsProfile->tbl." 
		where (`department_id`='{$department_id}' 
			or `list_department_id` like '%|{$department_id}|%'
		)
	)";
	$total_record = $clsBilling->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$field = "*";
	$list_billings = $clsBilling->getAll($cond." order by reg_date DESC".$limitCond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		foreach($list_billings as $key => $val){
			$project_id = $val['project_id'];
			$billing_type = $val['billing_type'];
			$contract_status_id = $val['contract_status_id'];
			###
			if($billing_type > 0){
				if(isset($arr_property_cached[$billing_type])){
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				} else {
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				}
			} else {
				$list_billings[$key]['billing_type'] = "";
			}
			###
			if($project_id > 0){
				if(isset($arr_projects_cached[$project_id])){
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				} else {
					$arr_projects_cached[$project_id] = $clsProject->getTitle($project_id);
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				}
			} else {
				$list_billings[$key]['poroject_name'] = "";
			}
			if($contract_status_id > 0){
				if(!isset($arr_property_cached[$contract_status_id])){
					$arr_property_cached[$contract_status_id] = $clsProperty->getTitle($contract_status_id);
				}
				$list_billings[$key]['contract_status'] = $arr_property_cached[$contract_status_id];
			} else {
				$list_billings[$key]['contract_status'] = "";
			}
		}
	}
	// $clsISO->print_pre($list_billings); die();
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.billing.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function admin_load_sale_logs(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsLog = new Log();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBilling', $clsBilling);
	##
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$cond.= "1=1 and `user_id` in (
		select `profile_id` from ".$clsProfile->tbl." 
		where (`department_id`='{$department_id}' 
			or `list_department_id` like '%|{$department_id}|%'
		)
	)";
	$limitCond = " limit 0,10";
	$list_logs = $clsLog->getAll("{$cond} order by `reg_date` DESC".$limitCond);
	if(!empty($list_logs)){
		$arr_profile_cached = array();
		foreach($list_logs as $key => $_oLog){
			$type = $_oLog['type'];
			$user_id = $_oLog['user_id'];
			$title = !empty($_oLog['title']) ? @strtok($_oLog['title'],'?') : "";
			$title = preg_replace('(Xem thông tin căn hộ|Tra cứu căn hộ)','', $title);
			if(isset($arr_profile_cached[$user_id])){
				$porfile = $arr_profile_cached[$user_id];
			} else {
				$oProfile = $clsProfile->getOne($user_id, "`avatar`,`full_name`,`first_name`,`last_name`");
				$arr_profile_cached[$user_id] = $clsProfile->getIndentityV4($user_id, $oProfile);
				$porfile = $arr_profile_cached[$user_id];
			}
			if($type='view_stock'){
				$oStock = $clsLog->getStock($title);
				$stock_id = $oStock['stock_id'];
				$status_id = $oStock['status_id'];
				$agency_id = $oStock['agency_id'];
				$label = "";
				if($status_id== _STOCK_STATUS_DQ_ID && $agency_id==_AGENCY_FH_ID){
					$label = "<sup class=\"text-yellow\">ĐQ</sup>";
				}
				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')">'.$title.$label.'</a>';
			} else if($type=='search'){
				$oStock = $clsLog->getStock($title);
				$stock_id = $oStock['stock_id'];
				$status_id = $oStock['status_id'];
				$agency_id = $oStock['agency_id'];
				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')">'.$title.'</a>';
			}
			$html.= '<tr>
				<td data-label="Nhân viên">'.$porfile.'</td>
				<td data-label="Thời gian">'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'</td>
				<td data-label="Nội dung">'.$contentHTML.'</td>
			</tr>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();	
}
function admin_load_billing_chart(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsLog = new Log();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '_month');
	$is_bigger = (int) Input::post('is_bigger', 0);
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	###
	$height = ($is_bigger) ? 'calc(100vh - 300px)' : '250px';
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:'.$height.'"></div>';
	$cond = "`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0";
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT_DIRECTOR')){
		// Continue
	} else {
		$cond.= " and `staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')
		)";
	}
	$block_id = vnSessionGetVar('BlockAdminId');
	if($block_id > 0) {
		$cond .= " AND stock_code IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
	}
//	echo $cond;die;
	$list_ranges = array();
	if($month == 0){
		$f = '%m/%Y';
		$to_month = time();
		$start_month = strtotime('-6 months', $to_month);
		for($i = $start_month; $i <= $to_month; $i = strtotime('+1 month', $i)){
			$list_ranges[] = date('m/Y', $i);
		}
	} else {
		if($date_type == '_quater'){
			if($month == 1){
				$start_month = 1;
				$end_month = 3;
			} else if($month == 2){
				$start_month = 4;
				$end_month = 6;
			} else if($month == 3){
				$start_month = 7;
				$end_month = 9;
			} else {
				$start_month = 9;
				$end_month = 12;
			}
			$f = "%m/%Y";
			for($i=$start_month; $i <= $end_month; $i++){
				$list_ranges[] = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
			}
		} else if($date_type == '_half'){
			if($month == 1){
				$start_month = 1;
				$end_month = 6;
			} else {
				$start_month = 7;
				$end_month = 12;
			}
			$f = "%m/%Y";
			for($i=$start_month; $i <= $end_month; $i++){
				$list_ranges[] = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
			}
		} else {
			$f = '%d/%m/%Y';
			$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			for($i=1; $i<= $number_day; $i++){
				$list_ranges[] = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
			}
		}
	}
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	$barChartData['zoomEnabled'] = true;
	$barChartData['zoomType'] = "xy";
	$barChartData['animationEnabled'] = true;
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '18',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	$barChartData['axisY2'] = array(
		'title' => 'Số lượng giao dịch',
		'titleFontColor' => '#C00000',
		'titleFontSize' => '18',
		'lineColor' => '#C00000',
		'labelFontColor' => '#C00000',
		'tickColor' => '#C00000'
	);
	foreach($list_ranges as $date){
		$total_billings = $clsBilling->countItem("{$cond} and FROM_UNIXTIME(`deposit_date`,'{$f}')='{$date}'");
		$total_sales = $clsBilling->sumItem("totalgrand","{$cond} and FROM_UNIXTIME(`deposit_date`,'{$f}')='{$date}'");
		$dataPoints[] = array(
			'label'	=> sprintf('%s', $date),
			'y'	=> $total_sales*1,
			'indexLabel' => $clsISO->shortNumber($total_sales)
		);
		$dataTotalPoints[] = array(
			'label'	=> sprintf('%s', $date),
			'y' => $total_billings*1,
			'indexLabel' => $total_billings." GD"
		);
	}
	$barChartData['data'] = array(
		array(
			"type"    => "spline",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"yValueFormatString"	=> "Doanh số: #,###đ",
			"dataPoints"    => $dataPoints
		),
		array(
			"type"  => "spline",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"yValueFormatString"	=> "Số lượng: #,### giao dịch",
			"dataPoints"   => $dataTotalPoints
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
function admin_load_billing_type(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsLog = new Log();
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$html = "";
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$cond = "`is_trash`=0 and `staff_id` in (
		select `profile_id` from ".$clsProfile->tbl." 
		where (`department_id`='{$department_id}' 
			or `list_department_id` like '%|{$department_id}|%'
		)
	)";
	if($month > 0){
		$format = "%m/%Y";
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month),$year);
		$start_date = strtotime(sprintf('01-%s-%s', $month, $year));
		$number_day = cal_days_in_month(CAL_GREGORIAN,$month, $year);
		$to_date = strtotime(sprintf('%s-%s-%s', $number_day,$month,$year));
	} else {
		$format = "%Y";
		$date_my = sprintf('%s', $year);
		$start_date = strtotime('01-01-2024');
		$number_day = cal_days_in_month(CAL_GREGORIAN,12,$year);
		$to_date = strtotime(sprintf('%s-%s-%s', $number_day,12,$year));
	}
	$cond.= " and FROM_UNIXTIME(`deposit_date`,'{$format}')='{$date_my}'";
	###
	$list_billing_type = $clsProperty->getCacheItems('_BILLING_TYPE');
	// $clsISO->print_pre($list_billing_type); die();
	if(!empty($list_billing_type)){
		foreach($list_billing_type as $key => $val){
			$prop_id = $val[$clsProperty->pkey];
			$total_sales = $clsBilling->sumItem("totalgrand", "{$cond} and `billing_type`='{$prop_id}'");
			$total_billings = $clsBilling->countItem("{$cond} and `billing_type`='{$prop_id}'");
			$html.= '<div class="report-list-item col-6 mb-3">
				<div class="d-flex align-items-start">
					<div class="report-list-icon shadow-sm p-2 rounded-2 me-2">
						<i class="bx '.$val['image'].'"></i>
					</div>
					<div class="w-100 d-flex flex-column flex-wrap">
						<span class="text-nowrap">'.$val['title'].'</span>
						<div class="d-flex gap-1 justify-content-between align-items-center">
							<h5 class="mb-0 fs-6">
								<a href="'.$clsISO->getLink('billing').'?billing_type='.$prop_id.'&start_date='.$start_date.'&to_date='.$to_date.'" title="Xem chi tiết">'.$clsISO->shortNumber($total_sales).'</a>
							</h5>
							<div class="fs-6 text-muted">
								<a href="'.$clsISO->getLink('billing').'?billing_type='.$prop_id.'&start_date='.$start_date.'&to_date='.$to_date.'" title="Xem chi tiết">'.$total_billings.'</a>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function admin_load_staffs(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsShare = new Share();
	##
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	##
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='{$department_id}' 
		or `list_department_id` like '%|{$department_id}|%'
	)", $field);
	###
	if($month > 0){
		$format = "%m/%Y";
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	} else {
		$format = "%Y";
		$date_my = sprintf('%s', $year);
	}
	###
	$html = ""; $total_staffs = 0;
	if(!empty($list_staffs)){
		$total_staffs = count($list_staffs);
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$cond = "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}'";
			$cond.= " and (FROM_UNIXTIME(`deposit_date`,'{$format}')='{$date_my}')";
			// Tổng DS
			$total_sales = $clsBilling->sumItem("totalgrand", $cond);
			// Tổng GD
			$total_billings = $clsBilling->countItem($cond);
			// Tiếp khách
			$total_share = $clsShare->countItem("`holderG`='share' and `user_id`='{$staff_id}' and FROM_UNIXTIME(`reg_date`,'{$format}')='{$date_my}'");
			$list_staffs[$key]['total_sales'] = $total_sales;
			$list_staffs[$key]['total_billings'] = $total_billings;
			$list_staffs[$key]['total_share'] = $total_share;
		}
		$arr_total_sales = array_column($list_staffs, "total_sales");
		array_multisort($arr_total_sales, SORT_DESC, $list_staffs);
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$html.= '<tr>
				<td class="text-nowrap">
					<a href="javascript:;" class="text-body" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$staff_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="350">'.$clsProfile->getIndentityV3($staff_id, true, $val).'</a>
				</td>
				<td class="text-left fw-bold text-main">'.$clsISO->shortNumber($val['total_sales']).'</td>
				<td class="text-center">'.$val['total_billings'].'</td>
				<td class="text-center">'.$val['total_share'].'</td>
			</tr>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_staffs' => $total_staffs,
		'callback' => '
			$(\'.total_staffs\').text(respJson.total_staffs);
			setTimeout(() => {
				const verticalExample = document.getElementById(\'dashboard_staffs\');
				if (verticalExample) {
					new PerfectScrollbar(verticalExample, {
						wheelPropagation: false
					});
				}
			},1000);
		'
	)); die();
}
function admin_load_report_worktime(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsProfile = new Profile();
	$clsWorktime = new Worktime();
	$clsProperty = new Property();
	#
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$sort_type = Input::post('sort_type');
	$month = Input::post('month', date('m/Y'));
	$tmp = explode('/', $month);
	$curr_month = $tmp[0];
	$curr_year  = $tmp[1];
	$start_date = '01-'.$curr_month.'-'.$curr_year;
	$end_date = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year).'-'.$curr_month.'-'.$curr_year;
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	#
	$tmp = $dbconn->getAll("select `t1`.* from {$clsWorktime->tbl} as `t1` 
		where `t1`.`is_trash`=0 and `t1`.`department_id`='{$department_id}' 
		and (`t1`.`worktime_date` between '{$start_time}' AND '{$end_time}')");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$department_id = $val['department_id'];
			$content_arrs = !empty($val['content']) 
				? json_decode(html_entity_decode($val['content']), true) : array();
			$list_staffs = array();
			if(!empty($content_arrs)){
				foreach($content_arrs as $staff_id => $staff_info){
					if(isset($list_worktimes[$department_id][$staff_id])){
						$list_worktimes[$department_id][$staff_id]['total_items'] += 1;
						$list_worktimes[$department_id][$staff_id]['list_items'][] = array(
							'staff_id' => $staff_id,
							'worktime_id' => $val[$clsWorktime->pkey],
							'worktime_date' => $val['worktime_date'],
							'staff_info' => $staff_info
						);
					} else {
						$list_worktimes[$department_id][$staff_id]['total_items'] = 1;
						$list_worktimes[$department_id][$staff_id]['list_items'][] = array(
							'staff_id' => $staff_id,
							'worktime_id' => $val[$clsWorktime->pkey],
							'worktime_date' => $val['worktime_date'],
							'staff_info' => $staff_info
						);
					}
				}
			}
		}
	}
	$smarty->assign('sort_type', $sort_type);
	$smarty->assign('list_worktimes', $list_worktimes);
	$smarty->assign('list_group_worktimes', $list_group_worktimes);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.worktime.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function admin_load_month(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	####
	$date_type = Input::post('date_type', '_month');
	if($date_type == '_month'){
		$html_options = sprintf('<option value="0">%s</option>', 'Tháng');
		$year = (int) Input::post('year', date('Y'));
		if($year == 2023){
			$start_month = 5;
			$end_month = 12;
		} else if($year == date('Y')) {
			$start_month = 1;
			$end_month = date('m');
		} else {
			$start_month = 1;
			$end_month = 12;
		}
		for($i=$start_month; $i<= $end_month; $i++){
			$html_options.= sprintf('<option value="%s">Tháng %s</option>', $i, $i);
		}
	} else {
		$html_options = "";
		if($date_type == '_quater'){
			for($i=1; $i<=4; $i++){
				$html_options.= sprintf('<option value="%s">Quý %s</option>', $i, $i);
			}
		} else if($date_type == '_half'){
			for($i=1; $i<=2; $i++){
				$html_options.= sprintf('<option value="%s">Nửa %s năm</option>', $i, ($i==1 ? 'đầu' : 'cuối'));
			}
		}
	}
	// Return
	echo $html_options; die();
}
function admin_load_report_share(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id;
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='{$department_id}' 
		or `list_department_id` like '%|{$department_id}|%'
	)", $field);						
	$time_ranges = array(
		'this_week' => array(
			'start_date' => strtotime('monday this week 00:00'),
			'to_date' => strtotime('sunday this week 23:59')
		),
		'prev_week' => array(
			'start_date' => strtotime('monday last week 00:00'),
			'to_date' => strtotime('sunday last week 23:59')
		),
		'this_month' => array(
			'start_date' => strtotime("first day of this month 00:00"),
			'to_date' => strtotime("last day of this month 23:59")
		),
		'prev_month' => array(
			'start_date' => strtotime('first day of last month 00:00'),
			'to_date' => strtotime('last day of last month 23:59')
		)
	);
	$html = '';
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		$oProfile = $clsProfile->getOne($staff_id, "`full_name`,`first_name`,`last_name`,`avatar`");
		$html.= '<tr>
			<td class="text-left text-nowrap">
				'.$clsProfile->getIndentityV6($staff_id, $oProfile).'
			</td>';
			foreach($time_ranges as $okey => $oval){
				$start_date = $oval['start_date'];
				$to_date = $oval['to_date'];
				$total_share = $clsShare->countItem("`holderG`='share' and `user_id`='{$staff_id}' and (`reg_date` between '{$start_date}' AND '{$to_date}')");
				$html.= '<td class="text-center">'.$total_share.'</td>';
			}	
		$html.= '</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => '
			setTimeout(() => {
				const verticalExample = document.getElementById(\'report_share\');
				if (verticalExample) {
					new PerfectScrollbar(verticalExample, {
						wheelPropagation: false
					});
				}
			},1000);
		'
	)); die();
}
function admin_load_report_search(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id;
	$clsLog = new Log();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='{$department_id}' 
		or `list_department_id` like '%|{$department_id}|%'
	)", $field);						
	$time_ranges = array(
		'this_week' => array(
			'start_date' => strtotime('monday this week 00:00'),
			'to_date' => strtotime('sunday this week 23:59')
		),
		'prev_week' => array(
			'start_date' => strtotime('monday last week 00:00'),
			'to_date' => strtotime('sunday last week 23:59')
		),
		'this_month' => array(
			'start_date' => strtotime("first day of this month 00:00"),
			'to_date' => strtotime("last day of this month 23:59")
		),
		'prev_month' => array(
			'start_date' => strtotime('first day of last month 00:00'),
			'to_date' => strtotime('last day of last month 23:59')
		)
	);
	$html = '';
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		foreach($time_ranges as $okey => $oval){
			$start_date = $oval['start_date'];
			$to_date = $oval['to_date'];
			$total_search = $clsLog->countItem("(`type`='search' or `type`='view_stock') and `user_id`='{$staff_id}' 
			and (`reg_date` between '{$start_date}' AND '{$to_date}')");
			$list_staffs[$key][$okey] = $total_search;
		}	
	}
	$arr_column_order = array_column($list_staffs, "this_week");
	array_multisort($arr_column_order, SORT_DESC, $list_staffs);
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		$oProfile = $clsProfile->getOne($staff_id, "`full_name`,`first_name`,`last_name`,`avatar`");
		$html.= '<tr>
			<td class="text-left text-nowrap">
				'.$clsProfile->getIndentityV6($staff_id, $oProfile).'
			</td>';
			foreach($time_ranges as $okey => $oval){
				$start_date = $oval['start_date'];
				$to_date = $oval['to_date'];
				$html.= '<td class="text-center">
					<a class="text-body" title="Xem chi tiết" href="/logs-sale.html?user_id='.$staff_id.'&start_date='.$start_date.'&to_date='.$to_date.'">
						'.$val[$okey].'
					</a>
				</td>';
			}
		$html.= '</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => '
			setTimeout(() => {
				const verticalExample = document.getElementById(\'report_search_logs\');
				if (verticalExample) {
					new PerfectScrollbar(verticalExample, {
						wheelPropagation: false
					});
				}
			},1000);
		'
	)); die();
}
function admin_top_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$data =  array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisY']['labelFormatter'] = 1;
	echo 1;die;
	$block_id = vnSessionGetVar('BlockAdminId');
	$sql_block= "";
	if($block_id > 0) {
		$sql_block .= " AND `t1`.`stock_code` IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
	}
	
	###
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:345px"></div>';
	$field = "SUM(`t1`.`totalgrand`) AS `totalgrand`, count(*) as `total_billing`,`t2`.`profile_id`,`t2`.`first_name`,`t2`.`last_name`,`t2`.`full_name`";
	echo "SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 
		INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` 
		WHERE `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 GROUP BY t1.staff_id ".$sql_block."
		ORDER BY `totalgrand` DESC LIMIT 0,10";die;
	$list_billings = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 
		INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` 
		WHERE `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 GROUP BY t1.staff_id ".$sql_block."
		ORDER BY `totalgrand` DESC LIMIT 0,10");
	foreach($list_billings as $key => $val){
		$staff_id = $val['profile_id'];
		$staff_name = ($deviceType=='phone') 
			? $clsProfile->getLastName($staff_id, $val, false) 
			: $clsProfile->getFullName($staff_id, $val);
		$dataPoints[] = array(
			'label'	=> $staff_name,
			'y'	=> $val['totalgrand']*1,
			'total' => $val['total_billing'],
			'indexLabel' => $clsISO->shortNumber($val['totalgrand'])
		);
	}
	$data['type'] = 'bar';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = 'Số lượng GD: {total} <br /> Doanh số: {y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		//'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function admin_chart_billing_type(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id;
	$clsLog = new Log();
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$is_bigger = (int) Input::post("is_bigger", 0);
	$date_type = Input::post('date_type', "_month");
	###
	$data =  array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisY']['labelFormatter'] = 1;
	$cond = "`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0";
	if($month > 0){
		if($date_type == '_quater'){
			if($month == 1) {
				$start_month = 1;
				$end_month = 3;
			} else if($month == 2) {
				$start_month = 4;
				$end_month = 6;
			} else if($month == 3) {
				$start_month = 7;
				$end_month = 9;
			} else {
				$start_month = 10;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$cond.= " and (`deposit_date` between {$start_date} AND {$end_date})";
		} else if($date_type == '_half'){
			if($month == 1) {
				$start_month = 1;
				$end_month = 6;
			} else {
				$start_month = 7;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$cond.= " and (`deposit_date` between {$start_date} AND {$end_date})";
		} else if($date_type == '_month'){
			$format = "%m/%Y";
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " and FROM_UNIXTIME(`deposit_date`,'{$format}')='{$date_my}'";
		}
	} else {
		$format = "%Y";
		$date_my = sprintf('%s', $year);
	}
	###
	$height = ($is_bigger==1) ? "calc(100vh - 300px)" : "330px";
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:'.$height.'"></div>';
	$list_billing_type = $clsProperty->getCacheItems('_BILLING_TYPE');
	if(!empty($list_billing_type)){
		foreach($list_billing_type as $key => $val){
			$prop_id = $val[$clsProperty->pkey];
			$total_sales = $clsBilling->sumItem("totalgrand", "{$cond} and `billing_type`='{$prop_id}'");
			$total_billings = $clsBilling->countItem("{$cond} and `billing_type`='{$prop_id}'");
			$dataPoints[] = array(
				'label'	=> $val['title'],
				'y'	=> $total_sales*1,
				'total' => $total_billings,
				'indexLabel' => $clsISO->shortNumber($total_sales)
			);
		}
	}
	$data['type'] = 'bar';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = 'Số lượng GD: {total} <br /> Doanh số: {y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		//'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function admin_load_department_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	if($month == 0){
		$date_my = sprintf('%s', $year);
		$sql_time = " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%Y')='{$date_my}'";
	} else {
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$sql_time = " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$date_my}'";
	}
	###
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:350px"></div>';
	###
	$field = "{$clsProperty->pkey},`title`";
	$list_departments = $clsProperty->getAll("`property_type`='_DEPARTMENT' and `parent_id`='"._DEPARTMENT_SALE_ID."' 
	and {$clsProperty->pkey}<>'"._DEPARTMENT_FH05_ID."'", $field);
	###
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['axisX'] = array(
		'labelAngle' => -90,
		'interval' => 1
	);
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1,
	);
	$barChartData['axisY2'] = array(
		'title' => 'Số lượng giao dịch',
		'titleFontColor' => 'rgb(138,12,33)',
		'labelFontColor' => 'rgb(138,12,33)',
		'lineColor' => 'rgb(138,12,33)',
		'tickColor' => 'rgb(138,12,33)'
	);
	
	$block_id = vnSessionGetVar('BlockAdminId');
	$sql_block= "";
	if($block_id > 0) {
		$sql_block .= " AND `t1`.`stock_code` IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
	}
	
	$list_staffs_notin = array();
	if(!empty($list_departments)){
		foreach($list_departments as $key => $val){
			$department_id = $val[$clsProperty->pkey];
			$list_staffs_in = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
				and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $clsProfile->pkey);
			if(!empty($list_staffs_in)){
				foreach($list_staffs_in as $staff){
					$list_staffs_notin[] = $staff[$clsProfile->pkey];
				}
				$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(*) as `total_billings`";
				$tmp = $dbconn->getRow("select {$field} from {$clsBilling->tbl} as `t1` 
						inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 
						where `t2`.`is_trash`=0 and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' and `t2`.`department_id`='{$department_id}' 
						and `t1`.`is_cancel`=0 and `t1`.`is_alliance`=0".$sql_time.$sql_block); // or `t2`.`list_department_id` like '%|{$department_id}|%'
				$total_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;
				$total_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;
				$dataPoints[] = array(
					'label' => $val['title'],
					'y' => $total_sales * 1,
					'indexLabel' => $clsISO->shortNumber($total_sales*1)
				);
				$dataTotalPoints[] = array(
					'label' => $val['title'],
					'y' => $total_billings * 1
				);
			}
		}
	}
	// OTHER
	$list_staffs_notin[] = _PROFILE_PARTNER_ID;
	$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(*) as `total_billings`";
	$tmp = $dbconn->getRow("select {$field} from {$clsBilling->tbl} as `t1` 
		inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 
		where `t2`.`is_trash`=0 and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and `t1`.`staff_id` not in (".implode(',',$list_staffs_notin).") and `t1`.`is_cancel`=0 
		and `t1`.`is_alliance`=0".$sql_time);
	$total_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;
	$total_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;
	$dataPoints[] = array(
		'label' => 'Khác',
		'y' => $total_sales * 1,
		'indexLabel' => $clsISO->shortNumber($total_sales*1)
	);
	$dataTotalPoints[] = array(
		'label' => 'Khác',
		'y' => $total_billings * 1
	);
	// PARTNER
	$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(*) as `total_billings`";
	$tmp = $dbconn->getRow("select {$field} from {$clsBilling->tbl} as `t1` 
		inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 
		where `t2`.`is_trash`=0 and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and `t1`.`staff_id`='"._PROFILE_PARTNER_ID."' and `t1`.`is_cancel`=0".$sql_time);
	$total_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;
	$total_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;
	$dataPoints[] = array(
		'label' => 'Đối tác',
		'y' => $total_sales * 1,
		'indexLabel' => $clsISO->shortNumber($total_sales*1)
	);
	$dataTotalPoints[] = array(
		'label' => 'Đối tác',
		'y' => $total_billings * 1
	);
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			"indexLabelFontColor" => "rgb(138,12,33)",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		),
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "rgb(138,12,33)",
			"showInLegend" => true,
			"dataPoints"   => $dataTotalPoints
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
function admin_load_info_fund(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsFund = new Fund();
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	###
	$gId = Input::post('gId');
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$bank_account_id = (int) Input::post('bank_account_id', 0);
	###
	$start_year = 2023; $end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$cond = $cond_prev = $cnd = "`is_trash`=0";
	if($bank_account_id > 0) {
		$cnd.= " and `bank_account_id`='{$bank_account_id}'";
	}
	if($month > 0){
		$m = $label = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$start_date = strtotime(sprintf('01-%s-%s', $clsISO->parseNumber($month), $year));
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $month, $year));
		$cond.= " and (FROM_UNIXTIME(`account_date`, '%m/%Y')='{$m}')";
		$label = sprintf('%s/%s', $month, $year);
	} else {
		$label = sprintf('năm %s', $year);
		$start_date = strtotime(sprintf('01-01-%s', $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, 12, $year));
		$cond.= " and (FROM_UNIXTIME(`account_date`, '%Y')='{$year}')";
	}
	// Quỹ đầu kì
	$total_period = $total_period_prev = $clsFund->getTotalStat($bank_account_id);
	#- Tổng thu đầu quỹ
	$total_income = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCTHU' and `account_date`<'{$start_date}'");
	$total_expense = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCCHI' and `account_date`<'{$start_date}'");
	$total_period+= ($total_income - $total_expense);
	// Tổng thu
	$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance = $total_period + $total_income - $total_expense;
	if($bank_account_id > 0 && $end_date > 0){
		$total_balance += $clsFund->getTotalTrans($bank_account_id, $end_date);
	}
	// So sánh
	if($month > 0){
		if($month == 1){
			$prev_month = 12;
			$prev_year = ($year-1);
		} else {
			$prev_month = ($month - 1);
			$prev_year = $year;
		}
		$m_prev = sprintf('%s/%s', $clsISO->parseNumber($prev_month), $prev_year);
		$end_day_prev = cal_days_in_month(CAL_GREGORIAN, $prev_month, $prev_year);
		$start_date_prev = strtotime(sprintf('01-%s-%s', $clsISO->parseNumber($prev_month), $prev_year));
		$end_date_prev = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day_prev, $prev_month, $prev_year));
		$cond_prev.= " and (FROM_UNIXTIME(`account_date`, '%m/%Y')='{$m_prev}')";
	} else {
		$prev_year = ($year - 1);
		$start_date_prev = strtotime(sprintf('01-01-%s', $prev_year));
		$end_day_prev = cal_days_in_month(CAL_GREGORIAN, 12, $prev_year);
		$end_date_prev = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day_prev, 12, $prev_year));
		$cond_prev.= " and (FROM_UNIXTIME(`account_date`, '%Y')='{$prev_year}')";
	}
	#- Tổng thu đầu quỹ
	$total_income_prev = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCTHU' and `account_date`<'{$start_date_prev}'");
	$total_expense_prev = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCCHI' and `account_date`<'{$start_date_prev}'");
	$total_period_prev+= ($total_income_prev - $total_expense_prev);
	// Tổng thu
	$total_income_prev = $clsFund->sumItem("amount", "{$cond_prev} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense_prev = $clsFund->sumItem("amount", "{$cond_prev} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance_prev = $total_period_prev + $total_income_prev - $total_expense_prev;
	if($bank_account_id > 0 && $end_date_prev > 0){
		$total_balance_prev += $clsFund->getTotalTrans($bank_account_id, $end_date_prev);
	}
	$html = '<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card cursor-pointer">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/paypal.png" alt="cube" class="rounded">
					</div>
					<div class="dropdown">
						<button class="btn btn-icon p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
							<div class="p-3">
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-select" name="month" gId="'.$gId.'" 
										onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Tháng</option>';
										foreach($list_months as $_month){
											$html.= '<option'.($_month==$month?" selected":"").' value="'.$_month.'">Tháng '.$_month.'</option>';
										}
									$html.= '</select>
								</div>
								<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
									<select class="form-control form-select" name="year" gId="'.$gId.'" 
										onChange="$Core.dashboard.reload(this,event)">';
									foreach($list_years as $_year){
										$html.= '<option'.($_year==$year?" selected":"").' value="'.$_year.'">'.$_year.'</option>';
									}
									$html.= '</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Quỹ đầu kỳ '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_period).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_period, $total_period_prev).'
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card gotoLink cursor-pointer" href="/fund/report.html">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/cc-primary.png" alt="cube" class="rounded">
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Tồn quỹ '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_balance).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_balance, $total_balance_prev).'
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card gotoLink cursor-pointer" href="/fund/report.html">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/wallet.png" alt="cube" class="rounded">
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Tổng thu '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_income).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_income, $total_income_prev).'
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card gotoLink cursor-pointer" href="/fund/report.html">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/cc-success.png" alt="cube" class="rounded">
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Tổng chi '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_expense).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_expense, $total_expense_prev).'
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function admin_load_info_staff(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$lstAllBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$cond = "`is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	
	$block_id = vnSessionGetVar('BlockAdminId');
	$oneBLock = $lstAllBlock[$block_id];
	$purl = "";
	$pfirst = 0;
	if($block_id > 0) {
		$cond .= " and block_ids like '%|".$block_id."|%' ";
		$purl .= (($pfirst > 0)?"&":"?") . "block_id=".$block_id;
		$txt_title = 'Nhân viên chuyên dự án '.$oneBLock['property_code'];
	}else{
		$txt_title = 'Nhân viên';
	}
	$total_staffs = $clsProfile->countItem($cond);
	$total_staffs_month = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(reg_date,'%m/%Y')='".date('m/Y')."'");
	$html = '<div class="col-5">
		<h6 class="card-title mb-3 text-nowrap">'.$txt_title.'</h6>
		<h5 class="card-title text-main text-nowrap mb-1">'.$total_staffs.' nhân viên</h5>
		<small class="d-block mb-4 pb-1 text-muted">
			<strong class="text-success">+'.$total_staffs_month.'</strong> nhân viên mới tháng '.date('m/Y').'
		</small>
		<a href="/staff.html'.$purl .'" class="btn btn-sm btn-outline-primary">Danh sách</a>
	</div>
	<div class="col-7 ps-0">
		<div id="staffChart_'.$uid.'" class="chartContainer pt-4 w-100 h-px-125"></div>
	</div>';
	$labels = $series = array();
	$due_date = time();
	$start_date = strtotime('-12 months', $due_date); 
	for($i=$start_date; $i<=$due_date; $i = strtotime('+1 month', $i)){
		$labels[] = sprintf('T%s', date('n/y', $i));
		$series[] = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".date('m/Y', $i)."'");
	}
	$callback = 'var options = {
		chart: {
			height: 110,
			type: "bar",
			toolbar: {show: !1}
		},
		plotOptions: {
			bar: {
				barHeight: "60%",
				columnWidth: "50%",
				startingShape: "rounded",
				endingShape: "rounded",
				borderRadius: 3,
				distributed: !0
			}
		},
		grid: {
			show: !1,
			padding: {
				top: -35,
				bottom: -10,
				left: -10,
				right: -10
			}
		},
		colors: [
			config.colors.primary,
			config.colors.secondary,
			config.colors.info,
			config.colors.success,
			config.colors.warning,
			config.colors.danger,
			config.colors.black
		],
		tooltip: {
			custom: function({series, seriesIndex, dataPointIndex, w}) {
				return \'<div class="p-1">Nhân viên mới: \'+series[seriesIndex][dataPointIndex] + \'</div>\'
			}
		},
		dataLabels: {enabled: !1},
		series: [{data: ['.implode(',',$series).']}],
		legend: {show: !1},
		xaxis: {
			categories: [\''.implode('\',\' ', $labels).'\'],
			axisBorder: {show: !1},
			axisTicks: {show: !1},
			labels: {style: {fontSize: "11px"}}
		},
		yaxis: {
			labels: {show: !1}
		}
	};
	var chart = new ApexCharts(document.querySelector(\'#staffChart_'.$uid.'\'), options);
	chart.render()';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback
	)); die();
}
function admin_report_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$cond = "`is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	$total_staffs = $clsProfile->countItem($cond);
	$total_staffs_month = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(reg_date,'%m/%Y')='".date('m/Y')."'");
	$html = '<div class="col-5">
		<h6 class="card-title mb-3 text-nowrap">Nhân viên</h6>
		<h5 class="card-title text-main text-nowrap mb-1">'.$total_staffs.' nhân viên</h5>
		<small class="d-block mb-4 pb-1 text-muted">
			<strong class="text-success">+'.$total_staffs_month.'</strong> nhân viên mới tháng '.date('m/Y').'
		</small>
		<a href="javascript:void(0);" data-toggle="ripple" onClick="$Core.dashboard.open_report_stock_hug(this, event)" class="btn btn-sm btn-outline-primary">Danh sách</a>
	</div>
	<div class="col-7 ps-0">
		<div id="staffChart_'.$uid.'" class="chartContainer pt-4 w-100 h-px-125"></div>
	</div>';
	$labels = $series = array();
	$due_date = time();
	$start_date = strtotime('-12 months', $due_date); 
	for($i=$start_date; $i<=$due_date; $i = strtotime('+1 month', $i)){
		$labels[] = sprintf('T%s', date('n/y', $i));
		$series[] = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".date('m/Y', $i)."'");
	}
	$callback = 'var options = {
		chart: {
			height: 110,
			type: "bar",
			toolbar: {show: !1}
		},
		plotOptions: {
			bar: {
				barHeight: "60%",
				columnWidth: "50%",
				startingShape: "rounded",
				endingShape: "rounded",
				borderRadius: 3,
				distributed: !0
			}
		},
		grid: {
			show: !1,
			padding: {
				top: -35,
				bottom: -10,
				left: -10,
				right: -10
			}
		},
		colors: [
			config.colors.primary,
			config.colors.secondary,
			config.colors.info,
			config.colors.success,
			config.colors.warning,
			config.colors.danger,
			config.colors.black
		],
		tooltip: {
			custom: function({series, seriesIndex, dataPointIndex, w}) {
				return \'<div class="p-1">Nhân viên mới: \'+series[seriesIndex][dataPointIndex] + \'</div>\'
			}
		},
		dataLabels: {enabled: !1},
		series: [{data: ['.implode(',',$series).']}],
		legend: {show: !1},
		xaxis: {
			categories: [\''.implode('\',\' ', $labels).'\'],
			axisBorder: {show: !1},
			axisTicks: {show: !1},
			labels: {style: {fontSize: "11px"}}
		},
		yaxis: {
			labels: {show: !1}
		}
	};
	var chart = new ApexCharts(document.querySelector(\'#staffChart_'.$uid.'\'), options);
	chart.render()';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback
	)); die();
}
function admin_open_report_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsBilling = new Billing();
	$clsStockHug = new StockHug();
	$uid = $clsISO->getUniqid();
	$list_patterns = array(
		'_soled' => array(
			'title' => 'Số căn bán tính đến hiện tại',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_f1_deposited' => array(
			'title' => 'Số căn F1 KH đã cọc của tháng',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_cross_selling' => array(
			'title' => 'Số căn bán chéo',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_registed_hdmb_month' => array(
			'title' => 'Số đã ký HĐMB tính số của tháng',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_wait_sign_hdmb' => array(
			'title' => 'Số căn chờ ký HĐMB',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_not_sold' => array(
			'title' => 'Tổng số căn quỹ ôm đang chờ bán',
			'total_stock' => 0,
			'total_price' => 0
		)
	);
	foreach($list_patterns as $key => $val){
		if($key == '_soled'){
			$total_stock = $clsStockHug->countItem("`status_id`='"._STOCK_HUG_STATUS_SOLD_ID."'");
			$total_price = 0;
		} else if($key == '_f1_deposited'){
			$total_stock = 0;
			$total_price = 0;
		}
		$list_patterns[$key]['total_stock'] = $total_stock;
		$list_patterns[$key]['total_price'] = $total_price;
	}
	$smarty->assign('list_patterns', $list_patterns);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.report_stock_hug.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function admin_load_data_share(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsShare = new Share();
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$cond = "`t1`.`holderG`='share' and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`, '%m/%Y')='{$date_my}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`, '%Y')='{$year}'";
	}
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	###
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	$field = "COUNT(*) as `total_share`,`t2`.`profile_id`,`t2`.`first_name`,`t2`.`last_name`,`t2`.`full_name`";
	$list_shares = $dbconn->getAll("SELECT {$field} FROM {$clsShare->tbl} AS `t1` 
		INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`user_id`=`t2`.`profile_id` 
		WHERE {$cond} GROUP BY `t1`.`user_id` HAVING `total_share`>0 limit 0,15");
	if(!empty($list_shares)){
		foreach($list_shares as $key => $val){
			$staff_id = $val['profile_id'];
			$staff_name = ($deviceType=='phone') 
				? $clsProfile->getLastName($staff_id, $val, false) 
				: $clsProfile->getFullName($staff_id, $val);
			$dataPoints[] = array(
				'label'	=> $staff_name,
				'y'	=> $val['total_share']*1,
				"indexLabel" => "{y}",
			);
		}
		$ks = array_column($dataPoints, "y");
		array_multisort($ks, SORT_ASC, $dataPoints);
	}
	$data['type'] = 'bar';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'barChartData' => $barChartData
	)); die();
}
function admin_load_data_followups(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsShare = new Share();
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	##
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	$barChartData = array();
	$barChartData['axisX'] = array(
		'labelAngle' => -90,
		'interval' => 1
	);
	$list_props = $clsProperty->getCacheItems('FOLLOWUP_TYPE');
	if(!empty($list_props)){ $ii = 0;
		foreach($list_props as $key => $val){
			$dataPoints = array();
			$prop_id = $val[$clsProperty->pkey];
			if($month > 0){
				$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				for($day=1; $day<=$end_day; $day++){
					$date = sprintf('%s/%s/%s', $clsISO->parseNumber($day), $clsISO->parseNumber($month), $year);
					$cond = " and FROM_UNIXTIME(`date_id`, '%d/%m/%Y')='{$date}'";
					$total_followups = $clsFollowUp->countItem("`is_trash`=0 and `type_id`='{$prop_id}'".$cond);
					$dataPoints[] = array(
						'name' => $i,
						'y' => $total_followups*1
					);
				}
			} else {
				for($m=1; $m<=12; $m++){
					$date_my = sprintf('%s/%s', $clsISO->parseNumber($m), $year);
					$cond = " and FROM_UNIXTIME(`date_id`, '%m/%Y')='{$date_my}'";
					$total_followups = $clsFollowUp->countItem("`is_trash`=0 and `type_id`='{$prop_id}'".$cond);
					$dataPoints[] = array(
						'name' => sprintf('T%s', $m),
						'y' => $total_followups*1,
					);
				}
			}
			$barChartData['data'][] =  array(
				"type" => ($ii==1 ? "spline" : "column"),
				"indexLabel" => "{y}",
				"toolTipContent" => $val['title'].': {y}',
				"color"	 => $val['bgcolor'],
				"name"    => $val['title'],
				"showInLegend"    => true,
				"dataPoints" => $dataPoints
			);
			++$ii;
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function admin_load_data_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	#
	$uid = $clsISO->getUniqid();
	$year  = (int) Input::post('year', date('Y'));
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	#
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	for($i=1; $i<=12; $i++){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
		$total_customer = $clsCustomer->countItem("`is_trash`=0 and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'");
		$dataPoints[] = array(
			'label' => sprintf('T%s', $i),
			'y' => $total_customer*1,
			'indexLabel' => $total_customer
		);
	}
	$data['type'] = 'column';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'barChartData' => $barChartData
	)); die();
}
function admin_load_sales_overview(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$is_bigger = (int) Input::post('is_bigger', 0);
	$date_type = Input::post('date_type', "_month");
	###
	$cond = $prev_cond = "`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0";
	if($month > 0){
		if($date_type == '_quater'){
			if($month == 1){
				$start_month = 1;
				$end_month = 3;
			} else if($month == 2){
				$start_month = 4;
				$end_month = 6;
			} else if($month == 3){
				$start_month = 7;
				$end_month = 9;
			} else {
				$start_month = 9;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$start_date_prev = strtotime("-6 months", $start_date);
			$end_date_prev = strtotime("-6 months", $end_date);
			$cond.= " and (`deposit_date` between {$start_date} AND {$end_date})";
			$prev_cond.= " and (`deposit_date` between {$start_date_prev} AND {$end_date_prev})";
			$compare_text = "quý trước";
		} else if($date_type == '_half'){
			if($month == 1){
				$start_month = 1;
				$end_month = 6;
			} else {
				$start_month = 7;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$start_date_prev = strtotime("-6 months", $start_date);
			$end_date_prev = strtotime("-6 months", $end_date);
			$cond.= " and (`deposit_date` between {$start_date} AND {$end_date})";
			$prev_cond.= " and (`deposit_date` between {$start_date_prev} AND {$end_date_prev})";
			$compare_text = "6 tháng trước";
		} else {
			if($month == 1){
				$prev_month = 12;
				$prev_year = ($year - 1);
			} else {
				$prev_month = ($month - 1);
				$prev_year = $year;
			}
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$date_prev_my = sprintf('%s/%s', $clsISO->parseNumber($prev_month), $prev_year);
			$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
			$prev_cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_prev_my}'";
			$compare_text = sprintf('tháng %s', $date_prev_my);
		}
	} else {
		$prev_year = ($year - 1);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$prev_cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$prev_year}'";
		$compare_text = sprintf('năm %s', $prev_year);
	}
	$block_id = vnSessionGetVar('BlockAdminId');
//	var_dump($_SESSION);die;
	if($block_id > 0) {
		$cond .= " AND stock_code IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
		$prev_cond.= " AND stock_code IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
	}
	$total_sales = $clsBilling->sumItem("totalgrand", $cond);
	$total_billings = $clsBilling->countItem($cond);
	$total_prev_sales = $clsBilling->sumItem("totalgrand", $prev_cond);
	$total_prev_billings = $clsBilling->countItem($prev_cond);
	$col = ($is_bigger==1) ? 6 : 5; 
	$html = '<div class="row">
		<div class="col-'.$col.' border-end">
			<h6 class="mb-2 text-muted">Doanh số</h5>
			<h5 class="card-title fs-3 text-main mb-1">'.$clsISO->shortNumber($total_sales,3).'</h5>
			';
		if($total_prev_billings > 0) {
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				'.$clsHelper->getHtmlGrowth($total_sales, $total_prev_sales).'</strong> so với '.$compare_text.'</small>';
		}else{
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				+ '.$clsISO->shortNumber($total_sales,3).'</strong> so với '.$compare_text.'</small>';
		}
	$html.='</div>
		<div class="col-'.$col.'">
			<h6 class="mb-2 text-muted">Số lượng</h5>
			<h5 class="card-title fs-3 text-main mb-1">'.$total_billings.' GD</h5>
			';
		if($total_prev_billings > 0) {
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				'.$clsHelper->getHtmlGrowth($total_billings, $total_prev_billings).'</strong> so với '.$compare_text.'</small>';
		}else{
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				+ '.$total_billings.' GD</strong> so với '.$compare_text.'</small>';
		}
	$html .='</div>
		'.($is_bigger==0?'<div class="col-2 pt-1 ps-0">
			<img src="'.URL_IMAGES.'/prize-light.png" height="80" class="rounded-start" alt="View Sales">
		</div>':'').'
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}

function admin_load_log_check_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsMember = new Member();
	$clsProperty = new Property();
	###
	$lstAllBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$block_id = vnSessionGetVar("BlockAdminId");
	$oneBlock = $lstAllBlock[$block_id];
	$cond = "";
	if($block_id > 0) {
		$cond .= " AND target_id IN (SELECT stock_id FROM default_stock WHERE block_id='".$block_id."')";
	}
	
	
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$fs = sprintf('%s/%s', $clsISO->parseNumber($month), date('y'));
	$total_members_all = $clsMember->countItem("`profile_type`='MOC'"); 
	$total_logs_all = $clsLog->countItem("(`type`='view_stock' or `type`='view')".$cond);
	$total_members = $clsMember->countItem("`profile_type`='MOC' and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$f."'"); 
	$total_logs = $clsLog->countItem("(`type`='view_stock' or `type`='view') and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$f."'".$cond); 
	
	$html = '<div class="d-flex flex-wrap gap-1">
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng user</h5> 
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_members_all.'">'.$clsISO->formatNumber2($total_members_all).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng tra cứu '.$oneBlock["property_code"].'</h5>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_logs_all.'">'.$clsISO->formatNumber2($total_logs_all).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng user T.'.$month.'</h5> 
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_members.'" data-speed="1000">'.$clsISO->formatNumber2($total_members).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tra cứu '.$oneBlock["property_code"].' T.'.$month.'</h5>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_logs.'" data-speed="1000">'.$clsISO->formatNumber2($total_logs).'</span>
			</h3>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function admin_load_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsStockHug = new StockHug();
	$list_blocks = array(
		'_TOTAL' => array(
			'title' => 'Tổng quỹ', 
			'subtitle' => 'Tổng số cọc đã cọc vào CĐT'
		),
		'_SOLD' => array(
			'title' => 'Đã bán', 
			'subtitle' => 'Tổng số cọc đã bán được'
		),
		'_NOT_SOLD' => array(
			'title' => 'Chưa bán', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_UNLOCK' => array(
			'title' => 'Chờ hoàn', 
			'subtitle' => 'Tổng số cọc huỷ căn chờ hoàn'
		),
		'_BALANCE' => array(
			'title' => 'Tồn cọc', 
			'subtitle' => 'Tổng tồn cọc của LM trong dự án Masteri'
		)
	);
	$cond= "is_trash=0";
	$html = '<div class="d-flex flex-wrap gap-1">';
	foreach($list_blocks as $key => $val){
		if($key == '_TOTAL'){
			$where = $cond;
		} else if($key == '_SOLD'){
			$props = $clsISO->make_attrs_builder(array(
				'field' => 'status_id',
				'status_id' => _STOCK_HUG_STATUS_SOLD_ID
			));
			$onClick = '$Core.stock_hug.set_status(this, event)';
			$where = $cond." and `status_id`='"._STOCK_HUG_STATUS_SOLD_ID."'";
		} else if($key == '_NOT_SOLD'){
			$props = $clsISO->make_attrs_builder(array(
				'field' => 'status_id',
				'status_id' => _STOCK_HUG_STATUS_DEF_ID
			));
			$onClick = '$Core.stock_hug.set_status(this, event)';
			$where = $cond." and `status_id`='"._STOCK_HUG_STATUS_DEF_ID."' 
				and `status_id`<>'"._STOCK_HUG_STATUS_UNLOCK_ID."'";
		} else if($key == '_UNLOCK'){
			$props = $clsISO->make_attrs_builder(array(
				'field' => 'status_id',
				'status_id' => _STOCK_HUG_STATUS_UNLOCK_ID
			));
			$onClick = '$Core.stock_hug.set_status(this, event)';
			$where = $cond." and `status_id`='"._STOCK_HUG_STATUS_UNLOCK_ID."'";
		} else if($key == '_BALANCE'){
			$where = $cond." and (`status_id`='"._STOCK_HUG_STATUS_UNLOCK_ID."' 
				or `status_id`='"._STOCK_HUG_STATUS_DEF_ID."')";
		}
		$total = $clsStockHug->countItem($where);
		$html.= '<div class="gbox gotoLink flex-fill px-2 py-3">
			<div class="d-flex mb-2 align-items-center justify-content-between">
				<h5 class="mb-0 fs-14">'.$val['title'].'</h5> 
				<a data-bs-toggle="tooltip" data-bs-toggle="hover" class="panel-help help_pop" title="'.$val['subtitle'].'">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="1089">'.$total.'</span>
			</h3>
		</div>';
	}
	$html .= '</div>';	
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function admin_load_sale_building(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	
	$total_F1_MWF = $total_billing = $total_MWF_CHEO = $_F1_HIGH_BUILDING = $_HIGH_BUILDING_CHEO = 0;
	$type  = Input::post('type', "total");
	$month  = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	###
	$cond = " AND `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`='0' and `t1`.billing_type IN ("._BILLING_TYPE_MWF_ID.","._BILLING_TYPE_CT_ID.") ";
	$order_by = " order by `t1`.`reg_date` DESC";
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	$lstBilling = $clsBilling->getAll($cond.$order_by);	
	$lstBilling = $dbconn->getAll("SELECT `t1`.*,`t2`.agency_id,`t2`.stock_id FROM default_billing as t1 LEFT JOIN default_stock as t2 ON `t1`.`stock_code`=`t2`.`ms_code` WHERE 1=1 ".$cond.$order_by);	
	$arr_agent = $clsProperty->getArraySearchByKey("_AGENCY");
	$arr_cache = [];
	foreach($lstBilling as $key => $value){
		if($value['billing_type'] == _BILLING_TYPE_MWF_ID && $value['billing_source_id'] == _BILLING_RESOURCE_F1_ID){
			++$total_F1_MWF;
		}
		if($value['billing_type'] == _BILLING_TYPE_MWF_ID && $value['billing_source_id'] == _BILLING_RESOURCE_LC_ID){
			++$total_MWF_CHEO;
		}
		if($value['billing_type'] == _BILLING_TYPE_CT_ID && $value['billing_source_id'] == _BILLING_RESOURCE_F1_ID){
			++$_F1_HIGH_BUILDING;
		}
		if($value['billing_type'] == _BILLING_TYPE_CT_ID && $value['billing_source_id'] == _BILLING_RESOURCE_LC_ID){
			++$_HIGH_BUILDING_CHEO;
		}
		$total_billing += (int)$value['totalgrand'];
		if($value['billing_type'] == _BILLING_TYPE_MWF_ID){
			$lstBilling[$key]['project_name'] = "MWF";
		}else if($value['billing_type'] == _BILLING_TYPE_CT_ID){
			$lstBilling[$key]['project_name'] = "VHOP";
		}else{
			$lstBilling[$key]['project_name'] = "--";
		}
		if($value['billing_source_id'] == _BILLING_RESOURCE_F1_ID){
			$lstBilling[$key]['source_name'] = "F1";
		}else if($value['agency_id'] > 0){
			$lstBilling[$key]['source_name'] = $arr_agent[$value['agency_id']]['title'];
		}else{
			$lstBilling[$key]['source_name'] = "--";
		}
	}
//	var_dump($lstBilling);die;
	
	$list_blocks = array(
		'_TOTAL_BUILDING_SALE' => array(
			'title' => 'Tổng số căn bán', 
			'subtitle' => 'Tổng số căn đã bán'
		),
		'_TOTAL_BILLING' => array(
			'title' => 'Tổng DS', 
			'subtitle' => 'Tổng doanh số'
		),
		'_F1_MWF' => array(
			'title' => 'MWF F1', 
			'subtitle' => 'Tổng số căn Masterise F1 đã bán'
		),
		'_MWF_CHEO' => array(
			'title' => 'MWF lấy chéo', 
			'subtitle' => 'Tổng số căn Masterise lấy chéo đã bán'
		),
		'_F1_HIGH_BUILDING' => array(
			'title' => 'Cao tầng Vin F1', 
			'subtitle' => 'Tổng số căn cao tầng Vin F1 đã bán'
		),
		'_HIGH_BUILDING_CHEO' => array(
			'title' => 'Cao tầng Vin lấy chéo', 
			'subtitle' => 'Tổng số căn cao tầng Vin lấy chéo đã bán'
		)
	);
	if($type == "total") {
		$html = '<div class="form-row d-flex flex-wrap gap-1">';	
		foreach($list_blocks as $key => $val){
			if($key == "_TOTAL_BUILDING_SALE") {
				$total = $total_F1_MWF+$total_MWF_CHEO+$_F1_HIGH_BUILDING+$_HIGH_BUILDING_CHEO;
			}else if($key == "_TOTAL_BILLING") {
				$total = $clsISO->formatNumberToEasyRead($total_billing)."đ";
			}else if($key == "_F1_MWF") {
				$total = $total_F1_MWF;
			}else if($key == "_MWF_CHEO") {
				$total = $total_MWF_CHEO;
			}else if($key == "_F1_HIGH_BUILDING") {
				$total = $_F1_HIGH_BUILDING;
			}else if($key == "_HIGH_BUILDING_CHEO") {
				$total = $_HIGH_BUILDING_CHEO;
			}
			$html.= '<div class="gbox gotoLink flex-fill px-2 py-3">
				<div class="d-flex mb-2 align-items-center justify-content-between">
					<h5 class="mb-0 fs-14">'.$val['title'].'</h5> 
					<a data-bs-toggle="tooltip" data-bs-toggle="hover" class="panel-help help_pop" title="'.$val['subtitle'].'">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<h3 class="fs-5 mb-0 fw-bold text-main">
					<span data-from="0" data-to="1089">'.$total.'</span>
				</h3>
			</div>';
		}
		$html .= '</div>';
	}else if($type == "list"){
		$smarty->assign("lstBilling",$lstBilling);
		$html = $core->build('admin'.DS.'_ajax.loadSale.tpl');
	}
		
	// Return
	
	echo json_encode(array(
		'html' => $html,
	)); die();
}
function admin_load_group_sale_overview(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$uid = $clsISO->getUniqid();
	$month  = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	###
	$cond = "`is_trash`=0 and `is_cancel`=0 and `is_alliance`='0'";
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	$block_id = vnSessionGetVar('BlockAdminId');
	$lstBuilding = $clsProperty->getAllCache("property_type='_BUILDING' AND for_id='{$block_id}'");
	foreach ($lstBuilding as $key => $value) {
		$slug = ($value['slug_vn'] !="")?$value['slug_vn']:$value['slug'];
		$title = ($value['title_vn'] !="")?$value['title_vn']:$value['title'];
		$list_home_blocks[$slug] = [
			'title'	=>	$title,
			'building_id'	=>	$value['property_id'],
		];
		unset($slug,$title);
	}
	foreach($list_home_blocks as $key => $val){
		$where = $cond. "  AND stock_code IN (SELECT ms_code FROM default_stock WHERE building_id='{$val['building_id']}')";
		$total_billings = $clsBilling->countItem($where);
		$total_f1_billings = $clsBilling->countItem("{$where} and `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'");
		$total_f1_registed_billings = $clsBilling->countItem("{$where} and `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."' and `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'");
		$total_price = $clsBilling->sumItem("totalgrand", $where);
		$list_home_blocks[$key]['total_billings'] = $total_billings;
		$list_home_blocks[$key]['total_price'] = $total_price;
		$list_home_blocks[$key]['total_f1_billings'] = $total_f1_billings;
		$list_home_blocks[$key]['total_f1_registed_billings'] = $total_f1_registed_billings;
	}
	$html = '<div class="form-row">'; $ii = 0;
	foreach($list_home_blocks as $key => $val){
		$html.= '<div class="col-2 flex-fill text-center'.(( $ii < (count($list_home_blocks)-1))?' border-end ':'').' pt-2" style="min-height:80px">
			<h6 class="mb-3 text-muted">'.$val['title'].'</h5>
			<h5 class="card-title fs-4 text-nowrap text-main mb-0 fw-semibold">'.$val['total_billings'].' GD</h5>
		</div>';
		++$ii;
	}
	$html.= '</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function admin_load_report_update_stock(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsAdminLog = new AdminLog();
	$clsProperty = new Property();
	###
	$end_date = time();
	$start_date = strtotime('-6 days', $end_date);
	$list_dates = array();
	for($i=$start_date; $i<= $end_date; $i = strtotime("+1 day", $i)){
		$list_dates[] = $i;
	}
	$html = '<div class="overflow-x-auto w-100">
		<table class="table table-bordered">
		<thead><tr>';
		foreach($list_dates as $val){
			$html.= '<th class="align-center text-center">'.$clsISO->convertTimeToText($val).'</th>';
		}
		$html.= '</tr></thead>';
		$total_agency = $clsProperty->countItem("`is_trash`=0 AND `property_type`='_AGENCY' AND JSON_EXTRACT(`more_information`,\"$.spreadsheetId\")<>''");
		foreach($list_dates as $val){
			$total_updated = $dbconn->getOne("SELECT COUNT(DISTINCT `target_id`) as `total_updated` 
				FROM {$clsAdminLog->tbl} WHERE`action`='update_stock' AND FROM_UNIXTIME(`date`,'%d/%m/%Y')='".date('d/m/Y', $val)."'");
			$html.= '<td class="align-center text-center"><strong class="text-main">'.$total_updated.'</strong>/<strong class="text-orange">'.$total_agency.'</strong></td>';
		}
		$html.= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
/*dashboard admin */
function admin_loadBlockAdmin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$type = "STOCK";
	$arr_status = $clsProperty->getArraySearchByKey("_STATUS");
	###
	$block_id = (int)Input::post("block_id",0);
	vnSessionSetVar('BlockAdminId',$block_id);	
	
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$block_name = $lstBlock[$block_id]['property_code'];
	// Return
	echo json_encode(array(
		'result' => true,
		'block_name'	=>	$block_name
	)); die();
}
/*end dashboard admin */