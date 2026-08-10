<?php 
	global $smarty, $core, $clsISO, $clsProfile;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$clsProject = new Project();
	$smarty->assign('clsProject', $clsProject);

	$clsMember = new Member();
	$smarty->assign('clsMember', $clsMember);
	###
	//$field = "{$clsProperty->pkey},title";
	//$list_bedrooms = $clsProperty->getItems('_BEDROOM', 0, $field);
	//$list_directions = $clsProperty->getItems('_DIRECTION', 0, $field);
	$list_bedrooms = $clsProperty->getCacheItems('_BEDROOM');
	$list_directions = $clsProperty->getCacheItems('_DIRECTION');
	$list_sop_type = $clsProperty->getCacheItems('_SOPTYPE');
	$smarty->assign('list_sop_type', $list_sop_type);
	###
	$list_range_floors = $list_range_axis = array();
	for($i=1; $i<=40; $i += 5){
		$list_range_floors[] = sprintf('%s-%s', $i, $i+4);
	}
	###
	$smarty->assign('list_bedrooms', $list_bedrooms);
	$smarty->assign('list_directions', $list_directions);
	$smarty->assign('list_range_floors', $list_range_floors);
	###
	$get_user_id = Input::get("user",0);
	$get_type_id = Input::get("type",0);
	$get_project_id = Input::get("project",0);
	$get_block_id = Input::get("block",0);
	$get_price_min = (int) Input::get("price_min",0);
	$get_price_max = (int) Input::get("price_max",50000000);
	$get_area_min = (int) Input::get("area_min",0);
	$get_area_max = (int) Input::get("area_max",500);
	$get_building_ids = Input::get("building","");
	$get_bedroom_ids = Input::get("bedroom","");
	$get_direction_ids = Input::get("direction","");
	$get_floor_ranges = Input::get("floor_range","");
	$get_price_range = Input::get("price_range","");
	$get_approved = Input::get("approved","");
	$sort_by = Input::get("sort_by","date_desc");
	$smarty->assign('get_user_id', $get_user_id);
	$smarty->assign('get_type_id', $get_type_id);
	$smarty->assign('get_project_id', $get_project_id);
	$smarty->assign('get_block_id', $get_block_id);
	$smarty->assign('get_price_min', $get_price_min);
	$smarty->assign('get_price_max', $get_price_max);
	$smarty->assign('get_area_min', $get_area_min);
	$smarty->assign('get_area_max', $get_area_max);
	$smarty->assign('get_bedroom_ids', $clsISO->getArrayByText($get_bedroom_ids));
	$smarty->assign('get_building_ids', $clsISO->getArrayByText($get_building_ids));
	$smarty->assign('get_direction_ids', ($get_direction_ids !="") ? $clsISO->getArrayByText($get_direction_ids) : array());
	$smarty->assign('get_floor_ranges', $clsISO->getArrayByText($get_floor_ranges));
	$smarty->assign('get_price_range', $get_price_range);
	$smarty->assign('get_approved', $get_approved);

	if($sort_by == "date_desc"){
		$txt_sort = $clsISO->makeIcon('bx-sort-up', 'Mới nhất');
	}else if($sort_by == "date_asc"){
		$txt_sort = $clsISO->makeIcon('bx-sort-down', 'Cũ nhất');
	}else if($sort_by == "price_asc"){
		$txt_sort = $clsISO->makeIcon('bx-sort-up', 'Giá tăng dần');
	}else if($sort_by == "price_desc"){
		$txt_sort = $clsISO->makeIcon('bx-sort-down', 'Giá giảm dần');
	}
	$smarty->assign('txt_sort', $txt_sort);
	
	$cond_project = "";
	if($get_type_id == _TYPE_HIGHLEVEL) {
		$cond_type = "	AND parent_id='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
		$cond_stock_type = " AND stock_type='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
		$cond_building = " AND property_type = '_BUILDING'";
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($get_type_id == _TYPE_LOWFLOOR) {
		$cond_type = "	AND parent_id='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
		$cond_stock_type = " AND stock_type='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
		$cond_building = " AND property_type = '_RANGE'";
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}

	$list_project = $clsProject->getAll("`is_trash`=0 AND project_id IN (".implode(",",_PROJECT_OCEAN_CITY).")" . $cond_project, $clsProject->pkey.',title');
	$smarty->assign('list_project', $list_project);

 	$list_buildings = array();
	if(!empty($get_block_id)) {
		$list_buildings = $clsProperty->getAllCache("for_id='".$get_block_id."'".$cond_building, "{$clsProperty->pkey},title");
	}	
	$smarty->assign('list_buildings', $list_buildings);

	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("for_id='".$get_project_id."'".$cond_type, $field);
	$smarty->assign('list_blocks', $list_blocks);
	###
	$is_search_price = $is_search_area = 0;
	if($get_price_min > 0 || $get_price_max < 50000000){
		$is_search_price = 1;
	}
	if($get_area_min > 0 || $get_area_max < 500){
		$is_search_area = 1;
	}
	$smarty->assign('is_search_area', $is_search_area);
	$smarty->assign('is_search_price', $is_search_price);
	
	$oProfile = array();
	if($get_user_id > 0){
		$field = "full_name,first_name,last_name";
		$oProfile = $clsProfile->getOne($get_user_id, $field);
		$oProfile[$clsProfile->pkey] = $get_user_id;
	}
	$smarty->assign('oProfile', $oProfile);

	$lstPriceRangeLeasing = $clsProperty->getCacheItems("_PRICE_RANGE_LEASING");
	foreach($lstPriceRangeLeasing as $key => $val) {
		$more_information_leasing = $clsISO->to_array_json($val['more_information']);
		$lstPriceRangeLeasing[$key]['min'] = (!empty($more_information_leasing['min'])) ? $more_information_leasing['min'] : 0;
		$lstPriceRangeLeasing[$key]['max'] = (!empty($more_information_leasing['max'])) ? $more_information_leasing['max'] : 0;	
		unset($more_information_leasing);
	}
	$lstAreaRange = $clsProperty->getCacheItems("_AREA_RANGE");
	foreach($lstAreaRange as $key => $val) {
		$more_information_area = $clsISO->to_array_json($val['more_information']);
		$lstAreaRange[$key]['min'] = (!empty($more_information_area['min'])) ? $more_information_area['min'] : 0;
		$lstAreaRange[$key]['max'] = (!empty($more_information_area['max'])) ? $more_information_area['max'] : 0;	
		unset($more_information_area);
	}
	$smarty->assign('lstPriceRangeLeasing', $lstPriceRangeLeasing);
	$smarty->assign('lstAreaRange', $lstAreaRange);
?>