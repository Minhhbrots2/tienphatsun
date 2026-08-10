<?
	global $core,$smarty;
	
	$clsConfiguration = new Configuration();
	$lstButton = $clsConfiguration->getValue('_LIST_RIGHT_BUTTONS');
	$lstButton = !empty($lstButton) ? @json_decode($lstButton, true) : array();
	$smarty->assign('lstButton', $lstButton);
	#
?>