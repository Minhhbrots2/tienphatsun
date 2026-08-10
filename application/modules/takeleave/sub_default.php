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

	global $assign_list,$smarty,$dbconn,$core,$adminid,$clsISO,$clsConfiguration,$title_page,$description_page,$keyword_page;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsTakeLeave = new TakeLeave();

	$smarty->assign('clsProfile',$clsProfile);

	$smarty->assign('clsProperty',$clsProperty);

	$smarty->assign('clsTakeLeave',$clsTakeLeave);

	

	$scriptJs = '';

	$cmd = Input::get("cmd", "_default");

	if($cmd=="_approval"){

		$tp = Input::get('tp');

		$string = Input::get('takeleave_id', 0);

		$takeleave_id = !empty($string) ? $clsISO->decryptID($string): 0;	

		$scriptJs.= '<a class="autoclick_'.$tp.'_'.$takeleave_id.'" tp="'.$tp.'" 

		takeleave_id="'.$takeleave_id.'" onClick="$Core.takeleave.approval(this, event)" ></a>

		<script type="text/javascript">

			$(function(){

				setTimeout(() => {

					//history.pushState("", "", "/take-leave/");

					$(\'.autoclick_'.$tp.'_'.$takeleave_id.'\').trigger(\'click\');

				}, 1000);

			})

		</script>';

	}

	$smarty->assign("show",$show);

	$smarty->assign("scriptJs",$scriptJs);

	##

	$lstGroup = $dbconn->getAssoc("select user_group_id,name from default_user_group");

	$lstUser = $clsProfile->getAll("is_trash=0 and is_active='1'",$clsProfile->pkey.",full_name,first_name,last_name");

		$smarty->assign('lstGroup',$lstGroup);

		$smarty->assign('lstUser',$lstUser);

	$has_unapproved = 0;

	if($adminid==1)	$has_unapproved = 1;

	$is_curator = input::get('is_curator',0);

	if($is_curator)	$has_unapproved = 1;

	$is_head_of_dep = input::get('is_head_of_dep',0);

	if($is_head_of_dep)	$has_unapproved = 1;

	$is_hrad = input::get('is_hrad',0);

	if($is_hrad)	$has_unapproved = 1;

		$smarty->assign('has_unapproved',$has_unapproved);

	#

	$DateType = 'reg_date';

	$start_date = '';

	$is_staff = input::get('is_staff',0);

	$is_director = input::get('is_director',0);

	

	if($is_staff==1||$is_director==1||$is_curator==1||$is_head_of_dep==1||$is_hrad==1){

		$DateType = 'start_date';

		$start_date = '01.01.'.date("Y");;

	}

	$smarty->assign('DateType',$DateType);

	$smarty->assign('start_date',$start_date);

	 /*=============Title & Description Page==================*/

	$title_page = 'Nghỉ phép | ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $title_page;

	$assign_list["description_page"] = $description_page;

	$keyword_page = $title_page;

	$assign_list["keyword_page"] = $keyword_page;

}

function default_open(){

	global $profile_id,$oneProfile,$smarty,$dbconn,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	//ini_set('display_errors',1);

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsTakeLeave = new TakeLeave();

	$clsEmailTemplate = new EmailTemplate();

	$smarty->assign('clsProperty',$clsProperty);

	$smarty->assign('clsTakeLeave',$clsTakeLeave);

	$smarty->assign('clsProfile',$clsProfile);

	

	$uid = $clsISO->getUniqid();

	$role_id = (int) $oneProfile['role_id'];

	$department_id = (int) $oneProfile['department_id'];

	$takeleave_id = (int) Input::post('takeleave_id', 0);

	$position = $role_id > 0 ? $clsProperty->getTitle($role_id) : $core->get_Lang('Staff');

	$smarty->assign('position',$position);

	

	$action = "_add";

	$oneTakeLeave = array(

		'reason' => "",

		'number_day' => 0,

		'start_date' => time(),

		'end_date' => time(),

		'curator_user_id' => 0,

		'cat_property_id' => 0,

		'number_day_no_paid_leave' => 0,

		'number_day_paid_leave_last_year' => 0,

		'number_day_paid_leave_this_year' => 0,

	);

	if($takeleave_id > 0){

		$action = "_edit";

		$oneTakeLeave = $clsTakeLeave->getOne($takeleave_id);

	}

	$smarty->assign('uid',$uid);

	$smarty->assign('action',$action);

	$smarty->assign('takeleave_id',$takeleave_id);

	$smarty->assign('oneTakeLeave',$oneTakeLeave);

	$smarty->assign('department_id',$department_id);

	

	$lstCurator = $clsProfile->getLstCurator();

	$smarty->assign('lstCurator',$lstCurator);

	// $clsISO->print_pre($lstCurator); die();

	$property_field = "{$clsProperty->pkey},title";

	$lstCat = $clsProperty->getAll("`is_trash`=0 and `parent_id`='0' 

		and `property_type`='CAT_TAKELEAVE' order by `order_no` ASC", $property_field);

	$smarty->assign('lstCat',$lstCat);

	// Output

	$html = $core->build('_ajax.open.tpl');

	echo json_encode(array(

		"msg"	=> "ok",

		"uid"	=> $uid,

		"html"	=> $html,

	));

}

function default_save(){

	global $assign_list,$smarty,$dbconn,$core,$profile_id,$clsISO,$clsConfiguration,$oneProfile;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsTakeLeave = new TakeLeave();

	$clsEmailTemplate = new EmailTemplate();

	

	$department_id = $oneProfile['department_id'];

	$takeleave_id = (int) Input::post('takeleave_id', 0);

	$position = Input::post('position');

	$number_day_paid_leave_this_year = (int) Input::post('number_day_paid_leave_this_year',0);

	$number_day_paid_leave_last_year = (int) Input::post('number_day_paid_leave_last_year',0);

	$number_day_no_paid_leave = (int) Input::post('number_day_no_paid_leave',0);

	$curator_user_id = (int) Input::post('curator_user_id',0);

	$cat_property_id = (int) Input::post('cat_property_id',0);

	$number_day = (int) Input::post('number_day',0);

	$start_date = Input::post('start_date');

	$start_time = Input::post('start_time');

	$end_date = Input::post('end_date');

	$end_time = Input::post('end_time');

	$reason = Input::post('reason');

	$start_month = $clsISO->getDateCreateFormat($start_date,"n");

	//$clsISO->print_pre($number_day);die;

	$takeleave_configs = $clsConfiguration->getValue('takeleave_configs');

	$takeleave_configs = $clsISO->to_array_json($takeleave_configs);

	$approver_configs = isset($takeleave_configs['approver']) 

		? $takeleave_configs['approver'] : array();

	// $clsISO->print_pre($approver_configs); die();

	if(empty($position)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập chức vụ",

			"name"	=> "position"

		)); die;

	}

	if(empty($curator_user_id)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập Người phụ trách khi bạn nghỉ phép!",

			"name"	=> "curator_user_id"

		)); die;

	}if($cat_property_id == 0){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng chọn loại nghỉ phép!",

			"name"	=> "cat_property_id"

		)); die;

	}

	if(empty($number_day_paid_leave_this_year) 

		&& empty($number_day_paid_leave_last_year)

		&& empty($number_day_no_paid_leave)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập số ngày nghỉ phép"

		)); die;

	}

	if(!empty($number_day_paid_leave_last_year) && $start_month>_MONTH_TAKE_LEAVE_RESET){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Ngày nghỉ phép năm ngoái hết hiệu lực sau tháng "._MONTH_TAKE_LEAVE_RESET,

			"name"	=> "number_day_paid_leave_last_year"

		)); die;

	}

	$takeleave_valid = $clsTakeLeave->getInfoTakeleave($profile_id,$start_date,'array');	

	if(!empty($number_day_paid_leave_last_year) && $start_month<=_MONTH_TAKE_LEAVE_RESET

		&& $number_day_paid_leave_last_year > $takeleave_valid['takeleave_valid_last_year']){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Số ngày nghỉ phép năm trước không hợp lệ",

			"name"	=> "number_day_paid_leave_last_year"

		)); die;

	}

	if(!empty($number_day_paid_leave_this_year) 

		&& $number_day_paid_leave_this_year>$takeleave_valid['takeleave_valid_this_year']){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Số ngày nghỉ phép năm nay không hợp lệ",

			"name"	=> "number_day_paid_leave_this_year"

		)); die;

	}

	if(empty($number_day)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập tổng số ngày nghỉ phép",

			"name"	=> "number_day"

		)); die;

	}

	if($number_day!=($number_day_paid_leave_this_year+$number_day_paid_leave_last_year+$number_day_no_paid_leave)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Tổng số ngày nghỉ phép không khớp"

		));die;

	}

	if(empty($start_date)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập ngày bắt đầu nghỉ phép",

			"name"	=> "start_date"

		));die;

	}

	if(empty($end_date)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập ngày hết nghỉ phép",

			"name"	=> "end_date"

		));die;

	}

	if($start_date==$end_date && $start_time==$end_time){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Thời gian nghỉ phép trong ngày bị trùng",

			"name"	=> "end_time"

		));die;

	}

	if(empty($reason)){

		echo json_encode(array(

			"msg"	=> "error",

			"html"	=> "Vui lòng nhập lí do nghỉ phép"

		));die;

	}

	//$clsISO->print_pre($_POST);die;

	$msg = "_error";

	if($takeleave_id > 0){

		if($clsTakeLeave->updateOne($takeleave_id, array(

			"position" => $position,

			"number_day_paid_leave_this_year" => $number_day_paid_leave_this_year,

			"number_day_paid_leave_last_year" => $number_day_paid_leave_last_year,

			"number_day_no_paid_leave" => $number_day_no_paid_leave,

			"number_day" => $number_day,

			'start_date' => $clsISO->toYMD($start_date),

			'start_time' =>$start_time,

			'end_date' =>$clsISO->toYMD($end_date),

			"end_time" => $end_time,

			"reason" => $reason,

			"curator_user_id" => $curator_user_id,

			"cat_property_id" => $cat_property_id,

			'user_id_update' => $profile_id,

			'upd_date' => time()

		))){

			$msg = "_success";

		}

	} else {

		$takeleave_id = $clsTakeLeave->getMaxId();

		if($clsTakeLeave->insert(array(

			"takeleave_id" => $takeleave_id,

			"position" => $position,

			"number_day_paid_leave_this_year" => $number_day_paid_leave_this_year,

			"number_day_paid_leave_last_year" => $number_day_paid_leave_last_year,

			"number_day_no_paid_leave" => $number_day_no_paid_leave,

			"number_day" => $number_day,

			'start_date' =>$clsISO->toYMD($start_date),

			'start_time' =>$start_time,

			'end_date' =>$clsISO->toYMD($end_date),

			"end_time" => $end_time,

			"reason" => $reason,

			"curator_user_id" => $curator_user_id,

			"cat_property_id" => $cat_property_id,

			"user_id" => $profile_id,

			'user_id_update' => $profile_id,

			"reg_date" => time(),

			'upd_date' => time()

		))){

			$msg = "_success";

			$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_TAKELEAVE_ID);

			$fromname = $clsEmailTemplate->getFromName(_MAIL_TAKELEAVE_ID, $oneEmailTemplate);

			$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_TAKELEAVE_ID, $oneEmailTemplate);

			$_template_subject = $clsEmailTemplate->getSubject(_MAIL_TAKELEAVE_ID, $oneEmailTemplate);

			$_template_message = $clsEmailTemplate->getContent(_MAIL_TAKELEAVE_ID,$oneEmailTemplate);	

			$replace_fields = array(

				'{staff_name}' => $clsProfile->getFullName($profile_id, $oneProfile),

				'{department_name}' => $clsProperty->getTitle($department_id),

				'{position}' => $position,

				'{year}' => date('Y'),

				'{info_takeleave}' => $clsTakeLeave->getInfoTakeleave($profile_id),

				'{number_noPaidLeave}' => $clsTakeLeave->getNumberNoPaidLeave($profile_id),

				'{number_day}' => $number_day,

				'{start_date_time}' => sprintf('%s %s', $clsISO->convertDateCreateFormat($start_date), $start_time),

				'{end_date_time}' => sprintf('%s %s', $clsISO->convertDateCreateFormat($end_date), $end_time),

				'{reason}' => $reason

			);

			// approved_by

			$list_logs = $leave_approver = array();

			$lstTP = array("curator","director_of_dep","head_of_dep","hrad", "director");

			foreach($lstTP as $tp){

				if($tp=="curator" 

					&& isset($approver_configs[$tp]['status']) && (int) $approver_configs[$tp]['status'] == 1){

					if($curator_user_id > 0 && $curator_user_id != $profile_id){

						$leave_approver[$tp] = array(

							'profile_id' => $curator_user_id,

							'approval_status' => 0							

						);

						$oProfile = $clsProfile->getOne($curator_user_id, "first_name,last_name,full_name,email");

						$replace_fields['{approver_name}'] = $clsProfile->getFullName($curator_user_id, $oProfile);

						$replace_fields['{message_title}'] = '[NQL] Có đơn xin nghỉ phép cần bạn xác nhận';

						$replace_fields['{link_approval}'] = '<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id, $tp).'">'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>';

						$subject = $_template_subject;

						$message  = $_template_message;

						foreach($replace_fields as $key => $val){

							$subject = str_replace($key, $val, $subject);

							$message = str_replace($key, $val, $message);

						}

						$toemail = 'vanthiembui.it@gmail.com';// $oProfile['toemail'];

						$toname = $clsProfile->getFullName($curator_user_id, $oProfile);

						$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

					}

				} else if($tp == 'head_of_dep' 

					&& isset($approver_configs[$tp]['status']) && (int) $approver_configs[$tp]['status'] == 1){

					$head_of_dep = $clsProfile->getHeadOfDep($department_id);

					if($head_of_dep > 0 && $head_of_dep != $profile_id){

						$leave_approver[$tp] = array(

							'profile_id' => $head_of_dep,

							'approval_status' => 0							

						);

						$oProfile = $clsProfile->getOne($curator_user_id, "first_name,last_name,full_name,email");

						$replace_fields['{approver_name}'] = $clsProfile->getFullName($curator_user_id, $oProfile);

						$replace_fields['{message_title}'] = '[TP] Có đơn xin nghỉ phép cần bạn xác nhận';

						$replace_fields['{link_approval}'] = '<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id, $tp).'">'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>';

						$subject = $_template_subject;

						$message  = $_template_message;

						foreach($replace_fields as $key => $val){

							$subject = str_replace($key, $val, $subject);

							$message = str_replace($key, $val, $message);

						}

						$toemail = 'vanthiembui.it@gmail.com';// $oProfile['toemail'];

						$toname = $clsProfile->getFullName($curator_user_id, $oProfile);

						$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

					} else if($head_of_dep > 0 && $head_of_dep == $profile_id){

						$leave_approver[$tp] = array(

							'profile_id' => $head_of_dep,

							'approval_status' => 1							

						);

					}

				} else if($tp == 'hrad' 

					&& isset($approver_configs[$tp]['status']) && (int) $approver_configs[$tp]['status'] == 1){

					if(isset($approver_configs[$tp]['approver_id']) && (int) $approver_configs[$tp]['approver_id'] > 0){

						$hrad_id = $approver_configs[$tp]['approver_id'];

						if($profile_id != $hrad_id){

							$leave_approver[$tp] = array(

								'profile_id' => $hrad_id,

								'approval_status' => 0							

							);

							$oProfile = $clsProfile->getOne($hrad_id, "first_name,last_name,full_name,email");

							$replace_fields['{approver_name}'] = $clsProfile->getFullName($hrad_id, $oProfile);

							$replace_fields['{message_title}'] = '[HCNS] Có đơn xin nghỉ phép cần bạn xác nhận';

							$replace_fields['{link_approval}'] = '<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id, $tp).'">'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>';

							$subject = $_template_subject;

							$message  = $_template_message;

							foreach($replace_fields as $key => $val){

								$subject = str_replace($key, $val, $subject);

								$message = str_replace($key, $val, $message);

							}

							// $clsISO->print_pre($message); die();

							$toemail = 'vanthiembui.it@gmail.com';// $oProfile['toemail'];

							$toname = $clsProfile->getFullName($hrad_id, $oProfile);

							$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

						} else {

							$leave_approver[$tp] = array(

								'profile_id' => $approver_configs[$tp]['approver_id'],

								'approval_status' => 1							

							);

						}

					}

				} else if($tp == 'director_of_dep' 

					&& isset($approver_configs[$tp]['status']) && (int) $approver_configs[$tp]['status'] == 1){

					$director_of_dep = $clsProfile->getDirectorOfDep($department_id);

					if($director_of_dep > 0 && $director_of_dep != $profile_id){

						$leave_approver[$tp] = array(

							'profile_id' => $director_of_dep,

							'approval_status' => 0							

						);

						$oProfile = $clsProfile->getOne($director_of_dep, "first_name,last_name,full_name,email");

						$replace_fields['{approver_name}'] = $clsProfile->getFullName($director_of_dep, $oProfile);

						$replace_fields['{message_title}'] = '[GĐBP] Có đơn xin nghỉ phép cần bạn xác nhận';

						$replace_fields['{link_approval}'] = '<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id, $tp).'">'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>';

						$subject = $_template_subject;

						$message  = $_template_message;

						foreach($replace_fields as $key => $val){

							$subject = str_replace($key, $val, $subject);

							$message = str_replace($key, $val, $message);

						}

						$toemail = 'vanthiembui.it@gmail.com';// $oProfile['toemail'];

						$toname = $clsProfile->getFullName($director_of_dep, $oProfile);

						$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

					} else if($director_of_dep > 0 && $director_of_dep == $profile_id){

						$leave_approver[$tp] = array(

							'profile_id' => $director_of_dep,

							'approval_status' => 1							

						);

					}

				} else if($tp == 'director' 

					&& isset($approver_configs[$tp]['status']) && (int) $approver_configs[$tp]['status'] == 1){

					if(isset($approver_configs[$tp]['approver_id']) && (int) $approver_configs[$tp]['approver_id'] > 0){

						$director_id = $approver_configs[$tp]['approver_id'];

						if($profile_id != $director_id){

							$leave_approver[$tp] = array(

								'profile_id' => $director_id,

								'approval_status' => 0							

							);

							$oProfile = $clsProfile->getOne($director_id, "first_name,last_name,full_name,email");

							$replace_fields['{approver_name}'] = $clsProfile->getFullName($director_id, $oProfile);

							$replace_fields['{message_title}'] = '[BGĐ] Có đơn xin nghỉ phép cần bạn xác nhận';

							$replace_fields['{link_approval}'] = '<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id, $tp).'">'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>';

							$subject = $_template_subject;

							$message  = $_template_message;

							foreach($replace_fields as $key => $val){

								$subject = str_replace($key, $val, $subject);

								$message = str_replace($key, $val, $message);

							}

							$toemail = 'vanthiembui.it@gmail.com';// $oProfile['toemail'];

							$toname = $clsProfile->getFullName($director_id, $oProfile);

							$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

						} else {

							$leave_approver[$tp] = array(

								'profile_id' => $director_id,

								'approval_status' => 0							

							);

						}

					}

				}

			}

			// Cật nhật người duyệt

			// $clsISO->print_pre($leave_approver); die();

			$clsTakeLeave->updateOne($takeleave_id, array(

				'leave_approver' => json_encode($leave_approver, JSON_UNESCAPED_UNICODE)

			));

		}

	}

	// Return

	echo json_encode(array(

		"msg"	=> $msg,

		"html"	=> '',

	));

}

function default_view(){

	global $assign_list,$smarty,$dbconn,$core,$profile_id,$clsISO,$clsConfiguration,$oneProfile;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsTakeLeave = new TakeLeave();

	$uid = $clsISO->getUniqid();

	$takeleave_id = (int) Input::post('takeleave_id', 0);

	

	$oneTakeLeave = $clsTakeLeave->getOne($takeleave_id);

	$leave_approver = $oneTakeLeave['leave_approver'];

	$leave_approver_arrs = $clsISO->to_array_json($leave_approver);

	// $clsISO->print_pre($leave_approver_arrs); die();

	$smarty->assign('leave_approver_arrs', $leave_approver_arrs);

	$smarty->assign('takeleave_id', $takeleave_id);

	$smarty->assign('oneTakeLeave', $oneTakeLeave);

	// Return

	$html = $core->build('_ajax.view.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_list_takeleave(){

	global $assign_list,$smarty,$dbconn,$core,$profile_id,$clsISO,$clsConfiguration,$oneProfile;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsTakeLeave = new TakeLeave();

	//ini_set('display_errors',1);

	$sortby =  Input::post('sortby','reg_date');

	$sorttype =  Input::post('sorttype','desc');

	$html = '<div class="table-container no-shadow text-nowrap overflow-x-auto">

	<table cellspacing="0" cellpadding="0" id="TableListTakeLeaveGlobe" class="table table-striped mb-0" width="100%">

		<thead><tr>

			<th class="align-center h-px-35 bg-lighter text-center">Mã</th>

			<th class="align-center h-px-35 bg-lighter text-left">Họ và tên</th>

			<th class="align-center h-px-35 bg-lighter text-left">Phòng ban</th>

			<th class="align-center h-px-35 bg-lighter text-left">Loại phép</th>

			<th class="align-center h-px-35 bg-lighter text-left">Số ngày nghỉ</th>

			<th class="align-center h-px-35 bg-lighter text-left" width="240">Ngày</th>

			<th class="align-center h-px-35 bg-lighter text-center">Lí do</strong></th>

			<th class="align-center h-px-35 bg-lighter text-center">Duyệt</th>

			<th class="align-center h-px-35 bg-lighter text-center">Ngày tạo</th>

			<th class="align-center h-px-35 bg-lighter text-center" width="40"></th>

		</tr></thead>';

	$current_page = (int) Input::post('page',1);

	$per_page = (int) Input::post('per_page',20);

	$keySearch = Input::post('keySearch');

	$date_type = Input::post('date_type', 'reg_date');

	$start_date = Input::post('start_date');

	$end_date = Input::post('end_date');

	$is_approval = Input::post('is_approval');

	$group_id = (int) Input::post('department_id', 0);

	$user_id = Input::post('user_id');

	$is_staff = Input::post('is_staff');

	$is_curator = Input::post('is_curator');

	$is_director = Input::post('is_director');

	$is_head_of_dep = Input::post('is_head_of_dep');

	$is_director_of_dep = Input::post('is_director_of_dep');

	$is_hrad = Input::post('is_hrad');

	

	$cond = "`t1`.`is_trash`= 0";

	$department_id = !empty($oneProfile['department_id']) 

		? (int) $oneProfile['department_id'] : 0;

	$role_id = !empty($oneProfile['role_id']) 

		? (int) $oneProfile['role_id'] : 0;

	if(!in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_DIRECTOR_ID))){

		if(in_array($role_id, array(_ROLE_GD_SALE, _ROLE_PGD_SALE))){

			$list_roles = array();

			$clsProperty->getChilds($role_id, $list_roles);

			if(!empty($list_roles)){

				$list_roles[] = $role_id;

				$cond.= " and `user_id` in(

					select `profile_id` from ".$clsProfile->tbl." 

					where `is_trash`=0 and `is_active`='1' and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%') and `role_id` in (".implode(',', $list_roles).")

				)";

			}

		} else {

			$cond .= " and (`t1`.`user_id`='{$profile_id}' or `t1`.`curator_user_id`={$profile_id})";

		}

	}

	if(!empty($keySearch)){

		$cond.= " and `user_id` IN (

			select {$clsProfile->pkey} from {$clsProfile->tbl} 

			where `phone` like '%{$keySearch}' 

				or `email` like '%{$keySearch}%' 

				or `user_name` like '%{$keySearch}%' 

				or `full_name` like '%{$keySearch}%'

			)";

	}

	if($date_type=='start_date'){

		if(!empty($start_date)){

			$cond.= " and (`start_date`>='".$clsISO->convertDateCreateFormat($start_date)."' 

			or `end_date`>='".$clsISO->convertDateCreateFormat($start_date)."')";

		}

		if(!empty($end_date)){

			$cond.= " and `start_date`<='".$clsISO->convertDateCreateFormat($end_date)."'";

		}

	} else if($date_type=='reg_date'){

		if(!empty($start_date)){

			$cond.= " and `reg_date`>='".$clsISO->convertTextToTime($start_date)."'";

		}

		if(!empty($end_date)){

			$cond.= " and `reg_date`<='".$clsISO->convertTextToTime($end_date)."'";

		}

	}

	if(!empty($group_id)){

		$cond.= " and `user_id` IN (select {$clsProfile->pkey} from {$clsProfile->tbl} 

		where (`department_id`={$group_id} or list_department_id like '%|{$group_id}|%'))";

	}

	if(!empty($user_id)){

		$cond.= " and `t1`.`user_id`='$user_id'";

	}

	if($is_staff){

		$cond.= " and `t1`.`user_id`='{$profile_id}'";

	}

	if($is_curator){

		$cond.= " and `t1`.`curator_user_id`='{$profile_id}'";

	}

	$chk_valid=0;

	if($is_approval!=''){

		if($is_staff){

			$chk_valid=1;

			$cond.= " and `is_approval`='{$is_approval}'";

		} else if($is_curator){

			$chk_valid=1;

		} else if($is_director){

			$chk_valid=1;

		} else if($is_head_of_dep){

			$chk_valid=1;

		} else if($is_director_of_dep){

			$chk_valid=1;

		} else if($is_hrad){

			$chk_valid=1;

		} else {

			$cond.= " and `is_approval`='{$is_approval}'";

		}	

	}

	if($is_curator || $is_director || $is_head_of_dep || $is_hrad || $user_id){

		$per_page=1000;

	}

	#- Begin pagination

	$total_record = $clsTakeLeave->countItem("{$cond}");

	$total_page = ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " limit {$offset},{$per_page}";

	#- End pagination

	$field = "t1.*,`t2`.`first_name`,`t2`.`last_name`,`t2`.`full_name`,`t2`.`department_id`";

	// $dbconn->debug=true;

	$lstTakeLeave = $dbconn->getAll("SELECT {$field} FROM {$clsTakeLeave->tbl} AS `t1` 

		INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`user_id`=`t2`.`profile_id` 

		WHERE {$cond} ORDER BY `t1`.{$date_type} DESC".$limitCond);

	// $clsISO->print_pre($lstTakeLeave); die();

	if(!empty($lstTakeLeave)){

		$arr_property_cached = $arr_profile_cached = array();

		foreach($lstTakeLeave as $k=>$oneTakeLeave){

			$user_id = $oneTakeLeave['user_id'];

			$cat_property_id = $oneTakeLeave['cat_property_id'];

			$curator_user_id = $oneTakeLeave['curator_user_id'];

			$takeleave_id = $oneTakeLeave[$clsTakeLeave->pkey];

			###

			$department_id = $oneTakeLeave['department_id'];

			if(!isset($arr_property_cached[$department_id])){

				$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);

			}

			if(!isset($arr_property_cached[$cat_property_id])){

				$arr_property_cached[$cat_property_id] = $clsProperty->getLabel($cat_property_id, " w-100 py-2 fs-12");

			}

			###

			if(!isset($arr_profile_cached[$curator_user_id])){

				if($curator_user_id==$profile_id){

					$arr_profile_cached[$curator_user_id] = $clsProfile->getFullName($curator_user_id, $oneProfile);

				} else {

					$arr_profile_cached[$curator_user_id] =$clsProfile->getFullName($curator_user_id);

				}

			}

			###

			$valid = 1;

			if($chk_valid && $is_approval!=''){

				$valid = 0;

				if($is_curator) $tp ='curator';

				if($is_head_of_dep) $tp ='head_of_dep';

				if($is_director_of_dep) $tp ='director_of_dep';

				if($is_hrad) $tp ='hrad';

				if($is_director) $tp ='director';

				$info_leave = !empty($oneTakeLeave['info_leave']) 

					? json_decode($oneTakeLeave['info_leave'],true) : array();

				$info_approval = !empty($info_leave['approval']) 

					? $info_leave['approval'] : array();

				if(empty($info_approval[$tp])){

					if($is_approval==2){//chưa duyệt

						$valid = 1;

					}

				}else{

					if(isset($info_approval[$tp]['is_approval']) 

						&& $info_approval[$tp]['is_approval']==1 && $is_approval==1){//đã duyệt

						$valid=1;

					}

					if(isset($info_approval[$tp]['is_approval']) 

						&& $info_approval[$tp]['is_approval']==0 && $is_approval==0){//không duyệt

						$valid=1;

					}

				}

			}

			#

			if($valid){

				$props = $clsISO->make_attrs_builder(array(

					'user_id' => $user_id,

					'takeleave_id'	=> $takeleave_id,

					'curator_user_id' => $curator_user_id

				));

				$html .= '<tr '.$props.'>

					<!--  data-url="'.PCMS_URL.'/take-leave/view/'.$takeleave_id.'" -->

					<td data-label="Mã" class="align-center text-center" '.$props.'>

						<a href="javascript:void(0)" onClick="$Core.takeleave.view(this, event)" class="text-link" '.$props.'>'.$takeleave_id.'</a>

					</td>

					<td data-label="Họ và tên" class="fieldarea white-space-normal-all" '.$props.'>

						<strong>'.$clsProfile->getFullName($user_id, $oneTakeLeave).'</strong>

					</td>

					

					<td class="align-center text-left">'.$arr_property_cached[$department_id].'</td>

					<td class="align-center text-left">'.$arr_property_cached[$cat_property_id].'</td>

					

					<td data-label="Số ngày nghỉ" class="text-left" '.$props.'>

						<strong>Tổng: '.$oneTakeLeave['number_day'].'</strong>

						<div class="text-center d-none align-items-center justify-content-between mt-2">

							'.($oneTakeLeave['number_day_paid_leave_this_year']>0?'<div class="bg-outline-success w-px-40 rounded-circle p-2 mr-2" title="Số ngày nghỉ phép có lương (Năm nay)">'.$oneTakeLeave['number_day_paid_leave_this_year'].'</div>':'').'

							'.($oneTakeLeave['number_day_paid_leave_last_year']>0?'<div class="bg-outline-info  w-px-40 rounded-circle p-2 mr-2" title="Số ngày nghỉ phép có lương (Năm trước)">'.$oneTakeLeave['number_day_paid_leave_last_year'].'</div>':'').'

							'.($oneTakeLeave['number_day_no_paid_leave']>0?'<div class="bg-outline-danger w-px-40 rounded-circle p-2" title="Số ngày nghỉ không lương">'.$oneTakeLeave['number_day_no_paid_leave'].'</div>':'').'

						</div>

					</td>

					

					<td data-label="Ngày" class="fieldarea white-space-normal-all" '.$props.'>

						<strong class="text-danger fs-13">

							'.$clsISO->convertDateCreateFormatDisplay($oneTakeLeave['start_date'],true).'

						</strong> -> <strong class="text-danger fs-13">

							'.$clsISO->convertDateCreateFormatDisplay($oneTakeLeave['end_date']).'

						</strong>

					</td>

					<td data-label="Lý do" class="align-center text-center" '.$props.'>

						'.$clsTakeLeave->getReason($takeleave_id,$oneTakeLeave).'

					</td>

					<td data-label="Duyệt" class="text-center" '.$props.'>

						'.$clsTakeLeave->getInfoApproval($takeleave_id,$oneTakeLeave).'

					</td>

					<td class="align-center text-center text-muted">

						'.$clsISO->makeIcon('bx-time', $clsISO->getTimeAgo($oneTakeLeave['reg_date'])).'

					</td>

					<td class="align-center text-center">

						<div class="dropdown">

							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">

								<i class="bx bx-dots-vertical-rounded"></i>

							</button>

							<div class="dropdown-menu p-2 w-px-110" data-popper-placement="bottom-end">

								<a class="btn btn-sm btn-icon btn-link cursor-pointer" onClick="$Core.takeleave.view(this, event)" '.$props.'>

									'.$core->makeIcon('eye').'</a>

								'.($clsTakeLeave->checkEdit($takeleave_id, $oneTakeLeave)?'

								<a class="btn btn-sm btn-icon btn-link cursor-pointer"  onClick="$Core.takeleave.open(this, event)" '.$props.'>

									'.$clsISO->makeIcon('bx-edit-alt').'</a>':'').'

								'.($clsTakeLeave->checkDel($takeleave_id,$oneTakeLeave)?'

								<a class="btn btn-sm btn-icon btn-link cursor-pointer" onClick="$Core.takeleave.delete(this, event)" '.$props.'>

									'.$clsISO->makeIcon('bx-trash').'</a>':'').'

								'.$clsTakeLeave->getLinkApprovalInList($takeleave_id,$oneTakeLeave).'

							</div>

						</div>

					</td>

				</tr>';

			}

		}

	} else {

		$html.= '<tr>

			<td colspan="10">

				<div class="p-5 text-center">

					<img src="'.URL_IMAGES.'/DataEmpty.svg" class="h-px-150" />

					<p class="text-muted">Chưa có dữ liệu</p>

				</div>

			</td>

		</tr>';

	}

	$html .= '</table></div>

	<input type="hidden" class="PageTakeLeave_currentPage" value="'.$current_page.'" />

	<input type="hidden" class="PageTakeLeave_dataTables_length" value="'.$per_page.'" />

	'.($total_page>1?'<div class="d-flex justify-content-center my-3">

		<div id="pager_TakeLeave"></div>

	</div>':'');

	// Return

	echo @json_encode(array(

		'cond' => $cond,

		'current_page'	=> $current_page,

		'per_page'	=> $per_page,

		'total_record'	=> $total_record,

		'total_page' => $total_page,

		'html'	=> $html

	));die();

}

function default_ajGetReason(){

	global $profile_id,$oneProfile,$smarty,$dbconn,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$clsTakeLeave = new TakeLeave();

	$takeleave_id = Input::get('takeleave_id');

	// Return

	echo $clsTakeLeave->getReasonUpdate($takeleave_id);

	die();

}

function default_ajGetReasonUser(){

	global $profile_id,$oneProfile,$smarty,$dbconn,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$clsProfile = new Profile();

	$tp = Input::get('tp');

	$year = Input::get('year');

	$user_id = Input::get('user_id');

	$takeleave_id = Input::get('takeleave_id');

	// Return

	echo $clsProfile->getReasonUser($user_id,$year,$takeleave_id,$tp); 

	die();

}

function default_getIntroCatTekeLeave(){

	global $profile_id,$oneProfile,$smarty,$dbconn,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$clsProperty = new Property();

	$cat_property_id = (int) Input::post('cat_property_id',0);

	// Return

	echo $clsProperty->getIntro($cat_property_id); die();

}

function default_edit(){

	global $profile_id,$oneProfile,$smarty,$dbconn,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	//ini_set('display_errors',1);

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsTakeLeave = new TakeLeave();

	$clsEmailTemplate = new EmailTemplate();

	$smarty->assign('clsProperty',$clsProperty);

	$smarty->assign('clsTakeLeave',$clsTakeLeave);

	$smarty->assign('clsProfile',$clsProfile);

	$takeleave_id = (int) Input::post('takeleave_id',0);

	$is_view = (int) Input::post('is_view',0);	

	$smarty->assign('takeleave_id',$takeleave_id);

	$smarty->assign('is_view',$is_view);

	$role_id = $oneProfile['role_id'];

	$department_id = $oneProfile['department_id'];

	$smarty->assign('role_id',$role_id);

	$smarty->assign('department_id',$department_id);

	if(Input::exists('submit') && Input::post('submit')=='Edit'){

		$position = Input::post('position');

		$number_day_paid_leave_this_year = floatval(Input::post('number_day_paid_leave_this_year',0));

		$number_day_paid_leave_last_year = floatval(Input::post('number_day_paid_leave_last_year',0));

		$number_day_no_paid_leave = floatval(Input::post('number_day_no_paid_leave',0));

		$number_day = Input::post('number_day',0);

		$start_date = Input::post('start_date');

		$start_time = Input::post('start_time');

		$end_date = Input::post('end_date');

		$end_time = Input::post('end_time');

		$reason = Input::post('reason');

		$curator_user_id = (int) Input::post('curator_user_id',0);

		$cat_property_id = (int) Input::post('cat_property_id',0);

		$start_month = $clsISO->getDateCreateFormat($start_date,"n");

		//echo $start_month;die;

		if(empty($position)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng nhập chức vụ",

				"name"	=> "position"

			));die;

		}

		if($cat_property_id == 0){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng chọn loại nghỉ phép!",

				"name"	=> "cat_property_id"

			));die;

		}

		if(empty($number_day_paid_leave_this_year) 

			&& empty($number_day_paid_leave_last_year) 

			&& empty($number_day_no_paid_leave)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng nhập số ngày nghỉ phép"

			));die;

		}

		if(!empty($number_day_paid_leave_last_year) && $start_month>_MONTH_TAKE_LEAVE_RESET){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Ngày nghỉ phép năm ngoái hết hiệu lực sau tháng "._MONTH_TAKE_LEAVE_RESET

			));die;

		}

		$takeleave_valid = $clsTakeLeave->getInfoTakeleave($profile_id,$start_date,'array');	

		if(!empty($number_day_paid_leave_last_year) && $start_month<=_MONTH_TAKE_LEAVE_RESET

			&& $number_day_paid_leave_last_year > $takeleave_valid['takeleave_valid_last_year']){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Số ngày nghỉ phép năm trước không hợp lệ",

				"name"	=> "number_day_paid_leave_last_year"

			));die;

		}

		if(!empty($number_day_paid_leave_this_year)

			&& $number_day_paid_leave_this_year > $takeleave_valid['takeleave_valid_this_year']){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Số ngày nghỉ phép năm nay không hợp lệ",

				"name"	=> "number_day_paid_leave_this_year"

			));die;

		}

		if(empty($number_day)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng nhập tổng số ngày nghỉ phép",

				"name"	=> "number_day"

			));die;

		}

		if($number_day!=($number_day_paid_leave_this_year+$number_day_paid_leave_last_year+$number_day_no_paid_leave)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Tổng số ngày nghỉ phép không khớp"

			));die;

		}

		if(empty($start_date)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng nhập ngày bắt đầu nghỉ phép",

				"name"	=> "start_date"

			));die;

		}

		if(empty($end_date)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng nhập ngày hết nghỉ phép",

				"name"	=> "end_date"

			));die;

		}

		if(empty($reason)){

			echo json_encode(array(

				"msg"	=> "error",

				"html"	=> "Vui lòng nhập lí do nghỉ phép"

			));die;

		}

		//$clsISO->print_pre($_POST);die;

		$oneTakeLeave = $clsTakeLeave->getOne($takeleave_id);

		$curator_user_id_old = $oneNoPaidLeave['curator_user_id'];

		$info_leave = $oneNoPaidLeave['info_leave'];

		$info_log = $oneNoPaidLeave['info_log'];

		$info_leave = !empty($info_leave) ? json_decode($info_leave,true) : array();

		$info_log = !empty($info_log) ? json_decode($info_log,true) : array();

		$clsTakeLeave->updateOne($takeleave_id,array(

			"position" => $position,

			"number_day_paid_leave_this_year" => $number_day_paid_leave_this_year,

			"number_day_paid_leave_last_year" => $number_day_paid_leave_last_year,

			"number_day_no_paid_leave" => $number_day_no_paid_leave,

			"number_day" 	=> $number_day,

			'start_date'	=>$clsISO->convertDateCreateFormat($start_date),

			'start_time'	=>$start_time,

			'end_date'		=>$clsISO->convertDateCreateFormat($end_date),

			"end_time" 	=> $end_time,

			"reason" 	=> $reason,

			"curator_user_id" 	=> $curator_user_id,

			"cat_property_id" 	=> $cat_property_id,

			'user_id_update' => $profile_id,

			'upd_date'	=> time()

		));

		//send mail

		header('Content-Type: text/html; charset=utf-8');

		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_ADD_TAKELEAVE_ID);

		$subject = $clsProfile->getFullName($profile_id,$oneProfile)." thay đổi thông tin đơn xin nghỉ phép";

		$message = $clsEmailTemplate->getContent(_MAIL_ADD_TAKELEAVE_ID,$oneEmailTemplate);

		$message = str_replace("|user_name|",$clsProfile->getFullName($profile_id,$oneProfile),$message);

		$message = str_replace("|user_group|",$clsProperty->getTitle($department_id),$message);

		$message = str_replace("|position|",$position,$message);

		$message = str_replace("|year|",date("Y"),$message);

		$message = str_replace("|info_takeleave|",$clsTakeLeave->getInfoTakeleave($profile_id),$message);

		$message = str_replace("|number_noPaidLeave|",$clsTakeLeave->getNumberNoPaidLeave($profile_id),$message);

		###

		$info_date_leave = '';

		if(!empty($number_day_paid_leave_this_year))

			$info_date_leave .= '<p><strong>Sử dụng nghỉ phép có lương (Năm nay)</strong>: '.$number_day_paid_leave_this_year.' ngày</p>';

		if(!empty($number_day_paid_leave_last_year))

			$info_date_leave .= '<p><strong>Sử dụng nghỉ phép có lương (Năm trước)</strong>: '.$number_day_paid_leave_last_year.' ngày</p>';

		if(!empty($number_day_no_paid_leave))

			$info_date_leave .= '<p><strong>Sử dụng nghỉ không lương</strong>: '.$number_day_no_paid_leave.' ngày</p>';

		$message = str_replace("|info_date_leave|",$info_date_leave,$message);

		$message = str_replace("|number_day|",$number_day,$message);

		$message = str_replace("|start_date_time|",$clsISO->convertDateCreateFormat($start_date).' '.$start_time,$message);

		$message = str_replace("|end_date_time|",$clsISO->convertDateCreateFormat($end_date).' '.$end_time,$message);

		$message = str_replace("|reason|",html_entity_decode($reason),$message);

		$lstTP  = array("curator","director_of_dep","head_of_dep","hrad");

		foreach($lstTP as $tp){

			if($tp=="curator"){

				if($curator_user_id!=$curator_user_id_old){

					$message_curator = sprintf(

						'%s đã chuyển người phụ trách sang %s',

						$clsProfile->getFullName($profile_id,$oneProfile),

						$clsProfile->getFullName($curator_user_id)

					);

					#Send email to curator

					$fromemail = $clsProfile->getEmail($profile_id,$oneProfile);

					$toemail = $clsProfile->getEmail($curator_user_id_old);

					$subject_curator_old = '['.$clsProfile->getFullName($curator_user_id_old).']'.$subject;

					$send_curator = $clsISO->sendEmail($fromemail,$toemail,$subject_curator_old,$message_curator);

				}

				if(!empty($curator_user_id)){

					$message_curator = str_replace("|admin_name|",$clsProfile->getFullName($curator_user_id),$message);

					$message_curator = str_replace("|message_title|",'Có đơn xin nghỉ phép cần bạn xác nhận',$message_curator);

					$message_curator = str_replace("|message_curator|",'',$message_curator);

					$message_curator = str_replace("|link_approval|",'<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'">

						'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>',$message_curator);

					#Send email to curator

					$fromemail = $clsProfile->getEmail($profile_id,$oneProfile);

					$toemail = $clsProfile->getEmail($curator_user_id);

					$subject_curator = '['.$clsProfile->getFullName($curator_user_id).']'.$subject;

					$send_curator = $clsISO->sendEmail($from,$to,$subject_curator,$message_curator);

					if($send_curator) $info_leave['lstApproval'][$tp] = $curator_user_id;

					$info_log[$clsISO->getUniqid()] = array(

						"tp"=>$tp,

						"gr"=>'edit',

						"reg_date"	=> time(),

						"is_send"	=> $send_curator

					);

				}

			} elseif($tp=="head_of_dep" && $department_id==_DEPARTMENT_BO_ID){

				$head_of_dep_id = $clsProfile->getHeadOfDep($department_id);

				if(!empty($head_of_dep_id)){

					$oneHeadOfDep = $clsProfile->getOne($head_of_dep_id,"full_name,first_name,last_name,email");

					$message_head_of_dep = str_replace("|admin_name|",$clsProfile->getFullName($head_of_dep_id,$oneHeadOfDep),$message);

					$message_head_of_dep = str_replace("|message_title|",'Có đơn xin nghỉ phép cần bạn xét duyệt',$message_head_of_dep);

					$message_head_of_dep = str_replace("|message_curator|",'<p><strong>Người phụ trách</strong>: '.$clsProfile->getFullName($curator_user_id).'</p>',$message_head_of_dep);

					$message_head_of_dep = str_replace("|link_approval|",'<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'">'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>',$message_head_of_dep);

					#Send email to head_of_dep

					$fromemail = $clsProfile->getEmail($profile_id,$oneProfile);

					$toemail = $clsProfile->getEmail($head_of_dep_id,$oneHeadOfDep);

					$subject_head_of_dep = '['.$clsProfile->getFullName($head_of_dep_id,$oneHeadOfDep).']'.$subject;

					$send_head_of_dep = $clsISO->sendEmail($from,$to,$subject_head_of_dep,$message_head_of_dep);

					if($send_head_of_dep) $info_leave['lstApproval'][$tp] = $head_of_dep_id;

					$info_log[$clsISO->getUniqid()] = array(

						"tp"=>$tp,

						"gr"=>'edit',

						"reg_date"	=> time(),

						"is_send"	=> $send_head_of_dep

					);

				}

			} elseif($tp=="hrad"){

				$oneHrad = $clsProfile->getOne(_USER_BO_ID,"full_name,first_name,last_name,email");

				$message_hrad = str_replace("|admin_name|",$clsProfile->getFullName(_USER_BO_ID,$oneHrad),$message);

				$message_hrad = str_replace("|message_title|",'Có đơn xin nghỉ phép cần bạn xét duyệt',$message_hrad);

				$message_hrad = str_replace("|message_curator|",'<p><strong>Người phụ trách</strong>: '.$clsProfile->getFullName($curator_user_id).'</p>',$message_hrad);

				$message_hrad = str_replace("|link_approval|",'<a href="'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'">

					'.$clsTakeLeave->getLinkApproval($takeleave_id,$tp).'</a>',$message_hrad);

				#Send email to hrad

				$fromemail = $clsProfile->getEmail($profile_id,$oneProfile);

				$toemail = $clsProfile->getEmail(_USER_BO_ID,$oneHrad);

				$subject_hrad = '['.$clsProfile->getFullName(_USER_BO_ID,$oneHrad).']'.$subject;

				$send_hrad = $clsISO->sendEmail($from,$to,$subject_hrad,$message_hrad);

				if($send_hrad) $info_leave['lstApproval'][$tp] = _USER_BO_ID;

				$info_log[$clsISO->getUniqid()] = array(

					"tp"=>$tp,

					"gr"=>'edit',

					"reg_date"	=> time(),

					"is_send"	=> $send_hrad

				);

			}

		}

		$arr_upd = array("info_log"=>json_encode($info_log));

		if(!empty($info_leave)){

			$arr_upd['info_leave'] = json_encode($info_leave,JSON_UNESCAPED_UNICODE);

		}

		$clsTakeLeave->updateOne($takeleave_id,$arr_upd);

		// Return

		echo json_encode(array(

			"msg"	=> "ok",

			"html"	=> ''

		));

	}else{

		$uid = $clsISO->getUniqid();

		$oneTakeLeave = $clsTakeLeave->getOne($takeleave_id);	

		$info_leave = json_decode($oneTakeLeave['info_leave'],true);

		$lstApproval = !empty($info_leave['approval'])?$info_leave['approval']:array(); 

		$smarty->assign('oneTakeLeave',$oneTakeLeave);

		$smarty->assign('lstApproval',$lstApproval);

		//$uniqid = $clsISO->getUniqid();	$smarty->assign('uniqid',$uniqid);

		$department_id = $clsProfile->getOneField('department_id',$oneTakeLeave['user_id']);

		$smarty->assign('department_id',$department_id);

		//$lstCurator = $clsVS_Admin->getAll("is_trash=0 and is_active=1 and department_id={$department_id}","user_id,full_name,first_name,last_name,email");

		$lstCurator = $clsProfile->getLstCurator($oneTakeLeave['user_id']);

		$smarty->assign('lstCurator',$lstCurator);

		$field = "{$clsProperty->pkey},title";

		$lstCat = $clsProperty->getAll("property_type='CAT_TAKELEAVE' order by order_no ASC",$field);

		$smarty->assign('lstCat',$lstCat);

		// Return

		$html = $core->build('_ajax.edit.tpl');

		echo json_encode(array(

			"msg"	=> "ok",

			'uid' => $uid,

			"html"	=> $html

		)); die();

	}

}

function default_delete(){

	global $profile_id,$smarty,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$clsTakeLeave = new TakeLeave();

	$takeleave_id = Input::post('takeleave_id', 0);

	###

	$msg  = "_error"; $html = "";

	if(in_array($takeleave_id, array(1, USER_ID_HCNS))){

		if($clsTakeLeave->deleteOne($takeleave_id)){

			$msg = "_success";

		}

	}else{

		$is_approved = $clsTakeLeave->getOneField('is_approved',$takeleave_id);

		if($is_approved){

			$html = 'Không thể xóa, Đơn này đã được duyệt!';

		} else {

			$msg = "_success";

			$clsTakeLeave->deleteOne($takeleave_id);

		}

	}

	// Return

	echo json_encode(array(

		'msg' => $msg,

		'html' => $html

	)); die();

}

function default_approval(){

	global $smarty,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	global $profile_id, $oneProfile;

	$clsProfile = new Profile();

	$clsProperty = new Property(); 

	$clsTakeLeave = new TakeLeave();

	$smarty->assign('clsProfile',$clsProfile);

	$smarty->assign('clsProperty',$clsProperty);

	$smarty->assign('clsTakeLeave',$clsTakeLeave);

	#

	$uid = $clsISO->getUniqid();

	$role_id = $oneProfile['role_id'];

	$department_id = $oneProfile['department_id'];

	$tp = Input::post('tp');//curator,director_of_dep,head_of_dep,hrad,director

	$takeleave_id = (int) Input::post('takeleave_id', 0);

	$oneTakeLeave = $clsTakeLeave->getOne($takeleave_id);	

	$user_id = $oneTakeLeave['user_id'];

	$curator_user_id = $oneTakeLeave['curator_user_id'];

	

	$field = "{$clsProfile->pkey},full_name,first_name,last_name,email,department_id";

	$tmp = $clsProfile->getAll("`profile_id` in ('{$user_id}','{$curator_user_id}')", $field);

	if(!empty($tmp)){

		foreach($tmp as $key => $val){

			if($val[$clsProfile->pkey]==$user_id){

				$department_id = $val['department_id'];

				$oneTakeLeave['user'] = $val;

			} else if($val[$clsProfile->pkey]==$curator_user_id){

				$oneTakeLeave['curator_user'] = $val;

			}

		}

	}

	if($tp=='curator'){

		$tp_title = 'Người phụ trách Xác nhận';

	} elseif($tp=='hrad'){

		$tp_title = 'Hành chính nhân sự Xét duyệt';

	} elseif ($tp=='head_of_dep'){

		$tp_title = 'Trưởng phòng Xét duyệt';

	} elseif ($tp=='director_of_dep'){

		$tp_title = 'Giám đốc Xét duyệt';

	} else if($tp=='director') {

		$tp_title = 'Ban Giám đốc Xét duyệt';

	}

	$leave_approver = $oneTakeLeave['leave_approver'];

	$leave_approver_arrs = $clsISO->to_array_json($leave_approver);

	$oneApproval = isset($leave_approver_arrs[$tp]) ? $leave_approver_arrs[$tp] : array(); 

	###

	$smarty->assign('tp',$tp);

	$smarty->assign('uid',$uid);

	$smarty->assign('takeleave_id',$takeleave_id);

	$smarty->assign('oneTakeLeave',$oneTakeLeave);

	$smarty->assign('department_id',$department_id);

	$smarty->assign('tp_title',$tp_title);

	$smarty->assign('leave_approver_arrs',$leave_approver_arrs);

	$smarty->assign('oneApproval',$oneApproval);

	// Return

	$html = $core->build('_ajax.approval.tpl');

	echo json_encode(array(	

		'uid' => $uid,

		'html' => $html

	));

}

function default_approval_save(){

	global $smarty,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	global $profile_id, $oneProfile;

	// ini_set('display_errors',1);

	// error_reporting(E_ALL);

	$clsProfile = new Profile();

	$clsProperty = new Property(); 

	$clsTakeLeave = new TakeLeave();		

	$clsEmailTemplate = new EmailTemplate();

	###

	$msg = "_error";

	$tp = Input::post('tp');

	$takeleave_id = Input::post('takeleave_id', 0);

	$approval_status = (int) Input::post('approval_status', 0);

	$oneTakeLeave= $clsTakeLeave->getOne($takeleave_id);

	$user_id = $oneTakeLeave['user_id']; // Người làm đơn

	$curator_user_id = $oneTakeLeave['curator_user_id']; // Người quản lý

	$oProfile = $clsProfile->getOne($user_id, "first_name,last_name,full_name,email");

	$leave_approver = $oneTakeLeave['leave_approver'];

	$leave_approver_arrs = $clsISO->to_array_json($leave_approver);

	$oneApproval = isset($leave_approver_arrs[$tp]) ? $leave_approver_arrs[$tp] : array(); 

	###

	if(!empty($oneApproval)){

		$oneApproval['profile_id'] = $profile_id;

		$oneApproval['approval_status'] = $approval_status;

		$oneApproval['content'] = Input::post('content');

		$oneApproval['upd_date'] = time();

	} else {

		$oneApproval = array(

			"profile_id" => $profile_id,

			"approval_status"	=> $approval_status,

			"content"	=> Input::post('content'),

			"reg_date" => time(),

			'upd_date'	=> time()

		);

	}

	$leave_approver_arrs[$tp] = $oneApproval;

	###

	$is_approved = 1;

	foreach($leave_approver_arrs as $key => $val){

		if(isset($val['approval_status']) && (int) $val['approval_status'] == 0){

			$is_approved = 0;

			break;

		}

	}

	// $clsISO->print_pre($is_approved); die();

	if($clsTakeLeave->updateOne($takeleave_id, array(

		"is_approved" => $is_approved,

		"leave_approver" => json_encode($leave_approver_arrs,JSON_UNESCAPED_UNICODE)

	))){

		$msg = "_success";

		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_TAKELEAVE_ID);

		$fromname = $clsEmailTemplate->getFromName(_MAIL_TAKELEAVE_ID, $oneEmailTemplate);

		$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_TAKELEAVE_ID, $oneEmailTemplate);

		if($tp == 'director'){

			if($approval_status == 1){

				$subject = "[".BRAND_NAME."] Ban Giám Đốc đã xét duyệt đơn xin nghỉ phép của bạn";

				$message = 'Đơn xin nghỉ phép của <strong>bạn</strong> đã được duyệt.<br />

				Thời gian nghỉ phép: '.$oneTakeLeave['number_day'].' ngày từ '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['start_date']), $oneTakeLeave['start_time']).' đến '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['end_date']), $oneTakeLeave['end_time']).'<br />Vui lòng duy trì tương tác với <strong>'.$clsProfile->getFullName($curator_user_id).'</strong> để đảm bảo công việc trong thời gian nghỉ phép.';

			}else if($approval_status == 2){

				$subject = "[".BRAND_NAME."] Ban Giám Đốc không duyệt đơn xin nghỉ phép của bạn";

				$message = 'Đơn xin nghỉ phép của <strong>bạn</strong> '.$oneTakeLeave['number_day'].' ngày từ '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['start_date']), $oneTakeLeave['start_time']).' đến '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['end_date']), $oneTakeLeave['end_time']).' đã không được duyệt bởi '.$clsProfile->getFullName($profile_id, $oneProfile).'<br />

				<strong>Lí do: </strong>'.$content.' 

				<br />Vui lòng điều chỉnh hoặc hủy đơn xin nghỉ phép.';

			}

			#Send email to staff

			$toemail = 'vanthiembui.it@gmail.com';// $oProfile['email']; 

			$toname = $clsProfile->getFullName($user_id, $oProfile);

			$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

		} else {

			if($tp == 'hrad'){

				$tp_name = 'Hành chính nhân sự';

			} else if($tp == 'head_of_dep'){

				$tp_name = 'Trưởng phòng';

			} else if($tp == 'director_of_dep'){

				$tp_name = 'Giám đốc bộ phận';

			} else if($tp == 'ci'){

				$tp_name = 'Người phụ trách';

			}

			if($approval_status == 1){

				$subject = sprintf("%s đã xét duyệt đơn xin nghỉ phép của bạn", $tp_name);

				$message = 'Đơn xin nghỉ phép của <strong>bạn</strong> đã được duyệt.<br />

				Thời gian nghỉ phép: '.$oneTakeLeave['number_day'].' ngày từ '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['start_date']), $oneTakeLeave['start_time']).' đến '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['end_date']), $oneTakeLeave['end_time']).'<br />Vui lòng duy trì tương tác với <strong>'.$clsProfile->getFullName($curator_user_id).'</strong> để đảm bảo công việc trong thời gian nghỉ phép.';

			}else if($approval_status == 2){

				$subject = sprintf("%s không duyệt đơn xin nghỉ phép của bạn", $tp_name);

				$message = 'Đơn xin nghỉ phép của <strong>bạn</strong> '.$oneTakeLeave['number_day'].' ngày từ '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['start_date']), $oneTakeLeave['start_time']).' đến '.sprintf('%s %s', $clsISO->convertDateCreateFormat($oneTakeLeave['end_date']), $oneTakeLeave['end_time']).' đã không được duyệt bởi '.$tp_name.': <strong>'.$clsProfile->getFullName($profile_id, $oneProfile).'</strong><br />

				<strong>Lí do: </strong>'.$content.' 

				<br />Vui lòng điều chỉnh hoặc hủy đơn xin nghỉ phép.';

			}

			#Send email to staff

			$toemail = 'vanthiembui.it@gmail.com';// $oProfile['email']; 

			$toname = $clsProfile->getFullName($user_id, $oProfile);

			$clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

		}

	}

	// Return

	echo json_encode(array(

		'msg' => $msg

	)); die();

}

function default_check_monday(){

	global $adminid,$smarty,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$start_date = Input::post('start_date');

	$end_date = Input::post('end_date');

	if(!empty($start_date) && $clsISO->getDateCreateFormat($start_date,"D")=='Mon'){

		echo json_encode(array(

			"msg" => "ok",

			"hasMonday"	=> 1

		));die;

	}

	if(!empty($end_date) && $clsISO->getDateCreateFormat($end_date,"D")=='Mon'){

		echo json_encode(array(

			"msg" => "ok",

			"hasMonday"	=> 1

		));die;

	}

	$hasMonday = $hasSaturday = 0;

	if(!empty($start_date) && !empty($end_date)){

		$start_date = $clsISO->convertDateCreateFormat($start_date);

		$end_date = $clsISO->convertDateCreateFormat($end_date);

		$end_date = date('Y-m-d',strtotime($end_date . ' +1 day'));

		$range_date = array();

		$period = new DatePeriod(

			 new DateTime($start_date),

			 new DateInterval('P1D'),

			 new DateTime($end_date)

		);

		foreach ($period as $key => $value) {    

			$range_date[] = $value->format('Y-m-d');      

		}

		foreach($range_date as $oneDate){

			if($clsISO->getDateCreateFormat($oneDate,"D")=='Mon'){

				$hasMonday = 1;

				break;

			}

		}

		// Return

		echo json_encode(array(

			"msg"	=> "ok",

			"hasMonday"	=> $hasMonday

		));die;

	}

	// Return

	echo json_encode(array(

		"msg"	=> "ok",

		"hasMonday"	=> $hasMonday

	)); die;

}

function default_check_monsat_day(){

	global $adminid,$smarty,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$start_date = Input::post('start_date');

	$end_date = Input::post('end_date');

	$start_date = $clsISO->convertDateCreateFormat($start_date);

	if(!empty($end_date)){

		$end_date = $clsISO->convertDateCreateFormat($end_date);

		$end_date = date('Y-m-d',strtotime($end_date . ' +1 day'));

	}else{

		$end_date = date('Y-m-d',strtotime($start_date . ' +1 day'));

	}

	$range_date = array();

	$hasMonday = $hasSaturday =  0;

	$period = new DatePeriod(

		 new DateTime($start_date),

		 new DateInterval('P1D'),

		 new DateTime($end_date)

	);

	foreach ($period as $key => $value) {

		$range_date[] = $value->format('Y-m-d');      

	}

	foreach($range_date as $oneDate){

		$representation_day = $clsISO->getDateCreateFormat($oneDate,"D");

		if($representation_day=='Mon'){

			$hasMonday = 1;

		}

		if($representation_day=='Sat'){

			$hasSaturday = 1;

		}

	}

	// Return

	echo json_encode(array(

		"msg"	=> "ok",

		"hasMonday"	=> $hasMonday,

		"hasSaturday"	=> $hasSaturday,

	)); die;

}

function default_load_profile_in_department(){

	global $profile_id,$smarty,$PCMS_URL,$clsISO,$core,$clsConfiguration;

	$clsProfile = new Profile();

	$department_id = (int) Input::post('department_id', 0);

	###

	$html_option = sprintf('<option value="0">%s</option>', 'Nhân viên');

	if($department_id > 0){

		$field = "{$clsProfile->pkey},code,first_name,last_name,full_name";

		$tmp = $clsProfile->getAll("`is_trash`=0 and `is_active`=1 and (`department_id`='{$department_id}' 

			or `list_department_id` like '%{$department_id}%')");

		if(!empty($tmp)){

			foreach($tmp as $key => $val){

				$html_option.= sprintf('<option value="%s">%s</option>', 

					$val[$clsProfile->pkey], 

					$clsProfile->getIndentityV2($val[$clsProfile->pkey], $val

				));

			}

		}

	}

	// Return

	echo $html_option; die();

}

?>