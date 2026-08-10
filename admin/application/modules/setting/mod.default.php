<?php
function default_setting(){
	global $assign_list,$core,$clsConfiguration,$dbconn,$clsISO;
	$group = Input::get('group','general');
	$agency_id = (int)Input::get('agency',0);
	$assign_list["agency_id"] = $agency_id;  
	$assign_list["group"] = $group;  
	
	$lstSetting_Type = array();
	$lstSetting_Type['_GROUP_COMPANY'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Công ty thành viên',
		'description'	=> 'Công ty thành viên'
	);
	$lstSetting_Type['_CRM_TASK'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Tác nghiệp',
		'description'	=> 'Danh sách tác nghiệp'
	);
	$lstSetting_Type['_CRM_RESULT'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Kết quả tác nghiệp',
		'description'	=> 'Danh sách kết quả tác nghiệp'
	);
	$lstSetting_Type['_CRM_SCRIPT'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Kịch bản gọi điện (CRM)',
		'description'	=> 'Kịch bản/gợi ý nội dung cuộc gọi cho Sale. Tên = tiêu đề kịch bản, Giới thiệu = nội dung kịch bản.'
	);
	$lstSetting_Type['_CRM_ZALO_TEMPLATE'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Mẫu tin Zalo (CRM)',
		'description'	=> 'Mẫu nội dung tin nhắn Zalo gửi khách. Tên = tiêu đề mẫu, Giới thiệu = nội dung tin. Dùng {ten} để chèn tên khách.'
	);
	$lstSetting_Type['_TYPE_STOCK_LOCK'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại lock căn',
		'description'	=> 'Khóa căn theo loại'
	);
	$lstSetting_Type['_DOCS_FILE_TYPE'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại tài liệu',
		'description'	=> 'Loại tài liệu'
	);
	$lstSetting_Type['_OFFICE_COST_CATEGORY'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh mục chi phí văn phòng',
		'description'	=> 'Loại tài liệu'
	);
	$lstSetting_Type['_ZALO_GROUP_TYPE'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại nhóm Zalo',
		'description'	=> 'Loại nhóm Zalo'
	);
	$lstSetting_Type['_LIST_FORM_BUSINESS'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại hình kinh doanh',
		'description'	=> 'Loại hình kinh doanh'
	);
	$lstSetting_Type['_TYPE_BOOKING'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại Booking',
		'description'	=> 'Loại Booking'
	);
	$lstSetting_Type['_PACKAGE_DATA'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Gói data khách hàng',
		'description'	=> 'Gói data khách hàng'
	);
	$lstSetting_Type['_CHANEL_ADS'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Kênh truyền thông',
		'description'	=> 'Kênh truyền thông'
	);	
	$lstSetting_Type['_AREA'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Khu vực dự án',
		'description'	=> 'Khu vực dự án'
	);
	$lstSetting_Type['_ACCOUNT'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Tài khoản tài chính',
		'description'	=> 'Tài khoản tài chính'
	);
	$lstSetting_Type['_CRITERIA_ASSET_CATEGORY'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh mục tiêu chí tài sản',
		'description'	=> 'Danh mục tiêu chí tài sản'
	);
	$lstSetting_Type['_LIABILITIES_EQUITY'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh mục nguồn vốn',
		'description'	=> 'Danh mục nguồn vốn'
	);
	$lstSetting_Type['_CASH_FLOW_CATEGORY'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh mục dòng tiền',
		'description'	=> 'Danh mục dòng tiền'
	);
	$lstSetting_Type['_OPEX_CATEGORY'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh mục chi phí vận hành',
		'description'	=> 'Danh mục chi phí vận hành'
	);
	$lstSetting_Type['_CHECKIN_TAGS'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Tag check-in',
		'description'	=> 'Tag check-in'
	); 
	$lstSetting_Type = array();
	
	$lstSetting_Type['_PROJECT'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh sách dự án',
		'description'	=> 'Mapping dự án'
	);
	$lstSetting_Type['_OFFICE'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Văn phòng',
		'description'	=> 'Văn phòng'
	);
	$lstSetting_Type['_MEETING_ROOM'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Phòng họp',
		'description'	=> 'Phòng họp'
	);
	$assign_list["lstSetting_Type"] = $lstSetting_Type;  
}
function default_load_block(){
	global $assign_list,$_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$project_id = (int) Input::post('project_id', 0);
	###
	$field = "{$clsProperty->pkey},`title`";
	$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
	
	$html_options = '<option value="0">Chọn phân khu</option>';
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
		}
	}
	// Return
	echo $html_options; die();
}
function default_open_setting(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$dbconn;
	$clsSetting = new Setting();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsSetting',$clsSetting);
	$smarty->assign('clsProperty',$clsProperty);
//	$dbconn->debug=true;
//	$lstDepartment = $clsProperty->getSelectOptimizePropertyNotTile("_OFFICE");
//	$clsISO->print_pre($lstOffice);die;
	#
	$action = '_form';
	$toId = Input::post('toId');
	$for_id = Input::post('for_id', 0);
	$_reload = Input::post('_reload',0);
	$parent_id = Input::post('parent_id', 0);
	$setting_type = Input::post('setting_type');
	$setting_id = (int) Input::post('setting_id',0);
	if($setting_type == "_MEETING_ROOM") {
		$office_id = (int) Input::post('office_id',0);
		$smarty->assign('office_id',$office_id);
	}
	#
	$smarty->assign('toId',$toId);
	$smarty->assign('action',$action);
	$smarty->assign('_reload',$_reload);
	$smarty->assign('parent_id',$parent_id);
	$smarty->assign('setting_type',$setting_type);
	$smarty->assign('setting_id',$setting_id);
	#
	$oneSetting = array('is_trash' => 0, 'parent_id' => $parent_id);
	$titlePage = $core->get_Lang('Addnew');
	$more_information = array(
		'project_id' => 0, 
		'block_id' => 0, 
		"project_admins" => [], 
		"stock_hug_configs" => [
			"spreadsheetId" => "", 
			"sheet_name" => ""
		],
	);
	if(in_array($setting_type, array('_TYPE_STOCK_LOCK', '_PROJECT'))){
		$field = "{$clsProfile->pkey},`code`,`full_name`,`first_name`,`last_name`";
		$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'");
		if(!empty($list_staffs)){
			foreach($list_staffs as $key => $val){
				$list_staffs[$key]['full_name'] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
			}
		}
		$smarty->assign('list_staffs',$list_staffs);
	}
	if($setting_id >0){
		$titlePage = $core->get_Lang('Update');
		$oneSetting = $clsSetting->getOne($setting_id);
		$for_id = $oneSetting['for_id'];
		$more_information = $oneSetting['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
	}
	if($setting_type == '_PROJECT'){
		$list_blocks = array();
		$field = "{$clsProject->pkey},`title`";
		$list_projects = $clsProject->getAll("is_trash=0", $field);
		$project_id = (int) $core->get_field($more_information, "project_id", 0);
		if($project_id > 0){
			$field = "{$clsProperty->pkey},`title`";
			$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND for_id='{$project_id}'", $field);
		}
		$smarty->assign('list_blocks', $list_blocks);
		$smarty->assign('list_projects', $list_projects);
		$smarty->assign('arr_project_admins', $arr_project_admins);
		#
		$html_worksheets = "";
		$stock_hug_configs = $core->get_field($more_information, "stock_hug_configs", []);
		$spreadsheetId = $core->get_field($stock_hug_configs, "spreadsheetId", "");
		$sheet_name = $core->get_field($stock_hug_configs, "sheet_name", "");
		if(!empty($spreadsheetId)){
			if($clsISO->checkContainer($spreadsheetId, "docs.google.com","")){
				@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
				$spreadsheetId = $matches[0];
			}
			/** Required Lib */
			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
			/** Init Client */
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes([Google_Service_Drive::DRIVE]);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			try {
				$spreadsheet = $service->spreadsheets->get($spreadsheetId);
				$arr_worksheets = $spreadsheet->sheets;
			} catch(Exception $ex){
				$msg_error = $ex->getMessage();
				if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
					$clsCrawl = new Crawl();
					$spreadsheetIdCopy = $clsCrawl->copySpreadsheet($spreadsheetId, [], 0, 0, 0);
					$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
					$arr_worksheets = $spreadsheet->sheets;
				}				
			}
			if(!empty($arr_worksheets)){
				foreach($arr_worksheets as $sheet){
					$sheetId = $sheet->properties['sheetId'];   
					$sheetName = $sheet->getProperties()->getTitle(); 
					$html_worksheets.= '<option'.($sheet_name == $sheetName ? " selected": "").' value="'.$sheetName.'">'.$sheetName.'</option>';
				}
			}
		}
		$smarty->assign('html_worksheets',$html_worksheets);
	}
	if($setting_type == "_MEETING_ROOM") {
		$office_id = (int) Input::post('office_id',0);
		$oneOffice = $clsSetting->getOne($office_id);
		if(!empty($oneOffice)) {
			$titlePage .=" phòng họp - " . $oneOffice["title"];
		}
	}
	$smarty->assign('for_id',$for_id);
	$smarty->assign('titlePage',$titlePage);
	$smarty->assign('oneSetting',$oneSetting);
	$smarty->assign('more_information',$more_information);
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.setting.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_save_setting(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$dbconn;
	$clsSetting = new Setting();
	$clsMoneyAccount = new MoneyAccount();
	$user_id = $core->_USER['user_id'];
    $setting_type = Input::post('setting_type');
	$setting_id = (int) Input::post('setting_id',0,true);
	$is_range_time = (int) Input::post('is_range_time',0);
	// Action
	if(Input::exists('action','GET')){
		$action = Input::get('action');
		if($action=='_saveorder'){
			$orderNo = Input::post('orderNo');
			for($i=0; $i<count($orderNo); $i++){
				$clsSetting->updateOne($orderNo[$i], array(
					'order_no'	=> ($i+1)	
				));
			}
			echo(1); die();
		}else if($action=='_delete'){
			if($clsSetting->countItem("_type='{$setting_type}' and parent_id='{$setting_id}'") > 0){
				echo '_invalid';
				die();
			}else{
				$log_message = __('Setting has been deleted')." : ". $clsSetting->getTitle($setting_id);
				if($clsSetting->deleteOne($setting_id)){	
					$clsActivityLog = new ActivityLog();
					$log = $clsActivityLog->addActivityLog("Setting","delete",["_type"=>"$setting_type"]);
				}	
			}
			echo(1); die();
		}
	}
	// End action
	$msg = "_error";
	$title = Input::post('title');
	$slug = $core->replaceSpace($title);
	$for_id = (int) Input::post('for_id',0);
	$parent_id = (int) Input::post('parent_id',0);
	$setting_code = Input::post('setting_code', "");
	$icon = Input::post('icon', "");
	if($setting_id > 0){
		$cond = "`is_trash`=0 and `parent_id`='{$parent_id}' and `_type`='{$setting_type}'";
		if($for_id > 0) $cond .= " and `for_id`='{$for_id}'";
		if($clsSetting->countItem("{$cond} and `setting_id`<>'{$setting_id}' and `slug`='{$slug}'") > 0){
			echo '_invalid'; 
			die();	
		}else{
			$oneSetting = $clsSetting->getOne($setting_id);
			$more_information = $oneSetting["more_information"];
			$more_information = $clsISO->to_array_json($more_information);
			// $clsISO->print_pre($more_information); die();
			$more_information['icon'] = $icon;
			$more_information['setting_code'] = $setting_code;
            $more_information['intro'] = Input::post('intro');
            $more_information['image'] = Input::post('image');
            $more_information['bgcolor'] = Input::post('bgcolor');
            $more_information['textcolor'] = Input::post('textcolor');
			if($setting_type == "_MEETING_ROOM") {
            	$more_information['office_id'] = (int)Input::post('office_id',0);
			}
            $more_information['user_id_update'] = Input::post('user_id_update');
			if($setting_type == "_ACCOUNT") {
				$tags = Input::post('tags');
				$account_type = Input::post('account_type');
				$account_type = $account_type ?? 'debit';
				$more_information['account_type'] = $account_type;
				if(!empty($tags)){
					$query_tags = $clsISO->getArrayByTextSlash($tags);
					$more_information['query_tags'] = $query_tags;
					// Xoá hết
					$clsMoneyAccount->deleteByCond("`account_id`='{$setting_id}'");
					// Thêm mới lại
					foreach($query_tags as $tag){
						$max_id = $clsMoneyAccount->getMaxId();
						$clsMoneyAccount->insert(array(
							$clsMoneyAccount->pkey => $max_id,
							'group_code' => $tag,
							'account_id' => $setting_id,
							'account_type' => $account_type
						));
					}
				} else {
					// Xoá hết
					$clsMoneyAccount->deleteByCond("`account_id`='{$setting_id}'");
				}	
			}
			if($setting_type == '_PROJECT'){
				$project_id = (int) Input::post('project_id',0);
				$block_id = (int) Input::post('block_id',0);
				$stock_hug_configs = Input::post('stock_hug_configs', []);
				$project_admins = Input::post('project_admins',[]);
				$more_information['project_id'] = $project_id;
				$more_information['block_id'] = $block_id;
				if(!empty($stock_hug_configs)){
					foreach($stock_hug_configs as $p_field => $p_value){
						$more_information['stock_hug_configs'][$p_field] = $p_value;
					}
				}
				$more_information['project_admins'] = $project_admins;
			}
			if($setting_type == '_CRITERIA_ASSET_CATEGORY' || $setting_type == '_LIABILITIES_EQUITY' || $setting_type == '_CASH_FLOW_CATEGORY'){
				$lst_account_id = Input::post('lst_account_id', []);
				$more_information['lst_account_id'] = $lst_account_id;
				if($setting_type == '_CASH_FLOW_CATEGORY') {
					$direction = Input::post('direction');
					$direction = $direction ?? 'in';
					$more_information['direction'] = $direction;
				}				
			}
			$data_update = array(
				'_type'	=> $setting_type,
				'parent_id'	=> $parent_id,
				'for_id' => $for_id,
				'title'	=> $title,
				'slug'	=> $core->replaceSpace($title),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			);
			if($clsSetting->updateOne($setting_id, $data_update)) {
				$msg = "_success";
			}
		}
	}else{
		$cond = "is_trash=0 and parent_id='{$parent_id}' and _type='{$setting_type}'";
		if($for_id > 0) $cond .= " and for_id='{$for_id}'";
		if($clsSetting->countItem("{$cond} and slug='{$slug}'") > 0){
			echo '_invalid'; 
			die();
		}else{
			$more_information = array();
			$setting_id = $clsSetting->getMaxId();
			$more_information['icon'] = $icon;
			$more_information['setting_code'] = $setting_code;
            $more_information['intro'] = Input::post('intro');
            $more_information['image'] = Input::post('image');
            $more_information['bgcolor'] = Input::post('bgcolor');
            $more_information['textcolor'] = Input::post('textcolor');
            $more_information['user_id'] = $user_id;
            $more_information['user_id_update'] = $user_id;
			if($setting_type == "_MEETING_ROOM") {
            	$more_information['office_id'] = (int)Input::post('office_id',0);
			}
			if($setting_type == "_ACCOUNT") {
				$tags = Input::post('tags');
				$account_type = Input::post('account_type');
				$account_type = $account_type ?? 'debit';
				$more_information['account_type'] = $account_type;
				if(!empty($tags)){
					$query_tags = $clsISO->getArrayByTextSlash($tags);
					$more_information['query_tags'] = $query_tags;
					foreach($query_tags as $tag){
						$tmp = $clsMoneyAccount->getByCond("`group_code`='{$tag}' AND `account_id`='{$setting_id}'");
						if(!empty($tmp)){
							// Break
						} else {
							$max_id = $clsMoneyAccount->getMaxId();
							$clsMoneyAccount->insert(array(
								$clsMoneyAccount->pkey => $max_id,
								'group_code' => $tag,
								'account_id' => $setting_id,
								'account_type' => $account_type
							));
						}
					}
				}
			}
			if($setting_type == '_PROJECT'){
				$project_id = (int) Input::post('project_id',0);
				$block_id = (int) Input::post('block_id',0);
				$more_information['project_id'] = $project_id;
				$more_information['block_id'] = $block_id;
			}
			if($setting_type == '_CRITERIA_ASSET_CATEGORY' || $setting_type == '_LIABILITIES_EQUITY' || $setting_type == '_CASH_FLOW_CATEGORY'){
				$lst_account_id = Input::post('lst_account_id', []);
				$more_information['lst_account_id'] = $lst_account_id;
				if($setting_type == '_CASH_FLOW_CATEGORY') {
					$direction = Input::post('direction');
					$direction = $direction ?? 'in';
					$more_information['direction'] = $direction;
				}
			}
			$data_insert = array(
				'setting_id'	=> $setting_id,
				'_type'	=> $setting_type,
				'parent_id'	=> $parent_id,
				'for_id' => $for_id,
				'title'	=> $title,
				'slug'	=> $slug,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'order_no'	=> $clsSetting->getMaxOrderNo(),
				'reg_date'	=> time()
			);
			if($clsSetting->insert($data_insert)) {			
				$msg = "_success";
			}
		}
	}
	// Return
	echo($setting_id); die();
}
function default_get_worksheets(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	#
	$arr_worksheets = array();
	$spreadsheetId = Input::post('spreadsheetId');
	$html_worksheets = '<option value="">Chọn bảng tính</option>';
	if(!empty($spreadsheetId)){
		if($clsISO->checkContainer($spreadsheetId, "docs.google.com","")){
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			$spreadsheetId = $matches[0];
		}
		/** Required Lib */
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes([Google_Service_Drive::DRIVE]);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		try {
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$arr_worksheets = $spreadsheet->sheets;
		} catch(Exception $ex){
			$msg_error = $ex->getMessage();
			if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
				$clsCrawl = new Crawl();
				$spreadsheetIdCopy = $clsCrawl->copySpreadsheet($spreadsheetId, [],0, 0,0);
				$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
				$arr_worksheets = $spreadsheet->sheets;
			}				
		}
		if(!empty($arr_worksheets)){
			foreach($arr_worksheets as $sheet){
				$sheet_id = $sheet->properties['sheetId'];   
				$sheet_name = $sheet->getProperties()->getTitle(); 
				$html_worksheets.= sprintf('<option value="%s">%s</option>', $sheet_name, $sheet_name);
			}
		}
	}
	// Return
	echo json_encode(array(
		'html_worksheets' => $html_worksheets,
	)); die();
}
function default_open_config_field(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	$clsSetting = new Setting();
	$clsStockHug = new StockHug();
	$clsProperty = new Property();
	#
	$html = '';
	$setting_id = (int) Input::post('setting_id', 0);
	$sheet_name = Input::post('sheet_name');
	$spreadsheetId = Input::post('spreadsheetId');
	if(!empty($spreadsheetId) && !empty($sheet_name)){
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
		// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
		// get all the rows of a sheet
		$range = $sheet_name; // here we use the name of the Sheet to get all the rows
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		$highestColumn = 0;
		if(!empty($tblData)){
			foreach($tblData as $oData){
				if($highestColumn < count($oData)){
					$highestColumn = count($oData);
				}
			}
			$oneSetting = $clsSetting->getOne($setting_id, "more_information");
			$more_information = $oneSetting["more_information"];
			$more_information = $clsISO->to_array_json($more_information);
			$stock_hug_configs = $core->get_field($more_information, "stock_hug_configs", []);
			$arrs_columns = $core->get_field($stock_hug_configs, "columns", []);
			// $clsISO->print_pre($arrs_columns); die();
			$html = '<div class="modal-dialog modal-lg">
			<form class="modal-content" method="POST">
				<div class="modal-header"> 
					<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
					<h3 class="modal-title"><strong>Import bảng hàng</strong></h3>
				</div>
				<div class="modal-body modal-body-scrollable">
					<table class="table table-bordered table-striped">
						<thead><tr>';
							for($col=0; $col<$highestColumn; $col++){
								$field = isset($arrs_columns[$col]) ? $arrs_columns[$col] : "";
								$html .= '<th style="min-width:100px" width="'.(100/$highestColumn).'%">
									<select name="columns['.$col.']" class="form-control stock_import_field">
										<option value="">Lựa chọn</option>
										'.$clsStockHug->renderOptionColumnField($col, $field).'
									</select>
								</th>';
							}
						$html .= '</tr>
						</thead>';
					$ii = 0;
					foreach($tblData as $key => $val){
						if($ii<=10){
							$html.='<tr>';
							for($col=0; $col<=$highestColumn; $col++){
								$html.= '<td>'.$val[$col].'</td>';
							}
							$html.= '</tr>';
						}
						++$ii;
					}
					$html.= '<tr>
						<td class="text-center" colspan="'.$highestColumn.'">
							Dữ liệu mẫu...
						</td>
					</tr>';
			$html.='</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" setting_id="'.$setting_id.'" 
						onClick="$Core.setting.save_config_field(this, event)">Lưu lại</button>
				</div>
			</form></div>';
		}
	} else {
		$html = '_invalid';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_save_config_field(){
	global $smarty,$core,$dbconn,$clsISO;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	###
	$columns = Input::post("columns", array());
	$setting_id = (int) Input::post('setting_id', 0);
	// $clsISO->print_pre($columns); die();
	if(!empty($columns) && $setting_id > 0) {
		$error_field = 0; $arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[$key] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
		if($error_field > 0){
			$res = array(
				'result' =>	false,
				'msg'	 =>	"Các cột dữ liệu không được trùng nhau"
			);
		} else {
			$oneSetting = $clsSetting->getOne($setting_id, "more_information");
			$more_information = $oneSetting['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$stock_hug_configs = $core->get_field($more_information, "stock_hug_configs", []);
			$stock_hug_configs['columns'] = $arr_fields;
			$more_information["stock_hug_configs"] = $stock_hug_configs;
			// $clsISO->print_pre($more_information); die();
			if($clsSetting->updateOne($setting_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE) 
			))){
				$res = array(
					"result" =>	true,
					'msg' => "Cài đặt thành công",
				);
			}
		}
	}else{
		$res = array(
			"result" =>	false,
			'msg' => "Có lỗi xảy ra. Xin vui lòng thử lại!",
		);
	}
	// Return	
	echo json_encode($res); die();
}
function default_load_select_setting(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	####
	$for_id = (int) Input::post('for_id', 0);
	$setting_id = Input::post('setting_id');
	$setting_type = Input::post('setting_type');
	$title = "";
	if($setting_type=='_PROJECT') $title = "Chọn dự án";
	if($setting_type=='_BLOCK') $title = "Chọn phân khu";
	if($setting_type=='_BUILDING') $title = "Chọn tòa nhà";
	####
	if($for_id > 0){
		$html = $clsSetting->getSelectBySettingOrigin($setting_type, $for_id, $setting_id, $title);
	} else {
		$html = $clsSetting->getSelectBySetting($setting_type, $for_id, $setting_id, $title);
	}
	// return
	echo $html; die();
}
function default_load_list_setting(){
	global $smarty,$assign_list,$user_id,$core,$clsISO,$_LANG_ID;
	$clsSetting = new Setting();
	$smarty->assign('clsSetting',$clsSetting);
	#
	$action = '_list';
	$setting_type = Input::post('setting_type');
	$smarty->assign('action',$action);
	$smarty->assign('setting_type',$setting_type);
	#
	$cond = "1=1";
	if($setting_type=='_AGENCY') $cond = "is_locked=0";
	$lstOffice = $arr_meeting_room = [];
	if($setting_type == "_MEETING_ROOM") {
		$lstOffice = $clsSetting->getAll($cond." and parent_id='0' and _type='_OFFICE' order by order_no ASC");
	}
	$lstSetting = $clsSetting->getAll($cond." and parent_id='0' and _type='{$setting_type}' order by order_no ASC");
	if(!empty($lstSetting)){
		foreach($lstSetting as $key=> $val){
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) ? json_decode(html_entity_decode($more_information), true) : array();
			$lstSetting[$key]['more_information'] = $more_information;
			if($setting_type == "_MEETING_ROOM") {
				if(!empty($more_information["office_id"])) {
					$arr_meeting_room[$more_information["office_id"]][] = $lstSetting[$key];
				}
			}
		}
	}
	$smarty->assign('lstSetting',$lstSetting);
	$smarty->assign('arr_meeting_room',$arr_meeting_room);
	$smarty->assign('lstOffice',$lstOffice);
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.setting.tpl');
	echo $html; die();
}
function default_storage_cache(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsSetting = new Setting();
	$setting_type = Input::post("setting_type","");
	###
	$msg = "_error";
	if(!empty($setting_type)){
		$msg = "_success";
		if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){
			$cache = new Cache();
			$field = "{$clsSetting->pkey},`title`,`slug`,`parent_id`,`more_information`";
			$list_settings = $clsSetting->getAll("`is_trash`=0 and `_type`='{$setting_type}' order by `order_no` ASC", $field);
			if(!empty($list_settings)){
				foreach($list_settings as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					foreach(['user_id', 'user_id_update'] as $field){
						if(isset($more_information[$field])) 
							unset($more_information[$field]);
					}
					$list_settings[$key]['more_information'] = $more_information;
				}
			}
			// $clsISO->print_pre($list_settings); die();
			$cachedName = sprintf('setting_%s_cached', $setting_type);
			$cache->set($cachedName, $list_settings);
		} else {
			$cachedName = sprintf('%s_cached.json', $setting_type);
			$cachedFile = DIR_CACHE_JSON.'/setting/'.$cachedName;
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($tblData, $list_settings); 
		}
	}
	// Return
	echo $msg; die();
}
function default_storage_cache_all(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsSetting = new Setting();
	###
	$msg = "_error";
	$list_items = $clsSetting->getAll("`_type`<>'' GROUP BY `_type`","_type");
	if(!empty($list_items)){
		$msg = "_success";
		foreach($list_items as $k => $v) {
			$setting_type = $v['_type'];
			$field = "{$clsSetting->pkey},`title`,`slug`,`parent_id`,`more_information";
			$list_settings = $clsSetting->getAll("`is_trash`=0 and `_type`='{$setting_type}' order by `order_no` ASC", $field);
			if(!empty($list_settings)){
				foreach($list_settings as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					foreach(['user_id', 'user_id_update'] as $field){
						if(isset($more_information[$field])) 
							unset($more_information[$field]);
					}
					$list_settings[$key]['more_information'] = $more_information;
				}
			}
			if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){
				$cache = new Cache();
				$cachedName = sprintf('setting_%s_cached', $setting_type);
				$cache->set($cachedName, $list_settings);
			} else {
				$cachedName = sprintf('%s_cached.json', $setting_type);
				$cachedFile = DIR_CACHE_JSON.'/setting/'.$cachedName;
				$encoder = new Webmozart\Json\JsonEncoder();
				$encoder->encodeFile($tblData, $list_settings); 
			}
			unset($list_settings);
		}			
	}	
	// Return
	echo $msg; die();
}
function default_open_add_property(){
    global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$smarty->assign('core',$core);
    $clsSetting = new Setting();
    $setting_id = (int) Input::post('setting_id');
    $oneSetting = $clsSetting->getOne($setting_id);
    $more_information = $oneSetting['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $configForm = !empty($more_information['configForm']) ? $more_information['configForm'] : [];
	$smarty->assign('setting_id',$setting_id);
	$smarty->assign('configForm',$configForm);
	$html = $core->build('_ajax.open_add_field.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_save_add_property(){
	ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
    global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$getAll = Input::post();
    $clsSetting = new Setting();
    $field = Input::post('field');
    $field_code = Input::post('field_code');
    $field_placeholder = Input::post('field_placeholder');
    $type = Input::post('type');
    $option = Input::post('option');
    $setting_id = Input::post('setting_id');
    $typeArrAccept = _LIST_TYPE_ARRAY_HAS_OPTION;
    $oneSetting = $clsSetting->getOne($setting_id);
    $arr_field_code = [];
    if (!empty($oneSetting)) {
        $more_information = $oneSetting['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $configForm = [];
        if (isset($field)) {
            foreach ($field as $indexField=>$field) {
                $configFormItem = [];
                $configFormItem['field'] = $field;
                $configFormItem['field_placeholder'] = !empty(trim($field_placeholder[$indexField])) ? trim($field_placeholder[$indexField]) : '';
                if (!empty(trim($field_code[$indexField]))) {
                    $configFormItem['field_code'] = trim($field_code[$indexField]);
                    $arr_field_code[] = trim($field_code[$indexField]);
                } else {
                    $data = [
                        'status' => 400,
                        'msg' => 'Dữ liệu không đầy đủ. Xin hãy kiểm tra lại',
                    ];
                    echo json_encode($data);die;
                }
                if (!empty(trim($type[$indexField]))) {
                    $configFormItem['type'] = trim($type[$indexField]);
                } else {
                    $data = [
                        'status' => 400,
                        'msg' => 'Dữ liệu không đầy đủ. Xin hãy kiểm tra lại',
                    ];
                    echo json_encode($data);die;
                }
                if (in_array(trim($type[$indexField]), $typeArrAccept)) {
                    if (!empty($option[$configFormItem['field_code']])) {
                        $configFormItem['option'] = $option[$configFormItem['field_code']];
                    } else {
                        $data = [
                            'status' => 400,
                            'msg' => 'Dữ liệu không đầy đủ. Xin hãy kiểm tra lại',
                        ];
                        echo json_encode($data);die;
                    }
                }
                $configForm[] = $configFormItem;
            }
            if (!empty($arr_field_code)) {
                $arr_field_code = @array_filter($arr_field_code);
                $countUnique = count(array_unique($arr_field_code));
                if (count($arr_field_code) != $countUnique) {
                    $data = [
                        'status' => 201,
                        'msg' => 'Dữ liệu của trường "Mã" không được trùng lặp',
                    ];
                    echo json_encode($data);die;
                }
                if (!$core->validateArrayFieldsLatin($arr_field_code)) {
                    $data = [
                        'status' => 201,
                        'msg' => 'Dữ liệu của trường "Mã" bao gồm là dạng chữ viết thường không dấu, số 0->9 và cách nhau bởi dấu "_"',
                    ];
                    echo json_encode($data);die;
                }
            }
            $updateStatus = false;
            if (!empty($configForm)) {
                $more_information['configForm'] = $configForm;
                if($clsSetting->updateOne($setting_id, array(
                    'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
                ))){
                    $updateStatus = true;
                }
            }
            if ($updateStatus) {
                $data = [
                    'status' => 200,
                    'msg' => 'lưu dữ liệu thành công',
                ];
            } else {
                $data = [
                    'status' => 201,
                    'msg' => 'lưu dữ liệu không thành công',
                ];
            }
        } else {
            $data = [
                'status' => 404,
                'msg' => 'Không có dữ liệu đẩy lên',
            ];
        }
    } else {
        $data = [
            'status' => 404,
            'msg' => 'không tìm thấy cài đặt',
        ];
    }
    // echo "<pre>";print_r($configForm);die;
    echo json_encode($data);die;
}