<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
	global $clsProduct, $dbconn, $core, $_LANG_ID, $smarty;
	
	$field = "*";
	$list_products = $clsProduct->getAll("is_trash=0 order by order_no ASC", $field);
	$smarty->assign('list_products', $list_products);
?>