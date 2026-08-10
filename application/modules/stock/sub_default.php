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

function default_default(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO;

	$clsPage = new Page();

	$clsProfile = new Profile(); 

	$clsBilling = new Billing();

	$clsProperty = new Property();

	$clsStock = new Stock();

	$clsProject = new Project();

	$clsPolicy = new Policy();

	$assign_list['clsPage'] = $clsPage;

	$assign_list["clsProject"] = $clsProject;

	$assign_list["clsProperty"] = $clsProperty;

	###

	$list_preloaders = array();

	for($i=0; $i<20; $i++){

		$list_preloaders[] = $i;

	}

	$assign_list["list_preloaders"] = $list_preloaders;

	// $clsISO->print_pre($lst_projects); die();

    /*=============Title & Description Page==================*/

	$title_page = 'Bảng hàng độc quyền ' . BRAND_NAME . ' | ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $onePage['intro'] . ' | ' . BRAND_NAME;

	$assign_list["description_page"] = $description_page;

	$assign_list["keyword_page"] = $keyword_page;

}

function default_load_stock(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id;

	$clsProfile = new Profile(); 

	$clsProperty = new Property();

	$clsStock = new Stock();

	$clsProject = new Project();

	$clsPolicy = new Policy();

	$smarty->assign('clsProject', $clsProject);

	$smarty->assign('clsProperty', $clsProperty);

	##

	$project_id = Input::post('project_id', 0);

	$blocks_ids = Input::post('blocks_ids');

	$building_ids = Input::post('building_ids');

	$bedroom_ids = Input::post('bedroom_ids');

	$direction_ids = Input::post('direction_ids');

	$axis_ids = Input::post('axis_ids');

	$type_ids = Input::post('type_ids');

	$price_min = Input::post('price_min');

	$price_max = Input::post('price_max');

	$floor_range = Input::post('floor_range');

	$price_min = $clsISO->processSmartNumber($price_min);

	$price_max = $clsISO->processSmartNumber($price_max);

	$_ss_exclusive_search = array();

	$_ss_exclusive_search['project_id'] = $project_id;

	$_ss_exclusive_search['blocks_ids'] = $blocks_ids;

	$_ss_exclusive_search['building_ids'] = $building_ids;

	$_ss_exclusive_search['bedroom_ids'] = $bedroom_ids;

	$_ss_exclusive_search['direction_ids'] = $direction_ids;

	$_ss_exclusive_search['price_min'] = $price_min;

	$_ss_exclusive_search['price_max'] = $price_max;

	$_ss_exclusive_search['floor_range'] = $floor_range;

	$_ss_exclusive_search['axis_ids'] = $axis_ids;

	$_ss_exclusive_search['type_ids'] = $type_ids;

	vnSessionSetVar('_ss_exclusive_search', $_ss_exclusive_search);

	

	$sql_project = "`is_trash`=0";

	$sql_string = "`is_trash`=0";

	$sql_stock = "`t1`.`is_trash`=0";

	if($project_id > 0) {

		$sql_string.= " and `project_id`='{$project_id}'";

		$sql_stock.= " AND `t1`.`project_id`='{$project_id}'";

		$sql_project.= " AND `project_id`='{$project_id}'";

	} else {

		//$arr_project_ins = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID);

		//$sql_project.= " AND {$clsProject->pkey} in (".implode(',',$arr_project_ins).")";

	}

	if(!empty($blocks_ids)){

		$sql_string.= " AND `block_id` in (".implode(',',$blocks_ids).")";

		$sql_stock.= " AND `t1`.`block_id` in (".implode(',',$blocks_ids).")";

	}

	if(!empty($building_ids)){

		$sql_string.= " AND `building_id` in (".implode(',',$building_ids).")";

		$sql_stock.= " AND `t1`.`building_id` in (".implode(',',$building_ids).")";

	}

	if(!empty($bedroom_ids)){

		$sql_string.= " AND `bedroom_id` in (".implode(',',$bedroom_ids).")";

		$sql_stock.= " AND `t1`.`bedroom_id` in (".implode(',',$bedroom_ids).")";

	}

	if(!empty($direction_ids)){

		$sql_string.= " AND `home_direction_id` in (".implode(',', $direction_ids).")";

		$sql_stock.= " AND `t1`.`home_direction_id` in (".implode(',', $direction_ids).")";

	}

	if(!empty($agency_ids)){

		$sql_string.= " AND `agency_id` in (".implode(',',$agency_ids).")";

		$sql_stock.= " AND `t1`.`home_direction_id` in (".implode(',', $direction_ids).")";

	}

	if(!empty($type_ids)){

		$sql_string.= " AND `type_id` in (".implode(',',$type_ids).")";

		$sql_stock.= " AND `t1`.`type_id` in (".implode(',',$type_ids).")";

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

		$sql_string.= " AND (`floor` in ('".implode('\',\'', $list_floors)."'))";

		$sql_stock.= " AND (`t1`.`floor` in ('".implode('\',\'', $list_floors)."'))";

	}

	if(!empty($axis_ids)){

		$sql_string.= " AND (`code` in ('".implode('\',\'', $axis_ids)."'))";

		$sql_stock.= " AND (`t1`.`code` in ('".implode('\',\'', $axis_ids)."'))";

	}

	$sql_string.= " AND (`total_price_vat` between {$price_min} and {$price_max})";

	$sql_stock.= " AND (`t1`.`total_price_vat` between {$price_min} and {$price_max})";

	// $dbconn->debug = true;

	$total_all_stocks = 0;

	$field = "{$clsProject->pkey},`title`";

	$lst_projects = $clsProject->getAll($sql_project, $field);

	// $clsISO->print_pre($lst_projects); die();

	$bg_default = "#7c3c10";

	$color_default = "#FFF";

	$arr_order = [

		_PROJECT_DEF_ID => 1,

		_PROJECT_SLC_ID => 2,

		_PROJECT_MSQ_ID => 3,

	];

	#-----

	$start_time = strtotime("-14 days",strtotime(date("Y-m-d")));	

	$start_time_7 = strtotime("-7 days",strtotime(date("Y-m-d")));

	$start_time_3 = strtotime("-3 days",strtotime(date("Y-m-d")));

	$end_time = time();

	$clsBilling =  new Billing();

	$lstStockSold = $clsBilling->getAll("JSON_EXTRACT(`more_information`,\"$.billing_source_id\")='"._BILLING_RESOURCE_F1_ID."' AND `deposit_date` BETWEEN '{$start_time}' AND '{$end_time}' ");

	$arr_total_stock_sold = $arr_total_stock_sold_7 = $arr_total_stock_sold_3 = [];	

	foreach ($lstStockSold as $key => $val) {

		$more_billing = $clsISO->to_array_json($val["more_information"]);

		if(!isset($arr_total_stock_sold[$more_billing["block_id"]])) {

			$arr_total_stock_sold[$more_billing["block_id"]] = 1;

		}else{

			$arr_total_stock_sold[$more_billing["block_id"]] += 1;

		}

		if($val["deposit_date"] >= $start_time_7) {

			if(!isset($arr_total_stock_sold_7[$more_billing["block_id"]])) {

				$arr_total_stock_sold_7[$more_billing["block_id"]] = 1;

			}else{

				$arr_total_stock_sold_7[$more_billing["block_id"]] += 1;

			}

		}

		if($val["deposit_date"] >= $start_time_3) {

			if(!isset($arr_total_stock_sold_3[$more_billing["block_id"]])) {

				$arr_total_stock_sold_3[$more_billing["block_id"]] = 1;

			}else{

				$arr_total_stock_sold_3[$more_billing["block_id"]] += 1;

			}

		}

	}

	#

	$total_dq = $total_grand = $total_sold = $total_sold_7 = $total_sold_3 = 0;

	$arr_total_project = [];

	if(!empty($lst_projects)){

		$arr_property_cached = array();

		$arr_ins = array("_BEDROOM","_VIEW","_DIRECTION","_TYPE");

		$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type` in ('".implode('\',\'', $arr_ins)."')", "{$clsProperty->pkey},`title`");

		if(!empty($tmp)){

			foreach($tmp as $key => $val){

				$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];

			}

			unset($tmp);

		}

		foreach($lst_projects as $gkey => $gval){

			$is_hot = 0;

			$project_id = $gval[$clsProject->pkey];

			$order_no = !empty($arr_order[$project_id]) ? $arr_order[$project_id] : 100;

			$lst_projects[$gkey]['order_no'] = $order_no;

			$field = "{$clsProperty->pkey},`title`,`title_vn`,`property_code`,`more_information`";

			$list_blocks = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BLOCK' AND `for_id`='{$project_id}' 

			 AND parent_id='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL AND `{$clsProperty->pkey}` IN (SELECT `block_id` FROM `{$clsStock->tbl}` WHERE `agency_id`='"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_DQ_ID."')", $field);

			if(!empty($list_blocks)){

				foreach($list_blocks as $okey => $oval){

					$total_dq_proj = $total_grand_proj = 0;

					$block_id = $oval[$clsProperty->pkey];

					$order_no = 0;

					if(in_array($block_id, array(_PROJECT_BLOCK_MTS_ID, _PROJECT_BLOCK_MLS_ID))){

						$order_no = 1;

					}

					$list_blocks[$okey]['order_no'] = $order_no;

					$more_information_block = $oval['more_information'];

					$more_information_block = $clsISO->to_array_json($more_information_block);

					if(!empty($more_information_block["is_hot"])) {

						$is_hot = 1;

						$list_blocks[$okey]['is_hot'] = 1;

					}else{

						$list_blocks[$okey]['is_hot'] = 0;

					}

					$list_price_field = array();

					$price_field_configs = $core->get_field($more_information_block, "price_field_configs", []);

					foreach($price_field_configs as $nkey => $nval){

						if(isset($nval['status']) && $nval['status'] == 1){

							$list_price_field[$nkey] = $nval['title'];

						}

					}

					$list_blocks[$okey]['list_price_field'] = $list_price_field;

					$list_blocks[$okey]['bgcolor'] = !empty($more_information_block["bgcolor"]) ? $more_information_block["bgcolor"] : $bg_default;

					$list_blocks[$okey]['textcolor'] = !empty($more_information_block["textcolor"]) ? $more_information_block["textcolor"] : $bg_default;

					$total_stocks = $clsStock->countItem("{$sql_string} and `agency_id`='"._AGENCY_FH_ID."' 

					and `status_id`='"._STOCK_STATUS_DQ_ID."' and `block_id`='{$block_id}'");

					$total_all_stocks += $total_stocks;

					$list_blocks[$okey]['total_stocks'] = $total_stocks;

					if($total_stocks > 0){

						$list_buildings = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BUILDING' 

						AND `for_id`='{$block_id}' order by `order_no` ASC", $field);

						if(!empty($list_buildings)){

							foreach($list_buildings as $mkey => $mval){

								$total_stocks = 1;

								$building_id = $mval[$clsProperty->pkey];

								// $dbconn->debug = true;

								$list_stocks = $dbconn->getAll("select `t1`.* from {$clsStock->tbl} as `t1` 

								inner join {$clsProperty->tbl} as `t2` on `t1`.`bedroom_id`=`t2`.`property_id` AND `t2`.`property_type`='_BEDROOM' 

								where {$sql_stock}  and `t1`.`agency_id`='"._AGENCY_FH_ID."' AND `t1`.`status_id`='"._STOCK_STATUS_DQ_ID."' AND `t1`.`block_id`='{$block_id}' AND `t1`.`building_id`='{$building_id}' order by `t2`.`order_no` ASC");

								if(!empty($list_stocks)){

									$total_stocks += count($list_stocks);

									// $total_stocks += $total_in_stocks;

									foreach($list_stocks as $key => $val){

										$stock_id = $val[$clsStock->pkey];

										$type_id = $val['type_id'];

										$view_id = $val['view_id'];

										$project_id= $val['project_id'];

										$bedroom_id = $val['bedroom_id'];

										$home_direction_id = $val['home_direction_id'];

										$more_information = $val['more_information'];

										$more_information = $clsISO->to_array_json($more_information);

										$is_sp_mech = (int) $core->get_field($more_information, "is_sp_mech", 0);

										$list_stocks[$key]['is_sp_mech'] = $is_sp_mech;

										$list_stocks[$key]['DT_TT'] = $more_information['DT_TT'];

										$list_stocks[$key]['more_information'] = $more_information;

										$list_stocks[$key]['is_fund_type'] = $clsStock->checkStockFundType($stock_id,$building_id,$val,$mval);
										$bg_stock_dq = "#FFFFFF";
										if(!empty($more_information["stock_dq"])) {
											if($more_information["stock_dq"] == "LM") {
												$bg_stock_dq = "#f7eeea";
											}else{
												$bg_stock_dq = "#f5e10a69";
											}
										}

										$list_stocks[$key]['bg_stock_dq'] = $bg_stock_dq;

										###

										$html_stock_posters = "";

										$stock_posters = isset($more_information['stock_poster']) 

											? $more_information['stock_poster'] : array();

										if(!empty($stock_posters)){

											foreach($stock_posters as $nkey => $nval){

												$gg_id = $clsISO->getGoogleId($nval['image']);

												$html_stock_posters.= '<a href="javascript:void(0)" class="text-link text-nowrap text-upper fs-13" data-download-src="'.$clsISO->genGoogleURL($gg_id,'download').'" data-fancybox="gallery_'.$stock_id.'" onClick="$Core.helper.hideAll_webuipopver(this, event)" data-src="'.$clsISO->getGoogleUrl($nval['image']).'" data-caption="'.$nval['title'].'">Xem <i class="bx fs-12 bx-link-external"></i></a>';

											}

										}

										$list_stocks[$key]['html_stock_posters'] = $html_stock_posters;

										###

										$html_image_sheets = "";

										$price_sheets = $core->get_field($more_information, "price_sheets", []);

										if(!empty($price_sheets)){

											$price_sheets = @array_reverse($price_sheets);

											$oneSheet = reset($price_sheets);

											if(!empty($oneSheet)){

												foreach($oneSheet['sheets'] as $nkey => $nval){

													$html_image_sheets.= '<a class="text-link fs-13" target="_blank" href="'.$nval['image'].'">'.$nval['title'].'<i class=\'bx bx-link-external\'></i></a>';

												}

											}

										}

										$total_price_vat = 0;

										if(isset($more_information['total_price_vat']) && !empty($more_information['total_price_vat'])){

											$total_price_vat = $more_information['total_price_vat'];

											$total_price_vat = $clsISO->processSmartNumber($total_price_vat);

											$total_price_vat = $clsISO->priceFormatV3($total_price_vat, 3);

										}

										$list_stocks[$key]['total_price_vat'] = $total_price_vat;

										$list_stocks[$key]['html_image_sheets'] = $html_image_sheets;

										#

										$html_sales_policy = $html_price_sheets = "";

										if(isset($more_information['csbh']) && !empty($more_information['csbh'])){

											$csbh = $more_information['csbh'];

										} else {

											$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);

											$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' order by ms_date DESC limit 0,1");

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

											}

										}

										if($view_id > 0){

											if(isset($arr_property_cached[$view_id])){

												$list_stocks[$key]['view_name'] = $arr_property_cached[$view_id];

											}

										}

										if($bedroom_id > 0){

											if(isset($arr_property_cached[$bedroom_id])){

												$list_stocks[$key]['bedroom_name'] = $arr_property_cached[$bedroom_id];

											}

										}

										if($home_direction_id > 0){

											if(isset($arr_property_cached[$home_direction_id])){

												$list_stocks[$key]['home_direction_name'] = $arr_property_cached[$home_direction_id];

											}

										}

										#------

										++$total_dq_proj;

										++$total_dq;

										$total_grand_proj += (int)$more_information["total_price_vat"];

										$total_grand += (int)$more_information["total_price_vat"];

									}

								}

								$list_buildings[$mkey]['total_stocks'] = $total_stocks;

								$list_buildings[$mkey]['list_stocks'] = $list_stocks;

							}

						}

						//$list_blocks[$okey]['total_stocks'] = $total_stocks;

						$list_blocks[$okey]['list_buildings'] = $list_buildings;

					}					

					#------

					$total_sold_pj = isset($arr_total_stock_sold[$block_id]) ? $arr_total_stock_sold[$block_id] : 0;

					$total_sold += $total_sold_pj;

					if(isset($arr_total_stock_sold_7[$block_id])) {

						$total_sold_pj_7 = $arr_total_stock_sold_7[$block_id];

						$total_sold_7 += $total_sold_pj_7;

					}else{

						$total_sold_pj_7 = 0;

					}

					if(isset($arr_total_stock_sold_3[$block_id])) {

						$total_sold_pj_3 = $arr_total_stock_sold_3[$block_id];

						$total_sold_3 += $total_sold_pj_3;

					}else{

						$total_sold_pj_3 = 0;

					}

					if(!empty($more_information_block["is_project"])) {						

						$arr_total_project[] = [

							"project_name"	=>	$oval["title"],

							"total_dq"	=>	$total_dq_proj,

							"total_grand"	=>	$total_grand_proj,

							"total_sold"	=>	$total_sold_pj,

							"total_sold_7"	=>	!empty($total_sold_pj_7) ? $total_sold_pj_7 : 0,

							"total_sold_3"	=>	!empty($total_sold_pj_3) ? $total_sold_pj_3 : 0,

						];

					}else{						

						if(!isset($arr_total_project[$project_id])) {

							$arr_total_project[$project_id] = [

								"project_name"	=>	$gval["title"],

								"total_dq"	=>	$total_dq_proj,

								"total_grand"	=>	$total_grand_proj,

								"total_sold"	=>	$total_sold_pj,

								"total_sold_7"	=>	!empty($total_sold_pj_7) ? $total_sold_pj_7 : 0,

								"total_sold_3"	=>	!empty($total_sold_pj_3) ? $total_sold_pj_3 : 0,

							];

						}else{

							$arr_total_project[$project_id]["total_dq"] += $total_dq_proj;

							$arr_total_project[$project_id]["total_grand"] += $total_grand_proj;

							$arr_total_project[$project_id]["total_sold"] += $total_sold_pj;

							$arr_total_project[$project_id]["total_sold_7"] += !empty($total_sold_pj_7) ? $total_sold_pj_7 : 0;

							$arr_total_project[$project_id]["total_sold_3"] += !empty($total_sold_pj_3) ? $total_sold_pj_3 : 0;

						}							

					}					

				}

				$order_no_arrs = @array_column($list_blocks, 'is_hot');

				@array_multisort($order_no_arrs, SORT_DESC, $list_blocks);

			

			}

			$lst_projects[$gkey]['is_hot_pj'] = $is_hot;

			$lst_projects[$gkey]['list_blocks'] = $list_blocks;

		}

		 $order_no_arrs_pj = @array_column($lst_projects, 'is_hot_pj');

		 @array_multisort($order_no_arrs_pj, SORT_DESC, $lst_projects);

	}

	 $order_no_arrs = @array_column($arr_total_project, 'total_dq');

	 @array_multisort($order_no_arrs, SORT_DESC, $arr_total_project);

	$smarty->assign('arr_total_project', $arr_total_project);

	$smarty->assign('total_dq', $total_dq);

	$smarty->assign('total_sold', $total_sold);

	$smarty->assign('total_sold_7', $total_sold_7);

	$smarty->assign('total_sold_3', $total_sold_3);

	$smarty->assign('total_grand', $total_grand);

	$smarty->assign('lst_projects', $lst_projects);

	$smarty->assign('total_all_stocks', $total_all_stocks);

	// Return

	$html = $core->build('_ajax_stock.tpl');

	echo json_encode(array(

		'html' => $html

	)); die();

}

?>