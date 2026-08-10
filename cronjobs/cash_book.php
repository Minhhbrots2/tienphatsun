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
	$clsCache = new Cache();
	$clsSetting = new Setting();
	$clsCashBook = new CashBook();
	$clsConfiguration = new Configuration();
	
	$current_year = date('Y');
	$profile_id = _PROFILE_TECH_ID;
	$list_group_company = $clsSetting->getCacheItems('_GROUP_COMPANY');
	if(!empty($list_group_company)){
		$cash_book_configs = $clsConfiguration->getValue('cash_book_configs');
		$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
		$columns = $core->get_field($cash_book_configs, "columns", []);
		if(!empty($columns) && (int) $core->get_field($cash_book_configs, "is_run_cronjob", 0) == 1){
			foreach($list_group_company as $key => $val){
				$company_id = (int) $val[$clsSetting->pkey];
				$one_configs = $core->get_field($cash_book_configs, $company_id, []);
				if(!empty($one_configs)){
					$spreadsheetId = $core->get_field($one_configs, "spreadsheetId", "");
					$sheet_name = $core->get_field($one_configs, "sheet_name", "");
					if(!empty($spreadsheetId) && !empty($sheet_name)){
						if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
							@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
							$spreadsheetId = $matches[0];
						}
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
						// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
						// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
						// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
						// get all the rows of a sheet
						try {
							$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$sheet_name."'"], array(
								'valueRenderOption' => 'FORMATTED_VALUE'
							));
							$tblData = $response->getValues();
						} catch(Exception $ex){
							$msg_error = $ex->getMessage();
							if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
								$drive = new Google_Service_Drive($client);
								// Đọc file Excel từ Google Drive
								$response = $drive->files->get($spreadsheetId, array(
									'supportsAllDrives' => 'true'
								));
								// Chuyển đổi file Excel thành Google Sheets
								$fileMetadata = new \Google_Service_Drive_DriveFile(array(
									'name' => sprintf('[Bản sao]%s', date('d-m-y h:i:s'))
								));
								$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
								$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
								$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
									'supportsAllDrives' => 'true'
								));
								$spreadsheetIdCopy = $convertedFile->getId();
								$response = $service->spreadsheets_values->get($spreadsheetIdCopy, ["'".$sheet_name."'"], array(
									'valueRenderOption' => 'FORMATTED_VALUE'
								));
								$tblData = $response->getValues();
								// Xoá file sau khi lấy dữ liệu xong
								$fileMetadataTrash = new \Google_Service_Drive_DriveFile();
								$fileMetadataTrash->setTrashed(true);
								$drive->files->update($spreadsheetIdCopy, $fileMetadataTrash, array(
									'supportsAllDrives' => true,
									'supportsTeamDrives' => true,
								));
							}				
						}
						if(!empty($tblData)){
							$arr_data = array();
							$totalRecord = count($tblData);
							for($i=4; $i<$totalRecord; $i++){
								$row = array();
								foreach($columns as $i_col => $p_field){
									$row[$p_field] = trim($tblData[$i][$i_col]);
								}
								$arr_data[] = $row;
							}
							// $clsISO->print_pre($arr_data); die();
							if(!empty($arr_data)){
								$current_date = "";
								// Xóa các hàng cũ và chèn hàng mới
								$clsCashBook->deleteByCond("`year`='{$current_year}' AND `company_id`='{$company_id}'");
								// Loop & thêm mới
								foreach($arr_data as $key => $val){
									$date = trim($val['date']);
									if(!empty($date)){
										$current_date = $date;
									} else {
										$date = $current_date;
									}
									$receipt_amount = trim($val['receipt_amount']);
									$payment_amount = trim($val['payment_amount']);
									$receipt_notes = trim($val['receipt_notes']);
									$payment_notes = trim($val['payment_notes']);
									if(!empty($date) && (!empty($receipt_amount) || !empty($payment_amount))){
										if(!empty($receipt_amount)){
											$clsCashBook->insert(array(
												$clsCashBook->pkey => $clsCashBook->getMaxId(),
												'type' => 'THUCTHU',
												'year'	=> $current_year,
												'company_id' => $company_id,
												'date' => $clsISO->convertTextToTime($date),
												'amount' => $clsISO->processSmartNumber($receipt_amount),
												'notes' => $receipt_notes,
												'user_id' => $profile_id,
												'reg_date' => time(),
												'user_id_update' => $profile_id,
												'upd_date' => time()
											));
										}
										if(!empty($payment_amount)){
											$clsCashBook->insert(array(
												$clsCashBook->pkey => $clsCashBook->getMaxId(),
												'type' => 'THUCCHI',
												'year' => $current_year,
												'company_id' => $company_id,
												'date' => $clsISO->convertTextToTime($date),
												'amount' => $clsISO->processSmartNumber($payment_amount),
												'notes' => $payment_notes,
												'user_id' => $profile_id,
												'reg_date' => time(),
												'user_id_update' => $profile_id,
												'upd_date' => time()
											));
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
?>