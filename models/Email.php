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
class Email extends dbBasic{
	function Email(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."email";
	}
	function checkValidEmail($email){
		$res = $this->getAll("1=1 and email='$email' order by email_id desc limit 0,1");
		return (!empty($res))?1:0;
	}
	function sendMessage($fromemail,$fromname,$email,$toname,$subject,$message,$attachments="",$plaintext=0) {
		global $clsISO,$core;
		
		$clsConfiguration = new Configuration();
		require_once($_SERVER['DOCUMENT_ROOT'].'/inc/mailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		try {
			$mail->From = $fromemail;
			$mail->FromName = html_entity_decode($fromname, ENT_QUOTES);

			if ($clsConfiguration->getValue('MailType') == "mail") {
				$mail->Mailer = "mail";
			}
			else {
				if ($clsConfiguration->getValue('MailType') == "smtp") {
					$mail->IsSMTP();
					$mail->Host = $clsConfiguration->getValue('SiteSmtpHost');
					$mail->Port = $clsConfiguration->getValue('SiteSmtpPort');
					$mail->Hostname = $_SERVER['SERVER_NAME'];
	
					if ($clsConfiguration->getValue('SiteSmtpSSL')) {
						$mail->SMTPSecure = $clsConfiguration->getValue('SiteSmtpSSL');
					}
	
	
					if ($clsConfiguration->getValue('SiteSmtpUsername')) {
						$mail->SMTPAuth = true;
						$mail->Username = $clsConfiguration->getValue('SiteSmtpUsername');
						$mail->Password = $clsConfiguration->getValue('SiteSmtpPassword');
					}
	
					$mail->Sender = $mail->From;
	
					if ($fromemail != $clsConfiguration->getValue('SiteSmtpUsername')) {
						$mail->AddReplyTo($fromemail, html_entity_decode($fromname, ENT_QUOTES));
					}
				}
			}
	
			$mail->XMailer = $clsConfiguration->getValue("CompanyName");
			$mail->CharSet = 'utf-8';
			$mail->AddAddress(trim($email), html_entity_decode($toname, ENT_QUOTES));
	
			if ($clsConfiguration->getValue('BCCMessages')) {
				$bcc = $clsConfiguration->getValue('BCCMessages') . ",";
				$bcc = explode(",", $bcc);
				foreach ($bcc as $value) {
					$ccaddress = trim($value);
	
					if ($ccaddress) {
						$mail->AddBCC($ccaddress);
						continue;
					}
				}
			}		
	
			$mail->Subject = html_entity_decode($subject, ENT_QUOTES);
	
			if ($plaintext) {
				$message = str_replace("<br>", "", $message);
				$message = str_replace("<br />", "", $message);
				$message = strip_tags($message);
				$mail->Body = html_entity_decode($message, ENT_QUOTES);
				$message = nl2br($message);
			}
			else {
				$message_text = str_replace("<p>", "", $message);
				$message_text = str_replace("</p>", "\r\n\r\n", $message_text);
				$message_text = str_replace("<br>", "\r\n", $message_text);
	
				$message_text = str_replace("<br />", "\r\n", $message_text);
	
				$message_text = strip_tags($message_text);
				
				$mail->Body = $message;
				$mail->AltBody = html_entity_decode($message_text, ENT_QUOTES);
			}
	
	
			if ($tplattachments) {
				$tplattachments = explode(",", $tplattachments);
				foreach ($tplattachments as $attachment) {
					$filename = $downloads_dir . $attachment;
					$displayname = substr($attachment, 7);
					$mail->AddAttachment($filename, $displayname);
				}
			}
	
	
			if ($attachmentfilename) {
				if (is_array($attachmentfilename)) {
					$count = 0;
					foreach ($attachmentfilename as $filelist) {
						$mail->AddStringAttachment($attachmentdata[$count], $filelist);
						++$count;
					}
				}
				else {
					$mail->AddStringAttachment($attachmentdata, $attachmentfilename);
				}
			}
	
	
			if (is_array($attachments)) {
				foreach ($attachments as $filename => $displayname) {
					$mail->AddAttachment($filename, $displayname);
				}
			}
	
			$mail->Send();
			$f = "userid,subject,message,date,to,cc,bcc";
			$v = "'".$core->_SESS->user_id."','".addslashes($subject)."','".addslashes($message)."','".date("YmdHis")."','".addslashes($email)."','".addslashes($copyto)."','".addslashes($clsConfiguration->getValue('BCCMessages'))."'";
			$this->insertOne($f,$v);
			$mail->ClearAddresses();
			return 1;
		} catch (phpmailerException $e) {
			return 2;
		}
		catch (Exception $e) {
			return 3;
		}
	}
	function getMaxId(){
		$res = $this->getAll("1=1 order by email_id desc");
		return intval($res[0]['email_id'])+1;
	}
	
	function getIdByEmail($email){
		$res = $this->getAll("email='$email'");
		return $res[0]['email_id'];
	}
	
	function getEmail($email_id){
		$res = $this->getOne($email_id);
		return $res['email'];
	}
	function sendMail($email_id){
		global $core, $clsISO, $clsConfiguration;
		#
		$clsEmailTemplate = new EmailTemplate();
		$oneItem = $this->getOne($email_id);
		
		#Send email to customer
		$from = $clsConfiguration->getValue('SiteReplyEmail');
		$owner = PAGE_NAME;
		$to = trim($oneItem['email']);
		$subject = PAGE_NAME.' '.$core->get_Lang('Newsletter');
		
		$message = $clsEmailTemplate->getContent(3);
		$message = str_replace('[%PAGE_NAME%]',PAGE_NAME,$message);
		$message = str_replace('{URL}','http://'.$_SERVER['HTTP_HOST'],$message);
		$message = str_replace('[%CUSTOMER_NAME%]','<b>'.$oneItem['name'].'</b>',$message);
		#
		$clsISO->sendEmail($from,$to,$subject,$message,$owner);
	}
}
?>