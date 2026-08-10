<?php
	global $core,$smarty,$clsConfiguration;
	$sale_policy = $clsConfiguration->getValue('sale_policy');
	$sale_policy = !empty($sale_policy) ? json_decode(html_entity_decode($sale_policy), true) : array();
	$smarty->assign('sale_policy', $sale_policy);
?>