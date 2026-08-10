<?php
die;
//	 ini_set('display_errors', '1');
//	 ini_set('display_startup_errors', '1');
//	 error_reporting(E_ALL);
	ini_set('memory_limit', '5048M');
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	define('DS', DIRECTORY_SEPARATOR);
	define("_SITE_ROOT", 'CRONJOB');
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
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
		$offset="+7:00";
		$dbconn->Execute("SET time_zone='".$offset."';");
	} else {
		$dbconn =& ADONewConnection(DB_TYPE);
		$dbconn->debug = ADODB_DEBUG;
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
		$offset="+7:00";
		$dbconn->Execute("SET time_zone='".$offset."';");
	}
	/** Core Requirement */
	require_once DIR_COMMON."/DbBasic.php";
	require_once DIR_COMMON."/App.php";
	require_once DIR_COMMON."/Core.php";
	require_once DIR_COMMON."/Module.php";
	require_once DIR_COMMON."/Download.php";
	require_once DIR_COMMON."/Upload.php";
	require_once DIR_COMMON."/Config.php";
	require_once DIR_COMMON."/Common.php";
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
	$core = new Core();
	global $profile_id;
	/** End ClassRequirement */
	$clsISO = new ISO();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsCrawl = new Crawl();
	$clsLogCrawl = new LogCrawl();
	$clsStock = new Stock();
	$clsStockLog = new StockLog();
	$clsStockAgent = new StockAgent();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsZalo = new Zalo();
//	====================
	$arr_sold_stocks = ["C2Z2-11-10","C2Z2-23-11"];
	if(!empty($arr_sold_stocks)) {		
		$stock_type = 178;
		$target_id = 10846;
		$txt_project = "🏠 Dự án: **{green}Masteri Era Landmark{/green}**\n";
		$clsStock->setDeBug(1);
		$arr_sold_stocks = array_map(function($v){
			return preg_replace('/[^a-zA-Z0-9]/', '', $v);
		}, $arr_sold_stocks);
		$list_sold_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$target_id}' AND REGEXP_REPLACE(ms_code, '[^a-zA-Z0-9]', '') IN ('".implode("','",$arr_sold_stocks)."')".$sql_string,$clsStock->pkey.',ms_code,bedroom_id,total_price_vat');
//		$clsISO->print_pre($list_sold_stocks);die;
		$message = "";
		$message .= $txt_project;
		$arr_cache_bedroom = [];
		foreach ($list_sold_stocks as $key => $val) {
			if(!isset($arr_cache_bedroom[$val["bedroom_id"]])) {
				$arr_cache_bedroom[$val["bedroom_id"]] = $clsProperty->getTitle($val["bedroom_id"]);
			}
			$message.= "💔 Đã bán **{red}".$val["ms_code"]."{/red}** (".$arr_cache_bedroom[$val["bedroom_id"]].", ".$clsISO->shortNumber($val['total_price_vat']).")\n";
		}
		$body = $clsZalo->createZaloPayloadFromMarkedMessage($message);

//		$arr_group_id = _ZALO_GROUP_SOLD_NOTIFY;
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
	}die;
//	====================
	##
	$dataCached = array();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_agency_cronjob_stock.json';
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();		
		$dataCached = $decoder->decodeFile($cachedFile);
	}
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$cond = "`is_trash`=0 AND `property_type`='_AGENCY' AND ((JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL) || (JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL))";
	if(!empty($dataCached)) {
		$cond .= " AND `{$clsProperty->pkey}` NOT IN (".implode(',',$dataCached).")";
	}
	$oneAgency = $clsProperty->getByCond($cond,$clsProperty->pkey.",`title`,`more_information`");
	if(empty($oneAgency)) {
		$oneAgency = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_AGENCY' AND ((JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL) || (JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL))",$clsProperty->pkey.",`title`,`more_information`");
		$dataCached = array();
	}
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
			foreach($block_crawl as $block_id => $v_cr) {
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
					];
				}
			}
		}
		
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($dataCached, $cachedFile);
		
		if(!empty($arr_crawl)) {
			$arr_stock_crawls = array();
			foreach($arr_crawl as $key => $crawl) {
				$list_stocks = array();
				$ranges = $crawl["ranges"];
				$target_id = $crawl["target_id"];
				$agency_id = $crawl["agency_id"];
				$stock_type = (int) $crawl["stock_type"];
				$spreadsheetId = $crawl["spreadsheetId"];
				if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
					$res = $clsCrawl->getDataNew($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type);
				}elseif($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
					 $crawl_lowfloor = $crawl["crawl_lowfloor"];
					 $res = $clsCrawl->getDataLowFloor($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type,$crawl_lowfloor);
				}
				
				$total_stock_sold = $total_stock_new = $total_updated = 0;
				if(!empty($res["result"])) {
					$tblData = !empty($res["tblData"]) ? $res["tblData"] : array();
					if(!empty($tblData)) {
						$arr_data_code = array();
						$total_record = @count($tblData);
						$arr_stock_code_not_in = $arr_stock_id_not_in = array();
						foreach($tblData as $key => $val) {
							$ms_code = trim($val['ms_code']);
							if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
								$val['ms_code'] = $ms_code;
								$arr_data_code[] = $ms_code;
							}
							unset($ms_code);
						}
						if(!empty($arr_data_code)) {
							$str_code_in = implode("','",$arr_data_code);
							if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
								$list_agent_stocks = $clsStock->getAll("`is_trash`=0 AND `block_id`='{$target_id}' AND `stock_type`='{$stock_type}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `ms_code` IN ('".$str_code_in."')",$clsStock->pkey.",ms_code");
							}elseif($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
								$list_agent_stocks = $clsStock->getAll("`project_id`='{$target_id}' AND `stock_type`='".$stock_type."' AND (`agency_id` <> '"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `is_trash`=0 AND `ms_code` IN ('".$str_code_in."')",$clsStock->pkey.',ms_code');
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
					#luu bang tam
					$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$target_id,$list_stocks);				
				}
			}
			$arr_cached_stocks = array();
			$list_cached_stocks = $clsStockAgent->getAll("`agency_id`='{$agency_id}'");
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
//				$arr_sold_stocks = @array_diff($arr_cached_stocks,$arr_stock_crawls);
				$arr_sold_stocks = array_unique($arr_sold_stocks);
				$arr_sold_stocks = array_values($arr_sold_stocks);
				if(!empty($arr_sold_stocks)){					
					$message = "";
					foreach($arr_sold_stocks as $stock_code){
						$message.= sprintf('💔 Đã bán %s', $stock_code);
						$message.= "\n";
					}
					// _ZALO_GROUP_SOLD_NOTIFY
					foreach(_ZALO_GROUP_SOLD_NOTIFY as $id_group){
						$curl = new \Curl\Curl();
						$curl->setHeaders(array(
							'Content-Type' => 'application/json',
							'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
						));
						$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
							'message' => $message,
							'group_id' => $id_group
						));
					}
				}
			}
			$res = array(
				'agency_id' => $agency_id,
				'agency_name' => $oneAgency['title'],
				'ms_codes' => $resStockCrawl["ms_codes"],
				'total' => $resStockCrawl["total"],
				'stock_sold' => $arr_sold_stocks,
				'date' => date("d/m/Y H:i"),
			);
			echo json_encode($res);
		}
	}
?>













