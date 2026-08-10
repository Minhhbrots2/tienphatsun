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
function cash_book_default(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	if(!$clsISO->checkPermission('cash_book_access')){
		header('Location:/#not-permiss');
		exit();
	}
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$group_company_arrs = $clsSetting->getCacheItems('_GROUP_COMPANY');
	#
	$smarty->assign('list_preloaders', $list_preloaders);
	$smarty->assign('group_company_arrs', $group_company_arrs);
	/*=============Title & Description Page==================*/
	$title_page = 'Sổ quỹ tiền mặt - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function cash_book_list(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$profile_id,$clsConfiguration;
	$clsCashBook = new CashBook();
	#
	$type = Input::post('type', "_all");
	$cash_date = Input::post('cash_date');
	$company_id = (int) Input::post('company_id', _GROUP_COMPANY_FH_ID);
	#- By Date
	$tmp = explode('-', $cash_date);
	$start_date = $clsISO->convertTextToTime($tmp[0]);
	$due_date = $clsISO->convertTextToTime($tmp[1], "23:59:59");
	$cond = "`company_id`='{$company_id}' AND `date` BETWEEN {$start_date} AND {$due_date}";
	if($type != "_all") {
		$cond.= " AND `type`='{$type}'";
	}
	$total_opening_balance = $total_receipt_amount = $total_payment_amount = $total_closing_balance = 0;
	$query = "SELECT
		SUM(CASE WHEN `company_id`='{$company_id}' AND `date` < '{$start_date}' AND `type`='THUCTHU' THEN amount
			WHEN `company_id`='{$company_id}' AND `date` < '{$start_date}' AND `type`='THUCCHI' THEN -amount
			ELSE 0 END) AS total_opening_balance,
		SUM(CASE WHEN `company_id`='{$company_id}' AND `date` <= '{$due_date}' AND `type`='THUCTHU' THEN amount
			WHEN `company_id`='{$company_id}' AND `date` <= '{$due_date}' AND `type`='THUCCHI' THEN -amount
			ELSE 0 END) AS `total_closing_balance`
		FROM {$clsCashBook->tbl}";
	$tmp = $dbconn->GetRow($query);
	if(!empty($tmp)){
		$total_opening_balance = $tmp['total_opening_balance'];
		$total_closing_balance = $tmp['total_closing_balance'];
	}
	$cash_book_configs = $clsConfiguration->getValue('cash_book_configs');
	$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
	$one_configs = $core->get_field($cash_book_configs, $company_id, []);
	$OPENING_BALANCE_DEF = $core->get_money_field($one_configs, "opening_balance", 0);
	// $clsISO->print_pre($OPENING_BALANCE_DEF); die();
	$total_opening_balance += $OPENING_BALANCE_DEF;
	$total_closing_balance += $OPENING_BALANCE_DEF;
	$html = ''; 
	// $dbconn->debug = true;
	$list = $clsCashBook->getAll("{$cond} ORDER BY `date` DESC");
	if(!empty($list)){ $ii= 1;
		foreach($list as $key => $val){
			$type = $val['type'];
			$amount = $clsISO->processSmartNumber($val['amount']);			
			if($type == 'THUCTHU'){
				$total_receipt_amount += $amount;
				$html_type = '<span class="badge bg-label-danger w-100">Khoản thu</span>';
			} else {
				$total_payment_amount += $amount;
				$html_type = '<span class="badge bg-label-primary w-100">Khoản chi</span>';
			}
			$html.= '<tr>
				<td class="align-center text-center">'.($ii).'</td>
				<td class="align-center text-right text-nowrap">'.$clsISO->convertTimeToText($val['date']).'</td>
				<td class="align-center text-left text-nowrap">'.$html_type.'</td>
				<td class="align-center text-right text-nowrap">'.$clsISO->formatPrice($amount)." ".$clsISO->getRate().'</td>
				<td class="align-center text-left">'.$val['notes'].'</td>
			</tr>';
			++$ii;
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_opening_balance' => $clsISO->formatPrice($total_opening_balance),
		'total_receipt_amount' => $clsISO->formatPrice($total_receipt_amount),
		'total_payment_amount' => $clsISO->formatPrice($total_payment_amount),
		'total_closing_balance' => $clsISO->formatPrice($total_closing_balance)
	)); die();
}
function cash_book_get_worksheets(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	#
	$msg = "_error";
	$spreadsheetId = Input::post('spreadsheetId');
	$html_options = '<option value="">Chọn bảng tính</option>';
	if(!empty($spreadsheetId)){
		$arr_worksheets = array();
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
			$arr_worksheets = $spreadsheet->sheets;
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
					'name' => sprintf('[Bản sao]%s', date('d-m-y h:i:s'))
				));
				$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
				$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
				$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
					'supportsAllDrives' => 'true'
				));
				$spreadsheetIdCopy = $convertedFile->getId();
				// $clsISO->print_pre($spreadsheetCopy); die();
				$spreadsheetCopy = $service->spreadsheets->get($spreadsheetIdCopy);
				$arr_worksheets = $spreadsheetCopy->sheets;
				// Xoá file sau khi lấy dữ liệu xong
				$fileMetadataTrash = new \Google_Service_Drive_DriveFile();
				$fileMetadataTrash->setTrashed(true);
				$drive->files->update($spreadsheetIdCopy, $fileMetadataTrash, array(
					'supportsAllDrives' => true,
					'supportsTeamDrives' => true,
				));
			}				
		}
		if(!empty($arr_worksheets)){
			foreach($arr_worksheets as $sheet){
				// $sheetId = $sheet->properties['sheetId'];   
				$sheetName = $sheet->getProperties()->getTitle(); 
				$html_options.= sprintf('<option value="%s">%s</option>', $sheetName, $sheetName);
			}
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html_options' => $html_options,
	)); die();
}
function cash_book_open_setting(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$profile_id,$clsConfiguration;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	#
	$group_company_arrs = $clsSetting->getCacheItems('_GROUP_COMPANY');
	$smarty->assign('group_company_arrs', $group_company_arrs);
	#
	$cash_book_configs = $clsConfiguration->getValue('cash_book_configs');
	$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
	$smarty->assign('cash_book_configs', $cash_book_configs);
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('cash_book'.DS. '_ajax_setting.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function cash_book_save_setting(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id;
	global $clsConfiguration;
	#
	$msg = "_error";
	$db_cash_book_configs = $clsConfiguration->getValue('cash_book_configs');
	$db_cash_book_configs = $clsISO->to_array_json($db_cash_book_configs);
	$cash_book_configs = Input::post('cash_book_configs', []);
	if(!empty($cash_book_configs)){
		foreach($cash_book_configs as $key => $val){
			foreach($val as $okey => $oval){
				$db_cash_book_configs[$key][$okey] = $oval;
			}
		}
	}
	if($clsConfiguration->updateValue("cash_book_configs", json_encode($db_cash_book_configs, JSON_UNESCAPED_UNICODE))){
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function cash_book_open_import(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$profile_id,$clsConfiguration;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	#
	$group_company_arrs = $clsSetting->getCacheItems('_GROUP_COMPANY');
	$cash_book_configs = $clsConfiguration->getValue("cash_book_configs");
	$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
	if(!empty($group_company_arrs)){
		foreach($group_company_arrs as $key => $val){
			$setting_id = $val[$clsSetting->pkey];
			$one_configs = $core->get_field($cash_book_configs, $setting_id, []);
			$group_company_arrs[$key]['spreadsheetId'] = $core->get_field($one_configs, 'spreadsheetId', "");
			$group_company_arrs[$key]['sheet_name'] = $core->get_field($one_configs, 'sheet_name', "");
		}
	}
	$one_configs = $core->get_field($cash_book_configs, _GROUP_COMPANY_FH_ID, []);
	$smarty->assign('one_configs', $one_configs);
	$smarty->assign('group_company_arrs', $group_company_arrs);
	// $clsISO->print_pre($group_company_arrs); die();
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('cash_book'.DS. '_ajax.open_import.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function cash_book_open_config(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsCashBook = new CashBook();
	
	$gId = Input::post('gId');
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG', 'file.upload');
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		if($holderG == 'google.sheet'){
			$spreadsheetId = Input::post('spreadsheetId');
			$sheet_name = Input::post('sheet_name');
			if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
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
						'name' => sprintf('[Bản sao]%s', date('d-m-y h:i:s'))
					));
					$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
					$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
					$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
						'supportsAllDrives' => 'true'
					));
					$spreadsheetIdCopy = $convertedFile->getId();
					// $clsISO->print_pre($spreadsheetCopy); die();
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
			$cash_book_configs = $clsConfiguration->getValue("cash_book_configs");
			$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
			$select_default = $core->get_field($cash_book_configs, "columns", []);
			// Remove file uploaded
			$highestColumnIndex = 15;
			$widthColumn = 100/$highestColumnIndex;	
			$data_select = $clsCashBook->getTableField();
			$smarty->assign("data_select",$data_select);
			$smarty->assign("select_default",$select_default);
			$smarty->assign("widthColumn",$widthColumn);
			$smarty->assign("highestColumnIndex",$highestColumnIndex);
			$smarty->assign("tblData", $tblData);
		} else {
			$fileImport = $_FILES['fileImport'];
			if(@is_uploaded_file($fileImport['tmp_name'])){
				$target_dir = PCMS_DIR."/tmp/";
				$file_ext = explode('.', basename($fileImport["name"]));
				$file_ext = strtolower(end($file_ext));
				$target_file = $target_dir . time().'.'.$file_ext;
				if (@move_uploaded_file($fileImport["tmp_name"], $target_file)) {
					$inputFileName = $target_file;
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel/IOFactory.php";
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						die($e->getMessage());
					}
					$worksheet 			= $objPHPExcel->getActiveSheet();
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow();
					$highestColumn      = $worksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					##
					$index = 0; $tblData = $select_default = array();
					for($row=1; $row <= $highestRow; ++ $row){
						for($col=0; $col < $highestColumnIndex; ++$col){
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$value = $cell->getValue(); 
							// Nếu là date
							if (PHPExcel_Shared_Date::isDateTime($cell)) {
								$value = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($value));
							}
							$tblData[$index][] = $value;
						}
						++$index;
					}
					$cash_book_configs = $clsConfiguration->getValue("cash_book_configs");
					$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
					$select_default = $core->get_field($cash_book_configs, "columns", []);
					// Remove file uploaded
					@unlink($inputFileName);
					$highestColumnIndex = 15;
					$widthColumn = 100/$highestColumnIndex;	
					$data_select = $clsCashBook->getTableField();
					$smarty->assign("data_select",$data_select);
					$smarty->assign("select_default",$select_default);
					$smarty->assign("widthColumn",$widthColumn);
					$smarty->assign("highestColumnIndex",$highestColumnIndex);
					$smarty->assign("tblData", $tblData);
				}
			}
		}
	}
	// Return
	$smarty->assign("tp", $tp);
	$smarty->assign("uid", $uid);
	$smarty->assign("gId", $gId);
	$html = $core->build($sub .DS. '_ajax.open_config.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function cash_book_save_config(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	#
	$gId = Input::post("gId");
	$columns = Input::post("columns", array());
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
			$cash_book_configs = $clsConfiguration->getValue('cash_book_configs');
			$cash_book_configs = $clsISO->to_array_json($cash_book_configs);
			$cash_book_configs['columns'] = $arr_fields;
			if($clsConfiguration->updateValue("cash_book_configs", json_encode($cash_book_configs, JSON_UNESCAPED_UNICODE))){
				$res = array(
					'result' =>	'success',
					'msg'	 =>	"Thành công"
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
function cash_book_do_import(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsISO,$profile_id,$oneProfile,$clsConfiguration;
	$clsCashBook = new CashBook();
	
	$msg = "_error";
	$current_year = date('Y');
	$company_id = (int) Input::post('company_id', _GROUP_COMPANY_FH_ID);
	// $clsISO->print_pre($company_id); die();
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$fileimport = $_FILES['fileimport'];
		if(!empty($fileimport)){
			if(@is_uploaded_file($fileimport['tmp_name'])){
				$target_dir = PCMS_DIR."/tmp/";
				$file_ext = explode('.', basename($fileimport["name"]));
				$file_ext = strtolower(end($file_ext));
				$target_file = $target_dir . time().'.'.$file_ext;
				if (@move_uploaded_file($fileimport["tmp_name"], $target_file)) {
					$html = '';
					$inputFileName = $target_file;
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel/IOFactory.php";
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						die($e->getMessage());
					}
					$worksheet 			= $objPHPExcel->getActiveSheet();
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow();
					$highestColumn      = $worksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					##
					$index = 0; $tblData = $select_default = array();
					for($row=1; $row <= $highestRow; ++ $row){
						for($col=0; $col < $highestColumnIndex; ++$col){
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$value = $cell->getValue(); 
							// Nếu là date
							if (PHPExcel_Shared_Date::isDateTime($cell)) {
								$value = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($value));
							}
							$tblData[$index][] = $value;
						}
						++$index;
					}
					// Remove file uploaded
					@unlink($inputFileName);
					if(!empty($tblData)){
						$msg = "_success";
						$arr_data = array();
						$totalRecord = count($tblData);
						$more_information = $oneProfile['more_information'];
						$columns = $core->get_field($more_information, "cash_book_config_columns", []);
						for($i=4; $i<$totalRecord; $i++){
							$row = array();
							foreach($columns as $i_col => $p_field){
								$row[$p_field] = trim($tblData[$i][$i_col]);
							}
							$arr_data[] = $row;
						}
						if(!empty($arr_data)){
							$current_date = "";
							// Xóa các hàng cũ và chèn hàng mới
							$clsCashBook->deleteByCond("`year`='{$current_year}' AND `company_id`='{$company_id}'");
							foreach($arr_data as $key => $val){
								$date = trim($val['date']);
								if(!empty($date)){
									$current_date = $date;
								} else {
									$date = $current_date;
								}
								$receipt_amount = trim($val['receipt_amount']);
								$payment_amount = trim($val['payment_amount']);
								$receipt_notes = trim($val['receipt_notes']);
								$payment_notes = trim($val['payment_notes']);
								if(!empty($date) && (!empty($receipt_amount) || !empty($payment_amount))){
									if(!empty($receipt_amount)){
										$clsCashBook->insert(array(
											$clsCashBook->pkey => $clsCashBook->getMaxId(),
											'type' => 'THUCTHU',
											'year' => $current_year,
											'company_id' => $company_id,
											'date' => $clsISO->convertTimeToText($date),
											'amount' => $clsISO->processSmartNumber($receipt_amount),
											'content' => $receipt_notes,
											'user_id' => $profile_id,
											'reg_date' => time(),
											'user_id_update' => $profile_id,
											'upd_date' => time()
										));
									}
									if(!empty($payment_amount)){
										$clsCashBook->insert(array(
											$clsCashBook->pkey => $clsCashBook->getMaxId(),
											'type' => 'THUCCHI',
											'year' => $current_year,
											'company_id' => $company_id,
											'date' => $clsISO->convertTimeToText($date),
											'amount' => $clsISO->processSmartNumber($payment_amount),
											'content' => $payment_notes,
											'user_id' => $profile_id,
											'reg_date' => time(),
											'user_id_update' => $profile_id,
											'upd_date' => time()
										));
									}
								}
							}
						}
					}
				}
			}
		} else {
			$spreadsheetId = Input::post('spreadsheetId');
			$sheet_name = Input::post('sheet_name');
			if(!empty($spreadsheetId)){
				if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
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
							'name' => sprintf('[Bản sao]%s', date('d-m-y h:i:s'))
						));
						$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
						$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
						$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
							'supportsAllDrives' => 'true'
						));
						$spreadsheetIdCopy = $convertedFile->getId();
						// $clsISO->print_pre($spreadsheetCopy); die();
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
				if(!empty($tblData)){
					$msg = "_success";
					$arr_data = array();
					$totalRecord = count($tblData);
					$more_information = $oneProfile['more_information'];
					$columns = $core->get_field($more_information, "cash_book_config_columns", []);
					for($i=4; $i<$totalRecord; $i++){
						$row = array();
						foreach($columns as $i_col => $p_field){
							$row[$p_field] = trim($tblData[$i][$i_col]);
						}
						$arr_data[] = $row;
					}
					if(!empty($arr_data)){
						$current_date = "";
						// Xóa các hàng cũ và chèn hàng mới
						$clsCashBook->deleteByCond("`year`='{$current_year}' AND `company_id`='{$company_id}'");
						foreach($arr_data as $key => $val){
							$date = trim($val['date']);
							if(!empty($date)){
								$current_date = $date;
							} else {
								$date = $current_date;
							}
							$receipt_amount = trim($val['receipt_amount']);
							$payment_amount = trim($val['payment_amount']);
							$receipt_notes = trim($val['receipt_notes']);
							$payment_notes = trim($val['payment_notes']);
							if(!empty($date) && (!empty($receipt_amount) || !empty($payment_amount))){
								if(!empty($receipt_amount)){
									$clsCashBook->insert(array(
										$clsCashBook->pkey => $clsCashBook->getMaxId(),
										'type' => 'THUCTHU',
										'year'	=> $current_year,
										'company_id' => $company_id,
										'date' => $clsISO->convertTextToTime($date),
										'amount' => $clsISO->processSmartNumber($receipt_amount),
										'notes' => $receipt_notes,
										'user_id' => $profile_id,
										'reg_date' => time(),
										'user_id_update' => $profile_id,
										'upd_date' => time()
									));
								}
								if(!empty($payment_amount)){
									$clsCashBook->insert(array(
										$clsCashBook->pkey => $clsCashBook->getMaxId(),
										'type' => 'THUCCHI',
										'year' => $current_year,
										'company_id' => $company_id,
										'date' => $clsISO->convertTextToTime($date),
										'amount' => $clsISO->processSmartNumber($payment_amount),
										'notes' => $payment_notes,
										'user_id' => $profile_id,
										'reg_date' => time(),
										'user_id_update' => $profile_id,
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
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}