<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is Â©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
$app->get('/get_spreadsheet_highfloor', function ($request, $response) use ($app) {
	global $dbconn, $clsISO,$profile_id,$core;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsISO = new ISO();
	$clsProperty = new Property();
	$clsConfiguration = new Configuration();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_agency_cronjob_highfloor.json';
	###
	$decoder = new Webmozart\Json\JsonDecoder();		
	$encoder = new Webmozart\Json\JsonEncoder();
	if(file_exists($cachedFile)){
		$lstCache = $decoder->decodeFile($cachedFile);
	} 
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$cond = "`property_type`='_AGENCY' AND `is_crawl`='1'";	
//	$cond .= " AND `property_id`='176'";	
	$lstAgency = $clsProperty->getAll($cond,$clsProperty->pkey.",more_information");
	$lstCrawl = $crawl_first = $crawl_second = $crawl = [];
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE; 	
	
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == $stock_type) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	$cachedFileConfig = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
	$arr_column_data = array();
	if(file_exists($cachedFileConfig)){
		$arr_column_data = $decoder->decodeFile($cachedFileConfig);			
	}
	$config_column_data = json_encode($arr_column_data,JSON_UNESCAPED_UNICODE);
	$arr_status_id = [];
	if(!empty($lstAgency)) {
		$is_crawl = $i = 0;
		$total_cronjob = 5;
		$arr_crawl = $crawl_tmp = [];
		foreach ($lstAgency as $key => $val) {
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$block_crawl = !empty($more_information["block_crawl"]) ? $more_information["block_crawl"] : array();
			$block_agency_crawl = !empty($lstCache[$val[$clsProperty->pkey]]) ? $lstCache[$val[$clsProperty->pkey]] : array();
			if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
				$arr_status_id[$val[$clsProperty->pkey]] = $more_information['stock_status_id'];
			}
			if(!empty($block_crawl)) {
				$arr_cache_building = [];
				foreach ($block_crawl as $block_id => $v_cr) {
					if(!empty($v_cr['is_crawl']) && !empty($v_cr["sheet_name"]) && !empty($v_cr["sheetID"])) {
						if(!isset($arr_cache_building[$block_id])) {
							$arr_cache_building[$block_id] = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$block_id}'",$clsProperty->pkey.",property_code,more_information");
						}
						$lstBuilding = $arr_cache_building[$block_id];
						$ranges = explode("|",$v_cr["sheet_name"]);
						
						$is_stock_point = !empty($v_cr["is_stock_point"]) ? $v_cr["is_stock_point"] : "";
						if($i < $total_cronjob) {
							$crawl_tmp[] = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$block_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
								"oneAgency"	=>	$val,
								"config_column_data"	=>	$config_column_data,
								"lstBuilding"	=>	json_encode($lstBuilding,JSON_UNESCAPED_UNICODE),
							];
						}
						if(empty($block_agency_crawl) || (!empty($block_agency_crawl) && !$clsISO->checkItemInArray($block_id,$block_agency_crawl))) {	
							$crawl = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$block_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
								"oneAgency"	=>	$val,
								"config_column_data"	=>	$config_column_data,
								"lstBuilding"	=>	json_encode($lstBuilding,JSON_UNESCAPED_UNICODE),
							];
							++$is_crawl;
							if($is_crawl <= $total_cronjob) {								
								$arr_crawl[] = $crawl;
							}							
							unset($crawl);
						}
						++$i;
						unset($is_stock_point);
					}
				}
			}
			if($is_crawl == $total_cronjob) {
				break;
			}
		}
	}
	if(empty($arr_crawl)) {
		$arr_crawl = $crawl_tmp;
		$lstCache = array();
	}
	foreach ($arr_crawl as $key => $val) {
		if(!$clsISO->checkItemInArray($val["target_id"],$lstCache[$val['agency_id']])) {
			$lstCache[$val['agency_id']][] = $val['target_id'];
		}
	}
//	$clsISO->print_pre($arr_crawl);die;
	$apiresults = array(
		'error' => 0,
		'result' => 'success',
		'arr_crawl'	=>	$arr_crawl,
		'arr_price_min_max'	=>	$arr_price_min_max,
	);
	$encoder->encodeFile($lstCache, $cachedFile);
	// Return
	echo echoResponse('200',$apiresults);
});
$app->post('/crawl_highfloor', function ($request, $response) use ($app){
	global $dbconn, $clsISO;
	
	// CÁC CLASS KHỞI TẠO NÊN DÙNG LAZY LOAD (Chỉ khởi tạo khi cần)
	$helper = new Helper();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockLog = new StockLog();
	$clsStockAgent = new StockAgent();
	
	$status_code = 400;
	$apiresults = [
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	];

	// TỐI ƯU: Cân nhắc đưa các require_once này ra ngoài file global/bootstrap để tránh load lại mỗi request
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	
	$decoder = new Webmozart\Json\JsonDecoder();
	$cachedFile = DIR_CACHE_JSON.'/crawl/stock/stock_sold_admin.json';
	$arrCacheSold = file_exists($cachedFile) ? $decoder->decodeFile($cachedFile) : [];
	
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
	$inputs = $request->getParsedBody();
	
	$agency_id           = $helper->getFieldValue("agency_id", $inputs);
	$block_id            = $helper->getFieldValue("target_id", $inputs);
	$stock_type          = $helper->getFieldValue("stock_type", $inputs);
	$spreadsheetId_crawl = $helper->getFieldValue("spreadsheetId_crawl", $inputs);
	$arr_data_input      = $helper->getFieldValue("arr_data", $inputs);
	
	$arr_price_min_max = $arr_data_input["arr_price_min_max"] ?? [];
	$oneAgency         = $helper->getFieldValue("oneAgency", $arr_data_input);
	$more_information  = $clsISO->to_array_json($oneAgency["more_information"] ?? '');
	
	$stock_status_id = $more_information['stock_status_id'] ?? _STOCK_STATUS_LOCK_ID;
	$lstStock_sold = $arrCacheSold[$stock_type][$agency_id][$block_id] ?? [];
    
    // TỐI ƯU: Khai báo 1 lần time() để dùng chung toàn API
	$currentTime = time(); 
	if(!empty($inputs["result"])) {
		$arr_not_upd = $arr_upd = $arr_ms_code_new = [];
		$total_stock_sold = $total_stock_new = $total_updated = 0;
		$arr_data = $helper->getFieldValue("tblData", $inputs) ?: [];
		
		// BẮT ĐẦU TRANSACTION DB (Sử dụng hàm tương ứng với framework/ORM của bạn)
		if (method_exists($dbconn, 'StartTrans')) $dbconn->StartTrans(); 
        else if (method_exists($dbconn, 'beginTransaction')) $dbconn->beginTransaction();
		if(!empty($arr_data)) {
			// Giá min max
			$oneBlock = $arr_block_cached[$block_id] ?? [];
			$for_id = $oneBlock["for_id"] ?? '';
			$price_min_max = $arr_price_min_max[$for_id][$block_id] ?? ($arr_price_min_max[$for_id][0] ?? []);
			$min = isset($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
			$max = isset($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
			
			$arr_cdt_not_update = [];
			if($agency_id != 10954 && $block_id == 10684){
				$lstMs_code = $clsStockAgent->getAll("`agency_id`='10954' AND `target_id`='$block_id' AND `stock_type`='{$stock_type}'","ms_code");
				if(!empty($lstMs_code)) {
					foreach ($lstMs_code as $val){
						$arr_cdt_not_update[] = $clsCrawl->getCodeNotTemplate($val["ms_code"]);
					}
				}
			}

			$arr_data_code = [];
			foreach ($arr_data as $val) {
				$ms_code = $val['ms_code'] ?? '';
				if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
					$clean_code = $clsCrawl->getCodeNotTemplate($ms_code);
					if(!in_array($clean_code, $arr_cdt_not_update)) { // Thay thế hàm checkItemInArray bằng in_array native cho nhanh
						$val['ms_code'] = $ms_code;
						$arr_data_code[$clean_code] = $val;
					}
				}
			}

			$lstStock = [];
			$arr_stock_id_not_in = [];
			if(!empty($arr_data_code)) {
				$arr_code_old = [];
				$arr_ms_code = array_keys($arr_data_code);
				$str_code_in = implode("','", $arr_ms_code);
//				$dbconn->debug=true;
				$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$block_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `mscode` IN ('".$str_code_in."')");
//				$clsISO->print_pre($list_agency_stocks);die;
				if(!empty($list_agency_stocks)) {
					foreach ($list_agency_stocks as $oneStock) {
						$ms_code = $oneStock["ms_code"];
						if(!in_array($ms_code, $lstStock_sold)) {
							$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
							$clean_code = $clsCrawl->getCodeNotTemplate($ms_code);
							
							$arr_data_code[$clean_code]["stock_id"] = $oneStock["stock_id"] ?? $oneStock[$clsStock->pkey];
							$arr_data_code[$clean_code]["oneStock"] = $oneStock;
							$arr_data_code[$clean_code]["min"] = $min;
							$arr_data_code[$clean_code]["max"] = $max;

							if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
								$arr_code_old[] = $oneStock["ms_code"];
							}
						}
					}
				}
				$arr_ms_code_new = array_diff($arr_ms_code, $arr_code_old);
				$total_stock_new = count($arr_ms_code_new);
			}
			$lstStock = array_values($arr_data_code);

			// ==========================================
			// Cập nhật các căn thành đã bán (Xử lý Batch)
			// ==========================================
			$g_cond = "`agency_id`='{$agency_id}' AND `status_id` > 0 AND `status_id` <> '"._STOCK_STATUS_SOLD_ID."' AND `stock_type`='{$stock_type}' AND `block_id`='{$block_id}'";
			if(!empty($arr_stock_id_not_in)){
				$g_cond .= " AND `stock_id` NOT IN (".implode(',', $arr_stock_id_not_in).")";
			}
			$list_sold_stocks = $clsStock->getAll($g_cond, "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`");

			if(!empty($list_sold_stocks)){
				// Lấy trước toàn bộ Meta Data để tránh N+1 Query
				$sold_stock_ids = array_column($list_sold_stocks, $clsStock->pkey);
				$meta_list = $clsStockMeta->getAll("`stock_id` IN (".implode(',', $sold_stock_ids).")", "{$clsStockMeta->pkey},stock_id,logs");
				$mapStockMeta = [];
				if($meta_list) {
					foreach($meta_list as $meta) $mapStockMeta[$meta['stock_id']] = $meta;
				}

				foreach($list_sold_stocks as $val){
					$stock_id = $val[$clsStock->pkey];
					$more_info = $clsISO->to_array_json($val['more_information']);
					$more_info['status_id'] = _STOCK_STATUS_SOLD_ID;
					$more_info['user_id_update_sold'] = 0;

					$oneStockMeta = $mapStockMeta[$stock_id] ?? null;
					$logs = !empty($oneStockMeta['logs']) ? $clsISO->to_array_json($oneStockMeta['logs']) : [];

					$logs[$clsISO->getUniqid()] = [
						'reg_date' => $currentTime, 
						'user_id' => 0,
						'from_id' => $val['status_id'],
						'to_id' => _STOCK_STATUS_SOLD_ID,
						'field' => 'status_id',
						'from' => '_front',
					];

					$is_stock_upd = $clsStock->updateOne($stock_id, [
						'ms_date' => $currentTime,
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_info, JSON_UNESCAPED_UNICODE)
					]);

					if($is_stock_upd) {
						++$total_stock_sold;
						if($oneStockMeta){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], [
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => $currentTime
							]);	
						} else {
                            $clsStockMeta->insert([
                                'stock_id' => $stock_id,
                                'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
                                'reg_date' => $currentTime,
                                'upd_date' => $currentTime
                            ]);
                        }
					}
				}
			}

			// ==========================================
			// Cập nhật danh sách chứng khoán / BĐS (Xử lý Batch)
			// ==========================================
			if(!empty($lstStock)) {
				// Tương tự, lấy trước Meta cho lstStock
				$lst_stock_ids = array_filter(array_column($lstStock, 'stock_id'));
				$mapStockMeta = [];
				if(!empty($lst_stock_ids)){
					$meta_list = $clsStockMeta->getAll("`stock_id` IN (".implode(',', $lst_stock_ids).")", "{$clsStockMeta->pkey},stock_id,logs");
					if($meta_list) {
						foreach($meta_list as $meta) $mapStockMeta[$meta['stock_id']] = $meta;
					}
				}
				$ms_codes = [];

				foreach ($lstStock as $v_stock){
					$oneStock = $v_stock["oneStock"] ?? [];
					$stock_id = $v_stock['stock_id'] ?? 0;
					$ms_code = $oneStock["ms_code"] ?? $v_stock["ms_code"];
					
					if($stock_id) {
						$ms_codes[] = $ms_code;
						$lst_stock_id[] = $stock_id;
					}

					// Fetch giá
					$total_price = $clsISO->get_price_field($v_stock, "total_price", 0);
					$total_price_vat = $clsISO->get_price_field($v_stock, "total_price_vat", 0);
					$total_price_early = $clsISO->get_price_field($v_stock, "total_price_early", 0);
					$total_price_progress = $clsISO->get_price_field($v_stock, "total_price_progress", 0);
					$total_price_bank = $clsISO->get_price_field($v_stock, "total_price_bank", 0);
					$total_price_bank_half = $clsISO->get_price_field($v_stock, "total_price_bank_half", 0);

					$more_info_stock = $clsISO->to_array_json($oneStock['more_information'] ?? '');
					$oneStockMeta = $mapStockMeta[$stock_id] ?? null;
					$logs = !empty($oneStockMeta['logs']) ? $clsISO->to_array_json($oneStockMeta['logs']) : [];
					$check_log = 0;
					$upd_field = ['agency_id' => $agency_id, 'upd_date' => $currentTime];
					$more_info_stock['ms_code'] = $ms_code;
					$more_info_stock['agency_id'] = $agency_id;

					// Cập nhật các trường động
					$fields_to_check = ['DT_TT', 'csbh', 'date_deposit_sign', 'DT_Tim'];
					foreach($fields_to_check as $f) {
						if(!empty($v_stock[$f])) $more_info_stock[$f] = $v_stock[$f];
					}

					// Xử lý giá
					if(!empty($total_price) && $total_price != ($more_info_stock["total_price"] ?? 0)) {
						$logs = $clsCrawl->renderArrayLog($logs, "total_price", $more_info_stock["total_price"] ?? 0, $total_price);
                        $more_info_stock['total_price'] = $total_price;
						$check_log = 1;
					}

					if(!empty($total_price_vat)) {
						$upd_field['total_price_vat'] = $total_price_vat;
						if($total_price_vat != ($more_info_stock['total_price_vat'] ?? 0)) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat", $oneStock["total_price_vat"] ?? 0, $total_price_vat);	
							$check_log = 1;
						}
						$more_info_stock['total_price_vat'] = $total_price_vat;
					} else if(!empty($total_price) && empty($total_price_vat)) {
						$total_price_vat = round($total_price * _PERCENT_PRICE_VAT);
						$upd_field['total_price_vat'] = $total_price_vat;
						if($total_price_vat != ($more_info_stock['total_price_vat'] ?? 0)) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat", $oneStock["total_price_vat"] ?? 0, $total_price_vat);
							$check_log = 1;
						}
						$more_info_stock["total_price_vat"] = $total_price_vat;
					}

					// Xử lý logs cho các loại giá khác
					$price_fields = [
						'total_price_early' => $total_price_early,
						'total_price_progress' => $total_price_progress,
						'total_price_bank' => $total_price_bank,
						'total_price_bank_half' => $total_price_bank_half
					];
					foreach($price_fields as $p_key => $p_val) {
						if(!empty($p_val) && $p_val != ($more_info_stock[$p_key] ?? 0)) {
							$logs = $clsCrawl->renderArrayLog($logs, $p_key, $more_info_stock[$p_key] ?? 0, $p_val);
                            $more_info_stock[$p_key] = $p_val;
							$check_log = 1;
						}
					}

					if(!empty($agency_id) && $agency_id != ($oneStock["agency_id"] ?? '')){
						$logs = $clsCrawl->renderArrayLog($logs, "agency_id", $oneStock["agency_id"] ?? '', $agency_id);
						$check_log = 1;	
					}									
					if(!empty($stock_status_id)) {
						$upd_field['status_id'] = $stock_status_id;
						$more_info_stock['status_id'] = $stock_status_id;
						if($stock_status_id != ($oneStock["status_id"] ?? '')) {
							$logs = $clsCrawl->renderArrayLog($logs, "status_id", $oneStock["status_id"] ?? '', $stock_status_id);	
							$check_log = 1;
						}									
					}

					// Xử lý Price Sheets
					$price_sheet_title = $v_stock["price_sheet_title"] ?? "PTG TẠM TÍNH";
					$price_sheet_link = $v_stock["price_sheet_link"] ?: ($v_stock["link_smartchip"] ?? "");
					
					if(!empty($price_sheet_title) && !empty($price_sheet_link)){
						$price_sheet_id = $clsISO->getUniqid();
						$more_info_stock['price_sheets'] = [
							$price_sheet_id => [
								'sheets' => [
									$clsISO->getUniqid() => [
										'title' => $price_sheet_title,
										'image' => $price_sheet_link
									]
								],
								'reg_date' => $currentTime,
								'upd_date' => $currentTime,
								'csbh' => $v_stock["csbh"] ?? "",
								'user_id' => 0,
								'user_update_id' => 0,
								'from' => "_front"
							]
						];
					}
					//khử log
					$result = [];
					$built_keys = [];
					foreach ($logs as $key => $item) {
						$unique_key = $item['field'] . '|' . ($item['from_value'] ?? '') . '|' . ($item['to_value'] ?? '');
						if (!isset($built_keys[$unique_key])) {
							$result[$key] = $item; 
							$built_keys[$unique_key] = true;
						}
					}
					$logs = $result;
					$upd_field['more_information'] = json_encode($more_info_stock, JSON_UNESCAPED_UNICODE);
					if($stock_id && $clsStock->updateOne($stock_id, $upd_field)){
						++$total_updated;
						$arr_upd[] = $ms_code;
						if($oneStockMeta && !empty($logs) && !empty($check_log)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], [
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => $currentTime
							]);	
						} else if (!$oneStockMeta && !empty($logs) && !empty($check_log)) {
                            $clsStockMeta->insert([
                                'stock_id' => $stock_id,
                                'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
                                'reg_date' => $currentTime,
                                'upd_date' => $currentTime
                            ]);
                        }
					} else {
						$arr_not_upd[] = $ms_code;
					}
				}
			}
		} 

        // KẾT THÚC TRANSACTION DB
		if (method_exists($dbconn, 'CompleteTrans')) $dbconn->CompleteTrans();
        else if (method_exists($dbconn, 'commit')) $dbconn->commit();

		// Logic tạo Logs
		$arr_ms_code_new = array_intersect($arr_ms_code_new, $arr_upd);
		$total_stock_new = count($arr_ms_code_new);
		
		$arr_data_upd = [
			"total_stock_sold" => $total_stock_sold,
			"total_stock_new" => $total_stock_new,
			"total_stock" => $total_updated,
			"stock_not_upd" => $arr_not_upd,
			"data_log" => $arr_data,
			"type" => 1,
			"result_type" => "update",
		];
		$clsLogCrawl->log($agency_id, $block_id, $arr_data_upd, $stock_type);
		
		if(!empty($ms_codes)){
			$clsTmpStockAgent->updateStockTmp($agency_id, $stock_type, $block_id, $ms_codes);			
		}

		$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y', $currentTime)."'", "{$clsStockLog->pkey},`more_information`");
		$encoded_lst_stock_id = json_encode($lst_stock_id ?? [], JSON_UNESCAPED_UNICODE);
		
		if(!empty($tmp)){
			$clsStockLog->updateOne($tmp[$clsStockLog->pkey], ['more_information' => $encoded_lst_stock_id]);
		} else {
			$clsStockLog->insert([
				'stock_type' => $stock_type,
				'agency_id' => $agency_id,
				'block_id' => $block_id,
				'more_information' => $encoded_lst_stock_id,
				'reg_date' => $currentTime,
				'user_id' => 0
			]);
		}

		$status_code = 200;
		$apiresults = [
			'error' => 0, 
			'result' => 'success', 
			'message' => [
				'spreadsheetId_crawl' => $spreadsheetId_crawl,
				'msg' => "Tổng cộng: {$total_updated}, Đã bán: {$total_stock_sold}, Nhập mới: {$total_stock_new}",
				'total_updated' => $total_updated,
				'total_stock_sold' => $total_stock_sold,
				'total_stock_new' => $total_stock_new,
				'stock_not_upd' => $arr_not_upd,
				'arr_upd' => $arr_upd,
			]
		];
	} else {
		$error = $helper->getFieldValue("error", $inputs);
		$clsLogCrawl->log($agency_id, $block_id, $error, $stock_type);
		
		$status_code = 410;
		$apiresults = [
			'error' => 1, 
			'result' => 'success', 
			'message' => [
				'spreadsheetId_crawl' => $spreadsheetId_crawl,
				'arr_upd' => $arr_upd ?? [],
			]
		];
	}
	
	echo echoResponse($status_code, $apiresults);
});
$app->post('/crawl_highfloorOLD', function ($request, $response) use ($app){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsLogCrawl = new LogCrawl();
	$clsStockMeta = new StockMeta();
	$clsCrawl = new Crawl();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockLog = new StockLog();
	$clsStockAgent = new StockAgent();
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);	
	#- Required Library
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$decoder = new Webmozart\Json\JsonDecoder();
	$cachedFile = DIR_CACHE_JSON.'/crawl/stock/stock_sold_admin.json';
	$arrCacheSold = $decoder->decodeFile($cachedFile);
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
	###
	$inputs = $request->getParsedBody();
	$agency_id  =$helper->getFieldValue("agency_id", $inputs);
	$block_id  =$helper->getFieldValue("target_id", $inputs);
	$stock_type  =$helper->getFieldValue("stock_type", $inputs);
	$spreadsheetId_crawl  =$helper->getFieldValue("spreadsheetId_crawl", $inputs);
	$arr_data  = $helper->getFieldValue("arr_data", $inputs);
	$arr_price_min_max = !empty($arr_data["arr_price_min_max"]) ? $arr_data["arr_price_min_max"] : array();
	$oneAgency = $helper->getFieldValue("oneAgency", $arr_data);
	$more_information = $clsISO->to_array_json($oneAgency["more_information"]);
	if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
		$arr_status_id[$val[$clsProperty->pkey]] = $more_information['stock_status_id'];
	}
	$stock_status_id = !empty($arr_status_id[$agency_id]) ? $arr_status_id[$agency_id] : _STOCK_STATUS_LOCK_ID;
	#cache stock sold	
	$lstStock_sold = !empty($arrCacheSold[$stock_type][$agency_id][$block_id]) ? $arrCacheSold[$stock_type][$agency_id][$block_id] : array();
	###
//	=======================
	if(!empty($inputs["result"])) {
		$arr_not_upd = $arr_upd = $arr_data = $arr_ms_code_new = array();
		$total_stock_sold = $total_stock_new = $total_updated = 0;
		$arr_data  =$helper->getFieldValue("tblData", $inputs);
		$arr_data = !empty($arr_data) ? $arr_data : array();
		if(!empty($arr_data)) {
			/*giá min max*/
			$oneBlock = $arr_block_cached[$block_id];
			$min = $max = "";
			if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
				$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$block_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$block_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
			}
			$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
			$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
			/*end giá min max*/
			
			$arr_cdt_not_update = [];
			if($agency_id != 10954 && $block_id == 10684){
				$lstMs_code = $clsStockAgent->getAll("`agency_id`='10954' AND `target_id`='$block_id' AND `stock_type`='{$stock_type}'","ms_code");
				if(!empty($lstMs_code)) {
					foreach ($lstMs_code as $key => $val){
						$arr_cdt_not_update[] = $clsCrawl->getCodeNotTemplate($val["ms_code"]);
					}
				}
			}
			$arr_data_code = $lstStock = array();
			$total_record = @count($arr_data);
			$arr_stock_code_not_in = $arr_stock_id_not_in = array();
			foreach ($arr_data as $key => $val) {
				$ms_code = $val['ms_code'];
				if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
					$val['ms_code'] = $ms_code;
					$ms_code = $clsCrawl->getCodeNotTemplate($ms_code);
					if(!$clsISO->checkItemInArray($ms_code,$arr_cdt_not_update)) {
						$arr_data_code[$ms_code] = $val;
					}
				}
				unset($ms_code);
			}
//			$clsISO->print_pre($arr_data_code);die;
			if(!empty($arr_data_code)) {
				$arr_code_old = [];
				$arr_ms_code = array_keys($arr_data_code);
				$str_code_in = implode("','",$arr_ms_code);
//				$dbconn->debug=true;
				$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$block_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `mscode` IN ('".$str_code_in."')");
//				echo "<pre>";
//				var_dump($list_agency_stocks);
//				echo "</pre>";die;
				if(!empty($list_agency_stocks)) {
					foreach ($list_agency_stocks as $k_stock => $oneStock) {
						$ms_code = $oneStock["ms_code"];
						if(!$clsISO->checkItemInArray($ms_code,$lstStock_sold)) {
							$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
							$ms_code = $clsCrawl->getCodeNotTemplate($ms_code);
							$arr_stock_code_not_in[] = $oneStock["ms_code"];
							$arr_data_code[$ms_code]["stock_id"] = $oneStock["stock_id"];
							$arr_data_code[$ms_code]["oneStock"] = $oneStock;
							if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
								$arr_code_old[] = $oneStock["ms_code"];
							}
							/*giá min max*/
							$arr_data_code[$ms_code]["min"] = $min;
							$arr_data_code[$ms_code]["max"] = $max;
							/*end giá min max*/
						}
					}
					unset($list_agency_stocks,$ms_code);
				}
				$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
				$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
			}
			$lstStock = array_values($arr_data_code);
//			$clsISO->print_pre($lstStock);die;
			// C?p nh?t các can thành dã bán
			$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`";
			$g_cond = "`agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') 
			and `stock_type`='{$stock_type}' AND `block_id`='{$block_id}'";
			if(!empty($arr_stock_id_not_in)){
				$g_cond.= " and `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
			}
//			$clsStock->setDeBug(1);
			$list_sold_stocks = $clsStock->getAll($g_cond, $field);
//			 var_dump($list_sold_stocks); die;
			if(!empty($list_sold_stocks)){
				foreach($list_sold_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					###
					$m_field = "{$clsStockMeta->pkey},logs";
					$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $val[$clsStock->pkey],
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					}
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$more_information['user_id_update_sold'] = 0;
					$logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(), 
						'user_id' => 0,
						'from_id' => $val['status_id'],
						'to_id' => _STOCK_STATUS_SOLD_ID,
						'field' => 'status_id',
						'from' => '_front',
					);
					if($clsStock->updateOne($val[$clsStock->pkey], array(
						'ms_date' => time(),
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))) {
						++$total_stock_sold;
						if(!empty($oneStockMeta)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					}
				}
				unset($list_sold_stocks);
			}
			if(!empty($lstStock)) {
				foreach ($lstStock as $k_stock => $v_stock){
					$oneStock = $v_stock["oneStock"];
					$stock_id = $v_stock['stock_id'];
					$ms_code = $v_stock["ms_code"];
					if(!empty($oneStock)) {
						$ms_code = $oneStock["ms_code"];
						$ms_codes[] = $ms_code;
						$lst_stock_id[] = $stock_id;
					}					
					$total_price = $clsISO->get_price_field($v_stock, "total_price", 0);
					$total_price_vat = $clsISO->get_price_field($v_stock, "total_price_vat", 0);
					$total_price_early = $clsISO->get_price_field($v_stock, "total_price_early", 0);
					$total_price_progress = $clsISO->get_price_field($v_stock, "total_price_progress", 0);
					$total_price_bank = $clsISO->get_price_field($v_stock, "total_price_bank", 0);
					$total_price_bank_half = $clsISO->get_price_field($v_stock, "total_price_bank_half", 0);
					#
					$csbh = !empty($v_stock["csbh"]) ?  $v_stock["csbh"] : "";
					$price_sheet_link = !empty($v_stock["price_sheet_link"]) ?  $v_stock["price_sheet_link"] : "";
					$link_smartchip = !empty($v_stock["link_smartchip"]) ?  $v_stock["link_smartchip"] : "";
					$price_sheet_title = !empty($v_stock["price_sheet_title"]) ?  $v_stock["price_sheet_title"] : "PTG TẠM TÍNH";
					$DT_TT = !empty($v_stock["DT_TT"]) ?  $v_stock["DT_TT"] : 0;
					$DT_Tim = !empty($v_stock["DT_Tim"]) ?  $v_stock["DT_Tim"] : 0;
					$date_deposit_sign = !empty($v_stock["date_deposit_sign"]) ?  $v_stock["date_deposit_sign"] : "";
					#
					$more_information_stock = $oneStock['more_information'];
					$more_information_stock = $clsISO->to_array_json($more_information_stock);
					#
					$logs = array(); $m_field = "{$clsStockMeta->pkey},`logs`"; 
					$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $stock_id,
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
					}
					$upd_field['agency_id'] = $agency_id;
					$more_information_stock['ms_code'] = $ms_code;
					$more_information_stock['agency_id'] = $agency_id;
					if(!empty($DT_TT)) $more_information_stock['DT_TT'] = $DT_TT;
					if(!empty($csbh)) $more_information_stock['csbh'] = $csbh;
					if(!empty($date_deposit_sign)) $more_information_stock['date_deposit_sign'] = $date_deposit_sign;
					if(!empty($DT_Tim)) $more_information_stock['DT_Tim'] = $DT_Tim;
					if(!empty($total_price) && $total_price != $more_information_stock["total_price"]) {
						$more_information_stock['total_price'] = $total_price;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price",$more_information_stock["total_price"],$total_price);
					}
					if(!empty($total_price) && $total_price != $more_information_stock["total_price"]) {
						$more_information_stock['total_price'] = $total_price;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price",$more_information_stock["total_price"],$total_price);
					}
					if(!empty($total_price_vat)) {
						$upd_field['total_price_vat'] = $total_price_vat;
						$more_information_stock['total_price_vat'] = $total_price_vat;
						if($total_price_vat != $oneStock["total_price_vat"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);	
						}
					}else if(!empty($total_price) && empty($total_price_vat)){ // có giá chua VAT và không có giá full VAT
						$total_price_vat = $total_price * _PERCENT_PRICE_VAT; // * 1.12
						$total_price_vat = round($total_price_vat);
						$upd_field['total_price_vat'] = $total_price_vat;
						$more_information_stock["total_price_vat"] = $total_price_vat;
						if($total_price_vat != $oneStock["total_price_vat"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);
						}
					}else if(!empty($total_price_early) && empty($total_price_vat)){
						$upd_field['total_price_vat'] = $total_price_early;
						if($total_price_early != $oneStock["total_price_vat"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_early);
						}
					}
					if(!empty($total_price_early) && $total_price_early != $more_information_stock["total_price_early"]) {
						$more_information_stock['total_price_early'] = $total_price_early;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_early",$more_information_stock["total_price_early"],$total_price_early);
					}
					if(!empty($total_price_progress) && $total_price_progress != $more_information_stock["total_price_progress"]) {
						$more_information_stock['total_price_progress'] = $total_price_progress;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_progress",$more_information_stock["total_price_progress"],$total_price_progress);
					}
					if(!empty($total_price_bank) && $total_price_bank != $more_information_stock["total_price_bank"]) {
						$more_information_stock['total_price_bank'] = $total_price_bank;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank",$more_information_stock["total_price_bank"],$total_price_bank);
					}
					if(!empty($total_price_bank_half) && $total_price_bank_half != $more_information_stock["total_price_bank_half"]) {
						$more_information_stock['total_price_bank_half'] = $total_price_bank_half;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank_half",$more_information_stock["total_price_bank_half"],$total_price_bank_half);
					}
					if(!empty($agency_id)) {
						$upd_field['agency_id'] = $agency_id;
						$more_information_stock['agency_id'] = $agency_id;
						if($agency_id != $oneStock["agency_id"]){
							$logs = $clsCrawl->renderArrayLog($logs,"agency_id",$oneStock["agency_id"],$agency_id);	
						}									
					}
					if(!empty($stock_status_id)) {
						$upd_field['status_id'] = $stock_status_id;
						$more_information_stock['status_id'] = $stock_status_id;
						if($stock_status_id != $oneStock["status_id"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"status_id",$oneStock["status_id"],$stock_status_id);	
						}									
					}
					###
					$sheets = $more = array();
					$price_sheets = !empty($more_information_stock["price_sheets"]) ? $more_information_stock["price_sheets"] : array();
					if(!empty($price_sheet_title) && (!empty($price_sheet_link) || !empty($link_smartchip))){
						$price_sheet_link = !empty($price_sheet_link) ? $price_sheet_link : $link_smartchip;
						$sheets[$clsISO->getUniqid()] = array(
							'title' => $price_sheet_title,
							'image' => $price_sheet_link
						);
					}
					if(!empty($sheets)){
						$price_sheets = array();
						$price_sheet_id = $clsISO->getUniqid();
						$price_sheets[$price_sheet_id]['sheets'] = $sheets;
						$price_sheets[$price_sheet_id]['reg_date'] = time();
						$price_sheets[$price_sheet_id]['upd_date'] = time();
						$price_sheets[$price_sheet_id]['csbh'] = $csbh;
						$price_sheets[$price_sheet_id]['user_id'] = 0;
						$price_sheets[$price_sheet_id]['user_update_id'] = 0;
						$price_sheets[$price_sheet_id]['from'] = "_front";
					}
					$more_information_stock['price_sheets']= $price_sheets;
					$upd_field['upd_date'] = time();
					$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
					if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
						++$total_updated;
						$arr_upd[] = $ms_code;
						if(!empty($oneStockMeta) && !empty($logs)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					} else{
						$arr_not_upd[] = $ms_code;
					}
				}
			}
		} else {
			$field = "{$clsStock->pkey},`status_id`,`more_information`";
			$list_stocks = $clsStock->getAll("`stock_type`='".$stock_type."' AND `agency_id`='{$agency_id}' 
			AND `block_id`='{$block_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')", $field);
			if(!empty($list_stocks)){
				$total_updated = 0;
				foreach($list_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$more_information['user_id_update_sold'] = $profile_id;
					##
					$m_field = "{$clsStockMeta->pkey},logs";
					$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $val[$clsStock->pkey],
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					}
					$logs[$clsISO->getUniqid()] = array(
							'reg_date' => time(), 
							'user_id' => 0,
							'from_id' => $val['status_id'],
							'to_id' => _STOCK_STATUS_SOLD_ID,
							'field' => 'status_id',								
							'from' => '_front',
						);
					if($clsStock->updateOne($val[$clsStock->pkey], array(
						'ms_date' => time(),
						'upd_date' => time(),
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))) {
						++$total_stock_sold;
						$arr_upd[] = $ms_code;
						if(!empty($oneStockMeta)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					}
				}
				unset($tmp);
			}
		}
		//log c?p nh?t
		$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
		$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
		#log new
		$arr_data_upd = array(
			"total_stock_sold"	=>	$total_stock_sold,
			"total_stock_new"	=>	$total_stock_new,
			"total_stock"	=>	$total_updated,
			"stock_not_upd"	=>	$arr_not_upd,
			"data_log"	=>	$arr_data,
			"type"	=>	1,	//0:t?ng h?p,1:drive,2:hình ?nh,3:copy,
			"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
		);
		$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
		#luu bang tam
		$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);			
		#tong hop quy dai ly
		/*$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);*/
		// Logs d? báo thay d?i file
		$logs_field = "{$clsStockLog->pkey},`more_information`";
		$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' 
			AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
		if(!empty($tmp)){
			$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
				'more_information' => json_encode($lst_stock_id, JSON_UNESCAPED_UNICODE)
			));
		} else {
			$clsStockLog->insert(array(
				'stock_type' => $stock_type,
				'agency_id' => $agency_id,
				'block_id' => $block_id,
				'more_information' => json_encode($lst_stock_id, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'user_id' => 0
			));
		}
		/** End */
		$result = array(
			'spreadsheetId_crawl'	=>	$spreadsheetId_crawl,
			'msg' => 'Tổng cộng: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
			'total_updated' => $total_updated,
			'total_stock_sold' => $total_stock_sold,
			'total_stock_new' => $total_stock_new,
			'stock_not_upd' => $arr_not_upd,
			'arr_upd' => $arr_upd,
		);
		$status_code = 200;
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'message' => $result
		);
	}else{
		$error  =$helper->getFieldValue("error", $inputs);
		$clsLogCrawl->log($agency_id,$block_id, $error, $stock_type);
		
		$result = array(
			'spreadsheetId_crawl'	=>	$spreadsheetId_crawl,
			'arr_upd' => $arr_upd,
		);		
		$status_code = 410;
		$apiresults = array(
			'error' => 1, 
			'result' => 'success', 
			'message' => $result
		);
	}
	// Return
	echo echoResponse($status_code, $apiresults);
});
$app->get('/get_spreadsheet_lowfloor', function ($request, $response) use ($app) {
	global $dbconn, $clsISO,$profile_id,$core;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsProperty = new Property();
	$clsISO = new ISO();
	$clsConfiguration = new Configuration();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_agency_cronjob_lowfloor.json';
	###
	$decoder = new Webmozart\Json\JsonDecoder();		
	$encoder = new Webmozart\Json\JsonEncoder();
	if(file_exists($cachedFile)){
		$lstCache = $decoder->decodeFile($cachedFile);
	}
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$cond = "`property_type`='_AGENCY' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL";	
//	$cond .= " AND (`property_id`='267' || `property_id`='268')";
	$lstAgency = $clsProperty->getAll($cond,$clsProperty->pkey.",more_information");
	$lstCrawl = $crawl_first = $crawl = [];
	$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == $stock_type) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	$cachedFileConfig = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
	$arr_column_data = array();
	if(file_exists($cachedFileConfig)){
		$arr_column_data = $decoder->decodeFile($cachedFileConfig);			
	}
	$config_column_data = json_encode($arr_column_data,JSON_UNESCAPED_UNICODE);
	
	$arr_status_id = [];
	if(!empty($lstAgency)) {
		$is_crawl = $i = 0; $total_cronjob = 3;
		foreach ($lstAgency as $key => $val) {
			$more_information = $clsISO->to_array_json($val['more_information']);
			$crawl_lowfloor = !empty($more_information["crawl_lowfloor"]) ? $more_information["crawl_lowfloor"] : array();
			$project_agency_crawl = !empty($lstCache[$val[$clsProperty->pkey]]) ? $lstCache[$val[$clsProperty->pkey]] : array();
			if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
				$arr_status_id[$val[$clsProperty->pkey]] = $more_information['stock_status_id'];
			}
			if(!empty($crawl_lowfloor)) {
				foreach ($crawl_lowfloor as $project_id => $v_cr) {
					if(!empty($v_cr['is_crawl']) && !empty($v_cr["sheet_name"]) && !empty($v_cr["sheetID"])) {
						$ranges = explode("|",$v_cr["sheet_name"]);
						foreach ($ranges as $k => $range) {
							$ranges[$k] = $range."!A1:AA500";
						}
						if($i < $total_cronjob) {
							$crawl_tmp[] = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$project_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
								"crawl_lowfloor"	=>	$v_cr,
								"oneAgency"	=>	$val,
								"config_column_data"	=>	$config_column_data,
							];
							++$i;
						}
						if(empty($project_agency_crawl) || (!empty($project_agency_crawl) 
							&& !$clsISO->checkItemInArray($project_id,$project_agency_crawl))) {	
							$crawl = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$project_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
								"crawl_lowfloor"	=>	$v_cr,
								"oneAgency"	=>	$val,
								"config_column_data"	=>	$config_column_data,
							];
							++$is_crawl;
							if($is_crawl <= $total_cronjob) {								
								$arr_crawl[] = $crawl;
							}							
							unset($crawl);
						}
					}
				}
			}
			if($is_crawl == $total_cronjob) {
				break;
			}
		}
	}
	if(empty($arr_crawl)) {
		$arr_crawl = $crawl_tmp;
		$lstCache = array();
	}
	foreach ($arr_crawl as $key => $val) {
		if(!$clsISO->checkItemInArray($val["target_id"],$lstCache[$val['agency_id']])) {
			$lstCache[$val['agency_id']][] = $val['target_id'];
		}
	}
	$apiresults = array(
		'error' => 0,
		'result' => 'success',
		'arr_crawl'	=>	$arr_crawl,
		'arr_price_min_max'	=>	$arr_price_min_max,
	);
	$encoder->encodeFile($lstCache, $cachedFile);
	// Return
	echo echoResponse('200',$apiresults);
});
$app->post('/crawl_lowfloor', function ($request, $response) use ($app){
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsLogCrawl = new LogCrawl();
	$clsStockMeta = new StockMeta();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockLog = new StockLog();

	// Bỏ "new Crawl()" thừa (không dùng trong endpoint này) → tránh OAuth ~1s.
	// Gộp N+1 tra cứu property: bọc $clsProperty memo getByCond (type/status/bank/agent... lặp
	// giữa các căn → ~12 query/căn còn ~1 query/điều kiện). insert() xoá cache để căn sau thấy
	// property vừa tạo. Chỉ uỷ quyền method dùng ở đây: getByCond/insert/getMaxId/getMaxorderNo/pkey.
	$clsProperty = new class($clsProperty) {
		private $real;
		private $cache = array();
		public $pkey;
		public function __construct($real) { $this->real = $real; $this->pkey = $real->pkey; }
		public function getByCond(...$args) {
			$key = md5(serialize($args));
			if (!array_key_exists($key, $this->cache)) {
				$this->cache[$key] = call_user_func_array(array($this->real, 'getByCond'), $args);
			}
			return $this->cache[$key];
		}
		public function insert($data) { $this->cache = array(); return $this->real->insert($data); }
		public function getMaxId() { return $this->real->getMaxId(); }
		public function getMaxorderNo() { return $this->real->getMaxorderNo(); }
	};
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$inputs = $request->getParsedBody();
	$agency_id  =$helper->getFieldValue("agency_id", $inputs);
	$target_id  =$helper->getFieldValue("target_id", $inputs);
	$stock_type  =$helper->getFieldValue("stock_type", $inputs);
	$spreadsheetId_crawl  =$helper->getFieldValue("spreadsheetId_crawl", $inputs);
	$arr_data  = $helper->getFieldValue("arr_data", $inputs);
	$arr_price_min_max = !empty($arr_data["arr_price_min_max"]) ? $arr_data["arr_price_min_max"] : array();
	$oneAgency = $helper->getFieldValue("oneAgency", $arr_data);
	$more_information = $clsISO->to_array_json($oneAgency["more_information"]);
	if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
		$arr_status_id[$val[$clsProperty->pkey]] = $more_information['stock_status_id'];
	}
	$stock_status_id = !empty($arr_status_id[$agency_id]) ? $arr_status_id[$agency_id] : _STOCK_STATUS_LOCK_ID;
	###
//	=======================
	if(!empty($inputs["result"])) {
		$arr_not_upd = $arr_upd = $arr_data = $arr_ms_code_new = array();
		$total_stock_sold = $total_stock_new = $total_updated = 0;
		$arr_data = !empty($inputs["tblData"]) ? $inputs["tblData"] : array();
		$list_stock_id = array();
		if(!empty($arr_data)) {
			$arr_data_code = $lstStock = array();
			$total_record = @count($arr_data);
			$arr_stock_code_not_in = $arr_stock_id_not_in = array();
			foreach ($arr_data as $key => $val) {
				$ms_code = $val['ms_code'];
				if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
					$val['ms_code'] = addslashes($ms_code);
					$arr_data_code[$ms_code] = $val;
				}
				unset($ms_code);
			}
//			$clsISO->print_pre($arr_data_code);die;
			if(!empty($arr_data_code)) {
				$arr_ms_code = array_keys($arr_data_code);
				$str_code_in = implode("','",$arr_ms_code);
				$arr_code_old = [];
				$list_agency_stocks = $clsStock->getAll("`project_id`='{$target_id}' AND `stock_type`='".$stock_type."' AND (`agency_id` <> '"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `ms_code` IN ('".$str_code_in."')",$clsStock->pkey.',more_information,ms_code,status_id,block_id');
				if(!empty($list_agency_stocks)) {
					foreach ($list_agency_stocks as $k_stock => $oneStock) {
						$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
						$arr_stock_code_not_in[] = $oneStock["ms_code"];
						$arr_data_code[$oneStock["ms_code"]]["stock_id"] = $oneStock["stock_id"];
						$arr_data_code[$oneStock["ms_code"]]["oneStock"] = $oneStock;
						if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
							$arr_code_old[] = $oneStock["ms_code"];
						}
						/*giá min max*/
						if(!empty($arr_price_min_max[$target_id])) {
							$price_min_max = isset($arr_price_min_max[$target_id][$oneStock["block_id"]]) ? $arr_price_min_max[$target_id][$oneStock["block_id"]] : $arr_price_min_max[$target_id][0];
						}
						$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
						$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
						$arr_data_code[$oneStock["ms_code"]]["min"] = $min;
						$arr_data_code[$oneStock["ms_code"]]["max"] = $max;
						/*end giá min max*/
					}
					unset($list_agency_stocks);
				}
				$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
				$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
			}
			$lstStock = array_values($arr_data_code);
			# C?p nh?t các can thành dã bán
			$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`";
			$g_cond = "`stock_type`='".$stock_type."' AND `project_id`='{$target_id}' AND `agency_id`='{$agency_id}' 
			AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
			if(!empty($arr_stock_id_not_in)){
				$g_cond.= " AND `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
			}
			if($target_id == 3) {
				$g_cond.= " AND `block_id`<>'10933'";
			}
			$list_sold_stocks = $clsStock->getAll($g_cond, $field);
			// var_dump($list_sold_stocks);die;
			if(!empty($list_sold_stocks)){
				foreach($list_sold_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information['user_id_update_sold'] = 0;
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$m_field = "{$clsStockMeta->pkey},logs";
					$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $val[$clsStock->pkey],
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					}
					$logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(), 
						'user_id' => 0,
						'from_id' => $val['status_id'],
						'to_id' => _STOCK_STATUS_SOLD_ID,
						'field' => 'status_id',
						'from' => '_front',
					);
					if($clsStock->updateOne($val[$clsStock->pkey], array(
						'ms_date' => time(),
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))) {
						++$total_stock_sold;
						if(!empty($oneStockMeta) && !empty($logs)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					}
				}
				unset($list_sold_stocks);
			}
			if(!empty($lstStock)) {
				// Prefetch StockMeta 1 query (thay N+1 getByCond/căn) — như /crawl_highfloor.
				$__updIds = array();
				foreach($lstStock as $__v) if(!empty($__v['stock_id'])) $__updIds[] = (int)$__v['stock_id'];
				$metaMap = array();
				if(!empty($__updIds)) {
					foreach($clsStockMeta->getAll("`stock_id` IN (".implode(',', $__updIds).")", "{$clsStockMeta->pkey},stock_id,logs") as $__m) $metaMap[$__m['stock_id']] = $__m;
				}
				foreach($lstStock as $k_stock => $v_stock){
					$oneStock = $v_stock["oneStock"];
					$stock_id = $v_stock['stock_id'];
					$ms_code = $oneStock["ms_code"];
					$ms_code = preg_replace('/\s+/', '', $ms_code);
					$type = !empty($v_stock["type_id"]) ? $v_stock["type_id"] : "";
					$total_price_vat = !empty($v_stock["total_price_vat"]) 
						? $clsStock->getPriceOriginV2($v_stock["total_price_vat"],$min,$max) : 0;
					$total_price_early = !empty($v_stock["total_price_early"]) 
						? $clsStock->getPriceOriginV2($v_stock["total_price_early"],$min,$max) : 0;
					$status = !empty($v_stock["status_id"]) ? $v_stock["status_id"] : "";
					$stock_hold = !empty($v_stock["stock_hold_id"]) ? $v_stock["stock_hold_id"] : "";
					$contract_type = !empty($v_stock["contract_type_id"]) ? $v_stock["contract_type_id"] : "";
					$contract_subject = !empty($v_stock["contract_subject_id"]) ? $v_stock["contract_subject_id"] : "";
					$invest_fund = !empty($v_stock["invest_fund_id"]) ? $v_stock["invest_fund_id"] : "";
					$sale_status = !empty($v_stock["sale_status_id"]) ? $v_stock["sale_status_id"] : "";
					$agent_lock = !empty($v_stock["agent_lock_id"]) ? $v_stock["agent_lock_id"] : "";
					$deposit_agent = !empty($v_stock["deposit_agent_id"]) ? $v_stock["deposit_agent_id"] : "";
					$bank_second = !empty($v_stock["bank_second_id"]) ? $v_stock["bank_second_id"] : "";
					$bank = !empty($v_stock["bank_id"]) ? $v_stock["bank_id"] : "";
					$home_direction = !empty($v_stock["home_direction_id"]) ? $v_stock["home_direction_id"] : "";
					$TCBG = !empty($v_stock["TCBG"]) ? $v_stock["TCBG"] : "";
					$notes = !empty($v_stock["notes"]) ? $v_stock["notes"] : "";
					$csbh = !empty($v_stock["csbh"]) ? $v_stock["csbh"] : "";
					$DT_TT = !empty($v_stock["DT_TT"]) ? $v_stock["DT_TT"] : "";
					$price_temporary_ns = !empty($v_stock["price_temporary_ns"]) ? $v_stock["price_temporary_ns"] : "";
					$link_smartchip = !empty($v_stock["link_smartchip"]) ? $v_stock["link_smartchip"] : "";
					if(empty($oneStock)) {
						$arr_not_upd[] = $ms_code;
						continue;
					}
					$logs = array(); $m_field = "{$clsStockMeta->pkey},`logs`";
					$oneStockMeta = isset($metaMap[$stock_id]) ? $metaMap[$stock_id] : null; // prefetch thay N+1 getByCond
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $oneStock[$clsStock->pkey],
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
					}
					$more_information_stock = $oneStock['more_information'];
					$more_information_stock = $clsISO->to_array_json($more_information_stock);	
					$upd_field = array(); // reset đầu mỗi căn: tránh field căn trước rò sang căn sau (status_id/type_id... dính nhầm)
					$upd_field['agency_id'] = $agency_id;
					$upd_field['status_id'] = $stock_status_id;
					$more_information_stock['ms_code'] = $ms_code;
					$more_information_stock['agency_id'] = $agency_id;				
					$more_information_stock['status_id'] = $stock_status_id;
					if(!empty($type)) {
						$tmp = $clsProperty->getByCond("`property_type`='_TYPE_VILLA' AND (`property_code`='{$type}' OR `slug_vn`='".$clsISO->replaceSpace($type)."' OR `slug`='".$clsISO->replaceSpace($type)."')", $clsProperty->pkey);
						if(!empty($tmp)) {
							$type_id = $tmp[$clsProperty->pkey];
							$upd_field["type_id"] = $type_id;
							$more_information_stock["type_id"] = $type_id;
						}
					}
					if(!empty($status)) {
						$tmp = $clsProperty->getByCond("`property_type`='_STATUS' AND `slug`='".$clsISO->replaceSpace($status)."'", $clsProperty->pkey);
						if(!empty($tmp)) {
							$status_id = $tmp[$clsProperty->pkey];
							$upd_field["status_id"] = $status_id;
							$more_information_stock["status_id"] = $status_id;
						}
					}
					if(!empty($stock_hold)) {
						$tmp = $clsProperty->getByCond("`property_type`='_STOCK_HOLD' AND `slug`='".$clsISO->replaceSpace($stock_hold)."'", $clsProperty->pkey);
						if(!empty($tmp)) {
							$stock_hold_id = $tmp[$clsProperty->pkey];
							$more_information_stock["stock_hold_id"] = $stock_hold_id;
						}
					}
					if(!empty($contract_type)) {
						$tmp = $clsProperty->getByCond("`property_type`='_CONTRACT_TYPE' and `slug`='".$clsISO->replaceSpace($contract_type)."'", $clsProperty->pkey);
						if(!empty($tmp)){
							$contract_type_id = $tmp[$clsProperty->pkey];
						} else {
							$contract_type_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $contract_type_id,
								'property_type' => '_CONTRACT_TYPE',
								'title' => trim($contract_type),
								'slug' => $clsISO->replaceSpace($contract_type),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["contract_type_id"] = $contract_type_id;
					}
					if(!empty($contract_subject)) {
						$tmp = $clsProperty->getByCond("`property_type`='_CONTRACT_SUBJECT' AND `slug`='".$clsISO->replaceSpace($tblData[$i][$key])."'", $clsProperty->pkey);
						if(!empty($tmp)){
							$contract_subject_id = $tmp[$clsProperty->pkey];
						} else {
							$contract_subject_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $contract_subject_id,
								'property_type' => '_CONTRACT_SUBJECT',
								'title' => $contract_subject,
								'slug' => $clsISO->replaceSpace($contract_subject),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["contract_subject_id"] = $contract_subject_id;
					}
					if(!empty($invest_fund)) {
						$tmp = $clsProperty->getByCond("`property_type`='_INVEST_FUND' AND `slug`='".$clsISO->replaceSpace($invest_fund)."'", $clsProperty->pkey);
						if(!empty($tmp)){
							$invest_fund_id = $tmp[$clsProperty->pkey];
						} else {
							$invest_fund_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $invest_fund_id,
								'property_type' => '_INVEST_FUND',
								'title' => $invest_fund,
								'slug' => $clsISO->replaceSpace($invest_fund),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["invest_fund_id"] = $invest_fund_id;
					}
					if(!empty($sale_status)) {
						$tmp = $clsProperty->getByCond("`property_type`='_SALE_STATUS' AND `slug`='".$clsISO->replaceSpace($sale_status)."'", $clsProperty->pkey);
						if(!empty($tmp)){
							$sale_status_id = $tmp[$clsProperty->pkey];
						} else {
							$sale_status_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $sale_status_id,
								'property_type' => '_SALE_STATUS',
								'title' => $sale_status,
								'slug' => $clsISO->replaceSpace($sale_status),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["sale_status_id"] = $sale_status_id;
					}
					if(!empty($agent_lock)) {
						$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$clsISO->replaceSpace($agent_lock)."' or `slug`='".$clsISO->replaceSpace($agent_lock)."')", $clsProperty->pkey);
						if(!empty($tmp)){
							$agent_lock_id = $tmp[$clsProperty->pkey];
						} else {
							$agent_lock_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $agent_lock_id,
								'property_type' => '_AGENCY',
								'title' => $agent_lock,
								'slug' => $clsISO->replaceSpace($agent_lock),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["agent_lock_id"] = $agent_lock_id;
					}
					if(!empty($deposit_agent)) {
						$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$clsISO->replaceSpace($deposit_agent)."' or `slug`='".$clsISO->replaceSpace($deposit_agent)."')", $clsProperty->pkey);
						if(!empty($tmp)){
							$deposit_agent_id = $tmp[$clsProperty->pkey];
						} else {
							$deposit_agent_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $deposit_agent_id,
								'property_type' => '_AGENCY',
								'title' => $deposit_agent,
								'slug' => $clsISO->replaceSpace($deposit_agent),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["deposit_agent_id"] = $deposit_agent_id;
					}
					if(!empty($bank_second)) {
						$tmp = $clsProperty->getByCond("`property_type`='_BANK' and slug='".$clsISO->replaceSpace($bank_second)."'", $clsProperty->pkey);
						if(!empty($tmp)){
							$bank_second_id = $tmp[$clsProperty->pkey];
						} else {
							$bank_second_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $bank_second_id,
								'property_type' => '_BANK',
								'title' => $bank_second,
								'slug' => $clsISO->replaceSpace($bank_second),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["bank_second_id"] = $bank_second_id;
					}
					if(!empty($bank)) {
						$tmp = $clsProperty->getByCond("`property_type`='_BANK' and slug='".$clsISO->replaceSpace($bank)."'", $clsProperty->pkey);
						if(!empty($tmp)){
							$bank_id = $tmp[$clsProperty->pkey];
						} else {
							$bank_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $bank_id,
								'property_type' => '_BANK',
								'title' => $bank,
								'slug' => $clsISO->replaceSpace($bank),
								'order_no' => $clsProperty->getMaxorderNo(),
								'user_id' => 0,
								'user_id_update' => 0,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
						$more_information_stock["bank_id"] = $bank_id;
					}
					if(!empty($home_direction)) {
						$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' and slug='".$clsISO->replaceSpace($home_direction)."'", $clsProperty->pkey);
						if(!empty($tmp)) {
							$home_direction_id = $tmp[$clsProperty->pkey] ;
							$upd_field["home_direction_id"] = $home_direction_id;
							$more_information_stock["home_direction_id"] = $home_direction_id;
						}
					}
					if(!empty($TCBG)) {
						$more_information_stock["TCBG"] = $TCBG;
					}
					if(!empty($notes)) {
						$more_information_stock["notes"] = $notes;
					}
					if(!empty($csbh)) {
						$more_information_stock["csbh"] = $csbh;
					}
					if(!empty($DT_TT)) {
						$more_information_stock["DT_TT"] = $DT_TT;
					}
					$price_temporary_ns = !empty($price_temporary_ns) ? $price_temporary_ns : $link_smartchip;
					$more_information_stock["price_temporary_ns"] = $price_temporary_ns;
					if(!empty($total_price_vat)){
						$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($total_price_vat,$min,$max);
					} else {
						if(!empty($total_price_early)){
							$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($total_price_early,$min,$max);
						}	
					}
					$check_log = 0;
					foreach ($v_stock as $p_field => $val) {
						if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
							,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
							,'total_price_bank_12','total_price_bank_18'))){
							$price_tmp = !empty($val) ? $clsStock->getPriceOriginV2($val,$min,$max) : 0;								
							if((!isset($more_information_stock[$p_field]) || (!empty($more_information_stock[$p_field]) 
								&& $more_information_stock[$p_field] != $price_tmp))){
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(),
									'user_id' => 0,
									'from_value' => $more_information_stock[$p_field],
									'to_value' => $price_tmp,
									'field' => $p_field,							
									'from' => '_front',
								);
								$check_log = 1;
							}
							$more_information_stock[$p_field] = $price_tmp;
						}
					}	
					//khử log
					$result = [];
					$built_keys = [];
//					$clsISO->print_pre($logs);die;
					foreach ($logs as $key => $item) {
						$unique_key = $item['field'] . '|' . ($item['from_value'] ?? '') . '|' . ($item['to_value'] ?? '');
						if (!isset($built_keys[$unique_key])) {
							$result[$key] = $item; 
							$built_keys[$unique_key] = true;
						}
					}
					$logs = $result;
					
					$upd_field['upd_date'] = time();
					$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
					if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
						$total_updated += 1;
						$arr_upd[] = $ms_code;
						if(!empty($oneStockMeta) && !empty($logs) && !empty($check_log)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
						$list_stock_id[$target_id][] = $oneStock[$clsStock->pkey];
					} else{
						$arr_not_upd[] = $ms_code;
					}
				}
			}
		} else {
			$field = "{$clsStock->pkey},`status_id`,`more_information`";
			$g_cond = "`stock_type`='".$stock_type."' AND `agency_id`='{$agency_id}' 
			AND `project_id`='{$target_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
			if($target_id == 3) {
				$g_cond.= " AND `block_id`<>'10933'";
			}
			$list_stocks = $clsStock->getAll($g_cond, $field);
			if(!empty($list_stocks)){
				$total_updated = 0;
				foreach($list_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information['user_id_update_sold'] = 0;
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$m_field = "{$clsStockMeta->pkey},logs";
					$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $val[$clsStock->pkey],
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					}
					$logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(), 
						'user_id' => 0,
						'from_id' => $val['status_id'],
						'to_id' => _STOCK_STATUS_SOLD_ID,
						'field' => 'status_id',								
						'from' => '_front',
					);
					if($clsStock->updateOne($val[$clsStock->pkey], array(
						'ms_date' => time(),
						'upd_date' => time(),
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))) {
						++$total_stock_sold;
						$arr_upd[] = $ms_code;
						if(!empty($oneStockMeta) && !empty($logs)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					}
				}
				unset($tmp);
			}
		}
		//log c?p nh?t
		$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
		$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;		
		#log new
		$arr_data_upd = array(
			"total_stock_sold"	=>	$total_stock_sold,
			"total_stock_new"	=>	$total_stock_new,
			"total_stock"	=>	$total_updated,
			"stock_not_upd"	=>	$arr_not_upd,
			"data_log"	=>	$arr_data,
			"title_log"	=>	'Tổng quỹ: '.$total_updated.', Căn bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
			"type"	=>	1,	//0:tá»•ng há»£p,1:drive,2:hÃ¬nh áº£nh,3:copy,
			"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
		);
		$clsLogCrawl->log($agency_id,$target_id, $arr_data_upd, $stock_type);
		// Start Logs 
		$logs_field = "{$clsStockLog->pkey},`more_information`";
		foreach ($list_stock_id as $project_id => $stock_ids) {	
			$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `project_id`='{$project_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
			if(!empty($tmp)){
				$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
					'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
				));
			} else {
				$clsStockLog->insert(array(
					'stock_type' => $stock_type,
					'agency_id' => $agency_id,
					'project_id' => $project_id,
					'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
					'reg_date' => time(),
					'user_id' => 0
				));
			}
		}
		/** End */
		$result = array(
			'spreadsheetId_crawl'	=>	$spreadsheetId_crawl,
			'msg' => 'Tổng quỹ: '.$total_updated.', Căn bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
			'total_updated' => $total_updated,
			'total_stock_sold' => $total_stock_sold,
			'total_stock_new' => $total_stock_new,
			'stock_not_upd' => $arr_not_upd,
			'arr_upd' => $arr_upd,
			'agency_id' => $agency_id,
			'stock_type' => $stock_type,
			'target_id' => $target_id,
		);
		$status_code = 200;
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'message' => $result
		);
	}else{
		$error  =$helper->getFieldValue("error", $inputs);
		$clsLogCrawl->log($agency_id,$block_id, $error, $stock_type);
		
		$result = array(
			'spreadsheetId_crawl'	=>	$spreadsheetId_crawl,
			'arr_upd' => $arr_upd,
		);
		
		
		$status_code = 410;
		$apiresults = array(
			'error' => 1, 
			'result' => 'success', 
			'message' => $result
		);
	}
	// Return
	echo echoResponse($status_code, $apiresults);
});
$app->get('/get_spreadsheet_agency', function ($request, $response) use ($app) {
	global $dbconn, $clsISO,$profile_id,$core;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsProperty = new Property();
	$clsISO = new ISO();
	$clsConfiguration = new Configuration();
	$clsZalo = new Zalo();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_agency_cronjob_stock.json';
	###
	$decoder = new Webmozart\Json\JsonDecoder();		
	$encoder = new Webmozart\Json\JsonEncoder();
	$dataCached = [];
	if(file_exists($cachedFile)){		
		$dataCached = $decoder->decodeFile($cachedFile);
	} 
	
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$cond = "`is_trash`=0 AND `property_type`='_AGENCY' AND ((JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL) || (JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL))";
//	$cond .= " AND `property_id`='10886'";
	if(!empty($dataCached)) {
		$cond .= " AND `{$clsProperty->pkey}` NOT IN (".implode(',',$dataCached).")";
	}
	$oneAgency = $clsProperty->getByCond($cond,$clsProperty->pkey.",`title`,`more_information`");
	if(empty($oneAgency)) {
		$oneAgency = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_AGENCY' AND ((JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL) || (JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL))",$clsProperty->pkey.",`title`,`more_information`");
		$dataCached = array();
	}
//	$clsISO->print_pre($oneAgency);die;
//	===========================
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == $stock_type) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	#cao tang
	$cachedFileConfigHighFloor = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
	$arr_column_data = array();
	if(file_exists($cachedFileConfigHighFloor)){
		$arr_column_data = $decoder->decodeFile($cachedFileConfigHighFloor);			
	}
	$config_column_data_highfloor = json_encode($arr_column_data,JSON_UNESCAPED_UNICODE);
	
	#thap tang
	$cachedFileConfigLowFloor = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
	$arr_column_data = array();
	if(file_exists($cachedFileConfigLowFloor)){
		$arr_column_data = $decoder->decodeFile($cachedFileConfigLowFloor);			
	}
	$config_column_data_lowFloor = json_encode($arr_column_data,JSON_UNESCAPED_UNICODE);
	
	$crawl = [];
	if(!empty($oneAgency)) {
		$agency_id = $oneAgency[$clsProperty->pkey];
		$dataCached[] = $agency_id;
		$arr_crawl = $crawl_tmp = [];
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_crawl = $core->get_field($more_information, "block_crawl", []);
		$crawl_lowfloor = $core->get_field($more_information, "crawl_lowfloor", []);
		if(!empty($block_crawl)) {
			$arr_cache_building = [];
			foreach($block_crawl as $block_id => $v_cr) {
				if(!isset($arr_cache_building[$block_id])) {
					$arr_cache_building[$block_id] = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$block_id}'",$clsProperty->pkey.",property_code,more_information");
				}
				$lstBuilding = $arr_cache_building[$block_id];
				$ranges = explode("|",$v_cr["sheet_name"]);
				
				$is_crawl = (int) $core->get_field($v_cr, "is_crawl", 0);
				$sheetID = $core->get_field($v_cr, "sheetID", null);
				$sheet_name = $core->get_field($v_cr, "sheet_name", null);
				if($is_crawl && $sheetID && $sheet_name) {
					$ranges = explode("|", $sheet_name);
					$arr_crawl[] = [
						"agency_id"	=>	$agency_id,
						"target_id"	=>	$block_id,
						"ranges" =>	$ranges,
						"spreadsheetId"	=>	$sheetID,
						"stock_type" =>	_BLOCK_TYPE_HIGHLEVEL_SALE,
						"oneAgency" =>	$oneAgency,
						"config_column_data"	=>	$config_column_data_highfloor,
						"lstBuilding"	=>	json_encode($lstBuilding,JSON_UNESCAPED_UNICODE),
					];					
				}
			}
		}
		if(!empty($crawl_lowfloor)) {
			foreach ($crawl_lowfloor as $target_id => $v_cr) {
				$is_crawl = (int) $core->get_field($v_cr, "is_crawl", 0);
				$sheetID = $core->get_field($v_cr, "sheetID", null);
				$sheet_name = $core->get_field($v_cr, "sheet_name", null);
				if($is_crawl && $sheetID && $sheet_name) {
					$ranges = explode("|", $sheet_name);
					foreach ($ranges as $k => $range) {
						$ranges[$k] = $range."!A1:AZ500";
					}
					$arr_crawl[] = [
						"agency_id"	=> $agency_id,
						"target_id"	=> $target_id,
						"ranges" => $ranges,
						"spreadsheetId"	=>	$sheetID,
						"stock_type" =>	_BLOCK_TYPE_LOWFLOOR_SALE,
						"crawl_lowfloor" =>	$v_cr,
						"oneAgency" =>	$oneAgency,
						"config_column_data"	=>	$config_column_data_lowFloor,
					];
				}
			}
		}
		$encoder->encodeFile($dataCached, $cachedFile);
	}
//	===========================
	$apiresults = array(
		'error' => 0,
		'result' => 'success',
		'arr_crawl'	=>	$arr_crawl,
	);
	$apiresults = array(
		'error' => 0,
		'result' => 'success',
		'arr_crawl'	=>	$arr_crawl,
		'arr_price_min_max'	=>	$arr_price_min_max,
	);
	// Return
	echo echoResponse('200',$apiresults);
});
$app->post('/crawl_stock', function ($request, $response) use ($app){
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsLogCrawl = new LogCrawl();
	$clsStockMeta = new StockMeta();
	$clsCrawl = new Crawl();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockAgent = new StockAgent();
	$clsStockLog = new StockLog();
	$clsProject = new Project();
	$clsZalo = new Zalo();
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$inputs = $request->getParsedBody();
	$agency_id  =$helper->getFieldValue("agency_id", $inputs);
	$target_id  =$helper->getFieldValue("target_id", $inputs);
	$stock_type  =$helper->getFieldValue("stock_type", $inputs);
	$tblData  = $helper->getFieldValue("tblData", $inputs);
	###
	$dataCached = array();
	$cachedFile = DIR_CACHE_JSON.'/crawl/stock/cache_sold_stock.json';
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();		
		$dataCached = $decoder->decodeFile($cachedFile);
	}
	#
	$cachedFileSold = DIR_CACHE_JSON.'/crawl/stock/stock_sold_admin.json';
	if(file_exists($cachedFileSold)){
		$arrCacheSold = $decoder->decodeFile($cachedFile);
	}
	$lstStock_sold = !empty($arrCacheSold[$stock_type][$agency_id][$target_id]) ? $arrCacheSold[$stock_type][$agency_id][$target_id] : array();
//	=======================
	if(!empty($inputs["result"])) {
		$total_stock_sold = $total_stock_new = $total_updated = 0;		
		if(!empty($tblData)) {
			$arr_cdt_not_update = [];
			if($agency_id != 10954 && $target_id == 10684){
				$lstMs_code = $clsStockAgent->getAll("`agency_id`='10954' AND `target_id`='$target_id' AND `stock_type`='{$stock_type}'");
				if(!empty($lstMs_code)) {
					foreach ($lstMs_code as $key => $val){
						$arr_cdt_not_update[] = $clsCrawl->getCodeNotTemplate($val["ms_code"]);
					}
				}
			}
			$arr_data_code = array();
			$total_record = @count($tblData);
			$arr_stock_code_not_in = $arr_stock_id_not_in = array();
			foreach($tblData as $key => $val) {
				$ms_code = trim($val['ms_code']);
				if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
					$val['ms_code'] = $ms_code;
					$ms_code = $clsCrawl->getCodeNotTemplate($val["ms_code"]);	
					if(!$clsISO->checkItemInArray($ms_code,$arr_cdt_not_update)) {
						$arr_data_code[] = $ms_code;
					}					
				}
				unset($ms_code);
			}
			#log
			$dataCached[$agency_id][$target_id]["data_crawl"] = $arr_data_code;
			if(!empty($arr_data_code)){
				$str_code_in = implode("','",$arr_data_code);
				$sql_string = "";
				if(!empty($lstStock_sold)) {
					$sql_string = " AND `ms_code` NOT IN ('".implode("','",$lstStock_sold)."')";
				}
				if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
					$list_agent_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$target_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND REPLACE(REPLACE(REPLACE(`ms_code`,'_',''),'-',''),'.','') IN ('".$str_code_in."')".$sql_string,$clsStock->pkey.',ms_code');
				}elseif($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
					$list_agent_stocks = $clsStock->getAll("`project_id`='{$target_id}' AND `stock_type`='".$stock_type."' AND (`agency_id` <> '"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `block_id`<>'"._BLOCK_FULLTON."' AND `is_trash`=0 AND  AND REPLACE(REPLACE(REPLACE(`ms_code`,'_',''),'-',''),'.','') IN ('".$str_code_in."')".$sql_string,$clsStock->pkey.',ms_code');
				}
				if(!empty($list_agent_stocks)) {
					foreach ($list_agent_stocks as $oneStock) {
						$list_stocks[] = $oneStock["ms_code"];
						$arr_stock_crawls[$target_id][] = $oneStock["ms_code"];
					}
					unset($list_agent_stocks);
				}
			}
		}
		#log
		$dataCached[$agency_id][$target_id]["data_upd"] = $list_stocks;
		#luu bang tam
		$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$target_id,$list_stocks);	
		$arr_cached_stocks = array();
		$list_cached_stocks = $clsStockAgent->getAll("`agency_id`='{$agency_id}' AND `stock_type`='{$stock_type}' AND `target_id`='{$target_id}'");
		$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);
		$arr_sold_stocks = [];
		if(!empty($list_cached_stocks) && !empty($arr_stock_crawls)){
			foreach($list_cached_stocks as $key => $val){
				$ms_code = $val['ms_code'];
				$arr_cached_stocks[$val["target_id"]][] = $ms_code;
			}
			
			foreach ($arr_stock_crawls as $target_id => $ms_codes) {
				$cached_stock = !empty($arr_cached_stocks[$target_id]) ? $arr_cached_stocks[$target_id] : array();
				$arr_sold = @array_diff($cached_stock,$ms_codes);
				$arr_sold_stocks = array_merge($arr_sold_stocks,$arr_sold);
				unset($arr_sold);
			}
			$arr_sold_stocks = array_unique($arr_sold_stocks);
			if(!empty($arr_sold_stocks)){	
				$list_stocks_not_sold = $clsStockAgent->getAll("`agency_id`<>'{$agency_id}' AND `stock_type`='{$stock_type}' AND `target_id`='{$target_id}' AND `ms_code` IN (".implode(',',$arr_sold_stocks).")","ms_code");
				if(!empty($list_stocks_not_sold)) {
					foreach ($list_stocks_not_sold as $key => $val) {
						$arr_sold_stocks = array_diff($arr_sold_stocks,[$val["ms_code"]]);
					}
				}
				$arr_sold_stocks = array_values($arr_sold_stocks);
				#
				if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) { 
					$oneBlock = $clsProperty->getOne($target_id,"title,for_id,more_information");
					$more_block = $clsISO->to_array_json($oneBlock["more_information"]);
					$oneProject = $clsProject->getOne($oneBlock["for_id"],"title,more_information");
					$more_project = $clsISO->to_array_json($oneProject["more_information"]);
					$txt_project_area = !empty($more_project["project_area"]) ? "({red}".$more_project["project_area"]."{/red})" : "";
					if(!empty($more_block["is_project"])) {
						$txt_project = "🏠 Dự án: **{green}".$oneBlock["title"]."{/green}**".$txt_project_area."\n";
					}else{
						$txt_project = "🏠 Dự án: KĐT **{green}".$oneProject["title"]."{/green}**".$txt_project_area."\n";
						$txt_project .= "🏰 Phân khu **".$oneBlock["title"]."** cao tầng\n";
					}		
					$cnd = " `block_id`='{$target_id}'";
				}else{
					$oneProject = $clsProject->getOne($target_id,"title,more_information");
					$more_project = $clsISO->to_array_json($oneProject["more_information"]);
					$txt_project_area = !empty($more_project["project_area"]) ? "({red}".$more_project["project_area"]."{/red})" : "";
					$txt_project = "🏠 Dự án: KĐT **{green}".$oneProject["title"]."{/green}**".$txt_project_area."\n";
					$cnd = " `project_id`='{$target_id}'";
				}
				#log
				$dataCached[$agency_id][$target_id]["data_sold"] = $arr_sold_stocks;
				if(!empty($arr_sold_stocks)) {
					$arr_sold = array_map(function($v){
						return preg_replace('/[^a-zA-Z0-9]/', '', $v);
					}, $arr_sold_stocks);
					$list_sold_stocks = $clsStock->getAll($cnd." AND `stock_type`='{$stock_type}' AND REGEXP_REPLACE(ms_code, '[^a-zA-Z0-9]', '') IN ('".implode("','",$arr_sold)."')",$clsStock->pkey.',ms_code,bedroom_id,total_price_vat');
					$message = "";
					if(!empty($list_sold_stocks)) {						
						$message .= $txt_project;
						$arr_cache_bedroom = [];
						foreach ($list_sold_stocks as $key => $val) {
							if(!isset($arr_cache_bedroom[$val["bedroom_id"]])) {
								$arr_cache_bedroom[$val["bedroom_id"]] = $clsProperty->getTitle($val["bedroom_id"]);
							}
							$message.= "💔 Đã bán **{red}".$val["ms_code"]."{/red}** (".$arr_cache_bedroom[$val["bedroom_id"]].((!empty($val['total_price_vat'])) ? ("-".$clsISO->shortNumber($val['total_price_vat'])) : "").")\n";
						}
					}					
					/*$message = "";
					$message .= $txt_project;
					foreach($arr_sold_stocks as $stock_code){
						$message.= "💔 Đã bán **{red}".$stock_code."{/red}**\n";
					}*/
					if($message != "") {
						$body = $clsZalo->createZaloPayloadFromMarkedMessage($message);
//						$arr_group_id = _ZALO_GROUP_SOLD_NOTIFY;
						$arr_group_id = ["8388131316320784986"];
						foreach($arr_group_id as $id_group){
							$body["group_id"] = $id_group;
							$curl = new \Curl\Curl();
							$curl->setHeaders(array(
								'Content-Type' => 'application/json',
								'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
							));
							$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', $body);
						}
						
					}					
					$encoder = new Webmozart\Json\JsonEncoder();
					$encoder->encodeFile($dataCached, $cachedFile);
				}				
			}
		}
		$status_code = 200;
		$result = array(
			'agency_id' => $agency_id,
			'agency_name' => $oneAgency['title'],
			'ms_codes' => $resStockCrawl["ms_codes"],
			'total' => $resStockCrawl["total"],
			'stock_sold' => $arr_sold_stocks,
			'date' => date("d/m/Y H:i"),
		);
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'message' => $result
		);
		
	}else{
		$result = array(
			"title_log"	=>	'File Gooogle Sheet không thể đọc',
			"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
			"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
		);
		$clsLogCrawl->log($agency_id,$target_id, $result, $stock_type);		
		
		$status_code = 410;
		$apiresults = array(
			'error' => 1, 
			'result' => 'success', 
			'message' => $result
		);
	}
	// Return
	echo echoResponse($status_code, $apiresults);
});
$app->get('/send-zalo', function ($request, $response) use ($app) {
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsLogCrawl = new LogCrawl();
	$clsStockMeta = new StockMeta();
	$clsCrawl = new Crawl();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockAgent = new StockAgent();
	$clsStockLog = new StockLog();
	$clsProject = new Project();
	$clsZalo = new Zalo();
	$status_code = 400;
	
	$clsCourse = new Course();
	$message= "**Bạn có** {green}__1 lời mời __{/green} **tham gia sự kiện**";
	$message.= "\n";
	$message.= "**Tiêu đề:** {red}**📌 THÔNG BÁO ĐÀO TẠO DỰ ÁN The Vision – Tăng ga về đích!**{/red}";
	$message.= "\n";
	$message.= "**Nội dung:** {green}__<p>⏰ Thời gian: 10h00 hôm nay 24/12/2025. 📍 Địa điểm: KĐ1-02, Vinhomes Ocean Park 2 👤 Diễn giả: Mr. Đào Duy Kiên – GĐ dự án cao tầng OCP2 👉 Yêu cầu ace tham gia đầy đủ, đúng giờ, chuẩn bị tinh thần bứt tốc giai đoạn cuối 🚀</p>__{/green} ";
	$message.= "\n";
	if(!empty($link)) {		
		$message.= "**Link chia sẻ:** {blue}".$link."{/blue}";
		$message.= "\n";	
	}
	$message.= "**Nhấn link sự kiện để tham gia ngay:** {blue}".PCMS_URL.$clsCourse->getLink(232)."{/blue}";
	$message.= "\n";
	#
	$target_id = 11175;
	$stock_type = 178;
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) { 
		$oneBlock = $clsProperty->getOne($target_id,"title,for_id,more_information");
		$more_block = $clsISO->to_array_json($oneBlock["more_information"]);
		$oneProject = $clsProject->getOne($oneBlock["for_id"],"title,more_information");
		$more_project = $clsISO->to_array_json($oneProject["more_information"]);
		$txt_project_area = !empty($more_project["project_area"]) ? "({red}".$more_project["project_area"]."{/red})" : "";
		if(!empty($more_block["is_project"])) {
			$txt_project = "🏠 Dự án: **{green}".$oneBlock["title"]."{/green}**".$txt_project_area."\n";
		}else{
			$txt_project = "🏠 Dự án: KĐT **{green}".$oneProject["title"]."{/green}**".$txt_project_area."\n";
			$txt_project .= "🏰 Phân khu **".$oneBlock["title"]."** cao tầng\n";
		}		
		$cnd = " `block_id`='{$target_id}'";
	}else{
		$oneProject = $clsProject->getOne($target_id,"title,more_information");
		$more_project = $clsISO->to_array_json($oneProject["more_information"]);
		$txt_project_area = !empty($more_project["project_area"]) ? "({red}".$more_project["project_area"]."{/red})" : "";
		$txt_project = "🏠 Dự án: **{green}".$oneProject["title"]."{/green}**".$txt_project_area."\n";
		$cnd = " `project_id`='{$target_id}'";
	}
	$message = $txt_project;
	
	$message .= "💔 Đã bán **{red}A2-12.19{/red}** (2N2VS Góc-5,7 tỷ)";
//	echo $message;die;
	
//	$message = '
//🏠 <b><span style="color:#00aa00">Dự án: Masteri Era Landmark</span></b><br>
//💔 <span style="color:#ff0000"><b>Đã bán</b></span> C2Z2-11-10<br>
//🎉 <span style="color:#ff8800"><b>Đã bán</b></span> C2Z2-23-11
//';	
//	$body = $clsZalo->parseHtmlToZaloMessage($message);
//	$clsISO->print_pre($message);die;
//	$message = $clsZalo->htmlToZaloMarkup($message);
//	$clsISO->print_pre($message);die;
	$body = $clsZalo->createZaloPayloadFromMarkedMessage($message);
//	$body = $clsZalo->parseZaloMessage($message);
//	$clsISO->print_pre($body);die;
	$dbconn->debug=true;
	$clsZalo->sendZaloUser2(289,$body);die;
	
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$message = "{red}**SOS cả nhà ơi!**{/red}
	Thủy đang cần gấp **{orange}1 HÀNH CHÍNH NHÂN SỰ TỔNG HỢP{/orange}** cho văn phòng chi nhánh mới toanh vừa setup xong ở gần {green}Vin Hạ Long Xanh (gần ngã ba Hùng Thắng), Hạ Long, Quảng Ninh{/green}.
	Văn phòng tổng ở Ocean Park Hà Nội.
	{red}**Lương: 9 - 15 triệu**{/red} + Thử việc 02 tháng, sau 2 tháng đóng BHXH đầy đủ + Teambuilding, nghỉ mát + Thưởng lễ tết.
	Inbox zalo {red}**0877769963**{/red} nhận JD.";
	$message = "PTG căn {red}**C2Z2-08-19**{/red}\r
Phân khu: {green}**Masteri Era Landmark**{/green} | Tòa: {green}**Vision 2**{/green}\r
Loại: **1PN+1** | Thông thủy: **48,1 m²** | Hướng: **TN**\r
PTG -> https://myoceancity.vn/p3/C2Z2-08-19/PTG.html";
	$body = $clsZalo->parseZaloMessage($message);
	$clsISO->print_pre($body);die;
	$body["group_id"] = "8388131316320784986";
	$curl = new \Curl\Curl();
	$curl->setHeaders(array(
		'Content-Type' => 'application/json',
		'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
	));
	$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', $body);
	$clsISO->print_pre($curl);die;
	// Return
	echo echoResponse($status_code, $apiresults);
});
?>