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
function performance_performance(){
	//	ini_set('display_errors', '1');
	//ini_set('display_startup_errors', '1');
	//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$deviceType;
	$clsStock = new Stock();
	$clsProperty = new Property();
	##
	$arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_building = $clsProperty->getArraySearchByKey("_BUILDING");
	$today = date("Y-m-d"); $smarty->assign('today', $today);
	if($deviceType == 'phone') {
		$time_handover = date("Y/m/d",strtotime("+ 1 months"));
	}else{
		$time_handover = date("d/m/Y",strtotime("+ 1 months"));
	}
	$smarty->assign('time_handover', $time_handover);
	$time_profit = date("Y-m-d",strtotime("+ 23 months")); $smarty->assign('time_profit', $time_profit);
	$time_leasing = date("Y-m-d",strtotime("+ 2 months")); $smarty->assign('time_leasing', $time_leasing);
	$time_AHNG = date("Y-m-d",strtotime("+ 11 months")); $smarty->assign('time_AHNG', $time_AHNG);
	
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$field = "`ms_code`,`block_id`,`stock_type`,`more_information`,`building_id`";
	$oneStock = $clsStock->getOne($stock_id, $field);
	$more_information = $clsISO->to_array_json($oneStock["more_information"]);
	
	$total_price_vat = $more_information['total_price_vat'];
//	$total_price_vat = 3060000000;
	$oneStock['total_price_vat'] = $total_price_vat;
	$oneStock['text_stock'] = sprintf('%s Toà %s %s - %s', $arr_bedroom[$more_information['bedroom_id']]["title"],$arr_building[$oneStock['building_id']]["title_vn"],$arr_block[$oneStock['block_id']]["title_vn"],$oneStock["ms_code"]);
	
	$capital = (int)$total_price_vat*0.2;
	$loan = (int)$total_price_vat*0.8;
	$oneStock['capital'] = $capital;
	$oneStock['loan'] = $loan;
	
	$smarty->assign('oneStock', $oneStock);
	$smarty->assign('more_information', $more_information);
//	var_dump($oneStock);die;
	###
	$investment_performance_revenue = $clsConfiguration->getValue('investment_performance_revenue');
	$lst_revenue = $clsISO->to_array_json($investment_performance_revenue);
	
	$total_price_revenue = 0;
	
	$price_loan = $total_price_vat * 10 * 24 / (100 * 12);
	$price_loan = round($price_loan);
	$smarty->assign('price_loan', $price_loan);
	
	$price_leasing = 8000000 * 11; $smarty->assign('price_leasing', $price_leasing);
	$total_price_revenue += $price_leasing;
	
	$price_risk = 0;
	$price_risk += $price_loan;
	
	foreach ($lst_revenue as $key => $value) {
		$value_risk = $value['value_risk'];
		$unit_risk = $value['unit_risk'];
		$lstChild = $value['lstChild'];
		$price_child = 0;
		foreach ($lstChild as $k_child_1 => $v_child_1) {
			if($v_child_1['unit_type'] == "_PERCENT") {
				$price = $total_price_vat * $v_child_1['value'] / 100;	
				if(!empty($v_child_1['time'])) {
					$price = $price * $v_child_1['time'] / 12;
				}
			}else{
				$price = $v_child_1['value'];	
				if(!empty($v_child_1['time'])) {
					$price = $price * $v_child_1['time'];
				}
			}
			$price = round($price);
			
			$price_child += $price;
			$lstChild[$k_child_1]['price'] = $price;
			$price_risk += $price;
			unset($price);
		}
		$lst_revenue[$key]['lstChild'] = $lstChild;
	}
	$price_risk = $price_risk * 50 / 100;
	
	$total_price_revenue += $price_risk;
	$smarty->assign('lst_revenue', $lst_revenue);
	$smarty->assign('price_risk', $price_risk);
	$smarty->assign('total_price_revenue', $total_price_revenue);
	
	$investment_performance_expense = $clsConfiguration->getValue('investment_performance_expense');
	$lst_expense = $clsISO->to_array_json($investment_performance_expense);
//	var_dump($lst_expense);die;
	$total_price_expense = $capital;
	
	$interest_first = $loan * 7.5/100;
	$interest_first = round($interest_first); $smarty->assign('interest_first', $interest_first);
	$interest_last = $loan * 9/100;
	$interest_last = round($interest_last); $smarty->assign('interest_last', $interest_last);
	$total_price_expense += ($interest_first + $interest_last);
	foreach ($lst_expense as $key => $value) {
		$lstChild = $value['lstChild'];
		foreach ($lstChild as $k_child_1 => $v_child_1) {
			
			if($v_child_1["unit_type"] == "_PERCENT") {
				if(!empty($value["is_interest"])) {
					$price = $loan * ($v_child_1['time'] / 12) * ($v_child_1['value'] / 100);
				}else{
					$price = $capital * ($v_child_1['time'] / 12) * ($v_child_1['value'] / 100);
				}
			}else{
				$price = $v_child_1['value'] * $v_child_1['time'];
			}
			$price = round($price);
			if(empty($v_child_1["is_late_offer"])) {
				$total_price_expense += $price;
			}
			
			$lstChild[$k_child_1]['price'] = $price;
			unset($price);
		}
		$lst_expense[$key]['lstChild'] = $lstChild;
	}
	$smarty->assign('lst_expense', $lst_expense);
	$smarty->assign('total_price_expense', $total_price_expense);
	$rate_revenue_expense = round($total_price_revenue * 100 / $total_price_expense,2);	
	$rate_revenue_expense_1_year = round($total_price_revenue * 100 / (2* $total_price_expense),2);	
	$smarty->assign('rate_revenue_expense', $rate_revenue_expense); 
	$smarty->assign('rate_revenue_expense_1_year', $rate_revenue_expense_1_year); 
	
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('performance'.DS.'_ajax.stock_performance.tpl');
	echo json_encode(array(
		'uid' 	=> $uid,
		'html' 	=> $html,
	)); die();
}
function performance_getNumberMonth($start_date, $end_date){
	$start_date = new DateTime($start_date);
	$end_date = new DateTime($end_date);
	$months = [];

	while ($start_date <= $end_date) {
		$months[] = $start_date->format('m/Y');
		$start_date->modify('+1 month');
	}
	return count($months);
}
function performance_loadTime(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$deviceType;
//	var_dump($_POST);die;	
	$time_buy = Input::post("time_buy","");
	$time_profit = Input::post("time_profit","");
	$time_leasing = Input::post("time_leasing","");
	$time_AHNG = Input::post("time_AHNG","");
	
	$str_time_buy = strtotime($time_buy);
	$str_time_profit = strtotime($time_profit);
	$str_time_leasing = strtotime($time_leasing);
	if($str_time_buy > $str_time_profit){
		$time_profit = date("Y-m-d",strtotime("+ 23 months",$str_time_buy));
	}
	if($str_time_buy > $str_time_leasing){
		$time_leasing = date("Y-m-d",strtotime("+ 2 months",$str_time_buy));
	}
	if($deviceType == 'phone') {
		$time_handover = date("Y/m/d",strtotime("+1 months",strtotime($time_buy)));
	}else{
		$time_handover = date("d/m/Y",strtotime("+1 months",strtotime($time_buy)));
	}
	
	$number_month_loan = performance_getNumberMonth($time_buy,$time_profit);
	$number_month_interest_first = performance_getNumberMonth($time_buy,$time_AHNG);
	$number_month_leasing = ceil(performance_getNumberMonth($time_leasing,$time_profit)/2);
	$number_month_leasing_buy = performance_getNumberMonth($time_buy,$time_leasing);
	$data = [
		"time_handover"					=>	$time_handover,
		"number_month_loan"				=>	$number_month_loan,
		"number_month_leasing"			=>	$number_month_leasing,
		"number_month_interest_first"	=>	($number_month_interest_first < $number_month_loan) ? $number_month_interest_first : $number_month_loan,
		"number_month_interest_last"	=>	($number_month_loan > $number_month_interest_first) ? $number_month_loan - $number_month_interest_first : 0,
		"number_month_leasing_buy"		=>	$number_month_leasing_buy,
		"time_min"						=>	$time_buy,
		"time_profit"					=>	$time_profit,
		"time_leasing"					=>	$time_leasing,
	];
	echo json_encode($data);die;
}
?>