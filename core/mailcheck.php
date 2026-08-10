<?php 
	/*
	* PHP file mail check someone has opened
	*/
	error_reporting(E_ALL);
	ini_set("display_errors",0);
	if(!function_exists('vsprint_r')){
		function vsprint_r($doc){
			print('<pre>'.print_r($doc, true).'</pre>'); die();
		}
	}
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	require_once(ABSPATH.'/init.php');
	require_once(ABSPATH.'/lang/vn.php');
	require_once DIR_ADODB.'/adodb.inc.php';
	require_once DIR_COMMON."/clsDbBasic.php";
	#- Database handle
	$dbconn = ADONewConnection(DB_TYPE);
	if (isset($dbinfo) && is_array($dbinfo)) {
		$dbconn->Connect($dbinfo['host'], $dbinfo['user'], $dbinfo['pass'], $dbinfo['db']);
	} else {
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}
	#- Loader class/model
	if(function_exists('spl_autoload_register')){
		function autoload($class){
			if(file_exists(DIR_CLASSES.'/class_'.$class.'.php')){
				require_once(DIR_CLASSES.'/class_'.$class.'.php');
			} else if(file_exists(DIR_CLASSES.'/class.'.$class.'.php')){
				require_once(DIR_CLASSES.'/class.'.$class.'.php');
			} elseif(IS_ADMIN_PAGE==1 && file_exists(DIR_ADMIN_CLASSES.'/class_'.$class.'.php')){
				require_once(DIR_ADMIN_CLASSES.'/class_'.$class.'.php');
			}
		}
		spl_autoload_register('autoload');
	}
	#
	$_LANG_ID = 'vn';
	$clsISO = new ISO();
	$CFG = new Configuration();
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent();
	$clsCRMMassMailSentLog = new CRMMassMailSentLog();
	#
	$utm_source = isset($_GET['utm_source']) ? $_GET['utm_source'] : ''; 
	$utm_campaign = isset($_GET['utm_campaign']) ? trim($_GET['utm_campaign']) : "";
	if($utm_campaign == 'massmail'){
		$massmailsent_id = CRM::decryptID($utm_source);
		if($massmailsent_id){
			if($clsCRMMassMailSent->updateOne($massmailsent_id, array(
				'mark_read'	=> 1,
				'time_read'	=> time()
			))){
				$clsCRMMassMailSentLog->insert(array(
					'id'	=> $clsCRMMassMailSentLog->getMaxId(),
					'mass_mail_sent_id'	=> $massmailsent_id,
					'ip'	=> $_SERVER['REMOTE_ADDR'],
					'browser_info'	=> $_SERVER['HTTP_USER_AGENT'],
					'reg_date'	=> time(),
					'status'	=> 1
				));
			}
		}
	} else if($utm_campaign =='renew'){
		$client_email_log_id = CRM::decryptID($utm_source);
		if($client_email_log_id){
			$clsVS_ClientEmailLog = new VS_ClientEmailLog();
			//var_dump($clsVS_ClientEmailLog); die();
			$clsVS_ClientEmailLog->updateOne($client_email_log_id, array(
				'mark_read'	=> 1,
				'time_read'	=> time()
			), false);
		}
	}
	#
	header("Content-Type: image/jpeg");
	//Generate Image (Es. dimesion is 1x1)
	$newimage = imagecreate(1,1);
	$grigio = imagecolorallocate($newimage,255,255,255);
	imagejpeg($newimage);
	imagedestroy($newimage);
?>