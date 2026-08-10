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
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	/*=============Title & Description Page==================*/
	$title_page = 'OKRs - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_list(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$profile_id,$oneProfile,$clsISO;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	$smarty->assign('clsOkrs', $clsOkrs);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$department_id = $oneProfile['department_id'];
	$cond = "`is_trash`=0 and `parent_id`=0"; // and ((`type_id`='"._TYPE_COMPANY_OKRS_ID."') or (`type_id`='"._TYPE_STAFF_OKRS_ID."' and `for_id`='{$profile_id}') or (`type_id`='"._TYPE_DEPARTMENT_OKRS_ID."' and `for_id`='{$department_id}'))
	# Begin pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',10);
	$total_record = $clsOkrs->countItem($cond);
	$total_page = @ceil($total_record/$total_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	# End pagination
	$list_okrs = $clsOkrs->getAll($cond." order by reg_date ASC".$limitCond);
	if(!empty($list_okrs)){
		foreach($list_okrs as $key => $val){
			$kr_information = $val['kr_information'];
			$kr_information = !empty($kr_information) 
				? json_decode(html_entity_decode($kr_information), true) 
				: array();
			$total_kr = !empty($kr_information) ? count($kr_information) : 0;
			$list_okrs[$key]['total_kr'] = $total_kr;
		}
	}
	$smarty->assign('list_okrs', $list_okrs);
	// $clsISO->print_pre($list_okrs); die();
	// Return
	$smarty->assign('template_type', 'list');
	$html = $core->build('_ajax.list.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open(){
	// error_reporting(E_ALL);
	// ini_set('display_errors',1);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	$smarty->assign('clsOkrs', $clsOkrs);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$uid = $clsISO->getUniqid();
	$okrs_id = (int) Input::post('okrs_id', "0");
	#
	$titlePage = "Thêm mục tiêu";
	$action = "_add"; $oneOkrs = array(
		'period_id' => 0,
		'group_id' => 0,
		'type_id' => 1,
		'for_id' => $profile_id
	);
	if($okrs_id > 0){
		$action = "_edit";
		$titlePage = "Sửa mục tiêu";
		$oneOkrs = $clsOkrs->getOne($okrs_id);
		$kr_information = $oneOkrs['kr_information'];
		$list_krs = !empty($kr_information) 
			? json_decode(html_entity_decode($kr_information), true) 
			: array();
		$smarty->assign('list_krs', $list_krs);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('okrs_id', $okrs_id);
	$smarty->assign('action', $action);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('oneOkrs', $oneOkrs);
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_add_result(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$toId = Input::post('toId');
	#
	$uid = $clsISO->getUniqid();
	$html.= '<div class="okrs__result-item position-relative okrs__result_'.$toId.' bg-lighter mt-2 p-3 rounded-3">
		<a class="close" onClick="$Core.okrs.delete_result(this, event)"></a>
		<div class="form-group form-row mb-2">
			<div class="col-12 col-md-6 mb-2 mb-lg-0">
				<label class="form-label mb-1">Kết quả chính</label>
				<input class="form-control required" placeholder="Kết quả chính" name="kr_information['.$uid.'][main_result]" />
			</div>
			<div class="col-12 col-md-3 mb-2 mb-lg-0">
				<label class="form-label mb-1">Mục tiêu</label>
				<input class="form-control required numberonly" placeholder="Mục tiêu" name="kr_information['.$uid.'][target]" />
			</div>
			<div class="col-12 col-md-3">
				<label class="form-label mb-1">Đơn vị</label>
				<select class="form-control required form-select" placeholder="Kế hoạch" name="kr_information['.$uid.'][unit_id]">
					'.$clsProperty->getSelectByProperty('_UNIT').'
				</select>
			</div>
		</div>
		<div class="form-group form-row">
			<div class="col-12 col-md-6 mb-2 mb-lg-0">
				<label class="form-label mb-1">Kế hoạch</label>
				<textarea class="form-control" cols="255" placeholder="Kế hoạch" name="kr_information['.$uid.'][plan]" rows="2"></textarea>
			</div>
			<div class="col-12 col-md-6">
				<label class="form-label mb-1">Kết quả thực tế</label>
				<textarea class="form-control" cols="255" placeholder="Kết quả thực tế" name="kr_information['.$uid.'][result]" rows="2"></textarea>
			</div>
		</div>
	</div>';
	// Return
	echo $html; die();	
}
function default_load_object(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	###
	$type_id = (int) Input::post('type_id', 0);
	if($type_id==1){
		$html= '<label class="form-label mb-1">Người được áp dụng</label>
		<div class="clearfix"></div>
		<select class="iso-selectizeNotSearch required" name="for_id" data-width="100%" data-placeholder="Người tham gia" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" data-width="100%"></select>';
	} else if($type_id==2){
		$html= '<label class="form-label mb-1">Phòng ban áp dụng</label>
		<div class="clearfix"></div>
		<select name="for_id" data-width="100%" class="form-control iso-select2 required">
			'.$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',0,'Phòng ban').'
		</select>';
	} else {
		$html= '<label class="form-label mb-1">Áp dụng</label>
		<div class="clearfix"></div>
		<input type="hidden" name="for_id" value="0" />
		<span class="d-block border radius-4 text-muted" style="padding:0.4rem">
			'.$clsISO->makeIcon('bx-group','Tất cả mọi người').'
		</span>';
	}
	// Return
	echo $html; die();
}
function default_load_okrs_parent(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$oneProfile,$profile_id;
	$clsProperty = new Property();
	$clsOkrs = new Okrs();
	#
	$list_groups = array(
		'1' => 'Cá nhân',
		'2' => 'Phòng ban',
		'3' => 'Công ty'
	);
	#
	$html = '';
	$cond = "`is_trash`=0";
	$department_id = $oneProfile['department_id'];
	$period_id = (int) Input::post('period_id', 0);
	if($period_id > 0){
		$cond.= " and `period_id`='{$period_id}'";
		foreach($list_groups as $group_id => $group_name){
			$field = "{$clsOkrs->pkey},`title`";
			$where = "{$cond} and `type_id`='{$group_id}'";
			if($group_id==_TYPE_STAFF_OKRS_ID){
				$where.= " and `for_id`='{$profile_id}'";
			} else if($group_id==_TYPE_DEPARTMENT_OKRS_ID){
				$where.= " and `for_id`='{$department_id}'";
			}
			// $clsISO->print_pre($where); die();
			$list_okrs = $clsOkrs->getAll($where." order by reg_date DESC", $field);
			if(!empty($list_okrs)){
				$html.='<optgroup label="'.$group_name.'">';
				foreach($list_okrs as $key => $val){
					$html.= '<option value="'.$val[$clsOkrs->pkey].'">'.$val['title'].'</option>';
				}
				$html.= '</optgroup>';
				unset($list_okrs);
			}
		}
	}
	// Return
	echo $html; die();
}
function default_load_list_orks(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$profile_id,$oneProfile;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	
	$_results = array();
	$department_id = $oneProfile['department_id'];
	$cond = "`is_trash`=0 and `parent_id`=0"; // and ((`type_id`='"._TYPE_COMPANY_OKRS_ID."') or (`type_id`='"._TYPE_STAFF_OKRS_ID."' and `for_id`='{$profile_id}') or (`type_id`='"._TYPE_DEPARTMENT_OKRS_ID."' and `for_id`='{$department_id}'))
	$list_okrs = $clsOkrs->getAll($cond, "{$clsOkrs->pkey},title");
	if(!empty($list_okrs)){
		foreach($list_okrs as $okey => $oval){
			$_results = array(
				'id' => $val[$clsOkrs->pkey],
				'title' => $val['title']
			);
		}
	}
	// Return
	echo json_encode($_results); die();
}
function default_pop_save_okrs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	
	$msg = "_error";
	$okrs_id = (int) Input::post('okrs_id', 0);
	$kr_information = Input::post('kr_information', array());
	if($okrs_id==0){
		$okrs_id = $clsOkrs->getMaxId();
		if($clsOkrs->insert(array(
			$clsOkrs->pkey => $okrs_id,
			'title' => Input::post('title'),
			'period_id' => (int) Input::post('period_id',0),
			'parent_id' => (int) Input::post('parent_id',0),
			'group_id' => (int) Input::post('group_id',0),
			'type_id' => (int) Input::post('type_id'),
			'for_id' => (int) Input::post('for_id'),
			'kr_information' => json_encode($kr_information, JSON_UNESCAPED_UNICODE),
			'is_public' => Input::post('is_public',0),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	} else {
		if($clsOkrs->updateOne($okrs_id, array(
			'title' => Input::post('title'),
			'period_id' => (int) Input::post('period_id',0),
			'parent_id' => (int) Input::post('parent_id',0),
			'group_id' => (int) Input::post('group_id',0),
			'type_id' => (int) Input::post('type_id',0),
			'for_id' => (int) Input::post('for_id',0),
			'kr_information' => json_encode($kr_information, JSON_UNESCAPED_UNICODE),
			'is_public' => Input::post('is_public',0),
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_delete(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$okrs_id = (int) Input::post("okrs_id", 0);
	if($okrs_id > 0){
		if($clsOkrs->deleteOne($okrs_id)){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_view_kr(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$okrs_id = (int) Input::post('okrs_id', 0);
	$oneOkrs = $clsOkrs->getOne($okrs_id);
	$titlePage = $clsOkrs->getTitle($okrs_id, $oneOkrs);
	$permiss_action = ($oneOkrs['user_id']==$profile_id) ? 1 : 0;
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('okrs_id', $okrs_id);
	$smarty->assign('oneOkrs', $oneOkrs);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('permiss_action', $permiss_action);
	// Return
	$smarty->assign('template_type', 'view_kr');
	$html = $core->build('_ajax.view_kr.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_kr(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$kr_id = Input::post('kr_id');
	$okrs_id = (int) Input::post('okrs_id', 0);
	#
	$action	= "_add"; $oneKr = array('unit_id' => 0);
	$titlePage 	= "Thêm kết quả chính";
	if(!empty($kr_id)){
		$kr_information = $clsOkrs->getOneField('kr_information', $okrs_id);
		$list_krs = !empty($kr_information) 
			? json_decode(html_entity_decode($kr_information), true) : array();
		if(!empty($kr_id) && !empty($list_krs) && @array_key_exists($kr_id, $list_krs)){
			$titlePage = "Cập nhật kết quả chính";
			$oneKr = $list_krs[$kr_id];
			$action = "_edit";
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('kr_id', $kr_id);
	$smarty->assign('oneKr', $oneKr);
	$smarty->assign('action', $action);
	$smarty->assign('okrs_id', $okrs_id);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('template_type', 'open_kr');
	$html = $core->build('_ajax.view_kr.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_kr(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	#
	$kr_id = Input::post("kr_id");
	$okrs_id = (int) Input::post("okrs_id", 0);
	$kr_information = $clsOkrs->getOneField('kr_information', $okrs_id);
	$list_krs = !empty($kr_information) 
		? json_decode(html_entity_decode($kr_information), true) : array();
	// $clsISO->print_pre($kr_id); die();
	#
	$msg = "_error";
	if(!empty($kr_id) && !empty($list_krs) && array_key_exists($kr_id, $list_krs)){
		$list_krs[$kr_id]['main_result'] = Input::post('main_result');
		$list_krs[$kr_id]['target'] = Input::post('target');
		$list_krs[$kr_id]['unit_id'] = Input::post('unit_id');
		$list_krs[$kr_id]['plan'] = Input::post('plan');
		$list_krs[$kr_id]['result'] = Input::post('result');
		if($clsOkrs->updateOne($okrs_id, array(
			'kr_information' => json_encode($list_krs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	} else {
		$list_krs[$clsISO->getUniqid()] = array(
			'main_result' => Input::post('main_result'),
			'target' => Input::post('target'),
			'unit_id' => Input::post('unit_id'),
			'plan' => Input::post('plan'),
			'result' => Input::post('result')
		);
		// $clsISO->print_pre($list_krs); die();
		if($clsOkrs->updateOne($okrs_id, array(
			'kr_information' => json_encode($list_krs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_kr(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	
	$kr_id = Input::post("kr_id");
	$okrs_id = (int) Input::post("okrs_id", 0);
	$kr_information = $clsOkrs->getOneField('kr_information', $okrs_id);
	$list_krs = !empty($kr_information) 
		? json_decode(html_entity_decode($kr_information), true) : array();
	// $clsISO->print_pre($kr_id); die();
	#
	$msg = "_error";
	if(!empty($kr_id) && !empty($list_krs) && @array_key_exists($kr_id, $list_krs)){
		unset($list_krs[$kr_id]);
		if($clsOkrs->updateOne($okrs_id, array(
			'kr_information' => json_encode($list_krs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_load_list_kr(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOkrs = new Okrs();
	$clsProperty = new Property();
	$smarty->assign('clsOkrs', $clsOkrs);
	$smarty->assign('clsProperty', $clsProperty);
	//error_reporting(E_ALL);
	//ini_set('display_errors',1);
	$okrs_id = (int) Input::post('okrs_id', 0);
	$oneOkrs = $clsOkrs->getOne($okrs_id,"kr_information,user_id");
	$permiss_action = ($oneOkrs['user_id']==$profile_id) ? 1 : 0;
	$kr_information = $oneOkrs['kr_information'];
	$list_krs = !empty($kr_information) 
		? json_decode(html_entity_decode($kr_information), true) 
		: array();
	$smarty->assign('okrs_id', $okrs_id);
	$smarty->assign('list_krs', $list_krs);
	$smarty->assign('permiss_action', $permiss_action);
	// Return
	$smarty->assign('template_type', 'list_kr');
	$html = $core->build('_ajax.view_kr.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
