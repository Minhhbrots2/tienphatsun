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
	require_once DIR_COMMON."/Common.php";
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
	$clsStock = new Stock();
	$clsStockLog = new StockLog();
	$clsStockMeta = new StockMeta();
	$clsConfiguration = new Configuration();
	
	$curl = new \Curl\Curl();
	$curl->get('https://masterisehomes.id.vn/api/data');
	if(!$curl->error){
		$response = $curl->response;
		$tblData = toArray($response);
		// $clsISO->print_pre($tblData); die();
		if(!empty($tblData)){
			$crawl_stocks = $clsConfiguration->getValue('crawl_stocks');
			$crawl_stocks = $clsISO->to_array_json($crawl_stocks);
			$field = "{$clsStock->pkey},`ms_code`,`more_information`";
			$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `agency_id`<>'"._AGENCY_FH_ID."' 
			AND `block_id` in (".implode(',',_PROJECT_BLOCK_MWF_ARRAY).") AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'", $field);
			// $clsISO->print_pre($list_stocks); die();
			$arr_stocks = $arr_sold_stocks = array();
			foreach($tblData as $key => $val){
				$stock_code = $val['Mã căn'];
				$link_ptg = $val['Link PTG'];
				$arr_stocks[] = $stock_code;
				if(!empty($list_stocks)){
					foreach($list_stocks as $okey => $oval){
						$stock_id = trim($oval[$clsStock->pkey]);
						$more_information = $oval['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$price_sheets = isset($more_information['price_sheets']) ? $more_information['price_sheets'] : [];
						if($oval['ms_code'] == trim($stock_code)){
							if(empty($price_sheets) && !empty($link_ptg)){
								$price_sheets[$clsISO->getUniqid()] = array(
									'sheets' => array(
										$clsISO->getUniqid() => array(
											'title' => 'PTG TẠM TÍNH',
											'image' => $link_ptg
										)
									),
									'reg_date' => time(),
									'upd_date' => time(),
									'csbh' => '',
									'user_id' => 0,
									'user_update_id' => 0,
									'from' => '_front'
								);
								$more_information['price_sheets'] = $price_sheets;
								// $clsISO->print_pre($more_information); die();
								$clsStock->updateOne($stock_id, array(
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								));
							}
						} 
					}
				}
			}
			$clsConfiguration->updateValue("crawl_stocks", json_encode($arr_stocks, JSON_UNESCAPED_UNICODE));
			die("");
			$arr_sold_stocks = !empty($crawl_stocks) && !empty($arr_stocks) 
				? @array_diff($crawl_stocks, $arr_stocks) : array();
			if(!empty($arr_sold_stocks)){
$message = "";
				foreach($arr_sold_stocks as $stock_code){
					$set = "`status_id`='"._STOCK_STATUS_SOLD_ID."',`ms_date`='".time()."'";
					$clsStock->updateByCond("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `ms_code`='{$stock_code}'", $set);
$message.= sprintf('💔 Đã bán %s', $stock_code);
$message.= "
";
				}
				// $clsISO->print_pre($message); die();
				foreach(_ZALO_GROUP_SOLD_NOTIFY as $id_group){
					// $clsISO->print_pre($id_group); die();
					$curl = new \Curl\Curl();
					$curl->setHeaders(array(
						'Content-Type' => 'application/json',
						'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
					));
					$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
						'message' => preg_replace('/\n/','', $message),
						'group_id' => $id_group
					));
				}
			}
		}
	}
	die('Cron success');
?>