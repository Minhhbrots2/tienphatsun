<?php
function getTMP($code){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsProperty = new Property();
	
	$parts = explode('-', $code);
	$range_code = $parts[0];
	$block_name = $range_name = '';
	if(preg_match('/HĐ/', $range_code, $matches)){
		$block_name = 'Hải Đăng';
		$range_name = 'Hải Đăng '.str_replace('HĐ', '', $range_code);
	} else if(preg_match('/VTĐ/', $range_code, $matches)){
		$block_name = 'Vịnh Thiên Đường';
		$range_name = 'Vịnh Thiên Đường '.str_replace('VTĐ', '', $range_code);
	} else if(preg_match('/TĐ/', $range_code, $matches)){
		$block_name = 'Thời đại';
		$range_name = 'Thời đại '.str_replace('TĐ', '', $range_code);
	} else if(preg_match('/PB/', $range_code, $matches)){
		$block_name = 'Phố Biển';
		$range_name = 'Phố Biển '.str_replace('PB', '', $range_code);
	} else if(preg_match('/VX/', $range_code, $matches)){
		$block_name = 'Vịnh Xanh';
		$range_name = 'Vịnh Xanh '.str_replace('VX', '', $range_code);
	} else if(preg_match('/ĐN/', $range_code, $matches)){
		$block_name = 'Đảo Ngọc';
		$range_name = 'Đảo Ngọc '.str_replace('ĐN', '', $range_code);
	} else if(preg_match('/PT/', $range_code, $matches)){
		$block_name = 'Phố Tây';
		$range_name = 'Phố Tây '.str_replace('PT', '', $range_code);
	} else if(preg_match('/AD/', $range_code, $matches)){
		$block_name = 'Ánh Dương';
		$range_name = 'Ánh Dương '.str_replace('AD', '', $range_code);
	} else if(preg_match('/VT/', $range_code, $matches)){
		$block_name = 'Vịnh Tây';
		$range_name = 'Vịnh Tây '.str_replace('VT', '', $range_code);
	}
	$range_id = $block_id = 0;
	if(!empty($block_name) && !empty($range_name) && !empty($range_code)){
		$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' and `slug`='".$core->replaceSpace($block_name)."'", $clsProperty->pkey);
		$block_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
		if($block_id > 0){
			$tmp = $clsProperty->getByCond("`property_type`='_RANGE' and `for_id`='{$block_id}' 
				and `property_code`='{$range_code}'", $clsProperty->pkey);
			$range_id = $tmp[$clsProperty->pkey];
		}
	}
	return array(
		'block_id' => $block_id,
		'range_id' => $range_id
	);
}
function default_import_json(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$data = file_get_contents('https://vhocp3-cms.ez-viz.net/js/data.js');
	$tblData = json_decode(html_entity_decode($data), true);
	// $clsISO->print_pre($tblData); die();
	$total_inserted = $total_updated = 0;
	if(!empty($tblData)){
		foreach($tblData as $key => $val){
			$project_id = 3;
			$code = $val['code'];
			$DT_TT = $val['area'];
			$DT_Tim = $val['total_area'];
			$floor = $val['floor'];
			$cat_name = $val['cat_name'];
			$direction = $val['direction'];
			###
			$more = array();
			//$tmp = getTMP($code);
			// $clsISO->print_pre($tmp); die();
			//$block_id = (int) $tmp['block_id'];
			//$building_id = (int) $tmp['range_id'];
			//if($block_id > 0) $more['block_id'] = $block_id;
			//if($building_id > 0) $more['building_id'] = $building_id;
			$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' and `slug_vn`='".$core->replaceSpace($direction)."'", $clsProperty->pkey);
			$home_direction_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
			###
			$code = trim(preg_replace('/\s+/', '', $code));
			$oneStock = $clsStock->getByCond("ms_code='{$code}'");
			if(!empty($oneStock)){
				$more_information = $oneStock['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				// $clsISO->print_pre($more_information); die();
				$more_information['home_direction_id'] = $home_direction_id;
				$more_information['floor'] = $floor;
				$more_information['DT_TT'] = $DT_TT;
				$more_information['DT_Tim'] = $DT_Tim;
				if($clsStock->updateOne($oneStock[$clsStock->pkey], array_merge($more, array(
					'home_direction_id' => $home_direction_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				)))){
					$total_updated += 1;
				}
			}
		}
	}
	$clsISO->print_pre($total_inserted.'|||'.$total_updated); die();
}
function default_open_import_leasing(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsProject = new Project();
	$field = "{$clsProject->pkey},title,more_information";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	// $clsISO->print_pre($list_projects); die();
	if(!empty($list_projects)){
		foreach($list_projects as $key => $val){
			$more_information= $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$spreadsheetLeasingId = isset($more_information['spreadsheetLeasingId']) 
				? $more_information['spreadsheetLeasingId'] : "";
			$list_projects[$key]['spreadsheetLeasingId'] = $spreadsheetLeasingId;
		}
	}
	$smarty->assign('list_projects', $list_projects);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.import_leasing.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_start_import_leasing(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$project_id = (int) Input::post('project_id', 0);
	$more_information = $clsProject->getOneField('more_information', $project_id);
	$more_information = $clsISO->to_array_json($more_information);
	$spreadsheetLeasingId = $more_information['spreadsheetLeasingId'];
	#- Require library
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
	$range = 'BH'; // here we use the name of the Sheet to get all the rows
	$response = $service->spreadsheets_values->get($spreadsheetLeasingId, $range);
	
	$highestColumnIndex = 20;
	$stock_type = _STOCK_TYPE_LEASING;
	$tblData = $response->getValues();
	$html = '<div class="modal-dialog modal-xl" style="width:calc(100vw - 300px)">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
				<h3 class="modal-title"><strong>Import bảng hàng</strong></h3>
			</div>
			<form method="POST">
				<div class="modal-body modal-body-scrollable">
					<table class="table table-striped">
						<thead><tr>
						<th width="3%" class="text-center">No.</th>';
						for($col=0; $col<$highestColumnIndex; $col++){
							$html .= '<th style="min-width:100px" width="'.(100/$highestColumnIndex).'%">
								<select name="columns['.$col.']" class="form-control stock_import_field">
									<option value="">Lựa chọn</option>
									'.$clsStock->renderOptionColumnField($col, $stock_type).'
								</select>
							</th>';
						}
						$html .= '</tr></thead>';
						if(!empty($tblData)){ $ii = 0; // Init
							$cachedName = sprintf('%s.json', $uid);
							$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
							$encoder = new Webmozart\Json\JsonEncoder();
							$encoder->encodeFile($tblData, $cachedFile); 
							foreach($tblData as $key => $val){
								$html.= '<tr>
									<td class="text-center">'.($ii+1).'</td>';
									for($col=0; $col<$highestColumnIndex; $col++){
										$html.= '<td class="text-left">'.$val[$col].'</td>';
									}
								$html.= '</tr>';
								++$ii;
							}
						}
			$html .= '</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default mr-2" data-dismiss="modal">'.$core->get_Lang('Close').'</button>
					<button type="button" class="btn btn-success" uid="'.$uid.'" project_id="'.$project_id.'" stock_type="'.$stock_type.'" onClick="$Core.stock.do_import_leasing(this, event)"><span>Cập nhật bảng hàng</span></button>
				</div>
			</form>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_do_import_leasing(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$uid = Input::post('uid');
	$columns = Input::post('columns', array());
	$stock_type = (int) Input::post('stock_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	$opt_over = Input::post('opt_over','Update');
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$tblData = array();
	$cachedName = sprintf('%s.json', $uid);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
		@unlink($cachedFile);
	}
	$error_field = 0; $msg = "";
	if(!empty($columns)){
		$arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[] = $p_field;
				} else {
					$msg.= ',' . $p_field;
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
	}
	if($error_field == 0){
		$total_results = 0;
		$list_updated_stocks = $list_404_stocks = array();
		if(!empty($tblData)){
			$total_record = @count($tblData);
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
				$oneStock = $clsStock->getByCond("`project_id`='{$project_id}' and `ms_code`='{$ms_code}'", $field);
				if(!empty($oneStock)){ // Cập nhật
					$uid = $clsISO->getUniqid();
					$upd_field = $more_information = array();
					$status_leasing_id = _STOCK_STATUS_LOCK_ID;
					$list_updated_stocks[] = $oneStock[$clsStock->pkey];
					$more_information = $clsISO->to_array_json($oneStock['more_information']);
					foreach($columns as $key => $p_field){
						if(!empty($p_field) && !empty($tblData[$i][$key])){
							if($p_field=='type_id'){
								$field = "{$clsProperty->pkey}";
								$tmp = $clsProperty->getByCond("is_trash=0 and `property_type`='_TYPE_VILLA' 
									and (`property_code`='".$tblData[$i][$key]."' 
									or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
									or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $field);
								$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
							} else if($p_field=='agency_id'){
								$field = "{$clsProperty->pkey},more_information";
								$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`property_code`='".$tblData[$i][$key]."' 
									or `slug` like '%".$core->replaceSpace($tblData[$i][$key])."%' 
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
										'order_no' => $clsProperty->getMaxorderNo(),
										'user_id' => $core->_USER['user_id'],
										'user_id_update' => $core->_USER['user_id'],
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
								$tmp = $clsProperty->getByCond("property_type='_DIRECTION' and slug='".$core->replaceSpace($tblData[$i][$key])."'", $field);
								$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
							} else if($p_field=='fee_included'){
								$field = "{$clsProperty->pkey}";
								$tmp = $clsProperty->getByCond("property_type='_MANAGEMENT_FEE_INCLUDED' and slug='".$core->replaceSpace($tblData[$i][$key])."'", $field);
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
						$total_results += 1;
					}
				} else {
					$list_404_stocks[] = $ms_code;
				}
			}
		}
		if($list_updated_stocks){
			$list_sold_stocks = $clsStock->getAll("`project_id`='{$project_id}' and `stock_type`='"._STOCK_TYPE_LEASING."' 
			and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `stock_id` not in (".implode(',', $list_updated_stocks).")", "{$clsStock->pkey}");
			if(!empty($list_sold_stocks)){
				foreach($list_sold_stocks as $key => $val){
					$clsStock->updateOne($val[$clsStock->pkey], array(
						'status_id' => _STOCK_STATUS_SOLD_ID
					));
				}
			}
		}
		if(!empty($list_404_stocks)){
			$clsAdminLog = new AdminLog();
			$clsAdminLog->insert(array(
				'date' => time(),
				'action' => 'update_stock_leasing',
				'user_id' => $core->_USER['user_id'],
				'target_id' => 0,
				'description' => json_encode($list_404_stocks, JSON_UNESCAPED_UNICODE)
			));
		}
		// Return
		echo 'success|||'.$total_results;
		die();
	} else {
		echo 'error_field';
		die();
	}
}
function default_open_import_lowfloor(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsProject = new Project();
	$field = "{$clsProject->pkey},title,more_information";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	// $clsISO->print_pre($list_projects); die();
	if(!empty($list_projects)){
		foreach($list_projects as $key => $val){
			$more_information= $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$spreadsheetId = isset($more_information['spreadsheetId']) 
				? $more_information['spreadsheetId'] : "";
			$list_projects[$key]['spreadsheetId'] = $spreadsheetId;
		}
	}
	$smarty->assign('list_projects', $list_projects);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.import_lowfloor.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_start_import_lowfloor(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$project_id = (int) Input::post('project_id', 0);
	$more_information = $clsProject->getOneField('more_information', $project_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$spreadsheetId = $more_information['spreadsheetId'];
	#- Require library
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
	$range = 'BH'; // here we use the name of the Sheet to get all the rows
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$highestColumnIndex = 22;
	$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;
	$tblData = $response->getValues();
	// $clsISO->print_pre($tblData); die();
	$html = '<div class="modal-dialog modal-xl" style="width:calc(100vw - 100px)">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
				<h3 class="modal-title"><strong>Import bảng hàng</strong></h3>
			</div>
			<form method="POST">
				<div class="modal-body modal-body-scrollable">
					<table class="table table-bordered table-fixed table-striped">
						<thead><tr>
						<th width="3%" class="text-center">No.</th>';
						for($col=0; $col<$highestColumnIndex; $col++){
							$html .= '<th style="min-width:100px" width="'.(100/$highestColumnIndex).'%">
								<select name="columns['.$col.']" class="form-control stock_import_field">
									<option value="">Lựa chọn</option>
									'.$clsStock->renderOptionColumnField($col, $stock_type,"",$project_id).'
								</select>
							</th>';
						}
						$html .= '</tr></thead>';
						if(!empty($tblData)){ $ii = 0; // Init
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
				</div>
				<div class="modal-footer">
					<div class="d-flex align-items-center justify-content-between">
						<div class="p__left">
							<div class="d-flex align-items-center">
								<label class="switch mr-2">
									<input type="checkbox" name="opt_ignore_empty" value="1" checked="checked">
									<span class="slider round"></span>
								</label>
								<span>Bỏ qua giá trị trống</span>
							</div>
						</div>
						<div class="p__right">
							<button type="button" class="btn btn-default mr-2" data-dismiss="modal">'.$core->get_Lang('Close').'</button>
							<button type="button" class="btn btn-success" uid="'.$uid.'" project_id="'.$project_id.'" stock_type="'.$stock_type.'" onClick="$Core.stock.do_import_lowfloor(this, event)"><span>Cập nhật bảng hàng</span></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_do_import_lowfloor(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProperty = new Property();
	###
	$uid = Input::post('uid');
	$columns = Input::post('columns', array());
	$opt_over = Input::post('opt_over','Update');
	$stock_type = (int) Input::post('stock_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	$opt_ignore_empty = (int) Input::post('opt_ignore_empty', 0);
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
	// Update về đã bán
	$field = "{$clsStock->pkey},`more_information`";
	$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}' 
	and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`>0", $field);
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
			$clsStock->updateOne($val[$clsStock->pkey], array(
				'status_id' => _STOCK_STATUS_SOLD_ID,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	if($error_field == 0){
		$total_results = 0;
		$list_404_stocks = $list_agency_updated_ids = array();
		if(!empty($tblData)){
			$total_record = @count($tblData);
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
				$field = "{$clsStock->pkey},`status_id`,`DT_TT`,`more_information`";
				$oneStock = $clsStock->getByCond("`is_trash`=0 and stock_type='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
				and `project_id`='{$project_id}' and `ms_code`='{$ms_code}'", $field);
				if(!empty($oneStock)){ // Cập nhật
					$uid = $clsISO->getUniqid();
					$upd_field = $more_information = array();
					$status_id = $oneStock['status_id'];
					$stock_status_id = _STOCK_STATUS_LOCK_ID;
					$more_information = $oneStock['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					
					$logs = array();
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
					foreach($columns as $key => $p_field){
						if(!empty($p_field) && !empty($tblData[$i][$key])){
							if($p_field=='type_id'){
								$field = "{$clsProperty->pkey}";
								$tmp = $clsProperty->getByCond("is_trash=0 and `property_type`='_TYPE_VILLA' 
									and (`property_code`='".$tblData[$i][$key]."' 
									or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
									or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $field);
								$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
							} else if($p_field=='agency_id'){
								$field = "{$clsProperty->pkey},more_information";
								$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`property_code`='".$tblData[$i][$key]."' 
									or `slug` like '%".$core->replaceSpace($tblData[$i][$key])."%' 
									or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
								)", $field);
								if(!empty($tmp)){
									$tblData[$i][$key] = $tmp[$clsProperty->pkey];
									if(!in_array($tmp[$clsProperty->pkey], $list_agency_updated_ids)){
										$list_agency_updated_ids[] = $tmp[$clsProperty->pkey];
									}
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
								$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_AGENCY' 
									and (`slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $field);
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
							} else if(in_array($p_field, array('bank_second_id','bank_id'))){
								$field = "{$clsProperty->pkey}";
								$tmp = $clsProperty->getByCond("property_type='_BANK' and slug='".$core->replaceSpace($tblData[$i][$key])."'", $field);
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
							if(!in_array($p_field, array('DT_Tim','total_price','total_price_vat','csbh','TCBG','contract_subject_id'
								,'invest_fund_id','bank_second_id','bank_id','sale_status_id','agent_lock_id','deposit_agent_id'
								,'total_price_early','total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
								,'total_price_bank_18','total_price_bank_12','cs_policy_ns','price_temporary_ns','notes','stock_hold_id'
								,'contract_type_id','deposit_date'))){
								if($p_field=='DT_TT'){
									$upd_field[$p_field] = $clsISO->toNumber($tblData[$i][$key]);
								} else {
									$upd_field[$p_field] = $tblData[$i][$key];
								}
								$more_information[$p_field] = $tblData[$i][$key];
							} else {
								if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
									,'total_price_progress','total_price_bank','total_price_bank_12','total_price_bank_18'
									,'total_price_bank_30','total_price_bank_36'))){
									$more_information[$p_field] = $clsStock->getPriceOrigin($tblData[$i][$key]);
									if($p_field=='total_price_vat'){
										$upd_field['total_price_vat'] = $clsStock->getPriceOrigin($tblData[$i][$key]);
									}
								} else {
									$more_information[$p_field] = $tblData[$i][$key];
								}
								if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
									,'total_price_progress','total_price_bank_12','total_price_bank_18','total_price_bank'
									,'total_price_bank_30','total_price_bank_36'))){
									if((!isset($more_information[$p_field]) || (!empty($more_information[$p_field]) 
										&& $more_information[$p_field] != $clsISO->convertToNumber($tblData[$i][$key])))){
										$logs[$clsISO->getUniqid()] = array(
											'reg_date' => time(),
											'user_id' => $core->_USER['user_id'],
											'from_value' => $more_information[$p_field],
											'to_value' => $clsStock->getPriceOrigin($tblData[$i][$key]),
											'field' => $p_field
										);
									}
								}
							}	
						} else if($opt_ignore_empty == 0 && in_array($p_field, array('total_price','total_price_vat'
							,'total_price_early','total_price_progress','total_price_bank','total_price_bank_30'
							,'total_price_bank_36','total_price_bank_12','total_price_bank_18','TCBG',"csbh"))){
							if($p_field=='TCBG' || $p_field == 'csbh'){
								$more_information[$p_field] = "";
							} else {
								$more_information[$p_field] = 0;
							}
						}
					}
					if(empty($oneStock['DT_TT']) && !empty($more_information['DT_TT']) && !isset($upd_field['DT_TT'])){
						$upd_field['DT_TT'] = $clsISO->toNumber($more_information['DT_TT']);
					}
					$more_information['status_id'] = $stock_status_id;
					$upd_field['status_id'] = $stock_status_id;
					$upd_field['upd_date'] = time();
					//$upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
					$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
					if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
						$total_results += 1;
						if(!empty($oneStockMeta) && !empty($logs)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));
						}
					}
				} else {
					$list_404_stocks[] = $ms_code;
				}
			}
			if(!empty($list_404_stocks)){
				$clsAdminLog = new AdminLog();
				$clsAdminLog->insert(array(
					'date' => time(),
					'action' => 'update_stock_leasing',
					'user_id' => $core->_USER['user_id'],
					'target_id' => $project_id,
					'description' => json_encode($list_404_stocks, JSON_UNESCAPED_UNICODE)
				));
			}
			// Start Logs
			if(!empty($list_agency_updated_ids)){
				foreach($list_agency_updated_ids as $agency_id){
					$clsAdminLog = new AdminLog();
					$clsAdminLog->insertLog('update_stock', _BLOCK_TYPE_LOWFLOOR_SALE, 
						$project_id, $agency_id, array(), '_admin');
				}
			}
			// End Logs	
		}
		// Return
		echo 'success|||'.$total_results;
		die();
	} else {
		echo 'error_field';
		die();
	}
}
function default_import_lowfloor(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$project_id = 9; // 2: VHOP2, 3:VHOP3
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
	$range = 'MATONG'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1XzM7mhdGUrmVBksmvIXZZNW24c7tkGyv28xg13ybK48';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	$list_code = array();
//	 $clsISO->print_pre($tblData); die(); 
	$arr_cache_type_villa = $arr_cache_direction = array();
	if(!empty($tblData)){
		for($i=1; $i<count($tblData); $i++){
			$block_name = $tblData[$i][0];
			$block_code = $tblData[$i][1];
			$range_code = $tblData[$i][2];
			$range_name = $tblData[$i][3];
			$range_block = $tblData[$i][4];
			$type_villa = $tblData[$i][5];
			$direction 	= $tblData[$i][7];
			if(!isset($arr_cache_type_villa[$type_villa])) {
				$arr_cache_type_villa[$type_villa] = $clsProperty->getByCond("`property_code`='{$type_villa}' AND property_type='_TYPE_VILLA'");
			}
			$type_id = !empty($arr_cache_type_villa[$type_villa][$clsProperty->pkey]) ? $arr_cache_type_villa[$type_villa][$clsProperty->pkey] : 0;
			if(!isset($arr_cache_direction[$direction])) {
				$arr_cache_direction[$direction] = $clsProperty->getByCond("`title`='{$direction}' AND property_type='_DIRECTION'");
			}
			$home_direction_id = !empty($arr_cache_direction[$direction][$clsProperty->pkey]) ? $arr_cache_direction[$direction][$clsProperty->pkey] : 0;
			$range_block = preg_replace('/\s+/', '', $range_block);
			if(!empty($block_name) && !empty($block_code) && !empty($range_code) && !empty($range_block)){
				$path_parts = @explode(',', $range_block);
				foreach($path_parts as $val){
					if(@str_replace('(chẵn)','', $val) != $val){ // Odd
						$tmp = str_replace('(chẵn)','', $val);
						$tmp = @explode('-', $tmp);
						$sufix = $tmp[0];
						$tmp = @explode('>', $tmp[1]);
						for($k=intval($tmp[0]); $k<=intval($tmp[1]); $k+=2){
							$list_code[] = array(
								'block_name' => $block_name,
								'block_code' => $block_code,
								'range_code' => $range_code,
								'range_name' => $range_name,
								'code' => $clsISO->parseNumber($k),
								'ms_code' => sprintf('%s-%s', $sufix, $clsISO->parseNumber($k)),
								'type_id'	=>	$type_id,
								'home_direction_id'	=>	$home_direction_id,
							);
						}
					} else if(@str_replace('(lẻ)','', $val) != $val){
						$tmp = str_replace('(lẻ)','', $val);
						$tmp = @explode('-', $tmp);
						$sufix = $tmp[0];
						$tmp = @explode('>', $tmp[1]);
						for($k=intval($tmp[0]); $k<=intval($tmp[1]); $k+=2){
							$list_code[] = array(
								'block_name' => $block_name,
								'block_code' => $block_code,
								'range_code' => $range_code,
								'range_name' => $range_name,
								'code' => $clsISO->parseNumber($k),
								'ms_code' => sprintf('%s-%s', $sufix, $clsISO->parseNumber($k)),
								'type_id'	=>	$type_id,
								'home_direction_id'	=>	$home_direction_id,
							);
						}
					} else {
						$tmp = @explode('-', $val);
						$sufix = $tmp[0];
						$tmp = @explode('>', $tmp[1]);
						for($k=intval($tmp[0]); $k<=intval($tmp[1]); $k+=1){
							$list_code[] = array(
								'block_name' => $block_name,
								'block_code' => $block_code,
								'range_code' => $range_code,
								'range_name' => $range_name,
								'code' => $clsISO->parseNumber($k),
								'ms_code' => sprintf('%s-%s', $sufix, $clsISO->parseNumber($k)),
								'type_id'	=>	$type_id,
								'home_direction_id'	=>	$home_direction_id,
							);
						}
					}
				}
			}
		}
	}
//	 $clsISO->print_pre($list_code); die(); 
	$total_insert = 0;
	if(!empty($list_code)){
		foreach($list_code as $val){
			$block_name = $val['block_name'];
			$block_code = $val['block_code'];
			$range_code = $val['range_code'];
			$range_name = $val['range_name'];
			$code = $val['code'];
			$ms_code = $val['ms_code'];
			$type_id = $val['type_id'];
			$home_direction_id = $val['home_direction_id'];
			$oBlock = $clsProperty->getByCond("`property_type`='_BLOCK' and (property_code='{$block_code}' or `slug`='".$core->replaceSpace($block_name)."') and `for_id`='{$project_id}'", $clsProperty->pkey);
			$block_id = !empty($oBlock) ? $oBlock[$clsProperty->pkey] : 0;
			if($block_id > 0){
				$oRange = $clsProperty->getByCond("`property_type`='_RANGE' and `for_id`='{$block_id}' and (`property_code`='{$range_code}' and `slug`='".$core->replaceSpace($range_name)."')", $clsProperty->pkey); 
				if(!empty($oRange)){
					$builing_id = $oRange[$clsProperty->pkey];
				} else {
					$builing_id = $clsProperty->getMaxId();
					$clsProperty->insert(array(
						$clsProperty->pkey => $builing_id,
						'property_type' => '_RANGE',
						'property_code' => $range_code,
						'title' => $range_name,
						'slug' => $core->replaceSpace($range_name),
						'order_no' => $clsProperty->getMaxOrderNo(),
						'reg_date' => time(),
						'upd_date' => time(),
						'for_id' => $block_id,
						'user_id' => $core->_USER['user_id'],
						'user_id_update' => $core->_USER['user_id']
					));
				}
				if($builing_id > 0){
					$oStock = $clsStock->getByCond("`ms_code`='{$ms_code}' and `project_id`='{$project_id}' and `block_id`='{$block_id}' AND `building_id`='{$builing_id}'", $clsStock->pkey);
//					$clsISO->print_pre($oStock); die(); 
					if(!empty($oStock)){
						/*if($clsStock->updateOne($oStock[$clsStock->pkey], array(
							'building_id' => $builing_id
						))){
							$total_insert++;
						}*/
					} else {
						if($clsStock->insert(array(
							$clsStock->pkey => $clsStock->getMaxId(),
							'stock_type' => _BLOCK_TYPE_LOWFLOOR_SALE,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'building_id' => $builing_id,
							'type_id' => $type_id,
							'home_direction_id' => $home_direction_id,
							'code' => $code,
							'ms_code' => $ms_code,
							'reg_date' => time(),
							'upd_date' => time(),
							'user_id' => $core->_USER['user_id'],
							'user_id_update' => $core->_USER['user_id']
						))){
							$total_insert++;
						}
					}
				}
			}
		}
	}
	$clsISO->print_pre($total_insert); die();
}
function default_import_lowfloor_step1(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	//die("x"); 
	$project_id = 2; // 2: VHOP2, 3:VHOP3
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
	$range = 'BH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1QR176fcvW0fydkWNc3zU4RKr66R10miEGpjNBFtDYIc';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	$clsISO->print_pre($tblData); die();
	$total_updated = 0;
	$list_code = array();
	if(!empty($tblData)){
		for($i=1; $i<count($tblData); $i++){
			$ms_code = $tblData[$i][2];
			$DT_TT = $tblData[$i][4];
			$DT_Tim = $tblData[$i][11];
			$oneStock = $clsStock->getByCond("ms_code='{$ms_code}' and `project_id`='{$project_id}'", "{$clsStock->pkey},more_information");
			if(!empty($oneStock)){
				$more_information = $oneStock['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(empty($more_information['DT_TT'])){
					$more_information['DT_TT'] = $DT_TT;
					$more_information['DT_Tim'] = $DT_Tim;
					// $clsISO->print_pre($more_information); die();
					if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
						'DT_TT' => $clsISO->toNumber($DT_TT),
						'more_information'=> json_encode($more_information)
					))){
						$total_updated += 1;
					}
				}
			}
		}
	}
	$clsISO->print_pre($total_updated); die();
}
function default_import_lowfloor_step2(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$project_id = 2; // 2: VHOP2, 3:VHOP3
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
	$range = 'BH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1d3b_1qv1yBsTaitIs3CtWAcgjZlTq1stR-bPllh3Moc';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	$total_updated = 0;
	if(!empty($tblData)){
		for($i=1; $i<count($tblData); $i++){
			$block_code = $tblData[$i][1];
			$ms_code = $tblData[$i][2];
			$template_code = $tblData[$i][3];
			$DT_TT = $tblData[$i][4];
			$DT_Tim = $tblData[$i][11]; // XD
			$DT_FL1 = $tblData[$i][5];
			$DT_FL2 = $tblData[$i][6];
			$DT_FL3 = $tblData[$i][7];
			$DT_FL4 = $tblData[$i][8];
			$DT_FL5 = $tblData[$i][9];
			$oneStock = $clsStock->getByCond("project_id='{$project_id}' and ms_code='{$ms_code}'", "{$clsStock->pkey},more_information");
			if(!empty($oneStock)){
				$more_information = $oneStock['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$more_information['DT_Tim'] = $DT_Tim;
				$more_information['DT_TT'] = $DT_TT;
				$more_information['DT_FL1'] = $DT_FL1;
				$more_information['DT_FL2'] = $DT_FL2;
				$more_information['DT_FL3'] = $DT_FL3;
				$more_information['DT_FL4'] = $DT_FL4;
				$more_information['DT_FL5'] = $DT_FL5;
				$more_information['template_code'] = $template_code;
				// $clsISO->print_pre($clsISO->toNumber($tblData[$i])); die();
				// $dbconn->debug = true;
				if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
					'DT_TT' => $clsISO->toNumber($DT_TT),
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				))){
					//die("xxx");
					$total_updated++;
				}
			}
		}
	}
	$clsISO->print_pre($total_updated); die();
}
function default_import_lowfloor_step2xxx(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	// die("x");
	$project_id = 3; // 2: VHOP2, 3:VHOP3
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
	$range = 'LH'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1ljY2B8WsVcmQKlMsSzU_e-Wx-zR3uBB4j-qmRhktGBU';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	$total_updated = 0;
	$list_code = array();
	if(!empty($tblData)){
		for($i=1; $i<count($tblData); $i++){
			$range_block = $tblData[$i][0];
			$property_code = $tblData[$i][1];
			if(!empty($range_block) && !empty($property_code)){
				$range_block = preg_replace('/\s+/', '', $range_block);
				if(@str_replace('(chẵn)','', $range_block) != $range_block){ // Odd	
					$tmp = str_replace('(chẵn)','', $range_block);
					$path_parts = @explode('-', $tmp);
					$sufix = $path_parts[0];
					$tmp = explode('>', $path_parts[1]);
					for($k = intval($tmp[0]); $k<=intval($tmp[1]); $k+=2){
						$list_code[sprintf('%s-%s', $sufix, $clsISO->parseNumber($k))] = $property_code;
					}
				} elseif(@str_replace('(lẻ)','', $range_block) != $range_block){ // Even	 
					$tmp = str_replace('(lẻ)','', $range_block);
					$path_parts = @explode('-', $tmp);
					$sufix = $path_parts[0];
					$tmp = explode('>', $path_parts[1]);
					for($k = intval($tmp[0]); $k<=intval($tmp[1]); $k+=2){
						$list_code[sprintf('%s-%s', $sufix, $clsISO->parseNumber($k))] = $property_code;
					}
				} else {
					$path_parts = @explode('-', $range_block);
					$sufix = $path_parts[0];
					$tmp = explode('>', $path_parts[1]);
					for($k = intval($tmp[0]); $k<=intval($tmp[1]); $k+=1){
						$list_code[sprintf('%s-%s', $sufix, $clsISO->parseNumber($k))] = $property_code;
					}
				}
			}
		}
	}
	
//	$clsISO->print_pre($list_code); die();
	if(!empty($list_code)){
		foreach($list_code as $ms_code => $property_code){
			if($property_code=='TL') $type_id = '333';
			if($property_code=='SL') $type_id = '331';
			if($property_code=='SH') $type_id = '330';
			$oneStock = $clsStock->getByCond("ms_code='{$ms_code}'", "{$clsStock->pkey},more_information");
			if(!empty($oneStock)){
				$more_information = $oneStock['more_information'];
				$more_information = !empty($more_information) 
					? json_decode(html_entity_decode($more_information), true) : array();
				$more_information['type_id'] = $type_id;
				// $clsISO->print_pre($more_information); die();
				$clsStock->updateOne($oneStock[$clsStock->pkey], array(
					'type_id' => $type_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				));
			}
		}
	}
	$clsISO->print_pre($list_code); die();
}
function default_import_lowfloor_TMB(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$project_id = 3; // 2: VHOP2, 3:VHOP3, 9:VHGG, 10:VWC
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
	$range = 'VIN3_BS'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1g9njaDNcKzAvr93Dpv3dWtdl-VZXz5LMG6N9bNkMGdc';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	$list_code = array();
//	 $clsISO->print_pre($tblData); die(); 
	if(!empty($tblData)){
		for($i=1; $i<count($tblData); $i++){
			$block_name = $tblData[$i][0];
			$block_code = $tblData[$i][1];
			$range_code = $tblData[$i][2];
			$range_name = $tblData[$i][3];
			$range_block = $tblData[$i][4];
			$range_block = preg_replace('/\s+/', '', $range_block);
			$property_code = $tblData[$i][5];
			$DT_TT = $tblData[$i][6];
			$home_direction = $tblData[$i][7];
			if($property_code=='TL') $type_id = '333';
			if($property_code=='DL') $type_id = '332';
			if($property_code=='SL') $type_id = '331';
			if($property_code=='LK') $type_id = '329';
			if(!empty($block_name) && !empty($block_code) && !empty($range_code) && !empty($range_block)){
				$path_parts = @explode(',', $range_block);
				foreach($path_parts as $val){
					if(@str_replace('(chẵn)','', $val) != $val){ // Odd
						$tmp = str_replace('(chẵn)','', $val);
						$tmp = @explode('-', $tmp);
						$sufix = $tmp[0];
						$tmp = @explode('>', $tmp[1]);
						for($k=intval($tmp[0]); $k<=intval($tmp[1]); $k+=2){
							$list_code[] = array(
								'block_name' => $block_name,
								'block_code' => $block_code,
								'range_code' => $range_code,
								'range_name' => $range_name,
								'code' => $clsISO->parseNumber($k),
								'ms_code' => sprintf('%s-%s', $sufix, $clsISO->parseNumber($k)),
								'type_id'	=>	$type_id,
								'DT_TT'	=>	$DT_TT,
								'home_direction'	=>	$home_direction
							);
						}
					} else if(@str_replace('(lẻ)','', $val) != $val){
						$tmp = str_replace('(lẻ)','', $val);
						$tmp = @explode('-', $tmp);
						$sufix = $tmp[0];
						$tmp = @explode('>', $tmp[1]);
						for($k=intval($tmp[0]); $k<=intval($tmp[1]); $k+=2){
							$list_code[] = array(
								'block_name' => $block_name,
								'block_code' => $block_code,
								'range_code' => $range_code,
								'range_name' => $range_name,
								'code' => $clsISO->parseNumber($k),
								'ms_code' => sprintf('%s-%s', $sufix, $clsISO->parseNumber($k)),
								'type_id'	=>	$type_id,
								'DT_TT'	=>	$DT_TT,
								'home_direction'	=>	$home_direction
							);
						}
					} else {
						$tmp = @explode('-', $val);
						$sufix = $tmp[0];
						$tmp = @explode('>', $tmp[1]);
						for($k=intval($tmp[0]); $k<=intval($tmp[1]); $k+=1){
							$list_code[] = array(
								'block_name' => $block_name,
								'block_code' => $block_code,
								'range_code' => $range_code,
								'range_name' => $range_name,
								'code' => $clsISO->parseNumber($k),
								'ms_code' => sprintf('%s-%s', $sufix, $clsISO->parseNumber($k)),
								'type_id'	=>	$type_id,
								'DT_TT'	=>	$DT_TT,
								'home_direction'	=>	$home_direction
							);
						}
					}
				}
			}
		}
	}
//	 $clsISO->print_pre($list_code); die(); 
	$total_insert = 0;
	if(!empty($list_code)){
		foreach($list_code as $val){
			$block_name = $val['block_name'];
			$block_code = $val['block_code'];
			$range_code = $val['range_code'];
			$range_name = $val['range_name'];
			$type_id = $val['type_id'];
			$DT_TT = $val['DT_TT'];
			$home_direction = $val['home_direction'];
			$oHomeDirection= $clsProperty->getByCond("`property_type`='_DIRECTION' and (`slug`='".$core->replaceSpace($home_direction)."')");
			$home_direction_id = !empty($oHomeDirection) ? $oHomeDirection[$clsProperty->pkey] : 0; 
			$more_information = [
				"type_id"	=>	$type_id,
				"DT_TT"		=>	$DT_TT,
				"home_direction_id"		=>	$home_direction_id,
			];
//			'{"type_id":"329","DT_TT":149.62,"DT_Tim":520.398,"status_id":32,"home_direction_id":"3","floor":5}'
			$code = $val['code'];
			$ms_code = $val['ms_code'];
			$oBlock = $clsProperty->getByCond("`property_type`='_BLOCK' and (property_code='{$block_code}' or `slug`='".$core->replaceSpace($block_name)."') and `for_id`='{$project_id}'", $clsProperty->pkey);
			$block_id = !empty($oBlock) ? $oBlock[$clsProperty->pkey] : 0; 
			
			$clsStock->setDeBug(1);
			if($block_id > 0){
				$oRange = $clsProperty->getByCond("`property_type`='_RANGE' and `for_id`='{$block_id}' and (`property_code`='{$range_code}' and `slug`='".$core->replaceSpace($range_name)."')", $clsProperty->pkey);
				if(!empty($oRange)){
					$builing_id = $oRange[$clsProperty->pkey];
				} else {
					$builing_id = $clsProperty->getMaxId();
					$arr_data = array(
						$clsProperty->pkey => $builing_id,
						'property_type' => '_RANGE',
						'property_code' => $range_code,
						'title' => $range_name,
						'slug' => $core->replaceSpace($range_name),
						'order_no' => $clsProperty->getMaxOrderNo(),
						'reg_date' => time(),
						'upd_date' => time(),
						'for_id' => $block_id,
						'user_id' => $core->_USER['user_id'],
						'user_id_update' => $core->_USER['user_id'],
					);
//					$clsISO->print_pre($arr_data); die(); 
					$clsProperty->insert($arr_data);
				}
				if($builing_id > 0){
					$oStock = $clsStock->getByCond("`ms_code`='{$ms_code}' AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}'", $clsStock->pkey);
					
					if(!empty($oStock)){
						echo 1;
						/*if($clsStock->updateOne($oStock[$clsStock->pkey], array(
							'building_id' => $builing_id,
							'more_information'	=> json_encode($more_information)
						))){
							$total_insert++;
						}*/
					} else {
						$arr_data = array(
							$clsStock->pkey => $clsStock->getMaxId(),
							'stock_type' => _BLOCK_TYPE_LOWFLOOR_SALE,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'building_id' => $builing_id,
							'type_id' => $type_id,
							'code' => $code,
							'ms_code' => $ms_code,
							'reg_date' => time(),
							'upd_date' => time(),
							'user_id' => $core->_USER['user_id'],
							'user_id_update' => $core->_USER['user_id'],
							'more_information'	=> json_encode($more_information)
						);
//						$clsISO->print_pre($arr_data); die(); 
						if($clsStock->insert($arr_data)){
							$total_insert++;
						}
					}
				}
			}
		}
	}
	$clsISO->print_pre($total_insert); die();
}
function default_import_lowfloor_TMB_step2(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$project_id = 3; // 2: VHOP2, 3:VHOP3, 9:VHGG, 10:VWC
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
	$range = 'CHITIET_V3'; // here we use the name of the Sheet to get all the rows
	$spreadsheetId = '1g9njaDNcKzAvr93Dpv3dWtdl-VZXz5LMG6N9bNkMGdc';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	$list_code = array();
//	 $clsISO->print_pre($tblData); die(); 
	if(!empty($tblData)){
		for($i=1; $i<count($tblData); $i++){
			$block_name = $tblData[$i][0];
			$block_code = $tblData[$i][1];
			$range_code = $tblData[$i][2];
			$range_name = $tblData[$i][3];
			$stock_code = $tblData[$i][4];
			$property_code = $tblData[$i][5];
			$DT_TT = $tblData[$i][6];
			$home_direction = $tblData[$i][7];
			if($property_code=='TL') $type_id = '333';
			if($property_code=='DL') $type_id = '332';
			if($property_code=='SL') $type_id = '331';
			if($property_code=='LK') $type_id = '329';
			if(!empty($block_name) && !empty($block_code) && !empty($range_code) && !empty($stock_code)){
				$path_parts = @explode(',', $range_block);
				$list_code[] = array(
					'block_name' => $block_name,
					'block_code' => $block_code,
					'range_code' => $range_code,
					'range_name' => $range_name,
					'ms_code' => $stock_code,
					'type_id'	=>	$type_id,
					'DT_TT'	=>	$DT_TT,
					'home_direction'	=>	$home_direction
				);
			}
		}
	}
//	 $clsISO->print_pre($list_code); die(); 
	$total_insert = 0;
	if(!empty($list_code)){
		foreach($list_code as $val){
			$block_name = $val['block_name'];
			$block_code = $val['block_code'];
			$range_code = $val['range_code'];
			$range_name = $val['range_name'];
			$type_id = $val['type_id'];
			$DT_TT = $val['DT_TT'];
			$home_direction = $val['home_direction'];
			$oHomeDirection= $clsProperty->getByCond("`property_type`='_DIRECTION' and (`slug`='".$core->replaceSpace($home_direction)."')");
			$home_direction_id = !empty($oHomeDirection) ? $oHomeDirection[$clsProperty->pkey] : 0; 
			$more_information = [
				"type_id"	=>	$type_id,
				"DT_TT"		=>	$DT_TT,
				"home_direction_id"		=>	$home_direction_id,
			];
//			'{"type_id":"329","DT_TT":149.62,"DT_Tim":520.398,"status_id":32,"home_direction_id":"3","floor":5}'
			$code = $val['code'];
			$ms_code = $val['ms_code'];
			$oBlock = $clsProperty->getByCond("`property_type`='_BLOCK' and (property_code='{$block_code}' or `slug`='".$core->replaceSpace($block_name)."') and `for_id`='{$project_id}'", $clsProperty->pkey);
			$block_id = !empty($oBlock) ? $oBlock[$clsProperty->pkey] : 0; 
			if($block_id > 0){
//				$clsProperty->setDeBug(1);
				$oRange = $clsProperty->getByCond("`property_type`='_RANGE' and `for_id`='{$block_id}' and (`property_code`='{$range_code}' and `slug`='".$core->replaceSpace($range_name)."')", $clsProperty->pkey);
				if(!empty($oRange)){
					$builing_id = $oRange[$clsProperty->pkey];
				} else {
					$builing_id = $clsProperty->getMaxId();
					$arr_data = array(
						$clsProperty->pkey => $builing_id,
						'property_type' => '_RANGE',
						'property_code' => $range_code,
						'title' => $range_name,
						'slug' => $core->replaceSpace($range_name),
						'order_no' => $clsProperty->getMaxOrderNo(),
						'reg_date' => time(),
						'upd_date' => time(),
						'for_id' => $block_id,
						'user_id' => $core->_USER['user_id'],
						'user_id_update' => $core->_USER['user_id'],
					);
//					$clsISO->print_pre($arr_data); die(); 
					$clsProperty->insert($arr_data);
				}
				if($builing_id > 0){
//					$clsStock->setDeBug(1);
					$oStock = $clsStock->getByCond("`ms_code`='{$ms_code}' AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}'", $clsStock->pkey);
//					
					if(!empty($oStock)){						
						$arr_data = array(
							'building_id' => $builing_id,
							'more_information'	=> json_encode($more_information)
						);
						$clsISO->print_pre($arr_data);
						if($clsStock->updateOne($oStock[$clsStock->pkey], $arr_data)){
							$total_insert++;
						}
					} else {
						$arr_data = array(
							$clsStock->pkey => $clsStock->getMaxId(),
							'stock_type' => _BLOCK_TYPE_LOWFLOOR_SALE,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'building_id' => $builing_id,
							'type_id' => 329,
							'code' => $code,
							'ms_code' => $ms_code,
							'reg_date' => time(),
							'upd_date' => time(),
							'user_id' => $core->_USER['user_id'],
							'user_id_update' => $core->_USER['user_id'],
							'more_information'	=> json_encode($more_information)
						);
//						var_dump($ms_code);die;
//						echo 2;
						$clsISO->print_pre($arr_data); die(); 
						if($clsStock->insert($arr_data)){
							$total_insert++;
						}
					}
				}
			}
		}
	}
	$clsISO->print_pre($total_insert); die();
}
function default_open_image(){
	global $core,$smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsUser = new User();
	$clsAdminLog = new AdminLog();
	$clsProperty = new Property();
	
	$project_id = (int)Input::post("project_id",0);
	$field = "{$clsProperty->pkey},title,intro,more_information";
	$list_agents = $clsProperty->getAll("`is_locked`=0 and `property_type`='_AGENCY' order by `order_no` ASC", $field);
	$smarty->assign('list_agents', $list_agents);
	$smarty->assign('project_id', $project_id);
	//Return
	$html = $core->build('_ajax.open_image.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_start_get_data_image(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsUser = new User();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$agency_id = (int) Input::post('agency_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$type = Input::post('type', "_COPY");
	$data = $data_row = array();
	$data_account = $clsStock->getDataAccount();
	$more_information = $clsProject->getOneField('more_information', $project_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();

	$spreadsheetId = $more_information['spreadsheetId'];
	
	if($data_account["api_key"] == "" || $data_account["model_id"] == "") {
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Không thể đọc được file ảnh"
		)); die();
	}
	
	$oneAgent = $clsProperty->getOne($agency_id,"title");
	$agentcy_name = $oneAgent['title'];
	
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	
	//get dữ liệu giỏ bank	
	$cachedBankName = "bank_cart_".$project_id.".json";
	$cachedBankFile = DIR_CACHE_JSON.'/'.$cachedBankName;
	$data_bank = $arr_bank = [];
	if(@file_exists($cachedBankFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$data_bank = $decoder->decodeFile($cachedBankFile);
		if(!empty($data_bank[date("d/m/Y")])){
			$arr_bank = $data_bank[date("d/m/Y")];
		}else{
			$service_bank = new Google_Service_Sheets($client);
			$range = 'GB';
			$response = $service_bank->spreadsheets_values->get($spreadsheetId, $range);
			$tblDataBank = $response->getValues();
			for($i=1; $i<count($tblDataBank); $i++){
				$arr_bank[$tblDataBank[$i][1]] = $tblDataBank[$i][2];
			}
			$data_bank[date("d/m/Y")] = $arr_bank;
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($data_bank, $cachedBankFile);
		}
	}else{		
		$service_bank = new Google_Service_Sheets($client);
		$range = 'GB';
		$response = $service_bank->spreadsheets_values->get($spreadsheetId, $range);
		$tblDataBank = $response->getValues();
		$arr_bank = [];
		for($i=1; $i<count($tblDataBank); $i++){
			$arr_bank[$tblDataBank[$i][1]] = $tblDataBank[$i][2];
		}
		$data_bank[date("d/m/Y")] = $arr_bank;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($data_bank, $cachedBankFile); 
	}
//	$clsISO->print_pre($arr_bank);die;
	$curl = new Curl\Curl();
	$curl->setBasicAuthentication($data_account['api_key'], '');
	$curl->setHeader('Content-Type','multipart/form-data');
	$arr_image = [];
	if(isset($_FILES['images']) && !empty($_FILES['images']['name'])) {
		for ($i=0; $i < count($_FILES['images']['name']); $i++) {
			$arr_image[$i]['name'] = $_FILES['images']['name'][$i];
			$arr_image[$i]['type'] = $_FILES['images']['type'][$i];
			$arr_image[$i]['tmp_name'] = $_FILES['images']['tmp_name'][$i];
		}
	}
	if(!empty($arr_image)) {
		$tblData = [];
		foreach ($arr_image as $image) {
			$realpath = new \CURLFile(
				$image["tmp_name"],
				$image['type'], 
				$image["name"]
			);
			$file_data = array('file' => $realpath);
			$curl->post('https://app.nanonets.com/api/v2/OCR/Model/'.$data_account['model_id'].'/LabelFile/', $file_data);
			if(!$curl->error){
				$response = toArray($curl->response);
				if(!empty($response['result'])){
					$data_res = !empty($response['result'][0]['prediction']) ? $response['result'][0]['prediction'] : array();
					foreach ($data_res as $k => $v) {
						$id = $v['id'];
						$cells = $v['cells'];
						foreach ($cells as $cell) {
							$tblData[$id][$cell['row']][] = str_replace("$","S",$cell['text']);
						}
					}				
					##	update số lượng			
					require_once(DIR_INCLUDES.'/json_master/autoload.php');
					$cachedFile = DIR_CACHE_JSON.'/account/account.json';
					$number = 0;
					if(@file_exists($cachedFile)){
						$decoder = new Webmozart\Json\JsonDecoder();
						$lst_account = $decoder->decodeFile($cachedFile);
						foreach ($lst_account as $key => $value) {
							if($value['api_key'] == $data_account['api_key']) {
								$lst_account[$key]['model_id']['number'] += 1;
							}
						}
						$encoder = new Webmozart\Json\JsonEncoder();
						$encoder->encodeFile($lst_account, $cachedFile);
					}
				}

			}else{
				echo json_encode(array(
					"result"	=>	false,
					"msg"		=>	"Không thể đọc được file ảnh"
				)); die();
			}
		}
	}		
	#
	$data = $data_row = array();
	if(!empty($tblData)) {
		foreach($tblData as $key => $value) {
			foreach ($value as $val) {
				$data[] = $val;
			}
		}
	}
	$i = -1;
	foreach ($data as $k => $v) {
		if(empty($code_index)) {
			for($i = 0; $i < count($v); $i++) {
				#lấy vị trí ms_code
				if(preg_match('/([A-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠƯỲÝ]+)\d?-\d{2,3}/', $v[$i])) {
					$code_index = $i;
					break;
				}
			}
			#thêm cột giỏ bank
			if(empty($code_index)){
				$data[$k][] = "Giỏ bank";
				$data[$k][] = "Đại lý";
			}
		}
		#thêm cột giỏ bank
		if(!empty($code_index)){
			$stock_code = str_replace("DD","ĐD",$v[$code_index]);
			$stock_code = str_replace("KD","KĐ",$stock_code);
			$stock_code = str_replace("DLBM","ĐLBM",$stock_code);
			$stock_code = str_replace("VTD","VTĐ",$stock_code);
			$stock_code = str_replace("DLBM","ĐLBM",$stock_code);
			$stock_code = str_replace("DLHD","ĐLHĐ",$stock_code);
			$data[$k][$code_index] = $stock_code;
			$data[$k][] = !empty($arr_bank[$stock_code]) ? $arr_bank[$stock_code] : "";
			$data[$k][] = $agentcy_name;
		}		
	}
	
 	$service = new Google_Service_Sheets($client);
	$body = new Google_Service_Sheets_ValueRange([
        'values' => $data
    ]);

    // Thiết lập để không ghi đè dữ liệu
    $params = [
        'valueInputOption' => 'RAW', // Giữ nguyên định dạng dữ liệu
        'insertDataOption' => 'INSERT_ROWS' // Thêm vào các hàng trống tiếp theo
    ];
	$range = "DATA_IMAGE";
    // Thêm dữ liệu vào Google Sheet
//	var_dump($spreadsheetId, $range, $body, $params);die;
    try {
        $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        echo json_encode(array(
			"result"	=>	true,
			"msg"		=>	"Dữ liệu đã được thêm vào sheet DATA_IMAGE "
		));
    } catch (Exception $e) {
		$clsISO->print_pre($e->getMessage());die;
        echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"ERROR!"
		));
    }
}
?>