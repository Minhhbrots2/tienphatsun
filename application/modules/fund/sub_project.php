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
function project_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	###
	$clsSetting = new Setting();
	$clsMoneyItem = new MoneyItem();
	$clsMoney = new Money();
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
	$dbconn->getAll("CREATE OR REPLACE VIEW default_v_account_balance AS
		SELECT 
			`acc`.`{$clsSetting->pkey}`,
			`mo`.accounting_date,
			`mo`.`company_id`,
			`moi`.`block_id`,
			`moi`.`regional_id`,
			SUM(`moi`.`debit_amount`) AS `total_debit`,
			SUM(`moi`.`credit_amount`) AS `total_credit`,
			SUM(`moi`.`debit_amount` - `moi`.`credit_amount`) AS `balance`
		FROM `{$clsSetting->tbl}` AS `acc`
		JOIN `{$clsMoneyItem->tbl}` AS `moi` 
			ON `moi`.`account_id` = `acc`.`{$clsSetting->pkey}`
		JOIN `{$clsMoney->tbl}` AS `mo` 
			ON `mo`.money_id = `moi`.money_id
		WHERE `acc`.`_type`='_ACCOUNT'
		GROUP BY `acc`.`{$clsSetting->pkey}`, `mo`.`company_id`, `moi`.`block_id`, `moi`.`regional_id`, `mo`.accounting_date");
		
	$arrAccount_CASH = $clsMoney->getAccountByType("CASH");
	$dbconn->getAll("CREATE OR REPLACE VIEW default_cash_lines AS
	SELECT 
		`m`.`money_id`, `m`.`accounting_date`, `mi`.`account_id`, `mi`.`corresponding_account_id`, `m`.`company_id`, `mi`.`block_id`, `mi`.`regional_id`,
		CASE WHEN `mi`.`debit_amount` > 0 THEN `mi`.`debit_amount` ELSE `mi`.`credit_amount` END AS `amount`,
		CASE WHEN `mi`.`debit_amount` > 0 THEN 'in' ELSE 'out' END AS `direction`
	FROM `{$clsMoneyItem->tbl}` AS `mi`
	JOIN `{$clsMoney->tbl}` AS `m` ON `m`.`money_id` = `mi`.`money_id`
	WHERE `mi`.`account_id` IN (".implode(',',$arrAccount_CASH).")");
	
	/*=============Title & Description Page==================*/
	$title_page = 'Chi phí - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function project_load_total_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$sub,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
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
	$start_time_prev = strtotime($arr_time["start_time_prev"]);
	$end_time_prev = strtotime($arr_time["end_time_prev"]);	
	$cond = " (`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	$cond_prev = " (`accounting_date` BETWEEN '{$start_time_prev}' AND '{$end_time_prev}')";
	
	$arrOffice = $clsSetting->getArraySearchByKey("_OFFICE");
	$arrAccountREVENUE = $clsMoney->getAccountByType("REVENUE");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$arrAccountMKT = $clsMoney->getAccountByType("MARKETING_COST");
	$arrAccountCOMMISSION = $clsMoney->getAccountByType("COMMISSION");
	
	$tmp = $dbconn->getAll("SELECT 
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountTHUCCHI).") AND `block_id` > 0 THEN `total_debit` ELSE 0 END) AS `total_expense`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountREVENUE).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_revenue`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountMKT).") AND `block_id` > 0 THEN `balance` ELSE 0 END) AS `total_MKT`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCOMMISSION).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_commission`
		FROM `default_v_account_balance` WHERE {$cond} AND `block_id` > 0 ");
	$tmp_prev = $dbconn->getAll("SELECT 
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountTHUCCHI).") AND `block_id` > 0 THEN `total_debit` ELSE 0 END) AS `total_expense`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountREVENUE).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_revenue`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountMKT).") AND `block_id` > 0 THEN `balance` ELSE 0 END) AS `total_MKT`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCOMMISSION).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_commission`
		FROM `default_v_account_balance` WHERE {$cond_prev} AND `block_id` > 0 ");
	
	$total_expense = $total_expense_prev = $total_revenue = $total_revenue_prev = $total_mkt = $total_mkt_prev = $total_commission = $total_commission_prev = 0;
	foreach ($tmp as $key => $val) {
		$total_expense += (int)$val["total_expense"];
		$total_revenue += (int)$val["total_revenue"];
		$total_mkt += (int)$val["total_mkt"];
		$total_commission += (int)$val["total_commission"];
	}
	foreach ($tmp_prev as $key => $val) {
		$total_expense_prev += (int)$val["total_expense"];
		$total_revenue_prev += (int)$val["total_revenue"];
		$total_mkt_prev += (int)$val["total_mkt"];
		$total_commission_prev += (int)$val["total_commission"];
	} 
	#
	$total_change = $total_expense - $total_expense_prev;
	$change_rate = ($total_expense_prev > 0) ? round($total_change*100/$total_expense_prev,1) : "";
	#
	$total_change_revenue = $total_revenue - $total_revenue_prev;
	$change_rate_revenue = ($total_revenue_prev > 0) ? round($total_change_revenue*100/$total_revenue_prev,1)."%" : "";
	#
	$total_change_mkt = $total_mkt - $total_mkt_prev;
	$change_rate_mkt = ($total_change_mkt > 0) ? round($total_change_mkt*100/$total_mkt_prev,1)."%" : "";
	#
	$total_change_commission = $total_commission - $total_commission_prev;
	$change_rate_commission = ($total_change_commission > 0) ? round($total_change_commission*100/$total_commission_prev,1)."%" : "";
	#
	$total_profit = $total_revenue - $total_expense;
	$total_profit_prev = $total_revenue_prev - $total_expense_prev;
	#
	$total_change_profit = $total_profit - $total_profit_prev;
	$change_rate_profit = ($total_profit_prev > 0) ? round($total_change_profit*100/$total_profit_prev,1)."%" : "";
	$smarty->assign("total_expense",$total_expense);
	$smarty->assign("total_change",$total_change);
	$smarty->assign("change_rate",$change_rate);
	$smarty->assign("total_revenue",$total_revenue);
	$smarty->assign("change_rate_revenue",$change_rate_revenue);
	$smarty->assign("total_mkt",$total_mkt);
	$smarty->assign("change_rate_mkt",$change_rate_mkt);
	$smarty->assign("total_commission",$total_commission);
	$smarty->assign("change_rate_commission",$change_rate_commission);
	$smarty->assign("total_profit",$total_profit);
	$smarty->assign("change_rate_profit",$change_rate_profit);
	$html = $core->build($sub.DS."_ajax.load_total.tpl");
	echo json_encode(array(
		"html"	=>	$html
	));die;
}
function project_load_project_revenue_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	
	if($month > 0){
		$format_time = "%d/%m/%Y";
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);			
		if(date("m-Y") == $my) {
			$start_date = date("d-m-Y",strtotime("-29 days"));
			$start_time = strtotime($start_date);
			$end_time = strtotime(date("d-m-Y 23:59:59"));
		}else{
			$start_time = strtotime($date);
			$end_month = date(sprintf('t-%s-%s 23:59:59', $clsISO->parseNumber($month), $year),$start_time);
			$end_time = strtotime($end_month);
		}
	} else {
		$format_time = "%m/%Y";
		$date = sprintf('01-01-%s', $year);
		if(date("Y") == $year) {
			$start_date = date("01-m-Y",strtotime("- 12 months"));
			$start_time = strtotime("+1 months",strtotime($start_date));
			$end_time = strtotime(date("d-m-Y 23:59:59"));
		}else{
			$start_time = strtotime($date);
			$end_month = date(sprintf('t-12-%s 23:59:59', $year),$start_time);
			$end_time = strtotime($end_month);
		}
	}	
	$cond = " (`accounting_date` BETWEEN {$start_time} AND {$end_time})";
	
	$arrAccountREVENUE = $clsMoney->getAccountByType("REVENUE");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	
	$tmp = $dbconn->getAll("SELECT FROM_UNIXTIME(`accounting_date`,'{$format_time}') AS `date`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountTHUCCHI).") AND `block_id` > 0 THEN `total_debit` ELSE 0 END) AS `total_expense`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountREVENUE).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_revenue`
		FROM `default_v_account_balance` WHERE {$cond} AND `block_id` > 0 GROUP BY `date` ");
	$total_expense = $total_revenue = 0;
	$arr_revenue = $arr_expense = [];
	foreach ($tmp as $key => $val) {
		if(!isset($arr_expense[$val["date"]])) {
			$arr_expense[$val["date"]] = (int)$val["total_expense"];	
		}else{
			$arr_expense[$val["date"]] += (int)$val["total_expense"];
		}
		if(!isset($arr_revenue[$val["date"]])) {
			$arr_revenue[$val["date"]] = (int)$val["total_revenue"];	
		}else{
			$arr_revenue[$val["date"]] += (int)$val["total_revenue"];
		}
	}
	###
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];
	
	$dataPoints_Expense = $dataPoints_revenue = array();
	$html = '<div id="chart_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	###
	if($month == 0){
		$a = 1;
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 months",$i)){
			$m = date("m/Y",$i);
			$total_expense = !empty($arr_expense[$m]) ? (int)$arr_expense[$m] : 0;
			$total_revenue = !empty($arr_revenue[$m]) ? (int)$arr_revenue[$m] : 0;
			
			$dataPoints_Expense[] = array(
				'label' => sprintf('T%s', $m),
				'y' => $total_expense*1,
				'indexLabel' => ($total_expense > 0) ? $clsISO->shortNumber($total_expense) : "0"
			);
			$dataPoints_revenue[] = array(
				'label' => sprintf('T%s', $m),
				'y' => $total_revenue*1,
				'indexLabel' => ($total_revenue > 0) ? $clsISO->shortNumber($total_revenue) : "0"
			);
			++$a;
			if($a == 20) {
				break;
			}
		}
	} else {		
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 days",$i)){
			$d = date("d/m/Y",$i);	
			$dm = strtotime(str_replace("/","-",$d));
			$dm = date("d/m",$dm);
			$total_expense = !empty($arr_expense[$d]) ? (int)$arr_expense[$d] : 0;
			$total_revenue = !empty($arr_revenue[$d]) ? (int)$arr_revenue[$d] : 0;		
			$dataPoints_Expense[] = array(
				'label' => $dm,
				'y' => $total_expense*1,
				'indexLabel' => ($total_expense > 0) ? $clsISO->shortNumber($total_expense) : "0"
			);
			$dataPoints_revenue[] = array(
				'label' => $dm,
				'y' => $total_revenue*1,
				'indexLabel' => ($total_revenue > 0) ? $clsISO->shortNumber($total_revenue) : "0"
			);
			++$a;
			if($a == 20) {
				break;
			}
		}
	}
	$barChartData['data'] = array(
		array(
			"type"    => "spline",
			"indexLabel" => "Chi phí {symbol}: {y}",
			"color"	 => "#1d6a01",
			"yValueFormatString"	 => '#,##0đ',
			"name"    => "Chi phí",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints_Expense
		),
		array(
			"type"  => "spline",
			"indexLabel" => "Doanh thu {symbol}: {y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "Doanh thu",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataPoints_revenue
		)
	);
	$callback = '$Core.chart.canvas_multi(\'chart_'.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function project_load_report_total_expense_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	###
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
	$cond = " (`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";	
	###
	$data = $barChartData = array();
	$arrAccount = $clsSetting->getArraySearchByKey("_ACCOUNT");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
//	$dbconn->debug=true;
	$tmp = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountTHUCCHI).") AND `block_id` > 0 THEN `total_debit` ELSE 0 END) AS `total_expense`
		FROM `default_v_account_balance` WHERE {$cond} AND `block_id` > 0 GROUP BY `account_id` ");
//	$clsISO->print_pre($tmp);die;
	$arr_expense = [];
	$total = 0;
	if(!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_expense[$val["account_id"]] = $val["total_expense"];
			$total += (int)$val["total_expense"];
		}
	}
	
	$dataPoints = array();
	$data = $barChartData = array();
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
	$barChartData['animationEnabled'] = true;
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
function project_load_project_cash_flow(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsSetting = new Setting();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$type = Input::get("type","cash");
	if($type == "commission") {
		$label_expense = "Nhận";
		$label_income = "Trả";
	}else{
		$label_expense = "Doanh thu";
		$label_income = "Chi phí";
	}
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
	$cond = " (`accounting_date` BETWEEN {$start_time} AND {$end_time})";
	
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$arrAccountTHUCTHU = $clsMoney->getAccountByType("THUCTHU");
	$arrAccountREVENUE = $clsMoney->getAccountByType("REVENUE");
	$arrAccountMKT = $clsMoney->getAccountByType("MARKETING_COST");
	$arrAccountCOMMISSION = $clsMoney->getAccountByType("COMMISSION");
	$tmp = $dbconn->getAll("SELECT `block_id`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountTHUCCHI).") AND `block_id` > 0 THEN `total_debit` ELSE 0 END) AS `total_THUCCHI`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountTHUCTHU).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_THUCTHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCOMMISSION).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_commission`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountREVENUE).") AND `block_id` > 0 THEN `total_credit` ELSE 0 END) AS `total_REVENUE`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountMKT).") AND `block_id` > 0 THEN `total_debit` ELSE 0 END) AS `total_MKT`
		FROM `default_v_account_balance` WHERE {$cond} AND `block_id` > 0 GROUP BY `block_id` ");
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_project_cached = $clsProject->getListProject();
	$dataPoints_THUCCHI = $dataPoints_THUCTHU = $data_table = [];
	$lstProjectBlock = $clsMoneyItem->getAll("`block_id` > 0 GROUP BY `block_id`","`block_id`,`project_id`");
	$arrProjectBlock = [];
	foreach ($lstProjectBlock as $key => $val) {
		$block_id = $val["block_id"];
		$title = $arr_block_cached[$block_id]["property_code"];
		$arrProjectBlock[$block_id] = $title;
	}
	$arr_block_cash_flow = [];
	if(!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_block_cash_flow[$val["block_id"]] = $val;
		}
	}
	
	foreach ($arrProjectBlock as $block_id => $title) {
		$total_THUCCHI = $total_commission = $total_THUCTHU = $total_REVENUE = $total_MKT = 0;
		if(isset($arr_block_cash_flow[$block_id])) {
			$total_THUCCHI = $arr_block_cash_flow[$block_id]["total_THUCCHI"];
			$total_commission = $arr_block_cash_flow[$block_id]["total_commission"];
			$total_THUCTHU = $arr_block_cash_flow[$block_id]["total_THUCTHU"];
			$total_REVENUE = $arr_block_cash_flow[$block_id]["total_REVENUE"];
			$total_MKT = $arr_block_cash_flow[$block_id]["total_MKT"];			
		}
		$dataPoints_THUCCHI[] = [
			'label' => $title,
			'y' => (int)$total_THUCCHI,
			'indexLabel' => $clsISO->shortNumber($total_THUCCHI)
		];
		$dataPoints_THUCTHU[] = [
			'label' => $title,
			'y' => (int)$total_THUCTHU,
			'indexLabel' => $clsISO->shortNumber($total_THUCTHU)
		];
		$total_profit = $total_REVENUE - $total_THUCCHI;
		$data_table[] = [
			"project_name"	=>	$title,
			"total_REVENUE"	=>	$clsISO->formatPrice($total_REVENUE).$clsISO->getRate(),
			"total_THUCCHI"	=>	$clsISO->formatPrice($total_THUCCHI).$clsISO->getRate(),
			"total_profit"	=>	$clsISO->formatPrice($total_profit).$clsISO->getRate(),
			"total_MKT"		=>	$clsISO->formatPrice($total_MKT).$clsISO->getRate(),
			"total_commission"		=>	$clsISO->formatPrice($total_commission).$clsISO->getRate()
		];		
	}
	###
	$data = $barChartData = array();
	$barChartData = [
		'animationEnabled' => true,
		'shared' => true,
	];
	$html = '<div id="chart_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "Thực chi {symbol}: {y}",
			"color"	 => "#b6d634",
			"yValueFormatString"	 => '#,##0đ',
			"name"    => "Thực chi",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints_THUCCHI
		),
		array(
			"type"  => "column",
			"indexLabel" => "Thực thu {symbol}: {y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "Thực thu",
			"color"	 => "#5070dd",
			"showInLegend" => true,
			"dataPoints"   => $dataPoints_THUCTHU
		)
	);
	$callback = '$Core.chart.canvas_multi(\'chart_'.$uid.'\',respJson.barChartData);';
	$smarty->assign("data_table",$data_table);
	$html_table = $core->build("project".DS."_ajax.load_table_cash_flow.tpl");
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback,
		'html_table' => $html_table,
	)); die();
}
