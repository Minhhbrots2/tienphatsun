<?php
	// Crawl hoa hồng: 1 lần/đêm quét HẾT các kỳ active (ưu tiên kỳ mới), mỗi kỳ tối đa 1 lần/ngày.
	@set_time_limit(0);
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
	require_once(DIR_INCLUDES.'/json_master/autoload.php');				
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');

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
	global $profile_id,$core;
	$profile_id = 0;
	/** End ClassRequirement */
	$clsISO = new ISO();
	$clsLogCrawl = new LogCrawl();
	$clsCommission = new Commission();
	$clsConfiguration = new Configuration();
	$cachedFile = DIR_CACHE_JSON.'/crawl/cache_commission.json';

	$decoder = new Webmozart\Json\JsonDecoder();		
	$encoder = new Webmozart\Json\JsonEncoder();
	$lstCache = array();
	if(file_exists($cachedFile)){
		$lstCache = $decoder->decodeFile($cachedFile);
	}
	$CommissionSheets = $clsConfiguration->getValue("CommissionSheets");
	$CommissionSheets = $clsISO->to_array_json($CommissionSheets);
	$current_time = date("d-m-Y");
//	$clsISO->print_pre($CommissionSheets);die;
	// Gom config theo NĂM rồi lấy job crawl đang bật (mode độc quyền: năm HOẶC quý — không trùng quarter_id).
	// resolveCrawlJobs đã sắp năm mới→cũ; mỗi job có quarter_id + spreadsheetId + sheet_name.
	$years = $clsCommission->normalizeConfig($CommissionSheets);
	$jobs = $clsCommission->resolveCrawlJobs($years);
	$is_update = 0;
	foreach($jobs as $job){
		$key = $job['quarter_id'];
		$spreadsheetId = $job['spreadsheetId'];
		$sheet_name = $job['sheet_name'];
		$cache = !empty($lstCache[$key]) ? $lstCache[$key] : array();
		$spreadsheetid = !empty($cache["spreadsheetid"]) ? $cache["spreadsheetid"] : "";
		// Khóa 1 lần/ngày/kỳ: đã crawl hôm nay và id không đổi thì thôi
		if(!empty($cache) && date("d-m-Y", $cache["time"]) == $current_time && $spreadsheetId == $spreadsheetid){
			continue;
		}
		$total_crawl = (empty($cache) || date("d-m-Y", $cache["time"]) != $current_time) ? 1 : (int) $cache["total"] + 1;
		// Cách ly lỗi: 1 file hỏng (đổi quyền/đổi tên tab...) không giết các file còn lại
		try {
			$_layout = isset($job['layout']) ? $job['layout'] : '';
			$crawl = $clsCommission->crawl($spreadsheetId, $key, $sheet_name, $_layout);
		} catch(Exception $_ex){
			$crawl = '_exception: ' . $_ex->getMessage();
		}
		$lstCache[$key] = [
			"spreadsheetid" =>	$spreadsheetId,
			"time" =>	strtotime($current_time),
			"total" =>	$total_crawl,
		];
		if(strpos($crawl, '_success') !== false){ // PHP 7.4-safe
			$lstCache[$key]["msg"] = $crawl;
		} else {
			$lstCache[$key]["msg"] = "_error";
			$lstCache[$key]["array_error"] = $crawl;
		}
		echo json_encode(array($key => $crawl));
		$is_update = 1;
		$encoder->encodeFile($lstCache, $cachedFile);
		unset($cache, $total_crawl, $spreadsheetid);
		// KHÔNG break — quét hết các kỳ active trong 1 lần chạy đêm
	}
	if(empty($is_update)) 	echo "_empty";
?>