<?php
	global $core,$smarty,$dbconn;
	// ini_set('display_errors',1);
	$list_preloaders = array();
	for($i=0; $i<10; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
?>