<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_vat(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$deviceType;
	
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	$columnNum = ($deviceType=='phone') ? 2 : 4;
	$assign_list["columnNum"] = $columnNum;
	/*=============Title & Description Page==================*/
	$title_page = 'VAT đã xuất - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_crawl(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsVAT = new VAT();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';		
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$spreadsheetName = 'Danh Sách'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1Z5XxnwmUFKj_sdfl1hVCI4KIqZecM6QXvXf9xa04ANw';
	$response = $service->spreadsheets_values->get($spreadsheetId, $spreadsheetName);
	$tblData = $response->getValues();
	if(!empty($tblData)){
		for($i=3; $i<count($tblData); $i++){
			$symbol = $tblData[$i][1];
			$contract_code = $tblData[$i][2];
			$contract_date = $tblData[$i][3];
			$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
			$partner_name = $tblData[$i][4];
			$stock_code = $tblData[$i][5];
			$staff_name = $tblData[$i][6];
			$amount = $tblData[$i][7];
			$deposit_price = $tblData[$i][8];
			$final_price = $tblData[$i][9];
			$status_name = $tblData[$i][11];
			$final_date = $tblData[$i][12];
			$final_date = !empty($final_date) ? $clsISO->toTime($final_date) : 0;
			// $clsISO->print_pre($final_date); die();
			$more_information = array();
			if(!empty($partner_name)){
				$more_information['partner_name'] = $partner_name;
				$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`property_code`='{$partner_name}' or `slug`='{$core->replaceSpace($partner_name)}' or `slug_vn`='{$core->replaceSpace($partner_name)}')", $clsProperty->pkey);
				$partner_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
				$more_information['partner_id'] = $partner_id;
			}
			if(!empty($status_name)){
				$tmp = $clsProperty->getByCond("`property_type`='VAT_STATUS' 
					and `slug`='{$core->replaceSpace($status_name)}'", $clsProperty->pkey);
				$status_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
			}
			if(!empty($staff_name)){
				$tmp = explode(',', $stock_code);
				$parts = @explode(',', $staff_name);
				if(!empty($parts)){ $ii = 0;
					foreach($parts as $staff_name){
						$all = $clsProfile->getByCond("`full_name_slug`='".$core->replaceSpace(trim($staff_name))."'", $clsProfile->pkey);
						$staff_id = !empty($all) ? $all[$clsProfile->pkey] : 0;
						$more_information['billing'][$clsISO->getUniqid()] = array(
							'staff_id' => $staff_id,
							'staff_name' => $staff_name,
							'stock_code' => $tmp[$ii]
						);
						++$ii;
					}
				}
			}
			if($clsVAT->countItem("`symbol`='{$symbol}' and `contract_code`='{$contract_code}'") == 0){
				$vat_id = $clsVAT->getMaxId();
				$clsVAT->insert(array(
					$clsVAT->pkey => $vat_id,
					'symbol' => $symbol,
					'contract_code' => $contract_code,
					'contract_date' => $contract_date,
					'vat_type' => _VAT_TYPE_VATOUT,
					'amount' => $clsISO->processSmartNumber($amount),
					'deposit_price' => $clsISO->processSmartNumber($deposit_price),
					'final_price' => $clsISO->processSmartNumber($final_price),
					'final_date' => $final_date,
					'status_id' => $status_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'reg_date' => $contract_date,
					'upd_date' => $contract_date,
					'user_id' => $profile_id,
					'user_id_update' => $profile_id
				));
			}
		}
	}
	$clsISO->print_pre($tblData); die();
}
function default_list_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsVAT = new VAT();
	$clsProperty = new Property();
	
	$cond = "`is_trash`=0";
	$keyword = Input::post('keyword');
	$vat_type = (int) Input::post('vat_type', 0);
	$status_id = (int) Input::post('status_id', 0);
	if($vat_type > 0) $cond.= " and `vat_type`='{$vat_type}'";
	if($status_id > 0) $cond.= " and `status_id`='{$status_id}'";
	if(!empty($keyword)){
		$cond.= " and (`symbol` like '%{$keyword}%' 
			or `contract_code` like '%{$keyword}%' 
			or JSON_EXTRACT(`more_information`,\"$.partner_name\") like '%{$keyword}%' 
			or JSON_EXTRACT(`more_information`,\"$.billing.*.stock_code\") like '%{$keyword}%'
			or JSON_EXTRACT(`more_information`,\"$.billing.*.staff_name\") like '%{$keyword}%'
		)";
	}
	#- Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 30);
	$total_record = $clsVAT->countItem($cond);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	// $dbconn->debug = true;
	$list_VATs = $clsVAT->getAll($cond." order by `reg_date` DESC, `contract_code` DESC".$limitCond);
	// $clsISO->print_pre($list_VATs); die();
	if(!empty($list_VATs)){
		$arr_property_cached = $arr_prop_cached = array();
		foreach($list_VATs as $key => $val){
			$vat_type = $val['vat_type'];
			$status_id = $val['status_id'];
			$amount = $clsISO->processSmartNumber($val['amount']);
			$deposit_price = $clsISO->processSmartNumber($val['deposit_price']);
			$final_price = $clsISO->processSmartNumber($val['final_price']);
			$unpaid_price = ($amount - $deposit_price - $final_price);
			$list_VATs[$key]['unpaid_price'] = $unpaid_price;
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_VATs[$key]['more_information'] = $more_information;
			$list_billings = isset($more_information['billing']) ? $more_information['billing'] : array();
			$list_VATs[$key]['list_billings'] = $list_billings;
			$list_VATs[$key]['total_billings'] = !empty($list_billings) ? count($list_billings) : 1;
			if($vat_type > 0 && !isset($arr_property_cached[$vat_type])){
				$arr_property_cached[$vat_type] = $clsProperty->getTitle($vat_type);
			}
			$list_VATs[$key]['vat_type_name'] = $arr_property_cached[$vat_type];
			if($status_id > 0 && !isset($arr_property_cached[$status_id])){
				$arr_prop_cached[$status_id] = $clsProperty->getOne($status_id, "title,bgcolor,textcolor");
				$arr_property_cached[$status_id] = $clsProperty->getTextColor($status_id, $arr_prop_cached[$status_id]);
			}
			$list_VATs[$key]['status_name'] = $arr_property_cached[$status_id];
			$list_VATs[$key]['bgcolor'] = $arr_prop_cached[$status_id]['bgcolor'];
		}
	}
	$smarty->assign('list_VATs', $list_VATs);
	
	$list_briefs = array(
		'_TOTAL' => 'Tổng',
		'_FINAL' => 'Đã tất toán',
		'_UNPAID' => 'Chưa thanh toán',
		'_DEPOSIT' => 'Đã tạm ứng' 
	);
	$html_briefs = "";
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
		$html_briefs.= '<div class="brief-item p-3 bg-white">
			<p class="fs-16 mb-2 text-dark">'.$text.'</p>
			<h3 class="fs-18 mb-0">'.$clsISO->formatPrice($total).'</h3>
		</div>';
	}
	// Return
	$html = $core->build('_ajax.list_VAT.tpl');
	echo json_encode(array(
		'html' => $html,
		'html_briefs' => $html_briefs,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'per_page' => $per_page,
	)); die();
}
function default_open_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsVAT = new VAT();
	$uid = $clsISO->getUniqid();
	$vat_id = (int) Input::post('vat_id', 0);
	###
	$action = '_add';
	$titlePage = 'Thêm mới VAT';
	$oneVAT = array(
		'reg_date' => time(),
		'upd_date' => time(),
		'user_id' => $profile_id,
		'vat_type' => _VAT_TYPE_VATOUT,
		'status_id' => _VAT_STATUS_UNPAID,
		'user_id_update' => $profile_id,
		'symbol' => $clsVAT->getSymbol(),
		'contract_code' => $clsVAT->getContractCode(),
		'contract_date' => time()
	);
	$more_information = array();
	$more_information['partner_id'] = 0;
	$more_information['partner_name'] = "";
	if($vat_id > 0){
		$action = '_edit';
		$oneVAT = $clsVAT->getOne($vat_id);
		$more_information = $oneVAT['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$titlePage = 'Chỉnh sửa VAT';
	}
	$assign_list["vat_id"] = $vat_id;
	$assign_list["action"] = $action;
	$assign_list["oneVAT"] = $oneVAT;
	$assign_list["more_information"] = $more_information;
	$assign_list["titlePage"] = $titlePage;
	// Return
	$html = $core->build('_ajax.vat.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsVAT = new VAT();
	
	$msg = "_error";
	$vat_id = (int) Input::post('vat_id', 0);
	$symbol = Input::post('symbol');
	$contract_code = Input::post('contract_code');
	$contract_date = Input::post('contract_date');
	$contract_date = !empty($contract_date) ? $clsISO->toTime($contract_date) : 0;
	$amount = Input::post('amount');
	$deposit_price = Input::post('deposit_price');
	$final_price = Input::post('final_price');
	$final_date = Input::post('final_date');
	$final_date = !empty($final_date) ? $clsISO->toTime($final_date) : 0;
	$status_id = Input::post('status_id', _VAT_STATUS_UNPAID);
	$amount = !empty($amount) ? $clsISO->processSmartNumber($amount) : 0;
	$deposit_price = !empty($final_price) ? $clsISO->processSmartNumber($deposit_price) : 0;
	$final_price = !empty($final_price) ? $clsISO->processSmartNumber($final_price) : 0;
	$unpaid_price = $amount - $deposit_price - $final_price;
	if($status_id == _VAT_STATUS_SOLD) {
		$final_price = $amount; // Gán tiền tất toán = tổng tiền
		$unpaid_price = $deposit_price = 0; // Reset {unpaid_price} && {deposit_price}
	}
	$more_information = Input::post('more_information');
	if($vat_id > 0){
		if($clsVAT->countItem("{$clsVAT->pkey}<>'{$vat_id}' and `contract_code`='{$contract_code}'") > 0){
			$msg = "_duplicated"; 
		} else {
			if($clsVAT->updateOne($vat_id, array(
				'symbol' => $symbol,
				'contract_code' => $contract_code,
				'contract_date' => $contract_date,
				'vat_type' => Input::post('vat_type'),
				'amount' => $amount,
				'deposit_price' => $deposit_price,
				'final_price' => $final_price,
				'final_date' => $final_date,
				'unpaid_price' => $unpaid_price,
				'status_id' => $status_id,
				'notes' => Input::post('notes'),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'upd_date' => time(),
				'user_id_update' => $profile_id
			))){
				$msg = "_success";
			}
		}
	} else {
		if($clsVAT->countItem("`contract_code`='{$contract_code}'") > 0){
			$msg = "_duplicated"; 
		} else {
			$vat_id = $clsVAT->getMaxId();
			if($clsVAT->insert(array(
				$clsVAT->pkey => $vat_id,
				'symbol' => $symbol,
				'contract_code' => $contract_code,
				'contract_date' => $contract_date,
				'vat_type' => Input::post('vat_type'),
				'amount' => $amount,
				'deposit_price' => $deposit_price,
				'final_price' => $final_price,
				'final_date' => $final_date,
				'unpaid_price' => $unpaid_price,
				'status_id' => $status_id,
				'notes' => Input::post('notes'),
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
function default_delete_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsVAT = new VAT();
	###
	$msg = "_error";
	$vat_id = (int) Input::post('vat_id', 0);
	if($vat_id > 0){
		if($clsVAT->updateOne($vat_id, array(
			'is_trash' => 1
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_add_line(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$gId = Input::post('gId');
	$uid = $clsISO->getUniqid();
	$total_line = (int) Input::post('total_line', 0);
	$html = '<tr gId="'.$gId.'" class="'.$gId.'">
		<td class="text-center">'.($total_line+1).'</td>
		<td><input type="text" name="more_information[billing]['.$uid.'][stock_code]" placeholder="Mã căn" class="form-control" /></td>
		<td class="text-left">
			<input type="hidden" class="'.$uid.'_id" name="more_information[billing]['.$uid.'][staff_id]" />
			<input type="text" uid="'.$uid.'" to_field="'.$staff_id.'" name="more_information[billing]['.$uid.'][staff_name]" placeholder="Sale bán" class="form-control autocomplete" onChange="$Core.acc._handle_change(this, event)" tp="staff_name" data-source="/index.php?mod='.$mod.'&act=get_json_staffs" />
		</td>
		<td class="text-center"><button gId="'.$gId.'" type="button" onClick="$Core.acc.add_line(this, event)" 
			class="btn btn-icon btn-outline-primary"><i class="bx bx-plus"></i></button>
		</td>
	</tr>';
	// Return
	echo $html; die();
}
function default_get_property(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProperty = new Property();
	$term = Input::get('term');
	$property_type = Input::get('property_type');
	
	$_results = array();
	if(!empty($property_type)){
		$field = "{$clsProperty->pkey},`title`";
		$cond = "`is_locked`=0 and `property_type`='{$property_type}'";
		if(!empty($term)) $cond.= " and (`slug` like '%".$core->replaceSpace($term)."%' 
			or `slug_vn` like '%".$core->replaceSpace($term)."%')";
		$list_items = $clsProperty->getAll($cond, $field);
		if(!empty($list_items)){
			foreach($list_items as $key => $val){
				$_results[] = array(
					'id' => $val[$clsProperty->pkey],
					'label' => $val['title'],
					'value' => $val['title']
				);
			}
		}
	}
	// Return
	echo json_encode($_results); die();
}
function default_get_json_staffs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$_results = array();
	$term = Input::get('term');
	$cond = "`is_trash`=0"; // and `is_active`=1
	if(!empty($term)){
		$cond.= " and `full_name_slug` like '%".$core->replaceSpace($term)."%'";
	}
	$field = "{$clsProfile->pkey},`first_name`,`last_name`,`full_name`";
	$list_staffs = $clsProfile->getAll($cond, $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$full_name = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
			$_results[] = array(
				'id' => $val[$clsProfile->pkey],
				'label' => $full_name,
				'value' => $full_name,
			);
		}
	}
	// Return
	echo json_encode($_results); die();
}
function default_done_VAT(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsVAT = new VAT();
	###
	$msg = "_error";
	$vat_id = (int) Input::post('vat_id', 0);
	if($vat_id > 0){
		$oneVAT = $clsVAT->getOne($vat_id);
		if($clsVAT->updateOne($vat_id, array(
			'deposit_price' => 0,
			'final_date' => time(),
			'status_id' => _VAT_STATUS_SOLD,
			'final_price' => $oneVAT['amount']
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
