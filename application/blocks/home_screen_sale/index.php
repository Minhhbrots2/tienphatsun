<?php 
	global $smarty, $core, $dbconn, $clsISO, $clsConfiguration, $profile_id, $oneProfile;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsHelper', $clsHelper);
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$more_information = $oneProfile['more_information'];
	###
	$list_staffs = $clsProfile->getProfileDep($department_id, 1, "all");
	$arr_staff_ids = !empty($list_staffs) ? @array_keys($list_staffs) : [];
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `staff_id` in ('".implode('\',\'', $arr_staff_ids)."')";
	#
	$time_configs = $clsConfiguration->getValue('time_config');
	$time_configs = $clsISO->to_array_json($time_configs);
	if($core->get_field($time_configs, "is_time_now", 0) == 1){
		$current_year = date('Y');
	} else {
		$current_year = (int) $core->get_field($time_configs, "year", date('Y'));
	}
	$prev_year = ($current_year - 1);
	$TOTAL_SALES = $clsBilling->sumItem("totalgrand", $cond." AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$current_year."'");
	$TOTAL_PERSON_SALES = $clsBilling->sumItem("totalgrand","`is_trash`=0 AND `is_cancel`=0 AND `staff_id`='{$profile_id}' 
		AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$current_year."'");
	$TOTAL_PERSON_BILLINGS = $clsBilling->countItem("`is_trash`=0 AND `is_cancel`=0 AND `staff_id`='{$profile_id}' 
		AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$current_year."'");
	$TOTAL_PREV_SALES = $clsBilling->sumItem("totalgrand", $cond." AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$prev_year."'");
	$TOTAL_PREV_PERSON_SALES = $clsBilling->sumItem("totalgrand","`is_trash`=0 AND `is_cancel`=0 
		AND `staff_id`='{$profile_id}' AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$prev_year."'");
	$smarty->assign('current_year', $current_year);
	$smarty->assign('prev_year', $prev_year);
	$smarty->assign('TOTAL_SALES', $TOTAL_SALES);
	$smarty->assign('TOTAL_PERSON_SALES', $TOTAL_PERSON_SALES);
	$smarty->assign('TOTAL_PERSON_BILLINGS', $TOTAL_PERSON_BILLINGS);
	$smarty->assign('TOTAL_PREV_SALES', $TOTAL_PREV_SALES);
	$smarty->assign('TOTAL_PREV_PERSON_SALES', $TOTAL_PREV_PERSON_SALES);
	###
	$list_preloader = array();
	for($i=0; $i<=10; $i++){
		$list_preloader[] = $i;
	}
	$smarty->assign('list_preloader', $list_preloader);
	
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	###
	$start_date = strtotime(sprintf('01-01-%s', $current_year));
	$num_day = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);
	$to_date = strtotime(sprintf('%s-12-%s', $num_day, $current_year));
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
?>