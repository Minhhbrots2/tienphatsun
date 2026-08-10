<?php
	global $core, $smarty;
	
	$list_preloaders = array();
	for($i=0; $i<3; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);	
?>