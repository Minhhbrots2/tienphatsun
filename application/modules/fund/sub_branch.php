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
function branch_branch(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	###
	$clsSetting = new Setting();
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$list_company = $clsSetting->getCacheItems('_GROUP_COMPANY');
	$smarty->assign('list_company', $list_company);
	$smarty->assign('list_preloaders', $list_preloaders);
	$smarty->assign('clsSetting', $clsSetting);
	###
	$list_blocks = [
		'total' => array(
			'title' => 'Tổng chi phí',
			'bgcolor' => '#410256'
		)
	];
	$lst_opex_category = $clsSetting->getArraySearchByKey("_OPEX_CATEGORY");
	foreach ($lst_opex_category as $key => $val) {
		$list_blocks[$val["setting_code"]] = [
			"title"	=>	$val["title"],
			"bgcolor"	=>	$val["bgcolor"],
		];
	}
	$smarty->assign('list_blocks', $list_blocks);
	
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$assign_list['prev_year'] = $prev_year;
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = sprintf('%s-01-01', $current_year);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_date = sprintf('%s-12-%s', $current_year, $end_day);
	$assign_list['start_date'] = $start_date;
	$assign_list['end_date'] = $end_date;
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	/*=============Title & Description Page==================*/
	$title_page = 'Chi phí - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function branch_load_total_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$sub,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
	
	/*$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);
		$time_prev = strtotime("-1 months",strtotime($date));
		$my_prev = date("m-Y",$time_prev);
		$cond = " FROM_UNIXTIME(`t2`.`accounting_date`,'%m-%Y')='{$my}'";
		$cond_prev = " FROM_UNIXTIME(`t2`.`accounting_date`,'%m-%Y')='{$my_prev}'";		
	} else {
		$date = sprintf('01-01-%s', $year);
		$time_prev = strtotime("-1 years",strtotime($date));
		$year_prev = date("Y",$time_prev);
		$cond = " FROM_UNIXTIME(`t2`.`accounting_date`,'%Y')='{$year}'";
		$cond_prev = " FROM_UNIXTIME(`t2`.`accounting_date`,'%Y')='{$year_prev}'";
	}*/
	#
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_day = ((int)$end_day > date("d")) ? date("d") : $end_day;
	$start_current_year = sprintf('%s-01-01', $current_year);
	$end_current_year = sprintf('%s-12-%s', $current_year, $end_day);
	$start_date = Input::post("start_date",$start_current_year);
	$end_date = Input::post("end_date",$end_current_year);
	$arr_time = $clsMoney->getPreviousDate($start_date,$end_date);
	$start_time = strtotime($arr_time["start_time"]);
	$end_time = strtotime($arr_time["end_time"]);
	$start_time_prev = strtotime($arr_time["start_time_prev"]);
	$end_time_prev = strtotime($arr_time["end_time_prev"]);	
	$cond = " (`t2`.`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	$cond_prev = " (`t2`.`accounting_date` BETWEEN '{$start_time_prev}' AND '{$end_time_prev}')";	
	
	$arrOffice = $clsSetting->getArraySearchByKey("_OFFICE");
	$arrAccountREVENUE = $clsMoney->getAccountByType("REVENUE");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$tmp = $dbconn->getALl("SELECT 
		SUM(CASE 
				WHEN `t1`.`regional_id` IN (".implode(',',array_keys($arrOffice)).") AND `t1`.`account_id` IN (".implode(',',$arrAccountTHUCCHI).")
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_branch`,
		SUM(CASE 
				WHEN `t1`.`account_id` IN (".implode(',',$arrAccountREVENUE).") 
				THEN `t1`.`credit_amount` 
				ELSE 0 
			END) AS `total_revenue`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond}");
	$tmp_prev = $dbconn->getALl("SELECT 
		SUM(CASE 
				WHEN `t1`.`regional_id` IN (".implode(',',array_keys($arrOffice)).")  AND `t1`.`account_id` IN (".implode(',',$arrAccountTHUCCHI).")
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_branch`,
		SUM(CASE 
				WHEN `t1`.`account_id` IN (".implode(',',$arrAccountREVENUE).") 
				THEN `t1`.`credit_amount` 
				ELSE 0 
			END) AS `total_revenue`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond_prev}");
//	$clsISO->print_pre($tmp);die;
	$total_branch = $total_branch_prev = $total_revenue = $total_revenue_prev = 0;
	foreach ($tmp as $key => $val) {
		$total_branch += (int)$val["total_branch"];
		$total_revenue += (int)$val["total_revenue"];
	}
	foreach ($tmp_prev as $key => $val) {
		$total_branch_prev += (int)$val["total_branch"];
		$total_revenue_prev += (int)$val["total_revenue"];
	} 
	#
	$total_change = $total_branch - $total_branch_prev;
	$change_rate = ($total_branch_prev > 0) ? round($total_change*100/$total_branch_prev,1) : "";
	#
	$total_change_revenue = $total_revenue - $total_revenue_prev;
	$change_rate_revenue = ($total_revenue_prev > 0) ? round($total_change_revenue*100/$total_revenue_prev,1)."%" : "";
	#
	$cost_ratio = ($total_revenue > 0) ? round($total_branch*100/$total_revenue,1) : "";
	#
	$total_profit = $total_revenue - $total_branch;
	$total_profit_prev = $total_revenue_prev - $total_branch_prev;
	#
	$total_change_profit = $total_profit - $total_profit_prev;
	$change_rate_profit = ($total_profit_prev > 0) ? round($total_change_profit*100/$total_profit_prev,1)."%" : "";
	$smarty->assign("total_branch",$total_branch);
	$smarty->assign("total_change",$total_change);
	$smarty->assign("change_rate",$change_rate);
	$smarty->assign("total_revenue",$total_revenue);
	$smarty->assign("change_rate_revenue",$change_rate_revenue);
	$smarty->assign("cost_ratio",$cost_ratio);
	$smarty->assign("total_profit",$total_profit);
	$smarty->assign("change_rate_profit",$change_rate_profit);
	$html = $core->build($sub.DS."_ajax.load_total.tpl");
	echo json_encode(array(
		"html"	=>	$html
	));die;
}
function branch_load_report_regional(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$sub,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
	
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_day = ((int)$end_day > date("d")) ? date("d") : $end_day;
	$start_current_year = sprintf('%s-01-01', $current_year);
	$end_current_year = sprintf('%s-12-%s', $current_year, $end_day);
	$start_date = Input::post("start_date",$start_current_year);
	$end_date = Input::post("end_date",$end_current_year);
	$arr_time = $clsMoney->getPreviousDate($start_date,$end_date);
	$start_time = strtotime($arr_time["start_time"]);
	$end_time = strtotime($arr_time["end_time"]);
	$cond = " (`t2`.`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	
	
	$arr_dep_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT",0,"",0);
	$lstDep = $clsISO->buildTree($arr_dep_cached,_DEPARTMENT_SALE_ID,$clsProperty->pkey);
	$arrAccountREVENUE = $clsMoney->getAccountByType("REVENUE");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$arrAccount = $clsSetting->getArraySearchByKey("_ACCOUNT");
	$acc_revenue = $acc_thuc_chi = $acc_thuc_thu = [];
	foreach ($arrAccount as $key => $val) {
		if($clsISO->checkItemInArray($key,$arrAccountREVENUE)) {
			$acc_revenue[$key] = $val["title"];
		}
		if($clsISO->checkItemInArray($key,$arrAccountTHUCCHI)) {
			$acc_thuc_chi[$key] = $val["title"];
		}
		if($clsISO->checkItemInArray($key,$arrAccountTHUCTHU)) {
			$acc_thuc_thu[$key] = $val["title"];
		}
	}
	foreach ($lstDep as $key => $val) {
		$more_information = $val["more_information"];
		
		if(empty($more_information["is_business_area"])) {
			unset($lstDep[$key]);
		}
	}
	$tmp = $dbconn->getALl("SELECT `t1`.`regional_id`,`t1`.`account_id`, SUM(`t1`.`credit_amount`) AS `total_credit_amount`,SUM(`t1`.`debit_amount`) AS `total_debit_amount`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond} AND `t1`.`regional_id` IN (".implode(',',array_keys($lstDep)).") GROUP BY `t1`.`account_id`,`t1`.`regional_id` ");
	$arr_report = [];
	$total_revenue = $total_thuc_chi = 0;
	foreach ($tmp as $key => $val) {
		if($clsISO->checkItemInArray($val["account_id"],$arrAccountREVENUE)) {
			if(!isset($arr_report[$val["regional_id"]]["credit_amount"])) {
				$arr_report[$val["regional_id"]]["credit_amount"] = (int)$val["total_credit_amount"];
			}else{
				$arr_report[$val["regional_id"]]["credit_amount"] += (int)$val["total_credit_amount"];
			}
		}
		if($clsISO->checkItemInArray($val["account_id"],$arrAccountTHUCCHI)) {
			if(!isset($arr_report[$val["regional_id"]]["debit_amount"])) {
				$arr_report[$val["regional_id"]]["debit_amount"] = (int)$val["total_debit_amount"];
			}else{
				$arr_report[$val["regional_id"]]["debit_amount"] += (int)$val["total_debit_amount"];
			}
		}
	}
	foreach ($arr_report as $key => $val) {
		$total_revenue = !empty($val["credit_amount"]) ? (int)$val["credit_amount"] : 0;
		$total_chi = !empty($val["debit_amount"]) ? (int)$val["debit_amount"] : 0;
		$total_profit = $total_revenue - $total_chi;
		$total_profit_last = $total_profit * 0.8;
		$arr_report[$key]["total_profit"] = $total_profit;
		$arr_report[$key]["total_profit_last"] = $total_profit_last;
	}
	
	$smarty->assign("lstDep",$lstDep);
	$smarty->assign("arr_report",$arr_report);
	$smarty->assign("acc_revenue",$acc_revenue);
	$smarty->assign("acc_thuc_chi",$acc_thuc_chi);
	$smarty->assign("acc_thuc_thu",$acc_thuc_thu);
	$html = $core->build($sub.DS."_ajax.load_report_regional.tpl");
	echo json_encode(array(
		"html"	=>	$html
	));die;
}
function branch_load_branch_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	#- Tổng cả năm
	define('START_YEAR', 2025);
	$uid = $clsISO->getUniqid();	
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_day = ((int)$end_day > date("d")) ? date("d") : $end_day;
	$start_current_year = sprintf('%s-01-01', $current_year);
	$end_current_year = sprintf('%s-12-%s', $current_year, $end_day);
	$start_date = Input::post("start_date",$start_current_year);
	$end_date = Input::post("end_date",$end_current_year);
	$arr_time = $clsMoney->getPreviousDate($start_date,$end_date);
	$start_time = strtotime($arr_time["start_time"]);
	$end_time = strtotime($arr_time["end_time"]);
	$cond = " (`t2`.`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	
	$arr_offices = $clsSetting->getArraySearchByKey("_OFFICE");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$tmp = $dbconn->getALl("SELECT `t1`.`regional_id`, 
		SUM(CASE 
				WHEN `t1`.`regional_id` IN (".implode(',',array_keys($arr_offices)).") AND `t1`.`account_id` IN (".implode(',',$arrAccountTHUCCHI).")
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_branch`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond} GROUP BY `t1`.`regional_id`");
	$arr_expense = [];
	if(!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_expense[$val["regional_id"]] = $val["total_branch"];
		}
	}	
	
	$dataPoints = array();
	$data = $barChartData = array();
	$data['axisX'] = array(
		'interval' => 1
	);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	###
	$cond = "`is_trash`=0"; 
	if(!empty($arr_offices)){ $ii = 0;
		foreach($arr_offices as $key => $val){
			$total = !empty($arr_expense[$val[$clsSetting->pkey]]) ? $arr_expense[$val[$clsSetting->pkey]] : 0;
			$dataPoints[] = array(
				'y'	=> $total*1,
				'label'	=> $val["title"],
				'indexLabel' => $clsISO->shortNumber($total,0)
			);
			++$ii;
		}
	}
	$data['type'] = 'column';
	$data['indexLabel'] = '{symbol} - {y}';
	$data['yValueFormatString'] = '#,##0 đ';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];
	$callback = '$Core.chart.canvas(\''.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}

function branch_load_chart_branch(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	###
	$uid = $clsISO->getUniqid();
	###	
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_day = ((int)$end_day > date("d")) ? date("d") : $end_day;
	$start_current_year = sprintf('%s-01-01', $current_year);
	$end_current_year = sprintf('%s-12-%s', $current_year, $end_day);
	$start_date = Input::post("start_date",$start_current_year);
	$end_date = Input::post("end_date",$end_current_year);
	$arr_time = $clsMoney->getPreviousDate($start_date,$end_date);
	$start_time = strtotime($arr_time["start_time"]);
	$end_time = strtotime($arr_time["end_time"]);
	$cond = " (`t2`.`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	
	$arrOffice = $clsSetting->getArraySearchByKey("_OFFICE");
	$arrAccount = $clsSetting->getArraySearchByKey("_ACCOUNT");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$arr_dep_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	
	$tmp = $dbconn->getALl("SELECT `t1`.`account_id`, 
		SUM(CASE 
				WHEN `t1`.`regional_id` IN (".implode(',',array_keys($arrOffice)).") AND `t1`.`account_id` IN (".implode(',',$arrAccountTHUCCHI).")
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_branch`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond} GROUP BY `t1`.`regional_id`");
	$arr_expense = [];
	$total = 0;
	if(!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_expense[$val["account_id"]] = $val["total_branch"];
			$total += (int)$val["total_branch"];
		}
	}
	
	$dataPoints = array();
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'interval' => 1
	);
	###
	if(!empty($arrAccountTHUCCHI)){
		foreach($arrAccountTHUCCHI as $key => $id){
			$amount = !empty($arr_expense[$id]) ? $arr_expense[$id] : 0;
			$dataPoints[] = [
				"label"		=>	$arrAccount[$id]["title"],
				"color"		=>	$arrAccount[$id]["bgcolor"],
				"y"			=>	$amount,
				"percent"	=>	($total > 0) ? round((int)$amount * 100/$total,1) : 0,
				"price_format"	=>	$clsISO->formatPrice((int)$amount).$clsISO->getRate()
			];
		}
	}
	##
	$data['type'] = 'doughnut';
	$data['indexLabel'] = '{label}: {percent}%';
	$data['yValueFormatString'] = '#,##0';
	$data['toolTipContent'] = '<b>{label}</b>: {price_format}';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['title'] = [
		"text"	=>	"Tổng chi phí: ".$clsISO->shortNumber($total),
		"verticalAlign" => "bottom",
        "fontSize" => 20,
        "fontWeight" =>  "bold"
	];
	$barChartData['data'] = $data;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];
	// Return
	$callback = '$Core.chart.canvas(\''.$uid.'\',respJson.barChartData);';
	$html = '<div class="form-row"><div class="col-12 col-md-6 col-xxl-6"><div id="'.$uid.'" class="chartContainer w-100 h-px-300"></div></div>';
	$html .= '<div class="col-12 col-md-6 col-xxl-6"><div class="d-flex gap-1 flex-column px-2 overflow-y-auto h-px-300">';
				foreach($dataPoints as $key => $val) {
					$html .= '<div class="d-flex align-items-start justify-content-between gap-2">
								<div class="d-flex align-items-start gap-2">
									<span class="w-px-15 h-px-15 mt-1 rounded-pill" style="min-width:15px;background: '.$val["color"].'"></span>
									<span class="">'.$val["label"].'</span>
								</div>
								<span class="text-fs-16 fw-bold text-nowrap">'.(($val["y"] > 0)?$clsISO->shortNumber($val["y"]):"0đ").'</span>
							</div>';
				}
	$html .= '</div></div></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function branch_list_ops_cost(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	$clsOpsCost  = new OpsCost ();
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsMoneyItem', $clsMoneyItem);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsOpsCost', $clsOpsCost);
	#
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));	
	$type = Input::post('type', "");	
//	==============================
	$cond = "`is_trash`=0";
	###	
	if($month > 0){
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);
		$cond = " FROM_UNIXTIME(`t2`.`accounting_date`,'%m-%Y')='{$my}'";	
	} else {
		$date = sprintf('01-01-%s', $year);
		$cond = " FROM_UNIXTIME(`t2`.`accounting_date`,'%Y')='{$year}'";
	}
	$clsCache = new Cache();
	$arr_dep_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT",0,"",0);
	$arrOffice = $clsSetting->getArraySearchByKey("_OFFICE");
	$arrAccount = $clsSetting->getArraySearchByKey("_ACCOUNT");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstDep = $clsISO->buildTree($arr_dep_cached,_DEPARTMENT_SALE_ID,$clsProperty->pkey);
	foreach ($lstDep as $key => $val) {
		$more_information = $val["more_information"];		
		if(empty($more_information["is_business_area"])) {
			unset($lstDep[$key]);
		}
	}	
	if($type == "branch") {
		$arr_regional_id = array_merge(array_keys($arrOffice),array_keys($lstDep));
		$cond .= " AND `t1`.`regional_id` IN (".implode(',',$arr_regional_id).")";
	}else if($type == "project") {		
		$cond .= " AND (`t1`.`block_id` > 0 OR `t1`.`project_id` > 0)";
	}
	
	#- Begin pagination
	$list_ops_cost = $dbconn->getALl("SELECT `t1`.*, `t2`.*
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond}");
	$current_page = Input::post('page',1);
	$per_page = Input::post('per_page',30);
	$total_record = !empty($list_ops_cost) ? count($list_ops_cost) : 0;
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	#- End pagination	
	
	$list_ops_cost = $dbconn->getALl("SELECT `t1`.*, `t2`.*
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond} ORDER BY `t2`.`accounting_date` DESC ");
	if(!empty($list_ops_cost)){
		$arr_property_cached = $arr_setting_cached = array();
		foreach($list_ops_cost as $key => $val){
			$account_name = !empty($arrAccount[$val["account_id"]]) ? "[".$arrAccount[$val["account_id"]]["setting_code"]."]".$arrAccount[$val["account_id"]]["title"] : "--";
			$list_ops_cost[$key]["account_name"] = $account_name;
			$list_ops_cost[$key]["project_name"] = !empty($val["block_id"]) ? $arr_block_cached[$val["block_id"]]["title"] : "--";
			if(isset($arrOffice[$val["regional_id"]])) {
				$list_ops_cost[$key]["department_name"] = $arrOffice[$val["regional_id"]]["title"];
			}elseif(isset($lstDep[$val["regional_id"]])){
				$list_ops_cost[$key]["department_name"] = $lstDep[$val["regional_id"]]["title"];
			}else{
				$list_ops_cost[$key]["department_name"] = "--";
			}			
		}
	}
	$smarty->assign('list_ops_cost', $list_ops_cost);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	
	// Return
	$html = $core->build("dashboard" . DS . '_ajax.ops_cost.tpl');
	// var_dump($html); die();
	echo json_encode(array(
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
	)); die();
}
