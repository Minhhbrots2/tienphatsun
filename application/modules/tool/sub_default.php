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
function default_tool(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i; 
	}
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('list_preloaders', $list_preloaders);
	
	/*=============Title & Description Page==================*/
	$title_page = 'Tình trạng căn hộ - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_load_block(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	###
	$project_id = (int) Input::post('project_id', 0);
	$stock_type = (int) Input::post('stock_type', 0);
	$cond = "`property_type`='_BLOCK' AND `for_id`='{$project_id}'";
	if($stock_type > 0) {
		$cond.= " AND `parent_id`='{$stock_type}' AND JSON_EXTRACT(`more_information`,\"$.on_sale\")=1";
	}
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("{$cond} ORDER BY `order_no` DESC", $field);
	##
	$html_options = '';
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>'; 
		}
	}
	// Return
	echo $html_options; die();
}
function default_load_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$html_options = '';
	$list_block_ids = Input::post('list_block_ids');
	if(!empty($list_block_ids)){
		foreach($list_block_ids as $key => $block_id){
			$field = "{$clsProperty->pkey},title";
			$cond = "property_type='_BUILDING' and for_id='{$block_id}'";
			$list_buildings = $clsProperty->getAll($cond . " ORDER BY `order_no` ASC", $field);
			if(!empty($list_buildings)){
				$html_options.= '<optgroup label="'.$clsProperty->getTitle($block_id).'">';
				foreach($list_buildings as $okey => $oval){
					$html_options.= '<option value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
				}
				unset($list_buildings);
				$html_options.= '</optgroup>';
			}
		}
	}
	// Return
	echo $html_options; die();
}
function default_loadFundType(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$html_options = '';
	$list_building_ids = Input::post('list_building_ids',array());
	$is_fund_type = 0;
	if(!empty($list_building_ids)){
		foreach($list_building_ids as $key => $building_id){
			$field = "{$clsProperty->pkey},title,more_information";
			$cond = "`property_id` IN (".implode(',',$list_building_ids).")";
			$list_buildings = $clsProperty->getAll($cond, $field);
			if(!empty($list_buildings)){				
				foreach($list_buildings as $okey => $oval){
					$more_information = $clsISO->to_array_json($oval["more_information"]);
					if(!empty($more_information['floor_hierarchy'])) {
						$is_fund_type = 1;
						break;
					}
				}
			}
		}
	}
	// Return
	echo json_encode([
		"is_fund_type" =>	$is_fund_type
	]); die();
}
function default_do_search(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$deviceType,$clsProfile,$profile_id,$clsConfiguration;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProperty = new Property();
	###
	$permiss_view_stock_resource = $clsISO->checkPermission('view_stock_resource');
	$project_id = Input::post('project_id', 1);
	$blocks_ids = Input::post('blocks_ids');
	$building_ids = Input::post('building_ids');
	$bedroom_ids = Input::post('bedroom_ids');
	$direction_ids = Input::post('direction_ids');
	$agency_ids = Input::post('agency_ids');
	$sort_by = Input::post('sort_by', "asc");
	$axis_ids = Input::post('axis_ids');
	$type_ids = Input::post('type_ids');
	$price_min = Input::post('price_min');
	$price_max = Input::post('price_max');
	$area_min = Input::post('area_min');
	$area_max = Input::post('area_max');
	$floor_range = Input::post('floor_range');
	$fund_type = Input::post('fund_type',array());
	$holderG = Input::post('holderG','_search');
	$is_toggle_agency = (int) Input::post('is_toggle_agency', 0);
	$price_min = $clsISO->processSmartNumber($price_min);
	$price_max = $clsISO->processSmartNumber($price_max);
	$area_min = $clsISO->processSmartNumber($area_min);
	$area_max = $clsISO->processSmartNumber($area_max);
	#
	$_ss_search = array();
	$_ss_search['project_id'] = $project_id;
	$_ss_search['blocks_ids'] = $blocks_ids;
	$_ss_search['building_ids'] = $building_ids;
	$_ss_search['bedroom_ids'] = $bedroom_ids;
	$_ss_search['direction_ids'] = $direction_ids;
	$_ss_search['agency_ids'] = $agency_ids;
	$_ss_search['price_min'] = $price_min;
	$_ss_search['price_max'] = $price_max;
	$_ss_search['area_min'] = $area_min;
	$_ss_search['area_max'] = $area_max;
	$_ss_search['floor_range'] = $floor_range;
	$_ss_search['axis_ids'] = $axis_ids;
	$_ss_search['type_ids'] = $type_ids;
	$_ss_search['fund_type'] = $fund_type;
	vnSessionSetVar('_ss_search', $_ss_search);
	$cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
	if(!$clsISO->checkPermission('view_stock_globe')){
		$cond.= " and `show_website` like '%|user.fh|%'";
	}
	if($project_id > 0){
		$cond.= " and (`project_id`='{$project_id}')";
	}
	$arr_building = $clsProperty->getArraySearchByKey("_BUILDING");
	$cnd = $cond;
	if(!empty($blocks_ids)){
		if(!empty($building_ids)){
			$list_blocks_lowerfloors = array(); // Block thấp tầng
			foreach($blocks_ids as $block_id){
				if($clsProperty->getOneField('parent_id',$block_id)==_BLOCK_TYPE_LOWFLOOR_SALE){
					$list_blocks_lowerfloors[] = $block_id;
				}
			}
			if(!empty($list_blocks_lowerfloors)){
				$cond.= " and (`block_id` in (".implode(',',$list_blocks_lowerfloors).") 
				or `building_id` in (".implode(',',$building_ids)."))";
			} else {
				$cond.= " and `building_id` in (".implode(',',$building_ids).")";
			}
			if(!empty($fund_type) && count($fund_type) == 1) {
				$lstBuilding = $clsProperty->getAll("`{$clsProperty->pkey}` IN (".implode(',',$building_ids).")");
				if(!empty($lstBuilding)) { $i = 0;
					$cond .= " AND (";
					foreach ($lstBuilding as $k => $_oBuilding) {
						$more_information_building = $clsISO->to_array_json($_oBuilding['more_information']);
						$floor_hierarchy = !empty($more_information_building['floor_hierarchy']) ? $more_information_building['floor_hierarchy'] : array();
						if(in_array("0",$fund_type)) {
							if(!empty($floor_hierarchy)){
								$cond .= (($i > 0) ? " OR " : "") . " (`building_id` = '".$_oBuilding["property_id"]."' AND `floor` NOT IN (".implode(',',$floor_hierarchy)."))";
								++$i;
							}							
						}else{
							if(!empty($floor_hierarchy)){
								$cond .= (($i > 0) ? " OR " : "") . " (`building_id` = '".$_oBuilding["property_id"]."' AND `floor` IN (".implode(',',$floor_hierarchy)."))";
								++$i;
							}
						}
						unset($more_information_building);
					}
					$cond .= ")";
				}
			}
		} else {
			$cond.= " and `block_id` in (".implode(',', $blocks_ids).")";
		}
	}
	if(!empty($floor_range)){
		$list_floors = array();
		foreach($floor_range as $floor){
			$tmp = explode('-', $floor);
			for($i=$tmp[0]; $i<=$tmp[1]; $i++){
				if($i==4) {
					$list_floors[] = '5A';
					$list_floors[] = '05A';
				} else if($i==7){
					$list_floors[] = '8A';
					$list_floors[] = '08A';
				} else if($i==13){
					$list_floors[] = '12A';
				} else if($i==14){
					$list_floors[] = '15A';
				} else if($i==17){
					$list_floors[] = '18A';
				}
				$list_floors[] = $clsISO->parseNumber($i);
			}
		}
		$cond.= " and (";
		for($i=0; $i<count($list_floors); $i++){
			$cond.= ($i==0?'':' or ')."(`floor`='".$list_floors[$i]."')";
		}
		$cond.= ")";
	}
	if(!empty($bedroom_ids)){
		$cond.= " and `bedroom_id` in (".implode(',',$bedroom_ids).")";
	}
	if(!empty($direction_ids)){
		$cond.= " and `home_direction_id` in (".implode(',', $direction_ids).")";
	}
	if(!empty($agency_ids)){
		$cond.= " and `agency_id` in (".implode(',',$agency_ids).")";
	}
	if(!empty($type_ids)){
		$cond.= " and `type_id` in (".implode(',',$type_ids).")";
	}
	if(!empty($axis_ids)){
		$ii = 0;
		$cond.= " and (";
		foreach($axis_ids as $axis){
			$cond.= ($ii==0?'':' or ')."(`code`='{$axis}')";
			++$ii;
		}
		$cond.= ")";
	}
	//$cnd.= " and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."')";
	$cond.= " and `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."'";
	if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
		//$cnd.= " and `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."'";
		$cond.= " and `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."'";
	}
	$cond.= " and (`total_price_vat` between {$price_min} and {$price_max})";
	$cond.= " and (JSON_EXTRACT(`more_information`,\"$.DT_TT\")  between {$area_min} and {$area_max})";
	###
	$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
	$list_status = $clsProperty->getAllCache("property_type='_STATUS' order by `order_no` ASC", $field);
	$status_bgcolor_arrs = $status_textcolor_arrs = array();
	if(!empty($list_status)){
		foreach($list_status as $key => $val){
			$status_bgcolor_arrs[$val[$clsProperty->pkey]] = $val['bgcolor'];
			$status_textcolor_arrs[$val[$clsProperty->pkey]] = $val['textcolor'];
		}
	}
	###
	// echo $cond; die();
	$sort_type = ($sort_by == 'asc') ? 'ASC' : 'DESC';
	$field = "{$clsStock->pkey},`block_id`,`building_id`,`ms_code`,`status_id`,`bedroom_id`,`agency_id`";
	$field.=",`project_id`,`type_id`,`code`,`home_direction_id`,`more_information`,`floor`,`show_website`";
	$field.= ",IF(CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.total_price_early\")) AS UNSIGNED)>0,CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.total_price_early\")) AS UNSIGNED),`total_price_vat`) as `price_order_no`";
	/** Quỹ ẩn đại lý */
	if(!$clsISO->checkPermission('view_stock_hidden')){
		$is_null = false; $arr_hid_blocks = $arr_agency_VIN = array();
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
		$list_agency = $clsProperty->getCacheItems("_AGENCY");
		if(!empty($list_agency)){
			foreach($list_agency as $key => $val){
				$agency_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(isset($more_information['FH_stock_vin']) && (int) $more_information['FH_stock_vin']==1){
					$arr_agency_VIN[] = $agency_id;
				}
				foreach($arr_hid_blocks as $hid_block_id) {
					$hid_field = sprintf('FH_%s_%s', $agency_id, $hid_block_id);
					if(isset($more_information[$hid_field]) && (int) $more_information[$hid_field]==1){
						$is_null = true;
						${"arr_agency_".$hid_block_id}[] = $agency_id;
					}
				}
			}
		}
		$ag_fgield = "{$clsProperty->pkey}";
		$tmp = $clsProperty->getAll("`is_trash`=1 and `is_locked`=0 and `property_type`='_AGENCY'", $ag_fgield);
		if(!empty($tmp)){
			foreach($tmp as $okey => $oval){
				$arr_agency_VIN[] = $oval[$clsProperty->pkey];
				foreach($arr_hid_blocks as $hid_block_id) {
					$is_null = true;
					${"arr_agency_".$hid_block_id}[] = $oval[$clsProperty->pkey];
				}
			}
			unset($tmp);
		}
		if(!empty($arr_agency_VIN) || $is_null){
			$cond.= " AND (CASE ";
			if(!empty($arr_agency_VIN)){
				$cond.= " WHEN block_id NOT IN(".implode(',', $arr_hid_blocks).") 
					THEN `agency_id` not in(".implode(',', $arr_agency_VIN).")";
			}
			if(!empty($arr_hid_blocks)){
				foreach($arr_hid_blocks as $hid_block_id) {
					if(!empty(${"arr_agency_".$hid_block_id})){
						$cond.= " WHEN block_id='{$hid_block_id}' THEN 
							`agency_id` not in(".implode(',', ${"arr_agency_".$hid_block_id}).")";
					}
				}
			}
			$cond.= " ELSE TRUE END)";
		}
	}
	/** End */
	$html = ""; $total_record = 0;
	$total_record = $clsStock->countItem($cond);
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page', 30);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	if($sort_by == 'desc'){
		$order_by = " ORDER BY `price_order_no` DESC";
	} else {
		$order_by = " ORDER BY `price_order_no` ASC";
	}
	###
	// $dbconn->debug = true;
	if($current_page > 1){
		$list_stocks = $clsStock->getAll("{$cond} and `agency_id`<>'"._AGENCY_FH_ID."'".$order_by.$limitCond, $field);
	} else {
		$list_stocks = array();
		$list_stocks_in_fh = $clsStock->getAll("{$cond} and `agency_id`='"._AGENCY_FH_ID."'".$order_by, $field);
		$list_stocks_notin_fh = $clsStock->getAll("{$cond} and `agency_id`<>'"._AGENCY_FH_ID."'".$order_by.$limitCond, $field);
		if(!empty($list_stocks_in_fh)){
			foreach($list_stocks_in_fh as $key => $val){
				$list_stocks[] = $val;
			}
			unset($list_stocks_in_fh);
		}
		if(!empty($list_stocks_notin_fh)){
			foreach($list_stocks_notin_fh as $key => $val){
				$list_stocks[] = $val;
			}
			unset($list_stocks_notin_fh);
		}
	}
	if(!empty($list_stocks)){
		$arr_qr_cached = $arr_property_cached = $arr_procode_cached = $arr_blocktype_cached = $arr_policy_cached = array();
		foreach($list_stocks as $key => $val){
			$show_website = $val['show_website'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_stocks[$key]['more_information'] = $more_information;
			$total_price_vat = $core->get_field($more_information, 'total_price_vat', 0);
			$total_price_early = $core->get_field($more_information, 'total_price_early', 0);
			$total_price_progress = $core->get_field($more_information, 'total_price_progress', 0);
			$total_price_bank = $core->get_field($more_information, 'total_price_bank', 0);
			$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
			$total_price_early = $clsISO->processSmartNumber($total_price_early);
			$total_price_progress = $clsISO->processSmartNumber($total_price_progress);
			$total_price_bank = $clsISO->processSmartNumber($total_price_bank);
			$price_sheets = $core->get_field($more_information, 'price_sheets', []);
			###
			$is_price_early = 0;
			if(!empty($total_price_early)){
//				$is_price_early = 1;
//				$total_price_vat = $total_price_early;
			}
			###
			$type_id= ($val['type_id'] > 0) ? $val['type_id'] : $more_information['type_id'];
			$stock_id= $val['stock_id'];
			$project_id= $val['project_id'];
			$block_id = $val['block_id'];
			$agency_id = $val['agency_id'];
			$building_id = $val['building_id'];
			$status_id = $val['status_id'];
			$bedroom_id = $val['bedroom_id'];
			$home_direction_id = $val['home_direction_id'];
			$oneTemplate = array();
			$oBuilding = $clsProperty->getOne($building_id,"more_information");
			$building_information = $oBuilding["more_information"];
			$building_information = $clsISO->to_array_json($building_information);
			if(isset($arr_template_cached[$building_id])){
				$oneTemplate = $arr_template_cached[$building_id];
			} else {
				$list_templates = $core->get_field($building_information, "template", []);
				if(!empty($list_templates)){
					foreach($list_templates as $okey => $oval){
						if($val['code'] == $oval['code']){
							$oneTemplate = $oval;
							break;
						}
					}
				}
				$arr_template_cached[$building_id] = $oneTemplate;
			}
			if($home_direction_id == 0 && (int) $oneTemplate['home_direction_id'] > 0){
				$home_direction_id = (int) $oneTemplate['home_direction_id'];
				if($home_direction_id == 0 && !empty($more_information['home_direction_id'])){
					$home_direction_id = (int) $more_information['home_direction_id'];
				}
			}
			if(!isset($arr_procode_cached[$status_id])){
				$arr_procode_cached[$status_id] = $clsProperty->getOneField('property_code',$status_id);
			}
			if(!isset($arr_property_cached[$status_id])){
				$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
			}
			if(!isset($arr_property_cached[$bedroom_id])){
				$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
			}
			if(!isset($arr_property_cached[$type_id])){
				$arr_property_cached[$type_id] = $clsProperty->getTitle($type_id);
			}	
			$is_fund_type = 0;
			$floor_hierarchy = $core->get_field($building_information, "floor_hierarchy", []);
			if(!empty($floor_hierarchy) && in_array($val['floor'],$floor_hierarchy)) {
				$is_fund_type = 1;
			}
			if(!isset($arr_property_cached[$agency_id])){
				$arr_property_cached[$agency_id] = $clsProperty->getTitleQR($agency_id);
			}
			if($deviceType=='phone'){
				if(!isset($arr_property_cached[$home_direction_id])){
					$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
				}
			} else {
				if(!isset($arr_qr_cached[$home_direction_id])){
					$arr_qr_cached[$home_direction_id] = $clsProperty->getTitleQR($home_direction_id);
				}
			}
			$bg_color = $status_bgcolor_arrs[$status_id];
			$text_color = $status_textcolor_arrs[$status_id];
			#- Giá/m2
			$DT_TT = $more_information['DT_TT'];
			$price_m2 = $total_price_vat/$clsISO->convertToNumber($DT_TT);
			#
			$html_price_sheets = $html_sales_policy = "";
			if(isset($arr_blocktype_cached[$block_id])){
				$blocktype_id = $arr_blocktype_cached[$block_id];
			} else {
				$blocktype_id = $clsProperty->getOneField('parent_id', $block_id);
				$arr_blocktype_cached[$block_id] = $blocktype_id;
			}
			if(!empty($price_sheets)){ $ii = 0;
				$price_sheets = @array_reverse($price_sheets);
				$oneSheet = @reset($price_sheets);
				$html_price_sheets = '<div class="d-flex align-items-center justify-content-center gap-1">';
				$html_more_price_sheets = ""; $total_more_price_sheets = 0;
				foreach($oneSheet['sheets'] as $okey => $oval){
					if($ii==0){
						$html_price_sheets.= '<a target="_blank" href="'.$oval['image'].'" class="btn btn-sm btn-outline-default">
						'.$clsISO->makeIcon('bx-link-external fs-12', 'PTG').'</a>';
					} else {
						$total_more_price_sheets += 1;
						$html_more_price_sheets.= '<div class=\'d-flex\'>
							<a target=\'_blank\' href=\''.$oval['image'].'\' class=\'btn btn-sm btn-outline-default\'>
								<i class=\'bx bx-link-external fs-12\'></i> PTG
							</a>
						</div>';
					}
					++$ii;
				}
				
				if(!empty($html_more_price_sheets)){
					$html_price_sheets.= '<a href="javascript:void(0);" class="text-upper fs-12" data-toggle="webui-popover" data-width="100" data-trigger="click" data-content="'.$html_more_price_sheets.'">(+'.$total_more_price_sheets.')</a>';
				}	
				$html_price_sheets.= '</div>';	
			}
			if($blocktype_id == _BLOCK_TYPE_LOWFLOOR_SALE){
				$regex = sprintf('%s_%s', $project_id, $block_id);
			} else {
				$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
			}
			if(isset($arr_policy_cached[$regex])){
				$onePolicy = $arr_policy_cached[$regex];
			} else {
				$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' 
					and `scope_slash` like '%|{$regex}|%' order by ms_date DESC limit 0,1");
			}
			if(!empty($onePolicy)){
				$html_sales_policy = '<a class="btn btn-sm btn-outline-default" target="_blank" href="'.$onePolicy['link_ns'].'">
					'.$clsISO->makeIcon('bx-link-external fs-12','CSBH').'</a>';
				if(empty($html_price_sheets)){
					$html_price_sheets = '<a class="text-upper text-main fs-12" target="_blank" href="'.$onePolicy['link_ms'].'">
						'.$clsISO->makeIcon('bx-link-external fs-12','PTG').'</a>';
				}
			}
			#- PTG IMG
			$img_price_sheet = 0;
			$price_sheets = isset($more_information['price_sheets']) && !empty($more_information['price_sheets']) 
				? $more_information['price_sheets'] : array();
			if(!empty($price_sheets)){
				$img_price_sheet = 1;
			}
			$liked = $clsProfile->checkInWishlist($stock_id);
			$html_fund_type = '';
			if($clsStock->checkStockFundType($stock_id,$building_id,$val,$oBuilding)) {				
				$html_fund_type = '<span class="text_fund_type text_fund_type_tool" title="Thứ cấp"></span>';
			}
			$html.= '<tr>
				'.($deviceType != 'phone' ? '<td class="text-center"><a href="javascript:void(0)" onClick="$Core.helper.toggle_wishlist(this,event)" stock_id="'.$stock_id.'" class="btn btn-sm'.($liked?' saved':'').' btn-default p-1 wishlist_'.$stock_id.'">'.$clsISO->makeIcon('bx-heart fs-11').'</a></td>': '').'
				<td class="p-0">
					<div class="position-relative" style="padding: 0.325rem 0.325rem">
					<a href="javascript:void(0);"'.($holderG=='_liked'?' onClick="$Core.tool.stock_view(this,event)" stock_id="'.$stock_id.'"':' data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id='.$stock_id.'" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350"').'>'.$val['ms_code'].'</a>'.$html_fund_type.($deviceType=='phone'?'<span class="label ml-1" style="color:'.$text_color.'; background:'.$bg_color.'">'.$arr_procode_cached[$status_id].'</span>':'').($permiss_view_stock_resource==1? ' <span class="label-agency badge '.($is_toggle_agency==1?' d-none':'').' fs-11 bg-label-secondary'.($clsStock->checkShow($show_website, 'MOC') ? '' : ' text-decoration-line-through').'">'.$clsISO->truncate($arr_property_cached[$agency_id],2,'').'</span>' : '').'</div></td>
				'.($deviceType=='phone'?'':'<td style="color:'.$text_color.'; background:'.$bg_color.'">'.$arr_property_cached[$status_id].'</td>').'
				<td class="text-left">'.(!empty($total_price_vat) ? sprintf('%s tỷ', $clsISO->priceFormatV2($total_price_vat,3)).($is_price_early?'(TTS)':''):'Check admin').'</td>
				<td class="text-left">'.(!empty($total_price_early) ? sprintf('%s tỷ', $clsISO->priceFormatV2($total_price_early,3)):'Check admin').'</td>
				<td class="text-left">'.(!empty($total_price_progress) ? sprintf('%s tỷ', $clsISO->priceFormatV2($total_price_progress,3)):'Check admin').'</td>
				<td class="text-left">'.(!empty($total_price_bank) ? sprintf('%s tỷ', $clsISO->priceFormatV2($total_price_bank,3)):'Check admin').'</td>
				'.($deviceType=='phone'?'':'<td class="text-left">'.(!empty($price_m2) ? '~ '.$clsISO->priceFormatV2($price_m2,1).' tr' : 'Check admin').'</td>').'
				<td class="text-center">'.$more_information['DT_TT'].'m<sup>2</sup></td>
				<td class="text-left">'.$arr_property_cached[$bedroom_id].'</td>
				'.($deviceType=='phone'?'':'<td class="text-left">'.($clsStock->checkStockFundType($stock_id,$building_id,$val,$oBuilding) ? "<span class='text-success'>Thứ cấp</span>" : $arr_property_cached[$type_id]).'</td>').'
				<td class="text-left">'.($deviceType=='phone'?$arr_property_cached[$home_direction_id]:$arr_qr_cached[$home_direction_id]).'</td>
				<td class="text-center">'.$html_price_sheets.'</td>
				<td class="text-center">'.$html_sales_policy.'</td>
			</tr>';
		}
	} else if($current_page == 1) {
		$html.= '<tr class="nohover">
			<td class="text-center" colspan="12">
				<img src="'.URL_IMAGES.'/listing-empty.svg" class="w-px-150" />
				<p clas="text-muted">Không có căn hộ nào phù hợp</p>
			</td>
		</tr>';
	}
	// var_dump($arr_qr_cached); die();
	// Return
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_record' => $total_record
	)); die();
}
function get_files($service, $folderId, $path = '') {
	$resultArray = [];
	$results = $service->files->listFiles([
		'orderBy' => "name",
		'q' => "'".$folderId."' in parents",
		'corpora' => "allDrives",
		'supportsAllDrives' => 'true',
		'includeItemsFromAllDrives' => 'true'
	]);
	foreach ($results->getFiles() as $file) {
		$filePath = $path . '/' . $file->getName();
		$resultArray[$file->getId()] = $filePath;
		if ($file->mimeType == 'application/vnd.google-apps.folder') {
			$resultArray = array_merge($resultArray, get_files($service, $file->getId(), $filePath));
		} 
	} 
	return $resultArray;
}
function default_favourite(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$clsProfile,$deviceType,$loggedIn,$project_id,$building_id,$ms_code;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$clsRequestPTG = new RequestPTG();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsRequestPTG', $clsRequestPTG);
	#
	$show = Input::get('show','list');
	$smarty->assign('show', $show);
	if($show=='detail'){
		$ms_code = Input::get('ms_code');
		$smarty->assign('ms_code', $ms_code);
		$oneStock = $clsStock->getByCond("`ms_code`='{$ms_code}'");
		$stock_id = !empty($oneStock) ? $oneStock[$clsStock->pkey] : 0;
		$oneMeta = $clsStockMeta->getByCond("stock_id='{$stock_id}' and profile_id='{$profile_id}'");
		if(!empty($oneMeta)){
			$stock_meta_id = $oneMeta[$clsStockMeta->pkey];
		} else {
			$stock_meta_id = $clsStockMeta->getMaxId();
			$clsStockMeta->insert(array(
				$clsStockMeta->pkey => $stock_meta_id,
				'stock_id' => $stock_id,
				'profile_id' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			));
			$oneMeta = $clsStockMeta->getOne($stock_meta_id);
		}
		$field = "{$clsStockMeta->pkey},content,profile_id";
		$list_stocks_meta = $clsStockMeta->getAll("stock_id='{$stock_id}' and content<>''");
		if(!empty($list_stocks_meta)){
			foreach($list_stocks_meta as $key => $val){
				$list_stocks_meta[$key]['author'] = $clsProfile->getFullName($val['profile_id']);
			}
		}
		$smarty->assign('list_stocks_meta', $list_stocks_meta);
		###
		$type_id = $oneStock['type_id'];
		$status_id = $oneStock['status_id'];
		$stock_type = $oneStock['stock_type'];
		$project_id = $oneStock['project_id'];
		$block_id 	= $oneStock['block_id'];
		$building_id = $oneStock['building_id'];
		$agency_id 	= $oneStock['agency_id'];
		$more_information = $oneStock['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$list_attributes = isset($more_information['properties']) && !empty($more_information['properties']) 
			? $more_information['properties'] : array();
		$price_sheets = isset($more_information['price_sheets']) && !empty($more_information['price_sheets']) 
			? $more_information['price_sheets'] : array();
		
		$oneProject = $clsProject->getOne($project_id);
		$assign_list['project_id'] = $project_id;
		$assign_list['oneProject'] = $oneProject;
		$assign_list['stock_type'] = $stock_type;
		###
		$oneTemplate = array();
		$oneBuilding = $clsProperty->getOne($building_id, "title,more_information,intro,for_id");
		$building_information = $oneBuilding['more_information'];
		$building_information = $clsISO->to_array_json($building_information);
		$assign_list['oneBuilding'] = $oneBuilding;
		$smarty->assign('block_id', $block_id);
		$smarty->assign('building_id', $building_id);
		###
		$block_information = $clsProperty->getOneField('more_information', $block_id);
		$block_information = $clsISO->to_array_json($block_information);
		$vr_link = isset($block_information['vr_link']) && !empty($block_information['vr_link']) 
			? $block_information['vr_link'] : "";			
		###
		$list_help_links = $clsProjectMeta->getAll("((`type`='building' and `building_ids` LIKE '%|{$building_id}|%') 
		or (`type`='block' and `block_ids` LIKE '%|{$block_id}|%')) order by type ASC, `order_no` ASC");
		if(!empty($list_help_links)){
			foreach($list_help_links as $key => $val){
				$is_driver = $clsISO->checkContainer($val['content'],"drive.google.com","") ? 1 : 0;
				$list_help_links[$key]['is_driver'] = $is_driver;
				$list_help_links[$key]['link'] = $val['content'];
			}
		}
		$list_templates = isset($building_information['template']) && !empty($building_information['template']) 
			? $building_information['template'] : array();
		if(!empty($list_templates)){
			foreach($list_templates as $key => $val){
				if($oneStock['code'] == $val['code']){
					$oneTemplate = $val;
					break;
				}
			}
		}
		// Ghi đè layout nếu có
		if(isset($more_information['layout']) && !empty($more_information['layout'])){
			$oneTemplate['layout'] = $more_information['layout'];
		}
		if(isset($more_information['layout_ns']) && !empty($more_information['layout_ns'])){
			$oneTemplate['layout_ns'] = $more_information['layout_ns'];
		}
		$DT_TT = isset($more_information['DT_TT']) && !empty($more_information['DT_TT']) 
			? $more_information['DT_TT'] : $oneTemplate['DT_TT'];
		$DT_Tim = isset($more_information['DT_Tim']) && !empty($more_information['DT_Tim']) 
			? $more_information['DT_Tim'] : $oneTemplate['DT_Tim'];
		$total_price_vat = !empty($more_information['total_price_vat']) 
			? $clsISO->processSmartNumber($more_information['total_price_vat']) : 0;
		###
		$bedroom_id = $oneStock['bedroom_id'];
		$home_direction_id = $oneStock['home_direction_id'];
		if($bedroom_id == 0 && isset($oneTemplate['bedroom_id']) && !empty($oneTemplate['bedroom_id'])){
			$bedroom_id = $oneTemplate['bedroom_id'];
		}
		if($home_direction_id == 0 && isset($oneTemplate['home_direction_id']) && !empty($oneTemplate['home_direction_id'])){
			$home_direction_id = $oneTemplate['home_direction_id'];
		}
		$smarty->assign('vr_link', $vr_link);
		$smarty->assign('oneTemplate', $oneTemplate);
		$smarty->assign('list_help_links', $list_help_links);
		$smarty->assign('more_information', $more_information);
		$has_price_early = $total_price_early = 0;
		$html_image_price_sheets = $html_price_sheets = $html_agency = $html_price_more = "";
		if(!empty($price_sheets)){
			$price_sheets = @array_reverse($price_sheets);
			$oneSheet = @reset($price_sheets);
			$img_price_sheets.= '<fieldset class="radius-3 ixZrPaFqgl my-2 bg-main">
				<legend class="fs-14 text-center m-0 px-1 bg-main text-white">
					Phiếu tính giá ('.$clsISO->convertTimeToText($oneSheet['upd_date']).')
					<a href="javascript:;" data-link="'.PCMS_URL.$clsStock->getLink($ms_code, 'PTG').'" onClick="$Core.util.copyToClipboard(this, event)" class="btn btn-xs bg-white text-main btn-icon btn-outline-default"><i class=\'bx bx-copy\'></i></a>
				</legend>
				<div class="d-flex justify-content-center gap-2 position-relative p-2">';
				foreach($oneSheet['sheets'] as $val){
					$img_price_sheets.= '<div class="flex-fill text-center">
						<a class="text_ptg" target="_blank" href="'.$val['image'].'" download>'.$val['title'].'</a>
					</div>';
				}
				$img_price_sheets.= '</div>
			</fieldset>';
			$html_image_price_sheets = '<div class="form-row">';
			foreach($oneSheet['sheets'] as $val){
				$image = $val['image'];
				if(!empty($image)){
					if( $clsISO->checkContainer($image,"drive.google.com","")){
						if(preg_match('/folders/',$image)){
							$folder_id = $clsISO->getGoogleId($image);
							/** Load API */
							require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
							/** Init Client */
							$client = new Google_Client();
							$client->setClientId(GOOGLE_CLIENT_ID);
							$client->setClientSecret(GOOGLE_CLIENT_SECRET);
							$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
							$client->setScopes(Google_Service_Drive::DRIVE);
							$service = new Google_Service_Drive($client);
							$list_files = @get_files($service, $folder_id);
							if(!empty($list_files)){
								foreach($list_files as $file_id => $file_name){
									$html_image_price_sheets.= '<div class="col-12 col-md-6">
										<div class="border p-1 radius-3 mb-2">
											<div class="mb-2 radius-3">
												<a href="'.$clsISO->genGoogleURL($file_id).'" data-fancybox>
													<img class="img-fluid radius-3" src="'.$clsISO->genGoogleURL($file_id).'" />
												</a>
											</div>
											<p class="fDIfvHwvwr">'.$val['title'].'</p>
										</div>
									</div>';
								}
							}
						} else {
							$image = $clsISO->getGoogleUrl($image);
							$html_image_price_sheets.= '<div class="col-12 col-md-6">
								<div class="border p-1 radius-3 mb-2">
									<div class="mb-2 radius-3">
										<a href="'.$clsISO->trim_space($image).'" data-fancybox>
											<img class="img-fluid radius-3" src="'.$clsISO->trim_space($image).'" />
										</a>
									</div>
									<p class="fDIfvHwvwr">'.$val['title'].'</p>
								</div>
							</div>';
						}
					} else {
						if( $clsISO->checkContainer($image,"/images/","")){
							$html_image_price_sheets.= '<div class="col-12 col-md-6">
								<div class="border p-1 radius-3 mb-2">
									<div class="mb-2 radius-3">
										<a href="'.$clsISO->trim_space($image).'" data-fancybox>
											<img class="img-fluid radius-3" src="'.$clsISO->trim_space($image).'" />
										</a>
									</div>
									<p class="fDIfvHwvwr">'.$val['title'].'</p>
								</div>
							</div>';
						} else {
							$html_image_price_sheets.= '<div class="col-12">
								<div class="border p-3 radius-3 mb-0">
									<a class="text-link fs-6" href="'.$clsISO->trim_space($image).'">'.$val['title'].'</a>
								</div>
							</div>';
						}
					}
				}
			}
			$html_image_price_sheets .= '</div>';
		}
		$smarty->assign('html_image_price_sheets', $html_image_price_sheets);
		if($oneStock['stock_type'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$price_field_configs = $clsProperty->getFieldValue($block_id, 'price_field_configs');
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
				if($total_cols > 0){ $ii= 0;
					$html_price_more.= '<hr class="my-2" />
						<div class="form-row position-relative">';
					foreach($price_field_configs as $key => $val){
						if(isset($val['status']) && $val['status']==1 
							&& isset($more_information[$key]) && !empty($more_information[$key])){
							$price = isset($more_information[$key]) ? $clsISO->processSmartNumber($more_information[$key]) : 0;
							$offset = ($total_cols==4 ? '2' : '3');
							$html_price_more.= '<div class="text-center col-xxl-2 col-'.($total_cols==3?'2':'3').
							($ii==0?' offset-xxl-'.$offset:'').'">
								<p class="mb-0">'.$val['title'].'</p>
								<div class="text-main fs-16 fw-bold position-relative">'
									.$clsISO->priceFormatV2($price,3).'tỷ
								</div>
							</div>';
							++$ii;
						}
					}
					$html_price_more.= '</div>
					<hr class="my-3" />';
				}
			}
		} else {
			$price_field_configs = array(
				'total_price_early' => 'Giá TTS',
				'total_price_progress' => 'Giá TTTĐ',
				'total_price_bank_12' => 'Vay 12T',
				'total_price_bank' => 'Vay 24T',
				'total_price_bank_36' => 'Vay 36T'
			);
			$total_cols = 0;
			foreach($price_field_configs as $key => $val){
				if(isset($more_information[$key]) && !empty($more_information[$key])){
					$total_cols += 1;
				}
			}
			$html_price_more.= '<hr class="my-2" />
				<div class="form-row position-relative">';
			foreach($price_field_configs as $key => $title){ $ii=0;
				if(isset($more_information[$key]) && !empty($more_information[$key])){
					$price = isset($more_information[$key]) ? $clsISO->processSmartNumber($more_information[$key]) : 0;
					$offset = ($total_cols==4 ? '2' : '3');
					$html_price_more.= '<div class="text-center col-xl-2 col-'.($total_cols==3?'4':'3').($key=='total_price_early'?' offset-xxl-'.$offset:'').'">
						<p class="mb-0">'.$title.'</p>
						<div class="text-main fs-14 fw-bold">'.$clsISO->priceFormatV2($price,3).' tỷ</div>
					</div>';
					++$ii;
				}
			}
			$html_price_more.= '</div>';
			if(empty($total_price_vat) && !empty($more_information['total_price_early'])){
				$has_price_early = 1;
				$total_price_vat = $clsISO->processSmartNumber($more_information['total_price_early']);
			}
		}
		$stock_posters = isset($more_information['stock_poster']) 
			? $more_information['stock_poster'] : array();
		if(!empty($stock_posters)){
			$html_links.= '<li>
				<i class="material-icons-outlined" style="color:#696cff">image</i> POSTER: ';
			foreach($stock_posters as $okey => $oval){
				$html_links.= '<a class="btn btn-xs rounded-pill btn-outline-default mr-1" data-fancybox data-preload="true" href="'.$clsISO->getGoogleUrl($oval['image']).'">'.$oval['title'].'</a>';
			}
			$html_links.= '</li>';
		}
		$stock_poster_video = isset($more_information['stock_poster_video']) 
			? $more_information['stock_poster_video'] : array();
		if(!empty($stock_poster_video)){
			$html_links.= '<li>
				<i class="material-icons-outlined" style="color:#696cff">video_camera_back</i> VIDEO: ';
			foreach($stock_poster_video as $okey => $oval){
				$html_links.= '<a class="btn btn-xs rounded-pill btn-outline-primary mr-1" target="_blank" href="'.$oval['video'].'">'.$oval['title'].'</a>';
			}
			$html_links.= '</li>';
		}
		if(!empty($oneTemplate) && isset($oneTemplate['layout']) && !empty($oneTemplate['layout'])){
			$html_links.='<li>
				<a target="_blank" href="'.$oneTemplate['layout'].'">
					<i class="material-icons-outlined">satellite</i> Layout mặt sàn</a></li>';	
		}
		if(!empty($oneTemplate) && isset($oneTemplate['layout_ns']) && !empty($oneTemplate['layout_ns'])){
			if($clsISO->checkPermission('view_stock_advanced')){
				$html_links.='<li>
					<a target="_blank" href="'.$oneTemplate['layout_ns'].'">
						<i class="material-icons-outlined">flip_to_front</i> Layout chi tiết căn hộ</a>
				</li>';
			}
		}
		if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
			$bedroom_information = $clsProperty->getOneField('more_information', $bedroom_id);
			$bedroom_information = $clsISO->to_array_json($bedroom_information);
			$folder_interior_ns = isset($bedroom_information['folder_interior_ns']) ? $bedroom_information['folder_interior_ns'] : array();
			if(!empty($folder_interior_ns)){ $ii = 0;
				$html_links.= '<li><i class="material-icons-outlined">image</i> NỘI THẤT: ';
				foreach($folder_interior_ns as $pkey => $pval){
					if(!empty($pval['title']) && !empty($pval['link'])){
						$gg_id = $clsISO->getGoogleId($pval['link']);
						$html_links.= '<span class="gr-link mr-1">
							<a data-fancybox data-type="iframe" data-preload="true" href="/viewer/'.$gg_id.'">'.$pval['title'].'</a>';
						if(!empty($pval['video'])){
							$html_links.= ' | <a data-fancybox data-type="iframe" href="'.$pval['video'].'">video</a>';
						}
						$html_links.='</span>';
					}
					++$ii;
				}
				$html_links.= '</li>';
			}
		}
		if($agency_id > 0 && ($loggedIn && $clsISO->checkPermission('view_stock_resource'))){
			$oProperty = $clsProperty->getOne($agency_id, "property_code,title,more_information");
			$agency_information = $oProperty['more_information'];
			$agency_information = $clsISO->to_array_json($agency_information);
			
			$html_button_price_sheets = "";
			$folder_price_sheets = isset($agency_information['folder_price_sheets']) && !empty($agency_information['folder_price_sheets']) 
				? $agency_information['folder_price_sheets'] : array();
			if(!empty($folder_price_sheets)){
				foreach($folder_price_sheets as $key => $val){
					if(!empty($val['title']) && !empty($val['link'])){
						$html_button_price_sheets.= '<a class="btn btn-xs btn-outline-default mr-1" href="'.$val['link'].'" target="_blank">'.$val['title'].'</a>';
					}
				}
			}
			if($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR')) {
				$html_links.= sprintf('<li><a href="/admin/?mod=setting&act=property&group=stock&agency='.$agency_id.'" target="_blank"><i class="material-icons-outlined">home_work</i> %s %s</a></li>', $oProperty['title'], $html_button_price_sheets);
			}else{
				$html_links.= sprintf('<li><i class="material-icons-outlined">home_work</i> %s %s</li>', $oProperty['title'], $html_button_price_sheets);
			}
			
		}
		if(!empty($list_attributes)){
			foreach($list_attributes as $key => $val){
				$html_links.= '<li>
					'.$val['title'].': '.$val['content'].'
				</li>';
			}
		}
		###
		if($oneStock['stock_type'] == _BLOCK_TYPE_LOWFLOOR_SALE){
			$regex = sprintf('%s_%s', $project_id, $block_id);
		} else {
			$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
		}
		$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
		order by ms_date DESC limit 0,1");
		if(!empty($onePolicy)){
			$html_links.= '<li><a target="_blank" href="'.$onePolicy['link_ms'].'">
				<i class="material-icons-outlined">paid</i> Phiếu tính giá
			</a></li>
			<li><a target="_blank" href="'.$onePolicy['link_ns'].'">
				<i class="material-icons-outlined">policy</i> Chinh sách Bán Hàng
			</a></li>';
		}
		if($clsISO->checkPermission('log_search_stock')){
			$clsLog = new Log();
			$html_links.= '<li><a href="'.PCMS_URL.'/logs-sale.html?stock_id='.$stock_id.'" target="_blank">
				<i class="material-icons-outlined">history</i> Lịch sử tra cứu <strong class="text-black">(
					'.$clsLog->getTotalSearch($stock_id).')
				</strong></a></li>';
		}
		#- Loai Hinh
		$oneType = $clsProperty->getOne($type_id,"title,bgcolor,textcolor");
		$smarty->assign('oneType', $oneType);
		#
		$smarty->assign('stock_id', $stock_id);
		$smarty->assign('oneStock', $oneStock);
		$smarty->assign('oneMeta', $oneMeta);
		$smarty->assign('stock_meta_id', $stock_meta_id);
		$smarty->assign('html_links', $html_links);
		$smarty->assign('img_price_sheets', $img_price_sheets);
		$smarty->assign('has_price_early', $has_price_early);
		$smarty->assign('total_price_vat', $total_price_vat);
		$smarty->assign('html_price_more', $html_price_more);
		$smarty->assign('html_price_sheets', $html_price_sheets);
		$smarty->assign('price_m2', $total_price_vat/$clsISO->convertToNumber($DT_TT));
		$title_page = $oneStock['ms_code'] . ' - '.PAGE_NAME;
	} else {
		$title_page = 'Căn hộ yêu thích - '.PAGE_NAME;
	}
	/*=============Title & Description Page==================*/
	$assign_list["title_page"] = $title_page;
}
function default_load_wishlist(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$clsProfile,$deviceType;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$wishlist = $clsProfile->getOneField('wishlist', $profile_id);
	$wishlist_arrs = $clsISO->to_array_json($wishlist);
	###
	$list_stocks = array();
	if(!empty($wishlist_arrs)){
		$field= "{$clsStock->pkey},ms_code,bedroom_id,home_direction_id,project_id,block_id,building_id
		,status_id,more_information";
		$list_stocks = $clsStock->getAll("stock_id in (".implode(',',$wishlist_arrs).")", $field);
		if(!empty($list_stocks)){
			$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
			$list_status = $clsProperty->getAllCache("property_type='_STATUS' order by order_no ASC", $field);
			$status_bgcolor_arrs = $status_textcolor_arrs = array();
			if(!empty($list_status)){
				foreach($list_status as $key => $val){
					$status_bgcolor_arrs[$val[$clsProperty->pkey]] = $val['bgcolor'];
					$status_textcolor_arrs[$val[$clsProperty->pkey]] = $val['textcolor'];
				}
			}
			$arr_property_cached = $arr_procode_cached = array();
			foreach($list_stocks as $key => $val){
				$project_id = $val['project_id'];
				$block_id = $val['block_id'];
				$building_id = $val['building_id'];
				$status_id = $val['status_id'];
				$bedroom_id = $val['bedroom_id'];
				$home_direction_id = $val['home_direction_id'];
				if(!isset($arr_procode_cached[$status_id])){
					$arr_procode_cached[$status_id] = $clsProperty->getOneField('property_code',$status_id);
				}
				if(!isset($arr_property_cached[$status_id])){
					$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
				}
				if(!isset($arr_property_cached[$bedroom_id])){
					$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
				}
				if(!isset($arr_property_cached[$home_direction_id])){
					$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
				}
				$more_information = $val['more_information'];
				$more_information = !empty($more_information) 
					? json_decode(html_entity_decode($more_information), true) 
					: array();
				$total_price_vat = 0;
				if(isset($more_information['total_price_vat']) && !empty($more_information['total_price_vat'])){
					$total_price_vat = $more_information['total_price_vat'];
					$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
					$total_price_vat = number_format((float) $clsISO->priceFormat($total_price_vat),3,'.','');
				}
				##
				$POLICY_LINK = $PTG_LINK = "";
				if($clsProperty->getOneField('parent_id', $block_id) == _BLOCK_TYPE_LOWFLOOR_SALE){
					$regex = sprintf('%s_%s', $project_id, $block_id);
				} else {
					$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
				}
				$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
				order by ms_date DESC limit 0,1");
				if(!empty($onePolicy)){
					$POLICY_LINK = '<a target="_blank" href="'.$onePolicy['link_ns'].'">
						'.$clsISO->makeIcon('bx-link-external','Mở link').'</a>';
					$PTG_LINK = '<a target="_blank" href="'.$onePolicy['link_ms'].'">
						'.$clsISO->makeIcon('bx-link-external','Mở link').'</a>';
				}
				$bg_color = $status_bgcolor_arrs[$status_id];
				$text_color = $status_textcolor_arrs[$status_id];
				##
				$list_stocks[$key]['total_price_vat'] = $total_price_vat;
				$list_stocks[$key]['DT_TT'] = $more_information['DT_TT'];
				$list_stocks[$key]['LOAI_CAN'] = $arr_property_cached[$bedroom_id];
				$list_stocks[$key]['HUONG_BC'] = $arr_property_cached[$home_direction_id];
				$list_stocks[$key]['TINH_TRANG'] = ($deviceType=='phone' ? '<span class="label ml-1" style="color:'.$text_color.'; background:'.$bg_color.'">'.$arr_procode_cached[$status_id].'</span>':'<td class="text-left" style="color:'.$text_color.'; background:'.$bg_color.'">'.$arr_property_cached[$status_id].'</td>');
				$list_stocks[$key]['POLICY_LINK'] = $POLICY_LINK;
				$list_stocks[$key]['PTG_LINK'] = $PTG_LINK;
			}
		}
	}
	$smarty->assign('list_stocks', $list_stocks);
	// Return
	$html = $core->build('_ajax.wishlist.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_edit_stock_field(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsStockMeta = new StockMeta();
	###
	$uid = $clsISO->getUniqid();
	$clsTable = Input::post('clsTable');
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', "");
	###
	$clsClassTable = new $clsTable();
	$p_value= $clsClassTable->getOneField($p_field, $p_id);
	###
	$smarty->assign('p_id', $p_id);
	$smarty->assign('p_field', $p_field);
	$smarty->assign('p_value', $p_value);
	$smarty->assign('titlePage', "Chỉnh sửa nội dung");
	// Return
	$html = $core->build('_ajax.edit_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_update_stock_field(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsStockMeta = new StockMeta();
	###
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', 0);
	if($clsStockMeta->updateOne($p_id, array(
		$p_field => Input::post($p_field)
	))){
		$p_value = $clsStockMeta->getOneField($p_field, $p_id);
		echo @html_entity_decode($p_value);
		die();
	} else {
		echo '_error';
		die();
	}
}
function default_open_stock_video(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsStockMeta = new StockMeta();
	##
	$uid = $clsISO->getUniqid();
	$media_id = Input::post('media_id');
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	##
	$titlePage = 'Thêm Media';
	$oneVideo = array('title' => "", 'url' => "");
	if(!empty($media_id)){
		$titlePage = 'Chỉnh sửa Media';
		$media = $clsStockMeta->getOneField('media', $stock_meta_id);
		$media = !empty($media) ? json_decode(html_entity_decode($media), true) : array();
		if(!empty($media_id) && !empty($media) 
			&& array_key_exists($media_id, $media)){
			$oneVideo = $media[$media_id];
		}
	}
	$smarty->assign('media_id', $media_id);
	$smarty->assign('oneVideo', $oneVideo);
	$smarty->assign('stock_meta_id', $stock_meta_id);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('template_type', 'video');
	$html = $core->build('_ajax.edit_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_add_stock_video(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	###
	$url = Input::post('url');
	$title = Input::post('title');
	$media_id = Input::post('media_id');
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	$media = $clsStockMeta->getOneField('media', $stock_meta_id);
	$media = !empty($media) ? json_decode(html_entity_decode($media), true) : array();
	###
	$msg= "_error";
	if(!empty($url) && !empty($title)){
		if(!empty($media_id) && !empty($media) 
			&& array_key_exists($media_id, $media)){
			$media[$media_id]['title'] = $title;
			$media[$media_id]['url'] = $url;
		} else {
			$media[$clsISO->getUniqid()] = array(
				'url' => $url,
				'title' => $title,
				'type' => 'video'
			);
		}
		if($clsStockMeta->updateOne($stock_meta_id, array(
			'media' => json_encode($media, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_upload_stock_image(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$uid = $clsISO->getUniqid();
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	
	$msg = "_error";
	$list_images= array();
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$images = $_FILES['images'];
		if(!empty($images['name']) && @array_sum($images['error']) == 0){
			for($i = 0; $i < count($images); $i++){
				$image = array();
				$image["name"] = $images['name'][$i];
				$image["type"] = $images['type'][$i];
				$image["tmp_name"] = $images['tmp_name'][$i];
				$image["error"] = $images['error'][$i];
				$image["size"] = $images['size'][$i];
				
				$clsUploadFile = new UploadFile();
				$up = $clsUploadFile->uploadItem($image,"/Stock","jpeg,jpg,gif,png");
				if(!empty($up) && file_exists(ROOTPATH.$up)){
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($image["name"], $image["type"], ROOTPATH.$up);
					$list_images[] = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					@unlink(ROOTPATH . $up);
				}
			}
		}
	}
	$smarty->assign('titlePage', "Thêm Media");
	$smarty->assign('list_images', $list_images);
	$smarty->assign('stock_meta_id', $stock_meta_id);
	// Return
	$smarty->assign('template_type','image');
	$html = $core->build('_ajax.edit_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_uploadImage(){
	global $core,$_frontIsLoggedin_user_id,$clsISO;
	$image = '';
	if(@is_uploaded_file($_FILES['image']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		$image = $clsUploadFile->uploadItem($_FILES["image"],"/Stock",EXTENSION_FILE_UPLOAD);
		if(!empty($image) && file_exists(ROOTPATH . $image)){
			// Set the file metadata for drive
			$title = $_FILES["image"]["name"];
			$mimeType = $_FILES["image"]["type"];
			$clsGoogleDrive = new GoogleDrive();
			$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image);
			// $image = 'https://drive.google.com/uc?export=view&id='.$createdFile->getId();
			$image = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
			@unlink(ROOTPATH . $image);
		}
	}
	// Return
	echo json_encode(array(
		'image' => $image
	)); die();
}
function default_add_stock_image(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	###
	$media = $clsStockMeta->getOneField('media', $stock_meta_id);
	$media = !empty($media) ? json_decode(html_entity_decode($media), true) : array();
	###
	$msg = "_error";
	$submit = Input::post('submit', 'add_image');
	if($submit=='edit_image'){
		$media_id = Input::post('media_id');
		if(!empty($media_id) && !empty($media) && array_key_exists($media_id, $media)){
			$media[$media_id]['title'] = Input::post('title');
			$media[$media_id]['url'] = Input::post('url');
			if($clsStockMeta->updateOne($stock_meta_id, array(
				'media' => json_encode($media, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
		}
	} else {
		$images = Input::post('images');
		if(!empty($images)){
			foreach($images as $key => $val){
				$media[$key] = array(
					'title' => $val['title'],
					'url' => $val['url'],
					'type' => 'image' 
				);
			}
			if($clsStockMeta->updateOne($stock_meta_id, array(
				'media' => json_encode($media, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_upload_file(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	
	$msg = "_error";
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$attactments = $_FILES['attactments'];
		if(!empty($attactments['name']) && @array_sum($attactments['error']) == 0){
			$list_files = array();
			for($i = 0; $i < count($attactments); $i++){
				$file = array();
				$file["name"] = $attactments['name'][$i];
				$file["type"] = $attactments['type'][$i];
				$file["tmp_name"] = $attactments['tmp_name'][$i];
				$file["error"] = $attactments['error'][$i];
				$file["size"] = $attactments['size'][$i];
				
				$clsUploadFile = new UploadFile();
				$up = $clsUploadFile->uploadItem($file,"/stock",EXTENSION_FILE_UPLOAD);
				if(!empty($up) && file_exists(ROOTPATH.$up)){
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($file["name"], $file["type"], ROOTPATH.$up);
					$attachment = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					// $attachment = 'https://drive.google.com/uc?export=view&id='.$createdFile->getId();
					$list_files[$clsISO->getUniqid()] = array(
						'description' => $file["name"],
						'file_name' => $file["name"],
						'file_size' => $file["size"],
						'attachment' => $attachment,
						'user_id'	=> $profile_id,
						'user_id_update' => $profile_id,
						'reg_date'	=> time(),
						'upd_date' => time()
					);
				}
			}
			if(!empty($list_files)){
				$files = $clsStockMeta->getOneField('files', $stock_meta_id);
				$files = !empty($files) ? json_decode(html_entity_decode($files), true) : array();
				$files = array_merge($files, $list_files);
				if($clsStockMeta->updateOne($stock_meta_id, array(
					'files' => json_encode($files, JSON_UNESCAPED_UNICODE)
				))){
					$msg = "_success";
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_edit_stock_media(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsStockMeta = new StockMeta();
	###
	$uid = $clsISO->getUniqid();
	$media_id = Input::post('media_id', "");
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	$media = $clsStockMeta->getOneField('media', $stock_meta_id);
	$list_medias = !empty($media) 
		? json_decode(html_entity_decode($media), true) 
		: array();
	
	$oneImage = array();
	if(!empty($media_id) && !empty($list_medias) 
		&& array_key_exists($media_id, $list_medias)){
		$oneImage = $list_medias[$media_id];
	}
	// $clsISO->print_pre($oneImage); die();
	$smarty->assign('media_id', $media_id);
	$smarty->assign('oneImage', $oneImage);
	$smarty->assign('stock_meta_id', $stock_meta_id);
	$smarty->assign('titlePage', "Chỉnh sửa Media");
	// Return
	$smarty->assign('template_type', 'edit_image');
	$html = $core->build('_ajax.edit_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete_stock_media(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsStockMeta = new StockMeta();
	
	$media_id = Input::post('media_id', "");
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	$media = $clsStockMeta->getOneField('media', $stock_meta_id);
	$list_medias = !empty($media) 
		? json_decode(html_entity_decode($media), true) 
		: array();
	// $clsISO->print_pre($list_medias); die();
	$msg = "_error";
	if(!empty($media_id) && !empty($list_medias) 
		&& array_key_exists($media_id, $list_medias)){
		unset($list_medias[$media_id]);
		if($clsStockMeta->updateOne($stock_meta_id, array(
			'media' => json_encode($list_medias, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function getEmbedVid($media){
	global $core, $dbconn, $clsISO, $deviceType;
	if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $media['url'])){
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $media['url'], $matches);
		return ($deviceType=='phone' ? '<iframe class="radius-3 mb-2 overflow-hidden" width="100%" height="250" src="https://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>':'<a class="gallery-item-link mb-2 video" style="background-image:url(https://i.ytimg.com/vi_webp/'.$matches[1].'/maxresdefault.webp")" href="'.$media['url'].'" data-fancybox></a>');
	}
}
function default_load_stock_media(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$deviceType;
	$clsProperty = new Property();
	$clsStockMeta = new StockMeta();
	###
	$stock_meta_id = (int) Input::post('stock_meta_id', 0);
	if(1==2){
		$media = $clsStockMeta->getOneField('media', $stock_meta_id);
		$list_medias = !empty($media) 
			? json_decode(html_entity_decode($media), true) 
			: array();
	} else {
		$list_medias = array();
		$stock_id = $clsStockMeta->getOneField('stock_id', $stock_meta_id);
		$list_stocks_media = $clsStockMeta->getAll("stock_id='{$stock_id}'");
		if(!empty($list_stocks_media)){
			foreach($list_stocks_media as $key => $val){
				$media = $val['media'];
				$media_arrs = !empty($media) 
					? json_decode(html_entity_decode($media), true) 
					: array();
				if(!empty($media_arrs)){
					foreach($media_arrs as $okey => $oval){
						$list_medias[$okey] = $oval;
					}
				}
			}
		}
	}
	###
	$html = "";
	if(!empty($list_medias)){
		$html.= '<div class="gallery-container">';
		foreach($list_medias as $id => $media){
			$func = 'open_stock_video';
			if($media['type'] =='image'){
				$func = 'edit_stock_media';
			}
			$html.= '<div class="gallery-item yHGjYosKnf">';
				if($media['type'] == 'video'){
					$html.= getEmbedVid($media);
				} else {
					if($deviceType=='phone'){
						$html.='<div class="jDDQlozraY radius-3 mb-2">
							<span onClick="$Core.tool.set_banner(this, event)" banner="'.trim($media['url']).'" media_id="'.$id.'" stock_meta_id="'.$stock_meta_id.'" class="pOBruJNexS">Set cover</span>
							<img src="'.$media['url'].'" class="img-fluid radius-3" />
						</div>';
					} else {
						$html.= '<a class="gallery-item-link mb-2" style="background-image:url('.$clsISO->getGoogleUrl(trim($media['url'])).')" 
						href="'.$clsISO->getGoogleUrl(trim($media['url'])).'" data-fancybox>
							<span onClick="$Core.tool.set_banner(this, event)" banner="'.$clsISO->getGoogleUrl(trim($media['url'])).'" media_id="'.$id.'" stock_meta_id="'.$stock_meta_id.'" class="pOBruJNexS">Set cover</span>
						</a>';
					}
				}
				$html .= '<div class="clearfix"></div>
				<div class="ctCfdnUPiK d-flex align-items-center justify-content-between">
					<p class="fDIfvHwvwr line-clamp-1 mb-0">'.$media['title'].'</p>
					<div class="nFERRQSiVo text-white">
						<a href="javascript:;" class="mr-1 text-gray" media_id="'.$id.'" stock_meta_id="'.$stock_meta_id.'" onClick="$Core.tool.'.$func.'(this, event)">'.$clsISO->makeIcon('bx-pencil').'</a>
						<a href="javascript:;" class="text-gray" media_id="'.$id.'" stock_meta_id="'.$stock_meta_id.'" onClick="$Core.tool.delete_stock_media(this, event)">'.$clsISO->makeIcon('bx-trash').'</a>
					</div>
				</div>';
			$html .= '</div>';
		}
		$html.='</div>';
	} else {
		$html.= '<div class="p-3 text-center">
			<div class="text-muted text-center">Chưa có hình ảnh, video nào!</div>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_set_banner(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	##
	$msg = "_error";
	$banner = Input::post('banner');
	$stock_meta_id = Input::post('stock_meta_id', 0);
	$more_information = $clsStockMeta->getOneField('more_information', $stock_meta_id);
	$more_information = $clsISO->to_array_json($more_information);
	$more_information['banner'] = $banner;
	if($clsStockMeta->updateOne($stock_meta_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	echo $msg; die();
}
function default_open_property(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProperty = new Property();
	$toId = Input::post('toId');
	$property_id = (int) Input::post('property_id',0);
	$property_type = Input::post('property_type',"");
	
	$action = '_add';
	$oneProperty = array("image" => "");
	if($property_id > 0){
		$action = '_edit';
		$oneProperty = $clsProperty->getOne($property_id);
	}
	$smarty->assign('toId', $toId);
	$smarty->assign('action', $action);
	$smarty->assign('oneProperty', $oneProperty);
	$smarty->assign('property_id', $property_id);
	$smarty->assign('property_type', $property_type);
	if($property_type=='BANK_ACCOUNT'){
		$list_banks = array();
		$curl = new Curl\Curl();
		$curl->get('https://api.vietqr.io/v2/banks');
		if(!$curl->error){
			$response = $curl->response;
			$response = toArray($response);
			if(isset($response['code']) && $response['code']=='00'){
				$list_banks = $response['data'];
			}
		}	
		$smarty->assign('list_banks', $list_banks);
	}
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.property.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function default_pop_save_property(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProperty = new Property();
	#
	$toId = Input::post('toId');
	$property_id = (int) Input::post('property_id',0);
	$property_type = Input::post('property_type',"");
	$title = Input::post('title');
	$bgcolor = Input::post('bgcolor');
	$textcolor = Input::post('textcolor');
	#
	$more = array();
	if($property_type=='BANK_ACCOUNT'){
		$image = Input::post('image');
		$ms_value = Input::post('ms_value');
		$more['image'] = $image;
		$more['ms_value'] = $clsISO->processSmartNumber($ms_value);
	}
	###
	if($property_id > 0){
	if($clsProperty->countItem("property_type='{$property_type}' and {$clsProperty->pkey}<>'{$property_id}' 
		and slug='".$core->replaceSpace($title)."'") == 0){
			if($clsProperty->updateOne($property_id, array_merge($more, array(
				'title' => $title,
				'bgcolor' => $bgcolor,
				'textcolor' => $textcolor,
				'slug' => $core->replaceSpace($title),
				'intro' => Input::post('intro'),
				'upd_date' => time()
			)))){
				if($toId=='crm'){
					$html = '_success';
				} else {
					$html = $clsProperty->getSelectByProperty($property_type,$property_id);
				}				
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Property","update",["property_type"=>$property_type]);
			}
		} else {
			$html = '_duplicated';
		}
	} else {
		if($clsProperty->countItem("property_type='{$property_type}' 
		and slug='".$core->replaceSpace($title)."'") == 0){
			$property_id = $clsProperty->getMaxId();
			if($clsProperty->insert(array_merge($more, array(
				$clsProperty->pkey => $property_id,
				'property_type' => $property_type,
				'title' => $title,
				'bgcolor' => $bgcolor,
				'textcolor' => $textcolor,
				'slug' => $core->replaceSpace($title),
				'intro' => Input::post('intro'),
				'order_no' => $clsProperty->getMaxOrderNo(),
				'reg_date' => time()
			)))){
				if($toId=='crm'){
					$html = '_success';
				} else {
					$html = $clsProperty->getSelectByProperty($property_type,$property_id);
				}
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Property","insert",["property_type"=>$property_type]);
			}
		} else {
			$html = '_duplicated';
		}
	}
	// Return
	echo $html; die();
}
function default_delete_property(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	$property_id = (int) Input::post('property_id',0);
	###
	$msg = "_error";
	if($clsProperty->deleteOne($property_id)){
		$msg = '_success';		
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Property","delete",["property_type"=>$property_type]);
	}
	// Return
	echo $msg; die();
}
function default_calendar(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;
	$clsSetting = new Setting();
	# Danh sách phòng gom theo chi nhánh; chi nhánh chưa cấu hình phòng sẽ có phòng ảo room_id=0.
	$room_map = $clsSetting->getMeetingRoomMap();
	# Phòng chọn sẵn: suy từ chi nhánh của phòng ban nhân sự, không có thì về văn phòng mặc định.
	$department_id = isset($oneProfile['department_id']) ? (int) $oneProfile['department_id'] : 0;
	$default_room = $clsSetting->resolveMeetingRoom($department_id, 0);
	$assign_list['room_offices'] = $room_map['offices'];
	$assign_list['room_list'] = $room_map['rooms'];
	$assign_list['default_office_id'] = $default_room['office_id'];
	$assign_list['default_room_id'] = $default_room['room_id'];
	/*=============Title & Description Page==================*/
	$title_page = 'Lịch đăng ký phòng họp - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_load_calendar(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCalendar = new Calendar();
	$current_now = strtotime(date('d-m-Y'));
	$start = (int) Input::get('start');
	$end = (int) Input::get('end');
	# Phòng đang xem: quyết định lịch của phòng nào được nạp. room_id=0 hợp lệ (phòng mặc định
	# của chi nhánh) nên office_id mới là cột phải có; luôn lọc theo cả cặp.
	$office_id = (int) Input::get('office_id', 0);
	$room_id = (int) Input::get('room_id', 0);
	# Chưa chọn phòng hợp lệ (office_id<=0) thì trả lịch rỗng, tránh khớp nhầm các dòng
	# mồ côi office_id=0 còn sót trong bảng.
	if($office_id <= 0){
		echo json_encode(array()); die();
	}
	$arr_department_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$cond_room = " AND `office_id`='{$office_id}' AND `room_id`='{$room_id}'";
	$_results = array();
	$arr_profile_cached = array();
	for($i = $start; $i<=$end; $i = strtotime("+1 day", $i)){
		$html_list = "";
		$list_items = $clsCalendar->getAll("`is_trash`=0 and FROM_UNIXTIME(`start_date`,'%d/%m/%Y')='".date('d/m/Y',$i)."'
			{$cond_room} order by `start_date` ASC");
		if(!empty($list_items)){
			foreach($list_items as $key => $val){
				$user_id = $val['user_id'];
				if($user_id == $profile_id){
					$arr_profile_cached[$user_id] = $oneProfile;
				} else {
					if(!isset($arr_profile_cached[$user_id])){
						$field = "`first_name`,`last_name`,`full_name`,`avatar`,`more_information`,`department_id`";
						$_oProfile = $clsProfile->getOne($user_id, $field);
						$profile_info = $clsISO->to_array_json($_oProfile["more_information"]);						
						$_oProfile["department_name"] = $arr_department_cached[$_oProfile["department_id"]]["title"];
						$arr_profile_cached[$user_id] = $_oProfile;
					}
				}
				$html_buttons = ($val['user_id'] == $profile_id) ? '
				<div class="js__calendar-tool js__calendar-action">
					<div class="js__calendar-cmd w-px-50 d-flex gap-1 align-items-center">
						<a data-toggle="ripple" class="cursor-pointer" onClick="$Core.calendar.open(this, event)" openFrom="_dashboard" title="Sửa" calendar_id="'.$val[$clsCalendar->pkey].'"><i class="bx bx-pencil"></i></a>
						<a data-toggle="ripple" class="cursor-pointer" onClick="$Core.calendar.delete(this, event)" calendar_id="'.$val[$clsCalendar->pkey].'" title="Xóa"><i class="bx bx-x"></i></a>
					</div>
				</div>':'';
				$html_list.= '<div class="js__calendar-item d-flex gap-2 relative mb-1 py-1 px-2 rounded-2"
					style="background:'.$val['bgcolor'].'">
					<div class="avatar avatar-xxs mt-1">
						<img class="avatar avatar-xxs rounded-pill" title="'.$clsProfile->getFullName($user_id, $arr_profile_cached[$user_id]).'" src="'.$clsProfile->getAvatar($val['user_id'],$arr_profile_cached[$user_id],40,40).'" />
					</div>
					<div class="js__calendar-item-body">
						<div class="mb-0">'.sprintf('%s <i class="fa fa-long-arrow-right" aria-hidden="true"></i> %s', date('H:i', $val['start_date']), date('H:i', $val['end_date'])).'</div>
						<p class="mb-0 lh-xs">'.$arr_profile_cached[$user_id]["last_name"]." - " . $arr_profile_cached[$user_id]["department_name"] .'</p>
						<p class="mb-0 lh-xs">'.$val['title'].'</p>
					</div>'.$html_buttons.'
				</div>';
			}
			unset($list_items);
		}
		$regis_fm = date('Y-m-d', $i);
		$regis_date = $clsISO->toTime($regis_fm, date('H:i'));
		$_results[] = array(
			'start' => date('Y-m-d H:i:s', $i),
			'title' => PAGE_NAME,
			'html' => '<div class="p-2">
				'.$html_list.'
				<div class="d-flex justify-content-end">
					'.($i >= $current_now ? '<button data-toggle="ripple" onClick="$Core.calendar.open(this, event)" openFrom="_calendar"
					regis_date="'.$regis_date.'" title="Thêm mới" class="btn btn-sm btn-icon btn-outline-default">
						<i class="bx bx-plus"></i>
					</button>' : '').'
				</div>
			</div>'
		);
	}
	// Return
	echo json_encode($_results); die();
}
function default_open_calendar(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID,$oneProfile;
	$clsCalendar = new Calendar();
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$regis_date = Input::post('regis_date', strtotime("+5 minutes"));
	$calendar_id = (int) Input::post('calendar_id', 0);
	# Phòng đang xem truyền từ thanh lọc, dùng làm mặc định khi thêm mới.
	$office_id = (int) Input::post('office_id', 0);
	$room_id = (int) Input::post('room_id', 0);
	###
	$list_colors = array(
		'#f44336','#3f51b5','#ff5722'
		,'#795548','#e91e63','#9c27b0');
	$smarty->assign('list_colors', $list_colors);
	###
	$action= "_add";
	$oneCalendar = array(
		'is_fullday' => 0,
		'start_date' => $regis_date,
		'bgcolor' => $list_colors[array_rand($list_colors, 1)],
		'end_date' => strtotime("+1 hour", $regis_date)
	);
	$html_dep_options = $html_group_options = $html_staff_options = "";
	if($calendar_id > 0){
		$action = "_edit";
		$oneCalendar = $clsCalendar->getOne($calendar_id);
		# Sửa lịch: giữ đúng phòng của bản ghi, không lấy theo thanh lọc.
		$office_id = (int) $oneCalendar['office_id'];
		$room_id = (int) $oneCalendar['room_id'];
		$more_information = $oneCalendar['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$list_department_id = isset($more_information['list_department_id'])
			?  $more_information['list_department_id'] : array();
		$list_group_id = isset($more_information['list_group_id'])
			?  $more_information['list_group_id'] : array();
		$list_staff_id = isset($more_information['list_staff_id'])
			?  $more_information['list_staff_id'] : array();
		if(!empty($list_department_id)){
			$clsProperty = new Property();
			foreach($list_department_id as $key => $dep_id){
				$html_dep_options.= sprintf('<option value="%s" selected>%s</option>',
					$dep_id, $clsProperty->getTitle($dep_id));
			}
		}
		if(!empty($list_group_id)){
			$clsGroupProfile = new GroupProfile();
			foreach($list_group_id as $key => $group_id){
				$html_group_options.= sprintf('<option value="%s" selected>%s</option>',
					$group_id, $clsGroupProfile->getTitle($group_id));
			}
		}
		if(!empty($list_staff_id)){
			$csProfile = new Profile();
			foreach($list_staff_id as $key => $staff_id){
				$html_staff_options.= sprintf('<option value="%s" selected>%s</option>',
					$staff_id, $csProfile->getFullName($staff_id));
			}
		}
	}
	# Phòng chưa xác định (mở form trực tiếp, không qua thanh lọc) thì suy mặc định.
	if($office_id <= 0){
		$department_id = isset($oneProfile['department_id']) ? (int) $oneProfile['department_id'] : 0;
		$default_room = $clsSetting->resolveMeetingRoom($department_id, 0);
		$office_id = $default_room['office_id'];
		$room_id = $default_room['room_id'];
	}
	$room_map = $clsSetting->getMeetingRoomMap();
	$smarty->assign('room_offices', $room_map['offices']);
	$smarty->assign('room_list', $room_map['rooms']);
	$smarty->assign('sel_office_id', $office_id);
	$smarty->assign('sel_room_id', $room_id);
	$smarty->assign('action', $action);
	$smarty->assign('calendar_id', $calendar_id);
	$smarty->assign('oneCalendar', $oneCalendar);
	$smarty->assign('html_dep_options', $html_dep_options);
	$smarty->assign('html_group_options', $html_group_options);
	$smarty->assign('html_staff_options', $html_staff_options);
	// Return
	$html = $core->build('_ajax.calendar.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_calendar(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID,$dbconn,$profile_id
	,$oneProfile,$clsNotification;
	$clsProfile = new Profile();
	$clsCalendar = new Calendar();
	$clsSetting = new Setting();
	###
	$msg = "_error";
	$subscribers = array();
	$current_now = time();
	$calendar_id = (int) Input::post('calendar_id', 0);
	$regis_date = Input::post('regis_date');
	$start_time = Input::post('start_time');
	$end_time = Input::post('end_time');
	$is_fullday = (int) Input::post('is_fullday', 0);
	$start_date = !empty($start_time) ? $clsISO->toTime($regis_date, $start_time) : 0;
	$end_date = !empty($end_time) ? $clsISO->toTime($regis_date, $end_time) : 0;
	# Phòng được đặt: chỉ nhận cặp office:room thực sự tồn tại trong bản đồ phòng.
	# Không suy đoán từ dữ liệu POST vì room_id=0 dùng chung cho mọi chi nhánh — chỉ cặp
	# (office_id, room_id) mới định danh duy nhất một phòng. Cặp lạ (phòng đã xoá, POST bịa) bị loại.
	$office_id = (int) Input::post('office_id', 0);
	$room_id = (int) Input::post('room_id', 0);
	$room_map = $clsSetting->getMeetingRoomMap();
	$is_valid_room = isset($room_map['index'][$office_id.':'.$room_id]);
	# regis_date được nhét thẳng vào SQL ở nhánh cả ngày, ép đúng định dạng ngày để chặn injection.
	$is_valid_date = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $regis_date);
	$cond_room = "`office_id`='{$office_id}' AND `room_id`='{$room_id}'";
	#
	if(!$is_valid_room || !$is_valid_date){
		$msg = "_invalid";
	} else if($start_date < $current_now || $start_date >= $end_date){
		$msg = "_invalid";
	} else {
		if($calendar_id > 0){
			# Kiểm trùng TRONG CÙNG PHÒNG. Bỏ ràng buộc theo ngày để bắt cả cuộc họp vắt nửa đêm.
			if($is_fullday == 1){
				$tmp = $clsCalendar->getByCond("`is_trash`=0 AND {$cond_room} AND {$clsCalendar->pkey}<>'{$calendar_id}'
					AND FROM_UNIXTIME(`start_date`,'%Y-%m-%d')='{$regis_date}'");
			} else {
				$tmp = $clsCalendar->getAll("`is_trash`=0 AND {$cond_room} AND {$clsCalendar->pkey}<>'{$calendar_id}'
					AND (`start_date`<'{$end_date}') AND (`end_date`>{$start_date})");
			}
			if(!empty($tmp)){
				$msg = '_duplicated';
			} else {
				$oneCal = $clsCalendar->getOne($calendar_id);
				$more_information = $oneCal['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$more_information['list_department_id'] = Input::post('list_department_id');
				$more_information['list_group_id'] = Input::post('list_group_id');
				$more_information['list_staff_id'] = Input::post('list_staff_id');
				if($clsCalendar->updateOne($calendar_id, array(
					'title' => Input::post('title'),
					'bgcolor' => Input::post('bgcolor'),
					'office_id' => $office_id,
					'room_id' => $room_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'start_date' => $start_date,
					'end_date' => $end_date,
					'upd_date' => time(),
					'user_id_update' => $profile_id
				))){
					$msg = "_success";
				}
			}
		} else {
			if($is_fullday == 1){
				$tmp = $clsCalendar->getByCond("`is_trash`=0 AND {$cond_room}
					AND FROM_UNIXTIME(`start_date`,'%Y-%m-%d')='{$regis_date}'");
			} else {
				$tmp = $clsCalendar->getAll("`is_trash`=0 AND {$cond_room}
					AND (`start_date`<'{$end_date}') AND (`end_date`>{$start_date})");
			}
			if(!empty($tmp)){
				$msg = '_duplicated';
			} else {
				$calendar_id = $clsCalendar->getMaxId();
				$list_department_id = Input::post('list_department_id');
				$list_group_id = Input::post('list_group_id');
				$list_staff_id = Input::post('list_staff_id');
				$more_information = array();
				$more_information['list_group_id'] = $list_group_id;
				$more_information['list_staff_id'] = $list_staff_id;
				$more_information['list_department_id'] = $list_department_id;
				if($clsCalendar->insert(array(
					$clsCalendar->pkey => $calendar_id,
					'title' => Input::post('title'),
					'is_fullday' => $is_fullday,
					'bgcolor' => Input::post('bgcolor'),
					'office_id' => $office_id,
					'room_id' => $room_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'start_date' => $start_date,
					'end_date' => $end_date,
					'reg_date' => time(),
					'upd_date' => time(),
					'user_id' => $profile_id,
					'user_id_update' => $profile_id
				))){
					$msg = "_success";
					// Send notification
					$clsNotify = new Notify();
					$clsFcmToken = new FcmToken();
					$list_staffs = $clsCalendar->get_staffs($list_department_id, $list_group_id, $list_staff_id);
					$title = sprintf("Mời tham gia cuộc họp [%s]", Input::post('title'));
					if(!empty($list_staffs)){
						$titleNotify = sprintf('<strong>%s</strong> đăng ký phòng họp vào lúc </strong>%s</strong>-></strong>%s</strong>: 	<strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), date('h:i', $start_date), date('h:i', $end_date), Input::post('title'));
						$tmp = $clsFcmToken->getAll("`push_type`='_pushalert'
							and `user_id` in (".implode(',', $list_staffs).") and `token`<>''", "token");
						if(!empty($tmp)){
							foreach($tmp as $key => $val){
								if(!in_array($val['token'], $subscribers)){
									$subscribers[] = $val['token'];
								}
							}
							$clsNotify->send_subscriber_notification(array(
								'title' => $title,
								'message' => strip_tags($titleNotify),
								'url' => PCMS_URL . '/lich-phong-hop.html'
							), $subscribers);
						}
						$clsNotify->insertNotify('Calendar', $clsCalendar->pkey, $calendar_id,
							$titleNotify, time(), sprintf('|%s|', implode('|', $list_staffs)));
						#thong bao app
						/*$params = [
							'title' => $title,
							'body' => strip_tags($titleNotify),
							'link' => PCMS_URL . '/lich-phong-hop.html'
						];
						if(is_object($clsNotification)){
							$clsNotification->doPushMessagingUser($params,$list_staffs);
						}*/
					}
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_calendar(){
	global $core,$clsISO,$profile_id;
	$clsCalendar = new Calendar();
	$calendar_id = (int) Input::post('calendar_id', 0);
	$msg = "_error";
	# Chỉ người tạo mới được xoá lịch của mình.
	$oneCal = $clsCalendar->getOne($calendar_id);
	if(!empty($oneCal) && (int) $oneCal['user_id'] === (int) $profile_id){
		if($clsCalendar->updateOne($calendar_id, array('is_trash' => 1, 'upd_date' => time()))){
			$msg = "_success";
		}
	}
	echo $msg; die();
}
function default_loadTotal_calendar(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID,$dbconn;
	$clsCalendar = new Calendar();
	$clsProperty = new Property();
	# Profile phải khởi tạo: JOIN dưới đây dùng $clsProfile->tbl, thiếu nó thì tên bảng rỗng
	# và câu lệnh hỏng -> thống kê theo phòng ban không ra dữ liệu.
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$type = Input::post("type","_OPEN");
	$month = (int) Input::post('month',0);
	$year = (int) Input::post('year',0);
	if($year == 0) $year = date('Y');
	# Phòng đang lọc từ thanh chọn; giá trị select là chuỗi "office_id:room_id".
	$room_combo = explode(':', Input::post('room_id',''));
	$office_id = (int) (isset($room_combo[0]) ? $room_combo[0] : 0);
	$room_id = (int) (isset($room_combo[1]) ? $room_combo[1] : 0);
	###
	# Luôn loại lịch đã xoá; nếu có chọn phòng thì thống kê thu hẹp theo đúng phòng đó.
	$cond = "`t1`.`is_trash`=0";
	if($office_id > 0){
		$cond .= " AND `t1`.`office_id`='{$office_id}' AND `t1`.`room_id`='{$room_id}'";
	}
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond .= " AND FROM_UNIXTIME(`t1`.`reg_date`,'%m/%Y')='{$date_my}'";
	}else{
		$cond .= " AND FROM_UNIXTIME(`t1`.`reg_date`,'%Y')='{$year}'";
	}
	# Tên phòng để hiện trên tiêu đề thống kê.
	$room_title = "";
	if($office_id > 0){
		$room_map = $clsSetting->getMeetingRoomMap();
		if(isset($room_map['index'][$office_id.':'.$room_id])){
			$room_title = $room_map['index'][$office_id.':'.$room_id]['title'];
		}
	}
	$smarty->assign('room_title', $room_title);
	$arr_department_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	// $clsISO->print_pre($arr_department_cached);die;
	$list_calendars = $dbconn->getAll("SELECT `t1`.*,`t2`.`department_id`,COUNT(`t2`.`department_id`) AS `total` 
		FROM {$clsCalendar->tbl} as `t1` LEFT JOIN `{$clsProfile->tbl}` as `t2` ON `t1`.`user_id` = `t2`.`profile_id` 
		WHERE {$cond} GROUP BY `t2`.`department_id`");
	$arr_data = []; $total = 0;
	if(!empty($list_calendars)){
		foreach($list_calendars as $key => $val){
			$department_id = $val['department_id'];
			$arr_data[$department_id] = [
				'department_id'	=>	$department_id,
				'department_name' => $arr_department_cached[$department_id]['title'],
				'total'	=>	$val['total']
			];
			$total += $val['total'];
		}
	}
	foreach($arr_department_cached as $k_dep => $v_dep) {
		$property_id = $v_dep['property_id'];
		$more_information = $v_dep['more_information'];
		$is_calendar = (int) $core->get_field($more_information, "is_calendar", 0);
		if(!isset($arr_data[$property_id]) && $is_calendar == 1) {
			$arr_data[$property_id] = [
				'department_id'		=>	$property_id,
				'department_name'	=>	$arr_department_cached[$property_id]['title'],
				'total'				=>	0
			];
		}
	}
	$total_sales_arrs = @array_column($arr_data, 'total');
	@array_multisort($total_sales_arrs, SORT_DESC, $arr_data);
	//	$clsISO->print_pre($arr_data);die;
	$smarty->assign('type', $type);
	$smarty->assign('total', $total);
	$smarty->assign('arr_data', $arr_data);
	# Trả kỳ đang lọc để select tháng/năm trong modal chọn sẵn đúng thời gian.
	# Danh sách năm dựng trong PHP (2024 -> năm sau): bounded, tránh {section loop=...} render lố.
	$smarty->assign('sel_month', $month);
	$smarty->assign('sel_year', $year);
	$smarty->assign('arr_years', range(2024, (int) date('Y') + 1));
	// Return
	$html = $core->build('_ajax.total_department.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
/** ZALO */
function default_group_zalo(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	###
	$load_preloaders = array();
	for($i=0; $i<100; $i++){
		$load_preloaders[] = $i;
	}
	$smarty->assign('load_preloaders', $load_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý nhóm check nguồn Zalo - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_sync_zalo(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsZalo = new Zalo();
	$clsCache = new Cache();
	$clsProfile = new Profile();
	###
	$field = "{$clsProfile->pkey},`phone`,`more_information`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $field);
	if(!empty($list_staffs)){
		$dataCached = array();
		$cachedFile = DIR_CACHE_JSON.'/zalo_account.json';
		require_once(DIR_INCLUDES.'/json_master/autoload.php');	
		foreach($list_staffs as $key => $val){
			$phone = trim($val['phone']);
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$zaloId = $core->get_field($more_information, "zaloId", "");
			if(!empty($zaloId)){
				$dataCached[] = (string) $zaloId;
			} else {
				if(!empty($phone)){
					$phone = $clsZalo->formatPhone($phone);
					$zaloId = $clsZalo->getZaloId($zaloId, $phone);
					if(!empty($zaloId)){
						$dataCached[] = (string) $zaloId;
						$more_information['zaloId'] = $zaloId;
						$clsProfile->updateOne($val[$clsProfile->pkey], array(
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
						));
					}
				}
			}
		}
		if(!empty($dataCached)){
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($dataCached, $cachedFile);
		}
	}
	// Return
	echo (1); die();
}
function default_load_chatlogs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsChatLog = new ChatLog();
	$clsZaloGroup = new ZaloGroup();
	
	$html = "";
	$list_chatlogs = $clsChatLog->getAll("1=1 order by `created_at` DESC limit 0,100");
	if(!empty($list_chatlogs)){
		foreach($list_chatlogs as $key => $val){
			$data_logs = $val['data_logs'];
			$data_logs = $clsISO->to_array_json($data_logs);
			$zaloId = $data_logs['sender_id'];
			$id_group = $data_logs['data']['data']['groupMsgs'][0]['idTo'];
			$name_group = "UNKNOW";
			$dName = $data_logs['data']['data']['groupMsgs'][0]['dName'];
			if(isset($data_logs['event']) && $data_logs['event'] == 'GROUP_RECEIVED_MESSAGE'){
				$tmp = $clsZaloGroup->getByCond("`id_group`='{$id_group}' limit 0,1");
				if(!empty($tmp)){
					$name_group = $tmp['name_group'];
				} else {
					$curl = new \Curl\Curl();
					$curl->setHeaders(array(
						'Content-Type' => 'application/json',
						'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MTA1ODM4ZWEzZWQxMjA1OTZjMjQzMyIsImlhdCI6MTc0NTkwMTYyNCwiZXhwIjoxNzc3NDM3NjI0fQ.8P5cRq2D8dk1T0ISOiGyctdc2BRhUgc7fnuVPXLy1OU'
					));
					$curl->post('https://public-api.bizflow.vn/functions/68105838ea3ed120596c2433', array(
						'group_id' => $id_group
					));
					if(!$curl->error){
						$response = toArray($curl->response);
						$name_group = $response['data']['data']['gridInfoMap'][$id_group]['name'];
						$clsZaloGroup->insert(array(
							'id_group' => $id_group,
							'name_group' => $name_group,
							'more_information' => json_encode($response, JSON_UNESCAPED_UNICODE),
							'reg_date' => time()
						));
					}
				}
			}
			$html.= '<tr>
				<td class="align-center">'.$clsISO->truncate($name_group, 30).'</td>
				<td class="align-center">'.$dName.'</td>
				<td class="align-center">'.$val['created_at'].'</td>
				<td class="align-center">
					<button onClick="$Core.zalo.open_group(this, event)" id_group="'.$id_group.'" name_group="'.$name_group.'" 
						title="Thêm nhóm" class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-plus"></i></button>
				</td>
			</tr>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();	
}
function default_set_field(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$zalo_group_permiss = $clsConfiguration->getValue('zalo_group_permiss');
	$zalo_group_permiss = $clsISO->to_array_json($zalo_group_permiss);
	##
	$msg = "_error";
	$group_id = Input::post('group_id');
	$to_field = Input::post('to_field', "status");
	$to_value = (int) Input::post('to_value', 0);
	if(!empty($zalo_group_permiss) && !empty($group_id)){
		if(array_key_exists($group_id, $zalo_group_permiss)){
			$zalo_group_permiss[$group_id][$to_field] = $to_value;
			if($clsConfiguration->updateValue('zalo_group_permiss', json_encode($zalo_group_permiss, JSON_UNESCAPED_UNICODE))){
				$msg = '_success';
			}
		}
	}
	// Return 
	echo $msg; die();
}
/** SALARY */
function default_salary(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration;
	global $title_page, $description_page;
	#
	$list_months = $list_years = array();
	$start_year = 2025;  $end_year = date('Y');
	$start_month = ($start_year == 2025) ? 8 : 1;
	for($i=$start_month; $i<=12; $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i<=$end_year; $i++){
		$list_years[] = $i;
	}
	$list_preloaders = array();
	for($i=0; $i<25; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng Lương Lãnh Đạo khối Kinh Doanh - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function soLanGap($a, $b) {
    if ($b == 0 || $b == 0) return 0;
    $ratio = $a / $b;
    if ($ratio >= 1 && $ratio < 1.5) {
        return 1;
    } elseif ($ratio >= 1.5 && $ratio < 2) {
        return 2;
    } elseif ($ratio >= 2) {
        return 3;
    } else {
        return 0;
    }
}
function default_list_salary(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	##
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	##
	$total_dep_staffs = 0;
	$arr_target_staff = array(_ROLE_HEAD_SALE => 5, _ROLE_GD_SALE => 12);
	$arr_target_billing = array(_ROLE_HEAD_SALE => 25, _ROLE_GD_SALE => 50);
	$arr_roles = array(_ROLE_GD_PROJECT, _ROLE_GD_SALE, _ROLE_HEAD_SALE, _ROLE_GD_SOP);
	$cond = "`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND `role_id` IN (".implode(',', $arr_roles).")";
	$cond.= " AND {$clsProfile->pkey}<>'"._PROFILE_TTQ_ID."'";
	$field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`avatar`,`department_id`,`role_id`,`team_id`";
	$list_staffs = $clsProfile->getAll($cond, $field);
	if(!empty($list_staffs)){
		$arr_property_cached = array();
		$total_dep_staffs = count($list_staffs);
		foreach($list_staffs as $key => $val){
			$team_id = (int) $val['team_id'];
			$role_id = (int) $val['role_id'];
			$department_id = (int) $val['department_id'];
			if($role_id == _ROLE_GD_PROJECT || $role_id == _ROLE_GD_SOP)
				$role_id = _ROLE_GD_SALE;
			$staff_id = (int) $val[$clsProfile->pkey];
			if($role_id > 0 && !isset($arr_property_cached[$role_id])){
				$arr_property_cached[$role_id] = $clsProperty->getTitle($role_id);
			}
			if($department_id > 0 && !isset($arr_property_cached[$department_id])){
				$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
			}
			$list_staffs[$key]['full_name'] = $clsProfile->getFullName($staff_id, $val);
			$list_staffs[$key]['avatar'] = $clsProfile->getAvatar($staff_id, $val, 40, 40);
			$list_staffs[$key]['role_name'] = $arr_property_cached[$role_id];
			$list_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			#
			$cond_staff = "`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND `department_id`='{$department_id}'";
			if($role_id == _ROLE_HEAD_SALE){
				$cond_staff.= " AND `team_id`='{$team_id}'";
			} else if(in_array($role_id, [_ROLE_GD_PROJECT, _ROLE_GD_SALE])){
				if($team_id > 0) {
					$cond_staff.= " AND `team_id`='{$team_id}'";
				}
			}
			###
			$total_staffs = 0; $arr_dep_staffs = array();
			$list_dep_staffs = $clsProfile->getAll($cond_staff, $clsProfile->pkey);
			if(!empty($list_dep_staffs)){
				$total_staffs = count($list_dep_staffs);
				foreach($list_dep_staffs as $okey => $oval){
					$arr_dep_staffs[] = $oval[$clsProfile->pkey];
				}
			}
			$list_staffs[$key]['total_staffs'] = $total_staffs;
			
			$start_date = strtotime('01-08-2025');
			$cond_billing = "`is_trash`=0 AND `is_cancel`=0 AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$my}'";
			$cond_billing.= " AND `staff_id` in (".implode(',', $arr_dep_staffs).")";
			$field = "`billing_source_id`,`billing_type`";
			// $dbconn->debug = true;
			$list_billings = $clsBilling->getAll($cond_billing, $field);
			$total_score = $total_f1 = $total_cross = $total_sop = 0;	
			if(!empty($list_billings)){
				foreach($list_billings as $okey => $oval){
					$billing_source_id = (int) $oval['billing_source_id'];
					$billing_type = (int) $oval['billing_type'];
					if($billing_source_id == _BILLING_RESOURCE_F1_ID 
						&& $billing_type != _BILLING_TYPE_TRANSFER_ID){
						$total_f1 += 5;
					} else if($billing_source_id == _BILLING_RESOURCE_CROSS_ID 
						&& $billing_type != _BILLING_TYPE_TRANSFER_ID){
						$total_cross += 2;
					} else if($billing_type == _BILLING_TYPE_TRANSFER_ID) {
						$total_sop += 1;
					}
				}
			}
			$salary = $percent_done = 0;
			$total_score = $total_f1 + $total_cross + $total_sop;
			$num_target = (int) $arr_target_billing[$role_id];
			$num_staff = (int) $arr_target_staff[$role_id];
			if($role_id == _ROLE_HEAD_SALE){
				$percent = soLanGap($total_score, $num_target);
				$salary = $percent * 5000000;
			} else if($role_id == _ROLE_GD_SALE){
				$percent = soLanGap($total_score, $num_target);
				$salary = $percent * 6000000;
			}
			$percent_done = round($total_score/$num_target, 2) * 100;
			$list_staffs[$key]['total_f1'] = $total_f1;
			$list_staffs[$key]['total_cross'] = $total_cross;
			$list_staffs[$key]['total_sop'] = $total_sop;
			$list_staffs[$key]['total_score'] = $total_score;
			$list_staffs[$key]['percent_done'] = $percent_done;
			$list_staffs[$key]['num_staff'] = $num_staff;
			$list_staffs[$key]['num_target'] = $num_target;
			$list_staffs[$key]['salary'] = $salary;
		}
		$salary_arrs = array_column($list_staffs, "total_score");
		array_multisort($salary_arrs, SORT_DESC, $list_staffs);
	}
	// $clsISO->print_pre($list_staffs); die();
	$smarty->assign('list_staffs', $list_staffs);
	// Return
	$html = $core->build('_ajax.salary.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_dep_staffs' => $total_dep_staffs
	)); die();
}
function default_share_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$clsProfile,$deviceType,$loggedIn;
	$clsStock = new Stock();
	
	$stock_id = (int)Input::post("stock_id",0);
	$res = ["result"=>false];
	if(!empty($stock_id)) {
		$arr_share = $clsStock->getContentShare($stock_id);
		$data_share = $arr_share["data_share"];
		$content = $arr_share["content"];
		$res = [
			"result"	=>	true,
			"data_share"	=>	$data_share,
			"content"	=>	$content,
		];
	}
	echo json_encode($res,JSON_UNESCAPED_UNICODE);die;
}