<?php
	global $core,$smarty,$dbconn,$profile_id,$oneProfile,$staff_id,$clsConfiguration,$clsISO;
	$clsCity = new City();
	$clsCountry = new Country();
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsCustomerSales = new CustomerSales();
	$smarty->assign('clsCity', $clsCity);
	$smarty->assign('clsCountry', $clsCountry);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	
	$crm_field = ($holderG == '_report') ? 'search_report_field' : 'search_field';
	$smarty->assign('crm_field', $crm_field);
	###
	$keysearch = Input::get('keysearch');
	$smarty->assign('keysearch', $keysearch);
	###
	$list_date_ranges = array(
		'0-0' => 'Hôm nay',
		'0-1' => 'Hôm qua',
		'0-3' => '3 ngày trước',
		'4-7' => '4-7 ngày trước',
		'8-15' => '8-15 ngày trước',
		'16-30' => '16-30 ngày trước',
		'31-60' => '31-60 ngày trước',
		'61-120' => '61-120 ngày trước'
	);
	$smarty->assign('list_date_ranges', $list_date_ranges);
	###
	$clsCampaign = new Campaign();
	$cond = "`is_trash`=0 and `campaign_type`='_campaign'";
	$field = "{$clsCampaign->pkey},title";
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	if($department_id == _DEPARTMENT_MKT_ID){
		$cond.= " and (`user_id` in (
			select `profile_id` from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='"._DEPARTMENT_MKT_ID."'
		) OR `use_globe`=1)";
	} else {
		$cond.= " and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	}
	$list_campaigns = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", $field);
	$smarty->assign('list_campaigns', $list_campaigns); unset($list_campaigns);
	#followups status
	$lstFollowUpsStatus = $clsProperty->getCacheItems("FOLLOWUP_STATUS");
	$smarty->assign('lstFollowUpsStatus', $lstFollowUpsStatus); unset($list_campaigns);
	#
	$more_information = $oneProfile['more_information'];
	$crm_view_all = isset($more_information['crm_view_all']) ? (int) $more_information['crm_view_all'] : 0;
	$smarty->assign('crm_view_all', $crm_view_all);
	#
	$group_customer_sale = $clsConfiguration->getValue('group_customer_sale');
	$group_customer_sale = !empty($group_customer_sale) ? $clsISO->to_array_json($group_customer_sale) : [];
	$group_customer_sale = !empty($group_customer_sale[$profile_id]) ? $group_customer_sale[$profile_id] : [];
	#
	$arr_projects = $clsSetting->getCacheItems('_PROJECT');
	$smarty->assign('arr_projects', $arr_projects); unset($arr_projects);
	
	$arr_bedrooms = array();
	$tmp = $clsProperty->getCacheItems("_BEDROOM");
	if(!empty($tmp)){
		foreach($tmp as $val){
			$arr_bedrooms[] = $val;
		}
	}
	$tmp = $clsProperty->getCacheItems("_TYPE_VILLA");
	if(!empty($tmp)){
		foreach($tmp as $val){
			$arr_bedrooms[] = $val;
		}
	}
	$smarty->assign('arr_bedrooms', $arr_bedrooms); unset($arr_bedrooms);
	#
	$lstCustomerSale = $clsCustomerSales->getAll("`user_id`='{$profile_id}' AND `is_stop`='0' GROUP BY `assign_date`");
	$arr_customer_sale = [];
	foreach ($lstCustomerSale as $key => $val) {
		$arr_customer_sale[date("d/m/Y H:i",$val["assign_date"])] = $val["customer_id"];
	}
	$arr_cache_profile = [];
	foreach ($group_customer_sale as $key => $val) {
		if($clsISO->checkItemInArray($arr_customer_sale[date("d/m/Y H:i",$val["time"])],$val["list_ids"])) {
			if(!isset($arr_cache_profile[$val['admin_id']])) {
				$arr_cache_profile[$val['admin_id']] = $clsProfile->getFullname($val['admin_id']);
			}
			$group_customer_sale[$key]["title"] = sprintf("Giao cho %s (%s)",$arr_cache_profile[$val['admin_id']],date("d/m/Y H:i",$val["time"]));
		}else{
			unset($group_customer_sale[$key]);
		}
	}
	$smarty->assign('group_customer_sale', $group_customer_sale);
	// Tab "Nhóm": full_permiss → tất cả NV công ty; GĐKD/GĐV → NV trong subtree phòng ban (exclude bản thân).
	// Dùng cache profile (getProfileCached) thay vì query DB; cache đã lọc is_trash=0, có sẵn full_name.
	$team_reps = $_repRows = array();
	if ($clsISO->checkPermission('full_permiss_crm')) {
		foreach ((array) $clsProfile->getProfileCached('all') as $_pid => $_pv) {
			if ((int) $_pid !== (int) $profile_id) { $_repRows[$_pid] = $_pv; }
		}
	} elseif ($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')) {
		$_tdept = $department_id;
		if ($clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') && $_tdept == (int) _DEPARTMENT_SALE_ID) { $_tdept = 0; }
		if ($_tdept > 0) {
			$_trids = $clsProfile->getSubordinateStaffIds($_tdept, $profile_id);
			if (!empty($_trids)) {
				$_allCached = (array) $clsProfile->getProfileCached('all');
				foreach ($_trids as $_rid) {
					$_rid = (int) $_rid;
					if ($_rid > 0 && isset($_allCached[$_rid])) { 
						$_repRows[$_rid] = $_allCached[$_rid]; 
					}
				}
			}
		}
	}
	if (!empty($_repRows)) {
		// map phòng ban (cache) để gắn tên phòng cho từng NV
		$_deptMap = (array) $clsProperty->getArraySearchByKey('_DEPARTMENT');
		foreach ($_repRows as $_pid => $_pv) {
			$_did = (int) (isset($_pv['department_id']) ? $_pv['department_id'] : 0);
			$_dname = ($_did > 0 && isset($_deptMap[$_did]['title'])) ? $_deptMap[$_did]['title'] : '';
			$team_reps[] = array(
				'profile_id' => (int) $_pid,
				'full_name' => isset($_pv['full_name']) ? $_pv['full_name'] : '',
				'dept_name' => $_dname
			);
		}
		usort($team_reps, function ($a, $b) {
			return strcasecmp($a['full_name'], $b['full_name']);
		});
	}
	$smarty->assign('team_reps', $team_reps ? $team_reps : array());
	// TP Marketing (_ROLE_HEAD_FREE): bộ lọc NV phòng Marketing (dept _DEPARTMENT_MKT_ID) → lọc theo người tạo (user_id).
	// Loại trừ director/full-permiss (họ đã có select "Người quản lý"/"Nhóm NV" — tránh 2 field admin_id trùng).
	$is_tp_mkt = ($role_id === (int) _ROLE_HEAD_FREE && !$clsISO->checkPermissionGroup('DIRECTOR') && !$clsISO->checkPermission('full_permiss_crm')) ? 1 : 0;
	$mkt_staffs = array();
	if ($is_tp_mkt) {
		// chỉ NV phòng Marketing đang làm việc (getProfileCached('active') đã loại status nghỉ + is_trash)
		foreach ((array) $clsProfile->getProfileCached('active') as $_pid => $_pv) {
			if ((int) (isset($_pv['department_id']) ? $_pv['department_id'] : 0) !== (int) _DEPARTMENT_MKT_ID) { continue; }
			$_nm = isset($_pv['full_name']) ? trim($_pv['full_name']) : '';
			$mkt_staffs[] = array(
				'profile_id' => (int) $_pid,
				'text' => ($_nm !== '' ? $_nm : '#' . $_pid)
			);
		}
		usort($mkt_staffs, function ($a, $b) { return strcasecmp($a['text'], $b['text']); });
	}
	$smarty->assign('is_tp_mkt', $is_tp_mkt);
	$smarty->assign('mkt_staffs', $mkt_staffs);
?>