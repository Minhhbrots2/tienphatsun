<?php
//	 ini_set('display_errors', '1');
//	 ini_set('display_startup_errors', '1');
//	 error_reporting(E_ALL);
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
	if(!defined('LANG_DEFAULT')) 
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
	global $profile_id;
	$profile_id = 0;
	/** End ClassRequirement */
	$clsISO = new ISO();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsStockLog = new StockLog();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsConfiguration = new Configuration();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_agency_cronjob_highfloor.json';
	$cachedFileLog = DIR_CACHE_JSON.'/crawl/cache_agency_log_highfloor.json';
	###
	$decoder = new Webmozart\Json\JsonDecoder();		
	$encoder = new Webmozart\Json\JsonEncoder();
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
	$cond = "`property_type`='_AGENCY' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL";	
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
	
	$arr_status_id = [];
	if(!empty($lstAgency)) {
		$is_crawl = $i = 0;
		$total_cronjob = 2;
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
				foreach ($block_crawl as $block_id => $v_cr) {
					if(!empty($v_cr['is_crawl']) && !empty($v_cr["sheet_name"]) && !empty($v_cr["sheetID"])) {
						$ranges = explode("|",$v_cr["sheet_name"]);
						if($i < $total_cronjob) {
							$crawl_tmp[] = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$block_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
							];
						}
						if(empty($block_agency_crawl) || (!empty($block_agency_crawl) && !$clsISO->checkItemInArray($block_id,$block_agency_crawl))) {	
							$crawl = [
								"agency_id"	=>	$val[$clsProperty->pkey],
								"target_id"	=>	$block_id,
								"ranges"	=>	$ranges,
								"spreadsheetId"	=>	$v_cr["sheetID"],
							];
							++$is_crawl;
							if($is_crawl <= $total_cronjob) {								
								$arr_crawl[] = $crawl;
							}							
							unset($crawl);
						}
						++$i;
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
	if(!empty($arr_crawl)) {
		foreach ($arr_crawl as $key => $crawl) {
			$arr_not_upd = $arr_upd = $arr_data = $arr_ms_code_new = array();
			$target_id = $crawl["target_id"];
			/*giá min max*/
			$oneBlock = $clsProperty->getOne($target_id,"for_id");
			$min = $max = "";
			if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
				$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$target_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$target_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
			}
			$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
			$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
			/*end giá min max*/
			
			$agency_id = $crawl["agency_id"];
			$stock_status_id = !empty($arr_status_id[$agency_id]) ? $arr_status_id[$agency_id] : _STOCK_STATUS_LOCK_ID;
			$spreadsheetId = $crawl["spreadsheetId"];
			$ranges = $crawl["ranges"];
			$res = $clsCrawl->getDataNew($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type);
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
							$val['ms_code'] = $ms_code;
							$ms_code = preg_replace('/\s+/', '', $ms_code);
							$ms_code = trim(str_replace('.','', $ms_code));
							$ms_code = str_replace("-","",$ms_code);
							$ms_code = strtoupper($ms_code);
							$arr_data_code[$ms_code] = $val;
						}
						unset($ms_code);
					}
					if(!empty($arr_data_code)) {
						$arr_code_old = [];
						$arr_ms_code = array_keys($arr_data_code);
						$str_code_in = implode("','",$arr_ms_code);
						$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$target_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND REPLACE(REPLACE(`ms_code`,'-',''),'.','') IN ('".$str_code_in."')");
						//$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$target_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id`='"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `ms_code` IN ('".$str_code_in."')");
						if(!empty($list_agency_stocks)) {
							foreach ($list_agency_stocks as $k_stock => $oneStock) {
								$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
								#
								$ms_code = $oneStock["ms_code"];
								$ms_code = preg_replace('/\s+/', '', $ms_code);
								$ms_code = trim(str_replace('.','', $ms_code));
								$ms_code = str_replace("-","",$ms_code);
								$ms_code = strtoupper($ms_code);
								
								$arr_stock_code_not_in[] = $oneStock["ms_code"];
								$arr_data_code[$ms_code]["stock_id"] = $oneStock["stock_id"];
								$arr_data_code[$ms_code]["oneStock"] = $oneStock;
								if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
									$arr_code_old[] = $oneStock["ms_code"];
								}
							}
							unset($list_agency_stocks,$ms_code);
						}
						$arr_ms_code_new = array_diff($arr_ms_code, $arr_code_old);
						$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
					}else{
						$data = array(
							"title_log"	=>	'File Gooogle Sheet đã bị thay đổi',
							"type"	=>	1,	//0:t?ng h?p,1:drive,2:hình ?nh,3:copy,
							"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
						);
						$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
						continue;
					}
					$lstStock = array_values($arr_data_code);
					// C?p nh?t các can thành dã bán
					$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`";
					$g_cond = "`agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') 
					and `stock_type`='".$stock_type."' AND `block_id`='{$target_id}' AND `is_trash`=0";
					if(!empty($arr_stock_id_not_in)){
						$g_cond.= " and `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
					}
					$list_sold_stocks = $clsStock->getAll($g_cond, $field);
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
							
							$total_price = !empty($v_stock["total_price"]) 
								? $clsISO->convertPriceShortToFullUpdate($v_stock["total_price"],$min,$max) : 0;
							$total_price_vat = !empty($v_stock["total_price_vat"]) 
								? $clsISO->convertPriceShortToFullUpdate($v_stock["total_price_vat"],$min,$max) : 0;
							$total_price_progress = !empty($v_stock["total_price_progress"]) 
								? $clsISO->convertPriceShortToFullUpdate($v_stock["total_price_progress"],$min,$max) : 0;
							$total_price_early = !empty($v_stock["total_price_early"]) 
								? $clsISO->convertPriceShortToFullUpdate($v_stock["total_price_early"],$min,$max) : 0;
							$total_price_bank = !empty($v_stock["total_price_bank"]) 
								? $clsISO->convertPriceShortToFullUpdate($v_stock["total_price_bank"],$min,$max) : 0;
							$total_price_bank_half = !empty($v_stock["total_price_bank_half"]) 
								? $clsISO->convertPriceShortToFullUpdate($v_stock["total_price_bank_half"],$min,$max) : 0;
							$csbh = !empty($v_stock["csbh"]) ? $v_stock["csbh"] : "";
							$price_sheet_link = !empty($v_stock["price_sheet_link"]) ? $v_stock["price_sheet_link"] : "";
							$link_smartchip = !empty($v_stock["link_smartchip"]) ? $v_stock["link_smartchip"] : "";
							$price_sheet_title = !empty($v_stock["price_sheet_title"]) ? $v_stock["price_sheet_title"] : "PTG TẠM TÍNH";
							$DT_TT = !empty($v_stock["DT_TT"]) ? $clsISO->toNumber(trim($v_stock["DT_TT"])) : "";
							$DT_Tim = !empty($v_stock["DT_Tim"]) ? $clsISO->toNumber(trim($v_stock["DT_Tim"])) : "";
							$date_deposit_sign = !empty($v_stock["date_deposit_sign"]) ? $v_stock["date_deposit_sign"] : "";
							$more_information_stock = $clsISO->to_array_json($oneStock['more_information']);
							$more_information_stock['ms_code'] = $ms_code;		
							
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
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
							}
							$upd_field['agency_id'] = $agency_id;
							$more_information_stock['agency_id'] = $agency_id;
							if(!empty($csbh)) $more_information_stock['csbh'] = $csbh;
							if(!empty($DT_TT)) $more_information_stock['DT_TT'] = $DT_TT;
							if(!empty($DT_Tim)) $more_information_stock['DT_Tim'] = $DT_Tim;
							if(!empty($date_deposit_sign)) $more_information_stock['date_deposit_sign'] = $date_deposit_sign;
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
								$list_stock_id[$target_id][] = $oneStock[$clsStock->pkey];
							} else{
								$arr_not_upd[] = $ms_code;
							}
						}
					}
				} else {
					$field = "{$clsStock->pkey},`status_id`,`more_information`";
					$list_stocks = $clsStock->getAll("`agency_id`='{$agency_id}' AND `stock_type`='".$stock_type."' 
					AND `block_id`='{$target_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')", $field);
					if(!empty($list_stocks)){
						$total_updated = 0;
						foreach($list_stocks as $key => $val){
							$logs = array();
							$more_information = $val['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							
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
							$more_information['user_id_update_sold'] = 0;
							$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
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
				//log cap nhat
				$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
				$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;					
				#log new
				$arr_data_upd = array(
					"total_stock_sold"	=>	$total_stock_sold,
					"total_stock_new"	=>	$total_stock_new,
					"total_stock"	=>	$total_updated,
					"stock_not_upd"	=>	$arr_not_upd,
					"data_log"	=>	$arr_data,
					"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
					"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
				);
				$clsLogCrawl->log($agency_id,$target_id, $arr_data_upd, $stock_type);
				
				// Start Logs 
				$logs_field = "{$clsStockLog->pkey},`more_information`";
				foreach ($list_stock_id as $block_id => $stock_ids) {	
					$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
					if(!empty($tmp)){
						$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
							'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
						));
					} else {
						$clsStockLog->insert(array(
							'stock_type' => $stock_type,
							'agency_id' => $agency_id,
							'block_id' => $block_id,
							'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
							'reg_date' => time(),
							'user_id' => 0
						));
					}
				}
				/** End */
			}
			$result = array(
				'result' =>	true,
				'crawl'	=>	$crawl,
				'msg' => 'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
				'total_updated' => $total_updated,
				'total_stock_sold' => $total_stock_sold,
				'total_stock_new' => $total_stock_new,
				'stock_not_upd' => $arr_not_upd,
				'arr_upd' => $arr_upd,
			);
			$lstCacheLog[] = [
				"target_id" => $target_id,
				"agency_id" => $agency_id,
				"time"		=>	date("d/m/Y H:i:s")
			];
			$encoder->encodeFile($lstCacheLog, $cachedFileLog);
			// $clsISO->print_pre($result);
			echo json_encode($result);
		}
	}
?>