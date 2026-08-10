<?php 
	error_reporting(false);
	ini_set('display_errors',0);
	//ini_set('post_max_size','200M');
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
	#
	$clsISO = new ISO();
	$clsStock = new Stock();
	//$clsStock->tbl = DB_PREFIX . "stock2";
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	#
	$field = "{$clsProperty->pkey},more_information";
	$list_agents = $clsProperty->getAll("`is_locked`=0 and `property_type`='_AGENCY' order by `order_no` ASC", $field);
	if(!empty($list_agents)){
		foreach($list_agents as $key => $val){
			$agency_id = $val[$clsProperty->pkey];
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array(); 
			if(isset($more_information['cron_automation_enable']) && $more_information['cron_automation_enable']==1){
				$spreadsheetId = isset($more_information['spreadsheetId']) && !empty($more_information['spreadsheetId']) 
					? $more_information['spreadsheetId'] : "";
				// $clsISO->print_pre($more_information); die();
				if(!empty($spreadsheetId)){
					require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
					/** Init Client */
					$client = new Google_Client();
					$client->setClientId(GOOGLE_CLIENT_ID);
					$client->setClientSecret(GOOGLE_CLIENT_SECRET);
					$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
					$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
					$service = new Google_Service_Sheets($client);
					// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
					// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
					// get all the rows of a sheet
					$range = 'BH'; // here we use the name of the Sheet to get all the rows
					$response = $service->spreadsheets_values->get($spreadsheetId, $range);
					$tblData = $response->getValues();
					$more_information['last_cronjob_time'] = time();
					$clsProperty->updateOne($agency_id, array(
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					));
					if(!empty($tblData)){
						for($i=2; $i<count($tblData); $i++){
							$ms_code = $tblData[$i][0];
							$total_price_vat = $tblData[$i][1];
							$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
							$csbh = $tblData[$i][2];
							$oneStock = $clsStock->getByCond("agency_id='{$agency_id}' and `ms_code`='{$ms_code}'");
							// $clsISO->print_pre($oneStock); die();
							if(!empty($oneStock)){
								$logs = $oneStock['logs'];
								$more_information = $oneStock['more_information'];
								$logs = !empty($logs) 
									? json_decode(html_entity_decode($logs), true) : array();
								$more_information = !empty($more_information) 
									? json_decode(html_entity_decode($more_information), true) : array();
								$price_sheets = isset($more_information['price_sheets']) && !empty($more_information['price_sheets']) 
									? $more_information['price_sheets'] : array();
								
								$sheets = array();
								#- PTG 1
								if(isset($tblData[$i][3]) && !empty($tblData[$i][3]) 
									&& isset($tblData[$i][4]) && !empty($tblData[$i][4])){
									$uid = $clsISO->getUniqid();
									$sheets[$uid] = array(
										'title' => $tblData[$i][3],
										'image' => $tblData[$i][4]
									);
								}
								#- PTG 2
								if(isset($tblData[$i][5]) && !empty($tblData[$i][5]) 
									&& isset($tblData[$i][6]) && !empty($tblData[$i][6])){
									$uid = $clsISO->getUniqid();
									$sheets[$uid] = array(
										'title' => $tblData[$i][5],
										'image' => $tblData[$i][6]
									);
								}
								#- PTG 3
								if(isset($tblData[$i][7]) && !empty($tblData[$i][7]) 
									&& isset($tblData[$i][8]) && !empty($tblData[$i][8])){
									$uid = $clsISO->getUniqid();
									$sheets[$uid] = array(
										'title' => $tblData[$i][7],
										'image' => $tblData[$i][8]
									);
								}
								#- PTG 4
								if(isset($tblData[$i][9]) && !empty($tblData[$i][9]) 
									&& isset($tblData[$i][10]) && !empty($tblData[$i][10])){
									$uid = $clsISO->getUniqid();
									$sheets[$uid] = array(
										'title' => $tblData[$i][9],
										'image' => $tblData[$i][10]
									);
								}
								if(!empty($sheets)){
									$price_sheet_id = "";
									if(!empty($price_sheets)){
										foreach($price_sheets as $key => $val){
											if(!empty($csbh) && isset($val['csbh']) && $val['csbh'] == $csbh){
												$price_sheet_id = $key;
											}
										}
									}
									if(!empty($price_sheet_id)){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(),
											//'user_id' => $core->_USER['user_id'],
											'price_sheets' => $price_sheets[$price_sheet_id]
										);
										// $clsISO->print_pre($logs); die();
										$price_sheets[$price_sheet_id]['sheets'] = $sheets;
										$price_sheets[$price_sheet_id]['upd_date'] = time();
										//$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
									} else {
										$price_sheet_id = $clsISO->getUniqid();
										$price_sheets[$price_sheet_id]['sheets'] = $sheets;
										$price_sheets[$price_sheet_id]['reg_date'] = time();
										$price_sheets[$price_sheet_id]['upd_date'] = time();
										$price_sheets[$price_sheet_id]['csbh'] = $csbh;
										//$price_sheets[$price_sheet_id]['user_id'] = $core->_USER['user_id'];
										//$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
									}
								}
								if($more_information['total_price_vat'] != $total_price_vat){
									$logs[$clsISO->getUniqid()] = array(
										'reg_date' => time(), 
										'user_id' => $core->_USER['user_id'],
										$field => $more_information['total_price_vat']
									);
								}
								$more_information['csbh']= $csbh;
								$more_information['status_id']= _STOCK_STATUS_LOCK_ID;
								$more_information['total_price_vat']= $total_price_vat;
								$more_information['price_sheets']= $price_sheets;
								// $clsISO->print_pre($more_information); die();
								if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
									'reg_date' => time(),
									'status_id' => _STOCK_STATUS_LOCK_ID,
									'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))){
									$total_updated += 1;
								}
							}
						}
					}
				}
			}
		}
	}
	// $clsISO->print_pre($list_agents); die();
	echo 'Crontab success';
	die();
?>