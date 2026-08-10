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
class IssueNote extends dbBasic{
	function __construct(){
		$this->pkey = "issue_note_id";
		$this->tbl = DB_PREFIX."issue_note";
	}
	function getQuoteShow($issue_note_id){
		global $core,$profile_id,$clsISO;
		$clsProfile = new Profile();
		$html = '';
		$field = "{$this->pkey},parent_id";
		$one = $this->getOne($issue_note_id, $field);
		if(!empty($one) && $one['parent_id'] > 0){
			$field2 = "{$this->pkey},parent_id,content,html_change,user_id,reg_date";
			$oneParent = $this->getOne($one['parent_id'], $field2);
			$html_change = $oneParent['html_change'];
			if(!empty($html_change)){
				$html_change = '<ul class="pl-3">
					'.html_entity_decode($html_change).'
				</ul>';
			}
			$hd = ($oneParent['user_id'] != $profile_id ? "Bạn" : $clsProfile->getIndentity($oneParent['user_id'],true,'xxs')) . ' đã viết vào lúc '. $clsISO->getTimeAgo($oneParent['reg_date']);
			$html.= '<blockquote class="mt-2">
				<div class="hdblockquote mb-2">'.$hd.'</div>
				<div class="replyblockquote">
					'.$html_change.' 
					'.html_entity_decode($oneParent['content']).'
				</div>'; 
				$html.= $this->getQuoteShow($one['parent_id']);
				$html.= '
			</blockquote>'; 
		}else{
			return '';
		}
		return $html;
	}
	function sendEmail($issue_note_id){
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		$clsIssue = new Issue();
		$clsIssueTask  = new IssueTask();
		$clsEmailTemplate = new EmailTemplate();
		
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_CREATE_ISSUE_NOTE_ID);
		$subject = $clsEmailTemplate->getSubject(_MAIL_CREATE_ISSUE_NOTE_ID,$oneEmailTemplate);
		$message = $clsEmailTemplate->getContent(_MAIL_CREATE_ISSUE_NOTE_ID,$oneEmailTemplate);	
		// $clsISO->print_pre($oneEmailTemplate); die();
		$field = "t1.*,t2.issue_id,t2.title,t2.priority_id,t2.user_id,t2.assign_to_id";
		$oneIssue = $dbconn->getRow("select {$field} from {$this->tbl} as t1 
			inner join {$clsIssue->tbl} as t2 on t1.issue_id=t2.issue_id 
			where t1.issue_note_id='{$issue_note_id}'");
		$issue_note_content = '';
		if($oneIssue['html_change']){
			$issue_note_content .= '<ul>'.$oneIssue['html_change'].'</ul>';
		}
		$issue_note_content .= $oneIssue['content'];
		###
		$issue_id = $oneIssue['issue_id'];
		$priority_id = $oneIssue['priority_id'];
		$oneAssign = $clsProfile->getOne($assign_to_id,'code,full_name,first_name,last_name,email');
		$replace_fields = array(
			'{issue_note_id}' => $issue_note_id,
			'{issue_name}' => $oneIssue['title'],
			'{priority_name}' => $clsProperty->getTitle($priority_id),
			'{issue_note_content}' => $issue_note_content,
			'{user_create_note}' => $clsProfile->getIndentityV2($profile_id, $oneProfile, false),
			'{issue_link}' => sprintf('<a href="%s">%s</a>',$clsIssue->getLink($issue_id), $clsIssue->getLink($issue_id))
		);
		foreach($replace_fields as $key => $val){
			$subject = str_replace($key, $val, $subject);
			$message = str_replace($key, $val, $message);
		}
		// Người giao tạo notes gửi cho người nhận
		if($oneIssue['user_id']==$profile_id){
			$oneReceived = $clsProfile->getOne($oneIssue['assign_to_id'], "code,full_name,first_name,last_name,email");
			$toemail = $clsProfile->getEmail($oneIssue['assign_to_id'], $oneReceived);
			$toname = $clsProfile->getIndentityV2($oneIssue['assign_to_id'], $oneReceived, false);
			// $clsISO->print_pre($toemail); die();
			$is_send_email = $clsISO->sendEmail($toemail, $toname, $subject, $message);
		} else if($oneIssue['assign_to_id']==$profile_id){
			// Nguời nhận việc tạo notes gửi cho người tạo
			$oneProfile = $clsProfile->getOne($oneIssue['user_id'], "code,full_name,first_name,last_name,email");
			$toemail = $clsProfile->getEmail($oneIssue['user_id'], $oneProfile);
			$toname = $clsProfile->getIndentityV2($oneIssue['user_id'], $oneProfile, false);
			// $clsISO->print_pre($toemail); die();
			$is_send_email = $clsISO->sendEmail($toemail, $toname, $subject, $message);
		} else {
			// Gửi cho người nhận
			$oneReceived = $clsProfile->getOne($oneIssue['assign_to_id'], "code,full_name,first_name,last_name,email");
			$toemail = $clsProfile->getEmail($oneIssue['assign_to_id'], $oneReceived);
			$toname = $clsProfile->getIndentityV2($oneIssue['assign_to_id'], $oneReceived, false);
			// $clsISO->print_pre($toemail); die();
			$oneProfile = $clsProfile->getOne($oneIssue['user_id'], "code,full_name,first_name,last_name,email");
			$cc = array(
				'toemail' => $clsProfile->getEmail($oneIssue['user_id'], $oneProfile),
				'toname' => $clsProfile->getIndentityV2($oneIssue['user_id'], $oneProfile)
			);
			$is_send_email = $clsISO->sendEmail($toemail, $toname, $subject, $message, $cc);
		}
		// $clsISO->print_pre($is_send_email); die();
		return $is_send_email;
	}
}