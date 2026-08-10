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
	$clsTransaction = new Transaction();
	$clsTransactionItem = new TransactionItem();
	
	$spreadsheetId = '1dSaqZtloYykx2dXQR6D72wxhc0cnApuuVpQcUl3Q-co';
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
	$response = $service->spreadsheets_values->get($spreadsheetId, ["2026 CS"], array(
		'valueRenderOption' => 'FORMATTED_VALUE'
	));
	$tblData = $response->getValues();
	if(!empty($tblData)){
		$total_records = count($tblData);
		for($i=18; $i < $total_records; $i++){
			$transaction_date = $tblData[$i][2];
			$unit_code = $tblData[$i][4];
			$unit_type = $tblData[$i][5];
			$project_name = $tblData[$i][6];
			$deal_type = $tblData[$i][7];
			$agent_name = $tblData[$i][8];
			$status = $tblData[$i][9];
			$sale_name = $tblData[$i][22];
			
			$contract_value = $tblData[$i][11];
			$commission_base = $tblData[$i][12];
			$commission_rate = $tblData[$i][23];
			
			$in_sale_bonus = $tblData[$i][14];
			$in_agent_bonus = $tblData[$i][15];
			$in_support = $tblData[$i][16];
			$in_deduction = $tblData[$i][17];
			$in_matched_payment = $tblData[$i][18]; // Theo đối chiếu
			$in_total_amount = $tblData[$i][19]; // Tổng nhận
			$in_advance_payment = $tblData[$i][20];
			$in_reconciled_amount = $tblData[$i][21]; // Con được đối chiếu
			
			$out_sale_payout_rate = $tblData[$i][23];
			$out_sale_bonus = $tblData[$i][24];// Thưởng Sale
			$out_agent_bonus = $tblData[$i][25]; // Thưởng đại lý
			$out_deduction = $tblData[$i][26]; // Gỉam trừ
			$out_matched_payment = $tblData[$i][27]; // Trả theo đối chiếu
			$out_total_payment = $tblData[$i][28]; // Tổng trả
			$out_advance_payment = $tblData[$i][29]; // Đã tạm ứng
			$out_remaining_payment = $tblData[$i][30]; // Còn phải trả
			$hold_amount = $tblData[$i][31]; //  Giữ lại
			
			if(!empty($transaction_date) && !empty($unit_code)){
				$tmp = $clsTransaction->getByCond("`unit_code`='{$unit_code}'");
				if(!empty($tmp)){
					$dbconn->StartTrans();
					$extra_data = $tmp['extra_data'];
					$extra_data = $clsISO->to_array_json($extra_data);
					$list = $clsTransactionItem->getAll("transaction_id='{$tmp[$clsTransaction->pkey]}'");
					if($clsTransaction->updateOne($tmp[$clsTransaction->pkey], array(
						'unit_code' => $unit_code,
						'unit_type' => $unit_type,
						'project_name' => $project_name,
						'agent_name' => $agent_name,
						'sale_name' => $sale_name,
						'deal_type' => $clsISO->slugify($deal_type),
						'status' => $clsISO->slugify($status),
						'transaction_date' => $clsISO->convertTimeToText($transaction_date),
						'contract_value' => $clsISO->processSmartNumber($contract_value),
						'commission_base' => $clsISO->processSmartNumber($commission_base),
						'commission_rate' => $clsISO->convertToNumber($commission_rate),
						'extra_data' => json_encode($extra_data, JSON_UNESCAPED_UNICODE),
						'upd_date' => time()
					))){
						$in_rows = array(
							'in_sale_bonus', 'in_agent_bonus', 'in_support', 'in_deduction', 
							'in_matched_payment', 'in_total_amount', 'in_advance_payment', 'in_reconciled_amount'
						);
						foreach($in_rows as $line_type){
							if(!empty($list)){
								foreach($list as $oval){
									if($oval['line_type'] == $line_type){
										$clsTransactionItem->updateOne($oval[$clsTransactionItem->pkey], array(
											'amount' => $clsISO->processSmartNumber(${$line_type})
										));
									}
								}
							} else {
								$clsTransactionItem->insert(array(
									'flow_type' => 'in',
									'line_type' => $line_type,
									'transaction_id' => $tmp[$clsTransaction->pkey],
									'amount' => $clsISO->processSmartNumber(${$line_type}),
									'reg_date' => time()
								));
							}
						}
						$out_rows = array(
							'out_sale_payout_rate', 'out_sale_bonus', 'out_agent_bonus', 'out_deduction', 'out_matched_payment', 
							'out_total_payment', 'out_advance_payment', 'out_remaining_payment', 'hold_amount'
						);
						foreach($out_rows as $line_type){
							if(!empty($list)){
								foreach($list as $oval){
									if($oval['line_type'] == $line_type){
										$clsTransactionItem->updateOne($oval[$clsTransactionItem->pkey], array(
											'amount' => $clsISO->processSmartNumber(${$line_type})
										));
									}
								}
							} else {
								$clsTransactionItem->insert(array(
									'flow_type' => 'out',
									'line_type' => $line_type,
									'transaction_id' => $tmp[$clsTransaction->pkey],
									'amount' => $clsISO->processSmartNumber(${$line_type}),
									'reg_date' => time()
								));
							}
						}
					}
					$dbconn->CompleteTrans();
				} else {
					$dbconn->StartTrans();
					// 1. insert deal
					$transaction_id = $clsTransaction->getMaxId();
					$extra_data = array();
					$clsTransaction->insert(array(
						$clsTransaction->pkey => $transaction_id,
						'unit_code' => $unit_code,
						'unit_type' => $unit_type,
						'project_name' => $project_name,
						'agent_name' => $agent_name,
						'sale_name' => $sale_name,
						'deal_type' => $clsISO->slugify($deal_type),
						'status' => $clsISO->slugify($status),
						'transaction_date' => $clsISO->convertTimeToText($transaction_date),
						'contract_value' => $clsISO->processSmartNumber($contract_value),
						'commission_base' => $clsISO->processSmartNumber($commission_base),
						'commission_rate' => $clsISO->convertToNumber($commission_rate),
						'extra_data' => json_encode($extra_data, JSON_UNESCAPED_UNICODE),
						'reg_date' => time(),
						'upd_date' => time()
					));
					$rows = array();
					foreach(['in_sale_bonus', 'in_agent_bonus', 'in_support', 'in_deduction', 
						'in_matched_payment', 'in_total_amount', 'in_advance_payment', 'in_reconciled_amount'] as $line_type){
						$rows[] = array(
							'flow_type' => 'in',
							'line_type' => $line_type,
							'transaction_id' => $transaction_id,
							'amount' => $clsISO->processSmartNumber(${$line_type}),
							'reg_date' => time()
						);
					}
					foreach(['out_sale_payout_rate', 'out_sale_bonus', 'out_agent_bonus', 'out_deduction', 'out_matched_payment', 
						'out_total_payment', 'out_advance_payment', 'out_remaining_payment', 'hold_amount'
					] as $line_type){
						$rows[] = array(
							'flow_type' => 'out',
							'line_type' => $line_type,
							'transaction_id' => $transaction_id,
							'amount' => $clsISO->processSmartNumber(${$line_type}),
							'reg_date' => time()
						);
					}
					// 2. insert items
					$clsTransactionItem->insertBatch($rows);
					$dbconn->CompleteTrans();
				}
			}
		}
	}
	// Output
	die('Run success');
?>