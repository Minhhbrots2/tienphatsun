<?php
	global $core,$smarty,$dbconn;
	$list_placeholders = array();
	for($i=0; $i<=6; $i++){
		$list_placeholders[] = $i;
	}
	$smarty->assign('list_placeholders', $list_placeholders);
	
	$current_month = date('n');
	$current_year = date('Y');
	$prev_month = $current_month - 1;
	$prev_year = $current_year;
	if($current_month==1){
		$prev_month = 12;
		$prev_year = ($current_year - 1);
	}
	$smarty->assign('current_month', $current_month);
	$smarty->assign('current_year', $current_year);
	$smarty->assign('prev_month', $prev_month);
	$smarty->assign('prev_year', $prev_year);
?>