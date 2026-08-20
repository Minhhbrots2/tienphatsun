<?php

	global $core,$smarty,$list_projects,$profile_id,$oneProfile,$oneProject,$project_id,$building_id,$mod,$act,$dbconn;

	$clsStock = new Stock();

	$clsProject = new Project();

	$clsProperty = new Property();

	$smarty->assign('clsProject',$clsProject);

	$smarty->assign('clsProperty',$clsProperty);

	if($mod == 'tool' && $act == 'favourite'){

		$ms_code = Input::get('ms_code');

		$field = "stock_type,building_id,block_id,project_id";

		$oneStock = $clsStock->getByCond("`ms_code`='{$ms_code}'", $field);

		$project_id = $oneStock['project_id'];

		$block_id = $oneStock['block_id'];

		$building_id = $oneStock['building_id'];

		$stock_type = $oneStock['stock_type'];

		$oneBuilding = $clsProperty->getOne($building_id, "for_id,more_information");

	} else {

		$stock_type = (int) Input::get('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE); 

		$project_id = (int) Input::get('project_id', 0);

		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){

			$building_id = (int) Input::get('building_id', 0);

		} else {

			$building_id = 0;

		}

	}

	$oneProject = $clsProject->getOne($project_id);

	$more_information = $oneProject['more_information'];

	$more_information = $clsISO->to_array_json($more_information);

	$bgcolor = !empty($more_information["bgcolor"]) ? $more_information["bgcolor"] : "";

	$map_la = !empty($more_information["map_la"]) ? $more_information["map_la"] : "";

	$map_lo = !empty($more_information["map_lo"]) ? $more_information["map_lo"] : "";

	$smarty->assign('map_la',$map_la);

	$smarty->assign('map_lo',$map_lo);

	$oneProject["logo"] = !empty($more_information["logo"]) ? $more_information["logo"] : "";

	

	$block_id = (int)Input::get("block_id",0);



	$list_block_type = $oneProject['list_block_type'];

	$arr_block_type = $clsISO->getArrayByTextSlash($list_block_type);

	$smarty->assign('arr_block_type', $arr_block_type);
	$is_lowfloor = @in_array(_BLOCK_TYPE_LOWFLOOR_SALE, $arr_block_type) ? 1 : 0;
	$smarty->assign('is_lowfloor', $is_lowfloor );

	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){

		$vr_link = $core->is_empty($more_information, 'vr_link') 

			? $more_information['vr_link'] : $vr_link;

		$vr_source = $core->is_empty($more_information, 'vr_source') 

			? $more_information['vr_source'] : $vr_source;

		$block_is_project = 0;		

		if(!empty($block_id)) {

			$oneBlock = $clsProperty->getArraySearchByKey("_BLOCK",$block_id);

			$more_info_block = $oneBlock["more_information"];

			$bgcolor = !empty($more_info_block["bgcolor"]) ? $more_info_block["bgcolor"] : $bgcolor;

			$vr_link = $core->is_empty($more_info_block, 'vr_link') ? $more_info_block['vr_link'] : $vr_link;

			if(!empty($more_info_block["is_project"])) {

				$block_is_project = 1;

			}

			$smarty->assign('oneBlock',$oneBlock);

			$smarty->assign('block_id',$block_id);

			$smarty->assign('block_is_project',$block_is_project);

		}

	} else {

		$vr_link = $clsISO->getValue("vr_link", $more_information);

		

		$block_id = !empty($oneBuilding['for_id']) ? $oneBuilding['for_id'] : $block_id;	

			

		$building_information = $oneBuilding['more_information'];

		$building_information = $clsISO->to_array_json($building_information);

		$smarty->assign('building_information',$building_information);

		$oneBlock = $clsProperty->getOne($block_id, "title,more_information,parent_id");

		$block_information = $oneBlock['more_information'];

		$block_information = $clsISO->to_array_json($block_information);

		$vr_link = $core->is_empty($block_information, 'vr_link') 

			? $block_information['vr_link'] : $vr_link;

		$vr_source = $core->is_empty($block_information, 'vr_source') 

			? $block_information['vr_source'] : "";

		$vr_link = $core->is_empty($building_information, 'vr_link') 

			? $building_information['vr_link'] : $vr_link;

		$vr_source = $core->is_empty($building_information, 'vr_source') 

			? $building_information['vr_source'] : $vr_source;

		$bgcolor = !empty($block_information["bgcolor"]) ? $block_information["bgcolor"] : $bgcolor;

		

		$block_is_project = 0;

		if(!empty($block_id)) {

			if(!empty($block_information["is_project"]) && $oneBlock["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE) {

				$block_is_project = 1;

			}

			$smarty->assign('block_is_project',$block_is_project);

		}

		$smarty->assign('block_id',$block_id);

		$smarty->assign('oneBlock',$oneBlock);

		$on_sale = !empty($block_information["on_sale"]) ? $block_information["on_sale"] : 0;

		$smarty->assign('on_sale',$on_sale);

		$smarty->assign('bgcolor',$bgcolor);

		#

		if(($act == 'stock' || $act == 'map') && !empty($building_id)) {

			$cond_stock = "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `project_id`='{$project_id}' AND `building_id`='{$building_id}' AND `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."'";

			if(!$clsISO->checkPermission('view_stock_globe')){

				$cond_stock.= " and `show_website` like '%|user.fh|%'";

			}

			$total_stocks = $clsStock->countItem($cond_stock);

			$smarty->assign('total_stocks',$total_stocks);

		}

		#

		if(!empty($block_id) && $oneBlock["parent_id"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {

			$clsCache = new Cache();

			if(!empty($list_projects)) {

				foreach ($list_projects as $key => $_oProject) {

					if($_oProject["project_id"] == $project_id) {

						if(!empty($_oProject["list_blocks"])) {

							foreach ($_oProject["list_blocks"] as $k => $v) {

								if($v["property_id"] == $block_id) {

									$lstBuildingBl = $v["list_menu_buildings"];

									break;

								}

							}

						}

						break;

					}

				}

			}else{

				$lstBuildingBl = $clsProperty->getAllCache("`property_type`='_BUILDING' AND `for_id`='{$block_id}' AND (JSON_EXTRACT(`more_information`,\"$.is_menu\")='1' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0') AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.on_sale') IS NOT NULL)","`{$clsProperty->pkey}`,`title`,`property_code`");

				foreach ($lstBuildingBl as $key => $val) {

					$lstBuildingBl[$key]["building_id"] = $val[$clsProperty->pkey];

					$lstBuildingBl[$key]["title"] = sprintf('%s(%s)', $val['title'], $val['property_code']);

					$lstBuildingBl[$key]["link"] = sprintf('/project/p%s/b%s.html', $project_id, $val[$clsProperty->pkey]);

				}

			}

			$smarty->assign('lstBuildingBl',$lstBuildingBl);

			$smarty->assign('block_information',$block_information);

		}

	}

	$smarty->assign('vr_link',$vr_link);

	$smarty->assign('vr_source',$vr_source);

	$smarty->assign('stock_type',$stock_type);

	###
	if($oneBlock["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE) {
		$link_stock = $clsProject->getLink($project_id, 0,0);	
	}else{
		$link_stock = $clsProject->getLink($project_id, $building_id,$block_id);
	}
	

	$smarty->assign('link_stock',$link_stock);

	###

	$cond = "`is_trash`=0 and `parent_id`='0' and `property_type`='_CATEGORY_DOCS'";

	$list_category_docs = $clsProperty->getAllCache("{$cond} order by `order_no` ASC","{$clsProperty->pkey},title,image");

	$smarty->assign('list_category_docs',$list_category_docs);

	$smarty->assign('project_id',$project_id);

	$smarty->assign('oneProject', $oneProject);

	$arr_project = [_PROJECT_VHOP3_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID, _PROJECT_VWC_ID];

	$smarty->assign('arr_project',$arr_project);



	#

	$clsProjectMeta = new ProjectMeta();

	$is_model = $is_handoverSpecs = 0;

	$cond_project_meta = "`is_trash`='0'";

	if($show == 'building'){

		$cond_project_meta.= " AND (`type`='building' 

			OR `type`='block'

		) AND (`building_id` LIKE '%|{$building_id}|%' OR `block_ids` LIKE '%|{$block_id}|%')";

	}else if($show == 'block' || !empty($block_is_project)) {

		$cond_project_meta.= " AND `type`='block' AND `block_ids` LIKE '%|{$block_id}|%'";

	}else{	

		$cond_belong_project = $clsProjectMeta->condByProject($project_id);

		$cond_project_meta .= " AND ((type = 'project' AND {$cond_belong_project})

			OR (type = 'block' AND  {$cond_belong_project} AND `block_ids` <> ''

			) OR (type = 'building' AND {$cond_belong_project} AND `block_ids` <> '' AND `building_ids` <> ''

		))";

	}

	$is_model = $clsProjectMeta->countItem($cond_project_meta." AND JSON_EXTRACT(`more_information`,\"$.is_model\")='1'");

	$is_handoverSpecs = $clsProjectMeta->countItem($cond_project_meta." AND JSON_EXTRACT(`more_information`,\"$.is_handoverSpecs\")='1'");

	$smarty->assign('is_model',$is_model);

	$smarty->assign('is_handoverSpecs',$is_handoverSpecs);

	# hero header v2: CDT + thong so toa (chip nao thieu du lieu thi tpl tu an)
	$investor_name = "";
	$investor_id = (int) $clsISO->getValue("investor_id", $more_information);
	if($investor_id > 0){
		$oneInvestor = $clsProperty->getOne($investor_id, "title");
		$investor_name = !empty($oneInvestor['title']) ? $oneInvestor['title'] : "";
	}
	$smarty->assign('investor_name', $investor_name);

	$sh_floor = $sh_house = $sh_elevator = 0;
	if(!empty($building_information) && is_array($building_information)){
		$sh_floor = (int) $clsISO->getValue("number_floor", $building_information);
		$sh_house = (int) $clsISO->getValue("number_house", $building_information);
		$sh_elevator = (int) $clsISO->getValue("number_of_elevator", $building_information);
	}
	$sh_total_units = ($sh_floor > 0 && $sh_house > 0) ? $sh_floor * $sh_house : 0;
	$smarty->assign('sh_floor', $sh_floor);
	$smarty->assign('sh_house', $sh_house);
	$smarty->assign('sh_elevator', $sh_elevator);
	$smarty->assign('sh_total_units', $sh_total_units);

?>