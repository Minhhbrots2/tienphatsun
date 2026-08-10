<?php 
	global $core, $smarty, $clsISO;
	#
	$w_www = 350;
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')){
		$w_www = 500;
	}
	$smarty->assign('w_www', $w_www);
?>