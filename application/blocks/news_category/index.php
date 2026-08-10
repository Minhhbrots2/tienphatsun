<?php
	global $core,$smarty,$clsISO,$cat_id,$profile_id;
	$clsNews = new News();
	$clsProperty = new Property();
	$smarty->assign('clsNews', $clsNews);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$cond = "`is_trash`=0 AND IF(`user_id`={$profile_id},1=1,`is_online`=1) AND `post_type`='_news'";
	$total_records = $clsNews->countItem($cond);
	$smarty->assign('total_records', $total_records);
	#
	$field = "{$clsProperty->pkey},title";
	$list_category = $clsProperty->getAll("is_trash=0 and property_type='_NEWS_CATEGORY' order by order_no ASC", $field);
	if(!empty($list_category)){
		foreach($list_category as $key => $val){
			$catId = $val[$clsProperty->pkey];
			$total_records_in = $clsNews->countItem("{$cond} and cat_id='{$catId}'");
			if($catId == _NEWS_CAT_CERTI_AGENCY_SITE_ID) {
				$list_category[$key]['link'] = '/chung-nhan-dai-ly.html';
			}else{
				$list_category[$key]['link'] = '/ban-tin.html?cat_id='.$catId;
			}
			
			$list_category[$key]['total_records_in'] = $total_records_in;
		}
	}
	$smarty->assign('list_category', $list_category);
	unset($list_category);
?>