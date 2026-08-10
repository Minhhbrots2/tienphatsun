<?php
	global $dbconn, $core, $smarty, $mod,$oneProfile,$profile_id;
	$clsNews = new News();
	$clsCache = new Cache();
	$clsBilling = new Billing();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsIncentive = new Incentive();
	$smarty->assign('clsNews',$clsNews);
	$smarty->assign('clsProfile',$clsProfile);
	$smarty->assign('clsBilling',$clsBilling);
	$smarty->assign("clsIncentive", $clsIncentive);
	$lstCategory = $clsProperty->getArraySearchByKey("_NEWS_CATEGORY");
	#
    $order_by = " order by `t1`.`reg_date` desc";
    $limitCond =" limit 0,10";
	$field = "`t1`.`news_id`,`t1`.`title`,`t1`.`images`,`t1`.`user_id`,`t1`.`reg_date`
		,`t1`.`cat_id`,`t1`.`view_num`,`t2`.`full_name`,`t2`.`first_name`,`t2`.`last_name`";
	if($clsCache->has('home_news_cached')){
		$list_news = $clsCache->get('home_news_cached');
		// $clsCache->delete('home_news_cached');
	} else {
		$arr_news_cat_cached = $clsProperty->getArraySearchByKey("_NEWS_CATEGORY",0,_NEWS_CAT_SITE_ID);
		$list_news = $dbconn->getAll("SELECT {$field} FROM {$clsNews->tbl} AS `t1` 
			INNER JOIN {$clsProfile->tbl} as `t2` on `t1`.`user_id`=`t2`.`profile_id` 
			WHERE `t1`.`is_trash`=0 AND `t1`.`is_online`=1  AND `t1`.`cat_id` IN(".implode(',',array_keys($arr_news_cat_cached)).") ".$order_by.$limitCond);	
		if(!empty($list_news)){
			foreach($list_news as $k => $val) {
				$cat_id = $val['cat_id'];
				$user_id = $val['user_id'];
				$images = $val['images'];
				$images = $clsISO->to_array_json($images);
				if(!empty($images)) {
					$list_news[$k]["image"] = $images[0];
				}
				if(!isset($arr_cache[$user_id])) {
					$arr_cache[$user_id] = $clsProfile->getFullName($user_id, $val);
				}
				$list_news[$k]['author'] = $arr_cache[$user_id];
				$list_news[$k]['cat_name'] = $lstCategory[$cat_id]["title"];
			}
		}
		$clsCache->put('home_news_cached', $list_news,10*60);
	}
	// $clsISO->print_pre($list_news);die;
	$smarty->assign('list_news', $list_news);
	$lstIncentive= $clsIncentive->getByCond("`is_active`='1' AND '".time()."' BETWEEN `start_time` AND `end_time`".$limitCond);
	$smarty->assign("oneIncentive", $lstIncentive);
	#
	$cond_total_price = "`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0  AND FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."'";
	if(!$clsISO->checkPermissionGroup('DIRECTOR')) {
		$cond_total_price .= " AND `staff_id`='{$profile_id}'";
	}
	$total_price = $clsBilling->sumItem("totalgrand", $cond_total_price);
	$smarty->assign("total_price", $total_price);
	#
	$clsCourse = new Course();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	### Stats mobile home (toàn hệ thống): Check-ins hôm nay / Nhân sự mới + Giao dịch chốt tháng này
	$mh_today = date('d/m/Y');
	$mh_month = date('m/Y');
	$mh_checkin = $clsCheckIn->countItem("`checked_in`='1' AND FROM_UNIXTIME(`checked_in_date`,'%d/%m/%Y')='{$mh_today}'");
	$mh_new_staff = $clsProfile->countItem("`is_active`='1' AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$mh_month}'");
	$mh_deal_closed = $clsBilling->countItem("`is_trash`='0' AND `is_cancel`='0' AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$mh_month}'");
	$smarty->assign('mh_checkin', $mh_checkin);
	$smarty->assign('mh_new_staff', $mh_new_staff);
	$smarty->assign('mh_deal_closed', $mh_deal_closed);
	###
	$uid = $clsISO->getUniqid();
	$arr_profile_groups = array();
	$cache_name = sprintf("_profile_group_%s_cached",$profile_id);
	if($clsCache->has($cache_name)){
		$arr_profile_groups = $clsCache->get($cache_name);
	} else {
		$arr_profile_groups = array();
		$tmp = $clsGroupProfile->getAllCache("`list_profile_id` like '%|{$profile_id}|%'", $clsGroupProfile->pkey);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_profile_groups[] = $val[$clsGroupProfile->pkey];
			}
			unset($tmp);
		}
		// Save Cached
		$clsCache->put($cache_name, $arr_profile_groups, 60*60);
	}
	$department_id = $oneProfile['department_id'];
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$arr_department_ids[] = $department_id;
	$arr_department_ids = array_unique($arr_department_ids);
	$cond = " and (`is_all_staff` = '1' OR ((`is_all_staff`='0' and (";
	foreach($arr_department_ids as $id){
		$cond.= ($ii==0 ? "": " or ")."`list_department_id` like '%|{$id}|%'";
		++$ii;
	}
	$sql_group = "";
	if(!empty($arr_profile_groups)){$ii = 0;
		foreach($arr_profile_groups as $group_id){
			$sql_group.= ($ii==0 ? "" : " or "). " `list_group_profile_id` like '%|{$group_id}|%'";
			++$ii;
		}
	}
	$cond .= " or `list_profile_id` LIKE '%|{$profile_id}|%' 
		or `user_id`='{$profile_id}'))".(!empty($arr_profile_groups)? " 
		or (`is_all_staff`=2 and (".$sql_group."))" : "")."))";

	$total_events = 0;
	$list_events = $clsCourse->getAll("`is_trash`=0 and `is_online`=1 and ((".time()." between `start_date` and `due_date`) or (`start_date` between ".time()." and ".strtotime("+5days").")) ".$cond." order by `reg_date` DESC");
	if(!empty($list_events)){
		$arr_property_cached = array();
		$total_events = count($list_events);
		foreach($list_events as $key => $val){
			$course_id = $val[$clsCourse->pkey];
			$cat_id = (int) $val['cat_id'];
			$start_date = $val['start_date'];
			$due_date = $val['due_date'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(!isset($arr_property_cached[$cat_id])){
				$arr_property_cached[$cat_id] = $clsProperty->getTitle($cat_id);
			}
			$list_events[$key]['cat_name'] = $arr_property_cached[$cat_id];
			$have_cancel = 1;
			if(time() > $start_date){
				$have_cancel = 0;
			}
			if ($cat_id == _MEDIA_DISSEMINATION){
				$upload_share = $core->get_field($more_information, "upload_share", []);
				$list_events[$key]['total_joined'] = !empty($upload_share) ? @count($upload_share) : 0;
				$is_joined = 0;
				if(!empty($upload_share)){
					foreach($upload_share as $okey => $oval){
						if($oval['profile_id'] == $profile_id){
							$is_joined = 1;
							break;
						}
					}
				}
				$list_events[$key]['is_joined'] = $is_joined;
				$list_events[$key]['upload_share'] = $upload_share;
			}else{
				$total_joined = $clsCheckIn->countItem("`event_id`='{$course_id}'");
				$list_events[$key]['total_joined'] = $total_joined;
				$is_joined = $is_checked_in = 0;
				$tmp = $clsCheckIn->getByCond("`event_id`='{$course_id}' and `profile_id`='{$profile_id}'");
				if(!empty($tmp)){
					$is_joined = 1;
					$is_checked_in = $tmp['checked_in'];
				}
			}
			if(time() < $start_date){
				$list_events[$key]['status'] = '<span class="badge bg-label-info">Sắp diễn ra</span>';
			}else if($start_date < time() && time() < $due_date){
				$list_events[$key]['status'] = '<span class="badge bg-label-success">Đang diễn ra</span>';
			}else{
				$list_events[$key]['status'] = '<span class="badge bg-label-danger">Đã diễn ra</span>';
			}
			$list_events[$key]['have_cancel'] = $have_cancel;
			$list_events[$key]['is_joined'] = $is_joined;
			$list_events[$key]['is_checked_in'] = $is_checked_in;
			#- Qúa hạn
			$is_expired = (time() > $due_date) ? 1 : 0;
			$list_events[$key]['is_expired'] = $is_expired;
		}
	}
	$smarty->assign("list_events",$list_events);
?>