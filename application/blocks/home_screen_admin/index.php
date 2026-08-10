<?php 
	global $core, $smarty, $dbconn, $clsISO,$profile_id,$oneProfile;
	$clsLog = new Log();
	$clsMember = new Member();
	$clsHelper = new Helper();
	$clsProject = new Project();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsHelper', $clsHelper);
	###
	$list_preloaders = array();
	for($i=0; $i<=100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	###
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$smarty->assign('prev_year', $prev_year);
	$smarty->assign('current_year', $current_year);
	$smarty->assign('current_month', $current_month);
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
	$start_date = strtotime(sprintf('01-01-%s', $end_year));
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $end_year);
	$to_date = strtotime(sprintf('%s-12-%s', $end_day, $end_year));
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
?>