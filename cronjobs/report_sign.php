<?php 
	ini_set('display_errors', '0');
	error_reporting(false);
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
	/** End ClassRequirement */
	$core = new Core();
	global $profile_id;
	$clsISO = new ISO();
	$clsBilling = new Billing();
	$clsBillingMeta = new BillingMeta();
	$clsProfile = new Profile();
	#
	$sign_date = date('d/m/Y');
	$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`
	,`agree_date`,`billing_source_id`,`billing_type`,`agree_status_id`,`more_information`,`admin_id`";
	$cond = "`is_trash`=0 AND `is_cancel`=0";	
	$sql_query = "SELECT {$field},'_new' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond} AND ((FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$sign_date}') OR (FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$sign_date}'))";
	$sql_union_query = "SELECT {$field},'_old' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond} AND EXISTS (SELECT 1 FROM {$clsBillingMeta->tbl} WHERE FROM_UNIXTIME(`meta_value`, '%d/%m/%Y')='{$sign_date}' AND {$clsBilling->tbl}.`billing_id`={$clsBillingMeta->tbl}.`billing_id`)";
	// $dbconn->debug = true;
	$list_billings = $dbconn->getAll("{$sql_query} UNION ALL {$sql_union_query}");
	// $clsISO->print_pre($list_billings); die();
	if(!empty($list_billings)){
		$total_billings = count($list_billings);
		$message = '📣 Hôm nay, ngày '.date('d/m/Y');
		$message.= "\n";
		$message.= 'Có [{total_success}] đã ký - [{total_unsigned}] chưa ký - [{total_cancel}] hủy lịch ký HĐMB/VBTT';
		$message.= "\n";
		$message.= "\n";
		$total_success = $total_cancel = $total_unsigned = 0;
		$arr_admin_cached = array(); $ii=0;
		foreach($list_billings as $key => $val){
			$sign_type = $val['sign_type'];
			$stock_code = $val['stock_code'];
			$admin_id = (int) $val['admin_id'];
			$agree_date = (int) $val['agree_date'];
			$contract_date = (int) $val['contract_date'];
			$agree_status_id = (int) $val['agree_status_id'];
			$billing_source_id = (int) $val['billing_source_id'];
			$contract_status_id = (int) $val['contract_status_id'];
			if($admin_id>0 && !isset($arr_admin_cached[$admin_id])){
				$arr_admin_cached[$admin_id] = $clsProfile->getFullName($admin_id);
			}
			$text_DQ = "[Quỹ chéo]";
			if($billing_source_id == _BILLING_RESOURCE_F1_ID){
				$text_DQ = "[Quỹ ĐQ]";
			}
			if($agree_date > 0 && $contract_date == 0){
				if($sign_type == '_new'){
					if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
						$total_success += 1;
						$stock_code .= $text_DQ. ' [Đã ký VBTT]';
					} else {
						$total_unsigned += 1;
						$stock_code .= $text_DQ. ' [Chưa ký VBTT]';
					}
				} else {
					$total_cancel += 1;
					$stock_code .= $text_DQ. ' [Hủy ký VBTT]';
				}
				$time =  $clsISO->formatTime($agree_date);
			} else if($contract_date > 0) {
				if($sign_type == '_new'){
					if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
						$total_success += 1;
						$stock_code .= $text_DQ. ' [Đã ký HĐMB]';
					} else {
						$total_unsigned += 1;
						$stock_code .= $text_DQ. ' [Chưa ký HĐMB]';
					}
				} else {
					$total_cancel += 1;
					$stock_code .= $text_DQ. ' [Hủy ký HĐMB]';
				}
				$time = $clsISO->formatTime($contract_date);
			}
			$message.= sprintf('%s - %s - %s - %s', $ii+1, $stock_code, $time, $arr_admin_cached[$admin_id]);
			$message.= "\n";
			++$ii;
		}
		$message = str_replace('{total_success}', $total_success, $message);
		$message = str_replace('{total_cancel}', $total_cancel, $message);
		$message = str_replace('{total_unsigned}', $total_unsigned, $message);
		// $clsISO->print_pre($message); die();
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
		));
		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' => $message,
			'group_id' => '5939742016694743644'
		)); // 5939742016694743644
	}
	die('Cronjob success !');
?>