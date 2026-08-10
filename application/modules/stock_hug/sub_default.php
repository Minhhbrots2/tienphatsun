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
	,$keyword_page,$clsConfiguration,$clsISO,$deviceType;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsPolicy = new Policy();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	###
	$columnNum = ($deviceType == 'phone') ? 2 : 4;
	$assign_list["columnNum"] = $columnNum;
	###
	$list_briefs = array(
		'_TOTAL' => array(
			'title' => 'Tổng quỹ', 
			'subtitle' => 'Tổng số cọc đã cọc vào CĐT'
		),
		'_SOLD' => array(
			'title' => 'Cọc đã bán', 
			'subtitle' => 'Tổng số cọc đã bán được'
		),
		'_NOT_SOLD' => array(
			'title' => 'Cọc chưa bán', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_UNLOCK' => array(
			'title' => 'Chờ hoàn', 
			'subtitle' => 'Tổng số cọc huỷ căn chờ hoàn'
		),
		'_BALANCE' => array(
			'title' => 'Tồn cọc LM', 
			'subtitle' => 'Tổng tồn cọc của LM trong dự án Masteri'
		)
	);
	$assign_list["list_briefs"] = $list_briefs;
	
	/*=============Title & Description Page==================*/
	$title_page = 'Quỹ ôm Masteri - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$stock_hug_id = (int) Input::post('stock_hug_id', 0);
	$smarty->assign('uid', $uid);
	$smarty->assign('stock_hug_id', $stock_hug_id);
	
	$action = '_add';
	$oneStockHug = $more_information = array(
		'bedroom_type' 		=> 0,
		'agency_id'			=> 0,
		'sale_agency_id' 	=> 0,
		//'is_contributed'	=> 0,
		//'refund_2_sides'	=> 0,
		'status_id' => _STOCK_HUG_STATUS_DEF_ID
	);
	if($stock_hug_id > 0){
		$action = '_edit';
		$oneStockHug = $clsStockHug->getOne($stock_hug_id);
		$more_information = $oneStockHug['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
	}
	$smarty->assign('action', $action);
	$smarty->assign('oneStockHug', $oneStockHug);
	$smarty->assign('more_information', $more_information);
	
	// Return
	$html = $core->build("_ajax.open.tpl");
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_crawl(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
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
	$spreadsheetName = 'ĐÃ THANH TOÁN 2023'; // here we use the name of the Sheet to get all the rows
	// $spreadsheetId = '11TKaIAWFhTunIp_p5OdWA1nnuHuPHlek08a_9ktgU4g';
	$spreadsheetId = '11TKaIAWFhTunIp_p5OdWA1nnuHuPHlek08a_9ktgU4g';
	$response = $service->spreadsheets_values->get($spreadsheetId, $spreadsheetName);
	$tblData = $response->getValues();
	###
	$errors = array();
	$total_inserted = $total_errors = 0;
	if(!empty($tblData)){
		for($i=4; $i<=count($tblData); $i++){
			$stock_code = trim($tblData[$i][1]);
			if(!empty($stock_code)){
				$oneStock = $clsStock->getByCond("ms_code='{$stock_code}' limit 0,1", "{$clsStock->pkey},bedroom_id");
				$stock_id = !empty($oneStock) ? $oneStock[$clsStock->pkey] : 0;
				$bedroom_type = !empty($oneStock) ? $oneStock['bedroom_id'] : 0;
				$customer_name = $tblData[$i][4];
				$agency_name = $tblData[$i][5];
				$tmp = $clsProperty->getByCond("property_type='_AGENCY' and title_vn='{$agency_name}'", $clsProperty->pkey);
				$agency_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
				$deposit_price = $tblData[$i][6];
				//$deposit_fh = $tblData[$i][6];
				//$deposit_ns = $tblData[$i][7];
				//$is_contributed = ($tblData[$i][8] == 'ok') ? 1 : 0;
				$deposit_date = $tblData[$i][7];
				$otp_date = $tblData[$i][8];
				$deposit_date = !empty($deposit_date) ? $clsISO->convertTextToTime($deposit_date) : 0;
				$otp_date = !empty($otp_date) && $otp_date != 'Không ký OTP' ? $clsISO->convertTextToTime($otp_date) : 0;
				$stock_code_sale = trim($tblData[$i][11]);
				$agency_sale = trim($tblData[$i][12]);
				if(!empty($agency_sale)){
					$tmp = $clsProperty->getByCond("property_type='_AGENCY' and title_vn='{$agency_sale}'", $clsProperty->pkey);
					$sale_agency_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
				} else {
					$sale_agency_id = 0;
				}
				$sale_date = $tblData[$i][13];
				$sale_date = !empty($sale_date) ? $clsISO->convertTextToTime($sale_date) : 0;
				$deposit_refund = trim($tblData[$i][14]);
				//$refund_2_sides = trim($tblData[$i][19] == 'ok') ? 1 : 0;
				$alliance_balance = trim($tblData[$i][15]);
				$status_name = trim($tblData[$i][16]);
				$notes = trim($tblData[$i][17]);
				
				$more_information = array();
				$more_information['notes'] = $notes;
				
				$status_id = !empty($agency_sale) ? _STOCK_HUG_STATUS_SOLD_ID : _STOCK_HUG_STATUS_DEF_ID;
				if(!empty($status_name)){
					$tmp = $clsProperty->getByCond("property_type='_STATUS_STOCK_HUG' 
						and slug='".$core->replaceSpace($status_name)."'", $clsProperty->pkey);
					if(!empty($tmp)) $status_id = $tmp[$clsProperty->pkey];
				}
				$reg_date = $deposit_date;
				if(empty($deposit_date)){
					$reg_date = time();
				}
				$oneStockHug = $clsStockHug->getByCond("stock_code='{$stock_code}'");
				if(!empty($oStockHug)){
					/* if($clsStockHug->updateOne($oneStockHug[$clsStockHug->pkey], array(
						'stock_id' => $stock_id,
						'stock_code' => $stock_code,
						'bedroom_type' => $bedroom_type,
						'customer_name' => $customer_name,
						'agency_id' => $agency_id,
						'deposit_price' => $clsISO->processSmartNumber($deposit_price),
						'deposit_fh' => $clsISO->processSmartNumber($deposit_fh),
						'deposit_ns' => $clsISO->processSmartNumber($deposit_ns),
						'is_contributed' => $is_contributed,
						'deposit_date' => $deposit_date,
						'otp_date' => $otp_date,
						'sale_agency_id' => $sale_agency_id,
						'sale_date' => $sale_date,
						'deposit_refund' => $clsISO->processSmartNumber($deposit_refund),
						'refund_2_sides' => $refund_2_sides,
						'alliance_balance' => $clsISO->processSmartNumber($alliance_balance),
						'status_id' => $status_id,
						'upd_date' => time(),
						'user_id_update' => $profile_id
					))){
						$total_inserted += 1;
					} */
				} else {
					if($clsStockHug->insert(array(
						$clsStockHug->pkey => $clsStockHug->getMaxId(),
						'stock_id' => $stock_id,
						'stock_code' => $stock_code,
						'bedroom_type' => $bedroom_type,
						'customer_name' => $customer_name,
						'agency_id' => $agency_id,
						'deposit_price' => $clsISO->processSmartNumber($deposit_price),
						// 'deposit_fh' => $clsISO->processSmartNumber($deposit_fh),
						// 'deposit_ns' => $clsISO->processSmartNumber($deposit_ns),
						// 'is_contributed' => $is_contributed,
						'deposit_date' => $deposit_date,
						'otp_date' => $otp_date,
						'sale_agency_id' => $sale_agency_id,
						'sale_date' => $sale_date,
						'deposit_refund' => $clsISO->processSmartNumber($deposit_refund),
						// 'refund_2_sides' => $refund_2_sides,
						// 'alliance_balance' => $clsISO->processSmartNumber($alliance_balance),
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'status_id' => $status_id,
						'reg_date' => $deposit_date,
						'upd_date' => $deposit_date,
						'user_id' => $profile_id,
						'user_id_update' => $profile_id
					))){
						$total_inserted += 1;
					} else {
						$errors[] = $i;
						$total_errors += 1;
					}
				}
			} else {
				$errors[] = $i;
				$total_errors += 1;
			}
		}
	}
	// Return
	echo $total_inserted; die();	
}
function default_list(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	
	$cond = "`is_trash`=0";
	$keysearch = Input::post('keysearch');
	$keysearch = trim($keysearch); // Replace space
	$status_id = (int) Input::post('status_id', 0);
	$date_field = Input::post('date_field');
	$start_date = Input::post('start_date');
	$to_date = Input::post('to_date');
	if(!empty($keysearch)) $cond.= " and (`stock_code` like '%{$keysearch}%' or `customer_name` like '%{$keysearch}%')";
	if($status_id > 0) $cond.= " and `status_id`='{$status_id}'";
	if(!empty($start_date) && !empty($to_date)){
		$start_date = $clsISO->toTime($start_date);
		$to_date = $clsISO->toTime($to_date. " 23:59:59");
		$cond.= " and (`{$date_field}` between {$start_date} and {$to_date})";
	}
	#- Begin pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 30);
	$total_record = $clsStockHug->countItem($cond);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$list_stocks = $clsStockHug->getAll($cond." order by `reg_date` DESC".$limitCond);
	// $clsISO->print_pre($list_stocks); die();
	$html = '';
	if(!empty($list_stocks)){ $ii = 0; // Init
		$arr_property_cached = $arr_prop_cached = array();
		foreach($list_stocks as $key => $val){
			$stock_hug_id = $val[$clsStockHug->pkey];
			$stock_id = $val['stock_id'];
			$stock_code = $val['stock_code'];
			$bedroom_type = (int) $val['bedroom_type'];
			$status_id = (int) $val['status_id'];
			$agency_id = (int) $val['agency_id'];
			$sale_agency_id = (int) $val['sale_agency_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($bedroom_type > 0 && !isset($arr_property_cached[$bedroom_type])){
				$arr_property_cached[$bedroom_type] = $clsProperty->getTitle($bedroom_type);
			}
			if($agency_id > 0 && !isset($arr_property_cached[$agency_id])){
				$arr_property_cached[$agency_id] = $clsProperty->getTitleQR($agency_id);
			}
			if($sale_agency_id > 0 && !isset($arr_property_cached[$sale_agency_id])){
				$arr_property_cached[$sale_agency_id] = $clsProperty->getTitleQR($sale_agency_id);
			}
			if($status_id > 0 && !isset($arr_property_cached[$status_id])){
				$arr_prop_cached[$status_id] = $clsProperty->getOne($status_id, "title,bgcolor,textcolor");
				$arr_property_cached[$status_id] = $clsStockHug->getStatus($status_id, $arr_prop_cached[$status_id]);
			}
			$alliance_balance = 50000000;
			$html.= '<tr ondblclick="$Core.stock_hug.open(this,event)" stock_hug_id="'.$stock_hug_id.'" style="background:'.$arr_prop_cached[$status_id]['bgcolor'].'">
				<td class="text-center">'.($ii+1).'</td>
				<td><a href="javascript:void(0);" data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id='.$stock_id.'" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="350">'.$val['stock_code'].'</a></td>
				<td>'.$arr_property_cached[$bedroom_type].'</td>
				<td>'.$val['customer_name'].'</td>
				<td  class="text-center">'.$arr_property_cached[$agency_id].'</td>
				<td>'.$clsISO->formatPrice($val['deposit_price']).'</td>
				<!-- <td class="text-center">'.(!empty($val['deposit_fh']) ? $clsISO->formatPrice($val['deposit_fh']) : "--").'</td>
				<td class="text-center">'.(!empty($val['deposit_ns']) ? $clsISO->formatPrice($val['deposit_ns']) : "--").'</td>
				<td class="text-center">'.($val['is_contributed'] ? 'OK' : '').'</td> -->
				<td class="text-center">'.(!empty($val['deposit_date']) ? $clsISO->convertTimeToText($val['deposit_date']) : '--').'</td>
				<td class="text-center">'.(!empty($val['otp_date']) ? $clsISO->convertTimeToText($val['otp_date']) : '--').'</td>
				<td class="text-center">'.(!empty($val['check_date']) ? $clsISO->convertTimeToText($val['check_date']) : '--').'</td>
				<td class="text-center">'.$arr_property_cached[$sale_agency_id].'</td>
				<td>'.(!empty($val['sale_date']) ? $clsISO->convertTimeToText($val['sale_date']) : "").'</td>
				<td>'.(!empty($val['deposit_refund']) ? $clsISO->formatPrice($val['deposit_refund']) : "").'</td>
				<!-- <td class="text-center">'.($val['refund_2_sides'] ? 'OK' : '').'</td> -->
				<td class="text-center">'.($status_id==_STOCK_HUG_STATUS_SOLD_ID ? "0" : $clsISO->formatPrice($alliance_balance)).'</td>
				<td class="text-center">'.$arr_property_cached[$status_id].'</td>
				<td>'.(!empty($more_information['notes']) ? $more_information['notes'] : "").'</td>
				<td class="text-center">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
				<td class="text-center">
					<div class="dropdown">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
							data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu w-px-100">
							<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.stock_hug.open(this,event)" stock_hug_id="'.$stock_hug_id.'"><i class="bx bx-pencil me-1"></i> Sửa</a>
							<a class="dropdown-item" href="javascript:void(0);" onClick="$Core.stock_hug.delete(this,event)" stock_hug_id="'.$stock_hug_id.'"><i class="bx bx-trash me-1"></i> Xóa</a>
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
		'_NOT_SOLD' => array(
			'title' => 'Chưa bán', 
			'subtitle' => 'Tổng số cọc tồn chưa bán được'
		),
		'_UNLOCK' => array(
			'title' => 'Chờ hoàn', 
			'subtitle' => 'Tổng số cọc huỷ căn chờ hoàn'
		),
		'_BALANCE' => array(
			'title' => 'Tồn cọc LM', 
			'subtitle' => 'Tổng tồn cọc của LM trong dự án Masteri'
		)
	);
	foreach($list_briefs as $key => $val){
		$onClick = $props = "";
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
		$total_price = $total * 50000000;
		$html_brief.= '<div'.(!empty($onClick) ? ' onClick="'.$onClick.'"': '').' '.$props.' 
			class="border cursor-pointer flex-fill p-3 rounded-2">
			<div class="d-flex mb-2 align-items-center justify-content-between">
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
function default_delete(){
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
function default_save(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockHug = new StockHug();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$stock_hug_id = (int) Input::post('stock_hug_id', 0);
	$deposit_date = Input::post('deposit_date');
	$otp_date = Input::post('otp_date');
	$check_date = Input::post('check_date');
	$sale_date = Input::post('sale_date');
	$stock_code = Input::post('stock_code');
	$deposit_date = !empty($deposit_date) ? $clsISO->toTime($deposit_date) : 0;
	$otp_date = !empty($otp_date) ? $clsISO->toTime($otp_date) : 0;
	$check_date = !empty($check_date) ? $clsISO->toTime($check_date) : 0;
	$sale_date = !empty($sale_date) ? $clsISO->toTime($sale_date) : 0;
	$deposit_price = Input::post('deposit_price');
	//$deposit_fh = Input::post('deposit_fh');
	//$deposit_ns = Input::post('deposit_ns');
	$deposit_refund = Input::post('deposit_refund');
	$deposit_price = !empty($deposit_price) ? $clsISO->processSmartNumber($deposit_price) : 0;
	//$deposit_fh = !empty($deposit_fh) ? $clsISO->processSmartNumber($deposit_fh) : 0;
	//$deposit_ns = !empty($deposit_ns) ? $clsISO->processSmartNumber($deposit_ns) : 0;
	$deposit_refund = !empty($deposit_refund) ? $clsISO->processSmartNumber($deposit_refund) : 0;
	if($stock_hug_id == 0){
		$more_information = array();
		$more_information['notes'] = Input::post('notes');
		$stock_code = preg_replace('/\s+/', '', $stock_code);
		$oneStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", $clsStock->pkey);
		$stock_id = !empty($oneStock) ? $oneStock[$clsStock->pkey] : 0;
		// $clsStockHug->setDebug();
		$stock_hug_id = $clsStockHug->getMaxId();
		if($clsStockHug->insert(array(
			$clsStockHug->pkey => $stock_hug_id,
			'stock_id' => $stock_id,
			'stock_code' => Input::post('stock_code'),
			'bedroom_type' => (int) Input::post('bedroom_type'),
			'customer_name' => Input::post('customer_name'),
			'agency_id' => Input::post('agency_id'),
			'deposit_price' => $deposit_price,
			//'deposit_fh' => $deposit_fh,
			//'deposit_ns' => $deposit_ns,
			//'is_contributed' => Input::post('is_contributed'),
			'deposit_date' => $deposit_date,
			'otp_date' => $otp_date,
			'check_date' => $check_date,
			'sale_agency_id' => Input::post('sale_agency_id'),
			'sale_date' => $sale_date,
			'deposit_refund' => $deposit_refund,
			//'refund_2_sides' => Input::post('refund_2_sides'),
			'status_id' => Input::post('status_id'),
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
		$more_information['notes'] = Input::post('notes');
		if($clsStockHug->updateOne($stock_hug_id, array(
			'stock_code' => Input::post('stock_code'),
			'bedroom_type' => (int) Input::post('bedroom_type'),
			'customer_name' => Input::post('customer_name'),
			'agency_id' => Input::post('agency_id'),
			'deposit_price' => $deposit_price,
			//'deposit_fh' => $deposit_fh,
			//'deposit_ns' => $deposit_ns,
			//'is_contributed' => Input::post('is_contributed'),
			'deposit_date' => $deposit_date,
			'otp_date' => $otp_date,
			'check_date' => $check_date,
			'sale_agency_id' => Input::post('sale_agency_id'),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'status_id' => Input::post('status_id'),
			'sale_date' => $sale_date,
			'deposit_refund' => $deposit_refund,
			//'refund_2_sides' => Input::post('refund_2_sides'),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";		
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("StockHug","update");
		}
	}
	// Return
	echo $msg; die();
}
?>