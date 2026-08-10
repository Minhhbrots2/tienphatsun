<?php
	/* Cron: rollup nhật ký truy cập -> default_stat_active_daily (tập active-user theo ngày).
	   Incremental theo cursor log_id lưu trong default_configuration (analytics_log_cursor).
	   Idempotent (INSERT IGNORE). Lịch chạy gần realtime (vd mỗi 10-15') qua HTTP GET.
	   DAU/WAU/MAU/retention được derive lúc đọc từ bảng này. */
	error_reporting(false);
	ini_set('display_errors',0);
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
	define("SMARTY_DEBUG", 	false);
	define("COMPILE_CHECK", true);
	define("ADODB_DEBUG", 	false);
	define("STOP_APP_IF_ERROR", 1);
	/** DriverDatabase */
	require_once(DIR_ADODB."/adodb.inc.php");
	$dbconn =& ADONewConnection(DB_TYPE);
	$dbconn->debug = ADODB_DEBUG;
	$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$dbconn->Execute("SET time_zone='+7:00'");
	/** Core Requirement */
	require_once DIR_COMMON."/DbBasic.php";
	require_once DIR_COMMON."/App.php";
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

	/* ===================== Rollup logic ===================== */
	$pre = DB_PREFIX;
	$clsConfig = new Configuration();
	$cursor = (int) $clsConfig->getValue('analytics_log_cursor', 0);
	$maxId  = (int) $dbconn->GetOne("SELECT MAX(log_id) FROM {$pre}log");
	$inserted = 0;
	if($maxId > $cursor){
		$dbconn->Execute("INSERT IGNORE INTO {$pre}stat_active_daily (d, user_id)
			SELECT DATE(FROM_UNIXTIME(reg_date)), user_id
			FROM {$pre}log
			WHERE log_id > {$cursor} AND log_id <= {$maxId}
			  AND from_site='_user' AND user_id > 0");
		$inserted = (int) $dbconn->Affected_Rows();
		$clsConfig->updateValue('analytics_log_cursor', $maxId);
	}
	// heartbeat để giám sát cron treo (so last_run với hiện tại)
	$clsConfig->updateValue('analytics_rollup_last_run', date('Y-m-d H:i:s')." cursor={$maxId} +{$inserted}");
	echo "stat_rollup OK | cursor {$cursor} -> {$maxId} | active rows +{$inserted}\n";
?>