<?php 
	global $smarty, $dbconn, $clsISO;
	
	$list_preloaders = array();
	for($i=0; $i<=30; $i++){
		$list_preloaders[] = $i;
	}
	
	$current_month = date('m');
	$current_year = date('Y');
	$current_quarter = ceil(date('n') / 3);
	$smarty->assign('current_month', $current_month);
	$smarty->assign('current_quarter', $current_quarter);
	$smarty->assign('current_year', $current_year);
	$smarty->assign('list_preloaders', $list_preloaders);
?>