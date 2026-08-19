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
		$list_props = $clsProjectMeta->getAll("`type`='block' and `block_ids` LIKE '%|{$block_id}|%' order by `order_no` ASC");
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
		$list_props = $clsProjectMeta->getAll("((`type`='building' and `building_ids` LIKE '%|{$building_id}|%') or (`type`='block' and `block_ids` LIKE '%|{$block_id}|%')) 
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
		$list_props = $clsProjectMeta->getAll("`type`='project' and ".$clsProjectMeta->condByProject($for_id)." order by `order_no` ASC");
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
	$clsSetting = new Setting();
	$clsProject = new Project();
	$clsProperty = new Property();
	# Ten chu dau tu: hien tren the du an + lam bo loc
	$arr_investor_titles = [];
	$tmp_inv = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_INVESTOR'", "`{$clsProperty->pkey}`,`title`");
	if(!empty($tmp_inv)){
		foreach($tmp_inv as $oval){
			$arr_investor_titles[(int) $oval[$clsProperty->pkey]] = $oval['title'];
		}
		unset($tmp_inv);
	}
	# KPI dau trang + du lieu cho cac select loc
	$arr_kpis = ['total' => 0, 'open' => 0, 'soon' => 0];
	$arr_filter_investors = $arr_filter_cities = $arr_kpi_investors = $arr_kpi_cities = [];

	$lstArea = $clsSetting->getArraySearchByKey("_AREA");
	$arr_project_area = $arr_area_cities = [];
	if(!empty($list_projects)) {
		$arr_city_titles = [];
		$clsCity = new City();
		$tmp_cities = $clsCity->getAll("`is_trash`=0 AND `country_id`=1", "`{$clsCity->pkey}`,`title`");
		if(!empty($tmp_cities)){
			foreach($tmp_cities as $oval){
				$arr_city_titles[(int) $oval[$clsCity->pkey]] = $oval['title'];
			}
			unset($tmp_cities);
		}
		foreach ($list_projects as $key => $val) {
			$area_id = $val["area_id"];
			$city_id = (int) $core->get_field($val["more_information"], "city_id", 0);
			if(!isset($arr_city_titles[$city_id])) $city_id = 0;
			$val["city_id"] = $city_id;
			if($city_id > 0){
				if(!isset($arr_area_cities[$area_id][$city_id])){
					$arr_area_cities[$area_id][$city_id] = [
						'city_id'	=> $city_id,
						'title'		=> $arr_city_titles[$city_id],
						'total'		=> 0
					];
				}
				++$arr_area_cities[$area_id][$city_id]['total'];
			}
			# Lam giau du lieu cho the du an (giao dien moi)
			$mi = $val["more_information"];
			$val["city_title"] = ($city_id > 0) ? $arr_city_titles[$city_id] : "";
			$val["building"] = $core->get_field($mi, "building", "");
			$val["apartment"] = $core->get_field($mi, "apartment", "");
			$val["arcreage"] = $core->get_field($mi, "arcreage", "");
			$val["building_density"] = $core->get_field($mi, "building_density", "");
			$val["vr_link"] = trim($core->get_field($mi, "vr_link", ""));
			$project_status = $core->get_field($mi, "project_status", "open");
			if(!in_array($project_status, array("open", "soon", "research"), true)){
				$project_status = "open";
			}
			$val["project_status"] = $project_status;
			$investor_id = (int) $core->get_field($mi, "investor_id", 0);
			$val["investor_id"] = $investor_id;
			$val["investor_name"] = ($investor_id > 0 && isset($arr_investor_titles[$investor_id])) ? $arr_investor_titles[$investor_id] : "";
			# Loai hinh: suy tu list_block_type cua du an
			# _header da convert list_block_type thanh mang san - chi explode khi con la chuoi
			if(is_array($val["list_block_type"])){
				$block_types = $val["list_block_type"];
			} else {
				$block_types = !empty($val["list_block_type"]) ? $clsISO->getArrayByTextSlash($val["list_block_type"]) : array();
			}
			$has_high = $clsISO->checkItemInArray(_BLOCK_TYPE_HIGHLEVEL_SALE, $block_types);
			$has_low = $clsISO->checkItemInArray(_BLOCK_TYPE_LOWFLOOR_SALE, $block_types);
			if($has_high && $has_low){
				$val["type_key"] = "mix";
				$val["type_label"] = "Cao + Thấp tầng";
			} else if($has_low){
				$val["type_key"] = "thap";
				$val["type_label"] = "Thấp tầng";
			} else {
				$val["type_key"] = "cao";
				$val["type_label"] = "Cao tầng";
			}
			# Dem tien ich: cot `utilities` la JSON tren bang project
			$utilities_raw = isset($val["utilities"]) ? $val["utilities"] : $clsProject->getOneField('utilities', $val[$clsProject->pkey]);
			$tmp_utl = $clsISO->to_array_json($utilities_raw);
			$val["total_utilities"] = !empty($tmp_utl) ? count($tmp_utl) : 0;
			# Cong don KPI + du lieu bo loc
			++$arr_kpis['total'];
			if($project_status == 'open') ++$arr_kpis['open'];
			if($project_status == 'soon') ++$arr_kpis['soon'];
			if($investor_id > 0){
				$arr_kpi_investors[$investor_id] = 1;
				$arr_filter_investors[$investor_id] = $val["investor_name"];
			}
			if($city_id > 0){
				$arr_kpi_cities[$city_id] = 1;
				$arr_filter_cities[$city_id] = $arr_city_titles[$city_id];
			}
			$arr_project_area[$area_id][] = $val;
		}
		// Sắp tab theo city_id để thứ tự bám đúng thứ tự tỉnh đã đánh trong bảng default_city.
		if(!empty($arr_area_cities)){
			foreach($arr_area_cities as $area_id => $cities){
				ksort($arr_area_cities[$area_id]);
			}
		}
	}
	//$clsISO->print_pre($arr_area_cities);die;

	$arr_kpis['investors'] = count($arr_kpi_investors);
	$arr_kpis['cities'] = count($arr_kpi_cities);
	$assign_list["arr_kpis"] = $arr_kpis;
	$assign_list["arr_filter_investors"] = $arr_filter_investors;
	$assign_list["arr_filter_cities"] = $arr_filter_cities;
	$assign_list["lstArea"] = $lstArea;
	$assign_list["arr_project_area"] = $arr_project_area;
	$assign_list["arr_area_cities"] = $arr_area_cities;
	$assign_list["arr_project"] = $arr_project;
	$assign_list["clsSetting"] = $clsSetting;
	// $clsISO->print_pre($list_projects); die();
	/*=============Title & Description Page==================*/
	$title_page = 'Thông tin dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_stock_old(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$loggedIn,$oneBuilding,$dev;
	$clsStock = new Stock();
	$clsCache = new Cache();
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
	#khóa căn
	$arr_stock_lock = [];
	if($profile_id == 289) {
		$clsStockLock = new StockLock();
		$stock_lock = $clsStockLock->getAll("(".time()." BETWEEN `start_date` AND `end_date`) and `status_id`='0' ");
		foreach ($stock_lock as $key => $val) {
			$arr_stock_lock[] = $val["stock_id"];
		}
	}
	$assign_list["arr_stock_lock"] = $arr_stock_lock;
	###
	$view_stock_hidden = $clsISO->checkPermission('view_stock_hidden');
	$permiss_view_stock_resource = $clsISO->checkPermission('view_stock_resource');
	$assign_list["view_stock_hidden"] = $view_stock_hidden;
	$assign_list["permiss_view_stock_resource"] = $permiss_view_stock_resource;
	###
	$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
	$arr_not_ins = array(_STOCK_STATUS_NON_ID, _STOCK_STATUS_INSTOCK_ID);
	$list_status = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='_STATUS' 
		and `property_id` NOT IN (".implode(',',$arr_not_ins).") order by `order_no` ASC", $field);
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
	$oneProject = $clsProject->getOne($project_id,"more_information");
	$more_information_project = $clsISO->to_array_json($oneProject["more_information"]);
	$assign_list["more_information_project"] = $more_information_project;
	$block_type = (int) Input::get('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	if($block_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
		$building_id = (int) Input::get('building_id', 0);
		$oneBuilding = $clsProperty->getOne($building_id);
		$block_id = $oneBuilding['for_id']; // BLOCK
		$list_agency_cached_VIN = ${'list_agency_cached_'.$block_id} = array();
		if($clsCache->has('_stock_agency_cached')){
			$tmp = $clsCache->get('_stock_agency_cached');
		} else {
			$ag_field = "{$clsProperty->pkey},`more_information`,`is_trash`";
			$tmp = $clsProperty->getAll("`is_locked`=0 and `property_type`='_AGENCY'", $ag_field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$tmp[$key]['more_information'] = $more_information;
				}
			}
			$clsCache->put('_stock_agency_cached', $tmp, 60*60);
		}
		if(!$clsISO->checkPermission('view_stock_hidden') && !empty($tmp)){
			foreach($tmp as $key => $val){
				$agency_id = $val[$clsProperty->pkey];
				if($val['is_trash'] == 1){
					$list_agency_cached_VIN[$agency_id] = 1;
					${"list_agency_cached_".$block_id}[$agency_id] = 1;	
				} else {
					$list_agency_cached_VIN[$agency_id] = 0;
					${'list_agency_cached_'.$block_id}[$agency_id] = 0;
					$more_information = $val['more_information'];
					// $clsISO->print_pre($more_information); die();
					$hide_stock_vin_FH = $core->get_field($more_information, 'FH_stock_vin', 0);
					if((int) $hide_stock_vin_FH==1 && !in_array($block_id, $arr_hid_blocks)){
						$list_agency_cached_VIN[$agency_id] = 1;
					}
					$hid_field = sprintf('FH_%s_%s', $agency_id, $block_id);
					if((int) $core->get_field($more_information, $hid_field, 0) == 1){
						${'list_agency_cached_'.$block_id}[$agency_id] = 1;
					}				
				}
			}
		}
		$assign_list["list_agency_cached_VIN"] = $list_agency_cached_VIN;
		$assign_list["list_agency_cached_MAS"] = ${"list_agency_cached_".$block_id};
		// $clsISO->print_pre(${'list_agency_cached_'.$block_id}); die();
		$bedroom_bgcolor_arrs = $bedroom_textcolor_arrs = array();
		if($clsCache->has('_stock_bedroom_cached')){
			$list_bedroom = $clsCache->get('_stock_bedroom_cached');
		} else {
			$list_bedroom = $clsProperty->getAll("property_type='_BEDROOM' order by order_no ASC", $field);
			$clsCache->put('_stock_bedroom_cached', $list_bedroom);
		}
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
		$floor_config = $core->get_field($more_information, "floor_config", []);
		$floor_hierarchy = $core->get_field($more_information, "floor_hierarchy", []);
		$arr_hide_stock_cross = $core->get_field($more_information, "hide_stock_cross", []);	
		$is_symbol = !empty($more_information["is_symbol"]) ? 1: 0;			
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
		#tang thu cap
		$block_information = $clsProperty->getOneField('more_information', $block_id);
		$block_information = $clsISO->to_array_json($block_information);
		$vr_link = $core->get_field($block_information, "vr_link", "");
		$assign_list["vr_link"] = $vr_link;
		// $clsISO->print_pre($more_information); die();
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
		$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
		order by ms_date DESC limit 0,1");
		$assign_list["onePolicy"] = $onePolicy;
		$assign_list["oneBuilding"] = $oneBuilding;
		#
		$floor = $core->get_field($more_information, "floor", "");
		$stock_templates = $core->get_field($more_information, "template", []);
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
		$arr_floors = !empty($floor) ? @explode(',', $floor) : array();
		$arr_stocks  = $arr_stocks_specical= $arr_cols  = $arr_cols_specical  = $arr_cells = $arr_status_id_show = array();
		$cond_stock = "`project_id`='{$project_id}' and `building_id`='{$building_id}'";
		if(!$clsISO->checkPermission('view_stock_globe')){
			$cond_stock.= " and `show_website` like '%|user.fh|%'";
		}
		$list_stocks = $clsStock->getAll($cond_stock);
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$code = $val['code'];
				#tầng có căn đối xứng khác mã
				if(!empty($is_symbol)) {
					$more_stock = $clsISO->to_array_json($val['more_information']);
					$building_code = !empty($more_stock["building_code"]) ? $more_stock["building_code"] : "";						
					$code = $building_code.".".$code;
				}
				$floor = $val['floor'];
				$status_id  = $val['status_id'];
				$agency_id = $val['agency_id'];
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
				if($status_id != _STOCK_STATUS_SOLD_ID && !$clsISO->checkItemInArray($status_id,$arr_status_id_show) 
					&& !($agency_id > 0 && ($list_agency_cached_VIN[$agency_id] == 1 || ${"list_agency_cached_".$block_id}[$agency_id] == 1) && !$view_stock_hidden)){
					$arr_status_id_show[] = $val['status_id'];
				}
			}
			@usort($arr_stocks, function($a, $b){
				if((int) $a > (int) $b)
					return 1;
				return -1;
			});
			/*@usort($arr_floors,  function($a, $b){
				if((int) $a > (int) $b)
					return 1;
				return -1;
			});*/
		}
		$assign_list["arr_status_id_show"] = $arr_status_id_show;
		if(!empty($stock_templates)){
			foreach($stock_templates as $key => $val){
				$code = $val['code'];
				if(!empty($is_symbol)) {
					$code = $val["symbol"].".".$code;		
				}				
				$arr_stocks[] = $code;
				$arr_cols[$code] = $val;
				unset($code);
			}
		}
		if(!empty($stock_template_specical)){
			foreach($stock_template_specical as $key => $val){
				foreach ($val as $k => $v) {
					if(!empty($v['code'])) {
						$code = $v['code'];
						if(!empty($is_symbol)) {
							$code = $v["symbol"].".".$code;		
						}	
						$arr_stocks_specical[$key][] = $code;
						$arr_cols_specical[$key][$code] = $v;
						unset($code);	
					}					
				}				
			}
		}
		###
		$floor_service_arrs = $floor_merge_arrs = $floor_merge_header_arrs = array();
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
						$arr_floor = [];
						$index_plus += $a[1];
						for($j=$a[0]; $j<($a[0] + $a[1]); $j++) {
							$arr_merge[] = $j;							
							$cell_col_index[$j] = $index_plus;
							$arr_floor[] = $j;
						}
						$cell_merge_arrs[] = array(
							'start_cell' => $a[0],
							'arr_floor' => $arr_floor,
							'collspan' => $a[1],
							'rowspan' => $a[2] ? ((int)$a[2] + 1) : 1,
						);
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
				$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
				and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `project_id`='{$project_id}' 
				and `building_id`='{$building_id}' and `floor`='{$floor}' order by `code` ASC");
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
//			$clsISO->print_pre($floor_merge_arrs);die;
		}
		//$clsISO->print_pre($floor_merge_header_arrs); die();
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
		// $clsISO->print_pre($list_stocks); die();
		// Show map
		$is_map = 0;
		$tmp = $clsStockShape->getByCond("`holderG`='stock' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' and `building_id`='{$building_id}'", $clsStockShape->pkey);
		if(empty($tmp)){	
			$tmp = $clsStockShape->getByCond("`holderG`='stock' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' and JSON_EXTRACT(`map_configs`,\"$.list_building_slash\") like '%|{$building_id}|%' limit 0,1", $clsStockShape->pkey);
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
		$list_blocks = array();
		if($clsCache->has('_stock_block_'.$project_id.'_cached')){
			$list_blocks = $clsCache->get('_stock_block_'.$project_id.'_cached');
			// $clsISO->print_pre($list_blocks); die();
		} else {
			$field = "{$clsProperty->pkey},`title`,`more_information`";
			$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and `for_id`='{$project_id}' 
			and `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` ASC", $field);
			if(!empty($list_blocks)){
				foreach($list_blocks as $key => $val){
					$block_information = $val['more_information'];
					$block_information = $clsISO->to_array_json($block_information);
					$list_blocks[$key]['more_information'] = $block_information;
				}
				$clsCache->put('_stock_block_'.$project_id.'_cached', $list_blocks);
			} else {
				if($clsCache->has('_stock_block_'.$project_id.'_cached')){
					$clsCache->delete('_stock_block_'.$project_id.'_cached');
				}
			}
		}
		###
		$assign_list["list_blocks"] = $list_blocks;
		$title_page = $clsProperty->getTitle($block_id);
		$project_information = $clsProject->getOneField('more_information', $project_id);
		$project_information = $clsISO->to_array_json($project_information);
		$vr_link = $core->get_field($project_information, "vr_link", "");
		$vr_source = $core->get_field($project_information, "vr_source", "");
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
function project_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$loggedIn,$oneBuilding,$dev;
	$clsStock = new Stock();
	$clsCache = new Cache();
	$clsStockShape = new StockShape();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$clsPolicy = new Policy();
	$assign_list["clsStock"] = $clsStock;
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$stock_bg_sold = vnSessionGetVar("stock_bg_sold");
	$assign_list["stock_bg_sold"] = $stock_bg_sold;
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	#khóa căn
	$arr_stock_lock = [];
	if($profile_id == 289) {
		$clsStockLock = new StockLock();
		$stock_lock = $clsStockLock->getAll("(".time()." BETWEEN `start_date` AND `end_date`) and `status_id`='0' ");
		foreach ($stock_lock as $key => $val) {
			$arr_stock_lock[] = $val["stock_id"];
		}
	}
	$assign_list["arr_stock_lock"] = $arr_stock_lock;
	###
	$view_stock_hidden = $clsISO->checkPermission('view_stock_hidden');
	$permiss_view_stock_resource = $clsISO->checkPermission('view_stock_resource');
	$assign_list["view_stock_hidden"] = $view_stock_hidden;
	$assign_list["permiss_view_stock_resource"] = $permiss_view_stock_resource;
	###
	$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
	$arr_not_ins = array(_STOCK_STATUS_NON_ID, _STOCK_STATUS_INSTOCK_ID);
	$list_status = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='_STATUS' 
		and `property_id` NOT IN (".implode(',',$arr_not_ins).") order by `order_no` ASC", $field);
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
	$oneProject = $clsProject->getOne($project_id,"`title`,`more_information`");
	$more_information_project = $oneProject['more_information'];
	$more_information_project = $clsISO->to_array_json($more_information_project);
	$assign_list["more_information_project"] = $more_information_project;
	$block_type = (int) Input::get('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	if($block_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
		$building_id = (int) Input::get('building_id', 0);
		$oneBuilding = $clsProperty->getOne($building_id);
		$block_id = $oneBuilding['for_id']; // BLOCK
		$list_agency_cached_VIN = ${'list_agency_cached_'.$block_id} = array();
		if($clsCache->has('_stock_agency_cached')){
			$tmp = $clsCache->get('_stock_agency_cached');
		} else {
			$ag_field = "{$clsProperty->pkey},`more_information`,`is_trash`";
			$tmp = $clsProperty->getAll("`property_type`='_AGENCY' AND `is_locked`=0", $ag_field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$tmp[$key]['more_information'] = $more_information;
				}
			}
			$clsCache->put('_stock_agency_cached', $tmp);
		}
		if(!$clsISO->checkPermission('view_stock_hidden') && !empty($tmp)){
			foreach($tmp as $key => $val){
				$agency_id = $val[$clsProperty->pkey];
				if($val['is_trash'] == 1){
					$list_agency_cached_VIN[$agency_id] = 1;
					${"list_agency_cached_".$block_id}[$agency_id] = 1;	
				} else {
					$list_agency_cached_VIN[$agency_id] = 0;
					${'list_agency_cached_'.$block_id}[$agency_id] = 0;
					$more_information = $val['more_information'];
					$hide_stock_vin_FH = $core->get_field($more_information, 'FH_stock_vin', 0);
					if((int) $hide_stock_vin_FH==1 && !in_array($block_id, $arr_hid_blocks)){
						$list_agency_cached_VIN[$agency_id] = 1;
					}
					$hid_field = sprintf('FH_%s_%s', $agency_id, $block_id);
					if((int) $core->get_field($more_information, $hid_field, 0) == 1){
						${'list_agency_cached_'.$block_id}[$agency_id] = 1;
					}				
				}
			}
		}
		$assign_list["list_agency_cached_VIN"] = $list_agency_cached_VIN;
		$assign_list["list_agency_cached_MAS"] = ${"list_agency_cached_".$block_id};
		// $clsISO->print_pre(${'list_agency_cached_'.$block_id}); die();
		$bedroom_bgcolor_arrs = $bedroom_textcolor_arrs = array();
		if($clsCache->has('_stock_bedroom_cached')){
			$list_bedroom = $clsCache->get('_stock_bedroom_cached');
		} else {
			$list_bedroom = $clsProperty->getAll("property_type='_BEDROOM' order by order_no ASC", $field);
			$clsCache->put('_stock_bedroom_cached', $list_bedroom);
		}
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
		$floor_config = $core->get_field($more_information, "floor_config", []);
		$floor_hierarchy = $core->get_field($more_information, "floor_hierarchy", []);
		$arr_hide_stock_cross = $core->get_field($more_information, "hide_stock_cross", []);	
		$arr_hide_stock_common = $core->get_field($more_information, "hide_stock_common", []);	
		$is_symbol = !empty($more_information["is_symbol"]) ? 1: 0;			
		#- Ẩn quỹ chéo
		$is_hide_stock_cross = 0;
		if(!empty($arr_hide_stock_cross) && isset($arr_hide_stock_cross[$profile_id])){
			$is_hide_stock_cross = $arr_hide_stock_cross[$profile_id];
		}	
		#- Ẩn quỹ chung
		$is_hide_stock_common = 0;
		if(!empty($arr_hide_stock_common) && isset($arr_hide_stock_common[$profile_id])){
			$is_hide_stock_common = $arr_hide_stock_common[$profile_id];
		}
		$is_map_dq = isset($more_information['layout_map_FH']) && !empty($more_information['layout_map_FH']) ? 1 : 0;
		// $clsISO->print_pre($more_information); die();
		$hide_row_floor_special = (int) $core->get_field($more_information, "hide_row_floor_special", 0);
		$assign_list["more_information"] = $more_information;
		$assign_list["is_map_dq"] = $is_map_dq;
		$assign_list["is_hide_stock_cross"] = $is_hide_stock_cross;
		$assign_list["is_hide_stock_common"] = $is_hide_stock_common;
		$assign_list["hide_row_floor_special"] = $hide_row_floor_special;
		#tang thu cap
		$block_information = $clsProperty->getOneField('more_information', $block_id);
		$block_information = $clsISO->to_array_json($block_information);
		$vr_link = $core->get_field($block_information, "vr_link", "");
		$assign_list["vr_link"] = $vr_link;
		// $clsISO->print_pre($more_information); die();
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
		$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
		order by ms_date DESC limit 0,1");
		#
		$assign_list["onePolicy"] = $onePolicy;
		$assign_list["oneBuilding"] = $oneBuilding;
		#
		$floor = $core->get_field($more_information, "floor", "");
		$stock_templates = $core->get_field($more_information, "template", []);
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
		$arr_floors = !empty($floor) ? @explode(',', $floor) : array();
		$arr_stocks = $arr_stocks_specical= $arr_cols  = $arr_cols_specical = $arr_cells = $arr_status_id_show = array();
		$cond_stock = "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `project_id`='{$project_id}' and `building_id`='{$building_id}'";
		// $dbconn->debug = true;
		$list_stocks = $clsStock->getAll($cond_stock);
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$code = $val['code'];
				#tầng có căn đối xứng khác mã
				if(!empty($is_symbol)) {
					$more_stock = $clsISO->to_array_json($val['more_information']);
					$building_code = !empty($more_stock["building_code"]) ? $more_stock["building_code"] : "";						
					$code = $building_code.".".$code;
				}
				$floor = $val['floor'];
				$status_id  = $val['status_id'];
				$agency_id = $val['agency_id'];
				/*if(!empty($floor) && !in_array($floor, $arr_floors)){
					$arr_floors[] = $floor;
				}*/
				#thứ cấp
				$is_fund_type = 0;
				if($clsStock->checkStockFundType($val["stock_id"],$building_id,$val,$oneBuilding)) {
					$is_fund_type = 1;
				}
				$val['is_fund_type'] = $is_fund_type;
				$val['is_dq'] = ($val["status_id"] == _STOCK_STATUS_DQ_ID) ? 1 : 0;
				$arr_cells[$floor][$code] = $val;
				if($status_id != _STOCK_STATUS_SOLD_ID && !$clsISO->checkItemInArray($status_id,$arr_status_id_show) && !($agency_id > 0 && ($list_agency_cached_VIN[$agency_id] == 1 || ${"list_agency_cached_".$block_id}[$agency_id] == 1) && !$view_stock_hidden)){
					$arr_status_id_show[] = $val['status_id'];
				}
			}
			@usort($arr_stocks, function($a, $b){
				if((int) $a > (int) $b)
					return 1;
				return -1;
			});
			/*@usort($arr_floors,  function($a, $b){
				if((int) $a > (int) $b)
					return 1;
				return -1;
			});*/
		}
		
		
		$assign_list["arr_status_id_show"] = $arr_status_id_show;
		$group_cols_template = [];
		if(!empty($stock_templates)){
			foreach($stock_templates as $key => $val){
				$code = $val['code'];
				if(!empty($is_symbol)) {
					$code = $val["symbol"].".".$code;
					if(isset($group_cols_template[$val["symbol"]])) {
						$group_cols_template[$val["symbol"]] +=1;
					}else{
						$group_cols_template[$val["symbol"]] = 1;
					}
				}				
				$arr_stocks[] = $code;
				$arr_cols[$code] = $val;
				unset($code);
			}
		}
		if(!empty($stock_template_specical)){
			foreach($stock_template_specical as $key => $val){
				foreach ($val as $k => $v) {
					if(!empty($v['code'])) {
						$code = $v['code'];
						if(!empty($is_symbol)) {
							$code = $v["symbol"].".".$code;									
						}	
						$arr_stocks_specical[$key][] = $code;
						$arr_cols_specical[$key][$code] = $v;
						unset($code);	
					}				
				}				
			}
		}
		$arr_floor_merge_not_config = [];
		###
		$floor_service_arrs = $floor_merge_service_arrs = $floor_merge_arrs = $floor_merge_header_arrs = array();
		if(!empty($floor_config)){
			foreach($floor_config as $floor => $val){
				$cell_merge = $val['cell_merge'];
				$is_out_order = !empty($val['is_out_order']) ? $val['is_out_order'] : 0;
				$tmp= !empty($cell_merge) ? explode('|', $cell_merge) : array();
				$cell_merge_arrs = $arr_merge = $cell_col_index = $cols_merge = array();
				if(!empty($tmp)){
					$index_plus=0;
					for($i=0; $i<count($tmp); $i++){
						$a = @explode('-', $tmp[$i]);
						$arr_cols_merge = $arr_floor = [];
						$index_plus += $a[1];
						$child_0 = (int)$a[0];
						$child_1 = (int)$a[1];
						$child_2 = (int)$a[2];
						for($j=$child_0; $j<($child_0 + $child_1); $j++) {
							$arr_merge[] = $j;							
							$cell_col_index[$j] = $index_plus;
							$arr_floor[] = $j;
							$arr_cols_merge[] = $j; 
						}						
						$cell_merge_arrs[] = array(
							'start_cell' => $child_0,
							'arr_floor' => $arr_floor,
							'arr_cols_merge' => $arr_cols_merge,
							'collspan' => $child_1,
							'rowspan' => $child_2 ? ($child_2 + 1) : 1,
						);
					}
				}
				$config_floor[$floor] = $arr_merge;
				if((int) $val['is_special']==1){
					$key_template = "";
					foreach ($arr_floor_specical as $key => $arrFloor) {
						if($clsISO->checkItemInArray($floor,$arrFloor)) {
							$key_template = $key;
							foreach($arrFloor as $fl) {
								if($fl != $floor) {
									$arr_floor_merge_not_config[$fl] = $floor;	
								}									
							}
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
				}else if($val['floor_type'] == _FLOOR_TYPE_SERVICE_ID && !empty($val["name_floor"])) {
					$floor_merge_service_arrs[$floor] = array(
						'name_floor' => $val["name_floor"],
					);
				}
			}
		}
		if(!empty($floor_merge_arrs)){
			$number_house = $more_information['number_house'];
			foreach($floor_merge_arrs as $floor => $arr){
				$arr_merge_cols = $arr_merge_cols_unsort = $arr_merge_backups_cols = array();
				$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `project_id`='{$project_id}' 
				and `building_id`='{$building_id}' and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `floor`='{$floor}' order by `code` ASC");
				// $clsISO->print_pre($list_stocks); die();
				if(!empty($list_stocks)){
					$total_stocks = count($list_stocks);
					$total_stock_mis  = $number_house - $total_stocks;
					foreach($list_stocks as $key => $val){
						$code = $val['code'];
						$more_information_stock = $val['more_information'];
						$more_information_stock = $clsISO->to_array_json($more_information_stock);
						$arr_merge_cols_unsort[] = $code;
						$arr_merge_backups_cols[$code] = array(
							'code' => $code,
							'DT_TT' => $more_information_stock['DT_TT'],
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
		if(!empty($floor_merge_service_arrs)){
			$number_house = $more_information['number_house'];
			foreach($floor_merge_service_arrs as $floor => $arr){
				$arr_merge_cols = $arr_merge_cols_unsort = $arr_merge_backups_cols = array();
				$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `project_id`='{$project_id}' 
				and `building_id`='{$building_id}' and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `floor`='{$floor}' order by `code` ASC");
				if(!empty($list_stocks)){
					$total_stocks = count($list_stocks);
					$total_stock_mis  = $number_house - $total_stocks;
					foreach($list_stocks as $key => $val){
						$code = $val['code'];
						$more_information_stock = $val['more_information'];
						$more_information_stock = $clsISO->to_array_json($more_information_stock);
						$arr_merge_cols_unsort[] = $code;
						$val['is_dq'] = ($val["status_id"] == _STOCK_STATUS_DQ_ID) ? 1 : 0;
						$price = !empty($more_information_stock["total_price_vat"]) ? $more_information_stock["total_price_vat"] : 0;
						if(empty($price)) $price = $more_information_stock['total_price_early'];
						if(empty($price)) $price = $more_information_stock['total_price_progress'];
						if(strtolower($price) != 'check'){
							$price = $clsISO->processSmartNumber($price);
							$price =  number_format((float) $clsISO->priceFormat($price),3,'.','');
						}
						$arr_merge_backups_cols[$code] = array(
							'code' => $code,
							'stock_id' => $val["stock_id"],
							'ms_code' => $val["ms_code"],
							'agency_id' => $val["agency_id"],
							'status_id' => $val["status_id"],
							'show_website' => $val["show_website"],
							'total_price_vat' => $price,
							'is_dq' => $val["is_dq"],
							'DT_TT' => $more_information_stock['DT_TT'],
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
				$floor_merge_service_arrs[$floor]['arr_merge_cols'] = $arr_merge_cols;
			}
		}
		$total_stocks = !empty($arr_stocks) ? count($arr_stocks) : 0;
		$assign_list["arr_floor_merge_not_config"] = $arr_floor_merge_not_config;
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
		$assign_list["floor_merge_service_arrs"] = $floor_merge_service_arrs;
		$assign_list["floor_merge_header_arrs"] = $floor_merge_header_arrs;
		$assign_list["group_cols_template"] = $group_cols_template;
		$title_page = $clsProperty->getTitle($building_id);
		// Show map
		$is_map = 0;
		$tmp = $clsStockShape->getByCond("`holderG`='stock' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' and `building_id`='{$building_id}'", $clsStockShape->pkey);
		if(empty($tmp)){	
			$tmp = $clsStockShape->getByCond("`holderG`='stock' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `block_id`='{$block_id}' and JSON_EXTRACT(`map_configs`,\"$.list_building_slash\") like '%|{$building_id}|%' limit 0,1", $clsStockShape->pkey);
		}
		if(!empty($tmp)){
			$is_map = 1;
		}
		$assign_list["is_map"] = $is_map;
	} else {
		#
		$lst_config_column = !empty($more_information_project["config_column_lowfloor"]) ? $more_information_project["config_column_lowfloor"] : ARRAY_CONFIG_COLUMN_SEARCH_LOWFLOOR;
		$lstField = $clsStock->getTableField(_BLOCK_TYPE_LOWFLOOR_SALE);
		$arr_field = [];
		foreach ($lst_config_column as $key => $field) {
			$arr_field[$field] = $lstField[$field];
		}
		$assign_list["arr_field"] = $arr_field;
		#
		$block_id = (int) Input::get('block_id' , 0);
		$assign_list["block_id"] = $block_id;
		
		$is_project_block = 0; // Block là dự án hay không?
		if($block_id > 0){
			$regex = sprintf('%s_%s', $project_id, $block_id);
			$onePolicy = $clsPolicy->getByCond("`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `ms_date`<='".time()."' 
			and `scope_slash` like '%|{$regex}|%' order by `ms_date` DESC limit 0,1");
			$oneBlock = $clsProperty->getArraySearchByKey("_BLOCK", $block_id);
			$more_information = $oneBlock['more_information'];
			$vr_link = $core->get_field($more_information, "vr_link", "");
			$vr_source = $core->get_field($more_information, "vr_source", "");
			if(isset($more_information['is_project']) && (int) $more_information['is_project'] == 1) {
				$is_project_block = 1;
				$get_blocks_ids = [$block_id];
			}
			$assign_list["get_blocks_ids"] = $get_blocks_ids;
			$assign_list["oneBlock"] = $oneBlock;
			$title_page = $clsProperty->getTitle($block_id, $oneBlock);
		} else {
			$title_page = $oneProject['title'];
			$vr_link = $core->get_field($more_information_project, "vr_link", "");
			$vr_source = $core->get_field($more_information_project, "vr_source", "");
			$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' 
			and `scope_slash` like '%|{$project_id}_%|%' order by ms_date DESC limit 0,1");
		}
		$assign_list["onePolicy"] = $onePolicy;
		// $clsISO->print_pre($block_id); die();
		$field = "{$clsProperty->pkey},`title`";
		$list_blocks = array();
		if($clsCache->has('_stock_block_'.$project_id.'_cached')){
			$list_blocks = $clsCache->get('_stock_block_'.$project_id.'_cached');
			// $clsISO->print_pre($list_blocks); die();
		} else {
			$field = "{$clsProperty->pkey},`title`,`more_information`";
			$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and `for_id`='{$project_id}' 
			and `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` ASC", $field);
			if(!empty($list_blocks)){
				foreach($list_blocks as $key => $val){
					$block_information = $val['more_information'];
					$block_information = $clsISO->to_array_json($block_information);
					$list_blocks[$key]['more_information'] = $block_information;
				}
				$clsCache->put('_stock_block_'.$project_id.'_cached', $list_blocks);
			} else {
				if($clsCache->has('_stock_block_'.$project_id.'_cached')){
					$clsCache->delete('_stock_block_'.$project_id.'_cached');
				}
			}
		}
		$assign_list["is_project_block"] = $is_project_block;
		###
		$assign_list["list_blocks"] = $list_blocks;
		$assign_list["vr_link"] = $vr_link;	
		$assign_list["vr_source"] = $vr_source;	
	}
	$assign_list["project_id"] = $project_id;
	$assign_list["block_type"] = $block_type;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng hàng '.$title_page;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function project_load_stock_old(){
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
	$is_project_block  = (int) Input::post('is_project_block', 0);
	if(isset($is_project_block) && $is_project_block == 1) {
		$block_id = (int) Input::post('block_id',0);
		$get_blocks_ids = [$block_id];
	}
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
					$list_ranges = $core->get_field($more_information, "list_ranges", []);
					if(!empty($list_ranges)){
						foreach($list_ranges as $range_id){
							if(!in_array($range_id, $list_range_ids)){
								$list_range_ids[] = $range_id;
							}
						}
						unset($list_ranges);
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
			$TCBG = $core->get_field($more_information, "TCBG", ""); 
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
	$total_record = $clsStock->countItem($cond." and `agency_id`<>'"._AGENCY_FH_ID."' and `agency_id`<>'"._AGENCY_NSL_ID."'");
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
	$is_project_block  = (int) Input::post('is_project_block', 0);
	if(isset($is_project_block) && $is_project_block == 1) {
		$block_id = (int) Input::post('block_id',0);
		$get_blocks_ids = [$block_id];
	}
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
					$list_ranges = $core->get_field($more_information, "list_ranges", []);
					if(!empty($list_ranges)){
						foreach($list_ranges as $range_id){
							if(!in_array($range_id, $list_range_ids)){
								$list_range_ids[] = $range_id;
							}
						}
						unset($list_ranges);
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
			$TCBG = $core->get_field($more_information, "TCBG", ""); 
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
	$total_record = $clsStock->countItem($cond." and `agency_id`<>'"._AGENCY_FH_ID."' and `agency_id`<>'"._AGENCY_NSL_ID."'");
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond  = " limit {$offset},{$per_page}";
	####
	$field = "*,IF(`total_price_early`>0,`total_price_early`,total_price_vat) AS `order_no`";
	$list_stocks = array(); $total_fh_stocks = 0;
	$arr_property_cached = $arr_props_cached = $arr_policy_cached = array();
//	 $dbconn->debug = true;
	
	$list_stocks_notin_fh = $clsStock->getAll($cond." and (`agency_id`<>'"._AGENCY_FH_ID."' 
		and `agency_id`<>'"._AGENCY_NSL_ID."') order by `order_no` ASC {$limitCond}", $field);
	// var_dump($list_stocks_notin_fh); die();
//	$clsISO->print_pre($list_stocks_notin_fh);die;
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
	
	#
	$oneProject = $clsProject->getOne($project_id,"more_information");
	$more_information_project = $clsISO->to_array_json($oneProject["more_information"]);
	$lst_config_column = !empty($more_information_project["config_column_lowfloor"]) ? $more_information_project["config_column_lowfloor"] : ARRAY_CONFIG_COLUMN_SEARCH_LOWFLOOR;
	$smarty->assign('lst_config_column', $lst_config_column);
	
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
			$stock_hold_id = isset($more_information['stock_hold_id']) 
				? (int) $more_information['stock_hold_id'] : 0;
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
			if($stock_hold_id > 0 && !isset($arr_property_cached[$stock_hold_id])){
				$arr_property_cached[$stock_hold_id] = $clsProperty->getTitle($stock_hold_id);
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
	$html = $core->build('project'.DS.'_ajax.stock_new.tpl');
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
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id,$deviceType;
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
	$price_sheets = $core->get_field($more_information, "price_sheets", []);
	$list_attributes = $core->get_field($more_information, "properties", []);
	$hide_price_sheets = (int) $core->get_field($more_information, "hide_price_sheets", 0);
	if($type_id==0 && (int) $core->get_field($more_information, "type_id", 0) > 0){
		$type_id = (int) $more_information['type_id'];
	}
	#- Start Log
	if(!in_array($profile_id, _PROFILE_NOT_LOG)){}
	$clsLog->insertAction('view_stock', sprintf('Xem thông tin căn hộ <strong>%s</strong>', $ms_code), $stock_id, $block_id);
	#
	$oneTemplate = $oneBlock = $oBuilding = $oneAgency = $oneStatus = $oneType = $arr_property_ids = array();
	if($block_id > 0) $arr_property_ids[] = $block_id;
	if($building_id > 0) $arr_property_ids[] = $building_id;
	if($agency_id > 0) $arr_property_ids[] = $agency_id;
	if($status_id > 0) $arr_property_ids[] = $status_id;
	if($type_id > 0) $arr_property_ids[] = $type_id;
	$field = "{$clsProperty->pkey},`title`,`property_code`,`more_information`,`textcolor`,`bgcolor`";
	$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(",", $arr_property_ids).")", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			if($building_id > 0 && $val[$clsProperty->pkey] == $building_id)
				$oBuilding = $val;
			else if($block_id > 0 && $val[$clsProperty->pkey] == $block_id)
				$oneBlock = $val;
			else if($agency_id > 0 && $val[$clsProperty->pkey] == $agency_id)
				$oneAgency = $val;
			else if($type_id > 0 && $val[$clsProperty->pkey] == $type_id)
				$oneType = $val;
			else if($status_id > 0 && $val[$clsProperty->pkey] == $status_id)
				$oneStatus = $val;
		}
		unset($tmp);
	}
	$oneProject = $clsProject->getOne($project_id, "`code`,`title`");
	#- Template
	$building_information = $oBuilding['more_information'];
	$building_information = $clsISO->to_array_json($building_information);	
	
	$floor_specical = !empty($building_information['floor_specical']) ? $building_information['floor_specical'] : array();
	if(!empty($floor_specical)) {
		$key=array_search($oneStock["floor"],$floor_specical);
		$template_specical = !empty($building_information['template_specical'][$key]) ? $building_information['template_specical'][$key] : array();
		if(!empty($template_specical)) {
			foreach($template_specical as $key => $val){
				if($oneStock['code'] == $val['code']){
					$oneTemplate = $val;
					break;
				}
			}
		}
	}
	if(empty($oneTemplate)) {
		$list_templates = $core->get_field($building_information, "template", []);
		if(!empty($list_templates)){
			foreach($list_templates as $key => $val){
				if($oneStock['code'] == $val['code']){
					$oneTemplate = $val;
					break;
				}
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
	#khoa can
	$html_lock_stock = ""; $check_role_lock = 0;
	/*if($profile_id == 289) {
		$clsStockLock = new StockLock();
		$stock_lock = $clsStockLock->getByCond("`stock_id`='{$stock_id}' AND (".time()." BETWEEN `start_date` AND `end_date`) ");
		if(!empty($stock_lock)) {
			$html_lock_stock.= '<button onclick="$Core.helper.stock_lock.unlock_stock(this,event)" stock_lock_id="'.$stock_lock[$clsStockLock->pkey].'" stock_id="'.$stock_id.'" class="btn btn-sm btn-danger" type="button"><i class="bx bx-vector"></i> <span>Mở khóa</span></button>';	
		}else{
			$html_lock_stock.= '<button onclick="$Core.helper.stock_lock.open_stock_lock(this,event)" stock_id="'.$stock_id.'" class="btn btn-sm btn-warning" type="button"><i class="bx bx-vector"></i> <span>Khóa</span></button>';	
		}
		if($clsISO->checkPermissionGroup("ADMIN_PROJECT") || $profile_id == 289) {
			$check_role_lock = 1;
		}
	}*/
	#
	$img_price_sheets = $html_price_more = $html_request_ptg = "";
	if(!empty($price_sheets && $hide_price_sheets==0)){
		$price_sheets = @array_reverse($price_sheets);
		$oneSheet = @reset($price_sheets);
		$img_price_sheets.= '<fieldset class="radius-4 ixZrPaFqgl my-2 bg-main">
			<legend class="fs-12 mb-0 px-1 bg-main text-white">Phiếu tính giá'.(!empty($csbh) ? sprintf(' %s', $csbh) : '').' <a href="javascript:;" data-link="'.$clsStock->getLink($ms_code, 'PTG').'" onClick="$Core.util.copyToClipboard(this, event)" class="btn btn-xs bg-white text-main btn-icon btn-outline-default"><i class=\'bx bx-copy fs-12\'></i></a></legend>
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
	$html_zalo = $clsProject->getHtmlContactZaloProject($block_id,$oneBlock);
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
				<legend class="fs-12 mb-0 px-1 bg-main text-white">Phiếu tính giá'.(!empty($csbh) ? sprintf(' %s', $csbh) : '').'</legend>
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
		$html_price_sheets.= '<li><i class="material-icons-outlined" style="color:#696cff">image</i> POSTER: ';
		foreach($stock_posters as $okey => $oval){
			$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-default mr-1" data-fancybox data-preload="true" href="'.$clsISO->getGoogleUrl($oval['image']).'">'.$oval['title'].'</a>';
		}
		$html_price_sheets.= '</li>';
	}else if(!empty($oneTemplate["layout"])) {
		$html_price_sheets.= '<li><i class="material-icons-outlined" style="color:#696cff">image</i> POSTER: ';
		$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-default mr-1" data-fancybox data-preload="true" href="'.$clsISO->getGoogleUrl($oneTemplate["layout"]).'">Trục căn '.$oneStock['code'].'</a>';
		$html_price_sheets.= '</li>';
	}
	$stock_poster_video = $core->get_field($more_information, "stock_poster_video", []);
	if(!empty($stock_poster_video)){
		$html_price_sheets.= '<li><i class="material-icons-outlined" style="color:#696cff">video_camera_back</i> VIDEO: ';
		foreach($stock_poster_video as $okey => $oval){			
			$gg_id = $clsISO->getGoogleId($oval['video']);
			$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-primary mr-1" target="_blank" href="'.$oval['video'].'">'.$oval['title'].'</a>';
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
		/* $bedroom_information = $clsProperty->getOneField('more_information', $bedroom_id);
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
		}*/
		/** End nội thất mẫu*/
	}
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
		$regex = sprintf('%s_%s', $project_id, $block_id);
	} else {
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
	}
	$condPolicy = "`block_type`='{$stock_type}' and `ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' ";
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
	if($agency_id > 0 
		&& ($clsISO->checkPermission('view_stock_resource') || $clsISO->check_view_resource($stock_type, $block_id, $oneBlock))){
		$more_information_ag = $oneAgency['more_information'];
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
		$html_price_sheets.= sprintf('<li><i class="material-icons-outlined">home_work</i> %s %s %s</li>', $oneAgency['title'], $html_button_price_sheets, $html_button_group_zalo);
	}
	$html_last_updated = "";
	if($oneStock['upd_date'] > 0){
		$html_last_updated .= "<tr>
			<th>Cập nhật lần cuối</th>
			<td class=\"text-primary fs-12\">".$clsISO->convertTimeToText($oneStock['upd_date'], true)."</td>
		</tr>";
	}
	#- Status
	if($status_id > 0){
		$status_name= $clsProperty->getTitle($status_id, $oneStatus);
	} else{
		$status_name = "Đã bán";
		$oneStatus= $clsProperty->getOne(_STOCK_STATUS_SOLD_ID,"bgcolor,textcolor");
	}
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
	if(!empty($DT_TT) && $clsISO->convertToNumber($DT_TT) > 0) {
		if(!empty($total_price_early)){
			$price_m2 = $clsISO->processSmartNumber($total_price_early) / $clsISO->convertToNumber($DT_TT);
		} else if(!empty($total_price_vat)){
			$price_m2 = $clsISO->processSmartNumber($total_price_vat) / $clsISO->convertToNumber($DT_TT);
		}
	}	
	$price_m2 = !empty($price_m2) ? $clsISO->shortNumber($price_m2) : "--";
	#- Permiss
	$permiss_upload_poster = $clsISO->checkPermission('upload_poster') ? 1 : 0;
	$permiss_hide_stock_globe = $clsISO->checkPermission('hide_stock_globe') ? 1 : 0;
	$permiss_edit_stock_advanced = $clsISO->checkPermission('edit_stock_advanced') ? 1 : 0;
	$permis_edit_stock_mechanism = $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR') ? 1 : 0;
	#poster
	$html_more_buttons = '<div class="btn-group">
		<button type="button" class="btn btn-sm btn-outline-primary btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
		<ul class="dropdown-menu no-hidden dropdown-menu-end">
			'.($permiss_edit_stock_advanced?'
			<li><a class="dropdown-item" data-toggle="ripple" stock_id="'.$stock_id.'" tp="sheet_price" onClick="$Core.helper.open_quick_stock(this,event)" class="btn btn-sm btn-outline-default " type="button">'.$core->makeIcon('plus', 'PTG').'</a></li>
			<li><a class="dropdown-item" data-toggle="ripple" stock_id="'.$stock_id.'" tp="update_status" class="btn btn-sm btn-outline-primary" 	onClick="$Core.helper.open_quick_stock(this,event)" type="button">'.$core->makeIcon('check', 'Tình trạng').'</a></li>':'').'
			'.($permiss_upload_poster ? '
			<hr class="dropdown-divider" />
			<li><a onClick="$Core.helper.open_quick_stock(this,event)" stock_id="'.$stock_id.'" tp="poster" class="dropdown-item" href="javascript:void(0);">'.$core->makeIcon('plus', 'Thêm postter').' </a></li>
			<li><a onClick="$Core.helper.open_quick_stock(this,event)" stock_id="'.$stock_id.'" tp="video" class="dropdown-item" href="javascript:void(0);">'.$core->makeIcon('plus', 'Thêm Video').'</a></li>' : '' ).'
		</ul>
	</div>';
	#so sánh 
	$html_compare = '<button class="btn-add-can btn btn-sm bg-main text-white" title="Thêm so sánh" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="pop" action="add" stock_id="'.$stock_id.'" ><i class="bx bx-git-compare"></i> So sánh</button>';
	 
	#- HTML Logs
	$html_history_log = "";
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkDEV()) {
		$html_history_price_log = '<a class="btn btn-sm btn-outline-default bg-white px-2" title="Lịch sử thay đổi giá" href="javascript:void(0);" onClick="$Core.helper.history_price_log(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-history fs-14','').'</a>';
	}
	$html = '<div class="rounded-3 mb-2 overflow-hidden">
		<div class="bg-'.($agency_id==_AGENCY_CNCN_ID?'purple':'main').' p-3 w-full w-100 text-center">
			<h3 class="text-white mb-0 fs-18 fw-bold">
			'.($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE ? sprintf('%s, %s', $oneBlock['title'], $oneProject['code']) : $oneBlock['property_code']).' - <a class="text-white" href="'.$clsStock->getLink($oneStock['ms_code']).'" target="_blank">'.$oneStock['ms_code'].' <i class="bx bx-link-external"></i></a> 
			<a class="text-white ml-1" href="/tim-kiem/'.$regex_search.'.html"><i class="bx bx-search"></i></a> 
			'.(!empty($vr_link)?'<a class="ml-1" onClick="$Core.helper.close_webui_popver(this, event)" title="Xem VR360" href="'.$vr_link.'" data-fancybox data-type="iframe" data-caption="'.$vr_source.'"><img src="'.URL_IMAGES.'/vr-w-360.png" class="w-px-30"></a>':'').'<button class="btn btn-icon text-white" onclick="$Core.global.share_stock(this,event)" stock_id="'.$stock_id.'" >
				<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
				<path d="M5.5 15.5c1.07 0 2.02-.5 2.67-1.26l6.87 3.87c-.01.13-.04.26-.04.39 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5c-1.07 0-2.02.5-2.67 1.26l-6.87-3.87c.01-.13.04-.26.04-.39s-.02-.26-.04-.39l6.87-3.87C16.47 8.5 17.42 9 18.5 9 20.43 9 22 7.43 22 5.5S20.43 2 18.5 2 15 3.57 15 5.5c0 .13.02.26.04.39L8.17 9.76A3.48 3.48 0 0 0 5.5 8.5C3.57 8.5 2 10.07 2 12s1.57 3.5 3.5 3.5m13 1.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5m0-13c.83 0 1.5.67 1.5 1.5S19.33 7 18.5 7 17 6.33 17 5.5 17.67 4 18.5 4m-13 6.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5S4 12.83 4 12s.67-1.5 1.5-1.5"></path>
				</svg>
			</button></h3>
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
				'.($clsISO->checkDEV()?'<a class="btn btn-sm btn-outline-default bg-white px-2" title="Tính hiệu suất đầu tư" href="javascript:void(0);" onClick="$Core.performance.open(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-line-chart fs-14','').'</a>':'').(($clsISO->checkSale()) ? $html_request_ptg : "").(!empty($check_role_lock) ? $html_lock_stock : "").$html_compare.'
			</div>
			'.$html_price_more.$img_price_sheets.'
			'.(($permiss_edit_stock_advanced || $permiss_hide_stock_globe || $permiss_upload_poster) ? '
			<div class="d-flex flex-wrap gap-1 fs-12 mt-2 justify-content-center">
				'.((!$clsISO->checkSale()) ? $html_request_ptg : "").'
				'.$html_more_buttons.'
			</div>':((!$clsISO->checkSale()) ? $html_request_ptg : "")).'
		</div>'.$html_zalo.'
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
						<span class="label bg-info">'.$price_m2.'</span>
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
	$field = "{$clsStock->pkey},floor,code,block_id,building_id,project_id,more_information";
	$oneStock = $clsStock->getOne($stock_id, $field);
	###
	$project_id = $oneStock['project_id'];
	$block_id = $oneStock['block_id'];
	$building_id = $oneStock['building_id'];
	$more_info_stock = $clsISO->to_array_json($oneStock['more_information']);
	$code = $oneStock['code'];
	$building_code = !empty($more_info_stock["building_code"]) ? $more_info_stock["building_code"] : "";
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
	if(!$clsISO->checkPermission('view_stock_globe')){
		$cond.= " and `show_website` like '%|user.fh|%'";
	}
	$list_stocks = $clsStock->getAll("{$cond} and `project_id`='{$project_id}' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}' and `code`='{$code}' 
		and `agency_id`>0 and `status_id`>0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
		and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' order by `floor` ASC", $field);
	// $clsISO->print_pre($cond); die();
	if(!empty($list_stocks)){ $ii = 1;
		$html.= '<div class="table-container no-shadow text-nowrap overflow-auto mb-1" style="max-height:300px">
			<table class="table table_'.$building_id.' mb-0" cellspacing="0" cellpadding="0">
				<thead class="position-sticky top-0 zindex-3"><tr>
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
				if(!(!empty($building_code) && $building_code == $more_information["building_code"])) {
					continue;
				}				
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
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page
	,$clsConfiguration,$clsISO,$profile_id,$oneProfile,$list_projects;
	$clsCache = new Cache();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	#
	$arr_project_highfloor = [_PROJECT_DEF_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID,_PROJECT_MRD_ID,_PROJECT_CSD_ID];
	$arr_project_lowfloor = [_PROJECT_VHOP3_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID,_PROJECT_VWC_ID,_PROJECT_DEF_ID];
    $orderSubdivision = ['MTS','LPH', 'MLS', 'LSB']; // Thứ tự sắp xếp các phân khu
    $arr_block =  $arr_hot = $list_all_blocks = $sorted = [];
	
	$lstArea = $clsSetting->getArraySearchByKey("_AREA");
	$list_all_blocks_area = $arr_project_area = $arr_menu_blocks_area = $arr_hot_area = $arr_block_area = [];
	$p_field = "{$clsProperty->pkey},`title`,`image`,`for_id`,`more_information`";
	$arr_menu_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND JSON_EXTRACT(`more_information`, \"$.is_menu\")='1' AND JSON_EXTRACT(`more_information`, \"$.is_project\")='1' order by `order_no` ASC", $p_field);
	$arr_menu_blocks_lowfloor = [];
	if(!empty($arr_menu_blocks)) {
		foreach ($arr_menu_blocks as $key => $val) {
			$arr_menu_blocks_lowfloor[$val["for_id"]][] = $val;
		}
	}
	if(!empty($list_projects)) {
		foreach ($list_projects as $key => $val) {
			$project_id = $val[$clsProject->pkey];
			$more_information = $val['more_information'];
			$val['bgcolor'] = $core->get_field($more_information, "bgcolor", '#FFF');
			$val['textcolor'] = $core->get_field($more_information, "textcolor", '#696cff');
			$list_blocks = $val['list_blocks'];
			unset($val["more_information"]);
//				 $clsISO->print_pre($list_blocks); die();
			if(!empty($list_blocks)){
				foreach($list_blocks as $okey => $oval){
					if(!empty($oval["list_menu_buildings"])) {
						$block_id = $oval[$clsProperty->pkey];
						$block_information = $oval['more_information'];
						$list_blocks[$okey]['project_id'] = $val['project_id'];
						$list_blocks[$okey]['project_name'] = $val['title'];
						$list_blocks[$okey]['project_info'] = [
							"project_id"	=>	$val["project_id"],
							"logo"	=>	$val["logo"],
							"title"	=>	$val["title"],
						];
						$list_blocks[$okey]['is_hot'] = $core->get_field($block_information, "is_hot", 0);
						unset($list_blocks[$okey]['list_builings']);
						$list_all_blocks_area[$val["area_id"]][$block_id] = $list_blocks[$okey];
						if((int) $core->get_field($block_information, "is_hot", 0) == 1) {
							$arr_block_area[$val["area_id"]][] = $block_id;	
							$arr_hot_area[$val["area_id"]][$block_id] = $list_blocks[$okey];	
						}
					}else{
						unset($list_blocks[$okey]);
					}
				}
			}
			if($clsProject->isLowFloor($val['project_id'], $val['list_block_type'])) {
				if(!empty($arr_menu_blocks_lowfloor[$val['project_id']])) {
					if(!isset($arr_menu_blocks_area[$val["area_id"]])) {
						$arr_menu_blocks_area[$val["area_id"]] = $arr_menu_blocks_lowfloor[$val['project_id']];
					}else{
						$arr_menu_blocks_area[$val["area_id"]] = array_merge($arr_menu_blocks_area[$val["area_id"]],$arr_menu_blocks_lowfloor[$val['project_id']]);
					}
				}
				$arr_project_area[$val["area_id"]][] = $val;
			}
		}
	}
	$more_profile = $oneProfile["more_information"];
	$arr_block_area = $more_profile["order_block_area"];
	foreach ($lstArea as $key => $val) {
		$sorted = $sorted_hot = [];
		$list_all_blocks = $list_all_blocks_area[$val[$clsSetting->pkey]];	
		$arr_projects = $arr_project_area[$val[$clsSetting->pkey]];	
		$arr_menu_blocks = $arr_menu_blocks_area[$val[$clsSetting->pkey]];	
		if(!empty($arr_block_area[$val[$clsSetting->pkey]])) {
			$arr_hot = !empty($arr_hot_area[$val[$clsSetting->pkey]]) ? $arr_hot_area[$val[$clsSetting->pkey]] : [];	
			foreach ($arr_block_area[$val[$clsSetting->pkey]] as $blockId) {
				if (array_key_exists($blockId, $list_all_blocks)) {				
					if(array_key_exists($blockId, $arr_hot)) {
						$sorted_hot[$blockId] = $list_all_blocks[$blockId];
					}else{
						$sorted[$blockId] = $list_all_blocks[$blockId];
					}
				}
			}
			$remaining = array_diff_key($list_all_blocks, $sorted);//danh sách chưa sắp xếp chứa cả hot
			$remaining = array_diff_key($remaining, $arr_hot);//danh sách chưa sắp xếp không chứa hot
			$sorted = array_merge($sorted,$remaining);
			$hot_not_order = array_diff_key($arr_hot, $sorted_hot); // danh sách hot chưa lưu
			$sorted_hot = array_merge($sorted_hot,$hot_not_order); // gộp danh sách hot
			$sorted = array_merge(array_values($sorted_hot),$sorted);
		}else{
			$sorted = $list_all_blocks;
			$arr_order = @array_column($sorted, 'is_hot');
			@array_multisort($arr_order, SORT_DESC, $sorted);
		}
		$lstArea[$key]["list_blocks"] = $sorted;
		$lstArea[$key]["arr_projects"] = $arr_projects;
		#
//			$clsISO->print_pre($arr_menu_blocks);die;
		if(!empty($arr_menu_blocks)){
			foreach($arr_menu_blocks as $k_mb => $v_mb){
				$more_information = $v_mb['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$arr_menu_blocks[$k_mb]['more_information'] = $more_information;
				$arr_menu_blocks[$k_mb]['link'] = $clsProperty->getLink('stock', array(
					'project_id' => $v_mb['for_id'],
					'block_id' => $v_mb[$clsProperty->pkey]
				));
			}
			$lstArea[$key]["arr_menu_blocks"] = $arr_menu_blocks;
		}
	}
	$assign_list["lstArea"] = $lstArea;
	# Tab Ma trận: gom lại theo dự án + CĐT từ chính dữ liệu đã dựng ở trên
	$assign_list["matrix"] = _stock_matrix_build($lstArea, $clsStock, $clsProperty, $clsProject, $core, $clsISO);
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng hàng dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
/**
 * Dựng dữ liệu cho tab Ma trận từ $lstArea đã build sẵn (không query lại block/building).
 * Gom cao tầng theo DỰ ÁN (mỗi dòng 1 dự án), phân khu thành nhóm con, tòa là nút bấm.
 * CĐT: investor_id trong more_information của phân khu → fallback cấp dự án; tên lấy cache _INVESTOR.
 * Trạng thái dự án: is_lock=1 (trong more_information) = hết hàng, ngược lại đang mở bán.
 * Số căn "còn": đếm 1 lần bằng GROUP BY (status_id<>SOLD, agency_id>0).
 * @return array investors / high / low / totals
 */
function _stock_matrix_build($lstArea, $clsStock, $clsProperty, $clsProject, $core, $clsISO){
	$list_investor = $clsProperty->getArraySearchByKey("_INVESTOR");
	if(!is_array($list_investor)){ $list_investor = array(); }
	# Đếm căn còn theo dự án — 1 query/loại, dùng chung mọi vùng
	$count_high = _stock_matrix_count_units($clsStock, _BLOCK_TYPE_HIGHLEVEL_SALE);
	$count_low  = _stock_matrix_count_units($clsStock, _BLOCK_TYPE_LOWFLOOR_SALE);
	$ctx = array("investor" => $list_investor, "high" => $count_high, "low" => $count_low, "core" => $core, "iso" => $clsISO);

	# Tab đầu tiên: Toàn quốc (gộp mọi vùng); sau đó mỗi khu vực 1 tab
	$regions = array();
	$all = _stock_matrix_region($lstArea, $ctx);
	$all["id"] = 0;
	$all["title"] = "Toàn quốc";
	$regions[] = $all;
	foreach($lstArea as $area){
		$has_data = !empty($area["list_blocks"]) || !empty($area["arr_projects"]) || !empty($area["arr_menu_blocks"]);
		if(!$has_data){ continue; }
		$one = _stock_matrix_region(array($area), $ctx);
		$one["id"] = isset($area["setting_id"]) ? (int) $area["setting_id"] : 0;
		$one["title"] = isset($area["title"]) ? $area["title"] : "Khu vực";
		$regions[] = $one;
	}
	return array("regions" => $regions);
}
/**
 * Dựng 1 vùng từ area entries — GOM THEO DỰ ÁN (mỗi dòng 1 dự án, như thiết kế gốc).
 * Dữ liệu vẫn lấy từ list_blocks / arr_projects của Tổng quan.
 * @return array investors / high / low / totals (chưa có id/title — nơi gọi gán)
 */
function _stock_matrix_region($areas, $ctx){
	$list_investor = $ctx["investor"]; $count_high = $ctx["high"]; $count_low = $ctx["low"];
	$core = $ctx["core"]; $clsISO = $ctx["iso"];

	# ---- Cao tầng: gom phân khu theo dự án, mỗi dự án 1 dòng ----
	$high_projects = array();
	$tower_total = 0;
	$sum_high_units = 0;
	foreach($areas as $area){
		if(empty($area["list_blocks"])){ continue; }
		foreach($area["list_blocks"] as $block){
			# Overview chỉ cần list_menu_buildings; giữ y hệt để không lệch danh sách
			if(empty($block["list_menu_buildings"])){ continue; }
			$pinfo = !empty($block["project_info"]) ? $block["project_info"] : array();
			$project_id = (int) (isset($pinfo["project_id"]) ? $pinfo["project_id"] : 0);
			# Khóa gom: dự án hợp lệ → gom theo dự án; không có project_id → mỗi phân khu 1 dòng
			# (tránh gộp nhầm nhiều phân khu mồ côi vào chung 1 dòng như trước).
			$gkey = $project_id > 0 ? 'p'.$project_id : 'b'.$block["property_id"];
			$block_more = is_array($block["more_information"]) ? $block["more_information"]
				: $clsISO->to_array_json($block["more_information"]);
			$investor_id = (int) $core->get_field($block_more, "investor_id", 0);
			if(!isset($high_projects[$gkey])){
				$proj_more = isset($pinfo["more_information"]) ? $pinfo["more_information"] : array();
				if(!is_array($proj_more)){ $proj_more = $clsISO->to_array_json($proj_more); }
				$proj_investor = (int) $core->get_field($proj_more, "investor_id", 0);
				$is_lock = (int) $core->get_field($proj_more, "is_lock", 0);
				$units = ($project_id > 0 && isset($count_high[$project_id])) ? (int) $count_high[$project_id] : 0;
				if($project_id > 0){ $sum_high_units += $units; }
				$high_projects[$gkey] = array(
					"project_id"     => $project_id,
					"title"          => isset($pinfo["title"]) ? $pinfo["title"] : $block["title"],
					"logo"           => isset($pinfo["logo"]) ? $pinfo["logo"] : "",
					"investor_id"    => $investor_id > 0 ? $investor_id : $proj_investor,
					"is_lock"        => $is_lock,
					"unit_count"     => $units,
					"unit_count_fmt" => number_format($units, 0, ',', '.'),
					"subs"           => array()
				);
			}
			if($high_projects[$gkey]["investor_id"] <= 0 && $investor_id > 0){
				$high_projects[$gkey]["investor_id"] = $investor_id;
			}
			$towers = array();
			foreach($block["list_menu_buildings"] as $bld){
				$towers[] = array(
					"title"  => $bld["title"],
					"link"   => isset($bld["link"]) ? $bld["link"] : "",
					"is_hot" => (int) $core->get_field($block_more, "is_hot", 0)
				);
				$tower_total++;
			}
			$high_projects[$gkey]["subs"][] = array(
				"block_title" => $block["title"],
				"bgcolor"     => $core->get_field($block_more, "bgcolor", "#696cff"),
				"textcolor"   => $core->get_field($block_more, "textcolor", "#fff"),
				"towers"      => $towers
			);
		}
	}
	$investor_summary = array();
	foreach($high_projects as $pid => $row){
		$inv_id = (int) $row["investor_id"];
		$high_projects[$pid]["investor_name"] = ($inv_id > 0 && isset($list_investor[$inv_id]))
			? $list_investor[$inv_id]["title"] : "Chưa gán CĐT";
		if(!isset($investor_summary[$inv_id])){ $investor_summary[$inv_id] = 0; }
		$investor_summary[$inv_id]++;
	}
	$high_projects = array_values($high_projects);

	# ---- Thấp tầng: arr_projects + arr_menu_blocks (như Tổng quan), khử trùng lặp ----
	$low_projects = array(); $low_blocks = array(); $low_pids = array(); $seen_lp = array(); $seen_lb = array();
	foreach($areas as $area){
		if(!empty($area["arr_projects"])){
			foreach($area["arr_projects"] as $proj){
				$pid = (int) $proj["project_id"];
				if(isset($seen_lp[$pid])){ continue; }   // cùng dự án ở nhiều vùng → chỉ 1 lần
				$seen_lp[$pid] = 1;
				$lu = isset($count_low[$pid]) ? (int) $count_low[$pid] : 0;
				$proj["unit_count"] = $lu;
				$proj["unit_count_fmt"] = number_format($lu, 0, ',', '.');
				$low_projects[] = $proj;
				$low_pids[$pid] = 1;
			}
		}
		if(!empty($area["arr_menu_blocks"])){
			foreach($area["arr_menu_blocks"] as $blk){
				$bid = (int) (isset($blk["property_id"]) ? $blk["property_id"] : 0);
				if($bid > 0 && isset($seen_lb[$bid])){ continue; }
				if($bid > 0){ $seen_lb[$bid] = 1; }
				$low_blocks[] = $blk;
			}
		}
	}
	$sum_low_units = 0;
	foreach(array_keys($low_pids) as $pid){ $sum_low_units += isset($count_low[$pid]) ? (int) $count_low[$pid] : 0; }

	# ---- Panel CĐT (đếm theo số dự án) ----
	$investors = array(array("id" => 0, "title" => "Tất cả CĐT", "count" => count($high_projects)));
	foreach($investor_summary as $inv_id => $cnt){
		if($inv_id <= 0){ continue; }
		$investors[] = array(
			"id"    => $inv_id,
			"title" => isset($list_investor[$inv_id]) ? $list_investor[$inv_id]["title"] : "CĐT #".$inv_id,
			"count" => $cnt
		);
	}

	return array(
		"investors"    => $investors,
		"high"         => $high_projects,
		"low_projects" => $low_projects,
		"low_blocks"   => $low_blocks,
		"totals"       => array(
			"high_projects"  => count($high_projects),
			"high_towers"    => $tower_total,
			"high_units"     => $sum_high_units,
			"high_units_fmt" => number_format($sum_high_units, 0, ',', '.'),
			"low_projects"   => count($low_projects) + count($low_blocks),
			"low_units"      => $sum_low_units,
			"low_units_fmt"  => number_format($sum_low_units, 0, ',', '.')
		)
	);
}
/**
 * Đếm số căn "còn" theo dự án cho 1 loại kho (cao/thấp tầng) bằng 1 truy vấn GROUP BY.
 * "Còn" = status_id > 0 và khác đã bán và có sàn (agency_id > 0) — theo đúng điều kiện lọc list.
 * @return array project_id => số căn
 */
function _stock_matrix_count_units($clsStock, $stock_type){
	global $dbconn;
	$stock_type = (int) $stock_type;
	$sold = (int) _STOCK_STATUS_SOLD_ID;
	$rows = $dbconn->GetAll("SELECT `project_id`, COUNT(*) AS `c` FROM `{$clsStock->tbl}`
		WHERE `stock_type`='{$stock_type}' AND `status_id`>0 AND `status_id`<>'{$sold}' AND `agency_id`>0
		GROUP BY `project_id`");
	$out = array();
	if(is_array($rows)){
		foreach($rows as $r){ $out[(int) $r["project_id"]] = (int) $r["c"]; }
	}
	return $out;
}
/**
 * Tiến độ dự án (cat_id=3): build từ bảng project_progress_items, gom nhóm theo THÁNG.
 * progress_date dạng "T{m}/{y}" (Tháng/Năm) hoặc "d/m/Y". Media từ cột JSON `media`
 * (ảnh Drive / video YouTube+Drive / tài liệu Drive preview). Trả mảng tháng (mới -> cũ).
 */
function project_build_progress($project_id, $block_id, $clsISO){
	global $clsISO;
	$clsProgress = new ProjectProgressItems();
//	$project_id = 2;
//	$block_id = 11266;
	$pg_scope = ($block_id > 0) ? "`block_id`='{$block_id}'" : "`block_id`='0'";
//	$dbconn->debug=true;
	
	$raw = $clsProgress->getAll("`project_id`='{$project_id}' AND {$pg_scope} AND `is_active`='1' order by `id` DESC");
	if(empty($raw) && $block_id > 0){
		// Phân khu chưa có tiến độ riêng -> fallback tiến độ cấp dự án (block_id=0)
		$raw = $clsProgress->getAll("`project_id`='{$project_id}' AND `block_id`='0' AND `is_active`='1' order by `id` DESC");
	}
//	$clsISO->print_pre($raw);die;
	
	if(empty($raw)) return array();
	$mmap = array();
	foreach($raw as $pg){
		// ----- Parse media JSON: ảnh / video / tài liệu -----
		$media_items = $clsISO->to_array_json(isset($pg['media']) ? $pg['media'] : '');
		$media = array(); $video = null; $cover = null;
		if(!empty($media_items) && is_array($media_items)){
			foreach($media_items as $m){
				$type  = !empty($m['type']) ? strtolower($m['type']) : 'image';
				$src   = isset($m['source']) ? $m['source'] : '';
				$url   = isset($m['url']) ? $m['url'] : '';
				$ref   = isset($m['ref']) ? $m['ref'] : '';
				$thumb = isset($m['thumb']) ? $m['thumb'] : '';
				$name  = isset($m['name']) ? $m['name'] : '';
				if($type == 'video'){
					$is_youtube = ($src == 'youtube') || (stripos($url, 'youtu') !== false);
					if($is_youtube){
						$yt_id = '';
						if(preg_match("/(?:youtu\.be\/|v=|embed\/|vi\/)([^\?&\"'>\/]+)/", $url, $mt)) $yt_id = $mt[1];
						elseif(!empty($ref)) $yt_id = $ref;
						$embed_url = $yt_id ? ('https://www.youtube.com/embed/'.$yt_id) : $url;
						if(empty($thumb) && $yt_id) $thumb = 'https://img.youtube.com/vi/'.$yt_id.'/hqdefault.jpg';
					} else {
						$embed_url = !empty($ref) ? ('https://drive.google.com/file/d/'.$ref.'/preview')
							: (strpos($url, '/view') !== false ? str_replace('/view', '/preview', $url) : $url);
					}
					$item = array('kind'=>'video','embed_url'=>$embed_url,'thumb'=>$thumb,'full'=>$thumb,'name'=>$name,'fb_type'=>'iframe','fb_src'=>$embed_url);
					if($video === null) $video = $item;
				} else if(in_array($type, array('document','file','pdf','doc','docx','xls','xlsx','ppt','pptx','gdoc','gsheet','gslides'))){
					$doc_embed = !empty($ref) ? ('https://drive.google.com/file/d/'.$ref.'/preview')
						: (strpos($url, '/view') !== false ? str_replace('/view', '/preview', $url) : $url);
					$item = array('kind'=>'document','embed_url'=>$doc_embed,'thumb'=>$thumb,'full'=>$doc_embed,'name'=>$name,'doc_type'=>$type,'fb_type'=>'iframe','fb_src'=>$doc_embed);
				} else {
					$full = $thumb ? preg_replace('/([?&]sz=w)\d+/', '${1}1600', $thumb) : '';
					$item = array('kind'=>'image','embed_url'=>'','thumb'=>$thumb,'full'=>$full ? $full : $thumb,'name'=>$name,'fb_type'=>'image','fb_src'=>$full ? $full : $thumb);
					if($cover === null) $cover = $item;
				}
				$media[] = $item;
			}
		}
		// ----- Parse ngày: tháng + ngày (d/m/Y -> có ngày; T{m}/{y} -> 1 ngày/tháng) -----
		$date_raw = trim(isset($pg['progress_date']) ? $pg['progress_date'] : '');
		$m_sort = 0; $month_label = 'Đang cập nhật'; $day_key = ''; $day_label = ''; $d_sort = 0;
		if(preg_match('#^(\d{1,2})[\/.\-](\d{1,2})[\/.\-](\d{4})$#', $date_raw, $dm)){
			$d = (int)$dm[1]; $mn = (int)$dm[2]; $y = (int)$dm[3];
			$month_label = 'THÁNG '.$mn.'/'.$y; $m_sort = $y*10000 + $mn*100;
			$day_key = sprintf('%04d%02d%02d', $y, $mn, $d); $day_label = sprintf('%02d/%02d/%04d', $d, $mn, $y); $d_sort = (int)$day_key;
		} else if(preg_match('#^T?(\d{1,2})[\/.\-](\d{4})$#i', $date_raw, $dm)){
			$mn = (int)$dm[1]; $y = (int)$dm[2];
			$month_label = 'THÁNG '.$mn.'/'.$y; $m_sort = $y*10000 + $mn*100;
			$day_key = $month_label; $day_label = ''; $d_sort = $m_sort;
		} else {
			$day_key = 'x'.$pg['id']; $day_label = $date_raw; $d_sort = 0;
		}
		// ----- Gom nhóm: tháng -> ngày (gộp media cùng ngày) -----
		if(!isset($mmap[$month_label])){
			$mmap[$month_label] = array('month_label'=>$month_label, 'sort_ts'=>$m_sort, 'dmap'=>array());
		}
		if(!isset($mmap[$month_label]['dmap'][$day_key])){
			$mmap[$month_label]['dmap'][$day_key] = array('day_label'=>$day_label, 'sort_ts'=>$d_sort, 'media'=>array(), 'video'=>null, 'cover'=>null);
		}
		$dref = &$mmap[$month_label]['dmap'][$day_key];
		foreach($media as $mm) $dref['media'][] = $mm;
		if($dref['video'] === null && $video !== null) $dref['video'] = $video;
		if($dref['cover'] === null && $cover !== null) $dref['cover'] = $cover;
		unset($dref);
		if($m_sort > $mmap[$month_label]['sort_ts']) $mmap[$month_label]['sort_ts'] = $m_sort;
	}
	// ----- Hoàn thiện: sắp xếp tháng/ngày (mới->cũ) + đánh số panel + loại video hero khỏi grid -----
	$months = array(); $pi = 0;
	foreach($mmap as $mo){
		$days = array_values($mo['dmap']);
		usort($days, function($a, $b){ return $b['sort_ts'] - $a['sort_ts']; });
		foreach($days as $k => $d){
			// Bỏ item đã hiện ở pt-viewer (video hero) khỏi pt-grid
			if($d['video'] !== null){
				$grid = array(); $removed = false;
				foreach($d['media'] as $mm){
					if(!$removed && $mm['kind'] === 'video' && $mm['fb_src'] === $d['video']['fb_src']){ $removed = true; continue; }
					$grid[] = $mm;
				}
				$days[$k]['media'] = $grid;
			}
			$days[$k]['pi'] = $pi++;
		}
		$months[] = array('month_label'=>$mo['month_label'], 'sort_ts'=>$mo['sort_ts'], 'days'=>$days);
	}
	usort($months, function($a, $b){ return $b['sort_ts'] - $a['sort_ts']; });
	return $months;
}
function project_build_progress_groups($project_id, $block_id, $building_id, $show, $clsISO){
	$clsProperty = new Property();
	$groups = array();
	$self_title = ($show == 'project') ? 'TIẾN ĐỘ DỰ ÁN' : 'TIẾN ĐỘ';
	if($show == 'building'){
		$m = project_build_progress($project_id, $block_id, $building_id, $clsISO);
		if(!empty($m)){
			$groups[] = array('title' => $self_title, 'months' => $m);
		} else {
			// Tòa chưa có tiến độ -> CHỈ hiện tiến độ phân khu của tòa đó
			if($block_id <= 0){ $block_id = (int) $clsProperty->getOneField('for_id', "`property_id`='{$building_id}'"); }
			if($block_id > 0){
				$mb = project_build_progress($project_id, $block_id, 0, $clsISO);
				if(!empty($mb)) $groups[] = array('title' => $self_title, 'months' => $mb);
			}
		}
	} else {
		// Cấp hiện tại (dự án hoặc phân khu)
		$m = project_build_progress($project_id, ($show == 'block' ? $block_id : 0), 0, $clsISO);
		if(!empty($m)) $groups[] = array('title' => $self_title, 'months' => $m);
		// Các phân khu con (chỉ ở cấp dự án)
		$blk_ids = array();
		if($show == 'project'){
			$tmp = $clsProperty->getAll("`for_id`='{$project_id}' AND `property_type`='_BLOCK' order by `order_no` ASC", "{$clsProperty->pkey},title");
			if(!empty($tmp)) foreach($tmp as $b){ $blk_ids[(int)$b[$clsProperty->pkey]] = $b['title']; }
		} else if($show == 'block' && $block_id > 0){
			$blk_ids = array(); // ở cấp phân khu chỉ liệt kê tòa bên dưới
		}
		foreach($blk_ids as $bid => $btitle){
			$mb = project_build_progress($project_id, $bid, 0, $clsISO);
			if(!empty($mb)) $groups[] = array('title' => $btitle, 'months' => $mb);
			// Tòa của phân khu này
			$bds = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BUILDING' AND `for_id`='{$bid}' order by `reg_date` ASC", "{$clsProperty->pkey},title");
			if(!empty($bds)) foreach($bds as $bd){
				$gid = (int)$bd[$clsProperty->pkey];
				$mg = project_build_progress($project_id, $bid, $gid, $clsISO);
				if(!empty($mg)) $groups[] = array('title' => $bd['title'], 'months' => $mg);
			}
		}
		// Tòa của phân khu đang xem (cấp phân khu)
		if($show == 'block' && $block_id > 0){
			$bds = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BUILDING' AND `for_id`='{$block_id}' order by `reg_date` ASC", "{$clsProperty->pkey},title");
			if(!empty($bds)) foreach($bds as $bd){
				$gid = (int)$bd[$clsProperty->pkey];
				$mg = project_build_progress($project_id, $block_id, $gid, $clsISO);
				if(!empty($mg)) $groups[] = array('title' => $bd['title'], 'months' => $mg);
			}
		}
	}
	// Đánh số panel TOÀN CỤC (mọi nhóm dùng chung vùng .pt-main)
	$pi = 0;
	foreach($groups as $gi => $g){
		foreach($g['months'] as $mi => $mo){
			foreach($mo['days'] as $di => $d){ $groups[$gi]['months'][$mi]['days'][$di]['pi'] = $pi++; }
		}
	}
	return $groups;
}
function project_detail(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsShop = new Shop();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProfile = new Profile();
	$clsCache = new Cache();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$assign_list["clsCache"] = $clsCache;
	###
	$show = Input::get('show','project');
	$project_id = (int) Input::get('project_id', 0);
	$block_id = (int) Input::get('block_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	$cat_id = (int) Input::get('cat_id', _PROJECT_DOCS_IMGVIDEO_CATID);
	$root_id = $clsProperty->getRootId($cat_id);
	$oneCat = $clsProperty->getOne($cat_id);
	$view_doc = "grid";
	if(!empty($oneCat)) {
		$more_cat = $clsISO->to_array_json($oneCat['more_information']);
		$view_doc = !empty($more_cat["view"]) ? $more_cat["view"] : "grid";
	}
	$assign_list["view_doc"] = $view_doc;
	$assign_list["show"] = $show;
	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	$assign_list["cat_id"] = $cat_id;
	$assign_list["oneCat"] = $oneCat;
	$assign_list["root_id"] = $root_id;
	$more_profile = $oneProfile["more_information"];
	$list_docs_save = !empty($more_profile["list_docs_save"]) ? $more_profile["list_docs_save"] : array();
	// $clsISO->print_pre($list_docs_save);die;
	$arr_docs_save = array_keys($list_docs_save);
	$assign_list["arr_docs_save"] = $arr_docs_save;
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
	$assign_list["more_information_project"] = $more_information;
	$assign_list["is_lowfloor"] = @in_array(_BLOCK_TYPE_LOWFLOOR_SALE, $arr_block_type) ? 1 : 0;
	$list_utilities = $list_shops = array();
	// Tiến độ (category "Tiến độ" _CATEGORY_DOCS id=11646): dữ liệu từ project_progress_items, gom nhóm theo tháng
	if($cat_id == _PROJECT_PROGRESS_CATID){
		$assign_list["list_progress"] = project_build_progress($project_id, $block_id, $clsISO);
		
	}
	// var_dump($cat_id); die();
	
	if($cat_id == _PROJECT_DOCS_UTILITY_CATID){
		$list_utilities = $clsProperty->getCacheItems("_UTILITIES_PROJECT");
		$utilities = $oneProject['utilities'];
		$utilities = $clsISO->to_array_json($utilities);
		foreach($list_utilities as $k_cat => $v_cat) {
			$total = 0;
			$list_utilities[$k_cat]['utilities'] = [];
			$utilities_project = $utilities_block = [];
			foreach ($utilities as $k_utilities => $v_utilities) {
				if($v_utilities['cat_id'] == $v_cat['property_id'] && (int) $v_utilities['block_id'] == 0) {
					$utilities_project[$k_cat]['utilities'][] = $v_utilities;
				}
				if($v_utilities['cat_id'] == $v_cat['property_id'] 
						&& ($v_utilities['block_id'] == $block_id && (!isset($v_utilities['building_ids']) || empty($v_utilities['building_ids'])))) {
					$utilities_block[$k_cat]['utilities'][] = $v_utilities;					
				}
				if ($show == 'project'){
					if($v_utilities['cat_id'] == $v_cat['property_id'] && (int) $v_utilities['block_id'] == 0) {
						$list_utilities[$k_cat]['utilities'][] = $v_utilities;
						++$total;
					}
				} else if($show == 'block') {
					$block_id = (int) Input::get('block_id', 0);
					if($v_utilities['cat_id'] == $v_cat['property_id']) {
						if($v_utilities['block_id'] == 0){
							$order_no = 0;
							$v_utilities['title'] = '<span class=\'badge bg-label-danger me-1\'>Chung</span>' . $v_utilities['title'];
						} else {
							$order_no = 1;
						}
						$v_utilities['order_no'] = $order_no;
						$list_utilities[$k_cat]['utilities'][] = $v_utilities;
						++$total;
					}
				} else if($show == 'building'){
					$building_id = (int) Input::get('building_id', 0);
					$oneBuilding = $clsProperty->getOne($building_id, "for_id");
					$block_id = $oneBuilding['for_id'];
					if($v_utilities['cat_id'] == $v_cat['property_id']) {
						if((!isset($v_utilities['building_ids']) || empty($v_utilities['building_ids']))){
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
			if($show == 'building'){
				if(!empty($list_utilities[$k_cat]['utilities'])) {
					$arr_order = @array_column($list_utilities[$k_cat]['utilities'], 'order_no');
					@array_multisort($arr_order, SORT_DESC, $list_utilities[$k_cat]['utilities']);
				}else if(!empty($utilities_block[$k_cat]['utilities'])) {
					$list_utilities[$k_cat]['utilities'] = $utilities_block[$k_cat]['utilities'];
				}else if(!empty($utilities_project[$k_cat]['utilities'])) {
					$list_utilities[$k_cat]['utilities'] = $utilities_project[$k_cat]['utilities'];
				}
				
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
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}' and JSON_UNQUOTE(JSON_EXTRACT(`more_information`,'$.on_sale'))='1' order by order_no ASC, JSON_UNQUOTE(JSON_EXTRACT(`more_information`,'$.on_sale')) DESC", $field);
		$vr_link = $clsISO->getValue("vr_link", $more_information);
		$vr_source = $clsISO->getValue("vr_source", $more_information);
		if(!empty($list_blocks)){
			foreach($list_blocks as $key => $val){
				$more_information_bl = $val['more_information'];
				$more_information_bl = $clsISO->to_array_json($more_information_bl);
				$construction_type = isset($more_information_bl['construction_type']) 
					? $more_information_bl['construction_type'] : "";
				$construction_type_arr = !empty($construction_type) 
					? @explode('|', $construction_type) : array();
				$more_information_bl['construction_type'] = $construction_type_arr;
				$list_blocks[$key]['more_information'] = $more_information_bl;
			}
		}
		$link_stock = $clsProject->getLink($project_id);
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
			$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image,parent_id";
			$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' and JSON_UNQUOTE(JSON_EXTRACT(`more_information`,'$.on_sale'))='1' and `for_id`='{$block_id}' order by `reg_date` ASC", $field);
			$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
				and `for_id`='{$project_id}' and `{$clsProperty->pkey}` <> '{$block_id}' order by `order_no` ASC", $field);
		} else {
			$link_stock = sprintf('/project/p%s/block%s.html', $project_id, $block_id);
			$field = "{$clsProperty->pkey},parent_id,property_code,title,intro,more_information,image,parent_id";
			$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
				and `for_id`='{$project_id}' order by `order_no` ASC", $field);
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
		$layout_block_ns = $clsISO->getValue("layout_ns", $block_information);
		$assign_list["layout_block_ns"] = $layout_block_ns;
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
		$field = "{$clsProperty->pkey},property_code,title,intro,more_information,image,parent_id";
		$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' and JSON_UNQUOTE(JSON_EXTRACT(`more_information`,'$.on_sale'))='1' and `for_id`='{$block_id}' order by `reg_date` ASC", $field);
		$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
				and `for_id`='{$project_id}' and `{$clsProperty->pkey}` <> '{$block_id}' order by `order_no` ASC", $field);
	} else if ($show == 'map') {
		$cat_id = '';
		if(!empty($block_id)) {
			$oneBlock = $clsProperty->getOne($block_id);
			$assign_list["oneBlock"] = $oneBlock;
		}
		if(!empty($building_id)) {
			$oneBuilding = $clsProperty->getOne($building_id);
			$assign_list["oneBuilding"] = $oneBuilding;
		}
		$project = $oneProject;		
		$location = array();
		#block map
		$array_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
		$clsStock = new Stock();
		$arr_location = $location_block = [];
		$location_block = !empty($more_information["location_block"]) ? $more_information["location_block"] : [];
		$lstStock = $clsStock->getAll("`block_id` IN (".implode(',', array_keys($location_block)).") AND `total_price_vat`>1000000000 AND JSON_EXTRACT(`more_information`,'$.DT_TT')>0 GROUP BY `block_id`", "`block_id`,`stock_id`,MIN(`total_price_vat`/JSON_EXTRACT(`more_information`,'$.DT_TT') ) AS price_min,MAX(`total_price_vat`/JSON_EXTRACT(`more_information`,'$.DT_TT') ) AS price_max");
		foreach ($lstStock as $key => $val) {
			$arr_price_block[$val["block_id"]] = [
				"price_min"	=>	!empty($val["price_min"]) ? round($val["price_min"]/1000000,1)*1000000 : 0,
				"price_max"	=>	!empty($val["price_max"]) ? round($val["price_max"]/1000000,1)*1000000 : 0,
			]; 
		}
//			$clsISO->print_pre($arr_price_block);die;
		if(!empty($location_block)) {
			foreach ($location_block as $key => $val) {
				$info_location = [
					"lat"	=>	(float)$val["lat"],
					"lng"	=>	(float)$val["lng"],
					"url"	=>	$clsProject->getLink($project_id,$val["block_id"]),
					"label"	=>	$array_block_cached[$val["block_id"]]["title"],
					"id" 	=> 	$val["block_id"],
					"image" => 	$array_block_cached[$val["block_id"]]["image"],
					"price_per_m2"	=>	(int)$arr_price_block[$val["block_id"]]["price_min"]."~".(int)$arr_price_block[$val["block_id"]]["price_max"],
					"territory" => []
				];
				if($block_id == $val["block_id"]) {
					$location = $info_location;
				}
				$arr_location[] = $info_location;
				$location_block[$key]['link'] = $clsProject->getLinkDetail($project_id,$val["block_id"],0,'overview',$array_block_cached[$val["block_id"]]);
				$location_block[$key]['infomation_ex'] = $array_block_cached[$val["block_id"]]["more_information"];
				$location_block[$key]['more_information'] = $array_block_cached[$val["block_id"]]["more_information"];
				$location_block[$key]['attrs'] = $array_block_cached[$val["block_id"]]["more_information"]["attrs"];
				$location_block[$key]['logo'] = $array_block_cached[$val["block_id"]]["image"];
				$location_block[$key]['image'] = $array_block_cached[$val["block_id"]]["image"];
				$location_block[$key]['title'] = $array_block_cached[$val["block_id"]]["title"];
			}
		}
		$assign_list["location_block"] = json_encode($arr_location,JSON_UNESCAPED_UNICODE);
		$assign_list["info_locations"] = $location_block;
		#end block map
		
		$vr_link = $clsISO->getValue("vr_link", $more_information);
		$vr_source = $clsISO->getValue("vr_source", $more_information);
		$location_highlight = $core->get_field($more_information, "location_highlight", []);
		$assign_list["locations"] =json_encode($location_highlight,JSON_UNESCAPED_UNICODE);
		$map_la = $core->get_field($more_information,"map_la", null);
		$map_lo = $core->get_field($more_information,"map_lo", null);
		$title_info = 'Bản đồ - '; 
		if(!empty($map_la) && !empty($map_lo) && empty($location)) {
			$project['image'] = !empty($project['image']) ? sprintf('%s%s', FH_URL, $project['image']) : "";
			$location = [
				"lat"	=>	(float) $more_information["map_la"],
				"lng"	=>	(float) $more_information["map_lo"],
				"url"	=>	$clsProject->getLink($project["project_id"]),
				"label"	=>	$project["title"],
				"id" 	=> 	$project['project_id'],
				"image" => 	$project['image'],
                "price_per_m2"	=> $more_information["price_per_m2"],
                "territory" => !empty($more_information['map_territory']) ? $more_information['map_territory'] : []
			];
			$project['link'] = $clsProject->getLinkDetail($project['project_id'],0,0,'overview',$project);
			$project['infomation_ex'] = $more_information;
		}
		$title_page = $project['title'];
		$assign_list["location"] = json_encode($location,JSON_UNESCAPED_UNICODE);
		$assign_list["map_zoom"] = $core->get_field($more_information, "map_zoom", 14);
		$assign_list["project"] = $project;
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
	if($show == 'project' || $show == 'map'){
		$project_cat_menu = !empty($more_information["project_cat_menu"]) ? $more_information["project_cat_menu"] : array();
//		if(!empty($project_cat_menu)) {
//			$cond.= " and {$clsProperty->pkey} IN (".implode(',',$project_cat_menu).")";
//		}else{
//			$cond.= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."'";
//			$cond .= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_TRAINING_CATID."' and {$clsProperty->pkey}<>'"._PROJECT_DOCS_POLICY_CATID."'";
//		}
	} else if($show == "block" && $oneBlock['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
		$cond.= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."'";
	}
	$list_category_docs = $clsProperty->getAll("{$cond} and `parent_id`='0' order by `order_no` ASC", $field);
	//	$clsISO->print_pre($list_category_docs);die;
	if($cat_id != _PROJECT_DOCS_UTILITY_CATID){
		$cond = "`is_trash`='0'";
		if($show == "block") {
			$cond.= " AND `type`='block' AND `block_ids` LIKE '%|{$block_id}|%'";
		}else if($show == "building") {
			$cond.= " AND (`type`='building' OR `type`='block') AND (`building_ids` LIKE '%|{$building_id}|%' OR `block_ids` LIKE '%|{$block_id}|%')";
		}else{
			$cond.= " AND ".$clsProjectMeta->condByProject($project_id);
		}
		$list_docs = $list_posts = array(); $total_docs = 0;
		$field = "{$clsProperty->pkey},slug,title";
		$cnd = "`is_trash`=0 and `property_type`='_CATEGORY_DOCS'";
		/*if($show == 'project'){
			$arr_notins = array(_PROJECT_DOCS_BM_CATID, _PROJECT_DOCS_CHTH_CATID, _PROJECT_DOCS_INTERIOR_CATID);
			$cnd.= " and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins).")";
		} else if($show == 'block' && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
			$arr_notins = array(_PROJECT_DOCS_BM_CATID);
			$cnd.= " and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins).")";
		}*/
//		$dbconn->debug=true;
		$list_childs = $clsProperty->getAll("{$cnd} and `parent_id`='{$cat_id}' order by order_no ASC", $field);
//		$clsISO->print_pre($list_childs);die;
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
					$list_childs[$key]['list_posts'] = $list_posts;
				}
				$list_docs = array();
				$tmp = $clsProjectMeta->getAll($cond." and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
				
				if(empty($tmp) && $show != "project") {
					$tmp = $clsProjectMeta->getAll("`is_trash`='0' AND `type`='block' AND `block_ids` LIKE '%|{$block_id}|%' and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
					if(empty($tmp)) {
						if($cat_id == _PROJECT_DOCS_POLICY_CATID || $cat_id == _PROJECT_DOCS_TRAINING_CATID) {
							$cond_more= "`is_trash`='0' AND ".$clsProjectMeta->condByProject($project_id);
						}else{
							$cond_more= "`is_trash`='0' AND `type`='project' AND ".$clsProjectMeta->condByProject($project_id);
						}
						$tmp = $clsProjectMeta->getAll($cond_more." and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
					}
				}
//				================
				if(!empty($tmp)){
					$tmp = $clsProjectMeta->getListResult($tmp,1);
					$total_docs += count($tmp); $is_active = 1;
					foreach($list_childs as $gkey => $gval){
						if(isset($gval['is_active']) && $gval['is_active'] == 1){
							$is_active = 0; 
							break;
						}
					}
					if($is_active == 1) $list_childs[$key]['is_active'] = 1;
					foreach($tmp as $okey => $oval){
						$list_docs = array_merge($list_docs,$oval['list_docs']);
					}
				}
//				================
				$list_childs[$key]['cond'] = $cond;
				$list_childs[$key]['list_docs'] = $list_docs;
			}
		} else {
			$list_docs = array();
			if($cat_id == _PROJECT_DOCS_LAYOUT_CATID){
				$field = "{$clsProjectMeta->pkey}";
				$tmp = $clsProjectMeta->getAll($cond." and (`cat_id`='{$cat_id}' or cat_id='"._PROJECT_DOCS_BM_CATID."') 
					order by `reg_date` DESC");
//				================
				if(empty($tmp)) {
					$cond= "`is_trash`='0' AND `type`='block' AND `block_ids` LIKE '%|{$block_id}|%'";
					$tmp = $clsProjectMeta->getAll($cond." and (`cat_id`='{$cat_id}' or cat_id='"._PROJECT_DOCS_BM_CATID."') 
					order by `reg_date` DESC");
					if(empty($tmp)) {
						if($cat_id == _PROJECT_DOCS_POLICY_CATID || $cat_id == _PROJECT_DOCS_TRAINING_CATID) {
							$cond= "`is_trash`='0' AND ".$clsProjectMeta->condByProject($project_id);
						}else{
							$cond= "`is_trash`='0' AND `type`='project' AND ".$clsProjectMeta->condByProject($project_id);
						}
						$tmp = $clsProjectMeta->getAll($cond." and (`cat_id`='{$cat_id}' or cat_id='"._PROJECT_DOCS_BM_CATID."') 
					order by `reg_date` DESC");
					}
				}
//				================
				
			} else {
				$tmp = $clsProjectMeta->getAll($cond." and `cat_id`='{$cat_id}' order by `reg_date` DESC");				
//				================
				if(empty($tmp)) {
					$cond= "`is_trash`='0' AND `type`='block' AND `block_ids` LIKE '%|{$block_id}|%'";
					$tmp = $clsProjectMeta->getAll($cond." and `cat_id`='{$cat_id}' order by `reg_date` DESC");
					if(empty($tmp)) {
						if($cat_id == _PROJECT_DOCS_POLICY_CATID || $cat_id == _PROJECT_DOCS_TRAINING_CATID) {
							$cond= "`is_trash`='0' AND ".$clsProjectMeta->condByProject($project_id);
						}else{
							$cond= "`is_trash`='0' AND `type`='project' AND ".$clsProjectMeta->condByProject($project_id);
						}
						$tmp = $clsProjectMeta->getAll($cond." and `cat_id`='{$cat_id}' order by `reg_date` DESC");
					}
				}
//				================
			}
			if($cat_id == _PROJECT_DOCS_POLICY_CATID || $cat_id == _PROJECT_DOCS_TRAINING_CATID) {
				$tmp = $clsProjectMeta->getListResult($tmp,0);
			}else{
				$tmp = $clsProjectMeta->getListResult($tmp,1);
			}
			if(!empty($tmp)){
				$total_docs += count($tmp);
				foreach($tmp as $okey => $oval){
					$list_docs = array_merge($list_docs,$oval['list_docs']);
				}
			}	
			/** CSBH */
			if($cat_id == _PROJECT_DOCS_CSBH_CATID){
				$clsPolicy = new Policy(); 
				$list_policy_blocks = $list_policy = array();
				if($show== 'block' || $show == 'building') {
					if($show== 'block'){
						$block_type = _BLOCK_TYPE_LOWFLOOR_SALE;
						$pattern = sprintf('|%s_%s_', $project_id, $block_id);
						$block_information = $oneBlock['more_information'];
						$block_information = $clsISO->to_array_json($block_information);
						$list_policy = !empty($block_information["sales_policy"]) 
							? $block_information["sales_policy"] : array();	
						if(!empty($list_policy)){
							foreach($list_policy as $okey => $oval){
								if(!empty($oval['image'])) {
									$list_policy[$okey]['image'] = $clsISO->getGoogleUrl($oval['image']);
								}else{
									unset($list_policy[$okey]);
								}								
							}
						}				
					} else {						
						$block_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
						$pattern = sprintf('|%s_%s_%s|', $project_id, $block_id, $building_id);
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
					$list_policy_docs = $clsPolicy->getAll("`is_trash`=0 
						and `scope_slash` like '%".$pattern."%' order by `ms_date` DESC");
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
				}elseif($show == "project"){		
					$json_project = sprintf('"project_id":"%s"', $project_id);
					$list_policy_docs = $clsPolicy->getAll("`is_trash`=0 and `block_type` IN (".implode(',',$arr_block_type).")
						and (`scope_slash` like '%|{$project_id}!_%' escape '!' or `scope` like '%{$json_project}%') order by `ms_date` DESC");
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
		$cond .= "	AND `type`='building' AND `building_id` LIKE '%|{$building_id}|%'";
	}else if($block_id > 0) {
		$cond .= "	AND `type`='block' AND `block_ids` LIKE '%|{$block_id}|%'";
	}else if($block_id > 0) {
		$cond .= "	AND `type`='project' AND ".$clsProjectMeta->condByProject($project_id);
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
	$listItem = $clsProperty->getAll($cond, $field);
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
	echo $html; die();
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
	$list_help_links = $clsProjectMeta->getAll("is_trash=0 and ((`type`='building' and `building_ids` LIKE '%|{$building_id}|%') 
	or (`type`='block' and `block_ids` LIKE '%|{$block_id}|%')) order by `type` ASC", "type,for_id,title,content");
	if(!empty($list_help_links)){
		$arr_cached_property = array();
		foreach($list_help_links as $key => $val){
			$type = $val['type'];
			if($type == "building") {
				$for_id = $building_id;	
				$arr_cached_property[$for_id] = $oneBuilding['title'];
			}else{
				$for_id = $block_id;	
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
									</div>
									<div class="col-6 col-xs-6">
										<div class="d-flex flex-column box_text">
											<span class="">Hướng</span>
											<span class="text-value">'.$clsProperty->getTitle($home_direction_id).'</span>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-6 col-xs-6 flex-fill">
										<div class="d-flex flex-column box_text">
											<span>'.($is_price_early?'Giá TTS':'Full VAT').'</span>
											<span class="text-price">'.$clsISO->shortNumber($total_price_early).'</span>
										</div>
									</div>';
						if(!empty($total_price_bank)) {
							$html_shapes .='<div class="col-6 col-xs-6 flex-fill">
										<div class="d-flex flex-column box_text">
											<span class="">Giá vay</span>
											<span class="text-price">'.$clsISO->shortNumber($total_price_bank).'</span>
										</div>
									</div>';
						}elseif(!empty($is_price_early) && !empty($v['total_price_vat'])) {
							$html_shapes .='<div class="col-6 col-xs-6 flex-fill">
									<div class="d-flex flex-column box_text">
										<span class="">Full VAT</span>
										<span class="text-price">'.$clsISO->shortNumber($v['total_price_vat']).'</span>
									</div>
								</div>';
						}					
				$html_shapes .='</div>
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
	$oneBuilding = $clsProperty->getOne($building_id);
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
					$oneStock = $clsStock->getOne($stock_id, "`project_id`,`building_id`,`code`,`more_information`");
					$project_id = (int) $oneStock['project_id'];
					$building_id = (int) $oneStock['building_id'];
					$more_information = $clsISO->to_array_json($oneStock["more_information"]);
					$building_code = !empty($more_information["building_code"]) ? $more_information["building_code"] : "";
					$code = $oneStock['code'];
					$cond= "{$sql_string} and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'
						and `project_id`='{$project_id}' and `block_id`='{$block_id}' and `agency_id`>0 
						and `building_id`='{$building_id}' and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
						and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`>0 and `code`='{$code}' AND JSON_EXTRACT(`more_information`,\"$.building_code\")='{$building_code}'";
					if($clsStock->countItem($cond." AND `agency_id`='"._AGENCY_FH_ID."'") > 0){
						$val["is_dq"] = 1;
					}
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
	$list_blocks = $list_img_preloaders = $oneBlock = $list_map_blocks = array();
	if($project_id == 0){
		header('Location : /');
		exit();
	}
	$field = "{$clsProject->pkey},`title`,`more_information`";
	$oneProject = $clsProject->getOne($project_id, $field);
	$more_information = $oneProject['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	#
	$is_project_block = 0; 
	$sql_stock_query = "`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'  `project_id`='{$project_id}' 
		AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `agency_id`>0";
	if($block_id > 0){
		$sql_stock_query.= " AND `block_id`='{$block_id}'";
		$oneBlock = $clsProperty->getArraySearchByKey("_BLOCK", $block_id);
		$more_information = $oneBlock['more_information'];
		if(isset($more_information['is_project']) && (int) $more_information['is_project'] == 1) {
			$is_project_block = 1;
		}
	}	
	$is_map_tiles = $core->get_field($more_information, "is_map_tiles", 0);
	if($is_map_tiles == 1){
		$curr_zoom = 3;
		$scriptJS = '<script type="text/javascript">
			var map_configs= {},
				project_id = \''.$project_id.'\',
				block_id = \''.$block_id.'\',
				_AGENCY_CNCN_ID = \''._AGENCY_CNCN_ID.'\',
				_AGENCY_FH_ID = \''._AGENCY_FH_ID.'\';
			map_configs[\'curr_zoom\'] = '.$curr_zoom.';
			map_configs[\'max_zoom\'] = '.$more_information['max_zoom'].';
			map_configs[\'tiles\'] = \''.$more_information['tiles_link'].'\';
			map_configs[\'max_bounds\'] = '.$core->get_field($more_information, 'max_bound','\'\'').';
			map_configs[\'center_point\'] = '.$core->get_field($more_information, 'center_point','\'\'').';
			map_configs[\'tms_enable\'] = '.((int) $core->get_field($more_information,'tms_enable',0) ==1 ? 'true': 'false').';
		</script>';
	} else {
		$img_layout = $core->get_field($more_information, "layout", "");
		$has_block = (int) $core->get_field($more_information, "has_block", 1);
		$scriptJS = '<script type="text/javascript">
			var image_maps = {},
				_AGENCY_CNCN_ID = \''._AGENCY_CNCN_ID.'\',
				_AGENCY_FH_ID = \''._AGENCY_FH_ID.'\',
				project_id = \''.$project_id.'\',
				block_id = \''.$block_id.'\';
				image_maps[\'project\'] = \''.sprintf('%s%s', FH_URL, $img_layout).'\';';
		if($has_block == 1){
			if($clsCache->has('_stock_block_'.$project_id.'_cached') && 1==2){
				$list_blocks = $clsCache->get('_stock_block_'.$project_id.'_cached');
				// $clsISO->print_pre($list_blocks); die();
			} else {
				$field = "{$clsProperty->pkey},`title`,`more_information`";
				$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}' 
				and `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` ASC", $field);
				if(!empty($list_blocks)){
					foreach($list_blocks as $key => $val){
						$_more_information = $val['more_information'];
						$_more_information = $clsISO->to_array_json($_more_information);
						$list_blocks[$key]['more_information'] = $_more_information;
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
				$_block_id = (int) $val[$clsProperty->pkey];
				$_mi = $val['more_information'];
				$_is_tiles = (int) $core->get_field($_mi, "is_map_tiles", 0);
				$_tiles_link = $core->get_field($_mi, "tiles_link", "");
				$layout_ms = $core->get_field($_mi, "layout_ms", "");
				if($_is_tiles == 1 && !empty($_tiles_link)){
					// Phân khu dạng tiles -> mỗi tab là 1 tiles map riêng
					$list_map_blocks[] = array(
						'block_id' => $_block_id, 'title' => $val['title'], 'is_tiles' => 1,
						'tiles' => $_tiles_link,
						'center_point' => $core->get_field($_mi, 'center_point', ''),
						'max_zoom' => (int) $core->get_field($_mi, 'max_zoom', 9),
						'curr_zoom' => 3,
						'tms_enable' => (int) $core->get_field($_mi, 'tms_enable', 0)
					);
				} else if(!empty($layout_ms)){
					// Phân khu dạng ảnh mặt bằng
					$scriptJS.= '
				image_maps['.$_block_id.']=\''.sprintf('%s%s', FH_URL, $layout_ms).'\';';
					$list_img_preloaders[] = sprintf('%s%s', FH_URL, $layout_ms);
					$list_map_blocks[] = array('block_id' => $_block_id, 'title' => $val['title'], 'is_tiles' => 0);
				}
			}
		}
		// has_project_map: dự án có ảnh mặt bằng cấp dự án -> map dự án (State A);
		// nếu không, các phân khu có ảnh (layout_ms) sẽ thành tab (State B); không có ảnh nào -> empty (State C)
		$has_project_map = !empty($img_layout) ? 1 : 0;
		$scriptJS.= '
			var has_project_map = '.$has_project_map.',
				map_blocks = '.json_encode($list_map_blocks, JSON_UNESCAPED_UNICODE).';';
		$scriptJS.= '</script>';
		###
		$assign_list["img_layout"] = $img_layout;
		$assign_list["scriptJS"] = $scriptJS;
	}
	$total_stocks = $clsStock->countItem($sql_stock_query);
	$assign_list["total_stocks"] = $total_stocks;
		
	$assign_list["block_id"] = $block_id;
	$assign_list["oneBlock"] = $oneBlock;
	$assign_list["is_project_block"] = $is_project_block;
	$assign_list["project_id"] = $project_id;
	$assign_list["oneProject"] = $oneProject;
	$assign_list["scriptJS"] = $scriptJS;
	$assign_list["is_map_tiles"] = $is_map_tiles;
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
/**
 * Gắn giá VAT thô (VND) cho 1 shape từ more_information -> FE render pin giá: (price_vat/1e9).toFixed(2).
 * Dùng chung cho get_shapes + get_shapes_upgraded.
 */
function project_enrich_stock_price($oneShape, $stockRow, $clsISO){
	$more_information = isset($stockRow['more_information']) ? $clsISO->to_array_json($stockRow['more_information']) : array();
	$oneShape['price_vat'] = isset($more_information['total_price_vat']) ? $clsISO->processSmartNumber($more_information['total_price_vat']) : 0;
	return $oneShape;
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
	$cond = "`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}'";
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
					and project_id='{$project_id}' AND `agency_id`>0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND {$clsStock->pkey} in (".implode(',', $list_stock_ids).")", "{$clsStock->pkey},status_id,agency_id,more_information");
					foreach($list_stocks as $key => $val){
						$stock_id = $val[$clsStock->pkey];
						$oneShape = $list_shapes[$stock_id];
						$oneShape['status_id'] = $val['status_id'];
						$oneShape['agency_id'] = $val['agency_id'];
						$oneShape = project_enrich_stock_price($oneShape, $val, $clsISO);
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
function project_get_shapes_upgraded(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id;
	$clsCache = new Cache();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$holderG = Input::post('holderG', 'project');
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$_results = array('msg' => '_error', 'holderG' => $holderG);
	###
	$cond = "`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND `holderG`='{$holderG}'";
	if(in_array($project_id, [_PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID])){
		// $clsCache->has('_stock_map_cached');
		if($clsCache->has('_stock_map_cached'.$project_id."_".$block_id)){
			$dataCached = $clsCache->get('_stock_map_cached'.$project_id."_".$block_id);
			$list_shapes = $core->get_field($dataCached, 'list_shapes', []);
			$list_stock_ids = $core->get_field($dataCached, 'list_stock_ids', []);
			// var_dump($list_stock_ids); die();
		} else {
			$list_stock_ids = $list_shapes = array();
			$cond.= " and `project_id` in (".implode(',', [_PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID]).")";
			$cond .= " AND `block_id`='{$block_id}'";
			$tmp = $clsStockShape->getAll($cond, "{$clsStockShape->pkey},shapes");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$_shapes = $val['shapes'];
					$_shapes = $clsISO->to_array_json($_shapes);
					if(!empty($_shapes)){
						foreach($_shapes as $okey => $oval){
							$stock_id = $oval['stock_id'];
							if((int) $stock_id > 0 && !in_array($stock_id, $list_stock_ids)){
								$list_stock_ids[] = $stock_id;
								$list_shapes[$stock_id] = $oval;
							}
						}
					}
				}
			}
			$clsCache->put('_stock_map_cached'.$project_id."_".$block_id, json_encode(array(
				'list_shapes' => $list_shapes,
				'list_stock_ids' => $list_stock_ids
			), JSON_UNESCAPED_UNICODE), 24*60*60);
		}
//		$clsISO->print_pre($list_stock_ids);die;
		$total_record = 0; $shapes = array();
		if(!empty($list_stock_ids) && !empty($list_shapes)){
			$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND `project_id` in (".implode(',', [_PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID]).") AND `agency_id`>0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND {$clsStock->pkey} in (".implode(',', $list_stock_ids).")", "{$clsStock->pkey},`status_id`,`agency_id`,`more_information`");
			if(!empty($list_stocks)){
				$total_record = count($list_stocks);
				foreach($list_stocks as $key => $val){
					$stock_id = $val[$clsStock->pkey];
					$oneShape = $list_shapes[$stock_id];
					$oneShape['status_id'] = $val['status_id'];
					$oneShape['agency_id'] = $val['agency_id'];
					$oneShape = project_enrich_stock_price($oneShape, $val, $clsISO);
					$shapes[] = $oneShape;
				}
			}
		}
		$_results['msg'] = '_success';
		$_results['shapes'] = $shapes;
		$_results['total_record'] = $total_record;
	} else {
		$cond.= " and `project_id`='{$project_id}'";
		$tmp = $clsStockShape->getByCond($cond);
		if(!empty($tmp)){
			$shapes = $tmp['shapes'];
			$shapes = $clsISO->to_array_json($shapes);
			$_results['msg'] = '_success';
			if(!empty($shapes)){
				$list_stock_ids = $list_shapes = array();
				foreach($shapes as $key => $val){
					$stock_id = $val['stock_id'];
					if($stock_id >0 && !in_array($stock_id, $list_stock_ids)){
						$list_shapes[$stock_id] = $val;
						$list_stock_ids[] = $stock_id;	
					}
				}
				$total_record = 0; $shapes = array();
				if(!empty($list_stock_ids) && !empty($list_shapes)){
					$list_stocks = $clsStock->getAll("`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
					and project_id='{$project_id}' AND `agency_id`>0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND {$clsStock->pkey} in (".implode(',', $list_stock_ids).")", "{$clsStock->pkey},status_id,agency_id,more_information");
					if(!empty($list_stocks)){
						$total_record = count($list_stocks);
						foreach($list_stocks as $key => $val){
							$stock_id = $val[$clsStock->pkey];
							$oneShape = $list_shapes[$stock_id];
							$oneShape['status_id'] = $val['status_id'];
							$oneShape['agency_id'] = $val['agency_id'];
							$oneShape = project_enrich_stock_price($oneShape, $val, $clsISO);
							$shapes[] = $oneShape;
						}
					}
				}
			}
			
			$_results['shapes'] = $shapes;
			$_results['total_record'] = $total_record;
			$_results['_STOCK_STATUS_SOLD_ID'] = _STOCK_STATUS_SOLD_ID;
		}
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
		$cond.= " AND (`t1`.`type`='building' or `t1`.`type`='block') AND (`t1`.`building_ids` LIKE '%|{$building_id}|%' OR `t1`.`block_ids` LIKE '%|{$block_id}|%')";
	}else if(!empty($block_id)) {
		$cond.= " AND `t1`.`type`='block' AND `t1`.`block_ids` LIKE '%|{$block_id}|%'";
	}else{	
		$cond_belong_project = $clsProjectMeta->condByProject($project_id);
		$cond .= " AND (
		   (`t1`.type = 'project' AND {$cond_belong_project})
		   OR (`t1`.type = 'block' AND {$cond_belong_project} AND `t1`.`block_ids` <> '')
		   OR (`t1`.type = 'building' AND {$cond_belong_project} AND `t1`.`block_ids` <> '' AND `t1`.`building_ids` <> '')
	  )";
	}
	$arr_cache = $list_docs = array();
	if($clsISO->_DEV()){
//		$dbconn->debug=true;
	}
	$tmp = $dbconn->getAll( "SELECT * FROM {$clsProjectMeta->tbl} `t1` 
		WHERE ".$cond." AND JSON_EXTRACT(`more_information`,\"$.".$view_type."\")='1' order by `reg_date` DESC");
	if($clsISO->_DEV()){
//		$clsISO->print_pre($tmp);die;
	}
	if(!empty($tmp)){
		$tmp = $clsProjectMeta->getListResult($tmp);
		foreach($tmp as $okey => $oval){
			$list_docs = array_merge($list_docs,$oval['list_docs']);
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
function project_save_docs(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	###
	$res = ["result"=>false,"msg"=>"error"];
	$sheet_id = Input::post('sheet_id', "");
	$action = Input::post('action', "save");
	$docs_id = (int) Input::post('docs_id', 0);
	if(!empty($profile_id) && !empty($sheet_id) && $docs_id) {
		$more_information = $oneProfile["more_information"];
		$list_docs_save = !empty($more_information["list_docs_save"]) ? $more_information["list_docs_save"] : array();
		if($action == "save") {
			$list_docs_save[$sheet_id] = $docs_id;
		}else{
			unset($list_docs_save[$sheet_id]);
		}
		$more_information["list_docs_save"] = $list_docs_save;
		if($clsProfile->updateOne($profile_id,array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))) {
			$oneProfile["more_information"] = $more_information;
			$res = ["result"=>true,"msg"=>"Lưu thành công"];
		}		
	}
	// Return
	echo $res; die();
}
function calculateDistance($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}
function getAddressFromLatLng($lat, $lng, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";
    $response = file_get_contents($url);
    $json = json_decode($response, true);
    if ($json['status'] === 'OK' && !empty($json['results'][0])) {
        return $json['results'][0]['formatted_address'];
    } else {
        return null;
    }
}
function fetchNearbyPlaces($lat, $lng, $type, $radius, $apiKey) {
	global $profile_id;
    $location = $lat . ',' . $lng;
    if ($type === 'entertainment') {
        // Nếu là loại giải trí, tìm nhiều loại
        $entertainmentTypes = ['park', 'playground', 'movie_theater'];
        $results = [];
        foreach ($entertainmentTypes as $entType) {
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" .
                   "location={$location}&radius={$radius}&type={$entType}&key={$apiKey}";
            $response = json_decode(file_get_contents($url), true);
            if (!empty($response['results'])) {
                $results = array_merge($results, $response['results']);
            }
        }
        return $results;
    }
	if ($type === 'market') {
        $marketTypes = ['supermarket', 'convenience_store', 'grocery_or_supermarket', 'shopping_mall'];
        $results = [];
        foreach ($marketTypes as $mType) {
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" .
                   "location={$location}&radius={$radius}&type={$mType}&key={$apiKey}";
            $response = json_decode(file_get_contents($url), true);
            if (!empty($response['results'])) {
                $results = array_merge($results, $response['results']);
            }
        }
        return $results;
    }
    // Trường hợp khác
    $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" .
           "location={$location}&radius={$radius}&type={$type}&key={$apiKey}";
    $response = json_decode(file_get_contents($url), true);
    return $response['results'] ?? [];
}
function project_document() {
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$title_page,$description_page,$profile_id;
	$clsTag = new Tag();
	$clsProjectMeta = new ProjectMeta();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$assign_list["clsTag"] = $clsTag;
	$assign_list["clsProjectMeta"] = $clsProjectMeta;
	if(isset($_POST["_search"]) && $_POST["_search"] == "_search") {
		$tag_id = (int) Input::post('tag_id',0);
		$keySearch= Input::post('keySearch',"");
		$cat_id = (int) Input::post('cat_id',0);
		$hasCond = false;
		$link = $clsISO->getLink("project_meta");
		if(!empty($keySearch)){
			$link .= ($hasCond?'&':'?') . 'keyword='.$keySearch;
			$hasCond = true;
		}
		if(!empty($cat_id)){
			$link .= ($hasCond?'&':'?') . 'cat_id='.$cat_id;
			$hasCond = true;
		}
	}
	$tag_id = Input::get('tag_id',0);
	$keySearch= Input::get('keySearch',"");
	$cat_id = (int) Input::get('cat_id',0);
	$project_id = (int) Input::get('project_id',0);
	$current_page = (int) Input::get('page',1);
	$per_page = (int) Input::get('per_page',30);
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$array_field_accept_query = ['page', 'cat_id', 'keyword', 'tag_id', 'project_id'];
	$allGet = Input::get();
	$query_use = [];
	if (!empty($allGet)) {
		foreach ($allGet as $field => $itemGet) {
			if (in_array($field, $array_field_accept_query)) {
				$query_use[$field] = $itemGet;
			}
		}
	}
	$arrTag = !empty($tag_id) ? explode(',', $tag_id) : [];
	$assign_list['arrTag'] = $arrTag;
	$assign_list['query_use'] = $query_use;
	$assign_list['current_page'] = $current_page;
	###
	$cond = "is_trash=0";
	if($show=='tag' && $tag_id > 0) $cond.= " and list_tag_id like '%|{$tag_id}|%'";
	$total_record = $clsProjectMeta->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$list_document = $clsProjectMeta->getAll("{$cond} GROUP BY cat_id", '*, COUNT(*) as total_document');
	$list_document = !empty($list_document) ? array_combine(array_map(function($query) {
		return $query[cat_id];
	},$list_document), array_values($list_document)) : [];
	## danh mujc tài liệu
	$condListCat = "`is_trash`=0";
	$condListCat.= " and `property_type`='_CATEGORY_DOCS'";
	$fieldListCat = "{$clsProperty->pkey},property_code,title, slug, parent_id";
	$arrListCat = $clsProperty->getAll($condListCat. " order by `order_no` ASC", $fieldListCat);
	$arrListCat = !empty($arrListCat) ? array_combine( array_column($arrListCat, 'property_id'), $arrListCat ) : [];
	$htmlCategory = "";
	$arrListCatTree = !empty($arrListCat) ? $clsProjectMeta->buildTree($arrListCat, 0, array('list_docs' => $list_document)) : [];
	// if ($profile_id == '1107') {
		// $result = $clsSetting->getCacheItems('_DOCS_FILE_TYPE');
		// echo "<pre>";print_r($result);die;
	// }
	$htmlCategory = !empty($arrListCatTree) ? $clsProjectMeta->renderMenu($arrListCatTree, false, $cat_id) : "";
	$assign_list['htmlCategory'] = $htmlCategory;
	$assign_list['arrListCat'] = $arrListCat;
	## tags
	$all_tags = [];
	$filter_tags = !empty($list_docs) ? array_column($list_docs, 'tags_slug', 'tags') : [];
	if (!empty($filter_tags)) {
		foreach ($filter_tags as $names => $slugs) {
			$nameArr = explode(',', $names);
			$slugArr = array_values(array_filter(explode('|', $slugs)));
			foreach ($slugArr as $index => $slug) {
				$all_tags[$slug] = $nameArr[$index] ?? '';
			}
		}
	}
	$assign_list["all_tags"] = $all_tags;
	// if ($profile_id == '1107') {
		// $file_get = file_get_contents('https://drive.google.com/uc?id=1SYWLzXgC7hyfPii2s4lNFoD9X1yUbm3R&export=download');
			// echo "<pre>";print_r($file_get);die;
	// }
	##
	$show = Input::get('show',"all");
	if($show=='tag'){
		$tag_id = (int) Input::get('tag_id',0);
	}else if($show=='detail'){
		$slide_id = (int) Input::get('slide_id',0);
		$oneSlide = $clsSlide->getOne($slide_id);
		$scriptJs = "";
		if($oneSlide['slide_type'] == 'url'){
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.$oneSlide['link'].'" data-fancybox data-type="iframe" title="Đọc nhanh"></a>';
		}else if($oneSlide['slide_type'] == 'upload'){
			if($clsSlide->isPdf(DOMAIN_URL.$oneSlide['link'])) {
				$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.DOMAIN_URL.$oneSlide['link'].'" data-fancybox data-type="iframe" title="Đọc nhanh"></a>';
			}else{
				$scriptJs.= '<a class="autoclick_'.$slide_id.'" target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src='.DOMAIN_URL.$oneSlide['link'].'" title="Đọc nhanh"></a>';
			}
		}else if($oneSlide['slide_type'] == 'post' || $oneSlide['slide_type'] == 'image') {
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" target="_blank" href="javascript:void()" onClick="$Core.slide.view(this, event)" slide_id="'.$slide_id.'" title="Đọc nhanh"></a>';
		}else if($oneSlide['slide_type'] == 'video') {
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.DOMAIN_URL.$oneSlide['link'].'" data-fancybox title="Xem nhanh"></a>';
		}
		$scriptJs.= '
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					//history.pushState("", "", "/hoc-tap.html");
					$(\'.autoclick_'.$slide_id.'\').trigger(\'click\');
				}, 500);
			})
		</script>';
		$assign_list["scriptJs"] = $scriptJs;
	}
	$assign_list["show"] = $show;
	$assign_list["tag_id"] = $tag_id;
	/*=============Title & Description Page==================*/
	$title_page = 'Kho tài liệu - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function project_list_document() {
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$title_page,$description_page,$profile_id;
	$clsTag = new Tag();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$assign_list["clsTag"] = $clsTag;
	$assign_list["clsProjectMeta"] = $clsProjectMeta;
	$tag_id = Input::post('tag_id',0);
	$keySearch= Input::post('keyword',"");
	$cat_id = (int) Input::post('cat_id',0);
	$project_id = (int) Input::post('project_id',0);
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',30);
	// if(isset($_POST["_search"]) && $_POST["_search"] == "_search") {
		// $tag_id = (int) Input::post('tag_id',0);
		// $keySearch= Input::post('keySearch',"");
		// $cat_id = (int) Input::post('cat_id',0);
		// $hasCond = false;
		// $link = $clsISO->getLink("project_meta");
		// if(!empty($keySearch)){
			// $link .= ($hasCond?'&':'?') . 'keyword='.$keySearch;
			// $hasCond = true;
		// }
		// if(!empty($cat_id)){
			// $link .= ($hasCond?'&':'?') . 'cat_id='.$cat_id;
			// $hasCond = true;
		// }
	// }
	###
	$cond = "is_trash=0";
	if(!empty($keySearch)) {
		$cond.= " and title like '%{$keySearch}%'";
	}
	if(!empty($cat_id)) {
		$cond.= " and (`cat_id`='{$cat_id}' or LOWER(`list_cat_id`) like LOWER('%|{$cat_id}|%'))";
	}
	if(!empty($project_id)) {
		$cond.= " and ".$clsProjectMeta->condByProject($project_id);
	}
	if(!empty($tag_id)) {
		$arrTag = explode(',', $tag_id);
		$arrConds = [];
		foreach($arrTag as $tagItem) {
			$arrConds[] .= " tags_slug like '%|{$tagItem}|%'";
		}
		$cond .= " AND (" . implode(" OR ", $arrConds) . ")";
	}
	$total_record = (int)$clsProjectMeta->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$list_document = $clsProjectMeta->getAll("{$cond} order by reg_date DESC".$limitCond);
	if(!empty($list_document)){
		$arr_profile_cached = $list_docs = array();
		foreach($list_document as $key => $val){
			$more_information = $clsISO->to_array_json($val['more_information']);
			$list_files = isset($more_information['list_files']) ? $more_information['list_files'] : array();
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
							'id' => $val["id"],
							'type' => $_type,
							'title' => $val['title'],
							'cat_id' => isset($val['cat_id']) ? $val['cat_id'] : null,
							'cat_name' => isset($val['cat_id']) && isset($arrListCat[$val['cat_id']]) ? $arrListCat[$val['cat_id']]['title'] : null,
							'list_images' => $list_images,
							'image' => $clsISO->genGoogleURL($mkey),
							'link' => $clsISO->genGoogleURL($mkey,($_type=='google.file'?'view':'preview')),
							'link_download' => $clsISO->genGoogleURL($more_information["gg_id"],"download"),
							'more_information' => array(
								'gg_id' => $mkey,
								'type' => $_type,
								'image' =>  $clsISO->genGoogleURL($mkey)
							)
						);
					}
				}else{
					$list_docs[] = array(
						'id' => $val["id"],
						'type' => "video",
						'title' => $val['title'],
						'cat_id' => isset($val['cat_id']) ? $val['cat_id'] : null,
						'cat_name' => isset($val['cat_id']) && isset($arrListCat[$val['cat_id']]) ? $arrListCat[$val['cat_id']]['title'] : null,
						'list_images' => [],
						'image' => $clsISO->genGoogleURL($more_information["gg_id"]),
						'link' => $clsISO->genGoogleURL($more_information["gg_id"],"preview"),
						'link_download' => $clsISO->genGoogleURL($more_information["gg_id"],"download"),									
						'more_information' => array(
							'gg_id' => $more_information["gg_id"],
							'type' => "video",
							'image' =>  $clsISO->genGoogleURL($more_information["gg_id"])
						)
					);
				}
			} else {
				$val['more_information'] = $more_information;
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
								$val['type'] = $_type;
								$val['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
							} else {
								$val['type'] = $more_information['type'];
								$val['link'] = $clsISO->genGoogleURL($nkey, 'view');
							}
							$val['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');
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
							$val['type'] = $_type;
							$val['link_download'] = $clsISO->genGoogleURL($nkey, 'download');
							$val['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));
						}
					}
				} else {
					$val['type'] = $more_information['type'];
					$val['link_download'] = $clsISO->getDownloadURL($val['content']);
					if($more_information['type'] == 'pdf'){
						$val['link'] = $clsISO->getIframeUrl($val['content']);
					} else {
						$val['link'] = $clsISO->getGoogleUrl($val['content']);
					}
				}
				$val['list_images'] = $list_images;
				$val['image'] = $more_information['image'];
				$val['cat_name'] = isset($val['cat_id']) && isset($arrListCat[$val['cat_id']]) ? $arrListCat[$val['cat_id']]['title'] : null;
				$list_docs[] = $val;
			}
			$user_id = $val['user_id'];
			$slide_type = $val['slide_type'];
			$link = $val['link'];
			if($slide_type == 'upload'){
				$link = str_replace(DOMAIN_URL, '', $link);
				$link = DOMAIN_URL. $link;
				$list_document[$key]['link'] = $link;
			}
			if(isset($arr_profile_cached[$user_id])){
				$list_document[$key]['oneProfile'] = $arr_profile_cached[$user_id];
			} else {
				$field = "full_name,first_name,last_name,avatar,department_id";
				$arr_profile_cached[$user_id] = $clsProfile->getOne($user_id, $field);
				$list_document[$key]['oneProfile'] = $arr_profile_cached[$user_id];
			}
		}
	}
	##
	$assign_list["list_document"] = $list_docs;
	##
	$show = Input::get('show',"all");
	if($show=='tag'){
		$tag_id = (int) Input::get('tag_id',0);
	}else if($show=='detail'){
		$slide_id = (int) Input::get('slide_id',0);
		$oneSlide = $clsSlide->getOne($slide_id);
		$scriptJs = "";
		if($oneSlide['slide_type'] == 'url'){
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.$oneSlide['link'].'" data-fancybox data-type="iframe" title="Đọc nhanh"></a>';
		}else if($oneSlide['slide_type'] == 'upload'){
			if($clsSlide->isPdf(DOMAIN_URL.$oneSlide['link'])) {
				$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.DOMAIN_URL.$oneSlide['link'].'" data-fancybox data-type="iframe" title="Đọc nhanh"></a>';
			}else{
				$scriptJs.= '<a class="autoclick_'.$slide_id.'" target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src='.DOMAIN_URL.$oneSlide['link'].'" title="Đọc nhanh"></a>';
			}
		}else if($oneSlide['slide_type'] == 'post' || $oneSlide['slide_type'] == 'image') {
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" target="_blank" href="javascript:void()" onClick="$Core.slide.view(this, event)" slide_id="'.$slide_id.'" title="Đọc nhanh"></a>';
		}else if($oneSlide['slide_type'] == 'video') {
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.DOMAIN_URL.$oneSlide['link'].'" data-fancybox title="Xem nhanh"></a>';
		}
		$scriptJs.= '
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					//history.pushState("", "", "/hoc-tap.html");
					$(\'.autoclick_'.$slide_id.'\').trigger(\'click\');
				}, 500);
			})
		</script>';
		$assign_list["scriptJs"] = $scriptJs;
	}
	$assign_list["show"] = $show;
	$assign_list["tag_id"] = $tag_id;
	$html = $core->build('project'.DS.'_ajax.list_docs.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'per_page' => $per_page
	));
}
function project_download_document() {
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$title_page,$description_page,$profile_id;
	$document_id = (int) Input::post('document_id');
	$list_images = [
		['gid' => '1SYWLzXgC7hyfPii2s4lNFoD9X1yUbm3R', 'type' => 'video'],
		['gid' => '1gT6-U4iJVNaf1WvSbbhrvdJQGP-jFdmQ', 'type' => 'video'],
		['gid' => '1oHcbQwOSldP0f7VULaslNq877JrKSnnp', 'type' => 'video'],
		['gid' => '18bATq1bj61QckQYSjprrn9gyn835KgX1', 'type' => 'video'],
		['gid' => '1QnAdo67BLglMA60nj-EAEIm0Cqd4yIfV', 'type' => 'video'],
		['gid' => '1s32fsLR5WPsuezOiZNnsge2IU9lf8gWC', 'type' => 'video'],
	];
	$tempDir = __DIR__ . '/tmp_downloads';
	if (!is_dir($tempDir)) {
		mkdir($tempDir, 0777, true);
	}
	print_r(__DIR__);die;
	foreach ($list_images as $file) {
		$fileId = $file['gid'];
		$savePath = $tempDir . '/' . $fileId . '.mp4'; // bạn có thể đổi phần mở rộng nếu biết chính xác
		echo "Đang tải file {$fileId}...\n";
		downloadFileFromGoogleDrive($fileId, $savePath);
	}
	// Tạo file zip chứa tất cả file vừa tải
	$zipName = '_files.zip';
	$zip = new ZipArchive();
	if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
		$files = scandir($tempDir);
		foreach ($files as $file) {
			if ($file !== '.' && $file !== '..') {
				$filePath = $tempDir . '/' . $file;
				$zip->addFile($filePath, $file);
			}
		}
		$zip->close();
		// Gửi file zip cho client download
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.$zipName.'"');
		header('Content-Length: ' . filesize($zipName));
		readfile($zipName);
		// Xóa file zip và các file tạm
		unlink($zipName);
		foreach ($files as $file) {
			if ($file !== '.' && $file !== '..') {
				unlink($tempDir . '/' . $file);
			}
		}
		rmdir($tempDir);
		exit;
	} else {
		echo "Không thể tạo file zip";
	}
}
function project_config_block(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$block_id = Input::post('block_id', 0);
	$oneBlock = $clsProperty->getOne($block_id, "`title`,`more_information`");
	$more_information = $clsProperty->getOneField('more_information', $block_id);
	$more_information = $clsISO->to_array_json($more_information);
	$crawl_configs = $core->get_field($more_information, "crawl_configs", []);
	#
	$arr_resource = array();
	$arr_resource['quycan'] = 'quycan.com';
	$arr_resource['masterisehomes'] = 'masterisehomes.id.vn';
	$arr_resource['mikgroup'] = 'mikgroup.id.vn';
	#
	$smarty->assign('block_id', $block_id);
	$smarty->assign('oneBlock', $oneBlock);
	$smarty->assign('arr_resource', $arr_resource);
	$smarty->assign('crawl_configs', $crawl_configs);
	// Return
	$html = $core->build('project'.DS.'_ajax.config_block.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function project_do_config_block(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsProperty = new Property();
	#
	$msg = "_error";
	$block_id = (int) Input::post('block_id', 0);
	if($block_id > 0){
		$more_information = $clsProperty->getOneField('more_information', $block_id);
		$more_information = $clsISO->to_array_json($more_information);
		// $clsISO->print_pre($more_information); die();
		$crawl_configs = Input::post('crawl_configs', []);
		$more_information['crawl_configs'] = $crawl_configs;
		if($clsProperty->updateOne($block_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function project_do_crawl(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$msg = "_error"; 
	$total_updated = 0;
	$uid = $clsISO->getUniqid();
	$resource = Input::post('resource');
	$block_id = (int) Input::post('block_id', 0);
	if($block_id > 0 && !empty($resource)){
		$more_information = $clsProperty->getOneField('more_information', $block_id);
		$more_information = $clsISO->to_array_json($more_information);
		$crawl_configs = $core->get_field($more_information, "crawl_configs", []);
		if(isset($crawl_configs[$resource])){
			if(!empty($crawl_configs[$resource]['url'])){
				$curl = new \Curl\Curl();
				$curl->get($crawl_configs[$resource]['url']);
				if(!$curl->error){
					$tlbData = toArray($curl->response);
					if(!empty($tlbData)){
						$arr_stocks = array();
						$field = "{$clsStock->pkey},`ms_code`,`more_information`";
						$tmp = $clsStock->getAll("`block_id`='{$block_id}' AND `agency_id`<>'"._AGENCY_FH_ID."' 
							AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'", $field);
						if(!empty($tmp)){
							foreach($tmp as $key => $val){
								$ms_code = $val['ms_code'];
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$price_sheets = $core->get_field($more_information, "price_sheets", []);
								if(!empty($price_sheets)){} else {
									$val['more_information'] = $more_information;
									$arr_stocks[$ms_code] = $val;
								}
							}
						}
						if(!empty($arr_stocks)){
							foreach($tlbData as $key => $val){
								$title_ptg = 'PTG TẠM TÍNH';
								if($resource == 'quycan'){
									$ms_code = trim($val['code']);
									$link_ptg = trim($val['price_list']);
									$TTS = trim($val['price_tts']);
									$TTTĐ = trim($val['price_tttd']);
									$Vay = trim($val['price_loan']);
									$total_price_vat = trim($val['price']);
								} else {
									$ms_code = trim($val['Mã căn']);
									$link_ptg = trim($val['Link PTG']);
									$TTS = trim($val['TTS']);
									$TTTĐ = trim($val['TTTĐ']);
									$Vay = trim($val['Vay']);
									$total_price_vat = trim($val['Tổng giá bán sau VAT và KPBT']);
								}
								if(!empty($ms_code) && !empty($link_ptg) && isset($arr_stocks[$ms_code])){
									$price_sheets = array();
									$price_sheets[$uid]['is_frontpage'] = 1;
									$price_sheets[$uid]['sheets'][$clsISO->getUniqid()] = array('title' => $title_ptg, 'image' => $link_ptg);
									$price_sheets[$uid]['reg_date'] = time();
									$price_sheets[$uid]['upd_date'] = time();
									$price_sheets[$uid]['user_id'] = $profile_id;
									$price_sheets[$uid]['user_update_id'] = $profile_id;
									#
									$more_information = $arr_stocks[$ms_code]['more_information'];
									if(!empty($TTS) && empty($more_information['total_price_early'])){
										$more_information['total_price_early'] = $TTS;
									}
									if(!empty($TTTĐ) && empty($more_information['total_price_bank'])){
										$more_information['total_price_progress'] = $TTTĐ;
									}
									if(!empty($Vay) && empty($more_information['total_price_bank'])){
										$more_information['total_price_bank'] = $Vay;
									}
									if(!empty($Vay) && empty($more_information['total_price_bank_half'])){
										$more_information['total_price_bank_half'] = $Vay;
									}
									if(!empty($total_price_vat) && empty($more_information['total_price_vat'])){
										$more_information['total_price_vat'] = $total_price_vat;
									}
									$more_information['hide_price_sheets'] = 0;
									$more_information['price_sheets'] = $price_sheets;
									if($clsStock->updateOne($arr_stocks[$ms_code][$clsStock->pkey], array(
										'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
									))){
										$total_updated += 1;
									}
								}
							}
						}
					}
				}
			}
		}
		$msg = ($total_updated > 0) ? '_success' : '_error';
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'total_updated' => $total_updated
	)); die();
}
function arr_aciteria(){
	global $clsISO;
	$arr_aciteria = [
		"total_price_vat"	=>	[
			"title"	=>	"Giá bán",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#ffeeee"
		],
		"price_m2"	=>	[
			"title"	=>	"Giá /m2",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#ffeeee"
		],
		"total_price_early"	=>	[
			"title"	=>	"Giá TTS",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#f4ffee"
		],
		"price_tts_m2"	=>	[
			"title"	=>	"TTS /m2",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#f4ffee"
		],
		"total_price_progress"	=>	[
			"title"	=>	"Giá TTTĐ",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#fffdee"
		],
		"price_tttd_m2"	=>	[
			"title"	=>	"Giá TTTĐ /m2",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#fffdee"
		],
		"total_price_bank"	=>	[
			"title"	=>	"Giá vay",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#f2eeff"
		],
		"price_vay_m2"	=>	[
			"title"	=>	"Giá vay /m2",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#f2eeff"
		],
		"total_price_bank_half"	=>	[
			"title"	=>	"Giá vay 50%",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#eeffff"
		],
		"price_vay50_m2"	=>	[
			"title"	=>	"Giá vay 50% /m2",
			"unit"	=>	"",
			"compare"	=>	"min",
			"bgcolor"	=>	"#eeffff"
		],
		"DT_TT"	=>	[
			"title"	=>	"DT thông thủy",
			"unit"	=>	"m<sup>2</sup>",
			"compare"	=>	"",
		],
//		"DT_Tim"	=>	[
//			"title"	=>	"DT tim tường",
//			"unit"	=>	"m<sup>2</sup>",
//			"compare"	=>	"",
//		],
		"home_direction"	=>	[
			"title"	=>	"Hướng",
			"unit"	=>	"",
			"compare"	=>	"",
		],
		"floor"	=>	[
			"title"	=>	"Tầng",
			"unit"	=>	"",
			"compare"	=>	"",
		],
		"bedroom_name"	=>	[
			"title"	=>	"Loại căn",
			"unit"	=>	"",
			"compare"	=>	"",
		],
		"status"	=>	[
			"title"	=>	"Tình trạng",
			"unit"	=>	"",
			"compare"	=>	"",
		],
		"type_name"	=>	[
			"title"	=>	"Loại hình",
			"unit"	=>	"",
			"compare"	=>	"",
		],
	];
	return $arr_aciteria;
}
function project_compare_stock(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$uid = $clsISO->getUniqid();
	$arr_aciteria = @arr_aciteria();
	
	foreach ($arr_aciteria as $key => $val) {
		$list = $val["list"];
		$min_value = min($list);
		$max_value = max($list);
		if($min_value > 0 && $max_value > 0) {
			if (count(array_keys($list, $min_value)) === 1) {
				$stock_id_min = array_search($min_value, $list);
				$arr_aciteria[$key]["stock_min"] = $stock_id_min;
			}
			if (count(array_keys($list, $max_value)) === 1) {
				$stock_id_max = array_search($max_value, $list);
				$arr_aciteria[$key]["stock_max"] = $stock_id_max;
			}
		}		
	}
	#
	$smarty->assign('arr_aciteria', $arr_aciteria);
	$smarty->assign('uid', $uid);
	// Return
	$html = $core->build('project'.DS.'_ajax.compare_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function project_load_compare_stock(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$toId = Input::post("toId","");
	$uid = $clsISO->getUniqid();
	$lstCompare = vnSessionExist('stock_compare') ? vnSessionGetVar('stock_compare') : [];
//	$clsISO->print_pre($lstCompare);die;
//	$lstCompare = [140242,140260];
	$lstStockCompare = $clsStock->getAll("`{$clsStock->pkey}` IN (".implode(',',$lstCompare).")");
	$arr_project_cache = $clsProject->getListProject();
	$arr_type_cache = $clsProperty->getArraySearchByKey("_TYPE");
	$arr_view_cache = $clsProperty->getArraySearchByKey("_VIEW");
	$arr_bedroom_cache = $clsProperty->getArraySearchByKey("_BEDROOM");
	$arr_direction_cache = $clsProperty->getArraySearchByKey("_DIRECTION");
	$arr_block_cache = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_building_cache = $clsProperty->getArraySearchByKey("_BUILDING");
	$arr_status_cache = $clsProperty->getArraySearchByKey("_STATUS");
	$arr_compare = [];
	$arr_aciteria = @arr_aciteria();
	foreach ($lstStockCompare as $key => $val) {
//		$clsISO->print_pre($val);die;
		$more_information = $clsISO->to_array_json($val["more_information"]);
		$type_id = !empty($more_information["type_id"]) ? $more_information["type_id"] : "";
		$view_id = !empty($view_id["view_id"]) ? $view_id["view_id"] : "";	
		$bedroom_id = !empty($val["bedroom_id"]) ? $val["bedroom_id"] : "";	
		$home_direction_id = !empty($val["home_direction_id"]) ? $val["home_direction_id"] : "";	
		if($val["stock_type"] == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$stock_code = $arr_project_cache[$val["project_id"]]["code"]." " . $val["ms_code"];
		}else{
			$stock_code = $arr_block_cache[$val["block_id"]]["property_code"]." " . $val["ms_code"];			
		}
		$price_m2 = $price_tts_m2 = $price_tttd_m2 = $price_vay_m2 = $price_vay50_m2 = 0;
		$DT_TT = !empty($more_information["DT_TT"]) ? $more_information["DT_TT"] : "";
		$total_price_vat = !empty($more_information["total_price_vat"]) ? $clsISO->processSmartNumber($more_information["total_price_vat"]) : 0;	
		$total_price_early = !empty($more_information["total_price_early"]) ? $clsISO->processSmartNumber($more_information["total_price_early"]) : "";
		$total_price_progress = !empty($more_information["total_price_progress"]) ? $clsISO->processSmartNumber($more_information["total_price_progress"]) : "";
		$total_price_bank = !empty($more_information["total_price_bank"]) ? $clsISO->processSmartNumber($more_information["total_price_bank"]) : "";
		$total_price_bank_half = !empty($more_information["total_price_bank_half"]) ? $clsISO->processSmartNumber($more_information["total_price_bank_half"]) : "";
		if(!empty($DT_TT) && $clsISO->convertToNumber($DT_TT) > 0) {
			$price_m2 = $clsISO->processSmartNumber($total_price_vat) / $clsISO->convertToNumber($DT_TT);
			$price_tts_m2 = $clsISO->processSmartNumber($total_price_early) / $clsISO->convertToNumber($DT_TT);
			$price_tttd_m2 = $clsISO->processSmartNumber($total_price_progress) / $clsISO->convertToNumber($DT_TT);
			$price_vay_m2 = $clsISO->processSmartNumber($total_price_bank) / $clsISO->convertToNumber($DT_TT);
			$price_vay50_m2 = $clsISO->processSmartNumber($total_price_bank_half) / $clsISO->convertToNumber($DT_TT);
		}
		
		$price_m2 = !empty($price_m2) ? $clsISO->shortNumber($price_m2) : "";
		$price_tts_m2 = !empty($price_tts_m2) ? $clsISO->shortNumber($price_tts_m2) : "";
		$price_tttd_m2 = !empty($price_tttd_m2) ? $clsISO->shortNumber($price_tttd_m2) : "";
		$price_vay_m2 = !empty($price_vay_m2) ? $clsISO->shortNumber($price_vay_m2) : "";
		$price_vay50_m2 = !empty($price_vay50_m2) ? $clsISO->shortNumber($price_vay50_m2) : "";
		if($clsISO->_DEV()){
			/*if($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$oneBuilding = $arr_building_cache[$val["building_id"]];
				$clsISO->print_pre($oneBuilding);die;
			}*/
		}
		$arr_compare[$val["stock_id"]] = [
			"stock_id"	=>	$val["stock_id"],
			"stock_code"	=>	$stock_code,
			"ms_code"	=>	$val["ms_code"],
			"project_name"	=>	!empty($arr_project_cache[$val["project_id"]]) ? $arr_project_cache[$val["project_id"]]["title"] : "",
			"block_name"	=>	!empty($arr_block_cache[$val["block_id"]]) ? $arr_block_cache[$val["block_id"]]["title"] : "",
			"status"	=>	!empty($arr_status_cache[$val["status_id"]]) ? '<span class="label" style="background:'.$arr_status_cache[$val["status_id"]]	["bgcolor"].'; color:'.$arr_status_cache[$val["status_id"]]["textcolor"].'">'.$arr_status_cache[$val["status_id"]]["title"].'</span>' : "",
			"type_name"	=>	!empty($arr_type_cache[$type_id]) ? '<span class="label" style="background:'.$arr_type_cache[$type_id]["bgcolor"].'; color:'.$arr_type_cache[$type_id]["textcolor"].'">'.$arr_type_cache[$type_id]["title"].'</span>' : "",
			"DT_TT"	=>	$DT_TT,
			"DT_Tim"	=>	!empty($more_information["DT_Tim"]) ? $more_information["DT_Tim"] : "",
			"csbh"	=>	!empty($more_information["csbh"]) ? $more_information["csbh"] : "",
			"total_price"	=>	!empty($more_information["total_price"]) ? $more_information["total_price"] : "",
			"total_price_vat"	=>	$clsISO->shortNumberV2($total_price_vat,3),
			"price_m2"	=>	$price_m2,
			"price_tts_m2"	=>	$price_tts_m2,
			"price_tttd_m2"	=>	$price_tttd_m2,
			"price_vay_m2"	=>	$price_vay_m2,
			"price_vay50_m2"	=>	$price_vay50_m2,
			"view_name"	=>	!empty($arr_view_cache[$view_id]) ? $arr_view_cache[$view_id]["title"] : "",
			"bedroom_name"	=>	!empty($arr_bedroom_cache[$bedroom_id]) ? $arr_bedroom_cache[$bedroom_id]["title"] : "",
			"home_direction"	=>	!empty($arr_direction_cache[$home_direction_id]) ? $clsProperty->getTitleQR($home_direction_id, $arr_direction_cache[$home_direction_id]) : "",
			"floor"	=>	!empty($val["floor"]) ? $val["floor"] : "",
			"total_price_early"	=>	!empty($total_price_early) ? $clsISO->shortNumberV2($total_price_early,3) : "",
			"total_price_progress"	=>	!empty($total_price_progress) ? $clsISO->shortNumberV2($total_price_progress,3) : "",
			"total_price_bank"	=>	!empty($total_price_bank) ? $clsISO->shortNumberV2($total_price_bank,3) : "",
			"total_price_bank_half"	=>	!empty($total_price_bank_half) ? $clsISO->shortNumberV2($total_price_bank_half,3) : "",
		];
		$arr_aciteria["total_price_vat"]["list"][$val["stock_id"]] = $total_price_vat;
		$arr_aciteria["price_m2"]["list"][$val["stock_id"]] = $price_m2;
		$arr_aciteria["price_tts_m2"]["list"][$val["stock_id"]] = $price_tts_m2;
		$arr_aciteria["price_tttd_m2"]["list"][$val["stock_id"]] = $price_tttd_m2;
		$arr_aciteria["price_vay_m2"]["list"][$val["stock_id"]] = $price_vay_m2;
		$arr_aciteria["price_vay50_m2"]["list"][$val["stock_id"]] = $price_vay50_m2;
		$arr_aciteria["total_price_early"]["list"][$val["stock_id"]] = $total_price_early;
		$arr_aciteria["total_price_progress"]["list"][$val["stock_id"]] = $total_price_progress;
		$arr_aciteria["total_price_bank"]["list"][$val["stock_id"]] = $total_price_bank;
		$arr_aciteria["total_price_bank_half"]["list"][$val["stock_id"]] = $total_price_bank_half;
	}
	
	foreach ($arr_aciteria as $key => $val) {
		$list = $val["list"];
		$min_value = min($list);
		$max_value = max($list);
		if($min_value > 0 && $max_value > 0) {
			if (count(array_keys($list, $min_value)) === 1) {
				$stock_id_min = array_search($min_value, $list);
				$arr_aciteria[$key]["stock_min"] = $stock_id_min;
			}
			if (count(array_keys($list, $max_value)) === 1) {
				$stock_id_max = array_search($max_value, $list);
				$arr_aciteria[$key]["stock_max"] = $stock_id_max;
			}
		}		
	}
	#
	$smarty->assign('toId', $toId);
	$smarty->assign('arr_aciteria', $arr_aciteria);
	$smarty->assign('arr_compare', $arr_compare);
	// Return
	$html = $core->build('project'.DS.'_ajax.load_compare_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total' => count($arr_compare),
	)); die();
}
function project_add_stock_compare(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$_tp = Input::post("_tp","modal");	
	$action = Input::post("action","open");	
	$toId = Input::post("toId","");	
	$html = "";
	$result = false;
	if($action == "open" && $_tp == "modal") {
		$lstProject = $clsProject->getListProject();
		$uid = $clsISO->getUniqid();
		$smarty->assign('toId', $toId);
		$smarty->assign('uid', $uid);
		$smarty->assign('lstProject', $lstProject);
		// Return
		$html = $core->build('project'.DS.'_ajax.add_stock_conpare.tpl');
		$result = true;
	}else {
		$stock_id = (int)Input::post("stock_id",0);
		$stock_compare = vnSessionExist('stock_compare') ? vnSessionGetVar('stock_compare') : [];
//		$clsISO->print_pre($stock_compare);die;
		if($action == "add"){
			if(!$clsISO->checkItemInArray($stock_id,$stock_compare)) {
				$stock_compare[] = $stock_id;
				$result = true;
			}			
		}else if($action == "delete") {
			$stock_compare = array_diff($stock_compare,[$stock_id]);
			$result = true;
		}
		$stock_compare = array_values(array_unique($stock_compare));
		vnSessionSetVar('stock_compare',$stock_compare);
	}
	echo json_encode(array(
		'uid' => $uid,
		'result' => $result,
		'html' => $html,
		'total' => count($stock_compare),
		'toId' => $toId,		
	)); die();
}
function project_delete_all_compare(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	vnSessionDelVar('stock_compare');
	echo 1;
}
function project_search_stock(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$uid = Input::post('uid');
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$keysearch = Input::post('keysearch');
	###
	$html_stock = "";
	$cond = "`is_trash`=0 AND `project_id`='{$project_id}' AND `status_id` > 0"; 
	if(!empty($block_id)) {
		$cond.= " AND `block_id`='{$block_id}'";
	}
	if(!empty($building_id)) {
		$cond.= " AND `building_id`='{$building_id}'";
	}
	if($clsISO->checkContainer($keysearch,'X','')){
		$key2search = str_replace('X', 'XX', $keysearch);
		$cond.= " AND (`ms_code` like '%".str_replace('X','_',$keysearch)."' 
			or `ms_code` like '%".str_replace('X','_',$key2search)."'
		)";			
	} else {
		$cond.= " AND `ms_code` like '%{$keysearch}%'";
	}
//	 $dbconn->debug = true;
	$field = "{$clsStock->pkey},`ms_code`"; 
	$list_stocks = $clsStock->getAll($cond." limit 0,20", $field);
//	 $clsISO->print_pre($list_stocks); die();
	if(!empty($list_stocks)){
		$html_stock .= '<ul class="list-unstyled">';
		foreach($list_stocks as $key => $val){
			$html_stock .= '<li>
				<a href="javascript:void(0);" onClick="$Core.global.compare.select_stock(this, event)" 
					stock_id="'.$val[$clsStock->pkey].'" uid="'.$uid.'">'.$val['ms_code'].'</a>
			</li>';
		}
		$html_stock .= '</ul>';
	} else {
		$html_stock = '';
	}
	// Return
	echo json_encode(array(
		'html' => $html_stock
	)); die();
}
function project_setBgSold(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$_type = Input::post("type","light");
	vnSessionSetVar('stock_bg_sold',$_type);
	echo 1;
}