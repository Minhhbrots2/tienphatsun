<?php 
require_once(dirname(__FILE__).DS.'mod.default.php');
function default_setView(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$clsConfiguration;
	###
	$morning_start = $clsConfiguration->getValue('morning_start');
	$morning_end = $clsConfiguration->getValue('morning_end');
	$afternoon_start = $clsConfiguration->getValue('afternoon_start');
	$afternoon_end = $clsConfiguration->getValue('afternoon_end');
	$assign_list['morning'] = $morning_start." - ". $morning_end;
	$assign_list['afternoon'] = $afternoon_start." - ". $afternoon_end;
	$morning_start  = strtotime(sprintf("%s",date("Y/m/d")." ".$morning_start));
	$morning_end  = strtotime(sprintf("%s",date("Y/m/d")." ".$morning_end));
	$afternoon_start  = strtotime(sprintf("%s",date("Y/m/d")." ".$afternoon_start));
	$afternoon_end  = strtotime(sprintf("%s",date("Y/m/d")." ".$afternoon_end));
	if(time() > $morning_start && time() < $morning_end) {
		$type_default = "morning";
	}else{
		$type_default = "afternoon";
	}
	$type = Input::post('type', $type_default);
	vnSessionSetVar('_ss_view', $type);
	// Return
	echo json_encode([
		"result"	=>	true
	]); die();
}
function default_import_agent(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsAdminLog = new AdminLog();
	$assign_list['clsStock'] = $clsStock;
	$assign_list['clsProperty'] = $clsProperty;
	//	$lstStock = $clsStock->deleteByCond("`project_id`='3' and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date("d/m/Y")."'");
	//	$clsISO->print_pre($lstStock); die();
	$total_stocks  = $total_price_sheets_mis = 0;
	$field = "{$clsProperty->pkey},title,intro,more_information";
	$list_agents = $clsProperty->getAll("`is_trash`=0 and `is_locked`=0 
		and `property_type`='_AGENCY' order by `order_no` ASC", $field);
	//	$clsISO->print_pre($list_agents);die;
	$arr_agent = [];
	$morning_start = $clsConfiguration->getValue('morning_start');
	$morning_end = $clsConfiguration->getValue('morning_end');
	$afternoon_start = $clsConfiguration->getValue('afternoon_start');
	$afternoon_end = $clsConfiguration->getValue('afternoon_end');
	$assign_list['morning'] = $morning_start." - ". $morning_end;
	$assign_list['afternoon'] = $afternoon_start." - ". $afternoon_end;
	##
	$morning_start  = strtotime(sprintf("%s",date("Y/m/d")." ".$morning_start));
	$morning_end  = strtotime(sprintf("%s",date("Y/m/d")." ".$morning_end));
	$afternoon_start  = strtotime(sprintf("%s",date("Y/m/d")." ".$afternoon_start));
	$afternoon_end  = strtotime(sprintf("%s",date("Y/m/d")." ".$afternoon_end));
	##
	if(time() > $morning_start && time() < $morning_end) {
		$type_default = "morning";
	}else{
		$type_default = "afternoon";
	}
	$_ss_view = vnSessionExist('_ss_view') ?  vnSessionGetVar('_ss_view') : $type_default;
	$assign_list['_ss_view'] = $_ss_view;
	if($_ss_view == "morning") {
		$time_start  = $morning_start;
		$time_end  = $morning_end;
	}else{
		$time_start  = $afternoon_start;
		$time_end  = $afternoon_end;
	}
	$lstLog = $clsAdminLog->getAll("`user_id`='{$core->_USER['user_id']}' GROUP BY `target_id` ORDER BY `date` DESC","id,target_id,
		MAX(CASE WHEN `date` BETWEEN {$time_start} AND {$time_end} THEN 1 ELSE 0 END) AS has_update");
	foreach ($lstLog as $key => $value) {
		$arr_agent[$value['target_id']] = [
			"has_update"	=>	$value['has_update']
		];
	}
	if(!empty($list_agents)){
		foreach($list_agents as $key => $val){
			$property_id = $val[$clsProperty->pkey];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$last_cronjob_time = (int) $core->get_field($more_information, 'last_cronjob_time', 0);
			$list_agents[$key]['last_cronjob_time'] = $last_cronjob_time;
			#
			$cron_automation_enable = 0;
			if(isset($more_information['cron_automation_enable'])){
				$cron_automation_enable = $more_information['cron_automation_enable'];
			}
			$more_information['cron_automation_enable'] = $cron_automation_enable;
			$list_agents[$key]['more_information'] = $more_information;
			$list_agents[$key]["has_update"] = !empty($arr_agent[$property_id]) ? $arr_agent[$property_id]["has_update"] : 0;
			#
			$total_price_sheets_mis_in = 0;
			$s_field = "{$clsStock->pkey},more_information";
			// `status_id`='"._STOCK_STATUS_LOCK_ID."' and 
//			$list_stocks = $clsStock->getAll("`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `agency_id`='{$val[$clsProperty->pkey]}'", $s_field);
			if(!empty($list_stocks)){
				$total_stock_in = count($list_stocks);
				foreach($list_stocks as $okey => $oval){
					$more_information = $oval['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					if(isset($more_information['price_sheets']) && !empty($more_information['price_sheets'])){
					} else {
						$total_price_sheets_mis_in += 1;
					}
				}	
			}
			$total_stocks+= $total_stock_in;
			$total_price_sheets_mis+= $total_price_sheets_mis_in;
			$list_agents[$key]['total_stock_in'] = $total_stock_in;
			$list_agents[$key]['total_price_sheets_mis_in'] = $total_price_sheets_mis_in;
		}
		//$last_cronjob_time_arrs = array_column($list_agents, 'last_cronjob_time');
		//array_multisort($last_cronjob_time_arrs, SORT_ASC, $list_agents);
	}
	$assign_list['list_agents'] = $list_agents;
	$assign_list['total_stocks'] = $total_stocks;
	$assign_list['total_price_sheets_mis'] = $total_price_sheets_mis;
	//$clsISO->print_pre($list_agents); die();	
	#reset lượt sử dụng tài khoản nanonet
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/account.json';
	$lst_account = array();
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lst_account = $decoder->decodeFile($cachedFile);
		foreach ($lst_account as $key => $value) {
			$model_id = $value['model_id'];
			if($model_id['upd_date'] <= time()) {
				$model_id['number'] = 0;
				$model_id['date'] = $model_id['upd_date'];
				$model_id['upd_date'] = strtotime("+1 months",$model_id['upd_date']);
				$lst_account[$key]['model_id'] = $model_id;
			}
			unset($model_id);
		}
	}
	$encoder = new Webmozart\Json\JsonEncoder();
	$encoder->encodeFile($lst_account, $cachedFile);
}
function get_files($service, $folderId, $path = '') {
	$resultArray = [];
	$results = $service->files->listFiles([
		'orderBy' => "name",
		'q' => "'".$folderId."' in parents",
		'corpora' => "allDrives",
		'supportsAllDrives' => 'true',
		'includeItemsFromAllDrives' => 'true'
	]);
	foreach ($results->getFiles() as $file) {
		$filePath = $path . '/' . $file->getName();
		$resultArray[$file->getId()] = $filePath;
		if ($file->mimeType == 'application/vnd.google-apps.folder') {
			$resultArray = array_merge($resultArray, get_files($service, $file->getId(), $filePath));
		} 
	} 
	return $resultArray;
}
function default_start_import_agent(){
	//	ini_set('display_errors', '1');
	//	ini_set('display_startup_errors', '1');
	//	error_reporting(E_ALL);
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	#
	$agency_id = (int) Input::post('agency_id', 0);
	$project_admin_id = (int) $core->_USER['project_admin_id'];
	$list_block_admin = $clsISO->getArrayByTextSlash($core->_USER['list_block_admin']);
	$more_information = $clsProperty->getOneField('more_information', $agency_id);
	$more_information = $clsISO->to_array_json($more_information);
	$spreadsheetId = $more_information['spreadsheetId'];
	//$clsISO->print_pre($spreadsheetId); die();
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
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	$range = 'BH'; // here we use the name of the Sheet to get all the rows
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$tblData = $response->getValues();
	##
	$highestColumnIndex = 20;
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$more_information = $core->_USER["more_information"];
	$more_information = $clsISO->to_array_json($more_information);
	$block_permiss = $core->get_field($more_information, "block_permiss", []);
	##
	$txtNote = ""; $arr_project = [];
	if(!empty($block_permiss)) {
		$lstBlockPermiss = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' AND `parent_id`='{$stock_type}' 
		AND `property_id` IN (".implode(',',$block_permiss).")", "{$clsProperty->pkey},`title`,`for_id`");
		if(!empty($lstBlockPermiss)) {
			$txtNote .= "- Bạn chỉ có thể cập nhật được bảng hàng của các căn thuộc các phân khu sau đây: ";
			foreach($lstBlockPermiss as $k => $v) {
				$arr_project[] = $v['for_id'];
				$txtNote .= (($k > 0) ? ", ":"") . "<strong>".$v['title']."</strong>";
			}
		}
	}
//	var_dump($block_permiss);die;
	//	echo $txtNote;die;
	if(!empty($arr_project)) {
		$field = "{$clsProject->pkey},title";
		$list_projects = $clsProject->getAll("is_menu=1 and list_block_type like '%|".$stock_type."|%' and ".$clsProject->pkey." IN (".implode(',',$arr_project).") ", $field);
		$html_project = '';
		if(count($list_projects) > 1) {
			$html_project = '<div class="border radius-half p-2 px-3 mr-2">
				<div class="radio">
					<input type="radio" id="project_all" name="project_id"'.($project_admin_id==0?' checked="checked"':'').' 
						value="0" onchange="$Core.stock.loadBlockU(this,event)" />
					<label for="project_all">Tất cả</label>
				</div>
			</div>';
		}		
		foreach($list_projects as $key => $val){
			$checked = "";
			if(($project_admin_id > 0 && $project_admin_id==$val[$clsProject->pkey]) || (empty($project_admin_id) && $key == 0)){
				$checked = "checked";
			}
			$project_admin_id  = ($project_admin_id > 0) ? $project_admin_id : $val[$clsProject->pkey];
			$html_project.= '<div class="border radius-half p-2 px-3 mr-2">
				<div class="radio">
					<input type="radio" '.$checked.' id="project_'.$val[$clsProject->pkey].'" name="project_id" stock_type="'.$stock_type.'" value="'.$val[$clsProject->pkey].'" onchange="$Core.stock.loadBlockU(this,event)" />
					<label for="project_'.$val[$clsProject->pkey].'">'.$val['title'].'</label>
				</div>
			</div>';
		}
		#block dự án quản lý
		$html_block = '<div class="d-flex align-items-start list_block_user w-100">';
		if($project_admin_id > 0) {
			$lstBlockU = $clsProperty->getAll("`property_type`='_BLOCK' AND `for_id`='".$project_admin_id."' AND `parent_id`='".$stock_type."' AND  property_id IN (".implode(',',$block_permiss).")",$clsProperty->pkey.',title');
			if(!empty($lstBlockU)) {
				$html_block .= '<label class="text-muted text-nowrap mr-2 mt-2">Phân khu:</label>
				<div class="d-flex flex-wrap align-items-start">';
				foreach( $lstBlockU as $k_block => $v_block) {
					$html_block.= '<div class="radius-half p-2 px-3 mr-2">
						<div class="checkbox">
							<input type="checkbox" id="block_'.$v_block[$clsProperty->pkey].'" name="block_id[]" value="'.$v_block[$clsProperty->pkey].'" '.($clsISO->checkItemInArray($v_block[$clsProperty->pkey],$list_block_admin) ? "checked" : "").' />
							<label for="block_'.$v_block[$clsProperty->pkey].'">'.$v_block['title'].'</label>
						</div>
					</div>';	
				}	
				$html_block.= '</div>';				
			}
		}
		$html_block.= '</div>';	
	}else{
		$html_project = '<label class="text-danger mr-2">Bạn chưa được cấp quyền cập nhật bảng hàng</label>';
	}
	$total_records = 0; $uid = $clsISO->getUniqid();
	$html = '<div class="modal-dialog modal-xl">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Import bảng hàng</strong></h3>
		</div>
		<form method="POST">
			<div class="modal-body modal-body-scrollable">
				<div class="d-flex flex-wrap align-items-center mb-3 p-3" style="border: 2px dashed #ff1c00">
					<div class="d-flex flex-wrap align-items-start mb-3">
					<label class="text-muted mr-2 mt-2">Dự án:</label>';
			$html.= $html_project;
		$html.= '</div>'.$html_block.'
			</div>';
		$html.='<div class="alert alert-warning box_notify d-none"><strong>Ghi chú</strong><br /> - Dòng màu vàng(nếu có) là mã căn không tồn tại.<br /> - Dòng màu đỏ(nếu có) là căn bạn không có quyền cập nhật. <br /> '.$txtNote.' 
				<button class="btn btn-icon btn_close_notify bg-warning" type="button" onclick="$Core.stock.closeNotify(this,event)"><i class="fa fa-times" aria-hidden="true"></i></button></div>
				<div style="overflow:auto; width:100%; max-height:500px">
				<table class="table text-nowrap table-bordered table-striped">
					<thead style="position:sticky; top:-1px; background:#FFF">
						<tr><th style="min-width:50px" width="50" class="text-center bg-lighter">No.</th>';	
				for($col=0; $col<$highestColumnIndex; $col++){
					$html.='<th class="p-0" style="min-width:120px" width="120">
						<select name="columns['.$col.']" class="form-control border-0 stock_import_field">
							<option value="">Lựa chọn</option>
							'.$clsStock->getHtmlColumnField($col, $stock_type, $agency_id).'
						</select>
					</th>';
				}
				$html.='</tr></thead>';
				if(!empty($tblData)){
					$tblData = array_values($tblData);
					$total_records = count($tblData);
					$cachedName = sprintf('%s.json', $uid);
					$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
					$encoder = new Webmozart\Json\JsonEncoder();
					$encoder->encodeFile($tblData, $cachedFile); 
					for($i=1; $i<=count($tblData); $i++){
						if(trim($tblData[$i][0]) != "") {
							$oneStock = $clsStock->getByCond("`is_trash`=0 and `ms_code`='".trim($tblData[$i][0])."' 
							and `stock_type`='".$stock_type."'", $clsStock->pkey.',block_id');
							$html.='<tr class="'.(!empty($oneStock)?'':'tr_selected').' " '.(!empty($oneStock) && !in_array($oneStock['block_id'],$block_permiss)?'style="background-color: #F2DEDD !important"':"").'>
								<td class="text-center bg-lighter">'.$i.'</td>';
							for($col=0; $col<$highestColumnIndex; $col++){
								$html.='<td class="text-left">'.(!empty($tblData[$i][$col]) ? $tblData[$i][$col] : "").'</td>';
							}
							$html .='</tr>';
							unset($oneStock);
						}							
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
				$html.= '
				</table>
			</div>
			<div class="modal-footer">
				<div class="d-flex align-items-center justify-content-between">';
				if(!empty($arr_project)) {
					$html.='<div class="p__left">
						<div class="d-flex align-items-center">
							<label class="switch mr-2">
								<input type="checkbox" name="opt_ignore_empty" value="1" checked />
								<span class="slider round"></span>
							</label>
							<span>Bỏ qua giá trị trống</span>
						</div>
					</div>
					<div class="p__right"><button type="button"'.($total_records==0?' disabled':'').' class="btn btn-success" agency_id="'.$agency_id.'"  stock_type="'.$stock_type.'" uid="'.$uid.'" onClick="$Core.stock.do_import_agent(this, event)"><span>Cập nhật bảng hàng</span></button></div>';
				}				
		$html.='</div>
			</div>
		</form>
	</div></div></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_import_agent(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$profile_id,$oneProfile
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$user_id = $core->_USER['user_id'];
	$clsUser = new User();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockLog = new StockLog();
	$clsStockAgent = new StockAgent();
	$clsTmpStockAgent = new TmpStockAgent();
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	$arrayBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	#
	$uid = Input::post('uid');
	$agency_id = (int) Input::post('agency_id', 0);
	$opt_ignore_empty = (int) Input::post('opt_ignore_empty', 1);
	$project_id = (int) Input::post('project_id', 0);
	$arr_blocks_ids = Input::post('block_id', []);
	$stock_type = (int) Input::post('stock_type', 0);
	$stock_type = (!empty($stock_type)) ? $stock_type : _BLOCK_TYPE_HIGHLEVEL_SALE;
	$oneUser = $clsUser->getOne($user_id,"more_information,is_super");
	$more_information_user = $oneUser['more_information'];
	$more_information_user = $clsISO->to_array_json($more_information_user);
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	if(empty($block_permiss)) {
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Tài khoản của bạn chưa được cấp quyền cập nhật bảng hàng"
		));die;
	}
	if(empty($arr_blocks_ids)){
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Bạn chưa chọn phân khu cần cập nhật"
		));die;
	}
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$columns = Input::post('columns', array());
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
		if(!in_array("ms_code", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột mã căn chưa được xác định"
			]); die();
		}
		if($error_field > 0){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Các cột dữ liệu không được trùng nhau"
			]); die();
		}
		$more_information_user['columns'][$agency_id] = $columns;
	}
	#- End require
	$tblData = array();
	$cachedName = sprintf('%s.json', $uid);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
		@unlink($cachedFile);		
	}
	// Cập nhật lần chạy cron cuối cùng
	$more_information = $clsProperty->getOneField("more_information", $agency_id);
	$more_information = $clsISO->to_array_json($more_information);
	$stock_status_id = _STOCK_STATUS_LOCK_ID;
	if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
		$stock_status_id = $more_information['stock_status_id'];
	}
	// Insert Logs
	$clsAdminLog = new AdminLog();
	$clsAdminLog->insertLog('update_stock', _BLOCK_TYPE_HIGHLEVEL_SALE, 
		$project_id, $agency_id, $block_permiss, '_admin');
	$more_information['columns'] = $columns;
	$more_information['last_cronjob_time'] = time();
	$clsProperty->updateOne($agency_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	));
	// End
	$stock_not_upd = $arr_data = $arr_block = $arr_total_stock_sold = $arr_total_stock_upd = $arr_total_stock_new = array();		
	$arr_tmp_stock = $lst_stock_id = $min_max_block = [];
	$total_updated = 0;	
	$sql_string = " AND `is_trash`=0"; $sql_block_permiss = "";
	if($project_id > 0) $sql_string.= " and `project_id`='{$project_id}'";
	if(!empty($arr_blocks_ids)) {
		$sql_block_permiss .= " and `block_id` IN (".implode(",",$arr_blocks_ids).") ";
		foreach ($arr_blocks_ids as $block_id) {
			$arr_tmp_stock[$block_id] = [];
			$lst_stock_id[$block_id] = [];
			$arr_tmp_stock[$block_id] = [];
			/*giá min max*/
			$oneBlock = !empty($arrayBlock[$block_id]) ? $arrayBlock[$block_id] : array();
			$min = $max = "";
			if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
				$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$block_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$block_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
			}
			$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
			$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
			$min_max_block[$block_id] = [
				"min"	=>	$min,
				"max"	=>	$max,
			];
			/*end giá min max*/
		}
	}
	if(!empty($tblData)){
		$total_record = @count($tblData);
		if($total_record > 1){
			$list_stock_ids = $list_stock_agency_ids = array();
			$more_information_user['project'][$project_id] = [
				"project_id"	=>	$project_id,
				"list_block_admin"	=>	$arr_blocks_ids,
			];
			$clsUser->updateOne($core->_USER['user_id'], array(
				'project_admin_id' => $project_id,
				'list_block_admin'	=>	$clsISO->makeSlashListFromArrayRoot($arr_blocks_ids),
				'more_information'	=>	json_encode($more_information_user)
			));
			// Cập nhật các căn thành đã bán
			$ms_code_index = $csbh_index  = $price_sheet_title_index = 
			$price_sheet_link_index = $date_deposit_sign_index = $total_price_vat_index = 
			$total_price_early_index = $DT_TT_index = $total_price_index = $link_video_index = _COLUMN_INDEX_DEF;
			foreach($columns as $key => $p_field){
				if($p_field=='ms_code'){
					$ms_code_index = $key;
				} else if($p_field=='csbh'){
					$csbh_index = $key;
				} else if($p_field=='price_sheet_title'){
					$price_sheet_title_index = $key;
				} else if($p_field == 'price_sheet_link'){
					$price_sheet_link_index = $key;
				} else if($p_field == 'price_sheet_link'){
					$price_sheet_link_index = $key;
				} else if($p_field == 'link_video'){
					$link_video_index = $key;
				} else if($p_field == "total_price") {
					$total_price_index = $key;
				} else if($p_field == "total_price_vat") {
					$total_price_vat_index = $key;
				} else if($p_field == "total_price_early") {
					$total_price_early_index = $key;
				} else if($p_field == "csbh") {
					$csbh_index = $key;
				}
			}
			$list_stock_ids = array();	
			for($i=1; $i<$total_record; $i++){
				if(isset($tblData[$i][$ms_code_index]) && !empty($tblData[$i][$ms_code_index])){
					$ms_code = trim($tblData[$i][$ms_code_index]);
					$oneStock = $clsStock->getByCond("`agency_id`='{$agency_id}' AND `ms_code`='{$ms_code}' 
					AND `stock_type`='".$stock_type."'".$sql_string.$sql_block_permiss, $clsStock->pkey);
					if(!empty($oneStock)) {
						$list_stock_ids[] = $oneStock[$clsStock->pkey];
					}
				}
			}
			// Cập nhật các căn thành đã bán
			$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`,`block_id`,`agency_id`";
			$g_cond = "`agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') 
			and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
			if(!empty($list_stock_ids)){
				$g_cond.= " and `{$clsStock->pkey}` not in(".implode(',', $list_stock_ids).")";
			}
			$list_sold_stocks = $clsStock->getAll($g_cond.$sql_string.$sql_block_permiss, $field);
			// $clsISO->print_pre($list_sold_stocks); die();
			if(!empty($list_sold_stocks)){
				foreach($list_sold_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$m_field = "{$clsStockMeta->pkey},logs";
					$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					if(!empty($oneStockMeta)){
						$logs = $oneStockMeta['logs'];
						$logs = $clsISO->to_array_json($logs);
					} else {
						$clsStockMeta->insert(array(
							'stock_id' => $val[$clsStock->pkey],
							'reg_date' => time(),
							'upd_date' => time()
						));
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
					}
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$more_information['user_id_update_sold'] = $profile_id;
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
					))){
						if(!empty($arr_total_stock_sold[$val["block_id"]])) {
							$arr_total_stock_sold[$val["block_id"]] += 1;
						}else{
							$arr_total_stock_sold[$val["block_id"]] = 1;
						}
						if(!empty($oneStockMeta)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					}
				}
				unset($list_sold_stocks);
			}
			$min_def = 1000000000;
			$max_def = 50000000000;
			for($i=1; $i<$total_record; $i++){
				if(isset($tblData[$i][$ms_code_index]) && !empty($tblData[$i][$ms_code_index])){
					$ms_code = trim($tblData[$i][$ms_code_index]); $opt_price = 0;
					$csbh = isset($tblData[$i][$csbh_index]) && !empty($tblData[$i][$csbh_index]) 
						? trim($tblData[$i][$csbh_index]) : "";
					$date_deposit_sign = isset($tblData[$i][$date_deposit_sign_index]) && !empty($tblData[$i][$date_deposit_sign_index]) 
						? trim($tblData[$i][$date_deposit_sign_index]) : "";
					$price_sheet_title = isset($tblData[$i][$price_sheet_title_index]) && !empty($tblData[$i][$price_sheet_title_index]) 
						? trim($tblData[$i][$price_sheet_title_index]) : "PTG TẠM TÍNH";
					$price_sheet_link = isset($tblData[$i][$price_sheet_link_index]) && !empty($tblData[$i][$price_sheet_link_index]) 
						? trim($tblData[$i][$price_sheet_link_index]) : "";
					$link_video = $core->get_field($tblData[$i], $link_video_index, "");
					// $dbconn->debug = true;
					$oneStock = $clsStock->getByCond("1=1 {$sql_string} and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
					and `ms_code`='{$ms_code}' {$sql_block_permiss}", $field);
					if(!empty($oneStock)){
						/*giá min max*/
						$min = !empty($min_max_block[$oneStock["block_id"]]["min"]) ? $min_max_block[$oneStock["block_id"]]["min"] : $min_def;
						$max = !empty($min_max_block[$oneStock["block_id"]]["max"]) ? $min_max_block[$oneStock["block_id"]]["max"] : $max_def;
						/*end giá min max*/
						$uid = $clsISO->getUniqid();
						$upd_field = $logs = $more_information = array();
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
						$more_information = $oneStock['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						foreach($columns as $key => $p_field){
							$tblData[$i]['agency_id'] = $agency_id;
							$tblData[$i]['status_id'] = $stock_status_id;
							if(!empty($p_field)){
								if($p_field=='block_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='building_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BUILDING' 
									and (property_code='".$tblData[$i][$key]."' or slug='".$core->replaceSpace($tblData[$i][$key])."')");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='view_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_VIEW' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'", $clsProperty->pkey);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : $stock_status_id;
								} else if($p_field=='type_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE' 
									and (`property_code`'".$tblData[$i][$key]."' or slug='".$core->replaceSpace($tblData[$i][$key])."')");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='bedroom_id' && !empty($tblData[$i][$key])){
									$tblData[$i][$key] = preg_replace('/\s+/', '', $tblData[$i][$key]);
									$tmp = $clsProperty->getByCond("`property_type`='_BEDROOM' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='home_direction_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='agency_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("is_trash=0 and property_type='_AGENCY' 
									and (`property_code`='".$tblData[$i][$key]."' 
									or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $clsProperty->pkey);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : $agency_id;
								}
								$upd_field['agency_id'] = $agency_id;
								$upd_field['status_id'] = $stock_status_id;
								$more_information['agency_id'] = $agency_id;
								$more_information['status_id'] = $stock_status_id;
								$more_information['link_video'] = $link_video;
								###
								$sheets = $more = array();
								$price_sheets = $core->get_field($more_information, 'price_sheets', []);
								if(!empty($price_sheet_title) && !empty($price_sheet_link)){
									$sheets[$clsISO->getUniqid()] = array(
										'title' => $price_sheet_title,
										'image' => $price_sheet_link
									);
								}
								if(!empty($sheets)){
									$price_sheets = array();
									$price_sheet_id = $clsISO->getUniqid();
									$price_sheets[$price_sheet_id]['sheets'] = $sheets;
									$price_sheets[$price_sheet_id]['reg_date'] = time();
									$price_sheets[$price_sheet_id]['upd_date'] = time();
									$price_sheets[$price_sheet_id]['csbh'] = $csbh;
									$price_sheets[$price_sheet_id]['user_id'] = $core->_USER['user_id'];
									$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
								}
								$more_information['price_sheets']= $price_sheets;
								if(!in_array($p_field, array('ms_code','DT_Tim','DT_TT','total_price','total_price_vat','sale_bonus'
								,'total_price_early','total_price_progress','total_price_bank','total_price_bank_half','csbh'
								,'price_sheet_title','price_sheet_link','date_deposit_sign','first_payment_amount','contract_sign_type','stock_dq')) 
									&& !empty($tblData[$i][$key])){
									$upd_field[$p_field] = trim($tblData[$i][$key]);
									$more_information[$p_field] = $tblData[$i][$key];
									if(in_array($p_field, ['agency_id','status_id'])){
										if($oneStock[$p_field] != trim($tblData[$i][$key])){
											$logs[$clsISO->getUniqid()] = array(
												'field' => $p_field,
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_id' => $oneStock[$p_field],
												'to_id' => trim($tblData[$i][$key])
											);
										}
									}
								} else {
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_half'))){
										$price_old = $more_information[$p_field];
										if($opt_ignore_empty == 0 && empty($tblData[$i][$key])){
											$more_information[$p_field]= 0;
										} else if(!empty($tblData[$i][$key])){
											$p_value = $clsISO->processSmartNumber($tblData[$i][$key]);
											$p_value = $clsISO->convertPriceShortToFullUpdate($p_value,$min,$max);
											$more_information[$p_field] = $p_value;
											${$p_field} = $p_value;
											if($price_old != $p_value){
												$logs[$clsISO->getUniqid()] = array(
													'field' => $p_field,
													'reg_date' => time(),
													'user_id' => $core->_USER['user_id'],
													'from_value' => $price_old,
													'to_value' => $p_value
												);
											}
										}
									} else {
										if($p_field=='date_deposit_sign' && !empty($date_deposit_sign)){
											$more_information[$p_field] = $tblData[$i][$key];
										} else {
											$more_information[$p_field] = $tblData[$i][$key];
										}
									}
								}
								if(isset($tblData[$i][$DT_TT_index]) && !empty($tblData[$i][$DT_TT_index])){
									$DT_TT = $tblData[$i][$DT_TT_index];
									$upd_field['DT_TT'] = $clsISO->toNumber(trim($tblData[$i][$key]));
								}
								if(isset($tblData[$i][$total_price_vat_index]) && !empty($tblData[$i][$total_price_vat_index])){
									$total_price_vat = $tblData[$i][$total_price_vat_index];
									$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
									$total_price_vat = $clsISO->convertPriceShortToFullUpdate($total_price_vat,$min,$max);
									$upd_field['total_price_vat'] = $total_price_vat;
								} else if(isset($tblData[$i][$total_price_index]) && !empty($tblData[$i][$total_price_index])){
									$total_price = $tblData[$i][$total_price_index];
									$total_price = $clsISO->processSmartNumber($total_price);
									$total_price = $clsISO->convertPriceShortToFullUpdate($total_price,$min,$max);
									$total_price_vat = $total_price * _PERCENT_PRICE_VAT;
									$total_price_vat = round($total_price_vat);
									$upd_field['total_price_vat'] = $total_price_vat;
									$more_information["total_price_vat"] = $total_price_vat;
								} else if(isset($tblData[$i][$total_price_early_index]) && !empty($tblData[$i][$total_price_early_index])){
									$total_price_early = $tblData[$i][$total_price_early_index];
									$total_price_early = $clsISO->processSmartNumber($total_price_early);
									$total_price_early = $clsISO->convertPriceShortToFullUpdate($total_price_early,$min,$max);
									$upd_field['total_price_vat'] = $total_price_early;
								} else if($opt_ignore_empty==0){
									$upd_field['total_price_vat'] = 0;
									$more_information['total_price_vat'] = 0;
								}
							}
						}
						#- End Logs
						$upd_field['upd_date'] = time();
						$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						// $clsISO->print_pre($total_updated); die();
						
						if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
							++$total_updated;
							if(!empty($oneStockMeta) && !empty($logs)){
								$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
									'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'upd_date' => time()
								));	
							}
							if($oneStock["agency_id"] == 0) {
								if(!empty($arr_total_stock_new[$oneStock["block_id"]])) {
									$arr_total_stock_new[$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_new[$oneStock["block_id"]] = 1;
								}
							}else{
								if(!empty($arr_total_stock_upd[$oneStock["block_id"]])) {
									$arr_total_stock_upd[$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_upd[$oneStock["block_id"]] = 1;
								}
							}
						} 
						$arr_data[$oneStock["block_id"]][] = [
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
						$arr_tmp_stock[$oneStock["block_id"]][] = $ms_code;
						$lst_stock_id[$oneStock["block_id"]][] = $oneStock[$clsStock->pkey];
					} else {
						$stock_not_upd[] = $ms_code;
					}
				}
			}
			if($total_updated > 0) {				
				#activity log		
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Stock","update");
			}
			$clsLogCrawl = new LogCrawl();
			foreach ($arr_blocks_ids as $block_id) {
				$total_stock_sold = !empty($arr_total_stock_sold[$block_id]) ? $arr_total_stock_sold[$block_id] : 0;
				$total_stock_new = !empty($arr_total_stock_new[$block_id]) ? $arr_total_stock_new[$block_id] : 0;
				$total_updated_block = !empty($arr_total_stock_upd[$block_id]) ? $arr_total_stock_upd[$block_id] : 0;
				$arr_data_upd = array(
					"total_stock_sold"	=>	$total_stock_sold,
					"total_stock_new"	=>	$total_stock_new,
					"total_stock"	=>	$total_updated_block,
					"stock_not_upd"	=>	$stock_not_upd,
					"data_log"	=>	!empty($arr_data[$block_id]) ? $arr_data[$block_id] : array(),
					"title_log"	=>	'Tổng quỹ: '.$total_updated_block.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
					"type"	=>	0,	//0:tổng hợp,1:drive,2:hình ảnh-copy,
					"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
				);
				$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
			}
		} else {
			$field = "{$clsStock->pkey},`status_id`,`more_information`,`block_id`,`ms_code`";
			$list_stocks = $clsStock->getAll("`is_trash`=0 and `agency_id`='{$agency_id}' 
				and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' ".$sql_string, $field);
			if(!empty($list_stocks)){
				$total_updated = 0;
				foreach($list_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					##
					$m_field = "{$clsStockMeta->pkey}";
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
					$logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(), 
						'user_id' => $core->_USER['user_id'],
						'from_id' => $val['status_id'],
						'to_id' => _STOCK_STATUS_SOLD_ID,
						'field' => 'status_id'
					);
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$more_information['user_id_update_sold'] = $profile_id;
					if($clsStock->updateOne($val[$clsStock->pkey], array(
						'ms_date' => time(),
						'upd_date' => time(),
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))) {
						$total_updated = 1;
						if(!empty($oneStockMeta)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
						if(!empty($arr_total_stock_sold[$oneStock["block_id"]])) {
							$arr_total_stock_sold[$oneStock["block_id"]] += 1;
						}else{
							$arr_total_stock_sold[$oneStock["block_id"]] = 1;
						}
					}
				}
				if($total_updated > 0) {				
					#activity log		
					$clsActivityLog = new ActivityLog();
					$log = $clsActivityLog->addActivityLog("Stock","update");
				}
				##
				$clsLogCrawl = new LogCrawl();
				foreach ($arr_blocks_ids as $block_id) {
					$total_stock_sold = !empty($arr_total_stock_sold[$block_id]) ? $arr_total_stock_sold[$block_id] : 0;
					$total_stock_new = !empty($arr_total_stock_new[$block_id]) ? $arr_total_stock_new[$block_id] : 0;
					$total_updated_block = !empty($arr_total_stock_upd[$block_id]) ? $arr_total_stock_upd[$block_id] : 0;
					$arr_data_upd = array(
						"total_stock_sold"	=>	$total_stock_sold,
						"total_stock_new"	=>	$total_stock_new,
						"total_stock"	=>	$total_updated_block,
						"stock_not_upd"	=>	$stock_not_upd,
						"data_log"	=>	!empty($arr_data[$block_id]) ? $arr_data[$block_id] : array(),
						"title_log"	=>	'Tổng quỹ: '.$total_updated_block.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
						"type"	=>	0,	//0:tổng hợp,1:drive,2:hình ảnh-copy,
						"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
				}
				unset($tmp);
			}
		}
	}
	#luu bang tam
	$logs_field = "{$clsStockLog->pkey},`more_information`";
	foreach ($arr_tmp_stock as $block_id => $ms_codes) {	
		$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);
		// Start Logs 
		$stock_ids = !empty($lst_stock_id[$block_id]) ? $lst_stock_id[$block_id] : array();
		$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
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
				'user_id' => $core->_USER['user_id']
			));
		}
		/** End */
	}			
	#tong hop quy dai ly
	if($agency_id == _AGENCY_FH_ID) {
		$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);	
	}
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'total_updated' => $total_updated
	)); die();
}
function default_start_copy_agent(){
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
	$project_admin_id = (int) $core->_USER['project_admin_id'];
	$more_information = $clsProject->getOneField('more_information', $project_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$spreadsheetId = $more_information['spreadsheetId'];
	$highestColumnIndex = 16;
	$data = $data_row = array();
	$dataHead = [];
	for($i=0; $i<$highestColumnIndex; $i++) {
		$data[] = "";
		$dataHead[] = [
			"type"	=>	"text",
			"title"	=>	'',
			"width"	=>	120
		];
	}
	for($i=0; $i<30; $i++) {
		$data_row[] = $data;
	}
	$arr_head = [];
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_menu=1 and list_block_type like '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%'", $field);
	$html = '<div class="modal-dialog modal-xl" style="width:calc(100vw - 100px)">
	<form class="modal-content" method="POST">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Import bảng hàng '.$clsProperty->getTitle($agency_id).'</strong></h3>
		</div>
		<div class="modal-body modal-body-scrollable">
			<div class="d-flex align-items-center mb-2">
				<label class="text-muted mr-2">Dự án:</label>
				<div class="border radius-half p-2 px-3 mr-2">
					<div class="radio">
						<input type="radio" id="project_all" name="project_id"'.($project_admin_id==0?' checked="checked"':'').' value="0" />
						<label for="project_all">Tất cả</label>
					</div>
				</div>';
				foreach($list_projects as $key => $val){
					$html.= '<div class="border radius-half p-2 px-3 mr-2">
						<div class="radio">
							<input type="radio"'.($project_admin_id==$val[$clsProject->pkey]?' checked="checked"':'').' id="project_'.$val[$clsProject->pkey].'" name="project_id" value="'.$val[$clsProject->pkey].'" />
							<label for="project_'.$val[$clsProject->pkey].'">'.$val['title'].'</label>
						</div>
					</div>';
				}
		$html.= '</div>
		<table class="table table-bordered table-fixed table-pill table-striped mb-0" style="position:sticky;top:-16px;background:#FFF;z-index:9">
				<thead><tr>
				<th style="min-width:50px" width="50" class="text-center">No.</th>';
				for($col=0; $col<$highestColumnIndex; $col++){
					$html.='<th class="p-0" style="min-width:120px" width="120">
						<select name="columns['.$col.']" class="form-control border-0 stock_import_field">
							<option value="">Lựa chọn</option>
							'.$clsStock->getHtmlColumnField($col, $stock_type, $agency_id).'
						</select>
					</th>';
				}
				$html .= '</tr></thead>
			</table>
			<div id="spreadsheet_'.$uid.'" class="spreadsheet_hide_head"></div>
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
					<button type="button" class="btn btn-success" uid="'.$uid.'" agency_id="'.$agency_id.'" project_id="'.$project_id.'" stock_type="'.$stock_type.'" onClick="$Core.stock.do_copy_agent(this, event)"><span>Cập nhật bảng hàng</span></button>
				</div>
			</div>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'data'	=>	$data_row,
		'dataHead'	=>	$dataHead
	)); die();
}
function default_start_import_stock(){
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
	$project_admin_id = (int) $core->_USER['project_admin_id'];
	$list_block_admin = $clsISO->getArrayByTextSlash($core->_USER['list_block_admin']);
	if($type == "_IMAGE") {
		$data_account = $clsStock->getDataAccount();
		if($data_account["api_key"] == "" || $data_account["model_id"] == "") {
			echo json_encode(array(
				"result"	=>	false,
				"msg"		=>	"Không thể đọc được file ảnh"
			)); die();
		}
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
					require_once(DIR_INCLUDES.'/json_master/autoload.php');
					$cachedFile = DIR_CACHE_JSON.'/account/account.json';
					if(@file_exists($cachedFile)){
						$decoder = new Webmozart\Json\JsonDecoder();
						$lst_account = $decoder->decodeFile($cachedFile);
						foreach ($lst_account as $key => $val) {
							if($val["api_key"] == $data_account["api_key"]) {
								$lst_account[$key]["model_id"]["is_use"] = 0;
								break;
							}
						}
						$encoder = new Webmozart\Json\JsonEncoder();
						$encoder->encodeFile($lst_account, $cachedFile);
					}
					echo json_encode(array(
						"result"	=>	false,
						"msg"		=>	"Không thể đọc được file ảnh"
					)); die();
				}
			}
		}
		$uid = $clsISO->getUniqid();
		$agency_id = (int) Input::post('agency_id', 0);
		$project_id = (int) Input::post('project_id', 0);
		$more_information = $clsProject->getOneField('more_information', $project_id);
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		$spreadsheetId = $more_information['spreadsheetId'];
		$data = $data_row = array();
		if(!empty($tblData)) {
			foreach($tblData as $key => $value) {
				foreach ($value as $val) {
					$data[] = $val;
				}
			}
		}
		$highestColumnIndex = (count($data[0]) < 16) ? 16 : count($data[0]);
		if(count($data[0]) < $highestColumnIndex) {
			foreach ($data as $k => $val) {
				for($i = count($val); $i <= $highestColumnIndex; $i++) {
					$val[$i] = "";
				}
				$data[$k] = $val;
			}
		}
		$highestColumnIndex = count($data[0]) + 1;
		$dataHead = [];
		for($i=0; $i<$highestColumnIndex; $i++) {
			$data_null[] = "";
			$dataHead[] = [
				"type"	=>	"text",
				"title"	=>	'',
				"width"	=>	120
			];
		}
		for($i=0; $i<200; $i++) {
			$data_row[] = (!empty($data[$i])) ? $data[$i] : $data_null;
		}
	}else{		
		$highestColumnIndex = 16;
		$data = $data_row = array();
		$dataHead = [];
		for($i=0; $i<$highestColumnIndex; $i++) {
			$data[] = "";
			$dataHead[] = [
				"type"	=>	"text",
				"title"	=>	'',
				"width"	=>	120
			];
		}				
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$cachedName = sprintf('%s_%s.json', $project_admin_id,$agency_id);
		$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$data_row = $decoder->decodeFile($cachedFile);
		}else{
			for($i=0; $i<50; $i++) {
				$data_row[] = $data;
			}
		}
	}
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	# ghi chu 
	$more_information_user = $clsISO->to_array_json($core->_USER["more_information"]);
	$block_permiss = !empty($more_information_user['block_permiss']) ? $more_information_user['block_permiss'] : array();
	$arr_project = [];
	if(!empty($block_permiss)) {
		$lstBlockPermiss = $clsProperty->getAll("`property_type`='_BLOCK' AND `parent_id`='".$stock_type."' AND `property_id` IN (".implode(',',$block_permiss).")",$clsProperty->pkey.",title,for_id");
		if(!empty($lstBlockPermiss)) {
			foreach ($lstBlockPermiss as $k => $v) {
				$arr_project[] = $v['for_id'];
			}
		}
	}
	if(!empty($arr_project)) {
		$field = "{$clsProject->pkey},title";
		$list_projects = $clsProject->getAll("is_menu=1 and list_block_type like '%|".$stock_type."|%' and ".$clsProject->pkey." IN (".implode(',',$arr_project).") ", $field);
		$html_project = '';
		if(count($list_projects) > 1) {
			$html_project = '<div class="border radius-half p-2 px-3 mr-2">
					<div class="radio">
						<input type="radio" id="project_all" name="project_id"'.($project_admin_id==0?' checked="checked"':'').'stock_type="'.$stock_type.'" value="0" onchange="$Core.stock.loadBlockU(this,event)" />
						<label for="project_all">Tất cả</label>
					</div>
				</div>';
		}		
		foreach($list_projects as $key => $val){
			$checked = "";
			if(($project_admin_id > 0 && $project_admin_id==$val[$clsProject->pkey]) || ($key == 0)){
				$checked = "checked";
			}
			$html_project.= '<div class="border radius-half p-2 px-3 mr-2">
				<div class="radio">
					<input type="radio" '.$checked.' id="project_'.$val[$clsProject->pkey].'" name="project_id" stock_type="'.$stock_type.'" value="'.$val[$clsProject->pkey].'" onchange="$Core.stock.loadBlockU(this,event)" />
					<label for="project_'.$val[$clsProject->pkey].'">'.$val['title'].'</label>
				</div>
			</div>';
		}
		#block dự án quản lý	
		$cond_blockU = "`property_type`='_BLOCK' AND `parent_id`='".$stock_type."' AND  property_id IN (".implode(',',$block_permiss).")";
		if($project_admin_id > 0) {
			$cond_blockU .= " AND `for_id`='".$project_admin_id."' ";
		}
		$html_block = '<div class="d-flex align-items-start list_block_user w-100">';
		$lstBlockU = $clsProperty->getAll($cond_blockU,$clsProperty->pkey.',title');
		if(!empty($lstBlockU)) {
			$html_block .= '<label class="text-muted text-nowrap mr-2 mt-2">Phân khu:</label>
			<div class="d-flex flex-wrap align-items-start">';
			foreach( $lstBlockU as $k_block => $v_block) {
				$html_block.= '<div class="radius-half p-2 px-3 mr-2">
					<div class="checkbox">
						<input type="checkbox" id="block_'.$v_block[$clsProperty->pkey].'" name="block_id[]" value="'.$v_block[$clsProperty->pkey].'" '.($clsISO->checkItemInArray($v_block[$clsProperty->pkey],$list_block_admin) ? "checked" : "").' />
						<label for="block_'.$v_block[$clsProperty->pkey].'">'.$v_block['title'].'</label>
					</div>
				</div>';	
			}	
			$html_block.= '</div>';				
		}
		$html_block.= '</div>';	
	}else{
		$html_project = '<label class="text-danger mr-2">Bạn chưa được cấp quyền cập nhật bảng hàng</label>';
	}
	#
	$more_information = $clsProject->getOneField('more_information', $project_id);
	$more_information = $clsISO->to_array_json($more_information);
	$spreadsheetId = $more_information['spreadsheetId'];
	$arr_head = [];
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_menu=1 and list_block_type like '%|".$stock_type."|%'", $field);
	$html = '<div class="modal-dialog modal-xl" style="width:calc(100vw - 100px)">
	<form class="modal-content" method="POST">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Import bảng hàng '.$clsProperty->getTitle($agency_id).'</strong></h3>
		</div>
		<div class="modal-body modal-body-scrollable">
			<div class="d-flex flex-wrap align-items-center mb-3 p-3" style="border: 2px dashed #ff1c00">
					<div class="d-flex flex-wrap align-items-start mb-3">
					<label class="text-muted mr-2 mt-2">Dự án:</label>';
			$html.= $html_project;
		$html.= '</div>'.$html_block.'</div>';
		$html.='<table class="table table-bordered table-fixed table-pill table-striped mb-0" style="position:sticky;top:-16px;background:#FFF;z-index:9">
				<thead><tr>
				<th style="min-width:50px" width="50" class="text-center">No.</th>';
				for($col=0; $col<$highestColumnIndex; $col++){
					$html.='<th class="p-0" style="min-width:120px" width="120">
						<select name="columns['.$col.']" class="form-control border-0 stock_import_field">
							<option value="">Lựa chọn</option>
							'.$clsStock->getHtmlColumnField($col, $stock_type, $agency_id).'
						</select>
					</th>';
				}
				$html .= '</tr></thead>
			</table>
			<div id="spreadsheet_'.$uid.'" class="spreadsheet_hide_head"></div>
		</div>
		<div class="modal-footer">
			<div class="d-flex align-items-center justify-content-between">';
			if(!empty($arr_project)) {
				$html.='<div class="d-flex align-items-center gap-2">
					<div class="d-flex align-items-center">
						<label class="switch mr-2">
							<input type="checkbox" name="opt_ignore_empty" value="1" checked />
							<span class="slider round"></span>
						</label>
						<span>Bỏ qua giá trị trống</span>
					</div>
					<div class="d-flex align-items-center">
						<label class="switch mr-2">
							<input type="checkbox" name="opt_update_ptg_only" value="1" />
							<span class="slider round"></span>
						</label>
						<span>Chỉ cập nhật PTG</span>
					</div>
				</div>
				<div class="p__right"><button type="button" class="btn btn-success" uid="'.$uid.'" agency_id="'.$agency_id.'" project_id="'.$project_id.'" stock_type="'.$stock_type.'" onClick="$Core.stock.do_copy_agent(this, event)"><span>Cập nhật bảng hàng</span></button></div>';
			}				
	$html.='</div>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid,
		'html' => $html,
		'data'	=>	$data_row,
		'dataHead'	=>	$dataHead
	)); die();
}
function default_do_copy_agent(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$dbconn,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsUser = new User();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProperty = new Property();
	$clsStockAgent = new StockAgent();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockLog = new StockLog();
	###
	$uid = Input::post('uid');
	$agency_id = (int) Input::post('agency_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$block_ids = Input::post('block_id', []);
	$opt_over = Input::post('opt_over','Update');
	$opt_fund = (int) Input::post('opt_fund', 0);
	$opt_ignore_empty = (int) Input::post('opt_ignore_empty', 0);
	$opt_update_ptg_only = (int) Input::post('opt_update_ptg_only', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$columns = Input::post('columns', array());
	$tblData = Input::post('tblData',array());
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
	$arrayBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
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
		if(!in_array("ms_code", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột mã căn chưa được xác định"
			]);
			die();
		}
	}
	$user_id = $core->_USER['user_id'];
	$oneUser = $clsUser->getOne($user_id,"more_information,is_super");
	$more_information_user = $clsISO->to_array_json($oneUser['more_information']);
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	//	$clsISO->print_pre($more_information_user);die;
	if(empty($block_permiss)) {
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Tài khoản của bạn chưa được cấp quyền cập nhật bảng hàng"
		));die;
	}
	if(empty($block_ids)){
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Bạn chưa chọn phân khu cần cập nhật"
		));die;
	}
	// Chỉ cập nhật PTG
	$stock_not_upd = $arr_data = $arr_block = $arr_total_stock_sold = $arr_total_stock_upd = $arr_total_stock_new = array();
	if($opt_update_ptg_only == 1){
		if($error_field == 0){
			$total_updated = 0;
			if(!empty($tblData)){
				$sql_block_permiss = "";
				if(!empty($block_ids)) {
					$sql_block_permiss .= " AND `block_id` IN (".implode(",",$block_ids).")";
				}
				$total_record = count($tblData);
				for($i=0; $i<$total_record; $i++){
					$ms_code_index = $csbh_index = $price_sheet_title_index = $price_sheet_link_index = 0;
					foreach($columns as $key => $p_field){
						if($p_field == 'ms_code'){
							$ms_code_index = $key;
						} else if($p_field=='csbh'){
							$csbh_index = $key;
						} else if($p_field == 'price_sheet_title'){
							$price_sheet_title_index = $key;
						} else if($p_field == 'price_sheet_link'){
							$price_sheet_link_index = $key;
						}
					}
					$ms_code = str_replace(" ","",$tblData[$i][$ms_code_index]);
					$arr_ms_code[] = $ms_code;
					$csbh = $csbh_index > 0 && isset($tblData[$i][$csbh_index]) ? trim(tblData[$i][$csbh_index]) : "";
					$price_sheet_title = $price_sheet_title_index > 0 && isset($tblData[$i][$price_sheet_title_index]) 
						? $tblData[$i][$price_sheet_title_index] : "PTG TẠM TÍNH";
					$price_sheet_link = $price_sheet_link_index > 0 && isset($tblData[$i][$price_sheet_link_index]) 
						? $tblData[$i][$price_sheet_link_index] : "";
					if(empty($ms_code)) continue;
					$field = "{$clsStock->pkey},`logs`,`more_information`,`status_id`,`agency_id`,`block_id`";
					$oneStock = $clsStock->getByCond("`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
						AND `agency_id`='{$agency_id}' AND `ms_code`='{$ms_code}'".$sql_block_permiss, $field);
					if(!empty($oneStock)){
						$more_information = $oneStock['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						##
						$sheets = array();
						$price_sheets = $core->get_field($more_information, 'price_sheets', []);
						if(!empty($price_sheet_title) && !empty($price_sheet_link)){
							$sheets[$clsISO->getUniqid()] = array(
								'title' => $price_sheet_title,
								'image' => $price_sheet_link
							);
						}
						if(!empty($sheets)){
							$price_sheet_id = "";
							$more_information['hide_price_sheets'] = 0;
							if(!empty($price_sheets) && !empty($csbh)){
								foreach($price_sheets as $okey => $oval){
									if(isset($oval['csbh']) && $oval['csbh'] == $csbh){
										$price_sheet_id = $okey;
									}
								}
							}
							if(!empty($price_sheet_id)){
								$price_sheets[$price_sheet_id]['sheets'] = $sheets;
								$price_sheets[$price_sheet_id]['upd_date'] = time();
								$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
							} else {
								$price_sheet_id = $clsISO->getUniqid();
								$price_sheets[$price_sheet_id]['sheets'] = $sheets;
								$price_sheets[$price_sheet_id]['reg_date'] = time();
								$price_sheets[$price_sheet_id]['upd_date'] = time();
								$price_sheets[$price_sheet_id]['csbh'] = $csbh;
								$price_sheets[$price_sheet_id]['user_id'] = $core->_USER['user_id'];
								$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
							}
						}
						$more_information['price_sheets']= $price_sheets;
						// $clsISO->print_pre($more_information); die();
						if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
						))){
							++$total_updated;
							if($oneStock["agency_id"] == 0) {
								if(!empty($arr_total_stock_new[$oneStock["block_id"]])) {
									$arr_total_stock_new[$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_new[$oneStock["block_id"]] = 1;
								}
							}else{
								if(!empty($arr_total_stock_upd[$oneStock["block_id"]])) {
									$arr_total_stock_upd[$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_upd[$oneStock["block_id"]] = 1;
								}
							}
						}
						$arr_data[$oneStock["block_id"]][] = [
							"ms_code"	=>	 $ms_code,
							"csbh"=> $csbh,
							"price_sheet_link"=> $price_sheet_link,
							"stock_id"=> $oneStock[$clsStock->pkey],
						];
					}else{
						$stock_not_upd[] = $ms_code;
					}
				}
			}
			if($total_updated > 0) {				
				#activity log		
				$clsActivityLog = new ActivityLog();
				$clsActivityLog->addActivityLog("Stock","update");				
				$clsLogCrawl = new LogCrawl();
				foreach ($block_ids as $block_id) {
					$total_stock_sold = !empty($arr_total_stock_sold[$block_id]) ? $arr_total_stock_sold[$block_id] : 0;
					$total_stock_new = !empty($arr_total_stock_new[$block_id]) ? $arr_total_stock_new[$block_id] : 0;
					$total_updated_block = !empty($arr_total_stock_upd[$block_id]) ? $arr_total_stock_upd[$block_id] : 0;
					$arr_data_upd = array(
						"total_stock_sold"	=>	$total_stock_sold,
						"total_stock_new"	=>	$total_stock_new,
						"total_stock"	=>	$total_updated_block,
						"stock_not_upd"	=>	$stock_not_upd,
						"data_log"	=>	!empty($arr_data[$block_id]) ? $arr_data[$block_id] : array(),
						"title_log"	=>	'Tổng quỹ: '.$total_updated_block.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
						"type"	=>	2,	//0:tổng hợp,1:drive,2:hình ảnh-copy,
						"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
				}
			}
			// Return
			echo json_encode(array(
				'result' =>	true,
				'msg' => '_success',
				'total_updated' => $total_updated
			));	die();
		} else {
			echo json_encode(array(
				'result'	=>	false,
				'msg' => 'error_field',
				'total_updated' => $total_updated
			));	die();
		}
	} else {
		#luu cache import
		$cachedName = sprintf('%s_%s.json', $project_id, $agency_id);
		$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($tblData, $cachedFile); 
		// Cập nhật lần chạy cron cuối cùng
		$more_information = $clsProperty->getOneField("more_information", $agency_id);
		$more_information = $clsISO->to_array_json($more_information);
		$stock_status_id = _STOCK_STATUS_LOCK_ID;
		if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
			$stock_status_id = $more_information['stock_status_id'];
		}
		$more_information['columns'] = $columns;
		$more_information['last_cronjob_time'] = time();
		// Cập nhật cấu hình cột
		$clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
		// Cập nhật dự án quản lý
		$usr_information = $core->_USER['more_information'];
		$usr_information = $clsISO->to_array_json($usr_information);
		$usr_information['project'][$project_id] = [
			"project_id"	=>	$project_id,
			"list_block_admin"	=>	$block_id,
		];
		$clsUser->updateOne($core->_USER['user_id'], array(
			'project_admin_id' => $project_id,
			'list_block_admin'		=>	$clsISO->makeSlashListFromArrayRoot($block_id),
			'more_information'	=>	json_encode($usr_information)
		));
		// Insert Logs
		$clsAdminLog = new AdminLog();
		$clsAdminLog->insertLog('update_stock', $stock_type, $project_id, $agency_id, $block_id, "_admin");
		if($error_field == 0){					
			$arr_tmp_stock = $lst_stock_id = [];
			foreach ($block_ids as $block_id) {
				$arr_tmp_stock[$block_id] = [];
				$lst_stock_id[$block_id] = [];
				/*giá min max*/
				$oneBlock = !empty($arrayBlock[$block_id]) ? $arrayBlock[$block_id] : array();
				$min = $max = "";
				if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
					$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$block_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$block_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
				}
				$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
				$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
				$min_max_block[$block_id] = [
					"min"	=>	$min,
					"max"	=>	$max,
				];
				/*end giá min max*/
			}
			$total_updated = 0;
			if(!empty($tblData)){
				$total_record = count($tblData);	
				/** Define column */
				$ms_code_index = $csbh_index = $total_price_index = $DT_TT_index = _COLUMN_INDEX_DEF;
				$total_price_vat_index = $date_deposit_sign_index = $total_price_early_index = _COLUMN_INDEX_DEF;
				$price_sheet_title_index = $price_sheet_link_index = _COLUMN_INDEX_DEF;
				foreach($columns as $key => $p_field){
					if($p_field=='ms_code'){
						$ms_code_index = $key;
					} else if($p_field=='csbh'){
						$csbh_index = $key;
					} else if($p_field=='price_sheet_title'){
						$price_sheet_title_index = $key;
					} else if($p_field == 'price_sheet_link'){
						$price_sheet_link_index = $key;
					} else if($p_field == 'date_deposit_sign'){
						$date_deposit_sign_index = $key;
					} else if($p_field == "total_price") {
						$total_price_index = $key;
					} else if($p_field=='DT_TT'){
						$DT_TT_index = $key;
					} else if($p_field == 'total_price_vat'){
						$total_price_vat_index = $key;
					} else if($p_field == 'total_price_early'){
						$total_price_early_index = $key;
					}
				}
				/* End Define */
				// Cập nhật các căn thành đã bán
				$sql_block_permiss = "";
				$sql_string = "`is_trash`=0";
				if($project_id > 0) {
					$sql_string.= " and `project_id`='{$project_id}'";
				}
				if(!empty($block_ids)) {
					$sql_block_permiss .= " and `block_id` IN (".implode(",",$block_ids).") ";
				}
				$list_stock_ids = array();
				for($i=0; $i<=$total_record; $i++){
					if(isset($tblData[$i][$ms_code_index]) && !empty($tblData[$i][$ms_code_index])){
						$ms_code = trim(str_replace(" ","",$tblData[$i][$ms_code_index]));
						$oneStock = $clsStock->getByCond("{$sql_string} and `ms_code`='{$ms_code}' 
						and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'".$sql_block_permiss, $clsStock->pkey);
						if(!empty($oneStock)) {
							$list_stock_ids[] = $oneStock[$clsStock->pkey];
						}
					}
				}
				// $clsISO->print_pre($list_stock_ids);die;
				if(!empty($list_stock_ids)){
					$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`,`block_id`";
					$list_sold_stocks = $clsStock->getAll("{$sql_string} and `agency_id`='{$agency_id}' and (`status_id`>0 
					and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and status_id<>'"._STOCK_STATUS_NON_ID."') and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `{$clsStock->pkey}` not in(".implode(',', $list_stock_ids).") {$sql_block_permiss}", $field);
					if(!empty($list_sold_stocks)){
						foreach($list_sold_stocks as $key => $val){
							$logs = array();
							$more_information = $val['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							$m_field = "{$clsStockMeta->pkey},`logs`";
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
							if(!empty($oneStockMeta)){
								$logs = $oneStockMeta['logs'];
								$logs = $clsISO->to_array_json($logs);
							} else {
								$clsStockMeta->insert(array(
									'stock_id' => $val[$clsStock->pkey],
									'reg_date' => time(),
									'upd_date' => time()
								));
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
							}
							$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
							$more_information['user_id_update_sold'] = $profile_id;
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
								if(!empty($oneStockMeta)){
									$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
										'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
										'upd_date' => time()
									));
								}
								if(isset($arr_total_stock_sold[$val['block_id']])) {
									$arr_total_stock_sold[$val['block_id']] += 1;
								} else{
									$arr_total_stock_sold[$val['block_id']] = 1;
								}
							}
						}
						unset($list_sold_stocks);
					}
				}
				for($i=0; $i<$total_record; $i++){
					$ms_code = $core->get_field($tblData[$i], $ms_code_index, "");
					$ms_code = str_replace(" ","",$ms_code);
					$csbh = $core->get_field($tblData[$i], $csbh_index, "");
					$date_deposit_sign = $core->get_field($tblData[$i], $date_deposit_sign_index, "");
					$price_sheet_title = $core->get_field($tblData[$i], $price_sheet_title_index, "PTG TẠM TÍNH");
					$price_sheet_link = $core->get_field($tblData[$i], $price_sheet_link_index, "");
					if(empty($ms_code)) continue;
					$field = "{$clsStock->pkey},`more_information`,`status_id`,`agency_id`,`block_id`";
					$oneStock = $clsStock->getByCond("{$sql_string} and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
						and `ms_code`='{$ms_code}' {$sql_block_permiss}", $field);
					// $clsISO->print_pre($oneStock);die;
					if(!empty($oneStock)){
						/*giá min max*/
						$min = !empty($min_max_block[$oneStock["block_id"]]["min"]) ? $min_max_block[$oneStock["block_id"]]["min"] : "";
						$max = !empty($min_max_block[$oneStock["block_id"]]["max"]) ? $min_max_block[$oneStock["block_id"]]["max"] : "";
						/*end giá min max*/
						$uid = $clsISO->getUniqid();
						$upd_field = $logs = $more_information = array();
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
						$more_information = $clsISO->to_array_json($oneStock['more_information']);
						foreach($columns as $key => $p_field){
							$tblData[$i]['agency_id'] = $agency_id;
							$tblData[$i]['status_id'] = $stock_status_id;
							if(!empty($p_field)){
								if($p_field=='block_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='building_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BUILDING' 
									and (property_code='".$tblData[$i][$key]."' or slug='".$core->replaceSpace($tblData[$i][$key])."')");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='view_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_VIEW' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'", $clsProperty->pkey);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : $stock_status_id;
								} else if($p_field=='type_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE' 
									and (`property_code`'".$tblData[$i][$key]."' or slug='".$core->replaceSpace($tblData[$i][$key])."')");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='bedroom_id' && !empty($tblData[$i][$key])){
									$tblData[$i][$key] = preg_replace('/\s+/', '', $tblData[$i][$key]);
									$tmp = $clsProperty->getByCond("`property_type`='_BEDROOM' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='home_direction_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
									and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='agency_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("is_trash=0 and property_type='_AGENCY' 
									and (`property_code`='".$tblData[$i][$key]."' 
									or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $clsProperty->pkey);
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : $agency_id;
								}
								$upd_field['agency_id'] = $agency_id;
								$upd_field['status_id'] = $stock_status_id;
								$more_information['agency_id'] = $agency_id;
								$more_information['status_id'] = $stock_status_id;
								$sheets = $more = array();
								$price_sheets = $core->get_field($more_information, "price_sheets", []);
								if(!empty($price_sheet_link)){
									$sheets[$clsISO->getUniqid()] = array(
										'title' => $price_sheet_title,
										'image' => $price_sheet_link
									);
								}
								if(!empty($sheets)){
									$price_sheets = array();
									$price_sheet_id = $clsISO->getUniqid();
									$price_sheets[$price_sheet_id]['sheets'] = $sheets;
									$price_sheets[$price_sheet_id]['reg_date'] = time();
									$price_sheets[$price_sheet_id]['upd_date'] = time();
									$price_sheets[$price_sheet_id]['csbh'] = $csbh;
									$price_sheets[$price_sheet_id]['user_id'] = $core->_USER['user_id'];
									$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
								}
								$more_information['price_sheets']= $price_sheets;
								if(!in_array($p_field, array('ms_code','DT_Tim','DT_TT','total_price','total_price_vat'
								,'total_price_early','total_price_progress','total_price_bank','total_price_bank_half','csbh'
								,'price_sheet_title','price_sheet_link','date_deposit_sign','sale_bonus')) && !empty($tblData[$i][$key])){
									$upd_field[$p_field] = trim($tblData[$i][$key]);
									$more_information[$p_field] = $tblData[$i][$key];
									if(in_array($p_field, ['agency_id','status_id'])){
										if($oneStock[$p_field] != trim($tblData[$i][$key])){
											$logs[$clsISO->getUniqid()] = array(
												'field' => $p_field,
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_id' => $oneStock[$p_field],
												'to_id' => trim($tblData[$i][$key])
											);
										}
									}
								} else {
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
									,'total_price_progress','total_price_bank','total_price_bank_half'))){
										$price_old = $more_information[$p_field];
										if($opt_ignore_empty == 0 && empty($tblData[$i][$key])){
											$more_information[$p_field]= 0;
										} else if(!empty($tblData[$i][$key])){
											$p_value = $clsISO->processSmartNumber($tblData[$i][$key]);
											$p_value = $clsISO->convertPriceShortToFullUpdate($p_value,$min,$max);
											$more_information[$p_field] = $p_value;
											${$p_field} = $p_value;
											if($price_old != $p_value){
												$logs[$clsISO->getUniqid()] = array(
													'field' => $p_field,
													'reg_date' => time(),
													'user_id' => $core->_USER['user_id'],
													'from_value' => $price_old,
													'to_value' => $p_value
												);
											}
										}
									} else {
										if($p_field=='date_deposit_sign' && !empty($date_deposit_sign)){
											$more_information[$p_field] = $tblData[$i][$key];
										} else {
											$more_information[$p_field] = $tblData[$i][$key];
										}
									}
								}
								if(isset($tblData[$i][$DT_TT_index]) && !empty($tblData[$i][$DT_TT_index])){
									$DT_TT = $tblData[$i][$DT_TT_index];
									$upd_field['DT_TT'] = $clsISO->toNumber(trim($DT_TT));
								}
								if(isset($tblData[$i][$total_price_vat_index]) && !empty($tblData[$i][$total_price_vat_index])){
									$total_price_vat = $tblData[$i][$total_price_vat_index];
									$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
									$total_price_vat = $clsISO->convertPriceShortToFullUpdate($total_price_vat,$min,$max);
									$upd_field['total_price_vat'] = $total_price_vat;
								} else if(isset($tblData[$i][$total_price_index]) && !empty($tblData[$i][$total_price_index])){
									$total_price = $tblData[$i][$total_price_index];
									$total_price = $clsISO->processSmartNumber($total_price);
									$total_price = $clsISO->convertPriceShortToFullUpdate($total_price,$min,$max);
									$total_price_vat = $total_price * _PERCENT_PRICE_VAT;
									$total_price_vat = round($total_price_vat);
									$upd_field['total_price_vat'] = $total_price_vat;
									$more_information["total_price_vat"] = $total_price_vat;
								} else if(isset($tblData[$i][$total_price_early_index]) && !empty($tblData[$i][$total_price_early_index])){
									$total_price_early = $tblData[$i][$total_price_early_index];
									$total_price_early = $clsISO->processSmartNumber($total_price_early);
									$total_price_early = $clsISO->convertPriceShortToFullUpdate($total_price_early,$min,$max);
									$upd_field['total_price_vat'] = $total_price_early;
								} else if($opt_ignore_empty==0){
									$upd_field['total_price_vat'] = 0;
									$more_information['total_price_vat'] = 0;
								}
							}
						}
						#- End Logs
						$upd_field['upd_date'] = time();
						$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
							++$total_updated;
							if(!empty($oneStockMeta)){
								$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
									'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'upd_date' => time()
								));
							}
							if($oneStock["agency_id"] == 0) {
								if(!empty($arr_total_stock_new[$oneStock["block_id"]])) {
									$arr_total_stock_new[$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_new[$oneStock["block_id"]] = 1;
								}
							}else{
								if(!empty($arr_total_stock_upd[$oneStock["block_id"]])) {
									$arr_total_stock_upd[$oneStock["block_id"]] += 1;
								}else{
									$arr_total_stock_upd[$oneStock["block_id"]] = 1;
								}
							}
						}
						//log cập nhật
						$arr_ms_code = array_keys($arr_data_code);
						$arr_not_upd = array_diff($arr_ms_code,$arr_upd);
						$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
						$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
						$total_stock_sold = !empty($arr_total_stock_sold[$oneStock["block_id"]]) ? $arr_total_stock_sold[$oneStock["block_id"]] : 0;
						$total_updated_block = !empty($arr_total_stock_upd[$oneStock["block_id"]]) ? $arr_total_stock_upd[$oneStock["block_id"]] : 0;
						#log new						
						$arr_data[$oneStock["block_id"]][] = [
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
						$arr_tmp_stock[$oneStock["block_id"]][] = $ms_code;
						$lst_stock_id[$oneStock["block_id"]][] = $oneStock[$clsStock->pkey];
					}else{
						$stock_not_upd[] = $ms_code;
					}
				}
			}
			if($total_updated > 0) {				
				#activity log		
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Stock","update");
			}
			$clsLogCrawl = new LogCrawl();
			foreach ($block_ids as $block_id) {
				$total_stock_sold = !empty($arr_total_stock_sold[$block_id]) ? $arr_total_stock_sold[$block_id] : 0;
				$total_stock_new = !empty($arr_total_stock_new[$block_id]) ? $arr_total_stock_new[$block_id] : 0;
				$total_updated_block = !empty($arr_total_stock_upd[$block_id]) ? $arr_total_stock_upd[$block_id] : 0;
				$arr_data_upd = array(
					"total_stock_sold"	=>	$total_stock_sold,
					"total_stock_new"	=>	$total_stock_new,
					"total_stock"	=>	$total_updated_block,
					"stock_not_upd"	=>	$stock_not_upd,
					"data_log"	=>	!empty($arr_data[$block_id]) ? $arr_data[$block_id] : array(),
					"title_log"	=>	'Tổng quỹ: '.$total_updated_block.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
					"type"	=>	2,	//0:tổng hợp,1:drive,2:hình ảnh-copy,
					"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
				);
				$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
			}
			#luu bang tam
			$logs_field = "{$clsStockLog->pkey},`more_information`";
			foreach ($arr_tmp_stock as $target_id => $ms_codes) {	
				$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$target_id,$ms_codes);
				// Start Logs 
				$stock_ids = !empty($lst_stock_id[$target_id]) ? $lst_stock_id[$target_id] : array();
				$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$target_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
				if(!empty($tmp)){
					$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
						'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
					));
				} else {
					$clsStockLog->insert(array(
						'stock_type' => $stock_type,
						'agency_id' => $agency_id,
						'block_id' => $target_id,
						'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
						'reg_date' => time(),
						'user_id' => $core->_USER['user_id']
					));
				}
				/** End */
			}			
			#tong hop quy dai ly
			if($agency_id == _AGENCY_FH_ID) {
				$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);	
			}
			// Return
			echo json_encode(array(
				'result'	=>	true,
				'msg' => '_success',
				'total_updated' => $total_updated
			));	die();
		} else {
			echo json_encode(array(
				'result'	=>	false,
				'msg' => 'error_field',
				'total_updated' => $total_updated
			));	die();
		}
	}
}
function default_open_import_logs(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsUser = new User();
	$clsAdminLog = new AdminLog();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$agency_id = Input::post('agency_id', 0);
	$list_logs = $clsAdminLog->getAll("`target_id`='{$agency_id}' and `action`='update_stock' order by `date` DESC limit 0,50");
	if(!empty($list_logs)){
		foreach($list_logs as $key => $val){
			$user_id = $val['user_id'];
			if($val["from_site"] == "_admin") {
				if($user_id == $core->_USER['user_id']){
					$list_logs[$key]['full_name'] = sprintf('%s %s', $core->_USER['first_name'], $core->_USER['last_name']);
				} else {
					$oUser = $clsUser->getOne($user_id, "first_name, last_name");
					$list_logs[$key]['full_name'] = sprintf('%s %s', $oUser['first_name'], $oUser['last_name']);
				}
			}else{
				$oUser = $clsProfile->getOne($user_id, "full_name");
				$list_logs[$key]['full_name'] = sprintf('%s', $oUser['full_name']);
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
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_cron_automation_enable(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProperty = new Property();
	#
	$msg = "_error";
	$agency_id = (int) Input::post('agency_id', 0);
	$cron_automation_enable = (int) Input::post('cron_automation_enable', 0);
	#
	$more_information = $clsProperty->getOneField("more_information", $agency_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	// $clsISO->print_pre($more_information); die();
	$more_information['cron_automation_enable'] = $cron_automation_enable;
	if($clsProperty->updateOne($agency_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// return
	echo $msg; die();
}
function default_do_import_agent_advanced(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$agency_id = (int) Input::post('agency_id', 0);
	$more_information = $clsProperty->getOneField('more_information', $agency_id);
	$more_information = $clsISO->to_array_json($more_information);
	$stock_sheet_configs = $core->get_field($more_information, "stock_sheet_configs", []);
	// $clsISO->print_pre($stock_sheet_configs); die();
	$total_updated = 0;
	if(!empty($stock_sheet_configs)){
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		foreach($stock_sheet_configs as $key => $val){
			$spreadsheetId = trim($val['spreadsheetId']);
			$name = trim($val['name']);
			$column = trim($val['column']);
			$row = trim($val['row']);
			if(!empty($spreadsheetId) && !empty($name)){
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
				// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
				// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
				// $clsISO->print_pre($spreadsheet); die();
				$range = $name; // here we use the name of the Sheet to get all the rows
				$response = $service->spreadsheets_values->get($spreadsheetId, $range);
				$tblData = $response->getValues();
				if(!empty($tblData)){
					$has_row = false;
					$list_rows = array();
					$tmp = @preg_split('/(\,|\|)/', $row);
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if($clsISO->checkContainer($val, '-','')){
								$output = explode('-', $val);
								if((int) $output[0]==(int) $output[1]){
									$has_row = true;
									$list_rows[] = $output[0];
								} else {
									$has_row = true;
									for($i=$output[0]; $i<=$output[1]; $i++){
										$list_rows[] = $i;
									}
								}
							} else {
								$has_row = true;
								$list_rows[] = $val;
							}
						}
						$list_rows = ($has_row) 
							? @array_unique($list_rows) 
							: $list_rows;
					}
					$list_columns = array();
					if(!empty($column)){
						$tmp = @explode('|', $column);
						foreach($tmp as $val){
							$output = @explode('-', $val);
							$list_columns[$output[0]] = $output[1];
						}
					}
					$field_ms_code_pos = 0;
					foreach($list_columns as $p_pos => $p_field){
						if($p_field=='ms_code'){
							$field_ms_code_pos = $p_pos;
						}
					}
					foreach($list_rows as $row){
						$oData = $tblData[$row];
						$ms_code = $oData[$field_ms_code_pos];
						if(!empty($ms_code)){
							$field = "{$clsStock->pkey},`more_information`";
							$oStock = $clsStock->getByCond("`ms_code`='{$ms_code}' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'", $field);
							// $clsISO->print_pre($oStock); die();
							if(!empty($oStock)){
								$more_information = $oStock['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$logs = array(); $m_field = "{$clsStockMeta->pkey},`logs`";
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$oStock[$clsStock->pkey]}'", $m_field);
								if(!empty($oneStockMeta)){
									$logs = $oneStockMeta['logs'];
									$logs = $clsISO->to_array_json($logs);
								} else {
									$clsStockMeta->insert(array(
										'stock_id' => $oStock[$clsStock->pkey],
										'reg_date' => time(),
										'upd_date' => time()
									));
									$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oneStock[$clsStock->pkey]."'", $m_field);
								}
								$update_field = array();
								$update_field['upd_date'] = time();
								$update_field['user_id_update'] = $core->_USER['user_id'];
								foreach($list_columns as $p_pos => $p_field){
									if(!in_array($p_field, array('DT_Tim','DT_TT','total_price','total_price_vat','total_price_early'
										,'total_price_progress' ,'total_price_bank','total_price_bank_half'))){
										if($p_field != 'ms_code'){
											$update_field[$p_field] = $oData[$p_pos];
										}
									} else {
										if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
											,'total_price_progress' ,'total_price_bank','total_price_bank_half'))){
											if(isset($oData[$p_pos]) && !empty($oData[$p_pos])){
												if(!isset($more_information[$p_field]) || (isset($more_information[$p_field]) && $more_information[$p_field] != $clsISO->processSmartNumber($oData[$p_pos]))){
													$logs[$clsISO->getUnuqid()] = array(
														'reg_date' => time(),
														'user_id' => $core->_USER['user_id'],
														'from_value' => $more_information[$p_field],
														'to_value' => $clsISO->processSmartNumber($oData[$p_pos]),
														'field' => $p_field
													);
												}
												$more_information[$p_field] = $clsISO->processSmartNumber($oData[$p_pos]);
											}
										} else {
											if(isset($oData[$oPos]) && !empty($oData[$p_pos])){
												$more_information[$p_field] = $oData[$p_pos];
											}
										}
									}
								}
								// $clsISO->print_pre($more_information); die();
								// $update_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
								$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
								if($clsISO->updateOne($oStock[$clsStock->pkey], $update_field)){
									$total_updated += 1;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));	
									}
								}
							}
						}
					}
				}
			}
		}
	}
	// Return
	echo '_success|||'.$total_updated; 
	die();
}
function default_setting_agent(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$assign_list['clsStock'] = $clsStock;
	$assign_list['clsProperty'] = $clsProperty;
	$total_stocks = 0;
	$field = "{$clsProperty->pkey},title,intro,more_information";
	$list_agents = $clsProperty->getAll("`is_locked`=0 and `property_type`='_AGENCY' order by `order_no` ASC", $field);
	if(!empty($list_agents)){
		foreach($list_agents as $key => $val){
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array(); 
			$last_cronjob_time = isset($more_information['last_cronjob_time']) && !empty($more_information['last_cronjob_time']) 
				? $more_information['last_cronjob_time'] : 0;
			$list_agents[$key]['last_cronjob_time'] = $last_cronjob_time;
			#
			$cron_automation_enable = 0;
			if(isset($more_information['cron_automation_enable'])){
				$cron_automation_enable = $more_information['cron_automation_enable'];
			}
			$more_information['cron_automation_enable'] = $cron_automation_enable;
			$list_agents[$key]['more_information'] = $more_information;
			#
			$total_stock_in = $clsStock->countItem("`status_id`='"._STOCK_STATUS_LOCK_ID."' and `agency_id`='{$val[$clsProperty->pkey]}'");
			$total_stocks += $total_stock_in;
			$list_agents[$key]['total_stock_in'] = $total_stock_in;
		}
		$last_cronjob_time_arrs = array_column($list_agents, 'last_cronjob_time');
		array_multisort($last_cronjob_time_arrs, SORT_ASC, $list_agents);
	}
	$assign_list['total_stocks'] = $total_stocks;
	$assign_list['list_agents'] = $list_agents;
	//$clsISO->print_pre($list_agents); die();
}
function default_upgrade(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn, $dbconn2;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list['clsSop'] = $clsSop;
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	$list_stocks = $dbconn2->getAll("select {$clsStock->pkey} from {$clsStock->tbl} 
		where stock_type='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and status_id='"._STOCK_STATUS_GENERAL_ID."'");
	if(!empty($list_stocks) && 1==2){
		foreach($list_stocks as $key => $val){
			$oStock = $clsStock->getOne($val[$clsStock->pkey], "more_information");
			$more_information = $oStock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['status_id'] = _STOCK_STATUS_GENERAL_ID;
			// $clsISO->print_pre($more_information); die();
			$clsStock->updateOne($val[$clsStock->pkey], array(
				'status_id' => _STOCK_STATUS_GENERAL_ID,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	$clsISO->print_pre($list_stocks); die();
}
function default_default(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list['clsSop'] = $clsSop;
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	###
	$dev = vnSessionExist('dev') ? (int) vnSessionGetVar('dev') : 0;
	if(isset($_GET['dev']) && !empty($_GET['dev'])){
		$dev = (int) $_GET['dev'];
		vnSessionSetVar('dev', $dev);
	}
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		$stock_type = (int) Input::post('stock_type', 0);
		$project_id = (int) Input::post('project_id', 0);
		$block_id = (int) Input::post('block_id', 0);
		$building_id = (int) Input::post('building_id', 0);
		$floor = Input::post('floor', "");
		$bedroom_id = (int) Input::post('bedroom_id', 0);
		$type_id = (int) Input::post('type_id', 0);
		$view_id = (int) Input::post('view_id', 0);
		$home_direction_id = (int) Input::post('home_direction_id', 0);
		$agency_id = (int) Input::post('agency_id', 0);
		$status_id = (int) Input::post('status_id', 0);
		$DT_TT = (int) Input::post('DT_TT', 0);
		if($project_id > 0) {
			$link .= '&project_id='.$project_id;
			$hasCond = true;
		}
		if($stock_type > 0){
			$link.= "&stock_type=".$stock_type;
			$hasCond = true;
		}
		if($block_id > 0) {
			if($clsProperty->countItem("`property_type`='_BLOCK' and `for_id`='{$project_id}' 
				and `property_id`='{$block_id}'") > 0){
				$link .= '&block_id='.$block_id;
			}
		}
		if($building_id > 0) {
			$link .= '&building_id='.$building_id;
		}
		if($floor != "") {
			$link .= '&floor='.$floor;
		}
		if($bedroom_id > 0) {
			$link .= '&bedroom_id='.$bedroom_id;
		}
		if($type_id > 0) {
			$link .= '&type_id='.$type_id;
		}
		if($view_id > 0) {
			$link .= '&view_id='.$view_id;
		}
		if($home_direction_id > 0) {
			$link .= '&home_direction_id='.$home_direction_id;
		}
		if($agency_id > 0) {
			$link .= '&agency_id='.$agency_id;
		}
		if($status_id > 0) {
			$link .= '&status_id='.$status_id;
		}
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		if(!empty($DT_TT)){
			$link .= '&DT_TT='.$DT_TT;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	#
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$assign_list['list_projects'] = $list_projects;
	$stock_type = (int) Input::get('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE) ;// Cao tầng
	$project_def_id = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? _PROJECT_DEF_ID : 0;
	$project_id = (int) Input::get('project_id', 0);
	$block_id = (int) Input::get('block_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	$floor = Input::get('floor', "");
	$bedroom_id = (int) Input::get('bedroom_id', 0);
	$type_id = (int) Input::get('type_id', 0);
	$view_id = (int) Input::get('view_id', 0);
	$home_direction_id = (int) Input::get('home_direction_id', 0);
	$agency_id = (int) Input::get('agency_id', 0);
	$status_id = (int) Input::get('status_id', 0);
	$DT_TT = (int) Input::get('DT_TT', 0);
	$keyword = Input::get("keyword", "");
	$code = Input::get("code", "");
	
	
	
	
	// $dbconn->debug = true;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$field = "{$clsProperty->pkey},title";
	if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
		$arrTypes = $clsProperty->getAllCache("`property_type`='_TYPE' order by order_no ASC", $field);
		$arrViews = $clsProperty->getAllCache("`property_type`='_VIEW' order by order_no ASC", $field);
		$arrBedRooms = $clsProperty->getAllCache("`property_type`='_BEDROOM' order by order_no ASC", $field);
		$arrTypeVilla = $clsProperty->getAllCache("`property_type`='_TYPE_VILLA' order by order_no ASC", $field);
		$arrBlock = $clsProperty->getAllCache("`property_type`='_BLOCK' and for_id='"._PROJECT_DEF_ID."' order by order_no ASC", $field);
		$assign_list['arrViews'] = $arrViews;
		$assign_list['arrBlock'] = $arrBlock;
	} else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
		$arrStockHold = $clsProperty->getAllCache("`property_type`='_STOCK_HOLD' order by order_no ASC", $field);
		$arrTypes = $clsProperty->getAllCache("`property_type`='_TYPE_VILLA' order by order_no ASC", $field);
		$arrInvestFund = $clsProperty->getAllCache("`property_type`='_INVEST_FUND' order by order_no ASC", $field);
		$arrContractType = $clsProperty->getAllCache("`property_type`='_CONTRACT_TYPE' order by order_no ASC", $field);
		$arrBank = $clsProperty->getAllCache("`property_type`='_BANK' order by order_no ASC", $field);
		if($project_id > 0){
			$arrBlock = $clsProperty->getAllCache("`property_type`='_BLOCK' and for_id='{$project_id}' order by order_no ASC", $field);
			if(!empty($arrBlock)){
				foreach($arrBlock as $okey => $oval){
					$block_id_in = $oval[$clsProperty->pkey];
					$list_ranges = $clsProperty->getAll("property_type='_RANGE' and for_id='{$block_id_in}' order by order_no ASC", $field);
					$arrBlock[$okey]['list_ranges'] = $list_ranges;
				}
			}
			$assign_list['arrBlock'] = $arrBlock;
		} else {
			$arrBlock = array();
			if(!empty($list_projects)){
				foreach($list_projects as $key => $val){
					$project_id_in = $val[$clsProject->pkey];
					$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id_in}' order by order_no ASC", $field);
					if(!empty($list_blocks)){
						foreach($list_blocks as $okey => $oval){
							$block_id_in = $oval[$clsProperty->pkey];
							$list_ranges = $clsProperty->getAll("property_type='_RANGE' and for_id='{$block_id_in}' order by order_no ASC", $field);
							$list_blocks[$okey]['list_ranges'] = $list_ranges;
						}
						$arrBlock[] = array(
							$clsProject->pkey => $val[$clsProject->pkey],
							'title' => $val['title'],
							'list_blocks' => $list_blocks
						);
					}
				}
			}
		}
		$assign_list['arrContractType'] = $arrContractType;
		$assign_list['arrInvestFund'] = $arrInvestFund;
		$assign_list['arrBlock'] = $arrBlock;
		$assign_list['arrBank'] = $arrBank;
	} else if($stock_type == _STOCK_TYPE_LEASING){
		$arrTypes = $clsProperty->getCacheItems("_TYPE_VILLA");
		$arrStockHold = $clsProperty->getCacheItems("_STOCK_HOLD");
		$arrCompleteFloors = $clsProperty->getCacheItems("_COMPLETED_FLOOR");
		$arrStatusPaymentProgress = $clsProperty->getCacheItems("_STATUS_PAYMENT_PROGRESS");
		$assign_list['arrTypes'] = $arrTypes;
		$assign_list['arrStockHold'] = $arrStockHold;
		$assign_list['arrCompleteFloors'] = $arrCompleteFloors;
		$assign_list['arrStatusPaymentProgress'] = $arrStatusPaymentProgress;
	}
	$oneBlock = $oneBuilding = $more_information = $more_information_building = array();
	// $arrStatus = $clsProperty->getAllCache("`property_type`='_STATUS' order by order_no ASC", $field);
	// $arrAgencies = $clsProperty->getAllCache("`property_type`='_AGENCY' order by order_no ASC", $field);
	// $arrDirections = $clsProperty->getCacheItems("`property_type`='' order by order_no ASC", $field);
	$arrStatus = $clsProperty->getCacheItems("_STATUS");
	// $clsISO->print_pre($arrStatus); die();
	$arrAgencies = $clsProperty->getAllCache("`property_type`='_AGENCY' order by order_no ASC", $field);
	$arrDirections = $clsProperty->getCacheItems("_DIRECTION");
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
		if($building_id > 0){
			$oneBuilding = $clsProperty->getOne($building_id);
			$more_information_building = $oneBuilding['more_information'];
			$more_information_building = $clsISO->to_array_json($more_information_building);
			$html_floor_option = '<option value="">Tầng</option>';
			$lstFloor = explode(",",$more_information_building["floor"]);
			foreach ($lstFloor as $fl) {
				$html_floor_option .= '<option value="'.$fl.'"'.($floor==$fl?' selected':'').'>Tầng '.$fl.'</option>';
			}
			/*for($i=1; $i <= $more_information_building['number_floor']; $i++){
				$html_floor_option .= '<option value="'.$i.'"'.($floor==$i?' selected':'').'>Tầng '.$i.'</option>';
			}*/
			$assign_list['html_floor_option'] = $html_floor_option;
		} 
		$assign_list['oneBuilding'] = $oneBuilding;
		$assign_list['more_information_building'] = $more_information_building;
	}
	##
	$assign_list['floor'] = $floor;
	$assign_list['view_id'] = $view_id;
	$assign_list['bedroom_id'] = $bedroom_id;
	$assign_list['type_id'] = $type_id;
	$assign_list['building_id'] = $building_id;
	$assign_list['home_direction_id'] = $home_direction_id;
	$assign_list['agency_id'] = $agency_id;
	$assign_list['status_id'] = $status_id;
	$assign_list['stock_type'] = $stock_type;
	$assign_list['keyword'] = $keyword;
	#
	if($stock_type == _STOCK_TYPE_LEASING){
		$cond = "`is_trash`=0 and JSON_EXTRACT(`more_information`,\"$.stock_type\")='"._STOCK_TYPE_LEASING."'";
	} else {
		$cond = "`is_trash`=0 and `stock_type`='{$stock_type}'";
	}
	if($project_id > 0){
		$cond.= " and `project_id`='{$project_id}'";
	}
	if($block_id > 0){
		$cond .= " and `block_id`='{$block_id}'";
	}
	if($block_id > 0 && $building_id > 0){
		$cond .= " and `building_id`='{$building_id}'";
	}
	if($floor != "") {
		$cond.= " and (`floor`='{$floor}' 
		)";
	}
	if($code != "") {
		$cond.= " and (`code`='{$code}' 
		)";
	}
	if($bedroom_id > 0) 
		$cond.= " and `bedroom_id`='{$bedroom_id}'";
	if($type_id > 0) 
		$cond.= " and `type_id`='{$type_id}'";
	if($view_id > 0) 
		$cond.= " and `view_id`='{$view_id}'";
	if($agency_id > 0) 
		$cond.= " and `agency_id`='{$agency_id}'";
	if($status_id > 0) 
		$cond.= " and `status_id`='{$status_id}'";
	if($DT_TT > 0) 
		$cond.= " and `DT_TT`='{$DT_TT}'";
	if($home_direction_id > 0) 
		$cond.= " and `home_direction_id`='{$home_direction_id}'";
	if(!empty($keyword)) 
		$cond .= " and (`ms_code` like '%{$keyword}%' or REPLACE(ms_code,'.','') like '%{$keyword}%')";
	#- Pagination
	$current_page = (int) Input::get('page',1);
	$per_page = vnSessionExist('_ss_per_page') 
		? (int) vnSessionGetVar('_ss_per_page') : 30;
	$total_record = $clsStock->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	if(!empty($arrStatus)){
		foreach($arrStatus as $key => $val){
			$status_id = $val[$clsProperty->pkey];
			if($status_id==_STOCK_STATUS_SOLD_ID && $stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
				$cnd = "(`status_id`='{$status_id}' or `status_id`=0)";
			} else {
				$cnd= "`status_id`='{$status_id}'";
			}
			$total_stock = $clsStock->countItem("`stock_type`='{$stock_type}' and ".$cnd);
			$arrStatus[$key]['total_stock'] = $total_stock;
		}
	}
	####
//	$list_stocks = $clsStock->getAll("{$cond} order by `floor` ASC, `code` ASC".$limitCond);
//	$clsStock->setDeBug(1);
	$list_stocks = $clsStock->getAll("{$cond} order by `stock_id` ASC, `code` ASC".$limitCond);
//	$clsISO->print_pre($list_stocks);die;
	// $clsISO->print_pre($list_stocks); die();
	foreach($list_stocks as $key => $val){
		$more_information = $val['more_information'];
		$more_information = !empty($more_information) 
			? @json_decode($more_information, true) : array();
		$list_stocks[$key]['more_information'] = $more_information;
	}
	$assign_list['list_stocks'] = $list_stocks;
	#
	$link_page_current = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page'&&$tmp[0]!='vpc_status')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> PCMS_URL.'/index.php'.$link_page_current
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links();
	$assign_list["html_pager"] = $html_pager;
	$assign_list["total_record"] = $total_record;
	$assign_list["total_page"] = $total_page;
	$assign_list["per_page"] = $per_page;
	$assign_list['link_page_current'] = $link_page_current;
	$assign_list['block_id'] = $block_id;
	$assign_list['project_id'] = $project_id;
	####
	$assign_list['arrTypes'] = $arrTypes;
	$assign_list['arrStatus'] = $arrStatus;
	$assign_list['arrAgencies'] = $arrAgencies;
	$assign_list['arrBedRooms'] = $arrBedRooms;
	$assign_list['arrTypeVilla'] = $arrTypeVilla;
	$assign_list['arrDirections'] = $arrDirections;
}
function default_reload_per_page(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	###
	$per_page = Input::post('per_page',20);
	vnSessionSetVar('_ss_per_page', $per_page);
	// Return
	echo (1); die();
}
function default_import(){
	require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
    require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
	$inputFileName = ABSPATH . "/X1.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	try {
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
	} catch(Exception $e) {
		die($e->getMessage());
	}
	$worksheet = $objPHPExcel->getActiveSheet();
	$worksheetTitle     = $worksheet->getTitle();
	$highestRow         = $worksheet->getHighestRow();
	$highestColumn      = $worksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	$tblColumn =array();
	for ($col = 0; $col < $highestColumnIndex; ++ $col) {
		$cell = $worksheet->getCellByColumnAndRow($col, 1);
		$tblColumn[] = $cell->getValue();
	}
	$index = 0;
	$tblData =array();
	for ($row = 2; $row <= $highestRow; ++ $row) {
		for ($col = 0; $col < $highestColumnIndex; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
			if(PHPExcel_Shared_Date::isDateTime($cell)){
				if(!empty($cell)){
					$date= PHPExcel_Shared_Date::ExcelToPHPObject($cell->getValue());
					$tblData[$index][] =  date_format($date,'d/m/Y');
				}else{
					$tblData[$index][] = $cell->getValue();
				}
			}else{
				$tblData[$index][] = $cell->getValue();
			}
		}
		++$index;
	}
	if(!empty($tblData)){
		foreach($tblData as $key => $val){
			$project_id = 1;
			$building = $val[0];
			$code = $val[1];
			$bedroom = $val[3];
			$direction = $val[4];
			$DT_TT = $val[5];
			$DT_Tim = $val[6];
			$block = $val[7];
			##
			$bedroom_id = 0;
			if(!empty($bedroom)){
				$tmp = $clsProperty->getAll("property_type='_BEDROOM' and slug='".$core->replaceSpace($bedroom)."'");
				$bedroom_id = $tmp[0][$clsProperty->pkey];
				unset($tmp);
			}
			##
			$home_direction_id = 0;
			if(!empty($direction)){
				$tmp = $clsProperty->getAll("property_type='_DIRECTION' and slug='".$core->replaceSpace($direction)."'");
				$home_direction_id = $tmp[0][$clsProperty->pkey];
				unset($tmp);
			}
			##
			$block_id = 0;
			if(!empty($block)){
				$tmp = $clsProperty->getAll("property_type='_BLOCK' and slug='".$core->replaceSpace($block)."'");
				$block_id = $tmp[0][$clsProperty->pkey];
				unset($tmp);
			}
			##
			$building_id = 0;
			if(!empty($building)){
				$tmp = $clsProperty->getAll("property_type='_BUILDING' and for_id='{$block_id}' 
				and slug='".$core->replaceSpace($building)."'");
				$building_id = $tmp[0][$clsProperty->pkey];
				unset($tmp);
			}
			if($clsStock->countItem("project_id='{$project_id}' and block_id='{$block_id}' 
			and building_id='{$building_id}' and code='{$code}'") == 0){
				$more_information = array(
					'bedroom_id' => $bedroom_id,
					'home_direction_id' => $home_direction_id,
					'DT_TT' => $DT_TT,
					'DT_Tim' => $DT_Tim
				);
				$clsStock->insert(array(
					'stock_id' => $clsStock->getMaxId(),
					'project_id' => $project_id,
					'block_id' => $block_id,
					'building_id' => $building_id,
					'code' => $code,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'user_id' => $core->_USER['user_id'],
					'reg_date' => time()
				));
			}
		}
	}
	$clsISO->print_pre($tblData); die();
}
function default_add_line(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$stock_id = $clsStock->getMaxId();
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$smarty->assign('stock_id', $stock_id);
	$smarty->assign('stock_type', $stock_type);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('block_id', $block_id);
	$smarty->assign('building_id', $building_id);
	$clsStock->insert(array(
		$clsStock->pkey => $stock_id,
		'stock_type' => $stock_type,
		'user_id' => $core->_USER['user_id'],
		'project_id' => $project_id,
		'block_id' 	=> $block_id,
		'building_id' => $building_id,
		'reg_date' => time()
	));
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.add_line.tpl');
	echo $html; die();
}
function default_delete_line(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	#
	$msg = '_error';
	$stock_id = (int) Input::post('stock_id', 0);
	if($clsStock->deleteOne($stock_id)){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_save_field(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProperty = new Property();
	###
	$field = Input::post('field');
	$value = Input::post('value');
	$stock_id = (int) Input::post('stock_id', 0);
	$oneStock = $clsStock->getOne($stock_id);
	$more_information = $oneStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	##
	$logs = array();
	$m_field = "{$clsStockMeta->pkey},`logs`";
	$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
	if(!empty($oneStockMeta)){
		$logs = $oneStockMeta['logs'];
		$logs = $clsISO->to_array_json($logs);
	} else {
		$clsStockMeta->insert(array(
			'stock_id' => $stock_id,
			'reg_date' => time(),
			'upd_date' => time()
		));
		$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
	}
	###
	$msg = "_error"; $upd_field = array();
	$more_information[$field] = trim($value);
	if($field=='DT_TT'){
		$upd_field[$field] = $clsISO->convertToNumber($value);
	} else if($field == 'dg_price'){
		$more_information['%s_origin'] = $clsISO->convertToNumber($value);
	} else if(in_array($field, array('status_id','building_id','block_id','agency_id','ms_code'
	,'type_id','bedroom_id','home_direction_id','view_id','floor','code','contract_type_id'))){
		$upd_field[$field] = trim($value);
		if(in_array($field, array('status_id','agency_id'))){
			$logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(), 
				'user_id' => $core->_USER['user_id'],
				'from_id' => $oneStock[$field],
				'to_id' => $value,
				'field' => $field
			);
		}
	} else if(in_array($field, array('total_price_vat','total_price','total_price_early','total_price_progress'
		,'total_price_bank','total_price_bank','total_price_bank_36','total_price_bank_half'))){
		$p_value = $clsISO->processSmartNumber($value);
		if($old_value != $more_information[$field]){
			$logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(), 
				'user_id' => $core->_USER['user_id'],
				'from_value' => $more_information[$field],
				'to_value' => $p_value,
				'field' => $field
			);
		}
		if(in_array($field, array('total_price_vat', 'total_price_early'))){
			if($field == 'total_price_early'){
				$total_price_vat = $more_information['total_price_vat'];
				if(!$total_price_vat && !empty($p_value)){
					$total_price_vat = $p_value;
				}
			} else {
				$total_price_vat = $p_value;
			}
			$upd_field['total_price_vat'] = $total_price_vat;
		}
	}
	// $clsISO->print_pre($logs); die();
	$upd_field['upd_date'] = time();
	$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
	if($clsStock->updateOne($stock_id, $upd_field)){
		$msg = "_success";
		if(!empty($oneStockMeta) && !empty($logs)){
			$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
				'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
				'upd_date' => time()
			));
		}
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Stock","update",['tp' => "info","ms_code" =>$oneStock['ms_code']]);
	}
	// Return
	echo $msg; die();
}
function default_add_template(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	###
	$msg = "_error";
	if($project_id > 0 && $block_id > 0 && $building_id > 0){
		$oneBuilding = $clsProperty->getOne($building_id);
		$more_information = $oneBuilding['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) 
			: array();
		$start_floor = $more_information['start_floor'];
		$number_house = (int) $more_information['number_house'];
		$more_information['is_filled'] = 0;
		$more_information['is_templated'] = 1;
		if($clsProperty->updateOne($building_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			for($i=1; $i<=$number_house; $i++){
				$clsStock->insert(array(
					$clsStock->pkey => $clsStock->getMaxId(),
					'project_id' => $project_id,
					'block_id' => $block_id,
					'building_id' => $building_id,
					'floor' => $start_floor,
					'code' => $clsISO->parseNumber($i),
					'reg_date' => time(),
					'user_id' => $core->_USER['user_id']
				));
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_add_fill(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$msg = "_error";
	if($project_id > 0 && $block_id > 0 && $building_id > 0){
		$oneBuilding = $clsProperty->getOne($building_id);
		$more_information = $oneBuilding['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) 
			: array();
		$start_floor = (int) $more_information['start_floor'];
		$number_house = (int) $more_information['number_house'];
		$number_floor = (int) $more_information['number_floor'];
		#
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
		$list_stocks = $clsStock->getAll("project_id='{$project_id}' and block_id='{$block_id}' 
		and building_id='{$building_id}' and floor='".$more_information['start_floor']."'");
		####
		$more_information['is_filled'] = 1;
		if($clsProperty->updateOne($building_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			for($i=$number_floor; $i>$start_floor; $i--){
				for($k=1; $k<=$number_house; $k++){
					foreach($list_stocks as $key => $val){
						if($val['code'] == $clsISO->parseNumber($k)){
							$clsStock->insert(array(
								$clsStock->pkey => $clsStock->getMaxId(),
								'project_id' => $project_id,
								'block_id' => $block_id,
								'building_id' => $building_id,
								'floor' => $clsISO->parseNumber($i),
								'code' => $clsISO->parseNumber($k),
								'agency_id' => $val['agency_id'],
								'more_information' => $val['more_information'],
								'reg_date' => time(),
								'user_id' => $core->_USER['user_id']
							));
							break;
						}
					}
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_open_import_file(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$smarty->assign('list_projects', $list_projects);
	##
	$tp = Input::post('tp', 'template');
	$stock_type = (int) Input::post('stock_type', 0); //0: Bán, 1: Cho thuê
	$project_id = (int) Input::post('project_id',0);
	$block_id = (int) Input::post('block_id',0);
	$building_id = (int) Input::post('block_id',0);
	$smarty->assign('tp', $tp);
	$smarty->assign('stock_type', $stock_type);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('block_id', $block_id);
	$smarty->assign('building_id', $building_id);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.open_import_file.tpl');
	echo $html; die();
}
function isEmptyRow($row) {
    foreach($row as $cell){
        if (null !== $cell) return false;
    }
    return true;
}
function default_import_file(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$msg = "error";
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		$project_id 	= (int) Input::post('project_id',0);
		$block_id 		= (int) Input::post('block_id',0);
		$stock_type 	= (int) Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
		$building_id 	= (int) Input::post('building_id',0);
		// $clsISO->print_pre($stock_type); die();
		if(is_uploaded_file($_FILES['attachment']['tmp_name'])){
			$target_dir = ROOTPATH."/tmp/";
			$file_ext = explode('.',basename($_FILES["attachment"]["name"]));
			$file_ext = strtolower(end($file_ext));
			$target_file = $target_dir . time().'.'.$file_ext;
			if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
				$html = '';
				$inputFileName = $target_file;
				require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
				require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
				} catch(Exception $e) {
					die($e->getMessage());
				}
				$worksheet = $objPHPExcel->getActiveSheet();
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow();
				$highestColumn      = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				###
				$index = 0; $tblData =array();
				for($row = 1; $row <= $highestRow; ++ $row) {
					$rowData = $worksheet->rangeToArray('A'. $row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
					if(isEmptyRow(reset($rowData))) { continue; } // skip empty row
					for($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						if(PHPExcel_Shared_Date::isDateTime($cell)){
							if(!empty($cell)){
								$date = trim($cell->getValue());
								$date = PHPExcel_Shared_Date::ExcelToPHPObject();
								$tblData[$index][] = date_format($date,'d/m/Y');
							}else{
								$tblData[$index][] = trim($cell->getValue());
							}
						}else{
							$tblData[$index][] = trim($cell->getValue());
						}
					}
					++$index;
				}
				@unlink($inputFileName);
				// $clsISO->print_pre($tblData); die();
				$html = '<div class="modal-dialog modal-xl" style="width:calc(100vw - 100px)">
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
								<div class="d-flex align-items-center justify-content-between">
									<div class="form-inline">
										'.($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE?'
										<label class="col-form-label">Hành động:</label>
										<div class="radio mr-2">
											<input name="opt_over" id="Insert" type="radio" value="Insert">
											<label for="Insert">Thêm mới</label>
										</div>
										<div class="radio">
											<input name="opt_over" id="Update" checked type="radio" value="Update">
											<label for="Update">Cập nhật</label>
										</div>':'').'
										<div class="checkbox">
											<input name="opt_fund" id="Fund" type="checkbox" value="1">
											<label for="Fund">&nbsp;Quỹ mới</label>
										</div>
									</div>
									<div class="d-flex">
										<input type="hidden" name="stock_type" value="'.$stock_type.'" />
										<input type="hidden" name="project_id" value="'.$project_id.'" />
										<button type="button" class="btn btn-default mr-2" data-dismiss="modal">'.$core->get_Lang('Close').'</button>
										<button type="button" class="btn btn-success" uid="'.$uid.'" project_id="'.$project_id.'" block_id="'.$block_id.'" stock_type="'.$stock_type.'" building_id="'.$building_id.'" onClick="do_import_file(this, event)"><span>Cập nhật bảng hàng</span></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>';
				// Return
				echo 'OK|||'.$html;
				die();
			}
		}
	}
}
function default_do_import_file(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$uid = Input::post('uid');
	$block_id = (int) Input::post('block_id', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$building_id = (int) Input::post('building_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$columns = Input::post('columns', array());
	$opt_fund = (int) Input::post('opt_fund', 0);
	$opt_over = Input::post('opt_over','Update');
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$tblData = array();
	$cachedName = sprintf('%s.json', $uid);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
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
	if($error_field == 0){
		$total_results = 0;
		if(!empty($tblData)){
			$total_record = @count($tblData);
			if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){ // Cao tầng
				for($i=1; $i<$total_record; $i++){
					$ms_code = "";
					foreach($columns as $key => $p_field){
						if($p_field=='ms_code'){
							$ms_code = $tblData[$i][$key];
							break;
						}
					}
					if(empty($ms_code)) continue;
					$field = "{$clsStock->pkey},`logs`,`more_information`,`status_id`,`agency_id`";
					$oneStock = $clsStock->getByCond("`ms_code`='{$ms_code}'", $field);
					if($opt_over=='Update'){
						if(!empty($oneStock)){
							$uid = $clsISO->getUniqid();
							$upd_field = $logs = $more_information = array();
							$logs = $clsISO->to_array_json($oneStock['logs']);
							$more_information = $clsISO->to_array_json($oneStock['more_information']);
							foreach($columns as $key => $p_field){
								if(!empty($p_field) && !empty($tblData[$i][$key])){
									if($p_field=='block_id'){
										$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='building_id'){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BUILDING' 
										and (property_code='".$tblData[$i][$key]."' or slug='".$core->replaceSpace($tblData[$i][$key])."')");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='view_id'){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_VIEW' 
										and slug='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='status_id'){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
										and slug='".$core->replaceSpace($tblData[$i][$key])."'", $clsProperty->pkey);
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='type_id'){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE' 
										and (`property_code`'".$tblData[$i][$key]."' or slug='".$core->replaceSpace($tblData[$i][$key])."')");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='bedroom_id'){
										$tblData[$i][$key] = preg_replace('/\s+/', '', $tblData[$i][$key]);
										$tmp = $clsProperty->getByCond("`property_type`='_BEDROOM' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='home_direction_id'){
										$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='agency_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("is_trash=0 and property_type='_AGENCY' 
										and (`property_code`='".$tblData[$i][$key]."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."')", $clsProperty->pkey);
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									}
									$more_information[$p_field] = $tblData[$i][$key];
									if(!in_array($p_field, array('DT_Tim','total_price','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_half'))){
										if($p_field=='DT_TT' && !empty($tblData[$i][$key])){
											$upd_field[$p_field] = $clsISO->toNumber(trim($tblData[$i][$key]));
										} else if($p_field=='total_price_vat' && !empty($tblData[$i][$key])){
											$upd_field[$p_field] = $clsISO->convertToNumber(trim($tblData[$i][$key]));
										} else {
											$upd_field[$p_field] = trim($tblData[$i][$key]);
										}
										if(in_array($p_field, ['agency_id','status_id'])){
											if($oneStock[$p_field] != trim($tblData[$i][$key])){
												$logs[$clsISO->getUniqid()] = array(
													'field' => $p_field,
													'reg_date' => time(),
													'user_id' => $core->_USER['user_id'],
													'from_id' => $oneStock[$p_field],
													'to_id' => trim($tblData[$i][$key])
												);
											}
										}
									} 
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_half'))){
										if(isset($tblData[$i][$key]) && !empty($tblData[$i][$key])){
											if($p_field == 'total_price_early'){
												$upd_field['total_price_vat'] = $clsISO->convertToNumber($tblData[$i][$key]);
											}
											$logs[$clsISO->getUniqid()] = array(
												'field' => $p_field,
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information[$p_field],
												'to_value' => trim($tblData[$i][$key])
											);
										}
									}
								}
							}
							#- End Logs
							$upd_field['upd_date'] = time();
							$upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
							if($opt_fund==1){
								$price_arrs = array('total_price_early','total_price_progress'
								,'total_price_bank','total_price_bank_half');
								foreach($price_arrs as $g_field){
									$more_information[$g_field] = "";
								}
							}
							$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
							if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
								$total_results += 1;
							}
						}
					} else if($opt_over=='Insert'){
						if(!empty($oneStock)){
							// Exists
						} else {
							$uid = $clsISO->getUniqid();
							$stock_id = $clsStock->getMaxId();
							$insert_field = $more_information = $logs = array();
							foreach($columns as $key => $p_field){
								if(!empty($p_field)){
									if($p_field=='block_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='building_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("is_trash=0 and property_type='_BUILDING' 
										and (`property_code`='".$tblData[$i][$key]."' 
											or `slug`='".$core->replaceSpace($tblData[$i][$key])."'
										)");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='view_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_VIEW' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='type_id'){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='status_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='bedroom_id' && !empty($tblData[$i][$key])){
										$tblData[$i][$key] = preg_replace('/\s+/', '', $tblData[$i][$key]);
										$tmp = $clsProperty->getByCond("`property_type`='_BEDROOM' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='home_direction_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
										and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									} else if($p_field=='agency_id' && !empty($tblData[$i][$key])){
										$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_AGENCY' 
										and (`property_code`='".$tblData[$i][$key]."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."')");
										$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									}
									$more_information[$p_field] = $tblData[$i][$key];
									if(!in_array($p_field, array('DT_Tim','total_price','total_price_early',
										'total_price_progress','total_price_bank','total_price_bank_half'))){
										if($p_field=='DT_TT' && !empty($tblData[$i][$key])){
											$insert_field[$p_field] = $clsISO->toNumber($tblData[$i][$key]);
										} if($p_field=='total_price_vat' && !empty($tblData[$i][$key])){
											$insert_field[$p_field] = $clsISO->convertToNumber($tblData[$i][$key]);
										} else {
											$insert_field[$p_field] = $tblData[$i][$key];
										}
										if(!in_array($p_field, ['agency_id','status_id'])){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_id' => 0,
												'to_id' => trim($tblData[$i][$key]),
												'field' => $p_field
											);
										}
									}
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_half'))){
										if(isset($tblData[$i][$key]) && !empty($tblData[$i][$key])){
											if($p_field == 'total_price_early'){
												$insert_field['total_price_vat'] = $clsISO->convertToNumber($tblData[$i][$key]);
											}
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_value' => 0,
												'to_value' => trim($tblData[$i][$key]),
												'field' => $p_field
											);
										}
									}
								}
							}
							if(isset($insert_field['building_id']) 
								&& !empty($insert_field['building_id']) 
								&& empty($insert_field['block_id'])){
								$block_id = $clsProperty->getOneField('for_id', $insert_field['building_id']);
								$insert_field['block_id'] = $block_id;
							}
							$insert_field[$clsStock->pkey] = $stock_id;
							$insert_field['project_id'] = $project_id;
							# End Logs
							$insert_field['reg_date'] = time();
							$insert_field['upd_date'] = time();
							$insert_field['stock_type'] = $stock_type;
							$insert_field['user_id'] = $core->_USER['user_id'];
							$insert_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
							$insert_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
							$clsStock->insert($insert_field);
							$total_results++;
						}
					}
				}
			} else if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE) { // Thấp tầng
				for($i=$total_record; $i>=1; $i--){
					$ms_code = "";
					foreach($columns as $key => $p_field){
						if($p_field=='ms_code'){
							$ms_code = $tblData[$i][$key];
							break;
						}
					}
					if(empty($ms_code)) continue;
					$field = "{$clsStock->pkey},`logs`,`more_information`";
					$oneStock = $clsStock->getByCond("ms_code='{$ms_code}' and `stock_type`='".$stock_type."'", $field);
					// $clsISO->print_pre($oneStock); die();
					if(!empty($oneStock)){ // Cập nhật
						$uid = $clsISO->getUniqid();
						$upd_field = $more_information = array();
						$logs = $clsISO->to_array_json($oneStock['logs']);
						$more_information = $clsISO->to_array_json($oneStock['more_information']);
						foreach($columns as $key => $p_field){
							if(!empty($p_field) && !empty($tblData[$i][$key])){
								if($p_field=='block_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' and `for_id`='{$project_id}' 
									and (`property_code`='".$tblData[$i][$key]."' or `slug`='".$core->replaceSpace($tblData[$i][$key])."')");
									$block_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									$tblData[$i][$key] = $block_id;
								} else if($p_field=='type_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE_VILLA' 
									and (`property_code`='".$tblData[$i][$key]."' 
										or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."'
									)");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='agency_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' 
									and (`property_code`='".$tblData[$i][$key]."' 
										or `slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
										or `slug` like '%".$core->replaceSpace($tblData[$i][$key])."%'
									)");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_STATUS' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='contract_type_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_CONTRACT_TYPE' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='contract_subject_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_CONTRACT_SUBJECT' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='invest_fund_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_INVEST_FUND' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='sale_status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_SALE_STATUS' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if(($p_field=='agent_lock_id' || $p_field=='deposit_agent_id') && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$core->replaceSpace($tblData[$i][$key])."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."'
									)");
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
								} else if(($p_field=='bank_second_id' || $p_field=='bank_id')  && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_BANK' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='home_direction_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_DIRECTION' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								}
								$logs[$uid][$p_field] = $tblData[$i][$key];
								$more_information[$p_field] = $tblData[$i][$key];
								if(!in_array($p_field, array('DT_Tim','total_price','total_price_vat','csbh','TCBG','contract_subject_id','invest_fund_id'
									,'bank_second_id','bank_id','sale_status_id','agent_lock_id','deposit_agent_id','total_price_early','total_price_progress'
									,'total_price_bank','total_price_bank_36','cs_policy_ns','price_temporary_ns','notes'))){
									$upd_field[$p_field] = $tblData[$i][$key];
								} else {
									if($p_field=='DT_TT' && !empty($tblData[$i][$key])){
										$upd_field[$p_field] = $clsISO->toNumber($tblData[$i][$key]);
									} else if($p_field=='total_price_vat' && !empty($tblData[$i][$key])){
										$upd_field[$p_field] = $clsISO->processSmartNumber($tblData[$i][$key]);
									}
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_36'))){
										if(!empty($tblData[$i][$key])){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information[$p_field],
												'to_value' => $clsISO->processSmartNumber($tblData[$i][$key]),
												'field' => $p_field
											);
										}
									}
								}
							}
						}
						# Logs
						$logs[$uid]['reg_date'] = time();
						$logs[$uid]['user_id'] = $core->_USER['user_id'];
						# End Logs
						$upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
						$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						$upd_field['upd_date'] = time();
						// $clsISO->print_pre($upd_field); die();
						if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
							$total_results += 1;
						}
					} else { // Thêm mới
						$uid = $clsISO->getUniqid();
						$stock_id = $clsStock->getMaxId();
						$insert_field = $more_information = $logs = array();
						foreach($columns as $key => $p_field){
							if(!empty($p_field)){
								if($p_field=='block_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' and `for_id`='{$project_id}' 
									and (`property_code`='".$tblData[$i][$key]."' or `slug`='".$core->replaceSpace($tblData[$i][$key])."')");
									$block_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
									$tblData[$i][$key] = $block_id;
								} else if($p_field=='type_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_TYPE_VILLA' and (`property_code`='".$tblData[$i][$key]."' 
										or slug_vn='".$core->replaceSpace($tblData[$i][$key])."' 
										or `slug`='".$core->replaceSpace($tblData[$i][$key])."')");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='agency_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_AGENCY' 
									and (`property_code`='".$tblData[$i][$key]."' 
										or `slug_vn`='".$tblData[$i][$key]."' 
										or `slug` like '%".$core->replaceSpace($tblData[$i][$key])."%'
									)");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='contract_type_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`property_type`='_CONTRACT_TYPE' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='contract_subject_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_CONTRACT_SUBJECT' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='invest_fund_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_INVEST_FUND' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='sale_status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_SALE_STATUS' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if(($p_field=='agent_lock_id' || $p_field=='deposit_agent_id') 
									&& !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_AGENCY' and (
										slug_vn='".$core->replaceSpace($tblData[$i][$key])."' 
										or slug='".$core->replaceSpace($tblData[$i][$key])."')");
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
								} else if(($p_field=='bank_second_id' || $p_field=='bank_id') 
									 && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_BANK' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
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
								} else if($p_field=='home_direction_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_DIRECTION' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								}
								$more_information[$p_field] = $tblData[$i][$key];
								if(!in_array($p_field, array('DT_Tim','total_price','csbh','TCBG','contract_subject_id','invest_fund_id'
									,'bank_second_id','bank_id','sale_status_id','agent_lock_id','deposit_agent_id','total_price_early'
									,'total_price_progress','total_price_bank','total_price_bank_36','cs_policy_ns','price_temporary_ns','notes','contract_type_id'))){
									$insert_field[$p_field] = $tblData[$i][$key];
								} else {
									if($p_field=='DT_TT' && !empty($tblData[$i][$key])){
										$insert_field[$p_field] = $clsISO->toNumber($tblData[$i][$key]);
									} else if($p_field=='total_price_vat' && !empty($tblData[$i][$key])){
										$insert_field[$p_field] = $clsISO->processSmartNumber($tblData[$i][$key]);
									}
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_half','total_price_bank_36'))){
										if(isset($tblData[$i][$key]) && !empty($tblData[$i][$key])){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $core->_USER['user_id'],
												'from_value' => $more_information[$p_field],
												'to_value' => $clsISO->processSmartNumber($tblData[$i][$key]),
												'field' => $p_field
											);
										}
									}
								}
							}
						}
						$insert_field[$clsStock->pkey] = $stock_id;
						$insert_field['project_id'] = $project_id;
						$insert_field['stock_type'] = $stock_type;
						//$more_information['logs'] = $logs;
						$insert_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						$insert_field['reg_date'] = time();
						$insert_field['upd_date'] = time();
						$insert_field['user_id'] = $core->_USER['user_id'];
						if($block_id > 0){
							if($clsStock->insert($insert_field)){
								$total_results++;
							}
						}
					}
				}
			} else if($stock_type==_STOCK_TYPE_LEASING){
				for($i=1; $i<$total_record; $i++){
					$ms_code = "";
					foreach($columns as $key => $p_field){
						if($p_field=='ms_code'){
							$ms_code = $tblData[$i][$key];
							break;
						}
					}
					if(empty($ms_code)) continue;
					$field = "{$clsStock->pkey},`logs`,`more_information`";
					$oneStock = $clsStock->getByCond("ms_code='{$ms_code}'", $field);
					if(!empty($oneStock)){
					} else {
						$uid = $clsISO->getUniqid();
						$stock_id = $clsStock->getMaxId();
						$insert_field = $more_information = $logs = array();
						foreach($columns as $key => $p_field){
							if(!empty($p_field)){
								if($p_field=='block_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BLOCK' 
									and (`property_code`='".$tblData[$i][$key]."' or `slug`='".$core->replaceSpace($tblData[$i][$key])."')");
									if(!empty($tmp)){
										$project_id = $tmp['for_id'];
										$block_id = $tmp[$clsProperty->pkey];
									} 
									$tblData[$i][$key] = $block_id;
								} else if($p_field=='status_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("is_trash=0 and property_type='_STATUS' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								} else if($p_field=='service_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("is_trash=0 and property_type='_STOCK_SERVICE' and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									if(!empty($tmp)){
										$service_id = $tmp[$clsProperty->pkey];
									} else {
										$service_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $service_id,
											'property_type' => '_STOCK_SERVICE',
											'title' => $tblData[$i][$key],
											'slug' => $core->replaceSpace($tblData[$i][$key]),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => $core->_USER['user_id'],
											'user_id_update' => $core->_USER['user_id'],
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$tblData[$i][$key] = $service_id;
								} else if($p_field=='home_direction_id' && !empty($tblData[$i][$key])){
									$tmp = $clsProperty->getByCond("property_type='_DIRECTION' 
									and slug='".$core->replaceSpace($tblData[$i][$key])."'");
									$tblData[$i][$key] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
								}
								$more_information[$p_field] = $tblData[$i][$key];
								if(!in_array($p_field, array('DT_TT','DT_Tim','total_price','total_price_vat','DT_Opt1','DT_Opt2'
								,'DT_Opt3','DT_Build_Floor_1','DT_Build_Floor_2','DT_Build_Floor_3','DT_Build_Floor_4','DT_Build_Floor_5','DT_Build_Floor_6','free_deadline'
								,'dg_price','service_fee','construct_Opt','support_price'))){
									$insert_field[$p_field] = $tblData[$i][$key];
								}	
							}
						}
						$insert_field[$clsStock->pkey] = $stock_id;
						$insert_field['project_id'] = $project_id;
						//$more_information['logs'] = $logs;
						$insert_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						$insert_field['reg_date'] = time();
						$insert_field['upd_date'] = time();
						$insert_field['user_id'] = $core->_USER['user_id'];
						//$clsISO->print_pre($insert_field); die();
						$clsStock->insert($insert_field);
						$total_results++;
					}
				}
			}
		}
		// Return
		echo 'success|||'.$total_results;
		die();
	} else {
		echo 'error_field';
		die();
	}
}
function default_do_action(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$msg = "_success";
	$cmd = Input::post('cmd');
	$stock_ids = Input::post('stock_ids', array());
	if($cmd=='delete'){
		if(!empty($stock_ids)){
			$clsStock->deleteByCond("{$clsStock->pkey} in (".implode(',', $stock_ids).")");
		} else {
			$msg = "_error";
		}
	} else if($cmd=='update'){
		$smarty->assign('stock_ids', $stock_ids);
		$list_website = array('user.fh','MOC','partner.fh');
		$smarty->assign('list_website', $list_website);
		// Return
		$smarty->assign('core', $core);
		$smarty->assign('clsISO', $clsISO);
		$smarty->assign('clsProperty', $clsProperty);
		$html = $core->build('_ajax.update.tpl');
		$msg.= '|||'.$html;
	}
	// Return
	echo ($msg); die();
}
function default_update_stock_field(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$msg = '_error';
	$stock_ids = Input::post('stock_ids');
	$stock_field = Input::post('stock_field');
	// $clsISO->print_pre($stock_field); die();
	$set = $set_more =  array();
	if(!empty($stock_field)){
		foreach($stock_field as $key => $val){
			$break = false;
			if($val['p_field'] == 'hide_price_sheets'){
				$set_more[$val['p_field']] = $val['p_value'];
			} else if($val['p_field']=='show_website'){
				$break = true;
				$p_value = "";
				if(!empty($val['p_value'])){
					$p_value = $clsISO->makeSlashListFromArray($val['p_value']);
				}
				$set[$val['p_field']] = $p_value;
			} else {
				if(!empty($val['p_field'])){
					$set[$val['p_field']] = $val['p_value'];
				}
			}
		}
		if(!empty($stock_ids) && (!empty($set) || !empty($set_more))){
			$msg = '_success';
			foreach($stock_ids as $stock_id){
				$upd_field = $set;
				if($break == false){
					$oneStock = $clsStock->getOne($stock_id, "logs,more_information");
					$logs = $oneStock['logs'];
					$more_information = $oneStock['more_information'];
					$logs = $clsISO->to_array_json($logs);
					$more_information = $clsISO->to_array_json($more_information);
					# Logs
					$uid = $clsISO->getUniqid();
					$logs[$uid]['reg_date'] = time();
					$logs[$uid]['user_id'] = $core->_USER['user_id'];
					# End Logs
					if(!empty($set)){
						foreach($set as $okey => $oval){
							$more_information[$okey] = $oval;
							$logs[$uid][$okey] = $oval;
						}
					}
					if(!empty($set_more)){
						foreach($set_more as $okey => $oval){
							$more_information[$okey] = $oval;
						}
					}
					$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
					//$clsISO->print_pre($more_information); die();
				}
				$upd_field['upd_date'] = time();
				//$upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
				$clsStock->updateOne($stock_id, $upd_field);
				# Dual-write: nếu bulk có sửa show_website thì mirror sang junction default_stock_site
				if(isset($set['show_website'])){
					$clsStockSite = isset($clsStockSite) ? $clsStockSite : new StockSite();
					$clsStockSite->syncFromShowWebsite($stock_id, $set['show_website']);
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_search_in_template_storage(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	##
	$stock_id = (int) Input::post('stock_id', 0);
	$code = Input::post('code');
	$field = "`building_id`,`floor`,`more_information`";
	$oneStock = $clsStock->getOne($stock_id, $field);
	$building_id = $oneStock['building_id'];
	###
	$oneBuilding = $clsProperty->getOne($building_id, "`property_code`,`more_information`");
	$property_code = $oneBuilding['property_code'];
	$more_information = $oneBuilding['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$template = isset($more_information['template']) && !empty($more_information['template']) 
		? $more_information['template'] : array();
	$stock_template = isset($more_information['stock_template']) && !empty($more_information['stock_template']) 
		? $more_information['stock_template'] : "";
	###
	$result = '_error'; $oneTemplate = array();
	if(!empty($template)){
		foreach($template as $key => $val){
			if($val['code'] == $code){
				$result = '_success';
				$oneTemplate = $val;
				break;
			}
		}
	}
	if($result=='_success' && !empty($stock_template) && !empty($property_code)){
		$pos = strpos($stock_template, '[Tang]');
		$code_f = is_numeric($code) ? $clsISO->parseNumber($code) : $code;
		if($pos !== false) {
			if(!empty($oneStock['floor'])){
				$ms_code = str_replace('[MaToa]',$property_code,$stock_template);
				$ms_code = str_replace('[Tang]',$clsISO->parseNumber($floor),$ms_code);
				$ms_code = str_replace('[CanHo]',$code_f,$ms_code);
				$oneTemplate['ms_code'] = $ms_code;
			}
		} else {
			$ms_code = str_replace('[MaToa]',$property_code,$stock_template);
			$ms_code = str_replace('[CanHo]',$code_f,$ms_code);
			$oneTemplate['ms_code'] = $ms_code;
		}
	}
	if($result=='_success'){
		$more_information = $oneStock['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) 
			: array();
		$arr_fields = array('bedroom_id','home_direction_id','view_id','ms_code');
		$arr_more_fields = array('DT_TT','DT_Tim','bedroom_id','home_direction_id','view_id','ms_code');
		$upd_date = array();
		foreach($arr_more_fields as $field){
			if(in_array($field, $arr_fields)){
				$upd_date[$field]= $oneTemplate[$field];
			}
			$more_information[$field] = $oneTemplate[$field];
		}
		$upd_date['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
		$clsStock->updateOne($stock_id, $upd_date);
	}
	// Return
	echo json_encode(array_merge($oneTemplate, array(
		'result' => $result
	))); die();
}
function default_open_stock(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsUser = new User();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProfile = new Profile();
	$titlePgae = 'Cập nhật thông tin nhà/căn hộ';
	$smarty->assign('titlePgae', $titlePgae);
	#
	$list_logs = array();
	$stock_id = Input::post('stock_id',0);
	$oneStock = $clsStock->getOne($stock_id);
	$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'");
	if(!empty($oneStockMeta)){
		$logs = $oneStockMeta['logs'];
		$list_logs = $clsISO->to_array_json($logs);
	}
	$more_information = $oneStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	if(!empty($list_logs)){
		$arr_user_cached = array();
		foreach($list_logs as $key => $val){
			$user_id = $val['user_id'];
			if(!isset($arr_user_cached[$user_id])){
				$arr_user_cached[$user_id] = $clsUser->getFullName($user_id);
				$list_logs[$key]['full_name'] = $arr_user_cached[$user_id];
			} else {
				$list_logs[$key]['full_name'] = $arr_user_cached[$user_id];
			}
		}
	}
	// $clsISO->print_pre($list_logs); die();
	$list_website = array('user.fh','MOC','partner.fh');
	$show_website = $oneStock['show_website'];
	$show_website_arr = $clsISO->getArrayByTextSlash($show_website);
	$smarty->assign('list_website', $list_website);
	$smarty->assign('show_website_arrs', $show_website_arr);
	$list_property = isset($more_information['properties']) && !empty($more_information['properties']) 
		? $more_information['properties'] : array();
	// $clsISO->print_pre($list_logs); die();
	$smarty->assign('stock_id', $stock_id);
	$smarty->assign('oneStock', $oneStock);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('list_property', $list_property);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_logs', $list_logs);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.open_stock.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_pop_save_stock(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	###
	$stock_id = (int) Input::post('stock_id',0);
	$properties = Input::post('properties'); // Thuộc tính
	$show_website = Input::post('show_website'); // Hiển thị ở website
	$markup_price = (int) Input::post('markup_price', 0); // Chênh hoa hồng
	$set = array(); $msg = "_error";
	if($stock_id > 0){
		$field = "{$clsStock->pkey},more_information,ms_code";
		$oneStock = $clsStock->getOne($stock_id, $field);
		$more_information = $oneStock['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['markup_price'] = $markup_price;
		if(!empty($properties)){
			$more_information['properties'] = $properties;
		} else {
			$more_information['properties'] = array();
		}
		if($clsStock->updateOne($stock_id, array(
			'show_website' => $clsISO->makeSlashListFromArray($show_website),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			# Dual-write: đồng bộ junction default_stock_site (mirror show_website)
			$clsStockSite = new StockSite();
			$clsStockSite->syncFromShowWebsite($stock_id, $clsISO->makeSlashListFromArray($show_website));
			#activity log		
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Stock","update",['tp' => "info","ms_code" =>$oneStock['ms_code']]);
		}
	}
	// Return
	echo $msg; die();
}
function default_toggle_stock_site(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockSite = new StockSite();
	$status = '_error'; $state = 0;
	$stock_id  = (int) Input::post('stock_id', 0);
	$site_code = trim(Input::post('site_code'));
	$want_on   = ((int) Input::post('state', 0) === 1);
	$allow = array('user.fh','MOC','partner.fh'); // chỉ cho phép site hợp lệ
	if($stock_id > 0 && in_array($site_code, $allow, true)){
		$show_website = $clsStock->getOneField('show_website', $stock_id);
		$arrs = !empty($show_website) ? $clsISO->getArrayByTextSlash($show_website) : array();
		$arrs = array_values(array_diff($arrs, array($site_code))); // bỏ site này trước cho sạch
		if($want_on){ $arrs[] = $site_code; }
		$new_show = $clsISO->makeSlashListFromArray($arrs);
		if($clsStock->updateOne($stock_id, array('show_website' => $new_show))){
			$clsStockSite->syncFromShowWebsite($stock_id, $new_show); // mirror junction
			$status = '_success';
			$state  = $want_on ? 1 : 0;
			$clsActivityLog = new ActivityLog();
			$clsActivityLog->addActivityLog("Stock","update",['tp' => "info","show_website" => $new_show]);
		}
	}
	echo json_encode(array('status' => $status, 'state' => $state, 'site_code' => $site_code)); die();
}
function default_open_stock_price(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$stock_id = Input::post('stock_id',0);
	$toId = isset($_POST['uid']) && !empty($_POST['uid']) 
		? $_POST['uid'] : $clsISO->getUniqid();
	$smarty->assign('toId', $toId);
	$smarty->assign('stock_id', $stock_id);
	###
	$more_information = $clsStock->getOneField("more_information", $stock_id);
	$more_information = $clsISO->to_array_json($more_information);
	###
	$list_sheets = array();
	$price_sheets = $core->get_field($more_information, "price_sheets", []);
	// clsISO->print_pre($price_sheets); die();
	if(!empty($price_sheets) && array_key_exists($toId, $price_sheets)){
		$csbh = isset($price_sheets[$toId]['csbh']) ? $price_sheets[$toId]['csbh'] : "";
		$list_sheets = $price_sheets[$toId]['sheets'];
	}
	$smarty->assign('core', $core);
	$smarty->assign('csbh', $csbh);
	$smarty->assign('list_sheets', $list_sheets);
	// Return
	$html = $core->build('_ajax.open_stock.price.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_stock_price_upload_file(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$image = "";
	if(!empty($_FILES['image']['name'])){
		if(is_uploaded_file($_FILES['image']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			$image = $clsUploadFile->uploadItem($_FILES["image"],"/PTG",EXTENSION_FILE_UPLOAD);
			if(!empty($image) && file_exists(ROOTPATH . $image)){
				// Set the file metadata for drive
				$title = 'FH_'.time().'_'.$_FILES["image"]["name"];
				$mimeType = $_FILES["image"]["type"];
				$clsGoogleDrive = new GoogleDrive();
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image);
				$image = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				@unlink(ROOTPATH . $image);
			}
		}	
	}
	// return
	echo $image; die();
}
function default_stock_price_addline(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$toId = Input::post('toId');
	$stock_id = (int) Input::post('stock_id', 0);
	$uid = $clsISO->getUniqid();
	$html = '<tr class="tr_stock_price_'.$toId.' tr_stock_price_'.$uid.'">
		<td class="text-left">
			<input type="text" placeholder="Nhập tên..." name="stock_price['.$uid.'][title]" 
			class="form-control required" maxlength="255" />
		</td>
		<td class="text-left">
			<div class="input-group">
				<input type="text" placeholder="Nhập ảnh..." name="stock_price['.$uid.'][image]" class="form-control required stock_price_image_hidden_'.$uid.'" maxlength="255" />
				<div class="input-group-btn">
					<button type="button" toId="'.$toId.'" uid="'.$uid.'" onClick="stock_price_select_file(this, event)" stock_id="'.$stock_id.'" class="btn btn-default">'.$core->makeIcon('upload','Chọn file').'</button>
				</div>
			</div>
		</td>
		<td class="text-center">
			<button type="button" onClick="stock_price_delete_line(this, event)" uid="'.$uid.'" toId="'.$stock_id.'" class="btn btn-sm btn-default">'.$core->makeIcon('trash').'</button>
		</td>
	</tr>';
	// return
	echo $html; die();
}
function default_pop_save_stock_price(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$uid = Input::post('uid');
	$csbh = Input::post('csbh');
	$stock_id = (int) Input::post('stock_id', 0);
	$stock_price = Input::post('stock_price');
	$msg = "_error";
	$oneStock = $clsStock->getOne($stock_id, "more_information,ms_code");
	$more_information	= $oneStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();
	$price_sheets = $core->get_field($more_information, "price_sheets", []);
	if(!empty($uid)){
		$price_sheets[$uid]['csbh'] = $clsISO->toTime($csbh);
		$price_sheets[$uid]['sheets'] = $stock_price;
		$price_sheets[$uid]['upd_date'] = time();
		$price_sheets[$uid]['user_update_id'] = $core->_USER['user_id'];
	} else {
		$uid = $clsISO->getUniqid();
		$price_sheets[$uid]['sheets'] = $stock_price;
		$price_sheets[$uid]['reg_date'] = time();
		$price_sheets[$uid]['upd_date'] = time();
		$price_sheets[$uid]['csbh'] = $clsISO->toTime($csbh);
		$price_sheets[$uid]['user_id'] = $core->_USER['user_id'];
		$price_sheets[$uid]['user_update_id'] = $core->_USER['user_id'];
	}
	$more_information['hide_price_sheets'] = 0;
	$more_information['price_sheets'] = $price_sheets;
	// $clsISO->print_pre($more_information); die();
	if($clsStock->updateOne($stock_id, array(
		'upd_date' => time(),
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = '_success';
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Stock","update",['tp' => "PTG","ms_code" =>$oneStock['ms_code']]);
	}
	// return
	echo $msg; die();
}
function default_delete_stock_price(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	###
	$uid = Input::post('uid');
	$stock_id = (int) Input::post('stock_id', 0);
	$more_information = $clsStock->getOneField('more_information', $stock_id);
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();
	$price_sheets = $core->get_field($more_information, "price_sheets", []);
	if(!empty($uid) && !empty($price_sheets) && array_key_exists($uid, $price_sheets)){
		unset($price_sheets[$uid]);
	}
	$more_information['price_sheets'] = $price_sheets;
	// $clsISO->print_pre($more_information); die();
	if($clsStock->updateOne($stock_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = '_success';
	}
	// return
	echo $msg; die();
}
function default_load_stock_price(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$stock_id = (int) Input::post('stock_id', 0);
	###
	$more_information = $clsStock->getOneField('more_information', $stock_id);
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();
	$price_sheets = $core->get_field($more_information, "price_sheets", []);
	// $clsISO->print_pre($price_sheets); die();
	$html = '';
	if(!empty($price_sheets)){
		$price_sheets = array_reverse($price_sheets);
		foreach($price_sheets as $uid => $val){
			$sheets = $val['sheets'];
			$html_sheets = array();
			if(!empty($sheets)){
				$total_sheets = count($sheets);
				foreach($sheets as $okey => $oval){
					$html_sheets[] = $oval['title'];
				}
			} else {
				$total_sheets = 0;
			}
			$html.= '<tr>
				<td>'.$clsISO->convertTimeToText($val['upd_date'], true).'</td>
				<td>+'.$total_sheets.' phiên bản('.implode(',',$html_sheets).')</td>
				<td><button uid="'.$uid.'" stock_id="'.$stock_id.'" onClick="open_stock_price(this, event)" 
					class="btn btn-xs btn-default">'.$core->makeIcon('pencil').'</button>
					<button uid="'.$uid.'" stock_id="'.$stock_id.'" onClick="delete_stock_price(this, event)" class="btn btn-xs btn-default">
						'.$core->makeIcon('trash').'
					</button></td>
			</tr>';
		} 
	} else {
		$html.= '<tr>
			<td class="text-center" colspan="3">Chưa có PTG</td>
		</tr>';
	}
	// Return
	echo $html; die();
}
function default_delete_stock_building(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	##
	$cond = "`project_id`='{$project_id}'";
	if($block_id > 0) $cond.= " and `block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " and `building_id`='{$building_id}'";
	$clsStock->deleteByCond($cond);
	// return
	echo 1; die();
}
function default_do_stock_sold(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	###
	$cond = "`project_id`='{$project_id}'";
	if($block_id > 0) $cond.= " and `block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " and `building_id`='{$building_id}'";
	$list_stocks = $clsStock->getAll($cond, "{$clsStock->pkey},more_information");
	// $clsISO->print_pre($list_stocks); die();
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
			$clsStock->updateOne($val[$clsStock->pkey], array(
				'upd_date' => time(),
				'status_id' => _STOCK_STATUS_SOLD_ID,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	// return
	echo 1; die();
}
function default_do_stock_once_sold(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	###
	$msg = "_error";
	$stock_code = Input::post('stock_code');
	$project_id = (int) Input::post('project_id',0);
	if(!empty($stock_code)){
		$field = "{$clsStock->pkey},more_information";
		$tmp = $clsStock->getAll("`project_id`='{$project_id}' and `ms_code`='{$stock_code}'", $field);
		if(!empty($tmp)){
			$more_information = $tmp[0]['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
			if($clsStock->updateOne($tmp[0][$clsStock->pkey], array(
				'upd_date' => time(),
				'status_id' => _STOCK_STATUS_SOLD_ID,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_add_stock_line(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$html = '<tr class="tr_stock_line_'.$uid.' tr_stock_line_'.$stock_id.'">
		<td class="text-left">
			<input type="text" placeholder="Nhập tiêu đề..." name="properties['.$uid.'][title]" 
			class="form-control required" maxlength="255" />
		</td>
		<td class="text-left">
			<input type="text" placeholder="Nhập giá trị..." name="properties['.$uid.'][content]" class="form-control required" maxlength="255" />
		</td>
		<td class="text-center">
			<button type="button" onClick="$Core.stock.delete_stock_line(this, event)" uid="'.$uid.'" stock_id="'.$stock_id.'" class="btn py-px-9 btn-default">'.$core->makeIcon('trash').'</button>
		</td>
	</tr>';
	// return
	echo $html; die();
}
function default_layout_upload_file(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error";
	$tofield = Input::post('tofield', "layout");
	$stock_id = (int) Input::post('stock_id', 0);
	if(!empty($_FILES['layout_file']['name'])){
		if(is_uploaded_file($_FILES['layout_file']['tmp_name'])){
			$more_information = $clsStock->getOneField('more_information', $stock_id);
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			// $clsISO->print_pre($more_information); die();
			$clsUploadFile = new UploadFile();
			$layout_file = $clsUploadFile->uploadItem($_FILES["layout_file"],"/PTG",EXTENSION_FILE_UPLOAD);
			if(!empty($layout_file) && file_exists(ROOTPATH . $layout_file)){
				// Set the file metadata for drive
				$title = 'FH_'.time().'_'.$_FILES["layout_file"]["name"];
				$mimeType = $_FILES["layout_file"]["type"];
				$clsGoogleDrive = new GoogleDrive();
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$layout_file);
				$layout_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				@unlink(ROOTPATH . $layout_file);
				$more_information[$tofield] = $layout_file;
				if($clsStock->updateOne($stock_id, array(
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				))){
					$msg = "_success";
				}
			}
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'layout_file' => $layout_file
	)); die();
}
function default_export(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$html = '<table class="table">';
	$list_stocks = $clsStock->getAll("project_id='1' and block_id='75' and status_id='151'");
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$html.= '<tr>
				<td>'.$val['ms_code'].'</td>
				<td>'.$clsProperty->getTitle($val['bedroom_id']).'</td>
				<td>'.$more_information['DT_TT'].'</td>
				<td>'.$more_information['total_price_vat'].'</td>
			</tr>';
		}
	}
	$html.= '</table>';
	echo $html; die();
}
function default_open_copy(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$project_id = Input::post('project_id', 0);
	$block_id = Input::post('block_id', 0);
	$building_id = Input::post('building_id', 0);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('block_id', $block_id);
	$smarty->assign('building_id', $building_id);
	// Return
	$html = $core->build('_ajax.open_copy.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_do_copy(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$project_id = Input::post('project_id', 0);
	$block_id = Input::post('block_id', 0);
	$building_id = Input::post('building_id', 0);
	$from_floor = Input::post('from_floor', 0);
	$to_floor = Input::post('to_floor', "");
	$to_floor_arrs = @explode(',', $to_floor);
	$updated_rows = 0;
	$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `project_id`='{$project_id}' 
	and `block_id`='{$block_id}' and `building_id`='{$building_id}' and `floor`='{$from_floor}'");
	// $clsISO->print_pre($list_stocks); die();
	if(!empty($list_stocks) && !empty($to_floor_arrs)){
		$oneBuilding = $clsProperty->getOne($building_id, "property_code,more_information");
		$building_code = $oneBuilding['property_code'];
		$building_information = $oneBuilding['more_information'];
		$building_information = $clsISO->to_array_json($building_information);
		$stock_template = isset($building_information['stock_template']) && !empty($building_information['stock_template']) 
				? $building_information['stock_template'] : "";
		foreach($list_stocks as $key => $val){
			foreach($to_floor_arrs as $to_floor){
				$code = $val['code'];
				$more_information_source = $val['more_information'];
				$more_information_source = $clsISO->to_array_json($more_information_source);
				$ms_code = str_replace('[MaToa]', $building_code, $stock_template);
				$ms_code = str_replace('[Tang]', $to_floor, $ms_code);
				$ms_code = str_replace('[CanHo]', $code, $ms_code);
				$oneStock = $clsStock->getByCond("stock_type='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `ms_code`='{$ms_code}' 
				and `project_id`='{$project_id}' and `block_id`='{$block_id}' and `building_id`='{$building_id}' 
				and `code`='{$code}' and `floor`='{$to_floor}'");
				// $clsISO->print_pre($oneStock); die();
				if(!empty($oneStock)){
					$more_information = $oneStock['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information['DT_TT'] = $more_information_source['DT_TT'];
					$more_information['DT_Tim'] = $more_information_source['DT_Tim'];
					$more_information['view_id'] = $more_information_source['view_id'];
					$more_information['bedroom_id'] = $more_information_source['bedroom_id'];
					$more_information['home_direction_id'] = $more_information_source['home_direction_id'];
					// $clsISO->print_pre($more_information); die();
					if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
						'stock_type' => _BLOCK_TYPE_HIGHLEVEL_SALE,
						'floor' => $to_floor,
						'code' => $code,
						'view_id' => $val['view_id'],
						'bedroom_id' => $val['bedroom_id'],
						'home_direction_id' => $val['home_direction_id'],
						'DT_TT' => $clsISO->toNumber($val['DT_TT']),
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						$msg = "_success";
						$updated_rows++;
					}
				} else {
					$more_information = array(
						'status_id' => $more_information_source['status_id'],
						'DT_TT' => $more_information_source['DT_TT'],
						'DT_Tim' => $more_information_source['DT_Tim'],
						'type_id' => $more_information_source['type_id'],
						'total_price' => $more_information_source['total_price'],
						'total_price_vat'=> $more_information_source['total_price_vat'],
						'view_id' => $more_information_source['view_id'],
						'bedroom_id' => $more_information_source['bedroom_id'],
						'home_direction_id' => $more_information_source['home_direction_id']
					);
					if($clsStock->insert(array(
						$clsStock->pkey => $clsStock->getMaxId(),
						'stock_type' => _BLOCK_TYPE_HIGHLEVEL_SALE,
						'project_id' => $project_id,
						'block_id' => $block_id,
						'building_id' => $building_id,
						'floor' => $to_floor,
						'code' => $code,
						'ms_code' => $ms_code,
						'view_id' => $val['view_id'],
						'type_id' => $val['type_id'],
						'bedroom_id' => $val['bedroom_id'],
						'home_direction_id' => $val['home_direction_id'],
						'DT_TT' => $clsISO->toNumber($val['DT_TT']),
						'status_id' => $val['status_id'],
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						$msg = "_success";
						$updated_rows ++;
					}
				}
			}
		}
	}
	// Return
	echo $msg.'|||'.$updated_rows; die();
}
function default_load_block_user(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$project_id = Input::post('project_id', 0);
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;	
	$list_block_admin = [];
	$more_information_user = $clsISO->to_array_json($core->_USER["more_information"]);
	$block_permiss = !empty($more_information_user['block_permiss']) ? $more_information_user['block_permiss'] : array();
	if($project_id > 0) {
		$project_save = !empty($more_information_user['project'][$project_id]) ? $more_information_user['project'][$project_id] : array();
		$list_block_admin = !empty($project_save) ? $project_save['list_block_admin'] : array();
	}else{
		if(!empty($more_information_user['project'])) {
			foreach($more_information_user['project'] as $k => $val) {
				$list_block_admin = array_merge($list_block_admin,$val['list_block_admin']);
			}
		}
	}
	$html = "";
	if(!empty($block_permiss)) {
		$cond = "`property_type`='_BLOCK' AND `parent_id`='".$stock_type."' AND  property_id IN (".implode(',',$block_permiss).")";
		if($project_id > 0) {
			$cond .= " AND `for_id`='".$project_id."'";
		}
		$lstBlockU = $clsProperty->getAll($cond,$clsProperty->pkey.',title');
		if(!empty($lstBlockU)) {
			$html .= '<label class="text-muted text-nowrap mr-2 mt-2">Phân khu:</label>
			<div class="d-flex flex-wrap align-items-start">';
			foreach( $lstBlockU as $k_block => $v_block) {
				$html.= '<div class="radius-half p-2 px-3 mr-2">
					<div class="checkbox">
						<input type="checkbox" id="block_'.$v_block[$clsProperty->pkey].'" name="block_id[]" value="'.$v_block[$clsProperty->pkey].'" '.($clsISO->checkItemInArray($v_block[$clsProperty->pkey],$list_block_admin) ? "checked" : "").' />
						<label for="block_'.$v_block[$clsProperty->pkey].'">'.$v_block['title'].'</label>
					</div>
				</div>';	
			}	
			$html.= '</div>';				
		}
	}
	echo $html;die;	
}
function default_update_DLHD(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$block_id = 9097;
	$clsProperty->setDeBug(1);
	$lstRange = $clsProperty->getAll("for_id='{$block_id}'");
	$lstStock = $clsStock->getAll("ms_code LIKE '%ĐLHĐ-%'");
	$total = 0;
	foreach($lstStock as $key => $val){
		$tmp = explode("-",$val['ms_code']);
		$building_id = 0;
		if(((int)$tmp[1] >= 1 && (int)$tmp[1] <= 126) || ((int)$tmp[1] >= 128  && (int)$tmp[1] <= 248 && $tmp[1] % 2 == 0)){
			$building_id = 9098;
//			$clsISO->print_pre($val["ms_code"]);
//			++$total;
		}
		if((int)$tmp[1] >= 127  && (int)$tmp[1] <= 253 && $tmp[1] % 2 == 1){
			$building_id = 9099;
//			$clsISO->print_pre($val);
//			++$total;
		}
		if((int)$tmp[1] >= 250  && (int)$tmp[1] <= 476 && $tmp[1] % 2 == 0){
			$building_id = 9100;
//			$clsISO->print_pre($val["ms_code"]);
//			++$total;
		}
		if((int)$tmp[1] >= 255  && (int)$tmp[1] <= 485 && $tmp[1] % 2 == 1){
			$building_id = 9101;
//			$clsISO->print_pre($val["ms_code"]);
//			++$total;
		}
		if((int)$tmp[1] >= 478  && (int)$tmp[1] <= 710 && $tmp[1] % 2 == 0){
			$building_id = 9102;
//			$clsISO->print_pre($val["ms_code"]);
//			++$total;
		}
		/*if($building_id > 0) {
			$clsStock->setDeBug(1);
			if($clsStock->updateOne($val['stock_id'],[
				"block_id"		=>	$block_id,
				"building_id"	=>	$building_id,
			])) {				
				++$total;
			}
		}*/
	}
	echo $total;
	die;
}
function default_updateStructCode(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$building_id = (int)Input::post("building_id",0);
	$oneBuilding = $clsProperty->getOne($building_id);
	$total_update = 0;
	if(!empty($oneBuilding)) {
		$more_information = $clsISO->to_array_json($oneBuilding['more_information']);
		$building_code = $oneBuilding['property_code'];
		$stock_template = isset($more_information['stock_template']) && !empty($more_information['stock_template']) 
			? $more_information['stock_template'] : "";
		$lstStock = $clsStock->getAll("`building_id`='{$building_id}'",$clsStock->pkey.',ms_code,code,floor,building_id,more_information');
		foreach ($lstStock as $key => $val) {
			$code = $val['code'];
			$floor = $val['floor'];
			/*if($floor == "07"){
				$floor = "08A";
			}
			if($floor == "13"){
				$floor = "12A";
			}
			if($floor == "14"){
				$floor = "12B";
			}*/
//			$floor = (is_numeric($floor)) ? $clsISO->parseNumber($floor) : $floor;
			$floor = (is_numeric($floor)) ? $floor : $floor;
			$more_information_stock = $clsISO->to_array_json($val['more_information']);
			if(!empty($more_information["is_symbol"]) && !empty($more_information_stock["building_code"])) {
				$building_code = $more_information_stock["building_code"];
			}
			if(!empty($more_information['is_symbol_floor']) && !empty($more_information_stock["building_code_floor"])) {
				$ms_code = $more_information_stock["building_code_floor"] . $code;
			}else{
				$ms_code = str_replace('[MaToa]', $building_code, $stock_template);
				$ms_code = str_replace('[Tang]', $floor, $ms_code);
				$ms_code = str_replace('[CanHo]', $code, $ms_code);
			}
			
			
			if($clsStock->updateOne($val[$clsStock->pkey], ["ms_code" => $ms_code])){
				++$total_update;
			}
		}	
	}
	echo json_encode(array(
		"result"	=>	true,
		"total_update"	=>	$total_update
	));die;	
}
function default_update_ptg(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$agency_id = (int)Input::post("agency_id",0);
	/** Init Client */
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Drive::DRIVE);
	$service = new Google_Service_Drive($client);
	$msg = "_error"; $total_update = 0;
	if($agency_id > 0) {
		$oneAgency = $clsProperty->getOne($agency_id, "more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$folder_price_sheets = $core->get_field($more_information, "folder_price_sheets", []);
		// $clsISO->print_pre($folder_price_sheets); die();
		if(!empty($folder_price_sheets)) {
			foreach($folder_price_sheets as $key => $val) {
				$link = $core->get_field($val,'link', "");
				$status = (int) $core->get_field($val, 'status', 0);
				if(!empty($link) && $status == 1 
					&& $clsISO->checkContainer($link, "drive.google.com", "")) {
					$gg_id = $clsISO->getGoogleId($link);
					$list_folders = get_folders($service, $gg_id);
					if(!empty($list_folders)){
						$msg = "_success";
						foreach($list_folders as $k_folder => $v_folder) {
							$folder_id = $v_folder['id'];
							$folder_name = trim($v_folder['name']);
							$oneStock = $clsStock->getByCond("`ms_code`='{$folder_name}' AND `status_id`>0 
								AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'","{$clsStock->pkey},more_information");
							if(!empty($oneStock)) {
								// $clsISO->print_pre($oneStock); die();
								$more_information = $oneStock['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$price_sheets = $core->get_field($more_information, 'price_sheets', []);
								$sheets = array();
								$sheets[$clsISO->getUniqid()] = array(
									'title' => "PTG TẠM TÍNH",
									'image' => sprintf("https://drive.google.com/drive/folders/%s",$folder_id)
								);
								$price_sheet_id = $clsISO->getUniqid();
								$price_sheets[$price_sheet_id]['sheets'] = $sheets;
								$price_sheets[$price_sheet_id]['reg_date'] = time();
								$price_sheets[$price_sheet_id]['upd_date'] = time();
								$price_sheets[$price_sheet_id]['user_id'] = $core->_USER['user_id'];
								$price_sheets[$price_sheet_id]['user_update_id'] = $core->_USER['user_id'];
								$more_information["price_sheets"] = $price_sheets;
								// $clsISO->print_pre($more_information); die();
								if($clsStock->updateOne($oneStock[$clsStock->pkey],[
									"more_information"	=>	json_encode($more_information, JSON_UNESCAPED_UNICODE)
								])) {
									$total_update += 1;
								}
							}
						}
					} else {
						$msg = "_empty";
					}
				}
			}
		} else {
			$msg = "_empty";
		}
	}
	echo json_encode(array(
		"msg" =>	$msg,
		"total_update"	=>	$total_update
	));die;	
}
function get_folders($service, $parentId, &$resultArray = []) {
    $optParams = [
        'q' => "'$parentId' in parents and mimeType='application/vnd.google-apps.folder' and trashed = false",
        'fields' => 'files(id, name)',
        'pageSize' => 1000,
		'corpora' => "allDrives",
		'supportsAllDrives' => 'true',
		'includeItemsFromAllDrives' => 'true'
    ];
    $results = $service->files->listFiles($optParams);
    $folders = $results->getFiles();
    foreach ($folders as $folder) {
        $folder_id = $folder->getId();
        $folder_name = $folder->getName();
        $resultArray[] = ['id' => $folder_id, 'name' => $folder_name];
        // Đệ quy tìm folder con
        get_folders($service, $folder_id, $resultArray);
    }
    return $resultArray;
}
?>