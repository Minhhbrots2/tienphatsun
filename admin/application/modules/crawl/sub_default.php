<?php 
function default_crawl(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' 
	AND `property_type`='_AGENCY' order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	foreach ($list_agency as $key => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
		$list_agency[$key]['more_information'] = $more_information;
		$list_agency[$key]['block_crawl'] = $block_crawl;
	}
	$cond = "`parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `property_type`='_BLOCK' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL";
	$lst_block = [];
	$listBlocks = $clsProperty->getAll($cond . " ORDER BY `for_id` ASC","`{$clsProperty->pkey}`,`title`,JSON_EXTRACT(`more_information`,'$.on_sale') AS `on_sale`");
	foreach ($listBlocks as $key => $val) {
		if($val["on_sale"] == 1) {
			$list_blocks[$val[$clsProperty->pkey]] = $val['title'];	
		}		
	}
	$assign_list["list_blocks"] = $list_blocks;
	$assign_list["list_agency"] = $list_agency;
}
function default_open_agency(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$clsProject = new Project();
	$smarty->assign('clsProperty',$clsProperty);
	$smarty->assign('clsProject',$clsProject);
	###
	$uid = $clsISO->getUniqid();
	$agency_id = Input::post('agency_id', 0);
	$stock_type = Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$oneItem = $clsProperty->getOne($agency_id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	
	$arr_cache_project = [];
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {		
		$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
		$cond = "`parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `property_type`='_BLOCK'";	
		$listBlocks = $clsProperty->getAll($cond . " ORDER BY `for_id` ASC","`{$clsProperty->pkey}`,`for_id`,`title`,JSON_EXTRACT(`more_information`,'$.on_sale') AS `on_sale`");
//		$clsISO->print_pre($listBlocks);die;
		$lst_block = [];		
		foreach ($listBlocks as $key => $val) {
			if($val["on_sale"] == 1) {
				if(!isset($arr_cache_project[$val['for_id']])) {
					$arr_cache_project[$val['for_id']] = $clsProject->getCode($val["for_id"]);
				}
				$lst_block[$val[$clsProperty->pkey]] = $val['title']." (".$arr_cache_project[$val['for_id']].")";
			}
			
		}
		foreach ($block_crawl as $key => $val) {
			$block_crawl[$key]["arr_sheet_name"] = $clsISO->getArrayByTextSlash($val['sheet_name'],"|");
			$block_crawl[$key]["arr_sheet_id"] = $clsISO->getArrayByTextSlash($val['sheet_id'],"|");
			$block_crawl[$key]["arr_stock_point"] = $clsISO->getArrayByTextSlash($val['is_stock_point'],"|");
			$block_crawl[$key]["lstBuilding"] = $clsProperty->getAll("`for_id`='".$key."'",$clsProperty->pkey.',title');
		}
		$smarty->assign('lst_block',$lst_block);
		$smarty->assign('block_crawl',$block_crawl);
	}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {		
		$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();
		foreach ($crawl_lowfloor as $key => $val) {
			$crawl_lowfloor[$key]["arr_sheet_name"] = $clsISO->getArrayByTextSlash($val['sheet_name'],"|");
			$crawl_lowfloor[$key]["arr_sheet_id"] = $clsISO->getArrayByTextSlash($val['sheet_id'],"|");
		}
//		var_dump($crawl_lowfloor);die;
		$lst_project = [];
		$field = "{$clsProject->pkey},`code`,`title`";
		$list_projects = $clsProject->getAll("`is_menu`='1' AND `list_block_type` LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%' order by `reg_date` ASC", $field);
		foreach ($list_projects as $key => $val) {
			$lst_project[$val[$clsProject->pkey]] = $val['code'];
		}
		$smarty->assign('lst_project',$lst_project);
		$smarty->assign('crawl_lowfloor',$crawl_lowfloor);
	}
	
	$smarty->assign('uid',$uid);
	$smarty->assign('agency_id',$agency_id);
	$smarty->assign('oneItem',$oneItem);
	$smarty->assign('stock_type',$stock_type);
	// Return
	$html = $core->build('_ajax.open_agency.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_save_agency(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	###
	
	$agency_id = (int)Input::post("agency_id",0);
	$stock_type = (int)Input::post("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
	$block_crawl = Input::post("block_crawl",array());
	$crawl_lowfloor = Input::post("crawl_lowfloor",array());
	$res = ["result"	=>	false,"msg"	=>	"error"];
	if(!empty($agency_id)) {
		$oneItem = $clsProperty->getOne($agency_id);
		$more_information = $clsISO->to_array_json($oneItem['more_information']);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$more_information['block_crawl'] = $block_crawl;
		}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			foreach ($crawl_lowfloor as $key => $val) {
				$arr_sheet_name = $clsISO->getArrayByTextSlash($val['sheet_name'],"|");
				foreach ($arr_sheet_name as $k_sheet_name => $sheet_name) {
					$config = $val[$sheet_name];
					foreach ($crawl_lowfloor[$key][$sheet_name]['color_sold'] as $k_sold => $color_sold) {
						$crawl_lowfloor[$key][$sheet_name]['color_sold'][$k_sold] = strtoupper($color_sold);
					}
					if(empty($config['is_color_dq'])) {
						unset($crawl_lowfloor[$key][$sheet_name]['color_dq']);
					}else{
						foreach ($crawl_lowfloor[$key][$sheet_name]['color_dq'] as $k_dq => $color_dq) {
							$crawl_lowfloor[$key][$sheet_name]['color_dq'][$k_dq] = strtoupper($color_dq);
						}
					}
					if(empty($config['is_color_break'])) {
						unset($crawl_lowfloor[$key][$sheet_name]['color_break']);
					}else{
						foreach ($crawl_lowfloor[$key][$sheet_name]['color_break'] as $k_br => $color_break) {
							$crawl_lowfloor[$key][$sheet_name]['color_break'][$k_br] = strtoupper($color_break);
						}
					}
				}	
			} 
			$more_information['crawl_lowfloor'] = $crawl_lowfloor;
		}
		if($clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result"	=>	true,"msg"	=>	"_success"];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_open_sheet(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	$clsTemporary = new Temporary();
	$clsCrawl = new Crawl();
	##
	$uid = Input::post('uid');
	$gId = Input::post('gId');
	$stock_type = (int)Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$spreadsheetId = Input::post('spreadsheetId');
	$arr_worksheets = $list_worksheets = array();
	if(!empty($spreadsheetId)){
		if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			// $clsISO->print_pre($matches); die();
			$spreadsheetId = $matches[0];
		}
		$clsTemporary->deleteByCond("`reg_date`<='".strtotime('-1 day')."'");
		$tmp = $clsTemporary->getByCond("`sid`='{$uid}' and `resource_id`='{$spreadsheetId}'");
		if(!empty($tmp)){
			$content = $tmp['content'];
			$arr_worksheets = $clsISO->to_array_json($content);
		} else {
//			/** Required Lib */
//			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
//			/** Init Client */
//			$client = new Google_Client();
//			$client->setClientId(GOOGLE_CLIENT_ID);
//			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
//			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
//			$client->setScopes([Google_Service_Drive::DRIVE]);
//			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
//			$service = new Google_Service_Sheets($client);
//			try {
//				$resource_id = $spreadsheetId;
//				$spreadsheet = $service->spreadsheets->get($spreadsheetId);
//				$list_worksheets = $spreadsheet->sheets;
//			} catch(Exception $ex){
//				$msg_error = $ex->getMessage();
//				if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
//					/*$drive = new Google_Service_Drive($client);
//					// Đọc file Excel từ Google Drive
//					$response = $drive->files->get($spreadsheetId, array(
//						'supportsAllDrives' => 'true'
//					));
//					// Chuyển đổi file Excel thành Google Sheets
//					$fileMetadata = new \Google_Service_Drive_DriveFile(array(
//						'name' => sprintf('%s', date('d-m-y h:i:s'))
//					));
//					$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
//					$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
//					$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
//						'supportsAllDrives' => 'true'
//					));
//					$resource_id = $spreadsheetId;
//					$spreadsheetId = $convertedFile->getId();
//					$spreadsheet = $service->spreadsheets->get($spreadsheetId);
//					$list_worksheets = $spreadsheet->sheets;*/
//					$spreadsheetIdCopy = $clsCrawl->copySpreadsheet($spreadsheetId, [],0, 0,0);
//					$resource_id = $spreadsheetIdCopy;
//					$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
//					$list_worksheets = $spreadsheet->sheets;
//				}				
//			}
			$list_worksheets = $clsCrawl->getSheet($spreadsheetId);
			if(!empty($list_worksheets)){
				foreach($list_worksheets as $sheet){
//					$clsISO->print_pre($sheet->properties);die;
					$id = $sheet->properties['sheetId'];   
					$name = $sheet->getProperties()->getTitle(); 
					$arr_worksheets[$id] = (string)$name;
				}
				$clsTemporary->insert(array(
					'sid' => $uid,
					'resource_id' => $resource_id,
					'spreadsheetId' => $spreadsheetId,
					'content' => json_encode($arr_worksheets, JSON_UNESCAPED_UNICODE),
					'user_id' => $core->_USER['user_id'],
					'reg_date' => time()
				));
			}
		}
	}
	$smarty->assign('gId', $gId);
	$smarty->assign('stock_type', $stock_type);
	$smarty->assign('spreadsheetId', $spreadsheetId);
	$smarty->assign('arr_worksheets', $arr_worksheets);
	// Return
	$html = $core->build('_ajax.open_sheet.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_handle_status(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	###
	$agency_id = (int)Input::post("agency_id",0);
	$block_id = (int)Input::post("block_id",0);
	$project_id = (int)Input::post("project_id",0);
	$is_crawl = (int)Input::post("is_crawl",0);
	$block_crawl = Input::post("block_crawl", array());
	$stock_type = (int)Input::post("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
	$res = ["result" =>	false,"msg"	=>	"error"];
	if(!empty($agency_id)) {
		$more_information = $clsProperty->getOneField('more_information', $agency_id);
		$more_information = $clsISO->to_array_json($more_information);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && !empty($block_id)) {
			$block_crawl = $core->get_field($more_information, "block_crawl", []);
			$block_crawl[$block_id]["is_crawl"] = $is_crawl;
			$more_information['block_crawl'] = $block_crawl;
		}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE && !empty($project_id)) {
			$crawl_lowfloor = $core->get_field($more_information, "crawl_lowfloor", []);
			$crawl_lowfloor[$project_id]["is_crawl"] = $is_crawl;
			$more_information['crawl_lowfloor'] = $crawl_lowfloor;
		}
		
		if($clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result"	=>	true,"msg"	=>	"_success"];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_crawl_agency(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsCrawl = new Crawl();
	$clsProperty = new Property();
	$clsStock = new StockCrawl();
	$clsStockLog = new StockLog();
	$clsLogCrawl = new LogCrawl();
	#- Required Library
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	
	$agency_id = (int) Input::post("agency_id", 0);
	$stock_type = (int) Input::post("stock_type", _BLOCK_TYPE_HIGHLEVEL_SALE);
	$oneAgency = $clsProperty->getByCond("`property_type`='_AGENCY' AND `property_id`='{$agency_id}' 
	AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL");
//	$clsISO->print_pre($oneAgency); die();
	if(!empty($oneAgency)) {
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_crawl = $core->get_field($more_information, "block_crawl", []);
//		 $clsISO->print_pre($block_crawl); die();
		$stock_status_id = _STOCK_STATUS_LOCK_ID;
		if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
			$stock_status_id = $more_information['stock_status_id'];
		}
		$arr_crawl = array();
		foreach ($block_crawl as $k_block => $v_block) {
			if(!empty($v_block["is_crawl"])) {
				$block_id = $k_block;
//				if($block_id != 8636) {
//					continue;
//				}
				$spreadsheetId = $v_block['sheetID'];
				$lst_range = $v_block['sheet_name'];
				if(!empty(trim($spreadsheetId)) && !empty($lst_range)) {
					$ranges = explode("|",$lst_range);
					$arr_data = array();
					$cachedName = sprintf('%s_%s.json', $oneAgency["title"], $block_id);
					
					$cachedFile = DIR_CACHE_JSON.'/crawl/'.$cachedName;
					if(!file_exists($cachedFile)){
						$decoder = new Webmozart\Json\JsonDecoder();
						$arr_data = $decoder->decodeFile($cachedFile);
//						 @unlink($cachedFile);
					}else{
						$arr_data = $clsCrawl->getData($spreadsheetId,$ranges,$block_id,$agency_id,$stock_type); 
						if($block_id == 9220) {
//						var_dump($arr_data);die;
						}
						$encoder = new Webmozart\Json\JsonEncoder();
						$encoder->encodeFile($arr_data, $cachedFile); 
					}	
					if(!empty($arr_data)) {
						$arr_data_code = $lstStock = array();
						$total_record = @count($arr_data);
						$arr_stock_code_not_in = $arr_stock_id_not_in = array();
						foreach ($arr_data as $key => $val) {
							$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($val['ms_code'])));
							if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
								$val['ms_code'] = $ms_code;
								$arr_data_code[$ms_code] = $val;
							}
							unset($ms_code);
						}
						if(!empty($arr_data_code)) {
//							$clsStock->setDeBug(1);
							$str_code_in = implode("','",array_keys($arr_data_code));
							$listStockAgency = $clsStock->getAll("`block_id`='{$block_id}' AND `stock_type`='".$stock_type."' AND `is_trash`=0 AND `ms_code` IN ('".$str_code_in."')");
							if(!empty($listStockAgency)) {
								foreach ($listStockAgency as $k_stock => $oneStock) {
									$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
									$arr_stock_code_not_in[] = $oneStock["ms_code"];
									$arr_data_code[$oneStock["ms_code"]]["stock_id"] = $oneStock["stock_id"];
									$arr_data_code[$oneStock["ms_code"]]["oneStock"] = $oneStock;
								}
							}
						}else{
							$clsLogCrawl->insertLog('change_field', $stock_type, $block_id, $agency_id, "Thay đổi cột trong file spreadsheet", "_admin");
							continue;
						}
						$lstStock = array_values($arr_data_code);
						if($block_id == 9220) {
//							var_dump($lstStock);die;
						}
						// Cập nhật các căn thành đã bán
						$field = "{$clsStock->pkey},`ms_code`,`logs`,`status_id`,`more_information`";
						$g_cond = "`agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') 
						and `stock_type`='".$stock_type."' AND `block_id`='{$block_id}' AND `is_trash`=0";
						if(!empty($arr_stock_id_not_in)){
							$g_cond.= " and `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
						}
//						$clsStock->setDeBug(1);
						$list_sold_stocks = $clsStock->getAll($g_cond, $field);
//						var_dump($list_sold_stocks);die;
						if(!empty($list_sold_stocks)){
							foreach($list_sold_stocks as $key => $val){
								$list_logs = !empty($val['logs']) ? $clsISO->to_array_json($val['logs']) : array();
								$more_information = !empty($val['more_information']) ? $clsISO->to_array_json($val['more_information']) : array();
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								$list_logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $core->_USER['user_id'],
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id'
								);
								$clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'logs' => json_encode($list_logs, JSON_UNESCAPED_UNICODE),
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								));
//								var_dump($list_logs);die;
							}
							unset($list_sold_stocks);
						}
						// Start Logs 
						$logs_field = "{$clsStockLog->pkey},`more_information`";
						$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' 
							AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
						if(!empty($tmp)){
							$_more_information = $tmp['more_information'];
							$arr_stocks = $clsISO->to_array_json($_more_information);
							$list_stocks = $clsStock->getAll("`agency_id`='{$agency_id}' and `block_id`='{$block_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') and `stock_type`='".$stock_type."' AND `is_trash`=0", $clsStock->pkey);
							if(!empty($list_stocks)){
								foreach($list_stocks as $_oStock){
									if(!in_array($_oStock[$clsStock->pkey], $arr_stocks)){
										$arr_stocks[] = $_oStock[$clsStock->pkey];
									}
								}
								unset($list_stocks);
							}
							$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
								'more_information' => json_encode($arr_stocks, JSON_UNESCAPED_UNICODE)
							));
						} else {
							$list_stocks = $clsStock->getAll("`agency_id`='{$agency_id}' and `block_id`='{$block_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') and `stock_type`='".$stock_type."' AND `is_trash`=0", $clsStock->pkey);
							$arr_stocks = array();
							if(!empty($list_stocks)){
								foreach($list_stocks as $_oStock){
									$arr_stocks[] = $_oStock[$clsStock->pkey];
								}
								unset($list_stocks);
							}
							$clsStockLog->insert(array(
								'stock_type' => $stock_type,
								'agency_id' => $agency_id,
								'block_id' => $block_id,
								'more_information' => json_encode($arr_stocks, JSON_UNESCAPED_UNICODE),
								'reg_date' => time(),
								'user_id' => $core->_USER['user_id']
							));
						}
						/** End */
						if(!empty($lstStock)) {
							foreach ($lstStock as $k_stock => $v_stock){
								$stock_id = $v_stock['stock_id'];
								$ms_code = $v_stock["ms_code"];
								$total_price_vat = !empty($v_stock["total_price_vat"]) ? $clsISO->convertPriceShortToFull($v_stock["total_price_vat"]) : 0;
								$total_price = !empty($v_stock["total_price"]) ? $clsISO->convertPriceShortToFull($v_stock["total_price"]) : 0;
								$total_price_progress = !empty($v_stock["total_price_progress"]) ? $clsISO->convertPriceShortToFull($v_stock["total_price_progress"]) : 0;
								$total_price_early = !empty($v_stock["total_price_early"]) ? $clsISO->convertPriceShortToFull($v_stock["total_price_early"]) : 0;
								$total_price_bank = !empty($v_stock["total_price_bank"]) ? $clsISO->convertPriceShortToFull($v_stock["total_price_bank"]) : 0;
								$total_price_bank_half = !empty($v_stock["total_price_bank_half"]) ? $clsISO->convertPriceShortToFull($v_stock["total_price_bank_half"]) : 0;
								$csbh = !empty($v_stock["csbh"]) ? $v_stock["csbh"] : "";
								$price_sheet_link = !empty($v_stock["price_sheet_link"]) ? $v_stock["price_sheet_link"] : "";
								$price_sheet_title = !empty($v_stock["price_sheet_title"]) ? $v_stock["price_sheet_title"] : "PTG TẠM TÍNH";
								$DT_Tim = !empty($v_stock["DT_Tim"]) ? $clsISO->toNumber(trim($v_stock["DT_Tim"])) : "";
								$DT_TT = !empty($v_stock["DT_TT"]) ? $clsISO->toNumber(trim($v_stock["DT_TT"])) : "";
								$date_deposit_sign = !empty($v_stock["date_deposit_sign"]) ? $v_stock["date_deposit_sign"] : "";
								$oneStock = $v_stock["oneStock"];
								$more_information_stock = $clsISO->to_array_json($oneStock['more_information']);
								$logs = $clsISO->to_array_json($oneStock['logs']);
								$more_information_stock['ms_code'] = $ms_code;								
//								var_dump($total_price_vat,$oneStock["total_price_vat"]);die;										
								
								$upd_field['agency_id'] = $agency_id;
								$more_information_stock['agency_id'] = $agency_id;
								if(!empty($DT_TT)) $more_information_stock['DT_TT'] = $DT_TT;
								if(!empty($csbh)) $more_information_stock['csbh'] = $csbh;
								if(!empty($date_deposit_sign)) $more_information_stock['date_deposit_sign'] = $date_deposit_sign;
								if(!empty($DT_Tim)) {
									$more_information_stock['DT_Tim'] = $DT_Tim;
									$upd_field['DT_Tim'] = $DT_Tim;
								}
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
									
								}else if(!empty($total_price) && empty($total_price_vat)){ // có giá chưa VAT và không có giá full VAT
									$total_price_vat = $total_price * _PERCENT_PRICE_VAT; // * 1.12
									$total_price_vat = round($total_price_vat);
									$upd_field['total_price_vat'] = $total_price_vat;
									$more_information_stock["total_price_vat"] = $total_price_vat;
									if($total_price_vat != $oneStock["total_price_vat"]) {
										$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);
									}
								}else if(!empty($total_price_early) && empty($total_price_vat)){
									$upd_field['total_price_vat'] = $total_price_early;
									$more_information_stock["total_price_vat"] = $total_price_early;
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
								$price_sheets = $core->get_field($more_information_stock, 'price_sheets', []);
								if(!empty($price_sheet_title) && !empty($price_sheet_link)){
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
									$price_sheets[$price_sheet_id]['user_id'] = $core->_USER['user_id'];
									$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
								}
								$more_information_stock['price_sheets']= $price_sheets;
								
								$upd_field['upd_date'] = time();
								$upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
								$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
//								var_dump($upd_field);
								// $clsISO->print_pre($total_updated); die();
								$clsStock->setDeBug(1);
								if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
									++$total_updated;
								} 
							}
						}
						
						if($total_updated > 0) {				
							#activity log		
//							$clsActivityLog = new ActivityLog();
//							$log = $clsActivityLog->addActivityLog("Stock","update");
						}
					} else {
						$field = "{$clsStock->pkey},`status_id`,`logs`,`more_information`";
						$list_stocks = $clsStock->getAll("`is_trash`=0 and `agency_id`='{$agency_id}' 
							and `stock_type`='".$stock_type."' AND `block_id`='{$block_id}' AND `is_trash`='0'", $field);
						if(!empty($list_stocks)){
							$total_updated = 0;
							foreach($list_stocks as $key => $val){
								$logs = $val['logs'];
								$more_information = $val['more_information'];
								$logs = $clsISO->to_array_json($logs);
								$more_information = $clsISO->to_array_json($more_information);
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $core->_USER['user_id'],
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id'
								);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								if($clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'upd_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))) {
									++$total_updated;
								}
							}
							if($total_updated > 0) {				
								#activity log		
//								$clsActivityLog = new ActivityLog();
//								$log = $clsActivityLog->addActivityLog("Stock","update");
							}
							unset($tmp);
						}
					}
				}
			}
		}
	}
	echo json_encode(array(
		'result'	=>	true,
		'msg' => '_success',
		'total_updated' => $total_updated
	));	die();
	$clsISO->print_pre($oneAgency);die;
}
function default_open_config_column(){
	global $assign_list, $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $menu_current, 
	$current_page, $core, $clsModule, $clsButtonNav, $clsConfiguration, $clsISO, $dbconn;
	$clsStock = new Stock();
	$clsCrawl = new Crawl();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsTemporary = new Temporary();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	$uid = $clsISO->getUniqid();
	$user_id = $core->_USER["user_id"];
	$smarty->assign("user_id",$user_id);
	#
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$sid = Input::post('sid'); 
	$agency_id = (int) Input::post('agency_id',0);
	$target_id = (int) Input::post('target_id',0);
	$stock_type = (int) Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$spreadsheetId = Input::post('spreadsheetId',"");
	$sheet_ids = Input::post('sheet_id',"");
	$ranges = Input::post('sheet_name',"");	
	$stock_points = Input::post('is_stock_point',"");
	$building_ids = Input::post('building_ids',array());
	
	$arr_data = $column_data = $code_floor_ind = array();
	if(!empty($agency_id) && !empty($ranges) && !empty($spreadsheetId) && !empty($target_id)) {
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		
		$sheet_ids = explode("|", $sheet_ids); 
		$stock_points = explode("|",$stock_points);
		$ranges = explode("|", $ranges); 
		$arr_sheet_range = $arr_sheet_building = $arr_stock_points = [];
		foreach($ranges as $key => $range) {
			$arr_sheet_range["'".$range."'"] = $sheet_ids[$key];
			#
			if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$arr_build = [];
				foreach($building_ids[$range] as $building_id) {
					$arr_build[$building_id] = $clsProperty->getTitle($building_id);
				}
				$arr_sheet_building["'".$range."'"] = $arr_build;
				$arr_stock_points["'".$range."'"] = $stock_points[$key];
				
				#
				$arr_code_floor = $core->get_field($more_information, "arr_code_floor", []);
				$code_floor_index = $core->get_field($arr_code_floor, "code_floor_index", []);
				$code_floor_ind[$target_id]["'".$range."'"] = $code_floor_index[$target_id]["'".$range."'"];
			}
			
		}
		
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {		
			$project_number_check = $core->get_field($more_information, "project_number_check", []);
			$number_check = $project_number_check[$target_id]["number_check"];	
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		}else if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {	
			$block_number_check = $core->get_field($more_information, "block_number_check", []);
			$number_check = $block_number_check[$target_id]["number_check"];
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
		}
		// Get spreadsheetId đã được nhân bản lúc chọn Sheet
		$tmp = $clsTemporary->getByCond("`sid`='{$sid}' and `user_id`='".$core->_USER['user_id']."' 
			and `resource_id`='{$spreadsheetId}' order by `reg_date` DESC");
		if(!empty($tmp)){
			$spreadsheetId = $tmp['spreadsheetId'];
		}
		$arr_data = $clsCrawl->getDataConfigColumn($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type); 
		$cachedFileData = DIR_CACHE_JSON.'/crawl/'.$uid.'.json';
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($arr_data, $cachedFileData);
		$number_column_sheet = [];
//		$clsISO->print_pre($arr_data);die;
		foreach ($arr_data as $key => $data_sheet) {
			$highestColumnIndex = 0;
			foreach ($data_sheet as $data) {
				$max_column = count($data);
				if($max_column > $highestColumnIndex) {
					$highestColumnIndex = $max_column;
				}
			}
			$number_column_sheet[$key] = $highestColumnIndex+1;
		}
		
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arr_column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) 
			? $arr_column_data[$agency_id][$target_id] : array();			
		
		$highestColumnIndex = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? 25 : 35;
		$smarty->assign("number_column_sheet",$number_column_sheet);
		$smarty->assign("arr_sheet_range",$arr_sheet_range);
		$smarty->assign("arr_sheet_building",$arr_sheet_building);
		$smarty->assign("arr_stock_points",$arr_stock_points);
		$smarty->assign("number_check",$number_check);
		$smarty->assign("clsStock",$clsStock);
		$smarty->assign("column_data",$column_data);
		$smarty->assign("agency_id",$agency_id);
		$smarty->assign("target_id",$target_id);
		$smarty->assign("stock_type",$stock_type);
		$smarty->assign("highestColumnIndex",$highestColumnIndex);
		$smarty->assign("is_stock_point",$is_stock_point);
		$smarty->assign("code_floor_ind",$code_floor_ind);
		$smarty->assign("arr_data",$arr_data);
		$smarty->assign("uid",$uid);
		// Return
		$html = $core->build("_ajax.open_config_column.tpl");
		echo json_encode(array(
			'result' =>	true,
			'uid' =>	$uid,
			'html' => $html
		)); die();
	}else{
		$res = array(
			"result" =>	false,
			'msg' => "Vui lòng nhập đủ thông tin spreasheetID và sheetname",
		);
	}
	echo json_encode($res); die();
//	$clsISO->print_pre($arr_data);die;
}
function default_do_config_column(){
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$smarty;
	$decoder = new Webmozart\Json\JsonDecoder();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawlLowFloor = new CrawlLowFloor();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	#
	$uid = $clsISO->getUniqid();
	$agency_id = (int) Input::post('agency_id', 0);
	$target_id = (int) Input::post('target_id',0);
	$stock_type = (int) Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$sheet_name = 	Input::post('sheet_name',"");
	$number_check = 	Input::post('number_check',array());
	$columns = 	Input::post('columns',array());
	$spreadsheetId = 	Input::post('sheetID',"");
	$gId = 	Input::post('gId',"");
	$cachedFileData = DIR_CACHE_JSON.'/crawl/'.$gId.'.json';
	if(file_exists($cachedFileData)){
		$cache_data = $decoder->decodeFile($cachedFileData);	
		@unlink($cachedFileData);
	}	
//	var_dump($cache_data);die;
	$column_data = array();
//	var_dump($_POST);die;
	
	if(!empty($agency_id) && !empty($target_id)) {
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$project_number_check = $core->get_field($more_information, "project_number_check", []);
			$project_number_check[$target_id]["number_check"] = $number_check;
			if(!empty($cache_data)) {
				foreach ($cache_data as $key => $arr_data) {
					$row_check = $number_check[$key];
					foreach ($arr_data as $k => $v) {
						if($k == $row_check) {
							$project_number_check[$target_id]["list_data_check"][$key] = $v;
							break;
						}
					}
					
				}
			}	
			$more_information['project_number_check'] = $project_number_check;
			$clsProperty->updateOne($agency_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));	
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		}elseif($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {			
			$arr_floor_index = 	Input::post('floor_index',array());
			$arr_code_index = 	Input::post('arr_code_index',array());
			
			$block_number_check = $core->get_field($more_information, "block_number_check", []);
			$block_number_check[$target_id]["number_check"] = $number_check;
			$arr_row_code = $arr_col_floor = $arr_code_floor = $code_floor_index = [];
			if(!empty($cache_data)) {
				foreach ($cache_data as $key => $arr_data) {
					if(isset($arr_floor_index[$key]) && isset($arr_code_index[$key])) {
						foreach($arr_floor_index[$key] as $building_id => $number_key) {
							$floor_index = $number_key;
							$code_index = $arr_code_index[$key][$building_id];
							$code_floor_index[$target_id][$key][$building_id] = [
								"floor_index"	=>	$floor_index,
								"code_index"	=>	$code_index,
							];
							foreach ($arr_data as $k => $v_row) {
								if($k == $code_index) {
									$arr_row_code[$target_id][$key][$building_id] = $v_row;
									
								}
								for ($col=0; $col < 50; $col ++) {
									if($col == $floor_index) {
										$arr_col_floor[$target_id][$key][$building_id][] = $v_row[$col];
									}
								}
							}
						}
						$arr_code_floor = [
							"arr_row_code"	=>	$arr_row_code,
							"arr_col_floor"	=>	$arr_col_floor,
							"code_floor_index"	=>	$code_floor_index,
						];
						
					}else{
						$row_check = $number_check[$key];
						foreach ($arr_data as $k => $v) {
							if($k == $row_check) {
								$block_number_check[$target_id]["list_data_check"][$key] = $v;
								break;
							}
						}
					}
					
				}
			}
			$more_information['block_number_check'] = $block_number_check;
			$more_information['arr_code_floor'] = $arr_code_floor;
			$clsProperty->updateOne($agency_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));	
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
		}
		if(file_exists($cachedFile)){
			$column_data = $decoder->decodeFile($cachedFile);			
		}
		$column_data[$agency_id][$target_id] = $columns;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($column_data, $cachedFile);
	}
	
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_help(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$type = Input::post("type","highfloor");
	if($type == "highfloor") {
		$SiteMsg_CRAWL_Help = $clsConfiguration->getValue('SiteMsg_Config_CRAWL_Highfloor_Help');	
	}else{
		$SiteMsg_CRAWL_Help = $clsConfiguration->getValue('SiteMsg_Config_CRAWL_Lowfloor_Help');
	}
	
	$html = '<div class="modal-dialog modal-ipad modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">Hướng dẫn sử dụng</h5>
				<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			</div>
			<div class="modal-body">
				<div class="tinyContent">
					'.html_entity_decode($SiteMsg_CRAWL_Help).'
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'uid' => $clsISO->getUniqid()
	)); die();
}
?>