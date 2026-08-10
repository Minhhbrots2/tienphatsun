<?php
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type, Authorization");
	#Required Init
	require_once('init.php');
	#Required Module File
	$arrModules = directory_map(dirname(__FILE__).DS.'modules','/\.php/');
	if(!empty($arrModules)){
		foreach ($arrModules as $module){
			if(stripos($module, 'lck')===false){
				require_once($module);
			}
		}
		unset($arrModules);
	}
	#Run App
	$app->run();
?>