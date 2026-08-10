<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is Ã‚Â©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsTraining = new Training();
	$clsGroupProfile = new GroupProfile();
	#
	$list_preloaders = $list_months = $list_years = array();
	for($i=0; $i<=50; $i++){
		$list_preloaders[] = $i;
	}
	#
	$current_now = time();
	$current_year = date('Y');
	$current_month = date('n');
	for($i=1; $i<=$current_month; $i++){
		$list_months[] = $i;
	}
	for($i=2023; $i<=$current_year; $i++){
		$list_years[] = $i;
	}
	#hien thi popup ban tin
	$scriptJs = "";
	$more_information = $oneProfile['more_information'];
	$cond = "`is_trash`=0 AND `is_online`=1 AND `post_type`='_news' AND JSON_EXTRACT(`more_information`,'$.show_pop')='1'";
	$arr_show_pop_news = $core->get_field($more_information, "show_pop_news", []);
	if(!empty($arr_show_pop_news)) {
		$cond .= " and `{$clsNews->pkey}` NOT IN (".implode(',',$arr_show_pop_news).")";
	}
	$oneNews = $clsNews->getByCond($cond,$clsNews->pkey);
	if(!empty($oneNews)){
		$scriptJs.= '<a class="autoclick_'.$oneNews["news_id"].'"" news_id="'.$oneNews["news_id"].'" 
			onClick="open_news(this, event)" action="_detail" ></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$oneNews["news_id"].'\').trigger(\'click\');
				}, 500);
			})
		</script>';			
		$more_information['show_pop_news'][] = $oneNews["news_id"];
		$clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information)]);
	}
	###
	$assign_list["scriptJs"] = $scriptJs;
	#
	$date = date("dmY").$profile_id;
	srand($date);
	$condTraining = "`is_trash`='0' AND `is_online`='1'";
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$list_group = $clsGroupProfile->getAll("`is_trash`='0' AND `is_online`='1' AND `list_profile_id` LIKE '%|".$profile_id."|%'",$clsGroupProfile->pkey);		
	$condTraining .= " AND (`is_all_staff`='1' ";
	if(!empty($arr_department_ids) || !empty($list_group) ) {
		$first = 0;
		$condTraining .= " OR  (`is_all_staff`='0' AND ( `list_profile_id` LIKE '%|{$profile_id}|%'";
		if(!empty($arr_department_ids)) {
			foreach ($arr_department_ids as $department_id) {
				$condTraining .= " OR `list_department_id` LIKE '%|{$department_id}|%'";
			}
		}
		if(!empty($list_group)) {
			foreach ($list_group as $k => $_oGroup) {
				$condTraining .= " OR `list_group_profile_id` LIKE '%|{$_oGroup[$clsGroupProfile->pkey]}|%'";	
			}	
		}
		$condTraining .="))";
	}
	$condTraining .= " )";
	$totalQuote = $clsTraining->countItem($condTraining);
	$offset = rand(0,$totalQuote);
	$oneTraining = $clsTraining->getByCond($condTraining." ORDER BY RAND() LIMIT 1");
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$assign_list["oneTraining"] = $oneTraining;
	$assign_list["clsTraining"] = $clsTraining;
	$smarty->assign('list_preloaders', $list_preloaders);
    /*=============Title & Description Page==================*/
	$title_page = $clsConfiguration->getValue('meta_title');
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_overview(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = sprintf('%s - %s', 'Tổng quan thị trường', PAGE_NAME);
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_filter(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$uid = Input::post('uid');
	$holderG = Input::post('holderG');
	$smarty->assign('uid', $uid);
	$smarty->assign('holderG', $holderG);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.filter.tpl');
	echo $html; die();
}
function default_dashboard(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID
	,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$tp = Input::get('tp');
	$holderG = Input::post('holderG', '_month');
	$year = (int) Input::post('year', date('Y'));
	if($holderG=='_month'){
		$month = (int) Input::post('month', 0);
		if($month > 0 && $year > 0){
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		}
	}
	$cond = "`is_trash`=0 and `is_cancel`=0";
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		// GD
	} else if($clsISO->checkPermissionGroup('SALE')) {
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		$arrRoles = array();
		$clsProperty->getChilds($role_id, $arrRoles);
		if(!empty($arrRoles)){
			$arrRoles[] = $role_id;
			$cond.= " and `staff_id` in(
				select `profile_id` from ".$clsProfile->tbl." 
				where (`department_id`='{$department_id}' 
					or `list_department_id` like '%|{$department_id}|%'
				) and `role_id` in (".implode(',', $arrRoles).")
			)";
		} else {
			$cond .= " and `staff_id`='{$profile_id}'";
		}
	} else {
		$cond .= " and `staff_id`='{$profile_id}'";
	}
	$html = $callback = '';
	if($tp=='total'){
		$total = $clsBilling->countItem($cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".$year."'");
		$total_prev = $clsBilling->countItem($cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".($year-1)."'");
		$growth = 'gray'; $symbol = '~'; $percent = 0;
		if($total > $total_prev){
			$diff = $total - $total_prev;
			if($total_prev == 0){
				$percent = 100;
			} else {
				$percent = ($diff/$total_prev) * 100;
			}
			$growth = 'success';
			$symbol = '+';
		} else if($total < $total_prev){
			$diff = $total_prev - $total;
			$percent = ($diff/$total_prev) * 100;
			$growth = 'danger';
			$symbol = '-'; 
		}
		$html.= '<h3 class="card-title mb-2">'.$total.'</h3>
		<!-- <small class="text-'.$growth.' fw-semibold">
			<i class="bx bx-up-arrow-alt"></i> '.$symbol.$percent.'%
		</small> -->';
	} else if($tp=='revenue'){
		$total = $clsBilling->sumItem("totalgrand", $cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".$year."'");
		$total_prev = $clsBilling->sumItem("totalgrand", $cond." and FROM_UNIXTIME(`deposit_date`,'%Y')='".($year-1)."'");
		$growth = 'gray'; $symbol = '~'; $percent = 0;
		if($total > $total_prev){
			$diff = $total - $total_prev;
			if($total_prev == 0){
				$percent = 100;
			} else {
				$percent = ($diff/$total_prev) * 100;
			}
			$growth = 'success';
			$symbol = '+';
		} else if($total < $total_prev){
			$diff = $total_prev - $total;
			$percent = ($diff/$total_prev) * 100;
			$growth = 'danger';
			$symbol = '-'; 
		}
		$html.= '<h3 class="card-title text-nowrap mb-2">'.shortNumber($total).'</h3>
		<!-- <small class="text-'.$growth.' fw-semibold">
			<i class="bx bx-up-arrow-alt"></i> '.$symbol.$percent.'%
		</small> -->';
	} else if($tp=='commission'){
		$total = 0;
		$field = "{$clsBilling->pkey},totalgrand,commission";
		$list_billings = $clsBilling->getAll($cond, $field);
		if(!empty($list_billings)){
			foreach($list_billings as $key => $val){
				$totalgrand = $clsISO->processSmartNumber($val['totalgrand']);
				$commission = $clsISO->processSmartNumber($val['commission']);
				$total+= $totalgrand*$commission/100; 
			}
		}
		$html.= '<div class="mb-3">
			<span class="badge bg-label-warning rounded-pill">Năm '.$year.'</span>
		</div>
		<small class="text-success text-nowrap fw-semibold">
			<i class="bx bx-chevron-up"></i> 0%
		</small>
		<h3 class="mb-0">'.shortNumber($total).'</h3>';
	} else if($tp=='billing_type'){
		$field = "{$clsProperty->pkey},title,intro,image";
		// $list_types = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BILLING_TYPE' 
		//	order by `order_no` ASC", $field);
		$list_types = $clsProperty->getCacheItems("_BILLING_TYPE");
		$cond = "`is_trash`=0 and `is_cancel`='0'";
		if($clsISO->checkPermissionGroup('DIRECTOR')){
			// GD
		} else if($clsISO->checkPermissionGroup('SALE')) {
			$arrRoles = array();
			$role_id = $oneProfile['role_id'];
			$department_id = $oneProfile['department_id'];
			$clsProperty->getChilds($role_id, $arrRoles);
			if(!empty($arrRoles)){
				$arrRoles[] = $role_id;
				$cond.= " and `staff_id` in(
					select `profile_id` from ".$clsProfile->tbl." 
					where `department_id`='{$department_id}' and `role_id` in (".implode(',', $arrRoles).")
				)";
			}
		} else {
			$cond .= " and `staff_id`='{$profile_id}'";
		}
		###
		if(!empty($list_types)){ $ii = 0;
			$html.= '<div class="form-row">';
			foreach($list_types as $key => $val){
				$prop_id = $val[$clsProperty->pkey];
				$where = $cond . " and `billing_type`='{$prop_id}'";
				$total_billings = $clsBilling->countItem($where);
				if($total_billings > 0){
					$total_prices = $clsBilling->sumItem("totalgrand", $where);
					$html.= '<div class="col-6 mb-2">
						<div class="d-flex gap-2 align-items-cecnter">
							<div class="avatar flex-shrink-0">
								<span class="avatar-initial rounded bg-label-primary">
									<i class="bx '.$val['image'].'"></i>
								</span>
							</div>
							<div class="d-flex w-100 align-items-center justify-content-between gap-2">
								'.($deviceType=='phone' ? '<div class="me-1">
									<h6 class="mb-0">'.$val['title'].'</h6>
									<small class="fw-semibold text-main">'.shortNumber($total_prices).'</small>
								</div>' : '<div class="me-1">
									<h6 class="mb-0">'.$val['title'].'</h6>
									<small class="text-muted d-none d-lg-block">'.strip_tags($val['intro']).'</small>
								</div>
								<div class="user-progress">
									<small class="fw-semibold text-main">'.shortNumber($total_prices).'</small>
								</div>').'
							</div>
						</div>
					</div>';
				}
				++$ii;
			}
			$html.= '</div>';
		}
	} else if($tp=='top_billing'){
		$html = '';
		$field = "{$clsBilling->pkey},`billing_code`,`billing_type`,`sold_to_type`,`staff_id`,`project_id`,`more_information`,`reg_date`,`totalgrand`";
		$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 order by `reg_date` DESC limit 0,6", $field);
		// skin=dbx: redesign dashboard — list GD mới (tên trên · dự án·loại căn · số tiền xanh); die sớm, nhánh cũ giữ nguyên
		$skin = Input::post('skin', '');
		if($skin == 'dbx'){
			if(!empty($list_billings)){
				$clsProject = new Project();
				$arr_profile_cached = $clsProfile->getProfileCached();
				$arr_project_cached = $clsProject->getListProject();
				$arr_property_cached = array();
				$arr_stock_ids = array();
				foreach($list_billings as $key => $val){
					$more_information = $clsISO->to_array_json($val['more_information']);
					$list_billings[$key]['more_information'] = $more_information;
					$stock_id = (int) $core->get_field($more_information, 'stock_id', 0);
					if((int) $val['project_id'] <= 0 && $stock_id > 0){
						$arr_stock_ids[] = $stock_id;
					}
				}
				$arr_stock_project = array();
				if(!empty($arr_stock_ids)){
					$list_stocks = $clsStock->getAll("`{$clsStock->pkey}` in (".implode(',', $arr_stock_ids).")", "`{$clsStock->pkey}`,`project_id`");
					if(!empty($list_stocks)){
						foreach($list_stocks as $val){
							$arr_stock_project[(int) $val[$clsStock->pkey]] = (int) $val['project_id'];
						}
					}
				}
				$html.= '<div>';
				foreach($list_billings as $key => $val){
					$staff_id = (int) $val['staff_id'];
					$billing_type = (int) $val['billing_type'];
					$project_id = (int) $val['project_id'];
					$more_information = $val['more_information'];
					$stock_id = (int) $core->get_field($more_information, 'stock_id', 0);
					if($project_id <= 0 && isset($arr_stock_project[$stock_id])){
						$project_id = $arr_stock_project[$stock_id];
					}
					if($billing_type > 0 && !isset($arr_property_cached[$billing_type])){
						$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
					}
					$project_name = '';
					if($project_id > 0 && isset($arr_project_cached[$project_id])){
						$project_name = trim($arr_project_cached[$project_id]['title']);
					} else if($project_id > 0){
						$project_name = trim($clsProject->getTitle($project_id));
					}
					$type_name = $billing_type > 0 ? $arr_property_cached[$billing_type] : '';
					$sub_text = $project_name;
					if($type_name !== ''){
						$sub_text.= ($sub_text !== '' ? ' · ' : '').$type_name;
					}
					if($sub_text === ''){
						$sub_text = 'Giao dịch';
					}
					// staff_id=0 (import NV ngoài HT, GD PTĐT) không có profile → không tra DB, dùng tên người bán đã lưu
					$oStaff = isset($arr_profile_cached[$staff_id]) ? $arr_profile_cached[$staff_id] : array();
					$staff_name = !empty($oStaff) ? trim($clsProfile->getFullName($staff_id, $oStaff)) : '';
					if($staff_name === ''){
						$staff_name = trim($core->get_field($more_information, 'seller_name', ''));
					}
					if($staff_name === ''){
						$staff_name = 'Chưa gán nhân viên';
					}
					$avatar = !empty($oStaff) ? $clsProfile->getAvatar($staff_id, $oStaff) : URL_IMAGES.'/no-avatar.jpg';
					$ava_attr = '';
					if(!empty($oStaff)){
						$ava_attr = ' data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$staff_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300"';
					}
					$html.= '<div class="dbx-person">
						<div class="dbx-person__ava"'.$ava_attr.'>
							<img src="'.$avatar.'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />
						</div>
						<div class="dbx-person__body">
							<div class="dbx-person__name">'.$staff_name.'</div>
							<div class="dbx-person__meta"><i class="bx bx-building-house"></i>'.$sub_text.'</div>
						</div>
						<span class="dbx-person__amt dbx-person__amt--gain">'.shortNumber($val['totalgrand']).'</span>
					</div>';
				}
				$html.= '</div>';
			}
			echo json_encode(array('html' => $html, 'callback' => '')); die();
		}
		if(!empty($list_billings)){
			$html.= '<ul class="p-0 m-0">';
			$total_billings = count($list_billings);
			$arr_property_cached = $arr_profile_cached = array();
			$arr_profile_cached = $clsProfile->getProfileCached();
			foreach($list_billings as $key => $val){
				$staff_id = $val['staff_id'];
				$sold_to_type = $val['sold_to_type'];
				$billing_type = $val['billing_type'];
				if($billing_type > 0 && !isset($arr_property_cached[$billing_type])){
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
				}
				$sold_to_type_name = "";
				if($sold_to_type > 0){
					if(isset($arr_property_cached[$sold_to_type])){
						$sold_to_type_name = sprintf(' <span class="badge badge-sm bg-label-danger">%s</span>',$arr_property_cached[$sold_to_type]);
					} else {
						$arr_property_cached[$sold_to_type] = $clsProperty->getTitle($sold_to_type);
						$sold_to_type_name = sprintf(' <span class="badge badge-sm bg-label-danger">%s</span>',$arr_property_cached[$sold_to_type]);
					}
				}
				$html.= '<li class="d-flex align-items-cecnter'.($key==$total_billings-1? '' : ' mb-1 pb-2').'">
					<div class="avatar avatar-sm flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$staff_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300">
						<img src="'.$clsProfile->getAvatar($staff_id, $arr_profile_cached[$staff_id]).'" 
							onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" class="rounded-pill" />
						'.$clsProfile->get_icon_verified($staff_id).'
					</div>
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
							<small class="text-muted d-block">Dự án '.$arr_property_cached[$billing_type].'</small>
							<div class="user-progress d-flex align-items-center gap-1">
								<h6 class="mb-0 text-main">'.shortNumber($val['totalgrand']).'</h6>
							</div>
						</div>
						<h6 class="mb-0">
							'.$clsProfile->getFullName($staff_id, $arr_profile_cached[$staff_id]).$sold_to_type_name.'
						</h6>
					</div>
				</li>';
			}
			$html.= '</ul>';
		}
		$callback = '';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function age_calculator($birthday){
	$bday = new DateTime(date('Y-m-d', $birthday)); 
	$today = new Datetime('today');
	$diff = $today->diff($bday);
	return $diff->y;
}
function days_to_birth($birthday){
	$today = time();
	$fixedBirthdate = date_create(date("Y", $today) . "-" . date("m", $birthday) . "-" . date("d", $birthday));
	$diff = date_diff(date_create(date("d-m-Y", $today)), $fixedBirthdate);
	return $diff->format("%a ngày nữa");
}
function default_staff_birthday(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$timer = time();
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG', '30days');
	$department_id = (int) Input::post('department_id', 0);
	#
	$field = "{$clsProfile->pkey},full_name,avatar,birthday,FROM_UNIXTIME(`birthday`,'%m-%d') as `birthday_ord`";
	if($department_id > 0){
		$department_id = $oneProfile['department_id'];
		$d1 = new DateTime(date('Y-m-d'));
		$d2 = new DateTime(sprintf('%s-%s-%s', date('Y'), 12, 31));
		$diff = $d2->diff($d1);
		$cond = "`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%') AND `birthday`>0 AND DATE_ADD(FROM_UNIXTIME(`birthday`),INTERVAL YEAR(CURDATE()) - YEAR(FROM_UNIXTIME(`birthday`)) + IF( DAYOFYEAR(CURDATE()) > DAYOFYEAR(FROM_UNIXTIME(`birthday`)),1,0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ".$diff->days." DAY)";
		$callback = 'setTimeout(() => {
			const verticalExample = document.getElementById(\'birthday_staffs\');
			if (verticalExample) {
				new PerfectScrollbar(verticalExample, {
					wheelPropagation: false,
					suppressScrollX: true,
					suppressScrollY: false
				});
			}
		},500);';
	} else {
		$cond = "`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND birthday<>'' 
		AND DATE_ADD(FROM_UNIXTIME(birthday),INTERVAL YEAR(CURDATE()) - YEAR(FROM_UNIXTIME(birthday)) + IF( DAYOFYEAR(CURDATE()) > DAYOFYEAR(FROM_UNIXTIME(birthday)), 1, 0) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ".($holderG=='7days'?'7':'30')." DAY)";
		$callback = 'setTimeout(() => {
			const verticalExample = document.getElementById(\''.$uid.'\');
			if (verticalExample) {
				new PerfectScrollbar(verticalExample, {
					wheelPropagation: false,
					suppressScrollX: true,
					suppressScrollY: false
				});
			}
		},500);';
	}
	$list_staffs = $clsProfile->getAll($cond." order by `birthday_ord` ASC", $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$age = age_calculator($val['birthday']);
			$days_to_birth = days_to_birth($val['birthday']);
			$list_staffs[$key]['age']= $age;
			$list_staffs[$key]['days_to_birth']= $days_to_birth;
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('list_staffs', $list_staffs);
	// Return
	$html = $core->build('_ajax.birthday.tpl');
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_unknow(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang;
	header('Location:'.PCMS_URL.$extLang);
	exit();
}
function default_load_profile_popover(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	##
	$type = Input::get('type', "");
	$user_id = (int) Input::get('user_id', 0);
	if($user_id == $profile_id){
		$oProfile = $oneProfile;
	} else {
		$field = "`phone`,`email`,`role_id`,`status_id`,`department_id`,`list_department_id`,`more_information`,`start_date`";
		$oProfile = $clsProfile->getOne($user_id, $field);
	}
	$phone = $oProfile['phone'];
	$email = $oProfile['email'];
	$role_id = $oProfile['role_id'];
	$status_id = $oProfile['status_id'];
	$department_id = $oProfile['department_id'];
	$more_information = $oneProfile['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$isMask = $clsISO->checkSupper() ? false: ($clsISO->checkPermission('view_phone_profile') ? false : true);
	$total_billing = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$total_price = $clsBilling->sumItem("totalgrand", "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$total_compete = $clsBilling->sumItem("realized_sales_compete", "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$status = ($status_id == _STATUS_STAFF_ON_ID) ? 'online' : 'offline';
	// Nút xem hồ sơ đầy đủ: chỉ hiện khi người xem có quyền (self / BLĐ+HCNS / quản lý xem cấp dưới)
	$canViewProfile = $clsProfile->canViewProfile($clsISO, $oneProfile, $profile_id, $oProfile, $user_id);
	$html = '<div clas="profile-wrap">
		<div class="banner w-100 h-px-80 bg-main rounded-2"></div>
		<div class="avatar bg-white avatar-xl avatar-'.$status.' rounded-pill mx-auto border border-2" style="margin-top:-40px">
			<img class="avatar rounded-pill avatar-xxl" src="'.$clsProfile->getAvatar($user_id, $oneUser).'">
		</div>
		<div class="text-center my-2">
			<h3 class="fs-5 mb-1">'.$clsProfile->getFullName($user_id, $oneUser).'</h3> 
			<p class="text-muted mb-0"><i class=\'bx bx-map translate-px-2\'></i>'.$clsProperty->getTitle($role_id).', '.$clsProperty->getTitle($department_id).'</p>
		</div>
		<div class="d-flex gap-1 mb-2 align-items-center justify-content-center">
			<a href="tel:'.$phone.'" class="phone btn btn-outline-default">
				<i class="bx bx-phone-call"></i> '.$clsProfile->mask($phone, $isMask).'
			</a>
			<a href="https://zalo.me/'.$phone.'" target="_blank" class="zalo btn btn-icon btn-outline-default">
				<img src="'.URL_IMAGES.'/zalo_chat.png" class="w-px-20">
			</a>
			<a href="mailto:'.$email.'" target="_blank" class="btn btn-icon btn-outline-default">
				<i class="bx bx-envelope"></i>
			</a>
			'.(($type != 'org_chart' && $canViewProfile) ? '<a href="javascript:void(0);" onClick="$Core.member.view_profile(this, event)" profile_id="'.$user_id.'" class="btn btn-icon btn-outline-default">
				<i class="bx bx-dots-vertical-rounded"></i>
			</a>' : '').'
		</div>
		'.(($type != 'org_chart') ? '
			<div class="w-90 mx-auto">
				<hr class="my-3" />
			</div>
			<div class="metadata-page-render mt-2">
				<section class="block metadata-section-render">
					<div class="metadata-row-render">
						<div class="metadata-row-title">
							<span class="title">Ngày bắt đầu</span>
						</div>
						<div class="metadata-row-viewer w-200 d-inline-block">
							'.$clsISO->convertTimeToText($oProfile['start_date']).'
						</div>
					</div>
					<div class="metadata-row-render">
						<div class="metadata-row-title">
							<span class="title">Tổng giao dịch</span>
						</div>
						<div class="metadata-row-viewer d-inline-block">'.$total_billing.' GD</div>
					</div>
					<div class="metadata-row-render">
						<div class="metadata-row-title">
							<span class="title">Tổng doanh số</span>
						</div>
						<div class="metadata-row-viewer d-inline-block">
							<strong class="red">'.$clsISO->shortNumber($total_price).'</strong>
						</div>
					</div>
					<div class="metadata-row-render">
						<div class="metadata-row-title">
							<span class="title">Doanh số thi đua</span>
						</div>
						<div class="metadata-row-viewer d-inline-block">
							<strong class="red">'.$clsISO->shortNumber($total_compete).'</strong>
						</div>
					</div>
				</section>
			</div>' : '').'
	</div>';
	// Return
	echo $html; die();
}
/** GIAO Dá»ŠCH */
function default_billing(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	// if(!$clsISO->checkPermission('access_billing')){}
		// $core->redirect('/#not_permiss');
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsBillingSale = new BillingSale();
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsCustomer', $clsCustomer);
	$lstProject = $clsProject->getListProject();
	$assign_list["lstProject"] = $lstProject;
	
	/*if($clsISO->_DEV()){ //update doanh số cá nhân
		$dbconn->debug=true;
		$arr_cache_billing = [];
		$lstBillingSale = $clsBillingSale->getAll("1=1");
		foreach ($lstBillingSale as $key => $val) {
			if(!isset($arr_cache_billing[$val["billing_id"]])) {
				$arr_cache_billing[$val["billing_id"]] = $clsBilling->getOne($val["billing_id"]);
			}
			$main_ratio = $val["share_ratio"];
			$oneBilling = $arr_cache_billing[$val["billing_id"]];
			$billing_information = $clsISO->to_array_json($oneBilling["more_information"]);
			$total_commission_value = $core->get_field($billing_information,"commission_value",0);
			$commission = $core->get_field($billing_information,"commission",0);
			$total_commission_value = !empty($total_commission_value) ? $clsISO->processSmartNumber($total_commission_value) : $clsISO->processSmartNumber($oneBilling["totalgrand"]);
			$total_commission_value = $total_commission_value * $commission / 100;
			$total_deduction = $core->get_field($billing_information,"total_deduction",0);
			$total_deduction_percent_sales = $core->get_field($billing_information,"total_deduction_percent_sales",0);
			$total_deduction = $main_ratio/100 * $total_deduction; // tổng tiền giảm trừ 1 sale
			$total_deduction_sales = $total_deduction_percent_sales / 100 * $total_deduction; // sale phải chịu
			$commission_value = ($main_ratio / 100 * $total_commission_value) - ($total_deduction - $total_deduction_sales);  // doanh số thi đua sau giảm trừ
//			var_dump($arr_cache_billing[$val["billing_id"]]["stock_code"]. "-" . $commission_value."-".$val["share_value"]);die;
			$clsBillingSale->updateOne($val[$clsBillingSale->pkey],["commission_value" => round($commission_value)]);
		}
		$clsISO->print_pre($lstBillingSale);die;		
	}*/
	
	#
	$lstDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$lstDepChild = $clsISO->buildTree($lstDepartment,_DEPARTMENT_SALE_ID, $clsProperty->pkey);
	$smarty->assign('lstDepChild', $lstDepChild);
	#
	$cond  = "`is_trash`=0 AND `is_cancel`=0"; // AND `is_cancel`=0
	$cond_new  = "`t1`.`is_trash`=0 AND `t1`.`is_cancel`=0"; // AND `is_cancel`=0
	$department_id = $oneProfile['department_id']; // ID phòng
	$more_information = $oneProfile['more_information'];
	$billing_configs = $core->get_field($more_information, "billing_configs", array('is_action_visible' => 0));
	$permiss_add = $clsISO->checkPermission('create_billing') ? 1 : 0;
	$permiss_view = $clsISO->checkPermission('view_billing') ? 1 : 0;
	$permiss_edit = $clsISO->checkPermission('edit_billing') ? 1 : 0;
	$permiss_cancel = $clsISO->checkPermission('cancel_billing') ? 1 : 0;
	$permiss_delete = $clsISO->checkPermission('delete_billing') ? 1 : 0;
	$permiss_add_info = $clsISO->checkPermission('add_info_billing') ? 1 : 0;
	$permiss_export = $clsISO->checkPermission('export_billing') ? 1 : 0;
	$smarty->assign('permiss_add', $permiss_add);
	$smarty->assign('permiss_view', $permiss_view);
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_cancel', $permiss_cancel);
	$smarty->assign('permiss_delete', $permiss_delete);
	$smarty->assign('permiss_export', $permiss_export);
	$smarty->assign('permiss_add_info', $permiss_add_info);
	// Quyết toán dùng chung gate với cấu hình bậc thang hoa hồng — cùng một loại việc: chốt tiền
	$smarty->assign('permiss_settlement', _billing_commission_can_manage() ? 1 : 0);
	$smarty->assign('billing_configs', $billing_configs);
	if(isset($_POST['filter']) && $_POST['filter'] =='filter'){
		$link = '/giao-dich.html';
		$hasCond = false;
		$keyword = Input::post('keyword');
		$dept_id = (int) Input::post('dept_id', 0);
		$staff_id = (int) Input::post('staff_id', 0);
		$contract_status_id = (int) Input::post('contract_status_id', 0);
		$status_id = (int) Input::post('status_id', 0);
		$project_id = (int) Input::post('project_id', 0);
		$block_id = (int) Input::post('block_id', 0);
		$billing_type = (int) Input::post('billing_type', 0);
		$billing_source = (int) Input::post('billing_source', 0);
		$start_date = Input::post('start_date', 0);
		$to_date = Input::post('to_date', 0);
		$is_sendpale = Input::post('is_sendpale', '_all');
		$is_sendemail = Input::post('is_sendemail', '_all');
		$is_sendemail = Input::post('is_sendemail', '_all');
		$sort_by = Input::post('sort_by', 'reg_date');
		$filter_by = Input::post('filter_by', '_project');
		if(!empty($filter_by)){
			$link .= ($hasCond?'&':'?') . 'filter_by='.$filter_by;
			$hasCond = true;
		}
		if(!empty($keyword)){
			$link .= ($hasCond?'&':'?') . 'keyword='.$keyword;
			$hasCond = true;
		}
		if($staff_id > 0){
			$link .= ($hasCond?'&':'?') . 'staff_id='.$staff_id;
			$hasCond = true;
		}
		if($dept_id > 0){
			$link .= ($hasCond?'&':'?') . 'dept_id='.$dept_id;
			$hasCond = true;
		}
		if($contract_status_id > 0){
			$link .= ($hasCond?'&':'?') . 'contract_status_id='.$contract_status_id;
			$hasCond = true;
		}
		if($status_id > 0){
			$link .= ($hasCond?'&':'?') . 'status_id='.$status_id;
			$hasCond = true;
		}
		if($project_id > 0){
			$link .= ($hasCond?'&':'?') . 'project_id='.$project_id;
			$hasCond = true;
		}
		if($block_id > 0){
			$link .= ($hasCond?'&':'?') . 'block_id='.$block_id;
			$hasCond = true;
		}
		if(!empty($start_date)){
			$link .= ($hasCond?'&':'?') . 'start_date='.$clsISO->toTime($start_date);
			$hasCond = true;
		}
		if(!empty($to_date)){
			$link .= ($hasCond?'&':'?') . 'to_date='.$clsISO->toTime($to_date." 23:59:59");
			$hasCond = true;
		}
		if($billing_type > 0){
			$link .= ($hasCond?'&':'?') . 'billing_type='.$billing_type;
			$hasCond = true;
		}
		if($billing_source > 0){
			$link .= ($hasCond?'&':'?') . 'billing_source='.$billing_source;
			$hasCond = true;
		}
		if($is_sendpale != '_all'){
			$link .= ($hasCond?'&':'?') . 'is_sendpale='.$is_sendpale;
			$hasCond = true;
		}
		if($is_sendemail != '_all'){
			$link .= ($hasCond?'&':'?') . 'is_sendemail='.$is_sendemail;
			$hasCond = true;
		}
		if(!empty($sort_by)){
			$link .= ($hasCond?'&':'?') . 'sort_by='.$sort_by;
			$hasCond = true;
		}
		header('Location:' . $link);
		exit();
	}
	$keyword = Input::get('keyword');
	$dept_id = (int) Input::get('dept_id', 0);
	$staff_id = (int) Input::get('staff_id', 0);
	$status_id = (int) Input::get('status_id', 0);
	$contract_status_id = (int) Input::get('contract_status_id', 0);
	$project_id = (int) Input::get('project_id', 0);
	$block_id = (int) Input::get('block_id', 0);
	$billing_type = (int) Input::get('billing_type', 0);
	$billing_source = (int) Input::get('billing_source', 0);
	$start_date = (int) Input::get('start_date',0);
	$to_date = (int) Input::get('to_date', 0);
	$sort_by = Input::get('sort_by', 'reg_date');
	$per_page = (int) Input::get('per_page', 30);
	$is_sendpale = Input::get('is_sendpale', '_all');
	$is_sendemail = Input::get('is_sendemail', '_all');
	$filter_by = Input::get('filter_by', '_dept');
	$search_field = Input::get('search_field', '');
	$is_success = Input::get('is_success', '');
	###
	$smarty->assign('keyword', $keyword);
	$smarty->assign('dept_id', $dept_id);
	$smarty->assign('staff_id', $staff_id);
	$smarty->assign('status_id', $status_id);
	$smarty->assign('contract_status_id', $contract_status_id);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('block_id', $block_id);
	$smarty->assign('billing_type', $billing_type);
	$smarty->assign('billing_source', $billing_source);
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
	$smarty->assign('sort_by', $sort_by);
	$smarty->assign('is_sendpale', $is_sendpale);
	$smarty->assign('is_sendemail', $is_sendemail);
	$smarty->assign('filter_by', $filter_by);
	// Lọc theo vị trí
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('view_all_billing')){
		// GD
	} else if($clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')) {
		$list_staffs = $clsProfile->getProfileDep($department_id, 1, "all");
		$arr_staff_ids = !empty($list_staffs) ? @array_keys($list_staffs) : [];
		$cond.= " AND `staff_id` in ('".implode('\',\'', $arr_staff_ids)."')";
		$cond_new.= " AND `t1`.`staff_id` in ('".implode('\',\'', $arr_staff_ids)."')";
	} else if($oneProfile['role_id'] == _ROLE_GD_PROJECT){
		if($filter_by == '_project' && $project_id == 0){
			$params = array();
			$more_information = $oneProfile['more_information'];
			$permiss_billing = $core->get_field($more_information, 'permiss_billing', []);
			if(!empty($permiss_billing)){
				foreach($permiss_billing as $loop_id => $arrs){
					if(!empty($arrs)){
						foreach($arrs as $id){
							$params[] = sprintf('|%s_%s|', $loop_id, $id);
						}
					}
				}
			}
			if(!empty($params)){
				$cond.= " AND `billing_search` in ('".implode('\',\'',$params)."')";
				$cond_new.= " AND `t1`.`billing_search` in ('".implode('\',\'',$params)."')";
			} else {
				$params[] = sprintf('|%s_%s|', 100000, 100000);
				$cond.= " AND `billing_search` in ('".implode('\',\'',$params)."')";
				$cond_new.= " AND `t1`.`billing_search` in ('".implode('\',\'',$params)."')";
			}
		} else {
			$cond.= " AND `staff_id` in (
				select `profile_id` from ".$clsProfile->tbl." 
				where (`department_id`='{$department_id}' or `team_id`='{$department_id}'
					or `list_department_id` like '%|{$department_id}|%'
				)
			)";
			$cond_new.= " AND `t1`.`staff_id` in (
				select `profile_id` from ".$clsProfile->tbl." 
				where (`department_id`='{$department_id}' or `team_id`='{$department_id}'
					or `list_department_id` like '%|{$department_id}|%'
				)
			)";
		}
	} else if($oneProfile['role_id'] == _ROLE_STAFF_ADMIN) {
		$params = array();
		$more_information = $oneProfile['more_information'];
		$block_permiss = $core->get_field($more_information, 'block_permiss', []);
		$permiss_billing = $core->get_field($more_information, 'permiss_billing', []);
		if(!empty($permiss_billing)){
			foreach($permiss_billing as $loop_id => $arrs){
				if(!empty($arrs)){
					foreach($arrs as $id){
						$params[] = sprintf('|%s_%s|', $loop_id, $id);
					}
				}
			}
		}
		#
		if(!empty($block_permiss)) {
			$cond.= " AND (JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',$block_permiss).") 
				OR (`project_id`="._PROJECT_OTHER_ID." AND `admin_id`='{$profile_id}')
			)";	
			$cond_new.= " AND (JSON_EXTRACT(`t1`.`more_information`,\"$.block_id\") IN (".implode(',',$block_permiss).") 
				OR (`t1`.`project_id`="._PROJECT_OTHER_ID." AND `t1`.`admin_id`='{$profile_id}')
			)";			
		}else{
			$cond.= " AND JSON_EXTRACT(`more_information`,\"$.block_id\")=''";
			$cond_new.= " AND JSON_EXTRACT(`t1`.`more_information`,\"$.block_id\")=''";
		}
	} else {
		$cond.= " AND (`staff_id`='{$profile_id}' OR `{$clsBilling->pkey}` IN (SELECT `billing_id` FROM `{$clsBillingSale->tbl}` WHERE `staff_id`='{$profile_id}'))";		
		$cond_new.= " AND (`t1`.`staff_id`='{$profile_id}' OR `t1`.`{$clsBilling->pkey}` IN (SELECT `billing_id` FROM `{$clsBillingSale->tbl}` WHERE `staff_id`='{$profile_id}'))";		
	}
	$pUrl = ''; 
	$params = array('filter_by' => $filter_by);
	if(!empty($keyword)) {
		$params['keyword'] = $keyword;
		$cond.=" AND (`billing_code` LIKE '%{$keyword}%' 
			OR `stock_code` LIKE '%{$keyword}%'
			OR JSON_EXTRACT(`more_information`,\"$.customer_phone\") LIKE '%{$keyword}%'
			OR JSON_EXTRACT(`more_information`,\"$.customer_email\") LIKE '%{$keyword}%'
			OR JSON_EXTRACT(`more_information`,\"$.customer_name\") LIKE '%{$keyword}%'
		)";
		$cond_new.=" AND (`t1`.`billing_code` LIKE '%{$keyword}%' 
			OR `t1`.`stock_code` LIKE '%{$keyword}%'
			OR JSON_EXTRACT(`t1`.`more_information`,\"$.customer_phone\") LIKE '%{$keyword}%'
			OR JSON_EXTRACT(`t1`.`more_information`,\"$.customer_email\") LIKE '%{$keyword}%'
			OR JSON_EXTRACT(`t1`.`more_information`,\"$.customer_name\") LIKE '%{$keyword}%'
		)";
	}
	if($staff_id > 0) {
		$params['staff_id'] = $staff_id;
		$cond.= " AND `staff_id`='{$staff_id}'";
		$cond_new.= " AND `t2`.`staff_id`='{$staff_id}'";
	}
	if($dept_id > 0){
		$params['dept_id'] = $dept_id;
		$cond.= " AND `staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$dept_id}' or `team_id`='{$dept_id}'
				or `list_department_id` like '%|{$dept_id}|%'
			)
		)";
		$cond_new.= " AND `t1`.`staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$dept_id}' or `team_id`='{$dept_id}'
				or `list_department_id` like '%|{$dept_id}|%'
			)
		)";	
	}
	if($contract_status_id > 0) {
		$params['contract_status_id'] = $contract_status_id;
		$cond.= " AND (`contract_status_id`='{$contract_status_id}' OR `agree_status_id`='{$contract_status_id}')";
		$cond_new.= " AND (`t1`.`contract_status_id`='{$contract_status_id}' OR `t1`.`agree_status_id`='{$contract_status_id}')";
	}
	if($project_id > 0) {
		$params['project_id'] = $project_id;
		$cond.= " and `project_id`='{$project_id}'";
		$cond_new.= " and `t1`.`project_id`='{$project_id}'";
	}
	if($block_id > 0){
		$params['block_id'] = $block_id;
		$cond.= " AND JSON_EXTRACT(`more_information`,'$.block_id')='{$block_id}'";	
		$cond_new.= " AND JSON_EXTRACT(`t1`.`more_information`,'$.block_id')='{$block_id}'";	
	}
	if($billing_type > 0) {
		$params['billing_type'] = $billing_type;
		$cond.= " AND `billing_type`='{$billing_type}'";
		$cond_new.= " AND `t1`.`billing_type`='{$billing_type}'";
	}
	if($billing_source > 0) {
		$params['billing_source'] = $billing_source;
		$cond.= " AND `billing_source_id`='{$billing_source}'";
		$cond_new.= " AND `t1`.`billing_source_id`='{$billing_source}'";
	}
	if($is_sendpale != '_all'){
		$params['is_sendpale'] = $is_sendpale;
		$cond_new.= " AND JSON_EXTRACT(`t1`.`more_information`,\"$.is_sendpale\")='{$is_sendpale}'";
	}
	if($is_sendemail != '_all'){
		$params['is_sendemail'] = $is_sendemail;
		$cond_new.= " AND JSON_EXTRACT(`t1`.`more_information`,\"$.is_sendemail\")='{$is_sendemail}'";
	}
	$date_field = "deposit_date";
	if($sort_by == 'contract_date'){
		$date_field = 'contract_date'; // Ngày ký HĐMB
	}
	$params['sort_by'] = $sort_by;
	if($start_date > 0 && $to_date ==0){
		$params['start_date'] = $start_date;
		$cond.=" AND (`{$date_field}` >= '{$start_date}')";
		$cond_new.=" AND (`t1`.`{$date_field}` >= '{$start_date}')";
	} else if($start_date==0 && $to_date > 0){
		$params['to_date'] = $to_date;
		$cond.=" AND (`{$date_field}` <= '{$to_date}')";
		$cond_new.=" AND (`t1`.`{$date_field}` <= '{$to_date}')";
	} else if($start_date > 0 && $to_date > 0){
		$params['start_date'] = $start_date;
		$params['to_date'] = $to_date;
		$cond.= " AND (`{$date_field}` BETWEEN '{$start_date}' AND '{$to_date}')";
		$cond_new.= " AND (`t1`.`{$date_field}` BETWEEN '{$start_date}' AND '{$to_date}')";
	}
	if(!empty($search_field)) {
		$params['search_field'] = $search_field;
		$params['is_success'] = $is_success;
		if(!empty($is_success)) {
			if($search_field == "contract_date") {
				$cond.= " AND `contract_date`>0 ";
				$cond_new.= " AND `t1`.`contract_date`>0 ";
			}else{
				$cond.= " AND(JSON_EXTRACT(`more_information`,\"$.".$search_field."\") IS NOT NULL AND JSON_EXTRACT(`more_information`,\"$.".$search_field."\")<>'')";
				$cond_new.= " AND(JSON_EXTRACT(`t1`.`more_information`,\"$.".$search_field."\") IS NOT NULL AND JSON_EXTRACT(`t1`.`more_information`,\"$.".$search_field."\")<>'')";
			}
		}else if($is_success == "0") {
			if($search_field == "contract_date") {
				$cond.= " AND `contract_date` = 0 ";
				$cond_new.= " AND `t1`.`contract_date` = 0 ";
			}else{
				$cond.= " AND(JSON_EXTRACT(`more_information`,\"$.".$search_field."\") IS NULL 
					OR  JSON_EXTRACT(`more_information`,\"$.".$search_field."\") = '')";
				$cond_new.= " AND(JSON_EXTRACT(`t1`.`more_information`,\"$.".$search_field."\") IS NULL 
					OR  JSON_EXTRACT(`t1`.`more_information`,\"$.".$search_field."\") = '')";
			}
		}
	}
	//	echo $cond;die;
	if(!empty($params)){ $ii = 0;
		foreach($params as $key => $val){
			$pUrl.= ($ii==0?"?":"&").$key.'='.$val;
			++$ii;
		}
	}
	$smarty->assign('pUrl', $pUrl);
	// Mặc Định
	$cnd = $cond;
	#tổng doanh số sau khi chia
	$total_grand = 0;
	if(!($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('view_all_billing') || $clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') || $oneProfile['role_id'] == _ROLE_GD_PROJECT)) {
		$cond_new .= " AND `t2`.`staff_id`='{$profile_id}'";
	}
	if($clsISO->_DEV()){
//		$dbconn->debug=true;
	}
	$lst_billings = $dbconn->getAll("SELECT `t1`.`billing_id`,`t1`.`stock_code`,`t2`.`staff_id`,`t2`.`share_ratio`,`t2`.`share_value` FROM `{$clsBilling->tbl}` AS `t1` RIGHT JOIN `{$clsBillingSale->tbl}` AS `t2` ON `t1`.`billing_id`=`t2`.`billing_id` WHERE {$cond_new}");
	if(!empty($lst_billings)) {
		foreach($lst_billings as $key => $val) {
			$total_grand += (int)$val["share_value"];
		}
	}
	if($clsISO->_DEV()){
//		$clsISO->print_pre($lst_billings);die;
	}
	
	$total_billings = $clsBilling->countItem($cond);
	$total_trans_billings = $clsBilling->countItem($cond. " AND `billing_type`='"._BILLING_TYPE_SOP_ID."'");
	$total_registed_hdmb = $clsBilling->countItem($cond."  AND `is_cancel`=0 
		AND `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
	$total_unregisted_hdmb = $clsBilling->countItem($cond." AND `is_cancel`=0 
		AND `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' AND `estimate_date`<>'0'");
	$total_not_schedule_hdmb = $clsBilling->countItem($cond." AND `is_cancel`=0 
		AND `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' AND `estimate_date`='0'");
	$smarty->assign('total_grand', $total_grand);
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('total_trans_billings', $total_trans_billings);
	$smarty->assign('total_registed_hdmb', $total_registed_hdmb);
	$smarty->assign('total_unregisted_hdmb', $total_unregisted_hdmb);
	$smarty->assign('total_not_schedule_hdmb', $total_not_schedule_hdmb);
	#- Begin pagination
	$current_page = (int) Input::get('page',1);
	$per_page = (int) Input::get('per_page',30);
	$smarty->assign('current_page', $current_page);
	$smarty->assign('per_page', $per_page);
	$total_record = $clsBilling->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$field = "*";
	$order_by = " ORDER BY `reg_date` DESC";
	if($sort_by == 'contract_date'){
		$order_by = " ORDER BY `contract_date` ASC";
	}
	// $clsISO->print_pre($cond); die();
	$list_billings = $clsBilling->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = array();
		$arr_projects_cached = $clsProject->getListProject();
		$arr_profile_cached = $clsProfile->getProfileCached();
		// Co-sale: batch sale phụ (is_primary=0) cho mọi billing trong list — tránh N+1
		$map_co_sellers = array();
		$_arr_bids = array();
		foreach($list_billings as $_v){ $_arr_bids[] = (int) $_v[$clsBilling->pkey]; }
		if(!empty($_arr_bids)){
			$_clsBillingSale = new BillingSale();
			$_co_rows = $_clsBillingSale->getAll("`billing_id` IN (".implode(',', $_arr_bids).") AND `is_primary`=0 ORDER BY `share_ratio` DESC", "`billing_id`,`staff_id`,`seller_name`,`share_ratio`");
			if(!empty($_co_rows)){
				foreach($_co_rows as $_cr){ $map_co_sellers[(int) $_cr['billing_id']][] = $_cr; }
			}
		}
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			$admin_id = $val['admin_id'];
			$partner_id = $val['partner_id'];
			$project_id = $val['project_id'];
			$is_cancel = (int) $val['is_cancel'];
			$billing_type = $val['billing_type'];
			$sold_to_type = $val['sold_to_type'];
			$deposit_date = $val['deposit_date'];
			$contract_status_id = (int) $val['contract_status_id'];
			$billing_source_id = $val['billing_source_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_billings[$key]['more_information'] = $more_information;
			$list_billings[$key]['score'] = $clsBilling->getScore($val[$clsBilling->pkey], $val);
			$block_id = !empty($more_information["block_id"]) ? $more_information["block_id"] : 0;
			$is_interest_accrued = $core->get_field($more_information, "is_interest_accrued", 0);
			$list_billings[$key]['is_interest_accrued'] = $is_interest_accrued;
			/** Background */
			$class = "";
			if($is_cancel == 1){
				$class = "bg-label-danger nohover";
			}
			$list_billings[$key]['class'] = $class;
			###
			$oProfile = $arr_profile_cached[$staff_id];
			$list_billings[$key]['oProfile'] = $oProfile;
			$_bid_cs = (int) $val[$clsBilling->pkey];
			$list_billings[$key]['co_sellers'] = isset($map_co_sellers[$_bid_cs]) ? $map_co_sellers[$_bid_cs] : array();
			$oAdminProfile = $arr_profile_cached[$admin_id];
			$list_billings[$key]['oAdminProfile'] = $oAdminProfile;
			if($billing_source_id > 0){
				if(!isset($arr_property_cached[$billing_source_id])){
					$arr_property_cached[$billing_source_id] = $clsBilling->getBillingSource($billing_source_id);
				}
				$list_billings[$key]['billing_source'] = $arr_property_cached[$billing_source_id];
			} else {
				$list_billings[$key]['billing_source'] = "";
			}
			if($billing_type > 0){
				if(isset($arr_property_cached[$billing_type])){
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				} else {
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				}
			} else {
				$list_billings[$key]['billing_type'] = "";
			}
			if($sold_to_type > 0){
				if(isset($arr_property_cached[$sold_to_type])){
					$list_billings[$key]['sold_to_type'] = $arr_property_cached[$sold_to_type];
				} else {
					$arr_property_cached[$sold_to_type] = $clsProperty->getTitle($sold_to_type);
					$list_billings[$key]['sold_to_type'] = $arr_property_cached[$sold_to_type];
				}
			} else {
				$list_billings[$key]['sold_to_type'] = "N/A";
			}
			###
			if($project_id > 0){
				$list_billings[$key]['project_name'] = $arr_projects_cached[$project_id]["title"]; // ten du an (khong phai ma)
			} else {
				$list_billings[$key]['project_name'] = "";
			}
			if(!empty($block_id) && !empty($arr_cache_block[$block_id])){
				$list_billings[$key]['block_name'] = $arr_cache_block[$block_id]["property_code"];
			} else {
				$list_billings[$key]['block_name'] = "";
			}
		}
	}
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('current_page', $current_page);
	$smarty->assign('totalgrand', $totalgrand);
	##
	$params = '';
	$query_string = @$_SERVER['QUERY_STRING'];
	$lst_query_string = @explode('&',$query_string);
	if(!empty($lst_query_string)){ $ii = 0;
		foreach($lst_query_string as $val){
			$tmp = @explode('=', $val);
			if($tmp[0]!='page' && $tmp[0] != 'mod' && $tmp[0] != 'act' && $tmp[0] != 'lang'){
				$params .= ($ii==0)?'?'.$val:'&'.$val;
				++$ii;
			}
		}
	}
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link_page_1'	=> '/giao-dich.html',
		'link' => '/giao-dich/'
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(true, $params);
	$assign_list["html_pager"] = $html_pager;
	/*=============Title & Description Page==================*/
	$title_page = 'Lịch sử giao dịch - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_load_report_billing(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$block_permiss = [];
	$arr_cache_block = $clsProperty->getArraySearchByKey("_BLOCK");
	foreach($arr_cache_block as $key => $val) {
		$more_block = $val["more_information"];
		$project_admins = $core->get_field($more_block, "project_admins", []);
		if($clsISO->checkItemInArray($profile_id,$project_admins) 
			|| ($profile_id == _PROFILE_MY_LINH_ADMIN_ID && $val["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE)){
			$block_permiss[] = $val[$clsProperty->pkey];
		}
	}
	#
	$cond  = "`is_trash`=0"; // AND `is_cancel`=0
	$department_id = $oneProfile['department_id']; // ID phòng
	$more_information = $oneProfile['more_information'];
	$keyword = Input::post('keyword');
	$dept_id = (int) Input::post('dept_id', 0);
	$staff_id = (int) Input::post('staff_id', 0);
	$contract_status_id = (int) Input::post('contract_status_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$billing_type = (int) Input::post('billing_type', 0);
	$billing_source = (int) Input::post('billing_source', 0);
	$start_date = Input::post('start_date', "");
	$to_date = Input::post('to_date', "");
	$is_sendpale = Input::post('is_sendpale', '_all');
	$is_sendemail = Input::post('is_sendemail', '_all');
	$is_sendemail = Input::post('is_sendemail', '_all');
	$sort_by = Input::post('sort_by', 'reg_date');
	$filter_by = Input::post('filter_by', '_project');	
	$start_date = !empty($start_date) ? strtotime($start_date) : 0;
	$to_date = !empty($to_date) ? strtotime($to_date) : 0;
	// Lọc theo vị trí
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('view_all_billing')){
		// GD
	} else if($clsISO->checkPermissionGroup('SALE_DIRECTOR') 
		&& $oneProfile['role_id'] != _ROLE_GD_PROJECT) {
		$cond.= " AND `staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$department_id}' or `team_id`='{$department_id}'
				or `list_department_id` like '%|{$department_id}|%'
			)
		)";
	} else if($oneProfile['role_id'] == _ROLE_GD_PROJECT){
		if($filter_by == '_project' && $project_id == 0){
			$params = array();
			$more_information = $oneProfile['more_information'];
			$permiss_billing = $core->get_field($more_information, 'permiss_billing', []);
			if(!empty($permiss_billing)){
				foreach($permiss_billing as $loop_id => $arrs){
					if(!empty($arrs)){
						foreach($arrs as $id){
							$params[] = sprintf('|%s_%s|', $loop_id, $id);
						}
					}
				}
			}
			if(!empty($params)){
				$cond.= " AND `billing_search` in ('".implode('\',\'',$params)."')";
			} else {
				$params[] = sprintf('|%s_%s|', 100000, 100000);
				$cond.= " AND `billing_search` in ('".implode('\',\'',$params)."')";
			}
		} else {
			$cond.= " AND `staff_id` in (
				select `profile_id` from ".$clsProfile->tbl." 
				where (`department_id`='{$department_id}' or `team_id`='{$department_id}'
					or `list_department_id` like '%|{$department_id}|%'
				)
			)";
		}
	} else if($oneProfile['role_id'] == _ROLE_STAFF_ADMIN) {
		#
		if(!empty($block_permiss)) {
			$cond.= " AND JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',$block_permiss).")";
		}else{
			$cond.= " AND JSON_EXTRACT(`more_information`,\"$.block_id\")=''";
		}
	} else {
		$cond.= " AND `staff_id`='{$profile_id}'";
	}
	$pUrl = ''; 
	if(!empty($keyword)) {
		$cond.=" AND (`billing_code` like '%{$keyword}%' or `stock_code` like '%{$keyword}%')";

	}
	if($staff_id > 0) {
		$cond.= " AND `staff_id`='{$staff_id}'";
	}
	$lstDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if($dept_id > 0){
		#
		$oDept = $lstDepartment[$dept_id];
		if($oDept["parent_id"] == _DEPARTMENT_SALE_ID) {
			$parent_dep = $dept_id;	
		}else{
			$parent_dep = $oDept["parent_id"];	
		}
		$cond.= " AND `staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$parent_dep}' or `team_id`='{$parent_dep}'
				or `list_department_id` like '%|{$parent_dep}|%'
			)
		)";
	}else{
		$parent_dep = _DEPARTMENT_SALE_ID;
	}	
	if($contract_status_id > 0) {
		$cond.= " AND (`contract_status_id`='{$contract_status_id}' OR `agree_status_id`='{$contract_status_id}')";
	}
	if($project_id > 0) {
		$cond.= " and `project_id`='{$project_id}'";
	}
	if($block_id > 0){
		$cond.= " AND JSON_EXTRACT(`more_information`,'$.block_id')='{$block_id}'";	
	}
	if($billing_type > 0) {
		$cond.= " AND `billing_type`='{$billing_type}'";
	}
	if($billing_source > 0) {
		$cond.= " AND `billing_source_id`='{$billing_source}'";
	}
	if($is_sendpale != '_all'){
		$cond.= " AND JSON_EXTRACT(`more_information`,\"$.is_sendpale\")='{$is_sendpale}'";
	}
	if($is_sendemail != '_all'){
		$cond.= " AND JSON_EXTRACT(`more_information`,\"$.is_sendemail\")='{$is_sendemail}'";
	}
	$date_field = "deposit_date";
	if($sort_by == 'contract_date'){
		$date_field = 'contract_date'; // Ngày ký HĐMB
	}
	if($start_date > 0 && $to_date ==0){
		$cond.=" AND (`{$date_field}` >= '{$start_date}')";
	} else if($start_date==0 && $to_date > 0){
		$cond.=" AND (`{$date_field}` <= '{$to_date}')";
	} else if($start_date > 0 && $to_date > 0){
		$cond.= " AND (`{$date_field}` BETWEEN '{$start_date}' AND '{$to_date}')";
	}
	if(!empty($search_field)) {
		if(!empty($is_success)) {
			if($search_field == "contract_date") {
				$cond.= " AND `contract_date` > 0 ";
			}else{
				$cond.= " AND( JSON_EXTRACT(`more_information`,\"$.".$search_field."\") IS NOT NULL AND  JSON_EXTRACT(`more_information`,\"$.".$search_field."\") <> '')";
			}
		}else if($is_success == "0") {
			if($search_field == "contract_date") {
				$cond.= " AND `contract_date` = 0 ";
			}else{
				$cond.= " AND( JSON_EXTRACT(`more_information`,\"$.".$search_field."\") IS NULL OR  JSON_EXTRACT(`more_information`,\"$.".$search_field."\") = '')";
			}
		}	
	}
	// Mặc Định
//	echo $cond;die;
	$cnd = $cond;
	$total_grand = $clsBilling->sumItem("totalgrand",$cond);
	$total_billings = $clsBilling->countItem($cond);
	$total_trans_billings = $clsBilling->countItem($cond. " AND `billing_type`='"._BILLING_TYPE_SOP_ID."'");
	$total_registed_hdmb = $clsBilling->countItem($cond."  AND `is_cancel`=0 
		AND `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
	$total_unregisted_hdmb = $clsBilling->countItem($cond." AND `is_cancel`=0 
		AND `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' AND `estimate_date`<>'0'");
	$total_not_schedule_hdmb = $clsBilling->countItem($cond." AND `is_cancel`=0 
		AND `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' AND `estimate_date`='0'");
//	$dbconn->debug=true;
	$total_billing_dq = $clsBilling->countItem($cond." AND `is_cancel`=0 
		AND `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'");
//	echo $total_billing_dq;die;
	$smarty->assign('total_grand', $total_grand);
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('total_trans_billings', $total_trans_billings);
	$smarty->assign('total_registed_hdmb', $total_registed_hdmb);
	$smarty->assign('total_unregisted_hdmb', $total_unregisted_hdmb);
	$smarty->assign('total_not_schedule_hdmb', $total_not_schedule_hdmb);
	$smarty->assign('total_billing_dq', $total_billing_dq);
	#- End pagination
	$field = "*";
	#
	$lstDepChild = $clsISO->buildTree($lstDepartment,$parent_dep, $clsProperty->pkey);
	$listStaff = $clsProfile->getProfileDep($parent_dep, 1);
	$cond .= " AND `staff_id` IN (".implode(',',array_keys($listStaff)).")";
	$list_billings = $clsBilling->getAll($cond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			###
			$oProfile = $arr_profile_cached[$staff_id];
			$dep_profile = $oProfile["department_id"];				
			if($parent_dep != _DEPARTMENT_SALE_ID) {
				if(!isset($lstDepChild[$dep_profile])) {
					$lstDepChild[$dep_profile] = $lstDepartment[$dep_profile];
					$lstDepChild[$dep_profile]["title"] = "PKD";
				}
				$dep_id = $dep_profile;
			}else{
				$oDep = $lstDepartment[$dep_profile];
				if($dep_profile == _DEPARTMENT_DEVELOP_ID) {
					$lstDepChild[$dep_profile]["title"] = "PTĐT"; 
					$lstDepChild[$dep_profile]["bgcolor"] = "#cc5500"; 
					$lstDepChild[$dep_profile]["textcolor"] = "#ffffff"; 
					$dep_id = $dep_profile;
				}else{
					if(!isset($lstDepChild[$dep_profile])) {
						$dep_id = $oDep["parent_id"];
					}else{
						$dep_id = $dep_profile;
					}
				}
			}
			if(empty($lstDepChild[$dep_id])) {
				continue;
			}
			if(!isset($lstDepChild[$dep_id]["total_billing"])) {
				$lstDepChild[$dep_id]["total_billing"] = 1;
				$lstDepChild[$dep_id]["totalgrand"] = $val["totalgrand"];
				if($val["billing_type"] == _BILLING_TYPE_SOP_ID) {
					$lstDepChild[$dep_id]["total_trans_billings"] = 1;
				}else{
					$lstDepChild[$dep_id]["total_trans_billings"] = 0;
				}
				if($val["contract_status_id"] == _CONTRACT_STATUS_DONE_ID && $val["is_cancel"] == 0) {
					$lstDepChild[$dep_id]["total_registed_hdmb"] = 1;
				}else{
					$lstDepChild[$dep_id]["total_registed_hdmb"] = 0;
				}
				if($val["contract_status_id"] == _CONTRACT_STATUS_WAIT_ID && $val["is_cancel"] == 0 && $val["estimate_date"] != 0) {
					$lstDepChild[$dep_id]["total_unregisted_hdmb"] = 1;
				}else{
					$lstDepChild[$dep_id]["total_unregisted_hdmb"] = 0;
				}
				if($val["contract_status_id"] == _CONTRACT_STATUS_WAIT_ID && $val["is_cancel"] == 0 && $val["estimate_date"] == 0) {
					$lstDepChild[$dep_id]["total_not_schedule_hdmb"] = 1;
				}else{
					$lstDepChild[$dep_id]["total_not_schedule_hdmb"] = 0;
				}
				if($val["billing_source_id"] == _BILLING_RESOURCE_F1_ID) {
					$lstDepChild[$dep_id]["total_billing_dq"] = 1;
				}else{
					$lstDepChild[$dep_id]["total_billing_dq"] = 0;
				}
			}else{
				$lstDepChild[$dep_id]["total_billing"] += 1;	
				$lstDepChild[$dep_id]["totalgrand"] += $val["totalgrand"];
				if($val["billing_type"] == _BILLING_TYPE_SOP_ID) {
					$lstDepChild[$dep_id]["total_trans_billings"] += 1;
				}
				if($val["contract_status_id"] == _CONTRACT_STATUS_DONE_ID && $val["is_cancel"] == 0) {
					$lstDepChild[$dep_id]["total_registed_hdmb"] += 1;
				}
				if($val["contract_status_id"] == _CONTRACT_STATUS_WAIT_ID && $val["is_cancel"] == 0 && $val["estimate_date"] != 0) {
					$lstDepChild[$dep_id]["total_unregisted_hdmb"] += 1;
				}
				if($val["contract_status_id"] == _CONTRACT_STATUS_WAIT_ID && $val["is_cancel"] == 0 && $val["estimate_date"] == 0) {
					$lstDepChild[$dep_id]["total_not_schedule_hdmb"] += 1;
				}
				if($val["billing_source_id"] == _BILLING_RESOURCE_F1_ID) {
					$lstDepChild[$dep_id]["total_billing_dq"] += 1;
				}
			}
			$lstDepChild[$dep_id]["abc"][] = $staff_id;
		}
	}
	$smarty->assign('lstDepChild', $lstDepChild);
	$smarty->assign('list_billings', $list_billings);
//	$smarty->assign('total_record', $total_record);
//	$smarty->assign('total_page', $total_page);
//	$smarty->assign('current_page', $current_page);
//	$smarty->assign('totalgrand', $totalgrand);
	$html = $core->build("_ajax.load_report_billing.tpl");
	echo json_encode(array(
		"html"	=>	$html
	), JSON_UNESCAPED_UNICODE);
}
function default_load_report_billing_dep(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$cond  = "`is_trash`=0"; // AND `is_cancel`=0
	$department_id = (int) Input::post('department_id', 0);
	$lstDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if($department_id > 0){
		$listStaff = $clsProfile->getProfileDep($department_id, 1);
		$cond.= " AND `staff_id` IN (".implode(',',array_keys($listStaff)).")";
	}
	#- End pagination
	$field = "*";
	#
	$lstDepChild = $clsISO->buildTree($lstDepartment,$department_id, $clsProperty->pkey);
	$list_billings = $clsBilling->getAll($cond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			###
			$oProfile = $arr_profile_cached[$staff_id];
			$dep_profile = $oProfile["department_id"];
            if(!isset($lstDepChild[$dep_profile])) {
                $lstDepChild[$dep_profile] = $lstDepartment[$dep_profile];
                $lstDepChild[$dep_profile]["title"] = "PKD";
            }
            $dep_id = $dep_profile;
			if(empty($lstDepChild[$dep_id])) {
				continue;
			}
			if(!isset($lstDepChild[$dep_id]["total_billing"])) {
				$lstDepChild[$dep_id]["total_billing"] = 1;
				if($val["contract_status_id"] == _CONTRACT_STATUS_DONE_ID && $val["is_cancel"] == 0) {
					$lstDepChild[$dep_id]["total_registed_hdmb"] = 1;
				}else{
					$lstDepChild[$dep_id]["total_registed_hdmb"] = 0;
				}
			}else{
				$lstDepChild[$dep_id]["total_billing"] += 1;	
				if($val["contract_status_id"] == _CONTRACT_STATUS_DONE_ID && $val["is_cancel"] == 0) {
					$lstDepChild[$dep_id]["total_registed_hdmb"] += 1;
				}
			}
		}
	}
//	$clsISO->print_pre($lstDepChild);die;
	$smarty->assign('lstDepChild', $lstDepChild);
	$html = $core->build("_ajax.load_report_billing_dep.tpl");
	echo json_encode(array(
		"html"	=>	$html
	), JSON_UNESCAPED_UNICODE);
}
function default_load_report_warning_dep(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$cond  = "`is_trash`=0"; // AND `is_cancel`=0
	$field = "*";
	#
    $lstDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$lstDepChild = $clsISO->buildTree($lstDepartment,_DEPARTMENT_SALE_ID, $clsProperty->pkey);
    $total_dep_rate_low = $total_dep_not_contract = 0;
    $time_prev = strtotime("-7 days");
//    $time_prev = time();
	$total_bill = 0;
    foreach ($lstDepChild as $dep_id => $val)  {
        $listStaff = $clsProfile->getProfileDep($dep_id, 1);
        $list_billings = $clsBilling->getAll($cond. " AND `staff_id` IN (".implode(',',array_keys($listStaff)).")");
        $total_billing = $total_registed_hdmb = 0;
		$total_bill += count($list_billings);
		$more_dep = !empty($val["more_information"]) ? $val["more_information"] : [];
        if(!empty($list_billings)){
            foreach($list_billings as $key => $_oBilling){
                $total_billing += 1;
                if($_oBilling["contract_status_id"] == _CONTRACT_STATUS_DONE_ID && $_oBilling["is_cancel"] == 0) {
                    $total_registed_hdmb += 1;
//					echo 1;
                }
            }
        }
        $rate = $clsISO->getRateNumber($total_registed_hdmb,$total_billing);		
//		$clsISO->print_pre($val["title"]."-Ky: ".$total_registed_hdmb."-".$total_billing."-".$rate);
        if($rate != "" && $rate < 40 && !empty($more_dep["is_business_area"])) {
            ++$total_dep_rate_low;
        }
        if($total_billing - $total_registed_hdmb > 40 && !empty($more_dep["is_business_area"])) {
            ++$total_dep_not_contract;
        }
		$lstDepChild[$dep_id]["total_billing"] = $total_billing;
    }
	$total_billing_not_contract = $clsBilling->countItem($cond." AND `is_cancel`=0 AND `contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' AND `estimate_date`='0' AND `deposit_date` < $time_prev");
	$smarty->assign('total_dep_rate_low', $total_dep_rate_low);
	$smarty->assign('total_dep_not_contract', $total_dep_not_contract);
	$smarty->assign('total_billing_not_contract', $total_billing_not_contract);
	$html = $core->build("_ajax.load_report_warning_dep.tpl");
	echo json_encode(array(
		"html"	=>	$html
	), JSON_UNESCAPED_UNICODE);
}
function default_get_filter_by(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$department_id = $oneProfile['department_id'];
	// $department_name = $oneProfile['department_name'];
	// $clsISO->print_pre($oneProfile); die();
	$filter_by = Input::post('filter_by', "_dept");
	$project_id = (int) Input::post('project_id', 0);
	$dept_id = (int) Input::post('dept_id', 0);
	$html_projects = '<option value="">Chọn dự án</option>';
	$arr_project_ins = $arr_staffs = $arr_departments = array();
	if($filter_by == '_dept'){
		$p_field = "{$clsProject->pkey},`title`";
		$tmp = $clsProject->getAll("`is_trash`=0", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html_projects.= '<option'.($project_id == $val[$clsProject->pkey] ? ' selected="selected"' : '').' 
					value="'.$val[$clsProject->pkey].'">'.$val['title'].'</option>';
			}
			unset($tmp);
		}
		# Phòng ban
		$arr_departments = array(
			'id' => $department_id,
			'text' => $clsProperty->getTitle($department_id)
		);
		#- Nhân viên
		$p_field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`code`";
		$tmp = $clsProfile->getAll("`is_active`='1' AND `department_id`='{$department_id}' 
			AND `status_id`='"._STATUS_STAFF_ON_ID."'", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_staffs[] = array(
					'id' => $val[$clsProfile->pkey],
					'text' => sprintf('%s-%s', $val['code'], $clsProfile->getFullName($val[$clsProfile->pkey], $val))
				);
			}
			unset($tmp);
		}
	} else {
		$more_information = $oneProfile['more_information'];
		$permiss_billing = $core->get_field($more_information, 'permiss_billing', []);
		if(!empty($permiss_billing)){
			$arr_project_ins = array_keys($permiss_billing);
		}
		$p_field = "{$clsProject->pkey},`title`";
		$tmp = $clsProject->getAll("`is_trash`=0 AND {$clsProject->pkey} IN (".implode(',', $arr_project_ins).")", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html_projects.= '<option'.($project_id == $val[$clsProject->pkey] ? ' selected="selected"' : '').' 
					value="'.$val[$clsProject->pkey].'">'.$val['title'].'</option>';
			}
			unset($tmp);
		}
		# Phòng ban
		$p_field = "{$clsProperty->pkey},`title`,`parent_id`";
		$tmp = $clsProperty->getAll("`is_trash`='0' AND property_type='_DEPARTMENT' 
			AND {$clsProperty->pkey}<>'"._DEPARTMENT_SALE_ID."' ORDER BY order_no ASC", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$slash = ($val['parent_id'] == _DEPARTMENT_SALE_ID) ? "|---" : "";
				$arr_departments[] = array(
					'id' => $val[$clsProperty->pkey],
					'text' => $slash . $clsProperty->getTitle($val[$clsProperty->pkey], $val)
				);
			}
			unset($tmp);
		}
		#- Nhân viên
		$sql_query = "`is_active`='1' AND `status_id`='"._STATUS_STAFF_ON_ID."'";
		if($dept_id > 0) $sql_query.= " AND `department_id`='{$dept_id}'";		
		$p_field = "{$clsProfile->pkey},`code`,`full_name`,`first_name`,`last_name`";
		$tmp = $clsProfile->getAll($sql_query." ORDER BY `reg_date` ASC", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_staffs[] = array(
					'id' => $val[$clsProfile->pkey],
					'text' => sprintf('%s-%s', $val['code'], $clsProfile->getFullName($val[$clsProfile->pkey], $val))
				);
			}
			unset($tmp);
		}
	}
	// Return
	echo json_encode(array(
		'arr_departments' => $arr_departments,
		'arr_staffs' => $arr_staffs,
		'html_projects' => $html_projects
	)); die();
}

function default_do_action(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile, $clsProfile;
	$clsProperty = new Property();
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$action_date = time();
	$action = Input::post('action');
	$list_id = Input::post('list_id', []);
	if(!empty($action) && !empty($list_id)){
		if($action == 'contract_signed'){
			foreach($list_id as $billing_id){
				$oneBilling = $clsBilling->getOne($billing_id, "`logs`,`contract_status_id`");
				$action_logs = $oneBilling['logs'];
				$contract_status_id = (int) $oneBilling['contract_status_id'];
				$action_logs = $clsISO->to_array_json($action_logs);
				if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
					continue;
				}
				$action_logs[$clsISO->getUniqid()] = array(
					'reg_date' => time(),
					'user_id' => $profile_id,
					'content' => sprintf('<strong>%s</strong> đã cập nhật tình trạng ký HĐMB thành <strong>%s</strong> vào lúc <strong>%s</strong>',
					$clsProfile->getFullName($profile_id, $oneProfile), $clsProperty->getTitle(_CONTRACT_STATUS_DONE_ID), 
					$clsISO->convertTimeToText($action_date, true))
				);
				// $dbconn->debug = true;
				if($clsBilling->updateOne($billing_id, array(
					'contract_status_id' => _CONTRACT_STATUS_DONE_ID,
					'logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
				))){
					$msg = "_success";
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_export(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	$callStartTime = microtime(true);
	$objPHPExcel = new PHPExcel();
	$creator = PAGE_NAME;
    $titlePage = 'Export User List';
	// Set document properties
    $objPHPExcel->getProperties()->setCreator($creator)
            ->setLastModifiedBy("")
            ->setTitle($titlePage)
            ->setSubject($titlePage)
            ->setDescription("")
            ->setKeywords("Export user list")
            ->setCategory($creator);
	$objPHPExcel->setActiveSheetIndex(0);
	$tblBackgroundHeader = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'b8cce4;')
        )
    );
	$tblBackgroundCancel = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'C00000;')
        )
    );
	$tblBackgroundHeaderRequired = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'a09f9f;')
        )
    );
	$tblBorderOutline = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'ccc'),
            ),
        ),
    );
	//$clsISO->print_pre($objPHPExcel); die();
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Mã căn');
	$objPHPExcel->getActiveSheet()->setCellValue('B1','Ngày cọc');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Ngày ký HĐMB');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Sales bán');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Loại hình');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Dự án');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Phân khu');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Nguồn gốc');
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Nguồn quỹ');
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Chủ đầu tư');
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Tình trạng');
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Doanh số');
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($tblBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($tblBackgroundHeader);
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
	// Set document autosize column
	PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
	foreach(range('A','L') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getNumberFormat()->setFormatCode(
		PHPExcel_Style_NumberFormat::FORMAT_TEXT
	);
	$cond= "`is_trash`=0";
	$keyword = Input::get('keyword');
	$staff_id = (int) Input::get('staff_id', 0);
	$status_id = (int) Input::get('status_id', 0);
	$department_id = (int) Input::get('department_id', 0);
	$contract_status_id = (int) Input::get('contract_status_id', 0);
	$project_id = (int) Input::get('project_id', 0);
	$block_id = (int) Input::get('block_id', 0);
	$billing_type = (int) Input::get('billing_type', 0);
	$billing_source = (int) Input::get('billing_source', 0);
	$sort_by = Input::get('sort_by', 'reg_date');
	$start_date = (int) Input::get('start_date', 0);
	$to_date = (int) Input::get('to_date', 0);
	$is_sendpale = Input::get('is_sendpale', '_all');
	$is_sendemail = Input::get('is_sendemail', '_all');
	/** Giám đốc dự án */
	if($oneProfile['role_id'] == _ROLE_GD_PROJECT && $department_id > 0){
		$params['department_id'] = $department_id;
		$cond.= " and `staff_id` in (
			select `profile_id` from ".$clsProfile->tbl." 
			where (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%'
			)
		)";
	}
	if(!empty($keyword)) {
		$cond.=" and (`billing_code` like '%{$keyword}%' 
			or `stock_code` like '%{$keyword}%'
		)";
	}
	if($staff_id > 0) {
		$cond.= " and `staff_id`='{$staff_id}'";
	}
	if($contract_status_id > 0) {
		$cond.= " and `contract_status_id`='{$contract_status_id}'";
	}
	if($project_id > 0) {
		$cond.= " and `project_id`='{$project_id}'";
	}
	if($block_id > 0){
		$cond.= " and JSON_EXTRACT(`more_information`,'$.block_id')='{$block_id}'";	
	}
	if($billing_type > 0) {
		$cond.= " and `billing_type`='{$billing_type}'";
	}
	if($billing_source > 0) {
		$cond.= " and `billing_source_id`='{$billing_source}'";
	}
	if($is_sendpale != '_all'){
		$cond.= " and JSON_EXTRACT(`more_information`,\"$.is_sendpale\")='{$is_sendpale}'";
	}
	#- Filter by send_email
	if($is_sendemail != '_all'){
		$cond.= " and JSON_EXTRACT(`more_information`,\"$.is_sendemail\")='{$is_sendemail}'";
	}
	#- Filter by date
	$date_field = "deposit_date";
	if($sort_by == 'contract_date'){
		$date_field = 'contract_date'; // Ngày ký HĐMB
	}
	if($start_date > 0 && $to_date ==0){
		$cond.=" and (`{$date_field}` > '{$start_date}')";
	} else if($start_date==0 && $to_date > 0){
		$cond.=" and (`{$date_field}` < '{$to_date}')";
	} else if($start_date > 0 && $to_date > 0){
		$cond.= " and (`{$date_field}` between '{$start_date}' and '{$to_date}')";
	}
	#
	$field = "*";
	$order_by = " order by `reg_date` ASC";
	if($sort_by == 'contract_date'){
		$order_by = " order by `contract_date` ASC";
	}
	// $clsISO->print_pre($cond); die();
	$list_billings = $clsBilling->getAll($cond.$order_by, $field);
	// $clsISO->print_pre($list_billings); die();
	if(!empty($list_billings)){
		$row = 2; $arr_profile_cached = $arr_property_cached = $arr_projects_cached =  array();
		$tmp = $clsProperty->getAll("property_type='_BILLING_TYPE'", "{$clsProperty->pkey},title");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		$tmp = $clsProject->getAll("is_trash='0'", "{$clsProject->pkey},title");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_projects_cached[$val[$clsProject->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			$project_id = (int) $val['project_id'];
			$billing_type = (int) $val['billing_type'];
			$is_cancel = (int) $val['is_cancel'];
			$billing_id = $val[$clsBilling->pkey];
			$billing_source_id = $val['billing_source_id'];
			$contract_status_id = $val['contract_status_id'];
			$agree_status_id = $val['agree_status_id'];
			$deposit_date = $val['deposit_date'];
			$contract_date = $val['contract_date'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$stock_resource = $core->get_field($more_information, "stock_resource", 0);
			$block_id = (int) $core->get_field($more_information, "block_id", 0);
			#
			$block_name = $investor_name =- "";
			$oneBlock = $dbconn->getRow("select `t1`.`title`,`t2`.`title` AS `investor_name` 
				FROM {$clsProperty->tbl} AS `t1` LEFT JOIN {$clsProperty->tbl} AS `t2` ON JSON_EXTRACT(`t1`.`more_information`,\"$.investor_id\")=`t2`.`property_id` AND `t2`.`property_type`='_INVESTOR' 
				WHERE `t1`.`property_id`='{$block_id}' AND `t1`.`property_type`='_BLOCK' ");
			if(!empty($oneBlock)){
				$block_name = $core->get_field($oneBlock, "title", "");
				$investor_name = $core->get_field($oneBlock, "investor_name", "");
			}
			#
			if(!isset($arr_profile_cached[$staff_id])){
				$arr_profile_cached[$staff_id] = $clsProfile->getIndentity($staff_id, false);
			}
			$status_name = ($is_cancel == 1) ? 'Đã huỷ' : "";
			if($agree_status_id> 0 && !isset($arr_property_cached[$agree_status_id])){
				$arr_property_cached[$agree_status_id] = $clsProperty->getTitle($agree_status_id);
				$status_name = $arr_property_cached[$agree_status_id];
			}
			if($contract_status_id> 0 && !isset($arr_property_cached[$contract_status_id])){
				$arr_property_cached[$contract_status_id] = $clsProperty->getTitle($contract_status_id);
				$status_name = $arr_property_cached[$contract_status_id];
			}
			if($billing_source_id > 0 && !isset($arr_property_cached[$billing_source_id])){
				$arr_property_cached[$billing_source_id] = $clsProperty->getTitle($billing_source_id);
			}
			if($stock_resource > 0 && !isset($arr_property_cached[$stock_resource])){
				$arr_property_cached[$stock_resource] = $clsProperty->getTitle($stock_resource);
			}
			if($block_id > 0 && !isset($arr_property_cached[$block_id])){
				$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
			}
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $val['stock_code']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $clsISO->convertTimeToText($deposit_date));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, ($contract_date > 0 
				? $clsISO->convertTimeToText($contract_date) : "") );
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $arr_profile_cached[$staff_id]);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $arr_property_cached[$billing_type]);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $arr_projects_cached[$project_id]);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $block_name);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $arr_property_cached[$stock_resource]);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $arr_property_cached[$billing_source_id]);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $investor_name);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $status_name);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $clsISO->formatPrice($val['totalgrand']));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':L'.$row)->applyFromArray($tblBorderOutline);
			if($is_cancel) $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':L'.$row)->applyFromArray($tblBackgroundCancel);
			++$row;
		}
		unset($list_billings);
	}
	// $clsISO->print_pre($list_staffs); die();
	$nameFile = 'Transaction_'.date('d_m_Y_his');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client's web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.($nameFile).'.xls');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
    $objWriter->save('php://output');
    exit;
}
function str_replace_first($search, $replace, $subject)	{
	$search = '/'.preg_quote($search, '/').'/';
	return preg_replace($search, $replace, $subject, 1);
}
function mask_code($stock_code){
	global $core, $clsISO;
	if($clsISO->checkContainer($stock_code,"-","")){
		$tmp = @explode('-', $stock_code);
		$code = $tmp[1];
		$code = preg_replace('/[0-9]/','X', $code);
		return sprintf('%s-%s', $tmp[0], $code);
	} else {
		$clsStock = new Stock();
		$field = "{$clsStock->pkey},floor";
		$oStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", $field);
		if(!empty($oStock)){
			$code = $oStock['code'];
			$floor = $oStock['floor'];
			return str_replace_first($floor, 'XX', $stock_code);
		}
	}
	return $stock_code;
}
function default_load_block(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProperty = new Property();
	$block_id = Input::post('block_id', 0);
	$project_id = Input::post('project_id', 0);
	$block_type = Input::post('block_type', 0);
	$is_selectize = Input::post('is_selectize', 0);
	$html = '';
	if($is_selectize) {
		$html.='<select name="block_id" class="iso-selectizeSync form-control required">';
	}
	$field = "{$clsProperty->pkey},`title`";
	$cond = "`is_trash`=0 and `property_type`='_BLOCK' and `for_id`='{$project_id}'";
	if(!empty($block_type)) {
		$cond .= " AND `parent_id`='{$block_type}'";
	}else{		
		$html.= '<option value="0">Phân khu</option>';
	}
	$list_blocks = $clsProperty->getAll($cond . " order by `order_no` ASC", $field);
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html.= '<option'.($block_id==$val[$clsProperty->pkey] ? ' selected': '').' value="'.$val[$clsProperty->pkey].'">
				'.$val['title'].'
			</option>';
		}
		unset($list_blocks);
	}
	if($is_selectize) {
		$html.='</select>';
	}
	// Return
	echo $html; die();
}
function default_load_billing_calendar(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$oneProfile;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	###
	$current_now = time();
	$start = (int) Input::get('start',0);
	$end = (int) Input::get('end',0);
	$openFrom = Input::get('openFrom', "_billing");
	$type_of_date = Input::get('type_of_date', "_all");
	$billing_type = (int) Input::get('billing_type', 0);
	$sql_string = "`is_trash`=0 and `is_cancel`=0";
	if($billing_type > 0) $sql_string.= " and `billing_type`='{$billing_type}'";	
	#get by role
	$arr_billing_type = array();
	if(in_array($oneProfile["role_id"], array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$permiss_billing = !empty($oneProfile["more_information"]['permiss_billing']) ? $oneProfile["more_information"]['permiss_billing'] : array();
		if(!empty($permiss_billing)) {
			foreach ($permiss_billing as $k => $val) {
				$arr_billing_type = array_merge($arr_billing_type,$val);
			}
		}
		if(!empty($arr_billing_type)) {
			$sql_string .= " AND `billing_type` IN (".implode(',',$arr_billing_type).")";
		}else{
			$sql_string .= " AND `billing_type` = '0'";
		}
	}
	###
	$results = array();
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$date = date('d/m/Y',$i);
		$total_billings = $total_registered = $total_unregisted = 0;
		$field = "{$clsBilling->pkey},`stock_code`,`contract_status_id`,`estimate_date`,`agree_date`,`billing_source_id`";
		$cond = " and (FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$date}' 
			or (estimate_date>='{$current_now}' and FROM_UNIXTIME(`estimate_date`,'%d/%m/%Y')='{$date}') 
			or FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$date}')";
		if($type_of_date == '_contract'){
			$cond = " and (FROM_UNIXTIME(`contract_date`,'%d/%m/%Y')='{$date}' 
			or (estimate_date>='{$current_now}' and FROM_UNIXTIME(`estimate_date`,'%d/%m/%Y')='{$date}'))";
		} else if($type_of_date == '_text'){
			$cond = " and (`agree_date`>0 and FROM_UNIXTIME(`agree_date`,'%d/%m/%Y')='{$date}')";
		}
		$list_billings = $clsBilling->getAll($sql_string.$cond, $field);
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			foreach($list_billings as $key => $val){
				$stock_code = $val['stock_code'];
				$agree_date = $val['agree_date'];
				$estimate_date = $val['estimate_date'];
				$billing_source_id = $val['billing_source_id'];
				$contract_status_id = $val['contract_status_id'];
				if($type_of_date == '_all'){
					if($estimate_date > 0 && date('d/m/Y',$estimate_date) == $date){
						if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
							$total_registered += 1;
							$tooltip = "Đã ký HĐMB";
							$cls = "bg-blue text-white";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s %s', 'F1', mask_code($val['stock_code']));
							}
						} else {
							$total_unregisted += 1;
							$tooltip = "Chưa ký HĐMB";
							$cls = "bg-yellow text-main";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s-%s %s', 'F1', date('H:i', $estimate_date), mask_code($stock_code));
							} else {
								$stock_code = sprintf('%s %s', date('H:i', $estimate_date), mask_code($stock_code));
							}
						}
						$results[] = array(
							'flag' => 1,
							'cls' => $cls,
							'tooltip' => $tooltip,
							'total_registered' => $total_registered,
							'total_unregisted' => $total_unregisted,
							'billing_id' => $val[$clsBilling->pkey],
							'title' => $stock_code,
							'date_f' => date('Y-m-d'),
							'start' => date('Y-m-d H:i:s',$i)
						);
					}
					if($agree_date > 0 && date('d/m/Y',$agree_date) == $date){
						$results[] = array(
							'flag' => 1,
							'cls' => 'bg-danger text-white',
							'tooltip' => 'Ngày ký VBTT',
							'billing_id' => $val[$clsBilling->pkey],
							'title' => mask_code($stock_code),
							'date_f' => date('Y-m-d'),
							'start' => date('Y-m-d H:i:s', $i)
						);
					}
				} else if($type_of_date == '_contract'){
					if($estimate_date > 0 && date('d/m/Y',$estimate_date) == $date){
						if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
							$total_registered += 1;
							$tooltip = "Đã ký HĐMB";
							$cls = "bg-blue text-white";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s %s', 'F1', mask_code($val['stock_code']));
							}
						} else {
							$total_unregisted += 1;
							$tooltip = "Chưa ký HĐMB";
							$cls = "bg-yellow text-main";
							if($billing_source_id==_BILLING_RESOURCE_F1_ID && $openFrom=='_billing'){
								$stock_code = sprintf('%s-%s %s', 'F1', date('H:i', $estimate_date), mask_code($stock_code));
							} else {
								$stock_code = sprintf('%s %s', date('H:i', $estimate_date), mask_code($stock_code));
							}
						}
						$results[] = array(
							'flag' => 1,
							'cls' => $cls,
							'tooltip' => $tooltip,
							'total_registered' => $total_registered,
							'total_unregisted' => $total_unregisted,
							'billing_id' => $val[$clsBilling->pkey],
							'title' => $stock_code,
							'date_f' => date('Y-m-d'),
							'start' => date('Y-m-d H:i:s',$i)
						);
					}
				} else if($type_of_date == '_text'){
					if($agree_date > 0 && date('d/m/Y',$agree_date) == $date){
						$results[] = array(
							'flag' => 1,
							'cls' => 'bg-primary text-white',
							'tooltip' => 'Ngày ký VBTT',
							'billing_id' => $val[$clsBilling->pkey],
							'title' => mask_code($stock_code),
							'date_f' => date('Y-m-d'),
							'start' => date('Y-m-d H:i:s',$i)
						);
					}
				}
			}
		}
	}
	// Return
	echo json_encode($results);
	die();
}
function default_open_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsTemplate = new Template();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$now = time();
	$uid = $clsISO->getUniqid();
	$openFrom = Input::post('openFrom', '_billing');
	$billing_id = (int) Input::post('billing_id', 0);
	###
	$action = '_add';
	$oneBilling = array(
		'partner_id' => 0,
		'product_code' => '',
		'billing_type' => 0,
		'billing_code' => $clsBilling->genCode(),
		'stock_resource' => _AGENCY_FH_ID,
		'contract_status_id' => _CONTRACT_STATUS_WAIT_ID,
		'sold_to_type' => _PTDT_BY_F1_ID,
		'commission' => 0
	);
	$dep_logs = array(
		'head_of_team_id' => 0,
		'head_of_dep_id' => 0,
		'project_director_id' => 0
	);
	$more_information = array(
		'is_sendpale' => 0,
		'is_sendemail' => 0,
		'sale_agency_id' => _AGENCY_FH_ID
	);
	$is_content_changing = $is_edit_content = 0;
	$arr_change_logs = array();
	$list_co_sellers = array(); // Co-sale: sale phụ để prefill (rỗng khi thêm mới)
	if($billing_id > 0){
		$action = '_edit';
		$oneBilling = $clsBilling->getOne($billing_id);
		$more_information = $oneBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$edit_due_date = (int) $core->get_field($more_information, "edit_due_date", 0);
		if($edit_due_date > time()) $is_edit_content = 1; // Được phép sửa
		$is_content_changing = (int) $core->get_field($more_information, "is_content_changing", 0);
		if($is_content_changing == 1){
			$changing_log_id = $core->get_field($more_information, "changing_log_id");
			$change_logs = $core->get_field($more_information, "change_logs", []);
			if(!empty($changing_log_id) && !empty($change_logs) && array_key_exists($changing_log_id, $change_logs)){
				$arr_change_logs = $change_logs[$changing_log_id];
			}
		}
		$dep_logs = $core->get_field($more_information, "dep_logs", array(
			'head_of_dep_id' => 0,
			'head_of_team_id' => 0,
			'project_director_id' => 0
		));
		$lstBlock = $clsProperty->getOItems("_BLOCK",$oneBilling["project_id"]);
		$smarty->assign('lstBlock', $lstBlock);
		// Co-sale: lấy sale phụ (is_primary=0) của GD để prefill form
		$_clsBillingSale = new BillingSale();
		$_allShares = $_clsBillingSale->getByBilling($billing_id);
		if(!empty($_allShares)){
			foreach($_allShares as $_s){
				if((int) $_s['is_primary'] != 1){ $list_co_sellers[] = $_s; }
			}
		}
	}
	# $arr_staffs
	$QueryBuilder = DB::table($clsProfile->tbl);
	$QueryBuilder->select("{$clsProfile->pkey},CONCAT(`code`,'-',`full_name`) as `full_name`,`avatar`,`status_id`", false);
	$QueryBuilder->where("is_trash", 0);
	$QueryBuilder->where("is_active", 1);
	$QueryBuilder->where("status_id", _STATUS_STAFF_OFF_ID, "<>");
	$arr_staffs = $QueryBuilder->get();
	if(!empty($arr_staffs)){
		foreach($arr_staffs as $key => $val){
			$arr_staffs[$key]['image'] = $clsProfile->getAvatar($val[$clsProfile->pkey], $val, 40, 40);
		}
	}
	// $clsISO->print_pre($arr_staffs); die();
	$smarty->assign('action', $action);
	$smarty->assign('openFrom', $openFrom);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('arr_staffs', $arr_staffs);
	$smarty->assign('dep_logs', $dep_logs);
	$smarty->assign('is_edit_content', $is_edit_content);
	$smarty->assign('arr_change_logs', $arr_change_logs);
	$smarty->assign('is_content_changing', $is_content_changing);
	$smarty->assign('list_co_sellers', $list_co_sellers);
	$smarty->assign('co_rows_count', max(3, count($list_co_sellers) + 1)); // đủ dòng cho mọi sale phụ hiện có + 1 dòng trống (tránh mất #4+)
	// Giao dịch cũ chưa có ô "Loại giao dịch" → điền sẵn theo giá trị SUY RA, không mặc định "sale".
	// Không làm vậy thì mở 1 GD của PTĐT rồi bấm Lưu là ghi đè vĩnh viễn thành sale nội bộ,
	// đè mất suy đoán đúng và làm sai bộ vai trò ăn hoa hồng.
	$main_head_of_dep_id = $main_sale_dir_id = 0;
	if($billing_id > 0){
		$clsBillingSaleForm = new BillingSale();
		$_shares_form = $clsBillingSaleForm->getByBilling($billing_id);
		if($core->get_field($more_information, 'deal_type', '') === ''){
			$more_information['deal_type'] = _billing_deal_type($_shares_form, $more_information);
		}
		// TPKD/GĐKD hiển thị ở form là của SALE CHÍNH; chưa gán thì tra theo đúng role trong nhánh phòng ban
		foreach($_shares_form as $_oShareForm){
			if((int) $core->get_field($_oShareForm, 'is_primary', 0) != 1){
				continue;
			}
			$_dep = (int) $core->get_field($_oShareForm, 'department_id', 0);
			$main_head_of_dep_id = (int) $core->get_field($_oShareForm, 'head_of_dep_id', 0);
			$main_sale_dir_id = (int) $core->get_field($_oShareForm, 'sale_dir_id', 0);
			if($main_head_of_dep_id <= 0 || $main_sale_dir_id <= 0){
				$_chain_form = $clsProperty->resolveStaffDepChain($_dep);
				if($main_head_of_dep_id <= 0){
					$main_head_of_dep_id = (int) $core->get_field($_chain_form, 'head_of_dep_id', 0);
				}
				if($main_sale_dir_id <= 0){
					$main_sale_dir_id = (int) $core->get_field($_chain_form, 'regional_director_id', 0);
				}
			}
			break;
		}
	}
	$smarty->assign('main_head_of_dep_id', $main_head_of_dep_id);
	$smarty->assign('main_sale_dir_id', $main_sale_dir_id);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsTemplate', $clsTemplate);
	// Return
	$html = $core->build('_ajax.billing.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_view_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$header_configs;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	###
	$uid = $clsISO->getUniqid();
	$tabfocus  = (int) Input::post('tabfocus', 1);
	$billing_id = (int) Input::post('billing_id', 0);
	$is_dir_project = (int) Input::post('is_dir_project', 0);
	if($billing_id == 0){
		echo '_invalid'; die();
	}
	$oneBilling = $clsBilling->getOne($billing_id);
	$more_information = $oneBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$dep_logs = $core->get_field($more_information, "dep_logs", array(
		'head_of_dep_id' => 0,
		'regional_director_id' => 0,
		'project_director_id' => 0
	));
	$interest_accrued = $core->get_field($more_information, "interest_accrued", []);
	$is_interest_accrued = (int) $core->get_field($more_information, "is_interest_accrued", 0);
	$deposit_paid_to_company = $core->get_field($more_information, "deposit_paid_to_company", []);
	$more_information['dep_logs'] = $dep_logs;
	$oneBilling['is_interest_accrued'] = $is_interest_accrued;
	$oneBilling['interest_accrued'] = $interest_accrued;
	#
	$staff_confirm_id = !empty($more_information["dep_logs"]["project_director_id"]) ? $more_information["dep_logs"]["project_director_id"] : 0;
	$confirm_billing = !empty($header_configs["confirm_billing"]) ? $clsISO->to_array_json($header_configs["confirm_billing"]) : [];
	if($confirm_billing["name"] == "admin") {
		if($confirm_billing["staff_id"] > 0){
			$staff_confirm_id = $confirm_billing["staff_id"];
		}
	}
	
	$arr_change_logs = array();
	$is_content_changing = (int) $core->get_field($more_information, "is_content_changing", 0);
	if($is_content_changing == 1){
		$changing_log_id = $core->get_field($more_information, "changing_log_id");
		$change_logs = $core->get_field($more_information, "change_logs", []);
		if(!empty($changing_log_id) && !empty($change_logs) && array_key_exists($changing_log_id, $change_logs)){
			$arr_change_logs = $change_logs[$changing_log_id];
		}
	}
	$smarty->assign('staff_confirm_id', $staff_confirm_id);
	$smarty->assign('arr_change_logs', $arr_change_logs);
	$smarty->assign('is_content_changing', $is_content_changing);
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('tabfocus', $tabfocus);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('is_dir_project', $is_dir_project);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('deposit_paid_to_company', $deposit_paid_to_company);
	$smarty->assign('clsStock', new Stock());
	$smarty->assign('clsCustomer', new Customer());
	// Return
	$html = $core->build('_ajax.billing.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete_billing(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	// Ép kiểu bắt buộc: deleteOne() nhét thẳng giá trị này vào "DELETE ... WHERE billing_id='$x'",
	// không ép thì một chuỗi kiểu 1' OR '1'='1 xoá sạch bảng giao dịch (MyISAM, không rollback được).
	$billing_id = (int) Input::post('billing_id', 0);
	// Xoá cứng, không có đường lùi → siết hơn huỷ GD: bắt buộc có quyền delete_billing
	if($billing_id <= 0 || !$clsISO->checkPermission('delete_billing')){
		_billing_write_audit($billing_id, 'delete_denied', sprintf('Từ chối xoá GD #%d: tài khoản không đủ quyền', $billing_id));
		echo '_permission'; die();
	}
	###
	$msg = '_error';
	if($clsBilling->deleteOne($billing_id)){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_do_change_confirmed(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$oneProfile,$profile_id,$clsISO,$header_configs;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$tp = Input::post('tp', 'approved');
	$billing_id = (int) Input::post('billing_id', 0);
	if($billing_id > 0){
		$oneBilling = $clsBilling->getOne($billing_id, "`project_id`,`logs`,`more_information`");
		$project_id = (int) $oneBilling['project_id'];
		$action_logs = $oneBilling['logs'];
		$more_information = $oneBilling['more_information'];
		$action_logs = $clsISO->to_array_json($action_logs);
		$more_information = $clsISO->to_array_json($more_information);
		$dep_logs = $core->get_field($more_information, "dep_logs", []);
		$project_director_id = (int) $core->get_field($dep_logs, "project_director_id", 0);
		$is_content_changing = (int) $core->get_field($more_information, "is_content_changing", 0);
		
		#
		$staff_confirm_id = $project_director_id;
		$confirm_billing = !empty($header_configs["confirm_billing"]) ? $header_configs["confirm_billing"] : [];
		if($confirm_billing["name"] == "admin") {
			if($confirm_billing["staff_id"] > 0){
				$staff_confirm_id = $confirm_billing["staff_id"];
			}
		}
		if($is_content_changing == 1){
			$more = array();
			$changing_log_id = $core->get_field($more_information, "changing_log_id");
			$change_logs = $core->get_field($more_information, "change_logs", []);
			if(!empty($changing_log_id) && !empty($change_logs) && array_key_exists($changing_log_id, $change_logs)){
				$change_log_arrs = $change_logs[$changing_log_id];
				$content_change = $core->get_field($change_log_arrs, "content_change", []);
				if($tp == 'approved' && !empty($content_change)){
					$action_name = "đồng ý";
					$more_information['is_content_changed'] = 1;
					$more_information['is_content_changing'] = 0;
					foreach($content_change as $p_field => $p_value){
						if($p_field == 'stock_code'){
							$stock_id = $building_id = $stock_type = $bedroom_id = $type_id = $home_direction_id = $investor_id = 0;
							if(!empty($p_value) && $project_id > 0){
								$field = "`t1`.{$clsStock->pkey},`t1`.`block_id`,`t1`.`building_id`,`t1`.`stock_type`
									,`t1`.`bedroom_id`,`t1`.`type_id`,`t1`.`home_direction_id`,`t2`.`more_information`";
								$oStock = $dbconn->getRow("SELECT {$field} FROM {$clsStock->tbl} AS `t1` 
									LEFT JOIN {$clsProperty->tbl} AS `t2` ON `t1`.`block_id`=`t2`.`property_id` 
									WHERE `t1`.`ms_code`='{$p_value}' AND `t1`.`project_id`={$project_id}");
								if(!empty($oStock)){
									$more_info = $oStock['more_information'];
									$more_info = $clsISO->to_array_json($more_info);
									$investor_id = (int) $core->get_field($more_info, "investor_id", 0);
									$stock_id = $oStock[$clsStock->pkey]; // Phân khu
									$block_id = $oStock['block_id']; // Toà nhà
									$building_id = $oStock['building_id']; // Toà nhà
									$stock_type = $oStock['stock_type'];
									$bedroom_id = $oStock['bedroom_id'];
									$type_id = $oStock['type_id'];
									$home_direction_id = $oStock['home_direction_id'];
								}
							}
							$more["investor_id"] = $investor_id;
							$more_information['type_id'] = $type_id;
							$more_information['stock_id'] = $stock_id;
							$more_information['block_id'] = $block_id;
							$more_information['building_id'] = $building_id;
							$more_information['stock_type'] = $stock_type;
							$more_information['bedroom_id'] = $bedroom_id;
							$more_information['home_direction_id'] = $home_direction_id;
						} else if ($p_field == 'staff_id') {
							$dep_logs = $core->get_field($change_log_arrs, "dep_logs", $dep_logs);
							$dep_logs['project_director_id'] = $project_director_id;
							// Chuỗi phụ trách theo cây phòng ban của người bán mới (leo trọn chuỗi; tài khoản PTĐT cũng resolve theo phòng)
							$oProfile = $clsProfile->getProfile($p_value);
							$_staff_dept_id = (int) $core->get_field($oProfile, "department_id", 0);
							$_dep_chain = $clsProperty->resolveStaffDepChain($_staff_dept_id);
							$dep_logs['head_of_dep_id'] = $_dep_chain['head_of_dep_id'];
							$dep_logs['regional_director_id'] = $_dep_chain['regional_director_id'];
							$dep_logs['region_id'] = $_dep_chain['region_id'];
							$more['regional_id'] = (int) $_dep_chain['region_id'];
							$more['department_id'] = $_staff_dept_id;
							$more_information['dep_logs'] = $dep_logs;
						} else if($p_field == 'totalgrand'){
							$p_value = $clsISO->processSmartNumber($p_value);
						}
						if($p_field != "dep_logs") {
							$more[$p_field] = $p_value;
						}
					}
				} else if($tp == 'rejected') {
					$action_name = "từ chối";
					$more_information['is_content_changed'] = 1;
					$more_information['is_content_changing'] = 0;
				}
				$ii = 0;
				$content_log = sprintf("<strong>%s</strong> đã <strong>%s</strong> thay đổi", $clsProfile->getFullName($profile_id, $oneProfile), $action_name);
				foreach($content_change as $p_field => $p_value){
					if($p_field == 'staff_id'){
						$p_value = $clsProfile->getFullName($p_value);
					} else if($p_field == 'totalgrand'){
						$p_value = $clsISO->formatPrice($p_value);
					} else if($p_field == 'dep_logs') {
						continue;
					}
					$content_log.= ($ii==0 ? " ": ", ") . sprintf('%s: %s', $clsBilling->getFieldName($p_field), $p_value);
					++$ii;
				}
				$content_log.= sprintf(" vào lúc <strong>%s</strong>", date('d/m/Y h:i:s'));
				$action_logs[$clsISO->getUniqid()] = array(
					'reg_date' => time(),
					'content' => $content_log,
					'profile_id' => $profile_id		
				);
				$change_logs[$changing_log_id]['status'] = $tp;
				$more_information['change_logs'] = $change_logs;	
				// $clsISO->print_pre($project_director_id); die();
				if($clsBilling->updateOne($billing_id, array_merge($more, array(
					'logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				)))){
					$msg = "_success";
					if($staff_confirm_id > 0){
						$more_information = $clsProfile->getOneField("more_information", $staff_confirm_id);
						$more_information = $clsISO->to_array_json($more_information);
						$billing_changing_confirms = $core->get_field($more_information, "billing_changing_confirms", []);
						if(!empty($billing_changing_confirms) && in_array($billing_id, $billing_changing_confirms)){
							$index = array_search($billing_id, $billing_changing_confirms);
							unset($billing_changing_confirms[$index]);
							$more_information['billing_changing_confirms'] = $billing_changing_confirms;
							$clsProfile->updateOne($staff_confirm_id, array(
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
							));
						}
					}
				}
			}
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_upd_action_visible(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$oneProfile,$profile_id,$clsISO;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$is_action_visible = Input::post('is_action_visible', 0);
	$more_information = $core->get_field($oneProfile, "more_information", []);
	$more_information['billing_configs']["is_action_visible"] = $is_action_visible;
	#
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_cancel_billing(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$clsProfile;
	$clsBilling = new Billing();
	$billing_id = (int) Input::post('billing_id', 0);
	// Huỷ GD gỡ doanh số khỏi mọi báo cáo → siết hơn sửa: sale không tự huỷ được GD của mình
	if(!_billing_can_cancel($billing_id)){
		_billing_write_audit($billing_id, 'cancel_denied', sprintf('Từ chối huỷ GD #%d: tài khoản không đủ quyền', $billing_id));
		echo '_permission'; die();
	}
	###
	$oneBilling = $clsBilling->getOne($billing_id, "`is_cancel`,`staff_id`,`logs`");
	if($oneBilling['is_cancel']==1){
		echo '_invalid'; 
		die();
	} else {
		$msg = '_error';
		$logs = $oneBilling['logs'];
		$logs = $clsISO->to_array_json($logs);
		$contentLog = sprintf('<strong>%s</strong> Đã huỷ giao dịch này', $clsProfile->getFullName($profile_id, $oneProfile));
		$logs[$clsISO->getUniqid()] = array(
			'title' => 'Huỷ giao dịch',
			'content' => $contentLog,
			'profile_id' => $profile_id,
			'reg_date' => time()
		);
		if($clsBilling->updateOne($billing_id, array(
			'is_cancel' => 1,
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			$clsFPoint = new FPoint();
			$clsFPoint->cancel_billing_LPoint($billing_id, $oneBilling);
			/** Cancel commission — theo billing_id. Hàm cũ sync_commission() tra theo
			    more_information['commission_id'], mà khoá đó chưa bao giờ được ghi, nên
			    updateOne(null,…) trả về ngay: giao dịch huỷ rồi vẫn nằm trong sổ phải trả. */
			$clsCommissionEntry = new CommissionEntry();
			$clsCommissionEntry->cancelByBilling($billing_id, $profile_id);
			// Doanh số thi đua phải về 0, không thì giao dịch đã huỷ vẫn cộng vào bảng xếp hạng
			$clsBilling->updateOne($billing_id, array(
				'realized_sales_compete' => 0,
				'realized_sales_commission' => 0
			));
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Billing","cancel");
		}
		// Return
		echo $msg; die();
	}
}
function default_open_activity(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG');
	$billing_id = (int) Input::post('billing_id', 0);
	$oBilling = $clsBilling->getOne($billing_id);
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information, "more_information");
	if($holderG == 'deposit_paid'){
		$titlePage = '10% đóng vào công ty';
		$deposit_paid_to_company = $core->get_field($more_information, "deposit_paid_to_company", []);
		if(!$deposit_paid_to_company){
			$deposit_paid_to_company = array(
				'action_date' => time(),
				'payer_name' => "",
				'deposit_paid_amount' => 0,
				'trans_code' => '',
				'notes' => '',
			);
		}
		$smarty->assign('deposit_paid_to_company', $deposit_paid_to_company);
	} else {
		$loop_arrs = array(
			'first_interest' => 'Lãi phát sinh L1',
			'second_interest' => 'Lãi phát sinh L2'
		);
		$titlePage = 'lãi phát sinh';
		$interest_accrued = $core->get_field($more_information, "interest_accrued", []);
		$is_interest_accrued = (int) $core->get_field($more_information, "is_interest_accrued", 0);
		if(!$interest_accrued){
			foreach($interest_accrued as $key => $val){
				$interest_accrued[$key] = array(
					'amount' => 0,
					'accrual_date' => "",
					'notes' => "",
					'is_completed' => 0
				);
			}
		}
		$smarty->assign('loop_arrs', $loop_arrs);
		$smarty->assign('interest_accrued', $interest_accrued);
		$smarty->assign('is_interest_accrued', $is_interest_accrued);
	}
	$smarty->assign('action', $action);
	$smarty->assign('holderG', $holderG);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('titlePage', sprintf('%s %s', $titlePage, $oBilling['stock_code']));
	// Return
	$smarty->assign('uid', $uid);
	$smarty->assign('template_type', '_deposit_paid');
	$html = $core->build('_ajax.sync_sold.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_activity(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$msg = "_error";
	$holderG = Input::post('holderG');
	$billing_id = (int) Input::post('billing_id', 0);
	if($billing_id > 0){
		if($holderG == 'deposit_paid'){
			$action_date = Input::post('action_date');
			$action_time = !empty($action_date) ? $clsISO->toTime($action_date) : 0;
			$payer_name = Input::post('payer_name');
			$deposit_paid_amount = Input::post('deposit_paid_amount');
			$trans_code = Input::post('trans_code');
			$notes = Input::post('notes');
			$oBilling = $clsBilling->getOne($billing_id, "more_information,logs");
			$more_information = $oBilling['more_information'];
			$logs = $oBilling['logs'];
			$more_information = $clsISO->to_array_json($more_information);
			$logs = $clsISO->to_array_json($logs);
			$logs[$clsISO->getUniqid()] = array(
				'profile_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật đóng 10&#37; vào công ty vào ngày <strong>%s</strong>, 
					Số tiền: <strong>%s</strong>, Họ & tên KH/UNC: <strong>%s</strong>, Mã FT: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $deposit_paid_amount, $payer_name, $trans_code)
			);
			$more_information['deposit_paid_to_company'] = array(
				'action_date' => $action_time,
				'payer_name' => $payer_name,
				'deposit_paid_amount' => $clsISO->processSmartNumber($deposit_paid_amount),
				'trans_code' => $trans_code,
				'notes' => $notes
			);
			if($clsBilling->updateOne($billing_id, array(
				'is_deposit_paid' => 1,
				'deposit_paid_amount' => $clsISO->processSmartNumber($deposit_paid_amount),
				'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
		} else {
			$oBilling = $clsBilling->getOne($billing_id, "`more_information`,`logs`");
			$more_information = $oBilling['more_information'];
			$action_logs = $oBilling['logs'];
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $clsISO->to_array_json($action_logs);
			#
			$is_interest_accrued = $is_interest_completed = 0;
			$interest_accrued = Input::post('interest_accrued', []);
			if(!empty($interest_accrued)){
				foreach($interest_accrued as $val){
					if((int) $val['status'] == 1){
						$is_interest_accrued = 1; // Có lãi
					}
					if((int) $val['status'] == 1 && (int) $val['is_completed'] == 0)
						$is_interest_completed = 1;
				}
			}
			$more_information['interest_accrued'] = $interest_accrued;
			$more_information['is_interest_accrued'] = $is_interest_accrued;
			$more_information['is_interest_completed'] = $is_interest_completed;
			// $clsISO->print_pre($more_information); die();
			if($clsBilling->updateOne($billing_id, array(
				// 'logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_add_info(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$uid = $clsISO->getUniqid();
	$billing_id = Input::post('billing_id', 0);
	$oBilling = $clsBilling->getOne($billing_id);
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oBilling', $oBilling);
	$smarty->assign('more_information', $more_information);	
	// Return
	$smarty->assign('template_type', '_add_info');
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.sync_sold.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_info_ccid(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$fileName = "";
	$curl = curl_init();
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$finfo = finfo_file($finfo, $fileName);
	$cFile = curl_file_create($fileName, $finfo, basename($fileName));
	$data = array("image" => $cFile, "filename" => $cFile->postname);
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.fpt.ai/vision/idr/vnm",
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_HTTPHEADER => array(
			"api-key: 45g4Pj66AJDWOEjkfAkkazIzI4bcZG3g"
		),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
}
function default_load_billing_sold(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$billing_id = Input::post('billing_id', 0);
	$field = "more_information,contract_date,estimate_date";
	$oneBilling = $clsBilling->getOne($billing_id, $field);
	$more_information = $clsBilling->getOneField('more_information', $billing_id);
	$more_information = $clsISO->to_array_json($more_information);
	if(!empty($oneBilling['contract_date'])){
		$contract_date = $oneBilling['contract_date'];
		$estimate_date = $oneBilling['estimate_date'];
		$oneBilling['contract_date'] = date('Y-m-d', $contract_date);
		$oneBilling['estimate_date'] = date('Y-m-d\TH:i', $estimate_date);
	}
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('more_information', $more_information);	
	// Return
	$smarty->assign('template_type', '_info');
	$html = $core->build('_ajax.sync_sold.tpl');
	echo $html; die();
}
function default_upd_billing_sold(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$smarty;
	$clsClient = new Client();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	#
	$msg = "_error";
	$billing_id = (int) Input::post('billing_id', 0);
	$contract_date = Input::post('contract_date');
	$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
	$customer_name = Input::post('customer_name');
	$customer_phone = Input::post('customer_phone');
	$customer_email = Input::post('customer_email');
	$customer_address = Input::post('permanent_address');
	$contact_address = Input::post('contact_address');
	$rescheduling_reason = Input::post('rescheduling_reason');
	#
	$oBilling = $clsBilling->getOne($billing_id);
	$staff_id = $oBilling['staff_id'];
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$logs = $oBilling['logs'];
	$logs = $clsISO->to_array_json($logs);
	$more_information['contract_date'] = $contract_date;
	$more_information['customer_name'] = $customer_name;
	$more_information['customer_phone'] = $customer_phone;
	$more_information['customer_email'] = $customer_email;
	$more_information['contact_address'] = $contact_address;
	$more_information['permanent_address'] = $customer_address;
	$more_information['identity_card'] = Input::post('identity_card');
	$more_information['issuance_date'] = Input::post('issuance_date');
	$more_information['issuance_location'] = Input::post('issuance_location');
	$more_information['billing_method'] = Input::post('billing_method', 0);
	$more_information['bank_guarantee_id'] = Input::post('bank_guarantee_id');
	#
	$_uid = Input::post('uid');
	$totalgrand = Input::post('totalgrand');
	$is_ignore_confirmed = (int)Input::post('is_ignore_confirmed',0);
	$is_confirmed = false;
	if($oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)){
		$is_confirmed = true;
	}
	if($is_confirmed == true && $is_ignore_confirmed == 0){
		$arr_fields_change = array();
		$is_change_amount = ($oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)) ? 1 : 0;
		if($is_change_amount) $arr_fields_change['totalgrand'] = $clsISO->processSmartNumber($totalgrand);
		$smarty->assign('_uid', $_uid);
		$smarty->assign('arr_fields_change', $arr_fields_change);
		$smarty->assign('billing_id', $billing_id);
		// Return
		$html = $core->build('_ajax.billing_confirm.tpl');
		echo json_encode(array(
			'msg' => '_confirm_changed',
			'billing_id' => $billing_id,
			'uid' => $clsISO->getUniqid(),
			'html' => $html
		)); die();
	} 
	if($totalgrand > 0 && $is_confirmed == false && $oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)){
		$content = sprintf('<strong>%s</strong> thay đổi %s', $clsProfile->getFullName($profile_id, $oneProfile), 'tổng tiền: '.$totalgrand);
		$logs[$clsISO->getUniqid()] = array(
			'profile_id' => $profile_id,
			'reg_date' => time(),
			'content' => $content
		);
	}
	if(!empty($rescheduling_reason)) {
		$more_information["rescheduling_reason"][$clsISO->getUniqid()] = [
			"user_id"	=>	$profile_id,
			"note"	=>	addslashes($rescheduling_reason),
			"reg_date"	=> time()
		];
	}
	if($clsBilling->updateOne($billing_id, array(
		'upd_date' => time(),
		'contract_date' => $contract_date,
		'billing_method' => Input::post('billing_method'),
		'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
		//add data customer
		if(!empty($customer_name) && !empty($customer_phone)){
			if($staff_id != _PROFILE_PARTNER_ID){
				$tmp = $clsCustomer->getByCond("`admin_id`='{$staff_id}' AND `phone`='{$customer_phone}'", $clsCustomer->pkey);
				if(!empty($tmp)){
					$customer_id = $tmp[$clsCustomer->pkey];
					$clsBilling->updateOne($billing_id, array('customer_id' => $customer_id));
				} else {
					$customer_id = $clsCustomer->getMaxId();
					$clsCustomer->insert(array(
						$clsCustomer->pkey => $customer_id,
						'name' => $customer_name,
						'name_slug' => $core->replaceSpace($customer_name),
						'phone' => $customer_phone,
						'address' => $customer_address,
						'status_id' =>  _CRM_STATUS_CHOT_ID,
						'admin_id' => $staff_id,
						'reg_date' => time(),
						'upd_date' => time(),
						'user_id' => $profile_id,
						'user_id_update' => $profile_id
					));
					$clsBilling->updateOne($billing_id, array('customer_id' => $customer_id));
				}
			}
			$tmp = $clsClient->getByCond("`billing_id` LIKE '%|{$billing_id}|%'");
			if(!empty($tmp)){
				$email = $core->get_field($more_information, "customer_email", $tmp["email"]);
				$phone = $core->get_field($more_information, "customer_phone", $tmp["phone"]);
				$full_name = $core->get_field($more_information, "customer_name", $tmp["full_name"]);
				$address = $core->get_field($more_information, "permanent_address", $tmp["address"]);
				$contact_address = $core->get_field($more_information, "contact_address", $tmp["contact_address"]);
				$identity_card = $core->get_field($more_information, "identity_card", $tmp["identity_card"]);
				$issuance_date = $core->get_field($more_information, "issuance_date", "");
				$issuance_date = ($issuance_date != "") ? strtotime(str_replace("/","-",$issuance_date)) : $tmp["issuance_date"];
				$issuance_location = $core->get_field($more_information, "issuance_location", $tmp["issuance_location"]);
				$clsClient->updateOne($tmp['client_id'], array(
					"email"				=>	$email,
					"phone"				=>	$phone,
					"full_name"			=>	$full_name,
					"slug"				=>	$clsISO->make_slug($full_name),
					"address"			=>	$address,
					"contact_address"	=>	$contact_address,
					"identity_card"		=>	$identity_card,
					"issuance_date"		=>	$issuance_date,
					"issuance_location"	=>	$issuance_location,
				));
			}else{
				$clsClient->insert(array(
					"client_id"			=>	$clsClient->getMaxId(),
					"full_name"			=>	$more_information['customer_name'],
					"slug"				=>	$clsISO->make_slug($more_information['customer_name']),
					"email"				=>	$more_information['customer_email'],
					"phone"				=>	$more_information['customer_phone'],
					"address"			=>	$more_information['permanent_address'],
					"contact_address"	=>	$more_information['contact_address'],
					"identity_card"		=>	$more_information['identity_card'],
					"issuance_date"		=>	$more_information['issuance_date'],
					"issuance_location"	=>	$more_information['issuance_location'],
					"billing_id"		=>	"|".$billing_id."|",
					"staff_id"			=>	"|".$staff_id."|",
					"reg_date"			=>	time(),
				));
			}
		}		
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Billing","update");
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $msg
	)); die();
}
function default_upload_sp_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$profile_id;
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$to_field = Input::post('to_field');
	$billing_code = Input::post('billing_code');
	$billing_id = (int) Input::post('billing_id', 0);
	if(isset($_POST['hid']) && $_POST['hid'] == 'upload'){
		if(!empty($_FILES['upload_file']['name'])){
			$person_info = array();
			if(@is_uploaded_file($_FILES['upload_file']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($_FILES["upload_file"],"/GD",EXTENSION_FILE_UPLOAD);
				$file_name = $_FILES['upload_file']['name'];
				$file_size = $_FILES['upload_file']['size'];
				// Upload file to google drive
				if($to_field == "image_poster") {
					$folder_id = GOOGLE_DRIVE_FOLDER_HONOR_ID;
					$clsGoogleUpload = new GoogleUpload($folder_id, true);
					$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
				}else{
					$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
					$folder_id = $clsGoogleUpload->create_folder($billing_code);
					$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
				}
				$uploaded_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				$uploaded_url = $clsISO->genGoogleURL($createdFile->getId());
				@unlink(ROOTPATH . $upload_file);
				// Update to DB
				if($billing_id > 0 && (!in_array($to_field, array('sales_dir_agree_image', 'sale_agree_image')))){
					$oneBilling = $clsBilling->getOne($billing_id, "billing_code,more_information");
					$billing_code = $oneBilling['billing_code'];
					$more_information = $oneBilling['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information[$to_field] = $uploaded_file;
					if($clsBilling->updateOne($billing_id, array(
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						$msg = '_success|||';
						if($to_field=='ccid_front' || $to_field=='ccid_back'){
							$msg.= '<img src="'.$uploaded_url.'" class="w-100 h-100 rounded-1" />|||'.implode('|||',$person_info);
						} else if($to_field=='sale_policy_file' || $to_field == 'capture_confirm_file' || $to_field == 'table_bonus_file'){
							$msg.= '<a class="download" data-fancybox="true" target="_blank" href="'.$uploaded_url.'">'.$uploaded_file.'</a>';
						}
					}
				} else if(in_array($to_field, array('sales_dir_agree_image', 'sale_agree_image'))){
					$msg = '_success|||
						<input type="hidden" value="'.$uploaded_file.'" name="'.$to_field.'" />
						<img src="'.$uploaded_url.'" class="w-100 h-100 rounded-1" />';
				} else {
					$msg = '_success|||
						<input type="hidden" value="'.$uploaded_file.'" name="'.$to_field.'" />
						<a class="download" data-fancybox="true" href="'.$uploaded_url.'">
							'.$clsISO->formatFileName($uploaded_file,10 ).'
						</a>';
				}
				if($to_field=='image_poster') {
					$msg = '_success|||
						<input type="hidden" value="'.$uploaded_file.'" name="'.$to_field.'" />
						<a class="download w-100 limit_1line text-nowrap" data-fancybox="true" href="'.$uploaded_url.'">
							'.$clsISO->formatFileName($uploaded_file,10 ).'
						</a>';
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_open_sp_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	##
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$toId = Input::post('toId');
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', 0);
	$titlePage = 'Uỷ nhiệm chi';
	if($p_field=='price_sheet_file'){
		$titlePage = 'File PTG';
	} else if($p_field=='residence_info'){
		$titlePage = 'Xác nhận cư trú';
	} else if($p_field=='contract_files'){
		$titlePage = 'Hợp đồng ký';
	}
	##
	$oBilling = $clsBilling->getOne($p_id, "more_information");
	$more_information = $oBilling['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$list_items = isset($more_information[$p_field]) 
		? $more_information[$p_field] : array();
	$smarty->assign('list_items', $list_items);
	##
	$smarty->assign('uid', $uid);
	$smarty->assign('toId', $toId);
	$smarty->assign('p_id', $p_id);
	$smarty->assign('p_field', $p_field);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.sync_sold.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_add_sp_file(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$deviceType;
	###
	$toId = Input::post('toId');
	$p_field = Input::post('p_field');
	$p_id = (int) Input::post('p_id', 0);
	$uid = $clsISO->getUniqid();
	$html = '<tr class="tr_'.$p_field.'_'.$toId.' tr_'.$p_field.'_'.$uid.'">
		<td class="text-left">
			<input type="text" placeholder="Nhập tên..." name="'.$p_field.'['.$uid.'][title]" 
			class="form-control required" maxlength="255" />
		</td>
		<td class="text-left">
			<div class="input-group">
				<input type="text" p_id="'.$p_id.'" p_field="'.$p_field.'" uid="'.$uid.'" 
				placeholder="Nhập ảnh..." name="'.$p_field.'['.$uid.'][image]" 
				class="form-control required '.$p_field.'_'.$uid.'" onPaste="$Core.billing.upload_clipboard(this, event)" maxlength="255" />
				<button type="button" toId="select_image_'.$toId.'" uid="'.$uid.'" onClick="$Core.billing.select_image(this, event)" p_id="'.$p_id.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload').'</button>
			</div>
		</td>
		<td class="text-center">
			<button type="button" onClick="$Core.billing.delete_sp_file(this, event)" uid="'.$uid.'" p_field="'.$p_field.'" 
			p_id="'.$p_id.'" class="btn btn-sm btn-icon btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt').'</button>
		</td>
	</tr>';
	// return
	echo $html; die();
}
function default_save_sp_file(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	###
	$msg = "_error";
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', 'payment_order');
	if($p_id > 0 && in_array($p_field, ['payment_order', 'price_sheet_file','residence_info','contract_files'])){
		$oBilling = $clsBilling->getOne($p_id, "more_information");
		$more_information = $oBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information[$p_field] = Input::post($p_field);
		###
		if($clsBilling->updateOne($p_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success|||";
			if(isset($more_information[$p_field]) && !empty($more_information[$p_field])){
				foreach($more_information[$p_field] as $key => $val){
					$msg.= '<a class="download" data-fancybox="true" href="'.$clsISO->getGoogleUrl($val['image']).'">
						'.$clsISO->makeIcon('bx-download',$val['title']).'
					<a/>';
				}
			} else {
				$msg.= '<span class="text-muted fs-12">Chưa có File</span>';
			}
		}
	}
	// return
	echo $msg; die();
}
function default_save_quick_notes(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$quick_notes = Input::post('quick_notes');
	$billing_id = (int) Input::post('billing_id', 0);
	$more_information = $clsBilling->getOneField('more_information', $billing_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) 
		: array();
	$more_information['quick_notes'] = $quick_notes;
	if($clsBilling->updateOne($billing_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_load_edit_inline_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action','_open');
	###
	$more_information = $clsBilling->getOneField('more_information', $p_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	###
	if($p_action=='_save'){
		// Đường ghi tắt này trước đây không gate và nhận p_field tuỳ ý → POST tay là sửa được
		// commission_value/total_deduction của mọi GD, đi vòng qua gate của form Lưu giao dịch.
		if(!_billing_can_save($p_id) || !in_array($p_field, _billing_inline_editable_fields())){
			_billing_write_audit($p_id, 'save_denied', sprintf('Từ chối sửa nhanh GD #%d, ô "%s"', $p_id, $p_field));
			echo '_permission'; die();
		}
		$p_value = Input::post('p_value');
		//$clsISO->print_pre($p_value); die();
		$more_information[$p_field] = $p_value;
		$clsBilling->updateOne($p_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
	}
	if($p_field=='notes'){
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= isset($more_information[$p_field]) && !empty($more_information[$p_field]) ? $more_information[$p_field] : "";
			$html.='<a class="editInlineField" onClick="$Core.billing.edit_inline_field(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<textarea cols="255" rows="2" class="form-control edit_profile_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'">'.$more_information[$p_field].'</textarea>';
		}
	} else if(in_array($p_field, ['sale_date','sale_policy_date'])){
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= isset($more_information[$p_field]) && !empty($more_information[$p_field]) ? $more_information[$p_field] : "";
			$html.='<a class="editInlineField" onClick="$Core.billing.edit_inline_field(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<div class="input-group-date">
				<input class="form-control isodatepicker no-border-right edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yyyy" />
			</div>';
		}
	} else {
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= isset($more_information[$p_field]) && !empty($more_information[$p_field]) ? $more_information[$p_field] : "";
			$html.='<a class="editInlineField" onClick="$Core.billing.edit_inline_field(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$placeholder = "";
			if($p_field=='customer_name') $placeholder = "Nhập tên khách hàng";
			if($p_field=='customer_phone') $placeholder = "Nhập tên điện thoại khách hàng";
			if($p_field=='customer_email') $placeholder = "Nhập tên e-mail khách hàng";
			if($p_field=='stock_resource') $placeholder = "Nguồn căn";
			if($p_field=='commission') $placeholder = "Hoa hồng";
			$html_input = '<input class="form-control edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="'.$placeholder.'" />';
		}
	}
	if($p_action=='_cancel' || $p_action=='_save'){
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container w-px-300">
			'.$html_input.'
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.billing.save_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.billing.cancel_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_autosave_inline_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field');
	// Cùng lỗ hổng với load_edit_inline_field: không gate + p_field tuỳ ý
	if(!_billing_can_save($p_id) || !in_array($p_field, _billing_inline_editable_fields())){
		_billing_write_audit($p_id, 'save_denied', sprintf('Từ chối lưu nhanh GD #%d, ô "%s"', $p_id, $p_field));
		echo '_permission'; die();
	}
	$p_value = Input::post('p_value');
	//$clsISO->print_pre($p_value); die();
	$more = array();
	$more_information = $clsBilling->getOneField('more_information', $p_id);
	$more_information = $clsISO->to_array_json($more_information);
	if(in_array($p_field, array('estimate_date','contract_date'))){
		$p_value = !empty($p_value) ? $clsISO->toTime($p_value) : 0;
		$more[$p_field] = $p_value;
	} else {
		$more_information[$p_field] = $p_value;
	}
	// $clsBilling->setDebug(true);
	if($clsBilling->updateOne($p_id, array_merge($more, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	)))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_list_logs(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsBilling = new Billing();
	$billing_id = Input::post('billing_id', 0);
	###
	$logs = $clsBilling->getOneField('logs', $billing_id);
	$list_logs = !empty($logs) ? @json_decode(html_entity_decode($logs), true) : array();
	$html = '';
	if(!empty($list_logs)){
		$html.= '<ul class="logs">';
		foreach($list_logs as $key => $val){
			$html.= '<li>'.$clsISO->convertTimeToText($val['reg_date'], true).": ".$val['content'].'</li>';
		}
		$html.= '<ul>';
	} else {
		$html .= '<div class="p-4 text-center">
			<img src="https://cdn-icons-png.flaticon.com/512/833/833602.png" width="50px" />
			<p class="text-muted mt-2">Chưa có lịch sử giao dịch</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_holder_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$project_id = Input::post('project_id', 0);
	$html = '<select class="iso-selectizeLiveSearch required w-100" placeholder="Tìm căn hộ" data-url="'.PCMS_URL.'/index.php?mod=home&act=load_stock_search&project_id='.$project_id.'" name="stock_id" data-optgroup="false"></select>';
	// Return
	echo $html; die();
}
function default_load_stock_search(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsStock = new Stock();
	$project_id = (int) Input::get('project_id', 0);
	$keysearch = Input::post('keysearch', "");
	####
	$cond = "`is_trash`=0";
	if($project_id > 0){
		$cond.= "  and `project_id`='{$project_id}'";
	}
	####
	$field = "{$clsStock->pkey},`ms_code`";
	$list_stocks = $clsStock->getAll("{$cond} and (`ms_code` like '%{$keysearch}%' or REPLACE(`ms_code`,'.','')='{$keysearch}')", $field);
	###
	$results = array();
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$results[] = array(
				'id' => $val[$clsStock->pkey],
				'text' => $val['ms_code']
			);
		}
	}
	// Return
	echo json_encode($results); 
	die();
}
function default_get_head_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$head_of_team_id = $head_of_dep_id = $regional_id = 0;
	$regional_director_id = $department_id = 0;
	$staff_id = (int) Input::post('staff_id', 0);
	$oneStaff = $clsProfile->getOne($staff_id, "`department_id`");
	if(!empty($oneStaff)){
		$msg = "_success";
		$department_id = (int) $oneStaff['department_id'];
		// Cùng resolver với lúc lưu GD — form hiển thị đúng cái server sẽ ghi
		$_dep_chain = $clsProperty->resolveStaffDepChain($department_id);
		$regional_id = (int) $_dep_chain['region_id'];
		$regional_director_id = (int) $_dep_chain['regional_director_id'];
		$head_of_dep_id = (int) $_dep_chain['head_of_dep_id'];
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'regional_id' => $regional_id,
		'department_id' => $department_id,
		'regional_director_id' => $regional_director_id,
		'head_of_dep_id' => $head_of_dep_id
	)); die();
}
function default_loadProjectDirector(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$head_of_team_id = $head_of_dep_id = 0;
	$block_id = (int) Input::post('block_id', 0);
	$oneBlock = $clsProperty->getArraySearchByKey("_BLOCK", $block_id);
	#	
	$project_director_id = 0;
	if(!empty($oneBlock)){
		$msg = "_success";
		$more_information = $oneBlock['more_information'];
		$project_director_id = $core->get_field($more_information, 'project_manager', 0);
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'project_director_id' => $project_director_id,
	)); die();
}
function default_get_project_dir_by_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$msg = "_error"; $project_director_id = 0;
	$stock_code = Input::post('stock_code');
	$project_id = (int) Input::post('project_id', 0);
	if(!empty($stock_code) && $project_id > 0){
		$msg = "_success";
		$field = "{$clsStock->pkey},`block_id`,`building_id`";
		$oStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `ms_code`='{$stock_code}'", $field);
		$block_id = $oStock['block_id']; // Phân khu
		$building_id = $oStock['building_id']; // Toà nhà
		$oneBlock = $clsProperty->getOne($block_id, "`parent_id`,`more_information`");
		if($oneBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE){
			$project_director_id = _PROFILE_PVD_ID;
		} else {
			$more_information = $oneBlock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$project_director_id = $core->get_field($more_information, 'project_manager', 0);
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'project_director_id' => $project_director_id
	)); die();
}
function _save_billing_shares($billing_id, $staff_id, $department_id, $regional_id, $totalgrand,$total_commission_value=0){
	global $core, $clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsBillingSale = new BillingSale();
	$billing_id = (int) $billing_id;
	$staff_id = (int) $staff_id;
	// Sale phụ đọc theo HÀNG (co_sale[i][staff_id] + co_sale[i][ratio]) → staff↔ratio luôn đúng cặp, miễn nhiễm dòng rỗng/ẩn
	$co_sale = Input::post('co_sale', array());
	if(!is_array($co_sale)){ $co_sale = array(); }
	// Gom sale phụ hợp lệ: bỏ rỗng, bỏ trùng sale chính, %<=0; mỗi sale 1 dòng
	$co_rows = array();
	$sum_co = 0;
	foreach($co_sale as $_row){
		if(!is_array($_row)){ continue; }
		$cid = (int) $core->get_field($_row, 'staff_id', 0);
		$ratio = (float) $core->get_field($_row, 'ratio', 0);
		// Có % nhưng không chọn được người → BỎ CẢ LẦN GHI. Nếu chỉ bỏ dòng này thì % của nó
		// âm thầm dồn sang sale chính (main_ratio = 100 − Σco), doanh số nhảy mà không ai báo.
		if($ratio > 0 && $cid <= 0){
			_billing_write_audit($billing_id, 'share_reject', sprintf('Bỏ qua ghi phân bổ GD #%d: có dòng sale phụ %s%% chưa chọn nhân viên', $billing_id, $ratio));
			return 0;
		}
		if($cid <= 0 || $cid == $staff_id){ continue; }
		if($ratio <= 0 || isset($co_rows[$cid])){ continue; }
		$co_rows[$cid] = $ratio;
		$sum_co += $ratio;
	}
	// Σ sale phụ vượt 100 thì sale chính âm. Bắt riêng ở đây vì bất biến Σratio cuối hàm KHÔNG bắt được:
	// main_ratio = 100 − Σco nên tổng luôn ra đúng 100 dù từng dòng vô lý.
	if($sum_co > 100){
		_billing_write_audit($billing_id, 'share_reject', sprintf('Bỏ qua ghi phân bổ GD #%d: tổng %% sale phụ = %s vượt 100', $billing_id, $sum_co));
		return 0;
	}
	// saveShares() XOÁ sạch rồi chèn lại, mà nó chỉ chèn các cột doanh số. Không gánh sang thì
	// mỗi lần lưu form giao dịch là mất hết vai trò/CTV/% hoa hồng đã gán ở màn Quyết toán —
	// mất im lặng, không log. Giữ theo staff_id vì billing_sale_id được cấp mới sau khi xoá.
	$keep_cols = array('head_of_dep_id', 'sale_dir_id', 'ctv_name', 'ctv_rate', 'ctv_amount', 'sales_commission_rate');
	$keep_by_staff = array();
	foreach($clsBillingSale->getByBilling($billing_id) as $_oOld){
		$_sid = (int) $core->get_field($_oOld, 'staff_id', 0);
		if($_sid <= 0){
			continue;
		}
		$_carry = array();
		foreach($keep_cols as $_col){
			if(isset($_oOld[$_col])){
				$_carry[$_col] = $_oOld[$_col];
			}
		}
		$keep_by_staff[$_sid] = $_carry;
	}
	$total_num = $clsISO->processSmartNumber($totalgrand);
	// Sale chính giữ phần còn lại → Σ(chính + phụ) luôn = 100, kể cả khi dữ liệu cũ lệch tổng
	$main_ratio = 100 - $sum_co;
	#
	$oneBilling = $clsBilling->getOne($billing_id);
	$billing_information = $clsISO->to_array_json($oneBilling["more_information"]);
	$total_commission_value = $core->get_field($billing_information,"commission_value",0);
	$commission = $core->get_field($billing_information,"commission",0);
	$total_commission_value = !empty($total_commission_value) ? $clsISO->processSmartNumber($total_commission_value) : $clsISO->processSmartNumber($totalgrand);
	$total_commission_value = $total_commission_value * $commission / 100;
	$total_deduction = $core->get_field($billing_information,"total_deduction",0);
	$total_deduction_percent_sales = $core->get_field($billing_information,"total_deduction_percent_sales",0);

	$shares = array();
	// Sale chính LUÔN có dòng. Trước đây nhánh này nằm sau gate _DEV() (bật bằng ?dev=1 trên URL) nên
	// mở 1 GD không co-sale rồi bấm Lưu là xoá sạch dòng phân bổ doanh số của GD đó.
	// Giữ dòng cả khi main_ratio = 0 (sale phụ ăn trọn 100%): mỗi GD phải luôn đúng 1 dòng is_primary,
	// báo cáo doanh số cá nhân join theo dòng này, thiếu là sale chính biến mất khỏi GD.
	if($staff_id > 0){
		$deduction_main = $main_ratio / 100 * $total_deduction; // tiền giảm trừ phần sale chính
		$deduction_main_sales = $total_deduction_percent_sales / 100 * $deduction_main; // sale phải chịu
		$commission_value = ($main_ratio / 100 * $total_commission_value) - ($deduction_main - $deduction_main_sales); // doanh số thi đua sau giảm trừ
		$main_row = array_merge(array(
			'staff_id' => $staff_id,
			'seller_name' => $clsProfile->getFullName($staff_id),
			'department_id' => $department_id,
			'regional_id' => $regional_id,
			'share_ratio' => $main_ratio,
			'share_value' => round($main_ratio / 100 * $total_num),
			'commission_value' => round($commission_value),
			'is_primary' => 1
		), isset($keep_by_staff[$staff_id]) ? $keep_by_staff[$staff_id] : array());
		// 2 ô TPKD/GĐKD trên form gán cho sale chính. Chỉ đè khi form thực sự gửi lên,
		// không thì giữ giá trị đã gán ở màn Quyết toán.
		if(array_key_exists('main_head_of_dep_id', $_POST)){
			$main_row['head_of_dep_id'] = (int) Input::post('main_head_of_dep_id', 0);
		}
		if(array_key_exists('main_sale_dir_id', $_POST)){
			$main_row['sale_dir_id'] = (int) Input::post('main_sale_dir_id', 0);
		}
		$shares[] = $main_row;
	}
	// Sale phụ: suy dept/regional theo từng staff để báo cáo đúng nhánh
	foreach($co_rows as $cid => $ratio){
		// Giảm trừ tính trên biến RIÊNG từng vòng: gán đè $total_deduction làm sale thứ 2, thứ 3 nhân dồn trên số đã co lại
		$deduction_co = $ratio / 100 * $total_deduction; // tiền giảm trừ phần sale phụ này
		$deduction_co_sales = $total_deduction_percent_sales / 100 * $deduction_co; // sale phải chịu
		$commission_value = ($ratio / 100 * $total_commission_value) - ($deduction_co - $deduction_co_sales); // doanh số thi đua sau giảm trừ
		$co_dep = $co_region = 0;
		$_oDep = $clsProfile->getOne($cid, "`department_id`");
		if(!empty($_oDep)){
			$co_dep = (int) $_oDep['department_id'];
			$_chain = $clsProperty->resolveStaffDepChain($co_dep);
			$co_region = (int) $core->get_field($_chain, 'region_id', 0);
		}
		$shares[] = array_merge(array(
			'staff_id' => $cid,
			'seller_name' => $clsProfile->getFullName($cid),
			'department_id' => $co_dep,
			'regional_id' => $co_region,
			'share_ratio' => $ratio,
			'share_value' => round($ratio / 100 * $total_num),
			'commission_value' => round($commission_value),
			'is_primary' => 0
		), isset($keep_by_staff[$cid]) ? $keep_by_staff[$cid] : array());
	}
	// Bất biến trước khi ghi đè: saveShares() xoá toàn bộ dòng cũ, nên bộ dòng mới phải đủ và cộng đúng 100%.
	// Không đạt thì thà giữ nguyên dữ liệu cũ còn hơn để GD mất dòng phân bổ.
	$sum_ratio = 0;
	foreach($shares as $_oShare){
		$sum_ratio += (float) $_oShare['share_ratio'];
	}
	if(empty($shares) || abs($sum_ratio - 100) > 1){
		_billing_write_audit($billing_id, 'share_reject', sprintf('Bỏ qua ghi phân bổ GD #%d: %d dòng, tổng tỷ lệ %s%%', $billing_id, count($shares), $sum_ratio));
		return 0;
	}
	// saveShares() cấp id bằng MAX(pkey)+1 nên 2 lần lưu đồng thời có thể đụng id và ghi hụt dòng.
	// Không chặn được trên MyISAM, nhưng ghi hụt phải nhìn thấy được chứ không im lặng.
	$saved = $clsBillingSale->saveShares($billing_id, $shares);
	if($saved != count($shares)){
		_billing_write_audit($billing_id, 'share_partial', sprintf('Ghi phân bổ GD #%d thiếu dòng: cần %d, ghi được %d', $billing_id, count($shares), $saved));
	}
	return $saved;
}
/**
 * Các ô được phép sửa nhanh tại chỗ trên màn giao dịch. Danh sách lấy đúng theo p_field
 * đang có trong application/views/home/*.tpl.
 * Cố ý KHÔNG có: commission_value, total_deduction*, staff_id, totalgrand, dep_logs —
 * các số gốc của doanh số chỉ được đi qua form Lưu giao dịch (đã gate + ghi lại dòng phân bổ).
 */
function _billing_inline_editable_fields(){
	return array(
		'notes', 'customer_name', 'customer_phone', 'customer_email', 'stock_resource',
		'commission', 'commission_sale', 'commission_agency', 'agency_bonus', 'marketing_bonus',
		'sale_bonus', 'sale_ps_bonus', 'support_sale', 'sale_date', 'sale_policy_date',
		'estimate_date', 'contract_date', 'payment_order', 'price_sheet_file',
		'residence_info', 'contract_files'
	);
}
/** Khối (vùng) của 1 GD. Ưu tiên cột snapshot; 151/342 GD import cũ có regional_id=0 nên phải
 *  suy lại từ phòng ban hiện tại của sale. Trả 0 = không xác định được. */
function _billing_region_id($billing_id){
	global $core;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$oneBilling = $clsBilling->getOne((int) $billing_id, "`regional_id`,`staff_id`");
	if(empty($oneBilling)){
		return 0;
	}
	$region_id = (int) $oneBilling['regional_id'];
	if($region_id > 0){
		return $region_id;
	}
	$staff_id = (int) $oneBilling['staff_id'];
	if($staff_id <= 0){
		return 0;
	}
	$oneStaff = $clsProfile->getOne($staff_id, "`department_id`");
	if(empty($oneStaff)){
		return 0;
	}
	$_chain = $clsProperty->resolveStaffDepChain((int) $oneStaff['department_id']);
	return (int) $core->get_field($_chain, 'region_id', 0);
}
/** Khối (vùng) của người đang đăng nhập. Trả 0 = không xác định được. */
function _billing_my_region_id(){
	global $core, $oneProfile;
	$clsProperty = new Property();
	$_chain = $clsProperty->resolveStaffDepChain((int) $core->get_field($oneProfile, 'department_id', 0));
	return (int) $core->get_field($_chain, 'region_id', 0);
}
/** Giám đốc Vùng chỉ được đụng GD trong khối của mình. Không xác định được khối (2 phía) thì KHÔNG cho. */
function _billing_regional_in_scope($billing_id){
	global $clsISO;
	if(!$clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')){
		return false;
	}
	$my_region = _billing_my_region_id();
	if($my_region <= 0){
		return false;
	}
	return $my_region == _billing_region_id($billing_id);
}
/**
 * Ai được thêm/sửa 1 giao dịch. Phạm vi để rộng có chủ đích (quyền module + chủ GD)
 * nhằm không chặn nhầm người đang vận hành; mọi lần từ chối đều ghi vết để soi lại.
 * $billing_id = 0 nghĩa là thêm mới.
 */
function _billing_can_save($billing_id){
	global $clsISO, $profile_id;
	$clsBilling = new Billing();
	$billing_id = (int) $billing_id;
	// Ban lãnh đạo + Giám đốc Kinh doanh: toàn quyền trên mọi GD
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		return true;
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		return true;
	}
	// Giám đốc Vùng: CHỈ trong khối của mình
	if($billing_id > 0 && _billing_regional_in_scope($billing_id)){
		return true;
	}
	if($billing_id <= 0){
		return $clsISO->checkPermission('create_billing') ? true : false;
	}
	if($clsISO->checkPermission('edit_billing')){
		return true;
	}
	// Không có quyền chung → chỉ được đụng GD của chính mình (sale đứng tên hoặc người đã tạo)
	$oneBilling = $clsBilling->getOne($billing_id, "`staff_id`,`admin_id`");
	if(empty($oneBilling)){
		return false;
	}
	if((int) $oneBilling['staff_id'] == (int) $profile_id){
		return true;
	}
	if((int) $oneBilling['admin_id'] == (int) $profile_id){
		return true;
	}
	return false;
}
/**
 * Ai được HUỶ giao dịch. Siết hơn sửa: huỷ là gỡ doanh số khỏi mọi báo cáo.
 * Sale KHÔNG được tự huỷ GD của mình, và quyền edit_billing cũng không đủ —
 * phải có quyền cancel_billing riêng, hoặc là cấp quản lý.
 */
function _billing_can_cancel($billing_id){
	global $clsISO;
	$billing_id = (int) $billing_id;
	if($billing_id <= 0){
		return false;
	}
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		return true;
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		return true;
	}
	if(_billing_regional_in_scope($billing_id)){
		return true;
	}
	return $clsISO->checkPermission('cancel_billing') ? true : false;
}
/**
 * Chỉ ghi đè key khi form THỰC SỰ có gửi ô đó lên.
 *
 * Các số quyết toán (giảm trừ, thưởng nóng) được nhập ở màn Quyết toán riêng, không còn nằm
 * trong form thêm/sửa giao dịch. Gán vô điều kiện như trước thì Input::post() trả chuỗi rỗng
 * → processSmartNumber('') = 0 ⇒ mỗi lần mở giao dịch bấm Lưu là xoá trắng số đã quyết toán,
 * và không có log nào vì nhật ký chỉ ghi khi giá trị mới khác rỗng.
 */
function _billing_set_if_posted(&$more_information, $key, $value){
	if(array_key_exists($key, $_POST)){
		$more_information[$key] = $value;
	}
}
/**
 * Ghi vết các thao tác nhạy cảm về tiền (từ chối quyền, bỏ qua ghi phân bổ) vào default_activitylog.
 * Dùng insert() thẳng vì addActivityLog() chỉ ghi khi getContentLog() nhận diện được cặp tbl/action.
 */
function _billing_write_audit($billing_id, $action, $content){
	global $profile_id;
	$clsActivityLog = new ActivityLog();
	return $clsActivityLog->insert(array(
		'tbl' => 'billing',
		'pkey' => 'billing_id',
		'pval' => (int) $billing_id,
		'title' => 'Chốt chặn giao dịch',
		'content' => $content,
		'profile_id' => (int) $profile_id,
		'user_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
		'_from' => defined('IS_ADMIN_PAGE') && IS_ADMIN_PAGE == 1 ? 'admin' : 'front',
		'action' => $action,
		'reg_date' => time()
	));
}
function default_pop_save_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$header_configs;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsStockHug = new StockHug();
	$clsProperty = new Property();
	###
	$_uid = Input::post('_uid');
	$billing_id = (int) Input::post('billing_id');
	// Handler này ghi thẳng commission_value/commission/staff_id/total_deduction nên phải chặn tại đây:
	// không có gate thì bất kỳ tài khoản đăng nhập nào POST tay cũng sửa được doanh số của mọi GD
	if(!_billing_can_save($billing_id)){
		_billing_write_audit($billing_id, 'save_denied', sprintf('Từ chối lưu GD #%d: tài khoản không đủ quyền', $billing_id));
		// Phía JS đọc dataType:'json' → trả chuỗi trần sẽ làm hỏng parse và treo vòng xoay chờ
		echo json_encode(array('msg' => '_permission', 'billing_id' => $billing_id)); die();
	}
	$billing_type = (int) Input::post('billing_type');
	$billing_code =  Input::post('billing_code');
	$deposit_date = Input::post('deposit_date');
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$stock_resource = (int) Input::post('stock_resource');
	$totalgrand = Input::post('totalgrand');
	$commission = Input::post('commission');
	$sale_bonus = Input::post('sale_bonus'); // Thưởng sale
	$support_sale = Input::post('support_sale'); // Hỗ trợ Sale
	$leader_commission_rate = Input::post('leader_commission_rate');
	$sale_dir_commission_rate = Input::post('sale_dir_commission_rate');
	$regional_dir_commission_rate = Input::post('regional_dir_commission_rate');
	$project_dir_commission_rate = Input::post('project_dir_commission_rate');
	$is_alliance = (int) Input::post('is_alliance', 0);
	$is_fullscore = (int) Input::post('is_fullscore', 0);
	$sale_agency_id = (int) Input::post('sale_agency_id', 0);
	$sold_to_type = (int) Input::post('sold_to_type', 0);
	$billing_source_id = (int) Input::post('billing_source_id', 0);
	$admin_id = (int) Input::post('admin_id', 0);
	#	
	$commission_value = Input::post('commission_value');
	$hot_bonus_customer = Input::post('hot_bonus_customer');
	$hot_bonus_agency = Input::post('hot_bonus_agency');
	$bonus_agency = Input::post('bonus_agency');
	$hot_bonus = Input::post('hot_bonus');
	$total_deduction = Input::post('total_deduction');
	$total_deduction_percent_sales = Input::post('total_deduction_percent_sales');
	$total_deduction_company = Input::post('total_deduction_company');
	$billing_search = sprintf('|%s_%s|', $project_id, $billing_type);
	$deposit_date = !empty($deposit_date) ? $clsISO->toTime($deposit_date) : 0;
	$next_month_time = strtotime('+1 month', $deposit_date);
	$edit_due_date = strtotime(sprintf('05-%s 23:59:59', date('m-Y', $next_month_time))); // Ngày mùng 5 tháng kế tiếp
	/** Send Zalo Msg */
	$is_send_zalo = (int) Input::post("is_send_zalo",0);
	$template_zalo_id = (int) Input::post("template_zalo_id",0);
	$staff_wish_id = (int) Input::post("staff_wish_id",0);
	$image_poster = Input::post("image_poster","");
	// Leader — chuỗi phụ trách (Vùng/GĐV/GĐKD) chốt TẠI SERVER theo cây phòng ban sau khi đọc staff_id, không tin hidden input
	$regional_id = $head_of_dep_id = $regional_director_id = $business_director_id = 0;
	$department_id = (int) Input::post('department_id', 0);
	$project_director_id = (int) Input::post('project_director_id', 0);
	$is_ignore_confirmed = (int) Input::post('is_ignore_confirmed', 0);
	###
	$staff_id = (int) Input::post('staff_id', 0);
	if($staff_id > 0){
		$_oStaffDep = $clsProfile->getOne($staff_id, "`department_id`");
		if(!empty($_oStaffDep)){
			$department_id = (int) $_oStaffDep['department_id'];
			$_dep_chain = $clsProperty->resolveStaffDepChain($department_id);
			$regional_id = (int) $_dep_chain['region_id'];
			$head_of_dep_id = (int) $_dep_chain['head_of_dep_id'];
			$regional_director_id = (int) $_dep_chain['regional_director_id'];
		}
	}
	$stock_code = Input::post('stock_code');
	$stock_code_q = $dbconn->qstr($stock_code); // escape cho SQL (Input::post chỉ xssClean, không escape nháy)
	$stock_id = $building_id = $stock_type = $bedroom_id = $type_id = $home_direction_id = $investor_id = 0;
	if(!empty($stock_code) && $project_id > 0){
		$field = "`t1`.{$clsStock->pkey},`t1`.`block_id`,`t1`.`building_id`,`t1`.`stock_type`
			,`t1`.`bedroom_id`,`t1`.`type_id`,`t1`.`home_direction_id`,`t2`.`more_information`";
		$oStock = $dbconn->getRow("SELECT {$field} FROM {$clsStock->tbl} AS `t1` 
			LEFT JOIN {$clsProperty->tbl} AS `t2` ON `t1`.`block_id`=`t2`.`property_id` 
			WHERE `t1`.`ms_code`={$stock_code_q} AND `t1`.`project_id`={$project_id}");
		if(!empty($oStock)){
			$more_information = $oStock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$investor_id = (int) $core->get_field($more_information, "investor_id", 0);
			$stock_id = $oStock[$clsStock->pkey]; // Phân khu
			$block_id = !empty($block_id) ? $block_id : $oStock['block_id']; // Phân khu
			$building_id = $oStock['building_id']; // Toà nhà
			$stock_type = $oStock['stock_type'];
			$bedroom_id = $oStock['bedroom_id'];
			$type_id = $oStock['type_id'];
			$home_direction_id = $oStock['home_direction_id'];
		}
	}
	###
	$msg = "_error";
	if($billing_id > 0){
		if($clsBilling->countItem("`is_cancel`=0 AND `stock_code`={$stock_code_q}
		AND `project_id`='{$project_id}' AND `billing_id`<>'{$billing_id}'")){
			$msg = "_duplicated";
		} else {
			$oBilling = $clsBilling->getOne($billing_id);
			$logs = $oBilling['logs'];
			$more_information = $oBilling['more_information'];
			$logs = $clsISO->to_array_json($logs);
			$more_information = $clsISO->to_array_json($more_information);
			$is_confirmed = false;
			if($oBilling['stock_code'] != $stock_code 
				|| $oBilling['staff_id'] != $staff_id 
				|| $oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)){
				$is_confirmed = true;
			}
			$confirm_billing = !empty($header_configs["confirm_billing"]) ? $clsISO->to_array_json($header_configs["confirm_billing"]) : [];
			if($is_confirmed == true && $is_ignore_confirmed == 0 && !empty($confirm_billing)){
				$arr_fields_change = array();
				$is_change_staff = ($oBilling['staff_id'] != $staff_id) ? 1 : 0;
				$is_change_code = ($oBilling['stock_code'] != $stock_code) ? 1 : 0;
				$is_change_amount = ($oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)) ? 1 : 0;
				if($is_change_staff) {
					$arr_fields_change['staff_id'] = $staff_id;
				}
				$dep_logs = $core->get_field($more_information, "dep_logs", []);
				if($confirm_billing["name"] == "admin") {
					if($confirm_billing["staff_id"] > 0){
						$arr_fields_change['dep_logs']['staff_id'] = $confirm_billing["staff_id"];
					}
				}else{
					if($core->get_field($dep_logs, "head_of_dep_id", 0) != $head_of_dep_id){
						$arr_fields_change['dep_logs']['head_of_dep_id'] = $head_of_dep_id;
					}
					if($core->get_field($dep_logs, "regional_director_id", 0) != $regional_director_id){
						$arr_fields_change['dep_logs']['regional_director_id'] = $regional_director_id;
					}
				}
				if($is_change_code) $arr_fields_change['stock_code'] = $stock_code;
				if($is_change_amount) $arr_fields_change['totalgrand'] = $clsISO->processSmartNumber($totalgrand);
				$smarty->assign('_uid', $_uid);
				$smarty->assign('billing_id', $billing_id);
				$smarty->assign('stock_code', $oBilling['stock_code']);
				$smarty->assign('arr_fields_change', $arr_fields_change);
				// Return
				$html = $core->build('_ajax.billing_confirm.tpl');
				echo json_encode(array(
					'msg' => '_confirm_changed',
					'billing_id' => $billing_id,
					'uid' => $clsISO->getUniqid(),
					'html' => $html
				)); die();
			}
			/** Logs */
			$is_changed = 0; $action_logs = array();
			if($deposit_date > 0 && $oBilling['deposit_date'] != $deposit_date){
				$is_changed = 1;
				$action_logs[] = 'ngày cọc: '.$clsISO->convertTimeToText($deposit_date);
			}
			if($admin_id > 0 && $oBilling['admin_id'] != $admin_id){
				$is_changed = 1;
				$action_logs[] = 'admin phụ trách: '.$clsProfile->getFullName($admin_id);
			}
			if($billing_type > 0 && $oBilling['billing_type'] != $billing_type){
				$is_changed = 1;
				$action_logs[] = 'loại hình: '.$clsProperty->getTitle($billing_type);
			}
			if($stock_resource > 0 && $more_information['stock_resource'] != $stock_resource){
				$is_changed = 1;
				$action_logs[] = 'nguồn gốc: '.$clsProperty->getTitle($stock_resource);
			}
			if($billing_source_id > 0 && $oBilling['billing_source_id'] != $billing_source_id){
				$is_changed = 1;
				$action_logs[] = 'nguồn quỹ: '.$clsProperty->getTitle($billing_source_id);
			}
			if($sold_to_type > 0 && $oBilling['sold_to_type'] != $sold_to_type){
				$is_changed = 1;
				$action_logs[] = 'bán cho: '.$clsProperty->getTitle($sold_to_type);
			}
			if($sale_agency_id > 0 && $more_information['sale_agency_id'] != $sale_agency_id){
				$is_changed = 1;
				$action_logs[] = 'đại lý bán: '.$clsProperty->getTitle($sale_agency_id);
			}
			if($staff_id > 0 && $is_confirmed == false && $oBilling['staff_id'] != $staff_id){
				$is_changed = 1;
				$action_logs[] = 'sale bán: '.$clsProfile->getFullName($staff_id);
			}
			if($project_id > 0 && $oBilling['project_id'] != $project_id){
				$is_changed = 1;
				$action_logs[] = 'dự án: '.$clsProject->getTitle($project_id);
			}
			if(!empty($stock_code) && $is_confirmed == false && $oBilling['stock_code'] != $stock_code){
				$is_changed = 1;
				$action_logs[] = 'mã căn: '.$stock_code;
			}
			if($totalgrand > 0 && $is_confirmed == false && $oBilling['totalgrand'] != $clsISO->processSmartNumber($totalgrand)){
				$is_changed = 1;
				$action_logs[] = 'tổng tiền: '.$totalgrand;
			}
			if($clsISO->convertToNumber($commission) > 0 
				&& $core->get_field($more_information, "commission", 0) != $commission){
				$is_changed = 1;
				$action_logs[] = 'hoa hồng: '.$commission;
			}
			if(!empty($sale_bonus) 
				&& $more_information['sale_bonus'] != $clsISO->processSmartNumber($sale_bonus)){
				$is_changed = 1;
				$action_logs[] = 'thưởng sale: '.$sale_bonus;
			}
			if(!empty($support_sale) 
				&& $more_information['support_sale'] != $clsISO->processSmartNumber($support_sale)){
				$is_changed = 1;
				$action_logs[] = 'hỗ trợ: '.$support_sale;
			}
			if(!empty($leader_commission_rate) 
				&& $more_information['leader_commission_rate'] != $clsISO->processSmartNumber($leader_commission_rate)){
				$is_changed = 1;
				$action_logs[] = 'hoa hồng TPKD: '.$leader_commission_rate;
			}
			if(!empty($regional_dir_commission_rate) 
				&& $more_information['regional_dir_commission_rate'] != $clsISO->processSmartNumber($leader_commission_rate)){
				$is_changed = 1;
				$action_logs[] = 'hoa hồng GĐ Vùng: '.$regional_dir_commission_rate;
			}
			if(!empty($sale_dir_commission_rate) 
				&& $more_information['sale_dir_commission_rate'] != $clsISO->processSmartNumber($sale_dir_commission_rate)){
				$is_changed = 1;
				$action_logs[] = 'hoa hồng GĐKD: '.$sale_dir_commission_rate;
			}
			if(!empty($project_dir_commission_rate) 
				&& $more_information['project_dir_commission_rate'] != $clsISO->processSmartNumber($project_dir_commission_rate)){
				$is_changed = 1;
				$action_logs[] = 'hoa hồng GĐDA: '.$project_dir_commission_rate;
			}
			if($is_changed == 1 && !empty($action_logs)){
				$content = sprintf('<strong>%s</strong> thay đổi %s', $clsProfile->getFullName($profile_id, $oneProfile), implode(', ', $action_logs));
				$logs[$clsISO->getUniqid()] = array(
					'profile_id' => $profile_id,
					'reg_date' => time(),
					'content' => $content
				);
			}
			if(!empty($commission_value) 
				&& $more_information['commission_value'] != $clsISO->processSmartNumber($commission_value)){
				$is_changed = 1;
				$action_logs[] = 'giá tính hoa hồng: '.$commission_value;
			}
			if(!empty($hot_bonus_customer) 
				&& $more_information['hot_bonus_customer'] != $clsISO->processSmartNumber($hot_bonus_customer)){
				$is_changed = 1;
				$action_logs[] = 'thưởng nóng khách hàng: '.$hot_bonus_customer;
			}
			if(!empty($hot_bonus_agency) 
				&& $more_information['hot_bonus_agency'] != $clsISO->processSmartNumber($hot_bonus_agency)){
				$is_changed = 1;
				$action_logs[] = 'thưởng nóng đại lý (CTV/ĐL): '.$hot_bonus_agency;
			}
			if(!empty($bonus_agency) 
				&& $more_information['bonus_agency'] != $clsISO->processSmartNumber($bonus_agency)){
				$is_changed = 1;
				$action_logs[] = 'thưởng đại lý: '.$bonus_agency;
			}
			if(!empty($hot_bonus) 
				&& $more_information['hot_bonus'] != $clsISO->processSmartNumber($hot_bonus)){
				$is_changed = 1;
				$action_logs[] = 'thưởng nóng: '.$hot_bonus;
			}
			if(!empty($total_deduction) 
				&& $more_information['total_deduction'] != $clsISO->processSmartNumber($total_deduction)){
				$is_changed = 1;
				$action_logs[] = 'tổng tiền giảm trừ: '.$total_deduction;
			}
			if(!empty($total_deduction_percent_sales) 
				&& $more_information['total_deduction_percent_sales'] != $total_deduction_percent_sales){
				$is_changed = 1;
				$action_logs[] = 'tỷ lệ sales chịu: '.$total_deduction_percent_sales;
			}
			if(!empty($total_deduction_company) 
				&& $more_information['total_deduction_company'] != $clsISO->processSmartNumber($total_deduction_company)){
				$is_changed = 1;
				$action_logs[] = 'công ty chịu: '.$total_deduction_company;
			}
			$more_information['stock_id'] = $stock_id;
			$more_information['block_id'] = $block_id;
			$more_information['building_id'] = $building_id;
			$more_information['stock_type'] = $stock_type;
			$more_information['bedroom_id'] = $bedroom_id;
			$more_information['type_id'] = $type_id;
			$more_information['edit_due_date'] = $edit_due_date;
			$more_information['home_direction_id'] = $home_direction_id;
			$more_information['deposit_date'] = $deposit_date;
			$more_information['stock_resource'] = $stock_resource;
			$more_information['sale_agency_id'] = $sale_agency_id;
			$more_information['billing_source_id'] = $billing_source_id;
			$more_information['staff_notes'] = Input::post('staff_notes');
			$more_information['customer_name'] = Input::post('customer_name');
			$more_information['customer_email'] = Input::post('customer_email');
			$more_information['customer_phone'] = Input::post('customer_phone');
			$more_information['sale_bonus'] = $clsISO->processSmartNumber($sale_bonus);
			$more_information['commission'] = $clsISO->convertToNumber($commission);
			$more_information['support_sale'] = $clsISO->processSmartNumber($support_sale);
			// 4 ô % hoa hồng vai trò đã bỏ khỏi form: chỉ ghi khi form thực sự gửi lên,
			// không thì mỗi lần lưu là ghi đè 0 lên số cũ (dù hiện 0/342 GD có giá trị)
			_billing_set_if_posted($more_information, 'leader_commission_rate', $clsISO->convertToNumber($leader_commission_rate));
			_billing_set_if_posted($more_information, 'sale_dir_commission_rate', $clsISO->convertToNumber($sale_dir_commission_rate));
			_billing_set_if_posted($more_information, 'regional_dir_commission_rate', $clsISO->convertToNumber($regional_dir_commission_rate));
			_billing_set_if_posted($more_information, 'project_dir_commission_rate', $clsISO->convertToNumber($project_dir_commission_rate));
			$more_information['sale_policy_file'] = Input::post('sale_policy_file');
			$more_information['capture_confirm_file'] = Input::post('capture_confirm_file');
			$more_information['table_bonus_file'] = Input::post('table_bonus_file');
			// 2 công tắc này đã bỏ khỏi form: chỉ ghi khi form thực sự gửi lên, không thì giữ số cũ
			_billing_set_if_posted($more_information, 'is_sendemail', (int) Input::post('is_sendemail', 0));
			_billing_set_if_posted($more_information, 'is_sendpale', (int) Input::post('is_sendpale', 0));
			$more_information['commission_value'] = $clsISO->processSmartNumber($commission_value);
			$more_information['bonus_agency'] = $clsISO->processSmartNumber($bonus_agency);
			$more_information['hot_bonus'] = $clsISO->processSmartNumber($hot_bonus);
			// 5 ô thuộc màn Quyết toán: chỉ ghi khi form có gửi lên, không thì giữ nguyên số cũ
			_billing_set_if_posted($more_information, 'hot_bonus_customer', $clsISO->processSmartNumber($hot_bonus_customer));
			_billing_set_if_posted($more_information, 'hot_bonus_agency', $clsISO->processSmartNumber($hot_bonus_agency));
			_billing_set_if_posted($more_information, 'total_deduction', $clsISO->processSmartNumber($total_deduction));
			_billing_set_if_posted($more_information, 'total_deduction_percent_sales', $clsISO->convertToNumber($total_deduction_percent_sales));
			_billing_set_if_posted($more_information, 'total_deduction_company', $clsISO->processSmartNumber($total_deduction_company));
			_billing_set_if_posted($more_information, 'deal_type', _billing_clean_deal_type(Input::post('deal_type')));
			if(!empty($image_poster)) $more_information['image_poster'] = $image_poster;
			$more = array();
			if($is_confirmed == false){
				$more['staff_id'] = $staff_id;
				$more['stock_code'] = $stock_code;
				$more['totalgrand'] = $clsISO->processSmartNumber($totalgrand);
				$more_information['dep_logs'] = array(
					'regional_id' => $regional_id,
					'head_of_dep_id' => $head_of_dep_id,
					'business_director_id' => $business_director_id,
					'regional_director_id' => $regional_director_id,
					'project_director_id' => $project_director_id
				);
			}
			// $clsISO->print_pre($more_information); die();
			if($clsBilling->updateOne($billing_id, array_merge($more, array(
				'billing_code' => $billing_code,
				'billing_type' => $billing_type,
				'billing_search' => $billing_search,
				'sold_to_type' => $sold_to_type,
				'regional_id' => $regional_id,
				'department_id' => $department_id,
				'deposit_date' => $deposit_date,
				'admin_id' => $admin_id,
				'investor_id' => $investor_id,
				'is_alliance' => $is_alliance, // LiÃan minh bán
				'is_fullscore' => $is_fullscore,
				'project_id' => (int) Input::post('project_id', 0),
				'state_id' => (int) Input::post('state_id', 0),
				'billing_source_id' => (int) Input::post('billing_source_id', 0),
				'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'upd_date' => time(),
				'user_id_update' => $profile_id
			)))){
				$msg = "_success";
				/** Co-sale: ghi lại danh sách sale tham gia (chính + phụ theo %) */
				_save_billing_shares($billing_id, $staff_id, $department_id, $regional_id, $totalgrand);
				// $FPoint = new FPoint();
				// $fpoint->insert_billing_LPoint($billing_id);
				/** Đồng bộ Hoa Hồng */
				// $oneBilling = $clsBilling->getOne($billing_id);
				// $clsBilling->sync_commission($billing_id, $oneBilling, "add");
				#activity log			
				// $clsActivityLog = new ActivityLog();
				// $log = $clsActivityLog->addActivityLog("Billing","update");
				#chuc mung
				/*if(!empty($is_send_zalo) && !empty($template_zalo_id) && !empty($image_poster)) {
					$arr_data = [
						"is_send_zalo"	=>	$is_send_zalo,
						"template_zalo_id"	=>	$template_zalo_id,
						"staff_wish_id"	=>	$staff_wish_id,
						"image_poster"	=>	$image_poster,
					];
					$clsBilling->sendWish($billing_id,$arr_data);
				}*/
			}
		}
	} else {
		if($clsBilling->countItem("`is_cancel`=0 AND `project_id`='{$project_id}'
			AND `stock_code`={$stock_code_q}")){
			$msg = "_duplicated";
		} else {
			$more_information = array();
			$billing_id = $clsBilling->getMaxId();
			$more_information['stock_id'] = $stock_id;
			$more_information['block_id'] = $block_id;
			$more_information['building_id'] = $building_id;			
			$more_information['stock_type'] = $stock_type;
			$more_information['bedroom_id'] = $bedroom_id;
			$more_information['type_id'] = $type_id;
			$more_information['edit_due_date'] = $edit_due_date;
			$more_information['home_direction_id'] = $home_direction_id;
			$more_information['deposit_date'] = $deposit_date;
			$more_information['stock_resource'] = $stock_resource;
			$more_information['sale_agency_id'] = $sale_agency_id;
			$more_information['billing_source_id'] = $billing_source_id;
			$more_information['staff_notes'] = Input::post('staff_notes');
			$more_information['customer_name'] = Input::post('customer_name');
			$more_information['customer_email'] = Input::post('customer_email');
			$more_information['customer_phone'] = Input::post('customer_phone');
			$more_information['commission'] = $clsISO->convertToNumber($commission);
			$more_information['sale_bonus'] = $clsISO->processSmartNumber($sale_bonus);
			$more_information['support_sale'] = $clsISO->processSmartNumber($support_sale);
			$more_information['leader_commission_rate'] = $clsISO->convertToNumber($leader_commission_rate);
			$more_information['regional_dir_commission_rate'] = $clsISO->convertToNumber($regional_dir_commission_rate);
			$more_information['sale_dir_commission_rate'] = $clsISO->convertToNumber($sale_dir_commission_rate);
			$more_information['project_dir_commission_rate'] = $clsISO->convertToNumber($project_dir_commission_rate);
			$more_information['sale_policy_file'] = Input::post('sale_policy_file');
			$more_information['capture_confirm_file'] = Input::post('capture_confirm_file');
			$more_information['table_bonus_file'] = Input::post('table_bonus_file');
			// 2 công tắc này đã bỏ khỏi form: chỉ ghi khi form thực sự gửi lên, không thì giữ số cũ
			_billing_set_if_posted($more_information, 'is_sendemail', (int) Input::post('is_sendemail', 0));
			_billing_set_if_posted($more_information, 'is_sendpale', (int) Input::post('is_sendpale', 0));
			$more_information['commission_value'] = $clsISO->processSmartNumber($commission_value);
			$more_information['bonus_agency'] = $clsISO->processSmartNumber($bonus_agency);
			$more_information['hot_bonus'] = $clsISO->processSmartNumber($hot_bonus);
			// 5 ô thuộc màn Quyết toán: chỉ ghi khi form có gửi lên, không thì giữ nguyên số cũ
			_billing_set_if_posted($more_information, 'hot_bonus_customer', $clsISO->processSmartNumber($hot_bonus_customer));
			_billing_set_if_posted($more_information, 'hot_bonus_agency', $clsISO->processSmartNumber($hot_bonus_agency));
			_billing_set_if_posted($more_information, 'total_deduction', $clsISO->processSmartNumber($total_deduction));
			_billing_set_if_posted($more_information, 'total_deduction_percent_sales', $clsISO->convertToNumber($total_deduction_percent_sales));
			_billing_set_if_posted($more_information, 'total_deduction_company', $clsISO->processSmartNumber($total_deduction_company));
			_billing_set_if_posted($more_information, 'deal_type', _billing_clean_deal_type(Input::post('deal_type')));
			if(!empty($image_poster)) {
				$more_information['image_poster'] = $image_poster;
			}
			$more_information['dep_logs'] = array(
				'regional_id' => $regional_id,
				'head_of_dep_id' => $head_of_dep_id,
				'business_director_id' => $business_director_id,
				'regional_director_id' => $regional_director_id,
				'project_director_id' => $project_director_id
			);
			/** Logs */
			$action_logs = array();
			if($admin_id > 0){
				$action_logs[] = 'admin phụ trách: '.$clsProfile->getFullName($admin_id);
			}
			if($deposit_date > 0){
				$action_logs[] = 'ngày cọc: '.$clsISO->convertTimeToText($deposit_date);
			}
			if($billing_type > 0){
				$action_logs[] = 'loại hình: '.$clsProperty->getTitle($billing_type);
			}
			if($stock_resource > 0){
				$action_logs[] = 'nguồn gốc: '.$clsProperty->getTitle($stock_resource);
			}
			if($sold_to_type > 0){
				$action_logs[] = 'bán cho: '.$clsProperty->getTitle($sold_to_type);
			}
			if($billing_source_id > 0 ){
				$action_logs[] = 'nguồn quỹ: '.$clsProperty->getTitle($billing_source_id);
			}
			if($sale_agency_id > 0){
				$action_logs[] = 'đại lý bán: '.$clsProperty->getTitle($sale_agency_id);
			}
			if($staff_id > 0 && $oBilling['staff_id'] != $staff_id){
				$action_logs[] = 'sale bán: '.$clsProfile->getFullName($staff_id);
			}
			if($project_id > 0){
				$action_logs[] = 'dự án: '.$clsProject->getTitle($project_id);
			}
			if(!empty($stock_code)){
				$action_logs[] = 'mã căn: '.$stock_code;
			}
			if($totalgrand > 0){
				$action_logs[] = 'tổng tiền: '.$totalgrand;
			}
			if($clsISO->convertToNumber($commission) > 0){
				$action_logs[] = 'hoa hồng: '.$commission;
			}
			if(!empty($sale_bonus)){
				$action_logs[] = 'thưởng sale: '.$sale_bonus;
			}
			if(!empty($support_sale)){
				$action_logs[] = 'hỗ trợ: '.$support_sale;
			}
			if(!empty($leader_commission_rate)){
				$action_logs[] = 'hoa hồng TPKD: '.$leader_commission_rate;
			}
			if(!empty($regional_dir_commission_rate)){
				$action_logs[] = 'hoa hồng GĐ Vùng: '.$regional_dir_commission_rate;
			}
			if(!empty($sale_dir_commission_rate)){
				$action_logs[] = 'hoa hồng GĐKD: '.$sale_dir_commission_rate;
			}
			if(!empty($project_dir_commission_rate)){
				$action_logs[] = 'hoa hồng GĐDA: '.$project_dir_commission_rate;
			}
			if(!empty($commission_value)){
				$action_logs[] = 'giá tính hoa hồng: '.$commission_value;
			}
			if(!empty($hot_bonus_customer)){
				$is_changed = 1;
				$action_logs[] = 'thưởng nóng khách hàng: '.$hot_bonus_customer;
			}
			if(!empty($hot_bonus_agency)){
				$is_changed = 1;
				$action_logs[] = 'thưởng nóng đại lý (CTV/ĐL): '.$hot_bonus_agency;
			}
			if(!empty($bonus_agency)){
				$is_changed = 1;
				$action_logs[] = 'thưởng đại lý: '.$bonus_agency;
			}
			if(!empty($hot_bonus)){
				$is_changed = 1;
				$action_logs[] = 'thưởng nóng: '.$hot_bonus;
			}
			if(!empty($total_deduction)){
				$is_changed = 1;
				$action_logs[] = 'tổng tiền giảm trừ: '.$total_deduction;
			}
			if(!empty($total_deduction_percent_sales)){
				$is_changed = 1;
				$action_logs[] = 'tỷ lệ sales chịu: '.$total_deduction_percent_sales;
			}
			if(!empty($total_deduction_company)){
				$is_changed = 1;
				$action_logs[] = 'công ty chịu: '.$total_deduction_company;
			}
			$logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(),
				'profile_id' => $profile_id,
				'content' => sprintf('<strong>%s</strong> đã tạo giao dịch mới </strong>%s</strong> %s', 
					$clsProfile->getFullName($profile_id, $oneProfile), $billing_code, implode(', ', $action_logs))
			);
			// $clsBilling->setDebug(true);
			if($clsBilling->insert(array(
				$clsBilling->pkey => $billing_id,
				'billing_code' => $billing_code,
				'billing_type' => $billing_type,
				'billing_search' => $billing_search,
				'sold_to_type' => $sold_to_type,
				'staff_id' => $staff_id,
				'regional_id' => $regional_id,
				'department_id' => $department_id,
				'deposit_date' => $deposit_date,
				'stock_code' => $stock_code,
				'admin_id' => $admin_id,
				'investor_id' => $investor_id,
				'is_alliance' => $is_alliance, // LiÃan minh bán
				'is_fullscore' => $is_fullscore, // Full điểm
				'totalgrand' => $clsISO->processSmartNumber($totalgrand),
				'state_id' => (int) Input::post('state_id', 0),
				'project_id' => (int) Input::post('project_id', 0),
				'billing_source_id' => (int) Input::post('billing_source_id', 0),
				'contract_status_id' => (int) Input::post('contract_status_id'),
				'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'notes'	=> $notes,
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			))){
				$msg = "_success";	
				/** Đồng bộ Loyalty — điểm ma trận theo vị trí + cascade phụ trách GĐKD/GĐ Vùng theo cây phòng ban */
				$FPoint = new FPoint();
				$FPoint->insert_billing_LPoint_role($billing_id);
				/** Co-sale: ghi danh sách sale tham gia (chính + phụ theo %) */
				_save_billing_shares($billing_id, $staff_id, $department_id, $regional_id, $totalgrand);
				/** Đồng bộ Hoa Hồng */
				// $oneBilling = $clsBilling->getOne($billing_id);
				// $clsBilling->sync_commission($billing_id, $oneBilling, "add");
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Billing","insert");
				#chuc mung
				if(!empty($is_send_zalo) && !empty($template_zalo_id) && !empty($image_poster)) {
					$arr_data = [
						"is_send_zalo"	=>	$is_send_zalo,
						"staff_wish_id"	=>	$staff_wish_id,
						"template_zalo_id"	=>	$template_zalo_id,
						"image_poster"	=>	$image_poster,
					];
					$clsBilling->sendWish($billing_id,$arr_data);
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'billing_id' => $billing_id,
		'html' => sprintf('<a class="d-none autoclick__'.$billing_id.'" billing_id="%s" 
			onClick="$Core.billing.add_info(this,event)"></a>', $billing_id)
	)); die();
}
function default_upd_billing_changed(){
	global $core,$clsISO,$clsUser,$clsProperty,$dbconn;
	global $profile_id, $oneProfile,$header_configs;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$msg = "_error";
    $billing_id = (int) Input::post('billing_id',0);
	$content_change = Input::post('content_change');
	if($billing_id > 0){
		$oBilling = $clsBilling->getOne($billing_id, "`logs`,`more_information`");
		$action_logs = $oBilling['logs'];
		$more_information = $oBilling['more_information'];
		$action_logs = $clsISO->to_array_json($action_logs);
		$more_information = $clsISO->to_array_json($more_information);
		$dep_logs = $core->get_field($more_information, "dep_logs", []);
		$change_logs = $core->get_field($more_information, "change_logs", []);
		$project_director_id = (int) $core->get_field($dep_logs, "project_director_id", 0);
		$staff_admin_id = (int) $core->get_field($content_change["dep_logs"], "staff_id", 0);
		#
		$changing_log_id = $clsISO->getUniqid();
		$change_logs[$changing_log_id] = array(
			'reason' => Input::post('reason'),
			'sales_dir_agree_image' => Input::post('sales_dir_agree_image'),
			'sale_agree_image' => Input::post('sale_agree_image'),
			'content_change' => $content_change,
			'reg_date' => time()
		);
		#
		$ii = 0;
		$content_log = sprintf("<strong>%s</strong> đã thay đổi", $clsProfile->getFullName($profile_id, $oneProfile));
		foreach($content_change as $p_field => $p_value){
			if($p_field == 'staff_id'){
				$p_value = $clsProfile->getFullName($p_value);
			} else if($p_field == 'totalgrand'){
				$p_value = $clsISO->formatPrice($p_value);
			} else if($p_field == 'dep_logs') {
				continue;
			}
			$content_log.= ($ii==0 ? " ": ", ") . sprintf('%s: %s', $clsBilling->getFieldName($p_field), $p_value);
			++$ii;
		}
		$content_log.= sprintf(" vào lúc <strong>%s</strong>", date('d/m/Y h:i:s'));
		$action_logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'content' => $content_log,
			'profile_id' => $profile_id		
		);
		$more_information['is_content_changed'] = 0;
		$more_information['is_content_changing'] = 1;
		$more_information['change_logs'] = $change_logs;
		$more_information['changing_log_id'] = $changing_log_id;
		// $clsISO->print_pre($more_information); die();
		if($clsBilling->updateOne($billing_id, array(
			'logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			if($project_director_id > 0 || $staff_admin_id > 0){
				if($staff_admin_id > 0) {
					$more_information = $clsProfile->getOneField("more_information", $staff_admin_id);				
					$more_information = $clsISO->to_array_json($more_information);
					$billing_changing_confirms = $core->get_field($more_information, "billing_changing_confirms", []);
					if(!in_array($billing_id, $billing_changing_confirms)){
						$billing_changing_confirms[] = $billing_id;
						$more_information['billing_changing_confirms'] = $billing_changing_confirms;
						$clsProfile->updateOne($staff_admin_id, array(
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
						));
					}
				}else{
					$more_information = $clsProfile->getOneField("more_information", $project_director_id);					
					$more_information = $clsISO->to_array_json($more_information);
					$billing_changing_confirms = $core->get_field($more_information, "billing_changing_confirms", []);
					if(!in_array($billing_id, $billing_changing_confirms)){
						$billing_changing_confirms[] = $billing_id;
						$more_information['billing_changing_confirms'] = $billing_changing_confirms;
						$clsProfile->updateOne($project_director_id, array(
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
						));
					}
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_load_billing_changing_confirms(){
	global $core,$clsISO,$clsUser,$clsProperty;
	global $profile_id, $oneProfile;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$html = "";
	$more_information = $oneProfile['more_information'];
	$billing_changing_confirms = $core->get_field($more_information, "billing_changing_confirms", []);
	if(!empty($billing_changing_confirms)){
		$field = "{$clsBilling->pkey},`staff_id`,`stock_code`,`deposit_date`,`billing_type`,`totalgrand`";
		$list_billings = $clsBilling->getAll("`{$clsBilling->pkey}` IN ('".implode('\',\'', $billing_changing_confirms)."')", $field);
		if(!empty($list_billings)){
			$arr_property_cached = array();
			$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BILLING_TYPE'", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
				}
				unset($tmp);
			}
			$html.= '<ul class="list-unstyled m-0 p-0 w-100">';
			$arr_profile_cached = $clsProfile->getProfileCached();
			$loop = 0;
			foreach($list_billings as $key => $val){
				$staff_id = $val['staff_id'];
				$billing_id = $val['billing_id'];
				$billing_type = $val['billing_type'];
				$oneStaff = $arr_profile_cached[$staff_id];
				$more_info = $oneStaff['more_information'];
				$html.= '<li class="p-2 bg-white border rounded-2 cursor-pointer'.($loop > 0 ? ' mt-2':'').'" 
					onClick="$Core.global.billing.view_billing(this, event)" billing_id="'.$billing_id.'">
					<div class="d-flex gap-2 align-items-center mb-2">
						<div class="avatar overflow-hidden avatar rounded-pill">
							<img src="'.$clsProfile->getAvatar($staff_id, $oneStaff, 50, 50).'" height="38px" />
						</div>
						<div class="w-100 d-flex flex-column">
							<div class="w-100 d-flex align-items-center justify-content-between mb-1">
								<div class="fw-bold">'.$oneStaff['full_name'].' 
									<span class="badge bg-label-primary rounded-pill">'.$more_info['department_name'].'<span>
								</div>
							</div>
							<div class="row text-fs-12">
								<div class="col-6 border-end">
									<span class="d-flex align-items-cecnter">Mã căn: '.$val['stock_code'].'</span>
									<span class="d-flex align-items-cecnter">Dự án: Vinhomes Ocean Park 2</span>
								</div>
								<div class="col-6">
									<span class="d-flex align-items-cecnter">Ngày cọc: '.$clsISO->convertTimeToText($val['deposit_date']).' </span>
									<span class="d-flex align-items-cecnter">Loại hình: '.$arr_property_cached[$billing_type].'</span>
								</div>
							</div>
						</div>
					</div>
					<div class="px-3 py-2 bg-lightest rounded-pill">
						<div class="row">
							<div class="col-6 col-lg-8">
								<small class="text-muted">Giá trị</small>
								<div class="text-main fw-bold">'.$clsISO->formatPrice($val['totalgrand']).'</div>
							</div>
							<div class="col-6 col-lg-4">
								<a href="javascript:void(0)" class="btn btn-block btn-primary rounded-pill">
									Chi tiết <i class="bx bx-link-external text-fs-14"></i>
								</a>
							</div>
						</div>
					</div>
				</li>';
				++$loop;
			}
			$html.= '</ul>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_billing_done(){
    global $profile_id,$core,$clsISO,$clsUser,$clsProperty,$dbconn;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
    $billing_id = (int) Input::request('billing_id',0);
	$open_from = Input::request('open_from', "_desktop");
	##
	$field = "`contract_date`,`estimate_date`,`opt_date`,`agree_date`,`opt_status_id` ,`agree_status_id`,`contract_status_id`,`more_information`,`project_id`";
	$arr_status_contract_cached = $clsProperty->getArraySearchByKey("_STATUS_CONTRACT");
	$oBilling = $clsBilling->getOne($billing_id, $field);
	$more_information = $clsISO->to_array_json($oBilling["more_information"]);
	$block_id = $core->get_field($more_information, "block_id");
	$project_id = $core->get_field($oBilling, "project_id");
	$status_contract_id = 0;
	if(!empty($block_id)) {
		$oneBlock = $clsProperty->getArraySearchByKey("_BLOCK",$block_id);
		$block_more_information = $clsISO->to_array_json($oneBlock["more_information"]);
		$status_contract_id = $block_more_information["status_contract"] ?? 0;
	}
	if(empty($status_contract_id) && !empty($project_id)) {
		$oneProject = $clsProject->getOne($project_id);
		$project_more_information = $clsISO->to_array_json($oneProject["more_information"]);
		$status_contract_id = $project_more_information["status_contract"] ?? 0;
	}
	$otp_date = $oBilling['opt_date'];
	$otp_customer = $core->get_field($more_information, "otp_customer", "");
	$otp_reg_link = $core->get_field($more_information, "otp_reg_link", "");
	$agree_date = $oBilling['agree_date'];
	$contract_date = $oBilling['contract_date'];
	$estimate_date = $oBilling['estimate_date'];
	$opt_status_id = $oBilling['opt_status_id'];
	$agree_status_id = $oBilling['agree_status_id'];
	$contract_status_id = $oBilling['contract_status_id'];
	$rescheduling_reason = $core->get_field($more_information, "rescheduling_reason", "");
	###
	$html = "";
	if($open_from == '_mobile'){
		$html.= '<div class="modal-dialog modal-dialog-centered modal-ipad">
		<form class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cập nhật lịch ký HĐMB</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">';
	} else {
		$html.= '<form class="p-2" method="post">';
	}
    $html.= '
		<div class="form-group mb-2">
         	<label class="form-label mb-1">Ngày ký VBTT</label>
			<input type="date" class="form-control" value="'.(!empty($agree_date)?date('Y-m-d',$agree_date):"").'" 
				name="agree_date" maxlength="255" placeholder="dd/mm/YYYY" />
        </div>';
	if(!empty($arr_status_contract_cached[$status_contract_id])) {
		$html .='<div class="form-group mb-2">
			<label class="form-label mb-1 d-flex align-items-center gap-1 justify-content-end">
                <input class="" type="checkbox" name="agree_status_id" value="'.$status_contract_id.'" '.($status_contract_id == $agree_status_id ? "checked" : "").' >
                <span>'.$arr_status_contract_cached[$status_contract_id]["title"].'</span>
            </label>
        </div>';
	}
	
	$html .='<hr class="my-3" />
        <div class="form-group mb-2">
         	<label class="form-label mb-1">Ngày ký HĐMB</label>
			<input type="date" class="form-control" value="'.(!empty($contract_date)?date('Y-m-d',$contract_date):"").'" 
				name="contract_date" maxlength="255" placeholder="dd/mm/YYYY" value_old="'.$contract_date.(!empty($contract_date)?date('Y-m-d',$contract_date):"").'" toCls="note_contract" onchange="$Core.billing.toggle_rescheduling_reason(this,event)" />
        </div>
		<div class="form-group note_contract d-none mb-2">
         	<label class="form-label mb-1">Lý do</label> 
			<textarea class="form-control" cols="5" name="rescheduling_reason"></textarea>
        </div>';
	if(!empty($arr_status_contract_cached[_CONTRACT_STATUS_DONE_ID])) {
		$html .='<div class="form-group mb-2">
			<label class="form-label mb-1 d-flex align-items-center gap-1 justify-content-end">
                <input class="" type="checkbox" name="contract_status_id" value="'._CONTRACT_STATUS_DONE_ID.'" '.($contract_status_id == _CONTRACT_STATUS_DONE_ID ? "checked" : "").' >
                <span>'.$arr_status_contract_cached[_CONTRACT_STATUS_DONE_ID]["title"].'</span>
            </label>
        </div>';
	}
	if($open_from == '_mobile'){
		$html.= '</div>
			<div class="modal-footer">
				<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn flex-fill btn-primary" onclick="$Core.billing.update_field(this,event);" 
					billing_id="'.$billing_id.'" open_from="'.$open_from.'">'.$core->makeIcon('check', 'Cập nhật').' </button>
			</div>
		</form></div>';
		// Return
		echo json_encode(array(
			'uid' => $clsISO->getUniqid(),
			'html' => $html
		)); die();
	} else {
		$html.= '<div class="form-group">
				<button type="button" class="btn btn-primary" onclick="$Core.billing.update_field(this,event);" 
				billing_id="'.$billing_id.'" open_from="'.$open_from.'">'.$core->makeIcon('check', 'Cập nhật').' </button>
			</div>
		</form>';
		// Return
		echo  $html; die();
	}
}
function default_update_field(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$billing_id = (int) Input::post('billing_id');
	$otp_date = Input::post('otp_date');
	$otp_customer = Input::post('otp_customer');
	$otp_reg_link = Input::post('otp_reg_link');
	$agree_date = Input::post('agree_date');
	$estimate_date = Input::post('estimate_date');
	$contract_date = Input::post('contract_date');
	$rescheduling_reason = Input::post('rescheduling_reason');
	$otp_date_in = !empty($otp_date) ? $clsISO->toTime($otp_date) : 0;
	$agree_date_in = !empty($agree_date) ? $clsISO->toTime($agree_date) : 0;
	$estimate_date_in = !empty($estimate_date) ? $clsISO->toTime($estimate_date) : 0;
	$contract_date_in = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
	$opt_status_id = (int) Input::post('opt_status_id', 0);
	$agree_status_id = (int) Input::post('agree_status_id', 0);
	$contract_status_id = (int) Input::post('contract_status_id', 0);
	if(!$contract_status_id) $contract_status_id = _CONTRACT_STATUS_WAIT_ID;
	##
	$msg = "_error";
	$field = "`billing_type`,`stock_code`,`logs`,`agree_date`,`opt_date`,`estimate_date`,`contract_date`,`more_information`";
	$oBilling = $clsBilling->getOne($billing_id, $field);
	$logs = $oBilling['logs'];
	$stock_code = $oBilling['stock_code'];
	$billing_type = $oBilling['billing_type'];
	$more_information = $oBilling['more_information'];
	$logs = $clsISO->to_array_json($logs);
	$more_information = $clsISO->to_array_json($more_information);
	if(!empty($rescheduling_reason)) {
		$more_information["rescheduling_reason"][$clsISO->getUniqid()] = [
			"user_id"	=>	$profile_id,
			"note"	=>	addslashes($rescheduling_reason),
			"reg_date"	=> time()
		];
	}	
	$is_changed = 0; $action_logs = array();
	if(!empty($agree_date_in) && $oBilling['agree_date'] != $agree_date_in){
		$is_changed = 1;
		$action_logs[] = 'ngày ký VBTT: ' .  $clsISO->convertTimeToText($agree_date_in);
	}
	if(!empty($otp_date_in) && $oBilling['opt_date'] != $otp_date_in){
		$is_changed = 1;
		$action_logs[] = 'ngày ký OTP: ' .  $clsISO->convertTimeToText($agree_date_in);
	}
	if(!empty($estimate_date_in) && $oBilling['estimate_date'] != $estimate_date_in){
		$is_changed = 1;
		$action_logs[] = 'ngày ký dự kiến ký HĐMB: ' .  $clsISO->convertTimeToText($estimate_date_in);
	}
	if(!empty($contract_date_in) && $oBilling['contract_date'] != $contract_date_in){
		$is_changed = 1;
		$action_logs[] = 'ngày ký HĐMB: ' . $clsISO->convertTimeToText($contract_date_in);
	}
	if($is_changed == 1 && !empty($action_logs)){
		$content = sprintf('%s thay đổi %s', $clsProfile->getFullName($profile_id, $oneProfile), implode(', ', $action_logs));
		$logs[$clsISO->getUniqid()] = array(
			'profile_id' => $profile_id,
			'reg_date' => time(),
			'content' => $content
		);
	}
	$more_information['opt_date'] = $otp_date_in;
	$more_information['otp_customer'] = $otp_customer;
	$more_information['otp_reg_link'] = $otp_reg_link;
	$more_information['agree_date'] = $agree_date_in;
	$more_information['estimate_date'] = $estimate_date_in;
	$more_information['contract_date'] = $contract_date_in;
	if($clsBilling->updateOne($billing_id, array(
		'opt_date' => $otp_date_in,
		'agree_date' => $agree_date_in,
		'estimate_date' => $estimate_date_in,
		'contract_date' => $contract_date_in,
		'opt_status_id' => $opt_status_id,
		'agree_status_id' => $agree_status_id,
		'contract_status_id' => $contract_status_id,
		'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
	))){
		$msg = "_success";
		$clsStockHug = new StockHug();
		if($billing_type == _BILLING_TYPE_MWF_ID && !empty($stock_code)){
			$oneStockHug = $clsStockHug->getByCond("`stock_code`='{$stock_code}'", $clsStockHug->pkey);
			$clsStockHug->updateOne($oneStockHug[$clsStockHug->pkey], array(
				'otp_date' => $otp_date_in
			));
		}
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Billing","update");
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'agree_date' => ($agree_date_in > 0) ? $agree_date : "---",
		'estimate_date' => ($estimate_date_in > 0) ? $estimate_date : "---",
		'contract_date' => ($contract_date_in > 0) ? $contract_date : "---"
	));
}
function default_list_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	####
	$_results = array();
	$holderG = Input::get('holderG',"all");
	$cond= "`is_trash`=0";
	if($holderG == 'permiss' || $holderG = 'active'){
		$cond.= " AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		if($clsISO->checkPermissionGroup('BO')){
			// BO
		} else if($clsISO->checkPermissionGroup('SALE')) {
			$department_id = $oneProfile['department_id'];
			$cond.= " AND (`department_id`='{$department_id}' OR `list_department_id` like '%|{$department_id}|%')";
		}
	}else if($holderG == "admin") {
		$cond.= " AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND `role_id` = '"._ROLE_STAFF_ADMIN."'";
	}
	$field = "{$clsProfile->pkey},`code`,`full_name`,`avatar`,`status_id`";
	$tmp = $clsProfile->getAll("{$cond} order by `reg_date` DESC", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$_results[] = array(
				'id' => $val[$clsProfile->pkey],
				'text' => sprintf('%s-%s', $val['code'], $val['full_name']) . ($val['status_id'] == _STATUS_STAFF_OFF_ID ? '<span class="badge bg-label-danger text-nowrap ml-1">Đã nghỉ</span>' : ''),
				'image' => $clsProfile->getAvatar($val[$clsProfile->pkey], $val, 40, 40)
			);
		}
		unset($tmp);
	}
	// return
	echo json_encode($_results); die();
}
function default_list_staffs_group(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	###
	$_results = array();
	$field = "{$clsGroupProfile->pkey},`title`";
	$list_groups = $clsGroupProfile->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	if(!empty($list_groups)){
		foreach($list_groups as $key => $val){
			$_results[] = array(
				'id' => $val[$clsGroupProfile->pkey],
				'text' => $val['title']
			);
		}
		unset($list_groups);
	}
	// Return
	echo json_encode($_results); die();
}
function default_list_project(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProject = new Project();
	####
	$_results = array();
	$tp = Input::get('tp', 'full');
	$field = "{$clsProject->pkey},code,title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	if(!empty($list_projects)){
		foreach($list_projects as $key => $val){
			$field = ($tp=='short') ? 'code' : 'title';
			$_results[] = array(
				'id' => $val[$clsProject->pkey],
				'text' => $val[$field]
			);
			unset($list_projects);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_list_customer(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsCustomer = new Customer();
	####
	$_results = array();
	$field = "{$clsCustomer->pkey},name";
	$list_customers = $clsCustomer->getAll("is_trash=0 order by reg_date ASC", $field);
	if(!empty($list_customers)){
		foreach($list_customers as $key => $val){
			$_results[] = array(
				'id' => $val[$clsCustomer->pkey],
				'text' =>$val['name']
			);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_open_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsCity = new City();
	$clsSetting = new Setting();
	$clsCountry = new Country();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	###
	$cond = "`is_trash`=0";
	$field = "{$clsCampaign->pkey},title";
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	$html_assign_def_options = "";
	if($department_id == _DEPARTMENT_MKT_ID || $clsISO->checkPermissionGroup('MARKETER')){
		$cond.= " and (`user_id` in (
			select `profile_id` from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='"._DEPARTMENT_MKT_ID."'
		) OR `use_globe`=1)";
		$arr_profile_cached = $clsProfile->getProfileCached();
		if(!empty($arr_profile_cached)){
			foreach($arr_profile_cached as $key => $val){
				if(in_array($val[$clsProfile->pkey], [_PROFILE_LTD_ID, _PROFILE_PVD_ID, _PROFILE_CEO_ID])){
					$html_assign_def_options.= '<option selected="selected" value="'.$val[$clsProfile->pkey].'">
						'.$clsProfile->getFullName($val[$clsProfile->pkey], $val).'
					</option>';
				}
			}
		}
	} else {
		$cond.= " and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	}
	$list_campaigns = $clsCampaign->getAll("{$cond} and `campaign_type`='_campaign' order by `reg_date` DESC", $field);
	$smarty->assign('list_campaigns', $list_campaigns); unset($list_campaigns);
	$smarty->assign('html_assign_def_options', $html_assign_def_options); unset($html_assign_def_options);
	$arr_projects = $clsSetting->getCacheItems('_PROJECT');
	$smarty->assign('arr_projects', $arr_projects); unset($arr_projects);
	$arr_bedrooms = $clsProperty->getAll("`is_trash`=0 AND `property_type` IN ('_BEDROOM', '_TYPE_VILLA')", "{$clsProperty->pkey},`title`");
	$smarty->assign('arr_bedrooms', $arr_bedrooms); unset($arr_bedrooms);
	// $clsISO->print_pre($arr_bedrooms); die();
	###
	$uid = Input::post('uid');
	$openFrom = Input::post('openFrom','_global');
	$smarty->assign('uid', $uid);
	$smarty->assign('openFrom', $openFrom);
	$smarty->assign('clsCity', $clsCity);
	$smarty->assign('clsCountry', $clsCountry);
	$smarty->assign('clsProperty', $clsProperty);
	$_ss_storage = array(
		'status_id' => 0,
		'blocktype_id' => 0,
		'list_block_id' => array(),
		'list_bedroom_id' => array(),
		'resource_id' => _CRM_RESOURCE_ADS_ID
	);
	if(vnSessionExist('_ss_storage')){
		$_ss_storage = vnSessionGetVar('_ss_storage');
	}
	$smarty->assign('_ss_storage', $_ss_storage);
	// Return
	$html = $core->build('_ajax.customer.tpl');
	echo json_encode(array(
		'uid' => 'modal'.$clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function default_pop_save_customer(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $oneProfile,$profile_id,$clsProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerHistory = new CustomerHistory();
	$clsProperty = new Property();
	$clsNotification = new Notification();
	$customer_id = (int) Input::post('customer_id', 0);
	###
	$name = Input::post('name');
	$address = Input::post('address');
	$phone = Input::post('phone');
	$email = Input::post('email');
	$notes = Input::post('notes');
	$facebook = Input::post('facebook');
	$tiktok = Input::post('tiktok');
	$begin_need = Input::post('begin_need');
	$agent_id = (int) Input::post('agent_id', 0);
	$admin_id = (int) Input::post('admin_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$list_block_id = Input::post('list_block_id');
	$list_share_id  = Input::post('list_share_id', []);
	$list_bedroom_id = Input::post('list_bedroom_id');
	$list_campaign_id = Input::post('list_campaign_id');
	$list_need_id = Input::post('list_need_id', []);
	$list_type_id = Input::post('list_type_id', []);
	$list_purpose_id = Input::post('list_purpose_id', []);
	$list_stock_id = Input::post('list_stock_id', []);
	$list_tags_id = Input::post('list_tags_id', []);
	$auto_create_followups = (int) Input::post('auto_create_followups', 0);
	###
	$more_information = $more = array();
	$list_user_notify = $list_share_id;
	if($admin_id != $profile_id){
		$list_share_id[] = $profile_id;
	}
	###
	if(!empty($notes)){
		$more['notes'] = json_encode(array(
			[$clsISO->getUniqid()] => array(
				'content' => $notes,
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			)
		), JSON_UNESCAPED_UNICODE);
	}
	###
	$msg = "_error";
	if(!empty($phone) && $clsCustomer->countItem("`phone`='{$phone}' and `admin_id`='{$admin_id}'")){
		$msg = "_duplicated";
	} else {
		$_ss_storage = array();
		foreach($_POST as $key => $val){
			if(in_array($key, array('resource_id','status_id','list_bedroom_id','blocktype_id','list_block_id'))){
				$_ss_storage[$key] = $val;
			}
		}
		vnSessionSetVar('_ss_storage', $_ss_storage);
		$customer_id = $clsCustomer->getMaxId();
		$list_share_id = !empty($list_share_id) ? $clsISO->makeSlashListFromArray($list_share_id) : "";
		$use_globe = !empty($list_share_id) ? 1 : 0;
		$content = sprintf('<strong>%s</strong> đã tạo mới khách hàng', $clsProfile->getFullName($profile_id, $oneProfile));
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' => $content
		);
		$more_information['tiktok'] = $tiktok;
		$more_information['facebook'] = $facebook;
		$more_information['agent_id'] = $agent_id;
		$more_information['action_logs'] = $action_logs;
		// $dbconn->debug = true;
		if($clsCustomer->insert(array_merge($more, array(
			$clsCustomer->pkey => $customer_id,
			'name' => $name,
			'name_slug' => $core->replaceSpace($name),
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'admin_id' => $admin_id,
			'status_id' => $status_id,
			'use_globe' => $use_globe,
			'begin_need' => $begin_need,
			'blocktype_id' => Input::post('blocktype_id', 0),
			'country_id' => (int) Input::post('country_id', 0),
			'city_id' => (int) Input::post('city_id', 0),
			'resource_id' => (int) Input::post('resource_id', 0),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		)))){
			$msg = "_success";						
			$clsCustomerMeta = new CustomerMeta();
			$share_ids = $clsCustomer->normalizeIdArray($list_user_notify);
			$campaign_ids = $clsCustomer->normalizeIdArray($list_campaign_id);
			$need_ids = $clsCustomer->normalizeIdArray($list_need_id);
			$type_ids = $clsCustomer->normalizeIdArray($list_type_id);
			$purpose_ids = $clsCustomer->normalizeIdArray($list_purpose_id);
			$block_ids = $clsCustomer->normalizeIdArray($list_block_id);
			$stock_ids = $clsCustomer->normalizeIdArray($list_stock_id);
			$bedroom_ids = $clsCustomer->normalizeIdArray($list_bedroom_id);
			$tag_ids = $clsCustomer->normalizeIdArray($list_tags_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $share_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'campaign', $campaign_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'need', $need_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'type', $type_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'purpose', $purpose_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'block', $block_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'stock', $stock_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'bedroom', $bedroom_ids, $profile_id);
			$clsCustomerMeta->syncByCustomerType($customer_id, 'tag', $tag_ids, $profile_id);
			// MKT tạo + nguồn Quảng Cáo → auto-thêm Huynh (_PROFILE_PTH_ID) làm người liên quan
			$clsCustomer->ensureMarketingAdsShare($customer_id, (int) Input::post('resource_id', 0));
			#status log
			$clsCustomerHistory->insert(array(
				'customer_id' => $customer_id,
				'from_status_id' => 0,
				'to_status_id' => $status_id,
				'staff_id' => $profile_id,
				'action_date' => time()
			));
			#activity log	
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Customer","insert",$_POST);
			if($auto_create_followups == 1){
				$clsFollowUp = new FollowUp();
				$timer = strtotime(date('d-m-Y'));
				$list_times = array(
					'1day' => strtotime('+1 day', $timer),
					'2day' => strtotime('+2 days', $timer),
					'5day' => strtotime('+5 days', $timer),
					'15day' => strtotime('+15 days', $timer),
					'30day' => strtotime('+30 days', $timer)
				);
				foreach($list_times as $key => $date_id){
					$followup_id = $clsFollowUp->getMaxId();
					if($clsFollowUp->insert(array(
						$clsFollowUp->pkey => $followup_id,
						'type_id' => _FOLLOWUP_CALL_ID,
						'status_id' => _FOLLOWUP_STATUS_PLAN_ID,
						'customer_id' => $customer_id,
						'date_id' => $date_id,
						'intro' => 'Call liên hệ lại khách',
						'admin_id' => $admin_id,
						'user_id' => $profile_id,
						'user_id_update' => $profile_id,
						'reg_date' => time(),
						'upd_date' => time()
					))){
						$titleNoty = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProperty->getTitle(_FOLLOWUP_CALL_ID) .": Call liên hệ lại khách", $clsCustomer->getName($customer_id), $clsISO->convertTimeToText($date_id, true));
						$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNoty, $date_id, '|'.$admin_id.'|');
						#thong bao app
						$params = [
							'title' => "CRM - Lịch hẹn",
							'body' => strip_tags($titleNoty),
							'link' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $customer_id)
						];
						$clsNotification->doPushMessagingUser($params,[$admin_id]);
					}
				}
			}
			$oCustomer = $clsCustomer->getOne($customer_id);	
			if($admin_id != $profile_id){
				$titleNoty = sprintf('<strong>%s</strong> Đã giao cho bạn phụ trách khách hàng <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer));
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),"|".$admin_id."|");
				if(!empty($list_user_notify) && $admin_id > 0) {
					$list_user_notify = array_diff($list_user_notify, array($admin_id));
				}
				#thong bao app
				$params = [
					'title' => "CRM - Khách hàng",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
				];
				$clsNotification->doPushMessagingUser($params,[$admin_id]);
				#
				$clsZalo = new Zalo();
				$customer_insert = [
					[
						"customer_name"	=>	$name,
						"phone"	=>	$phone,
						"begin_need"	=>	$begin_need,
					]
				];
				$clsZalo->sendNotifyCRMZalo($admin_id, [], 1, $customer_insert);
			}
			if(!empty($list_user_notify)){
				$titleNoty = sprintf('<strong>%s</strong> đã thêm bạn theo dõi khách hàng <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer));
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),$list_user_notify);
				#thong bao app
				$params = [
					'title' => "CRM - Khách hàng",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
				];
				$clsNotification->doPushMessagingUser($params,$list_user_notify);
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'id' => $customer_id,
		'name' => $name
	)); die();
}
function default_open_news(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	$clsProject = new Project();
	$uid = $clsISO->getUniqid();
	$gid = Input::post('gid', "");
	$cat_id = (int) Input::post('cat_id', 0);
	$news_id = (int) Input::post('news_id', 0);
	$action = Input::post('action', "_detail");
	#
	$arr_cat_mocs = $arr_ca_cats = array();
	$arr_ca_cats = $clsProperty->getArraySearchByKey("_NEWS_CATEGORY");
	$arr_project_tag = $clsSetting->getArraySearchByKey("_PROJECT");
	$clsProperty->makeOption(_CAT_MOC_ID, "_NEWS_CATEGORY", 0, $arr_cat_mocs);
	$smarty->assign('arr_ca_cats', $arr_ca_cats);
	$smarty->assign('arr_cat_mocs', $arr_cat_mocs);
	#
	$is_registed = 0;
	$oneNews = $list_images = array();
	if($news_id > 0 || $holderG == '_detail'){
		$oneNews = $clsNews->getOne($news_id);
		$images = $oneNews['images'];
		$more_information = $oneNews['more_information'];
		$list_profile_read = $oneNews['list_profile_read'];
		$list_profile_read = $clsISO->getArrayByTextSlash($list_profile_read);
		$clsNews->updateOne($news_id, "`view_num`=`view_num`+1");
		if(!in_array($profile_id, $list_profile_read)){
			$list_profile_read[] = $profile_id;
			$clsNews->updateOne($news_id, array(
				'list_profile_read' => $clsISO->makeSlashListFromArray($list_profile_read)
			));
		}
		$listProjectIds = $clsISO->getArrayByTextSlash($oneNews["list_project_ids"]);
		$oneNews['listProjectIds'] = $listProjectIds;
		$project_tags = [];
		if(!empty($listProjectIds)) {
			foreach($listProjectIds as $k => $project_tag_id) {
				if(isset($arr_project_tag[$project_tag_id]))
				$project_tags[] = $arr_project_tag[$project_tag_id]["title"];
			}
		}
		$oneNews['project_tags'] = $project_tags;
		$list_images = $clsISO->to_array_json($images);
		$more_information = $clsISO->to_array_json($more_information);
		$lst_tags = $core->get_field($more_information, "lst_tags", "");
		$lst_tags = !empty($lst_tags) ? $clsISO->getArrayByTextSlash($lst_tags) : [];
		$oneNews["show_pop"] = $core->get_field($more_information, "show_pop", 0);
		$oneNews["is_send_email"] = $core->get_field($more_information, "is_send_email", 0);
		$oneNews["is_post_moc"] = $core->get_field($more_information, "is_post_moc", 0);
		$oneNews["cat_moc_id"] = $core->get_field($more_information, "cat_moc_id", 0);
		$oneNews["api_posts_fh"] = $core->get_field($more_information, "api_posts_fh", 0);
		$arr_tag = [];
		$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
		$arr_building = $clsProperty->getArraySearchByKey("_BUILDING");
		$arr_cache_project = $arr_cache = array();
		foreach($lst_tags as $k=>$val) {
			$tmp = explode("-",$val);
			if(count($tmp) == 2) {
				if($tmp[0] == "DA"){
					if(!isset($arr_cache_project[$tmp[1]])) {
						$arr_cache_project[$tmp[1]] = $clsProject->getOne($tmp[1],"title,slug");
					}
					$oneProject = $arr_cache_project[$tmp[1]];
					$arr_tag[] = [
						"project_id"	=>	$tmp[1],
						"title"			=>	$clsProject->getTitle($tmp[1],$oneProject),
						"link"			=>	$clsProject->getLinkDetail($tmp[1],0,0,$oneProject)
					];
				}else if($tmp[0] == "PK") {
					$oneBlock = $arr_block[$tmp[1]];
					if(!isset($arr_cache_project[$oneBlock["for_id"]])) {
						$arr_cache_project[$oneBlock["for_id"]] = $clsProject->getOne($oneBlock["for_id"],"title,slug");
					}
					$oneProject = $arr_cache_project[$oneBlock["for_id"]];
					$arr_tag[] = [
						"block_id"		=>	$tmp[1],
						"title"			=>	$clsProperty->getTitle($tmp[1],$oneBlock),
						"link"			=>	$clsProject->getLinkDetail($oneBlock["for_id"],$tmp[1],0,$oneProject)
					];
				}else if($tmp[0] == "TD") {
					$oneBuilding = $arr_building[$tmp[1]];
					$oneBlock = $arr_block[$oneBuilding['for_id']];
					if(!isset($arr_cache_project[$oneBlock["for_id"]])) {
						$arr_cache_project[$oneBlock["for_id"]] = $clsProject->getOne($oneBlock["for_id"],"title,slug");
					}
					$oneProject = $arr_cache_project[$oneBlock["for_id"]];
					$arr_tag[] = [
						"block_id"		=>	$tmp[1],
						"title"			=>	$clsProperty->getTitle($tmp[1],$oneBuilding),
						"link"			=>	$clsProject->getLinkDetail($oneBlock["for_id"],$oneBuilding["for_id"],$tmp[1],$oneProject)
					];
				}
			}
		}
		if($action=='_detail'){
			$user_id = $oneNews['user_id'];
			$db_profile = $clsProfile->getProfile($user_id);
			$oneNews['db_profile'] = $db_profile;
			$status_liked = $clsNews->checkLiked($news_id, $oneNews);			
			$smarty->assign('status_liked', $status_liked);
		}
		$liked_json = $oneNews['liked_json'];
		$liked_json = $clsISO->to_array_json($liked_json);
		$liked = $core->get_field($liked_json, "like", []);
		$arr_liked = array_unique($liked);
		$total_liked = count($arr_liked);
		$total_comments = $clsNews->getTotalComment($oneNews[$clsNews->pkey]);
		$oneNews['total_comments'] = $total_comments;
		$oneNews['total_actions'] = $total_liked + $total_comments;
		$oneNews['attachments'] = !empty($more_information['attachments']) ? $more_information['attachments'] : array();
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('arr_tag', $arr_tag);
	$smarty->assign('lst_tags', $lst_tags);
	$smarty->assign('news_id', $news_id);
	$smarty->assign('gid', $gid);
	$smarty->assign('action', $action);
	$smarty->assign('oneNews', $oneNews);
	$smarty->assign('is_registed', $is_registed);
	$smarty->assign('list_images', $list_images);
	$smarty->assign('clsProject ', $clsProject );
	$smarty->assign('clsSetting ', $clsSetting );
	$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`image`,`more_information`";
	$lstProjects = $clsProject->getAll("`is_menu`='1' order by `reg_date` ASC", $field);
	$smarty->assign('lstProjects', $lstProjects);
	$lstProjectTag = $clsSetting->getArraySearchByKey("_PROJECT");
	$smarty->assign('lstProjectTag', $lstProjectTag);
	if($action == "_add"){
		$project_id = (int) Input::post('project_id', 0);
		$block_id = (int) Input::post('block_id', 0);	
		$lst_tags = ["DA-".$project_id,"PK-".$block_id];
		$smarty->assign('lst_tags', $lst_tags);
	}	
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.news.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'list_images' => $list_images,
	)); die();
}
function get_remote_image($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $data = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return false;
    }
    curl_close($ch);
    return $data;
}
function default_pop_save_news(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsNotify = new Notify();
	$clsNotification = new Notification();		
	###
	$news_id = (int) Input::post('news_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	$show_pop = (int) Input::post('show_pop', 0);
	$is_post_moc = (int) Input::post('is_post_moc', 0);
	$cat_moc_id = (int) Input::post('cat_moc_id', 0);
	$api_posts_fh = (int) Input::post('api_posts_fh', 0);
	$is_online = (int) Input::post('is_online', 0);
	$is_send_email = (int) Input::post('is_send_email', 0);
	$lst_tags = Input::post('lst_tags', array());
	$list_tags = $lst_tags;
	$lst_tags = $clsISO->makeSlashListFromArrayRoot($lst_tags);
	$project_ids = Input::post('project_ids', []);
	$list_project_ids = $clsISO->makeSlashListFromArray($project_ids);
	$title = Input::post('title');
	$content = trim($_POST['content']);
	$images = Input::post('images', []);	
	$more = array(); $msg = "_error";
	if($news_id > 0){
		$attachments_more = array();
		if(!empty($_FILES['attachments']['name'])){
			$clsUploadFile = new UploadFile();
			$clsGoogleDrive = new GoogleDrive();
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(is_uploaded_file($_FILES['attachments']['tmp_name'][$i])){
					$image = $clsUploadFile->uploadItem($file,"/news",EXTENSION_FILE_UPLOAD);
					if(!empty($image) && file_exists(ROOTPATH . $image)){
						// Set the file metadata for drive
						$name = $file["name"];
						$mimeType = $file["type"];
						$createdFile = $clsGoogleDrive->upload($name, $mimeType, ROOTPATH.$image);
						$file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
						$attachments_more[] = array('name' => $name, 'mimeType' => $mimeType, 'url' => $file);
						@unlink(ROOTPATH . $image);
					}
				}
			}
		}
		$oneNews = $clsNews->getOne($news_id);
		$more_information = $oneNews['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$attachments = $core->get_field($more_information, "attachments", []);
		$more_information['lst_tags'] = $lst_tags;
		$more_information['show_pop'] = $show_pop;
		$more_information['send_email'] = $send_email;
		$more_information['is_post_moc'] = $is_post_moc;
		$more_information['cat_moc_id'] = $cat_moc_id;
		$more_information['api_posts_fh'] = $api_posts_fh;
		$more_information["attachments"] = array_merge($attachments, $attachments_more);
		if($clsNews->updateOne($news_id, array_merge($more, array(
			'cat_id' => $cat_id,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'content' => $content,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'list_tags' => $lst_tags,
			'list_project_ids' => $list_project_ids,
			'upd_date' => time(),
			'is_online' => $is_online,
			'user_id_update' => $profile_id
		)))){
			$msg = '_success';
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("News","update",$_POST);
		}
	} else {
		$order_no = $clsNews->getMaxOrderNo();
		$more_information = array();
		$more_information['lst_tags'] = $lst_tags;
		$more_information['show_pop'] = $show_pop;
		$more_information['send_email'] = $send_email;
		$more_information['is_post_moc'] = $is_post_moc;
		$more_information['cat_moc_id'] = $cat_moc_id;
		$more_information['api_posts_fh'] = $api_posts_fh;
		#
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){	
			$clsUploadFile = new UploadFile();
			$clsGoogleDrive = new GoogleDrive();
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(is_uploaded_file($_FILES['attachments']['tmp_name'][$i])){
					$image = $clsUploadFile->uploadItem($file,"/news",EXTENSION_FILE_UPLOAD);
					if(!empty($image) && file_exists(ROOTPATH . $image)){
						// Set the file metadata for drive
						$name = $file["name"];
						$mimeType = $file["type"];
						$createdFile = $clsGoogleDrive->upload($name, $mimeType, ROOTPATH.$image,GOOGLE_DRIVE_FOLDER_ATTACHMENTS_ID);
						$file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
						$attachments[] = array(
							'name' => $name,
							'mimeType' => $mimeType,
							'url' => $file
						);
						@unlink(ROOTPATH . $image);
					}
				}
			}
		}
		$more_information["attachments"] = $attachments;
		// $dbconn->debug = true;
		$news_id = $clsNews->getMaxId();
		$list_profile_read = sprintf('|%s|', $profile_id);	
		if($clsNews->insert(array_merge($more, array(
			$clsNews->pkey => $news_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'cat_id' => $cat_id,
			'domain_id' => _NEWS_CAT_SITE_ID,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'content' => $content,
			'order_no' => $order_no,
			'list_profile_read' => $list_profile_read,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information , JSON_UNESCAPED_UNICODE),
			'list_tags' => $lst_tags,
			'is_online' => $is_online,
			'reg_date' => time()
		)))){
			$msg = '_success';
			$list_user_notify = array();
			$arr_profile = $clsProfile->getProfileCached('active');
			if(!empty($arr_profile)){
				foreach	($arr_profile as $key => $value){
					$list_user_notify[] = $value['profile_id'];
				}
			}
			if(!empty($list_user_notify)){
				$titleNotify = sprintf('<strong>%s</strong> đã đăng <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $title);
				$clsNotify->insertNotify('News',$clsNews->pkey, $news_id, $titleNotify, time(), $list_user_notify);	
				#thong bao app
				$params = [
					'title' => "Bản tin FG",
					'body' => strip_tags($title),
					'link' => PCMS_URL . $clsNews->getLink($news_id)
				];
				$clsNotification->doPushMessagingUser($params,$list_user_notify);	
			}
			$clsNotify->send_notification(array(
				'title' => sprintf('Bản tin FG'),
				'message' => strip_tags($title),
				'url' => PCMS_URL . $clsNews->getLink($news_id)
			));						
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("News","insert",$_POST);
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_news(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile,$clsISO;
	$clsNews = new News();
	###
	$msg = '_error';
	$news_id = (int) Input::post('news_id', 0);
	$oneItem = $clsNews->getOne($news_id,"title,images,more_information");
	$images = $clsISO->to_array_json($oneItem['images']);
	if($clsNews->updateOne($news_id, array(
		'is_trash' => 1,
		'user_id_update' => $profile_id
	))){
		if(!empty($images)){
			foreach($images as $img){
				if(!empty($img) && file_exists(ROOTPATH . $img)){
					@unlink(ROOTPATH . $img);
				}
			}
		}
		$msg = '_success';	
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("News","delete",$oneItem);
	}
	// Return
	echo $msg; die();
}
function default_delete_share(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile,$clsISO;
	$msg = '_error';
	$clsShare = new Share();
	$share_id = (int) Input::post('share_id', 0);
	$oneItem = $clsShare->getOne($share_id,"title,share_type,images,more_information");
	$images = $clsISO->to_array_json($oneItem['images']);
	if($clsShare->deleteOne($share_id)){
		if(!empty($images)){
			foreach($images as $img){
				if(!empty($img) && file_exists(ROOTPATH . $img)){
					@unlink(ROOTPATH . $img);
				}
			}
		}
		$msg = '_success';	
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Share","delete",$oneItem);
	}
	// Return
	echo $msg; die();
}
/** COMMENT */
function default_add_comment(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$oneProfile;
	$clsISO = new ISO();
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsComment = new Comment();	
	$clsFcmToken = new FcmToken();
	###
	$msg = '_error';
	if(isset($_POST['submit']) && $_POST['submit']=='comment'){
		$timer = time();
		$type = Input::post('type', "text");
		$holderG = Input::post('holderG', "_comment");
		$clsTable = Input::post('clsTable', 'News');
		$table_id = (int) Input::post('table_id', 0);
		$parent_id = (int) Input::post('parent_id', 0);
		$message = Input::post('message', "");// Nội dung bình luận
		###
		$clsClassTable = new $clsTable();
		$field = "`title`,`slug`,`user_id`";
		$oneTable = $clsClassTable->getOne($table_id, $field);
		$user_created_id = $oneTable['user_id']; // Id của người đăng tin
		#
		$is_image = 0; $image = "";
		if($type == "file"){
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$image = $clsUploadFile->uploadItem($_FILES["image"],"/COURSE/comment",EXTENSION_FILE_UPLOAD);
				if(!empty($image) && file_exists(ROOTPATH . $image)){
					$is_image = 1;
					// Set the file metadata for drive
					$title = $_FILES["image"]["name"];
					$mimeType = $_FILES["image"]["type"];
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_POSTER_ID);
					@unlink(ROOTPATH . $image);
					$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					$image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
					$message = "<img class='radius-4' src='".$image."' title='comment' style='max-width:250px'>";
				}
			}
		}
		$comment_id = $clsComment->getMaxId();
		if($clsComment->insert(array(
			'comment_id' 	=> $comment_id,
			'profile_id' 	=> $profile_id,
			'table_id' 		=> $table_id,
			'clsTable' 		=> $clsTable,
			'parent_id' 	=> $parent_id,
			'content'		=> $message,
			'reg_date'		=> $timer,
			'image'			=> $image,
			'is_image'		=> $is_image,
			'order_no'		=> $clsComment->getMaxOrderNo(),
			'is_active'		=> 1
		))){			
			$total_comments = $clsComment->getTotalComment($table_id, $clsTable);
			$msg = '_success|||<div class="awe__comment-item'.($holderG=='_reply'?' awe__comment-reply-group-'.$parent_id:'').'">
				<div class="d-flex w-100">
					<div class="awe__profile-avatar" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$profile_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="350">
						<img class="rounded-pill" src="'.$clsProfile->getAvatar($profile_id, $oneProfile).'" width="30" height="30" />
					</div>
					<div class="awe__comment-item-body">
						<div class="bg-lighter p-3 rounded-3">
							<div class="awe__comment-profile d-flex mb-2">
								<h4 class="awe__comment-name mr-2 fs-14">'.$clsProfile->getFullName($profile_id, $oneProfile).'</h4>
								<span class="awe__comment-time text-muted fs-12">
									<i class="bx bx-timer"></i> '.$clsISO->getTimeAgo($timer).'
								</span>
							</div>
							<div class="awe__comment-content">'.nl2br($message).'</div>
						</div>
						<div class="awe__comment-item-action d-flex align-items-center mt-1">
							<a href="javascript:void(0);" class="control-action'.($action=='like' ? ' liked': '').'" comment_id="'.$comment_id.'" onclick="$Core.comment.like(this, event)" data-name="like" ><i class="bx bx-like me-1"></i><span class="awe__comment-total-liked">0</span></a>
							'.($holderG=='_comment'?'<a href="javascript:void(0);" class="awe__comment-reply-button" onclick="$Core.news.comment_reply(this, event)" table_id="'.$table_id.'" clsTable="'.$clsTable.'" comment_id="'.$comment_id.'"><span>'.$core->makeIcon('angle-down', $core->get_Lang('Reply')).'</span></a>':'').'
						</div>
						'.($holderG=='_comment'?'
						<div class="awe__comment-reply-form awe__comment-reply-form-'.$comment_id.'"></div>
						<div class="awe__comment-reply-wrapper awe__comment-reply-wrapper-'.$comment_id.'"></div>':'').'
					</div>
				</div>
			</div>|||'.$total_comments;
			$list_user_notify = array();
			if($holderG=='_reply'){
				$oneComment = $clsComment->getOne($parent_id, "profile_id");
				if($oneComment['profile_id'] != $profile_id){
					$list_user_notify = [$oneComment['profile_id']];
				}
				$titleNoty = sprintf('<strong>%s</strong> đã trả lời bình luận của bạn tại <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $oneTable['title']);
			}else{
				if($user_created_id != $profile_id){
					$list_user_notify = [$user_created_id];
				}
				$tmp = $clsComment->getAll("`is_trash`=0 and `is_active`=1 and `table_id`='{$table_id}' 
				and `{$clsProfile->pkey}`<>'{$profile_id}' and `clsTable`='{$clsTable}'", $clsProfile->pkey);
				if(!empty($tmp)){
					foreach	($tmp as $key => $value){
						$_profile_id = $value['profile_id'];
						if(!in_array($_profile_id, $list_user_notify)){
							$list_user_notify[] = $_profile_id;
						}						
					}
					unset($tmp);
				}
				$titleNoty = sprintf('<strong>%s</strong> đã bình luận bản tin <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $oneTable['title']);
			}
			if(!empty($list_user_notify) && $profile_id != 289){
				/** Gửi thông báo tới mới người*/
				$clsNotify->insertNotify($clsTable,$clsClassTable->pkey,$table_id,
					$titleNoty, $timer,sprintf('|%s|',implode('|',$list_user_notify)));
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Bản tin FG",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsClassTable->getLink($table_id)
				];
				$clsNotification->doPushMessagingUser($params,$list_user_notify);
				/** Gửi notification tới mọi người */
				$subscribers = array();
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
					and `user_id` in (".implode(',', $list_user_notify).") and `token`<>''", "token");	
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						if(!in_array($val['token'], $subscribers)){
							$subscribers[] = $val['token'];
						}
					}
					$clsNotify->send_subscriber_notification(array(
						'title' => "Bình luận bản tin FG",
						'message' => strip_tags($titleNoty),
						'url' => PCMS_URL . $clsClassTable->getLink($table_id)
					), $subscribers);
					unset($tmp);
				}
			}
		}
		// Return
		echo $msg; die();
	}
}
function default_list_comments(){	
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$clsISO,$clsConfiguration;
	global $extLang;
	#- Init Object
	$clsProfile = new Profile();
	$clsComment = new Comment();
	$clsCommentVote = new CommentVote();
	$smarty->assign('clsComment', $clsComment);
	$smarty->assign('clsCommentVote', $clsCommentVote);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	#- Params
	$action = Input::post('action', 'reload');
	$table_id = (int) Input::post('table_id', 0);
	$clsTable = Input::post('clsTable', "Post");
	$sort_by = Input::post('sort_by', "desc");
	$smarty->assign('action', $action);
	$smarty->assign('table_id', $table_id);
	$smarty->assign('clsTable', $clsTable);
	$smarty->assign('sort_by', $sort_by);
	#
	$cond = "`is_trash`=0 and is_active=1 and parent_id='0' 
	and table_id='{$table_id}' and clsTable='{$clsTable}'";
	#- Pagination
	$page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 6);
	$total_record = $clsComment->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$order_by = " order by reg_date DESC";
	if($sort_by=='asc'){
		$order_by = " order by reg_date ASC";
	}
	$list_comments = $clsComment->getAll($cond.$order_by.$limitCond);
	if(!empty($list_comments)){
		$arr_profile_cached = array();
		foreach($list_comments as $key => $val){
			$profile_id = $val['profile_id'];
			if(isset($arr_profile_cached[$profile_id])){
				$db_profile = $arr_profile_cached[$profile_id];
			} else {
				$db_profile = $clsProfile->getProfile($profile_id);
				$arr_profile_cached[$profile_id] = $db_profile;
			}
			$list_comments[$key]['db_profile'] = $db_profile;
			#
			$sql_string = "is_trash=0 and is_active='1' and table_id='{$table_id}' 
			and clsTable='{$clsTable}' and parent_id='".$val[$clsComment->pkey]."'";
			$total_replys = $clsComment->countItem($sql_string);
			$list_comments[$key]['total_replys'] = $total_replys;
		}
	}
	//$clsISO->print_pre($list_comments); die();
	$smarty->assign('list_comments', $list_comments);
	$smarty->assign('page', $page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('total_record', $total_record);
	// Return
	$html = $core->build('_ajax.list_comment.tpl');
	echo @json_encode(array(
		'html' => $html,
		'action' => $action,
		'total_comments' => $clsComment->getTotalComment($table_id, $clsTable)
	)); die();
}
function default_load_replies(){	
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page,$clsISO,$clsConfiguration;
	global $extLang;
	#- Init Object
	$clsProfile = new Profile();
	$clsComment = new Comment();
	$clsCommentVote = new CommentVote();
	$smarty->assign('clsComment', $clsComment);
	$smarty->assign('clsCommentVote', $clsCommentVote);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	#- Params
	$table_id = (int) Input::post('table_id', 0);
	$clsTable = Input::post('clsTable', 'Post');
	$comment_id = (int) Input::post('comment_id', 0);
	$page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 5);
	$smarty->assign('table_id', $table_id);
	$smarty->assign('clsTable', $clsTable);
	$smarty->assign('comment_id', $comment_id);
	#
	$cond = "is_trash=0 and is_active='1' and table_id='{$table_id}' and parent_id='{$comment_id}'";
	$total_record = $clsComment->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#
	$order_by = " order by order_no DESC";
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$list_reply = $clsComment->getAll($cond.$order_by.$limitCond);
	//$clsISO->print_pre($list_reply); die();
	if(!empty($list_reply)){
		foreach($list_reply as $key => $val){
			$profile_id = $val['profile_id'];
			if(isset($arr_profile_cached[$profile_id])){
				$db_profile = $arr_profile_cached[$profile_id];
			} else {
				$db_profile = $clsProfile->getProfile($profile_id);
				$arr_profile_cached[$profile_id] = $db_profile;
			}
			$list_reply[$key]['db_profile'] = $db_profile;
		}
	}
	$smarty->assign('list_reply', $list_reply);
	$smarty->assign('page', $page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('total_record', $total_record);
	// Return
	$html = $core->build('_ajax.list_reply.tpl');
	echo @json_encode(array(
		'html' => $html
	)); die();
}
function default_form_comment(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$clsConfiguration;
	global $loggedIn, $profile_id, $oneProfile;
	#
	$uid = $clsISO->getUniqid();
	$clsTable = Input::post('clsTable', 'Post');
	$table_id = (int) Input::post('table_id', 0);
	$parent_id	= (int) Input::post('comment_id', 0);
	$smarty->assign('clsTable', $clsTable);
	$smarty->assign('table_id', $table_id);
	$smarty->assign('parent_id', $parent_id);
	// Return
	$smarty->assign('uid', $uid);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.form.comment.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function default_comment_vote(){
	global $smarty,$core,$dbconn,$_LANG_ID,$clsISO,$deviceType,$now_day,$is_agent;
	$clsComment = new Comment();
	$clsCommentVote = new CommentVote();
	#
	$ip_log = $_SERVER['REMOTE_ADDR'];
	$comment_id = $_POST['comment_id'];
	#
	$msg = '_error';
	$lstcheck = $clsCommentVote->getAll("is_trash=0 and comment_id='{$comment_id}' and ip_vote='{$ip_log}'");
	if(!empty($lstcheck)){
		$msg = '_success|||unlike';
		$clsComment->updateOne($comment_id, "number_voted=number_voted-1");
		$clsCommentVote->deleteOne($lstcheck[0][$clsCommentVote->pkey]);
	} else {
		$msg = '_success|||like';
		$f = "comment_vote_id,comment_id,reg_date,is_trash,ip_vote";
		$v = "'".$clsCommentVote->getMaxId()."','{$comment_id}','".time()."','0','{$ip_log}'";
		$clsCommentVote->insertOne($f,$v);
		$clsComment->updateOne($comment_id, "number_voted=number_voted+1");
	}
	#--End
	echo $msg; die();	
}
function default_list_comment_more(){
	global $core,$assign_list,$profile_id,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $clsISO,$clsConfiguration,$clsProduct;
	$clsComment = new Comment(); $assign_list['clsComment'] = $clsComment;
	#
	$for_id = (int) Input::post('for_id', 0);
	$type_id = Input::post('type_id', '_review');
	$order_no = (int) Input::post('order_no', 0);
	$reg_date = (int) Input::post('reg_date', 0);
	$sort_type = Input::post('sort_type', 'desc');
	$number_star = (int) Input::post('number_star', 0);
	$assign_list['for_id'] = $for_id;
	$assign_list['type_id'] = $type_id;
	#---
	$where = "is_trash=0 and is_active=1 and for_id='{$for_id}' and type_id='{$type_id}'";
	if($number_star > 0){
		$where .= " and number_star='{$number_star}'";
	}
	if($sort_type=='asc'){
		$where .= " and order_no>'{$order_no}'";
	} else {
		$where .= " and order_no<'{$order_no}'";
	}
	//echo $where; die();
	$lstComment = $clsComment->getAll("{$where} order by order_no {$sort_type} limit 0,5");
	$assign_list['lstComment'] = $lstComment; unset($lstComment);
	// Return
	$assign_list['core'] = $core;
	$html = $core->build('list_more.tpl');
	echo $html; die();
}
function default_news(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$cat_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsSetting = new Setting();
	$smarty->assign('clsNews', $clsNews);
	$smarty->assign('clsProfile', $clsProperty);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	###
	$arr_project_tag = $clsSetting->getArraySearchByKey("_PROJECT");
	$cmd = Input::get("cmd","");
	$show = Input::get('show', "");
	$cat_id = (int) Input::get('cat_id', 0);
	$assign_list["show"] = $show;
	$assign_list["cat_id"] = $cat_id;
	$type = "_CAT";
	if($cat_id == _NEWS_CAT_CERTI_AGENCY_SITE_ID) {
		$type = "_CERTI_AGENCY";
	}
	$assign_list["type"] = $type;
	#
	if($show=="detail"){
		if(Input::exists('code', 'GET')){
			$code = Input::get('code');
			$news_id = $clsISO->base64url_decode($code);
		} else {
			$news_id = Input::get('news_id');
		}
		$scriptJs.= '<a class="autoclick_'.$news_id.'"" news_id="'.$news_id.'" action="_detail" onClick="open_news(this, event)" ></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					history.pushState("", "", "/ban-tin.html");
					$(\'.autoclick_'.$news_id.'\').trigger(\'click\');
				}, 500);
			})
		</script>';
	}
	$smarty->assign("scriptJs",$scriptJs);
	###
	$cond = "`is_trash`=0 AND IF(`user_id`={$profile_id},1=1,`is_online`=1) AND `post_type`='_news'";
	if($cat_id > 0) $cond.= " AND `cat_id`='{$cat_id}'";
	/** Pagination */
	$per_page = 10;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsNews->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	/** End pagination */
	$order_by = " ORDER BY `order_no` DESC";
	$field = "{$clsNews->pkey},title,content,images,user_id,reg_date,order_no,liked_json";
	$field.= ",`cat_id`,`more_information`,`view_num`,`domain_id`,`list_project_ids`";
	// $clsNews->setDeBug(1);
	$list_post = $clsNews->getAll($cond.$order_by.$limitCond, $field);
	$arr_cache_project = $arr_cache = array();
	if(!empty($list_post)){
		$arr_profile_cached = $clsProfile->getProfileCached();
		$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
		$arr_building = $clsProperty->getArraySearchByKey("_BUILDING");
		foreach($list_post as $key => $val){
			$news_id = $val[$clsNews->pkey];
			$cat_id = (int) $val['cat_id'];
			$user_id = (int) $val['user_id'];
			$liked_json = $val['liked_json'];
			$more_information = $val['more_information'];
			$liked_json = $clsISO->to_array_json($liked_json);
			$more_information = $clsISO->to_array_json($more_information);
			$listProjectTagIds = $clsISO->getArrayByTextSlash($val["list_project_ids"]);
			$project_tag = [];
			if(!empty($listProjectTagIds)) {
				foreach($listProjectTagIds as $k => $project_tag_id) {
					if(isset($arr_project_tag[$project_tag_id]))
					$project_tag[] = $arr_project_tag[$project_tag_id]["title"];
				}
			}
			$list_post[$key]["project_tags"] = $project_tag;
			#
			$oProfile = $arr_profile_cached[$user_id];
			$db_profile = $clsProfile->getProfile($user_id, $oProfile);
			$list_post[$key]['db_profile'] = $db_profile;
			#
			$total_liked = 0;
			if(!empty($liked_json)){
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$list_post[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($news_id, $val);
			$list_post[$key]['status_liked'] = $status_liked;
			##
			$total_comments = $clsNews->getTotalComment($news_id);
			$list_post[$key]['total_comments'] = $total_comments;
			$list_post[$key]['total_actions'] = $total_liked + $total_comments;
			$list_post[$key]['link'] = sprintf("/ban-tin/%s.html", $news_id);
			$list_post[$key]['attachments'] = $core->get_field($more_information, "attachments", []);
			##
			$arr_tag = [];
			$lst_tags = $core->get_field($more_information, "lst_tags", "");
			$lst_tags = $clsISO->getArrayByTextSlash($lst_tags, "", []);
			if(!empty($lst_tags)){
				foreach($lst_tags as $k=>$val) {
					$tmp = @explode("-", $val);
					if(count($tmp) == 2) {
						if($tmp[0] == "DA"){
							if(!isset($arr_cache_project[$tmp[1]])) {
								$arr_cache_project[$tmp[1]] = $clsProject->getOne($tmp[1],"title,slug");
							}
							$oneProject = $arr_cache_project[$tmp[1]];
							$arr_tag[] = [
								"project_id"	=>	$tmp[1],
								"title"			=>	$clsProject->getTitle($tmp[1],$oneProject),
								"link"			=>	$clsProject->getLinkDetail($tmp[1],0,0,$oneProject)
							];
						}else if($tmp[0] == "PK") {
							$oneBlock = $arr_block[$tmp[1]];
							if(!isset($arr_cache_project[$oneBlock["for_id"]])) {
								$arr_cache_project[$oneBlock["for_id"]] = $clsProject->getOne($oneBlock["for_id"],"title,slug");
							}
							$oneProject = $arr_cache_project[$oneBlock["for_id"]];
							$arr_tag[] = [
								"block_id"		=>	$tmp[1],
								"title"			=>	$clsProperty->getTitle($tmp[1],$oneBlock),
								"link"			=>	$clsProject->getLinkDetail($oneBlock["for_id"],$tmp[1],0,$oneProject)
							];
						}else if($tmp[0] == "TD") {
							$oneBuilding = $arr_building[$tmp[1]];
							$oneBlock = $arr_block[$oneBuilding['for_id']];
							if(!isset($arr_cache_project[$oneBlock["for_id"]])) {
								$arr_cache_project[$oneBlock["for_id"]] = $clsProject->getOne($oneBlock["for_id"],"title,slug");
							}
							$oneProject = $arr_cache_project[$oneBlock["for_id"]];
							$arr_tag[] = [
								"block_id"		=>	$tmp[1],
								"title"			=>	$clsProperty->getTitle($tmp[1],$oneBuilding),
								"link"			=>	$clsProject->getLinkDetail($oneBlock["for_id"],$oneBuilding["for_id"],$tmp[1],$oneProject)
							];
						}
					}				
				}	
			}
			$list_post[$key]['arr_tag'] = $arr_tag;
		}
	}
	$smarty->assign('per_page', $per_page);
	$smarty->assign('list_post', $list_post);
	$smarty->assign('total_record', $total_record);
	#
	$scriptJs = "";
	if($cmd == "open") {
		$block_id = (int) Input::get("block_id",0);
		$project_id = (int) Input::get("project_id",0);
		$assign_list["block_id"] = $block_id;
		$assign_list["project_id"] = $project_id;
		$scriptJs = '<script>setTimeout(() => {
			$(".open_add_news").trigger("click");
		}, 500);</script>';
	}
	$assign_list["script"] = $scriptJs;
	/*=============Title & Description Page==================*/
	$title_page = 'Bản tin '.BRAND_NAME;
	if($cat_id == _NEWS_CAT_CERTI_AGENCY_SITE_ID) {
		$title_page = "Chứng nhận đại lý ".BRAND_NAME;
	}
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $title_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_more(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$assign_list["clsNews"] = $clsNews;
	$assign_list["clsMember"] = $clsMember;
	#
	$per_page = 6;
	$cat_id = (int) Input::post('cat_id', 0);
	$order_no = (int) Input::post('order_no', 0);
	$total_loaded = (int) Input::post('total_loaded', 0);
	#
	$cond = "`is_trash`=0 AND `is_online`=1 AND `post_type`='_news'";
	if($cat_id > 0){ $cond .= " and `cat_id`='{$cat_id}'";}
	$cond .= " and order_no<'{$order_no}'";
	#
	$limitCond = " LIMIT 0,{$per_page}";
	$order_by = " ORDER BY `order_no` DESC";
	$field = "{$clsNews->pkey},`title`,`content`,`images`,`user_id`,`reg_date`,`order_no`,`liked_json`";
	$list_news = $clsNews->getAll($cond.$order_by.$limitCond, $field);
	//$clsISO->print_pre($list_news); die();
	if(!empty($list_news)){
		$total_loaded += count($list_news);
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_news as $key => $val){
			$news_id = (int) $val[$clsNews->pkey];
			$user_id = (int) $val['user_id'];
			$liked_json = $val['liked_json'];
			#
			$oProfile = $arr_profile_cached[$user_id];
			$db_profile = $clsProfile->getProfile($user_id, $oProfile);
			$list_news[$key]['db_profile'] = $db_profile;
			#
			$total_liked = 0;
			$liked_json = $clsISO->to_array_json($liked_json);
			if(!empty($liked_json)){
				$arr_status = array_keys($liked_json);
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$list_news[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($news_id, $val);
			$list_news[$key]['status_liked'] = $status_liked;
			###
			$total_comments = $clsNews->getTotalComment($news_id);
			$list_news[$key]['total_comments'] = $total_comments;
			$list_news[$key]['total_actions'] = $total_liked+$total_comments;
		}
	}
	$smarty->assign('list_news', $list_news);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.load_more.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_loaded' => $total_loaded
	)); die();
}
function default_like(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$gid = Input::post('gid', '');
	$name = Input::post('name', 'like');
	$action = Input::post('action', 'like');
	$clsTable = Input::post('clsTable');
	$table_id = (int) Input::post('table_id', 0);
	#
	if($table_id == 0){
		echo '_invalid';
		die();
	} else {
		$clsClassTable = new $clsTable();
		$oneTable = $clsClassTable->getOne($table_id, "liked_json");
		$liked_json = $oneTable['liked_json'];
		$liked_json = !empty($liked_json) ? json_decode(html_entity_decode($liked_json), true) : array();
		// $clsISO->print_pre($liked_json); die();
		if($action == 'unlike'){
			foreach($liked_json as $key => $ids){
				if(!empty($ids) && in_array($profile_id, $ids)){
					$liked_json[$key] = array_diff($ids, array($profile_id));
				}
			}
		} else {
			if(!empty($liked_json)){
				foreach($liked_json as $key => $ids){
					if(!empty($ids) && in_array($profile_id, $ids)){
						$liked_json[$key] = array_diff($ids, array($profile_id));
					}
				}
			}
			$liked_json[$name][] = $profile_id;
		}
		$html = '_error';
		if($clsClassTable->updateOne($table_id, array(
			'liked_json' => json_encode($liked_json, JSON_UNESCAPED_UNICODE)
		))){
			$html = '_success|||<a gid="'.$gid.'" href="javascript:void(0);" news_id="'.$table_id.'" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="'.$clsTable.'" data-table_id="'.$table_id.'" class="awe__post-action awe__post-like-action'.($action=='like' ? ' liked': '').'"><i class="bx '.($action=='like' ? 'bxs-heart' : 'bx-heart').'"></i> Thích</a>|||'.$clsClassTable->genTotalLike($table_id);
		}
		// Return
		echo $html; die();
	}
}
function default_like_comment(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$gid = Input::post('gid', '');
	$name = Input::post('name', 'like');
	$action = Input::post('action', 'like');
	$comment_id = (int) Input::post('comment_id', 0);
	#
	if($comment_id == 0){
		echo '_invalid';
		die();
	} else {
		$clsComment = new Comment();
		$oneTable = $clsComment->getOne($comment_id, "liked_json");
		$liked_json = $oneTable['liked_json'];
		$liked_json = !empty($liked_json) ? json_decode(html_entity_decode($liked_json), true) : array();
		// $clsISO->print_pre($liked_json); die();
		if($action == 'unlike'){
			foreach($liked_json as $key => $ids){
				if(!empty($ids) && in_array($profile_id, $ids)){
					$liked_json[$key] = array_diff($ids, array($profile_id));
				}
			}
		} else {
			if(!empty($liked_json)){
				foreach($liked_json as $key => $ids){
					if(!empty($ids) && in_array($profile_id, $ids)){
						$liked_json[$key] = array_diff($ids, array($profile_id));
					}
				}
			}
			$liked_json[$name][] = $profile_id;
		}
		$html = '_error';
		if($clsComment->updateOne($comment_id, array(
			'liked_json' => json_encode($liked_json, JSON_UNESCAPED_UNICODE)
		))){
			$html = '<a href="javascript:void(0);" class="control-action'.($action=='like' ? ' liked': '').'" comment_id="'.$comment_id.'" onclick="$Core.comment.like(this, event)" data-name="like" ><i class="bx bx-like me-1" style="'.($action=='like' ? 'color:#e24b4a' : '').'"></i><span class="awe__comment-total-liked">'.$clsComment->genTotalLike($comment_id).'</span></a>';
		}
		// Return
		echo $html; die();
	}
}
function shortNumber($num) {
	global $clsISO;
    $units = [$clsISO->getRate(), 'K', 'triệu', 'tỷ', 'T'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1) ." ". $units[$i];
}
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error'; 
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$images = $_FILES['images'];
		if(!empty($images['name'])){ $ii = 0; //Init
			for($i = 0; $i<count($images); $i++){
				if($images['size'][$i] > 4194304){
					$msg = "_limit_size";
					break;
				}
			}
			if($msg == "_error"){
				$results = array();
				for($i = 0; $i<count($images); $i++){
					$clsUploadFile = new UploadFile();
					$image = array();
					$image["name"] = $images['name'][$i];
					$image["type"] = $images['type'][$i];
					$image["tmp_name"] = $images['tmp_name'][$i];
					$image["error"] = $images['error'][$i];
					$image["size"] = $images['size'][$i];
					$up = $clsUploadFile->uploadItem($image,"/Ban_Tin","jpg,jpeg,gif,png", array(
						'resize' => false,
						'resize_x' => 847,
						'resize_y' => 510,
						'watermark' => true,
					));
					if(!empty($up) && @file_exists(ABSPATH . $up)){
						$results[] = $up;
					}
				}
				// Return
				$html = '';
				if(!empty($results)){
					foreach($results as $image){
						$html .= '<span class="item">
							<img src="'.$image.'" />
							<input type="hidden" name="images[]" value="'.$image.'" />
							<a class="delete" src="'.$image.'" onClick="$Core.upload.delete(this, event)"></a>
						</span>';
					}
				}
				$msg = '_success|||' .$html;
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_uploadShare(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	###
	$msg = '_error'; $html = '';
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$uid = Input::post('uid');
		if(!empty($_FILES['image']['name'])){
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$image = $clsUploadFile->uploadItem($_FILES["image"],"/user-fh","jpeg,jpg,gif,png");
				if(!empty($image) && file_exists(ROOTPATH.$image)){
					// Set the file metadata for drive
					$title = $_FILES["image"]["name"];
					$mimeType = $_FILES["image"]["type"];
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_SHARE_ID);
					@unlink(ROOTPATH . $image);
					$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					$image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
					$msg = '_success';
					$html = '<input type="hidden" name="images[]" value="'.$image.'" />
					<div class="we-filedrop__image d-flex align-items-center justify-content-center" uid="'.$uid.'">
						<a class="delete" src="'.$image.'" uid="'.$uid.'" onClick="$Core.share.re_upload_share(this, event)"></a>
						<img class="img-responsive" style="max-width:100%;max-height:400px" src="'.$image.'" />
					</div>';
				}
			}
		}
	}
	// return
	echo sprintf('%s|||%s', $msg, $html); die();
}
function default_deleteImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	#
	$src = Input::post('src');
	if(!empty($src) && file_exists(ROOTPATH . $src)){
		@unlink(ROOTPATH . $src);
	}
	// Return
	echo 1; die();
}
function default_top_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$time_config = $clsConfiguration->getValue('time_config');
	$time_config = $clsISO->to_array_json($time_config);
	$is_time_now = !empty($time_config["is_time_now"]) ? $time_config["is_time_now"] : 0;
	if(empty($is_time_now)) {
		$year = !empty($time_config["year"]) ? $time_config["year"] : date("Y"); 		
		$month = !empty($time_config["month"]) ? sprintf('%02d', $time_config["month"]) : date("n");	
	}else{
		$month = (int) Input::post('month', 0);
		$year = (int) Input::post('year', 0);
	}
	$cond = " and `t2`.`is_cancel`='0' and `t1`.`profile_id`<>'"._PROFILE_PARTNER_ID."' 
		AND `t1`.`profile_id` NOT IN (".implode(',',_PROFILE_SUPPER_ID).")";
	if($month > 0 && $year > 0){
		$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`t2`.`deposit_date`,'%m/%Y')='{$f}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`t2`.`deposit_date`,'%Y')='".date('Y')."'";
	}
	$field = "`t1`.`profile_id`,`t1`.`code`,`t1`.`full_name`,`t1`.`avatar`,`t1`.`department_id`,`t1`.`role_id`";
	$field.= ",`t1`.`more_information`,SUM(`t2`.`realized_sales_compete`) as `total_price`,count(`t2`.`staff_id`) as `total_billing`";
	$list_top_staff = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as `t1` 
		left join {$clsBilling->tbl} as`t2` on `t1`.`profile_id`=`t2`.`staff_id`{$cond} 
		where `t1`.`is_trash`=0 and `t1`.`is_active`=1 and (`t1`.`list_department_id` NOT LIKE '%|12133|%') group by `t1`.`profile_id` 
		having `total_billing`>0 order by `total_price` DESC limit 0,6");
	// skin=dbx: redesign dashboard — list NV xuất sắc (tên · mã·phòng · doanh số navy + N GD); die sớm, nhánh cũ giữ nguyên
	$skin = Input::post('skin', '');
	if($skin == 'dbx'){
		$html = '';
		if(!empty($list_top_staff)){
			$arr_departments = array();
			$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_DEPARTMENT'", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $k => $v){
					$arr_departments[$v[$clsProperty->pkey]] = $v['title'];
				}
				unset($tmp);
			}
			$html.= '<div>';
			foreach($list_top_staff as $key => $val){
				$profile_id = (int) $val['profile_id'];
				$department_id = $val['department_id'];
				$more_information = $clsISO->to_array_json($val['more_information']);
				$department_name = isset($arr_departments[$department_id]) ? $arr_departments[$department_id] : '';
				$sub_text = $val['code'];
				if($department_name !== ''){
					$sub_text.= ($sub_text !== '' ? ' · ' : '').$department_name;
				}
				$avatar = $clsProfile->getAvatar($profile_id, $val, 80, 0);
				$gold_cls = $key < 3 ? ' dbx-person--gold' : '';
				$html.= '<div class="dbx-person'.$gold_cls.'">
					<div class="dbx-person__ava" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$profile_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300">
						<img class="rounded-pill object-fit-cover" src="'.$avatar.'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />
					</div>
					<div class="dbx-person__body">
						<div class="dbx-person__name">'.$val['full_name'].'</div>
						<div class="dbx-person__meta"><i class="bx bx-user"></i>'.$sub_text.'</div>
					</div>
					<div class="dbx-person__right">
						<span class="dbx-person__amt">'.shortNumber($val['total_price']).'</span>
						<div class="dbx-person__sub">'.(int) $val['total_billing'].' GD</div>
					</div>
				</div>';
			}
			$html.= '</div>';
		}
		echo json_encode(array('html' => $html)); die();
	}
	if(!empty($list_top_staff)){
		$html = '<ul class="p-0 m-0">';
		$arr_property_cached = array();
		$total_staffs = count($list_top_staff);
		$arr_departments = array();
		$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_DEPARTMENT'", "{$clsProperty->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_departments[$val[$clsProperty->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach($list_top_staff as $key => $val){
			$role_id = $val['role_id'];
			$profile_id = $val['profile_id'];
			$department_id = $val['department_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$department_name = $arr_departments[$department_id];
			$html .= '<li class="d-flex'.($key==$total_staffs-1?'':' pb-2 mb-1').'">
				<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$profile_id.'" data-toggle="webui-popover" >
					<img src="'.$clsProfile->getAvatar($val[$clsProfile->pkey], $val, 40, 40).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$val['full_name'].'" class="rounded-pill" />
					'.$clsProfile->get_icon_verified($profile_id, $more_information).'
				</div>
				<div class="w-100">
					<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
						<small class="text-muted d-block">'.$val['code'].'-'.$department_name.'</small>
						<div class="user-progress d-flex align-items-center gap-1">
							<h6 class="mb-0 text-main">'.shortNumber($val['total_price']).'</h6>
						</div>
					</div>
					<h6 class="mb-0">'.$val['full_name'].'</h6>
				</div>
			</li>';
		}
		$html .= '</ul>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_checkin_activity(){
	global $core,$clsISO,$deviceType,$oneProfile;
	$clsOC = new OfficeCheckin();
	$clsProfile = new Profile();
	$limit = ($deviceType == 'phone') ? 6 : 12;
	$cond = "`photo`<>''";
	// GĐKD/TP: chỉ check-in của người mình quản lý (subtree phòng ban); Director/không scope = toàn công ty
	$holderG = Input::get('holderG', '');
	if($holderG == '_sale_director'){
		$department_id = (int) $oneProfile['department_id'];
		$cond.= " AND `profile_id` IN (SELECT `".$clsProfile->pkey."` FROM `".$clsProfile->tbl."` WHERE `department_id`='{$department_id}' OR `list_department_id` LIKE '%|{$department_id}|%')";
	}
	$list = $clsOC->getAll("{$cond} ORDER BY `checkin_time` DESC LIMIT 0,{$limit}", "`profile_id`,`checkin_time`,`photo`,`note`,`more_information`");
	if(!empty($list)){
		$arr_profile_cached = $clsProfile->getProfileCached();
		$html = '<div class="owl-carousel owl-checkin-slider">';
		foreach($list as $val){
			$profile_id = (int) $val['profile_id'];
			$oProfile = isset($arr_profile_cached[$profile_id]) ? $arr_profile_cached[$profile_id] : array();
			$staff_name = htmlspecialchars($clsProfile->getFullName($profile_id, $oProfile), ENT_QUOTES);
			$photo = $val['photo'];
			$time = date('H:i · d/m', (int) $val['checkin_time']);
			$html.= '<div class="ckin-slide">
				<a href="'.$photo.'" data-fancybox="checkin_activity" class="d-block rounded-2 overflow-hidden position-relative">
					<img src="'.$photo.'" onerror="this.src=\''.URL_IMAGES.'/no-image.png\'" class="w-100" style="height:175px;object-fit:cover" alt="'.$staff_name.'" />
					<span class="position-absolute w-100 text-white px-2 pb-1 pt-4" style="left:0;bottom:0;background:linear-gradient(transparent,rgba(0,0,0,.75))">
						<span class="d-block text-truncate fw-semibold" style="font-size:12px">'.$staff_name.'</span>
						<span class="d-block" style="font-size:10.5px;opacity:.8">'.$time.'</span>
					</span>
				</a>
			</div>';
		}
		$html.= '</div>';
		$callback = "\$('.owl-checkin-slider:not(.owl-loaded)').owlCarousel({loop:false,margin:12,nav:true,dots:false,navText:[\"<i class='bx bx-chevron-left'></i>\",\"<i class='bx bx-chevron-right'></i>\"],responsive:{0:{items:2},600:{items:3},992:{items:5},1400:{items:6}}});";
		echo json_encode(array('html' => $html, 'callback' => $callback)); die();
	} else {
		$html = '<div class="dbx-empty">
			<span class="dbx-empty__ic"><i class="bx bx-camera-off"></i></span>
			<div class="dbx-empty__t">Chưa có check-in nào</div>
			<div class="dbx-empty__s">Ảnh check-in của nhân sự sẽ hiện ở đây</div>
		</div>';
		echo json_encode(array('html' => $html)); die();
	}
}
function default_load_top_shares(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$deviceType;
	global $profile_id, $oneProfile;
	$clsCache = new Cache();
	$clsShare = new Share();
	$clsProfile = new Profile();
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$total_shares = 0; $list_blanks = array();
	$holderG = Input::get('holderG','_all');
	$cond = "`is_trash`=0 AND `share_type`='share'";
	if($holderG=='_sale_director'){
		$cond.= " AND `user_id` in ( 
			SELECT `profile_id` FROM {$clsProfile->tbl} 
			WHERE (`department_id`='{$department_id}' OR `list_department_id` like '%|{$department_id}|%')
		)";
	} else if($holderG == "_sale") {
		$cond.= " AND `user_id`='{$profile_id}'";
	}
	$limitCond = " LIMIT 0,10";
	$order_by = " ORDER BY `reg_date` DESC";
	##
	$field = "{$clsShare->pkey},`title`,`images`,`reg_date`,`user_id`";
	$list_shares = $clsShare->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_shares)){
		$total_shares = count($list_shares);
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_shares as $key => $val){
			$user_id = (int) $val['user_id'];
			$images = $val['images'];
			$images = $clsISO->to_array_json($images);
			$list_shares[$key]['image'] = $images[0];
			$list_shares[$key]['images'] = $images;
			$oProfile = $arr_profile_cached[$user_id];
			$list_shares[$key]['full_name'] = $oProfile["full_name"];
		}
	}
	$number_item = ($holderG=='_all' ? 6 : 6);
	$max_item = ($deviceType=='phone') ? 3 : 6;
	$total_blanks = ($max_item - $total_shares);
	for($i=0; $i<$total_blanks; $i++){
		$list_blanks[] = $i;
	}
	$smarty->assign('holderG', $holderG);
	$smarty->assign('list_shares', $list_shares);
	$smarty->assign('list_blanks', $list_blanks);
	$assign_list['clsShare'] = $clsShare;
	$assign_list['clsProfile'] = $clsProfile;
	// Return
	$html = $core->build("_ajax.load_top_share.tpl");
	$callback = '$(\'.owl-share-slider\').owlCarousel({
		margin:0,
		loop:false,
		nav: true,
		lazyLoad:true,
		dots:false,
		autoplay:false,
		responsiveClass:true,
		margin:10,
		navText: [\'<i class="fa fa-angle-left"></i>\',\'<i class="fa fa-angle-right"></i>\'],
		responsive:{
			0:{items:3,},
			768:{items:4,},
			1200:{items:6,},
			1400:{items:'.$number_item.',},
		}
	});';
	echo json_encode(array(
		'html' => $html,
		'holderG' => $holderG,
		'callback' => $callback
	)); die();
}
function default_share(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsComment = new Comment();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProperty);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$arr_preloaders = array();
	for($i=0; $i<=6; $i++){
		$arr_preloaders[] = $i;
	}
	$current_year = date('Y');
	$current_month = date('n');
	$prev_year = $current_year;
	$prev_month = $current_month - 1;
	if($current_month==1){
		$prev_month = 12;
		$prev_year = ($current_year - 1);
	}
	$smarty->assign('current_year', $current_year);
	$smarty->assign('current_month', $current_month);
	$smarty->assign('prev_year', $prev_year);
	$smarty->assign('prev_month', $prev_month);
	##
	$share_id = (int) Input::get("share_id",0);
	$share_type = Input::get('share_type','share');
	$titlePage = ($share_type == 'secret') ? 'Thông tin mật' : 'Hoạt động tiếp khách';
	#
	$scriptJs = "";
	if($share_id > 0){
		$scriptJs= '<a class="autoclick_'.$share_id.'""  share_id="'.$share_id.'" onClick="$Core.share.open_share(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$share_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}
	###
	$cond = "`is_trash`=0 and `share_type`='{$share_type}'";
	$share_report = Input::get("share_report");
	if(!empty($share_report)) {
		if($clsISO->checkPermissionGroup("HEAD_SALE") 
			|| $clsISO->checkPermissionGroup("SALE_DIRECTOR") 
			|| $clsISO->checkPermissionGroup("SALE_DIRECTOR_ONLY") 
			|| $clsISO->checkPermissionGroup("BUSINESS_AREA")) {
			$dep_id = $oneProfile["department_id"];
			$cond.= " AND `user_id` IN (
				SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
				WHERE `department_id`='{$dep_id}' OR `list_department_id` LIKE '%|{$dep_id}|%'
			)";
		} else {
			$cond.= " AND `user_id`='{$profile_id}'";
		}
	}
	if($share_type == "share" && $clsISO->checkSale()) { 
		$dep_id = $oneProfile["department_id"];
		$cond.= " AND `user_id` IN (
			SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
			WHERE `department_id`='{$dep_id}' OR `list_department_id` LIKE '%|{$dep_id}|%'
		)";
	}
	/** Pagination */
	$current_page = (int) Input::get('page',1);
	$per_page = (int) Input::get('per_page',10);
	$total_record = $clsShare->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	/** End pagination */
	$order_by = " ORDER BY `reg_date` DESC";
	$field = "{$clsShare->pkey},`share_type`,`title`,`images`,`more_information`,`user_id`,`reg_date`,`liked_json`,`staff_id`,`list_staff_id`";
	// $dbconn->debug = true;
	$arr_total_comment = [];
	$tmp =  $clsComment->getAll("`clsTable`='Share' GROUP BY `table_id`", "COUNT(`table_id`) AS `total`,`table_id`");
	if(!empty($tmp)) {
		foreach($tmp as $key => $val) {
			$arr_total_comment[$val["table_id"]] = $val["total"];
		}
	}
	$list_shares = $clsShare->getAll($cond.$order_by.$limitCond, $field);
	if(!empty($list_shares)){
		$arr_profile_cached = $clsProfile->getProfileCached();
		$arr_project_cached = $arr_property_cached = array();
		foreach($list_shares as $key => $val){
			$share_id = $val[$clsShare->pkey];
			$user_id = (int) $val['user_id'];
			$list_staff_id = $val['list_staff_id'];
			$images = $val['images'];
			$liked_json = $val['liked_json'];
			$more_information = $val['more_information'];
			$liked_json = $clsISO->to_array_json($liked_json);
			$more_information = $clsISO->to_array_json($more_information);
			if($user_id > 0 && isset($arr_profile_cached[$user_id])){
				$db_profile = $clsProfile->getProfile($user_id, $arr_profile_cached[$user_id]);
			}
			$list_shares[$key]['db_profile'] = $db_profile;
			$total_liked = !empty($liked_json) ? count($liked_json) : 0;
			if($share_type == 'secret'){
				$project_id = $core->get_field($more_information, "project_id", 0);
				if($project_id > 0){
					if(!isset($arr_project_cached[$project_id])){
						$arr_project_cached[$project_id] = $clsProject->getTitle($project_id);
					}
					$more_information['project_name'] = $arr_project_cached[$project_id];
				}
			} else if($share_type == 'share') {
				$is_confirm = $core->get_field($more_information, "is_confirm", 0);
				$confirm = $core->get_field($more_information, "confirm", []);
				if(!empty($confirm)) {
					$profile_confirm = $clsProfile->getProfile($confirm["user_id"], $arr_profile_cached[$confirm["user_id"]]);
					$confirm["full_name"] = $profile_confirm['role']." ".$profile_confirm['name'];
					$confirm["time"] = $clsISO->convertTimeToTextFormat($confirm["reg_date"],"H:i • d/m/Y");
				}
				$more_information['confirm'] = $confirm;
				$more_information['is_confirm'] = $is_confirm;
				$images_arrs = $clsISO->to_array_json($images);
				$list_shares[$key]['image'] = !empty($images_arrs) ? $images_arrs[0] : URL_IMAGES.'/no-image.png';
			}
			$list_shares[$key]['total_liked'] = $total_liked;
			$list_shares[$key]['more_information'] = $more_information;
			$list_shares[$key]['lst_staff_id'] =$clsISO->getArrayByTextSlash($list_staff_id, ",", []);
			$list_shares[$key]['total_comments'] = !empty($arr_total_comment[$share_id]) ? $arr_total_comment[$share_id] : 0;
		}
	}
	$smarty->assign('scriptJs', $scriptJs);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('share_type', $share_type);
	$smarty->assign('list_shares', $list_shares);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('arr_preloaders', $arr_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = $titlePage  . ' | ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_share_waiting(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsComment = new Comment();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProperty);
	$smarty->assign('clsProperty', $clsProperty);
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	#
	$show = Input::post("show","");
	$is_report = (int)Input::post("is_report",0);
	$smarty->assign('is_report', $is_report);
	$cond = "`is_trash`=0 and `share_type`='share' AND JSON_EXTRACT(`more_information`,'$.is_confirm') IS NULL";
	$order_by = " ORDER BY `reg_date` DESC";
	if(!empty($is_report)) {		
		if($clsISO->checkHeadSale($oneProfile["role_id"])) {
			$dep_id = $oneProfile["department_id"];
		}else{
			$dep_id = (int)Input::post("department_id",0);
			$dep_id = !empty($dep_id) ? $dep_id : _DEPARTMENT_SALE_ID;
		}		
		if($show == "report_department") {
			$month = Input::post('month',0);
			$year = Input::post('year',date("Y"));
			if($month > 0){
				$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$start_time = strtotime(sprintf('01-%s-%s', $month, $year));
				$end_time = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
			} else {
				$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
				$start_time = strtotime(sprintf('01-01-%s', $year));
				$end_time = strtotime(sprintf('%s-%s-%s', $end_day, 12, $year));
			}
		}else{
			$month = Input::post("month",date("Y-m"));
			$start_date = date('Y-m-d 00:00:00', strtotime($month . '-01'));
			$end_date = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
			$start_time = strtotime($start_date);
			$end_time = strtotime($end_date);
			if($start_time < time() && $end_time > time()) {
				$end_time = time();
			}
		}
		$cond .= " AND (`reg_date` between '{$start_time}' and '{$end_time}')";
		$arr_profile_cached = $clsProfile->getProfileDep($dep_id, 1, "active");
	}else{
		$dep_id = $oneProfile["department_id"];
		$arr_profile_cached = $clsProfile->getProfileDep($dep_id, 0, "active");
	}
	$cond .= " AND `user_id` IN (".implode(',',array_keys($arr_profile_cached)).")";
	$field = "{$clsShare->pkey},`share_type`,`title`,`images`,`more_information`,`user_id`,`reg_date`,`liked_json`,`staff_id`,`list_staff_id`";
	$list_shares = $clsShare->getAll($cond.$order_by, $field);
	if(!empty($list_shares)){
		foreach($list_shares as $key => $val){
			$user_id = (int) $val['user_id'];
			$images = $val['images'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($user_id > 0 && isset($arr_profile_cached[$user_id])){
				$db_profile = $clsProfile->getProfile($user_id, $arr_profile_cached[$user_id]);
			}
			$list_shares[$key]['db_profile'] = $db_profile;
			$images_arrs = $clsISO->to_array_json($images);
			$list_shares[$key]['image'] = !empty($images_arrs) ? $images_arrs[0] : URL_IMAGES.'/no-image.png';
			$list_shares[$key]['more_information'] = $more_information;
		}
	}
	$smarty->assign('scriptJs', $scriptJs);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('share_type', $share_type);
	$smarty->assign('list_shares', $list_shares);
	$smarty->assign('total_record', count($list_shares));
	$smarty->assign('arr_preloaders', $arr_preloaders);
	// Return
	$html = $core->build('_ajax.share_waiting.tpl');
	echo json_encode([
		"uid"	=> $uid,
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_load_share_more(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProfile);
	#
	$per_page = 6;
	$share_type = Input::post('share_type', 'share');
	$reg_date = (int) Input::post('reg_date', 0);
	$total_loaded = (int) Input::post('total_loaded', 0);
	###
	$limitCond = " LIMIT 0,{$per_page}";
	$order_by = " ORDER BY `reg_date` DESC";
	$cond = "is_trash=0 and `share_type`='{$share_type}' and reg_date<'{$reg_date}'";
	if($share_type == "share" && $clsISO->checkSale()) { 
		$dep_id = $oneProfile["department_id"];
		$cond.= " AND `user_id` IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
			WHERE `department_id`='{$oneProfile["department_id"]}' OR `list_department_id` LIKE '%|{$oneProfile["department_id"]}|%')";
	}
	$field = "{$clsShare->pkey},`share_type`,`title`,`images`,`user_id`,`reg_date`,`liked_json`,`more_information`";
	$list_shares = $clsShare->getAll($cond.$order_by.$limitCond, $field);
	//$clsISO->print_pre($list_shares); die();
	if(!empty($list_shares)){
		$total_loaded += count($list_shares);
		$arr_project_cached = $arr_property_cached = array();
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_shares as $key => $val){
			$share_id = $val[$clsShare->pkey];
			$user_id = $val['user_id'];
			$images = $val['images'];
			$liked_json = $val['liked_json'];
			$more_information = $val['more_information'];
			$liked_json = $clsISO->to_array_json($liked_json);
			$more_information = $clsISO->to_array_json($more_information);
			if($user_id > 0 && isset($arr_profile_cached[$user_id])){
				$db_profile = $clsProfile->getProfile($user_id, $arr_profile_cached[$user_id]);
			}
			$list_shares[$key]['db_profile'] = $db_profile;
			if($share_type == 'secret'){
				$project_id = (int) $core->get_field($more_information, "", 0);
				if($project_id > 0 && isset($arr_project_cached[$project_id])){
					$arr_project_cached[$project_id] = $clsProject->getTitle($project_id);
					$more_information['project_name'] = $arr_project_cached[$project_id];
				}
			} else if($share_type == 'share') {
				$is_confirm = $core->get_field($more_information, "is_confirm", 0);
				$confirm = $core->get_field($more_information, "confirm", []);
				if(!empty($confirm)) {
					$profile_confirm = $clsProfile->getProfile($confirm["user_id"], $arr_profile_cached[$confirm["user_id"]]);
					$confirm["full_name"] = $profile_confirm['role']." ".$profile_confirm['name'];
					$confirm["time"] = $clsISO->convertTimeToTextFormat($confirm["reg_date"],"H:i • d/m/Y");
				}
				$more_information['confirm'] = $confirm;
				$more_information['is_confirm'] = $is_confirm;
				$images_arrs = $clsISO->to_array_json($images);
				$list_shares[$key]['image'] = !empty($images_arrs) ? $images_arrs[0] : URL_IMAGES.'/no-image.png';
			}
			#
			$total_liked = !empty($liked_json) ? @count($liked_json) : 0;
			$list_shares[$key]['total_liked'] = $total_liked;
			$list_shares[$key]['more_information'] = $more_information;
		}
	}
	$smarty->assign('share_type', $share_type);
	$smarty->assign('list_shares', $list_shares);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.share.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_loaded' => $total_loaded
	)); die();
}
function default_confirm_share(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO,$oneProfile;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$share_id = (int) Input::post('share_id', 0);
	$share_type = Input::post('share_type', "");
	#
	if($share_id == 0){
		$res = ["result"=>false,"msg"=>"Lỗi!"];
	} else {
		$oneShare = $clsShare->getOne($share_id);
		if(!empty($oneShare)) {
			$more_share = $clsISO->to_array_json($oneShare["more_information"]);
			$more_share["is_confirm"] = 1;
			$time_now = time();
			$more_share["confirm"] = [
				"user_id"	=>	$profile_id,
				"reg_date"	=>	$time_now,
			];
			if($clsShare->updateOne($share_id, array(
				'more_information' => json_encode($more_share, JSON_UNESCAPED_UNICODE)
			))){				
				$time = $clsISO->convertTimeToTextFormat($time_now,"H:i • d/m/Y");
				$profile_confirm = $clsProfile->getProfile($profile_id, $oneProfile);
				$html = "<i class='bx bxs-badge-check fs-20' style='vertical-align: sub' ></i> Đã xác thực <span class='tooltip-box'>
					<strong>Xác thực</strong><br>
					Bởi: <strong>".$profile_confirm['role']." ".$profile_confirm['name']."</strong><br>
					<span class='tooltip-time'>".$time."</span>
				</span>";
				$res = ["result"=>true,"msg"=>"Thành công!","html"	=>	$html];
			}
		}
	}
	// Return
	echo json_encode($res,JSON_UNESCAPED_UNICODE); die();
}
function default_share_staff(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$share_type = Input::get('share_type','staff');
	$year = (int) Input::post('year', date('Y'));
	$month = (int) Input::post('month', date('n'));
	$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$field = "`t1`.`profile_id`,`t1`.`code`,`t1`.`full_name`,`t1`.`avatar`
	,`t1`.`department_id`,`t1`.`role_id`,`t1`.`more_information`";
	if($share_type=='share'){
		$field_join = 'user_id';
		$field.= ",`t2`.`staff_id`,count(`t2`.`user_id`) as `total_share`";
	} else if($share_type=='honor'){
		$field_join = 'staff_id';
		$field.= ",`t2`.`user_id`,count(`t2`.`staff_id`) as `total_share`";
	}
	$list_staffs = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as `t1` 
	left join {$clsShare->tbl} as `t2` on `t1`.`profile_id`=`t2`.{$field_join} and `t2`.`share_type`='{$share_type}' 
	and FROM_UNIXTIME(`t2`.`reg_date`,'%m/%Y')='{$f}' where `t1`.`is_trash`=0 and `t1`.`is_active`=1 
	and `t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' group by `t1`.`profile_id` order by `total_share` DESC");
	if(!empty($list_staffs)){ $ii = 0;
		$html = '<ul style="list-style:none" class="p-0 m-0">';
		$arr_property_cached = array();
		$total_staffs = count($list_staffs);
		foreach($list_staffs as $key => $val){
			$role_id = $val['role_id'];
			$profile_id = $val['profile_id'];
			$department_id = $val['department_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(!isset($arr_property_cached[$department_id])){
				$department_name = $clsProperty->getTitle($department_id);
			}
			$department_name = $arr_property_cached[$department_id];
			$html .= '<li class="d-flex mb-2 pb-1'.($ii>10 ? ' toggleView d-none':'').'">
				<div class="avatar flex-shrink-0 me-2" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$profile_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="400">
					<img src="'.$clsProfile->getAvatar($profile_id,$val,40,40).'" 
						onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$val['full_name'].'" class="rounded" />
					'.$clsProfile->get_icon_verified($profile_id, $more_information).'
				</div>
				<div class="w-100">
					<div class="d-flex w-100 flex-wrap align-items-center justify-content-between">
						<small class="text-muted d-block mb-1">'.$val['code'].'-'.$department_name.'</small>
						<div class="user-progress d-flex align-items-center gap-1">
							<span class="fs-13 mb-0">'.$val['total_share'].' lần</span>
						</div>
					</div>
					<h6 class="mb-0 fs-14">'.$val['full_name'].'</h6>
				</div>
			</li>';
			++$ii;
		}
		if($total_staffs > 10){
			$html.= '<li>
				<button type="button" onClick="toggleItem(this, event)" toClass="toggleView" 
				class="btn btn-block btn-outline-primary">
					<span>Hiển thị thêm ('.($total_staffs-10).')</span>
				</button>
			</li>';
		}
		$html .= '</ul>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_share(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	#
	$uid = $clsISO->getUniqid();
	$share_id = (int) Input::post('share_id', 0);
	$share_type = Input::post('share_type', "share");
	#
	$title_def = "";
	if($share_type == 'secret'){
		$field = "{$clsProject->pkey},title";
		$list_all_projects = $clsProject->getAll("`is_trash`=0 and `is_menu`=1", $field);
		$smarty->assign('list_all_projects', $list_all_projects);
		$titlePage = "thông tin mật";
	} else if($share_type == 'share'){
		$arr_status = array();
		$tmp = $clsProperty->getCacheItems('CUSTOMER_STATUS');
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if(!in_array($val[$clsProperty->pkey], array(_CRM_STATUS_LEAD_ID, _CRM_STATUS_TRASH_ID))){
					$arr_status[] = $val; 
				}
			}
			unset($tmp);
		}
		$smarty->assign('arr_status', $arr_status);
		$title_def = sprintf('%s tiếp khách ngày %s', $clsProfile->getFullName($profile_id, $oneProfile), date('d-m-Y'));
		$titlePage = "hoạt động tiếp khách";
	} else if($share_type == 'honor'){
		$p_field = "{$clsProfile->pkey},full_name,avatar'";
		$list_staffs= $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by `reg_date` DESC", $p_field);
		$smarty->assign('list_staffs', $list_staffs);
		$titlePage = "Vinh danh bán hàng";
	}
	$oneShare = array('title' => $title_def);
	$list_images = $lst_staff_id = array();
	$more_information = array(
		'start_date' => time(),
		'end_date' => time(),
		'customer_id' => 0,
		'project_id' => 0,
		'content' => "",
		'status_id' => _CRM_STATUS_HEN_ID,
		'status_id' => _SHARE_STATUS_FOLLOWING_ID
	);
	$action = "_add";
	if($share_id > 0){
		$action = "_edit";
		$oneShare = $clsShare->getOne($share_id);
		$more_information = $oneShare['more_information']; 
		$list_images = $clsISO->to_array_json($oneShare['images']);
		$more_information = $clsISO->to_array_json($more_information);
		$lst_staff_id = $clsISO->getArrayByTextSlash($oneShare['list_staff_id']);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('share_id', $share_id);
	$smarty->assign('share_type', $share_type);
	$smarty->assign('action', $action);
	$smarty->assign('oneShare', $oneShare);
	$smarty->assign('list_images', $list_images);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('lst_staff_id', $lst_staff_id);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.share.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_share(){ 
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	$clsNotify = new Notify();
	$clsProfile = new Profile();				
	$clsFcmToken = new FcmToken();
	$clsCustomer = new Customer();
	$clsCustomerHistory = new CustomerHistory();
	$clsNotification = new Notification();
	###
	$share_id = (int) Input::post('share_id', 0);
	$share_type = Input::post('share_type', 'share');
	$staff_id = Input::post('staff_id', array());
	$list_staff_id = !empty($staff_id) ? $clsISO->makeSlashListFromArrayRoot($staff_id) : "";
	$title = Input::post('title');
	$images = Input::post('images'); 
	$content = Input::post('content');
	$stock_code = Input::post('stock_code');
	if($share_type == 'secret'){
		$project_id = (int) Input::post('project_id', 0);
		$start_date = Input::post('start_date');
		$end_date = Input::post('end_date');
		$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
		$end_date = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	}
	###
	$msg = "_error";
	if($share_id > 0){
		$oneShare = $clsShare->getOne($share_id);
		$more_information = $oneShare['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['content'] = $content;
		$more_information['stock_code'] = $stock_code;
		if($share_type == 'secret'){
			$more_information['project_id'] = $project_id;
			$more_information['start_date'] = $start_date;
			$more_information['end_date'] = $end_date;
		} else if($share_type == 'share'){
			$customer_name = Input::post('customer_name');
			$customer_phone = Input::post('customer_phone');
			$location = Input::post('location');
			$guest_count = (int) Input::post('guest_count');
			$target_ids = Input::post('target_ids', []);
			$interest_ids = Input::post('interest_ids', []);
			$status_id = (int) Input::post('status_id', _CRM_STATUS_HEN_ID);
			$budget_amount = Input::post('budget_amount', 0);
			$project_id = (int) Input::post('project_id', 0);
			$more_information['customer_name'] = $customer_name;
			$more_information['customer_phone'] = $customer_phone;
			$more_information['location'] = $location;
			$more_information['guest_count'] = $guest_count;
			$more_information['target_ids'] = $target_ids;
			$more_information['interest_ids'] = $interest_ids;
			$more_information['status_id'] = $status_id;
			$more_information['project_id'] = $project_id;
			$more_information['budget_amount'] = $clsISO->processSmartNumber($budget_amount);
		}
		// $clsISO->print_pre($more_information); die();
		if($clsShare->updateOne($share_id, array(
			'title' => $title,
			'list_staff_id' => $list_staff_id,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'more_information'  => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id,
		))){
			$msg = '_success';
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Share","update",$_POST);
		}
	} else {
		$share_id = $clsShare->getMaxId();
		$more_information = array();
		$more_information['content'] = $content;
		$more_information['stock_code'] = $stock_code;
		if($share_type == 'secret'){
			$more_information['start_date'] = $start_date;
			$more_information['end_date'] = $end_date;
			$more_information['project_id'] = $project_id;
		} else if($share_type == 'share'){			
			$customer_id = (int) Input::post('customer_id', 0);
			$customer_name = Input::post('customer_name');
			$customer_phone = Input::post('customer_phone');
			$location = Input::post('location');
			$guest_count = (int) Input::post('guest_count');
			$target_ids = Input::post('target_ids', []);
			$interest_ids = Input::post('interest_ids', []);
			$status_id = (int) Input::post('status_id', _CRM_STATUS_HEN_ID);
			$budget_amount = Input::post('budget_amount', 0);
			$project_id = (int) Input::post('project_id', 0);
			$more_information['customer_name'] = $customer_name;
			$more_information['customer_phone'] = $customer_phone;
			$more_information['location'] = $location;
			$more_information['guest_count'] = $guest_count;
			$more_information['target_ids'] = $target_ids;
			$more_information['interest_ids'] = $interest_ids;
			$more_information['status_id'] = $status_id;
			$more_information['project_id'] = $project_id;
			$more_information['budget_amount'] = $clsISO->processSmartNumber($budget_amount);
			# Tạo mới khách hàng
			if($customer_id == 0 || ($customer_id > 0 && $clsCustomer->getOneField('phone', $customer_id) != $customer_name)){
				$len = @strlen($customer_phone);
				$tmp = $clsCustomer->getByCond("`is_trash`=0 AND `admin_id`='{$profile_id}' 
					AND `name_slug`='".$core->replaceSpace($customer_name)."' AND RIGHT(`phone`,{$len})='{$customer_phone}'");
				if(!empty($tmp)){
					$upd_data = ["status_id" => $status_id];
					$list_block_id = $tmp["list_block_id"];
					$array_block_id = $clsISO->getArrayByTextSlash($list_block_id);
					if($project_id >0 && !$clsISO->checkItemInArray($project_id, $array_block_id)) {
						$array_block_id[] = $project_id;
					}
					$upd_data["list_block_id"] = $clsISO->makeSlashListFromArray($array_block_id);
					$customer_id = $tmp[$clsCustomer->pkey];
					$old_status_id = $tmp["status_id"];
					if($clsCustomer->updateOne($customer_id, $upd_data)){
						if($old_status_id != $status_id){
							$clsCustomerHistory->insert(array(
								'customer_id' => $customer_id,
								'from_status_id' => $old_status_id,
								'to_status_id' => $status_id,
								'staff_id' => $profile_id,
								'action_date' => time()
							));
						}
					}
				} else {
					$block_ids = [];
					if($project_id > 0) $block_ids[] = $project_id;
					$customer_id = $clsCustomer->getMaxId();
					$content_log = sprintf('<strong>%s</strong> đã tạo mới khách hàng', $customer_name);
					$action_logs[$clsISO->getUniqid()] = array(
						'user_id' => $profile_id,
						'reg_date' => time(),
						'content' => $content_log
					);
					$more_info = array(
						'tiktok' => '',
						'facebook' => '',
						'action_logs' => $action_logs
					);
					if($clsCustomer->insert(array(
						$clsCustomer->pkey => $customer_id,
						'name' => $customer_name,
						'name_slug' => $core->replaceSpace($customer_name),
						'phone' => $customer_phone,
						'begin_need' => $content,
						'more_information' => json_encode($more_info, JSON_UNESCAPED_UNICODE),
						'list_purpose_id' => $clsISO->makeSlashListFromArrayRoot($target_ids),
						'list_bedroom_id' => $clsISO->makeSlashListFromArrayRoot($interest_ids),
						'list_block_id' => $clsISO->makeSlashListFromArrayRoot($block_ids),
						'admin_id' => $profile_id,
						'user_id' => $profile_id,
						'user_id_update' => $profile_id,
						'status_id' => $status_id,
						'reg_date' => time(),
						'upd_date' => time()
					))){
						$clsCustomerHistory->insert(array(
							'customer_id' => $customer_id,
							'from_status_id' => _CRM_STATUS_LEAD_ID,
							'to_status_id' => $status_id,
							'staff_id' => $profile_id,
							'action_date' => time()
						));
					}
				}
			}
			$more_information['customer_id'] = $customer_id;
		}
		// $dbconn->debug = true;
		if($clsShare->insert(array(
			$clsShare->pkey => $share_id,
			'share_type' => $share_type,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'list_staff_id' => $list_staff_id,
			'title' => $title,
			'images' => json_encode($images, JSON_UNESCAPED_UNICODE),
			'more_information'  => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = '_success';	
			# thông báo
			$department_id = $oneProfile["department_id"];
			$oneDep = $clsProperty->getArraySearchByKey("_DEPARTMENT",$department_id);
			$head_of_dep_id = (int) $core->get_field($oneDep["more_information"], "head_of_dep_id", 0);
			if(!empty($head_of_dep_id) && $head_of_dep_id != $profile_id) {
				$clsNotify = new Notify();
				$time = $clsISO->convertTimeToTextFormat(time(),"H:i") . " ngày " . $clsISO->convertTimeToTextFormat(time(),"d/m/Y");
				$titleNoty = sprintf('<strong>%s</strong> đã thêm mới hoạt động tiếp khách vào lúc <strong>%s</strong>', $oneProfile["full_name"], $time);
				#thong bao app
				$params = [
					'title' => "Hoạt động tiếp khách",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsShare->getLink($share_id)
				];
				$user_notify = [$head_of_dep_id];
				$clsNotify->insertNotify('Share',$clsShare->pkey, $share_id, $titleNoty, time(), $user_notify);
				$clsNotification->doPushMessagingUser($params,$user_notify);
			}
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Share", "insert", $_POST);
		}
	}
	// Return
	echo $msg; die();
}
function default_open_share_detail(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsShare = new Share();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$share_id = (int) Input::post('share_id', 0);
	###
	$is_registed = 0;
	$arr_profile_cached = $clsProfile->getProfileCached();
	$oneShare = $list_images = $more_information = array();
	if($share_id > 0 || $holderG=='_detail'){
		$oneShare = $clsShare->getOne($share_id);
		$images = $oneShare['images'];
		$liked_json = $oneShare['liked_json'];
		$list_images = $clsISO->to_array_json($images);
		$liked_json = $clsISO->to_array_json($liked_json);
		$total_liked = !empty($liked_json) ? count($liked_json) : 0;
		$oneShare['total_liked'] = $total_liked;
		//$clsISO->print_pre($profile_id); die();
		$user_id = $oneShare['user_id'];
		$db_profile = $clsProfile->getProfile($user_id,$arr_profile_cached[$user_id]);
		$oneShare['db_profile'] = $db_profile;
		$oneShare['lst_staff_id'] = $clsISO->getArrayByTextSlash($oneShare['list_staff_id']);
		$more_information = $clsISO->to_array_json($oneShare['more_information']);
		$status_id = (int) $core->get_field($more_information, "status_id", 0);
		$project_budget = (int) $core->get_field($more_information, "project_budget", 0);
		$target_ids = $core->get_field($more_information, "target_ids", []);
		$interest_ids = $core->get_field($more_information, "interest_ids", []);
		$status_name = ($status_id > 0 ) ? $clsProperty->getTitle($status_id) : "";
		$target_name = $clsProperty->getTitleArray($target_ids, false, $arr_property_cached);
		$interest_name = $clsProperty->getTitleArray($interest_ids, false, $arr_property_cached);
		$more_information['status_name'] = $status_name;
		$more_information['target_name'] = $target_name;
		$more_information['interest_name'] = $interest_name;
		$more_information['project_budget_name'] = !empty($project_budget) ? $clsSetting->getTitle($project_budget) : "";
		$has_info = 0;
		if(!empty($more_information["customer_name"])) $has_info = 1;
		if(!empty($more_information["customer_phone"])) $has_info = 1;
		if(!empty($more_information["location"])) $has_info = 1;
		if(!empty($more_information["guest_count"])) $has_info = 1;
		if(!empty($more_information["target_ids"])) $has_info = 1;
		if(!empty($more_information["interest_ids"])) $has_info = 1;
		if(!empty($more_information["status_name"])) $has_info = 1;
		if(!empty($more_information["content"])) $has_info = 1;
		$oneShare["has_info"] = $has_info;
		$is_confirm = $core->get_field($more_information, "is_confirm", 0);
		$confirm = $core->get_field($more_information, "confirm", []);
		if(!empty($confirm)) {
			$profile_confirm = $clsProfile->getProfile($confirm["user_id"], $arr_profile_cached[$confirm["user_id"]]);
			$confirm["full_name"] = $profile_confirm['role']." ".$profile_confirm['name'];
			$confirm["time"] = $clsISO->convertTimeToTextFormat($confirm["reg_date"],"H:i • d/m/Y");;
		}
		$more_information['confirm'] = $confirm;
		$more_information['is_confirm'] = $is_confirm;
	}
	//	$clsISO->print_pre($oneShare);die;
	$smarty->assign('uid', $uid);
	$smarty->assign('share_id', $share_id);
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('oneShare', $oneShare);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_images', $list_images);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.share_detail.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'list_images' => $list_images,
	)); die();
}
function default_share_like(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$clsShare = new Share();
	$share_id = (int) Input::post('share_id', 0);
	$_type = Input::post('_type', "");
	#
	if($share_id == 0){
		echo json_encode([
			"result"	=>	false,
			"msg"	=>	"_invalid",
		 ],JSON_UNESCAPED_UNICODE);die;
	} else {
		$html_like = '';
		$liked_json = $clsShare->getOneField('liked_json', $share_id);
		$liked_json = !empty($liked_json) 
			? json_decode(html_entity_decode($liked_json), true) : array();
		if(in_array($profile_id, $liked_json)){
			$action = 'unlike';
			$liked_json = array_diff($liked_json, array($profile_id));
			$html_like = (count($liked_json) > 0) ? ('<a href="javascript:void(0)" class="" data-url="/index.php?mod=home&act=load_list_like&share_id='.$share_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300" >' . count($liked_json) . ' người đã thích bài viết này</a>') : '';
		} else {
			$action = 'like';			
			if(count($liked_json) == 0) {
				$html_like = '<a href="javascript:void(0)" class="" data-url="/index.php?mod=home&act=load_list_like&share_id='.$share_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300" >Bạn đã thích bài viết này</a>';
			}else{
				$html_like = '<a href="javascript:void(0)" class="" data-url="/index.php?mod=home&act=load_list_like&share_id='.$share_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300" >Bạn và ' . count($liked_json) . ' người đã thích bài viết này</a>';
			}
			$liked_json[] = $profile_id;
		}
		if($clsShare->updateOne($share_id, array(
			'liked_json' => json_encode($liked_json, JSON_UNESCAPED_UNICODE)
		))){
			$total_likes = !empty($liked_json) ? count($liked_json) : 0;
			$html_short = $html = '';
			if($_type == "home"){
				$$html = '<a href="javascript:void(0);" share_id="'.$share_id.'" _type="home" class="awe__share-like-action awe__share-like_short_'.$share_id.' text-dark"><i class="bx '.($action=='like' ? 'bxs-heart text-main' : 'bx-heart').'"></i> '.($total_likes > 0 ? $total_likes . " " : '').'Thích</a>';
				$html_short = '<a href="javascript:void(0);" share_id="'.$share_id.'" _type="home" class="awe__share-like-action awe__share-like_short_'.$share_id.' text-dark"><i class="bx '.($action=='like' ? 'bxs-heart text-main' : 'bx-heart').'"></i> '.($total_likes > 0 ? $total_likes . " " : '').'</a>';
			}else{
				$html = '<a href="javascript:void(0);" share_id="'.$share_id.'" class="awe__post-action awe__share-like_'.$share_id.' awe__share-like-action"><i class="bx '.($action=='like' ? 'bxs-heart text-main' : 'bx-heart').'"></i> '.($total_likes > 0 ? $total_likes . " " : '').'Thích</a>';
				$html_short = '<a href="javascript:void(0);" share_id="'.$share_id.'" _type="home" class="awe__share-like-action awe__share-like_short_'.$share_id.' text-dark"><i class="bx '.($action=='like' ? 'bxs-heart text-main' : 'bx-heart').'"></i> '.($total_likes > 0 ? $total_likes . " " : '').'</a>';
			}			
		}
		// Return
		echo json_encode([
			"result"	=>	true,
			"html"	=>	$html,
			"html_short"	=>	$html_short,
			"html_like"	=>	$html_like,
		 ],JSON_UNESCAPED_UNICODE);die;
	}
}
function default_load_list_like(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsShare = new Share();
	$share_id = (int) Input::get('share_id', 0);
	#
	$callback = "";
	$list_dep = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if(!empty($share_id)){
		$listProfile = $clsProfile->getProfileCached("active");
		$liked_json = $clsShare->getOneField('liked_json', $share_id);
		$list_like = $clsISO->to_array_json($liked_json);
		if(!empty($list_like)) {			
			$html= '<ul class="p-0 px-2 m-0 overflow-y-auto" style="max-height: 250px">';
			foreach($list_like as $profile_id) {
				$oProfile = $listProfile[$profile_id];
				$department_name = $list_dep[$oProfile["department_id"]]["title"];
				$html .= '<li class="d-flex align-items-cecnter'.($key==$total_billings-1? '' : ' mb-1 pb-2').'">
					<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" >
						<img src="'.$clsProfile->getAvatar($profile_id, $oProfile, 40, 40).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$oProfile["full_name"].'" class="rounded-pill" />
						'.$clsProfile->get_icon_verified($profile_id, $oProfile["more_information"]).'
					</div>
					<div class="w-100">
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
							<small class="text-muted d-block">'.$oProfile["code"].'-'.$department_name.'</small>
						</div>
						<h6 class="mb-0">'.$oProfile["full_name"].'</h6>
					</div>
				</li>';
			}
			$html.= '</ul>';
		}
		// Return
		echo $html; die();
	}
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_honor(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProfile);
	$assign_list["share_type"] = $share_type;
	$list_total_files = $clsShare->getIMG("honor");
	$list_files = array();
	$per_page = 10;
	$current_page = (int) Input::get('page',1);
	$total_record = count($list_total_files);
	$total_page = @ceil($total_record/$per_page);
	#
	$offset = ($current_page-1) * $per_page;
	$offset_to = $offset + $per_page;
	for($i=$offset; $i<$offset_to; $i++) {
		if(!empty($list_total_files[$i])) {
			$list_files[] = $list_total_files[$i];	
		}		
	}
	$smarty->assign('list_files', $list_files);
	$smarty->assign('current_page', $current_page + 1);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('list_shares', $list_shares);
	$smarty->assign('total_record', $total_record);
	/*=============Title & Description Page==================*/
	$title_page = 'Vinh danh bán hàng'  . ' | ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_honor_more(){
	global $smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsShare', $clsShare);
	$smarty->assign('clsProfile', $clsProfile);
	#
	$list_total_files = $clsShare->getIMG("honor");
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('total_loaded',10);
	$total_record = count($list_total_files);
	$total_page = @ceil($total_record/$per_page);
	#
	$offset = ($current_page-1) * $per_page;
	$offset_to = $offset + $per_page;
	for($i=$offset; $i<$offset_to; $i++) {
		if(!empty($list_total_files[$i])) {
			$list_files[] = $list_total_files[$i];	
		}		
	}
	//	$clsISO->print_pre($list_files);die;
	$smarty->assign('list_files', $list_files);
	$smarty->assign('list_shares', $list_shares);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.honor.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_loaded' => $per_page,
		'page' => $current_page+1,
		'total_page' => $total_page,
	)); die();
}
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
function default_search(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;
	$clsSlide = new Slide();
	$clsNews = new News();
	$clsStock = new Stock();
	$json_results = array();
	$maxQuerySuggestions = Input::get('maxQuerySuggestions', 50);
	$query = Input::get('query');
	if(!empty($query)){
		$slug = $core->replaceSpace($query);
		$field = "{$clsStock->pkey},ms_code";
		$list_stocks = $clsStock->getAll("ms_code='{$query}'", $field);
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$link = $val['link'];
				$slide_type = $val['slide_type'];
				$json_results['suggests']['Căn hộ'][] = array(
					'id' => $val[$clsStock->pkey],
					'name' => $val['ms_code'],
					'link' => '/my-favourite/'.$val['ms_code'],
					//'image' => PCMS_URL . $clsProject->getImage($val[$clsProject->pkey], 60, 40, $val)
				);
			}
		}
		$list_slides = $clsSlide->getAll("slug like '%{$slug}%'");
		if(!empty($list_slides)){
			foreach($list_slides as $key => $val){
				$link = $val['link'];
				$slide_type = $val['slide_type'];
				$json_results['suggests']['Kho tài liệu'][] = array(
					'id' => $val[$clsSlide->pkey],
					'name' => $val['title'],
					'link' => $link,
					//'image' => PCMS_URL . $clsProject->getImage($val[$clsProject->pkey], 60, 40, $val)
				);
			}
		}
	}
	// Return
	echo json_encode($json_results, JSON_UNESCAPED_UNICODE); die();
}
function default_load_person_billing(){ 
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$current_year = date('Y');
	$current_month = date('m/Y');
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `staff_id`='{$profile_id}'";	
	// $dbconn->debug = true;
	$total_billings = $clsBilling->countItem($cond . " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$current_year}'");
	$total_revenues = $clsBilling->sumItem("totalgrand", "{$cond} and FROM_UNIXTIME(`deposit_date`,'%Y')='{$current_year}'");
	$total_billings_in_month = $clsBilling->countItem($cond . " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$current_month}'");
	$total_revenues_in_month = $clsBilling->sumItem("totalgrand", "{$cond} and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$current_month}'");
	$total_compete = $clsBilling->sumItem("realized_sales_compete", "{$cond} and FROM_UNIXTIME(`deposit_date`,'%Y')='{$current_year}'");
	$total_compete_in_month = $clsBilling->sumItem("realized_sales_compete", "{$cond} and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$current_month}'");
	$html = '	<div class="spb">
		<div class="spb-cell"><div class="spb-ic ic-blue"><i class="bx bx-wallet"></i></div><div><div class="spb-lbl">Tổng số GD</div><div class="spb-val">'.$total_billings.'</div></div></div>
		<div class="spb-cell"><div class="spb-ic ic-green"><i class="bx bx-line-chart"></i></div><div><div class="spb-lbl">Tổng doanh số</div><div class="spb-val">'.$clsISO->shortNumber($total_revenues).'</div></div></div>
		<div class="spb-cell"><div class="spb-ic ic-amber"><i class="bx bx-trophy"></i></div><div><div class="spb-lbl">DS thi đua (năm)</div><div class="spb-val">'.$clsISO->shortNumber($total_compete).'</div></div></div>
		<div class="spb-cell"><div class="spb-ic ic-crimson"><i class="bx bx-calendar-check"></i></div><div><div class="spb-lbl">GD tháng này</div><div class="spb-val">'.$total_billings_in_month.'</div></div></div>
		<div class="spb-cell"><div class="spb-ic ic-amber"><i class="bx bx-credit-card"></i></div><div><div class="spb-lbl">Doanh số tháng này</div><div class="spb-val">'.$clsISO->shortNumber($total_revenues_in_month).'</div></div></div>
		<div class="spb-cell"><div class="spb-ic ic-amber"><i class="bx bx-trophy"></i></div><div><div class="spb-lbl">DS thi đua tháng này</div><div class="spb-val">'.$clsISO->shortNumber($total_compete_in_month).'</div></div></div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_person_chart(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$year = (int) Input::post('year', date('Y'));
	###
	$data = array();
	$barChartData= $dataPoints = array();
	$barChartData['animationEnabled'] = true;
	$data['axisY'] = array(
		'title' => 'Số lượng giao dịch',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 0
	);
	$cond = "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}'";
	$html = '<div id="'.$uid.'" class="chartContainer w-100 h-px-250"></div>';
	for($i=1; $i<=12; $i++){
		$m = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
		$total_billings = $clsBilling->countItem("{$cond} and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$m}'");
		$dataPoints[] = array(
			'y' => $total_billings*1,
			'label' => sprintf('Tháng %s', $i),
			'indexLabel' => $total_billings . " GD"
		);
	}
	$data['type'] = 'column';
	$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function default_load_person_campaign(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
    $clsProfile = new Profile();
    $clsCampaign = new Campaign();
    $clsProperty = new Property();
    $clsBilling  = new Billing();
    $smarty->assign('clsProfile', $clsProfile);
    $smarty->assign('clsProperty', $clsProperty);
    $smarty->assign('clsCampaign', $clsCampaign);
    #
	$html = "";
    $currentNow = time();
	$list_campaigns = $clsCampaign->getAll("`start_date`<'{$currentNow}' and `end_date`>'{$currentNow}' order by end_date ASC");
	if(!empty($list_campaigns)){
		foreach($list_campaigns as $oneCampaign){
			$campaign_id = $oneCampaign[$clsCampaign->pkey];
			$selector = $oneCampaign['selector'];
			$start_date = $oneCampaign['start_date'];
			$end_date = $oneCampaign['end_date'];
			$campaign_info = $oneCampaign['campaign_info'];
			$campaign_info = $clsISO->to_array_json($campaign_info);
			if($selector == 'staff'){
				if(!empty($campaign_info)){
					$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
					$cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
					if($campaign_info['opt_staff']=='_sale'){
						$cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%|' and `role_id`<>'"._ROLE_GD_PROJECT."'";
					} else if($campaign_info['opt_staff']=='_sale2'){
						 $cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%|'";
					}  else if($campaign_info['opt_staff']=='_sale2'){
						 $cond.= " and (`list_department_id` like '%|"._DEPARTMENT_SALE_ID."%|' or department_id='"._DEPARTMENT_DIRECTOR_ID."')";
					} else if($campaign_info['opt_staff']=='_select'){
						$ids = isset($campaign_info['staff']) && !empty($campaign_info['staff']) 
							? $campaign_info['staff'] : array();
						if(!empty($ids)){
							$cond.= " and {$clsProfile->pkey} in (".implode(',', $ids).")";
						}
					}
					if($clsProfile->countItem("{$cond} and `profile_id`='{$profile_id}'") > 0){
						if($oneCampaign['is_terms']==1){
							$campaign_terms = $oneCampaign['campaign_terms'];
							$campaign_terms = !empty($campaign_terms) 
								? json_decode(html_entity_decode($campaign_terms), true) : array();
							$list_terms = array();
							if(!empty($campaign_terms)){
								foreach($campaign_terms as $key => $val){
									if(!empty($val)){
										$list_terms[$key] = $val;
									}
								}
							}
							$smarty->assign('list_terms', $list_terms);
							#
							$total_scores = $total_transactions = 0;
							$field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`is_fullscore`,`deposit_date`";
							$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}' 
							and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);
							if(!empty($list_billings)){
								$total_transactions = count($list_billings);
								foreach($list_billings as $okey => $oval){
									$is_fullscore = $oval['is_fullscore'];
									$billing_type = (int) $oval['billing_type'];
									$billing_source_id = (int) $oval['billing_source_id'];
									if($billing_source_id == _BILLING_RESOURCE_F1_ID && $is_fullscore == 1 
										&& $billing_type == _BILLING_TYPE_MWF_ID){
										$promos_start_date = strtotime('01-07-2024');
										$end_promos_day = cal_days_in_month(CAL_GREGORIAN, 9, 2024);
										$promos_end_date = strtotime(sprintf('%s-9-2024 23:59:59', $end_promos_day));
										if($oval['deposit_date'] >= $promos_start_date 
											&& $oval['deposit_date'] <= $promos_end_date){
											$total_scores += 10;
										} else if($oval['deposit_date'] <= $promos_start_date){
											$total_scores += floatval(7.5);
										} else {
											$total_scores += floatval($campaign_terms[$billing_type]);
										}
									} else {
										$total_scores += floatval($campaign_terms[$oval['billing_type']]);
									}
								}
							}
							if($campaign_id==4){
								$campaign_target = $oneCampaign['campaign_target'];
								$campaign_target = !empty($campaign_target) 
									? json_decode(html_entity_decode($campaign_target), true) : array();
								$num_target = isset($campaign_target[$profile_id]["billing"]) 
									? $campaign_target[$profile_id]["billing"] : 0;
								#
								$html.= '<div class="card gotoLink mb-2 h-100" href="'.$clsCampaign->getLink($campaign_id).'">
									<div class="card-body">
										<div class="d-flex align-items-center justify-content-between">
											<div class="p_left">
												 <h4 class="mb-1 text-main"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsProfile->getFullName($profile_id, $oneProfile).'</a></h4>
												 <p class="m-0">'.$oneCampaign['title'].'</p>
											</div>
											<div class="d-flex align-items-center justify-content-between">
												<div class="p_right'.($deviceType=='phone'?' pr-2 mr-2':' pr-4 mr-4').'  border-end">
													<p class="mb-n1 fs-tiny text-muted">Điểm Bắc Kinh</p>
													<strong class="fs-3 text-main">'.$total_scores.'</strong>/'.$clsConfiguration->getValue('total_score').' điểm
												</div>
												<div class="p_right">
													<p class="mb-n1 fs-tiny text-muted">KPI giao dịch</p>
													<strong class="fs-3 text-main">'.$total_transactions.'</strong>/'.$num_target.' căn
												</div>
											</div>
										</div>
									</div>
								</div>';
							} else {
								$html.='<div class="card mb-2 gotoLink h-100" href="'.$clsCampaign->getLink($campaign_id).'">
									<div class="card-body">
										<div class="d-flex align-items-center justify-content-between">
											<div class="p_left">
												 <h4 class="mb-1"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsISO->makeIcon('bx-user', $clsProfile->getFullName($profile_id, $oneProfile)).'</a></h4>
												 <p class="m-0 pl-4">'.$oneCampaign['title'].'</p>
											</div>
											<div class="p_right">
												<strong class="fs-4 text-main">'.$total_scores.'</strong>
												<!--'.$clsConfiguration->getValue('total_score').' -->
											</div>
										</div>
									</div>
								</div>';		
							}
						} 
					}
				}
			} else if($selector=='group_staff'){
				if(!empty($campaign_info)){
					if($clsISO->checkSupper()){
						foreach($campaign_info as $key => $val){
							$group_members = $val['group_members'];
							$group_product = $val['group_product'];
							$group_target = $val['group_target'];
							if(!empty($group_members)){
								$cond = "";
								if($group_product=='CAO_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";
								if($group_product=='THAP_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
								if($group_product=='MWF') $cond.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."'";
								if($group_product=='CHO_THUE') $cond.= " and `billing_type` in (".implode(',', array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID)).")";
								$total_scores = $clsBilling->countItem("`is_trash`=0 and `is_cancel`='0' 
									and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."'".$cond);
								$html .= '<div class="card mb-2 gotoLink h-100" href="'.$clsCampaign->getLink($campaign_id).'">
									<div class="card-body">
										<div class="d-flex align-items-center justify-content-between">
											<div class="p_left">
												 <h4 class="mb-1"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsISO->makeIcon('bx-group', $val['group_name']).'</a></h4>
											</div>
											<div class="p_right">
												<strong class="fs-3 text-main">'.$total_scores.'</strong>/'.$group_target.' giao dịch
											</div>
										</div>
									</div>
								</div>';
							}
						}
					} else {
						foreach($campaign_info as $key => $val){
							$group_target = $val['group_target'];
							$group_members = $val['group_members'];
							$group_product = $val['group_product'];
							if(!empty($group_members)){
								if(@in_array($profile_id, $group_members)){
									$cond = "";
									if($group_product=='CAO_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";
									if($group_product=='THAP_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
									if($group_product=='MWF') $cond.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."'";
									if($group_product=='CHO_THUE') $cond.= " and `billing_type` in (".implode(',', array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID)).")";
									$total_scores = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") and (`deposit_date` between '{$start_date}' AND '{$end_date}')".$cond);
									$html .= '<div class="card d-none mb-3 gotoLink h-100" href="'.$clsCampaign->getLink($campaign_id).'">
										<div class="card-body">
											<div class="d-flex align-items-center justify-content-between">
												<div class="p_left">
													<h4 class="mb-1"><a class="text-main fs-5" href="'.$clsCampaign->getLink($campaign_id).'">'.$clsISO->makeIcon('bx-group', $val['group_name']).'</a></h4>
													 <p class="m-0 pl-4">Tổng số thành viên: '.count($group_members).'</p>
												</div>
												<div class="p_right">
													<strong class="fs-3 text-main">'.$total_scores.'</strong>/'.$group_target.' giao dịch
												</div>
											</div>
										</div>
									</div>';
								}
							}
						}
					}
				}
			}
		}
	} else {
		 $html = '_empty';
	}
    // Return
    echo json_encode(array(
        'html' => $html
    )); die();
}
function default_load_crm_desktop(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$currentYear = date('Y');
	$currentMonth = date('n');
	// Total
	$total_customer = $clsCustomer->countItem("(`admin_id`='{$profile_id}' or `use_globe`='1' or `list_share_id` like '%|{$profile_id}|%') and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	// Tiáo¿p khách
	$total_share = $clsShare->countItem("`staff_id`='{$profile_id}' and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	// Billing
	$total_transaction = $clsBilling->countItem("`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	// Billing
	$total_grand = $clsBilling->sumItem("totalgrand","`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($currentMonth)}'");
	###
	$html = '<div class="card mb-3 h-100">
		<div class="card-header">
			<h5 class="card-title mb-1 text-main">Thống kê hiện suất bán hàng cá nhận T'.$currentMonth.'</h5>
			<ul class="nav pqkmaItZyp nav-default">';
			for($i=1; $i<=12; $i++){
				$html.= '<li class="nav-item oxGryDemjr'.($currentMonth==$i?' active':'').'">
					<a href="javascript:void(0);"'.($currentMonth>=$i?' onClick="$Core.crm.load_data_month(this,event)"':'').' 
					month="'.$i.'" title="ThÃ¡ng '.$i.'" class="nav-link uFrQLkRcYk">T'.$i.'</a>
				</li>';
			}
		$html.= '</ul>
		</div>
		<div class="card-body">
			<div id="hPImzYKEjR" class="hPImzYKEjR small-briefs d-flex gap-2 flex-wrap">
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#ab0303">
					<p class="fs-12 text-white mb-2">Khách mới</p>
					<h3 class="fs-5 mb-0 text-white">'.$total_customer.' <span class="fs-tiny font-normal">lead</span></h3>
				</div>
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#1d6a01">
					<p class="fs-12 text-white mb-2">Tiếp khách</p>
					<h3 class="fs-5 mb-0 text-white">'.$total_share.' <span class="fs-tiny font-normal">láo§n</h3>
				</div>
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#eba000">
					<p class="fs-12 text-white mb-2">Giao dịch</p>
					<h3 class="fs-5 mb-0 text-white">'.$total_transaction.' <span class="fs-tiny font-normal">căn</span></h3>
				</div>
				<div class="brief-item m-0 overflow-hidden position-relative" style="background:#c9c313">
					<p class="fs-12 text-white mb-2">Doanh số</p>
					<h3 class="fs-5 mb-0 text-white">'.shortNumber($total_grand).'</h3>
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
        'html' => $html
    )); die();
}
function default_load_data_month_desktop(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$month = Input::post('month', date('m'));
	// Total
	$total_customer = $clsCustomer->countItem("(`admin_id`='{$profile_id}' or `use_globe`='1' or `list_share_id` like '%|{$profile_id}|%') and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($month)}'");
	// Tiáo¿p khách
	$total_share = $clsShare->countItem("`staff_id`='{$profile_id}' and FROM_UNIXTIME(`reg_date`,'%m')='{$clsISO->parseNumber($month)}'");
	// Billing
	$total_transaction = $clsBilling->countItem("`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($month)}'");
	// Billing
	$total_grand = $clsBilling->sumItem("totalgrand","`is_trash`=0 and `is_cancel`='0' and `staff_id`='{$profile_id}' 
	and FROM_UNIXTIME(`deposit_date`,'%m')='{$clsISO->parseNumber($month)}'");
	###
	$html = '<div class="brief-item m-0 overflow-hidden position-relative" style="background:#ab0303">
		<p class="fs-12 text-white mb-2">Khách mới</p>
		<h3 class="fs-5 mb-0 text-white">'.$total_customer.' <span class="fs-tiny font-normal">lead</span></h3>
	</div>
	<div class="brief-item m-0 overflow-hidden position-relative" style="background:#1d6a01">
		<p class="fs-12 text-white mb-2">Tiếp khách</p>
		<h3 class="fs-5 mb-0 text-white">'.$total_share.' <span class="fs-tiny font-normal">láo§n</span></h3>
	</div>
	<div class="brief-item m-0 overflow-hidden position-relative" style="background:#eba000">
		<p class="fs-12 text-white mb-2">Giao dịch</p>
		<h3 class="fs-5 mb-0 text-white">'.$total_transaction.' <span class="fs-tiny font-normal">căn</span></h3>
	</div>
	<div class="brief-item m-0 overflow-hidden position-relative" style="background:#c9c313">
		<p class="fs-12 text-white mb-2">Doanh số</p>
		<h3 class="fs-5 mb-0 text-white">'.shortNumber($total_grand).'</h3>
	</div>';
	// Return
	echo $html; die();
}
function default_load_desktop_stock_sold(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsStock = new Stock();
	$start_date = strtotime(sprintf('%s 00:00:00', date('d-m-Y')));
	$due_date = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$list_days = array(
		'today' => array(
			'title' => 'Hôm nay',
			'start_date' => $start_date,
			'end_date' => $due_date
		), 
		'yesterday' => array(
			'title' => 'Hôm qua',
			'start_date' => strtotime('-1 days', $start_date),
			'end_date' => strtotime('-1 days', $due_date)
		), 
		'7day' => array(
			'title' => '07 ngày',
			'start_date' => strtotime("-7 days", $start_date),
			'end_date' => $due_date,
		), 
		'30day' => array(
			'title' => '30 ngày',
			'start_date' => strtotime("-30 days", $start_date),
			'end_date' => $due_date
		)
	);
	$html = '<div class="card mb-2 gotoLink cursor-pointer" href="/report/stock.html">
		<h5 class="card-header">Thống kê căn bán cao tầng</h5>
		<div class="card-body">
			<div class="d-flex flex-wrap gap-2">';
			foreach($list_days as $key => $val){
				$start_date = $val['start_date'];
				$end_date = $val['end_date'];
				//$clsStock->setDebug(true);
				$total_stocks = $clsStock->countItem("stock_type='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `status_id`='"._STOCK_STATUS_SOLD_ID."' and (`ms_date` between {$start_date} and {$end_date})");
				$html.= '<div class="gbox '.$key.' flex-fill p-'.($deviceType=='phone'?'2':'3').'">
					<h5 class="mb-2 fs-14">'.$val['title'].'</h5>
					<h3 class="fs-4 mb-0 fw-bold text-main">
						<span class="countTo" data-from="0" data-to="'.$total_stocks.'" data-speed="5000">'.$total_stocks.'</span>
						<span class="fs-12 fw-normal text-muted">căn</span></h3>
				</div>';
			}
		$html .= '
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_top_search_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$due_date = time();
	$start_date = strtotime("-3 days", $due_date);
	$list_blocks = array(
		_PROJECT_BLOCK_SLC_ID => 'SLC', 
		_PROJECT_BLOCK_LSB_ID => 'LSB', 
		_PROJECT_BLOCK_MTS_ID => 'MTS', 
		_PROJECT_BLOCK_LOP_ID => 'LOP', 
		'_BLOCK_TYPE_LOWFLOOR_SALE' => 'Thấp tầng'
	);
	$cond_mas = "";
	if($clsISO->_DEV()){
		$start_date = strtotime(date("01-06-2026"));
		$list_blocks = [
			_PROJECT_BLOCK_MEL_ID	=>	"MEL",
			_PROJECT_BLOCK_MGC_ID	=>	"MGC",
			_PROJECT_BLOCK_LEK_ID	=>	"LEK",
			_PROJECT_BLOCK_HSG_ID	=>	"HSG",
			_PROJECT_BLOCK_LSB_ID => 'LSB', 
			_PROJECT_BLOCK_MTS_ID => 'MTS',
		];
		$cond_mas = " and block_id IN (".implode(',',array_keys($list_blocks)).")";
	} 
	// skin=dbx: dashboard BĐH nhận nguyên card theo thiết kế (nhánh dbx build ở dưới, sau khi có $arr_log)
	$skin = Input::post('skin', '');
	$is_director = $clsISO->checkPermissionGroup('DIRECTOR');
	if($skin == 'dbx'){
		$html = '';
	} else if($is_director) {
		$html = '<div class="card h-100 gotoLink cursor-pointer" href="'.PCMS_URL.'/logs-sale.html">';
	}else{
		$html = '<div class="card h-100">';
	}
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstLog = $dbconn->getAll("SELECT count(`t1`.`stock_id`) AS `total_stocks`,`t2`.`block_id` FROM {$clsLog->tbl} AS `t1` 
		INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`stock_id`=`t2`.`stock_id` WHERE `t1`.`type` IN ('".implode('\',\'', ['view_stock','search','view'])."') AND `t1`.`stock_id`<>'0' ".$cond_mas." and (`t1`.`reg_date` BETWEEN {$start_date} AND {$due_date}) GROUP BY `t2`.`block_id` ORDER BY `total_stocks` DESC");
	$arr_log = [];
	$total_MSQ = $total_lowfloor = 0;
	$ii=0;
	foreach ($lstLog as $key => $val) {
		if(isset($lstBlock[$val["block_id"]])) {
			$total_stocks = !empty($val) ? $val['total_stocks'] * 5 : 0;
			if($lstBlock[$val["block_id"]]["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE) {
				$total_lowfloor += $total_stocks;
			}else{
				if($clsISO->checkItemInArray($val["block_id"],_PROJECT_BLOCK_MSQ_ARRAY)) {
					$total_MSQ += $total_stocks;
				}else{
					$arr_log[] = [
						"total"	=>	$total_stocks,
						"block_name"	=>	!empty($list_blocks[$val["block_id"]]) ? $list_blocks[$val["block_id"]] : $lstBlock[$val["block_id"]]["property_code"],
					];
				}
			}				
		}
	}
	if(!empty($total_MSQ)) {
		$arr_log[] = [
			"total"	=>	$total_MSQ,
			"block_name"	=>	"MSQ",
		];
	}
	if(!empty($total_lowfloor)) {
		$arr_log[] = [
			"total"	=>	$total_lowfloor,
			"block_name"	=>	"Thấp tầng",
		];
	}
	$arr_total_score = @array_column($arr_log, 'total');
	@array_multisort($arr_total_score, SORT_DESC, $arr_log);
	// skin=dbx: nguyên card thiết kế (mini-card + progress bar, top-1 gold); die() sớm — caller Sale/GĐKD giữ card cũ
	if($skin == 'dbx'){
		$max_total = !empty($arr_log) ? max(1, (int) $arr_log[0]['total']) : 1;
		$html = '<section class="dbx-card'.($is_director ? ' gotoLink cursor-pointer' : '').'"'.($is_director ? ' href="'.PCMS_URL.'/logs-sale.html"' : '').'>
			<header class="dbx-card__head">
				<span class="dbx-card__ic"><i class="bx bx-search-alt"></i></span>
				<h3 class="dbx-card__title">Thống kê lượt tra cứu 24h qua</h3>
				<span class="dbx-card__chip">24 giờ qua</span>
			</header>
			<div class="dbx-card__body">
				<div class="dbx-mini-grid">';
		foreach ($arr_log as $key => $val){
			$pct = max(2, (int) round((int) $val['total'] / $max_total * 100));
			$html.= '<div class="dbx-mini'.($key == 0 ? ' dbx-mini--hl' : '').'">
				<div class="dbx-mini__head">
					<span class="dbx-mini__ic"><i class="bx bx-buildings"></i></span>
					<span class="dbx-mini__name">'.$val['block_name'].'</span>
				</div>
				<div class="dbx-mini__num"><b>'.$clsISO->formatNumber2($val['total']).'</b><span>lượt</span></div>
				<div class="dbx-mini__track"><div class="dbx-mini__fill" style="width:'.$pct.'%"></div></div>
			</div>';
			if($key == 4){
				break;
			}
		}
		$html.= '</div>
			</div>
		</section>';
		echo json_encode(array(
			'html' => $html
		)); die();
	}
	$html.='<h5 class="card-header">Thống kê lượt tra cứu 24h qua</h5>
	<div class="card-body">
		<div class="d-flex flex-wrap gap-2">';
		foreach ($arr_log as $key => $val){
			$html.= '<div class="gbox flex-fill '.($deviceType=='phone'?'p-2':'px-2 py-3').'">
				<h5 class="mb-2 fs-14">'.$val["block_name"].'</h5>
				<h3 class="fs-5 mb-0 fw-bold text-main">
					<span data-from="0" data-to="'.$val["total"].'" data-speed="1000">'.$clsISO->formatNumber2($val["total"]).'</span>
				</h3>
			</div>';
			if($key == 4){
				break;
			}
			/*if(($key == 3 && !empty($total_lowfloor)) || ($key == 4 && empty($total_lowfloor))){
				break;
			}*/
		}
		/*if(!empty($total_lowfloor)) {
			$html.= '<div class="gbox flex-fill '.($deviceType=='phone'?'p-2':'px-2 py-3').'">
				<h5 class="mb-2 fs-14">Thấp tầng</h5>
				<h3 class="fs-5 mb-0 fw-bold text-main">
					<span data-from="0" data-to="'.$total_lowfloor.'" data-speed="1000">'.$clsISO->formatNumber2($total_lowfloor).'</span>
				</h3>
			</div>';
		}*/
	$html .= '
			</div>
		</div>
	</div>';
		echo json_encode(array(
		'html' => $html
	)); die();
	/*$html.='<h5 class="card-header">Thống kê lượt tra cứu 24h qua</h5>
		<div class="card-body">
			<div class="d-flex flex-wrap gap-2">';
			foreach($list_blocks as $block_id => $block_name){
				if($block_id == '_BLOCK_TYPE_LOWFLOOR_SALE'){
					$cnd = "`t2`.`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
				} else {
					$cnd = "t2.block_id='{$block_id}' and `t2`.`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
				}
				$tmp = $dbconn->getRow("SELECT count(`t1`.`target_id`) AS `total_stocks` FROM {$clsLog->tbl} AS `t1` 
				INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`target_id`=`t2`.`stock_id` AND (`t1`.`type`='view' OR `t1`.`type`='view_stock') WHERE {$cnd} and (`t1`.`reg_date` BETWEEN {$start_date} AND {$due_date})");
				$total_stocks = !empty($tmp) ? $tmp['total_stocks'] * 5 : 0;
				$html.= '<div class="gbox flex-fill '.($deviceType=='phone'?'p-2':'px-2 py-3').'">
					<h5 class="mb-2 fs-14">'.$block_name.'</h5>
					<h3 class="fs-5 mb-0 fw-bold text-main">
						<span data-from="0" data-to="'.$total_stocks.'" data-speed="1000">'.$clsISO->formatNumber2($total_stocks).'</span>
					</h3>
				</div>';
			}
		$html .= '
			</div>
		</div>
	</div>';*/
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_log_check_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsMember = new Member();
	$clsProperty = new Property();
	###
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$f = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$fs = sprintf('%s/%s', $clsISO->parseNumber($month), date('y'));
	$clsCache = new Cache();
	if($clsCache->has('_ss_log_cached')){
		$_ss_log_cached = $clsCache->get('_ss_log_cached');
		$total_members_all = $_ss_log_cached['total_members_all'];
		$total_logs_all = $_ss_log_cached['total_logs_all'];
		$total_members = $_ss_log_cached['total_members'];
		$total_logs = $_ss_log_cached['total_logs'];
	} else {
		$total_members_all = $clsMember->countItem("`profile_type`='MOC'"); 
		$total_logs_all = $clsLog->countItem("(`type`='view_stock' or `type`='view')");
		$total_members = $clsMember->countItem("FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$f."' AND `profile_type`='MOC'"); 
		$total_logs = $clsLog->countItem("(`type`='view_stock' or `type`='view') and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$f."'"); 
		$clsCache->put('_ss_log_cached', array(
			'total_members_all' => $total_members_all,
			'total_logs_all' => $total_logs_all,
			'total_members' => $total_members,
			'total_logs' => $total_logs
		), 60*60);
	}
	$html = '<div class="d-flex flex-wrap gap-1">
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng user</h5> 
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_members_all.'">'.$clsISO->formatNumber2($total_members_all).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng tra cứu</h5>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_logs_all.'">'.$clsISO->formatNumber2($total_logs_all).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tổng user T.'.$month.'</h5> 
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_members.'" data-speed="1000">'.$clsISO->formatNumber2($total_members).'</span>
			</h3>
		</div>
		<div class="gbox gotoLink flex-fill px-2 py-3">
			<h5 class="mb-2 fs-14">Tra cứu T.'.$month.'</h5>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="'.$total_logs.'" data-speed="1000">'.$clsISO->formatNumber2($total_logs).'</span>
			</h3>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_euro2024(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$theme = 'purpleskin';
	$assign_list['theme'] = $theme;
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$range = 'BXH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = "1JLPVsJ5b0eIbJ4Rl5tn2cXm4mmdjWiE0ucSwxLwmlmQ";
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	##
	$max_score = $second_score = $three_score = $top_4_score = $top_5_score = 0;
	$list_staffs = array();
	if(!empty($tblData)){
		$total_columns = count($tblData[1]);
		for($i=2; $i<count($tblData); $i++){
			$total_score = $total_goal = $total_match = 0;
			for($k= 1; $k < ($total_columns - 1); $k++ ){
				if(!empty($tblData[$i][$k])){
					$total_goal += 1;
					$total_score += $tblData[$i][$k];
				}
				$total_match += 1;
			}
			if($total_score > $max_score) {
				$max_score = $total_score;
			}
			$list_staffs[] = array(
				'row' => $i,
				'full_name' => $tblData[$i][0],
				'total_score' => $total_score,
				'total_match' => $total_match,
				'total_goal' => $total_goal
			);
		}
		// $clsISO->print_pre($list_staffs); die();
		$arr_total_score = @array_column($list_staffs, 'total_score');
		@array_multisort($arr_total_score, SORT_DESC, $list_staffs);
		$arr_total_score = @array_unique($arr_total_score);
		/** Second */
		$second_score = findNumberLargest($arr_total_score,2);
		$three_score = findNumberLargest($arr_total_score,3);
		$top_4_score = findNumberLargest($arr_total_score,4);
		$top_5_score = findNumberLargest($arr_total_score,5);
	}
	// $clsISO->print_pre($second_score); die();
	$assign_list['max_score'] = $max_score;
	$assign_list['second_score'] = $second_score;
	$assign_list['three_score'] = $three_score;
	$assign_list['top_4_score'] = $top_4_score;
	$assign_list['top_5_score'] = $top_5_score;
	$assign_list['list_staffs'] = $list_staffs;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng xáo¿p háo¡ng Euro 2024 | '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function default_worldcup2026(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$theme = 'purpleskin';
	$assign_list['theme'] = $theme;
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$range = 'BXH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = "1JLPVsJ5b0eIbJ4Rl5tn2cXm4mmdjWiE0ucSwxLwmlmQ";
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	##
	$max_score = $second_score = $three_score = $top_4_score = $top_5_score = 0;
	$list_staffs = array();
	if(!empty($tblData)){
		$total_columns = count($tblData[1]);
		for($i=2; $i<count($tblData); $i++){
			$total_score = $total_goal = $total_match = 0;
			for($k= 1; $k < ($total_columns - 1); $k++ ){
				if(!empty($tblData[$i][$k])){
					$total_goal += 1;
					$total_score += $tblData[$i][$k];
				}
				$total_match += 1;
			}
			if($total_score > $max_score) {
				$max_score = $total_score;
			}
			$list_staffs[] = array(
				'row' => $i,
				'full_name' => $tblData[$i][0],
				'total_score' => $total_score,
				'total_match' => $total_match,
				'total_goal' => $total_goal
			);
		}
		// $clsISO->print_pre($list_staffs); die();
		$arr_total_score = @array_column($list_staffs, 'total_score');
		@array_multisort($arr_total_score, SORT_DESC, $list_staffs);
		$arr_total_score = @array_unique($arr_total_score);
		/** Second */
		$second_score = findNumberLargest($arr_total_score,2);
		$three_score = findNumberLargest($arr_total_score,3);
		$top_4_score = findNumberLargest($arr_total_score,4);
		$top_5_score = findNumberLargest($arr_total_score,5);
	}
	// $clsISO->print_pre($second_score); die();
	$assign_list['max_score'] = $max_score;
	$assign_list['second_score'] = $second_score;
	$assign_list['three_score'] = $three_score;
	$assign_list['top_4_score'] = $top_4_score;
	$assign_list['top_5_score'] = $top_5_score;
	$assign_list['list_staffs'] = $list_staffs;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng xếp hạng World Cup 2026 | '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function findNumberLargest(array $arr, $pos=2){
	//If array is empty then return
	if(empty($arr)) {
		return;
	}
	//sort the array in ascending order
	sort($arr);
	//save the element from the second last position of sorted array
	$numberLargest = $arr[sizeof($arr)- $pos];
	//return second-largest number
	return $numberLargest;
}
function default_open_euro(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$uid = $clsISO->getUniqid();
	$row = (int) Input::post('row', 2);
	$total_score = (int) Input::post('total_score');
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$range = 'BXH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = "1JLPVsJ5b0eIbJ4Rl5tn2cXm4mmdjWiE0ucSwxLwmlmQ";
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	###
	$html = "_error";
	if(!empty($tblData)){
		$arr_matchs = $tblData[1];
		$oneStaff = $tblData[$row];
		$html = '<div class="modal-dialog modal-dialog-scrollable'.($deviceType=='phone'?' modal-dialog-centered':'').'">
			<div class="modal-content modal-euro">
				<div class="modal-header">
					<h5 class="modal-title text-white">'.$oneStaff[0].' ('.$total_score.')</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<table class="table table-euro">
						<thead><tr>
							<th class="align-center text-center" width="3%">STT</th>
							<th class="align-center">Trận đấu</th>
							<th class="align-center text-center">Điểm</th>
						</tr></thead>';
					for($i=1; $i<count($arr_matchs)-1; $i++){
						$html.='<tr>
							<td class="text-center">'.$i.'</td>
							<td>'.$arr_matchs[$i].' '.(isset($oneStaff[$i]) && !empty($oneStaff[$i])?'<i class=\'bx bx-check-double\'></i>':'😝').'</td>
							<td class="text-center">'.(isset($oneStaff[$i]) && !empty($oneStaff[$i])?$oneStaff[$i]:0).'</td>
						</tr>';
					}
					$html.= '</table>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_config(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$setting = Input::get("setting");
	$Euro_Notes= $clsConfiguration->getValue(sprintf('SiteMsg_%s', $setting));
	// Return
	echo html_entity_decode($Euro_Notes); die();
}
function default_list_department(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	####
	$_results = array();
	$lstPartment = $clsProperty->getAllCache("property_type='_DEPARTMENT' and property_id NOT IN ("._DEPARTMENT_SALE_ID.","._DEPARTMENT_CTV_ID.")");
	if(!empty($lstPartment)){
		foreach($lstPartment as $key => $val){
			$_results[] = array(
				'id' => $val[$clsProperty->pkey],
				'slug' => $core->replaceSpace($val['slug']),
				'text' => sprintf('%s', $val['title'])
			);
		}
	}
	// return
	echo json_encode($_results); die();
}
function default_today(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	if($cmd=="_detail"){
		$course_id = Input::get('course_id', 0);
		$scriptJs.= '<a class="autoclick_'.$course_id.'"" course_id="'.$course_id.'" 
		onClick="$Core.course.open(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$course_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}
	$assign_list["scriptJs"] = $scriptJs;
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$permiss_add = $clsISO->checkPermission("create_course");
	$permiss_edit = $clsISO->checkPermission("edit_code");
	$assign_list["permiss_add"] = $permiss_add;
	$assign_list["permiss_edit"] = $permiss_edit; 
	###
	$list_preloaders = array();
	for($i= 0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
    /*=============Title & Description Page==================*/
	$title_page = 'Căn hộ nổi bật - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_zoom() {
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,
	$description_page,$keyword_page,$clsConfiguration,$clsISO;
	/*=============Title & Description Page==================*/
	$title_page = 'Bản đồ tổng thể Ocean Park 1 - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = "Cập nhật bản đồ mới nhất Ocean Park 1, toàn bộ mã căn khu thấp tầng và cao tầng";
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_confirm_deposit(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$assign_list,$clsConfiguration;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsEmailTemplate = new EmailTemplate();
	###
	$uid = $clsISO->getUniqid();
	$billing_id = (int) Input::post('billing_id', 0);
	###
	$mail_id = _MAIL_CONFIRM_DEPOSIT;
	$oneMail = $clsEmailTemplate->getOne($mail_id);
	$subject = $clsEmailTemplate->getSubject($mail_id,$oneMail);
	$content = $clsEmailTemplate->getContent($mail_id,$oneMail);
	$msg = "";
	$arr_field_none = [];
	$html = "";
	if($billing_id > 0){
		$oneBilling = $clsBilling->getOne($billing_id);
		if(!empty($oneBilling)) {
			$more_information = $clsISO->to_array_json($oneBilling['more_information']);
			$ms_code = $oneBilling['stock_code'];
			$customer_name = $more_information['customer_name'];
			$customer_email = $more_information['customer_email'];
			$customer_phone = $more_information['customer_phone'];
			$permanent_address = $more_information['permanent_address'];
//			$totalgrand = $oneBilling['totalgrand'];
			$billing_type = $oneBilling['billing_type'];
			$oneBilling_type = $clsProperty->getOne($billing_type,"more_information");
			$more_information_billing = $clsISO->to_array_json($oneBilling_type['more_information']);
			$deposit_date = $oneBilling['deposit_date'];
			$subject = str_replace("[%AGENCY_NAME%]",BRAND_NAME,$subject);
			$content = str_replace("[%AGENCY_NAME%]",BRAND_NAME,$content);
			if(empty($ms_code)) {
				$arr_field_none[] = "Mã căn";
				$subject = str_replace("[%MS_CODE%]","[Mã căn]",$subject);
				$content = str_replace("[%MS_CODE%]","[Mã căn]",$content);
			}else{
				$subject = str_replace("[%MS_CODE%]",$ms_code,$subject);
				$content = str_replace("[%MS_CODE%]",$ms_code,$content);
			}
			if(empty($customer_name)) {
				$arr_field_none[] = "Tên khách hàng";
				$content = str_replace("[%FULLNAME%]","[Tên khách hàng]",$content);
			}else{
				$content = str_replace("[%FULLNAME%]",$customer_name,$content);
			}
			if(empty($customer_email)) {
				$arr_field_none[] = "Email khách hàng";
				$content = str_replace("[%EMAIL%]","[Email khách hàng]",$content);
			}else{
				$content = str_replace("[%EMAIL%]",$customer_email,$content);
			}
			if(empty($customer_phone)) {
				$arr_field_none[] = "Số điện thoại";
				$content = str_replace("[%PHONE%]","[Số điện thoại]",$content);
			}else{
				$content = str_replace("[%PHONE%]",$customer_phone,$content);
			}
			if(empty($permanent_address)) {
				$arr_field_none[] = "Địa chỉ";
				$content = str_replace("[%ADDRESS%]","[Địa chỉ]",$content);
			}else{
				$content = str_replace("[%ADDRESS%]",$permanent_address,$content);
			}
			if(empty($more_information_billing['deposit'])) {
				$arr_field_none[] = "Số tiền cọc";
				$content = str_replace("[%DEPOSIT%]","[Số tiền cọc]",$content);
			}else{
				$content = str_replace("[%DEPOSIT%]",$more_information_billing['deposit'].$clsISO->getRate(),$content);
			}
			if(empty($deposit_date)) {
				$arr_field_none[] = "Ngày cọc";
				$content = str_replace("[%TIME_DEPOSIT%]","[Ngày cọc]",$content);
			}else{
				$content = str_replace("[%TIME_DEPOSIT%]",$clsISO->formatDate($deposit_date,4),$content);
			}
		}
		if(!empty($arr_field_none)) {
			$msg = "<b>".implode(', ',$arr_field_none)."</b>";
		}
		$company_email = $clsConfiguration->getValue('company_email');
		$smarty->assign('uid', $uid);
		$smarty->assign('company_email', $company_email);
		$smarty->assign('message', $msg);
		$smarty->assign('subject', $subject);
		$smarty->assign('content', $content);	
		// Return
		$smarty->assign('template_type', '_add_info');
		$html = $core->build('_ajax.email_deposit.tpl');
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function default_ultilities(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$arr_utilities = [];
	$list_utilities = $clsProperty->getCacheItems("_ULTILITIES");
	$arr_group_utilities = $clsProperty->getArraySearchByKey("_GROUP_ULTILITIES");
	// $clsISO->print_pre($list_utilities); die();
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		$role = "DIRECTOR";
	}elseif($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$role = "SALE_DIRECTOR";
	}elseif($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$role = "ADMIN_PROJECT";
	}elseif($clsISO->checkPermissionGroup('ACCOUNTANT')){
		$role = "ACCOUNTANT";
	} else {
		$role = "SALE";
	}
	foreach ($list_utilities as $key => $val) {		
		$more_information = $val['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if($clsISO->checkItemInArray($role, $more_information["role"])){
			$arr_utilities[$more_information["group"]]["title"] = $arr_group_utilities[$more_information["group"]]["title"];
			$arr_utilities[$more_information["group"]]["list"][] = [
				"title"	=>	$val['title'],
				"link"	=>	$more_information["link"],
				"icon"	=>	$more_information["icon"],
				'textcolor' => $val['textcolor'],
				'bgcolor' => $val['bgcolor']
			];
		}
	}
	$assign_list['arr_utilities'] = $arr_utilities;
	/*=============Title & Description Page==================*/
	$title_page ="Tiện ích hệ thống | " . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_delete_attachment_news(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsProject = new Project();
	###
	$url = Input::post('url');
	$news_id = (int) Input::post('news_id');
	$res = ["result" =>	false];
	if($news_id > 0){
		$oneNews = $clsNews->getOne($news_id, "more_information");
		$more_information = $oneNews['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$attachments = !empty($more_information['attachments']) 
			? $more_information['attachments'] : array();
		if(!empty($attachments)){
			foreach($attachments as $key => $val){
				if($val['url'] == $url){
					unset($attachments[$key]);
					break;
				}
			}
		}
		$attachments = @array_values($attachments);
		$more_information["attachments"] = $attachments;
		if($clsNews->updateOne($news_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result" =>	true];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_404(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang;
	/*=============Title & Description Page==================*/
	$title_page = '404 Not Found - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_load_followup_crm(){
	global $deviceType,$smarty,$profile_id,$oneProfile,$core,$dbconn,$clsISO,$clsConfiguration;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	##	
	$html= ''; $total_new = $total_today = $total_warning = $total_overdue = 0; 
//	 $dbconn->debug = true;
//	$list_followups = $clsFollowUp->getAll($cond." order by `date_id` DESC");
	$lstCustomer = $dbconn->getAll("SELECT `t1`.*, `t2`.`date_id`, `t2`.`status_id`, `t2`.`type_id`,`t2`.`{$clsFollowUp->pkey}` FROM `{$clsCustomer->tbl}` AS `t1` LEFT JOIN `{$clsFollowUp->tbl}` AS `t2` ON `t1`.`{$clsCustomer->pkey}` = `t2`.`customer_id` WHERE `t1`.`admin_id`='{$profile_id}'  AND `t1`.`status_id`<>'"._CRM_STATUS_TRASH_ID."'");
	if(!empty($lstCustomer)){ $ii= 1;
		foreach ($lstCustomer as $key => $val) {
			if(!empty($val["date_id"]) && date("d/m/Y",$val["date_id"]) == date("d/m/Y") && $clsISO->checkItemInArray($val["type_id"], array(_FOLLOWUP_CALL_ID,_FOLLOWUP_TASK_ID,_FOLLOWUP_ZALO_ID))) {
				++$total_today;
			}
			if((!empty($val["date_id"]) && $val["date_id"] < time() && $val["date_id"] >= strtotime("-7 days") && $clsISO->checkItemInArray($val["type_id"], array(_FOLLOWUP_CALL_ID,_FOLLOWUP_TASK_ID,_FOLLOWUP_ZALO_ID))) || empty($val["date_id"])) {
				++$total_warning;
			}
			if(!empty($val["date_id"]) && $val["date_id"] < time() && $val["status_id"] != _FOLLOWUP_STATUS_DONE_ID && $clsISO->checkItemInArray($val["type_id"], array(_FOLLOWUP_CALL_ID,_FOLLOWUP_TASK_ID,_FOLLOWUP_ZALO_ID))) {
//				$clsISO->print_pre($val);die;
				++$total_overdue;
			}
			if(date("d/m/Y",$val["reg_date"]) == date("d/m/Y")) {
				++$total_new;
			}
		}
	}
	$html = '<div class="fkn"><div class="fkn-row"><div class="fkn-ic fkn-green"><i class="bx bx-phone-call"></i></div><span class="fkn-num">'.$total_today.'</span><span class="fkn-lbl">Cần liên hệ hôm nay</span></div>
	<div class="fkn-row"><div class="fkn-ic fkn-blue"><i class="bx bx-user-plus"></i></div><span class="fkn-num">'.$total_new.'</span><span class="fkn-lbl">Khách mới</span></div>
	<div class="fkn-row"><div class="fkn-ic fkn-amber"><i class="bx bx-bell"></i></div><span class="fkn-num">'.$total_warning.'</span><span class="fkn-lbl">Cần follow-up</span></div>
	<div class="fkn-row"><div class="fkn-ic fkn-red"><i class="bx bx-alarm-exclamation"></i></div><span class="fkn-num">'.$total_overdue.'</span><span class="fkn-lbl">Quá hạn chăm sóc</span></div>
	</div>
	<a class="btn bg-main text-white w-100 mt-2" href="'.$clsISO->getLink('crm').'">Đi đến CRM <i class="bx bx-link-external"></i></a>';
	// output
	echo json_encode(array(
		'html'	=> $html
	)); die();
}
function default_load_sales_pipeline(){
	global $deviceType,$smarty,$profile_id,$oneProfile,$core,$dbconn,$clsISO,$clsConfiguration;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	##
	$tp = Input::post('tp', 'today');
	$keysearch =  Input::post('keysearch','');
	$sort_by =  Input::post('sort_by','date_id');
	$sort_type =  Input::post('sort_type','desc');
	$more_information = $oneProfile['more_information'];
	$desktop_followup_view = $core->get_field($more_information, "desktop_followup_view", "_plan");
	$cond = "`is_trash`=0 AND `admin_id`='{$profile_id}' AND `status_id`<>'"._CRM_STATUS_TRASH_ID."'";	
	$lstCustomer = $clsCustomer->getAll($cond . " GROUP BY `status_id`","`status_id`, COUNT(`customer_id`) AS `total_customer`");
	$arr_total_time = $arr_total_status = [];
	$total_cus = 0;
	foreach ($lstCustomer as $key => $val) {
		$status_id = !empty($val["status_id"]) ? $val["status_id"] : _CRM_STATUS_LEAD_ID;
		if(!isset($arr_total_status[$status_id])) {
			$arr_total_status[$status_id] = (int)$val["total_customer"];
		}else{
			$arr_total_status[$status_id] += (int)$val["total_customer"];
		}
		$total_cus += (int)$val["total_customer"];
	}
	$arr_status_cached = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
	$html = "";
	//	$clsISO->print_pre($arr_status_cached);die;
	if(!empty($arr_status_cached)){ $ii = 1;
	   $html = ''; $ii = 1; $total = count($arr_status_cached);
		foreach($arr_status_cached as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if((int) $core->get_field($more_information, "is_funnel_active", 0) == 1){
				$property_id = $val[$clsProperty->pkey];
				$total = !empty($arr_total_status[$property_id]) ? $arr_total_status[$property_id] : 0;			
				$ratio = (!empty($total_cus)) ? (round((int)$total * 100/(int)$total_cus,0)) : 0;
				$html .= '<div'.($total == $ii? '': ' class="mb-2"').'>
					<div class="d-flex align-items-center justify-content-between mb-1">
						<span class="fw-semibold" style="color:'.$val["bgcolor"].'">'.$val["title"].'</span>
						<span class="text-muted text-fs-13">'.$total.' / '.$total_cus.' &middot; '.$ratio.'%</span>
					</div>
					<div class="progress" style="height:8px">
						<div class="progress-bar" role="progressbar" style="width:'.$ratio.'%;background-color:'.$val["bgcolor"].'" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>';
				++$ii;
			}
		}
  	}
	// output
	echo json_encode(array(
		'html'	=> $html
	)); die();
}
/** =============== IMPORT GIAO DỊCH (BILLING) TỪ GOOGLE SHEET =============== */
/** Mở modal import: nạp danh sách trường map + option loại hình + spreadsheet đã lưu. */
function default_open_billing_import(){
	global $smarty, $core, $clsISO, $clsConfiguration;
	if(!$clsISO->checkPermission('create_billing')){
		echo json_encode(array('result' => false, 'msg' => 'Không có quyền tạo giao dịch.')); die();
	}
	$clsProperty = new Property();
	$clsImport = new BillingImport();
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('import_fields', $clsImport->getFields());
	$smarty->assign('billing_type_options', $clsProperty->getSelectByProperty('_BILLING_TYPE', 0));
	$smarty->assign('saved_spreadsheet', $clsConfiguration->getValue('billing_import_spreadsheet', ''));
	$html = $core->build('_ajax.import_billing.tpl');
	echo json_encode(array('uid' => $uid, 'html' => $html)); die();
}
/** Lấy danh sách tab (sheet) trong spreadsheet để user chọn. */
function default_get_billing_sheets(){
	global $clsISO, $clsConfiguration;
	if(!$clsISO->checkPermission('create_billing')){
		echo json_encode(array('result' => false, 'msg' => 'Không có quyền.')); die();
	}
	$spreadsheetId = trim(Input::post('spreadsheetId'));
	$result = false; $msg = '_error';
	$html = '<option value="">Chọn sheet</option>';
	if($spreadsheetId !== ''){
		try {
			require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			$response = $service->spreadsheets->get($spreadsheetId);
			// $clsISO->print_pre($response); die();
			foreach($response->getSheets() as $sheet){
				$title = $sheet->getProperties()->getTitle();
				$html .= "<option value='" . htmlspecialchars($title, ENT_QUOTES) . "'>" . htmlspecialchars($title) . "</option>";
			}
			$result = true; $msg = '_success';
			$clsConfiguration->updateValue('billing_import_spreadsheet', $spreadsheetId);
		} catch (Exception $e){
			$msg = 'Lỗi Spreadsheet ID hoặc quyền chia sẻ tới service account.';
		}
	}
	echo json_encode(array('result' => $result, 'msg' => $msg, 'html' => $html)); die();
}
/** Mở UI map cột: đọc dòng tiêu đề + vài dòng mẫu + cấu hình đã lưu. */
function default_config_billing_import(){
	global $smarty, $core, $clsISO;
	if(!$clsISO->checkPermission('create_billing')){
		echo json_encode(array('result' => false, 'msg' => 'Không có quyền.')); die();
	}
	$spreadsheetId = trim(Input::post('spreadsheetId'));
	$sheet_name = (string) Input::post('sheet_name'); // KHONG trim: tab co the co space dau/cuoi
	$header_row = max(1, (int) Input::post('header_row', 1));
	$start_row = (int) Input::post('start_row', 0);
	$clsImport = new BillingImport();
	$header = array(); $samples = array(); $read_error = '';
	if($spreadsheetId !== '' && $sheet_name !== ''){
		try {
			$data = $clsImport->readSheet($spreadsheetId, $sheet_name);
			if(!empty($data)){
				// Tiêu đề = dòng header_row (sheet có thể có dòng phụ trên header thật, vd nhãn trạng thái).
				$header = isset($data[$header_row - 1]) ? $data[$header_row - 1] : array();
				// Mẫu: từ dòng bắt đầu dữ liệu (nếu > header), else ngay sau header.
				$sfrom = ($start_row > $header_row) ? $start_row : ($header_row + 1);
				$startIdx = $sfrom - 1;
				$endIdx = min($startIdx + 3, count($data));
				for($i = $startIdx; $i < $endIdx; $i++){
					if(isset($data[$i])){ $samples[] = $data[$i]; }
				}
			}
		} catch (Exception $e){ $read_error = $e->getMessage(); }
	}
	$col_letters = array();
	$n_col = count($header);
	for($c = 0; $c < $n_col; $c++){
		$col_letters[$c] = $clsImport->colLetter($c);
	}
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('header', $header);
	$smarty->assign('samples', $samples);
	$smarty->assign('col_letters', $col_letters);
	$smarty->assign('import_fields', $clsImport->getFields());
	$smarty->assign('saved_config', $clsImport->getConfig());
	$smarty->assign('read_error', $read_error);
	$html = $core->build('_ajax.billing_import_config.tpl');
	echo json_encode(array('uid' => $uid, 'html' => $html)); die();
}
/** Lưu cấu hình map cột dùng chung (Configuration). */
function default_save_billing_import_config(){
	global $clsISO;
	if(!$clsISO->checkPermission('create_billing')){
		echo json_encode(array('result' => false, 'msg' => 'Không có quyền.')); die();
	}
	$columns = Input::post('columns', array());
	$clsImport = new BillingImport();
	echo json_encode($clsImport->saveConfig($columns)); die();
}
/** Chạy import. is_preview=1 (mặc định): chỉ xem trước, KHÔNG ghi DB. */
function default_run_billing_import(){
	global $clsISO;
	if(!$clsISO->checkPermission('create_billing')){
		echo json_encode(array('result' => false, 'msg' => 'Không có quyền.')); die();
	}
	$clsImport = new BillingImport();
	$report = $clsImport->run(array(
		'spreadsheetId' => trim(Input::post('spreadsheetId')),
		'sheet_name' => (string) Input::post('sheet_name'),
		'start_row' => (int) Input::post('start_row', 1),
		'billing_type' => (int) Input::post('billing_type', 0),
		'is_preview' => (int) Input::post('is_preview', 1)
	));
	echo json_encode($report); die();
}
/** =============== QUẢN LÝ TỶ LỆ HOA HỒNG BẬC THANG (theo vị trí) =============== */
/** Danh sách vị trí hưởng hoa hồng bậc thang (key => nhãn). */
function _billing_commission_roles(){
	return array(
		'TPKD'    => 'TPKD',
		'GD'      => 'Giám đốc',
		'CV_PTDT' => 'CV PTĐT',
		'TP_PTDT' => 'TP PTĐT',
		'GD_PTDT' => 'Giám đốc PTĐT'
	);
}
/** Bậc mặc định (mốc doanh số → tỷ lệ %) khi chưa cấu hình. */
function _billing_commission_defaults(){
	return array(
		'TPKD'    => array(array('min'=>0,'rate'=>4), array('min'=>520000000,'rate'=>5), array('min'=>1040000000,'rate'=>6), array('min'=>1560000000,'rate'=>7), array('min'=>2080000000,'rate'=>8)),
		'GD'      => array(array('min'=>0,'rate'=>4), array('min'=>1560000000,'rate'=>5), array('min'=>3120000000,'rate'=>6), array('min'=>6420000000,'rate'=>7)),
		'CV_PTDT' => array(array('min'=>0,'rate'=>1.5), array('min'=>800000000,'rate'=>2), array('min'=>2500000000,'rate'=>2.5)),
		'TP_PTDT' => array(array('min'=>0,'rate'=>2), array('min'=>1000000000,'rate'=>2.5), array('min'=>3000000000,'rate'=>3)),
		'GD_PTDT' => array(array('min'=>0,'rate'=>1), array('min'=>2000000000,'rate'=>1.5), array('min'=>4000000000,'rate'=>2))
	);
}
/** Chỉ Admin trưởng (ADMIN_PROJECT) hoặc BLĐ được cấu hình. */
function _billing_commission_can_manage(){
	global $clsISO;
	return $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR');
}
/**
 * Gom số quyết toán của 1 giao dịch về một mảng phẳng, đã ép kiểu sẵn cho BillingCalc.
 * Dùng chung cho cả handler mở modal lẫn handler lưu để hai bên không lệch cách đọc.
 */

/**
 * Một ô "tự tính, cho sửa đè" trên màn quyết toán: gộp số máy tính với số đã đè thành
 * bộ ba mà template và engine dùng chung.
 *
 * Mọi ô tiền/% trên màn này đi qua đây để chỉ có MỘT khuôn: `rate`/`amount` là số HIỆU LỰC
 * (đè nếu có, không thì số máy tính) — điền thẳng vào ô cho người dùng thấy và sửa được;
 * `manual` cho biết số đó do người quyết hay do máy.
 */
function _billing_cell($rate_auto, $amount_auto, $rate_de = 0, $amount_de = 0){
	$rate_de = (float) $rate_de;
	$amount_de = (float) $amount_de;
	$rate = $rate_de > 0 ? $rate_de : (float) $rate_auto;
	return array(
		'rate' => $rate > 0 ? $rate : '',
		'amount' => $amount_de > 0 ? round($amount_de) : round((float) $amount_auto),
		'manual' => ($rate_de > 0 || $amount_de > 0) ? 1 : 0
	);
}
/**
 * Số người dùng gõ vào một ô "tự tính" — trả về 0 khi họ KHÔNG sửa gì.
 *
 * Ô nay luôn hiện sẵn số máy tính (user chốt 28/07: điền sẵn, cho sửa) nên "để trống" không
 * còn dùng được để nhận biết ý định. Bằng đúng số máy tính ⇒ coi như không đè, để số bám theo
 * doanh số; khác ⇒ mới là đè. Không làm vậy thì mỗi lần quyết toán đều đóng dấu số cứng,
 * sửa giảm trừ xong tiền đứng im.
 *
 * $tol: 1đ cho tiền (sai số làm tròn hiển thị), 0.001 cho %.
 */
function _billing_override($posted, $auto, $tol = 1){
	$posted = (float) $posted;
	if($posted <= 0){
		return 0;
	}
	return (abs($posted - (float) $auto) <= $tol) ? 0 : $posted;
}
/**
 * Gom số quyết toán của 1 giao dịch về một mảng phẳng, đã ép kiểu sẵn cho BillingCalc.
 * Dùng chung cho cả handler mở modal lẫn handler lưu để hai bên không lệch cách đọc.
 */
function _billing_settlement_inputs($more_information){
	global $core;
	// CHỈ gồm ô người dùng thực sự nhập. Số máy tính ra (AF, AD auto, AJ, AM) được lưu dưới
	// tiền tố calc_* và KHÔNG bao giờ quay lại đây — cho số kết quả làm đầu vào thì lần quyết toán
	// thứ hai sẽ tính trên giá trị cũ đóng băng, đổi tổng giảm trừ cũng không nhúc nhích.
	return array(
		'commission_value'              => $core->get_field($more_information, 'commission_value', ''),
		'commission'                    => $core->get_field($more_information, 'commission', ''),
		'total_deduction'               => $core->get_field($more_information, 'total_deduction', ''),
		'total_deduction_percent_sales' => $core->get_field($more_information, 'total_deduction_percent_sales', ''),
		// Ô "Sales chịu" là override CÓ THỂ BẰNG 0 → không dùng get_field() vì nó coi 0 là rỗng.
		// Vắng mặt hoặc chuỗi rỗng = để BillingCalc tự tính; có mặt (kể cả 0) = đè.
		'total_deduction_sales'         => (array_key_exists('total_deduction_sales', $more_information)
			&& $more_information['total_deduction_sales'] !== '')
			? $more_information['total_deduction_sales'] : null,
		'total_deduction_company'       => $core->get_field($more_information, 'total_deduction_company', ''),
		'sales_commission_rate'         => $core->get_field($more_information, 'sales_commission_rate', '')
	);
}
/**
 * Giao dịch do Ban Phát triển đối tác bán, hay do sale nội bộ bán.
 *
 * Hai loại ăn hai bộ hoa hồng KHÁC HẲN nhau, không giao nhau chút nào (đo trên sheet nguồn):
 *   sale nội bộ → TPKD + GĐKD          (PTĐT: 0/82 dòng)
 *   PTĐT        → CV + TP + GĐ PTĐT    (sale nội bộ: 1–2/321 dòng)
 * Với giao dịch PTĐT, người đứng tên bán là ĐẠI LÝ/CTV bên ngoài nên không có staff_id —
 * nhân sự ăn hoa hồng là người của Ban, gán ở cấp giao dịch.
 */
/** Chỉ nhận đúng 2 giá trị; gõ gì khác coi như sale nội bộ. */
function _billing_clean_deal_type($value){
	return (trim((string) $value) === 'ptdt') ? 'ptdt' : 'sale';
}
/** Loại giao dịch đã chuẩn hoá; giao dịch cũ chưa có ô này thì suy như trước. */
function _billing_deal_type($shares, $more_information = array()){
	global $core;
	$v = trim((string) $core->get_field($more_information, 'deal_type', ''));
	if($v !== ''){
		return _billing_clean_deal_type($v);
	}
	return _billing_is_channel($shares, $more_information) ? 'channel' : 'sale';
}
/**
 * $more_information có ô "Loại giao dịch" người nhập chọn tay thì tin ô đó — chính xác tuyệt đối.
 * 342 giao dịch cũ chưa có ô này nên vẫn phải suy theo vai trò (đúng 389/393 dòng khi đối chiếu sheet).
 */
function _billing_is_channel($shares, $more_information = array()){
	global $core;
	$deal_type = $core->get_field($more_information, 'deal_type', '');
	if($deal_type !== ''){
		return $deal_type === 'channel';
	}
	if(!is_array($shares) || empty($shares)){
		return false;
	}
	$clsProfile = new Profile();
	$staff_ids = array();
	foreach($shares as $_oShare){
		// Ban Phát triển đối tác: dấu hiệu chắc chắn nhất khi có
		if((int) (isset($_oShare['department_id']) ? $_oShare['department_id'] : 0) == _DEPARTMENT_DEVELOP_ID){
			return true;
		}
		$_sid = (int) (isset($_oShare['staff_id']) ? $_oShare['staff_id'] : 0);
		if($_sid > 0){
			$staff_ids[$_sid] = $_sid;
		}
	}
	if(empty($staff_ids)){
		return false;
	}
	// Xét thêm VAI TRÒ của người đứng tên: chỉ 3/11 người mang role PTĐT là thuộc ban 12133,
	// số còn lại nằm rải ở phòng khác nên chỉ soi phòng ban thì bỏ sót phần lớn.
	// Đối chiếu với khối trên sheet: theo role đúng 389/393 dòng, theo phòng ban chỉ đúng 31/78.
	$channel_roles = array();
	foreach(BillingCalc::roles() as $_key => $_role){
		// Khoá vai trò đã đổi sang tiếng Anh: CHANNEL_* thay cho *_PTDT. Dò chuỗi cũ thì
		// mảng rỗng ⇒ hàm luôn trả false ⇒ mọi giao dịch PTĐT bị nhận nhầm thành bán nội bộ.
		if(strpos($_key, 'CHANNEL') !== false){
			$channel_roles[] = (int) $_role['role_id'];
		}
	}
	if(empty($channel_roles)){
		return false;
	}
	$list = $clsProfile->getAll("`{$clsProfile->pkey}` IN (" . implode(',', $staff_ids) . ")
		AND `role_id` IN (" . implode(',', $channel_roles) . ")", "`{$clsProfile->pkey}`");
	return !empty($list);
}
/**
 * $more_information có ô "Loại giao dịch" người nhập chọn tay thì tin ô đó — chính xác tuyệt đối.
 * 342 giao dịch cũ chưa có ô này nên vẫn phải suy theo vai trò (đúng 389/393 dòng khi đối chiếu sheet).
 */
function _billing_is_ptdt($shares, $more_information = array()){
	global $core;
	$deal_type = $core->get_field($more_information, 'deal_type', '');
	if($deal_type !== ''){
		return $deal_type === 'ptdt';
	}
	if(!is_array($shares) || empty($shares)){
		return false;
	}
	$clsProfile = new Profile();
	$staff_ids = array();
	foreach($shares as $_oShare){
		// Ban Phát triển đối tác: dấu hiệu chắc chắn nhất khi có
		if((int) (isset($_oShare['department_id']) ? $_oShare['department_id'] : 0) == _DEPARTMENT_DEVELOP_ID){
			return true;
		}
		$_sid = (int) (isset($_oShare['staff_id']) ? $_oShare['staff_id'] : 0);
		if($_sid > 0){
			$staff_ids[$_sid] = $_sid;
		}
	}
	if(empty($staff_ids)){
		return false;
	}
	// Xét thêm VAI TRÒ của người đứng tên: chỉ 3/11 người mang role PTĐT là thuộc ban 12133,
	// số còn lại nằm rải ở phòng khác nên chỉ soi phòng ban thì bỏ sót phần lớn.
	// Đối chiếu với khối trên sheet: theo role đúng 389/393 dòng, theo phòng ban chỉ đúng 31/78.
	$ptdt_roles = array();
	foreach(BillingCalc::roles() as $_key => $_role){
		if(strpos($_key, 'PTDT') !== false){
			$ptdt_roles[] = (int) $_role['role_id'];
		}
	}
	if(empty($ptdt_roles)){
		return false;
	}
	$list = $clsProfile->getAll("`{$clsProfile->pkey}` IN (" . implode(',', $staff_ids) . ")
		AND `role_id` IN (" . implode(',', $ptdt_roles) . ")", "`{$clsProfile->pkey}`");
	return !empty($list);
}
/**
 * % hoa hồng sales thực nhận (AI) của MỘT dòng sale.
 * Ưu tiên số lưu trên chính dòng đó; dòng chưa có (dữ liệu trước khi tách per-sale) thì lấy
 * số cũ ở cấp giao dịch; vẫn trống thì để BillingCalc dùng mặc định.
 */
function _billing_share_rate($oneShare, $more_information){
	global $core;
	$rate = isset($oneShare['sales_commission_rate']) ? (float) $oneShare['sales_commission_rate'] : 0;
	if($rate > 0){
		return $rate;
	}
	return $core->get_field($more_information, 'sales_commission_rate', '');
}
/** Mở modal Quyết toán của 1 giao dịch. */
function default_open_billing_settlement(){
	global $smarty, $core, $clsISO;
	if(!_billing_commission_can_manage()){
		echo json_encode(array('result' => false, 'msg' => 'Bạn không có quyền quyết toán giao dịch.')); die();
	}
	$clsBilling = new Billing();
	$clsBillingSale = new BillingSale();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$billing_id = (int) Input::post('billing_id', 0);
	$oneBilling = $clsBilling->getOne($billing_id);
	if(empty($oneBilling)){
		echo json_encode(array('result' => false, 'msg' => 'Không tìm thấy giao dịch.')); die();
	}
	// GD huỷ/xoá vẫn hiện trong danh sách nên vẫn bấm được menu — chặn ở đây, không thì
	// quyết toán xong sẽ ghi lại doanh số thi đua cho một giao dịch đã không còn hiệu lực
	if((int) $oneBilling['is_cancel'] == 1 || (int) $oneBilling['is_trash'] == 1){
		echo json_encode(array('result' => false, 'msg' => 'Giao dịch đã huỷ hoặc đã xoá, không quyết toán được.')); die();
	}
	$more_information = $clsISO->to_array_json($oneBilling['more_information']);
	$inputs = _billing_settlement_inputs($more_information);
	$totalgrand = $oneBilling['totalgrand'];
	// AG, AI, AJ, AL, AM đều là số CỦA TỪNG SALE (sheet tính theo dòng) → dựng bảng, không gộp một số.
	// AI của mỗi sale có thể khác nhau: live có GD 2 sale ăn 33,33% và 50%.
	$shares = $clsBillingSale->getByBilling($billing_id);
	$deal_type = _billing_deal_type($shares, $more_information);
	$is_channel = ($deal_type === 'channel');
	// Bán cho F2: không có hoa hồng cá nhân nào ⇒ ẩn hết khối quản lý/CTV/PTĐT,
	// chỉ còn phần công ty (Ban điều hành + Khối back) do Phase 4 xử.
	$is_f2 = ($deal_type === 'f2');
	// Loại giao dịch phải đi cùng $inputs xuống BillingCalc: mặc định 50% hoa hồng sale chỉ
	// dành cho bán nội bộ, xem ghi chú ở BillingCalc::compute().
	$inputs['deal_type'] = $deal_type;
	$rows = array();
	$total_realized = $total_sales_amount = $total_sales_deduct = $total_sales_net = 0;
	// Bậc thang hoa hồng quản lý: tra theo TỔNG doanh số của người đó trong CẢ THÁNG KÝ.
	// Hiện ngay tại modal để người quyết toán thấy TPKD/GĐKD được bao nhiêu, khỏi phải
	// đợi sang báo cáo mới biết. Số này là TẠM khi tháng chưa đóng — còn giao dịch nào
	// của tháng chưa phát sinh thì tổng còn thiếu, bậc có thể lên.
	$clsCommissionEntry = new CommissionEntry();
	$_agree = (int) $core->get_field($oneBilling, 'agree_date', 0);
	$period_stamp = $_agree > 0 ? $_agree : (int) $core->get_field($oneBilling, 'deposit_date', 0);
	// $clsConfiguration không nằm trong global của hàm này — khởi tạo tại chỗ,
	// cùng khuôn phòng thủ mà BillingImport.php đang dùng.
	$_cfg = new Configuration();
	$_saved_tiers = $_cfg->getValue('billing_commission_tiers', '');
	$st_tiers = !empty($_saved_tiers) ? json_decode($_saved_tiers, true) : array();
	if(!is_array($st_tiers)){ $st_tiers = array(); }
	foreach(BillingCalc::tiersDefault() as $_k => $_v){
		if(empty($st_tiers[$_k])){ $st_tiers[$_k] = $_v; }
	}
	foreach($shares as $_oShare){
		$share_rate = _billing_share_rate($_oShare, $more_information);
		$calc = BillingCalc::compute(array_merge($inputs, array(
			'share_ratio' => $_oShare['share_ratio'],
			'sales_commission_rate' => $share_rate,
			'totalgrand' => $totalgrand
		)));
		$total_realized += $calc['realized'];
		$total_sales_amount += $calc['sales_amount'];
		$total_sales_deduct += $calc['sales_deduct'];
		$total_sales_net += $calc['sales_net'];
		// TPKD/GĐKD của CHÍNH sale này. Chưa gán tay thì tra theo phòng ban đã snapshot lúc bán —
		// co-sale khác phòng thì mỗi người một quản lý, không dùng chung một bộ ở cấp giao dịch được.
		// Vai trò xác định theo VỊ TRÍ TRONG CÂY phòng ban, không theo role_id: ai đứng đầu
		// Phòng thì là TPKD, ai đứng đầu Khối thì là GĐKD. Live có người mang role 48
		// "Giám đốc Kinh doanh" nhưng đang làm trưởng phòng — tra theo role sẽ gán nhầm chính họ.
		$share_dep_id = (int) $core->get_field($_oShare, 'department_id', 0);
		$head_of_dep_id = (int) $core->get_field($_oShare, 'head_of_dep_id', 0);
		$sale_dir_id = (int) $core->get_field($_oShare, 'sale_dir_id', 0);
		// GD của Ban Phát triển đối tác không có TPKD/GĐKD → khỏi tra, khỏi hiện ô
		if(!$is_channel && ($head_of_dep_id <= 0 || $sale_dir_id <= 0)){
			$_chain = $clsProperty->resolveStaffDepChain($share_dep_id);
			if($head_of_dep_id <= 0){
				$head_of_dep_id = (int) $core->get_field($_chain, 'head_of_dep_id', 0);
			}
			if($sale_dir_id <= 0){
				$sale_dir_id = (int) $core->get_field($_chain, 'regional_director_id', 0);
			}
		}
		// Tiền vai trò ăn trên AG của CHÍNH dòng này (V6/V7), không phải tổng cả căn
		$_vt = array();
		foreach(array('SALES_MANAGER' => $head_of_dep_id, 'SALES_DIRECTOR' => $sale_dir_id) as $_rk => $_pid){
			if($_pid <= 0){
				$_vt[$_rk] = array('rate' => '', 'amount' => '', 'manual' => 0, 'cumulative' => 0);
				continue;
			}
			$_cum = $clsCommissionEntry->cumulativeFor($_rk, $_pid, $period_stamp);
			$_rate = BillingCalc::resolveRate(isset($st_tiers[$_rk]) ? $st_tiers[$_rk] : array(), $_cum);
			// Số đã đè (nếu có) hiện trong ô; số bậc thang hiện mờ làm gợi ý.
			// DECIMAL trả về chuỗi "0.0000" nên phải ép số rồi so > 0, không thì ô luôn đầy.
			$_col_r = ($_rk == 'SALES_MANAGER') ? 'head_of_dep_rate' : 'sale_dir_rate';
			$_col_a = ($_rk == 'SALES_MANAGER') ? 'head_of_dep_amount' : 'sale_dir_amount';
			$_de_r = (float) $core->get_field($_oShare, $_col_r, 0);
			$_de_a = (float) $core->get_field($_oShare, $_col_a, 0);
			$_vt[$_rk] = _billing_cell($_rate,
				BillingCalc::roleAmount($calc['realized'], $_de_r > 0 ? $_de_r : $_rate), $_de_r, $_de_a);
			$_vt[$_rk]['cumulative'] = $_cum;
		}
		$_ctv_rate = (float) $core->get_field($_oShare, 'ctv_rate', 0);
		$_ctv_amount = (float) $core->get_field($_oShare, 'ctv_amount', 0);
		$rows[] = array(
			'tpkd_calc' => $_vt['SALES_MANAGER'],
			'gdkd_calc' => $_vt['SALES_DIRECTOR'],
			'billing_sale_id' => $_oShare['billing_sale_id'],
			'seller_name' => $_oShare['seller_name'],
			'share_ratio' => $_oShare['share_ratio'],
			'is_primary' => $_oShare['is_primary'],
			'sales_commission_rate' => $share_rate,
			'head_of_dep_id' => $head_of_dep_id,
			'sale_dir_id' => $sale_dir_id,
			'ctv_name' => $core->get_field($_oShare, 'ctv_name', ''),
			// DECIMAL trả về chuỗi "0.0000" — không rỗng theo get_field nên sẽ hiện đầy ô;
			// ép số rồi so > 0 để ô trống vẫn trống, giống cách xử lý sales_commission_rate
			// CTV ăn trên AG của CHÍNH dòng này (khác đại lý ăn trên R cả căn)
			'ctv_calc' => _billing_cell($_ctv_rate,
				BillingCalc::roleAmount($calc['realized'], $_ctv_rate), 0, $_ctv_amount),
			'ctv_rate' => $_ctv_rate > 0 ? $_ctv_rate : '',
			'ctv_amount' => ((float) $core->get_field($_oShare, 'ctv_amount', 0)) > 0 ? $_oShare['ctv_amount'] : '',
			'calc' => $calc
		);
	}
	// Số cấp CĂN chỉ dùng cho khối Giảm trừ (AD/AF) — không phụ thuộc AI nên tỷ lệ 100% là đủ
	$whole = BillingCalc::compute(array_merge($inputs, array('share_ratio' => 100, 'totalgrand' => $totalgrand)));
	$smarty->assign('st_total_sales_amount', $total_sales_amount);
	$smarty->assign('st_total_sales_deduct', $total_sales_deduct);
	$smarty->assign('st_total_sales_net', $total_sales_net);
	// Đại lý ăn trên R của CẢ CĂN (khác CTV ăn trên AG từng sale) nên chỉ có MỘT bộ ô ở cấp giao dịch.
	// Để ở cấp dòng sale là tự mở đường cho lỗi đếm hai lần khi co-sale.
	// R đã giải (có fallback sang giá trị bán) — gốc tính tiền đại lý, và đưa xuống form
	// để JS khỏi lặp lại logic fallback
	$r_base = $clsISO->processSmartNumber($core->get_field($more_information, 'commission_value', 0));
	if($r_base <= 0){
		$r_base = $clsISO->processSmartNumber($totalgrand);
	}
	// Đại lý ăn trên R của CẢ CĂN, khác CTV ăn trên AG từng dòng
	$_ag_rate = $clsISO->convertToNumber($core->get_field($more_information, 'agency_rate', 0));
	$_ag_amount = $clsISO->processSmartNumber($core->get_field($more_information, 'agency_amount', 0));
	$smarty->assign('st_agency_calc', _billing_cell($_ag_rate,
		BillingCalc::roleAmount($r_base, $_ag_rate), 0, $_ag_amount));
	$smarty->assign('st_agency_rate', $_ag_rate > 0 ? $_ag_rate : '');
	// PTĐT phụ trách quỹ căn, không theo người bán → cấp giao dịch.
	// Cất ở khoá riêng, KHÔNG nhét vào dep_logs: dashboard đang lọc quyền bằng
	// JSON_EXTRACT(more_information,"$.dep_logs.*") ở 10 chỗ. Lưu ý dễ nhầm — trong dep_logs,
	// head_of_team_id là TPKD còn head_of_dep_id là GĐKD, tức TRÙNG TÊN nhưng KHÁC CẤP
	// với cột billing_sale.head_of_dep_id (là TPKD của dòng sale).
	// Danh sách nhân sự render SẴN cho mọi select vai trò trong modal. Dùng chung 1 mảng thay vì
	// để mỗi select tự preload từ server: vừa tránh biến thể preload làm rơi lựa chọn có sẵn,
	// vừa bỏ được 7 request tải toàn bộ nhân sự mỗi lần mở modal.
	$clsQB = DB::table($clsProfile->tbl);
	$clsQB->select("{$clsProfile->pkey},CONCAT(`code`,'-',`full_name`) as `full_name`", false);
	$clsQB->where("is_trash", 0);
	$clsQB->where("is_active", 1);
	$clsQB->where("status_id", _STATUS_STAFF_OFF_ID, "<>");
	$smarty->assign('st_staffs', $clsQB->get());
	$smarty->assign('st_is_channel', $is_channel ? 1 : 0);
	$smarty->assign('st_is_f2', $is_f2 ? 1 : 0);
	$smarty->assign('st_deal_type', $deal_type);
	// Vai trò cấp GIAO DỊCH: PTĐT ăn bậc thang trên TỔNG doanh số cả căn; Giám đốc dự án
	// và TP GDDA thì tỷ lệ theo quyết định từng dự án nên KHÔNG tra bậc — nhập tay.
	// Cùng cơ chế để trống = tự tính, gõ vào = đè.
	$st_bill_roles = array();
	foreach(BillingCalc::billingRolePrefixes() as $_rk => $_pre){
		$_pid = (int) $core->get_field($more_information, $_pre.'_id', 0);
		$_de_r = (float) $core->get_field($more_information, $_pre.'_rate', 0);
		$_de_a = (float) $core->get_field($more_information, $_pre.'_amount', 0);
		$_bac = 0;
		if($_pid > 0 && isset($st_tiers[$_rk])){
			$_bac = BillingCalc::resolveRate($st_tiers[$_rk], $clsCommissionEntry->cumulativeFor($_rk, $_pid, $period_stamp));
		}
		$_rate = $_de_r > 0 ? $_de_r : $_bac;
		$st_bill_roles[$_pre] = _billing_cell($_bac,
			$_rate > 0 ? BillingCalc::roleAmount($total_realized, $_rate) : 0, $_de_r, $_de_a);
		$st_bill_roles[$_pre]['id'] = $_pid;
	}
	$smarty->assign('st_bill_roles', $st_bill_roles);
	$oneProject = $clsProject->getOne((int) $oneBilling['project_id'], "`title`");
	$smarty->assign('st_r_base', $r_base);
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('project_title', !empty($oneProject) ? $oneProject['title'] : '');
	$smarty->assign('st_more', $more_information);
	$smarty->assign('st_rows', $rows);
	$smarty->assign('st_whole', $whole);
	$smarty->assign('st_total_realized', $total_realized);
	// Chi phí công ty: ƯU TIÊN số đã lưu ở `back_office`, chưa có thì mới tính từ tỷ lệ.
	// Dùng chung CommissionEntry::backOfficeRows() để màn này và engine ghi sổ không bao giờ
	// ra hai con số khác nhau — nếu tách hai đường thì người dùng nhìn một số, hệ thống lưu số khác.
	// Gốc back office là AH CẢ CĂN, không phải tổng realized các dòng sale. Căn không có dòng sale
	// (F2 bán thẳng cho đối tác) thì tổng dòng = 0, nhưng công ty vẫn ăn chi phí trên doanh số sau
	// giảm trừ của căn ⇒ dùng $whole['realized']. commission_value do admin nhập; chưa nhập (=0)
	// thì để 0, KHÔNG tự lấy giá bán thay thế. GD có dòng sale giữ nguyên tổng realized như cũ.
	$st_bo_base = $total_realized;
	if(empty($shares)){
		$st_bo_base = BillingCalc::money($core->get_field($more_information, 'commission_value', 0)) > 0 ? $whole['realized'] : 0;
	}
	$st_bo_rows = $clsCommissionEntry->backOfficeRows($more_information, $st_bo_base, $_cfg);
	// Tổng = CHÍNH các dòng phát ra. Không cộng lại từ tỷ lệ gốc: quỹ Back Office là mốc
	// trung gian, cộng cả nó lẫn 5 phòng con là gấp đôi phần back.
	$st_bo_total = 0;
	foreach($st_bo_rows as $_r){
		$st_bo_total += $_r['amount'];
	}
	$smarty->assign('st_bo_rows', $st_bo_rows);
	$smarty->assign('st_bo_total', $st_bo_total);
	$smarty->assign('st_can_config', _billing_commission_can_manage() ? 1 : 0);
	$smarty->assign('st_is_settled', (int) $core->get_field($more_information, 'is_settled', 0));
	$html = $core->build('_ajax.billing_settlement.tpl');
	echo json_encode(array('uid' => $uid, 'html' => $html)); die();
}
/** Lưu số quyết toán. Server luôn là chân lý: tính lại bằng BillingCalc, không tin số JS gửi lên. */
function default_save_billing_settlement(){
	global $core, $clsISO, $profile_id;
	$clsConfigurationSave = new Configuration();
	if(!_billing_commission_can_manage()){
		echo json_encode(array('result' => false, 'msg' => 'Bạn không có quyền quyết toán giao dịch.')); die();
	}
	$clsBilling = new Billing();
	$clsBillingSale = new BillingSale();
	$billing_id = (int) Input::post('billing_id', 0);
	$oneBilling = $clsBilling->getOne($billing_id);
	if(empty($oneBilling)){
		echo json_encode(array('result' => false, 'msg' => 'Không tìm thấy giao dịch.')); die();
	}
	// GD huỷ/xoá vẫn hiện trong danh sách nên vẫn bấm được menu — chặn ở đây, không thì
	// quyết toán xong sẽ ghi lại doanh số thi đua cho một giao dịch đã không còn hiệu lực
	if((int) $oneBilling['is_cancel'] == 1 || (int) $oneBilling['is_trash'] == 1){
		echo json_encode(array('result' => false, 'msg' => 'Giao dịch đã huỷ hoặc đã xoá, không quyết toán được.')); die();
	}
	$more_information = $clsISO->to_array_json($oneBilling['more_information']);
	// Ô nào form gửi lên thì ghi ô đó — giữ nguyên cơ chế của form bước 1
	_billing_set_if_posted($more_information, 'total_deduction', $clsISO->processSmartNumber(Input::post('total_deduction')));
	_billing_set_if_posted($more_information, 'total_deduction_percent_sales', $clsISO->convertToNumber(Input::post('total_deduction_percent_sales')));
	// "Sales chịu" (AD) là ô TỰ TÍNH CHO SỬA ĐÈ. Ô hiện sẵn AB×AC nên gửi lại đúng số đó
	// KHÔNG phải là đè: phải XOÁ khoá đi, để giảm trừ đổi thì AD đổi theo. Giữ lại thì con
	// số đóng băng ngay lần quyết toán đầu.
	if(array_key_exists('total_deduction_sales', $_POST)){
		$_ad_auto = round($clsISO->processSmartNumber($core->get_field($more_information, 'total_deduction', 0))
			* $clsISO->convertToNumber($core->get_field($more_information, 'total_deduction_percent_sales', 0)) / 100);
		$_ad_de = _billing_override($clsISO->processSmartNumber(Input::post('total_deduction_sales')), $_ad_auto);
		if($_ad_de > 0){
			$more_information['total_deduction_sales'] = $_ad_de;
		} else {
			unset($more_information['total_deduction_sales']);
		}
	// AF "Công ty chịu trừ hoa hồng": cùng cơ chế — bằng AB−AD−AE thì không lưu, khác mới lưu.
	if(array_key_exists('total_deduction_company_commission', $_POST)){
		$_ab = $clsISO->processSmartNumber($core->get_field($more_information, 'total_deduction', 0));
		// Đọc AD từ chính $more_information vừa ghi ở khối trên, KHÔNG dùng $_ad_de:
		// biến đó chỉ tồn tại khi ô Sales chịu được gửi lên. Form nào không có ô đó thì
		// $_ad_de chưa khai báo, và một biến rỗng lọt vào phép tính tiền là sai âm thầm.
		$_ac = $clsISO->convertToNumber($core->get_field($more_information, 'total_deduction_percent_sales', 0));
		$_ad_hl = (array_key_exists('total_deduction_sales', $more_information)
				&& $more_information['total_deduction_sales'] !== '')
			? $clsISO->processSmartNumber($more_information['total_deduction_sales'])
			: round($_ab * $_ac / 100);
		$_ae = $clsISO->processSmartNumber($core->get_field($more_information, 'total_deduction_company', 0));
		$_af_auto = $_ab - $_ad_hl - $_ae;
		$_af_de = _billing_override($clsISO->processSmartNumber(Input::post('total_deduction_company_commission')), $_af_auto);
		if($_af_de > 0){
			$more_information['total_deduction_company_commission'] = $_af_de;
		} else {
			unset($more_information['total_deduction_company_commission']);
		}
	}
	}
	_billing_set_if_posted($more_information, 'total_deduction_company', $clsISO->processSmartNumber(Input::post('total_deduction_company')));
	_billing_set_if_posted($more_information, 'hot_bonus_customer', $clsISO->processSmartNumber(Input::post('hot_bonus_customer')));
	_billing_set_if_posted($more_information, 'hot_bonus_agency', $clsISO->processSmartNumber(Input::post('hot_bonus_agency')));
	_billing_set_if_posted($more_information, 'recovery_rate_customer', $clsISO->convertToNumber(Input::post('recovery_rate_customer')));
	// Đại lý: cấp GIAO DỊCH, ăn trên R của cả căn. Số tiền tự tính nhưng cho gõ đè.
	_billing_set_if_posted($more_information, 'agency_rate', $clsISO->convertToNumber(Input::post('agency_rate')));
	// Vai trò cấp GIAO DỊCH — khoá riêng, KHÔNG đụng dep_logs (đang là khoá lọc quyền dashboard).
	// Người + % + tiền đi cùng một bảng tiền tố, khỏi liệt kê tay từng khoá.
	foreach(BillingCalc::billingRolePrefixes() as $_pre){
		_billing_set_if_posted($more_information, $_pre.'_id', (int) Input::post($_pre.'_id', 0));
	}
	if(array_key_exists('channel_exec_id', $_POST)){
		// Admin đã chọn tay → handler lưu GD không được suy lại đè lên
		$more_information['channel_manual'] = 1;
	}
	// % và số tiền của các vai trò cấp giao dịch: để trống = engine tự tính, gõ vào = đè.
	foreach(BillingCalc::billingRolePrefixes() as $_pre){
		_billing_set_if_posted($more_information, $_pre.'_rate', $clsISO->convertToNumber(Input::post($_pre.'_rate')));
		_billing_set_if_posted($more_information, $_pre.'_amount', $clsISO->processSmartNumber(Input::post($_pre.'_amount')));
	}

	$inputs = _billing_settlement_inputs($more_information);
	$totalgrand = $oneBilling['totalgrand'];
	$whole = BillingCalc::compute(array_merge($inputs, array('share_ratio' => 100, 'totalgrand' => $totalgrand)));
	// R đã giải (có fallback sang giá trị bán) — gốc tính tiền đại lý
	$r_base_save = $clsISO->processSmartNumber($core->get_field($more_information, 'commission_value', 0));
	if($r_base_save <= 0){
		$r_base_save = $clsISO->processSmartNumber($totalgrand);
	}
	// Số máy tính ra lưu dưới tiền tố calc_* để báo cáo dùng sẵn, tách hẳn khỏi ô nhập ở trên
	$more_information['calc_deduction_sales'] = $whole['deduction_sales'];
	$more_information['calc_deduction_company_commission'] = $whole['deduction_company_commission'];
	$more_information['is_settled'] = 1;
	$more_information['settled_date'] = time();
	$more_information['settled_by'] = (int) $profile_id;

	$shares = $clsBillingSale->getByBilling($billing_id);
	if(empty($shares)){
		_billing_write_audit($billing_id, 'settle_reject', sprintf('Bỏ qua quyết toán GD #%d: không có dòng phân bổ nào', $billing_id));
		echo json_encode(array('result' => false, 'msg' => 'Giao dịch chưa có dòng phân bổ doanh số. Mở giao dịch bấm Lưu để tạo trước khi quyết toán.')); die();
	}
	// Chặn TRƯỚC vòng lặp, không để tới lúc ghi sổ mới chặn: vòng lặp bên dưới ghi thẳng
	// commission_value xuống từng dòng chia, mà cột đó là gốc cộng bậc thang cả tháng.
	// Chặn muộn thì giao dịch tuy không được quyết toán nhưng đã kịp thổi doanh số tháng
	// của người quản lý lên — đo live GD #316 (tỷ lệ 200%) ghi 141.711.187 thay vì 70.855.593.
	$sum_ratio_check = 0;
	foreach($shares as $_oShare){
		$sum_ratio_check += BillingCalc::percent(isset($_oShare['share_ratio']) ? $_oShare['share_ratio'] : null, 100);
	}
	if(abs($sum_ratio_check - 100) > 1){
		_billing_write_audit($billing_id, 'settle_reject', sprintf('Từ chối quyết toán GD #%d: tổng tỷ lệ chia = %s%%, phải bằng 100%%', $billing_id, $sum_ratio_check));
		echo json_encode(array('result' => false, 'msg' => sprintf('Tổng tỷ lệ chia của các sale đang là %s%%, phải bằng 100%%. Mở giao dịch sửa lại tỷ lệ rồi quyết toán.', $sum_ratio_check))); die();
	}
	// Loại giao dịch quyết định có áp mặc định 50% hoa hồng sale hay không — phải đi cùng
	// $inputs xuống BillingCalc, nếu không server tính một đằng còn chỗ chặn dưới một nẻo.
	$deal_type_save = _billing_deal_type($shares, $more_information);
	// Bậc thang + kỳ tính: nạp MỘT lần cho cả vòng lặp, dùng để biết số nào là "máy tính ra"
	// mà phân biệt với số người gõ đè.
	$clsCommissionEntrySave = new CommissionEntry();
	$_agree_save = (int) $core->get_field($oneBilling, 'agree_date', 0);
	$period_stamp_save = $_agree_save > 0 ? $_agree_save : (int) $core->get_field($oneBilling, 'deposit_date', 0);
	$_saved_tiers_s = $clsConfigurationSave->getValue('billing_commission_tiers', '');
	$tiers_save = !empty($_saved_tiers_s) ? json_decode($_saved_tiers_s, true) : array();
	if(!is_array($tiers_save)){ $tiers_save = array(); }
	foreach(BillingCalc::tiersDefault() as $_k => $_v){
		if(empty($tiers_save[$_k])){ $tiers_save[$_k] = $_v; }
	}
	$inputs['deal_type'] = $deal_type_save;
	// AG/AI/AJ/AL/AM đều per-sale: mỗi dòng có % hoa hồng riêng, tính và ghi riêng
	$posted_shares = Input::post('share', array());
	if(!is_array($posted_shares)){ $posted_shares = array(); }
	$realized_primary = 0;
	$total_sales_amount = $total_sales_net = $realized_total_save = 0;
	foreach($shares as $_oShare){
		$sid = (int) $_oShare['billing_sale_id'];
		$share_rate = isset($posted_shares[$sid]['ai'])
			? $clsISO->convertToNumber($posted_shares[$sid]['ai'])
			: _billing_share_rate($_oShare, $more_information);
		// KHÔNG ép mặc định ở đây: 0 là con số THẬT — căn bán qua đối tác F2 hoặc CTV thì sale
		// nội bộ ăn 0đ (user chốt 29/07). Đo 370 dòng sổ: bỏ mặc định đi thì khớp tăng 240 -> 311.
		// Mặc định 50% được gán lúc TẠO dòng chia, xem chỗ $_rate_new.
		$calc = BillingCalc::compute(array_merge($inputs, array(
			'share_ratio' => $_oShare['share_ratio'],
			'sales_commission_rate' => $share_rate,
			'totalgrand' => $totalgrand
		)));
		$_p = isset($posted_shares[$sid]) ? $posted_shares[$sid] : array();
		// CTV ăn trên AG của CHÍNH sale này (không phải trên R cả căn như đại lý).
		// Có ca CTV nhận tiền khoán không theo % nên số tiền vẫn cho gõ đè.
		$ctv_rate = isset($_p['ctv_rate']) ? $clsISO->convertToNumber($_p['ctv_rate']) : 0;
		$ctv_auto = round($calc['realized'] * $ctv_rate / 100);
		$ctv_de = isset($_p['ctv_amount']) ? _billing_override($clsISO->processSmartNumber($_p['ctv_amount']), $ctv_auto) : 0;
		$ctv_amount = $ctv_de > 0 ? $ctv_de : $ctv_auto;
		// TPKD/GĐKD: ô hiện sẵn số bậc thang nên gửi lại đúng số đó KHÔNG phải là đè.
		// Xem _billing_override(). Không so thì mọi lần quyết toán đều đóng dấu số cứng,
		// doanh số hay bậc thang đổi về sau mà tiền đứng im.
		$_bac = array('SALES_MANAGER' => 0, 'SALES_DIRECTOR' => 0);
		foreach(array('SALES_MANAGER' => (int) $core->get_field($_oShare, 'head_of_dep_id', 0),
			'SALES_DIRECTOR' => (int) $core->get_field($_oShare, 'sale_dir_id', 0)) as $_rk => $_pid){
			if($_pid > 0){
				$_bac[$_rk] = BillingCalc::resolveRate(isset($tiers_save[$_rk]) ? $tiers_save[$_rk] : array(),
					$clsCommissionEntrySave->cumulativeFor($_rk, $_pid, $period_stamp_save));
			}
		}
		$head_rate = isset($_p['tpkd_rate']) ? _billing_override($clsISO->convertToNumber($_p['tpkd_rate']), $_bac['SALES_MANAGER'], 0.001) : 0;
		$head_amount = isset($_p['tpkd_amount']) ? _billing_override($clsISO->processSmartNumber($_p['tpkd_amount']),
			BillingCalc::roleAmount($calc['realized'], $head_rate > 0 ? $head_rate : $_bac['SALES_MANAGER'])) : 0;
		$dir_rate = isset($_p['gdkd_rate']) ? _billing_override($clsISO->convertToNumber($_p['gdkd_rate']), $_bac['SALES_DIRECTOR'], 0.001) : 0;
		$dir_amount = isset($_p['gdkd_amount']) ? _billing_override($clsISO->processSmartNumber($_p['gdkd_amount']),
			BillingCalc::roleAmount($calc['realized'], $dir_rate > 0 ? $dir_rate : $_bac['SALES_DIRECTOR'])) : 0;
		$clsBillingSale->updateOne($sid, array(
			'commission_value' => $calc['realized'],
			'share_value' => $calc['share_value'],
			'sales_commission_rate' => $share_rate,
			'head_of_dep_id' => isset($posted_shares[$sid]['tpkd']) ? (int) $posted_shares[$sid]['tpkd'] : (int) $core->get_field($_oShare, 'head_of_dep_id', 0),
			'sale_dir_id' => isset($posted_shares[$sid]['gdkd']) ? (int) $posted_shares[$sid]['gdkd'] : (int) $core->get_field($_oShare, 'sale_dir_id', 0),
			'ctv_name' => isset($posted_shares[$sid]['ctv_name']) ? trim((string) $posted_shares[$sid]['ctv_name']) : $core->get_field($_oShare, 'ctv_name', ''),
			'ctv_rate' => $ctv_rate,
			'ctv_amount' => $ctv_amount,
			// Tỷ lệ/tiền TPKD-GĐKD: bằng số bậc thang = không đè (ghi 0), khác = đè cho riêng GD này.
			// Ghi 0 chứ không bỏ qua — có vậy Admin mới XOÁ được số đã đè.
			'head_of_dep_rate' => $head_rate,
			'head_of_dep_amount' => $head_amount,
			'sale_dir_rate' => $dir_rate,
			'sale_dir_amount' => $dir_amount
		));
		// Không chặn (sheet gốc có ô ghi 1388,89% nên vẫn có thể là ca thật), nhưng phải truy được
		if($share_rate > 100){
			_billing_write_audit($billing_id, 'rate_over_100', sprintf('GD #%d: %s nhận %% hoa hồng = %s (lớn hơn 100%%)',
				$billing_id, $_oShare['seller_name'], $share_rate));
		}
		$total_sales_amount += $calc['sales_amount'];
		$total_sales_net += $calc['sales_net'];
		$realized_total_save += $calc['realized'];
		if((int) $_oShare['is_primary'] == 1){
			$realized_primary = $calc['realized'];
		}
	}
	// Chi phí công ty: tỷ lệ là hằng số, KHÔNG lưu. Ô trên form nay luôn hiện sẵn số máy tính
	// (user chốt 28/07: điền sẵn, cho sửa) nên "để trống = tự tính" không còn dùng được để nhận
	// biết ý định nữa — thay bằng SO VỚI SỐ MÁY TÍNH: đúng bằng thì không lưu gì, khác mới lưu.
	// Không làm vậy thì mọi lần quyết toán đều đóng dấu số cứng, doanh số sửa lại mà tiền đứng im.
	// Bộ khoá lấy từ CHÍNH cấu hình đang chạy, không hardcode: admin thêm khoản mới mà chỗ này
	// vẫn duyệt bộ mặc định thì khoản đó bị bỏ rơi không lưu.
	$_bo_cfg_save = $clsConfigurationSave;
	$_bo_saved = $_bo_cfg_save->getValue('billing_backoffice_rates', '');
	$_bo_cfg = !empty($_bo_saved) ? json_decode($_bo_saved, true) : array();
	$_bo_cfg = is_array($_bo_cfg) && !empty($_bo_cfg) ? $_bo_cfg : null;
	foreach(BillingCalc::backOfficeRows($realized_total_save, $_bo_cfg) as $_r){
		$_k = 'bo_'.$_r['key'].'_amount';
		if(!array_key_exists($_k, $_POST)){
			continue;
		}
		$_v = $clsISO->processSmartNumber(Input::post($_k));
		// Lệch 1đ là do làm tròn hiển thị, không phải người sửa
		if($_v > 0 && abs($_v - $_r['amount']) > 1){
			$more_information[$_k] = $_v;
		} else {
			unset($more_information[$_k]);
		}
	}

	// Tổng cả căn = CỘNG từng sale, không tính lại ở tỷ lệ 100%: mỗi sale một % hoa hồng riêng
	$more_information['sales_amount'] = $total_sales_amount;
	$more_information['sales_net_amount'] = $total_sales_net;
	// Đại lý tính trên R của cả căn. Ô hiện sẵn số máy tính nên gửi lại đúng số đó KHÔNG
	// phải là đè — xem _billing_override().
	if(array_key_exists('agency_amount', $_POST)){
		$_ag_auto = BillingCalc::roleAmount($r_base_save,
		$clsISO->convertToNumber($core->get_field($more_information, 'agency_rate', 0)));
		$_ag_de = _billing_override($clsISO->processSmartNumber(Input::post('agency_amount')), $_ag_auto);
		$more_information['agency_amount'] = $_ag_de > 0 ? $_ag_de : $_ag_auto;
	}
	// Ghi sổ hoa hồng TRƯỚC, đặt cờ đã-quyết-toán trên billing SAU. Hỏng dở giữa chừng thì
	// giao dịch nằm ở trạng thái *chưa quyết toán* (mở lại bấm là xong) — an toàn hơn hẳn
	// *đã quyết toán mà không có dòng tiền nào*. MyISAM không có transaction để lùi lại.
	$clsCommissionEntry = new CommissionEntry();
	$shares_saved = $clsBillingSale->getByBilling($billing_id); // đọc lại để chốt đúng số vừa ghi xuống
	$commission_id = $clsCommissionEntry->upsertFromBilling(
		$oneBilling,
		$shares_saved,
		$more_information,
		$profile_id,
		// Cùng hàm nhận diện mà modal dùng để quyết hiện ô nào: hai loại giao dịch ăn hai
		// bộ hoa hồng tách hẳn nhau, sổ phải ghi đúng bộ mà người nhập nhìn thấy
		_billing_deal_type($shares_saved, $more_information)
	);
	if($commission_id <= 0){
		_billing_write_audit($billing_id, 'settle_reject', sprintf('Không ghi được sổ hoa hồng cho GD #%d, huỷ quyết toán', $billing_id));
		echo json_encode(array('result' => false, 'msg' => 'Không ghi được sổ hoa hồng. Giao dịch chưa được quyết toán, vui lòng thử lại.')); die();
	}
	$more_information['commission_id'] = $commission_id;
	$msg = '_error';
	if($clsBilling->updateOne($billing_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'realized_sales_compete' => $realized_primary,
		'realized_sales_commission' => $total_sales_amount
	))){
		$msg = '_success';
		_billing_write_audit($billing_id, 'settled', sprintf('Quyết toán GD #%d: giảm trừ %s, sales chịu %s, công ty chịu trừ HH %s',
			$billing_id, number_format($clsISO->processSmartNumber($core->get_field($more_information, 'total_deduction', 0))),
			number_format($whole['deduction_sales']), number_format($whole['deduction_company_commission'])));
	}
	echo json_encode(array('result' => $msg == '_success', 'msg' => $msg)); die();
}
/** Mở popup cấu hình tỷ lệ hoa hồng bậc thang theo vị trí. */
function default_open_commission_tier(){
	global $smarty, $core, $clsISO, $clsConfiguration;
	if(!_billing_commission_can_manage()){
		echo json_encode(array('result' => false, 'msg' => 'Chỉ Admin trưởng mới cấu hình được mục này.')); die();
	}
	$roles = _billing_commission_roles();
	$defaults = _billing_commission_defaults();
	$saved = $clsConfiguration->getValue('billing_commission_tiers', '');
	$tiers = !empty($saved) ? json_decode($saved, true) : array();
	if(!is_array($tiers)){ $tiers = array(); }
	// Vị trí nào chưa có cấu hình → nạp bậc mặc định
	foreach($roles as $key => $label){
		if(empty($tiers[$key])){ $tiers[$key] = $defaults[$key]; }
	}
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('commission_roles', $roles);
	$smarty->assign('commission_tiers', $tiers);
	$html = $core->build('_ajax.commission_tier.tpl');
	echo json_encode(array('uid' => $uid, 'html' => $html)); die();
}
/** Lưu cấu hình tỷ lệ hoa hồng bậc thang (Configuration key billing_commission_tiers). */
function default_save_commission_tier(){
	global $clsISO, $clsConfiguration;
	if(!_billing_commission_can_manage()){
		echo json_encode(array('result' => false, 'msg' => 'Chỉ Admin trưởng mới cấu hình được mục này.')); die();
	}
	$roles = _billing_commission_roles();
	$posted = Input::post('tiers', array());
	if(!is_array($posted)){ $posted = array(); }
	$clean = array();
	foreach($roles as $key => $label){
		$rows = (isset($posted[$key]) && is_array($posted[$key])) ? $posted[$key] : array();
		$list = array();
		foreach($rows as $r){
			if(!is_array($r)){ continue; }
			$min = isset($r['min']) ? $clsISO->processSmartNumber($r['min']) : 0;
			$rate = isset($r['rate']) ? (float) str_replace(',', '.', $r['rate']) : 0;
			if($rate <= 0){ continue; } // bỏ dòng rỗng/không hợp lệ
			$list[] = array('min' => (float) $min, 'rate' => $rate);
		}
		usort($list, function($a, $b){ return $a['min'] <=> $b['min']; }); // mốc tăng dần
		$clean[$key] = $list;
	}
	$clsConfiguration->updateValue('billing_commission_tiers', json_encode($clean, JSON_UNESCAPED_UNICODE));
	echo json_encode(array('result' => true, 'msg' => 'Đã lưu cấu hình tỷ lệ hoa hồng theo vị trí.')); die();
}
/** Mở popup cấu hình tỷ lệ chi phí công ty (Ban lãnh đạo + quỹ Back Office). */
function default_open_backoffice_rate(){
	global $smarty, $core, $clsISO, $clsConfiguration;
	if(!_billing_commission_can_manage()){
		echo json_encode(array('result' => false, 'msg' => 'Chỉ Admin trưởng mới cấu hình được mục này.')); die();
	}
	$saved = $clsConfiguration->getValue('billing_backoffice_rates', '');
	$cfg = !empty($saved) ? json_decode($saved, true) : array();
	if(!is_array($cfg) || empty($cfg)){
		$cfg = BillingCalc::backOfficeDefault();
	}
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('bo_cfg', $cfg);
	$html = $core->build('_ajax.backoffice_rate.tpl');
	echo json_encode(array('uid' => $uid, 'html' => $html)); die();
}
/**
 * Lưu cấu hình tỷ lệ chi phí công ty (Configuration key billing_backoffice_rates).
 *
 * Chỉ nhận TỶ LỆ từ form. `label`/`department_id` lấy lại từ cấu hình đang lưu (hoặc bộ mặc định)
 * — chúng là ánh xạ sang phòng ban thật, để form gửi lên thì ai sửa DOM cũng đổi được nơi nhận tiền.
 */
function default_save_backoffice_rate(){
	global $clsISO, $clsConfiguration;
	if(!_billing_commission_can_manage()){
		echo json_encode(array('result' => false, 'msg' => 'Chỉ Admin trưởng mới cấu hình được mục này.')); die();
	}
	$saved = $clsConfiguration->getValue('billing_backoffice_rates', '');
	$goc = !empty($saved) ? json_decode($saved, true) : array();
	if(!is_array($goc) || empty($goc)){
		$goc = BillingCalc::backOfficeDefault();
	}
	$posted = Input::post('bo', array());
	if(!is_array($posted)){ $posted = array(); }
	$clean = array();
	foreach($goc as $key => $muc){
		$gui = isset($posted[$key]) && is_array($posted[$key]) ? $posted[$key] : array();
		$moi = array('label' => isset($muc['label']) ? $muc['label'] : $key);
		if(isset($muc['department_id'])){
			$moi['department_id'] = (int) $muc['department_id'];
		}
		$moi['rate_ah'] = isset($gui['rate_ah'])
			? (float) str_replace(',', '.', $gui['rate_ah'])
			: (isset($muc['rate_ah']) ? (float) $muc['rate_ah'] : 0);
		if($moi['rate_ah'] < 0){ $moi['rate_ah'] = 0; }
		if(!empty($muc['chia']) && is_array($muc['chia'])){
			$con_gui = isset($gui['chia']) && is_array($gui['chia']) ? $gui['chia'] : array();
			$con = array();
			$tong = 0;
			foreach($muc['chia'] as $ckey => $cmuc){
				$rate = isset($con_gui[$ckey]['rate_pool'])
					? (float) str_replace(',', '.', $con_gui[$ckey]['rate_pool'])
					: (isset($cmuc['rate_pool']) ? (float) $cmuc['rate_pool'] : 0);
				if($rate < 0){ $rate = 0; }
				$tong += $rate;
				$con[$ckey] = array(
					'label' => isset($cmuc['label']) ? $cmuc['label'] : $ckey,
					'department_id' => isset($cmuc['department_id']) ? (int) $cmuc['department_id'] : 0,
					'rate_pool' => $rate
				);
			}
			// Tổng khác 100% là chia hụt hoặc vượt quỹ — chặn ở server, không tin JS.
			// Cho lệch 0,01% vì tỷ lệ có thể lẻ (ví dụ chia 3 phòng đều nhau).
			if(abs($tong - 100) > 0.01){
				echo json_encode(array('result' => false, 'msg' => sprintf(
					'Các phòng trong "%s" đang cộng lại %s%%, phải bằng 100%% quỹ.',
					$moi['label'], rtrim(rtrim(number_format($tong, 2, ',', '.'), '0'), ',')))); die();
			}
			$moi['chia'] = $con;
		}
		$clean[$key] = $moi;
	}
	$clsConfiguration->updateValue('billing_backoffice_rates', json_encode($clean, JSON_UNESCAPED_UNICODE));
	_billing_write_audit(0, 'backoffice_rate', 'Đổi tỷ lệ chi phí công ty: '.json_encode($clean, JSON_UNESCAPED_UNICODE));
	// Trả kèm bộ tỷ lệ mới để màn quyết toán đang mở tính lại tiền ngay, khỏi phải đóng mở lại.
	// Gửi CẢ tỷ lệ của mục lẫn tỷ lệ quỹ cha: tiền làm tròn hai lần nên JS phải tính đúng
	// hai tầng, gộp thành một tỷ lệ tương đương là lệch 1đ so với số server ghi.
	$rows = array();
	foreach(BillingCalc::backOfficeRows(0, $clean) as $_r){
		$rows[] = array('key' => $_r['key'], 'rate' => $_r['rate'], 'rate_parent' => $_r['rate_parent']);
	}
	echo json_encode(array('result' => true, 'msg' => 'Đã lưu tỷ lệ chi phí công ty.', 'rows' => $rows)); die();
}
?>