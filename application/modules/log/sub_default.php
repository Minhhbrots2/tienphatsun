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

function truncate($string) {

	global $core, $dbconn, $clsISO;

	if(strlen($string) > 50){

		$l_string = substr($string, 0, 25);

		$r_string = substr($string, -25);

		return sprintf('%s...%s', $l_string, $r_string);

	} else {

		return $string;

	}

}

function default_login_history(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsProfile = new Profile();

	$clsProfileLog = new ProfileLog();

	###

	$field = "{$clsProfile->pkey},full_name,first_name,last_name";

	$lstUser = $clsProfile->getAll("is_trash=0 and `property_type`<>'_sale' and is_active='1'", $field);

	$smarty->assign('lstUser',$lstUser);

	/*=============Title & Description Page==================*/

	$title_page = 'Lịch sử đăng nhập - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

function default_load_login_logs(){

	// ini_set('display_errors',1);

	// error_reporting(E_ALL);

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsProfile = new Profile();

	$clsProfileLog = new ProfileLog();

	###

	$keySearch = Input::post('keySearch', "");

	$user_id = Input::post('user_id', 0);

	$department_id = Input::post('department_id', 0);

	$start_date = Input::post('start_date', "");

	$end_date = Input::post('end_date', "");

	$current_page = Input::post('page', 1);

	$per_page = Input::post('page', 100);

	$cond = "1=1";

	if(!empty($keySearch)){

		$cond.= " and (`ip_address` like '%{$keySearch}%' 

			or `browser` like '%{$keySearch}%'

		)";

	}

	if($user_id > 0) $cond.= " and `profile_id`='{$user_id}'";

	if(!empty($start_date)){

		$start_date_int = strtotime(str_replace('/','-',$start_date));

		$cond.= " and `reg_date` > '{$start_date_int}'";

	}

	if(!empty($end_date)){

		$end_date_int = strtotime(str_replace('/','-',$end_date));

		$cond.= " and `reg_date` < '{$end_date_int}'";

	}

	#- Begin pagination

	$total_record = $clsProfileLog->countItem($cond);

	$total_page = ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " limit {$offset},{$per_page}";

	#- End pagination

	$list_logs = $clsProfileLog->getAll("{$cond} order by reg_date DESC".$limitCond);

	if(!empty($list_logs)){

		$arr_profile_cached = array();

		foreach($list_logs as $key => $_oLog){

			$profile_id = $_oLog['profile_id'];

			if(isset($arr_profile_cached[$profile_id])){

				$porfile = $arr_profile_cached[$profile_id];

			} else {

				$arr_profile_cached[$profile_id] = $clsProfile->getIndentityV3($profile_id, true);

				$porfile = $arr_profile_cached[$profile_id];

			}

			$html.= '<tr>

				<td data-label="Nhân viên">'.$porfile.'</td>

				<td data-label="H.Động" clas="text-left">

					'.($_oLog['act']=='login' ? $clsISO->makeIcon('bx-log-in-circle text-success', 'Login') : $clsISO->makeIcon('bx-log-out-circle text-danger', 'Logout')).'

				</td>

				<td data-label="Thời gian">'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'</td>

				<td data-label="Địa chỉ IP">'.$_oLog['ip_address'].'</td>

				<td data-label="Trình duyệt">'.truncate($_oLog['browser']).'</td>

			</tr>';

		}

	}

	// Return

	echo json_encode(array(

		'html' => $html,

		'total_record' => $total_record,

	)); die();	

}

function default_log_sale(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsStock = new Stock();

	$clsProfile = new Profile();

	$clsMember = new Member();

	$clsProfileLog = new ProfileLog();

	###

	$is_full_permiss = $clsISO->checkPermissionGroup('DIRECTOR') ? 1 : 0;

	$smarty->assign('is_full_permiss', $is_full_permiss);

	

	$stock_id = (int)Input::get("stock_id",0);

	$user_id = (int)Input::get("user_id",0);

	$start_date = Input::get("start_date","");

	$end_date = Input::get("end_date","");

	$from_site = Input::get("from_site","");

	$smarty->assign('stock_id',$stock_id);

	$smarty->assign('user_id',$user_id);

	$smarty->assign('start_date',$start_date);	

	$smarty->assign('end_date',$end_date);

	$smarty->assign('from_site',$from_site);

	$smarty->assign('clsStock', $clsStock);

	$smarty->assign('clsMember', $clsMember);

	###

	if($is_full_permiss == 1){

		$lstUser = $clsProfile->getProfileCached();

	} else if($clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')){

		$department_id = (int) $oneProfile['department_id'];

		$lstUser = $clsProfile->getProfileDep($department_id, 1, "all");

	} else if(1==2) {

		$field = "{$clsMember->pkey},`code`,`full_name`";

		$cond = ($user_id==0) ? " and `profile_type`<>'_sale'" : "";

		if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){

			$department_id = $oneProfile['department_id'];

			$cond.= " and `department_id`='{$department_id}'";

		}

		$lstUser = $clsMember->getAll("`is_trash`=0 and `is_active`='1' {$cond} and `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $field);

	}

	$smarty->assign('lstUser',$lstUser);

	###

	$list_preloaders = array();

	for($i=0; $i<30; $i++){

		$list_preloaders[] = $i;

	}

	$smarty->assign('list_preloaders',$list_preloaders);

	/*=============Title & Description Page==================*/

	$title_page = 'Lịch sử tra cứu - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

function default_load_sale_logs(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsLog = new Log();

	$clsMember = new Member();

	$clsProfile = new Profile();

	###

	$keySearch = Input::post('keySearch', "");

	$user_id = (int) Input::post('user_id', 0);

	$stock_id = (int) Input::post('stock_id', 0);

	$start_date = Input::post('start_date', "");

	$end_date = Input::post('end_date', "");

	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;

	$end_time = !empty($end_date) ? $clsISO->toTime($end_date." 23:59:59") : 0;

	$from_site = Input::post('from_site', "_all");

	$current_page = (int) Input::post('page', 1);

	$per_page = (int) Input::post('per_page', 100);

	###

	$cond = "1=1";

	if(!empty($keySearch)){

		$cond.= " and (`title` like '%{$keySearch}%' 

			or `user_id` IN (SELECT `profile_id` FROM {$clsMember->tbl} 

				WHERE `email` LIKE '%{$keySearch}%' 

				OR `phone` LIKE '%{$keySearch}%')

			)";

	}

	if($clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')){

		if($user_id > 0){

			$cond.= " AND `from_site`='_user' AND `user_id`='{$user_id}'";

		} else {

			$department_id = (int) $oneProfile['department_id'];

			$list_staffs = $clsProfile->getProfileDep($department_id, 1, "all");

			$arr_staff_ids = !empty($list_staffs) ? @array_keys($list_staffs) : [];

			$cond.= " AND `from_site`='_user' AND `user_id` in ('".implode('\',\'',$arr_staff_ids)."')";

		}

	} else {

		if($user_id > 0) {

			$cond.= " AND `user_id`='{$user_id}'";

		}

	}

	if($stock_id > 0) {

		$cond.= " AND (`type`='search' OR `type`='view_stock') AND `target_id`='{$stock_id}'";

	}

	#- Thời gian

	if($start_time > 0 && $end_time == 0){

		$cond.= " AND `reg_date` > '{$start_time}'";

	} else if($start_time == 0 && $end_time > 0){

		$cond.= " AND `reg_date` < '{$end_time}'";

	} else if($start_time > 0 && $end_time > 0){

		$cond.= " AND (`reg_date` between {$start_time} AND {$end_time})";

	}

	if($from_site != '_all' && $from_site != ""){

		$cond.= " AND `from_site`='{$from_site}'";

	}

	#- Begin pagination

	$total_record = $clsLog->countItem($cond);

	$total_page = @ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " limit {$offset},{$per_page}";

	#- End pagination

	// $clsISO->print_pre($cond); die();

	// $dbconn->debug = true;

	$field = "{$clsLog->pkey},`type`,`user_id`,`title`,`reg_date`,`from_site`,`stock_id`";

	$list_logs = $clsLog->getAll("{$cond} ORDER BY `reg_date` DESC".$limitCond, $field);

	

	if(!empty($list_logs)){

		$arr_member_cached = array();

		$tmp = $clsMember->getAll("1=1", "{$clsMember->pkey},`profile_type`,`first_name`,`last_name`,`full_name`,`phone`,`avatar`");

		if(!empty($tmp)){

			foreach($tmp as $key => $val){

				$arr_member_cached[$val[$clsMember->pkey]] = $val;

			}

			unset($tmp);

		}

		$arr_profile_cached = $clsProfile->getProfileCached();

		foreach($list_logs as $key => $_oLog){

			$type = $_oLog['type'];

			$user_id = $_oLog['user_id'];

			$stock_id = $_oLog['stock_id'];

			$title = !empty($_oLog['title']) ? @strtok($_oLog['title'],'?') : "";

			$profile = "";

			if($user_id > 0){

				if($_oLog['from_site'] == "_user") {

					if(isset($arr_profile_cached[$user_id])){

						$_oProfile = $arr_profile_cached[$user_id];

						$tmp = $clsProfile->getIndentityV7($user_id, $_oProfile, true);

						$profile = $tmp['html'];

						$arr_profile_cached[$user_id]['total_searchs'] = $tmp['total_searchs'];

					} else {

						$profile = '<img class="avatar avatar-xs mr-2 rounded-pill" src="'.URL_IMAGES.'/no-avatar.jpg" /> My Ocean City';

					}

				}else {

					if(isset($arr_member_cached[$user_id])){

						$_oProfile = $arr_member_cached[$user_id];

						$tmp = $clsMember->getIndentityV7($user_id, $_oProfile, true);

						$profile = $tmp['html'];

						$arr_member_cached[$user_id]['total_searchs'] = $tmp['total_searchs'];

					} else {

						$profile = '<img class="avatar avatar-xs mr-2 rounded-pill" src="'.URL_IMAGES.'/no-avatar.jpg" /> My Ocean City';

					}

				}

			} else {

				$profile = '<img class="avatar avatar-xs mr-2 rounded-pill" src="'.URL_IMAGES.'/no-avatar.jpg" /> My Ocean City';

			}

			if($type=='view'){

				$action = "<i class='bx bx-table' ></i> Xem bảng hàng";

				$contentHTML = $title;

				$contentHTML= '<a href="'.$clsLog->getLinkByText($title).'" target="_blank" >'.$title.'</a>';

			} else if($type='view_stock'){

				$action = "<i class='bx bxs-show'></i> Xem căn hộ";

				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')" stock_id="'.$stock_id.'">'.$title.'</a>';

			} else if($type=='search'){

				$action = "<i class='bx bx-search'></i> Tìm kiếm căn hộ";

				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')" stock_id="'.$stock_id.'">'.$title.'</a>';

			}

			$list_logs[$key]['profile'] = $profile;

			$zalo_btn = !empty($_oProfile['phone']) ? '<a href="https://zalo.me/'.$_oProfile['phone'].'" target="_blank">

				<img src="'.URL_IMAGES.'/zalo_chat.png" width="20px" /></a>' : "";

			$html.= '<tr>

				<td class="text-left" data-label="Nhân viên">'.$profile.'</td>

				<td class="text-left" data-label="H.Động">'.$action.'</td>

				

				<td data-label="Nội dung">'.$contentHTML.'</td>

				<td class="border-end" data-label="Thời gian">

					'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'

				</td>

				<td data-label="Điện thoại">'.$_oProfile['phone'].'</td>

				<td class="text-center">'.$zalo_btn.'</td>

			</tr>';

		}

	} else {

		$html.= '<tr class="">

			<td class="text-muted text-center" colspan="5">

				<span>Chưa có hoạt động tra cứu</span>

			</td>

		</tr>';

	}

	// Return

	echo json_encode(array(

		'html' => $html,

		'cond' => $cond,

		'total_page' => $total_page,

		'total_record' => $total_record,

		'current_page' => $current_page,

		'per_page' => $per_page

	),JSON_UNESCAPED_UNICODE); die();	

}

function default_load_profile_popover(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;

	//ini_set('display_errors',1);

	$clsProfile = new Profile();

	$clsMember = new Member();

	$clsProperty = new Property();

	$clsBilling = new Billing();

	##

	$user_id = (int) Input::get('user_id', 0);

	if($user_id == $profile_id){

		$oProfile = $oneProfile;

	} else {

		$oProfile = $clsMember->getOne($user_id);

	}

	$isMask = $clsISO->checkSupper() ? false: true;

	$role_id = $oProfile['role_id'];

	$department_id = $oProfile['department_id'];

	$profile_type = $oProfile['profile_type'];

	$IsFH = ($profile_type=='_user') ? 1 : 0;

	$total_billing = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");

	$total_price = $clsBilling->sumItem("totalgrand", "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");

	$html = '<div class="links-bar-create-edit clearfix">

		<div class="profile-photo-create-edit pull-left mr-2">

			<img src="'.$clsMember->getAvatar($user_id, $oProfile).'">

		</div>

		<div class="title fs-16 bold">

			'.$clsMember->getIndentityV2($user_id, $oProfile, false).'

			'.($IsFH?'<span class="awe__post-star">

				'.$clsMember->genHTMLStar($oProfile['rating_star']).'

			</span>':'').'

		</div>

		<div clas="d-flex align-items-center">

			<span clas="awe__post-level mr-2 text-muted">

				'.$clsProperty->getTitle($role_id).'

			</span>

		</div>

		'.($IsFH?'<div class="subtitle mt-1 textred">

			Phòng ban: '.$clsProperty->getTitle($department_id).'

		</div>':'').'

	</div>

	<div class="bg-lighter rounded-2 p-3 mt-2">

		'.($profile_type=='_sale' ? '

		<div class="form-group mb-1">

			'.$core->makeIcon('clock-o', $clsISO->convertTimeToText($oProfile['reg_date'], true)).'

		</div>': '').'

		<div class="form-group mb-1">

			'.$core->makeIcon('phone', $clsMember->getPhone($user_id, $oProfile, $isMask)).'

		</div>

		<div class="form-group mb-1">

			'.$core->makeIcon('envelope', $clsMember->getEmail($user_id, $oProfile, $isMask)).'

		</div>

		<div class="form-group mb-1">

			'.$core->makeIcon('cc', $clsMember->getCCID($user_id, $oProfile, $isMask)).'

		</div>

		<div class="form-group">

			'.$core->makeIcon('calendar-o', $clsMember->getBirthday($user_id, $oProfile)).'

		</div>

	</div>

	'.($IsFH ? '<div class="metadata-page-render mt-2">

		<section class="block metadata-section-render">

			<div class="metadata-row-render">

				<div class="metadata-row-title">

					<span class="title">Ngày bắt đầu</span>

				</div>

				<div class="metadata-row-viewer w-200 d-inline-block">

					'.$oProfile['start_date'].'

				</div>

			</div>

			<div class="metadata-row-render">

				<div class="metadata-row-title">

					<span class="title">Tình trạng</span>

				</div>

				<div class="metadata-row-viewer w-200 d-inline-block">

					'.$clsProperty->getLabel($oProfile['status_id']).'

				</div>

			</div>

			<div class="metadata-row-render">

				<div class="metadata-row-title">

					<span class="title">Tổng giao dịch</span>

				</div>

				<div class="metadata-row-viewer d-inline-block">'.$total_billing.'</div>

			</div>

			<div class="metadata-row-render">

				<div class="metadata-row-title">

					<span class="title">Tổng doanh số</span>

				</div>

				<div class="metadata-row-viewer d-inline-block">

					<strong class="red" title="0">

						'.$clsISO->formatNumberToEasyRead($total_price).'</strong> ₫

				</div>

			</div>

		</section>

	</div>' : '').'';

	// Return

	echo $html; die();

}

function default_log_search(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsStock = new Stock();

	$clsProfile = new Profile();

	$clsProfileLog = new ProfileLog();

	$smarty->assign('clsStock', $clsStock);

	###

	$stock_id = isset($_GET['stock_id']) ? (int) $_GET['stock_id'] : 0;

	$smarty->assign('stock_id',$stock_id);

	###

	$field = "{$clsProfile->pkey},`code`,`full_name`";

	$lstUser = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $field);

	$smarty->assign('lstUser',$lstUser);

	###

	$list_preloaders = array();

	for($i=0; $i<30; $i++){

		$list_preloaders[] = $i;

	}

	$smarty->assign('list_preloaders',$list_preloaders);

	

	/*=============Title & Description Page==================*/

	$title_page = 'Lịch sử tìm kiếm - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

function default_load_search_logs(){

	// ini_set('display_errors', '1');

	// ini_set('display_startup_errors', '1');

	// error_reporting(E_ALL);

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsSearchLog = new SearchLog();

	$clsProfile = new Profile();

	$clsMember = new Member();

	###

	$keySearch = Input::post('keySearch', ""); 

	$user_id = (int) Input::post('user_id', 0);

	$start_date = Input::post('start_date', "");

	$end_date = Input::post('end_date', "");

	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;

	$end_time = !empty($end_date) ? $clsISO->toTime($end_date) : 0;

	$from_site = Input::post('from_site', "_all");

	$current_page = (int) Input::post('page', 1);

	$per_page = (int) Input::post('per_page', 50);

	###

	$cond = "1=1";

	if(!empty($keySearch)){

		$cond.= " and (`keyword` like '%{$keySearch}%')";

	}

	if($user_id > 0) 

		$cond.= " and `user_id`='{$user_id}'";

	if($start_time > 0 && $end_time == 0){

		$cond.= " and `reg_date` > '{$start_time}'";

	} else if($start_time == 0 && $end_time > 0){

		$cond.= " and `reg_date` < '{$end_time}'";

	} else if($start_time > 0 && $end_time > 0){

		$cond.= " and (`reg_date` between {$start_time} AND {$end_time})";

	}

	if($from_site != '_all'){

		$cond.= " and `user_id` IN (

			SELECT `profile_id` FROM {$clsProfile->tbl} 

			WHERE `profile_type`='{$from_site}'

		)";

	}

	#- Begin pagination

	$total_record = $clsSearchLog->countItem($cond);

	$total_page = @ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " limit {$offset},{$per_page}";

	#- End pagination

//	 $clsSearchLog->setDeBug(1);

	$list_logs = $clsSearchLog->getAll("{$cond} AND (`user_id` IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}`) OR `user_id` IN (SELECT `{$clsMember->pkey}` FROM `{$clsMember->tbl}`)) order by `reg_date` DESC".$limitCond);

//	$clsISO->print_pre($list_logs);die;

	if(!empty($list_logs)){

		$arr_profile_cached = $arr_profile_cached_MOC = array();

		foreach($list_logs as $key => $_oLog){

			$user_id = $_oLog['user_id'];

			if($user_id > 0){

				if($_oLog['_from'] == 'FH') {

					if(isset($arr_profile_cached[$user_id])){

						$porfile = $arr_profile_cached[$user_id];

					} else {

						$arr_profile_cached[$user_id] = $clsProfile->getIndentityV3($user_id, true);

						$porfile = !empty($arr_profile_cached[$user_id]) ? $arr_profile_cached[$user_id] : "";

					}

				}else{

					if(isset($arr_profile_cached_MOC[$user_id])){

						$porfile = $arr_profile_cached_MOC[$user_id];

					} else {

						$arr_profile_cached_MOC[$user_id] = $clsMember->getIndentityV3($user_id, true);

						$porfile = !empty($arr_profile_cached_MOC[$user_id]) ? $arr_profile_cached_MOC[$user_id] : "";

					}

				}

				

			} else {

				$porfile = '<img class="avatar avatar-xs mr-2 rounded-pill" src="'.URL_IMAGES.'/no-avatar.jpg" /> Khách'; 

			} 

			if(!empty($porfile)) {

				$html.= '<tr>

					<td data-label="Nhân viên">'.$porfile.'</td>

					<td data-label="Nội dung">'.$_oLog['keyword'].'</td>

					<td data-label="Thời gian" >'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'</td>

					<td data-label="User IP">'.$_oLog['user_ip'].'</td>

				</tr>'; 					

			} 

		}

	}

	// Return

	echo json_encode(array(

		'html' => $html,

		'cond' => $cond,

		'total_page' => $total_page,

		'total_record' => $total_record,

		'current_page' => $current_page,

		'per_page' => $per_page

	)); die();	

}

function default_open_report(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$uid = $clsISO->getUniqid();

	$smarty->assign('uid', $uid);

	// Return

	$html = $core->build('_ajax.report.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	));

}

function default_load_table_report(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsLog = new Log();

	$clsStock = new Stock();

	##

	$html = $sql_cond = "";

	$search_type = Input::post('search_type','all');

	if($search_type=='sold'){

		$sql_cond= " and `t2`.`status_id`='"._STOCK_STATUS_SOLD_ID."'"; 

	} else if($search_type=='not_sold'){

		$sql_cond= " and `t2`.`status_id`>0 and `t2`.`status_id`<>'"._STOCK_STATUS_SOLD_ID."'"; 

	}

	$list_stocks = $dbconn->getAll("SELECT COUNT(`t1`.`target_id`) as `total_search`,`t2`.`stock_id`,`t2`.`ms_code` 

		FROM {$clsLog->tbl} AS `t1` 

		INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`target_id`=`t2`.`stock_id` AND (`t1`.`type`='search' or `t1`.`type`='view_stock') 

		WHERE `t2`.`is_trash`=0 and `t2`.`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'{$sql_cond} 

		GROUP BY `t1`.`target_id` HAVING `total_search`>0 order by `total_search` DESC limit 0,20");

	

	if(!empty($list_stocks)){ $ii = 0;

		foreach($list_stocks as $key => $val){

			$html.= '<tr>

				<td class="text-center">'.($ii+1).'</td>

				<td class="text-left">

					<a href="javascript:void(0)" title="Xem chi tiết" class="text-link" onClick="$Core.helper.open_stock('.$val['stock_id'].');">'.$val['ms_code'].'<a/>

				</td>

				<td>'.$val['total_search'].'</td>

			</tr>';

			++$ii;

		}

	}

	// Return

	echo json_encode(array(

		'html' => $html

	)); die();

}



function default_online_moc(){

	ini_set('memory_limit', '7048M');

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsStock = new Stock();

	$clsProfile = new Profile();

	$clsMember = new Member();

	$clsProfileLog = new ProfileLog();

	

	$arr_member_cached = $clsMember->getMemberCached();

//	echo "<pre>";

//	var_dump($arr_member_cached);die;

	$smarty->assign('arr_member_cached',json_encode($arr_member_cached));

	###

	$list_preloaders = array();

	for($i=0; $i<30; $i++){

		$list_preloaders[] = $i;

	}

	$smarty->assign('list_preloaders',$list_preloaders);

	/*=============Title & Description Page==================*/

	$title_page = 'Danh sách online MOC - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

function default_view_history_connect_moc(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;

	$clsLog = new Log();

	$clsProject = new Project();

	$clsProperty = new Property();

	$clsMember = new Member();

	$member_id = (int)Input::post("member_id", 0);

	$uid = $clsISO->getUniqid();

	$smarty->assign('uid', $uid);

	$smarty->assign('member_id', $member_id);

	$oneMember = $clsMember->getOne($member_id);

	$lstLog = $clsLog->getAll("`user_id`='{$member_id}' AND `from_site`='_sale' AND `type`='view' ORDER BY `reg_date` DESC LIMIT 0,20");

	$arr_project_cached = $clsProject->getListProject();

	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");

	$arr_building_cached = $clsProperty->getArraySearchByKey("_BUILDING");

	$arr_member_cached = $clsMember->getMemberCached();

	

	foreach ($lstLog as $key => $val) {

		$link = $clsLog->getLinkByText($val["title"]);

		$arr_project = $clsLog->getProjectByURL($link);

		$project_id = !empty($arr_project["project_id"]) ? $arr_project["project_id"] : 0;

		$building_id = !empty($arr_project["building_id"]) ? $arr_project["building_id"] : 0;

		$project_name = "";

		

		if(!empty($project_id)) {

			if(!empty($building_id)) {

				$block_id = $arr_building_cached[$building_id]["for_id"];

				$oneBlock = !empty($arr_block_cached[$block_id]) ? $arr_block_cached[$block_id] : []; 

				$is_project = !empty($oneBlock["is_project"]) ? $oneBlock["is_project"] : 0;

				$project_name .= "Tòa " . $arr_building_cached[$building_id]["title"];

				$project_name .= ", ".$oneBlock["title"];

				if(empty($oneBlock["is_project"])) {

					$project_name .= ", ".$arr_project_cached[$project_id]["title"];

				}			

			}else{

				$project_name .= $arr_project_cached[$project_id]["title"];

			}

			$lstLog[$key]["project_name"] = $project_name;

			$lstLog[$key]["link"] = $link;

			$lstLog[$key]["phone"] = $arr_member_cached[$val["id"]]["phone"];

		}else{

			unset($lstLog[$key]);

		}

		

	}

	$smarty->assign("oneMember",$oneMember);

	$smarty->assign("lstLog",$lstLog);

	// Return

	$html = $core->build('_ajax.history_moc.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	));

}

?>