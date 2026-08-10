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
class CrawlLowFloor{
	function __construct(){
		#- Reuired Library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');				
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		$this->cachedFile = DIR_CACHE_JSON.'/crawl/crawl_lowfloor.json';
		#- Init Client
		$this->client = new Google_Client();
		$this->client->setClientId(GOOGLE_CLIENT_ID_DEV);
		$this->client->setClientSecret(GOOGLE_CLIENT_SECRET_DEV);
		$this->client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN_DEV);
		$this->client->setScopes([Google_Service_Drive::DRIVE]);
		$this->client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	}
	function getSpreadsheetData($spreadsheetId, $ranges,$is_copy=0){
		global $core, $dbconn, $clsISO;
		
		$service = new Google_Service_Sheets($this->client);
		try {
//			var_dump($spreadsheetId, $ranges);die;
			$arr_ranges = [];
			foreach ($ranges as $range) {
				if(!empty($is_copy)) {
					$range = str_replace("/","",$range);
				}
				$arr_ranges[] = $range."!A1:BC500";
			}
//			$response = $service->spreadsheets_values->batchGet($spreadsheetId, ['ranges' => $arr_ranges]);
			$params = [
				'ranges' => $arr_ranges,
				'includeGridData' => true,
//				'fields'=> 'sheets.properties.title,sheets.data.rowData.values(effectiveValue,effectiveFormat(backgroundColor))'

			];
			// Lấy thông tin bảng tính (bao gồm tất cả các sheet)
			$response = $service->spreadsheets->get($spreadsheetId, $params);
			return $response->getSheets();
		} catch (Exception $e) {
			$msg_error = $e->getMessage();
//			echo $msg_error;die;		
			if(json_decode($msg_error)->error->status == "INVALID_ARGUMENT"){
				return 0;
			}
			$spreadsheetId_Old = $spreadsheetId;
			$spreadsheetId = $this->copySpreadsheet($spreadsheetId_Old);
			if(!empty($spreadsheetId)){
				return $this->getSpreadsheetData($spreadsheetId, $ranges,1);
			}		
			return 0;			
		}
		return 0;
	}
	function copySpreadsheet($spreadsheetId){	
		global $clsISO;
		$arrCache = array();
		if(@file_exists($this->cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arrCache = $decoder->decodeFile($this->cachedFile);
			if(isset($arrCache[$spreadsheetId])) {
				$cache = $arrCache[$spreadsheetId];
//				echo $cache['spreadsheetId'];die;
				return $cache['spreadsheetId'];
				if($cache['reg_date'] > (time() - 14400)) {
					return $cache['spreadsheetId'];
				}
			}
		}
		
		$service = new Google_Service_Drive($this->client);
		try {
			// Đọc file Excel từ Google Drive
			$response = $service->files->get($spreadsheetId, array(
				'supportsAllDrives' => 'true'
			));
			// Chuyển đổi file Excel thành Google Sheets
			$fileMetadata = new \Google_Service_Drive_DriveFile(array(
				'name' => sprintf('%s', date('d-m-y h:i:s'))
			));
			$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID_DEV]);
			$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
			$convertedFile = $service->files->copy($spreadsheetId, $fileMetadata, array(
				'supportsAllDrives' => 'true'
			));
			$spreadsheetId_new = $convertedFile->getId();
			$arrCache[$spreadsheetId] = [
				'spreadsheetId' => $spreadsheetId_new,
				'reg_date'		=>	time()
			];
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($arrCache, $this->cachedFile);
			return $spreadsheetId_new;
		} catch (Exception $e) {
			$msg_error = $e->getMessage();
//			$clsISO->print_pre($msg_error);die;
			return 0;
		}
	}	
	function getDataOld($spreadsheetId, $ranges, $project_id, $agency_id) {
		global $core, $dbconn, $clsISO;
		$service = new Google_Service_Drive($this->client);
		$clsStock = new StockCrawl();
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		$arr_column_data = array();
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arr_column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$project_id]) ? $arr_column_data[$agency_id][$project_id] : array();
//		return $column_data;
		$tblData = array();
		if(!empty($column_data)) {
			$dataResponse = $this->getSpreadsheetData($spreadsheetId, $ranges);	
			$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;			
			// Xóa file sao chép sau khi đọc xong
			if(!empty($dataResponse["is_copy"])) {
//				$service->files->delete($spreadsheetId);
			}
			$dataSheet = [];
			if(!empty($response)) {
				foreach ($response as $valueRange) {
					// Lấy tên sheet từ chuỗi range (ví dụ: "Sheet1!A:Z")
					$range = $valueRange->getRange();
//					echo $range ;die;
					$parts = explode('!', $range);
					$sheetName = $parts[0];

					// Lấy dữ liệu của sheet đó
					$dataSheet[$sheetName] = $valueRange->getValues();
				}
//				return $dataSheet;
				if(!empty($dataSheet)) {
					$sheetName = "";
					foreach ($dataSheet as $key => $val) {
						$sheetName = $key;
						$arr_fields = $column_data[$sheetName];
						foreach ($val as $row) {
							$data_row = array();
							foreach($row as $k => $cell) {
								$is_stop= false;
								if(!empty($arr_fields[$k])) {
									$data_row[$arr_fields[$k]] = $cell;
								}
							}
//							$tblData[] = $data_row;	
							if(!empty($data_row["ms_code"])) {
								$tblData[] = $data_row;	
							}
						}
					}
				}
			}
		}		
		return $tblData;
	}
	function getData($spreadsheetId, $ranges, $project_id, $agency_id,$value="") {
		global $core, $dbconn, $clsISO;
		$service = new Google_Service_Drive($this->client);
		$clsStock = new StockCrawl();
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		$arr_column_data = array();
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arr_column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$project_id]) ? $arr_column_data[$agency_id][$project_id] : array();
//				return $column_data;
		$color_sold = !empty($value['color_sold']) ? $value['color_sold'] : array();
		$color_dq = (!empty($value['is_color_dq']) && !empty($value['color_dq'])) ? $value['color_dq'] : array();
		$color_break = (!empty($value['is_color_break']) && !empty($value['color_break'])) ? $value['color_break'] : array();
		$tblData = $dataPerSheet = array();
		if(!empty($column_data)) {
			foreach ($ranges as $key => $range) {		
				// Xử lý dữ liệu của sheet
				/*if(strpos($range," ")) {
					$arr_fields = $column_data["'".$range."'"];
				}else{
					$arr_fields = $column_data[$range];
				}*/
				$arr_fields = $column_data["'".$range."'"];
//				return $arr_fields;
				$response = $this->getSpreadsheetData($spreadsheetId, [$range]);			
//				var_dump($spreadsheetId, $range);die;
				foreach ($response as $sheet) {
					$title = $sheet->getProperties()->getTitle();
					$dataPerSheet[$title] = [];
					$data = $sheet->getData();

					if (!empty($data)) {
						$rowData = $data[0]->getRowData();
						$rowMetadata = $data[0]->getRowMetadata(); // Lấy thông tin metadata của hàng

						if ($rowData) {
							$is_general = 0;
							foreach ($rowData as $rowIndex => $row) {
								$is_sold = 0;
								$data_row = array();
								// Kiểm tra nếu hàng bị ẩn, bỏ qua hàng đó
								if ($rowMetadata && isset($rowMetadata[$rowIndex])) {
									if ($rowMetadata[$rowIndex]->getHiddenByUser()) {
										continue;
									}
								}

								$rowValues = [];
								$cellColors = [];

								$cells = $row->getValues();
								$check = 1;
								if ($cells) {
									foreach ($cells as $k => $cell) {
										if($k == 1 && $agency_id == 159 && $cell == "Quỹ ĐQ") {
											$check = 0;
										}
										// Lấy backgroundColor của ô nếu có
										$bgColor = null;
										if (
											$cell->getEffectiveFormat() &&
											$cell->getEffectiveFormat()->getBackgroundColor()
										) {
											$color = $cell->getEffectiveFormat()->getBackgroundColor();
											$red   = ($color->getRed() !== null) ? $color->getRed() * 255 : 0;
											$green = ($color->getGreen() !== null) ? $color->getGreen() * 255 : 0;
											$blue  = ($color->getBlue() !== null) ? $color->getBlue() * 255 : 0;
											$bgColor = sprintf("#%02x%02x%02x", round($red), round($green), round($blue));
										}
										$cellColors[] = $bgColor;
										if(in_array($bgColor,$color_sold)) {
											$is_sold = 1;
										}
										if(in_array($bgColor,$color_break)) {
											$is_general = 1;
											break;
										}
										// Lấy giá trị của ô nếu có
//										$value = $cell->getEffectiveValue();
										$value = $cell->getFormattedValue();
										$hyperlink = null;
										if ($cell->getUserEnteredFormat() && $cell->getHyperlink()) {
											$hyperlink = $cell->getHyperlink();
										}	
										$rowValues[] = (!empty($arr_fields[$k]) && $arr_fields[$k] == "price_temporary_ns") ? $hyperlink : $value;
										if(!empty($arr_fields[$k]) && (empty($color_dq) || (in_array($bgColor,$color_dq)))) {
											$data_row[$arr_fields[$k]] = ($arr_fields[$k] == "price_temporary_ns") ? $hyperlink : $value;
										}										
									}
								}
								if(!empty($is_general)) {
									break;
								}
								// Lưu thông tin hàng
								if(empty($is_sold) && !empty($data_row["ms_code"]) && $check) {
									$tblData[] = $data_row;
								}
								
							}
						}
					}
					$dataPerSheet[$title] = $tblData;
				}

				// Nếu cần, bạn có thể xử lý kết quả của sheet hiện tại ngay tại đây
				// và giải phóng bộ nhớ trước khi xử lý sheet tiếp theo.
				// Ví dụ:
				// processSheetData($dataPerSheet[$sheetName]);
				// unset($dataPerSheet[$sheetName]);
			}
			$clsISO->print_pre($dataPerSheet);die;
		}		
		return $dataPerSheet;
	}
	
	
	function getSpreadsheetDataConfig($spreadsheetId, $ranges,$is_copy=0){
		global $core, $dbconn, $clsISO;
		
		$service = new Google_Service_Sheets($this->client);
		try {
			$arr_ranges = [];
			foreach ($ranges as $range) {
				$arr_ranges[] = $range . '!A:ZZ';
			}	
			$response = $service->spreadsheets_values->batchGet($spreadsheetId, ['ranges' => $arr_ranges]);
			return array(
				"response"	=>	$response->getValueRanges(),
				"is_copy"	=>	$is_copy
			);
		} catch (Exception $e) {
			$msg_error = $e->getMessage();
//			if(!empty($is_copy)) {
//				$clsISO->print_pre($msg_error);die;	
//			}	
			if(json_decode($msg_error)->error->status == "INVALID_ARGUMENT"){
				return 0;
			}
			$spreadsheetId_Old = $spreadsheetId;
			$spreadsheetId = $this->copySpreadsheet($spreadsheetId_Old);
			if(!empty($spreadsheetId)){
				return $this->getSpreadsheetDataConfig($spreadsheetId, $ranges,1);
			}		
			return 0;			
		}
		return 0;
	}
	function getDataConfigColumn($spreadsheetId, $ranges, $project_id, $agency_id) {
		global $core, $dbconn, $clsISO;
		$service = new Google_Service_Drive($this->client);
		$clsStock = new StockCrawl();
		$dataSheet = [];
		$cachedName = sprintf('project_%s_%s.json', $agency_id, $project_id);
		
		$cachedFile = DIR_CACHE_JSON.'/crawl/'.$cachedName;
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$dataSheet = $decoder->decodeFile($cachedFile);
//			@unlink($cachedFile);
		}else{
//			return 1;
			$dataResponse = $this->getSpreadsheetDataConfig($spreadsheetId, $ranges);	
			$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;			
			// Xóa file sao chép sau khi đọc xong
			if(!empty($dataResponse["is_copy"])) {
//				$service->files->delete($spreadsheetId);
			}
			if(!empty($response)) {
				foreach ($response as $valueRange) {
					// Lấy tên sheet từ chuỗi range (ví dụ: "Sheet1!A:Z")
					$range = $valueRange->getRange();
//					echo $range ;die;
					$parts = explode('!', $range);
					$sheetName = $parts[0];
					// Lấy dữ liệu của sheet đó
					$dataSheet[$sheetName] = $valueRange->getValues();
				}
			}
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($dataSheet, $cachedFile); 
		}	
				
		return $dataSheet;
	}
}
?>