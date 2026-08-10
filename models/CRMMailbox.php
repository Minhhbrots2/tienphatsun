<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Private By VietISO (support@vietiso.com)                     # ||
|| # ---------------------------------------------------------------- # ||
|| # All Script code in this file is ©2007-2013 VietISO JSC.          # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class CRMMailbox extends dbBasic{
	function CRMMailbox(){
		$this->pkey = "id";
		$this->tbl = "_crm_mail_configuration";
		$this->setTable($this->tbl);
	}
	function sendEmail($mailbox_id, $toemail, $toname, $subject, $message, $attachments, $plaintext=false, $cc=null){
		global $dbconn, $mail, $core, $clsISO, $clsConfiguration, $mail;
		$oneMailbox = $this->GetOne($mailbox_id);
		
		// Info E-mail
		$SMTPHost = $oneMailbox['SMTPHost'];
		$SMTPPort = $oneMailbox['SMTPPort'];
		$SMTPUsername = $oneMailbox['SMTPUsername'];
		$SMTPPassword = $clsISO->decrypt($oneMailbox['SMTPPassword']);
		$SMTPSecure = $oneMailbox['SMTPSecure'];
		// Config E-mail
		$fromname = $oneMailbox['name'];
		$fromemail = $oneMailbox['email'];
		if(!$fromemail){
			$fromemail = $clsConfiguration->getValue('email_from_email');
		}
		$ErrorInfo = "";
		// (Re)create it, if it's gone missing
		if (!( $mail instanceof PHPMailer ) ) {
			require_once(DIR_INCLUDES.'/addons/mailer/class.phpmailer.php');
			require_once(DIR_INCLUDES.'/addons/mailer/class.pop3.php');
			require_once(DIR_INCLUDES.'/addons/mailer/class.smtp.php');
			$mail = new PHPMailer(true);
		}
		// Init
		try {
			// Empty out the values that may be set
			$mail->clearAllRecipients();
			$mail->clearAttachments();
			$mail->clearCustomHeaders();
			$mail->clearReplyTos();
			$mail->ClearAddresses();
			$mail->isMail();
			$mail->From = trim($fromemail);
			$mail->FromName = trim($fromname);
			// Set Content-Type and charset
			$content_type = 'text/html'; 
			if($plaintext){ $content_type = 'text/plain'; }
			$mail->ContentType = $content_type;
			$mail->CharSet = 'utf-8';
			$mail->XMailer = $clsConfiguration->getValue("mail_from_name");
			$mail->AddAddress(trim($toemail), $toname);
			//$mail->Mailer = 'smtp';
			//Enable SMTP debugging. 
			$mail->SMTPDebug = 0;
			//$mail->Priority  = 3;
			$mail->Encoding  = '8bit';
			//$mail->SMTPAutoTLS  = 1;
			//Set PHPMailer to use SMTP.
			$mail->IsSMTP();
			if('text/html' == $content_type){
				$mail->IsHTML(true);
			}
			//Set SMTP host name    
			$mail->Host = $SMTPHost;
			$mail->Port = $SMTPPort;
			if ($SMTPSecure !== 'none') {
				$mail->SMTPSecure = $SMTPSecure;
			}
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			//Provide username and password
			$mail->SMTPAuth = true;
			$mail->Username = trim($SMTPUsername);
			$mail->Password = $SMTPPassword;
			$mail->Sender = $mail->From;
			if ($fromemail != $SMTPUsername) {
				$mail->AddReplyTo($fromemail, html_entity_decode($fromname, ENT_QUOTES));
			}
			$mail->Subject = html_entity_decode($subject, ENT_QUOTES);
			if ($plaintext) {
				$message = html_entity_decode($message, ENT_QUOTES);
				$mail->Body = strip_tags($message);
			}else {
				$mail->Body = $message;
				$message_text = str_replace("<p>", "", $message);
				$message_text = str_replace("</p>", "\r\n\r\n", $message_text);
				$message_text = str_replace("<br>", "\r\n", $message_text);
				$message_text = str_replace("<br />", "\r\n", $message_text);
				$message_text = strip_tags($message_text);
				$mail->AltBody = html_entity_decode($message_text, ENT_QUOTES);
			}
			// Attachments
			if (!empty($attachments)){
				if(is_array($attachments)){
					foreach ($attachments as $filename) {
						$mail->AddAttachment($filename);
					}
				}else{
					$mail->AddAttachment($attachments);
				}	
			}
			// Add CC
			if(is_array($cc) && !empty($cc) && !is_null($cc)){
				foreach($cc as $value) {
					$ccaddress = trim($value);
					if($ccaddress) {
						$mail->AddCC($ccaddress);
					}
				}
			}
			// Send Email
			try{
				$is_send_mail = $mail->send();
			} catch (phpmailerException $e) {
				$is_send_mail = 0;
				$ErrorInfo = "PHPMailer Error: ".$mail->ErrorInfo;
			}
		} catch (Exception $e) {
			$is_send_mail = 0;
			$ErrorInfo = "PHPMailer Error: ".$mail->ErrorInfo;
		}
		return array(
			'result' => ($is_send_mail?'success':'error'), 
			'message' => $ErrorInfo
		);
	}
}
?>