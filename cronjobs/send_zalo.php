<?php 
	error_reporting(false);
	ini_set('display_errors', 0);
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	
	define('IS_ADMIN_PAGE', 0);
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
	$clsISO = new ISO();
	$clsProfile = new Profile();
	$clsZaloMessage = new ZaloMessage();
	#
	$list_msg = $clsZaloMessage->getAll("`is_active`='1' AND `schedule_type`='_repeat' AND `next_run_at`<='".time()."'");
	if(!empty($list_msg)){
		foreach($list_msg as $oneMsg){
			$msg_id = $oneMsg[$clsZaloMessage->pkey];
			$send_type = $oneMsg['send_type'];
			$more_information = $oneMsg['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$message = !empty($more_information['message']) ? trim($more_information['message']) : "";
			$next_run_at = $clsZaloMessage->calcNextRunAt(
				$oneMsg['next_run_at'], 
				$more_information['time_start'], 
				$more_information['time_end'], 
				$more_information['repeat_interval'], 
				$more_information['repeat_unit']
			);
			$clsZaloMessage->updateOne($msg_id, array(
				'last_run_at' => time(),
				'next_run_at' => $next_run_at
			));
			if($send_type == 'send_group'){
				$images = !empty($more_information['images']) ? $more_information['images'] : array(); 
				$arr_groups = !empty($more_information['list_group_id']) ? $more_information['list_group_id'] : array();
				if(!empty($arr_groups) && !empty($message)){
					foreach($arr_groups as $id_group){
						$curl = new \Curl\Curl();
						$curl->setHeaders(array(
							'Content-Type' => 'application/json',
							'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
						));
						$curl->post('https://public-api.func.vn/functions/68011b1db49c8ee7d3eac010', array(
							'message' => $message,
							'group_id' => $id_group
						));
						if(!empty($images)){
							$images_2 = array_map(function($img) {
								$img = str_replace(PCMS_URL, '', $img);
								$img = str_replace(DOMAIN_URL, '', $img);
								return PCMS_URL . $img; 
							}, $images);
							$total_images = @count($images_2);
							if($total_images == 1){
								$curl = new \Curl\Curl();
								$curl->setHeaders(array(
									'Content-Type' => 'application/json',
									'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_IMAGE_API_KEY)
								));
								$curl->post('https://public-api.func.vn/functions/6846ab85edb876b1e5d40921', array(
									"url" => $images_2[0],
									"desc" => "",
									"groupLayoutId" => 0,
									"group_id" => $id_group
								));
							} else {
								$curl = new \Curl\Curl();
								$curl->setHeaders(array(
									'Content-Type' => 'application/json',
									'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_IMAGES_API_KEY)
								));
								$curl->post('https://public-api.func.vn/functions/6846b624edb876b1e5d42a75', array(
									"urls" => $images_2,
									"group_id" => $id_group
								));
							}
						}
					}
				}
			} else {
				
			}
		}
	}
	die('Cronjob success !');
?>