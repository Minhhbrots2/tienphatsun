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
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	#- Check Permiss
	if(!$clsISO->checkPermission('marketing_access')){
		$core->redirect('/#not-permiss');
	}
	$list_preloaders = array();
	for($i=0; $i <= 50; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["current_month"] = date('Y-m', strtotime("-1 month"));
	$assign_list["list_preloaders"] = $list_preloaders;
    /*=============Title & Description Page==================*/
	$title_page = 'Thông báo - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_import(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', "");
	$titlePage = "";
	#
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$html = $core->build('_ajax.import.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
}
function default_crawl(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	#
	$uid = Input::post('uid');
	$by = Input::post('by', "URL");
	$spreadsheetId = Input::post('spreadsheetId');
	$arr_worksheet = array();
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
		$client->setScopes([
			Google_Service_Drive::DRIVE,
			Google_Service_Sheets::SPREADSHEETS
		]);
		$client->fetchAccessTokenWithRefreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$service = new Google_Service_Sheets($client);
		try {
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$arr_worksheet = $spreadsheet->sheets;
		} catch(Exception $ex){
			$msg_error = $ex->getMessage();
			if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
				$drive = new Google_Service_Drive($client);
				// Đọc file Excel từ Google Drive
				$response = $drive->files->get($spreadsheetId, array(
					'supportsAllDrives' => 'true'
				));
				// Chuyển đổi file Excel thành Google Sheets
				$fileMetadata = new \Google_Service_Drive_DriveFile(array(
					'name' => sprintf('%s', date('d-m-y h:i:s'))
				));
				$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
				$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
				$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
					'supportsAllDrives' => 'true'
				));
				$spreadsheetIdCopy = $convertedFile->getId();
				$spreadsheetCopy = $service->spreadsheets->get($spreadsheetIdCopy);
				$arr_worksheet = $spreadsheetCopy->sheets;
				// Xoá file sau khi lấy dữ liệu xong
				$fileMetadataTrash = new \Google_Service_Drive_DriveFile();
				$fileMetadataTrash->setTrashed(true);
				$drive->files->update($spreadsheetIdCopy, $fileMetadataTrash, array(
					'supportsAllDrives' => true,
					'supportsTeamDrives' => true,
				));
			}				
		}
		if(!empty($arr_worksheet)){
			foreach($arr_worksheet as $sheet){
				// $sheetId = $sheet->properties['sheetId'];   
				$sheetName = $sheet->getProperties()->getTitle(); 
				$html_worksheets.= sprintf('<option value="%s">%s</option>', $sheetName, $sheetName);
			}
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html_worksheets' => $html_worksheets,
	)); die();
}
function default_open_config(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$core,$clsModule,$clsConfiguration,$clsISO,$dbconn;
	global $profile_id, $oneProfile;
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$uid = $clsISO->getUniqid();
	$by = Input::post('by', 'ID');
	$tp = Input::post('tp', 'spending');
	$sheet_name = Input::post('sheet_name');
	$spreadsheetId = Input::post('spreadsheetId');
	if(!empty($spreadsheetId) && !empty($sheet_name)){
		if($by = "URL" && $clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			$spreadsheetId = $matches[0];
		}
		#- Require library		
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->setScopes([
			Google_Service_Drive::DRIVE,
			Google_Service_Sheets::SPREADSHEETS
		]);
		$client->fetchAccessTokenWithRefreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$service = new Google_Service_Sheets($client);
		// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
		// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
		// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
		// get all the rows of a sheet
		try {
			$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$sheet_name."'"], array(
				'valueRenderOption' => 'FORMATTED_VALUE'
			));
			// $clsISO->print_pre($response); die();
			$tblData = $response->getValues();
		} catch(Exception $ex){
			$msg_error = $ex->getMessage();
			if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
				$drive = new Google_Service_Drive($client);
				// Đọc file Excel từ Google Drive
				$response = $drive->files->get($spreadsheetId, array(
					'supportsAllDrives' => 'true'
				));
				// Chuyển đổi file Excel thành Google Sheets
				$fileMetadata = new \Google_Service_Drive_DriveFile(array(
					'name' => sprintf('%s', date('d-m-y h:i:s'))
				));
				$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
				$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
				$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
					'supportsAllDrives' => 'true'
				));
				$spreadsheetIdCopy = $convertedFile->getId();
				$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$sheet_name."'"], array(
					'valueRenderOption' => 'FORMATTED_VALUE'
				));
				// $clsISO->print_pre($response); die();
				$tblData = $response->getValues();
				// Xoá file sau khi lấy dữ liệu xong
				$fileMetadataTrash = new \Google_Service_Drive_DriveFile();
				$fileMetadataTrash->setTrashed(true);
				$drive->files->update($spreadsheetIdCopy, $fileMetadataTrash, array(
					'supportsAllDrives' => true,
					'supportsTeamDrives' => true,
				));
			}				
		}
		$more_information = $oneProfile['more_information'];
		$field = sprintf('marketing_%s_column_config', $tp);
		$select_default = $core->get_field($more_information, $field, []);
		#
		$clsTale = $clsMarketingSpending;
		if($tp == 'budget_register'){
			$clsTale = $clsMarketingBudgetRegister;
		}
		$data_select = $clsTale->getTableField();
		$highestColumnIndex = 15;
		$widthColumn = (100 / $highestColumnIndex);	
		$smarty->assign("data_select",$data_select);
		$smarty->assign("select_default",$select_default);
		$smarty->assign("widthColumn",$widthColumn);
		$smarty->assign("highestColumnIndex",$highestColumnIndex);
		$smarty->assign("tblData", $tblData);
	}
	// Return
	$html = $core->build("_ajax.config.tpl");
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_config(){
	global $smarty,$core,$dbconn,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$uid = Input::post("uid");
	$tp = Input::post('tp', 'spending');
	$columns = Input::post("columns", array());
	// $clsISO->print_pre($columns); die();
	if(!empty($columns)) {
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
			$more_information = $oneProfile['more_information'];
			$field = sprintf('marketing_%s_column_config', $tp);
			$more_information[$field] = $arr_fields;
			if($clsProfile->updateOne($profile_id, array(
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
function default_do_import(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $oneProfile,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$msg = "_error";
	$tp = Input::post('tp', 'spending');
	$month = Input::post('month');
	$by = Input::post('by', "ID");
	$sheet_name = Input::post('sheet_name');
	$spreadsheetId = Input::post('spreadsheetId');
	if(!empty($spreadsheetId) && $clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
		@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
		$spreadsheetId = $matches[0];
	}
	#- Require library		
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->setScopes([
		Google_Service_Drive::DRIVE,
		Google_Service_Sheets::SPREADSHEETS
	]);
	$client->fetchAccessTokenWithRefreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$service = new Google_Service_Sheets($client);
	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
	// get all the rows of a sheet
	try {
		$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$sheet_name."'"], array(
			'valueRenderOption' => 'FORMATTED_VALUE'
		));
		$tblData = $response->getValues();
	} catch(Exception $ex){
		$msg_error = $ex->getMessage();
		if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
			$drive = new Google_Service_Drive($client);
			// Đọc file Excel từ Google Drive
			$response = $drive->files->get($spreadsheetId, array(
				'supportsAllDrives' => 'true'
			));
			// Chuyển đổi file Excel thành Google Sheets
			$fileMetadata = new \Google_Service_Drive_DriveFile(array(
				'name' => sprintf('%s', date('d-m-y h:i:s'))
			));
			$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
			$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
			$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
				'supportsAllDrives' => 'true'
			));
			$spreadsheetIdCopy = $convertedFile->getId();
			$response = $service->spreadsheets_values->get($spreadsheetIdCopy, ["'".$sheet_name."'"], array(
				'valueRenderOption' => 'FORMATTED_VALUE'
			));
			$tblData = $response->getValues();
			// Xoá file sau khi lấy dữ liệu xong
			$fileMetadataTrash = new \Google_Service_Drive_DriveFile();
			$fileMetadataTrash->setTrashed(true);
			$drive->files->update($spreadsheetIdCopy, $fileMetadataTrash, array(
				'supportsAllDrives' => true,
				'supportsTeamDrives' => true,
			));
		}
	}
	// $clsISO->print_pre($tblData); die();
	if(!empty($tblData)){
		$msg = "_success";
		$arr_founds  = $arr_data_import = array();
		$total_record = count($tblData);
		$more_information = $oneProfile['more_information'];
		$field = sprintf('marketing_%s_column_config', $tp);
		$columns = $core->get_field($more_information, $field, []);
		for($i=$start_row; $i<$total_record; $i++){
			$arr_row = array();
			foreach($columns as $i_col => $p_field){
				$arr_row[$p_field] = trim($tblData[$i][$i_col]);
			}
			$arr_data_import[] = $arr_row;
		}	
		// $clsISO->print_pre($arr_data_import); die();
		if($tp == 'budget_register'){
			// $clsISO->print_pre($tblData); die();
			for($i=4; $i<$total_record; $i++){
				$department_name = trim($arr_data_import[$i]['department_name']);
				$staff_name = trim($arr_data_import[$i]['staff_name']);
				$project_name = trim($arr_data_import[$i]['project_name']);
				$fb_ads = trim($arr_data_import[$i]['fb_ads']);
				$gg_ads = trim($arr_data_import[$i]['gg_ads']);
				$zalo_ads = trim($arr_data_import[$i]['zalo_ads']);
				$tiktok_ads = trim($arr_data_import[$i]['tiktok_ads']);
				if(!empty($department_name) && !empty($staff_name) && !empty($project_name)  
					&& (!empty($fb_ads) || !empty($gg_ads) || !empty($zalo_ads) || !empty($tiktok_ads))){
					$department_id = $project_id = $block_id = $staff_id = 0;
					if(!empty($department_name)){
						$field = "{$clsProperty->pkey}";
						$tmp = $clsProperty->getByCond("`property_type`='_DEPARTMENT' AND `slug`='{$core->replaceSpace($department_name)}'", $field);
						$department_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					if(!empty($staff_name)){
						$sql_query = "`is_trash`=0"; //  AND status_id<>'"._STATUS_STAFF_OFF_ID."'
						$sql_query.= ($department_id > 0) ? " AND `department_id`='{$department_id}'" : "";
						$field = "{$clsProfile->pkey},`department_id`";
						$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='{$core->replaceSpace($staff_name)}'", $field);
						if(!empty($tmp)){
							$staff_id = $tmp[$clsProfile->pkey];
							$department_id = $tmp['department_id'];
						} else {
							$sql_query = "`is_trash`=0"; //  AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'
							$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='{$core->replaceSpace($staff_name)}'", $field);
							if(!empty($tmp)){
								$staff_id = $tmp[$clsProfile->pkey];
								$department_id = $tmp['department_id'];
							}
						}
					}
					if(!empty($project_name)){
						$tmp = $clsProject->getByCond("slug='{$core->replaceSpace($project_name)}'", $clsProject->pkey);
						if(!empty($tmp)){
							$project_id = $tmp[$clsProject->pkey];
							unset($tmp);
						} else {
							$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`slug`='".$core->replaceSpace($project_name)."' 
							OR `slug_vn`='".$core->replaceSpace($project_name)."' OR `property_code`='".$project_name."')", "{$clsProperty->pkey},`for_id`");
							if(!empty($tmp)){
								$project_id = $tmp['for_id'];
								$block_id = $tmp[$clsProperty->pkey];
								unset($tmp);
							}
						}
					}
					$tmp = $clsMarketingBudgetRegister->getByCond("`month`='{$month}' AND `department_id`='{$department_id}' 
						AND `staff_id`='{$staff_id}' AND `project_id`='{$project_id}' AND `block_id`='{$block_id}'");
					if(!empty($tmp)){
						$arr_founds[] = $tmp[$clsMarketingSpending->pkey];
						$more_information = $tmp['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$more_information['project_name'] = $project_name;
						$more_information['department_name'] = $department_name;
						$clsMarketingSpending->updateOne($tmp[$clsMarketingSpending->pkey], array(
							'fb_ads' => $clsISO->processSmartNumber($fb_ads),
							'gg_ads' => $clsISO->processSmartNumber($gg_ads),
							'zalo_ads' => $clsISO->processSmartNumber($zalo_ads),
							'tiktok_ads' => $clsISO->processSmartNumber($tiktok_ads),
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'upd_date' => time(),
						));
					} else {
						$id = $clsMarketingBudgetRegister->getMaxId();
						$more_information = array(
							'project_name' => $project_name,
							'department_name' => $department_name,
						);
						if($clsMarketingBudgetRegister->insert(array(
							$clsMarketingBudgetRegister->pkey => $id,
							'month' => $month,
							'staff_id' => $staff_id,
							'department_id' => $department_id,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'fb_ads' => $clsISO->processSmartNumber($fb_ads),
							'gg_ads' => $clsISO->processSmartNumber($gg_ads),
							'zalo_ads' => $clsISO->processSmartNumber($zalo_ads),
							'tiktok_ads' => $clsISO->processSmartNumber($tiktok_ads),
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'status' => 'pending',
							'reg_date' => time(),
							'upd_date' => time(),
						))){
							$arr_founds[] = $id;
						}
					}
				}
			}
			if(!empty($arr_founds)){
				$clsMarketingBudgetRegister->deleteByCond("`month`='{$month}' AND {$clsMarketingBudgetRegister->pkey} NOT IN(".implode(',', $arr_founds).")");
			} else {
				$clsMarketingBudgetRegister->deleteByCond("`month`='{$month}'");
			}
		} else {
			for($i=2; $i<$total_record; $i++){
				$department_name = trim($arr_data_import[$i]['department_name']);
				$staff_name = trim($arr_data_import[$i]['staff_name']);
				$project_name = trim($arr_data_import[$i]['project_name']);
				$chanel_name = trim($arr_data_import[$i]['chanel_name']);
				$ads_id = trim($arr_data_import[$i]['ads_id']);
				$ads_link = trim($arr_data_import[$i]['ads_link']);
				$amount = trim($arr_data_import[$i]['amount']);
				$company_support_rate = trim($arr_data_import[$i]['company_support_rate']);
				$company_support_amount = trim($arr_data_import[$i]['company_support_amount']);
				$status_name = trim($arr_data_import[$i]['status_name']);
				if(!empty($staff_name) && !empty($project_name) && !empty($ads_id) && !empty($chanel_name) && !empty($amount)){
					$department_id = $project_id = $block_id = $staff_id = $chanel_id = 0;
					if(!empty($chanel_name)){
						$tmp = $clsSetting->getByCond("`is_trash`=0 AND `_type`='_CHANEL_ADS' 
							AND `slug`='{$core->replaceSpace($chanel_name)}'", $clsSetting->pkey);
						$chanel_id = !empty($tmp) ? $tmp[$clsSetting->pkey] : 0;
					}
					if(!empty($department_name)){
						$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_DEPARTMENT' 
							AND `slug`='{$core->replaceSpace($department_name)}'", $clsProperty->pkey);
						$department_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					if(!empty($staff_name)){
						$sql_query = "`is_trash`=0";
						$sql_query.= ($department_id > 0) ? " AND `department_id`='{$department_id}'" : "";
						$field = "{$clsProfile->pkey},`department_id`";
						$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='{$core->replaceSpace($staff_name)}'", $field);
						if(!empty($tmp)){
							$staff_id = $tmp[$clsProfile->pkey];
							$department_id = $tmp['department_id'];
						} else {
							$tmp = $clsProfile->getByCond("`is_trash`=0 AND `full_name_slug`='".$core->replaceSpace($staff_name)."'", $field);
							if(!empty($tmp)){
								$staff_id = $tmp[$clsProfile->pkey];
								$department_id = $tmp['department_id'];
							}
						}
					}
					if(!empty($project_name)){
						$tmp = $clsProject->getByCond("slug='{$core->replaceSpace($project_name)}'", $clsProject->pkey);
						if(!empty($tmp)){
							$project_id = $tmp[$clsProject->pkey];
							unset($tmp);
						} else {
							$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_BLOCK' AND (`slug`='".$core->replaceSpace($project_name)."' 
							OR `slug_vn`='".$core->replaceSpace($project_name)."' OR `property_code`='".$project_name."')", "{$clsProperty->pkey},`for_id`");
							if(!empty($tmp)){
								$project_id = $tmp['for_id'];
								$block_id = $tmp[$clsProperty->pkey];
								unset($tmp);
							}
						}
					}
					$tmp = $clsMarketingSpending->getByCond("`month`='{$month}' AND `staff_id`='{$staff_id}' 
						AND `project_id`='{$project_id}' AND `block_id`='{$block_id}'");
					if(!empty($tmp)){
						$arr_founds[] = $tmp[$clsMarketingSpending->pkey];
						$more_information = $tmp['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						if($tmp['chanel_id'] == $chanel_id && $tmp['ads_id'] == $ads_id){
							$more_information['project_name'] = $project_name;
							$more_information['chanel_name'] = $chanel_name;
							$more_information['ads_name'] = $ads_name;
							$more_information['ads_id'] = $ads_id;
							$more_information['ads_link'] = $ads_link;
							$more_information['company_support_rate'] = $company_support_rate;
							$more_information['company_support_amount'] = $company_support_amount;
							$clsMarketingSpending->updateOne($tmp[$clsMarketingSpending->pkey], array(
								'ads_id' => $ads_id,
								'amount' => $clsISO->processSmartNumber($amount),
								'company_support_amount' => $clsISO->processSmartNumber($company_support_amount),
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));
						} else {
							$chanel_name_merge = $core->get_field($more_information, "chanel_name", "");
							$ads_name_merge = $core->get_field($more_information, "ads_name", "");
							$ads_id_merge = $core->get_field($more_information, "ads_id", "");
							$ads_link_merge = $core->get_field($more_information, "ads_link", "");
							$company_support_rate_merge = $core->get_field($more_information, "company_support_rate", "");
							$company_support_amount_merge = $core->get_field($more_information, "company_support_amount", "");
							if(!empty($chanel_name) && !$clsISO->checkContainer($chanel_name_merge, $chanel_name, ""))
								$chanel_name_merge.= ",".$chanel_name;
							if(!empty($ads_name) && !$clsISO->checkContainer($ads_name_merge, $ads_name, ""))
								$chanel_name_merge.= ",".$ads_name;
							if(!empty($ads_id) && !$clsISO->checkContainer($ads_id_merge, $ads_id, ""))
								$chanel_name_merge.= ",".$ads_id;
							if(!empty($ads_link) && !$clsISO->checkContainer($ads_link_merge, $ads_link, ""))
								$ads_link_merge.= ",".$ads_link;
							
							if(!empty($company_support_rate) && !$clsISO->checkContainer($company_support_rate_merge, $company_support_rate, ""))
								$company_support_rate_merge.= ",".$company_support_rate;
							if(!empty($company_support_amount) && !$clsISO->checkContainer($company_support_amount_merge, $company_support_amount, ""))
								$company_support_amount_merge.= ",".$company_support_amount;
							
							$more_information['chanel_name'] = $chanel_name_merge;
							$more_information['ads_name'] = $ads_name_merge;
							$more_information['ads_id'] = $ads_id_merge;
							$more_information['ads_link'] = $ads_link_merge;
							$more_information['company_support_rate'] = $company_support_rate_merge;
							$more_information['company_support_amount'] = $company_support_amount_merge;
							#
							$amount_merge = $tmp['amount'] + $clsISO->processSmartNumber($amount);
							$company_support_amount_merge = $tmp['company_support_amount'] + $clsISO->processSmartNumber($company_support_amount);
							$clsMarketingSpending->updateOne($tmp[$clsMarketingSpending->pkey], array(
								'amount' => $amount_merge,
								'company_support_amount' => $company_support_amount_merge,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));
						}
					} else {
						$id = $clsMarketingSpending->getMaxId();
						$more_information = array(
							'project_name' => $project_name,
							'chanel_name' => $chanel_name,
							'ads_name' => $ads_name,
							'ads_id' => $ads_id,
							'ads_link' => $ads_link,
							'company_support_rate' => $company_support_rate,
							'company_support_amount' => $company_support_amount
						);
						if($clsMarketingSpending->insert(array(
							$clsMarketingSpending->pkey => $id,
							'month' => $month,
							'staff_id' => $staff_id,
							'department_id' => $department_id,
							'chanel_id' => $chanel_id,
							'ads_id' => $ads_id,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'amount' => $clsISO->processSmartNumber($amount),
							'company_support_amount' => $clsISO->processSmartNumber($company_support_amount),
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'reg_date' => time(),
							'upd_date' => time()
						))){
							$arr_founds[] = $id;
						}
					}
				}
			}
			if(!empty($arr_founds)){
				$clsMarketingSpending->deleteByCond("`month`='{$month}' AND {$clsMarketingSpending->pkey} NOT IN(".implode(',', $arr_founds).")");
			} else {
				$clsMarketingSpending->deleteByCond("`month`='{$month}'");
			}
		}
	} else {
		$msg = "_empty";
	}
	// Return
	echo $msg; die();
}
function default_do_import_bkc(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$msg = "_error";
	$tp = Input::post('tp');
	$month = Input::post('month');
	$by = Input::post('by', "ID");
	$spreadsheetId = Input::post('spreadsheetId');
	$sheet_name = Input::post('sheet_name');
	if($by = "URL" && $clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
		@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
		$spreadsheetId = $matches[0];
	}
	#- Require library		
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
	if(!empty($tblData)){
		$msg = "_success";
		$arr_founds = array();
		$department_cached = "";
		$total_record = count($tblData);
		if($tp == 'budget_register'){
			// $clsISO->print_pre($tblData); die();
			for($i=4; $i<$total_record; $i++){
				$department_name = trim($tblData[$i][1]);
				$staff_name = trim($tblData[$i][2]);
				$project_name = trim($tblData[$i][3]);
				$fb_ads = trim($tblData[$i][4]);
				$gg_ads = trim($tblData[$i][5]);
				$zalo_ads = trim($tblData[$i][6]);
				$tiktok_ads = trim($tblData[$i][7]);
				if(!empty($department_name) && !empty($staff_name) && !empty($project_name)  
					&& (!empty($fb_ads) || !empty($gg_ads) || !empty($zalo_ads) || !empty($tiktok_ads))){
					$department_id = $project_id = $block_id = $staff_id = 0;
					if(!empty($department_name)){
						$field = "{$clsProperty->pkey}";
						$tmp = $clsProperty->getByCond("`property_type`='_DEPARTMENT' AND `slug`='{$core->replaceSpace($department_name)}'", $field);
						$department_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					if(!empty($staff_name)){
						$sql_query = "`is_trash`=0 AND status_id<>'"._STATUS_STAFF_OFF_ID."'";
						$sql_query.= ($department_id > 0) ? " AND `department_id`='{$department_id}'" : "";
						$field = "{$clsProfile->pkey},`department_id`";
						$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='{$core->replaceSpace($staff_name)}'", $field);
						if(!empty($tmp)){
							$staff_id = $tmp[$clsProfile->pkey];
							$department_id = $tmp['department_id'];
						} else {
							$sql_query = "`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
							$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='{$core->replaceSpace($staff_name)}'", $field);
							if(!empty($tmp)){
								$staff_id = $tmp[$clsProfile->pkey];
								$department_id = $tmp['department_id'];
							}
						}
					}
					if(!empty($project_name)){
						$tmp = $clsProject->getByCond("slug='{$core->replaceSpace($project_name)}'", $clsProject->pkey);
						if(!empty($tmp)){
							$project_id = $tmp[$clsProject->pkey];
							unset($tmp);
						} else {
							$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`slug`='".$core->replaceSpace($project_name)."' 
							OR `slug_vn`='".$core->replaceSpace($project_name)."' OR `property_code`='".$project_name."')", "{$clsProperty->pkey},`for_id`");
							if(!empty($tmp)){
								$project_id = $tmp['for_id'];
								$block_id = $tmp[$clsProperty->pkey];
								unset($tmp);
							}
						}
					}
					$tmp = $clsMarketingBudgetRegister->getByCond("`month`='{$month}' AND `department_id`='{$department_id}' 
						AND `staff_id`='{$staff_id}' AND `project_id`='{$project_id}' AND `block_id`='{$block_id}'");
					if(!empty($tmp)){
						$arr_founds[] = $tmp[$clsMarketingSpending->pkey];
						$clsMarketingSpending->updateOne($tmp[$clsMarketingSpending->pkey], array(
							'fb_ads' => $clsISO->processSmartNumber($fb_ads),
							'gg_ads' => $clsISO->processSmartNumber($gg_ads),
							'zalo_ads' => $clsISO->processSmartNumber($zalo_ads),
							'tiktok_ads' => $clsISO->processSmartNumber($tiktok_ads)
						));
					} else {
						$id = $clsMarketingBudgetRegister->getMaxId();
						if($clsMarketingBudgetRegister->insert(array(
							$clsMarketingBudgetRegister->pkey => $id,
							'month' => $month,
							'staff_id' => $staff_id,
							'department_id' => $department_id,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'fb_ads' => $clsISO->processSmartNumber($fb_ads),
							'gg_ads' => $clsISO->processSmartNumber($gg_ads),
							'zalo_ads' => $clsISO->processSmartNumber($zalo_ads),
							'tiktok_ads' => $clsISO->processSmartNumber($tiktok_ads),
							'status' => 'pending',
							'reg_date' => time(),
							'upd_date' => time(),
						))){
							$arr_founds[] = $id;
						}
					}
				}
			}
			if(!empty($arr_founds)){
				$clsMarketingBudgetRegister->deleteByCond("`month`='{$month}' AND {$clsMarketingBudgetRegister->pkey} NOT IN(".implode(',', $arr_founds).")");
			} else {
				$clsMarketingBudgetRegister->deleteByCond("`month`='{$month}'");
			}
		} else {
			for($i=2; $i<$total_record; $i++){
				if(!empty($tblData[$i][0])){
					$department_name = trim($tblData[$i][0]);
					$department_cached = $department_name;
				} else {
					$department_name = $department_cached;
				}
				$staff_name = trim($tblData[$i][1]);
				$project_name = trim($tblData[$i][2]);
				$amount = trim($tblData[$i][4]);
				if(!empty($staff_name) && !empty($project_name) && !empty($amount)){
					$department_id = $project_id = $block_id = $staff_id = 0;
					if(!empty($department_name)){
						$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_DEPARTMENT' AND `slug`='{$core->replaceSpace($department_name)}'");
						$department_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					if(!empty($staff_name)){
						$sql_query = "`is_trash`=0";
						$sql_query.= ($department_id > 0) ? " AND `department_id`='{$department_id}'" : "";
						$field = "{$clsProfile->pkey},`department_id`";
						$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='{$core->replaceSpace($staff_name)}'", $field);
						if(!empty($tmp)){
							$staff_id = $tmp[$clsProfile->pkey];
							$department_id = $tmp['department_id'];
						} else {
							$sql_query = "`is_trash`=0";
							$tmp = $clsProfile->getByCond("{$sql_query} AND `full_name_slug`='".$core->replaceSpace($staff_name)."'", $field);
							if(!empty($tmp)){
								$staff_id = $tmp[$clsProfile->pkey];
								$department_id = $tmp['department_id'];
							}
						}
					}
					if(!empty($project_name)){
						$tmp = $clsProject->getByCond("slug='{$core->replaceSpace($project_name)}'", $clsProject->pkey);
						if(!empty($tmp)){
							$project_id = $tmp[$clsProject->pkey];
							unset($tmp);
						} else {
							$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_BLOCK' AND (`slug`='".$core->replaceSpace($project_name)."' 
							OR `slug_vn`='".$core->replaceSpace($project_name)."' OR `property_code`='".$project_name."')", "{$clsProperty->pkey},`for_id`");
							if(!empty($tmp)){
								$project_id = $tmp['for_id'];
								$block_id = $tmp[$clsProperty->pkey];
								unset($tmp);
							}
						}
					}
					$tmp = $clsMarketingSpending->getByCond("`month`='{$month}' AND `department_id`='{$department_id}' 
						AND `staff_id`='{$staff_id}' AND project_id='{$project_id}' AND `block_id`='{$block_id}'");
					if(!empty($tmp)){
						$arr_founds[] = $tmp[$clsMarketingSpending->pkey];
						$clsMarketingSpending->updateOne($tmp[$clsMarketingSpending->pkey], array(
							'amount' => $clsISO->processSmartNumber($amount)
						));
					} else {
						$id = $clsMarketingSpending->getMaxId();
						if($clsMarketingSpending->insert(array(
							$clsMarketingSpending->pkey => $id,
							'spend_date' => time(),
							'month' => $month,
							'staff_id' => $staff_id,
							'department_id' => $department_id,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'amount' => $clsISO->processSmartNumber($amount),
							'reg_date' => time(),
						))){
							$arr_founds[] = $id;
						}
					}
				}
			}
			if(!empty($arr_founds)){
				$clsMarketingSpending->deleteByCond("`month`='{$month}' AND {$clsMarketingSpending->pkey} NOT IN(".implode(',', $arr_founds).")");
			} else {
				$clsMarketingSpending->deleteByCond("`month`='{$month}'");
			}
		}
	} else {
		$msg = "_empty";
	}
	// Return
	echo $msg; die();
}
function default_load_overview(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$date_type = Input::post('date_type', '_month');
	$month = Input::post('month', date('Y-m'));
	$quarter = (int) Input::post('quarter');
	$year = (int) Input::post('year', date('Y'));
	$project_id = Input::post('project_id', 0);
	$block_id = Input::post('block_id', 0);
	$department_id = Input::post('department_id', 0);
	#
	if($date_type == '_month'){
		$cond = "`month`='{$month}'";
	} else if($date_type == '_quarter'){
		if($quarter == 1) $arr_months = ["{$year}-01", "{$year}-02", "{$year}-03"];
		if($quarter == 2) $arr_months = ["{$year}-04", "{$year}-05", "{$year}-06"];
		if($quarter == 3) $arr_months = ["{$year}-07", "{$year}-08", "{$year}-09"];
		if($quarter == 4) $arr_months = ["{$year}-10", "{$year}-11", "{$year}-12"];
		$cond = "`month` in ('".implode('\',\'', $arr_months)."')";
	} else if($date_type == '_year'){
		$current_month = date('n');
		$arr_months = array();
		for($month=1; $month <= $current_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`month` in ('".implode('\',\'', $arr_months)."')";
	}
	$total_registers = $clsMarketingBudgetRegister->sumItem("total_budget", $cond);
	$total_amounts = $clsMarketingSpending->sumItem("amount", $cond);
	$total_company_support_amounts = $clsMarketingSpending->sumItem("company_support_amount", $cond);
	$total_sale_support_amounts = $clsMarketingSpending->sumItem("sale_support_amount", $cond);
	#
	$marketing_json_cached = array();
	if($clsCache->has('marketing_json_cached')){
		$marketing_json_cached = $clsCache->get('marketing_json_cached');
	}
	$marketing_json_cached['summary'] = array(
		'total_budget' => $total_registers,
		'total_spent' => $total_amounts,
		'company_support' => $total_company_support_amounts,
		'sale_pay' => $total_sale_support_amounts,
	);
	$clsCache->put('marketing_json_cached', json_encode($marketing_json_cached, JSON_UNESCAPED_UNICODE));
	#
	$html= '<div class="brief-item bg-orange">
		<div class="d-flex align-items-center">
			<div class="w-px-50">
				<i class=\'bx bx-wallet-alt\' style="font-size:42px"></i>
			</div>
			<div class="d-flex flex-column">
				<p class="fs-16 mb-2"> Tổng đăng ký</p>
				<h3 class="fs-5 mb-0">'.$clsISO->formatPrice($total_registers).$clsISO->getRate().'</h3>
			</div>
		</div>
	</div>
	<div class="brief-item  bg-azure">
		<div class="d-flex align-items-center">
			<div class="w-px-50">
				<i class=\'bx bx-wallet-alt\' style="font-size:42px"></i>
			</div>
			<div class="d-flex flex-column">
				<p class="fs-16 mb-2"> Tổng chạy</p>
				<h3 class="fs-5 mb-0">'.$clsISO->formatPrice($total_amounts).$clsISO->getRate().'</h3>
			</div>
		</div>
	</div>
	<div class="brief-item bg-cyan" bis_skin_checked="1">
		<div class="d-flex align-items-center">
			<div class="w-px-50">
				<i class=\'bx bx-pie-chart\' style="font-size:42px"></i>
			</div>
			<div class="d-flex flex-column">
				<p class="fs-16 mb-2"> Công ty hỗ trợ</p>
				<h3 class="fs-5 mb-0">'.$clsISO->formatPrice($total_company_support_amounts).$clsISO->getRate().'</h3>
			</div>
		</div>
	</div>
	<div class="brief-item bg-danger" bis_skin_checked="1">
		<div class="d-flex align-items-center">
			<div class="w-px-50">
				<i class=\'bx bx-wallet-alt\' style="font-size:42px"></i>
			</div>
			<div class="d-flex flex-column">
				<p class="fs-16 mb-2"> Sale chịu</p>
				<h3 class="fs-5 mb-0">'.$clsISO->formatPrice($total_sale_support_amounts).$clsISO->getRate().'</h3>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_budget_channel(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', '_month');
	$month = Input::post('month', date('Y-m'));
	$quarter = (int) Input::post('quarter');
	$year = (int) Input::post('year', date('Y'));
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$department_id = Input::post('department_id', 0);
	#
	$data = $barChartData = $dataPoints = array();
	$barChartData['animationEnabled'] = true;
	$chanel_arrs = array(
		'fb_ads' => array(
			'title' => 'Facebook',
			'color' => '#4564C8',
			'total_budget' => 0
		), 'gg_ads' => array(
			'title' => 'Google',
			'color' => '#E84333',
			'total_budget' => 0
		), 'zalo_ads' => array(
			'title' => 'Zalo',
			'color' => '#2292D0',
			'total_budget' => 0
		), 'tiktok_ads' => array(
			'title' => 'Tiktok',
			'color' => '#01F2EB',
			'total_budget' => 0
		)
	);
	#
	if($date_type == '_month'){
		$cond = "`month`='{$month}'";
	} else if($date_type == '_quarter'){
		if($quarter == 1) $arr_months = ["{$year}-01", "{$year}-02", "{$year}-03"];
		if($quarter == 2) $arr_months = ["{$year}-04", "{$year}-05", "{$year}-06"];
		if($quarter == 3) $arr_months = ["{$year}-07", "{$year}-08", "{$year}-09"];
		if($quarter == 4) $arr_months = ["{$year}-10", "{$year}-11", "{$year}-12"];
		$cond = "`month` in ('".implode('\',\'', $arr_months)."')";
	} else if($date_type == '_year'){
		$current_month = date('n');
		$arr_months = array();
		for($month=1; $month <= $current_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`month` in ('".implode('\',\'', $arr_months)."')";
	}
	$tmp = $clsMarketingBudgetRegister->getAll($cond, "`fb_ads`,`gg_ads`,`zalo_ads`,`tiktok_ads`");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$fb_ads = $clsISO->processSmartNumber($val['fb_ads']);
			$gg_ads = $clsISO->processSmartNumber($val['gg_ads']);
			$zalo_ads = $clsISO->processSmartNumber($val['zalo_ads']);
			$tiktok_ads = $clsISO->processSmartNumber($val['tiktok_ads']);
			$chanel_arrs['fb_ads']['total_budget']+= $fb_ads;
			$chanel_arrs['gg_ads']['total_budget']+= $gg_ads;
			$chanel_arrs['zalo_ads']['total_budget']+= $zalo_ads;
			$chanel_arrs['tiktok_ads']['total_budget']+= $tiktok_ads;
		}
		unset($tmp);
	}
	#
	$marketing_json_cached = array();
	if($clsCache->has('marketing_json_cached')){
		$marketing_json_cached = $clsCache->get('marketing_json_cached');
	}
	$html_points = "";
	foreach($chanel_arrs as $key => $val){
		$colorSet[] = $val['color'];
		$marketing_json_cached['by_channel'][] = array(
			'channel' => $val['title'],
			'budget' => $val['total_budget'],
			'spent' => 0
		);
		$dataPoints[] = array(
			'label' => $val['title'],
			'y' => $val['total_budget'],
			'indexLabel' => (string) $val['total_budget']
		);
		$html_points.= '<li class="d-flex align-items-center w-100 py-1 gap-2">
			<span class="d-inline-block w-px-15 h-px-15 rounded-1" style="background:'.$val['color'].'"></span> 
			<span>'.$val['title'].': <strong>'.$clsISO->shortNumber($val['total_budget']).'</strong></span>
		</li>';
	}
	$clsCache->put('marketing_json_cached', json_encode($marketing_json_cached, JSON_UNESCAPED_UNICODE));
	$html = '<div class="form-row">
		<div class="col-6">
			<div id="'.$uid.'" class="chartContainer h-px-300"></div>
		</div>
		<div class="col-6">
			<ul class="list-unstyled" style="padding-top:6rem;">
				'.$html_points.'
			</ul>
		</div>
	</div>';
	$data['type'] = 'doughnut';
	$data['indexLabel'] = '{y}';
	$data['innerRadius'] = '40';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	$data['toolTipContent'] = 'Số lượng GD: {total}<br /> Doanh số: {y}';
	$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['colorSet'] = $colorSet;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function default_load_budget_project(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', '_month');
	$month = Input::post('month', date('Y-m'));
	$quarter = (int) Input::post('quarter');
	$year = (int) Input::post('year', date('Y'));
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$department_id = (int) Input::post('department_id', 0);
	#
	$data = $barChartData = $dataBudgetPoints = $dataSpentPoints = $middle = array();
	$arr_projects = $arr_blocks = $arr_project_ids = $arr_block_ids = array();
	$barChartData['zoomEnabled'] = true;
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip']["shared"] = true;
	if($date_type == '_month'){
		$cond = "`month`='{$month}'";
	} else if($date_type == '_quarter'){
		if($quarter == 1) $arr_months = ["{$year}-01", "{$year}-02", "{$year}-03"];
		if($quarter == 2) $arr_months = ["{$year}-04", "{$year}-05", "{$year}-06"];
		if($quarter == 3) $arr_months = ["{$year}-07", "{$year}-08", "{$year}-09"];
		if($quarter == 4) $arr_months = ["{$year}-10", "{$year}-11", "{$year}-12"];
		$cond = "`month` in ('".implode('\',\'', $arr_months)."')";
	} else if($date_type == '_year'){
		$current_month = date('n');
		$arr_months = array();
		for($month=1; $month <= $current_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`month` in ('".implode('\',\'', $arr_months)."')";
	}
	if($date_type == '_month'){
		$cond = "`t1`.`month`='{$month}'";
		$join_cond = "`t2`.`month`='{$month}'";
	} else if($date_type == '_quarter'){
		if($quarter == 1) $arr_months = ["{$year}-01", "{$year}-02", "{$year}-03"];
		if($quarter == 2) $arr_months = ["{$year}-04", "{$year}-05", "{$year}-06"];
		if($quarter == 3) $arr_months = ["{$year}-07", "{$year}-08", "{$year}-09"];
		if($quarter == 4) $arr_months = ["{$year}-10", "{$year}-11", "{$year}-12"];
		$cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$join_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	} else if($date_type == '_year'){
		$current_month = date('n');
		$arr_months = array();
		for($month=1; $month <= $current_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$join_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	}
	$field = "`t1`.`project_id`,`t1`.`block_id`,`t1`.`total_budget`,`t2`.`amount`";
	$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsMarketingBudgetRegister->tbl} AS `t1` 
		LEFT JOIN {$clsMarketingSpending->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`staff_id` AND `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` AND {$join_cond} WHERE {$cond}");
	if(!empty($tmp)){
		$marketing_json_cached = array();
		if($clsCache->has('marketing_json_cached')){
			$marketing_json_cached = $clsCache->get('marketing_json_cached');
		}
		foreach($tmp as $key => $val){
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$total_budget = $val['total_budget'];
			$total_amount = $val['amount'];
			$total_budget = $clsISO->processSmartNumber($total_budget);
			$total_amount = $clsISO->processSmartNumber($total_amount);
			if($block_id > 0 && !in_array($block_id, $arr_block_ids)) 
				$arr_block_ids[] = $block_id;
			if($project_id > 0 && !in_array($block_id, $arr_project_ids)) 
				$arr_project_ids[] = $project_id;
			
			$key = $project_id . '_' . $block_id;
			if (!isset($middle[$key])) {
				$middle[$key] = [
					'project_id'   => $project_id,
					'block_id'     => $block_id,
					'total_budget' => 0,
					'total_amount' => 0,
				];
			}
			$middle[$key]['total_budget'] += $total_budget;
			$middle[$key]['total_amount'] += $total_amount;
		}
		if(!empty($arr_project_ids)){
			$p_field = "{$clsProject->pkey},`title`";
			$tmp = $clsProject->getAll("{$clsProject->pkey} IN (".implode(',', $arr_project_ids).")", $p_field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_projects[$val[$clsProject->pkey]] = $val['title'];
				}
				unset($tmp);
			}
		}
		if(!empty($arr_block_ids)){
			$b_field = "{$clsProperty->pkey},`property_code`";
			$tmp = $clsProperty->getAll("`property_type`='_BLOCK' AND {$clsProperty->pkey} IN (".implode(',', $arr_block_ids).")", $b_field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_blocks[$val[$clsProperty->pkey]] = $val['property_code'];
				}
				unset($tmp);
			}
		}
		$order_arr = @array_column($middle, "total_budget");
		@array_multisort($order_arr, SORT_DESC, $middle);
		foreach($middle as $key => $val){
			$block_id = (int) $val['block_id'];
			$project_id = (int) $val['project_id'];
			if($block_id > 0) {
				$project_name = $arr_blocks[$block_id];
			} else {
				$project_name = $arr_projects[$project_id];
			}
			$marketing_json_cached['by_project'][] = array(
				'project' => $project_name, 
				'budget' => $val['total_budget'],
				'spent' => $val['total_amount'],
			);
			$dataBudgetPoints[] = array(
				'label' => $project_name,
				'y' => $val['total_budget'],
				'indexLabel' => sprintf('%s', $clsISO->shortNumber($val['total_budget']))
			);
			$dataSpentPoints[] = array(
				'label' => $project_name,
				'y' => $val['total_amount'],
				'indexLabel' => sprintf('%s', $clsISO->shortNumber($val['total_amount']))
			);
		}
		$clsCache->put('marketing_json_cached', json_encode($marketing_json_cached, JSON_UNESCAPED_UNICODE));
	}
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	$barChartData['data'] = array(
		array(
			"type" => "stackedColumn",
			"name"	=> "Ngân sách dự kiến",
			"indexLabelPlacement" => "inside",
			'indexLabelFontColor' => '#FFF',
			"showInLegend" => true,
			"dataPoints" => $dataBudgetPoints
		), array(
			"type" => "stackedColumn",
			"name" => "Ngân sách thực tế",
			"indexLabelPlacement" => "outside",
			"showInLegend" => true,
			"dataPoints" => $dataSpentPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function default_load_budget_detail(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', '_month');
	$month = Input::post('month', date('Y-m'));
	$quarter = (int) Input::post('quarter');
	$year = (int) Input::post('year', date('Y'));
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$department_id = (int) Input::post('department_id', 0);
	#
	$html= '';
	if($date_type == '_month'){
		$cond = "`t1`.`month`='{$month}'";
		$join_cond = "`t2`.`month`='{$month}'";
	} else if($date_type == '_quarter'){
		if($quarter == 1) $arr_months = ["{$year}-01", "{$year}-02", "{$year}-03"];
		if($quarter == 2) $arr_months = ["{$year}-04", "{$year}-05", "{$year}-06"];
		if($quarter == 3) $arr_months = ["{$year}-07", "{$year}-08", "{$year}-09"];
		if($quarter == 4) $arr_months = ["{$year}-10", "{$year}-11", "{$year}-12"];
		$cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$join_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	} else if($date_type == '_year'){
		$current_month = date('n');
		$arr_months = array();
		for($month=1; $month <= $current_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$join_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	}
	$field = "t1.*,`t2`.`amount`,`t2`.`company_support_amount`,`t2`.`sale_support_amount`";
	#Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 10);
	// $dbconn->debug = true;
	$tmp = $dbconn->getRow("SELECT COUNT(1) as `total_record`, SUM(`t1`.`total_budget`) as `total_budgets`, SUM(`t2`.`amount`) as `total_amounts`, SUM(`t2`.`company_support_amount`) AS `total_company_support_amounts`, SUM(`t2`.`sale_support_amount`) AS `total_sale_support_amounts` FROM {$clsMarketingBudgetRegister->tbl} AS `t1` LEFT JOIN {$clsMarketingSpending->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`staff_id` AND `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` AND {$join_cond} WHERE {$cond}");
	$total_record = $total_amounts = $total_budgets = $total_company_support_amounts = $total_sale_support_amounts =  0;
	if(!empty($tmp)){
		$total_record = $tmp['total_record'];
		$total_amounts = $tmp['total_amounts'];
		$total_budgets = $tmp['total_budgets'];
		$total_company_support_amounts = $tmp['total_company_support_amounts'];
		$total_sale_support_amounts = $tmp['total_sale_support_amounts'];
	}
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	#End Pagination
	$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsMarketingBudgetRegister->tbl} AS `t1` 
		LEFT JOIN {$clsMarketingSpending->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`staff_id` AND `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` AND `t2`.`month`='{$month}' WHERE {$cond} ORDER BY `staff_id` ASC".$limitCond);
	if(!empty($tmp)){ $ii = 0;
		$arr_profile_cached = $clsProfile->getProfileCached();
		$arr_project_ids = $arr_block_ids = $arr_projects = $arr_blocks = array();
		foreach($tmp as $key => $val){
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			if($block_id > 0 && !in_array($block_id, $arr_block_ids)) $arr_block_ids[] = $block_id;
			if($project_id > 0 && !in_array($project_id, $arr_project_ids)) $arr_project_ids[] = $project_id;
		}
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
		foreach($tmp as $key => $val){
			$staff_id = $val['staff_id'];
			$block_id = (int) $val['block_id'];
			$project_id = (int) $val['project_id'];
			$total_budget = $clsISO->processSmartNumber($val['total_budget']);
			$fb_ads = $clsISO->processSmartNumber($val['fb_ads']);
			$gg_ads = $clsISO->processSmartNumber($val['gg_ads']);
			$zalo_ads = $clsISO->processSmartNumber($val['zalo_ads']);
			$tiktok_ads = $clsISO->processSmartNumber($val['tiktok_ads']);
			$total_amount = $clsISO->processSmartNumber($val['amount']);
			$company_support_amount = $clsISO->processSmartNumber($val['company_support_amount']);
			$sale_support_amount = $clsISO->processSmartNumber($val['sale_support_amount']);
			$used_percent = $total_budget > 0 ? round($total_amount / $total_budget * 100, 2) : 0;
			
			$oneStaff = $arr_profile_cached[$staff_id];
			$more_information = $oneStaff['more_information'];
			$department_name = $core->get_field($more_information, "department_name", "");
			$project_name = ($block_id > 0) ? $arr_blocks[$block_id] : $arr_projects[$project_id];
			
			$html.= '<tr>
				<td class="align-center text-dark text-center">'.($ii+1).'</td>
				<td class="align-center text-dark">'.$department_name.'</td>
				<td class="align-center text-dark">'.$clsProfile->getFullName($staff_id, $oneStaff).'</td>
				<td class="align-center text-dark">'.$project_name.'</td>
				<td class="align-center text-right text-dark">
					'.(!empty($fb_ads) ? '<span class="fb_ads">
						<i class=\'bx bxl-facebook me-1\'></i>'.$clsISO->shortNumber($fb_ads,1).'</span>' : '').'
					'.(!empty($gg_ads) ? '<span class="gg_ads">
						<i class=\'bx bxl-google me-1\'></i>'.$clsISO->shortNumber($gg_ads,1).'</span>' : '').'
					'.(!empty($zalo_ads) ? '<span class="zalo_ads">
						<i class=\'re__icon-zalo-white me-1\'></i>'.$clsISO->shortNumber($zalo_ads,1).'</span>' : '').'
					'.(!empty($tiktok_ads) ? '<span class="tiktok_ads">
						<i class=\'bx bxl-tiktok me-1\'></i>'.$clsISO->shortNumber($tiktok_ads,1).'</span>' : '').'
				</td>
				<td class="align-center text-right text-main">'.$clsISO->shortNumber($total_amount,3).'</td>
				<td class="align-center text-center">
					<div class="d-flex align-items-center  gap-1">
						<div class="progress w-px-50" style="height:12px;">
						  <div class="progress-bar bg-info" role="progressbar" style="width:'.$used_percent.'%;"></div>
						</div> '.$used_percent.'%
					</div>
				</td>
				<td class="align-center text-right text-primary fw-bold">'.$clsISO->shortNumber($company_support_amount,3).'</td>
				<td class="align-center text-right text-dark">'.$clsISO->shortNumber($sale_support_amount,3).'</td>
			</tr>';
			++$ii;
		}
		$html.='<tr class="fw-bold">
			<td class="bg-lighter text-dark text-center"></td>
			<td colspan="3" class="bg-lighter text-dark text-center">TỘNG CỘNG: </td>
			<td class="bg-lighter text-right text-dark">'.$clsISO->shortNumber($total_budgets,3).'</td>
			<td class="bg-lighter text-right text-dark text-dark">'.$clsISO->shortNumber($total_amounts,3).'</td>
			<td class="bg-lighter text-right text-dark text-dark text-dark">'.$clsISO->shortNumber($total_company_support_amounts,3).'</td>
			<td class="bg-lighter text-right text-dark">'.$clsISO->shortNumber($total_sale_support_amounts,3).'</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'per_page' => $per_page,
		'current_page' => $current_page
	)); die();
}
function default_load_area(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	
	$uid = $clsISO->getUniqid();
	$date_type = Input::post('date_type', '_month');
	$month = Input::post('month', date('Y-m'));
	$quarter = (int) Input::post('quarter');
	$year = (int) Input::post('year', date('Y'));
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$department_id = (int) Input::post('department_id', 0);
	
	$html.= '';
	if($date_type == '_month'){
		$cond = "`t1`.`month`='{$month}'";
		$join_cond = "`t2`.`month`='{$month}'";
	} else if($date_type == '_quarter'){
		if($quarter == 1) $arr_months = ["{$year}-01", "{$year}-02", "{$year}-03"];
		if($quarter == 2) $arr_months = ["{$year}-04", "{$year}-05", "{$year}-06"];
		if($quarter == 3) $arr_months = ["{$year}-07", "{$year}-08", "{$year}-09"];
		if($quarter == 4) $arr_months = ["{$year}-10", "{$year}-11", "{$year}-12"];
		$cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$join_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	} else if($date_type == '_year'){
		$current_month = date('n');
		$arr_months = array();
		for($month=1; $month <= $current_month; $month++){
			$arr_months[] = sprintf('%s-%s', $year, $clsISO->parseNumber($month));
		}
		$cond = "`t1`.`month` in ('".implode('\',\'', $arr_months)."')";
		$join_cond = "`t2`.`month` in ('".implode('\',\'', $arr_months)."')";
	}
	$tmp = $dbconn->getAll("SELECT `t1`.`department_id`, SUM(`t1`.`total_budget`) as `total_budgets`, SUM(`t2`.`amount`) as `total_amounts`, SUM(`t2`.`company_support_amount`) AS `total_company_support_amounts`, SUM(`t2`.`sale_support_amount`) AS `total_sale_support_amounts` FROM {$clsMarketingBudgetRegister->tbl} AS `t1` LEFT JOIN {$clsMarketingSpending->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`staff_id` AND `t1`.`project_id`=`t2`.`project_id` AND `t1`.`block_id`=`t2`.`block_id` AND {$join_cond} WHERE {$cond} GROUP By `t1`.`department_id`");
	$total_record = $total_amounts = $total_budgets = $total_company_support_amounts = $total_sale_support_amounts =  0;
	
	$data = $barChartData = $dataPoints = $dataPoints2 = $dataPoints3 = array();
	// $barChartData['zoomEnabled'] = true;
	$barChartData['animationEnabled'] = true;
	$barChartData['toolTip']["shared"] = true;
	//$barChartData['dataPointMaxWidth'] = 20;
	/*$barChartData['axisY'] = array(
		'title' => 'Doanh số bán hàng',
		'titleFontSize' => '16',
		'lineColor' => '#1d6a01',
		'tickColor' => '#1d6a01',
		'labelFontColor' => '#1d6a01',
		'titleFontColor' => '#1d6a01',
		'labelFormatter' => 1,
		'interval' => 1
	); */
	if(!empty($tmp)){
		$regionSummary = [];
		$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		$regions = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
		
		$costIndex = [];
		foreach($tmp as $key => $row){
			$department_id = (int) $row['department_id'];
			$costIndex[$department_id] = [
				'total_budgets' => (int) ($row['total_budgets'] ?? 0),
				'total_amounts' => (int) ($row['total_amounts'] ?? 0),
				'total_company_support_amounts' => (int) ($row['total_company_support_amounts'] ?? 0),
				'total_sale_support_amounts' => (int) ($row['total_sale_support_amounts'] ?? 0),
			];
		}
		$marketing_json_cached = array();
		if($clsCache->has('marketing_json_cached')){
			$marketing_json_cached = $clsCache->get('marketing_json_cached');
		}
		foreach ($regions as $regionId => $region) {
			// Chỉ lấy vùng cấp 1
			if (($region['parent_id'] ?? null) != 40) {
				continue;
			}
			// --- Chi phí của CHÍNH vùng ---
			$selfCost = $costIndex[$regionId] ?? [
				'total_budgets' => 0,
				'total_amounts' => 0,
				'total_company_support_amounts' => 0,
				'total_sale_support_amounts' => 0,
			];
			$totalBudgets = $selfCost['total_budgets'];
			$totalAmounts = $selfCost['total_amounts'];
			$totalCompany = $selfCost['total_company_support_amounts'];
			$totalSale    = $selfCost['total_sale_support_amounts'];
			// --- Cộng thêm chi phí của CHILDREN ---
			if (!empty($region['children'])) {
				foreach ($region['children'] as $child) {
					$childId = (int) $child['property_id'];
					if (!isset($costIndex[$childId])) {
						continue;
					}
					$totalBudgets += $costIndex[$childId]['total_budgets'];
					$totalAmounts += $costIndex[$childId]['total_amounts'];
					$totalCompany += $costIndex[$childId]['total_company_support_amounts'];
					$totalSale    += $costIndex[$childId]['total_sale_support_amounts'];
				}
			}
			// --- Kết quả vùng ---
			$regionSummary[$regionId] = [
				'region_id'   => $regionId,
				'region_code' => $region['property_code'] ?? '',
				'region_name' => $region['title'] ?? '',
				'total_budgets' => $totalBudgets,
				'total_amounts' => $totalAmounts,
				'total_company_support_amounts' => $totalCompany,
				'total_sale_support_amounts' => $totalSale,
				// Optional – rất hữu ích cho dashboard
				'used_percent' => $totalBudgets > 0 ? round($totalAmounts / $totalBudgets * 100, 2) : 0,
			];
		}
		uasort($regionSummary, function ($a, $b) {
			return $b['total_amounts'] <=> $a['total_amounts'];
		});
		$html.= '<div class="row">
			<div class="col-12 col-md-6">
				<div id="'.$uid.'" class="chartContainer h-px-350"></div>
			</div>
			<div class="col-12 col-md-6">
				<div class="border overflow-hidden rounded-2">
					<table class="table table-striped text-nowrap">
						<thead><tr>
							<th class="h-px-35">Vùng KD</th>
							<th class="h-px-35">Ngân sách</th>
							<th class="h-px-35">Tổng chi</th>
							<th class="h-px-35">% chi</th>
							<th class="h-px-35">Hỗ trợ</th>
							<!-- <th class="h-px-35">Sale chịu</th> -->
						</tr></thead>
						<tbody>';
						foreach($regionSummary as $okey => $oval){
							$dataPoints[] = array(
								'label' => $oval['region_name'],
								'y' => $oval['total_budgets']*1,
								//'indexLabel' => sprintf('%s VNĐ', $clsISO->shortNumber($oval['total_budgets']))
							);
							$dataPoints2[] = array(
								'label' => $oval['region_name'],
								'y' => $oval['total_amounts']*1,
								//'indexLabel' => sprintf('%s VNĐ', $clsISO->shortNumber($oval['total_amounts']))
							);
								$dataPoints3[] = array(
								'label' => $oval['region_name'],
								'y' => $oval['total_company_support_amounts']*1,
								//'indexLabel' => sprintf('%s VNĐ', $clsISO->shortNumber($oval['total_company_support_amounts']))
							);
							$html_points = "";
							$marketing_json_cached['by_region'][] = array(
								'region' => $oval['region_name'],
								'budget' => $oval['total_budgets'],
								'spent' => $oval['total_amounts']
							);
							$html.= '<tr>
								<td class="align-center border-bottom-0 fw-bold">'.$oval['region_name'].'</td>
								<td class="align-center border-bottom-0 bg-label-danger">'.$clsISO->shortNumber($oval['total_budgets']).'</td>
								<td class="align-center border-bottom-0 bg-label-warning">'.$clsISO->shortNumber($oval['total_amounts']).'</td>
								<td class="align-center border-bottom-0 bg-label-success">
									<div class="d-flex align-items-center gap-1">
										<div class="progress w-px-50" style="height:12px;">
										  <div class="progress-bar bg-info" role="progressbar" style="width:'.$oval['used_percent'].'%;"></div>
										</div> '.$oval['used_percent'].'%
									</div>
								</td>
								<td class="align-center border-bottom-0 bg-label-primary">'.$clsISO->shortNumber($oval['total_company_support_amounts']).'</td>
								<!-- <td class="align-center border-bottom-0 bg-label-info">'.$clsISO->shortNumber($oval['total_sale_support_amounts']).'</td> -->
							</tr>';
						}
			$html.= '</tbody>
				</table>
			</div>
		</div>';
		$clsCache->put('marketing_json_cached', json_encode($marketing_json_cached, JSON_UNESCAPED_UNICODE));
	}
	$barChartData['data'] = array(
		array(
			"type" => "stackedBar",
			"name"	=> "Ngân sách dự kiến",
			"showInLegend" => true,
			"dataPoints" => $dataPoints
		), array(
			"type" => "stackedBar",
			"name" => "Ngân sách thực tế",
			"showInLegend" => true,
			"dataPoints" => $dataPoints2
		), array(
			"type" => "stackedBar",
			"name" => "Công ty hỗ trợ",
			"showInLegend" => true,
			"dataPoints" => $dataPoints3
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function default_regis(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	#- Check Permiss
	if(!$clsISO->checkPermission('marketing_access')){
		$core->redirect('/#not-permiss');
	}
	$list_preloaders = array();
	for($i=0; $i <= 50; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["current_month"] = date('Y-m', strtotime("-1 month"));
	$assign_list["list_preloaders"] = $list_preloaders;
    /*=============Title & Description Page==================*/
	$title_page = 'Đăng ký ngân sách Marketing - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
} 
function default_load_regis(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	
	$month = Input::post('month', date('Y-m'));
	$cond = "`month`='{$month}'";
	$total_fb_ads = $total_gg_ads = $total_zalo_ads = $total_tiktok_ads = 0;
	$list_registers = $clsMarketingBudgetRegister->getAll($cond." ORDER BY `reg_date` DESC, `staff_id` DESC");
	// $clsISO->print_pre($list_registers); die();
	if(!empty($list_registers)){
		$arr_setting_cached = $arr_project_ids = $arr_block_ids = array();
		$arr_profile_cached = $clsProfile->getProfileCached();
		$tmp = $clsSetting->getAll("`_type`='_PROJECT'", "{$clsSetting->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_setting_cached[$val[$clsSetting->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach($list_registers as $key => $val){
			$block_id = (int) $val['block_id'];
			$project_id = (int) $val['project_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$total_fb_ads += $clsISO->processSmartNumber($val['fb_ads']);
			$total_gg_ads += $clsISO->processSmartNumber($val['gg_ads']);
			$total_zalo_ads += $clsISO->processSmartNumber($val['zalo_ads']);
			$total_tiktok_ads = $clsISO->processSmartNumber($val['tiktok_ads']);
			if((int) $core->get_field($more_information, "project_mapping_id", 0) > 0){
				// Next
			} else {
				if($block_id > 0 && !in_array($block_id, $arr_block_ids)) 
					$arr_block_ids[] = $block_id;
				if($project_id > 0 && !in_array($project_id, $arr_project_ids)) 
					$arr_project_ids[] = $project_id;
			}
		}
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
		foreach($list_registers as $key => $val){
			$staff_id = (int) $val['staff_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			
			$project_name = "";
			if(isset($more_information['project_mapping_id'])){
				$project_mapping_id = (int) $core->get_field($more_information, "project_mapping_id", 0);
				$project_name = $arr_setting_cached[$project_mapping_id];
			} else {
				$block_id = (int) $val['block_id'];
				$project_id = (int) $val['project_id'];
				$project_name = ($block_id > 0) ? $arr_blocks[$block_id] : $arr_projects[$project_id];
			}
			$list_registers[$key]["project_name"] = $project_name;
			
			$staff_information = $arr_profile_cached[$staff_id]["more_information"];	
			$staff_name = $clsProfile->getFullName($staff_id, $arr_profile_cached[$staff_id]);
			$department_name = $core->get_field($staff_information, "department_name", "");
			
			$list_registers[$key]["staff_name"] = $staff_name;
			$list_registers[$key]["department_name"] = $department_name;
		}
	}
	$smarty->assign('list_registers', $list_registers);
	$smarty->assign('total_fb_ads', $total_fb_ads);
	$smarty->assign('total_gg_ads', $total_gg_ads);
	$smarty->assign('total_zalo_ads', $total_zalo_ads);
	$smarty->assign('total_tiktok_ads', $total_tiktok_ads);
	// Return
	$html = $core->build('_ajax.regis.tpl');
	echo json_encode(array(
		'html' => $html
	));
}
function default_open_regis(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $oneProfile,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$uid = $clsISO->getUniqid();
	$current_year = date('Y');
	$current_month = date('m');
	$department_id = (int) $oneProfile['department_id'];
	$more_information = $oneProfile['more_information'];
	$arr_chanels = array(
		'fb_ads' => 'FB ADS',
		'gg_ads' => 'GG ADS',
		'zalo_ads' => 'Zalo ADS',
		'tiktok_ads' => 'Tiktok ADS',
	);
	#- Projects
	$field = "{$clsSetting->pkey},`title`";
	$arr_projects = $clsSetting->getAll("`is_trash`=0 AND `_type`='_PROJECT' ORDER BY `order_no` ASC", $field);
	#
	$month = sprintf('%s-%s', $current_year, $current_month);
	$regis_deadline = strtotime(sprintf('08-%s-%s 17:59', $current_month, $current_year));
	$is_edit_content = ($regis_deadline > time()) ? 1 : 0;
	$smarty->assign('is_edit_content', $is_edit_content);
	
	$action = "_add"; $total_records = 0;
	$list_registers = $clsMarketingBudgetRegister->getAll("`month`='{$month}' AND `staff_id`='{$profile_id}'");
	if(!empty($list_registers)){
		$action = "_edit";
		$total_records = count($list_registers);
		foreach($list_registers as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_registers[$key]['more_information'] = $more_information;
		}
	}
	$arr_rows = array();
	$total_blanks = 5 - $total_records;
	if($total_blanks > 0){
		for($i=1; $i<=$total_blanks; $i++){
			$arr_rows[] = ($total_records+$i);
		}
	}
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('month', $month);
	$smarty->assign('action', $action);
	$smarty->assign('arr_rows', $arr_rows);
	$smarty->assign('arr_chanels', $arr_chanels);
	$smarty->assign('arr_projects', $arr_projects);
	$smarty->assign('total_records', $total_records);
	$smarty->assign('regis_deadline', $regis_deadline);
	$smarty->assign('list_registers', $list_registers);
	// Return
	$html = $core->build('_ajax.open_regis.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_add_line(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $oneProfile,$profile_id,$deviceType;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$uid = $clsISO->getUniqid();
	$_total_rows = (int) Input::post('_total_rows', 0);
	$arr_chanels = array(
		'fb_ads' => 'FB ADS',
		'gg_ads' => 'GG ADS',
		'zalo_ads' => 'Zalo ADS',
		'tiktok_ads' => 'Tiktok ADS',
	);
	$html_options = "";
	$field = "{$clsSetting->pkey},`title`";
	$tmp = $clsSetting->getAll("`is_trash`=0 AND `_type`='_PROJECT' ORDER BY `order_no` ASC", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$html_options.= sprintf('<option value="%s">%s</option>', $val[$clsSetting->pkey], $val['title']);
		}
		unset($tmp);
	}
	$html = '<tr class="js__tr_marketing nohover">
		'.($deviceType!='phone' ? '<td class="text-center">'.($_total_rows+1).'</td>' : '').'
		<td class="align-center text-left">
			<select name="tblData['.$uid.'][project_mapping_id]" class="form-control form-select js__select-project min-w-px-175" 
				onchange="$Core.marketing.check(this, event)">
				<option value="0">Lựa chọn dự án</option>
				'.$html_options.'
			</select>
		</td>';
		foreach($arr_chanels as $key => $val){
			$html.= '<td class="align-center js__td-budget text-left">
				<input placeholder="'.$clsISO->getRate().'" name="tblData['.$uid.'][budgets]['.$key.']" 
					class="form-control price-In min-w-px-100 js__input-budget" />
			</td>';
		}
		$html.= '<td class="align-center text-center">
			<button type="button" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.marketing.delete_line(this, event)">
				<i class="bx bx-trash"></i>
			</button>
		</td>
	</tr>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_regis(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;
	global $oneProfile,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$msg = "_error";
	$current_month = date('m');
	$current_year = date('Y');
	$month = sprintf('%s-%s', $current_year, $current_month);
	$department_id = (int) $oneProfile['department_id'];
	// $more_information = $oneProfile['more_information'];
	if(isset($_POST['submit']) && $_POST['submit'] == 'register'){
		$arr_found = array();
		$tblData = Input::post('tblData');
		$month = Input::post('month', $month);
		foreach($tblData as $key => $val){
			$budgets = $val['budgets'];
			$project_mapping_id = (int) $val['project_mapping_id'];
			if($project_mapping_id > 0 && !$clsMarketingBudgetRegister->isEmpty($budgets)){
				$more_information = $clsSetting->getOneField("more_information", $project_mapping_id);
				$more_information = $clsISO->to_array_json($more_information);
				$project_id = (int) $core->get_field($more_information, "project_id", 0);
				$block_id = (int) $core->get_field($more_information, "block_id", 0);
				$fb_ads = $core->get_field($budgets, "fb_ads", 0);
				$gg_ads = $core->get_field($budgets, "gg_ads", 0);
				$zalo_ads = $core->get_field($budgets, "zalo_ads", 0);
				$tiktok_ads = $core->get_field($budgets, "tiktok_ads", 0);
				$tmp = $clsMarketingBudgetRegister->getByCond("`month`='{$month}' AND `staff_id`='{$profile_id}' 
					AND `project_id`='{$project_id}' AND `block_id='{$block_id}'`");
				if(!empty($tmp)){
					$more_information = $tmp['more_information'];
					// $more_information = $clsISO->to_array_json($more_information);
					if($clsMarketingBudgetRegister->insert(array(
						'fb_ads' => $clsISO->processSmartNumber($fb_ads),
						'gg_ads' => $clsISO->processSmartNumber($gg_ads),
						'zalo_ads' => $clsISO->processSmartNumber($zalo_ads),
						'tiktok_ads' => $clsISO->processSmartNumber($tiktok_ads),
						'status' => 'pending',
						'upd_date' => time()
					))){
						$msg = "_success";
						$arr_found[] = $tmp[$clsMarketingBudgetRegister->pkey];
					}
				} else {
					$regis_id = $clsMarketingBudgetRegister->getMaxId();
					$more_information = array('project_mapping_id' => $project_mapping_id);
					if($clsMarketingBudgetRegister->insert(array(
						$clsMarketingBudgetRegister->pkey => $regis_id,
						'month' => $month,
						'staff_id' => $profile_id,
						'department_id' => $department_id,
						'project_id' => $project_id,
						'block_id' => $block_id,
						'fb_ads' => $clsISO->processSmartNumber($fb_ads),
						'gg_ads' => $clsISO->processSmartNumber($gg_ads),
						'zalo_ads' => $clsISO->processSmartNumber($zalo_ads),
						'tiktok_ads' => $clsISO->processSmartNumber($tiktok_ads),
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'status' => 'pending',
						'reg_date' => time(),
						'upd_date' => time()
					))){
						$msg = "_success";
						$arr_found[] = $regis_id;
					}
				}
			}
		}
		// Xóa bỏ những hàng không tồn tại
		if(!empty($arr_found)){
			$clsMarketingBudgetRegister->deleteByCond("`month`='{$month}' AND `staff_id`='{$profile_id}' 
				AND `{$clsMarketingBudgetRegister->pkey}` NOT IN ('".implode('\',\'', $arr_found)."')");
		} else {
			$clsMarketingBudgetRegister->deleteByCond("`month`='{$month}' AND `staff_id`='{$profile_id}'");
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'month' => $month		
	)); die();
}
function parseAIJson(string $raw) {
    $raw = trim($raw);
    // Xoá markdown nếu có
    $raw = preg_replace('/^```json|^```|```$/m', '', $raw);
    $data = json_decode($raw, true);
	 return $data;
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON AI không hợp lệ');
    }
    return $data;
}
function insightStyle($type) {
    $map = [
        'summary'      => ['icon' => '✅', 'color' => 'green'],
        'warning'      => ['icon' => '⚠️', 'color' => 'orange'],
        'danger'       => ['icon' => '🚨', 'color' => 'red'],
        'risk_channel' => ['icon' => '🎯', 'color' => 'blue'],
        'forecast'     => ['icon' => '📈', 'color' => 'purple'],
    ];
    return $map[$type] ?? ['icon' => 'ℹ️', 'color' => 'gray'];
}
function renderInsightBox($insights){
    $html = '';
    foreach ($insights as $item) {
        $style = insightStyle($item['type']);
        $html .= '<div class="insight-row '.$style['color'].'">';
        $html .= '<div class="icon">'.$style['icon'].'</div>';
        $html .= '<div class="content">';
        $html .= '<strong>'.$item['title'].'</strong>';
        if (!empty($item['items'])) {
            $html .= '<ul>';
            foreach ($item['items'] as $sub) {
                $html .= '<li>';
                $html .= $sub['label'];
                if (isset($sub['value'])) {
                    $html .= ': <b>'.$sub['value'].'</b>';
                }
                if (isset($sub['percent'])) {
                    $html .= ' <span class="percent">@ '.$sub['percent'].'%</span>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        $html .= '<small>'.$item['note'].'</small>';
        $html .= '</div></div>';
    }
    return $html.'</div>';
}
function default_load_summary(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsMarketingSpending = new MarketingSpending();
	$clsMarketingBudgetRegister = new MarketingBudgetRegister();
	#
	$msg = "_error";
	$date_type = Input::post('date_type', '_month');
	$month = Input::post('month', date('Y-m'));
	$quarter = (int) Input::post('quarter');
	$year = (int) Input::post('year', date('Y'));
	$marketing_json_cached = array();
	if($clsCache->has('marketing_json_cached')){
		$marketing_json_cached = $clsCache->get('marketing_json_cached');
		$clsCache->delete('marketing_json_cached');
	}
	if($date_type == '_month'){
		$marketing_json_cached['month'] = $month;
		$dateText = sprintf('Tháng %s', $month);
		$cache_name = sprintf('_marketing_T%s_cached', $month);
	} if($date_type == '_quarter'){
		$marketing_json_cached['quater'] = $quater;
		$dateText = sprintf('Quý %s', $quater);
		$cache_name = sprintf('_marketing_Q%s_cached', $month);
	} if($date_type == '_year'){
		$marketing_json_cached['year'] = $year;
		$dateText = sprintf('Năm %s', $year);
		$cache_name = sprintf('_marketing_Y%s_cached', $month);
	}
	// Prompt 
	$promptText = 'Bạn là Giám đốc Marketing & Tài chính.

Nhiệm vụ:
Phân tích dữ liệu marketing '.$dateText.' và tạo báo cáo tổng quan cho Ban Lãnh Đạo.
Yêu cầu báo cáo phải:
- Nhìn 1 lần là hiểu toàn bộ tình hình marketing trong '.$dateText.'
- Chỉ ra rõ: tiền, lệch, rủi ro, dự báo
- Không viết lan man, không văn mẫu

=== QUY TẮC PHÂN TÍCH ===

1. Tổng quan ngân sách
- Nêu rõ: Tổng ngân sách, Tổng chi, % chi, Công ty hỗ trợ, Sale phải chịu
- Đánh giá mức độ kiểm soát chi tiêu

2. Phân tích kênh
- Xác định kênh chi nhiều nhất, ít nhất
- Nếu 1 kênh > 70% ngân sách → cảnh báo rủi ro phụ thuộc

3. Phân tích dự án
- Liệt kê 2 hoặc 3 dự án chi ngân sách nhiều nhất, ít nhất
- Nếu 1 dự án > 70% ngân sách → cảnh báo rủi ro phụ thuộc

4. Phân tích vùng kinh doanh
- Chỉ ra:
  - Vùng chi nhiều nhất
  - Vùng chi nhiều nhì
  - Vùng chi thấp nhất
- Nếu vùng < 25% → giải ngân chậm
- Nếu vùng > 60% → cảnh báo tốc độ chi cao

5. Cảnh báo & rủi ro
- Vượt ngân sách
- Lệch kênh
- Lệch vùng

6. Dự báo
- Dự báo tổng chi cuối tháng
- Đánh giá: An toàn / Nguy cơ vượt / Chắc chắn vượt

=== ĐỊNH DẠNG KẾT QUẢ (BẮT BUỘC) ===

Chỉ trả về JSON, KHÔNG markdown, KHÔNG ```json

Schema:
[
  {
    "type": "summary | warning | danger | risk_channel | forecast",
    "title": "Tiêu đề ngắn gọn cho lãnh đạo",
    "items": [
      { "label": "Tên đối tượng", "value": "Giá trị", "percent": 0 }
    ],
    "note": "Nhận định + khuyến nghị hành động"
  }
]

Quy tắc:
- Tối đa 6 insight
- Mỗi insight phải có GIÁ TRỊ CỤ THỂ (tiền / %)
- Không dùng từ chung chung
- Không lặp ý

=== DỮ LIỆU ĐẦU VÀO ===
'.json_encode($marketing_json_cached);
	if($clsCache->has($cache_name)){
		$html = $clsCache->get($cache_name);
		// $clsCache->delete($cache_name);
	} else {
		$payload = [
		  "model" => "gpt-4o",
		  "messages" => [[
			  "role" => "user",
			  "content" => $promptText
			]],
		  "temperature" => 0.5
		];
		$html = "";
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer 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'
		));
		$curl->post('https://api.openai.com/v1/chat/completions', $payload);
		if(!$curl->error){
			$response = toArray($curl->response);
			$raw = $response['choices'][0]['message']['content'];
			$insights = parseAIJson($raw);
			$html.= '<style type="text/css">
				.insight-box {
				  background: var(--bs-white);
				}
				.insight-row {
					display: flex;
					gap: 10px;
					padding: 10px 0;
					border-bottom: 1px solid #eee;
				}
				.insight-row > .content ul{
					padding-left: 20px;
					margin-bottom:10px;
				}
				.insight-row:last-child {
				  border-bottom: none;
				}
				.icon {
				  font-size: 22px;
				}
				.red { color: #e53935; }
				.orange { color: #fb8c00; }
				.green { color: #43a047; }
				.blue { color: #1e88e5; }
				.percent {
				  font-weight: 600;
				}
			</style>';
			$html.= renderInsightBox($insights);
		}
		$clsCache->put($cache_name, $html, 30*60);
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
?>