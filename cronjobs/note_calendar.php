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
	$offset="+7:00";
	$dbconn->Execute("SET time_zone='".$offset."';");

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

	###
	$clsISO = new ISO();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsCalendar = new Calendar();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();

	$date = date('d/m/Y');
	$total_billings = $total_registered = $total_unregisted = 0;
	$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`,`agree_date`,`billing_source_id`,`billing_type`,`contract_status_id`";
	$cond = "`is_trash`=0 and `is_cancel`=0 and (
		FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$date}') ";
	$list_billings = $clsBilling->getAll($sql_string.$cond, $field);
	$arr_admin = [];
	$data = $ar_cache_billing_type = array();
	foreach ($list_billings as $key => $val) {
		$oBilling = $val;
		$text_type = "";
		if(date("d/m/Y",$val['contract_date']) == $date) {
			$text_type .=  (($text_type != "") ? ", " :"") . "HĐMB";
		}
		if(date('d/m/Y',$val['agree_date']) == $date){
			$text_type .=  (($text_type != "") ? ", " :"") . "VBTT";
		}
		$oBilling["type"] = $text_type;
		if(!isset($ar_cache_billing_type[$val['billing_type']])) {
			$lstAdminGDDA = $clsProfile->getAll("`profile_id`='"._PROFILE_LTD_ID."' OR (`is_trash`='0' AND `status_id` <> '"._STATUS_STAFF_OFF_ID."' AND JSON_SEARCH(JSON_UNQUOTE(JSON_EXTRACT(more_information, '$.permiss_billing')), 'one', '".$val['billing_type']."') IS NOT NULL)",$clsProfile->pkey.',full_name,avatar,email,role_id');
			foreach ($lstAdminGDDA as $k_ad => $v_ad) {
				if($v_ad['role_id'] == _ROLE_STAFF_ADMIN){
					$arr_admin[$val['billing_type']][] = $v_ad['full_name'];
				}
			}
			$ar_cache_billing_type[$val['billing_type']]['lstAdminGDDA'] = $lstAdminGDDA;
		}
		$oBilling["admin_name"] = (!empty($arr_admin[$val['billing_type']])) ? implode(",",$arr_admin[$val['billing_type']]) : "--";
		if(!empty($ar_cache_billing_type[$val['billing_type']]['lstAdminGDDA'])) {
			foreach ($ar_cache_billing_type[$val['billing_type']]['lstAdminGDDA'] as $k_ad => $v_ad) {
				
				$data[$v_ad['profile_id']]["email"] = $v_ad["email"];
				$data[$v_ad['profile_id']]["full_name"] = $v_ad["full_name"];
				$data[$v_ad['profile_id']]["billings"][] = $oBilling;
				$data[$v_ad['profile_id']]["stock_code"][] = $val["stock_code"];
			}
		}
		
				
	}
	foreach ($data as $key => $value) {
		$total = !empty($value['billings']) ? count($value['billings']) : 0;
		$titleNoty = sprintf('Hôm nay có <strong>%s</strong> lịch ký HĐMB, VBTT căn <strong>%s</strong>', $total, implode(", ",$value['stock_code']));
		
		$clsNotify->insertNotify('Calendar',$clsCalendar->pkey, "0", $titleNoty, time(), [$key]);
		/** Gửi thông báo tới người gửi yêu cầu */
		$subscribers = array();
		$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id` = '".$key."' and `token`<>''", "token");	
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if(!in_array($val['token'], $subscribers)){
					$subscribers[] = $val['token'];
				}
			}
			$clsNotify->send_subscriber_notification(array(
				'title' => "Lịch ký HĐMB, VBTT",
				'message' => strip_tags($titleNoty),
				'url' => DOMAIN_URL . "/lich-ky.html"
			), $subscribers);
		}
		//gửi email
		$clsNotify->sendEmailCalendar($key,$value);
	}

	// End

	echo 'Run success'; die();

?>