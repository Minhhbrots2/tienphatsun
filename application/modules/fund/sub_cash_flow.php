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
function cash_flow_cash_flow(){
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
	$title_page = 'Dòng tiền - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function cash_flow_load_total_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$sub,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$clsConfiguration;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
	#
	$company_id = (int) Input::post('company_id', _GROUP_COMPANY_FH_ID);
	$list_company = $clsSetting->getCacheItems('_GROUP_COMPANY');
	#
	$company_name = "";
	if(!empty($list_company)){
		foreach($list_company as $key => $val){
			if($val[$clsSetting->pkey] == $company_id){
				$company_name = $val["title"];
				break;
			}
		}
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
	$start_time_prev = strtotime($arr_time["start_time_prev"]);
	$end_time_prev = strtotime($arr_time["end_time_prev"]);
	$cond = " (`accounting_date` BETWEEN {$start_time} AND {$end_time})";
	$cond_prev = " `accounting_date` <= {$end_time_prev}";
	
	$arrAccountCASH = $clsMoney->getAccountByType("CASH");
	$arrAccountPHAITHU = $clsMoney->getAccountByType("PHAITHU");
	$arrAccountPHAITRA = $clsMoney->getAccountByType("PHAITRA");
	$arrAccountDUTHU = $clsMoney->getAccountByType("DUKIENTHU");
	$arrAccountDUCHI = $clsMoney->getAccountByType("DUKIENCHI");
	$arrAccountOUTPUT_TAX = $clsMoney->getAccountByType("OUTPUT_TAX");
	$arrAccountDEBT = $clsMoney->getAccountByType("DEBT");
	$arr_cache_cash_flow_category = $clsSetting->getArraySearchByKey("_CASH_FLOW_CATEGORY");	
	$arrAccount_CASH = [];
	foreach($arr_cache_cash_flow_category as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		if(!empty($more_information["lst_account_id"])) {
			$arrAccount_CASH = array_merge($arrAccount_CASH,$more_information["lst_account_id"]);
		}	
	}
//	$dbconn->debug=true;
	$tmp = $dbconn->getALl("SELECT 
		(SUM(CASE  WHEN direction = 'in' THEN amount ELSE 0 END)
		- SUM(CASE WHEN direction = 'out' THEN amount ELSE 0 END)) AS total_cash_flow
		FROM default_cash_lines WHERE {$cond} AND `corresponding_account_id` IN (".implode(',',$arrAccount_CASH).")");
	$tmp_prev = $dbconn->getALl("SELECT 
		(SUM(CASE  WHEN direction = 'in' THEN amount ELSE 0 END)
		- SUM(CASE WHEN direction = 'out' THEN amount ELSE 0 END)) AS total_cash_flow
		FROM default_cash_lines WHERE {$cond_prev} AND `corresponding_account_id` IN (".implode(',',$arrAccount_CASH).")");
		
	$tmp2 = $dbconn->getAll("SELECT 
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCASH).") THEN `balance` ELSE 0 END) AS `total_cash`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITHU).") AND `balance` > 0 THEN `balance` ELSE 0 END) AS `total_PHAITHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITRA).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_PHAITRA`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountDUTHU).") AND `balance` < 0 THEN ABS(`balance`) ELSE 0 END) AS `total_DUTHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountOUTPUT_TAX).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_OUTPUT_TAX`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountDEBT).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_DEBT`
		FROM `default_v_account_balance` WHERE {$cond}");
	$tmp2_prev = $dbconn->getAll("SELECT 
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCASH).") THEN `balance` ELSE 0 END) AS `total_cash`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITHU).") AND `balance` > 0 THEN `balance` ELSE 0 END) AS `total_PHAITHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITRA).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_PHAITRA`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountDUTHU).") AND `balance` < 0 THEN ABS(`balance`) ELSE 0 END) AS `total_DUTHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountOUTPUT_TAX).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_OUTPUT_TAX`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountDEBT).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_DEBT`
		FROM `default_v_account_balance` WHERE {$cond_prev}");
	$total_cash = $total_cash_flow = $total_cash_flow_prev = $total_cash_in = $total_cash_in_prev = $total_cash_out = $total_cash_out_prev = $total_PHAITHU = $total_PHAITHU_prev = $total_PHAITRA = $total_PHAITRA_prev = $total_DUCHI = $total_DUCHI_prev = $total_OUTPUT_TAX = $total_OUTPUT_TAX_prev = $total_DEBT = $total_DEBT_prev = 0;
	foreach ($tmp as $key => $val) {
		$total_cash_flow += (int)$val["total_cash_flow"];
	}
	foreach ($tmp_prev as $key => $val) {
		$total_cash_flow_prev += (int)$val["total_cash_flow"];
	}
	foreach($tmp2 as $key => $val) {
		$total_cash += $val["total_cash"];
		$total_PHAITHU += $val["total_PHAITHU"];
		$total_PHAITRA += $val["total_PHAITRA"];
		$total_DUTHU += $val["total_DUTHU"];
		$total_OUTPUT_TAX += $val["total_OUTPUT_TAX"];
		$total_DEBT += $val["total_DEBT"];
	}
	foreach($tmp2_prev as $key => $val) {
		$total_cash_prev += $val["total_cash"];
		$total_PHAITHU_prev += $val["total_PHAITHU"];
		$total_PHAITRA_prev += $val["total_PHAITRA"];
		$total_DUTHU_prev += $val["total_DUTHU"];
		$total_OUTPUT_TAX_prev += $val["total_OUTPUT_TAX"];
		$total_DEBT_prev += $val["total_DEBT"];
	} 
	#
	// Dự kiến Chi
	$total_DUCHI = 0;
	$planned_expected_expense = $clsConfiguration->getValue('planned_expected_expense');
	$planned_expected_expense = $clsISO->to_array_json($planned_expected_expense);
	$data = $core->get_field($planned_expected_expense, "data", []);
	$arr_expected_expense = $core->get_field($data, $company_name, []);
	if(!empty($arr_expected_expense)){
		foreach($arr_expected_expense as $key => $val){
			$expected_amount = $val['expected_amount'];
			$expected_amount = $clsISO->processSmartNumber($expected_amount);
			$total_DUCHI += $expected_amount;
		}
	}
	#
	$total_change_cash_flow = $total_cash_flow - $total_cash_flow_prev;
	$change_rate_cash_flow = ($total_cash_flow_prev > 0) ? round($total_change_cash_flow*100/$total_cash_flow_prev,1)."%" : "";
	#
	$total_change_cash = $total_cash - $total_cash_prev;
	$change_rate_cash = ($total_cash_prev > 0) ? round($total_change_cash*100/$total_cash_prev,1)."%" : "";
	#
	$total_change_PHAITHU = $total_PHAITHU - $total_PHAITHU_prev;
	$change_rate_PHAITHU = ($total_PHAITHU_prev > 0) ? round($total_change_PHAITHU*100/$total_PHAITHU_prev,1)."%" : "";
	#
	$total_change_PHAITRA = $total_PHAITRA - $total_PHAITRA_prev;
	$change_rate_PHAITRA = ($total_PHAITRA_prev > 0) ? round($total_change_PHAITRA*100/$total_PHAITRA_prev,1)."%" : "";
	#
	$total_change_DUTHU = $total_DUTHU - $total_DUTHU_prev;
	$change_rate_DUTHU = ($total_DUTHU_prev > 0) ? round($total_change_DUTHU*100/$total_DUTHU_prev,1)."%" : "";
	#
	$total_change_OUTPUT_TAX = $total_OUTPUT_TAX - $total_OUTPUT_TAX_prev;
	$change_rate_OUTPUT_TAX = ($total_OUTPUT_TAX_prev > 0) ? round($total_change_OUTPUT_TAX*100/$total_OUTPUT_TAX_prev,1)."%" : "";
	#
	$total_change_DEBT = $total_DEBT - $total_DEBT_prev;
	$change_rate_DEBT = ($total_DEBT_prev > 0) ? round($total_change_DEBT*100/$total_DEBT_prev,1)."%" : "";
	#
	$smarty->assign("total_cash_flow",$total_cash_flow);
	$smarty->assign("change_rate_cash_flow",$change_rate_cash_flow);
	$smarty->assign("total_cash",$total_cash);
	$smarty->assign("change_rate_cash",$change_rate_cash);
	$smarty->assign("total_PHAITHU",$total_PHAITHU);
	$smarty->assign("change_rate_PHAITHU",$change_rate_PHAITHU);
	$smarty->assign("total_PHAITRA",$total_PHAITRA);
	$smarty->assign("change_rate_PHAITRA",$change_rate_PHAITRA);
	$smarty->assign("total_DUTHU",$total_DUTHU);
	$smarty->assign("change_rate_DUTHU",$change_rate_DUTHU);
	$smarty->assign("total_DUCHI",$total_DUCHI);
	$smarty->assign("total_OUTPUT_TAX",$total_OUTPUT_TAX);
	$smarty->assign("change_rate_OUTPUT_TAX",$change_rate_OUTPUT_TAX);
	$smarty->assign("total_DEBT",$total_DEBT);
	$smarty->assign("change_rate_DEBT",$change_rate_DEBT);
	$html = $core->build($sub.DS."_ajax.load_total.tpl");
	echo json_encode(array(
		"html"	=>	$html
	));die;
}
function cash_flow_load_chart(){
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
	###
	if($month > 0){
		$format_time = "%d/%m/%Y";
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);			
		if(date("m-Y") == $my) {
			$start_date = date("d-m-Y",strtotime("- 1 months"));
			$start_time = strtotime("+1 days",strtotime($start_date));
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
			$start_date = date("01-m-Y",strtotime("- 11 months"));
			$start_time = strtotime($start_date);
			$end_time = strtotime(date("d-m-Y 23:59:59"));
		}else{
			$start_time = strtotime($date);
			$end_month = date(sprintf('t-12-%s 23:59:59', $year),$start_time);
			$end_time = strtotime($end_month);
		}
	}	
	$cond = " (`accounting_date` BETWEEN {$start_time} AND {$end_time})";
	$arr_cache_cash_flow_category = $clsSetting->getArraySearchByKey("_CASH_FLOW_CATEGORY");	
	$arrAccount_CASH = [];
	foreach($arr_cache_cash_flow_category as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		if(!empty($more_information["lst_account_id"])) {
			$arrAccount_CASH = array_merge($arrAccount_CASH,$more_information["lst_account_id"]);
		}	
	}
	$tmp = $dbconn->getALl("SELECT FROM_UNIXTIME(`accounting_date`,'{$format_time}') AS `date`,
		SUM(CASE  WHEN direction = 'out' THEN amount ELSE 0 END) AS total_outflow,
		SUM(CASE  WHEN direction = 'in' THEN amount ELSE 0 END) AS total_inflow
		FROM default_cash_lines WHERE {$cond} AND `corresponding_account_id` IN (".implode(',',$arrAccount_CASH).") GROUP BY `date`");
	
	$arr_outflow = $arr_inflow = [];
	foreach ($tmp as $key => $val) {
		if(!isset($arr_inflow[$val["date"]])) {
			$arr_inflow[$val["date"]] = (int)$val["total_inflow"];	
		}else{
			$arr_inflow[$val["date"]] += (int)$val["total_inflow"];
		}
		if(!isset($arr_outflow[$val["date"]])) {
			$arr_outflow[$val["date"]] = (int)$val["total_outflow"];	
		}else{
			$arr_outflow[$val["date"]] += (int)$val["total_outflow"];
		}
	}
	$data = $barChartData = array();
	
	$dataPoints_inflow = $dataPoints_outflow = array();
	###
	if($month == 0){
		$a = 1;
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 months",$i)){
			$m = date("m/Y",$i);
			$total_inflow = !empty($arr_inflow[$m]) ? (int)$arr_inflow[$m] : 0;
			$total_outflow = !empty($arr_outflow[$m]) ? (int)$arr_outflow[$m] : 0;
			
			$dataPoints_inflow[] = array(
				'label' => sprintf('T%s', $m),
				'y' => $total_inflow*1,
				'indexLabel' => ($total_inflow > 0) ? $clsISO->shortNumber($total_inflow) : "0"
			);
			$dataPoints_outflow[] = array(
				'label' => sprintf('T%s', $m),
				'y' => $total_outflow*1,
				'indexLabel' => ($total_outflow > 0) ? $clsISO->shortNumber($total_outflow) : "0"
			);
		}
	} else {		
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 days",$i)){
			$d = date("d/m/Y",$i);	
			$dm = strtotime(str_replace("/","-",$d));
			$dm = date("d/m",$dm);
			$total_inflow = !empty($arr_inflow[$d]) ? (int)$arr_inflow[$d] : 0;
			$total_outflow = !empty($arr_outflow[$d]) ? (int)$arr_outflow[$d] : 0;		
			$dataPoints_inflow[] = array(
				'label' => $dm,
				'y' => $total_inflow*1,
				'indexLabel' => ($total_inflow > 0) ? $clsISO->shortNumber($total_inflow) : "0"
			);
			$dataPoints_outflow[] = array(
				'label' => $dm,
				'y' => $total_outflow*1,
				'indexLabel' => ($total_outflow > 0) ? $clsISO->shortNumber($total_outflow) : "0"
			);
		}
	}	
	$html = '<div id="chart_'.$uid.'" class="chartContainer w-100 h-px-200"></div>';
	$barChartData = [
		'animationEnabled' 	=>	true,
		'toolTip' 	=>	[
			"shared"	=>	true
		],
		'axisY' 	=>	[
			"labelFormatter"	=>	1
		],
		'data'	=>	[
			[
				"type"    => "spline",
				"indexLabel" => "Tiền vào {symbol}: {y}",
				"yValueFormatString"	 => '#,##0đ',
				"name"    => "Tiền vào",
				"color"	 => "#1d6a01",
				"showInLegend" => true,
				"lineColor" => "#71dd37",
				"markerColor" => "#71dd37",
				"markerSize" => 5,
				"markerType" => "circle",
				"dataPoints"    => $dataPoints_inflow
			],
			[
				"type"  => "spline",
				"indexLabel" => "Tiền ra {symbol}: {y}",
				"yValueFormatString"	 => '#,##0đ',
				"name"	=> "Tiền ra",
				"color"	 => "#C00000",
				"showInLegend" => true,
				"lineColor" => "#ff3e1d",
				"markerColor" => "#ff3e1d",
				"markerSize" => 5,
				"markerType" => "circle",
				"dataPoints"   => $dataPoints_outflow
			]
		]
	];
	$callback = '$Core.chart.canvas_multi(\'chart_'.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function cash_flow_load_chart_tax(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsProject = new Project();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$type = Input::get("type","cash");
	###
	if($month > 0){
		$format_time = "%d/%m/%Y";
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);			
		if(date("m-Y") == $my) {
			$start_date = date("d-m-Y",strtotime("- 1 months"));
			$start_time = strtotime("+1 days",strtotime($start_date));
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
			$start_date = date("01-m-Y",strtotime("- 11 months"));
			$start_time = strtotime($start_date);
			$end_time = strtotime(date("d-m-Y 23:59:59"));
		}else{
			$start_time = strtotime($date);
			$end_month = date(sprintf('t-12-%s 23:59:59', $year),$start_time);
			$end_time = strtotime($end_month);
		}
	}	
	$cond = " (`accounting_date` BETWEEN {$start_time} AND {$end_time})";	
	$arrAccount_output_tax = $clsMoney->getAccountByType("OUTPUT_TAX");
	$tmp = $dbconn->getALl("SELECT FROM_UNIXTIME(`t2`.`accounting_date`,'{$format_time}') AS `date`,
		SUM( CASE 
			WHEN (`t1`.`debit_amount` - `t1`.`credit_amount`) < 0
			THEN -(`t1`.`debit_amount` - `t1`.`credit_amount`)
			ELSE 0
		END) AS `total_tax_payable`
		FROM `{$clsMoneyItem->tbl}` AS `t1`
		JOIN `{$clsMoney->tbl}` AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond} AND `t1`.`account_id` IN (".implode(',',$arrAccount_output_tax).") GROUP BY `date` ");
//	$clsISO->print_pre($tmp);die;
	
	$arr_tax_payable = [];
	foreach ($tmp as $key => $val) {
		$total_tax_payable = (int)$val["total_tax_payable"];
		if(!isset($arr_tax_payable[$val["date"]])) {
			$arr_tax_payable[$val["date"]] = $total_tax_payable;	
		}else{
			$arr_tax_payable[$val["date"]] += $total_tax_payable;
		}
		unset($total_tax_payable);
	}
	$data = $barChartData = array();
	###
//	$dbconn->debug=true;
	if($month == 0){
		$a = 1;
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 months",$i)){
			$m = date("m/Y",$i);
			$total_tax_payable = !empty($arr_tax_payable[$m]) ? (int)$arr_tax_payable[$m] : 0;
			$dataPoints[] = array(
				'label' => sprintf('%s', $m),
				'y' => $total_tax_payable*1,
				'indexLabel' => ($total_tax_payable > 0) ? $clsISO->shortNumber($total_tax_payable) : "0"
			);
		}
	} else {		
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 days",$i)){
			$d = date("d/m/Y",$i);	
			$dm = strtotime(str_replace("/","-",$d));
			$dm = date("d/m",$dm);
			$total_tax_payable = !empty($arr_tax_payable[$d]) ? (int)$arr_tax_payable[$d] : 0;	
			$dataPoints[] = array(
				'label' => $dm,
				'y' => $total_tax_payable*1,				
				'indexLabel' => ($total_tax_payable > 0) ? $clsISO->shortNumber($total_tax_payable) : "0"
			);
			
		}
	}
	$data['axisX'] = array(
		'interval' => 1
	);
	$data['type'] = 'spline';
	$data['yValueFormatString'] = '#,##0';
	$data['toolTipContent'] = '<b>{label}</b>: {y}đ';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;	
	
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];
	
	$html = '<div id="'.$uid.'" class="chartContainer w-100 h-px-200"></div>';
	$barChartData['axisY'] = array(
		'labelFormatter' => 1
	);
	$barChartData['data'] = array(
		"type"    => "spline",
		"color"	 => "#1d6a01",
		"showInLegend" => true,
		"lineColor" => "#71dd37",
		"markerColor" => "#71dd37",
		"markerSize" => 5,
		"markerType" => "circle",
		"yValueFormatString"	 => '#,##0đ',
		"name"    => "Thuế",
		"showInLegend"    => true,
		"dataPoints"    => $dataPoints
	);
//	$barChartData['data'] = $data;
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
function buildFlatTree($nodes, &$result = [], $prefix = '', $isRoot = true) {

    // ===== ROOT =====
    if ($isRoot) {
        $index = 0;

        foreach ($nodes as $node) {
            $index++;
            buildFlatTree($node, $result, (string)$index, false);
        }

        return $result;
    }

    // ===== NODE =====
    $stt = $prefix;

    // push trước (để đúng thứ tự)
    $currentPos = count($result);

    $result[] = [
        'stt'        => $stt,
        'title'      => $nodes['title'],
        'code'       => $nodes['code'] ?? '',
        'bgcolor'       => $nodes['bgcolor'] ?? '',
        'lst_account_id'       => $nodes['lst_account_id'] ?? [],
		"setting_id" =>	$nodes['setting_id'],
		"parent_id"  =>	$nodes['parent_id'],
        'total'      => 0,
        'total_prev' => 0
    ];

    $total = $nodes['total'] ?? 0;
    $total_prev = $nodes['total_prev'] ?? 0;

    // nếu có children → cộng dồn
    if (!empty($nodes['children'])) {
        $childIndex = 0;

        // reset total nếu là node cha
        $total = 0;
        $total_prev = 0;

        foreach ($nodes['children'] as $child) {
            $childIndex++;

            $childPrefix = $stt . '.' . $childIndex;

            $childTotal = buildFlatTree($child, $result, $childPrefix, false);

            $total += $childTotal['total'];
            $total_prev += $childTotal['total_prev'];
        }
    }

    // update lại node hiện tại
    $result[$currentPos]['total'] = $total;
    $result[$currentPos]['total_prev'] = $total_prev;

    return [
        'total' => $total,
        'total_prev' => $total_prev
    ];
}
function cash_flow_load_detail_cash_flow(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$type = Input::post("_type","");
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
	$start_time_prev = strtotime($arr_time["start_time_prev"]);
	$end_time_prev = strtotime($arr_time["end_time_prev"]);
	$cond = " (`t2`.`accounting_date` <= {$end_time})";
	$cond_prev = " (`t2`.`accounting_date` <= {$end_time_prev})";
	$smarty->assign("start_date",date("d/m/Y",$start_time));
	$smarty->assign("end_date",date("d/m/Y",$end_time));
	
//	$dbconn->debug=true;
	$arr_account_cached = $clsSetting->getArraySearchByKey("_ACCOUNT");
	$arr_account_parent_cached = $clsISO->buildTree($arr_account_cached,0,$clsSetting->pkey);
//	$clsISO->print_pre($arr_account_parent_cached);die;
	$arrAccount_type = $arr_total = $arr_total_prev = [];
	$title_page = "Báo cáo";
	if($type == "OUTPUT_TAX") {
		$title_page = "Thuế ";
		$arrAccount_type = $clsMoney->getAccountByType("OUTPUT_TAX");
		$tmp = $dbconn->getALl("SELECT `t1`.`account_id`,
			SUM( CASE 
				WHEN (`t1`.`debit_amount` - `t1`.`credit_amount`) < 0
				THEN -(`t1`.`debit_amount` - `t1`.`credit_amount`)
				ELSE 0
			END) AS `total_money`
			FROM `{$clsMoneyItem->tbl}` AS `t1`
			JOIN `{$clsMoney->tbl}` AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
			WHERE {$cond} AND `t1`.`account_id` IN (".implode(',',$arrAccount_type).") GROUP BY `t1`.`account_id` ");
		$tmp_prev = $dbconn->getALl("SELECT `t1`.`account_id`,
			SUM( CASE 
				WHEN (`t1`.`debit_amount` - `t1`.`credit_amount`) < 0
				THEN -(`t1`.`debit_amount` - `t1`.`credit_amount`)
				ELSE 0
			END) AS `total_money`
			FROM `{$clsMoneyItem->tbl}` AS `t1`
			JOIN `{$clsMoney->tbl}` AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
			WHERE {$cond_prev} AND `t1`.`account_id` IN (".implode(',',$arrAccount_type).") GROUP BY `t1`.`account_id` ");
//		$clsISO->print_pre($tmp);die;
	}else if($type == "ACCOUNT_CASH") {
		$title_page = "tiền tài khoản";
		$arrAccountBANK_BALANCE = $clsMoney->getAccountByType("BANK_BALANCE");
		$arrAccount_type = $clsMoney->getAccountByType("CASH");
		$tmp = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") THEN `balance`
			ELSE 0 
		END) AS `total_money`
		FROM `default_v_account_balance` WHERE (`accounting_date` <= {$end_time}) AND `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") GROUP BY `account_id`");
		$tmp_prev = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") THEN `balance`
			ELSE 0 
		END) AS `total_money`
		FROM `default_v_account_balance` WHERE (`accounting_date` <= {$end_time_prev}) AND `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") GROUP BY `account_id`");
	}else if($type == "RECEIVABLES_PAYABLES") {
		$title_page = "công nợ";
		$arrAccountPHAITHU = $clsMoney->getAccountByType("PHAITHU");
		$arrAccountPHAITRA = $clsMoney->getAccountByType("PHAITRA");
		$arrAccount_type = array_merge($arrAccountPHAITHU,$arrAccountPHAITRA);	
		$tmp = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITHU).") AND `balance` > 0 THEN `balance` ELSE 0 END) AS `total_PHAITHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITRA).") AND `balance` < 0 THEN `balance` ELSE 0 END) AS `total_PHAITRA`
		FROM `default_v_account_balance` WHERE (`accounting_date` <= {$end_time}) GROUP BY `account_id`");
		$tmp_prev = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITHU).") AND `balance` > 0 THEN `balance` ELSE 0 END) AS `total_PHAITHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITRA).") AND `balance` < 0 THEN `balance` ELSE 0 END) AS `total_PHAITRA`
		FROM `default_v_account_balance` WHERE (`accounting_date` <= {$end_time_prev}) GROUP BY `account_id`");
	}else if($type == "ASSET") {
		$title_page = "tài sản";
		$cond = " (`t2`.`accounting_date` BETWEEN {$start_time} AND {$end_time})";
		$arrAccount_short_term = $clsMoney->getAccountByType("SHORT_TERM_ASSET");// TK tài sản ngắn hạn
		$arrAccount_long_term = $clsMoney->getAccountByType("LONG_TERM_ASSET"); // TK tài sản dài hạn
		$arrAccount_fixed_assets = $clsMoney->getAccountByType("FIXED_ASSETS"); // TK tài sản cố định
		$arrAccount_asset_depriciable = $clsMoney->getAccountByType("ASSET_DEPRICIABLE");	// TK tài sản khấu hao
		$arrAccount_asset_profision = $clsMoney->getAccountByType("ASSET_PROVISION");	// TK tài sản dự phòng
		$arrAccount_type = array_merge($arrAccount_short_term,$arrAccount_long_term);
//		$clsISO->print_pre($arrAccount_asset);die;
		#
//		$dbconn->debug=true;
		$tmp = $dbconn->getALl("SELECT `setting_id` AS `account_id`,
			SUM(CASE 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_short_term).") AND balance > 0 THEN balance 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_asset_profision).") THEN balance
				ELSE 0
			END) AS short_assets,
			SUM(CASE 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_long_term).") AND balance > 0 THEN balance
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_fixed_assets).") THEN balance
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_asset_depriciable).") THEN balance
				ELSE 0
			END) AS long_assets
			FROM default_v_account_balance WHERE `accounting_date` <= '{$end_time}' GROUP BY `account_id`");
		$tmp_prev = $dbconn->getALl("SELECT `setting_id` AS `account_id`,
			SUM(CASE 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_short_term).") AND balance > 0 THEN balance 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_asset_profision).") THEN balance
				ELSE 0
			END) AS short_assets,
			SUM(CASE 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_long_term).") AND balance > 0 THEN balance
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_fixed_assets).") THEN balance
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_asset_depriciable).") THEN balance
				ELSE 0
			END) AS long_assets
			FROM default_v_account_balance WHERE `accounting_date` <= '{$end_time_prev}' GROUP BY `account_id`");
//		$clsISO->print_pre($tmp_prev);die;
	}else if($type == "LIABILITIES_EQUITY") {
		$title_page = "nguồn vốn";
		$arrAccount_type = $clsMoney->getAccountByType("LIABILITIES_EQUITY");	
		$arrAccount_liabilities_debit = $clsMoney->getAccountByType("LIABILITIES_DEBIT");	
		$arrAccount_equity_debit = $clsMoney->getAccountByType("EQUITY_DEBIT");	
		$arrAccount_type = array_diff($arrAccount_type,$arrAccount_liabilities_debit);
		$arrAccount_type = array_diff($arrAccount_type,$arrAccount_equity_debit);
		#
//		$dbconn->debug=true;
		$tmp = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
			SUM(CASE 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_liabilities_debit).") THEN `balance`
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_equity_debit).") THEN `balance`
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") THEN -`balance`
				ELSE 0 
			END) AS `total_money`
		FROM default_v_account_balance WHERE `accounting_date` <= '{$end_time}' AND `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") GROUP BY `account_id`");
		$tmp_prev = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
			SUM(CASE 
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_liabilities_debit).") THEN `balance`
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_equity_debit).") THEN `balance`
				WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") THEN -`balance`
				ELSE 0 
			END) AS `total_money`
		FROM default_v_account_balance WHERE `accounting_date` <= '{$end_time_prev}' AND `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_type).") GROUP BY `account_id`");
	}else if($type == "CASH_FLOW") {
		$title_page = "dòng tiền";
		$arr_cache_cash_flow_category = $clsSetting->getArraySearchByKey("_CASH_FLOW_CATEGORY");	
		$arrAccount_type = $clsMoney->getAccountByType("CASH");	
//		$clsISO->print_pre($arr_cache_cash_flow_category);die;
		$caseWhen = $arr_category_code = [];
		foreach($arr_cache_cash_flow_category as $key => $val) {
			$more_information = $clsISO->to_array_json($val["more_information"]);
			$direction = $more_information['direction'];
			$code = $val['setting_code'];
			if(!empty($more_information["lst_account_id"])) {
				$accounts = implode(',', $more_information["lst_account_id"]);
				$caseWhen[] = "SUM(CASE 
					WHEN cl.direction = '$direction' 
					AND cl.corresponding_account_id IN ($accounts)
					THEN cl.amount ELSE 0 END) AS `{$code}`";
			}				
			$arr_category_code[$code] = [
				"setting_id"	=>	$val["setting_id"],
				"title"	=>	$val["title"],
				"parent_id"	=>	$val["parent_id"],
				"code"	=>	$val["setting_code"],
				"bgcolor"	=>	$val["bgcolor"],
				"direction"	=>	!empty($more_information["direction"]) ? $more_information["direction"] : "",
				"total"	=>	0,
				"total_prev"	=>	0,
			];
		}
		if(!empty($caseWhen)) {
			$sql = "SELECT " . implode(",\n", $caseWhen) . " FROM `default_cash_lines` AS `cl`";
			$tmp = $dbconn->getAll($sql." WHERE `accounting_date` <= '{$end_time}'");
			$tmp_prev = $dbconn->getAll($sql." WHERE `accounting_date` <= '{$end_time_prev}'");
			if(!empty($tmp[0])) {
				foreach($tmp[0] as $code => $total) {
					if(isset($arr_category_code[$code])) {
						$direction = $arr_category_code[$code]["direction"];
						$parent_id = $arr_category_code[$code]["parent_id"];
						$arr_category_code[$code]["total"] = (int)((($direction == "out") ? "-" : "").$total);
						
					}
				}
			}
			if(!empty($tmp_prev[0])) {
				foreach($tmp_prev[0] as $code => $total) {
					if(isset($arr_category_code[$code])) {
						$direction = $arr_category_code[$code]["direction"];
						$parent_id = $arr_category_code[$code]["parent_id"];
						$arr_category_code[$code]["total_prev"] = (int)((($direction == "out") ? "-" : "").$total);
					}
				}
			}
			unset($tmp,$tmp_prev);
		}
		$lstAccount = $clsISO->buildTree($arr_category_code,0,$clsSetting->pkey);	
		$lstCategory_cash_flow = buildFlatTree($lstAccount);
//		$clsISO->print_pre($lstCategory_cash_flow);die;
		#
		
	}
	if(!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			if($type == "RECEIVABLES_PAYABLES") {
				if($clsISO->checkItemInArray($val["account_id"],$arrAccountPHAITHU)) {
					if(!isset($arr_total[$val["account_id"]])) {
						$arr_total[$val["account_id"]] = (int)$val["total_PHAITHU"];
					}else{
						$arr_total[$val["account_id"]] += (int)$val["total_PHAITHU"];
					}
				}else{
					if(!isset($arr_total[$val["account_id"]])) {
						$arr_total[$val["account_id"]] = (int)$val["total_PHAITRA"];
					}else{
						$arr_total[$val["account_id"]] += (int)$val["total_PHAITRA"];
					}
				}				
			}elseif($type == "ASSET") {
				if($clsISO->checkItemInArray($val["account_id"],$arrAccount_short_term)) {
					if(!isset($arr_total[$val["account_id"]])) {
						$arr_total[$val["account_id"]] = (int)$val["short_assets"];
					}else{
						$arr_total[$val["account_id"]] += (int)$val["short_assets"];
					}
				}else{
					if(!isset($arr_total[$val["account_id"]])) {
						$arr_total[$val["account_id"]] = (int)$val["long_assets"];
					}else{
						$arr_total[$val["account_id"]] += (int)$val["long_assets"];
					}
				}				
			}else{
				if(!isset($arr_total[$val["account_id"]])) {
					$arr_total[$val["account_id"]] = (int)$val["total_money"];
				}else{
					$arr_total[$val["account_id"]] += (int)$val["total_money"];
				}
			}
						
		}
	}
	if(!empty($tmp_prev)) {
		foreach ($tmp_prev as $key => $val) {
			if($type == "RECEIVABLES_PAYABLES") {
				if($clsISO->checkItemInArray($val["account_id"],$arrAccountPHAITHU)) {
					if(!isset($arr_total_prev[$val["account_id"]])) {
						$arr_total_prev[$val["account_id"]] = (int)$val["total_PHAITHU"];
					}else{
						$arr_total_prev[$val["account_id"]] += (int)$val["total_PHAITHU"];
					}
				}else{
					if(!isset($arr_total_prev[$val["account_id"]])) {
						$arr_total_prev[$val["account_id"]] = (int)$val["total_PHAITRA"];
					}else{
						$arr_total_prev[$val["account_id"]] += (int)$val["total_PHAITRA"];
					}
				}				
			}elseif($type == "ASSET") {
				if($clsISO->checkItemInArray($val["account_id"],$arrAccount_short_term)) {
					if(!isset($arr_total_prev[$val["account_id"]])) {
						$arr_total_prev[$val["account_id"]] = (int)$val["short_assets"];
					}else{
						$arr_total_prev[$val["account_id"]] += (int)$val["short_assets"];
					}
				}else{
					if(!isset($arr_total_prev[$val["account_id"]])) {
						$arr_total_prev[$val["account_id"]] = (int)$val["long_assets"];
					}else{
						$arr_total_prev[$val["account_id"]] += (int)$val["long_assets"];
					}
				}				
			}else{
				if(!isset($arr_total_prev[$val["account_id"]])) {
					$arr_total_prev[$val["account_id"]] = (int)$val["total_money"];
				}else{
					$arr_total_prev[$val["account_id"]] += (int)$val["total_money"];
				}
			}			
		}
	}
	$lstAccount = [];
//	$clsISO->print_pre($arr_account_parent_cached);die;
	$lst_accout_type = array_intersect_key($arr_account_cached, array_flip($arrAccount_type));
	foreach ($lst_accout_type as $account_id => $_oItem) { 
		$more_information = $clsISO->to_array_json($_oItem["more_information"]);
		$lst_accout_type[$account_id]["total"] = !empty($arr_total[$account_id]) ? $arr_total[$account_id] : 0;
		$lst_accout_type[$account_id]["total_prev"] = !empty($arr_total_prev[$account_id]) ? $arr_total_prev[$account_id] : 0;
		$lst_accout_type[$account_id]["code"] = $more_information["setting_code"];
		unset($more_information);
	}
	
	$lstAccount = $clsISO->buildTree($lst_accout_type,0,$clsSetting->pkey);	
	$lstBuildAccount = buildFlatTree($lstAccount);
	
	if($type == "ASSET" || $type == "LIABILITIES_EQUITY" ) {
		if($type == "ASSET") {
			$arr_category_money_cached = $clsSetting->getArraySearchByKey("_CRITERIA_ASSET_CATEGORY");
		}
		if($type == "LIABILITIES_EQUITY") {
			$arr_category_money_cached = $clsSetting->getArraySearchByKey("_LIABILITIES_EQUITY");
		}
//		$clsISO->print_pre($arr_category_money_cached);die;
		
//		$clsISO->print_pre($arr_cache_criteria_cached);die;
		if(!empty($arr_category_money_cached)) {
			$arr_total = [];
			foreach ($lstBuildAccount as $key => $val){
				$arr_total[$val["setting_id"]]["total"] = $val["total"];
				$arr_total[$val["setting_id"]]["total_prev"] = $val["total_prev"];
			}
			if($clsISO->_DEV()){
				$clsISO->print_pre($arr_total);die;
			}
			foreach ($arr_category_money_cached as $key => $val) {
				$more_information = $clsISO->to_array_json($val["more_information"]);
				$arr_category_money_cached[$key]["code"] = $more_information["setting_code"];
				$lst_account_id = !empty($more_information["lst_account_id"]) ? $more_information["lst_account_id"] : [];
				$arr_category_money_cached[$key]["lst_account_id"] = $lst_account_id;
				$lstItem = array_intersect_key($arr_total, array_flip($lst_account_id ));
				foreach($lstItem as $k => $v) {
					if(!isset($arr_category_money_cached[$key]["total"])) {
						$arr_category_money_cached[$key]["total"] = (int)$v["total"];
					}else{
						$arr_category_money_cached[$key]["total"] += (int)$v["total"];
					}
					if(!isset($arr_category_money_cached[$key]["total_prev"])) {
						$arr_category_money_cached[$key]["total_prev"] = (int)$v["total_prev"];
					}else{
						$arr_category_money_cached[$key]["total_prev"] += (int)$v["total_prev"];
					}
				}
				unset($lstItem,$lst_account_id,$more_information);
			}
			$lstAccount = $clsISO->buildTree($arr_category_money_cached,0,$clsSetting->pkey);	
			$lstBuildAccount = buildFlatTree($lstAccount);
		}
		
	}
//	$clsISO->print_pre($lstBuildAccount);die;
	$smarty->assign("title_page",$title_page);
	$smarty->assign("type",$type);
	if($type == "CASH_FLOW") {
		$smarty->assign("lstBuildAccount",$lstCategory_cash_flow);
	}else{
		$smarty->assign("lstBuildAccount",$lstBuildAccount);	
	}
	
	$smarty->assign("uid",$uid);
	$html = $core->build($sub.DS."_ajax.load_detail_cash_flow.tpl");
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
//		'barChartData' => $barChartData,
//		'callback' => $callback
	)); die();
}
function cash_flow_load_chart_cash_account(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));	
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
	
	$arrAccountBANK_BALANCE = $clsMoney->getAccountByType("BANK_BALANCE");
	$arrAccountTIENMAT = $clsMoney->getAccountByType("TIENMAT");
	$arrAccountCASH = $clsMoney->getAccountByType("CASH");
//	$dbconn->debug=true;
	$tmp = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCASH).") THEN `balance`
			ELSE 0 
		END) AS `total_money`
		FROM `default_v_account_balance` WHERE {$cond} AND `{$clsSetting->pkey}` IN (".implode(',',$arrAccountCASH).") GROUP BY `{$clsSetting->pkey}`");
	
//	$clsISO->print_pre($tmp);die;
	$dataPoints = $barChartData = array();
	$total_TIENMAT = $total_BANK = $total = 0;
	if(!empty($tmp)) {		
		$total = array_sum(array_column($tmp, 'total_money'));
		foreach ($tmp as $key => $val) {
			$total_money = (int)$val["total_money"];
			if($clsISO->checkItemInArray($val["setting_id"],$arrAccountTIENMAT)) {
				$total_TIENMAT += $total_money;
			}else{	
				$total_BANK += $total_money;
			}
		}
		$dataPoints = [
			[
				"label"		=>	"Tiền mặt",
				"color"		=>	"#ffab00",
				"y"			=>	$total_TIENMAT,
				"percent"	=>	($total > 0) ? round((int)$total_TIENMAT * 100/$total,1) : 0,
				"price_format"	=>	$clsISO->formatPrice((int)$total_TIENMAT).$clsISO->getRate()
			],
			[
				"label"		=>	"Tiền ngân hàng",
				"color"		=>	"#007bff",
				"y"			=>	$total_BANK,
				"percent"	=>	($total > 0) ? round((int)$total_BANK * 100/$total,1) : 0,
				"price_format"	=>	$clsISO->formatPrice((int)$total_BANK).$clsISO->getRate()
			],
		];
	}
	
	###
	$barChartData = array();
	##
	$data = [
		"axisX"	=>	[
			'interval' => 1
		],
		"type"	=>	"pie",
		"indexLabel"	=>	"{label}: {percent}%",
		"yValueFormatString"	=>	"#,##0",
		"toolTipContent"	=>	"<b>{label}</b>: {price_format}",
		"showInLegend"	=>	false,
		"dataPoints"	=>	$dataPoints,
	];
	$barChartData['animationEnabled'] = true;
	$barChartData['title'] = [
		"text"	=>	"Tổng tiền: ".$clsISO->shortNumber($total),
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
	$html = '<div class="form-row"><div class="col-12 col-md-6 col-xxl-6"><div id="'.$uid.'" class="chartContainer w-100 h-px-250"></div></div>';
	$html .= '<div class="col-12 col-md-6 col-xxl-6"><div class="d-flex gap-1 flex-column justify-content-end px-2 overflow-y-auto h-px-200">';
		foreach($dataPoints as $key => $val) {
			$html .= '<div class="d-flex align-items-start justify-content-between gap-2">
						<div class="d-flex align-items-start gap-1 fs-12">
							<span class="w-px-10 h-px-10 mt-1 rounded-pill" style="min-width:10px;background: '.$val["color"].'"></span>
							<span class="">'.$val["label"].'</span>
						</div>
						<span class="text-fs-14 fw-bold text-nowrap">'.($clsISO->formatPrice((int)$val["y"]).$clsISO->getRate()).'</span>
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
function cash_flow_load_chart_receivables_payables(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	###	
	if($month > 0){
		$format_time = "%d/%m/%Y";
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);			
		if(date("m-Y") == $my) {
			$start_date = date("d-m-Y",strtotime("- 1 months"));
			$start_time = strtotime("+1 days",strtotime($start_date));
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
			$start_date = date("01-m-Y",strtotime("- 11 months"));
			$start_time = strtotime($start_date);
			$end_time = strtotime(date("d-m-Y 23:59:59"));
		}else{
			$start_time = strtotime($date);
			$end_month = date(sprintf('t-12-%s 23:59:59', $year),$start_time);
			$end_time = strtotime($end_month);
		}
	}	
	$cond = " (`accounting_date` BETWEEN {$start_time} AND {$end_time})";
	
	$arrAccountPHAITHU = $clsMoney->getAccountByType("PHAITHU");
	$arrAccountPHAITRA = $clsMoney->getAccountByType("PHAITRA");
//	$dbconn->debug=true;
	$tmp = $dbconn->getAll("SELECT FROM_UNIXTIME(`accounting_date`,'{$format_time}') AS `date`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITHU).") AND `balance` > 0 THEN `balance` ELSE 0 END) AS `total_PHAITHU`,
		SUM(CASE WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccountPHAITRA).") AND `balance` < 0 THEN -`balance` ELSE 0 END) AS `total_PHAITRA`
		FROM `default_v_account_balance` WHERE {$cond} GROUP BY `date`");
//	$clsISO->print_pre($tmp);die;
	$arr_PHAITHU = $arr_PHAITRA = [];
	foreach ($tmp as $key => $val) {
		if(!isset($arr_PHAITHU[$val["date"]])) {
			$arr_PHAITHU[$val["date"]] = (int)$val["total_PHAITHU"];	
		}else{
			$arr_PHAITHU[$val["date"]] += (int)$val["total_PHAITHU"];
		}
		if(!isset($arr_PHAITRA[$val["date"]])) {
			$arr_PHAITRA[$val["date"]] = (int)$val["total_PHAITRA"];	
		}else{
			$arr_PHAITRA[$val["date"]] += (int)$val["total_PHAITRA"];
		}
	}
//	$clsISO->print_pre($arr_revenue);die;
	
	###
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];
	$barChartData['axisY'] = array(
		'labelFormatter' => 1
	);
//	$clsISO->print_pre($barChartData);die;
	
	$dataPoints_PHAITHU = $dataPoints_PHAITRA = array();
	$html = '<div id="'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	###
//	$dbconn->debug=true;
	if($month == 0){
		$a = 1;
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 months",$i)){
			$m = date("m/Y",$i);
			$total_PHAITHU = !empty($arr_PHAITHU[$m]) ? (int)$arr_PHAITHU[$m] : 0;
			$total_PHAITRA = !empty($arr_PHAITRA[$m]) ? (int)$arr_PHAITRA[$m] : 0;
			
			$dataPoints_PHAITHU[] = array(
				'label' => sprintf('%s', $m),
				'y' => $total_PHAITHU*1,
				'indexLabel' => ($total_PHAITHU > 0) ? $clsISO->shortNumber($total_PHAITHU) : "0"
			);
			$dataPoints_PHAITRA[] = array(
				'label' => sprintf('%s', $m),
				'y' => $total_PHAITRA*1,
				'indexLabel' => ($total_PHAITRA > 0) ? $clsISO->shortNumber($total_PHAITRA) : "0"
			);
		}
	} else {	
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 days",$i)){
			$d = date("d/m/Y",$i);	
			$dm = strtotime(str_replace("/","-",$d));
			$dm = date("d/m",$dm);
			$total_PHAITHU = !empty($arr_PHAITHU[$d]) ? (int)$arr_PHAITHU[$d] : 0;
			$total_PHAITRA = !empty($arr_PHAITRA[$d]) ? (int)$arr_PHAITRA[$d] : 0;		
			$dataPoints_PHAITHU[] = array(
				'label' => $dm,
				'y' => $total_PHAITHU*1,
				'indexLabel' => ($total_PHAITHU > 0) ? $clsISO->shortNumber($total_PHAITHU) : "0"
			);
			$dataPoints_PHAITRA[] = array(
				'label' => $dm,
				'y' => $total_PHAITRA*1,
				'indexLabel' => ($total_PHAITRA > 0) ? $clsISO->shortNumber($total_PHAITRA) : "0"
			);
		}
	}
//	$clsISO->print_pre($dataPoints_PHAITHU);die;
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "Thu {symbol}: {y}",
			"color"	 => "#1d6a01",
			"yValueFormatString"	 => '#,##0đ',
			"name"    => "Thu",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints_PHAITHU
		),
		array(
			"type"  => "column",
			"indexLabel" => "Trả {symbol}: {y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "Trả",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataPoints_PHAITRA
		)
	);
	$callback = '$Core.chart.canvas_multi(\''.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function cash_flow_load_chart_accruals(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$sub,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
	
	$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$format_time = "%d/%m/%Y";
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$date = sprintf('01-%s-%s', $clsISO->parseNumber($month), $year);			
		if(date("m-Y") == $my) {
			$start_date = date("d-m-Y",strtotime("- 1 months"));
			$start_time = strtotime("+1 days",strtotime($start_date));
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
			$start_date = date("01-m-Y",strtotime("- 11 months"));
			$start_time = strtotime($start_date);
			$end_time = strtotime(date("d-m-Y 23:59:59"));
		}else{
			$start_time = strtotime($date);
			$end_month = date(sprintf('t-12-%s 23:59:59', $year),$start_time);
			$end_time = strtotime($end_month);
		}
	}	
	$cond = " (`t2`.`accounting_date` BETWEEN {$start_time} AND {$end_time})";
	$arrAccountDUTHU = $clsMoney->getAccountByType("DUKIENTHU");
	$arrAccountDUCHI = $clsMoney->getAccountByType("DUKIENCHI");	
	#
	$tmp = $dbconn->getALl("SELECT FROM_UNIXTIME(`t2`.`accounting_date`,'{$format_time}') AS `date`,
		(SUM(CASE WHEN account_id IN (".implode(',',$arrAccountDUTHU).") THEN debit_amount ELSE 0 END)
			- SUM(CASE WHEN corresponding_account_id IN (".implode(',',$arrAccountDUTHU).") THEN credit_amount ELSE 0 END)) AS `total_DUTHU`,
		(SUM(CASE WHEN corresponding_account_id IN (".implode(',',$arrAccountDUCHI).") THEN credit_amount ELSE 0 END)
			- SUM(CASE WHEN account_id IN (".implode(',',$arrAccountDUCHI).") THEN debit_amount ELSE 0 END)) AS `total_DUCHI`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id` 
		WHERE {$cond}");
	
	$arr_DUTHU = $arr_DUCHI = [];
	foreach ($tmp as $key => $val) {
		if(!isset($arr_DUTHU[$val["date"]])) {
			$arr_DUTHU[$val["date"]] = (int)$val["total_DUTHU"];	
		}else{
			$arr_DUTHU[$val["date"]] += (int)$val["total_DUTHU"];
		}
		if(!isset($arr_DUCHI[$val["date"]])) {
			$arr_DUCHI[$val["date"]] = (int)$val["total_DUCHI"];	
		}else{
			$arr_DUCHI[$val["date"]] += (int)$val["total_DUCHI"];
		}
	}
	
	###
	$dataPoints_DUTHU = $dataPoints_DUCHI = array();
	$html = '<div id="chart_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	###
	if($month == 0){
		$a = 1;
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 months",$i)){
			$m = date("m/Y",$i);
			$total_DUTHU = !empty($arr_DUTHU[$m]) ? (int)$arr_DUTHU[$m] : 0;
			$total_DUCHI = !empty($arr_DUCHI[$m]) ? (int)$arr_DUCHI[$m] : 0;
			$dataPoints_DUTHU[] = array(
				'label' => sprintf('%s', $m),
				'y' => $total_DUTHU*1,
				'indexLabel' => ($total_DUTHU > 0) ? $clsISO->shortNumber($total_DUTHU) : "0"
			);
			$dataPoints_DUCHI[] = array(
				'label' => sprintf('%s', $m),
				'y' => $total_DUCHI*1,
				'indexLabel' => ($total_DUCHI > 0) ? $clsISO->shortNumber($total_DUCHI) : "0"
			);
		}
	} else {		
		for($i=$start_time; $i<=$end_time; $i = strtotime("+1 days",$i)){
			$d = date("d/m/Y",$i);	
			$dm = strtotime(str_replace("/","-",$d));
			$dm = date("d/m",$dm);
			$total_DUTHU = !empty($arr_DUTHU[$d]) ? (int)$arr_DUTHU[$d] : 0;
			$total_DUCHI = !empty($arr_DUCHI[$d]) ? (int)$arr_DUCHI[$d] : 0;		
			$dataPoints_DUTHU[] = array(
				'label' => $dm,
				'y' => $total_DUTHU*1,
				'indexLabel' => ($total_DUTHU > 0) ? $clsISO->shortNumber($total_DUTHU) : "0"
			);
			$dataPoints_DUCHI[] = array(
				'label' => $dm,
				'y' => $total_DUCHI*1,
				'indexLabel' => ($total_DUCHI > 0) ? $clsISO->shortNumber($total_DUCHI) : "0"
			);
		}
	}
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];	
	$barChartData['axisY'] = array(
		'labelFormatter' => 1
	);
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "Dự thu {symbol}: {y}",
			"color"	 => "#71dd37",
			"yValueFormatString"	 => '#,##0đ',
			"name"    => "Dự thu",
			"showInLegend"    => true,
			"lineColor"	=> "#71dd37",
			"markerColor"	=>"#71dd37",
			"markerSize"	=>5,
			"markerType"	=>"circle",
			"dataPoints"    => $dataPoints_DUTHU
		),
		array(
			"type"  => "column",
			"indexLabel" => "Dự chi {symbol}: {y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "Dự chi",
			"color"	 => "#ff3e1d",
			"showInLegend" => true,
			"lineColor"	=> "#ff3e1d",
			"markerColor"	=>"#ff3e1d",
			"markerSize"	=>5,
			"markerType"	=>"circle",
			"dataPoints"   => $dataPoints_DUCHI
		)
	);
	$callback = '$Core.chart.canvas_multi(\'chart_'.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function cash_flow_load_chart_liabilities_equity(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsProperty = new Property();
	$clsSetting  = new Setting ();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$dataPoints_Expense = $dataPoints_Income = array();
	###	
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
	
	$arrAccount_liabilities_equity = $clsMoney->getAccountByType("LIABILITIES_EQUITY");	
	$arrAccount_liabilities_debit = $clsMoney->getAccountByType("LIABILITIES_DEBIT");	
	$arrAccount_equity_debit = $clsMoney->getAccountByType("EQUITY_DEBIT");	
	$arrAccount_liabilities_equity = array_diff($arrAccount_liabilities_equity,$arrAccount_liabilities_debit);
	$arrAccount_liabilities_equity = array_diff($arrAccount_liabilities_equity,$arrAccount_equity_debit);
	#
//	$dbconn->debug=true;
	$tmp = $dbconn->getAll("SELECT `{$clsSetting->pkey}` AS `account_id`,
		SUM(CASE 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_liabilities_debit).") THEN `balance`
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_equity_debit).") THEN `balance`
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_liabilities_equity).") THEN -`balance`
			ELSE 0 
		END) AS `total`
	FROM default_v_account_balance WHERE `accounting_date` < '{$end_time}' AND `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_liabilities_equity).") GROUP BY `account_id`");
//	===============================
	$arr_total = [];
	if(!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			if(!isset($arr_total[$val["account_id"]])) {
				$arr_total[$val["account_id"]] = (int)$val["total"];
			}else{
				$arr_total[$val["account_id"]] += (int)$val["total"];
			}						
		}
	}
	$arr_account_cached = $clsSetting->getArraySearchByKey("_ACCOUNT");
	$lst_accout_type = array_intersect_key($arr_account_cached, array_flip($arrAccount_liabilities_equity));
//	$clsISO->print_pre($lst_accout_type);die;
	foreach ($lst_accout_type as $account_id => $_oItem) { 
		$more_information = $clsISO->to_array_json($_oItem["more_information"]);
		$lst_accout_type[$account_id]["total"] = !empty($arr_total[$account_id]) ? $arr_total[$account_id] : 0;
		$lst_accout_type[$account_id]["code"] = $more_information["setting_code"];
		unset($more_information);
	}
	$lstAccount = $clsISO->buildTree($lst_accout_type,0,$clsSetting->pkey);	
	$lstBuildAccount = buildFlatTree($lstAccount);
	
	$arr_cache_liabilities_equity = $clsSetting->getArraySearchByKey("_LIABILITIES_EQUITY");
	$arr_total = [];
	foreach ($lstBuildAccount as $key => $val){
		$arr_total[$val["setting_id"]]["total"] = $val["total"];
		$arr_total[$val["setting_id"]]["total_prev"] = $val["total_prev"];
	}
	foreach ($arr_cache_liabilities_equity as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		$arr_cache_liabilities_equity[$key]["code"] = $more_information["setting_code"];
		$arr_cache_liabilities_equity[$key]["bgcolor"] = $more_information["bgcolor"];
		$lst_account_id = !empty($more_information["lst_account_id"]) ? $more_information["lst_account_id"] : [];
		$arr_cache_liabilities_equity[$key]["lst_account_id"] = $lst_account_id;
		$lstItem = array_intersect_key($arr_total, array_flip($lst_account_id ));
		if(!empty($lstItem)) {
			foreach($lstItem as $k => $v) {
				if(!isset($arr_cache_liabilities_equity[$key]["total"])) {
					$arr_cache_liabilities_equity[$key]["total"] = (int)$v["total"];
				}else{
					$arr_cache_liabilities_equity[$key]["total"] += (int)$v["total"];
				}
			}
		}else{
			$arr_cache_liabilities_equity[$key]["total"] = 0;
		}
		
		unset($lstItem,$lst_account_id,$more_information);
	}
	$lstAccount = $clsISO->buildTree($arr_cache_liabilities_equity,0,$clsSetting->pkey);	
	$lstBuildAccount = buildFlatTree($lstAccount);
//	$clsISO->print_pre($lstBuildAccount);die;
	$result = array_filter($lstBuildAccount, function($item) {
		return $item['parent_id'] == 0;
	});
//	$clsISO->print_pre($result);die;
//	===============================
	$dataPoints = $arr_cash = $data = $barChartData = array();
	$data['axisX'] = array(
		'interval' => 1
	);
	$total_cash = $total = 0;
	if(!empty($tmp)) {		
		$total = array_sum(array_column($tmp, 'total'));
		foreach ($result as $key => $val) {
			$total_item = (int)$val["total"];
			$dataPoints[] = [
				"label"		=>	$val["title"],
				"color"		=>	$val["bgcolor"],
				"y"			=>	$total_item,
				"percent"	=>	($total > 0) ? round((int)$total_item * 100/$total,1) : 0,
				"price_format"	=>	$clsISO->formatPrice((int)$total_item).$clsISO->getRate()
			];
		}
	}
	##
	$data['type'] = 'pie';
	$data['indexLabel'] = '{label}: {percent}%';
	$data['yValueFormatString'] = '#,##0';
	$data['toolTipContent'] = '<b>{label}</b>: {price_format}';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['title'] = [
		"text"	=>	"Tổng vốn: ".$clsISO->shortNumber($total),
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
	$html = '<div class="form-row"><div class="col-12 col-md-6 col-xxl-6"><div id="'.$uid.'" class="chartContainer w-100 h-px-250"></div></div>';
	$html .= '<div class="col-12 col-md-6 col-xxl-6"><div class="d-flex gap-1 flex-column justify-content-end px-2 overflow-y-auto h-px-200">';
				foreach($dataPoints as $key => $val) {
					$html .= '<div class="d-flex align-items-start justify-content-between gap-2">
								<div class="d-flex align-items-start gap-1 fs-12">
									<span class="w-px-10 h-px-10 mt-1 rounded-pill" style="min-width:10px;background: '.$val["color"].'"></span>
									<span class="">'.$val["label"].'</span>
								</div>
								<span class="text-fs-14 fw-bold text-nowrap">'.(($val["y"] > 0)?$clsISO->shortNumber($val["y"]):"0đ").'</span>
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
function cash_flow_load_chart_assets(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$sub,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
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
	
	$arrAccount_short_term = $clsMoney->getAccountByType("SHORT_TERM_ASSET");// TK tài sản ngắn hạn
	$arrAccount_long_term = $clsMoney->getAccountByType("LONG_TERM_ASSET"); // TK tài sản dài hạn
	$arrAccount_fixed_assets = $clsMoney->getAccountByType("FIXED_ASSETS"); // TK tài sản cố định
	$arrAccount_asset_depriciable = $clsMoney->getAccountByType("ASSET_DEPRICIABLE");	// TK tài sản khấu hao
	$arrAccount_asset_profision = $clsMoney->getAccountByType("ASSET_PROVISION");	// TK tài sản dự phòng
	#
//	$dbconn->debug=true;
	
	$tmp = $dbconn->getALl("SELECT 
		-- NGẮN HẠN
		SUM(CASE 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_short_term).") AND balance > 0 THEN balance 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_asset_profision).") THEN balance
			ELSE 0
		END) AS short_assets,		
		-- DÀI HẠN
		SUM(CASE 
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_long_term).") AND balance > 0 THEN balance
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_fixed_assets).") THEN balance
			WHEN `{$clsSetting->pkey}` IN (".implode(',',$arrAccount_asset_depriciable).") THEN balance
			ELSE 0
		END) AS long_assets

		FROM default_v_account_balance WHERE `accounting_date` <= '{$end_time}';");
//	$clsISO->print_pre($tmp);die;
	$short_assets = $long_assets = 0;
	$arr_DUTHU = $arr_DUCHI = [];
	foreach ($tmp as $key => $val) {
		$short_assets += (int)$val["short_assets"];
		$long_assets += (int)$val["long_assets"];
	}
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip'] = [
		"shared"	=>	true
	];	
	$total = $short_assets + $long_assets;
	$barChartData['title'] = [
		"text"	=>	"Tổng tài sản: ".$clsISO->shortNumber($total),
		"verticalAlign" => "bottom",
        "fontSize" => 20,
        "fontWeight" =>  "bold"
	];
	$dataPoints = [
		[
			"label"		=>	"Tài sản ngắn hạn",
			"color"		=>	"#71dd37",
			"y"			=>	$short_assets,
			"percent"	=>	($total > 0) ? round((int)$short_assets * 100/$total,1) : 0,
			"price_format"	=>	$clsISO->formatPrice((int)$short_assets).$clsISO->getRate()
		],
		[
			"label"		=>	"Tài sản dài hạn",
			"color"		=>	"#ff3e1d",
			"y"			=>	$long_assets,
			"percent"	=>	($total > 0) ? round((int)$long_assets * 100/$total,1) : 0,
			"price_format"	=>	$clsISO->formatPrice((int)$long_assets).$clsISO->getRate()
		]
	];
	##
	$data['type'] = 'pie';
	$data['indexLabel'] = '{label}: {percent}%';
	$data['yValueFormatString'] = '#,##0';
	$data['toolTipContent'] = '<b>{label}</b>: {price_format}';
	$data['showInLegend'] = false;
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	$html = '<div class="form-row"><div class="col-12 col-md-6 col-xxl-6"><div id="'.$uid.'" class="chartContainer w-100 h-px-250"></div></div>';
	$html .= '<div class="col-12 col-md-6 col-xxl-6"><div class="d-flex gap-1 flex-column justify-content-end px-2 overflow-y-auto h-px-200">';
				foreach($dataPoints as $key => $val) {
					$html .= '<div class="d-flex align-items-start justify-content-between gap-2">
								<div class="d-flex align-items-start gap-1 fs-12">
									<span class="w-px-10 h-px-10 mt-1 rounded-pill" style="min-width:10px;background: '.$val["color"].'"></span>
									<span class="">'.$val["label"].'</span>
								</div>
								<span class="text-fs-14 fw-bold text-nowrap">'.(($val["y"] > 0)?$clsISO->shortNumber($val["y"]):"0đ").'</span>
							</div>';
				}
	$html .= '</div></div></div>';
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
