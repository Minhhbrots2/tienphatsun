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
}
function default_campaign(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProperty = new Property();
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo kết quả kinh doanh - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_sale(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProperty = new Property();
	##
	$list_departments = array();
	$clsProperty->makeOption(0, '_DEPARTMENT', 0,  $list_departments);
	$assign_list["list_departments"] = $list_departments;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo kết quả kinh doanh - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
} 
function default_load_report_sales(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProperty = new Property();
	$clsMember = new Member();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$billing_type = Input::post('billing_type','all');
	$smarty->assign('billing_type', $billing_type);
	$department_id = Input::post('department_id',0); 
	$date_range = Input::post('date_range');
	$temporary = @explode('-', $date_range);
	$start_date = $temporary[0];
	$end_date 	= $temporary[1];
	$start_time = $clsISO->convertTextToTime($start_date);
	$end_time 	= $clsISO->convertTextToTime($end_date, "23:59:59");
	$cond = $query = $cond_billing = "";
	if($department_id > 0){
		$query.= " and `staff_id` in (select `profile_id` from {$clsProfile->tbl} 
			where (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%'))";
		$cond.= " and (`t1`.`department_id`='{$department_id}' or `t1`.`list_department_id` like '%|{$department_id}|%')";
	}
	$query.= " and (`deposit_date` between '{$start_time}' and '{$end_time}')";
	$cond_billing.= " and (`deposit_date` between '{$start_time}' and '{$end_time}')";
	//$clsISO->print_pre(date('d/m/Y',$start_time)); die();
	$field = "`t1`.`{$clsProfile->pkey}`,`t1`.`role_id`,`t1`.`code`,`t1`.`full_name`, `t1`.`first_name`,`t1`.`last_name`,`t1`.`start_date`,`t1`.`reg_date`,`t2`.`title`";
	/*$list_staffs = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as `t1` 
		inner join {$clsProperty->tbl} as `t2` on `t1`.`department_id`=`t2`.`property_id` 
		where `t1`.`is_trash`=0 and `t1`.`is_active`=1 and `t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and `t2`.`property_type`='_DEPARTMENT' {$cond} order by `t2`.`order_no` ASC");*/
	$list_staffs = $clsProfile->getProfileCached();
	// and `t2`.`parent_id`='"._DEPARTMENT_SALE_ID."'
	$total_billings = $total_sales = 0;
	$field = "{$clsProperty->pkey},title,bgcolor";
	$list_billing_types = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BILLING_TYPE' order by `order_no` ASC", $field);
	if(!empty($list_billing_types)){
		foreach($list_billing_types as $key => $val){
			$property_id= $val[$clsProperty->pkey];
			$total_in_billings = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `is_alliance`='0' 
			and `billing_type`='{$property_id}'".$query, $field);
			$list_billing_types[$key]['total_billings'] = $total_in_billings;
			$total_billings += $total_in_billings;
		}
	}
	if($billing_type != "all"){
		$cond_billing.= " and `billing_type`='{$billing_type}'";
	}
	if(!empty($list_staffs)){
		$arr_property_cached = array();
		foreach($list_staffs as $key => $val){
			$role_id = $val['role_id'];
			$staff_id = $val[$clsMember->pkey];
			$more_profile = $val["more_information"];
//			$clsISO->print_pre($more_profile);die;
			$list_staffs[$key]["department_name"] = $more_profile["department_name"];
			###
			$total_in_billings = $total_in_sales = 0;
			$field = "{$clsBilling->pkey},totalgrand";
			$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0 and `staff_id`='{$staff_id}'".$cond_billing);
			if(!empty($list_billings)){
				$total_in_billings += count($list_billings);
				foreach($list_billings as $okey => $oval){
					$total_in_sales += $clsISO->processSmartNumber($oval['totalgrand']);
				}
			}
			$list_staffs[$key]['total_sales'] = $total_in_sales;
			$list_staffs[$key]['total_billings'] = $total_in_billings;
			if(isset($arr_property_cached[$role_id])){
				$list_staffs[$key]['role'] = $arr_property_cached[$role_id];
			} else {
				$arr_property_cached[$role_id] = $clsProperty->getTitle($role_id);
				$list_staffs[$key]['role'] = $arr_property_cached[$role_id];
			}
			$total_sales += $total_in_sales;
			$startDate = $val['start_date'];
			if(empty($val['start_date'])) {
				$startDate = $val['reg_date'];
			}
			$startDate = strpos($startDate, "/") ? $startDate : date("d/m/Y",$startDate);
			$list_staffs[$key]['startDate'] = $startDate;
			$start_date = strtotime(str_replace("/","-",$startDate));
			$list_staffs[$key]['rangeDate'] = $clsISO->getNumberTime($start_date);
		}
		$total_sales_arrs = @array_column($list_staffs, 'total_sales');
		@array_multisort($total_sales_arrs, SORT_DESC, $list_staffs);
	}
//	$clsISO->print_pre($list_staffs);die;
	//$clsISO->print_pre($cond_billing); die();
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('total_sales', $total_sales);
	$smarty->assign('list_staffs', $list_staffs);
	$smarty->assign('list_billing_types', $list_billing_types);
	// Return
	$html = $core->build('_ajax.sale.tpl');
	echo $html; die();
}
function default_work(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsProperty = new Property();
	$clsMember = new Member();
	if(!$clsISO->checkPermissionGroup('DIRECTOR') && !$clsISO->checkPermissionGroup('BO') && !$clsISO->checkPermissionGroup('BUSINESS_AREA') && !$clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
		header("Location: /");exit();
	}
	
	$list_preloaders = $type_of_date_arrs = $role_arrs = $arr_projects = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$type_of_date_arrs['week'] = 'Tuần';
	$type_of_date_arrs['month'] = 'Tháng';
	$assign_list["type_of_date_arrs"] = $type_of_date_arrs;
	$assign_list["current_month"] = date("Y-m");
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo Nét đẹp lao động - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;

	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_content_share(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$month = Input::post('month', date("Y-m"));
	$start_date = date('Y-m-d 00:00:00', strtotime($month . '-01'));
	$end_date = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	if($start_time < time() && $end_time > time()) {
		$end_time = time();
	}
	$cond = ""; $list_share = array();
	$list_worktimes = $list_group_worktimes = array();
	
	$arr_property_cached = $clsProperty->getArraySearchByKey("_ROLE");
	$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_dep_area = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
	$listProfile = $clsProfile->getProfileCached("active");
	
	$lstDepartment = [];
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		$department_id = (int) Input::post('department_id',0);
		if($department_id > 0){
			if(isset($list_dep_area[$department_id])) {
				$list_dep_area[$department_id]["is_not_area"] = 0;	
				$lstDepartment[$department_id] = $list_dep_area[$department_id];
			}else{
				$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
				$lstDepartment[$department_id]["is_not_area"] = 1;
			}			
		}else{
			$lstDepartment = $list_dep_area;
		}
	} else if($clsISO->checkPermissionGroup("BUSINESS_AREA"))  {	
		$department_id = (int) Input::post('department_id',0);
		$department_id = !empty($department_id) ? $department_id : $oneProfile['department_id'];
		if(isset($list_dep_area[$department_id])) {
			$list_dep_area[$department_id]["is_not_area"] = 0;
			$lstDepartment[$department_id] = $list_dep_area[$department_id];
		}else{
			$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
			$lstDepartment[$department_id]["is_not_area"] = 1;
		}	
				
	}else if($clsISO->checkPermissionGroup("SALE_DIRECTOR")){
		$department_id = !empty($oneProfile['department_id']) ? $oneProfile['department_id'] : 0;
		$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
		$lstDepartment[$department_id]["is_not_area"] = 1;
	}
	$arr_role_not_in = array(_ROLE_GD_PROJECT,_ROLE_GD_SALE,_ROLE_HEAD_SALE,_ROLE_BUSINESS_AREA_ID);
	$smarty->assign("head_sale",$arr_role_not_in);
	$cond = "`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'
		and `profile_id` not in (".implode(',',_PROFILE_SALE_NOTIN_ID).")";
	$field = "profile_id,full_name,role_id";
	$money = 50000;
	foreach ($lstDepartment as $dep_area_id => $_oDepArea) {
		$total_price_area = $total_share_area = 0;		
		$listStaff = $clsProfile->getProfileDep($dep_area_id, 0, "active");
		$list_shares = $clsShare->getAll("`share_type`='share' AND `user_id` in (".implode(',', array_keys($listStaff)).") AND (`reg_date` between '{$start_time}' and '{$end_time}') ORDER BY `reg_date` DESC","{$clsShare->pkey},reg_date,user_id,more_information");
		$arr_staff_share = [];
		foreach ($list_shares as $key => $val) {
			if(!isset($arr_staff_share[$val["user_id"]])) {
				$arr_staff_share[$val["user_id"]] = 1;
			}else{
				$arr_staff_share[$val["user_id"]] += 1;
			}
		}			
		if (!empty($listStaff)) {
			foreach($listStaff as $key => $val){
				$role_id = $val['role_id'];
				$department_id = $val[$clsProperty->pkey];
				$total_share = !empty($arr_staff_share[$val["profile_id"]]) ? $arr_staff_share[$val["profile_id"]] : 0;
				if(isset($arr_property_cached[$role_id])){
					$listStaff[$key]['role'] = $arr_property_cached[$role_id]["title"];
				} else {
					$arr_property_cached[$role_id] = $clsProperty->getOne($role_id);
					$listStaff[$key]['role'] = $arr_property_cached[$role_id]["title"];
				}
				$listStaff[$key]['no'] = $ii;
				$listStaff[$key]['total_share'] = $total_share;
				if(!$clsISO->checkHeadSale($role_id)){
					if($total_share >=8) {
						$total_price = 0;
					}else{
						$total_price = abs($total_share - 8) * $money;
						$total_fines = abs($total_share - 8) * $money;
					}
					$order_no = 0;
				} else {
					$total_price = $total_fines = 0;
					$order_no = 1;
				}
				if(isset($lst_dep[$dep_id]["total_price"])){
					$lst_dep[$dep_id]['total_price']+= $total_price;
					$lst_dep[$dep_id]['total_shares']+= $total_share;
				} else {
					$lst_dep[$dep_id]['total_price'] = $total_price;
					$lst_dep[$dep_id]['total_shares'] = $total_share;
				}
				$listStaff[$key]["total_price"] = $total_price;
				$listStaff[$key]["total_fines"] = $total_fines;
				$listStaff[$key]["order_no"] = $order_no;
				if(isset($lstDepartment[$dep_area_id]["total_price"])){
					$lstDepartment[$dep_area_id]['total_price']+= $total_price;
					$lstDepartment[$dep_area_id]['total_shares']+= $total_share;
				} else {
					$lstDepartment[$dep_area_id]['total_price'] = $total_price;
					$lstDepartment[$dep_area_id]['total_shares'] = $total_share;
				}
				++$ii;
				$total_price_area += $total_price; 
				$total_share_area+= $total_share; 
				unset($total_share,$total_price,$total_fines); 
			}
			$arr_orders = array_column($listStaff, 'order_no');
			array_multisort($arr_orders, SORT_DESC, $listStaff);
			$lstDepartment[$dep_area_id]["listStaff"] = $listStaff;
		}
		if(empty($_oDepArea["is_not_area"])) {
			$lst_dep = $_oDepArea["children"];
			if(!empty($lst_dep)) {
				foreach ($lst_dep as $dep_id => $$_oDep){
					$listStaff = $clsProfile->getProfileDep($dep_id, 0, "active");
					$list_shares = $clsShare->getAll("`share_type`='share' AND `user_id` in (".implode(',', array_keys($listStaff)).") AND (`reg_date` between '{$start_time}' and '{$end_time}') ORDER BY `reg_date` DESC","{$clsShare->pkey},reg_date,user_id,more_information");						
					$arr_staff_share = [];
					foreach ($list_shares as $key => $val) {
						if(!isset($arr_staff_share[$val["user_id"]])) {
							$arr_staff_share[$val["user_id"]] = 1;
						}else{
							$arr_staff_share[$val["user_id"]] += 1;
						}
					}	
					if(!empty($listStaff)) {
						foreach($listStaff as $key => $val){
							$role_id = $val['role_id'];
							$department_id = $val[$clsProperty->pkey];
							$total_share = !empty($arr_staff_share[$val[$clsProfile->pkey]]) ? $arr_staff_share[$val[$clsProfile->pkey]] : 0;
							if(isset($arr_property_cached[$role_id])){
								$listStaff[$key]['role'] = $arr_property_cached[$role_id]["title"];
							} else {
								$arr_property_cached[$role_id] = $clsProperty->getOne($role_id);
								$listStaff[$key]['role'] = $arr_property_cached[$role_id]["title"];
							}
							$listStaff[$key]['no'] = $ii;
							$listStaff[$key]['total_share'] = $total_share;
							if(!$clsISO->checkHeadSale($role_id)){
								if($total_share >=8) {
									$total_price = 0;
								}else{
									$total_price = abs($total_share - 8) * $money;
									$total_fines = abs($total_share - 8) * $money;
								}
								$order_no = 0;
							} else {
								$total_price = $total_fines = 0;
								$order_no = 1;
							}
							if(isset($lst_dep[$dep_id]["total_price"])){
								$lst_dep[$dep_id]['total_price']+= $total_price;
								$lst_dep[$dep_id]['total_shares']+= $total_share;
							} else {
								$lst_dep[$dep_id]['total_price'] = $total_price;
								$lst_dep[$dep_id]['total_shares'] = $total_share;
							}
							$listStaff[$key]["total_price"] = $total_price;
							$listStaff[$key]["total_fines"] = $total_fines;
							$listStaff[$key]["order_no"] = $order_no;
							++$ii;
							$total_price_area += $total_price; 
							$total_share_area+= $total_share; 
							unset($total_share,$total_price,$total_fines); 
						}
						$arr_orders = array_column($listStaff, 'order_no');
						array_multisort($arr_orders, SORT_DESC, $listStaff);
						$lst_dep[$dep_id]["listStaff"] = $listStaff;
					}										
				}
			}
			$lstDepartment[$dep_area_id]["department_child"] = $lst_dep;
			$lstDepartment[$dep_area_id]["total_price_area"] = $total_price_area;
			$lstDepartment[$dep_area_id]["total_share_area"] = $total_share_area;
			$lstDepartment[$dep_area_id]["total_fines_area"] = $total_fines_area;
		}	
	}
	$smarty->assign('lstDepartment',$lstDepartment);
	// Return
	$html = $core->build('_ajax.share.tpl');
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE);die;
}
function default_load_report_share(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile,$profile_id;
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$show = Input::post('show',"");
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
		$dep_default_id = $oneProfile["department_id"];
	}else{
		$month = Input::post('month',date("Y-m"));
		$start_date = date('Y-m-d 00:00:00', strtotime($month . '-01'));
		$end_date = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
		$start_time = strtotime($start_date);
		$end_time = strtotime($end_date);
		$dep_default_id = ($show == "home_share") ? $oneProfile["department_id"] : 0;
	}
	if($start_time < time() && $end_time > time()) {
		$end_time = time();
	}
	$start_time_prev = strtotime("-1 months", $start_time);
	$month_prev = date("Y-m",$start_time_prev);
	$end_date_prev = date('Y-m-d 23:59:59', strtotime('last day of ' . date("Y-m",$start_time_prev)));
	$end_time_prev = strtotime($end_date_prev);	
	$cond = "";
	
	$lstDepartment = $list_share = $list_worktimes = $list_group_worktimes = array();
	$arr_property_cached = $clsProperty->getArraySearchByKey("_ROLE");
	$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_dep_area = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
	// $listProfile = $clsProfile->getProfileCached("active");
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		$department_id = (int) Input::post('department_id', $dep_default_id);
		if($department_id > 0){
			if(isset($list_dep_area[$department_id])) {
				$list_dep_area[$department_id]["is_not_area"] = 0;	
				$lstDepartment[$department_id] = $list_dep_area[$department_id];
			}else{
				$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
				$is_not_area = 1;
				$lstDepartment[$department_id]["is_not_area"] = 1;
			}			
		}else{
			$lstDepartment = $list_dep_area;
		}
	} else if($clsISO->checkPermissionGroup("BUSINESS_AREA"))  {	
		$department_id = (int) Input::post('department_id',$dep_default_id);
		$department_id = !empty($department_id) ? $department_id : $oneProfile['department_id'];
		if(isset($list_dep_area[$department_id])) {
			$list_dep_area[$department_id]["is_not_area"] = 0;
			$lstDepartment[$department_id] = $list_dep_area[$department_id];
		}else{
			$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
			$is_not_area = 1;
			$lstDepartment[$department_id]["is_not_area"] = 1;
		}	
				
	} else if($clsISO->checkPermissionGroup("SALE_DIRECTOR") || $clsISO->checkSale()){
		$department_id = !empty($oneProfile['department_id']) ? $oneProfile['department_id'] : 0;
		$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
		$is_not_area = 1;
		$lstDepartment[$department_id]["is_not_area"] = 1;
	}
	$arr_role_not_in = array(_ROLE_GD_PROJECT,_ROLE_GD_SALE,_ROLE_HEAD_SALE,_ROLE_BUSINESS_AREA_ID);
	$smarty->assign("head_sale",$arr_role_not_in);
	$cond = "";
	$is_sale = 0;
	if(!$clsISO->checkHeadSale($oneProfile["role_id"]) && $clsISO->checkSale()) {
		$cond .= " AND `user_id` ='{$profile_id}'";
		$is_sale = 1;
	}
	$smarty->assign("is_sale",$is_sale);
	
	$field = "profile_id,full_name,role_id";
	$money = 50000;
	$total_shares = $total_guest_count = $total_sale_active = $total_area_active = $total_area = $total_sale = $total_dep = $total_dep_active = $total_share_prev = 0; 
	$arr_top_sale = [];
	$time_last = 0;
	$total_share_waiting = $total_share_confirm = 0;
	foreach($lstDepartment as $dep_area_id => $_oDepArea) {
		$more_info = $val['more_information'];
		$is_business_area = (int) $core->get_field($more_info, "is_business_area", 0);
		$total_share_area = $guest_count_area = $total_sale_active_area = $total_dep_active_area = $total_sale_area = $is_active = 0;
		$guest_count_dep = $total_share_dep = $total_sale_active_dep = $total_sale_dep = 0;
		$list_staffs = $clsProfile->getProfileDep($dep_area_id, 0, "active");		
		if(!empty($list_staffs)) {
			$total_share_prev += $clsShare->countItem("`user_id` in (".implode(',', array_keys($list_staffs)).") AND `share_type`='share' and (`reg_date` between '{$start_time_prev}' and '{$end_time_prev}')");
			$list_shares = $clsShare->getAll("`share_type`='share' AND `user_id` in (".implode(',', array_keys($list_staffs)).") AND (`reg_date` between '{$start_time}' and '{$end_time}') {$cond} ORDER BY `reg_date` DESC","{$clsShare->pkey},reg_date,user_id,more_information");
			$arr_share = $arr_sale_active = [];
			if(!empty($list_shares)) {
				foreach ($list_shares as $key => $v) {
					$more_staff = $v['more_information'];
					$more_staff = $clsISO->to_array_json($more_staff);
					$guest_count = (int) $core->get_field($more_staff, "guest_count", 1);
					$total_guest_count += $guest_count; ++$total_shares;
					$guest_count_dep += $guest_count; ++$total_share_dep;
					$guest_count_area += $guest_count;  ++$total_share_area;
					if(isset($arr_top_share[$v["user_id"]])) {
						$arr_top_share[$v["user_id"]]["total"] += 1;
					}else{
						$arr_top_share[$v["user_id"]] = $list_staffs[$v["user_id"]];
						$arr_top_share[$v["user_id"]]["total"] = 1;
					}
					if(empty($time_last)) {
						$time_last = $v["reg_date"];
					}
					if(!$clsISO->checkItemInArray($v["user_id"],$arr_sale_active)) {
						$arr_sale_active[] = $v["user_id"];
					}
					if(empty($more_staff["is_confirm"])) {
						++$total_share_waiting;
					}else{
						++$total_share_confirm;
					}
					unset($more_staff, $guest_count);
				}
				$sum_sale_active = !empty($arr_sale_active) ? count($arr_sale_active) : 0;
				$total_sale_active_area += $sum_sale_active;
				$total_sale_active += $sum_sale_active;
				$total_sale_active_dep += $sum_sale_active;
				if(!empty($arr_sale_active)) {
					$is_active = 1; 
					$is_active_dep = 1;
				}					
//				$clsISO->print_pre($list_shares);die;
				unset($list_shares,$arr_sale_active, $sum_sale_active);
			}
			$total_staff = count($list_staffs);
			$total_sale_area += $total_staff;
			$total_sale += $total_staff;
			$total_sale_dep += $total_staff;
			unset($total_staff);
			// $lstDepartment[$dep_area_id]["list_staffs"] = $list_staffs;
			$lstDepartment[$dep_area_id]["total_share_dep"] = $total_share_dep;
			$lstDepartment[$dep_area_id]["guest_count_dep"] = $guest_count_dep;
			$lstDepartment[$dep_area_id]["total_sale_active_dep"] = $total_sale_active_dep;
			$lstDepartment[$dep_area_id]["total_sale_dep"] = $total_sale_dep;
			++$total_dep;
			if(!empty($is_active_dep)) {
				++$total_dep_active;
			}
		}
		if(empty($_oDepArea["is_not_area"])) {
			$lst_dep = $_oDepArea["children"];
			if(!empty($lst_dep)) {
				foreach ($lst_dep as $dep_id => $_oDep){
					$listStaff = $clsProfile->getProfileDep($dep_id, 0, "active");
//						$clsISO->print_pre($listStaff);die;
					if(!empty($listStaff)) {
						$guest_count_dep = $total_share_dep = $total_sale_active_dep = $total_sale_dep = 0;
						$total_share_prev += $clsShare->countItem("`user_id` in (".implode(',', array_keys($listStaff)).") AND `share_type`='share' and (`reg_date` between '{$start_time_prev}' and '{$end_time_prev}')");
						$lst_share = $clsShare->getAll("`share_type`='share' AND `user_id` in (".implode(',', array_keys($listStaff)).") AND (`reg_date` between '{$start_time}' and '{$end_time}') ORDER BY `reg_date` DESC","{$clsShare->pkey},reg_date,user_id,more_information");
						$arr_sale_active = [];
						if(!empty($lst_share)) {
							foreach ($lst_share as $key => $v) {
								$more_staff = $v['more_information'];
								$more_staff = $clsISO->to_array_json($more_staff);
								$guest_count = (int) $core->get_field($more_staff, "guest_count", 1);
								$total_guest_count += $guest_count; ++$total_shares;
								$guest_count_dep += $guest_count; ++$total_share_dep;
								$guest_count_area += $guest_count;  ++$total_share_area;
								if(isset($arr_top_share[$v["user_id"]])) {
									$arr_top_share[$v["user_id"]]["total"] += 1;
								}else{
									$arr_top_share[$v["user_id"]] = $listStaff[$v["user_id"]];
									$arr_top_share[$v["user_id"]]["total"] = 1;
								}
								if(empty($time_last)) {
									$time_last = $v["reg_date"];
								}
								if(!$clsISO->checkItemInArray($v["user_id"],$arr_sale_active)) {
									$arr_sale_active[] = $v["user_id"];
								}								
								if(empty($more_staff["is_confirm"])) {
									++$total_share_waiting;
								}else{
									++$total_share_confirm;
								}
								unset($more_staff, $guest_count);
							}
							$sum_sale_active = !empty($arr_sale_active) ? count($arr_sale_active) : 0;
							$total_sale_active_area += $sum_sale_active;
							$total_sale_active += $sum_sale_active;
							$total_sale_active_dep += $sum_sale_active;
							if(!empty($arr_sale_active)) {
								$is_active = 1; 
								$is_active_dep = 1;
							}					
			//				$clsISO->print_pre($list_shares);die;
							unset($list_shares,$arr_sale_active, $sum_sale_active);
						}
						$total_staff = count($listStaff);
						$total_sale_area += $total_staff;
						$total_sale += $total_staff;
						$total_sale_dep += $total_staff;
						unset($total_staff);
						$lst_dep[$dep_id]["total_share_dep"] = $total_share_dep;
						$lst_dep[$dep_id]["guest_count_dep"] = $guest_count_dep;
						$lst_dep[$dep_id]["total_sale_active_dep"] = $total_sale_active_dep;
						$lst_dep[$dep_id]["total_sale_dep"] = $total_sale_dep;
					}
					++$total_dep;
				}
				if(!empty($is_active_dep)) {
					++$total_dep_active;
				}
			}
			$lstDepartment[$dep_area_id]["department_child"] = $lst_dep;
			$lstDepartment[$dep_area_id]["guest_count_area"] = $guest_count_area;
			$lstDepartment[$dep_area_id]["total_sale_active_area"] = $total_sale_active_area;
			$lstDepartment[$dep_area_id]["total_sale_area"] = $total_sale_area;
			$lstDepartment[$dep_area_id]["total_share_area"] = $total_share_area;
		}
		
		if(!empty($is_active)) {
			++$total_area_active;
		}	
	}
	$oneDep = !empty($department_id) ? $lstDepartment[$department_id] : [];
	$arr_orders = array_column($lstDepartment, 'total_share_area');
	array_multisort($arr_orders, SORT_DESC, $lstDepartment);
	if(!empty($is_not_area)){
		$arr_orders = array_column($arr_top_share, 'total');
		array_multisort($arr_orders, SORT_DESC, $arr_top_share);
	}
	$ratio = (int)(($total_share_prev > 0) ? (($total_shares - $total_share_prev)/$total_share_prev)*100 : $total_shares);
	
	// $ratio = -120;
	// $clsISO->print_pre($lstDepartment);die; 
	$smarty->assign('total_share_confirm',$total_share_confirm);
	$smarty->assign('total_share_waiting',$total_share_waiting);
	$smarty->assign('total_guest_count',$total_guest_count);
	$smarty->assign('total_shares',$total_shares);
	$smarty->assign('lstDepartment',$lstDepartment);
	$smarty->assign('total_sale_active',$total_sale_active);
	$smarty->assign('total_sale',$total_sale);
	$smarty->assign('total_dep',$total_dep);
	$smarty->assign('total_dep_active',$total_dep_active);
	$smarty->assign('total_area_active',$total_area_active);
	$smarty->assign('oneDep',$oneDep);
	$smarty->assign('ratio',$ratio);
	$smarty->assign('show',$show);
	$smarty->assign('total_share_prev',$total_share_prev);
	$smarty->assign('arr_top_share',$arr_top_share);
	$smarty->assign('time_last',$time_last);
	$smarty->assign('start_time', $start_time);
	$smarty->assign('end_time', $end_time);
	if($clsISO->_DEV()){
//		$clsISO->print_pre($lstDepartment);die;
//		$clsISO->print_pre($oneDep);die;
	}
	// Return
	$html = $core->build('_ajax.share_report.tpl');
	echo json_encode([
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_load_report_top_share(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile,$profile_id;
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	
	$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_dep_area = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
	$arr_dep_area_id = array_keys($list_dep_area);
//	$clsISO->print_pre($arr_dep_area_id);die;
	$arr_dep = $lst_staff_share = [];
	$total_share = 0;
	foreach ($list_dep_area as $dep_area_id => $_oDepArea) {
		$total_share_area = 0;
		$list_dep = $clsProperty->getArraySearchByKey("_DEPARTMENT",0,$dep_area_id);
		$listStaff = $clsProfile->getProfileDep($dep_area_id, 1, "active");
		if(!empty($listStaff)) {
			$lst_share = $clsShare->getAll("`share_type`='share' AND `user_id` in (".implode(',', array_keys($listStaff)).") GROUP BY `user_id` ORDER BY `total_share` DESC","{$clsShare->pkey},reg_date,user_id, COUNT(`user_id`) AS `total_share`");
			foreach ($lst_share as $key => $val) {
				$total_share_area += $val["total_share"];
				$total_share += $val["total_share"];
				if(!isset($lst_staff_share[$val["user_id"]])) {
					$lst_staff_share[$val["user_id"]] = $listStaff[$val["user_id"]];
					$lst_staff_share[$val["user_id"]]["total_share"] = $val["total_share"];
					$lst_staff_share[$val["user_id"]]["department_name"] = $_oDepArea["title"];
				}else{
					$lst_staff_share[$val["user_id"]]["total_share"] += $val["total_share"];
				}
			}
		}
		$list_dep_area[$dep_area_id]["total_share_area"] = $total_share_area;
	}
	$total_sort = @array_column($lst_staff_share, 'total_share');
	@array_multisort($total_sort, SORT_DESC, $lst_staff_share);
//	$clsISO->print_pre($lst_staff_share);die;
	$total_dep_sort = @array_column($list_dep_area, 'total_share_area');
	@array_multisort($total_dep_sort, SORT_DESC, $list_dep_area);
	$smarty->assign("lst_staff_share",$lst_staff_share);
	$smarty->assign("list_dep_area",$list_dep_area);
	$smarty->assign("total_share",$total_share);
	
	// Return
	$html = $core->build('_ajax.report_top_share.tpl');
	echo json_encode([
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_worktime(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsWorktime = new Worktime();
	$clsProperty = new Property();
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo vắng mặt - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_content_worktime(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsWorktime = new Worktime();
	$clsProperty = new Property();
	#
	$sort_type = Input::post('sort_type');
	$month = Input::post('month', date('m/Y'));
	$tmp = explode('/', $month);
	$curr_month = $tmp[0];
	$curr_year = $tmp[1];
	$start_date = '01-'.$curr_month.'-'.$curr_year;
	$end_date = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year).'-'.$curr_month.'-'.$curr_year;
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	#
	$tmp = $dbconn->getAll("select `t1`.* from {$clsWorktime->tbl} as `t1` 
		inner join {$clsProperty->tbl} as `t2` on `t1`.`department_id`=`t2`.`property_id` 
		where `t1`.`is_trash`=0 and (`t1`.`worktime_date` between '{$start_time}' AND '{$end_time}') 
		order by `t2`.`order_no` ASC");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$department_id = $val['department_id'];
			$content_arrs = !empty($val['content']) 
				? json_decode(html_entity_decode($val['content']), true) : array();
			$list_staffs = array();
			if(!empty($content_arrs)){
				if(in_array($sort_type, array('asc','desc')) && 1==2){
					foreach($content_arrs as $staff_id => $staff_info){
						$staff_info['staff_id'] = $staff_id;
						$staff_info['department_id'] = $department_id;
						$staff_info['worktime_date'] = $val['worktime_date'];
						if(isset($list_worktimes[$staff_id])){
							$list_worktimes[$staff_id]['total_items'] += 1;
							$list_worktimes[$staff_id]['list_items'][] = $staff_info;
						} else {
							$list_worktimes[$staff_id]['total_items'] = 1;
							$list_worktimes[$staff_id]['list_items'][] = $staff_info;
						}
					}
				} else {
					foreach($content_arrs as $staff_id => $staff_info){
						if(isset($list_worktimes[$department_id][$staff_id])){
							$list_worktimes[$department_id][$staff_id]['total_items'] += 1;
							$list_worktimes[$department_id][$staff_id]['list_items'][] = array(
								'staff_id' => $staff_id,
								'worktime_date' => $val['worktime_date'],
								'staff_info' => $staff_info
							);
						} else {
							$list_worktimes[$department_id][$staff_id]['total_items'] = 1;
							$list_worktimes[$department_id][$staff_id]['list_items'][] = array(
								'staff_id' => $staff_id,
								'worktime_date' => $val['worktime_date'],
								'staff_info' => $staff_info
							);
						}
					}
				}
			}
		}
	}
	$smarty->assign('sort_type', $sort_type);
	$smarty->assign('list_worktimes', $list_worktimes);
	$smarty->assign('list_group_worktimes', $list_group_worktimes);
	// Return
	$html = $core->build('_ajax.worktime.tpl');
	echo $html; die();
}
function default_sale_month(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	#
	$curr_year = date('Y');
	$list_years = $list_months = array();
	for($i = 2023; $i <= $curr_year; $i++){
		$list_years[] = $i;
	}
	for($i=1; $i<= 12; $i++){
		$list_months[] = $i;
	}
	for($i=1; $i<= 30; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["curr_year"] = $curr_year;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Bảng tổng hợp bán hàng danh số - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_sale_month(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$deviceType,$header_configs;
	$clsConfiguration = Configuration::getInstance();
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsMember = new Member();
	$clsProfile = new Profile();
	##
	$curr_year = (int) Input::post('year', date('Y'));
	if($curr_year == (int) date('Y')) {
		$curr_month = (int) date('n');
	} else {
		$curr_month = 12;
	}
	$keyword = Input::post('keyword');
	$billing_type = (int) Input::post('billing_type', 0);
	$department_id = (int) Input::post('department_id', 0);
	##
	$arr_staffs = $arr_staffs_in = array();
	$field = "{$clsProfile->pkey},department_id,code,full_name,first_name,last_name,start_date";
	$cond_staff = "`is_trash`=0 AND `is_active`=1 AND `status_id`='"._STATUS_STAFF_ON_ID."' AND `{$clsProfile->pkey}`<>'"._PROFILE_PARTNER_ID."'"; 
	//  AND `start_date`<='".strtotime(sprintf('01-01-%s', $curr_year))."'
	// $cond_staff.= " AND `department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'";	
	if(!empty($keyword)) $cond_staff.= " AND `full_name_slug` like '%".$core->replaceSpace($keyword)."%'";
	if($department_id > 0) $cond_staff.= " AND (`department_id`='{$department_id}' OR `list_department_id` like '%|{$department_id}|%')";
	$list_staffs = $clsProfile->getAll($cond_staff, $field);
	##
	$cnd = "`is_trash`=0 AND `is_cancel`=0 AND `is_alliance`=0";
	if($billing_type > 0) $cnd.= " and `billing_type`='{$billing_type}'";
	$html = '<table class="table table-campaign">
	<thead><tr>
		<th class="p_header text-center text-upper" colspan="16">
			<div class="mb-2">
				<img src="'.$clsConfiguration->getValue('LogoWhite').'" width="'.$clsConfiguration->getImageWidth('LogoWhite').'" height="'.$clsConfiguration->getImageHeight('LogoWhite').'" alt="'.htmlspecialchars($header_configs['CompanyName'], ENT_QUOTES).'" />
			</div>
			<strong>Bảng tổng hợp cá nhân '.$curr_year.'</strong><br>
			(01/01/'.$curr_year.'-31/12/'.$curr_year.')
		</th>
	</tr><tr>
		<th width="3%" class="p_head text-center">STT</th>
		<th class="p_head text-left">Họ và tên</th>';
		for($month=1; $month <= 12; $month++){
			$html.= '<th width="6%" class="p_head text-center'.($month==$curr_month?' cell_highlight':'').'">T'.$month.'</th>';	
		}
	$html .= '
		<th class="p_head text-center">Tổng</th>
		<th class="p_head text-center d-none">H.suất</th>
	</tr></thead>';
	$arr_sums = array(); $total_alls = 0;
	if(!empty($list_staffs)){ $ii = 1; // Init
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsMember->pkey];
			$total_transactions = $clsBilling->countItem("{$cnd} and `staff_id`='{$staff_id}' 
				and FROM_UNIXTIME(`reg_date`,'%Y')='".$curr_year."'");
			$list_staffs[$key]['total_transactions'] = $total_transactions;				
		}
		$total_transactions_arrs = @array_column($list_staffs, 'total_transactions');
		@array_multisort($total_transactions_arrs, SORT_DESC, $list_staffs);
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsMember->pkey];
			$html.= '<tr class="p_row">
				<td class="p_cell text-center">'.$ii.'</td>
				<td class="p_cell fw-bold">'.$clsProfile->getIndentityV4($staff_id, $val,"",0,1).'</td>';
			for($month=1; $month <= 12; $month++){
				$total_transactions = 0;
				if($month <= $curr_month){
					$m = sprintf('%s/%s', $clsISO->parseNumber($month), $curr_year);
					$total_transactions = $clsBilling->countItem("{$cnd} AND `staff_id`='{$staff_id}' AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$m}'");
					$total_alls += $total_transactions;
					$arr_sums[$month][$staff_id] += $total_transactions;
					$html.= '<td class="p_cell text-center'.($month==$curr_month?' cell_highlight':'').'">'.$total_transactions.'</td>';
				} else {
					$arr_sums[$month][$staff_id] += 0;
					$html.= '<td class="p_cell text-center">-</td>';
				}
			}
			$html.= '<td class="p_cell text-center">'.$val['total_transactions'].'</td>
				<td class="p_cell text-center d-none">'.Helper::percent($val['total_transactions'],$curr_month).'%</td>
			</tr>';
			++$ii;
		}
	}
	$html.= '
		<tfoot><tr>
			<th class="p_head text-center" colspan="2">Tổng cộng</th>';
			for($month=1; $month <= 12; $month++){
				$html.= '<th width="6%" class="p_head text-center'.($month==$curr_month?' cell_highlight':'').'">'.array_sum($arr_sums[$month]).'</th>';	
			}
		$html.= '
			<th class="p_head text-center">'.$total_alls.'</th>
			<th class="p_head text-center d-none"></th>
		</tr></tfoot>
	</table>';
	// Return
	echo $html; die();
}
function default_select_block(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$project_id = (int) Input::post('project_id', 0);
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'", $field);
	##
	$html_options = '<option value="0">Phân khu/Block</option>';
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>'; 
		}
		unset($list_blocks);
	}
	// Return
	echo $html_options; die();
}
function default_select_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$block_id = Input::post('block_id');
	$is_multi = (int)Input::post('is_multi',0);
	#
	$field = "{$clsProperty->pkey},title";
	$cond = "property_type='_BUILDING' ";
	if($is_multi == 1){
		$cond .= " and for_id IN (".implode(',',$block_id).")";
		$html_options = "";
	}else{
		$cond .= "and for_id='{$block_id}'";
		$html_options = '<option value="0">Tòa nhà/Building</option>';
	}
	$clsProperty->setDeBug(1);
	$list_buildings = $clsProperty->getAll($cond, $field);
	##
	if(!empty($list_buildings)){
		foreach($list_buildings as $okey => $oval){
			$html_options.= '<option value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
		}
		unset($list_buildings);
	}
	// Return
	echo $html_options; die();
}
function default_report_moc(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsLog = new Log();
	$clsSop = new Sop();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsInterior = new Interior();
	$clsService = new Service();
	$clsLeasing = new Leasing();
	###
	$numberView = $clsLog->countItem("`from_site`='_sale' and FROM_UNIXTIME(`reg_date`,'%Y')"); 
	// $numberView += 70000;
	$smarty->assign('numberView', $numberView);
	$numberUser = $clsMember->countItem("`profile_type`='MOC' and FROM_UNIXTIME(`reg_date`,'%Y')"); 
	// $numberUser += 300;
	$smarty->assign('numberUser', $numberUser);
	$numberSop = $clsSop->countItem("FROM_UNIXTIME(`reg_date`,'%Y')"); 
	$smarty->assign('numberSop', $numberSop);
	$numberLeasing = $clsLeasing->countItem("FROM_UNIXTIME(`reg_date`,'%Y')"); 
	$smarty->assign('numberLeasing', $numberLeasing);
	$numberInterior = $clsInterior->countItem("FROM_UNIXTIME(`reg_date`,'%Y')"); 
	$smarty->assign('numberInterior', $numberInterior);
	$numberService = $clsService->countItem("FROM_UNIXTIME(`reg_date`,'%Y')"); 
	$smarty->assign('numberService', $numberService);
	$numberService = $clsService->countItem("FROM_UNIXTIME(`reg_date`,'%Y')"); 
	$smarty->assign('numberService', $numberService);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	#
	$field = "{$clsProperty->pkey},`title`,`property_code`";
	$list_block_mas = $clsProperty->getAll("`property_type`='_BUILDING' and (`for_id`='"._PROJECT_BLOCK_LSB_ID."' OR `{$clsProperty->pkey}` IN (".implode(",", _PROJECT_BLOCK_ARRAY)."))", $field);
	$smarty->assign('list_block_mas', $list_block_mas);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo tăng trưởng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_report_building_stock_min_max(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$deviceType;
	$clsProperty = new Property();
	$clsStock = new Stock();
	
	$arr_type = [
		[
			"title"	=>	"Biểu đồ thống kê trung bình giá TTS",
			"type"	=>	"total_price_early",
			"uid"	=>	$clsISO->getUniqid()
		],
		[
			"title"	=>	"Biểu đồ thống kê trung bình giá Vay",
			"type"	=>	"total_price_bank",
			"uid"	=>	$clsISO->getUniqid()
		],
		
	];
	$assign_list["arr_type"] = $arr_type;
	
	$list_bedroom = $clsProperty->getCacheItems("_BEDROOM");
	###
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$field = "{$clsProperty->pkey},`title`,`property_code`,`for_id`";
	$list_block_mas = $clsProperty->getAll("`property_type`='_BUILDING'", $field);
	$arr_cache_project = [];
	foreach($list_block_mas as $key => $val) {
		$project_id = !empty($lstBlock[$val["for_id"]]['project_id']) ? $lstBlock[$val["for_id"]]['project_id']: 0;
		$list_block_mas[$key]['link'] = sprintf('/project/p%s/b%s.html', $project_id, $val[$clsProperty->pkey]);
		if($deviceType == "phone") {
			$list_block_mas[$key]['block_name'] = $lstBlock[$val["for_id"]]['property_code'];
		}else{
			$list_block_mas[$key]['block_name'] = $lstBlock[$val["for_id"]]['title'];
		}
		
		$field = "{$clsStock->pkey},ms_code,more_information";
		$sql_string= "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			and `building_id`='{$val[$clsProperty->pkey]}' AND JSON_EXTRACT(`more_information`,\"$.total_price_early\") > 0 AND JSON_EXTRACT(`more_information`,\"$.total_price_bank\") > 0";
		$html_building = "";
		$total_stock = 0;
		$arr_berrom = [];		
		$total_early_min_m2 = $total_bank_min_m2 = $total_early_max_m2 = $total_bank_max_m2 = 0;
		foreach($list_bedroom as $k_bedroom => $val_bedroom){
			$cond = "{$sql_string} and `bedroom_id`='{$val_bedroom[$clsProperty->pkey]}'";
			$DT_TT_MIN = $DT_TT_MAX = "";
			$number_stock = $clsStock->countItem("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `building_id`='{$val[$clsProperty->pkey]}' 
			and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."')  and `bedroom_id`='{$val_bedroom[$clsProperty->pkey]}'");
			$total_stock += $number_stock;
			$lstStock = $clsStock->getAll($cond, $field);
			$ms_code = '';
			if(!empty($lstStock)){
				$total_price_early_min= $total_price_early_max = $total_price_progress_min = $total_price_progress_max = $total_price_bank_min = $total_price_bank_max = $DT_TT_MIN = $DT_TT_MAX = 0;
				$ii =0;
				foreach($lstStock as $k_stock => $v_stock) { 
					$more_information = $v_stock['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$total_price_progress = $clsISO->processSmartNumber($more_information['total_price_progress']);
					$total_price_early = $clsISO->processSmartNumber($more_information['total_price_early']);
					$total_price_early = $clsISO->processSmartNumber($more_information['total_price_early']);
					$total_price_bank = $clsISO->processSmartNumber($more_information['total_price_bank']);
					$DT_TT = $clsISO->convertToNumber($more_information['DT_TT']);
					if($ii == 0){
						$total_price_early_min = $total_price_early_max = $total_price_early;
						$total_price_progress_min = $total_price_progress_max = $total_price_progress;
						$total_price_bank_min = $total_price_bank_max = $total_price_bank;
						$DT_TT_MIN = $DT_TT_MAX = $DT_TT;
					} else {
						if($total_price_early < $total_price_early_min){
							$total_price_early_min = $total_price_early;
						}
						if($total_price_progress < $total_price_progress_min){
							$total_price_progress_min = $total_price_progress;
						}
						if($total_price_bank < $total_price_bank_min){
							$total_price_bank_min = $total_price_bank;
						}
						if($DT_TT < $DT_TT_MIN){
							$DT_TT_MIN = $DT_TT;
						}
						if($total_price_early > $total_price_early_max){
							$total_price_early_max = $total_price_early;
						}
						if($total_price_progress > $total_price_progress_max){
							$total_price_progress_max = $total_price_progress;
						}
						if($total_price_bank > $total_price_bank_max){
							$total_price_bank_max = $total_price_bank;
						}
						if($DT_TT > $DT_TT_MAX){
							$DT_TT_MAX = $DT_TT;
						}
					}
					++$ii;
					unset($more_information);
				}
				$total_price_early_min_m2 = $total_price_early_min / $clsISO->convertToNumber($DT_TT_MIN); 
				$total_early_min_m2 += $total_price_early_min_m2;
				$total_price_early_min_m2 = round($total_price_early_min_m2,1);
				
				$total_price_early_max_m2 = $total_price_early_max / $clsISO->convertToNumber($DT_TT_MAX); 
				$total_early_max_m2 += $total_price_early_max_m2;
				$total_price_early_max_m2 = round($total_price_early_max_m2,1);
				
				$total_price_bank_min_m2 = $total_price_bank_min / $clsISO->convertToNumber($DT_TT_MIN); 
				$total_bank_min_m2 += $total_price_bank_min_m2;
				$total_price_bank_min_m2 = round($total_price_bank_min_m2,1);
				
				$total_price_bank_max_m2 = $total_price_bank_max / $clsISO->convertToNumber($DT_TT_MAX); 
				$total_bank_max_m2 += $total_price_bank_max_m2;
				$total_price_bank_max_m2 = round($total_price_bank_max_m2,1);
				
				$total_price_progress_min = round($total_price_progress_min,1);
				$total_price_progress_max = round($total_price_progress_max,1);
				
				$arr_berrom[] = [
					"property_id"				=>	$val_bedroom['property_id'],
					"title"						=>	$val_bedroom['title'],
					"DT_TT_MIN"					=>	$DT_TT_MIN,
					"DT_TT_MAX"					=>	$DT_TT_MAX,
					"total_price_early_min"		=>	$total_price_early_min,
					"total_price_early_max"		=>	$total_price_early_max,
					"total_price_progress_min"	=>	$total_price_progress_min,
					"total_price_progress_max"	=>	$total_price_progress_max,
					"total_price_bank_min"		=>	$total_price_bank_min,
					"total_price_bank_max"		=>	$total_price_bank_max,
					"total_price_early_min_m2"	=>	$total_price_early_min_m2,
					"total_price_early_max_m2"	=>	$total_price_early_max_m2,
					"total_price_bank_min_m2"	=>	$total_price_bank_min_m2,
					"total_price_bank_max_m2"	=>	$total_price_bank_max_m2,
					"total_price_progress_min"	=>	$total_price_progress_min,
					"total_price_progress_max"	=>	$total_price_progress_max,
					"number_stock"				=>	$number_stock,
				];
			}			
		}
		$list_block_mas[$key]['list_bedroom'] = $arr_berrom;
		$list_block_mas[$key]["total_stock"] = $total_stock;
		if(empty($arr_berrom)){
			unset($list_block_mas[$key]);
		}else{
			$list_block_mas[$key]["average_early_min_m2"] = round($total_early_min_m2/count($arr_berrom),1);
			$list_block_mas[$key]["average_early_max_m2"] = round($total_early_max_m2/count($arr_berrom),1);
			$list_block_mas[$key]["average_bank_min_m2"] = round($total_bank_min_m2/count($arr_berrom),1);
			$list_block_mas[$key]["average_bank_max_m2"] = round($total_bank_max_m2/count($arr_berrom),1);
		}
		
		unset($project_id);
	}
	$total_arrs = @array_column($list_block_mas, 'total_stock');
	@array_multisort($total_arrs, SORT_DESC, $list_block_mas);
	$smarty->assign('list_block_mas', $list_block_mas);
	/*=============Title & Description Page==================*/
	$title_page = 'Tổng quan các phân khu - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_building_chart(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsProject = new Project();
	$arr_project = [_PROJECT_DEF_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID];
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_VHOP1 = $arr_VHOP2 = $arr_VHGG = []; 
	$arr_block = [];
	foreach($lstBlock  as $key => $value) {
		if($value['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE && in_array($value['project_id'],$arr_project)) {
			$arr_block[$value['project_id']][] = $value;	
		}		
	}
	###
	$type = Input::post("type","total_price_early");
	$uid = $clsISO->getUniqid();
	$html = '<div id="'.$uid.'" class="chartContainer w-100" style="height:300px"></div>';
	
	$barChartData = array();
	$data = array();
	$barChartData['animationEnabled'] = true;
	$data = [];
	$sql_string= "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND JSON_EXTRACT(`more_information`,'$.total_price_early') > 0 AND JSON_EXTRACT(`more_information`,'$.total_price_bank') > 0";
	$dataPoints_early = $dataPoints_bank = [];
	foreach ($arr_block as $key => $val) {
		foreach ($val as $k_block => $v_block) {
//			$clsStock->setDeBug(1);
			$lstStock = $clsStock->getAll($sql_string." AND `block_id`='{$v_block[$clsProperty->pkey]}'", "more_information");
			$ms_code = '';
			if(!empty($lstStock)){
				$ii =0;
				$total_price_early_m2 = $total_price_bank_m2 = 0;
				foreach($lstStock as $k_stock => $v_stock) { 
					$more_information = $v_stock['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$total_price_early = $clsISO->processSmartNumber($more_information["total_price_early"]);
					$total_price_bank = $clsISO->processSmartNumber($more_information["total_price_bank"]);
					$DT_TT = $clsISO->convertToNumber($more_information['DT_TT']);
					$total_price_early_m2 += $total_price_early / $DT_TT;
					$total_price_bank_m2 += $total_price_bank / $DT_TT;
					++$ii;
					unset($more_information);
				}
				if($ii > 0){
					$avg_early_m2 = round($total_price_early_m2/$ii);
					$dataPoints_early[] = [
						"label"	=>	$v_block['title'],
						"type"	=>	"TTS",
						"y"		=>	$avg_early_m2,
						"color"	=>	"#4f81bc",
						"indexLabel" =>	round($avg_early_m2/1000000,1).""
					];
					$avg_bank_m2 = round($total_price_bank_m2/$ii);
					$dataPoints_bank[] = [
						"label"	=>	$v_block['title'],
						"type"	=>	"Vay",
						"y"		=>	$avg_bank_m2,
						"color"	=>	"#c0504e",
						"indexLabel" =>	round($avg_bank_m2/1000000,1).""
					];
				}
				
			}
		}
	}
	
	$data = [
		[
			"type"	=> "column",
			"name"	=> "TTS",
			"showInLegend" => true,
			"dataPoints" => $dataPoints_early,		
		],
		[
			"type"	=> "column",
			"name"	=> "Vay",
			"showInLegend" => true,
			"dataPoints" => $dataPoints_bank,
		]
	];
	$barChartData['axisY'] = [
		"title"	=>	"m2",
		"includeZero"	=>	true
	];
	$barChartData['data'] = $data;
//	$clsISO->print_pre($data);die;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback'	=>	'  var chart = new CanvasJS.Chart("'.$uid.'", {
			title: {
				text: ""
			},
			axisX: {
				title: "",
				interval: 1,
				labelFontSize: 11,
				includeZero: true
			},
			axisY: {
				minimum: 0,
				labelFormatter: function(e) {
					var value = e.value / 1000000;
					return value.toFixed(0) + " triệu/m2";
				}
			},
			toolTip:{
				contentFormatter: function ( e ) {
					return e.entries[0].dataPoint.label+" "+e.entries[0].dataPoint.type+": "+parseFloat((e.entries[0].dataPoint.y/1000000).toFixed(1)) + "triệu/m2";  
				}  
			},
			data: '.json_encode($data).'
		});
    chart.render();'	
	));
}
function default_load_report_Min_Max(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$html = "";
	$block_id = _PROJECT_BLOCK_LSB_ID;
	$building_id = Input::post('building_id');
	$holderG = Input::post("holderG");
	$list_bedroom = $clsProperty->getCacheItems("_BEDROOM");
	$field = "{$clsStock->pkey},ms_code,more_information";
	$sql_string= "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
		and `building_id`='{$building_id}' AND JSON_EXTRACT(`more_information`,\"$.total_price_early\") > 0 AND JSON_EXTRACT(`more_information`,\"$.total_price_bank\") > 0";
	$html_building = "";
	$total_stock = 0;
	foreach($list_bedroom as $key => $val){
		$bedroom_id = $val[$clsProperty->pkey];
		$cond = "{$sql_string} and `bedroom_id`='{$bedroom_id}'";
		$DT_TT_MIN = $DT_TT_MAX = "";
		$number_stock = $clsStock->countItem("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `building_id`='{$building_id}' 
		and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."')  and `bedroom_id`='{$bedroom_id}'");
		$total_stock += $number_stock;
		$oneStock = $clsStock->getByCond($cond, $field);
		$ms_code = '';
		if(!empty($oneStock)){
			$more_information = $oneStock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$total_price_progress = $clsISO->processSmartNumber($more_information['total_price_progress']);
			$total_price_early = $clsISO->processSmartNumber($more_information['total_price_early']);
			$total_price_bank = $clsISO->processSmartNumber($more_information['total_price_bank']);
		  	$DT_TT = $more_information['DT_TT'];
			
			$total_price_early_m2 = $total_price_early / $clsISO->convertToNumber($DT_TT);
			$total_price_early_m2 = round($total_price_early_m2,1);
			$total_price_bank_m2 = $total_price_bank / $clsISO->convertToNumber($DT_TT);
			$total_price_bank_m2 = round($total_price_bank_m2,1);
			$html_building.= '<tr>
				<td>'.$val['title'].'</td>
				<td>'.(!empty($DT_TT) ? $DT_TT.'m<sup>2</sup>':'-').'</td>
				<td>'.$clsISO->shortNumber($total_price_early_m2).'</td>
				<td>'.$clsISO->shortNumber($total_price_bank_m2).'</td>
				<td>'.$clsISO->shortNumber($total_price_early).'</td>
				<td>'.$clsISO->shortNumber($total_price_progress).'</td>
				<td>'.$clsISO->shortNumber($total_price_bank).'</td>
				<td class="text-center">'.(($number_stock > 0)?"<span class='text-success'>Còn hàng</span>":"<span class='text-danger'>Hết hàng</span>").'</td>
			</tr>';
			unset($number_stock);
		}
	}
	
	if($html_building != "") {		
		$html = '<table class="table mb-0">
			<thead><tr>
				<th class="bg-lighter">Căn</th>
				<th class="bg-lighter">Diện tích</th>
				<th class="bg-lighter">TTS/m<sup>2</sup></th>
				<th class="bg-lighter">Vay/m<sup>2</sup></th>
				<th class="bg-lighter">TTS</th>
				<th class="bg-lighter">TTTĐ</th>
				<th class="bg-lighter">Vay</th>
				<th class="bg-lighter">Tình trạng</th>
			</tr></thead>';		
		$html.= '<tbody>'.$html_building.'</tbody>';
		$html.= '</table>';
	}
	// Return
	echo json_encode(array(
		"html" => $html,
		"total_stock"	=>	$total_stock
	)); die();
}
function default_load_report_MWF(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$html = "";
	$block_id = _PROJECT_BLOCK_LSB_ID;
	$building_id = Input::post('building_id');
	$holderG = Input::post("holderG");
	$list_bedroom = $clsProperty->getCacheItems("_BEDROOM");
	// $clsISO->print_pre($list_bedroom); die();
	$html = '<table class="table mb-0">
		<thead><tr>
			<th>Căn</th>
			<th>Thông thủy</th>
			<th>Giá Min</th>
			<th>Giá/m2</th>
			<th>Thông thủy</th>
			<th>Giá Max</th>
			<th>Giá/m2</th>
		</tr></thead>';
	$bedroom_arrs_in = array(21,23,25,26,27);
	$field = "{$clsStock->pkey},ms_code,more_information";
	$sql_string= "`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
		and `building_id`='{$building_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
		and `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
	foreach($list_bedroom as $key => $val){
		$bedroom_id = $val[$clsProperty->pkey];
		if(in_array($bedroom_id, $bedroom_arrs_in)){
			$cond = "{$sql_string} and `bedroom_id`='{$bedroom_id}'";
			$DT_TT_MIN = $DT_TT_MAX = "";
			$total_price_early_min = $total_price_early_max = 0;
			$list_stocks = $clsStock->getAll($cond, $field);
			$ms_code = '';
			if(!empty($list_stocks)){ $ii = 0; // Init
				foreach($list_stocks as $okey => $oval){
					$more_information = $oval['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$total_price_early = $more_information['total_price_early'];
					if(empty($total_price_early)){
						$total_price_early = $more_information['total_price_vat'];
					}
					if(!empty($total_price_early)){
						$total_price_early = $clsISO->processSmartNumber($total_price_early);
						if($ii == 0){
							$total_price_early_min = $total_price_early_max = $total_price_early;
							$DT_TT_MIN = $DT_TT_MAX = $more_information['DT_TT'];
						} else {
							if($total_price_early < $total_price_early_min){
								$total_price_early_min = $total_price_early;
								$DT_TT_MIN = $more_information['DT_TT'];
							}
							if($total_price_early > $total_price_early_max){
								$total_price_early_max = $total_price_early;
								$DT_TT_MAX = $more_information['DT_TT'];
							}
						}
						++$ii;
					}
				}
				$total_price_early_area_min = $total_price_early_min / $clsISO->convertToNumber($DT_TT_MIN);
				$total_price_early_area_min = round($total_price_early_area_min,1);
				$total_price_early_area_max = $total_price_early_max / $clsISO->convertToNumber($DT_TT_MAX);
				$total_price_early_area_max = round($total_price_early_area_max,1);
				$html.= '<tr>
					<td>'.$val['title'].'</td>
					<td>'.(!empty($DT_TT_MIN) ? $DT_TT_MIN.'m<sup>2</sup>':'-').'</td>
					<td>'.$clsISO->shortNumber($total_price_early_min).'</td>
					<td>'.$clsISO->shortNumber($total_price_early_area_min).'</td>
					<td>'.(!empty($DT_TT_MAX) ? $DT_TT_MAX.'m<sup>2</sup>':'-').'</td>
					<td>'.$clsISO->shortNumber($total_price_early_max).'</td>
					<td>'.$clsISO->shortNumber($total_price_early_area_max).'</td>
				</tr>';
			}
		}
	}
	$html.= '</table>';
	// Return
	echo json_encode(array(
		"html" => $html
	)); die();
}
function default_load_stock_MOC_chart(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	###
	$type = Input::post('type', 'view_moc');
	$time_type = Input::post('time_type', 'THIS_MONTH');
	$barChartData = $dataPoints = array();
	$barChartData['animationEnabled'] = true;
	###
	if($type == 'view_moc'){
		$clsTable = new Log();
		$cond = "`from_site`='_sale'";
	}else if($type == "sales_moc"){
		$clsTable = new Member();
		$cond = "`profile_type`='MOC'";
	}
	if(in_array($time_type, ['THIS_MONTH'])){
		$start_date = strtotime("-30 day"); 
		$due_date = strtotime(date("d-m-Y 23:59:59"));
		for($ii=$start_date; $ii<= $due_date; $ii = strtotime("+1 day", $ii)){
			$total = $clsTable->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y',$ii)."'");
			$dataPoints[] = array(
				'label'	=> sprintf('Ngày %s', date('d/m', $ii)),
				'y'	=> $total*1
			);
		}
	} else if(in_array($time_type, ['THIS_YEAR'])){
		for($ii=1; $ii <= 12; $ii++){
			$y = ($time_type=='THIS_YEAR') ? date('Y') : date('Y') - 1;
			$my = sprintf('%s/%s', $clsISO->parseNumber($ii), $y);
			$total = $clsTable->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$my."'");
			$dataPoints[] = array(
				'label'	=> sprintf('Tháng %s', $ii),
				'y'	=> $total*1
			);
		}
	}
	$data = array();
	$data['type'] = 'column';
	$data['showInLegend'] = 'false';
	$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode($barChartData); die();
}
function default_load_report_MOC(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	$clsAPI = new API();
	$clsLog = new Log();
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsProperty = new Property();
	$clsMOCLog = new MOCLog();
	###
	$data = array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	###
	$html = "";
	$uid = $clsISO->getUniqid();
	$array_data = $more = array();
	$type = Input::post('type', ''); 
	$time_type = Input::post('time_type', 'THIS_MONTH');
	$month = Input::post("month");
	$year = Input::post("year", date('Y'));
	$by_day = Input::post('by_day');
	$assign_list['uid'] = $uid;
	$assign_list['type'] = $type;
	###
	if($type == "user_new"){			
		$keyword = Input::post("keyword","");
		$cond = "";
		if($keyword != ""){
			$cond = " AND (full_name_slug LIKE '%{$keyword}%' OR full_name LIKE '%{$keyword}%')";
		}
		$array_role = [];
		$lst_user = $clsMember->getAll("`is_trash`=0 and `is_active`='1'  and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and profile_type = 'MOC' AND phone <> '' {$cond} ORDER BY reg_date DESC limit 0,10");
		foreach($lst_user as $key => $value){
			$number_view_stock = $clsLog->countItem("`user_id`='{$value['profile_id']}' and type='view_stock'");
			$lst_user[$key]['number_view_stock'] = $number_view_stock;
			if(!isset($array_role[$value['profile_id']])){
				$array_role[$value['profile_id']] = $clsProperty->getTitle($value['role_id']);
			}
			$lst_user[$key]['role_name'] = $array_role[$value['profile_id']];
			unset($number_view_stock);
		}
		$assign_list['lst_user'] = $lst_user;
	}elseif($type == "user_view_stock_sale" || $type == "user_view_stock_fh"){ 
		if ($type == "user_view_stock_sale"){
			$profile_type = "MOC";
		}else{
			$profile_type = "user.fh";
		}
		$keyword = Input::post("keyword","");
		$cond = "tbl1.`is_trash`=0 and tbl1.`is_active`='1' and tbl1.`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and tbl1.profile_type = '{$profile_type}' AND tbl1.phone <> '' ";
		$cond .= " AND `tbl1`.`profile_id` NOT IN (7,9,13,38,289)";
		if($keyword != ""){$cond .= " AND (tbl1.full_name_slug LIKE '%{$keyword}%' OR tbl1.full_name LIKE '%{$keyword}%')";}
		$field = "tbl1.full_name,tbl1.reg_date,tbl1.profile_id,tbl1.phone,tbl1.role_id,COUNT(tbl2.user_id) AS number_view_stock
		,MAX(tbl2.reg_date) as time_connect,tbl3.title as role_name";
		$sql = "SELECT {$field} FROM {$clsMember->tbl} as tbl1 JOIN {$clsLog->tbl} AS tbl2 ON tbl2.user_id = tbl1.profile_id LEFT JOIN {$clsProperty->tbl} AS tbl3 ON tbl1.role_id=tbl3.property_id WHERE {$cond} GROUP BY tbl2.user_id ORDER BY number_view_stock DESC limit 0,10";
		$lst_user = $dbconn->getAll($sql);
		$assign_list['lst_user'] = $lst_user;
	}elseif($type == "date_access_log"){
		$date = Input::post("date", date('d/m/Y'));
		$time = strtotime(str_replace("/","-",$date));
		$access_logs = $clsMOCLog->getAll("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".$date."' order by reg_date desc");
		$start_date = strtotime(date("d-m-Y", $time) . " 00:00:00");
		$end_date = strtotime(date("d-m-Y", $time) . " 23:59:59");
		if(!empty($access_logs)){
			$array_data = array();
			$arr_time = [];
			$start_time = strtotime(date("d-m-Y H:i"));
			for($i=0; $i<40; $i++){
				$arr_time[] = $start_time - 600*$i;
			}
			$arr_time = array_reverse($arr_time);
			$data = [];
			$t=1;
			foreach($access_logs as $key => $value){
				if($value['reg_date'] >= $start_date && $value['reg_date'] <= $end_date){
					$array_data[] = $value;
				}
			}
			if(!empty($array_data)){
				$arr_profile_cached = array();
				foreach($array_data as $key => $val){
					$profile_id = isset($val['profile_id']) ? (int) $val['profile_id'] : 0;
					if($profile_id > 0){
						$is_popup = 1;
						if(!isset($arr_profile_cached[$profile_id])){
							$arr_profile_cached[$profile_id] = $clsMember->getFullName($profile_id);
						}
					} else {
						$is_popup = 0;
						$arr_profile_cached[$profile_id] = 'Khách';
					}
					$array_data[$key]['is_popup'] = $is_popup;
					$array_data[$key]['full_name'] = $arr_profile_cached[$profile_id];
				#
					for($i=0; $i<count($arr_time); $i++){	
						if($i == 0 &&  $val['reg_date'] <= $arr_time[$i] && $val['reg_date'] > $arr_time[$i]-900){
							++$t;
							$data[$arr_time[$i]] = $t;
						}else if($val['reg_date'] <= $arr_time[$i] && $val['reg_date'] > $arr_time[$i-1]){
							$data[$arr_time[$i]] = (isset($data[$arr_time[$i]]))?($data[$arr_time[$i]]+1):1;
						}
					}
				}
			}
			#				
			foreach($data as $k => $v){
				$dataPoints[] = array(
					'label'	=> date("H:i",$k),
					'y'	=> $v
				);
			}
			$data['type'] = 'line';
			$data['showInLegend'] = 'false';
			$data['legendText'] = '{label}';
			$data['indexLabel'] = '{y}';
			$data['indexLabelPlacement'] = 'inside';
			$data['indexLabelFontColor'] = '#36454F';
			$data['dataPoints'] = $dataPoints;
			$barChartData['data'] = $data;
		}
		$assign_list['array_data'] = $array_data;
	}elseif($type == "date_access_log_url"){
		$date = Input::post("date", date('d/m/Y'));
		$time = strtotime(str_replace("/","-",$date));
		$start_date = strtotime(date("d-m-Y", $time) . " 00:00:00");
		$end_date = strtotime(date("d-m-Y", $time) . " 23:59:59");
		$access_logs = $clsMOCLog->getAll("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".$date."'");
		if(!empty($access_logs)){
			$array_data = array();
			$arr_time = [];
			if(strtotime(date("d-m-Y")) == $time){
				$start_time = strtotime(date("d-m-Y H:i"));
				for($i=0; $i<40; $i++){
					$arr_time[] = $start_time - 600*$i;
				}
			$arr_time = array_reverse($arr_time);
			}else{
				for($i=0; $i<=24; $i++){
					$arr_time[] = $time + 3600*$i;
				}
			}
			$data = [];
			$t=1;
			foreach($access_logs as $key => $value){
				if($value['reg_date'] >= $start_date && $value['reg_date'] <= $end_date){
					$array_data[] = $value;
				}
			}
			if(!empty($array_data)){
				$arr_profile_cached = array();
				foreach($array_data as $key => $val){
					for($i=0; $i<count($arr_time); $i++){	
						if($i == 0 &&  $val['reg_date'] <= $arr_time[$i] && $val['reg_date'] > $arr_time[$i]-900){
							++$t;
							$data[$arr_time[$i]] = $t;
						}else if($val['reg_date'] <= $arr_time[$i] && $val['reg_date'] > $arr_time[$i-1]){
							$data[$arr_time[$i]] = (isset($data[$arr_time[$i]]))?($data[$arr_time[$i]]+1):1;
						}
					}
				}
			}
			#				
			foreach($data as $k => $v){
				$dataPoints[] = array(
					'label'	=> date("H:i",$k),
					'y'	=> $v
				);
			}
			$data['type'] = 'line';
			$data['showInLegend'] = 'false';
			$data['legendText'] = '{label}';
			$data['indexLabel'] = '{y}';
			$data['indexLabelPlacement'] = 'inside';
			$data['indexLabelFontColor'] = '#36454F';
			$data['dataPoints'] = $dataPoints;
			$barChartData['data'] = $data;
		}
		$count_table = count($array_data)/2;
		if($count_table >= 10){
			$assign_list['double_table'] = 1;
		}
		$assign_list['count_table'] = $count_table;
		$total_arrs = @array_column($array_data, 'reg_date');
		@array_multisort($total_arrs, SORT_DESC, $array_data);
		$assign_list['array_data'] = $array_data;
	}elseif($type == "date_access_transaction"){
		$date = Input::post("date", date('d/m/Y'));
		$time = strtotime(str_replace("/","-",$date));	
		$start_date = strtotime(date("d-m-Y", $time) . " 00:00:00");
		$end_date = strtotime(date("d-m-Y", $time) . " 23:59:59");
		$access_logs = $clsMOCLog->getAll("(url LIKE '%/cn/%.html' or url LIKE '%/ct/%.html') AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".$date."'");
		if(!empty($access_logs)){
			$array_data = array();
			$arr_time = [];
			if(strtotime(date("d-m-Y")) == $time){
				$range_time = 600;
				$start_time = strtotime(date("d-m-Y H:i"));
				for($i=0; $i<40; $i++){
					$arr_time[] = $start_time - $range_time*$i;
				}
			$arr_time = array_reverse($arr_time);
			}else{
				$range_time = 3600;
				for($i=0; $i<=24; $i++){
					$arr_time[] = $time + 3600*$i;
				}
			}
			$data = [];
			$t=1;
			foreach($access_logs as $key => $value){
				if($value['reg_date'] >= $start_date && $value['reg_date'] <= $end_date){
					$array_data[] = $value;
				}
			}
//				die;
			if(!empty($array_data)){
				$arr_profile_cached = array();
				foreach($array_data as $key => $val){
					for($i=0; $i<count($arr_time); $i++){	
						if($i == 0 &&  $val['reg_date'] <= $arr_time[$i] && $val['reg_date'] > $arr_time[$i]-$range_time){
							++$t;
							$data[$arr_time[$i]] = $t;
						}else if($val['reg_date'] <= $arr_time[$i] && $val['reg_date'] > $arr_time[$i-1]){
							$data[$arr_time[$i]] = (isset($data[$arr_time[$i]]))?($data[$arr_time[$i]]+1):1;
						}
					}
				}
			}
			#				
			foreach($data as $k => $v){
				$dataPoints[] = array(
					'label'	=> date("H:i",$k),
					'y'	=> $v
				);
			}
			$data['type'] = 'line';
			$data['showInLegend'] = 'false';
			$data['legendText'] = '{label}';
			$data['indexLabel'] = '{y}';
			$data['indexLabelPlacement'] = 'inside';
			$data['indexLabelFontColor'] = '#36454F';
			$data['dataPoints'] = $dataPoints;
			$barChartData['data'] = $data;
		}
		$count_table = count($array_data)/2;
		if($count_table >= 10){
			$assign_list['double_table'] = 1;
		}
		$assign_list['count_table'] = $count_table;
		$total_arrs = @array_column($array_data, 'reg_date');
		@array_multisort($total_arrs, SORT_DESC, $array_data);
		$assign_list['array_data'] = $array_data;
	}else if($type == "list_stock_DQ"){
		$clsStock = new Stock();
		$clsProject = new Project();
		$field = "{$clsStock->pkey},ms_code,project_id,building_id,block_id";
		$lstStockDQ = $clsStock->getAll("`agency_id`='"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_DQ_ID."'",$field);
		if(!empty($lstStockDQ)){
			$arr_project_cached = array();
			$arr_property_cached = array();
			foreach($lstStockDQ as $key => $val){
				$stock_id = $val['stock_id'];
				$project_id = $val['project_id'];
				$block_id = $val['block_id'];
				$building_id = $val['building_id'];
				if($project_id > 0 && !isset($arr_project_cached[$project_id])){
					$arr_project_cached[$project_id] = $clsProject->getTitle($project_id);
				}
				if($block_id > 0 && !isset($arr_property_cached[$block_id])){
					$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
				}
				if($building_id > 0 && !isset($arr_property_cached[$building_id])){
					$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
				}
				$lstStockDQ[$key]['project_name'] = $arr_project_cached[$project_id];
				$lstStockDQ[$key]['block_name'] = $arr_property_cached[$block_id];
				$lstStockDQ[$key]['building_name'] = $arr_property_cached[$building_id];
				$totalSearch_FH = $clsLog->getTotalSearch($stock_id,"_user");
				$totalSearch_MYOCEAN = $clsLog->getTotalSearch($stock_id,"_sale");
				###
				$lstStockDQ[$key]['totalSearchFH'] = $totalSearch_FH;
				$lstStockDQ[$key]['totalSearchMYOCEAN'] = $totalSearch_MYOCEAN;
				$lstStockDQ[$key]['logs_FH'] = '<a href="'.PCMS_URL.'/logs-sale.html?stock_id='.$stock_id.'&from_site=_user" target="_blank">
					<i class="material-icons-outlined">history</i> Lịch sử tra cứu <strong class="text-black">('.$totalSearch_FH.')</strong></a></li>';
				$lstStockDQ[$key]['logs_MYOCEAN'] = '<a href="'.PCMS_URL.'/logs-sale.html?stock_id='.$stock_id.'&from_site=_sale" target="_blank">
					<i class="material-icons-outlined">history</i> Lịch sử tra cứu <strong class="text-black">('.$totalSearch_MYOCEAN.')</strong></a></li>';
			}
			$total_arrs = @array_column($lstStockDQ, 'totalSearchMYOCEAN');
			@array_multisort($total_arrs, SORT_DESC, $lstStockDQ);
		}
		$assign_list['lstStockDQ'] = $lstStockDQ;
	}elseif($type == "agent"){
		$clsStock = new Stock();
		/* Agency */
		$field = "{$clsProperty->pkey},title,bgcolor,textcolor";
		$list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' and `for_id`='"._PROJECT_BLOCK_LSB_ID."'", $field);
		/** Bedroom */
		$list_bedroom = $clsProperty->getAllCache("property_id IN (21,22,23,25,26,27)", $field);
		if(!empty($list_bedroom)){
			foreach ($list_bedroom as $key => $value){
				if($value['property_id'] == 21){
					$list_bedroom[$key]['title'] = "Stu";
				}
			} 
		}
		$assign_list['list_bedroom'] = $list_bedroom;
		$assign_list['list_buildings'] = $list_buildings;
		###
		$arr_all_total_stocks = array();
		if(!empty($list_buildings)){
			foreach($list_buildings as $key => $val){
				$building_id = $val[$clsProperty->pkey];
				$arr_all_total_stocks[$building_id] = array();
				foreach($list_bedroom as $okey => $oval){
					$bedroom_id = $oval[$clsProperty->pkey];
					$arr_all_total_stocks[$building_id][$bedroom_id] = 0;
				}
			}
		}
		$total_stocks = 0;
		$field = "{$clsProperty->pkey},title";
		$list_agent = $clsProperty->getAll("`is_trash`=0 and `property_type`='_AGENCY' 
			and `is_locked`='0' order by `order_no` ASC", $field);
		foreach($list_agent as $key => $val){
			$title = $val['title'];
			$agency_id = $val[$clsProperty->pkey];
			if($agency_id == _AGENCY_FH_ID){
				$cond_DQ = " AND `status_id`='"._STOCK_STATUS_DQ_ID."'";
			}else{
				$cond_DQ = " AND `status_id`='"._STOCK_STATUS_LOCK_ID."'";
			}
			$total_DQ = 0; $arr_total_stocks = array();
			if(!empty($list_buildings)){
				foreach($list_buildings as $okey => $oval){
					$buildingID = $oval[$clsProperty->pkey];
					$arr_total_stocks[$buildingID] = array();
					foreach($list_bedroom as $nkey => $nval){
						$bedroomID = $nval[$clsProperty->pkey];
						// $dbconn->debug=true;
						$total_in_stocks = $clsStock->countItem("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
						AND `agency_id`='{$agency_id}' AND `building_id`='{$buildingID}' 
						AND `bedroom_id`='{$bedroomID}'".$cond_DQ);
						$total_stocks += $total_in_stocks;
						$total_DQ += $total_in_stocks;
						$arr_total_stocks[$buildingID][$bedroomID] += $total_in_stocks;
						$arr_all_total_stocks[$buildingID][$bedroomID] += $total_in_stocks;
					}
				}
			}
//			 $clsISO->print_pre($arr_total_stocks); die();
			if($total_DQ == 0){
				unset($list_agent[$key]);
			} else {
				$list_agent[$key]['total_DQ'] = $total_DQ;
				$list_agent[$key]['arr_total_stocks'] = $arr_total_stocks;
			}
		}
		$total_arrs = @array_column($list_agent, 'total_DQ');
		@array_multisort($total_arrs, SORT_DESC, $list_agent);
		$assign_list['total_stocks'] = $total_stocks;
		$assign_list['arr_all_total_stocks'] = $arr_all_total_stocks;
		$assign_list['list_agent'] = $list_agent;
	}elseif($type == "agent_log"){
		$clsStock = new Stock();
		$clsAdminLog = new AdminLog();
		$date = Input::post("date", date('d/m/Y'));
		$arr_time = [
			date("d/m/Y"),
			date("d/m/Y",strtotime("-1 days")),
			date("d/m/Y",strtotime("-2 days")), 
			date("d/m/Y",strtotime("-3 days")),
			date("d/m/Y",strtotime("-4 days"))
		];
		$start_time = date("d-m-Y 00:00:00",strtotime("-4 days"));
		$start_time = strtotime($start_time);
		$end_time = date("d-m-Y 23:59:59");
		$end_time = strtotime($end_time);
		$assign_list['arr_time'] = $arr_time;
		$array_data = [];
		$lstAdminLog = $clsAdminLog->getAll("action='update_stock' AND (date BETWEEN {$start_time} AND {$end_time})","date,target_id,FROM_UNIXTIME(`date`,'%d/%m/%Y') as day");
		// $array_data[$value['target_id']][""]
		$array_cache = [];
		foreach($lstAdminLog as $key => $value){
			if(!isset($array_cache[$value['target_id']])){
				$array_cache[$value['target_id']] = $clsProperty->getTitle($value['target_id']);
			}
			$array_data[$value['target_id']]['title'] = $array_cache[$value['target_id']];
			for($i=0; $i<count($arr_time);$i++){
				if($value['day'] == $arr_time[$i]){
					$array_data[$value['target_id']][$arr_time[$i]][] = date("H:i",$value['date']);
				}
			}
		}
		$assign_list['array_data'] = $array_data;
	} else if($type=='user_access_MOC'){
		$due_date = time();
		$start_date = strtotime('-15 days', $due_date);
		$dataPointLogin = $dataPointsSearch = $dataPointAccess= array();
		$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=$start_date; $i<= $due_date; $i = strtotime('+1 day', $i)){
			$date = date('d/m/Y', $i);
			$clsMemeber = new Member();
			$total_regis = $clsMemeber->countItem("`profile_type`='MOC' and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='{$date}'");
			$tmp = $dbconn->getRow("select count(1) as `total_user` from (
				select count(1) as `total_search` from ".$clsLog->tbl." as `t1` 
				where `t1`.`user_id`>0 and FROM_UNIXTIME(`t1`.`reg_date`,'%d/%m/%Y')='{$date}' 
				group by `t1`.`user_id` having `total_search`>5) as `tbl`");
			$total_user = !empty($tmp) ? $tmp['total_user'] : 0;
			$tmp = $dbconn->getRow("select count(distinct `user_ip`) as `total_access` from {$clsMOCLog->tbl}
				where FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='{$date}'");
			$total_access = !empty($tmp) ? $tmp['total_access'] : 0;
			###
			$dataPointLogin[] = array(
				'label' => $date,
				'y' => $total_regis*1
			);
			$dataPointsSearch[] = array(
				'label' => $date,
				'y' => $total_user*1
			);
			$dataPointAccess[] = array(
				'label' => $date,
				'y' => $total_access*1
			);
		}
		$barChartData['data'] = array(
			array(
				"type"    => "spline",
				"indexLabel" => "{y}",
				"color"	 => "#1d6a01",
				"name"    => "Người dùng mới",
				"showInLegend"    => true,
				"dataPoints"    => $dataPointLogin
			),
			array(
				"type"  => "spline",
				"indexLabel" => "{y}",
				"name"	=> "Truy cập",
				"color"	 => "#C00000",
				"showInLegend" => true,
				"dataPoints"   => $dataPointAccess
			),
			array(
				"type"    => "spline",
				"indexLabel" => "{y}",
				"color"	 => "#eba000",
				"name"    => "Tra cứu nhiều",
				"showInLegend"    => true,
				"dataPoints"    => $dataPointsSearch
			),
		);
	} else if($type=='service_MOC'){
		$due_date = time();
		$start_date = strtotime('-15 days', $due_date);
		$dataPointLogin = $dataPointsSearch = $dataPointAccess= array();
		$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=$start_date; $i<= $due_date; $i = strtotime('+1 day', $i)){
			$date = date('d/m/Y', $i);
			$clsSop = new Sop();
			$clsInterior = new Interior();
			$clsLeasing = new Leasing();
			$clsService = new Service();
			$total_sop = $clsSop->countItem("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='{$date}'");
			$total_interior = $clsInterior->countItem("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='{$date}'");
			$total_leasing = $clsLeasing->countItem("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='{$date}'");
			$total_service = $clsService->countItem("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='{$date}'");
			###
			$dataPointSop[] = array(
				'label' => $date,
				'y' => $total_sop*1
			);
			$dataPointInterior[] = array(
				'label' => $date,
				'y' => $total_interior*1
			);
			$dataPointLeasing[] = array(
				'label' => $date,
				'y' => $total_leasing*1
			);
			$dataPointService[] = array(
				'label' => $date,
				'y' => $total_service*1
			);
		}
		$barChartData['data'] = array(
			array(
				"type"    => "spline",
				"indexLabel" => "{y}",
				"color"	 => "#ff3e1d",
				"name"    => "Chuyển nhượng",
				"showInLegend"    => true,
				"dataPoints"    => $dataPointSop
			),
			array(
				"type"    => "spline",
				"indexLabel" => "{y}",
				"color"	 => "#03c3ec",
				"name"    => "Nội thất",
				"showInLegend"    => true,
				"dataPoints"    => $dataPointInterior
			),
			array(
				"type"    => "spline",
				"indexLabel" => "{y}",
				"color"	 => "#eba000",
				"name"    => "Cho thuê",
				"showInLegend"    => true,
				"dataPoints"    => $dataPointLeasing
			),
			array(
				"type"    => "spline",
				"indexLabel" => "{y}",
				"color"	 => "#696cff",
				"name"    => "Dịch vụ",
				"showInLegend"    => true,
				"dataPoints"    => $dataPointService
			)
		);
	} else {
		if(!empty($month)){
			$month = $clsISO->parseNumber($month);
			$array_data = $clsMOCLog->getAll("FROM_UNIXTIME(`reg_date`,'%m/%Y')='".sprintf('%s/%s', $month, $year)."' 
				GROUP BY (url) ORDER BY `total` DESC ",$clsMOCLog->pkey.",url,count(url) as total");
		}else{
			$array_data = $clsMOCLog->getAll("FROM_UNIXTIME(`reg_date`,'%Y')='".$year."' 
				GROUP BY (url) ORDER BY total DESC",$clsMOCLog->pkey.",url,count(url) as total");
		}
		$assign_list['array_data'] = $array_data;
	}
	// Return
	$html = $core->build("_ajax.table_chart.tpl");
	echo json_encode( array_merge($more, array(
		'uid' => $uid,
		'html' => $html, 
		'barChartData' => $barChartData
	)));
}
function default_open_import_logs(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsUser = new User();
	$clsAdminLog = new AdminLog();
	$clsProperty = new Property();
	$agency_id = Input::post('agency_id', 0);
	$list_logs = $clsAdminLog->getAll("`target_id`='{$agency_id}' and `action`='update_stock' order by `date` DESC limit 0,50");
	if(!empty($list_logs)){
		foreach($list_logs as $key => $val){
			$user_id = $val['user_id'];
			if($user_id == $core->_USER['user_id']){
				$list_logs[$key]['full_name'] = sprintf('%s %s', $core->_USER['first_name'], $core->_USER['last_name']);
			} else {
				$oUser = $clsUser->getOne($user_id, "first_name, last_name");
				$list_logs[$key]['full_name'] = sprintf('%s %s', $oUser['first_name'], $oUser['last_name']);
			}
		}
	}
	$smarty->assign('agency_id', $agency_id);
	$smarty->assign('list_logs', $list_logs);
	$smarty->assign('clsProperty', $clsProperty);
	//Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.open_import_logs.tpl');
	$uid = $clsISO->getUniqid();
	echo json_encode(array(
		'html' => $html,
		'uid'	=>	$uid
	)); die();
}
function default_load_profile_popover(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	//ini_set('display_errors',1);
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$user_id = (int) Input::get('user_id', 0);
	if($user_id == $profile_id){
		$oneUser = $oneProfile;
	} else {
		$oneUser = $clsMember->getOne($user_id);
	}
	$role_id = $oneUser['role_id'];
	$department_id = $oneUser['department_id'];
	$total_billing = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$total_price = $clsBilling->sumItem("totalgrand", "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$user_id}'");
	$html = '<div class="links-bar-create-edit clearfix">
		<div class="profile-photo-create-edit pull-left mr-2">
			<img src="'.$clsMember->getAvatar($user_id, $oneUser).'">
		</div>
		<div class="title fs-16 bold">
			'.$clsMember->getIndentityV2($user_id, $oneUser, false).'
		</div>
		<div class="subtitle mt-1 textred">'.$clsProperty->getTitle($role_id).'</div>
	</div>
	<div class="bg-lighter rounded-2 p-3 mt-2">
		<div class="form-group mb-1">
			'.$core->makeIcon('phone', $clsMember->getPhone($user_id, $oneUser, true)).'
		</div>
		<div class="form-group mb-1">
			'.$core->makeIcon('envelope', $clsMember->getEmail($user_id, $oneUser, true)).'
		</div>
		<div class="form-group mb-1">
			'.$core->makeIcon('cc', $clsMember->getCCID($user_id, $oneUser, true)).'
		</div>
		<div class="form-group">
			'.$core->makeIcon('calendar-o', $clsMember->getBirthday($user_id, $oneUser)).'
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_billing(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list['list_preloaders'] = $list_preloaders;
	$report_type = Input::get('report_type', 'common');
	$box_width = ($report_type=='common') ? 350 : 200;
	$assign_list['report_type'] = $report_type;
	$assign_list['box_width'] = $box_width;
	$columnNum = ($deviceType=='phone') ? 2 : 4;
	$assign_list['columnNum'] = $columnNum;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo giao dịch chốt - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_billings(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsProject = new Project();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$group_product = Input::post("group_product");
	$billing_type = (int) Input::post('billing_type', 0);
	$report_type = Input::post('report_type', 'common');
	// `is_cancel`=0 
	$cond = "`is_trash`=0"; // and `is_alliance`=0
	if($month == 0){
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	} else {
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$m}'";
	}
	// Lọc dữ liệu
	if($report_type == 'mwf'){
		$cond.= " and billing_type='{$billing_type}' and `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'";
	} else {
		if($group_product == 'CAO_TANG'){
			$group_arrs = array(_BILLING_TYPE_CT_ID,_BILLING_TYPE_MWF_ID,_BILLING_TYPE_LSP_ID);
			$cond.= " and `billing_type` in (".implode(',', $group_arrs).")";
		} else if($group_product == 'THAP_TANG'){
			$cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
		} else if($group_product == 'CHO_THUE') {
			$group_arrs = array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID);
			$cond.= " and `billing_type` in (".implode(',', $group_arrs).")";
		}
	}
	if(in_array($oneProfile['role_id'], array(_ROLE_GD_PROJECT))){
		$params = array();
		$more_information = $oneProfile['more_information'];
		$permiss_billing = isset($more_information['permiss_billing']) 
			? $more_information['permiss_billing'] : array();
		if(!empty($permiss_billing)){
			foreach($permiss_billing as $project_id => $arrs){
				if(!empty($arrs)){
					foreach($arrs as $id){
						$params[] = sprintf('|%s_%s|', $project_id, $id);
					}
				}
			}
		}
		if(!empty($params)){
			$cond.= " and `billing_search` in ('".implode('\',\'',$params)."')";
		} else {
			$params = array();
			$params[] = sprintf('|%s_%s|', 100000, 100000);
			$cond.= " and `billing_search` in ('".implode('\',\'',$params)."')";
		}
	}
	$field = "{$clsBilling->pkey},`project_id`,`staff_id`,`stock_code`,`deposit_date`,`contract_date`,`is_cancel`";
	$field.= ",`estimate_date`,`otp_date`,`contract_status_id`,`totalgrand`,`more_information`";
	$total_record = $clsBilling->countItem($cond);
	$list_billings = $clsBilling->getAll($cond." order by `reg_date` DESC", $field);
	$total_canceled = $clsBilling->countItem("{$cond} and is_cancel='1'");
	$total_registed_hdmb = $clsBilling->countItem($cond."  and `is_cancel`=0 
		and `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
	$total_unregisted_hdmb = $clsBilling->countItem($cond." and `is_cancel`=0 
		and (`contract_status_id`='"._CONTRACT_STATUS_WAIT_ID."' or `contract_status_id`='"._CONTRACT_STATUS_OTP_ID."') 
		and `estimate_date`<>'0'");
	$total_not_schedule_hdmb = $clsBilling->countItem($cond." and `is_cancel`=0 
		and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."' and `estimate_date`='0'");
	$sum = '<div class="d-flex flex-wrap gap-1">
		<div class="flex-fill px-3 py-2 bg-lightest border text-center rounded-pill">
			Đã hủy: <strong class="text-main fw-bold">'.$total_canceled.'</strong></div>
		<div class="flex-fill px-3 py-2 bg-lightest border text-center rounded-pill">
			Đã ký: <strong class="text-main fw-bold">'.$total_registed_hdmb.'</strong></div>
		<div class="flex-fill px-3 py-2 bg-lightest border text-center rounded-pill">
			Có lịch ký: <strong class="text-main fw-bold">'.$total_unregisted_hdmb.'</strong></div>
		<div class="flex-fill px-3 py-2 bg-lightest border text-center rounded-pill">
			Chưa có lịch: <strong class="text-main fw-bold">'.$total_not_schedule_hdmb.'</strong></div>
	</div>';
	$html = "";
	if(!empty($list_billings)){ $ii = 1;
		$total_prices = 0;
		$arr_project_cached = $arr_property_cached = $arr_staff_cached = array();
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			$project_id = $val['project_id'];
			$contract_status_id = $val['contract_status_id'];
			$stock_code = $val['stock_code'];
			$stock_field = "{$clsStock->pkey},building_id,bedroom_id,more_information";
			$oneStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", $stock_field);
			$bedroom_id = $oneStock['bedroom_id'];
			$building_id = $oneStock['building_id'];
			$stock_information = $oneStock['more_information'];
			$stock_information = $clsISO->to_array_json($stock_information);
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$sale_agency_id = isset($more_information['sale_agency_id']) 
				? (int) $more_information['sale_agency_id'] : 0;
			$stock_resource = isset($more_information['stock_resource']) 
				? $more_information['stock_resource'] : 0;
			$stock_resource_name = $stock_resource;
			if(!empty($stock_resource) && is_numeric($stock_resource)){
				if(isset($arr_property_cached[$stock_resource])){
					$stock_resource_name = $arr_property_cached[$stock_resource];
				} else {
					$arr_property_cached[$stock_resource] = $clsProperty->getTitle($stock_resource);
					$stock_resource_name = $arr_property_cached[$stock_resource];
				}
			}
			if(!isset($arr_property_cached[$bedroom_id])){
				$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
			}
			if(!isset($arr_property_cached[$building_id])){
				$arr_property_cached[$building_id] = $clsProperty->getCode($building_id);
			}
			if(!isset($arr_property_cached[$contract_status_id])){
				$arr_property_cached[$contract_status_id] = $clsProperty->getTextColor($contract_status_id);
			}
			if(!isset($arr_staff_cached[$staff_id])){
				$oneStaff = $clsMember->getOne($staff_id, "full_name,first_name,last_name,code");
				$arr_staff_cached[$staff_id] = sprintf('%s-%s', $oneStaff['code'], $clsMember->getFullName($staff_id, $oneStaff));
			}
			if(!isset($arr_project_cached[$project_id])){
				$arr_project_cached[$project_id] = $clsProject->getOneField('code', $project_id);
			}
			if($sale_agency_id > 0 && !isset($arr_property_cached[$sale_agency_id])){
				$arr_property_cached[$sale_agency_id] = $clsProperty->getTitle($sale_agency_id);
			}
			$total_prices += $clsISO->processSmartNumber($val['totalgrand']);
			$html.= '<tr'.($val['is_cancel'] == 1? ' class="tr_canceled"':'').'>
				<td class="text-center">'.$ii.'</td>
				<td class="text-left">'.$arr_project_cached[$project_id].'</td>
				<td class="text-center">'.$clsISO->convertTimeToText($val['deposit_date']).'</td>
				<td class="text-center">'.$arr_property_cached[$building_id].'</td>
				<!-- <td class="text-left">'.$arr_staff_cached[$staff_id].'</td> -->
				<td class="text-left fw-bold">'.(!empty($oneStock) ? '<a class="text-link cursor-pointer" onClick="$Core.helper.open_stock('.$oneStock[$clsStock->pkey].')">'.$stock_code.'<i class=\'bx bx-link-external fs-12\'></i></a>' : $stock_code).'</td>
				<td class="text-center">'.$arr_property_cached[$bedroom_id].'</td>
				<td class="text-left text-main">'.$clsISO->formatPrice($val['totalgrand']).$clsISO->getRate().'</td>
				<td class="text-center">'.(!empty($val['otp_date']) ? $clsISO->convertTimeToText($val['otp_date']) : '<span class="text-muted">Chưa ký OTP</span>').'</td>
				<td class="text-center">'.$clsISO->convertTimeToText($val['deposit_date']).'</td>
				<td class="text-center">'.(!empty($val['contract_date']) ? $clsISO->convertTimeToText($val['contract_date']) : '-').'</td>
				<td class="text-center">'.(!empty($val['estimate_date']) && $contract_status_id != _CONTRACT_STATUS_DONE_ID ? $clsISO->convertTimeToText($val['estimate_date']) : '-').'</td>
				<td class="text-center">'.$arr_property_cached[$contract_status_id].'</td>
				<td class="text-left">'.$arr_property_cached[$sale_agency_id].'</td>
				<td class="text-left">'.(!empty($stock_resource_name) ? $stock_resource_name : "---").'</td>
			</tr>';
			++$ii;
		}
		$html.= '<tr>
			<td colspan="4">--</td>
			<td colspan="2">--</td>
			<td class="fw-bold" colspan="10">'.$clsISO->formatPrice($total_prices).$clsISO->getRate().'</td>
		</tr>';
	} else {
		$html.= '<tr>
			<td colspan="14" class="text-center">
				<div class="p-5">
					<img src="'.URL_IMAGES.'/illustration-empty-results.svg" width="150px" />
					<p class="text-muted mb-0">Không có dữ liệu phù hợp</p>
				</div>
			</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'sum' => $sum,
		'cond' => $cond,
		'total_record' => $total_record
	)); die();
}
function default_stock_resource(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Thống kê check nguồn căn - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_stock_resource_logs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsStock = new Stock();
	$clsMember = new Member();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockCheck = new StockCheck();
	###
	$html = "";
	$cond = "`is_trash`=0 and `log_type`='_stock_resource' and `from_site`='MOC'";
	$start_date = Input::post('start_date');
	$due_date = Input::post('due_date');
	$start_date_def = strtotime(date('d-m-Y'));
	$due_date_def = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : $start_date_def;
	$due_date = !empty($due_date) ? $clsISO->toTime(sprintf('%s 23:59:59', $due_date)) : $due_date_def;
	$cond.= " and (`reg_date` between {$start_date} and {$due_date})";
	#- Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 100);
	$total_record = $clsStockCheck->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	$list_logs = $dbconn->getAll("select *,count(stock_id) as `total_search` from ".$clsStockCheck->tbl." 
		where {$cond} group by stock_id order by reg_date DESC".$limitCond);
	if(!empty($list_logs)){ $ii = 0;
		$arr_profile_cached = $arr_project_cached = $arr_property_cached = array();
		foreach($list_logs as $key => $val){
			$user_id = $val['user_id'];
			$stock_id = $val["stock_id"];
			//$oneStock = $clsStock->getOne($stock_id, "project_id,block_id");
			//$project_id = $oneStock['project_id'];
			//$block_id = $oneStock['block_id'];
			if(!isset($arr_profile_cached[$user_id])){
				$arr_profile_cached[$user_id] = $clsMember->getIndentityV6($user_id, true);
			}
			//if(!isset($arr_project_cached[$project_id])){
			//	$arr_project_cached[$project_id] = $clsProject->getCode($project_id);
			//}
			//if(!isset($arr_property_cached[$block_id])){
			//	$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
			//}
			$html.= '<tr>
				'.($deviceType!='phone'?'<td class="text-center">'.($ii+1).'</td>':'').'
				<!-- <td class="text-left">'.$arr_project_cached[$project_id].'</td>
				<td class="text-left">'.$arr_property_cached[$block_id].'</td> -->
				<td class="text-left border-end"><a href="javascript:void(0);" data-url="'.PCMS_URL.'/index.php?mod=home&sub=project&act=load_stock_popover&stock_id='.$stock_id.'" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350">'.$val["stock_code"].' <i class=\'bx bx-link-external fs-6\'></i></a></td>
				<td class="text-center">'.$val['total_search'].'</td>
				<td class="text-left">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
				<td class="text-left">'.$arr_profile_cached[$user_id].'</td>
			</tr>';
			++$ii;
		}
	} else {
		$html.= '<tr class="nohover">
			<td colspan="6" class="text-center">
				<img src="'.URL_IMAGES.'/listing-empty.svg" class="w-px-150 my-2" />
				<p class="text-muted m-0">Chưa có dữ liệu thống kê check nguồn căn</p>
			</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'per_page' => $per_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	)); die();
}
function default_load_stock_resource_logs_chart(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsStock = new Stock();
	$clsMember = new Member();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockCheck = new StockCheck();
	###
	$html = "";
	$cond = "`is_trash`=0 and `log_type`='_stock_resource' and `from_site`='MOC'";
	$date = Input::post('date');
//	echo $date;die;
	$type = Input::post('type',"");
	$due_date = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$due_date = !empty($date) ? $clsISO->toTime(sprintf('%s 23:59:59', $date)) : $due_date;	
	$start_date = strtotime('-10 days', strtotime(date("d-m-Y",$due_date)));
//	echo date("d/m/Y",$start_date)."=====".date("d/m/Y",$due_date);die;
	$cond.= " and (`reg_date` between {$start_date} and {$due_date})";
	#- Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 100);
	$total_record = $clsStockLog->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	#- End Pagination	
	$arr_time = [];	
	$limit = 7;
	for($i=0; $i<$limit; $i++){
		$arr_time[] = strtotime("-".$i."days",$due_date);
	}
	$arr_time = array_reverse($arr_time);
	if($type == "ViewLogSearch") {
		$list_logs = $dbconn->getAll("select * from ".$clsStockLog->tbl." 
		where {$cond} group by stock_id order by reg_date DESC");
		$data = [];
		$t=1;
		if(!empty($list_logs)){
			$arr_profile_cached = array();
			foreach($list_logs as $key => $val){
				if(isset($data[date("d/m/Y",$val['reg_date'])])) {
					$data[date("d/m/Y",$val['reg_date'])] += 1;
				}else{
					$data[date("d/m/Y",$val['reg_date'])] = 1;
				}
			}
		}
		for($i=0; $i<count($arr_time); $i++){
			$day = date("d/m/Y",$arr_time[$i]);
			if(isset($data[$day])) {
				$dataPoints[] = array(
					'label'	=> $day,
					'y'	=> $data[$day]
				);
			}else{
				$dataPoints[] = array(
					'label'	=> $day,
					'y'	=> 0
				);
			}
		}
		$barChartData['data'] = array(
			array(
				"type"    => "spline",
				"color"	 => "#1d6a01",
				"name"    => "Lượt",
				"yValueFormatString"	=> "# lượt",
				"showInLegend"    => true,
				"dataPoints"    => $dataPoints
			),
			array(
				"type"  => "column",
				"name"	=> "Lượt",
				"yValueFormatString"	=> "# lượt",
				"color"	 => "#C00000",
				"showInLegend" => true,
				"dataPoints"   => $dataPoints
			)
		);
	}else if($type == "ViewUserSearch"){
		$data = [];
		$list_logs = $dbconn->getAll("select * from ".$clsStockLog->tbl." where {$cond}");
		foreach ($list_logs as $key => $value) {
			if(isset($data[$value['user_id']][date("d/m/Y",$value['reg_date'])])){
				$data[$value['user_id']][date("d/m/Y",$value['reg_date'])] += 1;
				$data[$value['user_id']]["total"] += 1;
			}else{
				$data[$value['user_id']][date("d/m/Y",$value['reg_date'])] = 1;
				$data[$value['user_id']]["total"] += 1;
			}
			$data[$value['user_id']]["user_id"] = $value['user_id'];
		}
		$total_data = @array_column($data, 'total');
		@array_multisort($total_data, SORT_DESC, $data);
		for($i=0; $i<count($arr_time); $i++){
			$day = date("d/m/Y",$arr_time[$i]);
			$j=0;
			foreach($data as $key => $value){
				$y=0;
				if($j == 20){
					break;
				}
				if(isset($value[$day])){
					$y = $value[$day];
				}
				$dataPoints[$value['user_id']]["data"][] = array(
					'label'	=> $day,
					'y'	=> $y
				);
				if(!isset($array_cache_user[$value['user_id']])){
					$array_cache_user[$value['user_id']] = $clsMember->getFullName($value['user_id']);
				}
				$dataPoints[$value['user_id']]["full_name"] = $array_cache_user[$value['user_id']];
				++$j;
			}
		}
		$barChartData['data'] = [];
		$i=1;
		foreach($dataPoints as $key => $value) {
			$barChartData['data'][] = array(
				"type"    				=> "spline",
				"name"    				=> $value["full_name"],				
				"yValueFormatString"	=> $value["full_name"].": # lượt",
				"showInLegend"    		=> true,
				"dataPoints"    		=> $value["data"],
				"legendText"			=>	"Top ".$i,
			);
			++$i;
		}
	}
	$uid = $clsISO->getUniqid();
	echo json_encode( array(
		'uid' => $uid,
		'barChartData' => $barChartData 
	));die;
}
function default_order_package(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsLog = new Log();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsOrder = new Order();
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$totalUserPackageTrial = $clsMember->countItem("`is_active`=1 AND JSON_EXTRACT(`more_information`,\"$.is_tried\") = 1"); 
	$smarty->assign('totalUserPackageTrial', $totalUserPackageTrial);	
	$totalUserPackagePro = $clsMember->countItem("`is_active`=1 AND role_id = '"._MEMBER_PARKAGE_PRO_ID."'"); 
	$smarty->assign('totalUserPackagePro', $totalUserPackagePro);
	$totalUserPackageVip = $clsMember->countItem("`is_active`=1 AND role_id = '"._MEMBER_PARKAGE_VVIP_ID."'"); 
	$smarty->assign('totalUserPackageVip', $totalUserPackageVip);
	$total_revenue = $clsOrder->getAll("status = '1'","SUM(JSON_EXTRACT(`more_information`,\"$.response.data.amount\")) as total");	
	$smarty->assign('total_revenue', $total_revenue[0]["total"]);
	$total_success = $total_extend = 0;
	$lstOrder = $clsOrder->getAll();
	$total_order = count($lstOrder);
	foreach($lstOrder as $key => $value) {
		if($value['status'] == 1) {
			++$total_success;
		}
	}
	if($total_order > 0) {
		$total_extend = round($total_success/$total_order,2);
	}
	$smarty->assign('total_order', $total_order);
	$smarty->assign('total_success', $total_success);
	$smarty->assign('total_extend', $total_extend*100);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	#
	$list_block_mas = array(
		'mwf' => 'Phân khu Masteri',
		'miami' => 'Miami M1-2-3',
		'hawaii' => 'Hawaii H1-H2',
	);
	$smarty->assign('list_block_mas', $list_block_mas);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo tăng trưởng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_order(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	$clsAPI = new API();
	$clsOrder = new Order();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$data = array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	###
	$html = "";
	$uid = $clsISO->getUniqid();
	$array_data = $more = array();
	$type = Input::post('type', ''); 
	$time_type = Input::post('time_type', 'THIS_MONTH');
	$month = Input::post("month");
	$year = Input::post("year", date('Y'));
	$by_day = Input::post('by_day');
	$assign_list['uid'] = $uid;
	$assign_list['type'] = $type;
	###
	if($type == "NUMBER_CHART"){
		$month = (int) Input::post('month', 0);
		$year = (int) Input::post('year', date('Y'));
		if($month == 0){
			$cond.= "FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
		} else {
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= "FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$m}'";
		}
		$access_logs = $clsOrder->getAll($cond.' ORDER BY reg_date ASC',$clsOrder->pkey.",reg_date,status,JSON_EXTRACT(`more_information`,\"$.response.data.amount\") as amount");
		if(!empty($access_logs)){
			$arr_data = $arr_data_number = []; $arr_data_revenue = [];
			if($month == 0){
				for($i=1; $i<=12; $i ++) {
					if($i < 10) {
						$arr_data_number[date("0".$i."-Y")] = 0;
						$arr_data_revenue[date("0".$i."-Y")] = 0;
					}else{
						$arr_data_number[ date($i."-Y")] = 0;
						$arr_data_revenue[ date($i."-Y")] = 0;
					}					
				}
				foreach($access_logs as $key => $value) {
					$arr_data_number[date("m-Y",$value['reg_date'])] += 1;
					if($value['status'] == 1){
						$arr_data_revenue[date("m-Y",$value['reg_date'])] += $value['amount'];
					}					
				}
			} else {
				for($i=1; $i<=date("d"); $i ++) {
					if($i < 10) {
						$arr_data_number[date("0".$i."-m-Y")] = 0;
						$arr_data_revenue[date("0".$i."-m-Y")] = 0;
					}else{
						$arr_data_number[date($i."-m-Y")] = 0;
						$arr_data_revenue[date($i."-m-Y")] = 0;
					}					
				}
				foreach($access_logs as $key => $value) {
					$arr_data_number[date("d-m-Y",$value['reg_date'])] += 1;
					if($value['status'] == 1){
						$arr_data_revenue[date("d-m-Y",$value['reg_date'])] += $value['amount'];
					}
				}
			}
			#				
			foreach($arr_data_number as $k => $v){
				$dataPointsNumber[] = array(
					'label'	=> $k,
					'y'	=> $v
				);
			}				
			foreach($arr_data_revenue as $k => $v){
				$dataPointsRevenue[] = array(
					'label'	=> $k,
					'y'	=> $v
				);
			}
			$barChartData['data'] = array(
				array(
					"type"    => "spline",
					"color"	 => "#1d6a01",
					"name"    => "Số lượng hoá đơn",
					"showInLegend"    => true,							
					"yValueFormatString"	=> "Số lượng: # order",
					"dataPoints"    => $dataPointsNumber
				),
				array(
					"type"  => "spline",
					"name"	=> "Doanh thu",
					"color"	 => "#C00000",
					"showInLegend" => true,		
					"yValueFormatString"	=> "Doanh thu: # đ",
					"dataPoints"   => $dataPointsRevenue
				)
			);
		}
	}else if($type == "MEMBER"){
		$arr_package = $clsProperty->getArraySearchByKey("_PACKAGE");
		$clsMember = new Member();
		$keyword = Input::post("keyword",""); 
		$cond = "`is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		$package_id = Input::post("package_id",1);
		if($package_id == 1) {
			$cond .= " AND JSON_EXTRACT(`more_information`,\"$.is_tried\") = 1";
		}else {
			$cond .= " AND role_id='{$package_id}'";
		}
		if($keyword != ""){
			$cond .= " AND (full_name_slug LIKE '%{$keyword}%' OR full_name LIKE '%{$keyword}%')";
		}
		$array_role = [];
		$lst_user = $clsMember->getAll($cond." ORDER BY reg_date DESC");
		foreach($lst_user as $key => $value){
			$more_information = $clsISO->to_array_json($value['more_information']);
			$lst_user[$key]['start_date'] = $more_information['VIP']['start_date'];
			$lst_user[$key]['due_date'] = $more_information['VIP']['due_date'];
			if($package_id == 1) {
				$lst_user[$key]['package_name'] = "Gói dùng thử";
			}else{
				$lst_user[$key]['package_name'] = $arr_package[$value['role_id']]['title'];
			}
		}
		$assign_list['lst_user'] = $lst_user;
	}
	// Return
	$html = $core->build("_ajax.table_chart_order.tpl");
	echo json_encode( array_merge($more, array(
		'uid' => $uid,

		'html' => $html, 
		'barChartData' => $barChartData
	)));
}
function default_loyalty(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsLog = new Log();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFPoint = new FPoint();
	if(!$clsISO->checkPermissionGroup('DIRECTOR')){
		header('Location:/');
		exit();
	}
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_months);
	$cond = "is_trash=0 and is_active=1";
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$lstDepartment = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='_DEPARTMENT' and property_id  NOT IN ("._DEPARTMENT_BGD.","._DEPARTMENT_CTV_ID.") order by order_no ASC",$clsProperty->pkey.',title,parent_id');
//	$clsISO->print_pre($arr_department);die;
	unset($arr_department[_DEPARTMENT_CTV_ID]);
	$smarty->assign('lstDepartment', $lstDepartment);
	$smarty->assign('totalPoint', $totalPoint);
	$smarty->assign('arr_department', $arr_department);
	$smarty->assign('arr_depart_point', $arr_depart_point);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Thống kê điểm loyalty - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_loyalty_chart(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$year = (int) Input::post('year', date('Y'));
	
	$data = array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	for($month = 1; $month<=12; $month++){
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$total_scores = $clsFPoint->sumItem("score", "`ns_type`='Lpoint' and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$m}'");
		$dataPoints[] = array(
			'label'	=> sprintf('T%s', $month),
			'y'	=> $total_scores * 1,
			'indexLabel' => $total_scores
		);
	}
	$data['type'] = 'spline';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	$html = '<div id="'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html, 
		'draw_chart' => 1,
		'barChartData' => $barChartData
	));
}
function default_load_loyalty_staffs(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn,$deviceType;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$field = "{$clsProfile->pkey},department_id,role_id,full_name,first_name,last_name,total_Lpoint";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`='"._STATUS_STAFF_ON_ID."' 
	and `department_id`<>'"._DEPARTMENT_BGD."' order by `total_Lpoint` DESC", $field);
	$html.= '<table class="table">
		<thead><tr>
			<th class="align-center text-left">Họ và tên</th>
			<th class="align-center text-center">Phòng ban</th>
			<th class="align-center text-center">Điểm</th>
			'.($deviceType!='phone'?'<th class="align-center text-left">Họ và tên</th>
			<th class="align-center text-center">Phòng ban</th>
			<th class="align-center text-center">Điểm</th>':'').'
		</tr></thead>';
	if(!empty($list_staffs)){
		$arr_property_cached = array();
		if($deviceType=='phone'){
			foreach($list_staffs as $key => $val){
				$staff_id = $val[$clsProfile->pkey];
				$department_id = (int) $val['department_id'];
				$role_id = (int) $val['role_id'];
				if($department_id > 0 && !isset($arr_property_cached[$department_id])){
					$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
				}
				if($role_id > 0 && !isset($arr_property_cached[$role_id])){
					// $arr_property_cached[$role_id] = $clsProperty->getTitle($role_id);
				}
				$html.= '<tr>
					<td><a href="javascript:void(0);" class="text-body fw-bold" onClick="$Core.member.view_profile(this, event);" 
					profile_id="'.$staff_id.'">'.$clsProfile->getFullName($staff_id, $val).'</td>
					<td class="text-left">'.$arr_property_cached[$department_id].'</td>
					<td class="text-center'.($kk==0?' border-end':'').'">
						<a href="javascript:void(0);" class="text-orange" onClick="$Core.global.open_Lpoint(this, event)" 
						staff_id="'.$staff_id.'">'.$val['total_Lpoint'].'
					</td>
				</tr>';	
			}
		} else {
			$tmp = array(); $kk = 0;
			$total_staffs = count($list_staffs);
			for($ii=0; $ii<$total_staffs; $ii++){
				$tmp[$kk][] = $list_staffs[$ii];
				if(($ii+1)%2==0) $kk++;
			}
			foreach($tmp as $groups){
				$html.= '<tr>'; $kk = 0;
				foreach($groups as $val){
					$staff_id = $val[$clsProfile->pkey];
					$department_id = (int) $val['department_id'];
					$role_id = (int) $val['role_id'];
					if($department_id > 0 && !isset($arr_property_cached[$department_id])){
						$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
					}
					if($role_id > 0 && !isset($arr_property_cached[$role_id])){
						// $arr_property_cached[$role_id] = $clsProperty->getTitle($role_id);
					}
					$html.= '<td><a href="javascript:void(0);" class="text-body fw-bold" onClick="$Core.member.view_profile(this, event);" 
					profile_id="'.$staff_id.'">'.$clsProfile->getFullName($staff_id, $val).'</td>
					<td class="text-center">'.$arr_property_cached[$department_id].'</td>
					<td class="text-center'.($kk==0?' border-end':'').'">
						<a href="javascript:void(0);" class="text-orange" onClick="$Core.global.open_Lpoint(this, event)" 
						staff_id="'.$staff_id.'">'.$val['total_Lpoint'].'
					</td>';	
					++$kk;
				}
				$html.= '</tr>';
			}
		}
	}
	$html.= '</table>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_list_profile(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$html = "";
	$uid = $clsISO->getUniqid();
	$array_data = $more = array();
	$type = Input::post('type', '_TOTAL'); 	
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$keyword = Input::post("keyword", "");
	$department_id = (int)Input::post("department_id", 0);
	
	$arr_notin = array(_DEPARTMENT_DIRECTOR_ID, _DEPARTMENT_CTV_ID);
	$cond = "`t2`.`is_trash`=0 AND `t2`.`status_id`='"._STATUS_STAFF_ON_ID."' AND `t2`.`department_id` NOT IN (".implode(',', $arr_notin).")";
	if($department_id > 0){
		$cond .= " AND (`t2`.`department_id`='{$department_id}%' 
			OR `t2`.`list_department_id` LIKE '%|{$department_id}|%'
		)";
	}
	if(!empty($keyword)) {
		$cond .= " AND (`t2`.`full_name_slug` LIKE '%".$clsISO->replaceSpace($keyword)."%' 
			OR `t2`.`full_name` LIKE '%{$keyword}%')";
	}
	if($type == "_TOTAL") {			
		if($month == 0){
			$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`,'%Y')='{$year}'";
		} else {
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`,'%m/%Y')='{$m}'";
		}
		$lstDepartment = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
		and `property_id` NOT IN (".implode(',',$arr_notin).") order by `order_no` ASC",$clsProperty->pkey.',title,parent_id');
		$lstPointDepartment = $dbconn->getAll("SELECT `t2`.`department_id`,SUM(`t1`.`score`) as `total_point` 
			FROM {$clsFPoint->tbl} as `t1` LEFT JOIN {$clsProfile->tbl} as `t2` ON `t1`.`profile_id`=`t2`.`profile_id` 
			WHERE ".$cond." GROUP BY `t2`.`department_id` ORDER BY `total_point` ASC");
		$arr_depart_point = [];
		foreach ($lstPointDepartment as $key => $value) {
			$arr_depart_point[$value['department_id']] = $value['total_point'];
		}
		$totalPoint = 0;
		$arr_department = [];
		foreach($lstDepartment as $key => $value) {
			if($value['parent_id'] == 0) {
				$arr_department[$value['property_id']] = [
					'title' =>	$value['title'],
					"lstChild"	=>	[]
				];
				$totalPoint += (isset($arr_depart_point[$value['property_id']]) ? $arr_depart_point[$value['property_id']] : 0);
			}else{
				if(!isset($arr_department[$value['parent_id']])) {
					$title_parent = "";
				}else{
					$title_parent = $arr_department[$value['parent_id']]["title"];
				}	
				$lstChild[$value['property_id']] = [
					'title'	=>	$value['title']
				];		
				$arr_department[$value['parent_id']] = [
					'title'	=>	$title_parent,
					'lstChild'	=>	$lstChild
				];
				$totalPoint += (isset($arr_depart_point[$value['property_id']]) ? $arr_depart_point[$value['property_id']] : 0);
			}
		}
		foreach ($arr_department as $key => $value) {
			$point_department = isset($arr_depart_point[$key]) ? $arr_depart_point[$key] : 0;
			$lstChild = $value['lstChild'];
			foreach ($lstChild as $k => $v) {
				$lstChild[$k]["point"] = (isset($arr_depart_point[$k]) ? $arr_depart_point[$k] : 0);
				$point_department += (isset($arr_depart_point[$k]) ? $arr_depart_point[$k] : 0);
			}
			$arr_department[$key]['point'] = $point_department;
			$arr_department[$key]['lstChild'] = $lstChild;
		}
		unset($arr_department[_DEPARTMENT_CTV_ID]);
		$smarty->assign('lstDepartment', $lstDepartment);
		$smarty->assign('totalPoint', $totalPoint);
		$smarty->assign('arr_department', $arr_department);
		$smarty->assign('arr_depart_point', $arr_depart_point);
	}else if($type == "_TOP") {	
		$arr_department = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		$field = "{$clsProfile->pkey},first_name,last_name,full_name,total_Lpoint,avatar,department_id";
		$lstTopProfile = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by `total_Lpoint` DESC limit 0,10", $field);
		if(!empty($lstTopProfile)){
			foreach($lstTopProfile as $key => $value) {
				$lstTopProfile[$key]['department_name'] = $arr_department[$value['department_id']]['title'];
				$lstTopProfile[$key]['more_information'] = $clsISO->to_array_json($value['more_information']);
			}
		}
		$smarty->assign('lstTopProfile', $lstTopProfile);
	}else if($type == "_LIST" || $type == "_PAGE"){					
		if($month == 0){
			$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`,'%Y')='{$year}'";
		} else {
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`,'%m/%Y')='{$m}'";
		}
		$current_page = (int)Input::post("page",1);
		$per_page = (int)Input::post("per_page",20);
		$offset = ($current_page-1) * $per_page;
		$limitCond = " limit {$offset},{$per_page}";
		$order_by = " ORDER BY `t1`.reg_date DESC";
		###
		$allItem = $dbconn->getAll("SELECT count(`t1`.profile_id) as total_record FROM {$clsFPoint->tbl} as t1 
			LEFT JOIN {$clsProfile->tbl} as t2 ON `t1`.profile_id = `t2`.profile_id WHERE ".$cond);
		$total_record = $allItem[0]['total_record'];
		$total_page = @ceil($total_record/$per_page);
		$lstProfile = $dbconn->getAll("SELECT `t2`.department_id,`t2`.full_name,`t2`.profile_id,`t1`.score,`t1`.`reg_date`,`t1`.`act` 
			FROM {$clsFPoint->tbl} as t1 LEFT JOIN {$clsProfile->tbl} as t2 ON `t1`.profile_id = `t2`.profile_id 
			WHERE ".$cond.$order_by.$limitCond);
		$arr_department = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		unset($arr_department[_DEPARTMENT_BGD],$arr_department[_DEPARTMENT_CTV_ID]);
		foreach($lstProfile as $key => $value) {
			$lstProfile[$key]['department_name'] = $arr_department[$value['department_id']]['title'];
		}
		// $clsISO->print_pre($lstProfile); die();
		$assign_list['lstProfile'] = $lstProfile;
		$assign_list['arr_department'] = $arr_department;
	}	
	$assign_list['uid'] = $uid;
	$assign_list['type'] = $type;
	// Return
	$html = $core->build("_ajax.report_loyalty.tpl");
	echo json_encode( array_merge($more, array(
		'uid' 			=> $uid,
		'html' 			=> $html, 
		'curent_page'	=>	$current_page,
		'total_record'	=>	$total_record,
		'total_page'	=>	$total_page,
		'per_page'		=>	$per_page,
	)));
}
function default_load_month(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	####
	$date_type = Input::post('date_type', '_month');
	if($date_type == '_month'){
		$html_options = sprintf('<option value="0">%s</option>', 'Tháng');
		$year = (int) Input::post('year', date('Y'));
		if($year == 2023){
			$start_month = 5;
			$end_month = 12;
		} else if($year == date('Y')) {
			$start_month = 1;
			$end_month = date('m');
		} else {
			$start_month = 1;
			$end_month = 12;
		}
		for($i=$start_month; $i<= $end_month; $i++){
			$html_options.= sprintf('<option value="%s">Tháng %s</option>', $i, $i);
		}
	} else {
		$html_options = "";
		if($date_type == '_quater'){
			for($i=1; $i<=4; $i++){
				$html_options.= sprintf('<option value="%s">Quý %s</option>', $i, $i);
			}
		} else if($date_type == '_half'){
			for($i=1; $i<=2; $i++){
				$html_options.= sprintf('<option value="%s">Nửa %s năm</option>', $i, ($i==1 ? 'đầu' : 'cuối'));
			}
		}
	}
	// Return
	echo $html_options; die();
}
function default_report_user(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách người dùng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;	
}
function default_load_user(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	####
	$clsMember = new Member();
	$status = Input::post("status","unactive");
	$keyword = Input::post("keyword","");
	$smarty->assign("status",$status);
	$current_page = Input::post("page",1);
	$per_page = Input::post("per_page",20);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$cond = "is_active = 1 AND profile_type='MOC'";
	if($status == "unactive") {
		$cond .= " AND is_verified='0'";
	}else{
		$cond .= " AND is_verified='1'";
	}
	if(!empty($keyword)) {
		$cond .= " AND (full_name LIKE '%".$keyword."%' OR email LIKE '%".$keyword."%' OR phone LIKE '%".$keyword."%')";
	}
	$order_by = " ORDER BY upd_date DESC,phone DESC";
	$lst_user = $clsMember->getAll($cond.$order_by);
	$smarty->assign("lst_user",$lst_user);
	// Return
	$html = $core->build("_ajax.load_user.tpl");	
	echo $html; die();
}
function default_active_user(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	####
	$clsMember = new Member();
	$member_id = (int)Input::post("member_id",0);
	$data = ["result" => false];
	if($member_id > 0) {
		$oneMember = $clsMember->getOne($member_id,"is_verified");
		if(!empty($oneMember) && $clsMember->updateOne($member_id,["is_verified" => 1,"upd_date" => time()])) {
			$data = ["result" => true];
		}
	}
	// Return
	echo json_encode($data); die();
}
function default_report_group_bkc(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsGroupProfile = new GroupProfile();
	
	$Current_Now = time();
	$Current_Month = date('n');
	$Current_Year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<=$Current_Month; $i++){
		$list_months[] = $i;
	}
	$Start_Year = 2024;
	for($i=$Start_Year; $i<=$Current_Year; $i++){
		$list_years[] = $i;
	}
	$list_groups = $clsGroupProfile->getAll("is_trash=0", "{$clsGroupProfile->pkey},title");
	$assign_list["list_groups"] = $list_groups;
	
	$oneGroup = $clsGroupProfile->getOne(_PROFILE_DEFAULT_GROUP_ID);
	$list_profile_id = $oneGroup['list_profile_id'];
	$cond = "`is_trash`=0 and status_id<>'"._STATUS_STAFF_OFF_ID."'";
	$arr_profile_groups = $clsISO->getArrayByTextSlash($list_profile_id);
	if(!empty($arr_profile_groups)) 
		$cond.= " and {$clsProfile->pkey} in(".implode(',', $arr_profile_groups).")";
	$field = "{$clsProfile->pkey},first_name,last_name,full_name";
	$list_staffs = $clsProfile->getAll($cond, $field);
	$assign_list["list_staffs"] = $list_staffs;
	
	$list_preloaders = array();
	for($i=1; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	###
	$start_date = date('Y-m-d', strtotime('-7 days'));
	$end_date = date('Y-m-d', time());
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$assign_list["Current_Year"] = $Current_Year;
	$assign_list["Current_Month"] = $Current_Month;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	$assign_list["list_blocks"] = $list_blocks;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo nhóm/CLB - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_report_group(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsGroupProfile = new GroupProfile();
	$clsProperty = new Property();
	##
	$Current_Now = time();
	$Current_Month = date('n');
	$Current_Year = date('Y');
	$start_date = date('Y-m-d', strtotime('-7 days'));
	$end_date = date('Y-m-d', time());
	$list_months = $list_years = array();
	for($i=1; $i<=$Current_Month; $i++){
		$list_months[] = $i;
	}
	$Start_Year = 2024;
	for($i=$Start_Year; $i<=$Current_Year; $i++){
		$list_years[] = $i;
	}
	##
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	##
	$list_blocks = array(
		'this_month' => array(
			'title' => 'Tổng căn bán tháng này',
			'class' => 'a1a bg-orange'
		), 
		'prev_month' => array(
			'title' => 'So với tháng trước',
			'class' => 'a2a bg-azure'
		),
		'sold_f1' => array(
			'title' => 'Tổng căn bán quỹ F1',
			'class' => 'a3a bg-cyan'
		),
		'sold_cross' => array(
			'title' => 'Tổng căn bán quỹ chéo',
			'class' => 'a4a bg-danger'
		),
		'all_year' => array(
			'title' => 'Tổng căn bán cả năm',
			'class' => 'a5a bg-purple'
		)
	);
	$arr_status_customer_cached = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$assign_list["Current_Year"] = $Current_Year;
	$assign_list["Current_Month"] = $Current_Month;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('list_preloaders', $list_preloaders);
	$smarty->assign('arr_status_customer_cached', $arr_status_customer_cached);
	##
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo nhóm/CLB - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_report_group_total(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn,$deviceType; 
	$clsLog = new Log();
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsProfile = new Profile();	
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$current_now = time();
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$start_date = Input::post("start_date");
	$end_date = Input::post("end_date");
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime($end_date, "23:00:00") : 0;
	// $date_type = Input::post('date_type', '_month');
	$title = ($month > 0) ? sprintf('Tháng %s/%s', $month, $year) : sprintf('Năm %s', $year);
	
	$group_id = (int) Input::post('group_id', 0);
	$group_id = $group_id ?: _PROFILE_DEFAULT_GROUP_ID;
	$oneGroup = $clsGroupProfile->getOne($group_id);
	$list_profile_id = $oneGroup['list_profile_id'];
	$arr_staff_ids = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
	$total_staffs = count($arr_staff_ids);
	// Tra cứu
	$arr_users_search = $arr_users_share = $arr_users_sold = array();
	$total_searchs = $total_users_search = $total_users_not_search = 0;
	$sql_query = "`from_site`='_user' AND (`reg_date` BETWEEN {$start_time} AND {$end_time})";
	$sql_query.= " AND `user_id` IN ('".implode('\',\'', $arr_staff_ids)."')";
	$tmp = $clsLog->getAll($sql_query, "user_id");
	if(!empty($tmp)){
		$total_searchs = count($tmp);
		foreach($tmp as $key => $val){
			if(!in_array($val['user_id'], $arr_users_search)){
				$arr_users_search[] = $val['user_id'];
			}
			unset($tmp);
		}
		$total_users_search = count($arr_users_search);
	}
	$total_users_not_search = $total_staffs - $total_users_search;
	$arr_users_not_search = @array_diff($arr_staff_ids, $arr_users_search);
	$arr_users_not_search = !empty($arr_users_not_search) ? array_values($arr_users_not_search) : [];
	// Tiếp khách
	$total_shareds = $total_users_share = $total_staffs_unshare = $total_guests = 0;
	$sql_query = "`share_type`='share' AND (`reg_date` BETWEEN {$start_time} AND {$end_time})";
	$tmp = $clsShare->getAll("{$sql_query} AND `user_id` in (".implode(',',$arr_staff_ids).")", "`user_id`,`more_information`");
	if(!empty($tmp)){
		$total_shareds = count($tmp);
		foreach($tmp as $okey => $oval){
			$more_infor = $oval['more_information'];
			$more_infor = $clsISO->to_array_json($more_infor);
			$guest_count = (int) $core->get_field($more_infor, "guest_count", 0);
			$total_guests += $guest_count;
			if(!in_array($oval["user_id"], $arr_users_share)){
				$arr_users_share[] = $oval["user_id"];
			}
		}
		unset($tmp);
		$total_users_share = @count($arr_users_share);
	}
	$total_staffs_unshare = $total_staffs - $total_users_share;
	$arr_users_not_share = @array_diff($arr_staff_ids, $arr_users_share);
	$arr_users_not_share = !empty($arr_users_not_share) ? array_values($arr_users_not_share) : [];
	// Giao dịch
	$total_billing_f1 = $total_billing_cross = $last_sale_date = 0;
	$total_billings = $total_grands = $total_staffs_sold = $total_staffs_unsold = 0;
	$sql_query = "`is_trash`=0 AND `is_cancel`=0 AND (`deposit_date` BETWEEN {$start_time} AND {$end_time})";
	$sql_query.= " AND `staff_id` in (".implode(',',$arr_staff_ids).")";
	$tmp = $clsBilling->getAll($sql_query." ORDER BY `deposit_date` DESC", "`staff_id`,`totalgrand`,`deposit_date`,`billing_source_id`");
	if(!empty($tmp)){
		$total_billings = count($tmp);
		$last_sale_date = $tmp[0]['deposit_date'];
		foreach($tmp as $okey => $oval){
			$deposit_date = $oval['deposit_date'];
			$billing_source_id = $oval['billing_source_id'];
			if(_BILLING_RESOURCE_F1_ID == $billing_source_id){
				$total_billing_f1 += 1;
			} else {
				$total_billing_cross += 1;
			}
			if($deposit_date > $last_sale_date) $last_sale_date = $deposit_date;
			$total_grands += $clsISO->processSmartNumber($oval['totalgrand']);
			if(!in_array($oval["staff_id"], $arr_users_sold)){
				$arr_users_sold[] = $oval["staff_id"];
			}
		}
		unset($tmp);
		$total_staffs_sold = @count($arr_users_sold);
	}
	$total_staffs_unsold = ($total_staffs - $total_staffs_sold);
	$arr_staffs_unsold = @array_diff($arr_staff_ids, $arr_users_sold);
	$arr_staffs_unsold = !empty($arr_staffs_unsold) ? array_values($arr_staffs_unsold) : [];
	$no_sale_days = ($last_sale_date > 0) ? $clsISO->getNumDayBetweenDate($last_sale_date, $current_now) : 0;
	#
	$html = '<div class="brief-item a4a bg-purple">
		<p class="fs-16 mb-1">Nhân sự</p>
		<h3 class="fs-4 mb-2">'.$total_staffs.'</h3>
		<small class="text-white">Có <strong>0</strong> người mới &amp; <strong>0</strong> đã nghỉ. 
			<!-- <a class="text-decoration-underline cursor-pointer">Xem ngay <i class="bx bx-link-external text-fs-12"></i></a> -->
		</small>
	</div>
	<div class="brief-item a1a bg-orange">
		<p class="fs-16 mb-1">Tra cứu</p>
		<h3 class="fs-4 mb-2">'.$clsISO->formatNumberToEasyRead($total_searchs).'</h3>
		<small class="text-white">Có <strong>'.$total_users_not_search.'</strong> Sale chưa tra cứu. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'search', 'list_staffs' => $arr_users_not_search)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>
	<div class="brief-item a2a bg-azure">
		<p class="fs-16 mb-1">Tiếp khách</p>
		<h3 class="fs-4 mb-2">'.$clsISO->formatNumberToEasyRead($total_shareds).' 
			<span class="text-fs-11">lượt & <strong class="text-warning">'.$total_guests.'</strong> khách</span>
		</h3>
		<small class="text-white">Có <strong>'.$total_staffs_unshare.'</strong> Sale chưa tiếp khách. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'client_meeting', 'list_staffs' => $arr_users_not_share)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>
	<div class="brief-item a3a bg-cyan">
		<p class="fs-16 mb-1">Giao dịch</p>
		<h3 class="fs-4 mb-2">'.$clsISO->formatNumberToEasyRead($total_billings).'
			<Span class="text-fs-11">Có <strong class="text-warning">'.$total_billing_f1.'</strong> ĐQ & <strong class="text-warning">'.$total_billing_cross.'</strong> quỹ chéo</span>
		</h3>
		<small class="text-white">Có <strong>'.$total_staffs_unsold.'</strong> Sale chưa có giao dịch. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'billing', 'list_staffs' => $arr_staffs_unsold)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>
	<div class="brief-item a4a bg-danger">
		<p class="fs-16 mb-1">Doanh số</p>
		<h3 class="fs-4 mb-2">'.$clsISO->shortNumber($total_grands).'</h3>
		<small class="text-white">Có <strong>'.$total_staffs_unsold.'</strong> Sale chưa có giao dịch. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'billing', 'list_staffs' => $arr_staffs_unsold)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>';	
	// Return
	echo json_encode(array(
		'html' => $html,
		'html_targets' => $html_targets,
		'title_page'	=>	$title_page
	)); die();
}
function default_report_dep_total(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn,$deviceType; 
	$clsLog = new Log();
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsProfile = new Profile();	
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$current_now = time();
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	// $date_type = Input::post('date_type', '_month');
	$title = ($month > 0) ? sprintf('Tháng %s/%s', $month, $year) : sprintf('Năm %s', $year);
	$department_id = (int) Input::post('department_id', 0);
	$department_id = ($department_id > 0) ? $department_id : _DEPARTMENT_SALE_ID;
	$list_staffs = $clsProfile->getProfileDep($department_id, 1, "active");
	$arr_staff_ids = !empty($list_staffs) ? @array_keys($list_staffs) : [];
	$total_staffs = count($arr_staff_ids);
	// Tra cứu
	$arr_users_search = $arr_users_share = $arr_users_sold = array();
	$total_searchs = $total_users_search = $total_users_not_search = 0;
	$sql_query = "`from_site`='_user'";
	if($month > 0){
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$sql_query.= " AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$my}'";
	} else {
		$sql_query.= " AND FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
	}
	$sql_query.= " AND `user_id` IN ('".implode('\',\'', $arr_staff_ids)."')";
	$tmp = $clsLog->getAll($sql_query, "user_id");
	if(!empty($tmp)){
		$total_searchs = count($tmp);
		foreach($tmp as $key => $val){
			if(!in_array($val['user_id'], $arr_users_search)){
				$arr_users_search[] = $val['user_id'];
			}
			unset($tmp);
		}
		$total_users_search = count($arr_users_search);
	}
	$total_users_not_search = $total_staffs - $total_users_search;
	$arr_users_not_search = @array_diff($arr_staff_ids, $arr_users_search);
	$arr_users_not_search = !empty($arr_users_not_search) ? array_values($arr_users_not_search) : [];
	// Tiếp khách
	$total_shareds = $total_users_share = $total_staffs_unshare = $total_guests = 0;
	$sql_query = "`share_type`='share'";
	if($month > 0){
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$sql_query.= " AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$my}'";
	} else {
		$sql_query.= " AND FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
	}
	$tmp = $clsShare->getAll($sql_query." AND `user_id` in (".implode(',',$arr_staff_ids).")", "`user_id`,`more_information`");
	if(!empty($tmp)){
		$total_shareds = count($tmp);
		foreach($tmp as $okey => $oval){
			$more_infor = $oval['more_information'];
			$more_infor = $clsISO->to_array_json($more_infor);
			$guest_count = (int) $core->get_field($more_infor, "guest_count", 0);
			$total_guests += $guest_count;
			if(!in_array($oval["user_id"], $arr_users_share)){
				$arr_users_share[] = $oval["user_id"];
			}
		}
		unset($tmp);
		$total_users_share = @count($arr_users_share);
	}
	$total_staffs_unshare = $total_staffs - $total_users_share;
	$arr_users_not_share = @array_diff($arr_staff_ids, $arr_users_share);
	$arr_users_not_share = !empty($arr_users_not_share) ? array_values($arr_users_not_share) : [];
	// Giao dịch
	$total_billing_f1 = $total_billing_cross = 0;
	$total_billings = $total_grands = $total_staffs_sold = $total_staffs_unsold = 0;
	$sql_query = "`is_trash`=0 AND `is_cancel`=0";
	if($month > 0){
		$my = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		$sql_query.= " AND FROM_UNIXTIME(`deposit_date`,'%Y-%m')='{$my}'";
		$mkt_cond = "`t1`.`month`='{$my}'";
		$sql_mkt_cond = "`t2`.`month`='{$my}'";
	} else {
		$sql_query.= " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$arr_months = array();
		$max_month = ($year == date('Y')) ? date('n') : 12;
		for($month=1; $month <= $max_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$mkt_cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$sql_mkt_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	}
	$last_sale_date = 0;
	$sql_query.= " AND `staff_id` in (".implode(',',$arr_staff_ids).")";
	$tmp = $clsBilling->getAll($sql_query." ORDER BY `deposit_date` DESC", "`staff_id`,`totalgrand`,`deposit_date`,`billing_source_id`");
	if(!empty($tmp)){
		$total_billings = count($tmp);
		$last_sale_date = $tmp[0]['deposit_date'];
		foreach($tmp as $okey => $oval){
			$deposit_date = $oval['deposit_date'];
			$billing_source_id = $oval['billing_source_id'];
			if(_BILLING_RESOURCE_F1_ID == $billing_source_id){
				$total_billing_f1 += 1;
			} else {
				$total_billing_cross += 1;
			}
			if($deposit_date > $last_sale_date) $last_sale_date = $deposit_date;
			$total_grands += $clsISO->processSmartNumber($oval['totalgrand']);
			if(!in_array($oval["staff_id"], $arr_users_sold)){
				$arr_users_sold[] = $oval["staff_id"];
			}
		}
		unset($tmp);
		$total_staffs_sold = @count($arr_users_sold);
	}
	$total_staffs_unsold = ($total_staffs - $total_staffs_sold);
	$arr_staffs_unsold = @array_diff($arr_staff_ids, $arr_users_sold);
	$arr_staffs_unsold = !empty($arr_staffs_unsold) ? array_values($arr_staffs_unsold) : [];
	$no_sale_days = ($last_sale_date > 0) ? $clsISO->getNumDayBetweenDate($last_sale_date, $current_now) : 0;
	#
	$mkt_cond.= " AND `t1`.`staff_id` IN ('".implode('\',\'', $arr_staff_ids)."')";
	$sql_mkt_cond.= " AND `t2`.`staff_id` IN ('".implode('\',\'', $arr_staff_ids)."')";
	$user_percent = $total_amounts = $total_budgets = $total_company_support_amounts = $total_sale_support_amounts = 0;
	$field = "SUM(`t1`.`total_budget`) AS `total_budgets`, SUM(`t2`.`amount`) AS `total_amounts`, SUM(`t2`.`company_support_amount`) AS `total_company_support_amounts`, SUM(`t2`.`sale_support_amount`) AS `total_sale_support_amounts`";
	$tmp = $dbconn->getRow("SELECT {$field} FROM {$clsMarketingBudgetRegister->tbl} AS `t1` LEFT JOIN {$clsMarketingSpending->tbl} AS `t2` ON `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` AND {$sql_mkt_cond} WHERE {$mkt_cond}");
	if(!empty($tmp)){
		$total_amounts = $tmp['total_amounts'];
		$total_budgets = $tmp['total_budgets'];
		$total_company_support_amounts = $tmp['total_company_support_amounts'];
		$total_sale_support_amounts = $tmp['total_sale_support_amounts'];
		$user_percent = round($total_amounts / $total_budgets, 2) * 100;
	}
	$html = '<div class="brief-item a4a bg-solid">
		<p class="fs-16 mb-1">Ngân sách Marketing</p>
		<h3 class="fs-4 mb-2">
			<span class="text-fs-12 text-white me-1">Dự kiến</span>
			'.$clsISO->shortNumber($total_budgets, 2).'
		</h3>
		<small class="text-white">Thực tế <strong>'.$clsISO->shortNumber($total_amounts,2).'</strong>, 
			Công ty hỗ trợ <strong>'.$clsISO->shortNumber($total_company_support_amounts,2).'</strong></small>
	</div>
	<div class="brief-item a4a bg-purple">
		<p class="fs-16 mb-1">Nhân sự</p>
		<h3 class="fs-4 mb-2">'.$total_staffs.'</h3>
		<small class="text-white">Có <strong>0</strong> người mới &amp; <strong>0</strong> đã nghỉ. 
			<!-- <a class="text-decoration-underline cursor-pointer">Xem ngay <i class="bx bx-link-external text-fs-12"></i></a> -->
		</small>
	</div>
	<div class="brief-item a1a bg-orange">
		<p class="fs-16 mb-1">Tra cứu</p>
		<h3 class="fs-4 mb-2">'.$clsISO->formatNumberToEasyRead($total_searchs).'</h3>
		<small class="text-white">Có <strong>'.$total_users_not_search.'</strong> Sale chưa tra cứu. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'search', 'list_staffs' => $arr_users_not_search)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>
	<div class="brief-item a2a bg-azure">
		<p class="fs-16 mb-1">Tiếp khách</p>
		<h3 class="fs-4 mb-2">'.$clsISO->formatNumberToEasyRead($total_shareds).' 
			<span class="text-fs-11">lượt & <strong class="text-warning">'.$total_guests.'</strong> khách</span>
		</h3>
		<small class="text-white">Có <strong>'.$total_staffs_unshare.'</strong> Sale chưa tiếp khách. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'client_meeting', 'list_staffs' => $arr_users_not_share)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>
	<div class="brief-item a3a bg-cyan">
		<p class="fs-16 mb-1">Giao dịch</p>
		<h3 class="fs-4 mb-2">'.$clsISO->formatNumberToEasyRead($total_billings).'
			<Span class="text-fs-11">Có <strong class="text-warning">'.$total_billing_f1.'</strong> ĐQ & <strong class="text-warning">'.$total_billing_cross.'</strong> quỹ chéo</span>
		</h3>
		<small class="text-white">Có <strong>'.$total_staffs_unsold.'</strong> Sale chưa có giao dịch. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'billing', 'list_staffs' => $arr_staffs_unsold)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>
	<div class="brief-item a4a bg-danger">
		<p class="fs-16 mb-1">Doanh số</p>
		<h3 class="fs-4 mb-2">'.$clsISO->shortNumber($total_grands).'</h3>
		<small class="text-white">Có <strong>'.$total_staffs_unsold.'</strong> Sale chưa có giao dịch. 
			<a class="text-decoration-underline cursor-pointer" onClick="$Core.report.view_staff(this, event)" data-options=\''.json_encode(array('holderG' => 'billing', 'list_staffs' => $arr_staffs_unsold)).'\'>Xem ngay <i class="bx bx-link-external text-fs-12"></i></a>
		</small>
	</div>';	
	if($department_id == _DEPARTMENT_SALE_ID){
		$target_trans = 2000;
		$target_amounts = 13000000000000;
		$target_staffs = 500;
	} else {
		$target_staffs = 100;
		$target_trans = $target_amounts = 0;
		$more_information = $clsProperty->getOneField('more_information', $department_id);
		$more_information = $clsISO->to_array_json($more_information);
		$target_sales = $core->get_field($more_information, "target_sales", []);
		if($month > 0){
			$key = sprintf('%s_%s', $month, $year);
			$target_trans = $core->get_number_field($target_sales[$key], "quantity", 0);
			$target_amounts = $core->get_money_field($target_sales[$key], "amount", 0);
		} else {
			$target_trans = $target_amounts = 0;
			for($month=1; $month<=12; $month++){
				$key = sprintf('%s_%s', $month, $year);
				$target_trans += $core->get_number_field($target_sales[$key], "quantity", 0);
				$target_amounts += $core->get_money_field($target_sales[$key], "amount", 0);
			}
		}
	}
	$target_trans_percent = Helper::percent($total_billings, $target_trans);
	$target_revenue_percent = Helper::percent($total_grands, $target_amounts);
	$target_staffs_percent = Helper::percent($total_staffs, $target_staffs);
	$html_targets = ($no_sale_days > 1 ? '<div class="alert alert-danger text-center mb-2">
		<i class=\'bx bx-alarm-exclamation\'></i> Đã <strong>'.$no_sale_days.'</strong> ngày trôi qua 
			<strong>'.$clsProperty->getTitle($department_id).'</strong> chưa có giao dịch!
	</div>' : '').'
	<div class="form-row">
		<div class="col-12 col-md-4 mb-2 mb-lg-0">
			<div class="p-3 rounded-3 bg-label-success">
				<div class="d-flex gap-2 align-items-center mb-1">
					<i class=\'bx bx-check-circle text-success text-fs-40\'></i>
					<div class="d-flex gap-1 flex-column" style="width:calc(100% - 40px)">
						<small>Đã hoàn thành <strong>'.$target_trans_percent.'</strong>% KPI trong '.$title.'</small>
						<div class="progress">
							<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
								role="progressbar" style="width: '.$target_trans_percent.'%"></div>
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center text-fs-12 justify-content-between">
					<span class="text-muted">Số giao dịch hiện tại: <strong>'.$total_billings.'</strong></span>
					<span>Còn thiếu <strong class="text-warning">'.($target_trans - $total_billings).' GD</strong></span>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4 mb-2 mb-lg-0">
			<div class="p-3 rounded-3 bg-label-info text-dard">
				<div class="d-flex gap-2 align-items-center mb-1">
					<i class=\'bx bx-dollar-circle text-danger text-fs-40\'></i>
					<div class="d-flex gap-1 flex-column" style="width:calc(100% - 40px)">
						<small>Đã hoàn thành <strong>'.$target_revenue_percent.'%</strong> KPI trong '.$title.'</small>
						<div class="progress">
							<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
								role="progressbar" style="width:'.$target_revenue_percent.'%"></div>
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center text-fs-12 justify-content-between">
					<span class="text-muted">Doanh số hiện tại: <strong>'.$clsISO->shortNumber($total_grands).'</strong></span>
					<span>Còn thiếu <strong class="text-warning">'.($clsISO->shortNumber($target_amounts - $total_grands)).'</strong></span>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-4">
			<div class="p-3 rounded-3 bg-label-primary">
				<div class="d-flex gap-2 align-items-center mb-1">
					<i class=\'bx bx-user-circle text-primary text-fs-40\'></i>
					<div class="d-flex gap-1 flex-column" style="width:calc(100% - 40px)">
						<small>Đã hoàn thành <strong>'.$target_staffs_percent.'</strong> KPI trong '.$title.'</small>
						<div class="progress">
							<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
								role="progressbar" style="width:'.$target_staffs_percent.'%"></div>
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center text-fs-12 justify-content-between">
					<span class="text-muted">Số nhân sự hiện tại: <strong>'.$total_staffs.'</strong></span>
					<span>Còn thiếu <strong class="text-warning">'.($target_staffs - $total_staffs).'</strong> nhân sự</span>
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'html_targets' => $html_targets,
		'title_page'	=>	$title_page
	)); die();
}
function default_load_billing_chart(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn; 
	$clsLog = new Log();
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '7days');
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	
	$group_id = (int) Input::post('group_id', 0);
	$group_id = $group_id ?: _PROFILE_DEFAULT_GROUP_ID;
	$oneGroup = $clsGroupProfile->getOne($group_id);
	$list_profile_id = $oneGroup['list_profile_id'];
	$arr_staff_ids = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
	###
	$list_staffs = array();
	$arr_staffs_cached = $clsProfile->getProfileCached();
	if(!empty($arr_staffs_cached)){
		foreach($arr_staffs_cached as $key => $val){
			if(in_array($val[$clsProfile->pkey], $arr_staff_ids)){
				$list_staffs[$key] = $val;
				$list_staffs[$key]['total_billings'] = 0;
				$list_staffs[$key]['total_sales'] = 0;
			}
		}
	}
	#
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `staff_id` IN ('".implode('\',\'', $arr_staff_ids)."')";	
	$cond.= " AND (`deposit_date` BETWEEN {$start_time} AND {$end_time})";
	$list_billings = $clsBilling->getAll($cond, "`totalgrand`,`staff_id`");
	if(!empty($list_billings)){
		foreach($list_billings as $key => $val){
			$staff_id = (int) $val['staff_id'];
			$totalgrand = $clsISO->processSmartNumber($val['totalgrand']);
			if(isset($list_staffs[$staff_id])){
				$list_staffs[$staff_id]['total_billings'] += 1;
				$list_staffs[$staff_id]['total_sales'] += $totalgrand;
			}
		}
	}
	###
	$html = '<div class="chartContainer p-2 rounded-1">
		<div id="'.$uid.'" class="w-100 h-px-300"></div>
	</div>';
	###
	$data = $dataPoints = $dataTotalPoints = $barChartData = array(); 
	$barChartData['zoomEnabled'] = true;
	// $barChartData['animationEnabled'] = true;
	// $barChartData['dataPointMaxWidth'] = 20;
	$barChartData['axisX'] = array(
		'interval' => 1
	);
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'lineColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'titleFontColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	$barChartData['axisY2'][] = array(
		'title' => 'SL giao dịch(GD)',
		'titleFontColor' => '#C00000',
		'labelFontColor' => '#C00000',
		'titleFontSize' => '16',
		'lineColor' => '#C00000',
		'tickColor' => '#C00000',
		'maximum' => 50
	);
	$arr_order = @array_column($list_staffs, 'total_sales');
	@array_multisort($arr_order, SORT_DESC, $list_staffs);
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		$total_sales = $val['total_sales'];
		$total_billings = $val['total_billings'];
		$dataPoints[] = array(
			'label'	=> $clsProfile->getLastName($staff_id, $val, false),
			'y'	=> $total_sales*1,
			'indexLabel' => ($total_sales > 0) ? $clsISO->shortNumber($total_sales) : ""
		);
		$dataTotalPoints[] = array(
			'label'	=> $clsProfile->getLastName($staff_id, $val, false),
			'y' => $total_billings*1,
			'indexLabel' => (string) $total_billings
		);
	}
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"   => true,
			"dataPoints"    => $dataPoints
		), array(
			"type"  => "column",
			"indexLabel" => "{y} GD",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataTotalPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'draw_chart' => 1,
		'multi_chart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function default_load_group_month_chart(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id;
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	
	$uid = $clsISO->getUniqid();
	$current_year = date('Y'); // Year
	$current_month = date('n'); // Tháng
	$year = Input::post('year', date('Y'));
	$group_id = (int) Input::post('group_id', _PROFILE_DEFAULT_GROUP_ID);
	$search = Input::post('search', "");
	$oneGroup = $clsGroupProfile->getOne($group_id);
	$list_profile_id = $oneGroup['list_profile_id'];
	$list_staff_ids = $clsISO->getArrayByTextSlash($list_profile_id);
	if($search == "report_dep") {
		$department_id = (int) Input::post('department_id', 0);
		if(!empty($department_id)) {			
			$oneDep = $clsProperty->getArraySearchByKey("_DEPARTMENT",$department_id);
			$more_dep = !empty($oneDep["more_information"]) ? $oneDep["more_information"] : [];
			if(!empty($more_dep["is_business_area"])) {
				$title_page = "Báo cáo ".$oneDep["title"];
			}else{
				$title_page = "Báo cáo phòng kinh doanh ".$oneDep["title"];
			}			
		}
		$department_id = !empty($department_id) ? $department_id : _DEPARTMENT_SALE_ID;
	}else{
		$department_id = (int) Input::get('department_id', 0);
	}
	if(!empty($department_id)) {
		$list_staff = $clsProfile->getProfileDep($department_id, 1, "active");
		$list_staff_ids = array_keys($list_staff);
	}
	$html = '<div class="chartContainer p-2 rounded-1">
		<div id="'.$uid.'" class="w-100 h-px-300"></div>
	</div>';
	###
	$data = $dataPoints = $barChartData = array();
	$barChartData['zoomEnabled'] = true;
	$barChartData['animationEnabled'] = true;
	$data['axisY'] = array(
		'title' => 'Tổng số giao dịch',
		'titleFontColor' => '#333',
		// 'lineColor' => '#333',
		// 'labelFontColor' => '#C00000',
		// 'tickColor' => '#333',
	);	
	$data['axisX'] = array(
		'interval' => 1
	);
	for($month=1; $month<=12; $month++){
		$dmy = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		if($year == $current_year && $month > $current_month){
			$total = 0;
		} else {
			$total = $clsBilling->countItem("`is_trash`=0 AND `is_cancel`='0' AND `is_alliance`=0 AND `staff_id` in (".implode(',', $list_staff_ids).") AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$dmy}'");
		}
		$dataPoints[] = array(
			'label'	=> sprintf('T%s', $month),
			'y'	=> $total*1,
			'indexLabel' => (string) $total
		);
	}
	###
	$data['type'] = 'column';
	$data['indexLabel'] = '{y}';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'draw_chart' => "1",
		'multi_chart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function default_load_calendar(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	
	$holderG = Input::post('holderG','_desktop');
	$typeHoldder = Input::post('typeHoldder','_all');
	if($holderG=='_desktop'){
		$html = '<style type="text/css">
			.fc-event-custom{ text-align:center;}
			.fc-event-skin{background-color:transparent !important; border:0 !important}
		</style>';
	}else{
		$html = '<style type="text/css">
			.fc-widget-header{height:30px; line-height:30px;}
			.fc-event-skin{background-color:transparent !important; border:0 !important}
			.fc-event-custom{ height:90px; position:relative;}
			.fc-event-list{ position:absolute; left:0px; bottom:5px;}
			.event{display:inline-block;width:9pt; height:9pt;margin:0 2px 2px 0;}
			.event{box-shadow:inset 0 0 5px 0 rgba(0,0,0,.4);border-radius:8px;border:1px solid #fff;}
			.event-lead-link{color: #009926;}
			.fc-event-custom>.badge{line-height:12px;}
			@media (min-width:768px){
				.btn-group-toolbar{
					margin-top:-45px;
				}
			}
		</style>';
	}
	if($holderG=='_tablist'){
		$html .= '<div class="btn-group pull-right btn-group-toolbar">
			<button type="button" class="btn btn-sm js_filter-calendar btn-'.($typeHoldder=='_all'?'primary':'default').'" v="_all">'.$core->makeIcon('users','Tất cả').'</button>
			<button type="button" class="btn btn-sm js_filter-calendar btn-'.($typeHoldder=='_me'?'primary':'default').'" v="_me">'.$core->makeIcon('user', 'Chỉ tôi').'</button>
		</div>
		<div class="clearfix"></div>';
	}
	$html .= '<div id="calendar'.($holderG=='_desktop'?'_desktop':'').'" class="simple-calendar"></div>';
	if($holderG=='_tablist'){
		$html .= '<div class="clearfix mt-2"></div>
		<div class="d-flex align-items-center pull-left">
			'.($core->makeIcon('calendar mr-2')).' <a href="javascript:void(0);" class="text-link">
			<u>'.$core->get_Lang('Add to your Google Calendar').'</u></a>
		</div>';
	}
	echo $html; die();
}
function default_load_cell_calendar(){
	global $oSmarty,$smarty,$core,$clsISO,$dbconn,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	#- Params
	$start = (int) Input::get('start',0);
	$end = (int) Input::get('end',0);
	$tp = Input::get('tp','follow-ups');
	$group_id = (int) Input::get('group_id',0);
	$staff_id = (int) Input::get('staff_id',0);
	
	$cond = "`is_trash`=0";
	if($tp=='customer'){
		$field = "reg_date";
		if($staff_id > 0) {
			$cond.= " and `admin_id`='{$staff_id}'";
		}elseif($group_id > 0){
			$oneGroup = $clsGroupProfile->getOne($group_id,$clsGroupProfile->pkey.',list_profile_id');
			if(!empty($oneGroup)) {
				$list_profile_id = (!empty($oneGroup['list_profile_id'])) ? $clsISO->getArrayByTextSlash($oneGroup['list_profile_id']) : array();
				if(!empty($list_profile_id)) {
					$cond.= " and `admin_id` IN (".implode(",",$list_profile_id).")";
				}
			}
		}
		$clsClassTable = $clsCustomer;
	} else if($tp=='follow-ups'){
		$field = "date_id";		
		if($staff_id > 0) {
			$cond.= " and `admin_id`='{$staff_id}'";
		}elseif($group_id > 0){
			$oneGroup = $clsGroupProfile->getOne($group_id,$clsGroupProfile->pkey.',list_profile_id');
			if(!empty($oneGroup)) {
				$list_profile_id = (!empty($oneGroup['list_profile_id'])) ? $clsISO->getArrayByTextSlash($oneGroup['list_profile_id']) : array();
				if(!empty($list_profile_id)) {
					$cond.= " and `admin_id` IN (".implode(",",$list_profile_id).")";
				}
			}
		}
		
		$clsClassTable = $clsFollowUp;
	}
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$date_id = date('d-m-Y',$i);
		$total_record = $clsClassTable->countItem("{$cond} and FROM_UNIXTIME({$field},'%d-%m-%Y')='{$date_id}'");
		$results[] = array(
			'tp' => $tp,
			'title' => PAGE_NAME,
			'number' => $total_record,
			'start' => date('Y-m-d H:i:s',$i)
		);
	}
	// Return
	echo @json_encode($results);
	die();
}
function default_top_10(){
	global $smarty,$assign_list,$core,$clsISO,$oneProfile,$profile_id,$dbconn,
		$title_page,$description_page,$keyword_page; 
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$assign_list["clsProperty"] = $clsProperty;
	if(!$clsISO->checkPermissionGroup('DIRECTOR')){
		header('Location:/');
		exit();
	}
	$list_billing_types = $clsProperty->getCacheItems('_BILLING_TYPE');
	if(!empty($list_billing_types)){
		$arr_property_cached = array();
		foreach($list_billing_types as $key => $val){
			$billing_type = $val[$clsProperty->pkey];
			$field = "`t1`.`staff_id`,`t2`.`full_name`,`t2`.`department_id`,
			count(1) as `total_billings`,SUM(`t1`.`totalgrand`) as `total_revenue`";
			$list_staffs = $dbconn->getAll("select {$field} from {$clsBilling->tbl} as `t1` 
				inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 
				where `t1`.`is_trash`=0 and `t1`.`is_cancel`='0' and `t1`.`staff_id`<>'"._PROFILE_PARTNER_ID."' 
				and `t1`.`is_alliance`=0 and `t2`.`is_trash`=0 and `t2`.`status_id`='"._STATUS_STAFF_ON_ID."' and FROM_UNIXTIME(`t1`.`deposit_date`,'%Y')='".date('Y')."' and `t1`.`billing_source_id`='"._BILLING_RESOURCE_F1_ID."' and `t1`.`billing_type`='{$billing_type}' 
				GROUP BY `t1`.`staff_id` ORDER BY `total_revenue` DESC limit 0,10");
			
			$total_grands = 0;
			if(!empty($list_staffs)){
				foreach($list_staffs as $okey => $oval){
					$department_id = $oval['department_id'];
					if(!isset($arr_property_cached[$department_id])){
						$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
					}
					$list_staffs[$okey]['department_name'] = $arr_property_cached[$department_id];
					$total_grands += $clsISO->convertToNumber($oval['total_revenue']);
				}
			}
			$list_billing_types[$key]['total_grands'] = $total_grands;
			$list_billing_types[$key]['list_staffs'] = $list_staffs;
		}
		$arr_order = @array_column($list_billing_types, 'total_grands');
		@array_multisort($arr_order, SORT_DESC, $list_billing_types);
	}
	$assign_list["list_billing_types"] = $list_billing_types;
	
	// $clsISO->print_pre($list_billing_types); die();
	/*=============Title & Description Page==================*/
	$title_page = 'Top 10 Sale - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_request_ptg(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsRequestPTG = new RequestPTG();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$assign_list['clsRequestPTG'] = $clsRequestPTG;
	$assign_list['clsStock'] = $clsStock;
	$assign_list['clsProfile'] = $clsProfile;
	$status = Input::get("status","");
	$cond = $cond_all = "1=1";
	if($status != "") {
		$cond .= " AND `t1`.`status_id` = '{$status}'";
		$cond_all .= " AND `status_id` = '{$status}'";
	}
	#
	$lst_success = $clsRequestPTG->getAll("`status_id` = '1'","reg_date,upd_date");
	$total_success = count($lst_success);
	$total_time_feedback = 0;
	foreach ($lst_success as $key => $val){
		$time = $val["upd_date"] - $val['reg_date'];
		$total_time_feedback += $time;
	}
	$avg_time_feedback = 0;
	if($total_success > 0){
		$avg_time_feedback = $clsISO->getTime(round($total_time_feedback/$total_success));
	}
	
	$total_pendding = $clsRequestPTG->countItem("`status_id` = '0'");
	$total_record = $clsRequestPTG->countItem("1=1");
	$assign_list['avg_time_feedback'] = $avg_time_feedback;	
	$assign_list['total_record'] = $total_record;	
	$assign_list['total_pendding'] = $total_pendding;	
	$assign_list['total_success'] = $total_record - $total_pendding;	
	$assign_list['status'] = $status;	
	#
	#
	
	/*=============Title & Description Page==================*/
	$title_page = 'Báo phản hồi yêu cầu phiếu tính giá - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_requestchart(){
	global $smarty,$assign_list,$adminid,$core,$deviceType,$clsISO,$dbconn;
	$clsRequestPTG = new RequestPTG();
	$clsProfile = new Profile();
	$lstAdmin = $clsProfile->getAll("role_id='"._ROLE_STAFF_ADMIN."' and `status_id` <> '"._STATUS_STAFF_OFF_ID."'",$clsProfile->pkey.",full_name");
	$arr_admin = [];
	foreach($lstAdmin  as $key => $value) {
		$arr_admin[$value[$clsProfile->pkey]] = $value;		
	}
	###
	$uid = $clsISO->getUniqid();
	$html = '<div id="'.$uid.'" class="chartContainer w-100" style="height:300px"></div>';
	
	$barChartData = array();
	$data = array();
	$data = [];
	$sql_string= "`status_id`='1'";
	$dataPoints = $dataTotal = [];
	$ii = 0;
	foreach ($arr_admin as $key => $val) {
		$lstRequest = $clsRequestPTG->getAll($sql_string." AND `user_updated_id`='".$val[$clsProfile->pkey]."'", "reg_date,upd_date");
		$time_min = $time_max = 0;
		$total = count($lstRequest);
		if(!empty($lstRequest)){	
			foreach($lstRequest as $k_request => $v_request) { 
				$time = $v_request['upd_date'] -  $v_request['reg_date'];
				if($time_min == 0){
					$time_min = $time;
				}else{
					$time_min = ($time < $time_min) ? $time : $time_min;
				}
				$time_max = ($time > $time_max) ? $time : $time_max;
			}
		}		
		$dataPoints[] = [
			"label"	=>	$val['full_name'],
			"type"	=>	"Phản hồi",
			"y"		=>	array($time_min/100,$time_max/100),
			"color"	=>	"#4f81bc",
			"label_tooltip" =>	$clsISO->getTime($time_min)." đến ".$clsISO->getTime($time_max)
		];
		$dataTotal[] = [
			"label"	=>	$val['full_name'],
			"type"	=>	"Phản hồi",
			"y"		=>	$total,
			"color"	=>	"#c0504e",
			"indexLabel" =>	$total." lần",
			"label_tooltip" =>	$total." lần",
		];
	}
	$data = [
		[
			"type"	=> "rangeColumn",
			"name"	=> "Thời gian phản hồi",
			"showInLegend" => true,
			"dataPoints" => $dataPoints,		
		],
		[
			"type"	=> "spline",
			"name"	=> "Số lần phản hồi",
			"showInLegend" => true,
			"dataPoints" => $dataTotal,
		]
	];
//	$clsISO->print_pre($data);die;
	$barChartData['animationEnabled'] = true;
	$barChartData['axisY'] = [
		"title"	=>	"m2",
		"includeZero"	=>	true
	];
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback'	=>	'  var chart = new CanvasJS.Chart("'.$uid.'", {
			title: {
				text: ""
			},
			axisX: {
				title: "",
				interval: 1,
				labelFontSize: 11,
				includeZero: true
			},
			axisY: {
				minimum: 0,
				maximum: 100,
				labelFormatter: function(e) {
				console.log(e)
					return "";
				}
			},
			toolTip:{
				contentFormatter: function ( e ) {
					return e.entries[0].dataPoint.type+": "+e.entries[0].dataPoint.label_tooltip;  
				}  
			},
			data: '.json_encode($data).'
		});
    chart.render();'	
	));
}
function default_report_activity_log(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsActivityLog = new ActivityLog();
	###
	###
	$start_date = strtotime("-7 days");
	$start_date = date('Y-m-d', $start_date);
	$end_date = date('Y-m-d', time());
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	###
//	$clsActivityLog->setDeBug(1);
	$listActivityLog = $clsActivityLog->getAll("1=1 ORDER BY `reg_date` DESC","profile_id");
	$arr_cache_profile = $arr_cache = array();
	foreach($listActivityLog as $key => $val) {
		if(!isset($arr_cache_profile[$val['profile_id']])) {
			$arr_cache_profile[$val['profile_id']] = [
				"profile_id"	=>	$val['profile_id'],
				"full_name"		=>	$clsProfile->getFullName($val['profile_id'])
			];
		}
	}
	$assign_list["listActivityLog"] = $listActivityLog;
	$assign_list["arr_cache_profile"] = $arr_cache_profile;
//	$clsISO->print_pre($listActivityLog);die;
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[]= $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách log hệ thống';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_activity_log(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$clsConfiguration,$clsISO,$deviceType,$profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsActivityLog = new ActivityLog();
	###
	$_from = Input::post("_from","");
	$tbl = Input::post("tbl","");
	$profile_id = (int)Input::post("profile_id",0);
	$start_date = Input::post("start_date","");
	$end_date = Input::post("end_date","");
	$start_date = !empty($start_date) ? strtotime($start_date) : 0;
	$end_date = !empty($end_date) ? strtotime($end_date." 23:59") : 0;
	###
	$cond = $cnd = "1=1";
	if(!empty($tbl)) {
		$cond.= " and `tbl`='{$tbl}'";
	}
	if($start_date > 0 && $end_date == 0) {
		$cnd .= " AND `reg_date` >= '{$start_date}'";
		$cond .= " AND `reg_date` >= '{$start_date}'";
	}else if($start_date == 0 && $end_date > 0) {
		$cnd .= " AND `reg_date` <= '{$start_date}'";
		$cond .= " AND `reg_date` <= '{$start_date}'";
	}else if($start_date > 0 && $end_date > 0) {
		$cnd .= " AND `reg_date` BETWEEN '{$start_date}' AND '{$end_date}'";
		$cond .= " AND `reg_date` BETWEEN '{$start_date}' AND '{$end_date}'";
	}
	if(!empty($profile_id)) {
		$cnd .= " AND `profile_id`='{$profile_id}'";
		$cond .= " AND `profile_id`='{$profile_id}'";
	}
	if(!empty($_from)) {
		$cnd .= " AND `_from`='{$_from}'";
		$cond .= " AND `_from`='{$_from}'";
	}
	$html_options = '<option value="">Chọn loại</option>';
	$all = $clsActivityLog->getAll($cnd, "distinct(`tbl`),`title`");
	if(!empty($all)){
		foreach($all as $key => $val){
			$selected = ($val['tbl']==$tbl) ? " selected" : "";
			$html_options.= sprintf('<option value="%s"%s>%s</option>', $val['tbl'], $selected, $val['title']);
		}
		unset($all);
	}
	//	$clsActivityLog->setDeBug(1);
	$current_page = Input::post('page',1);
	$per_page  = Input::post('per_page',50);
	$total_record = $clsActivityLog->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	###
	$html = "";
	$list_logs = $clsActivityLog->getAll($cond." ORDER BY `reg_date` DESC".$limitCond);
	if(!empty($list_logs)) { $ii = 0;
		foreach($list_logs as $key => $val) {
			if($val["_from"] == "admin") {
				$class = "bg-label-primary";
				$list_logs[$key]["_from"] = "AD";
				$list_logs[$key]["_class"] = $class;
			}else{
				$class = "bg-label-danger";
				$list_logs[$key]["_from"] = "FR";
				$list_logs[$key]["_class"] = $class;
			}
		}
		$stt = ($current_page -1) * $per_page;
		foreach($list_logs as $key => $value){
			$html.= '<tr> 
				<td class="align-center text-nowrap text-center">'.($ii+1+$stt).'</td>
				<td class="align-center text-nowrap text-left">
					<span class="badge '.$value['_class'].'">'.$value["_from"].'</span> '.$value["title"].' 
				</td>
				<td class="align-center text-nowrap text-left">'.$value["content"].'</td>
			</tr>';
			++$ii;
		}
	}else{
		$html .= '<tr>
			<td class="text-center" colspan="3">Không tìm thấy kết quả</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'html_options' => $html_options,
		'per_page' => $per_page
	));
}
function default_report_price_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProperty = new Property();
	###
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='"._PROJECT_DEF_ID."'", $field);
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[]= $i;
	}
	$assign_list["list_blocks"] = $list_blocks;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo thay đổi giá';
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_price_stock(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$deviceType;
	global $profile_id, $oneProfile;
	$clsLog = new Log();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProfileLog = new ProfileLog();
	$clsStock = new Stock();
	##
	$cond .= "`status_id` <> '"._STOCK_STATUS_SOLD_ID."' AND `logs` <> ''";
	$order_by = " ORDER BY `upd_date` DESC ";
	
	$keyword = Input::post("keyword","");
	$project_id = (int)Input::post("project_id",0);
	$blocks_ids = Input::post("blocks_ids",array());
	$building_ids = Input::post("building_ids",array());
	if(!empty($keyword)) {
		$cond .= " AND `ms_code` LIKE '%{$keyword}%'";
	}
	if(!empty($project_id)) {
		$cond .= " AND `project_id` ='{$project_id}'";
	}
	if(!empty($blocks_ids)) {
		$cond .= " AND `block_id` IN (".implode(',',$blocks_ids).")";
	}
	if(!empty($building_ids)) {
		$cond .= " AND `building_id` IN (".implode(',',$building_ids).")";
	}
//	$clsStock->setDeBug(1);
	$lstItem = $clsStock->getAll($cond.$order_by,$clsStock->pkey.",ms_code,logs");
//	$clsISO->print_pre($lstItem);die;
	$arr_cache_profile = array();
	foreach($lstItem as $k => $val) {
		$list_logs = $clsISO->to_array_json($val['logs']);
		###
		$rowspan = 1;
		if(!empty($list_logs)){
			$list_dates = array();
			foreach($list_logs as $key => $val){
				$field = $val['field'];
				$from_value = (int)$clsISO->processSmartNumber($val["from_value"]);
				$from_value = $clsISO->convertPriceShortToFull($from_value);
				$to_value = (int)$clsISO->processSmartNumber($val["to_value"]);
				$to_value = $clsISO->convertPriceShortToFull($to_value);
				if($field!='total_price_vat' || ($field == 'total_price_vat' && $from_value == $to_value)){
					unset($list_logs[$key]);
				}
			}
			$checkExist = 0;
			foreach($list_logs as $key => $val){
				$from_value = (int)$clsISO->processSmartNumber($val["from_value"]);
				$from_value = $clsISO->convertPriceShortToFull($from_value);
				$to_value = (int)$clsISO->processSmartNumber($val["to_value"]);
				$to_value = $clsISO->convertPriceShortToFull($to_value);
				$list_logs[$key]["reg_date"] = date('d/m/Y H:i', $val['reg_date']);
				$list_logs[$key]["from_value"] = $clsISO->convertToNumber($from_value);
				$list_logs[$key]["to_value"] = $clsISO->convertToNumber($to_value);
				if(!isset($arr_cache_profile[$val['user_id']])) {
					$arr_cache_profile[$val['user_id']] = $clsProfile->getFullName($val['user_id']);
				}
				$list_logs[$key]["full_name"] = $arr_cache_profile[$val['user_id']];
			}
//			$clsISO->print_pre($list_logs);die;
			$lstItem[$k]['list_logs'] =$list_logs;
			$rowspan = count($list_logs) + 1;
			
		}
		unset($lstItem[$k]['logs']);
		$lstItem[$k]['rowspan'] = $rowspan;
		
		if(count($list_logs) == 0) {
			unset($lstItem[$k]);
		}
		
	}
//	$clsISO->print_pre($lstItem);die;
//	$smarty->assign("lstItem",$lstItem);
	$assign_list["lstItem"] = $lstItem;
	$html = $core->build("_ajax.load_report_price_stock.tpl");
	// Return
	echo json_encode(array(
		'html' => $html
	));
}
function default_top_sales(){
	global $smarty,$assign_list,$core,$clsISO,$oneProfile,$profile_id,$dbconn,$title_page,$description_page,$keyword_page; 
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$assign_list["clsProperty"] = $clsProperty;
	if(!$clsISO->checkPermissionGroup('DIRECTOR')){
		header('Location:/');
		exit();
	}
	$list_time_points = array(
		'1_month' => array(
			'title' => '1 tháng',
			'list_staffs' => []
		), '2_months' => array(
			'title' => '2 tháng',
			'list_staffs' => []
		), '3_months' => array(
			'title' => '3 tháng',
			'list_staffs' => []
		), '6_months' => array(
			'title' => '6 tháng',
			'list_staffs' => []
		),
	);
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	$assign_list["list_time_points"] = $list_time_points;
	/*=============Title & Description Page==================*/
	$title_page = 'Sales chưa có giao dịch - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_top_sales(){
	global $smarty,$assign_list,$core,$clsISO,$oneProfile,$profile_id,$dbconn,$title_page,$description_page,$keyword_page; 
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	
	$list_time_points = array(
		'1_month' => array(
			'title' => '1 tháng',
			'list_staffs' => []
		), '2_months' => array(
			'title' => '2 tháng',
			'list_staffs' => []
		), '3_months' => array(
			'title' => '3 tháng',
			'list_staffs' => []
		), '6_months' => array(
			'title' => '6 tháng',
			'list_staffs' => []
		),
	);
	$curr_month = date('m');
	$curr_year = date('Y');
	$department_id = (int) Input::post('department_id', 0);
	// $start_time = strtotime(sprintf('%s-%s-%s', 1, $curr_month, $curr_year));
	// $end_day = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year);
	// $end_time = strtotime(sprintf('%s-%s-%s', $end_day, $curr_month, $curr_year));
	$end_time = time();
	$arr_property_cached = array();
	foreach($list_time_points as $key => $val){
		if($key == '1_month'){
			$start_date = strtotime("-1 month", $end_time);
		} else if($key == '2_months'){
			$start_date = strtotime('-2 months', $end_time);
		} else if($key == '3_months'){
			$start_date = strtotime('-3 months', $end_time);
		} else if($key == '6_months'){
			$start_date = strtotime('-6 months', $end_time);
		}
		$end_date = $end_time;
		// $dbconn->debug = true;
		$field = "`t1`.{$clsProfile->pkey},`t1`.`full_name`,`t1`.`department_id`,`t1`.`start_date`";
		$sql_query = "`t1`.`is_trash`=0 AND `t1`.`status_id`='"._STATUS_STAFF_ON_ID."'";
		if($department_id > 0 && $department_id != _DEPARTMENT_SALE_ID){
			$sql_query.= " AND `t1`.`department_id`='{$department_id}'";
			$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
		} else {
			$sql_query.= " AND `t1`.`list_department_id` LIKE '%|"._DEPARTMENT_SALE_ID."|%'";
			$tmp = $clsProperty->getAll("`property_type`='_DEPARTMENT'", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $okey => $oval){
					$arr_property_cached[$oval[$clsProperty->pkey]] = $oval['title'];
				}
				unset($tmp);
			}
		}
		// $dbconn->debug = true;
		$clsProfile->alias = "t1";
		$list_staffs = $clsProfile->getAll("{$sql_query} AND `t1`.`start_date`<='{$start_date}' AND NOT EXISTS ( 
			SELECT 1 FROM {$clsBilling->tbl} as `t2` 
			WHERE `t2`.`staff_id`=`t1`.`profile_id` AND `t2`.`is_cancel`=0 AND (`t2`.`deposit_date` BETWEEN {$start_date} AND {$end_date})
		) ORDER BY `start_date` ASC", $field);
		if(!empty($list_staffs)){
			foreach($list_staffs as $okey => $oval){
				$department_id = $oval['department_id'];
				$list_staffs[$okey]['department_name'] = $arr_property_cached[$department_id];
			}
			$list_time_points[$key]['list_staffs'] = $list_staffs;
		}
		$list_time_points[$key]['start_date'] = $start_date;
		$list_time_points[$key]['end_date'] = $end_date;
	}
	// $assign_list["list_time_points"] = $list_time_points;
	$smarty->assign('list_time_points', $list_time_points);
	// Return
	$html = $core->build('_ajax.top_sales.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_sales_has_trans(){
	global $smarty,$assign_list,$core,$clsISO,$oneProfile,$profile_id,$dbconn,
		$title_page,$description_page,$keyword_page; 
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$assign_list["clsProperty"] = $clsProperty;
	if(!$clsISO->checkPermissionGroup('DIRECTOR')){
		header('Location:/');
		exit();
	}
	$current_month = date('n');
	$current_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= $current_month; $i++){
		$list_months[] = $i;
	}
	for($i=2023; $i<= $current_year; $i++){
		$list_years[] = $i;
	}
	$assign_list["current_month"] = $current_month;
	$assign_list["current_year"] = $current_year;
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Sale đã có giao dịch - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_sales_has_trans(){
	global $smarty,$assign_list,$core,$clsISO,$oneProfile,$profile_id,$dbconn,
		$title_page,$description_page,$keyword_page,$deviceType; 
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '_month');
	$department_id = (int) Input::post('department_id', 0); //COUNT(1) AS `total_billings`
	$billing_type = (int) Input::post('billing_type', 0); //COUNT(1) AS `total_billings`
	$field = "`t1`.{$clsProfile->pkey},`t1`.`full_name`,`t1`.`department_id`";
	$field.= ",SUM(CASE WHEN `t2`.`is_cancel` = 0 THEN 1 ELSE 0 END) AS `total_billings`";
	$field.= ",SUM(CASE WHEN `t2`.`is_cancel` = 0 THEN `t2`.`totalgrand` ELSE 0 END) AS `total_ds`";
	$field.= ",SUM(CASE WHEN `t2`.`is_cancel` = 1 THEN 1 ELSE 0 END) as `total_cancel`";
	$field.= ",SUM(CASE WHEN `t2`.`billing_source_id` = '"._BILLING_RESOURCE_F1_ID."' THEN 1 ELSE 0 END) as `total_f1`";
	$field.= ",SUM(CASE WHEN `t2`.`billing_source_id` = '"._BILLING_RESOURCE_F1_ID."' THEN `t2`.`totalgrand` ELSE 0 END) as `total_billing_f1`";
	$field.= ",SUM(CASE WHEN `t2`.`contract_status_id` = '"._CONTRACT_STATUS_DONE_ID."' THEN 1 ELSE 0 END) as `total_registed`";
	// $dbconn->debug = true;
	$cnd = "`t2`.`is_trash`=0 and `t2`.`is_alliance`=0 AND `t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	
	// $group_id = 12;
	// $one = $clsGroupProfile->getOne($group_id);
	// $list_profile_id = $one['list_profile_id'];
	// $arr_profile_groups = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
	// $cnd.= " AND `t2`.`staff_id` IN (".implode(',',$arr_profile_groups).")";
	if($department_id > 0) $cnd.= " and (`department_id`='{$department_id}' 
		OR `list_department_id` like '%|{$department_id}|%')";
	if(!empty($billing_type)) $cnd.= " AND `billing_type`='{$billing_type}'";
	if($month > 0){
		if($date_type == '_month'){
			$regex = "%m/%Y";
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year); 
			$start_date = strtotime(sprintf('%s-%s-%s', '01', $month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
			$cnd.= " AND FROM_UNIXTIME(`t2`.`deposit_date`,'{$regex}')='{$date_my}'";
		} else if($date_type == '_quater'){
			if($month == 1){
				$start_month = 1;
				$end_month = 3;
			} else if($month == 2){
				$start_month = 4;
				$end_month = 6;
			} else if($month == 3){
				$start_month = 7;
				$end_month = 9;
			} else {
				$start_month = 9;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$cnd.= " AND (`t2`.`deposit_date` BETWEEN {$start_date} AND {$end_date})";
		} else if($date_type == '_half_year'){
			if($month == 1){
				$start_month = 1;
				$end_month = 6;
			} else if($month == 2){
				$start_month = 7;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$cnd.= " AND (`t2`.`deposit_date` BETWEEN {$start_date} AND {$end_date})";
		}
	} else {
		$regex = "%Y";
		$date_my = $year;
		$start_date = strtotime(sprintf('%s-%s-%s', '01', '01', $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
		$end_date = strtotime(sprintf('%s-%s-%s', $end_day, 12, $year));
		$cnd.= " AND FROM_UNIXTIME(`t2`.`deposit_date`,'{$regex}')='{$date_my}'";
	}
	// $cnd.= " AND FROM_UNIXTIME(`t2`.`deposit_date`, '%Y')='2025' AND `t2`.`deposit_date` < '".strtotime('30-6-2025 23:59:59')."'";
	$list_staffs = $dbconn->getAll("SELECT {$field} FROM {$clsProfile->tbl} as `t1` 
		INNER JOIN {$clsBilling->tbl} as `t2` ON `t1`.`profile_id`=t2.`staff_id` 
		WHERE {$cnd} GROUP BY `t2`.`staff_id` HAVING `total_billings`>0 ORDER BY `total_ds` DESC");
//	$clsISO->print_pre($list_staffs); die();
	$html = "";
	if(!empty($list_staffs)){ $ii=1;
		$arr_property_cached = array();
		$total_billings = $total_billing_f1 = $total_f1 = $total_registed = $total_cancel = $total_ds = 0;
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$department_id = $val['department_id'];
			if(!isset($arr_property_cached[$department_id])){
				$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
			}
			$html.= '<tr>
				'.($deviceType!='phone'?'<td class="align-center text-center">'.$ii.'</td>':'').'
				<td class="align-center">'.$val['full_name'].'</td>
				<td class="align-center">'.$arr_property_cached[$department_id].'</td>
				<td class="align-center text-center">
					<strong class="fw-bold text-main">'.$clsISO->shortNumber($val['total_ds']).'</strong>
				</td>
				<td class="align-center text-center">
					<a class="text-link" target="_blank" href="/giao-dich.html?staff_id='.$staff_id.'&start_date='.$start_date.'&to_date='.$end_date.'"><i class="bx bx-link-external fs-12"></i></a>
					<strong class="fw-bold text-primary">'.$val['total_billings'].'</strong> căn
				</td>
				<td class="align-center  text-center">
					<strong class="text-warning fw-bold">'.$clsISO->shortNumber($val['total_billing_f1']).'</strong>
				</td>
				<td class="align-center  text-center">
					<strong class="text-warning fw-bold">'.$val['total_f1'].'</strong> căn
				</td>
				<td class="align-center text-center">
					<strong class="text-danger fw-bold ">'.$val['total_registed'].'</strong> căn
				</td>
				<td class="align-center text-center">
					<strong class="text-gray fw-bold ">'.$val['total_cancel'].'</strong> căn
				</td>
			</tr>';
			$total_ds += $val['total_ds'];
			$total_billings += $val['total_billings'];
			$total_f1 += $val['total_f1'];
			$total_billing_f1 += $val['total_billing_f1'];
			$total_registed += $val['total_registed'];
			$total_cancel += $val['total_cancel'];
			++$ii;
		}
		$html.= '<tr>
			<td'.($deviceType=='phone'?'':' colspan="2"').' class="align-center bg-lighter text-center h-px-35 fw-bold">TỔNG</td>
			<td class="align-center bg-lighter h-px-35"></td>
			<td class="align-center bg-lighter h-px-35 text-center">
				<strong class="fw-bold text-main">'.$clsISO->shortNumber($total_ds).'</strong>
			</td>
			<td class="align-center bg-lighter h-px-35 text-center">
				<a href="'.PCMS_URL.'/giao-dich.html?start_date='.$start_date.'&to_date='.$end_date.'"
					class="text-link" target="_blank"><i class="bx bx-link-external fs-12"></i></a>
				<strong class="fw-bold text-primary">'.$total_billings.'</strong> căn
			</td>
			<td class="align-center bg-lighter h-px-35 text-center">
				<strong class="text-warning fw-bold">'.$clsISO->shortNumber($total_billing_f1).'</strong>
			</td>
			<td class="align-center bg-lighter h-px-35 text-center">
				<strong class="text-warning fw-bold">'.$total_f1.'</strong> căn
			</td>
			<td class="align-center bg-lighter h-px-35 text-center">
				<strong class="text-danger fw-bold ">'.$total_registed.'</strong> căn
			</td>
			<td class="align-center bg-lighter h-px-35 text-center">
				<strong class="text-gray fw-bold ">'.$total_cancel.'</strong> căn
			</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_report_sales_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsProfile;
	$clsMember = new Member();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsShare = new Share();
	$clsLog = new Log();
	$clsCourse = new Course();
	$clsAttendance = new Attendance();
	$clsCustomer = new Customer();
	#
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', "_month");
	$group_id = (int) Input::post('group_id', 0);
	$group_id = $group_id ?: _PROFILE_DEFAULT_GROUP_ID;
	$billing_type_id = (int) Input::post('billing_type_id', 0);
	$billing_source_id = (int) Input::post('billing_source_id', 0);
	
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date'); 
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	# Billing
	$sql_query = " AND `is_trash`=0 and `is_cancel`=0";
	$sql_query.= " AND (`deposit_date` BETWEEN {$start_time} AND {$end_time})";
	if($billing_source_id > 0) {
		$sql_query.= " AND `billing_source_id`='{$billing_source_id}'";
	}
	if($billing_type_id > 0) {
		$sql_query.= " AND `billing_type`='{$billing_type_id}'";
	}
	# Media share
	$sql_course = "`start_date`>='{$start_time}' AND `due_date`<='{$end_time}'";
	# Logs
	$sql_logs = " AND (`reg_date` BETWEEN {$start_time} AND {$end_time})";
	# Share
	$sql_share = "(`reg_date` BETWEEN {$start_time} AND {$end_time})";;
	#- Chấm Công
	$sql_attendance = "(`work_date` BETWEEN {$start_time} AND {$end_time})";
	# Customer
	$sql_customer = "(`reg_date` BETWEEN {$start_time} AND {$end_time})";
	#
	$oneGroup = $clsGroupProfile->getOne($group_id, "list_profile_id");
	$list_profile_id = $oneGroup["list_profile_id"];
	$arr_profile_groups = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
	#
	$arr_satffs = array();
	$arr_staffs_cached = $clsProfile->getProfileCached("active");
	if(!empty($arr_staffs_cached)){
		foreach($arr_staffs_cached as $key => $val){
			if(in_array($val[$clsProfile->pkey], $arr_profile_groups)){
				$arr_satffs[$key] = $val;
				$arr_satffs[$key]['total_sales'] = 0;
				$arr_satffs[$key]['total_billings'] = 0;
				$arr_satffs[$key]['total_share'] = 0;
				$arr_satffs[$key]['total_search'] = 0;
				$arr_satffs[$key]['total_post'] = 0;
				$arr_satffs[$key]['total_customer'] = 0;
				$arr_satffs[$key]['total_work_unit'] = 0;
				$more_information_staff = $val["more_information"];
				$social_channels = !empty($more_information_staff["social_channels"]) ? $more_information_staff["social_channels"] : [];
				$arr_satffs[$key]['total_social'] = count($social_channels);
				unset($more_information_staff);
			}
		}
	} else {
		$cond = " AND `{$clsProfile->pkey}` IN ('".implode('\',\'',$arr_profile_groups)."')";
		$field = "`{$clsProfile->pkey}`,`code`,`full_name`,`first_name`,`last_name`";
		$arr_satffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'".$cond, $field);
	}
	$total_sales = $total_billings = $total_share = $total_search = $total_post = 0;
	$total_customers = $total_work_units = 0;
	if(!empty($arr_satffs)){
		#- Giao dịch
		$field = "{$clsBilling->pkey},`staff_id`,`totalgrand`";
		$list_billings = $clsBilling->getAll("`staff_id` IN (".implode(",", $arr_profile_groups).")".$sql_query, $field);
		if(!empty($list_billings)){
			foreach($list_billings as $key => $val){
				$staff_id = (int) $val['staff_id'];
				$totalgrand = $clsISO->processSmartNumber($val['totalgrand']);
				$arr_satffs[$staff_id]['total_billings'] += 1;
				$arr_satffs[$staff_id]['total_sales'] += $totalgrand;
			}
			unset($list_billings);
		}
		#- Tiếp khách
		$field = "COUNT(*) as `total_share`,`user_id`";
		$list_shares = $clsShare->getAll(" {$sql_share} AND `share_type`='share' 
			AND user_id IN ('".implode('\',\'',$arr_profile_groups)."') GROUP BY `user_id`", $field);
		if(!empty($list_shares)){
			foreach($list_shares as $key => $val){
				$user_id = (int) $val['user_id'];
				$arr_satffs[$user_id]['total_share'] += $val['total_share'];
			}
			unset($list_shares);
		}
		#- Tìm kiếm
		$field = "COUNT(*) as `total_search`,`user_id`";
		$list_logs = $clsLog->getAll("`user_id` IN ('".implode('\',\'',$arr_profile_groups)."')".$sql_logs." GROUP BY `user_id`", $field);
		if(!empty($list_logs)){
			foreach($list_logs as $key => $val){
				$user_id = (int) $val['user_id'];
				$arr_satffs[$user_id]['total_search'] += $val['total_search'];
			}
			unset($list_logs);
		}
		#- Khoá học đào tạo
		$cond = "{$sql_course} AND `is_trash`=0 AND `cat_id`='"._MEDIA_DISSEMINATION."'";
		$cond.= " AND `list_group_profile_id` like '%|{$group_id}|%'";
		$list_course = $clsCourse->getAll($cond, "more_information");
		if(!empty($list_course)){
			foreach($list_course as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$upload_share = $core->get_field($more_information, "upload_share", []);
				if(!empty($upload_share)){
					foreach($upload_share as $okey => $oval){
						if(isset($arr_satffs[$okey])){
							$arr_satffs[$okey]['total_post'] += 1;
						}
					}
				}
			}
			unset($list_course);
		}
		#- Chấm công
		$tmp = $clsAttendance->getAll("{$sql_attendance} AND `staff_id` IN ('".implode('\',\'',$arr_profile_groups)."') 
			GROUP BY `staff_id`", "SUM(`work_unit`) AS `total_work_unit`,`staff_id`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$staff_id = (int) $val['staff_id'];
				$arr_satffs[$staff_id]['total_work_unit'] += $val['total_work_unit'];
			}
			unset($tmp);
		}
		#- Khách hàng
		$tmp = $clsCustomer->getAll("{$sql_customer} 
			AND `admin_id` IN ('".implode('\',\'',$arr_profile_groups)."') GROUP BY `admin_id`", "COUNT(1) AS `total_customer`,`admin_id`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$admin_id = (int) $val['admin_id'];
				$arr_satffs[$admin_id]['total_customer'] += $val['total_customer'];
			}
			unset($tmp);
		}
		foreach($arr_satffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$arr_satffs[$key]['total_sales'] = $val['total_sales'];
			$arr_satffs[$key]['total_billings'] = $val['total_billings'];
			$arr_satffs[$key]['total_share'] = $val['total_share'];
			$arr_satffs[$key]['total_search'] = $val['total_search'];
			$arr_satffs[$key]['total_post'] = $val['total_post'];
			$arr_satffs[$key]['total_customer'] = $val['total_customer'];
			$arr_satffs[$key]['total_work_unit'] = $val['total_work_unit'];
			$total_billings += $val['total_billings'];
			$total_sales += $val['total_sales'];
			$total_share += $val['total_share'];
			$total_search+= $val['total_search'];
			$total_post+= $val['total_post'];
			$total_customers += $val['total_customer'];
			$total_work_units += $val['total_work_unit'];
		}
		$sort_by = Input::post('sort_by', "total_sales");
		$sort_type = Input::post('sort_type', "desc");
		$total_sales_arrs = @array_column($arr_satffs, $sort_by);
		@array_multisort($total_sales_arrs, ($sort_type == 'desc' ? SORT_DESC : SORT_ASC), $arr_satffs);
	}
	//$clsISO->print_pre($cond_billing); die();
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('total_sales', $total_sales);
	$smarty->assign('total_share', $total_share);
	$smarty->assign('total_search', $total_search);
	$smarty->assign('total_post', $total_post);
	$smarty->assign('total_customers', $total_customers);
	$smarty->assign('total_work_units', $total_work_units);
	$smarty->assign('arr_satffs', $arr_satffs);
	// Return
	$html = $core->build('_ajax.load_report_sales_group.tpl');
	echo json_encode(array("html" => $html),JSON_UNESCAPED_UNICODE); die();
}
function default_load_top_sales_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsProfile;
	$clsMember = new Member();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$department_id = (int) Input::post('department_id', 0);
	$department_id = ($department_id > 0) ? $department_id : _DEPARTMENT_SALE_ID;
	// $clsISO->print_pre(_DEPARTMENT_SALE_ID); die();
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `staff_id`<>'"._PROFILE_PARTNER_ID."'";
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
	} else {
		$cond.= " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	$list_staffs = $clsProfile->getProfileDep($department_id, 1, "active");
	// $clsISO->print_pre($list_staffs); die();
	$arr_staff_ids = !empty($list_staffs) ? array_keys($list_staffs) : [];
	$cond.= " AND `staff_id` IN ('".implode('\',\'',$arr_staff_ids)."')";
	// $clsISO->print_pre($cond); die();
	$list_staffs = $arr_staffs_billing = array();
	$field = "`staff_id`, COUNT(1) AS `total_billings`, SUM(`totalgrand`) AS `total_grands`";
	$tmp = $clsBilling->getAll("{$cond} GROUP BY `staff_id` ORDER BY `total_grands` DESC LIMIT 0,5", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_staffs_billing[$val["staff_id"]] = $val;
		}
		unset($tmp);
	}
	if(!empty($arr_staffs_billing)){ $ii = 0;
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($arr_staffs_billing as $staff_id => $val){
			$list_staffs[] = array(
				'profile_id' => $staff_id,
				'oneStaff' => $arr_profile_cached[$staff_id],
				'total_billings' => $val['total_billings'],
				'total_sales' => $val['total_grands']
			);
			++$ii;
		}
	}
	$smarty->assign('list_staffs', $list_staffs);
	// Return
	$html = $core->build('_ajax.load_report_sales_group.tpl');
	echo json_encode(array("html" => $html),JSON_UNESCAPED_UNICODE); die();
}
function default_report_department(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$clsProfile,$oneProfile,$deviceType;
	global $title_page, $description_page;
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	##
	if(!$clsISO->checkPermissionGroup("DIRECTOR") 
		&& !$clsISO->checkPermissionGroup('SALE_DIRECTOR') 
		&& !$clsISO->checkPermissionGroup("BUSINESS_AREA")) {
		header("Location: /#not-permiss"); 
		exit();
	}
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if($clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')){
		$department_root_id = $department_id;
	} else {
		$department_root_id = _DEPARTMENT_SALE_ID;
	}
	$list_departments = $clsISO->buildTree($arr_deparment_cached, $department_root_id, 'property_id');
	$oneDep = $clsProperty->getArraySearchByKey("_DEPARTMENT", $department_id);
	$smarty->assign('list_departments', $list_departments);
	#
	$Current_Now = time();
	$Current_Month = date('n', $Current_Now);
	$Current_Year = date('Y', $Current_Now);
	$start_date = date('Y-m-d', strtotime('-7 days'));
	$end_date = date('Y-m-d', $Current_Now);
	#
	$list_months = $list_years = $list_preloaders = array();
	for($i=1; $i<=$Current_Month; $i++){
		$list_months[] = $i;
	}
	#
	$Start_Year = 2024;
	for($i=$Start_Year; $i<=$Current_Year; $i++){
		$list_years[] = $i;
	}
	##
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	##
	$list_blocks[] = array(
		'title' => 'Tra cứu',
		'class' => 'a1a bg-orange'
	);
	$list_blocks[] = array(
		'title' => 'Tiếp khách',
		'class' => 'a2a bg-azure'
	);
	$list_blocks[] = array(
		'title' => 'Giao dịch',
		'class' => 'a3a bg-cyan'
	);
	$list_blocks[] = array(
		'title' => 'Doanh số',
		'class' => 'a4a bg-danger'
	);
	if($deviceType == 'computer'){
		$list_blocks[] = array(
			'title' => 'Nhân sự',
			'class' => 'a5a bg-purple'
		);
	}
	$smarty->assign('current_month', date("Y-m"));	
	$smarty->assign('start_date', $start_date);
	$smarty->assign('end_date', $end_date);
	$smarty->assign('Current_Year', $Current_Year);
	$smarty->assign('Current_Month', $Current_Month);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('oneDep', $oneDep);
	$smarty->assign('list_preloaders', $list_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo phòng ban/Vùng kinh doanh - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function default_load_marketing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsProfile,$oneProfile,$deviceType;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampign = new Campaign();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$department_id = Input::post('department_id', 0);
	$department_id = ($department_id > 0) ? $department_id : _DEPARTMENT_SALE_ID;
	// GĐ Sale
	$is_dir_sale = $clsISO->checkPermissionGroup("SALE_DIRECTOR") ? 1 : 0;
	// GĐ 
	$is_regional_dir = $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') ? 1 : 0;
	// Cond
	if($month > 0){
		$month = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		$cond = "`t1`.`month`='{$month}'";
		$sub_cond = "`month`='{$month}'";
		$sql_query = " AND FROM_UNIXTIME(`reg_date`,'%Y-%m')='{$month}'";
	} else {
		$arr_months = array();
		$max_month = ($year == date('Y')) ? date('n') : 12;
		for($month=1; $month <= $max_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`t1`.`month` IN ('".implode('\',\'', $arr_months)."')";
		$sub_cond = "`month` IN ('".implode('\',\'', $arr_months)."')";
		$sql_query = " AND FROM_UNIXTIME(`reg_date`,'%Y-%m') IN ('".implode('\',\'',$arr_months)."')";
	}
	$list_staffs = $clsProfile->getProfileDep($department_id, 1, "active");
	$arr_staffs_ids = !empty($list_staffs) ? array_keys($list_staffs) : [];
	$cond.= " AND `t1`.`staff_id` IN ('".implode('\',\'', $arr_staffs_ids)."')";
	$sub_cond.= " AND `staff_id` IN ('".implode('\',\'', $arr_staffs_ids)."')";
	#
	$list_regis_staffs = $arr_projects = $arr_blocks = $arr_block_ids = $arr_project_ids = array();
	$user_percent = $total_budgets = $total_amounts = $total_company_support_amounts = $total_sale_support_amounts = 0;
	$field = "`t1`.`{$clsMarketingBudgetRegister->pkey}`,`t1`.`total_budget`,`t1`.`staff_id`,`t1`.`project_id`";
	$field.= ",`t1`.`block_id`,`t2`.`amount`,`t2`.`company_support_amount`,`t2`.`sale_support_amount`";
	// $dbconn->debug = true;
	$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsMarketingBudgetRegister->tbl} AS `t1` LEFT JOIN (SELECT `project_id`,`block_id`,`staff_id`,SUM(`amount`) AS `amount`,SUM(`company_support_amount`) AS `company_support_amount`,SUM(`sale_support_amount`) AS `sale_support_amount` FROM {$clsMarketingSpending->tbl} WHERE {$sub_cond} GROUP BY `project_id`, `block_id`, `staff_id`) AS `t2` ON `t1`.`staff_id`=`t2`.`staff_id` AND `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` WHERE {$cond}");	
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$staff_id = (int) $val['staff_id'];
			$block_id = (int) $val['block_id'];
			$project_id = (int) $val['project_id'];
			if($block_id > 0 && !in_array($block_id, $arr_block_ids)) 
				$arr_block_ids[] = $block_id;
			if($project_id > 0 && !in_array($project_id, $arr_project_ids)) 
				$arr_project_ids[] = $project_id;
			$total_budgets += $clsISO->processSmartNumber($val['total_budget']);
			$total_amounts += $clsISO->processSmartNumber($val['amount']);
			$total_company_support_amounts += $clsISO->processSmartNumber($val['company_support_amount']);
			$total_sale_support_amounts += $clsISO->processSmartNumber($val['sale_support_amount']);
			#
			if(array_key_exists($staff_id, $list_regis_staffs)){
				$list_regis_staffs[$staff_id][] = $val;
			} else {
				$list_regis_staffs[$staff_id][] = $val;
			}
		}
		$user_percent = round($total_amounts / $total_budgets, 2) * 100;
		if(!empty($arr_project_ids)){
			$list_projects = $clsProject->getAll("{$clsProject->pkey} IN (".implode(",", $arr_project_ids).")", "{$clsProject->pkey},`title`");
			if(!empty($list_projects)){
				foreach($list_projects as $key => $val){
					$arr_projects[$val[$clsProject->pkey]] = $val['title']; 
				}
				unset($list_projects);
			}
		}
		if(!empty($arr_block_ids)){
			$list_blocks = $clsProperty->getAll("{$clsProperty->pkey} IN (".implode(",", $arr_block_ids).")", "{$clsProperty->pkey},`title`");
			if(!empty($list_blocks)){
				foreach($list_blocks as $key => $val){
					$arr_blocks[$val[$clsProperty->pkey]] = $val['title']; 
				}
				unset($list_blocks);
			}
		}
	}
	$html = '<div class="row">
		<div class="col-12 col-md-5 mb-2 mb-lg-0">
			<div class="d-flex align-items-center gap-3 text-fs-15 mb-2">
				<span class="w-px-200"><i class=\'bx bx-bar-chart-alt text-danger text-fs-26\'></i> Ngân sách dự kiến</span>
				<span class="fw-bold">'.$clsISO->formatPrice($total_budgets).'</span>
			</div>
			<div class="d-flex align-items-center gap-3 text-fs-15">
				<span class="w-px-200"><i class=\'bx bx-dollar-circle text-warning text-fs-26\'></i> Ngân sách thực tế</span>
				<div class="d-flex gap-1 fw-bold text-warning">'.$clsISO->formatPrice($total_amounts).($user_percent > 0 ? sprintf('<span class="badge bg-label-success" title="Thực tế/Dự kiến" data-bs-toggle="tooltip">%s&#37;</span>', $user_percent) : '').'</div>
			</div>
		</div>
		<div class="col-1 d-none d-lg-block border-end"></div>
		<div class="col-1 d-none d-lg-block"></div>
		<div class="col-12 col-md-5">
			<div class="d-flex align-items-center gap-3 text-fs-15 mb-2">
				<span class="w-px-200"><i class=\'bx bx-building text-primary text-fs-26\'></i> Công ty hỗ trợ</span>
				<span class="fw-bold">'.$clsISO->formatPrice($total_company_support_amounts).'</span>
			</div>
			<div class="d-flex align-items-center gap-3 text-fs-15">
				<span class="w-px-200"><i class=\'bx bx-user text-success text-fs-26\'></i> Sale chịu</span>
				<span class="fw-bold">'.$clsISO->formatPrice($total_sale_support_amounts).'</span>
			</div>
		</div>
	<div>
	<hr class="my-3" />';
	if($department_id == _DEPARTMENT_SALE_ID){
		$is_director = 1;
	} else {
		$more_information = $clsProperty->getOneField('more_information', $department_id);
		$more_information = $clsISO->to_array_json($more_information);
		$is_business_area = (int) $core->get_field($more_information, "is_business_area", 0);
		if($is_business_area == 1){
			$is_regional_dir = 1;
		} else {
			$is_dir_sale = 1;
		}
	}
	// $clsISO->print_pre($list_regis_staffs); die();
	if(!empty($list_regis_staffs)){
		if($is_regional_dir == 1 || $is_director == 1){
			$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
			if($is_director == 1){
				$list_departments = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
				if(!empty($list_departments)){
					$html.= '<div class="form-row">';
					foreach($list_departments as $dep_id => $val){
						$arr_staffs = $clsProfile->getProfileDep($dep_id, 1, "active");
						$arr_staffs_ids = !empty($arr_staffs) ? array_keys($arr_staffs) : [];
						
						$results = array();
						$total_budgets = $total_amounts = $total_company_support_amounts = $total_sale_support_amounts = 0;
						if(!empty($arr_staffs_ids)){
							foreach ($list_regis_staffs as $staff_id => $items) {
								if (in_array($staff_id, $arr_staffs_ids, true)) {
									$results[$staff_id] = $items;
								}
							}
							if(!empty($results)){
								foreach($results as $ostaff_id => $oitems){
									foreach($oitems as $item){
										$total_budgets += $clsISO->processSmartNumber($item['total_budget'] ?? 0);
										$total_amounts += $clsISO->processSmartNumber($item['amount'] ?? 0);
										$total_company_support_amounts += $clsISO->processSmartNumber($item['company_support_amount'] ?? 0);
										$total_sale_support_amounts += $clsISO->processSmartNumber($item['sale_support_amount'] ?? 0);
									}
								}
							}
						}
						$used_percent = $total_budgets > 0 ? round($total_amounts / $total_budgets * 100, 2) : 0;
						$html.= '<div class="col-12 col-md-6 col-xxxl-3 mb-2">
							<div class="bg-light w-100 py-2 px-3 rounded-3">
								<div class="d-flex align-items-center justify-content-between">
									<h3 class="text-fs-15 mb-0">'.$val['title'].'</h3>
									<strong class="text-right text-danger">'.$clsISO->shortNumber($total_budgets).'</strong>
								</div>
								<div class="d-flex py-1 gap-1 w-100 align-items-center">
									<div class="progress w-100" style="height:10px;">
									  <div class="progress-bar bg-info" role="progressbar" style="width:'.$used_percent.'%;">'.$used_percent.'%</div>
									</div> 
								</div>
								<div class="d-flex align-items-center justify-content-between">
									<div class="d-flex gap-2 align-items-center justify-content-between">
										<span class="text-muted">Thực tế</span>
										<strong class="text-right text-warning">'.$clsISO->shortNumber($total_amounts).'</strong>
									</div>
									<div class="d-flex gap-2 align-items-center justify-content-between">
										<span class="text-muted">Hỗ trợ</span>
										<strong class="text-right text-warning">'.$clsISO->shortNumber($total_company_support_amounts).'</strong>
									</div>
								</div>
							</div>
						</div>';
					}
					$html.= '</div>';
				}
			}	
			$html.= '<div class="table-container text-nowrap overflow-x-auto no-shadow">
				<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">
					<thead><tr>
						<th class="align-center text-center w-px-30 h-px-35">No.</th>
						<th class="align-center h-px-35">Họ và tên</th>
						<th class="align-center h-px-35">Dự án</th>
						<th class="align-center sortable h-px-35 text-right">Dự kiến</th>
						<th class="align-center sortable h-px-35 text-right">Thực tế</th>
						<th class="align-center sortable h-px-35">% chi</th>

						<th class="align-center sortable h-px-35 text-right">CTY hỗ trợ</th>
						<th class="align-center sortable h-px-35 text-right">Sale chịu</th>
						<th class="align-center sortable h-px-35 text-center">Tổng Lead</th>
					</tr></thead>';
					$stt = 0;
					foreach($list_regis_staffs as $staff_id => $rows){
						$total_rows = count($rows);
						$oneStaff = $list_staffs[$staff_id];
						$more_staff = $oneStaff['more_information'];
						$department_name = $core->get_field($more_staff, "department_name", "");
						if($total_rows == 1){
							foreach($rows as $row){
								$block_id = (int) $row['block_id'];
								$project_id = (int) $row['project_id'];
								$project_name = ($block_id > 0) ? $arr_blocks[$block_id] : $arr_projects[$project_id];
								$total_budgets = $clsISO->processSmartNumber($row['total_budget'] ?? 0);
								$total_amounts = $clsISO->processSmartNumber($row['amount'] ?? 0);
								$company_support_amounts = $clsISO->processSmartNumber($row['company_support_amount'] ?? 0);
								$sale_support_amounts = $clsISO->processSmartNumber($row['sale_support_amount'] ?? 0);
								$used_percent = $total_budgets > 0 ? round($total_amounts / $total_budgets * 100, 2) : 0;
								$oneCampaign = 	array($clsCampign->pkey => 0);
								if($block_id > 0){
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}' OR JSON_EXTRACT(`campaign_config`,\"$.block_id\")='{$block_id}'", $clsCampign->pkey);
								} else {
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}'", $clsCampign->pkey);
								}
								$campaign_id = $oneCampaign[$clsCampign->pkey];
								// $dbconn->debug = true;
								// AND `list_campaign_id` LIKE '%|{$campaign_id}|%'
								$total_customers = $clsCustomer->countItem("`admin_id`='{$staff_id}' AND `resource_id`='"._CRM_RESOURCE_ADS_ID."'".$sql_query);
								$html.= '<tr>
									<td class="align-center text-center bg-white">'.($stt+1).'</td>
									<td class="align-center bg-white">
										<span class="badge bg-label-purple">'.$department_name.'</span>
										'.$clsProfile->getFullName($staff_id, $oneStaff).'
									</td>
									<td class="align-center bg-white">'.($project_name).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_budgets).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_amounts).'</td>
									<td class="align-center text-center bg-white">
										<div class="d-flex align-items-center gap-1">
											<div class="progress w-px-50" style="height:12px;">
											  <div class="progress-bar bg-info" role="progressbar" style="width:'.$used_percent.'%;"></div>
											</div> '.$used_percent.'%
										</div>
									</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($company_support_amounts).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($sale_support_amounts).'</td>
									<td class="align-center bg-white text-center">'.$total_customers.'</td>
								</tr>';
								++$stt;
							}
						} else { $ii = 0;
							foreach($rows as $row){
								$staff_id  = (int) $row['staff_id'];
								$block_id = (int) $row['block_id'];
								$project_id = (int) $row['project_id'];
								$project_name = ($block_id > 0) ? $arr_blocks[$block_id] : $arr_projects[$project_id];
								$total_budgets = $clsISO->processSmartNumber($row['total_budget'] ?? 0);
								$total_amounts = $clsISO->processSmartNumber($row['amount'] ?? 0);
								$company_support_amounts = $clsISO->processSmartNumber($row['company_support_amount'] ?? 0);
								$sale_support_amounts = $clsISO->processSmartNumber($row['sale_support_amount'] ?? 0);
								$used_percent = $total_budgets > 0 ? round($total_amounts / $total_budgets * 100, 2) : 0;
								#
								$oneCampaign = 	array($clsCampign->pkey => 0);
								if($block_id > 0){
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}' OR JSON_EXTRACT(`campaign_config`,\"$.block_id\")='{$block_id}'", $clsCampign->pkey);
								} else {
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}'", $clsCampign->pkey);
								}
								$campaign_id = $oneCampaign[$clsCampign->pkey];
								$total_customers = $clsCustomer->countItem("`admin_id`='{$staff_id}' AND `resource_id`='"._CRM_RESOURCE_ADS_ID."' 
									AND `list_campaign_id` LIKE '%|{$campaign_id}|%'".$sql_query);
								$html.= '<tr>
									'.($ii==0?'<td class="align-center text-center bg-white" rowspan="'.$total_rows.'">'.($stt+1).'</td>
									<td class="align-center bg-white" rowspan="'.$total_rows.'">
										<span class="badge bg-label-purple">'.$department_name.'</span>
										'.$clsProfile->getFullName($staff_id, $oneStaff).'
									</td>':'').'
									<td class="align-center bg-white">'.($project_name).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_budgets).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_amounts).'</td>
									<td class="align-center text-center bg-white">
										<div class="d-flex align-items-center gap-1">
											<div class="progress w-px-50" style="height:12px;">
											  <div class="progress-bar bg-info" role="progressbar" style="width:'.$used_percent.'%;"></div>
											</div> '.$used_percent.'%
										</div>
									</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($company_support_amounts).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($sale_support_amounts).'</td>
									<td class="align-center bg-white text-center">'.$total_customers.'</td>
								</tr>';
								++$ii;
							}
							++$stt;
						}
					}
				$html.= '</table>
			</div>';
		} else if($is_dir_sale){
			$html.= '<div class="table-container no-shadow">
				<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered">
					<thead><tr>
						<th class="align-center text-center w-px-30 h-px-35">STT</th>
						<th class="align-center h-px-35">Họ và tên</th>
						<th class="align-center h-px-35">Dự án</th>
						<th class="align-center h-px-35 sortable text-right">Dự kiến</th>
						<th class="align-center h-px-35 sortable text-right">Thực tế</th>
						<th class="align-center h-px-35 sortable">% chi</th>
						<th class="align-center h-px-35 sortable text-right">CTY hỗ trợ</th>
						<th class="align-center h-px-35 sortable text-right">Sale chịu</th>
						<th class="align-center h-px-35 sortable text-center">Tổng Lead</th>
					</tr></thead>';
					$kk = 0;
					foreach($list_regis_staffs as $staff_id => $rows){
						$total_rows = count($rows);
						$oneStaff = $list_staffs[$staff_id];
						$more_staff = $oneStaff['more_information'];
						$department_name = $core->get_field($more_staff, "department_name", "");
						if($total_rows == 1){
							foreach($rows as $row){
								$block_id = (int) $row['block_id'];
								$project_id = (int) $row['project_id'];
								$project_name = ($block_id > 0) ? $arr_blocks[$block_id] : $arr_projects[$project_id];
								$total_budgets = $clsISO->processSmartNumber($row['total_budget'] ?? 0);
								$total_amounts = $clsISO->processSmartNumber($row['amount'] ?? 0);
								$company_support_amounts = $clsISO->processSmartNumber($row['company_support_amount'] ?? 0);
								$sale_support_amounts = $clsISO->processSmartNumber($row['sale_support_amount'] ?? 0);
								$used_percent = $total_budgets > 0 ? round($total_amounts / $total_budgets * 100, 2) : 0;
								$oneCampaign = 	array($clsCampign->pkey => 0);
								if($block_id > 0){
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}' OR JSON_EXTRACT(`campaign_config`,\"$.block_id\")='{$block_id}'", $clsCampign->pkey);
								} else {
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}'", $clsCampign->pkey);
								}
								$campaign_id = $oneCampaign[$clsCampign->pkey];
								$total_customers = $clsCustomer->countItem("`admin_id`='{$staff_id}' AND `resource_id`='"._CRM_RESOURCE_ADS_ID."' 
									AND `list_campaign_id` LIKE '%|{$campaign_id}|%'".$sql_query);
								$html.= '<tr>
									<td class="align-center text-center bg-white">'.($stt+1).'</td>
									<td class="align-center bg-white">
										<span class="badge bg-label-purple">'.$department_name.'</span>
										'.$clsProfile->getFullName($staff_id, $oneStaff).'
									</td>
									<td class="align-center bg-white">'.($project_name).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_budgets).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_amounts).'</td>
									<td class="align-center text-center bg-white">
										<div class="d-flex align-items-center gap-1">
											<div class="progress w-px-50" style="height:12px;">
											  <div class="progress-bar bg-info" role="progressbar" style="width:'.$used_percent.'%;"></div>
											</div> '.$used_percent.'%
										</div>
									</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($company_support_amounts).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($sale_support_amounts).'</td>
									<td class="align-center bg-white text-center">'.$total_customers.'</td>
								</tr>';
								++$stt;
							}
						} else { $ii = 0;
							foreach($rows as $row){
								$staff_id  = (int) $row['staff_id'];
								$block_id = (int) $row['block_id'];
								$project_id = (int) $row['project_id'];
								$project_name = ($block_id > 0) ? $arr_blocks[$block_id] : $arr_projects[$project_id];
								$total_budgets = $clsISO->processSmartNumber($row['total_budget'] ?? 0);
								$total_amounts = $clsISO->processSmartNumber($row['amount'] ?? 0);
								$company_support_amounts = $clsISO->processSmartNumber($row['company_support_amount'] ?? 0);
								$sale_support_amounts = $clsISO->processSmartNumber($row['sale_support_amount'] ?? 0);
								$used_percent = $total_budgets > 0 ? round($total_amounts / $total_budgets * 100, 2) : 0;
								#
								$oneCampaign = 	array($clsCampign->pkey => 0);
								if($block_id > 0){
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}' OR JSON_EXTRACT(`campaign_config`,\"$.block_id\")='{$block_id}'", $clsCampign->pkey);
								} else {
									$oneCampaign = $clsCampign->getByCond("JSON_EXTRACT(`campaign_config`,\"$.project_id\")='{$project_id}'", $clsCampign->pkey);
								}
								$campaign_id = $oneCampaign[$clsCampign->pkey];
								$total_customers = $clsCustomer->countItem("`admin_id`='{$staff_id}' AND `resource_id`='"._CRM_RESOURCE_ADS_ID."' 
									AND `list_campaign_id` LIKE '%|{$campaign_id}|%'".$sql_query);
								$html.= '<tr>
									'.($ii==0?'<td class="align-center text-center bg-white" rowspan="'.$total_rows.'">'.($kk+1).'</td>
									<td class="align-center bg-white" rowspan="'.$total_rows.'">
										<span class="badge bg-label-purple">'.$department_name.'</span>
										'.$clsProfile->getFullName($staff_id, $oneStaff).'
									</td>':'').'
									<td class="align-center bg-white">'.($project_name).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_budgets).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($total_amounts).'</td>
									<td class="align-center text-center bg-white">
										<div class="d-flex align-items-center gap-1">
											<div class="progress w-px-50" style="height:12px;">
											  <div class="progress-bar bg-info" role="progressbar" style="width:'.$used_percent.'%;"></div>
											</div> '.$used_percent.'%
										</div>
									</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($company_support_amounts).'</td>
									<td class="align-center bg-white text-right">'.$clsISO->shortNumber($sale_support_amounts).'</td>
									<td class="align-center bg-white text-center">'.$total_customers.'</td>
								</tr>';
								++$ii;
							}
							++$kk;
						}
					}
				$html.= '</table>
			</div>';
		}
	} else {
		$html.= '
		<div class="alert alert-warning text-center mb-0">
			<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
			<span>Chưa có nhân sự đăng nào ký ngân sách chạy Marketing!</span>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_billing_score(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsBilling = new Billing();
	$clsProject = new Project();
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list['list_preloaders'] = $list_preloaders;
	$report_type = Input::get('report_type', 'common');
	$box_width = ($report_type=='common') ? 500 : 200;
	$assign_list['report_type'] = $report_type;
	$assign_list['box_width'] = $box_width;
	$columnNum = ($deviceType=='phone') ? 2 : 4;
	$assign_list['columnNum'] = $columnNum;
	$lstProject = $clsProject->getListProject();
	$assign_list["lstProject"] = $lstProject;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo thông tin giao dịch chốt - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_billings_score(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsProject = new Project();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', 0);
	$group_product = Input::post("group_product");
	$billing_type = (int) Input::post('billing_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$report_type = Input::post('report_type', 'common');
	// `is_cancel`=0 
	$cond = "`is_trash`=0"; //  AND `is_cancel`='0'
	if($month == 0 && $year == 0) {
		$start_date = strtotime("2025-01-01 00:00:00");
		$to_date = time();
	}else{
		if($month == 0){
			$start_date = strtotime(sprintf("%s-01-01 00:00:00",$year));
			$to_date = strtotime(sprintf("%s-12-31 23:59:59",$year));
		} else {
			$ym = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
			$baseTime = strtotime("$ym-01");
			$start_date = strtotime(date('Y-m-d 00:00:00', $baseTime));
			$to_date   = strtotime(date('Y-m-d 23:59:59', strtotime('last day of this month', $baseTime)));
		}
	}
	#
	$arr_search["start_date"] = $start_date;
	$arr_search["to_date"] = $to_date;
	// Lọc dữ liệu
	$date_field = "deposit_date";
	if($sort_by == 'contract_date'){
		$date_field = 'contract_date'; // Ngày ký HĐMB
	}
	if($start_date > 0 && $to_date ==0){
		$cond.=" AND (`{$date_field}` > '{$start_date}')";
	} else if($start_date==0 && $to_date > 0){
		$cond.=" AND (`{$date_field}` < '{$to_date}')";
	} else if($start_date > 0 && $to_date > 0){
		$params['to_date'] = $to_date;
		$cond.= " AND (`{$date_field}` BETWEEN '{$start_date}' AND '{$to_date}')";
	}
	/*if($group_product == 'CAO_TANG'){
		$group_arrs = array(_BILLING_TYPE_CT_ID,_BILLING_TYPE_MWF_ID,_BILLING_TYPE_LSP_ID);
		$cond.= " and `billing_type` in (".implode(',', $group_arrs).")";
	} else if($group_product == 'THAP_TANG'){
		$cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
	} else if($group_product == 'CHO_THUE') {
		$group_arrs = array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID);
		$cond.= " and `billing_type` in (".implode(',', $group_arrs).")";
	}*/
	if(!empty($billing_type)){
		$cond.= " and `billing_type`='".$billing_type."'";
		$arr_search["billing_type"] = $billing_type;
	}
	if(!empty($project_id)){
		$cond.= " and `project_id`='".$project_id."'";
		$arr_search["project_id"] = $project_id;
	}
	if(!empty($block_id)){
		$cond.= " and JSON_EXTRACT(`more_information`,\"$.block_id\")='".$block_id."'";
		$arr_search["block_id"] = $block_id;
	}
	
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
		$cond.= " AND `staff_id`='{$profile_id}'";
	}
	$arrayInfoScore = $clsBilling->getArrayInfoScore();
	$arr_report = [];
	$field = "{$clsBilling->pkey},`more_information`,`contract_date`,`deposit_date`";
//	$dbconn->debug=true;
	$lstBilling = $clsBilling->getAll($cond." order by `reg_date` DESC", $field);
//	$clsISO->print_pre($lstBilling);die;
	$purl = "?filter_by=_project";
	if(!empty($arr_search)) {
		foreach ($arr_search as $key => $val) {
			$purl .= "&".$key."=".$val;
		}
	}
	$total_billding = !empty($lstBilling) ? count($lstBilling) : 0;
	foreach ($lstBilling as $key => $oBilling) {
		$more_information = $oBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		foreach ($arrayInfoScore as $_field => $title) {
			if($_field == "contract_date") {
				$value = $oBilling[$_field];
			}else {
				$value = !empty($more_information[$_field]) ? $more_information[$_field] : "";
			}
			$total_yes = !empty($value) ? 1 : 0;
			$total_no = empty($value) ? 1 : 0;
			if(!isset($arr_report[$_field])) {
				$arr_report[$_field] = [
					"title"		=>	$title,
					"total_yes"	=>	$total_yes,
					"total_no"	=>	$total_no,
				];
			}else{
				$arr_report[$_field]["total_yes"] += $total_yes;
				$arr_report[$_field]["total_no"] += $total_no;
			}
			unset($value);
		}
	}
	$smarty->assign("arr_report", $arr_report);
	$smarty->assign("total_billding", $total_billding);
	$smarty->assign("purl", $purl);
	$html = $core->build("_ajax.report_billings_score.tpl");
	// Return
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'total_billding' => $total_billding
	)); die();
}
function default_target_sales(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsProperty = new Property();
	$clsMember = new Member();
	if(!$clsISO->checkPermissionGroup('DIRECTOR') && !$clsISO->_DEV()) {
		header("Location: /");exit();
	}
	$list_preloaders = $type_of_date_arrs = $role_arrs = $arr_projects = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["current_month"] = date("Y-m");
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo mục tiêu cá nhân sale - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_target_sales(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$month = Input::post('month',date("Y-m"));
	$start_date = date('Y-m-d 00:00:00', strtotime($month . '-01'));
	$end_date = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	if($start_time < time() && $end_time > time()) {
		$end_time = time();
	}
	$my = date("n_Y",$start_time);
	$cond = ""; $list_share = array();
	$list_worktimes = $list_group_worktimes = array();
	
	$arr_property_cached = $clsProperty->getArraySearchByKey("_ROLE");
	$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_dep_area = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
	$listProfile = $clsProfile->getProfileCached("active");
	$lstDepartment = [];
	
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		$department_id = (int) Input::post('department_id',0);
		if($department_id > 0){
			if(isset($list_dep_area[$department_id])) {
				$list_dep_area[$department_id]["is_not_area"] = 0;	
				$lstDepartment[$department_id] = $list_dep_area[$department_id];
			}else{
				$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
				$lstDepartment[$department_id]["is_not_area"] = 1;
			}			
		}else{
			$lstDepartment = $list_dep_area;
		}
	} else if($clsISO->checkPermissionGroup("BUSINESS_AREA"))  {	
		$department_id = (int) Input::post('department_id',0);
		$department_id = !empty($department_id) ? $department_id : $oneProfile['department_id'];
		if(isset($list_dep_area[$department_id])) {
			$list_dep_area[$department_id]["is_not_area"] = 0;
			$lstDepartment[$department_id] = $list_dep_area[$department_id];
		}else{
			$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
			$lstDepartment[$department_id]["is_not_area"] = 1;
		}	
				
	}else if($clsISO->checkPermissionGroup("SALE_DIRECTOR")){
		$department_id = !empty($oneProfile['department_id']) ? $oneProfile['department_id'] : 0;
		$lstDepartment[$department_id] = $arr_deparment_cached[$department_id];
		$lstDepartment[$department_id]["is_not_area"] = 1;

	}
//	$clsISO->print_pre($list_dep_area);die;
	
	$has_target = [];
	foreach ($lstDepartment as $dep_area_id => $_oDepArea) {
		$more_area = $_oDepArea["more_information"];
		$target_area = !empty($more_area["target_sales"]) ?  $more_area["target_sales"] : [];
		if(!empty($target_area[$my])){
			
			$lstDepartment[$dep_area_id]["target_quantity"] = $target_area[$my]["quantity"];
			$lstDepartment[$dep_area_id]["target_amount"] = $core->get_money_field($target_area[$my], "amount", 0);
		}else{
			$lstDepartment[$dep_area_id]["target_quantity"] = 0;
			$lstDepartment[$dep_area_id]["target_amount"] = 0;
		}
		$is_business_area = !empty($more_area["is_business_area"]) ? $more_area["is_business_area"] : 0;
		$lstDepartment[$dep_area_id]["is_business_area"] = $is_business_area;
		$achieved_quantity_area = $achieved_amount_area = 0;
//		$target_area_quantity = 
//		$clsISO->print_pre($_oDepArea);die;
		$total_price_area = $total_share_area = 0;		
		$listStaff = $clsProfile->getProfileDep($dep_area_id, 0, "active");
		$list_billing = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`='0' AND `staff_id` in (".implode(',', array_keys($listStaff)).") AND (`deposit_date` between '{$start_time}' and '{$end_time}') GROUP BY `staff_id`","staff_id, SUM(totalgrand) as total_grand, COUNT(`staff_id`) as total_billing");
		$arr_staff_billing = [];
		foreach ($list_billing as $key => $val) {
			$arr_staff_billing[$val["staff_id"]] = $val;
		}		
		if (!empty($listStaff)) {
			foreach($listStaff as $key => $val){
				$more_information = $val["more_information"];
				$listStaff[$key]['role_name'] = $more_information["role_name"];
				$listStaff[$key]['department_name'] = $more_information["department_name"];
				$target_sales = !empty($more_information["target_sales"]) ?  $more_information["target_sales"] : [];
				if(!empty($target_sales[$my])){
					$quantity = $target_sales[$my]["quantity"];
					$amount = (int)$core->get_money_field($target_sales[$my], "amount", 0);
					$listStaff[$key]["target_quantity"] = $quantity;
					$listStaff[$key]["target_amount"] = $amount;
					$order = 1;
				}else{
					$listStaff[$key]["target_quantity"] = 0;
					$listStaff[$key]["target_amount"] = 0;
					$order = 2;
				}
				if(!empty($arr_staff_billing[$val[$clsProfile->pkey]])) {
					$achieved_quantity = $arr_staff_billing[$val[$clsProfile->pkey]]["total_billing"];
					$achieved_amount = (int)$arr_staff_billing[$val[$clsProfile->pkey]]["total_grand"];
					$listStaff[$key]["achieved_quantity"] = $achieved_quantity;
					$listStaff[$key]["achieved_amount"] = $achieved_amount;
					$achieved_quantity_area += $achieved_quantity;
					$achieved_amount_area += $achieved_amount;
				}else{
					$listStaff[$key]["achieved_quantity"] = 0;
					$listStaff[$key]["achieved_amount"] = 0;
				}
				$listStaff[$key]["target_quantity_rate"] = !empty($quantity) ? ceil($achieved_quantity*100/$quantity,1)."%" : "--";
				$listStaff[$key]["target_amount_rate"] = !empty($amount) ? ceil($achieved_amount*100/$amount,1)."%" : "--";
				if($clsISO->checkHeadSale($val["role_id"])){
					$order_no = 0;
				}				
			}
			$arr_orders = array_column($listStaff, 'order_no');
			array_multisort($arr_orders, SORT_ASC, $listStaff);
//			$clsISO->print_pre($listStaff);die;
			$lstDepartment[$dep_area_id]["listStaff"] = $listStaff;
		}
		if(empty($_oDepArea["is_not_area"])) {
			$lst_dep = $_oDepArea["children"];
			if(!empty($lst_dep)) {
				foreach ($lst_dep as $dep_id => $_oDep){
					$achieved_quantity_dep = $achieved_amount_dep = 0;
					$more_dep = $_oDep["more_information"];
					$target_dep = !empty($more_dep["target_sales"]) ?  $more_dep["target_sales"] : [];
					if(!empty($target_dep[$my])){
						$lst_dep[$dep_id]["target_quantity"] = $target_dep[$my]["quantity"];
						$lst_dep[$dep_id]["target_amount"] =  $core->load_report_share($target_dep[$my], "amount", 0);
					}else{
						$lst_dep[$dep_id]["target_quantity"] = 0;
						$lst_dep[$dep_id]["target_amount"] = 0;
					}
					$listStaff = $clsProfile->getProfileDep($dep_id, 0, "active");
					$list_billing = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`='0' AND `staff_id` in (".implode(',', array_keys($listStaff)).") AND (`deposit_date` between '{$start_time}' and '{$end_time}') GROUP BY `staff_id`","staff_id, SUM(totalgrand) as total_grand, COUNT(`staff_id`) as total_billing");
					$arr_staff_billing = [];
					foreach ($list_billing as $key => $val) {
						$arr_staff_billing[$val["staff_id"]] = $val;
					}		
					if (!empty($listStaff)) {
						foreach($listStaff as $key => $val){
							$more_information = $val["more_information"];
							
							$listStaff[$key]['role_name'] = $more_information["role_name"];
							$listStaff[$key]['department_name'] = $more_information["department_name"];
							$target_sales = !empty($more_information["target_sales"]) ?  $more_information["target_sales"] : [];
							if(!empty($target_sales[$my])){
								$listStaff[$key]["target_quantity"] = $target_sales[$my]["quantity"];
								$listStaff[$key]["target_amount"] = $core->get_money_field($target_sales[$my], "amount", 0);
								$order = 1;
							}else{
								$listStaff[$key]["target_quantity"] = 0;
								$listStaff[$key]["target_amount"] = 0;
								$order = 2;
							}
							if(!empty($arr_staff_billing[$val[$clsProfile->pkey]])) {								
								$achieved_quantity = $arr_staff_billing[$val[$clsProfile->pkey]]["total_billing"];
								$achieved_amount = (int)$arr_staff_billing[$val[$clsProfile->pkey]]["total_grand"];
								$listStaff[$key]["achieved_quantity"] = $achieved_quantity;
								$listStaff[$key]["achieved_amount"] = $achieved_amount;
								$achieved_quantity_area += $achieved_quantity;
								$achieved_amount_area += $achieved_amount;
								$achieved_quantity_dep += $achieved_quantity;
								$achieved_amount_dep += $achieved_amount;
							}else{
								$listStaff[$key]["achieved_quantity"] = 0;
								$listStaff[$key]["achieved_amount"] = 0;
							}
							if($clsISO->checkHeadSale($val["role_id"])){
								$order_no = 0;
							}
						}
						$arr_orders = array_column($listStaff, 'order_no');
						array_multisort($arr_orders, SORT_ASC, $listStaff);
						$lst_dep[$dep_id]["listStaff"] = $listStaff;
					}
					$lst_dep[$dep_id]["achieved_quantity"] = $achieved_quantity_dep;
					$lst_dep[$dep_id]["achieved_amount"] = $achieved_amount_dep;
				}
			}
			$lstDepartment[$dep_area_id]["department_child"] = $lst_dep;
		}	
		$lstDepartment[$dep_area_id]["achieved_quantity"] = $achieved_quantity_area;
		$lstDepartment[$dep_area_id]["achieved_amount"] = $achieved_amount_area;
//		$clsISO->print_pre($lstDepartment[$dep_area_id]);die;
	}
	$smarty->assign('lstDepartment',$lstDepartment);
	
	// Return
	$html = $core->build('_ajax.target_sales.tpl');
	echo json_encode([
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_load_KPI(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsLog = new Log();
	$clsShare = new Share();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsAttendance = new Attendance();
	// $date_type = Input::post('date_type', "_month");
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$department_id = (int) Input::post("department_id", 0);	
	$title_page = '<h3 class="card-title mb-1">Kết quả kinh doanh</h3>';
	$lstProfile = $clsProfile->getProfileCached("active");
	if(!empty($department_id) && $clsISO->_DEV()){
		$staff_direction_dep = $clsProperty->getStaffDirectorDep($department_id);
		$head_of_dep_id = $staff_direction_dep["head_of_dep_id"];
		$oProfileHead = $clsProfile->getProfile($head_of_dep_id,$lstProfile[$head_of_dep_id]);
		$more_profile = $oProfileHead["more_information"];
		if(!empty($staff_direction_dep["is_regional"])) {
			$title_page = '<h3 class="card-title mb-1">Kết quả KD '.$more_profile["department_name"].'</h3>
						<p class="text-dark mb-0 txt_time">GĐ Vùng: <span class="text-main fw-bold">'.$oProfileHead["name"].'</span></p>';
		}else{
			$title_page = '<h3 class="card-title mb-1">Kết quả KD phòng '.$more_profile["department_name"].'</h3>
						<p class="text-dark mb-0 txt_time">GĐKD: <span class="text-main fw-bold">'.$oProfileHead["name"].'</span></p>';
		}
	}
	$department_id = ($department_id > 0) ? $department_id : _DEPARTMENT_SALE_ID;
	$sort_by = Input::post('sort_by', 'target_achieved_amount');
	$sort_type = Input::post('sort_type', 'desc');
	#
	$arr_target_time = [];
	if($month > 0){
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$start_time = strtotime(sprintf('01-%s-%s', $month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end_time = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
		$arr_target_time[] = sprintf('%s_%s', $month, $year);
		$sql_attendance = " AND FROM_UNIXTIME(`work_date`,'%m/%Y')='{$my}'";
		
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
		$start_time = strtotime(sprintf('01-01-%s', $year));
		$end_time = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, 12, $year));
		for($m=1; $m <= 12; $m++) {
			$arr_target_time[] = sprintf('%s_%s', $m, $year);
		}
		$sql_attendance = " AND FROM_UNIXTIME(`work_date`,'%Y')='{$year}'";
	}
	$end_time = ($end_time > time()) ? time() : $end_time;
	#
	$txt_time = sprintf("Báo cáo kết quả từ %s đến %s",date("d/m/Y",$start_time),date("d/m/Y",$end_time));
	
	$list_staffs = $clsProfile->getProfileDep($department_id, 1, "active");
	$arr_staffs_ids = !empty($list_staffs) ? array_keys($list_staffs) : [];
	// Giao dịch
	$arr_staffs_billing = array();
	$field = "`staff_id`, SUM(`totalgrand`) as `total_grands`, COUNT(`staff_id`) as `total_billings`";
	$list_billings = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`='0' AND `staff_id` IN ('".implode('\',\'',$arr_staffs_ids)."') 
		AND (`deposit_date` BETWEEN '{$start_time}' AND '{$end_time}') GROUP BY `staff_id`", $field);
	if(!empty($list_billings)){
		foreach ($list_billings as $key => $val) {
			$arr_staffs_billing[$val["staff_id"]] = $val;
		}
		unset($list_billings);
	}
	#share
	$total_share = 0; $arr_users_share = [];
	$field = "`user_id`,COUNT(1) AS `total`";
	$list_shares = $clsShare->getAll("`share_type`='share' AND `user_id` in ('".implode('\',\'', $arr_staffs_ids)."') 
		AND (`reg_date` between '{$start_time}' and '{$end_time}') GROUP BY `user_id`", $field);
	if(!empty($list_shares)){
		foreach ($list_shares as $key => $val) {
			$arr_users_share[$val["user_id"]] = $val["total"];
			$total_share += (int) $val["total"];
		}
		unset($list_shares);
	}
	#search
	$arr_users_search = []; $total_search = 0;
	$list_logs = $clsLog->getALl("from_site='_user' AND `user_id` IN ('".implode('\',\'', $arr_staffs_ids)."')  AND (`type`='search' OR `type`='view_stock') AND (`reg_date` BETWEEN {$start_time} AND {$end_time}) GROUP BY `user_id`" ,"`user_id`,COUNT(`{$clsLog->pkey}`) AS `total`" );
	if(!empty($list_logs)){
		foreach($list_logs as $key => $val) {
			$arr_users_search[$val["user_id"]] = $val["total"];
			$total_search += (int) $val["total"];
		}
	}
	$total_target_quantity = $total_target_amount = $total_target_quantity_rate = $total_target_amount_rate = 0;
	$total_achieved_quantity = $total_achieved_amount = 0;
	if(!empty($list_staffs)) {
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$more_information = $val["more_information"];
			$role_name = $core->get_field($more_information, "role_name", "");
			$department_name = $core->get_field($more_information, "department_name", "");
			$list_staffs[$key]['role_name'] = $role_name;
			$list_staffs[$key]['department_name'] = $department_name;
			$social_channels = $core->get_field($more_information, "social_channels", "");
			$list_staffs[$key]['total_social'] = !empty($social_channels) ? count($social_channels) : 0;
			// Tổng công
			$total_work_unit = $clsAttendance->sumItem("work_unit", "`staff_id`='{$staff_id}'".$sql_attendance);
			// Mục tiêu
			$target_quantity = $target_amount = 0;
			$target_amount_rate = $target_quantity_rate = 0;
			$target_achieved_amount = $target_achieved_quantity = 0;
			$target_sales = $core->get_field($more_information, "target_sales", []);
			if(!empty($arr_target_time)){
				foreach($arr_target_time as $my){
					if(isset($target_sales[$my]) && !empty($target_sales[$my])){
						$quantity = (int) $target_sales[$my]["quantity"];
						$amount = (float) $core->get_money_field($target_sales[$my], "amount", 0);
						$target_quantity += $quantity;
						$target_amount += $amount;
					} else {
						$quantity = 1;
						$amount = 3000000000;
						$target_quantity += $quantity;
						$target_amount += $amount;
					}
				}
			}
			$list_staffs[$key]["target_amount"] = $target_amount;
			$list_staffs[$key]["target_quantity"] = $target_quantity;
			if(!empty($arr_staffs_billing) && isset($arr_staffs_billing[$staff_id])){
				$target_achieved_amount = (float) $arr_staffs_billing[$staff_id]['total_grands'];
				$target_achieved_quantity = (int) $arr_staffs_billing[$staff_id]['total_billings'];
				$target_quantity_rate = ($target_quantity > 0) ? round($target_achieved_quantity / $target_quantity, 2) * 100 : 0;
				$target_amount_rate = $target_amount > 0 ? round($target_achieved_amount / $target_amount, 2) * 100 : 0;
			}
			$list_staffs[$key]["target_achieved_amount"] = $target_achieved_amount;
			$list_staffs[$key]["target_achieved_quantity"] = $target_achieved_quantity;
			$list_staffs[$key]["target_quantity_rate"] = $target_quantity_rate;
			$list_staffs[$key]["target_amount_rate"] = $target_amount_rate;
			$list_staffs[$key]["total_work_unit"] = $total_work_unit;
			$list_staffs[$key]["total_share"] = isset($arr_users_share[$staff_id]) ? $arr_users_share[$staff_id] : 0;
			$list_staffs[$key]["total_search"] = isset($arr_users_search[$staff_id]) ? $arr_users_search[$staff_id] : 0;
		}
		// $total_target_quantity_rate = !empty($total_target_quantity) ? round($total_achieved_quantity*100/$total_target_quantity)."%" : "";
		// $total_target_amount_rate = !empty($total_target_amount) ? round($total_achieved_amount*100/$total_target_amount)."%" : "";
		$sort = ($sort_type == 'desc') ? SORT_DESC : SORT_ASC;
		$arr_orders = array_column($list_staffs, $sort_by);
		array_multisort($arr_orders, $sort, $list_staffs);
	}
	$smarty->assign('total_target_quantity',$total_target_quantity);
	$smarty->assign('total_target_amount',$total_target_amount);
	$smarty->assign('total_achieved_quantity',$total_achieved_quantity);
	$smarty->assign('total_achieved_amount',$total_achieved_amount);
	$smarty->assign('total_target_quantity_rate',$total_target_quantity_rate);
	$smarty->assign('total_target_amount_rate',$total_target_amount_rate);
	$smarty->assign('total_share',$total_share);
	$smarty->assign('total_search',$total_search);
	$smarty->assign('list_staffs',$list_staffs);
	// Return
	$html = $core->build('_ajax.loadKPI.tpl');
	echo json_encode([
		"html"	=> $html,
		"txt_time"	=> $txt_time,
		"title_page"	=> $title_page,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_load_social_profile(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	##
	$user_id = (int) Input::get('user_id', 0);
	$field = "`phone`,`email`,`role_id`,`status_id`,`department_id`,`more_information`,`start_date`";
	$oProfile = $clsProfile->getOne($user_id, $field);
	$more_information = $oProfile['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$social_channels = !empty($more_information["social_channels"]) ? $more_information["social_channels"] : [];
	if(!empty($social_channels)) {
		$html = '<div clas="profile-wrap"><div class="row g-2">';
		foreach($social_channels as $key => $val) {
			if($val["category"] == "facebook") {
				$icon = "bxl-facebook";
				$_sc_label = "Facebook";
				$_txt_color = "text-primary";
			}elseif($val["category"] == "tiktok") {
				$icon = "bxl-tiktok";
				$_sc_label = "TikTok";
				$_txt_color = "text-dark";
			}elseif($val["category"] == "youtube") {
				$icon = "bxl-youtube";
				$_sc_label = "Youtube";
				$_txt_color = "text-danger";
			}elseif($val["category"] == "fanpage") {
				$icon = "bxl-facebook-square";
				$_sc_label = "Fanpage";
				$_txt_color = "text-primary";
			}			
			$html .= '<div class="col-12">
						<a href="'.$val["link"].'" target="_blank" class="d-flex align-items-center gap-1 px-2 py-1 border rounded text-decoration-none">
							<i class="bx '.$icon.' fs-4 flex-shrink-0 '.$_txt_color.'"></i>
							<div class="flex-grow-1 overflow-hidden">
								<div class="fw-medium small">'.(!empty($val["title"]) ? $val["title"] : $_sc_label).'</div>
								<div class="text-muted text-truncate" style="font-size:11px">'.$val["link"].'</div>
							</div>
						</a>
					</div>';
		}
		$html.='</div></div>';
	}
	// Return
	echo $html; die();
}
function default_load_report_person(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProperty = new Property();
	$clsMember = new Member();
	$clsBilling = new Billing();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$group_id = (int)Input::post('group_id', _PROFILE_DEFAULT_GROUP_ID);
	$department_id = (int)Input::get('department_id', 0);
	
	$month = Input::post('month',date("Y-m"));
	$start_date = date('Y-m-d 00:00:00', strtotime($month . '-01'));
	$end_date = date('Y-m-d 23:59:59', strtotime('last day of ' . $month));
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	$start_time_prev = strtotime("-1 months", $start_time);
	$month_prev = date("Y-m",$start_time_prev);
	$end_date_prev = date('Y-m-d 23:59:59', strtotime('last day of ' . date("Y-m-d",$start_time_prev)));
	
	$end_time_prev = strtotime($end_date_prev);
	$total_sale_active = $total_sale_new = $total_sale_out = 0;
	$total_sale_active_prev = $total_sale_new_prev = $total_sale_out_prev = 0;
	
	$list_profile = $clsProfile->getProfileDep($department_id, 1);	
//	$clsISO->print_pre($list_profile);die;
	foreach ($list_profile as $key => $val) {
		if(date("Y-m",$val["start_date"]) == $month) {
			$val["start_date"] = date("d/m/Y",$val["start_date"]);
			++$total_sale_new;
		}
		if(date("Y-m",$val["end_date"]) == $month) {
			$val["start_date"] = date("d/m/Y",$val["start_date"]);
			$val["end_date"] = date("d/m/Y",$val["end_date"]);
			$clsISO->print_pre($val);
			++$total_sale_out;
		}
		if(($val["end_date"] > $end_time || $val["end_date"] == 0) && date("Y-m",$val["end_date"]) != $month) {
			++$total_sale_active;
		}
		if(date("Y-m",$val["start_date"]) == $month_prev) {
			++$total_sale_new_prev;
		}
		if(date("Y-m",$val["end_date"]) == $month_prev) {
			++$total_sale_out_prev;
		}
		if(($val["end_date"] > $end_time_prev || $val["end_date"] == 0) && date("Y-m",$val["end_date"]) != $month_prev) {
//			var_dump($val["end_date"] > $end_time_prev );
//				var_dump(date("Y-m",$val["end_date"]),$val["end_date"],$month_prev);
			/*if($val["profile_id"] == 1039) {
				echo date("d/m/Y",$val["end_date"])."-".date("d/m/Y",$end_time_prev);die;
				var_dump($val["end_date"] > $end_time_prev );
				var_dump(date("Y-m",$val["end_date"]),$val["end_date"],$month_prev);
				$clsISO->print_pre($val);die;
			}*/
			
			++$total_sale_active_prev;
		}
	}
	die;
//	var_dump($total_sale_active, $total_sale_new, $total_sale_out);
//	echo "----------<br>";
//	var_dump($total_sale_active_prev, $total_sale_new_prev, $total_sale_out_prev);die;
	$smarty->assign('total_sale_active_prev', $total_sale_active_prev);
	$smarty->assign('total_sale_new_prev', $total_sale_new_prev);
	$smarty->assign('total_sale_out_prev', $total_sale_out_prev);
	$smarty->assign('total_sale_active', $total_sale_active);
	$smarty->assign('total_sale_new', $total_sale_new);
	$smarty->assign('total_sale_out', $total_sale_out);
	// Return
	$html = $core->build('_ajax.load_report_person.tpl');
	echo json_encode(array("html" => $html),JSON_UNESCAPED_UNICODE); die();
}
function default_view_staff(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG');
	$list_staffs = Input::post('list_staffs');
	if($holderG == 'search') $titlePage = "Nhân sự chưa tra cứu";
	if($holderG == 'client_meeting') $titlePage = "Nhân sự chưa tiếp khách";
	if($holderG == 'billing') $titlePage = "Nhân sự chưa có giao dịch";
	$html= '<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">'.$titlePage.'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table">';
				if(!empty($list_staffs)){ $ii = 0;
					$arr_profile_cached = $clsProfile->getProfileCached();
					foreach($list_staffs as $staff_id){
						$html.= '<tr>
							<td width="5%" class="text-center">'.($ii+1).'</td>
							<td>'.$clsProfile->getIndentityV5($staff_id, $arr_profile_cached[$staff_id], "", "xs").'</td>
						</tr>';
						++$ii;
					}
				}
			$html.= '</table>
			</div>
			<div class="modal-footer">
				<button data-toggle="ripple" type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Hủy bỏ</button>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_report_top_search(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile,$profile_id;
	$clsLog  = new Log();
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$cond= "(`type` IN ('".implode('\',\'', ['search', 'view_stock'])."')) AND `from_site`='_user' AND `user_id` IN (
		SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
		WHERE `status_id` <>'"._STATUS_STAFF_OFF_ID."' AND `list_department_id` LIKE '%|"._DEPARTMENT_SALE_ID."|%'
	)";
	$start_time = strtotime(date(("Y-m-01")));	
	$end_time = strtotime(date(("Y-m-t 23:59:59")));
	$cond.= " AND (`reg_date` between {$start_time} AND {$end_time})";
	$field = "`user_id`,COUNT(`user_id`) AS `total_search`";
	// $dbconn->debug = true;
	$list_logs = $clsLog->getAll("{$cond} GROUP BY `user_id` ORDER BY `total_search` DESC LIMIT 0,10", $field);	
	$lstProfile = $clsProfile->getProfileCached("active");
	foreach ($list_logs as $key => $val) {
		$oProfile = $lstProfile[$val["user_id"]];
		$more_information = $oProfile["more_information"];
		$department_name = $core->get_field($more_information, "department_name", "");
		$oProfile["department_name"] = $department_name;
		$list_logs[$key]["oProfile"] = $oProfile;
		if(date("n") == 2) {
			$list_logs[$key]["total_search"] = $val["total_search"] + 200;
		}
		unset($oProfile);
	}
//	$clsISO->print_pre($list_logs);die;
	$smarty->assign("list_logs",$list_logs);	
	// Return
	$html = $core->build('_ajax.report_top_search.tpl');
	echo json_encode([
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_load_report_block_top_search(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile,$profile_id;
	$clsLog  = new Log();
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsStock  = new Stock();
	#
	$cond= "(`type`='search' OR `type`='view_stock') AND `from_site`='_user' AND `user_id` IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` WHERE `status_id` <>'"._STATUS_STAFF_OFF_ID."' AND `list_department_id` LIKE '%|"._DEPARTMENT_SALE_ID."|%')";
	$start_time = strtotime(date(("Y-m-01")));	
	$end_time = time();
	
	$lstLog = $dbconn->getAll("SELECT count(`t1`.`stock_id`) AS `total_stocks`,`t2`.`block_id` FROM {$clsLog->tbl} AS `t1` 
		INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`stock_id`=`t2`.`stock_id` WHERE `t1`.`type` IN ('".implode('\',\'',['view','view_stock','search'])."') AND `t1`.`stock_id`<>'0' and (`t1`.`reg_date` BETWEEN {$start_time} AND {$end_time}) GROUP BY `t2`.`block_id` ORDER BY `total_stocks` DESC");
			
	$arr_log = [];
	$ii = $total_MSQ = $total_lowfloor = 0;
	$list_blocks = array(
		_PROJECT_BLOCK_SLC_ID => 'Sunshine Legend City', 
		_PROJECT_BLOCK_LSB_ID => 'Lumière Spring Bay', 
		_PROJECT_BLOCK_MTS_ID => 'Masteri Trinity Square', 
		_PROJECT_BLOCK_LOP_ID => 'Lumière Orial Pearl', 
		'_BLOCK_TYPE_LOWFLOOR_SALE' => 'Thấp tầng'
	);
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	foreach ($lstLog as $key => $val) {
		if(isset($lstBlock[$val["block_id"]])) {
			$more_block = $lstBlock[$val["block_id"]]["more_information"];
			if(!empty($more_block["on_sale"])) {
				$total_stocks = !empty($val) ? $val['total_stocks']+20 : 0;
				if($lstBlock[$val["block_id"]]["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE) {
					$total_lowfloor += $total_stocks;
				}else{
					if($clsISO->checkItemInArray($val["block_id"],_PROJECT_BLOCK_MSQ_ARRAY)) {
						$total_MSQ += $total_stocks;
					}else{
						$arr_log[] = [
							"total"	=>	$total_stocks,
							"block_name"	=>	!empty($list_blocks[$val["block_id"]]) ? $list_blocks[$val["block_id"]] : $lstBlock[$val["block_id"]]["title"],
						];
					}
				}
			}							
		}
	}
	if(!empty($total_MSQ)) {
		$arr_log[] = [
			"total"	=>	$total_MSQ,
			"block_name"	=>	"Sky Quarter",
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
	$smarty->assign("start_time",$start_time);	
	$smarty->assign("arr_log",$arr_log);	
	// Return
	$html = $core->build('_ajax.report_block_top_search.tpl');
	echo json_encode([
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function default_sales_agent(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile,$profile_id;
	#
	$list_preloaders = array();
	for($i=0; $i<25; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	#
	$list_months = $list_years = array();
	for($i=2024; $i <= date('Y'); $i++){
		$list_years[] = $i;
	}
	for($i=1; $i <= date('n'); $i++){
		$list_months[] = $i;
	}
	$assign_list["list_months"] = $list_months;
	$assign_list["list_years"] = $list_years;
	/*=============Title & Description Page==================*/
	$title_page = 'Đại lý bán hàng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_sales_agent(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$clsProfile,$oneProfile,$profile_id;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	#
	$sql_query = "";
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$sold_type = Input::post('sold_type', 'partner_development');
	#
	if($month > 0 && $year >= 0){
		if($year == 0) $year = date('Y');
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$sql_query.= " AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$my}'";
	} else if($month == 0 && $year > 0) {
		$sql_query.= " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	#
	$field = "t1.`stock_code`,`t1`.`deposit_date`,`t1`.`totalgrand`,`t2`.`property_id`,`t2`.`title`";
	// $dbconn->debug = true;
	$list_billings = array();
	if($sold_type == 'partner_development'){
		$tmp = $dbconn->getAll("SELECT {$field}  FROM {$clsBilling->tbl} AS `t1` 
			JOIN {$clsProperty->tbl} AS `t2` 
				ON JSON_EXTRACT(`t1`.`more_information`,\"$.sale_agency_id\")=`t2`.`property_id` AND `t2`.`property_type`='_AGENCY' 
			WHERE `t1`.`is_cancel`=0 AND `t2`.`property_id`<>'"._AGENCY_FH_ID."'".$sql_query);
	} else {
		$tmp = $dbconn->getAll("SELECT {$field}  FROM {$clsBilling->tbl} AS `t1` 
		JOIN {$clsProperty->tbl} AS `t2` 
			ON JSON_EXTRACT(`t1`.`more_information`,\"$.stock_resource\")=`t2`.`property_id` AND `t2`.`property_type`='_AGENCY' 
		WHERE `t1`.`is_cancel`=0 AND `t1`.`billing_source_id`='"._BILLING_RESOURCE_CROSS_ID."' AND JSON_EXTRACT(`t1`.`more_information`,\"$.sale_agency_id\")='"._AGENCY_FH_ID."' AND `t2`.`property_id`<>'"._AGENCY_FH_ID."'".$sql_query);
	}
	$total_billings = $total_sales = 0;
	if(!empty($tmp)){
		$sorted_arrs = array();
		foreach($tmp as $key => $val){
			$property_id = $val['property_id'];
			if(array_key_exists($property_id, $sorted_arrs)){
				$sorted_arrs[$property_id]['oList'][] = $val;
			} else {
				$sorted_arrs[$property_id] = array(
					'title' => $val['title'],
					'oList' => array($val)
				);
			}
		}
		foreach($sorted_arrs as $key => $val){
			$oList = $val['oList'];
			$total_in_billings = count($oList);
			$total_billings += $total_in_billings;
			$total_in_sales = 0; 
			$html_content = '<div class=\'table-container no-shadow\'>
				<table cellpadding=\'0\' cellspacing=\'0\' class=\'table table-bordered mb-0 text-nowrap\'>
					<thead><tr>
						<th class=\'bg-lighter\'>Mã căn</th>
						<th class=\'bg-lighter text-right\'>Ngày chốt</th>
						<th class=\'bg-lighter text-right\'>DS</th>
					</tr></thead>';
				$deposit_date = $oList[0]['deposit_date'];
				foreach($oList as $okey => $oval){
					if($oval['deposit_date'] > $deposit_date){
						$deposit_date = $oval['deposit_date'];
					}
					$total_in_sales += $clsISO->processSmartNumber($oval['totalgrand']);
					$total_sales += $clsISO->processSmartNumber($oval['totalgrand']);
					$html_content.= '<tr>
						<td>'.$oval['stock_code'].'</td>
						<td class=\'text-right\'>'.$clsISO->convertTimeToText($oval['deposit_date']).'</td>
						<td class=\'text-right\'>'.$clsISO->shortNumber($oval['totalgrand']).'</td>
					</tr>';
				}
				$html_content.= '</table>
				</div>';
			$list_billings[] = array(
				'title' => $val['title'],
				'deposit_date'	=> $deposit_date,
				'total_in_billings' => $total_in_billings,
				'total_in_sales' => $total_in_sales,
				'html_content' => $html_content
			);
		}
		$order = array_column($list_billings, 'total_in_billings');
		array_multisort($order, SORT_DESC, $list_billings);
	}
	// $clsISO->print_pre($list_billings); die();
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('total_sales', $total_sales);
	// Return
	$html = $core->build('_ajax.sales_agent.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_report_agent(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProperty = new Property();
	$clsStock = new Stock();
	#
	$list_blocks = array(
		_PROJECT_BLOCK_MGC_ID => 'MGC',
		_PROJECT_BLOCK_MEL_ID => 'MEL',
		_PROJECT_BLOCK_LEK_ID => 'LEK',
		_PROJECT_BLOCK_MSQ_ID => 'RSQ',
		_PROJECT_BLOCK_TGC_ID => 'TGC',
		_PROJECT_BLOCK_LSB_ID => 'LSB',
	);
	#
	$list_preloaders = array();
	for($i=1; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_blocks"] = $list_blocks;
	$assign_list["list_preloaders"] = $list_preloaders;
	// $clsISO->print_pre($list_agents); die();
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo quỹ đại lý - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report_agent(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProperty = new Property();
	$clsStock = new Stock();
	#
	$list_agents = array();
	$sort_type = Input::post('sort_type', 'desc');
	$sort_by = Input::post('sort_by', 'total_all');
	#
	$total_alls = $total_mas = $total_mik = $total_sun = $total_alu = $total_lsb = $total_vin = $total_np = 0;
	$total_mgc = $total_mel = $total_lek = $total_msq = $total_tgc = $total_lop = $total_alc = 0;
	$field = "{$clsProperty->pkey},`title`";
	$arr_agents = $clsProperty->getAll("`property_type`='_AGENCY'", $field);
	if(!empty($arr_agents)){
		foreach($arr_agents as $key => $val){
			$list_agents[$val[$clsProperty->pkey]] = array(
				'title' => preg_replace('/^[0-9]+\s*-\s*/', '', $val['title']),
				'total_mgc' => 0,
				'total_mel' => 0,
				'total_lek' => 0,
				'total_msq' => 0,
				'total_tgc' => 0,
				'total_lsb' => 0,
				'total_mik' => 0,
				'total_sun' => 0,
				'total_alu' => 0,
				'total_lop' => 0,
				'total_vin' => 0,
				'total_alc' => 0,
				'total_np'  => 0,
				'total_all' => 0
			);
		}
		// $dbconn->debug = true;
		$arr_blocks = array_merge(
			_PROJECT_BLOCK_MAS_ARRAY, 
			_PROJECT_BLOCK_MIK_ARRAY, 
			_PROJECT_BLOCK_SUNSHINE_ARRAY, 
			_PROJECT_BLOCK_ALUMI_ARRAY
		);
		$arr_projects = array(
			_PROJECT_VHOP2_ID,
			_PROJECT_VHOP3_ID,
			_PROJECT_ALC_ID,
			_PROJECT_NP_ID
		);
		// $dbconn->debug = true;
		$list_stocks = $clsStock->getAll("IF(`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."',`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `block_id` IN ('".implode('\',\'', $arr_blocks)."'),`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND `project_id` IN ('".implode('\',\'',$arr_projects)."')) AND `agency_id`>0 AND `status_id`>0 AND `status_id` NOT IN('".implode('\',\'', [_STOCK_STATUS_NON_ID, _STOCK_STATUS_SOLD_ID])."')", "`stock_type`,`block_id`,`agency_id`,`project_id`");
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$agency_id = (int) $val['agency_id'];
				$block_id = (int) $val['block_id'];
				$project_id = (int) $val['project_id'];
				$stock_type = (int) $val['stock_type'];
				if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
					if(in_array($project_id, [_PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID])){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_vin += 1;
							$list_agents[$agency_id]['total_vin'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($project_id == _PROJECT_ALC_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_alc += 1;
							$list_agents[$agency_id]['total_alc'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($project_id == _PROJECT_NP_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_np  += 1;
							$list_agents[$agency_id]['total_np']  += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					}
				} else if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
					if(in_array($block_id, _PROJECT_BLOCK_MIK_ARRAY)){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_mik += 1;
							$list_agents[$agency_id]['total_mik'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if(in_array($block_id, _PROJECT_BLOCK_SUNSHINE_ARRAY)){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_sun += 1;
							$list_agents[$agency_id]['total_sun'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if(in_array($block_id, _PROJECT_BLOCK_ALUMI_ARRAY)){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_alu += 1;
							$list_agents[$agency_id]['total_alu'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($block_id == _PROJECT_BLOCK_MGC_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_mgc += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_mgc'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($block_id == _PROJECT_BLOCK_LOP_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_lop += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_lop'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($block_id == _PROJECT_BLOCK_MEL_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_mel += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_mel'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($block_id == _PROJECT_BLOCK_LEK_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_lek += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_lek'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if($block_id == _PROJECT_BLOCK_LSB_ID){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_lsb += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_lsb'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					}  else if(in_array($block_id, [_PROJECT_BLOCK_RQ_ID, _PROJECT_BLOCK_PQ_ID])){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_msq += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_msq'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} else if(in_array($block_id, [_PROJECT_BLOCK_TGC_ID, _PROJECT_BLOCK_LM_ID, _PROJECT_BLOCK_MCC_ID])){
						if($agency_id > 0 && isset($list_agents[$agency_id])){
							$total_all += 1;
							$total_tgc += 1;
							$total_mas += 1;
							$list_agents[$agency_id]['total_tgc'] += 1;
							$list_agents[$agency_id]['total_all'] += 1;
						}
					} 
				}
			}
		}
	}
	$order_arrs = @array_column($list_agents, $sort_by);
	// $clsISO->print_pre($order_arrs); die();
	@array_multisort($order_arrs, ($sort_type=='desc' ? SORT_DESC : SORT_ASC), $list_agents);
	$smarty->assign('list_agents', $list_agents);
	// Return
	$html = $core->build('_ajax.report_agent.tpl');
	echo json_encode(array(
		'html' => $html,
		'callback' => '
			$(`.total_all`).html(\'('.$total_all.')\');
			$(`.total_mas`).html(\'('.$total_mas.')\');
			$(`.total_mgc`).html(\'('.$total_mgc.')\');
			$(`.total_mel`).html(\'('.$total_mel.')\');
			$(`.total_lek`).html(\'('.$total_lek.')\');
			$(`.total_lop`).html(\'('.$total_lop.')\');
			$(`.total_lsb`).html(\'('.$total_lsb.')\');
			$(`.total_msq`).html(\'('.$total_msq.')\');
			$(`.total_tgc`).html(\'('.$total_tgc.')\');
			$(`.total_mik`).html(\'('.$total_mik.')\');
			$(`.total_sun`).html(\'('.$total_sun.')\');
			$(`.total_alu`).html(\'('.$total_alu.')\');
			$(`.total_vin`).html(\'('.$total_vin.')\');
			$(`.total_alc`).html(\'('.$total_alc.')\');
			$(`.total_np`).html(\'('.$total_np.')\');'
	)); die();
}
function default_load_sold_stock_7days(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$data = $dataPoints = $barChartData = array();
	$due_time = time();
	$start_time = strtotime("-7 days", $due_time);
	
	$arr_stocks = array();
	$tmp = $clsStock->getAll("`agency_id`>0 AND `status_id`='"._STOCK_STATUS_SOLD_ID."' 
		AND (`ms_date` BETWEEN {$start_time} AND {$due_time})", "ms_date");
	if(!empty($tmp)){

		foreach($tmp as $key => $val){
			$ms_date = $val['ms_date'];
			if(isset($arr_stocks[date('dmY', $ms_date)])){
				$arr_stocks[date('dmY', $ms_date)] += 1;
			} else {
				$arr_stocks[date('dmY', $ms_date)] = 1;
			}
		}
	}
	for($i= $start_time; $i<=$due_time; $i = strtotime("+1 day", $i)){
		$total = $arr_stocks[date('dmY', $i)];
		if ($total >= 50 && $total < 70) {
			$total -= 25;
		} else if ($total >= 71 && $total < 80) {
			$total -= 35;
		} else if ($total >= 80 && $total < 100) {
			$total -= 45;
		} else if ($total >= 100 && $total < 150) {
			$total -= 85;
		} else if ($total >= 150) {
			$total -= 125;
		} 
		$dataPoints[] = array(
			'y' => $total,
			'label' => sprintf('%s', date('d/m', $i)),
			'indexLabel' => (string) $total
		);
	}
	$data['type'] = 'spline';
	// $data['indexLabel'] = '{y}';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = 'Số lượng GD: {y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	
	$html = '<div class="chartContainer p-2 rounded-2">
		<div class="h-px-250 w-100" id="'.$uid.'"></div>
	</div>';
	// Return 
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		//'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function default_report_search_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsLog = new Log();
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo tra cứu dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_search_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$start_date = Input::post("start_date",'');
	$end_date = Input::post("end_date",'');
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
	if($clsISO->checkPermissionGroup('DIRECTOR')) {
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
			$total_stocks = !empty($val) ? $val['total_stocks'] : 0;
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
		}
	$html .= '
			</div>
		</div>
	</div>';
		echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_report_sales_group_CRM(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsProfile;
	$clsMember = new Member();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsShare = new Share();
	$clsLog = new Log();
	$clsCourse = new Course();
	$clsAttendance = new Attendance();
	$clsCustomer = new Customer();
	#
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', "_month");
	$group_id = (int) Input::post('group_id', 0);
	$group_id = $group_id ?: _PROFILE_DEFAULT_GROUP_ID;
	
	$start_date = Input::post('start_time');
	$end_date = Input::post('end_time'); 
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime(sprintf("%s 23:59:59",$end_date)) : 0;
	# Customer
	$sql_customer = "(`reg_date` BETWEEN {$start_time} AND {$end_time})";
	#
	$oneGroup = $clsGroupProfile->getOne($group_id, "list_profile_id");
	$list_profile_id = $oneGroup["list_profile_id"];
	$arr_profile_groups = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
	#
	$arr_satffs = array();
	$arr_staffs_cached = $clsProfile->getProfileCached("active");
	if(!empty($arr_staffs_cached)){
		foreach($arr_staffs_cached as $key => $val){
			if(in_array($val[$clsProfile->pkey], $arr_profile_groups)){
				$arr_satffs[$key] = $val;
				$arr_satffs[$key]['total_customer'] = 0;
				unset($more_information_staff);
			}
		}
	} else {
		$cond = " AND `{$clsProfile->pkey}` IN ('".implode('\',\'',$arr_profile_groups)."')";
		$field = "`{$clsProfile->pkey}`,`code`,`full_name`,`first_name`,`last_name`";
		$arr_satffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'".$cond, $field);
	}
	$total_sales = $total_billings = $total_share = $total_search = $total_post = 0;
	$total_customers = $total_work_units = 0;
	$arr_status_customer_cached = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
	if(!empty($arr_satffs)){
		#- Khách hàng
		$tmp = $clsCustomer->getAll("{$sql_customer} 
			AND `admin_id` IN ('".implode('\',\'',$arr_profile_groups)."') AND `status_id` IN (".implode(',',array_keys($arr_status_customer_cached)).") GROUP BY `admin_id`,`status_id`", "COUNT(`status_id`) AS `total_status`,`admin_id`,`status_id`");
		
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$admin_id = (int) $val['admin_id'];
				if(!empty($arr_satffs[$admin_id]['total_status'][$val["status_id"]])) {
					$arr_satffs[$admin_id]['lst_status'][$val["status_id"]] += $val['total_status'];
				}else{
					$arr_satffs[$admin_id]['lst_status'][$val["status_id"]] = $val['total_status'];
					
				}
				if(!empty($arr_satffs[$admin_id]['total_customer'])) {
					$arr_satffs[$admin_id]['total_customer'] += $val['total_status'];
				}else{
					$arr_satffs[$admin_id]['total_customer'] = $val['total_status'];					
				}	
			}
			unset($tmp);
		}
	}
	$smarty->assign('arr_satffs', $arr_satffs);
	$smarty->assign('arr_status_customer_cached', $arr_status_customer_cached);
	// Return
	$html = $core->build('_ajax.load_report_sales_group_CRM.tpl');
	echo json_encode(array("html" => $html),JSON_UNESCAPED_UNICODE); die();
}

function default_report_checkin(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$clsProfile,$oneProfile,$deviceType;
	global $title_page, $description_page;

	/*=============Phân quyền==================*/
	if(!$clsISO->checkPermissionGroup('DIRECTOR')
		&& !$clsISO->checkPermissionGroup('BO')
		&& !$clsISO->checkPermissionGroup('BUSINESS_AREA')
		&& !$clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		header("Location: /"); exit();
	}

	$clsProperty   = new Property();
	$clsGroupProfile = new GroupProfile();

	/* Cây phòng ban: Vùng = con trực tiếp của _DEPARTMENT_SALE_ID (40); Phòng = con của Vùng */
	$arr_dept = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_regions    = array();   /* [{id,title}] các vùng (DIRECTOR chọn) */
	$region_dept_map = array();   /* vùng_id => [{id,title}] phòng (cascading) */
	foreach($arr_dept as $id => $row){
		if((int)$row['parent_id'] === _DEPARTMENT_SALE_ID){
			$rid = (int) $id;
			$list_regions[]        = array('id'=>$rid, 'title'=>isset($row['title'])?$row['title']:'');
			$region_dept_map[$rid] = array();
		}
	}
	foreach($arr_dept as $id => $row){
		$pid = (int) $row['parent_id'];
		if(isset($region_dept_map[$pid])){
			$region_dept_map[$pid][] = array('id'=>(int)$id, 'title'=>isset($row['title'])?$row['title']:'');
		}
	}

	/* filter_mode + danh sách theo role */
	$list_region_depts = array();   /* REGIONAL: phòng theo vùng quản lý */
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		$filter_mode = 'director'; $role_label = 'Giám đốc';
	} elseif($clsISO->checkPermissionGroup('BUSINESS_AREA')){
		$filter_mode = 'region';   $role_label = 'Giám đốc vùng';
		$my_region = (int) $oneProfile['department_id'];
		$list_region_depts = isset($region_dept_map[$my_region]) ? $region_dept_map[$my_region] : array();
	} else {
		$filter_mode = 'sale';     $role_label = 'Giám đốc kinh doanh';
	}

	/* Nhóm nhân viên (DIRECTOR only) */
	$list_groups = array();
	if($filter_mode === 'director'){
		$list_groups = $clsGroupProfile->getAll("`is_trash`=0 ORDER BY `title` ASC", "group_profile_id,title");
	}

	$assign_list['filter_mode']          = $filter_mode;
	$assign_list['list_regions']         = $list_regions;
	$assign_list['region_dept_map_json'] = json_encode($region_dept_map, JSON_UNESCAPED_UNICODE);
	$assign_list['list_region_depts']    = $list_region_depts;
	$assign_list['list_groups']          = $list_groups;
	$assign_list['role_label']           = $role_label;
	$assign_list['is_director']          = ($filter_mode === 'director') ? 1 : 0;

	/*=============Title & Description Page==================*/
	$title_page = 'Tổng quan Check-in - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}

/* ==================== HELPER: Haversine khoảng cách (mét) ==================== */
function report_haversine($lat1, $lng1, $lat2, $lng2){
	$R = 6371000; $t = M_PI / 180;
	$dla = ($lat2 - $lat1) * $t;
	$dlo = ($lng2 - $lng1) * $t;
	$a = sin($dla/2)*sin($dla/2) + cos($lat1*$t)*cos($lat2*$t)*sin($dlo/2)*sin($dlo/2);
	return (int) round($R * 2 * atan2(sqrt($a), sqrt(1-$a)));
}

/* ==================== HELPER: Resolve office từ lat/lng (replicate chat_resolve_office logic) ==================== */
function report_resolve_office_name($lat, $lng, $offices){
	global $clsISO;
	if(empty($lat) || empty($lng)){ return 'Ngoài VP'; }
	$best = null; $best_dist = PHP_INT_MAX;
	foreach($offices as $o){
		$mi    = isset($o['more_information']) ? $o['more_information'] : array();
		/* Bỏ qua VP không hoạt động (is_active=0), mặc định 1 nếu chưa set */
		if(isset($mi['is_active']) && !(int)$mi['is_active']){ continue; }
		$olat  = (float) (isset($mi['lat'])  ? $mi['lat']  : 0);
		$olng  = (float) (isset($mi['lng'])  ? $mi['lng']  : 0);
		if($olat == 0 || $olng == 0){ continue; }
		$radius = (int) (isset($mi['radius_m']) ? $mi['radius_m'] : 200);
		$dist   = report_haversine($lat, $lng, $olat, $olng);
		if($dist <= $radius && $dist < $best_dist){
			$best_dist = $dist;
			$best      = $o['title'];
		}
	}
	return !empty($best) ? $best : 'Ngoài VP';
}

/* ==================== HELPER: Gom 1 dept + TẤT CẢ con cháu (duyệt theo parent_id) ==================== */
function report_dept_descendants($flat, $root_id){
	$root_id = (int) $root_id;
	if($root_id <= 0) return array();
	/* index con theo parent_id để duyệt subtree nhanh */
	$by_parent = array();
	foreach($flat as $id => $row){
		$pid = (int) (isset($row['parent_id']) ? $row['parent_id'] : 0);
		$by_parent[$pid][] = (int) $id;
	}
	$result = array($root_id); $stack = array($root_id);
	while(!empty($stack)){
		$cur = array_pop($stack);
		if(!empty($by_parent[$cur])){
			foreach($by_parent[$cur] as $cid){ $result[] = $cid; $stack[] = $cid; }
		}
	}
	return array_unique($result);
}

/* ==================== HELPER: Build scoped dept_ids array + enforce server-side scope ====================
   LƯU Ý: buildTree trả NESTED (children lồng), KHÔNG phẳng → phải duyệt subtree theo parent_id, không thì
   DIRECTOR/BUSINESS_AREA chỉ thấy dept cấp vùng và MẤT check-in của dept lá (office_checkin lưu dept lá). */
function report_checkin_scoped_dept_ids($clsISO, $clsProperty, $oneProfile){
	$arr_department_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		/* Toàn bộ phòng ban toàn công ty */
		$allowed = array_keys($arr_department_cached);
	} else {
		/* BUSINESS_AREA (vùng) & SALE_DIRECTOR (phòng): dept của họ + toàn bộ con cháu */
		$allowed = report_dept_descendants($arr_department_cached, (int) $oneProfile['department_id']);
	}
	return array_unique(array_filter(array_map('intval', $allowed)));
}

/* ==================== AJAX: KPI + donut overview ==================== */
function default_load_checkin_overview(){
	global $smarty,$assign_list,$core,$dbconn,$clsISO,$clsProfile,$oneProfile;

	$clsProperty = new Property();
	$clsSetting  = new Setting();
	$clsOfficeCheckin = new OfficeCheckin();

	/* ---- Parse date_range ---- */
	$date_range = trim(Input::post('date_range', ''));
	$today_ymd  = (int) date('Ymd');
	if(!empty($date_range) && strpos($date_range, ' - ') !== false){
		$tmp        = explode(' - ', $date_range);
		$from_ts    = $clsISO->convertTextToTime(trim($tmp[0]));
		$to_ts      = $clsISO->convertTextToTime(trim($tmp[1]), "23:59:59");
		$from_ymd   = (int) date('Ymd', $from_ts);
		$to_ymd     = (int) date('Ymd', $to_ts);
		$kpi_day    = $to_ymd; /* "Chưa check-in" tính theo ngày cuối range */
	} else {
		$from_ymd = $today_ymd;
		$to_ymd   = $today_ymd;
		$kpi_day  = $today_ymd;
	}

	/* ---- Scope: dept_ids được phép ---- */
	$scoped_dept_ids = report_checkin_scoped_dept_ids($clsISO, $clsProperty, $oneProfile);
	if(empty($scoped_dept_ids)){
		/* Không có scope → trả empty */
		$kpi = array('total'=>0,'checked_in'=>0,'not_checked_in'=>0,'rate'=>0,'active_offices'=>0);
		$donut_office_json = '{"labels":[],"series":[],"percents":[]}';
		$donut_dept_json   = '{"labels":[],"series":[],"percents":[]}';
		$kpi_day_label = date('d/m/Y');
		$smarty->assign("_kpi",$kpi);
		$smarty->assign("donut_office_json",$donut_office_json);
		$smarty->assign("donut_dept_json",$donut_dept_json);
		$smarty->assign("kpi_day_label",$kpi_day_label);
		$smarty->assign("list_top_active",array());
		$html = $core->build('_ajax.checkin_overview.tpl');
		echo $html; die();
	}

	/* ---- Filter: client department_id phải trong scope ---- */
	$filter_dept_id = (int) Input::post('department_id', 0);
	if($filter_dept_id > 0 && in_array($filter_dept_id, $scoped_dept_ids)){
		$sub_dept = report_dept_descendants($clsProperty->getArraySearchByKey('_DEPARTMENT'), $filter_dept_id);
		$query_dept_ids = array_values(array_intersect($sub_dept, $scoped_dept_ids));
		if(empty($query_dept_ids)){ $query_dept_ids = array($filter_dept_id); }
	} else {
		$query_dept_ids = $scoped_dept_ids;
	}

	/* ---- Group filter (DIRECTOR only): default_group_profile.list_profile_id chứa pipe-list profile_ids ---- */
	$group_id = (int) Input::post('group_id', 0);
	$group_profile_ids = array();
	if($group_id > 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO'))){
		$clsGroupProfile = new GroupProfile();
		$oGroup = $clsGroupProfile->getOne($group_id, "list_profile_id");
		if(!empty($oGroup['list_profile_id'])){
			$raw_ids = $clsISO->getArrayByTextSlash($oGroup['list_profile_id']);
			foreach($raw_ids as $pid){
				$pid = (int) $pid;
				if($pid > 0) $group_profile_ids[] = $pid;
			}
		}
	}

	/* ---- Query: lấy toàn bộ check-in trong scope+range ---- */
	$dept_ids_sql = implode(',', $query_dept_ids);
	$cond_base = "`work_date` BETWEEN {$from_ymd} AND {$to_ymd} AND `department_id` IN ({$dept_ids_sql})";
	if(!empty($group_profile_ids)){
		$cond_base .= " AND `profile_id` IN (".implode(',', $group_profile_ids).")";
	}
	$field = "profile_id,department_id,office_id,checkin_time,note,lat,lng,more_information,work_date";
	$list_checkin = $dbconn->getAll("SELECT {$field} FROM {$clsOfficeCheckin->tbl} WHERE {$cond_base} ORDER BY checkin_time DESC");
	if(!is_array($list_checkin)) $list_checkin = array();

	/* ---- Load offices cho resolve GPS → VP ---- */
	$offices = $clsSetting->getArraySearchByKey('_OFFICE');

	/* ---- KPI 1: Tổng lượt check-in ---- */
	$kpi_total = count($list_checkin);

	/* ---- KPI 2: Số người đã check-in (distinct profile_id) ---- */
	$distinct_profile_ids = array();
	foreach($list_checkin as $row){
		$distinct_profile_ids[(int)$row['profile_id']] = 1;
	}
	$kpi_checked_in = count($distinct_profile_ids);

	/* ---- KPI 3: Chưa check-in (theo $kpi_day) ---- */
	/* Lấy profile đã check-in đúng ngày $kpi_day */
	$checked_in_day = array();
	foreach($list_checkin as $row){
		if((int)$row['work_date'] === $kpi_day){
			$checked_in_day[(int)$row['profile_id']] = 1;
		}
	}
	/* Roster active trong scope (dùng getProfileDep với dept của scope) */
	$clsProfileObj2 = new Profile();
	$roster_profiles = array();
	foreach($query_dept_ids as $dep_id){
		$dep_staff = $clsProfileObj2->getProfileDep($dep_id, 0, 'active');
		foreach($dep_staff as $pid => $pval){
			$roster_profiles[(int)$pval['profile_id']] = 1;
		}
	}
	$kpi_not_checked_in = max(0, count($roster_profiles) - count(array_intersect_key($checked_in_day, $roster_profiles)));
	$total_profile = count($roster_profiles);

	/* ---- KPI 4: Tỉ lệ % ---- */
	$denom = $kpi_checked_in + $kpi_not_checked_in;
	$kpi_rate = $denom > 0 ? round($kpi_checked_in / $denom * 100, 1) : 0;

	/* ---- KPI 5: Số địa điểm/VP hoạt động (distinct resolved office name) ---- */
	$active_offices = array();
	foreach($list_checkin as $row){
		$oid = (int) $row['office_id'];
		if($oid > 0 && isset($offices[$oid])){
			$oname = $offices[$oid]['title'];
		} elseif(!empty($row['lat']) && !empty($row['lng'])){
			$oname = report_resolve_office_name((float)$row['lat'], (float)$row['lng'], $offices);
		} else {
			$oname = 'Ngoài VP';
		}
		$active_offices[$oname] = 1;
	}
	$kpi_active_offices = count($active_offices);

	/* ---- Donut: theo VP (dùng bgcolor của _OFFICE khi có) ---- */
	/* Xây map: office_title → bgcolor từ _OFFICE settings */
	$office_bgcolor_map = array();
	foreach($offices as $o){
		$mi = isset($o['more_information']) ? $o['more_information'] : array();
		$bg = !empty($mi['bgcolor']) ? $mi['bgcolor'] : '';
		if(!empty($bg)) $office_bgcolor_map[$o['title']] = $bg;
	}
	$office_counts = array();
	$office_color_by_name = array();
	foreach($list_checkin as $row){
		$oid = (int) $row['office_id'];
		if($oid > 0 && isset($offices[$oid])){
			$oname = $offices[$oid]['title'];
			$mi_o  = isset($offices[$oid]['more_information']) ? $offices[$oid]['more_information'] : array();
			$bg    = !empty($mi_o['bgcolor']) ? $mi_o['bgcolor'] : '';
		} else {
			$resolved = (!empty($row['lat']) && !empty($row['lng']))
				? report_resolve_office_name((float)$row['lat'], (float)$row['lng'], $offices)
				: 'Ngoài VP';
			if($resolved !== 'Ngoài VP'){
				$oname = $resolved;
				$bg    = isset($office_bgcolor_map[$oname]) ? $office_bgcolor_map[$oname] : '';
			} else {
				$mi2  = $clsISO->to_array_json(isset($row['more_information']) ? $row['more_information'] : '');
				$addr = !empty($mi2['address']) ? $mi2['address'] : '';
				$oname = 'Khác';	/* check-in không thuộc VP nào → gom 1 mục "Khác" (tab Văn phòng chỉ hiện danh sách VP thật) */
				$bg   = '';
			}
		}
		$office_counts[$oname] = isset($office_counts[$oname]) ? $office_counts[$oname]+1 : 1;
		if(!empty($bg) && !isset($office_color_by_name[$oname])) $office_color_by_name[$oname] = $bg;
	}
	arsort($office_counts);
	$donut_office = report_build_donut_data($office_counts, $kpi_total, $office_color_by_name, count($offices) + 1);	/* hiện ĐỦ mọi VP + 1 mục "Khác" */

	/* ---- Donut: theo phòng ban ---- */
	$arr_dept_cached = $clsProperty->getArraySearchByKey('_DEPARTMENT');
	$dept_counts = array();
	foreach($list_checkin as $row){
		$dep_id = (int) $row['department_id'];
		$dep_title = isset($arr_dept_cached[$dep_id]['title']) ? $arr_dept_cached[$dep_id]['title'] : 'Khác';
		$dept_counts[$dep_title] = isset($dept_counts[$dep_title]) ? $dept_counts[$dep_title]+1 : 1;
	}
	arsort($dept_counts);
	$donut_dept = report_build_donut_data($dept_counts, $kpi_total);
	$kpi = array(
		'total_profile'          => $total_profile,
		'total'          => $kpi_total,
		'checked_in'     => $kpi_checked_in,
		'not_checked_in' => $kpi_not_checked_in,
		'rate'           => $kpi_rate,
		'active_offices' => $kpi_active_offices,
	);
	/* JSON strings passed as Smarty vars (Smarty không có json_encode filter) */
	$donut_office_json = json_encode($donut_office, JSON_UNESCAPED_UNICODE);
	$donut_dept_json   = json_encode($donut_dept,   JSON_UNESCAPED_UNICODE);
	$kpi_day_label = date('d/m/Y', mktime(0,0,0,
		(int)substr((string)$kpi_day,4,2),
		(int)substr((string)$kpi_day,6,2),
		(int)substr((string)$kpi_day,0,4)
	));

	/* ---- Top hoạt động (top 5 theo số lượt, gom từ $list_checkin đã load — không query thêm) ---- */
	$top_count = array();
	foreach($list_checkin as $row){
		$pid = (int) $row['profile_id'];
		$top_count[$pid] = isset($top_count[$pid]) ? $top_count[$pid]+1 : 1;
	}
	arsort($top_count);
	$list_top_active = array();
	$clsProfileTop = new Profile();
	$ti = 0;
	foreach($top_count as $pid => $cnt){
		if(++$ti > 5) break;
		$oProfile = $clsProfileTop->getOne($pid, "profile_id,full_name,avatar");
		$list_top_active[] = array(
			'profile_id'    => $pid,
			'full_name'     => isset($oProfile['full_name']) ? $oProfile['full_name'] : '',
			'avatar'        => $clsProfileTop->getAvatar($pid, $oProfile, 40, 40),
			'checkin_count' => (int) $cnt,
		);
	}

	$smarty->assign("_kpi",$kpi);
	$smarty->assign("donut_office_json",$donut_office_json);
	$smarty->assign("donut_dept_json",$donut_dept_json);
	$smarty->assign("kpi_day_label",$kpi_day_label);
	$smarty->assign("list_top_active",$list_top_active);

	$html = $core->build('_ajax.checkin_overview.tpl');
	echo $html; die();
}

/* ---- Helper: Gom top-6 + Khác cho donut. $color_map = ['label'=>'#hex'] tuỳ chọn ---- */
function report_build_donut_data($counts, $total, $color_map = array(), $max = 6){
	$labels = array(); $series = array(); $percents = array(); $colors = array();
	$ii = 0; $other_count = 0;
	foreach($counts as $label => $cnt){
		$ii++;
		if($ii <= $max){
			$labels[]   = $label;
			$series[]   = (int) $cnt;
			$percents[] = $total > 0 ? round($cnt/$total*100,1) : 0;
			$colors[]   = !empty($color_map[$label]) ? $color_map[$label] : null;
		} else {
			$other_count += $cnt;
		}
	}
	if($other_count > 0){
		$labels[]   = 'Khác';
		$series[]   = $other_count;
		$percents[] = $total > 0 ? round($other_count/$total*100,1) : 0;
		$colors[]   = null;
	}
	return array('labels'=>$labels,'series'=>$series,'percents'=>$percents,'colors'=>$colors);
}

/* ==================== AJAX: Top hoạt động + Danh sách mới nhất ==================== */
function default_load_checkin_list(){
	global $smarty,$assign_list,$core,$dbconn,$clsISO,$clsProfile,$oneProfile;

	$clsProperty = new Property();
	$clsSetting  = new Setting();
	$clsOfficeCheckin = new OfficeCheckin();
	$clsProfileObj = new Profile();

	/* ---- Parse date_range ---- */
	$date_range = trim(Input::post('date_range', ''));
	$today_ymd  = (int) date('Ymd');
	if(!empty($date_range) && strpos($date_range, ' - ') !== false){
		$tmp      = explode(' - ', $date_range);
		$from_ts  = $clsISO->convertTextToTime(trim($tmp[0]));
		$to_ts    = $clsISO->convertTextToTime(trim($tmp[1]), "23:59:59");
		$from_ymd = (int) date('Ymd', $from_ts);
		$to_ymd   = (int) date('Ymd', $to_ts);
	} else {
		$from_ymd = $today_ymd;
		$to_ymd   = $today_ymd;
	}

	/* ---- Scope ---- */
	$scoped_dept_ids = report_checkin_scoped_dept_ids($clsISO, $clsProperty, $oneProfile);
	if(empty($scoped_dept_ids)){
		$smarty->assign("list_recent",array());
		$html = $core->build('_ajax.checkin_list.tpl');
		echo $html; die();
	}
	$filter_dept_id = (int) Input::post('department_id', 0);
	if($filter_dept_id > 0 && in_array($filter_dept_id, $scoped_dept_ids)){
		$sub_dept = report_dept_descendants($clsProperty->getArraySearchByKey('_DEPARTMENT'), $filter_dept_id);
		$query_dept_ids = array_values(array_intersect($sub_dept, $scoped_dept_ids));
		if(empty($query_dept_ids)){ $query_dept_ids = array($filter_dept_id); }
	} else {
		$query_dept_ids = $scoped_dept_ids;
	}
	/* ---- Group filter (DIRECTOR only): default_group_profile.list_profile_id ---- */
	$group_id = (int) Input::post('group_id', 0);
	$group_profile_ids = array();
	if($group_id > 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO'))){
		$clsGroupProfile = new GroupProfile();
		$oGroup = $clsGroupProfile->getOne($group_id, "list_profile_id");
		if(!empty($oGroup['list_profile_id'])){
			$raw_ids = $clsISO->getArrayByTextSlash($oGroup['list_profile_id']);
			foreach($raw_ids as $pid){
				$pid = (int) $pid;
				if($pid > 0) $group_profile_ids[] = $pid;
			}
		}
	}
	$dept_ids_sql = implode(',', $query_dept_ids);
	$cond_base = "`work_date` BETWEEN {$from_ymd} AND {$to_ymd} AND `department_id` IN ({$dept_ids_sql})";
	if(!empty($group_profile_ids)){
		$cond_base .= " AND `profile_id` IN (".implode(',', $group_profile_ids).")";
	}

	/* ---- Check-in mới nhất (toàn bộ danh sách trong scope+range) ---- */
	$field_recent = "profile_id,checkin_time,note,photo,lat,lng,office_id,more_information";
	$list_recent_raw = $dbconn->getAll(
		"SELECT {$field_recent} FROM {$clsOfficeCheckin->tbl} WHERE {$cond_base} ORDER BY checkin_time DESC"
	);
	if(!is_array($list_recent_raw)) $list_recent_raw = array();

	/* Batch load profile (tránh N+1 khi danh sách dài) */
	$recent_pids = array();
	foreach($list_recent_raw as $row){ $recent_pids[(int)$row['profile_id']] = 1; }
	$profile_map = array();
	if(!empty($recent_pids)){
		$pids_sql  = implode(',', array_keys($recent_pids));
		$prof_rows = $dbconn->getAll("SELECT profile_id,full_name,avatar FROM {$clsProfileObj->tbl} WHERE profile_id IN ({$pids_sql})");
		if(is_array($prof_rows)){
			foreach($prof_rows as $pr){ $profile_map[(int)$pr['profile_id']] = $pr; }
		}
	}

	$offices = $clsSetting->getArraySearchByKey('_OFFICE');
	$list_recent = array();
	foreach($list_recent_raw as $row){
		$pid       = (int) $row['profile_id'];
		$oProfile  = isset($profile_map[$pid]) ? $profile_map[$pid] : array('profile_id'=>$pid);
		$mi        = $clsISO->to_array_json(isset($row['more_information']) ? $row['more_information'] : '');
		$addr      = !empty($mi['address']) ? $mi['address'] : '';
		/* Resolve location: office name > note > address > "Chưa xác định" */
		$oid = (int) $row['office_id'];
		if($oid > 0 && isset($offices[$oid])){
			$location = $offices[$oid]['title'];
		} elseif(!empty($row['lat']) && !empty($row['lng'])){
			$resolved = report_resolve_office_name((float)$row['lat'], (float)$row['lng'], $offices);
			$location = ($resolved !== 'Ngoài VP') ? $resolved : (!empty($row['note']) ? $row['note'] : (!empty($addr) ? $addr : 'Ngoài VP'));
		} elseif(!empty($row['note'])){
			$location = $row['note'];
		} elseif(!empty($addr)){
			$location = $addr;
		} else {
			$location = 'Chưa xác định';
		}
		$list_recent[] = array(
			'profile_id'  => $pid,
			'full_name'   => isset($oProfile['full_name']) ? $oProfile['full_name'] : '',
			'avatar'      => $clsProfileObj->getAvatar($pid, $oProfile, 40, 40),
			'time_label'  => !empty($row['checkin_time']) ? date('H:i d/m', (int)$row['checkin_time']) : '',
			'location'    => $location,
			'photo'       => isset($row['photo']) ? $row['photo'] : '',
			'lat'       => $row['lat'],
			'lng'       => $row['lng'],
		);
	}

	$smarty->assign("list_recent",$list_recent);
	$html = $core->build('_ajax.checkin_list.tpl');
	echo $html; die();
}
function default_load_list_profile_checkin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	global $smarty,$assign_list,$core,$dbconn,$clsISO,$clsProfile,$oneProfile;
	$clsProperty = new Property();
	$clsSetting  = new Setting();
	$clsOfficeCheckin = new OfficeCheckin();
	$_type = Input::post("_type","has_checkin");
	/* ---- Parse date_range ---- */
	$date_range = trim(Input::post('date_range', ''));
	$today_ymd  = (int) date('Ymd');
	if(!empty($date_range) && strpos($date_range, ' - ') !== false){
		$tmp        = explode(' - ', $date_range);
		$from_ts    = $clsISO->convertTextToTime(trim($tmp[0]));
		$to_ts      = $clsISO->convertTextToTime(trim($tmp[1]), "23:59:59");
		$from_ymd   = (int) date('Ymd', $from_ts);
		$to_ymd     = (int) date('Ymd', $to_ts);
		$kpi_day    = $to_ymd; /* "Chưa check-in" tính theo ngày cuối range */
	} else {
		$from_ymd = $today_ymd;
		$to_ymd   = $today_ymd;
		$kpi_day  = $today_ymd;
	}

	/* ---- Scope: dept_ids được phép ---- */
	$scoped_dept_ids = report_checkin_scoped_dept_ids($clsISO, $clsProperty, $oneProfile);

	/* ---- Filter: client department_id phải trong scope ---- */
	$filter_dept_id = (int) Input::post('department_id', 0);
	if($filter_dept_id > 0 && in_array($filter_dept_id, $scoped_dept_ids)){
		$sub_dept = report_dept_descendants($clsProperty->getArraySearchByKey('_DEPARTMENT'), $filter_dept_id);
		$query_dept_ids = array_values(array_intersect($sub_dept, $scoped_dept_ids));
		if(empty($query_dept_ids)){ $query_dept_ids = array($filter_dept_id); }
	} else {
		$query_dept_ids = $scoped_dept_ids;
	}

	/* ---- Group filter (DIRECTOR only): default_group_profile.list_profile_id chứa pipe-list profile_ids ---- */
	$group_id = (int) Input::post('group_id', 0);
	$group_profile_ids = array();
	if($group_id > 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO'))){
		$clsGroupProfile = new GroupProfile();
		$oGroup = $clsGroupProfile->getOne($group_id, "list_profile_id");
		if(!empty($oGroup['list_profile_id'])){
			$raw_ids = $clsISO->getArrayByTextSlash($oGroup['list_profile_id']);
			foreach($raw_ids as $pid){
				$pid = (int) $pid;
				if($pid > 0) $group_profile_ids[] = $pid;
			}
		}
	}

	/* ---- Query: lấy toàn bộ check-in trong scope+range ---- */
	$dept_ids_sql = implode(',', $query_dept_ids);
	$cond_base = "`work_date` BETWEEN {$from_ymd} AND {$to_ymd} AND `department_id` IN ({$dept_ids_sql})";
	if(!empty($group_profile_ids)){
		$cond_base .= " AND `profile_id` IN (".implode(',', $group_profile_ids).")";
	}
	$field = "profile_id,department_id,office_id,checkin_time,note,lat,lng,more_information,work_date";
	$list_checkin = $dbconn->getAll("SELECT {$field} FROM {$clsOfficeCheckin->tbl} WHERE {$cond_base} ORDER BY checkin_time DESC");
	if(!is_array($list_checkin)) $list_checkin = array();

	/* ---- Load offices cho resolve GPS → VP ---- */
	$offices = $clsSetting->getArraySearchByKey('_OFFICE');

	/* ---- KPI 1: Tổng lượt check-in ---- */
	$kpi_total = count($list_checkin);

	/* ---- KPI 2: Số người đã check-in (distinct profile_id) ---- */
	$distinct_profile_ids = array();
	foreach($list_checkin as $row){
		$distinct_profile_ids[(int)$row['profile_id']] = 1;
	}
	$kpi_checked_in = count($distinct_profile_ids);

	/* ---- KPI 3: Chưa check-in (theo $kpi_day) + đếm số lần CI/người ---- */
	$checked_in_day = array();
	$count_by_profile = array();
	$min_time_day = array();	/* MIN(checkin_time) mỗi người trong $kpi_day → xét đúng giờ/muộn + giờ vào */
	$max_time_day = array();	/* MAX(checkin_time) mỗi người → giờ ra (check-out) */
	foreach($list_checkin as $row){
		if((int)$row['work_date'] === $kpi_day){
			$_cpid = (int)$row['profile_id'];
			$checked_in_day[$_cpid] = 1;
			$count_by_profile[$_cpid] = (isset($count_by_profile[$_cpid]) ? $count_by_profile[$_cpid] : 0) + 1;
			$_ct = (int)$row['checkin_time'];
			if($_ct > 0 && (!isset($min_time_day[$_cpid]) || $_ct < $min_time_day[$_cpid])){ $min_time_day[$_cpid] = $_ct; }
			if($_ct > 0 && (!isset($max_time_day[$_cpid]) || $_ct > $max_time_day[$_cpid])){ $max_time_day[$_cpid] = $_ct; }
		}
	}
	/* Roster active trong scope (dùng getProfileDep với dept của scope) */
	$clsProfileObj2 = new Profile();
	$roster_profiles = array();
	$arr_profile = [];
	$arr_department_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	/* Mốc đúng giờ theo khối (khớp bảng Vùng): khối kinh doanh (subtree sale-root) 09:00, khối khác 08:30.
	   Đơn vị rostered ($dep_id) quyết định khối vì sale-root là top-level → cả subtree cùng khối. */
	$sale_root = defined('_DEPARTMENT_SALE_ID') ? (int) _DEPARTMENT_SALE_ID : 40;
	$kd_set    = array_flip(report_dept_descendants($arr_department_cached, $sale_root));
	$cfg_oc    = $clsOfficeCheckin->getConfig();
	$cut_sale  = report_ontime_cut(isset($cfg_oc['on_time_sale']) ? $cfg_oc['on_time_sale'] : '09:00', $kpi_day, 9, 0);
	$cut_other = report_ontime_cut(isset($cfg_oc['on_time'])      ? $cfg_oc['on_time']      : '08:30', $kpi_day, 8, 30);
	$cut_early = report_ontime_cut(isset($cfg_oc['checkout_time']) ? $cfg_oc['checkout_time'] : '17:30', $kpi_day, 17, 30);	/* mốc về sớm */
	$after_close = ($kpi_day < $today_ymd) || (time() >= report_ontime_cut(isset($cfg_oc['checkout_time']) ? $cfg_oc['checkout_time'] : '17:30', $today_ymd, 17, 30));	/* qua giờ đóng ca / ngày cũ */
	foreach($query_dept_ids as $dep_id){
		$dep_staff = $clsProfileObj2->getProfileDep($dep_id, 0, 'active');
		foreach($dep_staff as $pid => $pval){
			if(!isset($roster_profiles[(int)$pval['profile_id']])) {
				$info_staff = $pval["more_information"];
				$department_id = $pval["department_id"];
				$_ppid = (int)$pval['profile_id'];
				$roster_profiles[$_ppid] = 1;
				$_pcnt = isset($count_by_profile[$_ppid]) ? $count_by_profile[$_ppid] : 0;
				$arr_profile[$_ppid] = [
					"profile_id"	=>	$_ppid,
					"full_name"		=>	$pval["full_name"],
					"avatar"		=>	$pval["avatar"],
					"department_name"		=>	$info_staff["department_name"],
					"count"			=>	$_pcnt,
					"time_in"		=>	isset($min_time_day[$_ppid]) ? date('H:i', $min_time_day[$_ppid]) : '',
					"time_out"		=>	report_checkout_display($_pcnt, isset($max_time_day[$_ppid]) ? $max_time_day[$_ppid] : 0, $after_close),
					"_cut"			=>	isset($kd_set[(int)$dep_id]) ? $cut_sale : $cut_other,	// mốc đúng giờ theo khối
				];
			}

		}
	}
	$profile_checkIn = array_intersect_key($checked_in_day, $roster_profiles);
	$profile_not_checkIn = array_diff(array_keys($roster_profiles),array_keys($profile_checkIn));
	/* Đúng giờ / đi muộn: người đã CI trong ngày, so MIN(checkin_time) với mốc theo khối ($_cut). */
	$profile_on_time = array();
	$profile_late    = array();
	$profile_checked_out = array();	/* ≥2 lượt = đã ra */
	$profile_early       = array();	/* ≥2 lượt & ra < mốc = về sớm */
	foreach(array_keys($profile_checkIn) as $pro_id){
		$mn  = isset($min_time_day[$pro_id]) ? $min_time_day[$pro_id] : 0;
		$cut = isset($arr_profile[$pro_id]['_cut']) ? $arr_profile[$pro_id]['_cut'] : $cut_other;
		if($mn > 0 && $mn <= $cut){ $profile_on_time[$pro_id] = 1; } else { $profile_late[$pro_id] = 1; }
		$_cnt = isset($count_by_profile[$pro_id]) ? $count_by_profile[$pro_id] : 0;
		if($_cnt >= 2){ $profile_checked_out[$pro_id] = 1; if(isset($max_time_day[$pro_id]) && $max_time_day[$pro_id] < $cut_early){ $profile_early[$pro_id] = 1; } }
	}
	$arr_profile_ids = array_keys($profile_checkIn);	/* has_checkin (mặc định) */
	if($_type == "not_checkin")     { $arr_profile_ids = $profile_not_checkIn; }
	elseif($_type == "all")         { $arr_profile_ids = array_keys($roster_profiles); }
	elseif($_type == "on_time")     { $arr_profile_ids = array_keys($profile_on_time); }
	elseif($_type == "late")        { $arr_profile_ids = array_keys($profile_late); }
	elseif($_type == "checked_out") { $arr_profile_ids = array_keys($profile_checked_out); }
	elseif($_type == "early_leave") { $arr_profile_ids = array_keys($profile_early); }
	$list_profile_ids = [];
	foreach ($arr_profile_ids as $pro_id) {
		$list_profile_ids[$pro_id] = $arr_profile[$pro_id];
	}
	$sort_arrs = @array_column($list_profile_ids, 'count');
	@array_multisort($sort_arrs, SORT_DESC, $list_profile_ids);
	$uid = $clsISO->getUniqId();
	$smarty->assign("uid",$uid);
	$smarty->assign("_type",$_type);
	$smarty->assign("list_profile_ids",$list_profile_ids);
	$html = $core->build('_ajax.list_profile_checkin.tpl');
	echo json_encode([
		"uid"	=>	$uid,
		"html"	=>	$html,
	],JSON_UNESCAPED_UNICODE);
}

/* ==================== HELPER: tier màu theo tỷ lệ (legend) ==================== */
function report_rate_tier($rate){
	if($rate >= 95) return 'success';	/* xanh  */
	if($rate >= 85) return 'warning';	/* vàng  */
	if($rate >= 70) return 'orange';	/* cam   */
	return 'danger';					/* đỏ    */
}

/* ==================== HELPER: 'HH:MM' + Ymd → timestamp mốc giờ đúng ca (fallback $dh:$dm) ==================== */
function report_ontime_cut($hhmm, $ymd, $dh, $dm){
	$p = explode(':', (string)$hhmm);
	$h = (isset($p[0]) && $p[0] !== '') ? (int)$p[0] : $dh;
	$m = (isset($p[1]) && $p[1] !== '') ? (int)$p[1] : $dm;
	return mktime($h, $m, 0, (int)substr((string)$ymd,4,2), (int)substr((string)$ymd,6,2), (int)substr((string)$ymd,0,4));
}

/* ==================== HELPER: hiển thị giờ CHECK-OUT (giờ ra) — theo count/MAX/đã-qua-giờ-đóng ==================== */
function report_checkout_display($cnt, $mx, $after_close){
	if($cnt >= 2){ return date('H:i', (int)$mx); }					/* ≥2 lượt → lượt cuối = giờ ra */
	if($cnt == 1){ return $after_close ? 'Chưa check-out' : '--'; }	/* mới vào: qua giờ đóng → chưa ra; chưa tới → -- */
	return '';														/* chưa check-in */
}

/* ==================== HELPER: roster 1 đơn vị = subtree parent_id + getProfileDep(dep,0).
   Khớp overview/drill-down; KHÔNG dùng getProfileDep(id,1) (list_department_id) vì chuỗi này đôi khi
   thiếu tổ tiên trung gian (vd |303|40| bỏ qua 57) → undercount + lệch số với modal/KPI. ==================== */
function report_region_roster($clsProfileObj, $arr_dept, $root_id, $scoped){
	$subtree = array_values(array_intersect(report_dept_descendants($arr_dept, $root_id), $scoped));
	if(empty($subtree)){ $subtree = array((int) $root_id); }
	$roster = array();
	foreach($subtree as $dep){
		$staff = $clsProfileObj->getProfileDep($dep, 0, 'active');
		if(is_array($staff)){ foreach($staff as $pv){ $roster[(int)$pv['profile_id']] = $pv; } }
	}
	return $roster;	/* keyed by profile_id => full row */
}

/* ==================== AJAX: Bảng "Theo dõi Check-in theo Vùng/đơn vị" ====================
   Đệ quy theo `department_id` (parent) + quyền:
   - DIRECTOR/BO, parent=0: Vùng (con sale-root) + khối top-level khác (trừ sale-root).
   - REGIONAL/parent>0: các phòng con của parent.
   - Không còn phòng con (lá): mode 'person' → danh sách nhân viên + trạng thái. */
function default_load_checkin_region(){
	global $smarty,$assign_list,$core,$dbconn,$clsISO,$clsProfile,$oneProfile;

	$clsProperty   = new Property();
	$clsProfileObj = new Profile();
	$clsOC         = new OfficeCheckin();
	$arr_dept      = $clsProperty->getArraySearchByKey('_DEPARTMENT');	// keyed by id: {title,parent_id,property_code,more_information}

	/* ---- Ngày: tính theo ngày cuối range (như "chưa check-in" ở overview) ---- */
	$date_range = trim(Input::post('date_range', ''));
	$kpi_day = (int) date('Ymd');
	if(!empty($date_range) && strpos($date_range, ' - ') !== false){
		$tmp = explode(' - ', $date_range);
		$to_ts = $clsISO->convertTextToTime(trim($tmp[1]), "23:59:59");
		$kpi_day = (int) date('Ymd', $to_ts);
	}

	/* ---- Scope ---- */
	$scoped = report_checkin_scoped_dept_ids($clsISO, $clsProperty, $oneProfile);
	if(empty($scoped)){
		$smarty->assign('rc_mode', 'unit'); $smarty->assign('rc_units', array());
		$smarty->assign('rc_total', null);  $smarty->assign('rc_people', array());
		$html = $core->build('_ajax.checkin_region.tpl'); echo $html; die();
	}

	$is_director = ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO'));
	$sale_root   = defined('_DEPARTMENT_SALE_ID') ? (int) _DEPARTMENT_SALE_ID : 40;

	/* ---- Xác định "đơn vị-hàng" (units) theo parent + quyền ---- */
	$parent_id = (int) Input::post('department_id', 0);
	if($parent_id > 0 && !in_array($parent_id, $scoped)){ $parent_id = 0; }	// ngoài scope → reset

	$units = array();
	if($parent_id > 0){
		foreach($arr_dept as $id => $row){ if((int)$row['parent_id'] === $parent_id && $id != _DEPARTMENT_DIRECTOR_ID){ $units[] = (int)$id; } }
	} elseif($is_director){
		foreach($arr_dept as $id => $row){
			if($id != _DEPARTMENT_DIRECTOR_ID) {
				$pid = (int)$row['parent_id'];
				if($pid === $sale_root){ $units[] = (int)$id; }						/* Vùng */
				elseif($pid === 0 && (int)$id !== $sale_root){ $units[] = (int)$id; }	/* khối top-level (trừ sale-root) */
			}			
		}
	} else {
		$parent_id = (int) $oneProfile['department_id'];
		foreach($arr_dept as $id => $row){ if((int)$row['parent_id'] === $parent_id){ $units[] = (int)$id; } }
	}
	$units = array_values(array_intersect($units, $scoped));
	$mode  = empty($units) ? 'person' : 'unit';

	/* ---- Check-in trong ngày (scope) → mỗi profile MIN (giờ vào) / MAX (giờ ra) / COUNT (số lượt) ---- */
	$checkin_min = array(); $checkin_max = array(); $checkin_cnt = array();
	$rows_ci = $dbconn->getAll("SELECT profile_id, MIN(checkin_time) AS mn, MAX(checkin_time) AS mx, COUNT(*) AS c FROM {$clsOC->tbl} WHERE work_date={$kpi_day} AND department_id IN (".implode(',', $scoped).") GROUP BY profile_id");
	if(is_array($rows_ci)){ foreach($rows_ci as $r){ $pid=(int)$r['profile_id']; $checkin_min[$pid]=(int)$r['mn']; $checkin_max[$pid]=(int)$r['mx']; $checkin_cnt[$pid]=(int)$r['c']; } }

	/* ---- Mốc "đúng giờ" (config): khối kinh doanh (subtree sale-root) dùng on_time_sale (mặc định 09:00),
	   các khối khác dùng on_time (mặc định 08:30). Quyết định theo ĐƠN VỊ vì sale-root là top-level (parent=0):
	   đơn vị ∈ subtree(sale-root) ⟺ toàn bộ roster đơn vị đó thuộc khối kinh doanh. ---- */
	$cfg    = $clsOC->getConfig();
	$kd_set = array_flip(report_dept_descendants($arr_dept, $sale_root));	/* {sale-root ∪ con cháu} → tra nhanh isset() */
	$cut_sale  = report_ontime_cut(isset($cfg['on_time_sale']) ? $cfg['on_time_sale'] : '09:00', $kpi_day, 9, 0);
	$cut_other = report_ontime_cut(isset($cfg['on_time'])      ? $cfg['on_time']      : '08:30', $kpi_day, 8, 30);
	$cut_early = report_ontime_cut(isset($cfg['checkout_time']) ? $cfg['checkout_time'] : '17:30', $kpi_day, 17, 30);	/* mốc về sớm: check-out trước giờ này = về sớm */
	$today_ymd   = (int) date('Ymd');
	$after_close = ($kpi_day < $today_ymd) || (time() >= report_ontime_cut(isset($cfg['checkout_time']) ? $cfg['checkout_time'] : '17:30', $today_ymd, 17, 30));	/* đã qua giờ đóng ca (hôm nay) hoặc ngày quá khứ */

	if($mode === 'person'){
		/* ---- Lá: danh sách nhân viên của $parent_id + trạng thái (subtree, khớp drill-down) ---- */
		$staff = array_values(report_region_roster($clsProfileObj, $arr_dept, $parent_id, $scoped));
		$people = array();
		$cut = isset($kd_set[$parent_id]) ? $cut_sale : $cut_other;	/* đơn vị lá thuộc KD → mốc 09:00, else 08:30 */
		if(is_array($staff)){
			foreach($staff as $pv){
				$pid  = (int)$pv['profile_id'];
				$mn   = isset($checkin_min[$pid]) ? $checkin_min[$pid] : 0;
				$cnt  = isset($checkin_cnt[$pid]) ? $checkin_cnt[$pid] : 0;
				$mx   = isset($checkin_max[$pid]) ? $checkin_max[$pid] : 0;
				$st   = ($mn <= 0) ? 'chua' : ($mn <= $cut ? 'dung' : 'muon');
				$info = isset($pv['more_information']) && is_array($pv['more_information']) ? $pv['more_information'] : array();
				$people[] = array(
					'profile_id'      => $pid,
					'full_name'       => isset($pv['full_name']) ? $pv['full_name'] : '',
					'avatar'          => $clsProfileObj->getAvatar($pid, $pv, 80, 0),
					'department_name' => isset($info['department_name']) ? $info['department_name'] : '',
					'time'            => $mn > 0 ? date('H:i', $mn) : '',
					'time_out'        => report_checkout_display($cnt, $mx, $after_close),
					'status'          => $st,
				);
			}
		}
		$smarty->assign('rc_mode', 'person');
		$smarty->assign('rc_people', $people);
		$smarty->assign('rc_units', array());
		$smarty->assign('rc_total', null);
		$html = $core->build('_ajax.checkin_region.tpl'); echo $html; die();
	}

	/* ---- Đơn vị: tổng hợp mỗi unit ---- */
	$units_data = array();
	$T = array('total'=>0,'dacI'=>0,'dung'=>0,'muon'=>0,'chua'=>0,'cout'=>0,'early'=>0);
	$head_ids = array();
	foreach($units as $uid){
		$roster = report_region_roster($clsProfileObj, $arr_dept, $uid, $scoped);	/* subtree + mode-0, khớp overview/drill-down */
		$total = count($roster); $dacI = 0; $dung = 0; $cout = 0; $early = 0;
		if($total === 0){ continue; }	/* bỏ đơn vị không có nhân sự */
		$cut = isset($kd_set[$uid]) ? $cut_sale : $cut_other;	/* đơn vị ∈ khối KD → 09:00, else 08:30 */
		foreach($roster as $pid => $_){
			if(isset($checkin_cnt[$pid])){
				$dacI++;
				if($checkin_min[$pid] <= $cut){ $dung++; }								/* đúng giờ (giờ vào ≤ mốc) */
				if($checkin_cnt[$pid] >= 2){ $cout++; if($checkin_max[$pid] < $cut_early){ $early++; } }	/* ≥2 lượt = đã ra; ra < 17:30 = về sớm */
			}
		}
		$muon = $dacI - $dung; $chua = $total - $dacI;
		$rate = $total > 0 ? round($dacI / $total * 100, 1) : 0;
		$urow = $arr_dept[$uid];
		$mi   = isset($urow['more_information']) ? (is_array($urow['more_information']) ? $urow['more_information'] : $clsISO->to_array_json($urow['more_information'])) : array();
		$head = isset($mi['head_of_dep_id']) ? (int)$mi['head_of_dep_id'] : 0;
		if($head > 0){ $head_ids[$head] = 1; }
		$has_child = false;
		foreach($arr_dept as $cid => $crow){ if((int)$crow['parent_id'] === $uid){ $has_child = true; break; } }
		$units_data[] = array(
			'id'=>$uid, 'title'=>isset($urow['title'])?$urow['title']:'', 'code'=>isset($urow['property_code'])?$urow['property_code']:'',
			'total'=>$total, 'dacI'=>$dacI, 'dung'=>$dung, 'muon'=>$muon, 'chua'=>$chua, 'cout'=>$cout, 'early'=>$early,
			'rate'=>$rate, 'tier'=>report_rate_tier($rate), 'head'=>$head, 'has_child'=>$has_child,
		);
		$T['total']+=$total; $T['dacI']+=$dacI; $T['dung']+=$dung; $T['muon']+=$muon; $T['chua']+=$chua; $T['cout']+=$cout; $T['early']+=$early;
	}
	$T['rate'] = $T['total'] > 0 ? round($T['dacI'] / $T['total'] * 100, 1) : 0;
	$T['tier'] = report_rate_tier($T['rate']);

	/* ---- Leader = head_of_dep_id (batch query 1 lần) ---- */
	$leader_map = array();
	if(!empty($head_ids)){
		$lp = $dbconn->getAll("SELECT profile_id, full_name, avatar FROM {$clsProfileObj->tbl} WHERE profile_id IN (".implode(',', array_keys($head_ids)).")");
		if(is_array($lp)){ foreach($lp as $p){ $leader_map[(int)$p['profile_id']] = $p; } }
	}
	foreach($units_data as &$u){
		$h = $u['head'];
		if($h > 0 && isset($leader_map[$h])){
			$u['leader_name']   = isset($leader_map[$h]['full_name']) ? $leader_map[$h]['full_name'] : '';
			$u['leader_avatar'] = $clsProfileObj->getAvatar($h, $leader_map[$h], 80, 0);
		} else { $u['leader_name'] = ''; $u['leader_avatar'] = ''; }
	}
	unset($u);

	$smarty->assign('rc_mode', 'unit');
	$smarty->assign('rc_units', $units_data);
	$smarty->assign('rc_total', $T);
	$smarty->assign('rc_people', array());
	$html = $core->build('_ajax.checkin_region.tpl'); echo $html; die();
}

/* ==================== AJAX: Hành trình check-in của 1 người (modal, click từ danh sách) ====================
   Input: profile_id + date_range. ENFORCE SCOPE (chống xem người ngoài quyền/IDOR). Trả {uid, html}. */
function default_load_profile_journey(){
	global $smarty,$core,$dbconn,$clsISO,$oneProfile;
	$clsProperty   = new Property();
	$clsProfileObj = new Profile();
	$clsSetting    = new Setting();
	$clsOC         = new OfficeCheckin();

	$pid = (int) Input::post('profile_id', 0);

	/* ---- Ngày: ưu tiên param `day` (Ymd, do nút ‹ › gửi); else lấy cuối date_range ---- */
	$day = (int) Input::post('day', 0);
	if($day <= 0){
		$date_range = trim(Input::post('date_range', ''));
		$day = (int) date('Ymd');
		if(!empty($date_range) && strpos($date_range, ' - ') !== false){
			$tmp = explode(' - ', $date_range);
			$to_ts = $clsISO->convertTextToTime(trim($tmp[1]), "23:59:59");
			$day = (int) date('Ymd', $to_ts);
		}
	}
	$today_ymd = (int) date('Ymd');
	if($day > $today_ymd){ $day = $today_ymd; }	/* không cho ngày tương lai */
	$day_ts    = mktime(0, 0, 0, (int)substr((string)$day,4,2), (int)substr((string)$day,6,2), (int)substr((string)$day,0,4));
	$wd        = array('Chủ nhật','Thứ Hai','Thứ Ba','Thứ Tư','Thứ Năm','Thứ Sáu','Thứ Bảy');
	$is_today  = ($day === $today_ymd);
	$day_label = ($is_today ? 'Hôm nay · ' : '') . $wd[(int)date('w', $day_ts)] . ', ' . date('d/m/Y', $day_ts);

	/* ---- Hồ sơ + SCOPE (dept của người phải nằm trong quyền xem) ---- */
	$scoped   = report_checkin_scoped_dept_ids($clsISO, $clsProperty, $oneProfile);
	$oProfile = ($pid > 0) ? $clsProfileObj->getOne($pid, "profile_id,full_name,avatar,department_id,more_information") : array();
	$allowed  = ($pid > 0 && is_array($oProfile) && !empty($oProfile) && in_array((int)$oProfile['department_id'], $scoped, true));

	$items   = array();
	$profile = array('name'=>'','avatar'=>'','dept'=>'','role'=>'');
	if($allowed){
		$mi_me = $clsISO->to_array_json((!empty($oProfile['more_information'])) ? $oProfile['more_information'] : '');
		$profile = array(
			'name'   => isset($oProfile['full_name']) ? $oProfile['full_name'] : '',
			'avatar' => $clsProfileObj->getAvatar($pid, $oProfile, 40, 40),
			'dept'   => isset($mi_me['department_name']) ? $mi_me['department_name'] : '',
			'role'   => isset($mi_me['role_name']) ? $mi_me['role_name'] : '',
		);
		$rows = $clsOC->getAll("`profile_id`=".$pid." AND `work_date`=".$day." ORDER BY `checkin_time` ASC", "`checkin_time`,`lat`,`lng`,`photo`,`note`,`office_id`,`more_information`");
		if(!is_array($rows)){ $rows = array(); }
		$offices  = $clsSetting->getArraySearchByKey('_OFFICE');
		$tags_set = $clsSetting->getArraySearchByKey('_CHECKIN_TAGS');
		foreach($rows as $r){
			$mi   = $clsISO->to_array_json((!empty($r['more_information'])) ? $r['more_information'] : '');
			$addr = !empty($mi['address']) ? $mi['address'] : '';
			$addr = $clsOC->chat_checkin_address($mi);
			$oid  = (int) $r['office_id'];
			if($oid > 0 && isset($offices[$oid])){ $place = $offices[$oid]['title']; }
			else {
				$resolved = report_resolve_office_name((float)$r['lat'], (float)$r['lng'], $offices);
				$place = ($resolved !== 'Ngoài VP') ? $resolved : (!empty($addr) ? $addr : 'Ngoài VP');
			}
			$tags = array();
			if(isset($mi['tags']) && is_array($mi['tags']) && is_array($tags_set)){
				foreach($mi['tags'] as $tk){
					$tk = (int)$tk;
					if(isset($tags_set[$tk])){

						$tmi = (isset($tags_set[$tk]['more_information']) && is_array($tags_set[$tk]['more_information'])) ? $tags_set[$tk]['more_information'] : array();
						$tags[] = array(
							'label' => isset($tags_set[$tk]['title']) ? $tags_set[$tk]['title'] : '',
							'color' => !empty($tmi['bgcolor']) ? $tmi['bgcolor'] : '#64748b',
							'icon'  => !empty($tmi['icon']) ? $tmi['icon'] : 'bx bx-purchase-tag',
						);
					}
				}
			}
			$items[] = array(
				'time'      => !empty($r['checkin_time']) ? date('H:i', (int)$r['checkin_time']) : '',
				'place'     => $place,
				'address'   => $addr,
				'tags'      => $tags,
				'note'      => isset($r['note']) ? (string)$r['note'] : '',
				'photo'     => isset($r['photo']) ? $r['photo'] : '',
				'dot_color' => !empty($tags) ? $tags[0]['color'] : '#9f223a',
			);
		}
	}

	/* ---- Stats: lần CI / địa điểm (distinct place) / ảnh ---- */
	$stats = array('count'=>count($items), 'places'=>0, 'photos'=>0);
	$seen_places = array();
	foreach($items as $it){
		if(!empty($it['place']) && !isset($seen_places[$it['place']])){ $seen_places[$it['place']] = 1; $stats['places']++; }
		if(!empty($it['photo'])){ $stats['photos']++; }
	}

	$uid = $clsISO->getUniqId();
	$smarty->assign('uid', $uid);
	$smarty->assign('pj_allowed', $allowed ? 1 : 0);
	$smarty->assign('pj_profile', $profile);
	$smarty->assign('pj_items', $items);
	$smarty->assign('pj_stats', $stats);
	$smarty->assign('pj_day_label', $day_label);
	$smarty->assign('pj_is_today', $is_today ? 1 : 0);
	$smarty->assign('pj_day', $day);
	$html = $core->build('_ajax.profile_journey.tpl');
	echo json_encode(array('uid'=>$uid, 'html'=>$html, 'day'=>$day), JSON_UNESCAPED_UNICODE);
}
?>