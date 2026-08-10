<?php 
	global $smarty, $dbconn, $clsISO;
	
	$list_preloaders = array();
	for($i=0; $i<=30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
?>