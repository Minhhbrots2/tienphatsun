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
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	##
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	$list_transaction_type = $clsProperty->getCacheItems('_TRANSACTION_TYPE');
	$assign_list["list_transaction_type"] = $list_transaction_type;
    /*=============Title & Description Page==================*/
	$title_page = 'Xác nhận hoa hồng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_list(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsTransaction = new Transaction();
	$keyword = Input::post('keyword');
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	$get_transaction_type = Input::post('transaction_type', '_all');
	
	$cond = $sql_cond = "`is_trash`=0";
	if($get_transaction_type != "_all"){
		$cond.= " and `transaction_type`='{$get_transaction_type}'";
	}
	if($clsISO->checkPermissionGroup('DIRECTOR')){
		// Continue
	} else if($clsISO->checkPermissionGroup('ADMIN_PROJECT')){
		$list_projects = $clsProperty->getAll("`is_trash`=0 and `property_type`='_TRANSACTION_PROJECT' and JSON_EXTRACT(`more_information`,\"$.project_admin_id\")='{$profile_id}'", "{$clsProperty->pkey}");
		if(!empty($list_projects)){
			$tmp = array();
			foreach($list_projects as $key => $val){
				$tmp[] = $val[$clsProperty->pkey];
			}
			$cond.= " and `project_id` in (".implode(',', $tmp).")";
			$sql_cond.= " and `project_id` in (".implode(',', $tmp).")";
		}
	} else {
		$cond.= " and `staff_id`='{$profile_id}'";
		$sql_cond.= " and `staff_id`='{$profile_id}'";
	}
	#- Begin pagination
	$total_record = $clsTransaction->countItem($cond);
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$list_transactions = $clsTransaction->getAll($cond." order by reg_date DESC".$limitCond);
	if(!empty($list_transactions)){
		$arr_property_cached = array();
		foreach($list_transactions as $key => $val){
			$project_id = $val['project_id'];
			$transaction_type = $val['transaction_type'];
			if(!isset($arr_property_cached[$transaction_type])){
				$arr_property_cached[$transaction_type] = $clsProperty->getTitle($transaction_type);
			}
			if(!isset($arr_property_cached[$project_id])){
				$arr_property_cached[$project_id] = $clsProperty->getTitle($project_id);
			}
			$list_transactions[$key]['project_name'] = $arr_property_cached[$project_id];
			$list_transactions[$key]['transaction_name'] = $arr_property_cached[$transaction_type];
		}
	}
	$smarty->assign('list_transactions', $list_transactions);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('clsTransaction', $clsTransaction);
	
	$total_alls = $clsTransaction->countItem($sql_cond);
	$html_filter_list = '<label class="we-radio">
		<input type="radio"'.($get_transaction_type=='_all'?' checked':'').' class="search_field js__select-transaction_type" onChange="$Core.transaction.do_search(this, event)" name="transaction_type" data-field="transaction_type" value="_all">
		<span>Tất cả ('.$total_alls.')</span>
	</label>';
	$list_transaction_type = $clsProperty->getCacheItems('_TRANSACTION_TYPE');
	if(!empty($list_transaction_type)){
		foreach($list_transaction_type as $key => $val){
			$transaction_type = $val[$clsProperty->pkey];
			$total_items = $clsTransaction->countItem("{$sql_cond} and `transaction_type`='{$transaction_type}'");
			$html_filter_list.= '<label class="we-radio" >
				<input type="radio"'.($get_transaction_type==$transaction_type?' checked':'').' class="search_field js__select-transaction_type" onChange="$Core.transaction.do_search(this, event)" 
					data-field="transaction_type" name="transaction_type" value="'.$val['property_id'].'">
				<span>'.$val['title'].' ('.$total_items.')</span>
			</label>';
		}
	}
	// Return
	$html = $core->build('_ajax.list.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'html_filter_list' => $html_filter_list
	)); die();
}
function default_open(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsTransaction = new Transaction();
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProject', $clsProject);
	###
	$more_information = $oneProfile['more_information'];
	$banks_info = isset($more_information['banks_info']) 
		? $more_information['banks_info'] : array(); 
	$list_transaction_type = $clsProperty->getCacheItems('_TRANSACTION_TYPE');
	$smarty->assign('list_transaction_type', $list_transaction_type);
	###
	$action= '_add';
	$uid = $clsISO->getUniqid();
	$openFrom = Input::post('openFrom', '_list');
	$transaction_id = (int) Input::post('transaction_id', 0);
	$more_information = array("bank_id" => "");
	$oneTransaction = array(
		'send_date' => time(),
		'project_id' => _TRANSACTION_PROJECT_VHOP1_ID,
		'transaction_type' => _TRANSACTION_TYPE_DEF_ID
	);
	$billing_store = $list_attachments = array();
	if($transaction_id > 0){
		$action = '_edit';
		$oneTransaction = $clsTransaction->getOne($transaction_id);
		$attachments = $oneTransaction['attachments'];
		$billing_store = $oneTransaction['billing_store'];
		$more_information = $oneTransaction['more_information'];
		$billing_store = $clsISO->to_array_json($billing_store);
		$list_attachments = $clsISO->to_array_json($attachments);
		$more_information = $clsISO->to_array_json($more_information);
	}
	if($openFrom == '_billing'){
		$billing_id = (int) Input::post('billing_id', 0);
		$oneBilling = $clsBilling->getOne($billing_id);
		$smarty->assign('billing_id', $billing_id);
		$smarty->assign('oneBilling', $oneBilling);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('openFrom', $openFrom);
	$smarty->assign('transaction_id', $transaction_id);
	$smarty->assign('oneTransaction', $oneTransaction);
	$smarty->assign('clsTransaction', $clsTransaction);
	$smarty->assign('billing_store', $billing_store);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_attachments', $list_attachments);
	###
	$html_banks = sprintf('<option value="0">%s</option>', "Tài khoản ngân hàng");
	if(!empty($banks_info)){
		foreach($banks_info as $bank_id => $bank){
			$selected = ($more_information['bank_id'] == $bank_id) ? " selected" : "";
			$html_banks.= sprintf('<option value="%s"%s>%s(%s)</option>', $bank_id, $selected, $bank['account_number'], $bank['bank_name']);
		}
	}
	$list_trans_projects = $clsProperty->getCacheItems("_TRANSACTION_PROJECT");
	$smarty->assign('list_trans_projects', $list_trans_projects);
	$smarty->assign('html_banks', $html_banks);
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'action' => $action
	)); die();
}
function default_add_line(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$uid = $clsISO->getUniqid();
	$total_row = (int) Input::post('total_row',1);
	$send_month = Input::post('send_month', date('Y-m'));
	$project_id = (int) Input::post('project_id', _TRANSACTION_PROJECT_VHOP1_ID);
	$transaction_id = (int) Input::post('transaction_id',0);
	$html = '<tr class="'.$uid.' transaction_item_'.$transaction_id.'">
		<td class="p-0 text-center">'.($total_row+1).'</td>
		<td class="p-0 w-px-125">
			<input type="hidden" class="billing_id" name="billing_store['.$uid.'][billing_id]" value="0">
			<input type="hidden" class="billing_code" name="billing_store['.$uid.'][billing_code]" value="0">
			<select prompt="Giao dịch" uid="'.$uid.'" class="form-control cboProduct" name="billing_store['.$uid.'][project]" id="cboProduct_'.$uid.'" data-options="panelWidth:800,url:\'/index.php?mod='.$mod.'&act=search_billing&uid='.$uid.'&staff_id='.$profile_id.'\',mode:\'remote\',method:\'post\',width:\'130px\',height:\'34px\',multiple:false, columns: [[{field:\'billing_code\',title:\'Mã GD\',width:70,align:\'left\'},{field:\'deposit_date\',title:\'Ngày cọc\',width:85,align:\'left\'},{field:\'staff_name\',title:\'Nhân viên\',width:120,align:\'left\'},{field:\'stock_code\',title:\'Mã căn\',width:80},{field:\'project_name\',title:\'Dự án\',width:120,align:\'left\'},{field:\'totalgrand\',title:\'Tồng tiền\',width:80,align:\'left\'}]],idField:\'billing_code\',textField:\'billing_code\',fitColumns: true,showFooter: true,pagination: true,pageSize: 20,queryParams:{send_month:\''.$send_month.'\',project_id:\''.$project_id.'\'}"></select>
		</td>
		<td class="p-0"><input type="text" class="form-control no-focus stock_code" placeholder="Mã căn" 
			name="billing_store['.$uid.'][stock_code]" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus project_name" placeholder="Dự án" 
			name="billing_store['.$uid.'][project_name]" /></td>
		<td class="p-0"><input type="date" placeholder="dd/mm/yy" name="billing_store['.$uid.'][contract_date]" 
			class="form-control no-focus contract_date" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In total_price" 
			placeholder="0.00đ" name="billing_store['.$uid.'][total_price]" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus numberonly commission" 
			name="billing_store['.$uid.'][commission]" placeholder="0%" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ms" 
			placeholder="0.00đ" name="billing_store['.$uid.'][price_ms]" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ns" 
			placeholder="0.00đ" name="billing_store['.$uid.'][price_ns]" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ps" placeholder="0.00đ" 
			name="billing_store['.$uid.'][price_ms]" /></td>
		<td class="p-0"><input type="text" class="form-control no-focus notes" placeholder="Viết ghi chú..." 
			name="billing_store['.$uid.'][notes]" /></td>
		<td class="text-center"><a href="javascript:void(0);" class="text-muted" onClick="$Core.helper.deleteline(this, vent)">
			'.$clsISO->makeIcon('bx-trash').'
		</a></td>
	</tr>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_handle_bank(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id;
	$clsProfile = new Profile();
	###
	$html_banks = sprintf('<option value="0">%s</option>', "Tài khoản ngân hàng");
	$staff_id = Input::post('staff_id', 0);
	$more_information = $clsProfile->getOneField('more_information', $staff_id);
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = isset($more_information['banks_info']) 
		? $more_information['banks_info'] : array(); 
	if(!empty($banks_info)){
		foreach($banks_info as $bank_id => $bank){
			$html_banks.= sprintf('<option value="%s">%s(%s)</option>', $bank_id, $bank['account_number'], $bank['bank_name']);
		}
	}
	// Return
	echo $html_banks; die();
}
function default_search_billing(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$oneProfile,$clsISO;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$results = array('total' => 0, 'rows' => array());
	###
	$uid = Input::get('uid', "");
	$tp = Input::get("tp", "_dash");
	$keysearch = Input::post('q', "");
	###
	$cond = "`is_trash`=0 and `is_cancel`=0";
	if($tp == "_dash"){
		$role_id = $oProfile['role_id'];
		$department_id = $oProfile['department_id'];
		$staff_id = (int) Input::get('staff_id', 0);
		$send_month = Input::post('send_month', date('Y-m'));
		$project_id = (int) Input::post('project_id', _TRANSACTION_PROJECT_VHOP1_ID);
		if($project_id == _TRANSACTION_PROJECT_VHOP1_ID){
			$cond.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";
		} else if($project_id == _TRANSACTION_PROJECT_MWF_ID){
			$cond.= " and `billing_type`='"._BILLING_TYPE_MWF_ID."'";
		} else if($project_id == _TRANSACTION_PROJECT_LSP_ID){
			$cond.= " and `billing_type`='"._BILLING_TYPE_LSP_ID."'";
		} else if($project_id == _TRANSACTION_PROJECT_VHOP2_ID){
			$cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";
		} else if($project_id == _TRANSACTION_PROJECT_LEASING_ID){
			$cond.= " and `billing_type` in (".implode(',', array(_BILLING_TYPE_LEASING_ID,_BILLING_TYPE_LEASING_OCP2_ID,_BILLING_TYPE_LEASING_MGW_ID)).")";
		}
		###
		$cond.= " and `staff_id`<>'"._PROFILE_PARTNER_ID."'";
		if($role_id == _ROLE_GD_PROJECT){
			//$cond.= " and (`staff_id`='{$staff_id}' or `project_id`='')";
		} else if($role_id == _ROLE_GD_SALE){
			//$cond.= " and (`staff_id`='{$staff_id}' or `project_id`='')";
		} else {
			$cond.= " and `staff_id`='{$staff_id}'";
		}
		$cond.= " and FROM_UNIXTIME(`deposit_date`,'%Y-%m')='{$send_month}'";
	}
	if(!empty($keysearch)){
		$cond.= " and (`stock_code` like '%{$keysearch}%' 
			or `billing_code` like '%{$keysearch}%'
		)";
	}
	#- Pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('row', 20);
	$total_record = $clsBilling->countItem($cond);
	$results['total'] = $total_record;
	$total_page = @ceil($total_page/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$field = "{$clsBilling->pkey},billing_code,stock_code,staff_id,project_id,customer_id";
	$field.= ",deposit_date,contract_date,commission,partner_id,more_information";
	$list_billings = $clsBilling->getAll($cond." order by `deposit_date` DESC".$limitCond); 
	//$clsISO->print_pre($list_billings); die();
	if(!empty($list_billings)){
		foreach($list_billings as $key => $val){
			$contract_date = "";
			if($val['contract_date'] > 0){
				$contract_date = $clsISO->convertTimeToText($val['contract_date']);
			}
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$_oneStaff = $clsProfile->getOne($val['staff_id'], "`code`,`full_name`,`first_name`,`last_name`,`address`");
			$results['rows'][] = array(
				'uid' => $uid,
				'billing_id' => $val[$clsBilling->pkey],
				'stock_code' => $val['stock_code'],
				'billing_code' => $val['billing_code'],
				'commission' => $val['commission'],
				'sale_bonus' => $more_information['sale_bonus'],
				'support_sale' => $more_information['support_sale'],
				'deposit_date' => $clsISO->convertTimeToText($val['deposit_date']),
				'contract_date' => $contract_date,
				'project_name'  => $clsProject->getCode($val['project_id']),
				'customer_name' => $clsCustomer->getName($val['customer_id']),
				'staff_name' => $clsProfile->getIndentityV2($val['staff_id'], $_oneStaff, false),
				'staff_address' => $clsProfile->getAddress($val['staff_id'], $_oneStaff),
				'totalgrand' => $clsISO->formatNumberToEasyRead($val['totalgrand']) 
			);
		}
	}
	// Return
	echo json_encode($results); 
	die();
}
function default_pop_save_transaction(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsTransaction = new Transaction();
	###
	$transaction_id = (int) Input::post('transaction_id', 0);
	$transaction_type = (int) Input::post('transaction_type', _TRANSACTION_TYPE_DEF_ID);
	$send_month = Input::post('send_month', date('Y-m'));
	$tmp = @explode('-', $send_month);
	$end_day = cal_days_in_month(CAL_GREGORIAN, $tmp[1], $tmp[0]);
	$send_date = sprintf('%s-%s-%s', $tmp[0], $tmp[1], $end_day);
	$billing_store = Input::post('billing_store');
	// $clsISO->print_pre($send_month); die();
	$_validated = 0;
	if(!empty($billing_store)){
		foreach($billing_store as $key => $billing){
			$is_empty = 1;
			foreach($billing as $field){
				if(!empty($field)){
					$is_empty = 0;
					break;
				}
			}
			if($is_empty) {
				unset($billing_store[$key]);
			} else {
				if(empty($billing['billing_id']) 
					|| empty($billing['stock_code']) 
					|| empty($billing['commission']) 
					|| empty($billing['total_price'])){
					$_validated ++;
				}
			}
		}
	}
	$msg = "_error";
	if($_validated == 0){
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(!is_uploaded_file($file['name'])){
					$clsUploadFile = new UploadFile();
					$up = $clsUploadFile->uploadItem($file,'/attachments',"pdf,doc,docx,xls,xlsx,csv,txt,zip,jpg,jpeg,png,gif");
					if(!empty($up) && file_exists(ABSPATH . $up)){
						$attachments[] = $up;
					}
				}
			}
		}
		if($transaction_id > 0){
			$oneTransaction = $clsTransaction->getOne($transaction_id);
			$more_information = $oneTransaction['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['bank_id'] = Input::post('bank_id');
			if($clsTransaction->updateOne($transaction_id, array(
				'transaction_type' => $transaction_type,
				'code' => Input::post('code'),
				'send_date' => $clsISO->toTime($send_date),
				'project_id' => Input::post('project_id', 0),
				//'status_id' => _TRANSACTION_STATUS_NEW,
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'billing_store' => json_encode($billing_store, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'user_id_update' => $profile_id,
				'upd_date' => time()
			))){
				$msg = '_success';
			}
		} else {
			$transaction_id = $clsTransaction->getMaxId();
			$more_information = array();
			$more_information['bank_id'] = Input::post('bank_id');
			$more_information['logs'][$clsISO->getUniqid()] = array(
				'reg_date' => time(),
				'user_id' => $profile_id,
				'content' => sprintf('<strong>%s</strong> tạo xác nhận hoa hồng <strong>%s</strong>',
					$clsProfile->getFullName($profile_id, $oneProfile), Input::post('code'))
			);
			if($clsTransaction->insert(array(
				'transaction_id' => $transaction_id,
				'transaction_type' => $transaction_type,
				'code' => Input::post('code'),
				'staff_id' => $profile_id,
				'send_date' => $clsISO->toTime($send_date),
				'project_id' => Input::post('project_id', 0),
				'status_id' => _TRANSACTION_STATUS_NEW,
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'billing_store' => json_encode($billing_store, JSON_UNESCAPED_UNICODE),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			))){
				$msg = '_success';
			}
		}
	} else {
		$msg = "_empty";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_view(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsTransaction = new Transaction();
	##
	$uid = $clsISO->getUniqid();
	$transaction_id = (int) Input::post('transaction_id', 0);
	$field = "`t1`.*,`t2`.`full_name`,`t2`.`phone`,`t2`.`address`,`t2`.`department_id`,`t2`.`role_id`
	,`t2`.`more_information` as `prof_information`";
	$oneTransaction = $dbconn->getRow("select {$field} from {$clsTransaction->tbl} as `t1` 
		inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 
		where `t1`.`transaction_id`='{$transaction_id}'");
	$project_id = $oneTransaction['project_id'];
	$more_information = $oneTransaction['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$list_logs = isset($more_information['logs']) 
		? $more_information['logs'] : array();
	$smarty->assign('list_logs', $list_logs);
	
	$prof_information = $oneTransaction['prof_information'];
	$prof_information = $clsISO->to_array_json($prof_information);
	$banks_info = isset($prof_information['banks_info']) ? $prof_information['banks_info'] : array();
	$oneBank = isset($banks_info[$more_information['bank_id']]) 
		? $banks_info[$more_information['bank_id']] : array(); 
	$smarty->assign('oneBank', $oneBank);
	#
	$department_id = $oneTransaction['department_id'];
	$field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`";
	$oneLeader = $clsProfile->getByCond("department_id='{$department_id}' and `role_id`='"._ROLE_GD_SALE."'", $field);
	// $clsISO->print_pre($oneLeader); die();
	$oneProject = $clsProperty->getOne($project_id);
	$project_information = $oneProject['more_information'];
	$project_information = $clsISO->to_array_json($project_information);
	$project_admin_id = $project_information['project_admin_id'];
	$project_director_id = $project_information['project_director_id'];
	$smarty->assign('project_admin_id', $project_admin_id);
	$smarty->assign('project_director_id', $project_director_id);
	##
	$tmp = array();
	$arr_profiles_in = array($project_admin_id, $project_director_id, _PROFILE_CEO_ID);
	$ls = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $arr_profiles_in).")", $field);
	if(!empty($ls)){
		foreach($ls as $key => $val){
			$tmp[$val[$clsProfile->pkey]] = $val;
		}
		unset($ls);
	}
	$list_work_progress = array(
		'leader' => array(
			'id' => $oneLeader[$clsProfile->pkey],
			'name' => $clsProfile->getFullName($oneLeader[$clsProfile->pkey], $oneLeader)
		), 'admin' => array(
			'id' => $project_admin_id,
			'name' => $clsProfile->getFullName($project_admin_id, $tmp[$project_admin_id])
		), 'project' => array(
			'id' => $project_director_id,
			'name' => $clsProfile->getFullName($project_director_id, $tmp[$project_director_id])
		), 'ceo' => array(
			'id' => $_PROFILE_CEO_ID,
			'name' => $clsProfile->getFullName(_PROFILE_CEO_ID, $tmp[_PROFILE_CEO_ID])
		)
	);
	$smarty->assign('list_work_progress', $list_work_progress);
	
	$billing_store = $oneTransaction['billing_store'];
	$billing_store = !empty($billing_store) 
		? json_decode(html_entity_decode($billing_store), true) : array();
	$smarty->assign('uid', $uid);
	$smarty->assign('transaction_id', $transaction_id);
	$smarty->assign('oneTransaction', $oneTransaction);
	$smarty->assign('billing_store', $billing_store);
	$smarty->assign('billing_store', $billing_store);
	// Return
	$html = $core->build('_ajax.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_print(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	$clsStock = new Stock();
	$clsBilling = new Billing();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsTransaction = new Transaction();
	##
	$uid = $clsISO->getUniqid();
	$transaction_id = (int) Input::post('transaction_id', 0);
	$field = "`t1`.*,`t2`.`full_name`,`t2`.`phone`,`t2`.`address`,`t2`.`department_id`,`t2`.`role_id`
	,`t2`.`more_information` as `prof_information`";
	$oneTransaction = $dbconn->getRow("select {$field} from {$clsTransaction->tbl} as `t1` 
		inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 
		where `t1`.`transaction_id`='{$transaction_id}'");
	$project_id = $oneTransaction['project_id'];
	$more_information = $oneTransaction['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$prof_information = $oneTransaction['prof_information'];
	$prof_information = $clsISO->to_array_json($prof_information);
	$banks_info = isset($prof_information['banks_info']) ? $prof_information['banks_info'] : array();
	$oneBank = isset($banks_info[$more_information['bank_id']]) 
		? $banks_info[$more_information['bank_id']] : array(); 
	$smarty->assign('oneBank', $oneBank);
	
	$department_id = $oneTransaction['department_id'];
	$field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`";
	$oneLeader = $clsProfile->getByCond("department_id='{$department_id}' and `role_id`='"._ROLE_GD_SALE."'", $field);
	$smarty->assign('oneLeader', $oneLeader);
	// $clsISO->print_pre($oneLeader); die();
	
	$oneProject = $clsProperty->getOne($project_id);
	$project_information = $oneProject['more_information'];
	$project_information = $clsISO->to_array_json($project_information);
	$project_director_id = $project_information['project_director_id'];
	$project_admin_id = $project_information['project_admin_id'];
	$smarty->assign('project_director_id', $project_director_id);
	$smarty->assign('project_admin_id', $project_admin_id);
	
	$billing_store = $oneTransaction['billing_store'];
	$billing_store = !empty($billing_store) 
		? json_decode(html_entity_decode($billing_store), true) : array();
	$smarty->assign('uid', $uid);
	$smarty->assign('transaction_id', $transaction_id);
	$smarty->assign('oneTransaction', $oneTransaction);
	$smarty->assign('billing_store', $billing_store);
	$smarty->assign('billing_store', $billing_store);
	// Return
	$html = $core->build('_ajax.print.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
