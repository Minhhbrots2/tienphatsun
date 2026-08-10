<?php
	global $smarty, $core, $dbconn;
	$get_stock_type = vnSessionExist('_ss_stock_type') 
		? vnSessionGetVar('_ss_stock_type') : _BLOCK_TYPE_HIGHLEVEL_SALE;
	$smarty->assign('get_stock_type', $get_stock_type);
?>