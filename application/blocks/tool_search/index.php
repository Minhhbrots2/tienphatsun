<?php 
	global $smarty, $clsISO, $dbconn, $profile_id;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	
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
	$_ss_search = array();
	$s = Input::get("s",0);
	if(vnSessionExist('_ss_search') && $s == 0){
		$_ss_search = vnSessionGetVar('_ss_search');
	}
	$project_id = Input::get("project_id",0);
	$_ss_search['project_id'] = !empty($project_id) ? $project_id : $_ss_search['project_id'];
	$block_ids = Input::get("block_ids","");
	$_ss_search['blocks_ids'] = !empty($block_ids) ? explode(",",$block_ids) : $_ss_search['blocks_ids'];
	$building_ids = Input::get("building_ids","");
	$_ss_search['building_ids'] = !empty($building_ids) ? explode(",",$building_ids) : $_ss_search['building_ids'];
	$bedroom_ids = Input::get("bedroom_ids","");
	$_ss_search['bedroom_ids'] = !empty($bedroom_ids) ? explode(",",$bedroom_ids) : $_ss_search['bedroom_ids'];
	$floor_range = Input::get("floor_range","");
	$_ss_search['floor_range'] = !empty($floor_range) ? explode(",",$floor_range) : $_ss_search['floor_range'];
	$direction_ids = Input::get("direction_ids","");
	$_ss_search['direction_ids'] = !empty($direction_ids) ? explode(",",$direction_ids) : $_ss_search['direction_ids'];
	$agency_ids = Input::get("agency_ids","");
	$_ss_search['agency_ids'] = !empty($agency_ids) ? explode(",",$agency_ids) : $_ss_search['agency_ids'];
	$axis_ids = Input::get("axis_ids","");
	$_ss_search['axis_ids'] = !empty($axis_ids) ? explode(",",$axis_ids) : $_ss_search['axis_ids'];
	$_ss_project_id = isset($_ss_search['project_id']) && !empty($_ss_search['project_id']) 
		? $_ss_search['project_id'] : _PROJECT_VHOP2_ID;
	$_ss_blocks_ids = isset($_ss_search['blocks_ids']) && !empty($_ss_search['blocks_ids']) 
		? $_ss_search['blocks_ids'] : array();
	$_ss_building_ids = isset($_ss_search['building_ids']) && !empty($_ss_search['building_ids']) 
		? $_ss_search['building_ids'] : array();
	$_ss_bedroom_ids = isset($_ss_search['bedroom_ids']) && !empty($_ss_search['bedroom_ids']) 
		? $_ss_search['bedroom_ids'] : array();
	$_ss_direction_ids = isset($_ss_search['direction_ids']) && !empty($_ss_search['direction_ids']) 
		? $_ss_search['direction_ids'] : array();
	$_ss_agency_ids = isset($_ss_search['agency_ids']) && !empty($_ss_search['agency_ids']) 
		? $_ss_search['agency_ids'] : array();
	$_ss_type_ids = isset($_ss_search['type_ids']) && !empty($_ss_search['type_ids']) 
		? $_ss_search['type_ids'] : array();
	$_ss_price_min = isset($_ss_search['price_min']) && !empty($_ss_search['price_min']) 
		? $_ss_search['price_min'] : 0;
	$_ss_price_max = isset($_ss_search['price_max']) && !empty($_ss_search['price_max']) 
		? $_ss_search['price_max'] : 30*1000000000;
	$_ss_area_min = isset($_ss_search['area_min']) && !empty($_ss_search['area_min']) 
		? $_ss_search['area_min'] : 0;
	$_ss_area_max = isset($_ss_search['area_max']) && !empty($_ss_search['area_max']) 
		? $_ss_search['area_max'] : 500;
	$_ss_floor_range = isset($_ss_search['floor_range']) && !empty($_ss_search['floor_range']) 
		? $_ss_search['floor_range'] : array();
	$_ss_axis_ids = isset($_ss_search['axis_ids']) && !empty($_ss_search['axis_ids']) 
		? $_ss_search['axis_ids'] : array();
	// $clsISO->print_pre($_ss_axis_ids); die();
	$smarty->assign('_ss_project_id', $_ss_project_id);
	$smarty->assign('_ss_blocks_ids', $_ss_blocks_ids);
	$smarty->assign('_ss_building_ids', $_ss_building_ids);
	$smarty->assign('_ss_bedroom_ids', $_ss_bedroom_ids);
	$smarty->assign('_ss_direction_ids', $_ss_direction_ids);
	$smarty->assign('_ss_agency_ids', $_ss_agency_ids);
	$smarty->assign('_ss_price_min', $_ss_price_min);
	$smarty->assign('_ss_price_max', $_ss_price_max);
	$smarty->assign('_ss_area_min', $_ss_area_min);
	$smarty->assign('_ss_area_max', $_ss_area_max);
	$smarty->assign('_ss_floor_range', $_ss_floor_range);
	$smarty->assign('_ss_type_ids', $_ss_type_ids);
	$smarty->assign('_ss_axis_ids', $_ss_axis_ids);
	
	$field = "{$clsProperty->pkey},title,more_information";
	$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='".$_ss_project_id."' 
		AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' order by `order_no` DESC, JSON_EXTRACT(`more_information`,\"$.on_sale\") DESC", $field);
	$smarty->assign('list_blocks', $list_blocks);
	$is_fund_type = 0;
	if(!empty($_ss_blocks_ids)){
		$list_ss_buildings = array();
		foreach($_ss_blocks_ids as $block_id){
			$list_ss_buildings[$block_id] = $clsProperty->getAll("property_type='_BUILDING' and for_id='".$block_id."'", $field);
		}
		if(!empty($list_ss_buildings)) {
			foreach ($list_ss_buildings[$block_id] as $key => $_oBuilding) {
				$more_information_building = $clsISO->to_array_json($_oBuilding['more_information']);
				$floor_hierarchy = !empty($more_information_building['floor_hierarchy']) ? $more_information_building['floor_hierarchy'] : array();
				if(!empty($floor_hierarchy)) {
					$is_fund_type = 1;
					break;
				}
			}
		}
		$smarty->assign('list_ss_buildings', $list_ss_buildings);
	} else {
		$list_buildings = $clsProperty->getAll("property_type='_BUILDING' and for_id='"._BLOCK_DEF_ID."'", $field);
		if(!empty($list_buildings)) {
			foreach ($list_buildings as $key => $_oBuilding) {
				$more_information_building = $clsISO->to_array_json($_oBuilding['more_information']);
				$floor_hierarchy = !empty($more_information_building['floor_hierarchy']) ? $more_information_building['floor_hierarchy'] : array();
				if(!empty($floor_hierarchy)) {
					$is_fund_type = 1;
					break;
				}
			}
		}
		$smarty->assign('list_buildings', $list_buildings);
	}
	$smarty->assign('is_fund_type', $is_fund_type);
	#
	$lstAgency = $clsProperty->getArraySearchByKey("_AGENCY");
	foreach($lstAgency as $key => $val) {
		$info_agency = !empty($val["more_information"]["info_agency"]) ? $val["more_information"]["info_agency"] : [];
		$branch_office = $project = [];
		if(!empty($info_agency["branch_office"])) {
			$branch_office = $info_agency["branch_office"];
			foreach ($branch_office as $k_branch => $v_branch) {
				if(empty($v_branch)) {
					unset($branch_office[$k_branch]);
				}
			}
		}
		if(!empty($info_agency["project"])) {
			$project = $info_agency["project"];
			foreach ($project as $k_project => $v_project) {
				if(empty($v_project["title"]) && empty($v_project["project_manager"]) && empty($v_project["project_manager"])) {
					unset($project[$k_project]);
				}
			}
		}
		if(!empty($info_agency["head_office"]) || !empty($branch_office) || !empty($project)) {
			$lstAgency[$key]["is_info"] = 1;
		}else{
			$lstAgency[$key]["is_info"] = 0;
		}
	}
	$smarty->assign('lstAgency', $lstAgency);
?>