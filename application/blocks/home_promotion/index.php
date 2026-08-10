<?php
	global $smarty, $core, $dbconn, $clsISO;
	
	$clsPromotion = new Promotion();
	$smarty->assign('clsPromotion', $clsPromotion);
	$list_promotions = $clsPromotion->getAll("is_trash=0 and is_homepage=1 order by order_no DESC limit 0,3");
	$total_promotion = !empty($list_promotions) ? count($list_promotions) : 0;
	$smarty->assign('list_promotions', $list_promotions);
	$smarty->assign('total_promotion', $total_promotion);
	#
	$arr_placeholders = array();
	for($i=0; $i<(3-$total_promotion);$i++){
		$arr_placeholders[] = ($i+1);
	}
	$smarty->assign('arr_placeholders', $arr_placeholders);
	
?>