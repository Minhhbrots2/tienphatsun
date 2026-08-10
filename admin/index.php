<?php
	error_reporting(false);
	ini_set('display_errors', 0);
	if (version_compare(phpversion(), '5.4.0', '<')) {
		if(session_id() == '') {
			session_start();
		}
	} else {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}
	define('IS_ADMIN_PAGE', 1);
	define("_SITE_ROOT", 'ADMIN');
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH', dirname(__FILE__));
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	
	/** Required Config */
	require(ROOTPATH.DS.'setting.php');
	require(ROOTPATH.DS.'init.php');
	require_once(DIR_INCLUDES."/tinymce_config.php");
	/** Required Module */
	if (file_exists(DIR_INCLUDES."/index.php")){
		require_once(DIR_INCLUDES."/index.php");
	}else{
		echo('MaxxCMS not found!');die();
	}
?>