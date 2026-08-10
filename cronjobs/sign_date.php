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
	$clsProfile = new Profile();
	
	$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`agree_date`,`admin_id`,`billing_source_id`";
	$field.= ",IF(contract_date>0, contract_date,agree_date) AS `action_date`";
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."' AND (FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='".date('d/m/Y')."' OR FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='".date('d/m/Y')."') ORDER BY `action_date` ASC";
	// $dbconn->debug = true;
	$list_billings = $clsBilling->getAll($cond, $field);
	// $clsISO->print_pre($list_billings); die();
	if(!empty($list_billings)){
		$total_billings = count($list_billings);
		$message = '📣 Hôm nay, ngày '.date('d/m/Y').'';
		$message.= "\n";
		$message.= 'Có '.$total_billings.' lịch ký ({total_DQ} độc quyền & {total_not_DQ} quỹ chéo) HĐMB/VBTT';
		$message.= "\n\n";
		$arr_admin_cached = array();
		$total_DQ = $total_not_DQ = $ii = 0;
		foreach($list_billings as $key => $val){
			$stock_code = $val['stock_code'];
			$admin_id = (int) $val['admin_id'];
			$billing_source_id = (int) $val['billing_source_id'];
			$agree_date = (int) $val['agree_date'];
			$contract_date = (int) $val['contract_date'];
			if($admin_id>0 && !isset($arr_admin_cached[$admin_id])){
				$arr_admin_cached[$admin_id] = $clsProfile->getFullName($admin_id);
			}
			if($agree_date > 0 && $contract_date == 0){
				$stock_code .= ' [VBTT]';
				$time =  $clsISO->formatTime($agree_date);
			} else if($contract_date > 0) {
				$stock_code .= ' [HĐMB]';
				$time = $clsISO->formatTime($contract_date);
			}
			$text_DQ = "[Quỹ Chéo]";
			if($billing_source_id == _BILLING_RESOURCE_F1_ID){
				$total_DQ += 1;
				$text_DQ = "[Quỹ ĐQ]";
			} else {
				$total_not_DQ += 1;
			}
			$message.= sprintf('%s - %s%s - %s - %s', $ii+1,  $stock_code, $text_DQ, $time, $arr_admin_cached[$admin_id]);
			$message.= "\n";
			++$ii;
		}
		$message = str_replace('{total_DQ}', $total_DQ, $message);
		$message = str_replace('{total_not_DQ}', $total_not_DQ, $message);
		$clsISO->print_pre($message); die();
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
		));
		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' => preg_replace('/\n/','', $message),
			'group_id' => '5939742016694743644'
		));
	}
	// Return
	die('Cron success');
?>