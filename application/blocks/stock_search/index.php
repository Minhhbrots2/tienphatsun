<?php 
	global $core, $smarty, $clsISO,$profile_id;
	$clsCache = new Cache();
	$clsProperty = new Property();
	if($project_id == _PROJECT_VHGG_ID){
		$list_range_groups = $clsProperty->getCacheItems('_GROUP_RANGE_VHGG');
		$smarty->assign('list_range_groups', $list_range_groups);
	}
	$list_buildings = array();
	if(isset($is_project_block) && $is_project_block == 1) {
		if($clsCache->has('_stock_building_'.$block_id.'_cached')){
			$list_buildings = $clsCache->get('_stock_building_'.$block_id.'_cached');
		} else {
			$field = "{$clsProperty->pkey},`title`,`more_information`";
			$list_buildings = $clsProperty->getAll("property_type='_RANGE' and `for_id`='{$block_id}' order by `order_no` ASC", $field);
			if(!empty($list_buildings)){
				foreach($list_buildings as $key => $val){
					$building_information = $val['more_information'];
					$building_information = $clsISO->to_array_json($building_information);
					$list_buildings[$key]['more_information'] = $building_information;
				}
				$clsCache->put('_stock_building_'.$block_id.'_cached', $list_buildings);
			} else {
				if($clsCache->has('_stock_building_'.$block_id.'_cached')){
					$clsCache->delete('_stock_building_'.$block_id.'_cached');
				}
			}
		}
	}
	##
	$_ss_price_min = 0;
	$_ss_price_max = 150*1000000000;
	$smarty->assign('list_buildings', $list_buildings);
	$smarty->assign('_ss_price_min', $_ss_price_min);
	$smarty->assign('_ss_price_max', $_ss_price_max);
	##
	$_ss_area_min = 0;
	$_ss_area_max = 500;
	$smarty->assign('_ss_area_min', $_ss_area_min);
	$smarty->assign('_ss_area_max', $_ss_area_max);
	##
	$agency_ids = Input::get("agency_ids","");
	$_ss_search['agency_ids'] = !empty($agency_ids) ? @explode(",",$agency_ids) : $_ss_search['agency_ids'];
	$get_agency_ids = isset($_ss_search['agency_ids']) && !empty($_ss_search['agency_ids']) ? $_ss_search['agency_ids'] : array();
	$smarty->assign('get_agency_ids', $get_agency_ids);
?>