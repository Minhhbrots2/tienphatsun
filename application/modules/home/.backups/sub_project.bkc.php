<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function get_item_next($source, $curr){
	if(!empty($source)){
		$pos = 0;
		for($i=0; $i<count($source); $i++){
			if($source[$i]== $curr){
				$pos = $i;
				break;
			}
		}
		$nex_pos = $pos+1;
		if(@isset($source[$nex_pos])){
			return $source[$nex_pos];
		} else {
			return "";
		}
	}
	return "";
}
function project_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$show = Input::get('show','project');
	$project_id = (int) Input::get('project_id', 0);
	$assign_list["show"] = $show;
	$assign_list["project_id"] = $project_id;
	#
	$oneProject = $clsProject->getOne($project_id);
	$block_type = $oneProject['block_type'];
	$assign_list["block_type"] = $block_type;
	$assign_list["oneProject"] = $oneProject;
	if($show=='block'){
		$for_id = $block_id = (int) Input::get('block_id', 0);
		$oneBlock = $clsProperty->getOne($block_id);
		$title_page = 'Phân khu '. $oneBlock['title'];
		$more_information = $oneBlock['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		#
		$assign_list["block_id"] = $block_id;
		$assign_list["oneBlock"] = $oneBlock;
		$assign_list["more_information"] = $more_information;
		if($block_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image";
			$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' and `for_id`='{$block_id}' order by `reg_date` ASC", $field);
			$assign_list["list_buildings"] = $list_buildings;
			// $clsISO->print_pre($list_buildings); die();
		} else {
			$field = "{$clsProperty->pkey},property_code,title,intro,more_information";
			$list_blocks = $clsProperty->getAll("for_id='{$project_id}' order by order_no ASC", $field);
			$assign_list["list_blocks"] = $list_blocks;
		}
		$list_props = $clsProjectMeta->getAll("`type`='{$show}' and `for_id`='{$for_id}' order by `order_no` ASC");
		if(!empty($list_props)){
			foreach($list_props as $key => $val){
				$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
				$list_props[$key]['is_driver'] = $is_driver;
				$list_props[$key]['link'] = $clsISO->getIframeUrl($val['content']);
				$list_props[$key]['reg_date_f'] = $clsISO->getTimeAgo($val['reg_date']);
			}
		}	
	}  else if($show=='building'){
		$block_id = (int) Input::get('block_id', 0);
		$for_id = $building_id = (int) Input::get('building_id', 0);
		$oneBlock = $clsProperty->getOne($block_id,"title");
		$oneBuilding = $clsProperty->getOne($building_id);
		$title_page = sprintf('Tòa %s, %s', $oneBuilding['title'], $oneBlock['title']);
		$more_information = $oneBuilding['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		$assign_list["block_id"] = $block_id;
		$assign_list["building_id"] = $building_id;
		$assign_list["oneBuilding"] = $oneBuilding;
		$assign_list["more_information"] = $more_information;
		// $clsISO->print_pre($oneBuilding); die();
		$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image";
		$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
		and `for_id`='{$block_id}' and {$clsProperty->pkey}<>'{$building_id}' order by `reg_date` ASC", $field); 
		$assign_list["list_buildings"] = $list_buildings;
		###
		$list_props = $clsProjectMeta->getAll("((`type`='{$show}' and `for_id`='{$for_id}') or (`type`='block' and `for_id`='{$block_id}')) 
		order by type DESC, `order_no` ASC");
		if(!empty($list_props)){
			foreach($list_props as $key => $val){
				if($val['type']=='building'){
					$list_props[$key]['title'] = sprintf('%s %s', $val['title'], $oneBuilding['title']);
				} else {
					$list_props[$key]['title'] = sprintf('%s %s', $val['title'], $oneBlock['title']);
				}
				$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
				$list_props[$key]['is_driver'] = $is_driver;
				$list_props[$key]['link'] = $clsISO->getIframeUrl($val['content']);
				$list_props[$key]['reg_date_f'] = $clsISO->getTimeAgo($val['reg_date']);
			}
		}	
	} else {
		$for_id = $project_id;
		$more_information = $oneProject['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		$assign_list["more_information"] = $more_information;
		// $clsISO->print_pre($more_information); die();
		$title_page = $oneProject['title'];
		#
		$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image";
		$list_blocks = $clsProperty->getAll("for_id='{$project_id}' order by order_no ASC", $field);
		$assign_list["list_blocks"] = $list_blocks;
		###
		$list_props = $clsProjectMeta->getAll("`type`='{$show}' and `for_id`='{$for_id}' order by `order_no` ASC");
		if(!empty($list_props)){
			foreach($list_props as $key => $val){
				$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
				$list_props[$key]['is_driver'] = $is_driver;
				$list_props[$key]['link'] = $clsISO->getIframeUrl($val['content']);
				$list_props[$key]['reg_date_f'] = $clsISO->getTimeAgo($val['reg_date']);
			}
		}	
	}
	$assign_list["list_props"] = $list_props;
	/*=============Title & Description Page==================*/
	$title_page = $title_page . ' - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_project(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$loggedIn,$list_projects;
	
	$arr_project = [_PROJECT_VHOP3_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID,_PROJECT_VWC_ID];
	$assign_list["arr_project"] = $arr_project;
	// $clsISO->print_pre($list_projects); die();
	/*=============Title & Description Page==================*/
	$title_page = 'Thông tin dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$loggedIn,$oneBuilding;
	$clsStock = new Stock();
	$clsStockShape = new StockShape();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$clsPolicy = new Policy();
	$assign_list["clsStock"] = $clsStock;
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	###
	$permiss_view_stock_resource = $clsISO->checkPermission('view_stock_resource');
	$assign_list["permiss_view_stock_resource"] = $permiss_view_stock_resource;
	###
	$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
	$list_status = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='_STATUS' 
		and `{$clsProperty->pkey}`<>'"._STOCK_STATUS_NON_ID."' and {$clsProperty->pkey}<>"._STOCK_STATUS_INSTOCK_ID." 
		order by order_no ASC", $field);
	$total_status = !empty($list_status) ? count($list_status) : 0;
	$status_bgcolor_arrs = $status_textcolor_arrs = array();
	if(!empty($list_status)){
		foreach($list_status as $key => $val){
			$status_bgcolor_arrs[$val[$clsProperty->pkey]] = $val['bgcolor'];
			$status_textcolor_arrs[$val[$clsProperty->pkey]] = $val['textcolor'];
		}
	}
	$assign_list["list_status"] = $list_status;
	$assign_list["status_bgcolor_arrs"] = $status_bgcolor_arrs;
	$assign_list["status_textcolor_arrs"] = $status_textcolor_arrs;
	###
	$project_id = (int) Input::get('project_id', 0);
	$block_type = (int) Input::get('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	###	
	$list_agency_cached_VIN = $list_agency_cached_MWF = $list_agency_cached_MGA = array();
	$list_agency_cached_MLS = $list_agency_cached_LSB = $list_agency_cached_MTS = array();
	
	if($block_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
		$building_id = (int) Input::get('building_id', 0);
		$oneBuilding = $clsProperty->getOne($building_id);
		$block_id = $oneBuilding['for_id']; // BLOCK
		$ag_field = "{$clsProperty->pkey},`more_information`,`is_trash`";
		$tmp = $clsProperty->getAll("`is_locked`=0 and `property_type`='_AGENCY'", $ag_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$agency_id = $val[$clsProperty->pkey];
				if($val['is_trash'] == 1){
					$list_agency_cached_VIN[$agency_id] = 1;
					//new
					if($profile_id == 289) {
						${"arr_agency_".$block_id}[$agency_id] = 1;					
					}else{
						$list_agency_cached_MWF[$agency_id] = 1;
						$list_agency_cached_LSB[$agency_id] = 1;
						$list_agency_cached_MGA[$agency_id] = 1;
						$list_agency_cached_MLS[$agency_id] = 1;
						$list_agency_cached_MTS[$agency_id] = 1;
					}
				} else {
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					// $clsISO->print_pre($more_information); die();	
					//new
					if($profile_id == 289) {
						if(isset($more_information['FH_stock_vin']) && (int) $more_information['FH_stock_vin']==1){
							$list_agency_cached_VIN[$agency_id] = 1;
						}
						if(isset($more_information['FH_'.$agency_id."_".$block_id]) && (int) $more_information['FH_'.$agency_id."_".$block_id]==1){
							${"arr_agency_".$block_id}[$agency_id] = 1;
						}else{
							${"arr_agency_".$block_id}[$agency_id] = 0;
						}
					}else{
						$hide_stock_vin = (int) $core->get_field($more_information, 'hide_stock_vin', 0);
						$hide_stock_mwf = (int) $core->get_field($more_information, 'hide_stock_mwf', 0);
						$hide_stock_lsb = (int) $core->get_field($more_information, 'hide_stock_lsb', 0);
						$hide_stock_mls = (int) $core->get_field($more_information, 'hide_stock_mls', 0);
						$hide_stock_mts = (int) $core->get_field($more_information, 'hide_stock_mts', 0);
						$hide_stock_mga = (int) $core->get_field($more_information, 'hide_stock_mga', 0);
						$list_agency_cached_LSB[$agency_id] = 0;
						$list_agency_cached_MGA[$agency_id] = 0;
						$list_agency_cached_MLS[$agency_id] = 0;
						$list_agency_cached_MTS[$agency_id] = 0;									
						if($block_id == _PROJECT_BLOCK_LSB_ID && $hide_stock_lsb == 1){
							$list_agency_cached_LSB[$agency_id] = 1;
						} else if($block_id == _PROJECT_BLOCK_MLS_ID && $hide_stock_mls == 1){
							$list_agency_cached_MLS[$agency_id] = 1;
						} else if($block_id == _PROJECT_BLOCK_MWF_ID && $hide_stock_in_MWF == 1){
							$list_agency_cached_MWF[$agency_id] = 1;
							$list_agency_cached_VIN[$agency_id] = 0;
						} else if($block_id == _PROJECT_BLOCK_MGA_ID && $hide_stock_mga == 1){
							$list_agency_cached_MGA[$agency_id] = 1;
						} else if($block_id == _PROJECT_BLOCK_MTS_ID && $hide_stock_mts == 1) {
							$list_agency_cached_MTS[$agency_id] = 1;
						} else {
							$list_agency_cached_MWF[$agency_id] = 0;
							if($hide_stock_vin == 1){
								$list_agency_cached_VIN[$agency_id] = 1;
							} else {
								$list_agency_cached_VIN[$agency_id] = 0;
							}
						}
					}
					
				}
			}
		}
		$assign_list["list_agency_cached_VIN"] = $list_agency_cached_VIN;
		//new
		if($profile_id == 289) {
			$assign_list["list_agency_cached"] = ${"arr_agency_".$block_id};				
		}else{
			$assign_list["list_agency_cached_MWF"] = $list_agency_cached_MWF;
			$assign_list["list_agency_cached_LSB"] = $list_agency_cached_LSB;
			$assign_list["list_agency_cached_MLS"] = $list_agency_cached_MLS;
			$assign_list["list_agency_cached_MGA"] = $list_agency_cached_MGA;
			$assign_list["list_agency_cached_MTS"] = $list_agency_cached_MTS;
		}
		
		$bedroom_bgcolor_arrs = $bedroom_textcolor_arrs = array();
		$list_bedroom = $clsProperty->getAllCache("property_type='_BEDROOM' order by order_no ASC", $field);
		if(!empty($list_bedroom)){
			foreach($list_bedroom as $key => $val){
				if(!empty($val['textcolor'])){
					$bedroom_textcolor_arrs[$val[$clsProperty->pkey]] = $val['textcolor'];
				} else {
					$bedroom_textcolor_arrs[$val[$clsProperty->pkey]] = '#287e3f';
				}
				if(!empty($val['bgcolor'])){
					$bedroom_bgcolor_arrs[$val[$clsProperty->pkey]] = $val['bgcolor'];
				} else {
					$bedroom_bgcolor_arrs[$val[$clsProperty->pkey]] = '#287e3f';
				}
			}
		}
		$assign_list["bedroom_bgcolor_arrs"] = $bedroom_bgcolor_arrs;
		$assign_list["bedroom_textcolor_arrs"] = $bedroom_textcolor_arrs;
		#
		$more_information = $oneBuilding['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$hide_row_floor_special = isset($more_information['hide_row_floor_special']) 
			? (int) $more_information['hide_row_floor_special'] : 0;
		$floor_config = isset($more_information['floor_config']) && !empty($more_information['floor_config']) 
			? $more_information['floor_config'] : array();
		$assign_list["more_information"] = $more_information;
		$assign_list["hide_row_floor_special"] = $hide_row_floor_special;
		#tang thu cap
		$floor_hierarchy = $core->get_field($more_information, "floor_hierarchy", []);
		$arr_hide_stock_cross = $core->get_field($more_information, "hide_stock_cross", []);
		#- Ẩn quỹ chéo
		$is_hide_stock_cross = 0;
		if(!empty($arr_hide_stock_cross) && isset($arr_hide_stock_cross[$profile_id])){
			$is_hide_stock_cross = $arr_hide_stock_cross[$profile_id];
		}
		$is_map_dq = isset($more_information['layout_map_FH']) && !empty($more_information['layout_map_FH']) ? 1 : 0;
		// $clsISO->print_pre($more_information); die();
		$hide_row_floor_special = (int) $core->get_field($more_information, "hide_row_floor_special", 0);
		$assign_list["more_information"] = $more_information;
		$assign_list["is_map_dq"] = $is_map_dq;
		$assign_list["is_hide_stock_cross"] = $is_hide_stock_cross;
		$assign_list["hide_row_floor_special"] = $hide_row_floor_special;	
		#
		$block_information = $clsProperty->getOneField('more_information', $block_id);
		$block_information = $clsISO->to_array_json($block_information);
		$vr_link = isset($block_information['vr_link']) && !empty($block_information['vr_link']) 
			? $block_information['vr_link'] : "";
		$assign_list["vr_link"] = $vr_link;
		// $clsISO->print_pre($more_information); die();
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
		$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
		order by ms_date DESC limit 0,1");
		#
		$list_help_links = $clsProjectMeta->getAll("is_trash=0 and ((`type`='building' and `for_id`='{$building_id}') 
		or (`type`='block' and `for_id`='{$block_id}')) order by `type` ASC", "type,for_id,title,content");
		if(!empty($list_help_links)){
			$arr_cached_property = array();
			foreach($list_help_links as $key => $val){
				$type = $val['type'];
				$for_id = $val['for_id'];
				if($for_id==$building_id){
					$arr_cached_property[$for_id] = $oneBuilding['title'];
				} else {
					if(!isset($arr_cached_property[$for_id])){
						$arr_cached_property[$for_id] = $clsProperty->getTitle($for_id);
					}
				}
				$list_help_links[$key]['title'] = sprintf('%s %s', $val['title'], $arr_cached_property[$for_id]);
				$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
				$list_help_links[$key]['is_driver'] = $is_driver;
			}
		}
		$assign_list["list_help_links"] = $list_help_links;
		$assign_list["onePolicy"] = $onePolicy;
		$assign_list["oneBuilding"] = $oneBuilding;
		#
		$floor = isset($more_information['floor']) && !empty($more_information['floor']) 
			? $more_information['floor'] : "";
		$stock_templates = isset($more_information['template']) 
			? $more_information['template'] : array();
		$stock_template_specical = $core->get_field($more_information, "template_specical", []);
		$floor_specical = $core->get_field($more_information, "floor_specical", []);
		$arr_check_floor_specical = $arr_floor_specical = [];
		if(!empty($floor_specical)) {
			foreach ($floor_specical as $key => $val) {
				$tmp = explode(",",$val);
				$arr_floor_specical[$key] = $tmp;
			}	
		}
		$assign_list["floor_specical"] = $floor_specical;
		$assign_list["arr_floor_specical"] = $arr_floor_specical;
		$arr_floors = !empty($floor) ? explode(',', $floor) : array();
		#
		$arr_stocks  = $arr_stocks_specical= $arr_cols  = $arr_cols_specical  = $arr_cells = $arr_status_id_show = array();
		$list_stocks = $clsStock->getAll("`project_id`='{$project_id}' and `building_id`='{$building_id}'");
		if($profile_id==_PROFILE_TECH_ID){
			var_dump($list_stocks); die();
		}
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$code = $val['code'];
				$floor = $val['floor'];
				if(!empty($floor) && !in_array($floor, $arr_floors)){
					$arr_floors[] = $floor;
				}
				#thứ cấp
				$is_fund_type = 0;
				if($clsStock->checkStockFundType($val["stock_id"],$building_id,$val,$oneBuilding)) {
					$is_fund_type = 1;
				}
				$val['is_fund_type'] = $is_fund_type;
				$val['is_dq'] = ($val["status_id"] == _STOCK_STATUS_DQ_ID) ? 1 : 0;
				$arr_cells[$floor][$code] = $val;
				if($val['status_id'] != _STOCK_STATUS_SOLD_ID && !$clsISO->checkItemInArray($val['status_id'],$arr_status_id_show) && !(!empty($val['agency_id']) && ($list_agency_cached_VIN[$val['agency_id']] == 1 || $list_agency_cached_MWF[$val['agency_id']] == 1 || $list_agency_cached_LSB[$val['agency_id']] == 1 ) && !$clsISO->checkPermission('view_stock_hidden'))){
					$arr_status_id_show[] = $val['status_id'];
				}
			}
			@usort($arr_stocks, function($a, $b){
				if((int) $a > (int) $b)
					return 1;
				return -1;
			});
			@usort($arr_floors,  function($a, $b){
				if((int) $a > (int) $b)
					return 1;
				return -1;
			});
		}
		
		$assign_list["arr_status_id_show"] = $arr_status_id_show;
		if(!empty($stock_templates)){
			foreach($stock_templates as $key => $val){
				$arr_stocks[] = $val['code'];
				$arr_cols[$val['code']] = $val;
			}
		}
		if(!empty($stock_template_specical)){
			foreach($stock_template_specical as $key => $val){
				foreach ($val as $k => $v) {
					if(!empty($v['code'])) {
						$arr_stocks_specical[$key][] = $v['code'];
						$arr_cols_specical[$key][$v['code']] = $v;
					}					
				}				
			}
		}
		###
		$floor_service_arrs = $floor_merge_arrs = $floor_merge_header_arrs = array();
		$config_floor = [];
		if(!empty($floor_config)){
			foreach($floor_config as $floor => $val){
				$cell_merge = $val['cell_merge'];
				$is_out_order = !empty($val['is_out_order']) ? $val['is_out_order'] : 0;
				$tmp= !empty($cell_merge) ? explode('|', $cell_merge) : array();
				$cell_merge_arrs = $arr_merge = $cell_col_index = array();
				if(!empty($tmp)){
					$index_plus=0;
					for($i=0; $i<count($tmp); $i++){
						$a = @explode('-', $tmp[$i]);
						$cell_merge_arrs[] = array(
							'start_cell' => $a[0],
							'collspan' => $a[1],
							'rowspan' => $a[2] ? ($a[2] + 1) : 1,
						);
						
						$index_plus += $a[1];
						for($j=$a[0]; $j<($a[0] + $a[1]); $j++) {
							$arr_merge[] = $j;							
							$cell_col_index[$j] = $index_plus;
						}
					}
				}
				$config_floor[$floor] = $arr_merge;
				if((int) $val['is_special']==1){
					$key_template = "";
					foreach ($arr_floor_specical as $key => $arrFloor) {
						if($clsISO->checkItemInArray($floor,$arrFloor)) {
							$key_template = $key;
							break;
						}
					}
					if(!empty($key_template)) {
						$_arr_cols = $arr_cols_specical[$key_template];
						$_arr_stock = $arr_stocks_specical[$key_template];
					}else{
						$_arr_cols = $arr_cols;
						$_arr_stock = $arr_stocks;
					}					
					$floor_merge_arrs[$floor] = array(
						'cell_merge_arrs' => $cell_merge_arrs,
						'cell_col_index' => $cell_col_index,
						'arr_cols' => $_arr_cols,
						'arr_stocks' => $_arr_stock,
						'is_out_order' => $is_out_order,
					);
					if($val['floor_type'] == _FLOOR_TYPE_SERVICE_ID){
						$floor_service_arrs[] = $floor;
					} else if($val['floor_type'] == _FLOOR_TYPE_MERGE_ID) {	
						$next_floor = get_item_next($arr_floors, $floor);
						if(!empty($next_floor)){
							$floor_merge_header_arrs[] = $next_floor;
						}
					}
				}
			}
		}
		if(!empty($floor_merge_arrs)){
			$number_house = $more_information['number_house'];
			foreach($floor_merge_arrs as $floor => $arr){
				$arr_merge_cols = $arr_merge_cols_unsort = $arr_merge_backups_cols = array();
				$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				and `project_id`='{$project_id}' and `building_id`='{$building_id}' and `floor`='{$floor}' order by `code` ASC");
				// $clsISO->print_pre($list_stocks); die();
				if(!empty($list_stocks)){
					$total_stocks = count($list_stocks);
					$total_stock_mis  = $number_house - $total_stocks;
					foreach($list_stocks as $key => $val){
						$code = $val['code'];
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$arr_merge_cols_unsort[] = $code;
						$arr_merge_backups_cols[$code] = array(
							'code' => $code,
							'DT_TT' => $more_information['DT_TT'],
							'bedroom_id' => $val['bedroom_id']
						);
					}
					$route = array('01','02','03','5A','05A','04','05','06','8A','08A','07','08','09','10','11','12'
								   ,'12A','13','15A','14','15','16','17','18A');
					for($i=18; $i<=$number_house; $i++){
						$route[] = $i;
					}
					$tmp = array();
					foreach($route as $val){
						if(in_array($val, $arr_merge_cols_unsort)){
							$tmp[] = $val;
						}
					}
					foreach($tmp as $code){
						$arr_merge_cols[] = $arr_merge_backups_cols[$code];
					}
					$arr_merge_cols[] = array('code' => '_empty');
					unset($list_stocks);
				}
				$floor_merge_arrs[$floor]['arr_merge_cols'] = $arr_merge_cols;
			}
		}
		if($profile_id == 289) {
//			$clsISO->print_pre($arr_floors);
//			$clsISO->print_pre($floor_merge_arrs); die();
		}

//		$clsISO->print_pre($arr_stocks); die();
		$total_stocks = !empty($arr_stocks) ? count($arr_stocks) : 0;
		$assign_list["config_floor"] = $config_floor;
		$assign_list["arr_floors"] = $arr_floors;
		$assign_list["arr_stocks"] = $arr_stocks;
		$assign_list["arr_cols"] = $arr_cols;
		$assign_list["arr_stocks_specical"] = $arr_stocks_specical;
		$assign_list["arr_cols_specical"] = $arr_cols_specical;
		$assign_list["arr_cells"] = $arr_cells;
		$assign_list["block_id"] = $block_id;
		$assign_list["building_id"] = $building_id;
		$assign_list["oneBuilding"] = $oneBuilding;
		$assign_list["total_rowspan"] = $total_stocks;
		$assign_list["floor_service_arrs"] = $floor_service_arrs;
		$assign_list["floor_merge_arrs"] = $floor_merge_arrs;
		$assign_list["floor_merge_header_arrs"] = $floor_merge_header_arrs;
		$title_page = $clsProperty->getTitle($building_id);
		// Show map
		$is_map = 0;
		$tmp = $clsStockShape->getByCond("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' and `building_id`='{$building_id}'", $clsStockShape->pkey);
		$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
			and `for_id`='{$block_id}'", $clsProperty->pkey.",property_code,title");
		$arr_ins = array();
		if(!empty($list_buildings)){
			foreach($list_buildings as $key => $val){
				$arr_ins[] = $val[$clsProperty->pkey];
				$list_buildings[$key]["building_id"] = $val[$clsProperty->pkey];
				$list_buildings[$key]["link"] = sprintf('/project/p%s/b%s.html', $project_id, $val[$clsProperty->pkey]);
			}
			$assign_list["list_buildings"] = $list_buildings;
			unset($list_buildings);
		}
		if(empty($tmp)){	
			$tmp = $clsStockShape->getByCond("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' and `building_id` in (".implode(',', $arr_ins).") and JSON_EXTRACT(`map_configs`,\"$.is_all_block\")=1", $clsStockShape->pkey);
		}
		if(!empty($tmp)){
			$is_map = 1;
		}
		$assign_list["is_map"] = $is_map;
	} else {
		$block_id = Input::get('block_id' , 0);
		$assign_list["block_id"] = $block_id;
		if($block_id > 0){
			$regex = sprintf('%s_%s', $project_id, $block_id);
			$onePolicy = $clsPolicy->getByCond("`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `ms_date`<='".time()."' 
			and `scope_slash` like '%|{$regex}|%' order by ms_date DESC limit 0,1");
		} else {
			$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' 
			and `scope_slash` like '%|".$project_id."_%|%' order by ms_date DESC limit 0,1");
		}
		$assign_list["onePolicy"] = $onePolicy;
		// $clsISO->print_pre($block_id); die();
		$field = "{$clsProperty->pkey},title";
		$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}' 
		and `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` ASC", $field);
		$list_help_links = $clsProjectMeta->getAll("is_trash=0 and (`type`='project' and `for_id`='{$project_id}') order by `type` ASC", "type,for_id,title,content");
		// $clsISO->print_pre($list_help_links); die();
		if(!empty($list_help_links)){
			$arr_cached_property = array();
			foreach($list_help_links as $key => $val){
				$type = $val['type'];
				$for_id = $val['for_id'];
				if($for_id==$building_id){
					$arr_cached_property[$for_id] = $oneBuilding['title'];
				} else {
					if(!isset($arr_cached_property[$for_id])){
						$arr_cached_property[$for_id] = $clsProperty->getTitle($for_id);
					}
				}
				$list_help_links[$key]['title'] = sprintf('%s %s', $val['title'], $arr_cached_property[$for_id]);
				$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
				$list_help_links[$key]['is_driver'] = $is_driver;
			}
		}
		$assign_list["list_help_links"] = $list_help_links;
		###
		$assign_list["list_blocks"] = $list_blocks;
		$title_page = $clsProperty->getTitle($block_id);
		$project_information = $clsProject->getOneField('more_information', $project_id);
		$project_information = $clsISO->to_array_json($project_information);
		$vr_link = isset($project_information['vr_link']) && !empty($project_information['vr_link']) 
			? $project_information['vr_link'] : "";
		$vr_source = isset($project_information['vr_source']) && !empty($project_information['vr_source']) 
			? $project_information['vr_source'] : "";
		$assign_list["vr_link"] = $vr_link;	
		$assign_list["vr_source"] = $vr_source;	
	}
	$assign_list["project_id"] = $project_id;
	$assign_list["block_type"] = $block_type;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng hàng '.$title_page;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function project_load_stock(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsPolicy', $clsPolicy);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$project_id = (int) Input::post('project_id', 0);
	$get_blocks_ids = Input::post('block_ids');
	$get_range_ids = Input::post('range_ids');
	$get_type_ids = Input::post('type_ids');
	$get_status_ids = Input::post('status_ids');
	$get_agency_ids = Input::post('agency_ids');
	$get_home_direction_ids = Input::post('home_direction_ids');
	$get_contract_type_ids = Input::post('contract_type_ids');
	$get_stock_hold_ids = Input::post('stock_hold_ids');
	$get_TCBG_ids = Input::post('TCBG_ids', array());
	$price_min = Input::post('price_min', 0);
	$price_max = Input::post('price_max', 0);
	$price_min = $clsISO->processSmartNumber($price_min);
	$price_max = $clsISO->processSmartNumber($price_max);
	$area_min = (int) Input::post('area_min', 0);
	$area_max = (int) Input::post('area_max', 500);
	###
	$cond = "`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}' 
	and `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `agency_id`>0";
	if($project_id == _PROJECT_VHGG_ID){
		if(!empty($get_range_ids)){
			$cond.= " and (`building_id` in (".implode(',',$get_range_ids)."))";
		} else if(!empty($get_blocks_ids)) {
			$list_range_groups = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $get_blocks_ids).")", "more_information");
			if(!empty($list_range_groups)){
				$list_range_ids = array();
				foreach($list_range_groups as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_ranges = isset($more_information['list_ranges']) 
						? $more_information['list_ranges'] : array();
					if(!empty($list_ranges)){
						foreach($list_ranges as $range_id){
							if(!in_array($range_id, $list_range_ids)){
								$list_range_ids[] = $range_id;
							}
						}
					}
				}
				if(!empty($list_range_ids)){
					$cond.= " and (`building_id` in (".implode(',',$list_range_ids)."))";
				}
			}
		}
	} else {
		if(!empty($get_blocks_ids)) 
			$cond.= " and (`block_id` in (".implode(',',$get_blocks_ids)."))";
		if(!empty($get_range_ids)) 
			$cond.= " and (`building_id` in (".implode(',',$get_range_ids)."))";
	}
	if(!empty($get_type_ids)) 
		$cond.= " and (`type_id` in (".implode(',',$get_type_ids)."))";
	if(!empty($get_status_ids)) 
		$cond.= " and (`status_id` in (".implode(',',$get_status_ids)."))";
	if(!empty($get_agency_ids)) 
		$cond.= " and (`agency_id` in (".implode(',',$get_agency_ids)."))";
	if(!empty($get_home_direction_ids)) 
		$cond.= " and (`home_direction_id` in (".implode(',', $get_home_direction_ids)."))";
	if(!empty($get_contract_type_ids)) 
		$cond.= " and (JSON_EXTRACT(`more_information`,\"$.contract_type_id\") in (".implode(',', $get_contract_type_ids)."))";
	if(!empty($get_stock_hold_ids))
		$cond.= " and (JSON_EXTRACT(`more_information`,\"$.stock_hold_id\") in (".implode(',', $get_stock_hold_ids)."))";
	$cond.= " and (`total_price_vat`>='{$price_min}' and `total_price_vat`<='{$price_max}')";
	$cond.= " and (`DT_TT`>='{$area_min}' and `DT_TT`<='{$area_max}')";
	
	$arrs_TCBG = array(); $html_options_TCBG = "";
	$list_ag_stock = $clsStock->getAll($cond, "more_information");
	if(!empty($list_ag_stock)){
		foreach($list_ag_stock as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$TCBG = isset($more_information['TCBG']) && !empty($more_information['TCBG']) 
				? $more_information['TCBG'] : "";
			if(!empty($TCBG) && !in_array($TCBG, $arrs_TCBG)){
				$arrs_TCBG[] = $TCBG;
				$html_options_TCBG.= sprintf('<option'.(in_array($TCBG, $get_TCBG_ids) ? ' selected' : '').' value="'.$TCBG.'">'.$TCBG.'</option>', $TCBG, $TCBG);
			}
		}
	}
	if(!empty($get_TCBG_ids)){
		$cond.= " AND ("; $ii = 0;
		foreach($get_TCBG_ids as $TCBG){
			$cond.= ($ii==0 ? "" : " or ") . "(JSON_EXTRACT(`more_information`,\"$.TCBG\") like '%{$TCBG}%')";
			++$ii;
		}
		$cond.= ")";
	}	
	###
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 50);
	$total_record = $clsStock->countItem($cond." and `agency_id`<>'"._AGENCY_FH_ID."' 
		and `agency_id`<>'"._AGENCY_NSL_ID."'");
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond  = " limit {$offset},{$per_page}";
	####
	$field = "*,IF(CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.`total_price_early`\")) AS UNSIGNED)>0,CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.`total_price_early`\")) AS UNSIGNED),CAST(`total_price_vat` AS UNSIGNED)) AS `order_no`";
	$list_stocks = array(); $total_fh_stocks = 0;
	$arr_property_cached = $arr_props_cached = $arr_policy_cached = array();
	// $dbconn->debug = true;
	$list_stocks_notin_fh = $clsStock->getAll($cond." and (`agency_id`<>'"._AGENCY_FH_ID."' 
		and `agency_id`<>'"._AGENCY_NSL_ID."') order by `order_no` ASC {$limitCond}", $field);
	// var_dump($list_stocks_notin_fh); die();
	$more = array();
	//	$order_by = " ORDER BY CASE WHEN `total_price_vat` = 0 THEN 1 ELSE 0 END ASC, `total_price_vat` ASC";
	if($current_page == 1){
		$list_stocks_in_fh = $clsStock->getAll($cond." and `agency_id`='"._AGENCY_FH_ID."' 
			order by `order_no` ASC", $field);
		$list_stocks_in_nsl = $clsStock->getAll($cond." and `agency_id`='"._AGENCY_NSL_ID."' 
			order by `order_no` ASC", $field);	
		// $clsISO->print_pre($cond); die();
		if(!empty($list_stocks_in_fh) && !empty($list_stocks_in_nsl)){
			$tmp = $list_stocks_in_fh;
			$list_stocks_in_fh = array_merge($tmp, $list_stocks_in_nsl);
		} else if(!empty($list_stocks_in_nsl) && empty($list_stocks_in_fh)){
			$list_stocks_in_fh = $list_stocks_in_nsl;
		}
		if(!empty($list_stocks_in_fh) && empty($list_stocks_notin_fh)){
			$total_fh_stocks = count($list_stocks_in_fh);
			$list_stocks = $list_stocks_in_fh;
		} else if(empty($list_stocks_in_fh) && !empty($list_stocks_notin_fh)){
			$list_stocks = $list_stocks_notin_fh;
		} else if(!empty($list_stocks_in_fh) && !empty($list_stocks_notin_fh)){
			$total_fh_stocks = count($list_stocks_in_fh);
			$list_stocks = array_merge($list_stocks_in_fh, $list_stocks_notin_fh);
		}
		$total_record += $total_fh_stocks;
		$more['total_record'] = $total_record;
	} else {
		$list_stocks = $clsStock->getAll($cond." and (`agency_id`<>'"._AGENCY_FH_ID."' 
		and `agency_id`<>'"._AGENCY_NSL_ID."') order by `order_no` ASC {$limitCond}", $field);
	}
	
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$type_id = $val['type_id'];
			$block_id = $val['block_id'];
			$status_id = $val['status_id'];
			$agency_id = $val['agency_id'];
			$building_id = $val['building_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(isset($more_information['home_direction_id']) && (int) $more_information['home_direction_id'] > 0){
				$home_direction_id = (int) $more_information['home_direction_id'];
				$list_stocks[$key]['home_direction_id'] = $home_direction_id;
			}
			// $clsISO->print_pre($val); die();
			$total_price_vat = $more_information['total_price_vat'];
			$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
			if(empty($more_information['csbh'])){
				$regex = sprintf('%s_%s', $project_id, $block_id);
				if($arr_policy_cached[$regex]){
					$onePolicy = $arr_policy_cached[$regex];
				} else {
					$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' 
					and `scope_slash` like '%|{$regex}|%' order by `ms_date` DESC limit 0,1");
				}
				if(!empty($onePolicy)){
					$csbh = $onePolicy['ms_date'];
					$more_information['csbh'] = $clsISO->convertTimeToText($csbh);
					$html_sales_policy = '<a target="_blank" href="'.$onePolicy['link_ns'].'">
						'.$clsISO->makeIcon('bx-link-external','Mở link').'
					</a>';
					$html_price_sheets = '<a target="_blank" href="'.$onePolicy['link_ms'].'">
						'.$clsISO->makeIcon('bx-link-external','Mở link').'
					</a>';
				}
			}
			$list_stocks[$key]['html_sales_policy'] = $html_sales_policy;
			$list_stocks[$key]['html_price_sheets'] = $html_price_sheets;
			$list_stocks[$key]['more_information'] = $more_information;
			$contract_type_id = isset($more_information['contract_type_id']) 
				? (int) $more_information['contract_type_id'] : 0;
			$contract_subject_id = isset($more_information['contract_subject_id']) 
				? (int) $more_information['contract_subject_id'] : 0;
			$invest_fund_id = isset($more_information['invest_fund_id']) 
				? (int) $more_information['invest_fund_id'] : 0;
			$sale_status_id = isset($more_information['sale_status_id']) 
				? (int) $more_information['sale_status_id'] : 0;
			$agent_lock_id = isset($more_information['agent_lock_id']) 
				? (int) $more_information['agent_lock_id'] : 0;
			$deposit_agent_id = isset($more_information['deposit_agent_id']) 
				? (int) $more_information['deposit_agent_id'] : 0;
			$bank_second_id = isset($more_information['bank_second_id']) 
				? (int) $more_information['bank_second_id'] : 0;
			$bank_id = isset($more_information['bank_id']) ? $more_information['bank_id'] : 0;
			//if($status_id > 0 && !isset($arr_property_cached[$status_id])){
			//	$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
			//}
			if($type_id > 0 && !isset($arr_property_cached[$type_id])){
				$arr_property_cached[$type_id] = $clsProperty->getTitle($type_id);
			}
			if($block_id > 0 && !isset($arr_property_cached[$block_id])){
				$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
			}
			if($building_id > 0 && !isset($arr_property_cached[$building_id])){
				$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
			}
			if($contract_type_id > 0 && !isset($arr_property_cached[$contract_type_id])){
				$arr_property_cached[$contract_type_id] = $clsProperty->getTitle($contract_type_id);
			}
			if($contract_subject_id > 0 && !isset($arr_property_cached[$contract_subject_id])){
				$arr_property_cached[$contract_subject_id] = $clsProperty->getTitle($contract_subject_id);
			}
			if($invest_fund_id > 0 && !isset($arr_property_cached[$invest_fund_id])){
				$arr_property_cached[$invest_fund_id] = $clsProperty->getTitle($invest_fund_id);
			}
			/*if($sale_status_id > 0 && !isset($arr_property_cached[$sale_status_id])){
				$arr_property_cached[$sale_status_id] = $clsProperty->getTitle($sale_status_id);
			}
			if($agent_lock_id > 0 && !isset($arr_property_cached[$agent_lock_id])){
				$arr_property_cached[$agent_lock_id] = $clsProperty->getTitle($agent_lock_id);
			}*/
			if($home_direction_id > 0 && !isset($arr_property_cached[$home_direction_id])){
				$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
			}
			if($deposit_agent_id > 0 && !isset($arr_property_cached[$deposit_agent_id])){
				$arr_property_cached[$deposit_agent_id] = $clsProperty->getTitle($deposit_agent_id);
			}
			/*if($bank_second_id > 0 && !isset($arr_property_cached[$bank_second_id])){
				$arr_property_cached[$bank_second_id] = $clsProperty->getTitle($bank_second_id);
			}*/
			if($bank_id > 0 && !isset($arr_property_cached[$bank_id])){
				$arr_property_cached[$bank_id] = $clsProperty->getTitle($bank_id);
			}
			if($agency_id > 0 && !isset($arr_props_cached[$agency_id])){
				$arr_property_cached[$agency_id] = $clsProperty->getTitle($agency_id);
				$arr_props_cached[$agency_id] = $clsProperty->getTitleQR($agency_id);
			}
		}	
	}
	$smarty->assign('list_stocks', $list_stocks);
	$smarty->assign('arr_props_cached', $arr_props_cached);
	$smarty->assign('arr_property_cached', $arr_property_cached);
	$view_stock_resource = $clsISO->checkPermission('view_stock_resource');
	if(!$view_stock_resource){
		$view_stock_resource = $clsISO->check_view_resource(_BLOCK_TYPE_LOWFLOOR_SALE);
	}
	$smarty->assign('view_stock_resource', $view_stock_resource);
	// Return
	$html = $core->build('project'.DS.'_ajax.stock.tpl');
	echo json_encode(array_merge($more, array(
		'html' => $html,
		'cond' => $cond,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'html_options_TCBG' => $html_options_TCBG
	))); die();
}
function project_load_range(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$html_options = '';
	$project_id = (int) Input::post('project_id', 0);
	$list_block_ids = Input::post('list_block_ids');
	if(!empty($list_block_ids)){
		foreach($list_block_ids as $key => $block_id){
			$field = "{$clsProperty->pkey},title";
			if($project_id == _PROJECT_VHGG_ID){
				$more_information = $clsProperty->getOneField('more_information', $block_id);
				$more_information = $clsISO->to_array_json($more_information);
				$list_ranges = isset($more_information['list_ranges']) && !empty($more_information['list_ranges']) 
					? $more_information['list_ranges'] : array();
				$cond= "property_type='_RANGE' and {$clsProperty->pkey} in (".implode(',', $list_ranges).")";
			} else {
				$cond = "property_type='_RANGE' and for_id='{$block_id}'";
			}
			$list_ranges = $clsProperty->getAll($cond." order by `order_no` ASC", $field);
			if(!empty($list_ranges)){
				$html_options.= '<optgroup label="'.$clsProperty->getTitle($block_id).'">';
				foreach($list_ranges as $okey => $oval){
					$html_options.= '<option value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
				}
				unset($list_ranges);
				$html_options.= '</optgroup>';
			}
		}
	}
	// Return
	echo $html_options; die();
}
function project_load_range_dropdown(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$html_dropdowns = '';
	$uid = $clsISO->getUniqid();
	$block_id = (int) Input::post('block_id', 0);
	$field = "{$clsProperty->pkey},title";
	$cond = "property_type='_RANGE' and `for_id`='{$block_id}'";
	// $dbconn->debug = true;
	$list_ranges = $clsProperty->getAll($cond, $field);
	// $clsISO->print_pre($list_ranges); die();
	$html_dropdowns .= '<div class="dropdown-header d-flex align-items-center justify-content-between">
		<a class="goback cursor-pointer" onClick="$Core.tool.go_back(this, event)">
			<i class=\'bx bx-chevron-left\'></i></a>
		<span class="fs-6">'.$clsProperty->getTitle($block_id).'</span>
		<span class="text-main">Lựa chọn</span>
	</div>
	<div class="px-3 py-2">
		<div class="input-group input-group-merge">
			<span class="input-group-text"><i class="bx bx-search"></i></span>
			<input type="text" placeholder="Tìm kiếm..." class="form-control iso_search_field" toClass="'.$uid.'" />
		</div>
	</div>';
	if(!empty($list_ranges)){
		foreach($list_ranges as $key => $val){
			$html_dropdowns.= '<div class="dropdown-item '.$uid.'">
				<div class="form-check cursor-pointer">
					<input type="checkbox" data-field="range_ids[]" data-class="js__range_checkbox" class="form-check-input js__range_checkbox search_field" 
					id="chk_'.$val[$clsProperty->pkey].'" value="'.$val[$clsProperty->pkey].'" title="'.$val['title'].'">
					<label class="form-check-label iso_search_item" for="chk_'.$val[$clsProperty->pkey].'">'.$val['title'].'</label>
				</div>
			</div>';
		}
		unset($list_ranges);
	}
	// Return
	echo json_encode(array(
		'html' => $html_dropdowns
	)); die();
}
function project_load_stock_popover(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsLog = new Log();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	###
	$stock_id = Input::get('stock_id', 0);
	$oneStock = $clsStock->getOne($stock_id);
	$ms_code 	= $oneStock['ms_code'];
	$stock_type = (int) $oneStock['stock_type'];
	$project_id = (int) $oneStock['project_id'];
	$type_id 	= (int) $oneStock['type_id'];
	$block_id 	= (int) $oneStock['block_id'];
	$building_id= (int) $oneStock['building_id'];
	$status_id 	= (int) $oneStock['status_id'];
	$agency_id  = (int) $oneStock['agency_id'];
	$bedroom_id = $oneStock['bedroom_id'];
	$home_direction_id = $oneStock['home_direction_id'];
	$more_information = $oneStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$list_attributes = $core->get_field($more_information, "properties", []);
	$price_sheets = $core->get_field($more_information, "price_sheets", []);
	$hide_price_sheets = (int) $core->get_field($more_information, "hide_price_sheets", 0);
	if($type_id==0 && isset($more_information['type_id']) && !empty($more_information['type_id'])){
		$type_id = (int) $more_information['type_id'];
	}
	#- Start Log
	if(!in_array($profile_id, _PROFILE_NOT_LOG)){
		$clsLog->insertAction('view_stock', sprintf('Xem thông tin căn hộ <strong>%s</strong>', $ms_code), $stock_id);
	}
	#- Template
	$oneTemplate = array();
	$oBuilding = $clsProperty->getOne($building_id, "property_code,more_information");
	$building_information = $oBuilding['more_information'];
	$building_information = $clsISO->to_array_json($building_information);
	$list_templates = $core->get_field($building_information, "template", []);
	if(!empty($list_templates)){
		foreach($list_templates as $key => $val){
			if($oneStock['code'] == $val['code']){
				$oneTemplate = $val;
				break;
			}
		}
	}
	#CSBH tang thu cap
	$floor_hierarchy = $core->get_field($building_information, "floor_hierarchy", []);
	$stock_template = $building_information['stock_template'];
	$stock_template = str_replace('[MaToa]',$oBuilding['property_code'], $stock_template);
	$stock_template = str_replace('[Tang]', 'XX', $stock_template);
	$regex_search = str_replace('[CanHo]', $oneStock['code'], $stock_template);
	##
	$oneProject = $clsProject->getOne($project_id, "code,title");
	$oneBlock = $clsProperty->getOne($block_id, "title,property_code,more_information");
	$block_information = $oneBlock['more_information'];
	$block_information = $clsISO->to_array_json($block_information);
	$vr_link = $core->get_field($block_information, "vr_link", null);
	$vr_source = $core->get_field($block_information, "vr_source", null);
	$DT_TT = $core->get_field($more_information, "DT_TT", $oneTemplate['DT_TT']);
	$DT_Tim = $core->get_field($more_information, "DT_Tim", $oneTemplate['DT_Tim']);
	$total_price_vat = $core->get_field($more_information, "total_price_vat", 0);
	$csbh = $core->get_field($more_information, "csbh", "");
	if(isset($oneTemplate['home_direction_id']) && !empty($oneTemplate['home_direction_id'])){
		$home_direction_id = $oneTemplate['home_direction_id'];
	}
	if($bedroom_id == 0 && isset($oneTemplate['bedroom_id']) && !empty($oneTemplate['bedroom_id'])){
		$bedroom_id = $oneTemplate['bedroom_id'];
	}
	#
	$img_price_sheets = $html_price_more = $html_request_ptg = "";
	if(!empty($price_sheets && $hide_price_sheets==0)){
		$price_sheets = @array_reverse($price_sheets);
		$oneSheet = @reset($price_sheets);
		$img_price_sheets.= '<fieldset class="radius-4 ixZrPaFqgl my-2 bg-main">
			<legend class="fs-12 mb-0 px-1 bg-main text-white">Phiếu tính giá'.(!empty($csbh) ? sprintf(' %s', $csbh) : '').' <a href="javascript:;" data-link="'.MYOCEAN_URL.$clsStock->getLink($ms_code, 'PTG').'" onClick="$Core.util.copyToClipboard(this, event)" class="btn btn-xs bg-white text-main btn-icon btn-outline-default"><i class=\'bx bx-copy fs-12\'></i></a></legend>
			<div class="d-flex p-2">';
			foreach($oneSheet['sheets'] as $val){
				$img_price_sheets.= '<div class="flex-fill text-center">
					<a class="text_ptg" target="_blank" href="'.$val['image'].'" download>'.$val['title'].'</a>
				</div>';
			}
			$img_price_sheets.= '</div>
		</fieldset>';
	}
	#
	$html_request_ptg = "";
	if($agency_id != _AGENCY_CNCN_ID){
		$clsRequestPTG = new RequestPTG();
		if($clsRequestPTG->checkRequest($stock_id)) {
			$html_request_ptg.= '<button class="btn btn-sm btn-success" type="button" style="cursor:no-drop"><i class="fa fa-check"></i> <span>Đã yêu cầu PTG</span></button>';		
		}else{
			$html_request_ptg.= '<button stock_id="'.$stock_id.'" onclick="$Core.helper.requestPTG(this,event)" action="_OPEN" id="request_'.$stock_id.'" class="btn btn-sm btn-warning" type="button"><i class="bx bx-vector"></i> <span>Yêu cầu PTG</span></button>';		
		}
	}
	$has_price_early = $total_price_early = 0;
	$price_field_configs = $core->get_field($block_information, "price_field_configs", []);
	// $clsISO->print_pre($price_field_configs); die();
	if($oneStock['stock_type'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
		if(!empty($price_field_configs)){
			$total_cols = 0;
			foreach($price_field_configs as $key => $val){
				if(isset($val['status']) && $val['status']==1 
					&& isset($more_information[$key]) && !empty($more_information[$key])){
					$total_cols += 1;
					if($key=='total_price_early'){
						$total_price_early = $more_information[$key];
					}
				}
			}
			if($total_cols > 0){
				$html_price_more.= '<hr class="my-2" />
					<div class="form-row justify-content-center">';
				foreach($price_field_configs as $key => $val){
					if(isset($val['status']) && $val['status']==1 
						&& isset($more_information[$key]) && !empty($more_information[$key])){
						$price = isset($more_information[$key]) ? $clsISO->processSmartNumber($more_information[$key]) : 0;
						if($block_id == _PROJECT_BLOCK_MLS_ID 
							&& $clsStock->checkStockFundType($stock_id,$building_id,$oneStock)) {
							if(in_array($key, array('total_price_early', 'total_price_bank'))){
								$html_price_more.= '<div class="text-center col-4">
									<p class="mb-0">'.$val['title'].'</p>
									<div class="text-main fs-14 fw-bold">'.$clsISO->priceFormatV2($price,3).'tỷ</div>
								</div>';
							} else {
								$html_price_more.= "";
							}
						}else{
							$html_price_more.= '<div class="text-center col-'.($total_cols==3?'4':'3').'">
								<p class="mb-0">'.$val['title'].'</p>
								<div class="text-main fs-14 fw-bold">'.$clsISO->priceFormatV2($price,3).'tỷ</div>
							</div>';
						}
					}
				}
				$html_price_more.= '</div>';
			}
		}
	} else {
		$price_temporary_ns = $core->get_field($more_information, "price_temporary_ns", null);
		if(empty($price_sheets) && !empty($price_temporary_ns)){
			$img_price_sheets.= '<fieldset class="radius-4 ixZrPaFqgl my-2 bg-main">
				<legend class="fs-12 mb-0 px-1 bg-main text-white">
					Phiếu tính giá '.(!empty($csbh) ? sprintf(' %s', $csbh) : '').'
				</legend>
				<div class="d-flex p-2">';
				$img_price_sheets.= '<div class="flex-fill text-center">
					<a target="_blank" class="text-upper text_ptg" href="'.$price_temporary_ns.'">PTG TẠM TÍNH</a>
				</div>';
				$img_price_sheets.= '</div>
			</fieldset>';
		}
		if(!empty($price_field_configs)){
			$total_cols = 0;
			foreach($price_field_configs as $key => $val){
				if(isset($val['status']) && $val['status']==1 
					&& isset($more_information[$key]) && !empty($more_information[$key])){
					$total_cols += 1;
				}
			}
			if($total_cols > 0){
				$html_price_more.= '<hr class="my-2" />
					<div class="form-row justify-content-center">';
				foreach($price_field_configs as $key => $val){
					if(isset($val['status']) && $val['status']==1 
						&&isset($more_information[$key]) && !empty($more_information[$key])){
						$price = isset($more_information[$key]) ? $clsISO->processSmartNumber($more_information[$key]) : 0;
						$html_price_more.= '<div class="text-center col-'.($total_cols==3?'4':'3').'">
							<p class="mb-0 text-nowrap">'.$val['title'].'</p>
							<div class="text-main fs-14 fw-bold">'.$clsISO->priceFormatV2($price,3).'tỷ</div>
						</div>';
					}
				}
				$html_price_more.= '</div>';
			}
		}
		if(empty($total_price_vat) && !empty($more_information['total_price_early'])){
			$has_price_early = 1;
			$total_price_vat = $clsISO->processSmartNumber($more_information['total_price_early']);
		}
	}
	#- PTG
	$html_price_sheets = "";
	$stock_posters = $core->get_field($more_information, "stock_poster", []);
	if(!empty($stock_posters)){
		$html_price_sheets.= '<li>
			<i class="material-icons-outlined" style="color:#696cff">image</i> POSTER: ';
		foreach($stock_posters as $okey => $oval){
			$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-default mr-1" data-fancybox data-preload="true" href="'.$clsISO->getGoogleUrl($oval['image']).'">'.$oval['title'].'</a>';
		}
		$html_price_sheets.= '</li>';
	}	
	if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
		if(isset($more_information['layout']) && !empty($more_information['layout'])){
			$html_price_sheets.='<li><a target="_blank" href="'.$more_information['layout'].'">
				<i class="material-icons-outlined">flip_to_front</i> Layout chi tiết căn hộ</a></li>';	
		}
	} else {
		if(!empty($oneTemplate) && !empty($oneTemplate['layout_ns'])){
			$html_price_sheets.='<li><a target="_blank" href="'.$oneTemplate['layout_ns'].'">
				<i class="material-icons-outlined">flip_to_front</i> Layout chi tiết căn hộ</a></li>';	
		}
		/** Nội thất mẫu */
		$bedroom_information = $clsProperty->getOneField('more_information', $bedroom_id);
		$bedroom_information = $clsISO->to_array_json($bedroom_information);
		$folder_interior_ns = $core->get_field($bedroom_information, "folder_interior_ns", []);
		if(!empty($folder_interior_ns)){ $ii = 0;
			$html_price_sheets.= '<li><i class="material-icons-outlined">image</i> NỘI THẤT: ';
			foreach($folder_interior_ns as $pkey => $pval){
				if(!empty($pval['title']) && !empty($pval['link'])){
					$gg_id = $clsISO->getGoogleId($pval['link']);
					$html_price_sheets.= '<span class="gr-link mr-1">
						<a data-fancybox data-type="iframe" data-preload="true" href="/viewer/'.$gg_id.'">'.$pval['title'].'</a>';
					if(!empty($pval['video'])){
						$html_price_sheets.= ' | <a data-fancybox data-type="iframe" href="'.$pval['video'].'">video</a>';
					}
					$html_price_sheets.='</span>';
				}
				++$ii;
			}
			$html_price_sheets.= '</li>';
		}
		/** End nội thất mẫu*/
	}
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
		$regex = sprintf('%s_%s', $project_id, $block_id);
	} else {
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
	}
	$condPolicy = "`block_type`='{$stock_type}' and `ms_date`<='".time()."' 
	and `scope_slash` like '%|{$regex}|%' ";
	if($clsStock->checkStockFundType($stock_id,$building_id,$oneStock,$oBuilding)) {
		$condPolicy .= " AND `applicable_fund_type`='1'";
	}else{
		$condPolicy .= " AND `applicable_fund_type`='0'";
	}
	$onePolicy = $clsPolicy->getByCond($condPolicy." order by ms_date DESC limit 0,1");
	if(!empty($onePolicy)){
		$html_price_sheets.= '
			<li><a target="_blank" href="'.$onePolicy['link_ns'].'"><i class="material-icons-outlined">policy</i> Chính sách bán hàng('.$clsISO->convertTimeToText($onePolicy['ms_date']).')</a></li>
			<li><a target="_blank" href="'.$onePolicy['link_ms'].'"><i class="material-icons-outlined">paid</i> Phiếu tính giá mẫu('.$clsISO->convertTimeToText($onePolicy['ms_date']).')</a></li>';
	}
	if($clsISO->checkPermission('log_search_stock')){
		$html_price_sheets.= '<li><a href="'.PCMS_URL.'/logs-sale.html?stock_id='.$stock_id.'" target="_blank">
			<i class="material-icons-outlined">history</i> Lịch sử tra cứu <strong class="text-black">('.$clsLog->getTotalSearch($stock_id).')</strong></a></li>';
	}
	#
	$agency_name = "";
	if($agency_id > 0){
		$oneProperty = $clsProperty->getOne($agency_id, "property_code,title,more_information");
		if($clsISO->checkPermission('view_stock_resource') 
			|| $clsISO->check_view_resource($stock_type, $block_id, $oneBlock)){
			$more_information_ag = $oneProperty['more_information'];
			$more_information_ag = $clsISO->to_array_json($more_information_ag);
			$group_zalo = $core->get_field($more_information_ag, 'group_zalo', "");
			$html_button_group_zalo = !empty($group_zalo) ? sprintf('<a class="zalo_group-link" href="%s" target="_blank">'.$clsISO->makeIcon('bx-search', 'Check').'</a>', $group_zalo) : "";
			###
			$html_button_price_sheets = "";
			$folder_price_sheets = $core->get_field($more_information_ag, 'folder_price_sheets', []);
			if(!empty($folder_price_sheets)){
				foreach($folder_price_sheets as $key => $val){
					if(!empty($val['title']) && !empty($val['link'])){
						$html_button_price_sheets.= '<a class="btn btn-xs btn-outline-default mr-1" href="'.$val['link'].'" target="_blank">'.((mb_strlen($val['title']) > 4) ? $clsISO->truncate($val['title'],3,"") : $val['title']).'</a>';
					}
				}
			}
			###
			$html_price_sheets.= sprintf('<li><i class="material-icons-outlined">home_work</i> %s %s %s</li>', $oneProperty['title'], $html_button_price_sheets, $html_button_group_zalo);
		}
	}
	$html_last_updated = "";
	if($oneStock['upd_date'] > 0){
		$html_last_updated .= "<tr>
			<th>Cập nhật lần cuối</th>
			<td class=\"text-primary fs-12\">".$clsISO->convertTimeToText($oneStock['upd_date'], true)."</td>
		</tr>";
	}
	#- Status
	$oneStatus = array('bgcolor'=>'#FFF', 'textcolor' => '#566a7f');
	if($status_id > 0){
		$oneStatus= $clsProperty->getOne($status_id,"title,bgcolor,textcolor");
		$status_name= $clsProperty->getTitle($status_id, $oneStatus);
	} else{
		$status_name = "Đã bán";
		$oneStatus= $clsProperty->getOne(_STOCK_STATUS_SOLD_ID,"bgcolor,textcolor");
	}
	#- Loai Hinh
	$oneType = $clsProperty->getOne($type_id,"title,bgcolor,textcolor");
	#- Liked	
	$liked = $clsProfile->checkInWishlist($stock_id);
	#- tìm kiếm tòa, tầng, trục căn
	$html_search = '<div class="w-full mb-2 d-flex">
		<div class="x-box flex-fill text-center">
			<p class="text-muted mb-1"><i class="bx bx-building-house"></i> Tòa</p>
			<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"building").'" target="_blank">Tòa '.$clsProperty->getCode($building_id).'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
		</div>
		<div class="x-box flex-fill text-center">
			<p class="text-muted mb-1"><i class="bx bxl-heroku"></i> Tầng</p>
			<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"floor").'" target="_blank">Tầng '.$oneStock["floor"].'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
		</div>
		<div class="x-box flex-fill text-center">
			<p class="text-muted mb-1"><i class="bx bx-collection"></i> Trục</p>
			<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"code").'" target="_blank">Trục '.$oneStock["code"].'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
		</div>
	</div>';
	#- Price/m2
	$price_m2 = 0;
	if(!empty($total_price_early)){
		$price_m2 = $clsISO->processSmartNumber($total_price_early) / $clsISO->convertToNumber($DT_TT);
	} else if(!empty($total_price_vat)){
		$price_m2 = $clsISO->processSmartNumber($total_price_vat) / $clsISO->convertToNumber($DT_TT);
	}
	$permiss_upload_poster = $clsISO->checkPermission('upload_poster') ? 1 : 0;
	$permiss_hide_stock_globe = $clsISO->checkPermission('hide_stock_globe') ? 1 : 0;
	$permiss_edit_stock_advanced = $clsISO->checkPermission('edit_stock_advanced') ? 1 : 0;
	$html_history_log = "";
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkDEV()) {
		$html_history_price_log = '<a class="btn btn-sm btn-outline-default bg-white px-2" title="Lịch sử thay đổi giá" href="javascript:void(0);" onClick="$Core.helper.history_price_log(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-history fs-14','').'</a>';
	}
	$html = '<div class="rounded-3 mb-2 overflow-hidden">
		<div class="bg-'.($agency_id==_AGENCY_CNCN_ID?'purple':'main').' p-3 w-full w-100 text-center">
			<h3 class="text-white mb-0 fs-18 fw-bold">
			'.($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE ? sprintf('%s, %s', $oneBlock['title'], $oneProject['code']) : $oneBlock['property_code']).' - <a class="text-white" href="'.$clsStock->getLink($oneStock['ms_code']).'" target="_blank">'.$oneStock['ms_code'].' <i class="bx bx-link-external"></i></a> 
			<a class="text-white ml-1" href="/tim-kiem/'.$regex_search.'.html"><i class="bx bx-search"></i></a> 
			'.(!empty($vr_link)?'<a class="ml-1" onClick="$Core.helper.close_webui_popver(this, event)" title="Xem VR360" href="'.$vr_link.'" data-fancybox data-type="iframe" data-caption="'.$vr_source.'"><img src="'.URL_IMAGES.'/vr-w-360.png" class="w-px-30"></a>':'').'</h3>
		</div>
		<div class="py-2 px-3 bg-grayter">
			<div class="text-center">
				<p class="mb-0 text-main fs-24 fw-bold">
					<i class="material-icons-outlined fs-24 mr-1">shopping_cart</i>
					'.(!empty($total_price_vat) ? ($stock_type== _BLOCK_TYPE_LOWFLOOR_SALE ? sprintf('%s tỷ', $clsISO->formatPriceV2($total_price_vat)) : $clsISO->formatPrice($total_price_vat)) : 'Check admin').'
				</p>
				<p class="mb-0">'.($has_price_early?'Giá trên là phương án <strong>Thanh Toán Sớm</strong>':'Giá đã bao gồm VAT & KPBT').'</p>
				'.(!empty($csbh) ? '<p class="text-main fs-13">Chính sách bán hàng áp dụng: <strong>'.$csbh.'</strong></p>' : '').'
			</div>
			<div class="d-flex flex-wrap gap-1 fs-12 mt-2 justify-content-center">
				'.$html_history_price_log.($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE?'<a class="btn btn-sm btn-outline-default bg-white px-2" title="Biểu đồ giá" href="javascript:void(0);" onClick="$Core.helper.open_stock_chart(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-bar-chart-alt fs-14','').'</a>':'').' 
				<a class="btn btn-sm btn-outline-default bg-white px-2" title="Tính lãi vay" href="javascript:void(0);" onClick="$Core.calculator.open(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx bx-calculator fs-14','').'</a>
				'.($clsISO->checkDEV()?'<a class="btn btn-sm btn-outline-default bg-white px-2" title="Tính hiệu suất đầu tư" href="javascript:void(0);" onClick="$Core.performance.open(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-line-chart fs-14','').'</a>':'').(($clsISO->checkSale()) ? $html_request_ptg : "").'
			</div>
			'.$html_price_more.$img_price_sheets.'
			'.(($permiss_edit_stock_advanced || $permiss_hide_stock_globe || $permiss_upload_poster) ? '
			<div class="d-flex flex-wrap gap-1 fs-12 mt-2 justify-content-center">
				'.($permiss_edit_stock_advanced?'<button stock_id="'.$stock_id.'" tp="sheet_price" onClick="$Core.helper.open_quick_stock(this,event)" class="btn btn-sm btn-outline-default bg-white" type="button">'.$core->makeIcon('plus', 'PTG').'</button>
				<button stock_id="'.$stock_id.'" tp="update_status" class="btn btn-sm btn-outline-primary" onClick="$Core.helper.open_quick_stock(this,event)"  type="button">'.$core->makeIcon('check', 'Tình trạng').'</button>':'').'
				'.($permiss_upload_poster ?'<button stock_id="'.$stock_id.'" tp="poster" class="btn btn-sm btn-outline-default bg-white" onClick="$Core.helper.open_quick_stock(this,event)"  type="button">'.$core->makeIcon('check', 'POSTER').'</button>':'').'
				'.($permiss_hide_stock_globe ? '<button stock_id="'.$stock_id.'" tp="poster" class="btn btn-sm '.($clsStock->checkShow($oneStock['show_website'], 'MOC')?'btn-outline-primary':'btn-outline-default').'" onClick="$Core.helper.hide_stock_MOC(this,event)" type="button">'.$core->makeIcon('check-square-o', 'MOC').'</button>' : '').((!$clsISO->checkSale()) ? $html_request_ptg : "").'
			</div>':((!$clsISO->checkSale()) ? $html_request_ptg : "")).'
		</div>
		'.($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE?'<div class="py-3 mb-2 bg-lighter">
			<div class="w-full mb-2 d-flex">
				<div class="w-full mb-2 d-flex">
					<div class="x-box flex-fill text-center">
						<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn</p>
						<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"bedroom").'" target="_blank">'.$clsProperty->getTitle($bedroom_id).'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
					</div>
					<div class="x-box flex-fill text-center">
						<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>
						<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"direction").'" target="_blank">'.$clsProperty->getTitleQR($home_direction_id).'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
					</div>
					<div class="x-box flex-fill text-center">
						<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>
						<h4 class="fs-14 mb-0">'.$DT_TT.' m<sup>2</sup></h4>
					</div>
				</div>
			</div>
			'.$html_search.'
			<div class="w-full d-flex">
				<div style="width:33.33%" class="x-box flex-fill text-center">
					<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>
					<h4 class="fs-14 mb-0">
						<span class="label" style="background:'.$oneStatus['bgcolor'].'; color:'.$oneStatus['textcolor'].'">'.$status_name.'</span>
					</h4>
				</div>
				<div style="width:33.33%" class="x-box flex-fill text-center">
					<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Loại hình</p>
					<h4 class="fs-14 mb-0">'.($clsStock->checkStockFundType($stock_id,$building_id,$oneStock,$oBuilding) ? '<span class="label" style="background:#28ff00; color:#333">Thứ cấp</span>' : ('<span class="label" style="background:'.$oneType['bgcolor'].'; color:'.$oneType['textcolor'].'">
							'.$clsProperty->getLoaiHinh($oneStock['type_id']).'
						</span>')).'						
					</h4>
				</div>
				<div style="width:33.33%" class="x-box flex-fill text-center">
					<p class="text-muted mb-1"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>
					<h4 class="fs-13 mb-0">
						<span class="label bg-info">'.$clsISO->shortNumber($price_m2).'</span>
					</div>
				</div>
			</div> 
			<ul class="mb-1 pl-2 list-unstyled">
				'.$html_price_sheets.'
			</ul>
			<hr class="my-0" />
		</div>':'<div class="w-full mt-1 py-2 d-flex">
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại hình</p>
				<h4 class="fs-14">'.$clsProperty->getTitle($type_id, $oneType).'</h3>
			</div>
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>
				<h4 class="fs-14">'.($home_direction_id > 0 ? $clsProperty->getTitle($home_direction_id) : '---').'</h3>
			</div>
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Diện tích đất</p>
				<h4 class="fs-14">'.$DT_TT.'m<sup>2</sup></h3>
			</div>
		</div>
		<hr class="my-0" />
		<div class="w-full py-2 d-flex">
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>
				<h4 class="fs-14 mb-0">
					<span class="label" style="background:'.$oneStatus['bgcolor'].';color:'.$oneStatus['textcolor'].'">
						'.$status_name.'
					</span>
				</h4>
			</div>
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Dãy</p>
				<h4 class="fs-14 mb-0">'.$clsProperty->getTitle($building_id).'</h3>
			</div>
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Diện tích XD</p>
				<h4 class="fs-14  mb-0">'.$DT_Tim.'m<sup>2</sup></h3>
			</div>
		</div>
		<div class="w-full py-1 d-flex">
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1">
					<i class="d-inline-block re__icon-villa--sm"></i> Quỹ đầu tư
				</p>
				<h4 class="fs-14 mb-0">'.$clsProperty->getTitle($more_information['invest_fund_id']).'</h4>
			</div>
			<div class="x-box flex-fill text-center">
				<p class="text-muted mb-1">
					<i class="d-inline-block re__icon-office--sm"></i> Giỏ bank
				</p>
				<h4 class="fs-14">'.$clsProperty->getTitle($more_information['bank_id']).'</h3>
			</div>
		</div>
		<ul class="mb-1 pl-2 list-unstyled">
			'.$html_price_sheets.'
		</ul>
		<hr class="my-0" />').'
	</div>
	<div class="text-muted text-center">
		<span class="fs-12">'.$html_last_updated.'</span>
	</div>';
	// Return
	echo $html; die();
}
function project_load_pop_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
		,$description_page,$keyword_page,$clsConfiguration,$clsISO,$project_id,$oneProject;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$stock_id = Input::get('stock_id', 0);
	$field = "floor,code,block_id,building_id,project_id";
	$oneStock = $clsStock->getOne($stock_id, $field);
	###
	$project_id = $oneStock['project_id'];
	$block_id = $oneStock['block_id'];
	$building_id = $oneStock['building_id'];
	$code = $oneStock['code'];
	###
	$field = "{$clsStock->pkey},ms_code,more_information,home_direction_id,agency_id,status_id,bedroom_id";
	$cond = "`is_trash`=0";
	if(!$clsISO->checkPermission('view_stock_hidden')){
		$arr_hid_agency = $arr_agency_ids = $arr_hid_blocks = array();
		$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
		$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
		if(!empty($agency_hidden_stock_FH)){
			foreach ($agency_hidden_stock_FH as $key => $val) {
				$hid_block_id = (int) $val['block_id'];
				if($hid_block_id > 0 && !in_array($hid_block_id, $arr_hid_blocks)) {
					$arr_hid_blocks[] = $hid_block_id;	
				}		
			}
		}
		$arr_hid_agency = array();
		$list_agency = $clsProperty->getCacheItems("_AGENCY"); 
		if(!in_array($block_id, $arr_hid_blocks)){
			foreach($list_agency as $key => $val){
				$agency_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$hide_stock_vin = $core->get_field($more_information, 'FH_stock_vin', 0);
				if((int) $hide_stock_vin==1){
					$arr_hid_agency[] = $agency_id;
				}
			}
		} else {
			foreach($list_agency as $key => $val){
				$agency_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$hid_field = sprintf('FH_%s_%s', $agency_id, $block_id);
				if((int) $core->get_field($more_information, $hid_field, 0) == 1){
					$arr_hid_agency[] = $agency_id;
				}
			}
		}
		$list_ag_notins = $clsProperty->getAll("`is_locked`=0 AND `is_trash`=1 
			AND `property_type`='_AGENCY'", $clsProperty->pkey);
		if(!empty($list_ag_notins)){
			foreach($list_ag_notins as $key => $val){
				$arr_hid_agency[] = $val[$clsProperty->pkey];
			}
		}
		if(!empty($arr_hid_agency)){
			$cond.= " and `agency_id` not in (".implode(',', $arr_hid_agency).")";
		}
	}
	$list_stocks = $clsStock->getAll("{$cond} and `project_id`='{$project_id}' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}' and `code`='{$code}' 
		and `agency_id`>0 and `status_id`>0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
		and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' order by `floor` ASC", $field);
	// $clsISO->print_pre($cond); die();
	if(!empty($list_stocks)){ $ii = 1;
		$html.= '<div class="table-container no-shadow text-nowrap overflow-x-auto mb-1">
			<table class="table table_'.$building_id.' mb-0" cellspacing="0" cellpadding="0">
				<thead><tr>
					<th class="algin-center bg-lighter text-center h-px-35">Mã căn</th>
					<th class="algin-center bg-lighter text-center h-px-35">Loại</th>
					<th class="algin-center bg-lighter text-center h-px-35">DT_TT</th>
					<th class="algin-center bg-lighter text-center h-px-35">Hướng</th>
					<th class="algin-center bg-lighter text-center h-px-35">Giá</th>
				</tr></thead>';
			$arr_property_cached = array();
			foreach($list_stocks as $key => $val){
				$stock_id = $val[$clsStock->pkey];
				$agency_id = $val['agency_id'];
				$status_id = $val['status_id'];
				$bedroom_id = $val['bedroom_id'];
				$home_direction_id = (int) $val['home_direction_id'];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$total_price_vat = $clsISO->processSmartNumber($more_information['total_price_vat']);
				$total_price_early = $clsISO->processSmartNumber($more_information['total_price_early']);
				if($home_direction_id > 0 && !empty($more_information['home_direction_id'])){
					$home_direction_id = $more_information['home_direction_id'];
				}
				$is_early = 0;
				if(empty($total_price_vat) && !empty($total_price_early)){
					$is_early = 1;
					$total_price_vat = $total_price_early;
				}
				if($bedroom_id > 0 && !isset($arr_property_cached[$bedroom_id])){
					$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
				}
				if($home_direction_id > 0 && !isset($arr_property_cached[$home_direction_id])){
					$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
				}
				if($status_id > 0 && !isset($arr_property_cached[$status_id])){
					$f_field = "bgcolor,textcolor,title,property_code";
					$arr_property_cached[$status_id] = $clsProperty->getOne($status_id, $f_field);
				}
				$label = sprintf('<span class="label ml-1" style="color:%s; background:%s">%s</span>', 
					$arr_property_cached[$status_id]['textcolor'],
					$arr_property_cached[$status_id]['bgcolor'], 
					$arr_property_cached[$status_id]['property_code']
				);
				$html.= '<tr>
					<td class="algin-center text-center">
						<a class="text-link" title="'.$val['ms_code'].'" 
							onClick="$Core.helper.open_stock('.$stock_id.')">'.$val['ms_code'].'</a>
						'.$label.'
					</td>
					<td class="algin-center text-center">
						'.$arr_property_cached[$bedroom_id].'
					</td>
					<td class="algin-center text-center">
						'.$more_information['DT_TT'].'m<sup>2</sup>
					</td>
					<td class="algin-center text-center">
						'.$arr_property_cached[$home_direction_id].'
					</td>
					<td class="algin-center text-center">
						'.$clsISO->priceFormatV2($total_price_vat,3).' tỷ'.($is_early?'(TTS)':'').'
					</td>
				</tr>';
				++$ii;
			}	
			$html.= '</table>
			<style type="text/css">
				.table_'.$building_id.' th,
				.table_'.$building_id.' td{
					padding-left:0.125rem !important;
					padding-right:0.125rem !important;
				}
			</style>
		</div>
		<div class="text-muted text-right">
			<span class="fs-10">Giá Full Vat</span>
		</div>';
	} else {
		$html = '';
	}
	// Return
	echo $html; die();
}
function project_stock_list(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
		,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProperty = new Property();
	$list_projects = $list_quick_menus = array();
	$list_mwf_builings = $list_lsb_builings = $list_mga_builings = array();
	$arr_project_highfloor = [_PROJECT_DEF_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID,_PROJECT_MRD_ID,_PROJECT_CSD_ID];
	$arr_project_lowfloor = [_PROJECT_VHOP3_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID,_PROJECT_VWC_ID];
	
	$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`more_information`";
	if( $clsCache->has('_ss_project_cached')){
		$list_projects = $clsCache->get('_ss_project_cached');
		$clsCache->delete('_ss_project_cached');
	} else {
		$list_projects = $clsProject->getAll("`is_menu`='1' order by `reg_date` ASC", $field);
		if(!empty($list_projects)){
			foreach($list_projects as $key => $val){
				$project_id = $val[$clsProject->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$logo = isset($more_information['logo']) ? $more_information['logo'] : "";
				$list_projects[$key]['logo'] = $logo;
				$lstBlockHighFloor = [];
				$list_projects[$key]['order_no'] = ($project_id==_PROJECT_VHOP2_ID) ? 1 : 0;
				$list_blocks = $clsProperty->getOItems('_BLOCK',$project_id,"title,property_code,parent_id,more_information");
				if(!empty($list_blocks)){
					foreach($list_blocks as $okey => $oval){
						$block_id = $oval[$clsProperty->pkey];
						$order_no = ($block_id == _PROJECT_BLOCK_MGA_ID) ? 2 : 1;
						if($oval['parent_id']==_BLOCK_TYPE_HIGHLEVEL_SALE){ // Cao tầng
							$list_builings = $clsProperty->getOItems('_BUILDING', $block_id, "`property_code`,`for_id`,`more_information`");
							if(!empty($list_builings)){
								$arr_building = [];
								foreach($list_builings as $mkey => $mval){
									$building_id = $mval[$clsProperty->pkey];
									$more_information = $mval['more_information'];
									$more_information = $clsISO->to_array_json($more_information);
									if(isset($more_information['is_menu']) && (int) $more_information['is_menu'] == 1 && empty($more_information['is_out_stock'])){
										$arr_building[] = array(
											'order_no' => $order_no,
											'title' => sprintf('%s(%s)', $mval['title'], $mval['property_code']),
											'link' => sprintf('/project/p%s/b%s.html', $project_id, $building_id)
										);
									}
								}							
								if(!empty($arr_building)) {
									$list_blocks[$okey]['building'] = $arr_building;	
									$lstBlockHighFloor[] = $list_blocks[$okey];
								}								
							}					
						}
					}
				}
				$list_projects[$key]['block'] = $lstBlockHighFloor;
			}
		}
		$clsCache->put('_ss_project_cached', $list_projects);
	}
	// $clsISO->print_pre($list_projects); die();
	$assign_list["list_projects"] = $list_projects;
	$assign_list["arr_project_highfloor"] = $arr_project_highfloor;
	$assign_list["arr_project_lowfloor"] = $arr_project_lowfloor;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng hàng dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_detail(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsShop = new Shop();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$show = Input::get('show','project');
	$project_id = (int) Input::get('project_id', 0);
	$block_id = (int) Input::get('block_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	$cat_id = (int) Input::get('cat_id', _PROJECT_DOCS_IMGVIDEO_CATID);
	$root_id = $clsProperty->getRootId($cat_id);
	$assign_list["show"] = $show;
	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	$assign_list["cat_id"] = $cat_id;
	$assign_list["root_id"] = $root_id;
	
	$arr_project = [_PROJECT_VHOP3_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID];
	$assign_list["arr_project"] = $arr_project;
	###
	$oneProject = $clsProject->getOne($project_id);
	$list_block_type = $oneProject['list_block_type'];
	$more_information = $oneProject['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$arr_block_type = $clsISO->getArrayByTextSlash($list_block_type);
	$assign_list["oneProject"] = $oneProject;
	$assign_list["more_information"] = $more_information;
	$assign_list["is_lowfloor"] = @in_array(_BLOCK_TYPE_LOWFLOOR_SALE, $arr_block_type) ? 1 : 0;
	$list_utilities = $list_shops = array();
	if($cat_id == _PROJECT_DOCS_UTILITY_CATID){
		$list_utilities = $clsProperty->getCacheItems("_UTILITIES_PROJECT");
		$utilities = $oneProject['utilities'];
		$utilities = $clsISO->to_array_json($utilities);
		foreach($list_utilities as $k_cat => $v_cat) {
			$total = 0;
			$list_utilities[$k_cat]['utilities'] = [];
			foreach ($utilities as $k_utilities => $v_utilities) {
				if ($show == 'project'){
					if($v_utilities['cat_id'] == $v_cat['property_id'] && (int) $v_utilities['block_id'] == 0) {
						$list_utilities[$k_cat]['utilities'][] = $v_utilities;
						++$total;
					}
				} else if($show == 'block') {
					$block_id = (int) Input::get('block_id', 0);
					if($v_utilities['cat_id'] == $v_cat['property_id'] 
						&& ($v_utilities['block_id'] == $block_id && (!isset($v_utilities['building_ids']) || empty($v_utilities['building_ids'])))) {
						$list_utilities[$k_cat]['utilities'][] = $v_utilities;
						++$total;
					}
				} else if($show == 'building'){
					$building_id = (int) Input::get('building_id', 0);
					$oneBuilding = $clsProperty->getOne($building_id, "for_id");
					$block_id = $oneBuilding['for_id'];
					if($v_utilities['cat_id'] == $v_cat['property_id'] && ((isset($v_utilities['building_ids']) && in_array($building_id, $v_utilities['building_ids'])) || $v_utilities['block_id'] == $block_id)) {
						if($v_utilities['block_id'] > 0 && (!isset($v_utilities['building_ids']) || empty($v_utilities['building_ids']))){
							$order_no = 0;
							$v_utilities['title'] = '<span class=\'badge bg-label-danger me-1\'>Chung</span>' . $v_utilities['title'];
						} else {
							$order_no = 1;
						}
						$v_utilities['order_no'] = $order_no;
						$list_utilities[$k_cat]['utilities'][] = $v_utilities;
						++$total;
					}
				}			
			}
			if($show == 'building' 
				&& !empty($list_utilities[$k_cat]['utilities'])){
				$arr_order = @array_column($list_utilities[$k_cat]['utilities'], 'order_no');
				@array_multisort($arr_order, SORT_DESC, $list_utilities[$k_cat]['utilities']);
			}
			if($total == 0) {
				unset($list_utilities[$k_cat]);
			}
		}
	}
	$assign_list["list_shops"] = $list_shops;
	$assign_list["list_utilities"] = $list_utilities;
	$list_blocks = $list_buildings = $list_policy_docs = array(); 
	$link_stock = $vr_link = $vr_source = "";
	if($show == 'project'){
		$subfix = 'dự án';
		$field = "{$clsProperty->pkey},parent_id,property_code,title,intro,more_information,image";
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}' and JSON_EXTRACT(`more_information`,'$.on_sale')='1' order by order_no ASC, JSON_EXTRACT(`more_information`,'$.on_sale') DESC", $field);
		$vr_link = $clsISO->getValue("vr_link", $more_information);
		$vr_source = $clsISO->getValue("vr_source", $more_information);
		if(!empty($list_blocks)){
			foreach($list_blocks as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$construction_type = isset($more_information['construction_type']) 
					? $more_information['construction_type'] : "";
				$construction_type_arr = !empty($construction_type) 
					? @explode('|', $construction_type) : array();
				$more_information['construction_type'] = $construction_type_arr;
				$list_blocks[$key]['more_information'] = $more_information;
			}
		}
	} else if($show == 'block'){
		$subfix = 'phân khu';
		$oneBlock = $clsProperty->getOne($block_id);
		$stock_type = (int) $oneBlock['parent_id'];
		$more_information_block = $oneBlock['more_information'];
		$more_information_block = $clsISO->to_array_json($more_information_block);
		$assign_list["oneBlock"] = $oneBlock;
		$assign_list["more_information"] = $more_information_block;
		$vr_link = $clsISO->getValue("vr_link", $more_information_block);
		$vr_source = $clsISO->getValue("vr_source", $more_information_block);
		if(empty($vr_link)) {
			$vr_link = $clsISO->getValue("vr_link", $more_information);
			$vr_source = $clsISO->getValue("vr_source", $more_information);
		}
		// $clsISO->print_pre($more_information);die;
		$assign_list["stock_type"] = $stock_type;
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image";
			$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' and JSON_EXTRACT(`more_information`,'$.on_sale')='1' 
			and `for_id`='{$block_id}' order by `reg_date` ASC", $field);
			if(!empty($list_buildings)){
				$arr_property_cached = array();
				foreach($list_buildings as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_buildings[$key]['more_information'] = $more_information;
				}
			}
		} else {
			$link_stock = sprintf('/project/p%s/block%s.html', $project_id, $block_id);
			$field = "{$clsProperty->pkey},parent_id,property_code,title,intro,more_information,image";
			$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
				and `for_id`='{$project_id}' order by `order_no` ASC", $field);
			if(!empty($list_blocks)){
				foreach($list_blocks as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$construction_type = isset($more_information['construction_type']) 
						? $more_information['construction_type'] : "";
					$construction_type_arr = !empty($construction_type) 
						? @explode('|', $construction_type) : array();
					$more_information['construction_type'] = $construction_type_arr;
					$list_blocks[$key]['more_information'] = $more_information;
				}
			}
		}
	} else if($show == 'building'){
		$subfix = 'toà nhà';
		$oneBlock = $clsProperty->getOne($block_id);
		$oneBuilding = $clsProperty->getOne($building_id);
		$block_information = $oneBlock['more_information'];
		$block_information = $clsISO->to_array_json($block_information);
		$more_information_building = $oneBuilding['more_information'];
		$more_information_building = $clsISO->to_array_json($more_information_building);
		$vr_link = $clsISO->getValue("vr_link", $block_information);
		$vr_source = $clsISO->getValue("vr_source", $block_information);
		if(empty($vr_link)) {
			$vr_link = $clsISO->getValue("vr_link", $more_information);
			$vr_source = $clsISO->getValue("vr_source", $more_information);
		}
		$link_stock = $clsProject->getLink($project_id, $building_id);
		$layout_ns = isset($more_information_building['layout_ns']) ? $more_information_building['layout_ns'] : "";
		$list_layouts = array();
		if(!empty($layout_ns)){
			$list_layouts[$clsISO->getUniqid()] = array(
				'title' => "Tầng điển hình",
				'image' => $layout_ns
			);
		}
		$layout_ms = isset($more_information_building['layout_ms']) ? $more_information_building['layout_ms'] : array();
		if(!empty($layout_ms)){
			foreach($layout_ms as $key => $val){
				$list_layouts[$key] = $val; 
			}
		}
		$assign_list["list_layouts"] = $list_layouts;
		// $clsISO->print_pre($list_layouts); die();
		$assign_list["oneBlock"] = $oneBlock;
		$assign_list["oneBuilding"] = $oneBuilding;
		$assign_list["more_information"] = $more_information_building;
		$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image";
		$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' and JSON_EXTRACT(`more_information`,'$.on_sale')='1' 
		and `for_id`='{$block_id}' order by `reg_date` ASC", $field);
		if(!empty($list_buildings)){
			$arr_property_cached = array();
			foreach($list_buildings as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$list_buildings[$key]['more_information'] = $more_information;
			}
		}
	}
	$assign_list["subfix"] = $subfix;
	$assign_list["link_stock"] = $link_stock;
	$assign_list["vr_link"] = $vr_link;
	$assign_list["vr_source"] = $vr_source;
	$assign_list["list_blocks"] = $list_blocks;
	$assign_list["list_buildings"] = $list_buildings;
	/* End in*/
	$field = "{$clsProperty->pkey},title,image";
	$cond = "`is_trash`=0 and `property_type`='_CATEGORY_DOCS'";
	if($show == 'project'){
		$cond.= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."'";
	} else if($show == "block" && $oneBlock['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
		$cond.= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."'";
	}
	$list_category_docs = $clsProperty->getAll("{$cond} and `parent_id`='0' order by `order_no` ASC", $field);
	//	$clsISO->print_pre($list_category_docs);die;
	if($cat_id != _PROJECT_DOCS_UTILITY_CATID){
		$cond = "`is_trash`='0'";
		if($show == "block") {
			$cond.= " AND `type`='block' AND `for_id`='{$block_id}'";
		}else if($show == "building") {
			$cond.= " AND (`type`='building' or `type`='block') AND (`for_id`='{$building_id}' or `for_id`='{$block_id}')";
		}else{
			$cond.= " AND `type`='project' AND `for_id`='{$project_id}'";
		}
		$list_docs = $list_posts = array(); $total_docs = 0;
		$field = "{$clsProperty->pkey},slug,title";
		$cnd = "`is_trash`=0 and `property_type`='_CATEGORY_DOCS'";
		if($show == 'project'){
			$arr_notins = array(_PROJECT_DOCS_BM_CATID, _PROJECT_DOCS_CHTH_CATID, _PROJECT_DOCS_INTERIOR_CATID);
			$cnd.= " and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins).")";
		} else if($show == 'block' && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
			$arr_notins = array(_PROJECT_DOCS_BM_CATID);
			$cnd.= " and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins).")";
		}
		$list_childs = $clsProperty->getAll("{$cnd} and `parent_id`='{$cat_id}' order by order_no ASC", $field);
		if(!empty($list_childs)){
			foreach($list_childs as $key => $val){
				$subcat_id = $val[$clsProperty->pkey];
				$list_childs[$key]['is_active'] = 0;
				if($subcat_id == _PROJECT_DOCS_PR_CATID){
					$clsNews = new News();
					$pattern = sprintf('DA-%s', $project_id);
					$pattern_cond = "JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'";
					if($show == 'block'){
						$pattern = sprintf('PK-%s', $block_id);
						$pattern_cond = "JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'";
					} else if($show == 'building') {
						$pattern = sprintf('TOA-%s', $building_id);
						$pattern_block = sprintf('PK-%s', $block_id);
						$pattern_cond = "(JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%' or JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern_block}|%')";
					}
					$list_posts = array();
					$field = "{$clsNews->pkey},title,content,images,reg_date";
					$tmp = $clsNews->getAll("`is_trash`=0 and `post_type`='_news' and {$pattern_cond} order by `reg_date` DESC", $field);
					if(!empty($tmp)){
						$list_childs[$key]['is_active'] = 1;
						foreach($tmp as $okey => $oval){
							$images = $oval['images'];
							$images = $clsISO->to_array_json($images);
							$image = !empty($images) ? @reset($images) : "";
							$list_posts[$okey] = array(
								'title' => $oval['title'],
								'intro' => $oval['content'],
								'content' => $clsNews->getLink($oval[$clsNews->pkey]),
								'more_information' => array('type' => '_news', 'image' => $image)
							);
						}
						unset($tmp);
					}
				}
				$list_docs = array();
				$tmp = $clsProjectMeta->getAll($cond." and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
				if(!empty($tmp)){
					$total_docs += count($tmp); $is_active = 1;
					foreach($list_childs as $gkey => $gval){
						if(isset($gval['is_active']) && $gval['is_active'] == 1){
							$is_active = 0; 
							break;
						}
					}
					if($is_active == 1) 
						$list_childs[$key]['is_active'] = 1;
					foreach($tmp as $okey => $oval){
						$list_images = array();
						$content = $oval['content'];
						$more_information = $oval['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$is_expanded = isset($more_information['is_expanded']) 
							? (int) $more_information['is_expanded'] : 0; 
						$list_files = isset($more_information['list_files']) 
							? $more_information['list_files'] : array();
						if(((!empty($list_files) && $more_information['type'] == 'google.file') || (empty($list_files) && $more_information['type'] == 'video')) && $is_expanded == 1){
							if(!empty(!empty($list_files))) {
								foreach($list_files as $mkey => $mval){
									if($clsISO->isFileVideo($mval)){
										$_type = 'video';
									} else if($clsISO->isPDF($mval)){
										$_type = 'pdf';
									} else {
										$_type = 'google.file';
									}
									$list_docs[] = array(
										'id' => $oval["id"],
										'type' => $_type,
										'title' => $oval['title'],
										'list_images' => $list_images,
										'image' => $clsISO->genGoogleURL($mkey),
										'link' => $clsISO->genGoogleURL($mkey,($_type=='google.file'?'view':'preview')),
										'more_information' => array(
											'gg_id' => $mkey,
											'type' => $_type,
											'image' =>  $clsISO->genGoogleURL($mkey)
										)
									);
								}
							}else{
								$list_docs[] = array(
									'id' => $oval["id"],
									'type' => "video",
									'title' => $oval['title'],
									'list_images' => [],
									'image' => $clsISO->genGoogleURL($more_information["gg_id"]),
									'link' => $clsISO->genGoogleURL($more_information["gg_id"],"preview"),
									'more_information' => array(
										'gg_id' => $more_information["gg_id"],
										'type' => "video",
										'image' =>  $clsISO->genGoogleURL($more_information["gg_id"])
									)
								);
							}
						} else {
							$oval['more_information'] = $more_information;
							if(!empty($list_files)){ $kk = 0;
								if(count($list_files) > 1){
									foreach($list_files as $nkey => $nval){
										if($clsISO->isFileVideo($nval)){
											$_type = 'video';
										} else if($clsISO->isPDF($nval)){
											$_type = 'pdf';
										} else {
											$_type = 'google.file';
										}
										// Nếu ảnh đầu tiên là video
										if($kk == 0 && in_array($_type, array('video', 'pdf'))){
											$oval['type'] = $_type;
											$oval['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
										} else {
											$oval['type'] = $more_information['type'];
											$oval['link'] = $clsISO->genGoogleURL($nkey, 'view');
										}
										$oval['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');
										$kk>0 && $list_images[] = array(
											'gid' => $nkey,
											'type' => $_type,
											'image' =>  $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'))
										);
										++$kk;
									}
								} else {
									foreach($list_files as $nkey => $nval){
										if($clsISO->isFileVideo($nval)){
											$_type = 'video';
										} else if($clsISO->isPDF($nval)){
											$_type = 'pdf';
										} else {
											$_type = 'google.file';
										}
										$oval['type'] = $_type;
										$oval['link_download'] = $clsISO->genGoogleURL($nkey, 'download');
										$oval['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
									}
								}
							} else {
								$oval['type'] = $more_information['type'];
								$oval['link_download'] = $clsISO->getDownloadURL($oval['content']);
								if($more_information['type'] == 'pdf'){
									$oval['link'] = $clsISO->getIframeUrl($oval['content']);
								} else {
									$oval['link'] = $clsISO->getGoogleUrl($oval['content']);
								}
							}
							$oval['list_images'] = $list_images;
							$oval['image'] = $more_information['image'];
							$list_docs[] = $oval;
						}
					}
				}
				$list_childs[$key]['cond'] = $cond;
				$list_childs[$key]['list_docs'] = $list_docs;
			}
		} else {
			$list_docs = array();
			if($cat_id == _PROJECT_DOCS_LAYOUT_CATID){
				$field = "{$clsProjectMeta->pkey}";
				$tmp = $clsProjectMeta->getAll($cond." and (`cat_id`='{$cat_id}' or cat_id='"._PROJECT_DOCS_BM_CATID."') 
					order by `reg_date` DESC");
			} else {
				$tmp = $clsProjectMeta->getAll($cond." and `cat_id`='{$cat_id}' order by `reg_date` DESC");
			}
			if(!empty($tmp)){
				$total_docs += count($tmp);
				foreach($tmp as $okey => $oval){
					$more_information = $oval['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$is_expanded = isset($more_information['is_expanded']) 
						? (int) $more_information['is_expanded'] : 0; 
					$list_files = isset($more_information['list_files']) 
						? $more_information['list_files'] : array();
					// $clsISO->print_pre($tmp);die;
					if(!empty($list_files) && $more_information['type'] == 'google.file' && $is_expanded == 1){
						foreach($list_files as $mkey => $mval){
							if($clsISO->isFileVideo($mval)){
								$_type = 'video';
								$_link = $clsISO->genGoogleURL($mkey, 'preview');
							} else if($clsISO->isPDF($mval)){
								$_type = 'pdf';
								$_link = $clsISO->genGoogleURL($mkey, 'preview');
							} else {
								$_type = 'google.file';
								$_link = $clsISO->genGoogleURL($mkey, 'view');
							}
							$list_docs[] = array(
								'type' => $_type,
								'link' => $_link,
								'title' => $oval['title'],
								'list_images' => $list_images,
								'image' =>  $clsISO->genGoogleURL($mkey),
								'link_download' => $clsISO->genGoogleURL($mkey, 'download'),
								'more_information' => array(
									'gg_id' => $mkey,
									'type' => $_type,
									'image' =>  $clsISO->genGoogleURL($mkey)
								)
							);
						}
					} else {
						$oval['more_information'] = $more_information;
						if(!empty($list_files)){ $kk = 0;
							if(count($list_files) > 1){
								foreach($list_files as $nkey => $nval){
									if($clsISO->isFileVideo($nval)){
										$_type = 'video';
									} else if($clsISO->isPDF($nval)){
										$_type = 'pdf';
									} else {
										$_type = 'google.file';
									}
									// Nếu ảnh đầu tiên là video
									if($kk == 0 && in_array($_type, array('video','pdf'))){
										$oval['type'] = $_type;
										$oval['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
									} else {
										$oval['type'] = $more_information['type'];
										$oval['link'] = $clsISO->genGoogleURL($nkey, 'view');
									}
									$oval['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');
									$kk > 0 && $list_images[] = array(
										'gid' => $nkey,
										'type' => $_type,
										'image' => $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'))
									);
									++$kk;
								}
							} else {
								foreach($list_files as $nkey => $nval){
									if($clsISO->isFileVideo($nval)){
										$_type = 'video';
									} else if($clsISO->isPDF($nval)){
										$_type = 'pdf';
									} else {
										$_type = 'google.file';
									}
									$oval['type'] = $_type;
									$oval['link_download'] = $clsISO->genGoogleURL($nkey, 'download');
									$oval['link'] = $clsISO->genGoogleURL($nkey, ($_type=='google.file'?'view':'preview'));
								}
							}
						} else {
							$oval['type'] = $more_information['type'];
							$oval['link_download'] = $clsISO->getDownloadURL($oval['content']);
							if($more_information['type'] == 'pdf'){
								$oval['link'] = $clsISO->getIframeUrl($oval['content']);
							} else {
								$oval['link'] = $clsISO->getGoogleUrl($oval['content']);
							}
						}
						$oval['list_images'] = $list_images;
						$oval['image'] = $more_information['image'];
						$list_docs[] = $oval;
					}
				}
			}
			/** CSBH */
			if($cat_id == _PROJECT_DOCS_CSBH_CATID){
				$clsPolicy = new Policy(); 
				$list_policy_blocks = $list_policy = array();
				if(($show== 'block' && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) || $show == 'building') {
					if($show== 'block' && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
						$block_type = _BLOCK_TYPE_LOWFLOOR_SALE;
						$pattern = sprintf('%s_%s', $project_id, $val[$clsProperty->pkey]);
						$block_information = $oneBlock['more_information'];
						$block_information = $clsISO->to_array_json($block_information);
						$list_policy = !empty($block_information["sales_policy"]) 
							? $block_information["sales_policy"] : array();		
						if(!empty($list_policy)){
							foreach($list_policy as $okey => $oval){
								$list_policy[$okey]['image'] = $clsISO->getGoogleUrl($oval['image']);
							}
						}				
					} else {						
						$block_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
						$pattern = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
						$block_information = $oneBlock['more_information'];
						$block_information = $clsISO->to_array_json($block_information);
						$list_policy = !empty($block_information["sales_policy"]) 
							? $block_information["sales_policy"] : array();	
						if(!empty($list_policy)){
							foreach($list_policy as $okey => $oval){
								if(!$clsISO->checkItemInArray($building_id, $oval['building'])){
									unset($list_policy[$okey]);
								}else{
									$list_policy[$okey]['image'] = $clsISO->getGoogleUrl($oval['image']);	
								}								
							}
						}
					}
					$list_policy_docs = $clsPolicy->getAll("`is_trash`=0 and `block_type`='{$block_type}' 
						and `scope_slash` like '%|{$pattern}|%' order by `ms_date` DESC");
					if(!empty($list_policy_docs)){
						foreach($list_policy_docs as $okey => $oval){
							$link_ns = $oval['link_ns'];
							$_more_information = $oval['more_information'];
							$_more_information = $clsISO->to_array_json($_more_information);
							if(!$_more_information){
								$_more = $clsProjectMeta->crawl($link_ns);
								if(!empty($_more)){
									foreach($_more as $nkey => $nval){
										$_more_information[$nkey] = $nval;
									}
								}
								$clsPolicy->updateOne($oval[$clsPolicy->pkey], array(
									'more_information' => json_encode($_more_information, JSON_UNESCAPED_UNICODE)
								));
							}
							$more_information = array(
								'link' => $link_ns,
								'image' => $_more_information['image']
							);
							$list_policy_docs[$okey]['more_information'] = $more_information;
						}
					}
				}
			}
		}
	}
	$assign_list['total_docs'] = $total_docs;
	$assign_list['list_docs'] = $list_docs;
	$assign_list['list_posts'] = $list_posts;
	$assign_list['list_childs'] = $list_childs;
	$assign_list["list_category_docs"] = $list_category_docs;
	$assign_list['list_policy_docs'] = $list_policy_docs;
	$assign_list['list_policy'] = $list_policy;
	/*=============Title & Description Page==================*/
	$title_page = 'Thông tin dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function project_view_docs_cat(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id,$assign_list;
	###
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProjectMeta"] = $clsProjectMeta;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$uid = $clsISO->getUniqid();
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	$parent_id = (int) Input::post('parent_id', 0);
	###
	$assign_list["uid"] = $uid;
	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	$assign_list["cat_id"] = $cat_id;
	$assign_list["parent_id"] = $parent_id;
	###
	$lstCategoryDocs = $clsProperty->getArraySearchByKey("_CATEGORY_DOCS");
	$arrCategoryDocs = $lstDocs = [];
	$cond = "`is_trash`='0'";
	if($building_id > 0) {
		$cond .= "	AND `type`='building' AND `for_id`='{$building_id}'";
	}else if($block_id > 0) {
		$cond .= "	AND `type`='block' AND `for_id`='{$block_id}'";
	}else if($block_id > 0) {
		$cond .= "	AND `type`='project' AND `for_id`='{$project_id}'";
	}
	$list_cat_child = $clsProperty->getAll("parent_id = '{$parent_id}'");
	if(!empty($list_cat_child)) {
		foreach($list_cat_child  as $k_cat => $v_cat) {
			$lstDocs = $clsProjectMeta->getAll($cond." AND (`cat_id`='{$v_cat['property_id']}' OR `list_cat_id` LIKE '%|{$v_cat['property_id']}|%')");
			if(!empty($lstDocs)) {
				foreach($lstDocs as $k_doc => $v_doc) {
					$more_information = $clsISO->to_array_json($v_doc['more_information']);
					$list_gg_id = !empty($more_information["list_gg_id"]) ? $more_information["list_gg_id"] : array();
					$youtu_id = !empty($more_information["youtu_id"]) ? $more_information["youtu_id"] : "";
					$list_image = array();
					if(!empty($list_gg_id)) {
						foreach($list_gg_id as $gg_id){
							$list_image[] = $clsISO->genGoogleURL($gg_id);
						}
					}
					$lstDocs[$k_doc]["list_image"] = $list_image;
					if(!empty($youtu_id)) {
						$link_video = "https://youtu.be/".$youtu_id;
						$lstDocs[$k_doc]["link_video"] = $link_video;
						$lstDocs[$k_doc]["image_video"] = $more_information["image"];
					}
					unset($more_information);
				}
				$list_cat_child[$k_cat]['lstDocs'] = $lstDocs;
			}			
		}
	}
	// $clsProjectMeta->setDeBug(1);
	$listDocs = $clsProjectMeta->getAll($cond." AND `cat_id`='{$parent_id}'");
	foreach($listDocs as $k_doc => $v_doc) {
		$more_information = $clsISO->to_array_json($v_doc['more_information']);
		$list_gg_id = !empty($more_information["list_gg_id"]) ? $more_information["list_gg_id"] : array();
		$youtu_id = !empty($more_information["youtu_id"]) ? $more_information["youtu_id"] : "";
		$list_image = array();
		if(!empty($list_gg_id)) {
			foreach($list_gg_id as $gg_id){
				$list_image[] = $clsISO->genGoogleURL($gg_id);
			}
		}
		$listDocs[$k_doc]["list_image"] = $list_image;
		if(!empty($youtu_id)) {
			$link_video = "https://youtu.be/".$youtu_id;
			$listDocs[$k_doc]["link_video"] = $link_video;
			$listDocs[$k_doc]["image_video"] = $more_information["image"];
		}
		unset($more_information);
	}
	// $clsISO->print_pre($listDocs);die;
	$assign_list['parent_name'] = $lstCategoryDocs[$parent_id]["title"];
	$assign_list['list_cat_child'] = $list_cat_child;
	$assign_list['listDocs'] = $listDocs;
	// $clsISO->print_pre($lstDocs);die;
	// Return
	$html = $core->build('project'.DS.'_ajax.docs.tpl');
	echo json_encode(array(
		'uid' 	=> $uid,
		'html' 	=> $html,
		'cond' 	=> $cond,
	)); die();
}
function project_load_block_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$html_dropdowns = '';
	$uid = $clsISO->getUniqid();
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$type = Input::post('type', "block");
	if($type == "block") {
		$cond .= "`for_id`='{$project_id}' AND `property_type`='_BLOCK'";
	}else{
		$cond .= "`for_id`='{$block_id}' AND (`property_type`='_BUILDING' OR `property_type`='_RANGE')";
	}
	$field = "{$clsProperty->pkey},title";	
	$clsProperty->setDeBug(1);
	$listItem = $clsProperty->getAll($cond, $field);
	$clsISO->print_pre($listItem);die;
	$html = "";
	if(!empty($listItem)){
		if($type == "block") {
			$html = '<option value="">Chọn phân khu</option>';
		}else{
			$html = '<option value="">Chọn Tòa/Dãy</option>';
		}
		
		foreach($listItem as $key => $val){
			$html .= '<option value="'.$val["property_id"].'">'.$val["title"].'</option>';
		}
		unset($listItem);
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function formatNumber($num){
	if(!empty($num))
		return (float) str_replace(',','.', $num);
	return $num;
}
function project_download_ptg(){
	// ini_set('memory_limit','4096M');
	ini_set('max_execution_time', 0);
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$msg = "_error"; $html = ""; $callback = "";
	$stock_id = (int) Input::post('stock_id', 0);
	$field = "`stock_type`,`project_id`,`block_id`,`building_id`";
	$field.= ",`ms_code`,`code`,`floor`,`bedroom_id`,`more_information`";
	$oneStock = $clsStock->getOne($stock_id, $field);
	// $clsISO->print_pre($oneStock); die();
	$stock_type = $oneStock['stock_type'];
	$project_id = $oneStock['project_id'];
	$block_id = $oneStock['block_id'];
	$bedroom_id = $oneStock['bedroom_id'];
	$building_id = $oneStock['building_id'];
	$stock_information = $oneStock['more_information'];
	$stock_information = $clsISO->to_array_json($stock_information);
	###
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
		$regex = sprintf('%s_%s', $project_id, $block_id);
	} else {
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
	}
	$onePolicy = $clsPolicy->getByCond("`block_type`='{$stock_type}' and `ms_date`<='".time()."' 
	and `scope_slash` like '%|{$regex}|%' order by ms_date DESC limit 0,1");
	if(!empty($onePolicy)){
		$more_information = $onePolicy['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		// Tải thư viện
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes([Google_Service_Drive::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
		// Khởi tạo dịch vụ Google Drive và Sheets
		$drive = new \Google_Service_Drive($client);
		$service = new \Google_Service_Sheets($client);
		###
		$price_sheet_id = $more_information['price_sheet_id'];
		// Tải file Excel từ Google Drive
		try {
			$msg = "_success";
			$response = $drive->files->get($price_sheet_id, array(
				'supportsAllDrives' => 'true'
			));
			// Chuyển đổi file Excel thành Google Sheets
			$fileMetadata = new \Google_Service_Drive_DriveFile(array(
				'name' => sprintf('%s_%s',$oneStock['ms_code'], date('d-m-y h:i:s'))
			));
			$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
			$convertedFile = $drive->files->copy($price_sheet_id, $fileMetadata, array(
				'supportsAllDrives' => 'true'
			));
			$spreadsheetId = $convertedFile->id;
			// Ghi dữ liệu vào file Configs
			$values = [
				['','','','',$oneStock['ms_code'],$oneStock['floor'],$oneStock['code'],$clsProperty->getTitle($bedroom_id),
					formatNumber($stock_information['DT_TT']),formatNumber($stock_information['DT_Tim']),$stock_information['total_price_vat']],
			];
			// $clsISO->print_pre($values); die();
			$body = new Google_Service_Sheets_ValueRange(['values' => $values]);
			$range = 'Configs';
			$service->spreadsheets_values->append($spreadsheetId, $range, $body, array(
				'valueInputOption' => 'RAW'
			));
			###
			$newValue = $oneStock['ms_code'];
			$body = new Google_Service_Sheets_ValueRange([
				'values' => [[$newValue]]
			]);
			$range = trim($more_information['spreadsheet_cell_stock']);
			$service->spreadsheets_values->update($spreadsheetId, $range, $body, array(
				'valueInputOption' => 'USER_ENTERED'
			));
			$url = sprintf('https://docs.google.com/spreadsheets/d/%s/export?format=xlsx', $spreadsheetId);
			$html = sprintf('<a id="price_sheet_%s" class="d-none"  href="%s"></a>', $uid, $url);
			$callback = 'setTimeout(() => {
				document.getElementById(\'price_sheet_'.$uid.'\').click();
				document.getElementById(\'price_sheet_'.$uid.'\').remove();
			}, 500);';
		} catch (Google_Service_Exception $e) {
			
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
		'callback' => $callback
	)); die();
}
function project_map(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$title_page,$description_page,$keyword_page,$clsISO;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$project_id = (int) Input::get('project_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	if($project_id == 0 || $building_id == 0){
		header('Location : /');
		exit();
	}
	$oneProject = $clsProject->getOne($project_id);
	$assign_list["oneProject"] = $oneProject;
	###
	$field = "title,for_id,more_information,intro";
	$oneBuilding = $clsProperty->getOne($building_id, $field);
	// $clsISO->print_pre($oneBuilding); die();
	$block_id = $oneBuilding['for_id'];
	$more_information = $oneBuilding['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$img_layout = isset($more_information['layout_map']) 
		? sprintf('%s?v=%s', $more_information['layout_map'], time()) : "";
	
	$block_information = $clsProperty->getOneField('more_information', $block_id);
	$block_information = $clsISO->to_array_json($block_information);
	$vr_link = isset($block_information['vr_link']) && !empty($block_information['vr_link']) 
		? $block_information['vr_link'] : "";
	$assign_list["vr_link"] = $vr_link;
	// $clsISO->print_pre($more_information); die();
	$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
	$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
	order by ms_date DESC limit 0,1");
	#
	$list_help_links = $clsProjectMeta->getAll("is_trash=0 and ((`type`='building' and `for_id`='{$building_id}') 
	or (`type`='block' and `for_id`='{$block_id}')) order by `type` ASC", "type,for_id,title,content");
	if(!empty($list_help_links)){
		$arr_cached_property = array();
		foreach($list_help_links as $key => $val){
			$type = $val['type'];
			$for_id = $val['for_id'];
			if($for_id==$building_id){
				$arr_cached_property[$for_id] = $oneBuilding['title'];
			} else {
				if(!isset($arr_cached_property[$for_id])){
					$arr_cached_property[$for_id] = $clsProperty->getTitle($for_id);
				}
			}
			$list_help_links[$key]['title'] = sprintf('%s %s', $val['title'], $arr_cached_property[$for_id]);
			$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
			$list_help_links[$key]['is_driver'] = $is_driver;
		}
	}
	$assign_list["list_help_links"] = $list_help_links;
	$assign_list["onePolicy"] = $onePolicy;
	$assign_list["oneBuilding"] = $oneBuilding;
	$assign_list["img_layout"] = $img_layout;
	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	/*=============Title & Description Page==================*/
	$title_page = sprintf('Mặt bằng tòa %s, dự án %s | ', $oneBuilding['title'], $oneProject['title']) . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_get_map(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$title_page,$description_page,$keyword_page,$clsISO,$deviceType;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$uid = $clsISO->getUniqid();
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$more_information = $clsProperty->getOneField('more_information', $building_id);
	$more_information = $clsISO->to_array_json($more_information);
	$img_layout = !empty($more_information['layout_map_FH']) 
		? sprintf('%s?v=%s', $more_information['layout_map_FH'], time()) : "";
	$tmp = $clsStockShape->getByCond("`holderG`='stock_FH' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}'");
	$shapes = !empty($tmp["shapes"]) ? $clsISO->to_array_json($tmp["shapes"]) : array();
	$html_point = "";
	if(!empty($shapes)) {
		foreach ($shapes as $key => $val) {
			$html_point.= '<div id="item_drag_'.$key.'_'.$uid.'" shap_id="'.$key.'" class="draggable item_drag_'.$key.'_'.$uid.' item_drag ui-draggable ui-draggable-handle" project_id="'.$tmp["project_id"].'" block_id="'.$tmp["block_id"].'" building_id="'.$tmp["building_id"].'" code="'.$val["code"].'" top="'.$val["top"].'" left="'.$val["left"].'" style="top:'.$val["top"].'%;left:'.$val["left"].'%;"></div>';
		}
	}
	$html = '<div class="modal-dialog modal-'.($deviceType=='phone'?'fullscreen':' modal-lg modal-dialog-centered').'">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Quỹ căn độc quyền</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div id="map_'.$uid.'" class="map map_stock_fh" style="max-width:1000px; margin: auto;position:relative">
					'.$html_point.'
					<img class="w-100 rounded-2" src="'.$img_layout.'">
					<button type="button" class="btn btn-icon btn-sm bg-white btn_drag position-absolute" onclick="$Core.project.dragabled(this,event)" title="Chỉnh sửa"><i class="bx bx-pencil"></i></button>
					<button type="button" class="btn btn-icon btn-sm bg-white btn_download position-absolute" onclick="$Core.project.download_map(this,event)" title="Tải về"><i class="bx bx-download"></i></button>
				</div>
			</div>
			<input type="hidden" name="block_id" value="'.$block_id.'">
			<input type="hidden" name="building_id" value="'.$building_id.'">
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'img_layout' => $img_layout,
	)); die();
}
function project_render_stock_map(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$html_shapes = ""; $holderG = 'stock';
	$to_map = Input::post('to_map', "");
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$_results = array('msg' => '_error', 'holderG' => $holderG);
	###
	$field = "{$clsStockShape->pkey},`shapes`,`shapes_render`,`map_configs`";
	$cond = "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `block_id`='{$block_id}' 
	and `building_id`='{$building_id}' AND `holderG`='stock_FH'";
	$tmp = $clsStockShape->getByCond($cond, $field);
	if(!empty($tmp)){
		if(!empty($tmp['shapes'])) {			
			$shapes = $tmp['shapes'];
			$shapes_render = $tmp['shapes_render'];
			$shapes = $clsISO->to_array_json($shapes);	
			$shapes_render = $clsISO->to_array_json($shapes_render);
			$_results['is_shape_render'] = !empty($shapes_render) ? 1 : 0;
			foreach($shapes as $key => $val) {	
				$field = "{$clsStock->pkey},`home_direction_id`,`total_price_vat`,`more_information`,`ms_code`";
				$list_stocks = $clsStock->getAll("`is_trash`=0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' 
				AND `code`='".$val['code']."' AND `building_id`='{$building_id}' AND `agency_id`='"._AGENCY_FH_ID."'", $field);
				if(!empty($list_stocks)) {
					foreach($list_stocks as $k => $v) {
						$stock_id = $v[$clsStock->pkey];
						$home_direction_id = (int) $v['home_direction_id'].
						$more_information = $v['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$DT_TT = $core->get_field($more_information, "DT_TT", 0);
						$total_price_bank = $core->get_field($more_information, "total_price_bank", 0);
						$total_price_early = $core->get_field($more_information, "total_price_early", 0);
						$is_price_early = 1;
						if(empty($total_price_early)){
							$is_price_early = 0;
							$total_price_early = $v['total_price_vat'];
						}
						$pos_top = !empty($shapes_render[$stock_id]['top']) ? $shapes_render[$stock_id]['top'] : 0;
						$pos_left = !empty($shapes_render[$stock_id]['left']) ? $shapes_render[$stock_id]['left'] : 0;
						$html_shapes .= '<div id="item_tooltip_'.$key.'_'.$to_map.'" class="item_tooltip item_tooltip_'.$key.'_'.$to_map.'" toid="'.$key.'_'.$to_map.'" shap_id="'.$key.'_'.$to_map.'" block_id="'.$block_id.'" building_id="'.$building_id.'" stock_id="'.$stock_id.'" top="'.$pos_top.'" left="'.$pos_left.'" style="left:'.$pos_left.'%; top:'.$pos_top.'%;">
							<div class="box_code">'.$v["ms_code"].'</div>
							<div class="body_tooltip">
								<div class="form-row">
									<div class="col-6 col-xs-6">
										<div class="d-flex flex-column box_text">
											<span>Thông thủy</span>
											<span class="text-value">'.$DT_TT.'m<sup>2</sup></span>
										</div>
										<div class="d-flex flex-column box_text">
											<span>'.($is_price_early?'Giá TTS':'Full VAT').'</span>
											<span class="text-price">'.$clsISO->shortNumber($total_price_early).'</span>
										</div>
									</div>
									<div class="col-6 col-xs-6">
										<div class="d-flex flex-column box_text">
											<span class="">Hướng</span>
											<span class="text-value">'.$clsProperty->getTitle($home_direction_id).'</span>
										</div>
										<div class="d-flex flex-column box_text">
											<span class="">Giá vay</span>
											<span class="text-price">'.$clsISO->shortNumber($total_price_bank).'</span>
										</div>
									</div>
								</div>
							</div>
						</div>';
					}
				}
				unset($list_stocks);
			}
		}
		$_results['msg'] = '_success';
		$_results['html_shapes'] = $html_shapes;
	}
	// Return
	echo json_encode($_results); die();
}
function project_update_shape_render(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsUser = new User();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStockShape = new StockShape();
	$clsStock = new Stock();
	###
	$msg  = "_error";
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$shapes_render = Input::post('shapes_render', "");
	$cond = "`stock_type`='".$stock_type."'  AND `holderG`='stock_FH'";
	if($block_id > 0) $cond.= " and `block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " and `building_id`='{$building_id}'";
	$tmp = $clsStockShape->getByCond($cond);
	if(!empty($tmp)){
		if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
			'shapes_render' => json_encode($shapes_render, JSON_UNESCAPED_UNICODE),
			'user_id_update' => $core->_USER['user_id'],
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		"msg"	=>	$msg
	)); die();
}
function project_draw_shapes(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	$clsConfiguration = new Configuration();
	###
	$holderG = 'stock';
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$_results = array('msg' => '_error', 'holderG' => $holderG);
	###
	$field = "{$clsStockShape->pkey},shapes,map_configs";
	$cond = "`holderG`='stock' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}'";
	$tmp = $clsStockShape->getByCond($cond, $field);
	if(empty($tmp)){
		$tmp = $clsStockShape->getByCond("`holderG`='stock' AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' AND JSON_EXTRACT(`map_configs`,\"$.list_building_slash\") like '%|{$building_id}|%' limit 0,1", $field);
	}
	// $clsISO->print_pre($tmp); die();
	if(!empty($tmp)){
		$shapes = $tmp['shapes'];
		$map_configs = $tmp['map_configs'];
		$shapes = $clsISO->to_array_json($shapes);
		$map_configs = $clsISO->to_array_json($map_configs);
		// $clsISO->print_pre($shapes); die();
		if(!empty($shapes)){
			$list_stock_ids = $list_shapes = $list_marker_shapes = array();
			$sql_string = "`is_trash`=0";
			if(!$clsISO->checkPermission('view_stock_hidden')){
				$arr_hid_blocks = array();
				$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
				$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
				if(!empty($agency_hidden_stock_FH)){
					foreach ($agency_hidden_stock_FH as $key => $val) {
						$hid_block_id = (int) $val['block_id'];
						if($hid_block_id > 0 && !in_array($hid_block_id, $arr_hid_blocks)) {
							$arr_hid_blocks[] = $hid_block_id;	
						}		
					}
				}
				$arr_hid_agency = array();
				$list_agency = $clsProperty->getCacheItems("_AGENCY"); 
				if(!in_array($block_id, $arr_hid_blocks)){
					foreach($list_agency as $key => $val){
						$agency_id = $val[$clsProperty->pkey];
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$hide_stock_vin = $core->get_field($more_information, 'FH_stock_vin', 0);
						if((int) $hide_stock_vin==1){
							$arr_hid_agency[] = $agency_id;
						}
					}
				} else {
					foreach($list_agency as $key => $val){
						$agency_id = $val[$clsProperty->pkey];
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$hid_field = sprintf('FH_%s_%s', $agency_id, $block_id);
						if((int) $core->get_field($more_information, $hid_field, 0) == 1){
							$arr_hid_agency[] = $agency_id;
						}
					}
				}
				$list_ag_notins = $clsProperty->getAll("`is_locked`=0 AND `is_trash`=1 
					AND `property_type`='_AGENCY'", $clsProperty->pkey);
				if(!empty($list_ag_notins)){
					foreach($list_ag_notins as $key => $val){
						$arr_hid_agency[] = $val[$clsProperty->pkey];
					}
				}
				if(!empty($arr_hid_agency)){
					$sql_string.= " and `agency_id` not in (".implode(',', $arr_hid_agency).")";
				}
			}
			// $clsISO->print_pre($sql_string); die();
			foreach($shapes as $key => $val){
				$stock_id = $val['stock_id'];
				$shape_type = $val['shape_type'];
				if($shape_type == 'circlemarker'){
					$oneStock = $clsStock->getOne($stock_id, "`project_id`,`building_id`,`code`");
					$project_id = (int) $oneStock['project_id'];
					$building_id = (int) $oneStock['building_id'];
					$code = $oneStock['code'];
					$cond= "{$sql_string} and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'
						and `project_id`='{$project_id}' and `block_id`='{$block_id}' and `agency_id`>0 
						and `building_id`='{$building_id}' and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
						and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`>0 and `code`='{$code}'";
					if($clsStock->countItem($cond) > 0){
						$list_marker_shapes[] = $val;
					}
				} else if($stock_id >0 && !in_array($stock_id, $list_stock_ids)){
					$list_shapes[$stock_id] = $val;
					$list_stock_ids[] = $stock_id;	
				}
			}
			$shapes = $list_marker_shapes;
			/* if(!empty($list_stock_ids) && !empty($list_shapes)){
				$field = "{$clsStock->pkey},bedroom_id,home_direction_id,more_information";
				$list_stocks = $clsStock->getAll("{$sql_string} AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
					AND (`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `status_id`<>'"._STOCK_STATUS_NON_ID."') 
					AND {$clsStock->pkey} in (".implode(',', $list_stock_ids).")", $field);
				// $clsISO->print_pre($list_stocks); die();
				$arr_property_cached = array();
				foreach($list_stocks as $key => $val){
					$stock_id = $val[$clsStock->pkey];
					$bedroom_id = $val['bedroom_id'];
					$home_direction_id = $val['home_direction_id'];
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					if($bedroom_id > 0 && !isset($arr_property_cached[$bedroom_id])){
						$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
					}
					if($home_direction_id > 0 && !isset($arr_property_cached[$home_direction_id])){
						$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
					}
					$list_shapes[$stock_id]['bedroom'] = $arr_property_cached[$bedroom_id];
					$list_shapes[$stock_id]['home_direction'] = $arr_property_cached[$home_direction_id];
					$list_shapes[$stock_id]['DT_Tim'] = $more_information['DT_Tim'];
					$list_shapes[$stock_id]['DT_TT'] = $more_information['DT_TT'];
					$is_early = 0;
					$total_price_vat = $more_information['total_price_vat'];
					$total_price_early = $more_information['total_price_early'];
					$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
					$list_shapes[$stock_id]['dg_price'] = $clsISO->priceFormatV2($total_price_vat,3);
					$shapes[] = $list_shapes[$stock_id];
				} 
			}*/
		}
		$_results['msg'] = '_success';
		$_results['shapes'] = $shapes;
		$_results['map_configs'] = $map_configs;
		$_results['stock_shape_id'] = $tmp[$clsStockShape->pkey];
	}
	// Return
	echo json_encode($_results); die();
}
function project_layout(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$title_page,$description_page,$keyword_page,$clsISO;
	$clsCache = new Cache();
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$block_id = (int) Input::get('block_id', 0);
	$project_id = (int) Input::get('project_id', 0);
	if($project_id == 0){
		header('Location : /');
		exit();
	}
	$field = "{$clsProject->pkey},title,more_information";
	$oneProject = $clsProject->getOne($project_id, $field);
	$more_information = $oneProject['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$img_layout = $core->get_field($more_information, "layout", "");
	$has_block = (int) $core->get_field($more_information, "has_block", 1);
	$scriptJS = '<script type="text/javascript">
		var image_maps = {},
			project_id = \''.$project_id.'\',
			block_id = \''.$block_id.'\';
			image_maps[\'project\'] = \''.sprintf('%s%s', FH_URL, $img_layout).'\';';
	$list_blocks = $list_img_preloaders = array();
	if($has_block == 1){
		if($clsCache->has('_stock_block_'.$project_id.'_cached')){
			$list_blocks = $clsCache->get('_stock_block_'.$project_id.'_cached');
			// $clsISO->print_pre($list_blocks); die();
		} else {
			$field = "{$clsProperty->pkey},`title`,`more_information`";
			$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}' 
			and `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` ASC", $field);
			if(!empty($list_blocks)){
				foreach($list_blocks as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_blocks[$key]['more_information'] = $more_information;
				}
				$clsCache->put('_stock_block_'.$project_id.'_cached', $list_blocks);
			} else {
				if($clsCache->has('_stock_block_'.$project_id.'_cached')){
					$clsCache->delete('_stock_block_'.$project_id.'_cached');
				}
			}
		}
	}
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$block_id = $val[$clsProperty->pkey];
			$more_information = $val['more_information'];
			$layout_ms = $core->get_field($more_information, "layout_ms", "");
			if(!empty($layout_ms)){
				$scriptJS.= '
			image_maps['.$block_id.']=\''.sprintf('%s%s', FH_URL, $layout_ms).'\';';
				$list_img_preloaders[] = sprintf('%s%s', FH_URL, $layout_ms);
			}
		}
	}
	$scriptJS.= '</script>';
	###
	$total_stocks = $clsStock->countItem("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
		and project_id='{$project_id}' and status_id>0 and status_id<>'"._STOCK_STATUS_SOLD_ID."' and `agency_id`>0");
	// $clsISO->print_pre($total_stock); die();	
	$assign_list["total_stocks"] = $total_stocks;
	###
	$assign_list["img_layout"] = $img_layout;
	$assign_list["project_id"] = $project_id;
	$assign_list["oneProject"] = $oneProject;
	$assign_list["scriptJS"] = $scriptJS;
	$assign_list["list_img_preloaders"] = $list_img_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = sprintf('Mặt bằng dự án %s - ', $oneProject['title']) . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_get_img_layout(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$tp = Input::post('tp', 'project');
	$pval_id = (int) Input::post('pval_id', 0);
	if($tp == 'project'){
		$field = "layout";
		$clsClassTable = new Project();
	} else if($tp == 'block'){
		$field = "layout_ms";
		$clsClassTable = new Property();
	}
	$more_information = $clsClassTable->getOneField("more_information", $pval_id);
	$more_information = $clsISO->to_array_json($more_information);
	$img_layout = !empty($more_information[$field]) 
		? sprintf('%s?v=%s', $more_information[$field], time()) : "";
	// Return
	echo json_encode(array(
		'img_layout' => $img_layout
	)); die();
}
function project_get_shapes(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$holderG = 'stock';
	$block_id = (int) Input::post('block_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$oneProject = $clsProject->getOne($project_id, "more_information");
	$more_information = $oneProject['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$has_block = (int) $core->get_field($more_information, 'has_block', 0);
	$_results = array('msg' => '_error', 'holderG' => $holderG);
	###
	$cond = "`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `holderG`<>'project' and `project_id`='{$project_id}'";
	if($block_id > 0) {
		$cond.= " and `block_id`='{$block_id}'";
	} else if($has_block == 1) {
		$holderG = 'block';
		$_results['holderG'] = 'block';
	}
	$tmp = $clsStockShape->getByCond($cond);
	if(!empty($tmp)){
		$shapes = $tmp['shapes'];
		$shapes = $clsISO->to_array_json($shapes);
		if(!empty($shapes)){
			if($holderG == 'block' && $has_block == 1){
				foreach($shapes as $key => $val){
					$block_id = $val['block_id'];
					$shapes[$key]['name'] = $clsProperty->getTitle($block_id);
				}
			} else {
				$list_stock_ids = $list_shapes = array();
				foreach($shapes as $key => $val){
					$stock_id = $val['stock_id'];
					if($stock_id >0 && !in_array($stock_id, $list_stock_ids)){
						$list_shapes[$stock_id] = $val;
						$list_stock_ids[] = $stock_id;	
					}
				}
				$shapes = array();
				if(!empty($list_stock_ids) && !empty($list_shapes)){
					$list_stocks = $clsStock->getAll("`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
					and project_id='{$project_id}' AND `agency_id`>0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND {$clsStock->pkey} in (".implode(',', $list_stock_ids).")", "{$clsStock->pkey},status_id,agency_id");
					foreach($list_stocks as $key => $val){
						$stock_id = $val[$clsStock->pkey];
						$oneShape = $list_shapes[$stock_id];
						$oneShape['status_id'] = $val['status_id'];
						$oneShape['agency_id'] = $val['agency_id'];
						$shapes[] = $oneShape;
					}
				}
			}
		}
		$_results['msg'] = '_success';
		$_results['shapes'] = $shapes;
		$total_record = ($block_id > 0) ? count($shapes) : $clsStock->countItem("`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and project_id='{$project_id}' AND `agency_id`>0 AND `status_id`>0 AND status_id<>'"._STOCK_STATUS_SOLD_ID."' AND `status_id`<>'"._STOCK_STATUS_NON_ID."'");
		$_results['total_record'] = $total_record;
		$_results['_STOCK_STATUS_SOLD_ID'] = _STOCK_STATUS_SOLD_ID;
	}
	// Return
	echo json_encode($_results); die();
}
function project_open_model(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id,$smarty;
	$clsProperty = new Property();
	$clsProjectMeta = new ProjectMeta(); 
	$smarty->assign("clsProjectMeta",$clsProjectMeta);
	###
	$uid = $clsISO->getUniqid();
	$view_type = Input::post('_type', "is_model");
	$building_id = (int) Input::post('building_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$cond = "`t1`.`is_trash`='0'";
	if(!empty($building_id) ) {
		$cond.= " AND (`t1`.`type`='building' 
			OR `t1`.`type`='block'
		) AND (`t1`.`for_id`='{$building_id}' OR `t1`.`for_id`='{$block_id}')";
	}else if(!empty($block_id)) {
		$cond.= " AND `t1`.`type`='block' AND `t1`.`for_id`='{$block_id}'";
	}else{	
		$cond .= " AND ((`t1`.type = 'project' AND `t1`.for_id = '{$project_id}') 
			OR (`t1`.type = 'block' AND EXISTS (
				SELECT 1 FROM {$clsProperty->tbl} `t2` 
				WHERE `t2`.property_id = `t1`.for_id 
				AND `t2`.property_type = '_BLOCK' 
				AND `t2`.for_id = '{$project_id}')
			) OR (`t1`.type = 'building' AND EXISTS (
				SELECT 1 FROM {$clsProperty->tbl} `t3` 
				WHERE `t3`.property_id = `t1`.for_id 
				AND `t3`.property_type = '_BUILDING' 
				AND EXISTS (
				   SELECT 1 FROM {$clsProperty->tbl} `t4` 
				   WHERE `t4`.property_id = `t3`.for_id 
				   AND `t4`.property_type = '_BLOCK' 
				   AND `t4`.parent_id = '"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
				   AND `t4`.for_id = '{$project_id}'
				)
			)
		))";	
	}
	$arr_cache = $list_docs = array();
	$tmp = $dbconn->getAll( "SELECT * FROM {$clsProjectMeta->tbl} `t1` 
		WHERE ".$cond." AND JSON_EXTRACT(`more_information`,\"$.".$view_type."\")='1' order by `reg_date` DESC");
	if(!empty($tmp)){
		foreach($tmp as $okey => $oval){
			if(!isset($array_cache[$oval["for_id"]])) {
				$array_cache[$oval["for_id"]] = $clsProperty->getTitle($oval["for_id"]);
			}
			$oval['title'] = $oval['title'] . " - " . $array_cache[$oval["for_id"]];
			$list_images = array();
			$content = $oval['content'];
			$more_information = $oval['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			
			$oval['_type'] = $more_information['type'];
			$is_expanded = isset($more_information['is_expanded']) 
				? (int) $more_information['is_expanded'] : 0; 
			$list_files = isset($more_information['list_files']) 
				? $more_information['list_files'] : array();
			$type = $more_information['type'];
			$oval['more_information'] = $more_information;	
			if(!empty($list_files)){ 
				if(count($list_files) > 1){
					$kk = 0;
					foreach($list_files as $nkey => $nval){
						if($clsISO->isFileVideo($nval)){
							$_type = 'video';
						} else if($clsISO->isPDF($nval)){
							$_type = 'pdf';
						} else {
							$_type = 'google.file';
						}
						$oval['type'] = $_type;
						// Nếu ảnh đầu tiên là video
						if($kk == 0){
							if(in_array($_type, array('video'))) {
								$oval['type'] = $more_information['type'];
							}
							$oval['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
						}
						$oval['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');
						$kk>0 && $list_images[] = array(
							'gid' => $nkey,
							'type' => $_type,
							'image' =>  $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'))
						);
						++$kk;
					}
				} else {
					$kk = 0;
					foreach($list_files as $nkey => $nval){
						if($clsISO->isFileVideo($nval)){
							$_type = 'video';
						} else if($clsISO->isPDF($nval)){
							$_type = 'pdf';
						} else {
							$_type = 'google.file';
						}
						$oval['type'] = $_type;
						if($kk == 0){
							if(in_array($_type, array('video'))) {
								$oval['type'] = $more_information['type'];
							}
							$oval['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
						}
						$oval['link_download'] = $clsISO->genGoogleURL($nkey, 'download');
					}
				}									
			} else {
				$oval['image'] = $more_information['image'];
				$oval['type'] = $more_information['type'];
				$oval['link_download'] = $clsISO->getDownloadURL($oval['content']);
				if(in_array($more_information['type'], array('video','pdf'))){
					$oval['link'] = $clsISO->getIframeUrl($oval['content']);
				} else {
					$oval['link'] = $clsISO->getGoogleUrl($oval['content']);
				}
			}
			$oval['list_images'] = $list_images;
			$oval['image'] = $more_information['image'];
			$list_docs[] = $oval;
		}
	}
	$smarty->assign("uid",$uid);
	$smarty->assign("view_type",$view_type);
	$smarty->assign("list_docs",$list_docs);
	$html = $core->build("project".DS."_ajax.load_model.tpl");
	// Return
	echo json_encode(array(
		"html"	=>	$html,
		"uid"	=>	$uid,
		"list_docs"	=> $list_docs
	)); die();
}
function project_hide_stock_cross(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$building_id = (int) Input::post('building_id', 0);
	if($building_id > 0){
		$more_information = $clsProperty->getOneField("more_information", $building_id);
		$more_information = $clsISO->to_array_json($more_information);
		$arr_hide_stock_cross = $core->get_field($more_information, "hide_stock_cross", []);
		if(!empty($arr_hide_stock_cross) && isset($arr_hide_stock_cross[$profile_id])){
			if((int) $arr_hide_stock_cross[$profile_id] == 1){
				$arr_hide_stock_cross[$profile_id] = 0;
			} else {
				$arr_hide_stock_cross[$profile_id] = 1;
			}
		} else {
			$arr_hide_stock_cross[$profile_id] = 1;
		}
		$more_information['hide_stock_cross'] = $arr_hide_stock_cross;
		// $clsISO->print_pre($more_information); die();
		if($clsProperty->updateOne($building_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}