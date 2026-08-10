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
class Feedback extends dbBasic{
	function __construct(){
		$this->pkey = "feedback_id";
		$this->tbl = DB_PREFIX."feedback";
	}
	function genCode($feedback_id){
		global $clsConfiguration;
		return '#Fb'.$feedback_id;
	}
	function getFeedBackInfo($feedback_id){
		$one = $this->getOne($feedback_id);
		$feedback_store = @unserialize($one['feedback_store']);
		return $feedback_store;
	}
	function countTotalAllFeedback($clsTable) {
		$cond = "1=1";
		return $this->countItem($cond);
	}
	function countTotalFeedback($is_process='') {
		$cond = "1=1 and is_process = '$is_process'";
		return $this->countItem($cond);
	}
	function sendEmail($feedback_id){
		global $core,$smarty,$extLang,$clsISO,$clsPage,$clsConfiguration;
		header('Content-Type: text/html; charset=utf-8');
		#
		$email_template_id = 2;
		$clsEmailTemplate = new EmailTemplate();
		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);
		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);
		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);
		
		$email_logo_url = $clsEmailTemplate->getLogoUrl($email_template_id);
		$width_logo = $clsEmailTemplate->getLogoWidth($email_template_id);
		$height_logo = $clsEmailTemplate->getLogoHeight($email_template_id);

		$smarty->assign('asset_url', URL_ASSET);
		$smarty->assign('shop_url', DOMAIN_NAME);
		$smarty->assign('email_logo_url', $email_logo_url);
		$smarty->assign('width_logo', $width_logo?$width_logo:'auto');
		$smarty->assign('height_logo', $height_logo?$height_logo:'auto');

		$smarty->assign('shop_name', $clsConfiguration->getValue('company_name'));
		$smarty->assign('shop_phone', $clsConfiguration->getValue('company_phone'));
		$smarty->assign('shop_email', $clsConfiguration->getValue('company_email'));
		$smarty->assign('shop_address', $clsConfiguration->getValue('company_address'));
		#---
		$one = $this->getOne($feedback_id);
		$code = $one['code'];
		$contact_name = $one['full_name'];
		$contact_email = $one['email'];
		$contact_phone = $one['phone'];
		$contact_address = $one['address'];
		$contact_message = $one['message'];
		$smarty->assign('contact_name', $contact_name);
		$smarty->assign('contact_email', $contact_email);
		$smarty->assign('contact_phone', $contact_phone);
		$smarty->assign('contact_address', $contact_address);
		$smarty->assign('contact_message', $contact_message);
		$subject = $smarty->fetch('eval:'.$subject);
		$message = $smarty->fetch('eval:'.$message);
		#-- Send email to customer
		$toemail = $contact_email;
		$toname = $contact_name;
		$is_send_email = $clsISO->sendEmail($toemail,$toname,$subject,$message);
		#-- Send email to administrator
		$lst_email_admins = $clsISO->getEmailNotifier('contact');
		if(!empty($lst_email_admins)){
			foreach($lst_email_admins as $email){
				if($email['status']){
					$email_name = $email['email_name'];
					$email_address = $email['email_address'];
					$clsISO->sendEmail($email_address, $email_name, $subject, $message);
				}
			}
		}
		return 1; 
	}
}
?>