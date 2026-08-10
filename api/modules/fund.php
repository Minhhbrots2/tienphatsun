<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
$app->post('/v1/ops_cost/sync', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsOpsCost = new OpsCost();
	$clsSetting = new Setting();
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$arr_category_cached = $arr_profile_cached = $arr_setting_cached = array();
	$tblData = $request->getParsedBody();
	// $clsISO->print_pre($tblData); die();
	if(!empty($tblData) && !empty($tblData["expense_code"])) {
		$parrent_id = 0;
		$expense_code = trim($tblData['expense_code']); // Mã chi
		$expense_code = str_replace('\\\\','\\', $expense_code);
		$expense_date = trim($tblData['expense_date']); // Ngày chi
		$type_name = trim($tblData['type_name']); // Loại
		$department_name = $tblData['department_name']; // Phòng ban
		$proposer_name = $tblData['proposer_name']; // Người đề suất
		$reason = $tblData['reason']; // Lý do
		$approver_name = $tblData['approver_name']; // Người duyệt
		$amount = $tblData['amount']; // Số tiền
		$stock_code = $tblData['stock_code']; // Mã căn
		$project_name = $tblData['project_name']; // Dự án
		$execution_date = $tblData['execution_date']; // Dự án
		$payment_method = $tblData['payment_method']; // Phương thức thanh toán
		$status_name = $tblData['status_name']; // Tình trạng
		$notes = $tblData['notes']; // Ghi chú
		$recipient_account = $tblData['recipient_account']; // TK Nhận
		#
		$type_id = 0;
		if(!empty($type_name)){
			if($arr_category_cached[$type_name]){
				$type_id = $arr_category_cached[$type_name];
			} else {
				$field = "{$clsProperty->pkey}";
				$tmp = $clsProperty->getByCond("`property_type`='_OPS_COST_CAT' AND (`property_code`='{$type_name}' or `slug`='".$clsISO->replaceSpace($type_name)."')", $field);
				if(!empty($tmp)){
					$type_id = $tmp[$clsProperty->pkey];
					$arr_category_cached[$type_name] = $type_id;
				}
			}
		}	
		#
		$department_id = 0;
		if(!empty($department_name)){
			if($arr_setting_cached[$department_name]){
				$department_id = $arr_setting_cached[$department_name];
			} else {
				$field = "{$clsSetting->pkey}";
				$tmp = $clsSetting->getByCond("`is_trash`=0 and `_type`='_DEPARTMENT' 
					AND `slug`='".$clsISO->replaceSpace($department_name)."'", $field);
				if(!empty($tmp)){
					$department_id = $tmp[$clsSetting->pkey];
				} else {
					$department_id = $clsSetting->getMaxId();
					$clsSetting->insert(array(
						$clsSetting->pkey => $department_id,
						'_type' => '_DEPARTMENT',
						'title' => $department_name,
						'slug' => $clsISO->replaceSpace($department_name),
						'order_no' => $clsSetting->getMaxOrderNo(),
						'reg_date' => time(),
					));
				}
				$arr_setting_cached[$department_name] = $department_id;
			}
		}
		#
		$status_id = 0;
		if(!empty($status_name)){
			if($arr_setting_cached[$status_name]){
				$status_id = $arr_setting_cached[$status_name];
			} else {
				$field = "{$clsSetting->pkey}";
				$tmp = $clsSetting->getByCond("`is_trash`=0 and `_type`='_STATUS' 
					AND `slug`='".$clsISO->replaceSpace($status_name)."'", $field);
				if(!empty($tmp)){
					$status_id = $tmp[$clsSetting->pkey];
				} else {
					$status_id = $clsSetting->getMaxId();
					$clsSetting->insert(array(
						$clsSetting->pkey => $status_id,
						'_type' => '_STATUS',
						'title' => $status_name,
						'slug' => $clsISO->replaceSpace($status_name),
						'order_no' => $clsSetting->getMaxOrderNo(),
						'reg_date' => time(),
					));
				}
				$arr_setting_cached[$status_name] = $status_id;
			}
		}
		$proposer_id = $approver_id = 0;
		if(!empty($proposer_name)){
			$parts = @explode('-', $proposer_name);
			$field = "{$clsProfile->pkey}"; // Field
			$tmp = $clsProfile->getByCond("`code`='{$parts[0]}' AND `full_name_slug`='".$clsISO->replaceSpace($parts[1])."'", $field);
			$proposer_id = !empty($tmp) ? $tmp[$clsProfile->pkey] : 0;
		}
		if(!empty($approver_name)){
			$parts = @explode('-', $approver_name);
			$field = "{$clsProfile->pkey}"; // Field
			$tmp = $clsProfile->getByCond("`code`='{$parts[0]}' AND `full_name_slug`='".$clsISO->replaceSpace($parts[1])."'", $field);
			$approver_id = !empty($tmp) ? $tmp[$clsProfile->pkey] : 0;
		}
		// $dbconn->debug = true;
		$tmp = $clsOpsCost->getByCond("`expense_code`='".addslashes($expense_code)."'");
		if(!empty($tmp)){
			if($clsOpsCost->updateOne($tmp[$clsOpsCost->pkey], array(
				'type_id' => $type_id,
				'parrent_id' => $parrent_id,
				'expense_code' => $expense_code,
				'expense_date' => $clsISO->convertTextToTime($expense_date),
				'department_id' => $department_id,
				'proposer_id' => $proposer_id,
				'approver_id' => $approver_id,
				'amount' => $clsISO->convertStringToNumber($amount),
				'more_information' => json_encode($tblData, JSON_UNESCAPED_UNICODE),
				'status_id' => $status_id,
				'upd_date' => time(),
			))){
				$status_code = 200;
				$apiresults = array(
					'error' => 0, 
					'result' => 'success', 
					'expense_code' => $expense_code,
					'message' => "Cập nhật thành công !"
				);
			}
		} else {
			if($clsOpsCost->insert(array(
				$clsOpsCost->pkey => $clsOpsCost->getMaxId(),
				'type_id' => $type_id,
				'parrent_id' => $parrent_id,
				'expense_code' => $expense_code,
				'expense_date' => $clsISO->convertTextToTime($expense_date),
				'department_id' => $department_id,
				'proposer_id' => $proposer_id,
				'approver_id' => $approver_id,
				'amount' => $clsISO->convertStringToNumber($amount),
				'more_information' => json_encode($tblData, JSON_UNESCAPED_UNICODE),
				'status_id' => $status_id,
				'user_id' => _PROFILE_CHN_ID,
				'user_id_update' => _PROFILE_CHN_ID,
				'reg_date' => time(),
				'upd_date' => time(),
			))){
				$status_code = 200;
				$apiresults = array(
					'error' => 0,
					'expense_code' => $expense_code,
					'result' => 'success', 
					'message' => "Thêm mới thành công !"
				);
			}
		}
	}
	// Return
	echo echoResponse($status_code, $apiresults);
});
$app->post('/operation_fee/add_category', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$inputs = $request->getParsedBody();
	// $clsISO->print_pre($inputs);die; 
	$number_upd = $group_id = 0;
	if(!empty($inputs)) {
		$group_name = trim($inputs['group_name']);
		$group_code = trim($inputs['group_code']);
		$expense_code = trim($inputs['expense_code']);
		$expense_name = trim($inputs['expense_name']);
		if(!empty($group_code) && !empty($group_name)) {
			$more_information = [
				"group_name"	=>	$group_name,
				"group_code"	=>	$group_code,
			];
			$group_name_slug = $clsISO->replaceSpace($group_name);
			$oneGroup = $clsProperty->getByCond("`property_type`='_OPERATION_FEE_CAT' AND `property_code`='{$group_code}'");
			if(!empty($oneGroup)) {
				$group_id = $oneGroup[$clsProperty->pkey];
				if($clsProperty->updateOne($group_id, array(	
					'user_id_update' => _PROFILE_CHN_ID,
					'title' => $group_name,
					'slug' => $group_name_slug,
					'more_information' => json_encode($more_information),
					'upd_date' => time(),
				))) {
					++$number_upd;
				}
			}else{
				$group_id = $clsProperty->getMaxID();
				if($clsProperty->insert(array(
					$clsProperty->pkey => $group_id,	
					'property_type'	=> "_OPERATION_FEE_CAT",
					'property_code' => $group_code,
					'parent_id'	=> 0,
					'for_id' => 0,
					'title'	=> $group_name,
					'slug'	=> $group_name_slug,
					'intro' => "",
					'image' => "",
					'bgcolor' => "",
					'textcolor' => "",
					'user_id'	=> $user_id,
					'user_id_update'	=> $user_id,
					'order_no'	=> $clsProperty->getMaxOrderNo(),
					'more_information' => json_encode($more_information),
					'reg_date' => time(),
					'upd_date' => time(),
				))) {
					++$number_upd;
				}
			}
		}		
		if(!empty($expense_code) && !empty($expense_name)) {	
			$tmp = explode("_",$expense_code);
			$group_code = !empty($tmp[0]) ? $tmp[0] : "";
			$oneGroup = $clsProperty->getByCond("`property_type`='_OPERATION_FEE_CAT' AND `property_code`='{$group_code}'");
			if(!empty($oneGroup)) {
				$group_id = $oneGroup[$clsProperty->pkey];
				$more_information = [
					"expense_name"	=>	$expense_name,
					"expense_code"	=>	$expense_code,
				];
				$expense_name_slug = $clsISO->replaceSpace($expense_name);
				$oneItem = $clsProperty->getByCond("`property_type`='_OPERATION_FEE_CAT' AND `property_code`='{$expense_code}' AND `parent_id`='{$group_id}'");
				if(!empty($oneItem)) {
					$cat_id = $oneItem[$clsProperty->pkey];
					if($clsProperty->updateOne($cat_id, array(	
						'parent_id' => $group_id,
						'user_id_update' => _PROFILE_CHN_ID,
						'title' => $expense_name,
						'slug' => $expense_name_slug,
						'more_information' => json_encode($more_information),
						'upd_date' => time(),
					))) {
						++$number_upd;
					}
				}else{
					$cat_id = $clsProperty->getMaxID();
					if($clsProperty->insert(array(
						$clsProperty->pkey => $cat_id,	
						'property_type'	=> "_OPERATION_FEE_CAT",
						'property_code' => $expense_code,
						'for_id'	=> 0,
						'parent_id' => $group_id,
						'title'	=> $expense_name,
						'slug'	=> $expense_name_slug,
						'intro' => "",
						'image' => "",
						'bgcolor' => "",
						'textcolor' => "",
						'user_id'	=> $user_id,
						'user_id_update'	=> $user_id,
						'order_no'	=> $clsProperty->getMaxOrderNo(),
						'more_information' => json_encode($more_information),
						'reg_date' => time(),
						'upd_date' => time(),
					))) {
						++$number_upd;
					}
				}
			}
		}
	}
	if($number_upd > 0) {
		$status_code = 200;
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'message' => "Cập nhật thành công ".$number_upd." bản ghi"
		);
	}
	// Return
	echo echoResponse($status_code, $apiresults);
});

?>