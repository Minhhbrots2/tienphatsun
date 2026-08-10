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
$app->post('/chatbot/apartment/ptg', function ($request, $response, $args) use ($app) {
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
	$stock_code = isset($inputs['ma_can']) ? $inputs['ma_can'] : "";
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
					'body' => $html_price_sheets
				);
			} else {
				$apiresults = array(
					'error' => 1, 
					'result' => 'error', 
					'body' => 'Empty'
				);
			}
		} else {
			$apiresults = array(
				'error' => 1, 
				'result' => 'error', 
				'body' => 'Empty'
			);
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'body' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/chatbot/apartment/status', function ($request, $response, $args) use ($app) {
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
	$stock_code = isset($inputs['ma_can']) ? $inputs['ma_can'] : "";
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
				$stock_poster = $core->get_field($more_information, "stock_poster", []);
				$is_exclusive = ($agency_id == _AGENCY_FH_ID) ? 1 : 0;
				$project_name = $clsProject->getTitle($project_id);
				$block_name = $clsProperty->getTitle($block_id);
				if($status_id == 0 || $status_id == _STOCK_STATUS_SOLD_ID){
$html_price_sheets.= sprintf('[%s] Căn %s, %s, %s đã bán ạ', 'Tình trạng', $ms_code, $block_name, $project_name);
$html_price_sheets.= '
-----------------
';	
				} else {
					if($is_exclusive == 1){
$html_price_sheets.= sprintf('[Độc quyền Future Homes]');
$html_price_sheets.= '
';								
					}
$html_price_sheets.= sprintf('[%s] Căn %s, %s, %s: Còn hàng', ($tp_check == '_ptg'? 'PTG':'Tình trạng'), $ms_code, $block_name, $project_name);	
$html_price_sheets.= '
';					
					if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
						$price_sheets = $core->get_field($more_information, "price_sheets", []);
						if(!empty($price_sheets)){
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
					'body' => $html_price_sheets
				);
			} else {
				$apiresults = array(
					'error' => 1, 
					'result' => 'error', 
					'body' => 'Empty'
				);
			}
		} else {
			$apiresults = array(
				'error' => 1, 
				'result' => 'error', 
				'body' => 'Empty'
			);
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'body' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/chatbot/apartment/poster', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$clsHelper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	$inputs = $request->getParsedBody();
	$stock_code = isset($inputs['ma_can']) ? $inputs['ma_can'] : "";
	// $clsISO->print_pre($stock_code); die();
	$apiresults = array('error' => 1, 'result' => 'error', 'message' => "Error");
	if(!empty($stock_code)){
		$field = "ms_code,agency_id,building_id,code,floor,more_information";
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
			$tmp = $clsStock->getByCond("`is_trash`=0 AND `block_id` not in (".implode(',',$arr_block_notin).") AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND (`ms_code`='{$stock_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$ms_code}') limit 0,1", $field);
			if(!empty($tmp)){
				$agency_id = (int) $tmp['agency_id'];
				$building_id = (int) $tmp['building_id'];
				$more_information = $tmp['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$stock_poster = $core->get_field($more_information, "stock_poster", []);
				$is_exclusive = ($agency_id == _AGENCY_FH_ID) ? 1 : 0;
				if(!empty($stock_poster)){
					$stock_poster = @array_reverse($stock_poster);
					$onePoster = @reset($stock_poster);
					$html_poster = sprintf(' %s %s', $onePoster['title'], $onePoster['image']);
					$apiresults = array(
						'error' => 0, 
						'result' => 'success', 
						'body' => $html_poster
					);
				} else {
					$img_poster = "";
					$code = trim($tmp['code']);
					$more_information = $clsProperty->getOneField('more_information', $building_id);
					$more_information = $clsISO->to_array_json($more_information);
					$template_arrs = $core->get_field($more_information, "template", []);
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
							'body' => $img_poster
						);
					} else {
						$apiresults = array(
							'error' => 1, 
							'result' => 'error', 
							'body' => 'Empty'
						);	
					}
				}
			} else {
				$apiresults = array(
					'error' => 1, 
					'result' => 'error', 
					'body' => 'Empty'
				);
			}
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'body' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/chatbot/apartment/axis', function ($request, $response, $args) use ($app) {
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
		'body' => "Error"
	);
	$inputs 	= $request->getParsedBody();
	$code 		= $inputs['code'] ?? "";
	$project	= $inputs['project'] ?? "";
	if(!empty($code)){
		$code = strtoupper($code);
		$cond = "`is_trash`=0 AND `agency_id`>0";
		if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
			$cond.= " AND (`status_id`>0 AND `status_id` NOT IN ('".implode('\',\'', [_STOCK_STATUS_NON_ID, _STOCK_STATUS_HIDDEN_ID, _STOCK_STATUS_SOLD_ID])."'))";
		} else {
			$cond.= " AND (`status_id`>0 AND `status_id` NOT IN ('".implode('\',\'', [_STOCK_STATUS_NON_ID, _STOCK_STATUS_SOLD_ID])."'))";
		}
		$field = "{$clsStock->pkey},`stock_type`,`bedroom_id`,`home_direction_id`,`more_information`";
		$field.= ',`project_id`,`block_id`,`building_id`,`floor`,`code`,`status_id`';
		if($clsISO->checkContainer($code, "XX", "")){
			// Tìm vị trí theo trục hay tầng
			$vitriXX = $helper->viTriCuaXX($code);	
			$extsearch = @str_replace('XX', 'XXX', $code);
			$code_more = str_replace('5A', '05A', $code);
			$code_more = str_replace('8A', '08A', $code_more);
			$extsearch_more = str_replace('5A', '05A', $extsearch); 
			$extsearch_more = str_replace('8A', '08A', $extsearch_more); 
			$order_by = " order by `floor` ASC";
			if($vitriXX == '_last'){
				$order_by = " order by `code` ASC";
			}
			$list_stocks = $clsStock->getAll("{$cond} and (`ms_code` like '".str_replace('X','_',$code)."' 
				or `ms_code` like '".str_replace('X','_',$code_more)."' 
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
					$html_stock.= sprintf('Phân khu: %s | Tòa: %s', 
						$clsProperty->getTitle($block_id), 
						$clsProperty->getTitle($building_id)
					);
					if($vitriXX == '_last'){
						$a = explode('XX', $code);
						$html_stock.= '-- Tầng '.$a[1].'--';	
					} else if($vitriXX == '_center') {
						$a = explode('XX', $code);
						$html_stock.= '-- Trục '.$a[1].'--';						
					}
					$html_stock.= sprintf('Loại: %s | Thông thủy: %s m² | Hướng: %s', $clsProperty->getTitle($bedroom_id), $more_information['DT_TT'], $clsProperty->getTitle($home_direction_id));
					$html_stock.= '-------';
				} else {
					$html_stock.= sprintf('Phân khu: %s | Dãy: %s', $clsProperty->getTitle($block_id), $clsProperty->getTitle($building_id));
					$html_stock.= sprintf('DT Đất: %s m² | DT XD: %s m² | Hướng: %s', $more_information['DT_TT'], $more_information['DT_Tim'], $clsProperty->getTitle($home_direction_id));
					$html_stock.= '-------';
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
					'body' => $html_stock
				);
			} else {
				if($vitriXX == '_center'){
					$html_stock = sprintf('Tầng %s đã hết !', $code);
				} else {
					$html_stock = sprintf('Trục %s đã hết !', $code);
				}
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'body' => $html_stock
				);
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/apartment/get_hold_agency', function ($request, $response, $args) use ($app) {
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
$app->post('/chatbot/apartment/info', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	$apiresults = array('error' => 1, 'result' => 'error', 'message' => "Error");
	$inputs = $request->getParsedBody();
	$stock_code = isset($inputs['ma_can']) ? $inputs['ma_can'] : "";
	$project_name = isset($inputs['du_an']) ? $inputs['du_an'] : "";
	$tower_name = isset($inputs['toa']) ? $inputs['toa'] : "";
	if(!empty($stock_code)){
		$field = "`ms_code`,`status_id`,`total_price_vat`,`total_price_bank`,`total_price_early`,`total_price_progress`";
		$field.= ",`floor`,`bedroom_id`,`home_direction_id`,`view_id`,`more_information`,`block_id`,`building_id`";
		// $dbconn->debug = true;
		$stock_code = trim($stock_code);
		$stock_code = strtoupper($stock_code);
		$ms_code = preg_replace('/\s+/','',$stock_code);
		$ms_code = str_replace('.', '', $ms_code);
		$ms_code = str_replace('-', '', $ms_code);
		$arr_block_notin = array(_PROJECT_BLOCK_MWF_ID);
		$project_id = $block_id = $building_id = 0;
		$sql_query = "`is_trash`=0 AND `block_id` not in (".implode(',',$arr_block_notin).") 
			AND `status_id`<>'"._STOCK_STATUS_NON_ID."'";
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
		if(!empty($tower_name)){
			$cond = "`is_trash`=0 AND `property_type`='_BUILDING'";
			if($block_id > 0){
				$cond.= " AND `for_id`='{$block_id}'";
			}
			$tmp = $clsProperty->getByCond("{$cond} 
				AND (`property_code`='{$tower_name}' OR `slug`='".$core->replaceSpace($tower_name)."')", $clsProperty->pkey);
			if(!empty($tmp)){
				$block_id = $tmp['for_id'];
				$building_id = $tmp[$clsProperty->pkey];
				$sql_query.= " AND `building_id`='{$building_id}'";
			}
		}
		// $dbconn->debug = true;
		$oneStock = $clsStock->getByCond("{$sql_query} 
			AND (`ms_code`='{$stock_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$ms_code}') limit 0,1", $field);
		// $clsISO->print_pre($oneStock); die();	
		if(!empty($oneStock)){
			$status_id = (int) $oneStock['status_id'];
			$block_id = (int) $oneStock['block_id'];
			$building_id = (int) $oneStock['building_id'];
			$project_id = (int) $oneStock['project_id'];
			$bedroom_id = (int) $oneStock['bedroom_id'];
			$view_id = (int) $oneStock['view_id'];
			$home_direction_id = (int) $oneStock['home_direction_id'];
			$total_price_vat = $clsISO->processSmartNumber($oneStock['total_price_vat']);
			$total_price_progress = $clsISO->processSmartNumber($oneStock['total_price_progress']);
			$total_price_early = $clsISO->processSmartNumber($oneStock['total_price_early']);
			$total_price_bank = $clsISO->processSmartNumber($oneStock['total_price_bank']);
			$more_information = $oneStock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			#- Tình trạng
			$status_text = ($status_id == 0 || $status_id == _STOCK_STATUS_SOLD_ID) ? 'Đã bán' : 'Còn hàng';
			#
			$DT_TT = $core->get_field($more_information, "DT_TT", "");
			$DT_Tim = $core->get_field($more_information, "DT_Tim", "");
			#
			$arr_property = array();
			$block_name = $building_name = "";
			$bedroom_name = $home_direction_name = $view_name = "";
			$arr_property[] = $block_id;
			$arr_property[] = $building_id;
			if($bedroom_id > 0) $arr_property[] = $bedroom_id;
			if($home_direction_id > 0) $arr_property[] = $home_direction_id;
			if($view_id > 0) $arr_property[] = $view_id;
			#
			$m_field = "`{$clsProperty->pkey}`,`title`";
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in ('".implode('\',\'', $arr_property)."')", $m_field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if($bedroom_id > 0 && $val[$clsProperty->pkey] == $bedroom_id){
						$bedroom_name = $val['title']; 
					} else if($home_direction_id > 0 && $val[$clsProperty->pkey] == $home_direction_id){
						$home_direction_name = $val['title']; 
					} else if($view_id > 0 && $val[$clsProperty->pkey] == $view_id){
						$view_name = $val['title']; 
					} else if($block_id > 0 && $val[$clsProperty->pkey] == $block_id){
						$block_name = $val['title']; 
					} else if($building_id > 0 && $val[$clsProperty->pkey] == $building_id){
						$building_name = $val['title']; 
					} 
				}
			}
			$apiresults = array(
				'error' => 0, 
				'result' => 'success', 
				'body' => array(
					'ma_can' => $oneStock['ms_code'],
					'ten_phan_khu' => $block_name,
					'ten_phan_toa' => $building_name,
					'loai_can' => $bedroom_name,
					'huong' => $home_direction_name,
					'view' => $view_name,
					'tang' => $oneStock['floor'],
					'gia_full_vat' => $total_price_vat,
					'gia_thanh_toan_tien_do' => $total_price_progress,
					'gia_thanh_toan_som' => $total_price_early,
					'gia_vay_ngan_hang' => $total_price_bank,
					"dien_tich_thong_thuy" => $DT_TT,
					"dien_tich_tim_tuong" =>  $DT_Tim,
					"tinh_trang" => $status_text
				)
			);
		} else {
			$apiresults = array(
				'error' => 1, 
				'result' => 'error', 
				'body' => 'Căn hộ đã bán'
			);
		}
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'error', 
			'body' => 'Không đúng mã căn'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/chatbot/apartment/search', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	###
	$apiresults = array('error' => 1, 'result' => 'error', 'body' => "Error");
	$inputs = $request->getParsedBody();
	$project = $core->get_field($inputs, "project", "");
	$building = $core->get_field($inputs, "building", "");
	$stock_type = $core->get_field($inputs, "stock_type", "cao_tang");
	$unit_type = $core->get_field($inputs, "unit_type", "");
	$bedrooms = (int) $core->get_field($inputs, "bedrooms", "");
	$floor_min = $core->get_field($inputs, "floor_min", 0);
	$floor_max = $core->get_field($inputs, "floor_max", 0);
	$price_min = $core->get_money_field($inputs, "price_min", 0);
	$price_max = $core->get_money_field($inputs, "price_max", 0);
	$price_sort = $core->get_money_field($inputs, "price_sort", "asc");
	$area_min = $core->get_field($inputs, "area_min", 0);
	$area_max = $core->get_field($inputs, "area_max", 0);
	$area_sort = $core->get_field($inputs, "area_sort", "");
	$axis_num = $core->get_field($inputs, "axis_num", null);
	$payment_type = $core->get_field($inputs, "payment_type", "cash");
	#
	$sql_query = "`is_trash`=0";
	if($stock_type == "cao_tang"){
		$sql_query.= " AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
	} else if($stock_type == "thap_tang"){
		$sql_query.= " AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
	}
	$project_id = $block_id = $building_id = 0;
	if(!empty($project)){
		$tmp = $clsProject->getByCond("`slug`='".$core->replaceSpace($project)."'", $clsProject->pkey);
		if(!empty($tmp)){
			$project_id = (int) $tmp[$clsProject->pkey];
			$sql_query.= " AND `project_id`='".$tmp[$clsProject->pkey]."'";
		} else {
			$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`property_code`='{$project}' 
				OR `slug` like '%{$core->replaceSpace($project)}%')", "{$clsProperty->pkey},`for_id`");
			if(!empty($tmp)){
				$project_id = (int) $tmp['for_id'];
				$block_id = (int) $tmp[$clsProperty->pkey];
				$sql_query.= " AND `project_id`='{$project_id}' AND `block_id`='{$block_id}'";
			}
		}
	}
	if(!empty($building)){
		$sql_cond = "`property_type`='_BUILDING'";
		if($block_id > 0){
			$sql_cond.= " AND `for_id`='{$block_id}'";
		} else if($project_id > 0) {
			$tmp_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `for_id`='{$project_id}'", $clsProperty->pkey);
			if(!empty($tmp_blocks)){
				$arr_in_blocks = array();
				foreach($tmp_blocks as $key => $val){
					$arr_in_blocks[] = $val[$clsProperty->pkey];
				}
				unset($tmp_blocks);
				$sql_cond.= " AND `for_id` in (".implode(',',$arr_in_blocks).")";
			}
		}
		$tmp = $clsProperty->getByCond("{$sql_cond} AND (`property_code`='{$building}' 
			OR `slug`='".$core->replaceSpace($building)."' 
			OR `slug_vn`='".$core->replaceSpace($building)."'
		)", $clsProperty->pkey);
		if(!empty($tmp)){
			$building_id = $tmp[$clsProperty->pkey];
			$sql_query.= " AND `building_id`='{$building_id}'";
		}
	}
	$sql_query.= " AND `status_id`>0 AND `status_id` NOT IN ('".implode('\',\'',[_STOCK_STATUS_SOLD_ID, _STOCK_STATUS_NON_ID])."')";
	if(!empty($floor_min) && empty($floor_max)){
		$sql_query.= " AND `floor` < {$floor_min})";
	} else if(empty($floor_min) && !empty($floor_max)){
		$sql_query.= " AND `floor` >= '{$floor_max}')";
	} else if(!empty($floor_min) && !empty($floor_max)){
		$floor_min = (int) $floor_min;
		$floor_max = (int) $floor_max;
		if($floor_min < $floor_max){
			for($i=$floor_min; $i<=$floor_max; $i++){
				$floor_arrs[] = $i;
			}
		} else if($floor_min == $floor_max){
			$floor_arrs[] = $i;
		}	
		if(!empty($floor_arrs)){
			$floor_arrs = @array_unique($floor_arrs);
			$sql_query.= " and `floor` in(".implode(',', $floor_arrs).")";
		}
	}
	if($bedrooms > 0 || !empty($unit_type)){
		if($bedrooms > 0 && empty($unit_type)){
			$tmp = $clsProperty->getAll("`property_type`='_BEDROOM' AND `ms_value`='{$bedrooms}'", $clsProperty->pkey);
		}
		if(!empty($unit_type)){
			$tmp = $clsProperty->getAll("`property_type`='_BEDROOM' AND (`slug` like '%{$clsISO->replaceSpace($unit_type)}%' OR `slug_vn` like '%{$clsISO->replaceSpace($unit_type)}%'
			)", $clsProperty->pkey);
		}
		if(!empty($tmp)){
			$arr = array();
			foreach($tmp as $key => $val){
				$arr[] = $val[$clsProperty->pkey];
			}
			unset($tmp);
			$sql_query.= " AND `bedroom_id` in (".implode(',', $arr).")";
		}
	}
	// Lọc bởi diện tích
	if(!empty($area_min) && empty($area_max)){
		$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.DT_TT\") <= {$area_min})";
	} else if(empty($area_min) && !empty($area_max)){
		$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.DT_TT\") > '{$area_max}')";
	} else if(!empty($area_min) && !empty($area_max)){
		$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.DT_TT\") BETWEEN {$area_min} AND {$area_max})";
	}
	// Lọc bởi giá
	if($payment_type == 'installment'){
		if(!empty($price_min) && empty($price_max)){
			$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.total_price_bank\") <= {$price_min})";
		} else if(empty($area_min) && !empty($area_max)){
			$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.total_price_bank\") > '{$price_max}')";
		} else if(!empty($area_min) && !empty($area_max)){
			$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.total_price_bank\") BETWEEN {$area_min} AND {$area_max})";
		}
	} else {
		if(!empty($price_min) && empty($price_max)){
			$sql_query.= " AND (`total_price` <= {$price_min})";
		} else if(empty($area_min) && !empty($area_max)){
			$sql_query.= " AND (`total_price` > '{$price_max}')";
		} else if(!empty($area_min) && !empty($area_max)){
			$sql_query.= " AND (`total_price` BETWEEN {$area_min} AND {$area_max})";
		}
	}
	// $clsISO->print_pre($sql_query); die();
	$field = "{$clsStock->pkey},`stock_type`,`ms_code`,`bedroom_id`,`home_direction_id`,`project_id`,`block_id`,`building_id`";
	$field.= ",`view_id`,`floor`,`type_id`,`agency_id`,`more_information`,IF(`agency_id`="._AGENCY_FH_ID.",1,0) AS `order_no`,IF(total_price_vat>0,total_price_vat,`total_price_early`) as `price_order_no`";
	if(!empty($area_sort)){
		if($price_sort == 'asc'){
			$sort_type = ($area_sort == 'asc') ? 'ASC' : 'DESC';
			$order_by = " ORDER BY `DT_TT` {$sort_type}, `price_order_no` ASC";
		} else {
			$sort_type = ($area_sort == 'asc') ? 'ASC' : 'DESC';
			$order_by = " ORDER BY `DT_TT` {$sort_type}, `price_order_no` DESC";
		}
	} else {
		$sort_type = ($price_sort == 'asc') ? 'ASC' : 'DESC';
		$order_by = " ORDER BY `order_no` DESC, `price_order_no` {$sort_type}";
	}
	$list_stocks = $clsStock->getAll($sql_query.$order_by." LIMIT 0,10", $field);
	if(!empty($list_stocks)){
		$arr_project_cached = $clsProject->getListProject();
		$stockUnits = $arr_data_cached = $arr_data_ids = array();
		foreach($list_stocks as $key => $val){
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$building_id = (int) $val['building_id'];
			$view_id = (int) $val['view_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$home_direction_id = (int) $val['home_direction_id'];
			if($block_id > 0 && !in_array($block_id, $arr_data_ids)){
				$arr_data_ids[] = $block_id;
			}
			if($building_id > 0 && !in_array($building_id, $arr_data_ids)){
				$arr_data_ids[] = $building_id;
			}
			if($bedroom_id > 0 && !in_array($bedroom_id, $arr_data_ids)){
				$arr_data_ids[] = $bedroom_id;
			}
			if($view_id > 0 && !in_array($view_id, $arr_data_ids)){
				$arr_data_ids[] = $view_id;
			}
			if($home_direction_id > 0 && !in_array($home_direction_id, $arr_data_ids)){
				$arr_data_ids[] = $home_direction_id;
			}
		}
		if(!empty($arr_data_ids)){
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_data_ids).")", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $okey => $oval){
					$arr_data_cached[$oval[$clsProperty->pkey]] = $oval['title'];
				}
			}
		}
		foreach($list_stocks as $key => $val){
			$project_id = $val['project_id'];
			$block_id = $val['block_id'];
			$building_id = $val['building_id'];
			$bedroom_id = $val['bedroom_id'];
			$view_id = $val['view_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$stockUnits[] = array(
				'unit_code' => $val['ms_code'],
                'project' => $arr_project_cached[$project_id]['title'],
                'block' => $arr_data_cached[$block_id],
				'building' => $arr_data_cached[$building_id],
                'floor' => $val['floor'],
                'bedroom' => $arr_data_cached[$bedroom_id],
                'area' => $core->get_field($more_information,"DT_TT", 0),
                'price_vat' => $core->get_field($more_information,"total_price_vat", 0),
				'price_early' => $core->get_field($more_information,"total_price_early", 0),
				'price_bank' => $core->get_field($more_information,"total_price_bank", 0),
                'view' => $arr_data_cached[$view_id],
				'direction' => $arr_data_cached[$home_direction_id],
                // 'estimated_rental' => 8500000,
                // 'roi_percentage' => 5.2,
                // 'handover_date' => '2027-Q4',
			);
		}
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'body' => $stockUnits
		);
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'success', 
			'body' => []
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/chatbot/units/search', function ($request, $response, $args) use ($app) {
	global $core, $dbconn, $clsISO;
	$helper = new Helper();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty =  new Property();
	$clsProjectMeta = new ProjectMeta();
	$apiresults = array('error' => 1, 'result' => 'error', 'body' => "Error");
	$inputs = $request->getParsedBody();
	$project = $core->get_field($inputs, "project", "");
	$bedrooms = $core->get_field($inputs, "bedrooms", "");
	$floor = $core->get_field($inputs, "floor", []);
	$area = $core->get_field($inputs, "area", []);
	$budget = $core->get_field($inputs, "budget", []);
	$payment_type = $core->get_field($inputs, "payment_type", "installment");
	$sql_query = "`is_trash`=0";
	if(!empty($project)){
		$tmp = $clsProject->getByCond("`slug`='".$core->replaceSpace($project)."'", $clsProject->pkey);
		if(!empty($tmp)){
			$sql_query.= " AND `project_id`='".$tmp[$clsProject->pkey]."'";
		} else {
			$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`property_code`='{$project}' 
				OR `slug` like '%{$core->replaceSpace($project)}%')", "{$clsProperty->pkey},`for_id`");
			if(!empty($tmp)){
				$project_id = $tmp['for_id'];
				$block_id = $tmp[$clsProperty->pkey];
				$sql_query.= " AND `project_id`='{$project_id}' AND `block_id`='{$block_id}'";
			}
		}
	}
	if(!empty($floor)){
		$floor_arrs = array();
		$min = (int) $floor['min'];
		$max = (int) $floor['max'];
		if($min < $max){
			for($i=$min; $i<=$max; $i++){
				$floor_arrs[] = $i;
			}
		} else if($min == $max){
			$floor_arrs[] = $i;
		}	
		if(!empty($floor_arrs)){
			$floor_arrs = @array_unique($floor_arrs);
			$sql_query.= " and `floor` in(".implode(',', $floor_arrs).")";
		}					
	}
	if(!empty($bedrooms)){
		$tmp = $clsProperty->getAll("`property_type`='_BEDROOM' AND (`slug` like '%{$clsISO->replaceSpace($bedrooms)}%' 
			OR `slug_vn` like '%{$clsISO->replaceSpace($bedrooms)}%'
		)", $clsProperty->pkey);
		if(!empty($tmp)){
			$arr = array();
			foreach($tmp as $key => $val){
				$arr[] = $val[$clsProperty->pkey];
			}
			unset($tmp);
			$sql_query.= " AND `bedroom_id` in (".implode(',', $arr).")";
		}
	}
	if(!empty($area)){
		$min = (float) $area['min'];
		$max = (float) $area['max'];
		$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.DT_TT\") BETWEEN {$min} AND {$max})";
	}
	if(!empty($budget)){
		$min = (float) $budget['min'];
		$max = (float) $budget['max'];
		if($payment_type == "installment"){
			$min = $min * 3.33;
			$max = $max * 3.33;
			$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.total_price_vat\") BETWEEN {$min} AND {$max})";
		} else {
			$sql_query.= " AND (JSON_EXTRACT(`more_information`,\"$.total_price_vat\") BETWEEN {$min} AND {$max})";
		}
	}
	$field = "{$clsStock->pkey},`stock_type`,`ms_code`,`bedroom_id`,`home_direction_id`,`project_id`,`block_id`,`building_id`";
	$field.= ",`view_id`,`floor`,`type_id`,`agency_id`,`more_information`,IF(`agency_id`="._AGENCY_FH_ID.",1,0) AS `order_no`,IF(CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.total_price_early\")) AS UNSIGNED)>0,CAST(JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.total_price_early\")) AS UNSIGNED),`total_price_vat`) as `price_order_no`";
	$order_by = " ORDER BY `order_no` DESC, `price_order_no` ASC";
	$list_stocks = $clsStock->getAll($sql_query.$order_by." LIMIT 0,5", $field);
	if(!empty($list_stocks)){
		$arr_project_cached = $clsProject->getListProject();
		$stockUnits = $arr_data_cached = $arr_data_ids = array();
		foreach($list_stocks as $key => $val){
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$building_id = (int) $val['building_id'];
			$view_id = (int) $val['view_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$home_direction_id = (int) $val['home_direction_id'];
			if($block_id > 0 && !in_array($block_id, $arr_data_ids)){
				$arr_data_ids[] = $block_id;
			}
			if($building_id > 0 && !in_array($building_id, $arr_data_ids)){
				$arr_data_ids[] = $building_id;
			}
			if($bedroom_id > 0 && !in_array($bedroom_id, $arr_data_ids)){
				$arr_data_ids[] = $bedroom_id;
			}
			if($view_id > 0 && !in_array($view_id, $arr_data_ids)){
				$arr_data_ids[] = $view_id;
			}
			if($home_direction_id > 0 && !in_array($home_direction_id, $arr_data_ids)){
				$arr_data_ids[] = $home_direction_id;
			}
		}
		if(!empty($arr_data_ids)){
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_data_ids).")", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $okey => $oval){
					$arr_data_cached[$oval[$clsProperty->pkey]] = $oval['title'];
				}
			}
		}
		foreach($list_stocks as $key => $val){
			$project_id = $val['project_id'];
			$block_id = $val['block_id'];
			$building_id = $val['building_id'];
			$bedroom_id = $val['bedroom_id'];
			$view_id = $val['view_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$stockUnits[] = array(
				'unit_code' => $val['ms_code'],
                'project' => $arr_project_cached[$project_id]['title'],
                'block' => $arr_data_cached[$block_id],
				'building' => $arr_data_cached[$building_id],
                'floor' => $val['floor'],
                'bedrooms' => $arr_data_cached[$bedroom_id],
                'area' => $core->get_field($more_information,"DT_TT", 0),
                'price' => $core->get_field($more_information,"total_price_vat", 0),
                'view' => $arr_data_cached[$view_id],
				'direction' => $arr_data_cached[$home_direction_id],
                'payment_type' => $payment_type
			);
		}
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'body' => $stockUnits
		);
	} else {
		$apiresults = array(
			'error' => 1, 
			'result' => 'success', 
			'body' => []
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
?>