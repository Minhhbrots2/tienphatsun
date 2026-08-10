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
function stock_open_import(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$deviceType,$profile_id;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	
	$list_groups = array(
		_BLOCK_TYPE_LOWFLOOR_SALE => array(
			'field' => 'spreadsheetId',
			'title' => 'Thấp tầng'
		),
		_STOCK_TYPE_LEASING => array(
			'field' => 'spreadsheetLeasingId',
			'title' => 'Cho thuê'
		),
		_BLOCK_TYPE_HIGHLEVEL_SALE => array(
			'field' => 'spreadsheetStockId',
			'title' => 'Cao tầng'
		)
	);
	$field = "{$clsProject->pkey},title,more_information";
	$list_group_projects = $clsProject->getAll("`is_trash`=0 and `is_menu`=1", $field);
	if(!empty($list_group_projects)){
		foreach($list_group_projects as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_admin_id = $core->get_field($more_information, 'list_admin_id', []);
			
			$list_in_groups = array();
			foreach($list_groups as $okey => $oval){
				$list_admins = isset($list_admin_id[$okey]) ? $list_admin_id[$okey] : array();
				if(in_array($profile_id, $list_admins)){
					$field = $oval['field'];
					$list_in_groups[] = array(
						'type_id' => $okey,
						'title' => $oval['title'],
						'spreadsheetId' => $more_information[$field]
					);
				}
			}
			$list_group_projects[$key]['list_groups'] = $list_in_groups;
		}
	}
	// $clsISO->print_pre($list_group_projects); die();
	$smarty->assign('list_group_projects', $list_group_projects);
	// Return
	$html = $core->build('stock'.DS.'_ajax.open_import.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
}
function stock_start_import(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	#
	$uid = $clsISO->getUniqid();
	$stock_type = (int) Input::post('stock_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	
	$lstBlockId = $clsProperty->getAll("`for_id`='{$project_id}' AND `property_type`='_BLOCK'",$clsProperty->pkey.",title");
	$lstBlock = array();
	$more_information_user = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	foreach ($lstBlockId as $key => $val) {
		if($clsISO->checkItemInArray($val[$clsProperty->pkey],$block_permiss)) {
			$lstBlock[$val[$clsProperty->pkey]] = $val["title"];
		}
	}
	
	$spreadsheetId = Input::post('spreadsheetId');
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');				
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
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
	$range = 'BH'; // here we use the name of the Sheet to get all the rows
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	// $clsISO->print_pre($tblData); die()
	$total_records = 0;
	$html = '<div class="modal-dialog modal-fullscreen">
	<form method="POST" class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>Import bảng hàng '.$clsProperty->getTitle($stock_type).' '.$clsProject->getCode($project_id).'</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
		<div class="d-flex flex-wrap align-items-center mb-3 p-3" style="border: 2px dashed #ff1c00">
			<label class="text-muted text-nowrap mr-2">Phân khu:</label>
			<div class="d-flex flex-wrap align-items-start">';
			if(!empty($lstBlock)) {
				foreach ($lstBlock as $block_id => $block_name) {
					$html .= '<div class="radius-half p-2 px-3 mr-2">
								<div class="checkbox">
									<input class="form-check-input me-2" type="checkbox" id="block_'.$block_id.'_'.$uid.'" name="block_id[]" value="'.$block_id.'">
									<label for="block_'.$block_id.'_'.$uid.'">'.$block_name.'</label>
								</div>
							</div>';
				}				
			}else{
				$html .= '<span class="text-main fw-bold">Bạn chưa được cấp quyền cập nhật bảng hàng</span>';
			}
	$html .='</div>
		</div>
		<div class="alert alert-warning mb-2"> Dòng màu vàng(nếu có) là mã căn không tồn tại.</div>';
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
		$more_information = $clsProject->getOneField("more_information", $project_id);
		$more_information = $clsISO->to_array_json($more_information);
		$columns = isset($more_information['columns'][$stock_type]) ? $more_information['columns'][$stock_type] : [];
		$html.= '<div class="text-nowrap overflow-auto mb-0" style="height:calc(100vh - 210px)">
			<table cellspacing="0" cellpadding="0" class="table no-width table-bordered table-striped">
				<thead><tr>';
				$highestColumnIndex = 15;
				for($col=0; $col<$highestColumnIndex; $col++){
					$field = isset($columns[$col]) ? $columns[$col] : "";
					$html.= '<th class="bg-white" style="min-width:125px" width="'.(100/$highestColumnIndex).'%">
						<select name="columns['.$col.']" class="form-control form-select stock_import_field">
							<option value="">Lựa chọn</option>
							'.$clsStock->getHtmlColumnField($col, $stock_type, 0, $field).'
						</select>
					</th>';
				}
				$html.= '</tr>
				</thead>';
				if(!empty($tblData)){
					$total_records = count($tblData);
					$cachedName = sprintf('%s.json', $uid);
					$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
					$encoder = new Webmozart\Json\JsonEncoder();
					$encoder->encodeFile($tblData, $cachedFile); 
					for($i=1; $i<count($tblData); $i++){
						$oneStock = $clsStock->getAll("and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
						and `project_id`='{$project_id}' and `ms_code`='".trim($tblData[$i][0])."' ", $clsStock->pkey);
						$html.='<tr'.(!empty($oneStock)?'':' class="tr_selected"').'>';
							for($col=0; $col<$highestColumnIndex; $col++){
								$html.= '<td class="text-left">'.$tblData[$i][$col].'</td>';
							}
						$html.= '</tr>';
					}
				} else {
					$html.='<tr>
						<td colspan="20">
							<div class="p-2 text-center">
								<img src="'.DOMAIN_URL.'/application/themes/images/table-no-data.png" />
								<p>Chưa có dữ liệu</p>
							</div>
						</td>
					</tr>';
				}
			$html.= '</table>
		</div>';
	} else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
		$highestColumnIndex = 22;
		$html.= '<div class="text-nowrap">
			<table class="table no-width table-bordered table-fixed table-striped mb-0">
				<thead><tr>
				<th width="3%" class="text-center">No.</th>';
				for($col=0; $col<$highestColumnIndex; $col++){
					$html.= '<th style="min-width:125px" width="'.(100/$highestColumnIndex).'%">
						<select name="columns['.$col.']" class="form-control form-select stock_import_field">
							<option value="">Lựa chọn</option>
							'.$clsStock->renderOptionColumnField($col, $stock_type, "", $project_id).'
						</select>
					</th>';
				}
			$html .= '</tr></thead>';
			if(!empty($tblData)){ $ii = 0; // Init
				$total_records = count($tblData);
				$cachedName = sprintf('%s.json', $uid);
				$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
				$encoder = new Webmozart\Json\JsonEncoder();
				$encoder->encodeFile($tblData, $cachedFile); 
				foreach($tblData as $key => $val){
					$html.= '<tr>
						<td class="text-center">'.($ii+1).'</td>';
						for($col=0; $col<$highestColumnIndex; $col++){
							$html.= '<td class="text-left text-nowrap">'.$val[$col].'</td>';
						}
					$html.= '</tr>';
					++$ii;
				}
			} 
		$html .= '</table>
		</div>';
	} else if($stock_type == _STOCK_TYPE_LEASING) {
		$highestColumnIndex = 22;
		$html.= '<div class="text-nowrap">
		<table class="table no-width table-bordered table-fixed table-striped">
			<thead><tr>
			<th width="3%" class="text-center">No.</th>';
			for($col=0; $col<$highestColumnIndex; $col++){
				$html .= '<th style="min-width:125px" width="'.(100/$highestColumnIndex).'%">
					<select name="columns['.$col.']" class="form-control form-select stock_import_field" >
						<option value="">Lựa chọn</option>
						'.$clsStock->renderOptionColumnField($col, $stock_type, "", $project_id).'
					</select>
				</th>';
			}
			$html .= '</tr></thead>';
			if(!empty($tblData)){ $ii = 0; // Init
				$total_records = count($tblData);
				$cachedName = sprintf('%s.json', $uid);
				$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
				$encoder = new Webmozart\Json\JsonEncoder();
				$encoder->encodeFile($tblData, $cachedFile); 
				foreach($tblData as $key => $val){
					$html.= '<tr>
						<td class="text-center">'.($ii+1).'</td>';
						for($col=0; $col<$highestColumnIndex; $col++){
							$html.= '<td class="text-left text-nowrap">'.$val[$col].'</td>';
						}
					$html.= '</tr>';
					++$ii;
				}
			} 
		$html .= '</table>
		</div>';
	}
	$html.= '</div>
		<div class="modal-footer align-items-center justify-content-between">
			<div class="d-flex align-items-center">
				<label class="switch mr-2">
					<input type="checkbox" name="opt_ignore_empty" value="1" checked />
					<span class="slider round"></span>
				</label>
				<span>Bỏ qua giá trị trống</span>
			</div>
			<div class="p__right">
				<button type="button"'.($total_records==0?' disabled':'').' class="btn btn-primary" stock_type="'.$stock_type.'" project_id="'.$project_id.'" uid="'.$uid.'" onClick="$Core.global.stock.do_import(this, event)">Cập nhật bảng hàng</button>
			</div>
		</div>
	</form></div>
	<style>
		.modal-body-scrollable {
			overflow-y: auto;
			max-height: calc(100vh - 120px);
		}
	</style>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function stock_do_import(){
//	 ini_set('display_errors',1);
//	 error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$profile_id,$oneProfile
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsStockLog = new StockLog();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockCrawl = new StockCrawl();
	/** Params */
	$uid = Input::post('uid');
	$columns = Input::post('columns', array());
	$stock_type = (int) Input::post('stock_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	$block_ids = Input::post('block_id', array());
	$opt_ignore_empty = (int) Input::post('opt_ignore_empty', 1);
	
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == $stock_type) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$tblData = array();
	$cachedName = sprintf('%s.json', $uid);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
		@unlink($cachedFile);			
	}
	$msg = "_error"; 
	$total_updated = 0;	
	
	#chua chon phan khu cap nhat
	if(empty($block_ids)){
		echo json_encode(array(
			'msg' => $msg,
			'total_updated' => $total_updated
		)); die();
	}
	$stock_not_upd = $arr_data = $arr_agency = $arr_block = $arr_total_stock_sold = $arr_total_stock_upd = $arr_total_stock_new = $lst_stock_id = array();
	if(!empty($tblData)){
		$error_field = 0;
		if(!empty($columns)){
			$arr_fields = array();
			foreach($columns as $key => $p_field){
				if(!empty($p_field)){
					if(!in_array($p_field, $arr_fields)){
						$arr_fields[] = $p_field;
					} else {
						$error_field += 1;
					}
				} else {
					unset($columns[$key]);
				}
			}
		}
		$total_record = @count($tblData);
		if($error_field == 0){
			$msg = "_success";
			$more_information = $clsProject->getOneField('more_information', $project_id);
			$more_information = $clsISO->to_array_json($more_information);
			$more_information["columns"][$stock_type] = $columns;
			$clsProject->updateOne($project_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
				#- Definne Column.
				$block_index = _COLUMN_INDEX_DEF;
				$building_index = _COLUMN_INDEX_DEF;
				$floor_index = _COLUMN_INDEX_DEF;
				$code_index = _COLUMN_INDEX_DEF;
				$ms_code_index = _COLUMN_INDEX_DEF; 
				$type_index = _COLUMN_INDEX_DEF;
				$bedroom_index = _COLUMN_INDEX_DEF;
				$home_direction_index = _COLUMN_INDEX_DEF;
				$agency_index = _COLUMN_INDEX_DEF; 
				$view_index = _COLUMN_INDEX_DEF;
				$DT_TT_index = _COLUMN_INDEX_DEF;
				$DT_Tim_index = _COLUMN_INDEX_DEF;
				$total_price_index = _COLUMN_INDEX_DEF;
				$total_price_vat_index = _COLUMN_INDEX_DEF;
				$total_price_early_index = _COLUMN_INDEX_DEF;
				$total_price_bank_half_index = _COLUMN_INDEX_DEF;
				$total_price_progress_index = _COLUMN_INDEX_DEF;
				$total_price_bank_index = _COLUMN_INDEX_DEF;
				$price_sheet_title_index = _COLUMN_INDEX_DEF;
				$price_sheet_link_index = _COLUMN_INDEX_DEF;
				$sale_bonus_index = _COLUMN_INDEX_DEF;
				$date_deposit_sign_index = _COLUMN_INDEX_DEF;
				$status_index = _COLUMN_INDEX_DEF;
				$csbh_index = _COLUMN_INDEX_DEF;
				$reg_confirm_date_index = _COLUMN_INDEX_DEF;
				$maintenance_fee_index = _COLUMN_INDEX_DEF;
				foreach($columns as $key => $p_field){
					if($p_field=='block_id'){
						$block_index = $key;
					} else if($p_field=='building_id'){
						$building_index = $key;
					} else if($p_field=='floor'){
						$floor_index = $key;
					} else if($p_field=='code'){
						$code_index = $key;
					} else if($p_field=='ms_code'){
						$ms_code_index = $key;
					} else if($p_field=='type_id'){
						$type_index = $key;
					} else if($p_field=='bedroom_id'){
						$bedroom_index = $key;
					} else if($p_field=='home_direction_id'){
						$home_direction_index = $key;
					}  else if($p_field=='agency_id'){
						$agency_index = $key;
					} else if($p_field=='view_id'){
						$view_index = $key;
					} else if($p_field=='DT_TT'){
						$DT_TT_index = $key;
					} else if($p_field=='DT_Tim'){
						$DT_Tim_index = $key;
					} else if($p_field=='total_price'){
						$total_price_index = $key;
					} else if($p_field=='total_price_vat'){
						$total_price_vat_index = $key;
					} else if($p_field=='total_price_early'){
						$total_price_early_index = $key;
					} else if($p_field=='total_price_progress'){
						$total_price_progress_index = $key;
					} else if($p_field=='total_price_bank_half'){
						$total_price_bank_half_index = $key;
					} else if($p_field=='total_price_bank'){
						$total_price_bank_index = $key;
					} else if($p_field=='price_sheet_title'){
						$price_sheet_title_index = $key;
					} else if($p_field == 'price_sheet_link'){
						$price_sheet_link_index = $key;
					} else if($p_field=='sale_bonus'){
						$sale_bonus_index = $key;
					} else if($p_field == 'date_deposit_sign'){
						$date_deposit_sign_index = $key;
					} else if($p_field=='status_id'){
						$status_index = $key;
					} else if($p_field=='csbh'){
						$csbh_index = $key;
					} else if($p_field == "maintenance_fee") {
						$maintenance_fee_index = $key;
					} else if($p_field == 'reg_confirm_date'){
						$reg_confirm_date_index = $key;
					}
				}
				#- End
				$stock_status_id = _STOCK_STATUS_LOCK_ID;
				$list_stock_ids = $list_stock_agency_ids = $agency_ids_cached = array();
				for($i=1; $i<$total_record; $i++){
					$agency_name = trim($tblData[$i][$agency_index]);
					if(!empty($agency_name)){
						if(isset($agency_ids_cached[$agency_name]) && !empty($agency_ids_cached[$agency_name])){
							$agency_id = $agency_ids_cached[$agency_name];
						} else {
							$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_AGENCY' 
								and `slug`='".$core->replaceSpace($agency_name)."'", $clsProperty->pkey);
							$agency_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
							$agency_ids_cached[$agency_name] = $agency_id;
						}
						if(!in_array($agency_id, $list_stock_agency_ids)){
							$list_stock_agency_ids[] = $agency_id;
						}
					}
				}
				// Cập nhật các căn thành đã bán
				if(!empty($list_stock_agency_ids)){
					foreach($list_stock_agency_ids as $agency_id){
						// Start Logs
						$clsAdminLog = new AdminLog();
						$clsAdminLog->insertLog('update_stock', _BLOCK_TYPE_HIGHLEVEL_SALE, 
							$project_id, $agency_id, array(),'_front_end');
						// End Logs
					}
					$sql_LSB = "";
					if($profile_id == _PROFILE_MY_LINH_ADMIN_ID) {
						$sql_LSB = " AND `block_id`='"._PROJECT_BLOCK_LSB_ID."' ";
					}
					$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`,`block_id`,`agency_id`";
					$list_sold_stocks = $clsStock->getAll("`agency_id` in (".implode(',', $list_stock_agency_ids).") and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."') and `project_id`='{$project_id}' and `block_id` IN (".implode(',',$block_ids).") and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'".$sql_LSB, $field);
					// $clsISO->print_pre($list_sold_stocks); die();
					if(!empty($list_sold_stocks)){
						foreach($list_sold_stocks as $key => $val){
							$logs = array();
							$more_information = $val['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
							$more_information['user_id_update_sold'] = $profile_id;
							###
							$m_field = "{$clsStockMeta->pkey},`logs`";
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
							if(!empty($oneStockMeta)){
								$logs = $oneStockMeta['logs'];
								$logs = $clsISO->to_array_json($logs);
							} else {
								$clsStockMeta->insert(array(
									'stock_id' => $oneStock[$clsStock->pkey],
									'reg_date' => time(),
									'upd_date' => time()
								));
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
							}
							#- Add Logs
							$logs[$clsISO->getUniqid()] = array(
								'reg_date' => time(), 
								'user_id' => $core->_USER['user_id'],
								'from_id' => $val['status_id'],
								'to_id' => _STOCK_STATUS_SOLD_ID,
								'field' => 'status_id'
							);
							if($clsStock->updateOne($val[$clsStock->pkey], array(
								'ms_date' => time(),
								'status_id' => _STOCK_STATUS_SOLD_ID,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
							))) {
								if(!empty($oneStockMeta) && !empty($logs)){
									$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
										'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
										'upd_date' => time()
									));
								}
								if(!empty($arr_total_stock_sold[$val["agency_id"]][$val["block_id"]])) {
									$arr_total_stock_sold[$val["agency_id"]][$val["block_id"]] += 1;
								}else{
									$arr_total_stock_sold[$val["agency_id"]][$val["block_id"]] = 1;
								}
								if(!in_array($val["agency_id"],$arr_agency)) {
									$arr_agency[] = $val["agency_id"];
								}
								if(!in_array($val["block_id"],$arr_block)) {
									$arr_block[] = $val["block_id"];
								}
							}
						}
						unset($list_sold_stocks);
					}
				}
				// End cập nhật các căn thành đã bán
				$arr_tmp_stock = [];
				for($i=1; $i<$total_record; $i++){
					if((isset($tblData[$i][$agency_index]) && !empty($tblData[$i][$agency_index])) 
						&& (isset($tblData[$i][$ms_code_index]) && !empty($tblData[$i][$ms_code_index]))){
						// $clsISO->print_pre($tblData); die();
						$opt_price = 0;
						$ms_code = trim($tblData[$i][$ms_code_index]);
						$agency_name = trim($tblData[$i][$agency_index]);
						if((isset($tblData[$i][$total_price_bank_index]) && !empty($tblData[$i][$total_price_bank_index])) 
							|| (isset($tblData[$i][$total_price_index]) && !empty($tblData[$i][$total_price_index])) 
							|| (isset($tblData[$i][$total_price_vat_index]) && !empty($tblData[$i][$total_price_vat_index])) 
							|| (isset($tblData[$i][$total_price_early_index]) && !empty($tblData[$i][$total_price_early_index])) 
							|| (isset($tblData[$i][$total_price_progress_index]) && !empty($tblData[$i][$total_price_progress_index])) 
							|| (isset($tblData[$i][$total_price_bank_half_index]) && !empty($tblData[$i][$total_price_bank_half_index]))){
							$opt_price = 1;
							$total_price = $core->get_field($tblData[$i], $total_price_index, 0);
							$total_price_vat = $core->get_field($tblData[$i], $total_price_vat_index, 0);
							$total_price_early = $core->get_field($tblData[$i], $total_price_early_index, 0);
							$total_price_bank = $core->get_field($tblData[$i], $total_price_bank_index, 0);
							$total_price_progress = $core->get_field($tblData[$i], $total_price_progress_index, 0);
							$total_price_bank_half = $core->get_field($tblData[$i], $total_price_bank_half_index, 0);
							$total_price_vat = !empty($total_price_vat) ? $clsISO->processSmartNumber($total_price_vat) : 0;
							$total_price_early = !empty($total_price_early) ? $clsISO->processSmartNumber($total_price_early) : 0;
							$total_price_progress = !empty($total_price_progress) ? $clsISO->processSmartNumber($total_price_progress) : 0;
							$total_price_bank = !empty($total_price_bank) ? $clsISO->processSmartNumber($total_price_bank) : 0;
							$total_price_bank_half = !empty($total_price_bank_half) ? $clsISO->processSmartNumber($total_price_bank_half) : 0;
							
							if(!empty($total_price) && empty($total_price_vat)) {
								$total_price_vat = $total_price * _PERCENT_PRICE_VAT;
							}
						}
						$csbh = $core->get_field($tblData[$i], $csbh_index, "");
						$date_deposit_sign = $core->get_field($tblData[$i], $date_deposit_sign_index, "");
						$status_name = $core->get_field($tblData[$i], $status_index, "");
						$sale_bonus = $core->get_field($tblData[$i], $sale_bonus_index, "");
						$reg_confirm_date = $core->get_field($tblData[$i], $reg_confirm_date_index, "");
						###
						$agency_id = 0;
						if(!empty($agency_name)){
							$tmp = $clsProperty->getByCond("`is_trash`=0 and `is_locked`=0 and `property_type`='_AGENCY' 
								and `slug`='".$core->replaceSpace($agency_name)."'","{$clsProperty->pkey},more_information");
							if(!empty($tmp)){
								$agency_id = $tmp[$clsProperty->pkey];
								$ag_information = $tmp['more_information'];
								$ag_information = $clsISO->to_array_json($ag_information);
								if(isset($ag_information['stock_status_id']) && !empty($ag_information['stock_status_id'])){
									$stock_status_id = $ag_information['stock_status_id'];
								}
								if(!in_array($agency_id,$arr_agency)) {
									$arr_agency[] = $agency_id;
								}
							}
						}
						if($agency_id == 0) continue;
						if(!empty($status_name)){
							$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
							and `slug`='".$core->replaceSpace($status_name)."'", $clsProperty->pkey);
							if(!empty($tmp)) $stock_status_id = $tmp[$clsProperty->pkey];
						}
						$oneStock = $clsStock->getByCond("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `project_id`='{$project_id}' and `block_id` IN (".implode(',',$block_ids).")
						and `agency_id`='{$agency_id}' and `ms_code`='{$ms_code}' and `status_id`<>'"._STOCK_STATUS_NON_ID."'");
						if(!isset($arr_tmp_stock[$agency_id])) {
							$arr_tmp_stock[$agency_id] = [];
						}
						if(!empty($oneStock)){						
							/*giá min max*/
							if(!empty($arr_price_min_max[$project_id])) {
								$price_min_max = isset($arr_price_min_max[$project_id][$oneStock["block_id"]]) ? $arr_price_min_max[$project_id][$oneStock["block_id"]] : $arr_price_min_max[$project_id][0];
							}
							$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
							$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
							/*end giá min max*/
							$logs = array();
							$more_information = $oneStock['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							$price_sheets = $core->get_field($more_information, "price_sheets", []);
							##
							$m_field = "{$clsStockMeta->pkey},`logs`";
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
							if(!empty($oneStockMeta)){
								$logs = $oneStockMeta['logs'];
								$logs = $clsISO->to_array_json($logs);
							} else {
								$clsStockMeta->insert(array(
									'stock_id' => $oneStock[$clsStock->pkey],
									'reg_date' => time(),
									'upd_date' => time()
								));
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
							}
							$sheets = $update_field = array();
							$update_field['upd_date'] = time();
							$update_field['agency_id'] = $agency_id;
							$update_field['status_id'] = $stock_status_id;
							#- PTG 1
							if(isset($tblData[$i][$price_sheet_link_index]) && !empty($tblData[$i][$price_sheet_link_index])){
								$price_sheet_title = "PTG TẠM TÍNH";
								if(isset($tblData[$i][$price_sheet_link_index]) && !empty($tblData[$i][$price_sheet_link_index])){
									$price_sheet_title = $tblData[$i][$price_sheet_title_index];
								}
								$sheets[$clsISO->getUniqid()] = array(
									'title' => $price_sheet_title,
									'image' => $tblData[$i][$price_sheet_link_index]
								);
							}
							if(!empty($sheets)){
								$more_information['hide_price_sheets'] = 0;
								$price_sheets = array();
								$price_sheet_id = $clsISO->getUniqid();
								$price_sheets[$price_sheet_id]['sheets'] = $sheets;
								$price_sheets[$price_sheet_id]['reg_date'] = time();
								$price_sheets[$price_sheet_id]['upd_date'] = time();
								$price_sheets[$price_sheet_id]['csbh'] = $csbh;
								$price_sheets[$price_sheet_id]['user_id'] = $profile_id;
								$price_sheets[$price_sheet_id]['user_update_id'] = $profile_id;
							}
							if($opt_price==1){
								$total_price_vat = $clsISO->convertPriceShortToFullUpdate($total_price_vat,$min,$max);
								$total_price_early = $clsISO->convertPriceShortToFullUpdate($total_price_early,$min,$max);
								$total_price_progress = $clsISO->convertPriceShortToFullUpdate($total_price_progress,$min,$max);
								$total_price_bank = $clsISO->convertPriceShortToFullUpdate($total_price_bank,$min,$max);
								$total_price_bank_half = $clsISO->convertPriceShortToFullUpdate($total_price_bank_half,$min,$max);
								if(!empty($total_price_vat) && $total_price_vat > 1000000 
									&& $more_information['total_price_vat'] != $total_price_vat){
									$logs[$clsISO->getUniqid()] = array(
										'reg_date' => time(), 
										'user_id' => $core->_USER['user_id'],
										'from_value' => $more_information['total_price_vat'],
										'to_value' => $total_price_vat,
										'field' => 'total_price_vat'
									);
									$more_information['total_price_vat']= $total_price_vat;
									$update_field['total_price_vat'] = $clsISO->convertToNumber($total_price_vat);
								}
								if(!empty($total_price_early) && $total_price_early > 1000000){
									if($more_information['total_price_early'] != $total_price_early){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(), 
											'user_id' => $core->_USER['user_id'],
											'from_value' => $more_information['total_price_early'],
											'to_value' => $total_price_early,
											'field' => 'total_price_early'
										);
									}
									$more_information['total_price_early']= $total_price_early;
									$update_field['total_price_vat'] = $clsISO->convertToNumber($total_price_early);
								} else {
									if($opt_ignore_empty==0){
										$more_information['total_price_early']= 0;
									}
								}
								if(!empty($total_price_progress)  && $total_price_progress > 1000000){
									if($more_information['total_price_progress'] != $total_price_progress){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(), 
											'user_id' => $core->_USER['user_id'],
											'from_value' => $more_information['total_price_progress'],
											'to_value' => $total_price_progress,
											'field' => 'total_price_progress'
										);
									}
									$more_information['total_price_progress']= $total_price_progress;
								} else {
									if($opt_ignore_empty==0){
										$more_information['total_price_progress']= 0;
									}
								}
								if(!empty($total_price_bank) && $total_price_bank > 1000000){
									if($more_information['total_price_bank'] != $total_price_bank){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(), 
											'user_id' => $core->_USER['user_id'],
											'from_value' => $more_information['total_price_bank'],
											'to_value' => $total_price_bank,
											'field' => 'total_price_bank'
										);
									}
									$more_information['total_price_bank']= $total_price_bank;
								} else {
									if($opt_ignore_empty==0){
										$more_information['total_price_bank']= 0;
									}
								}
								if(!empty($total_price_bank_half) && $total_price_bank_half > 1000000){
									if($more_information['total_price_bank_half'] != $total_price_bank_half){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(), 
											'user_id' => $core->_USER['user_id'],
											'from_value' => $more_information['total_price_bank_half'],
											'to_value' => $total_price_bank_half,
											'field' => 'total_price_bank_half'
										);
									}
									$more_information['total_price_bank_half']= $total_price_bank_half;
								} else {
									if($opt_ignore_empty==0){
										$more_information['total_price_bank_half']= 0;
									}
								}
							}
							if($oneStock['status_id'] != $stock_status_id){
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $profile_id,
									'from_id' => $oneStock['status_id'],
									'to_id' => $stock_status_id,
									'field' => 'status_id',
									'from' => '_front_end'
								);
							}
							if(!empty($date_deposit_sign)){
								$more_information['date_deposit_sign']= $date_deposit_sign;
							}
							if(!empty($sale_bonus)){
								$more_information['sale_bonus']= $sale_bonus;
							} else {
								if($opt_ignore_empty == 0){
									$more_information['sale_bonus']= 0;
								}
							}
							$more_information['agency_id']= $agency_id;
							$more_information['status_id']= $stock_status_id;
							$more_information['price_sheets']= $price_sheets;
							$more_information['reg_confirm_date']= $reg_confirm_date;
							
							if($clsStock->updateOne($oneStock[$clsStock->pkey], array_merge($update_field, array(
								//'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
							)))){
								$total_updated += 1;
								if(!empty($oneStockMeta) && !empty($logs)){
									$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
										'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
										'upd_date' => time()
									));
								}
								if(!empty($arr_total_stock_upd[$oneStock["agency_id"]][$oneStock["block_id"]])) {
									$arr_total_stock_upd[$oneStock["agency_id"]][$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_upd[$oneStock["agency_id"]][$oneStock["block_id"]] = 1;
								}	
								if(!in_array($oneStock["block_id"],$arr_block)) {
									$arr_block[] = $oneStock["block_id"];
								}
							}
							$arr_data[$agency_id][$oneStock["block_id"]][] = [
								"ms_code"	=>	 $ms_code,
								"total_price_vat"=> $total_price_vat,
								"total_price_early"=> $total_price_early,
								"total_price_progress"=> $total_price_progress,
								"total_price_bank"=> $total_price_bank,
								"total_price_bank_half"=> $total_price_bank_half,
								"csbh"=> $csbh,
								"price_sheet_link"=> $price_sheet_link,
								"stock_id"=> $oneStock[$clsStock->pkey],
							];
							$arr_tmp_stock[$agency_id][$oneStock["block_id"]][] = $ms_code;
						} else {
							$oneStock = $clsStock->getByCond("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
							and `project_id`='{$project_id}' and `block_id` IN (".implode(',',$block_ids).") and `ms_code`='{$ms_code}' and `agency_id`<>'{$agency_id}'");
							if(!empty($oneStock)){
								/*giá min max*/
								if(!empty($arr_price_min_max[$project_id])) {
									$price_min_max = isset($arr_price_min_max[$project_id][$oneStock["block_id"]]) ? $arr_price_min_max[$project_id][$oneStock["block_id"]] : $arr_price_min_max[$project_id][0];
								}
								$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
								$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
								/*end giá min max*/
								$logs = array();
								$more_information = $oneStock['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$price_sheets = $core->get_field($more_information, "price_sheets", []);
								
								$m_field = "{$clsStockMeta->pkey},`logs`";
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
								if(!empty($oneStockMeta)){
									$logs = $oneStockMeta['logs'];
									$logs = $clsISO->to_array_json($logs);
								} else {
									$clsStockMeta->insert(array(
										'stock_id' => $oneStock[$clsStock->pkey],
										'reg_date' => time(),
										'upd_date' => time()
									));
									$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
								}
								$sheets = $update_field = array();
								$update_field['ms_date'] = 0;
								$update_field['upd_date'] = time();
								$update_field['agency_id'] = $agency_id;
								$update_field['status_id'] = $stock_status_id;
								#- PTG 1
								if(isset($tblData[$i][$price_sheet_link_index]) && !empty($tblData[$i][$price_sheet_link_index])){
									$price_sheet_title = "PTG TẠM TÍNH";
									if(isset($tblData[$i][$price_sheet_title_index]) && !empty($tblData[$i][$price_sheet_title_index])){
										$price_sheet_title = $tblData[$i][$price_sheet_title_index];
									}
									$sheets[$clsISO->getUniqid()] = array(
										'title' => $price_sheet_title,
										'image' => $tblData[$i][$price_sheet_link_index]
									);
								}
								if(!empty($sheets)){
									$more_information['hide_price_sheets'] = 0;
									$price_sheets = array();
									$price_sheet_id = $clsISO->getUniqid();
									$price_sheets[$price_sheet_id]['sheets'] = $sheets;
									$price_sheets[$price_sheet_id]['reg_date'] = time();
									$price_sheets[$price_sheet_id]['upd_date'] = time();
									$price_sheets[$price_sheet_id]['csbh'] = $csbh;
									$price_sheets[$price_sheet_id]['user_id'] = $profile_id;
									$price_sheets[$price_sheet_id]['user_update_id'] = $profile_id;
								}
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(),
									'user_id' => $core->_USER['user_id'],
									'from_id' => $oneStock['agency_id'],
									'to_id' => $agency_id,
									'field' => 'agency_id'
								);
								if($opt_price==1){
									$total_price_vat = $clsISO->convertPriceShortToFullUpdate($total_price_vat,$min,$max);
									$total_price_early = $clsISO->convertPriceShortToFullUpdate($total_price_early,$min,$max);
									$total_price_progress = $clsISO->convertPriceShortToFullUpdate($total_price_progress,$min,$max);
									$total_price_bank = $clsISO->convertPriceShortToFullUpdate($total_price_bank,$min,$max);
									$total_price_bank_half = $clsISO->convertPriceShortToFullUpdate($total_price_bank_half,$min,$max);
									if(!empty($total_price_vat) && $total_price_vat > 1000000 
										&& $more_information['total_price_vat'] != $total_price_vat){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(), 
											'user_id' => $core->_USER['user_id'],
											'from_value' => $more_information['total_price_vat'],
											'to_value' => $total_price_vat,
											'field' => 'total_price_vat'
										);
										$more_information['total_price_vat']= $total_price_vat;
										$update_field['total_price_vat'] = $clsISO->convertToNumber($total_price_vat);
									}
									if(!empty($total_price_early) && $total_price_early > 1000000){
										if($more_information['total_price_early'] != $total_price_early){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(), 
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information['total_price_early'],
												'to_value' => $total_price_early,
												'field' => 'total_price_early'
											);
										}
										$more_information['total_price_early']= $total_price_early;
										$update_field['total_price_vat'] = $clsISO->convertToNumber($total_price_early);
									} else {
										if($opt_ignore_empty==0){
											$more_information['total_price_early']= 0;
										}
									}
									if(!empty($total_price_progress) && $total_price_progress > 1000000){
										if($more_information['total_price_progress'] != $total_price_progress){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(), 
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information['total_price_progress'],
												'to_value' => $total_price_progress,
												'field' => 'total_price_progress'
											);
										}
										$more_information['total_price_progress']= $total_price_progress;
									} else {
										if($opt_ignore_empty==0){
											$more_information['total_price_progress']= 0;
										}
									}
									if(!empty($total_price_bank) && $total_price_bank > 1000000){
										if($more_information['total_price_bank'] != $total_price_bank){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(), 
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information['total_price_bank'],
												'to_value' => $total_price_bank,
												'field' => 'total_price_bank'
											);
										}
										$more_information['total_price_bank']= $total_price_bank;
									} else {
										if($opt_ignore_empty==0){
											$more_information['total_price_bank']= 0;
										}
									}
									if(!empty($total_price_bank_half) && $total_price_bank_half > 1000000){
										if($more_information['total_price_bank_half'] != $total_price_bank_half){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(), 
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information['total_price_bank_half'],
												'to_value' => $total_price_bank_half,
												'field' => 'total_price_bank_half'
											);
										}
										$more_information['total_price_bank_half']= $total_price_bank_half;
									} else {
										if($opt_ignore_empty==0){
											$more_information['total_price_bank_half']= 0;
										}
									}
								}
								#- Log trạng thái
								if($oneStock['status_id'] != $stock_status_id){
									$logs[$clsISO->getUniqid()] = array(
										'reg_date' => time(), 
										'user_id' => $core->_USER['user_id'],
										'from_id' => $oneStock['status_id'],
										'to_id' => $stock_status_id,
										'field' => 'status_id'
									);
								}
								if(!empty($date_deposit_sign)){
									$more_information['date_deposit_sign']= $date_deposit_sign;
								}
								if(!empty($sale_bonus)){
									$more_information['sale_bonus']= $sale_bonus;
								} 
								$more_information['agency_id']= $agency_id;
								$more_information['status_id']= $stock_status_id;
								$more_information['price_sheets']= $price_sheets;
								$more_information['reg_confirm_date']= $reg_confirm_date;
								if($clsStock->updateOne($oneStock[$clsStock->pkey], array_merge($update_field, array(
									// 'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								)))){
									$total_updated += 1;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));
									}
									if(!empty($arr_total_stock_new[$oneStock["agency_id"]][$oneStock["block_id"]])) {
										$arr_total_stock_new[$oneStock["agency_id"]][$oneStock["block_id"]] += 1;
									}else{
										$arr_total_stock_new[$oneStock["agency_id"]][$oneStock["block_id"]] = 1;
									}
									if(!in_array($oneStock["block_id"],$arr_block)) {
										$arr_block[] = $oneStock["block_id"];
									}
								}								
								$arr_data[$agency_id][$oneStock["block_id"]][] = [
									"ms_code"	=>	 $ms_code,
									"total_price_vat"=> $total_price_vat,
									"total_price_early"=> $total_price_early,
									"total_price_progress"=> $total_price_progress,
									"total_price_bank"=> $total_price_bank,
									"total_price_bank_half"=> $total_price_bank_half,
									"csbh"=> $csbh,
									"price_sheet_link"=> $price_sheet_link,
									"stock_id"=> $oneStock[$clsStock->pkey],
								];
								$arr_tmp_stock[$agency_id][$oneStock["block_id"]][] = $ms_code;
								$lst_stock_id[$agency_id][$oneStock["block_id"]] = $oneStock[$clsStock->pkey];
							}else{
								$stock_not_upd[$agency_id][] = $ms_code;
							}
						}
					}
				}
				#log new
				$clsLogCrawl = new LogCrawl();
				if(!empty($arr_agency)) {
					foreach ($arr_agency as $agency_id) {
						if(!empty($arr_block)) {
							foreach ($arr_block as $block_id) {
								$total_stock_sold = !empty($arr_total_stock_sold[$agency_id][$block_id]) ? $arr_total_stock_sold[$agency_id][$block_id] : 0;
								$total_stock_new = !empty($arr_total_stock_new[$agency_id][$block_id]) ? $arr_total_stock_new[$agency_id][$block_id] : 0;
								$total_updated_log = !empty($arr_total_stock_upd[$agency_id][$block_id]) ? $arr_total_stock_upd[$agency_id][$block_id] : 0;
								$arr_data_upd = array(
									"total_stock_sold"	=>	$total_stock_sold,
									"total_stock_new"	=>	$total_stock_new,
									"total_stock"	=>	$total_updated_log,
									"stock_not_upd"	=>	$stock_not_upd[$agency_id],
									"data_log"	=>	!empty($arr_data[$agency_id][$block_id]) ? $arr_data[$agency_id][$block_id] : array(),
									"title_log"	=>	'Tổng quỹ: '.$total_updated_log.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
									"type"	=>	0,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
									"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
								);
								$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
							}
						}										
						#luu bang tam
						foreach ($arr_tmp_stock[$agency_id] as $block_id => $ms_codes) {	
							$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);
							// Start Logs 
							$stock_ids = !empty($lst_stock_id[$agency_id][$block_id]) ? $lst_stock_id[$agency_id][$block_id] : array();
							$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $clsStockLog->pkey);
							if(!empty($tmp)){
								$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
									'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
								));
							} else {
								$clsStockLog->insert(array(
									'stock_type' => $stock_type,
									'agency_id' => $agency_id,
									'block_id' => $block_id,
									'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
									'reg_date' => time(),
									'user_id' => $profile_id
								));
							}
							/** End */
						}			
						#tong hop quy dai ly
//						$resStockCrawl = $clsStockCrawl->updateStockCrawl($agency_id);
					}
				}	
				// End Cao Tầng
			} else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
				$agency_index = _COLUMN_INDEX_DEF; 
				$stock_hold_index = _COLUMN_INDEX_DEF;
				$type_index = _COLUMN_INDEX_DEF;
				$ms_code_index = _COLUMN_INDEX_DEF;
				$home_direction_index = _COLUMN_INDEX_DEF;
				$DT_TT_index = _COLUMN_INDEX_DEF;
				$DT_Tim_index = _COLUMN_INDEX_DEF;
				$total_price_index = _COLUMN_INDEX_DEF;
				$total_price_vat_index = _COLUMN_INDEX_DEF;
				$total_price_early_index = _COLUMN_INDEX_DEF;
				$total_price_bank_half_index = _COLUMN_INDEX_DEF;
				$total_price_progress_index = _COLUMN_INDEX_DEF;
				$total_price_bank_12_index = _COLUMN_INDEX_DEF;
				$total_price_bank_18_index = _COLUMN_INDEX_DEF;
				$total_price_bank_index = _COLUMN_INDEX_DEF; // 24T
				$total_price_bank_30_index = _COLUMN_INDEX_DEF;
				$total_price_bank_36_index = _COLUMN_INDEX_DEF;
				$price_sheet_title_index = _COLUMN_INDEX_DEF;
				$price_sheet_link_index = _COLUMN_INDEX_DEF;
				$sale_bonus_index = _COLUMN_INDEX_DEF;
				$date_deposit_sign_index = _COLUMN_INDEX_DEF;
				$status_index = _COLUMN_INDEX_DEF;
				$csbh_index = _COLUMN_INDEX_DEF;
				$TCBG_index = _COLUMN_INDEX_DEF;
				$deposit_date_index = _COLUMN_INDEX_DEF;
				$cs_policy_ns_index = _COLUMN_INDEX_DEF;
				$price_temporary_ns_index = _COLUMN_INDEX_DEF;
				$contract_type_index = _COLUMN_INDEX_DEF;
				$contract_subject_index = _COLUMN_INDEX_DEF;
				$invest_fund_index = _COLUMN_INDEX_DEF;
				$sale_status_index = _COLUMN_INDEX_DEF;
				$agent_lock_index = _COLUMN_INDEX_DEF;
				$deposit_agent_index = _COLUMN_INDEX_DEF;
				$bank_second_index = _COLUMN_INDEX_DEF;
				$bank_index = _COLUMN_INDEX_DEF;
				$notes_index = _COLUMN_INDEX_DEF;
				$maintenance_fee_index = _COLUMN_INDEX_DEF;
				foreach($columns as $key => $p_field){
					if($p_field=='agency_id'){
						$agency_index = $key;
					} else if($p_field == 'stock_hold_id'){
						$stock_hold_index = $key;
					} else if($p_field=='type_id'){
						$type_index = $key;
					} else if($p_field=='ms_code'){
						$ms_code_index = $key;
					} else if($p_field=='home_direction_id'){
						$home_direction_index = $key;
					} else if($p_field=='DT_TT'){
						$DT_TT_index = $key;
					} else if($p_field=='DT_Tim'){
						$DT_Tim_index = $key;
					} else if($p_field=='total_price'){
						$total_price_index = $key;
					} else if($p_field=='total_price_vat'){
						$total_price_vat_index = $key;
					} else if($p_field=='total_price_early'){
						$total_price_early_index = $key;
					} else if($p_field=='total_price_progress'){
						$total_price_progress_index = $key;
					} else if($p_field=='total_price_bank_12'){
						$total_price_bank_12_index = $key;
					} else if($p_field=='total_price_bank_18'){
						$total_price_bank_18_index = $key;
					} else if($p_field=='total_price_bank_30'){
						$total_price_bank_30_index = $key;
					} else if($p_field=='total_price_bank_36'){
						$total_price_bank_36_index = $key;
					} else if($p_field=='total_price_bank_half'){
						$total_price_bank_half_index = $key;
					} else if($p_field=='total_price_bank'){
						$total_price_bank_index = $key;
					} else if($p_field=='block_id'){
						$block_index = $key;
					} else if($p_field=='building_id'){
						$building_index = $key;
					} else if($p_field=='sale_bonus'){
						$sale_bonus_index = $key;
					} else if($p_field=='status_id'){
						$status_index = $key;
					} else if($p_field=='TCBG'){
						$TCBG_index = $key;
					} else if($p_field=='csbh'){
						$csbh_index = $key;
					} else if($p_field=='deposit_date'){
						$deposit_date_index = $key;
					} else if($p_field=='cs_policy_ns'){
						$cs_policy_ns_index = $key;
					} else if($p_field=='price_temporary_ns'){
						$price_temporary_ns_index = $key;
					} else if($p_field=='contract_subject_id'){
						$contract_subject_index = $key;
					} else if($p_field=='invest_fund_id'){
						$invest_fund_index = $key;
					} else if($p_field=='sale_status_id'){
						$sale_status_index = $key;
					} else if($p_field=='agent_lock_id'){
						$agent_lock_index = $key;
					} else if($p_field=='deposit_agent_id'){
						$deposit_agent_index = $key;
					} else if($p_field=='bank_id'){
						$bank_index = $key;
					} else if($p_field=='notes'){
						$notes_index = $key;
					} else if($p_field == "maintenance_fee") {
						$maintenance_fee_index = $key;
					} 
				}
				$list_agency_updated_ids = $list_code_agency_ids = $agency_ids_cached = $list_404_stocks = $arr_tmp_stock = array();
				for($i=1; $i<$total_record; $i++){
					$agency_name = trim($tblData[$i][$agency_index]);
					if(!empty($agency_name)){
						if(isset($agency_ids_cached[$agency_name]) && !empty($agency_ids_cached[$agency_name])){
							$agency_id = $agency_ids_cached[$agency_name];
						} else {
							$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_AGENCY' 
								and `slug`='".$core->replaceSpace($agency_name)."'", $clsProperty->pkey);
							$agency_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
							$agency_ids_cached[$agency_name] = $agency_id;
						}
						if(!in_array($agency_id, $list_agency_updated_ids)){
							$list_agency_updated_ids[] = $agency_id;
						}
						if(!in_array($agency_id,$arr_agency)) {
							$arr_agency[] = $agency_id;
						}
						if(!isset($list_code_agency_ids[$agency_id])) {
							$list_code_agency_ids[$agency_id] = [];
						}
						if(!empty($tblData[$i][$ms_code_index])) {
							$list_code_agency_ids[$agency_id][] = trim($tblData[$i][$ms_code_index]);		
						}
						
						if(!isset($arr_tmp_stock[$agency_id])) {
							$arr_tmp_stock[$_agency_id] = [];
						}
					}
				}
				#so can ban dai ly
				if(!empty($list_code_agency_ids)) {
					foreach ($list_code_agency_ids as $agencyId => $lstCode) {
						$condSold = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND `agency_id`='{$agencyId}' 
						AND `project_id`='{$project_id}' AND `block_id` IN (".implode(',',$block_ids).") AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'";
						if(!empty($lstCode)) {
							$condSold .= " AND `ms_code` NOT IN ('".implode("','",$lstCode)."')";
						}
						$total_sold = $clsStock->countItem($condSold);							
						$arr_total_stock_sold[$agencyId][$project_id] = $total_sold;
					}
				}
				// Update về đã bán
				$field = "{$clsStock->pkey},more_information,agency_id,project_id";
				$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND `agency_id` IN (".implode(',',$list_agency_updated_ids).")
				and `project_id`='{$project_id}' AND `block_id` IN (".implode(',',$block_ids).") and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`>0", $field);
				if(!empty($list_stocks)){
					foreach($list_stocks as $key => $val){
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
						if($clsStock->updateOne($val[$clsStock->pkey], array(
							'status_id' => _STOCK_STATUS_SOLD_ID,
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
						))){							
							if(!in_array($val["agency_id"],$arr_agency)) {
								$arr_agency[] = $val["agency_id"];
							}	
						}
					}
				}
				for($i=1; $i<=$total_record; $i++){
					$ms_code = $tblData[$i][$ms_code_index];
					$DT_TT = $tblData[$i][$DT_TT_index];
					$DT_TT = str_replace(',', '.', $DT_TT);
					$DT_TT = $clsISO->toNumber(trim($DT_TT));
					$total_price_vat = $tblData[$i][$total_price_vat_index];
					$total_price_early = $tblData[$i][$total_price_early_index];
					$total_price_progress = $tblData[$i][$total_price_progress_index];
					if(empty($ms_code)) continue;
					$ms_code = preg_replace('/\s+/', '', $ms_code);
					$field = "{$clsStock->pkey},`status_id`,`DT_TT`,`more_information`,`agency_id`,`block_id`";
					$oneStock = $clsStock->getByCond("`is_trash`=0 and stock_type='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
					and `project_id`='{$project_id}' AND `block_id` IN (".implode(',',$block_ids).") and TRIM(`ms_code`)='{$ms_code}'", $field);
					if(!empty($oneStock)){ // Cập nhật
						/*giá min max*/
						if(!empty($arr_price_min_max[$project_id])) {
							$price_min_max = isset($arr_price_min_max[$project_id][$oneStock["block_id"]]) ? $arr_price_min_max[$project_id][$oneStock["block_id"]] : $arr_price_min_max[$project_id][0];
						}
						$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
						$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
						/*end giá min max*/
						$logs = array();
						$uid = $clsISO->getUniqid();
						$more_information = $oneStock['more_information'];
						$upd_field = $more_information = array();
						$status_id = $oneStock['status_id'];
						$stock_status_id = _STOCK_STATUS_LOCK_ID;
						$more_information = $clsISO->to_array_json($more_information);
						
						$m_field = "{$clsStockMeta->pkey},`logs`";
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
						if(!empty($oneStockMeta)){
							$logs = $oneStockMeta['logs'];
							$logs = $clsISO->to_array_json($logs);
						} else {
							$clsStockMeta->insert(array(
								'stock_id' => $oneStock[$clsStock->pkey],
								'reg_date' => time(),
								'upd_date' => time()
							));
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
						}
						$agency_id_new = 0;
						foreach($columns as $key => $p_field){
							if(!empty($p_field) && !empty($tblData[$i][$key])){
								if($p_field=='type_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`property_type`='_TYPE_VILLA' and (`property_code`='".$tblData[$i][$key]."' or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='agency_id'){
									$field = "{$clsProperty->pkey},more_information";
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`property_code`='".$tblData[$i][$key]."' 
										or `slug` like '%".$core->replaceSpace($tblData[$i][$key])."%' 
										or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
									)", $field);
									if(!empty($tmp)){
										$tblData[$i][$key] = $tmp[$clsProperty->pkey];
										$agency_id_new = $tmp[$clsProperty->pkey];
										$agency_information = $tmp['more_information'];
										$agency_information = $clsISO->to_array_json($agency_information);
										if(isset($agency_information['stock_status_id']) && !empty($agency_information['stock_status_id'])){
											$stock_status_id = $agency_information['stock_status_id'];
										}
									} else {
										$tblData[$i][$key] = 0;
									}
								} else if($p_field=='status_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='stock_hold_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STOCK_HOLD' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='contract_type_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_TYPE' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									if(!empty($tmp)){
										$contract_type_id = $tmp[$clsProperty->pkey];
									} else {
										$contract_type_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $contract_type_id,
											'property_type' => '_CONTRACT_TYPE',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $contract_type_id;
								} else if($p_field=='contract_subject_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_SUBJECT' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									if(!empty($tmp)){
										$contract_subject_id = $tmp[$clsProperty->pkey];
									} else {
										$contract_subject_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $contract_subject_id,
											'property_type' => '_CONTRACT_SUBJECT',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $contract_subject_id;
								} else if($p_field=='invest_fund_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_INVEST_FUND' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									if(!empty($tmp)){
										$invest_fund_id = $tmp[$clsProperty->pkey];
									} else {
										$invest_fund_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $invest_fund_id,
											'property_type' => '_INVEST_FUND',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $invest_fund_id;
								} else if($p_field=='sale_status_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_SALE_STATUS' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									if(!empty($tmp)){
										$sale_status_id = $tmp[$clsProperty->pkey];
									} else {
										$sale_status_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $sale_status_id,
											'property_type' => '_SALE_STATUS',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $invest_fund_id;
								} else if(in_array($p_field, array('agent_lock_id','deposit_agent_id'))){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $field);
									if(!empty($tmp)){
										$agency_id = $tmp[$clsProperty->pkey];
									} else {
										$agency_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $agency_id,
											'property_type' => '_AGENCY',
											'title' => $tblData[$i][$key],
											'title_vn' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'slug_vn' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'is_locked' => 1,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $agency_id;
									if(in_array($agency_id, $list_agency_updated_ids)){
										$list_agency_updated_ids[] = $agency_id;
									}
								} else if(in_array($p_field, array('bank_second_id','bank_id'))){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BANK' 
										and slug='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									if(!empty($tmp)){
										$bank_id = $tmp[$clsProperty->pkey];
									} else {
										$bank_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $bank_id,
											'property_type' => '_BANK',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $bank_id;
								} else if($p_field=='home_direction_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								}
								if(!in_array($p_field, array('DT_Tim','DT_TT','total_price','total_price_vat','csbh','TCBG'
									,'contract_subject_id','invest_fund_id','bank_second_id','bank_id','sale_status_id'
									,'agent_lock_id','deposit_agent_id','total_price_early','total_price_progress'
									,'total_price_bank','total_price_bank_30','total_price_bank_36','total_price_bank_18'
									,'total_price_bank_12','cs_policy_ns','price_temporary_ns','stock_hold_id'
									,'contract_type_id','deposit_date','notes'))){
									$upd_field[$p_field] = trim($tblData[$i][$key]);
									$more_information[$p_field] = trim($tblData[$i][$key]);
								} else {
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
									,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
									,'total_price_bank_12','total_price_bank_18'))){
										$more_information[$p_field] = $clsStock->getPriceOrigin($tblData[$i][$key],$min,$max);
									} else {
										$more_information[$p_field] = trim($tblData[$i][$key]);
									}
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
									,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
									,'total_price_bank_12','total_price_bank_18'))){
										if((!isset($more_information[$p_field]) || (!empty($more_information[$p_field]) 
											&& $more_information[$p_field] != $clsISO->convertToNumber($tblData[$i][$key])))){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information[$p_field],
												'to_value' => $clsStock->getPriceOrigin($tblData[$i][$key],$min,$max),
												'field' => $p_field
											);
										}
									}
								}	
							} else if($opt_ignore_empty == 0){
								if($p_field == 'contract_type_id'){
									$more_information[$p_field] = 0;
								} else if($p_field=='TCBG' || $p_field=='notes' || $p_field=='csbh'){
									$more_information[$p_field] = "";
								} else if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
								,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
								,'total_price_bank_12','total_price_bank_18','TCBG','notes','csbh','contract_type_id'
								,'bank_id','price_temporary_ns'))){
									$more_information[$p_field] = 0;
								} else {
									$more_information[$p_field] = "";
								}
							}
						}
						// End For
						if(!empty($DT_TT)) $upd_field['DT_TT'] = $clsISO->toNumber($DT_TT);
						if(!empty($total_price_vat)){
							$upd_field['total_price_vat'] = $clsStock->getPriceOrigin($total_price_vat,$min,$max);
						} else {
							if(!empty($total_price_early)){
								$upd_field['total_price_vat'] = $clsStock->getPriceOrigin($total_price_early,$min,$max);
							}	
						}
						$more_information['status_id'] = $stock_status_id;
						$upd_field['status_id'] = $stock_status_id;
						$upd_field['upd_date'] = time();
						// $upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
						$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						// $clsISO->print_pre($upd_field); die();
						if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
							$total_updated += 1;
							if(!empty($oneStockMeta)){
								$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
									'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'upd_date' => time()
								));
							}
							if($oneStock["agency_id"] == $agency_id_new) {
								if(!empty($arr_total_stock_upd[$agency_id_new][$project_id])) {
									$arr_total_stock_upd[$agency_id_new][$project_id] += 1;
								}else{
									$arr_total_stock_upd[$agency_id_new][$project_id] = 1;
								}
							}else{
								if(!empty($arr_total_stock_new[$agency_id_new][$project_id])) {
									$arr_total_stock_new[$agency_id_new][$project_id] += 1;
								}else{
									$arr_total_stock_new[$agency_id_new][$project_id] = 1;
								}
							}
							$arr_data[$agency_id_new][$project_id][] = [
								"ms_code"	=>	 $ms_code,
								"total_price_vat"=> $total_price_vat,
								"total_price_early"=> $total_price_early,
								"total_price_progress"=> $total_price_progress,
								"total_price_bank"=> $more_information["total_price_bank"],
								"total_price_bank_half"=> $more_information["total_price_bank_half"],
								"total_price_bank_12"=> $more_information["total_price_bank_12"],
								"total_price_bank_18"=> $more_information["total_price_bank_18"],
								"total_price_bank_30"=> $more_information["total_price_bank_30"],
								"total_price_bank_30"=> $more_information["total_price_bank_36"],
								"csbh"=> $more_information["csbh"],
								"price_temporary_ns"=> $more_information["price_temporary_ns"],
								"stock_id"=> $oneStock[$clsStock->pkey],
							];
							$arr_tmp_stock[$agency_id_new][$project_id][] = $ms_code;
							
							$lst_stock_id[$agency_id_new][$project_id][] = $oneStock[$clsStock->pkey];
						}
					} else {
						$list_404_stocks[] = $ms_code;
					}
				}
				// Start Logs
				if(!empty($list_agency_updated_ids)){
					foreach($list_agency_updated_ids as $agency_id){
						$clsAdminLog = new AdminLog();
						$clsAdminLog->insertLog('update_stock', _BLOCK_TYPE_LOWFLOOR_SALE, 
							$project_id, $agency_id, array(), '_front_end');
					}
				}
				// End Logs
				if(!empty($list_404_stocks)){
					$clsAdminLog = new AdminLog();
					$clsAdminLog->insert(array(
						'date' => time(),
						'action' => 'update_stock_import',
						'user_id' => $core->_USER['user_id'],
						'target_id' => $project_id,
						'description' => json_encode($list_404_stocks, JSON_UNESCAPED_UNICODE)
					));
				}
				#log new
				$clsLogCrawl = new LogCrawl();
				if(!empty($arr_agency)) {
					foreach ($arr_agency as $agency_id) {
						$total_stock_sold = !empty($arr_total_stock_sold[$agency_id][$project_id]) ? $arr_total_stock_sold[$agency_id][$project_id] : 0;
						$total_stock_new = !empty($arr_total_stock_new[$agency_id][$project_id]) ? $arr_total_stock_new[$agency_id][$project_id] : 0;
						$totalUpdated = !empty($arr_total_stock_upd[$agency_id][$project_id]) ? $arr_total_stock_upd[$agency_id][$project_id] : 0;
						$arr_data_upd = array(
							"total_stock_sold"	=>	$total_stock_sold,
							"total_stock_new"	=>	$total_stock_new,
							"total_stock"	=>	$totalUpdated,
							"stock_not_upd"	=>	$stock_not_upd[$agency_id],
							"data_log"	=>	!empty($arr_data[$agency_id][$project_id]) ? $arr_data[$agency_id][$project_id] : array(),
							"title_log"	=>	'Tổng quỹ: '.$totalUpdated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
							"type"	=>	0,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
							"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
						);
						$clsLogCrawl->log($agency_id,$project_id, $arr_data_upd, $stock_type);
																
						#luu bang tam
						foreach ($arr_tmp_stock[$agency_id] as $target_id => $ms_codes) {	
							$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$target_id,$ms_codes);							
							// Start Logs 
							$stock_ids = !empty($lst_stock_id[$agency_id][$target_id]) ? $lst_stock_id[$agency_id][$target_id] : array();
							$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `project_id`='{$target_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $clsStockLog->pkey);
							if(!empty($tmp)){
								$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
									'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
								));
							} else {
								$clsStockLog->insert(array(
									'stock_type' => $stock_type,
									'agency_id' => $agency_id,
									'project_id' => $target_id,
									'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
									'reg_date' => time(),
									'user_id' => $profile_id
								));
							}
							/** End */
						}			
						#tong hop quy dai ly
//						$resStockCrawl = $clsStockCrawl->updateStockCrawl($agency_id);
					}
				}
				// End Thấp Tầng
			} else if($stock_type == _STOCK_TYPE_LEASING){
				$list_404_stocks = array();
				for($i=1; $i<=$total_record; $i++){
					$ms_code = "";
					foreach($columns as $key => $p_field){
						if($p_field=='ms_code'){
							$ms_code = $tblData[$i][$key];
							break;
						}
					}
					if(empty($ms_code)) continue;
					$ms_code = preg_replace('/\s+/', '', $ms_code);
					$field = "{$clsStock->pkey},`status_id`,`more_information`";
					$oneStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `block_id` IN (".implode(',',$block_ids).") and `ms_code`='{$ms_code}'", $field);
					if(!empty($oneStock)){ // Cập nhật
						$uid = $clsISO->getUniqid();
						$upd_field = $more_information = array();
						$status_leasing_id = _STOCK_STATUS_LOCK_ID;
						$more_information = $clsISO->to_array_json($oneStock['more_information']);
						foreach($columns as $key => $p_field){
							if(!empty($p_field) && !empty($tblData[$i][$key])){
								if($p_field=='type_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("is_trash=0 and `property_type`='_TYPE_VILLA' and (
										`property_code`='".$tblData[$i][$key]."' 
										or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='agency_id'){
									$field = "{$clsProperty->pkey},more_information";
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`property_code`='".$tblData[$i][$key]."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."' 
										or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
									)", $field);
									if(!empty($tmp)){
										$agency_id = $tmp[$clsProperty->pkey];
									} else {
										$agency_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $agency_id,
											'property_type' => '_AGENCY',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'order_no' => $clsProperty->getMaxorderNo(),
											'reg_date' => time(),
											'upd_date' => time(),
											'is_locked' => 1
										));
									}
									$tblData[$i][$key] = $agency_id;
								} else if($p_field=='status_leasing_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									if(!empty($tmp)) $status_leasing_id = $tmp[$clsProperty->pkey];
								} else if($p_field=='stock_hold_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STOCK_HOLD' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='payment_progress_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`property_type`='_STATUS_PAYMENT_PROGRESS' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='completed_floor_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`property_type`='_COMPLETED_FLOOR' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='home_direction_id'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_DIRECTION' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='fee_included'){
									$field = "{$clsProperty->pkey}";
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_MANAGEMENT_FEE_INCLUDED' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $field);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								}
								if($p_field=='dg_price'){
									$more_information[sprintf('%s_origin', $p_field)] = $clsISO->processSmartNumber($tblData[$i][$key]);
								}
								$more_information[$p_field] = $tblData[$i][$key];
								if(!in_array($p_field, array('DT_Tim','total_price','csbh','TCBG','service_price'
									,'setup_price','construct_price','completed_floor_id','payment_progress_id'
									,'dg_price','notes','stock_hold_id','link_image', 'fee_included'))){
									if($p_field=='DT_TT' && !empty($tblData[$i][$key])){
										$upd_field[$p_field] = $clsISO->toNumber($tblData[$i][$key]);
									} else {
										$upd_field[$p_field] = $tblData[$i][$key];
									}
								}
							} else {
								if($p_field == 'fee_included') {
									$more_information[$p_field] = _STATUS_LEASING_KBP_ID;
								} else {
									$more_information[$p_field] = "";
								}
							}
						}
						$upd_field['upd_date'] = time();
						$upd_field['stock_type'] = $stock_type;
						$upd_field['status_id'] = $status_leasing_id;
						$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
							$total_updated += 1;
						}
					} else {
						$list_404_stocks[] = $ms_code;
					}
				}
				// End Cho Thuê
			}	
		} else {
			$msg = "error_field";
		}
	}
	if($msg == "_success") {					
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Stock","update");
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'total_updated' => $total_updated
	)); die();
}
function stock_open_stock_lock(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	##
	$stock_id = (int)Input::post("stock_id",0);
	$project_id = 0;
	$oneStock = $clsStock->getOne($stock_id);
	if(!empty($oneStock)) {
		$project_id = $oneStock["project_id"];
	}
	$field = "{$clsProject->pkey},`title`,`more_information`";
	$lstProjects = $clsProject->getAll("`is_menu`='1' order by `reg_date` ASC", $field);
	
	$lstTypeLock = [
		[
			"setting_id"	=>	1,
			"title"			=>	"Giờ",
			"is_range_time"	=>	0
		],
		[
			"setting_id"	=>	2,
			"title"			=>	"Khoảng thời gian",
			"is_range_time"	=>	1
		]
	];
	$smarty->assign('oneStock', $oneStock);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('stock_id', $stock_id);
	$smarty->assign('lstProjects', $lstProjects);
	$smarty->assign('lstTypeLock', $lstTypeLock);
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('clsProperty', $clsProperty);
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('stock'.DS.'_ajax.stock_lock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function stock_loadTime(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$date_type = (int)Input::post("date_type",1);
	##
	
	$html = "";
	if($date_type == 2) {
		$html = '<div class="form-group mb-2">
					<label class="form-label">Khoảng thời gian</label>
					<div class="input-group">
						<input type="datetime-local" class="form-control required" name="start_time">
						<input type="datetime-local" class="form-control required" name="end_time">
					</div>						
				</div>';
	}else {
		$html = '<div class="form-group mb-2">
					<label class="form-label">Kết thúc</label>
					<div class="w-px-150">
						<input type="time" class="form-control required" name="hour" value="">
					</div>
				</div>';
	}
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'html' => $html
	)); die();
}
function stock_search_stock(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	###
	$uid = Input::post('uid');
	$holderG = Input::post('holderG', 'stock');
	$stock_type = (int) Input::post('stock_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	$keysearch = Input::post('keysearch');
	###
	$html_stock = "";
	$cond = "`is_trash`=0 and `project_id`='{$project_id}' and `agency_id`='"._AGENCY_FH_ID."' and `status_id`='"._STOCK_STATUS_DQ_ID."' and `ms_code` like '%{$keysearch}%'";
	$field = "{$clsStock->pkey},`ms_code`"; 
	$list_stocks = $clsStock->getAll($cond." limit 0,20", $field);
	// $clsISO->print_pre($list_stocks); die();
	if(!empty($list_stocks)){
		$html_stock .= '<ul class="list-unstyled">';
		foreach($list_stocks as $key => $val){
			$html_stock .= '<li>
				<a href="javascript:void(0);" onClick="$Core.helper.stock_lock.select_stock(this, event)" 
					stock_id="'.$val[$clsStock->pkey].'" uid="'.$uid.'">'.$val['ms_code'].'</a>
			</li>';
		}
		$html_stock .= '</ul>';
	} else {
		$html_stock = '';
	}
	// Return
	echo json_encode(array(
		'html' => $html_stock
	)); die();
}
function stock_save_stock_lock(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockLock = new StockLock();
	###
	$stock_lock_id = (int) Input::post('stock_lock_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$stock_id = (int) Input::post('stock_id', 0);
	$stock_code = (int) Input::post('stock_code', 0);
	$type_id = (int) Input::post('type_id', 0);
	$date_type = (int) Input::post('date_type', 1);
	$hour = Input::post('hour', "");
	$start_time = Input::post('start_time', "");
	$end_time = Input::post('end_time', "");
	$notes = Input::post('notes', "");
//	echo $start_time;die;
	$start_date = time();
	$end_date = strtotime("+30 minutes");
	if(!empty($hour)) {
		$start_date = time();
		$end_date = date("d-m-Y ".$hour);
		$end_date = strtotime($end_date);
	}else{
		$start_date = !empty($start_time) ? strtotime($start_time) : 0;
		$end_date = !empty($end_time) ? strtotime($end_time) : 0;
	}
	###
	$res = ["result" => false];
	$oneStock = $clsStock->getOne($stock_id);
	$logs = array();
	if(!empty($oneStock)) {		
		$upd_data = array(
			'stock_id' => $stock_id,
			'type_id' => $type_id,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'user_id' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		);
		if($stock_lock_id > 0) {
			$oneStockLock = $clsStockLock->getOne($stock_lock_id);
			$more_information = $clsISO->to_array_json($oneStockLock["more_information"]);
			$more_information["notes"] = addslashes($notes);
			$more_information["date_type"] = $date_type;
			$logs = !empty($more_information["logs"]) ? $more_information["logs"] : array();						
			$logs[] = [
				"date_type"	=>	$date_type,
				"hour"	=>	$hour,
				"start_time"	=>	$start_time,
				"end_time"	=>	$end_time,
				"notes"	=>	addslashes($notes),
				"time"	=>	time(),
				"user_id"	=>	$profile_id,
			];			
			$more_information["logs"] = $logs;
			$upd_data["more_information"] = json_encode($more_information,JSON_UNESCAPED_UNICODE);
			if($clsStockLock->updateOne($stock_lock_id,$upd_data)) {
				$res = ["result" => true];
			}

		}else{			
			$logs[] = [
				"date_type"	=>	$date_type,
				"hour"	=>	$hour,
				"start_time"	=>	$start_time,
				"end_time"	=>	$end_time,
				"notes"	=>	addslashes($notes),
				"time"	=>	time(),
				"user_id"	=>	$profile_id,
			];
			$more_information = [
				"date_type"	=>	$date_type,
				"notes"	=>	addslashes($notes),
				"logs"	=>	$logs
			];
			$upd_data["more_information"] = json_encode($more_information,JSON_UNESCAPED_UNICODE);
			$stock_lock_id = $clsStockLock->getMaxId();
			$upd_data[$clsStockLock->pkey] = $stock_lock_id;
			if($clsStockLock->insert($upd_data)) {
				$res = ["result" => true];
			}
		}
		
	}
	
	// Return
	echo json_encode($res); die();
}
?>