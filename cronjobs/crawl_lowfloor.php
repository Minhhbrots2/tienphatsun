<?php
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	// ini_set('post_max_size','200M');
	ini_set('memory_limit', '5048M');
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH',$_SERVER['DOCUMENT_ROOT']);
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	define("DIR_INCLUDES", ROOTPATH."/core");
	define("DIR_MODELS", ROOTPATH."/models");
	define("DIR_ADODB", DIR_INCLUDES."/adodb5");
	/** Required Config */
	require(ABSPATH.DS.'init.php');
	require(ABSPATH.DS.'setting.php');
	define("DIR_LANG", PCMS_DIR."/lang");
	define("LANG_DEFAULT",'vn');
	/** Debugging */
	define("SMARTY_DEBUG", 	false);//debug or not
	define("COMPILE_CHECK", true);//compile check
	define("ADODB_DEBUG", 	false);//debug or not
	define("STOP_APP_IF_ERROR", 1);//stop if error happen 0: no, 1: yes
	/** DriverDatabase */
	require_once(DIR_ADODB."/adodb.inc.php");
	if(defined('ADODB_CACHED') && ADODB_CACHED==1){
		$ADODB_CACHE_DIR = DIR_CACHE_ADODB;
		$GLOBALS['ADODB_CACHE_DIR'] = DIR_CACHE_ADODB;
		$dbconn =& ADONewConnection(DB_TYPE);
		$dbconn->debug = ADODB_DEBUG;
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$dbconn->cacheSecs = ADODB_CACHED_TIME;
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	} else {
		$dbconn =& ADONewConnection(DB_TYPE);
		$dbconn->debug = ADODB_DEBUG;
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	}
	/** Core Requirement */
	require_once DIR_COMMON."/DbBasic.php";
	require_once DIR_COMMON."/App.php";
	//require_once DIR_COMMON."/Core.php";
	require_once DIR_COMMON."/Module.php";
	require_once DIR_COMMON."/Download.php";
	require_once DIR_COMMON."/Upload.php";
	require_once DIR_COMMON."/Config.php";
	require_once DIR_INCLUDES."/curl/vendor/autoload.php";
	require_once DIR_INCLUDES."/carbon/vendor/autoload.php";
	/** ClassRequirement */
	if(function_exists('spl_autoload_register')){
		function autoload($model){
			if(file_exists(DIR_MODELS.DS.$model.'.php')){
				require_once(DIR_MODELS.DS.$model.'.php');
			} else if(file_exists(DIR_MODELS.'/class.'.$model.'.php')){
				require_once(DIR_MODELS.'/class.'.$model.'.php');
			}
		}
		spl_autoload_register('autoload');
	}
	/** End ClassRequirement */	
	/** Library Requirement */
	if (is_dir(DIR_LIB)){
		$customLibArray = array();
		if ($dh = opendir(DIR_LIB)) {
			while (($file = readdir($dh)) !== false) {
				if (substr($file, -3)=='php')
				array_push($customLibArray, $file);
			}
			closedir($dh);
		}
		if(!empty($customLibArray)){
			foreach ($customLibArray as $customLib){
				require_once(DIR_LIB."/".$customLib);
			}
		}
	}
	/** End ClassRequirement */
	$clsISO = new ISO();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsStockLog = new StockLog();
	$profile_id = 0;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsConfiguration = new Configuration();
	$decoder = new Webmozart\Json\JsonDecoder();
	$encoder = new Webmozart\Json\JsonEncoder();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_agency_cronjob_lowfloor.json';
	$cachedFileLog = DIR_CACHE_JSON.'/crawl/cache_agency_log_lowfloor.json';
	if(file_exists($cachedFile)){
		$lstCache = $decoder->decodeFile($cachedFile);
	}
	if(file_exists($cachedFileLog)){
		$lstCacheLog = $decoder->decodeFile($cachedFileLog);
	}
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$lastUpdate = !empty($lstCache) ? end($lstCache) : array();
	$cond = "`property_type`='_AGENCY' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL";	
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

	$arr_status_id = [];
	if(!empty($lstAgency)) {
		$is_crawl = $i = 0; $total_cronjob = 2;
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
							$ranges[$k] = $range."!A1:AZ500";
						}
						if($i < $total_cronjob) {
							$crawl_tmp[] = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$project_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
								"crawl_lowfloor"	=>	$v_cr,
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
		foreach ($arr_crawl as $key => $val) {
			if(!$clsISO->checkItemInArray($val["target_id"],$lstCache[$val['agency_id']])) {
				$lstCache[$val['agency_id']][] = $val['target_id'];
			}			
		}	
	}else{ 	
		foreach ($arr_crawl as $key => $val) {
			if(!$clsISO->checkItemInArray($val["target_id"],$lstCache[$val['agency_id']])) {
				$lstCache[$val['agency_id']][] = $val['target_id'];
			}
		}		
	}
	$encoder->encodeFile($lstCache, $cachedFile);
	// $clsISO->print_pre($arr_crawl);die;
	if(!empty($arr_crawl)) {
		foreach ($arr_crawl as $key => $crawl) {
			$target_id = $crawl["target_id"];
			$agency_id = $crawl["agency_id"];
			$stock_status_id = !empty($arr_status_id[$agency_id]) ? $arr_status_id[$agency_id] : _STOCK_STATUS_LOCK_ID;
			$spreadsheetId = $crawl["spreadsheetId"];
			$ranges = $crawl["ranges"];
			$crawl_lowfloor = $crawl["crawl_lowfloor"];
			$res = $clsCrawl->getDataLowFloor($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type,$crawl_lowfloor);
			$total_stock_sold = $total_stock_new = $total_updated = 0;
			if(!empty($res["result"])) {
				$arr_data = !empty($res["tblData"]) ? $res["tblData"] : array();
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
					}else{
						$data = array(
							"title_log"	=>	'File Gooogle Sheet đã bị thay đổi',
							"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
							"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
						);
						$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
						echo json_encode(array(
							'result'	=>	false,
							'msg' => 'File Gooogle Sheet đã bị thay đổi. Hãy cập nhật lại cấu hình'
						));	die();
					}
					$lstStock = array_values($arr_data_code);
					
					# Cập nhật các căn thành đã bán
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
						foreach($lstStock as $k_stock => $v_stock){
							$oneStock = $v_stock["oneStock"];
							$stock_id = $v_stock['stock_id'];
							$ms_code = $v_stock["ms_code"];
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
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
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
									}
									$more_information_stock[$p_field] = $price_tmp;
								}
							}	
							$upd_field['upd_date'] = time();
							$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
							if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
								$total_updated += 1;
								$arr_upd[] = $ms_code;
								if(!empty($oneStockMeta) && !empty($logs)){
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
				//log cập nhật
				$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
				$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;		
				#log new
				$arr_data_upd = array(
					"total_stock_sold"	=>	$total_stock_sold,
					"total_stock_new"	=>	$total_stock_new,
					"total_stock"	=>	$total_updated,
					"stock_not_upd"	=>	$arr_not_upd,
					"data_log"	=>	$arr_data,
					"title_log"	=>	'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
					"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
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
			}
			$result = array(
				'result'	=>	true,
				'crawl'	=>	$crawl,
				'msg' => 'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
				'total_updated' => $total_updated,
				'total_stock_sold' => $total_stock_sold,
				'total_stock_new' => $total_stock_new,
				'stock_not_upd' => $arr_not_upd,
				'arr_upd' => $arr_upd,
			);
			$encoder->encodeFile($lstCacheLog, $cachedFileLog);
//			$clsISO->print_pre($result);
			echo json_encode($result);		
		}
	}
?>