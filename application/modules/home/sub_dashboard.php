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
function dashboard_load_ranking_club(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$deviceType; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$arr_clubs = array();
	foreach([_GROUP_NS_CLUB, _GROUP_DM_CLUB] as $group_id){
		$oneGroup = $clsGroupProfile->getOne($group_id, "`group_profile_id`,`title`,`list_profile_id`");
		$list_profile_id = $oneGroup['list_profile_id'];
		$arr_members = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array();
		$total_members = !empty($arr_members) ? count($arr_members) : 0;
		##
		$start_date = strtotime('1-7-2025 00:00:00');
		$end_date = strtotime('30-9-2025 23:59:59');
		##
		$field = "{$clsBilling->pkey},`totalgrand`";
		$list_billings = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`=0 AND `staff_id` in (".implode(',', $arr_members).") 
		AND (`deposit_date` BETWEEN {$start_date} AND {$end_date})", $field);
		$total_billings = $total_sales = 0;
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			foreach($list_billings as $key => $val){
				$totalgrand = $val['totalgrand'];
				$total_sales += $clsISO->processSmartNumber($totalgrand);
			}
		}
		$arr_clubs[] = array(
			'group_id' => $group_id,
			'title' => $oneGroup['title'],
			'total_members' => $total_members,
			'total_billings' => $total_billings,
			'total_sales' => $total_sales
		);
	}
	$columns = @array_column($arr_clubs, 'total_sales');
	@array_multisort($columns, SORT_DESC, $arr_clubs);
	###
	$html = ""; $ii = 0;
	foreach($arr_clubs as $key => $val){
		$html.= '<div onClick="$Core.dashboard.open_clb(this, event)" clb_id="'.$val['group_id'].'" class="ranking-item ranking-item__'.$val['group_id'].' d-flex cursor-pointer fw-bold align-items-center justify-content-between w-100 h-px-40'.($ii==0?' mb-2':'').'">
			<div class="ranking-text text-white">
				<div class="d-flex align-items-center gap-1">
					<img class="w-px-25" src="'.URL_IMAGES.'/icons/logo-future-'.($val['group_id']==_GROUP_NS_CLUB?'starter':'diamond').'.png" />
					<span>'.$val['title'].'</span>
				</div>
			</div>
			<div class="d-flex gap-1 align-items-center justify-content-between">
				<div class="w-px-40 text-center">'.$val['total_members'].'</div>
				<div class="w-px-40 text-center">'.$val['total_billings'].'</div>
				<div class="w-px-80 text-center">'.$clsISO->shortNumber($val['total_sales']).'</div>
			</div>
		</div>';
		++$ii;
	}
	// Return 
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_open_clb(){
	global $smarty,$core,$clsISO,$oneProfile,$dbconn,$profile_id,$deviceType; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$uid = $clsISO->getUniqid();
	$clb_id = (int) Input::post('clb_id', 0);
	$start_date = strtotime('1-7-2025 00:00:00');
	$end_date = strtotime('30-9-2025 23:59:59');
	$oneGroup = $clsGroupProfile->getOne($clb_id, "`group_profile_id`,`title`,`list_profile_id`");
	$list_profile_id = $oneGroup['list_profile_id'];
	$arr_members = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array();
	$list_members = array(); $total_members = 0;
	if(!empty($arr_members)){
		$total_members = count($arr_members);
		// $dbconn->debug = true;
		$field = "`profile_id`,`first_name`,`last_name`,`full_name`,`avatar`,`department_id`";
		$list_members = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $arr_members).")", $field);
		if(!empty($list_members)){
			$arr_property_cached = array();
			foreach($list_members as $key => $val){
				$profile_id = $val[$clsProfile->pkey];
				$department_id = $val['department_id'];
				$QueryBuilder = DB::table($clsBilling->tbl);
				$QueryBuilder->select("{$clsBilling->pkey},totalgrand");
				$QueryBuilder->where('is_trash', 0);
				$QueryBuilder->where('is_cancel', 0);
				$QueryBuilder->where('staff_id', $profile_id);
				$QueryBuilder->where_between('deposit_date', $start_date, $end_date);
				$tmp = $QueryBuilder->get();
				###
				$total_billings = $total_sales = 0; 
				if(!empty($tmp)){
					$total_billings = count($tmp);
					foreach($tmp as $okey => $oval){
						$total_sales += $clsISO->processSmartNumber($oval['totalgrand']);
					}
					unset($tmp);
				}
				$list_members[$key]['total_billings'] = $total_billings;
				$list_members[$key]['total_sales'] = $total_sales;
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] = $clsProperty->getTitle($department_id);
				}
				$list_members[$key]['department_name'] = $arr_property_cached[$department_id];
			}
			$columns = @array_column($list_members, 'total_sales');
			@array_multisort($columns, SORT_DESC, $list_members);
		}
	}
	$smarty->assign('clb_id', $clb_id);
	$smarty->assign('oneGroup', $oneGroup);
	$smarty->assign('total_members', $total_members);
	$smarty->assign('list_members', $list_members);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.open_clb.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function dashboard_load_commission_person(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsCache = new Cache();
	$clsBilling = new Billing();
	$clsCommission = new Commission();
	##
	$uid = $clsISO->getUniqid();
	$year = (int) Input::post('year', date('Y'));
	##
	$is_dir_sale = $is_dir_project = $is_head_dep = 0;
	if($clsISO->checkPermissionGroup('HEAD_SALE')){
		$is_head_dep = 1;
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$is_dir_sale = 1;
	}
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
		$is_dir_project = 1;
	} 
	$cond = "`t1`.`is_cancel`=0";
	if($is_head_dep == 1 && $is_dir_project == 0){ // TPKD & KO GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_team_id\")='{$profile_id}'))";
	} else if($is_head_dep == 1 && $is_dir_project == 1){ // TPKD & GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND (JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_team_id\")='{$profile_id}' OR JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.project_director_id\")='{$profile_id}')))";
	} else if($is_dir_sale == 1 && $is_dir_project == 0){ // GĐKD
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_dep_id\")='{$profile_id}'))";
	} else if($is_dir_sale == 1 && $is_dir_project == 1) { // GĐKD + GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND (JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_dep_id\")='{$profile_id}' OR JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.project_director_id\")='{$profile_id}')))";
	} else if($is_dir_project == 1 && $is_dir_sale == 0) { // GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.project_director_id\")='{$profile_id}'))";
	} else { // SALE
		$cond.= " AND (`t1`.`staff_id`='{$profile_id}')";
	}
	$cond.= " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%Y')>='{$year}'";
	$cache_name = sprintf('_commission_%s_cached', $profile_id);
	if($clsCache->has($cache_name)){
		$arr_billings = $clsCache->get($cache_name);
	} else {
		$field = "`t1`.`staff_id`,`t1`.`totalgrand`,`t1`.`more_information`,`t2`.`more_information` AS `commission_information`";
		$field.= ",`t2`.`status_sale_id`,`t2`.`status_leader_id`,`t2`.`status_sale_dir_id`,`t2`.`status_project_dir_id`
		,`t1`.`stock_code`,`t1`.`{$clsBilling->pkey}`";
		$arr_billings = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 
			LEFT JOIN {$clsCommission->tbl} AS `t2` ON `t1`.`billing_id`=`t2`.`billing_id` WHERE {$cond}");
		if(!empty($arr_billings)){
			foreach($arr_billings as $key => $val){
				$more_information = $val['more_information'];
				$commission_information = $val['commission_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$commission_information = $clsISO->to_array_json($commission_information);
				$arr_billings[$key]['more_information'] = $more_information;
				$arr_billings[$key]['commission_information'] = $commission_information;
			}
		}
		$clsCache->put($cache_name, $arr_billings, 12*60*60);
	}
	$arr_stocks = $arr_deposits = $arr_billing_cached = array();
	$total = $total_paid = $total_wait = $total_paid_commissions = $total_wait_commissions = 0;
	if(!empty($arr_billings)){
		foreach($arr_billings as $key => $val){
			$billing_id = $val[$clsBilling->pkey];
			$staff_id = $val['staff_id'];
			$stock_code = $val['stock_code'];
			$totalgrand = (float) $val['totalgrand'];
			$status_sale_id = (int) $val['status_sale_id'];
			$status_leader_id = (int) $val['status_leader_id'];
			$status_sale_dir_id = (int) $val['status_sale_dir_id'];
			$status_project_dir_id = (int) $val['status_project_dir_id'];
			$more_information = $val['more_information'];
			$commission_information = $val['commission_information'];
			$dep_logs = $core->get_field($more_information, "dep_logs", []); // Logs
			$head_of_team_id = (int) $core->get_field($dep_logs, 'head_of_team_id', 0); // TPKD
			$head_of_dep_id = (int) $core->get_field($dep_logs, 'head_of_dep_id', 0); // GĐKD
			$project_director_id = (int) $core->get_field($dep_logs, 'project_director_id', 0);
			$advance_paid_amount = 0;
			if(!empty($commission_information)){
				$contract_comm_base = (float) $core->get_field($commission_information, "contract_comm_base", 0);
				if($staff_id == $profile_id){ // Là người bán
					$sales_commission_rate = (float) $core->get_field($commission_information, "sales_commission_rate", 0); // Tổng tiền
					$sales_commission_amount = (float) $core->get_field($commission_information, "sales_commission_amount", 0);
					$sales_bonus_amount = (float) $core->get_field($commission_information, "sales_bonus_amount_out", 0); // Tổng thưởng
					$sales_total_amount = (float) $core->get_field($commission_information, "sales_total_amount", 0);
					$advance_paid_amount = (float) $core->get_field($commission_information, "advance_paid_amount", 0); // Đã tạm ứng
					if(!empty($sales_commission_rate)){ // Nhập rồi không trống
						if(empty($sales_total_amount)){
							$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
						}
					} else {
						$sales_commission_rate = (float) $core->get_field($more_information, "commission", 0);
						$sales_bonus_amount = (float) $core->get_field($more_information, "sales_bonus_amount", 0);
						$sales_commission_amount = Helper::cal($contract_comm_base, $sales_commission_rate);
						$sales_total_amount = $sales_commission_amount + $sales_bonus_amount;
					}
					if($head_of_team_id == $profile_id){ // Là TPKD
						$leader_commission_rate = (float) $core->get_field($commission_information, 'leader_commission_rate', 0);
						$leader_commission_amount = (float) $core->get_field($commission_information, 'leader_commission_amount', 0);
						$leader_total_amount = (float) $core->get_field($commission_information, 'leader_total_amount', 0);
						$sales_commission_rate += $leader_commission_rate;
						$sales_commission_amount += $leader_commission_amount;
						$sales_total_amount += $leader_total_amount;
					}
					if($head_of_dep_id == $profile_id){ // Là GĐKD
						$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sale_dir_commission_amount = Helper::cal($contract_comm_base, $sale_dir_commission_rate);
						$sales_commission_rate += $sale_dir_commission_rate;
						$sales_commission_amount += $sale_dir_commission_amount;
						$sales_total_amount += $sale_dir_commission_amount;
					} 
					if($project_director_id == $profile_id){ // Là GĐDA
						$project_dir_commission_rate = (float) $core->get_field($commission_information, "project_dir_commission_rate", 0);
						$project_dir_commission_amount = (float) $core->get_field($commission_information, "project_dir_commission_amount", 0);
						$project_dir_net_bonus_amount += (float) $core->get_field($commission_information, "project_dir_net_bonus_amount_out", 0);
						$project_dir_total_amount = (float) $core->get_field($commission_information, "project_dir_total_amount", 0);
						$sales_commission_rate += $project_dir_commission_rate;
						$sales_commission_amount += $project_dir_commission_amount;
						$sales_bonus_amount += $project_dir_net_bonus_amount;
						$sales_total_amount += $project_dir_total_amount;
					}
					if($status_sale_id == _COMMISSION_PAID_STATUS_ID){
						$total_paid += 1;
						$total_paid_commissions += $sales_total_amount;
					} else {
						$total_wait += 1;
						$total_wait_commissions += $sales_total_amount;
						$total_wait_commissions -= $advance_paid_amount;
						$total_paid_commissions += $advance_paid_amount;
					}
				} else { // Không phải là người bán
					$sales_total_amount = 0;
					if($head_of_team_id == $profile_id){ // Vài trò là TPKD
						$sales_commission_rate = (float) $core->get_field($commission_information, "leader_commission_rate", 0);
						if(!empty($sales_commission_rate)){
							$sales_commission_amount = (float) $core->get_field($commission_information, "leader_commission_amount", 0);
							$sales_bonus_amount = (float) $core->get_field($commission_information, "leader_commission_bonus_amount", 0);
							$sales_total_amount += (float) $core->get_field($commission_information, "leader_total_amount", 0);
						} else {
							$sales_bonus_amount = 0;
							$sales_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
							$sales_commission_amount = $contract_comm_base * $sales_commission_rate / 100;
							$sales_total_amount += ($sales_commission_amount + $sales_bonus_amount);
						}
						if($status_leader_id == _COMMISSION_PAID_STATUS_ID){
							if(!in_array($billing_id, $arr_billing_cached)) {
								$total_paid += 1;
								$arr_billing_cached[] = $billing_id;
							}
							$total_paid_commissions += $sales_total_amount;
						} else {
							if(!in_array($billing_id, $arr_billing_cached)) {
								$total_wait += 1;
								$arr_billing_cached[] = $billing_id;
							}
							$total_wait_commissions += $sales_total_amount;
						}
					}
					if($head_of_dep_id == $profile_id){ // Là GĐDA + GĐKD
						$sales_bonus_amount = 0;
						$sales_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sales_commission_amount = ($contract_comm_base * $sales_commission_rate) / 100;
						$sales_total_amount += ($sales_commission_amount + $sales_bonus_amount);
						if($status_sale_dir_id == _COMMISSION_PAID_STATUS_ID){
							if(!in_array($billing_id, $arr_billing_cached)) {
								$total_paid += 1;
								$arr_billing_cached[] = $billing_id;
							}
							$total_paid_commissions += $sales_total_amount;
						} else {
							if(!in_array($billing_id, $arr_billing_cached)) {
								$total_wait += 1;
								$arr_billing_cached[] = $billing_id;
							}
							$total_wait_commissions += $sales_total_amount;
						}
					}
					if($project_director_id == $profile_id){ // Vài trò là GĐDA
						$sales_commission_rate = (float) $core->get_field($commission_information, "project_dir_commission_rate", 0);
						if(!empty($sales_commission_rate)){
							$sales_commission_amount = (float) $core->get_field($commission_information, "project_dir_commission_amount", 0);
							$sales_total_amount += (float) $core->get_field($commission_information, "project_dir_total_amount", 0);
						} else {
							$sales_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
							$sales_bonus_amount = (float) $core->get_field($commission_information, "project_dir_bonus_amount_out", 0);
							$sales_commission_amount = ($contract_comm_base * $sales_commission_rate) / 100;
							$sales_total_amount += ($sales_commission_amount + $sales_bonus_amount);
						}
						if($status_project_dir_id == _COMMISSION_PAID_STATUS_ID){
							if(!in_array($billing_id, $arr_billing_cached)) {
								$total_paid += 1;
								$arr_billing_cached[] = $billing_id;
							}
							$total_paid_commissions += $sales_total_amount;
						} else {
							if(!in_array($billing_id, $arr_billing_cached)) {
								$total_wait += 1;
								$arr_billing_cached[] = $billing_id;
							}
							$total_wait_commissions += $sales_total_amount;
						}
					}
				}
			} else {
				$total_wait += 1;
				$contract_comm_base = $totalgrand * 0.8;
				if($staff_id == $profile_id){ // Người bán
					$sales_commission_rate = (float) $core->get_field($more_information, "commission", 0);
					$sales_bonus_amount = (float) $core->get_field($more_information, 'sales_bonus', 0);
					if($head_of_team_id == $profile_id){ // TPKD
						$leader_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
						$sales_commission_rate += $leader_commission_rate;
					}
					if($head_of_dep_id == $profile_id){ // GĐKD
						$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sales_commission_rate += $sale_dir_commission_rate;
					}
					if($project_director_id == $profile_id){ // GĐDA
						$project_dir_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
						$sales_commission_rate += $project_dir_commission_rate;
					}
					$sales_commission_amount = $contract_comm_base * $sales_commission_rate / 100;
				} else { // Người khác bán
					$sales_bonus_amount = $sales_commission_rate = 0;
					if($head_of_team_id == $profile_id){
						$leader_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
						$sales_commission_rate += $leader_commission_rate;
					} 
					if($head_of_dep_id == $profile_id){ // Là GĐDA + GĐKD
						$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sales_commission_rate += $sale_dir_commission_rate;
					} 
					if($project_director_id == $profile_id){ // Là GĐDA
						$project_dir_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
						$sales_commission_rate += $project_dir_commission_rate;
					}
					$sales_commission_amount += ($contract_comm_base * $sales_commission_rate) / 100;
				}
				$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
				$total_wait_commissions += $sales_total_amount;
			}
		}
		unset($arr_billings);
	}
	$total = $total_paid_commissions + $total_wait_commissions;
	$attrs = $clsISO->make_attrs_builder(array(
		'total_paid_commissions' => $total_paid_commissions,
		'total_wait_commissions' => $total_wait_commissions,
		'total_paid' => $total_paid,
		'total_wait' => $total_wait
	));
	$html = '<hr class="my-0 bg-wine" />
	<div class="row ">
		<div class="col-6 py-3 border-end border-wine">
			<div class="d-flex align-items-cecnter gap-2">
				<div class="avatar avatar-sm flex-shrink-0">
					<img src="'.URL_IMAGES.'/icons/reshot-icon-wallet-full-of-money-NRHXA2YE5S.svg" class="rounded" />
				</div>
				<div class="d-flex flex-column text-white">
					<div class="d-flex align-items-center mb-1 text-upper">Đã nhận</div>
					<h3 class="text-fs-18 text-warning mb-1">'.$clsISO->shortNumber($total_paid_commissions,2).'</h3>
					<p class="mb-0 text-fs-12">'.$total_paid.' giao dịch đã trả</p>
				</div>
			</div>
		</div>
		<div class="col-6 py-3">
			<div class="d-flex align-items-cecnter gap-2">
				<div class="avatar avatar-sm flex-shrink-0">
					<img src="'.URL_IMAGES.'/icons/reshot-icon-money-BTKWYNM52F.svg" class="rounded" />
				</div>
				<div class="d-flex flex-column text-white">
					<div class="d-flex align-items-center mb-1 text-upper">Chờ nhận</div>
					<h3 class="text-warning text-fs-18 mb-1">'.$clsISO->shortNumber($total_wait_commissions,2).' </h3>
					<p class="mb-0 text-fs-12">'.$total_wait.' giao dịch đang chờ xử lý</p>
				</div>
			</div>
		</div>
	</div>
	<a href="javascript:void(0);" class="btn border-wine text-white btn-block text-upper btn-outline-default text-fs-13" 
		onClick="$Core.dashboard.open_commission(this, event)" '.$attrs.'>Xem chi tiết <i class=\'bx bx-link-external text-fs-13\'></i></a>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total' => $clsISO->shortNumber($total,3)
	)); die();
}
function dashboard_open_commission(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$deviceType;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsCommission = new Commission();
	$_dataPost = $_POST;
	$uid = $clsISO->getUniqid();
	$list_preloaders = $list_months = $list_years = $arr_status = array();
	for($i = 0; $i < 15; $i++){
		$list_preloaders[] = $i;
	}
	for($i = date('Y'); $i >= 2025; $i--){
		$list_years[] = $i;
	}
	for($i=1; $i<=date('n'); $i++){
		$list_months[] = $i;
	}
	#
	$is_dir_project = $is_dir_sale = $is_head_dep = 0; 
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
		$is_dir_project = 1; // Là GĐ DA
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$is_dir_sale = 1; // Gám đốc phòng
	}
	if($clsISO->checkPermissionGroup('HEAD_SALE')){
		$is_head_dep = 1;
	}
	$is_tab = 0;
	if($is_dir_project == 1 || $is_dir_sale == 1 || $is_head_dep == 1){
		$is_tab = 1;
	}
	#
	$tmp = $clsProperty->getAll("`property_type`='COMMISSION_PAYMENT_STATUS'", "{$clsProperty->pkey},`title`,`image`");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_status[$val[$clsProperty->pkey]] = sprintf(
			'<span class="d-flex gap-2 align-items-center">
				<span class="badge rounded-pill w-px-15 h-px-15 %s">&nbsp;</span> %s
			</span>', $val['image'], $val['title']);
		}
		unset($tmp);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('is_tab', $is_tab);
	$smarty->assign('_dataPost', $_dataPost);
	$smarty->assign('arr_status', $arr_status);
	$smarty->assign('list_years', $list_years);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('dashboard' . DS . '_ajax.open_commission.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die(); 
}
function dashboard_load_commission(){
	global $smarty,$core,$dbconn,$clsISO,$oneProfile,$profile_id,$deviceType;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsCommission = new Commission();
	###
	$is_dir_project = $is_dir_sale = $is_head_dep = 0; 
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
		$is_dir_project = 1; // Là GĐ DA
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR')){
		$is_dir_sale = 1; // Gám đốc phòng
	}
	if($clsISO->checkPermissionGroup('HEAD_SALE')){
		$is_head_dep = 1;
	}
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', 0);
	$status_id = (int) Input::post('status_id', 0);
	$holderG = Input::post('holderG', '_me');
	$cond = "`t1`.`is_trash`=0 AND `t1`.`is_cancel`=0";
	#- HEAD DEP
	if($is_head_dep == 1){
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_team_id\")='{$profile_id}'))";
	} else if($is_head_dep == 1 && $is_dir_project == 1){ // TPKD & GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND (JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_team_id\")='{$profile_id}' OR JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.project_director_id\")='{$profile_id}')))";
	} else if($is_dir_sale == 1 && $is_dir_project == 0){ // GĐKD
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_dep_id\")='{$profile_id}'))";
	} else if($is_dir_sale == 1 && $is_dir_project == 1) { // GĐKD + GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND (JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.head_of_dep_id\")='{$profile_id}' OR JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.project_director_id\")='{$profile_id}')))";
	} else if($is_dir_project == 1){ // GĐDA
		$cond.= " AND ((`t1`.`staff_id`='{$profile_id}') OR (`t1`.`staff_id`<>'{$profile_id}' AND JSON_EXTRACT(`t1`.`more_information`,\"$.dep_logs.project_director_id\")='{$profile_id}'))";
	} else { // SALE
		$cond.= " AND (`t1`.`staff_id`='{$profile_id}')";
	}
	$html_month = "<option>Tháng</option>";
	if(empty($year)) {
		$start_time = strtotime("01-01-2025");
		$end_time = time();
		$cond.= " AND (`t1`.`deposit_date` BETWEEN '{$start_time}' AND '{$end_time}')";
	}else{
		if($month > 0){
			$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cond.= " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$my}'";
		} else {
			$cond.= " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%Y')='{$year}'";
		}
		if($year == date("Y")) {
			$end_month = date("n");
		}else{
			$end_month = 12;
		}
		for($i=1; $i<= $end_month; $i++){
			$html_month .= "<option value='{$i}' ".($i == $month ? "selected" : "").">Tháng {$i}</option>";
		}		
	}
	if($status_id > 0) $cond.= " AND `t2`.`status_sale_id`='{$status_id}'";
	if($holderG == '_me') $cond.= " AND `t1`.`staff_id`='{$profile_id}'";
	if($holderG == '_sales') $cond.= " AND `t1`.`staff_id`<>'{$profile_id}'";
	#- Pagination
	$total_record = $dbconn->getOne("SELECT COUNT(1) as `total_record` FROM {$clsBilling->tbl} AS `t1` 
	LEFT JOIN {$clsCommission->tbl} AS `t2` ON `t1`.`billing_id`=`t2`.`billing_id` WHERE ".$cond);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 1000);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	$field = "`t1`.`{$clsBilling->pkey}`,`t1`.`staff_id`,`t1`.`stock_code`,`t1`.`totalgrand`,`t1`.`more_information`
	,`t2`.`more_information` AS `commission_information`,`t2`.`status_sale_id`,`t2`.`status_leader_id`,
	`t2`.`status_project_dir_id`,`t2`.`status_sale_dir_id`,`t1`.`contract_date`,`contract_status_id`";
	$arr_billings = $dbconn->getAll("SELECT {$field} FROM `{$clsBilling->tbl}` AS `t1` 
		LEFT JOIN `{$clsCommission->tbl}` AS `t2` ON `t1`.`billing_id`=`t2`.`billing_id` 
		WHERE {$cond} ORDER BY `t1`.`deposit_date` DESC {$limitCond}");
	// $clsISO->print_pre(count($arr_billings)); die();
	$totalgrand = $total_amount = $total_bonus = $total_deposit = $total_billings = 0;
	if(!empty($arr_billings)){
		$arr_property_cached = array();
		$total_billings = count($arr_billings);
		$tmp = $clsProperty->getAll("`property_type`='COMMISSION_PAYMENT_STATUS'", "{$clsProperty->pkey},`title`,`image`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = sprintf(
					'<span class="badge rounded-pill w-px-15 h-px-15 %s" data-bs-toggle="tooltip" title="%s">&nbsp;</span>', $val['image'], $val['title']);
			}
			unset($tmp);
		}
		foreach($arr_billings as $key => $val){
			$staff_id = $val['staff_id'];
			$stock_code = $val['stock_code'];
			$totalgrand = $val['totalgrand'];
			$status_sale_id = (int) $val['status_sale_id'];
			$status_leader_id = (int) $val['status_leader_id'];
			$status_sale_dir_id = (int) $val['status_sale_dir_id'];
			$status_project_dir_id = (int) $val['status_project_dir_id'];
			$more_information = $val['more_information'];
			$commission_information = $val['commission_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$commission_information = $clsISO->to_array_json($commission_information);
			$dep_logs = $core->get_field($more_information, "dep_logs", []); // Logs
			$head_of_team_id = (int) $core->get_field($dep_logs, 'head_of_team_id', 0); // TPKD
			$head_of_dep_id = (int) $core->get_field($dep_logs, 'head_of_dep_id', 0); // GĐKD
			$project_director_id = (int) $core->get_field($dep_logs, 'project_director_id', 0); // GĐDA
			$is_owner = $advance_paid_amount = 0; // Số tiền đã tạm ứng
			if(!empty($commission_information)){
				$contract_comm_base = (float) $core->get_field($commission_information, "contract_comm_base", 0);
				if($staff_id == $profile_id){ // Là người bán
					$is_owner = 1;
					$status_id = $status_sale_id;
					$sales_commission_rate = (float) $core->get_field($commission_information, "sales_commission_rate", 0);
					$sales_commission_amount = (float) $core->get_field($commission_information, "sales_commission_amount", 0);
					$sales_bonus_amount = (float) $core->get_field($commission_information, "sales_bonus_amount_in", 0);
					$sales_total_amount = (float) $core->get_field($commission_information, "sales_total_amount", 0);
					$advance_paid_amount = (float) $core->get_field($commission_information, "advance_paid_amount", 0);
					if(!empty($sales_commission_rate)){ // Nhập rồi không trống
						// $sales_commission_amount = (float) $core->get_field($commission_information, "sales_commission_amount", 0);
						// $sales_total_amount = $core->get_field($commission_information, "sales_total_amount", 0);
						if(empty($sales_total_amount)){
							$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
						}
					} else {
						$sales_commission_rate = (float) $core->get_field($more_information, "commission", 0);
						$sales_bonus_amount = (float) $core->get_field($commission_information, "sales_bonus_amount", 0);
						$sales_commission_amount = Helper::cal($contract_comm_base, $sales_commission_rate);
						$sales_total_amount = $sales_commission_amount + $sales_bonus_amount;
					}
					if($head_of_team_id == $profile_id){ // Là TPKD
						$leader_commission_rate = (float) $core->get_field($commission_information, 'leader_commission_rate', 0);
						$leader_commission_amount = (float) $core->get_field($commission_information, 'leader_commission_amount', 0);
						$leader_total_amount = (float) $core->get_field($commission_information, 'leader_total_amount', 0);
						$sales_commission_rate += $leader_commission_rate;
						$sales_commission_amount += $leader_commission_amount;
						$sales_total_amount += $leader_total_amount;
					}
					if($head_of_dep_id == $profile_id){ // Là GĐKD
						$sale_dir_commission_rate = (float) $core->get_field($commission_information, 'sale_dir_commission_rate', 0);
						$sale_dir_commission_amount = (float) $core->get_field($commission_information, 'sale_dir_commission_amount', 0);
						// $sale_dir_commission_amount = ($contract_comm_base * $sale_dir_commission_rate) / 100;
						$sales_commission_rate += $sale_dir_commission_rate;
						$sales_commission_amount += $sale_dir_commission_amount;
						$sales_total_amount += $sale_dir_commission_amount;
					} 
					if($project_director_id == $profile_id){ // Là GĐDA
						$project_dir_commission_rate = (float) $core->get_field($commission_information, "project_dir_commission_rate", 0);
						$project_dir_commission_amount = (float) $core->get_field($commission_information, "project_dir_commission_amount", 0);
						$project_dir_bonus_amount += (float) $core->get_field($commission_information, "project_dir_bonus_amount_in", 0);
						$project_dir_total_amount = (float) $core->get_field($commission_information, "project_dir_total_amount", 0);
						$sales_commission_rate += $project_dir_commission_rate;
						$sales_commission_amount += $project_dir_commission_amount;
						$sales_bonus_amount += $project_dir_bonus_amount;
						$sales_total_amount += $project_dir_total_amount;	
					}	
				} else {
					$sales_commission_rate = $sales_commission_amount = $sales_bonus_amount = $sales_total_amount = 0;
					if($head_of_team_id == $profile_id){ // Là TPKD
						$status_id = $status_leader_id;
						$leader_commission_rate = (float) $core->get_field($commission_information, "leader_commission_rate", 0);
						$leader_commission_amount = (float) $core->get_field($commission_information, "leader_commission_amount", 0);
						$leader_commission_bonus_amount = (float) $core->get_field($commission_information, "leader_commission_bonus_amount", 0);
						$leader_total_amount = (float) $core->get_field($commission_information, "leader_total_amount", 0);
						$sales_commission_rate += $leader_commission_rate;
						$sales_commission_amount += $leader_commission_amount;
						$sales_bonus_amount += $leader_commission_bonus_amount;
						$sales_total_amount += $leader_total_amount;
					} 
					if($head_of_dep_id == $profile_id){ // Là GĐKD
						$status_id = $status_sale_dir_id;
						$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sale_dir_commission_amount = (float) $core->get_field($more_information, 'sale_dir_commission_amount', 0); 
						$sales_commission_rate += $sale_dir_commission_rate;
						$sales_commission_amount += $sale_dir_commission_amount;
						$sales_total_amount += $sale_dir_commission_amount;
					} 
					if($project_director_id == $profile_id){// Là GĐDA
						$status_id = $status_project_dir_id;
						$project_dir_commission_rate = (float) $core->get_field($commission_information, "project_dir_commission_rate", 0);
						$project_dir_commission_amount = (float) $core->get_field($commission_information, "project_dir_commission_amount", 0);
						$project_dir_bonus_amount = (float) $core->get_field($commission_information, "project_dir_bonus_amount_in", 0);
						$project_dir_total_amount = (float) $core->get_field($commission_information, "project_dir_total_amount", 0);
						$sales_commission_rate += $project_dir_commission_rate;
						$sales_commission_amount += $project_dir_commission_amount;
						$sales_bonus_amount += $project_dir_bonus_amount;
						$sales_total_amount += $project_dir_total_amount;
					}
				}
			} else {
				$contract_comm_base = ($totalgrand * 0.8);
				if($staff_id == $profile_id){ // Mình bán
					$is_owner = 1;
					$status_id = _COMMISSION_NO_STATEMENT_STATUS_ID;
					$sales_commission_rate = (float) $core->get_field($more_informatiion, "commission", 0);
					$sales_bonus_amount = (float) $core->get_field($more_information, 'sales_bonus', 0);
					if($head_of_team_id == $profile_id){ // Là TPKD
						$leader_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
						$sales_commission_rate += $leader_commission_rate;
					}
					if($head_of_dep_id == $profile_id){ // Là GĐKD
						$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sales_commission_rate += $sale_dir_commission_rate;
					} 
					if($project_director_id == $profile_id){ // Là GĐDA
						$project_dir_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
						$sales_commission_rate += $project_dir_commission_rate;
					}
					$sales_commission_amount = Helper::cal($contract_comm_base, $sales_commission_rate);
					$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
				} else {
					$sales_commission_rate = $sales_commission_amount = $sales_bonus_amount = $sales_total_amount = 0;
					if($head_of_team_id == $profile_id){ // Là TPKD
						$status_id = _COMMISSION_NO_STATEMENT_STATUS_ID;
						$leader_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
						$sales_commission_rate += $leader_commission_rate;
					}
					if($head_of_dep_id == $profile_id){ // Là GĐKD
						$status_id = _COMMISSION_NO_STATEMENT_STATUS_ID;
						$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
						$sales_commission_rate += $sale_dir_commission_rate;
					}
					if($project_director_id == $profile_id){ // Là GĐDA
						$status_id = _COMMISSION_NO_STATEMENT_STATUS_ID;
						$project_dir_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
						$sales_commission_rate += $project_dir_commission_rate;
					}
					$sales_commission_amount = Helper::cal($contract_comm_base, $sales_commission_rate);	
					$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
				}
			}
			$total_grand += $sales_commission_amount;
			$total_bonus += $sales_bonus_amount;
			$total_amount += $sales_total_amount;
			$total_deposit += $advance_paid_amount;
			###
			$arr_billings[$key]['is_owner'] = $is_owner;
			$arr_billings[$key]['status_name'] = $arr_property_cached[$status_id];
			$arr_billings[$key]['sales_commission_rate'] = $sales_commission_rate;
			$arr_billings[$key]['sales_bonus_amount'] = $sales_bonus_amount;
			$arr_billings[$key]['sales_commission_amount'] = $sales_commission_amount;
			$arr_billings[$key]['advance_paid_amount'] = $advance_paid_amount;
			$arr_billings[$key]['sales_total_amount'] = $sales_total_amount;
			$arr_billings[$key]['commission_information'] = $commission_information;
		}
	}
	$smarty->assign('arr_billings', $arr_billings);
	$smarty->assign('total_grand', $total_grand);
	$smarty->assign('total_amount', $total_amount);
	$smarty->assign('total_bonus', $total_bonus);
	$smarty->assign('total_deposit', $total_deposit);	
	$smarty->assign('total_billings', $total_billings);
	#
	$html_totals = "";
	if($total_billings > 0){
		$html_totals = '<tr class="nohover">
			<td class="align-center text-right bg-lighter fw-bold"></td>
			<td class="align-center text-center bg-lighter fw-bold">TỔNG CỘNG</td>
			<td class="align-center text-right bg-lighter fw-bold" colspan="3">'.$clsISO->shortNumber($total_amount,2).'</td>
		</tr>';
	}
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('dashboard' . DS . '_ajax.open_commission.tpl');
	echo json_encode(array(
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'html_totals' => $html_totals,
		'html_month' => $html_month,
	)); die(); 
}
function dashboard_ranking_staff(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$deviceType; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	define('MAX_SCORE', 60);
	define('CAMPAIGN_ID', 4);
	define('TRANSACTION_DEF', 12);
	$oneCampaign = $clsCampaign->getOne(CAMPAIGN_ID);
	$campaign_target = $oneCampaign['campaign_target'];
	$campaign_terms = $oneCampaign['campaign_terms'];
	$campaign_terms = $clsISO->to_array_json($campaign_terms);
	$campaign_target = $clsISO->to_array_json($campaign_target);
	$person_targets = isset($campaign_target[$profile_id]) 
		? $campaign_target[$profile_id] : array();
	$billing_target = isset($person_targets['billing']) && (int) $person_targets['billing'] > 0 
		? (int) $person_targets['billing'] : TRANSACTION_DEF;
	$tranning_target = isset($person_targets['tranning']) && (int) $person_targets['tranning'] > 0 
		? (int) $person_targets['tranning'] : 0;
	// $clsISO->print_pre($oneCampaign); die();
	$total_scores = $total_billings = 0;
	$field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`is_fullscore`,`deposit_date`";
	$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 
		and `staff_id`='{$profile_id}' and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."'", $field);
	if(!empty($list_billings)){
		$total_billings = count($list_billings);
		foreach($list_billings as $okey => $oval){
			$is_fullscore = $oval['is_fullscore'];
			$billing_type = (int) $oval['billing_type'];
			$billing_source_id = (int) $oval['billing_source_id'];
			if($billing_source_id == _BILLING_RESOURCE_F1_ID && $is_fullscore == 1 
				&& $billing_type == _BILLING_TYPE_MWF_ID){
				$start_date = strtotime('01-07-2024');
				$end_day = cal_days_in_month(CAL_GREGORIAN, 9, 2024);
				$end_date = strtotime(sprintf('%s-9-2024 23:59:59', $end_day));
				if($oval['deposit_date'] >= $start_date && $oval['deposit_date'] <= $end_date){
					$total_scores += 10;
				} else if($oval['deposit_date'] <= $start_date){
					$total_scores += floatval(7.5);
				} else {
					$total_scores += floatval($campaign_terms[$billing_type]);
				}
			} else {
				$total_scores += floatval($campaign_terms[$oval['billing_type']]);
			}
		}
	}
	$total_customer = 0;
	$target_customer = 1000;
	$total_trainning = 0;
	$total_text = 'Tổng: ';
	$target_text = 'Mục tiêu: ';
	if($deviceType == 'phone'){
		$total_text = '<span class="translate-px-2">&#8721;</span> ';
		$target_text = '<span class="fs-16 translate-px-2">&#8599;</span> ';
	}
	$html= '<div class="col-6 col-md-3 mb-2 mb-lg-0">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0 text-nowrap">Giao dịch</h5>
				<a href="javascript:void(0);" class="text-muted help_pop" title="Trợ giúp">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<div class="card-body">
				<div class="progress">
				  <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width:'.$clsISO->getPercent($total_billings,$billing_target).'%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">'.$clsISO->getPercent($total_billings,$billing_target).'%</div>
				</div>
				<div class="pt-2 d-flex align-items-center justify-content-between">
					<span class="d-inline-flex gap-1 text-muted text-nowrap">'.$total_text.$total_billings.' GD</span>
					<span class="d-inline-flex gap-1 text-warning text-nowrap">'.$target_text.$billing_target.' GD</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 mb-2 mb-lg-0">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0 text-nowrap">Thi đua Bắc Kinh</h5>
				<a href="javascript:void(0);" class="text-muted help_pop" title="Điểm đi bắc kinh 2024">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<div class="card-body">
				<div class="progress">
				  <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width:'.$clsISO->getPercent($total_scores, MAX_SCORE).'%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">'.$clsISO->getPercent($total_scores, MAX_SCORE).'%</div>
				</div>
				<div class="pt-2 d-flex align-items-center justify-content-between">
					<span class="d-inline-flex gap-1 text-muted text-nowrap">'.$total_text.$total_scores.' điểm</span>
					<span class="d-inline-flex gap-1 text-warning text-nowrap">'.$target_text.MAX_SCORE.' điểm</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0 text-nowrap">Học tập</h5>
				<a href="javascript:void(0);" class="text-muted help_pop" title="Học tập">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<div class="card-body">
				<div class="progress">
				  <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width:0%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="pt-2 d-flex align-items-center justify-content-between">
					<span class="d-inline-flex gap-1 text-muted text-nowrap">'.$total_text.$total_trainning.' điểm</span>
					<span class="d-inline-flex gap-1 text-warning text-nowrap">'.$target_text.$tranning_target.' điểm</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3">
		<div class="card">
			<div class="card-header d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-0">Khách hàng mới</h5>
				<a href="javascript:void(0);" class="text-muted help_pop" title="Trợ giúp">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<div class="card-body">
				<div class="progress">
				  <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width:0%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="pt-2 d-flex align-items-center justify-content-between">
					<span class="d-inline-flex gap-1 text-muted text-nowrap">'.$total_text.$total_customer.' KH</span>
					<span class="d-inline-flex gap-1 text-warning text-nowrap">'.$target_text.$target_customer.' KH</span>
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_get_home_news(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsNews = new News();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsNews', $clsNews);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$list_homes_news = $clsNews->getAll("`is_trash`=0 and `is_online`=1 
		and `post_type`='_news' and `domain_id`='"._NEWS_CAT_SITE_ID."' order by `reg_date` DESC limit 0,2");
	if(!empty($list_homes_news)){
		$arr_profile_cached = array();
		foreach($list_homes_news as $key => $val){
			$cat_id = $val['cat_id'];
			$user_id = $val['user_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_informatiion);
			if(isset($arr_profile_cached[$user_id])){
				$db_profile = $arr_profile_cached[$user_id];
			} else {
				$db_profile = $clsProfile->getProfile($user_id);
				$arr_profile_cached[$user_id] = $db_profile;
			}
			$list_homes_news[$key]['db_profile'] = $db_profile;
			#
			$total_liked = 0;
			$liked_json = $val['liked_json'];
			$liked_json = $clsISO->to_array_json($val['liked_json']);
			if(!empty($liked_json)){
				foreach($liked_json as $okey => $ids){
					if(is_array($ids) && !empty($ids)){
						$total_liked+= count($ids);
					}
				}
			}
			$list_homes_news[$key]['total_liked'] = $total_liked;
			$status_liked = $clsNews->checkLiked($val[$clsNews->pkey], $val);
			$list_homes_news[$key]['status_liked'] = $status_liked;
			$total_comments = $clsNews->getTotalComment($val[$clsNews->pkey]);
			$list_homes_news[$key]['total_comments'] = $total_comments;
			$list_homes_news[$key]['total_actions'] = $total_comments + $total_liked;
			if($cat_id == _NEWS_GRATITUDE_CAT_ID){
				$list_homes_news[$key]['staff'] = array();
				$list_homes_news[$key]['department'] = array();
				$staff_id = !empty($more_information['staff_id']) ? $more_information['staff_id'] : [];
				$department_id = !empty($more_information['department_id']) ? $more_information['department_id'] : [];
				if(!empty($staff_id)) {
					$lstStaff = $clsProfile->getAll("`profile_id` IN (".implode(",",$staff_id).")",$clsProfile->pkey.',full_name');
					$list_homes_news[$key]['staff'] = $lstStaff;
				}
				if(!empty($department_id)) {
					$lstDepartment = $clsProperty->getAllCache("`property_id` IN (".implode(",",$department_id).")",$clsProperty->pkey.",title");
					$list_homes_news[$key]['department'] = $lstDepartment;
				}
			}
		}
	}
	$smarty->assign('list_homes_news', $list_homes_news);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.home_news.tpl');
	$callback = '$(function(){
		if($(\'.awe__post-description:not(.collapsed)\').length){
			$(\'.awe__post-description:not(.collapsed)\').each((_i, _elem) => {
				var _height = $(_elem).outerHeight();
				if(_height > 200){
					$(_elem).addClass(\'collapsed\').append(\'<a class="awe__link-more">Xem thêm...</a>\');
				}
			});
		}
	});';
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function dashboard_load_agent_sell_mwf(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsStock = new Stock();
	$clsProperty = new Property();
	$html = '';
	$arr_block_miani = array(76,77,78);
	$arr_block_hawai = array(283,217,282);
	$list_items = $clsProperty->getCacheItems('_AGENCY');
	if(!empty($list_items)){ $ii = 0;
		foreach($list_items as $key => $val){
			$agency_id = $val[$clsProperty->pkey];
			$cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `agency_id`='{$agency_id}' 
				and `status_id`>0 and `block_id`='"._PROJECT_BLOCK_MWF_ID."'";
			if($val['property_id'] == _AGENCY_FH_ID){
				$cond.= " AND `status_id`='"._STOCK_STATUS_DQ_ID."'";
			}else{
				$cond.= " AND `status_id`='"._STOCK_STATUS_LOCK_ID."'";
			}
			$total_mwf_miami = $clsStock->countItem("{$cond} and `building_id` in (".implode(',', $arr_block_miani).")");
			$total_mwf_hawai = $clsStock->countItem("{$cond} and `building_id` in (".implode(',', $arr_block_hawai).")");
			if($total_mwf_miami + $total_mwf_hawai > 0){
				$html.= '<tr'.($agency_id==_AGENCY_FH_ID? ' class="bg-main text-white"':'').'>
					<td class="text-center">'.($ii+1).'</td>
					<td>'.$val['title'].'</td>
					<td class="text-center">'.$total_mwf_miami.'</td>
					<td class="text-center">'.$total_mwf_hawai.'</td>
				</tr>';
				++$ii;
			}
		}
	}
	$html.= '';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_open_full(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$tp = Input::post('tp', "load_billing_chart");
	$titlePage = ($tp == 'load_billing_chart') 
		? 'Biểu đồ tăng trưởng doanh số' 
		: 'Thống kê theo loại giao dịch';
	$smarty->assign('tp', $tp);
	$smarty->assign('titlePage', $titlePage);
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	$smarty->assign('list_years', $list_years);
	$smarty->assign('list_months', $list_months);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.full.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function dashboard_load_billing(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBilling', $clsBilling);
	###
	$call_from = Input::post('call_from', "");
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '30days');
	##
	$cond = "`is_trash`=0 AND `is_cancel`=0";
	if($call_from == '_DEPARTMENT'){
		$department_id = (int) Input::post('department_id', 0);
		$department_id = ($department_id > 0) ? $department_id : _DEPARTMENT_SALE_ID;
		$arr_staffs = $clsProfile->getProfileDep($department_id, 1, "all");
		$arr_staffs_ids = !empty($arr_staffs) ? array_keys($arr_staffs) : [];
		$cond.= " AND `staff_id` IN ('".implode('\',\'', $arr_staffs_ids)."')";
	} else if($call_from == '_dashboard'){
		$department_id = $oneProfile['department_id'];
		$arr_staffs = $clsProfile->getProfileDep($department_id, 1, "all");
		$arr_staffs_ids = !empty($arr_staffs) ? array_keys($arr_staffs) : [];
		$cond.= " AND `staff_id` IN ('".implode('\',\'', $arr_staffs_ids)."')";
	} else {
		$department_id = (int) $oneProfile['department_id'];
		$group_id = (int) Input::post('group_id', _PROFILE_DEFAULT_GROUP_ID);
		$oneGroup = $clsGroupProfile->getOne($group_id, "list_profile_id");
		$arr_profile_ids = $clsISO->getArrayByTextSlash($oneGroup['list_profile_id'], ",", []);
		if(!empty($arr_profile_ids)){
			$cond.= " AND `staff_id` in ('".implode('\',\'', $arr_profile_ids)."')";
		}		
	}
	##
	if($date_type == 'today'){
		$start_time = strtotime(date('d-m-Y'));
		$end_time = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	} else if($date_type == '7days'){
		$end_time = time();
		$start_time = strtotime('-7 days');
	} else if($date_type == '15days'){
		$end_time = time();
		$start_time = strtotime('-15 days');
	} else if($date_type == '30days'){
		$end_time = time();
		$start_time = strtotime('-30 days');
	} else {
		if($month > 0){
			$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			$start_time = strtotime(sprintf('01-%s-%s', $month, $year));
			$end_time = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
		} else {
			$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
			$start_time = strtotime(sprintf('01-01-%s', $year));
			$end_time = strtotime(sprintf('%s-%s-%s', $end_day, 12, $year));
		}
	}
	$cond.= " AND (`deposit_date` BETWEEN {$start_time} AND {$end_time})";
	#- Pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',10);
	$total_record = $clsBilling->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	$field = "{$clsBilling->pkey},`staff_id`,`billing_type`,`billing_source_id`,`contract_status_id`";
	$field.= ",`more_information`,`stock_code`,`totalgrand`,`deposit_date`,`project_id`";
	$list_billings = $clsBilling->getAll($cond." order by `reg_date` DESC".$limitCond, $field);
	if(!empty($list_billings)){
		$block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
		$billing_type_cached = $clsProperty->getArraySearchByKey("_BILLING_TYPE");
		$arr_projects_cached = $clsProject->getListProject();
		$arr_profile_cached = $clsProfile->getProfileCached();
		// $clsISO->print_pre($arr_projects_cached); die();
		foreach($list_billings as $key => $val){
			$staff_id = $val['staff_id'];
			$billing_type = (int) $val['billing_type'];
			$billing_source_id  = (int) $val['billing_source_id'];
			$contract_status_id = (int) $val['contract_status_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$project_id = (int) $val['project_id'];
			$block_id = (int) $core->get_field($more_information, "block_id", 0);
			###
			if($billing_type > 0){
				$list_billings[$key]['billing_type'] = $billing_type_cached[$billing_type]["title"];
			} else {
				$list_billings[$key]['billing_type'] = "";
			}
			###
			if($project_id > 0){
				$list_billings[$key]['project_name'] = $arr_projects_cached[$project_id]["code"];
			} else {
				$list_billings[$key]['project_name'] = "";
			}
			if($block_id > 0){
				$list_billings[$key]['block_name'] = $block_cached[$block_id]["title"];
			} else {
				$list_billings[$key]['block_name'] = "";
			}
			$oneStaff = $arr_profile_cached[$staff_id];
			$list_billings[$key]['oneStaff'] = $oneStaff;
			// ĐQ
			$is_executable = $billing_source_id == _BILLING_RESOURCE_F1_ID ? 1 : 0;
			$list_billings[$key]['is_executable'] = $is_executable;
		}
	}
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.billing.tpl');
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
	)); die();
}
function dashboard_load_sale_logs(){
	global $smarty,$core,$clsISO,$oneProfile,$dbconn,$profile_id,$oneProfile; 
	$clsLog = new Log();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBilling', $clsBilling);
	// $role_id = $oneProfile['role_id'];
	// $department_id = $oneProfile['department_id'];
	$arr_staffs = array();
	$TMP = $clsProfile->getProfileCached('active');
	if(!empty($TMP)){
		#- GĐ KD
		$is_dir_sale = $clsISO->checkPermissionGroup('SALE_DIRECTOR') ? 1 : 0;
		#- GĐ Vùng
		$is_regional_dir = $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') ? 1 : 0;
		foreach($TMP as $key => $val){
			$department_id = $val['department_id'];
			$list_department_id = $val['list_department_id'];
			if($is_regional_dir == 1){
				$department_arrs = $clsISO->getArrayByTextSlash($list_department_id);
				if(in_array($oneProfile['department_id'], $department_arrs)){
					$arr_staffs[$val["profile_id"]] = $val;
				}
			} else if($is_dir_sale == 1) {
				if($department_id == $oneProfile['department_id']){
					$arr_staffs[$val["profile_id"]] = $val;
				}
			}
		}
		unset($TMP);
	}
	$cond = "`user_id` in (".implode(',', array_keys($arr_staffs)).")";
	$limitCond = " limit 0,10";
	$list_logs = $clsLog->getAll("{$cond} order by `reg_date` DESC".$limitCond);
	if(!empty($list_logs)){
		$arr_profile_cached = array();
		foreach($list_logs as $key => $_oLog){
			$type = $_oLog['type'];
			$user_id = $_oLog['user_id'];
			$title = !empty($_oLog['title']) ? @strtok($_oLog['title'],'?') : "";
			$title = preg_replace('(Xem thông tin căn hộ|Tra cứu căn hộ)','', $title);
			if($type='view_stock'){
				$oStock = $clsLog->getStock($title);
				$stock_id = $oStock['stock_id'];
				$status_id = $oStock['status_id'];
				$agency_id = $oStock['agency_id'];
				$label = "";
				if($status_id== _STOCK_STATUS_DQ_ID && $agency_id==_AGENCY_FH_ID){
					$label = "<sup class=\"text-yellow\">ĐQ</sup>";
				}
				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')">'.$title.$label.'</a>';
			} else if($type=='search'){
				$oStock = $clsLog->getStock($title);
				$stock_id = $oStock['stock_id'];
				$status_id = $oStock['status_id'];
				$agency_id = $oStock['agency_id'];
				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')">'.$title.'</a>';
			}
			$html.= '<tr>
				<td data-label="Nhân viên"><strong>'.$clsProfile->getFullName($user_id, $arr_staffs[$user_id]).'</strong></td>
				<td data-label="Thời gian">'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'</td>
				<td data-label="Nội dung">'.$contentHTML.'</td>
			</tr>';
		}
	}else{
		$html= '<tr>
				<td colspan="3" class="_empty" >Danh sách trống!</td>
			</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();	
}
function dashboard_load_profile(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsProfile = new Profile();
	$department_id = (int) Input::post("department_id",0);
	##
	$html = "";
	// $tmp = $clsProfile->getAll("`is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
	// 	AND (`department_id`='{$department_id}' OR `list_department_id` like '%|{$department_id}|%')","{$clsProfile->pkey},`full_name`");
	$tmp = $clsProfile->getProfileDep($department_id, 1, "active");
	if(!empty($tmp)) {
		$html = '<option value="0">Chọn nhân viên</option>';
		foreach ($tmp as $key => $value) {
			$html .= '<option value="'.$value[$clsProfile->pkey].'">'.$value['full_name'].'</option>';
		}
	}
	echo $html; 
}
function dashboard_load_billing_chart(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '_month');
	$department = Input::post('department', '');
	$staff_id = (int) Input::post('profile_id', 0);
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	###
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `billing_type`<>'"._BILLING_TYPE_SOP_ID."'";	
	$cond_staff = "`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		AND `department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'";
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	if($staff_id == 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT'))){
		if($department != "") {
			if($department == "OTHER") {
				$list_dep_notin = array();
				$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_DEPARTMENT' 
					AND `parent_id`='"._DEPARTMENT_SALE_ID."'", $clsProperty->pkey);
				if(!empty($tmp)){
					foreach ($tmp as $key => $val) {
						$list_dep_notin[] = $val[$clsProperty->pkey];
					}
				}
				$cond.= " and `staff_id` in (
					select `{$clsProfile->pkey}` from ".$clsProfile->tbl." 
					where `department_id` NOT IN (".implode(',',$list_dep_notin)."))";
				$cond_staff.= " AND `department_id` NOT IN (".implode(',',$list_dep_notin).")";
			}else{
				$cond.= " and `staff_id` in (
					select `profile_id` from ".$clsProfile->tbl." 
					where (`department_id`='{$department}' or `list_department_id` like '%|{$department}|%'))";
				$cond_staff.= " AND (`department_id`='{$department}' or `list_department_id` like '%|{$department}|%')";
			}
		}	
		if(!empty($staff_id)) {
			$cond .= " AND `staff_id`='".$staff_id."'";
		}
	} else {
		$list_staffs = $clsProfile->getProfileDep($department_id, 1, "all");
		$arr_staff_ids = !empty($list_staffs) ? @array_keys($list_staffs) : [];
		$cond.= " AND `staff_id` in ('".implode('\',\'', $arr_staff_ids)."')";
	}
	$list_ranges = array();
	if($month == 0){
		$f = '%m/%Y';
		if(date('n') >= 5){
			$start_month = strtotime('first day of january this year 00:00:00');
			$to_month = time();
		} else {
			$to_month = time();
			$start_month = strtotime('-6 months', $to_month);
		}
		for($i = $start_month; $i <= $to_month; $i = strtotime('+1 month', $i)){
			$list_ranges[] = date('m/Y', $i);
		}
	} else {
		$f = '%d/%m/%Y';
		$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1; $i<= $number_day; $i++){
			$list_ranges[] = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
		}
	}
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['dataPointMaxWidth'] = 60;
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	if($staff_id == 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT'))) {
		$barChartData['axisY2'][] = array(
			'title' => 'SL giao dịch(GD)',
			'titleFontColor' => '#C00000',
			'titleFontSize' => '16',
			'lineColor' => '#C00000',
			'labelFontColor' => '#C00000',
			'tickColor' => '#C00000',
			'maximum' => 400
		);
		$barChartData['axisY2'][] = array(
			'title' => 'SL nhân viên(người)',
			'titleFontColor' => '#6d78ad',
			'titleFontSize' => '16',
			'lineColor' => '#6d78ad',
			'labelFontColor' => '#6d78ad',
			'tickColor' => '#6d78ad',
			'maximum' => 400
		);
	} else {
		$barChartData['axisY2'][] = array(
			'title' => 'SL giao dịch(GD)',
			'titleFontColor' => '#C00000',
			'titleFontSize' => '16',
			'lineColor' => '#C00000',
			'labelFontColor' => '#C00000',
			'tickColor' => '#C00000'
		);
	}
	foreach($list_ranges as $date){
		$tmp = explode('/', $date);
		$end_day = cal_days_in_month(CAL_GREGORIAN, $tmp[0], $tmp[1]);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $tmp[0], $tmp[1]));

		$total_billings = $total_sales = 0;
		$field = "{$clsBilling->pkey},`totalgrand`";
		$list_billings = $clsBilling->getAll("{$cond} and FROM_UNIXTIME(`deposit_date`,'{$f}')='{$date}'", $field);
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			foreach($list_billings as $key => $val){
				$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
				$total_sales += $totalgrand;
			}
			unset($list_billings);
		}
		#fake
		/*$total_sales *=2.5;$total_billings *=2.5;*/
		$total_staffs = $clsProfile->countItem("{$cond_staff} and `reg_date`<='{$end_date}'");
		$total_staffs = (int) $total_staffs + _TOTAL_MEMBER_BGD;
		$dataStaffPoints[] = array(
			'label'	=> sprintf('%s', $date),
			'y' => $total_staffs*1,
			'indexLabel' => (string) $total_staffs
		);
		$dataPoints[] = array(
			'label'	=> sprintf('%s', $date),
			'y'	=> $total_sales*1,
			'indexLabel' => $clsISO->shortNumber($total_sales)
		);
		$dataTotalPoints[] = array(
			'label'	=> sprintf('%s', $date),
			'y' => $total_billings*1,
			'indexLabel' => (string) $total_billings
		);
	}
	$barChartData['data'] = array(
		array(
			"type"    => "line",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		),
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataTotalPoints
		)
	);
	if($staff_id == 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT'))){
		$barChartData['data'][] = array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng nhân viên",
			"color"	 => "#6d78ad",
			"showInLegend" => true,
			"dataPoints"   => $dataStaffPoints
		);
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_draw_billing_chart(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id; 
	$clsLog = new Log();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', date('n'));
	$year = (int) Input::post('year', date('Y'));
	$department_id = $oneProfile['department_id'];
	###
	$cond = "`is_trash`=0 and status_id<>'"._STATUS_STAFF_OFF_ID."' AND `department_id`='{$department_id}'";
	$list_staffs = $clsProfile->getAll($cond, "{$clsProfile->pkey},full_name,first_name,last_name");
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	$cond = "`is_trash`=0 and `is_cancel`=0";	
	if($month > 0){
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$m}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	//$barChartData['zoomEnabled'] = true;
	//$barChartData['zoomType'] = "xy";
	$barChartData['animationEnabled'] = true;
	$barChartData['dataPointMaxWidth'] = 20;
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1,
		// 'maximum' => 300
	);
	$barChartData['axisY2'][] = array(
		'title' => 'SL giao dịch(GD)',
		'titleFontColor' => '#C00000',
		'titleFontSize' => '16',
		'lineColor' => '#C00000',
		'labelFontColor' => '#C00000',
		'tickColor' => '#C00000',
		'maximum' => 100
	);
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		$total_billings = $total_sales = 0;
		$field = "{$clsBilling->pkey},totalgrand";
		$list_billings = $clsBilling->getAll("{$cond} and `staff_id`='{$staff_id}'", $field);
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			foreach($list_billings as $key => $val){
				$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
				$total_sales += $totalgrand;
			}
			unset($list_billings);
		}
		$dataPoints[] = array(
			'label'	=> $clsProfile->getLastName($staff_id, $val, false),
			'y'	=> $total_sales*1,
			'indexLabel' => $clsISO->shortNumber($total_sales)
		);
		$dataTotalPoints[] = array(
			'label'	=> $clsProfile->getLastName($staff_id, $val, false),
			'y' => $total_billings*1,
			'indexLabel' => (string) $total_billings
		);
	}
	$columns = @array_column($dataPoints, 'y');
	@array_multisort($columns, SORT_DESC, $dataPoints);
	$columns = @array_column($dataTotalPoints, 'y');
	@array_multisort($columns, SORT_DESC, $dataTotalPoints);
	$barChartData['data'] = array(
		array(
			"type"    => "spline",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		), array(
			"type"  => "spline",
			"indexLabel" => "{y}",
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
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_load_billing_type(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType; 
	$clsLog = new Log();
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$html = "";
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$cond = "`is_trash`=0 and `staff_id` in (
		select `profile_id` from ".$clsProfile->tbl." 
		where (`department_id`='{$department_id}' 
			or `list_department_id` like '%|{$department_id}|%'
		))";
	if($month > 0){
		$format = "%m/%Y";
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month),$year);
		$start_date = strtotime(sprintf('01-%s-%s', $month, $year));
		$number_day = cal_days_in_month(CAL_GREGORIAN,$month, $year);
		$to_date = strtotime(sprintf('%s-%s-%s', $number_day,$month,$year));
	} else {
		$format = "%Y";
		$date_my = sprintf('%s', $year);
		$start_date = strtotime('01-01-2024');
		$number_day = cal_days_in_month(CAL_GREGORIAN,12,$year);
		$to_date = strtotime(sprintf('%s-%s-%s', $number_day,12,$year));
	}
	$cond.= " and FROM_UNIXTIME(`deposit_date`,'{$format}')='{$date_my}'";
	###
	$list_billing_type = $clsProperty->getCacheItems('_BILLING_TYPE');
	if(!empty($list_billing_type)){
		foreach($list_billing_type as $key => $val){
			$prop_id = $val[$clsProperty->pkey];
			$title = $val['title'];
			if($deviceType=='phone'){
				$title =  !empty($val['title_vn']) ? $val['title_vn'] : $val['title'];
			}
			$total_sales = $clsBilling->sumItem("totalgrand", "{$cond} and `billing_type`='{$prop_id}'");
			$total_billings = $clsBilling->countItem("{$cond} and `billing_type`='{$prop_id}'");
			$html.= '<div class="report-list-item col-6 mb-2">
				<div class="d-flex align-items-start">
					<div class="report-list-icon shadow-sm p-2 rounded-2 me-2">
						<i class="bx '.$val['image'].'"></i>
					</div>
					<div class="w-100 d-flex flex-column flex-wrap">
						<span class="text-muted text-nowrap">'.$title.'</span>
						<div class="d-flex gap-1 justify-content-between align-items-center">
							<h5 class="mb-0 fs-6">
								<a class="text-main" href="'.$clsISO->getLink('billing').'?billing_type='.$prop_id.'&start_date='.$start_date.'&to_date='.$to_date.'" title="Xem chi tiết">'.$clsISO->shortNumber($total_sales).'</a>
							</h5>
							<div class="fs-6 text-muted">
								<a class="text-warning" href="'.$clsISO->getLink('billing').'?billing_type='.$prop_id.'&start_date='.$start_date.'&to_date='.$to_date.'" title="Xem chi tiết">'.$total_billings.'</a>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_staffs(){
	global $smarty,$core,$clsISO,$oneProfile, $profile_id, $oneProfile; 
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsShare = new Share();
	##
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	#- GĐ KD
	$is_dir_sale = $clsISO->checkPermissionGroup('SALE_DIRECTOR') ? 1 : 0;
	#- GĐ Vùng
	$is_regional_dir = $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') ? 1 : 0;
	##
	$list_staffs = array();
	$TMP = $clsProfile->getProfileCached('active');
	if(!empty($TMP)){
		foreach($TMP as $key => $val){
			$dep_id = $val['department_id'];
			$list_department_id = $val['list_department_id'];
			if($is_regional_dir == 1){
				$department_arrs = $clsISO->getArrayByTextSlash($list_department_id, ",", []);
				if(in_array($department_id, $department_arrs)){
					$val['total_sales'] = 0;
					$val['total_compete'] = 0;
					$val['total_billings'] = 0;
					$list_staffs[$key] = $val;
				}
			} else if($is_dir_sale == 1) {
				if($dep_id == $department_id){
					$val['total_sales'] = 0;
					$val['total_compete'] = 0;
					$val['total_billings'] = 0;
					$list_staffs[$key] = $val;
				}
			}
		}
		unset($TMP);
	}
	###
	if($month > 0){
		$format = "%m/%Y";
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	} else {
		$format = "%Y";
		$date_my = sprintf('%s', $year);
	}
	###
	// $dbconn->debug = true;
	$html = ""; $total_staffs = 0;
	if(!empty($list_staffs)){
		$total_staffs = count($list_staffs);
		$sql_query = "`is_trash`=0 AND `is_cancel`=0".($year <= 2025 ? " AND `department_id`='{$department_id}'" : "");
		$sql_query.= " AND `staff_id` IN (".implode(',', array_keys($list_staffs)).") AND FROM_UNIXTIME(`deposit_date`,'{$format}')='{$date_my}'";
		// $clsISO->print_pre($sql_query); die();
		$billings_arrs = $clsBilling->getAll($sql_query, "{$clsBilling->pkey},`totalgrand`,`realized_sales_compete`,`staff_id`");
		if(!empty($billings_arrs)){
			foreach($billings_arrs as $key => $val){
				$staff_id = $val['staff_id'];
				$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
				$list_staffs[$staff_id]['total_billings'] += 1;
				$list_staffs[$staff_id]['total_sales'] += $totalgrand;
				$list_staffs[$staff_id]['total_compete'] += $clsISO->convertToNumber($val['realized_sales_compete']);
			}
			unset($billings_arrs);
		}
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			// Tổng DS & GD
			$total_sales = $val['total_sales'];
			$total_billings = $val['total_billings'];
			// Tiếp khách
			$total_share = $clsShare->countItem("`share_type`='share' and `user_id`='{$staff_id}' 
				and FROM_UNIXTIME(`reg_date`,'{$format}')='{$date_my}'");
			$list_staffs[$key]['cond'] = $cond;
			$list_staffs[$key]['total_sales'] = $total_sales;
			$list_staffs[$key]['total_billings'] = $total_billings;
			$list_staffs[$key]['total_share'] = $total_share;
		}
		$arr_total_sales = array_column($list_staffs, "total_sales");
		array_multisort($arr_total_sales, SORT_DESC, $list_staffs);
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$html.= '<tr>
				<td><strong>'.$clsProfile->getFullName($staff_id, $val).'</strong></td>
				<td class="text-center">'.$val['total_billings'].'</td>
				<td class="text-left fw-bold text-main">'.$clsISO->shortNumber($val['total_sales']).'</td>
				<td class="text-left fw-bold text-warning">'.$clsISO->shortNumber($val['total_compete']).'</td>
			</tr>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_staffs' => $total_staffs,
		'callback' => '$(\'.total_staffs\').text(respJson.total_staffs);'
	)); die();
}
function dashboard_load_report_worktime(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	$clsProfile = new Profile();
	$clsWorktime = new Worktime();
	$clsProperty = new Property();
	#
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$sort_type = Input::post('sort_type');
	$month = Input::post('month', date('m/Y'));
	$tmp = explode('/', $month);
	$curr_month = $tmp[0];
	$curr_year  = $tmp[1];
	$start_date = '01-'.$curr_month.'-'.$curr_year;
	$end_date = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year).'-'.$curr_month.'-'.$curr_year;
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	#
	$tmp = $dbconn->getAll("select `t1`.* from {$clsWorktime->tbl} as `t1` 
		where `t1`.`is_trash`=0 and `t1`.`department_id`='{$department_id}' 
		and (`t1`.`worktime_date` between '{$start_time}' AND '{$end_time}')");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$department_id = $val['department_id'];
			$content_arrs = !empty($val['content']) 
				? json_decode(html_entity_decode($val['content']), true) : array();
			$list_staffs = array();
			if(!empty($content_arrs)){
				foreach($content_arrs as $staff_id => $staff_info){
					if(isset($list_worktimes[$department_id][$staff_id])){
						$list_worktimes[$department_id][$staff_id]['total_items'] += 1;
						$list_worktimes[$department_id][$staff_id]['list_items'][] = array(
							'staff_id' => $staff_id,
							'worktime_id' => $val[$clsWorktime->pkey],
							'worktime_date' => $val['worktime_date'],
							'staff_info' => $staff_info
						);
					} else {
						$list_worktimes[$department_id][$staff_id]['total_items'] = 1;
						$list_worktimes[$department_id][$staff_id]['list_items'][] = array(
							'staff_id' => $staff_id,
							'worktime_id' => $val[$clsWorktime->pkey],
							'worktime_date' => $val['worktime_date'],
							'staff_info' => $staff_info
						);
					}
				}
			}
		}
	}
	$smarty->assign('sort_type', $sort_type);
	$smarty->assign('list_worktimes', $list_worktimes);
	$smarty->assign('list_group_worktimes', $list_group_worktimes);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.worktime.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_month(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$oneProfile;
	####
	$date_type = Input::post('date_type', '_month');
	$year = (int) Input::post('year', date('Y'));
	$quarter = (int) Input::post('quarter', 0);
	if($date_type == '_month'){
		$html_options = sprintf('<option value="0">%s</option>', 'Tháng');
		if(!empty($quarter)) {
			if($quarter == 1) {
				$start_month = 1;
				$end_month = 3;
			}else if($quarter == 2) {
				$start_month = 4;
				$end_month = 6;
			}else if($quarter == 3) {
				$start_month = 7;
				$end_month = 9;
			}else if($quarter == 4) {
				$start_month = 10;
				$end_month = 12;
			}
		}else{
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
		}
		for($i=$start_month; $i<= $end_month; $i++){
			$html_options.= sprintf('<option value="%s">Tháng %s</option>', $i, $i);
		}
	} else if($date_type=='_half_year'){
		$html_options = sprintf('<option value="0">%s</option>', 'Chọn mốc');
		$html_options.= sprintf('<option value="1">%s</option>', 'Nửa đầu năm');
		$html_options.= sprintf('<option value="2">%s</option>', 'Nửa cuối năm');
	} else {
		$html_options = "";
		if($date_type == '_quater' || $date_type== '_quarter'){
			$max_loop = 4; 
			if($year == (int) date('Y')){
				if(date('n') >=1 && date('n') <=3) $max_loop = 1;
				if(date('n') >=4 && date('n') <=6) $max_loop = 2;
				if(date('n') >=7 && date('n') <=9) $max_loop = 3;
				if(date('n') >=10 && date('n') <=12) $max_loop = 4;
			}
			$html_options = sprintf('<option value="0">%s</option>', 'Quý');
			for($i=1; $i<=$max_loop; $i++){
				$html_options.= sprintf('<option value="%s">Quý %s</option>', $i, $i);
			}
		}
	}
	// Return
	echo $html_options; die();
}
function dashboard_load_report_share(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id,$deviceType;
	$clsCache = new Cache();
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	if($clsCache->has('_dept_staff_'.$department_id.'_cached')){
		$list_staffs = $clsCache->get('_dept_staff_'.$department_id.'_cached');
		// $clsISO->print_pre($list_staffs); die();
	} else {
		$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
			and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $field);
		$clsCache->put('_dept_staff_'.$department_id.'_cached', $list_staffs);
	}						
	$time_ranges = array(
		'this_week' => array(
			'start_date' => strtotime('monday this week 00:00'),
			'to_date' => strtotime('sunday this week 23:59')
		),
		'prev_week' => array(
			'start_date' => strtotime('monday last week 00:00'),
			'to_date' => strtotime('sunday last week 23:59')
		),
		'this_month' => array(
			'start_date' => strtotime("first day of this month 00:00"),
			'to_date' => strtotime("last day of this month 23:59")
		),
		'prev_month' => array(
			'start_date' => strtotime('first day of last month 00:00'),
			'to_date' => strtotime('last day of last month 23:59')
		)
	);
	$html = '';
	foreach($list_staffs as $key => $val){
		$staff_id = $val[$clsProfile->pkey];
		$oProfile = $clsProfile->getOne($staff_id, "`full_name`,`first_name`,`last_name`,`avatar`");
		$html.= '<tr>
			<td class="text-left text-nowrap">
				'.$clsProfile->getIndentityV6($staff_id, $oProfile).'
			</td>';
			foreach($time_ranges as $okey => $oval){
				$start_date = $oval['start_date'];
				$to_date = $oval['to_date'];
				$total_share = $clsShare->countItem("`share_type`='share' and `user_id`='{$staff_id}' 
				and (`reg_date` between '{$start_date}' AND '{$to_date}')");
				$html.= '<td class="text-center">'.$total_share.'</td>';
			}	
		$html.= '</tr>';
	}
	// Return
	$callback = 'setTimeout(() => {
		let verticalExample = document.getElementById(\'report_share\');
		if (verticalExample) {
			let ps = new PerfectScrollbar(verticalExample, {
				wheelPropagation: false,
				suppressScrollX: true,
				suppressScrollY: false
			});
		}
	},500);';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function dashboard_top_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType, $clsConfiguration;
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$data =  array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisY']['labelFormatter'] = 1;
	###
	$cond = "";
	if($month == 0){
		$cond.= "FROM_UNIXTIME(`t1`.`deposit_date`,'%Y')='{$year}'";
	} else {
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= "FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$m}'";
	}
	$cache_name = sprintf('_top_staff_billings_%s_%s', $month, $year);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	if($clsCache->has($cache_name)){
		$list_billings = $clsCache->get($cache_name);
		// $clsISO->print_pre($list_billings); die();
	} else {
		$field = "SUM(`t1`.`totalgrand`) AS `totalgrand`,count(*) as `total_billing`,`t2`.`profile_id`";
		$list_billings = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` and `t2`.`profile_id` NOT IN (".implode(',',_PROFILE_BLD_ID).") WHERE {$cond} and `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t2`.`is_active`='1' GROUP BY `t1`.`staff_id` ORDER BY `totalgrand` DESC LIMIT 0,10");
		$clsCache->put($cache_name, $list_billings, 60*60); // Cache nó 30p' một
	}
	if(!empty($list_billings)){
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_billings as $key => $val){
			$staff_id = $val['profile_id'];
			$oProfile = $arr_profile_cached[$staff_id];
			$staff_name = $clsProfile->getFullName($staff_id, $oProfile);
			$dataPoints[] = array(
				'label'	=> $staff_name,
				'y'	=> $val['totalgrand']*1,
				'total' => $val['total_billing'],
				'indexLabel' => $clsISO->shortNumber($val['totalgrand'])
			);
		}
	}
	$data['type'] = 'bar';
	$data['color'] = $clsConfiguration->getValue('BrandColor');
	$data['indexLabel'] = '{y}';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = 'Số lượng GD: {total}<br /> Doanh số: {y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#ffffff';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		//'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_chart_billing_type(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $clsConfiguration;
	$clsLog = new Log();
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$is_bigger = (int) Input::post("is_bigger", 0);
	$date_type = Input::post('date_type', "_month");
	###
	$data =  array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisY']['labelFormatter'] = 1;
	$cond = "`is_trash`=0 and `is_cancel`=0";
	if($month > 0){
		$format = "%m/%Y";
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	} else {
		$format = "%Y";
		$date_my = sprintf('%s', $year);
	}
	$cond.= " and FROM_UNIXTIME(`deposit_date`,'{$format}')='{$date_my}'";
	###
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	$list_billing_type = $clsProperty->getCacheItems('_BILLING_TYPE');
	// $clsISO->print_pre($list_billing_type); die();
	if(!empty($list_billing_type)){
		foreach($list_billing_type as $key => $val){
			$prop_id = $val[$clsProperty->pkey];
			if(!in_array($prop_id, array(_BILLING_TYPE_LEASING_ID, _BILLING_TYPE_TRANSFER_ID))){
				$total_billings = $total_sales = 0; // Start
				$tmp = $clsBilling->getAll("{$cond} and `billing_type`='{$prop_id}'", "totalgrand");
				if(!empty($tmp)){
					$total_billings = count($tmp);
					foreach($tmp as $okey => $oval){
						$totalgrand = $oval['totalgrand'];
						$total_sales += $clsISO->convertToNumber($totalgrand);
					}
					unset($tmp);
				}
				$total_sales *= 2; $total_billings *=2;
				$dataPoints[] = array(
					'label'	=> $val['title'],
					'y'	=> $total_sales*1,
					'total' => (string) $total_billings,
					'indexLabel' => $clsISO->shortNumber($total_sales)
				);
			}
		}
	}
	$data['type'] = 'column';
	$data['color'] = $clsConfiguration->getValue('BrandColor');
	$data['indexLabel'] = '{y}';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = '{label}<br /> Số lượng: {total} GD <br /> Doanh số: {y}';
	$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#233446';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		//'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_load_department_billing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id,$clsConfiguration,$deviceType;
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsBillingSale = new BillingSale();
	###
	$more = array();
	$uid = $clsISO->getUniqid();
	$disp = Input::post('disp', "chart");
	$tp = Input::get('tp', 'department');
	//$sql_query = "`is_trash`=0 AND `property_type`='_DEPARTMENT'";
	//$pro_field = "{$clsProperty->pkey},`title`,`more_information`";
	//$tmp = $clsProperty->getAll("{$sql_query} AND `parent_id`='"._DEPARTMENT_SALE_ID."' 
	// AND `{$clsProperty->pkey}`<>'"._DEPARTMENT_DEVELOP_ID."'", $pro_field);	
	$tmp = $list_departments = array();
	$clsProperty->makeList(_DEPARTMENT_SALE_ID, "_DEPARTMENT", $tmp);
	if(!empty($tmp)){
		$arr_profile_cached = $clsProfile->getProfileCached("active");
		// $clsISO->print_pre($arr_profile_cached); die();
		// Tập id các KHỐI (is_business_area=1) — phòng leaf KD là node có parent là 1 khối.
		// Lọc theo cấu trúc cây (không hardcode id) → tự loại root "Phòng Kinh doanh" & tự thích ứng khi thêm khối/phòng.
		$khoi_ids = array();
		foreach($tmp as $_k => $_v){
			$_mi = $clsISO->to_array_json($_v['more_information']);
			if((int) $core->get_field($_mi, 'is_business_area', 0) == 1){
				$khoi_ids[(int) $_v[$clsProperty->pkey]] = 1;
			}
		}
		foreach($tmp as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$head_of_dep_id = $core->get_field($more_information, 'head_of_dep_id', 0);
			#KPI
			if(isset($more_information["target_sales"])) {
				$target_dep_sales = $core->get_field($more_information, "target_sales", []);
				$val["target_dep_sales"] = $target_dep_sales;
			}
			$oProfile = array();
			if((int) $head_of_dep_id > 0){
				$oProfile = $arr_profile_cached[$head_of_dep_id];
				// $clsISO->print_pre($oProfile); die();
			}
			$val['sales_director_profile'] = $oProfile;
			$val['more_information'] = $more_information;
			if($tp == 'department'){
				if($core->get_field($more_information, "is_business_area", 0) == 1){
					// Break
				} else if(isset($khoi_ids[(int) $val['parent_id']])){
					// chỉ lấy leaf PHÒNG KD (parent là 1 khối) — bỏ root "Phòng Kinh doanh"
					$val['arr_deps'] = array();
					$list_departments[] = $val;
				}
			} else if($tp == 'regional') {
				$is_regional_row = ($core->get_field($more_information, "is_business_area", 0) == 1);
				// Ban Phát triển đối tác nằm ngang cấp khối nhưng không gắn cờ is_business_area,
				// nên mặc định rơi khỏi bảng xếp hạng khối dù đang tạo doanh số lớn nhất.
				// Xếp hạng theo KHỐI vẫn tính ban này; chỉ bảng xếp hạng theo PHÒNG mới loại.
				if(!$is_regional_row && (int) $val[$clsProperty->pkey] == _DEPARTMENT_DEVELOP_ID){
					$is_regional_row = true;
				}
				if($is_regional_row){
					$arr_deps = array();
					$pro_field = "{$clsProperty->pkey},`title`,`more_information`";
					$sql_query = "`is_trash`=0 AND `property_type`='_DEPARTMENT'";
					$list_deps = $clsProperty->getAll("{$sql_query} AND `parent_id`='{$val[$clsProperty->pkey]}'", $pro_field);
					if(!empty($list_deps)){
						foreach($list_deps as $okey => $oval){
							$arr_deps[] = $oval[$clsProperty->pkey];
						}
						unset($list_deps);
					}
					$val['arr_deps'] = $arr_deps;
					$list_departments[] = $val;
				}
			}
		}
	}
	if($disp == 'chart'){
		$month = (int) Input::post('month', 0);
		$year = (int) Input::post('year', date('Y'));
		if($month == 0){
			$date_my = sprintf('%s', $year);
		} else {
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		}
		$sql_time = " AND ((FROM_UNIXTIME(`t1`.`agree_date`,'%Y')='{$date_my}' AND `t1`.`agree_date`>0 ) OR (FROM_UNIXTIME(`t1`.`contract_date`,'%Y')='{$date_my}' AND `t1`.`contract_date`>0 ))" ;
		
		$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
		###
		$data = array();
		$dataPoints = $dataTotalPoints = $barChartData = array();
		$barChartData['animationEnabled'] = true;
		$barChartData['axisX'] = array(
			'labelAngle' => -90,
			'interval' => 1
		);
		$barChartData['axisY'] = array(
			'title' => 'Doanh số bán hàng',
			'titleFontColor' => '#1d6a01',
			'labelFontColor' => '#1d6a01',
			'lineColor' => '#1d6a01',
			'tickColor' => '#1d6a01',
			'labelFormatter' => 1,
		);
		$barChartData['axisY2'] = array(
			'title' => 'Số lượng giao dịch',
			'titleFontColor' => 'rgb(138,12,33)',
			'labelFontColor' => 'rgb(138,12,33)',
			'lineColor' => 'rgb(138,12,33)',
			'tickColor' => 'rgb(138,12,33)'
		);
		$list_staffs_notin = array();
		if(!empty($list_departments)){
			foreach($list_departments as $key => $val){
				$department_id = $val[$clsProperty->pkey];
				if(!empty($list_staffs_in)){
					foreach($list_staffs_in as $staff){
						$list_staffs_notin[] = $staff[$clsProfile->pkey];
					}
				}
                $arr_deps_chart = !empty($val['arr_deps']) ? $val['arr_deps'] : array();
				$cond_dep_chart = "`t2`.`department_id`='{$department_id}'"
					.(!empty($arr_deps_chart) ? " OR `t2`.`department_id` IN (".implode(',', $arr_deps_chart).")" : "");
				$field = "SUM(`tbl_bl`.`share_value`) AS `total_sales`,COUNT(DISTINCT `t1`.`billing_id`) AS `total_billings`";
				$tmp = $dbconn->getRow("SELECT {$field}
				FROM `{$clsBillingSale->tbl}` AS `tbl_bl`
				INNER JOIN {$clsBilling->tbl} AS `t1`
					ON `t1`.`billing_id`=`tbl_bl`.`billing_id`
				INNER JOIN {$clsProfile->tbl} AS `t2`
					ON `t2`.`profile_id`=`tbl_bl`.`staff_id`
				WHERE `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t2`.`is_trash`=0
				 AND ({$cond_dep_chart})".$sql_time);
				
				$total_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;
				$total_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;
				$dataPoints[] = array(
					'label' => $val['title'],
					'y' => $total_sales * 1,
					'indexLabel' => $clsISO->shortNumber($total_sales*1)
				);
				$dataTotalPoints[] = array(
					'label' => $val['title'],
					'y' => $total_billings * 1
				);
			}
		}
		// OTHER
		$list_staffs_notin[] = _PROFILE_PARTNER_ID;
		// Ban PTĐT đã có cột riêng ở bảng xếp hạng theo khối nên vẫn trừ ra khỏi nhóm "Khác"
		// để không cộng trùng; bảng theo phòng thì ban này không được tính.
		$field = "sum(`t1`.`realized_sales_compete`) as `total_sales`,count(1) as `total_billings`";
		$sql_other = "select sum(`tbl_bl`.`share_value`) as `total_sales`,count(distinct `t1`.`billing_id`) as `total_billings`
				from `{$clsBillingSale->tbl}` as `tbl_bl`
				inner join {$clsBilling->tbl} as `t1` on `t1`.`billing_id`=`tbl_bl`.`billing_id`
				inner join {$clsProfile->tbl} as `t2` on `t2`.`profile_id`=`tbl_bl`.`staff_id`
				where `tbl_bl`.`staff_id` not in (".implode(',',$list_staffs_notin).")
				and `t1`.`is_trash`=0 and `t1`.`is_cancel`=0 and `t2`.`is_trash`=0
				and (`t2`.`list_department_id` NOT LIKE '%|"._DEPARTMENT_DEVELOP_ID."|%')".$sql_time;
		$tmp = $dbconn->getRow($sql_other);
		$total_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;
		$total_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;
		$dataPoints[] = array(
			'label' => 'Khác',
			'y' => $total_sales * 1,
			'indexLabel' => $clsISO->shortNumber($total_sales*1)
		);
		$dataTotalPoints[] = array(
			'label' => 'Khác',
			'y' => $total_billings * 1
		);
		// PARTNER
		$field = "sum(`t1`.`realized_sales_compete`) as `total_sales`,count(*) as `total_billings`";
		$sql_partner = "select sum(`tbl_bl`.`share_value`) as `total_sales`,count(distinct `t1`.`billing_id`) as `total_billings`
				from `{$clsBillingSale->tbl}` as `tbl_bl`
				inner join {$clsBilling->tbl} as `t1` on `t1`.`billing_id`=`tbl_bl`.`billing_id`
				where `tbl_bl`.`staff_id`='"._PROFILE_PARTNER_ID."' and `t1`.`is_trash`=0 and `t1`.`is_cancel`=0".$sql_time;
		$tmp = $dbconn->getRow($sql_partner);
		$total_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;
		$total_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;
		$dataPoints[] = array(
			'label' => 'Đối tác',
			'y' => $total_sales * 1,
			'indexLabel' => $clsISO->shortNumber($total_sales*1)
		);
		$dataTotalPoints[] = array(
			'label' => 'Đối tác',
			'y' => $total_billings * 1
		);
		// $total_sales_arrs = @array_column($dataPoints, 'y');
		// @array_multisort($total_sales_arrs, SORT_DESC, $dataPoints);
		$barChartData['data'] = array(
			array(
				"type"    => "column",
				"indexLabel" => "{y}",
				"indexLabelFontColor" => "rgb(138,12,33)",
				"color"	 => "#1d6a01",
				"name"    => "Doanh số bán hàng",
				"showInLegend"    => true,
				"dataPoints"    => $dataPoints
			),
			array(
				"type"  => "column",
				"indexLabel" => "{y}",
				"axisYType" => "secondary",
				"name"	=> "Số lượng giao dịch",
				"color"	 => "rgb(138,12,33)",
				"showInLegend" => true,
				"dataPoints"   => $dataTotalPoints
			)
		);
		$more = array(
			'drawchart' => '1',
			'multichart' => 1,
			'barChartData' => $barChartData
		);
	} else {
		$date_type = Input::post('date_type', "_month");
		if($date_type == '_year'){
			$sql_time = " AND ((FROM_UNIXTIME(`t1`.`agree_date`,'%Y')='".date('Y')."') OR (FROM_UNIXTIME(`t1`.`contract_date`,'%Y')='".date('Y')."'))" ;
			#KPI
			for($i = 1; $i< 12; $i++) {
				$arr_nY[] = $i."_".date('Y');
			}
		} else if($date_type=='_quarter'){
			$tmp = $clsISO->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
			$start_date = strtotime($tmp['start']." 00:00:00");
			$due_date = strtotime($tmp['end']." 23:59:59");
			$sql_time = " AND ((`t1`.`agree_date` BETWEEN {$start_date} AND {$due_date}) OR (`t1`.`contract_date` BETWEEN {$start_date} AND {$due_date}))" ;		
			#KPI
			for($i = date("n",$start_date); $i< date("n",$due_date); $i++) {
				$arr_nY[] = $i."_".date('Y');
			}
		} else if($date_type == '_month'){
			$sql_time = " AND ((FROM_UNIXTIME(`t1`.`agree_date`,'%m/%Y')='".date('m/Y')."') OR (FROM_UNIXTIME(`t1`.`contract_date`,'%m/%Y')='".date('m/Y')."'))" ;
			#KPI
			$arr_nY[] = date("n_Y");
		}
		if(!empty($list_departments)){
			$total_dep_billings = $total_dep_sales = 0;
			$current_pos = 0; $arr_departments = array();
            $clsBillingSale = new BillingSale();
			foreach($list_departments as $key => $val){
				$arr_deps = $val['arr_deps'];
				$department_id = $val[$clsProperty->pkey];
				if(in_array($department_id, _DEPARTMENT_IGNORES_ARRAY)) continue;
                $field = "SUM(`tbl_bl`.`share_value`) AS `total_sales`,COUNT(DISTINCT `t1`.`billing_id`) AS `total_billings`";
				$tmp = $dbconn->getRow("SELECT {$field}
					FROM `{$clsBillingSale->tbl}` AS `tbl_bl`
					INNER JOIN {$clsBilling->tbl} AS `t1`
						ON `t1`.`billing_id`=`tbl_bl`.`billing_id`
					INNER JOIN {$clsProfile->tbl} AS `t2`
						ON `t2`.`profile_id`=`tbl_bl`.`staff_id`
					WHERE `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t2`.`is_trash`=0
					AND (`t2`.`department_id`='{$department_id}'".(!empty($arr_deps)? " OR `t2`.`department_id` IN (".implode(",", $arr_deps).")" : "" ).")".$sql_time);
				$total_sales = $total_billings = 0; // CREATE INDEX idx_name ON orders(user_id, created_at);
				if(!empty($tmp)){
					$total_sales = $tmp['total_sales'];
					$total_billings = $tmp['total_billings'];
				}
				$arr_departments[$department_id] = $val;
				$arr_departments[$department_id]['total_sales'] = $total_sales;
				$arr_departments[$department_id]['total_billings'] = $total_billings;
				$total_dep_sales += $total_sales;
				$total_dep_billings += (int) $total_billings;
				#KPI
				if(!empty($arr_nY)) {
					$quantity = $amount = 0;
					foreach ($arr_nY as $nY) {
						if(!empty($val["target_dep_sales"]) && !empty($val["target_dep_sales"][$nY])) {
							$target_dep_sales = $core->get_field($val["target_dep_sales"], $nY, array(
								'target_type' => 'all_month',
								'quantity' => 0, 
								'amount' => 0
							));
							$quantity += (float)$target_dep_sales["quantity"];
							$amount += (float)$target_dep_sales["amount"];
						}
					}					
					$arr_departments[$department_id]["target_dep_sales"] = [
						'quantity' => $quantity, 
						'amount' => $amount
					];
				}
			}
			//$tmp = array();
			//if(!empty($arr_departments) && array_key_exists(_DEPARTMENT_DEVELOP_ID, $arr_departments)){
			//	$tmp = $arr_departments[_DEPARTMENT_DEVELOP_ID];
			//	unset($arr_departments[_DEPARTMENT_DEVELOP_ID]);
			//}
			$total_billings_arrs = array_column($arr_departments, "total_sales");
			array_multisort($total_billings_arrs, SORT_DESC, $arr_departments);
			#
			$ii = 1;
			$cls_rank = ($deviceType == 'phone') ? "" : "mr-2";
			//$clsISO->print_pre($arr_departments);die;
			foreach($arr_departments as $okey => $oval){
				$stt = $ii;
				if($ii == 1) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-1.png">';
				if($ii == 2) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-2f.png">';
				if($ii == 3) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-3f.png">';
				$department_id = $oval[$clsProperty->pkey];
				$oProfile = $oval['sales_director_profile'];
				$prof_information = $oProfile['more_information'];
				$prof_information = $clsISO->to_array_json($prof_information);
				$more_information = $oval['more_information'];
				$head_of_dep_id = $core->get_field($more_information, 'head_of_dep_id', 0);
				$html.= '<div class="d-flex align-items-center justify-content-between py-1/5 px-2 bg-white-100 rounded-2 mb-1">
					<div class="d-flex gap-1 align-items-center">
						<div class="'.$cls_rank.' w-px-30 text-fs-16 algin-center text-center text-white" 
							style="min-width:30px">'.$stt.'</div>
						<img class="avatar avatar-xs rounded-pill" src="'.$clsProfile->getAvatar($head_of_dep_id, $oProfile).'" />
						<div class="d-flex text-white flex-column gap-0">
							'.($tp == 'department' ? '<div class="text-fs-14 fw-bold text-nowrap">'.$oval['title'].'</div>
							<h4 class="text-fs-11 mb-0">GĐKD: '.$clsProfile->getFullName($head_of_dep_id, $oProfile).'</h4>' : '
							<div class="text-fs-14 fw-bold text-nowrap">'.$oval['title'].'</div>
							<h4 class="text-fs-11 mb-0">GĐK: '.$clsProfile->getFullName($head_of_dep_id, $oProfile).'</h4>').'
						</div>
					</div>
					<div class="d-flex gap-2 text-white text-center align-items-center">
						<div class="text-center w-px-40">'.$oval['total_billings'].'</div>
						<div class="text-center w-px-80">'.$clsISO->shortNumberV2($oval['total_sales']).'</div>
					</div>
				</div>';
				++$ii;
			}
		}
	}
	// Return
	echo json_encode(array_merge($more, array(
		'uid' => $uid,
		'html' => $html
	))); die();
}
function dashboard_load_info_fund(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsFund = new Fund();
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	###
	$gId = Input::post('gId');
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$bank_account_id = (int) Input::post('bank_account_id', 0);
	###
	$start_year = 2023; $end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$cond = $cond_prev = $cnd = "`is_trash`=0";
	if($bank_account_id > 0) {
		$cnd.= " and `bank_account_id`='{$bank_account_id}'";
	}
	if($month > 0){
		$m = $label = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$start_date = strtotime(sprintf('01-%s-%s', $clsISO->parseNumber($month), $year));
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $month, $year));
		$cond.= " and (FROM_UNIXTIME(`account_date`, '%m/%Y')='{$m}')";
		$label = sprintf('%s/%s', $month, $year);
	} else {
		$label = sprintf('năm %s', $year);
		$start_date = strtotime(sprintf('01-01-%s', $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, 12, $year));
		$cond.= " and (FROM_UNIXTIME(`account_date`, '%Y')='{$year}')";
	}
	// Quỹ đầu kì
	$total_period = $total_period_prev = $clsFund->getTotalStat($bank_account_id);
	#- Tổng thu đầu quỹ
	$total_income = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCTHU' and `account_date`<'{$start_date}'");
	$total_expense = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCCHI' and `account_date`<'{$start_date}'");
	$total_period+= ($total_income - $total_expense);
	// Tổng thu
	$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance = $total_period + $total_income - $total_expense;
	if($bank_account_id > 0 && $end_date > 0){
		$total_balance += $clsFund->getTotalTrans($bank_account_id, $end_date);
	}
	// So sánh
	if($month > 0){
		if($month == 1){
			$prev_month = 12;
			$prev_year = ($year-1);
		} else {
			$prev_month = ($month - 1);
			$prev_year = $year;
		}
		$m_prev = sprintf('%s/%s', $clsISO->parseNumber($prev_month), $prev_year);
		$end_day_prev = cal_days_in_month(CAL_GREGORIAN, $prev_month, $prev_year);
		$start_date_prev = strtotime(sprintf('01-%s-%s', $clsISO->parseNumber($prev_month), $prev_year));
		$end_date_prev = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day_prev, $prev_month, $prev_year));
		$cond_prev.= " and (FROM_UNIXTIME(`account_date`, '%m/%Y')='{$m_prev}')";
	} else {
		$prev_year = ($year - 1);
		$start_date_prev = strtotime(sprintf('01-01-%s', $prev_year));
		$end_day_prev = cal_days_in_month(CAL_GREGORIAN, 12, $prev_year);
		$end_date_prev = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day_prev, 12, $prev_year));
		$cond_prev.= " and (FROM_UNIXTIME(`account_date`, '%Y')='{$prev_year}')";
	}
	#- Tổng thu đầu quỹ
	$total_income_prev = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCTHU' and `account_date`<'{$start_date_prev}'");
	$total_expense_prev = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCCHI' and `account_date`<'{$start_date_prev}'");
	$total_period_prev+= ($total_income_prev - $total_expense_prev);
	// Tổng thu
	$total_income_prev = $clsFund->sumItem("amount", "{$cond_prev} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense_prev = $clsFund->sumItem("amount", "{$cond_prev} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance_prev = $total_period_prev + $total_income_prev - $total_expense_prev;
	if($bank_account_id > 0 && $end_date_prev > 0){
		$total_balance_prev += $clsFund->getTotalTrans($bank_account_id, $end_date_prev);
	}
	$html = '<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card no-shadow cursor-pointer">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/paypal.png" alt="cube" class="rounded">
					</div>
					<div class="dropdown">
						<button class="btn btn-icon p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end">
							<div class="p-3">
								<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
									<select class="form-control form-select" name="month" gId="'.$gId.'" 
										onChange="$Core.dashboard.reload(this,event)"> 
										<option value="">Tháng</option>';
										foreach($list_months as $_month){
											$html.= '<option'.($_month==$month?" selected":"").' value="'.$_month.'">Tháng '.$_month.'</option>';
										}
									$html.= '</select>
								</div>
								<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
									<select class="form-control form-select" name="year" gId="'.$gId.'" 
										onChange="$Core.dashboard.reload(this,event)">';
									foreach($list_years as $_year){
										$html.= '<option'.($_year==$year?" selected":"").' value="'.$_year.'">'.$_year.'</option>';
									}
									$html.= '</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Quỹ đầu kỳ '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_period).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_period, $total_period_prev).'
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card no-shadow gotoLink bg-light cursor-pointer" href="'.PCMS_URL.'/fund/report.html">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/cc-primary.png" alt="cube" class="rounded">
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Tồn quỹ '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_balance).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_balance, $total_balance_prev).'
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card no-shadow'.($total_income > $total_expense?' bg-success text-white':'').' gotoLink cursor-pointer" 
			href="'.PCMS_URL.'/fund/report.html">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/wallet.png" alt="cube" class="rounded">
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Tổng thu '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_income).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_income, $total_income_prev).'
			</div>
		</div>
	</div>
	<div class="col-6 col-md-3 col-lg-6 mb-2">
		<div class="card no-shadow'.($total_expense > $total_income?' bg-danger text-white':'').' gotoLink cursor-pointer" 
			href="'.PCMS_URL.'/fund/report.html">
			<div class="card-body">
				<div class="card-title d-flex align-items-start justify-content-between mb-4">
					<div class="avatar flex-shrink-0">
						<img src="'.URL_IMAGES.'/dashboard/cc-success.png" alt="cube" class="rounded">
					</div>
				</div>
				<span class="fw-medium d-block text-nowrap mb-1">Tổng chi '.$label.'</span>
				<h4 class="card-title mb-2">'.$clsISO->shortNumber($total_expense).'</h4>
				'.$clsHelper->getHtmlGrowthV2($total_expense, $total_expense_prev).'
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_info_staff(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsCache = new Cache();
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	###
	$uid = $clsISO->getUniqid();
	$role_id = $oneProfile['role_id'];
	$cond = "`is_active`=1 AND {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."' AND `status_id`='"._STATUS_STAFF_ON_ID."'";
	if($clsCache->has('_ss_staff_cached')){
		$_ss_staff_cached = $clsCache->get('_ss_staff_cached');
		$total_staffs = $_ss_staff_cached['total_staffs'];
		$total_staffs_month = $_ss_staff_cached['total_staffs_month'];
	} else {
		$total_staffs = $clsProfile->countItem($cond);
		$total_staffs_month = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(reg_date,'%m/%Y')='".date('m/Y')."'");
		$_ss_staff_cached['total_staffs'] = $total_staffs;
		$_ss_staff_cached['total_staffs_month'] = $total_staffs_month;
		$clsCache->put('_ss_staff_cached', $_ss_staff_cached);
	}
	$labels = $series = array();
	$due_date = time(); $start_date = strtotime('-12 months', $due_date); 
	for($i=$start_date; $i<=$due_date; $i = strtotime('+1 month', $i)){
		$labels[] = sprintf('T%s', date('n/y', $i));
		$series[] = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".date('m/Y', $i)."'");
	}
	$data = $dataPoints = $barChartData = array();
	if($role_id == _ROLE_HEAD_HR_BO){
		$drawchart = 1;
		//$clsCache->delete('_dept_staffs_cached');
		if($clsCache->has('_dept_staffs_cached')){
			$list_staffs = $clsCache->get('_dept_staffs_cached');
		} else {
			$field = "`t1`.`title`,count(1) as `total_staffs`";
			$list_staffs = $dbconn->getAll("select {$field} from {$clsProperty->tbl} as `t1` 
				inner join {$clsProfile->tbl} as `t2` on `t1`.`property_id`=`t2`.`department_id` 
				and `t1`.`property_type`='_DEPARTMENT' and t1.parent_id='"._DEPARTMENT_SALE_ID."' 
				WHERE `t2`.`is_trash`=0 and `t2`.`is_active`='1' and `t2`.`status_id`='"._STATUS_STAFF_ON_ID."' 
				and `t1`.`is_trash`=0 group by `t2`.`department_id` order by `total_staffs` DESC");
			$clsCache->put('_dept_staffs_cached', $list_staffs);
		}
		###
		if(!empty($list_staffs)){
			foreach($list_staffs as $key => $val){
				$dataPoints[] = array(
					'label'	=> $val['title'],
					'y'	=> $val['total_staffs']*1,
					"indexLabel" => "{y}",
				);
			}
		}
		$data['type'] = 'column';
		$data['indexLabel'] = '{y} ';
		$data['title']['fontSize'] = '13';
		$data['title']['fontColor'] = 'rgb(159,34,58)';
		$data['indexLabelPlacement'] = 'inside';
		$data['indexLabelFontColor'] = '#36454F';
		$data['dataPoints'] = $dataPoints;
		$barChartData['animationEnabled'] = true;
		$barChartData['data'] = $data;
		$html = '<div class="row">
			<div class="col-12'.($deviceType=='phone'?' border-end':'').' col-md-3 mb-3 mb-lg-0">
				<h6 class="card-title mb-3 text-nowrap">Nhân viên</h6>
				<h5 class="card-title text-main text-nowrap mb-1">'.$total_staffs.' nhân viên</h5>
				<small class="d-block mb-2 mb-lg-3 pb-1 text-muted">
					<strong class="text-success">+'.$total_staffs_month.'</strong> nhân viên mới tháng '.date('m/Y').'
				</small>
				<a href="'.PCMS_URL.'/staff.html" class="btn btn-sm btn-outline-primary">Danh sách</a>
			</div>
			<div class="col-12 col-md-5'.($deviceType=='phone'?' border-end':'').'">
				<div id="'.$uid.'" class="chartContainer w-100" style="height:125px"></div>
			</div>
			<div class="col-12 col-md-4">
				<div id="staffChart_'.$uid.'" class="chartContainer pt-4 w-100 h-px-125"></div>
			</div>
		</div>';
	} else {
		$drawchart = 0;
		$html = '<div class="row">
			<div class="col-5">
				<h6 class="card-title mb-3 text-nowrap">Nhân viên</h6>
				<h5 class="card-title text-main text-nowrap mb-1">'.$total_staffs.' nhân viên</h5>
				<small class="d-block mb-2 pb-1 text-muted">
					<strong class="text-success">+'.$total_staffs_month.'</strong> nhân viên mới tháng '.date('m/Y').'
				</small>
				<a href="'.PCMS_URL.'/staff.html" class="btn btn-sm btn-outline-primary">Danh sách</a>
			</div>
			<div class="col-7 ps-0">
				<div id="staffChart_'.$uid.'" class="chartContainer pt-3 w-100 h-px-100"></div>
			</div>
		</div>';
	}
	$callback = 'var options = {
		chart: {
			height: 110,
			type: "bar",
			toolbar: {show: !1}
		}, plotOptions: {
			bar: {
				barHeight: "60%",
				columnWidth: "50%",
				startingShape: "rounded",
				endingShape: "rounded",
				borderRadius: 3,
				distributed: !0
			}
		}, grid: {
			show: !1,
			padding: {
				top: -35,
				bottom: -10,
				left: -10,
				right: -10
			}
		}, colors: [
			config.colors.primary,
			config.colors.secondary,
			config.colors.info,
			config.colors.success,
			config.colors.warning,
			config.colors.danger,
			config.colors.black
		], tooltip: {
			custom: function({series, seriesIndex, dataPointIndex, w}) {
				return \'<div class="p-1">Nhân viên mới: \'+series[seriesIndex][dataPointIndex] + \'</div>\'
			}
		}, dataLabels: {enabled: !1},
		series: [{data: ['.implode(',',$series).']}],
		legend: {show: !1},
		xaxis: {
			categories: [\''.implode('\',\' ', $labels).'\'],
			axisBorder: {show: !1},
			axisTicks: {show: !1},
			labels: {style: {fontSize: "11px"}}
		}, yaxis: {
			labels: {show: !1}
		}
	};
	var chart = new ApexCharts(document.querySelector(\'#staffChart_'.$uid.'\'), options);
	chart.render()';
	// skin=dbx: dashboard BĐH — ruột số lớn + mini-bar thuần CSS từ $series/$labels, bỏ ApexCharts (callback rỗng vẫn được eval an toàn)
	$skin = Input::post('skin', '');
	if($skin == 'dbx'){
		$max_val = !empty($series) ? max(1, max($series)) : 1;
		$last_idx = count($series) - 1;
		$bars = '';
		foreach($series as $idx => $val){
			$bar_h = max(4, (int) round($val / $max_val * 48));
			$lb = $labels[$idx];
			if($idx > 0 && strpos($lb, 'T1/') !== 0){
				$lb = preg_replace('#/\d+$#', '', $lb);
			}
			$bars.= '<div class="dbx-bars__col">
				<div class="dbx-bars__bar'.($idx == $last_idx ? ' dbx-bars__bar--hl' : '').'" style="height:'.$bar_h.'px"></div>
				<span class="dbx-bars__lbl">'.$lb.'</span>
			</div>';
		}
		$html = '<div class="dbx-bignum"><span class="dbx-bignum__val">'.$total_staffs.'</span><span class="dbx-bignum__cap">nhân viên</span></div>
			<div class="dbx-upnote">+'.$total_staffs_month.' nhân viên mới tháng '.date('m/Y').'</div>
			<div class="dbx-bars">'.$bars.'</div>';
		$callback = '';
		$drawchart = 0;
		$barChartData = array();
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => $drawchart,
		'callback' => $callback,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_report_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$cond = "`is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	$total_staffs = $clsProfile->countItem($cond);
	$total_staffs_month = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(reg_date,'%m/%Y')='".date('m/Y')."'");
	$html = '<div class="col-5">
		<h6 class="card-title mb-3 text-nowrap">Nhân viên</h6>
		<h5 class="card-title text-main text-nowrap mb-1">'.$total_staffs.' nhân viên</h5>
		<small class="d-block mb-4 pb-1 text-muted">
			<strong class="text-success">+'.$total_staffs_month.'</strong> nhân viên mới tháng '.date('m/Y').'
		</small>
		<a href="javascript:void(0);" data-toggle="ripple" onClick="$Core.dashboard.open_report_stock_hug(this, event)" class="btn btn-sm btn-outline-primary">Danh sách</a>
	</div>
	<div class="col-7 ps-0">
		<div id="staffChart_'.$uid.'" class="chartContainer pt-4 w-100 h-px-125"></div>
	</div>';
	$labels = $series = array();
	$due_date = time();
	$start_date = strtotime('-12 months', $due_date); 
	for($i=$start_date; $i<=$due_date; $i = strtotime('+1 month', $i)){
		$labels[] = sprintf('T%s', date('n/y', $i));
		$series[] = $clsProfile->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".date('m/Y', $i)."'");
	}
	$callback = 'var options = {
		chart: {
			height: 110,
			type: "bar",
			toolbar: {show: !1}
		},
		plotOptions: {
			bar: {
				barHeight: "60%",
				columnWidth: "50%",
				startingShape: "rounded",
				endingShape: "rounded",
				borderRadius: 3,
				distributed: !0
			}
		},
		grid: {
			show: !1,
			padding: {
				top: -35,
				bottom: -10,
				left: -10,
				right: -10
			}
		},
		colors: [
			config.colors.primary,
			config.colors.secondary,
			config.colors.info,
			config.colors.success,
			config.colors.warning,
			config.colors.danger,
			config.colors.black
		],
		tooltip: {
			custom: function({series, seriesIndex, dataPointIndex, w}) {
				return \'<div class="p-1">Nhân viên mới: \'+series[seriesIndex][dataPointIndex] + \'</div>\'
			}
		},
		dataLabels: {enabled: !1},
		series: [{data: ['.implode(',',$series).']}],
		legend: {show: !1},
		xaxis: {
			categories: [\''.implode('\',\' ', $labels).'\'],
			axisBorder: {show: !1},
			axisTicks: {show: !1},
			labels: {style: {fontSize: "11px"}}
		},
		yaxis: {
			labels: {show: !1}
		}
	};
	var chart = new ApexCharts(document.querySelector(\'#staffChart_'.$uid.'\'), options);
	chart.render()';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback
	)); die();
}
function dashboard_open_report_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsBilling = new Billing();
	$clsStockHug = new StockHug();
	$uid = $clsISO->getUniqid();
	$list_patterns = array(
		'_soled' => array(
			'title' => 'Số căn bán tính đến hiện tại',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_f1_deposited' => array(
			'title' => 'Số căn F1 KH đã cọc của tháng',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_cross_selling' => array(
			'title' => 'Số căn bán chéo',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_registed_hdmb_month' => array(
			'title' => 'Số đã ký HĐMB tính số của tháng',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_wait_sign_hdmb' => array(
			'title' => 'Số căn chờ ký HĐMB',
			'total_stock' => 0,
			'total_price' => 0
		),
		'_not_sold' => array(
			'title' => 'Tổng số căn quỹ ôm đang chờ bán',
			'total_stock' => 0,
			'total_price' => 0
		)
	);
	foreach($list_patterns as $key => $val){
		if($key == '_soled'){
			$total_stock = $clsStockHug->countItem("`status_id`='"._STOCK_HUG_STATUS_SOLD_ID."'");
			$total_price = 0;
		} else if($key == '_f1_deposited'){
			$total_stock = 0;
			$total_price = 0;
		}
		$list_patterns[$key]['total_stock'] = $total_stock;
		$list_patterns[$key]['total_price'] = $total_price;
	}
	$smarty->assign('list_patterns', $list_patterns);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.report_stock_hug.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function dashboard_load_data_share(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsShare = new Share();
	$clsHelper = new Helper();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$cond = "`t1`.`share_type`='share' and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	if($month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`, '%m/%Y')='{$date_my}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`t1`.`reg_date`, '%Y')='{$year}'";
	}
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	###
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	$field = "COUNT(*) as `total_share`,`t2`.`profile_id`,`t2`.`first_name`,`t2`.`last_name`,`t2`.`full_name`";
	$list_shares = $dbconn->getAll("SELECT {$field} FROM {$clsShare->tbl} AS `t1` 
		INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`user_id`=`t2`.`profile_id` 
		WHERE {$cond} GROUP BY `t1`.`user_id` HAVING `total_share`>0 limit 0,15");
	if(!empty($list_shares)){
		foreach($list_shares as $key => $val){
			$staff_id = $val['profile_id'];
			$staff_name = ($deviceType=='phone') 
				? $clsProfile->getLastName($staff_id, $val, false) 
				: $clsProfile->getFullName($staff_id, $val);
			$dataPoints[] = array(
				'label'	=> $staff_name,
				'y'	=> $val['total_share']*1,
				"indexLabel" => "{y}",
			);
		}
		$ks = array_column($dataPoints, "y");
		array_multisort($ks, SORT_ASC, $dataPoints);
	}
	$data['type'] = 'bar';
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
		'drawchart' => '1',
		'barChartData' => $barChartData
	)); die();
}
function dashboard_load_data_followups(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsShare = new Share();
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	##
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	$barChartData = array();
	$barChartData['axisX'] = array(
		'labelAngle' => -90,
		'interval' => 1
	);
	$list_props = $clsProperty->getCacheItems('FOLLOWUP_TYPE');
	if(!empty($list_props)){ $ii = 0;
		foreach($list_props as $key => $val){
			$dataPoints = array();
			$prop_id = $val[$clsProperty->pkey];
			if($month > 0){
				$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				for($day=1; $day<=$end_day; $day++){
					$date = sprintf('%s/%s/%s', $clsISO->parseNumber($day), $clsISO->parseNumber($month), $year);
					$cond = " and FROM_UNIXTIME(`date_id`, '%d/%m/%Y')='{$date}'";
					$total_followups = $clsFollowUp->countItem("`is_trash`=0 and `type_id`='{$prop_id}'".$cond);
					$dataPoints[] = array(
						'name' => $i,
						'y' => $total_followups*1
					);
				}
			} else {
				for($m=1; $m<=12; $m++){
					$date_my = sprintf('%s/%s', $clsISO->parseNumber($m), $year);
					$cond = " and FROM_UNIXTIME(`date_id`, '%m/%Y')='{$date_my}'";
					$total_followups = $clsFollowUp->countItem("`is_trash`=0 and `type_id`='{$prop_id}'".$cond);
					$dataPoints[] = array(
						'name' => sprintf('T%s', $m),
						'y' => $total_followups*1,
					);
				}
			}
			$barChartData['data'][] =  array(
				"type" => ($ii==1 ? "spline" : "column"),
				"indexLabel" => "{y}",
				"toolTipContent" => $val['title'].': {y}',
				"color"	 => $val['bgcolor'],
				"name"    => $val['title'],
				"showInLegend"    => true,
				"dataPoints" => $dataPoints
			);
			++$ii;
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_load_data_customer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	#
	$uid = $clsISO->getUniqid();
	$year  = (int) Input::post('year', date('Y'));
	$html = '<div id="'.$uid.'" class="chartContainer" style="height:300px"></div>';
	#
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$data['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	for($i=1; $i<=12; $i++){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
		$total_customer = $clsCustomer->countItem("`is_trash`=0 and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'");
		$dataPoints[] = array(
			'label' => sprintf('T%s', $i),
			'y' => $total_customer*1,
			'indexLabel' => $total_customer
		);
	}
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
		'drawchart' => '1',
		'barChartData' => $barChartData
	)); die();
}
function dashboard_dbx_split_number($val){
	// Tách "34,84 tỷ" → [số, đơn vị] để .dbx-stat__num hiển thị đơn vị cỡ nhỏ (dashboard BĐH skin=dbx)
	$pos = strrpos($val, ' ');
	if($pos === false){ return array($val, ''); }
	return array(substr($val, 0, $pos), substr($val, $pos + 1));
}
function dashboard_load_sales_overview(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', "_month");
	if($date_type == '_month'){
		$quarter = 0; // Default value
		$month = (int) Input::post('month', date('n'));
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Default value
		$quarter = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	###
	$cond = $prev_cond = "`is_trash`=0 AND `is_cancel`=0";
	$txt_time = "";
	if($date_type == '_month' && $month > 0){
		if($month == 1){
			$prev_month = 12;
			$prev_year = ($year - 1);
		} else {
			$prev_month = ($month - 1);
			$prev_year = $year;
		}
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$date_prev_my = sprintf('%s/%s', $clsISO->parseNumber($prev_month), $prev_year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
		$prev_cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_prev_my}'";
		$compare_text = sprintf('tháng %s', $date_prev_my);
		$txt_time = sprintf("Tháng %s",$date_my);
	} else if($date_type == '_quarter' && $quarter > 0){
		$prev_year = $year-1;
		$prev_quarter = $quarter;
		/* if($quarter == 1){
			$prev_year = $year - 1;
			$prev_quarter = 4;
		} else {
			$prev_quarter = ($quarter - 1);
		}*/
		$cond.= " and QUARTER(FROM_UNIXTIME(`deposit_date`))='{$quarter}' AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$prev_cond.= " and QUARTER(FROM_UNIXTIME(`deposit_date`))='{$prev_quarter}' and FROM_UNIXTIME(`deposit_date`,'%Y')='{$prev_year}'";
		$date_prev_my = sprintf('%s/%s', $prev_quarter, $prev_year);
		$compare_text = sprintf('quý %s', $date_prev_my);
		$txt_time = sprintf("Quý %s/%s",$quarter,$year);
	} else if($date_type == '_half_year' && $half_year > 0){
		if($half_year == 1){
			$start_month = 1;
			$end_month = 6;
			$txt_time = sprintf("Nửa đầu năm %s",$year);
		} else if($half_year == 2){
			$start_month = 7;
			$end_month = 12;
			$txt_time = sprintf("Nửa cuối năm %s",$year);
		}
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
		$cond.= " AND (`deposit_date` BETWEEN {$start_date} AND {$end_date})";
		###
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year-1));
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year-1));
		$prev_cond.= " AND (`deposit_date` BETWEEN {$start_date} AND {$end_date})";
		$compare_text = sprintf('năm %s', $year-1);
	} else {
		$prev_year = ($year - 1);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$prev_cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$prev_year}'";
		$compare_text = sprintf('năm %s', $prev_year);
		if($year != date("Y")) {
			$txt_time = sprintf("Năm %s",$year);
		}else{
			$txt_time = sprintf("Bán hàng tới %s",date("d/m/Y"));
		}
	}
	# 
	$total_trans_billings = $clsBilling->countItem("{$cond} AND `billing_type`='"._BILLING_TYPE_SOP_ID."'");
	$total_trans_sales = $clsBilling->sumItem("totalgrand", "{$cond} AND `billing_type`='"._BILLING_TYPE_SOP_ID."'");;
	#
	$cond.= " AND `billing_type`<>'"._BILLING_TYPE_SOP_ID."'";
	$prev_cond.= " AND `billing_type`<>'"._BILLING_TYPE_SOP_ID."'";
	// Tỏng DS & GD tháng này
	$total_sales = $total_billings = 0;
	$tmp = $clsBilling->getAll($cond, "{$clsBilling->pkey},totalgrand");
	if(!empty($tmp)){
		$total_billings = count($tmp);
		foreach($tmp as $key => $val){
			$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
			$total_sales += $totalgrand;
		}
		unset($tmp);
	}
	// Tỏng DS & GD tháng trước
	$total_prev_sales = $total_prev_billings = 0;
	$tmp = $clsBilling->getAll($prev_cond, "{$clsBilling->pkey},totalgrand");
	if(!empty($tmp)){
		$total_prev_billings = count($tmp);
		foreach($tmp as $key => $val){
			$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
			$total_prev_sales += $totalgrand;
		}
		unset($tmp);
	}
	#fake
	/*$total_sales *=2.5;$total_billings *= 2.5;*/
	// skin=dbx: dashboard Ban Điều Hành (thiết kế 07/2026) — cùng số liệu, markup .dbx-stat; caller khác giữ nguyên nhánh else
	$skin = Input::post('skin', '');
	if($skin == 'dbx'){
		list($num_sales, $unit_sales) = dashboard_dbx_split_number($clsISO->shortNumber($total_sales,3,1));
		list($num_trans, $unit_trans) = dashboard_dbx_split_number($clsISO->shortNumber($total_trans_sales,3,1));
		$dir_sales = ($total_sales == $total_prev_sales) ? '' : (($total_sales > $total_prev_sales) ? ' is-up' : ' is-down');
		$dir_billings = ($total_billings == $total_prev_billings) ? '' : (($total_billings > $total_prev_billings) ? ' is-up' : ' is-down');
		$html = '<div class="dbx-stat-row">
			<div class="dbx-stat">
				<div class="dbx-stat__lbl">Doanh số</div>
				<div class="dbx-stat__num">'.$num_sales.'<span>'.$unit_sales.'</span></div>
				<div class="dbx-stat__growth'.$dir_sales.'">'.$clsHelper->getHtmlGrowthV2($total_sales, $total_prev_sales).' so với '.$compare_text.'</div>
			</div>
			<div class="dbx-stat">
				<div class="dbx-stat__lbl">Số lượng</div>
				<div class="dbx-stat__num">'.$total_billings.'<span>GD</span></div>
				<div class="dbx-stat__growth'.$dir_billings.'">'.$clsHelper->getHtmlGrowthV2($total_billings, $total_prev_billings).' so với '.$compare_text.'</div>
			</div>
		</div>';
	} else {
		$html = '<div class="row row-cols-2">
			<div class="col border-end">
				<h6 class="fw-semibold line-clamp-1 text-fs-14 mb-0">Doanh số</h5>
				<hr class="my-2 w-px-50" />
				<h5 class="card-title fs-5 text-main mb-1">'.$clsISO->shortNumber($total_sales,3,1).'</h5>
				<small class="d-block text-muted"><strong class="text-main text-nowrap">
					'.$clsHelper->getHtmlGrowth($total_sales, $total_prev_sales).'</strong> so với '.$compare_text.'</small>
			</div>
			<div class="col">
				<h6 class="fw-semibold line-clamp-1 text-fs-14 mb-0">Số lượng</h5>
				<hr class="my-2 w-px-50" />
				<h5 class="card-title fs-5 text-main mb-1">'.$total_billings.' GD</h5>
				<small class="d-block text-muted"><strong class="text-main text-nowrap">
					'.$clsHelper->getHtmlGrowth($total_billings, $total_prev_billings).'</strong> so với '.$compare_text.'</small>
			</div>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'txt_time' => $txt_time,
	), JSON_UNESCAPED_UNICODE); die();
}
function dashboard_load_person_dept_sales(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	###
	$department_id = (int) $oneProfile['department_id'];
	$department_name = $core->get_field($oneProfile, 'department_name', 'Phòng mình');
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	if($year <= 0){ $year = (int) date('Y'); }
	### Danh sách sale trong phòng (gồm cả subtree phòng con)
	$list_staffs = $clsProfile->getProfileDep($department_id, 1, "all");
	$arr_staff_ids = !empty($list_staffs) ? @array_keys($list_staffs) : array();
	$dept_in = "'".implode('\',\'', $arr_staff_ids)."'";
	### Điều kiện kỳ hiện tại + kỳ trước (đối chiếu tăng trưởng)
	$base = "`is_trash`=0 AND `is_cancel`=0";
	if($month > 0){
		if($month == 1){
			$prev_month = 12;
			$prev_year = ($year - 1);
		} else {
			$prev_month = ($month - 1);
			$prev_year = $year;
		}
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$prev_my = sprintf('%s/%s', $clsISO->parseNumber($prev_month), $prev_year);
		$time_cond = " AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$my}'";
		$prev_time_cond = " AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$prev_my}'";
		$compare_text = sprintf('tháng %s', $prev_my);
	} else {
		$prev_year = ($year - 1);
		$time_cond = " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$prev_time_cond = " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$prev_year}'";
		$compare_text = sprintf('năm %s', $prev_year);
	}
	### Cá nhân
	$p_base = $base." AND `staff_id`='{$profile_id}'";
	$person_sales = $clsBilling->sumItem("totalgrand", $p_base.$time_cond);
	$person_billings = (int) $clsBilling->countItem($p_base.$time_cond);
	$person_prev_sales = $clsBilling->sumItem("totalgrand", $p_base.$prev_time_cond);
	### Phòng mình quản lý
	$d_base = $base." AND `staff_id` IN ({$dept_in})";
	$dept_sales = $clsBilling->sumItem("totalgrand", $d_base.$time_cond);
	$dept_billings = (int) $clsBilling->countItem($d_base.$time_cond);
	$dept_prev_sales = $clsBilling->sumItem("totalgrand", $d_base.$prev_time_cond);
	### Doanh số rút gọn — shortNumber tự bọc đơn vị (tỷ/đ) trong <small>, in thẳng
	$person_sales_html = $clsISO->shortNumber($person_sales, 1, 1);
	$dept_sales_html = $clsISO->shortNumber($dept_sales, 1, 1);
	### Doanh số thi đua (realized_sales_compete) — cá nhân + phòng
	$person_compete = $clsBilling->sumItem("realized_sales_compete", $p_base.$time_cond);
	$dept_compete = $clsBilling->sumItem("realized_sales_compete", $d_base.$time_cond);
	$person_compete_html = $clsISO->shortNumber($person_compete, 1, 1);
	$dept_compete_html = $clsISO->shortNumber($dept_compete, 1, 1);
	$person_growth = $clsHelper->getHtmlGrowthV2($person_sales, $person_prev_sales);
	$dept_growth = $clsHelper->getHtmlGrowthV2($dept_sales, $dept_prev_sales);
	$dept_name_html = htmlspecialchars($department_name, ENT_QUOTES);
	### Markup 2 cột (nạp vào .dsx-grid)
	$html = '<div class="dsx-seg">
		<div class="dsx-seg-top">
			<span class="dsx-ic gold"><i class="bx bx-user"></i></span>
			<span class="dsx-seg-lbl">Của mình</span>
			<span class="dsx-chip"><i class="bx bx-receipt"></i>'.$person_billings.' GD</span>
		</div>
		<div class="dsx-metrics">
			<div class="dsx-metric"><div class="dsx-cap">Doanh số bán</div><div class="dsx-val">'.$person_sales_html.'</div></div>
			<div class="dsx-metric dsx-metric--c"><div class="dsx-cap">Thi đua</div><div class="dsx-val dsx-val--c">'.$person_compete_html.'</div></div>
		</div>
		<div class="dsx-growth">'.$person_growth.' so với '.$compare_text.'</div>
	</div>
	<div class="dsx-seg">
		<div class="dsx-seg-top">
			<span class="dsx-ic blue"><i class="bx bx-buildings"></i></span>
			<span class="dsx-seg-lbl">'.$dept_name_html.'</span>
			<span class="dsx-chip"><i class="bx bx-receipt"></i>'.$dept_billings.' GD</span>
		</div>
		<div class="dsx-metrics">
			<div class="dsx-metric"><div class="dsx-cap">Doanh số bán</div><div class="dsx-val">'.$dept_sales_html.'</div></div>
			<div class="dsx-metric dsx-metric--c"><div class="dsx-cap">Thi đua</div><div class="dsx-val dsx-val--c">'.$dept_compete_html.'</div></div>
		</div>
		<div class="dsx-growth">'.$dept_growth.' so với '.$compare_text.'</div>
	</div>';
	echo json_encode(array('html' => $html), JSON_UNESCAPED_UNICODE); die();
}
function dashboard_load_checkin_report(){
	global $dbconn, $clsISO, $profile_id;
	$tbl = DB_PREFIX.'office_checkin';
	$pf = DB_PREFIX.'profile';
	$dept = (int) Input::post('dept', 0);
	// Chi tinh nhan su DANG LAM VIEC (loai da nghi = _STATUS_STAFF_OFF_ID)
	$work = "`p`.`is_trash`=0 AND `p`.`is_active`=1 AND `p`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	$scope = ($dept > 0) ? " AND `p`.`list_department_id` LIKE '%|".$dept."|%'" : '';
	$r = $dbconn->getRow("SELECT COUNT(*) AS `c` FROM {$pf} AS `p` WHERE {$work}{$scope}");
	$roster_total = !empty($r['c']) ? (int) $r['c'] : 0;
	$join = "INNER JOIN {$pf} AS `p` ON `oc`.`profile_id`=`p`.`profile_id` AND {$work}{$scope}";
	$today = (int) date('Ymd'); $ym = (int) date('Ym'); $y = (int) date('Y');
	$rd = $dbconn->getRow("SELECT COUNT(DISTINCT `oc`.`profile_id`) AS `c` FROM {$tbl} AS `oc` {$join} WHERE `oc`.`work_date`={$today}");
	$rm = $dbconn->getRow("SELECT COUNT(*) AS `c` FROM {$tbl} AS `oc` {$join} WHERE FLOOR(`oc`.`work_date`/100)={$ym}");
	$ry = $dbconn->getRow("SELECT COUNT(*) AS `c` FROM {$tbl} AS `oc` {$join} WHERE FLOOR(`oc`.`work_date`/10000)={$y}");
	$td = !empty($rd['c']) ? (int) $rd['c'] : 0;
	$mo = !empty($rm['c']) ? (int) $rm['c'] : 0;
	$yr = !empty($ry['c']) ? (int) $ry['c'] : 0;
	$pct = $roster_total > 0 ? min(100, round($td / $roster_total * 100)) : 0;
	$f = function($n){ return number_format($n, 0, ',', '.'); };
	$html = '<div class="ckin-grid">'
		.'<div class="ckin-card"><div class="ckin-top"><span class="ckin-ic green"><i class="bx bx-user-check"></i></span><span class="ckin-lbl">Hôm nay</span></div>'
		.'<div class="ckin-num">'.$td.'<span class="ckin-unit">/ '.$roster_total.' NS · '.$pct.'%</span></div>'
		.'<div class="ckin-bar"><span class="green" style="width:'.$pct.'%"></span></div></div>'
		.'<div class="ckin-card"><div class="ckin-top"><span class="ckin-ic navy"><i class="bx bx-calendar-check"></i></span><span class="ckin-lbl">Tháng này</span></div>'
		.'<div class="ckin-num">'.$f($mo).'<span class="ckin-unit">lượt</span></div>'
		.'<div class="ckin-bar"><span class="navy" style="width:100%"></span></div></div>'
		.'<div class="ckin-card"><div class="ckin-top"><span class="ckin-ic amber"><i class="bx bx-calendar-star"></i></span><span class="ckin-lbl">Năm nay</span></div>'
		.'<div class="ckin-num">'.$f($yr).'<span class="ckin-unit">lượt</span></div>'
		.'<div class="ckin-bar"><span class="amber" style="width:100%"></span></div></div>'
		.'</div>';
	echo json_encode(array('html' => $html), JSON_UNESCAPED_UNICODE); die();
}
function dashboard_ms_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsStockHug = new StockHug();
	$cond= "is_trash=0";
	$html = '<div class="d-flex flex-wrap gap-1">';
	foreach($list_blocks as $key => $val){
		if($key == '_TOTAL'){
			$where = $cond;
		} else if($key == '_SOLD'){
			$props = $clsISO->make_attrs_builder(array(
				'field' => 'status_id',
				'status_id' => _STOCK_HUG_STATUS_SOLD_ID
			));
			$onClick = '$Core.stock_hug.set_status(this, event)';
			$where = $cond." and `status_id`='"._STOCK_HUG_STATUS_SOLD_ID."'";
		} else if($key == '_NOT_SOLD'){
			$props = $clsISO->make_attrs_builder(array(
				'field' => 'status_id',
				'status_id' => _STOCK_HUG_STATUS_DEF_ID
			));
			$onClick = '$Core.stock_hug.set_status(this, event)';
			$where = $cond." and `status_id`='"._STOCK_HUG_STATUS_DEF_ID."' 
				and `status_id`<>'"._STOCK_HUG_STATUS_UNLOCK_ID."'";
		} else if($key == '_UNLOCK'){
			$props = $clsISO->make_attrs_builder(array(
				'field' => 'status_id',
				'status_id' => _STOCK_HUG_STATUS_UNLOCK_ID
			));
			$onClick = '$Core.stock_hug.set_status(this, event)';
			$where = $cond." and `status_id`='"._STOCK_HUG_STATUS_UNLOCK_ID."'";
		} else if($key == '_BALANCE'){
			$where = $cond." and (`status_id`='"._STOCK_HUG_STATUS_UNLOCK_ID."' 
				or `status_id`='"._STOCK_HUG_STATUS_DEF_ID."')";
		}
		$total = $clsStockHug->countItem($where);
		$html.= '<div class="gbox gotoLink flex-fill px-2 py-3">
			<div class="d-flex mb-2 align-items-center justify-content-between">
				<h5 class="mb-0 fs-14">'.$val['title'].'</h5> 
				<a data-bs-toggle="tooltip" data-bs-toggle="hover" class="panel-help help_pop" title="'.$val['subtitle'].'">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span data-from="0" data-to="1089">'.$total.'</span>
			</h3>
		</div>';
	}
	$html .= '</div>';	
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_stock_hug(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsStock = new Stock();
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$total_stocks = 0; $arr_blocks = array();
	$field = "{$clsStock->pkey},`block_id`";
	$list_stocks = $clsStock->getAll("`is_trash`=0 and `agency_id`='"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_DQ_ID."'", $field);
	if(!empty($list_stocks)){
		$total_stocks = count($list_stocks);
		foreach($list_stocks as $key => $val){
			$block_id = $val['block_id'];
			if(isset($arr_blocks[$block_id])){
				$arr_blocks[$block_id] += 1;
			} else {
				$arr_blocks[$block_id] = 1;
			}
		}
		unset($list_stocks);
	}
	$html.= '<div class="form-row">
		<div class="col-12 col-md-4 mb-2 mb-lg-0">
			<div class="d-flex align-items-cecnter gap-3">
				<div class="p-2 w-px-50 h-px-50 bg-label-danger rounded-3 text-center">
					<i class="bx fs-26 mt-1 bx-building-house"></i>
				</div>
				<div class="d-flex flex-column align-items-start">
					<p class="text-muted mb-1">Tổng quỹ ôm</p>
					<h3 class="mb-0 text-fs-20 text-main">'.$total_stocks.'</h3>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-8">
			<div id="stock_hug_chart_'.$uid.'" class="chartContainer w-100 h-px-75"></div>
			<style type="text/css">
				.apexcharts-yaxistooltip{ display:none !important} 
				.apexcharts-canvas, #stock_hug_chart_'.$uid.'{ min-height:85px !important}
			</style>
		</div>
	</div>';
	$labels = $series= $arr_titles = array();
	if(!empty($arr_blocks)){
		$field = "{$clsProperty->pkey},`property_code`";
		$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BLOCK' 
			AND `{$clsProperty->pkey}` IN (".implode(',', array_keys($arr_blocks)).")", $field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_titles[$val[$clsProperty->pkey]] = $val['property_code'];
			}
			unset($tmp);
		}
		foreach($arr_blocks as $block_id => $total_stocks){
			$labels[] = $arr_titles[$block_id];
			$series[] = $total_stocks;
		}
	}
	$callback = 'var options = {
		chart: {
			height: 110,
			type: "bar",
			toolbar: {show: !1}
		}, plotOptions: {
			bar: {
				barHeight: "60%",
				columnWidth: "50%",
				startingShape: "rounded",
				endingShape: "rounded",
				borderRadius: 3,
				distributed: !0
			}
		}, grid: {
			show: !1,
			padding: {
				top: -35,
				bottom: -10,
				left: -10,
				right: -10
			}
		}, colors: [
			config.colors.primary,
			config.colors.secondary,
			config.colors.info,
			config.colors.success,
			config.colors.warning,
			config.colors.danger,
			config.colors.black
		], tooltip: {
			custom: function({series, seriesIndex, dataPointIndex, w}) {
				return \'<div class="p-1">Tổng số căn: \'+series[seriesIndex][dataPointIndex] + \'</div>\'
			}
		}, dataLabels: {enabled: !1},
		series: [{data: ['.implode(',',$series).']}],
		legend: {show: !1},
		xaxis: {
			categories: [\''.implode('\',\' ', $labels).'\'],
			axisBorder: {show: !1},
			axisTicks: {show: !1},
			labels: {style: {fontSize: "11px"}}
		}, yaxis: {
			labels: {show: !1}
		}
	};
	var chart = new ApexCharts(document.querySelector(\'#stock_hug_chart_'.$uid.'\'), options);
	chart.render()';
	// skin=dbx: dashboard BĐH — tổng quỹ + mini-bar thuần CSS theo dự án, bỏ ApexCharts (bar cao nhất tô gold)
	$skin = Input::post('skin', '');
	if($skin == 'dbx'){
		$max_val = !empty($series) ? max(1, max($series)) : 1;
		$max_idx = !empty($series) ? array_search(max($series), $series) : -1;
		$bars = '';
		foreach($series as $idx => $val){
			$bar_h = max(4, (int) round($val / $max_val * 48));
			$bars.= '<div class="dbx-bars__col">
				<div class="dbx-bars__bar'.($idx === $max_idx ? ' dbx-bars__bar--hl' : '').'" style="height:'.$bar_h.'px"></div>
				<span class="dbx-bars__lbl">'.$labels[$idx].'</span>
			</div>';
		}
		$html = '<div class="d-flex align-items-center gap-3">
			<div class="d-flex align-items-center gap-2 flex-shrink-0">
				<span class="dbx-tile-ic"><i class="bx bxs-building-house"></i></span>
				<div>
					<div class="dbx-stat__lbl">Tổng quỹ ôm</div>
					<div class="dbx-bignum__val">'.$total_stocks.'</div>
				</div>
			</div>
			<div class="flex-fill dbx-minw0">
				<div class="dbx-bars">'.$bars.'</div>
			</div>
		</div>';
		$callback = '';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback
	)); die();
}
function dashboard_load_group_sale_overview(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	###
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', '_month');
	if($date_type == '_month'){
		$quarter = 0; // Init value
		$month  = (int) Input::post('month', date('n'));
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Init value
		$quarter  = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	###
	$cond = "`is_trash`=0 and `is_cancel`=0";
	if($date_type == '_month' && $month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
	} else if($date_type == '_half_year' && $half_year > 0){
		if($half_year == 1){
			$start_month = 1;
			$end_month = 6;
		} else if($half_year == 2){
			$start_month = 7;
			$end_month = 12;
		}
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
		$cond.= " AND (`deposit_date` BETWEEN {$start_date} AND {$end_date})";
	} else if($date_type == '_quarter' && $quarter > 0){
		if($quarter == 1){
			$start_month = 1;
			$end_month = 3;
		} else if($quarter == 2){
			$start_month = 4;
			$end_month = 6;
		} else if($quarter == 3){
			$start_month = 7;
			$end_month = 9;
		} else if($quarter == 4){
			$start_month = 10;
			$end_month = 12;
		}
		$start_time = date(sprintf('%s-%s-01 00:00', $year, $clsISO->parseNumber($start_month)));
		$end_time = date(sprintf('%s-%s-t 23:59:59', $year, $clsISO->parseNumber($end_month)));
		$cond.= " and `deposit_date` BETWEEN ".strtotime($start_time)." AND ".strtotime($end_time);
	} else {
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	$arr_blocks = array();
	$field = "{$clsProperty->pkey},`title`";
	$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BILLING_TYPE'", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_blocks[$val[$clsProperty->pkey]] = array(
				'title' => $val['title']
			);
		}
		unset($arr_block);
	}
	foreach($arr_blocks as $key => $val){
		$where = $cond." AND `billing_type`='".$key."'";
		// $dbconn->debug=true;
		$total_billings = $total_sales = $total_f1_billings = $total_f1_registed_billings = 0;
		$field = "{$clsBilling->pkey},`totalgrand`,`billing_source_id`,`contract_status_id`";
		$tmp = $clsBilling->getAll($where, $field);
		if(!empty($tmp)){
			$total_billings = count($tmp);
			foreach($tmp as $okey => $oval){
				$totalgrand = $oval['totalgrand'];
				$billing_source_id = (int) $oval['billing_source_id'];
				$contract_status_id = (int) $oval['contract_status_id'];
				$total_sales += $clsISO->convertToNumber($totalgrand);
				if($billing_source_id == _BILLING_RESOURCE_F1_ID){
					$total_f1_billings += 1;
					if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
						$total_f1_registed_billings += 1;
					}
				}
			}
			unset($tmp);
		}
		$arr_blocks[$key]['total_price'] = $total_sales;
		$arr_blocks[$key]['total_billings'] = $total_billings;
		$arr_blocks[$key]['total_f1_billings'] = $total_f1_billings;
		$arr_blocks[$key]['total_f1_registed_billings'] = $total_f1_registed_billings;
	}
	// $clsISO->print_pre($arr_blocks); die();
	// skin=dbx: dashboard BĐH — lưới 2x2 .dbx-cdt + txt_time cho chip; die() sớm nên nhánh cũ (kế toán) giữ nguyên
	$skin = Input::post('skin', '');
	if($skin == 'dbx'){
		$html = '<div class="dbx-grid2">';
		foreach($arr_blocks as $key => $val){
			$total_price = (int) $val['total_price'];
			$total_billings = (int) $val['total_billings'];
			$html.= '<div class="dbx-cdt'.($key == 'MAS' ? ' dbx-cdt--hl' : '').'">
				<div class="dbx-cdt__name">'.$val['title'].'</div>
				<div class="dbx-cdt__val">'.$clsISO->shortNumber($total_price,3,1).'</div>
				<div class="dbx-cdt__sub">'.$total_billings.' GD · '.$val['total_f1_registed_billings'].'/'.$val['total_f1_billings'].' F1 ký HĐMB</div>
			</div>';
		}
		$html.= '</div>';
		if($date_type == '_month' && $month > 0){
			$txt_time = sprintf('Tháng %s/%s', $clsISO->parseNumber($month), $year);
		} else if($date_type == '_quarter' && $quarter > 0){
			$txt_time = sprintf('Quý %s/%s', $quarter, $year);
		} else if($date_type == '_half_year' && $half_year > 0){
			$txt_time = ($half_year == 1) ? sprintf('Nửa đầu năm %s', $year) : sprintf('Nửa cuối năm %s', $year);
		} else {
			$txt_time = sprintf('Năm %s', $year);
		}
		echo json_encode(array(
			'html' => $html,
			'txt_time' => $txt_time
		), JSON_UNESCAPED_UNICODE); die();
	}
	$html = '<div class="row row-cols-3">'; $ii = 0;
	foreach($arr_blocks as $key => $val){
		$total_price = (int)$val['total_price'];
		$total_billings = (int)$val['total_billings'];
		/*$total_price *=2.5;$total_billings *=2.5;*/
		$_class = "";
		if($deviceType=='phone'){
			if($ii <= 2){
				$_class.= " border-bottom pb-2";
			} else {
				$_class.= " flex-fill pt-3";
			}
		}
		$html.= '<div class="col'.$_class.''.($ii==count($arr_blocks)-1?'':' border-end').'">
			<h6 class="mb-0 fw-semibold line-clamp-1 text-fs-14">'.$val['title'].'</h5>
			<hr class="my-2 w-px-50" />
			<h5 class="card-title fs-16 text-nowrap text-main mb-1" '.$total_price.'>
				'.$clsISO->shortNumber($total_price,3,1).'
			</h5>
			<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">'.$total_billings.'</strong> GD, <strong class="text-main">'.$val['total_f1_registed_billings'].'/'.$val['total_f1_billings'].'</strong> GD F1 ký HĐMB</small>
		</div>';
		++$ii;
	}
	$html.= '</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function dashboard_load_report_update_stock(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsAdminLog = new AdminLog();
	$clsProperty = new Property();
	###
	$end_date = time();
	$start_date = strtotime('-6 days', $end_date);
	$list_dates = array();
	for($i=$start_date; $i<= $end_date; $i = strtotime("+1 day", $i)){
		$list_dates[] = $i;
	}
	$html = '<div class="overflow-x-auto w-100">
		<table class="table table-bordered">
		<thead><tr>';
		foreach($list_dates as $val){
			$html.= '<th class="align-center text-center">'.$clsISO->convertTimeToText($val).'</th>';
		}
		$html.= '</tr></thead>';
		$total_agency = $clsProperty->countItem("`is_trash`=0 AND `property_type`='_AGENCY' AND JSON_EXTRACT(`more_information`,\"$.spreadsheetId\")<>''");
		foreach($list_dates as $val){
			$total_updated = $dbconn->getOne("SELECT COUNT(DISTINCT `target_id`) as `total_updated` 
				FROM {$clsAdminLog->tbl} WHERE`action`='update_stock' AND FROM_UNIXTIME(`date`,'%d/%m/%Y')='".date('d/m/Y', $val)."'");
			$html.= '<td class="align-center text-center"><strong class="text-main">'.$total_updated.'</strong>/<strong class="text-orange">'.$total_agency.'</strong></td>';
		}
		$html.= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
/** Dashboard admin */
function dashboard_load_stock_admin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$type = "STOCK";
	$arr_status = $clsProperty->getArraySearchByKey("_STATUS");
	###
	$keyword = Input::post('keyword');
	$agency_id = (int) Input::post('agency_id', 0);
	$page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 10);
	###
	$cond  = "`is_trash`=0 and status_id > 0 and status_id <> '"._STOCK_STATUS_NON_ID."'";
	if(!empty($keyword)) {
		$cond.=" and (`ms_code` like '%{$keyword}%')";
	}
	if($agency_id > 0) {
		$cond.= " and `agency_id`='{$agency_id}'";
	}
	$order_by = " order by `{$clsStock->pkey}` DESC,`reg_date` DESC";
	$limitCond = " limit 0,20";
	$total_record = $clsStock->countItem($cond);
	$clsPagination = new Pagination();
	$create_link = $clsPagination->pagination_ajax($total_record,$per_page,$page);
	var_dump($create_link);die;
	$list_stocks = $clsStock->getAll($cond.$order_by.$limitCond);
	if(!empty($list_stocks)){
		$arr_property_cached = $arr_projects_cached = array();
		foreach($list_stocks as $key => $val){
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
			$list_stocks[$key]['project_name'] = $arr_project_cached[$project_id];
			$list_stocks[$key]['block_name'] = $arr_property_cached[$block_id];
			$list_stocks[$key]['building_name'] = $arr_property_cached[$building_id];
			$list_stocks[$key]['status'] = $arr_status[$val['status_id']]["title"];
		}
	}
	$smarty->assign('list_stocks', $list_stocks);
	$smarty->assign('type', $type);
	$html = $core->build('dashboard'.DS.'_ajax.load_screen_admin.tpl');
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_billing_admin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$type = "BILLING";
	###
	$keyword = Input::get('keyword');
	$staff_id = (int) Input::get('staff_id', 0);
	$status_id = (int) Input::get('status_id', 0);
	$contract_status_id = (int) Input::get('contract_status_id', 0);
	$project_id = (int) Input::get('project_id', 0);
	$billing_type = (int) Input::get('billing_type', 0);
	$billing_source = (int) Input::get('billing_source', 0);
	$start_date = (int) Input::get('start_date', 0);
	$to_date = (int) Input::get('to_date', 0);
	$sort_by = Input::get('sort_by', 'reg_date');
	$smarty->assign('keyword', $keyword);
	$smarty->assign('staff_id', $staff_id);
	$smarty->assign('status_id', $status_id);
	$smarty->assign('contract_status_id', $contract_status_id);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('billing_type', $billing_type);
	$smarty->assign('billing_source', $billing_source);
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
	$smarty->assign('sort_by', $sort_by);
	###
	$cond  = "`is_trash`=0";
	if(!empty($keyword)) {
		$cond.=" and (`billing_code` like '%{$keyword}%' or `stock_code` like '%{$keyword}%')";
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
	if($billing_type > 0) {
		$cond.= " and `billing_type`='{$billing_type}'";
	}
	if($billing_source > 0) {
		$cond.= " and `billing_source_id`='{$billing_source}'";
	}
	$order_by = " order by `{$clsBilling->pkey}` DESC,`reg_date` DESC";
	$limitCond = " limit 0,20";
	$list_billings = $clsBilling->getAll($cond.$order_by.$limitCond);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		foreach($list_billings as $key => $val){
			$partner_id = $val['partner_id'];
			$project_id = $val['project_id'];
			$billing_type = $val['billing_type'];
			$billing_source_id = $val['billing_source_id'];
			$contract_status_id = $val['contract_status_id'];
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			$list_billings[$key]['more_information'] = $more_information;
			###
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
			###
			if($project_id > 0){
				if(isset($arr_projects_cached[$project_id])){
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				} else {
					$arr_projects_cached[$project_id] = $clsProject->getTitle($project_id);
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				}
			} else {
				$list_billings[$key]['poroject_name'] = "";
			}
			if(!isset($arr_property_cached[$contract_status_id])){
				$arr_property_cached[$contract_status_id] = $clsProperty->getTextColor($contract_status_id);
			}
			$list_billings[$key]['text_contract_status'] = $arr_property_cached[$contract_status_id];
		}
	}
	$smarty->assign('type', $type);
	$smarty->assign('list_billings', $list_billings);
	$html = $core->build('dashboard'.DS.'_ajax.load_screen_admin.tpl');
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_sales_overview_admin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id, $deviceType;
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year  = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', "_month");
	###
	$cond = $prev_cond = "`is_trash`=0 and `is_cancel`=0";
	if($month > 0){
		if($month == 1){
			$prev_month = 12;
			$prev_year = ($year - 1);
		} else {
			$prev_month = ($month - 1);
			$prev_year = $year;
		}
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$date_prev_my = sprintf('%s/%s', $clsISO->parseNumber($prev_month), $prev_year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
		$prev_cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_prev_my}'";
		$compare_text = sprintf('tháng %s', $date_prev_my);
	} else {
		$prev_year = ($year - 1);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$prev_cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$prev_year}'";
		$compare_text = sprintf('năm %s', $prev_year);
	}
	$block_id = vnSessionGetVar('BlockAdminId');
	//	var_dump($_SESSION);die;
	if($block_id > 0) {
		$cond .= " AND stock_code IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
		$prev_cond.= " AND stock_code IN (SELECT ms_code FROM default_stock WHERE block_id='{$block_id}')";
	}
	$total_sales = $clsBilling->sumItem("totalgrand", $cond);
	$total_billings = $clsBilling->countItem($cond);
	$total_prev_sales = $clsBilling->sumItem("totalgrand", $prev_cond);
	$total_prev_billings = $clsBilling->countItem($prev_cond);
	$html = '<div class="row">
		<div class="col-6 col-lg-5 border-end">
			<h6 class="mb-2 text-muted">Doanh số</h5>
			<h5 class="card-title fs-5 text-main mb-1">'.$clsISO->shortNumber($total_sales,3).'</h5>';
		if($total_prev_billings > 0) {
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				'.$clsHelper->getHtmlGrowth($total_sales, $total_prev_sales).'</strong> so với '.$compare_text.'</small>';
		}else{
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				+ '.$clsISO->shortNumber($total_sales,3).'</strong> so với '.$compare_text.'</small>';
		}
	$html.='</div>
		<div class="col-6 col-lg-5">
			<h6 class="mb-2 text-muted">Số lượng</h5>
			<h5 class="card-title fs-5 text-main mb-1">'.$total_billings.' GD</h5>';
		if($total_prev_billings > 0) {
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				'.$clsHelper->getHtmlGrowth($total_billings, $total_prev_billings).'</strong> so với '.$compare_text.'</small>';
		}else{
			$html.= '<small class="d-block pb-1 text-muted"><strong class="text-main text-nowrap">
				+ '.$total_billings.' GD</strong> so với '.$compare_text.'</small>';
		}
	$html .='</div>
		<div class="col-2 d-none d-lg-block pt-1 ps-0">
			<img src="'.URL_IMAGES.'/prize-light.png" height="80" class="rounded-start" alt="View Sales">
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
function dashboard_load_billing_overview_admin(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType,$dbconn; 
	$clsBilling = new Billing();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$cond = "`is_trash`=0 and `is_cancel`=0";
	$block_arrs = array(
		'_registed_hdmb' => 'Đã ký ',
		'_unregisted_hdmb' => 'Có lịch ký',
		'_not_schedule_hdmb' => 'Chưa có lịch'
	);
	$html = '<div class="row">';
	foreach($block_arrs as $key => $val){
		if($key == '_registed_hdmb'){
			if($month > 0){
				$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
				$where = "{$cond} and FROM_UNIXTIME(`contract_date`,'%m/%Y')='{$m}'";
			} else {
				$where = "{$cond} and FROM_UNIXTIME(`contract_date`,'%Y')='{$year}'";
			}
			$total_billings = $clsBilling->countItem($where." and `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
			$total_f1_billings = $clsBilling->countItem($where." and `billing_source_id`='"._BILLING_RESOURCE_F1_ID."' 
			and `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
			$total_cross_billings = $clsBilling->countItem($where." and `billing_source_id`='"._BILLING_RESOURCE_CROSS_ID."' 
			and `contract_status_id`='"._CONTRACT_STATUS_DONE_ID."'");
		} else if($key == '_unregisted_hdmb'){
			if($month > 0){
				$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
				$where = "{$cond} and FROM_UNIXTIME(`estimate_date`,'%m/%Y')='{$m}'";
			} else {
				$where = "{$cond} and FROM_UNIXTIME(`estimate_date`,'%Y')='{$year}'";
			}
			$total_billings = $clsBilling->countItem($where." and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."'");
			$total_f1_billings = $clsBilling->countItem($where." and `billing_source_id`<>'"._BILLING_RESOURCE_F1_ID."' 
			and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."'");
			$total_cross_billings = $clsBilling->countItem($where." and `billing_source_id`<>'"._BILLING_RESOURCE_CROSS_ID."' 
			and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."'");
		} else if($key == '_not_schedule_hdmb'){
			if($month > 0){
				$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
				$where = "{$cond} and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$m}'";
			} else {
				$where = "{$cond} and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
			}
			$total_billings = $clsBilling->countItem($where." and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."' 
			and `estimate_date`='0'");
			$total_f1_billings = $clsBilling->countItem($where." and `billing_source_id`<>'"._BILLING_RESOURCE_F1_ID."' 
			and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."' and `estimate_date`='0'");
			$total_cross_billings = $clsBilling->countItem($where." and `billing_source_id`='"._BILLING_RESOURCE_CROSS_ID."' 
			and `contract_status_id`<>'"._CONTRACT_STATUS_DONE_ID."' and `estimate_date`='0'");
		}
		$html.= '<div class="col-4 border-end">
			<h6 class="mb-2 text-muted">'.$val.'</h6>
			<h5 class="card-title fs-4 text-main mb-1">'.$total_billings.' GD</h5>
			<small class="d-block pb-1 text-muted">Có <strong class="text-main text-nowrap">'.$total_f1_billings.'</strong> F1, <strong class="text-main text-nowrap">'.$total_cross_billings.'</strong> lấy chéo</small>
		</div>';
	}
	$html.= '</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_billing_chart_admin(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$deviceType;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month');
	$year  = (int) Input::post('year', date('Y'));
	###
	$cond = "`is_trash`=0 and `is_cancel`='0'";
	if($month > 0){
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$m}'";
	} else {
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	}
	$list_groups = array(
		'CAO_TANG' => array(
			'title' => 'Cao tầng',
			'color' => '#28cd16'
		), 'THAP_TANG' => array(
			'title' => 'Thấp tầng',
			'color' => '#0239a1'
		), 'MWF_F1' => array(
			'title' => 'MWF F1',
			'color' => '#b31919'
		), 'MWF_CROSS' => array(
			'title' => 'MWF bán chéo',
			'color' => '#c08600'
		), 'CHO_THUE' => array(
			'title' => 'Cho thuê',
			'color' => '#1a4903'
		)
	);
	$html_points = "";
	$data = $dataPoints = $barChartData = $colorSet = array();
	foreach($list_groups as $key => $val){
		$colorSet[] = $val['color'];
		$where = $cond;
		if($key=='CAO_TANG'){
			$where.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";
		} else if($key=='THAP_TANG') {
			$where.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
		} else if($key=='MWF_F1') {
			$where.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."' and `billing_source_id`='"._BILLING_RESOURCE_F1_ID."'";
		} else if($key=='MWF_CROSS') {
			$where.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."' and `billing_source_id`='"._BILLING_RESOURCE_CROSS_ID."'";
		} else if($key == 'CHO_THUE') {
			$where.= " and `billing_type` in (".implode(',',array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID)).")";
		}
		$total_billings = $clsBilling->countItem($where);
		$dataPoints[] = array(
			'label' => $val['title'],
			'exploded' => ($key=='MWF_F1' ? true : false),
			'y' => $total_billings * 1,
			'indexLabel' => sprintf('%s GD', $total_billings)
		);
		$html_points.= '<li class="d-flex align-items-center w-100 py-1  gap-2">
			<span class="d-inline-block w-px-15 h-px-15 rounded-pill" style="background:'.$val['color'].'"></span> 
			<span>'.$val['title'].': <strong>'.$total_billings.'</strong></span>
		</li>';
	}
	$barChartData['colorSet'] = $colorSet;
	$barChartData['animationEnabled'] = true;
	// Render HTML
	$html = '<div class="form-row">
		<div class="col-8">
			<div id="'.$uid.'" class="chartContainer w-100" style="height:300px"></div>
		</div>
		<div class="col-4">
			<ul class="list-unstyled" style="padding-top:5rem;">
				'.$html_points.'
			</ul>
		</div>
	</div>';
	$data['type'] = 'pie';
	// $data['toolTipContent']='{label}: <strong>{y} GD</strong>';
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
		'drawchart' => '1',
		'barChartData' => $barChartData
	)); die();
}
function dashboard_load_calendar_admin(){
	global $smarty,$core,$clsISO,$mod,$act,$oneProfile,$deviceType;
	$uid = $clsISO->getUniqid();
	$html = '<div id="calendar_'.$uid.'"></div>';
	$callback = 'var oSettings = {
		initialView:\'week\',
		selectable: false,
		unselectAuto: false,
		header: {left: \'\', center: \'title\', right: \'prev,next\'},
		events:"'.PCMS_URL.'/index.php?mod='.$mod.'&act=load_billing_calendar&openFrom=_dashboard",
		eventRender : function(event, element, view){
			element.find(".fc-event-title").remove();
			if(parseInt(event.flag) > 0){
				var html = \'<a class="badge fc-event-badge \'+event.cls+\' cursor-pointer" onclick="view_billing(this, event)" billing_id="\'+event.billing_id+\'" title="\'+event.tooltip+\': \'+event.title+\'">\'+event.title+\'</a>\';
				element.append(html);
			}
		}
	};
	$(\'#calendar_'.$uid.'\').fullCalendar(oSettings);';
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
/*end dashboard admin */
function dashboard_load_report_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsVAT = new VAT();
	$clsProperty = new Property();	
	###
	$month = Input::post('month', 0);
	$year = Input::post('year', date('Y'));
	$vat_type = (int) Input::get("vat_type", _VAT_TYPE_VATOUT);
	$cond = "`is_trash`=0 and `vat_type`='{$vat_type}'";
	$list_briefs = array(
		'_TOTAL' => 'Tổng VAT',
		'_FINAL' => 'Đã tất toán',
		'_DEPOSIT' => 'Đã tạm ứng',
		'_UNPAID' => 'Chưa thanh toán'
	);
	$html_briefs = '<div class="form-row d-flex flex-wrap">';
	foreach($list_briefs as $key => $text){
		if($key == '_TOTAL'){
			$total = $clsVAT->sumItem("amount", $cond);
		} else if($key=='_FINAL'){
			$total = $clsVAT->sumItem("final_price", $cond);
		} else if($key == '_UNPAID'){
			$total = $clsVAT->sumItem("unpaid_price", $cond);
		} else if($key == '_DEPOSIT'){
			$total = $clsVAT->sumItem("deposit_price", $cond);
		}
		$html_briefs .= '<div class="col-6 mb-2">
			<div class="gbox gotoLink p-3">
				<div class="d-flex align-items-center justify-content-between mb-2">
					<h5 class="fs-14 mb-0">'.$text.'</h5> 
					<a class="panel-help help_pop openHelp" title="'.$text.'">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<h3 class="fs-5 mb-0 fw-bold text-main">
					<span>'.$clsISO->formatNumber2($total).'đ</span>
				</h3>
			</div>
		</div>';
	}
	$html_briefs .= '</div>';
	// Return
	echo json_encode(array(
		'html' => $html_briefs,
		'type'	=>	$type
	)); die();
}
function dashboard_load_desktop_bank_accounts(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsCache = new Cache();
	$clsProperty = new Property();
	$clsCashFund = new CashFund();
	if($clsCache->has('_bank_account_cached')){
		$list_bank_accounts = $clsCache->get('_bank_account_cached');
	} else {
		$field = "{$clsProperty->pkey},property_code,title,textcolor,bgcolor,image";
		$list_bank_accounts = $clsProperty->getAll("property_type='BANK_ACCOUNT' order by order_no ASC", $field);
		$clsCache->put('_bank_account_cached', $list_bank_accounts);
	}
	#
	$total = 0; $more_information = array();
	$oneCash = $clsCashFund->getByCond("1=1 order by `upd_date` DESC", "more_information");
	if(!empty($oneCash)){
		$more_information = $oneCash['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if(!empty($list_bank_accounts)){
			foreach($list_bank_accounts as $key => $val){
				$bank_account_id = $val[$clsProperty->pkey];
				$total_price = $core->get_field($more_information, $bank_account_id, 0);
				$total += $clsISO->processSmartNumber($total_price);
			}
		}
	} else {
		if(!empty($list_bank_accounts)){
			foreach($list_bank_accounts as $key => $val){
				$bank_account_id = $val[$clsProperty->pkey];
				$more_information[$bank_account_id] = 0;
			}
		}
	}
	$html = '<div class="col-6 col-lg-2 mb-2 text-center">
		<div class="obank bg-primary mb-2 mb-lg-0">
			<img class="FUND my-2" src="'.URL_IMAGES.'/FUND.png" width="80px" />
			<div class="fs-12 text-white fwd-bold">Tổng tiền</div>
			<hr class="my-1" />
			<div  class="text-white fw-bold">'.$clsISO->shortNumber($total).'</div>
		</div>
	</div>';
	if(!empty($list_bank_accounts)){
		foreach($list_bank_accounts as $key => $val){
			$image = $val['image'];
			$bank_account_id = $val[$clsProperty->pkey];
			$total_price = $core->get_field($more_information, $bank_account_id, 0);
			$total_price = $clsISO->processSmartNumber($total_price);
			$html.= '<div class="col-6 col-lg-2 mb-2 text-center">
				<div style="background:'.$val['bgcolor'].'" class="obank mb-2 mb-lg-0">
					<img class="'.$image.' mb-1" src="'.($image=='QTM'?URL_IMAGES.'/QTM.png':'https://api.vietqr.io/img/'.$image.'.png').'" width="80px" />
					<div class="fs-12 text-white text-nowrap fwd-bold">'.$val['title'].'</div>
					<hr class="my-1" />
					<div class="text-white fw-bold">'.$clsISO->shortNumber($total_price).'</div>
				</div>
			</div>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function soLanGap($a, $b) {
    if ($b == 0 || $b == 0) return 0;
    $ratio = $a / $b;
    if ($ratio >= 1 && $ratio < 1.5) {
        return 1;
    } elseif ($ratio >= 1.5 && $ratio < 2) {
        return 2;
    } elseif ($ratio >= 2) {
        return 3;
    } else {
        return 0;
    }
}
function dashboard_load_person_salary(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	if(in_array($role_id, array(_ROLE_GD_PROJECT, _ROLE_GD_SOP))) {
		$role_id = _ROLE_GD_SALE;
	}
	$month = (int) Input::post('month', date('m'));
	$year = (int) Input::post('year', date('Y'));
	$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$arr_staff_target = array(_ROLE_HEAD_SALE => 5, _ROLE_GD_SALE => 12);
	$arr_billing_target = array(_ROLE_HEAD_SALE => 25, _ROLE_GD_SALE => 50);
	$cond = "`t2`.`is_trash`=0 AND `t2`.`department_id`='{$department_id}' AND `t1`.`is_cancel`=0";
	if($role_id == _ROLE_HEAD_SALE){
		$team_id = (int) $oneProfile['team_id'];
		$cond.= " AND `t2`.`team_id`='{$team_id}'";
	} else if($role_id == _ROLE_GD_SALE){
	}
	$cond.= " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$date_my}'";
	$total_score = $total_f1 = $total_cross = $total_sop = 0;
	$field = "`t1`.`billing_source_id`,`t1`.`billing_type`";
	$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 
	INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` WHERE {$cond}");
	// $clsISO->print_pre($tmp); die();
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$billing_type = (int) $val['billing_type'];
			$billing_source_id = (int) $val['billing_source_id'];
			if($billing_source_id == _BILLING_RESOURCE_F1_ID && $billing_type != _BILLING_TYPE_TRANSFER_ID){
				$total_f1 += 5;
			} else if($billing_source_id == _BILLING_RESOURCE_CROSS_ID && $billing_type != _BILLING_TYPE_TRANSFER_ID){
				$total_cross += 2;
			} else if($billing_type == _BILLING_TYPE_TRANSFER_ID) {
				$total_sop += 1;
			}
		}
	}
	$total_score = $total_f1 + $total_cross + $total_sop;
	$num_target = (int) $arr_billing_target[$role_id];
	$percent_done = round($total_score/$num_target, 2) * 100;
	if($role_id == _ROLE_HEAD_SALE){
		$percent = soLanGap($total_score, $num_target);
		$salary = $percent * 5000000;
	} else if($role_id == _ROLE_GD_SALE){
		$percent = soLanGap($total_score, $num_target);
		$salary = $percent * 6000000;
	}
	$html = '<div class="d-flex flex-column gap-1 text-white">
		<div class="d-flex align-items-center text-center">
			<div class="col-6">
				<p class="text-white mb-1">Tổng điểm</p>
				<h6 class="text-fs-15 mb-0">'.$total_score.' / '.$num_target.'</h6>
			</div>
			<div class="col-6">
				<p class="text-white mb-1">% Hoàn thành</p>
				<h6 class="text-fs-15 mb-0">'.$percent_done.'%</h6>
			</div>
		</div>
		<div class="d-flex align-items-center gap-2">
			<p class="mb-0">Lương nhận: </p>
			<h4 class="mb-0 badge rounded-pill bg-label-primary text-fs-15">
				'.$clsISO->formatPrice($salary).' '.$clsISO->getRate().'
			</h4>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_load_total_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$deviceType;
	$clsStock = new Stock();
	$clsProperty  = new Property();
	$clsStockTotal  = new StockTotal();
	###
	$uid = $clsISO->getUniqid();
	$investor = Input::post('investor', "mas");
	$number_day = 20;
	if($deviceType == 'phone') {
		$number_day = 3;
	}
	#
	$lst_block = $clsProperty->getArraySearchByKey("_BLOCK");
	if($investor == "mas") {
		$arr_block = _PROJECT_BLOCK_MWF_ARRAY;
	}elseif($investor == "vin") {
		$arr_block = _PROJECT_BLOCK_VIN_ARRAY;
	}
	if(!empty($arr_block)) {
		$cond = " AND `target_id` IN (".implode(',',$arr_block).")";
	}else{
		$arr_block = array_merge(_PROJECT_BLOCK_MWF_ARRAY,_PROJECT_BLOCK_VIN_ARRAY);
		$cond = " AND `target_id` NOT IN (".implode(',',$arr_block).")";
	}
	$due_date = strtotime(date("d-m-Y 23:59:59"));
	$start_date = strtotime("-20 days", $due_date);
	$lstItem = $clsStockTotal->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'".$cond." AND `date` BETWEEN {$start_date} AND {$due_date} GROUP BY `day`,`target_id` ORDER BY `date` ASC","total as `total_invest`, FROM_UNIXTIME(`date`,'%d/%m/%Y') as  `day`");
	foreach ($lstItem as $key => $val) {
		if(isset($arr_item[$val['day']])){
			$arr_item[$val['day']] += $val["total_invest"];
		}else{
			$arr_item[$val['day']] = $val["total_invest"];
		}
	}
	###
	$data = array();
	$barChartData= $dataPoints = array();
	$barChartData['animationEnabled'] = true;
	$data['axisY'] = array(
		'title' => 'Quỹ hàng',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 0
	);
	$cond = "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}'";
	$html = '<div id="'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	for($i = 0; $i < $number_day; $i++) {
		$date = date("d/m/Y",strtotime("-".$i." days "));
		$total = $arr_item[$date];
		if(!empty($arr_item[$date])) {
			$dataPoints[] = array(
				'y' => (int)$total,
				'label' => $date,
				'indexLabel' => $total . " căn hộ"
			);
		}		
	}
	$dataPoints = array_reverse($dataPoints);
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
function dashboard_load_stock_project(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType,$oneProfile,$profile_id;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$project_id = (int) Input::post('project_id', _PROJECT_DEF_ID);
	$lst_block = $clsProperty->getArraySearchByKey("_BLOCK");
	$lst_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	###
	$lstStock = $clsStock->getAll("`is_trash`='0' AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `block_id` IN (SELECT `{$clsProperty->pkey}` FROM `{$clsProperty->tbl}` WHERE `property_type`='_BLOCK' AND `for_id`='{$project_id}' AND JSON_EXTRACT(`more_information`,\"$.on_sale\")=1) GROUP BY block_id,bedroom_id ","COUNT(`{$clsStock->pkey}`) as `total`,`bedroom_id`,`block_id`");
	$arr_block_stock = $arr_bedroom = [];
	foreach ($lstStock as $key => $val) {
		$arr_block_stock[$val['block_id']][$val['bedroom_id']] = $val["total"];
		$arr_bedroom[] = $val['bedroom_id'];
	}
	if(!empty($arr_bedroom)) {
	$html = '<div class="table-container overflow-x-auto text-nowrap no-shadow">
	<table border="0" cellspacing="0" cellpadding="0" class="table table-bordered dragable installed">
		<thead><tr>
			<th class="align-center bg-lighter h-px-40 text-center" width="3%">Phân khu</th>';
		foreach($lst_bedroom as $bedroom_id => $_oBedroom) {
			if($clsISO->checkItemInArray($bedroom_id,$arr_bedroom)) {
				$html .= '<th class="align-center bg-lighter text-center">'.$_oBedroom["title"].'</th>';	
			}			
		}
	$html.='</tr></thead>';
		$ii = 0;
		foreach($arr_block_stock as $block_id => $oneItem){
			$prop_id = $val[$clsProperty->pkey];
			$html.= '<tr>
				<td class="text-left text-nowrap">'.$lst_block[$block_id]["property_code"].'</td>';
				foreach($lst_bedroom as $bedroom_id => $_oBedroom) {
					$class_text = "";
					if(empty($oneItem[$bedroom_id])) {
						$class_text = " text-main ";
					}elseif((int)$oneItem[$bedroom_id] < 10) {
						$class_text = " text-warning ";
					}
					if($clsISO->checkItemInArray($bedroom_id,$arr_bedroom)) {
						$html .= '<td class="text-center fw-bold '.$class_text.'">'.(!empty($oneItem[$bedroom_id]) ? $oneItem[$bedroom_id] : 0).'</td>';	
					}					
				}			
			$html.='
			</tr>';
			++$ii;
		}
	$html.= '</table>
	</div>';
	}else{
		$html = '
		<div class="d-flex flex-column justify-content-center align-items-center h-100">
			<img src="'.URL_IMAGES.'/listing-empty.svg" width="100">
			<p>Danh sách trống</p>
		</div>
		';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		// 'cond' => $cond
	)); die();
}
function dashboard_load_top_search_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$due_date = time();
	$start_date = strtotime("-10 days", $due_date);
	$filter_by = Input::post('filter_by', 'price');
	$more_information = $oneProfile["more_information"];
	$k = 0; $arr_block_total_default = $arr_block_search = [];
	foreach (_PROJECT_BLOCK_MWF_ARRAY as $block_id) {
		$arr_block_total_default[$block_id] = 0;
		++$k;
	}
	$block_search = $core->get_field($more_information, "block_search", $arr_block_total_default);
	arsort($block_search);
	foreach($block_search as $key => $val) {
		if(count($arr_block_search) > 5) {
			break;
		}
		$arr_block_search[] = $key;
	}
	// $dbconn->debug = true;
	if($filter_by == 'view'){
		$field = "COUNT(`t1`.`target_id`) as `total_search`,`t2`.`stock_id`,`t2`.`ms_code`";
		$field.= ",`t2`.`bedroom_id`,`t2`.`home_direction_id`,`t2`.`more_information`,`t2`.`block_id`,`t2`.`agency_id`";
		$list_stocks = $dbconn->getAll("SELECT {$field} FROM {$clsLog->tbl} AS `t1` 
		INNER JOIN {$clsStock->tbl} AS `t2` ON `t1`.`target_id`=`t2`.`stock_id` AND (`t1`.`type`='search' OR `t1`.`type`='view_stock') 
		WHERE (`t1`.`reg_date` BETWEEN {$start_date} AND {$due_date}) AND `t2`.`is_trash`=0 AND `t2`.`status_id`>0 AND `t2`.`status_id`<>'"._STOCK_STATUS_NON_ID."' AND `t2`.`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `t2`.`block_id` IN (".implode(',',$arr_block_search).") GROUP BY `t2`.`block_id` HAVING `total_search`>0 ORDER BY `total_search` DESC limit 0,5");
	} else {
		$field = "`s`.`stock_id`,`s`.`ms_code`,`s`.`bedroom_id`,`s`.`home_direction_id`,`s`.`more_information`,`s`.`block_id`,
		`s`.`agency_id`,IF( CAST(JSON_UNQUOTE(JSON_EXTRACT(`s`.`more_information`,'$.total_price_early')) AS UNSIGNED) > 0,CAST(JSON_UNQUOTE(JSON_EXTRACT(`s`.`more_information`,'$.total_price_early')) AS UNSIGNED),`s`.`total_price_vat`) AS `total_price`";
		$list_stocks = $dbconn->getAll("SELECT * FROM (SELECT {$field}, ROW_NUMBER() OVER (PARTITION BY `s`.`block_id` ORDER BY IF(CAST(JSON_UNQUOTE(JSON_EXTRACT(`s`.`more_information`,'$.total_price_early')) AS UNSIGNED) > 0,CAST(JSON_UNQUOTE(JSON_EXTRACT(`s`.`more_information`,'$.total_price_early')) AS UNSIGNED),`s`.`total_price_vat`) ASC) AS `rn` FROM {$clsStock->tbl} AS `s` WHERE `s`.`stock_type` = '"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `s`.`status_id` > 0 AND `s`.`status_id`<>'"._STOCK_STATUS_NON_ID."' AND `s`.`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND s.`block_id` IN (".implode(',',$arr_block_search).")
		) AS `ranked` WHERE `rn` = 1 ORDER BY `total_price` ASC LIMIT 0,5");
	}
	$html = "";
	if(!empty($list_stocks)){
		$total_stocks = count($list_stocks);
		$html.= '<ul class="list-unstyled">';
		$arr_blocks = $clsProperty->getArraySearchByKey("_BLOCK");
		$arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
		$arr_direction = $clsProperty->getArraySearchByKey("_DIRECTION");
		// $clsISO->print_pre($arr_blocks); die();
		foreach($list_stocks as $key => $val){
			$ms_code = $val['ms_code'];
			$stock_id = (int) $val['stock_id'];
			$block_id = (int) $val['block_id'];
			$agency_id = (int) $val['stock_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$home_direction_id = (int) $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$total_price_vat = $core->get_field($more_information, "total_price_vat", 0);
			$total_price_early = $core->get_field($more_information, "total_price_early", 0);
			$html.= '<li class="d-flex align-items-cecnter mb-1'.($key < ($total_stocks-1) ? " pb-1 border-bottom" : "").'">
				<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
					<div class="mb-0 position-relative">
						<a href="javascript:void(0);" title="Xem thông tin căn hộ" onClick="$Core.helper.open_stock('.$stock_id.')">
							'.($key+1).'. '.$ms_code.' ('.$arr_blocks[$block_id]['property_code'].')'.($agency_id==_AGENCY_FH_ID?'<sup class="text-yellow">ĐQ</sup>':'').'</a>							
						<div class="d-flex align-items-center gap-2 text-fs-12">
							<span class="d-flex gap-1">
								<i class="re__icon-bedroom--sm"></i> 
								'.$arr_bedroom[$bedroom_id]["title"].'
							</span>
							<span class="d-flex gap-1">
								<i class="re__icon-ying-yang--xl"></i>
								'.$arr_direction[$home_direction_id]["title"].'
							</span>
							'.($filter_by=='view' ? '<span class="d-flex gap-1">
								<i class="re__icon-eye-open--xl"></i>
								'.$val['total_search'].'
							</span>' : '').'
						</div>
					</div>
					<div class="">
						<h6 class="mb-0 text-main">'.$clsISO->shortNumber($total_price_vat).'</h6>
						<small>'.$clsISO->shortNumber($total_price_early).' (TTS)</small>
					</div>
				</div>
			</li>';
		}
		$html.= '</ul>';
		unset($list_stocks);
	} else {
		$html = '<div class="d-flex flex-column justify-content-center align-items-center">
			<img src="'.URL_IMAGES.'/listing-empty.svg" width="100">
			<p>Danh sách trống</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function dashboard_addTargetSales(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
    $clsProfile = new Profile();
    $clsProperty = new Property();
    $clsBilling  = new Billing();
	#
	$msg = "_error";
	$currentNow = time();
	$current_month = date('n');
	$current_year = date('Y');
	$uid = $clsISO->getUniqid();
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$is_sale_dir = $clsISO->checkPermissionGroup('SALE_DIRECTOR') ? 1 : 0;
	$is_regional_dir = $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') ? 1 : 0;
	#
	$action = Input::post("action","_OPEN");
	$key_current = sprintf('%s_%s', $current_month, $current_year);
	$more_information = $oneProfile["more_information"];
	$target_sales = $core->get_field($more_information, "target_sales", []);
	if($is_sale_dir || $is_regional_dir){
		$more_dep = $clsProperty->getOneField('more_information', $department_id);
		$more_dep = $clsISO->to_array_json($more_dep);
		$target_dep_sales = $core->get_field($more_dep, "target_sales", []);
	}
	#
	$html = "";
	if($action == "_OPEN") {
		$target_config = $core->get_field($target_sales, "target_config", array(
			'target_type' => 'all_month',
			'quantity' => 0, 
			'amount' => 0
		));
		if($is_sale_dir == 1 || $is_regional_dir == 1){
			$target_dep_month_config = $core->get_field($target_dep_sales, $key_current, array(
				'target_type' => 'all_month',
				'quantity' => 0, 
				'amount' => 0
			));
			$target_dep_config = $core->get_field($target_dep_sales, "target_config", array(
				'target_type' => 'all_month',
				'quantity' => 0, 
				'amount' => 0
			));
			$smarty->assign("target_dep_config", $target_dep_config);
			$smarty->assign("target_dep_month_config", $target_dep_month_config);
			// $clsISO->print_pre($target_dep_config); die();
		}
		$titlePage = '';
		if($is_sale_dir == 1) $titlePage = " &amp; phòng kinh doanh";
		if($is_regional_dir == 1) $titlePage = "&amp; vùng kinh doanh";
		$smarty->assign("uid", $uid);
		$smarty->assign("ym", $ym);
		$smarty->assign("titlePage", $titlePage);
		$smarty->assign("target_config", $target_config);
		$smarty->assign("is_sale_dir", $is_sale_dir);
		$smarty->assign("is_regional_dir", $is_regional_dir);
		// Return
		$html = $core->build('dashboard'.DS.'_ajax.open_target.tpl');	
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html
		)); die();
	}else if($action == "_SAVE") {
		$target_type = Input::post('target_type', "only_month");
		$target_config = Input::post("target_config", array());
		$result = false;
		if($is_sale_dir == 1 || $is_regional_dir == 1){
			// Department
			$target_dep_config = Input::post('target_dep_config', array());
			if($target_dep_config['target_type'] == 'only_month'){
				$target_dep_sales[$key_current] = $target_dep_config;
			} else if($target_dep_config['target_type'] == 'all_month') {
				$quantity = round($target_dep_config['quantity'] / 12);
				$amount = round($clsISO->processSmartNumber($target_dep_config['amount']) / 12);
				for($i=$current_month; $i<=12; $i++){
					$key = sprintf('%s_%s', $i, $current_year);
					$target_dep_sales[$key] = ['quantity' => $quantity, 'amount' => $amount];
				}
			}
			$target_dep_sales['target_config'] = $target_dep_config;
			$more_dep["target_sales"] = $target_dep_sales;
			if($clsProperty->updateOne($department_id, array(
				'more_information' => json_encode($more_dep, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
			// Personal
			if($target_config['target_type'] == 'only_month'){
				$target_sales[$key_current] = $target_config;
			} else if($target_config['target_type'] == 'all_month') {
				$quantity = round($target_config['quantity'] / 12);
				$amount = round($clsISO->processSmartNumber($target_config['amount']) / 12);
				for($i=$current_month; $i<=12; $i++){
					$key = sprintf('%s_%s', $i, $current_year);
					$target_sales[$key] = ['quantity' => $quantity, 'amount' => $amount];
				}
			}
			$target_sales['target_config'] = $target_config;
			$more_information["target_sales"] = $target_sales;
			if($clsProfile->updateOne($profile_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
				$result = true;
			}
		} else {
			if($target_config['target_type'] == 'only_month'){
				$target_sales[$key_current] = $target_config;
			} else if($target_config['target_type'] == 'all_month') {
				$quantity = round($target_config['quantity'] / 12);
				$amount = round($clsISO->processSmartNumber($target_config['amount']) / 12);
				for($i=$current_month; $i<=12; $i++){
					$key = sprintf('%s_%s', $i, $current_year);
					$target_sales[$key] = ['quantity' => $quantity, 'amount' => $amount];
				}
			}
			$target_sales['target_config'] = $target_config;
			$more_information["target_sales"] = $target_sales;
			if($clsProfile->updateOne($profile_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
				$result = true;
			}
		}
		// Return
		echo json_encode(array(
			'result' => $result,
			'msg' => $msg,
		)); die();
	}	
}
function dashboard_load_target_sales(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
    $clsProfile = new Profile();
    $clsProperty = new Property();
    $clsBilling  = new Billing();
    $smarty->assign('clsProfile', $clsProfile);
    $smarty->assign('clsProperty', $clsProperty);
    $smarty->assign('clsCampaign', $clsCampaign);
	#
	$uid = $clsISO->getUniqid();
	$department_id = (int)Input::post('department_id', 0);
	$my = Input::post('month', date("Y-m"));
	$tmp = @explode("-",$my);
	$month = (int) $tmp[1];
	$year = (int) $tmp[0];
	$key = sprintf('%s_%s',$month, $year);
	$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$my}'";
	if(!empty($department_id)) {
		$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		$oneDepartment = $arr_deparment_cached[$department_id];
		$more_information = $oneDepartment["more_information"];
		$is_business_area = $core->get_field($more_information, "is_business_area", 0);
		$target_sales = $core->get_field($more_information, "target_sales", []);
		$target_config = $core->get_field($target_sales, $key, []);
		$listStaff = $clsProfile->getProfileDep($department_id, 1, "active");
		$cond.= " AND `staff_id` IN (".implode(',', array_keys($listStaff)).")";
		if($clsISO->_DEV()){
//			$clsISO->print_pre($more_information);die;
		}
		if(!empty($is_business_area)) {
			$title_box = "Mục tiêu {$oneDepartment["title"]}";
			if($clsISO->_DEV()){
				$lstDepChild = $clsISO->buildTree($arr_deparment_cached,$department_id,"property_id");
				$arr_not_target = [];
				foreach ($lstDepChild as $k => $val) {
					$more_child = $val["more_information"];
					$target_sales_child = $core->get_field($more_child, "target_sales", []);
					$target_config_child = $core->get_field($target_sales_child, $key, []);
					if(empty($target_config_child)) {
						$arr_not_target[] = $val["title"];
					}
				}
				$txt_not_target = "";
				if(!empty($arr_not_target)) {
					$txt_not_target = "Phòng <strong>" . implode(", ",$arr_not_target) . "</strong> chưa thiết lập mục tiêu kinh doanh tháng {$month}/{$year}";
				}
				$smarty->assign("txt_not_target",$txt_not_target);
			}
		} else {
			$title_box = "Mục tiêu PKD {$oneDepartment["title"]}";
		}
	}else{
		$more_information = $oneProfile["more_information"];
		$target_sales = $core->get_field($more_information, "target_sales", []);
		$target_config = $core->get_field($target_sales, $key, []);
		// $clsISO->print_pre($key); die();
		$cond.= " AND `staff_id`='{$profile_id}'";
		$title_box = "Mục tiêu của tôi";
	}	
	$data = $barChartData = array();
	if(!empty($target_config)) {
		$lstBilling = $clsBilling->getAll($cond);
		// $clsISO->print_pre($lstBilling);die;
		$arr_billing_date = [];
		$total_amount = 0;
		if(!empty($lstBilling)) {
			foreach($lstBilling as $key => $val) {
				if(!isset($arr_billing_date[date("d/m/Y",$val["deposit_date"])])) {
					$arr_billing_date[date("d/m/Y",$val["deposit_date"])] = (int)$val["totalgrand"];
				}else{
					$arr_billing_date[date("d/m/Y",$val["deposit_date"])] += (int)$val["totalgrand"];
				}
				$total_amount += (int)$val["totalgrand"];
			}
		}
		$target_quantity = $core->get_field($target_config, "quantity", 0);
		$target_amount = $core->get_money_field($target_config, "amount", 0);
		$total_billings = count($lstBilling);
		$percent_qty = (!empty($target_quantity)) ? ceil($total_billings*100/$target_quantity) : (!empty($total_billings) ? 100 : 0);
		$percent_amount = (!empty($target_amount)) ? ceil($total_amount*100/$target_amount) : (!empty($total_amount) ? 100 : 0);
		$unmet_target_price = ($target_amount - $total_amount > 0) ? $target_amount - $total_amount : 0;
		$unmet_target_quantity = ($target_quantity - $total_billings > 0) ? $target_quantity - $total_billings : 0;
		$smarty->assign("total_billings",$total_billings);
		$smarty->assign("total_amount",$total_amount);
		$smarty->assign("target_quantity",$target_quantity);
		$smarty->assign("target_amount",$target_amount);
		$smarty->assign("percent_qty",$percent_qty);
		$smarty->assign("unmet_target_price",$unmet_target_price);
		$smarty->assign("unmet_target_quantity",$unmet_target_quantity);
		$smarty->assign("department_id",$department_id);
		$smarty->assign("is_business_area",$is_business_area);
		$html = $core->build('dashboard'.DS.'_ajax.load_target_sales.tpl');	
		$endMonth   = date('d', strtotime('last day of ' . $year . "-" .$clsISO->parseNumber($month)));	
		$dataTargets = $dataPoints = array();
		$barChartData['animationEnabled'] = true;
		$barChartData['toolTip'] = array(
			'shared' => true,
			'reversed' => true
		);
		$barChartData['axisY'] = array(
			"valueFormatString" => "#,##0,,,.#",
			"suffix" => " tỷ"
		);
		$key = sprintf('%s_%s', $month, $year);
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$target_a_day = $total_billings = 0;
		if($total_amount == 0 ) {
			$target_a_day = ceil($target_amount/$endMonth);
		}
		$check = $target = 0;
		for($day=1; $day<=$endMonth; $day++){
			$dmy = sprintf('%s/%s/%s', $clsISO->parseNumber($day), $clsISO->parseNumber($month), $year);
			$total_billings += !empty($arr_billing_date[$dmy]) ? (int)$arr_billing_date[$dmy] : 0;
			$dataPoints[] = array(
				'label' => $day,
				'y' => (int) $total_billings
			);
			$target += $target_a_day;
			if($total_amount > 0 && $target_amount > 0 && $total_billings > 0 && $day<$endMonth && $check == 0) {				
				$target_a_day = ceil(($target_amount-$total_billings)/($endMonth - $day + 1));
				$target += $total_billings;
				$check = 1;
			}
			$dataTargets[] = array(
				'label' => $day,
				'y' => ($endMonth > $day) ? (($target_amount > 0 && $target > $target_amount) ? $target_amount : $target) : $target_amount
			);
		}
		$html.= '<div class="chartContainer">
			<div id="'.$uid.'" class="h-px-250"></div>
		</div>';
		$barChartData['data'] = array(
			array(
				"type"  => "line",
				// "indexLabel" => "{y}",
				"name"	=> "Hoàn thành",
				"color"	=> "#359104",
				"showInLegend" => true,
				"dataPoints" => $dataPoints
			), array(
				"type"  => "line",
				// "indexLabel" => "{y}",
				"color"	=> "#808080",
				"name"  => "Mục tiêu",
				"showInLegend" => true,
				"lineDashType" => "dash",
				"dataPoints" => $dataTargets
			)
		);
		$drawchart = 1;
	}else{		
		if($clsISO->checkPermissionGroup("REGIONAL_DIRECTOR") && !empty($department_id) && empty($is_business_area)) {
			$html = '<div class="d-flex flex-column align-items-center mb-3">
				<div class="d-flex justify-content-center mb-2">
					<img class="w-75" src="'.URL_IMAGES.'/backgrounds/muc-tieu.jpg" />
				</div>
				<div class="d-flex justify-content-center align-items-center mb-2">
					<div class="xs:w-100 text-muted text-center">Phòng '.$oneDepartment["title"].' chưa được thiết lập tiêu tháng '.$month.'/'.$year.'</div>
				</div>
			</div>';
		}else{
			$html = '<div class="d-flex flex-column align-items-center mb-3">
				<div class="d-flex justify-content-center mb-2">
					<img class="w-75" src="'.URL_IMAGES.'/backgrounds/muc-tieu.jpg" />
				</div>
				<div class="d-flex justify-content-center align-items-center mb-2">
					<div class="xs:w-100 text-muted text-center">Người thành công là người có mục tiêu rõ ràng!<br />
					Bạn chưa thiết lập tiêu tháng '.$month.'/'.$year.'</div>
				</div>
				<button data-toggle="ripple" type="button" class="btn btn-outline-danger" title="Cài đặt mục tiêu" 
					onclick="$Core.dashboard.addTargetSales(this,event)" action="_OPEN">
					<i class="bx bx-cog"></i> Thiết lập mục tiêu
				</button>
			</div>';
		}
		$drawchart = 0;
	}
    // Return
    echo json_encode(array(
		'uid' => $uid,
        'html' => $html,
        'title_box' => $title_box,
		'drawchart' => $drawchart,
		'multichart' => 1,
		'barChartData' => $barChartData
    )); die();
}
function dashboard_billing_me(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling  = new Billing();
	$clsProject  = new Project();
	$smarty->assign('clsProfile', $clsProfile);
	$html = '';
	$field = "{$clsBilling->pkey},`totalgrand`,`stock_code`,`deposit_date`,`agree_date`,`contract_date`,`agree_status_id`,`contract_status_id`,`project_id`,`more_information`";
	$list_billings = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`=0 AND `staff_id`='{$profile_id}' ORDER BY `deposit_date` DESC limit 0,6",$field);
	if(!empty($list_billings)){
		$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
		$arr_cache_project = $clsProject->getListProject();
		foreach($list_billings as $key => $val){
			$agree_date = (int) $val['agree_date'];
			$contract_date = (int) $val['contract_date'];
			$agree_status_id = (int) $val['agree_status_id'];
			$contract_status_id = (int) $val['contract_status_id'];
			$more_information = $clsISO->to_array_json($val["more_information"]);
			$block_id = !empty($more_information["block_id"]) ? $more_information["block_id"] : 0;
			$project_id = !empty($val["project_id"]) ? $val["project_id"] : 0;
			$oneBlock = !empty($block_id) ? $arr_block[$block_id] : array();
			// Du an: neu block la project thi lay title block, khong thi lay title project
			$project_name = '';
			$block_more = (!empty($oneBlock) && isset($oneBlock["more_information"])) ? $oneBlock["more_information"] : array();
			if(is_string($block_more)){ $block_more = $clsISO->to_array_json($block_more); }
			if(!empty($block_more["is_project"])){
				$project_name = $oneBlock["title"];
			} else if(!empty($arr_cache_project[$project_id])){
				$project_name = $arr_cache_project[$project_id]["title"];
			}
			$block_name = !empty($oneBlock["title"]) ? $oneBlock["title"] : '';
			// Loai HDMB/VBTT suy tu ngay hop dong / thoa thuan
			if($contract_date > 0){
				$type_badge = '<span class="sign-bdg sign-bdg--hdmb">HĐMB</span>';
			} else if($agree_date > 0){
				$type_badge = '<span class="sign-bdg sign-bdg--vbtt">VBTT</span>';
			} else {
				$type_badge = '<span class="sign-muted">—</span>';
			}
			// Trang thai ky (giu nguyen logic cu)
			$status = '<span class="badge bg-label-danger">Chưa ký HĐMB</span>';
			if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
				$status = '<span class="badge bg-label-success">Đã ký HĐMB</span>';
			} else if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
				if($contract_date > 0){
					$status = '<span class="badge bg-label-primary">Ngày ký HĐMB: '.$clsISO->convertTimeToText($contract_date).'</span>';
				} else {
					$status = '<span class="badge bg-label-success">Đã ký VBTT</span>';
				}
			} else if($agree_date > 0 && $agree_status_id != _CONTRACT_STATUS_AGREE_SIGNED_ID){
				$status = '<span class="badge bg-label-primary">Ngày ký VBTT: '.$clsISO->convertTimeToText($agree_date).'</span>';
			}
			$stock_code = htmlspecialchars($val["stock_code"], ENT_QUOTES);
			$html .= '<tr class="sign-row">
				<td><a class="sign-code" billing_id="'.$val[$clsBilling->pkey].'" onClick="view_billing(this,event)">'.$stock_code.'</a></td>
				<td class="text-muted">'.$clsISO->formatDate($val['deposit_date'],3).'</td>
				<td>'.($project_name !== '' ? '<span class="sign-proj">'.htmlspecialchars($project_name, ENT_QUOTES).'</span>' : '<span class="sign-muted">—</span>').'</td>
				<td class="text-muted">'.($block_name !== '' ? htmlspecialchars($block_name, ENT_QUOTES) : '—').'</td>
				<td class="text-center">'.$type_badge.'</td>
				<td class="text-end"><span class="text-main fw-bold">'.$clsISO->shortNumber($val['totalgrand']).'</span></td>
				<td class="text-center">'.$status.'</td>
			</tr>';
		}
	} else {
		$html = '<tr><td colspan="7" class="border-0 bg-transparent">
			<div class="dbx-empty">
				<span class="dbx-empty__ic"><i class="bx bx-receipt"></i></span>
				<div class="dbx-empty__t">Bạn chưa có giao dịch</div>
				<div class="dbx-empty__s">Các giao dịch gần đây của bạn sẽ hiện ở đây</div>
			</div>
		</td></tr>';
	}
	echo json_encode(array('html' => $html)); die();
}
function dashboard_load_search_history(){
	global $core,$dbconn,$mod,$clsISO,$oneProfile,$profile_id,$deviceType;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$pid = (int) $profile_id;
	// 10 can xem gan nhat cua chinh minh (dedupe theo stock, moi nhat truoc)
	$field = "`s`.`stock_id`,`s`.`ms_code`,`s`.`block_id`,`s`.`bedroom_id`,`s`.`home_direction_id`,`s`.`more_information`,`s`.`agency_id`,`s`.`status_id`,MAX(`l`.`reg_date`) AS `last_view`";
	$sql = "SELECT {$field} FROM ".DB_PREFIX."log AS `l`
		INNER JOIN {$clsStock->tbl} AS `s` ON `s`.`stock_id`=`l`.`stock_id`
		WHERE `l`.`type`='view_stock' AND `l`.`user_id`='{$pid}' AND `l`.`stock_id`>0 AND `s`.`is_trash`=0
		GROUP BY `s`.`stock_id` ORDER BY `last_view` DESC LIMIT 0,10";
	$list = $dbconn->getAll($sql);
	$html = '';
	if(!empty($list)){
		$arr_blocks = $clsProperty->getArraySearchByKey("_BLOCK");
		$arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
		$arr_direction = $clsProperty->getArraySearchByKey("_DIRECTION");
		$arr_status = $clsProperty->getArraySearchByKey("_STATUS");
		foreach($list as $val){
			$stock_id = (int) $val['stock_id'];
			$block_id = (int) $val['block_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$direction_id = (int) $val['home_direction_id'];
			$agency_id = (int) $val['agency_id'];
			$status_id = (int) $val['status_id'];
			$more = $clsISO->to_array_json($val['more_information']);
			$price = $core->get_field($more, 'total_price_vat', 0);
			$block = !empty($arr_blocks[$block_id]) ? $arr_blocks[$block_id] : array();
			$block_name = !empty($block['title']) ? $block['title'] : (!empty($block['property_code']) ? $block['property_code'] : '');
			$bedroom = !empty($arr_bedroom[$bedroom_id]['title']) ? $arr_bedroom[$bedroom_id]['title'] : '';
			$direction = !empty($arr_direction[$direction_id]['title']) ? $arr_direction[$direction_id]['title'] : '';
			$spec = trim($bedroom.(($bedroom !== '' && $direction !== '') ? ' · ' : '').$direction);
			// trang thai can
			$st_label = 'Còn hàng'; $st_cls = 'bg-label-success';
			if($status_id == _STOCK_STATUS_SOLD_ID){ $st_label = 'Đã bán'; $st_cls = 'bg-label-danger'; }
			else if($status_id == _STOCK_STATUS_LOCK_ID){ $st_label = 'Quỹ khoá'; $st_cls = 'bg-label-warning'; }
			else if($status_id == _STOCK_STATUS_NON_ID){ $st_label = 'Không có căn'; $st_cls = 'bg-label-secondary'; }
			else if($status_id > 0 && !empty($arr_status[$status_id]['title'])){ $st_label = $arr_status[$status_id]['title']; $st_cls = 'bg-label-primary'; }
			$dq = ($agency_id == _AGENCY_FH_ID) ? ' <sup class="text-warning fw-bold">ĐQ</sup>' : '';
			$ms_code = htmlspecialchars($val['ms_code'], ENT_QUOTES);
			$html .= '<tr class="sign-row">
				<td><a class="sign-code" href="javascript:void(0)" onClick="$Core.helper.open_stock('.$stock_id.')" title="Xem thông tin căn">'.$ms_code.'</a>'.$dq.'</td>
				<td>'.($block_name !== '' ? '<span class="sign-proj">'.htmlspecialchars($block_name, ENT_QUOTES).'</span>' : '<span class="sign-muted">—</span>').'</td>
				<td class="text-muted">'.($spec !== '' ? htmlspecialchars($spec, ENT_QUOTES) : '—').'</td>
				<td class="text-end"><span class="text-main fw-bold">'.$clsISO->shortNumber($price).'</span></td>
				<td class="text-center"><span class="badge '.$st_cls.'">'.$st_label.'</span></td>
				<td class="text-end text-muted">'.$clsISO->getTimeAgo((int)$val['last_view']).'</td>
			</tr>';
		}
	} else {
		$html = '<tr><td colspan="6" class="border-0 bg-transparent">
			<div class="dbx-empty">
				<span class="dbx-empty__ic"><i class="bx bx-search-alt"></i></span>
				<div class="dbx-empty__t">Chưa có lịch sử tra cứu</div>
				<div class="dbx-empty__s">Các căn bạn xem gần đây sẽ hiện ở đây</div>
			</div>
		</td></tr>';
	}
	echo json_encode(array('html' => $html)); die();
}
function dashboard_open_config_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$uid = $clsISO->getUniqid();
	$gId = Input::post('gId', $uid);
	##
	$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
	$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
	$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\")=1 AND JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\") LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_EXTRACT(`more_information`,\"$.is_booking\")=1 AND JSON_EXTRACT(`more_information`,\"$.project_admins_slash\") LIKE '%|{$profile_id}|%')");
	$smarty->assign('uid', $uid);
	$smarty->assign('gId', $gId);
	$smarty->assign('arr_projects', $arr_projects);
	// Return
	$html = $core->build('dashboard'.DS.'_ajax.open_config_project.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function dashboard_save_config_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	##
	$msg = "_error";
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$more_information = $oneProfile['more_information'];
	// $clsISO->print_pre($more_information); die();
	$more_information['project_dir_current'] = array(
		'project_id' => $project_id,
		'block_id' => $block_id
	);
	// $clsISO->print_pre($more_information); die();
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'project_id' => $project_id,
		'block_id' => $block_id
	)); die();
}
function dashboard_load_revenue_chart(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '_month');
	$department = Input::post('department', '');
	$staff_id = (int) Input::post('profile_id', 0);
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
	$start_time = strtotime(sprintf('01-01-%s', $year));
	$end_time = strtotime(sprintf('%s-%s-%s', $end_day, 12, $year));
	###
	$lstProjectMas = $clsProperty->getAllCache("JSON_EXTRACT(`more_information`,\"$.on_sale\") ='1' AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' ");
	$arr_project_mas = [];
	foreach ($lstProjectMas as $key => $val) {
		if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_MSQ_ARRAY)) {
			if(!isset($arr_project_mas["_PROJECT_BLOCK_MSQ_ARRAY"])) {
				$arr_project_mas["_PROJECT_BLOCK_MSQ_ARRAY"]["property_code"] = "MSQ";
			}
		}else if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_CSD_ARRAY)) {
			if(!isset($arr_project_mas["_PROJECT_BLOCK_CSD_ARRAY"])) {
				$arr_project_mas["_PROJECT_BLOCK_CSD_ARRAY"]["property_code"] = "CSĐ";
			}
		}else if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_SUNSHINE_ARRAY)) {
			if(!isset($arr_project_mas["_PROJECT_BLOCK_SUNSHINE_ARRAY"])) {
				$arr_project_mas["_PROJECT_BLOCK_SUNSHINE_ARRAY"]["property_code"] = "SLC";
			}
		}else if($clsISO->checkItemInArray($val[$clsProperty->pkey],_PROJECT_BLOCK_MIK_ARRAY)) {
			if(!isset($arr_project_mas["_PROJECT_BLOCK_MIK_ARRAY"])) {
				$arr_project_mas["_PROJECT_BLOCK_MIK_ARRAY"]["property_code"] = "MIK";
			}
		}else{
			$arr_project_mas[$val[$clsProperty->pkey]] = $val;
		}
	}
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `billing_type`<>'"._BILLING_TYPE_SOP_ID."' AND (`deposit_date` BETWEEN '{$start_time}' AND '{$end_time}')";	
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	if($staff_id == 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT'))){
		if($department != "") {
			if($department == "OTHER") {
				$list_dep_notin = array();
				$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_DEPARTMENT' 
					AND `parent_id`='"._DEPARTMENT_SALE_ID."'", $clsProperty->pkey);
				if(!empty($tmp)){
					foreach ($tmp as $key => $val) {
						$list_dep_notin[] = $val[$clsProperty->pkey];
					}
				}
				$cond.= " and `staff_id` in (
					select `{$clsProfile->pkey}` from ".$clsProfile->tbl." 
					where `department_id` NOT IN (".implode(',',$list_dep_notin)."))";
			}else{
				$cond.= " and `staff_id` in (
					select `profile_id` from ".$clsProfile->tbl." 
					where (`department_id`='{$department}' or `list_department_id` like '%|{$department}|%'))";
			}
		}	
		if(!empty($staff_id)) {
			$cond .= " AND `staff_id`='".$staff_id."'";
		}
	} else {
		$cond .= " AND `staff_id`='".$staff_id."'";
	}
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['dataPointMaxWidth'] = 60;
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	$barChartData['axisY2'][] = array(
		'title' => 'SL giao dịch(GD)',
		'titleFontColor' => '#C00000',
		'titleFontSize' => '16',
		'lineColor' => '#C00000',
		'labelFontColor' => '#C00000',
		'tickColor' => '#C00000',
		'maximum' => 400
	);
	foreach($arr_project_mas as $key => $val){
		$total_billings = $total_sales = 0;
		$field = "{$clsBilling->pkey},`totalgrand`";
		if($key == "_PROJECT_BLOCK_MSQ_ARRAY") {
			$list_billings = $clsBilling->getAll("{$cond} and JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',_PROJECT_BLOCK_MSQ_ARRAY).")", $field);
		}else if($key == "_PROJECT_BLOCK_CSD_ARRAY") {
//			$dbconn->debug=true;
			$list_billings = $clsBilling->getAll("{$cond} and JSON_EXTRACT(`more_information`,\"$.block_id\") IN (".implode(',',_PROJECT_BLOCK_CSD_ARRAY).")", $field);
//			$clsISO->print_pre($list_billings);die;
		}else{
			$list_billings = $clsBilling->getAll("{$cond} and JSON_EXTRACT(`more_information`,\"$.block_id\")='".$val[$clsProperty->pkey]."'", $field);
		}
		if(!empty($list_billings)){
			$total_billings = count($list_billings);
			foreach($list_billings as $key => $v_billing){
				$totalgrand = $clsISO->convertToNumber($v_billing['totalgrand']);
				$total_sales += $totalgrand;
			}
			unset($list_billings);
		}
		$dataPoints[] = array(
			'label'	=> $val["property_code"],
			'y'	=> $total_sales*1,
			'indexLabel' => $clsISO->shortNumber($total_sales)
		);
		$dataTotalPoints[] = array(
			'label'	=> $val["property_code"],
			'y' => $total_billings*1,
			'indexLabel' => (string) $total_billings."GD"
		);
	}
//	$clsISO->print_pre($dataPoints);die;
	$barChartData['data'] = array(
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataTotalPoints
		),
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
/**=========DASH BOARD SALE========**/
function dashboard_sale(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsNews = new News();
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsTraining = new Training();
	$clsGroupProfile = new GroupProfile();
	$clsCampaign = new Campaign();
	#
	$list_preloaders = $list_months = $list_years = array();
	for($i=0; $i<=50; $i++){
		$list_preloaders[] = $i;
	}
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$lst_department = $clsProperty->getArraySearchByKey("_DEPARTMENT",0,_DEPARTMENT_SALE_ID);
	$list_campaigns = $clsCampaign->getAll("`campaign_type`='_campaign' and (`user_id`='{$profile_id}' OR `use_globe`=1) order by `reg_date` DESC", "{$clsCampaign->pkey},title");
	$smarty->assign('uid', $uid);
	$smarty->assign('list_campaigns', $list_campaigns);
	$smarty->assign('lst_department', $lst_department);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$smarty->assign('list_preloaders', $list_preloaders);
    /*=============Title & Description Page==================*/
	$title_page = "Tổng quan sale";
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function dashboard_load_total_dashboard(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsShare = new Share();
	$clsBilling = new Billing();
	#
	$date_type = Input::post('date_type', "_month");
	if($date_type == '_month'){
		$quarter = 0; // Default value
		$month = (int) Input::post('month', 0);
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Default value
		$quarter = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	##
	$msg = "_error";
	$total_billing = $total_sale = $total_customer = $total_followups = $total_share_customer = $total_share = $total_share_waiting = 0;
	$cond_billing = "`staff_id`='{$profile_id}' AND `is_cancel`='0'";	
	$cond_customer = "`admin_id`='{$profile_id}' AND `status_id`<>'"._CRM_STATUS_TRASH_ID."'";	
	$cond_followUp = "`followup_type`='_crm' AND `admin_id`='{$profile_id}'";	
	$cond_share = "`share_type`='share' AND `user_id`='{$profile_id}'";	
	if($date_type == '_month' && $month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);		
		$cond_billing .= " AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$date_my}'";
		$cond_customer .= " AND (FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}' OR FROM_UNIXTIME(`time_receipt`,'%m/%Y')='{$date_my}')";
		$cond_followUp .= " AND FROM_UNIXTIME(`date_id`,'%m/%Y')='{$date_my}'";
		$cond_share .= " AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'";
	} else if($date_type == '_quarter' && $quarter > 0){		
		$cond_billing .= " AND QUARTER(FROM_UNIXTIME(`deposit_date`))='{$quarter}' AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$cond_customer .= " AND ((QUARTER(FROM_UNIXTIME(`reg_date`))='{$quarter}' AND FROM_UNIXTIME(`reg_date`,'%Y')='{$year}') OR (QUARTER(FROM_UNIXTIME(`time_receipt`))='{$quarter}' AND FROM_UNIXTIME(`time_receipt`,'%Y')='{$year}'))";
		$cond_followUp .= " AND QUARTER(FROM_UNIXTIME(`date_id`))='{$quarter}' AND FROM_UNIXTIME(`date_id`,'%Y')='{$year}'";
		$cond_share .= " AND QUARTER(FROM_UNIXTIME(`reg_date`))='{$quarter}' AND FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
	} else if($date_type == '_half_year' && $half_year > 0){
		if($half_year == 1){
			$start_month = 1;
			$end_month = 6;
			$txt_time = sprintf("Nửa đầu năm %s",$year);
		} else if($half_year == 2){
			$start_month = 7;
			$end_month = 12;
			$txt_time = sprintf("Nửa cuối năm %s",$year);
		}
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
		$cond_billing .= " AND (`deposit_date` BETWEEN {$start_date} AND {$end_date})";
		$cond_customer .= " AND ((`reg_date` BETWEEN {$start_date} AND {$end_date}) OR (`time_receipt` BETWEEN {$start_date} AND {$end_date}))";
		$cond_followUp .= " AND (`date_id` BETWEEN {$start_date} AND {$end_date})";
		$cond_share .= " AND (`reg_date` BETWEEN {$start_date} AND {$end_date})";		
	} else {
		$cond_billing .= " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
		$cond_customer .= " AND (FROM_UNIXTIME(`reg_date`,'%Y')='{$year}' OR FROM_UNIXTIME(`time_receipt`,'%Y')='{$year}')";
		$cond_followUp .= " AND FROM_UNIXTIME(`date_id`,'%Y')='{$year}'";
		$cond_share .= " AND FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
	}
	$lstBilling = $clsBilling->getByCond($cond_billing, "COUNT(`{$clsBilling->pkey}`) AS `total_billing`, SUM(`totalgrand`) AS `total_sale`");
	if(!empty($lstBilling)) {
		$total_billing = $lstBilling["total_billing"];
		$total_sale = $clsISO->formatPrice($lstBilling["total_sale"])."đ";
	}
	$total_customer = $clsCustomer->countItem($cond_customer);
	$total_followups = $clsFollowUp->countItem($cond_followUp);
	$lstShare = $clsShare->getAll($cond_share, "`{$clsShare->pkey}`,`more_information`,JSON_EXTRACT(`more_information`,\"$.is_confirm\") AS `is_confirm`");
	foreach ($lstShare as $key => $val) {
		++$total_share;
		if(!empty($val["is_confirm"])) {
			++$total_share_customer;
		}else{
			++$total_share_waiting;
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'total_billing' => $total_billing,
		'total_sale' => $total_sale,
		'total_customer' => $total_customer,
		'total_followups' => $total_followups,
		'total_share_customer' => $total_share_customer,
		'total_share' => $total_share,
		'total_share_waiting' => $total_share_waiting,
	)); die();
}
function dashboard_load_target_sale_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsShare = new Share();
	$clsBilling = new Billing();
	$my = Input::post('month', date("Y-m"));
	$tmp = @explode("-",$my);
	$month = (int) $tmp[1];
	$year = (int) $tmp[0];
	$m_y = sprintf('%s_%s',$month, $year);
	$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	##
	$more_information = $oneProfile["more_information"];
	$target_sales = $core->get_field($more_information, "target_sales", []);
	$target_config = $core->get_field($target_sales, $m_y, []);
//	$clsISO->print_pre($target_config);die;
	$target_quantity = $target_amount = $total_billings = $percent_qty = $percent_amount = 0;
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND FROM_UNIXTIME(`deposit_date`,'%m/%Y')='{$my}' AND `staff_id`='{$profile_id}'";
	if(!empty($target_config)) {
		$lstBilling = $clsBilling->getAll($cond);
		$arr_billing_date = [];
		$total_amount = 0;
		if(!empty($lstBilling)) {
			foreach($lstBilling as $key => $val) {
				if(!isset($arr_billing_date[date("d/m/Y",$val["deposit_date"])])) {
					$arr_billing_date[date("d/m/Y",$val["deposit_date"])] = (int)$val["totalgrand"];
				}else{
					$arr_billing_date[date("d/m/Y",$val["deposit_date"])] += (int)$val["totalgrand"];
				}
				$total_amount += (int)$val["totalgrand"];
			}
		}
		$target_quantity = $core->get_field($target_config, "quantity", 0);
		$target_amount = $core->get_money_field($target_config, "amount", 0);
		$total_billings = count($lstBilling);
		$percent_qty = (!empty($target_quantity)) ? ceil($total_billings*100/$target_quantity) : (!empty($total_billings) ? 100 : 0);
		$percent_amount = (!empty($target_amount)) ? ceil($total_amount*100/$target_amount) : (!empty($total_amount) ? 100 : 0);
	}
	$smarty->assign("target_config",$target_config);
	$smarty->assign("month",$month);
	$smarty->assign("year",$year);
	$smarty->assign("total_billings",$total_billings);
	$smarty->assign("total_amount",$total_amount);
	$smarty->assign("target_quantity",$target_quantity);
	$smarty->assign("target_amount",$target_amount);
	$smarty->assign("percent_qty",$percent_qty);
	$smarty->assign("percent_amount",$percent_amount);
	$html = $core->build("dashboard".DS."_ajax.load_target_sale_report.tpl");
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
	)); die();
}
function getMonthQuarter($quarter){
	$arr_quarter = [
		"1"	=>	[
			"start_month"	=>	1,
			"end_month"	=>	3,
		],
		"2"	=>	[
			"start_month"	=>	4,
			"end_month"	=>	6,
		],
		"3"	=>	[
			"start_month"	=>	7,
			"end_month"	=>	9,
		],
		"4"	=>	[
			"start_month"	=>	10,
			"end_month"	=>	12,
		]
	];
	return !empty($arr_quarter[$quarter]) ? $arr_quarter[$quarter] : [];
}
function dashboard_load_billing_chart_sale(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn; 
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
//	=========================
	$date_type = Input::post('date_type', '_month');
	if($date_type == '_month'){
		$quarter = 0; // Init value
		$month  = (int) Input::post('month', 0);
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Init value
		$quarter  = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	$current_month_int = date("n");
	###
	$cond = "`is_trash`=0 AND `is_cancel`=0 AND `billing_type`<>'"._BILLING_TYPE_SOP_ID."' AND `staff_id`='{$profile_id}'";	
	if($date_type == '_month'){
		if($month == 0){
			if($year == date("Y")) {
				$start_time = date("01-m-Y",strtotime("-11 months"));
				$start_date = strtotime($start_time);
				$end_time = date(sprintf('t-%s 23:59:59', date("m-Y")));	
				$end_date = strtotime($end_time);
			}else{
				$start_date = strtotime(sprintf('01-01-%s', $year));
				$end_date = strtotime(sprintf('31-12-%s', $year));
			}
			$f = '%m/%Y';
			for($i = $start_date; $i <= $end_date; $i = strtotime('+1 month', $i)){
				$list_ranges[] = date('m/Y', $i);
			}
		} else {
			$f = '%d/%m/%Y';
			$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			for($i=1; $i<= $number_day; $i++){
				$list_ranges[] = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
			}
		}
	} else if($date_type == '_half_year'){		
		$f = '%m/%Y';
		if($half_year == 1 || ($half_year == 0 && $current_month_int <= 6)){
			$start_month = 1;
			$end_month = 6;
		} else if($half_year == 2 || ($half_year == 0 && $current_month_int > 6)){
			$start_month = 7;
			$end_month = 12;
		}
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
		for($i = $start_date; $i <= $end_date; $i = strtotime('+1 month', $i)){
			$list_ranges[] = date('m/Y', $i);
		}
	} else if($date_type == '_quarter'){
		$f = '%m/%Y';
		$arr_time_quarter = [];
		if(empty(@getMonthQuarter($quarter))) {
			$current_quarter = ceil(date('n') / 3);
			$currentIndex = $year * 4 + $current_quarter;
			for ($i = 3; $i >= 0; $i--) {
				$index = $currentIndex - $i;
				$y = floor(($index - 1) / 4);
				$q = $index - ($y * 4);
				$arrquarter = @getMonthQuarter($q);
				$start_month = $arrquarter["start_month"];
				$end_month = $arrquarter["end_month"];
				$start_time = date(sprintf('%s-%s-01 00:00', $y, $clsISO->parseNumber($start_month)));
				$end_time = date(sprintf('%s-%s-t 23:59:59', $y, $clsISO->parseNumber($end_month)));		
				$start_date = strtotime($start_time);
				$end_date = strtotime($end_time);	
				$arr_time_quarter[] = [
					"label" => "Quý {$q}/{$y}",
					"year" => $y,
					"quarter" => $q,
					"start_date" => $start_date,
					"end_date" => $end_date
				];
			}
		} else{
			$arrquarter = @getMonthQuarter($quarter);
			$start_month = $arrquarter["start_month"];
			$end_month = $arrquarter["end_month"];
			$start_time = date(sprintf('%s-%s-01 00:00', $year, $clsISO->parseNumber($start_month)));
			$end_time = date(sprintf('%s-%s-t 23:59:59', $year, $clsISO->parseNumber($end_month)));		
			$start_date = strtotime($start_time);
			$end_date = strtotime($end_time);				
			for($i = $start_date; $i <= $end_date; $i = strtotime('+1 month', $i)){
				$list_ranges[] = date('m/Y', $i);
			}
		}		
	} 
//	=========================
	###
	$data = array();
	$dataPoints = $dataTotalPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['dataPointMaxWidth'] = 60;
	$barChartData['zoomEnabled'] = true;
	$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'titleFontColor' => '#1d6a01',
		'lineColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFormatter' => 1
	);
	if($staff_id == 0 && ($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT'))) {
		$barChartData['axisY2'][] = array(
			'title' => 'SL giao dịch(GD)',
			'titleFontColor' => '#C00000',
			'titleFontSize' => '16',
			'lineColor' => '#C00000',
			'labelFontColor' => '#C00000',
			'tickColor' => '#C00000',
			'maximum' => 400
		);
		$barChartData['axisY2'][] = array(
			'title' => 'SL nhân viên(người)',
			'titleFontColor' => '#6d78ad',
			'titleFontSize' => '16',
			'lineColor' => '#6d78ad',
			'labelFontColor' => '#6d78ad',
			'tickColor' => '#6d78ad',
			'maximum' => 400
		);
	} else {
		$barChartData['axisY2'][] = array(
			'title' => 'SL giao dịch(GD)',
			'titleFontColor' => '#C00000',
			'titleFontSize' => '16',
			'lineColor' => '#C00000',
			'labelFontColor' => '#C00000',
			'tickColor' => '#C00000'
		);
	}
	if($date_type == '_quarter' && $quarter == 0) {
		foreach ($arr_time_quarter as $k => $v) {
			$field = "{$clsBilling->pkey},`totalgrand`";
			$list_billings = $clsBilling->getAll("{$cond} and (`deposit_date` BETWEEN '{$v["start_date"]}' AND '{$v["end_date"]}')", $field);
			if(!empty($list_billings)){
				$total_billings = count($list_billings);
				foreach($list_billings as $key => $val){
					$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
					$total_sales += $totalgrand;
				}
				unset($list_billings);
			}
			$dataPoints[] = array(
				'label'	=> $v["label"],
				'y'	=> $total_sales*1,
				'indexLabel' => $clsISO->shortNumber($total_sales)
			);
			$dataTotalPoints[] = array(
				'label'	=> $v["label"],
				'y' => $total_billings*1,
				'indexLabel' => (string) $total_billings
			);
		}
	}else{
		foreach($list_ranges as $date){
			$tmp = explode('/', $date);
			$end_day = cal_days_in_month(CAL_GREGORIAN, $tmp[0], $tmp[1]);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $tmp[0], $tmp[1]));
			$total_billings = $total_sales = 0;
			$field = "{$clsBilling->pkey},`totalgrand`";
	//		$dbconn->debug = true;
			$list_billings = $clsBilling->getAll("{$cond} and FROM_UNIXTIME(`deposit_date`,'{$f}')='{$date}'", $field);
	//		$clsISO->print_pre($list_billings);
			if(!empty($list_billings)){
				$total_billings = count($list_billings);
				foreach($list_billings as $key => $val){
					$totalgrand = $clsISO->convertToNumber($val['totalgrand']);
					$total_sales += $totalgrand;
				}
				unset($list_billings);
			}
			$dataPoints[] = array(
				'label'	=> sprintf('%s', $date),
				'y'	=> $total_sales*1,
				'indexLabel' => $clsISO->shortNumber($total_sales)
			);
			$dataTotalPoints[] = array(
				'label'	=> sprintf('%s', $date),
				'y' => $total_billings*1,
				'indexLabel' => (string) $total_billings
			);
		}
	}
//	die;
//	$clsISO->print_pre($dataPoints);die;
	$barChartData['data'] = array(
		array(
			"type"    => "line",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Doanh số bán hàng",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		),
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Số lượng giao dịch",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataTotalPoints
		)
	);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function dashboard_load_followup_action(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
	$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$resource_id = (int) Input::post('resource_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	$date_type = Input::post('date_type', "_month");
	if($date_type == '_month'){
		$quarter = 0; // Default value
		$month = (int) Input::post('month', 0);
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Default value
		$quarter = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	$cond = "`followup_type`='_crm' AND `admin_id`='{$profile_id}'";	
	if(!empty($resource_id) || !empty($campaign_id)) {
		$cond .= " AND `customer_id` IN (SELECT `customer_id` FROM `{$clsCustomer->tbl}` WHERE `admin_id`='{$profile_id}'";
		if(!empty($resource_id)){
			$cond.= " AND `resource_id`='{$resource_id}'";
		}
		if(!empty($campaign_id)){
			$cond.= " AND `list_campaign_id` LIKE '%|{$campaign_id}|%'";
		}
		$cond .= " )";
	}
	if($date_type == '_month' && $month > 0){
		$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);		
		$cond .= " AND FROM_UNIXTIME(`date_id`,'%m/%Y')='{$date_my}'";
	} else if($date_type == '_quarter' && $quarter > 0){	
		$cond .= " AND QUARTER(FROM_UNIXTIME(`date_id`))='{$quarter}' AND FROM_UNIXTIME(`date_id`,'%Y')='{$year}'";
	} else if($date_type == '_half_year' && $half_year > 0){
		if($half_year == 1){
			$start_month = 1;
			$end_month = 6;
			$txt_time = sprintf("Nửa đầu năm %s",$year);
		} else if($half_year == 2){
			$start_month = 7;
			$end_month = 12;
			$txt_time = sprintf("Nửa cuối năm %s",$year);
		}
		$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
		$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));		
		$cond .= " AND (`date_id` BETWEEN {$start_date} AND {$end_date})";	
	} else {
		$cond .= " AND FROM_UNIXTIME(`date_id`,'%Y')='{$year}'";
	}	
	$dataPoints = $dataTotalPoints = $barChartData = array();	
	$arr_type_cached = $clsProperty->getArraySearchByKey("FOLLOWUP_TYPE");
//	$dbconn->debug = true;
	$lstFollowUp = $clsFollowUp->getAll($cond. " GROUP BY `type_id`","COUNT(`type_id`) AS `total`,`type_id`");
//	$clsISO->print_pre($lstFollowUp);die;
	if(!empty($lstFollowUp)) {
		foreach($lstFollowUp as $key => $val) {
			$total = $val["total"];
			$dataPoints[] = array(
				'label'	=> $arr_type_cached[$val["type_id"]]["title"],
				'y'	=> (int)$total,
				'indexLabel' => $total . " lần"
			);
		}
		$barChartData['animationEnabled'] = true;
		$data['type'] = 'column';
		$data['indexLabel'] = '{y}';
		$data['title']['fontColor'] = 'rgb(159,34,58)';
		$data['toolTipContent'] = '{label}<br /> Tổng: {y} lần';
		$data['indexLabelPlacement'] = 'inside';
		$data['indexLabelFontColor'] = '#36454F';
		$data['dataPoints'] = $dataPoints;
		$barChartData['data'] = $data;
		$uid = $clsISO->getUniqid();
		$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html,
			'drawchart' => '1',
			'barChartData' => $barChartData
		)); die();
	}else{
		$html = '<div class="d-flex p-3 flex-column align-items-center justify-content-center  h-px-200">
			<img src="'.URL_IMAGES.'/empty.svg" class="w-px-100 mb-2">
			<p class="text-muted">Không có hoạt động khách hàng nào!</p>
		</div>';
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html,
		)); die();
	}
//	$clsISO->print_pre($dataPoints);die; 
}
function dashboard_load_search_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile,$profile_id;
	$clsShare = new Share();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsLog  = new Log();
	$clsStock  = new Stock();
	$clsProject  = new Project();
	$field = "`t1`.`{$clsLog->pkey}`,`t1`.`reg_date`,`t1`.`stock_id`,`t2`.`ms_code`,`t2`.`block_id`,`t2`.`project_id`";
//	$dbconn->debug = true;
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_project_cached = $clsProject->getListProject();
	$cond= "(`t1`.`type`='search' OR `t1`.`type`='view_stock') AND `t1`.`from_site`='_user' AND `t1`.`user_id`='{$profile_id}'";
	$list_logs = $dbconn->getAll("SELECT {$field} FROM `{$clsLog->tbl}` AS `t1` LEFT JOIN `{$clsStock->tbl}` AS `t2` ON `t1`.`stock_id`=`t2`.`stock_id` WHERE {$cond} ORDER BY `t1`.`reg_date` DESC LIMIT 0,8");	
//	$clsISO->print_pre($list_logs);die;
	$smarty->assign("list_logs",$list_logs);	
	// Return
	$html = $core->build("dashboard".DS."_ajax.load_search_stock.tpl");
	echo json_encode([
		"html"	=> $html,
	],JSON_UNESCAPED_UNICODE);die;
}
function dashboard_load_marketing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsProfile,$oneProfile,$deviceType,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampign = new Campaign();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$date_type = Input::post('date_type', "_month");
	if($date_type == '_month'){
		$quarter = 0; // Default value
		$month = (int) Input::post('month', 0);
	} else if($date_type == '_half_year'){
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if($date_type == '_quarter') {
		$month = 0; // Default value
		$quarter = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', date('Y'));
	if($date_type == '_month' && $month > 0){
		$date_ym = sprintf('%s-%s', $year, $clsISO->parseNumber($month));	
		$cond = "`t1`.`month`='".$date_ym."'";
		$sub_cond = "`month`='".$date_ym."'";
	} else {
		if($date_type == '_quarter' && $quarter > 0){	
			if($quarter == 1) {
				$start_month = 1;
				$end_month = 3;
			}else if($quarter == 2) {
				$start_month = 4;
				$end_month = 6;
			}else if($quarter == 3) {
				$start_month = 7;
				$end_month = 9;
			}else if($quarter == 4) {
				$start_month = 10;
				$end_month = 12;
			}
		} else if($date_type == '_half_year' && $half_year > 0){
			if($half_year == 1){
				$start_month = 1;
				$end_month = 6;
				$txt_time = sprintf("Nửa đầu năm %s",$year);
			} else if($half_year == 2){
				$start_month = 7;
				$end_month = 12;
				$txt_time = sprintf("Nửa cuối năm %s",$year);
			}
		}else{
			$start_month = 1;
			$end_month = ($year == date("Y")) ? date("n") : 12;			
		}		
		$arr_months = [];
		for($i=$start_month; $i<= $end_month; $i++) {
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($i));
		}
		$cond = "`t1`.`month` IN ('".implode('\',\'', $arr_months)."')";
		$sub_cond = "`month` IN ('".implode('\',\'', $arr_months)."')";
	}
	// GĐ Sale
	$is_dir_sale = $clsISO->checkPermissionGroup("SALE_DIRECTOR") ? 1 : 0;
	// GĐ 
	$is_regional_dir = $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') ? 1 : 0;
	// Cond
	$cond.= " AND `t1`.`staff_id` = '{$profile_id}'";
	#
	$list_regis_staffs = $arr_projects = $arr_blocks = $arr_block_ids = $arr_project_ids = array();
	$user_percent = $total_budgets = $total_amounts = $total_company_support_amounts = $total_sale_support_amounts = 0;
	$field = "`t1`.`{$clsMarketingBudgetRegister->pkey}`,`t1`.`total_budget`,`t1`.`staff_id`,`t1`.`project_id`";
	$field.= ",`t1`.`block_id`,`t2`.`amount`,`t2`.`company_support_amount`,`t2`.`sale_support_amount`";
//	 $dbconn->debug = true;
	$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsMarketingBudgetRegister->tbl} AS `t1` LEFT JOIN (SELECT `project_id`,`block_id`,`staff_id`,SUM(`amount`) AS `amount`,SUM(`company_support_amount`) AS `company_support_amount`,SUM(`sale_support_amount`) AS `sale_support_amount` FROM {$clsMarketingSpending->tbl} WHERE {$sub_cond} GROUP BY `project_id`, `block_id`, `staff_id`) AS `t2` ON `t1`.`staff_id`=`t2`.`staff_id` AND `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` WHERE {$cond}");	
//	$clsISO->print_pre($tmp);die;
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
	<div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
/**=========DASH BOARD SALE========**/