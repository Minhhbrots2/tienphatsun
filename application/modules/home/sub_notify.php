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
function notify_live(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsNotify = new Notify();
	###
	$totalNoty = 0; $titleNoty = $contentNoty = "";
	$tmp = $clsNotify->getByCond("`is_send`='0' AND `list_user_slash` like '%|{$profile_id}|%' 
		AND FROM_UNIXTIME(`send_date`,'%d/%m/%Y %H:%i')='".date('d/m/Y H:i')."' ORDER BY `reg_date` DESC");
	if(!empty($tmp)){
		$totalNoty = 1;
		$linkNoty = "/";
		$titleNoty = "Thông báo";
		if($tmp['tbl']=='FollowUp'){
			$titleNoty = "Lịch hẹn";
			$oneFollowUp = $clsFollowUp->getOne($tmp['pval'], "customer_id");
			$linkNoty = PCMS_URL . '/crm/#/activity/'.$oneFollowUp['customer_id'];	
		}  else if($tmp['tbl'] == 'Issue'){
			$linkNoty= PCMS_URL . '/issue/'.$tmp['pval'].'.html';
		} else if($tmp['tbl']=='Customer'){
			$linkNoty = PCMS_URL . '/crm/#/activity/'.$tmp['pval'];
		} else if($tmp['tbl']=='Overtime'){
			$linkNoty = PCMS_URL . sprintf('/overtime/%s.html', $tmp['pval']);
		} else if($tmp['tbl'] == 'Calendar'){
			$linkNoty = '/lich-phong-hop.html';
		} else if($tmp['tbl']=='RequestPTG'){
			$linkNoty = $clsISO->getLink("request_ptg");
		} else if($tmp['tbl']=='Share'){
			$clsShare = new Share();
			$linkNoty = $clsShare->getLink($tmp['pval']);
		} else if($tmp['tbl']=='default_share'){
			$clsShare = new Share();
			$oShare = $clsShare->getOne($tmp['pval']);
			if($oShare['share_type'] == 'secret'){ // Thông tin ẩn
				$linkNoty = sprintf('/thong-tin-mat/%s.html', $tmp['pval']);
			} else if($oShare['share_type'] == 'honor') {
				$linkNoty = sprintf('/vinh-danh/%s.html', $tmp['pval']);
			} else {
				$linkNoty = '/net-dep-lao-dong.html';
			}
		}
		$contentNoty = strip_tags(html_entity_decode($tmp['content']));
		$clsNotify->updateOne($tmp[$clsNotify->pkey], array('is_send' => 1));
		unset($tmp);
	}
	// Return
	echo json_encode(array(
		"totalNoty" => $totalNoty,
		"titleNoty" => $titleNoty,
		"contentNoty" => $contentNoty,
		"linkNoty" => $linkNoty
	)); die();
}
function notify_notify(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$oneProfile;
	$clsShare = new Share();
	$clsNotify = new Notify();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$now = time();
	$totalNoty = 0; $htmlNoty = "";
	$holderG = Input::post('holderG', '_globe');
	$send_date = (int) Input::post('send_date', time());
	$totalNoty = $clsNotify->countItem("`list_user_slash` LIKE '%|{$profile_id}|%' AND `list_user_read` NOT LIKE '%|{$profile_id}|%' 
		AND DATE_SUB(FROM_UNIXTIME(`send_date`),INTERVAL 12 HOUR)<='".date('Y-m-d H:i:s')."'");
	$list_notify = $dbconn->getAll("SELECT * FROM {$clsNotify->tbl} WHERE `list_user_slash` LIKE '%|{$profile_id}|%' AND `send_date`<'{$send_date}' 
		AND `list_user_read` like '%|{$profile_id}|%' ORDER BY `send_date` DESC LIMIT 0,10");
	$list_notify_unread = array();
	if($holderG == "_globe"){
		$list_notify_unread = $dbconn->getAll("SELECT * FROM {$clsNotify->tbl} WHERE `list_user_slash` LIKE '%|{$profile_id}|%' 
		AND DATE_SUB(FROM_UNIXTIME(`send_date`),INTERVAL 12 HOUR)<'".date('Y-m-d H:i:s', $send_date)."' 
		AND `list_user_read` not like '%|{$profile_id}|%' ORDER BY `send_date` DESC");
	}
	if(!empty($list_notify) || !empty($list_notify_unread)){
		if(empty($list_notify) && !empty($list_notify_unread)){
			$list_notify = $list_notify_unread;
		} else if(!empty($list_notify) && !empty($list_notify_unread)){
			$list_notify = array_merge($list_notify_unread, $list_notify);
		}
		foreach($list_notify as $key => $val){
			$tbl = $val['tbl'];
			$pval = $val['pval'];
			$reg_date = $val['reg_date'];
			$send_date = $val['send_date'];
			$notify_id = $val[$clsNotify->pkey];
			if($tbl=='FollowUp'){
				$oneFollowUp = $clsFollowUp->getOne($pval, "customer_id");
				$customer_id = $oneFollowUp['customer_id'];
				$link= '/crm/#/activity/'.$customer_id;
			} else if($tbl == 'Issue'){
				$link= '/issue/'.$pval.'.html';
			} else if($tbl=='Customer'){
				$link = '/crm/#/activity/'.$pval;
			} else if($tbl=='Overtime'){
				$link = sprintf('/overtime/%s.html', $pval);
			} else if($tbl=='Share'){
				$clsShare = new Share();
				$link = $clsShare->getLink($pval);
			} else if($tbl=='default_share'){
				$oShare = $clsShare->getOne($pval);
				if($oShare['share_type'] == 'secret'){ // Thông tin ẩn
					$link = sprintf('/thong-tin-mat/%s.html', $pval);
				} else if($oShare['share_type'] == 'honor') {
					$link = sprintf('/vinh-danh/%s.html', $pval);
				} else {
					$link = '/net-dep-lao-dong.html';
				}
			} else if($tbl=='Slide'){
				$link = sprintf('/hoc-tap/%s.html', $pval);
			} else if($tbl=='Gratitude'){
				$link = sprintf('/tri-an/%s.html', $pval);
			} else if($tbl == 'Calendar'){
				$link = '/lich-phong-hop.html';
			} else if($tbl=='RequestPTG'){
				$link = $clsISO->getLink("request_ptg");
			} else if($tbl=='Stock'){
				$clsStock = new Stock();
				$ms_code = $clsStock->getMsCode($pval);
				$link = $clsStock->getLink($ms_code);
			} else if($tbl=='Docs'){
				$clsDocs = new Docs();
				$clsFolder = new Folder();
				$oneDoc = $clsDocs->getOne($pval,"cat_id");
				$link = $clsFolder->getLink($oneDoc["cat_id"])."?doc=".$core->encryptId($pval);
			} else if($tbl=='Quiz'){
				$clsQuiz = new Quiz();
				$link = $clsQuiz->getLink($pval);
			}
			if($tbl == 'News'){
				$htmlNoty.= '<li send_date="'.$val['send_date'].'" href="javascript:void(0)" onclick="open_news(this, event)" news_id="'.$pval.'" class="gotoLink'.($clsNotify->checkIsRead($notify_id, $val)?' bg-white':' unread bg-grayter').' list-group-item list-group-item-action dropdown-notifications-item" action="_detail" notify_id="'.$notify_id.'" course_id="'.$pval.'">
					<div class="d-flex fs-13">
						<div class="flex-grow-1">
							<p class="mb-0">'.$val['content'].'</p>
							<small class="text-muted">'.($send_date > $now ? $clsISO->getTimeMore($send_date) : $clsISO->getTimeAgo($send_date)).'</small>
						</div>
						<div class="flex-shrink-0 dropdown-notifications-actions">
							<a href="javascript:void(0)" onClick="$Core.notify.mark_read(this,event)" class="dropdown-notifications-read" notify_id="'.$notify_id.'"><span class="badge badge-dot"></span></a>
							<a href="javascript:void(0)" onClick="$Core.notify.delete(this,event)" class="dropdown-notifications-archive" notify_id="'.$notify_id.'"><span class="bx bx-x"></span></a>
						</div>
					</div>
				</li>';
			}else if($tbl == 'Course'){
				$clsCourse = new Course();
				$htmlNoty.= '<li send_date="'.$val['send_date'].'" href="'.$clsCourse->getLink($pval).'" class="notify_course gotoLink'.($clsNotify->checkIsRead($notify_id, $val)?' bg-white':' unread bg-grayter').' list-group-item list-group-item-action dropdown-notifications-item" notify_id="'.$notify_id.'" course_id="'.$pval.'">
					<div class="d-flex fs-13">
						<div class="flex-grow-1">
							<p class="mb-0">'.$val['content'].'</p>
							<small class="text-muted">'.($send_date > $now ? $clsISO->getTimeMore($send_date) : $clsISO->getTimeAgo($send_date)).'</small>
						</div>
						<div class="flex-shrink-0 dropdown-notifications-actions">
							<a href="javascript:void(0)" onClick="$Core.notify.mark_read(this,event)" class="dropdown-notifications-read" notify_id="'.$notify_id.'"><span class="badge badge-dot"></span></a>
							<a href="javascript:void(0)" onClick="$Core.notify.delete(this,event)" class="dropdown-notifications-archive" notify_id="'.$notify_id.'"><span class="bx bx-x"></span></a>
						</div>
					</div>
				</li>';
			}else{
				$htmlNoty.= '<li send_date="'.$val['send_date'].'" href="'.$link.'" class="gotoLink'.($clsNotify->checkIsRead($notify_id, $val)?' bg-white':' unread bg-grayter').' list-group-item list-group-item-action dropdown-notifications-item" notify_id="'.$notify_id.'">
					<div class="d-flex fs-13">
						<div class="flex-grow-1">
							<p class="mb-0">'.$val['content'].'.</p>
							<small class="text-muted">'.($send_date > $now ? $clsISO->getTimeMore($send_date) : $clsISO->getTimeAgo($send_date)).'</small>
						</div>
						<div class="flex-shrink-0 dropdown-notifications-actions">
							<a href="javascript:void(0)" onClick="$Core.notify.mark_read(this,event)" class="dropdown-notifications-read" notify_id="'.$notify_id.'"><span class="badge badge-dot"></span></a>
							<a href="javascript:void(0)" onClick="$Core.notify.delete(this,event)" class="dropdown-notifications-archive" notify_id="'.$notify_id.'"><span class="bx bx-x"></span></a>
						</div>
					</div>
				</li>';
			}
		}
		unset($list_notify);
	} else {
		if($holderG=='_globe'){
			$htmlNoty.= '<li>
				<div class="p-3 text-muted text-center">
					<i class="bx bx-bell bx-lg"></i>
					<p>Chưa có thông báo</p>
				</div>
			</li>';
		} else {
			$htmlNoty = '_empty';
		}
	}
	// Return
	echo json_encode(array(
		'totalNoty' => $totalNoty,
		'htmlNoty' => $htmlNoty
	)); die();
}
function notify_delete(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsNotify = new Notify();
	#
	$msg = "_error";
	$notify_id = (int) Input::post('notify_id', 0);
	if($clsNotify->deleteOne($notify_id)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function notify_mark_read(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsNotify = new Notify();
	$tp = Input::post('tp', 'MARK_READ');
	if($tp=='MARK_READ'){
		$notify_id = Input::post('notify_id', 0);
		$list_user_read = $clsNotify->getOneField('list_user_read', $notify_id);
		$list_user_read = !empty($list_user_read) 
			? $clsISO->getArrayByTextSlash($list_user_read) : array();
		$list_user_read[] = $profile_id;
		$list_user_read_slash = $clsISO->makeSlashListFromArray($list_user_read);
		$clsNotify->updateOne($notify_id, array(
			'list_user_read' => $list_user_read_slash
		));
	} else {
		$list_notify = $clsNotify->getAll("`list_user_slash` like '%|{$profile_id}|%' 
			and `list_user_read` not like '%|{$profile_id}|%' and (send_date<'".time()."' OR DATE_SUB(FROM_UNIXTIME(`send_date`),INTERVAL 12 HOUR)<'".date('Y-m-d H:i:s', time())."')", "{$clsNotify->pkey},list_user_read");
		if(!empty($list_notify)){
			foreach($list_notify as $key => $val){
				$list_user_read = $val['list_user_read'];
				$list_user_read = !empty($list_user_read) 
					? $clsISO->getArrayByTextSlash($list_user_read) 
					: array();
				$list_user_read[] = $profile_id;
				$list_user_read_slash = $clsISO->makeSlashListFromArray($list_user_read);
				$clsNotify->updateOne($val[$clsNotify->pkey], array(
					'list_user_read' => $list_user_read_slash
				));
			}
		}
	}
}
