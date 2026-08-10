<?php
	global $dbconn, $core, $smarty, $mod;
	$clsNews = new News();
	$clsCache = new Cache();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$arr_category = $clsProperty->getArraySearchByKey("_NEWS_CATEGORY");
	#
    $order_by = " order by `t1`.`reg_date` desc";
    $limitCond =" limit 0,9";
	$field = "`t1`.`news_id`,`t1`.`title`,`t1`.`images`,`t1`.`user_id`,`t1`.`reg_date`
		,`t1`.`cat_id`,`t1`.`view_num`,`t2`.`full_name`,`t2`.`first_name`,`t2`.`last_name`";
	if($clsCache->has('home_news_cached')){
		$list_news = $clsCache->get('home_news_cached');
		// $clsCache->delete('home_news_cached');
	} else {
		$list_news = $dbconn->getAll("SELECT {$field} FROM {$clsNews->tbl} AS `t1` 
			INNER JOIN {$clsProfile->tbl} as `t2` on `t1`.`user_id`=`t2`.`profile_id` 
			WHERE `t1`.`is_trash`=0 AND `t1`.`is_online`=1 AND `t1`.`domain_id`='"._NEWS_CAT_SITE_ID."'".$order_by.$limitCond);	
		if(!empty($list_news)){
			$arr_profile_cached = array();
			foreach($list_news as $k => $val) {
				$cat_id = $val['cat_id'];
				$user_id = $val['user_id'];
				$images = $val['images'];
				$images = $clsISO->to_array_json($images);
				if(!empty($images)) {
					$list_news[$k]["image"] = $images[0];
				}
				if(!isset($arr_profile_cached[$user_id])) {
					$arr_profile_cached[$user_id] = $clsProfile->getFullName($user_id, $val);
				}
				$list_news[$k]['author'] = $arr_profile_cached[$user_id];
				$list_news[$k]['cat_name'] = $arr_category[$cat_id]["title"];
			}
		}
		$clsCache->put('home_news_cached', $list_news, 60*60);
	}
	$smarty->assign('list_news', $list_news);
	$smarty->assign('clsNews',$clsNews);
	$smarty->assign('clsProfile',$clsProfile);
?>