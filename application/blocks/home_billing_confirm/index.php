<?php
	global $smarty, $core, $profile_id, $oneProfile, $clsISO, $clsProfile, $clsProperty;
	$clsBilling = new Billing();
	
	$more_information = $oneProfile['more_information'];
	$billing_changing_confirms = $core->get_field($more_information, "billing_changing_confirms", []);
	$total_billing_changing_confirms = !empty($billing_changing_confirms) ? count($billing_changing_confirms) : 0;
	$smarty->assign('total_billing_changing_confirms', $total_billing_changing_confirms);
	
?>