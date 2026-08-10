<?php
/*======================================================================*\
|| crawl_local_linkdiag — kiểm tra file export tải TỪ MẠNG SERVER có giữ ||
|| hyperlink (link PTG/smartchip) hay không. Độc lập, không cần DB.      ||
|| Chạy: https://ca.futurehomes.vn/cronjobs/crawl_local_linkdiag.php     ||
\*======================================================================*/
	ini_set('display_errors', '1');
	error_reporting(E_ALL);
	ini_set('memory_limit', '2048M');
	header('Content-Type: text/plain; charset=utf-8');

	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	define('DIR_INCLUDES', ROOTPATH . '/core');
	require_once(DIR_INCLUDES . '/PhpSpreadsheet/autoload.php');

	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

	$sid = isset($_GET['sid']) ? $_GET['sid'] : '1lezEjJNrTcCIMsWHnE7dM8rh6JfTppGlnnKl6007oKM';
	$sheetName = isset($_GET['sheet']) ? $_GET['sheet'] : 'MIK ĐỘC QUYỀN';

	$url = "https://docs.google.com/spreadsheets/d/$sid/export?format=xlsx";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$eff  = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	$err  = curl_error($ch);
	curl_close($ch);

	echo "=== DIRECT EXPORT (mạng server) ===\n";
	echo "HTTP=$code  size=" . strlen((string)$data) . "\n";
	echo "effectiveURL=$eff\n";
	echo "curlError=" . ($err ?: '(none)') . "\n";
	$magic = substr((string)$data, 0, 2);
	echo "magic='$magic'  (PK = đúng xlsx)\n";

	if ($magic !== 'PK') {
		echo "\n>>> KHÔNG phải xlsx — server bị chặn / redirect login. 400 byte đầu:\n";
		echo substr((string)$data, 0, 400) . "\n";
		exit;
	}

	$tmp = tempnam(sys_get_temp_dir(), 'ld_');
	file_put_contents($tmp, $data);
	$reader = IOFactory::createReader('Xlsx');
	$reader->setReadDataOnly(false);
	$wb = $reader->load($tmp);
	@unlink($tmp);

	echo "\n=== HYPERLINK CHECK ===\n";
	echo "Sheets: " . implode(" | ", $wb->getSheetNames()) . "\n";
	$sheet = $wb->getSheetByName($sheetName);
	if (!$sheet) { echo "Không thấy sheet '$sheetName'\n"; exit; }

	$maxRow = min(30, $sheet->getHighestDataRow());
	$maxCol = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
	echo "sheet='$sheetName'  maxCol=$maxCol  maxRowReal=" . $sheet->getHighestDataRow() . "\n";

	$cnt = 0;
	for ($r = 1; $r <= $maxRow; $r++) {
		for ($c = 1; $c <= $maxCol; $c++) {
			if (!$sheet->cellExistsByColumnAndRow($c, $r)) continue;
			$cell = $sheet->getCellByColumnAndRow($c, $r);
			$link = $cell->getHyperlink()->getUrl();
			if ($link !== '') {
				$cnt++;
				if ($cnt <= 8) {
					echo "  " . Coordinate::stringFromColumnIndex($c) . $r . " => "
						. mb_substr((string)$cell->getFormattedValue(), 0, 15) . " | LINK=" . mb_substr($link, 0, 60) . "\n";
				}
			}
		}
	}
	echo "TỔNG ô có hyperlink (30 dòng đầu) = $cnt\n";
	echo ($cnt > 0 ? ">>> File server tải VỀ CÓ link → lỗi nằm ở CrawlLocal (nhánh tải/scan).\n"
	              : ">>> File server tải về KHÔNG có link → mạng server nhận file khác (mất hyperlink).\n");
