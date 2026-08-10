<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function worktime_default(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	###
	$maxtime = strtotime(sprintf('%s %s', date('d-m-Y'), '11:00:00'));
	if(time() < $maxtime)
		$is_send_report = 1;
	else 
		$is_send_report = 0;
	
	$smarty->assign('is_send_report', $is_send_report);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo nhân sự vắng mặt FH | '.$PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function worktime_open_staff(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	
	// Return
	$html = $core->build('worktime'.DS.'_ajax.open_staff.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function worktime_staff(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo nhân sự vắng mặt FH | '.$PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function worktime_set_date(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	
	$tp = Input::post('tp', 'next');
	$date = Input::post('date', "");
	$int_date = $clsISO->convertTextToTime($date);
	if($tp=='next') $date_new = strtotime("+1 day", $int_date);
	if($tp=='prev') $date_new = strtotime("-1 day", $int_date);
	// Return
	echo $clsISO->convertTimeToText($date_new);
	die();
}
function worktime_list(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile, $deviceType;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsProfile = new Profile();
	$clsWorktime = new Worktime();
	$clsProperty = new Property();
	##
	$htmlTable = "";
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		$field = "{$clsProperty->pkey},title";
		$list_departments = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
		and `parent_id`='"._DEPARTMENT_SALE_ID."' and {$clsProperty->pkey} not in(".implode(',',_GROUP_SALE_NOTIN_ID).") order by `title` ASC,`order_no` ASC", $field);
		// $clsISO->print_pre($list_departments); die();
		// or {$clsProperty->pkey} in (".implode(',', array(_DEPARTMENT_DIRECTOR_ID,_DEPARTMENT_BO_ID))."))
		if(!empty($list_departments)){
			foreach($list_departments as $key => $val){
				$department_id = $val[$clsProperty->pkey];
				$total_staffs = $clsProfile->countItem("`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
				and `department_id`='{$department_id}'");
				$list_departments[$key]['total_staffs'] = $total_staffs;
			}
		}
		$date_id = Input::post('date_id', date('d/m/Y'));
		$htmlTable.= '<div class="table-wrapper">
			<table width="100%" style="table-layout:fixed;" class="table table-bordered">
				<thead><tr>
					<th width="'.($deviceType=='phone'?'35':'15').'%" class="align-center bg-lighter">
						<span>Team/Phòng ban</span>
					</th>
					<th width="'.($deviceType=='phone'?'65':'85').'%" class="align-center bg-lighter">
						<span>Nội dung</span>
					</th>
				</tr></thead>';
			if(!empty($list_departments)){
				foreach($list_departments as $key => $val){
					$total_staffs = $val['total_staffs'];
					$department_id = $val[$clsProperty->pkey];
					if($total_staffs > 0){
						$oneWorktime = $clsWorktime->getByCond("department_id='{$department_id}' 
						and FROM_UNIXTIME(`worktime_date`,'%d/%m/%Y')='{$date_id}'");
						if(!empty($oneWorktime)){
							$content = $oneWorktime['content'];
							$content = !empty($content) 
								? json_decode(html_entity_decode($content), true) : array();
							if(!empty($content)){
								$html_content = ''; $ii= 0;
								foreach($content as $okey => $oval){
									if($oval['type']=='_ALLDAY') $type = 'cả ngày';
									if($oval['type']=='_MORNING') $type = 'buổi sáng';
									if($oval['type'] == '_AFTERNOON') $type = 'buổi chiều';
									$html_content.= ($ii==0?'':'<br />') . '&bull; <strong>'.$clsProfile->getFullName($okey).'</strong> xin nghỉ <strong>'.$type.'</strong> '.$oval['reason'];
									++$ii;
								}
								$total_leave = count($content);
								$htmlTable.= '<tr>
									<td class="text-left"><strong>'.$val['title'].'</strong> </td>
									<td class="text-left"><span class="text-danger">Đi làm '.($total_staffs-$total_leave).'/'.$total_staffs.'</span> <br />'.$html_content.'</td>
								</tr>';
							} else {
								$htmlTable.= '<tr>
									<td class="text-left"><strong>'.$val['title'].'</strong></td>
									<td class="text-primary">Đi làm đủ '.$total_staffs.'/'.$total_staffs.'</td>
								</tr>';
							}
						} else {
							$htmlTable .= '<tr>
								<td class="text-left"><strong>'.$val['title'].'</strong></td>
								<td class="text-left"><span class="text-muted">Chưa gửi báo cáo</span></td>
							</tr>';
						}
					}
				}
			}
		$htmlTable.= '
			</table>
		</div>';
	} else if($clsISO->checkPermissionGroup('SALE')) {
		$keySearch = Input::post('keySearch', "");
		$start_date = Input::post('start_date', "");
		$end_date = Input::post('end_date', "");
		##
		$cond = "`is_trash`=0";
		if(!empty($keySearch)) {
			$cond .= " and (title like '%{$keySearch}%' 
				or slug like '%".$core->replaceSpace($keySearch)."%'
			)";
		}
		$cond .= " and `user_id`='{$profile_id}'";
		#- Begin Pagination
		$current_page = (int) Input::post('page',1);
		$per_page = (int) Input::post('per_page',20);
		$total_record = $clsWorktime->countItem($cond);
		$total_page = @ceil($total_record/$per_page);
		$offset = ($current_page-1)*$per_page;
		$limitCond = " limit {$offset},{$per_page}";
		#- End Pagination
		
		$list_worktimes = $clsWorktime->getAll($cond." order by reg_date DESC".$limitCond);
		if(!empty($list_worktimes)){ $ii = 0;
			$arr_profile_cached = array();
			foreach($list_worktimes as $key => $val){
				$user_id = $val['user_id'];
				if(isset($arr_profile_cached[$user_id])){
					$oneUser = $arr_profile_cached[$user_id];
				} else {
					$oneUser = $clsProfile->getOne($user_id, "first_name,last_name,full_name,department_id,avatar");
					$arr_profile_cached[$user_id] = $oneUser;
				}
				$worktime_id = $val[$clsWorktime->pkey];
				$content = !empty($val['content']) 
					? json_decode(html_entity_decode($val['content']), true) : array();
				if(!empty($content)){
					$html_content = '<ul class="pl-3 mb-0">';
					foreach($content as $okey => $oval){
						if($oval['type']=='_ALLDAY') $type = 'cả ngày';
						if($oval['type']=='_MORNING') $type = 'buổi sáng';
						if($oval['type'] == '_AFTERNOON') $type = 'buổi chiều';
						$html_content .= '<li><strong>'.$clsProfile->getFullName($okey).'</strong> xin nghỉ <strong>'.$type.'</strong> '.$oval['reason'].'</li>';
					}
					$html_content.= '</ul>';
				} else {
					$html_content = '<span class="text-danger">Nhân sự hoạt động đầy đủ</span>';
				}
				$htmlTable.= '<tr>
					<td data-label="Team báo cáo" class="text-left">'.$clsProfile->getIndentityV4($user_id, $oneUser).'</td>
					<td data-label="Nội dung" class="text-left">'.$html_content.'</td>
					<td data-label="Ghi chú" class="text-center">'.(!empty($val['staff_notes'])?'<a data-bs-toggle="tooltip" data-bs-html="true" title="'.$val['staff_notes'].'">'.$core->makeIcon('comment').'</a>':'--').'</td>
					<td data-label="Ngày báo cáo" class="text-right">'.$clsISO->convertTimeToText($val['worktime_date']).'</td>
					<td data-label="Ngày cập nhật" class="text-right">'.$clsISO->convertTimeToText($val['upd_date'], true).'</td>
					<td class="text-center">
						<div class="dropdown">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
								<i class="bx bx-dots-vertical-rounded"></i>
							</button>
							<div class="dropdown-menu">
								'.($user_id==$profile_id?'
								<a class="dropdown-item" tp="full" onClick="$Core.worktime.open(this,event)" 
								worktime_id="'.$worktime_id.'" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>':'').'
							</div>
						</div>
					</td>
				</tr>';
				++$ii;
			}
		}
	}
	$smarty->assign('htmlTable', $htmlTable);
	// Return
	$html = $core->build('worktime'.DS.'_ajax.list.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'per_page' => $per_page,
		'current_page' => $current_page
	));
}
function worktime_open(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsProfile = new Profile();
	$clsWorktime = new Worktime();
	$uid = $clsISO->getUniqid();
	$worktime_id = (int) Input::post('worktime_id',0);
	$smarty->assign('uid', $uid);
	$smarty->assign('worktime_id', $worktime_id);
	###
	$department_id = $oneProfile['department_id'];
	$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
	$list_staffs = $clsProfile->getAll("is_trash=0 and department_id='{$department_id}' 
	and status_id<>'"._STATUS_STAFF_OFF_ID."' order by reg_date ASC", $field);
	###
	$action = "_add";
	$oneWorktime = array();
	if($worktime_id > 0){
		$action = "_edit";
		$oneWorktime = $clsWorktime->getOne($worktime_id);
		$content = !empty($oneWorktime['content']) 
			? json_decode(html_entity_decode($oneWorktime['content']), true) : array(); 
		// $clsISO->print_pre($content); die();
		foreach($list_staffs as $key => $val){
			$profile_id = $val['profile_id'];
			if(isset($content[$profile_id])){
				$list_staffs[$key]['status'] = $content[$profile_id]['status'];
				$list_staffs[$key]['type'] = $content[$profile_id]['type'];
				$list_staffs[$key]['reason'] = $content[$profile_id]['reason'];
			} else {
				$list_staffs[$key]['status'] = 0;
				$list_staffs[$key]['type'] = "";
				$list_staffs[$key]['reason'] = "";
			}
		}
	} else {
		if($clsWorktime->countItem("department_id='{$department_id}' 
		and FROM_UNIXTIME(`worktime_date`,'%d/%m/%Y')='".date('d/m/Y')."'") > 0){
			echo json_encode(array(
				'result' => 'error'
			)); die();
		} else {
			foreach($list_staffs as $key => $val){
				$list_staffs[$key]['status'] = 0;
				$list_staffs[$key]['type'] = "";
				$list_staffs[$key]['reason'] = "";
			}
		}
	}
	$smarty->assign('action', $action);
	$smarty->assign('list_staffs', $list_staffs);
	$smarty->assign('department_id', $department_id);
	$smarty->assign('oneWorktime', $oneWorktime);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('worktime'.DS.'_ajax.open.tpl');
	echo json_encode(array(
		'result' => 'success',
		'uid' => $uid,
		'html' => $html
	)); die();
}
function worktime_save(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsProfile = new Profile();
	$clsWorktime = new Worktime();
	
	$msg = "_error";
	$worktime_id = (int) Input::post('worktime_id', 0);
	$worktime_date = Input::post('worktime_date', "");
	$worktime_date = !empty($worktime_date) ? $clsISO->toTime($worktime_date) : 0;
	$content = Input::post('content');
	if($worktime_id > 0){
		if($clsWorktime->updateOne($worktime_id, array(
			'worktime_date' => $worktime_date,
			'user_id_update' => $profile_id,
			'department_id' => Input::post('department_id', 0),
			'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
			'staff_notes' => Input::post('staff_notes'),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	} else {
		$worktime_id = $clsWorktime->getMaxId();
		// $clsWorktime->setDebug();
		if($clsWorktime->insert(array(
			$clsWorktime->pkey => $worktime_id,
			'worktime_date' => $worktime_date,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'department_id' => Input::post('department_id', 0),
			'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
			'staff_notes' => Input::post('staff_notes'),
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}