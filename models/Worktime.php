<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Worktime extends dbBasic{
	function __construct(){
		$this->pkey = "worktime_id";
		$this->tbl = DB_PREFIX."worktime";
	}
	function getIdByEmail($email){
		$res = $this->getAll("email='$email'");
		return $res[0]['subscribe_id'];
	}
	function checkValidEmail($email, $for_id=0, $type="SUBSCRIBE" ){
		$cond = "email='{$email}'";
		if(!empty($type)) $cond.= " and type='{$type}'";
		if((int) $for_id > 0) $cond.= " and for_id='{$for_id}'";
		return $this->countItem($cond) ? 1: 0;
	}
	function checkEmailExist($email, $for_id, $type){
		$res = $this->getAll("email='$email' and for_id='$for_id' and type='$type'", $this->pkey);
		return is_array($res) && count($res)>0 ? 1 : 0;
	}
	function getName($subscribe_id){
		$res = $this->getOne($subscribe_id);
		return $res['full_name'];
	}
	function getEmail($subscribe_id){
		$res = $this->getOne($subscribe_id);
		return $res['email'];
	}
	function sendMail($subscribe_id){
		
		global $core,$oneCommon,$clsISO,$smarty,$extLang,$clsPage,$clsConfiguration;
		header('Content-Type: text/html; charset=utf-8');
		$clsEmailTemplate = new EmailTemplate();
		$message = $clsEmailTemplate->getContent(3);
		#
		$email_template_id = 3;
		$email_logo_url = $clsEmailTemplate->getLogoUrl($email_template_id);
		$width_logo = $clsEmailTemplate->getLogoWidth($email_template_id);
		$height_logo = $clsEmailTemplate->getLogoHeight($email_template_id);

		$smarty->assign('email_logo_url', $email_logo_url);
		$smarty->assign('width_logo', $width_logo?$width_logo:'auto');
		$smarty->assign('height_logo', $height_logo?$height_logo:'auto');
		$oneItem=$this->getOne($subscribe_id);
		$message = str_replace('[%PAGE_NAME%]',PAGE_NAME,$message);
		$message = str_replace('{URL}','http://'.$_SERVER['HTTP_HOST'],$message);
		$message = str_replace('[%CUSTOMER_EMAIL%]',$oneItem['email'],$message);
		$message = str_replace('[%CUSTOMER_NAME%]',$oneItem['name'],$message);
		$subject = $smarty->fetch('eval:'.$subject);
		$message = $smarty->fetch('eval:'.$message);
				
		#Send email to customer
		$from = EMAIL_REPLY;
		$to = trim($oneItem['email']);
		$owner = PAGE_NAME;
		$subject = PAGE_NAME.' '.$core->get_Lang('Newsletter');
		#
		$headers = 	"MIME-Version: 1.0\r\n".
				"Content-type: text/html; charset=utf-8\r\n".
				"From:  ".$owner."<".$from.">\r\n".
				"Subject: ".$subject."\r\n";
		print_r($message);die();
		$is_send_mail = @mail($to, $subject, $message, $headers);
	}
}
?>