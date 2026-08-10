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
$app->get('/project/get_blocks/{project_id}', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsProjectMeta = new ProjectMeta();
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$project_id = isset($args['project_id']) ? (int) $args['project_id'] : 0;
	if($project_id == 0){
		$apiresults = array(
			'error' => 1,
			'result' => 'error',
			'message' => "Project Id Not Found"
		);
	} else {
		$data = array();
		// $dbconn->debug = true;
		$field = "{$clsProperty->pkey},`property_code`,`property_type`,`title`,`for_id`,`parent_id`,`more_information`";
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}' order by `order_no` ASC", $field);
		if(!empty($list_blocks)){
			foreach($list_blocks as $key => $val){
				$block_id = $val[$clsProperty->pkey];
				$stock_type = (int) $val['parent_id'];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$property_type = ($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE ? '_BUILDING' : '_RANGE');
				$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='{$property_type}' 
					and `for_id`='{$block_id}' order by order_no ASC", $field);
				if(!empty($list_buildings)){
					foreach($list_buildings as $okey => $oval){
						$building_information = $oval['more_information'];
						$building_information = $clsISO->to_array_json($building_information);
						$list_buildings[$okey]['more_information'] = $building_information;
					}
				}
				$data[] = array(
					'block_id' => $block_id,
					'for_id' => $val['for_id'],
					'property_code' => $val['property_code'],
					'title' => $val['title'],
					'stock_type' => $val['parent_id'],
					'more_information' => $more_information,
					'list_buildings' => $list_buildings,
				);
			}
		}
		$apiresults = array(
			'error' => 0,
			'result' => 'success',
			'data' => $data
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/chatbot/check-access', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsProfile = new Profile();
	$clsZaloGroup = new ZaloGroup();
	$clsConfiguration = new Configuration();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$is_access = false;
	$inputs = $request->getParsedBody();
	$id_zalo = isset($inputs['id_zalo']) ? trim($inputs['id_zalo']) : "";
	$id_group = isset($inputs['id_group']) ? trim($inputs['id_group']) : "";
	$list_groups = $clsZaloGroup->getAll("`is_company_group`=1", "`id_group`,`more_information`,`status`");
	if(!empty($id_group) && !empty($list_groups)){
		foreach($list_groups as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($val['id_group'] == $id_group && (int) $val['status'] == 1){
				if((int) $core->get_field($more_information, "is_reply_owner") == 0){	
					$cachedFile = DIR_CACHE_JSON.'/zalo_account.json';
					if(@file_exists($cachedFile)){
						$decoder = new Webmozart\Json\JsonDecoder();		
						$zalo_accounts = $decoder->decodeFile($cachedFile);
						if(!in_array($id_zalo, $zalo_accounts)){
							$is_access = true;
						}
					}
				} else {
					$is_access = true;
				}
				break;
			}
		}
	}
	if($is_access == true){
		$apiresults = array(
			'error' => 0,
			'result' => 'success',
			'message' => 'Success'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/stock/check-is-agency', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsProfile = new Profile();
	$clsZaloGroup = new ZaloGroup();
	$clsConfiguration = new Configuration();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$is_access = false;
	$inputs = $request->getParsedBody();
	$id_zalo = isset($inputs['id_zalo']) ? trim($inputs['id_zalo']) : "";
	$id_group = isset($inputs['id_group']) ? trim($inputs['id_group']) : "";
	$list_groups = $clsZaloGroup->getAll("`is_company_group`=1", "`id_group`,`more_information`");
	if(!empty($id_group) && !empty($list_groups)){
		foreach($list_groups as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($val['id_group'] == $id_group){
				if((int) $core->get_field($more_information, "type_id", 0) == _ZALO_GROUP_TYPE_AGENCY_ID){
					$zalo_accounts = array();
					$cachedFile = DIR_CACHE_JSON.'/zalo_account.json';
					if(@file_exists($cachedFile)){
						$decoder = new Webmozart\Json\JsonDecoder();		
						$zalo_accounts = $decoder->decodeFile($cachedFile);
					}
					if(!in_array($id_zalo, $zalo_accounts)){
						$is_access = true;
					}
				}
				break;
			}
		}
	}
	if($is_access == true){
		$apiresults = array(
			'error' => 0,
			'result' => 'success',
			'message' => 'Success'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/check-permiss/group-zalo', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsZaloGroup = new ZaloGroup();
	$clsConfiguration = new Configuration();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$found = false;
	$inputs = $request->getParsedBody();
	$id_group = isset($inputs['id_group']) ? trim($inputs['id_group']) : "";
	$list_groups = $clsZaloGroup->getAll("`is_company_group`=1", "`id_group`,`more_information`");
	if(!empty($id_group) && !empty($list_groups)){
		foreach($list_groups as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($val['id_group'] == $id_group && (int) $core->get_field($more_information, "is_check_source", 0)== 1){
				$found = true;
				break;
			}
		}
	}
	if($found == true){
		$apiresults = array(
			'error' => 0,
			'result' => 'success',
			'message' => 'Success'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/check-permiss/can_list', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsZaloGroup = new ZaloGroup();
	$clsConfiguration = new Configuration();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$found = false;
	$inputs = $request->getParsedBody();
	$id_group = isset($inputs['id_group']) ? trim($inputs['id_group']) : "";
	$list_groups = $clsZaloGroup->getAll("`is_company_group`=1", "`id_group`,`more_information`");
	if(!empty($id_group) && !empty($list_groups)){
		foreach($list_groups as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($val['id_group'] == $id_group 
				&& $core->get_field($more_information, "is_can_list", 0)== 1){
				$found = true;
				break;
			}
		}
	}
	if($found == true){
		$apiresults = array(
			'error' => 0,
			'result' => 'success',
			'message' => 'Success'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/chat/logs', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$helper = new Helper();
	$clsChatLog = new ChatLog();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$inputs = $request->getParsedBody();
	$created_at = $inputs['created_at'] ? $inputs['created_at'] : 0;
	$data_logs = isset($inputs['data_logs']) ? $inputs['data_logs'] : [];
	if(!empty($data_logs)){
		// $dbconn->debug = true;
		$tmp = new DateTime($created_at);
		$created_at = $tmp->format('Y-m-d H:i:s');
		if($clsChatLog->insert(array(
			'data_logs' => json_encode($data_logs, JSON_UNESCAPED_UNICODE),
			'created_at' => $created_at
		))){
			$apiresults = array(
				'error' => 1,
				'result' => 'success',
				'message' => 'Success'
			);
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/get_layout', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$toa_nha = isset($inputs['toa_nha']) ? trim($inputs['toa_nha']) : "";
	$so_tang = isset($inputs['so_tang']) ? trim($inputs['so_tang']) : "";
	$so_truc_can = isset($inputs['so_truc_can']) ? trim($inputs['so_truc_can']) : "";
	if((!empty($so_tang) && !empty($toa_nha)) || (!empty($so_truc_can) && !empty($toa_nha))){
		$field = "more_information";
		$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_BUILDING' AND (`slug` like '%".$clsISO->replaceSpace($toa_nha)."%' 
			OR `property_code`='".strtoupper($toa_nha)."'
		) AND JSON_EXTRACT(`more_information`,\"$.is_out_stock\")=0", $field);
		// $clsISO->print_pre($tmp); die();
		if(!empty($tmp)){
			$more_information = $tmp['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(!empty($so_tang)){
				$img_desc = sprintf('tầng %s', $so_tang);
				$layout_ms = $core->get_field($more_information, "layout_ms", []);
				$img_layout = $core->get_field($more_information, "layout_ns", "");
				if(!empty($layout_ms)){
					foreach($layout_ms as $key => $val){
						$arr_floor = $clsProperty->getNumberFloor($val['title']);
						if(in_array($so_tang, $arr_floor)) {
							$img_layout = $val['image'];
							break;
						}
					}
				}
			} else {
				$img_desc = sprintf('trục %s', $so_truc_can);
				$template = $core->get_field($more_information, "template", []);
				$template_specical = $core->get_field($more_information, "template_specical", []);
				if(!empty($template)){
					foreach($template as $key => $val){
						if(trim($val['code']) == $so_truc_can){
							$img_layout = trim($val['layout']);
							break;
						}
					}
				}
			}
			if(!empty($img_layout)){
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'img_layout' => $clsISO->getGoogleUrl($img_layout),
					"img_desc" => sprintf('Layout %s, tòa %s. Link -> %s', $img_desc, $toa_nha, $img_layout)
				);
			}
		}	
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/search/get_stocks.cfg', function ($request, $response, $args) use ($app) {
	global $dbconn, $core, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$stock_type = isset($inputs['stock_type']) ? trim($inputs['stock_type']) : "cao_tang";
	$project_name = isset($inputs['project_name']) ? trim($inputs['project_name']) : "";
	$block_name = isset($inputs['block_name']) ? trim($inputs['block_name']) : "";
	$floor = isset($inputs['floor']) ? $inputs['floor'] : "";
	$building_name = isset($inputs['building_name']) ? trim($inputs['building_name']) : "";
	$range_name = isset($inputs['range_name']) ? trim($inputs['range_name']) : "";
	$DT_TT = isset($inputs['DT_TT']) ? (float) $inputs['DT_TT'] : 0;
	$bedroom_name = isset($inputs['loai_can']) ? trim($inputs['loai_can']) : "";
	$home_direction_name = isset($inputs['huong']) ? trim($inputs['huong']) : "";
	$so_luong = $core->get_field($inputs, "so_luong", 5);
	$muc_tieu = $core->get_field($inputs, "muc_tieu", "ASC");
	$truc_can = $core->get_field($inputs, "truc_can", ""); 
	$view_name = $core->get_field($inputs, "view_name", "");
	$arr_block_notin = array(_PROJECT_BLOCK_MWF_ID);
	###
	if((!empty($project_name) || !empty($block_name) || !empty($building_name) || !empty($range_name)) 
		&& (!empty($DT_TT) || !empty($bedroom_name) || !empty($home_direction_name) || !empty($floor) 
			|| !empty($muc_tieu) || !empty($so_luong) || !empty($truc_can) || !empty($view_name))){
		// AND `project_id`<>'"._PROJECT_VHGG_ID."
		$project_id = $block_id = 0;
		$cond = "`is_trash`=0 AND `agency_id`>0 AND `block_id` not in (".implode(',',$arr_block_notin).")";
		if(!empty($project_name)){
			$tmp = $clsProject->getByCond("`is_trash`=0 and (`slug` like '%".$core->replaceSpace($project_name)."%' 
				OR `code`='{$project_name}')", $clsProject->pkey);
			if(!empty($tmp)) {
				$project_id = $tmp[$clsProject->pkey];
				$cond.= " and `project_id`='".$tmp[$clsProject->pkey]."'";
				unset($tmp);
			} else {
				$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_BLOCK' AND (`slug` like '%".$core->replaceSpace($project_name)."%' 
					OR `slug_vn` like '%".$core->replaceSpace($project_name)."%' OR `property_code`='{$project_name}')", $clsProperty->pkey);
				if(!empty($tmp)) {
					$block_id =  $tmp[$clsProperty->pkey];
					$cond.= " AND `block_id`='{$tmp[$clsProperty->pkey]}'";
					unset($tmp);
				}
			}
		}
		if(!empty($block_name) && $block_id == 0){
			$sql_query = "`is_trash`=0 AND `property_type`='_BLOCK'";
			if($project_id > 0) $sql_query.= " AND `for_id`='{$project_id}'";
			$tmp = $clsProperty->getByCond("{$sql_query} AND (`slug` like '%".$core->replaceSpace($block_name)."%' 
				OR `slug_vn` like '%".$core->replaceSpace($block_name)."%' OR `property_code`='{$block_name}'
			) AND JSON_EXTRACT(`more_information`,\"$.on_sale\")='1'", $clsProperty->pkey);
			if(!empty($tmp)){
				$block_id = $tmp[$clsProperty->pkey];
				$cond.= " AND `block_id`='{$tmp[$clsProperty->pkey]}'";
				unset($tmp);
			}
		}
		if(!empty($range_name)){
			$tmp = $clsProperty->getByCond("`property_type`='_RANGE' AND (`slug`='".$core->replaceSpace($range_name)."' 
				OR `slug_vn`='".$clsISO->replaceSpace($range_name)."' 
				OR `property_code`='{$range_name}'
			)", $clsProperty->pkey);
			if(!empty($tmp)){
				$cond.= " AND `building_id`='{$tmp[$clsProperty->pkey]}'";
				unset($tmp);
			}
		}
		$number_floor = 0;		
		if(!empty($building_name)){
			$field = "{$clsProperty->pkey},title,more_information";
			$sql_query = "`is_trash`=0 AND `property_type`='_BUILDING'";
			if($block_id > 0) $sql_query.= " AND `for_id`='{$block_id}'";
			$tmp = $clsProperty->getByCond("{$sql_query} AND (`slug` LIKE '%".$clsISO->replaceSpace($building_name)."%' 
				OR `slug_vn` like '%".$clsISO->replaceSpace($building_name)."%' OR `property_code`='{$building_name}'
			) AND `for_id` NOT IN (".implode(',',$arr_block_notin).") AND JSON_EXTRACT(`more_information`,\"$.on_sale\")='1'", $field);
			if(!empty($tmp)){
				$more_information = $tmp['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$number_floor = isset($more_information['number_floor']) ? (int) $more_information['number_floor'] : 0;
				$cond.= " AND `building_id`='{$tmp[$clsProperty->pkey]}'";
				unset($tmp);
			}
		}
		if($stock_type == 'cao_tang'){
			$cond.= " AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
			if(!empty($bedroom_name)){
				$slug = $clsISO->replaceSpace($bedroom_name);
				$slug_not_p = str_replace('p', '', $slug);
				$tmp = $clsProperty->getAll("`property_type`='_BEDROOM' AND (`slug` like '%{$slug}%' 
					OR `slug_vn` like '%{$slug}%' OR `slug` like '%{$slug_not_p}%' 
					OR `slug_vn` like '%{$slug_not_p}%'
				)", $clsProperty->pkey);
				if(!empty($tmp)){
					$arr = array();
					foreach($tmp as $key => $val){
						$arr[] = $val[$clsProperty->pkey];
					}
					unset($tmp);
					$cond.= " AND `bedroom_id` in (".implode(',', $arr).")";
				}
			}
			if(!empty($floor)){
				if($clsISO->checkContainer($floor, '>', '')){
					$floor = @str_replace('>', '', $floor);
					$cond.= " and `floor`>'{$floor}'";
				} else if($clsISO->checkContainer($floor, '<', '')){
					$floor = @str_replace('<', '', $floor);
					$cond.= " and `floor`<'{$floor}'";
				} else {
					$floor_arrs = array();
					if(!in_array($floor, array('cao','thap','trung'))){
						if($clsISO->checkContainer($floor, ",", "")){
							$tmp = explode(',', $floor);
							if(!empty($tmp)){
								foreach($tmp as $val){
									if($clsISO->checkContainer($val,"-", "")){
										$tmp2 = explode('-', $val);
										$from_number = (int) $tmp2[0];
										$to_number = (int) $tmp2[1];
										if($from_number < $to_number){
											for($i=$from_number; $i<=$to_number; $i++){
												$floor_arrs[] = $i;
											}
										} else if($from_number > $to_number){
											for($i=$to_number; $i<=$from_number; $i++){
												$floor_arrs[] = $i;
											}
										} else if($from_number == $to_number){
											$floor_arrs[] = $i;
										}
									} else {
										$floor_arrs[] = $val;
									}
								}
							}
						} else if($clsISO->checkContainer($floor, "-", "")){
							$tmp = explode('-', $floor);
							$from_number = (int) $tmp[0];
							$to_number = (int) $tmp[1];
							if($from_number < $to_number){
								for($i=$from_number; $i<=$to_number; $i++){
									$floor_arrs[] = $i;
								}
							} else if($from_number > $to_number){
								for($i=$to_number; $i<=$from_number; $i++){
									$floor_arrs[] = $i;
								}
							} else if($from_number == $to_number){
								$floor_arrs[] = $i;
							}		
						} else {
							$floor_arrs[] = $floor;
						}
					} else {
						$floor_arrs = $helper->splitIntoThreeRanges($number_floor, $floor);
					}
					if(!empty($floor_arrs)){
						$floor_arrs = @array_unique($floor_arrs);
						$cond.= " and `floor` in(".implode(',', $floor_arrs).")";
					}	
				}
			}
		} else {
			$cond.= " AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
		}
		if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
			$cond.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."' 
			AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
		} else {
			$cond.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
		}
		if(!empty($DT_TT)){
			$cond.= " AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.DT_TT\"))='{$DT_TT}'";
		}
		if(!empty($home_direction_name)){
			$tmp = $clsProperty->getAll("`property_type`='_DIRECTION' AND (`slug` like '%".$clsISO->replaceSpace($home_direction_name)."%' 
				OR `slug_vn` like '%".$clsISO->replaceSpace($home_direction_name)."%' 
				OR property_code='{$home_direction_name}'
			)", $clsProperty->pkey);
			if(!empty($tmp)){
				$home_direction_arrs = array();
				foreach($tmp as $okey => $oval){
					$home_direction_arrs[] = $oval[$clsProperty->pkey];
				}
				$cond.= " AND `home_direction_id` in (".implode(',',$home_direction_arrs).")";
				unset($tmp);
			}
		}
		if(!empty($view_name)){
			$tmp = $clsProperty->getByCond("`is_locked`=0 AND `property_type`='_VIEW' AND (
				`slug` like '%".$clsISO->replaceSpace($view_name)."%' 
				OR `slug_vn` like '%".$clsISO->replaceSpace($view_name)."%' 
				OR property_code='{$view_name}'
			)", $clsProperty->pkey);
			if(!empty($tmp)){
				$cond.= " AND `view_id`='{$tmp[$clsProperty->pkey]}'";
				unset($tmp);
			}
		}
		if(!empty($truc_can)){
			$so_luong = 50;
			$cond.= " and `code`='{$truc_can}'";
		}
		// $dbconn->debug = true;
		$field = "{$clsStock->pkey},`stock_type`,`ms_code`,`bedroom_id`,`home_direction_id`,`project_id`,`block_id`,`building_id`";
		$field.= ",`status_id`,`type_id`,`agency_id`,`more_information`,IF(`agency_id`="._AGENCY_FH_ID.",1,0) AS `order_no`,IF(CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.total_price_early\")) AS UNSIGNED)>0,CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.total_price_early\")) AS UNSIGNED),`total_price_vat`) as `price_order_no`";
		if($muc_tieu=='ASC'){
			$order_by = " ORDER BY `order_no` DESC, `price_order_no` ASC";
		} else if($muc_tieu == 'DESC'){
			$order_by = " ORDER BY `order_no` DESC, `price_order_no` DESC";
		}
		$limitCond = ($so_luong > 0) ? sprintf(' LIMIT 0,%s', $so_luong) : " LIMIT 0,10";
		$list_stocks = $clsStock->getAll($cond.$order_by.$limitCond, $field);
		// $clsISO->print_pre($list_stocks); die();
		if(!empty($list_stocks)){ $ii = 1;
			$html_stock = ""; $list_founds = array();
			$total_stocks = count($list_stocks);
			$arr_property_cached = $arr_price_block_cached = array();
			foreach($list_stocks as $key => $val){
				$ms_code = $val['ms_code'];
				$project_id = (int) $val['project_id'];
				$status_id = (int) $val['status_id'];
				$agency_id = (int) $val['agency_id'];
				$stock_type = (int) $val['stock_type'];
				$block_id = (int) $val['block_id'];
				$building_id = (int) $val['building_id'];
				$bedroom_id = (int) $val['bedroom_id'];
				$type_id = (int) $val['type_id'];
				$home_direction_id = (int) $val['home_direction_id'];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(!isset($arr_price_block_cached[$block_id])){
					$list_price_field = array();
					$oneBlock = $clsProperty->getOne($block_id, "title,more_information");
					$block_information = $oneBlock['more_information'];
					$block_information = $clsISO->to_array_json($block_information);
					$price_field_configs = $core->get_field($block_information, "price_field_configs", []);
					foreach($price_field_configs as $nkey => $nval){
						if(isset($nval['status']) && $nval['status'] == 1){
							$list_price_field[$nkey] = $nval['title'];
						}
					}
					$arr_price_block_cached[$block_id] = array(
						'title' => $oneBlock['title'],
						'list_price_field' => $list_price_field
					);
				} else {
					$list_price_field = $arr_price_block_cached[$block_id]['list_price_field'];
				}
				if($bedroom_id > 0 && !isset($arr_property_cached[$bedroom_id])){
					$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
				}
				if($home_direction_id > 0 && !isset($arr_property_cached[$home_direction_id])){
					$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
				}
$html_stock.= ($agency_id==_AGENCY_FH_ID?'[Độc Quyền] ':'').$ms_code;
$html_stock.= '
';				
				if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
$html_stock.= sprintf('%s | Thông thủy: %s m² | %s', $arr_property_cached[$bedroom_id], $more_information['DT_TT'], $arr_property_cached[$home_direction_id]);
$html_stock.= '
';	
				} else {
$html_stock.= sprintf('%s | Thông thủy: %s m² | %s', $arr_property_cached[$type_id], $more_information['DT_TT'], $arr_property_cached[$home_direction_id]);
$html_stock.= '
';	
				}
				$is_full_vat = 0;
				if(!empty($more_information['total_price_vat'])){
					$is_full_vat = 1;
					$total_price_vat = $more_information['total_price_vat'];
					$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
$html_stock.= sprintf('Giá FULL VAT: %s tỷ', $clsISO->priceFormatV3($total_price_vat, 3));
				}
				if(!empty($list_price_field)){ $kk = 0;
					foreach($list_price_field as $mkey => $mval){
						if(!empty($more_information[$mkey])){
							$price = $more_information[$mkey];
$html_stock.= sprintf('%s%s: %s tỷ',($is_full_vat==1? ' | ' : ($kk==0?'' : ' | ')), $mval, $clsISO->priceFormatV3($price, 3));					
						}
						++$kk;
					}
$html_stock.= '
';
				} else { // Không có giá TTS...
$html_stock.= '
';	
				}
				#- PTG
				if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
					$price_sheets = $core->get_field($more_information, "price_sheets", []);
					if(!empty($price_sheets)){
$html_stock.= sprintf('PTG -> %s', $price_sheets);
$html_stock.= '
';
					}
				} else {
					$price_temporary_ns = $core->get_field($more_information, "price_temporary_ns", "");
					if(!empty($price_temporary_ns)){
$html_stock.= sprintf('%s %s', 'PTG TẠM TÍNH', $price_temporary_ns);
$html_stock.= '
';
					} else {
						$price_sheets = $core->get_field($more_information, "price_sheets", []);
						$oneSheet = @reset($price_sheets);
						if(!empty($oneSheet)){
							foreach($oneSheet['sheets'] as $val){
$html_stock.= sprintf('Link -> %s %s', $val['title'], $val['image']);
$html_price_sheets.= '
';
							}
						}
					}	
				}
				# Poster
				$stock_poster = $core->get_field($more_information, "stock_poster", []);
				if(!empty($stock_poster)){
					$stock_poster = @array_reverse($stock_poster);
					$onePoster = @reset($stock_poster);
$html_stock.= sprintf('Poster -> %s', $onePoster['image']);
$html_stock.= '
';
				}
				if($total_stocks > 1 && $ii < $total_stocks){
$html_stock.= "-------";
$html_stock.= '
';						
				}
				++$ii;
			}
			$apiresults = array(
				'error' => 0,
				'result' => 'success',
				'html_stock' => $html_stock
			);
		}  else {
			$html_stock = "Không có danh sách căn phù hợp với tiêu chí tìm kiếm !";
			if(!empty($project_name)){
$html_stock.= '
⦁ Dự án: '.$project_name;				
			} 
			if(!empty($block_name)) {
$html_stock.= '
⦁ Phân khu: '.$block_name;
			}
			if($stock_type == 'cao_tang' && !empty($building_name)){
$html_stock.= '
⦁ Tòa nhà: '.$building_name;					
			}
			if($stock_type == 'thap_tang' && !empty($range_name)){
$html_stock.= '
⦁ Dãy: '.$range_name;			
			}
			if(!empty($floor)){
$html_stock.= '
⦁ Tầng: '.$floor;				
			}
			if(!empty($DT_TT)){
$html_stock.= '
⦁ Diện tích: '.$DT_TT;				
			}
			if(!empty($bedroom_name)){
$html_stock.= '
⦁ Loại căn: '.$bedroom_name;				
			}
			if(!empty($home_direction_name)){
$html_stock.= '
⦁ Hướng: '.$home_direction_name;				
			}
			if(!empty($truc_can)){
$html_stock.= '
⦁ Trục căn: '.$truc_can;				
			}
			if(!empty($view_name)){
$html_stock.= '
⦁ View: '.$view_name;				
			}
			if(!empty($muc_tieu)){
$html_stock.= '
⦁ Mục tiêu: '.($muc_tieu == 'ASC' ? 'Rẻ nhất' : 'Đắt nhất');				
			}
			$apiresults = array(
				'error' => 1,
				'result' => 'error',
				'message' => "Error",
				'html_stock' => $html_stock
			);
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v0/search/get_stocks', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$stock_code = isset($inputs['stock_code']) ? trim($inputs['stock_code']) : "";
	if(!empty($stock_code)){
		$stock_code = strtoupper($stock_code);
		//  AND `project_id`<>'"._PROJECT_VHGG_ID."'
		$cond = "`is_trash`=0 AND `agency_id`>0";
		if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
			$cond.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
		} else {
			$cond.= " AND (`status_id`>0 
				AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
		}
		$field = "{$clsStock->pkey},stock_type,ms_code,bedroom_id,`home_direction_id`,`more_information`";
		$field.= ',`project_id`,`block_id`,`building_id`,`status_id`,`view_id`,`floor`,`code`,`agency_id`';
		if($clsISO->checkContainer($stock_code, "XX", "")){
			$extsearch = @str_replace('XX', 'XXX', $stock_code);
			$stock_code_more = str_replace('5A', '05A', $stock_code);
			$stock_code_more = str_replace('8A', '08A', $stock_code_more);
			$extsearch_more = str_replace('5A', '05A', $extsearch); 
			$extsearch_more = str_replace('8A', '08A', $extsearch_more); 
			$list_stocks = $clsStock->getAll("{$cond} and (`ms_code` like '%".str_replace('X','_',$stock_code)."%' 
				or `ms_code` like '%".str_replace('X','_',$stock_code_more)."%' 
				or `ms_code` like '%".str_replace('X','_',$extsearch)."%' 
				or `ms_code` like '%".str_replace('X','_',$extsearch_more)."%'
			) order by `code` ASC", $field);
			if(!empty($list_stocks)){ $ii = 1;
				$html_stock = ""; $list_founds = array();
				$total_stocks = count($list_stocks);
				$arr_property_cached = $arr_price_block_cached = array();
				$html_stock .= '{%list_floor%}';
				// Tìm vị trí theo trục hay tầng
				$vitriXX = $helper->viTriCuaXX($stock_code);
				if($vitriXX == '_center'){
					$block_id = (int) $list_stocks[0]['block_id'];
					$building_id = (int) $list_stocks[0]['building_id'];
					$bedroom_id = (int) $list_stocks[0]['bedroom_id'];
					$home_direction_id = (int) $list_stocks[0]['home_direction_id'];
					$more_information = $list_stocks[0]['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
$html_stock.= sprintf('Phân khu: %s | Tòa: %s', $clsProperty->getTitle($block_id), $clsProperty->getTitle($building_id));
$html_stock.= '
';
					if($vitriXX == '_last'){
$html_stock.= '
';	
					} else if($vitriXX == '_center') {
$html_stock.= '
';						
					}
$html_stock.= sprintf('Loại: %s | Thông thủy: %s m² | Hướng: %s', $clsProperty->getTitle($bedroom_id), $more_information['DT_TT'], $clsProperty->getTitle($home_direction_id));
$html_stock.= '
-------';
$html_stock.= '
';		
					foreach($list_stocks as $key => $val){
						$code = $val['code'];
						$floor = $val['floor'];
						$ms_code = $val['ms_code'];
						$project_id = (int) $val['project_id'];
						$status_id = (int) $val['status_id'];
						$agency_id = (int) $val['agency_id'];
						$stock_type = (int) $val['stock_type'];
						$block_id = (int) $val['block_id'];
						$building_id = (int) $val['building_id'];
						$bedroom_id = (int) $val['bedroom_id'];
						$home_direction_id = (int) $val['home_direction_id'];
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
							$check_field = $floor;
							if($vitriXX == '_last'){
								$check_field = $code;
							}
							if(!in_array($check_field, $list_founds)){
								$list_founds[] = $check_field;
							}
							if(!isset($arr_price_block_cached[$block_id])){
								$list_price_field = array();
								$oneBlock = $clsProperty->getOne($block_id, "more_information");
								$block_information = $oneBlock['more_information'];
								$block_information = $clsISO->to_array_json($block_information);
								$price_field_configs = isset($block_information['price_field_configs']) 
									? $block_information['price_field_configs'] : array();
								foreach($price_field_configs as $nkey => $nval){
									if(isset($nval['status']) && $nval['status'] == 1){
										$list_price_field[$nkey] = $nval['title'];
									}
								}
								$arr_price_block_cached[$block_id] = $list_price_field;
							} else {
								$list_price_field = $arr_price_block_cached[$block_id];
							}
$html_stock.= ($agency_id==_AGENCY_FH_ID?'[Độc Quyền] ':'').$ms_code;
$html_stock.= '
';								$is_full_vat = 0;
							if(!empty($more_information['total_price_vat'])){
								$is_full_vat = 1;
								$total_price_vat = $more_information['total_price_vat'];
								$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
$html_stock.= sprintf('Giá FULL VAT: %s tỷ', $clsISO->priceFormatV3($total_price_vat, 3));
							}
							if(!empty($list_price_field)){ $kk = 0;
								foreach($list_price_field as $mkey => $mval){
									if(!empty($more_information[$mkey])){
										$price = $more_information[$mkey];
$html_stock.= sprintf('%s%s: %s tỷ',($is_full_vat==1? ' | ' : ($kk==0?'' : ' | ')), $mval, $clsISO->priceFormatV3($price, 3));					
									}
									++$kk;
								}
$html_stock.= '
';
							} else { // Không có giá TTS...
$html_stock.= '
';	
							}
							#- PTG
							$price_sheets = isset($more_information['price_sheets']) 
								? $more_information['price_sheets'] : array();
							if(!empty($price_sheets)){
$html_stock.= sprintf('PTG -> %s', $price_sheets);
$html_stock.= '
';
							}
							# Poster
							$stock_poster = isset($more_information['stock_poster']) 
								? $more_information['stock_poster'] : array();
							if(!empty($stock_poster)){
								$stock_poster = @array_reverse($stock_poster);
								$onePoster = @reset($stock_poster);
$html_stock.= sprintf('Poster -> %s', $onePoster['image']);
$html_stock.= '
';
							}
						}
						if($total_stocks > 1 && $ii < $total_stocks){
$html_stock.= "-------";
$html_stock.= '
';						
						}
						++$ii;
					}
				} else {
					foreach($list_stocks as $key => $val){
						$code = $val['code'];
						$floor = $val['floor'];
						$ms_code = $val['ms_code'];
						$block_id = (int) $val['block_id'];
						$project_id = (int) $val['project_id'];
						$building_id = (int) $val['building_id'];
						$status_id = (int) $val['status_id'];
						$agency_id = (int) $val['agency_id'];
						$bedroom_id = (int) $val['bedroom_id'];
						$stock_type = (int) $val['stock_type'];
						$home_direction_id = (int) $val['home_direction_id'];
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
							$check_field = $floor;
							if($vitriXX == '_last'){
								$check_field = $code;
							}
							if(!in_array($check_field, $list_founds)){
								$list_founds[] = $check_field;
							}
							if($bedroom_id > 0 && !isset($arr_property_cached[$bedroom_id])){
								$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
							}
							if($block_id > 0 && !isset($arr_property_cached[$block_id])){
								$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
							}
							if($building_id > 0 && !isset($arr_property_cached[$building_id])){
								$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
							}
							if($home_direction_id > 0 && !isset($arr_property_cached[$home_direction_id])){
								$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
							}
							if(!isset($arr_price_block_cached[$block_id])){
								$list_price_field = array();
								$oneBlock = $clsProperty->getOne($block_id, "more_information");
								$block_information = $oneBlock['more_information'];
								$block_information = $clsISO->to_array_json($block_information);
								$price_field_configs = isset($block_information['price_field_configs']) 
									? $block_information['price_field_configs'] : array();
								foreach($price_field_configs as $nkey => $nval){
									if(isset($nval['status']) && $nval['status'] == 1){
										$list_price_field[$nkey] = $nval['title'];
									}
								}
								$arr_price_block_cached[$block_id] = $list_price_field;
							} else {
								$list_price_field = $arr_price_block_cached[$block_id];
							}
$html_stock.= ($agency_id==_AGENCY_FH_ID?'[Độc Quyền]':'').'Căn '.$ms_code;
$html_stock.= '
';
$html_stock.= sprintf('Phân khu: %s | Tòa: %s', $arr_property_cached[$block_id], $arr_property_cached[$building_id]);
$html_stock.= '
';
$html_stock.= sprintf('Loại: %s | Thông thủy: %s m² | Hướng: %s', $arr_property_cached[$bedroom_id], $more_information['DT_TT'], $arr_property_cached[$home_direction_id]);
$html_stock.= '
';
							if(!empty($more_information['total_price_vat'])){
								$total_price_vat = $more_information['total_price_vat'];
								$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
$html_stock.= sprintf('Giá FULL VAT: %s tỷ', $clsISO->priceFormatV3($total_price_vat, 3));
$html_stock.= '
';					}
if(!empty($list_price_field)){
	foreach($list_price_field as $mkey => $mval){
		if(!empty($more_information[$mkey])){
			$price = $more_information[$mkey];
$html_stock.= sprintf('%s: %s tỷ', $mval, $clsISO->priceFormatV3($price, 3));
$html_stock.= '
';					
		}
	}
}
							#- PTG
							$price_sheets = isset($more_information['price_sheets']) ? $more_information['price_sheets'] : array();
							if(!empty($price_sheets)){
$html_stock.= sprintf('PTG -> %s', $price_sheets);
$html_stock.= '
';
							}
							# Poster
							$stock_poster = isset($more_information['stock_poster']) 
								? $more_information['stock_poster'] : array();
							if(!empty($stock_poster)){
								$stock_poster = @array_reverse($stock_poster);
								$onePoster = @reset($stock_poster);
$html_stock.= sprintf('Poster -> %s', $onePoster['image']);
$html_stock.= '
';
							}
						}
						if($total_stocks > 1 && $ii<$total_stocks){
$html_stock.= "-------";
$html_stock.= '
';						
						}
						++$ii;
					}
				}
				if(!empty($list_founds)){
					if($vitriXX == '_center'){
						$html_stock = str_replace('{%list_floor%}', sprintf('Còn căn thuộc tầng %s
-------
', implode(',', $list_founds)), $html_stock);
					} else if($vitriXX == '_last') {
						$html_stock = str_replace('{%list_floor%}', sprintf('Còn căn thuộc trục %s
-------
', implode(',', $list_founds)), $html_stock);
					}
				} else {
					$html_stock = str_replace('{%list_floor%}','', $html_stock);
				}
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'html_stock' => $html_stock
				);
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/search/get_stocks', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsZaloGroup = new ZaloGroup();
	$clsConfiguration = new Configuration();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$id_group = isset($inputs['id_group']) ? trim($inputs['id_group']) : "";
	$stock_code = isset($inputs['stock_code']) ? trim($inputs['stock_code']) : "";
	if(!empty($stock_code)){
		$stock_code = strtoupper($stock_code);
		// AND `project_id`<>'"._PROJECT_VHGG_ID."'
		$cond = "`is_trash`=0 AND `agency_id`>0";
		if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
			$cond.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
		} else {
			$cond.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
		}
		$is_check_full = 1; // Có quyền check full không
		$list_groups = $clsZaloGroup->getAll("`is_company_group`=1", "`id_group`,`more_information`");
		if(!empty($id_group) && !empty($list_groups)){
			foreach($list_groups as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if($val['id_group'] == $id_group){
					$is_check_full = (int) $core->get_field($more_information, "is_check_full", 0);
					break;
				}
			}
			unset($list_groups);
		}
		if($is_check_full == 0){
			$cond.= " AND `agency_id`='"._AGENCY_FH_ID."'";
		}
		$field = "{$clsStock->pkey},stock_type,bedroom_id,`home_direction_id`,`more_information`";
		$field.= ',`project_id`,`block_id`,`building_id`,`floor`,`code`,`status_id`';
		if($clsISO->checkContainer($stock_code, "XX", "")){
			// Tìm vị trí theo trục hay tầng
			$vitriXX = $helper->viTriCuaXX($stock_code);	
			$extsearch = @str_replace('XX', 'XXX', $stock_code);
			$stock_code_more = str_replace('5A', '05A', $stock_code);
			$stock_code_more = str_replace('8A', '08A', $stock_code_more);
			$extsearch_more = str_replace('5A', '05A', $extsearch); 
			$extsearch_more = str_replace('8A', '08A', $extsearch_more); 
			$order_by = " order by `floor` ASC";
			if($vitriXX == '_last'){
				$order_by = " order by `code` ASC";
			}
			$list_stocks = $clsStock->getAll("{$cond} and (`ms_code` like '".str_replace('X','_',$stock_code)."' 
				or `ms_code` like '".str_replace('X','_',$stock_code_more)."' 
				or `ms_code` like '".str_replace('X','_',$extsearch)."' 
				or `ms_code` like '".str_replace('X','_',$extsearch_more)."'
			)".$order_by, $field);
			// var_dump($list_stocks); die();
			if(!empty($list_stocks)){ $ii = 1;
				$html_stock = ""; 
				$list_founds = array();
				$stock_type = $list_stocks[0]['stock_type'];
				$block_id = (int) $list_stocks[0]['block_id'];
				$building_id = (int) $list_stocks[0]['building_id'];
				$bedroom_id = (int) $list_stocks[0]['bedroom_id'];
				$home_direction_id = (int) $list_stocks[0]['home_direction_id'];
				$more_information = $list_stocks[0]['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
$html_stock.= sprintf('Phân khu: %s | Tòa: %s', $clsProperty->getTitle($block_id), $clsProperty->getTitle($building_id));
$html_stock.= '
';
					if($vitriXX == '_last'){
						$a = explode('XX', $stock_code);
$html_stock.= '-- Tầng '.$a[1].'--
';	
					} else if($vitriXX == '_center') {
						$a = explode('XX', $stock_code);
$html_stock.= '-- Trục '.$a[1].'--
';						
					}
$html_stock.= sprintf('Loại: %s | Thông thủy: %s m² | Hướng: %s', $clsProperty->getTitle($bedroom_id), $more_information['DT_TT'], $clsProperty->getTitle($home_direction_id));
$html_stock.= '
-------';
$html_stock.= '
';
				} else {
$html_stock.= sprintf('Phân khu: %s | Dãy: %s', $clsProperty->getTitle($block_id), $clsProperty->getTitle($building_id));
$html_stock.= '
';
$html_stock.= sprintf('DT Đất: %s m² | DT XD: %s m² | Hướng: %s', $more_information['DT_TT'], $more_information['DT_Tim'], $clsProperty->getTitle($home_direction_id));
$html_stock.= '
-------';
$html_stock.= '
';
				}
				foreach($list_stocks as $key => $val){
					$code = $val['code'];
					$floor = $val['floor'];
					$stock_type = $val['stock_type'];
					if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
						$check_field = $floor;
						if($vitriXX == '_last'){
							$check_field = $code;
						}
						if(!in_array($check_field, $list_founds)){
							$list_founds[] = $check_field;
						}
					} else {
						if(!in_array($code, $list_founds)){
							$list_founds[] = $code;
						}
					}
					++$ii;
				}
				$html_stock .= '{%list_floor%}';
				if(!empty($list_founds)){
					if($vitriXX == '_center'){
						$t = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? " thuộc tầng" : "";
						$html_stock = str_replace('{%list_floor%}', sprintf('Còn căn%s %s', $t, implode(',', $list_founds)), $html_stock);
					} else if($vitriXX == '_last') {
						$t = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? " thuộc trục" : "";
						$html_stock = str_replace('{%list_floor%}', sprintf('Còn căn%s %s', $t, implode(',', $list_founds)), $html_stock);
					}
				} else {
					$html_stock = str_replace('{%list_floor%}','', $html_stock);
				}
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'html_stock' => $html_stock
				);
			} else {
				if($vitriXX == '_center'){
					$html_stock = sprintf('Tầng %s đã hết !', $stock_code);
				} else {
					$html_stock = sprintf('Trục %s đã hết !', $stock_code);
				}
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'html_stock' => $html_stock
				);
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/stock/update-sold', function ($request, $response, $args) use ($app) {
	global $dbconn, $core, $clsISO;
	$clsHelper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$arr_MDC = [_PROJECT_MRD_ID, _PROJECT_CSD_ID, _PROJECT_NTD_ID];
	$project_name = isset($inputs['project']) ? $inputs['project'] : "";
	$units = isset($inputs['units']) ? $inputs['units'] : [];
	if(!empty($units)){
		$sql_query = "`is_trash`=0";
		$project_id = $block_id = 0;
		if(!empty($project_name)){
			$tmp = $clsProject->getByCond("`is_trash`=0 
				and (`code`='{$project_name}' or `slug` like '%".$core->replaceSpace($project_name)."%')", $clsProject->pkey);
			if(!empty($tmp)){
				$project_id = $tmp[$clsProject->pkey];
				$sql_query.= " AND `project_id`='{$project_id}'";
			} else {
				$x_field = "{$clsProperty->pkey},`for_id`";
				$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_BLOCK' 
					AND (`property_code`='{$project_name}' or `slug` like '%".$core->replaceSpace($project_name)."%')", $x_field);
				if(!empty($tmp)){
					$project_id = $tmp['for_id'];
					$block_id = $tmp[$clsProperty->pkey];
					$sql_query.= " AND `block_id`='{$block_id}'";
				}
			}
		}
		$html_stock = ""; $is_sucess = false;
		foreach($units as $stock_code){
			// $dbconn->debug = true;
			$field = "{$clsStock->pkey},`stock_type`,`block_id`,`building_id`,`bedroom_id`,`home_direction_id`,`more_information`";
			$tmp = $clsStock->getByCond("{$sql_query} AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' 
			AND `ms_code`='".trim($stock_code)."'", $field);
			// $clsISO->print_pre($tmp); die();
			if(!empty($tmp)){
				$stock_type = (int) $tmp['stock_type'];
				$block_id = (int) $tmp['block_id'];
				$building_id = (int) $tmp['building_id'];
				$bedroom_id = (int) $tmp['bedroom_id'];
				$home_direction_id = (int) $tmp['home_direction_id'];
				$more_information = $tmp['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if($clsStock->updateOne($tmp[$clsStock->pkey], array(
					'status_id' => _STOCK_STATUS_SOLD_ID,
					'ms_date' => time(),
				))){
					$is_sucess = true;
					$arr_property = $arr_property_cached = array();
					if($block_id > 0) $arr_property[] = $block_id;
					if($building_id > 0) $arr_property[] = $building_id;
					if($bedroom_id > 0) $arr_property[] = $bedroom_id;
					if($home_direction_id > 0) $arr_property[] = $home_direction_id;
					if(!empty($arr_property)){
						$prop_field = "{$clsProperty->pkey},`title`";
						$bmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_property).")", $prop_field);
						if(!empty($bmp)){
							foreach($bmp as $okey => $oval){
								$arr_property_cached[$oval[$clsProperty->pkey]] = $oval['title'];
							}
						}
					}
$html_stock.= sprintf('💔 Đã bán %s', $stock_code);
$html_stock.= "
";
					if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
$html_stock.= sprintf("%s | Tòa: %s", $arr_property_cached[$block_id], $arr_property_cached[$building_id]);
$html_stock.= "
";
$html_stock.= sprintf("%s | Diện tích %s m² | %s", $arr_property_cached[$bedroom_id] ,$more_information['DT_TT']
, $arr_property_cached[$home_direction_id]);
					} else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
$html_stock.= sprintf("%s | Dãy: %s", $arr_property_cached[$block_id], $arr_property_cached[$building_id]);
$html_stock.= "
";
$html_stock.= sprintf("Diện tích %s m² | Hướng: %s", $more_information['DT_TT'], $arr_property_cached[$home_direction_id]);
					}
$html_stock.= "
-----
";				}
			}
		}
		if($is_sucess){
			$project_name = 'HN';
			$apiresults = array(
				'error' => 0,
				'result' => 'success',
				'message' => 'Success',
				'project_name' => $project_name,
				'html_stock' => $html_stock
			);
		} else {
			$apiresults = array(
				'error' => 1,
				'result' => 'error',
				'message' => 'Error'
			);
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/stock/get_total.cfg', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$date_type = isset($inputs['date_type']) ? trim($inputs['date_type']) : "24h";
	$block_name = isset($inputs['block_name']) ? trim($inputs['block_name']) : "";
	if(!empty($block_name)){
		$html_stock = "";
		if($clsISO->checkContainer($block_name,",","")){
			$arr_blocks = array(); 
			$block_name = preg_replace('/\s+/', ' ', $block_name); // Remove multple space to single space
			$block_name = preg_replace('/\,\s+/', ',', $block_name); // Remove space after (,)
			$block_name = preg_replace('/\s+\,/', ',', $block_name); // Remove space before (,)
			$parts = explode(',', $block_name);
			foreach($parts as $block_name){
				$field = "{$clsProperty->pkey},`title`";
				$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`property_code`='{$block_name}' 
					OR `slug` like '%".$clsISO->replaceSpace($block_name)."%')", $field);
				if(!empty($tmp)){
					$block_id = $tmp[$clsProperty->pkey];
					$arr_blocks[$block_id] = array(
						'tp' => 'block',
						'title' => $tmp['title']
					);
				} else {
					$field = "{$clsProject->pkey},`title`";
					$tmp = $clsProject->getByCond("(`code`='{$block_name}' OR `slug` LIKE '%".$clsISO->replaceSpace($block_name)."%')", $field);
					if(!empty($tmp)){
						$project_id = $tmp[$clsProject->pkey];
						$arr_blocks[$project_id] = array(
							'tp' => 'project',
							'title' => $tmp['title']
						);
					}
				}
			}
			if(!empty($arr_blocks)){
				if($date_type == '24h'){
					$end_time = time();
					$start_time = strtotime('-24 hours', $end_time);
					$text_time = '24h qua';
				} else if($date_type == 'yesterday') {
					$curr_date = date('d-m-Y', strtotime("-1 days"));
					$start_time = strtotime("{$curr_date} 00:00:00");
					$end_time = strtotime("{$curr_date} 23:59:59");
					$text_time = $curr_date;
				} else if($date_type == 'this_week') {
					$bmp = $clsISO->getRangeTime('THIS_WEEK');
					$curr_date = date('d-m-Y', strtotime("-1 days"));
					$start_time = strtotime("{$curr_date} 00:00:00");
					$end_time = strtotime("{$curr_date} 23:59:59");
					$text_time = 'tuần này';
				} else if($date_type == 'last_week') {
					$bmp = $clsISO->getRangeTime('PREV_WEEK');
					$start_time = $bmp['start_date'];
					$end_time = $bmp['due_date'];
					$text_time = 'tuần trước';
				} else if($date_type == 'this_month') {
					$bmp = $clsISO->getRangeTime('THIS_MONTH');
					$start_time = $bmp['start_date'];
					$end_time = $bmp['due_date'];
					$text_time = 'tháng này';
				} else if($date_type == 'last_month') {
					$bmp = $clsISO->getRangeTime('PREV_MONTH');
					$start_time = $bmp['start_date'];
					$end_time = $bmp['due_date'];
					$text_time = 'tháng trước';
				} else if($clsISO->checkContainer($date_type,"|","")){
					$bmp = @explode('|', $date_type);
					$start_time = strtotime(str_replace('/','-',$tmp[0]));
					$end_time = strtotime(str_replace('/','-',$tmp[1]) . " 23:59:59");
					$text_time = "{$bmp[0]} tới {$bmp[1]}";
				} else if($clsISO->is_validate_date($date_type)) {
					$start_time = strtotime(sprintf('%s %s', str_replace('/','-', $date_type), '00:00:00'));
					$end_time = strtotime(sprintf('%s %s', str_replace('/','-', $date_type), '23:59:59'));
					$text_time = $date_type;
				} 
$html_stock.= sprintf('Thống kê căn bán %s', $text_time);
$html_stock.= '
--	
';				
				$total_blocks = count($arr_blocks); $ii = 1;
				foreach($arr_blocks as $block_id => $_oBlock){
					$block_name = $_oBlock['title'];
					if($_oBlock['tp'] == 'block'){
						$cond = "`is_trash`=0 AND `block_id`='{$block_id}'";
					} else {
						$cond = "`is_trash`=0 AND `project_id`='{$block_id}'";
					}
					$total = $clsStock->countItem("{$cond} AND `status_id`='"._STOCK_STATUS_SOLD_ID."' AND (`ms_date` BETWEEN {$start_time} AND {$end_time})");
					if($total > 0){
$html_stock.= sprintf('💔 %s', $block_name);
$html_stock.='
';
$html_stock.= sprintf('-- %s căn bán', $total, $text_time);
						if($ii < $total_blocks){
$html_stock.='
';					
						}						
					}
					++$ii;
				} 
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'html_stock' => $html_stock
				);
			}
		} else {
			$is_project = false;
			$field = "{$clsProperty->pkey},`title`";
			$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`property_code`='{$block_name}' 
				OR `slug` like '%".$clsISO->replaceSpace($block_name)."%')", $field);
			if(empty($tmp)){
				$field = "{$clsProject->pkey},`title`";
				$tmp = $clsProject->getByCond("`code`='{$block_name}' OR `slug` LIKE '%".$clsISO->replaceSpace($block_name)."%'", $field);
				$is_project = true;
			}
			if(!empty($tmp)){
				if($date_type == '24h'){
					$end_time = time();
					$start_time = strtotime('-24 hours', $end_time);
					$text_time = '24h qua';
				} else if($date_type == 'yesterday') {
					$curr_date = date('d-m-Y', strtotime("-1 days"));
					$start_time = strtotime("{$curr_date} 00:00:00");
					$end_time = strtotime("{$curr_date} 23:59:59");
					$text_time = 'ngày hôm qua';
				} else if($date_type == 'this_week') {
					$bmp = $clsISO->getRangeTime('THIS_WEEK');
					$curr_date = date('d-m-Y', strtotime("-1 days"));
					$start_time = strtotime("{$curr_date} 00:00:00");
					$end_time = strtotime("{$curr_date} 23:59:59");
					$text_time = 'tuần này';
				} else if($date_type == 'last_week') {
					$bmp = $clsISO->getRangeTime('PREV_WEEK');
					$start_time = $bmp['start_date'];
					$end_time = $bmp['due_date'];
					$text_time = 'tuần trước';
				} else if($date_type == 'this_month') {
					$bmp = $clsISO->getRangeTime('THIS_MONTH');
					$start_time = $bmp['start_date'];
					$end_time = $bmp['due_date'];
					$text_time = 'tháng này';
				} else if($date_type == 'last_month') {
					$bmp = $clsISO->getRangeTime('PREV_MONTH');
					$start_time = $bmp['start_date'];
					$end_time = $bmp['due_date'];
					$text_time = 'tháng trước';
				} else if($clsISO->checkContainer($date_type,"|","")){
					$bmp = @explode('|', $date_type);
					$start_time = strtotime(str_replace('/','-',$bmp[0]));
					$end_time = strtotime(str_replace('/','-',$bmp[1]) . " 23:59:59");
					$text_time .= "{$bmp[0]} tới {$bmp[1]}";
				} else if($clsISO->is_validate_date($date_type)) {
					$start_time = strtotime(sprintf('%s %s', str_replace('/','-', $date_type), '00:00:00'));
					$end_time = strtotime(sprintf('%s %s', str_replace('/','-', $date_type), '23:59:59'));
					$text_time = $date_type;
				}
				if($is_project){
					$project_id = $tmp[$clsProject->pkey];
					$cond = "`project_id`='{$project_id}'";
				} else {
					$block_id = $tmp[$clsProperty->pkey];
					$cond = "`block_id`='{$block_id}'";
				}
				$total = $clsStock->countItem("{$cond} and `status_id`='"._STOCK_STATUS_SOLD_ID."' 
				AND (`ms_date` BETWEEN {$start_time} AND {$end_time})");
$html_stock.= sprintf('💔 %s', $tmp['title']);
$html_stock.='
';
$html_stock.= sprintf('-- %s căn bán trong %s', $total, $text_time);
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'html_stock' => $html_stock
				);
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/stock/get_price_sheet', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsHelper = new Helper();
	$clsStock = new Stock();
	$clsStockSearch = new StockSearch();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	$clsZaloGroup = new ZaloGroup();
	$clsConfiguration = new Configuration();
	###
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$id_group = isset($inputs['id_group']) ? trim($inputs['id_group']) : "";
	$tp_check = isset($inputs['tp_check']) ? $inputs['tp_check'] : "_ptg";
	$stock_code = isset($inputs['stock_code']) ? $inputs['stock_code'] : "";
	// $clsISO->print_pre($inputs); die();
	if(!empty($stock_code)){
		$field = "stock_type,agency_id,more_information,ms_code,project_id,block_id,building_id";
		$field.= ",code,bedroom_id,home_direction_id,status_id";
		$stock_code = trim($stock_code);
		$project_name = $block_name = "";
		if (preg_match('/^(.*?)\|block:"(.*?)"\|project:(.*)$/', $str, $matches)) {
			$stock_code = $matches[1];
			$block_name = $matches[2];
			$project_name = $matches[3];
		} else if(@preg_match('/^(.*?)\|project:(.*)$/', $stock_code, $matches)){
			$stock_code = $matches[1];
			$project_name = $matches[2];
		} else if(@preg_match('/^(.*?)\|block:(.*)$/', $stock_code, $matches)){
			$stock_code = $matches[1];
			$block_name = $matches[2];
		}
		$stock_code = strtoupper($stock_code);
		$stock_code = preg_replace('/\s+/','',$stock_code);
		$ms_code = str_replace('.', '', $stock_code);
		$ms_code = str_replace('-', '', $ms_code);
		$ex_code = str_replace('4A', '04A', $stock_code);
		$ex_code = str_replace('8A', '08A', $ex_code);
		$arr_block_notin = array(
			_PROJECT_BLOCK_MWF_ID, 
			_PROJECT_BLOCK_MGA_ID, 
			_PROJECT_BLOCK_LPH_ID,
			_PROJECT_BLOCK_PAV_ID
		);
		// $dbconn->debug = true;
		$sql_cond = "`is_trash`=0";
		$is_check_full = 1; // Có quyền check full không
		$list_groups = $clsZaloGroup->getAll("`is_company_group`=1", "`id_group`,`more_information`");
		if(!empty($id_group) && !empty($list_groups)){
			foreach($list_groups as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if($val['id_group'] == $id_group){
					$is_check_full = (int) $core->get_field($more_information, "is_check_full", 0);
					break;
				}
			}
			unset($list_groups);
		}
		if($is_check_full == 0){
			$sql_cond.= " and `agency_id`='"._AGENCY_FH_ID."'";
		}
		if(!empty($project_name)){
			$project_name = strtoupper($project_name);
			$oPropject = $clsProject->getByCond("`is_trash`=0 and (`code`='{$project_name}' or `slug` like '%".$core->replaceSpace($project_name)."%')", $clsProject->pkey);
			if(!empty($oPropject)) $sql_cond.= " and `project_id`='".$oPropject[$clsProject->pkey]."'";
		}
		if(!empty($block_name)){
			$block_name = strtoupper($block_name);
			$oBlock = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`property_code`='{$block_name}' OR `slug` like '%".$core->replaceSpace($block_name)."%')", $clsProperty->pkey);
			if(!empty($oBlock)) $sql_cond.= " and `block_id`='".$oBlock[$clsProperty->pkey]."'";
		}
		$list_stocks = $clsStock->getAll("{$sql_cond} AND `block_id` not in (".implode(',',$arr_block_notin).") AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND (`ms_code`='{$stock_code}' OR `ms_code`='{$ex_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$ms_code}')", $field);
		if(!empty($list_stocks)){
			$html_price_sheets = ""; $ii = 1;
			$total_stocks = count($list_stocks);
			foreach($list_stocks as $key => $val){
				$code = $val['code'];
				$ms_code = $val['ms_code'];
				$project_id = (int) $val['project_id'];
				$block_id = (int) $val['block_id'];
				$agency_id = (int) $val['agency_id'];
				$status_id = (int) $val['status_id'];
				$bedroom_id = (int) $val['bedroom_id'];
				$building_id = (int) $val['building_id'];
				$stock_type = (int) $val['stock_type'];
				$home_direction_id = (int) $val['home_direction_id'];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$stock_poster = isset($more_information['stock_poster']) 
					? $more_information['stock_poster'] : array();
				$is_exclusive = ($agency_id == _AGENCY_FH_ID) ? 1 : 0;
				$project_name = $clsProject->getTitle($project_id);
				$block_name = $clsProperty->getTitle($block_id);
				if($status_id == 0 || $status_id == _STOCK_STATUS_SOLD_ID){
$html_price_sheets.= sprintf('[%s] Căn %s, %s, %s đã bán ạ', ($tp_check == '_ptg'? 'PTG':'Tình trạng'), $ms_code, $block_name, $project_name);
$html_price_sheets.= '
-----------------
';	
				} else {
					if($tp_check == 'status'){
						if($is_exclusive == 1){
$html_price_sheets.= sprintf('[Độc quyền Future Homes]');
$html_price_sheets.= '
';								
						}
$html_price_sheets.= sprintf('[%s] Căn %s, %s, %s: Còn hàng', ($tp_check == '_ptg'? 'PTG':'Tình trạng'), $ms_code, $block_name, $project_name);	
$html_price_sheets.= '
';					
					}
					if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
						$price_sheets = $core->get_field($more_information, "price_sheets", []);
						if(!empty($price_sheets)){
							if($tp_check == '_ptg'){
								if($is_exclusive==1){								
$html_price_sheets.= sprintf('----------------------
[Độc Quyền] PTG căn %s
----------------------', $ms_code);
								} else {
$html_price_sheets.= sprintf('PTG căn %s', $ms_code);
$html_price_sheets.= '
';						
									$tmp = $clsStock->getByCond("`agency_id`='"._AGENCY_FH_ID."' and `status_id`>0 
									AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `project_id`='{$project_id}' 
									and `building_id`='{$building_id}' and `code`='{$code}'", "floor,ms_code");
									if(!empty($tmp)){
$html_price_sheets.= sprintf('----------------------
[Future Homes] có căn Độc quyền tầng %s nhé %s
----------------------
', $tmp['floor'], $tmp['ms_code']);
									}
								}								
							}
$html_price_sheets.= sprintf('Phân khu: %s | Tòa: %s', $clsProperty->getTitle($block_id), 
$clsProperty->getTitle($building_id));
$html_price_sheets.= '
';
$html_price_sheets.= sprintf('Loại: %s | Thông thủy: %s m² | Hướng: %s', 
	$clsProperty->getTitle($bedroom_id),
	$more_information['DT_TT'], 
	$clsProperty->getTitle($home_direction_id)
);
$html_price_sheets.= '
';							
							$price_sheets = @array_reverse($price_sheets);
							$oneSheet = @reset($price_sheets);
							if(!empty($oneSheet)){
								foreach($oneSheet['sheets'] as $val){
$html_price_sheets.= sprintf('Link -> %s %s', $val['title'], $val['image']);
$html_price_sheets.= '
';
								}
							}
							if(!empty($stock_poster)){
								$stock_poster = @array_reverse($stock_poster);
								$onePoster = @reset($stock_poster);
								if($ii==$total_stocks){
$html_price_sheets.= sprintf('Poster -> %s %s', $onePoster['title'], $onePoster['image']);
								} else {
$html_price_sheets.= sprintf('Poster -> %s %s', $onePoster['title'], $onePoster['image']);
$html_price_sheets.= '
';
								}
							} else {
								$code = $tmp['code'];
								$more_information = $clsProperty->getOneField('more_information', $building_id);
								$more_information = $clsISO->to_array_json($more_information);
								$template_arrs = $core->get_field($more_information, "template", []);
								if(!empty($template_arrs)){
									foreach($template_arrs as $key => $val){
										if($val['code'] == $code && !empty($val['layout'])){
											if($ii==$total_stocks){
												$html_price_sheets.= sprintf('\r\nPoster -> %s%s',$val['layout']);
											} else {
												$html_price_sheets.= sprintf('\r\nPoster -> %s%s',$val['layout']);
											}
											break;
										}
									}
								}
							}
						}
					} else {
						$price_temporary_ns = isset($more_information['price_temporary_ns']) 
							? $more_information['price_temporary_ns'] : "";
						if(!empty($price_temporary_ns))	{
							if($tp_check == '_ptg'){
								if($is_exclusive==1){
$html_price_sheets.= sprintf('[Độc Quyền] PTG căn %s', $ms_code);
$html_price_sheets.= '
';	
								} else {
$html_price_sheets.= sprintf('PTG căn %s ', $ms_code);
$html_price_sheets.= '
';	
								}								
							}
$html_price_sheets.= sprintf('PTG -> %s %s', 'PTG TẠM TÍNH', $price_temporary_ns);
$html_price_sheets.= '
';
						} else {
							$price_sheets = $core->get_field($more_information, "price_sheets", []);
							if(!empty($price_sheets)){
								$price_sheets = @array_reverse($price_sheets);
								$oneSheet = @reset($price_sheets);
								foreach($oneSheet['sheets'] as $val){
$html_price_sheets.= sprintf('Link -> %s %s', $val['title'], $val['image']);
$html_price_sheets.= '
';
								}
							}
						}
						if(!empty($stock_poster)){
							$stock_poster = @array_reverse($stock_poster);
							$onePoster = @reset($stock_poster);
							if($ii==$total_stocks){
$html_price_sheets.= sprintf('Poster -> %s %s', $onePoster['title'], $onePoster['image']);
							} else {
							}
$html_price_sheets.= sprintf('Poster -> %s %s', $onePoster['title'], $onePoster['image']);
$html_price_sheets.= '
';
						}
					}
					if($ii < $total_stocks && $total_stocks > 1){
$html_price_sheets.= "-----------------";
$html_price_sheets.= '
';
					}
				}
				++$ii;
			}
			if(!empty($html_price_sheets)){
				$apiresults = array(
					'error' => 0, 
					'result' => 'success', 
					'is_exclusive' => 0,
					'stock_code' => $stock_code,
					'html_price_sheets' => $html_price_sheets
				);
			} else {
				$apiresults = array(
					'error' => 1, 
					'result' => 'error', 
					'message' => 'Empty'
				);
			}
		} else {
			$apiresults = array(
				'error' => 1, 
				'result' => 'error', 
				'message' => 'Empty'
			);
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'message' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/stock/get_poster', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsHelper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	$apiresults = array('error' => 1, 'result' => 'error', 'message' => "Error");
	$inputs = $request->getParsedBody();
	$stock_code = isset($inputs['stock_code']) ? $inputs['stock_code'] : "";
	// $clsISO->print_pre($stock_code); die();
	if(!empty($stock_code)){
		$field = "ms_code,agency_id,building_id,code,floor,more_information";
		// $dbconn->debug = true;
		$stock_code = trim($stock_code);
		$stock_code = strtoupper($stock_code);
		if($clsISO->checkContainer($stock_code, "XX", "")){
			$stock_code = str_replace('-', '', $stock_code);
			$tmp = @explode('XX', $stock_code); // Tìm mã tòa, trục căn
			$field = "{$clsProperty->pkey},`more_information`";
			$oneBuilding = $clsProperty->getByCond("`property_type`='_BUILDING' AND `property_code`='".$tmp[0]."'", $field);
			$img_poster = "";
			if(!empty($oneBuilding)){
				$more_information = $oneBuilding['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$template_arrs = $core->get_field($more_information, "template", []);
				if(!empty($template_arrs)){
					foreach($template_arrs as $pkey => $oval){
						$x_code = $clsHelper->formatXCode($tmp[1]);
						if($oval['code'] == $tmp[1] || ($tmp[1] != $x_code && $oval['code'] == $x_code)) {
							if(!empty($oval['layout'])){
								$img_poster = $oval['layout'];
								break;
							}
						}
					}
				}
			}
			if(!empty($img_poster)){
				$apiresults = array(
					'error' => 0, 
					'is_exclusive' => 0,
					'result' => 'success', 
					'html_poster' => $img_poster
				);
			} else {
				$apiresults = array(
					'error' => 1, 
					'result' => 'error', 
					'message' => 'Empty'
				);	
			}
		} else {
			$ms_code = preg_replace('/\s+/','',$stock_code);
			$ms_code = str_replace('.', '', $ms_code);
			$ms_code = str_replace('-', '', $ms_code);
			$arr_block_notin = array(_PROJECT_BLOCK_MWF_ID);
			$tmp = $clsStock->getByCond("`is_trash`=0 AND `block_id` not in (".implode(',',$arr_block_notin).") AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND (`ms_code`='{$stock_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$ms_code}') limit 0,1", $field);
			if(!empty($tmp)){
				$agency_id = (int) $tmp['agency_id'];
				$building_id = (int) $tmp['building_id'];
				$more_information = $tmp['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$stock_poster = isset($more_information['stock_poster']) 
					? $more_information['stock_poster'] : array();
				$is_exclusive = ($agency_id == _AGENCY_FH_ID) ? 1 : 0;
				if(!empty($stock_poster)){
					$stock_poster = @array_reverse($stock_poster);
					$onePoster = @reset($stock_poster);
					$html_poster = sprintf(' %s %s', $onePoster['title'], $onePoster['image']);
					$apiresults = array(
						'error' => 0, 
						'result' => 'success', 
						'is_exclusive' => $is_exclusive,
						'html_poster' => $html_poster
					);
				} else {
					$img_poster = "";
					$code = $tmp['code'];
					$more_information = $clsProperty->getOneField('more_information', $building_id);
					$more_information = $clsISO->to_array_json($more_information);
					$template_arrs = $core->get_field($more_information, "template", []);
					// $clsISO->print_pre($more_information); die();
					if(!empty($template_arrs)){
						foreach($template_arrs as $key => $val){
							if($val['code'] == $code){
								$img_poster = $val['layout'];
								break;
							}
						}
					}
					if(!empty($img_poster)){
						$apiresults = array(
							'error' => 0, 
							'result' => 'success', 
							'is_exclusive' => $is_exclusive,
							'html_poster' => $img_poster
						);
					} else {
						$apiresults = array(
							'error' => 1, 
							'result' => 'error', 
							'message' => 'Empty'
						);	
					}
				}
			} else {
				$apiresults = array(
					'error' => 1, 
					'result' => 'error', 
					'message' => 'Empty'
				);
			}
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'message' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/stock/get_hold_agency', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	$apiresults = array('error' => 1, 'result' => 'error', 'message' => "Error");
	$inputs = $request->getParsedBody();
	$stock_code = isset($inputs['stock_code']) ? $inputs['stock_code'] : "";
	if(!empty($stock_code)){
		$field = "`ms_code`,`agency_id`,`status_id`";
		// $dbconn->debug = true;
		$stock_code = trim($stock_code);
		$stock_code = strtoupper($stock_code);
		$ms_code = preg_replace('/\s+/','',$stock_code);
		$ms_code = str_replace('.', '', $ms_code);
		$ms_code = str_replace('-', '', $ms_code);
		$arr_block_notin = array(_PROJECT_BLOCK_MWF_ID);
		$tmp = $clsStock->getByCond("`is_trash`=0 AND `block_id` not in (".implode(',',$arr_block_notin).") AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND (`ms_code`='{$stock_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$ms_code}') limit 0,1", $field);
		if(!empty($tmp)){
			$status_id = (int) $tmp['status_id'];
			if($status_id==0 || $status_id == _STOCK_STATUS_SOLD_ID){
				$apiresults = array(
					'error' => 0, 
					'result' => 'sold',
					'message' => 'Sold'
				);
			} else {
				$agency_id = $tmp['agency_id'];
				$html_agency = $clsProperty->getTitle($agency_id);
				$apiresults = array(
					'error' => 0, 
					'result' => 'success', 
					'html_agency' => $html_agency
				);
			}
		} else {
			$apiresults = array(
				'error' => 1, 
				'result' => 'error', 
				'message' => 'Empty'
			);
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'message' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
?>