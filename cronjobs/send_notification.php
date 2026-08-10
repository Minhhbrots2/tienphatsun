<?php 
	ini_set('display_errors', '1');
	error_reporting(true);
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
	$clsISO = new ISO();
	$clsClassTable = new News();
	$clsNotify = new Notify();
	$clsEmailTemplate = new EmailTemplate();
	$clsProfile = new Profile();
	#
//	 $dbconn->debug = true;
	$cond = "`is_trash`='0' AND `is_online`='1' AND `post_type`='notification' AND (JSON_EXTRACT(`more_information`,\"$.send_notify\")='zalo' OR JSON_EXTRACT(`more_information`,\"$.send_notify\")='email')";
	$cond .= " AND `end_date` <= '".time()."'";
//	$clsClassTable->setDeBug(1);
	$lstItem = $clsClassTable->getAll($cond);
//	$clsISO->print_pre($lstItem); die();
	if(!empty($lstItem)){
		foreach ($lstItem as $key => $_oItem) {
			$more_information = $clsISO->to_array_json($_oItem["more_information"]);
			$send_notify = $more_information["send_notify"];
			$content = $_oItem["content"];
			$images = $clsISO->to_array_json($_oItem["images"]);
			$image = !empty($images[0]) ? $images[0] : "";
			
			if($send_notify == "email") {
				$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_NEWS);
				$subject = $clsEmailTemplate->getSubject(_MAIL_NEWS,$oneEmailTemplate);
				$message = $clsEmailTemplate->getContent(_MAIL_NEWS,$oneEmailTemplate);
				$fromEmail = $clsEmailTemplate->getFromEmail(_MAIL_NEWS);
				$fromName = $clsEmailTemplate->getFromName(_MAIL_NEWS);
//				$clsISO->print_pre($oneEmailTemplate);die;

				$html_image = "";
				if(!empty($image)) {
					$html_image .='<div class="imgs-grid-image" style="margin-bottom: 5px">
							<div class="image-wrap">
								<img src="'.DOMAIN_NAME.$image.'" alt="" title="" style="width:100%">
							</div>
						</div>';
				}
				$replace_fields = array(
					'{title}' => $_oItem["title"],
					'{content}' => $clsClassTable->formatHTML($_oItem["content"]),
					'{image}' => $html_image
				);
				foreach($replace_fields as $key => $val){
					$message = str_replace($key, $val, $message);
				}
				$subject = "Future Homes thông báo";
				// Send Email
				$lstProfile = $clsProfile->getAll("`status_id` > 0 AND `status_id` <> '"._STATUS_STAFF_OFF_ID."' AND `is_trash` = '0' AND `email` <> '' AND `full_name` <> '' AND `profile_id`='289'","email,full_name");
				//test
				$lstProfile[] = ["email"=> "anhtruongcnttb@gmail.com","full_name"=>"TVT"];
//				$clsISO->print_pre($lstProfile);die;
				if($clsISO->sendEmailSystemMulti($fromEmail,$fromName,$lstProfile, $subject, $message)) {
					$clsClassTable->updateOne($_oItem[$clsClassTable->pkey],["is_online"=>0]);
				}
				
				//send mail
			}elseif($send_notify == "zalo") {
				//$group_zalo_id = _FH_GROUP_ZALO_ID;
				$group_zalo_id = "8388131316320784986";
				$content = html_entity_decode(strip_tags($content));
				$content = str_replace("\r\n","\n",$content);
				$image = FH_URL.$image;
				
				if(!empty($content)) {	
					if($clsNotify->sendZaloContent($content,$group_zalo_id)) {
						if(!empty($image)) {
							$clsNotify->sendZaloImage($image,$group_zalo_id);
						}	
						$clsClassTable->updateOne($_oItem[$clsClassTable->pkey],["is_online"=>0]);
					}
				}
			}
			
		}
	}
	die('Cronjob success !');
?>