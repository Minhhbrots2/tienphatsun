<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function default_load_customers(){
	global $smarty, $core, $clsISO, $_LANG_ID, $dbconn,$clsProfile, $deviceType, $clsConfiguration;
	global $profile_id, $oneProfile;
	$clsStock 	 = new Stock();
	$clsCountry  = new Country();
	$clsCity 	 = new City();
	$clsArchived = new Archived();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerShare = new CustomerShare();
	$clsCustomerCampaign = new CustomerCampaign();
	$clsFollowUp = new FollowUp();
	$clsCampaign = new Campaign();
	$useJoinShare = false;
	$useJoinCampaign = false;
	$useJoinRelation = false;
	$joinSql = "";
	$smarty->assign("clsCustomer",$clsCustomer);
	$smarty->assign("clsFollowUp",$clsFollowUp);
	/* Global cond */
	$now = time();
	$start_time_today = strtotime(date('d-m-Y'));
	$end_time_today = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$start_time_7days = strtotime('-7 days', $now);
	#end_time_today
	$cond = $cnd = "`is_trash`=0";
	$has_search = $has_group = false;
	$action = Input::post('action',"");
	$tab = Input::post('tab','owner');
	$view = Input::post("view", "table");
	$holderG = Input::post('holderG','_tablist');
	$keysearch = Input::post('keysearch', "");
	$status_id = (int) Input::post('status_id');
	$block_id = (int) Input::post('block_id', 0);
	$bedroom_id = (int) Input::post('bedroom_id', 0);
	$sort_by = Input::post('sort_by', "last_contact");
	$priority_id = (int) Input::post('priority_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$typeHolder = Input::post('typeHolder',"_all");
	$staff_id = (int)Input::post('staff_id',0);
	$profile_id = !empty($staff_id) ? $staff_id : $profile_id;
	
	$is_all = (int) Input::post('is_all', 0);
	$reg_date = Input::post('reg_date', "");
	$group_id = (int) Input::post('group_id',0,true);
	$admin_id = (int) Input::post('admin_id',0,true);
	$campaign_id = (int) Input::post('campaign_id',0,true);
	$blocktype_id = (int) Input::post('blocktype_id',0,true);
	$group_customer_sale = Input::post('group_customer_sale',"",true);
	$shareJoinAdminId = ($admin_id > 0) ? $admin_id : $profile_id;
	$useJoinShare = true;
	$joinSql.= " LEFT JOIN (
		SELECT `customer_id` AS `customer_ref`, `admin_id` AS `share_admin_id`
		FROM `{$clsCustomerShare->tbl}`
		WHERE `admin_id`='{$shareJoinAdminId}'
		GROUP BY `customer_id`, `admin_id`
	) AS `cs` ON `cs`.`customer_ref`=`customer_id`";
	if($campaign_id > 0){
		$useJoinCampaign = true;
		$joinSql.= " LEFT JOIN (
			SELECT `customer_id` AS `customer_ref`, `campaign_id` AS `rel_campaign_id`
			FROM `{$clsCustomerCampaign->tbl}`
			WHERE `campaign_id`='{$campaign_id}'
			GROUP BY `customer_id`, `campaign_id`
		) AS `cc` ON `cc`.`customer_ref`=`customer_id`";
	}
	$useJoinRelation = ($useJoinShare || $useJoinCampaign) ? true : false;
	$from_notify = 0; $more = $arr_columns = array();
	$is_checkbox = $clsCustomer->isFullPermiss() ? true : false;
	
	if(!empty($keysearch)){
		$has_search = true;
		$arr_ids = @explode(',', $keysearch);
		$slug = $core->replaceSpace($keysearch);
		if(!empty($arr_ids)){
			$from_notify = 1;
			$cond.= " and (`name` like '%{$keysearch}%' 
				or `name_slug` like '%{$slug}%' 
				or `email` like '%{$keysearch}%' 
				or `phone` like '%{$keysearch}%' 
				or `address` like '%{$keysearch}%' 
				or `{$clsCustomer->pkey}` in ('".implode("','", $arr_ids)."')
			)";
		} else {
			$cond.= " and (`name` like '%{$keysearch}%' 
				or `name_slug` like '%{$slug}%' 
				or `email` like '%{$keysearch}%' 
				or `phone` like '%{$keysearch}%' 
				or `address` like '%{$keysearch}%'
			)";
		}
	}
	# filter by priority_id
	if(intval($priority_id) > 0){
		$has_search = true;
		$cnd.= " AND `priority_id`='{$priority_id}'";
		$cond.= " AND `priority_id`='{$priority_id}'";
	}
	# filter by resource_id
	if($resource_id > 0){
		$has_search = true;
		$cnd.= " AND `resource_id`='{$resource_id}'";
		$cond.= " AND `resource_id`='{$resource_id}'";
	}
	if($block_id > 0){
		$has_search = true;
		$cnd.= " AND `list_block_id` LIKE '%|{$block_id}|%'";
		$cond.= " AND `list_block_id` LIKE '%|{$block_id}|%'";
	}
	if($bedroom_id > 0){
		$has_search = true;
		$cnd.= " AND `list_bedroom_id` LIKE '%|{$bedroom_id}|%'";
		$cond.= " AND `list_bedroom_id` LIKE '%|{$bedroom_id}|%'";
	}
	# filter by reg_date
	if(!empty($reg_date)){
		$cnd.= " AND FROM_UNIXTIME(`reg_date`,'%Y-%m-%d')='{$reg_date}'";
		$cond.= " AND FROM_UNIXTIME(`reg_date`,'%Y-%m-%d')='{$reg_date}'";
	}
	# filter by campaign
	if($campaign_id > 0){
		if($useJoinCampaign){
			$cnd.= " AND (`cc`.`rel_campaign_id`='{$campaign_id}' OR `list_campaign_id` like '%|{$campaign_id}|%')";
			$cond.= " AND (`cc`.`rel_campaign_id`='{$campaign_id}' OR `list_campaign_id` like '%|{$campaign_id}|%')";
		} else {
			$cnd.= " AND ".$clsCustomer->sqlCondHasCampaign($campaign_id);
			$cond.= " AND ".$clsCustomer->sqlCondHasCampaign($campaign_id);
		}
	}
	# filter by status_id
	if(!empty($status_id)){
		$has_search = true;
		$cond.= " AND `status_id`='{$status_id}'";		
	} else {
		if($is_all == 0) {
			$cond.= " AND `status_id`<>'"._CRM_STATUS_TRASH_ID."'";
		}
	}
	$orderBy = ""; // Set Order Default
	if($group_id > 0){
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array(); 
		if(!empty($list_profile_arrs)){
			$has_group = true;
			$cnd.= " AND (`admin_id` in (".implode(',', $list_profile_arrs)."))";
			$cond.= " AND (`admin_id` in (".implode(',', $list_profile_arrs)."))";
		}
	}
	if($blocktype_id > 0){
		$cnd.= " AND `blocktype_id`='{$blocktype_id}'";
		$cond.= " AND `blocktype_id`='{$blocktype_id}'";
	}
	if($is_all == 0){
		$cnd.= " AND `customer_id` not in (
			select `customer_id` from `{$clsArchived->tbl}` 
			where `profile_id`='{$profile_id}'
		)";
		$cond.= " AND `customer_id` not in (
			select `customer_id` from `{$clsArchived->tbl}` 
			where `profile_id`='{$profile_id}'
		)";
	}
	if($group_customer_sale != ""){
		$group_customer = $clsConfiguration->getValue('group_customer_sale');
		$group_customer = !empty($group_customer) ? $clsISO->to_array_json($group_customer) : [];
		$arr_group_customer_sale = !empty($group_customer[$profile_id]) ? $group_customer[$profile_id] : [];
		if(!empty($arr_group_customer_sale[$group_customer_sale])) {
			$list_ids = $arr_group_customer_sale[$group_customer_sale]["list_ids"];
			$cnd.= " AND `customer_id` IN (".implode(',',$list_ids).")";
			$cond.= " AND `customer_id` IN (".implode(',',$list_ids).")";
		}
	}
	$sql_string = $sql_cond = $cond;
	if($admin_id > 0){
		// Ẩn checkbox vì mình không phải là người quản lý.
		$is_checkbox = $clsCustomer->isFullPermiss() ? true : false; 
		if($tab == 'owner' || $tab == 'converted_customer'){
			$cnd.= " AND (`admin_id`='{$admin_id}')";
			$cond.= " AND (`admin_id`='{$admin_id}')";
		} else {
			if($useJoinShare){
				$cnd.= " AND (`admin_id`<>'{$admin_id}' AND (`cs`.`share_admin_id`='{$admin_id}' OR `list_share_id` like '%|{$admin_id}|%'))";
				$cond.= " AND (`admin_id`<>'{$admin_id}' AND (`cs`.`share_admin_id`='{$admin_id}' OR `list_share_id` like '%|{$admin_id}|%'))";
			} else {
				$cnd.= " AND (`admin_id`<>'{$admin_id}' AND ".$clsCustomer->sqlCondHasShare($admin_id).")";
				$cond.= " AND (`admin_id`<>'{$admin_id}' AND ".$clsCustomer->sqlCondHasShare($admin_id).")";
			}
		}
	}
	#- Order by
	if(($tab == 'owner' || $tab == 'converted_customer') && $has_group == false){
		if($admin_id == 0){
			$cnd.= " AND `admin_id`='{$profile_id}'";
			$cond.= " AND `admin_id`='{$profile_id}'";
		}
		$is_checkbox = true;
		$orderBy = "order by `upd_date` ".($sort_by=='first_contact' ? 'ASC' : 'DESC');
	} else if($tab=='following' && $has_group == false) {
		if($admin_id == 0){
			if($useJoinShare){
				$cnd.= " AND `admin_id`<>'{$profile_id}' AND (`user_id`='{$profile_id}' or `cs`.`share_admin_id`='{$profile_id}' OR `list_share_id` like '%|{$profile_id}|%')";
				$cond.= " AND `admin_id`<>'{$profile_id}' AND (`user_id`='{$profile_id}' or `cs`.`share_admin_id`='{$profile_id}' OR `list_share_id` like '%|{$profile_id}|%')";
			} else {
				$cnd.= " AND `admin_id`<>'{$profile_id}' AND (`user_id`='{$profile_id}' 
					or ".$clsCustomer->sqlCondHasShare($profile_id).")";
				$cond.= " AND `admin_id`<>'{$profile_id}' AND (`user_id`='{$profile_id}' 
					or ".$clsCustomer->sqlCondHasShare($profile_id).")";
			}
		}
		$orderBy = " order by `use_globe` DESC, `upd_date` ".($sort_by=='first_contact' ? 'ASC' : 'DESC');
	}
	$smarty->assign("is_checkbox", $is_checkbox);
	#time 
	$date_type = Input::post('date_type', '_month');
	if($date_type == '_month'){
		$quarter = 0; // Init value
		$month  = (int) Input::post('month', 0);
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Init value
		$quarter  = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', 0);
	###
	if(!empty($year)) {
		if($date_type == '_month' && $month > 0){
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cnd.= " and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'";
			$cond.= " and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'";
		} else if($date_type == '_half_year' && $half_year > 0){
			if($half_year == 1){
				$start_month = 1;
				$end_month = 6;
			} else if($half_year == 2){
				$start_month = 7;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$cnd.= " AND (`reg_date` BETWEEN {$start_date} AND {$end_date})";
			$cond.= " AND (`reg_date` BETWEEN {$start_date} AND {$end_date})";
		} else if($date_type == '_quarter' && $quarter > 0){
			if($quarter == 1){
				$start_month = 1;
				$end_month = 3;
			} else if($quarter == 2){
				$start_month = 4;
				$end_month = 6;
			} else if($quarter == 3){
				$start_month = 7;
				$end_month = 9;
			} else if($quarter == 4){
				$start_month = 10;
				$end_month = 12;
			}
			$start_time = date(sprintf('%s-%s-01 00:00', $year, $clsISO->parseNumber($start_month)));
			$end_time = date(sprintf('%s-%s-t 23:59:59', $year, $clsISO->parseNumber($end_month)));
			$cnd.= " and `reg_date` BETWEEN ".strtotime($start_time)." AND ".strtotime($end_time);
			$cond.= " and `reg_date` BETWEEN ".strtotime($start_time)." AND ".strtotime($end_time);
		} else {
			$cnd.= " and FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
			$cond.= " and FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
		}
	}
	#- beign pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page',20);
	$per_page = ($per_page > 0) ? $per_page : 20;
	$countByWhere = function($where) use ($dbconn, $clsCustomer, $joinSql){
		$sql = "SELECT COUNT(DISTINCT `customer_id`) AS `total` 
			FROM `{$clsCustomer->tbl}` {$joinSql} 
			WHERE {$where}";
		$tmp = $dbconn->GetOne($sql);
		return (int) $tmp;
	};
	$total_record = $useJoinRelation ? $countByWhere($cond) : $clsCustomer->countItem($cond);
	$total_page = ceil($total_record/$per_page);
	$offset = 0;
	if($current_page > 1){
		$limit = $current_page * $per_page;
		$limitCond = " limit 0,{$limit}";
	} else {
		$offset = ($current_page-1)*$per_page;
		$limitCond = " limit {$offset},{$per_page}";
	}
	$smarty->assign('index', $offset);
	$smarty->assign('action', $action);
	#- end pagination
	if($has_group == false){
		if($admin_id > 0){
			$total_manage = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`='{$admin_id}')") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`='{$admin_id}')");
			if($useJoinShare){
				$total_assign = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`<>'{$admin_id}' and (`cs`.`share_admin_id`='{$admin_id}' OR `list_share_id` like '%|{$admin_id}|%'))") : 0;
			} else {
				$total_assign = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`<>'{$admin_id}' and ".$clsCustomer->sqlCondHasShare($admin_id).")") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`<>'{$admin_id}' and ".$clsCustomer->sqlCondHasShare($admin_id).")");
			}
			$total_converted = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`='{$admin_id}') and (`status_id`='"._CRM_STATUS_CHOT_ID."')") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`='{$admin_id}') and (`status_id`='"._CRM_STATUS_CHOT_ID."')");
		} else {
			$total_manage = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`='{$profile_id}')") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`='{$profile_id}')");
			if($useJoinShare){
				$total_assign = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`<>'{$profile_id}' AND (`cs`.`share_admin_id`='{$profile_id}' OR `list_share_id` like '%|{$profile_id}|%'))") : 0;
			} else {
				$total_assign = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`<>'{$profile_id}' AND ".$clsCustomer->sqlCondHasShare($profile_id).")") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`<>'{$profile_id}' AND ".$clsCustomer->sqlCondHasShare($profile_id).")");
			}
			$total_converted = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`='{$profile_id}') AND (`status_id`='"._CRM_STATUS_CHOT_ID."')") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`='{$profile_id}') AND (`status_id`='"._CRM_STATUS_CHOT_ID."')");
		}
	} else {
		$total_assign = 0;
		$total_manage = $total_record;
	}
	#
	$field = "{$clsCustomer->pkey},CONVERT(BINARY(CONVERT(`phone` USING latin1)) USING utf8mb4) as `phone`";
	if($useJoinRelation){
		$sqlList = "SELECT DISTINCT `{$clsCustomer->tbl}`.* 
			FROM `{$clsCustomer->tbl}` {$joinSql} 
			WHERE {$cond} {$orderBy} {$limitCond}";
		$list_customers = $dbconn->GetAll($sqlList);
	} else {
		$list_customers = $clsCustomer->getAll("{$cond} {$orderBy}".$limitCond);
	}
	
	$list_data_fields = $clsProperty->getArraySearchByKey("FIELD_DATA");
	$more_information = $oneProfile['more_information'];
	$def_field = ($view_by == "table") ? _ARRAY_TABLE_FIELD_DATA_CUSTOMER_DEFAULT : _ARRAY_COMPACT_FIELD_DATA_CUSTOMER_DEFAULT;
	$list_setting_field = $core->get_field($more_information, "fieldDataCustomer", $def_field);
	if(!empty($list_setting_field)){
		foreach($list_setting_field as $id) {
			if(!isset($list_data_fields[$id])) {
				$list_data_fields[$id] = $clsProperty->getOne($id,"`title`,`property_code`");
			}
			$arr_columns[$list_data_fields[$id]['property_code']] = $list_data_fields[$id];
		} 
	}
	$smarty->assign('arr_columns', $arr_columns);
	#
	$lst_data = [];
	$arr_field = !empty($arr_columns) ? @array_keys($arr_columns) : [];
	if(in_array('need', $arr_field)) $arr_need = $clsProperty->getArraySearchByKey("NEED");
	if(in_array('finance', $arr_field)) $arr_finance = $clsProperty->getArraySearchByKey("FINANCE");
	if(in_array('purpose', $arr_field)) $arr_purpose = $clsProperty->getArraySearchByKey("PURPOSE");
	if(in_array('bedroom', $arr_field)) $arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	if(in_array('customer_type', $arr_field)) $arr_customer_type = $clsProperty->getArraySearchByKey("CUSTOMER_TYPE");
	###
	$total_cus_new = $total_cus_7days = 0;
	$arr_status_cached = $arr_property_cached = $arr_block_cached = array();
	$s_field = "{$clsProperty->pkey},`property_type`,`title`,`property_code`,`bgcolor`,`intro`,`more_information`";
	$arr_property_ins = array('CUSTOMER_STATUS', '_AGENCY', '_GENDER');
	$tmp = $clsProperty->getAll("`property_type` in ('".implode('\',\'', $arr_property_ins)."') order by `order_no` ASC", $s_field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			if($val['property_type'] == 'CUSTOMER_STATUS'){
				$arr_status_cached[$val[$clsProperty->pkey]] = $val;
			} else {
				$arr_property_cached[$val[$clsProperty->pkey]] = $val;
			}
		}
		unset($tmp);
	}
	$tmp = $clsSetting->getCacheItems('_PROJECT');
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_block_cached[$val[$clsSetting->pkey]] = $val["title"];
		}
	}
	if(!empty($list_customers)){ $ii=0;// Init
		$arr_profile_cached = $clsProfile->getProfileCached();
		$arr_country_cached = $arr_city_cached = $arr_campaign_cached = array();
		foreach($list_customers as $k_cus => $cus){
			$reg_date = $val['reg_date'];
			$admin_id = $cus['admin_id'];
			$status_id = (int) $cus['status_id'];
			$customer_id = $cus[$clsCustomer->pkey];
			$resource_id = (int) $cus['resource_id'];
			$blocktype_id = (int) $cus['blocktype_id'];
			$list_tags_id = $cus['list_tags_id'];
			$list_stock_id = $cus['list_stock_id'];
			$list_campaign_id = $cus['list_campaign_id'];
			$more_info = $cus['more_information'];
			$more_info = $clsISO->to_array_json($more_info);
			$list_tags_arrs = $clsISO->getArrayByTextSlash($list_tags_id, ",", []);
			$list_stock_arrs = $clsISO->getArrayByTextSlash($list_stock_id, ",", []);
			$campaign_arrs = $clsISO->getArrayByTextSlash($list_campaign_id, ",", []);
			#
			if($status_id == _CRM_STATUS_LEAD_ID && ($reg_date > $start_time_today && $reg_date < $end_time_today)){
				$total_cus_new += 1;
			}
			if($status_id != _CRM_STATUS_CHOT_ID && ($reg_date > $start_time_7days && $reg_date < $now)){
				$total_cus_7days += 1;
			}
			$icon = ($admin_id==$profile_id)?'plus':'gavel';					
			$list_customers[$k_cus]['icon'] = $icon;
			#- Mục đích
			$title_purpose = "";
			$list_archived = $cus['list_archived'];
			$list_purpose_id = $cus['list_purpose_id'];
			if(!empty($list_purpose_id)){
				$tmp = $clsISO->getArrayByTextSlash($list_purpose_id);
				$title_purpose = " ".$clsProperty->getTitleArray($tmp, true);
			}
			$list_customers[$k_cus]['title_purpose'] = $title_purpose;
			$list_customers[$k_cus]['name'] = (!empty($cus['name']) ? ucfirst($cus['name']) : "Không tên");
			$list_customers[$k_cus]['begin_need'] = (!empty($cus['begin_need']) ? htmlspecialchars($cus['begin_need']) : "");
			#
			$is_archived = 0;
			$tmp = $clsArchived->getByCond("`profile_id`='{$profile_id}' and `customer_id`='{$customer_id}'");
			if(!empty($tmp)){ $is_archived = 1; }
			$icon_archived = $clsISO->makeIcon($is_archived ? 'bx bx-archive-in text-yellow' : 'bx bx-archive-in text-blank');
			$list_customers[$k_cus]['icon_archived'] = $icon_archived;
			###
			$html_follow_ups = "";
			$props = 'customer_id="'.$customer_id.'"';
			$total_followups = $total_followups_next = 0;
			$f_field = "{$clsFollowUp->pkey},`date_id`,`intro`";
			$list_followups = $clsFollowUp->getAll("`customer_id`='{$customer_id}' ORDER BY `reg_date` DESC", $f_field);
			if(!empty($list_followups)){ $kk = 1;
				$total_followups = count($list_followups);
				$html_follow_ups.= '<ul class="mb-0 list-unstyled lh-xs" style="min-width:200px">';
				foreach($list_followups as $mkey => $mval){
					if($kk == 1){
						$html_follow_ups.= '<li class="fs-12 my-0">
							<span class="text-'.($mval['date_id'] > $now ? 'main' : 'primary').'">'.($mval['date_id'] > $now ? $clsISO->getTimeMore($mval['date_id']) : $clsISO->getTimeAgo($mval['date_id']) ) .'</span> - '.ucfirst($mval['intro']).'
						</li>';
					}
					if($mval['date_id'] >= time()){
						$total_followups_next+= 1;
					}
					++$kk;
				}
				$html_follow_ups.= '</ul>';
			}
			$list_customers[$k_cus]['total_followups'] = $total_followups;
			$list_customers[$k_cus]['total_followups_next'] = $total_followups_next;
			#
			$html_tags = $clsCustomer->getHTMLTags($customer_id, $cus);
			$html_stocks = !empty($list_stock_arrs) 
				? sprintf('<div class="d-flex gap-1 mb-1">%s</div>', $clsStock->getTitleArray($list_stock_arrs, "_list")) : "";
			$list_customers[$k_cus]['html_tags'] = $html_tags;
			$list_customers[$k_cus]['html_stocks'] = $html_stocks;
			$list_customers[$k_cus]['html_follow_ups'] = $html_follow_ups;
			
			$oneStatus = $arr_status_cached[$status_id];
			$bgcolor = $oneStatus['bgcolor'];
			$textcolor = $oneStatus['textcolor'];
			$list_customers[$k_cus]['bgcolor'] = $bgcolor;
			$list_customers[$k_cus]['textcolor'] = $textcolor;
			#
			if(!isset($arr_property_cached[$status_id])){
				if($deviceType == "phone"){
					$arr_property_cached[$status_id] = $clsProperty->getLabel($status_id, " mr-1", false);
				}else{
					$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id, $oneStatus);
				}					
			}
			$status_name = $arr_property_cached[$status_id];
			$list_customers[$k_cus]['status_name'] = $arr_property_cached[$status_id];
			#
			if($resource_id > 0 && !isset($arr_property_cached[$resource_id])){
				$arr_property_cached[$resource_id] = $clsProperty->getTitle($resource_id);
			}
			$resource_name = $arr_property_cached[$resource_id];
			$list_customers[$k_cus]['resource_name'] = $resource_name;
			#
			$blocktype_name = "--";
			if($blocktype_id > 0){
				if(isset($arr_property_cached[$blocktype_id])){
					$blocktype_name = $arr_property_cached[$blocktype_id];
				} else {
					$arr_property_cached[$blocktype_id] = $clsProperty->getTitleCache('_BLOCK_TYPE', $blocktype_id);
					$blocktype_name = $arr_property_cached[$blocktype_id];
				}
			}
			$list_customers[$k_cus]['blocktype_name'] = $blocktype_name;
			if($clsISO->checkItemInArray("name", $arr_field)) {
				$arr_data["name"] = '<td class="text-left">
					<div class="d-block text-nowrap"><a href="javascript:void(0);" onClick="$Core.crm.open_customer(this,event)" class="link goLink font-bold view_customer fs-6" route="/customer/'.$customer_id.'/overview" customer_id="'.$customer_id.'">
						'.(!empty($cus['name']) ? ucfirst($cus['name']) : "Không tên").'</a>'.$title_purpose.'
					 </div>
					<div class="d-flex align-items-center gap-1 fs-11">
						<span class="d-flex gap-1 align-items-center text-nowrap">
							<i class="material-icons-outlined fs-13 no-translate">more_time</i>
							'.$clsISO->getTimeAgo($cus["reg_date"]).'
						</span>
						<span class="d-flex gap-1 align-items-center text-warning text-nowrap">
							<i class="material-icons-outlined fs-13 no-translate">alarm</i>
							'.$clsISO->getTimeAgo($cus["upd_date"]).'
						</span>
					</div>
				</td>';
			}
			if($clsISO->checkItemInArray("phone", $arr_field)) {
				$arr_data["phone"] = '<td class="text-left">
					<div class="d-flex gap-1 align-items-center">'.
						(!empty($cus['phone']) ? ('<a href="https://zalo.me/'.$cus['phone'].'" target="_blank" class="zalo_chat"></a>
							<a href="tel:'.$cus['phone'].'" class="js_clicktocall text-nowrap text-body fs-13">
							<img src="'.URL_IMAGES.'/phone-icon.png">'.$clsCustomer->mask($cus['phone'], true).'</a>') : '---').'
					</div>
				</td>';
			}
			if($clsISO->checkItemInArray("admin",$arr_field)) {
				$arr_data["admin"] = '<td class="text-center">
					<a href="javascript:void(0);" '.(($cus['admin_id'] == $profile_id) ? 'onClick="$Core.crm.change_assigned(this, event)"' : '')
					.' customer_id="'.$customer_id.'" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id='.$cus['admin_id'].'" data-toggle="webui-popover" data-trigger="hover" data-width="350">
						<img class="avatar avatar-xs mr-2 rounded-pill" src="'.$clsProfile->getAvatar($cus['admin_id']).'">
						<span class="cre">'.$core->makeIcon($icon).'</span>
					</a>
				</td>';
			}
			if($clsISO->checkItemInArray("follow-ups",$arr_field)) {
				$arr_data["follow-ups"] = '<td style="width:55px" class="text-center">
					<a onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="btn btn-sm btn-icon btn-outline-default text-main">'.$total_followups.'</a>
				</td>
				<td class="text-left">
					'.$html_stocks.$html_follow_ups.$html_tags.'
				</td>';
			}
			if($clsISO->checkItemInArray("resource",$arr_field)) {
				$arr_data["resource"] = '<td class="text-left text-nowrap">'.$resource_name.'</td>';
			}
			if($clsISO->checkItemInArray("campaign",$arr_field)) {
				$campaign_group_name = ""; $title_campaign_arrs = array();
				if(!empty($campaign_arrs)){
					foreach($campaign_arrs as $campaign_id){
						if(isset($arr_campaign_cached[$campaign_id])){
							$title_campaign_arrs[] = '<a data-bs-toggle="tooltip" title="Lọc khách hàng" campaign_id="'.$campaign_id.'" 
								class="text-muted cursor-pointer" onClick="$Core.crm.set_campaign(this, event)">
								'.$arr_campaign_cached[$campaign_id].'
							</a>'; 
						} else {
							$arr_campaign_cached[$campaign_id] = $clsCampaign->getTitle($campaign_id);
							$title_campaign_arrs[] = '<a data-bs-toggle="tooltip" title="Lọc khách hàng" campaign_id="'.$campaign_id.'" 
								class="text-muted cursor-pointer" onClick="$Core.crm.set_campaign(this, event)">
								'.$arr_campaign_cached[$campaign_id].'
							</a>'; 
						}
					}
				} else {
					$title_campaign_arrs[] = "--";
				}
				$campaign_group_name = implode(',', $title_campaign_arrs);
				$list_customers[$k_cus]['title_campaign_arrs'] = $campaign_group_name;
				$arr_data["campaign"] = '<td class="text-left text-nowrap">'.$campaign_group_name.'</td>';
			}
			if($clsISO->checkItemInArray("blocktype",$arr_field)) {
				$arr_data["blocktype"] = '<td class="text-left text-nowrap">'.$blocktype_name.'</td>';
			}
			if($clsISO->checkItemInArray("begin_need",$arr_field)) {
				$arr_data["begin_need"] = '<td class="align-center">'.htmlspecialchars($cus['begin_need']).'</td>';
			}
			if($clsISO->checkItemInArray("agency_name", $arr_field)) {
				$agent_id = $core->get_field($more_info, "agent_id", 0);
				$agency_name = ($agent_id > 0 && isset($arr_property_cached[$agent_id])) ? $arr_property_cached[$agent_id]['title'] : "";
				$arr_data["agency_name"] = '<td class="align-center">'.$agency_name.'</td>';
			}
			if($clsISO->checkItemInArray("gender_name", $arr_field)) {
				$gender_id = $core->get_field($more_info, "gender_id", 0);
				$gender_name = ($gender_id > 0 && isset($arr_property_cached[$gender_id])) ? $arr_property_cached[$gender_id]['title'] : "";
				$arr_data["gender_name"] = '<td class="align-center">'.$gender_name.'</td>';
			}
			#bedroom
			if($clsISO->checkItemInArray("bedroom",$arr_field)) {
				$arrBedroom = [];
				$list_bedroom_id = $cus['list_bedroom_id'];
				$list_bedroom_id = $clsISO->getArrayByTextSlash($list_bedroom_id);				
				if(!empty($list_bedroom_id)) {
					foreach($list_bedroom_id as $bedroom_id) {
						if(!empty($arr_bedroom[$bedroom_id])) {
							$arrBedroom[] = $arr_bedroom[$bedroom_id]['title'];
						}
					}
				}
				$arr_data["bedroom"] = '<td class="align-center">'.(!empty($arrBedroom) ? implode(", ",$arrBedroom) : "---" ).'</td>';
			}
			#block
			if($clsISO->checkItemInArray("block",$arr_field)) {
				$arr_blocks = [];
				$list_block_id = $cus['list_block_id'];
				$tmp = $clsISO->getArrayByTextSlash($list_block_id);
				if(!empty($tmp)) {
					foreach($tmp as $block_id) {
						if(!empty($arr_block_cached[$block_id])) {
							$arr_blocks[] = $arr_block_cached[$block_id];
						}
					}
				}
				// $clsISO->print_pre($arr_blocks); die();
				$arr_data["block"] = '<td class="align-center">
					'.(!empty($arr_blocks) ? implode(", ",$arr_blocks) : "---" ).'
				</td>';
			}
			if($clsISO->checkItemInArray("status", $arr_field)) {
				$arr_data["status"] = '<td class="text-left text-nowrap">
					<label class="w-100 badge" style="background:'.$oneStatus['bgcolor'].' !important; color:'.$oneStatus['textcolor'].' !important">'.$status_name.'</label>
				</td>';
			}
			#list share
			if($clsISO->checkItemInArray("list_share", $arr_field)) {
				$arr_shares = [];
				$list_share_id = $cus['list_share_id'];
				$tmp = !empty($list_share_id) ? $clsISO->getArrayByTextSlash($list_share_id) : [];
				$tmp = array_unique($tmp);
				if(!empty($tmp)) {
					foreach($tmp as $share_id) {
						if(isset($arr_profile_cached[$share_id]) && $admin_id != $share_id) {
							$arr_shares[] = '<img data-bs-toggle="tooltip" class="avatar avatar-xxs rounded-pill" alt="'.$arr_profile_cached[$share_id]["full_name"].'" title="'.$arr_profile_cached[$share_id]["full_name"].'" src="'.$clsProfile->getAvatar($share_id, $arr_profile_cached[$share_id], 30, 30).'" />';
						}
					}
				}
				$arr_data["list_share"] = '<td class="align-center">
					'.(!empty($arr_shares) ? '<div class="avatar-group">'.implode(",",$arr_shares).'</div>' : "--" ).'
				</td>';
			}
			if($clsISO->checkItemInArray("email",$arr_field)) {
				$arr_data["email"] = '<td class="align-center">'.(!empty($cus['email']) ? $cus['email'] : "---" ).'</td>';
			}
			if($clsISO->checkItemInArray("address",$arr_field)) {
				$arr_data["address"] = '<td class="align-center">'.(!empty($cus['address']) ? $cus['address'] : "---" ).'</td>';
			}
			#country
			if($clsISO->checkItemInArray("country", $arr_field)) {
				$country_id = (int) $cus['country_id'];
				if($country_id > 0 && !isset($arr_country_cached[$country_id])) {
					$arr_country_cached[$country_id] = $clsCountry->getTitle($country_id);
				}
				$arr_data["country"] = '<td class="align-center text-nowrap">
					'.(!empty($arr_country_cached[$country_id]) ? $arr_country_cached[$country_id] : "---" ).'
				</td>';
			}
			#city
			if($clsISO->checkItemInArray("city", $arr_field)) {
				$city_id = (int) $cus['city_id'];
				if($city_id > 0 && !isset($arr_city_cached[$city_id])) {
					$arr_city_cached[$city_id] = $clsCity->getTitle($city_id);
				}
				$arr_data["city"] = '<td class="align-center text-nowrap">
					'.(!empty($arr_city_cached[$city_id]) ? $arr_city_cached[$city_id] : "---" ).'
				</td>';
			}
			#birdthday			
			if($clsISO->checkItemInArray("birthday", $arr_field)) {
				$arr_data["birthday"] = '<td class="align-center">'.(!empty($cus['birthday']) ? $clsISO->convertTimeToText($cus['birthday'],0,"/") : '--').'</td>';
			}
			#customer_type			
			if($clsISO->checkItemInArray("customer_type", $arr_field)) {
				$arrType = [];
				$list_type_id = $cus['list_type_id'];
				$tmp = $clsISO->getArrayByTextSlash($list_type_id);
				if(!empty($tmp)) {
					foreach($tmp as $type_id) {
						if(!empty($tmp[$type_id])) {
							$arrType[] = $tmp[$type_id]['title'];
						}
					}
				}
				$arr_data["customer_type"] = '<td class="align-center">
					'.(!empty($arrType) ? @implode(", ",$arrType) : "--" ).'
				</td>';
			}
			#finance			
			if($clsISO->checkItemInArray("finance", $arr_field)) {
				$finance_id = $cus['finance_id'];
				$arr_data["finance"] = '<td class="align-center">
					'.(!empty($arr_finance[$finance_id]) ? $arr_finance[$finance_id]['title'] : "---" ).'
				</td>';
			}
			#purpose			
			if($clsISO->checkItemInArray("purpose",$arr_field)) {
				$list_purpose_id = $cus['list_purpose_id'];
				$list_purpose_id = $clsISO->getArrayByTextSlash($list_purpose_id);
				$arrPurpose = [];
				if(!empty($list_purpose_id)) {
					foreach($list_purpose_id as $purpose_id) {
						if(!empty($arr_purpose[$purpose_id])) {
							$arrPurpose[] = $arr_purpose[$purpose_id]['title'];
						}
					}
				}
				$arr_data["purpose"] = '<td class="align-center">'.(!empty($arrPurpose) ? implode(", ",$arrPurpose) : "---" ).'</td>';
			}
			#need			
			if($clsISO->checkItemInArray("need", $arr_field)) {
				$arrNeed = [];
				$list_need_id = $cus['list_need_id'];
				$list_need_id = $clsISO->getArrayByTextSlash($list_need_id);
				if(!empty($list_need_id)) {
					foreach($list_need_id as $need_id) {
						if(!empty($arr_need[$need_id])) {
							$arrNeed[] = $arr_need[$need_id]['title'];
						}
					}
				}
				$arr_data["need"] = '<td class="align-center">'.(!empty($arrNeed) ? implode(", ",$arrNeed) : "---" ).'</td>';
			}
			$lst_data[$customer_id] = [
				"customer_id" => $customer_id,
				"icon_archived" => $icon_archived,
				"total_followups_next" => $total_followups_next,
				"data_html" => $arr_data
			];
			++$ii;
		}							
		$lst_data = array_map(function($item) use ($arr_field) {
			$sorted_item = $item; // Giữ nguyên cus_id
			$sorted_item["data_html"] = [];
			foreach($arr_field as $field) {
				if (isset($item["data_html"][$field])) {
					$sorted_item["data_html"][$field] = $item["data_html"][$field];
				}
			}
			return $sorted_item;
		}, $lst_data);
		$smarty->assign("lst_data",$lst_data);
	} else {
		$html_empty = CRM::renderHTMLNoDocument('Not any record(s)');
		$smarty->assign("html_empty",$html_empty);
	}
	#
	$html_briefs = $html_warning = "";
	if($action != "load_more"){
		if(!empty($arr_status_cached)){ $ii = 1;
			foreach($arr_status_cached as $key => $val){
				$property_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if((int) $core->get_field($more_information, "is_funnel_active", 0) == 1){
					$total_customers = $useJoinRelation ? $countByWhere($cnd." and `status_id`='{$property_id}'") : $clsCustomer->countItem($cnd." and `status_id`='{$property_id}'");
					$html_briefs.= '<div onClick="$Core.crm.set_status(this,event)" status_id="'.$property_id.'" 
						class="funnel-stage cursor-pointer" style="background:'.$val['bgcolor'].'">
						<span class="label">'.$val['title'].'</span>
						<span class="value">'.$total_customers.' </span>
					</div>';
				}
				++$ii;
			}
		}
		$arr_task_type = $arr_call_type = []; $total_overdue_followups = 0;
		$list_followups = $clsFollowUp->getAll("`admin_id`='{$profile_id}' AND `status_id`<>'"._FOLLOWUP_STATUS_DONE_ID."' 
			AND FROM_UNIXTIME(`date_id`,'%m/%Y')='".date('m/Y')."'", "`date_id`,`type_id`");
		$start_time_tomorrow = strtotime(date('d-m-Y', strtotime('+1 day')));
		$end_time_tomorrow = strtotime(sprintf('%s 23:59:59', date('d-m-Y', strtotime('+1 day'))));
		// $clsISO->print_pre($list_followups); die();
		if(!empty($list_followups)){
			foreach($list_followups as $key => $val){
				$date_id = $val['date_id'];
				$type_id = (int) $val['type_id'];
				if($type_id == _FOLLOWUP_TASK_ID){
					if($date_id < $now){
						$total_overdue_followups += 1;
					}
					$today_key = sprintf('today_%s', $type_id);
					$tomorrow_key = sprintf('tomorrow_%s', $type_id);
					if($date_id >= $start_time_today && $date_id <= $end_time_today){
						if(isset($arr_type[$today_key])){
							$arr_task_type[$today_key]['total'] += 1;
						} else {
							$arr_task_type[$today_key] = array(
								'time' => 'today',
								'type_id' => $type_id,
								'total' => 1
							);
						}
					} else if($date_id >= $start_time_tomorrow && $date_id <= $end_time_tomorrow){
						if(isset($arr_type[$tomorrow_key])){
							$arr_task_type[$tomorrow_key]['total'] += 1;
						} else {
							$arr_task_type[$tomorrow_key] = array(
								'time' => 'tomorrow',
								'type_id' => $type_id,
								'total' => 1
							);
						}
					}
				} else if($type_id == _FOLLOWUP_CALL_ID) {
					if($date_id >= $start_time_today && $date_id <= $end_time_today){
						if(isset($arr_call_type[$today_key])){
							$arr_call_type[$today_key]['total'] += 1;
						} else {
							$arr_call_type[$today_key] = array(
								'time' => 'today',
								'type_id' => $type_id,
								'total' => 1
							);
						}
					}
				}
			}
			if(!empty($arr_task_type)){
				foreach($arr_task_type as $key => $val){
					$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-primary p-2 rounded-2">
						<div class="w-px-30 p-2"><i class="fa fa-clock-o text-fs-20"></i></div>
						<div class="d-flex flex-column">
							<h3 class="mb-1 text-fs-14"><strong class="fs-6">'.$val['total'].'</strong> cuộc gặp vào '.($val['time'] == 'today' ? 'hôm nay' : ' ngày mai').'</h3>
							<small>Cần chuẩn bị nội dung & tài liệu đầy đủ</small>
						</div>
					</div>';
				}
			}
			if(!empty($arr_call_type)){
				foreach($arr_call_type as $key => $val){
					$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-purple p-2 rounded-2">
						<div class="w-px-30 p-2"><i class="fa fa-phone-square text-fs-20"></i></div>
						<div class="d-flex flex-column">
							<h3 class="mb-1 text-fs-14"><strong class="fs-6">'.$val['total'].'</strong> cuộc gọi gọi điện vào '.($val['time'] == 'today' ? 'hôm nay' : ' ngày mai').'</h3>
							<small>Cần chuẩn bị nội dung & tài liệu đầy đủ</small>
						</div>
					</div>';
				}
			}
			if($total_overdue_followups > 0){
				$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-warning p-2 rounded-2">
					<div class="w-px-30 p-2"><i class="fa fa-exclamation-triangle text-fs-20"></i></div>
					<div class="d-flex flex-column">
						<h3 class="mb-1 text-fs-14">'.$total_overdue_followups.' follow-ups đã  bị quá hạn</h3>
						<small>Hãy lên lịch liên hệ để giữ nhịp chăm sóc</small>
					</div>
				</div>';
			}
		} else {
			$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-warning p-2 rounded-2">
				<div class="w-px-30 p-2"><i class="fa fa-exclamation-triangle text-fs-20"></i></div>
				<div class="d-flex flex-column">
					<h3 class="mb-1 text-fs-14">30 ngày bạn chưa tương tác với khách hàng</h3>
					<small>Hãy lên lịch liên hệ để giữ nhịp chăm sóc</small>
				</div>
			</div>';
		}
		// $clsISO->print_pre($list_followups); die();
		if($total_cus_new > 0){
			$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-success p-2 rounded-2">
				<div class="w-px-30 p-2"><i class="fa fa-bell-o text-fs-20"></i></div>
				<div class="d-flex flex-column">
					<h3 class="mb-1 text-fs-14">'.$total_cus_new.' khách tiền năng cần xử lý</h3>
					<small>Ưu tiên liên hệ sớm để nắm bắt cơ hội.</small>
				</div>
			</div>';
		}
		if($total_cus_7days == 0){
			$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-lighter p-2 rounded-2">
				<div class="w-px-30 p-2"><i class="fa fa-info-circle text-fs-20"></i></div>
				<div class="d-flex flex-column">
					<h3 class="mb-1 text-fs-14">7 ngày gần đây bạn chưa có khách hàng mới</h3>
					<small>Cần chủ động tiếp cận để tạo cơ hội bán hàng.</small>
				</div>
			</div>';
		}
	}
	if(empty($html_warning)){
		$html_warning .= '<div class="border text-center rounded-2 p-3 border-dashed">
			Bạn chưa có thông báo nào !
		</div>'; 
	}
	#
	$smarty->assign("typeHolder",$typeHolder);
	$smarty->assign("date_id",$date_id);
	$smarty->assign("holderG",$holderG);
	$smarty->assign("is_checkbox",$is_checkbox);
	$smarty->assign("sort_by", $sort_by);
	$smarty->assign("list_customers",$list_customers);
	$smarty->assign("per_page",$per_page);
	$smarty->assign("current_page",$current_page);
	$smarty->assign("total_page",$total_page);
	// Output
	$html = $core->build("_ajax.list_customer.tpl");
	echo json_encode(array_merge($more, array(
		'html' => $html,
		'cond' => $cond,
		'from_notify' => $from_notify,
		'current_page'	=> $current_page,
		'total_assign' => $total_assign,
		'total_manage' => $total_manage,
		'total_converted' => $total_converted,
		'total_page'	=> $total_page,
		'total_record'	=> $total_record,
		'per_page'	=> $per_page,
		'action'	=> $action,
		'html_briefs'	=> $html_briefs,
		'html_warning' => $html_warning
	)), JSON_UNESCAPED_UNICODE); die();
}
function default_load_converted_rates(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn;
	global $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsCustomerHistory = new CustomerHistory();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	
	$html = "";
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$query = "SELECT
			`s`.`from_status_id`,
			`s`.`to_status_id`,
			COUNT(DISTINCT `s`.`customer_id`) AS `total_move`,
			b.total_from,
			ROUND(
				COUNT(DISTINCT `s`.`customer_id`) / `b`.`total_from` * 100,
				0
			) AS `conversion_rate`
		FROM {$clsCustomerHistory->tbl} `s`
		JOIN (
			SELECT
				`from_status_id`,
				COUNT(DISTINCT customer_id) AS `total_from`
			FROM {$clsCustomerHistory->tbl}
			WHERE `from_status_id` > 0
			GROUP BY `from_status_id`
		) `b` ON `s`.`from_status_id` = `b`.`from_status_id`
		WHERE `s`.`from_status_id` > 0
		GROUP BY `s`.`from_status_id`, s.to_status_id
		ORDER BY `s`.`from_status_id`, `s`.`to_status_id`;";
	} else {
		$query= "SELECT
			s.from_status_id,
			s.to_status_id,
			COUNT(DISTINCT s.customer_id) AS total_move,
			b.total_from,
			ROUND(
				COUNT(DISTINCT s.customer_id) / b.total_from * 100,
				2
			) AS conversion_rate
		FROM {$clsCustomerHistory->tbl} s
		JOIN {$clsCustomer->tbl} c ON c.customer_id = s.customer_id
		JOIN (
			SELECT
				h.from_status_id,
				COUNT(DISTINCT h.customer_id) AS total_from
			FROM {$clsCustomerHistory->tbl} h
			JOIN {$clsCustomer->tbl} c2 ON c2.customer_id = h.customer_id
			WHERE c2.admin_id = {$profile_id}
			  AND h.from_status_id > 0
			GROUP BY h.from_status_id
		) b ON s.from_status_id = b.from_status_id
		WHERE c.admin_id = {$profile_id}
		  AND s.from_status_id > 0
		GROUP BY s.from_status_id, s.to_status_id
		ORDER BY s.from_status_id;";
	}
	// $dbconn->debug = true;
	$list = $dbconn->getAll($query);
	if(!empty($list)){ $ii = 0;
		$arr_property_cached = array();
		$tmp = $clsProperty->getCacheItems("CUSTOMER_STATUS");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach($list as $key => $val){
			$from_status_id = (int) $val['from_status_id'];
			$to_status_id = (int) $val['to_status_id'];
			if($from_status_id != $to_status_id) {
				$html.= '<div class="d-flex flex-column mb-1'.($ii <= 4 ? '' : ' d-none toggleRow').'">
					<div class="d-flex align-items-center justify-content-between text-fs-13 mb-0">
						<span class="text-muted">'.$arr_property_cached[$from_status_id].' ('.$val['total_from'].')</span>
						<span class="">'.$arr_property_cached[$to_status_id].' ('.$val['total_move'].')</span>
					</div>
					<div class="progress w-100 h-px-12">
						<div class="progress-bar bg-info" role="progressbar" style="width:'.$val['conversion_rate'].'%">'.$val['conversion_rate'].'%</div>
					</div>
				</div>';
				++$ii;
			}
		}
		$html.= '<div class="d-flex">
			<a onClick="$Core.util.toggle_tr(this, event)" toCls="toggleRow" class="btn btn-sm btn-outline-default">Xem thêm
				<i class=\'bx bx-chevron-down\'></i>
			</a>
		</div>';
	} else {
		$html.= '<div class="d-flex p-3 flex-column align-items-center justify-content-center">
			<img src="'.URL_IMAGES.'/empty.svg" class="w-px-100 mb-2" />
			<p class="text-muted">Chưa có khách hàng nào được chuyển đổi!</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_report_campaign(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn; 
	$clsCustomer = new Customer();
	$clsCustomerShare = new CustomerShare();
	$clsCustomerCampaign = new CustomerCampaign();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$useJoinShare = false;
	$useJoinCampaign = false;
	$useJoinRelation = false;
	$joinSql = "";
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '_month');
	$resource_id = (int) Input::post('resource_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	$group_id = (int) Input::post('group_id', 0);
	$admin_id = (int) Input::post('admin_id',0,true);
	$shareJoinAdminId = ($admin_id > 0) ? $admin_id : $profile_id;
	$useJoinShare = true;
	$joinSql.= " LEFT JOIN (
		SELECT `customer_id` AS `customer_ref`, `admin_id` AS `share_admin_id`
		FROM `{$clsCustomerShare->tbl}`
		WHERE `admin_id`='{$shareJoinAdminId}'
		GROUP BY `customer_id`, `admin_id`
	) AS `cs` ON `cs`.`customer_ref`=`customer_id`";
	if($campaign_id > 0){
		$useJoinCampaign = true;
		$joinSql.= " LEFT JOIN (
			SELECT `customer_id` AS `customer_ref`, `campaign_id` AS `rel_campaign_id`
			FROM `{$clsCustomerCampaign->tbl}`
			WHERE `campaign_id`='{$campaign_id}'
			GROUP BY `customer_id`, `campaign_id`
		) AS `cc` ON `cc`.`customer_ref`=`customer_id`";
	}
	$useJoinRelation = ($useJoinShare || $useJoinCampaign) ? true : false;
	$_tp = Input::get('tp', "chart");
	$cond = "`is_trash`=0";	
	if($resource_id > 0){
		$cond.= " AND `resource_id`='{$resource_id}'";
	}
	if($campaign_id > 0){
		if($useJoinCampaign){
			$cond.= " AND (`cc`.`rel_campaign_id`='{$campaign_id}' OR `list_campaign_id` like '%|{$campaign_id}|%')";
		} else {
			$cond.= " AND ".$clsCustomer->sqlCondHasCampaign($campaign_id);
		}
	}
	if($admin_id > 0){
		if($useJoinShare){
			$cond.= " AND (`admin_id`<>'{$admin_id}' AND (`cs`.`share_admin_id`='{$admin_id}' OR `list_share_id` like '%|{$admin_id}|%'))";
		} else {
			$cond.= " AND (`admin_id`<>'{$admin_id}' AND ".$clsCustomer->sqlCondHasShare($admin_id).")";
		}
	}else{
		if($useJoinShare){
			$cond.= " AND (`admin_id`='{$profile_id}' OR `user_id`='{$profile_id}' OR `cs`.`share_admin_id`='{$profile_id}' OR `list_share_id` like '%|{$profile_id}|%')";
		} else {
			$cond.= " AND (`admin_id`='{$profile_id}' OR `user_id`='{$profile_id}' 
					OR ".$clsCustomer->sqlCondHasShare($profile_id).")";
		}
	}
	if($group_id > 0){
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array(); 
		if(!empty($list_profile_arrs)){
			$cond.= " AND (`admin_id` in (".implode(',', $list_profile_arrs)."))";
		}
	}
//	echo $cond;die;
	
	$list_ranges = array();
	if($month == 0){
		$f = '%m/%Y';
		$format_time = '%Y';
		if($year == date("Y")) {
			if(date('n') >= 5){
				$start_month = strtotime('first day of january this year 00:00:00');
				$to_month = time();
			} else {
				$to_month = time();
				$start_month = strtotime('-12 months', $to_month);
			}	
		}else{
			$start_month = strtotime(date(sprintf("%s-01-01 00:00:00",$year)));
			$to_month = strtotime(date(sprintf("%s-12-31 23:59:59",$year)));
		}
		
		for($i = $start_month; $i <= $to_month; $i = strtotime('+1 month', $i)){
			$list_ranges[] = date('m/Y', $i);
		}
		$time = $year;
	} else {
		$f = '%d/%m/%Y';
		$format_time = '%m/%Y';
		$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1; $i<= $number_day; $i++){
			$list_ranges[] = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
		}
		$time = $clsISO->parseNumber($month)."/".$year;
	}	
	if($useJoinRelation){
		$query = "SELECT 
			`reg_date`,
			`status_id`,
			FROM_UNIXTIME(`reg_date`,'{$f}') as `date_time`,
			FROM_UNIXTIME(`reg_date`,'{$format_time}') as `format_time`,
			COUNT(DISTINCT `customer_id`) AS `total_customer`
		FROM `{$clsCustomer->tbl}` {$joinSql}
		WHERE {$cond}
		GROUP BY FROM_UNIXTIME(`reg_date`,'{$f}'),`status_id`";
		$lstCustomer = $dbconn->GetAll($query);
	} else {
		$lstCustomer = $clsCustomer->getAll($cond . " GROUP BY FROM_UNIXTIME(`reg_date`,'{$f}'),`status_id`","`reg_date`,`status_id`,FROM_UNIXTIME(`reg_date`,'{$f}') as `date_time`,FROM_UNIXTIME(`reg_date`,'{$format_time}') as `format_time`, COUNT(`customer_id`) AS `total_customer`");
	}
	$arr_total_time = $arr_total_status = [];
	foreach ($lstCustomer as $key => $val) {
		if(!isset($arr_total_time[$val["date_time"]])) {
			$arr_total_time[$val["date_time"]] = (int)$val["total_customer"];
		}else{
			$arr_total_time[$val["date_time"]] += (int)$val["total_customer"];
		}
		if(!isset($arr_total_status[$val["format_time"]][$val["status_id"]])) {
			$arr_total_status[$val["format_time"]][$val["status_id"]] = (int)$val["total_customer"];
		}else{
			$arr_total_status[$val["format_time"]][$val["status_id"]] += (int)$val["total_customer"];
		}
	}
//	$clsISO->print_pre($arr_total_time);die;
	if($_tp == "chart") {		
		$data = $dataPoints = $dataTotalPoints = $barChartData = array();
		$barChartData['animationEnabled'] = true;
//		$data['axisY']['labelFormatter'] = 1;
		foreach($list_ranges as $date){
			$tmp = explode('/', $date);
			$end_day = cal_days_in_month(CAL_GREGORIAN, $tmp[0], $tmp[1]);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $tmp[0], $tmp[1]));
			$field = "{$clsCustomer->pkey},`totalgrand`";
	//		$dbconn->debug=true;
			$total_cus = (!empty($arr_total_time[$date])) ? $arr_total_time[$date] : 0;
			$dataPoints[] = array(
				'label'	=> sprintf('%s', $date),
				'y'	=> (int)$total_cus,
				'indexLabel' => $total_cus . " KH"
			);
		}		
	
		$data['type'] = 'spline';
		$data['indexLabel'] = '{y}';
		$data['title']['fontColor'] = 'rgb(159,34,58)';
		$data['toolTipContent'] = '{label}<br /> Tổng: {y} khách hàng';
		$data['indexLabelPlacement'] = 'inside';
		$data['indexLabelFontColor'] = '#36454F';
		$data['dataPoints'] = $dataPoints;
		$barChartData['data'] = $data;
		$html = '<div id="'.$uid.'" class="chartContainer p-0 mb-3" style="height:200px"></div>';
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html,
			'html_briefs' => $html_briefs,		
			'drawchart' => '1',
			'barChartData' => $barChartData
		)); die();
	}else{
		$arr_status_cached = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
		$html = "";
		$arr_total_status_time = !empty($arr_total_status[$time]) ? $arr_total_status[$time] : [];
		
		if(!empty($arr_status_cached)){ $ii = 1;
		   $html = '<div class="form-row row-cols-1 row-cols-md-2">';
			foreach($arr_status_cached as $key => $val){
				$property_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$total_customers = !empty($arr_total_status_time[$property_id]) ? $arr_total_status_time[$property_id] : 0;
				$html.= '<div class="col mb-2"><div class="p-2 d-flex justify-content-between fs-16 align-items-center" style="background:'.$val['bgcolor'].';color:#FFF">
					<span class="label fs-6">'.$val['title'].'</span>
					<span class="value">'.$total_customers.' </span>
				</div></div>';
				++$ii;
			}
		   $html .= '</div>';
		}
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html,
		)); die();
	}
	
}
function default_load_pop_action(){
	global $smarty,$core,$profile_id, $clsISO, $_LANG_ID;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$html_req = "";
	if($clsISO->_DEV()){
		$html = '<form class="p-2">
			<div class="form-group mb-2">
				<label class="form-label mb-1 text-nowrap">Tình trạng</label>
				<select name="status_id" class="form-control upd_field form-select">
					<option value="0">Tình trạng</option>
					'.$clsProperty->getSelectByProperty('CUSTOMER_STATUS', 0).'
				</select>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Loại hình</label>
					<select name="blocktype_id" class="form-control upd_field form-select">
						<option value="0">Loại hình</option>
						'.$clsProperty->getSelectByProperty('_BLOCK_TYPE', 0).'
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Nguồn khách</label>
					<select name="resource_id" class="form-control upd_field form-select">
						<option value="0">Nguồn khách</option>
						'.$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0).'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Người quản lý</label>
				<div class="clearfix"></div>
				<select class="iso-selectizeNotSearch upd_field" name="admin_id" data-width="100%" data-placeholder="Người quản lý" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" data-width="100%" onChange="$Core.crm.loadAmountRequest(this,event)" toId="amount_request" ></select>
			</div>
			<div class="mt-2 d-none" id="amount_request"></div>
			<hr class="my-2" />
			<div class="alert alert-warning fs-12">
				<u>Lưu ý</u>: Khi nhấp áp dụng sẽ cập nhật những khách hàng đã chọn với field bên trên!
			</div>
			<button type="button" onClick="$Core.crm.do_action(this, event)" class="btn btn-block btn-primary">
				<i class="bx bx-check"></i>
				<span>Áp dụng</span>
			</button>
		</form>';
	}else{
		$html = '<form class="p-2">
			<div class="form-group mb-2">
				<label class="form-label mb-1 text-nowrap">Tình trạng</label>
				<select name="status_id" class="form-control upd_field form-select">
					<option value="0">Tình trạng</option>
					'.$clsProperty->getSelectByProperty('CUSTOMER_STATUS', 0).'
				</select>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Loại hình</label>
					<select name="blocktype_id" class="form-control upd_field form-select">
						<option value="0">Loại hình</option>
						'.$clsProperty->getSelectByProperty('_BLOCK_TYPE', 0).'
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Nguồn khách</label>
					<select name="resource_id" class="form-control upd_field form-select">
						<option value="0">Nguồn khách</option>
						'.$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0).'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Người quản lý</label>
				<div class="clearfix"></div>
				<select class="iso-selectizeNotSearch upd_field" name="admin_id" data-width="100%" data-placeholder="Người quản lý" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" data-width="100%"></select>
			</div>
			<hr class="my-2" />
			<div class="alert alert-warning fs-12">
				<u>Lưu ý</u>: Khi nhấp áp dụng sẽ cập nhật những khách hàng đã chọn với field bên trên!
			</div>
			<button type="button" onClick="$Core.crm.do_action(this, event)" class="btn btn-block btn-primary">
				<i class="bx bx-check"></i>
				<span>Áp dụng</span>
			</button>
		</form>';
	}
	
	// Return
	echo $html; die();
}
function default_do_action(){
	global $smarty, $core, $profile_id, $oneProfile, $dbconn, $clsISO, $_LANG_ID,$clsConfiguration;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsRequestCus = new RequestCus();
	###
	$msg = "_error";
	$list_ids = Input::post('list_ids');
	$update_field = Input::post('update_field');
	#request buy
	$is_amount_paid = (int)Input::post('is_amount_paid',0);
	$request_id = (int)Input::post('request_id',0);
	unset($update_field["request_id"]);
	$total_remaining = $amount_paid = 0;
	if(!empty($is_amount_paid) && !empty($request_id)) {
		$oneRequestCus = $clsRequestCus->getOne($request_id);
		if(!empty($oneRequestCus)) {
			$more_request = $clsISO->to_array_json($oneRequestCus["more_information"]);
			$amount = $oneRequestCus["amount"];
			$total_paid = !empty($more_request["total_paid"]) ? (int)$more_request["total_paid"] : 0;
			$total_remaining = $amount - $total_paid;
		}	
	}
	if(!empty($list_ids) && !empty($update_field)){
		if(@array_key_exists("admin_id", $update_field) && !empty($update_field["admin_id"])){
			$group_customer_sale = $clsConfiguration->getValue('group_customer_sale');
			$group_customer_sale = !empty($group_customer_sale) ? $clsISO->to_array_json($group_customer_sale) : [];
			$group_customer_sale[$profile_id][$clsISO->getUniqid()] = [
				'user_id'	=>	$profile_id,
				'admin_id'	=>	$update_field["admin_id"],
				"total"		=>	count($list_ids),
				'list_ids'	=>	$list_ids,
				'time'		=>	time()
			];
			$clsConfiguration->updateValue('group_customer_sale', json_encode($group_customer_sale, JSON_UNESCAPED_UNICODE));
		}
		$customer_insert = [];
		foreach($list_ids as $customer_id){
			$field = "`admin_id`,`user_id`,`name`,`phone`,`begin_need`,`status_id`,`resource_id`,`blocktype_id`,`list_share_id`,`more_information`";
			$oCustomer = $clsCustomer->getOne($customer_id, $field);
			$more_information = $oCustomer['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			##
			$s_field = 'admin_id';
			if(@array_key_exists($s_field, $update_field) && !empty($update_field[$s_field])){
				$admin_id = (int) $update_field[$s_field]; // Admin chuyển tới
				if($clsCustomer->isRootProfile()){
					$list_share_id = array_merge(_PROFILE_SUPPER_ID, array(_PROFILE_TAT_ID));
				} else {
					$list_share_id = $oCustomer['list_share_id'];
				}
				$list_share_arrs = !empty($list_share_id) 
					? $clsISO->getArrayByTextSlash($list_share_id) : array();
				$action_logs = $core->get_field($more_information, "action_logs", []);
				$arr_profile = $adminProfile = $adminProfileOld = array();
				if($profile_id == $oCustomer[$s_field]){ // Mình chuyển KH của mình
					$adminProfileOld = $oneProfile;
				} else { // Mình chuyển KH của người khác
					$arr_profile[] = $oCustomer[$s_field];
				}
				$arr_profile[] = $admin_id;
				$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $arr_profile).")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						if($val[$clsProfile->pkey] == $admin_id){
							$adminProfile = $val;
						} else if($val[$clsProfile->pkey] == $oCustomer[$s_field]){
							$adminProfileOld = $val;
						}
					}
					unset($tmp);
				}
				$customer_insert[] = [
					"customer_name"	=>	$oCustomer["name"],
					"phone"	=>	$oCustomer["phone"],
					"begin_need"	=>	$oCustomer["begin_need"],
				];
				$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($oCustomer[$s_field], $adminProfileOld), $clsProfile->getFullName($admin_id, $adminProfile));
				$action_logs[$clsISO->getUniqid()] = array(
					'content' => $content,
					'user_id' => $profile_id,
					'reg_date' => time()
				);
				$more_information['action_logs'] = $action_logs;	
				if(!in_array($oCustomer[$s_field], $list_share_arrs)){
					$list_share_arrs[] = $oCustomer[$s_field];
				}
				$use_globe = ($oCustomer['user_id'] == $admin_id) ? 0 : 1;
				$update_field['use_globe'] = $use_globe;
				$update_field['list_share_id'] = $clsISO->makeSlashListFromArray($list_share_arrs);
				
				#request buy
				if($total_remaining > 0) {
					$more_information["is_customer_buy"] = 1;
				}
			} else {
				$arr_property = $content_logs = array();
				$is_status = $is_blocktype = $is_resource = 0;
				if(isset($update_field['status_id']) && (int) $update_field['status_id'] > 0 
					&& (int) $update_field['status_id'] != $oCustomer['status_id']){
					$is_status = 1;
					$arr_property[] = $update_field['status_id'];
				} 
				if(isset($update_field['blocktype_id']) && (int) $update_field['blocktype_id'] > 0 
					&& (int) $update_field['blocktype_id'] != $oCustomer['blocktype_id']){
					$is_blocktype = 1;
					$arr_property[] = $update_field['blocktype_id'];
				}
				if(isset($update_field['resource_id']) && (int) $update_field['resource_id'] > 0 
					&& (int) $update_field['resource_id'] != $oCustomer['resource_id']){
					$is_resource = 1;
					$arr_property[] = $update_field['resource_id'];
				}
				if(!empty($arr_property)){
					$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_property).")", "{$clsProperty->pkey},`title`");
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if($is_status == 1 && $val[$clsProperty->pkey] == (int) $update_field['status_id']){
								$content_logs[] = sprintf('Tình trạng: %s', $val['title']);
							} else if($is_blocktype == 1 && $val[$clsProperty->pkey] == (int) $update_field['blocktype_id']){
								$content_logs[] = sprintf('Loại hình: %s', $val['title']);
							} else if($is_resource == 1 && $val[$clsProperty->pkey] == (int) $update_field['resource_id']){
								$content_logs[] = sprintf('Nguồn khách: %s', $val['title']);
							}
						}
					}
					$content = sprintf('<strong>%s</strong> đã thay đổi <strong>%s</strong>', 
						$clsProfile->getFullName($profile_id, $oneProfile), implode(',', $content_logs));
					$action_logs[$clsISO->getUniqid()] = array(
						'content' => $content,
						'user_id' => $profile_id,
						'reg_date' => time()
					);
					$more_information['action_logs'] = $action_logs;
				}
			}
			$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
			if($clsCustomer->updateOne($customer_id, $update_field)){
				$msg = "_success";
				if(@array_key_exists($s_field, $update_field)){
					$admin_id = $update_field[$s_field];
					if($clsCustomer->isRootProfile()){
						/** Lưu lại giao cho ai */
						$clsCustomerSales->insert(array(
							$clsCustomerSales->pkey => $clsCustomerSales->getMaxId(),
							'customer_id' => $customer_id,
							'admin_id' => $admin_id,
							'assign_date' => time(),
							'user_id' => $profile_id
						));
					}
					/** Push notification */
					#request buy
					if($total_remaining > 0) {
						--$total_remaining;
						++$amount_paid;
						$titleNoty = sprintf('<strong>%s</strong> đã trả khách hàng <strong>%s</strong> yêu cầu mua dự án <strong>%s</strong>', 
							$clsProfile->getFullName($profile_id, $oneProfile), 
							$clsCustomer->getName($customer_id, $oCustomer),
							$oneRequestCus["project_name"]
						);
					}else{
						$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
							$clsProfile->getFullName($profile_id, $oneProfile), 
							$clsCustomer->getName($customer_id, $oCustomer)
						);
					}
					
					$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(),"|".$admin_id."|");
					$subscribers = array();
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id`='{$admin_id}' and `token`<>''", "token");		
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if(!in_array($val['token'], $subscribers)){
								$subscribers[] = $val['token'];
							}
						}
						$clsNotify->send_subscriber_notification(array(
							'title' => "CRM - Khách hàng mới",
							'message' => strip_tags($titleNoty),
							'url' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
						), $subscribers);
						#thong bao app
						$clsNotification = new Notification();
						$params = [
							'title' => "CRM - Khách hàng mới",
							'body' => strip_tags($titleNoty),
							'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
						];
						$clsNotification->doPushMessagingUser($params,[$admin_id]);
					}
				}
			}
		}
		// Send Zalo
		$s_field = 'admin_id';
		if(@array_key_exists($s_field, $update_field) && !empty($update_field[$s_field])){
			$total_customers = count($list_ids); // Tổng khách hàng
			$admin_id = $update_field[$s_field]; // Người phụ trách
			$_oProfile = $clsProfile->getOne($admin_id);
			$zaloId = $clsProfile->getZaloId($admin_id, $_oProfile);
			if(!empty($zaloId)){
				$clsZalo = new Zalo();
				#request buy
				if($amount_paid > 0) {					
					//update
					$total_paid = !empty($more_request["total_paid"]) ? (int)$more_request["total_paid"] : 0;
					$total_paid = $total_paid + $amount_paid;
					$more_request["total_paid"] = $total_paid;
					$status = ($total_paid < $oneRequestCus["amount"]) ? 2 : 1;
					$clsRequestCus->updateOne($request_id,["status"=>$status,"more_information" => json_encode($more_request, JSON_UNESCAPED_UNICODE)]);
					
					$message = sprintf("Xin chào {color:#C00000}%s{/color}", $clsProfile->getFullName($admin_id, $_oProfile));
					$message.= "\n";
					$message.= sprintf("[%s %s] đã trả +%s khách hàng tiềm năng cho yêu cầu mua dự án **%s**", 
						$oneProfile['role_name'], $clsProfile->getFullName($profile_id, $oneProfile), $total_customers,$oneRequestCus["project_name"]);
					$message.= "\n";
					$message.= "Hãy truy cập CRM/Quản lý khách hàng (".DOMAIN_URL."/crm/) để bắt đầu chăm sóc khách hàng!";
					$clsZalo->sendMsgSchedule($zaloId, $_oProfile['phone'], $message);
				}else{
					$clsZalo->sendNotifyCRMZalo($admin_id, $_oProfile, $total_customers, $customer_insert);
				}
				
				
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_open_customer(){
	global $smarty,$core,$profile_id, $clsISO, $_LANG_ID;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsCountry = new Country();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	// $clsBusinessCampaign = new BusinessCampaign();
	$field = "{$clsProperty->pkey},title,image";
	$list_activity = $clsProperty->getAllCache("`is_trash`=0 and `parent_id`='0' 
	and `property_type`='FOLLOWUP_TYPE' order by `order_no` ASC", $field);
	$smarty->assign('list_activity', $list_activity);
	$rollback = (int) Input::post('rollback',1);
	$customer_id = Input::post('customer_id');
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$list_share_id = $oneCustomer['list_share_id'];
	$list_campaign_id  = $oneCustomer['list_campaign_id'];
	$more_information = $oneCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();	
	$arr_share_ids = $clsCustomer->getShareIds($customer_id, $oneCustomer);
	$list_campaign_id = $clsCustomer->getCampaignIds($customer_id, $oneCustomer);
	$oneCustomer['list_campaign_id'] = $list_campaign_id;
	$list_type_id = $oneCustomer['list_type_id'];
	$list_need_id = $oneCustomer['list_need_id'];
	$list_stock_id = $oneCustomer['list_stock_id'];
	$list_purpose_id = $oneCustomer['list_purpose_id'];
	$list_bedroom_id = $oneCustomer['list_bedroom_id'];
	$list_block_id = $oneCustomer['list_block_id'];
	if(!empty($list_need_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_need_id);
		$oneCustomer['list_need_id'] = $tmp;
	}
	if(!empty($list_purpose_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_purpose_id);
		$oneCustomer['list_purpose_id'] = $tmp;
	}
	if(!empty($list_stock_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_stock_id);
		$oneCustomer['list_stock_id'] = $tmp;
	}
	if(!empty($list_type_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_type_id);
		$oneCustomer['list_type_id'] = $tmp;
	}
	if(!empty($list_bedroom_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_bedroom_id);
		$oneCustomer['list_bedroom_id'] = $tmp;
	}
	if(!empty($list_block_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_block_id);
		$oneCustomer['list_block_id'] = $tmp;
	}
	$country_id = $onePotential['country_id'];
	$props = 'customer_id="'.$customer_id.'" disp="item"';
	/** Permission */
	$permiss = $clsISO->checkPermission('full_permissions_crm') ? 1 : 0;
	if(!$permiss) $permiss = (in_array($profile_id, array($oneCustomer['user_id'],$oneCustomer['admin_id']))) ? 1 : 0;
	/** End Permission */
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('oneCustomer', $oneCustomer);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsCampaign', $clsCampaign);
	$smarty->assign('clsSetting', $clsSetting);
	###
	$permiss_action = $permiss_edit = $permiss_notes = 0;
	if($oneCustomer['admin_id']==$profile_id || $clsCustomer->isFullPermiss()){
		$permiss_action = $permiss_edit = 1;
	}
	if(@in_array($profile_id, $arr_share_ids) || $clsCustomer->isFullPermiss()){
		$permiss_notes = 1;
	}
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_notes', $permiss_notes);
	$smarty->assign('permiss_action', $permiss_action);
	#
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.customer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_edit_inline_field(){
	//ini_set('display_errors',1);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$oneProfile;
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerHistory = new CustomerHistory();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	$clsProfile = new Profile();
	$clsStock = new Stock();
	###
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action','_open');
	if($p_action=='_save'){
		$p_value = Input::post('p_value');
		if(in_array($p_field, array('twitter','facebook','tiktok','linkedin','instagram','finance'))){
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			if($p_field == 'twitter') $text_field = "Twitter";
			if($p_field == 'facebook') $text_field = "Facebook";
			if($p_field == 'tiktok') $text_field = "Tiktok";
			if($p_field == 'linkedin') $text_field = "Linkedin";
			if($p_field == 'instagram') $text_field = "Instagram";
			if($p_field == 'finance') $text_field = "Tài chính";
			$content = sprintf('<strong>%s</strong> đã cập nhật <strong>%s</strong> thành <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $text_field, $p_value);
			// $clsISO->print_pre($content); die();
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information[$p_field] = $p_value;
			$more_information['action_logs'] = $action_logs;
			$clsCustomer->updateOne($p_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if($p_field=='list_block_id'){
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			#
			$text_value_field = $clsSetting->getTitleArray($p_value);
			$content = sprintf('<strong>%s</strong> đã cập nhật <strong>Dự án/Phân khu</strong> thành <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $text_value_field);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			$p_value_arr = $clsCustomer->normalizeIdArray($p_value);
			$p_value = $clsISO->makeSlashListFromArray($p_value_arr);
			$clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time(),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			if($p_field == 'list_campaign_id'){
				$clsCustomer->syncCampaignIds($p_id, $p_value_arr, $profile_id, false);
			}
		} else if(in_array($p_field, array(
			'list_purpose_id',
			'list_need_id',
			'list_stock_id',
			'list_type_id',
			'list_bedroom_id',
			'list_campaign_id'
		))){
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			if($p_field == 'list_purpose_id') {
				$text_field = "Mục đích";
				$text_value_field = $clsProperty->getTitleArray($p_value);
			} else if($p_field == 'list_need_id') {
				$text_field = "Nhu cầu";
				$text_value_field = $clsProperty->getTitleArray($p_value);
			} else if($p_field == 'list_stock_id') {
				$text_field = "Căn hộ";
				$text_value_field = $clsStock->getCodeArray($p_value);
			} else if($p_field == 'list_bedroom_id') {
				$text_field = "Loại căn hộ";
				$text_value_field = $clsProperty->getTitleArray($p_value);
			}  else if($p_field == 'list_campaign_id'){
				$text_field = "Chiến dịch";
				$text_value_field = $clsCampaign->getTitleArray($p_value);
			} 
			$content = sprintf('<strong>%s</strong> đã cập nhật <strong>%s</strong> thành <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $text_field, $text_value_field);
			// $clsISO->print_pre($content); die();
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			$p_value = $clsISO->makeSlashListFromArray($p_value);
			$clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time(),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if($p_field=='birthday'){
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			##
			$content = sprintf('<strong>%s</strong> đã cập nhật <strong>Ngày sinh</strong> thành <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $p_value);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$p_value = !empty($p_value) ? $clsISO->convertTextToTime($p_value) : 0;
			$clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if($p_field=='admin_id'){
			$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},`name`,`user_id`,`status_id`,`more_information`,`list_share_id`");
			$admin_old_id = $oCustomer[$p_field];
			$list_share_id = $oCustomer['list_share_id'];
			$more_information = $oCustomer['more_information']; 
			#
			$list_share_arrs = $clsISO->getArrayByTextSlash($list_share_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			#
			$arr_profile = $adminProfile = $adminProfile_Old = array();
			if($profile_id == $admin_old_id){ // Mình chuyển KH của mình
				$adminProfile_Old = $oneProfile;
			} else { // Mình chuyển KH của người khác
				$arr_profile[] = $admin_old_id;
			}
			$arr_profile[] = $p_value;
			$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $arr_profile).")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if($val[$clsProfile->pkey] == (int) $p_value){
						$adminProfile = $val;
					} else if($val[$clsProfile->pkey] == $admin_id){
						$adminProfile_Old = $val;
					}
				}
				unset($tmp);
			}
			$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsProfile->getFullName($admin_id, $adminProfile_Old), 
				$clsProfile->getFullName($p_value, $adminProfile)
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			##
			$list_share_arrs[] = $admin_old_id;
			$list_share_arrs = $clsCustomer->normalizeIdArray($list_share_arrs);
			$use_globe = ($oCustomer['user_id'] == $p_value) ? 0 : 1;
			if($clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time(),
				'use_globe' => $use_globe,
				'list_share_id' => $clsISO->makeSlashListFromArray($list_share_arrs),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$clsCustomer->syncShareIds($p_id, $list_share_arrs, $profile_id, false);
				/** Push notification */
				$clsNotify = new Notify();
				$clsFcmToken = new FcmToken();
				$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsCustomer->getName($p_id, $oCustomer)
				);
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey, $p_id, $titleNoty, time(),"|".$p_value."|");
				##
				$subscribers = array();
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id`='{$p_value}' and `token`<>''", "token");	
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						if(!in_array($val['token'], $subscribers)){
							$subscribers[] = $val['token'];
						}
					}
					$clsNotify->send_subscriber_notification(array(
						'title' => "CRM - Khách hàng mới",
						'message' => strip_tags($titleNoty),
						'url' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $p_id)
					), $subscribers);
				}
				/** Send Zalo */
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "CRM - Khách hàng mới",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $p_id)
				];
				$clsNotification->doPushMessagingUser($params,[$p_value]);
			}
		} else if($p_field=='status_id'){
			$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},`status_id`,`more_information`");
			$status_id = (int) $oCustomer['status_id']; 
			$more_information = $oCustomer['more_information']; 
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			##
			$arr_property = $content_logs = array();
			$arr_property[] = $p_value;
			$arr_property[] = $status_id;
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(",", $arr_property).")", "{$clsProperty->pkey},title");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if($val[$clsProperty->pkey] == $p_value){
						$content_logs['to'] = $val['title'];
					} else if($val[$clsProperty->pkey] == $status_id){
						$content_logs['from'] = $val['title'];
					}
				}
			}
			$content = sprintf('<strong>%s</strong> đã cập nhật tình trạng từ <strong>%s</strong> tới <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $content_logs['from'], $content_logs['to']);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			if($clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$clsCustomerHistory->insert(array(
					'customer_id' => $p_id,
					'from_status_id' => $status_id,
					'to_status_id' => $p_value,
					'staff_id' => $profile_id,
					'action_date' => time()
				));
			}
		} else {
			$more = array();
			if(in_array($p_field, ['name','email','address'])){
				if($p_field == 'name') $text_field = "Họ và tên";
				if($p_field == 'email') $text_field = "E-mail";
				if($p_field == 'phone') $text_field = "Điện thoại";
				if($p_field == 'address') $text_field = "Địa chỉ";
				if($p_field == 'begin_need') $text_field = "Nhu cầu ban đâu";
				$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},`more_information`");
				$more_information = $oCustomer['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$action_logs = $core->get_field($more_information, "action_logs", []);
				###
				$content = sprintf('<strong>%s</strong> đã cập nhật <strong>%s</strong> từ <strong>%s</strong> tới <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $text_field, !empty($oCustomer[$p_field]) ? $oCustomer[$p_field] : "N/A", $p_value);
				$action_logs[$clsISO->getUniqid()] = array(
					'content' => $content,
					'user_id' => $profile_id,
					'reg_date' => time()
				);
				$more_information['action_logs'] = $action_logs;
				$more = array('more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE));
			}
			$clsCustomer->updateOne($p_id, array_merge($more, array(
				$p_field => $p_value,
				'upd_date' => time()
			)));
		}
	}
	if(in_array($p_field, array('name','email','phone','address','CCID'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.='<div class="metadata-row-editable-triggerArea">'.$oCustomer[$p_field].'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input name="'.$p_field.'" class="form-control form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" value="'.$oCustomer[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />';
		}
	} else if(in_array($p_field, array('admin_id'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		$p_value = $oCustomer[$p_field];
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.='<div class="metadata-row-editable-triggerArea">
				'.$clsProfile->getIndentity($p_value, false).'
			</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<select name="'.$p_field.'" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" class="iso-selectizeNotSearch w-px-200 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'">
				<option value="'.$p_value.'" selected="selected">
					'.$clsProfile->getIndentity($p_value, false).'
				</option>
			</select>';
		}
	} else if(in_array($p_field, array('birthday'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_value = $oCustomer[$p_field];
			$p_date = !empty($p_value) ? $clsISO->convertTimeToText($p_value) : "";
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_date.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$p_value = $oCustomer[$p_field];
			$p_date = !empty($p_value) ? $clsISO->convertTimeToText($p_value) : "";
			$html_input = '<input name="'.$p_field.'" class="form-control datepicker form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yy" autocomplete="off" value="'.$p_date.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />
			<style type="text/css">.ui-datepicker{ z-index:9999 !important}</style>';
		}
	} else if($p_field=='begin_need'){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.='<div class="metadata-row-editable-triggerArea">'.$oCustomer[$p_field].'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<textarea class="form-control form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />'.$oCustomer[$p_field].'</textarea>';
		}
	} else if(in_array($p_field, array('facebook', 'tiktok', 'twitter', 'linkedin', 'instagram'))){
		$more_information = $clsCustomer->getOneField('more_information', $p_id);
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= $more_information[$p_field];
			$html.='<div class="metadata-row-editable-triggerArea">'.$oCustomer[$p_field].'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input class="form-control form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_customer_field_'.$p_field.'_'.$p_id.'" />';
		}
	} else if(in_array($p_field, array('status_id','finance_id','resource_id','blocktype_id'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			if(isset($oCustomer[$p_field]) && (int) $oCustomer[$p_field] > 0){
				$p_text = $clsProperty->getTitle($oCustomer[$p_field]);
			} else {
				$p_text = "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$label = 'Tình trạng';
			$property_type = 'CUSTOMER_STATUS';
			if($p_field=='type_id'){
				$label = 'Loại';
				$property_type = 'CUSTOMER_TYPE';
			} else if($p_field=='finance_id'){
				$label = 'Tài chính';
				$property_type = 'FINANCE';
			} else if($p_field=='resource_id'){
				$label = 'Nguồn gốc';
				$property_type = '_CUSTOMER_RESOURCES';
			} else if($p_field=='blocktype_id'){
				$label = 'Loại hình';
				$property_type = '_BLOCK_TYPE';
			}
			$html_input = '<select class="form-control form-select form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />
				'.$clsISO->getSelectByPropertyTypeTitle($property_type,$oCustomer['status_id'],$label).'
			</select>';
		}
	} else if($p_field == 'list_block_id'){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(isset($oCustomer[$p_field]) && !empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text = $clsSetting->getTitleArray($p_array);
			} else {
				$p_text.= "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_options = "";
			$p_data = $oCustomer[$p_field];
			$p_array = $clsISO->getArrayByTextSlash($p_data);
			$tmp = $clsSetting->getCacheItems('_PROJECT');
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$html_options.= '<option'.(in_array($val[$clsSetting->pkey],$p_array) ? ' selected="selected"' : '').' 
						value="'.$val[$clsSetting->pkey].'">'.$val['title'].'</option>';
				}
				unset($tmp);
			}
			$html_input = '<select data-placeholder="'.$label.'" data-allow-clear="true" multiple="multiple" class="form-control iso-select2 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]" />
				'.$html_options.'
			</select>';
		}
	} else if($p_field == 'list_bedroom_id'){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(isset($oCustomer[$p_field]) && !empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text.= $clsProperty->getTitleArray($p_array);
			} else {
				$p_text.= "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_options = "";
			$p_data = $clsCustomer->getOneField($p_field, $p_id);
			$p_array = $clsISO->getArrayByTextSlash($p_data);
			$tmp = $clsProperty->getCacheItems('_BEDROOM');
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$html_options.= '<option'.(in_array($val[$clsProperty->pkey],$p_array) ? ' selected="selected"' : '').' 
						value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
				}
				unset($tmp);
			}
			$tmp = $clsProperty->getCacheItems('_TYPE_VILLA');
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$html_options.= '<option'.(in_array($val[$clsProperty->pkey],$p_array) ? ' selected="selected"' : '').' 
						value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
				}
				unset($tmp);
			}
			$html_input = '<select data-placeholder="'.$label.'" data-allow-clear="true" multiple="multiple" 
				class="form-control iso-select2 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]" />
				'.$html_options.'
			</select>';
		}
	} else if(in_array($p_field, array('list_need_id','list_purpose_id','list_type_id'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(isset($oCustomer[$p_field]) && !empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text.= $clsProperty->getTitleArray($p_array);
			} else {
				$p_text.= "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			if($p_field=='list_purpose_id'){
				$label = 'Mục đích';
				$property_type = 'PURPOSE';
			} else if($p_field=='list_need_id'){
				$label = 'Nhu cầu';
				$property_type = 'NEED';
			} else if($p_field=='list_type_id'){
				$label = 'Loại khách hàng';
				$property_type = 'CUSTOMER_TYPE';
			} 
			$p_data = $clsCustomer->getOneField($p_field, $p_id);
			$p_array = $clsISO->getArrayByTextSlash($p_data);
			$html_input = '<select data-placeholder="'.$label.'" data-allow-clear="true" multiple="multiple" class="form-control iso-select2 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]" />
				'.$clsProperty->getSelectByPropertyV2($property_type,$p_array,'Phòng ban').'
			</select>';
		}
	} else if($p_field == 'list_campaign_id'){
		$uid = $clsISO->getUniqid();
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(!empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text = $clsCampaign->getTitleArray($p_array);
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField"  onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$p_array = array();
			if(!empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
			}
			$html_input = '<div class="input-group align-items-center" style="width:calc(100% - 62px)">
				<div class="select2-container no-border-right" style="width:calc(100% - 40px)">
					<select multiple="multiple" id="slb_Campaign_'.$uid.'" data-width="100%" data-placeholder="Chọn chiến dịch" class="form-control iso-select2 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]">';
					$field = "{$clsCampaign->pkey},title";
					$list_campaigns = $clsCampaign->getAll("`campaign_type`='_campaign' 
						and `user_id`='{$profile_id}' order by `reg_date` DESC", $field);
					if(!empty($list_campaigns)){
						foreach($list_campaigns as $key => $val){
							$html_input.= '<option'.(in_array($val[$clsCampaign->pkey],$p_array)?' selected':'').' value="'.$val[$clsCampaign->pkey].'">'.$val['title'].'</option>';
						}
					}
					$html_input .= '</select>
				</div>
				<button type="button" toId="'.$uid.'" onClick="$Core.crm.open_campaign(this, event)" 
				class="btn btn-icon btn-outline-default">'.$clsISO->makeIcon('bx-plus').'</button>
			</div>';
		}
	} else if($p_field=='list_stock_id'){
		$oProfile = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(isset($oProfile[$p_field]) && !empty($oProfile[$p_field])){
				$p_data = $oProfile[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text.= $clsStock->getTitleArray($p_array);
			} else {
				$p_text.= "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$p_data = $clsCustomer->getOneField($p_field, $p_id);
			$p_array = $clsISO->getArrayByTextSlash($p_data);
			$html_input = '<select multiple="multiple" class="form-control iso-selectizeLiveSearch edit_customer_field_'.$p_field.'_'.$p_id.'" data-url="'.PCMS_URL.'/index.php?mod=home&act=load_stock_search" data-optgroup="false" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]">';
				if(!empty($p_array)){
					foreach($p_array as $stock_id){
						$html_input.= '<option value="'.$stock_id.'" selected="selected">
							'.$clsStock->getMsCode($stock_id).'</option>';
					}
				}
			$html_input .= '</select>';
		}
	}
	if($p_action=='_cancel' || $p_action=='_save'){
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container">
			'.$html_input.'
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.crm.save_edit_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.crm.cancel_edit_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_done_followup(){
	global $smarty,$adminid,$core,$clsISO;
	$clsFollowUp = new FollowUp();
	$customer_id = (int) Input::post('customer_id',0);
	$followup_id = (int) Input::post('followup_id',0);
	/** Update */
	$msg = '_error';
	if($clsFollowUp->updateOne($followup_id, array(
		'status_id'	=> _FOLLOWUP_STATUS_DONE_ID,
		'upd_date'	=> time()
	))){
		$msg = '_success';
	}
	// Return
	echo json_encode(array(
		"msg"	=> $msg
	));die;
}
function default_open_import(){
	global $core,$adminid,$clsISO,$profile_id,$oneProfile,$clsISO;
	$uid = $clsISO->getUniqid();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	###
	$html_campaigns = "";
	$cond = "`is_trash`=0";
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
	$field = "{$clsCampaign->pkey},`title`";
	$list_campaigns = $clsCampaign->getAll("{$cond} and `campaign_type`='_campaign' order by `reg_date` DESC", $field);
	
	if(!empty($list_campaigns)){
		foreach($list_campaigns as $key => $val){
			$html_campaigns.= sprintf('<option value="%s">%s</option>', $val[$clsCampaign->pkey], $val['title']);
		}
		unset($list_campaigns);
	}
	$html= '<div class="modal-dialog">
		<form method="post" action="" enctype="multipart/form-data" class="modal-content" id="frmIssue">
			<div class="modal-header"> 
				<h5 class="modal-title"><strong>Nhập khách hàng</strong></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="form" cellpadding="0" cellspacing="2" width="100%">
					<tr>
						<td class="fieldarea" colspan="2">
							<strong>Step 1: Download Spreadsheet</strong>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel" width="25%"></td>
						<td class="fieldarea">
							<a data-toggle="ripple" href="'.PCMS_URL.'/templates/Customer.xlsx" target="_blank" class="btn btn-outline-primary"><svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96" fill="#FFF" stroke-miterlimit="10" stroke-width="2">
								<path stroke="#979593" d="M67.1716,7H27c-1.1046,0-2,0.8954-2,2v78 c0,1.1046,0.8954,2,2,2h58c1.1046,0,2-0.8954,2-2V26.8284c0-0.5304-0.2107-1.0391-0.5858-1.4142L68.5858,7.5858 C68.2107,7.2107,67.702,7,67.1716,7z"/>
								<path fill="none" stroke="#979593" d="M67,7v18c0,1.1046,0.8954,2,2,2h18"/>
								<path fill="#C8C6C4" d="M51 61H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 60.5523 51.5523 61 51 61zM51 55H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 54.5523 51.5523 55 51 55zM51 49H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 48.5523 51.5523 49 51 49zM51 43H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 42.5523 51.5523 43 51 43zM51 67H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 66.5523 51.5523 67 51 67zM79 61H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 60.5523 79.5523 61 79 61zM79 67H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 66.5523 79.5523 67 79 67zM79 55H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 54.5523 79.5523 55 79 55zM79 49H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 48.5523 79.5523 49 79 49zM79 43H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 42.5523 79.5523 43 79 43zM65 61H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 60.5523 65.5523 61 65 61zM65 67H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 66.5523 65.5523 67 65 67zM65 55H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 54.5523 65.5523 55 65 55zM65 49H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 48.5523 65.5523 49 65 49zM65 43H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 42.5523 65.5523 43 65 43z"/>
								<path fill="#107C41" d="M12,74h32c2.2091,0,4-1.7909,4-4V38c0-2.2091-1.7909-4-4-4H12c-2.2091,0-4,1.7909-4,4v32 C8,72.2091,9.7909,74,12,74z"/><path d="M16.9492,66l7.8848-12.0337L17.6123,42h5.8115l3.9424,7.6486c0.3623,0.7252,0.6113,1.2668,0.7471,1.6236 h0.0508c0.2617-0.58,0.5332-1.1436,0.8164-1.69L33.1943,42h5.335l-7.4082,11.9L38.7168,66H33.041l-4.5537-8.4017 c-0.1924-0.3116-0.374-0.6858-0.5439-1.1215H27.876c-0.0791,0.2684-0.2549,0.631-0.5264,1.0878L22.6592,66H16.9492z"/>
							</svg> Tải mẫu file Import</a>
						</td>
					</tr>
					<tr><td class="fieldarea" colspan="2">
						<strong>Step 2: '.$core->get_Lang('AttachFile').'</strong></td>
					</tr>
					<tr>
						<td class="fieldlabel">Chọn file(.xls,.xlsx)</td>
						<td class="fieldarea" colspan="3">
							<div class="input-group" >
								<input type="hidden" name="file_id" value="" />
								<input type="file" name="fileimport" class="form-control fileimport" onChange="$Core.crm.config_column(this,event)" />
								<button type="button" onClick="$Core.crm.config_column(this,event)" tp="upload_file" 
									class="btn btn-icon btn-outline-default"><i class="bx bx-cog"></i></button>	
							</div>
							<small class="form-text">File Import excludes Create Date & Update Date</small>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Hoặc google sheet</td>
						<td class="fieldarea" colspan="3">
							<div class="input-group" >
								<input type="text" class="form-control" name="spreadsheetId" placeholder="Nhập URL Google Sheet" />
								<button type="button" onClick="$Core.crm.config_column(this,event)" tp="google_sheet" 
									class="btn btn-icon btn-outline-default"><i class="bx bx-cog"></i></button>	
							</div>
							<small class="form-text">Tên sheet cần Import phải có tên là <strong class="text-main">"Data"</strong></small>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Nguồn khách</td>
						<td class="fieldarea">
							<select name="resource_id" class="form-control form-select required">
								<option value="0">Nguồn gốc</option>
								'.$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', _CRM_RESOURCE_ADS_ID).'
							</select>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Chiến dịch <a onClick="$Core.crm.open_campaign(this, event)" 
						href="javascript:void(0);" toId="Import_'.$uid.'">+ Thêm<a></td>
						<td class="fieldarea">
							<select name="list_campaign_id[]" id="slb_Campaign_Import_'.$uid.'" data-placeholder="Chiến dịch" 
							multiple="true" class="form-control iso-select2" data-width="100%" data-allow-clear="true">
								<option value="0">Lựa chọn chiến dịch</option>
								'.$html_campaigns.'
							</select>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel"></td>
						<td class="fieldarea" colspan="3">
							<div class="d-flex gap-1 align-items-center">
								<label class="switch">
									<input type="checkbox" name="auto_create_followups" value="1" />
									<span class="slider round"></span>
								</label>
								<span>Tạo FU tự động</span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Người phụ trách</td>
						<td class="fieldarea">
							<select class="iso-selectizeNotSearch required" name="admin_id" data-placeholder="Người phụ trách" 
								data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff">
								<option value="'.$profile_id.'" selected="selected">
									'.$clsProfile->getIndentityV2($profile_id, $oneProfile, false).'
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Người liên quan</td>
						<td class="fieldarea">
							<select class="iso-selectizeNotSearch" name="list_share_id[]" data-placeholder="Người liên quan" 
								data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" multiple="true">
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" onClick="$Core.crm.do_import(this, event)">
					'.$core->get_Lang('Upload').'
				</button>
			</div>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));die();
}
function default_do_import_customer(){
	global $core,$profile_id,$oneProfile,$clsISO,$dbconn;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$clsCustomerHistory = new CustomerHistory();
	$clsFollowUp = new FollowUp();	
	$clsZalo = new Zalo();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');		
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	###
	$current_time = time();
	$totalInsert = $totalDuplicate = 0;
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		$file_id = Input::post('file_id');
		$spreadsheetId = Input::post('spreadsheetId');
		$resource_id = (int) Input::post('resource_id', 0);
		$admin_id = (int) Input::post('admin_id', $profile_id);
		$list_share_id  = Input::post('list_share_id',[]);
		$list_campaign_arrs = Input::post('list_campaign_id');
		$list_campaign_id = $clsISO->makeSlashListFromArray($list_campaign_arrs, ",", []);
		$auto_create_followups = (int) Input::post('auto_create_followups', 0);
		$general_logs = array();
		if($resource_id > 0) 
			$general_logs[] = sprintf('Nguồn gốc: %s', $clsProperty->getTitle($resource_id));
		if(!empty($list_share_id))
			$general_logs[] = sprintf('Người liên quan: %s', $clsProfile->getNameArray($list_share_id, ","));
		if(!empty($list_campaign_arrs)) 
			$general_logs[] = sprintf('Chiến dịch: %s', $clsCampaign->getTitleArray($list_campaign_arrs));
		
		$ad_field = "`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
		$adProfile = $clsProfile->getOne($admin_id, $ad_field);
		if(!empty($file_id) || !empty($spreadsheetId)){
			$columns = ["name","phone","email","address","status_id","begin_need"];
			$cachedColumnName = sprintf('column_%s.json', $profile_id);
			$cachedFileColumn = sprintf('%s/customer/%s', DIR_CACHE_JSON, $cachedColumnName);
			if(@file_exists($cachedFileColumn)){
				$columns = $decoder->decodeFile($cachedFileColumn);
			}
			if(!empty($spreadsheetId)){
				if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
					@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
					// $clsISO->print_pre($matches); die();
					$spreadsheetId = $matches[0];
				}
				#- Require library		
				require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
				/** Init Client */
				$client = new Google_Client();
				$client->setClientId(GOOGLE_CLIENT_ID);
				$client->setClientSecret(GOOGLE_CLIENT_SECRET);
				$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
				$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
				$service = new Google_Service_Sheets($client);
				// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
				// get all the rows of a sheet
				$range = 'Data'; // here we use the name of the Sheet to get all the rows
				$response = $service->spreadsheets_values->get($spreadsheetId, $range);
				$tblData = $response->getValues();
			} else {
				$tblData = [];
				$cachedName = sprintf('%s.json', $file_id);
				$cachedFile = DIR_CACHE_JSON.'/customer/'.$cachedName;
				if(@file_exists($cachedFile)){
					$tblData = $decoder->decodeFile($cachedFile);
					@unlink($cachedFile);
				}
			}
			if(!empty($tblData)){
				$arr_data_customer = array();
				$total_record = @count($tblData);
				if($total_record > 1){
					for($i=1; $i<$total_record; $i++){
						$arr_customer = [];
						foreach($columns as $i_col => $p_field){
							$arr_customer[$p_field] = $tblData[$i][$i_col];
						}
						$arr_data_customer[] = $arr_customer;
					}
				}
				$customer_insert = [];
				foreach($arr_data_customer as $key => $_oCus) {
					$name = $_oCus["name"];
					$email = !empty($_oCus["email"]) ? $_oCus["email"] : "";
					$phone = !empty($_oCus["phone"]) ? $clsCustomer->formatPhone($_oCus["phone"]) : "";
					$address = !empty($_oCus["address"]) ? $_oCus["address"] : "";
					$status = !empty($_oCus["status"]) ? $_oCus["status"] : "";
					$begin_need = !empty($_oCus["begin_need"]) ? $_oCus["begin_need"] : "";
					$action_date = !empty($_oCus["action_date"]) ? $_oCus["action_date"] : "";
					$customer_sales = !empty($_oCus["customer_sales"]) ? $_oCus["customer_sales"] : "";
					$customer_content = !empty($_oCus["customer_content"]) ? $_oCus["customer_content"] : "";
					$gender_name = !empty($_oCus["gender"]) ? $_oCus["gender"] : "";
					$birthday = !empty($_oCus["birthday"]) ? $_oCus["birthday"] : "";
					$agency_name = !empty($_oCus["agent_id"]) ? $_oCus["agent_id"] : "";
					$tiktok_link = !empty($_oCus["tiktok_link"]) ? $_oCus["tiktok_link"] : "";
					$facebook_link = !empty($_oCus["facebook_link"]) ? $_oCus["facebook_link"] : "";
					/** Khai báo logs */
					$content_logs = $more_information = $action_logs = array();
					if(!empty($name)) $content_logs[] = sprintf('Tên khách hàng: %s', $name);
					if(!empty($address)) $content_logs[] = sprintf('Địa chỉ: %s', $address);
					if(!empty($begin_need)) $content_logs[] = sprintf('Nhu cầu: %s', $begin_need);
					$content_logs = array_merge($content_logs, $general_logs);
					$action_logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(),
						'user_id' => $profile_id,
						'content' => sprintf('<strong>%s</strong> đã thêm mới khách hàng với %s', 
							$clsProfile->getFullName($profile_id, $oneProfile), implode(',', $content_logs))
					);
					if($admin_id != $profile_id){
						$list_share_id[] = $profile_id;
						$action_logs[$clsISO->getUniqid()] = array(
							'reg_date' => time(),
							'user_id' => $profile_id,
							'content' => sprintf('<strong>%s</strong> đã giao <strong>%s</strong> quản lý khách hàng', 
								$clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($admin_id, $adProfile))
						);
					}
					$agent_id = $gender_id = 0;
					if(!empty($agency_name)){
						$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_AGENCY' AND (`slug`='".$core->replaceSpace($agency_name)."' OR `property_code`='{$agency_name}')", $clsProperty->pkey);
						$agent_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					if(!empty($gender_name)){
						$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_GENDER' AND (`slug`='".$core->replaceSpace($gender_name)."' OR `property_code`='{$gender_name}')", $clsProperty->pkey);
						$gender_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					$more_information['agent_id'] = $agent_id;
					$more_information['gender_id'] = $gender_id;
					$more_information['tiktok'] = $tiktok_link;
					$more_information['facebook'] = $facebook_link;
					$more_information['action_logs'] = $action_logs;
					if(!empty($name) && !empty($phone)){
						$lstcheck = $clsCustomer->getByCond("`admin_id`='{$profile_id}' AND `phone`='{$phone} limit 0,1", $clsCustomer->pkey);
						if(!empty($lstcheck)){
							$totalDuplicate ++;
						}else{
							$customer_id = $clsCustomer->getMaxId();
							$list_share_id_slash = !empty($list_share_id) ? $clsISO->makeSlashListFromArray($list_share_id) : "";
							if($clsCustomer->insert(array(
								$clsCustomer->pkey => $customer_id,
								'name'	=> $name,
								'name_slug'	=> $core->replaceSpace($name),
								'status_id'	=> _CRM_STATUS_LEAD_ID,
								'resource_id' => $resource_id,
								'list_campaign_id' => $list_campaign_id,
								'list_share_id' => $list_share_id_slash,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'birthday' => $clsCustomer->dobToTimestamp($birthday),
								'email'	=> $email,
								'phone'	=> $phone,
								'address'  => $address,
								'admin_id' => $admin_id,
								'begin_need' => $begin_need,
								'user_id'	 => $profile_id,
								'user_id_update' => $profile_id,
								'reg_date'	=> $current_time,
								'upd_date'	=> $current_time,
							))){
								$clsCustomer->syncShareIds($customer_id, $list_share_id, $profile_id, false);
								$clsCustomer->syncCampaignIds($customer_id, $list_campaign_arrs, $profile_id, false);
								$totalInsert++;								
								$customer_insert[] = [
									"customer_name"	=>	$name,
									"phone"	=>	$phone,
									"begin_need"	=>	$begin_need,
								];
								$clsCustomerHistory->insert(array(
									'customer_id' => $customer_id,
									'from_status_id' => 0,
									'to_status_id' => $status_id,
									'staff_id' => $admin_id,
									'action_date' => $current_time
								));
								if(!empty($action_date) && !empty($customer_sales) && !empty($customer_content)){
									$tmp = $clsProfile->getByCond("`is_trash`=0 AND `full_name_slug`='".$core->replaceSpace($customer_sales)."'", $clsProfile->pkey);
									if(!empty($tmp)){
										$clsFollowUp->insert(array(
											'type_id' => _FOLLOWUP_CALL_ID,
											'status_id' => _FOLLOWUP_STATUS_DONE_ID,
											'customer_id' => $customer_id,
											'date_id' => $clsISO->convertTextToTime($action_date),
											'intro' => $customer_content,
											'admin_id' => $tmp[$clsProfile->pkey],
											'user_id' => $tmp[$clsProfile->pkey],
											'user_id_update' => $tmp[$clsProfile->pkey],
											'reg_date' => $current_time,
											'upd_date' => $current_time
										));
									}
								}
								if($auto_create_followups == 1){
									$timer = strtotime(date('d-m-Y'));
									$list_times = array(
										'1day' => strtotime('+1 day', $timer),
										'2day' => strtotime('+2 days', $timer),
										'5day' => strtotime('+5 days', $timer),
										'15day' => strtotime('+15 days', $timer),
										'30day' => strtotime('+30 days', $timer)
									);
									foreach($list_times as $key => $date_id){
										if($clsFollowUp->insert(array(
											'type_id' => _FOLLOWUP_CALL_ID,
											'status_id' => _FOLLOWUP_STATUS_PLAN_ID,
											'customer_id' => $customer_id,
											'date_id' => $date_id,
											'intro' => 'Call liên hệ lại khách',
											'admin_id' => $admin_id,
											'user_id' => $profile_id,
											'user_id_update' => $profile_id,
											'reg_date' => $current_time,
											'upd_date' => $current_time
										))){
											$titleNoty = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProperty->getTitle(_FOLLOWUP_CALL_ID) .": Call liên hệ lại khách", $clsCustomer->getName($customer_id), $clsISO->convertTimeToText($date_id, true));
											$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNoty, $date_id, '|'.$admin_id.'|');
										}
									}
								}
								if($admin_id != $profile_id){
									if($clsCustomer->isRootProfile()){
										$clsCustomerSales->insert(array(
											'customer_id' => $customer_id,
											'admin_id' => $admin_id,
											'assign_date' => time()
										));
									}
									$oCustomer = array('name' => $name);	
									$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
										$clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer));
									$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),"|".$admin_id."|");
									#thong bao app
									$clsNotification = new Notification();
									$params = [
										'title' => "CRM - Khách hàng mới",
										'body' => strip_tags($titleNoty),
										'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
									];
									$clsNotification->doPushMessagingUser($params,[$admin_id]);
								}
							}
						}
					}
				}
				if($totalInsert > 0 && $admin_id != $profile_id){
					$zaloId = $clsProfile->getZaloId($admin_id, $adProfile);
					if(!empty($zaloId)){
						$clsZalo->sendNotifyCRMZalo($admin_id, $adProfile, $totalInsert, $customer_insert);
					}
				}
			}
		}
	}
	// Return
	echo('0$$$'.$totalInsert.'$$$'.$totalDuplicate); die();
}
function default_manager_campaign(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsCampaign = new Campaign();
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_manager');
	$html = $core->build('_ajax.campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_campaigns(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerCampaign = new CustomerCampaign();
	$mapCountCampaign = array();
	$tmpCounts = $dbconn->GetAll("SELECT `campaign_id`, COUNT(DISTINCT `customer_id`) AS `total_customer`
		FROM `{$clsCustomerCampaign->tbl}`
		GROUP BY `campaign_id`");
	if(!empty($tmpCounts)){
		foreach($tmpCounts as $row){
			$mapCountCampaign[(int)$row['campaign_id']] = (int)$row['total_customer'];
		}
	}
	
	$html= '';
	$list_campaigns = $clsCampaign->getAll("`campaign_type`='_campaign'  ORDER BY `reg_date` DESC");
	if(!empty($list_campaigns)){ $ii = 1;
		foreach($list_campaigns as $campaign){
			$campaign_id = $campaign[$clsCampaign->pkey];
			$total_cus = isset($mapCountCampaign[$campaign_id]) ? (int)$mapCountCampaign[$campaign_id] : 0;
			$html.= '<tr>
				<td class="text-center">'.($ii).'</td>
				<td class="align-center">'.$campaign['title'].'</td>
				<td class="align-center text-center">'.$total_cus.'</td>
				<td class="text-center">
					<div class="dropdown">
						<button type="button" data-bs-toggle="dropdown" 
							class="btn btn-link btn-icon btn-sm dropdown-toggle hide-arrow rounded-pill text-muted">
							<i class=\'bx bx-dots-vertical-rounded\'></i>
						</button>
						<div class="dropdown-menu min-w-px-150">
							<a class="dropdown-item cursor-pointer" onClick="$Core.global.crm.open_campaign(this, event)" 
								campaign_id="'.$campaign_id.'"><i class="bx bx-pencil"></i> Sửa</a>
							<a class="dropdown-item cursor-pointer" onClick="$Core.global.crm.delete_campaign(this, event)" 
								campaign_id="'.$campaign_id.'"><i class="bx bx-trash"></i> Xóa</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item cursor-pointer" onClick="$Core.global.crm.merge_campaign(this, event)" 
								campaign_id="'.$campaign_id.'"><i class="bx bx-vector"></i> Gộp khách</a>	
						</ul>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		unset($list_campaigns);
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_campaign(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsSetting = new Setting();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	#
	$uid = $clsISO->getUniqid();
	$toId = Input::post('toId');
	$campaign_id = (int) Input::post('campaign_id', 0);
	#
	$field = "{$clsProject->pkey},`title`";
	$arr_projects = $clsProject->getAll("`is_trash`=0", $field);
	$smarty->assign('arr_projects', $arr_projects);
	#
	$action = "_add";
	$arr_blocks = array();
	$oneCampaign = array('title' => '');
	$campaign_config = array('project_mapping_id' => 0);
	$titlePage = 'Thêm mới chiến dịch';
	if($campaign_id > 0){
		$action = "_edit";
		$titlePage = 'Cập nhật chiến dịch';
		$oneCampaign = $clsCampaign->getOne($campaign_id);
		$campaign_config = $oneCampaign['campaign_config'];
		$campaign_config = $clsISO->to_array_json($campaign_config);
		$project_id = (int) $core->get_field($campaign_config, "project_id", 0);
		if($project_id > 0){
			$field = "{$clsProperty->pkey},`title`";
			$arr_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `for_id`='{$project_id}'", $field);
			// $clsISO->print_pre($arr_blocks); die();
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('toId', $toId);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('campaign_id', $campaign_id);
	$smarty->assign('arr_blocks', $arr_blocks);
	$smarty->assign('oneCampaign', $oneCampaign);
	$smarty->assign('campaign_config', $campaign_config);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	###
	$msg = "_error";
	$campaign_id = (int) Input::post('campaign_id', 0);
	$title = Input::post('title');
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$use_globe = (int) Input::post('use_globe', 0);
	if($campaign_id == 0){
		$campaign_id = $clsCampaign->getMaxId();
		$campaign_config = array();
		$campaign_config['project_id'] = $project_id;
		$campaign_config['block_id'] = $block_id;
		// $dbconn->debug = true;
		if($clsCampaign->insert(array(
			$clsCampaign->pkey => $campaign_id,
			'campaign_type' => '_campaign',
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'campaign_config' => json_encode($campaign_config, JSON_UNESCAPED_UNICODE),
			'use_globe' => $use_globe,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		$oneCampaign = $clsCampaign->getOne($campaign_id);
		$campaign_config = $oneCampaign['campaign_config'];
		$campaign_config = $clsISO->to_array_json($campaign_config);
		$campaign_config['project_id'] = $project_id;
		$campaign_config['block_id'] = $block_id;
		if($clsCampaign->updateOne($campaign_id, array(
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'campaign_config' => json_encode($campaign_config, JSON_UNESCAPED_UNICODE),
			'use_globe' => $use_globe,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'name' => $title,
		'campaign_id' => $campaign_id
	)); die();
}
function default_delete_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	#
	$msg = "_error";
	$campaign_id = (int) Input::post('campaign_id', 0);
	if($campaign_id > 0 && $clsCampaign->deleteOne($campaign_id)){
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		"msg" => $msg
	)); die();
}
function default_merge_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	#
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$campaign_id = (int) Input::post('campaign_id', 0);
	$oneCampaign = $clsCampaign->getOne($campaign_id);
	#
	$html_campaigns = "";
	$list_campaigns = $clsCampaign->getAll("{$clsCampaign->pkey}<>'{$campaign_id}' AND `campaign_type`='_campaign' ORDER BY `reg_date` DESC");
	if(!empty($list_campaigns)){ $ii = 1;
		foreach($list_campaigns as $campaign){
			$html_campaigns.= sprintf('<option value="%s">%s</option>', $campaign[$clsCampaign->pkey], $campaign['title']);
			++$ii;
		}
		unset($list_campaigns);
	}
	$smarty->assign('campaign_id', $campaign_id);
	$smarty->assign('oneCampaign', $oneCampaign);
	$smarty->assign('html_campaigns', $html_campaigns);
	// Return
	$smarty->assign('template_type', '_merge');
	$html = $core->build('_ajax.campaign.tpl');
	echo json_encode(array(
		"uid" => $uid,
		"html" => $html
	)); die();
}
function default_do_merge_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	#
	$msg = "_error";
	$merge_id = (int) Input::post('merge_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	#
	if($merge_id > 0 && $campaign_id > 0){
		$list_customers = $clsCustomer->getAll($clsCustomer->sqlCondHasCampaign($merge_id), "{$clsCustomer->pkey},`list_campaign_id`");
		// $clsISO->print_pre($list_customers); die();
		if(!empty($list_customers)){
			foreach($list_customers as $key => $val){
				$customer_id = $val[$clsCustomer->pkey];
				$list_campaign_id = $val['list_campaign_id'];
				$campaign_arrs = $clsISO->getArrayByTextSlash($list_campaign_id, ",", []);
				$index = array_search($merge_id, $campaign_arrs);
				unset($campaign_arrs[$index]);
				$campaign_arrs[] = $campaign_id;
				$list_campaign_id = $clsISO->makeSlashListFromArrayRoot($campaign_arrs);
				if($clsCustomer->updateOne($customer_id, array(
					'list_campaign_id' => $list_campaign_id
				))){ // 16170
					$clsCustomer->syncCampaignIds($customer_id, $campaign_arrs, $profile_id, false);
					$msg = "_success";
				}
			}
			unset($list_customers);
		}
	}
	// Return
	echo json_encode(array('msg' => $msg)); 
	die();
}
function default_open_help(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$SiteMsg_CRM_Help = $clsConfiguration->getValue('SiteMsg_CRM_Help');
	$html = '<div class="modal-dialog modal-ipad modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">Hướng dẫn sử dụng CRM</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="tinyContent">
					'.html_entity_decode($SiteMsg_CRM_Help).'
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'uid' => $clsISO->getUniqid()
	)); die();
}
function default_open_participant(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id', 0);
	$arr_share_ids = $clsCustomer->getShareIds($customer_id);
	#
	$html_user_participants = "";
	if(!empty($arr_share_ids)){
		foreach($arr_share_ids as $key => $val){
			$html_user_participants.= '<option value="'.$val.'" selected>'.$clsProfile->getFullName($val).'</option>';
		}
	}
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('html_user_participants', $html_user_participants);
	// Return
	$html = $core->build('_ajax.participant.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_participant(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$customer_id = (int) Input::post('customer_id', 0);
	$user_participants = Input::post('user_participants');
	$oCustomer = $clsCustomer->getOne($customer_id, "`name`,`admin_id`,`list_share_id`,`more_information`");
	$admin_id = (int) $oCustomer['admin_id'];
	$list_share_id = $oCustomer['list_share_id'];
	$more_information = $oCustomer['more_information'];
	$list_share_arrs = $clsCustomer->getShareIds($customer_id, $oCustomer);
	$more_information = $clsISO->to_array_json($more_information);
	$action_logs = $core->get_field($more_information, "action_logs", []);
	###
	$list_share_id_slash = ""; $use_globe = 0;
	if(!empty($user_participants)){
		$use_globe = 1;
		$user_participants = @array_diff($user_participants, array($admin_id));
		if(!empty($user_participants)){
			$list_share_id_slash = $clsISO->makeSlashListFromArray($user_participants);
		}
	}
	$participants_added_arrs = $participants_removed_arrs = array();
	if(!empty($user_participants) && empty($list_share_arrs)){
		$participants_added_arrs = $user_participants;
	} else if(!empty($user_participants) && !empty($list_share_arrs)){
		foreach($user_participants as $usr_id){
			if(!in_array($usr_id, $list_share_arrs)){
				$participants_added_arrs[] = $usr_id;
			}
		}
	}
	if(!empty($list_share_arrs) && empty($user_participants)){
		$participants_removed_arrs = $list_share_arrs;
	} else if(!empty($user_participants) && !empty($list_share_arrs)){
		foreach($list_share_arrs as $key => $val){
			if(!in_array($val, $user_participants)){
				$participants_removed_arrs[] = $val;
			}
		}
	}
	if(!empty($participants_added_arrs) || !empty($participants_removed_arrs)){
		$participants_name_added_arrs = $participants_name_removed_arrs = array();
		$participants_merge_arrs = array_merge($participants_added_arrs, $participants_removed_arrs);
		if(!empty($participants_merge_arrs)){
			$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $participants_merge_arrs).")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
			foreach($tmp as $key => $val){
				if(!empty($participants_added_arrs) && in_array($val[$clsProfile->pkey], $participants_added_arrs)){
					$participants_name_added_arrs[] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
				} else if(!empty($participants_removed_arrs) && in_array($val[$clsProfile->pkey], $participants_removed_arrs)){
					$participants_name_removed_arrs[] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
				}
			}
			unset($tmp);
		}
		if(!empty($participants_added_arrs) && empty($participants_removed_arrs)){
			$content = sprintf("<strong>%s</strong> đã thêm <strong>%s</strong> vào danh sách nguời liên quan", 
				$clsProfile->getFullName($profile_id, $oneProfile), implode(',',$participants_name_added_arrs));
		} else if(empty($participants_added_arrs) && !empty($participants_removed_arrs)){
			$content = sprintf("<strong>%s</strong> đã gỡ bỏ <strong>%s</strong> trong danh sách nguời liên quan", 
				$clsProfile->getFullName($profile_id, $oneProfile), implode(',',$participants_name_removed_arrs));
		} else {
			$content = sprintf("<strong>%s</strong> đã thêm vào <strong></strong> và gỡ bỏ <strong>%s</strong> danh sách nguời liên quan", $clsProfile->getFullName($profile_id, $oneProfile), implode(',',$participants_name_added_arrs), implode(',',$participants_name_removed_arrs));
		}
		$action_logs[$clsISO->getUniqid()] = array(
			'content' => $content,
			'user_id' => $user_id,
			'reg_date' => time()
		);
		$more_information['action_logs'] = $action_logs;
	}
	// $clsISO->print_pre($participants_added_arrs); die();
	if($clsCustomer->updateOne($customer_id, array(
		'use_globe' => $use_globe,
		'list_share_id' => $list_share_id_slash,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$clsCustomer->syncShareIds($customer_id, $user_participants, $profile_id, false);
		$msg = "_success";
		if(!empty($participants_added_arrs)){
			$titleNoty = sprintf('<strong>%s</strong> đã gán bạn liên quan tới khách hàng <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsCustomer->getName($customer_id, $oCustomer)
			);
			$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),$participants_added_arrs);
		}
		if(!empty($participants_removed_arrs)){
			$titleNoty = sprintf('<strong>%s</strong> đã xóa bạn liên quan tới khách hàng <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsCustomer->getName($customer_id, $oCustomer)
			);
			$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),$participants_removed_arrs);
		}
	}
	// return
	echo $msg; die();
}
function default_setting_config_crawl(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID, $profile_id,$clsConfiguration;
	$clsProject = new Project();
	$type = Input::post("_type","_OPEN");
	$html = "";
	if($type == "_OPEN") {
		$crm_crawl_config = $clsConfiguration->getValue("crm_crawl_config");
		$crm_crawl_config = $clsISO->to_array_json($crm_crawl_config);
		$uid = $clsISO->getUniqid();
		$smarty->assign('uid',$uid);
		$smarty->assign('crm_crawl_config',$crm_crawl_config);
		$html = $core->build('_ajax.open_setting.tpl');
	}else if($type == "_SAVE") {
		$crawl_config = Input::post('crawl_config',array());
		$crm_crawl_config = [];
		if(!empty($crawl_config["spreadsheet_id"])) {
			foreach($crawl_config["spreadsheet_id"] as $key => $spreadsheet_id) {
				$crm_crawl_config[$key] = [
					"spreadsheet_id"	=>	$spreadsheet_id,
					"sheet_id"	=>	$crawl_config["sheet_id"][$key],
					"sheet_name"	=>	$crawl_config["sheet_name"][$key],
				];
			}
		}
		$clsConfiguration->updateValue('crm_crawl_config', json_encode($crm_crawl_config,JSON_UNESCAPED_UNICODE));
	}
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_open_sheet(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	$clsTemporary = new Temporary();
	$clsCrawl = new Crawl();
	##
	$uid = $clsISO->getUniqid();
	$gId = Input::post('gId');
	$spreadsheetId = Input::post('spreadsheetId');
	$arr_worksheets = $list_worksheets = array();
	if(!empty($spreadsheetId)){
		if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			// $clsISO->print_pre($matches); die();
			$spreadsheetId = $matches[0];
		}
		/** Required Lib */
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes([Google_Service_Drive::DRIVE]);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		try {
			$resource_id = $spreadsheetId;
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$list_worksheets = $spreadsheet->sheets;
		} catch(Exception $ex){
			$msg_error = $ex->getMessage();
			if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
				$spreadsheetIdCopy = $clsCrawl->copySpreadsheet($spreadsheetId, [],0, 0,0);
				$resource_id = $spreadsheetIdCopy;
				$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
				$list_worksheets = $spreadsheet->sheets;
			}				
		}
		if(!empty($list_worksheets)){
			foreach($list_worksheets as $sheet){
				$id = $sheet->properties['sheetId'];   
				$name = $sheet->properties['title']; 
				$arr_worksheets[$id] = $name;
			}
		}
	}
	$smarty->assign('gId', $gId);
	$smarty->assign('spreadsheetId', $spreadsheetId);
	$smarty->assign('arr_worksheets', $arr_worksheets);
	// Return
	$html = $core->build('_ajax.open_sheet.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_open_config_column(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list, $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $menu_current, 
	$current_page, $core, $clsModule, $clsButtonNav, $clsConfiguration, $clsISO, $dbconn;
	$clsStock = new Stock();
	$clsCustomer = new Customer();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsTemporary = new Temporary();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	$uid = $clsISO->getUniqid();
	#
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	#- Init Client
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID_DEV);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET_DEV);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN_DEV);
	$client->setScopes([Google_Service_Drive::DRIVE]);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	
	$sid = Input::post('sid'); 
	$spreadsheetId = Input::post('spreadsheetId',"");
	$sheet_ids = Input::post('sheet_id',"");
	$ranges = Input::post('sheet_name',"");
	$arr_data = $column_data = array();
	if(!empty($ranges) && !empty($spreadsheetId)) {
		$sheet_ids = explode("|", $sheet_ids); 
		$ranges = explode("|", $ranges); 
		$arr_sheet_range = [];
		foreach($ranges as $key => $range) {
			$arr_sheet_range["'".$range."'"] = $sheet_ids[$key];
		}
		$arr_data = $clsCustomer->getDataConfigColumn($spreadsheetId,$ranges,$client);
		$cachedFileData = DIR_CACHE_JSON.'/customer/data_crawl.json';
		$encoder->encodeFile($arr_data, $cachedFileData);
		
		$number_column_sheet = [];
		foreach ($arr_data as $key => $data_sheet) {
			$highestColumnIndex = 0;
			foreach ($data_sheet as $data) {
				$max_column = count($data);
				if($max_column > $highestColumnIndex) {
					$highestColumnIndex = $max_column;
				}
			}
			$number_column_sheet[$key] = $highestColumnIndex+1;
		}
		$number_check = [];
		$cachedFile = DIR_CACHE_JSON.'/customer/config_crawl_customer.json';
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$data_config = $decoder->decodeFile($cachedFile);	
			$number_check = !empty($data_config["number_check"]) ? $data_config["number_check"] : [];
		}			
		
		$highestColumnIndex = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? 25 : 35;
		$smarty->assign("number_column_sheet",$number_column_sheet);
		$smarty->assign("arr_sheet_range",$arr_sheet_range);
		$smarty->assign("number_check",$number_check);
		$smarty->assign("clsStock",$clsStock);
		$smarty->assign("column_data",$column_data);
		$smarty->assign("agency_id",$agency_id);
		$smarty->assign("target_id",$target_id);
		$smarty->assign("stock_type",$stock_type);
		$smarty->assign("highestColumnIndex",$highestColumnIndex);
		$smarty->assign("arr_data",$arr_data);
		$smarty->assign("uid",$uid);
		// Return
		$html = $core->build("_ajax.open_config_column.tpl");
		echo json_encode(array(
			'result' =>	true,
			'uid' =>	$uid,
			'html' => $html
		)); die();
	}else{
		$res = array(
			"result" =>	false,
			'msg' => "Vui lòng nhập đủ thông tin spreasheetID và sheetname",
		);
	}
	echo json_encode($res); die();
//	$clsISO->print_pre($arr_data);die;
}
function default_do_config_column(){
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$smarty;
	$decoder = new Webmozart\Json\JsonDecoder();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawlLowFloor = new CrawlLowFloor();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	#
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$encoder = new Webmozart\Json\JsonEncoder();
	#
	$uid = $clsISO->getUniqid();
	$sheet_name = 	Input::post('sheet_name',"");
	$number_check = 	Input::post('number_check',array());
	$columns = 	Input::post('columns',array());
	$spreadsheetId = 	Input::post('sheetID',"");
	$gId = 	Input::post('gId',"");
	$cachedFileData = DIR_CACHE_JSON.'/customer/data_crawl.json';
	if(file_exists($cachedFileData)){
		$cache_data = $decoder->decodeFile($cachedFileData);	
//		@unlink($cachedFileData);
	}	
	$column_data = array();
	if(!empty($number_check) && !empty($cache_data)) {
		$cachedFile = DIR_CACHE_JSON.'/customer/config_crawl_customer.json';
		$field_column = [];
		if(!empty($cache_data)) {
			foreach ($cache_data as $key => $arr_data) {
				$row_check = $number_check[$key];
				foreach ($arr_data as $k => $v) {
					if($k == $row_check) {
						$field_column[$key] = $v;
						break;
					}
				}
			}
		}	
		$arr_data = [
			"number_check" => $number_check,
			"field_column" => $field_column,
		];
		$encoder->encodeFile($arr_data, $cachedFile);
		
	}
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid
	)); die();
}
function default_req_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	##
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('_ajax.req_customer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_req_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	##
	$html = ""; 
	$uid = Input::post('uid');
	$status = (int) Input::post('status', 0);
	$total_record = $total_assigned = $total_unassigned = 0;
	$list_items = $clsRequestCus->getAll("`_type` <>'_buy' ORDER BY `reg_date` DESC");
	if(!empty($list_items)){
		$total_record = count($list_items);
		$arr_profile_cached = array();
		$tmp = $clsProfile->getAll("`is_trash`=0", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_profile_cached[$val[$clsProfile->pkey]] = $clsProfile->getFullName($val[$clsProfile->pkey], $val); 
			}
			unset($tmp);
		}
		foreach($list_items as $key => $val){
			$user_id = (int) $val['user_id'];
			$user_status_id = (int) $val['user_status_id'];
			$status_date = $val['status_date'];
			if($val['status'] == 1){
				$total_assigned += 1;
			} else {
				$total_unassigned += 1;
			}
			$html.= '<tr class="'.($status==1?($val['status']==0?'d-none':"") : ($status==2 ? ($val['status']==1?"d-none":"") : '')).'">
				<td class="align-center">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
				<td class="align-center">'.$arr_profile_cached[$user_id].'</td>
				<td class="align-center text-center">'.$val['amount'].'</td>
				<td class="align-center">'.$val['project_name'].'</td>
				<td class="align-center text-center">
					<label class="switch" title="Đã xử lý">
						<input type="checkbox" value="1" name="status" uid="'.$uid.'" onChange="$Core.global.crm.status_req_customer(this, event)"'.($val['status']==1?' checked':'').' request_cus_id="'.$val[$clsRequestCus->pkey].'">
						<span class="slider round"></span>
					</label></td>
				<td class="align-center">'.($user_status_id > 0 ? $arr_profile_cached[$user_status_id] : "--").'</td>
				<td class="align-center">'.($status_date > 0 ? $clsISO->convertTimeToText($status_date,true) : "--").'</td>
			</tr>';
		}
	} else {
		$html.= '<tr>
			<td colspan="7" class="text-center border-end">
				<img src="'.URL_IMAGES.'/DataEmpty.svg" class="w-px-100" />
				<p class="text-muted">Chưa có yêu cầu nào</p>
			</td>
		</tr>';
	}
	$html_briefs = '<div onClick="$Core.global.crm.do_search_req_customer(this, event)" 
		class="brief-item a1a bg-orange flex-fill cursor-pointer" status="0" uid="'.$uid.'">
		<p class="text-fs-14 mb-0">Tổng y/c</p>
		<hr class="w-px-50 my-2">
		<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">'.$total_record.'</h3>
	</div>
	<div onClick="$Core.global.crm.do_search_req_customer(this, event)" status="1" 
		class="brief-item a2a bg-azure flex-fill cursor-pointer" uid="'.$uid.'">
		<p class="text-fs-14 mb-0">Đã xử lý</p>
		<hr class="w-px-50 my-2">
		<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">'.$total_assigned.'</h3>
	</div>
	<div onClick="$Core.global.crm.do_search_req_customer(this, event)" status="2" 
		class="brief-item a2a bg-purple flex-fill cursor-pointer" uid="'.$uid.'">
		<p class="text-fs-14 mb-0">Chưa xử lý</p>
		<hr class="w-px-50 my-2">
		<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">'.$total_unassigned.'</h3>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'html_briefs' => $html_briefs
	)); die();
}
function default_status_req_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	
	$msg = "_error";
	$status = (int) Input::post('status', 0);
	$request_cus_id = (int) Input::post('request_cus_id', 0);
	if($request_cus_id > 0){
		if($clsRequestCus->updateOne($request_cus_id, array(
			'status' => $status,
			'user_status_id' => $profile_id,
			'status_date' => time()
		))){
			$msg = "_error";
		}
	}
}
function default_open_req_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsCity = new City();
	$clsCountry = new Country();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.req_customer.tpl');
	echo json_encode(array(
		'uid' => 'modal'.$clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function default_save_req_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	$clsZalo = new Zalo();
	###
	$msg = "_error";
	$amount = (int) Input::post('amount', 0);
	$project_name = Input::post('project_name');
	$notes = Input::post('notes');
	$more_information = array(
		'amount' => $amount,
		'project_name' => $project_name,
		'notes' => $notes
	);
	// $dbconn->debug = true;
	if($clsRequestCus->insert(array(
		'amount' => $amount,
		'project_name' => $project_name,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'user_id' => $profile_id,
		'reg_date' => time()
	))){
		$msg = "_success";
		// Send Zalo
		$message = "======================";
		$message.= "\n";
		$message.= sprintf("📢**%s %s** yêu cầu được cấp data khách hàng", $oneProfile['role_name'] , $clsProfile->getFullName($profile_id, $oneProfile));
		$message.= "\n";
		$message.= "Số lượng: {color:#C00000}".$amount."{/color} khách hàng";
		$message.= "\n";
		$message.= sprintf("Dự án: %s", $project_name);
		if(!empty($notes)){
			$message.= sprintf("Ghi chú: %s", $notes);
		}
		$message.= "\n";
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
		));
		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' => $message,
			'group_id' => _CRM_DATA_GROUP_ZALO_ID
		));
	}
	// return
	echo $msg; die();
}
function default_open_select(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$uid = Input::post('uid', $clsISO->getUniqid());
	$call_from = Input::post('call_from', "");
	$smarty->assign('uid', $uid);
	$smarty->assign('call_from', $call_from);
	// Return
	$html = $core->build('_ajax.open_select.tpl');
	echo json_encode(array(
		'uid' => sprintf('open_select_%s', $uid),
		'html' => $html
	)); die();
}
function default_load_ms_customers(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	
	$html = '';
	$uid = $clsISO->getUniqid();
	$status_id = (int) Input::post('status_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$sql_query = "`is_trash`=0 AND `admin_id`='{$profile_id}'";
	if($status_id > 0) $sql_query.= " AND `status_id`='{$status_id}'";
	if($resource_id > 0) $sql_query.= " AND `resource_id`='{$resource_id}'";
	#- Begin pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 50);
	$total_record = $clsCustomer->countItem($sql_query);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$field = "{$clsCustomer->pkey},`name`,`phone`,`resource_id`,`list_campaign_id`,`status_id`,`upd_date`";
	$list_customers = $clsCustomer->getAll("{$sql_query} ORDER BY `upd_date` DESC".$limitCond, $field);
	if(!empty($list_customers)){
		$arr_property_cached = $arr_status_cached = $arr_campaign_cached = array();
		$arr_in = array('_CUSTOMER_RESOURCES','CUSTOMER_STATUS');
		$p_field = "{$clsProperty->pkey},`property_type`,`title`,`bgcolor`,`textcolor`";
		$tmp = $clsProperty->getAll("`property_type` IN ('".implode('\',\'', $arr_in)."')", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if(in_array($val['property_type'], array('_CUSTOMER_RESOURCES'))){
					$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
				} else {
					$arr_status_cached[$val[$clsProperty->pkey]] = $val;
				}
			}
			unset($tmp);
		}
		#
		$cond = "`is_trash`=0 AND `campaign_type`='_campaign' AND (`user_id`='{$profile_id}' OR `use_globe`=1)";
		$tmp = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", "{$clsCampaign->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_campaign_cached[$val[$clsCampaign->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach($list_customers as $key => $val){
			$status_id = (int) $val['status_id'];
			$resource_id = (int) $val['resource_id'];
			$list_campaign_id = $val['list_campaign_id'];
			$list_campaign_arrs = $clsISO->getArrayByTextSlash($list_campaign_id, "", []);
			$oneStatus = $status_id > 0 && isset($arr_status_cached[$status_id]) ? $arr_status_cached[$status_id] : array();
			$html.= '<div id="'.$val[$clsCustomer->pkey].'" class="d-flex bg-lighter item_share_customer rounded-2 p-2 mb-1">
				<label class="d-flex align-items-center gap-2 ant-checkbox">
					<input type="radio" uid="'.$uid.'" onChange="$Core.global.crm.handle_select_customer(this, event)" name="customer_id" 
						value="'.$val[$clsCustomer->pkey].'" full_name="'.$val['name'].'" phone="'.$val['phone'].'" class="js__customer_item">
					<div class="d-flex flex-column gap text-nowrap">
						<div class="d-flex align-items-center gap-1">
							<span class="badge rounded-pill" style="background:'.$oneStatus['bgcolor'].' !important; color:'.$oneStatus['textcolor'].' !important">'.$oneStatus['title'].'</span> 
							<span class="fw-bold">'.$val['name'].'</span>
						</div>
						<div class="d-flex align-items-center gap-2 text-fs-12">
							'.($resource_id > 0 && isset($arr_property_cached[$resource_id]) 
								? '<span class="text-muted">'.$arr_property_cached[$resource_id].'</span>' : '' ).'
							'.(!empty($list_campaign_arrs) ? '<span class="text-muted">
								'.$clsCampaign->getTitleFromCached($list_campaign_arrs, $arr_campaign_cached).'
							</span>' : '' ).'
							<span class="d-flex align-items-center gap-1 text-muted">
								<i class="material-icons-outlined fs-13 no-translate">more_time</i> 
								'.$clsISO->getTimeAgo($val['upd_date']).'
							</span>
						</div>
					</div>
				<label>
			</label></div>';
		}
	} else {
		$html = '<div class="d-flex empty py-3 flex-column align-items-center">
			<img src="'.URL_IMAGES.'/empty.svg" class="w-px-100" />
			<p class="text-muted">Không có khách hàng</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'per_page' => $per_page
	)); die();
}

function default_data_distribution(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	#
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	#- Campaign
	$c_field = "{$clsCampaign->pkey},title";
	$cond = "`campaign_type`='_campaign' and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	$list_campaigns = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", $c_field);
	
	$smarty->assign('uid', $uid);
	$smarty->assign('list_campaigns', $list_campaigns);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$html = $core->build('_ajax.data_distribution.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_share_customers(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerCampaign = new CustomerCampaign();
	$clsCampaign = new Campaign();
	$useJoinCampaign = false;
	$joinSql = "";
	
	$html = '';
	$uid = $clsISO->getUniqid();
	$status_id = (int) Input::post('status_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	if($campaign_id > 0){
		$useJoinCampaign = true;
		$joinSql = " LEFT JOIN (
			SELECT `customer_id` AS `customer_ref`, `campaign_id` AS `rel_campaign_id`
			FROM `{$clsCustomerCampaign->tbl}`
			WHERE `campaign_id`='{$campaign_id}'
			GROUP BY `customer_id`, `campaign_id`
		) AS `cc` ON `cc`.`customer_ref`=`customer_id`";
	}
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 50);
	
	$sql_query = "`is_trash`=0 AND `admin_id`='{$profile_id}'";
	if($status_id > 0) $sql_query.= " AND `status_id`='{$status_id}'";
	if($resource_id > 0) $sql_query.= " AND `resource_id`='{$resource_id}'";
	if($campaign_id > 0){
		if($useJoinCampaign){
			$sql_query.= " AND (`cc`.`rel_campaign_id`='{$campaign_id}' OR `list_campaign_id` like '%|{$campaign_id}|%')";
		} else {
			$sql_query.= " AND ".$clsCustomer->sqlCondHasCampaign($campaign_id);
		}
	}
	#- Begin pagination
	if($useJoinCampaign){
		$total_record = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT `customer_id`) 
			FROM `{$clsCustomer->tbl}` {$joinSql} WHERE {$sql_query}");
	} else {
		$total_record = $clsCustomer->countItem($sql_query);
	}
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	// $dbconn->debug = true;
	$field = "{$clsCustomer->pkey},`name`,`resource_id`,`list_campaign_id`,`status_id`,`upd_date`";
	if($useJoinCampaign){
		$list_customers = $dbconn->GetAll("SELECT DISTINCT `{$clsCustomer->tbl}`.{$field} 
			FROM `{$clsCustomer->tbl}` {$joinSql} 
			WHERE {$sql_query} ORDER BY `upd_date` DESC {$limitCond}");
	} else {
		$list_customers = $clsCustomer->getAll("{$sql_query} ORDER BY `upd_date` DESC".$limitCond, $field);
	}
	if(!empty($list_customers)){
		$arr_property_cached = $arr_status_cached = $arr_campaign_cached = array();
		$arr_in = array('_CUSTOMER_RESOURCES','CUSTOMER_STATUS');
		$p_field = "{$clsProperty->pkey},`property_type`,`title`,`bgcolor`,`textcolor`";
		$tmp = $clsProperty->getAll("`property_type` IN ('".implode('\',\'', $arr_in)."')", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if(in_array($val['property_type'], array('_CUSTOMER_RESOURCES'))){
					$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
				} else {
					$arr_status_cached[$val[$clsProperty->pkey]] = $val;
				}
			}
			unset($tmp);
		}
		#
		$cond = "`is_trash`=0 AND `campaign_type`='_campaign'";
		$cond.= " AND (`user_id`='{$profile_id}' OR `use_globe`=1)";
		$tmp = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", "{$clsCampaign->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_campaign_cached[$val[$clsCampaign->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach($list_customers as $key => $val){
			$status_id = (int) $val['status_id'];
			$resource_id = (int) $val['resource_id'];
			$list_campaign_id = $val['list_campaign_id'];
			$list_campaign_arrs = !empty($list_campaign_id) 
				? $clsISO->getArrayByTextSlash($list_campaign_id) : array();
			$oneStatus = $status_id > 0 && isset($arr_status_cached[$status_id]) 
				? $arr_status_cached[$status_id] : array();
			$html.= '<div id="'.$val[$clsCustomer->pkey].'" class="d-flex bg-lighter item_share_customer rounded-2 p-2 mb-1">
				<label class="d-flex align-items-center gap-2 ant-checkbox">
					<input uid="'.$uid.'" onChange="$Core.crm.handle_cus_staff(this, event)" name="list_customers[]" 
						value="'.$val[$clsCustomer->pkey].'" type="checkbox" class="js__customer_item">
					<div class="d-flex flex-column gap text-nowrap">
						<strong><label class="badge_status badge" style="background:'.$oneStatus['bgcolor'].' !important; color:'.$oneStatus['textcolor'].' !important">'.$oneStatus['title'].'</label> '.$val['name'].'</strong>
						<div class="d-flex align-items-center gap-2 text-fs-12">
							'.($resource_id > 0 && isset($arr_property_cached[$resource_id]) 
								? '<span class="text-muted">'.$arr_property_cached[$resource_id].'</span>' : '' ).'
							'.(!empty($list_campaign_arrs) ? '<span class="text-muted">
								'.$clsCampaign->getTitleFromCached($list_campaign_arrs, $arr_campaign_cached).'
							</span>' : '' ).'
							<!-- <span class="d-flex align-items-center gap-1 text-muted">
								<i class="material-icons-outlined fs-13 no-translate">more_time</i> 
								'.$clsISO->getTimeAgo($val['upd_date']).'
							</span> -->
						</div>
					</div>
				<label>
			</label></div>';
		}
	} else {
		$html = '<div class="d-flex empty py-3 flex-column align-items-center">
			<img src="'.URL_IMAGES.'/empty.svg" class="w-px-100" />
			<p class="text-muted">Không có khách hàng</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'per_page' => $per_page
	)); die();
}
function default_load_share_staffs(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$department_id = Input::post('department_id', 0);
	$keysearch =  Input::post('keysearch');
	if($clsISO->_DEV()){
		$sql_query = "`t1`.`is_trash`=0 AND `t1`.`status_id`='"._STATUS_STAFF_ON_ID."' ";
	}else{
		$sql_query = "`t1`.`is_trash`=0 AND `t1`.`status_id`='"._STATUS_STAFF_ON_ID."' 
		AND `t1`.`list_department_id` LIKE '%|"._DEPARTMENT_SALE_ID."|%'";
	}
	
	if($department_id > 0) $sql_query.= " AND `t1`.`department_id`='{$department_id}'";
	if(!empty($keysearch)) $sql_query.= " AND `full_name_slug` LIKE '%".$core->replaceSpace($keysearch)."%'";
	$field = "`t1`.{$clsProfile->pkey},`t1`.`code`,`t1`.`full_name`,`t1`.`first_name`,`t1`.`last_name`";
	$field.= ",`t1`.`avatar`,`t2`.`title` AS `department_name`,`t3`.`title` as `role_name`";
	$list_staffs = $dbconn->getAll("SELECT {$field} FROM {$clsProfile->tbl} AS `t1` 
		INNER JOIN {$clsProperty->tbl} AS `t2` ON `t1`.`department_id`=`t2`.`property_id`
		INNER JOIN {$clsProperty->tbl} AS `t3` ON `t1`.`role_id`=`t3`.`property_id`
		WHERE {$sql_query}");
		
	$html = '';
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$html.= '<div class="d-flex bg-lighter rounded-2 p-2 mb-1">
				<label class="d-flex align-items-center gap-2 ant-checkbox">
					<input uid="'.$uid.'" name="list_staffs[]" onChange="$Core.crm.handle_selected_staff(this, event)" 
						value="'.$val[$clsProfile->pkey].'" type="checkbox" class="js__staff_item">
					<div class="d-flex gap-2 align-items-center">
						<img class="avatar avatar-xs rounded-pill" onerror="this.src=\''.URL_IMAGES.'/no-avatar.png\'" 
							src="'.$clsProfile->getAvatar($val[$clsProfile->pkey], $val, 30, 30).'" />
						<div class="d-flex flex-column gap-0">
							<span>'.sprintf('%s %s', $val['code'], $clsProfile->getFullName($val[$clsProfile->pkey], $val)).'</span>
							<div class="d-flex align-items-center text-fs-12 gap-2">
								<span class="text-muted">'.$val['department_name'].'</span>
								<span class="text-muted">'.$val['role_name'].'</span>
							</div>
						</div>
					</div>
				<label>
			</div>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_do_share_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$isRootProfile = $clsCustomer->isRootProfile();
	
	$msg = "_error";
	$list_staffs = Input::post('list_staffs', []);
	$list_customers = Input::post('list_customers', []);
	$customer_insert = [];
	if(!empty($list_customers) && !empty($list_staffs)){
		$msg = "_success";
		$total_staffs = count($list_staffs);
		$total_customers = count($list_customers);
		if($total_staffs == 1){
			$total_assigned = 0;
			$admin_id = $list_staffs[0];
			$p_field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
			$adminProfile = $clsProfile->getOne($admin_id, $p_field);
			foreach($list_customers as $customer_id){
				$field = "`admin_id`,`name`,`phone`,`begin_need`,`list_share_id`,`more_information`";
				$oCustomer = $clsCustomer->getOne($customer_id, $field);
				if(!empty($oCustomer)){
					$list_share_id = $oCustomer['list_share_id'];
					$more_information = $oCustomer['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_share_arrs = !empty($list_share_id) 
						? $clsISO->getArrayByTextSlash($list_share_id) : array();
					
					$action_logs = $core->get_field($more_information, "action_logs", []);
					$adminProfile_old = array();
					if($profile_id == $oCustomer['admin_id']){ // Mình chuyển KH của mình
						$adminProfile_old = $oneProfile;
					} else { // Mình chuyển KH của người khác
						$adminProfile_old = $clsProfile->getOne($oCustomer['admin_id'], $p_field);
					}
					$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($oCustomer['admin_id'], $adminProfile_old), $clsProfile->getFullName($admin_id, $adminProfile));
					$action_logs[$clsISO->getUniqid()] = array(
						'content' => $content,
						'user_id' => $profile_id,
						'reg_date' => time()
					);
					$more_information['action_logs'] = $action_logs;	
					if(!in_array($oCustomer['admin_id'], $list_share_arrs)){
						$list_share_arrs[] = $oCustomer['admin_id'];
					}
					$update_field['use_globe'] = 1; // Mặc định share
					$update_field['admin_id'] = $admin_id;
					$update_field['list_share_id'] = $clsISO->makeSlashListFromArray($list_share_arrs);
					$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
					if($clsCustomer->updateOne($customer_id, $update_field)){
						$total_assigned ++;
						$clsCustomerSales->insert(array(
							'customer_id' => $customer_id,
							'admin_id' => $admin_id,
							'user_id' => $profile_id,
							'assign_date' => time(),
							'is_stop' => ($isRootProfile ? 0 : 1)
						));
						$customer_insert[] = [
							"customer_name"	=>	$oCustomer["name"],
							"phone"	=>	$oCustomer["phone"],
							"begin_need"	=>	$oCustomer["begin_need"],
						];
					}
				}
			}
			if($total_assigned > 0){
				$clsZalo = new Zalo();
				$clsZalo->sendNotifyCRMZalo($admin_id, $adminProfile, $total_assigned, $customer_insert);
			}
		} else {
			$extra = $total_customers % $total_staffs;
			$base = intdiv($total_customers, $total_staffs);
			$index = 0; $arr_staffs_customers = [];
			foreach ($list_staffs as $i => $staff_id) {
				$take = $base + ($i < $extra ? 1 : 0);
				$arr_staffs_customers[$staff_id] = array_slice($list_customers, $index, $take);
				$index += $take;
			}
			// $clsISO->print_pre($arr_staffs_customers); die();
			if(!empty($arr_staffs_customers)){
				foreach($arr_staffs_customers as $admin_id => $arr_customers){
					if(!empty($arr_customers)){
						$total_assigned = 0;
						$p_field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
						$adminProfile = $clsProfile->getOne($admin_id, $p_field);
						$customer_insert = [];
						foreach($arr_customers as $customer_id){
							$field = "`admin_id`,`list_share_id`,`more_information`";
							$oCustomer = $clsCustomer->getOne($customer_id, $field);
							if(!empty($oCustomer)){
								$list_share_id = $oCustomer['list_share_id'];
								$more_information = $oCustomer['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$list_share_arrs = !empty($list_share_id) 
									? $clsISO->getArrayByTextSlash($list_share_id) : array();
								
								$action_logs = $core->get_field($more_information, "action_logs", []);
								$adminProfile_old = array();
								if($profile_id == $oCustomer['admin_id']){ // Mình chuyển KH của mình
									$adminProfile_old = $oneProfile;
								} else { // Mình chuyển KH của người khác
									$adminProfile_old = $clsProfile->getOne($oCustomer['admin_id'], $p_field);
								}
								$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($oCustomer['admin_id'], $adminProfile_old), $clsProfile->getFullName($admin_id, $adminProfile));
								$action_logs[$clsISO->getUniqid()] = array(
									'content' => $content,
									'user_id' => $profile_id,
									'reg_date' => time()
								);
								$more_information['action_logs'] = $action_logs;	
								if(!in_array($oCustomer['admin_id'], $list_share_arrs)){
									$list_share_arrs[] = $oCustomer['admin_id'];
								}
								$update_field['use_globe'] = 1; // Mặc định share
								$update_field['admin_id'] = $admin_id;
								$update_field['list_share_id'] = $clsISO->makeSlashListFromArray($list_share_arrs);
								$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
								// $clsISO->print_pre($update_field); die();
								if($clsCustomer->updateOne($customer_id, $update_field)){
									$total_assigned ++;
									$clsCustomerSales->insert(array(
										'customer_id' => $customer_id,
										'admin_id' => $admin_id,
										'user_id' => $profile_id,
										'assign_date' => time(),
										'is_stop' => ($isRootProfile ? 0 : 1)
									));
									$customer_insert[] = [
										"customer_name"	=>	$oCustomer["name"],
										"phone"	=>	$oCustomer["phone"],
										"begin_need"	=>	$oCustomer["begin_need"],
									];
								}
							}
						}
						if($total_assigned > 0){
							$clsZalo = new Zalo();
							$clsZalo->sendNotifyCRMZalo($admin_id, $adminProfile, $total_assigned, $customer_insert);
						}
					}
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_unShare(){
	global $profile_id,$oneProfile, $core, $clsISO, $clsUser, $_LANG_ID, $dbconn,$clsProfile, $deviceType,$smarty,$clsConfiguration;
	$clsStock 	 = new Stock();
	$clsCountry  = new Country();
	$clsCity 	 = new City();
	$clsArchived = new Archived();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsCampaign = new Campaign();
	$clsCustomerSales = new CustomerSales();
	$smarty->assign("clsCustomer",$clsCustomer);
	$smarty->assign("clsFollowUp",$clsFollowUp);
	/* Global cond */
	$now = time();	
	$action = Input::post('action',"");
	$tab = Input::post('tab','owner');
	$view = Input::post("view", "table");
	$holderG = Input::post('holderG','_tablist');
	$keysearch = Input::post('keysearch', "");
	$status_id = (int) Input::post('status_id');
	$sort_by = Input::post('sort_by', "last_contact");
	$priority_id = (int) Input::post('priority_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$typeHolder = Input::post('typeHolder',"_all");
	$staff_id = (int)Input::post('staff_id',0);
	$profile_id = !empty($staff_id) ? $staff_id : $profile_id;
	
	$is_all = (int) Input::post('is_all', 0);
	$reg_date = Input::post('reg_date', "");
	$group_id = (int) Input::post('group_id',0,true);
	$admin_id = (int) Input::post('admin_id',0,true);
	$campaign_id = (int) Input::post('campaign_id',0,true);
	$blocktype_id = (int) Input::post('blocktype_id',0,true);
	$group_customer_sale = Input::post('group_customer_sale',"",true);
	
	if($clsCustomer->isFullPermiss()) {
		$cond =  "`t2`.`is_trash`=0 AND (`t2`.`user_id`='{$profile_id}' OR `t2`.`user_id`='"._PROFILE_ROOT_ID."')";
	}else{
		$cond =  "`t2`.`is_trash`=0 AND `t2`.`user_id`='{$profile_id}'";
	}
	if(!empty($keysearch)){
		$arr_ids = @explode(',', $keysearch);
		$slug = $core->replaceSpace($keysearch);
		if(!empty($arr_ids)){
			$cond.= " and (`t2`.`name` like '%{$keysearch}%' 
				or `t2`.`name_slug` like '%{$slug}%' 
				or `t2`.`email` like '%{$keysearch}%' 
				or `t2`.`phone` like '%{$keysearch}%' 
				or `t2`.`address` like '%{$keysearch}%' 
				or `t2`.`{$clsCustomer->pkey}` in ('".implode("','", $arr_ids)."')
			)";
		} else {
			$cond.= " and (`t2`.`name` like '%{$keysearch}%' 
				or `t2`.`name_slug` like '%{$slug}%' 
				or `t2`.`email` like '%{$keysearch}%' 
				or `t2`.`phone` like '%{$keysearch}%' 
				or `t2`.`address` like '%{$keysearch}%'
			)";
		}
	}
	# filter by priority_id
	if(intval($priority_id) > 0){
		$cond.= " and `t2`.`priority_id`='{$priority_id}'";
	}
	# filter by resource_id
	if($resource_id > 0){
		$cond.= " and `t2`.`resource_id`='{$resource_id}'";
	}
	# filter by reg_date
	if(!empty($reg_date)){
		$cond.= " and FROM_UNIXTIME(`t2`.`reg_date`,'%Y-%m-%d')='{$reg_date}'";
	}
	# filter by campaign
	if($campaign_id > 0){
		$cond.= " and ".$clsCustomer->sqlCondHasCampaign($campaign_id, 't2');
	}
	# filter by status_id
	if(!empty($status_id)){
		$cond.= " and `t2`.`status_id`='{$status_id}'";
	} else {
		if($is_all == 0) {
			$cond.= " AND `t2`.`status_id`<>'"._CRM_STATUS_TRASH_ID."'";
		}
	}
	$orderBy = ""; // Set Order Default
	if($group_id > 0){
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array(); 
		if(!empty($list_profile_arrs)){
			$cond.= " and (`t2`.`admin_id` in (".implode(',', $list_profile_arrs)."))";
		}
	}
	if($blocktype_id > 0){
		$cond.= " and `t2`.`blocktype_id`='{$blocktype_id}'";
	}
	if($is_all == 0){
		$cond.= " and `t2`.`customer_id` not in (
			select `customer_id` from `{$clsArchived->tbl}` 
			where `profile_id`='{$profile_id}'
		)";
	}
	if($group_customer_sale != ""){
		$group_customer = $clsConfiguration->getValue('group_customer_sale');
		$group_customer = !empty($group_customer) ? $clsISO->to_array_json($group_customer) : [];
		$arr_group_customer_sale = !empty($group_customer[$profile_id]) ? $group_customer[$profile_id] : [];
		if(!empty($arr_group_customer_sale[$group_customer_sale])) {
			$list_ids = $arr_group_customer_sale[$group_customer_sale]["list_ids"];
			$cond.= " and `t2`.`customer_id` IN (".implode(',',$list_ids).")";
		}
	}
	$sql_string = $sql_cond = $cond;
	if($admin_id > 0){
		// Ẩn checkbox vì mình không phải là người quản lý.
		$cond.= " and (`t2`.`admin_id`<>'{$admin_id}' and ".$clsCustomer->sqlCondHasShare($admin_id, 't2').")";
	}
	#
	$field = "`t1`.*,`t2`.`name`,`t2`.`list_share_id`,`t2`.`more_information`";
//	$dbconn->debug = true;
	$items = $dbconn->getAll("SELECT {$field} FROM {$clsCustomerSales->tbl} AS `t1` INNER JOIN {$clsCustomer->tbl} AS `t2` ON `t1`.`customer_id`=`t2`.`customer_id` WHERE t1.`is_stop`=0 AND {$cond}"); 
//	$clsISO->print_pre($items);die;
	
	$_minutes_after = _CRM_TIME_AFTER_FOLLOWUP / 60;
	$total = 0;
	if(!empty($items)){
		foreach($items as $key => $val){
			$admin_id = (int) $val['admin_id'];
			$customer_id = (int) $val['customer_id'];
			$assign_date = (int) $val['assign_date'];
			$list_share_id = $val['list_share_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_share_arrs = !empty($list_share_id) ? $clsISO->getArrayByTextSlash($list_share_id) : array();
			$list_logs = isset($more_information['logs']) ? $more_information['logs'] : array();
			// $dbconn->debug = true;
			$list_followups = $clsFollowUp->getAll("`admin_id`='{$admin_id}' AND `customer_id`='{$customer_id}' ORDER BY `reg_date` DESC");
			if(strtotime("+{$_minutes_after} minutes", $assign_date) < time() && empty($list_followups)){
					$list_logs[$clsISO->getUniqid()] = array(
						'_type' => 'assign',
						'from_id' => $admin_id,
						'to_id' => $val["user_id"],
						'status_id' => 0,
						'reg_date' => time()
					);
					$more_information['logs'] = $list_logs;
					if($clsCustomer->updateOne($customer_id, array(
						'admin_id' => $val["user_id"],
						'list_share_id' => $list_share_id,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						/** Xoá bỏ quyền CS */
						$clsCustomerSales->updateOne($val[$clsCustomerSales->pkey], array(
							'is_stop' => 1
						));
						++$total;
					}
				}
			}
		unset($items);
	}
	// Output
	echo json_encode([
		"total"	=>	$total,
		"msg"	=>	"Đã thu hồi {$total} khách hàng không được chăm sóc"
	], JSON_UNESCAPED_UNICODE); die();
}
function default_configColumn(){
	global $core,$profile_id,$oneProfile,$clsISO,$smarty;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');		
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	###
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', 'upload');
	$totalInsert = $totalDuplicate = 0;
	if($tp == 'google_sheet'){
		$spreadsheetId = Input::post('spreadsheetId');
		if(!empty($spreadsheetId)){
			if($clsISO->checkContainer($spreadsheetId, "docs.google.com","")){
				@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
				$spreadsheetId = $matches[0];
			}
			#- Require library		
			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
			/** Init Client */
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
			// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
			// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
			// get all the rows of a sheet
			$range = 'Data'; // here we use the name of the Sheet to get all the rows
			$response = $service->spreadsheets_values->get($spreadsheetId, $range);
			$tblData = $response->getValues();
			// $clsISO->print_pre($tblData); die();
			$select_default = ["name","phone","email","address","status_id","begin_need"];
			$cachedColumnName = sprintf('column_%s.json', $profile_id);
			$cachedFile = DIR_CACHE_JSON.'/customer/'.$cachedColumnName;
			if(file_exists($cachedFile)){
				$select_default = $decoder->decodeFile($cachedFile);
			}
			$data_select = $clsCustomer->getDataColumnCustomer();
			$highestColumnIndex = 15;
			$widthColumn = 100/$highestColumnIndex;	
			$smarty->assign("data_select",$data_select);
			$smarty->assign("select_default",$select_default);
			$smarty->assign("widthColumn",$widthColumn);
			$smarty->assign("highestColumnIndex",$highestColumnIndex);
			$smarty->assign("tblData",$tblData);
			// Return
			$html = $core->build("_ajax.configColumn.tpl");
			echo json_encode(array(
				'uid' => $uid,
				'result' =>	true,
				'html' => $html
			)); die();
		}
	} else {
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
			if(@is_uploaded_file($_FILES['fileimport']['tmp_name'])){
				$target_dir = PCMS_DIR."/tmp/";
				$file_ext =explode('.',basename($_FILES["fileimport"]["name"]));
				$file_ext =strtolower(end($file_ext));
				$target_file = $target_dir . time().'.'.$file_ext;
				if (move_uploaded_file($_FILES["fileimport"]["tmp_name"], $target_file)) {
					$html = '';
					$inputFileName = $target_file;
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel/IOFactory.php";
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						die($e->getMessage());
					}
					$worksheet = $objPHPExcel->getActiveSheet();
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow();
					$highestColumn      = $worksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					##
					$index = 0; $tblData =array();
					for($row=1; $row <= $highestRow; ++ $row){
						for($col=0; $col < $highestColumnIndex; ++$col){
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$tblData[$index][] = $cell->getValue();
						}
						++$index;
					}
					// Remove file uploaded
					@unlink($inputFileName);
					$select_default = ["name","phone","email","address","status_id","begin_need"];
					$cachedColumnName = sprintf('column_%s.json', $profile_id);
					$cachedFile = DIR_CACHE_JSON.'/customer/'.$cachedColumnName;
					if(file_exists($cachedFile)){
						$select_default = $decoder->decodeFile($cachedFile);
					}
					$highestColumnIndex = 15;
					$widthColumn = 100/$highestColumnIndex;	
					$data_select = $clsCustomer->getDataColumnCustomer();
					if(!empty($tblData)){
						$cachedName = sprintf('%s.json', $uid);
						$cachedFile = DIR_CACHE_JSON.'/customer/'.$cachedName;
						$encoder->encodeFile($tblData, $cachedFile);
					}
					$smarty->assign("uid", $uid);
					$smarty->assign("data_select",$data_select);
					$smarty->assign("select_default",$select_default);
					$smarty->assign("widthColumn",$widthColumn);
					$smarty->assign("highestColumnIndex",$highestColumnIndex);
					$smarty->assign("tblData",$tblData);
					// Return
					$html = $core->build("_ajax.configColumn.tpl");
					echo json_encode(array(
						'uid' => $uid,
						'result' =>	true,
						'html' => $html
					)); die();
				}
			}
		}
	}
	// Return
	$res = array(
		"result"	=>	false,
		'msg' => "Vui lòng upload file excel",
	);
	echo json_encode($res); die();
}
function default_continue_config(){
	global $core,$profile_id,$oneProfile,$clsISO,$smarty;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	###
	$uid = Input::post("uid");
	$columns = Input::post("columns", array());
	// $clsISO->print_pre($columns); die();
	if(!empty($columns)) {
		$error_field = 0; $arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[$key] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
		if(!in_array("name", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột họ tên khách hàng chưa được xác định"
			]); die();
		}
		if(!in_array("phone", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột số điện thoại khách hàng chưa được xác định"
			]); die();
		}
		if($error_field > 0){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Các cột dữ liệu không được trùng nhau"
			]); die();
		}
		$cachedColumnName = sprintf('column_%s.json', $profile_id);
		$cachedFile = DIR_CACHE_JSON.'/customer/'.$cachedColumnName;
		$encoder = new Webmozart\Json\JsonEncoder();
		// $clsISO->print_pre($arr_fields); die();
		$encoder->encodeFile($arr_fields, $cachedFile);
		$res = array(
			"result"	=>	true,
			'msg' => "Cài đặt thành công",
		);
	}else{
		$res = array(
			"result"	=>	false,
			'msg' => "Có lỗi xảy ra. Xin vui lòng thử lại!",
		);
	}
	// Return	
	echo json_encode($res); die();
}

?>
