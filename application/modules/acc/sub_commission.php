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
function commission_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$deviceType;
	$clsConfiguration = new Configuration();
	#
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	$columnNum = ($deviceType=='phone') ? 2 : 6;
	$assign_list["columnNum"] = $columnNum;
	/*=============Title & Description Page==================*/
	$title_page = 'VAT đã xuất - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function commission_sync(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$deviceType;
	$clsBilling = new Billing();
	$clsCommission = new Commission();
	##
	$billing_id = (int) Input::post('billing_id', 0);
	$oneBilling = $clsBilling->getOne($billing_id);
	// $clsISO->print_pre($oneBilling); die();
	$clsBilling->sync_commission($billing_id, $oneBilling, "add");
	##
	echo 1; die();
}
function commission_open_setting(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsConfiguration = new Configuration();
	$clsCommission = new Commission();
	#
	$uid = $clsISO->getUniqid();
	$current_year = (int) date('Y');
	$CommissionSheets = $clsISO->to_array_json($clsConfiguration->getValue("CommissionSheets"));
	// Gom theo NĂM (mới → cũ). Mỗi năm 1 kiểu: Cả năm HOẶC 4 quý.
	$list_years = $clsCommission->normalizeConfig($CommissionSheets);
	// Luôn có thẻ năm hiện tại để cấu hình
	if(!isset($list_years[$current_year])){
		$_blank = $clsCommission->_blankCrawlYear();
		$_blank['mode'] = 'year';
		$list_years[$current_year] = $_blank;
		krsort($list_years);
	}
	$smarty->assign('current_year', $current_year);
	$smarty->assign('list_years', $list_years);
	// Return
	$html = $core->build('commission'.DS.'_ajax.open_setting.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function commission_update_setting(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsConfiguration = new Configuration();
	$clsCommission = new Commission();
	##
	$msg = "_error";
	// Gộp form (gom theo năm) lên trên config hiện có → giữ năm không hiển thị trên form
	$years = $clsCommission->normalizeConfig($clsISO->to_array_json($clsConfiguration->getValue("CommissionSheets")));
	$commission_years = Input::post('commission_years');
	if(!empty($commission_years) && is_array($commission_years)){
		foreach($commission_years as $y => $data){
			$y = (int) $y;
			if($y < 2020 || $y > 2100 || !is_array($data)){
				continue;
			}
			$mode = (isset($data['mode']) && $data['mode'] == 'quarter') ? 'quarter' : 'year';
			$_year_slot = $clsCommission->_blankCrawlSlot();
			if(isset($data['year']) && is_array($data['year'])){
				$_year_slot['is_active'] = (int) $core->get_field($data['year'], 'is_active', 0);
				$_year_slot['spreadsheetId'] = trim($core->get_field($data['year'], 'spreadsheetId', ''));
				$_year_slot['sheet_name'] = trim($core->get_field($data['year'], 'sheet_name', ''));
			}
			$_q_slots = array();
			for($i = 1; $i <= 4; $i++){
				$_slot = $clsCommission->_blankCrawlSlot();
				if(isset($data['q'][$i]) && is_array($data['q'][$i])){
					$_slot['is_active'] = (int) $core->get_field($data['q'][$i], 'is_active', 0);
					$_slot['spreadsheetId'] = trim($core->get_field($data['q'][$i], 'spreadsheetId', ''));
					$_slot['sheet_name'] = trim($core->get_field($data['q'][$i], 'sheet_name', ''));
				}
				$_q_slots[$i] = $_slot;
			}
			$years[$y] = array('mode' => $mode, 'year' => $_year_slot, 'q' => $_q_slots);
		}
	}
	// Thêm năm mới (chỉ nhập năm; kiểu chọn trong thẻ sau đó)
	$new_period_year = (int) Input::post('new_period_year', 0);
	$new_period_type = Input::post('new_period_type');
	if($new_period_year >= 2020 && $new_period_year <= 2100 && !isset($years[$new_period_year])){
		$_blank = $clsCommission->_blankCrawlYear();
		$_blank['mode'] = ($new_period_type == 'Q') ? 'quarter' : 'year';
		$years[$new_period_year] = $_blank;
	}
	// Lưu schema gom theo năm (khoá = năm)
	krsort($years);
	$store = array();
	foreach($years as $y => $yr){
		$store[(string) $y] = array('mode' => $yr['mode'], 'year' => $yr['year'], 'q' => $yr['q']);
	}
	if($clsConfiguration->updateValue("CommissionSheets", json_encode($store, JSON_UNESCAPED_UNICODE))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function commission_crawl(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$clsConfiguration;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsCommission = new Commission();
	$quarter_id = Input::post("quarter_id");
	$spreadsheetId = Input::post("spreadsheetId");
	$sheet_name = trim((string) Input::post("sheet_name", ''));
	$results = array();
	if(!empty($quarter_id) && !empty($spreadsheetId)){
		// Crawl 1 kỳ cụ thể (nút Crawl theo dòng) — sheet_name lấy thẳng từ form
		$results[$quarter_id] = $clsCommission->crawl($spreadsheetId, $quarter_id, $sheet_name);
	} else {
		// Không tham số → ĐỒNG BỘ NGAY tất cả job đang bật (mode độc quyền: năm HOẶC quý)
		$years = $clsCommission->normalizeConfig($clsISO->to_array_json($clsConfiguration->getValue("CommissionSheets")));
		$jobs = $clsCommission->resolveCrawlJobs($years);
		foreach($jobs as $job){
			try {
				$results[$job['quarter_id']] = $clsCommission->crawl($job['spreadsheetId'], $job['quarter_id'], $job['sheet_name']);
			} catch(Exception $_ex){
				$results[$job['quarter_id']] = '_exception: ' . $_ex->getMessage();
			}
		}
	}
	$msg = empty($results) ? 'error' : 'ok';
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'results' => $results,
		'error' => implode(',', $errors_arrs)
	)); die();
}
function commission_list(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCommission = new Commission();
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsCommission', $clsCommission);
	###
	$more = array();
	$uid = $clsISO->getUniqid();
	$QueryBuider = DB::table($clsCommission->tbl);
	$QueryBuider->where("is_trash", 0);
	// $cond = "`is_trash`=0";
	$keyword = Input::post('keyword');
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_time = !empty($start_date) ? $clsISO->toTime($start_date, "00:00:00") : 0;
	$end_time = !empty($end_date) ? $clsISO->toTime($end_date, "23:59:59") : 0;
	$status_id = (int) Input::post('status_id', 0);
	$status_sale_id = (int) Input::post('status_sale_id', 0);
	$status_company_id = (int) Input::post('status_company_id', 0);
	if(!empty($keyword)){
		$QueryBuider->group_start();
		$QueryBuider->like("JSON_EXTRACT(`more_information`,\"$.stock_code\")", $keyword);
		$QueryBuider->or_like("JSON_EXTRACT(`more_information`,\"$.agency_name\")", $keyword);
		$QueryBuider->or_like("JSON_EXTRACT(`more_information`,\"$.staff_name\")", $keyword);
		$QueryBuider->group_end();
	}
	$cnd = $cond;
	if($start_time > 0 && $end_time == 0){
		$QueryBuider->where("contract_date", $start_time, ">");
	} if($start_time == 0 && $end_time > 0){
		$QueryBuider->where("contract_date", $end_time, "<");
	} else if($start_time > 0 && $end_time > 0){
		$QueryBuider->group_start();
		$QueryBuider->where('contract_date', $start_time, '>');
		$QueryBuider->where('contract_date', $end_time, '<');
		$QueryBuider->group_end();
	}
	if($status_id > 0) {
		// $cond.= " and `status_id`='{$status_id}'";
		$QueryBuider->where("status_id", $status_id);
	}
	if($status_sale_id > 0) {
		// $cond.= " and `status_sale_id`='{$status_sale_id}'";
		$QueryBuider->where("status_sale_id", $status_sale_id);
	} 
	if($status_company_id > 0) {
		// $cond.= " and `status_company_id`='{$status_company_id}'";
		$QueryBuider->where("status_company_id", $status_company_id);
	}
	#- Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 50);
	$total_record = $QueryBuider->copy()->count_all_results();
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page-1)*$per_page;
	$QueryBuider->limit($per_page);
	$QueryBuider->offset($offset);
	#- End Pagination
	// $list_items = $clsCommission->getAll($cond." order by `reg_date` DESC".$limitCond);
	$QueryBuider->order_by('contract_date', 'DESC');
	$list_items = $QueryBuider->get();
	// $clsISO->print_pre($list_items); die();
	if(!empty($list_items)){
		$arr_property_cached = $arr_prop_cached = $arr_profile_cached = array();
		$p_field = "{$clsProperty->pkey},`title`,`bgcolor`,`textcolor`";
		$tmp = $clsProperty->getAll("`property_type`='COMMISSION_PAYMENT_STATUS'", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$status_id = $val[$clsProperty->pkey];
				$arr_prop_cached[$status_id] = $val;
				$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id, $val);
			}
			unset($tmp);
		}
		foreach($list_items as $key => $val){
			$status_sale_id = $val['status_sale_id'];
			$status_agent_id = $val['status_agent_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_items[$key]['more_information'] = $more_information;
			$list_items[$key]['bgcolor'] = $arr_prop_cached[$status_sale_id]['bgcolor'];
		}
	}
	// $clsISO->print_pre($list_items); die();
	$smarty->assign('uid', $uid);
	$smarty->assign('list_items', $list_items);
	$smarty->assign('arr_prop_cached', $arr_prop_cached);
	$smarty->assign('arr_property_cached', $arr_property_cached);
	// Return
	$html = $core->build('commission'.DS.'_ajax.list.tpl');
	echo json_encode(array_merge($more, array(
		'uid' => $uid,
		'html' => $html,
		// 'more' => $more,
		'html_briefs' => $html_briefs,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'per_page' => $per_page,
	))); die();
}
function commission_open(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsBilling = new Billing();
	$clsCommission = new Commission();
	$uid = $clsISO->getUniqid();
	$commission_id = (int) Input::post('commission_id', 0);
	###
	$action = '_add';
	$titlePage = 'Thêm mới Hoa hồng';
	$oneCommission = array(
		'reg_date' => time(),
		'upd_date' => time(),
		'user_id' => $profile_id,
		'user_id_update' => $profile_id,
		'billing_id' => 0,
		'billing_code' => "",
		'status_sale_id' => _COMMISSION_UNPAID_STATUS_ID,
		'status_company_id' => _COMMISSION_UNPAID_STATUS_ID,
		'status_id' => _COMMISSION_BILLING_UNREISTED_CONTRACT_ID,
	);
	$more_information = array();
	$more_information['partner_id'] = 0;
	$more_information['partner_name'] = "";
	if($commission_id > 0){
		$action = '_edit';
		$field = "`t1`.*,`t2`.`billing_code`,`t2`.`stock_code`";
		$oneCommission = $dbconn->getRow("SELECT {$field} FROM {$clsCommission->tbl} AS t1 
			INNER JOIN {$clsBilling->tbl} AS `t2` ON `t1`.`billing_id`=`t2`.`billing_id` 
			WHERE `t1`.`commission_id`='{$commission_id}'");
		$more_information = $oneCommission['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$titlePage = 'Chỉnh sửa Hoa hồng';
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('commission_id', $commission_id);
	$smarty->assign('oneCommission', $oneCommission);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$html = $core->build('commission'.DS.'_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function commission_save(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsBilling = new Billing();
	$clsCommission = new Commission();
	
	$msg = "_error";
	$commission_id = (int) Input::post('commission_id', 0);
	$status_id = Input::post('status_id', _VAT_STATUS_UNPAID);
	$billing_id = (int) Input::post('billing_id', 0);
	$status_sale_id = Input::post('status_sale_id', _COMMISSION_UNPAID_STATUS_ID);
	$status_company_id = Input::post('status_company_id', _COMMISSION_UNPAID_STATUS_ID);
	$commission = Input::post('commission', 0);
	$seller_commission = Input::post('seller_commission', 0);
	$sale_department_commission = Input::post('sale_department_commission', 0);
	$project_director_commission = Input::post('project_director_commission', 0);
	$seller_bonus = Input::post('seller_bonus', 0);
	$leader_bonus = Input::post('leader_bonus', 0);
	$agency_bonus = Input::post('agency_bonus', 0);
	$marketing_bonus = Input::post('marketing_bonus', 0);
	$deposit_money = Input::post('deposit_money', 0); // Tạm ứng Sale
	$deposit_money_company  = Input::post('deposit_money_company', 0);
	$sale_department_support_money = Input::post('sale_department_support_money', 0);
	$seller_bonus = !empty($seller_bonus) 
		? $clsISO->processSmartNumber($seller_bonus) : 0;
	$leader_bonus = !empty($leader_bonus) 
		? $clsISO->processSmartNumber($leader_bonus) : 0;
	$agency_bonus = !empty($agency_bonus) 
		? $clsISO->processSmartNumber($agency_bonus) : 0;
	$marketing_bonus = !empty($marketing_bonus) 
		? $clsISO->processSmartNumber($marketing_bonus) : 0;
	$deposit_money = !empty($deposit_money) 
		? $clsISO->processSmartNumber($deposit_money) : 0;
	$deposit_money_company = !empty($deposit_money_company) 
		? $clsISO->processSmartNumber($deposit_money_company) : 0;
	$sale_department_support_money = !empty($sale_department_support_money) 
		? $clsISO->processSmartNumber($sale_department_support_money) : 0;
	$commission = !empty($commission) ? $clsISO->convertToNumber($commission) : 0;
	$seller_commission = !empty($seller_commission) ? $clsISO->convertToNumber($seller_commission) : 0;
	$sale_department_commission = !empty($sale_department_commission) 
		? $clsISO->convertToNumber($sale_department_commission) : 0;
	$project_director_commission = !empty($project_director_commission) 
		? $clsISO->convertToNumber($project_director_commission) : 0;
	$oBilling = $clsBilling->getOne($billing_id);
	$total_amount = $oBilling['totalgrand'];
	$total_eighty_money = $total_amount * 80/100;
	$total_commission_money = $total_eighty_money * $commission / 100;
	$total_pretax_money = $total_commission_money + $seller_bonus + $agency_bonus + $marketing_bonus;
	$tax_vat_money = $total_pretax_money * 10 / 100;
	$tax_personal_money = $total_commission_money * 10 / 100;
	// Tổng tiền = 
	$total_money = $total_pretax_money + $tax_vat_money - $tax_personal_money;
	// Tổng tiền phải thu = Tổng cộng - Tạm ứng công ty nhận về
	$total_final_money = 0; // Phải thu
	if($status_company_id != _COMMISSION_PAID_STATUS_ID){
		$total_final_money = $total_money - $deposit_money_company;
	}
	// Tiền hoa hồng Sale
	$seller_commission_money = $total_eighty_money * $seller_commission / 100;
	// Tổng tiền sale nhận = Tiền hoa hồng + Tiền thưởng.
	$total_seller_money = $seller_commission_money + $seller_bonus;
	// Tiền hoa hồng công ty
	$company_commision = $commission - $sale_department_commission - $project_director_commission;
	$company_commision_money = $company_commision * $total_eighty_money / 100;
	$company_bonus_money = 0;
	if($agency_bonus > 0 || $marketing_bonus > 0){
		$company_bonus_money = $agency_bonus + $marketing_bonus - $sale_department_support_money;
	}
	// Tổng tiền phải thu = TT + Thưởng MKT
	$total_company_money = 0;
	if($status_company_id == _COMMISSION_PAID_STATUS_ID){
		$total_company_money = $company_commision_money + $company_bonus_money;
	}
	// Tổng hoa hồng PKD
	$sale_department_money = $total_eighty_money * $sale_department_commission / 100;
	// Tổng tiền 
	$total_sale_department_money = $sale_department_money + $sale_department_support_money + $seller_bonus;
	// Hoa hồng leader = Hoa hồng PKD - Hoa hồng Sale
	$leader_commission = $sale_department_commission - $seller_commission;
	$leader_commission_money = !empty($leader_commission) ? ($total_eighty_money * $leader_commission) / 100 : 0;
	$total_leader_money = $leader_commission_money + $leader_bonus;
	// GĐ dự án
	$project_director_money = $project_director_commission * $total_eighty_money / 100;
	if($commission_id > 0){
		$more_information = $clsCommission->getOneField('more_information', $commission_id);
		$more_information = $clsISO->to_array_json($more_information);
		//$more_information['deposit_date'] = $deposit_date;
		//$more_information['staff_name'] = $staff_name;
		//$more_information['staff_id'] = $staff_id;
		//$more_information['stock_code'] = $stock_code;
		//$more_information['partner_id'] = $partner_id;
		//$more_information['partner_name'] = $partner_name;
		$more_information['total_amount'] = $total_amount;
		$more_information['commission'] = $commission;
		$more_information['total_commission_money'] = $total_commission_money;
		$more_information['tax_vat_money'] = $tax_vat_money;
		$more_information['tax_personal_money'] = $tax_personal_money;
		$more_information['total_money'] = $total_money;
		$more_information['company_bonus_money'] = $company_bonus_money;
		$more_information['company_commision_money'] = $company_commision_money;
		$more_information['total_company_money'] = $total_company_money;
		$more_information['sale_department_money'] = $sale_department_money;
		$more_information['total_sale_department_money'] = $total_sale_department_money;
		$more_information['leader_bonus'] = $leader_bonus;
		$more_information['leader_commission'] = $leader_commission;
		$more_information['leader_commission_money'] = $leader_commission_money;
		$more_information['total_leader_money'] = $total_leader_money;
		$more_information['project_director_money'] = $project_director_money;
		$more_information['seller_bonus'] = $seller_bonus;
		$more_information['seller_commission'] = $seller_commission;
		$more_information['sale_department_commission'] = $sale_department_commission;
		$more_information['project_director_commission'] = $project_director_commission;
		$more_information['agency_bonus'] = $agency_bonus;
		$more_information['marketing_bonus'] = $marketing_bonus;
		$more_information['sale_department_support_money'] = $sale_department_support_money;
		$more_information['deposit_money'] = $deposit_money;
		$more_information['deposit_money_company'] = $deposit_money_company;
		$more_information['status_id'] = $status_id;
		$more_information['status_sale_id'] = $status_sale_id;
		$more_information['status_company_id'] = $status_company_id;
		$more_information['notes'] = Input::post('notes');
		// $clsISO->print_pre($more_information); die();
		if($clsCommission->countItem("{$clsCommission->pkey}<>'{$commission_id}' and `billing_id`='{$billing_id}'") > 0){
			$msg = "_duplicated";
		} else {
			if($clsCommission->updateOne($commission_id, array(
				'billing_id' => $billing_id,
				'status_id' => $status_id,
				'status_sale_id' => $status_sale_id,
				'status_company_id' => $status_company_id,
				'total_amount' => $total_amount,
				'deposit_money' => $deposit_money,
				'deposit_money_company' => $deposit_money_company,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'upd_date' => time(),
				'user_id_update' => $profile_id
			))){
				$msg = "_success";
			}
		}
	} else {
		$more_information = array();
		$more_information['total_amount'] = $total_amount;
		$more_information['commission'] = $commission;
		$more_information['total_commission_money'] = $total_commission_money;
		$more_information['tax_vat_money'] = $tax_vat_money;
		$more_information['tax_personal_money'] = $tax_personal_money;
		$more_information['total_money'] = $total_money;
		$more_information['company_bonus_money'] = $company_bonus_money;
		$more_information['company_commision_money'] = $company_commision_money;
		$more_information['total_company_money'] = $total_company_money;
		$more_information['sale_department_money'] = $sale_department_money;
		$more_information['total_sale_department_money'] = $total_sale_department_money;
		$more_information['leader_bonus'] = $leader_bonus;
		$more_information['leader_commission'] = $leader_commission;
		$more_information['leader_commission_money'] = $leader_commission_money;
		$more_information['total_leader_money'] = $total_leader_money;
		$more_information['project_director_money'] = $project_director_money;
		$more_information['seller_bonus'] = $seller_bonus;
		$more_information['seller_commission'] = $seller_commission;
		$more_information['sale_department_commission'] = $sale_department_commission;
		$more_information['project_director_commission'] = $project_director_commission;
		$more_information['agency_bonus'] = $agency_bonus;
		$more_information['marketing_bonus'] = $marketing_bonus;
		$more_information['sale_department_support_money'] = $sale_department_support_money;
		$more_information['deposit_money'] = $deposit_money;
		$more_information['deposit_money_company'] = $deposit_money_company;
		$more_information['status_id'] = $status_id;
		$more_information['status_sale_id'] = $status_sale_id;
		$more_information['status_company_id'] = $status_company_id;
		// $clsISO->print_pre($more_information); die();
		$commission_id = $clsCommission->getMaxId();
		if($clsCommission->countItem("`billing_id`='{$billing_id}'") > 0){
			$msg = "_duplicated";
		} else {
			if($clsCommission->insert(array(
				$clsCommission->pkey => $commission_id,
				'billing_id' => $billing_id,
				'status_id' => $status_id,
				'status_sale_id' => $status_sale_id,
				'status_company_id' => $status_company_id,
				'total_amount' => $total_amount,
				'deposit_money' => $deposit_money,
				'deposit_money_company' => $deposit_money_company,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo $msg; die();
}
function commission_delete(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsCommission = new Commission();
	###
	$msg = "_error";
	$commission_id = (int) Input::post('commission_id', 0);
	if($commission_id > 0){
		if($clsCommission->deleteOne($commission_id)){
			$msg = "_success";
		}
		// if($clsCommission->updateOne($commission_id, array(
		// 	'is_trash' => 1
		// ))){
		// 	$msg = "_success";
		// }
	}
	// Return
	echo $msg; die();
}
