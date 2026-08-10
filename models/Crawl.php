<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class Crawl{
	function __construct(){
		#- Reuired Library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');				
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		$this->cachedFile = DIR_CACHE_JSON.'/crawl/crawl.json';
		#- Init Client
		$this->client = new Google_Client();
		$this->client->setClientId(GOOGLE_CLIENT_ID);
		$this->client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$this->client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$this->client->setScopes([Google_Service_Drive::DRIVE]);
		$this->client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$this->encoder = new Webmozart\Json\JsonEncoder();
		$this->decoder = new Webmozart\Json\JsonDecoder();
		
		$this->drive = new Google_Service_Drive($this->client);
		
		if(!empty(IS_ADMIN_PAGE) && IS_ADMIN_PAGE == 1) {
			$this->_site = "_admin";
		}else{
			$this->_site = "_front";
		}
	}
	function getCodeNotTemplate($ms_code){
		$ms_code = preg_replace('/\s+/', '', $ms_code);
		$ms_code = trim(str_replace('.','', $ms_code));
		$ms_code = str_replace("-","",$ms_code);
		$ms_code = str_replace("_","",$ms_code);
		$ms_code = strtoupper($ms_code);
		return $ms_code;
	}
	function columnLetter($index) {
		$letter = '';
		while ($index >= 0) {
			$letter = chr($index % 26 + 65) . $letter;
			$index = floor($index / 26) - 1;
		}
		return $letter;
	}
	function getSpreadsheetData($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,$is_copy=0){
		global $core, $dbconn, $clsISO,$profile_id;
		$service = new Google_Service_Sheets($this->client);		
		$clsLogCrawl = new LogCrawl();
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE && $agency_id ==159 && ($target_id == 2 || $target_id == 3 || $target_id == 10)) {
//			var_dump($spreadsheetIdCopy);die;
			$spreadsheetIdCopy = $spreadsheetId;
		}else{
			$spreadsheetIdCopy = $this->copySpreadsheet($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type);	
		}
		if(!empty($spreadsheetIdCopy)) {
			try {
				$response = $service->spreadsheets->get($spreadsheetIdCopy, [
					'ranges' => $ranges,
					'includeGridData' => true
				]);
				return array(
					'spreadsheetId' => $spreadsheetIdCopy,
					"response"	=>	$response->getSheets(),
					"is_copy"	=>	1
				);
			} catch (Exception $e) {
				$msg_error = $e->getMessage();	
				if($profile_id == 289) {
					var_dump($msg_error);die;
				}
				if(json_decode($msg_error)->error->status == "INVALID_ARGUMENT"){
					$data = array(
						"title_log"	=>	'File Gooogle Sheet đã bị thay đổi',
						"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
				}else if(!empty($is_copy)) {
					$data = array(
						"title_log"	=>	'File Gooogle Sheet không thể đọc',
						"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"read_speadsheet",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
				}		
				return 0; 			
			}
		}
		
		return 0;
	}
	function copySpreadsheet($spreadsheetId, $ranges,$block_id, $agency_id,$stock_type,$is_update=0){	
		global $clsISO,$profile_id;
		$clsProperty = new Property();
		$arrCache = array();		
		$service = new Google_Service_Drive($this->client);
		if(@file_exists($this->cachedFile)){
			$arrCache = $this->decoder->decodeFile($this->cachedFile);
			if(isset($arrCache[$spreadsheetId])) {
				$cache = $arrCache[$spreadsheetId];
				if($cache['reg_date'] > (time() - 1800)) {
					return $cache['spreadsheetId'];
				}else{	
					// Xóa file sao chép khi đã hết thời gian cache
					$this->deleteSpreadsheetCopy($spreadsheetId,$cache['spreadsheetId']);
				}
			}
		}
		try {
			// Đọc file Excel từ Google Drive
			$response = $service->files->get($spreadsheetId, array(
				'supportsAllDrives' => true
			));
			// Chuyển đổi file Excel thành Google Sheets
			/*$fileMetadata = new \Google_Service_Drive_DriveFile(array(
				'name' => sprintf('%s %s',$agency_id , date('d-m-y h:i:s'))
			));
			$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
			$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');*/
			if($is_update == 1) {
				$fileMetadata = new Google_Service_Drive_DriveFile([
					'name' => sprintf('%s %s', $agency_id, date('d-m-y H:i:s')),
					'mimeType' => 'application/vnd.google-apps.spreadsheet',
					'parents' => [GOOGLE_DRIVE_PTG_COPY_ID]
				]);
				$convertedFile = $service->files->copy($spreadsheetId, $fileMetadata, array(
					'supportsAllDrives' => true
				));
				$spreadsheetId_new = $convertedFile->getId();
			}else{
				$fileMetadata = new Google_Service_Drive_DriveFile([
					'name' => sprintf('%s %s', $agency_id, date('d-m-y H:i:s')),
					'mimeType' => 'application/vnd.google-apps.spreadsheet',
	//				'parents' => [GOOGLE_DRIVE_PTG_COPY_ID]
				]);
				$convertedFile = $service->files->copy($spreadsheetId, $fileMetadata, array(
					'supportsAllDrives' => true
				));
				$spreadsheetId_new = $convertedFile->getId();
				$service->files->update(
					$spreadsheetId_new,
					new Google_Service_Drive_DriveFile(),
					[
						'addParents' => GOOGLE_DRIVE_PTG_COPY_ID,
						'supportsAllDrives' => true
					]
				);
			}
			
			$arrCache[$spreadsheetId] = [
				'spreadsheetId' => $spreadsheetId_new,
				'reg_date'		=>	time()
			];
			$this->encoder->encodeFile($arrCache, $this->cachedFile);
			return $spreadsheetId_new;
		} catch (Exception $e) {
			$msg_error = $e->getMessage();
//			$clsISO->print_pre($msg_error);die;
			if(json_decode($msg_error)->error->errors[0]->reason == "insufficientFilePermissions"){
				++$is_update;
				return $this->copySpreadsheet($spreadsheetId, $ranges,$block_id, $agency_id,$stock_type,$is_update);
			}
			
			if(json_decode($msg_error)->error->errors[0]->reason == "cannotCopyFile"){
				return $spreadsheetId;
			}
			$clsLogCrawl = new LogCrawl();
			$data = array(
				"title_log"	=>	'Không thể tạo bản sao',
				"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
				"result_type"	=>	"copy_speadsheet",	//change_field,copy_speadsheet,read_speadsheet
			);
			$clsLogCrawl->log($agency_id,$block_id, $data, $stock_type);
			return 0;
		}
	}
	function getSheet($spreadsheetId){
		global $clsISO;
		$service = new Google_Service_Sheets($this->client);	
		$list_worksheets = [];
		try {
			$resource_id = $spreadsheetId;
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$list_worksheets = $spreadsheet->sheets;
		} catch(Exception $ex){
			$msg_error = $ex->getMessage();
			if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
				$spreadsheetIdCopy = $this->copySpreadsheet($spreadsheetId, [],0, 0,0);
				$resource_id = $spreadsheetIdCopy;
				$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
				$list_worksheets = $spreadsheet->sheets;
			}				
		}
		return $list_worksheets;
	}
	function deleteSpreadsheetCopy($spreadsheetId,$spreadsheetId_copy) {
		if(@file_exists($this->cachedFile)){
			$arrCache = $this->decoder->decodeFile($this->cachedFile);
			if(isset($arrCache[$spreadsheetId])) {
				$cache = $arrCache[$spreadsheetId];
				// Xóa file sao chép khi đã hết thời gian cache
				try {
					$fileMetadataTrash = new Google_Service_Drive_DriveFile();
					$fileMetadataTrash->setTrashed(true);
					$this->drive->files->update($spreadsheetId_copy, $fileMetadataTrash, array(
						'supportsAllDrives' => true,
						'supportsTeamDrives' => true,
					));
				} catch (Exception $e) {
					return 0;
				}
				/*try {
					$this->drive->files->delete($spreadsheetId_copy);
					unset($arrCache[$spreadsheetId]);
					$this->encoder->encodeFile($arrCache, $this->cachedFile);
				} catch (Exception $e) {
					return 0;
				}*/
			}
		}
		return 1;
	}
	function getDataNewV1($spreadsheetId, $ranges, $target_id, $agency_id,$stock_type) {
		global $core, $dbconn, $clsISO,$profile_id;
		$clsStock = new StockCrawl();		
		$clsProperty = new Property();	
		$clsLogCrawl = new LogCrawl();
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';			
		}else{
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
		}
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_number_check = !empty($more_information["block_number_check"]) ? $more_information["block_number_check"] : array();
		$arr_number_check = $block_number_check[$target_id]["number_check"];
		$list_data_check = $block_number_check[$target_id]["list_data_check"];
		$arr_column_data = array();
		if(file_exists($cachedFile)){
			$arr_column_data = $this->decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();
//		return $column_data;
		
		$color_sold = !empty($value['color_sold']) ? $value['color_sold'] : array("#FF0000","#EA4335");
		if($agency_id == 284) $color_sold[] = "#A5A5A5"; //đông đô
		if($agency_id == 318) $color_sold[] = "#8E7CC3"; //Tân Long
		if($agency_id == 265) $color_sold[] = "#FFF2CC"; //Trường Phát
		if($agency_id == 239) $color_sold[] = "#EA4335"; //Đất Việt
		if($agency_id == 8801) $color_sold[] = "#CC0000"; //Elite Captital
		if($agency_id == 280 && $target_id == 10177) $color_sold[] = "#980000"; //EH-ATD
		
		$color_break = (!empty($value['is_color_break']) && !empty($value['color_break'])) ? $value['color_break'] : array("#20124D");
		if($agency_id == 162) $color_break[] = "#FF0000"; //VHS
		if($agency_id == 274 && $target_id == 9220) $color_break[] = "#FFE599"; //TPL
		
		$tblData = $tblHidden = array();
		$res = ["result" => false];
		$pattern = '/[\s]|[^a-zA-Z0-9]/u';
		if(!empty($column_data)) { 
			/*if($profile_id == 289) {
				$dataResponse = $this->getSpreadsheetDataSmartChip($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);
			}else{
				$dataResponse = $this->getSpreadsheetData($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);	
			}*/
			$dataResponse = $this->getSpreadsheetDataSmartChip($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);
			
			$spreadsheetId_crawl = $dataResponse["spreadsheetId"];
			$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
			if(!empty($response)) {
				foreach ($response as $sheet) {
					$title = $sheet->getProperties()->getTitle(); //tiêu đề sheet
					$arr_fields = !empty($column_data["'".$title."'"]) ? $column_data["'".$title."'"] : $column_data[$title];
					$number_check = !empty($arr_number_check["'".$title."'"]) ? $arr_number_check["'".$title."'"] : $arr_number_check[$title];
					$data_check = !empty($list_data_check["'".$title."'"]) ? $list_data_check["'".$title."'"] : $list_data_check[$title];
//					var_dump($arr_fields,$data_check,$title);die;
//					$number_check = 0; //test
					$data_sheet[$title] = [];
					foreach ($sheet->getData() as $data) {
						$rowData = $data->getRowData();
						$rowMetadata  = $data->getRowMetadata();
						foreach ($rowData as  $i => $row) {
							$is_sold = $is_general = $isHidden = $is_changed = 0;
							if (isset($rowMetadata[$i])) {
								$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
							}
							# Nếu hàng ẩn thì bỏ qua
							if (!$isHidden) {
								$data_row = array();
								foreach ($row->getValues() as $key => $cell) {	
									$value = $cell->getFormattedValue();
									
									#kiểm tra có sự thay đổi cột trong file nếu có thì dừng, báo lỗi
									if($i == $number_check && $data_check[$key] != $value && ($agency_id != 162 || ($agency_id == 162 && $key != 13)) && $arr_fields[$key] != "link_smartchip"){
										#nếu có file copy thì xóa
										if(!empty($dataResponse["is_copy"])) {
											$this->deleteSpreadsheetCopy($spreadsheetId,$dataResponse["spreadsheetId"]);
										}
//										echo 1;die;
										$is_changed = 1;
										#log
										$data_log = array(
											"title_log"	=>	"File Gooogle Sheet đã bị thay đổi ".$data_check[$key]."->".$value,
											"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
											"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
										);
										$clsLogCrawl->log($agency_id,$target_id, $data_log, $stock_type);
										break;
									}
									
									# Lấy backgroundColor của ô nếu có
									$bgColor = null;
									if ($cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
										$color = $cell->getEffectiveFormat()->getBackgroundColor();
										$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
										$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
										$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
										$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
										$bgColor = strtoupper($bgColor);
									}
									$data_row["bgColor"] = $bgColor;
									$cellColors[] = $bgColor;	
									if($clsISO->checkItemInArray("status_id",$arr_fields)) {
										if($arr_fields[$key] == "status_id" && $clsISO->replaceSpace($value) == "da-ban") {
											$is_sold = 1;
											$data_row["bgColor"] = $bgColor;
											break;
										}										
									}else {
										if(($arr_fields[$key] == "ms_code" || $arr_fields[$key] == "code_link") && in_array($bgColor,$color_sold)) {
											$is_sold = 1;
											$data_row["bgColor"] = $bgColor;
											break;
										}	
										if($agency_id == 318 && in_array($bgColor,$color_sold)){ //Tân Long
											$is_sold = 1;
											$data_row["bgColor"] = $bgColor;
											break;
										}
									}
									
									# end background
									
									if((($arr_fields[$key] == "ms_code" || $arr_fields[$key] == "code_link")  && in_array($bgColor, $color_break)) 
									   || ($agency_id == 265 && $key == 0 && in_array($bgColor,$color_break)  /*Trường Phát*/)
									   || ($agency_id == 274 && in_array($bgColor,$color_break)  /*TPL*/)
									  ) {
										$is_general = 1;
										break;
									}
									$hyperlink = null;
									if ($cell->getUserEnteredFormat() && $cell->getHyperlink()) {
										$hyperlink = $cell->getHyperlink();
										$hyperlink = ($hyperlink) ? $hyperlink : "";
									}
									
									if((!empty($arr_fields[$key]) && ($arr_fields[$key] == "code_link" || $arr_fields[$key] == "ms_code")) && !empty($value)) {
										$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($value)));
										$ms_code = str_replace("TC","",$ms_code);
										$ms_code = str_replace(" ","",$ms_code);
										$ms_code = trim($ms_code,"x");
										$data_row["ms_code"] = $ms_code;
										$data_row["price_sheet_link"] = $hyperlink;
										unset($ms_code);
									}else if(!empty($arr_fields[$key]) && (empty($color_dq) || (in_array($bgColor,$color_dq)))) {
										$data_row[$arr_fields[$key]] = ($arr_fields[$key] == "price_sheet_link") ? $hyperlink : $value;
									}
								}
								if(!empty($is_general) || !empty($is_changed)) {
									break;
								}
								# Lưu thông tin hàng	
//								$data_sheet[$title][] = $data_row;
								if(empty($is_sold) && !empty($data_row["ms_code"] && (($agency_id ==161 && $data_row['ms_code'] != "C4Z1.0405A") || $agency_id !=161)) 
									&& preg_match(REGEX_MS_CODE, $data_row["ms_code"]) && strlen($data_row["ms_code"]) < 20) {	
									$tblData[] = $data_row;									
//									$data_sheet[$title][] = $data_row;
								}
							}
						}
					}
				}
				if($profile_id == 289) {
//					echo $spreadsheetId_crawl;die;
//					var_dump($tblData);die;
				}
				if(!empty($is_changed)) {	
					$res = [
						"result"	=>	false,
						"type"		=>	"change_field"
					];
				}else{
					$res = [
						"result"	=>	true,
						"spreadsheetId_crawl"	=>	$spreadsheetId_crawl,
						"tblData"	=>	$tblData,
						"data_sheet"	=>	$data_sheet,
					];
				}
				
			}else{
				$res["result"] = false;
			}
		}
		return $res;
	}
	function getDataLowFloor_v1($spreadsheetId, $ranges, $target_id, $agency_id,$stock_type,$crawl_lowfloor = array()) {
		global $core, $dbconn, $clsISO,$profile_id;
		$clsStock = new StockCrawl();		
		$clsProperty = new Property();	
		$clsLogCrawl = new LogCrawl();
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';	
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$project_number_check = !empty($more_information["project_number_check"]) ? $more_information["project_number_check"] : array();
		$arr_number_check = $project_number_check[$target_id]["number_check"];
		$list_data_check = $project_number_check[$target_id]["list_data_check"];
//		var_dump($list_data_check);die;
		$arr_column_data = array();
		if(file_exists($cachedFile)){
			$arr_column_data = $this->decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();	
//		return $column_data;
		
		$tblData = $tblHidden = array();
		$res = ["result" => false];
		$pattern = '/[\s]|[^a-zA-Z0-9]/u';
		if(!empty($column_data)) { 
			$dataResponse = $this->getSpreadsheetData($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);
			
			if(!empty($dataResponse["is_copy"])) {
				$spreadsheetId_copy = $dataResponse["spreadsheetId"];
			}
			$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
			if(!empty($response)) {
				foreach ($response as $sheet) {
					$title = $sheet->getProperties()->getTitle(); //tiêu đề sheet
					$sheet_id = $sheet->getProperties()->getSheetId();	
					$crawl_lowfloor_sheet = !empty($crawl_lowfloor["'".$title."'"]) ? $crawl_lowfloor["'".$title."'"] : $crawl_lowfloor[$title];	
					$color_sold = !empty($crawl_lowfloor_sheet['color_sold']) ? $crawl_lowfloor_sheet['color_sold'] : array();
					$color_dq = (!empty($crawl_lowfloor_sheet['is_color_dq']) && !empty($crawl_lowfloor_sheet['color_dq'])) ? $crawl_lowfloor_sheet['color_dq'] : array();
					$color_break = (!empty($crawl_lowfloor_sheet['is_color_break']) && !empty($crawl_lowfloor_sheet['color_break'])) ? $crawl_lowfloor_sheet['color_break'] : array();
					$arr_fields = !empty($column_data["'".$title."'"]) ? $column_data["'".$title."'"] : $column_data[$title];	
					$number_check = !empty($arr_number_check["'".$title."'"]) ? $arr_number_check["'".$title."'"] : $arr_number_check[$title];	
					$data_check = !empty($list_data_check["'".$title."'"]) ? $list_data_check["'".$title."'"] : $list_data_check[$title];	
//					var_dump($arr_fields);die;
					
//					var_dump($sheet_id,$crawl_lowfloor[$sheet_id],$crawl_lowfloor);die;
//					$number_check = 10; //test
					$data_sheet[$title] = [];
					foreach ($sheet->getData() as $data) {
						$rowData = $data->getRowData();
						$rowMetadata  = $data->getRowMetadata();
						foreach ($rowData as  $i => $row) {
							$is_sold = $is_general = $isHidden = $is_changed = 0;
							if (isset($rowMetadata[$i])) {
								$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
							}
							if($i < $number_check ) {
								continue;
							}
							# Nếu hàng ẩn thì bỏ qua
							if (!$isHidden) {
								$data_row = $arr_code = array();
								$is_dq = 0;
								foreach ($row->getValues() as $key => $cell) {									
									$value = $cell->getFormattedValue();	
									
									$colMeta = $data->getColumnMetadata()[$key];
									if($colMeta->getHiddenByUser() || $colMeta->getHiddenByFilter()){
										continue;
									}
//									var_dump($i,$number_check,$data_check[$key]."====".$value);
									#kiểm tra có sự thay đổi cột trong file nếu có thì dừng, báo lỗi
									if($i == $number_check && $data_check[$key] != $value && $data_check[$key] != "link_smartchip"){
										#nếu có file copy thì xóa
										if(!empty($dataResponse["is_copy"])) {
											$this->deleteSpreadsheetCopy($spreadsheetId,$dataResponse["spreadsheetId"]);
										}
//										echo 1;die;
										$is_changed = 1;
										#log
										$data_log = array(
											"title_log"	=>	"File Gooogle Sheet đã bị thay đổi ".$data_check[$key]."->".$value,
											"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
											"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
										);
										if($profile_id == 289) {
											echo $spreadsheetId_copy;die;	
										}										
										$clsLogCrawl->log($agency_id,$target_id, $data_log, $stock_type);
										break;
									}
									
									# Lấy backgroundColor của ô nếu có
									$bgColor = $bgColorBreak = $bgColorDQ = null;
									if (($arr_fields[$key] == "ms_code" || $arr_fields[$key] == "code_link" || $arr_fields[$key] == "color_dq") && $cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
										$color = $cell->getEffectiveFormat()->getBackgroundColor();
										$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
										$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
										$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
										$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
										$bgColor = strtoupper($bgColor);
										$bgColorBreak = $bgColor;										
										if(($clsISO->checkItemInArray("color_dq", $arr_fields) && $arr_fields[$key] == "color_dq") || !$clsISO->checkItemInArray("color_dq", $arr_fields) ){
											$bgColorDQ = $bgColor;
										}
									}
									# end background
									
									$cellColors[] = $bgColor;
									if(($arr_fields[$key] == "ms_code" || $arr_fields[$key] == "code_link") && in_array($bgColor,$color_sold)) {
										$is_sold = 1;
										$data_row["bgColor"] = $bgColor;
										break;
									}
									
									if((($agency_id == 276 && $target_id == 3) || ($agency_id == 162 && $target_id == 9) || ($agency_id == 276 && $target_id == 3) /*QTC VHOP3*/ || ($agency_id == 240 && $target_id == 3) /*NSL VHOP3*/) && !empty($color_break) && $cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
										$color = $cell->getEffectiveFormat()->getBackgroundColor();
										$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
										$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
										$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
										$bgColorBreak = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
										$bgColorBreak = strtoupper($bgColorBreak);
									}
									if((($arr_fields[$key] == "ms_code" || $arr_fields[$key] == "code_link" || ($agency_id == 162 && $target_id == 9) || ($agency_id == 240 && $target_id == 3) )  && in_array($bgColorBreak, $color_break))) {
										$is_general = 1;
										break;
									}
									$hyperlink = null;
									if ($cell->getUserEnteredFormat() && $cell->getHyperlink()) {
										$hyperlink = $cell->getHyperlink();
										$hyperlink = ($hyperlink) ? $hyperlink : "";
									}
									
									if($clsISO->checkItemInArray("color_dq", $arr_fields)) {
										if(($arr_fields[$key] == "color_dq" && in_array($bgColorDQ,$color_dq)) || empty($color_dq)) {
											$is_dq = 1;
											$data_row["bgColor"] = $bgColorDQ;
										}
										if($agency_id == 159 && $value == "Quỹ ĐQ"){
											$is_dq = 1;
										}
									}else{
										if(($arr_fields[$key] == "ms_code" && in_array($bgColorDQ,$color_dq)) || empty($color_dq)) {
											$is_dq = 1;
											$data_row["bgColor"] = $bgColorDQ;
										}
									}
									
									/*if(((($arr_fields[$key] == "ms_code" && $agency_id != "159") || ($arr_fields[$key] == "color_dq" && $agency_id == "159")) && in_array($bgColorDQ,$color_dq)) || empty($color_dq)) {
										$is_dq = 1;
										$data_row["bgColor"] = $bgColorDQ;
									}*/
									if(!empty($is_dq)) {
										if((!empty($arr_fields[$key]) && ($arr_fields[$key] == "code_link" || $arr_fields[$key] == "ms_code")) && !empty($value)) {
											$arr_code = $this->getCode($value); 
											if($arr_fields[$key] == "code_link") {
												$data_row["price_temporary_ns"] = $hyperlink;	
											}											
											unset($ms_code);
										}else if(!empty($arr_fields[$key]) && !($arr_fields[$key] == "code_link" || $arr_fields[$key] == "ms_code")) {
											$data_row[$arr_fields[$key]] = ($arr_fields[$key] == "price_temporary_ns") ? $hyperlink : $value;
										}
									}
									
								}
								if(!empty($is_general) || !empty($is_changed)) {
//									var_dump($data_row);die;
									break;
								}
								# Lưu thông tin hàng	
								$data_sheet[$title][] = $data_row;
//								var_dump(empty($is_sold), !empty($data_row["ms_code"]), preg_match(REGEX_MS_CODE, $data_row["ms_code"]),$data_row["ms_code"]);
//								echo "--------<br>";
								if(empty($is_sold) && !empty($arr_code)) {		
									foreach ($arr_code as $ms_code) {
										$tmp = $data_row;
										if(preg_match(REGEX_MS_CODE, $ms_code)) {
											$tblData[] = array_merge(["ms_code"=>$ms_code],$data_row);								
//											$data_sheet[$title][] = array_merge([$ms_code],$data_row);
										}
									}
										
								}
							}
						}
					}
				}
//				var_dump($data_sheet);die;
				if(!empty($is_changed)) {	
					$res = [
						"result"	=>	false,
						"type"		=>	"change_field"
					];
				}else{
					$res = [
						"result"	=>	true,
						"spreadsheetId_copy"	=>	$spreadsheetId_copy,
						"tblData"	=>	$tblData,
						"data_sheet"	=>	$data_sheet,
					];
				}
				
			}else{
				$res["result"] = false;
			}
		}
		return $res;
	}
	function getCode($ms_code) {
		if (strpos($ms_code, '/') === false) {			
			$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($ms_code)));	
			$ms_code = strtoupper($ms_code);
			$ms_code = str_replace(" ","",$ms_code);
			$ms_code = str_replace("(LẺ)","",$ms_code);
			$ms_code = str_replace("(CHẴN)","",$ms_code);
			$ms_code = str_replace("CĂNGÓC","",$ms_code);
			$ms_code = str_replace("GóC","",$ms_code);
			$ms_code = trim($ms_code); 
			return [ $ms_code ];
		}
		list($prefix, $nums) = explode('-', $ms_code, 2);
		$arr_code = explode('/', $nums);
		$results = [];
		foreach ($arr_code as $code) {
			$code = trim($code);
			$ms_code = "{$prefix}-{$code}";
			$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($ms_code)));	
			$ms_code = strtoupper($ms_code);
			$ms_code = str_replace(" ","",$ms_code);
			$ms_code = str_replace("(LẺ)","",$ms_code);
			$ms_code = str_replace("(CHẴN)","",$ms_code);
			$ms_code = str_replace("CĂNGÓC","",$ms_code);
			$ms_code = str_replace("GóC","",$ms_code);
			$ms_code = trim($ms_code); 
			$results[] = $ms_code;
		}
		return $results;
	}
	function renderArrayLog($logs=array(), $field, $from_id, $to_id){
		global $clsISO,$core,$profile_id;
		if(IS_ADMIN_PAGE == 1) {
			$user_id = $core->_USER['user_id'];
		}else{
			$user_id = $profile_id;
		}
		if(in_array($field, [
			'total_price',
			'total_price_vat',
			'total_price_early',
			'total_price_progress',
			'total_price_bank_half',
			'total_price_bank',
			'total_price_bank_12',
			'total_price_bank_18',
			'total_price_bank_36'])){
			$from_field = 'from_value';
			$to_field = "to_value";
		} else {
			$from_field = "from_id";
			$to_field = "to_id";
		}
		$logs[$clsISO->getUniqid()] = array(
			'field' => $field,
			'reg_date' => time(),
			'user_id' => $user_id,
			$from_field => $from_id,
			$to_field => $to_id
		);
		return $logs;
	}
	function getSpreadsheetDataConfig($spreadsheetId, $ranges,$block_id, $agency_id,$stock_type,$is_copy=0){
		global $core, $dbconn, $clsISO;
		$service = new Google_Service_Sheets($this->client);
		try {
			$arr_ranges = [];
			foreach ($ranges as $range) {
				$arr_ranges[] = $range . '!A:ZZ';
			}	
			$response = $service->spreadsheets_values->batchGet($spreadsheetId, ['ranges' => $arr_ranges]);
			return array( "response"	=>	$response->getValueRanges());
		} catch (Exception $e) {
			$msg_error = $e->getMessage();
			/*if(!empty($is_copy)) {
				$clsISO->print_pre($msg_error);die;	
			}*/	
			if(json_decode($msg_error)->error->status == "INVALID_ARGUMENT"){
				return 0;
			}
			$spreadsheetId_Old = $spreadsheetId;
			$spreadsheetId = $this->copySpreadsheet($spreadsheetId_Old, $ranges,$block_id, $agency_id,$stock_type);
			if(!empty($spreadsheetId)){
				return $this->getSpreadsheetDataConfig($spreadsheetId, $ranges,$block_id, $agency_id,$stock_type,1);
			}		
			return 0;			
		}
		return 0;
	}
	function getDataConfigColumn($spreadsheetId,$ranges,$block_id,$agency_id,$stock_type) {
		global $core, $dbconn, $clsISO;
		$sheets = [];
		// Init service
		$service = new Google_Service_Drive($this->client);
		// Get Data Sheet
		$dataResponse = $this->getSpreadsheetDataConfig($spreadsheetId, $ranges,$block_id, $agency_id,$stock_type);
		$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;			
		// Xóa file sao chép sau khi đọc xong
		if(isset($response["is_copy"]) && $response["is_copy"] == 1) {
			// $service->files->delete($spreadsheetId);
		}
		if(!empty($response)) {
			foreach ($response as $valueRange) {
				// Lấy tên sheet từ chuỗi range (ví dụ: "Sheet1!A:Z")
				$range = $valueRange->getRange();
				$parts = explode('!', $range);
				$sheet_name = $parts[0];
				// Lấy dữ liệu của sheet đó
				$sheets[$sheet_name] = $valueRange->getValues();
			}
		}		
		return $sheets;
	}
	function isSubset_diff($arr1, $arr2){
		return empty(array_diff($arr1, $arr2));
	}
	/*===========Verrsion 2===========*/	
	
	function getSpreadsheetDataSmartChip($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,$is_copy=0){
		global $core, $dbconn, $clsISO,$profile_id;
		$service = new Google_Service_Sheets($this->client);		
		$clsLogCrawl = new LogCrawl();
		$clsProperty = new Property();
		if((((($agency_id == 10890 || $agency_id == 1321) && $target_id == 11047) || ($agency_id == 8801 && $target_id == 11110)) && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) || 
		   ($agency_id == 262 && ($target_id == 2 || $target_id == 3) && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE)) {
			$spreadsheetIdNew = $spreadsheetId;
		}else{
			$spreadsheetIdNew = $this->copySpreadsheet($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type);	
		}	
		
		
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_number_check = !empty($more_information["block_number_check"]) ? $more_information["block_number_check"] : array();
		$project_number_check = !empty($more_information["project_number_check"]) ? $more_information["project_number_check"] : array();
//		var_dump($arr_number_check);
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {	
			$arr_number_check = $project_number_check[$target_id]["number_check"];		
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		}else if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$arr_number_check = $block_number_check[$target_id]["number_check"];
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
		}
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arr_column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) 
			? $arr_column_data[$agency_id][$target_id] : array();
		$pattern = '/[\s]|[^a-zA-Z0-9]/u';
		
		if(!empty($spreadsheetIdNew)) {
			try {
				$spreadsheet = $service->spreadsheets->get($spreadsheetIdNew, [
					'fields' => 'sheets(properties(sheetId,title,gridProperties(columnCount)))'
				]);
				$arr_column_sheet = [];
				foreach ($spreadsheet->getSheets() as $s) {
					$props = $s->getProperties();
					$title = $props->getTitle();
					$sheetId = $props->getSheetId();
					$grid = $props->getGridProperties();
					if($grid && $totalColumn = $grid->getColumnCount()) {
						$arr_column_sheet["'".$title."'"] = [
							"sheetId"	=>	$sheetId,
							"totalColumn"	=>	$totalColumn,
						];
					}
					
				}
				
				$sheetsMapping = $requests = array();
				if(!empty($column_data) && $spreadsheetIdNew != $spreadsheetId) {
					foreach ($column_data as $sheetName => $col) {
						$arr_fields = !empty($column_data["'".$sheetName."'"]) ? $column_data["'".$sheetName."'"] : $column_data[$sheetName];
						$number_check = !empty($arr_number_check[$sheetName]) ? $arr_number_check[$sheetName] : 0;
						
						$sourceColumnIndex = $sourceColumnIndex1 = $targetColumnIndex =  "";
						foreach ($col as $key => $field_name) {
							if(($field_name == "price_sheet_link" && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) 
							   || ($field_name == "price_temporary_ns" && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) 
							   || $field_name == "code_link") {
								if(!empty($sourceColumnIndex)) {
									$sourceColumnIndex1 = $key;
								}else{
									$sourceColumnIndex = $key;
								}
								
							}else if($field_name == "link_smartchip") {
								$targetColumnIndex = $key;
							}
							if(!empty($sourceColumnIndex) && !empty($targetColumnIndex)) break;
						}
								
						if(!empty($sourceColumnIndex) && !empty($targetColumnIndex)){
							// Tạo instance của Google Sheets Service
							$sourceColumn = $this->columnLetter($sourceColumnIndex);
							if(!empty($sourceColumnIndex1)) $sourceColumn1 = $this->columnLetter($sourceColumnIndex1);
							$targetColumn = $this->columnLetter($targetColumnIndex);
							
							//thêm cột mới nếu không tồn tại $targetColumn
							$column_sheet = !empty($arr_column_sheet["'".$sheetName."'"]) ? $arr_column_sheet["'".$sheetName."'"] : $arr_column_sheet[$sheetName];
							if (!empty($column_sheet) && $column_sheet["totalColumn"] < $targetColumnIndex+1) {
								// Chèn từ index hiện có đến index targetColumns (không bao gồm endIndex)
								$requests = [
									new Google_Service_Sheets_Request([
										'insertDimension' => [
											'range' => [
												'sheetId' => $column_sheet["sheetId"],
												'dimension' => 'COLUMNS',
												'startIndex' => $column_sheet["totalColumn"],
												'endIndex' => $targetColumnIndex+1
											],
											'inheritFromBefore' => true
										]
									])
								];
								$batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
									'requests' => $requests
								]);
								$service->spreadsheets->batchUpdate($spreadsheetIdNew, $batchUpdateRequest);
							}
							// Bước 1: Đọc dữ liệu của cột A (ví dụ: từ A1 trở xuống)
							$rangeSource = $sheetName.'!'.$sourceColumn.'1:'.$sourceColumn;
							$response = $service->spreadsheets_values->get($spreadsheetIdNew, $rangeSource);
							$rangeSource = $response->getValues();
							if (!empty($rangeSource)) {
								// Bước 2: Tạo dữ liệu cho cột B dựa theo dữ liệu của cột A.
								// Ví dụ: công thức ghép chuỗi từ ô A với ".URL"
								$valuesTarget = [];
								foreach ($rangeSource as $index => $rowData) {
									$formula = "";
									if($index == $number_check) {
										$formula = "Link smartchip";
									}
									
									$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($rowData[0])));
									$ms_code = str_replace("TC","",$ms_code);
									$ms_code = str_replace(" ","",$ms_code);
									$ms_code = trim($ms_code,"x");
									// Lưu ý: các dòng trong Google Sheet đánh số từ 1
									if($index > $number_check && !empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
										$rowNumber = $index + 1;
										// Tạo công thức cho ô tương ứng ở cột B; ví dụ: =A1 & ".URL"
										// Bạn có thể thay đổi công thức theo mục đích của mình
										if(!empty($sourceColumn) && !empty($sourceColumn1)) {
											$formula = "=IFERROR({$sourceColumn}{$rowNumber}.URL;{$sourceColumn1}{$rowNumber}.URL)";
										}else{
											$formula = "={$sourceColumn}{$rowNumber}.URL";
										}
										
									}
									$valuesTarget[] = [$formula];
								}
								// Xác định phạm vi cập nhật ở cột B (từ B1 đến B số dòng tương ứng với dữ liệu ở cột A)
								$rangeB = $sheetName.'!'.$targetColumn.'1:'.$targetColumn . count($valuesTarget);
								// Gói dữ liệu cần cập nhật vào một đối tượng ValueRange
								$body = new Google_Service_Sheets_ValueRange([
									'values' => $valuesTarget
								]);
								// Thiết lập tùy chọn giá trị là USER_ENTERED để Google Sheets xử lý chuỗi như là công thức
								$params = [
									'valueInputOption' => 'USER_ENTERED'
								];
								// Bước 3: Gọi API cập nhật dữ liệu
								$result = $service->spreadsheets_values->update($spreadsheetIdNew, $rangeB, $body, $params);
							}							
						}
					}
				}
				$range_column = "";
				if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
					$range_column = '!A1:ZZ500';
				}					
				$arr_ranges = [];
				foreach ($ranges as $range) {
					$arr_ranges[] = $range . $range_column;					
				}
				
				try {
					$response = $service->spreadsheets->get($spreadsheetIdNew, [
						'ranges' => $arr_ranges,
						'includeGridData' => true
					]);
					return array(
						'spreadsheetId' => $spreadsheetIdNew,
						"response"	=>	$response->getSheets(),
						"is_copy"	=>	$is_copy
					);
				} catch (Exception $e) {
					$msg_error = $e->getMessage();	
					if($profile_id == 289) {
						echo "sss";
						$clsISO->print_pre($msg_error);die;
					}
					$data = array(
						"title_log"	=>	'File Gooogle Sheet không thể đọc',
						"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"read_speadsheet",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
					return 0; 			
				}
				
			} catch (Exception $e) {
				$msg_error = $e->getMessage();	
				if($profile_id == 289) {
					echo "aaa";
					$clsISO->print_pre($msg_error);die;
				}
				$data = array(
					"title_log"	=>	'File Gooogle Sheet không thể đọc',
					"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
					"result_type"	=>	"read_speadsheet",	//change_field,copy_speadsheet,read_speadsheet
				);
				$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
			}
		}
		return 0;
	}
	function getSpreadsheetDataSmartChipNew($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type, $is_copy = 0) {
		global $core, $dbconn, $clsISO, $profile_id;

		$service = new Google_Service_Sheets($this->client);		
		$clsLogCrawl = new LogCrawl();
		$clsProperty = new Property();

		// -------------------------------------------------------------------------
		// 1. CONFIG MAPPING: Loại bỏ đống câu lệnh if/else kiểm tra điều kiện copy phức tạp
		// -------------------------------------------------------------------------
		static $NO_COPY_MAP = [
			_BLOCK_TYPE_HIGHLEVEL_SALE => [
				10890 => [11047],
				1321  => [11047],
				8801  => [11110]
			],
			_BLOCK_TYPE_LOWFLOOR_SALE => [
				262   => [2, 3]
			]
		];

		$is_no_copy = isset($NO_COPY_MAP[$stock_type][$agency_id]) && in_array($target_id, $NO_COPY_MAP[$stock_type][$agency_id]);
		$spreadsheetIdNew = $is_no_copy ? $spreadsheetId : $this->copySpreadsheet($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type);	

		if (empty($spreadsheetIdNew)) {
			return 0;
		}

		// -------------------------------------------------------------------------
		// 2. TẢI CẤU HÌNH VÀ FILE CACHE AN TOÀN
		// -------------------------------------------------------------------------
		$oneAgency = $clsProperty->getOne($agency_id, "more_information");
		$more_information = $clsISO->to_array_json($oneAgency['more_information'] ?? '');

		$block_number_check = $more_information["block_number_check"] ?? [];
		$project_number_check = $more_information["project_number_check"] ?? [];
		$arr_number_check = [];
		$cachedFile = '';

		if ($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {	
			$arr_number_check = $project_number_check[$target_id]["number_check"] ?? [];		
			$cachedFile = DIR_CACHE_JSON . '/crawl/config_column_lowfloor.json';
		} else if ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$arr_number_check = $block_number_check[$target_id]["number_check"] ?? [];
			$cachedFile = DIR_CACHE_JSON . '/crawl/config_column_highfloor.json';
		}

		$arr_column_data = (file_exists($cachedFile)) ? (new Webmozart\Json\JsonDecoder())->decodeFile($cachedFile) : [];
		$column_data = $arr_column_data[$agency_id][$target_id] ?? [];

		try {
			// Đọc cấu trúc danh sách các Sheet hiện có
			$spreadsheet = $service->spreadsheets->get($spreadsheetIdNew, [
				'fields' => 'sheets(properties(sheetId,title,gridProperties(columnCount)))'
			]);

			$arr_column_sheet = [];
			foreach ($spreadsheet->getSheets() as $s) {
				$props = $s->getProperties();
				$title = $props->getTitle();
				$grid = $props->getGridProperties();
				if ($grid && $totalColumn = $grid->getColumnCount()) {
					$arr_column_sheet["'".$title."'"] = [
						"sheetId"     => $props->getSheetId(),
						"totalColumn" => $totalColumn,
					];
				}
			}

			// Khởi tạo mảng gom dữ liệu nhằm tối ưu hóa số lượt gọi API (Batching)
			$batchStructuralRequests = []; 
			$batchValueData = [];          

			if (!empty($column_data) && $spreadsheetIdNew != $spreadsheetId) {
				foreach ($column_data as $sheetName => $col) {
					$number_check = $arr_number_check[$sheetName] ?? 0;

					$sourceColumnIndex = null;
					$sourceColumnIndex1 = null;
					$targetColumnIndex = null;

					// Xác định nhanh chỉ mục của các cột nguồn và đích
					foreach ($col as $key => $field_name) {
						if (($field_name == "price_sheet_link" && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE)   
						   || ($field_name == "price_temporary_ns" && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE)  
						   || $field_name == "code_link") {
							if ($sourceColumnIndex !== null) {
								$sourceColumnIndex1 = $key;
							} else {
								$sourceColumnIndex = $key;
							}
						} else if ($field_name == "link_smartchip") {
							$targetColumnIndex = $key;
						}
						if ($sourceColumnIndex !== null && $targetColumnIndex !== null) break;
					}

					if ($sourceColumnIndex !== null && $targetColumnIndex !== null) {
						$sourceColumn = $this->columnLetter($sourceColumnIndex);
						$sourceColumn1 = ($sourceColumnIndex1 !== null) ? $this->columnLetter($sourceColumnIndex1) : null;
						$targetColumn = $this->columnLetter($targetColumnIndex);

						$column_sheet = $arr_column_sheet["'".$sheetName."'"] ?? $arr_column_sheet[$sheetName] ?? null;

						if (!empty($column_sheet)) {
							// TỐI ƯU 1: Gom các yêu cầu thêm cột mới nếu thiếu cấu trúc
							if ($column_sheet["totalColumn"] < $targetColumnIndex + 1) {
								$batchStructuralRequests[] = new Google_Service_Sheets_Request([
									'insertDimension' => [
										'range' => [
											'sheetId'    => $column_sheet["sheetId"],
											'dimension'  => 'COLUMNS',
											'startIndex' => $column_sheet["totalColumn"],
											'endIndex'   => $targetColumnIndex + 1
										],
										'inheritFromBefore' => true
									]
								]);
							}

							// Đọc dữ liệu cột nguồn (Bắt buộc phải gọi Get để có dữ liệu build công thức dòng)
							$rangeSource = $sheetName . '!' . $sourceColumn . '1:' . $sourceColumn;
							$responseValues = $service->spreadsheets_values->get($spreadsheetIdNew, $rangeSource);
							$rangeSourceData = $responseValues->getValues();

							if (!empty($rangeSourceData)) {
								$valuesTarget = [];
								foreach ($rangeSourceData as $index => $rowData) {
									$formula = "";
									if ($index == $number_check) {
										$formula = "Link smartchip";
									}

									$cellRawValue = $rowData[0] ?? '';
									$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($cellRawValue)));
									$ms_code = str_replace(["TC", " "], "", $ms_code);
									$ms_code = trim($ms_code, "x");

									if ($index > $number_check && !empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
										$rowNumber = $index + 1;

										if (!empty($sourceColumn) && !empty($sourceColumn1)) {
											$formula = "=IFERROR({$sourceColumn}{$rowNumber}.URL;{$sourceColumn1}{$rowNumber}.URL)";
										} else {
											$formula = "={$sourceColumn}{$rowNumber}.URL";
										}
									}
									$valuesTarget[] = [$formula];
								}

								// TỐI ƯU 2: Gom dữ liệu công thức cần ghi thay vì cập nhật đơn lẻ từng sheet
								$batchValueData[] = new Google_Service_Sheets_ValueRange([
									'range'  => $sheetName . '!' . $targetColumn . '1:' . $targetColumn . count($valuesTarget),
									'values' => $valuesTarget
								]);
							}							
						}
					}
				}

				// Thực thi chèn cột cấu trúc TẬP TRUNG (Chỉ dùng đúng 1 Single API Call)
				if (!empty($batchStructuralRequests)) {
					$batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
						'requests' => $batchStructuralRequests
					]);
					$service->spreadsheets->batchUpdate($spreadsheetIdNew, $batchUpdateRequest);
				}

				// Ghi dữ liệu công thức hàng loạt TẬP TRUNG (Chỉ dùng đúng 1 Single API Call)
				if (!empty($batchValueData)) {
					$batchValuesRequestBody = new Google_Service_Sheets_BatchUpdateValuesRequest([
						'valueInputOption' => 'USER_ENTERED',
						'data'             => $batchValueData
					]);
					$service->spreadsheets_values->batchUpdate($spreadsheetIdNew, $batchValuesRequestBody);
				}
			}

			// -------------------------------------------------------------------------
			// 3. ĐỌC TOÀN BỘ GRID DATA VỀ CHO HÀM GATDATANEW() XỬ LÝ
			// -------------------------------------------------------------------------
			$range_column = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? '!A1:AC400' : '';
			$arr_ranges = [];
			foreach ($ranges as $range) {
				$arr_ranges[] = $range . $range_column;					
			}

			$response = $service->spreadsheets->get($spreadsheetIdNew, [
				'ranges'          => $arr_ranges,
				'includeGridData' => true
			]);

			return [
				'spreadsheetId' => $spreadsheetIdNew,
				"response"      => $response->getSheets(),
				"is_copy"       => $is_copy
			];

		} catch (Exception $e) {
			if ($profile_id == 289) {
				$clsISO->print_pre($e->getMessage());
				die;
			}
			$data = [
				"title_log"   => 'File Google Sheet không thể đọc hoặc xử lý lỗi: ' . $e->getMessage(),
				"type"        => 1,	
				"result_type" => "read_speadsheet",	
			];
			$clsLogCrawl->log($agency_id, $target_id, $data, $stock_type);
		}
		return 0;
	}
	function getSpreadsheetDataSmartChipV1($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,$is_copy=0){
		global $core, $dbconn, $clsISO,$profile_id;
		$service = new Google_Service_Sheets($this->client);		
		$clsLogCrawl = new LogCrawl();
		$clsProperty = new Property();
		$spreadsheetIdNew = $this->copySpreadsheet($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type);	
		
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_number_check = !empty($more_information["block_number_check"]) ? $more_information["block_number_check"] : array();
		$project_number_check = !empty($more_information["project_number_check"]) ? $more_information["project_number_check"] : array();
//		var_dump($arr_number_check);
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {	
			$arr_number_check = $project_number_check[$target_id]["number_check"];		
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		}else if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$arr_number_check = $block_number_check[$target_id]["number_check"];
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
		}
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arr_column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) 
			? $arr_column_data[$agency_id][$target_id] : array();
		$pattern = '/[\s]|[^a-zA-Z0-9]/u';
		if(!empty($spreadsheetIdNew)) {
			try {
				$sheetsMapping = $requests = array();
				if(!empty($column_data) && $spreadsheetIdNew != $spreadsheetId) {
					foreach ($column_data as $sheetName => $col) {
						$arr_fields = !empty($column_data["'".$sheetName."'"]) ? $column_data["'".$sheetName."'"] : $column_data[$sheetName];
						$number_check = !empty($arr_number_check[$sheetName]) ? $arr_number_check[$sheetName] : 0;
						
						$sourceColumnIndex = $targetColumnIndex =  "";
						foreach ($col as $key => $field_name) {
							if(($field_name == "price_sheet_link" && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) 
							   || ($field_name == "price_temporary_ns" && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) 
							   || $field_name == "code_link") {
								$sourceColumnIndex = $key;
							}else if($field_name == "link_smartchip") {
								$targetColumnIndex = $key;
							}
							if(!empty($sourceColumnIndex) && !empty($targetColumnIndex)) break;
						}
						if(!empty($sourceColumnIndex) && !empty($targetColumnIndex)){
							// Tạo instance của Google Sheets Service
							$sourceColumn = $this->columnLetter($sourceColumnIndex);
							$targetColumn = $this->columnLetter($targetColumnIndex);
							// Bước 1: Đọc dữ liệu của cột A (ví dụ: từ A1 trở xuống)
							$rangeSource = $sheetName.'!'.$sourceColumn.'1:'.$sourceColumn;
							$response = $service->spreadsheets_values->get($spreadsheetIdNew, $rangeSource);
							$rangeSource = $response->getValues();
							if (!empty($rangeSource)) {
								// Bước 2: Tạo dữ liệu cho cột B dựa theo dữ liệu của cột A.
								// Ví dụ: công thức ghép chuỗi từ ô A với ".URL"
								$valuesTarget = [];
								foreach ($rangeSource as $index => $rowData) {
									$formula = "";
									if($index == $number_check) {
										$formula = "Link smartchip";
									}
									
									$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($rowData[0])));
									$ms_code = str_replace("TC","",$ms_code);
									$ms_code = str_replace(" ","",$ms_code);
									$ms_code = trim($ms_code,"x");
									// Lưu ý: các dòng trong Google Sheet đánh số từ 1
									if($index > $number_check && !empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
										$rowNumber = $index + 1;
										// Tạo công thức cho ô tương ứng ở cột B; ví dụ: =A1 & ".URL"
										// Bạn có thể thay đổi công thức theo mục đích của mình
										$formula = "={$sourceColumn}{$rowNumber}.URL";
									}
									
	//								echo $formula;die;
									$valuesTarget[] = [$formula];
								}
//								$clsISO->print_pre($valuesTarget);die;
								// Xác định phạm vi cập nhật ở cột B (từ B1 đến B số dòng tương ứng với dữ liệu ở cột A)
								$rangeB = $sheetName.'!'.$targetColumn.'1:'.$targetColumn . count($valuesTarget);
//								var_dump($targetColumn,$rangeB);die;
//								$clsISO->print_pre($valuesTarget);die;
								// Gói dữ liệu cần cập nhật vào một đối tượng ValueRange
								$body = new Google_Service_Sheets_ValueRange([
									'values' => $valuesTarget
								]);
								// Thiết lập tùy chọn giá trị là USER_ENTERED để Google Sheets xử lý chuỗi như là công thức
								$params = [
									'valueInputOption' => 'USER_ENTERED'
								];
								// Bước 3: Gọi API cập nhật dữ liệu
								$result = $service->spreadsheets_values->update($spreadsheetIdNew, $rangeB, $body, $params);
							}							
						}
					}
				}
				try {
					$response = $service->spreadsheets->get($spreadsheetIdNew, [
						'ranges' => $ranges,
						'includeGridData' => true
					]);
					return array(
						'spreadsheetId' => $spreadsheetIdNew,
						"response"	=>	$response->getSheets(),
						"is_copy"	=>	$is_copy
					);
				} catch (Exception $e) {
					$msg_error = $e->getMessage();	
					if($profile_id == 289) {
						echo "sss";
						$clsISO->print_pre($msg_error);die;
					}
					$data = array(
						"title_log"	=>	'File Gooogle Sheet không thể đọc',
						"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"read_speadsheet",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
					return 0; 			
				}
				
			} catch (Exception $e) {
				$msg_error = $e->getMessage();	
				if($profile_id == 289) {
					echo "aaa";
					$clsISO->print_pre($msg_error);die;
				}
				$data = array(
					"title_log"	=>	'File Gooogle Sheet không thể đọc',
					"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
					"result_type"	=>	"read_speadsheet",	//change_field,copy_speadsheet,read_speadsheet
				);
				$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
			}
		}
		return 0;
	}
	function getDataLowFloor($spreadsheetId, $ranges, $target_id, $agency_id,$stock_type,$crawl_lowfloor = array()) {
		global $core, $dbconn, $clsISO,$profile_id;
		$clsStock = new StockCrawl();		
		$clsProperty = new Property();	
		$clsLogCrawl = new LogCrawl();
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';	
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$project_number_check = !empty($more_information["project_number_check"]) ? $more_information["project_number_check"] : array();
		$arr_number_check = $project_number_check[$target_id]["number_check"];
		$list_data_check = $project_number_check[$target_id]["list_data_check"];
		$arr_column_data = array();
		if(file_exists($cachedFile)){
			$arr_column_data = $this->decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();	
//		return $column_data;
		
		$tblData = $tblHidden = array();
		$res = ["result" => false];
		if(!empty($column_data)) { 
			if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE && $agency_id ==159 && ($target_id == 2 || $target_id == 3 || $target_id == 10)) {
				$dataResponse = $this->getSpreadsheetData($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);
			}else{
				$dataResponse = $this->getSpreadsheetDataSmartChip($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);
			}
			
			
			$spreadsheetId_crawl = $dataResponse["spreadsheetId"];
			$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
			if(!empty($response)) {
				foreach ($response as $sheet) {
					$title = $sheet->getProperties()->getTitle(); //tiêu đề sheet
					$sheet_id = $sheet->getProperties()->getSheetId();	
					$crawl_lowfloor_sheet = !empty($crawl_lowfloor["'".$title."'"]) ? $crawl_lowfloor["'".$title."'"] : $crawl_lowfloor[$title];	
					$color_sold = !empty($crawl_lowfloor_sheet['color_sold']) ? $crawl_lowfloor_sheet['color_sold'] : array();
					$color_dq = (!empty($crawl_lowfloor_sheet['is_color_dq']) && !empty($crawl_lowfloor_sheet['color_dq'])) ? $crawl_lowfloor_sheet['color_dq'] : array();
					$color_break = (!empty($crawl_lowfloor_sheet['is_color_break']) && !empty($crawl_lowfloor_sheet['color_break'])) ? $crawl_lowfloor_sheet['color_break'] : array();
					$arr_fields = !empty($column_data["'".$title."'"]) ? $column_data["'".$title."'"] : $column_data[$title];	
					$number_check = !empty($arr_number_check["'".$title."'"]) ? $arr_number_check["'".$title."'"] : $arr_number_check[$title];	
					$data_check = !empty($list_data_check["'".$title."'"]) ? $list_data_check["'".$title."'"] : $list_data_check[$title];	
					$merges = $sheet->getMerges();
//					var_dump($color_sold);die;
//					var_dump($sheet_id,$crawl_lowfloor_sheet,$crawl_lowfloor);die;
//					$number_check = 10; //test
					$data_sheet[$title] = [];
					foreach ($sheet->getData() as $data) {
						$rowData = $data->getRowData();
						$rowMetadata  = $data->getRowMetadata();
						$rowData = $data->getRowData();
						$rowMetadata  = $data->getRowMetadata();
						$colMetadata = $data->getColumnMetadata();
						//tiêu đề bảng
						$header = $this->getHeader($rowData,$rowMetadata,$title,$column_data,$list_data_check,$colMetadata);
//						var_dump($column_data,$list_data_check);die;
						if(!empty($header)) {
							$fields_table = array();
							foreach ($rowData as  $i => $row) {
								if(isset($header[$i])) {
									$fields_table = $header[$i];
								}
								if(!empty($fields_table)) {
//									$clsISO->print_pre($fields_table);die;
									$is_sold = $is_general = $isHidden = $is_changed = 0;
									if (isset($rowMetadata[$i])) {
										$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
									}
									if($i < $number_check ) {
										continue;
									}
									# Nếu hàng ẩn thì bỏ qua
									if (!$isHidden) {
										$data_row = $arr_code = array();
										$is_dq = 0;
										foreach ($row->getValues() as $key => $cell) {									
											$value = $cell->getFormattedValue();	
											if((str_contains($clsISO->replaceSpace($value),'bang-hang-chung') && $agency_id == 11568 && $target_id ==42)) {
												$is_general = 1;
												break;
											}
											$colMeta = $data->getColumnMetadata()[$key];
											if($colMeta->getHiddenByUser() || $colMeta->getHiddenByFilter()){
												continue;
											}
											# Lấy backgroundColor của ô nếu có
											$bgColor = $bgColorBreak = $bgColorDQ = null;
											if (($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link" || $fields_table[$key] == "color_dq" || $agency_id == 238) && $cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
												$color = $cell->getEffectiveFormat()->getBackgroundColor();
												$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
												$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
												$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
												$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
												$bgColor = strtoupper($bgColor);
												$bgColorBreak = $bgColor;										
												if(($clsISO->checkItemInArray("color_dq", $fields_table) && $fields_table[$key] == "color_dq") || !$clsISO->checkItemInArray("color_dq", $fields_table) ){
													$bgColorDQ = $bgColor;
												}
											}
											# end background
											if((($agency_id == 276 && $target_id == 3) || ($agency_id == 162 && $target_id == 9) || ($agency_id == 276 && $target_id == 3) /*QTC VHOP3*/ || ($agency_id == 240 && $target_id == 3) /*NSL VHOP3*/) && !empty($color_break) && $cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
												$color = $cell->getEffectiveFormat()->getBackgroundColor();
												$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
												$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
												$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
												$bgColorBreak = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
												$bgColorBreak = strtoupper($bgColorBreak);
											}
											if((($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link" || ($agency_id == 162 && $target_id == 9) || ($agency_id == 240 && $target_id == 3) )  && in_array($bgColorBreak, $color_break))) {
												$is_general = 1;
												break;
											}
											if((str_contains($clsISO->replaceSpace($value),'bang-hang-chung') && $agency_id == 11566 && $target_id ==42)) {
												$is_general = 1;
												break;
											}
											if(($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link" || $agency_id == 238) && in_array($bgColor,$color_sold)) {
												$is_sold = 1;
												$data_row["bgColor"] = $bgColor;
												break;
											}
											$hyperlink = null;
											if ($cell->getUserEnteredFormat() && $cell->getHyperlink()) {
												$hyperlink = $cell->getHyperlink();
												$hyperlink = ($hyperlink) ? $hyperlink : "";
											}
											if($clsISO->checkItemInArray("color_dq", $fields_table)) {
												if(($fields_table[$key] == "color_dq" && in_array($bgColorDQ,$color_dq)) || empty($color_dq)) {
													$is_dq = 1;
													$data_row["bgColor"] = $bgColorDQ;
												}
												if($agency_id == 159 && $value == "Quỹ ĐQ"){
													$is_dq = 1;
												}
											}else{
												if(($fields_table[$key] == "ms_code" && in_array($bgColorDQ,$color_dq)) || empty($color_dq)) {
													$is_dq = 1;
													$data_row["bgColor"] = $bgColorDQ;
												}
											}
											/*if(((($fields_table[$key] == "ms_code" && $agency_id != "159") || ($fields_table[$key] == "color_dq" && $agency_id == "159")) && in_array($bgColorDQ,$color_dq)) || empty($color_dq)) {
												$is_dq = 1;
												$data_row["bgColor"] = $bgColorDQ;
											}*/
											if((!empty($fields_table[$key]) && ($fields_table[$key] == "code_link" || $fields_table[$key] == "ms_code")) && !empty($value)) {
												$arr_code = $this->getCode($value); 
												if($fields_table[$key] == "code_link") {
													$data_row["price_temporary_ns"] = $hyperlink;	
												}	
												unset($ms_code);
											}else if(!empty($fields_table[$key]) && !($fields_table[$key] == "code_link" || $fields_table[$key] == "ms_code")) {
												$data_row[$fields_table[$key]] = (($fields_table[$key] == "price_temporary_ns") || ($fields_table[$key] == "link_smartchip")) ? $hyperlink : $value;
											}
										}
										if(!empty($is_general) || !empty($is_changed)) {
		//									var_dump($data_row);die;
											break;
										}
										# Lưu thông tin hàng	
										$data_sheet[$title][] = $data_row;
		//								var_dump(empty($is_sold), !empty($data_row["ms_code"]), preg_match(REGEX_MS_CODE, $data_row["ms_code"]),$data_row["ms_code"]);
		//								echo "--------<br>";
										$data_sheet[$title][] = array_merge([$ms_code],$data_row);
										if(empty($is_sold) && !empty($arr_code) && !empty($is_dq)) {	
											foreach ($arr_code as $ms_code) {
												$tmp = $data_row;
												if(preg_match(REGEX_MS_CODE, $ms_code) && strlen($ms_code) < 20) {
													$tblData[] = array_merge(["ms_code"=>$ms_code],$data_row);								
		//											$data_sheet[$title][] = array_merge([$ms_code],$data_row);
												}
											}
										}
									}
								}
								
							}
						}
					}
				}
				if($profile_id == 289) {
					echo $spreadsheetId_crawl;
					$clsISO->print_pre($tblData);die;
//					$clsISO->print_pre($data_sheet);die;
				}
//				var_dump($data_sheet);die;
				if(!empty($is_changed)) {	
					$res = [
						"result"	=>	false,
						"type"		=>	"change_field"
					];
				}else{
					$res = [
						"result"	=>	true,
						"spreadsheetId_crawl"	=>	$spreadsheetId_crawl,
						"tblData"	=>	$tblData,
						"data_sheet"	=>	$data_sheet,
					];
				}
				
			}else{
				$res["result"] = false;
			}
		}
		return $res;
	}
	function getDataNewV2($spreadsheetId, $ranges, $target_id, $agency_id,$stock_type) {
		global $core, $dbconn, $clsISO,$profile_id;
		$clsStock = new StockCrawl();		
		$clsProperty = new Property();	
		$clsLogCrawl = new LogCrawl();
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';			
		}else{
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
		}
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_number_check = !empty($more_information["block_number_check"]) ? $more_information["block_number_check"] : array();;
		$arr_number_check = $block_number_check[$target_id]["number_check"];
		$list_data_check = $block_number_check[$target_id]["list_data_check"];
		$arr_column_data = array();
		if(file_exists($cachedFile)){
			$arr_column_data = $this->decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();
//		return $column_data;
		
		$color_sold = !empty($value['color_sold']) ? $value['color_sold'] : array("#FF0000","#EA4335");
		if($agency_id == 284) $color_sold[] = "#A5A5A5"; //đông đô
		if($agency_id == 318) $color_sold[] = "#8E7CC3"; //Tân Long
//		if($agency_id == 265) $color_sold[] = "#FFF2CC"; //Trường Phát
		if($agency_id == 239) $color_sold[] = "#EA4335"; //Đất Việt
		if($agency_id == 8801) $color_sold[] = "#CC0000"; //Elite Captital
		if($agency_id == 280 && $target_id == 10177) $color_sold[] = "#980000"; //EH-ATD
		
		$color_break = (!empty($value['is_color_break']) && !empty($value['color_break'])) ? $value['color_break'] : array("#20124D");
		if($agency_id == 162) $color_break[] = "#FF0000"; //VHS
		if($agency_id == 10572 && $target_id == 1349) $color_break[] = "#FF0000"; //VSL
		
		$tblData = $tblHidden = array();
		$res = ["result" => false];
		if(!empty($column_data)) { 
			$dataResponse = $this->getSpreadsheetDataSmartChip($spreadsheetId, $ranges,$target_id, $agency_id,$stock_type,0);
			
			$spreadsheetId_crawl = $dataResponse["spreadsheetId"];
			$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
			if(!empty($response)) {
				foreach ($response as $sheet) {
					$title = $sheet->getProperties()->getTitle();
					$arr_fields = !empty($column_data["'".$title."'"]) ? $column_data["'".$title."'"] : $column_data[$title];
					$number_check = !empty($arr_number_check["'".$title."'"]) ? $arr_number_check["'".$title."'"] : $arr_number_check[$title];
					$data_check = !empty($list_data_check["'".$title."'"]) ? $list_data_check["'".$title."'"] : $list_data_check[$title];
					foreach ($sheet->getData() as $data) {
						$rowData = $data->getRowData();
						$rowMetadata  = $data->getRowMetadata();						
						$colMetadata = $data->getColumnMetadata();
						//tiêu đề bảng
						$header = $this->getHeader($rowData,$rowMetadata,$title,$column_data,$list_data_check,$colMetadata);
						if(!empty($header)) {
							$fields_table = array();
							foreach ($rowData as  $i => $row) {
								if(isset($header[$i])) {
									$fields_table = $header[$i];
								}
								if(!empty($fields_table)) {
									$is_sold = $is_general = $isHidden = $is_changed = 0;
									if (isset($rowMetadata[$i])) {
										$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
									}
									
									
									# Nếu hàng ẩn thì bỏ qua
									if (!$isHidden) {
										$data_row = array();
										foreach ($row->getValues() as $key => $cell) {	
											$value = $cell->getFormattedValue();
											# Lấy backgroundColor của ô nếu có
											$bgColor = null;
											if ($cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
												$color = $cell->getEffectiveFormat()->getBackgroundColor();
												$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
												$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
												$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
												$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
												$bgColor = strtoupper($bgColor);
											}
											$data_row["bgColor"] = $bgColor;	
											# end background
											
											if((isset($fields_table[$key]) && ($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link")  && in_array($bgColor, $color_break)) || ($agency_id == 265 && $key == 0 && in_array($bgColor,$color_break)  /*Trường Phát*/)) {
												$is_general = 1;
												break;
											}
											
											if($clsISO->checkItemInArray("status_id",$fields_table)) {
												if($fields_table[$key] == "status_id" && $clsISO->replaceSpace($value) == "da-ban") {
													$is_sold = 1;
													$data_row["bgColor"] = $bgColor;
													break;
												}										
											}else {
												if(isset($fields_table[$key]) && ($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link") && in_array($bgColor,$color_sold)) {
													$is_sold = 1;
													$data_row["bgColor"] = $bgColor;
													break;
												}	
												if($agency_id == 318 && in_array($bgColor,$color_sold)){ //Tân Long
													$is_sold = 1;
													$data_row["bgColor"] = $bgColor;
													break;
												}
											}
											$hyperlink = null;
											if ($cell->getUserEnteredFormat() && $cell->getHyperlink()) {
												$hyperlink = $cell->getHyperlink();
												$hyperlink = ($hyperlink) ? $hyperlink : "";
											}
											if((!empty($fields_table[$key]) && ($fields_table[$key] == "code_link" || $fields_table[$key] == "ms_code")) && !empty($value)) {
												$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($value)));
												$ms_code = str_replace("TC","",$ms_code);
												$ms_code = str_replace(" ","",$ms_code);
												$ms_code = trim($ms_code,"x");
												$data_row["ms_code"] = $ms_code;
												$data_row["price_sheet_link"] = $hyperlink;
												unset($ms_code);
											}else if(!empty($fields_table[$key]) && (empty($color_dq) || (in_array($bgColor,$color_dq)))) {
												$data_row[$fields_table[$key]] = (($fields_table[$key] == "price_sheet_link") || ($fields_table[$key] == "link_smartchip")) ? $hyperlink : $value;
											}
										}
										if(!empty($is_general) || !empty($is_changed)) {
											break;
										}
										
										# Lưu thông tin hàng	
//										$data_sheet[$title][] = $data_row;
										if(empty($is_sold) && !empty($data_row["ms_code"]) && preg_match(REGEX_MS_CODE, $data_row["ms_code"])) {									
											$tblData[] = $data_row;									
											/*$data_sheet[$title][] = $data_row;*/
										}
									}
								}								
							}
						}
					}
				}
				if($profile_id == 289) {
//					echo $spreadsheetId_crawl."\n";
//					var_dump($tblData);die;
				}
				if(!empty($is_changed)) {	
					$res = [
						"result"	=>	false,
						"type"		=>	"change_field"
					];
				}else{
					$res = [
						"result"	=>	true,
						"spreadsheetId_crawl"	=>	$spreadsheetId_crawl,
						"tblData"	=>	$tblData,
						/*"data_sheet"	=>	$data_sheet,*/
					];
				}
				
			}else{
				$res["result"] = false;
			}
		}
		return $res;
	}
	function checkHeader($cells){
		global $clsISO;
		foreach ($cells as $cell) {
			$value = $cell->getFormattedValue();
			$value = $clsISO->replaceSpace($value);
			if(stripos($value, "ma-can") !== false || stripos($value, "ma-cam") !== false || stripos($value, "ten-lo") !== false || stripos($value, "loai-can") !== false || stripos($value, "ma-san-pham") !== false || stripos($value, "ten-can") !== false) {
				return true;
				break;
			}
		}
		return false;
	}
	function getColspan($row, $col, $merges){
		foreach ($merges as $range) {
			$startRow = $range->getStartRowIndex();
			$endrow = $range->getEndRowIndex();
			$startCol = $range->getStartColumnIndex();
			$endCol = $range->getEndColumnIndex();
			if ($row >= $startRow && $row < $endrow && $col >= $startCol && $col < $endCol) {
				return $endCol - $startCol;
			}
		}
		return 1;
	}
	function getHeaderV2($rows,$rowMetadata,$sheet_name,$column_data,$list_data_check,$merges) {
		global $clsISO;
		$arr_fields = !empty($column_data["'".$sheet_name."'"]) ? $column_data["'".$sheet_name."'"] : $column_data[$sheet_name];
		$data_check = !empty($list_data_check["'".$sheet_name."'"]) ? $list_data_check["'".$sheet_name."'"] : $list_data_check[$sheet_name];
		var_dump($arr_fields,$data_check);die;
		$arr_field_header = [];
		foreach ($arr_fields as $key => $val) {
			if(!empty($val)) {
				if($val == "link_smartchip") {
					$arr_field_header[$val] = "link-smartchip";
				}else{
					$arr_field_header[$val] = !empty($data_check[$key]) ? $clsISO->replaceSpace($data_check[$key]) : "";
				}				
			}
		}	
		$clsISO->print_pre($arr_field_header);die;
		$header = $arr_check_multi = [];
		foreach ($rows as $key => $row) {
			$isHidden = 0;
			if (isset($rowMetadata[$key])) {
				$isHidden = $rowMetadata[$key]->getHiddenByUser() || $rowMetadata[$key]->getHiddenByFilter();
			}
			if (!$isHidden) {
				$cells = $row->getValues() ?: [];  
				if ($this->checkHeader($cells)) {
					$data_header = $arr_check_merge_header = [];
					$header_index = $key;
					foreach ($cells as $key_cell => $cell) {	
						$value = trim($cell->getFormattedValue());
						if(!empty($value)) {
							$slug_value = $clsISO->replaceSpace($value);
							$field = array_search($slug_value, $arr_field_header);								
							$colspan = $this->getColspan($key, $key_cell, $merges);
							if($colspan > 1) {
								$arr_check_multi[$key_cell] = [
									"colspan"	=>	$colspan,
									"val"	=>	$value,
								];
//								$data_header[$key_cell]
							}
						}	
						
						if(!empty ($field) && !$clsISO->checkItemInArray($field,$data_header)) {
							$data_header[$key_cell] = $field;
						}	
					}
					
					if(array_search("ms_code", $data_header)) {
						$header[$key] = $data_header; 						
					}					
				}else if(!empty($arr_check_multi) && ($header_index == ($key - 1))) {
					foreach ($cells as $key_cell => $cell) {
						if(!empty($arr_check_multi[$key_cell]) && $key_cell <= $arr_check_multi[$key_cell]["colspan"]) {							
//							$clsISO->print_pre($data_header[$key_cell]);die;
							$flag = $clsISO->replaceSpace($arr_check_multi[$key_cell]["value"]);
						}
						$value = trim($cell->getFormattedValue());
						if(!empty($value) && !empty($flag)) {
							$slug_value = $flag."|".$clsISO->replaceSpace($value);
							$data_header[$key_cell] = $slug_value;
						}	
					}
					unset($arr_check_multi);
				}
			}		
			
		}
		$clsISO->print_pre($data_header);die;
		die;
		return $header;
	}
	function getHeader($rows,$rowMetadata,$sheet_name,$column_data,$list_data_check,$colMetadata) {
		global $clsISO,$profile_id;
		$arr_fields = !empty($column_data["'".$sheet_name."'"]) ? $column_data["'".$sheet_name."'"] : $column_data[$sheet_name];
		$data_check = !empty($list_data_check["'".$sheet_name."'"]) ? $list_data_check["'".$sheet_name."'"] : $list_data_check[$sheet_name];
		$arr_field_header = [];
		$key_smartchip = $key_ptg = "";
		foreach ($arr_fields as $key => $val) {
			if(!empty($val)) {
				if($val == "link_smartchip") {
					$arr_field_header[$val] = "link-smartchip";
					$key_smartchip = $key;
				}else{
					if($val == "price_sheet_link"){
						$key_ptg = $key;
					}
					$arr_field_header[$val] = !empty($data_check[$key]) ? $clsISO->replaceSpace($data_check[$key]) : "";
				}				
			}
		}	
		$header = [];
		foreach ($rows as $key => $row) {
			$isHidden = 0;
			if (isset($rowMetadata[$key])) {
				$isHidden = $rowMetadata[$key]->getHiddenByUser() || $rowMetadata[$key]->getHiddenByFilter();
			}
			if (!$isHidden) {
				$cells = $row->getValues() ?: []; 
				if ($this->checkHeader($cells)) {
					$check_link_smartchip = 0;
					$data_header = [];
					foreach ($cells as $key_cell => $cell) {	
						$value = trim($cell->getFormattedValue());
						if(!empty($value)) {
							$slug_value = $clsISO->replaceSpace($value);
							$field = array_search($slug_value, $arr_field_header);	
						}	
						if(!empty ($field) && ( !$clsISO->checkItemInArray($field,$data_header) || $field == "ms_code") && !$colMetadata[$key_cell]->getHiddenByUser()) {
							$data_header[$key_cell] = $field;
							unset($field);
						}
																		
						if($field == "link_smartchip") {
							$check_link_smartchip = 1;
						}
					}
					if(!empty($data_header)) {						
						if($key_smartchip != "" && !isset($data_header[$key_smartchip])) {
							$data_header[$key_smartchip] = "link_smartchip";
						}					
						if($key_ptg != "" && !isset($data_header[$key_ptg])) {
							$data_header[$key_ptg] = "price_sheet_link";
						}
						$header[$key] = $data_header; 						
					}
					
				}
			}		
			
		}
		return $header;
	}
	/*===========End Verrsion 2===========*/
	function getArrNumberString($arr){
		foreach ($arr as $key => $val) {
			if((int)$val > 0){
				if(is_numeric($val)) {
					if((int)$val < 10){
						$floor = "0".ltrim($val,"0");
					}else{
						$floor = $val;
					}
				}elseif((int)$val < 10){
					$floor = "0".ltrim($val,"0");
				}else{
					$floor = $val;
				}
			}else{
				$floor = "";
			}
			$arr[$key] = $floor;
		}
		return $arr;
	}
	
	function checkHeaderPoint($cells,$key="Căn"){
		global $clsISO;
		foreach ($cells as $cell) {
			$value = $cell->getFormattedValue();
			$value = $clsISO->replaceSpace($value);
			$slug_key = $clsISO->replaceSpace($key);
			if(stripos($value, $slug_key) !== false) {
				return true;
				break;
			}
		}
		return false;
	}
	function getHeaderPoint($rows,$rowMetadata,$sheet_name,$column_data) {
		global $clsISO;
		$arr_fields = !empty($column_data["'".$sheet_name."'"]) ? $column_data["'".$sheet_name."'"] : $column_data[$sheet_name];
		$data_check = !empty($list_data_check["'".$sheet_name."'"]) ? $list_data_check["'".$sheet_name."'"] : $list_data_check[$sheet_name];
		$arr_field_header = [];
		foreach ($arr_fields as $key => $val) {
			if(!empty($val)) {
				$arr_field_header[$val] = !empty($data_check[$key]) ? $clsISO->replaceSpace($data_check[$key]) : "";			
			}
		}	
		$header = [];
		foreach ($rows as $key => $row) {
			$isHidden = 0;
			if (isset($rowMetadata[$key])) {
				$isHidden = $rowMetadata[$key]->getHiddenByUser() || $rowMetadata[$key]->getHiddenByFilter();
			}
			if (!$isHidden) {
				$cells = $row->getValues() ?: []; 
				if ($this->checkHeaderPoint($cells,"Căn")) {
					$check_link_smartchip = 0;
					$data_header = [];
					foreach ($cells as $key_cell => $cell) {	
						$value = trim($cell->getFormattedValue());
						if(!empty($value)) {							
							$data_header[$key_cell] = $value;
						}
					}
					if(!empty($data_header)) {
						$header[$key] = $data_header; 						
					}
					
				}
			}		
			
		}
		return $header;
	}
	function getDataNew($spreadsheetId, $ranges, $target_id, $agency_id,$stock_type) {
		global $core, $dbconn, $clsISO,$profile_id;
		$clsStock = new StockCrawl();		
		$clsProperty = new Property();	
		$clsLogCrawl = new LogCrawl();
		$oneAgency = $clsProperty->getOne($agency_id,"more_information");
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_crawls = !empty($more_information["block_crawl"]) ? $more_information["block_crawl"] : [];
		$block_crawl = $block_crawls[$target_id];
		$is_stock_point = $block_crawl["is_stock_point"];
		$arr_stock_point = $clsISO->getArrayByTextSlash($is_stock_point);
		$ranges_stock_point = $ranges_not_stock_point = [];
		foreach($ranges as $key => $range) {
			if(!empty($arr_stock_point[$key])) {
				$ranges_stock_point[] = $range;
			}else{
				$ranges_not_stock_point[] = $range;
			}
		}
		$res["result"] = false;
		$tblData = array();
		$success = 0;
		$spreadsheetId_crawl = $spreadsheetId;
		if(!empty($ranges_stock_point)) {	
			$arr_code_floor = !empty($more_information["arr_code_floor"]) ? $more_information["arr_code_floor"] : array();
			$arr_row_codes = !empty($arr_code_floor["arr_row_code"][$target_id]) ? $arr_code_floor["arr_row_code"][$target_id] : array();
			$arr_col_floors = !empty($arr_code_floor["arr_col_floor"][$target_id]) ? $arr_code_floor["arr_col_floor"][$target_id] : array();
			$code_floor_indexs = !empty($arr_code_floor["code_floor_index"][$target_id]) ? $arr_code_floor["code_floor_index"][$target_id] : array();	
			#
			$arr_building = $color_dq = [];
			$lstBuilding = $clsProperty->getAll("`for_id`='{$target_id}' AND `property_type`='_BUILDING'");
			foreach ($lstBuilding as $key => $val) {
				$more_information_building = $clsISO->to_array_json($val["more_information"]);
				$val['more_information'] = $more_information_building;
				$arr_building[$val[$clsProperty->pkey]] = $val;
			}
			#
			
			$color_sold = array("#FF0000","#EA4335");
			if($agency_id == 10881) {
				$color_dq = array("#FFFF00");
			}
			
			if(!empty($arr_row_codes) && !empty($arr_col_floors) && !empty($code_floor_indexs)) { 
				$dataResponse = $this->getSpreadsheetData($spreadsheetId, $stock_point,$target_id, $agency_id,$stock_type,0);
				$spreadsheetId_crawl = $dataResponse["spreadsheetId"];
				$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
				
				$tblHidden = array();
				if(!empty($response)) {
					foreach ($response as $sheet) {
						$title = $sheet->getProperties()->getTitle();
						$lst_row_code = $arr_row_codes["'".$title."'"] ? $arr_row_codes["'".$title."'"] : $arr_row_codes[$title];
						$lst_col_floor = $arr_col_floors["'".$title."'"] ? $arr_col_floors["'".$title."'"] : $arr_col_floors[$title];
						$lst_code_floor_index = $code_floor_indexs["'".$title."'"] ? $code_floor_indexs["'".$title."'"] : $code_floor_indexs[$title];
						if(!empty($lst_row_code) && !empty($lst_row_code)) {
							foreach ($lst_row_code as $building_id => $row_code) {
								#
								$arr_col_floor = $lst_col_floor[$building_id];
								$arr_col_floor = $this->getArrNumberString($arr_col_floor);
								#
								$arr_code_floor_index = $lst_code_floor_index[$building_id];
								$floor_index = $arr_code_floor_index["floor_index"];
								$code_index = $arr_code_floor_index["code_index"];
								$oneBuilding = $arr_building[$building_id];
								if(!empty($oneBuilding)) {
									$more_build = $oneBuilding["more_information"];
									$stock_template = $more_build["stock_template"];
									$building_code = $oneBuilding["property_code"];
									$stock_template = str_replace("[MaToa]",$building_code,$stock_template);
									
									foreach ($sheet->getData() as $data) {
										$rowData = $data->getRowData();
										$rowMetadata  = $data->getRowMetadata();
										//tiêu đề bảng
										$header = $this->getHeaderPoint($rowData,$rowMetadata,$title,$row_code);
										$arr_row_code = array();
										if(!empty($header)) {
											foreach ($rowData as  $i => $row) {
												if(isset($header[$i])) {
													$arr_row_code = $this->getArrNumberString($header[$i]);
												}
												if(!empty($arr_row_code)) {
													$isHidden  = 0;
													if (isset($rowMetadata[$i])) {
														$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
													}
													$floor = $arr_col_floor[$i];
													# Nếu hàng ẩn thì bỏ qua
													if (!$isHidden && !empty($floor)) {
														$data_row = array();
														foreach ($row->getValues() as $key => $cell) {
															if($key < $floor_index) {
																continue;
															}
															$is_sold = 0;
															$value = $cell->getFormattedValue();
															if(!empty($arr_row_code[$key]) && !empty($floor) && !empty($value)) {
																$ms_code = str_replace("[Tang]",$floor,$stock_template);
																$ms_code = str_replace("[CanHo]",$arr_row_code[$key],$ms_code);
																# Lấy backgroundColor của ô nếu có
																$bgColor = null;
																if ($cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
																	$color = $cell->getEffectiveFormat()->getBackgroundColor();
																	$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
																	$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
																	$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
																	$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
																	$bgColor = strtoupper($bgColor);
																}
																$data_row["bgColor"] = $bgColor;	
																# end background
																if($clsISO->replaceSpace($value) == "da-ban" || in_array($bgColor,$color_sold) || (!empty($color_dq) && !in_array($bgColor,$color_dq))) {
																	$is_sold = 1;
																	$data_row["bgColor"] = $bgColor;
																}
																if(empty($is_sold)) {
																	$tblData[] = [
																		"ms_code"	=>	$ms_code,
																		"total_price_vat"	=>	$value,
																	];
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					$success = 1;
				}
			}
		}
		if(!empty($ranges_not_stock_point)){
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
			$block_number_check = !empty($more_information["block_number_check"]) ? $more_information["block_number_check"] : array();;
			$arr_number_check = $block_number_check[$target_id]["number_check"];
			$list_data_check = $block_number_check[$target_id]["list_data_check"];
			$arr_column_data = array();
			if(file_exists($cachedFile)){
				$arr_column_data = $this->decoder->decodeFile($cachedFile);			
			}	
			$column_data = !empty($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();
			$color_sold = !empty($value['color_sold']) ? $value['color_sold'] : array("#FF0000","#EA4335");
			if($agency_id == 284) $color_sold[] = "#A5A5A5"; //đông đô
			if($agency_id == 318) $color_sold[] = "#8E7CC3"; //Tân Long
	//		if($agency_id == 265) $color_sold[] = "#FFF2CC"; //Trường Phát
			if($agency_id == 239) $color_sold[] = "#EA4335"; //Đất Việt
			if($agency_id == 8801) $color_sold[] = "#CC0000"; //Elite Captital
			if($agency_id == 280 && $target_id == 10177) $color_sold[] = "#980000"; //EH-ATD			
			if($agency_id == 10894 && $target_id == 10684) $color_sold[] = "#FFFF00"; // Global Property			
			if($agency_id == 10897 && $target_id == 11047) $color_sold[] = "#D9EAD3"; //Tiến Phát
			if($agency_id == 162 && $target_id == 11475) $color_sold[] = "#D9EAD3"; //VHS CXL
			if($agency_id == 162 && $target_id == 11475) $color_sold[] = "#FFF2CC"; //VHS CXL
			$color_break_default = array("#20124D");
			if($agency_id == 176 && $target_id == 11038) {
				$color_break_default = [];
			}
			if($agency_id == 262 && $target_id == 11043) {
				$color_break_default[] = "#FFF2CC";
			}
			$color_break = (!empty($value['is_color_break']) && !empty($value['color_break'])) ? $value['color_break'] : $color_break_default;
			//if($agency_id == 162) $color_break[] = "#FF0000"; //VHS
			if($agency_id == 10572 && $target_id == 1349) $color_break[] = "#FF0000"; //VSL
			if($agency_id == 10897 && $target_id == 10684) $color_dq[] = "#F9CB9C"; //Tiến Phát
			
			#
			$lstBuilding = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$target_id}'",$clsProperty->pkey.",property_code,more_information");
			foreach ($lstBuilding as $key => $val) {
				$lstBuilding[$key]["more_information"] = $clsISO->to_array_json($val["more_information"]);
			}
			$tblHidden = array();
			if(!empty($column_data)) { 
				$dataResponse = $this->getSpreadsheetDataSmartChip($spreadsheetId, $ranges_not_stock_point,$target_id, $agency_id,$stock_type,0);				
						
				$spreadsheetId_crawl = $dataResponse["spreadsheetId"];
				$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
				$break_stock = 0;
				if(!empty($response)) {
					foreach ($response as $sheet) {	
						$title = $sheet->getProperties()->getTitle();	
						$arr_fields = !empty($column_data["'".$title."'"]) ? $column_data["'".$title."'"] : $column_data[$title];
						$number_check = !empty($arr_number_check["'".$title."'"]) ? $arr_number_check["'".$title."'"] : $arr_number_check[$title];
						$data_check = !empty($list_data_check["'".$title."'"]) ? $list_data_check["'".$title."'"] : $list_data_check[$title];
//						var_dump($title);
						foreach ($sheet->getData() as $data) {
							$rowData = $data->getRowData();
							$rowMetadata  = $data->getRowMetadata();
							$colMetadata = $data->getColumnMetadata();
							//tiêu đề bảng
							$header = $this->getHeader($rowData,$rowMetadata,$title,$column_data,$list_data_check,$colMetadata);
							if(!empty($header)) {
								$fields_table = array();
								$check_table_index= 1;
								foreach ($rowData as  $i => $row) {
									if(isset($header[$i])) {
										$fields_table = $header[$i];
										if(($agency_id == 10894 && $target_id == 10684) || ($agency_id == 10897 && $target_id == 10684) || ($agency_id == 162 && $target_id == 10684) || ($agency_id == 261 && $target_id == 10418)) {//bang dau tien doc quyen
											if(!empty($break_stock)) {
												$is_general = 1;
												break;
											}
											$break_stock = 1;
										}
										if(($agency_id == 263 && $target_id == 10570) || ($agency_id == 10550 && $target_id == 10570)) {//từ bang thứ 2 doc quyen
											if($check_table_index == 1) {
												$fields_table = [];
												++$check_table_index;
												continue;
											}
										}
									}
									if(!empty($fields_table)) {
										
										$is_sold = $is_general = $isHidden = 0;
										if (isset($rowMetadata[$i])) {
											$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
										}
										# Nếu hàng ẩn thì bỏ qua
										if (!$isHidden) {
											$data_row = array();
											$is_dq = 1;
											foreach ($row->getValues() as $key => $cell) {	
												$value = $cell->getFormattedValue();
												# Lấy backgroundColor của ô nếu có
												$bgColor = null;
												if ($cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
													$color = $cell->getEffectiveFormat()->getBackgroundColor();
													$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
													$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
													$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
													$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
													$bgColor = strtoupper($bgColor);
												}
												$data_row["bgColor"] = $bgColor;	
												# end background
												if((isset($fields_table[$key]) && ($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link")  && in_array($bgColor, $color_break)) || ($agency_id == 265 && $key == 0 && in_array($bgColor,$color_break)  /*Trường Phát*/)) {
													$is_general = 1;
													break;
												}
												#TH phan biet quy voi cot co chu doc quyen hoac dq
												if($clsISO->checkItemInArray("stock_dq",$fields_table)) {
													if($fields_table[$key] == "stock_dq" && ($clsISO->replaceSpace($value) != "doc-quyen" && $clsISO->replaceSpace($value) != "dq" && $clsISO->replaceSpace($value) != "nhom-quy-doc-quyen")) {
														$is_dq = 0;
														$data_row["bgColor"] = $bgColor;
														break;
													}										
												}#
												if($clsISO->checkItemInArray("status_id",$fields_table)) {
													if($fields_table[$key] == "status_id" && ($clsISO->replaceSpace($value) == "da-ban" || ($clsISO->replaceSpace($value) == "chung" && $agency_id == 1321 && $target_id == 11047))) {
														$is_sold = 1;
														$data_row["bgColor"] = $bgColor;
														break;
													}										
												}else {
													if(isset($fields_table[$key]) && ($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link") && in_array($bgColor,$color_sold)) {
														$is_sold = 1;
														$data_row["bgColor"] = $bgColor;
														break;
													}	
													if($agency_id == 318 && in_array($bgColor,$color_sold)){ //Tân Long
														$is_sold = 1;
														$data_row["bgColor"] = $bgColor;
														break;
													}
												}
												$hyperlink = null;
												if (($cell->getUserEnteredFormat() || $fields_table[$key] == "link_smartchip") && $cell->getHyperlink()) {
													$hyperlink = $cell->getHyperlink();
													$hyperlink = ($hyperlink) ? $hyperlink : "";
													
												}
												if(str_contains($clsISO->replaceSpace($value),'quy-can-chung') || str_contains($clsISO->replaceSpace($value),'quy-cheo') || str_contains($clsISO->replaceSpace($value),'tong-hop') || (str_contains($clsISO->replaceSpace($value),'quy-chung') && ($agency_id == 10904 || $agency_id == 10902) && $target_id ==11047)) {
													$is_general = 1;
													break;
												}
												if((!empty($fields_table[$key]) && ($fields_table[$key] == "code_link" || $fields_table[$key] == "ms_code")) && !empty($value) && ((strlen($value) > 3 && strlen($value) < 10 && $target_id == 10360) || $target_id != 10360)) {
													$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\_\-]+/u', '', addslashes(trim($value)));
													$ms_code = str_replace("TC","",$ms_code);
													$ms_code = str_replace(" ","",$ms_code);
													$ms_code = trim($ms_code,"x");
													$data_row["ms_code"] = $ms_code;
													if($fields_table[$key] == "code_link") {
														$data_row["price_sheet_link"] = $hyperlink;
													}													
													unset($ms_code);
												}else if(!empty($fields_table[$key]) && (empty($color_dq) || (in_array($bgColor,$color_dq))) && $fields_table[$key] != "ms_code") {
													$data_row[$fields_table[$key]] = (($fields_table[$key] == "price_sheet_link") || ($fields_table[$key] == "link_smartchip")) ? $hyperlink : $value;
													
												}
											}
											if(!empty($is_general)) {	
												break;
											}
											if(empty($data_row["ms_code"]) && !empty($data_row["building_id"]) && !empty($data_row["floor"]) && !empty($data_row["code"])){
												if(in_array($bgColor,$color_sold)) {
													$is_sold = 1;
													unset($data_row);
												}else{														
													$ms_code = $this->genMsCode($lstBuilding,$data_row["building_id"],$data_row["floor"],$data_row["code"]);
													$data_row["ms_code"] = $ms_code;
													unset($ms_code);
												}
											}
											# Lưu thông tin hàng	
											$data_sheet[$title][] = $data_row;
											if(empty($is_sold) && !empty($data_row["ms_code"]) && preg_match(REGEX_MS_CODE, $data_row["ms_code"]) && !empty($is_dq)) {									
												$tblData[] = $data_row;									
												/*$data_sheet[$title][] = $data_row;*/
											}
										}
									}								
								}
							}
						}
					}					
					$success = 1;
				}
			}
		}
		
		if($profile_id == 289 || $clsISO->_DEV()) {
//			echo $spreadsheetId_crawl; 
//			$clsISO->print_pre($data_sheet);die;
//			$clsISO->print_pre($tblData);die;
		}
		if(!empty($success)) {	
			$res = [
				"result"	=>	true,
				"spreadsheetId_crawl"	=>	$spreadsheetId_crawl,
				"tblData"	=>	$tblData,
				/*"data_sheet"	=>	$data_sheet,*/
			];
		}
		return $res;
	}
	function getDataAPI($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type) {
		global $core, $dbconn, $clsISO, $profile_id;
		$clsStock = new StockCrawl();       
		$clsProperty = new Property();  
		$clsLogCrawl = new LogCrawl();

		// -------------------------------------------------------------------------
		// 1. CẤU HÌNH MÀU — lấy từ nguồn DUY NHẤT CrawlConfig (dùng chung với CrawlLocal)
		// -------------------------------------------------------------------------
		$currentConfig = CrawlConfig::resolveColors($agency_id, $target_id);
		$color_sold  = $currentConfig['color_sold'];
		$color_break = $currentConfig['color_break'];
		$color_dq    = $currentConfig['color_dq'];

		// Closure helper dùng chung để chuyển đổi màu RGB từ Google API sang HEX
		$fnGetHexColor = function($cell) {
			if ($cell->getEffectiveFormat() && $cell->getEffectiveFormat()->getBackgroundColor()) {
				$color = $cell->getEffectiveFormat()->getBackgroundColor();
				$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
				$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
				$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
				return strtoupper(sprintf("#%02x%02x%02x", round($red), round($green), round($blue)));
			}
			return null;
		};

		// -------------------------------------------------------------------------
		// 2. KHỞI TẠO VÀ PHÂN TÁCH RANGES
		// -------------------------------------------------------------------------
		$oneAgency = $clsProperty->getOne($agency_id, "more_information");
		$more_information = $clsISO->to_array_json($oneAgency['more_information'] ?? '');
		$block_crawl = $more_information["block_crawl"][$target_id] ?? [];
		$is_stock_point = $block_crawl["is_stock_point"] ?? '';
		$arr_stock_point = $clsISO->getArrayByTextSlash($is_stock_point);

		$ranges_stock_point = [];
		$ranges_not_stock_point = [];
		foreach ($ranges as $key => $range) {
			if (!empty($arr_stock_point[$key])) {
				$ranges_stock_point[] = $range;
			} else {
				$ranges_not_stock_point[] = $range;
			}
		}

		$res["result"] = false;
		$tblData = [];
		$success = 0;
		$spreadsheetId_crawl = $spreadsheetId;

		// -------------------------------------------------------------------------
		// 3. XỬ LÝ RANGES_STOCK_POINT
		// -------------------------------------------------------------------------
		if (!empty($ranges_stock_point)) {  
			$arr_code_floor = $more_information["arr_code_floor"] ?? [];
			$arr_row_codes = $arr_code_floor["arr_row_code"][$target_id] ?? [];
			$arr_col_floors = $arr_code_floor["arr_col_floor"][$target_id] ?? [];
			$code_floor_indexs = $arr_code_floor["code_floor_index"][$target_id] ?? [];   

			$arr_building = [];
			$lstBuilding = $clsProperty->getAll("`for_id`='{$target_id}' AND `property_type`='_BUILDING'");
			foreach ($lstBuilding as $val) {
				$val['more_information'] = $clsISO->to_array_json($val["more_information"] ?? '');
				$arr_building[$val[$clsProperty->pkey]] = $val;
			}

			if (!empty($arr_row_codes) && !empty($arr_col_floors) && !empty($code_floor_indexs)) { 
				// Fix lỗi biến $stock_point chưa định nghĩa bằng cách fallback về $is_stock_point
				$stock_point_param = $stock_point ?? $is_stock_point;
				$dataResponse = $this->getSpreadsheetData($spreadsheetId, $stock_point_param, $target_id, $agency_id, $stock_type, 0);
				$spreadsheetId_crawl = $dataResponse["spreadsheetId"] ?? $spreadsheetId;
				$response = $dataResponse["response"] ?? 0;   

				if (!empty($response)) {
					foreach ($response as $sheet) {
						$title = $sheet->getProperties()->getTitle();
						$lst_row_code = $arr_row_codes["'".$title."'"] ?? $arr_row_codes[$title] ?? null;
						$lst_col_floor = $arr_col_floors["'".$title."'"] ?? $arr_col_floors[$title] ?? null;
						$lst_code_floor_index = $code_floor_indexs["'".$title."'"] ?? $code_floor_indexs[$title] ?? null;

						if (empty($lst_row_code) || empty($lst_col_floor) || empty($lst_code_floor_index)) continue;

						foreach ($lst_row_code as $building_id => $row_code) {
							$arr_col_floor = $this->getArrNumberString($lst_col_floor[$building_id] ?? '');
							$arr_code_floor_index = $lst_code_floor_index[$building_id] ?? [];
							$floor_index = $arr_code_floor_index["floor_index"] ?? 0;

							$oneBuilding = $arr_building[$building_id] ?? null;
							if (empty($oneBuilding)) continue;

							$stock_template = $oneBuilding["more_information"]["stock_template"] ?? '';
							$building_code = $oneBuilding["property_code"] ?? '';
							$stock_template = str_replace("[MaToa]", $building_code, $stock_template);

							foreach ($sheet->getData() as $data) {
								$rowData = $data->getRowData();
								$rowMetadata = $data->getRowMetadata();
								$header = $this->getHeaderPoint($rowData, $rowMetadata, $title, $row_code);

								if (empty($header)) continue;
								$arr_row_code = [];

								foreach ($rowData as $i => $row) {
									if (isset($header[$i])) {
										$arr_row_code = $this->getArrNumberString($header[$i]);
									}
									if (empty($arr_row_code)) continue;

									$isHidden = false;
									if (isset($rowMetadata[$i])) {
										$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
									}

									$floor = $arr_col_floor[$i] ?? null;
									if ($isHidden || empty($floor)) continue;

									foreach ($row->getValues() as $key => $cell) {
										if ($key < $floor_index) continue;

										$value = $cell->getFormattedValue();
										if (!empty($arr_row_code[$key]) && !empty($value)) {
											$ms_code = str_replace("[Tang]", $floor, $stock_template);
											$ms_code = str_replace("[CanHo]", $arr_row_code[$key], $ms_code);

											$bgColor = $fnGetHexColor($cell);
											$is_sold = 0;

											if ($clsISO->replaceSpace($value) == "da-ban" || in_array($bgColor, $color_sold) || (!empty($color_dq) && !in_array($bgColor, $color_dq))) {
												$is_sold = 1;
											}
											if (empty($is_sold)) {
												$tblData[] = [
													"ms_code"         => $ms_code,
													"total_price_vat" => $value,
												];
											}
										}
									}
								}
							}
						}
					}
					$success = 1;
				}
			}
		}

		// -------------------------------------------------------------------------
		// 4. XỬ LÝ RANGES_NOT_STOCK_POINT
		// -------------------------------------------------------------------------
		if (!empty($ranges_not_stock_point)) {
			$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highfloor.json';
			$block_number_check = $more_information["block_number_check"] ?? [];
			$arr_number_check = $block_number_check[$target_id]["number_check"] ?? [];
			$list_data_check = $block_number_check[$target_id]["list_data_check"] ?? [];

			$arr_column_data = file_exists($cachedFile) ? $this->decoder->decodeFile($cachedFile) : [];
			$column_data = $arr_column_data[$agency_id][$target_id] ?? [];

			$lstBuilding = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$target_id}'", $clsProperty->pkey.",property_code,more_information");
			foreach ($lstBuilding as $key => $val) {
				$lstBuilding[$key]["more_information"] = $clsISO->to_array_json($val["more_information"] ?? '');
			}

			if (!empty($column_data)) { 
				$dataResponse = $this->getSpreadsheetDataSmartChipNew($spreadsheetId, $ranges_not_stock_point, $target_id, $agency_id, $stock_type, 0);             
				$spreadsheetId_crawl = $dataResponse["spreadsheetId"] ?? $spreadsheetId;
				$response = $dataResponse["response"] ?? 0;   
				$break_stock = 0;

				if (!empty($response)) {
					foreach ($response as $sheet) { 
						$title = $sheet->getProperties()->getTitle();   
						$fields_table_config = $column_data["'".$title."'"] ?? $column_data[$title] ?? [];

						foreach ($sheet->getData() as $data) {
							$rowData = $data->getRowData();
							$rowMetadata = $data->getRowMetadata();
							$colMetadata = $data->getColumnMetadata();
							$header = $this->getHeader($rowData, $rowMetadata, $title, $column_data, $list_data_check, $colMetadata);

							if (empty($header)) continue;
							$fields_table = [];
							$check_table_index = 1;

							foreach ($rowData as $i => $row) {
								if (isset($header[$i])) {
									$fields_table = $header[$i];
									if (in_array($target_id, [10684, 10418]) && in_array($agency_id, [10894, 10897, 162, 261])) {
										if (!empty($break_stock)) {
											$is_general = 1;
											break;
										}
										$break_stock = 1;
									}
									if ($check_table_index == 1 && in_array($target_id, [10570]) && in_array($agency_id, [263, 10550])) {
										$fields_table = [];
										++$check_table_index;
										continue;
									}
								}
								if (empty($fields_table)) continue;

								$is_sold = $is_general = $isHidden = 0;
								if (isset($rowMetadata[$i])) {
									$isHidden = $rowMetadata[$i]->getHiddenByUser() || $rowMetadata[$i]->getHiddenByFilter();
								}
								if ($isHidden) continue;

								$data_row = [];
								$is_dq = 1;

								foreach ($row->getValues() as $key => $cell) {  
									$value = $cell->getFormattedValue();
									$bgColor = $fnGetHexColor($cell);
									$data_row["bgColor"] = $bgColor;    

									if (isset($fields_table[$key]) && ($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link") && in_array($bgColor, $color_break)) {
										$is_general = 1;
										break;
									}
									if ($agency_id == 265 && $key == 0 && in_array($bgColor, $color_break)) {
										$is_general = 1;
										break;
									}

									if ($clsISO->checkItemInArray("stock_dq", $fields_table)) {
										if ($fields_table[$key] == "stock_dq" && !in_array($clsISO->replaceSpace($value), ["doc-quyen", "dq", "nhom-quy-doc-quyen"])) {
											$is_dq = 0;
											$data_row["bgColor"] = $bgColor;
											break;
										}                                       
									}

									if ($clsISO->checkItemInArray("status_id", $fields_table)) {
										if ($fields_table[$key] == "status_id" && ($clsISO->replaceSpace($value) == "da-ban" || ($clsISO->replaceSpace($value) == "chung" && $agency_id == 1321 && $target_id == 11047))) {
											$is_sold = 1;
											$data_row["bgColor"] = $bgColor;
											break;
										}                                       
									} else {
										if (isset($fields_table[$key]) && ($fields_table[$key] == "ms_code" || $fields_table[$key] == "code_link") && in_array($bgColor, $color_sold)) {
											$is_sold = 1;
											$data_row["bgColor"] = $bgColor;
											break;
										}   
										if ($agency_id == 318 && in_array($bgColor, $color_sold)) {
											$is_sold = 1;
											$data_row["bgColor"] = $bgColor;
											break;
										}
									}

									$hyperlink = null;
									if (($cell->getUserEnteredFormat() || ($fields_table[$key] ?? '') == "link_smartchip") && $cell->getHyperlink()) {
										$hyperlink = $cell->getHyperlink() ?: "";
									}

									$cleanSpaceVal = $clsISO->replaceSpace($value);
									if (str_contains($cleanSpaceVal, 'quy-can-chung') || str_contains($cleanSpaceVal, 'quy-cheo') || str_contains($cleanSpaceVal, 'tong-hop') || (str_contains($cleanSpaceVal, 'quy-chung') && in_array($agency_id, [10904, 10902]) && $target_id == 11047)) {
										$is_general = 1;
										break;
									}

									if (!empty($fields_table[$key]) && ($fields_table[$key] == "code_link" || $fields_table[$key] == "ms_code") && !empty($value) && ((strlen($value) > 3 && strlen($value) < 10 && $target_id == 10360) || $target_id != 10360)) {

										// ---------------------------------------------------------
										// GIỮ NGUYÊN HOÀN TOÀN QUY TẮC PHÂN TÍCH MS_CODE THEO YÊU CẦU
										// ---------------------------------------------------------
										$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\_\-]+/u', '', addslashes(trim($value)));
										$ms_code = str_replace("TC", "", $ms_code);
										$ms_code = str_replace(" ", "", $ms_code);
										$ms_code = trim($ms_code, "x");
										$data_row["ms_code"] = $ms_code;

										if ($fields_table[$key] == "code_link") {
											$data_row["price_sheet_link"] = $hyperlink;
										}                                                   
										unset($ms_code);
									} else if (!empty($fields_table[$key]) && (empty($color_dq) || in_array($bgColor, $color_dq)) && $fields_table[$key] != "ms_code") {
										$data_row[$fields_table[$key]] = in_array($fields_table[$key], ["price_sheet_link", "link_smartchip"]) ? $hyperlink : $value;
									}
								}

								if (!empty($is_general)) break;

								if (empty($data_row["ms_code"]) && !empty($data_row["building_id"]) && !empty($data_row["floor"]) && !empty($data_row["code"])) {
									if (in_array($bgColor, $color_sold)) {
										$is_sold = 1;
										unset($data_row);
									} else {                                                        
										$ms_code = $this->genMsCode($lstBuilding, $data_row["building_id"], $data_row["floor"], $data_row["code"]);
										$data_row["ms_code"] = $ms_code;
										unset($ms_code);
									}
								}

								if (empty($is_sold) && !empty($data_row["ms_code"]) && preg_match(REGEX_MS_CODE, $data_row["ms_code"]) && !empty($is_dq)) {                                    
									$tblData[] = $data_row;                                     
								}
							}
						}
					}
					$success = 1;
				}
			}
		}
		if($clsISO->_DEV() || $profile_id == 289){
//			echo $spreadsheetId_crawl;
//			$clsISO->print_pre($tblData);die;
		}

		if (!empty($success)) { 
			$res = [
				"result"               => true,
				"spreadsheetId_crawl"  => $spreadsheetId_crawl,
				"tblData"              => $tblData,
			];
		}
		return $res;
	}
	function genMsCode($lstBuilding,$building_code,$floor,$code) {
		global $clsISO;
		$stock_template = "";
		foreach ($lstBuilding as $key => $val) {
			$more_information = $val["more_information"];
			$property_code = $val["property_code"];
//			var_dump($property_code);
			if(strpos(trim($building_code), $property_code) !== false) {
				$stock_template = $more_information["stock_template"];
				break;
			}
		}
		if(!empty($stock_template)) {
			$floor = $this->getCodeFloor($floor);
			$code = $this->getCodeFloor($code);
			$ms_code = str_replace('[MaToa]', $building_code, $stock_template);
			$ms_code = str_replace('[Tang]', $floor, $ms_code);
			$ms_code = str_replace('[CanHo]', $code, $ms_code);	
			return $ms_code;
		}
		return "";
		
	}
	function getCodeFloor($str){
		global $clsISO;
		$number = (int)$str;
		if($number > 0) {
			$txt_code = preg_replace('/[^A-Za-z]/', '', $str);			
			$code = ($number < 10) ? $code = "0".$number.$txt_code : $code = $number.$txt_code;
			return $code;
		}
		return "";
	}
	function getDataImage($data){
		global $clsISO;
		$client = new Google_Client();
		try {
			$client = new GuzzleHttp\Client();
			$response = $client->post(
				'https://vision.googleapis.com/v1/images:annotate?key=AIzaSyA_a2ACm73tqAf0aV9wpi9iKC1YQFVHo04',
				['json' => $data]
			);
			$clsISO->print_pre($response);die;
			return $result;
		} catch (Exception $e) {
			$msg_error = $e->getMessage();	
			$clsISO->print_pre($msg_error);die;
		}
		
	}
}
?>