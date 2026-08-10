<?php 
	global $core, $clsISO, $smarty, $dbconn, $profile_id;
	$clsIncentive = new Incentive();
	$lstItem = $clsIncentive->getAll("`is_active`='1' AND '".time()."' BETWEEN `start_time` AND `end_time`");
	#
	$smarty->assign("clsIncentive", $clsIncentive);
	$smarty->assign("lstItem", $lstItem);
?>