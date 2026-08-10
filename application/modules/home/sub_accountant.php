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
function accountant_load_income_expend(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsLog = new Log();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsFund = new Fund();
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
	$cond= "is_trash=0";
	#
	$list_ranges = array();
	if($month == 0){
		$f = '%m/%Y';
		$to_month = time();
		$start_month = strtotime('-6 months', $to_month);
		for($i = $start_month; $i <= $to_month; $i = strtotime('+1 month', $i)){
			$list_ranges[] = date('m/Y', $i);
		}
		$cond.= " and FROM_UNIXTIME(`account_date`,'%Y')='{$year}'";
	} else {
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);	
		$cond.= " and FROM_UNIXTIME(`account_date`,'%m/%Y')='{$date_my}'";
		$f = '%d/%m/%Y';
		$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1; $i<= $number_day; $i++){
			$list_ranges[] = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
		}
	}
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	$barChartData['zoomEnabled'] = true;
	$barChartData['zoomType'] = "xy";
	$barChartData['animationEnabled'] = true;
	$barChartData['axisY'] = array(
		'title' => 'Thu chi',
		'titleFontSize' => '18',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	$barChartData['axisY2'] = array(
		'title' => 'Chi',
		'titleFontColor' => '#C00000',
		'titleFontSize' => '18',
		'lineColor' => '#C00000',
		'labelFontColor' => '#C00000',
		'tickColor' => '#C00000',
	);
	foreach($list_ranges as $date){
		$total_income = $clsFund->sumItem("amount","{$cond} and `gr`='THUCTHU' and FROM_UNIXTIME(`account_date`,'{$f}')='{$date}'");
		$total_expend = $clsFund->sumItem("amount","{$cond} and `gr`='THUCCHI' and FROM_UNIXTIME(`account_date`,'{$f}')='{$date}'");
		$dataPointsIncome[] = array(
			'label'	=> sprintf('%s', $date),
			'y'	=> $total_income*1,
			'indexLabel' => $clsISO->shortNumber($total_income)
		);
		$dataPointsExpend[] = array(
			'label'	=> sprintf('%s', $date),
			'y' => $total_expend*1,
			'indexLabel' => $clsISO->shortNumber($total_expend)
		);
	}
	$barChartData['data'] = array(
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"name"	=> "Thực thu",
			"color"	 => "#1d6a01",
			"showInLegend" => true,	
			"yValueFormatString"	=> "Thu: #,###đ",
			"dataPoints"   => $dataPointsIncome
		),
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			"color"	 => "#C00000",
			"name"    => "Thực chi",
			"showInLegend"    => true,	
			"yValueFormatString"	=> "Chi: #,###đ",
			"dataPoints"    => $dataPointsExpend
		),
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
function accountant_load_month(){
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
function accountant_load_department_billing(){
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
function accountant_load_info_fund(){
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
function accountant_load_info_staff(){
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
	}
	$total_staffs = $clsProfile->countItem($cond);
	$total_staffs_month = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(reg_date,'%m/%Y')='".date('m/Y')."'");
	$html = '<div class="col-5">
		<h6 class="card-title mb-3 text-nowrap">Nhân viên chuyên dự án '.$oneBLock['property_code'].'</h6>
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
function accountant_report_stock_hug(){
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
function accountant_open_report_stock_hug(){
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
function accountant_load_data_share(){
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
function accountant_load_data_followups(){
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
function accountant_load_data_customer(){
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
function accountant_load_income_expenditure(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$type  = Input::post('type', "");
	 //===================
	$clsFund = new Fund();
	$clsProperty = new Property();
	$smarty->assign('clsFund', $clsFund);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$cnd = $cond = $prev_cond = "is_trash=0";
	$date_range = Input::post('date_range');
	$tmp = @explode('-', $date_range);
	$start_date = $tmp[0]; $end_date = $tmp[1];
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_date = !empty($end_date) ? $clsISO->toTime($end_date." 23:59:59") : 0;
	#
	$cond2 = $cond;
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);		
		$cnd.= " and FROM_UNIXTIME(`account_date`,'%m/%Y')='{$date_my}'";
		$cond.= " and FROM_UNIXTIME(`account_date`,'%m/%Y')='{$date_my}'";
		$start_date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);
		$start_date = strtotime($start_date);
	} else {		
		$cnd.= " and FROM_UNIXTIME(`account_date`,'%Y')='{$year}'";
		$cond.= " and FROM_UNIXTIME(`account_date`,'%Y')='{$year}'";
		$start_date = sprintf('01-01-%s', $year);
		$start_date = strtotime($start_date);
	}
	// Quỹ đầu kì
	$total_period = $clsFund->getTotalStat();
	#- Tổng thu đầu quỹ
	$total_income = $clsFund->sumItem("amount", "{$cond2} and `gr`='THUCTHU' and `account_date`<'{$start_date}'");
	$total_expense = $clsFund->sumItem("amount", "{$cond2} and `gr`='THUCCHI' and `account_date`<'{$start_date}'");
	$total_period+= ($total_income - $total_expense);
	// Tổng thu
	$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance = $total_period + $total_income - $total_expense;
	if($type == "_TOTAL_PERIOD") {
		$total = $total_period;
	}else if($type == "_TOTAL_INCOME") {
		$total = $total_income;
	}else if($type == "_TOTAL_EXPENSE") {
		$total = $total_expense;
	}else if($type == "_TOTAL_BALANCE") {
		$total = $total_balance;
	}
	$html = '
			<h5 class="card-title fs-3 text-main mb-1">'.$clsISO->formatNumberToEasyRead($total).'đ</h5>
			';
	// Return
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function accountant_load_reportIncomeExpend(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$type = Input::post("type","THUCTHU");
	$uid = $clsISO->getUniqid();
	$cond = $html = "";
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	if($type == "THUCTHU") {
		$listCat_THUCTHU = $clsProperty->getCacheItems('THUCTHU');
		if($month == 0){
			$cond.= "FROM_UNIXTIME(`account_date`,'%Y')='{$year}'";
		} else {
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= "FROM_UNIXTIME(`account_date`,'%m/%Y')='{$m}'";
		}
		$html = '<div class="form-row d-flex flex-wrap">';
		foreach($listCat_THUCTHU as $key => $value) {
			$total_amount = $clsFund->sumItem("amount", $cond." and gr='THUCTHU' and type_id='{$value['property_id']}'");
			$html .= '<div class="col-6 col-md-3 col-lg-2 mb-2 flex-fill">
						<div class="gbox gotoLink flex-fill px-2 py-3 h-100">
							<h5 class="mb-3 fs-14">'.$value["title"].'</h5> 
							<h3 class="fs-16 mb-0 fw-bold text-main">
								<span data-from="0" data-to="'.$total_amount.'">'.$clsISO->formatNumber2($total_amount).'đ</span>
							</h3>
						</div>
					</div>';
			$dataPoints[] = array(
				'label' => $value["title"],
				'y' => $total_amount*1,
				'indexLabel' => $clsISO->shortNumber($total_amount)
			);
		}
		$html .= '</div>';
	}else if($type == "THUCCHI") {
		$listCat_THUCCHI = $clsProperty->getCacheItems('THUCCHI');
		if($month == 0){
			$cond.= "FROM_UNIXTIME(`account_date`,'%Y')='{$year}'";
		} else {
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= "FROM_UNIXTIME(`account_date`,'%m/%Y')='{$m}'";
		}
		$html = '<div class="form-row d-flex flex-wrap">';
		foreach($listCat_THUCCHI as $key => $value) {
			$total_amount = $clsFund->sumItem("amount", $cond." and gr='THUCCHI' and type_id='{$value['property_id']}'");
			$html .= '<div class="col-6 col-md-3 col-lg-2 mb-2 flex-fill">
						<div class="gbox gotoLink flex-fill px-2 py-3 h-100">
							<h5 class="mb-3 fs-14">'.$value["title"].'</h5> 
							<h3 class="fs-16 mb-0 fw-bold text-main">
								<span data-from="0" data-to="'.$total_amount.'">'.$clsISO->formatNumber2($total_amount).'đ</span>
							</h3>
						</div>
					</div>';
			$dataPoints[] = array(
				'label' => $value["title"],
				'y' => $total_amount*1,
				'indexLabel' => $clsISO->shortNumber($total_amount)
			);
		}
		$html .= '</div>';
	}
	$data['type'] = 'bar';
	$data['showInLegend'] = false;
	$data['indexLabel'] = '{y}';
	$data['legendText'] = '{label}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	###
	$callback = '$Core.chart.canvas(\'chart_'.$type.'_'.$uid.'\',respJson.barChartData);';
	// Return
	$html = '<div id="chart_'.$type.'_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
	// Return
	echo json_encode(array(
		'html' => $html,
		'type'	=>	$type
	)); die();
}
function accountant_load_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsVAT = new VAT();
	$clsProperty = new Property();	
	$type = Input::post("type","VAT_THU");
	$vat_type = 0;
	if($type == "VAT_THU") {
		$vat_type = _VAT_TYPE_VATOUT;
	}else if($type == "VAT_CHI"){
		$vat_type = _VAT_TYPE_VATIN;
	}
	$cond = "`is_trash`=0";	
	if($vat_type > 0) $cond.= " and `vat_type`='{$vat_type}'";
	$list_briefs = array(
		'_TOTAL' => 'Tổng',
		'_FINAL' => 'Đã tất toán',
		'_UNPAID' => 'Chưa thanh toán',
		'_DEPOSIT' => 'Đã tạm ứng' 
	);
	$html_briefs = '<div class="form-row d-flex flex-wrap">';
	foreach($list_briefs as $key => $text){
		if($key == '_TOTAL'){
			$total = $clsVAT->sumItem("amount", $cond);
		} else if($key=='_FINAL'){
			$total = $clsVAT->sumItem("final_price", $cond);
		} else if($key == '_UNPAID'){
			$total = $clsVAT->sumItem("unpaid_price", $cond);
		} else if($key == '_DEPOSIT'){
			$total = $clsVAT->sumItem("deposit_price", $cond);
		}
		$html_briefs .= '<div class="col-6 mb-2"><div class="gbox gotoLink px-2 py-3">
							<h5 class="mb-2 fs-14">'.$text.'</h5> 
							<h3 class="fs-5 mb-0 fw-bold text-main">
								<span data-from="0" data-to="'.$total.'">'.$clsISO->formatNumber2($total).'đ</span>
							</h3>
						</div></div>';
	}
	$html_briefs .= '</div>';
	// Return
	echo json_encode(array(
		'html' => $html_briefs,
		'type'	=>	$type
	)); die();
}
function accountant_load_stock_hug(){
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
function accountant_load_group_sale_overview(){
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
function accountant_load_report_update_stock(){
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
function accountant_loadMostRecentIncomeExpend(){
	global $smarty,$core,$clsISO,$oneProfile; 
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$smarty->assign('clsFund', $clsFund);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$cnd = $cond = "is_trash=0";
	$type = Input::post("type","THUCTHU");
	$cond .= " AND `gr`='{$type}'";
	#- End pagination	
	$list_funds = $clsFund->getAll($cond." order by `reg_date` DESC LIMIT 0,6");
	$html = "";
	if(!empty($list_funds)){
		$arr_property_cached = array();
		foreach($list_funds as $key => $val){
			$gr = $val['gr'];
			$amount = $val['amount'];
			//$reg_date = $val['reg_date'];
			$account_date = $val['account_date'];
			$bank_account_id = $val['bank_account_id'];
			if(isset($arr_property_cached[$bank_account_id])){
				$bank_account_name = $arr_property_cached[$bank_account_id];
			} else {
				$bank_account_name = $clsProperty->getTitle($bank_account_id);
				$arr_property_cached[$bank_account_id] = $bank_account_name;
			}
			$list_funds[$key]['bank_account_name'] = $bank_account_name;
			$list_funds[$key]['balance'] = $clsFund->getTotalBalance($bank_account_id, $account_date, $gr, $amount);
			$html.= '<li class="d-flex mb-3 pb-1">
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
							<h6 class="mb-0 d-flex gap-2 align-items-center"><a class="text-main" href="javascript:void(0);" onClick="$Core.accountant.view(this,event)" gr="'.$val['gr'].'" fund_id="'.$val['fund_id'].'">'.$val['code'].'</a><small class="text-muted d-block">'.date("d/m/Y H:i",$val['account_date']).'</small></h6>								
							<div class="user-progress d-flex align-items-center gap-1">
								<h6 class="mb-0">'.$clsISO->shortNumber($val['amount']).'</h6>
							</div>
						</div>
						<small class="text-muted d-block">'.$val['content'].'</small>
					</div>
				</li>';
		}
	}
	echo json_encode(array(
		'html' => $html
	)); die();
}