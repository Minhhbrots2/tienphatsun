<?php 
	global $core, $smarty, $dbconn, $clsISO,$profile_id,$oneProfile,$block_id;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsProject = new Project();
	$smarty->assign('clsHelper', $clsHelper);
	###
	$current_year = date('Y');
	$prev_year = ($current_year - 1);
	$smarty->assign('prev_year', $prev_year);
	$smarty->assign('current_year', $current_year);
	###
	$list_preloaders = array();
	for($i=0; $i<=100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
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
	$smarty->assign('block_id', $block_id);
	$smarty->assign('lstBlock', $lstBlock);
?>