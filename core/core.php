<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
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
/** Debugging */
define("SMARTY_DEBUG", 	false);//debug or not
define("COMPILE_CHECK", true);//compile check
define("ADODB_DEBUG", 	false);//debug or not
define("STOP_APP_IF_ERROR", 1);//stop if error happen 0: no, 1: yes
/** Private Directory */
function parseURL($url){
	$url = str_replace(DOMAIN_URL,'',$url);
	return $url;
}
define("DIR_TEMPLATES_C", 	PCMS_DIR."/tmp");
define("DIR_IMAGES",		DIR_VIEWS."/skin/images");
define("DIR_CSS",			DIR_VIEWS."/skin/css");
define("DIR_JS",			DIR_VIEWS."/skin/js");
#Private Url
define("URL_THEMES",		parseURL(URL_APPLICATION)."/themes");
define("URL_IMAGES",		URL_THEMES."/images");
define("URL_CSS",			URL_THEMES."/css");
define("URL_JS",			URL_THEMES."/js");
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
/** Site Name */
$tmp = select_query(DB_PREFIX.'configuration',"*","`setting`='site_name'","",0,1);
$PAGE_NAME = !empty($tmp) ? $tmp[0]['value'] : _HTTP_HOST;
define("PAGE_NAME", $PAGE_NAME); unset($tmp);
/** Core Requirement */
require_once DIR_COMMON."/DB.php";
require_once DIR_COMMON."/DbBasic.php";
require_once DIR_COMMON."/DbBasicMF.php";
require_once DIR_COMMON."/App.php";
require_once DIR_COMMON."/Core.php";
require_once DIR_COMMON."/Module.php";
// require_once DIR_COMMON."/Download.php";
require_once DIR_COMMON."/Response.php";
require_once DIR_COMMON."/Upload.php";
require_once DIR_COMMON."/Config.php";
require_once DIR_INCLUDES."/curl/vendor/autoload.php";
require_once DIR_INCLUDES."/carbon/vendor/autoload.php";
require_once DIR_INCLUDES . '/php-simple-redis-cache/vendor/autoload.php';
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
/** End  Library Requirement */
if(IS_ADMIN_PAGE){
	require_once DIR_COMMON."/Paging.php";
	require_once DIR_COMMON."/DataSource.php";
	require_once DIR_COMMON."/DataGrid.php";
	$DIR_CLASSES_ADMIN = PCMS_DIR.'/models';
	if (is_dir($DIR_CLASSES_ADMIN)){
		$customClsArray = array();
		if ($dh = opendir($DIR_CLASSES_ADMIN)) {
			while (($file = readdir($dh)) !== false) {
				if (substr($file, -3)=='php')
				array_push($customClsArray, $file);
			}
			closedir($dh);
		}	
		if(!empty($customClsArray)){
			foreach ($customClsArray as $customCls){
				require_once($DIR_CLASSES_ADMIN."/".$customCls);
			}
		}
	}
}

// Tự động load toàn bộ file .php trong thư mục hooks/
require_once(DIR_COMMON . DS . "Hook.php");
$hook_dir = PCMS_DIR . DS . 'hooks';
foreach (glob($hook_dir . '/*.php') as $hookFile) {
    require_once $hookFile;
}

/** TemplateRequirement */
require_once(DIR_SMARTY."/Smarty.class.php");
$smarty = new Smarty;
if(IS_ADMIN_PAGE==1){
	$smarty->caching = CACHING;
	//Load Language
	$_LANG_ID = LANG_ADMIN_DEFAULT;
	if(isset($_GET["lang"]) && !empty($_GET['lang'])){
		$_LANG_ID = trim($_GET["lang"]);
		vnSessionSetVar("ADMINPAGE_LANG", $_LANG_ID);
	} elseif (vnSessionExist("ADMINPAGE_LANG")){
		$_LANG_ID = vnSessionGetVar("ADMINPAGE_LANG");
	}
	$smarty->assign("_LANG_ID", $_LANG_ID);
} else {
	$smarty->caching = 0;
	$_LANG_ID = LANG_DEFAULT;
	if(isset($_GET["lang"])){
		$_LANG_ID = trim($_GET["lang"]);
		vnSessionSetVar("_LANG_ID", $_LANG_ID);
	} elseif (vnSessionExist("_LANG_ID")){
		$_LANG_ID = vnSessionGetVar("_LANG_ID");
	}
	$smarty->assign("_LANG_ID", $_LANG_ID);
}
if (LANG_LOAD==1 && file_exists(DIR_LANG."/".$_LANG_ID.".php")){
	require_once(DIR_LANG."/".$_LANG_ID.".php");
} 
$smarty->config_overwrite = true;
$smarty->debugging = SMARTY_DEBUG;
$smarty->compile_check = COMPILE_CHECK;
$smarty->template_dir = DIR_TEMPLATES;
$smarty->compile_dir = DIR_TEMPLATES_C;
$smarty->config_dir = DIR_INCLUDES."/conf";
$smarty->assign('URL_VIEWS', URL_VIEWS);
function select_query($tbl,$field,$cond,$orderby,$start,$limit){
	global $dbconn;
	$where = ($cond!="")? " WHERE $cond" : "";
	$orderby = ($orderby!="")? "ORDER BY $orderby" : "";
	$limit = ($limit!="")? "LIMIT $start, $limit" : "";
	$field_list = $field!=""?$field:"*";
	$sql = "SELECT ".$field_list." FROM ".$tbl." $where $orderby $limit";
	$rs = $dbconn->getAll($sql);
	return $rs;	
}
function update_query($tbl,$cond,$set){
	global $dbconn;
	$sql = "UPDATE ".$this->tbl." SET {$set} WHERE {$cond}";
	if (!$dbconn->Execute($sql)){
		trigger_error("Cannot run SQL: `$sql`", E_USER_ERROR);
		return 0;
	}
	return 1;	
}
function update_one_query($tbl,$pkey,$pval,$set){
	global $dbconn;
	$sql = "UPDATE ".$this->tbl." SET $set WHERE $pkey='$pval'";
	if (!$dbconn->Execute($sql)){
		trigger_error("Cannot run SQL: `$sql`", E_USER_ERROR);
		return 0;
	}
	return 1;	
}
?>