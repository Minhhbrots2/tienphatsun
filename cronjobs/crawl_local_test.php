<?php
/*======================================================================*\
|| crawl_local_test — đối chiếu Crawl::getDataNew (Google API) với       ||
|| CrawlLocal::getDataNew (PhpSpreadsheet export) trên DỮ LIỆU THẬT.     ||
|| CHỈ ĐỌC, KHÔNG ghi DB. Chạy trên server (cần PHP+ionCube+DB+Google).  ||
||                                                                       ||
|| Cách dùng:                                                            ||
||   php cronjobs/crawl_local_test.php                  (tự chọn block)  ||
||   ?agency_id=159&target_id=2     chỉ định block cụ thể                ||
||   ?only=local | ?only=google     chỉ chạy 1 bên (cô lập autoload)     ||
||   ?dump=1                         in toàn bộ tblData                  ||
\*======================================================================*/
	ini_set('display_errors', '0');
	ini_set('display_startup_errors', '0');
	error_reporting(E_ALL);
	// Hiện cả FATAL/PARSE error (display_errors thường không in được loại này)
	register_shutdown_function(function () {
		$e = error_get_last();
		if ($e && in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR))) {
			echo "\n\n*** FATAL: " . $e['message'] . "\n    in " . $e['file'] . ":" . $e['line'] . " ***\n";
		}
	});
	ini_set('memory_limit', '5048M');
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	define("DIR_INCLUDES", ROOTPATH . "/core");
	define("DIR_MODELS", ROOTPATH . "/models");
	define("DIR_ADODB", DIR_INCLUDES . "/adodb5");
	/** Required Config */
	require(ABSPATH . DS . 'init.php');
	require(ABSPATH . DS . 'setting.php');
	define("DIR_LANG", PCMS_DIR . "/lang");
	if (!defined('LANG_DEFAULT')) define("LANG_DEFAULT", 'vn');
	define("SMARTY_DEBUG", false);
	define("COMPILE_CHECK", true);
	define("ADODB_DEBUG", false);
	define("STOP_APP_IF_ERROR", 1); 
	/** DriverDatabase */
	require_once(DIR_ADODB . "/adodb.inc.php");
	$dbconn =& ADONewConnection(DB_TYPE);
	$dbconn->debug = ADODB_DEBUG;
	$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	/** Core Requirement */
	require_once DIR_COMMON . "/DbBasic.php";
	require_once DIR_COMMON . "/App.php";
	require_once DIR_COMMON . "/Module.php";
	require_once DIR_COMMON . "/Download.php";
	require_once DIR_COMMON . "/Upload.php";
	require_once DIR_COMMON . "/Config.php";
	require_once DIR_INCLUDES . "/curl/vendor/autoload.php";
	require_once DIR_INCLUDES . "/carbon/vendor/autoload.php";
	/** ClassRequirement */
	if (function_exists('spl_autoload_register')) {
		function autoload($model) {
			if (file_exists(DIR_MODELS . DS . $model . '.php')) {
				require_once(DIR_MODELS . DS . $model . '.php');
			} else if (file_exists(DIR_MODELS . '/class.' . $model . '.php')) {
				require_once(DIR_MODELS . '/class.' . $model . '.php');
			}
		}
		spl_autoload_register('autoload');
	}
	global $profile_id;
	$profile_id = 0;

	$clsISO = new ISO();
	$clsProperty = new Property();

	$type = (isset($_GET['type']) && $_GET['type'] == 'lowfloor') ? 'lowfloor' : 'highfloor';
	$stock_type = ($type == 'lowfloor') ? _BLOCK_TYPE_LOWFLOOR_SALE : _BLOCK_TYPE_HIGHLEVEL_SALE;
	$agency_id = isset($_GET['agency_id']) ? (int)$_GET['agency_id'] : 0;
	$target_id = isset($_GET['target_id']) ? (int)$_GET['target_id'] : 0;
	$only = isset($_GET['only']) ? $_GET['only'] : '';
	$dump = !empty($_GET['dump']);
	$crawl_lowfloor = array();

	// -------------------------------------------------------------------
	// Xác định block để test
	// -------------------------------------------------------------------
	$spreadsheetId = '';
	$ranges = array();
	$cfgKey = ($type == 'lowfloor') ? 'crawl_lowfloor' : 'block_crawl';
	if ($agency_id && $target_id) {
		$one = $clsProperty->getOne($agency_id, "more_information");
		$mi = $clsISO->to_array_json($one['more_information']);
		$bc = isset($mi[$cfgKey][$target_id]) ? $mi[$cfgKey][$target_id] : array();
		$spreadsheetId = isset($bc["sheetID"]) ? $bc["sheetID"] : '';
		$ranges = !empty($bc["sheet_name"]) ? explode("|", $bc["sheet_name"]) : array();
		if ($type == 'lowfloor') $crawl_lowfloor = $bc;
	} else {
		$cond = "`property_type`='_AGENCY' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$." . $cfgKey . ".*.is_crawl') IS NOT NULL";
		$lstAgency = $clsProperty->getAll($cond, $clsProperty->pkey . ",more_information");
		if (!empty($lstAgency)) {
			foreach ($lstAgency as $val) {
				$mi = $clsISO->to_array_json($val['more_information']);
				$bc = isset($mi[$cfgKey]) ? $mi[$cfgKey] : array();
				foreach ($bc as $bid => $v) {
					if (!empty($v['is_crawl']) && !empty($v["sheet_name"]) && !empty($v["sheetID"])) {
						$agency_id = $val[$clsProperty->pkey];
						$target_id = $bid;
						$spreadsheetId = $v["sheetID"];
						$ranges = explode("|", $v["sheet_name"]);
						if ($type == 'lowfloor') $crawl_lowfloor = $v;
						break 2;
					}
				}
			}
		}
	}
	// Lowfloor: cronjob append "!A1:AZ500" vào mỗi range
	if ($type == 'lowfloor') {
		foreach ($ranges as $k => $range) $ranges[$k] = $range . "!A1:AZ500";
	}

	echo "================ CRAWL LOCAL TEST ================\n";
	echo "agency_id=$agency_id  target_id=$target_id  stock_type=$stock_type\n";
	echo "spreadsheetId=$spreadsheetId\n";
	echo "ranges=" . implode(" | ", $ranges) . "\n";
	echo "-------------------------------------------------\n";

	if (empty($spreadsheetId) || empty($ranges)) {
		exit("Không tìm thấy block crawl phù hợp. Truyền ?agency_id=&target_id=\n");
	}

	// -------------------------------------------------------------------
	// Chạy 2 bên
	// -------------------------------------------------------------------
	$resG = array("result" => false, "tblData" => array());
	$resL = array("result" => false, "tblData" => array());
	$tG = $tL = 0;

	if ($only != 'local') {
		$clsCrawl = new Crawl();
		$t0 = microtime(true);
		try {
			$resG = ($type == 'lowfloor')
				? $clsCrawl->getDataLowFloor($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type, $crawl_lowfloor)
				: $clsCrawl->getDataNew($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type);
		} catch (\Throwable $e) {
			echo "*** GOOGLE ERROR: " . $e->getMessage() . "\n    " . $e->getFile() . ":" . $e->getLine() . "\n";
		}
		$tG = round(microtime(true) - $t0, 2);
		echo "[Google API ] result=" . (!empty($resG['result']) ? 'OK' : 'FAIL')
			. "  rows=" . count(isset($resG['tblData']) ? $resG['tblData'] : array()) . "  time={$tG}s\n";
	}
	if ($only != 'google') {
		$clsCrawlLocal = new CrawlLocal();
		$t0 = microtime(true);
		try {
			$resL = ($type == 'lowfloor')
				? $clsCrawlLocal->getDataLowFloor($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type, $crawl_lowfloor)
				: $clsCrawlLocal->getDataNew($spreadsheetId, $ranges, $target_id, $agency_id, $stock_type);
		} catch (\Throwable $e) {
			echo "*** LOCAL ERROR: " . $e->getMessage() . "\n    " . $e->getFile() . ":" . $e->getLine() . "\n";
		}
		$tL = round(microtime(true) - $t0, 2);
		echo "[PhpSpreadsht] result=" . (!empty($resL['result']) ? 'OK' : 'FAIL')
			. "  rows=" . count(isset($resL['tblData']) ? $resL['tblData'] : array()) . "  time={$tL}s\n";
	}
	echo "-------------------------------------------------\n";

	if ($only == 'google' || $only == 'local') {
		if ($dump) print_r($only == 'google' ? $resG['tblData'] : $resL['tblData']);
		exit("(Chạy 1 bên — bỏ qua so khớp)\n");
	}

	// -------------------------------------------------------------------
	// So khớp theo ms_code chuẩn hoá
	// -------------------------------------------------------------------
	$normCode = function ($c) {
		$c = preg_replace('/\s+/', '', (string)$c);
		$c = trim(str_replace('.', '', $c));
		$c = str_replace('-', '', $c);
		return strtoupper($c);
	};
	$indexBy = function ($rows) use ($normCode) {
		$out = array();
		foreach ($rows as $r) {
			if (empty($r['ms_code'])) continue;
			$out[$normCode($r['ms_code'])] = $r;
		}
		return $out;
	};

	$mapG = $indexBy(isset($resG['tblData']) ? $resG['tblData'] : array());
	$mapL = $indexBy(isset($resL['tblData']) ? $resL['tblData'] : array());

	$onlyG = array_diff_key($mapG, $mapL);
	$onlyL = array_diff_key($mapL, $mapG);
	$common = array_intersect_key($mapG, $mapL);

	$priceDiff = array();
	foreach ($common as $code => $rG) {
		$pG = isset($rG['total_price_vat']) ? trim((string)$rG['total_price_vat']) : '';
		$pL = isset($mapL[$code]['total_price_vat']) ? trim((string)$mapL[$code]['total_price_vat']) : '';
		if ($pG !== $pL) $priceDiff[$code] = array('google' => $pG, 'local' => $pL);
	}

	echo "Unique mã: Google=" . count($mapG) . "  Local=" . count($mapL) . "  Chung=" . count($common) . "\n";
	echo "Chỉ có ở GOOGLE (" . count($onlyG) . "): " . implode(", ", array_slice(array_keys($onlyG), 0, 30)) . "\n";
	echo "Chỉ có ở LOCAL  (" . count($onlyL) . "): " . implode(", ", array_slice(array_keys($onlyL), 0, 30)) . "\n";
	echo "Lệch giá total_price_vat (" . count($priceDiff) . "):\n";
	$i = 0;
	foreach ($priceDiff as $code => $p) {
		echo "   $code : google='{$p['google']}'  local='{$p['local']}'\n";
		if (++$i >= 30) { echo "   ... (còn nữa)\n"; break; }
	}

	$verdict = (count($onlyG) == 0 && count($onlyL) == 0 && count($priceDiff) == 0) ? "KHỚP 100%" : "CÓ LỆCH — kiểm tra ở trên";
	echo "-------------------------------------------------\n";
	echo "KẾT LUẬN: $verdict\n";

	if ($dump) {
		echo "\n=== tblData GOOGLE ===\n"; print_r($resG['tblData']);
		echo "\n=== tblData LOCAL ===\n"; print_r($resL['tblData']);
	}
?>
