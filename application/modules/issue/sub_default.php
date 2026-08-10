<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function default_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$arr_tabs = array(
		'today' => 'Hôm nay',
		'yesterday' => 'Hôm qua',
		'this_week' => 'Tuần này',
		'prev_week' => 'Tuần trước',
		'this_month' => 'Tháng này',
		'prev_month' => 'Tháng trước'
	);
	$smarty->assign("arr_tabs", $arr_tabs);
	###
	$tp = Input::get('tp', 'today');
	$smarty->assign("tp", $tp);
	/*=============Title & Description Page==================*/
	$title_page = 'Thống kê công việc | '. PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_issue_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsIssue = new Issue();
	$clsIssueCurrent = new IssueCurrent();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	
	$tp = Input::post('tp', 'today');
	$cond = "`is_trash`=0";
	if(!_issue_can_see_all()){
		$arr_staff_in = array();
		_issue_role_scope_ids($arr_staff_in);   // scope theo PHÒNG BAN (xem helper)
		if(!empty($arr_staff_in)){
			$cond.= " AND `assign_to_id` IN (".implode(',', $arr_staff_in).")";
		}
	}
	if($tp == 'today'){
		$cond.= " AND EXISTS (
			SELECT {$clsIssueCurrent->tbl}.`issue_id` FROM `{$clsIssueCurrent->tbl}` 
			WHERE `{$clsIssueCurrent->tbl}`.`start_date` <= (UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) - 1) AND IF(`{$clsIssueCurrent->tbl}`.`due_date` = 0, UNIX_TIMESTAMP(), `{$clsIssueCurrent->tbl}`.`due_date`) >= UNIX_TIMESTAMP(CURDATE()) AND {$clsIssue->tbl}.`issue_id`={$clsIssueCurrent->tbl}.`issue_id`
			GROUP BY {$clsIssueCurrent->tbl}.`issue_id`  
		)";
	} else if($tp == 'yesterday'){
		$cond.= " AND EXISTS (
			SELECT {$clsIssueCurrent->tbl}.`issue_id` FROM `{$clsIssueCurrent->tbl}` 
			WHERE `start_date` <= (UNIX_TIMESTAMP(CURDATE()) - 1) AND IF(`due_date` = 0, UNIX_TIMESTAMP(), `due_date`) >= UNIX_TIMESTAMP(CURDATE() - INTERVAL 1 DAY) AND {$clsIssue->tbl}.`issue_id`={$clsIssueCurrent->tbl}.`issue_id`
			GROUP BY {$clsIssueCurrent->tbl}.`issue_id`  
		)";
	} else if($tp == 'prev_week'){
		$cond.= " AND EXISTS (
			SELECT {$clsIssueCurrent->tbl}.`issue_id` FROM `{$clsIssueCurrent->tbl}` 
			WHERE `start_date` <= (UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY)) + 86399) AND IF(`due_date` = 0, UNIX_TIMESTAMP(), `due_date`) >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 7) DAY))
			AND {$clsIssue->tbl}.`issue_id`={$clsIssueCurrent->tbl}.`issue_id`
			GROUP BY {$clsIssueCurrent->tbl}.`issue_id`  
		)";
	} else if($tp == 'this_week'){
		$cond.= " AND EXISTS (
			SELECT {$clsIssueCurrent->tbl}.`issue_id` FROM `{$clsIssueCurrent->tbl}` 
			WHERE `start_date` <= (UNIX_TIMESTAMP(DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE())) DAY)) + 86399) AND IF(`due_date` = 0, UNIX_TIMESTAMP(), `due_date`) >= UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY))
			AND {$clsIssue->tbl}.`issue_id`={$clsIssueCurrent->tbl}.`issue_id`
			GROUP BY {$clsIssueCurrent->tbl}.`issue_id`  
		)";
	} else if($tp == 'this_month'){
		$cond.= " AND EXISTS (
			SELECT {$clsIssueCurrent->tbl}.`issue_id` FROM `{$clsIssueCurrent->tbl}` 
			WHERE `start_date` <= (UNIX_TIMESTAMP(LAST_DAY(CURDATE())) + 86399) AND IF(`due_date` = 0, UNIX_TIMESTAMP(), `due_date`) >= UNIX_TIMESTAMP(DATE_FORMAT(CURDATE(), '%Y-%m-01')) AND {$clsIssue->tbl}.`issue_id`={$clsIssueCurrent->tbl}.`issue_id`
			GROUP BY {$clsIssueCurrent->tbl}.`issue_id`
		)";
	} else if($tp == 'prev_month'){
		$cond.= " AND EXISTS (
			SELECT {$clsIssueCurrent->tbl}.`issue_id` FROM `{$clsIssueCurrent->tbl}` 
			WHERE `start_date` <= (UNIX_TIMESTAMP(LAST_DAY(CURDATE() - INTERVAL 1 MONTH)) + 86399) 
			AND IF(`due_date` = 0, UNIX_TIMESTAMP(), `due_date`) >= UNIX_TIMESTAMP(DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01')) 
			AND {$clsIssue->tbl}.`issue_id`={$clsIssueCurrent->tbl}.`issue_id`
			GROUP BY {$clsIssueCurrent->tbl}.`issue_id`
		)";
	}
	$order_by = " ORDER BY `reg_date` DESC";
	$field = "*";
	// $dbconn->debug = true;
	$list_issues = $clsIssue->getAll($cond.$order_by, $field);
	// $clsISO->print_pre($list_issues); die();
	if(!empty($list_issues)){
		$arr_profile_cached = array();
		foreach($list_issues as $key => $val){
			$state_name = "";
			if($val['reg_date'] >= $today_start_time && $val['reg_date'] <= $today_end_time){
				$state_name = ' <span class="badge bg-label-danger">Hôm nay</span>';
			} else if($val['reg_date'] >= $yesterday_start_time && $val['reg_date'] <= $yesterday_start_time){
				$state_name = ' <span class="badge bg-label-warning">Hôm qua</span>';
			} else if($val['reg_date'] > $week_start_time && $val['reg_date'] <= $week_end_time){
				$state_name = ' <span class="badge bg-label-primary">Tuần này</span>';
			} else if($val['reg_date'] > $prev_week_start_time && $val['reg_date'] <= $prev_week_end_time){
				$state_name = ' <span class="badge bg-label-success">Tuần trước</span>';
			}
			$list_issues[$key]['state_name'] = $state_name;
			$list_issues[$key]['time_do'] = $clsIssue->getTimeDo($val[$clsIssue->pkey]);
			$list_childs = $clsIssue->getIsssueChild($val[$clsIssue->pkey], "---");
			$list_issues[$key]['list_childs'] = $list_childs; 
		}
	}
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('list_issues', $list_issues);
	// Return
	$html = $core->build('_ajax.table_report.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_default(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	if(!$clsISO->checkPermission('issue_access')){
		$core->redirect('/');
	}
	##
	$scriptJs = '';
	$cmd = Input::get("cmd", "_default");
	if($cmd=="_detail"){
		$string = Input::get('issue_id', 0);
		$issue_id = !empty($string) ? $clsISO->base64url_decode($string) : 0;
		if($issue_id > 0){
			$scriptJs.= '<a class="autoclick_'.$issue_id.'"" issue_id="'.$issue_id.'" onClick="$Core.issue.view_issue(this, event)"></a>
			<script type="text/javascript">
				$(function(){
					setTimeout(() => {
						history.pushState("", "", "/issue.html");
						$(\'.autoclick_'.$issue_id.'\').trigger(\'click\');
					}, 500);
				})
			</script>';
		} else {
			$core->redirect('/');
		}
	}
	$smarty->assign("scriptJs",$scriptJs);
	##
	$_ss_per_page = 15;
	$_ss_sort_by = 'reg_date';
	if(vnSessionExist('_ss_sort_by')){
		$_ss_sort_by = vnSessionGetVar('_ss_sort_by');
	}
	if(vnSessionExist('_ss_per_page')){
		$_ss_per_page = vnSessionGetVar('_ss_per_page');
	}
	$more_information = $oneProfile["more_information"];
	$_ss_view = isset($more_information['_ss_view']) ? $more_information['_ss_view'] : 'grid';
	$smarty->assign('_ss_view', $_ss_view);
	$smarty->assign("_ss_sort_by",$_ss_sort_by);
	$smarty->assign("_ss_per_page",$_ss_per_page);
	$list_record_pages = array(30, 50, 100, 200);
	$smarty->assign("list_record_pages",$list_record_pages);
	#
	$list_issue_blocks = array(
		'_working' => array(
			'title' => 'Công việc đang thực hiện',
			'accent' => '#696cff',
			'delay' => 0
		),
		'_all' => array(
			'title' => 'Danh sách công việc',
			'accent' => '#d9dee3',
			'delay' => 80
		)
	);
	$smarty->assign("list_issue_blocks",$list_issue_blocks);
	$smarty->assign('clsProperty', new Property());
	/*=============Title & Description Page==================*/
	$title_page = 'Lịch công việc | '. PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_issue_kanban(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$assign_list;
	$clsIssue = new Issue(); $assign_list['clsIssue'] = $clsIssue;
	$clsIssueNote = new IssueNote();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	##
	$holderG 		= Input::post('holderG',"me");
	if($holderG == "") {
		$holderG = "me";
	}
	$is_all 	= (int) Input::post('is_all',0);
	$keysearch 		= Input::post('keysearch');
	$start_date 	= Input::post('start_date');
	$end_date 		= Input::post('end_date');
	$start_date_int = !empty($start_date) ? $clsISO->convertTextToTime($start_date) : 0;
	$end_date_int 	= !empty($end_date) ? $clsISO->convertTextToTime($end_date) : 0;
	$user_id 		= (int) Input::post('user_id', 0);
	$assign_to_id 	= (int) Input::post('assign_to_id', 0);
	$priority_id	= (int) Input::post('priority_id', 0);
	$status_id 		= (int) Input::post('status_id', 0);
	$sort_by		= Input::post('sort_by', 'reg_date');
	$type			= Input::post('type', '');
	$assign_list['type'] = $type;
	
	$assign_list['options'] = json_encode([
		"holderG"		=>	$holderG,
		"is_all"		=>	$is_all,
		"keysearch"		=>	$keysearch,
		"start_date"	=>	$start_date,
		"end_date"		=>	$end_date,
		"user_id"		=>	$user_id,
		"assign_to_id"	=>	$assign_to_id,
		"priority_id"	=>	$priority_id,
		"sort_by"		=>	$sort_by,
	]);
	##
	$cond = "`is_trash`=0 and `parent_id`=0";
	if($is_all==0) $cond.= " and `is_archived`='0'";
	if(!empty($keysearch)) $cond.= " and `slug` like '%{$core->replaceSpace($keysearch)}%'";
	if($user_id > 0 && $assign_to_id > 0) {
		$cond .= " and (`user_id`='{$user_id}' and `assign_to_id`='{$assign_to_id}')";
	} else if($user_id > 0 && $assign_to_id == 0){
		$cond .= " and `user_id`='{$user_id}'";
	} else if($user_id == 0 && $assign_to_id > 0){
		$cond .= " and `assign_to_id`='{$assign_to_id}'";
	} else {
		if($holderG=='me'){
			$cond.= " and (user_id='{$profile_id}' 
				or `assign_to_id`='{$profile_id}' 
				or `participants_slash` like '%|{$profile_id}|%'
			)";
		} else if($holderG=='assign'){
			$cond.= " and (`user_id`='{$profile_id}' and assign_to_id<>'{$profile_id}')";
		} else if($holderG=='related'){
			$cond.= " and (user_id<>'{$profile_id}' 
				and `assign_to_id`<>'{$profile_id}' 
				and `participants_slash` like '%|{$profile_id}|%'
			)";
		} else if($holderG=='_working'){
			if($clsISO->checkSale()) {
				$cond.= " and (`assign_to_id`='{$profile_id}' OR `participants_slash` LIKE '%|{$profile_id}|%' OR `user_id`='{$profile_id}')";
			}
			$cond.= " AND `status_id`='"._ISSUE_STATUS_DOING."'";
		}
	}
	if($priority_id > 0) $cond .= " and `priority_id`='{$priority_id}'";
	if($status_id > 0) $cond .= " and `status_id`='{$status_id}'";
	if($start_date_int > 0 && $end_date_int ==0){
		$cond.= " and (`reg_date`>='{$start_date_int}')";
	} else if($start_date_int ==0 && $end_date_int > 0){
		$cond.= " and (`reg_date`<='{$end_date_int}')";
	} else if($start_date_int >0 && $end_date_int > 0) {
		$cond.= " and (`reg_date`>='{$start_date_int}' and `reg_date`<='{$end_date_int}')";
	}
	# Scope cây vai trò (server-side, chống IDOR) — DIRECTOR & self-view bỏ qua
	$me = (int) $profile_id;
	$is_self_view = ($user_id==0 && $assign_to_id==0 && in_array($holderG, array('me','assign','related')));
	if(!$is_self_view && !_issue_can_see_all()){
		$arr_staff_in = array();
		_issue_role_scope_ids($arr_staff_in);
		if(!in_array($me, $arr_staff_in)) $arr_staff_in[] = $me;
		$cond .= " AND (`assign_to_id` IN (".implode(',', array_map('intval', $arr_staff_in)).") OR `assign_to_id`='{$me}' OR `user_id`='{$me}' OR `participants_slash` LIKE '%|{$me}|%')";
	}
	if((int) Input::post('mine', 0) == 1){ $cond.= " AND `assign_to_id`='{$me}'"; }
//	echo $cond;die;
	#Pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',15);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- Session
	vnSessionSetVar('_ss_sort_by', $sort_by);
	vnSessionSetVar('_ss_per_page', $per_page);
	#- End Session
	$order_by = " order by `status_id` ASC";
	if($sort_by=='reg_date'){
		$order_by = " order by `reg_date` DESC";
	}
	
	$dataContent = $data = [];
	$assign_list['prMap'] = array(191 => 'Thấp', 192 => 'Bình thường', 193 => 'Cao', 194 => 'Khẩn cấp');
	$listStatus = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_ISSUE_STATUS'");
//	var_dump($listStatus);die;
	if($type == "load_more"){
		$status = (int) Input::post('status',0);
		if($status > 0) $cond .= " and `status_id`='{$status}'";
		$condStatus =" and status_id ='{$status}'";
		$total_record = $clsIssue->countItem($cond.$condStatus);
		$total_page = @ceil($total_record/$per_page);
//		$clsIssue->setDeBug(1);
		$lstIssue = $clsIssue->getAll($cond.$condStatus.$order_by.$limitCond,$clsIssue->pkey.',title,start_date,end_date,status_id,priority_id,user_id,assign_to_id,done_ratio');
//		var_dump($lstIssue);die;
		$assign_list['lstIssue'] = $lstIssue;
		$assign_list['data'] = $data;
		$html = $core->build("_ajax.issue_kanban.tpl");
		echo json_encode(array(
			'uid' 			=> $uid,
			'type' 			=> $type,
			'html' 			=> $html,
			'total_page'	=> $total_page,
			'current_page'	=> $current_page,
			'status_id'		=> $status,
		)); die();
	}else{
		foreach($listStatus as $key => $value) {
			$data[$value["property_id"]]["id"] = $value["property_id"];
			$data[$value["property_id"]]["title"] = $value["title"];
			$data[$value["property_id"]]["textcolor"] = $value["textcolor"];
			$condStatus =" and status_id ='{$value["property_id"]}'";
			$total_record = $clsIssue->countItem($cond.$condStatus);
			$data[$value["property_id"]]["total_record"] = $total_record;
			$total_page = @ceil($total_record/$per_page);
//			$clsIssue->setDeBug(1);
			$list_issues = $clsIssue->getAll($cond.$condStatus.$order_by.$limitCond,$clsIssue->pkey.',title,start_date,end_date,status_id,priority_id,user_id,assign_to_id,done_ratio');
//			var_dump($list_issues);die;
			if(!empty($list_issues)){
				$arr_profile_cached = array();
				foreach($list_issues as $key => $val){
					$list_childs = $clsIssue->getIsssueChild($val[$clsIssue->pkey], "---");
					$list_issues[$key]['list_childs'] = $list_childs; 
				}
			}
			$data[$value["property_id"]]["item"] = $list_issues;
			/*foreach($list_issues as $k => $val) {
				$data[$val["status_id"]]["item"][$val["issue_id"]] = $val;
			}*/
			unset($list_issues,$total_record);
		}
//		var_dump($data);die;
		$assign_list['data'] = $data;
		$html = $core->build("_ajax.issue_kanban.tpl");
		echo json_encode(array(
			'uid' 			=> $uid,
			'type' 			=> $type,
			'dataContent' 	=> $dataContent,
			'html' 			=> $html,
		)); die();
	}	
}
function default_load_issues(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('clsIssue', $clsIssue);
	##
	$current_date = date('d-m-Y');
	$yesterday_date = date('d-m-Y', strtotime('-1 day'));
	$today_start_time = strtotime($current_date);
	$today_end_time = strtotime(sprintf('%s 23:59:59', $current_date));
	$yesterday_start_time = strtotime($yesterday_date);
	$yesterday_end_time = strtotime(sprintf('%s 23:59:59', $yesterday_date));
	$week_start_time = strtotime('monday this week 00:00:00');
	$week_end_time = strtotime('sunday this week 23:59:59');
	$prev_week_start_time = strtotime('monday last week 00:00:00');
	$prev_week_end_time = strtotime('sunday last week  23:59:59');
	##
	$holderG 		= Input::post('holderG',"me");
	$is_all 		= (int) Input::post('is_all',0);
	$keysearch 		= Input::post('keysearch');
	$start_date 	= Input::post('start_date');
	$end_date 		= Input::post('end_date');
	$start_date_int = !empty($start_date) ? $clsISO->convertTextToTime($start_date) : 0;
	$end_date_int 	= !empty($end_date) ? $clsISO->convertTextToTime($end_date) : 0;
	$user_id 		= (int) Input::post('user_id', 0);
	$assign_to_id 	= (int) Input::post('assign_to_id', 0);
	$priority_id	= (int) Input::post('priority_id', 0);
	$status_id 		= (int) Input::post('status_id', 0);
	$sort_by		= Input::post('sort_by', 'reg_date');
	##
	if($holderG== '_child'){
		$issue_id = (int) Input::post('issue_id', 0);
		$cond = "`is_trash`=0 AND `parent_id`={$issue_id}";
	} else {
		$cond = "`is_trash`=0 AND `parent_id`=0";
	}
	$me = (int) $profile_id;
	// GATE _child (chống leak xuyên phòng): chỉ xem việc con nếu được xem việc CHA.
	if($holderG == '_child' && !_issue_can_see_all()){
		$clsIssueGate = new Issue();
		$oParent = $clsIssueGate->getOne($issue_id, "user_id,assign_to_id,participants_slash");
		$arr_scope = array();
		_issue_role_scope_ids($arr_scope);
		$can_parent = (!empty($oParent) && (
			in_array((int) $oParent['assign_to_id'], $arr_scope, true)
			|| (int) $oParent['user_id'] === $me
			|| (int) $oParent['assign_to_id'] === $me
			|| strpos((string) $oParent['participants_slash'], '|'.$me.'|') !== false
		));
		if(!$can_parent){ $cond = "`is_trash`=0 AND `parent_id`=-1"; }   // không được phép xem cha → 0 dòng
	}
	$personal_lenses = array('_my_overdue','_my_today','_my_week','_assigned_by_me','_following');
	$is_personal = in_array($holderG, $personal_lenses);
	if($holderG != "_child" && !$is_personal && !_issue_can_see_all()){
		$arr_staff_in = array();
		_issue_role_scope_ids($arr_staff_in);
		if(!empty($arr_staff_in)){
			$cond.= " AND (`assign_to_id` IN (".implode(',', $arr_staff_in).") OR `assign_to_id`='{$me}' OR `user_id`='{$me}' OR `participants_slash` LIKE '%|{$me}|%')";
		}
	}
	# Nút "Việc của tôi" — lọc việc giao cho tôi (tự giao hoặc người khác giao)
	if($holderG != "_child" && (int) Input::post('mine', 0) == 1){ $cond.= " AND `assign_to_id`='{$me}'"; }
	# Lens cá nhân "Việc của tôi" — tự scope theo người đăng nhập (server-side, chống IDOR)
	if($is_personal){
		$done_ids = "206,"._ISSUE_STATUS_COMPLETED; // 206=Đã đóng, 207=Hoàn thành
		if($holderG=='_assigned_by_me'){
			$cond.= " AND `user_id`='{$me}' AND `assign_to_id`<>'{$me}'";
		} else if($holderG=='_following'){
			$cond.= " AND `user_id`<>'{$me}' AND `assign_to_id`<>'{$me}' AND `participants_slash` LIKE '%|{$me}|%'";
		} else {
			$cond.= " AND (`assign_to_id`='{$me}' OR `participants_slash` LIKE '%|{$me}|%')";
			if($holderG=='_my_overdue'){
				$cond.= " AND `status_id` NOT IN ({$done_ids}) AND `end_date`>0 AND `end_date`<".time();
			} else if($holderG=='_my_today'){
				$cond.= " AND `end_date` BETWEEN {$today_start_time} AND {$today_end_time}";
			} else if($holderG=='_my_week'){
				$cond.= " AND `end_date` BETWEEN {$week_start_time} AND {$week_end_time}";
			}
		}
	}
	if($is_all==0) $cond.= " and `is_archived`='0'";
	if(!empty($keysearch)) $cond.= " and `slug` like '%{$core->replaceSpace($keysearch)}%'";
	if($user_id > 0 && $assign_to_id > 0) {
		$cond .= " and (`user_id`='{$user_id}' and `assign_to_id`='{$assign_to_id}')";
	} else if($user_id > 0 && $assign_to_id == 0){
		$cond .= " and `user_id`='{$user_id}'";
	} else if($user_id == 0 && $assign_to_id > 0){
		$cond .= " and `assign_to_id`='{$assign_to_id}'";
	} else {
		if($holderG=='_working'){
			if($clsISO->checkSale()) {
				$cond.= " and (`assign_to_id`='{$profile_id}' OR `participants_slash` LIKE '%|{$profile_id}|%' OR `user_id`='{$profile_id}')";
			}
			$cond.= " AND `status_id`='"._ISSUE_STATUS_DOING."'";
		} else if(!$is_personal) {
			$cond.= " AND `status_id`<>'"._ISSUE_STATUS_DOING."'";
		}
	}
	if($priority_id > 0) $cond .= " and `priority_id`='{$priority_id}'";
	if($status_id > 0) $cond .= " and `status_id`='{$status_id}'";
	if($start_date_int > 0 && $end_date_int ==0){
		$cond.= " and (`reg_date`>='{$start_date_int}')";
	} else if($start_date_int ==0 && $end_date_int > 0){
		$cond.= " and (`reg_date`<='{$end_date_int}')";
	} else if($start_date_int >0 && $end_date_int > 0) {
		$cond.= " and (`reg_date`>='{$start_date_int}' and `reg_date`<='{$end_date_int}')";
	}
	#Pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',30);
	$total_record = $clsIssue->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- Session
	vnSessionSetVar('_ss_sort_by', $sort_by);
	vnSessionSetVar('_ss_per_page', $per_page);
	#- End Session
	$order_by = " order by `status_id` ASC";
	if($sort_by=='reg_date'){
		$order_by = " order by `reg_date` DESC";
	}
	$list_issues = $clsIssue->getAll($cond.$order_by.$limitCond);
	if(!empty($list_issues)){
		$arr_profile_cached = array();
		foreach($list_issues as $key => $val){
			$state_name = "";
			if($val['reg_date'] >= $today_start_time && $val['reg_date'] <= $today_end_time){
				$state_name = ' <span class="badge bg-label-danger">Hôm nay</span>';
			} else if($val['reg_date'] >= $yesterday_start_time && $val['reg_date'] <= $yesterday_start_time){
				$state_name = ' <span class="badge bg-label-warning">Hôm qua</span>';
			} else if($val['reg_date'] > $week_start_time && $val['reg_date'] <= $week_end_time){
				$state_name = ' <span class="badge bg-label-primary">Tuần này</span>';
			} else if($val['reg_date'] > $prev_week_start_time && $val['reg_date'] <= $prev_week_end_time){
				$state_name = ' <span class="badge bg-label-success">Tuần trước</span>';
			}
			if($val['end_date'] > 0 && $val['end_date'] < time() && !in_array($val['status_id'], array(206, _ISSUE_STATUS_COMPLETED))){
				$_od = floor((time()-$val['end_date'])/86400);
				$state_name .= ' <span class="badge bg-label-danger ms-1">Quá hạn'.($_od>0?' '.$_od.'d':'').'</span>';
			}
			$list_issues[$key]['state_name'] = $state_name;
			$list_childs = $clsIssue->getIsssueChild($val[$clsIssue->pkey], "<i class='bx bx-subdirectory-right'></i>");
			$list_issues[$key]['list_childs'] = $list_childs; 
		}
	}
	$smarty->assign('holderG', $holderG);
	$smarty->assign('list_issues', $list_issues);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('current_page', $current_page);
	# Map màu trạng thái / ưu tiên theo thiết kế (cho _ajax.list_issue.tpl)
	$smarty->assign('stMap', array(
		_ISSUE_STATUS_NEW       => array('label'=>'Mới','dot'=>'#ff3e1d','fg'=>'#ff3e1d','bg'=>'#ffe0db'),
		_ISSUE_STATUS_DOING     => array('label'=>'Đang xử lý','dot'=>'#696cff','fg'=>'#696cff','bg'=>'#e7e7ff'),
		_ISSUE_STATUS_STOP      => array('label'=>'Tạm ngưng','dot'=>'#8592a3','fg'=>'#697a8d','bg'=>'#eef0f2'),
		206                     => array('label'=>'Đã đóng','dot'=>'#71dd37','fg'=>'#67ad2e','bg'=>'#e8fadf'),
		_ISSUE_STATUS_COMPLETED => array('label'=>'Hoàn thành','dot'=>'#71dd37','fg'=>'#67ad2e','bg'=>'#e8fadf'),
	));
	$smarty->assign('prMap', array(
		191 => array('label'=>'Thấp','color'=>'#8592a3'),
		192 => array('label'=>'Bình thường','color'=>'#1971c2'),
		193 => array('label'=>'Cao','color'=>'#ffab00'),
		194 => array('label'=>'Khẩn cấp','color'=>'#ff3e1d'),
	));
	// Return
	$html = $core->build('_ajax.list_issue.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	)); die();
}
function default_open_issue(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsIssue = new Issue();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$field = "{$clsProfile->pkey},full_name,code";
	$list_staffs = $clsProfile->getAll("is_trash=0 and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."' 
	and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by code ASC", $field);
	$smarty->assign('list_staffs', $list_staffs);
	###
	$start_date = time();
	$end_date = strtotime("+1 hours");
	$issue_id = (int) Input::post('issue_id', 0);
	$parent_id = (int) Input::post('parent_id', 0);
	$issue_type = Input::post('issue_type', "_addissue");
	$department_id = $oneProfile['department_id'];
	$smarty->assign('issue_id', $issue_id);
	$smarty->assign('parent_id', $parent_id);
	$smarty->assign('issue_type', $issue_type);
	$smarty->assign('start_date', $start_date);
	$smarty->assign('end_date', $end_date);
	$smarty->assign('department_id', $department_id);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.issue.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_view_issue(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	// ini_set('display_errors',1);
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsIssueTask = new IssueTask();
	$clsIssueTarget = new IssueTarget();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsIssueNote', $clsIssueNote);
	$smarty->assign('clsIssueTarget', $clsIssueTarget);
	$uid = $clsISO->getUniqid();
	#
	$issue_id = (int) Input::post('issue_id', 0);
	$department_id = $oneProfile['department_id'];
	$oneIssue = $clsIssue->getOne($issue_id);
	$issue_target_id = (int) $oneIssue['issue_target_id'];
	$issue_target_name = "Thêm mục tiêu";
	if($issue_target_id > 0){
		$issue_target_name = $clsIssueTarget->getTitle($issue_target_id);
	}
	$oneIssue['issue_target_name'] = $issue_target_name;
	$participants = $oneIssue['participants'];
	$participants_arr = $clsISO->to_array_json($participants);
	$smarty->assign('department_id', $department_id);
	$smarty->assign('issue_id', $issue_id);
	$smarty->assign('oneIssue', $oneIssue);
	###
	$permiss_edit = $permiss_action = $permiss_task = 0;
	if($oneIssue['user_id'] == $profile_id){
		$permiss_edit = 1;
		$permiss_task = 1;
	}
	if($oneIssue['assign_to_id'] == $profile_id){
		$permiss_action = 1;
		$permiss_task = 1;
	}
	if(!empty($participants_arr) && in_array($profile_id, $participants_arr)){
		$permiss_task = 1;
	}
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_action', $permiss_action);
	$smarty->assign('permiss_task', $permiss_task);
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_issue_edit(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$toId = Input::post('toId', "");
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field');
	$smarty->assign('uid', $uid);
	$smarty->assign('toId', $toId);
	$smarty->assign('p_id', $p_id);
	$smarty->assign('p_field', $p_field);
	
	$oneIssue = $clsIssue->getOne($p_id);
	if($p_field=='participants'){
		$participants = $oneIssue['participants'];
		$participants_arrs = !empty($participants) 
			? json_decode(html_entity_decode($participants), true) : array();
		//$clsISO->print_pre($participants_arrs); die();
		$html_options = "";
		if(!empty($participants_arrs)){
			foreach($participants_arrs as $user_id){
				$html_options.= sprintf('<option value="%s" selected="selected">%s</option>', $user_id, $clsProfile->getIndentity($user_id, false));
			}
		}
		$smarty->assign('html_options', $html_options);
	}
	$smarty->assign('oneIssue', $oneIssue);
	// Return
	$html = $core->build('_ajax.issue_edit.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_issue_edit(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	#
	$toId = Input::post('toId');
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field');
	$p_value = Input::post($p_field);
	#
	$html = ""; $msg = "_error";
	$oneIssueOld = $clsIssue->getOne($p_id, $p_field);
	if($p_field=='participants'){
		$set = array();
		if(!empty($p_value)){
			$p_value_json = json_encode($p_value);
			$participants_slash = $clsISO->makeSlashListFromArrayRoot($p_value);
		} else {
			$p_value_json = "";
			$participants_slash = "";
		}
		$set = array(
			$p_field => $p_value_json, 
			'participants_slash' => $participants_slash
		);
	} else if($p_field=='start_date' || $p_field=='end_date'){
		$p_date = !empty($p_value) ? $clsISO->toTime($p_value) : 0;
		$set = array($p_field => $p_date);
	} else {
		$set = array($p_field => $p_value);
		if($p_field=='status_id'){
			// Cập nhật trang thái các issue B -> tạm ngưng, issue A -> Đang xử lý
			if($p_value==_ISSUE_STATUS_DOING){
				$field = "{$clsIssue->pkey},`status_id`";
				$list_doing_issues = $clsIssue->getAll("`is_trash`=0 and `{$clsIssue->pkey}`<>'{$p_id}' 
				and `assign_to_id`='{$profile_id}' 
				and `status_id`='"._ISSUE_STATUS_DOING."'", $field);
				if(!empty($list_doing_issues)){
					foreach($list_doing_issues as $issue){
						if($clsIssue->updateOne($issue[$clsIssue->pkey], array(
							'status_id' => _ISSUE_STATUS_STOP
						))){
							$html_change = sprintf('<li><strong>%s</strong> vừa mới thay đổi tình trạng công việc 
								từ <strong>%s</strong> tới <strong>%s</strong></li>', 
								$clsProfile->getFullName($profile_id, $oneProfile), 
								$clsProperty->getTitle($issue['status_id']), 
								$clsProperty->getTitle(_ISSUE_STATUS_STOP)
							);
							$clsIssueNote->insert(array(
								'issue_note_id' => $clsIssueNote->getMaxId(),
								'issue_id' => $issue[$clsIssue->pkey],
								'html_change' => $html_change,
								'user_id' => $profile_id,
								'user_id_update' => $profile_id,
								'reg_date' => time(),
								'upd_date' => time()
							));
						}
					}
					unset($list_doing_issues);
				}
			}
			if((int) $p_value == _ISSUE_STATUS_COMPLETED){
				$set['done_ratio'] = 100;
			}
		}
	}
	if($clsIssue->updateOne($p_id, $set)){
		$msg = "_success";
		$oneIssue = $clsIssue->getOne($p_id);
		if($p_field=='assign_to_id'){
			$html.= $clsProfile->getIndentityV4($p_value);
			$html_change = sprintf('<li><strong>%s</strong> vừa mới thay đổi người nhân việc từ <strong>%s</strong> tới <strong>%s</strong></li>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsProfile->getFullName($oneIssueOld['assign_to_id']), 
				$clsProfile->getFullName($oneIssue['assign_to_id'])
			);
			$clsIssueNote->insert(array(
				'issue_note_id' => $clsIssueNote->getMaxId(),
				'issue_id' => $p_id,
				'html_change' => $html_change,
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			));
		} else if($p_field=='participants'){
			$tmp = explode('_', $toId);
			$html = $clsIssue->getImplementer($issue_id, $tmp[1], $oneIssue);
		} else if($p_field=='status_id' || $p_field=='priority_id'){
			$titleNoty = sprintf('<strong>%s</strong> vừa mới thay đổi '.($p_field=='status_id'?'tình trạng':'độ ưu tiên').' công việc từ <strong>%s</strong> tới <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsProperty->getTitle($oneIssueOld[$p_field]), 
				$clsProperty->getTitle($oneIssue[$p_field])
			);
			// Bắn thông báo
			if($p_field == 'status_id'){
				$subscribers = array();
				$title = sprintf('Công việc [%s]', $oneIssue['title']);
				$list_user_notify = $clsIssue->getUserNotifierUpgrade($issue_id, $oneIssue);
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
					and `user_id` in (".implode(',', $list_user_notify).") and `token`<>''", "token");	
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						if(!in_array($val['token'], $subscribers)){
							$subscribers[] = $val['token'];
						}
					}
					$clsNotify->send_subscriber_notification(array(
						'title' => $title,
						'message' => strip_tags($titleNoty),
						'url' => $clsIssue->getLink($p_id)
					), $subscribers);
				}
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => $title,
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsIssue->getLink($p_id)
				];
				$clsNotification->doPushMessagingUser($params,$list_user_notify);
			}
			$html_change = sprintf('<li>%s</li>', $titleNoty);
			$clsIssueNote->insert(array(
				'issue_note_id' => $clsIssueNote->getMaxId(),
				'issue_id' => $p_id,
				'html_change' => $html_change,
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			));
			if($p_field=='status_id'){
				$html = $clsIssue->getStatus($oneIssue[$p_field]);
			} else {
				$html = $clsIssue->getPriority($oneIssue[$p_field]);
			}
		} else if($p_field=='start_date' || $p_field=='end_date'){
			$html = $p_value;
		}  else {
			$html = html_entity_decode($oneIssue[$p_field]);
		}
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Issue","update");
	} 
	// Return
	echo $msg.'|||'.$html; die();
}
function default_load_issue_notes(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsIssueTask = new IssueTask();
	###
    $issue_id = (int) Input::post('issue_id',0);
    $clsIssueNote->updateByCond("issue_id='$issue_id'","is_read='1'");
	###
    $list_notes = $clsIssueNote->getAll("is_trash=0 and issue_id='{$issue_id}' order by reg_date desc");
    $html = '<div class="history">';
    if(!empty($list_notes)){ $ii=0; // Init
        foreach($list_notes as $key => $val){
            $user_id = $val['user_id'];
			$issue_note_id = $val[$clsIssueNote->pkey];
			
			$html_mention = "";
			$mention_list = $val['mention_list'];
			$mention_list_arrs = !empty($mention_list) 
				? json_decode(html_entity_decode($mention_list), true) 
				: array();
			if(!empty($mention_list_arrs)){ $ii= 0;
				$html_mention.= ' cùng với &nbsp;';
				foreach($mention_list_arrs as $usr_id){
					$html_mention.= ($ii==0?'':', ') . sprintf(
						'<span class="text-danger">%s</span>', 
						$clsProfile->getIndentity($usr_id, false)
					);
					++$ii;
				}
			}
			$html_attachments = "";
			$attachments = $val['attachments'];
			$list_files = !empty($attachments) 
				? json_decode(html_entity_decode($attachments), true) 
				: array();
			if(!empty($list_files)){
				$html_attachments.= '<div class="MultiFile-preview my-2">';
				foreach($list_files as $file){
					$html_attachments.= '<div class="MultiFile-label">
						<a class="MultiFile-title" href="'.$file.'" download>'.formatNameFile(basename(ABSPATH.$file)).'</a>
					</div>';
				}
				$html.= '</div>';
			}
            $html.= '<div class="phpbox noteInList">
            <div class="phpbox-title d-flex align-items-center justify-content-between">
				<div class="p__pleft fs-13">
					'.($user_id==$profile_id?'<strong>Bạn</strong>':'<img class="avatar avatar-xs mr-2 rounded-pill" src="'.$clsProfile->getAvatar($user_id).'" /> <strong class="mr-1">'. $clsProfile->getFullName($user_id).'</strong>').' viết vào lúc <font class="text-danger fs-12">'.$clsISO->formatDate($val['reg_date'],4).'</font>'.$html_mention.'
				</div>
                <div class="p__right dropdown right pull-right">
                    <a class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"></a>
                    <div class="dropdown-menu">
                        '.($profile_id==$user_id?'<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.issue.open_issue_notes(this, event)" tp="edit" issue_id="'.$issue_id.'" issue_note_id="'.$issue_note_id.'">'.$core->makeIcon('pencil',"Sửa").'</a>':'').'
                        <a href="javascript:void(0);" class="dropdown-item" onClick="$Core.issue.open_issue_notes(this, event)" tp="quote" issue_id="'.$issue_id.'" issue_note_id="'.$issue_note_id.'">'.$core->makeIcon('comments-o',"Phản hồi").'</a>
						'.($profile_id==$user_id?'<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.issue.delete_notes(this, event)" issue_id="'.$issue_id.'" issue_note_id="'.$issue_note_id.'">'.$core->makeIcon('trash-o',"Xóa").'</a>':'').'
                    </div>
                </div>
            </div>
            <div class="phpbox-body timyContent">
                '.(!empty($val['html_change'])?'
					<ul class="mb-2 pl-2">'.html_entity_decode($val['html_change']).'</ul>
				':'') . html_entity_decode($val['content']).'
				'.$html_attachments.'
            </div>
            '.$clsIssueNote->getQuoteShow($issue_note_id);
			$html.= '</div>';
            ++$ii;
        }
    }else{
        $html .= '<div class="issue-dw-empty">Chưa có hoạt động nào.</div>';
    }
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_issue_participants(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile();
	#
	$html = "";
	$participants = Input::post('participants', array());
	if(!empty($participants)){
		foreach($participants as $profile_id){
			$html.= '<div class="issue_participant">
				'.$clsProfile->getIndentityV3($profile_id, true).'
			</div>';
		}
	}
	// Return
	echo $html; die();
}
function default_pop_save_issue(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsNotify = new Notify();
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsIssueCurrent = new IssueCurrent();
	$clsProfile = new Profile();
	$clsFcmToken = new FcmToken();
	$clsEmailTemplate = new EmailTemplate();	
	$clsProperty = new Property();
	$clsNotification = new Notification();
	###
	$msg = "_error";
	$holderG = "_all";
	$issue_id = (int) Input::post('issue_id', 0);
	$parent_id = (int) Input::post('parent_id', 0);
	$title = Input::post('title');
	$content = Input::post('content');
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_date = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	$issue_target_id = (int) Input::post('issue_target_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$type_id = (int) Input::post('type_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$priority_id = (int) Input::post('priority_id', 0);
	$assign_to_id = (int) Input::post('assign_to_id', 0);
	$participants = Input::post('participants', array());
	$customer_id = (int) Input::post('customer_id', 0);
	if($profile_id != $assign_to_id){
		$status_id = _ISSUE_STATUS_NEW;
	}
	###
	$_valid = 1;
	if($start_date >= $end_date){
		$_valid = 0;
		$errors[] = "Ngày bắt đầu & kết thúc không hợp lệ";
	}
	###
	if($_valid == 1){
		$more = array();
		if(!empty($participants)){
			$participants_slash = $clsISO->makeSlashListFromArrayRoot($participants);
			$more['participants'] = json_encode($participants, JSON_UNESCAPED_UNICODE);
			$more['participants_slash'] = $participants_slash;
		}
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){
			for($i=0; $i<count($_FILES['attachments']['name']); $i++){
				$files = array();
				$files["name"] = $_FILES['attachments']['name'][$i];
				$files["type"] = $_FILES['attachments']['type'][$i];
				$files["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$files["error"] = $_FILES['attachments']['error'][$i];
				$files["size"] = $_FILES['attachments']['size'][$i];
				#
				$up = '';
				if(@is_uploaded_file($files["tmp_name"])){
					$clsUploadFile = new UploadFile();
					$up = $clsUploadFile->uploadItem($files, "/attachments", EXTENSION_FILE_UPLOAD);
					if(!empty($up) && @file_exists(ABSPATH.$up)){
						$attachments[] = $up; 
					}
				}
			}
		}
		if(!empty($attachments)){
			$more['attachments'] =  json_encode($attachments, JSON_UNESCAPED_UNICODE);
		}
		if($issue_id == 0){
			$issue_id = $clsIssue->getMaxId();
			if($clsIssue->insert(array_merge($more, array(
				'issue_id' => $issue_id,
				'parent_id' => $parent_id,
				'title' => $title,
				'slug' => $core->replaceSpace($title),
				'content' => $content,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'project_id' => $project_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'priority_id' => $priority_id,
				'assign_to_id' => $assign_to_id,
				'issue_target_id' => $issue_target_id,
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			)))){
				$msg = "_success";
				$oneIssue = $clsIssue->getOne($issue_id);
				$_field = "`full_name`,`first_name`,`last_name`,`more_information`,`phone`";
				$_oProfile = $clsProfile->getOne($assign_to_id, $_field);
				if($assign_to_id == $profile_id){
					$html_change = sprintf('<li><strong>%s</strong> vừa mới tự giao một việc cho mình</li>', 
						$clsProfile->getFullName($oneIssue['user_id'])
					);
				} else {
					$html_change = sprintf('<li><strong>%s</strong> vừa mới giao một việc cho <strong>%s</strong></li>', 
						$clsProfile->getFullName($oneIssue['user_id']), 
						$clsProfile->getFullName($oneIssue['assign_to_id'])
					);
				}
				if($status_id==_ISSUE_STATUS_DOING){
					/** Stop current issue */
					$QueryBuilder = DB::table($clsIssue->tbl);
					$QueryBuilder->select("{$clsIssue->pkey},status_id");
					$QueryBuilder->where('is_trash', 0);
					$QueryBuilder->where('assign_to_id', $profile_id);
					$QueryBuilder->where('status_id', _ISSUE_STATUS_DOING);
					$QueryBuilder->where($clsIssue->pkey, $issue_id, "<>");
					$list_issue_doings = $QueryBuilder->get();
					if(!empty($list_issue_doings)){
						foreach($list_issue_doings as $issue){
							if($clsIssue->updateOne($issue[$clsIssue->pkey], array(
								'upd_date' => time(),
								'status_id' => _ISSUE_STATUS_STOP
							))){
								$html_change = sprintf('<li><strong>%s</strong> vừa mới thay đổi tình trạng công việc 
									từ <strong>%s</strong> tới <strong>%s</strong></li>', 
									$clsProfile->getFullName($profile_id, $oneProfile), 
									$clsProperty->getTitle($issue['status_id']), 
									$clsProperty->getTitle(_ISSUE_STATUS_STOP)
								);
								$clsIssueNote->insert(array(
									'issue_note_id' => $clsIssueNote->getMaxId(),
									'issue_id' => $issue[$clsIssue->pkey],
									'html_change' => $html_change,
									'user_id' => $profile_id,
									'user_id_update' => $profile_id,
									'reg_date' => time(),
									'upd_date' => time()
								));
							}
						}
						unset($list_doings);
					}
					/** End tracking time all task current */
					$clsIssueCurrent->updateByCond("`user_id`='{$profile_id}' and `due_date`='0'","`due_date`='".time()."'");
					/** Start task current */
					$clsProfile->updateOne($profile_id,"`issue_current_id`='{$issue_id}'");
					/** Logs task current */
					$issue_current_id = $clsIssueCurrent->getMaxId(); 
					$clsIssueCurrent->insert(array(
						$clsIssueCurrent->pkey => $issue_current_id,
						'issue_id' => $issue_id,
						'user_id' => $profile_id,
						'start_date' => time()
					));
				}
				$clsIssueNote->insert(array(
					'issue_note_id' => $clsIssueNote->getMaxId(),
					'issue_id' => $issue_id,
					'html_change' => $html_change,
					'user_id' => $profile_id,
					'user_id_update' => $profile_id,
					'reg_date' => time(),
					'upd_date' => time()
				));
				if($assign_to_id != $oneIssue['user_id']){
					#- Send Email
					$clsIssue->sendEmail($issue_id);
					#- Send Notification
					if(!empty($participants)){
						$titleNoty = sprintf('<strong>%s</strong> giao cho <strong>%s</strong> công việc [<strong>%s</strong>]', 
							$clsProfile->getFullName($profile_id, $oneProfile), 
							$clsProfile->getFullName($assign_to_id, $_oProfile), $title);
						$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $titleNoty, 
							time(), sprintf('|%s|', implode('|', $participants)));
						#thong bao app
						$params = [
							'title' => "Thông báo công việc mới",
							'body' => strip_tags($titleNoty),
							'link' => PCMS_URL . $clsIssue->getLink($issue_id)
						];
						$clsNotification->doPushMessagingUser($params,$participants);
					}
					$titleNoty = sprintf('<strong>%s</strong> giao việc cho bạn [<strong>%s</strong>]', 
						$clsProfile->getFullName($profile_id, $oneProfile), $title);
					$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $titleNoty, 
						time(), sprintf('|%s|', $assign_to_id));
					#thong bao app
					$params = [
						'title' => "Thông báo công việc mới",
						'body' => strip_tags($titleNoty),
						'link' => PCMS_URL . $clsIssue->getLink($issue_id)
					];
					$clsNotification->doPushMessagingUser($params,[$assign_to_id]);
					#- Send Zalo
					$zaloId = $clsProfile->getZaloId($assign_to_id, $_oProfile); 
					if(!empty($zaloId)){
						$clsZalo = new Zalo();
						$message = "Xin chào ".$clsProfile->getFullName($assign_to_id, $_oProfile)."\r".strip_tags($titleNoty)."\rHãy truy cập ".$clsIssue->getLink($issue_id)." để xem và thực hiện công việc!";
						$clsZalo->sendMsgSchedule($zaloId, $_oProfile['phone'], $message);
					}
					/** Gửi thông báo tới người nhận việc */
					$subscribers = array();
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
						and `user_id`='{$assign_to_id}' and `token`<>''", "token");		
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if(!in_array($val['token'], $subscribers)){
								$subscribers[] = $val['token'];
							}
						}
						$clsNotify->send_subscriber_notification(array(
							'title' => "Thông báo công việc mới",
							'message' => strip_tags($titleNoty),
							'url' => $clsIssue->getLink($issue_id)
						), $subscribers);
					}
					if(!empty($participants)){
						$titleNoty = sprintf('<strong>%s</strong> giao cho <strong>%s</strong> công việc [<strong>%s</strong>]', 
							$clsProfile->getFullName($profile_id, $oneProfile),
							$clsProfile->getFullName($assign_to_id, $_oProfile), $title);
						$subscribers = array();
						$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
							and `user_id` in (".implode(',', $participants).") and `token`<>''", "token");		
						if(!empty($tmp)){
							foreach($tmp as $key => $val){
								if(!in_array($val['token'], $subscribers)){
									$subscribers[] = $val['token'];
								}
							}
							$clsNotify->send_subscriber_notification(array(
								'title' => "Thông báo công việc mới",
								'message' => strip_tags($titleNoty),
								'url' => $clsIssue->getLink($issue_id)
							), $subscribers);
						}
					}
				} else if(!empty($participants)) {
					$titleNoty = sprintf('<strong>%s</strong> giao việc cho chính mình [<strong>%s</strong>]', 
						$clsProfile->getFullName($assign_to_id, $_oProfile), $title);
					$clsNotify->insertNotify('Issue',$clsIssue->pkey, $issue_id, $titleNoty, time(), $participants);
					/** Gửi thông báo tới người nhận việc */
					$subscribers = array();
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
						and `user_id` in (".implode(',', $participants).") and `token`<>''", "token");		
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if(!in_array($val['token'], $subscribers)){
								$subscribers[] = $val['token'];
							}
						}
						$clsNotify->send_subscriber_notification(array(
							'title' => "Thông báo công việc mới",
							'message' => strip_tags($titleNoty),
							'url' => $clsIssue->getLink($issue_id)
						), $subscribers);
					}
					#thong bao app
					$params = [
						'title' => "Thông báo công việc mới",
						'body' => strip_tags($titleNoty),
						'link' => PCMS_URL . $clsIssue->getLink($issue_id)
					];
					$clsNotification->doPushMessagingUser($params,$participants);
				}
			}
		} else {
			if($clsIssue->updateOne($issue_id, array_merge($more, array(
				'title' => $title,
				'slug' => $core->replaceSpace($title),
				'content' => $content,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'project_id' => $project_id,
				'type_id' => $type_id,
				'priority_id' => $priority_id,
				'assign_to_id' => $assign_to_id,
				'user_id_update' => $profile_id,
				'upd_date' => time()
			)))){
				$msg = "_success";
			}
		}
	} else {
		$html_errors = "";
		foreach($errors as $key => $val){
			$html_errors.= '&bull ' . $val;
		}
		// Return
		echo '_invalid|||'.$html_errors; die();
	}
	// Return
	echo $msg.'|||'.$holderG; die();
}
function default_add_issue_note(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	// ini_set('display_errors',1);
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProfile = new Profile();
	$clsFcmToken = new FcmToken();
	$clsEmailTemplate = new EmailTemplate();
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_note_id = (int) Input::post('issue_note_id', 0);
	$mention_list = Input::post('mention_list', array());
	$attachments = Input::post('attachments', array());
	$oneIssue = $clsIssue->getOne($issue_id, "user_id,assign_to_id,participants");
	$list_user_notify = $clsIssue->getUserNotifierUpgrade($issue_id, $oneIssue);
	###
	$msg = '_error';
	if($issue_note_id == 0){
		$issue_note_id = $clsIssueNote->getMaxId();
		if($clsIssueNote->insert(array(
			'issue_note_id' => $issue_note_id,
			'issue_id' => $issue_id,
			'content' => Input::post('content'),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'mention_list' => json_encode($mention_list),
			'attachments' => json_encode($attachments),
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = '_success';
			// Update time
			$clsIssue->updateOne($issue_id, array('upd_date' => time()));
			// Send email
			$clsIssueNote->sendEmail($issue_note_id);
			// Tạo thông báo
			$clsNotify = new Notify();
			$titleNoty = sprintf('<strong>%s</strong> đã notes vào công việc <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsIssue->getTitleV2($issue_id));
			$clsNotify->insertNotify('Issue',$clsIssue->pkey, $issue_id, $titleNoty, time(), $list_user_notify);
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => sprintf("Thêm notes công việc [%s]", $clsIssue->getTitleV2($issue_id)),
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . $clsIssue->getLink($issue_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_user_notify);
			// Bắn thông báo
			$subscribers = array();
			$user_groups = array_merge($participants, array($assign_to_id));
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id` in (".implode(',', $list_user_notify).") and `token`<>''", "token");		
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => sprintf("Thêm notes công việc [%s]", $clsIssue->getTitleV2($issue_id)),
					'message' => strip_tags($titleNoty),
					'url' => PCMS_URL . $clsIssue->getLink($issue_id)
				), $subscribers);
			}	
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("IssueNote","insert");
		}
	} else {
		if($clsIssueNote->updateOne($issue_note_id, array(
			'content' => Input::post('content'),
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))){
			$msg = '_success';
			// Update time
			$clsIssue->updateOne($issue_id, array(
				'upd_date' => time()
			));
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("IssueNote","insert");
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_issue_notes(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	// ini_set('display_errors',1);
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProfile = new Profile();
	###
	$msg = "_error";
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_note_id = (int) Input::post('issue_note_id', 0);
	$oneIssueNotes = $clsIssueNote->getOne($issue_note_id);
	if($clsIssueNote->deleteOne($issue_note_id)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function formatNameFile($name) {
	global $core, $dbconn, $clsISO;
	$part_part = pathinfo($name);
	$filename = $part_part['filename'];
	$extension = $part_part['extension'];
	if(strlen($filename) > 20){
		$l_filename = substr($filename, 0, 10);
		$r_filename = substr($filename, -10);
		return sprintf('%s...%s.%s', $l_filename, $r_filename, $extension);
	} else {
		return $name;
	}
}
function default_upload_multipe_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	
	$attachments = array();
	if($_SERVER['REQUEST_METHOD']=='POST'){
		if(!empty($_FILES['attachments']['name'])){
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(!is_uploaded_file($file['name'])){
					$clsUploadFile = new UploadFile();
					$up = $clsUploadFile->uploadItem($file,'/test3',EXTENSION_FILE_UPLOAD);
					if(!empty($up) && file_exists(ABSPATH . $up)){
						$attachments[] = $up;
					}
				}
			}
		}
	}
	$html= "";
	if(!empty($attachments)){
		foreach($attachments as $file){
			$html.='<div class="MultiFile-label">
				<a href="javascript:void(0)" class="MultiFile-remove" onClick="$Core.upload.delete(this, event)" 
				title="Xóa" src="'.$file.'">x</a> 
				<span class="MultiFile-label">
					<input type="hidden" name="attachments[]" value="'.$file.'" />
					<span class="MultiFile-title">'.formatNameFile($file).'</span>
				</span>
			</div>';
		}
	}
	// Return
	echo $html; die();
}
function default_add_issue_task(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$toId = Input::post('toId');
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_task_id = (int) Input::post('issue_task_id', 0);
	$action = ($issue_task_id > 0) ? '_edit' : '_add';
	
	$html = '<form class="'.$toId.' method="POST" enctype="multipart/form-data" checklist-new-item u-gutter js-new-checklist-item editing">
		<textarea class="form-control required textarea_issue_task_'.$toId.'" toId="'.$toId.'" onkeyup="$Core.issue.set_fireEvent(this, event)" name="content" placeholder="Nhập nội dung" rows="2"></textarea>
		<div id="issue_attachments_file_'.$toId.'" class="py-2 MultiFile-preview"></div>
		<input id="issue_task_upload_file_'.$toId.'" uid="'.$toId.'" type="file" class="issue_task_upload_file d-none" name="attachments[]" multiple="multiple" />
		<div class="checklist-add-controls mt-2 u-clearfix">
			<button type="button" class="btn js-add-task btn-primary" onClick="$Core.issue.save_add_task(this, event)" issue_task_id="'.$issue_task_id.'" issue_id="'.$issue_id.'" toId="'.$toId.'">Thêm</button>
			<button type="button" class="btn js-upload-task add_task_upload_'.$issue_id.' btn-link btn-only-icon" onClick="$Core.issue.issue_task_upload(this, event)" action="'.$action.'" issue_id="'.$issue_id.'" toId="'.$toId.'"><i class="material-icons-outlined no-translate">attach_file</i> </button>
			<button type="button" class="btn js-cancel-task add_task_cancel_'.$issue_id.' btn-link btn-only-icon" onClick="$Core.issue.cancel_add_task(this, event)" action="'.$action.'" issue_id="'.$issue_id.'" toId="'.$toId.'"><i class="material-icons-outlined no-translate">close</i></button>
		</div>
	</form>';
	// Return
	echo $html; die();
}
function default_save_add_task(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id,$oneProfile;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsIssueTask = new IssueTask();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsNotification = new Notification();
	###
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_task_id = (int) Input::post('issue_task_id', 0);
	$content = Input::post('content');
	$attachments = array();
	$list_files = Input::post('attachments');
	if(!empty($list_files)){
		foreach($list_files as $val){
			if(!empty($val) && file_exists(ROOTPATH.$val)){
				$attachments[] = $val;
			}
		}
	}
	###
	$msg = "_error";
	if($issue_task_id > 0){
		if($clsIssueTask->updateOne($issue_task_id, array(
			'content' => $content,
			'user_id_update' => $profile_id,
			'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
			'upd_date' => time()
		))){
			$msg = '_success';
		}
	} else {
		$oneIssue = $clsIssue->getOne($issue_id, "`user_id`,`status_id`,`assign_to_id`");
		$issue_task_id = $clsIssueTask->getMaxId();
		if($clsIssueTask->insert(array(
			$clsIssueTask->pkey => $issue_task_id,
			'issue_id' => $issue_id,
			'content' => $content,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$more = array();
			$msg = '_success';
			if($oneIssue['status_id']==_ISSUE_STATUS_COMPLETED){
				$more['status_id'] = _ISSUE_STATUS_STOP;
				$title = sprintf('<strong>%s</strong> đã thay đổi tình trạng từ <strong>%s</strong> tới <strong>%s</strong>',
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsProperty->getTitle($oneIssue['status_id']),
					$clsProperty->getTitle(_ISSUE_STATUS_STOP)
				);
				$html_change = sprintf('<li>%s</li>', $title);
				$clsIssueNote->insert(array(
					'issue_note_id' => $clsIssueNote->getMaxId(),
					'issue_id' => $issue_id,
					'html_change' => $html_change,
					'user_id' => $profile_id,
					'user_id_update' => $profile_id,
					'reg_date' => time(),
					'upd_date' => time()
				));
				// Tạo thông báo
				// Mình là người làm & không phải là người tạo
				$clsNotify = new Notify();
				if($profile_id == $oneIssue['assign_to_id'] && $oneIssue['user_id'] != $profile_id){
					// Gửi cho người giao
					$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), array($oneIssue['user_id']));
					#thong bao app
					$params = [
						'title' => "Thông báo công việc",
						'body' => strip_tags($title),
						'link' => PCMS_URL . $clsIssue->getLink($issue_id)
					];
					$clsNotification->doPushMessagingUser($params,[$oneIssue['user_id']]);
					// Gửi cho người theo dõi
					$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
					if(!empty($list_user_follow)){
						$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
						#thong bao app
						$params = [
							'title' => "Thông báo công việc",
							'body' => strip_tags($title),
							'link' => PCMS_URL . $clsIssue->getLink($issue_id)
						];
						$clsNotification->doPushMessagingUser($params,$list_user_follow);
					}
				} else if($oneIssue['user_id'] == $profile_id && $profile_id != $oneIssue['assign_to_id']){
					// Mình là người tạo và không phải là người làm
					// Gửi cho người theo dõi
					$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
					if(!empty($list_user_follow)){
						$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
						#thong bao app
						$params = [
							'title' => "Thông báo công việc",
							'body' => strip_tags($title),
							'link' => PCMS_URL . $clsIssue->getLink($issue_id)
						];
						$clsNotification->doPushMessagingUser($params,$list_user_follow);
					}
				}
			}
			// Update Time
			$clsIssue->updateOne($issue_id, array_merge($more, array(
				'upd_date' => time()
			)));
			// Tạo thông báo
			$clsNotify = new Notify();
			if($profile_id!=$oneIssue['assign_to_id']){
				// Gửi cho người làm
				$title = sprintf('<strong>%s</strong> đã thêm hạng mục <strong>%s</strong> vào công việc của bạn đang làm.', $clsProfile->getFullName($profile_id, $oneProfile), $content);
				$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), array($oneIssue['assign_to_id']));				
				#thong bao app
				$params = [
					'title' => "Thông báo công việc",
					'body' => strip_tags($title),
					'link' => PCMS_URL . $clsIssue->getLink($issue_id)
				];
				$clsNotification->doPushMessagingUser($params,[$oneIssue['assign_to_id']]);
				// Gửi cho người theo dõi
				$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
				if(!empty($list_user_follow)){
					$title = sprintf('<strong>%s</strong> đã thêm hạng mục <strong>%s</strong> vào công việc bạn đang theo dõi.', $clsProfile->getFullName($profile_id, $oneProfile), $content);
					$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
					#thong bao app
					$params = [
						'title' => "Thông báo công việc",
						'body' => strip_tags($title),
						'link' => PCMS_URL . $clsIssue->getLink($issue_id)
					];
					$clsNotification->doPushMessagingUser($params,$list_user_follow);
				}
			} else {
				// Gửi cho người theo dõi
				$title = sprintf('<strong>%s</strong> đã thêm hạng mục <strong>%s</strong> vào công việc bạn đang theo dõi.', $clsProfile->getFullName($profile_id, $oneProfile), $content);
				$arr_user = $clsIssue->getUserNotifier($issue_id);
				$clsNotify->insertNotify('Issue',$clsIssue->pkey, $issue_id, $title, time(), $arr_user);
				#thong bao app
				$params = [
					'title' => "Thông báo công việc",
					'body' => strip_tags($title),
					'link' => PCMS_URL . $clsIssue->getLink($issue_id)
				];
				$clsNotification->doPushMessagingUser($params,$arr_user);
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_load_issue_tasks(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsIssueTask = new IssueTask();
	$clsProfile = new Profile();
	###
	$html = ''; $total_done= $total_udone = 0;
	$issue_id = (int) Input::post('issue_id', 0);
	$oneIssue = $clsIssue->getOne($issue_id);
	$list_tasks = $clsIssueTask->getAll("issue_id='{$issue_id}' order by `reg_date` DESC, `is_done` DESC");
	if(!empty($list_tasks)){
		foreach($list_tasks as $key => $oTask){
			$toId = $clsISO->getUniqid();
			$user_id = $oTask['user_id'];
			$issue_task_id = $oTask[$clsIssueTask->pkey];
			###
			$attachments = $oTask['attachments'];
			$list_files = !empty($attachments) 
				? json_decode(html_entity_decode($attachments), true) : array();
			###
			$permiss_action = $permiss_edit = 0;
			if($profile_id==$user_id){
				$permiss_edit = 1;
			}
			if($profile_id==$oneIssue['assign_to_id']){
				$permiss_action = 1;
			}
			if($oTask['is_done']==1){
				$total_done+= 1;
			} else {
				$total_udone+= 1;
			}
			#
			$html_attachments = $html_edit_attachments = "";
			if(!empty($list_files)){
				$html_attachments.= '<div class="MultiFile-preview my-1">';
				foreach($list_files as $file){
					$path_parts = @pathinfo($file);
					$extension = $path_parts['extension'];
					$file_type = (in_array($extension, array('png','jpg','jpeg','gif'))) ? "_img" : "_other";
					$html_attachments.= '<div class="MultiFile-label">
						<a class="MultiFile-title" href="'.$file.'"'.($file_type=='_img'?' data-fancybox':' download').'>
							'.formatNameFile(basename(ABSPATH.$file)).'
						</a>
					</div>';
					$html_edit_attachments.='<div class="MultiFile-label">
						<a href="javascript:void(0)" class="MultiFile-remove" onClick="$Core.upload.delete(this, event)" 
						title="Xóa" src="'.$file.'">x</a> 
						<span class="MultiFile-label">
							<input type="hidden" name="attachments[]" value="'.$file.'" />
							<span class="MultiFile-title">'.formatNameFile($file).'</span>
						</span>
					</div>';
				}
				$html_attachments.= '</div>';
			}
			$html.= '<div class="checklist-item mb-1 no-assignee no-due'.($oTask['is_done']==1?' done':'').'">
				<div class="checklist-item-checkbox">
					<div class="pretty p-jelly p-icon fs-16">
						<input class="'.($permiss_action==1 ? 'js-toggle-checklist-item' : 'js-toggle-checklist-item preventDefault').'" type="checkbox" issue_id="'.$issue_id.'" issue_task_id="'.$issue_task_id.'"'.($oTask['is_done']==1?' checked':'').' value="1">
						<div class="state">
							<i class="icon bx bx-check"></i>
							<label class="bold">&nbsp;</label>
						</div>
					</div>
				</div>
				<div class="checklist-item-details js-checkitem">
					<div class="checklist-item-row js-checkitem-row">
						<div class="checklist-item-text-and-controls">
							<div'.($permiss_edit==1?' onClick="$Core.issue.edit_issue_task(this,event)"':'').'  toId="'.$toId.'" class="checklist-item-details-text">'.$clsISO->force_br_newline($oTask['content']).'</div>
							'.($permiss_edit?'<a href="javascript:void(0);" onClick="$Core.issue.delete_issue_task(this,event)" issue_task_id="'.$issue_task_id.'" issue_id="'.$issue_id.'" data-bs-toggle="tooltip" title="Xóa" class="checklist-item-delete-button">'.$clsISO->makeIcon('bx-trash-alt').'</a>':'').'
							'.$html_attachments.'
							<div class="d-flex align-items-center justiry-content-between">
								<span class="d-none d-lg-block text-muted fs-12 mr-2"><i style="transform:translateY(5px);" class="material-icons-outlined">more_time</i>
									'.$clsISO->convertTimeToText($oTask['reg_date'], true).'
								</span>
								<span class="d-none d-lg-block text-muted fs-12 mr-2"><i style="transform:translateY(5px);" class="material-icons-outlined">more_time</i>
									'.$clsISO->convertTimeToText($oTask['upd_date'], true).'
								</span>
								<span class="d-flex text-muted align-items-center fs-12">
									'.$clsProfile->getIndentity($oTask['user_id'], true, 'xxs').'
								</span>
							</div>
						</div>
						<form class="'.$toId.' checklist-new-item js-new-checklist-item d-none" method="POST" enctype="multipart/form-data">
							<textarea class="form-control autosize textarea_issue_task_'.$toId.'" name="content" placeholder="Nhập nội dung" onkeyup="$Core.issue.set_fireEvent(this,event)" toId="'.$toId.'" rows="2">'.$oTask['content'].'</textarea>
							<div id="issue_attachments_file_'.$toId.'" class="py-2 MultiFile-preview">'.$html_edit_attachments.'</div>
							<input id="issue_task_upload_file_'.$toId.'" uid="'.$toId.'" type="file" class="issue_task_upload_file d-none" name="attachments[]" multiple="multiple" />
							<div class="checklist-add-controls mt-2 clearfix">
								<button type="button" class="btn btn-primary js-add-task confirm mod-submit-edit js-save-edit" onClick="$Core.issue.save_add_task(this, event)" issue_task_id="'.$issue_task_id.'" issue_id="'.$issue_id.'" toId="'.$toId.'">Cập nhật</button>
								<button type="button" class="btn btn-link js-upload-edit btn-only-icon" onClick="$Core.issue.issue_task_upload(this, event)" action="_edit" issue_id="'.$issue_id.'" toId="'.$toId.'"><i class="material-icons-outlined no-translate">attach_file</i></button>
								<button type="button" class="btn btn-link js-cancel-task js-cancel-edit btn-only-icon" onClick="$Core.issue.cancel_add_task(this, event)" action="_edit" issue_id="'.$issue_id.'" toId="'.$toId.'"><i class="material-icons-outlined no-translate">close</i></button>
							</div>
						</form>
					</div>
				</div>
			</div>';
		}
	} else {
		$html.= '<div class="p-3 mb-2 text-center bg-lighter rounded-2">Chưa có hạng mục công việc nào.</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_done' => $total_done,
		'total_udone' => $total_udone
	)); die();
}
function default_load_issue_done_ratio(){
    global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsIssue = new Issue();
    $issue_id = Input::request('issue_id',0);
    $done_ratio = $clsIssue->getOneField('done_ratio', $issue_id);
    $html = '<form method="post">
        <div class="form-group mb-2">
           <div class="range-slider">
				<input type="range" class="range-slider__range" name="done_ratio" min="0" 
				max="100" step="10" value="'.$done_ratio.'">
				<span class="range-slider__value">0</span>
			</div>
        </div>
        <div class="form-group">
			<input type="hidden" name="p_field" value="done_ratio" />
            <button type="button" class="btn btn-primary" onclick="$Core.issue.save_issue_pfield(this,event);" 
			issue_id="'.$issue_id.'">'.$core->makeIcon('check', 'Cập nhật').' </button>
        </div>
    </form>';
    // Return
    echo  $html; die();
}
function default_delete_issue_task(){
	global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
	$clsIssue = new Issue();
	$clsIssueTask = new IssueTask();
	$issue_id = (int) Input::request('issue_id',0);
	$issue_task_id = (int) Input::request('issue_task_id',0);
	#
	$msg= "_error";
	if($clsIssueTask->getOneField('user_id',$issue_task_id) == $profile_id){
		if($clsIssueTask->deleteOne($issue_task_id)){
			$msg = "_success";
		}
	} else {
		$msg = "_invaid";
	}
	// Return
	echo $msg; die();
}
function default_save_issue_pfield(){
	global $profile_id,$core,$clsISO,$oneProfile;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote(); 
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsNotification = new Notification();
	###
	$issue_id = (int) Input::post('issue_id',0);
	$p_field = Input::post('p_field', 'status_id');
	$p_value = Input::post($p_field, 0);
	###
	$html = "";
	$msg = "_error";
	$oneIssue = $clsIssue->getOne($issue_id);
	$set = array($p_field => $p_value);
	$set['upd_date'] = time();
	if($p_field=='status_id'){
		if((int) $p_value == _ISSUE_STATUS_COMPLETED){
			$set['done_ratio'] = 100;
		}
	} else if($p_field=='done_ratio'){
		if((int) $p_value == 100){
			$set['status_id'] = _ISSUE_STATUS_COMPLETED;
		}
	}
	if($clsIssue->updateOne($issue_id, $set)){
		if($p_field=='done_ratio'){
			$oneIssueUpgraded = $clsIssue->getOne($issue_id);
			$html.= $clsIssue->getProgress($issue_id, $oneIssueUpgraded);
		}
		$msg = "_success";
		if($p_field=='status_id'){
			$title = sprintf('<strong>%s</strong> đã thay đổi tình trạng từ <strong>%s</strong> tới <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsProperty->getTitle($oneIssue['status_id']),
				$clsProperty->getTitle($p_value)
			);
			$html_change = sprintf('<li>%s</li>', $title);
			if($oneIssue['user_id'] != $oneIssue['assign_to_id']){
				// Tạo thông báo
				$clsNotify = new Notify();
				// Gửi cho người giao
				$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), array($oneIssue['user_id']));
				#thong bao app
				$params = [
					'title' => "Thông báo công việc",
					'body' => strip_tags($title),
					'link' => PCMS_URL . $clsIssue->getLink($issue_id)
				];
				$clsNotification->doPushMessagingUser($params,[$oneIssue['user_id']]);
				// Gửi cho người theo dõi
				$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
				if(!empty($list_user_follow)){
					$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
					#thong bao app
					$params = [
						'title' => "Thông báo công việc",
						'body' => strip_tags($title),
						'link' => PCMS_URL . $clsIssue->getLink($issue_id)
					];
					$clsNotification->doPushMessagingUser($params,$list_user_follow);
				}
			}	
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Issue","update",["status"=>1]);
		} else if($p_field=='done_ratio'){
			$title = '<strong>'.$clsProfile->getFullName($profile_id, $oneProfile).'</strong> đã thay đổi % hoàn thành từ <strong>'.$oneIssue['done_ratio'].'</strong>% tới <strong>'.$p_value.'</strong>%';
			$html_change = sprintf('<li>%s</li>', $title);
			if($oneIssue['user_id'] != $oneIssue['assign_to_id']){
				// Tạo thông báo
				$clsNotify = new Notify();
				// Gửi cho người giao
				$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), array($oneIssue['user_id']));
				#thong bao app
				$params = [
					'title' => "Thông báo công việc",
					'body' => strip_tags($title),
					'link' => PCMS_URL . $clsIssue->getLink($issue_id)
				];
				$clsNotification->doPushMessagingUser($params,[$oneIssue['user_id']]);
				// Gửi cho người theo dõi
				$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
				if(!empty($list_user_follow)){
					$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
					#thong bao app
					$params = [
						'title' => "Thông báo công việc",
						'body' => strip_tags($title),
						'link' => PCMS_URL . $clsIssue->getLink($issue_id)
					];
					$clsNotification->doPushMessagingUser($params,$list_user_follow);
				}
			}
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Issue","update",["status"=>1]);
		}
		$issue_note_id = $clsIssueNote->getMaxId();
		$clsIssueNote->insert(array(
			'issue_note_id' => $issue_note_id,
			'issue_id' => $issue_id,
			'html_change' => $html_change,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		));
	}
	// Return
    echo json_encode(array(
		'p_field' => $p_field,
		'issue_id' => $issue_id,
		'html' => $html
	)); die();
}
function default_tick_issue_task(){
	global $profile_id,$core,$clsISO,$oneProfile;
	$clsIssue = new Issue();
	$clsIssueNote = new IssueNote(); 
	$clsIssueTask = new IssueTask();
	###
	$issue_id = (int) Input::post('issue_id',0);
	$issue_task_id = (int) Input::post('issue_task_id',0);
	$is_done = (int) Input::post('is_done',0);
	###
	$msg = "_error";
	if($clsIssueTask->updateOne($issue_task_id, array(
		'is_done' => $is_done
	))){
		$msg = "_success";
		$clsIssue->updateOne($issue_id, array(
			'upd_date' => time()
		));
	}
	// Return
    echo $msg; die();
}
function default_load_issue_implementer(){
    global $_frontIsLoggedin_user_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsIssue = new Issue();
    #
    $issue_id = (int) Input::request('issue_id',0);
    $html = '<form method="post">
        <div class="form-group mb-3">
            <select class="iso-selectizeNotSearch" data-placeholder="Thêm người theo dõi" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" multiple="true" data-width="100%" placeholder="Thêm người theo dõi"></select>
        </div>
        <hr />
		<div class="form-group">
			<input type="hidden" name="p_field" value="status_id" />
            <button type="button" class="btn btn-primary" onclick="$Core.issue.save_issue_pfield(this,event);" 
			issue_id="'.$issue_id.'">'.$core->makeIcon('check', 'Cập nhật').'</button>
        </div>
    </form>';
    // Return
    echo $html; die();
}
function default_open_issue_notes(){
	global $smarty,$_frontIsLoggedin_user_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', 'edit');
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_note_id = (int) Input::post('issue_note_id', 0);
	###
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	$smarty->assign('issue_id', $issue_id);
	$smarty->assign('issue_note_id', $issue_note_id);
	###
	$oneIssueNotes = $clsIssueNote->getOne($issue_note_id);
	$mention_list = $oneIssueNotes['mention_list'];
	$mention_list_arrs = !empty($mention_list) 
		? json_decode($mention_list, true) : array();
	$smarty->assign('mention_list_arrs', $mention_list_arrs);
	#
	$html_attachments = "";
	if(!empty($oneIssueNotes['attachments'])){
		$tmp = json_decode(html_entity_decode($oneIssueNotes['attachments']), true);
		foreach($tmp as $file){
			$html_attachments.= '<div class="MultiFile-label">
				<a href="javascript:void(0)" class="MultiFile-remove" 
				onClick="$Core.upload.delete(this,event)" src="'.$file.'">x</a> 
				<input type="hidden" name="attachments[]" value="'.$file.'" />
				<span class="MultiFile-label" title="'.$file.'">
					<span class="MultiFile-title">'.formatNameFile($file).'</span>
				</span>
			</div>';
		}
	}
	$smarty->assign('html_attachments', $html_attachments);
	$smarty->assign('oneIssueNotes', $oneIssueNotes);
	// Return
	$html = $core->build('_ajax.issue.notes.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_issue_notes(){
	global $smarty,$profile_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', 'edit');
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_note_id = (int) Input::post('issue_note_id', 0);
	$mention_list = Input::post('mention_list', array());
	$attachments = Input::post('attachments', array());
	$msg = "_error";
	if($tp=='quote'){
		if($clsIssueNote->insert(array(
			$clsIssueNote->pkey => $clsIssueNote->getMaxId(),
			'parent_id' => $issue_note_id,
			'issue_id' => $issue_id,
			'content' => Input::post('content'),
			'mention_list' => json_encode($mention_list),
			'attachments' => json_encode($attachments),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = '_success';
			// Update time
			$clsIssue->updateOne($issue_id, array(
				'upd_date' => time()
			));
			// Send email
			$clsIssueNote->sendEmail($issue_note_id);
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("IssueNote","reply",["tp"=>$tp]);
		}
	} else {
		if($issue_note_id > 0){
			if($clsIssueNote->updateOne($issue_note_id, array(
				'content' => Input::post('content'),
				'mention_list' => json_encode($mention_list),
				'attachments' => json_encode($attachments),
				'upd_date' => time(),
				'user_id_update' => $profile_id
			))){
				$msg = '_success';
				// Update time
				$clsIssue->updateOne($issue_id, array(
					'upd_date' => time()
				));
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("IssueNote","update");
			}
		} else {
			if($clsIssueNote->insert(array(
				$clsIssueNote->pkey => $clsIssueNote->getMaxId(),
				'parent_id' => 0,
				'issue_id' => $issue_id,
				'content' => Input::post('content'),
				'mention_list' => json_encode($mention_list),
				'attachments' => json_encode($attachments),
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			))){
				$msg = '_success';
				// Update time
				$clsIssue->updateOne($issue_id, array(
					'upd_date' => time()
				));
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_issue(){
	global $smarty,$profile_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	###
	$msg = "_error";
	$issue_id = (int) Input::post('issue_id', 0);
	if($issue_id > 0){
		$oneIssue = $clsIssue->getOne($issue_id);
		if($oneIssue['status_id'] == _ISSUE_STATUS_DOING){
			$msg = "_doing";
		} else {
			if($clsIssue->doDelete($issue_id)){
				$msg = "_success";
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_done_issue(){
	global $smarty,$profile_id,$core,$clsISO,$clsUser,$clsProperty,$oneProfile;
    $clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	
	$html = ""; $msg = "_error";
	$issue_id = (int) Input::post('issue_id', 0);
	$oneIssue = $clsIssue->getOne($issue_id, "status_id,assign_to_id");
	if($oneIssue['assign_to_id'] != $profile_id && !$clsISO->checkDEV()){ echo '_invalid'; die(); }
	if($clsIssue->updateOne($issue_id, array(
		'done_ratio' => 100,
		'status_id' => _ISSUE_STATUS_COMPLETED
	))){
		$msg = "_success";
		$html.= $clsIssue->getProgress($issue_id, $oneIssue);
		$html_change = sprintf(
			'<li><strong>%s</strong> đã thay đổi tình trạng từ <strong>%s</strong> tới <strong>%s</strong></li>',
			$clsProfile->getFullName($profile_id, $oneProfile), 
			$clsProperty->getTitle($oneIssue['status_id']),
			$clsProperty->getTitle(_ISSUE_STATUS_COMPLETED)
		);
		$clsIssueNote->insert(array(
			'issue_note_id' => $clsIssueNote->getMaxId(),
			'issue_id' => $issue_id,
			'html_change' => $html_change,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		));
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Issue","update",["status"=>1]);
	}
	// Return
	$html_buttons = $clsIssue->render_html_actions($issue_id, $oneIssue);
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
		'html_buttons' => $html_buttons
	)); die();
}
function default_archive_issue(){
	global $smarty,$profile_id,$core,$clsISO,$clsUser,$clsProperty,$oneProfile;
    $clsIssue = new Issue();
	$clsIssueNote = new IssueNote();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$msg = "_error";
	$issue_id = (int) Input::post('issue_id', 0);
	$oneIssue = $clsIssue->getOne($issue_id,"is_archived");
	if($oneIssue['is_archived']==1){
		$is_archived = 0;
		$action = '_un_archived';
	} else {
		$is_archived = 1;
		$action = '_archived';
	}
	if($clsIssue->updateOne($issue_id, array(
		'is_archived' => $is_archived
	))){
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'action' => $action
	)); die();
}
function default_load_calendar(){
	global $smarty,$core,$clsISO,$profile_id,$oneProfile;
	$clsIssue = new Issue();
	$me = (int) $profile_id;
	$year = (int) Input::post('year', date('Y'));
	$month = (int) Input::post('month', date('n'));
	if($month < 1){ $month = 1; }
	if($month > 12){ $month = 12; }
	# Scope giống Danh sách (union + nút "Việc của tôi")
	$cond = "`is_trash`=0 AND `parent_id`=0";
	if(!_issue_can_see_all()){
		$arr_staff_in = array();
		_issue_role_scope_ids($arr_staff_in);
		if(!empty($arr_staff_in)){
			$cond.= " AND (`assign_to_id` IN (".implode(',', $arr_staff_in).") OR `assign_to_id`='{$me}' OR `user_id`='{$me}' OR `participants_slash` LIKE '%|{$me}|%')";
		}
	}
	if((int) Input::post('mine', 0) == 1){ $cond.= " AND `assign_to_id`='{$me}'"; }
	if((int) Input::post('is_all', 0) == 0){ $cond.= " AND `is_archived`='0'"; }
	# Lọc theo tình trạng (chip)
	$_st_chip = (int) Input::post('status_id', 0);
	if($_st_chip > 0){ $cond.= " AND `status_id`='{$_st_chip}'"; }
	# Khoảng tháng theo end_date
	$m_start = mktime(0,0,0,$month,1,$year);
	$dim = (int) date('t', $m_start);
	$m_end = mktime(23,59,59,$month,$dim,$year);
	$cond.= " AND `end_date` BETWEEN {$m_start} AND {$m_end}";
	$list = $clsIssue->getAll($cond." ORDER BY `end_date` ASC", "`issue_id`,`title`,`status_id`,`end_date`");
	# Gom theo ngày trong tháng
	$byday = array();
	if(!empty($list)){
		foreach($list as $v){
			$byday[(int) date('j', $v['end_date'])][] = $v;
		}
	}
	# Lưới tuần T2..CN
	$startOffset = ((int) date('N', $m_start) + 6) % 7;
	$today_d = ((int) date('Y') == $year && (int) date('n') == $month) ? (int) date('j') : 0;
	$cells = array();
	for($i=0; $i<$startOffset; $i++){ $cells[] = null; }
	for($d=1; $d<=$dim; $d++){ $cells[] = $d; }
	while(count($cells) % 7 != 0){ $cells[] = null; }
	$pm = $month-1; $py = $year; if($pm < 1){ $pm = 12; $py--; }
	$nm = $month+1; $ny = $year; if($nm > 12){ $nm = 1; $ny++; }
	$smarty->assign('cal_weeks', array_chunk($cells, 7));
	$smarty->assign('cal_byday', $byday);
	$smarty->assign('cal_today', $today_d);
	$smarty->assign('cal_label', 'Tháng '.$month.', '.$year);
	$smarty->assign('cal_prev_y', $py);
	$smarty->assign('cal_prev_m', $pm);
	$smarty->assign('cal_next_y', $ny);
	$smarty->assign('cal_next_m', $nm);
	$smarty->assign('stCls', array(203=>'st-203', 204=>'st-204', 205=>'st-205', 206=>'st-207', 207=>'st-207'));
	$smarty->assign('clsIssue', $clsIssue);
	$html = $core->build('_ajax.calendar.tpl');
	echo json_encode(array('html' => $html)); die();
}
function default_load_timeline(){
	global $smarty,$core,$clsISO,$profile_id,$oneProfile;
	$clsIssue = new Issue();
	$me = (int) $profile_id;
	$count = 12; # số ngày cửa sổ
	$arr_dow = array(0=>'CN',1=>'T2',2=>'T3',3=>'T4',4=>'T5',5=>'T6',6=>'T7');
	# base = ngày bắt đầu cửa sổ (YYYY-MM-DD), mặc định hôm nay - 3
	$base_in = preg_replace('/[^0-9\-]/', '', Input::post('base', ''));
	$base_ts = $base_in ? strtotime($base_in.' 00:00:00') : 0;
	if($base_ts <= 0){ $base_ts = strtotime(date('Y-m-d')) - 3*86400; }
	$win_start = $base_ts;
	$win_end = $base_ts + $count*86400; # exclusive (qua ngày cuối)
	# Scope giống Danh sách (union + nút "Việc của tôi")
	$cond = "`is_trash`=0 AND `parent_id`=0";
	if(!_issue_can_see_all()){
		$arr_staff_in = array();
		_issue_role_scope_ids($arr_staff_in);
		if(!empty($arr_staff_in)){
			$cond.= " AND (`assign_to_id` IN (".implode(',', $arr_staff_in).") OR `assign_to_id`='{$me}' OR `user_id`='{$me}' OR `participants_slash` LIKE '%|{$me}|%')";
		}
	}
	if((int) Input::post('mine', 0) == 1){ $cond.= " AND `assign_to_id`='{$me}'"; }
	if((int) Input::post('is_all', 0) == 0){ $cond.= " AND `is_archived`='0'"; }
	# Lọc theo tình trạng (chip)
	$_st_chip = (int) Input::post('status_id', 0);
	if($_st_chip > 0){ $cond.= " AND `status_id`='{$_st_chip}'"; }
	# Chỉ việc chồng lấn cửa sổ (start_date=0 thì lấy end_date)
	$cond.= " AND `end_date` >= {$win_start} AND IF(`start_date`>0,`start_date`,`end_date`) < {$win_end}";
	$list = $clsIssue->getAll($cond." ORDER BY IF(`start_date`>0,`start_date`,`end_date`) ASC, `end_date` ASC LIMIT 60"
		, "`issue_id`,`title`,`status_id`,`start_date`,`end_date`,`done_ratio`");
	# Ngày tiêu đề
	$today_ymd = date('Y-m-d');
	$days = array();
	for($i=0; $i<$count; $i++){
		$d = $base_ts + $i*86400;
		$days[] = array(
			'dow' => $arr_dow[(int) date('w', $d)],
			'num' => (int) date('j', $d),
			'today' => (date('Y-m-d', $d) == $today_ymd) ? 1 : 0
		);
	}
	# Tính bar mỗi việc (left/width % theo cửa sổ)
	$rows = array();
	if(!empty($list)){
		foreach($list as $v){
			$st = $v['start_date'] > 0 ? (int) $v['start_date'] : (int) $v['end_date'];
			$en = $v['end_date'] > 0 ? (int) $v['end_date'] : $st;
			if($en < $st){ $en = $st; }
			# canh về mốc ngày (00:00) để ra số ngày nguyên
			$s = (strtotime(date('Y-m-d', $st)) - $base_ts) / 86400;
			$e = (strtotime(date('Y-m-d', $en)) - $base_ts) / 86400 + 1;
			if($s < 0){ $s = 0; }
			if($e > $count){ $e = $count; }
			$left = $s / $count * 100;
			$width = ($e - $s) / $count * 100;
			if($width < 4){ $width = 4; }
			$sid = (int) $v['status_id'];
			$pr = (int) $v['done_ratio'];
			if($sid == 206 || $sid == 207){ $pr = 100; } # đã đóng/hoàn thành = 100%
			if($pr < 0){ $pr = 0; } if($pr > 100){ $pr = 100; }
			$rows[] = array(
				'issue_id' => $v['issue_id'],
				'title' => $v['title'],
				'cls' => ($sid == 206 || $sid == 207) ? 'st-207' : 'st-'.$sid,
				'left' => round($left, 2),
				'width' => round($width, 2),
				'pr' => $pr
			);
		}
	}
	$smarty->assign('tl_days', $days);
	$smarty->assign('tl_rows', $rows);
	$smarty->assign('tl_label', date('d/m', $base_ts).' – '.date('d/m', $base_ts + ($count-1)*86400));
	$smarty->assign('tl_prev', date('Y-m-d', $base_ts - 7*86400));
	$smarty->assign('tl_next', date('Y-m-d', $base_ts + 7*86400));
	$smarty->assign('tl_today', date('Y-m-d', strtotime(date('Y-m-d')) - 3*86400));
	$html = $core->build('_ajax.timeline.tpl');
	echo json_encode(array('html' => $html)); die();
}
function default_set_view(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$clsProfile = new Profile();
	$view = Input::post('view', 'grid');
	$call_from = Input::post('call_from', 'issue');
	
	$_ss_name = "_ss_view";
	if($call_from == "issue_target"){
		$_ss_name = "_ss_view_target";
	}
	$more_information = $oneProfile["more_information"];
	$more_information[$_ss_name] = $view;
	$clsProfile->updateOne($profile_id,[
		"more_information" => 	json_encode($more_information)
	]);
	// Return
	echo 1; die();
}
function default_add_target(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsIssue = new Issue();
	$clsIssueTarget = new IssueTarget();
	###
	$issue_id = (int) Input::post('issue_id', 0);
	$issue_target_id = (int) Input::post('issue_target_id', 0);
	if($issue_id > 0){
		$clsIssue->updateOne($issue_id, array(
			'issue_target_id' => $issue_target_id
		));
	}
	$html = $clsIssueTarget->getOneField('title', $issue_target_id);
	// Return
	echo $html; die();
}
function default_target(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	###
	$more_information = $oneProfile['more_information'];
	$view_by = isset($more_information["_ss_view_target"]) 
		? $more_information["_ss_view_target"] : "table";
	$assign_list["view_by"] = $view_by;
	
	/*=============Title & Description Page==================*/
	$title_page = 'Lịch công việc | '. PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_get_issue_target(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id, $oneProfile;
	$clsIssue = new Issue();
	$clsIssueTarget = new IssueTarget();
	
	$_results = array();
	$department_id = (int) Input::get('department_id', 0);
	$list_targets = $clsIssueTarget->getAll("`is_trash`=0 and `department_id`='{$department_id}'");
	if(!empty($list_targets)){
		foreach($list_targets as $key => $val){
			$_results[] = array(
				'id' => $val[$clsIssueTarget->pkey],
				'text' => $val['title']
			);
		}
	}
	// Return 
	echo json_encode($_results, JSON_UNESCAPED_UNICODE); die();
}
function default_load_issue_target(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id, $oneProfile, $deviceType;
	$clsIssue = new Issue();
	$clsIssueTarget = new IssueTarget();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	
	$year = (int) Input::post('year', date('Y'));
	$more_information = $oneProfile['more_information'];
	$view_by = isset($more_information["_ss_view_target"]) 
		? $more_information["_ss_view_target"] : "table";
	
	$arr_property_cached = array();
	for($month=1; $month<=12; $month ++){
		$start_month = strtotime(sprintf('%s-%s-%s 00:00', 01, $month, $year));
		$end_day_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end_month = strtotime(sprintf('%s-%s-%s 23:59', $end_day_month, $month, $year));
		// and `end_date`<='{$end_month}'
		$list_targets = $clsIssueTarget->getAll("`start_date`>='{$start_month}' AND `start_date`<='{$end_month}'");
		$total_targets = !empty($list_targets) ? count($list_targets) : 0;
			$html.= '<div class="table-container  mb-2">
				<table cellpadding="0" cellspacing="0" class="table table-bordered">
					<thead><tr>
						<th width="60%" class="align-center h-px-35 bg-lighter">Tháng T'.$month.'</th>
						<th class="align-center h-px-35 text-left bg-lighter">Từ ngày</th>
						<th class="align-center h-px-35 text-left bg-lighter">Tới ngày</th>
						<th class="align-center h-px-35 bg-lighter">Uư tiên</th>
						<th class="align-center h-px-35 text-left bg-lighter">Tình trạng</th>
						<th class="align-center h-px-35 bg-lighter" width="40px"></th>
					</tr></thead>';
			if(!empty($list_targets)){
				foreach($list_targets as $key => $val){
					$status_id = $val['status_id'];
					$priority_id = $val['priority_id'];
					$issue_target_id = $val[$clsIssueTarget->pkey];
					if(!isset($arr_property_cached[$status_id])){
						$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
					}
					if(!isset($arr_property_cached[$priority_id])){
						$arr_property_cached[$priority_id] = $clsProperty->getTitle($priority_id);
					}
					$list_issues = $clsIssue->getAll("issue_target_id='{$issue_target_id}'");
					$html.= '<tr>
						<td>
							<div class="d-flex align-items-center gap-2">
								<div class="mkCharts" data-percent="45" data-size="30" data-color="#654321"></div>
								<div class="d-flex flex-column">
									<h3 class="mb-1"><a href="javascript:void(0)" class="fs-6 fw-bold">'.$val['title'].'</a></h3>
									<div class="d-flex align-items-center gap-2">
										<span class="d-flex align-items-center gap-1">
											<i class=\'bx bx-check-square\'></i>
											<span>8/11</span>
										</span>
										<span class="d-flex align-items-center gap-1">
											<i class=\'bx bx-user\'></i>
											<span>Bùi Văn Thiêm</span>
										</span>
										<span class="d-flex align-items-center gap-1">
											<i class=\'bx bx-chart\'></i>
											<span>P.Công Nghệ</span>
										</span>
									</div>
								</div>
							</div>
						</td>
						<td>
							<i class="material-icons-outlined no-translate">more_time</i> 
							<span>'.$clsISO->convertTimeToText($val['start_date']).'</span>
						</td>
						<td>
							<i class="bx bx-right-arrow-alt"></i>
								<span>'.$clsISO->convertTimeToText($val['end_date']).'</span>
						</td>
						<td></td>
						<td class="align-center text-center">
							'.($profile_id==$val['user_id'] ? '<div class="dropdown">
								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
									<i class="bx bx-dots-vertical-rounded"></i>
								</button>
								<div class="dropdown-menu w-px-100">
									<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.issue.open_target(this, event)" issue_target_id="'.$issue_target_id.'"><i class="bx bx-pencil me-1"></i> Sửa</a>
									<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.issue.delete_target(this, event)" issue_target_id="'.$issue_target_id.'"><i class="bx bx-trash me-1"></i> Xóa</a>
								</div>
							</div>' : '').'
						</td>
					</tr>';
				}
				
			} else {
				$html.= '<tr>
					<td></td>
				</tr>';
			}
		$html.= '</table></div>';
	}
	// Return 
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_issue_quick(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsIssue = new Issue();
	$clsIssueTarget = new IssueTarget();
	$html_table_tasks = '<div class="table-container text-nowrap overflow-x-auto">
	<table cellspacing=\'0\' cellpadding=\'0\' class=\'table mb-0\' width="100%">
		<thead><tr>
			<th class=\'align-center\'>Tên công việc</th>
			<th class=\'align-center\'>Tình trạng</th>
			<th class=\'align-center\'>Thời gian</th>
			<th class=\'align-center\'>Người thực hiện</th>
			<th class=\'align-center\'>Tiến độ</th>
		</tr></thead>';
	$uid = $clsISO->getUniqid();
	$issue_target_id = (int) Input::post('issue_target_id', 0);
	$oneIssueTarget = $clsIssueTarget->getOne($issue_target_id);
	$list_tasks = $clsIssue->getAll("issue_target_id='{$issue_target_id}'");
	if(!empty($list_tasks)){
		$arr_property_cached = $arr_profile_cached = array();
		foreach($list_tasks as $okey => $oval){
			$issue_id = $oval[$clsIssue->pkey];
			$task_status_id = $oval['status_id'];
			$assign_to_id = $oval['assign_to_id'];
			if($oval['status_id'] == _ISSUE_STATUS_DOING && $is_doing == 0){
				$is_doing = 1;
			}
			if($oval['status_id'] == _ISSUE_STATUS_COMPLETED){
				$total_dones += 1;
			}
			if(!isset($arr_property_cached[$task_status_id])){
				$arr_property_cached[$task_status_id] = $clsIssue->getStatusLabel($task_status_id);
			}
			if(!isset($arr_profile_cached[$assign_to_id])){
				$arr_profile_cached[$assign_to_id] = $clsProfile->getIndentityV3($assign_to_id, true);
			}
			$html_table_tasks.= '<tr>
				<td class=\'align-center text-left\'>
					<a href="javascript:void(0)" onClick="$Core.issue.view_issue(this, event)" issue_id="'.$issue_id.'">
						<strong>'.$oval['title'].'</strong></a>
				</td>
				<td class=\'align-center text-left\'>'.$arr_property_cached[$task_status_id].'</td>
				<td class=\'align-center text-left\'>
					<div class=\'d-flex align-items-center\'>
						'.$clsISO->convertTimeToText($oval['start_date']).'
						<i class=\'bx bx-right-arrow-alt\'></i>
						'.$clsISO->convertTimeToText($oval['end_date']).'
					</div>
				</td>
				<td class=\'align-center text-left\'>'.$arr_profile_cached[$assign_to_id].'</td>
				<td class=\'align-center text-center\'>'.$clsIssue->getProgress($issue_id, $oval).'</td>
			</tr>';
		}
	}
	$html_table_tasks.= '
		</table>
	</div>';
	$html = '<div class="modal-dialog modal-dialog-centered modal-ipad-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">
				<span class="fs-12 text-muted">Mục tiêu</span> <br/>
				'.$clsIssueTarget->getTitle($issue_target_id, $oneIssueTarget).'</h5>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">';
		$html.= $html_table_tasks;
	$html.= '</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Đóng</button>
		</div>
	</div></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_target(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id, $oneProfile;
	$clsIssue = new Issue();
	$clsIssueTarget = new IssueTarget();
	$clsProfile = new Profile();
	$clsProperty = new Property(); 
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsIssueTarget', $clsIssueTarget);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$current_time = time();
	$uid = $clsISO->getUniqid();
	$issue_target_id = (int) Input::post('issue_target_id', 0);
	$department_id = $oneProfile['department_id'];
	$smarty->assign('uid', $uid);
	$smarty->assign('department_id', $department_id);
	$smarty->assign('issue_target_id', $issue_target_id);
	$oneItem = array(
		'priority_id' => 0,
		'start_date' => $current_time,
		'end_date' => strtotime("1 month", $current_time),
		'department_id' => $department_id
	);
	if($issue_target_id > 0){
		$oneItem = $clsIssueTarget->getOne($issue_target_id);
	}
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('issue_target_id', $issue_target_id);
	// Return
	$html = $core->build('_ajax.target.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_issue_target(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id, $oneProfile;
	$clsIssue = new Issue();
	$clsIssueTarget = new IssueTarget();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$issue_target_id = (int) Input::post('issue_target_id', 0);
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	if($issue_target_id == 0){
		$issue_target_id = $clsIssueTarget->getMaxId();
		if($clsIssueTarget->insert(array(
			$clsIssueTarget->pkey => $issue_target_id,
			'title' => Input::post('title'),
			'content' => Input::post('content'),
			// 'status_id' => _ISSUE_STATUS_NEW,
			'department_id' => Input::post('department_id'),
			'priority_id' => Input::post('priority_id'),
			'start_date' => $start_time,
			'end_date' => $end_time,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else if($issue_target_id > 0){
		if($clsIssueTarget->updateOne($issue_target_id, array(
			'title' => Input::post('title'),
			'content' => Input::post('content'),
			//'status_id' => _ISSUE_STATUS_NEW,
			'department_id' => Input::post('department_id'),
			'priority_id' => Input::post('priority_id'),
			'start_date' => $start_time,
			'end_date' => $end_time,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_target(){
	global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
	$clsIssueTarget = new IssueTarget();
	$issue_target_id = (int) Input::request('issue_target_id', 0);
	#
	$msg = "_error";
	if($issue_target_id > 0 && ($clsIssueTarget->getOneField('user_id', $issue_target_id) == $profile_id || $clsISO->checkDEV())){
		if($clsIssueTarget->deleteOne($issue_target_id)){
			$msg = "_success";
		}
	} else {
		$msg = "_invalid";
	}
	// Return
	echo $msg; die();
}
function default_start_issue(){
    global $profile_id,$core,$dbconn,$clsISO,$clsConfiguration,$clsISO,$oneProfile;
    $clsIssue = new Issue();
	$clsProfile = new Profile();
    $clsIssueNote = new IssueNote();
    $clsIssueCurrent = new IssueCurrent();
    $clsNotification = new Notification();
    ###
	$msg = "_success";
    $issue_id = (int) Input::post('issue_id',0);
    $oneIssue = $clsIssue->getOne($issue_id);
    if($oneIssue['assign_to_id'] != $profile_id && !$clsISO->checkDEV()){ echo '_invalid'; die(); }
    /** Close task current */
	$set = "status_id='"._ISSUE_STATUS_STOP."',upd_date='".time()."'";
    $clsIssue->updateByCond("`assign_to_id`='{$profile_id}' and `status_id`='"._ISSUE_STATUS_DOING."'", $set);
    /** Start task current */
    $clsIssue->updateOne($issue_id,"`status_id`='"._ISSUE_STATUS_DOING."',`upd_date`='".time()."'");
    $clsProfile->updateOne($profile_id,"`issue_current_id`='{$issue_id}'");
    /** End tracking time all task current */
    $clsIssueCurrent->updateByCond("`user_id`='{$profile_id}' and `due_date`='0'","`due_date`='".time()."'");
    /** Start task current */
	$f = "{$clsIssueCurrent->pkey},issue_id,user_id,start_date";
    $issue_current_id = $clsIssueCurrent->getMaxId(); 
    $v = "'{$issue_current_id}','{$issue_id}','{$profile_id}','".time()."'";
    $clsIssueCurrent->insertOne($f,$v);
    /** Create Note */
	$html_change = sprintf('<li><strong>%s</strong> bắt đầu thực hiện công việc</li>', 
		$clsProfile->getFullName($profile_id, $oneProfile));
	$clsIssueNote->insert(array(
		'issue_note_id' => $clsIssueNote->getMaxId(),
		'issue_id' => $issue_id,
		'html_change' => $html_change,
		'user_id' => $profile_id,
		'user_id_update' => $profile_id,
		'reg_date' => time(),
		'upd_date' => time()
	));
    /* Notification */
	if($oneIssue['user_id'] != $oneIssue['assign_to_id']){
		$title = sprintf('<strong>%s</strong> bắt đầu thực hiện công việc <strong>%s</strong>',
			$clsProfile->getFullName($profile_id, $oneProfile), 
			$clsIssue->getTitle($issue_id, $oneIssue)
		);
		// Tạo thông báo
		$clsNotify = new Notify();
		// Gửi cho người giao
		$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), array($oneIssue['user_id']));
		#thong bao app
		$params = [
			'title' => "Thông báo công việc",
			'body' => strip_tags($title),
			'link' => PCMS_URL . $clsIssue->getLink($issue_id)
		];
		$clsNotification->doPushMessagingUser($params,[$oneIssue['user_id']]);
		// Gửi cho người theo dõi
		$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
		if(!empty($list_user_follow)){
			$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
			#thong bao app
			$params = [
				'title' => "Thông báo công việc",
				'body' => strip_tags($title),
				'link' => PCMS_URL . $clsIssue->getLink($issue_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_user_follow);
		}
	}	
	// Activity log			
	$clsActivityLog = new ActivityLog();
	$clsActivityLog->addActivityLog("Issue","update",["status"=>1]);
    
	$html_buttons = $clsIssue->render_html_actions($issue_id, $oneIssue);
	// Return
    echo json_encode(array(
		'msg' => $msg,
		'html_buttons' => $html_buttons
	)); die();
}
function default_stop_issue(){
    global $profile_id,$core,$clsISO,$dbconn,$clsUser,$clsProperty,$clsConfiguration;
    $clsIssue = new Issue();
	$clsProfile = new Profile();
    $clsIssueNote = new IssueNote();
    $clsIssueCurrent = new IssueCurrent();
    $clsNotification = new Notification();
    #
	$msg = "_success";
    $issue_id = Input::post('issue_id',0);
    $oneIssue = $clsIssue->getOne($issue_id);
    if($oneIssue['assign_to_id'] != $profile_id && !$clsISO->checkDEV()){ echo '_invalid'; die(); }
    $clsProfile->updateOne($profile_id, array('issue_current_id' => 0 ));
    /** Remove Other issue tracking time and save to pending */
    $clsIssueCurrent->updateByCond("`user_id`='{$profile_id}' and `due_date`='0'","due_date='".time()."'");
    /** Remove Other issue tracking time and save to pending */
	//$set = "status_id='"._ISSUE_STATUS_STOP."',upd_date='".time()."'";
    //$clsIssue->updateByCond("`assign_to_id`='{$profile_id}' and `status_id`='"._ISSUE_STATUS_DOING."'", $set);
    $clsIssue->updateOne($issue_id, array(
		'status_id' => _ISSUE_STATUS_STOP
	));
    /** Create Note */    
	$html_change = sprintf('<li>%s tạm dừng thực hiện công việc</li>', 
		$clsProfile->getFullName($profile_id, $oneProfile));
	$clsIssueNote->insert(array(
		'issue_note_id' => $clsIssueNote->getMaxId(),
		'issue_id' => $issue_id,
		'html_change' => $html_change,
		'user_id' => $profile_id,
		'user_id_update' => $profile_id,
		'reg_date' => time(),
		'upd_date' => time()
	));
	 /* Notification */
	if($oneIssue['user_id'] != $oneIssue['assign_to_id']){
		$title = sprintf('<strong>%s</strong> tạm dừng thực hiện công việc <strong>%s</strong>',
			$clsProfile->getFullName($profile_id, $oneProfile), 
			$clsProperty->getTitle($oneIssue['status_id'])
		);
		// Tạo thông báo
		$clsNotify = new Notify();
		// Gửi cho người giao
		$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), array($oneIssue['user_id']));
		#thong bao app
		$params = [
			'title' => "Thông báo công việc",
			'body' => strip_tags($title),
			'link' => PCMS_URL . $clsIssue->getLink($issue_id)
		];
		$clsNotification->doPushMessagingUser($params,[$oneIssue['user_id']]);
		// Gửi cho người theo dõi
		$list_user_follow = $clsIssue->getUserNotifier($issue_id, '_do');
		if(!empty($list_user_follow)){
			$clsNotify->insertNotify('Issue', $clsIssue->pkey, $issue_id, $title, time(), $list_user_follow);
			#thong bao app
			$params = [
				'title' => "Thông báo công việc",
				'body' => strip_tags($title),
				'link' => PCMS_URL . $clsIssue->getLink($issue_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_user_follow);
		}
	}
	$html_buttons = $clsIssue->render_html_actions($issue_id, $oneIssue);
    // Return
    echo json_encode(array(
		'msg' => $msg,
		'html_buttons' => $html_buttons
	)); die();
}
# ===== Helper dùng chung: scope theo cây vai trò (server-side) =====
function _issue_role_scope_ids(&$arr_staff_in){
	// ĐỔI 2026-06-24: scope hiển thị công việc theo PHÒNG BAN (không còn theo cây vai trò).
	// Trả list profile_id nhân viên thuộc phòng của người đăng nhập + phòng con
	// (list_department_id của họ chứa department_id mình). BLD/DIRECTOR đã bỏ qua ở _issue_can_see_all().
	global $oneProfile, $profile_id;
	$clsProfile = new Profile();
	$arr_staff_in = array();
	$my_dept = (int) $oneProfile['department_id'];
	if($my_dept > 0){
		$list_staffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`='1' AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'
			AND `list_department_id` LIKE '%|".$my_dept."|%'", "{$clsProfile->pkey}");
		if(!empty($list_staffs)){
			foreach($list_staffs as $val){ $arr_staff_in[] = (int) $val[$clsProfile->pkey]; }
		}
	}
	// LUÔN gồm bản thân → IN() không rỗng (chống leak khi department_id=0); user thường self vốn đã thuộc phòng.
	if((int) $profile_id > 0 && !in_array((int) $profile_id, $arr_staff_in, true)){
		$arr_staff_in[] = (int) $profile_id;
	}
}

/**
 * Ai được thấy TẤT CẢ công việc (bỏ qua scope phòng ban):
 * phòng Ban Lãnh Đạo (BGĐ, _DEPARTMENT_BGD=38) HOẶC nhóm DIRECTOR HOẶC _PROFILE_BLD_ID.
 */
function _issue_can_see_all(){
	global $oneProfile;
	// CHỈ phòng Ban Lãnh Đạo (BGĐ=38) thấy hết — đúng yêu cầu.
	// (BỎ checkPermissionGroup('DIRECTOR') + _PROFILE_BLD_ID: chúng cấp see-all cho cả
	//  _PROFILE_TECH_ID=9/role GĐ-PGĐ ngoài phòng 38 → gây lộ việc xuyên phòng. GĐ/PGĐ thật đều đã ở phòng 38.)
	$bgd = defined('_DEPARTMENT_BGD') ? (int) _DEPARTMENT_BGD : 38;
	if((int) $oneProfile['department_id'] === $bgd){ return true; }
	if(!empty($oneProfile['list_department_id']) && strpos($oneProfile['list_department_id'], '|'.$bgd.'|') !== false){ return true; }
	return false;
}
?>