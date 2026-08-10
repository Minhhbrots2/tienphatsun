<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsPolicy = new Policy();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	###
	$field = "{$clsProperty->pkey},title,more_information";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='"._PROJECT_DEF_ID."'", $field);
	if(!empty($list_blocks)){
		foreach($list_blocks as $okey => $oval){
			$block_id = $oval[$clsProperty->pkey];
			$more_information = $oval['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$price_field_configs = isset($more_information['price_field_configs']) 
				? $more_information['price_field_configs'] : array();
			$list_price_field = array();
			foreach($price_field_configs as $nkey => $nval){
				if(isset($nval['status']) && $nval['status'] == 1){
					$list_price_field[$nkey] = $nval['title'];
				}
			}
			$list_blocks[$okey]['list_price_field'] = $list_price_field;
			// $clsISO->print_pre($list_price_field); die();
			$list_stocks = $clsStock->getAll("`agency_id`='"._AGENCY_FH_ID."' 
				and `status_id`='"._STOCK_STATUS_DQ_ID."' and `block_id`='{$block_id}'");
			if(!empty($list_stocks)){
				$arr_property_cached = array();
				foreach($list_stocks as $key => $val){
					$type_id = $val['type_id'];
					$project_id= $val['project_id'];
					$building_id = $val['building_id'];
					$bedroom_id = $val['bedroom_id'];
					$home_direction_id = $val['home_direction_id'];
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_stocks[$key]['DT_TT'] = $more_information['DT_TT'];
					$list_stocks[$key]['more_information'] = $more_information;
					###
					$total_price_vat = 0;
					if(isset($more_information['total_price_vat']) && !empty($more_information['total_price_vat'])){
						$total_price_vat = $more_information['total_price_vat'];
						$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
						$total_price_vat = number_format((float) $clsISO->priceFormat($total_price_vat),3,'.','');
					}
					$list_stocks[$key]['total_price_vat'] = $total_price_vat;
					#
					$html_sales_policy = $html_price_sheets = "";
					if(isset($more_information['csbh']) && !empty($more_information['csbh'])){
						$csbh = $more_information['csbh'];
					} else {
						$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
						$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
						order by ms_date DESC limit 0,1");
						if(!empty($onePolicy)){
							$csbh = date('d/m/Y', $onePolicy['ms_date']);
							$html_sales_policy = '<a target="_blank" href="'.$onePolicy['link_ns'].'">'.$clsISO->makeIcon('bx-link-external','Mở link').'</a>';
							$html_price_sheets = '<a target="_blank" href="'.$onePolicy['link_ms'].'">'.$clsISO->makeIcon('bx-link-external','Mở link').'</a>';
						}
					}
					$list_stocks[$key]['csbh'] = $csbh;
					$list_stocks[$key]['html_sales_policy'] = $html_sales_policy;
					###
					if($type_id > 0){
						if(isset($arr_property_cached[$type_id])){
							$list_stocks[$key]['type_name'] = $arr_property_cached[$type_id];
						} else {
							$arr_property_cached[$type_id] = $clsProperty->getTitle($type_id);
							$list_stocks[$key]['type_name'] = $arr_property_cached[$type_id];
						}
					}
					if($block_id > 0){
						if(isset($arr_property_cached[$block_id])){
							$list_stocks[$key]['block_name'] = $arr_property_cached[$block_id];
						} else {
							$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
							$list_stocks[$key]['block_name'] = $arr_property_cached[$block_id];
						}
					}
					if($bedroom_id > 0){
						if(isset($arr_property_cached[$bedroom_id])){
							$list_stocks[$key]['bedroom_name'] = $arr_property_cached[$bedroom_id];
						} else {
							$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
							$list_stocks[$key]['bedroom_name'] = $arr_property_cached[$bedroom_id];
						}
					}
					if($home_direction_id > 0){
						if(isset($arr_property_cached[$home_direction_id])){
							$list_stocks[$key]['home_direction_name'] = $arr_property_cached[$home_direction_id];
						} else {
							$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
							$list_stocks[$key]['home_direction_name'] = $arr_property_cached[$home_direction_id];
						}
					}
				}
				$list_blocks[$okey]['list_stocks'] = $list_stocks;	
			} else {
				unset($list_blocks[$okey]);
			}
		}
	}
	$assign_list["list_blocks"] = $list_blocks;
	// $clsISO->print_pre($list_stocks); die();
}
function default_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsPolicy = new Policy();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	###
	$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
	$list_status = $clsProperty->getAllCache("property_type='_STATUS' order by order_no ASC", $field);
	$total_status = !empty($list_status) ? count($list_status) : 0;
	$status_bgcolor_arrs = $status_textcolor_arrs = array();
	if(!empty($list_status)){
		foreach($list_status as $key => $val){
			$status_bgcolor_arrs[$val[$clsProperty->pkey]] = $val['bgcolor'];
			$status_textcolor_arrs[$val[$clsProperty->pkey]] = $val['textcolor'];
		}
	}
	// $clsISO->print_pre($status_textcolor_arrs); die();
	$assign_list["list_status"] = $list_status;
	$assign_list["status_bgcolor_arrs"] = $status_bgcolor_arrs;
	$assign_list["status_textcolor_arrs"] = $status_textcolor_arrs;
	
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
	// $clsISO->print_pre($list_bedroom); die();
	$assign_list["bedroom_bgcolor_arrs"] = $bedroom_bgcolor_arrs;
	$assign_list["bedroom_textcolor_arrs"] = $bedroom_textcolor_arrs;
	###
	$project_id = (int) Input::get('project_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	$oneBuilding = $clsProperty->getOne($building_id);
	$block_id = $oneBuilding['for_id']; // BLOCK
	// Chính sách bán hàng
	$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
	$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' order by ms_date DESC limit 0,1");
	$more_information = $oneBuilding['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$list_help_links = isset($more_information['properties']) && !empty($more_information['properties']) 
		? $more_information['properties'] : array();
	$assign_list["onePolicy"] = $onePolicy;
	$assign_list["oneBuilding"] = $oneBuilding;
	$assign_list["more_information"] = $more_information;
	$assign_list["list_help_links"] = $list_help_links;
	
	$floor = isset($more_information['floor']) && !empty($more_information['floor']) 
		? $more_information['floor'] : "";
	$stock_templates = isset($more_information['template']) 
		? $more_information['template'] : array();
	$arr_floors = !empty($floor) ? explode(',', $floor) : array();
	#
	$arr_stocks = $arr_cols  = $arr_cells = array();
	$list_stocks = $clsStock->getAll("project_id='{$project_id}' and building_id='{$building_id}'");
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$code = $val['code'];
			$floor = $val['floor'];
			if(!empty($floor) && !in_array($floor, $arr_floors)){
				$arr_floors[] = $floor;
			}
			if(!empty($code) && !in_array($code, $arr_stocks)){
				//$arr_stocks[] = $code;
				//$arr_cols[$code] = $val;
			}
			$arr_cells[$floor][$code] = $val;
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
	//$clsISO->print_pre($arr_cells); die();
	if(!empty($stock_templates)){
		foreach($stock_templates as $key => $val){
			$arr_stocks[] = $val['code'];
			$arr_cols[$val['code']] = $val;
		}
	}
	//$clsISO->print_pre($arr_cols); die();
	$total_stocks = !empty($arr_stocks) ? count($arr_stocks) : 0;
	$assign_list["arr_floors"] = $arr_floors;
	$assign_list["arr_stocks"] = $arr_stocks;
	$assign_list["arr_cols"] = $arr_cols;
	$assign_list["arr_cells"] = $arr_cells;
	$assign_list["oneBuilding"] = $oneBuilding;
	$assign_list["total_rowspan"] = $total_stocks - $total_status*2;
	//$clsISO->print_pre($arr_stocks); die();
	$assign_list["project_id"] = $project_id;
	$assign_list["building_id"] = $building_id;
}
?>