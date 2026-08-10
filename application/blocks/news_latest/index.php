<?php
	global $dbconn, $core, $smarty, $mod;
	$clsNews = new News(); $smarty->assign('clsNews',$clsNews);
	$clsNewsBlock = new NewsBlock(); $smarty->assign('clsNewsBlock',$clsNewsBlock);
	#
    $where = "`t2`.`is_trash`=0 and `t2`.`type`='4TOPHOT'";
    $order_by = " order by `t2`.`order_no` desc";
    $limitCond =" limit 0,5";
	
	$field = "`t1`.`news_id`,`t1`.`reg_date`,`t2`.`news_block_id`";
	$list_more_news = $dbconn->getAll("select {$field} from ".$clsNews->tbl." as `t1` 
		inner join ".$clsNewsBlock->tbl." as `t2` on `t1`.`news_id`=`t2`.`news_id` 
		where ".$where.$order_by.$limitCond);
	$smarty->assign('list_more_news', $list_more_news);
?>