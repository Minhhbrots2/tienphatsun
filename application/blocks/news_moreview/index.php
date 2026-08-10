<?
	global $core,$smarty;
	$clsNews = new News();
	$smarty->assign('clsNews',$clsNews);
	#
	$smarty->assign('list_more_news',$clsNews->getAll("is_trash=0 and is_online=0 order by view_num DESC limit 0,10", $clsNews->pkey));
	unset($clsNews);
?>