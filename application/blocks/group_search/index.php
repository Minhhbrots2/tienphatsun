<?php 
	global $smarty, $core, $dbconn, $clsISO;
	#
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	#
	$list_groups = $clsGroupProfile->getAll("1=1", "{$clsGroupProfile->pkey},`title`");
	$smarty->assign('list_groups', $list_groups);
?>