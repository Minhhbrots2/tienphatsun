<?php
	global $core,$smarty,$clsProduct;
	#
	$clsNews = new News();
	$smarty->assign('clsNews',$clsNews);
	
	$field = "{$clsNews->pkey},`title`,`image`";
	$list_foods = $clsNews->getAll("`is_trash`=0 and `is_online`='1' order by order_no DESC limit 0,10", $field);
	$smarty->assign('list_foods',$list_foods); unset($list_foods);
?>