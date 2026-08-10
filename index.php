<?php
	// E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT
	error_reporting(true);
	ini_set('display_errors', 0);
	// ini_set('post_max_size','200M');
	define('IS_ADMIN_PAGE', 0);
	define("_SITE_ROOT", 'FRONTPAGE');
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH', dirname(__FILE__));
	define('ROOTPATH', dirname(__FILE__));
	/** Required Config */
	require(ABSPATH.DS.'init.php');
	require(ABSPATH.DS.'setting.php');
	/** Required Module */
	if (file_exists(DIR_INCLUDES."/index.php")){
		require_once(DIR_INCLUDES."/index.php");
	}else{
		echo('ISOCMS not found!');die();
	}
?> 