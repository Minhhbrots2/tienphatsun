<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 MaxxCMS Techical Team (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxxCMS Team.            # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
	#- Require config.
	if(@file_exists($config_path = ROOTPATH.DS.'config.php')){
		require_once($config_path);
	} else {
		echo(htmlNotFound); die();
	}
	#- Compress and encode
	if(GZIP_GLOBAL){
		if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')){
			ob_start("ob_gzhandler");
		}else{
			ob_start();
		}
	}
	if(COMPRESS_GLOBAL){
		function compress($buffer){
			$search = array('/\n/','/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
			$replace = array(' ','>','<','\\1'  );
			$buffer = preg_replace($search, $replace, $buffer);
			// $buffer = str_replace(' Bang DQ','Bang DQ',$buffer);
			// $buffer = str_replace('%20Bang%20DQ','Bang%20DQ',$buffer);
			return $buffer;
		}
		//ob_start("compress");
	}
	#- Definition constants global
	define("HANDLE_ERROR", 0);
	define("PCMS_DIR", ABSPATH);
	define("DIR_INCLUDES", ROOTPATH."/core");
	define("DIR_COMMON", DIR_INCLUDES."/system");
	define("DIR_CONFIGS", ABSPATH."/configs");
	if(!IS_ADMIN_PAGE){
		define("PCMS_URL", _ISOCMS_PROTOCOL.$_SERVER['HTTP_HOST']);
	} else {
		define("PCMS_URL", _ISOCMS_PROTOCOL.$_SERVER['HTTP_HOST'].DS.'admin');
	}
	define("DIR_LOGS", ROOTPATH.'/logs');
	define("LOG_SYSTEM_FILE", DIR_LOGS.'/system.log');
	define("DIR_APPLICATION", ABSPATH."/application");
	define("URL_APPLICATION", PCMS_URL."/application");
	define("URL_THEMES", URL_APPLICATION."/themes");
	define("DIR_VIEWS", DIR_APPLICATION."/views");
	define("URL_VIEWS", URL_APPLICATION."/views"); 
	define("DIR_LIB", DIR_INCLUDES."/libraries");
	define("DIR_MODULES", DIR_APPLICATION."/modules");
	define("DIR_MODELS", ROOTPATH."/models");
	define("DIR_ADODB", DIR_INCLUDES."/adodb5");
	define("DIR_SMARTY", DIR_INCLUDES."/smarty");
	define("DIR_LANG", PCMS_DIR."/lang");
	if(!defined('LANG_DEFAULT')) 
		define("LANG_DEFAULT",'vn');
	if(!defined('LANG_ADMIN_DEFAULT')) 
		define("LANG_ADMIN_DEFAULT",'vn');
	
	#- Session
	$SESSION_NAME = "ISOCMS_SESSION";
	$SESSION_PATH = "/tmp";
	$SESSION_COOKIE = 1; //1: user cookie, 0: no cookie
	$SESSION_TIME_OUT = time() + 5*24*3600;
	require_once DIR_COMMON."/vnSession.php";
	require_once DIR_COMMON."/IniManager.php";
	#Setup session
	if (!vnSessionSetup()) {
		trigger_error('Session setup failed', E_USER_ERROR);
		exit();
	}
	#- Cookie Start
	require_once DIR_COMMON."/Common.php";
	require_once DIR_COMMON."/Cookie.php";
	require_once DIR_INCLUDES."/FirebaseJWT/JWT.php";
	#Setup cookie
	$COOKIE_NAME = 'ISOCMS_COOKIES';
	$COOKIE_TIME_OUT  = time() + COOKIE_EXPIRES;
	$clsCookie = new VnCookie($COOKIE_NAME, $COOKIE_TIME_OUT);
	$clsCookie->extractAll($COOKIE_NAME);
	#- Stdio
	require_once DIR_COMMON."/Stdio.php"; 	
	$stdio = new Stdio();
	$_GET = $stdio->parse_incoming(true);
	//$_POST = $stdio->parse_incoming(false); 
?>