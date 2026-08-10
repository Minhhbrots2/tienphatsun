<?php 
	error_reporting(E_ALL);
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
	$dbconn->Execute("SET time_zone='{$offset}';");
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
	$clsISO = new ISO();
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$fromemail = 'info@futurehomes.vn';
	$fromname = 'Hệ thống quản lý Cronjob';
	$toemail = 'vanthiembui.it@gmail.com';
	$toname = 'Bùi Văn Thiêm';
	$subject = '[Thông báo] Cronjob đã chạy hoàn tất';
	$message = 'Xin chào,<br /><br />
	Hệ thống vừa thực thi cronjob vào lúc '.date('d/m/Y H:i:s').'<br />
	Nếu có bất kỳ lỗi nào, vui lòng kiểm tra hệ thống để xử lý kịp thời.<br /><br />
	Trân trọng,<br />
	Hệ thống quản lý Cronjob';
	$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);
	// echo 'Stop Run'; die();
	$field = "{$clsProfile->pkey},`role_id`,`department_id`,`contract_date`
		,`start_date`,`total_Lpoint`,`full_name`,`level_id`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`=1 and `status_id`='"._STATUS_STAFF_ON_ID."' 
	and `department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'", $field);
	$last_day = strtotime(sprintf('28-%s-%s', date('m'), date('Y')));
	// $clsISO->print_pre($last_day); die();
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$clsFPoint->insert_LPoint_seniority($staff_id, $val, $last_day);
		}
	}
	// End
	echo 'Run success'; die();
?>