<?php 
	error_reporting(false);
	ini_set('display_errors', 0);
	ini_set('memory_limit', '5048M');
	
	define('DS', DIRECTORY_SEPARATOR);
	define("_SITE_ROOT", 'CRONJOB');
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
	require_once DIR_COMMON."/Core.php";
	require_once DIR_COMMON."/Module.php";
	require_once DIR_COMMON."/Download.php";
	require_once DIR_COMMON."/Upload.php";
	require_once DIR_COMMON."/Config.php";
	require_once DIR_INCLUDES."/curl/vendor/autoload.php";
	require_once DIR_INCLUDES."/carbon/vendor/autoload.php";
	require_once(DIR_INCLUDES.'/json_master/autoload.php');				
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	require_once DIR_INCLUDES."/curl/vendor/autoload.php";
	require_once DIR_INCLUDES . '/php-simple-redis-cache/vendor/autoload.php';

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
	$clsISO = new ISO();
	$clsConfiguration = new Configuration();
	
	$spreadsheetId = '1wHDM2ShXLowT_F4dt7LIX-V-O2FNzHyVliSpEyI1f6s';
	/** Required Lib */
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->setScopes([
		Google_Service_Drive::DRIVE,
		Google_Service_Sheets::SPREADSHEETS
	]);
	$client->fetchAccessTokenWithRefreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$service = new Google_Service_Sheets($client);
	// Get all sheets
	$spreadsheet = $service->spreadsheets->get($spreadsheetId);
	$arr_worksheet = $spreadsheet->sheets;
	
	$crawl_data = array();
	if(!empty($arr_worksheet)){
		foreach($arr_worksheet as $sheet){
			$sheetName = $sheet->getProperties()->getTitle(); 
			// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
			// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
			// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
			// get all the rows of a sheet
			$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$sheetName."'"], array(
				'valueRenderOption' => 'FORMATTED_VALUE'
			));
			$tblData = $response->getValues();
			// $clsISO->print_pre($tblData); die();
			if(!empty($tblData)){
				$totalRecord = count($tblData);
				for($i=2; $i<$totalRecord; $i++){
					$expected_date = $tblData[$i][0];
					$expected_month = $tblData[$i][1];
					$expense_group = $tblData[$i][2];
					$paid_by = $tblData[$i][3];
					$office_name = $tblData[$i][4];
					$project_name = $tblData[$i][5];
					$partner_group = $tblData[$i][6];
					$content = $tblData[$i][7];
					$expected_amount = $tblData[$i][8];
					$notes = $tblData[$i][9];
					$status = $tblData[$i][10];
					if(!empty($expected_date) && !empty($expected_month) 
						&& !empty($expected_amount) && $status == 'Chưa chi'){
						$crawl_data[$sheetName][] = array(
							'expected_date' => $tblData[$i][0],
							'expected_month' => $tblData[$i][1],
							'expense_group' => $tblData[$i][2],
							'paid_by' => $tblData[$i][3],
							'office_name' => $tblData[$i][4],
							'project_name' => $tblData[$i][5],
							'partner_group' => $tblData[$i][6],
							'content' => $tblData[$i][7],
							'expected_amount' => $tblData[$i][8],
							'notes' => $tblData[$i][9],
							'status' => $tblData[$i][10]
						);
					}
				}
			}
		}
	}
	$clsConfiguration->updateValue(
		'planned_expected_expense', 
		json_encode(array(
			'upd_date' => time(),
			'data' => $crawl_data
		), JSON_UNESCAPED_UNICODE)
	);
	// Output
	die('Success');
?>