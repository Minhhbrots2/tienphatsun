<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| CrawlLocal — bản đọc quỹ căn từ Google Sheet qua endpoint export xlsx ||
|| + thư viện PhpSpreadsheet (core/PhpSpreadsheet), thay cho Google      ||
|| Sheets API trong Crawl.php. Chỉ ĐỌC (read-only), không ghi xlsx.      ||
||                                                                       ||
|| Tương thích PHP 7.2: không dùng str_contains / typed properties /     ||
|| arrow fn / match. Dùng strpos(...)!==false thay str_contains.         ||
||                                                                       ||
|| Public API trùng chữ ký Crawl::getDataNew để thay thế trực tiếp:      ||
||   getDataNew($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type)||
|| Trả về cùng định dạng: ["result","spreadsheetId_crawl","tblData"].    ||
\*======================================================================*/
 
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CrawlLocal {

	/** Danh sách file tạm cần dọn sau khi parse xong */
	private $tmpFiles = array();

	function __construct() {
		// Chỉ nạp PhpSpreadsheet (không khởi tạo Google_Client ở đây để tránh
		// nạp chung 2 composer-autoloader; googleapiclient chỉ nạp khi fallback).
		require_once(DIR_INCLUDES.'/json_master/autoload.php');				
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		require_once(DIR_INCLUDES . '/PhpSpreadsheet/autoload.php');
		$this->encoder = new Webmozart\Json\JsonEncoder();
		$this->decoder = new Webmozart\Json\JsonDecoder();
	}

	/** Chốt an toàn: dọn file tạm còn sót nếu request kết thúc bất thường (Error/fatal không vào được catch). */
	function __destruct() {
		$this->cleanup();
	} 

	// =====================================================================
	// PUBLIC: cùng chữ ký Crawl::getDataNew
	// =====================================================================
	function getDataNew($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type) {
		global $clsISO,$profile_id;
		$clsProperty = new Property();

		$res = array("result" => false);
		$tblData = array();
		$success = 0; 
		$spreadsheetId_crawl = $spreadsheetId;

		// more_information của agency → tách ranges theo is_stock_point
		$oneAgency = $clsProperty->getOne($agency_id, "more_information");
		$more_information = $clsISO->to_array_json(isset($oneAgency['more_information']) ? $oneAgency['more_information'] : '');

		$block_crawl = isset($more_information["block_crawl"][$target_id]) ? $more_information["block_crawl"][$target_id] : array();
		$is_stock_point = isset($block_crawl["is_stock_point"]) ? $block_crawl["is_stock_point"] : '';
		$arr_stock_point = $clsISO->getArrayByTextSlash($is_stock_point);

		$ranges_stock_point = array();
		$ranges_not_stock_point = array();
		foreach ($ranges as $key => $range) {
			if (!empty($arr_stock_point[$key])) $ranges_stock_point[] = $range;
			else $ranges_not_stock_point[] = $range;
		}

		// Tải toàn bộ workbook 1 lần (export xlsx chứa mọi sheet)
		$wb = $this->loadWorkbook($spreadsheetId, $target_id, $agency_id, $stock_type, $ranges);
		if (empty($wb)) {
			// Không lấy được xlsx (sheet chia sẻ kiểu "Viewer không cho tải/in/sao chép" → Google
			// chặn export/copy/download 403; chỉ Sheets API đọc được). Fallback sang Crawl gốc.
			$clsCrawl = new Crawl();
			$res = $clsCrawl->getDataAPI($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type);
			return $res;
		}

		try {
			if (!empty($ranges_not_stock_point)) {
				$tblData = array_merge($tblData, $this->parseColumnConfig($wb, $ranges_not_stock_point, $target_id, $agency_id, $more_information));
				$success = 1;
			}
			if (!empty($ranges_stock_point)) {
				$tblData = array_merge($tblData, $this->parseStockPoint($wb, $ranges_stock_point, $target_id, $agency_id, $more_information));
				$success = 1;
			}
		} catch (Exception $e) {
			$this->cleanup($wb);
			return $res;
		}

		$this->cleanup($wb);
		if (!empty($success)) {
			$res = array(
				"result"              => true,
				"spreadsheetId_crawl" => $spreadsheetId_crawl,
				"tblData"             => $tblData,
			);
		}
		return $res;
	}

	// =====================================================================
	// NHÁNH 1: ranges_not_stock_point — dò header động + cấu hình cột
	// (port Crawl::getDataNew nhánh không stock-point)
	// =====================================================================
	private function parseColumnConfig($wb, $ranges, $target_id, $agency_id, $more_information) {
		global $clsISO,$profile_id;
		$clsProperty = new Property();
		$tblData = array();

		$cfg = $this->resolveColorConfig($agency_id, $target_id);
		$color_sold  = $cfg['color_sold'];
		$color_break = $cfg['color_break'];
		$color_dq    = $cfg['color_dq'];

		// Cấu hình cột + nhãn header kỳ vọng
		$cachedFile = DIR_CACHE_JSON . '/crawl/config_column_highfloor.json';
		$arr_column_data = file_exists($cachedFile) ? $this->decoder->decodeFile($cachedFile) : array();
		$column_data = isset($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();
		if (empty($column_data)) return $tblData;
		$block_number_check = isset($more_information["block_number_check"]) ? $more_information["block_number_check"] : array();
		$list_data_check = isset($block_number_check[$target_id]["list_data_check"]) ? $block_number_check[$target_id]["list_data_check"] : array();

		// Danh sách building để fallback genMsCode
		$lstBuilding = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$target_id}'", $clsProperty->pkey . ",property_code,more_information");
		foreach ($lstBuilding as $k => $v) {
			$lstBuilding[$k]["more_information"] = $clsISO->to_array_json(isset($v["more_information"]) ? $v["more_information"] : '');
		}

		// break_stock áp dụng XUYÊN SUỐT mọi sheet (khớp Crawl::getDataNew): với 4 combo
		// độc quyền bên dưới chỉ lấy bảng header ĐẦU TIÊN trên toàn workbook.
		$break_stock = 0;
		foreach ($ranges as $sheet_name) {
			$sheet = $this->findSheetByName($wb, $sheet_name);
			if (!$sheet) continue;
			$maxRow = $sheet->getHighestDataRow();
			$maxColIdx = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

			$header = $this->getHeader($sheet, $maxRow, $maxColIdx, $sheet_name, $column_data, $list_data_check);
			if (empty($header)) continue;
			$cfRules = $this->buildCfRules($sheet); // luật conditional-formatting (màu "đã bán" thường là CF)

			$fields_table = array();
			$check_table_index = 1;

			for ($r = 1; $r <= $maxRow; $r++) {
				if (isset($header[$r])) {
					$fields_table = $header[$r];
					// Bảng độc quyền đầu tiên: chỉ lấy 1 bảng rồi dừng sheet
					if (($agency_id == 10894 && $target_id == 10684) || ($agency_id == 10897 && $target_id == 10684)
						|| ($agency_id == 162 && $target_id == 10684) || ($agency_id == 261 && $target_id == 10418)) {
						if (!empty($break_stock)) break;
						$break_stock = 1;
					}
					// Bỏ bảng đầu, lấy từ bảng thứ 2 (độc quyền)
					if (($agency_id == 263 && $target_id == 10570) || ($agency_id == 10550 && $target_id == 10570)) {
						if ($check_table_index == 1) { $fields_table = array(); ++$check_table_index; continue; }
					}
				}
				if (empty($fields_table)) continue;
				if (!$sheet->getRowDimension($r)->getVisible()) continue;

				$cells = $this->readRow($sheet, $r, $maxColIdx);
				$data_row = array();
				$is_sold = 0; $is_general = 0; $is_dq = 1; $bgColorLast = null; $rowLink = null;

				foreach ($cells as $key => $c) {
					$value = $c['value'];
					$bgColor = $c['bg'];
					$cfHit = $this->cfBg($cfRules, $r, $key, $cells); // CF đè fill (màu "đã bán" thường là CF)
					if ($cfHit !== null) $bgColor = $cfHit;
					$bgColorLast = $bgColor;
					$data_row["bgColor"] = $bgColor;
					$field = isset($fields_table[$key]) ? $fields_table[$key] : null;

					// Màu break (ranh giới quỹ chung) trên cột mã / cột A của TP
					if (($field == "ms_code" || $field == "code_link") && in_array($bgColor, $color_break)) { $is_general = 1; break; }
					if ($agency_id == 265 && $key == 0 && in_array($bgColor, $color_break)) { $is_general = 1; break; }

					// Cột phân biệt độc quyền
					if ($clsISO->checkItemInArray("stock_dq", $fields_table) && $field == "stock_dq") {
						$sv = $clsISO->replaceSpace($value);
						if ($sv != "doc-quyen" && $sv != "dq" && $sv != "nhom-quy-doc-quyen") { $is_dq = 0; break; }
					}

					// Trạng thái đã bán
					if ($clsISO->checkItemInArray("status_id", $fields_table)) {
						if ($field == "status_id") {
							$sv = $clsISO->replaceSpace($value);
							if ($sv == "da-ban" || ($sv == "chung" && $agency_id == 1321 && $target_id == 11047)) { $is_sold = 1; break; }
						}
					} else {
						if (($field == "ms_code" || $field == "code_link") && in_array($bgColor, $color_sold)) { $is_sold = 1; break; }
						if ($agency_id == 318 && in_array($bgColor, $color_sold)) { $is_sold = 1; break; }
					}

					// Hyperlink / smartchip: đã đọc sẵn trong readRow (cùng lúc với value/màu).
					$hyperlink = isset($c['link']) ? $c['link'] : null;
					// Cột PTG/smartchip giữ link phiếu giá dưới dạng hyperlink native; mỗi dòng
					// chỉ có 1 hyperlink → nhớ lại để dùng nếu mapping field link không khớp config.
					if ($hyperlink !== null && $rowLink === null) $rowLink = $hyperlink;

					// Marker quỹ chung/quỹ chéo/tổng hợp → kết thúc bảng dữ liệu
					$sv = $clsISO->replaceSpace($value);
					if (strpos($sv, 'quy-can-chung') !== false || strpos($sv, 'quy-cheo') !== false || strpos($sv, 'tong-hop') !== false
						|| (strpos($sv, 'quy-chung') !== false && ($agency_id == 10904 || $agency_id == 10902) && $target_id == 11047)) {
						$is_general = 1; break;
					}

					if (!empty($field) && ($field == "code_link" || $field == "ms_code") && !empty($value)
						&& ((strlen($value) > 3 && strlen($value) < 10 && $target_id == 10360) || $target_id != 10360)) {
						$ms = (string)preg_replace('/[^\p{L}\p{N}\.\_\-]+/u', '', addslashes(trim($value)));
						$ms = str_replace("TC", "", $ms);
						$ms = str_replace(" ", "", $ms);
						$ms = trim($ms, "x");
						$data_row["ms_code"] = $ms;
						if ($field == "code_link") $data_row["price_sheet_link"] = $hyperlink;
					} else if (!empty($field) && (empty($color_dq) || in_array($bgColor, $color_dq)) && $field != "ms_code") {
						$data_row[$field] = ($field == "price_sheet_link") ? $hyperlink : $value;
					}
				}

				if (!empty($is_general)) break; // gặp ranh giới quỹ chung → dừng sheet

				// Fallback sinh ms_code từ building/floor/code
				if (empty($data_row["ms_code"]) && !empty($data_row["building_id"]) && !empty($data_row["floor"]) && !empty($data_row["code"])) {
					if (in_array($bgColorLast, $color_sold)) {
						$is_sold = 1;
					} else {
						$data_row["ms_code"] = $this->genMsCode($lstBuilding, $data_row["building_id"], $data_row["floor"], $data_row["code"]);
					}
				}

				// Link phiếu giá: nếu cột price_sheet_link chưa bắt được link, dùng hyperlink của dòng
				// (cột PTG/smartchip là ô duy nhất có hyperlink trong dòng).
				if (empty($data_row["price_sheet_link"]) && !empty($rowLink)) {
					$data_row["price_sheet_link"] = $rowLink;
				}

				// ===== DEBUG (xoá sau khi xong) =====
				/*if (!empty($data_row["ms_code"]) && preg_match(REGEX_MS_CODE, $data_row["ms_code"]) && empty($GLOBALS['__cl_dbg'])) {
					$GLOBALS['__cl_dbg'] = 1;
					echo "DBG sheet=$sheet_name r=$r maxColIdx=$maxColIdx\n";
					echo "rowLink=" . var_export($rowLink, true) . "\n";
					echo "fields_table="; print_r($fields_table);
					echo "links đọc được trong dòng:\n";
					foreach ($cells as $k => $cc) {
						if (!empty($cc['link'])) echo "  idx$k (" . Coordinate::stringFromColumnIndex($k + 1) . ") = " . $cc['link'] . "\n";
					}
					echo "data_row="; print_r($data_row);
					die;
				}*/
				// ===== END DEBUG =====

				if (empty($is_sold) && !empty($data_row["ms_code"]) && preg_match(REGEX_MS_CODE, $data_row["ms_code"]) && !empty($is_dq)) {
					$tblData[] = $data_row;
				}
				
				if($profile_id == 289){
//					$clsISO->print_pre($data_row);
				}
			}
		}
		if($profile_id == 289){
//			$clsISO->print_pre($tblData);die;
		}
		return $tblData;
	}

	// =====================================================================
	// NHÁNH 2: ranges_stock_point — sơ đồ mặt bằng (ma trận tầng × căn)
	// (port Crawl::getDataNew nhánh stock-point)
	// =====================================================================
	private function parseStockPoint($wb, $ranges, $target_id, $agency_id, $more_information) {
		global $clsISO;
		$clsProperty = new Property();
		$tblData = array();

		$arr_code_floor = isset($more_information["arr_code_floor"]) ? $more_information["arr_code_floor"] : array();
		$arr_row_codes = isset($arr_code_floor["arr_row_code"][$target_id]) ? $arr_code_floor["arr_row_code"][$target_id] : array();
		$arr_col_floors = isset($arr_code_floor["arr_col_floor"][$target_id]) ? $arr_code_floor["arr_col_floor"][$target_id] : array();
		$code_floor_indexs = isset($arr_code_floor["code_floor_index"][$target_id]) ? $arr_code_floor["code_floor_index"][$target_id] : array();
		if (empty($arr_row_codes) || empty($arr_col_floors) || empty($code_floor_indexs)) return $tblData;

		$color_sold = array("#FF0000", "#EA4335");
		$color_dq = ($agency_id == 10881) ? array("#FFFF00") : array();

		$arr_building = array();
		$lstBuilding = $clsProperty->getAll("`for_id`='{$target_id}' AND `property_type`='_BUILDING'");
		foreach ($lstBuilding as $v) {
			$v['more_information'] = $clsISO->to_array_json(isset($v["more_information"]) ? $v["more_information"] : '');
			$arr_building[$v[$clsProperty->pkey]] = $v;
		}

		foreach ($ranges as $sheet_name) {
			$sheet = $this->findSheetByName($wb, $sheet_name);
			if (!$sheet) continue;
			$maxRow = $sheet->getHighestDataRow();
			$maxColIdx = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

			$lst_row_code = $this->cfgBySheet($arr_row_codes, $sheet_name);
			$lst_col_floor = $this->cfgBySheet($arr_col_floors, $sheet_name);
			$lst_code_floor_index = $this->cfgBySheet($code_floor_indexs, $sheet_name);
			if (empty($lst_row_code) || empty($lst_col_floor) || empty($lst_code_floor_index)) continue;

			// Header điểm: lấy các dòng chứa mã căn theo cột (key = số dòng tuyệt đối)
			$header = $this->getHeaderPoint($sheet, $maxRow, $maxColIdx);
			if (empty($header)) continue;

			foreach ($lst_row_code as $building_id => $row_code) {
				$arr_col_floor = $this->getArrNumberString(isset($lst_col_floor[$building_id]) ? $lst_col_floor[$building_id] : array());
				$arr_code_floor_index = isset($lst_code_floor_index[$building_id]) ? $lst_code_floor_index[$building_id] : array();
				$floor_index = isset($arr_code_floor_index["floor_index"]) ? $arr_code_floor_index["floor_index"] : 0;

				$oneBuilding = isset($arr_building[$building_id]) ? $arr_building[$building_id] : null;
				if (empty($oneBuilding)) continue;

				$stock_template = isset($oneBuilding["more_information"]["stock_template"]) ? $oneBuilding["more_information"]["stock_template"] : '';
				$building_code = isset($oneBuilding["property_code"]) ? $oneBuilding["property_code"] : '';
				$stock_template = str_replace("[MaToa]", $building_code, $stock_template);

				$arr_row_code = array();
				for ($r = 1; $r <= $maxRow; $r++) {
					if (isset($header[$r])) $arr_row_code = $this->getArrNumberString($header[$r]);
					if (empty($arr_row_code)) continue;
					if (!$sheet->getRowDimension($r)->getVisible()) continue;

					// arr_col_floor đánh chỉ mục theo grid 0-based = số-dòng-tuyệt-đối - 1
					$floor = isset($arr_col_floor[$r - 1]) ? $arr_col_floor[$r - 1] : null;
					if (empty($floor)) continue;

					$cells = $this->readRow($sheet, $r, $maxColIdx);
					foreach ($cells as $key => $c) {
						if ($key < $floor_index) continue;
						$value = $c['value'];
						if (!empty($arr_row_code[$key]) && !empty($floor) && !empty($value)) {
							$ms_code = str_replace("[Tang]", $floor, $stock_template);
							$ms_code = str_replace("[CanHo]", $arr_row_code[$key], $ms_code);

							$bgColor = $c['bg'];
							$is_sold = 0;
							if ($clsISO->replaceSpace($value) == "da-ban" || in_array($bgColor, $color_sold)
								|| (!empty($color_dq) && !in_array($bgColor, $color_dq))) {
								$is_sold = 1;
							}
							if (empty($is_sold)) {
								$tblData[] = array("ms_code" => $ms_code, "total_price_vat" => $value);
							}
						}
					}
				}
			}
		}
		return $tblData;
	}

	// =====================================================================
	// HEADER DETECTION (dò động)
	// =====================================================================

	/** Map cột-file → field theo nhãn header thực tế (port Crawl::getHeader). Key = số dòng tuyệt đối. */
	private function getHeader($sheet, $maxRow, $maxColIdx, $sheet_name, $column_data, $list_data_check) {
		global $clsISO;
		$arr_fields = $this->cfgBySheet($column_data, $sheet_name);
		$data_check = $this->cfgBySheet($list_data_check, $sheet_name);
		if (empty($arr_fields)) return array();

		$arr_field_header = array();
		$key_ptg = "";
		foreach ($arr_fields as $key => $val) {
			if (empty($val) || $val == "link_smartchip") continue; // bỏ qua link_smartchip
			if ($val == "price_sheet_link") $key_ptg = $key;
			$arr_field_header[$val] = !empty($data_check[$key]) ? $clsISO->replaceSpace($data_check[$key]) : "";
		}

		$header = array();
		for ($r = 1; $r <= $maxRow; $r++) {
			if (!$sheet->getRowDimension($r)->getVisible()) continue;
			$cells = $this->readRow($sheet, $r, $maxColIdx);
			if (!$this->checkHeader($cells)) continue;

			$data_header = array();
			foreach ($cells as $key_cell => $c) {
				$value = trim($c['value']);
				$field = false;
				if (!empty($value)) {
					$slug = $clsISO->replaceSpace($value);
					$field = array_search($slug, $arr_field_header);
				}
				$colLetter = Coordinate::stringFromColumnIndex($key_cell + 1);
				$colHidden = !$sheet->getColumnDimension($colLetter)->getVisible();
				if (!empty($field) && (!$clsISO->checkItemInArray($field, $data_header) || $field == "ms_code") && !$colHidden) {
					$data_header[$key_cell] = $field;
				}
			}
			if (!empty($data_header)) {
				// Cột price_sheet_link thường không có nhãn header → fallback theo chỉ mục cấu hình
				// (vị trí cột giữ nguyên trong file export nên khớp với config index).
				if ($key_ptg !== "" && !isset($data_header[$key_ptg])) $data_header[$key_ptg] = "price_sheet_link";
				$header[$r] = $data_header;
			}
		}
		return $header;
	}

	/** Dòng có phải header bảng quỹ căn không (port Crawl::checkHeader). */
	private function checkHeader($cells) {
		global $clsISO;
		foreach ($cells as $c) {
			$value = $clsISO->replaceSpace($c['value']);
			if (stripos($value, "ma-can") !== false || stripos($value, "ma-cam") !== false || stripos($value, "ten-lo") !== false
				|| stripos($value, "loai-can") !== false || stripos($value, "ma-san-pham") !== false || stripos($value, "ten-can") !== false) {
				return true;
			}
		}
		return false;
	}

	/** Header sơ đồ điểm: dòng chứa mã căn (port Crawl::getHeaderPoint). Key = số dòng tuyệt đối. */
	private function getHeaderPoint($sheet, $maxRow, $maxColIdx) {
		$header = array();
		for ($r = 1; $r <= $maxRow; $r++) {
			if (!$sheet->getRowDimension($r)->getVisible()) continue;
			$cells = $this->readRow($sheet, $r, $maxColIdx);
			if (!$this->checkHeaderPoint($cells, "Căn")) continue;
			$data_header = array();
			foreach ($cells as $key_cell => $c) {
				$value = trim($c['value']);
				if (!empty($value)) $data_header[$key_cell] = $value;
			}
			if (!empty($data_header)) $header[$r] = $data_header;
		}
		return $header;
	}

	/** (port Crawl::checkHeaderPoint) */
	private function checkHeaderPoint($cells, $key = "Căn") {
		global $clsISO;
		$slug_key = $clsISO->replaceSpace($key);
		foreach ($cells as $c) {
			$value = $clsISO->replaceSpace($c['value']);
			if (stripos($value, $slug_key) !== false) return true;
		}
		return false;
	}

	// =====================================================================
	// HELPERS (port nguyên si, không phụ thuộc Google API)
	// =====================================================================

	/** Zero-pad số tầng < 10 → "0X" (port Crawl::getArrNumberString). */
	private function getArrNumberString($arr) {
		if (!is_array($arr)) return array();
		foreach ($arr as $key => $val) {
			if ((int)$val > 0) {
				if (is_numeric($val)) {
					$floor = ((int)$val < 10) ? "0" . ltrim($val, "0") : $val;
				} elseif ((int)$val < 10) {
					$floor = "0" . ltrim($val, "0");
				} else {
					$floor = $val;
				}
			} else {
				$floor = "";
			}
			$arr[$key] = $floor;
		}
		return $arr;
	}

	/** Sinh ms_code từ stock_template của building (port Crawl::genMsCode). */
	private function genMsCode($lstBuilding, $building_code, $floor, $code) {
		$stock_template = "";
		foreach ($lstBuilding as $v) {
			$mi = isset($v["more_information"]) ? $v["more_information"] : array();
			$pc = isset($v["property_code"]) ? $v["property_code"] : '';
			if ($pc !== '' && strpos(trim($building_code), $pc) !== false) {
				$stock_template = isset($mi["stock_template"]) ? $mi["stock_template"] : "";
				break;
			}
		}
		if (!empty($stock_template)) {
			$floor = $this->getCodeFloor($floor);
			$code = $this->getCodeFloor($code);
			$ms = str_replace('[MaToa]', $building_code, $stock_template);
			$ms = str_replace('[Tang]', $floor, $ms);
			$ms = str_replace('[CanHo]', $code, $ms);
			return $ms;
		}
		return "";
	}

	/** (port Crawl::getCodeFloor) */
	private function getCodeFloor($str) {
		$number = (int)$str;
		if ($number > 0) {
			$txt = preg_replace('/[^A-Za-z]/', '', $str);
			return ($number < 10) ? "0" . $number . $txt : $number . $txt;
		}
		return "";
	}

	// =====================================================================
	// LOWFLOOR (thấp tầng) — port Crawl::getDataLowFloor
	// =====================================================================

	/** Public: cùng chữ ký Crawl::getDataLowFloor (6 tham số, có $crawl_lowfloor). */
	function getDataLowFloor($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type, $crawl_lowfloor = array()) {
		global $clsISO;
		$res = array("result" => false);
 
		$wb = $this->loadWorkbook($spreadsheetId, $target_id, $agency_id, $stock_type, $ranges);
		if (empty($wb)) {
			// Sheet chặn tải xlsx → fallback Sheets API (Crawl::getDataLowFloor, KHÔNG phải getDataAPI).
			$clsCrawl = new Crawl();
			return $clsCrawl->getDataLowFloor($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type, $crawl_lowfloor);
		}

		$clsProperty = new Property();
		$oneAgency = $clsProperty->getOne($agency_id, "more_information");
		$more_information = $clsISO->to_array_json(isset($oneAgency['more_information']) ? $oneAgency['more_information'] : '');

		try {
			$tblData = $this->parseLowFloor($wb, $ranges, $target_id, $agency_id, $crawl_lowfloor, $more_information);
		} catch (Exception $e) {
			$this->cleanup($wb);
			return $res;
		}
		$this->cleanup($wb);
		return array("result" => true, "spreadsheetId_crawl" => $spreadsheetId, "tblData" => $tblData);
	}

	/** Parse nhánh thấp tầng (port Crawl::getDataLowFloor inner). */
	private function parseLowFloor($wb, $ranges, $target_id, $agency_id, $crawl_lowfloor, $more_information) {
		global $clsISO;
		$tblData = array();

		$cachedFile = DIR_CACHE_JSON . '/crawl/config_column_lowfloor.json';
		$arr_column_data = file_exists($cachedFile) ? $this->decoder->decodeFile($cachedFile) : array();
		$column_data = isset($arr_column_data[$agency_id][$target_id]) ? $arr_column_data[$agency_id][$target_id] : array();
		//echo "LF-DBG column_data=" . (empty($column_data) ? "EMPTY (thiếu config_column_lowfloor[$agency_id][$target_id]); file_exists=" . (file_exists($cachedFile) ? 1 : 0) : count($column_data) . " sheets:[" . implode(",", array_keys($column_data)) . "]") . "\n";
		if (empty($column_data)) return $tblData;

		$project_number_check = isset($more_information["project_number_check"]) ? $more_information["project_number_check"] : array();
		$arr_number_check = isset($project_number_check[$target_id]["number_check"]) ? $project_number_check[$target_id]["number_check"] : array();
		$list_data_check = isset($project_number_check[$target_id]["list_data_check"]) ? $project_number_check[$target_id]["list_data_check"] : array();
		foreach ($ranges as $range) {
			// Range lowfloor có hậu tố "!A1:AZ500" → tách lấy tên sheet.
			$parts = explode('!', $range);
			$sheet_name = trim($parts[0]);
			$sheet = $this->findSheetByName($wb, $sheet_name);
			if (!$sheet) { 
				//echo "LF-DBG sheet='$sheet_name' NOT FOUND. Sheets: [" . implode(", ", $wb->getSheetNames()) . "]\n"; 
				continue; 
			}
			$maxRow = $sheet->getHighestDataRow();
			$maxColIdx = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

			// Màu per-sheet từ $crawl_lowfloor (+ cờ is_color_dq/is_color_break)
			$cf = $this->cfgBySheet($crawl_lowfloor, $sheet_name);
			if (!is_array($cf)) $cf = array();
			$color_sold  = !empty($cf['color_sold']) ? $cf['color_sold'] : array();
			$color_dq    = (!empty($cf['is_color_dq']) && !empty($cf['color_dq'])) ? $cf['color_dq'] : array();
			$color_break = (!empty($cf['is_color_break']) && !empty($cf['color_break'])) ? $cf['color_break'] : array();
			$color_sold  = array_map('strtoupper', (array)$color_sold);   // chuẩn hoá hoa/thường (#ff0000 vs #FF0000)
			$color_dq    = array_map('strtoupper', (array)$color_dq);
			$color_break = array_map('strtoupper', (array)$color_break);
			$cfRules = $this->buildCfRules($sheet);   // luật conditional-formatting (màu "đã bán" thường là CF)

			$number_check = $this->cfgBySheet($arr_number_check, $sheet_name);
			if (!is_numeric($number_check)) $number_check = 0;

			// Cột ẩn (lowfloor bỏ qua cột ẩn) — tính 1 lần/sheet
			$hiddenCols = array();
			for ($c = 1; $c <= $maxColIdx; $c++) {
				$L = Coordinate::stringFromColumnIndex($c);
				if (!$sheet->getColumnDimension($L)->getVisible()) $hiddenCols[$c - 1] = true;
			}

			$header = $this->getHeader($sheet, $maxRow, $maxColIdx, $sheet_name, $column_data, $list_data_check);
			/*echo "LF-DBG sheet='$sheet_name' maxRow=$maxRow maxCol=$maxColIdx header=" . count($header) . " number_check=$number_check sold=" . count($color_sold) . " dq=" . count($color_dq) . " break=" . count($color_break) . " hidden=" . count($hiddenCols) . "\n";*/
			if (empty($header)) continue;

			$fields_table = array();
			$dbgSeen = 0; $dbgGen = 0; $dbgSold = 0; $dbgDq0 = 0; $dbgNoCode = 0; $dbgKept = 0;
			for ($r = 1; $r <= $maxRow; $r++) {
				if (isset($header[$r])) $fields_table = $header[$r];
				if (empty($fields_table)) continue;
				if (($r - 1) < $number_check) continue; // bỏ dòng trước header (grid index = $r-1)
				if (!$sheet->getRowDimension($r)->getVisible()) continue;
				$dbgSeen++;

				$cells = $this->readRow($sheet, $r, $maxColIdx);
				$data_row = array();
				$arr_code = array();
				$is_sold = 0; $is_general = 0; $is_dq = 0;

				foreach ($cells as $key => $c) {
					if (isset($hiddenCols[$key])) continue; // bỏ cột ẩn
					$value = $c['value'];
					$field = isset($fields_table[$key]) ? $fields_table[$key] : null;
					$sv = $clsISO->replaceSpace($value);

					// Bảng hàng chung → kết thúc bảng
					if (strpos($sv, 'bang-hang-chung') !== false && $target_id == 42 && ($agency_id == 11568 || $agency_id == 11566)) {
						$is_general = 1; break;
					}

					// bgColor chỉ tính cho cột mã / color_dq (hoặc agency 238)
					$bgColor = null; $bgColorBreak = null; $bgColorDQ = null;
					if ($field == "ms_code" || $field == "code_link" || $field == "color_dq" || $agency_id == 238) {
						$bgColor = $c['bg'];
						$cfHit = $this->cfBg($cfRules, $r, $key, $cells); // màu conditional-formatting hiệu lực (đè fill)
						if ($cfHit !== null) $bgColor = $cfHit;
						$bgColorBreak = $bgColor;
						if (($clsISO->checkItemInArray("color_dq", $fields_table) && $field == "color_dq") || !$clsISO->checkItemInArray("color_dq", $fields_table)) {
							$bgColorDQ = $bgColor;
						}
					}
					// Recompute color_break cho vài dự án đặc thù (mọi cột)
					if ((($agency_id == 276 && $target_id == 3) || ($agency_id == 162 && $target_id == 9) || ($agency_id == 240 && $target_id == 3)) && !empty($color_break)) {
						$bgColorBreak = $c['bg'];
						$cfHit2 = $this->cfBg($cfRules, $r, $key, $cells);
						if ($cfHit2 !== null) $bgColorBreak = $cfHit2;
					}
					if (($field == "ms_code" || $field == "code_link" || ($agency_id == 162 && $target_id == 9) || ($agency_id == 240 && $target_id == 3)) && in_array($bgColorBreak, $color_break)) {
						$is_general = 1; break;
					}
					// Đã bán theo màu
					if (($field == "ms_code" || $field == "code_link" || $agency_id == 238) && in_array($bgColor, $color_sold)) {
						$is_sold = 1; $data_row["bgColor"] = $bgColor; break;
					}

					$hyperlink = isset($c['link']) ? $c['link'] : null;

					// is_dq (mặc định 0 — chỉ đẩy row khi is_dq=1)
					if ($clsISO->checkItemInArray("color_dq", $fields_table)) {
						if (($field == "color_dq" && in_array($bgColorDQ, $color_dq)) || empty($color_dq)) {
							$is_dq = 1; $data_row["bgColor"] = $bgColorDQ;
						}
						if ($agency_id == 159 && $value == "Quỹ ĐQ") $is_dq = 1;
					} else {
						if (($field == "ms_code" && in_array($bgColorDQ, $color_dq)) || empty($color_dq)) {
							$is_dq = 1; $data_row["bgColor"] = $bgColorDQ;
						}
					}

					// Mã căn → getCode (tách nhiều mã); link → price_temporary_ns
					if (!empty($field) && ($field == "code_link" || $field == "ms_code") && !empty($value)) {
						$arr_code = $this->getCode($value);
						if ($field == "code_link") $data_row["price_temporary_ns"] = $hyperlink;
					} else if (!empty($field) && !($field == "code_link" || $field == "ms_code")) {
						$data_row[$field] = ($field == "price_temporary_ns" || $field == "link_smartchip") ? $hyperlink : $value;
					}
				}
				
				if (!empty($is_general)) { $dbgGen++; break; } // gặp bảng hàng chung → dừng sheet

				// Link phiếu tạm tính nằm trên ô có hyperlink (cột price_temporary_ns); fallback nếu mapping rỗng.
				$rowLinkLF = $this->firstLink($cells, $hiddenCols);
				if (empty($data_row["price_temporary_ns"]) && !empty($rowLinkLF)) {
					$data_row["price_temporary_ns"] = $rowLinkLF;
				}
				if (!empty($is_sold)) { $dbgSold++; continue; }
				if (empty($arr_code)) { $dbgNoCode++; continue; }
				if (empty($is_dq)) { $dbgDq0++; continue; }
				foreach ($arr_code as $ms_code) {
					if (preg_match(REGEX_MS_CODE, $ms_code) && strlen($ms_code) < 20) {
						$tblData[] = array_merge(array("ms_code" => $ms_code), $data_row);
						$dbgKept++;
					}
				}
			}
			//echo "LF-DBG sheet='$sheet_name' → seen=$dbgSeen kept=$dbgKept | skip: general=$dbgGen sold=$dbgSold noCode=$dbgNoCode dq0=$dbgDq0\n";
		}
		return $tblData;
	}

	/** Hyperlink đầu tiên (bỏ cột ẩn) của 1 dòng — dùng làm price_temporary_ns khi mapping rỗng. */
	private function firstLink($cells, $hiddenCols) {
		foreach ($cells as $key => $c) {
			if (isset($hiddenCols[$key])) continue;
			if (!empty($c['link'])) return $c['link'];
		}
		return null;
	}

	/** Tách mã ghép "P-1/2/3" → nhiều mã (port Crawl::getCode). */
	private function getCode($ms_code) {
		if (strpos($ms_code, '/') === false) {
			return array($this->cleanCode($ms_code));
		}
		$tmp = explode('-', $ms_code, 2);
		$prefix = isset($tmp[0]) ? $tmp[0] : '';
		$nums = isset($tmp[1]) ? $tmp[1] : '';
		$results = array();
		foreach (explode('/', $nums) as $code) {
			$results[] = $this->cleanCode($prefix . '-' . trim($code));
		}
		return $results;
	}

	/** Làm sạch 1 mã căn (dùng chung cho getCode). */
	private function cleanCode($ms_code) {
		$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($ms_code)));
		$ms_code = strtoupper($ms_code);
		$ms_code = str_replace(array(" ", "(LẺ)", "(CHẴN)", "CĂNGÓC", "GóC"), "", $ms_code);
		return trim($ms_code);
	}

	/** Lấy cấu hình theo tên sheet (hỗ trợ cả key có/không dấu nháy 'Sheet'). */
	private function cfgBySheet($arr, $sheet_name) {
		if (isset($arr["'" . $sheet_name . "'"])) return $arr["'" . $sheet_name . "'"];
		if (isset($arr[$sheet_name])) return $arr[$sheet_name];
		return null;
	}

	/** Config dải màu sold/break/dq theo agency + combo (port từ getDataNew1 CRAWL_CONFIG_MAP). */
	private function resolveColorConfig($agency_id, $target_id) {
		// Nguồn DUY NHẤT: CrawlConfig (dùng chung với Crawl::getDataAPI) — sửa màu chỉ ở CrawlConfig.php.
		return CrawlConfig::resolveColors($agency_id, $target_id);
	}

	// =====================================================================
	// I/O: đọc ô, tải/parse xlsx (+ fallback copy như Crawl)
	// =====================================================================

	/** Đọc 1 dòng → [colIdx0 => ['value','bg','cell']]. colIdx0 0-based để khớp logic gốc. */
	private function readRow($sheet, $r, $maxColIdx) {
		$cells = array();
		for ($c = 1; $c <= $maxColIdx; $c++) {
			if ($sheet->cellExistsByColumnAndRow($c, $r)) {
				$cell = $sheet->getCellByColumnAndRow($c, $r);
				// PhpSpreadsheet cache chỉ giữ 1 ô "hiện hành" sống — phải đọc HẾT (value, màu,
				// link) NGAY tại đây, không giữ object ô để đọc trễ (sẽ trả sai/rỗng).
				$u = $cell->getHyperlink()->getUrl();
				$cells[$c - 1] = array(
					'value' => $this->cellValue($cell),
					'bg'    => $this->hexColor($cell),
					'link'  => ($u !== '' ? $u : null),
				);
			} else {
				$cells[$c - 1] = array('value' => '', 'bg' => null, 'link' => null);
			}
		}
		return $cells;
	}

	/**
	 * Giá trị ô. Ô công thức → dùng giá trị ĐÃ CACHE trong file (getOldCalculatedValue) thay vì
	 * bắt PhpSpreadsheet tính lại (nhiều sheet dùng hàm Google-only/cross-sheet → tính lại ra #NAME?).
	 * Áp format số để khớp giá trị hiển thị như Google API getFormattedValue.
	 */
	private function cellValue($cell) {
		if ($cell->getDataType() === DataType::TYPE_FORMULA) {
			$val = $cell->getOldCalculatedValue();
			if ($val === null) {
				try { $val = $cell->getCalculatedValue(); } catch (\Exception $e) { $val = ''; }
			}
			$fmt = $cell->getStyle()->getNumberFormat()->getFormatCode();
			return (string)NumberFormat::toFormattedString($val, $fmt);
		}
		return (string)$cell->getFormattedValue();
	}

	/** Màu nền dạng #RRGGBB; trả null nếu ô KHÔNG tô nền (tránh nhận nhầm như màu mặc định). */
	private function hexColor($cell) {
		if (!$cell) return null;
		$fill = $cell->getStyle()->getFill();
		if ($fill->getFillType() === Fill::FILL_NONE) return null;
		$rgb = $fill->getStartColor()->getRGB();
		return $rgb ? '#' . strtoupper($rgb) : null;
	}

	// ---------------------------------------------------------------------
	// Conditional formatting (PhpSpreadsheet getFill KHÔNG đánh giá CF) —
	// suy ra màu hiệu lực cho 2 mẫu phổ biến: containsText + so-sánh-bằng.
	// ---------------------------------------------------------------------

	/** Đọc & chuẩn hoá các luật CF của sheet → mảng rule [box,col(ref),kind,text,hex]. */
	private function buildCfRules($sheet) {
		$rules = array();
		$coll = $sheet->getConditionalStylesCollection();
		foreach ($coll as $range => $conds) {
			foreach (explode(' ', $range) as $rng) {
				$box = $this->rangeBox($rng);
				if (!$box) continue;
				foreach ($conds as $cond) {
					$fill = $cond->getStyle()->getFill();
					if ($fill->getFillType() === Fill::FILL_NONE) continue;
					$rgb = $fill->getStartColor()->getRGB();
					if (!$rgb) continue;
					$hex = '#' . strtoupper($rgb);
					$type = $cond->getConditionType();
					$parsed = null;
					if ($type === 'containsText') {
						$parsed = array('kind' => 'contains', 'col' => $box[0], 'text' => (string)$cond->getText());
					} else if ($type === 'cellIs' && $cond->getOperatorType() === 'equal') {
						$cs = $cond->getConditions();
						$txt = isset($cs[0]) ? trim((string)$cs[0], '"') : '';
						$parsed = array('kind' => 'eq', 'col' => $box[0], 'text' => $txt);
					} else if ($type === 'expression') {
						$cs = $cond->getConditions();
						$f = isset($cs[0]) ? trim((string)$cs[0]) : '';
						if (preg_match('/^\$?([A-Z]+)\$?\d+\s*=\s*"(.*)"$/u', $f, $m)) {
							$parsed = array('kind' => 'eq', 'col' => Coordinate::columnIndexFromString($m[1]), 'text' => $m[2]);
						}
					}
					if ($parsed) {
						$parsed['box'] = $box;
						$parsed['hex'] = $hex;
						$rules[] = $parsed;
					}
				}
			}
		}
		return $rules;
	}

	/** "A3:BC1033"/"D3" → [minColIdx,maxColIdx,minRow,maxRow] (1-based) hoặc null. */
	private function rangeBox($rng) {
		$rng = trim($rng);
		if ($rng === '') return null;
		if (strpos($rng, ':') === false) {
			$p = Coordinate::coordinateFromString($rng);
			$ci = Coordinate::columnIndexFromString($p[0]);
			return array($ci, $ci, (int)$p[1], (int)$p[1]);
		}
		$ab = explode(':', $rng);
		$a = Coordinate::coordinateFromString($ab[0]);
		$b = Coordinate::coordinateFromString($ab[1]);
		return array(
			Coordinate::columnIndexFromString($a[0]), Coordinate::columnIndexFromString($b[0]),
			(int)$a[1], (int)$b[1]
		);
	}

	/** Màu CF hiệu lực cho ô (col 0-based) tại dòng $r — dựa giá trị các ô trong dòng ($cells). */
	private function cfBg($cfRules, $r, $colIdx0, $cells) {
		if (empty($cfRules)) return null;
		$col1 = $colIdx0 + 1;
		foreach ($cfRules as $rule) {
			$box = $rule['box'];
			if ($col1 < $box[0] || $col1 > $box[1] || $r < $box[2] || $r > $box[3]) continue;
			$refIdx0 = $rule['col'] - 1;
			$refVal = isset($cells[$refIdx0]['value']) ? trim((string)$cells[$refIdx0]['value']) : '';
			if ($rule['kind'] === 'eq') {
				// Google/Excel so sánh chuỗi trong CF KHÔNG phân biệt hoa/thường ("Đã Bán" khớp "Đã bán").
				if ($this->ciEq($refVal, $rule['text'])) return $rule['hex'];
			} else {
				if ($rule['text'] !== '' && $this->ciContains($refVal, $rule['text'])) return $rule['hex'];
			}
		}
		return null;
	}

	/** So sánh bằng không phân biệt hoa/thường (mb-aware). */
	private function ciEq($a, $b) {
		if (function_exists('mb_strtolower')) return mb_strtolower($a, 'UTF-8') === mb_strtolower($b, 'UTF-8');
		return strcasecmp($a, $b) === 0;
	}

	/** Chứa chuỗi không phân biệt hoa/thường (mb-aware). */
	private function ciContains($haystack, $needle) {
		if (function_exists('mb_stripos')) return mb_stripos($haystack, $needle, 0, 'UTF-8') !== false;
		return stripos($haystack, $needle) !== false;
	}

	/** Tải xlsx → load PhpSpreadsheet (bật đọc style để có màu nền). */
	private function loadWorkbook($spreadsheetId, $target_id, $agency_id, $stock_type, $ranges = array()) {
		$path = $this->downloadXlsx($spreadsheetId, $target_id, $agency_id, $stock_type);
		if (empty($path)) return null;
		try {
			$reader = IOFactory::createReader('Xlsx');
			$reader->setReadDataOnly(false); // cần style → KHÔNG bật read-data-only
			// CHỈ parse các sheet cần (bỏ hậu tố "!A1:..") — file nhiều sheet (vd 25) nhanh ~6x.
			// Tên sheet trong config có thể lệch dấu cách đầu/cuối so với tab thật (vd config
			// "Quỹ độc quyền " vs tab "Quỹ độc quyền"). Khớp theo trim rồi nạp TÊN THẬT để
			// setLoadSheetsOnly không trượt (trượt → nạp 0 sheet → tblData rỗng). Không khớp được
			// thì bỏ giới hạn (nạp hết, chậm hơn) thay vì nạp rỗng.
			if (!empty($ranges)) {
				$want = array();
				foreach ($ranges as $r) {
					$p = explode('!', $r);
					$nm = trim($p[0]);
					$want[$nm] = true;                                  // tên đầy đủ
					$want[trim(mb_substr($nm, 0, 31, 'UTF-8'))] = true; // bản cắt 31 ký tự (xlsx giới hạn tên tab)
				}
				$real = array();
				foreach ($reader->listWorksheetNames($path) as $sn) {
					if (isset($want[trim($sn)])) $real[] = $sn;
				}
				if (!empty($real)) $reader->setLoadSheetsOnly(array_values(array_unique($real)));
			}
			return $reader->load($path);
		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * Tìm sheet theo tên, BỎ QUA lệch dấu cách đầu/cuối giữa config và tab thật.
	 * getSheetByName khớp CHÍNH XÁC → config "Quỹ độc quyền " (thừa dấu cách cuối) không khớp
	 * tab "Quỹ độc quyền" → null → bỏ sheet → tblData rỗng. Trim 2 phía để khớp.
	 * LƯU Ý: caller GIỮ NGUYÊN $sheet_name (kèm dấu cách) cho cfgBySheet/getHeader vì
	 * config_column_highfloor.json keyed theo đúng tên có dấu cách.
	 */
	private function findSheetByName($wb, $sheet_name) {
		$name = trim($sheet_name);
		$sheet = $wb->getSheetByName($name);
		if ($sheet) return $sheet;
		$names = $wb->getSheetNames();
		// 1) Khớp bỏ lệch dấu cách đầu/cuối (config vs tab thật)
		foreach ($names as $sn) {
			if (trim($sn) === $name) return $wb->getSheetByName($sn);
		}
		// 2) xlsx GIỚI HẠN tên tab 31 KÝ TỰ → tên sheet Google dài bị CẮT khi export
		//    (vd "bảng hàng ĐQ sắp xếp theo giá tăng dần +"(40) → "bảng hàng ĐQ sắp xếp theo giá t"(31)).
		//    So tên config cắt-31 (mb, theo ký tự không phải byte) với tab thật.
		$trunc = trim(mb_substr($name, 0, 31, 'UTF-8'));
		if ($trunc !== $name) {
			foreach ($names as $sn) {
				if (trim($sn) === $trunc) return $wb->getSheetByName($sn);
			}
		}
		return null;
	}

	/**
	 * Tải xlsx theo 3 bước: (1) export trực tiếp (sheet public); (2) export kèm OAuth Bearer
	 * token cho sheet PRIVATE (tài khoản Crawl có quyền đọc, giữ nguyên hyperlink, không dính
	 * giới hạn 10MB); (3) last-resort copy Drive rồi export bản sao.
	 * Lưu ý: ZipArchive không đọc được php://memory → bắt buộc ghi file tạm thật.
	 */
	private function downloadXlsx($spreadsheetId, $target_id, $agency_id, $stock_type) {
		$dbg = !empty($GLOBALS['__cl_dl_dbg']); // bật chẩn đoán tải: đặt $GLOBALS['__cl_dl_dbg']=1
		$xlsxMime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		$docsUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/export?format=xlsx";

		// 0) CACHE: 1 workbook (export tải CẢ file, nhiều block dùng chung sheetID) tốn ~7s tải.
		//    Dùng lại file cache trong 30' → bỏ qua download. Key theo spreadsheetId.
		//    Bỏ qua đọc cache khi $GLOBALS['__cl_no_cache']=1 (test/buộc tải mới); vẫn ghi cache.
		$cacheFile = $this->xlsxCacheFile($spreadsheetId); 
//		if (empty($GLOBALS['__cl_no_cache'])
//			&& is_file($cacheFile) && (time() - @filemtime($cacheFile)) < 1800
//			&& @file_get_contents($cacheFile, false, null, 0, 2) === 'PK') {
//			if ($dbg) echo "0 CACHE-HIT age=" . (time() - filemtime($cacheFile)) . "s $cacheFile\n";
//			return $cacheFile; // KHÔNG đưa vào tmpFiles → cleanup() không xoá, giữ cho lần sau
//		}

		// 1) Export trực tiếp (sheet public)
		$direct = $this->curlGet($docsUrl);
		if ($dbg) echo "1 DIRECT: code={$direct['code']} size=" . strlen((string)$direct['body']) . " isXlsx=" . ($this->isXlsx($direct['body']) ? 1 : 0) . "\n";
		if ($direct['code'] == 200 && $this->isXlsx($direct['body'])) {
			return $this->cacheXlsx($spreadsheetId, $direct['body']);
		}

		try {
			$clsCrawl = new Crawl();
			$accessToken = $this->getAccessToken($clsCrawl);
			if ($dbg) echo "TOKEN len=" . strlen((string)$accessToken) . "\n";

			if (!empty($accessToken)) {
				$auth = array("Authorization: Bearer " . $accessToken);

				// 2) Sheet private → Drive API export (chuẩn OAuth), giữ hyperlink
				$driveUrl = "https://www.googleapis.com/drive/v3/files/{$spreadsheetId}/export?mimeType=" . rawurlencode($xlsxMime) . "&supportsAllDrives=true";
				$da = $this->curlGet($driveUrl, $auth);
				if ($dbg) {
					echo "2 DRIVE-EXPORT: code={$da['code']} size=" . strlen((string)$da['body']) . " isXlsx=" . ($this->isXlsx($da['body']) ? 1 : 0) . "\n";
					if (!$this->isXlsx($da['body'])) echo "   head: " . substr((string)$da['body'], 0, 220) . "\n";
				}
				if ($da['code'] == 200 && $this->isXlsx($da['body'])) return $this->cacheXlsx($spreadsheetId, $da['body']);

				// 2b) File .xlsx UPLOAD sẵn trên Drive (không export được) → tải thẳng file gốc
				$mediaUrl = "https://www.googleapis.com/drive/v3/files/{$spreadsheetId}?alt=media&supportsAllDrives=true";
				$md = $this->curlGet($mediaUrl, $auth);
				if ($dbg) {
					echo "2b DRIVE-GET-MEDIA: code={$md['code']} size=" . strlen((string)$md['body']) . " isXlsx=" . ($this->isXlsx($md['body']) ? 1 : 0) . "\n";
					if (!$this->isXlsx($md['body'])) echo "   head: " . substr((string)$md['body'], 0, 220) . "\n";
				}
				if ($md['code'] == 200 && $this->isXlsx($md['body'])) return $this->cacheXlsx($spreadsheetId, $md['body']);

				// 2c) Thử export qua docs endpoint kèm Bearer (một số file)
				$auth2 = $this->curlGet($docsUrl, $auth);
				if ($dbg) echo "2c DOCS-BEARER: code={$auth2['code']} size=" . strlen((string)$auth2['body']) . " isXlsx=" . ($this->isXlsx($auth2['body']) ? 1 : 0) . "\n";
				if ($auth2['code'] == 200 && $this->isXlsx($auth2['body'])) return $this->cacheXlsx($spreadsheetId, $auth2['body']);
			}

			// 3) Last resort: copy Drive rồi export bản sao (có thể mất hyperlink)
			$copyId = $clsCrawl->copySpreadsheet($spreadsheetId, array(), $target_id, $agency_id, $stock_type);
			if ($dbg) echo "3 COPY: copyId=" . var_export($copyId, true) . "\n";
			if (!empty($copyId)) {
				$bytes = $this->exportViaDrive($clsCrawl, $copyId);
				if ($dbg) echo "3 EXPORT-COPY: size=" . strlen((string)$bytes) . " isXlsx=" . ($this->isXlsx($bytes) ? 1 : 0) . "\n";
				if ($this->isXlsx($bytes)) return $this->cacheXlsx($spreadsheetId, $bytes);
			}
		} catch (Exception $e) {
			if ($dbg) echo "EXCEPTION: " . $e->getMessage() . "\n";
			return null;
		}
		if ($dbg) echo ">>> TẤT CẢ CÁCH TẢI ĐỀU THẤT BẠI\n";
		return null;
	}

	/** Lấy access token từ Google_Client của Crawl (đã refreshToken trong __construct). */
	private function getAccessToken($clsCrawl) {
		try {
			$token = $clsCrawl->client->getAccessToken();
			if (is_array($token)) return isset($token['access_token']) ? $token['access_token'] : '';
			return (string)$token;
		} catch (Exception $e) {
			return '';
		}
	}

	/** Export bản sao (Google Sheet native) ra xlsx qua Drive API (giới hạn ~10MB). */
	private function exportViaDrive($clsCrawl, $fileId) {
		try {
			$resp = $clsCrawl->drive->files->export(
				$fileId,
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				array('alt' => 'media')
			);
			return (string)$resp->getBody();
		} catch (Exception $e) {
			return '';
		}
	}

	private function curlGet($url, $headers = array()) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if (!empty($headers)) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$body = curl_exec($ch);
		$code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return array('body' => $body, 'code' => $code);
	}

	/** Xlsx = file zip → 2 byte đầu "PK" (lọc trang HTML login khi sheet private). */
	private function isXlsx($data) {
		return is_string($data) && strlen($data) > 4 && substr($data, 0, 2) === 'PK';
	}

	private function writeTemp($data) {
		$path = tempnam(sys_get_temp_dir(), 'crawl_');
		file_put_contents($path, $data);
		$this->tmpFiles[] = $path;
		return $path;
	}

	/** Đường dẫn file cache xlsx theo spreadsheetId (TTL kiểm tại downloadXlsx). */
	private function xlsxCacheFile($spreadsheetId) {
		$dir = (defined('DIR_CACHE_JSON') ? DIR_CACHE_JSON : sys_get_temp_dir()) . '/crawl';
		if (!is_dir($dir)) @mkdir($dir, 0777, true);
		return $dir . '/xlsxcache_' . md5($spreadsheetId) . '.xlsx';
	}

	/**
	 * Ghi xlsx vào cache (persistent, dùng lại cho block khác cùng workbook trong 30').
	 * KHÔNG đưa vào $this->tmpFiles → cleanup() không xoá. Ghi cache lỗi → fallback file tạm.
	 */ 
	private function cacheXlsx($spreadsheetId, $data) {
		$path = $this->xlsxCacheFile($spreadsheetId);
		if (@file_put_contents($path, $data) !== false && is_file($path)) return $path;
		return $this->writeTemp($data);
	}

	private function cleanup($wb = null) {
		if ($wb) {
			$wb->disconnectWorksheets();
			unset($wb);
		}
		foreach ($this->tmpFiles as $f) {
			@unlink($f);
		}
		$this->tmpFiles = array();
	}
	
	function getCodeNotTemplate($ms_code){
		$ms_code = preg_replace('/\s+/', '', $ms_code);
		$ms_code = trim(str_replace('.','', $ms_code));
		$ms_code = str_replace("-","",$ms_code);
		$ms_code = str_replace("_","",$ms_code);
		$ms_code = strtoupper($ms_code);
		return $ms_code;
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
}
?>
