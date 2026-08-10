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
class Issue extends dbBasic{
	function __construct(){
		$this->pkey = "issue_id";
		$this->tbl = DB_PREFIX."issue";
	}
	function getTitle($issue_id, $oDataTable = array()){
		global $clsISO;
		return sprintf('#%s %s', $issue_id, $oDataTable['title']);
	}
	function getTitleV2($issue_id){
		global $clsISO;
		return $this->getOneField('title', $issue_id);
	}
	function formatTitle($title){
		if(strlen($title) > 50){
			$l = substr($title, 0, 50);
			$r = substr($title,50, strlen($title) - 50);
			return $l.'<br />'. $r;
		} else {
			return $title;
		}
	}
	function getProgress($issue_id, $oDataTable=array()){
		if(!isset($oDataTable['done_ratio']) || !isset($oDataTable['status_id'])){
			$oDataTable = $this->getOne($issue_id,"done_ratio,status_id");
		}
		$status_id = $oDataTable['status_id'];
		$done_ratio = $oDataTable['done_ratio'];
		if($status_id==ISSUE_COMPLETED_ID){
			$done_ratio = 100;
		}
		$html = '<div class="progress w-px-75 text-center">
			<div class="progress-bar" style="width:'.$done_ratio.'%;">
				<span>'.$done_ratio.'%</span>
			</div>
		</div>';
		return $html;
	}
	function getStatus($status_id){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$oProperty  = $clsProperty->getOne($status_id, "title,bgcolor");
		return '<div class="d-flex align-items-center">
			<span style="background:'.$oProperty['bgcolor'].'" class="status_issue mr-1"></span>
			<span class="text-muted">' . $clsProperty->getTitle($status_id, $oProperty) . '</span>
		</div>';
	}
	function getStatusLabel($status_id){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$oProperty  = $clsProperty->getOne($status_id, "title,bgcolor");
		return sprintf('<span class=\'badge\' style=\'background:%s\'>%s</span>',$oProperty['bgcolor'],$clsProperty->getTitle($status_id, $oProperty));
	}
	public function getPriority($priority_id, $label=true){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$oProperty = $clsProperty->getOne($priority_id, "title,image");
		$text= ($label==true) ? $oProperty['title']: '';
		return '<span style="width:100px;">
			 <img src="'.$oProperty['image'].'" width="16" height="16" align="absmiddle" title="'.$oProperty['title'].'"> '.$text.'
		</span>';
	}
	function checkDoTask($issue_id, $oneIssue=array()){
		global $core, $profile_id, $clsISO;
		if(!isset($oneIssue['assign_to_id'])){
			$oneIssue = $this->getOne($issue_id, "assign_to_id");
		}
		if($profile_id==$oneIssue['assign_to_id'])
			return 1;
		return 0;
	}
	function isTrackingTimeIssue($issue_id){
		global $adminid;
		$clsIssueCurrent = new IssueCurrent();
		if($this->getOneField('status_id',$issue_id)==_ISSUE_STATUS_COMPLETED) return 0;//Completed
		/*if($clsVS_Issue->checkCanStartFinish($issue_id)==0){
			$clsVS_IssueCurrent->updateByCond("issue_id='$issue_id' and due_date='0'","due_date='".time()."'");
			return 0;//Expired 
		}*/
		if($clsIssueCurrent->countItem("`issue_id`='{$issue_id}' and due_date='0'")>0) return 1;
		return 0;
	}
	public function countTime($seconds){
		global $core, $clsISO;
		return gmdate ('H:i:s', $seconds);
	}
	public function getOfficeTime($start, $end){
		$extraTime = 0;
		$retVal = $end - $start;
		return $retVal-$extraTime;
	}
	public function getTimeDoInSecond($issue_id){
		global $core, $dbconn;
		$clsIssueCurrent = new IssueCurrent();
		
		$time = 0;
		$tmp = $clsIssueCurrent->getAll("`issue_id`='{$issue_id}' order by `start_date` asc", "start_date,due_date");
		if(!empty($tmp)){
			foreach($tmp as $issue){
				if($issue['due_date']=='0'){
					$end = time();
				}else{
					$end = $issue['due_date'];
				}
				$time += $this->getOfficeTime($issue['start_date'], $end);
			}
			unset($tmp);
		}
		return $time;
	}
	function getTimeDoingTotal($issue_id){
		$totalTime = $this->getTimeDoInSecond($issue_id);
		$childs = $this->getAll("`is_trash`=0 and `parent_id`='{$issue_id}' order by `issue_id` asc", $this->pkey);
		if(!empty($childs)){
			foreach($childs as $issue){
				$totalTime += $this->getTimeDoingTotal($issue[$this->pkey]);
			}
			unset($childs);
		}
		return $totalTime;
	}
	function getTimeDo($issue_id){
		return $this->countTime($this->getTimeDoingTotal($issue_id)); 
	}
	function getTimeClock($issue_id, $_oIssue){
		$html_clock = "";
		if($_oIssue['status_id'] == _ISSUE_STATUS_DOING){
			$html_clock = '<span class="time-issue-'.$issue_id.' badge bg-label-danger timeCountUp text-red">
				'.$this->getTimeDo($issue_id).'
			</span>';
		}
		return $html_clock;
	}
	function render_html_actions($issue_id, $oneIssue = array()){
		global $core, $dbconn, $profile_id, $clsISO, $deviceType;
		$oneIssue = $this->getOne($issue_id, "user_id,status_id,assign_to_id");
		$user_id = $oneIssue['user_id'];
		$status_id = $oneIssue['status_id'];
		$html = ""; $cls = ($deviceType=='phone') ? ' flex-fill' : '';
		if($status_id == _ISSUE_STATUS_COMPLETED){
			$html.= '<button type="button" class="btn btn-outline-default btnQuickReply" onClick="$Core.issue.open_issue_notes(this, event)" issue_id="'.$issue_id.'" title="Click to report this issue"><i class="bx bx-plus"></i> Thêm báo cáo</button>';
		} else if($status_id == _ISSUE_STATUS_DOING){
			$html.= '<!-- STOP -->
			'.($this->checkDoTask($status_id, $oneIssue)?'<button id="btnStopIssue" type="button" onClick="$Core.issue.stop_issue(this, event);" class="btn'.$cls.' btn-outline-danger" title="Dừng" issue_id="'.$issue_id.'"><i class="bx bx-pause"></i> Tạm dừng</button>':'').'
			<!-- Report -->
			<button type="button" class="btn'.$cls.' btn-outline-default btnQuickReply" onClick="$Core.issue.open_issue_notes(this, event)" issue_id="'.$issue_id.'"  title="Click to report" tp="add"><i class="bx bx-plus"></i> Thêm báo cáo</button>
			<!-- Done -->
			'.($this->checkDoTask($status_id, $oneIssue)?'<button type="button" class="btn'.$cls.' btn-outline-success" onclick="$Core.issue.done_issue(this, event); return false;" issue_id="'.$issue_id.'"><i class="bx bx-check-circle"></i> Hoàn thành</button>':'');
		}else{
			$html.= ($this->checkDoTask($issue_id, $oneIssue) ? '<!-- Start -->
			<button type="button" class="btn btn-outline-warning doItNow" title="Bắt đầu" onClick="$Core.issue.start_issue(this, event)" issue_id="'.$issue_id.'"><i class="bx bx-play"></i> Bắt đầu</button>':'');
			$html.= '<!-- Report -->
			<button type="button" class="btn btn-outline-default btnQuickReply" onClick="$Core.issue.open_issue_notes(this, event)" issue_id="'.$issue_id.'" '.$tp_list.' title="Click to report this issue"><i class="bx bx-plus"></i> Thêm báo cáo</button>
			<!-- Done -->
			'.($this->checkDoTask($issue_id, $oneIssue)?'<button type="button" class="btn btn-outline-success" onclick="$Core.issue.done_issue(this, event); return false;" title="Hoàn thành" issue_id="'.$issue_id.'"><i class="bx bx-check-circle"></i> Hoàn thành</button>':'');
		}
		return $html;
	}
	function getImplementer($issue_id, $uid, $oDataTable=array()){
		global $core, $dbconn, $clsISO, $profile_id;
		$clsProfile = new Profile();
		$user_id = $oDataTable['user_id'];
		$assign_to_id = $oDataTable['assign_to_id'];
		$participants = $oDataTable['participants'];
		$arr_participants = !empty($participants) 
			? json_decode(html_entity_decode($participants), true) : array();
		
		$html = "";
		if(!empty($arr_participants)){ $ii= 1;
			foreach($arr_participants as $user_id){
				if($ii <= 5){
					$oneUser = $clsProfile->getOne($user_id, "full_name,first_name,last_name,avatar");
					$html.= '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar pull-up" title="'.$clsProfile->getFullName($user_id, $oneUser).'">
					  <img src="'.$clsProfile->getAvatar($user_id, $oneUser).'" alt="'.$clsProfile->getFullName($user_id, $oneUser).'" class="rounded-circle" />
					</li>';
					++$ii;
				}
			}
		} 
		if($user_id == $profile_id || $assign_to_id == $profile_id){
			$html.= '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar pull-up" title="Thêm người theo dõi"><a class="user-plus radius-circle bg-white" onClick="$Core.issue.open_issue_edit(this, event)" p_field="participants" toId="participants_'.$uid.'" uid="'.$uid.'" p_id="'.$issue_id.'">'.$clsISO->makeIcon('bx-user-plus').'</a></li>';
		}
		return $html; die();
	}
	function doDelete($issue_id){
		$clsIssueNote = new IssueNote();
		$clsIssueTask  = new IssueTask();
		$clsIsssueCurrent = new IssueCurrent();
		// Delete Issue Notes
		$clsIssueNote->deleteByCond("`issue_id`='{$issue_id}'");
		// Delete Issue Task
		$clsIssueTask->deleteByCond("`issue_id`='{$issue_id}'");
		// Delete Issue Task
		$clsIsssueCurrent->deleteByCond("`issue_id`='{$issue_id}'");
		// Delete Issue
		$this->deleteOne($issue_id);		
		// Return
		return 1;
	}
	function getLink($isue_id, $shorted=false){
		global $core, $clsISO;
		if($shorted==true)
			return sprintf('/issue/%s.html', $clsISO->base64url_encode($isue_id));
		return PCMS_URL . sprintf('/issue/%s.html', $clsISO->base64url_encode($isue_id));
	}
	function getUserNotifier($issue_id, $tp='_all', $oneIssue = array()){
		global $core, $dbconn, $profile_id, $clsISO;
		if(empty($oneIssue)){
			$field = "user_id,assign_to_id,participants";
			$oneIssue = $this->getOne($issue_id, $field);
		}
		$participants = $oneIssue['participants'];
		$arr_participants = $clsISO->to_array_json($participants);
		###
		$list_users = array(); // _cre: Tạo, _do: Làm
		if($profile_id != $oneIssue['user_id'] && (in_array($tp, array('_all', '_do'))))
			$list_users[] = $oneIssue['user_id'];
		if($profile_id != $oneIssue['assign_to_id'] && (in_array($tp, array('_all', '_cre'))))
			$list_users[] = $oneIssue['assign_to_id'];
		if(!empty($arr_participants)){
			foreach($arr_participants as $u_id){
				if(!in_array($u_id, $list_users)){
					$list_users[] = $u_id;
				}
			}
		}
		return $list_users;
	}
	function getUserNotifierUpgrade($issue_id, $oneIssue = array()){
		global $core, $dbconn, $profile_id, $clsISO;
		$participants = $oneIssue['participants'];
		$arr_participants = $clsISO->to_array_json($participants);
		
		$list_users = array();
		if($oneIssue['user_id'] == $profile_id && $oneIssue['assign_to_id'] != $profile_id){
			$list_users[] = $oneIssue['assign_to_id'];
		} else if($oneIssue['assign_to_id'] == $profile_id && $oneIssue['user_id'] != $profile_id){
			$list_users[] = $oneIssue['user_id'];
		} else {
			if($profile_id != $oneIssue['user_id']) 
				$list_users[] = $oneIssue['user_id'];
			if($profile_id != $oneIssue['assign_to_id']) 
				$list_users[] = $oneIssue['assign_to_id'];
		}
		if(!empty($arr_participants)){
			foreach($arr_participants as $u_id){
				if(!in_array($u_id, $list_users)){
					$list_users[] = $u_id;
				}
			}
		}
		return $list_users;
	}
	function sendEmail($issue_id){
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		$clsIssueNote = new IssueNote();
		$clsIssueTask  = new IssueTask();
		$clsEmailTemplate = new EmailTemplate();
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_CREATE_ISSUE_ID);
		$subject = $clsEmailTemplate->getSubject(_MAIL_CREATE_ISSUE_ID,$oneEmailTemplate);
		$message = $clsEmailTemplate->getContent(_MAIL_CREATE_ISSUE_ID,$oneEmailTemplate);	
		// $clsISO->print_pre($oneEmailTemplate); die();
		$oneIssue = $this->getOne($issue_id);
		$type_id = $oneIssue['type_id'];
		$priority_id = $oneIssue['priority_id'];
		$assign_to_id = $oneIssue['assign_to_id'];
		$oneAssign = $clsProfile->getOne($assign_to_id,'code,full_name,first_name,last_name,email');
		$replace_fields = array(
			'{issue_name}' => $oneIssue['title'],
			'{issue_type}' => $clsProperty->getTitle($type_id),
			'{priority_name}' => $clsProperty->getTitle($priority_id),
			'{issue_description}' => $oneIssue['content'],
			'{user_name}' => $clsProfile->getIndentityV2($profile_id, $oneProfile, false),
			'{user_create}' => $clsProfile->getIndentityV2($assign_to_id, $oneAssign, false),
			'{issue_link}' => sprintf('<a href="%s">%s</a>',$this->getLink($issue_id), $this->getLink($issue_id))
		);
		foreach($replace_fields as $key => $val){
			$subject = str_replace($key, $val, $subject);
			$message = str_replace($key, $val, $message);
		}
		// Send Email
		$toemail = $clsProfile->getEmail($assign_to_id, $oneAssign);
		$toname = $clsProfile->getIndentityV2($assign_to_id, $oneAssign, false);
		// $clsISO->print_pre($toemail); die();
		$is_send_email = $clsISO->sendEmail($toemail, $toname, $subject, $message);
		// $clsISO->print_pre($is_send_email); die();
		return $is_send_email;
	}
	function checkIsParent($cat_id,$parent_id_check){
        $one = $this->getOne($cat_id, "parent_id");
        $parent_id = $one['parent_id'];
        if($parent_id==$parent_id_check){
            return 1;
        }
        if($parent_id==0){return 0;}
        return $this->checkIsParent($parent_id,$parent_id_check);
    }
	function getListParent($issue_id, $ret=array()){
		global $core, $dbconn, $clsISO;
		if(!$ret) $ret = array();
		$ret[] = $issue_id;
		$parent_id = $this->getOneField("parent_id", $issue_id);
		if($parent_id > 0){
			$ret = $this->getListParent($parent_id, $ret);
		}
		return $ret;
	}
	function getTree($issue_id){
		global $core, $dbconn, $clsISO;
		$html = ""; 
		$tmp = $this->getListParent($issue_id);
		if(!empty($tmp)){
			$tmp = @array_reverse($tmp);
			for($i=0; $i<count($tmp); $i++){
				$html.= sprintf('<div class="mb-1">
					<a href="javascript:void(0)" onClick="$Core.issue.view_issue(this, event)" issue_id="%s">%s %s</a>
				</div>', $tmp[$i], str_repeat('---', $i), $this->getTitleV2($tmp[$i]));
			}
		}
		// Return
		return $html;
	}
	function getIsssueChild($issue_id, $spacer='---', $results = array()){
		global $dbconn, $core;
		if(!$results) $results = array();
		$tmp = $this->getAll("`is_trash`=0 and `parent_id`='{$issue_id}' order by `reg_date` DESC");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$id = $val[$this->pkey];
				$val['title'] = $spacer.'&nbsp;'.trim($this->getTitleV2($id));
				$results[$id] = $val;
				$results = $this->getIsssueChild($id, $spacer.'----', $results);
			}
			unset($tmp);
		}
		return $results;
	}
}