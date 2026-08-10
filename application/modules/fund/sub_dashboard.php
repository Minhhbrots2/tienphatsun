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
function dashboard_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsMoneyAccount = new MoneyAccount();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
	###
	$list_company = $clsSetting->getCacheItems('_GROUP_COMPANY');
	$smarty->assign('list_company', $list_company);
	#
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	#
	$arr_boxs = array(
		array(
			'title' => 'Tổng thu',
			'cls' => 'fund_debit'
		), array(
			'title' => 'Tổng chi',
			'cls' => 'fund_credit'
		), array(
			'title' => 'Dự chi',
			'cls' => 'fund_credit'
		), array(
			'title' => 'Tổng tài sản',
			'cls' => 'fund_assets'
		), array(
			'title' => 'Lợi nhuận',
			'cls' => 'fund_profit'
		)
	);
	$smarty->assign('arr_boxs', $arr_boxs);
	#
	$arr_nett_profit_filter = array(
		'gross_profit' => 'LN gộp',
		'net_profit' => 'LN thuần',
		'profit_before_tax' => 'LN trước thuế',
		'profit_after_tax' => 'LN sau thuế',
	);
	$smarty->assign('arr_nett_profit_filter', $arr_nett_profit_filter);
	#
	$start_year = 2023;
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $current_year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = sprintf('%s-01-01', $current_year);
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_date = sprintf('%s-12-%s', $current_year, $end_day);
	$assign_list['start_date'] = $start_date;
	$assign_list['end_date'] = $end_date;
	$assign_list['prev_year'] = $prev_year;
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	
	// Dự kiến Chi
	$planned_expected_expense = $clsConfiguration->getValue('planned_expected_expense');
	$planned_expected_expense = $clsISO->to_array_json($planned_expected_expense);
	$data = $core->get_field($planned_expected_expense, "data", []);
	
	// $clsISO->print_pre($planned_expected_expense); die();
	$assign_list["planned_expected_expense"] = $planned_expected_expense;
	/*=============Title & Description Page==================*/
	$title_page = 'Tài chính - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}


function dashboard_load_total_summary(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	
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
	// Tổng tài sản
	$total_assets = $total_assets_prev = 0;
	// Tông thu
	$total_income = $total_income_prev = 0;
	// Tổng chi
	$total_expense = $total_expense_prev = 0;
	// Lợi nhận
	$total_profit = $total_profit_prev = 0;
	// Dự chi
	$total_expected_expense = $total_expected_expense_prev = 0;
	#
	// Dự kiến Chi
	$planned_expected_expense = $clsConfiguration->getValue('planned_expected_expense');
	$planned_expected_expense = $clsISO->to_array_json($planned_expected_expense);
	$data = $core->get_field($planned_expected_expense, "data", []);
	$arr_expected_expense = $core->get_field($data, $company_name, []);
	if(!empty($arr_expected_expense)){
		foreach($arr_expected_expense as $key => $val){
			$expected_amount = $val['expected_amount'];
			$expected_amount = $clsISO->processSmartNumber($expected_amount);
			$total_expected_expense += $expected_amount;
		}
	}
	$tmp = $dbconn->getRow("SELECT SUM(`t1`.`debit_amount` - `t1`.`credit_amount`) AS `total_assets` 
		FROM {$clsMoneyItem->tbl} AS `t1` 
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id`
		WHERE `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('ASSET'))."') AND (`t2`.`accounting_date` BETWEEN {$start_time} AND {$end_time})");
	$tmp_prev = $dbconn->getRow("SELECT SUM(`t1`.`debit_amount` - `t1`.`credit_amount`) AS `total_assets` 
		FROM {$clsMoneyItem->tbl} AS `t1` 
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id`
		WHERE `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('ASSET'))."') AND (`t2`.`accounting_date` BETWEEN {$start_time_prev} AND {$end_time_prev})");
	if(!empty($tmp)){
		$total_assets = $core->get_field($tmp, "total_assets", 0);
	}
	if(!empty($tmp_prev)){
		$total_assets_prev = $core->get_field($tmp_prev, "total_assets", 0);
	}
	$tmp = $dbconn->getRow("SELECT
		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCTHU'))."') 
			THEN `t1`.`credit_amount` 
			ELSE 0 
		END) AS `total_income`,

		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCCHI'))."') 
			THEN `t1`.`debit_amount` 
			ELSE 0 
		END) AS `total_expense`,

		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCTHU'))."') 
			THEN `t1`.`credit_amount` 
			ELSE 0 
		END)
		-
		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCCHI'))."') 
			THEN `t1`.`debit_amount` 
			ELSE 0 
		END) AS `total_profit`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id`
		WHERE (`t2`.`accounting_date` BETWEEN {$start_time} AND {$end_time})
	");
	$tmp_prev = $dbconn->getRow("SELECT
		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCTHU'))."') 
			THEN `t1`.`credit_amount` 
			ELSE 0 
		END) AS `total_income`,

		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCCHI'))."') 
			THEN `t1`.`debit_amount` 
			ELSE 0 
		END) AS `total_expense`,

		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCTHU'))."') 
			THEN `t1`.`credit_amount` 
			ELSE 0 
		END)
		-
		SUM(CASE 
			WHEN `t1`.`account_id` IN ('".implode('\',\'', $clsMoney->getAccountByType('THUCCHI'))."') 
			THEN `t1`.`debit_amount` 
			ELSE 0 
		END) AS `total_profit`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id`
		WHERE (`t2`.`accounting_date` BETWEEN {$start_time_prev} AND {$end_time_prev})
	");
	if(!empty($tmp)){
		$total_income = $core->get_field($tmp, "total_income", 0);
		$total_expense = $core->get_field($tmp, "total_expense", 0);
		$total_profit = $core->get_field($tmp, "total_profit", 0);
	}
	if(!empty($tmp_prev)){
		$total_income_prev = $core->get_field($tmp_prev, "total_income", 0);
		$total_expense_prev = $core->get_field($tmp_prev, "total_expense", 0);
		$total_profit_prev = $core->get_field($tmp_prev, "total_profit", 0);
	}
	#
	if($total_income_prev > 0){
		$ratio_income = round(($total_income - $total_income_prev)*100/$total_income_prev,1)."%";
	}else{
		$ratio_income = $clsISO->shortNumber($total_income);
	}
	$smarty->assign("total_income",$total_income);
	$smarty->assign("total_income_prev",$total_income_prev);
	$smarty->assign("ratio_income",$ratio_income);
	#
	if($total_assets_prev > 0){
		$ratio_assets = round(($total_assets - $total_assets_prev)*100/$total_assets_prev,1)."%";
	}else{
		$ratio_assets = $clsISO->shortNumber($total_income);
	}	
	$smarty->assign("total_assets",$total_assets);
	$smarty->assign("total_assets_prev",$total_assets_prev);
	$smarty->assign("ratio_assets",$ratio_assets);
	#
	if($total_expense_prev > 0){
		$ratio_expense = round(($total_expense - $total_expense_prev)*100/$total_expense_prev,1)."%";
	}else{
		$ratio_expense = $clsISO->shortNumber($total_expense);
	}	
	$smarty->assign("total_expense",$total_expense);
	$smarty->assign("total_expense_prev",$total_expense_prev);
	$smarty->assign("ratio_expense",$ratio_expense);
	#
	if($total_profit_prev > 0){
		$ratio_profit = round(($total_profit - $total_profit_prev)*100/$total_profit_prev,1)."%";
	}else{
		$ratio_profit = $clsISO->shortNumber($total_profit);
	}
	$smarty->assign("total_profit",$total_profit);
	$smarty->assign("total_profit_prev",$total_profit_prev);
	$smarty->assign("ratio_profit",$ratio_profit);
	$smarty->assign("total_expected_expense",$total_expected_expense);
	$html = $core->build("dashboard".DS."_ajax.load_total.tpl");
	// Return
	echo json_encode(array(
		"html"	=>	$html
	));die;
}
function dashboard_load_expected_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID, $clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsConfiguration = new Configuration();
	
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
	// Dự kiến Chi
	$planned_expected_expense = $clsConfiguration->getValue('planned_expected_expense');
	$planned_expected_expense = $clsISO->to_array_json($planned_expected_expense);
	$data = $core->get_field($planned_expected_expense, "data", []);
	$arr_expected_expense = $core->get_field($data, $company_name, []);
	if(!empty($arr_expected_expense)){
		foreach($arr_expected_expense as $key => $val){
			$expected_amount = $val['expected_amount'];
			$expected_amount = $clsISO->processSmartNumber($expected_amount);
			$total_expected_expense += $expected_amount;
			$html.= '<tr>
				<td>'.$val['expected_date'].'</td>
				<td>'.$val['expected_month'].'</td>
				<td>'.$val['expense_group'].'</td>
				<td>'.$val['paid_by'].'</td>
				<td class="text-center">'.$val['office_name'].'</td>
				<td>'.$val['project_name'].'</td>
				<td>'.$val['partner_group'].'</td>
				<td>'.$val['content'].'</td>
				<td class="text-right">'.$val['expected_amount'].'</td>
				<td class="text-center">
					<span class="text-danger">'.$val['status'].'</span>
				</td>
			</tr>';
		}
	}
	// Return
	echo json_encode(array(
		"html"	=>	$html,
		'callback' => '$(\'.total_expected_expense\').text(\''.$clsISO->shortNumber($total_expected_expense,2).'\')'
	));die;
}
function dashboard_load_nett_profit(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsMoneyAccount = new MoneyAccount();
	$clsSetting = new Setting();
	
	$uid = $clsISO->getUniqid();
	$currentYear = Input::post('year', date('Y'));
	$currentMonth = date('n');
    $currentQuarter = ceil($currentMonth / 3);

    $list_terms = [];
    for ($i = 0; $i < 4; $i++) {
        $q = $currentQuarter - $i;
        $y = $currentYear;
        // Xử lý lùi năm nếu cần
        while ($q <= 0) {
            $q += 4;
            $y--;
        }
        // Xác định tháng bắt đầu và kết thúc của quý
		$startMonth = ($q - 1) * 3 + 1;
        $endMonth = $startMonth + 2;
        // Ngày bắt đầu
        $startDate = strtotime("$y-$startMonth-01");
        // Ngày kết thúc (ngày cuối tháng)
        $endDate = strtotime("$y-$endMonth-01");
        $list_terms[] = [
            'quarter' => "Q$q - $y",
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
	$list_terms = array_reverse($list_terms);
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	// $barChartData['dataPointMaxWidth'] = 1000;
	$barChartData['toolTip']['shared'] = true;
	$barChartData['axisY'] = array(
		// 'title' => 'Doanh số bán hàng',
		// 'titleFontSize' => '16',
		// 'titleFontColor' => '#1d6a01',
		// 'lineColor' => '#1d6a01',
		// 'labelFontColor' => '#1d6a01',
		// 'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	$data_gross_profit_Points = $data_net_profit_Points = array();
	foreach($list_terms as $key => $val){
		$start_date = $val['start_date'];
		$end_date = $val['end_date'];
		$my = date('m/Y', $i);
		$revenue = $cost = $expense = $other_income = $other_expense = $tax = 0;
		$sql_query = "WITH `account_balance` AS (
			SELECT 
				`t1`.`account_id`,
				`m`.`group_code`,
				CASE 
					WHEN `m`.`account_type` = 'debit'
						THEN `t1`.`debit_amount` - `t1`.`credit_amount`
					WHEN `m`.`account_type` = 'credit'
						THEN `t1`.`credit_amount` - `t1`.`debit_amount`
					WHEN `m`.`account_type` = 'both'
						THEN `t1`.`debit_amount` - `t1`.`credit_amount`
					ELSE 0
				END AS `amount`
			FROM {$clsMoneyItem->tbl} AS `t1`
			JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = t2.`money_id`
			JOIN {$clsMoneyAccount->tbl} AS `m` ON `t1`.`account_id` = `m`.`account_id`
			WHERE (`t2`.`accounting_date` BETWEEN '{$start_date}' AND '{$end_date}')
		)
		SELECT
			SUM(CASE WHEN group_code = 'REVENUE' THEN amount ELSE 0 END) AS revenue,
			SUM(CASE WHEN group_code = 'COST' THEN amount ELSE 0 END) AS cost,
			SUM(CASE WHEN group_code = 'EXPENSE' THEN amount ELSE 0 END) AS expense,
			SUM(CASE WHEN group_code = 'OTHER_INCOME' THEN amount ELSE 0 END) AS other_income,
			SUM(CASE WHEN group_code = 'OTHER_EXPENSE' THEN amount ELSE 0 END) AS other_expense,
			SUM(CASE WHEN group_code = 'TAX' THEN amount ELSE 0 END) AS tax
		FROM `account_balance`";
		// $dbconn->debug = true;
		$tmp = $dbconn->getRow($sql_query);
		if(!empty($tmp)){
			$revenue = $core->get_money_field($tmp, "revenue", 0);
			$cost = $core->get_money_field($tmp, "cost", 0);
			$expense = $core->get_money_field($tmp, "expense", 0);
			$other_income = $core->get_money_field($tmp, "other_income", 0);
			$other_expense = $core->get_money_field($tmp, "other_expense", 0);
			$tax = $core->get_money_field($tmp, "tax", 0);
		}
		// Lợi nhuận gộp
		$gross_profit = $revenue - $cost;
		// Lợi nhuận thuần
		$net_profit = $gross_profit - $expense;
		// Lợi nhuận trước thuế
		$profit_before_tax = $net_profit + $other_income - $other_expense;
		// Lợi nhuận sau thuế
		$profit_after_tax = $profit_before_tax - $tax;
		$data_gross_profit_Points[] = array(
			'label' => sprintf('%s', $val['quarter']),
			'y'	=> $gross_profit,
			'indexLabel' => $clsISO->shortNumber($gross_profit),
		);
		$data_net_profit_Points[] = array(
			'label' => sprintf('%s', $val['quarter']),
			'y'	=> $net_profit,
			'indexLabel' => $clsISO->shortNumber($net_profit),
		);
		$data_profit_before_tax_Points[] = array(
			'label' => sprintf('%s', $val['quarter']),
			'y'	=> $profit_before_tax,
			'indexLabel' => $clsISO->shortNumber($profit_before_tax),
		);
		$data_profit_after_tax_Points[] = array(
			'label' => sprintf('%s', $val['quarter']),
			'y'	=> $profit_after_tax,
			'indexLabel' => $clsISO->shortNumber($profit_after_tax),
		);
	}
	// die();
	$html.= '<div id="'.$uid.'" class="chartContainer h-px-300 mb-2"></div>
	<ul class="list-unstyled mb-0">
		<li class="d-flex align-items-center border-bottom justify-content-between py-1">
			<span class="w-40">Doanh thu</span>
			<span>'.$clsISO->shortNumber(1200000000).'</span>
		</li>
		<li class="d-flex align-items-center border-bottom justify-content-between py-1">
			<span class="w-40">Giá vốn</span>
			<span>'.$clsISO->shortNumber(1200000000).'</span>
		</li>
		<li class="d-flex align-items-center justify-content-between py-1">
			<span class="w-40">Lội nhuận gộp</span>
			<span>'.$clsISO->shortNumber(1200000000).'</span>
		</li>
	</ul>';
	$barChartData['data'] = array(
		array(
			"type"    => "stackedColumn",
			"indexLabel" => "{y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"    => "LN gộp",
			"showInLegend" => true,
			"dataPoints" => $data_gross_profit_Points
		), array(
			"type"  => "stackedColumn",
			"indexLabel" => "{y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "LN thuần",
			"showInLegend" => true,
			"dataPoints"   => $data_net_profit_Points
		), array(
			"type"  => "stackedColumn",
			"indexLabel" => "{y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "LN trước thuế",
			"showInLegend" => true,
			"dataPoints"   => $data_profit_before_tax_Points
		), array(
			"type"  => "stackedColumn", // stackedColumn
			"indexLabel" => "{y}",
			"yValueFormatString"	 => '#,##0đ',
			"name"	=> "LN sau thuế",
			"showInLegend" => true,
			"dataPoints"   => $data_profit_after_tax_Points
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData,
	)); die();
}
function dashboard_load_asset_summary(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsMoneyAccount = new MoneyAccount();
	$clsSetting = new Setting();
	
	$year = (int) Input::post('year', date('Y'));
	$arr_account_cash = $clsMoney->getAccountByType('MOEY');
	$arr_account_invest = $clsMoney->getAccountByType('INVEST');
	$arr_account_receivable = $clsMoney->getAccountByType('PHAITHU');
	$arr_account_advance = $clsMoney->getAccountByType('TAMUNG');
	$arr_account_fixed_asset = $clsMoney->getAccountByType('FIXED_ASSETS');
	$arr_account_long_term_invest = $clsMoney->getAccountByType('LONG_TERM_INVEST');
	$arr_account_cip = $clsMoney->getAccountByType('CIP');
	$arr_account_provision = $clsMoney->getAccountByType('PROVISION');
	
	$sql_query = "SELECT
		SUM(CASE WHEN x.group_code = 'CASH' THEN amount ELSE 0 END) AS cash,
		SUM(CASE WHEN x.group_code = 'INVEST' THEN amount ELSE 0 END) AS investment,
		SUM(CASE WHEN x.group_code = 'PHAITHU' THEN amount ELSE 0 END) AS receivable,
		SUM(CASE WHEN x.group_code = 'TAMUNG' THEN amount ELSE 0 END) AS advance,
		SUM(CASE WHEN x.group_code = 'FIXED_ASSETS' THEN amount ELSE 0 END) AS fixed_asset,
		SUM(CASE WHEN x.group_code = 'LONG_TERM_INVEST' THEN amount ELSE 0 END) AS long_term_invest,
		SUM(CASE WHEN x.group_code = 'CIP' THEN amount ELSE 0 END) AS cip,
		SUM(CASE WHEN x.group_code = 'PROVISION' THEN amount ELSE 0 END) AS provision
	FROM (
		SELECT 
			t1.account_id,
			m.group_code,
			CASE 
				WHEN m.account_type = 'credit'
					THEN t1.credit_amount - t1.debit_amount
				WHEN m.account_type = 'debit'
					THEN t1.debit_amount - t1.credit_amount
				WHEN m.account_type = 'both'
					THEN GREATEST(t1.debit_amount - t1.credit_amount, 0)
				ELSE 0
			END AS amount
		FROM {$clsMoneyItem->tbl} t1
		JOIN {$clsMoney->tbl} t2 ON t1.money_id = t2.money_id
		JOIN {$clsMoneyAccount->tbl} m ON t1.account_id = m.account_id
		WHERE FROM_UNIXTIME(t2.accounting_date,'%Y') <= '{$year}'
	) AS `x`";
	$cash = $investment = $receivable = $advance = $fixed_asset = $long_term_invest = $cip = $provision = 0;
	// $tmp = $dbconn->getAll($sql_query);
	$tmp = $dbconn->getRow($sql_query);
	// $clsISO->print_pre($tmp); die();
	if(!empty($tmp)){
		$cash = $core->get_money_field($tmp, "cash", 0);
		$investment = $core->get_money_field($tmp, "investment", 0);
		$receivable = $core->get_money_field($tmp, "receivable", 0);
		$advance = $core->get_money_field($tmp, "advance", 0);
		$fixed_asset = $core->get_money_field($tmp, "fixed_asset", 0);
		$long_term_invest = $core->get_money_field($tmp, "long_term_invest", 0);
		$cip = $core->get_money_field($tmp, "cip", 0);
		$provision = $core->get_money_field($tmp, "provision", 0);
	}
	$total = $cash + $investment + $receivable + $advance + $fixed_asset + $long_term_invest + $cip + $provision;
	
	$html.= '<!-- Total Amount -->
	<div class="total-row">
		<span class="total-amount">'.$clsISO->shortNumber($total).'</span>
		<span class="badge bg-label-success">
			<svg viewBox="0 0 10 10" fill="currentColor"><polygon points="5,1 9,9 1,9"/></svg> 1,9%
		</span>
	</div>
	<div class="compare-label mt-n2 mb-2">So với tháng trước</div>
	<!-- Stacked Bar Chart -->
	<div class="chart-area">
		<!-- Tiền (cash) — tallest -->
		<div class="bar-group">
			<div class="bar-stack" style="height:128px;">
				<div class="bar-seg" style="height:80%; background:#2563eb;"></div>
				<div class="bar-seg" style="height:20%; background:#60a5fa;"></div>
			</div>
		</div>
		<!-- Hàng tồn kho -->
		<div class="bar-group">
			<div class="bar-stack" style="height:88px;">
				<div class="bar-seg" style="height:55%; background:#f59e0b;"></div>
				<div class="bar-seg" style="height:45%; background:#2563eb;"></div>
			</div>
		</div>
		<!-- Đầu tư -->
		<div class="bar-group">
			<div class="bar-stack" style="height:68px;">
				<div class="bar-seg" style="height:48%; background:#f59e0b;"></div>
				<div class="bar-seg" style="height:52%; background:#22c55e;"></div>
			</div>
		</div>
		<!-- Khác -->
		<div class="bar-group">
			<div class="bar-stack" style="height:14px;">
				<div class="bar-seg" style="height:100%; background:#d1d5db;"></div>
			</div>
		</div>
	</div>
	<!-- Legend -->
	<div class="legend">
		<div class="legend-item"><div class="legend-dot" style="background:#2563eb;"></div> Tiền</div>
		<div class="legend-item"><div class="legend-dot" style="background:#60a5fa;"></div> Phải thu</div>
		<div class="legend-item"><div class="legend-dot" style="background:#f59e0b;"></div> Hàng tồn kho</div>
		<div class="legend-item"><div class="legend-dot" style="background:#22c55e;"></div> Đầu tư</div>
		<div class="legend-item"><div class="legend-dot" style="background:#fbbf24;"></div> Tài sản cố định</div>
	</div>
	<div class="divider my-2"></div>
	<!-- Asset List -->
	<div class="asset-list">
		<div class="asset-row d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<div class="color-box" style="background:#2563eb;"></div>
				<span class="asset-name">Tiền</span>
			</div>
			<span class="asset-value">'.$clsISO->shortNumber($cash).'</span>
		</div>
		<div class="asset-row d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<div class="color-box" style="background:#60a5fa;"></div>
				<span class="asset-name">Phải thu</span>
			</div>
			<span class="asset-value">'.$clsISO->shortNumber($receivable).'</span>
		</div>
		<div class="asset-row d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<div class="color-box" style="background:#f59e0b;"></div>
				<span class="asset-name">Tạm ứng</span>
			</div>
			<span class="asset-value">'.$clsISO->shortNumber($advance).'</span>
		</div>
		<div class="asset-row d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<div class="color-box" style="background:#22c55e;"></div>
				<span class="asset-name">Đầu tư</span>
			</div>
			<span class="asset-value">'.$clsISO->shortNumber($investment).'</span>
		</div>
		<div class="asset-row d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<div class="color-box" style="background:#fbbf24;"></div>
				<span class="asset-name">Tài sản cố định</span>
			</div>
			<span class="asset-value">'.$clsISO->shortNumber($fixed_asset).'</span>
		</div>
		<div class="asset-row d-flex align-items-center justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<div class="color-box" style="background:#d1d5db;"></div>
				<span class="asset-name">Đầu tư dài hạn</span>
			</div>
			<span class="asset-value">'.$clsISO->shortNumber($long_term_invest).'</span>
		</div>
	</div>';
	 // Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_total_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$smarty->assign('clsMoney', $clsMoney);
	$smarty->assign('clsSetting', $clsSetting);
	
	$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$my = sprintf('t-%s-%s', $clsISO->parseNumber($month), $year);
		$cond = " `t2`.`accounting_date`";
	} else {
		$cond = " FROM_UNIXTIME(`g`.`accounting_date`,'%Y')='{$year}'";
	}
	$arrAccountTHUCTHU = $clsMoney->getAccountByType("THUCTHU");
	$arrAccountTHUCCHI = $clsMoney->getAccountByType("THUCCHI");
	$arrAccountASSET = $clsMoney->getAccountByType("ASSET");
	$arrAccountMKT = $clsMoney->getAccountByType("MARKETING_COST");
	$arrOffice = $clsSetting->getArraySearchByKey("_OFFICE");
	$tmp = $dbconn->getRow("SELECT 
		total_income, 
		total_expense,
		CONCAT(SUM(total_income) - SUM(total_expense)) AS total_profit,
		total_mkt,
		total_branch
	FROM (
		SELECT
			SUM(CASE 
				WHEN `t1`.`account_id` IN (".implode(',',$arrAccountTHUCTHU).") 
				THEN `t1`.`credit_amount` 
				ELSE 0 
			END) AS `total_income`,

			SUM(CASE 
				WHEN `t1`.`account_id` IN (".implode(',',$arrAccountTHUCCHI).") 
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_expense`,
			SUM(CASE 
				WHEN `t1`.`account_id` IN (".implode(',',$arrAccountMKT).") 
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_mkt`,
			SUM(CASE 
				WHEN `t1`.`regional_id` IN (".implode(',',$arrOffice).") 
				THEN `t1`.`debit_amount` 
				ELSE 0 
			END) AS `total_branch`
		FROM {$clsMoneyItem->tbl} AS `t1`
		JOIN {$clsMoney->tbl} AS `t2` ON `t1`.`money_id` = `t2`.`money_id`
		WHERE `t2`.`accounting_date`<='".time()."' 
	) AS `t`");
	$total_income = $core->get_field($tmp, "total_income", 0);
	$total_expense = $core->get_field($tmp, "total_expense", 0);
	$total_profit = $core->get_field($tmp, "total_profit", 0);
	$total_mkt = $core->get_field($tmp, "total_mkt", 0);
	$total_branch = $core->get_field($tmp, "total_branch", 0);
	$smarty->assign("total_income",$total_income);
	$smarty->assign("total_expense",$total_expense);
	$smarty->assign("total_profit",$total_profit);
	$smarty->assign("total_assets",$total_assets);
	$smarty->assign("total_mkt",$total_mkt);
	$smarty->assign("total_branch",$total_branch);
	$html = $core->build("dashboard".DS."_ajax.load_total_expense.tpl");
	echo json_encode(array(
		"html"	=>	$html
	));die;
}
function dashboard_load_report_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	
	$uid = $clsISO->getUniqid();
	#- Tổng cả năm
	/*define('START_YEAR', 2025);
	$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond = " FROM_UNIXTIME(`g`.`accounting_date`,'%m/%Y')='{$my}'";
		
	} else {
		$cond = " FROM_UNIXTIME(`g`.`accounting_date`,'%Y')='{$year}'";
	}*/	
	$current_year = date('Y');
	$current_month = date('m');
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$end_day = ((int)$end_day > date("d")) ? date("d") : $end_day;
	$start_current_year = sprintf('%s-01-01', $current_year);
	$end_current_year = sprintf('%s-12-%s', $current_year, $end_day);
	$start_date = Input::post("start_date",$start_current_year);
	$end_date = Input::post("end_date",$end_current_year);
	$arr_time = $clsMoney->getPreviousDate($start_date,$end_date);
	$start_time = strtotime($arr_time["start_time"]);
	$end_time = strtotime($arr_time["end_time"]);
	$cond = " (`g`.`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	
	###
	$data = $barChartData = $dataPoints = array();
	$arr = array(
		'operation_cost' => array(
			'color' => '#6d78ad',
			'title' => 'Chi phí quản lý doanh nghiệp',
			'tags' => 'ADMIN_EXPENSE',
			'total' => 0
		), 'sale_cost' => array(
			'color' => '#df7970',
			'title' => 'Chi phí bán hàng',
			'tags' => 'SALE_COST',
			'total' => 0
		), 'marketing_cost' => array(
			'color' => '#51cda0',
			'title' => 'Chi phí MKT',
			'tags' => 'MARKETING_COST',
			'total' => 0
		), 'other_cost' => array(
			'color' => '#060673',
			'title' => 'Chi phí khác',
			'tags' => 'OTHER_COST',
			'total' => 0
		)
	);
	$sqlParts = [];
	foreach ($arr as $key => $val) {
		$tags = addslashes($val['tags']); // hoặc bind param nếu có
		$arr_account = $clsMoney->getAccountByType($tags);
		if(!empty($arr_account)) {
			$sqlParts[] = "
				SUM(IF(`e`.`account_id` IN(".implode(',',$arr_account)."), `e`.`debit_amount`, 0)) AS `{$key}`
			";
		}		
	}
	$sql = "SELECT " . implode(",\n", $sqlParts) . " FROM {$clsMoneyItem->tbl} `e`
	JOIN {$clsMoney->tbl} `g` ON `e`.`money_id` = `g`.`money_id`
	JOIN {$clsSetting->tbl} `a` ON `e`.`account_id` = `a`.`setting_id` AND `a`.`_type`='_ACCOUNT'
	WHERE ".$cond;
//	$dbconn->debug=true;
	$tmp = $dbconn->getRow($sql);
//	$clsISO->print_pre($tmp);die;
	if(!empty($tmp)){
		foreach ($tmp as $key => $val) {
			$arr[$key]['total'] = (int)$val ?: 0;
		}
	}
	foreach($arr as $key => $val){
		$dataPoints[] = array(
			'y'	=> $val['total']*1,
			'label'	=> $val['title'],
			'indexLabel' => $clsISO->shortNumber($val['total'])
		);
	}
	$html = '<div gId="{$gId}" class="ajax" style="max-width:250px" >
		<div id="'.$uid.'" class="chartContainer h-px-150"></div>						
	</div>
	<div class="d-flex gap-1 flex-column px-2 flex-fill">';
	foreach($arr as $key => $val){
		$html.= '<div class="d-flex align-items-start justify-content-between gap-2">
			<div class="d-flex align-items-start gap-2">
				<span class="w-px-15 h-px-15 rounded-pill mt-1" style="background:'.$val['color'].'"></span>
				<span class="">'.$val['title'].'</span>
			</div>
			<span class="text-fs-15 fw-bold text-nowrap text-right">'.$clsISO->shortNumber($val['total']).'</span>
		</div>';
	}
	$html.= '</div>';
	#
	$data['type'] = 'doughnut';
	$data['indexLabel'] = '{label} - {y}';
	$data['yValueFormatString'] = '#,##0.0"%"';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	$barChartData['legendText'] = "{label}: {y}%";
	$barChartData["legend"] = [
		"verticalAlign" => "center",
        "horizontalAlign" => "right"
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
function dashboard_load_report_income(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	#
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	/*if($month > 0){
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s', $year, $month, $end_day));
		$sql_cur_query = "`g`.`accounting_date`<='{$end_date}'";
		#
		$pre_month = ($month == 1) ? 12 : ($month - 1);
		$pre_year = ($month == 1) ? ($year - 1) : $year;
		$end_pre_day = cal_days_in_month(CAL_GREGORIAN, $pre_month, $pre_year);
		$end_pre_date = strtotime(sprintf('%s-%s-%s', $pre_year, $pre_month, $end_pre_day));
		$sql_pre_query = "`g`.`accounting_date`<='{$end_pre_date}'";
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
		$end_date = strtotime(sprintf('%s-%s-%s', $year, 12, $end_day));
		$sql_cur_query = "`g`.`accounting_date`<='{$end_date}'";
		#
		$pre_year = ($year-1);
		$end_pre_day = cal_days_in_month(CAL_GREGORIAN, date('n'), $pre_year);
		$end_pre_date = strtotime(sprintf('%s-%s-%s', $pre_year, date('n'), $end_pre_day));
		$sql_pre_query = "`g`.`accounting_date`<='{$end_pre_date}'";
	}*/
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
	$sql_cur_query = " (`g`.`accounting_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	$sql_pre_query = " (`g`.`accounting_date` BETWEEN '{$start_time_prev}' AND '{$end_time_prev}')";
	
	
	$arr = array(
		'actual_receipt' => array(
			'cls'	=> 'green',
			'icon' => '<svg width="24" height="20" viewBox="0 0 34 30" fill="none">
				<rect x="1" y="8" width="32" height="20" rx="5" fill="#bbf7d0" stroke="#22c55e" stroke-width="1.8"/>
				<path d="M6 8V6A5 5 0 0 1 11 1h12a5 5 0 0 1 5 5v2" stroke="#22c55e" stroke-width="1.8" fill="none" stroke-linecap="round"/>
				<circle cx="27" cy="18" r="3" fill="#16a34a"/>
				<line x1="7" y1="16" x2="18" y2="16" stroke="#16a34a" stroke-width="2" stroke-linecap="round"/>
				<line x1="7" y1="21" x2="13" y2="21" stroke="#16a34a" stroke-width="2" stroke-linecap="round" opacity=".55"/>
			</svg>',
			'field' => 'debit_amount',
			'title' => 'Thực thu',
			'tags'  => 'PHAITHU',
			'total' => 0,
			'total_pre' => 0
		), 'expected_revenue' => array(
			'cls'	=> 'yellow',
			'icon' => '<svg width="24" height="20" viewBox="0 0 34 30" fill="none">
				<rect x="2"  y="20" width="7" height="9" rx="2" fill="#fcd34d"/>
				<rect x="13" y="12" width="7" height="17" rx="2" fill="#f59e0b"/>
				<rect x="24" y="6"  width="7" height="23" rx="2" fill="#d97706"/>
			</svg>',
			'field' => 'debit_amount',
			'title' => 'Dự kiến thu',
			'tags'  => 'DUKIENTHU',
			'total' => 0,
			'total_pre' => 0
		), 'collected_on_behalf' => array(
			'cls'	=> 'blue',
			'icon' => '<svg width="26" height="20" viewBox="0 0 36 30" fill="none">
				<!-- left arm -->
				<path d="M1 20 C3 16, 8 13, 13 15 L18 19" stroke="#60a5fa" stroke-width="2.2" stroke-linecap="round" fill="none"/>
				<!-- left fingers -->
				<path d="M13 15 L16 10 C17 8, 19.5 9, 18.5 11.5 L16 15" fill="#bfdbfe" stroke="#60a5fa" stroke-width="1.4"/>
				<path d="M16 10 L18 7 C19 5, 21.5 6, 20.5 8.5 L18.5 11.5" fill="#dbeafe" stroke="#93c5fd" stroke-width="1.4"/>
				<!-- right arm -->
				<path d="M35 20 C33 16, 28 13, 23 15 L18 19" stroke="#3b82f6" stroke-width="2.2" stroke-linecap="round" fill="none"/>
				<!-- right fingers -->
				<path d="M23 15 L20 10 C19 8, 16.5 9, 17.5 11.5 L20 15" fill="#93c5fd" stroke="#3b82f6" stroke-width="1.4"/>
				<path d="M20 10 L18 7 C17 5, 14.5 6, 15.5 8.5 L17.5 11.5" fill="#bfdbfe" stroke="#60a5fa" stroke-width="1.4"/>
				<!-- clasp centre -->
				<ellipse cx="18" cy="19.5" rx="5" ry="4" fill="#dbeafe" stroke="#3b82f6" stroke-width="1.6"/>
				<!-- cuffs -->
				<path d="M1 20 C2 24, 7 27, 12 27 L18 24" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" fill="none"/>
				<path d="M35 20 C34 24, 29 27, 24 27 L18 24" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" fill="none"/>
			</svg>',
			'field' => 'credit_amount',
			'title' => 'Thu hộ',
			'tags' => 'THUHO',
			'total' => 0,
			'total_pre' => 0
		)
	);
	$sqlParts = [];
	foreach ($arr as $key => $val) {
		$tags = addslashes($val['tags']);
		$arr_account = $clsMoney->getAccountByType($tags);
		if(!empty($arr_account)) {
			$sqlParts[] = "
				SUM(IF(`e`.`account_id` IN(".implode(',',$arr_account)."), `e`.`".$val['field']."`, 0)) AS `{$key}`
			";
		}		
	}
	$sql = "SELECT " . implode(",\n", $sqlParts) . " FROM {$clsMoneyItem->tbl} `e`
		JOIN {$clsMoney->tbl} `g` ON `e`.`money_id` = `g`.`money_id`
		JOIN {$clsSetting->tbl} `a` ON `e`.`account_id` = `a`.`setting_id` AND `a`.`_type`='_ACCOUNT'
		WHERE {$sql_cur_query}";
	$total = 0;
	$tmp = $dbconn->getRow($sql);
	if(!empty($tmp)){
		foreach ($tmp as $key => $val) {
			$arr[$key]['total'] = $val ?: 0;
			$total += $val ?: 0;
		}
	}
	// Prev
	$sql = "SELECT " . implode(",\n", $sqlParts) . " FROM {$clsMoneyItem->tbl} `e`
		JOIN {$clsMoney->tbl} `g` ON `e`.`money_id` = `g`.`money_id`
		JOIN {$clsSetting->tbl} `a` ON `e`.`account_id` = `a`.`setting_id` AND `a`.`_type`='_ACCOUNT'
		WHERE {$sql_pre_query}";
	$tmp = $dbconn->getRow($sql);
	if(!empty($tmp)){
		foreach ($tmp as $key => $val) {
			$arr[$key]['total_pre'] = $val ?: 0;
		}
	}
	$html.= '<div class="metrics-grid gap-2">';
	foreach($arr as $key => $val){
		$total_in = $val['total'];
		$total_pre = $val['total_pre'];
		$arrow = ($total_in >= $total_pre) ? 'up' : 'down';
		$percent = (($total_in - $total_pre) / $total_pre) * 100;
		$html.= '<div class="metric-box '.$val['cls'].'">
			<div class="box-header">
				<div class="box-icon">'.$val['icon'].'</div>
				<span class="box-label">'.$val['title'].'</span>
			</div>
			<div class="box-value text-fs-20">'.$clsISO->shortNumber($total_in, 3).'</div>
			<div class="box-delta '.($percent >= 0 ? 'green' : 'red').'">
				<span class="arrow-'.$arrow.'"></span> '.(($percent >= 0 ? '+' : '') . round($percent, 2) . '%').'
			</div>
			<!-- <div class="box-sub">
				<span class="sub-bold green">+280 triệu</span> so với kỳ trước
			</div> -->
		</div>';
	}
	$html.= '</div>
	<!-- Summary row -->
	<div class="summary-row">
		<div class="summary-item gap-2">
			<svg width="22" height="20" viewBox="0 0 30 26" fill="none" style="flex-shrink:0">
				<rect x="0"  y="15" width="8" height="11" rx="2" fill="#fbbf24"/>
				<rect x="11" y="7"  width="8" height="19" rx="2" fill="#22c55e"/>
				<rect x="22" y="11" width="7" height="15" rx="2" fill="#4ade80" opacity=".7"/>
			</svg>
			<span class="summary-label">Tổng  tiền vào:</span>
			<span class="summary-value">32,9 tỷ</span>
		</div>
		<div class="summary-divider"></div>
		<div class="summary-item gap-2">
			<svg width="20" height="20" viewBox="0 0 26 26" fill="none" style="flex-shrink:0">
				<path d="M13 2 C8.5 2, 5 5.8, 5 10.5 C5 13.8, 6.8 16.7, 9.5 18.2 L9.5 20.5 C9.5 21.3, 10.2 22, 11 22 L15 22 C15.8 22, 16.5 21.3, 16.5 20.5 L16.5 18.2 C19.2 16.7, 21 13.8, 21 10.5 C21 5.8, 17.5 2, 13 2Z" fill="#fde68a" stroke="#f59e0b" stroke-width="1.4"/>
				<line x1="10.5" y1="22" x2="15.5" y2="22" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round"/>
				<line x1="11" y1="24" x2="15" y2="24" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round"/>
				<path d="M10 17.5 C10 15.5, 12 14, 13 12 C14 14, 16 15.5, 16 17.5" stroke="#f59e0b" stroke-width="1.2" fill="none" stroke-linecap="round"/>
			</svg>
			<span class="summary-label">Doanh thu thực:</span>
			<span class="summary-value">14,0 tỷ</span>
		</div>
	</div>
	<!-- Bottom bar -->
	<div class="bottom-bar">
		<svg width="26" height="24" viewBox="0 0 26 24" fill="none" style="flex-shrink:0">
			<rect x="0"  y="14" width="7" height="10" rx="2" fill="#34d399"/>
			<rect x="9"  y="6"  width="7" height="18" rx="2" fill="#0d9488"/>
			<rect x="19" y="9"  width="7" height="15" rx="2" fill="#2dd4bf" opacity=".75"/>
		</svg>
		<span class="bottom-label">Doanh thu</span>
		<span class="bottom-value text-fs-20">'.$clsISO->shortNumber($total).'</span>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_report_cash(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsMoneyAccount = new MoneyAccount();
	
	$year = (int) Input::post('year', date('Y'));
	$month = (int) Input::post('month', 0);
	if($month > 0){
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s', $year, $month, $end_day));
		$sql_cond = "`t2`.`accounting_date`<='{$end_date}'";
	} else {
		$sql_cond = "FROM_UNIXTIME(`t2`.`accounting_date`,'%Y') <= '{$year}'";
	}
	$total_cash = $total_bank_balance = 0;
	$sql_query = "SELECT
		SUM(CASE WHEN `x`.`group_code` = 'TIENMAT' THEN amount ELSE 0 END) AS `total_cash`,
		SUM(CASE WHEN `x`.`group_code` = 'BANK_BALANCE' THEN amount ELSE 0 END) AS `total_bank_balance`
	FROM (
		SELECT 
			`t1`.`account_id`,
			`t4`.`group_code`,
			CASE 
				WHEN `t4`.`account_type` = 'credit' 
					THEN `t1`.`credit_amount` - `t1`.`debit_amount`
				WHEN `t4`.`account_type` = 'debit' 
					THEN `t1`.`debit_amount` - `t1`.`credit_amount`
				WHEN `t4`.`account_type` = 'both' 
					THEN `t1`.`debit_amount` - `t1`.`credit_amount`
				ELSE 0
			END AS `amount`
		FROM {$clsMoneyItem->tbl} t1
		JOIN {$clsMoney->tbl} t2 ON `t1`.`money_id` = `t2`.`money_id`
		JOIN {$clsMoneyAccount->tbl} t4 ON t1.`account_id` = t4.account_id
		WHERE {$sql_cond}
	) AS `x`";
	// $dbconn->debug = true;
	$tmp = $dbconn->getRow($sql_query);
	if(!empty($tmp)){
		$total_cash = $tmp['total_cash'] ?? 0;
		$total_bank_balance = $tmp['total_bank_balance'] ?? 0;
	}
	$total = $total_cash + $total_bank_balance;
	$dataCached = $clsSetting->getCacheItems("_ACCOUNT");
	$list_cash_items = $clsMoney->getChild(_MONEY_ACCOUNT_CASH_ID, $dataCached);
	$list_banks_items = $clsMoney->getChild(_MONEY_ACCOUNT_BANK_BALANCE_ID, $dataCached);
	$html = '<div class="summary-bar">
		<div class="summary-total">Tổng: <strong>'.$clsISO->shortNumber($total,3).'</strong></div>
		<div class="summary-pill">
			<span class="label">Tiền mặt</span>
			<span class="value">'.$clsISO->shortNumber($total_cash,3).'</span>
		</div>
		<div class="summary-pill">
			<span class="label">Ngân hàng</span>
			<span class="value">'.$clsISO->shortNumber($total_bank_balance,3).'</span>
		</div>
	</div>
	<div class="x-grid">
		<div class="x-col">
			<div class="section-header">
				<div class="section-title">
					<span>💰</span> Tiền mặt
					<span class="section-code">111</span>
				</div>
			</div>
			<div class="item-list">';
			if(!empty($list_cash_items)){
				foreach($list_cash_items as $key => $val){
					$total = 0;
					if(!empty($val['children'])){
						foreach($val['children'] as $okey => $oval){
							$total_money = 0;
							$sql_query = "SELECT SUM(`e`.`debit_amount` - `e`.`credit_amount`) AS `total_money` 
								FROM {$clsMoneyItem->tbl} as `e` 
								JOIN {$clsMoney->tbl} as `t2` ON `e`.`money_id`=`t2`.`money_id`
								WHERE `e`.`account_id`='{$oval[$clsSetting->pkey]}' AND {$sql_cond}";
							$tmp = $dbconn->getRow($sql_query);
							if(!empty($tmp)){
								$total_money = $tmp['total_money'] ?? 0;
							}
							$val['children'][$okey]['total_money'] = $total_money;
							$total += $total_money;
						}
					}
					// $clsISO->print_pre($list_cash_items); die();
					
					$html.= '<div class="item">
						<div class="item-left">
							<span class="dot dot-green"></span>
							<span class="item-name">'.$val['title'].'</span>
						</div>
						<span class="item-value">'.$clsISO->shortNumber($total,3).'</span>
					</div>';
					if(!empty($val['children'])){
						foreach($val['children'] as $okey => $oval){
							$html.= '<div class="item">
								<div class="item-left">
									<span class="dot dot-green"></span>
									<span class="item-name"><i class=\'bx bx-subdirectory-right\'></i>'.$oval['title'].'</span>
								</div>
								<span class="item-value">'.$clsISO->shortNumber($oval['total_money'],3).'</span>
							</div>';
						}
					}
				}
			}
			$html.= '</div>
			<!-- Bottom divider: Ngân hàng summary -->
			<!-- <div class="divider-row">
				<div class="section-title">
				  <span>🏦</span> Ngân hàng
				  <span class="section-code">112</span>
				</div>
			</div> -->
		</div>
		<div class="x-col">
			<div class="section-header">
				<div class="section-title">
					<span>🏦</span> Ngân hàng
					<span class="section-code">112</span>
				</div>
				<div class="section-amount">
					'.$clsISO->shortNumber($total_bank_balance,3).'
					<a class="arrow-link">→</a>
				</div>
			</div>
			<div class="item-list">'; $ii = 0;
			if(!empty($list_banks_items)){
				foreach($list_banks_items as $key => $val){
					$total = 0;
					if(!empty($val['children'])){
						foreach($val['children'] as $okey => $oval){
							$total_money = 0;
							$sql_query = "SELECT SUM(`e`.`debit_amount` - `e`.`credit_amount`) AS `total_money` 
								FROM {$clsMoneyItem->tbl} AS `e` 
								JOIN {$clsMoney->tbl} AS `t2` ON `e`.`money_id`=`t2`.`money_id`
								WHERE `e`.`account_id`='{$oval[$clsSetting->pkey]}' AND {$sql_cond}";
							$tmp = $dbconn->getRow($sql_query);
							if(!empty($tmp)){
								$total_money = $tmp['total_money'] ?? 0;
							}
							$val['children'][$okey]['total_money'] = $total_money;
							$total += $total_money;
						}
					}
					$html.= '<div class="item'.($ii >= 6 ? ' d-none' : '').'">
						<div class="item-left">
							<span class="dot dot-green"></span>
							<span class="item-name">'.$val['title'].'</span>
						</div>
						<span class="item-value">'.$clsISO->shortNumber($total,3).'</span>
					</div>';
					$ii++;
					if(!empty($val['children'])){
						foreach($val['children'] as $okey => $oval){
							$html.= '<div class="item'.($ii >= 5 ? ' d-none toggleItem' : '').'">
								<div class="item-left">
									<span class="dot dot-green"></span>
									<span class="item-name"><i class=\'bx bx-subdirectory-right\'></i>'.$oval['title'].'</span>
								</div>
								<span class="item-value">'.$clsISO->shortNumber($oval['total_money'],3).'</span>
							</div>';
							$ii++;
						}
						$html.= '<div class="d-flex align-items-center justify-content-start">
							<a onClick="$Core.util.toggle_tr(this, event)" toCls="toggleItem" 
								class="btn btn-sm btn-link">Xem thêm <i class=\'bx bx-chevron-down\'></i></a>
						</div>';
					}
					$ii++;
				}
			}
			$html.= '</div>
			<!-- <div class="collapsed-section">
				<div class="section-title">
					<span>📊</span> Đầu tư
					<span class="section-code">121, 128</span>
				</div>
				<div class="chevron">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
					stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<polyline points="6 9 12 15 18 9"/>
					</svg>
				</div>
			</div> -->
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_cash_book_summary(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsCashBook = new CashBook();
	$clsConfiguration = new Configuration();
	// Ngày tháng
	$year = (int) Input::post('year', date('Y'));
	$start_date = strtotime(sprintf('01-01-%s 00:00:00', $year));
	$end_day_of_end_month = cal_days_in_month(CAL_GREGORIAN, 12, $year);
	$due_date = strtotime(sprintf('%s-12-%s 23:59:59', $end_day_of_end_month, $year));  
	// Công ty
	$company_id = (int) Input::post('company_id', _GROUP_COMPANY_FH_ID);
	
	$cond = "`company_id`='{$company_id}' AND `date` BETWEEN {$start_date} AND {$due_date}";
	$total_opening_balance = $total_receipt_amount = $total_payment_amount = $total_closing_balance = 0;
	$query = "SELECT
		SUM(CASE WHEN `company_id`='{$company_id}' AND `date` < '{$start_date}' AND `type`='THUCTHU' THEN amount
			WHEN `company_id`='{$company_id}' AND `date` < '{$start_date}' AND `type`='THUCCHI' THEN -amount
			ELSE 0 END) AS total_opening_balance,
		SUM(
			CASE WHEN `company_id`='{$company_id}' AND `date` BETWEEN '{$start_date}' AND '{$due_date}' AND `type` = 'THUCTHU'
				THEN amount ELSE 0
			END
		) AS `total_receipt_amount`,
		
		SUM(
			CASE WHEN `company_id`='{$company_id}' AND `date` BETWEEN '{$start_date}' AND '{$due_date}' AND type = 'THUCCHI'
				THEN amount ELSE 0
			END
		) AS total_payment_amount,
		
		SUM(CASE WHEN `company_id`='{$company_id}' AND `date` <= '{$due_date}' AND `type`='THUCTHU' THEN amount 
			WHEN `company_id`='{$company_id}' AND `date` <= '{$due_date}' AND `type`='THUCCHI' THEN -amount
			ELSE 0 END
		) AS `total_closing_balance`
		FROM {$clsCashBook->tbl}";
	
	$tmp = $dbconn->GetRow($query);
	if(!empty($tmp)){
		$total_opening_balance = $tmp['total_opening_balance'];
		$total_receipt_amount = $tmp['total_receipt_amount'];
		$total_payment_amount = $tmp['total_payment_amount'];
		$total_closing_balance = $tmp['total_closing_balance'];
	}
	$cash_book_configs = $clsConfiguration->getValue('cash_book_configs');
	$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
	$one_configs = $core->get_field($cash_book_configs, $company_id, []);
	$OPENING_BALANCE_DEF = $core->get_money_field($one_configs, "opening_balance", 0);
	// $clsISO->print_pre($OPENING_BALANCE_DEF); die();
	$total_opening_balance += $OPENING_BALANCE_DEF;
	$total_closing_balance += $OPENING_BALANCE_DEF;
	
	$html= '<div class="report-block">
		<p class="report-label">Báo cáo tồn quỹ tiền mặt '.$year.'</p>
		<div class="report-grid">
			<div class="report-item">
				<p class="report-item-label">Tồn đầu kỳ</p>
				<p class="report-item-value">'.$clsISO->formatPrice($total_opening_balance).' đ</p>
			</div>
			<div class="report-item">
				<p class="report-item-label">Thu trong kỳ</p>
				<p class="report-item-value">'.$clsISO->formatPrice($total_receipt_amount).' đ</p>
			</div>
			<div class="report-item">
				<p class="report-item-label">Chi trong kỳ</p>
				<p class="report-item-value">'.$clsISO->formatPrice($total_payment_amount).' đ</p>
			</div>
			<div class="report-item highlight">
				<p class="report-item-label">Tồn cuối kỳ</p>
				<p class="report-item-value">'.$clsISO->formatPrice($total_closing_balance).' đ</p>
			</div>
		</div>
	</div>
	<div class="room-grid">
		<div class="room-item tgd">
			<p class="room-item-label">Tồn tiền Kết 1: Phòng TGĐ</p>
			<p class="room-item-value">0 đ</p>
		</div>
		<div class="room-item ketoan">
			<p class="room-item-label">Tồn tiền Kết 2: Phòng Kế toán</p>
			<p class="room-item-value">'.$clsISO->formatPrice($total_closing_balance).' đ</p>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_date_range(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$current_month = date("n");
	$current_year = date("Y");
	$month = Input::post('month', "");
	$year = Input::post('year', "");
	$year = ($year != "") ? $year : $current_year;
	if($month > 0){
		$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
		$start_date = date(sprintf('01-%s-%s', $clsISO->parseNumber($month), $year));
		$start_date = strtotime($start_date);
		$end_date =  date(sprintf('t-%s-%s 23:59:59', $clsISO->parseNumber($month), $year));
		$end_date = strtotime($end_date);
	} else {
		$date = sprintf('01-01-%s', $year);
		$start_date = strtotime($date);
		$last_time = date(sprintf('31-12-%s 23:59:59', $year));
		$end_date = strtotime($last_time);
	}
	// Return
	echo json_encode(array(
		'start_date' => date('Y-m-d', $start_date),
		'end_date' => date('Y-m-d', $end_date),
	)); die();
}

function dashboard_load_time_range(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$date_type = Input::post('date_type');
	if($date_type == "") {		
		$month = (int) Input::post("month", 0);
		$year = (int) Input::post("year", date('Y'));
		if($month > 0){
			$my = sprintf('%s-%s', $clsISO->parseNumber($month), $year);
			$start_date = date(sprintf('01-%s-%s', $clsISO->parseNumber($month), $year));
			$start_date = strtotime($start_date);
			$end_date =  date(sprintf('t-%s-%s 23:59:59', $clsISO->parseNumber($month), $year));
			$end_date = strtotime($end_date);
		} else {
			$date = sprintf('01-01-%s', $year);
			$start_date = strtotime($date);
			$last_time = date(sprintf('31-12-%s 23:59:59', $year));
			$end_date = strtotime($last_time);
		}
	} else if($date_type == 'today'){
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
function dashboard_load_detail_income_statement(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsMoneyAccount = new MoneyAccount();
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
	$arrAccount_REVENUE = $clsMoney->getAccountByType("REVENUE");
	$arrAccount_SALES_REVENUE = $clsMoney->getAccountByType("SALES_REVENUE");
	$arrAccount_COST = $clsMoney->getAccountByType("COST");
	$arrAccount_FINANCIAL_INCOME = $clsMoney->getAccountByType("FINANCIAL_INCOME");
	$arrAccount_FINANCE_COST = $clsMoney->getAccountByType("FINANCE_COST");
	$arrAccount_EXPENSE = $clsMoney->getAccountByType("EXPENSE");
	$arrAccount_OTHER_INCOME = $clsMoney->getAccountByType("OTHER_INCOME");
	$arrAccount_OTHER_EXPENSE = $clsMoney->getAccountByType("OTHER_EXPENSE");
	$arrAccount_TAX = $clsMoney->getAccountByType("TAX");
	$arr_account_parent_cached = $clsISO->buildTree($arr_account_cached,0,$clsSetting->pkey);
	$tmp = $dbconn->getALl("SELECT 
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_REVENUE).") THEN `b`.`total_credit` ELSE 0 END) AS `total_revenue`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_SALES_REVENUE).") THEN `b`.`total_debit` ELSE 0 END) AS `total_sales_revenue`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_COST).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_cost`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_FINANCIAL_INCOME).") THEN `b`.`total_credit` - `b`.`total_debit` ELSE 0 END) AS `total_financial_income`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_FINANCE_COST).") THEN `b`.`total_credit` - `b`.`total_debit` ELSE 0 END) AS `total_finance_cost`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_COST).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_cost`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_EXPENSE).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_expense`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_OTHER_INCOME).") THEN `b`.`total_credit` - `b`.`total_debit` ELSE 0 END) AS `total_other_expense`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_OTHER_EXPENSE).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_income_expense`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_TAX).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_tax`
	FROM (
		SELECT 
			`mi`.`account_id`,
			SUM(`mi`.debit_amount) AS `total_debit`,
			SUM(`mi`.credit_amount) AS `total_credit`
		FROM `{$clsMoneyItem->tbl}` AS `mi`
		JOIN `{$clsMoney->tbl}` AS `m` ON `m`.money_id = `mi`.money_id
		WHERE m.accounting_date BETWEEN '{$start_time}' AND '{$end_time}'
		GROUP BY `mi`.`account_id`
	) AS `b`");
	$tmp_prev = $dbconn->getALl("SELECT 
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_REVENUE).") THEN `b`.`total_credit` ELSE 0 END) AS `total_revenue`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_SALES_REVENUE).") THEN `b`.`total_debit` ELSE 0 END) AS `total_sales_revenue`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_COST).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_cost`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_FINANCIAL_INCOME).") THEN `b`.`total_credit` - `b`.`total_debit` ELSE 0 END) AS `total_financial_income`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_FINANCE_COST).") THEN `b`.`total_credit` - `b`.`total_debit` ELSE 0 END) AS `total_finance_cost`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_COST).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_cost`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_EXPENSE).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_expense`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_OTHER_INCOME).") THEN `b`.`total_credit` - `b`.`total_debit` ELSE 0 END) AS `total_other_expense`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_OTHER_EXPENSE).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_income_expense`,
        SUM(CASE WHEN `b`.`account_id` IN (".implode(',',$arrAccount_TAX).") THEN `b`.`total_debit` - `b`.`total_credit` ELSE 0 END) AS `total_tax`
	FROM (
		SELECT 
			`mi`.`account_id`,
			SUM(`mi`.debit_amount) AS `total_debit`,
			SUM(`mi`.credit_amount) AS `total_credit`
		FROM `{$clsMoneyItem->tbl}` AS `mi`
		JOIN `{$clsMoney->tbl}` AS `m` ON `m`.money_id = `mi`.money_id
		WHERE m.accounting_date BETWEEN '{$start_time_prev}' AND '{$end_time_prev}'
		GROUP BY `mi`.`account_id`
	) AS `b`");
	$total_revenue = $total_net_revenue = $total_sales_revenue = $total_cost = $total_financial_income = $total_financial_cost = $total_expense = $total_other_expense = $total_income_expense = $total_tax = 0;
	$total_revenue_prev = $total_net_revenue_prev = $total_sales_revenue_prev = $total_cost_prev = $total_financial_income_prev = $total_financial_cost_prev = $total_expense_prev = $total_other_expense_prev = $total_income_expense_prev = $total_tax_prev = 0;
	foreach ($tmp as $key => $val) {
		$total_revenue += $val["total_revenue"];
		$total_sales_revenue += $val["total_sales_revenue"];
		$total_cost += $val["total_cost"];
		$total_financial_income += $val["total_financial_income"];
		$total_finance_cost += $val["total_finance_cost"];
		$total_expense += $val["total_expense"];
		$total_other_expense += $val["total_other_expense"];
		$total_income_expense += $val["total_income_expense"];
		$total_tax += $val["total_tax"];
	}
	foreach ($tmp_prev as $key => $val) {
		$total_revenue_prev += $val["total_revenue"];
		$total_sales_revenue_prev += $val["total_sales_revenue"];
		$total_cost_prev += $val["total_cost"];
		$total_financial_income_prev += $val["total_financial_income"];
		$total_finance_cost_prev += $val["total_finance_cost"];
		$total_expense_prev += $val["total_expense"];
		$total_other_expense_prev += $val["total_other_expense"];
		$total_income_expense_prev += $val["total_income_expense"];
		$total_tax_prev += $val["total_tax"];
	}
	$total_net_revenue = $total_revenue - $total_sales_revenue;
	$gross_profit = $total_net_revenue - $total_cost;
	$operating_profit = $gross_profit + $total_financial_income - $total_expense - $total_other_expense;
	$other_revenue = $total_income_expense - $total_other_expense;
	$total_before_tax = $operating_profit + $other_revenue;
	$total_after_tax = $total_before_tax - $total_tax;
	#
	$total_net_revenue_prev = $total_revenue_prev - $total_sales_revenue_prev;
	$gross_profit_prev = $total_net_revenue_prev - $total_cost_prev;
	$operating_profit_prev = $gross_profit_prev + $total_financial_income_prev - $total_expense_prev - $total_other_expense_prev;
	$other_revenue_prev = $total_income_expense_prev - $total_other_expense_prev;
	$total_before_tax_prev = $operating_profit_prev + $other_revenue_prev;
	$total_after_tax_prev = $total_before_tax_prev - $total_tax_prev;
	$lstReport = [
		[
			"stt"	=>	1,
			"title"	=>	"Doanh thu bán hàng và cung cấp dịch vụ",
			"code"	=>	"01",
			"total"	=>	$total_revenue,
			"total_prev"	=>	$total_revenue_prev,
		],
		[
			"stt"	=>	2,
			"title"	=>	"Các khoản giảm trừ doanh thu",
			"code"	=>	"02",
			"total"	=>	$total_sales_revenue,
			"total_prev"	=>	$total_sales_revenue_prev,
		],
		[
			"stt"	=>	3,
			"title"	=>	"Doanh thu thuần về bán hàng và cung cấp dịch vụ (10 = 01 - 02)",
			"code"	=>	"10",
			"total"	=>	$total_net_revenue,
			"total_prev"	=>	$total_net_revenue_prev,
		],
		[
			"stt"	=>	4,
			"title"	=>	"Giá vốn hàng bán",
			"code"	=>	"11",
			"total"	=>	$total_cost,
			"total_prev"	=>	$total_cost_prev,
		],
		[
			"stt"	=>	5,
			"title"	=>	"Lợi nhuận gộp về bán hàng và cung cấp dịch vụ (20 = 10 - 11)",
			"code"	=>	"20",
			"total"	=>	$gross_profit,
			"total_prev"	=>	$gross_profit_prev,
		],
		[
			"stt"	=>	6,
			"title"	=>	"Doanh thu hoạt động tài chính",
			"code"	=>	"21",
			"total"	=>	$total_financial_income,
			"total_prev"	=>	$total_financial_income_prev,
		],
		[
			"stt"	=>	7,
			"title"	=>	"Chi phí tài chính",
			"code"	=>	"22",
			"total"	=>	$total_financial_cost,
			"total_prev"	=>	$total_financial_cost_prev,
		],
		[
			"stt"	=>	8,
			"title"	=>	"Chi phí quản lý kinh doanh",
			"code"	=>	"24",
			"total"	=>	$total_expense,
			"total_prev"	=>	$total_expense_prev,
		],
		[
			"stt"	=>	9,
			"title"	=>	"Lợi nhuận thuần từ hoạt động kinh doanh (30 = 20 + 21 - 22 - 24)",
			"code"	=>	"30",
			"total"	=>	$operating_profit,
			"total_prev"	=>	$operating_profit_prev,
		],
		[
			"stt"	=>	10,
			"title"	=>	"Thu nhập khác",
			"code"	=>	"31",
			"total"	=>	$total_income_expense,
			"total_prev"	=>	$total_income_expense_prev,
		],
		[
			"stt"	=>	11,
			"title"	=>	"Chi phí khác",
			"code"	=>	"32",
			"total"	=>	$total_other_expense,
			"total_prev"	=>	$total_other_expense_prev,
		],
		[
			"stt"	=>	12,
			"title"	=>	"Lợi nhuận khác (40 = 31 - 32)",
			"code"	=>	"40",
			"total"	=>	$other_revenue,
			"total_prev"	=>	$other_revenue_prev,
		],
		[
			"stt"	=>	13,
			"title"	=>	"Tổng lợi nhuận kế toán trước thuế (50 = 30 + 40)",
			"code"	=>	"50",
			"total"	=>	$total_before_tax,
			"total_prev"	=>	$total_before_tax_prev,
		],
		[
			"stt"	=>	14,
			"title"	=>	"Chi phí thuế TNDN",
			"code"	=>	"51",
			"total"	=>	$total_tax,
			"total_prev"	=>	$total_tax_prev,
		],
		[
			"stt"	=>	15,
			"title"	=>	"Lợi nhuận sau thuế thu nhập doanh nghiệp (60 = 50 - 51)",
			"code"	=>	"60",
			"total"	=>	$total_after_tax,
			"total_prev"	=>	$total_after_tax_prev,
		],
	];
//	$clsISO->print_pre($lstReport);die;
	$smarty->assign("lstReport",$lstReport);		
	$smarty->assign("uid",$uid);
	$html = $core->build($sub.DS."_ajax.load_detail_income_statement.tpl");
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
//		'barChartData' => $barChartData,
//		'callback' => $callback
	)); die();
}
