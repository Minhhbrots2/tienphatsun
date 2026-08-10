<?php 
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$list_range_floors = $list_range_axis = array();
	for($i=1; $i<=40; $i += 5){
		$list_range_floors[] = sprintf('%s-%s', $i, $i+4);
	}
	for($i=1; $i<=40; $i++){
		if($i==4){
			$list_range_axis[] = '5A';
			$list_range_axis[] = '05A';
		} else if($i==7){
			$list_range_axis[] = '8A';
			$list_range_axis[] = '08A';
		} else if($i==13){
			$list_range_axis[] = '12A';
		} else if($i==14){
			$list_range_axis[] = "15A";
		} else if($i==17){
			$list_range_axis[] = "18A";
		}
		$axis = $clsISO->parseNumber($i);
		$list_range_axis[] = $axis;
	}
	$smarty->assign('list_range_axis', $list_range_axis);
	$smarty->assign('list_range_floors', $list_range_floors);
	###
	$_ss_exclusive_search = array();
	if(vnSessionExist('_ss_exclusive_search')){
//		$_ss_exclusive_search = vnSessionGetVar('_ss_exclusive_search');
	}
	$_ss_project_id = (int) $core->get_field($_ss_exclusive_search, 'project_id', 0);
	$_ss_blocks_ids = $core->get_field($_ss_exclusive_search, 'blocks_ids', []);
	$_ss_building_ids = $core->get_field($_ss_exclusive_search, 'building_ids', []);
	$_ss_bedroom_ids = $core->get_field($_ss_exclusive_search, 'bedroom_ids', []);
	$_ss_direction_ids = $core->get_field($_ss_exclusive_search, 'direction_ids', []);
	$_ss_type_ids = $core->get_field($_ss_exclusive_search, 'type_ids', []);
	$_ss_price_min = $core->get_field($_ss_exclusive_search, 'price_min', 0);
	$_ss_price_max = $core->get_field($_ss_exclusive_search, 'price_max', 50*1000000000);
	$_ss_floor_range = $core->get_field($_ss_exclusive_search, 'floor_range', []);
	$_ss_axis_ids = $core->get_field($_ss_exclusive_search, 'axis_ids', []);
	// $clsISO->print_pre($_ss_axis_ids); die();
	$smarty->assign('_ss_project_id', $_ss_project_id);
	$smarty->assign('_ss_blocks_ids', $_ss_blocks_ids);
	$smarty->assign('_ss_building_ids', $_ss_building_ids);
	$smarty->assign('_ss_bedroom_ids', $_ss_bedroom_ids);
	$smarty->assign('_ss_direction_ids', $_ss_direction_ids);
	$smarty->assign('_ss_price_min', $_ss_price_min);
	$smarty->assign('_ss_price_max', $_ss_price_max);
	$smarty->assign('_ss_floor_range', $_ss_floor_range);
	$smarty->assign('_ss_type_ids', $_ss_type_ids);
	$smarty->assign('_ss_axis_ids', $_ss_axis_ids);
	###
	$field = "{$clsProject->pkey},`title`,`code`";
	$arr_project_ins = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID);
	$lst_projects = $clsProject->getAll("`is_trash`=0 and `is_menu`=1", $field);
	###
	$list_ss_buildings = $list_blocks = array();
	$field = "{$clsProperty->pkey},title,more_information";
	if($_ss_project_id > 0){
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
		AND `for_id`='{$_ss_project_id}' AND JSON_EXTRACT(`more_information`,\"$.on_sale\")=1 ORDER BY `order_no` DESC, JSON_EXTRACT(`more_information`,\"$.on_sale\") DESC", $field);
	}
	if(!empty($_ss_blocks_ids)){
		foreach($_ss_blocks_ids as $block_id){
			$list_ss_buildings[$block_id] = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BUILDING' 
			AND `for_id`='{$block_id}' ORDER BY `order_no` ASC", $field);
		}
	}
	$smarty->assign('lst_projects', $lst_projects);
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('list_ss_buildings', $list_ss_buildings);
	
?>