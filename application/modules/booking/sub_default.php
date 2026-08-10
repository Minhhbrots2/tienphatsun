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
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBooking = new Booking();
	#
	$permiss_add = ($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkDEV()) ? 1 : 0;
	$permiss_full = ($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR')) ? 1 : 0;
	$assign_list["permiss_add"] = $permiss_add;
	$assign_list["permiss_full"] = $permiss_full;
	# booking_type
	$current_time = time();
	$get_booking_type = Input::get('booking_type', 'internal');
	$get_project_id = (int) Input::get('project_id', 0);
	$get_block_id = (int) Input::get('block_id', 0);
	$get_building_id = (int) Input::get('building_id', 0);
	$get_status_id = (int) Input::get('status_id', 0);
	$get_state_id = (int) Input::get('state_id', 0);
	$get_department_id = (int) Input::get('department_id', 0);
	$get_staff_id = (int) Input::get('staff_id', 0);
	$booking_type = ($get_booking_type == 'investor') ? _BOOKING_TYPE_INVESTOR_ID : _BOOKING_TYPE_INTERNAL_ID;
	#
	$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
	$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
	if($clsISO->checkPermissionGroup('ACCOUNTANT')){
		$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1) 
		UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1)");
	} else {
		$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` 
		INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` 
		WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%')");
	}
	if(!empty($arr_projects) && $get_project_id == 0){ $ii = 0;
		foreach($arr_projects as $key => $val){
			if($ii == 0){
				$get_project_id = (int) $val['project_id'];
				$get_block_id = (int) $val['block_id'];
			} 
			++$ii;
		}
	}
	$assign_list["clsBooking"] = $clsBooking;
	$assign_list["arr_projects"] = $arr_projects;
	$assign_list["booking_type"] = $booking_type;
	$assign_list["get_booking_type"] = $get_booking_type;
	$assign_list["get_project_id"] = $get_project_id;
	$assign_list["get_block_id"] = $get_block_id;
	$assign_list["get_building_id"] = $get_building_id;
	$assign_list["get_status_id"] = $get_status_id;
	$assign_list["get_state_id"] = $get_state_id;
	$assign_list["get_department_id"] = $get_department_id;
	$assign_list["get_staff_id"] = $get_staff_id;
	#
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$type_arrs = array(
		'internal' => 'Nội bộ',
		'investor' => 'Chủ ĐT'
	);
	$assign_list["type_arrs"] = $type_arrs;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý Booking - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Quản lý booking - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
	$keyword_page = 'Quản lý booking, '.PAGE_NAME;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_my_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBooking = new Booking();
	#
	$current_time = time();
	$get_view_by = Input::get('view_by', '_table');
	$get_project_id = (int) Input::get('project_id', 0);
	$get_block_id = (int) Input::get('block_id', 0);
	$get_building_id = (int) Input::get('building_id', 0);
	$get_status_id = (int) Input::get('status_id', 0);
	$get_department_id = (int) Input::get('department_id', 0);
	$get_staff_id = (int) Input::get('staff_id', 0);
	
	$list_preloaders = $view_arrs = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$view_arrs = array(
		'_table' => 'bx bx-table',
		'_project' => 'bx bx-list-ul'
	);
	#
	$field = "{$clsProject->pkey},title AS `project_name`";
	$arr_projects = $clsProject->getAll("`is_trash`=0 AND `is_menu`=1 ORDER BY `reg_date` ASC", $field);
	#
	$assign_list["view_arrs"] = $view_arrs;
	$assign_list["arr_projects"] = $arr_projects;
	$assign_list["list_preloaders"] = $list_preloaders;
	$assign_list["get_view_by"] = $get_view_by;
	$assign_list["get_project_id"] = $get_project_id;
	$assign_list["get_block_id"] = $get_block_id;
	$assign_list["get_building_id"] = $get_building_id;
	$assign_list["get_status_id"] = $get_status_id;
	$assign_list["get_department_id"] = $get_department_id;
	$assign_list["get_staff_id"] = $get_staff_id;
	
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý Booking - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Quản lý booking - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
	$keyword_page = 'Quản lý booking, '.PAGE_NAME;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBooking = new Booking();
	#
	$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
	$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
	$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` 
		INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` 
		WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_project\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1)");
	$assign_list["arr_projects"] = $arr_projects;
	/*=============Title & Description Page==================*/
	$title_page = 'Thống kê Booking - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Quản lý booking - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
	$keyword_page = 'Quản lý booking, '.PAGE_NAME;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBooking = new Booking();
	$clsBilling = new Billing();
	#
	$is_project_block = 0;
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	#- Block Info
	if($block_id > 0){
		$more_information = $clsProperty->getOneField('more_information', $block_id);
		$more_information = $clsISO->to_array_json($more_information);
		$billing_type = $core->get_field($more_information, "billing_type", 0);
		$is_project_block = isset($more_information['is_project']) ? $more_information['is_project'] : 0;
	} else {
		$billing_type = _BILLING_TYPE_MWF_ID;
	}
	$smarty->assign('project_id', $project_id);
	$smarty->assign('block_id', $block_id);
	$smarty->assign('building_id', $building_id);
	$smarty->assign('billing_type', $billing_type);
	$smarty->assign('is_project_block', $is_project_block);
	#
	$cond = "`is_trash`=0";
	if($project_id > 0) $cond.= " AND `project_id`='{$project_id}'";
	if($block_id > 0) $cond.= " AND `block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " AND `building_id`='{$building_id}'";
	#- Field
	$field = "{$clsBooking->pkey},`status_id`,`amount`";
	#- End Field
	$total_bookings = $total_amount_bookings = $total_matched_bookings = $total_amount_matched_bookings = 0;
	$total_refund_bookings = $total_amount_refund_bookings = 0;
	$total_hold_bookings = $total_amount_hold_bookings = 0;
	# Internal
	$cnd = $cond." AND `booking_type`='"._BOOKING_TYPE_INTERNAL_ID."'";
	$list_bookings = $clsBooking->getAll($cnd, $field);
	if(!empty($list_bookings)){
		$total_bookings = count($list_bookings);
		foreach($list_bookings as $key => $val){
			$status_id = (int) $val['status_id'];
			$amount = $val['amount'];
			$total_amount_bookings += $clsISO->processSmartNumber($amount);
			if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
				$total_matched_bookings += 1;
				$total_amount_matched_bookings += $clsISO->processSmartNumber($amount);
			} else if($status_id== _BOOKING_REFUND_DEPOSIT_ID){
				$total_refund_bookings += 1;
				$total_amount_refund_bookings += $clsISO->processSmartNumber($amount);
			}
		}
		unset($list_bookings);
	}
	$total_balance_bookings = $total_bookings - $total_matched_bookings - $total_refund_bookings;
	$total_amount_balance_bookings = $total_amount_bookings - $total_amount_matched_bookings - $total_amount_refund_bookings;
	$smarty->assign('total_bookings', $total_bookings);
	$smarty->assign('total_amount_bookings', $total_amount_bookings);
	$smarty->assign('total_matched_bookings', $total_matched_bookings);
	$smarty->assign('total_amount_matched_bookings', $total_amount_matched_bookings);
	$smarty->assign('total_refund_bookings', $total_refund_bookings);
	$smarty->assign('total_amount_refund_bookings', $total_amount_matched_bookings);
	$smarty->assign('total_balance_bookings', $total_balance_bookings);
	$smarty->assign('total_amount_balance_bookings', $total_amount_balance_bookings);
	$smarty->assign('total_hold_bookings', $total_hold_bookings);
	$smarty->assign('total_amount_hold_bookings', $total_amount_hold_bookings);
	# Investor
	$total_invest_bookings = $total_amount_invest_bookings = $total_matched_invest_bookings = $total_amount_matched_invest_bookings = 0;
	$total_payment_invest_bookings = $total_amount_payment_invest_bookings = $total_recall_invest_bookings = $total_amount_recall_invest_bookings = 0;
	
	$cnd = $cond." AND `booking_type`='"._BOOKING_TYPE_INVESTOR_ID."'";
	$list_invest_bookings = $clsBooking->getAll($cnd, $field);
	if(!empty($list_invest_bookings)){
		$total_invest_bookings = count($list_invest_bookings);
		foreach($list_invest_bookings as $key => $val){
			$status_id = (int) $val['status_id'];
			$amount = $val['amount'];
			$total_amount_invest_bookings += $clsISO->processSmartNumber($amount);
			if($status_id == _BOOKING_STATUS_PAYMENT_AUDIT_ID){
				$total_payment_invest_bookings += 1;
				// $total_matched_invest_bookings += 1;
				$total_amount_payment_invest_bookings += $clsISO->processSmartNumber($amount);
				// $total_amount_matched_invest_bookings += $clsISO->processSmartNumber($amount);
			} else if($status_id == _BOOKING_STATUS_UNIT_MATCH_ID){
				$total_matched_invest_bookings += 1;
				$total_amount_matched_invest_bookings += $clsISO->processSmartNumber($amount);
			} else if($status_id == _BOOKING_STATUS_RECALL_ID){
				$total_recall_invest_bookings += 1;
				$total_amount_recall_invest_bookings += $clsISO->processSmartNumber($amount);
			}
		}
		unset($list_bookings);
	}
	$total_balance_invest_bookings = $total_invest_bookings - $total_payment_invest_bookings - $total_matched_invest_bookings - $total_recall_invest_bookings;
	$total_amount_balance_invest_bookings = $total_amount_invest_bookings - $total_amount_payment_invest_bookings - $total_amount_matched_invest_bookings - $total_amount_recall_invest_bookings;
	$smarty->assign('total_invest_bookings', $total_invest_bookings);
	$smarty->assign('total_amount_invest_bookings', $total_amount_invest_bookings);
	$smarty->assign('total_matched_invest_bookings', $total_matched_invest_bookings);
	$smarty->assign('total_amount_matched_invest_bookings', $total_amount_matched_invest_bookings);
	$smarty->assign('total_payment_invest_bookings', $total_payment_invest_bookings);
	$smarty->assign('total_amount_payment_invest_bookings', $total_amount_payment_invest_bookings);
	$smarty->assign('total_recall_invest_bookings', $total_recall_invest_bookings);
	$smarty->assign('total_amount_recall_invest_bookings', $total_amount_recall_invest_bookings);
	$smarty->assign('total_balance_invest_bookings', $total_balance_invest_bookings);
	$smarty->assign('total_amount_balance_invest_bookings', $total_amount_balance_invest_bookings);
	#- Order đầu tiên
	$sql_query = "`is_trash`=0 and `is_cancel`=0 AND `project_id`='{$project_id}'";
	if($billing_type > 0) $sql_query.= " AND `billing_type`='{$billing_type}'"; 
	
	$total_solds = $total_trans_deposit_paid = $total_amount_trans_deposit_paid = 0;
	$field = "{$clsBilling->pkey},`is_deposit_paid`,`deposit_paid_amount`";
	$list_solds = $clsBilling->getAll("{$sql_query} AND `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'", $field);
	if(!empty($list_solds)){
		$total_solds = count($list_solds);
		foreach($list_solds as $key => $val){
			if($val['is_deposit_paid'] == 1){
				$total_trans_deposit_paid += 1;
				$deposit_paid_amount = $val['deposit_paid_amount'];
				$total_amount_trans_deposit_paid+= $clsISO->processSmartNumber($deposit_paid_amount);
			}
		}
		unset($list_solds);
	}
	// $clsISO->print_pre($list_solds); die();
	$smarty->assign('total_solds', $total_solds);
	$smarty->assign('total_trans_deposit_paid', $total_trans_deposit_paid);
	$smarty->assign('total_amount_trans_deposit_paid', $total_amount_trans_deposit_paid);
	#
	$tmp = $clsBilling->getByCond("{$sql_query} order by `reg_date` ASC");
	if($tmp){
		$start_date = $tmp['reg_date'];
		$due_date = strtotime("+14 months", $start_date);
	} else {
		$start_date = time();
		$due_date = strtotime("+14 months", $start_date);
	}
	#- Mảng tháng
	$list_months = array(); 
	$current_month = date('n'); 
	$current_year = date('Y');
	$total_billings = $total_sales = $total_f1 = $total_cross = $total_agrees = $total_contracts = 0;
	for($i=$start_date; $i<= $due_date; $i = strtotime("+1 month", $i)){
		$total_month_billings = $total_month_sales = $total_month_f1 = $total_month_cross = $total_month_agrees = $total_month_contracts = 0;
		if((date('n', $i) <= $current_month && date('Y', $i) == $current_year) || (date('Y', $i) < $current_year)){
			$field = "{$clsBilling->pkey},`totalgrand`,`billing_source_id`,`agree_status_id`,`contract_status_id`";
			$list_billings = $clsBilling->getAll("{$sql_query} AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='".date('m/Y', $i)."'", $field);
			if(!empty($list_billings)){
				$total_month_billings = count($list_billings);
				foreach($list_billings as $key => $val){
					$billing_source_id = (int) $val['billing_source_id'];
					$agree_status_id = (int) $val['agree_status_id'];
					$contract_status_id = (int) $val['contract_status_id'];
					$totalgrand = $clsISO->processSmartNumber($val['totalgrand']);
					$total_month_sales += $totalgrand;
					if($billing_source_id == _BILLING_RESOURCE_F1_ID){
						$total_month_f1 += 1;
					} else if($billing_source_id == _BILLING_RESOURCE_CROSS_ID){
						$total_month_cross += 1;
					}
					if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
						$total_month_agrees += 1;
					}
					if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
						$total_month_contracts += 1;
					}
				}
				unset($list_billings);
			}
		}
		$total_billings += $total_month_billings;
		$total_sales += $total_month_sales;
		$total_f1 += $total_month_f1;
		$total_cross += $total_month_cross;
		$total_agrees += $total_month_agrees;
		$total_contracts += $total_month_contracts;
		$list_months[] = array(
			'month' => $i,
			'total_month_billings' => $total_month_billings,
			'total_month_sales' => $total_month_sales,
			'total_month_f1' => $total_month_f1,
			'total_month_cross' => $total_month_cross,
			'total_month_agrees' => $total_month_agrees,
			'total_month_contracts' => $total_month_contracts,
		);
	}
	$smarty->assign('list_months', $list_months);
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('total_sales', $total_sales);
	$smarty->assign('total_f1', $total_f1);
	$smarty->assign('total_cross', $total_cross);
	$smarty->assign('total_agrees', $total_agrees);
	$smarty->assign('total_contracts', $total_contracts);
	// Return
	$html = $core->build('_ajax.report.tpl');
	Response::echoResponse(200, array(
		'html' => $html
	)); die();
}
function default_open_billing(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsBooking = new Booking();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$arr_staffs = array();
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG');
	$month = Input::post('month', 'all_month');
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$billing_type = (int) Input::post('billing_type', 0);
	#
	$cond = "`is_trash`=0 AND `project_id`='{$project_id}'";
	if($billing_type > 0) $cond.= " AND `billing_type`='{$billing_type}'";
	if($month != "all_month") $cond.= " AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$month}'";
	if($holderG == 'all_fund'){
		// Break
	} else if($holderG == 'deposit_paid_to_company'){
		$cond.= " AND `is_deposit_paid`=1";
	} else if($holderG == 'f1_fund'){
		$cond.= " AND `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'";
	} else if($holderG == 'cross_fund'){
		$cond.= " AND `billing_source_id`='"._BILLING_RESOURCE_CROSS_ID."'";
	} else if($holderG == 'agree_signed'){
		$cond.= " AND `agree_status_id`='"._CONTRACT_STATUS_AGREE_SIGNED_ID."'";
	} else if($holderG == 'contract_signed'){
		$cond.= " AND `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'";
	}
	#
	$titlePage = '';
	if($holderG == 'f1_fund') $titlePage = "quỹ ĐQ";
	if($holderG == 'cross_fund') $titlePage = "quỹ chéo";
	if($holderG == 'agree_signed') $titlePage = "ký VBTT";
	if($holderG == 'contract_signed') $titlePage = "ký HĐMB";
	if($holderG == 'sold') $titlePage = "quỹ ĐQ";
	if($holderG == 'deposit_paid_to_company') $titlePage = " đóng 10% Công ty";
	if($month != "all_month") $titlePage.= " T".$month;
	$smarty->assign('titlePage', $titlePage);
	#
	$total_billings = 0;
	$field = "{$clsBilling->pkey},`deposit_date`,`stock_code`,`staff_id`,`totalgrand`,`more_information`";
	$field.= ",`billing_source_id`,`is_deposit_paid`,`is_cancel`";
	$list_billings = $clsBilling->getAll($cond." ORDER BY `reg_date` DESC", $field);
	if(!empty($list_billings)){
		$total_billings = count($list_billings);
		$arr_staff_ids = $arr_property_cached = array();
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			$billing_source_id = (int) $val['billing_source_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(!in_array($staff_id,  $arr_staff_ids)){
				$arr_staff_ids[] = $staff_id;
			}
			$list_billings[$key]['more_information'] = $more_information;
			if($billing_source_id > 0){
				if(!isset($arr_property_cached[$billing_source_id])){
					$arr_property_cached[$billing_source_id] = $clsBilling->getBillingSource($billing_source_id);
				}
				$list_billings[$key]['billing_source'] = $arr_property_cached[$billing_source_id];
			} else {
				$list_billings[$key]['billing_source'] = "";
			}
		}
		if(!empty($arr_staff_ids)){
			$p_field = "`t1`.{$clsProfile->pkey},`t1`.`code`,`t1`.`first_name`,`t1`.`last_name`,`t1`.`full_name`,`t2`.`title` as `department_name`";
			$tmp = $dbconn->getAll("SELECT {$p_field} FROM {$clsProfile->tbl} AS `t1` 
				INNER JOIN {$clsProperty->tbl} AS `t2` ON `t1`.`department_id`=`t2`.`{$clsProperty->pkey}` AND `t2`.`property_type`='_DEPARTMENT' 
				WHERE `t1`.`{$clsProfile->pkey}` in (".implode(',',$arr_staff_ids).")");
			if(!empty($tmp)){
				foreach($tmp as $okey => $oval){
					$staff_id = $oval[$clsProfile->pkey];
					$arr_staffs[$staff_id] = array(
						'staff_name' => $clsProfile->getIndentityV2($staff_id, $oval),
						'department_name' => $oval['department_name']
					);
				}
				unset($tmp);
			}
		}
	}
	// $clsISO->print_pre($list_billings); die();
	$smarty->assign('arr_staffs', $arr_staffs);
	$smarty->assign('total_billings', $total_billings);
	$smarty->assign('list_billings', $list_billings);
	// Return
	$html = $core->build('_ajax.billing.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_bookings(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$image_page,$type_list,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	#
	$permiss_admin = $clsISO->checkPermissionGroup('ADMIN_PROJECT') ? 1 : 0;
	$permiss_accounting = $clsISO->checkPermissionGroup('ACCOUNTANT') ? 1 : 0;
	$smarty->assign('permiss_admin', $permiss_admin);
	$smarty->assign('permiss_accounting', $permiss_accounting);
	#
	$booking_type = (int) Input::post('booking_type', _BOOKING_TYPE_INTERNAL_ID);
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$state_id = (int) Input::post('state_id', 0);
	$department_id = (int) Input::post('department_id', 0);
	$staff_id = (int) Input::post('staff_id', 0);
	$keysearch = Input::post('keysearch');
	
	$cond = "`t1`.`is_trash`=0 AND `t1`.`booking_type`='{$booking_type}'";
	if($project_id > 0) $cond.= " AND `t1`.`project_id`='{$project_id}'";
	if($block_id > 0) $cond.= " AND `t1`.`block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " AND `t1`.`building_id`='{$building_id}'";
	if($status_id > 0) $cond.= " AND `t1`.`status_id`='{$status_id}'";
	if($state_id > 0) $cond.= " AND `t1`.`state_id`='{$state_id}'";
	if(!empty($keysearch)){
		$cond.= " AND (JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.customer_name\")) LIKE '%{$keysearch}%' 
			OR JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.content\")) LIKE '%{$keysearch}%')";
	}
	if($department_id > 0 && $staff_id == 0){
		$cond.= " AND (JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.department_id\"))='{$department_id}')";
	} else if($staff_id > 0){
		$cond.= " AND (JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.staff_id\"))='{$staff_id}')";
	}
	#
	$total_price = $total_record = 0;
	if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
		$total_matched = $total_refund = $total_balance = $total_deposit_paid = $total_pending = $total_wait_refund = 0;
		$total_price_matched = $total_price_refund = $total_price_deposit_paid = $total_price_pending = $total_price_wait_refund = 0; 
	} else {
		$total_unit_match = $total_payment_audit = 0;
		$total_price_unit_match = $total_price_payment_audit = 0;
	}
	// $dbconn->debug = true;
	if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
		$field = "`t1`.*,`t2`.`more_information` AS `meta_information`";
		$list_bookings = $dbconn->getAll("SELECT {$field} FROM `{$clsBooking->tbl}` AS `t1` 
		LEFT JOIN `{$clsBookingMeta->tbl}` AS `t2` ON `t1`.`booking_id`=`t2`.`booking_id` AND `t2`.`type`='priority' 
		WHERE {$cond} ORDER BY `t1`.`reg_date` DESC");
	} else {
		$field = "`t1`.*";
		$clsBooking->setAlias("t1");
		$list_bookings = $clsBooking->getAll("{$cond} ORDER BY `t1`.`reg_date` DESC");
	}
	if(!empty($list_bookings)){
		$total_record = count($list_bookings);
		$arr_project_cached = $arr_property_cached = $arr_profile_cached = $arr_status_cached = $arr_floor_range_cached = array();
		$list_projects = $clsProject->getAll("`is_trash`=0", "{$clsProject->pkey},`code`,`title`");
		if(!empty($list_projects)){
			foreach($list_projects as $key => $val){
				$arr_project_cached[$val[$clsProject->pkey]] = $val['code'];
			}
			unset($list_projects);
		}
		$tmp = $clsProperty->getCacheItems('_BEDROOM');
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$bedroom_id = $val[$clsProperty->pkey];
				$arr_property_cached[$bedroom_id] = $val['title'];
			}
			unset($tmp);
		}
		$p_field = "{$clsProperty->pkey},`title`,`image`";
		$tmp = $clsProperty->getAll("`is_trash`=0 AND (`property_type`='_BOOKING_STATUS' OR `property_type`='_BOOKING_STATE')", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$image = $val['image'];
				$arr_property_cached[$val[$clsProperty->pkey]] = '<span class="badge '.$image.' w-100 text-upper">'.$val['title'].'</span>';
			}
			unset($tmp);
		}
		if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
			$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BUILDING' 
				AND `for_id`='{$block_id}'", "more_information");
			if(!empty($tmp)){
				foreach($tmp as $okey => $oval){
					$more_information = $oval['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$floor_range_configs = $core->get_field($more_information, "floor_range_configs", []);
					if(!empty($floor_range_configs)){
						foreach($floor_range_configs as $okey => $oval){
							if($oval['floor_type'] == 'consecutive'){
								$arr_floor_range_cached[$okey] = sprintf('%s→%s', $oval['from'], $oval['to']);
							} else {
								$arr_floor_range_cached[$okey] = sprintf('%s', $oval['floor']);
							}	
						}
					}
				}
				unset($tmp);
			}
		}
		foreach($list_bookings as $key => $val){
			$department_id = (int) $val['department_id'];
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$status_id = (int) $val['status_id'];
			$state_id = (int) $val['state_id'];
			$amount =  $clsISO->processSmartNumber($val['amount']);
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$total_price += $amount; // + Tổng tiền
			if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
				$is_deposit_paid = (int) $val['is_deposit_paid'];
				$meta_information = $val['meta_information'];
				$meta_information = $clsISO->to_array_json($meta_information);
				$floor_range = $core->get_field($meta_information, "floor_range", "");
				if(!empty($floor_range) && isset($arr_floor_range_cached[$floor_range])){
					$meta_information['floor_range'] = $arr_floor_range_cached[$floor_range];
				}
				$list_bookings[$key]['meta_information'] = $meta_information;
				if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
					$total_matched += 1;
					$total_price_matched += $amount;
					if($is_deposit_paid == 1){
						$total_deposit_paid += 1;
						$total_price_deposit_paid += $amount;
					}
				} else if($status_id == _BOOKING_REFUND_DEPOSIT_ID){
					$total_refund += 1;
					$total_price_refund += $amount;
					if($state_id == _BOOKING_STATE_REFUNDING_ID){
						$total_wait_refund += 1;
						$total_price_wait_refund += $amount;
					}
				} else if($status_id == _BOOKING_STATUS_PENDING_ID){
					$total_pending += 1;
					$total_price_pending += $amount;
				}
				$staff_id = (int) $core->get_field($more_information, "staff_id", 0);
				if($staff_id > 0 && isset($arr_profile_cached[$staff_id])){
					$oStaff = $arr_profile_cached[$staff_id];
				} else {
					$field = "`t1`.`code`,`t1`.`full_name`,`t1`.`first_name`,`t1`.`last_name`,`t2`.`title` as `department_name`";
					$oStaff = $dbconn->getRow("select {$field} FROM {$clsProfile->tbl} AS `t1` 
					INNER JOIN {$clsProperty->tbl} AS `t2` ON t1.department_id=t2.property_id 
					WHERE `t1`.profile_id='{$staff_id}'");
					$arr_profile_cached[$staff_id] = $oStaff;
				}
				$list_bookings[$key]['oStaff'] = $oStaff;
				$list_bookings[$key]['state_name'] = $arr_property_cached[$state_id];
			} else {
				if($status_id == _BOOKING_STATUS_UNIT_MATCH_ID){
					$total_unit_match += 1;
					$total_payment_audit += $amount;
				} else if($status_id == _BOOKING_STATUS_PAYMENT_AUDIT_ID){
					$total_payment_audit += 1;
					$total_price_payment_audit += $amount;
				}
			}
			$bedroom_name = $block_name = $status_name = "";
			if($bedroom_id > 0 && isset($arr_property_cached[$bedroom_id])){
				$bedroom_name = $arr_property_cached[$bedroom_id];
			}
			if($block_id > 0){
				if(isset($arr_property_cached[$block_id])){
					$block_name = $arr_property_cached[$block_id];
				} else {
					$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
					$block_name = $arr_property_cached[$block_id];
				}
			}
			if($status_id > 0 && isset($arr_property_cached[$status_id])){
				$status_name = $arr_property_cached[$status_id];
			}
			$list_bookings[$key]['status_name'] = $status_name;
			$list_bookings[$key]['bedroom_name'] = $bedroom_name;
			if(!empty($block_name)){
				$list_bookings[$key]['project_name'] = sprintf('%s / %s', $arr_project_cached[$project_id], $block_name);
			} else {
				$list_bookings[$key]['project_name'] = $arr_project_cached[$project_id];
			}
			$list_bookings[$key]['more_information'] = $more_information;
			$list_bookings[$key]['is_cancel'] = ($status_id == _BOOKING_STATUS_CANCEL_ID) ? 1 : 0;
		}
	}
	$smarty->assign('clsBooking', $clsBooking);
	$smarty->assign('booking_type', $booking_type);
	$smarty->assign('list_bookings', $list_bookings);
	if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
		$total_balance = $total_record - $total_matched - $total_refund;
		$total_price_balance = $total_price - $total_price_matched - $total_price_refund;
		$html_briefs = '<div data-toggle="ripple" class="brief-item cursor-pointer a1a bg-orange" status_id="0" 
			onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Tổng booking</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_record.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a2a cursor-pointer bg-azure" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'"
			onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Khớp cọc</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_matched.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_matched).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a6a cursor-pointer bg-solid" status_id="'._BOOKING_REFUND_DEPOSIT_ID.'" onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Hoàn cọc</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_refund.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_refund).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item cursor-pointer a1a bg-secondary" status_id="0" 
			onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Chờ hoàn cọc</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_wait_refund.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_wait_refund).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a3a bg-cyan cursor-pointer">
			<p class="text-fs-13 mb-0">10% vào FH</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_deposit_paid.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_deposit_paid).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a5a bg-purple cursor-pointer">
			<p class="text-fs-13 mb-0">Tổng cọc tồn</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_balance.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_balance).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a6a cursor-pointer bg-dark">
			<p class="text-fs-13 mb-0">Chưa duyệt</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-0 text-white">'.$total_pending.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_pending).'</h3>
		</div>';
	} else {
		$html_briefs = '<div data-toggle="ripple" class="brief-item a1a cursor-pointer bg-orange" status_id="0" 
			onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Tổng booking</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_record.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a2a cursor-pointer bg-azure" status_id="'._BOOKING_STATUS_UNIT_MATCH_ID.'" 
			onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Khớp căn</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-0 text-white">'.$total_unit_match.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_unit_match).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a6a cursor-pointer bg-solid" status_id="'._BOOKING_STATUS_PAYMENT_AUDIT_ID.'" 
			onClick="$Core.booking.set_status(this, event)">
			<p class="text-fs-13 mb-0">Tra soát TĐTT</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-1 text-white">'.$total_payment_audit.' BK</h3>
			<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_payment_audit).'</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a3a cursor-pointer bg-cyan">
			<p class="text-fs-13 mb-0">10% vào FH</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a5a cursor-pointer bg-green">
			<p class="text-fs-13 mb-0">Tổng tồn 10%</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
		</div>
		<div data-toggle="ripple" class="brief-item a5a cursor-pointer bg-purple">
			<p class="text-fs-13 mb-0">Tổng cọc tồn</p>
			<hr class="w-px-50 my-2" />
			<h3 class="text-fs-16 mb-0 text-white">0 BK</h3>
		</div>';
	}
	// Return
	$html = $core->build('_ajax.booking.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html,
		'total_record' => $total_record,
		'html_briefs' => $html_briefs
	)); die();
}
function default_load_mybookings(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	#
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$state_id = (int) Input::post('state_id', 0);
	$view_by = Input::post('view_by', "_table");
	$cond = "`t1`.`is_trash`=0 AND `t1`.`booking_type`='"._BOOKING_TYPE_INTERNAL_ID."'";
	if($project_id > 0) $cond.= " AND `t1`.`project_id`='{$project_id}'";
	if($block_id > 0) $cond.= " AND `t1`.`block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " AND `t1`.`building_id`='{$building_id}'";
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY')){
		$department_id = $oneProfile['department_id'];
		$cond.= " AND (JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.staff_id\"))='{$profile_id}')";
	} else {
		$cond.= " AND (JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.staff_id\"))='{$profile_id}')";
	}
	if($state_id > 0) $cond.= " AND `t1`.`state_id`='{$state_id}'";
	if($status_id > 0) $cond.= " AND `t1`.`status_id`='{$status_id}'";
	#
	// $clsISO->print_pre($cond); die();
	$total_price = $total_record = 0;
	$total_matched = $total_refund = $total_balance = $total_deposit_paid = $total_pending = 0;
	$total_price_matched = $total_price_refund = $total_price_deposit_paid = $total_price_pending = 0; 
	// $dbconn->debug = true;
	$field = "`t1`.*,`t2`.`more_information` AS `meta_information`";
	$list_bookings = $dbconn->getAll("SELECT {$field} FROM `{$clsBooking->tbl}` AS `t1` 
	LEFT JOIN `{$clsBookingMeta->tbl}` AS `t2` ON `t1`.`booking_id`=`t2`.`booking_id` AND `t2`.`type`='priority' 
	WHERE {$cond} ORDER BY `t1`.`reg_date` DESC");
	// $clsISO->print_pre($list_bookings); die();
	if(!empty($list_bookings)){
		$total_record = count($list_bookings);
		$arr_project_cached = $arr_property_cached = $arr_status_cached = $arr_floor_range_cached = array();
		if($view_by == '_table'){
			$tmp = $clsProject->getAll("`is_trash`=0", "{$clsProject->pkey},`code`,`title`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_project_cached[$val[$clsProject->pkey]] = $val['code'];
				}
				unset($tmp);
			}
			$p_field = "{$clsProperty->pkey},`more_information`";
			$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BUILDING' 
				AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.floor_range_configs\")) IS NOT NULL", $p_field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$more_building = $val['more_information'];
					$more_building = $clsISO->to_array_json($more_building);
					$floor_range_configs = $core->get_field($more_building, "floor_range_configs", []);
					if(!empty($floor_range_configs)){
						foreach($floor_range_configs as $nkey => $nval){
							if($nval['floor_type'] == 'consecutive'){
								$arr_floor_range_cached[$nkey] = sprintf('%s→%s', $nval['from'], $nval['to']);
							} else {
								$arr_floor_range_cached[$nkey] = $nval['floor'];
							}
						}
					}
				}
				unset($tmp);
			}
		}
		$tmp = $clsProperty->getCacheItems('_BEDROOM');
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$bedroom_id = $val[$clsProperty->pkey];
				$arr_property_cached[$bedroom_id] = $val['title'];
			}
			unset($tmp);
		}
		$p_field = "{$clsProperty->pkey},`title`,`image`";
		$tmp = $clsProperty->getAll("`is_trash`=0 AND (`property_type`='_BOOKING_STATUS' OR `property_type`='_BOOKING_STATE')", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$image = $val['image'];
				$arr_property_cached[$val[$clsProperty->pkey]] = '<span class="badge '.$image.' text-upper">'.$val['title'].'</span>';
			}
			unset($tmp);
		}
		foreach($list_bookings as $key => $val){
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$state_id = (int) $val['state_id'];
			$status_id = (int) $val['status_id'];
			$more_information = $core->get_field($val, "more_information", []);
			$meta_information = $core->get_field($val, "meta_information", []);
			$more_information = $clsISO->to_array_json($more_information);
			$meta_information = $clsISO->to_array_json($meta_information);
			$amount = $val['amount'];
			$amount = $clsISO->processSmartNumber($amount);
			$total_price += $amount; // + Tổng tiền
			$is_deposit_paid = (int) $val['is_deposit_paid'];
			if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
				$total_matched += 1;
				$total_price_matched += $amount;
				if($is_deposit_paid == 1){
					$total_deposit_paid += 1;
					$total_price_deposit_paid += $amount;
				}
			} else if($status_id == _BOOKING_REFUND_DEPOSIT_ID){
				$total_refund += 1;
				$total_price_refund += $amount;
			} else if($status_id == _BOOKING_STATUS_PENDING_ID){
				$total_pending += 1;
				$total_price_pending += $amount;
			}
			$bedroom_name = $block_name = $status_name = $state_name = "";
			if($bedroom_id > 0 && isset($arr_property_cached[$bedroom_id])){
				$bedroom_name = $arr_property_cached[$bedroom_id];
			}
			if($state_id > 0 && isset($arr_property_cached[$state_id])){
				$state_name = $arr_property_cached[$state_id];
			}
			if($status_id > 0 && isset($arr_property_cached[$status_id])){
				$status_name = $arr_property_cached[$status_id];
			}
			$list_bookings[$key]['state_name'] = $state_name;
			$list_bookings[$key]['status_name'] = $status_name;
			$list_bookings[$key]['bedroom_name'] = $bedroom_name;
			if($view_by == '_table'){
				$floor_range = $core->get_field($meta_information, "floor_range", "");
				if(!empty($floor_range) && array_key_exists($floor_range, $arr_floor_range_cached)){
					$meta_information['floor_range'] = $arr_floor_range_cached[$floor_range];
				}
				if($block_id > 0){
					if(isset($arr_property_cached[$block_id])){
						$block_name = $arr_property_cached[$block_id];
					} else {
						$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
						$block_name = $arr_property_cached[$block_id];
					}
				}
				if(!empty($block_name)){
					$list_bookings[$key]['project_name'] = sprintf('%s / %s', $arr_project_cached[$project_id], $block_name);
				} else {
					$list_bookings[$key]['project_name'] = $arr_project_cached[$project_id];
				}
			}
			$list_bookings[$key]['more_information'] = $more_information;
			$list_bookings[$key]['meta_information'] = $meta_information;
		}
	}
	if($view_by == '_table'){
		$smarty->assign('list_bookings', $list_bookings);
	} else if($view_by == '_project'){
		$arr_projects = array();
		if(!empty($list_bookings)){
			$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`
				,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
			$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
			$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1) UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1)");
			if(!empty($arr_projects)){
				foreach($arr_projects as $key => $val){
					$project_id = (int) $val['project_id'];
					$block_id = (int) $val['block_id'];
					foreach($list_bookings as $okey => $oval){
						if($project_id == $oval['project_id'] && $oval['block_id'] == $block_id){
							$meta_information = $core->get_field($oval, "meta_information", []);
							$floor_range = $core->get_field($meta_information, "floor_range", "");
							$arr_projects[$key]['list_bookings'][] = $oval;
						}
					}
				}
				foreach($arr_projects as $key => $val){
					$project_id = (int) $val['project_id'];
					$block_id = (int) $val['block_id'];
					$list_bookings = $val['list_bookings'];
					if(!empty($list_bookings)){
						$arr_floor_range_cached = array();
						if($block_id > 0){
							$tmp = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$block_id}'", "more_information");
							if(!empty($tmp)){
								foreach($tmp as $mkey => $mval){
									$more_building = $mval['more_information'];
									$more_building = $clsISO->to_array_json($more_building);
									$floor_range_configs = $core->get_field($more_building, "floor_range_configs", []);
									if(!empty($floor_range_configs)){
										foreach($floor_range_configs as $nkey => $nval){
											if($nval['floor_type'] == 'consecutive'){
												$arr_floor_range_cached[$nkey] = sprintf('%s→%s', $nval['from'], $nval['to']);
											} else {
												$arr_floor_range_cached[$nkey] = $nval['floor'];
											}
										}
									}
								}
								unset($tmp);
							}
						}
						foreach($list_bookings as $okey => $oval){
							$meta_information = $core->get_field($oval, "meta_information", []);
							$floor_range = $core->get_field($meta_information, "floor_range", "");
							if(!empty($floor_range) && array_key_exists($floor_range, $arr_floor_range_cached)){
								$meta_information['floor_range'] = $arr_floor_range_cached[$floor_range];
								$list_bookings[$okey]['meta_information'] = $meta_information;
							}
						}
						$arr_projects[$key]['list_bookings'] = $list_bookings;
					}
				}
			}
		}
		$smarty->assign('arr_projects', $arr_projects);
	}
	$smarty->assign('clsBooking', $clsBooking);
	$smarty->assign('view_by', $view_by);
	$total_balance = $total_record - $total_matched - $total_refund;
	$total_price_balance = $total_price - $total_price_matched - $total_price_refund;
	$html_briefs = '<div data-toggle="ripple" class="brief-item cursor-pointer a1a bg-orange" status_id="0" 
		onClick="$Core.booking.set_status(this, event)">
		<p class="text-fs-13 mb-0">Tổng booking</p>
		<hr class="w-px-50 my-2" />
		<h3 class="text-fs-16 mb-1 text-white">'.$total_record.' BK</h3>
		<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price).'</h3>
	</div>
	<div data-toggle="ripple" class="brief-item a2a cursor-pointer bg-azure" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'"
		onClick="$Core.booking.set_status(this, event)">
		<p class="text-fs-13 mb-0">Khớp cọc</p>
		<hr class="w-px-50 my-2" />
		<h3 class="text-fs-16 mb-1 text-white">'.$total_matched.' BK</h3>
		<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_matched).'</h3>
	</div>
	<div data-toggle="ripple" class="brief-item a6a cursor-pointer bg-solid" status_id="'._BOOKING_REFUND_DEPOSIT_ID.'" onClick="$Core.booking.set_status(this, event)">
		<p class="text-fs-13 mb-0">Hoàn cọc</p>
		<hr class="w-px-50 my-2" />
		<h3 class="text-fs-16 mb-1 text-white">'.$total_refund.' BK</h3>
		<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_refund).'</h3>
	</div>
	<div data-toggle="ripple" class="brief-item a3a bg-cyan cursor-pointer">
		<p class="text-fs-13 mb-0">10% vào FH</p>
		<hr class="w-px-50 my-2" />
		<h3 class="text-fs-16 mb-1 text-white">'.$total_deposit_paid.' BK</h3>
		<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_deposit_paid).'</h3>
	</div>
	<div data-toggle="ripple" class="brief-item a5a bg-purple cursor-pointer">
		<p class="text-fs-13 mb-0">Tổng cọc tồn</p>
		<hr class="w-px-50 my-2" />
		<h3 class="text-fs-16 mb-1 text-white">'.$total_balance.' BK</h3>
		<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_balance).'</h3>
	</div>
	<div data-toggle="ripple" class="brief-item a6a cursor-pointer bg-dark">
		<p class="text-fs-13 mb-0">Chưa duyệt</p>
		<hr class="w-px-50 my-2" />
		<h3 class="text-fs-16 mb-0 text-white">'.$total_pending.' BK</h3>
		<h3 class="text-fs-16 mb-0 text-white">'.$clsISO->shortNumber($total_price_pending).'</h3>
	</div>';
	// Return
	$html = $core->build('_ajax.mybooking.tpl');
	Response::echoResponse(200, array(
		'html' => $html,
		'total_record' => $total_record,
		'html_briefs' => $html_briefs
	)); die();
}
function default_view_booking(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsProfile = new Profile();
	#
	$uid = $clsISO->getUniqid();
	$booking_id = (int) Input::post('booking_id', 0);
	$oneBooking = $clsBooking->getOne($booking_id);
	$booking_type = (int) $oneBooking['booking_type'];
	$state_id = (int) $oneBooking['state_id'];
	$status_id = (int) $oneBooking['status_id'];
	$project_id = (int) $oneBooking['project_id'];
	$block_id = (int) $oneBooking['block_id'];
	$building_id = (int) $oneBooking['building_id'];
	$action_logs = $oneBooking['action_logs'];
	$more_information = $oneBooking['more_information'];
	$action_logs = $clsISO->to_array_json($action_logs);
	$more_information = $clsISO->to_array_json($more_information);
	$cancel_info = $core->get_field($more_information, "cancel_info", []);
	#
	$oneBookingMeta = $oneDepositPaid = array($clsBookingMeta->pkey => 0, 'more_information' => []);
	$is_lock_recall = $is_lock_unit_match = $is_lock_payment_audit = 0;
	$is_matched_added = $is_refund_added = $is_lock_priority = $is_lock_matched = $is_lock_refund = 0;
	if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
		$is_matched_added = 1; // Đã thêm khớp căn
		$is_lock_priority = 1; // Lock thay đổi ưu tiên
		$is_lock_refund = 1; // Lock hoàn cọc
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='matched' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
		$oneDepositPaid = $clsBookingMeta->getByCond("`type`='deposit_to_company' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneDepositPaid['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneDepositPaid['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_REFUND_DEPOSIT_ID){
		$is_lock_matched = $is_lock_priority = 1; // Lock khớp căn & thay đổi ưu tiên
		if($state_id == _BOOKING_STATE_REFUNDING_ID){
			$oneBookingMeta = $clsBookingMeta->getByCond("`type`='refund_req' AND `booking_id`='{$booking_id}'");
			$is_refund_added = !empty($oneBookingMeta) ? 1 : 0;
		} else if($state_id == _BOOKING_STATE_REFUNDED_ID){
			$is_refund_added = 1; // Đã thêm ưu tiên
			$oneBookingMeta = $clsBookingMeta->getByCond("`type`='refund' AND `booking_id`='{$booking_id}'");
			$is_refund_added = !empty($oneBookingMeta) ? 1 : 0;
		}
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	}  else if($status_id == _BOOKING_STATUS_RECALL_ID){
		$is_lock_unit_match = $is_lock_payment_audit = 1;
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='recall' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_STATUS_UNIT_MATCH_ID){
		$is_lock_recall = $is_lock_payment_audit = 1;
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='unit_match' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_STATUS_PAYMENT_AUDIT_ID){
		$is_lock_recall = $is_lock_unit_match = 1;
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='payment_audit' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_STATUS_CANCEL_ID){
		$is_lock_priority = $is_lock_matched = $is_lock_refund = 1;
	}
	$onePriority = $clsBookingMeta->getByCond("`type`='priority' AND `booking_id`='{$booking_id}' limit 0,1");
	if(!empty($onePriority)){
		$is_priority_added = 1;
		$_more_information = $onePriority['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
	} else {
		$is_priority_added = 0;
		$onePriority = array($clsBookingMeta->pkey => 0, 'more_information' => []);
	}
	$arr_profile_ins[] = $oneBooking['user_id_update'];
	if($oneBooking['status_id'] == _BOOKING_STATUS_CANCEL_ID){
		$arr_profile_ins[] = $cancel_info['user_id'];	
	}
	if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
		if($state_id == _BOOKING_STATE_PENDING_ID){
			$is_lock_priority = $is_lock_matched = $is_lock_refund = 1;
		}
		$arr_profile_ins[] = $core->get_field($more_information, "staff_id", 0);
	}
	/** Kế toán thì chỉ được sử dụng Hoàn cọc*/
	if($clsISO->checkPermissionGroup('ACCOUNTANT')){
		$is_lock_priority = 1;
		$is_lock_matched = 1;
	}
	$oneUserCanceled = $oneUserUpdated = $oneStaffSale = array();
	$Field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`";
	$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $arr_profile_ins).")", $Field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			if($val[$clsProfile->pkey] == $oneBooking['user_id_update']){
				$oneUserUpdated = $val;
			} else if($oneBooking['status_id'] == _BOOKING_STATUS_CANCEL_ID 
				&& $val[$clsProfile->pkey] == $cancel_info['user_id']){
				$oneUserCanceled = $val;
			} else if($booking_type == _BOOKING_TYPE_INTERNAL_ID 
				&& $val[$clsProfile->pkey] == $core->get_field($more_information, "staff_id", 0)){
				$oneStaffSale = $val;
			}
		}
		unset($tmp);
	}
	$smarty->assign('oneStaffSale', $oneStaffSale);
	$smarty->assign('oneUserUpdated', $oneUserUpdated);
	$smarty->assign('oneUserCanceled', $oneUserCanceled);
	#
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('oneBooking', $oneBooking);
	$smarty->assign('booking_type', $booking_type);
	$smarty->assign('onePriority', $onePriority);
	$smarty->assign('oneBookingMeta', $oneBookingMeta);
	$smarty->assign('oneDepositPaid', $oneDepositPaid);
	$smarty->assign('action_logs', $action_logs);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('cancel_info', $cancel_info);
	$smarty->assign('is_priority_added', $is_priority_added);
	$smarty->assign('is_matched_added', $is_matched_added);
	$smarty->assign('is_refund_added', $is_refund_added);
	$smarty->assign('is_lock_priority', $is_lock_priority);
	$smarty->assign('is_lock_matched', $is_lock_matched);
	$smarty->assign('is_lock_refund', $is_lock_refund);
	
	$smarty->assign('is_lock_recall', $is_lock_recall);
	$smarty->assign('is_lock_unit_match', $is_lock_unit_match);
	$smarty->assign('is_lock_payment_audit', $is_lock_payment_audit);
	// Return
	$html = $core->build('_ajax.view_booking.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_content(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	#
	$booking_id = (int) Input::post('booking_id', 0);
	$oneBooking = $clsBooking->getOne($booking_id);
	// $clsISO->print_pre($oneBooking); die();
	$booking_type = (int) $oneBooking['booking_type'];
	$bedroom_id = (int) $oneBooking['bedroom_id'];
	$status_id = (int) $oneBooking['status_id'];
	$state_id = (int) $oneBooking['state_id'];
	$project_id = (int) $oneBooking['project_id'];
	$block_id = (int) $oneBooking['block_id'];
	$building_id = (int) $oneBooking['building_id'];
	$more_information = $oneBooking['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	#
	$oneBookingMeta = $oneBilling = $onePriority = $floor_range_configs = array();
	if($building_id > 0){
		$building_information = $clsProperty->getOneField("more_information", $building_id);
		$building_information = $clsISO->to_array_json($building_information);
		$floor_range_configs = $core->get_field($building_information, "floor_range_configs", []);
	}
	if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='matched' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$stock_code = $core->get_field($_more_information, "stock_code", "");
		if(!empty($stock_code)){
			$b_field = "{$clsBilling->pkey},`billing_code`";
			$oneBilling = $clsBilling->getByCond("`is_trash`=0 and stock_code='{$stock_code}'", $b_field);
		}
		$oneBookingMeta['more_information'] = $_more_information;
		$oneDepositPaid = $clsBookingMeta->getByCond("`type`='deposit_to_company' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneDepositPaid['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneDepositPaid['more_information'] = $_more_information;
		$smarty->assign('oneDepositPaid', $oneDepositPaid);
	} else if($status_id == _BOOKING_REFUND_DEPOSIT_ID){
		if($state_id == _BOOKING_STATE_REFUNDING_ID){
			$oneBookingMeta = $clsBookingMeta->getByCond("`type`='refund_req' AND `booking_id`='{$booking_id}'");
		} else if($state_id == _BOOKING_STATE_REFUNDED_ID){
			$oneBookingMeta = $clsBookingMeta->getByCond("`type`='refund' AND `booking_id`='{$booking_id}'");
		}
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_STATUS_RECALL_ID){
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='recall' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_STATUS_UNIT_MATCH_ID){
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='unit_match' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	} else if($status_id == _BOOKING_STATUS_PAYMENT_AUDIT_ID){
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='payment_audit' AND `booking_id`='{$booking_id}'");
		$_more_information = $oneBookingMeta['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$oneBookingMeta['more_information'] = $_more_information;
	}
	#
	$onePriority = $clsBookingMeta->getByCond("`type`='priority' AND `booking_id`='{$booking_id}' ORDER BY `reg_date` DESC limit 0,1");
	if(!empty($onePriority)){
		$_more_information = $onePriority['more_information'];
		$_more_information = $clsISO->to_array_json($_more_information);
		$floor_range = $core->get_field($_more_information, "floor_range", "");
		if(!empty($floor_range) && !empty($floor_range_configs)){
			foreach($floor_range_configs as $okey => $oval){
				if($okey == $floor_range){
					if($oval['floor_type'] == 'consecutive'){
						$_more_information['floor_range'] = sprintf('%s -> %s', $oval['from'], $oval['to']);
					} else {
						$_more_information['floor_range'] = sprintf('%s', $oval['floor']);
					}
					break;
				}
			}
		}
		$onePriority['more_information'] = $_more_information;
	} else {
		$onePriority = array(
			$clsBookingMeta->pkey => 0, 
			'more_information' => []
		);
	}
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('onePriority', $onePriority);
	$smarty->assign('oneBookingMeta', $oneBookingMeta);
	##
	$arr_property = array(); 
	$oneBooking['block_name'] = $oneBooking['building_name'] = $oneBooking['bedroom_name'] = "N/A";
	if($block_id > 0) $arr_property[] = $block_id;
	if($building_id > 0) $arr_property[] = $building_id;
	if($bedroom_id > 0) $arr_property[] = $bedroom_id;
	if(!empty($arr_property)){
		$prop_field = "{$clsProperty->pkey},title";
		$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_property).")", $prop_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if($block_id > 0 && $val[$clsProperty->pkey] == $block_id){
					$block_name = $val['title'];
					$oneBooking['block_name'] = $block_name;
				} else if($building_id > 0 && $val[$clsProperty->pkey] == $building_id){
					$building_name = $val['title'];
					$oneBooking['building_name'] = $building_name;
				} else if($bedroom_id > 0 && $val[$clsProperty->pkey] == $bedroom_id){
					$bedroom_name = $val['title'];
					$oneBooking['bedroom_name'] = $bedroom_name;
				}
			}
			unset($tmp);
		}
	}
	#
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('booking_type', $booking_type);
	$smarty->assign('oneBooking', $oneBooking);
	$smarty->assign('clsBooking', $clsBooking);
	$smarty->assign('more_information', $more_information);
	// Return
	$html = $core->build('_ajax.content.tpl');
	Response::echoResponse(200, array(
		'html' => $html
	)); die();
}
function default_open(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$type_list,$profile_id;
	$clsBooking = new Booking();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsBookingMeta = new BookingMeta();
	$smarty->assign('clsBooking', $clsBooking);
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$permiss_full = $clsISO->checkPermissionGroup('ADMIN_PROJECT') ? 1 : 0;
	$smarty->assign('permiss_full', $permiss_full);
	
	$uid 			= $clsISO->getUniqid();
	$booking_id 	= (int) Input::post('booking_id', 0);
	$project_id 	= (int) Input::post('project_id', 0);
	$block_id 		= (int) Input::post('block_id', 0);
	$building_id 	= (int) Input::post('building_id', 0);
	$booking_type 	= (int) Input::post('booking_type', _BOOKING_TYPE_INTERNAL_ID);
	$smarty->assign('floor_arrs', array(
		'low_floor' => 'Tầng thấp',
		'mid_floor' => 'Tầng trung',
		'high_floor' => 'Tầng cao'
	));
	/** Get Remember */
	$more_information = $oneProfile['more_information'];
	$booking_remembered = $core->get_field($more_information, "booking_remembered", []);
	$oneRemembered = $core->get_field($booking_remembered, $booking_type, []);
	if(!$project_id) $project_id = $core->get_field($oneRemembered, 'project_id', 0);
	if(!$block_id) $block_id = $core->get_field($oneRemembered, 'block_id', 0);
	if(!$building_id) $building_id = $core->get_field($oneRemembered, 'building_id', 0);
	#
	$action = "_add";
	$more_information = $arr_blocks = $arr_buildings = $oneBookingMeta = $template_configs = array();
	$more_information = array('staff_id' => 0, 'department_id' => 0);
	$oneBooking = array(
		'type_id' => _BOOKING_TYPE_BOOKING_ID,
		'booking_type' => $booking_type,
		'project_id' => $project_id,
		'block_id' => $block_id,
		'building_id' => $building_id,
		'booking_date' => time(),
		'booking_code' => $clsBooking->getCode($booking_type, $block_id, time()),
		'bedroom_id' => 0, // Default
		'amount' => 50000000
	);
	if($booking_id > 0){
		$action = "_edit";
		$oneBooking = $clsBooking->getOne($booking_id);
		$more_information = $oneBooking['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
			$tmp = $clsBookingMeta->getByCond("`type`='priority' AND `booking_id`='{$booking_id}'");
			if(!empty($tmp)){
				$_more_information = $tmp['more_information'];
				$oneBookingMeta = $clsISO->to_array_json($_more_information);
			}
		}
	}
	#
	$prop_field = "{$clsProperty->pkey},`title`";
	$block_id = (int) $core->get_field($oneBooking, 'block_id', 0);
	$project_id = (int) $core->get_field($oneBooking, 'project_id', 0);
	$building_id = (int) $core->get_field($oneBooking, 'building_id', 0);
	if($project_id > 0){
		$arr_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
		and `for_id`='{$project_id}' order by `order_no` ASC", $prop_field);
	}
	#
	if($block_id > 0){
		$arr_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
		and `for_id`='{$block_id}' order by `order_no` ASC", $prop_field);
	}
	#
	$html_floor_range_options = '<option value="">Khoảng tầng</option>';
	if($building_id > 0){
		$building_information = $clsProperty->getOneField('more_information', $building_id);
		$building_information = $clsISO->to_array_json($building_information);
		$floor_range_configs = $core->get_field($building_information, "floor_range_configs", []);
		$template_configs = $core->get_field($building_information, "template", []);
		if(!empty($floor_range_configs)){
			foreach($floor_range_configs as $key => $val){
				if($val['floor_type'] == 'consecutive'){
					$text = $val['from']. '->'.$val['to'];
				} else {
					$text = $val['floor'];
				}
				$html_floor_range_options.= '<option'.($core->get_field($oneBookingMeta, "floor_range", "") == $key ? ' selected' : '').' level="'.$val['level'].'" value="'.$key.'">'.$text.'</option>';
			}
		}
	}
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('booking_type', $booking_type);
	$smarty->assign('oneBooking', $oneBooking);
	$smarty->assign('oneBookingMeta', $oneBookingMeta);
	$smarty->assign('arr_blocks', $arr_blocks);
	$smarty->assign('arr_buildings', $arr_buildings);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('template_configs', $template_configs);
	$smarty->assign('html_floor_range_options', $html_floor_range_options);
	// Return
	$html = $core->build('_ajax.open.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$image_page,$type_list,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	#
	$msg = "_error"; $more = array();
	$type_id = (int) Input::post('type_id', 0);
	$booking_id = (int) Input::post('booking_id', 0);
	$booking_type = (int) Input::post('booking_type', _BOOKING_TYPE_INTERNAL_ID); //0: global, 1: investor
	$booking_code = Input::post('booking_code', 0);
	$booking_date = Input::post('booking_date', 0);
	$booking_time = !empty($booking_date) ? $clsISO->toTime($booking_date) : 0;
	$project_id = Input::post('project_id', 0);
	$block_id = Input::post('block_id', 0);
	$building_id = Input::post('building_id', 0);
	$amount = Input::post('amount', 0);
	$customer_name = Input::post('customer_name', "");
	$customer_phone = Input::post('customer_phone', "");
	$customer_email = Input::post('customer_email', "");
	$customer_idcard = Input::post('customer_idcard', "");
	$ccid_front = Input::post('ccid_front', "");
	$ccid_back = Input::post('ccid_back', ""); 
	$content = Input::post('content'); // Nội dung UNC
	$payment_order = Input::post('payment_order'); // File UNC
	$bedroom_id = (int) Input::post('bedroom_id', 0);
	$unit_axis = Input::post('unit_axis');
	$floor_range = Input::post('floor_range');
	$floor_type = Input::post('floor_type');
	$is_remember = (int) Input::post('is_remember', 1);
	if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
		$staff_id = (int) Input::post('staff_id', 0);
		$department_id = (int) Input::post('department_id', 0);
	} else {
		$trans_code = Input::post('trans_code');
	}
	// Ghi nhớ lại các thông tin đã chọn trước đó.
	if($is_remember == 1){
		$more_information = $oneProfile['more_information'];
		$more_information['booking_remembered'][$booking_type] = array(
			'project_id' => $project_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'amount' => $clsISO->processSmartNumber($amount)
		);
		$clsProfile->updateOne($profile_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
	}
	if($booking_id > 0){
		$oneBooking = $clsBooking->getOne($booking_id);
		$more_information = $oneBooking['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['content'] = $content;
		$more_information['payment_order'] = $payment_order;
		$more_information['customer_name'] = $customer_name;
		$more_information['customer_phone'] = $customer_phone;
		$more_information['customer_email'] = $customer_email;
		$more_information['ccid_front'] = $ccid_front;
		$more_information['ccid_back'] = $ccid_back;
		$more_information['notes'] = Input::post('notes');
		if($booking_type == _BOOKING_TYPE_INVESTOR_ID){
			$more_information['trans_code'] = $trans_code;
			$more_information['floor_type'] = $floor_type;
			$more_information['unit_axis'] = $unit_axis;
			$more_information['floor_range'] = $floor_range;
			$more['type_id'] = $type_id;
		} else {
			$more_information['staff_id'] = $staff_id;
			$more_information['department_id'] = $department_id;
			$more_information['customer_idcard'] = $customer_idcard;
		}
		if($clsBooking->updateOne($booking_id, array_merge($more, array(
			'booking_type' => $booking_type,
			'booking_code' => $booking_code,
			'booking_date' => $booking_time,
			'project_id' => $project_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'bedroom_id' => $bedroom_id,
			'customer_name' => $customer_name,
			'amount' => $clsISO->processSmartNumber($amount),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id_update' => $profile_id,
			'upd_date' => time(),
		)))){
			$msg = "_success";
			if($booking_type == _BOOKING_TYPE_INTERNAL_ID && (!empty($unit_axis) || !empty($floor_range))){
				$priority = 1;
				$oneBookingMeta = $clsBookingMeta->getByCond("`type`='priority' and `booking_id`='{$booking_id}'");
				if(!empty($oneBookingMeta)){
					$more_information = $oneBookingMeta['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information['project_id'] = $project_id;
					$more_information['block_id'] = $block_id;
					$more_information['building_id'] = $building_id;
					$more_information['unit_axis'] = $unit_axis;
					$more_information['floor_range'] = $floor_range;
					$more_information['floor_type'] = $floor_type;
					$more_information['priority'] = $priority;
					$clsBookingMeta->updateOne($oneBookingMeta[$clsBookingMeta->pkey], array(
						'action_date' => $booking_time,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'user_id_update' => $profile_id,
						'upd_date' => time()
					));
				} else {
					$total_bookings = $clsBookingMeta->countItem("`type`='priority' AND `booking_id`<>'{$booking_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.project_id\"))='{$project_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.block_id\"))='{$block_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.building_id\"))='{$building_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.unit_axis\"))='{$unit_axis}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.floor_range\"))='{$floor_range}'");
					if($total_bookings >= 1) $priority = ($total_bookings+1);
					$more_information = array();
					$more_information['project_id'] = $project_id;
					$more_information['block_id'] = $block_id;
					$more_information['building_id'] = $building_id;
					$more_information['unit_axis'] = $unit_axis;
					$more_information['floor_range'] = $floor_range;
					$more_information['floor_type'] = $floor_type;
					$more_information['priority'] = $priority;
					$clsBookingMeta->insert(array(
						'type' => 'priority',
						'booking_id' => $booking_id,
						'action_date' => $booking_time,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'user_id' => $profile_id,
						'user_id_update' => $profile_id,
						'reg_date' => time(),
						'upd_date' => time()
					));
				}
			}
		}
	} else {
		$booking_id = $clsBooking->getMaxId();
		
		$booking_code = $clsBooking->getCode($booking_type, $block_id, time());
		$more_information = $action_logs = array();
		$more_information['content'] = $content;
		$more_information['payment_order'] = $payment_order;
		$more_information['customer_name'] = $customer_name;
		$more_information['customer_phone'] = $customer_phone;
		$more_information['customer_email'] = $customer_email;
		$more_information['ccid_front'] = $ccid_front;
		$more_information['ccid_back'] = $ccid_back;
		$more_information['notes'] = Input::post('notes');
		/** Logs */
		$content_logs = array();
		if(!empty($booking_code)){
			$content_logs[] = "Mã booking: ".$booking_code;
		}
		if(!empty($booking_date)){
			$content_logs[] = "Ngày booking: ".$booking_date;
		}
		if($booking_type == _BOOKING_TYPE_INTERNAL_ID){
			$more['type_id'] = $type_id;
			$more_information['staff_id'] = $staff_id;
			$more_information['department_id'] = $department_id;
			if($staff_id > 0) {
				$content_logs[] = "Họ tên Sale/ PKD: ".$clsProfile->getFullName($staff_id);
			}
			if(!empty($customer_name)){
				$content_logs[] = "Khách hàng trên UNC: ".$customer_name;
			}
			if(!empty($content)){
				$content_logs[] = "Nội dung: ".$content;
			}
		} else {
			$more_information['customer_idcard'] = $customer_idcard;
			$more_information['trans_code'] = $trans_code;
			$more_information['floor_type'] = $floor_type;
			$more_information['unit_axis'] = $unit_axis;
			$more_information['floor_range'] = $floor_range;
		}
		if($project_id > 0){
			$content_logs[] = "Dự án: ".$clsProject->getTitle($project_id);
		}
		$arr_property = array();
		if($block_id > 0) $arr_property[] = $block_id;
		if($building_id > 0) $arr_property[] = $building_id;
		if(!empty($arr_property)){
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_property).")", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if($block_id > 0 && $val[$clsProperty->pkey] == $block_id){
						$block_name = $val['title'];
						$content_logs[] = "Phân khu/Block: ".$block_name;
					} else if($building_id > 0 && $val[$clsProperty->pkey] == $building_id){
						$building_name = $val['title'];
						$content_logs[] = "Tòa nhà: ".$building_name;
					}
				}
				unset($tmp);
			}
		}
		if(!empty($amount)){
			$content_logs[] = "Số tiền: ".$amount;
		}
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' => sprintf('<strong>%s</strong> đã tạo booking mới với %s', 
			$clsProfile->getFullName($profile_id, $oneProfile), implode(', ', $content_logs))
		);
		$state_id = _BOOKING_STATE_PENDING_ID;
		if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
			$state_id = _BOOKING_STATE_APPROVED_ID;
		}
		/* End Logs */
		if($clsBooking->insert(array_merge($more, array(
			$clsBooking->pkey => $booking_id,
			'booking_type' => $booking_type,
			'booking_code' => $booking_code,
			'booking_date' => $booking_time,
			'project_id' => $project_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'bedroom_id' => $bedroom_id,
			'state_id' => $state_id,
			'status_id' => _BOOKING_WAIT_DEPOSIT_ID,
			'customer_name' => $customer_name,
			'amount' => $clsISO->processSmartNumber($amount),
			'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time(),
		)))){
			$msg = "_success";
			if($booking_type == _BOOKING_TYPE_INTERNAL_ID && (!empty($unit_axis) || !empty($floor_range))){
				$priority = 1;
				$total_bookings = $clsBookingMeta->countItem("`type`='priority' AND `booking_id`<>'{$booking_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.project_id\"))='{$project_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.block_id\"))='{$block_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.building_id\"))='{$building_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.unit_axis\"))='{$unit_axis}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.floor_range\"))='{$floor_range}'");
				if($total_bookings >= 1) $priority = ($total_bookings + 1);
				#
				$more_information = array();
				$more_information['project_id'] = $project_id;
				$more_information['block_id'] = $block_id;
				$more_information['building_id'] = $building_id;
				$more_information['unit_axis'] = $unit_axis;
				$more_information['floor_range'] = $floor_range;
				$more_information['floor_type'] = $floor_type;
				$more_information['priority'] = $priority;
				$clsBookingMeta->insert(array(
					'type' => 'priority',
					'booking_id' => $booking_id,
					'action_date' => $booking_time,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'user_id' => $profile_id,
					'user_id_update' => $profile_id,
					'reg_date' => time(),
					'upd_date' => time()
				));
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_upload_image(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$profile_id;
	$clsBilling = new Billing();
	#
	$msg = "_error"; $uploaded_image = "";
	$to_field = Input::post('to_field');
	$booking_id = (int) Input::post('booking_id', 0);
	if($clsISO->_DEV()){
		echo sprintf('%s|||%s', "_success", "https://drive.google.com/file/d/1WOrs088-on2LqE4JeHy3S44L23m3ov6G/view?usp=drive_link");die(); 
	}
	if(isset($_POST['hid']) && $_POST['hid'] == 'upload'){
		if(!empty($_FILES['upload_image']['name'])){
			$person_info = array();
			if(@is_uploaded_file($_FILES['upload_image']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_image = $clsUploadFile->uploadItem($_FILES["upload_image"],"/BOOKING",EXTENSION_FILE_UPLOAD);
				if(!empty($upload_image) && file_exists(ROOTPATH . $uploaded_image)){
					$msg = "_success";
					$file_name = $_FILES['upload_image']['name'];
					$file_size = $_FILES['upload_image']['size'];
					// Upload file to google drive
					$folder_id = GOOGLE_DRIVE_FOLDER_BOOKING_ID;
					$clsGoogleUpload = new GoogleUpload($folder_id, true);
					$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_image,$folder_id);
					$uploaded_image = $clsISO->genGoogleURL($createdFile->getId());
					@unlink(ROOTPATH . $upload_image);
				}
			}
		}
	}
	// Return
	echo sprintf('%s|||%s', $msg, $uploaded_image);die();
}
function default_load_staff(){
	global $smarty,$profile_id,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$_options = array();
	$staff_id = (int) Input::post('staff_id', 0);
	$department_id = (int) Input::post('department_id', 0);
	$field = "{$clsProfile->pkey},`code`,`avatar`,`full_name`,`first_name`,`last_name`,`status_id`";
	$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	if($department_id > 0) $cond.= " AND (`list_department_id` LIKE '%|{$department_id}|%')";
	$tmp = $clsProfile->getAll("{$cond} order by `start_date` ASC", $field);
	if(!empty($tmp)){
		foreach($tmp as $keuy => $val){
			$status_id = (int) $val['status_id'];
			$_options[] = array(
				'id' => $val[$clsProfile->pkey],
				'text' => sprintf('%s-%s', $val['code'], $val['full_name']).($status_id == _STATUS_STAFF_OFF_ID ? '<span class="badge bg-label-danger ml-1">Đã nghỉ</span>' : ""),
				'image' => $clsProfile->getAvatar($val[$clsProfile->pkey], $val, 40, 40)
			);
		}
		unset($tmp);
	}
	// Return
	echo json_encode($_options); die();
}
function default_load_block(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id;
	$clsProperty = new Property();
	$uid = Input::post('uid');
	$block_id = Input::post('block_id', 0);
	$project_id = Input::post('project_id', 0);
	$call_from = Input::post('call_from', "add_edit");
	#
	$html = '<select onChange="$Core.booking.load_building(this, event)" data-field="block_id" name="block_id" 
	uid="'.$uid.'" call_from="'.$call_from.'" class="iso-selectize'.(in_array($call_from, ['search', 'search_stock_hug'])?' search_field':'').($call_from=='search_stock_hug'?' w-px-200':'').' form-control required">		
		<option value="0">Phân khu</option>';
	$field = "{$clsProperty->pkey},`title`";
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$list_blocks = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BLOCK' AND `for_id`='{$project_id}' 
		AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.project_admins_slash\")) like '%|{$profile_id}|%' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1 order by `order_no` ASC", $field);
	} else if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ACCOUNTANT') || $clsISO->checkDEV()){
		$list_blocks = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BLOCK' AND `for_id`='{$project_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1 order by `order_no` ASC", $field);
	}
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html.= '<option'.($block_id==$val[$clsProperty->pkey] ? ' selected': '').' 
				value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
		}
		unset($list_blocks);
	}
	$html.='</select>';
	// Return
	echo $html; die();
}
function default_load_building(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProperty = new Property();
	#
	$uid = Input::post('uid');
	$block_id = Input::post('block_id', 0);
	$building_id = Input::post('building_id', 0);
	$call_from = Input::post('call_from', "add_edit");
	#
	$html = '<select name="building_id" data-field="building_id" onChange="$Core.booking.do_changed(this, event)" uid="'.$uid.'" 
		class="iso-selectizeSync'.(in_array($call_from, ['search', 'search_stock_hug'])?' search_field':'').($call_from=='search_stock_hug'?' w-px-200':'').' form-control" call_from="'.$call_from.'">
		<option value="0">Tòa nhà</option>';
	$field = "{$clsProperty->pkey},`title`";
	$list_items = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
		and `for_id`='{$block_id}' order by `order_no` ASC", $field);
	if(!empty($list_items)){
		foreach($list_items as $key => $val){
			$html.= '<option'.($building_id==$val[$clsProperty->pkey] ? ' selected': '').' 
				value="'.$val[$clsProperty->pkey].'">
				'.$val['title'].'
			</option>';
		}
		unset($list_items);
	}
	$html.='</select>';
	// Return
	echo $html; die();
}
function default_load_floor_range(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsProperty = new Property();
	##
	$building_id = (int) Input::post('building_id', 0);
	$more_information = $clsProperty->getOneField('more_information', $building_id);
	$more_information = $clsISO->to_array_json($more_information);
	$template_configs = $core->get_field($more_information, "template", []);
	$floor_range_configs = $core->get_field($more_information, "floor_range_configs", []);
	// $clsISO->print_pre($template); die();
	##
	$html_floor_options = '<option value="">Khoảng tầng</option>';
	$html_stock_options = '<option value="">Trục căn</option>';
	if(!empty($floor_range_configs)){
		foreach($floor_range_configs as $key => $val){
			if($val['floor_type'] == 'consecutive'){
				$text = $val['from']. '->'.$val['to'];
			} else {
				$text = $val['floor'];
			}
			$html_floor_options.= '<option level="'.$val['level'].'" value="'.$key.'">'.$text.'</option>';
		}
		unset($floor_range_configs);
	}
	if(!empty($template_configs)){
		foreach($template_configs as $key => $val){
			$html_stock_options.= '<option bedroom_id="'.$val['bedroom_id'].'" value="'.$val['code'].'">'.$val['code'].'</option>';
		}
		unset($template_configs);
	}
	// Return
	echo json_encode(array(
		'html_stock_options' => $html_stock_options,
		'html_floor_options' => $html_floor_options
	)); die();
}
function default_open_activity(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG');
	$booking_id = (int) Input::post('booking_id', 0);
	$activity_id = (int) Input::post('activity_id', 0);
	$booking_type = (int) Input::post('booking_type', _BOOKING_TYPE_INTERNAL_ID);
	#Loại tầng
	$floor_arrs = array(
		'low_floor' => 'Tầng thấp',
		'mid_floor' => 'Tầng trung',
		'high_floor' => 'Tầng cao'
	);
	$money_arrs = array();
	for($i=1; $i<=2; $i++){
		$money_arrs[] = $clsISO->formatPrice($i*50000000);
	}
	$smarty->assign('money_arrs', $money_arrs);
	$smarty->assign('floor_arrs', $floor_arrs);
	$oneBooking = $clsBooking->getOne($booking_id);
	$block_id = (int) $core->get_field($oneBooking, "block_id", 0);
	$project_id = (int) $core->get_field($oneBooking, "project_id", 0);
	$building_id = (int) $core->get_field($oneBooking, "building_id", 0);
	#
	$titlePage = "ưu tiên";
	if($holderG == 'recall') $titlePage = 'thu hồi';
	if($holderG == 'refund') $titlePage = 'hoàn cọc';
	if($holderG == 'refund_req') $titlePage = 'y/c hoàn cọc';
	if($holderG == 'matched') $titlePage = 'khớp cọc';
	if($holderG == 'unit_match') $titlePage = 'khớp căn';
	if($holderG == 'payment_audit') $titlePage = 'tra soát TĐTT';
	if($holderG == 'deposit_to_company') $titlePage = 'đóng 10% vào công ty';
	##
	$action = '_add';
	$more_information = array();
	$oneItem = array('action_date' => time());
	if($holderG == 'deposit_to_company'){
		$oneBookingMeta = $clsBookingMeta->getByCond("`type`='matched' AND `booking_id`='{$booking_id}'", "more_information");
		if(!empty($oneBookingMeta)){
			$_more_information = $oneBookingMeta['more_information'];
			$_more_information = $clsISO->to_array_json($_more_information);
			if(isset($_more_information['stock_code']) && !empty($_more_information['stock_code']))
				$more_information['stock_code'] = $_more_information['stock_code'];
		}
	}
	if($activity_id > 0){
		$action = "_edit";
		$oneItem = $clsBookingMeta->getOne($activity_id);
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
	} else {
		if($holderG == 'refund'){
			$oneItem = $clsBookingMeta->getByCond("`type`='{$holderG}' AND `booking_id`='{$booking_id}'");
			if(!empty($oneItem)){
				$action = "_edit";
				$more_information = $oneItem['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
			}
		}
	}
	$html_floor_range_options = '<option value="">Khoảng tầng</option>';
	if($holderG == 'priority'){
		$list_blocks = $list_buildings = array();
		$prop_field = "{$clsProperty->pkey},`title`";
		if($project_id > 0){
			$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
			and `for_id`='{$project_id}' order by `order_no` ASC", $prop_field);
		}
		if($block_id > 0){
			$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
			and `for_id`='{$block_id}' order by `order_no` ASC", $prop_field);
		}
		if($building_id > 0){
			$building_information = $clsProperty->getOneField('more_information', $building_id);
			$building_information = $clsISO->to_array_json($building_information);
			$floor_range_configs = $core->get_field($building_information, "floor_range_configs", []);
			$template_configs = $core->get_field($building_information, "template", []);
			if(!empty($floor_range_configs)){
				foreach($floor_range_configs as $key => $val){
					$text = ($val['floor_type'] == 'consecutive') ? $val['from']. '->'.$val['to'] : $val['floor'];
					$html_floor_range_options.= '<option'.($core->get_field($more_information, "floor_range", "") == $key ? ' selected' : '').' level="'.$val['level'].'" value="'.$key.'">'.$text.'</option>';
				}
			}
		}
		$smarty->assign('list_blocks', $list_blocks);
		$smarty->assign('list_buildings', $list_buildings);
		$smarty->assign('template_configs', $template_configs);
	}
	// $clsISO->print_pre($oneItem); die();
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('holderG', $holderG);
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('activity_id', $activity_id);
	$smarty->assign('booking_type', $booking_type);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('oneBooking', $oneBooking);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('html_floor_range_options', $html_floor_range_options);
	// Return
	$html = $core->build('_ajax.open_activity.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_activity(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$msg = "_error"; $more = array();
	$holderG = Input::post('holderG');
	$booking_id = (int) Input::post('booking_id', 0);
	$activity_id = (int) Input::post('activity_id', 0);
	$action_date = Input::post('action_date');
	$action_time = !empty($action_date) ? $clsISO->toTime($action_date) : 0;
	$oneBooking = $clsBooking->getOne($booking_id);
	$project_id = (int) $core->get_field($oneBooking, 'project_id', 0);
	$block_id = (int) $core->get_field($oneBooking, 'block_id', 0);
	$building_id = (int) $core->get_field($oneBooking, 'building_id', 0);
	$action_logs = $oneBooking['action_logs'];
	$action_logs = $clsISO->to_array_json($action_logs);
	#
	if($activity_id > 0){
		$oneItem = $clsBookingMeta->getOne($activity_id);
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['notes'] = Input::post('notes');
		if($holderG == 'matched'){
			$amount = Input::post('amount');
			$staff_sale_id = (int) Input::post('staff_sale_id', 0);
			$more_information['amount'] = $clsISO->processSmartNumber($amount);
			$more_information['staff_sale_id'] = $staff_sale_id;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật khớp cọc vào ngày 
				<strong>%s</strong> với số tiền <strong>%s</strong>, người đứng số <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $amount, $clsProfile->getFullName($staff_sale_id))
			);
		} else if($holderG == 'refund'){
			$amount = Input::post('amount');
			$receiver_id = Input::post('receiver_id', 0);
			$payment_order = Input::post('payment_order', 0);
			$more_information['amount'] = $clsISO->processSmartNumber($amount);
			$more_information['receiver_id'] = $receiver_id;
			$more_information['payment_order'] = $payment_order;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật hoàn cọc vào ngày 
				<strong>%s</strong>, số tiền <strong>%s</strong>, người nhận <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $amount, 
					$clsProfile->getFullName($receiver_id)));
			$more['state_id'] = _BOOKING_STATE_REFUNDED_ID;
		} else if($holderG == 'refund_reg'){
			$amount = Input::post('amount');
			$receiver_id = Input::post('receiver_id', 0);
			$more_information['amount'] = $clsISO->processSmartNumber($amount);
			$more_information['receiver_id'] = $receiver_id;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật yêu cầu hoàn cọc vào ngày 
					<strong>%s</strong>, số tiền <strong>%s</strong>, người nhận <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $amount, $clsProfile->getFullName($receiver_id)));
			$more['state_id'] = _BOOKING_STATE_REFUNDING_ID;
		} else if($holderG == 'priority'){
			$unit_axis = Input::post('unit_axis');
			$floor_range = Input::post('floor_range');
			$floor_type = Input::post('floor_type');
			$priority = (int) Input::post('priority', 0);
			$more_information['project_id'] = $project_id;
			$more_information['block_id'] = $block_id;
			$more_information['building_id'] = $building_id;
			$more_information['unit_axis'] = $unit_axis;
			$more_information['floor_range'] = $floor_range;
			$more_information['floor_type'] = $floor_type;
			$more_information['priority'] = $priority;
			#
			$is_changed = 0;
			if($core->get_field($more_information, "project_id", 0) != $project_id) $is_changed = 1;
			if($core->get_field($more_information, "block_id", 0) != $block_id) $is_changed = 1;
			if($core->get_field($more_information, "building_id", 0) != $building_id) $is_changed = 1;
			if($core->get_field($more_information, "unit_axis", 0) != $unit_axis) $is_changed = 1;
			if($core->get_field($more_information, "floor_range", 0) != $floor_range) $is_changed = 1;
			if($is_changed == 1 && 1==2){
				$total_bookings = $clsBookingMeta->countItem("`type`='priority' AND `booking_id`<>'{$booking_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.project_id\"))='{$project_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.block_id\"))='{$block_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.building_id\"))='{$building_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.unit_axis\"))='{$unit_axis}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.floor_range\"))='{$floor_range}'");
				if($total_bookings >= 1) {
					$priority = ($total_bookings+1);
				} else {
					$priority = 1;
				}
				$more_information['priority'] = $priority;
			}
			$more['state_id'] = _BOOKING_STATE_PRIORITIZED_ID;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật ưu tiên vào ngày <strong>%s</strong>, trục căn: <strong>%s</strong>, khoảng tầng: <strong>%s</strong>, loại tầng: <strong>%s</strong>, trạng thái: <strong>%s</strong>, Ưu tiên: <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $action_date, $unit_axis, $floor_range, $clsBooking->getFloorType($floor_type), $clsProperty->getTitleCache('_BOOKING_STATE', _BOOKING_STATE_PRIORITIZED_ID), $priority));
		} else if($holderG == 'unit_match'){
			$stock_code = Input::post('stock_code');
			$name_owner = Input::post('name_owner');
			$reg_sign_link = Input::post('reg_sign_link');
			$payment_order = Input::post('payment_order');
			$payment_order_check = Input::post('payment_order_check');
			$more_information['stock_code'] = $stock_code;
			$more_information['name_owner'] = $name_owner;
			$more_information['reg_sign_link'] = $reg_sign_link;
			$more_information['payment_order'] = $payment_order;
			$more_information['payment_order_check'] = $payment_order_check;
			$more['status_id'] = _BOOKING_STATUS_UNIT_MATCH_ID;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật khớp căn vào ngày <strong>%s</strong>, 
				mã căn: <strong>%s</strong>, người đứng tên: <strong>%s</strong>, Link ĐKXN: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $stock_code, $name_owner, $reg_sign_link));
		} else if($holderG == 'payment_audit'){
			$stock_code = Input::post('stock_code');
			$content = Input::post('content');
			$payment_order = Input::post('payment_order');
			$payment_order_check = Input::post('payment_order_check');
			$more_information['stock_code'] = $stock_code;
			$more_information['content'] = $content;
			$more_information['payment_order'] = $payment_order;
			$more_information['payment_order_check'] = $payment_order_check;
			$more['status_id'] = _BOOKING_STATUS_PAYMENT_AUDIT_ID;
			// $more['deposit_paid_amount'] = $clsISO->processSmartNumber($amount);
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật tra soát TĐTT vào ngày: <strong>%s</strong>, 
				mã căn: <strong>%s</strong>, nội dung: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $stock_code, $content));
		} else if($holderG == 'recall'){
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật thu hồi vào ngày <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date));
		}
		if($clsBookingMeta->updateOne($activity_id, array(
			'action_date' => $action_time,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
			$clsBooking->updateOne($booking_id, array_merge($more, array(
				'upd_date' => time(),
				'user_id_update' => $profile_id,
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
			)));
		}
	} else {
		$activity_id = $clsBookingMeta->getMaxId();
		$more_information = array();
		$more_information['notes'] = Input::post('notes');
		if($holderG == 'matched'){
			$amount = Input::post('amount');
			$stock_code = Input::post('stock_code');
			$staff_sale_id = (int) Input::post('staff_sale_id', 0);
			$more_information['stock_code'] = $stock_code;
			$more_information['amount'] = $clsISO->processSmartNumber($amount);
			$more_information['staff_sale_id'] = $staff_sale_id;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới khớp cọc vào ngày 
				<strong>%s</strong> với số tiền <strong>%s</strong>, người đứng số <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $amount, $clsProfile->getFullName($staff_sale_id))
			);
			$more['status_id'] = _BOOKING_MATCHED_DEPOSIT_ID;
			$more['state_id'] = _BOOKING_STATE_DEPOSIT_MATCHED_ID;
		} else if($holderG == 'refund'){
			$amount = Input::post('amount');
			$receiver_id = Input::post('receiver_id', 0);
			$payment_order = Input::post('payment_order', 0);
			$more_information['amount'] = $clsISO->processSmartNumber($amount);
			$more_information['receiver_id'] = $receiver_id;
			$more_information['payment_order'] = $payment_order;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới hoàn cọc vào ngày 
				<strong>%s</strong> số tiền <strong>%s</strong>, người nhận <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $amount, 
					$clsProfile->getFullName($receiver_id)));
			$more['state_id'] = _BOOKING_STATE_REFUNDED_ID;
		} else if($holderG == 'refund_req'){
			$amount = Input::post('amount');
			$receiver_id = Input::post('receiver_id', 0);
			$more_information['amount'] = $clsISO->processSmartNumber($amount);
			$more_information['receiver_id'] = $receiver_id;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới yêu cầu hoàn cọc vào ngày 
					<strong>%s</strong>, số tiền <strong>%s</strong>, người nhận <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $amount, $clsProfile->getFullName($receiver_id)));
			$more['status_id'] = _BOOKING_REFUND_DEPOSIT_ID;
			$more['state_id'] = _BOOKING_STATE_REFUNDING_ID;
		} else if($holderG == 'priority'){
			$unit_axis = Input::post('unit_axis');
			$floor_range = Input::post('floor_range');
			$floor_type = Input::post('floor_type');
			$priority = (int) Input::post('priority', 0);
			$more_information['project_id'] = $project_id;
			$more_information['block_id'] = $block_id;
			$more_information['building_id'] = $building_id;
			$more_information['unit_axis'] = $unit_axis;
			$more_information['floor_range'] = $floor_range;
			$more_information['floor_type'] = $floor_type;
			if(1==2){
				$total_bookings = $clsBookingMeta->countItem("`type`='priority' AND `booking_id`<>'{$booking_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.project_id\"))='{$project_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.block_id\"))='{$block_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.building_id\"))='{$building_id}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.unit_axis\"))='{$unit_axis}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`, \"$.floor_range\"))='{$floor_range}'");
				if($total_bookings >= 1) {
					$priority = ($total_bookings+1);
				} else {
					$priority = 1;
				}
			}
			$more['state_id'] = _BOOKING_STATE_PRIORITIZED_ID;
			$more_information['priority'] = $priority;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới ưu tiên vào ngày <strong>%s</strong>, trục căn: <strong>%s</strong>, khoảng tầng: <strong>%s</strong>, loại tầng: <strong>%s</strong>, trạng thái: <strong>%s</strong>, Ưu tiên: <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $action_date, $unit_axis, $floor_range, $clsBooking->getFloorType($floor_type), $clsProperty->getTitleCache('_BOOKING_STATE', _BOOKING_STATE_PRIORITIZED_ID), $priority));
		} else if($holderG == 'unit_match'){
			$stock_code = Input::post('stock_code');
			$name_owner = Input::post('name_owner');
			$reg_sign_link = Input::post('reg_sign_link');
			$payment_order = Input::post('payment_order');
			$payment_order_check = Input::post('payment_order_check');
			$more_information['stock_code'] = $stock_code;
			$more_information['name_owner'] = $name_owner;
			$more_information['reg_sign_link'] = $reg_sign_link;
			$more_information['payment_order'] = $payment_order;
			$more_information['payment_order_check'] = $payment_order_check;
			$more['status_id'] = _BOOKING_STATUS_UNIT_MATCH_ID;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới khớp căn vào ngày <strong>%s</strong>, 
				mã căn: <strong>%s</strong>, người đứng tên: <strong>%s</strong>, Link ĐKXN: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $stock_code, $name_owner, $reg_sign_link));
		} else if($holderG == 'payment_audit'){
			$stock_code = Input::post('stock_code');
			$content = Input::post('content');
			$payment_order = Input::post('payment_order');
			$payment_order_check = Input::post('payment_order_check');
			$more_information['stock_code'] = $stock_code;
			$more_information['content'] = $content;
			$more_information['payment_order'] = $payment_order;
			$more_information['payment_order_check'] = $payment_order_check;
			// $more['is_deposit_paid'] = 1;
			// $more['deposit_paid_amount'] = $clsISO->processSmartNumber($amount);
			$more['status_id'] = _BOOKING_STATUS_PAYMENT_AUDIT_ID;
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới tra soát TĐTT vào ngày: <strong>%s</strong>, 
				mã căn: <strong>%s</strong>, nội dung: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date, $stock_code, $content));
		} else if($holderG == 'recall'){
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã thêm mới thu hồi vào ngày <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_date));
			$more['status_id'] = _BOOKING_STATUS_RECALL_ID;
		}
		// $clsISO->print_pre($more_information); die();
		if($clsBookingMeta->insert(array(
			$clsBookingMeta->pkey => $activity_id,
			'type' => $holderG,
			'booking_id' => $booking_id,
			'action_date' => $action_time,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
			$clsBooking->updateOne($booking_id, array_merge($more, array(
				'upd_date' => time(),
				'user_id_update' => $profile_id,
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
			)));
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_approved(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	
	$msg = "_error";
	$action_date = time();
	$booking_id = (int) Input::post('booking_id', 0);
	if($booking_id > 0){
		$oneBooking = $clsBooking->getOne($booking_id);
		$status_id = $oneBooking['status_id'];
		$action_logs = $oneBooking['action_logs'];
		$action_logs = $clsISO->to_array_json($action_logs);
		#
		$state_arrs[_BOOKING_STATE_PENDING_ID] = '';
		$state_arrs[_BOOKING_STATE_APPROVED_ID] = '';
		$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',',array_keys($state_arrs)).")", "{$clsProperty->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if($val[$clsProperty->pkey] == _BOOKING_STATE_PENDING_ID){
					$state_arrs[_BOOKING_STATE_PENDING_ID] = $val['title'];
				} else if($val[$clsProperty->pkey] == _BOOKING_STATE_APPROVED_ID){
					$state_arrs[_BOOKING_STATE_APPROVED_ID] = $val['title'];
				}
			}
			unset($tmp);
		}
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' => sprintf('<strong>%s</strong> đã thay đổi trạng thái từ <strong>%s</strong> tới <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $state_arrs[_BOOKING_STATE_PENDING_ID], $state_arrs[_BOOKING_STATE_APPROVED_ID], $clsISO->convertTimeToText($action_date)));
		if($clsBooking->updateOne($booking_id, array(
			'state_id' => _BOOKING_STATE_APPROVED_ID,
			'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	} else {
		$msg = "_empty";
	}
	// Return
	echo $msg; die();
}
function default_cancel_activity(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG');
	$booking_id = (int) Input::post('booking_id', 0);
	$activity_id = (int) Input::post('activity_id', 0);
	#
	$titlePage = "ưu tiên";
	if($holderG == 'recall') $titlePage = 'thu hồi';
	if($holderG == 'refund') $titlePage = 'hoàn cọc';
	if($holderG == 'matched') $titlePage = 'khớp cọc';
	if($holderG == 'unit_match') $titlePage = 'khớp căn';
	if($holderG == 'payment_audit') $titlePage = 'tra soát TĐTT';
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('holderG', $holderG);
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('activity_id', $activity_id);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$html = $core->build('ajax_cancel.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_cancel_activity(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$msg = "_error"; $more = array();
	$holderG = Input::post('holderG');
	$booking_id = (int) Input::post('booking_id', 0);
	$activity_id = (int) Input::post('activity_id', 0);
	$reason = Input::post('reason');
	if($booking_id > 0 && $activity_id > 0){
		$oneBooking = $clsBooking->getOne($booking_id);
		$action_logs = $oneBooking['action_logs'];
		$action_logs = $clsISO->to_array_json($action_logs);
		#
		$oneBookingMeta = $clsBookingMeta->getOne($activity_id);
		$more_information = $oneBookingMeta['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		// $clsISO->print_pre($more_information); die();
		if($holderG == 'matched'){
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' =>  sprintf('<strong>%s</strong> hủy khớp cọc ngày: <strong>%s</strong>, mã căn: <strong>%s</strong>, số tiền: <strong>%s</strong>, người đứng số: <strong>%s</strong>, lý do: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsISO->convertTimeToText($oneBookingMeta['action_date']),
					$more_information['stock_code'], $clsISO->formatPrice($more_information['amount']),
					$clsProfile->getFullName($more_information['staff_sale_id']), $reason
				)
			);
		} else if($holderG == 'unit_match'){
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' =>  sprintf('<strong>%s</strong> hủy khớp căn ngày: <strong>%s</strong>, 
				mã căn: <strong>%s</strong>, người đứng tên: <strong>%s</strong>, lý do: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $clsISO->convertTimeToText($oneBookingMeta['action_date']),
					$more_information['stock_code'], $more_information['name_owner'], $reason
				)
			);
		} else if($holderG == 'payment_audit'){
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' =>  sprintf('<strong>%s</strong> hủy tra soát TĐTT ngày: <strong>%s</strong>, mã căn: <strong>%s</strong>, nội dung: <strong>%s</strong>, lý do: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsISO->convertTimeToText($oneBookingMeta['action_date']),
					$more_information['stock_code'], $more_information['content'], $reason
				)
			);
		} else if($holderG == 'refund'){
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' =>  sprintf('<strong>%s</strong> hủy yêu cầu hoàn cọc ngày: <strong>%s</strong>, mã căn: <strong>%s</strong>, lý do: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsISO->convertTimeToText($oneBookingMeta['action_date']),
					$more_information['stock_code'], $reason
				)
			);
		}
		// $clsISO->print_pre($action_logs); die();
		if($clsBookingMeta->deleteOne($activity_id)){
			$msg = "_success";
			$clsBooking->updateOne($booking_id, array(
				'status_id' => _BOOKING_WAIT_DEPOSIT_ID,
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
				'upd_date' => time(),
				'user_id_update' => $profile_id,
			));
		}
	}
	// Return
	echo $msg; die();
}
function default_open_confirm(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	$clsBooking = new Booking();
	
	$uid = $clsISO->getUniqid();
	$booking_id = (int) Input::post('booking_id', 0);
	$oneBooking = $clsBooking->getOne($booking_id);
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('oneBooking', $oneBooking);
	// Return
	$html = $core->build('_ajax.confirm.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_cancel_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	$clsBooking = new Booking();
	#
	$msg = "_error";
	$booking_id = (int) Input::post('booking_id', 0);
	$cancel_reason = Input::post('cancel_reason');
	if($booking_id > 0){
		$oneBooking = $clsBooking->getOne($booking_id);
		$action_logs = $oneBooking['action_logs'];
		$more_information = $oneBooking['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$action_logs = $clsISO->to_array_json($action_logs);
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' =>  sprintf('<strong>%s</strong> hủy booking ngày: <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsISO->convertTimeToText(time(), true)
			)
		);
		$more_information['cancel_info'] = array(
			'user_id' => $profile_id,
			'cancel_date' => time(),
			'cancel_reason' => $cancel_reason			
		);
		if($clsBooking->updateOne($booking_id, array(
			'status_id' => _BOOKING_STATUS_CANCEL_ID,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
		}
	}
	// Return 
	echo $msg; die();
}
function default_open_state(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	$clsBooking = new Booking();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$booking_id = (int) Input::post('booking_id', 0);
	$booking_type = (int) Input::post('booking_type', _BOOKING_TYPE_INTERNAL_ID);
	$oneBooking = $clsBooking->getOne($booking_id);
	#
	$state_arrs = $clsProperty->getCacheItems('_BOOKING_STATE');
	$smarty->assign('state_arrs', $state_arrs);
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('booking_id', $booking_id);
	$smarty->assign('booking_type', $booking_type);
	$smarty->assign('oneBooking', $oneBooking);
	// Return
	$html = $core->build("_ajax.state.tpl");
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_upd_state(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	$clsBooking = new Booking();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$booking_id = (int) Input::post('booking_id', 0);
	$booking_type = (int) Input::post('booking_type', _BOOKING_TYPE_INTERNAL_ID);
	#
	if($booking_id > 0 && $clsBooking->updateOne($booking_id, array(
		'state_id' => Input::post('state_id')
	))){
		$msg = "_success";
	}
	// Retutn
	echo $msg; die();
}
function default_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBooking = new Booking();
	$clsBookingMeta = new BookingMeta();
	$clsProfile = new Profile();
	#
	$permiss_admin = $clsISO->checkPermissionGroup('ADMIN_PROJECT') ? 1 : 0;
	$assign_list["permiss_admin"] = $permiss_admin;
	# booking_type
	$block_id = (int)Input::get("block_id",0);
	$project_id = (int)Input::get("project_id",0);
	$get_booking_type = Input::get('booking_type', 'internal');
	$booking_type = ($get_booking_type == 'investor') ? _BOOKING_TYPE_INVESTOR_ID : _BOOKING_TYPE_INTERNAL_ID;
	#
	$total_record = 0; $current_time = time();
	$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
	$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
	if($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR')){
		$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` 
		INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` 
		WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%')");
	} else {
		$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` 
		INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` 
		WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.start_booking\"))<='{$current_time}' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.end_booking\"))>='{$current_time}') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.start_booking\"))<='{$current_time}' AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.end_booking\"))>='{$current_time}')");
	}
	$assign_list["arr_projects"] = $arr_projects;
	#
	$oneItem = $clsProperty->getOne($block_id, "{$clsProperty->pkey},`for_id`,`title`,`more_information`");
	$for_id = $oneItem['for_id'];
	$more_information = $oneItem['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$p_field = "{$clsProperty->pkey},`title`,`more_information`";
	$list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$block_id}'", $p_field);
	$cond = "`t1`.`is_trash`=0 AND `t1`.`booking_type`='{$booking_type}' AND `t1`.`project_id`='{$for_id}' AND `t1`.`block_id`='{$block_id}'";
	#
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstDepartment = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_total_booking_dep = $list_total_booking_bed = $arr_all_bedrrom = $arr_profile_cached = array();
	if(!empty($list_buildings)) {
		$QueryBuilder = DB::table($clsProperty->tbl);
		$QueryBuilder->select("{$clsProperty->pkey},property_code,title");
		$QueryBuilder->where("is_trash", 0);
		$QueryBuilder->where("property_type", "_DEPARTMENT");
		$tmp = $QueryBuilder->get();
		if(!empty($tmp)){
			foreach($tmp as $okey => $oval){
				$arr_profile_cached[$oval[$clsProperty->pkey]] = $oval['property_code'];
			}
			unset($tmp);
		}
		foreach($list_buildings as $key => $val) {
			$building_id = $val[$clsProperty->pkey];
			$more_building = $val['more_information'];
			$more_building = $clsISO->to_array_json($more_building);
			$stock_templates = $core->get_field($more_building, "template", []);
			$arr_bedrrom = $arr_stocks = $arr_cols = [];
			if(!empty($stock_templates)){
				foreach($stock_templates as $k_temp => $v_temp){
					$code = $v_temp['code'];
					$arr_bedrrom[$code] = [
						"property_id"	=>	$v_temp["bedroom_id"],
						"title"	=>	$lstBedroom[$v_temp["bedroom_id"]]["title"],
						"order_no"	=>	$lstBedroom[$v_temp["bedroom_id"]]["order_no"]
					];
					$arr_all_bedrrom[$v_temp["bedroom_id"]] = [
						"property_id"	=>	$v_temp["bedroom_id"],
						"title"	=>	$lstBedroom[$v_temp["bedroom_id"]]["title"],
						"order_no"	=>	$lstBedroom[$v_temp["bedroom_id"]]["order_no"]
					];
					if(!empty($is_symbol)) {
						$code = $v_temp["symbol"].".".$code;		
					}				
					$arr_stocks[] = $code;
					$arr_cols[$code] = $v_temp;
					unset($code);
				}
			}
			$ranges = [];
			$floor_range_configs = $core->get_field($more_building, "floor_range_configs", []);
			if(!empty($floor_range_configs)){
				foreach($floor_range_configs as $k_config => $v_config) {
					$arr_floor = $clsBooking->getArrayFloor($v_config);
					if($v_config["floor_type"] == "consecutive") {
						$ranges[$k_config] = [
							"label" =>	$v_config["from"]."→". $v_config["to"],
							"arrfloor" => $arr_floor,
							"level" =>	$v_config["level"],
						];
					}else{
						$ranges[$k_config] = [
							"label"	=>	$v_config["floor"],
							"arrfloor"	=>	$arr_floor,
							"level"	=>	$v_config["level"],
						];
					}
				}
			}
			$list_buildings[$key]["arr_stocks"] = $arr_stocks;	
			$list_buildings[$key]["arr_cols"] = $arr_cols;	
			$list_buildings[$key]["more_information"] = $more_building;	
			$cond_booking = " AND `t1`.`state_id`<>'"._BOOKING_STATE_PENDING_ID."' 
				AND `t1`.`status_id`<>'"._BOOKING_STATUS_CANCEL_ID."' AND `t1`.`building_id`='{$building_id}'";
			#
			$arr_booking = [];
			$field = "`t1`.*,`t2`.`more_information` as `more_meta`,JSON_UNQUOTE(JSON_EXTRACT(`t2`.`more_information`,\"$.priority\")) AS `priority`";
			$list_bookings = $dbconn->getAll("SELECT {$field} FROM `{$clsBooking->tbl}` AS `t1` 
				INNER JOIN `{$clsBookingMeta->tbl}` AS `t2` ON `t1`.`booking_id` = `t2`.`booking_id` 
				WHERE ".$cond.$cond_booking." ORDER BY `priority` ASC");
			$last_upd = 0;
			foreach ($list_bookings as $k_bk => $v_bk) {
				$more_bk = $clsISO->to_array_json($v_bk["more_information"]);
				$more_meta = $clsISO->to_array_json($v_bk["more_meta"]);
				#
				$staff_id = $more_bk["staff_id"];
				$department_id = $more_bk["department_id"];
				$bedroom_id = $arr_bedrrom[$more_meta["unit_axis"]]["property_id"];
				if(!empty($list_total_booking_dep[$department_id]["bedroom"][$bedroom_id])) {
					$list_total_booking_dep[$department_id]["bedroom"][$bedroom_id] += 1;
				}else{
					$list_total_booking_dep[$department_id]["bedroom"][$bedroom_id] = 1;
				}
				if(!empty($list_total_booking_dep[$department_id]["total"])) {
					$list_total_booking_dep[$department_id]["total"] += 1;
				}else{
					$list_total_booking_dep[$department_id]["total"] = 1;
				}
				if(!empty($list_total_booking_bed[$bedroom_id])) {
					$list_total_booking_bed[$bedroom_id] += 1;
				}else{
					$list_total_booking_bed[$bedroom_id] = 1;
				}
				$v_bk['staff_id'] = $staff_id;
				$v_bk['department_id'] = $department_id;
				$v_bk['department_name'] = $arr_profile_cached[$department_id];
				$v_bk["priority"] = !empty($v_bk["priority"]) ? $v_bk["priority"] : 1;
				$unit_axis = $core->get_field($more_meta, "unit_axis",  "");
				$floor_range = $core->get_field($more_meta, "floor_range", "");
				if($floor_range != "" && $unit_axis != "") {
					$arr_booking[$floor_range][$unit_axis][] = $v_bk;
					if(isset($ranges[$floor_range])) {
						$ranges[$floor_range]["arr_booking"][$unit_axis][] = $v_bk;	
					}
				}
				$last_upd = ($last_upd < $v_bk["upd_date"]) ? $v_bk["upd_date"] : $last_upd;
			}
			if(!empty($ranges)) {				
				$total = 0;
				$arr_row_range = $arr_layout_range = [];
				foreach ($ranges as $k_range => $v_range) {
					if(!empty($arr_row_range[$v_range["level"]])) {
						$arr_row_range[$v_range["level"]] += 1;
					} else {
						$arr_row_range[$v_range["level"]] = 1;
					}
					$max_ut = 1;
					foreach ($v_range["arr_booking"] as $_code => $lst_bk) {
						$max_ut = ($max_ut < count($lst_bk)) ? count($lst_bk) : $max_ut;				
						$total_record += count($lst_bk);
					}
					$arr_row_range[$v_range["level"]] += $max_ut;
					$v_range["row_ut"] = $max_ut;
					$arr_layout_range[$v_range["level"]][] = $v_range;
				}
				$list_buildings[$key]["ranges"] = $ranges;	
				$list_buildings[$key]["layout_range"] = $arr_layout_range;	
				$list_buildings[$key]["arr_row_range"] = $arr_row_range;	
				$list_buildings[$key]["last_upd"] = $last_upd;	
			}else{
				unset($list_buildings[$key]);
			}
		}
	}
	#
	$bedroom_arr_sort = @array_column($arr_all_bedrrom, 'order_no');
	@array_multisort($bedroom_arr_sort, SORT_ASC, $arr_all_bedrrom);
	$assign_list["arr_bedrrom"] = $arr_all_bedrrom;
	foreach ($list_total_booking_dep as $key => $val) {
		$list_total_booking_dep[$key]["title"] = $lstDepartment[$key]["title"];
		$list_total_booking_dep[$key]["order_no"] = $lstDepartment[$key]["order_no"];
	}
	$dep_arr_sort = @array_column($list_total_booking_dep, 'total');
	@array_multisort($dep_arr_sort, SORT_DESC, $list_total_booking_dep);	
	$assign_list["list_total_booking_dep"] = $list_total_booking_dep;
	$assign_list["list_total_booking_bed"] = $list_total_booking_bed;
	$assign_list["total_booking_bed"] = array_sum(array_values($list_total_booking_bed));
	$arr_color = ["#37d601","#ffea97","#EFF6FF","#e96d15","#c25000","#c21000","#007ac2","#a259ff","#ff59ee","#ff5992"];
	#
	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["clsBooking"] = $clsBooking;
	$assign_list["oneItem"] = $oneItem;
	$assign_list["list_buildings"] = $list_buildings;
	$assign_list["more_information"] = $more_information;
	$assign_list["floor_range"] = $floor_range;
	$assign_list["total_record"] = $total_record;
	$assign_list["arr_color"] = $arr_color;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách Booking '.$oneItem["title"].' - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Danh sách Booking '.$oneItem["title"].' - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
	$keyword_page = 'Quản lý booking, '.PAGE_NAME;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_stock_hug(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	###
	$list_briefs = array(
		'_TOTAL' => array(
			'title' => 'Tổng quỹ', 
			'subtitle' => 'Tổng số cọc đã cọc vào CĐT'
		),
		'_SOLD' => array(
			'title' => 'Đã bán', 
			'subtitle' => 'Tổng số cọc đã bán được'
		),
		'_HUG' => array(
			'title' => 'Căn ôm', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_HUG_10' => array(
			'title' => 'Căn ôm 10%', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_REFUND' => array(
			'title' => 'Thu hồi', 
			'subtitle' => 'Tổng số cọc huỷ căn chờ hoàn'
		)
	);
	$arr_projects = array();
	$tmp = $clsSetting->getCacheItems('_PROJECT');
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$project_admins = $core->get_field($more_information, "project_admins", []);
			if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
				if(!empty($project_admins) && in_array($profile_id, $project_admins)){
					$arr_projects[] = $val;
				}
			} else {
				$arr_projects[] = $val;
			}
		}
		unset($tmp);
	}
	$assign_list["list_briefs"] = $list_briefs;
	$assign_list["arr_projects"] = $arr_projects;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Quỹ ôm - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_import(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsSetting = new Setting();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	
	$arr_projects = array();
	$tmp = $clsSetting->getCacheItems('_PROJECT');
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$project_admins = $core->get_field($more_information, "project_admins", []);
			$stock_hug_configs = $core->get_field($more_information, "stock_hug_configs", []);
			$spreadsheetId = $core->get_field($stock_hug_configs, "spreadsheetId", "");
			if(!empty($spreadsheetId)){
				if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
					if(!empty($project_admins) && in_array($profile_id, $project_admins)){
						$arr_projects[] = $val;
					}
				} else {
					$arr_projects[] = $val;
				}
			}
		}
		unset($tmp);
	}
	$smarty->assign('arr_projects', $arr_projects);
	// Return
	$html = $core->build("_ajax.open_import.tpl");
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_import(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	
	$msg = "_error";
	$totalInserted = $totalUpdated = 0;
	$project_id = (int) Input::post('project_id', 0);
	if($project_id > 0){
		$oneSetting = $clsSetting->getOne($project_id, "more_information");
		$more_information = $oneSetting['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$stock_hug_configs = $core->get_field($more_information, "stock_hug_configs", []);
		$spreadsheetId = $core->get_field($stock_hug_configs, "spreadsheetId", "");
		$sheet_name = $core->get_field($stock_hug_configs, "sheet_name", "");
		$columns = $core->get_field($stock_hug_configs, "columns", []);
		if(!empty($spreadsheetId) && !empty($sheet_name) && !empty($columns)){
			$project_id = (int) $core->get_field($more_information, "project_id", 0);
			$block_id = (int) $core->get_field($more_information, "block_id", 0);
			// $clsISO->print_pre($more_information); die();
			if($clsISO->checkContainer($spreadsheetId, "docs.google.com","")){
				@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
				$spreadsheetId = $matches[0];
			}
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
			// get all the rows of a sheet
			$range = $sheet_name; // here we use the name of the Sheet to get all the rows
			$response = $service->spreadsheets_values->get($spreadsheetId, $range);
			$tblData = $response->getValues();
			if(!empty($tblData)){
				$msg = "_success";
				$arr_stocks = array();
				$total_record = count($tblData);
				for($i=1; $i<$total_record; $i++){
					$arr_row = array();
					foreach($columns as $p_col => $p_field){
						$arr_row[$p_field] = trim($tblData[$i][$p_col]);
					}
					$arr_stocks[] = $arr_row;
				}
				if(!empty($arr_stocks)){
					foreach($arr_stocks as $val){
						$stock_code = $core->get_field($val, "stock_code", "");
						$bedroom_name = $core->get_field($val, "bedroom_name", "");
						$otp_customer = $core->get_field($val, "otp_customer", "");
						$otp_date = $core->get_field($val, "otp_date", "");
						$otp_reg_link = $core->get_field($val, "otp_reg_link", "");
						$status_name = $core->get_field($val, "status_name", "");
						$state_name = $core->get_field($val, "state_name", "");
						$deposit_date = $core->get_field($val, "deposit_date", "");
						$sale_price = $core->get_field($val, "sale_price", 0);
						$deposit_amount = $core->get_field($val, "deposit_amount", 0);
						$developer_deposit_amount = $core->get_field($val, "developer_deposit_amount", 0);
						$fund_type = $core->get_field($val, "fund_type", "");
						$stock_resource = $core->get_field($val, "stock_resource", "");
						$refund_date = $core->get_field($val, "refund_date", "");
						$agree_date = $core->get_field($val, "agree_date", "");
						$contract_date = $core->get_field($val, "contract_date", "");
						$commision_rate = $core->get_field($val, "commision_rate", 0);
						$sale_bonus_amount = $core->get_field($val, "sale_bonus_amount", 0);
						$first_payment_amount = $core->get_field($val, "first_payment_amount", 0);
						$notes = $core->get_field($val, "notes", "");
						$otp_date = !empty($otp_date) ? $clsISO->convertTextToTime($otp_date) : 0;
						$deposit_date = !empty($deposit_date) ? $clsISO->convertTextToTime($deposit_date) : 0;
						$refund_date = !empty($refund_date) ? $clsISO->convertTextToTime($refund_date) : 0;
						$agree_date = !empty($agree_date) ? $clsISO->convertTextToTime($agree_date) : 0;
						$contract_date = !empty($contract_date) ? $clsISO->convertTextToTime($contract_date) : 0;
						$sale_price = !empty($sale_price) ? $clsISO->processSmartNumber($sale_price) : 0;
						$deposit_amount = !empty($deposit_amount) ? $clsISO->processSmartNumber($deposit_amount) : 0;
						$developer_deposit_amount = !empty($deposit_amount) ? $clsISO->processSmartNumber($developer_deposit_amount) : 0;
						$sale_bonus_amount = !empty($sale_bonus_amount) ? $clsISO->processSmartNumber($sale_bonus_amount) : 0;
						$first_payment_amount = !empty($first_payment_amount) ? $clsISO->processSmartNumber($first_payment_amount) : 0;
						if(!empty($stock_code) && !empty($status_name)){
							$sql_cond = $sql_query = "`is_trash`=0 AND `project_id`='{$project_id}'";
							if($block_id > 0){
								$sql_query.= " AND `block_id`='{$block_id}'";
								$sql_cond.= " AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.block_id\"))='{$block_id}'";
							}
							$field = "{$clsStock->pkey},`block_id`,`building_id`,`bedroom_id`";
							$oneStock = $clsStock->getByCond($sql_query." AND `ms_code`='{$stock_code}'", $field);
							$stock_id = $oneStock[$clsStock->pkey];
							$block_id = $oneStock['block_id'];
							$building_id = $oneStock['building_id'];
							$bedroom_id = $oneStock['bedroom_id'];
							$tmp = $clsProperty->getByCond("`property_type`='_STATUS_STOCK_HUG' 
								AND `slug`='".$core->replaceSpace($status_name)."'", $clsProperty->pkey);
							$status_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : _STOCK_HUG_STATUS_DEF_ID;
							#
							$billing_id = 0;
							if($status_id == _STOCK_HUG_STATUS_SOLD_ID) {
								$tmp = $clsBilling->getByCond("{$sql_cond} AND `stock_code`='{$stock_code}'", "{$clsBilling->pkey},`more_information`");
								if(!empty($tmp)){
									$more = array();
									$billing_id = $tmp[$clsBilling->pkey];
									$billing_information = $tmp['more_information'];
									$billing_information = $clsISO->to_array_json($billing_information);
									if($otp_date > 0) {
										$more['opt_date'] = $otp_date;
										$billing_information['otp_date'] = $otp_date;
									}
									if(!empty($otp_customer)) {
										$billing_information['otp_customer'] = $otp_customer;
									}
									if(!empty($otp_reg_link)) {
										$billing_information['otp_reg_link'] = $otp_reg_link;
									}
									$clsBilling->updateOne($tmp[$clsBilling->pkey], array_merge($more, array(
										'more_information' => json_encode($billing_information, JSON_UNESCAPED_UNICODE)
									)));
								}
								unset($tmp);
							}
							$field = "{$clsStockHug->pkey},`more_information`";
							$tmp = $clsStockHug->getByCond("`is_trash`=0 AND `stock_id`='{$stock_id}' 
								AND `project_id`='{$project_id}' AND `block_id`='{$block_id}'", $field);
							if(!empty($tmp)){
								$more_information = $tmp['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['otp_customer'] = $otp_customer;
								$more_information['otp_date'] = $otp_date;
								$more_information['otp_reg_link'] = $otp_reg_link;
								$more_information['totalgrand'] = $sale_price;
								$more_information['refund_date'] = $refund_date;
								$more_information['agree_date'] = $agree_date;
								$more_information['contract_date'] = $contract_date;
								$more_information['commision_rate'] = $commision_rate;
								$more_information['sale_bonus_amount'] = $sale_bonus_amount;
								$more_information['fund_type'] = $fund_type;
								$more_information['stock_resource'] = $stock_resource;
								$more_information['notes'] = $notes;
								if($clsStockHug->updateOne($tmp[$clsStockHug->pkey], array(
									'stock_id' => $stock_id,
									'billing_id' => $billing_id,
									'stock_code' => $stock_code,
									'bedroom_id' => $bedroom_id,
									'building_id' => $building_id,
									'deposit_date' => $deposit_date,
									'deposit_amount' => $deposit_amount,
									'developer_deposit_amount' => $developer_deposit_amount,
									'first_payment_amount' => $first_payment_amount,
									'status_id' => $status_id,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
									'user_id_update' => $profile_id,
									'upd_date' => time()
								))){
									$totalUpdated += 1;
								}
							} else {
								$more_information = array();
								$more_information['otp_customer'] = $otp_customer;
								$more_information['otp_date'] = $otp_date;
								$more_information['otp_reg_link'] = $otp_reg_link;
								$more_information['totalgrand'] = $sale_price;
								$more_information['refund_date'] = $refund_date;
								$more_information['agree_date'] = $agree_date;
								$more_information['contract_date'] = $contract_date;
								$more_information['commision_rate'] = $commision_rate;
								$more_information['sale_bonus_amount'] = $sale_bonus_amount;
								$more_information['fund_type'] = $fund_type;
								$more_information['stock_resource'] = $stock_resource;
								$more_information['notes'] = $notes;
								// $dbconn->debug = true;
								if($clsStockHug->insert(array(
									$clsStockHug->pkey => $clsStockHug->getMaxId(),
									'stock_id' => $stock_id,
									'billing_id' => $billing_id,
									'stock_code' => $stock_code,
									'bedroom_id' => $bedroom_id,
									'project_id' => $project_id,
									'block_id' => $block_id,
									'building_id' => $building_id,
									'deposit_date' => $deposit_date,
									'deposit_amount' => $deposit_amount,
									'developer_deposit_amount' => $developer_deposit_amount,
									'first_payment_amount' => $first_payment_amount,
									'status_id' => $status_id,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
									'user_id' => $profile_id,
									'reg_date' => time(),
									'user_id_update' => $profile_id,
									'upd_date' => time()
								))){
									$totalInserted += 1;
								}
							}
						}
					}
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'totalInserted' => $totalInserted,
		'totalUpdated' => $totalUpdated
	)); die();
}
function default_open_stock_hug(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$action = '_add';
	$uid = $clsISO->getUniqid();
	$stock_hug_id = (int) Input::post('stock_hug_id', 0);
	$oneStockHug = $more_information = array(
		'bedroom_id' => 0,
		//'is_contributed'	=> 0,
		'status_id' => _STOCK_HUG_STATUS_DEF_ID
	);
	$more_information = $list_blocks = $list_buildings = array();
	if($stock_hug_id > 0){
		$action = '_edit';
		$oneStockHug = $clsStockHug->getOne($stock_hug_id);
		$block_id = (int) $core->get_field($oneStockHug, 'block_id', 0);
		$project_id = (int) $core->get_field($oneStockHug, 'project_id', 0);
		$more_information = $oneStockHug['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		
		$prop_field = "{$clsProperty->pkey},`title`";
		if($project_id > 0){
			$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
			and `for_id`='{$project_id}' order by `order_no` ASC", $prop_field);
		}
		if($block_id > 0){
			$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
			and `for_id`='{$block_id}' order by `order_no` ASC", $prop_field);
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('stock_hug_id', $stock_hug_id);
	$smarty->assign('oneStockHug', $oneStockHug);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('list_buildings', $list_buildings);
	// Return
	$html = $core->build("_ajax.stock_hug.tpl");
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_stock_hug(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsStockHug = new StockHug();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	
	$project_id = (int) Input::post('project_id', 0);
	$keysearch = Input::post('keysearch');
	$keysearch = trim($keysearch); // Replace space
	$status_id = Input::post('status_id', []);
	$date_field = Input::post('date_field');
	$start_date = Input::post('start_date');
	$to_date = Input::post('to_date');
	
	$block_information = $clsProperty->getOneField('more_information', $block_id);
	$block_information = $clsISO->to_array_json($block_information);
	$billing_type = (int) $core->get_field($block_information, 'billing_type', 0);
	
	$sql_query = "`t1`.`is_trash`=0";
	if($project_id > 0){
		$oneSetting = $clsSetting->getOne($project_id, "more_information");
		$more_information = $oneSetting['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$project_id = (int) $core->get_field($more_information, "project_id", 0);
		$block_id = (int) $core->get_field($more_information, "block_id", 0);
		$sql_query.= " AND `t1`.`project_id`='{$project_id}'";
		if($block_id > 0) $sql_query.= " AND `t1`.`block_id`='{$block_id}'";
	}
	if($billing_type > 0){
		$sql_query.= " AND `t2`.`billing_type`='{$billing_type}'";
	}
	if(!empty($status_id)){
		$sql_query.= " AND `t1`.`status_id` IN ('".implode('\',\'', $status_id)."')";
	}
	$p_field = "`t1`.`{$clsStockHug->pkey}`,`t1`.`stock_code`,`t1`.`more_information`,`t1`.`status_id`,`t1`.`deposit_amount`";
	$p_field.= ",`t1`.`stock_id`,`t1`.`deposit_date`,`t1`.`developer_deposit_amount`";
	$p_field.= ",`t1`.`billing_id`,`t2`.`deposit_date`,`t2`.`staff_id`,`t2`.`is_cancel`,`t2`.`state_id`";
	// $dbconn->debug = true;
	$list_stocks = $dbconn->getAll("SELECT {$p_field} FROM {$clsStockHug->tbl} AS `t1` 
		LEFT JOIN {$clsBilling->tbl} AS `t2` ON `t1`.billing_id=`t2`.`billing_id` 
		WHERE {$sql_query} ORDER BY `t1`.`reg_date` DESC");
		
	$html = ''; $total_record = $total_solds = $total_refunds = $total_hugs = $total_first_payment_hugs = 0;
	$total_amount = $total_sales_amount = $total_refunds_amount = $total_hugs_amount = $total_first_payment_amount = 0;
	
	if(!empty($list_stocks)){ $ii = 0; // Init
		$total_record = count($list_stocks);
		$arr_property_cached = array();
		$arr_profile_cached = $clsProfile->getProfileCached();
		$tmp = $clsProperty->getCacheItems('BILLING_STATE');
		if($tmp){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = sprintf('<span class="%s w-100 rounded-pill">%s<span>', $val['image'], $val['title']);
			}
			unset($tmp);
		}
		$tmp = $clsProperty->getCacheItems('BILLING_SOURCE');
		if($tmp){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = sprintf('%s', $val['title']);
			}
			unset($tmp);
		}
		$tmp = $clsProperty->getCacheItems('_STATUS_STOCK_HUG');
		if($tmp){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = sprintf('<span class="%s w-100 rounded-pill">%s<span>', $val['image'], $val['title']);
			}
			unset($tmp);
		}
		foreach($list_stocks as $key => $val){
			$stock_id = $val['stock_id'];
			$stock_code = $val['stock_code'];
			$billing_id = (int) $val['billing_id']; 
			$staff_id = (int) $val['staff_id'];
			$state_id = (int) $val['state_id'];
			$status_id = (int) $val['status_id'];
			$more_information = $val['more_information'];
			$deposit_amount = $clsISO->processSmartNumber($val['deposit_amount']);
			$first_payment_amount = $clsISO->processSmartNumber($val['first_payment_amount']);
			$developer_deposit_amount = $clsISO->processSmartNumber($val['developer_deposit_amount']);
			$more_information = $clsISO->to_array_json($more_information);
			$otp_date = $core->get_number_field($more_information, "otp_date", 0);
			$deposit_date = $core->get_number_field($more_information, "deposit_date", 0);
			$refund_date = (int) $core->get_number_field($more_information, "refund_date", 0);
			$agree_date = (int) $core->get_number_field($more_information, "agree_date", 0);
			$contract_date = (int) $core->get_number_field($more_information, "contract_date", 0);
			$commission_rate = $core->get_number_field($more_information, "commission_rate", 0);
			$sale_bonus = $core->get_number_field($more_information, "sale_bonus_amount", 0);
			$totalgrand = $core->get_number_field($more_information, "totalgrand", 0);
			$fund_type = $core->get_number_field($more_information, "fund_type", "");
			$stock_resource = $core->get_number_field($more_information, "stock_resource", "");
			// $billing_source_id = $core->get_number_field($more_information, "billing_source_id", 0);
			$state_name = $staff_name = $department_name = "";
			if($state_id > 0 && isset($arr_property_cached[$state_id])){
				$state_name = $arr_property_cached[$state_id];
			}
			if((int) $core->get_field($val, "is_cancel", 0) == 1){
				$state_name = '<span class="badge bg-gray w-100 rounded-pill">Đã hủy</span>';
			}
			if($staff_id > 0 && isset($arr_profile_cached[$staff_id])){
				$oneStaff = $arr_profile_cached[$staff_id];
				$staff_information = $oneStaff['more_information'];
				$staff_name = sprintf('%s-%s', $oneStaff['code'], $oneStaff['full_name']);
				$department_name = $staff_information['department_name'];
			}
			$status_stock = $fund_name = $resource_name = "";
			if($status_id == _STOCK_HUG_STATUS_SOLD_ID){
				$total_solds += 1;
				$total_sales_amount += $developer_deposit_amount;
			} else if($status_id == _STOCK_HUG_STATUS_REFUND_ID){
				$total_refunds += 1;
				$total_refunds_amount += $developer_deposit_amount;
			} else if($status_id == _STOCK_HUG_STATUS_DEF_ID){
				$total_hugs += 1;
				$total_hugs_amount += $developer_deposit_amount;
				if(isset($val['is_first_payment_paid']) && $val['is_first_payment_paid'] == 1){
					$total_first_payment_hugs += 1;
					$total_first_payment_amount += $first_payment_amount;
				}
			}
			$total_amount += $developer_deposit_amount;
			$status_stock = $arr_property_cached[$status_id];
			if($fund_type = "ĐQ"){
				$fund_name = '<span class="badge bg-danger w-100 rounded-pill">ĐQ<span>';
			} else {
				$fund_name = '<span class="badge bg-warning w-100 rounded-pill">CHÉO<span>';
			}
			$html.= '<tr>
				<td class="text-center">'.($ii+1).'</td>
				<td><a href="javascript:void(0);" data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id='.$stock_id.'" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350">'.$val['stock_code'].'</a></td>
				<td>'.$more_information['otp_customer'].'</td>
				<td class="text-center">'.($otp_date > 0 ? $clsISO->convertTimeToText($otp_date) : "-").'</td>
				<td class="text-center">'.(isset($more_information['otp_reg_link']) && !empty($more_information['otp_reg_link'])?'<a class="text-link" target="_blank" href="'.$more_information['otp_reg_link'].'">'.$clsISO->truncate($more_information['otp_reg_link'], 20).'<i class=\'bx bx-link-external text-fs-12\'></i></a>':'-').'</td>
				<td class="text-center text-upper">'.$clsISO->formatPrice($val['developer_deposit_amount']).'</td>
				<td class="text-center text-upper">'.$status_stock.'</td>
				<td class="text-left"><span class="badge bg-label-purple">'.$department_name.'</span> '.$staff_name.'</td>
				<td class="align-center text-center text-upper">'.$state_name.'</td>
				<td class="align-center text-right">'.$clsISO->formatPrice($v).'</td>
				<td class="align-center text-center">'.($deposit_date > 0 ? $clsISO->convertTimeToText($deposit_date) : "-").'</td>
				<td class="align-center text-right">'.$clsISO->formatPrice($val['deposit_amount']).'</td>
				<td class="align-center text-center text-upper">'.$fund_name.'</td>
				<td class="align-center text-center">'.$stock_resource.'</td>
				<td class="align-center text-center">'.($refund_date > 0 ? $clsISO->convertTimeToText($refund_date) : "-").'</td>
				<td class="align-center text-center">'.($agree_date > 0 ? $clsISO->convertTimeToText($agree_date) : "-").'</td>
				<td class="align-center text-center">'.($contract_date > 0 ? $clsISO->convertTimeToText($contract_date) : "-").'</td>
				<td class="align-center text-center">'.(!empty($commission_rate) ? $commission_rate.'%' : '-').'</td>
				<td class="align-center text-right">'.$sale_bonus_amount.$clsISO->getRate().'</td>
				<td class="align-center">'.$core->get_field($more_information, "notes", "-").'</td>
				<td class="align-center text-center">
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-button hide-arrow">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu w-px-100">
							'.($billing_id > 0 ? '
							<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.global.billing.view_billing(this,event)" billing_id="'.$val['id'].'"><i class="fa fa-eye me-1"></i> Xem</a>
							': '<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.stock_hug.open(this,event)" stock_hug_id="'.$val['id'].'"><i class="bx bx-pencil me-1"></i> Sửa</a>
							<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.stock_hug.delete(this,event)" stock_hug_id="'.$val['id'].'"><i class="bx bx-trash me-1"></i> Xóa</a>').'
						</div>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		// $clsISO->print_pre($arr_prop_cached); die();
	}
	$html_brief = "";
	$list_briefs = array(
		'_TOTAL' => array(
			'title' => 'Tổng quỹ', 
			'subtitle' => 'Tổng số cọc đã cọc vào CĐT'
		),
		'_SOLD' => array(
			'title' => 'Đã bán', 
			'subtitle' => 'Tổng số cọc đã bán được'
		),
		'_HUG' => array(
			'title' => 'Căn ôm', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_HUG_10' => array(
			'title' => 'Căn ôm 10%', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_REFUND' => array(
			'title' => 'Thu hồi', 
			'subtitle' => 'Tổng số cọc huỷ căn chờ hoàn'
		)
	);
	foreach($list_briefs as $key => $val){
		$onClick = $props = "";
		if($key == '_TOTAL'){
			$total = $total_record;
			$total_price = $total_amount;
		} else if($key == '_SOLD'){
			$total= $total_solds;
			$total_price = $total_sales_amount;
		} else if($key == '_HUG'){
			$total= $total_hugs;
			$total_price = $total_hugs_amount;
		} else if($key == '_HUG_10'){
			$total= $total_first_payment_amount;
			$total_price = $total_first_payment_amount;
		} else if($key == '_REFUND'){
			$total= $total_refunds;
			$total_price = $total_refunds_amount;
		}
		$html_brief.= '<div'.(!empty($onClick) ? ' onClick="'.$onClick.'"': '').' '.$props.' 
			class="border cursor-pointer flex-fill p-3 rounded-2">
			<div class="d-flex mb-1 align-items-center justify-content-between">
				<h5 class="mb-0">'.$val['title'].'</h5>
				<a class="panel-help help_pop openHelp" title="'.$val['subtitle'].'">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<ul class="list-unstyled mb-0">
				<li class="d-flex align-items-center justify-content-between">
					<span class="text-muted">Số lượng:</span>
					<strong class="fs-5 text-main">'.$total.'</strong>
				</li>
				<li class="d-flex align-items-center justify-content-between">
					<span class="text-muted">Tổng tiền:</span>
					<strong class="fs-5 text-main">'.$clsISO->shortNumber($total_price).'</strong>
				</li>
			</ul>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'html_brief' => $html_brief
	)); die();
}
function default_get_bedroom_type(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	###
	$bedroom_id = 0;
	$stock_code = Input::post('stock_code');
	if(!empty($stock_code)){
		$stock_code = preg_replace('/\s+/','', $stock_code);
		$oneStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", "bedroom_id");
		// $clsISO->print_pre($oneStock); die();
		$bedroom_id = !empty($oneStock) ? $oneStock["bedroom_id"] : 0;
	}
	// Return 
	echo json_encode(array(
		'bedroom_id' => $bedroom_id
	)); die();	
}
function default_delete_stock_hug(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	
	$msg = "_error";
	$stock_hug_id = (int) Input::post('stock_hug_id', 0);
	if($stock_hug_id > 0 && $clsStockHug->updateOne($stock_hug_id, array(
		'is_trash' => 1
	))) {
		$msg = "_success";
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("StockHug","delete");
	}
	// Return
	echo $msg; die();
}
function default_save_stock_hug(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$stock_hug_id = (int) Input::post('stock_hug_id', 0);
	$stock_code = Input::post('stock_code');
	$bedroom_id = (int) Input::post('bedroom_id', 0);
	$status_id = (int) Input::post('status_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$otp_date = Input::post('otp_date');
	$otp_customer = Input::post('otp_customer');
	$otp_reg_link = Input::post('otp_reg_link');
	$refund_date = Input::post('refund_date');
	$deposit_date = Input::post('deposit_date');
	$deposit_date = !empty($deposit_date) ? $clsISO->toTime($deposit_date) : 0;
	$refund_date = !empty($refund_date) ? $clsISO->toTime($refund_date) : 0;
	$otp_date = !empty($otp_date) ? $clsISO->toTime($otp_date) : 0;
	
	$totalgrand = Input::post('totalgrand');
	$deposit_price = Input::post('deposit_price');
	$deposit_amount = Input::post('deposit_amount');
	$totalgrand = !empty($totalgrand) ? $clsISO->processSmartNumber($totalgrand) : 0;
	$deposit_price = !empty($deposit_price) ? $clsISO->processSmartNumber($deposit_price) : 0;
	$deposit_amount = !empty($deposit_amount) ? $clsISO->processSmartNumber($deposit_amount) : 0;
	if($stock_hug_id == 0){
		$more_information = array();
		$more_information['otp_customer'] = $otp_customer;
		$more_information['otp_date'] = $otp_date;
		$more_information['otp_reg_link'] = $otp_reg_link;
		$more_information['notes'] = Input::post('notes');
		$stock_code = preg_replace('/\s+/', '', $stock_code);
		$oneStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `block_id`='{$block_id}' 
			AND `building_id`='{$building_id}' AND `ms_code`='{$stock_code}'", $clsStock->pkey);
		$stock_id = !empty($oneStock) ? $oneStock[$clsStock->pkey] : 0;
		// $clsStockHug->setDebug();
		$stock_hug_id = $clsStockHug->getMaxId();
		if($clsStockHug->insert(array(
			$clsStockHug->pkey => $stock_hug_id,
			'stock_id' => $stock_id,
			'stock_code' => $stock_code,
			'bedroom_id' => $bedroom_id,
			'otp_customer' => $otp_customer,
			'otp_date' => $otp_date,
			'otp_reg_link' => $otp_reg_link,
			'project_id' => $project_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'refund_date' => $refund_date,
			'deposit_price' => $deposit_price,
			'deposit_date' => $deposit_date,
			'totalgrand' => $totalgrand,
			'deposit_amount' => $deposit_amount,
			'status_id' => $status_id,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		$more_information= $clsStockHug->getOneField('more_information', $stock_hug_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['otp_customer'] = $otp_customer;
		$more_information['otp_date'] = $otp_date;
		$more_information['otp_reg_link'] = $otp_reg_link;
		$more_information['notes'] = Input::post('notes');
		$oneStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `block_id`='{$block_id}' 
			AND `building_id`='{$building_id}' AND `ms_code`='{$stock_code}'", $clsStock->pkey);
		$stock_id = !empty($oneStock) ? $oneStock[$clsStock->pkey] : 0;
		if($clsStockHug->updateOne($stock_hug_id, array(
			'stock_id' => $stock_id,
			'stock_code' => $stock_code,
			'bedroom_id' => $bedroom_id,
			'otp_customer' => $otp_customer,
			'otp_date' => $otp_date,
			'otp_reg_link' => $otp_reg_link,
			'project_id' => $project_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'refund_date' => $refund_date,
			'deposit_date' => $deposit_date,
			'totalgrand' => $totalgrand,
			'deposit_price' => $deposit_price,
			'deposit_amount' => $deposit_amount,
			//'deposit_fh' => $deposit_fh,
			//'deposit_ns' => $deposit_ns,
			//'is_contributed' => Input::post('is_contributed'),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'status_id' => $status_id,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";		
			# activity log			
			# $clsActivityLog = new ActivityLog();
			# $log = $clsActivityLog->addActivityLog("StockHug","update");
		}
	}
	// Return
	echo $msg; die();
}
function default_load_project_dir(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$project_id = $block_id = 0; $arr_blocks = array();
	$role_type = Input::post('role_type', _ROLE_STAFF_SALE);
	if($role_type == _ROLE_GD_PROJECT){
		$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
		$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
		$tmp = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\"))=1 AND (JSON_UNQUOTE(JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.is_booking\"))=1 AND JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.project_admins_slash\")) LIKE '%|{$profile_id}|%')");
		#
		$more_information = $oneProfile['more_information'];
		$project_dir_current = $core->get_field($more_information, "project_dir_current", []);
		$project_id = (int) $core->get_field($project_dir_current, "project_id", 0);
		$block_id = (int) $core->get_field($project_dir_current, "block_id", 0);
		#
		if($project_id > 0){
			// $list_blocks = $clsProject-
		}
	} else {
		$field = "{$clsProject->pkey},`title` AS `project_name`";
		$tmp = $clsProject->getAll("`is_trash`=0", $field);
	}
	#
	$arr_projects = array();
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_projects[] = array(
				'id' => $val['project_id'],
				'text' => $val['project_name']
			);
		}
	}
	// Return
	echo json_encode(array(
		'project_id' => $project_id,
		'block_id' => $block_id,
		'arr_projects' => $arr_projects,
		'arr_blocks' => $arr_blocks
	)); die();
}
?>