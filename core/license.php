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
	
	$gen = md5($_SERVER['SERVER_ADDR'].'-LICENSE-'.DB_NAME);
	echo $gen; die();
?>