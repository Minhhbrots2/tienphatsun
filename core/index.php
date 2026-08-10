<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
/*Kernel of system*/
require_once(DIR_INCLUDES."/core.php");	
/** =====================================================================* 
INITIATION SECTION  
* =====================================================================*/ 
$mod = $stdio->GET("mod" ,"home");
$act = $stdio->GET("act" ,"default");
$core = new Core();
/** =====================================================================* 
CONTROL SECTION  
* =====================================================================*/
if($core->isAjax()){
	require_once(DIR_APPLICATION."/_header.php");
	if(file_exists(DIR_MODULES."/$mod/index.php")){
		require_once(DIR_MODULES."/$mod/index.php");
	}else{
		$html = htmlNotFound;
		echo($html);die();
	}
	require_once(DIR_APPLICATION."/_footer.php");
	/*Display template*/
	$smarty->assign('mod', $mod);
	$smarty->assign('act', $act);
	$smarty->assign('core', $core);
} else {
	require_once(DIR_APPLICATION."/_header.php");
	if(file_exists(DIR_MODULES."/$mod/index.php")){
		require_once(DIR_MODULES."/$mod/index.php");
	}else{
		$html = htmlNotFound;
		echo($html); die();
	}
	require_once(DIR_APPLICATION."/_footer.php");
	/*Display template*/
	$assign_list["mod"] = $mod; 
	$assign_list["act"] = $act; 
	$assign_list["core"] = $core;
	$smarty->assign($assign_list);
	$cache_id = md5($_SERVER['REQUEST_URI'].ISOCMS_THEMES); 
	if(isset($_GET['clearCache'])){
		$clearCache = md5($_GET['clearCache']);
		$smarty->clear_cache(DIR_VIEWS."/index.tpl",$clearCache);
	}
	if(isset($_GET['clearAllCache'])){
		$smarty->clearAllCache();
	}
	/*Display template*/
	$smarty->cache_lifetime = cache_lifetime;
	$smarty->display(DIR_VIEWS."/index.tpl",$cache_id);
}
?>