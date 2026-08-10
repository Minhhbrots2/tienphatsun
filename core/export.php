

<?php
	/*
	* PHP file mail check someone has opened
	*/
	//ini_set('display_errors',1);
	error_reporting(E_ALL ^ E_NOTICE);
	if(!function_exists('vsprint_r')){
		function vsprint_r($doc){
			print('<pre>'.print_r($doc, true).'</pre>'); die();
		}
	}
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	/** Required Config File*/
	require_once(ABSPATH.'/init.php');
	//require_once(ABSPATH.'/lang/vn.php');
	require_once DIR_ADODB.'/adodb.inc.php';
	require_once DIR_COMMON."/clsDbBasic.php";
	require_once DIR_COMMON."/clsDbBasicWHMCS.php";
	require_once DIR_COMMON."/clsCache.php";
	//require_once(DIR_COMMON.'/class.driver.php');
	//require_once(DIR_INCLUDES.'/addons/phpexcel/Classes/PHPExcel.php');
	#- Database handle
	/*$dbconn = ADONewConnection(DB_TYPE);
	if (isset($dbinfo) && is_array($dbinfo)) {
		$dbconn->Connect($dbinfo['host'], $dbinfo['user'], $dbinfo['pass'], $dbinfo['db']);
	} else {
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	}*/
	if(ADODB_CACHED==1){
		$ADODB_CACHE_DIR = CACHE_ADODB_DIR;
		$GLOBALS['ADODB_CACHE_DIR'] = $ADODB_CACHE_DIR;
		
		$dbconn = ADONewConnection(DB_TYPE);
		//$dbconn->debug = ADODB_DEBUG;
		if (isset($dbinfo) && is_array($dbinfo)) {
			$dbconn->Connect($dbinfo['host'], $dbinfo['user'], $dbinfo['pass'], $dbinfo['db']);
		} else {
			$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}
		$dbconn->cacheSecs = ADODB_CACHED_TIME;
		$dbconn->EXECUTE("set names 'utf8'");
		
		$whmcs_db = ADONewConnection(DB_TYPE);
		//$whmcs_db->debug = ADODB_DEBUG;
		$whmcs_db->connect(DB_HOST_WHMCS, DB_USER_WHMCS, DB_PASS_WHMCS, DB_NAME_WHMCS);
		$whmcs_db->cacheSecs = ADODB_CACHED_TIME;
		$whmcs_db->EXECUTE("set names 'utf8'");
		$whmcs_db->setFetchMode(ADODB_FETCH_ASSOC);
	} else {
		$dbconn = ADONewConnection(DB_TYPE);
		//$dbconn->debug = ADODB_DEBUG;
		if (isset($dbinfo) && is_array($dbinfo)) {
			$dbconn->Connect($dbinfo['host'], $dbinfo['user'], $dbinfo['pass'], $dbinfo['db']);
		} else {
			$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		}
		$dbconn->EXECUTE("set names 'utf8'");
		
		$whmcs_db = ADONewConnection(DB_TYPE);
		//$whmcs_db->debug = ADODB_DEBUG;
		$whmcs_db->connect(DB_HOST_WHMCS, DB_USER_WHMCS, DB_PASS_WHMCS, DB_NAME_WHMCS);
		$whmcs_db->EXECUTE("set names 'utf8'");
		$whmcs_db->setFetchMode(ADODB_FETCH_ASSOC);
	}

	#- Loader class/model
	if(function_exists('spl_autoload_register')){
		function autoload($class){
			if(file_exists(DIR_CLASSES.'/class_'.$class.'.php')){
				require_once(DIR_CLASSES.'/class_'.$class.'.php');
			} else if(file_exists(DIR_CLASSES.'/class.'.$class.'.php')){
				require_once(DIR_CLASSES.'/class.'.$class.'.php');
			} elseif(IS_ADMIN_PAGE==1 && file_exists(DIR_ADMIN_CLASSES.'/class_'.$class.'.php')){
				require_once(DIR_ADMIN_CLASSES.'/class_'.$class.'.php');
			}
		}
		spl_autoload_register('autoload');
	}
	require_once(DIR_INCLUDES.'/addons/carbon/vendor/autoload.php');
	require_once(DIR_INCLUDES.'/addons/View/Formatter/Price.php');
	//require_once(DIR_INCLUDES.'/functions.php');
	function fromMySQLDate($date, $includeTime='', $applyClientDateFormat='', $zerodateval=''){
		if ($date instanceof Carbon\Carbon) {
			if ((string) $date === (string) Carbon\Carbon::createFromTimestamp(0, 'UTC')) {
				$date = '0000-00-00';
			}
		}
		$isZeroDate = substr($date, 0, 10) == '0000-00-00';
		if ($isZeroDate && $zerodateval) {
			return $zerodateval;
		}
		$year = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$day = substr($date, 8, 2);
		$hours = substr($date, 11, 2);
		$minutes = substr($date, 14, 2);
		$seconds = substr($date, 17, 2);
		
		$date = 'DD/MM/YYYY';
		$date = str_replace('YYYY', $year, $date);
		$date = str_replace('MM', $month, $date);
		$date = str_replace('DD', $day, $date);
		if ($includeTime) {
			$date .= ' ' . $hours . ':' . $minutes;
		}
		return $date;
	}
	function toArray($d){
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		} else {
			// Return array
			return $d;
		}
	}
	/*function __autoload($class){
		$classFile = DIR_CLASSES.'/class_'.$class.'.php';
		// Note: Class of name must start by prefix class_
		if(strpos($class, 'class_') === 0){
			break;
		}
		// Check file existence before including the if 
		if(file_exists($classFile) && !class_exists($class)){
			require_once($classFile);
		}
	}*/
	#
	$customHelperArray = array();
	if (is_dir(DIR_LIBRARYS)){
		if ($dh = opendir(DIR_LIBRARYS)) {
			while (($file = readdir($dh)) !== false) {
				if (substr($file, -3)=='php')
				array_push($customHelperArray, $file);
			}
			closedir($dh);
		}	
	}
	if(!empty($customHelperArray)){
		foreach ($customHelperArray as $helper){
			require_once(DIR_LIBRARYS."/".$helper); 
		}
		unset($customHelperArray);
	}
	$_LANG_ID = 'vn';
	if(Input::exists('lang_id','GET')){
		$_LANG_ID = Input::get('lang_id');
	}
	require_once(ABSPATH.'/lang/'.$_LANG_ID.'.php');
	function _get_Lang($key){
		global $_FRONTLANG;
		if (strpos($key, " ")!==false){
			$arr = str_word_count($key, 1);
			foreach ($arr as $k => $v){
				$val = trim($v, "'?,");
				$trans= (isset($_FRONTLANG[$val]))? $_FRONTLANG[$val] : $val;
				$key = str_replace($val, $trans, $key);
			}
			return $key;
		}else{
			$val = trim($key, "'?,");
			$trans= (isset($_FRONTLANG[$val]))? $_FRONTLANG[$val] : $val;
			$key = str_replace($val, $trans, $key);
			return $key;
		}
		return $key;
	}
	function get_Lang($key){
		global $_FRONTLANG;
		return (isset($_FRONTLANG[$key]))? $_FRONTLANG[$key] : $key;
	}
	function __($text) {
		return get_Lang( $text);
	}
	/** Auth Key*/
	if(Input::exists('secure','GET')){
		$secure = Input::get('secure');
		if(trim($secure) != md5($_SERVER['SERVER_ADDR'].DB_NAME)){
			die('Notx have access!');
		}
	}else{
		die('Not have access!');
	}
	/** End Auth Key*/
	$f = Input::get('f');
	$t = Input::get('t');
	$tid = Input::get('tid');

	$clsISO = new ISO();
	$clsProperty = new Property();
	$clsConfiguration = new Configuration();
	if($t=='CRM'){
		$clsPotential = new Potential();
		$clsCRMField = new CRMField();
		$clsCRMFieldValue = new CRMFieldValue();
		$clsCRMFieldGroup = new CRMFieldGroup();
		/* Variable */
		$user_id = (int) Input::get('u',0);
		$type = Input::get('type','excel');
		
		$creator = 'Travel Master';
		$titlePage = 'Worksheet';
		define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		$callStartTime = microtime(true);
		$objPHPExcel = new PHPExcel();
		$wizard = new PHPExcel_Helper_HTML();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($creator)
				->setLastModifiedBy("")
				->setTitle($titlePage)
				->setSubject($titlePage)
				->setDescription($titlePage)
				->setKeywords($titlePage)
				->setCategory($creator);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Type');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Status');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Priority');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Email');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Phone');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Address');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Admin ID');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Client ID');
		$columnEnd = 'I';
		if($type != 'blank'){
			$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Update Date');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Create Date');
			$columnEnd = 'K';
		}
		// Custom Field
		$arrCRMField = array();
		$lstFieldGroup = $clsCRMFieldGroup->GetAll("is_trash=0 and status='1' order by order_no ASC", $clsCRMFieldGroup->pkey);
		if(!empty($lstFieldGroup)){ $ii = ($type=='blank') ? 9 : 11; // Start column
			foreach($lstFieldGroup as $fieldgroup){
				$fieldgroup_id = $fieldgroup[$clsCRMFieldGroup->pkey];
				$lstCRMField = $clsCRMField->GetAll("is_trash=0 and fieldgroup_id='{$fieldgroup_id}' 
				and status='1' order by order_no ASC", "crm_field_id,value");
				if(!empty($lstCRMField)){
					foreach($lstCRMField as $field){
						$crm_field_id = $field[$clsCRMField->pkey];
						$column_name = CRM::genColumn($ii);
						$columnEnd = $column_name;
						$arrCRMField[] = array(
							'crm_field_id'	=> $crm_field_id,
							'column_name'	=> $column_name
						);
						$value = @json_decode($field['value'], true);
						$objPHPExcel->getActiveSheet()->setCellValue($column_name.'1',$value['name']);
						++$ii;
					}
				}
			}
		}
		// Set Autosize
		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
		foreach(range('A',$columnEnd) as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A:'.$columnEnd)->getNumberFormat()->setFormatCode(
			PHPExcel_Style_NumberFormat::FORMAT_TEXT
		);
		// Loop
		if($type != 'blank'){
			$cond = "is_trash=0";
			if($user_id > 0){
				$cond .= " and user_id='{$user_id}'";
			}
			$lstPotential = $clsPotential->GetAll("{$cond} order by reg_date ASC");
			if(!empty($lstPotential)){ $rowbegin = 2; // Init
				foreach($lstPotential as $potential){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowbegin, $potential['name']);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowbegin, $clsProperty->getTitle($potential['type_id']));
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowbegin, $clsProperty->getTitle($potential['status_id']));
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowbegin, $clsPotential->getPrioritySimple($potential['priority'],$potential));
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowbegin, $potential['email']);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowbegin, $potential['phone']);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowbegin, $potential['address']);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowbegin, $potential['admin_id']);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowbegin, $potential['client_id']);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowbegin, $clsISO->convertTimeToText($potential['upd_date'], true));
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$rowbegin, $clsISO->convertTimeToText($potential['reg_date'], true));
					// Custom Field
					foreach($arrCRMField as $field){
						$crm_field_id = $field['crm_field_id'];
						$column_name = $field['column_name'];
						$field_value = $clsCRMFieldValue->getValue($crm_field_id,$potential[$clsPotential->pkey],"");
						$objPHPExcel->getActiveSheet()->setCellValue($column_name.$rowbegin,$field_value);
					}
					++$rowbegin;
				}
			}
		}
		if($type=='csv'){
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment;filename="ExportCRM_'.date('dmY',time()).'.csv');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
		}else{
			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle($titlePage);
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			// Redirect output to a client's web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="ExportCRM_'.date('dmY',time()).'.xls');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		}
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}
	if($t=='listReportSeo'){
		$clsVS_Issue = new VS_Issue();
		$clsVS_Admin = new VS_Admin();
		$clsVS_Project = new VS_Project();
		/* Variable */
		$user_id = (int) Input::get('u',0);
		$issue_id = (int) Input::get('issue_id',0);
		$start_date = Input::get('start_date',0);
		$end_date = Input::get('end_date',0);
		$start_date = $clsISO->convertTextToTime($start_date);
		$end_date = $clsISO->convertTextToTime($end_date,"23:59:59");
		$oneIssue = $clsVS_Issue->getOne($issue_id);
		$info_report_seo = json_decode($oneIssue['info_report_seo'],true);
		
		#
		require 'vendor/autoload.php';
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$tblBackgroundHeader = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => '8BD160'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$sheet->setCellValue('A1', 'TỔNG HỢP THÔNG TIN BÀI VIẾT');
		$sheet->mergeCells('A1'.':F1');
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->setCellValue('B2', 'Dự án:');
		$sheet->setCellValue('C2', $clsVS_Project->getTitle($oneIssue['project_id']));
		$sheet->setCellValue('B3', 'Thời gian:');
		$sheet->setCellValue('C3', $clsISO->convertTimeToText($start_date).(!empty($end_date)?' -> '.$clsISO->convertTimeToText($end_date):''));
		$sheet->setCellValue('B4', 'Người thực hiện:');
		$sheet->setCellValue('C4', $clsVS_Admin->getFullName($oneIssue['assigned_to_id']));
		$idx=5;
		$sheet->setCellValue('A'.$idx, 'No.');
		$sheet->setCellValue('B'.$idx, __('reporting date'));
		$sheet->setCellValue('C'.$idx, __("LinkClient"));
		$sheet->setCellValue('D'.$idx, __("LinkAdmin"));
		$sheet->setCellValue('E'.$idx, __("approval status"));
		$sheet->setCellValue('F'.$idx, __("payment status"));
		$sheet->getStyle('A'.$idx.':F'.$idx)->applyFromArray($tblBackgroundHeader);
		$columnEnd = 'F';
		foreach(range('A',$columnEnd) as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$idx=6;$k = 1;
		if(!empty($info_report_seo)){
			foreach($info_report_seo as $report_seo_id=>$oneReportSeo){
				$valid=1;
				if(!empty($start_date)){
					if($oneReportSeo['reg_date']<$start_date) $valid = 0;
				}
				if(!empty($end_date)){
					if($oneReportSeo['reg_date']>$end_date) $valid = 0;
				}
				if($valid){
					$sheet->setCellValue('A'.$idx, $k);
					$sheet->setCellValue('B'.$idx, $clsISO->convertTimeToText($oneReportSeo['reg_date']));
					$sheet->setCellValue('C'.$idx, html_entity_decode($oneReportSeo['link_client']));
					$sheet->setCellValue('D'.$idx, html_entity_decode($oneReportSeo['link_admin']));
					$sheet->setCellValue('E'.$idx, ($oneReportSeo['is_done']?__('Approved'):__('NotApproved')));
					$sheet->setCellValue('F'.$idx, ($oneReportSeo['is_paid']?__('paid'):__('unpaid')));
					++$idx;++$k;	
				}
			}
		}
		$titlePage = 'List ReportSeo';
		$sheet->setTitle($titlePage);
		#
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="ExportListReportSeo_'.date('dmYHi').'.xlsx');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public');
		//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		ob_end_clean();
		$writer->save('php://output');
		
		exit;
		
		
		
		#
		$creator = 'Okrs';
		$titlePage = 'List ReportSeo';
		
		
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($creator)
				->setLastModifiedBy("")
				->setTitle($titlePage)
				->setSubject($titlePage)
				->setDescription($titlePage)
				->setKeywords($titlePage)
				->setCategory($creator);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet->setCellValue('A1', 'No.');
		$sheet->setCellValue('B1', __('reporting date'));
		$sheet->setCellValue('C1', __("LinkClient"));
		$sheet->setCellValue('D1', __("LinkAdmin"));
		$sheet->setCellValue('E1', __("approval status"));
		$sheet->setCellValue('F1', __("payment status"));
		$columnEnd = 'F';
		// Set Autosize
		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
		foreach(range('A',$columnEnd) as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A:'.$columnEnd)->getNumberFormat()->setFormatCode(
			PHPExcel_Style_NumberFormat::FORMAT_TEXT
		);
		#
		$idx=2;$k = 1;
		if(!empty($info_report_seo)){
			foreach($info_report_seo as $report_seo_id=>$oneReportSeo){
				$valid=1;
				if(!empty($start_date)){
					if($oneReportSeo['reg_date']<$start_date) $valid = 0;
				}
				if(!empty($end_date)){
					if($oneReportSeo['reg_date']>$end_date) $valid = 0;
				}
				if($valid){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $k);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$idx, $clsISO->convertTimeToText($oneReportSeo['reg_date']));
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$idx, $oneReportSeo['link_client']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$idx, $oneReportSeo['link_admin']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$idx, ($oneReportSeo['is_done']?__('Approved'):__('NotApproved')));
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$idx, ($oneReportSeo['is_paid']?__('paid'):__('unpaid')));
					++$idx;++$k;	
				}
			}
		}
		$objPHPExcel->getActiveSheet()->setTitle($titlePage);
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="ExportListReportSeo_'.date('dmYHi').'.xls');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}
	if($t=='exportAccDebt'){
		$clsVS_Money = new VS_Money();
		$clsVS_Client = new VS_Client();
		$clsVS_Admin = new VS_Admin();
		$user_id = (int) Input::get('u',0);
		$start_date = Input::get('start_date', '');
		$due_date = Input::get('due_date', '');
		$client_id = (int) Input::get('client_id' , 0);
		$status_id = (int) Input::get('status_id', 3);//-1
		$register_id = (int) Input::get('register_id' , 0);
		$start_date = $clsISO->convertTextToTime($start_date);
		$due_date = $clsISO->convertTextToTime($due_date);
		if(empty($start_date)) $start_date = strtotime('first day of january this year');
		if(empty($due_date)) $due_date = strtotime('last day of december this year');
		//ini_set('display_errors',1);
		#
		require 'vendor/autoload.php';
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$tblBackgroundHeader = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => '8BD160'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$tblBackgroundCus = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => 'd9d9d9'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$sheet->setCellValue('B1', 'Công Nợ');
		//$sheet->mergeCells('A1'.':F1');
		//$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('B1')->getFont()->setBold(true);
		$sheet->setCellValue('B2', 'Thời gian:');
		$sheet->setCellValue('C2', $clsISO->convertTimeToText($start_date).(!empty($due_date)?' -> '.$clsISO->convertTimeToText($due_date):''));
		$idx=3;
		if(!empty($client_id)){
			$sheet->setCellValue('B'.$idx, 'Công ty/doanh nghiệp:');
			$sheet->setCellValue('C'.$idx, $clsVS_Client->getIdentity($client_id));
			$idx++;
		}
		
		if(1){//!empty($status_id)
			switch ($status_id) {
			  case "-1":
				$payment_status = __('All');
				break;
			  case "0":
				$payment_status = __('Unpaid');
				break;
			  case "1":
				$payment_status = __('Paid');
				break;
			  case "2":
				$payment_status = __('Deposit');
				break;
			  case "3":
				$payment_status = __('Missing');
				break;
			  default:
				$payment_status = __('All');
			}
			$sheet->setCellValue('B'.$idx, __('payment status'));
			$sheet->setCellValue('C'.$idx, $payment_status);
			$idx++;
		}
		//echo $payment_status; die;
		if(!empty($register_id)){
			$sheet->setCellValue('B'.$idx, 'Đăng ký mới');
			$sheet->setCellValue('C'.$idx, $clsVS_Admin->getFullName($register_id));
			$idx++;
		}
		#
		$cond = "`is_trash`=0 and (`reg_date` between {$start_date} AND {$due_date})";
		if($client_id > 0){
			$cond .= " and `client_id`='{$client_id}'";
		}
		$cond2 = $cond;
		$cond .= " and gr='CONGNO'";
		$cond2 .= " and `gr`='THUCTHU' and `is_done`=1";
		if($register_id > 0){
			$cond .= " and `tp`='order' and money_id in (select money_id from _crm_orders_product where revenue_taker_user_id={$register_id})";//created_user_id
			$cond2 .= " and `tp`='order' and parent_id in (select money_id from _crm_orders_product where revenue_taker_user_id={$register_id})";//created_user_id
		}
		if($status_id > -1) {
			if($status_id == 3){
				$cond .= " and (`is_done`='0' OR `is_done`='2')";
			} else {
				$cond .= " and `is_done`='{$status_id}'";
			}
		}
		$list_moneys = array();
		$list_customers = array();
		$list_cus = array();
		$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
		$limitCond = '';
		//$clsVS_Money->setDebug(1);
		$list_cus = $clsVS_Money->getAll($cond,"distinct client_id");
		$tmp = $clsVS_Money->getAll($cond." order by reg_date DESC".$limitCond);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$client_id = $val['client_id'];
				/*if(!in_array($client_id,$lstClientID)){
					$lstClientID[] = $client_id;
				}*/
				$total =  $clsISO->parsePriceDecimal($val['total']);
				$total_paid = $clsVS_Money->getTotalPaid($val['tp'], 'THUCTHU', $val[$clsVS_Money->pkey], false);
				if($val['tp'] == 'renew'){
					$totalPT = $clsVS_Money->getTotal($val['money_id'], $val['resource_id'], $client_id);
					$total_balance = $totalPT - $total_paid;
					$flag = ($total > $totalPT) ? 1 : 0;
					$val['flag'] = $flag;
					$val['totalPT'] = $totalPT;
					if($totalPT==0){
						//continue;
					}
				} else {
					$total_balance = $total - $total_paid;
				}
				$val['total_paid'] = $total_paid;
				$val['total_balance'] = $total_balance;


				if(array_key_exists($client_id, $list_moneys)){
					$list_moneys[$client_id][] = $val;
					$list_customers[$client_id]['total'] += $total;
					$list_customers[$client_id]['total_paid'] += $total_paid;
					$list_customers[$client_id]['total_balance'] += $total_balance;
				} else {
					$list_moneys[$client_id][] = $val;
					$list_customers[$client_id] = array(
						'name' => $clsVS_Client->getIdentity($client_id),
						'total' => $total,
						'total_paid' => $total_paid,
						'total_balance' => $total_balance
					) ;
				}
			}
		}
		//$clsISO->pre($list_moneys);die('updating....');
		//$clsISO->pre($list_customers);die;
		//$clsISO->pre($list_cus);die;
		
		$sheet->setCellValue('A'.$idx, 'No.');
		$sheet->setCellValue('B'.$idx, __('CreatedDate'));
		$sheet->setCellValue('C'.$idx, __("TotalMoney"));
		$sheet->setCellValue('D'.$idx, __("Paid"));
		$sheet->setCellValue('E'.$idx, __("Balance"));
		$sheet->setCellValue('F'.$idx, __("Type"));
		$sheet->setCellValue('G'.$idx, __("Status"));
		$sheet->setCellValue('H'.$idx, __("CreatedBy"));
		$sheet->getStyle('A'.$idx.':H'.$idx)->applyFromArray($tblBackgroundHeader);
		$columnEnd = 'H';
		foreach(range('A',$columnEnd) as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$sheet->getColumnDimension("A")->setWidth(60);
		$idx++;$k = 1;
		$TotalMoney = 0;
		$TotalPaid = 0;
		$TotalBalance = 0;
		if(!empty($list_moneys)){
			foreach($list_moneys as $_client_id=>$_list_items){
				$TotalMoney += $list_customers[$_client_id]['total'];
				$TotalPaid += $list_customers[$_client_id]['total_paid'];
				$TotalBalance += $list_customers[$_client_id]['total_balance'];
				$sheet->setCellValue('A'.$idx, $list_customers[$_client_id]['name']);
				//$sheet->mergeCells('A'.$idx.':B'.$idx);
				$sheet->setCellValue('C'.$idx, $clsISO->formatCurrency($list_customers[$_client_id]['total']));
				$sheet->setCellValue('D'.$idx, $clsISO->formatCurrency($list_customers[$_client_id]['total_paid']));
				$sheet->setCellValue('E'.$idx, $clsISO->formatCurrency($list_customers[$_client_id]['total_balance']));
				$sheet->getStyle('A'.$idx.':H'.$idx)->applyFromArray($tblBackgroundCus);
				$idx++;
				if(!empty($_list_items)){
					foreach($_list_items as $ki=>$oneItem){
						$_tp = $oneItem['tp'];
						$_mid = $oneItem['money_id'];
						$_resource_id = $oneItem['resource_id'];
						$sheet->setCellValue('A'.$idx, '#'.$_mid);
						$sheet->setCellValue('B'.$idx, $clsISO->convertTimeToText($oneItem['reg_date'], true));
						if($_tp =='renew'){
							if($oneItem['flag']==1){
								$sheet->setCellValue('C'.$idx, $clsISO->formatCurrency($oneItem['totalPT']).'/'.$clsISO->formatCurrency($oneItem['total']));
							}else{
								$sheet->setCellValue('C'.$idx, $clsISO->formatCurrency($oneItem['total']));
							}
						}else{
							$sheet->setCellValue('C'.$idx, $clsISO->formatCurrency($oneItem['total']));
						}
						$sheet->setCellValue('D'.$idx, $clsISO->formatCurrency($oneItem['total_paid']));
						$sheet->setCellValue('E'.$idx, $clsISO->formatCurrency($oneItem['total_balance']));
						$sheet->setCellValue('F'.$idx, $clsVS_Money->getType($_tp,1));
						$sheet->setCellValue('G'.$idx, $clsVS_Money->getStatus($oneItem['is_done'],1));
						$sheet->setCellValue('H'.$idx, $clsVS_Admin->getFullName($oneItem['user_id']));
						$idx++;
					}
				}
			}
		}
		$sheet->setCellValue('A'.$idx, 'Tổng');
		$sheet->mergeCells('A'.$idx.':B'.$idx);
		$sheet->getStyle('A'.$idx)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
		$sheet->getStyle('A'.$idx)->getFont()->setBold(true);
		$sheet->setCellValue('C'.$idx, $clsISO->formatCurrency($TotalMoney));
		$sheet->setCellValue('D'.$idx, $clsISO->formatCurrency($TotalPaid));
		$sheet->setCellValue('E'.$idx, $clsISO->formatCurrency($TotalBalance));
		
		
		$titlePage = 'AccDebt';
		$sheet->setTitle($titlePage);
		#
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="exportAccDebt_'.date('dmYHi').'.xlsx');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public');
		//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		ob_end_clean();
		$writer->save('php://output');
		
		exit;
	}
if($t=='exportHistoryPayment'){
		$clsVS_Money = new VS_Money();
		$clsVS_Client = new VS_Client();
		$clsPotential = new Potential();
		$clsVS_Admin = new VS_Admin();
		$clsProperty = new Property();
		$user_id = (int) Input::get('u',0);
		$start_date = Input::get('start_date', '');
		$due_date = Input::get('due_date', '');
		$start_date = $clsISO->convertTextToTime($start_date);
		$due_date = $clsISO->convertTextToTime($due_date);
		if(empty($start_date)) $start_date = strtotime('first day of january this year');
		if(empty($due_date)) $due_date = strtotime('last day of december this year');
		require 'vendor/autoload.php';
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$tblBackgroundHeader = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => '8BD160'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$tblBackgroundCus = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => 'd9d9d9'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$sheet->setCellValue('B1', 'Thanh toán');
		//$sheet->mergeCells('A1'.':F1');
		//$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('B1')->getFont()->setBold(true);
		$sheet->setCellValue('B2', 'Thời gian:');
		$sheet->setCellValue('C2', $clsISO->convertTimeToText($start_date).(!empty($due_date)?' -> '.$clsISO->convertTimeToText($due_date):''));
		$idx=3;
		$cond = "`is_trash`=0 and `gr`='THUCTHU' and `is_done`=1 and (`paid_date` between {$start_date} AND {$due_date})";// and `client_id`={$client_id}
		$list_payments = $clsVS_Money->getAll($cond." order by paid_date DESC");
		#
		$idx++;
		$sheet->setCellValue('A'.$idx, '');//No.
		$sheet->setCellValue('B'.$idx, __('Date'));
		$sheet->setCellValue('C'.$idx, __("Customer"));
		$sheet->setCellValue('D'.$idx, __("CompanyName"));
		$sheet->setCellValue('E'.$idx, __("Amount"));
		$sheet->setCellValue('F'.$idx, __("PaymentMethod"));
		$sheet->getStyle('A'.$idx.':F'.$idx)->applyFromArray($tblBackgroundHeader);
		$columnEnd = 'F';
		foreach(range('A',$columnEnd) as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$sheet->getColumnDimension("A")->setWidth(60);
		$idx++;
		if(!empty($list_payments)){
			foreach($list_payments as $k=>$one){
				$potential_id = $clsVS_Client->getPotential($one['client_id']);
				if($potential_id){
					$customer = $clsPotential->getName($potential_id);
					$company_name = $clsPotential->getOneField("companyname",$potential_id);
				}else{
					$customer = $clsVS_Client->getClientFullName($one['client_id']);
					$company_name = $clsVS_Client->getClientName($one['client_id']);
				}
				//$sheet->setCellValue('A'.$idx, '');//No.
				$sheet->setCellValue('B'.$idx, $clsISO->convertTimeToText($one['paid_date']));
				$sheet->setCellValue('C'.$idx, $customer);
				$sheet->setCellValue('D'.$idx, $company_name);
				$sheet->setCellValue('E'.$idx, $one['total']);
				$sheet->setCellValue('F'.$idx, $clsProperty->getTitle($one['paymentmethod']));
				$idx++;
			}
		}
	
		$titlePage = 'exportHistoryPayment';
		$sheet->setTitle($titlePage);
		#
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="exportHistoryPayment_'.date('dmYHi').'.xlsx');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public');
		//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		ob_end_clean();
		$writer->save('php://output');
		
		exit;
	}	
	if($t=='exportAccOrderReg'){
		$clsVS_Addon = new VS_Addon();
		$clsVS_Client = new VS_Client();
		$clsPotential = new Potential();
		$clsVS_SopRenew = new VS_SopRenew();
		$clsVS_Product = new VS_Product();
		$clsVS_Hosting = new VS_Hosting();
		$clsVS_Domain = new VS_Domain();
		$clsOrder = new Order();
		$clsOrderProduct = new OrderProduct();
		$clsVS_Admin = new VS_Admin();
		ini_set('display_errors',1);
		#
		//$user_id = (int) Input::get('u',0);
		$start_date = Input::get('start_date', '');
		$due_date = Input::get('due_date', '');
		$status_register = Input::get('status_register',-1);
		$user_id = (int) Input::get('user_id' , 0);
		$payment_status = (int) Input::get('payment_status' , -1);
		$start_date = $clsISO->convertTextToTime($start_date);
		$due_date = $clsISO->convertTextToTime($due_date);
		#
		require 'vendor/autoload.php';
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$tblBackgroundHeader = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => '8BD160'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$tblBackgroundCus = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => 'd9d9d9'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$sheet->setCellValue('B1', 'Đăng ký mới');
		//$sheet->mergeCells('A1'.':F1');
		//$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('B1')->getFont()->setBold(true);
		$sheet->setCellValue('B2', 'Thời gian:');
		$sheet->setCellValue('C2', $clsISO->convertTimeToText($start_date).(!empty($due_date)?' -> '.$clsISO->convertTimeToText($due_date):''));
		$idx=3;
		if(1){//!empty($status_id)
			switch ($status_register) {
			  case "-1":
				$status_reg = __('All');
				break;
			  case "0":
				$status_reg = 'Pending';
				break;
			  case "1":
				$status_reg = 'Đã đăng ký';
				break;
			  case "2":
				$status_reg = 'Required';
				break;
			  default:
				$status_reg = __('All');
			}
			$sheet->setCellValue('B'.$idx, __('ServiceStatus'));
			$sheet->setCellValue('C'.$idx, $status_reg);
			$idx++;
		}
		if(1){//!empty($status_id)
			switch ($payment_status) {
			  case "-1":
				$payment_stt = __('All');
				break;
			  case "0":
				$payment_stt = __('Unpaid');
				break;
			  case "1":
				$payment_stt = __('Paid');
				break;
			  case "2":
				$payment_stt = __('Deposit');
				break;
			  case "3":
				$payment_stt = 'Pending';
				break;
			  default:
				$payment_stt = __('All');
			}
			$sheet->setCellValue('B'.$idx, __('payment status'));
			$sheet->setCellValue('C'.$idx, $payment_stt);
			$idx++;
		}
		//echo $payment_status; die;
		if(!empty($user_id)){
			$sheet->setCellValue('B'.$idx, 'Người đăng ký');
			$sheet->setCellValue('C'.$idx, $clsVS_Admin->getFullName($user_id));
			$idx++;
		}
		#
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
		$cond = "1=1";
		if($status_register > -1){
			$cond .= " and status_register={$status_register}";
		}
		if(!empty($start_date)){
			$cond .= " and created_date >= {$start_date}";
		}
		if(!empty($due_date)){
			$cond .= " and created_date <= {$due_date}";
		}
		if($user_id > 0){
			$cond .= " and `created_user_id`='{$user_id}'";
		}
		if($payment_status > -1){
			if($payment_status==3){//pending
				$cond .= " and `money_id`=0";
			}else{
				$cond .= " and `money_id` IN(select money_id from _crm_money where is_done={$payment_status})";
			}
		}
		$assoc_register = $dbconn->getAssoc("SELECT pid,created_date,created_user_id,revenue_taker_user_id,potential_id,orderid,total_grand,order_id,money_id,status_register,status_acc,id as order_product_id FROM _crm_orders_product WHERE {$cond}");
			 //$smarty->assign('assoc_register',$assoc_register);
		//$clsISO->pre($db_register);die;
		$db_register = $dbconn->getAll("SELECT `type`,`pid`,`id` FROM _crm_orders_product WHERE {$cond}");

		$domainids ='';
		$hostingids='';
		$vasids = array();
		foreach ($db_register as $item){
			if($item['type']=='Hosting'){
				$hostingids .= $item['pid'].',';
			}
			if($item['type']=='Domain'){
				$domainids .= $item['pid'].',';
			}
			if($item['type']=='VAS'){
				$vasids[] = $item['id'];
			}
		}

		$hostingids = $hostingids !='' ? rtrim($hostingids,',') : '';
		$domainids = $domainids !='' ? rtrim($domainids,',') : '';
		$datatsore = array();
		if($hostingids !=''){
			$datas = $whmcs_db->getAll("select tblhosting.*,tblproductgroups.name as group_name,tblproductgroups.id AS group_id,tblproducts.name," . "(SELECT CONCAT(name,'|',ipaddress,'|',hostname) FROM tblservers WHERE tblservers.id=tblhosting.server) AS serverdetails," . "(SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tblhosting.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname from tblhosting INNER JOIN tblproducts ON tblproducts.id=tblhosting.packageid INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid where tblhosting.id IN(".$hostingids.")");
			/*if($adminid==31){
				$clsISO->pre($datas);die;
			}*/
			foreach($datas as $data){
				$status_go =  $data['domainstatus'];
				$billingcycle =  $data['billingcycle'];
				$pid = $data["packageid"];
				$name = $data["name"];
				if(empty($name)){
					$name = $clsVS_Product->getName($pid);
				}
				$group_name = $data['group_name'];
				$domain = $data["domain"];
				$recurringamount = $clsISO->formatCurrency($data["amount"])->toFull();
				$billingcycle = $data["billingcycle"];
				$nextduedate = $data["nextduedate"];
				$serverdetails = $data["serverdetails"];
				$datatsore[] = array(
					'id'	=> $data['id'],
					'pid'	=> $data['id'],
					'type'	=> 'Hosting',
					'client_id'	=> $data['userid'],
					'client_name'	=> $clsVS_Client->getIdentity($data['userid'],false),
					'potential_name'	=> $clsPotential->getPotential($assoc_register[$data['id']]['potential_id'],false),
					'name'	=> $name,
					'domain'	=> $domain,
					'group_name'	=> $group_name,
					'nextduedate'	=> fromMySQLDate($nextduedate),
					'status_go'=>$status_go,
					'billingcycle'=>$billingcycle,
					'status'	=> $clsVS_SopRenew->renderHtmlStatus($data['domainstatus']),
					'statusid'	=>$data['domainstatus'],
					'recurringamount'=>$recurringamount,
					'reg_date'=>strtotime($data['regdate']),
				);
			}
		}

		if($domainids !=''){
			$datas = $whmcs_db->getAll("select tbldomains.*,(SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tbldomains.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname from tbldomains where tbldomains.id IN(".$domainids.")");
			/*if($adminid==31){
				$clsISO->pre($datas);die;
			}*/
			foreach($datas as $data){
				$status_go =  $data['status'];
				$billingcycle =  $data['registrationperiod'].' year';
				$group_name = __('Domain');
				$name = $data["name"];
				$domain = $data["domain"];
				$recurringamount = $clsISO->formatCurrency($data["recurringamount"])->toFull();
				$nextduedate = $data["nextduedate"];
				$datatsore[] = array(
					'id'	=> $data['id'],
					'pid'	=> $data['id'],
					'type'	=> 'Domain',
					'client_id'	=> $data['userid'],
					'client_name'	=> $clsVS_Client->getIdentity($data['userid'],false),
					'potential_name'	=> $clsPotential->getPotential($assoc_register[$data['id']]['potential_id'],false),
					'name'	=> $name,
					'domain'	=> $domain,
					'group_name'	=> $group_name,
					'nextduedate'	=> fromMySQLDate($nextduedate),
					'status_go'=>$status_go,
					'billingcycle'=>$billingcycle,
					'status'	=> $clsVS_SopRenew->renderHtmlStatus($data['status']),
					'statusid'	=>$data['status'],
					'recurringamount'=>$recurringamount,
					'reg_date'=>strtotime($data['registrationdate'])
				);
			}
		}
		if(!empty($vasids)){
			foreach($vasids as $order_product_id){
				$oneOP = $clsOrderProduct->getOne($order_product_id);
				$order_detail = json_decode($oneOP['order_detail'],true);
				$status = 'Pending';
				if($oneOP['status_register']==1){
					$status = 'Đã đăng ký';
				}elseif($oneOP['status_register']==2){
					$status = 'Required';
				}
				$datatsore[] = array(
					'id'	=> 0,
					'pid'	=> 0,
					'type'	=> 'VAS',
					'client_id'	=> $oneOP['client_id'],
					'client_name'	=> $clsVS_Client->getIdentity($oneOP['client_id'],false),
					'potential_name'	=> $clsPotential->getPotential($oneOP['potential_id'],false),
					'name'	=> $order_detail['s_name'],
					'domain'	=> $order_detail['domain'],
					'group_name'	=> $order_detail['group_name'],
					'nextduedate'	=> '',
					'status_go'=>'',
					'billingcycle'=>$order_detail['billingcycle'],
					'status'	=> $status,
					'statusid'	=>$oneOP['status_register'],
					'recurringamount'=>0,
					'oneOP'	=>$oneOP,
					'reg_date'	=>$oneOP['created_date'],
					//'note'	=>$clsOrder->getOneField('note',$oneOP['order_id']),
				);
			}
		}
		uasort($datatsore, "compare_regdate_desc");
		$lstItem=array();
		if(!empty($datatsore)){
			foreach(toArray($datatsore) as $k=>$val){
				//$dataCount= $val['statusid'];
				$client_id = $val['client_id'];
				$client_name = $val['client_name'];
				$potential_name = $val['potential_name'];
				if(!in_array($client_id,array_keys($lstItem))){
					$lstItem[$client_id] = array(
						'client_id'	=> $client_id,
						'client_name'	=> $client_name,
						'potential_name'	=> $potential_name,
						'items'	=> array($val)
					);
				}else{
					$lstItem[$client_id]['items'][] = $val;
				}
			}
		}
		$dataCount = COUNT(toArray($datatsore));
		//$smarty->assign('lstItem',$lstItem);
		//$clsISO->pre($lstItem);die;
		$sheet->setCellValue('A'.$idx, 'No.');
		$sheet->setCellValue('B'.$idx, __('Service name'));
		$sheet->mergeCells('B'.$idx.':C'.$idx);
		$sheet->setCellValue('D'.$idx, __("Total"));
		$sheet->setCellValue('E'.$idx, __("Next duedate"));
		$sheet->setCellValue('F'.$idx, __("Status"));
		$sheet->setCellValue('G'.$idx, __("Payment"));
		$sheet->setCellValue('H'.$idx, __("Saler"));
		$sheet->setCellValue('I'.$idx, __("UserCreated"));
		$sheet->setCellValue('J'.$idx, __("CreatedDate"));
		$sheet->setCellValue('K'.$idx, __("Notes"));
		$sheet->getStyle('A'.$idx.':K'.$idx)->applyFromArray($tblBackgroundHeader);
		$columnEnd = 'K';
		foreach(range('A',$columnEnd) as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$sheet->getColumnDimension("A")->setWidth(60);
		$idx++;$k = 1;
		if(!empty($lstItem)){
			foreach($lstItem as $k=>$oneItem){
				$items = $oneItem['items'];
				$sheet->setCellValue('A'.$idx, $oneItem['potential_name']);
				//$sheet->mergeCells('A'.$idx.':J'.$idx);
				$sheet->getStyle('A'.$idx.':J'.$idx)->applyFromArray($tblBackgroundCus);
				$idx++;
				if(!empty($items)){
					foreach($items as $k=>$oneI){
						if($oneI['type']=='VAS'){
							$oneOP = $oneI['oneOP'];
							$order_product_id = $oneOP['id'];
							$_status = 'Pending';
							if($oneOP['status_register']==1){
								$_status = 'Đã đăng ký';
							}elseif($oneOP['status_register']==2){
								$_status = 'Required';
							}
							$sheet->setCellValue('A'.$idx, $oneOP['money_id']?'P':'');//✓
							$sheet->getStyle('A'.$idx)->getFont()->setName('Wingdings 2')->setSize(12)->setBold(true);
							$sheet->setCellValue('B'.$idx, $oneI['group_name'].':'.$oneI['name']."\r\n"."#".$oneOP['order_id']." ".$oneI['domain']);
							$sheet->getStyle('B'.$idx)->getAlignment()->setWrapText(true);
							$sheet->mergeCells('B'.$idx.':C'.$idx);
							$sheet->setCellValue('D'.$idx, $clsISO->formatCurrency($oneOP['total_grand']));
							$sheet->setCellValue('E'.$idx, $oneI['billingcycle']."\r\n".$oneI['nextduedate']);
							$sheet->getStyle('E'.$idx)->getAlignment()->setWrapText(true);
							$sheet->setCellValue('F'.$idx, $_status);
							$sheet->setCellValue('G'.$idx, $clsOrderProduct->getStatusPayment($order_product_id,$oneOP,1));
							$sheet->setCellValue('H'.$idx, $clsVS_Admin->getFullName($oneOP['revenue_taker_user_id']));
							$sheet->setCellValue('I'.$idx, $clsVS_Admin->getFullName($oneOP['created_user_id']));
							$sheet->setCellValue('J'.$idx, $clsISO->convertTimeToText($oneOP['created_date']));
							$sheet->setCellValue('K'.$idx, strip_tags(html_entity_decode($clsOrder->getOneField('note',$oneOP['order_id']))));
						}else{
							$_status = 'Pending';
							if($assoc_register[$oneI['pid']]['status_register']==1){
								$_status = 'Đã đăng ký';
							}elseif($assoc_register[$oneI['pid']]['status_register']==2){
								$_status = 'Required';
							}
							$sheet->setCellValue('A'.$idx, $assoc_register[$oneI['pid']]['money_id']?'P':'');//✓
							$sheet->getStyle('A'.$idx)->getFont()->setName('Wingdings 2')->setSize(12)->setBold(true);
							$sheet->setCellValue('B'.$idx, $oneI['group_name'].':'.$oneI['name']."\r\n"."#".$assoc_register[$oneI['pid']]['orderid']." ".$oneI['domain']);
							$sheet->getStyle('B'.$idx)->getAlignment()->setWrapText(true);
							$sheet->mergeCells('B'.$idx.':C'.$idx);
							
							
							$sheet->setCellValue('D'.$idx, $clsISO->formatCurrency($assoc_register[$oneI['pid']]['total_grand']));
							$sheet->setCellValue('E'.$idx, $oneI['billingcycle']."\r\n".$oneI['nextduedate']);
							$sheet->getStyle('E'.$idx)->getAlignment()->setWrapText(true);
							$sheet->setCellValue('F'.$idx, $_status);
							$sheet->setCellValue('G'.$idx, $clsOrderProduct->getStatusPayment($assoc_register[$oneI['pid']]['order_product_id'],$assoc_register[$oneI['pid']],1));
							$sheet->setCellValue('H'.$idx, $clsVS_Admin->getFullName($assoc_register[$oneI['pid']]['revenue_taker_user_id']));
							$sheet->setCellValue('I'.$idx, $clsVS_Admin->getFullName($assoc_register[$oneI['pid']]['created_user_id']));
							$sheet->setCellValue('J'.$idx, $clsISO->convertTimeToText($assoc_register[$oneI['pid']]['created_date']));
							$sheet->setCellValue('K'.$idx, strip_tags(html_entity_decode($clsOrder->getOneField('note',$assoc_register[$oneI['pid']]['order_id']))));
						}
						$idx++;
					}
				}
			}
		}
		
		$titlePage = 'AccOrderReg';
		$sheet->setTitle($titlePage);
		#
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="exportAccOrderReg_'.date('dmYHi').'.xlsx');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public');
		//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		ob_end_clean();
		$writer->save('php://output');
		
		exit;
	}
	if($t=='exportAccRenew'){
		$clsConfiguration = new Configuration();
		$clsVS_Admin = new VS_Admin();
		$clsVS_Client = new VS_Client();
		$clsVS_Domain = new VS_Domain();
		$clsVS_Server = new VS_Server();
		$clsVS_Product = new VS_Product();
		$clsVS_ProductGroup = new VS_ProductGroup();
		$clsVS_SopRenew = new VS_SopRenew();
		$clsVS_SopStatus = new VS_SopStatus();
		
		$clsVS_Addon = new VS_Addon();
		$clsVS_SopMark = new VS_SopMark();
		$clsVS_Money = new VS_Money();
		$clsOrder = new Order();
		$clsOrderProduct = new OrderProduct();
		#
		$user_id = (int) Input::get('u',0);
		$dateType = Input::get('dateType',7);
		//$yearIn = (int) Input::get('yearIn',1,true);
		$yearIn = 1;
		$clientid = (int) Input::get('clientid',0,true);
		//$productGroup = Input::get('productGroup','',true);
		$product = Input::get('product','',true);//str: 1,2
		//$clsISO->pre($product);die;
		$pserver = (int) Input::get('pserver','0',true);
		$keysearch = Input::get('keysearch','');
		$status = Input::get('status','all');
		$start_date = Input::get('start_date','');
		$end_date = Input::get('end_date','');
		if(is_numeric($dateType) && (int) $dateType >= 0){
			$from = date('Y-m-d');
			$to = date('Y-m-d',strtotime("+{$dateType} days"));
		} else if(is_numeric($dateType) && (int) $dateType < 0){
			$to = date('Y-m-d');
			$from = date('Y-m-d',strtotime("{$dateType} days"));
		} else if(is_string($dateType)) {
			if($dateType == 'expried'){
				$from = date('Y-m-d',strtotime("-{$yearIn} years"));
				$to = date('Y-m-d');
			}
			if($dateType == 'any'){
				$from = $clsISO->convertDateCreateFormat($start_date);
				$to = $clsISO->convertDateCreateFormat($end_date);
				//$clsISO->pre($from);
				//$clsISO->pre($to);die;
			}
		}
		#
		require 'vendor/autoload.php';
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$tblBackgroundHeader = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => '8BD160'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$tblBackgroundCus = array(
			'fill' => array(
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
                  	'rgb' => 'd9d9d9'
				],
             	'endColor' => [
                 	'argb' => 'FFFFFFFF'
             	]
				//'color' => array('rgb' => 'ecf5ff;')
			)
		);
		$idx=1;
		$sheet->setCellValue('B'.$idx, 'Sắp hết hạn');
		$sheet->getStyle('B'.$idx)->getFont()->setBold(true);
		$idx++;
		$sheet->setCellValue('B'.$idx, 'Thời gian:');
		$sheet->setCellValue('C'.$idx, $from.(!empty($to)?' -> '.$to:''));
		$idx++;
		if($product){
			//$onePro = $clsVS_Product->getByCond("id={$product}","name");
			$allPro = $clsVS_Product->getAll("id in ({$product})","name");
			//$clsISO->pre($allPro);die;
			$proG = array();
			if(!empty($allPro)){
				foreach($allPro as $k=>$one){
					$proG[] = $one['name'];
				}
			}
			$sheet->setCellValue('B'.$idx, 'Nhóm sản phẩm:');
			$sheet->setCellValue('C'.$idx, implode(",",$proG));
			$idx++;
		}
		if($pserver){
			$onePser = $clsVS_Server->getByCond("id={$pserver}","name");
			$sheet->setCellValue('B'.$idx, 'Server:');
			$sheet->setCellValue('C'.$idx, $onePser['name']);
			$idx++;
		}
		if($clientid){
			$onePser = $clsVS_Server->getByCond("id={$pserver}","name");
			$sheet->setCellValue('B'.$idx, 'Công ty/doanh nghiệp:');
			$sheet->setCellValue('C'.$idx, $clsVS_Client->getIdentity($clientid));
			$idx++;
		}
		if($status){
			switch ($status) {
			  case "all":
				$_status = __('All');
				break;
			  case "Pending":
				$_status = "Pending";
				break;
			  case "Active":
				$_status = "Active";
				break;
			  case "Completed":
				$_status = "Completed";
				break;
			  case "Suspended":
				$_status = "Suspended";
				break;
			  case "Terminated":
				$_status = "Terminated";
				break;
			  case "Cancelled":
				$_status = "Cancelled";
				break;
			  case "Fraud":
				$_status = "Fraud";
				break;
			  case "Expired":
				$_status = "Expired(Tên miền)";
				break;		
			  default:
				$_status = __('All');
			}
			$sheet->setCellValue('B'.$idx, 'Tình trạng:');
			$sheet->setCellValue('C'.$idx, $_status);
			$idx++;
		}
		$cond = "";
		if($clientid > 0){
			$cond .= " AND `tblclients`.`id`='{$clientid}'";
		}
		#
		$hosting_cond = " AND (`tblhosting`.`billingcycle`<>'One Time' and `tblhosting`.`billingcycle`<>'Free Account')";
		$addon_cond = " AND (`tblhostingaddons`.`billingcycle`<>'One Time' and `tblhostingaddons`.`billingcycle`<>'Free Account')";
		$domain_cond= "";
		if($dateType == "any"){
			// Some code
			//$status = Input::post('status');
			//$smarty->assign('status',$status);
			if($status != 'all'){
				$hosting_cond .= " AND `tblhosting`.`domainstatus`='{$status}'";
				$addon_cond .= " AND `tblhostingaddons`.`status`='{$status}'";
				$domain_cond .= " AND `tbldomains`.`status`='{$status}'";
			}
			if(!empty($from)){
				$hosting_cond .= " AND (STR_TO_DATE(`tblhosting`.`nextduedate`,'%Y-%m-%d') >= '{$from}')";
				$addon_cond .= " AND (STR_TO_DATE(`tblhostingaddons`.`nextduedate`,'%Y-%m-%d') >= '{$from}')";
				$domain_cond .= " AND (STR_TO_DATE(`tbldomains`.`nextduedate`,'%Y-%m-%d') >= '{$from}')";
			}
			if(!empty($to)){
				$hosting_cond .= " AND (STR_TO_DATE(`tblhosting`.`nextduedate`,'%Y-%m-%d') <= '{$to}')";
				$addon_cond .= " AND (STR_TO_DATE(`tblhostingaddons`.`nextduedate`,'%Y-%m-%d') <= '{$to}')";
				$domain_cond .= " AND (STR_TO_DATE(`tbldomains`.`nextduedate`,'%Y-%m-%d') <= '{$to}')";
			}
		} else {
			if(is_numeric($dateType) && (int) $dateType >= 0){
				$hosting_cond .= " AND (`tblhosting`.`domainstatus`='Active')";
				$addon_cond .= " AND (`tblhostingaddons`.`status`='Active')";
				$domain_cond .= " AND (`tbldomains`.`status`='Active')";
			}
			if(is_numeric($dateType) && (int) $dateType < 0){//$dateType==-30
				//$status = Input::post('status');
				//$smarty->assign('status',$status);
				if($status != 'all'){
					$hosting_cond .= " AND `tblhosting`.`domainstatus`='{$status}'";
					$addon_cond .= " AND `tblhostingaddons`.`status`='{$status}'";
					$domain_cond .= " AND `tbldomains`.`status`='{$status}'";
				}
			}
			$hosting_cond .= " AND (STR_TO_DATE(`tblhosting`.`nextduedate`,'%Y-%m-%d') BETWEEN '{$from}' and '{$to}')";// AND (`tblhosting`.`domainstatus`='Active')
			$addon_cond .= " AND (STR_TO_DATE(`tblhostingaddons`.`nextduedate`,'%Y-%m-%d') BETWEEN '{$from}' and '{$to}')";// AND (`tblhostingaddons`.`status`='Active')
			$domain_cond .= " AND (STR_TO_DATE(`tbldomains`.`nextduedate`,'%Y-%m-%d') BETWEEN '{$from}' and '{$to}')";// AND (`tbldomains`.`status`='Active')
		}
		$has_cond_addon = false;
		$has_cond_domain = false;
		if($pserver > 0){
			$has_cond_domain = true;
			$hosting_cond .= " AND `tblhosting`.`server`='{$pserver}'";
			$domain_cond .= " AND `tbldomains`.`type`='okrs'";
		}
		if(!empty($product)){
			$hosting_cond .= " AND `tblhosting`.`packageid` in ($product)";
			if($has_cond_domain == false){
				$domain_cond .= " AND `tbldomains`.`type`='okrs'";
			}
		}
		/** Get All Cients*/
		if(!empty($keysearch)){
			$hosting_cond.= " AND `tblhosting`.`domain` like '%{$keysearch}%'";
			$domain_cond.= " AND `tbldomains`.`domain` like '%{$keysearch}%'";
			$addon_cond.= " AND `tblhostingaddons`.`name` like '%{$keysearch}%'";
		}
		$user_group_id = $clsVS_Admin->getOneField('user_group_id',$user_id);
		//var_dump($user_group_id);die;
		if(in_array($user_group_id,array(USER_GROUP_SALE))||$user_id==123){//USER_GROUP_HCNS, tưởng (Duyên dùng)
			$hosting_cond .= " AND `tblhosting`.`packageid` IN (69, 70, 71, 72, 73, 172, 176, 186, 187, 196, 195, 200)";//tms
			$domain_cond = " AND 0";
			$addon_cond = " AND 0";
		}
		if($user_group_id==USER_GROUP_SALE&&!$core->_USER['admin_permiss_manage']){
			$lstClient = $dbconn->getCol("select client_id from _crm_orders_product where  created_user_id={$user_id} OR revenue_taker_user_id={$user_id}");
			//$clsISO->pre($lstClient);die;
			if(!empty($lstClient)){
				$cond .= " AND `tblclients`.`id` IN (".implode(",",$lstClient).")";
			}else{
				$cond .= " AND 0";
			}
		}
		$sql = "select * from (
			(select `tblclients`.`id`,CONCAT_WS(' ',`tblclients`.`firstname`,`tblclients`.`lastname`) as fullname,`tblclients`.`companyname`
			,`tblhosting`.`nextduedate`,@service_type := 'hosting' as `_type`,`tblhosting`.`id` as `service_id` from tblclients 
				inner join `tblhosting` on `tblclients`.`id`=`tblhosting`.`userid` 
				WHERE 1=1".$cond.$hosting_cond."
			) 
			UNION (select `tblclients`.`id`,CONCAT_WS(' ',`tblclients`.`firstname`,`tblclients`.`lastname`) as fullname,`tblclients`.`companyname`
			,`tblhostingaddons`.`nextduedate`,@service_type := 'addon' as `_type`,`tblhostingaddons`.`id` as `service_id` from `tblclients` 
				inner join `tblhostingaddons` on `tblclients`.`id`=`tblhostingaddons`.`userid` 
				WHERE 1=1".$cond.$addon_cond."
			) 
			UNION (select `tblclients`.`id`,CONCAT_WS(' ',`tblclients`.`firstname`,`tblclients`.`lastname`) as fullname,`tblclients`.`companyname`
			,`tbldomains`.`nextduedate`,@service_type := 'domain' as `_type`,`tbldomains`.`id` as `service_id` from `tblclients` 
				inner join `tbldomains` on `tblclients`.`id`=`tbldomains`.`userid` 
				WHERE 1=1".$cond.$domain_cond."
			)
		) as `tg` order by `tg`.`nextduedate` asc";
		$listVSClient = array();
		$result = $whmcs_db->Execute($sql);
		$totalR = $result->recordCount();
		if($totalR){
			while($r = $result->fetchRow()){
				$client_id = $r['id'];
				if(@array_key_exists($client_id,$listVSClient)){
					$list_hosting_id = $listVSClient[$client_id]['list_hosting_id'];
					$list_addon_id = $listVSClient[$client_id]['list_addon_id'];
					$list_domain_id = $listVSClient[$client_id]['list_domain_id'];
					if($r['_type'] == 'hosting'){
						$list_hosting_id[] = $r['service_id'];
					} else if($r['_type'] == 'addon'){
						$list_addon_id[] = $r['service_id'];
					} else if($r['_type'] == 'domain'){
						$list_domain_id[] = $r['service_id'];
					}
					$listVSClient[$client_id]['list_hosting_id'] = $list_hosting_id;
					$listVSClient[$client_id]['list_addon_id'] = $list_addon_id;
					$listVSClient[$client_id]['list_domain_id'] = $list_domain_id;
				} else {
					$list_hosting_id = array();
					$list_addon_id = array();
					$list_domain_id = array();
					if($r['_type'] == 'hosting'){
						$list_hosting_id[] = $r['service_id'];
					} else if($r['_type'] == 'addon'){
						$list_addon_id[] = $r['service_id'];
					} else if($r['_type'] == 'domain'){
						$list_domain_id[] = $r['service_id'];
					}
					$listVSClient[$client_id] = array(
						'id' => $client_id,
						'fullname' => $r['fullname'],
						'companyname' => $r['companyname'],
						'nextduedate' => $r['nextduedate'],
						'list_hosting_id' => $list_hosting_id,
						'list_addon_id' => $list_addon_id ,
						'list_domain_id' => $list_domain_id
					);
				}
			}
		}
		//$clsISO->pre($listVSClient);die;
		//$clsISO->pre($totalR);die;
		$sheet->setCellValue('A'.$idx, 'No.');
		$sheet->setCellValue('B'.$idx, "Loại dịch vụ");
		$sheet->setCellValue('C'.$idx, "Tên dịch vụ");
		$sheet->setCellValue('D'.$idx, "Thời hạn");
		$sheet->setCellValue('E'.$idx, "Đơn giá");
		$sheet->setCellValue('F'.$idx, "S.L");
		$sheet->setCellValue('G'.$idx, 'Thành Tiền');
		$sheet->setCellValue('H'.$idx, "Tổng tiền");
		$sheet->setCellValue('I'.$idx, __("Billing cycle"));
		$sheet->setCellValue('J'.$idx, __("_Status"));
		$sheet->getStyle('A'.$idx.':J'.$idx)->applyFromArray($tblBackgroundHeader);
		$columnEnd = 'J';
		foreach(range('A',$columnEnd) as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}
		$sheet->getColumnDimension("B")->setWidth(60);
		$idx++;$k = 1;
		if(!empty($listVSClient)){
			foreach($listVSClient as $client_id=>$data){
				$potential_id = $clsVS_Client->getPotential($client_id);
				//$tableProductList = $clsVS_Client->get_reg_vals($client_id,$data['list_hosting_id'],$data['list_addon_id'],$data['list_domain_id']);
				$sheet->setCellValue('A'.$idx, $k);
				$sheet->setCellValue('B'.$idx, $clsVS_Client->getIdentity($client_id));
				
				$sheet->getStyle('A'.$idx.':J'.$idx)->applyFromArray($tblBackgroundCus);
				//$tableProductList
				$taxexempt = $clsVS_Client->getOneField('taxexempt', $client_id);
				$currency = $clsISO->getCurrency($client_id);
				$list_hosting_id = $data['list_hosting_id'];
				$list_addon_id = $data['list_addon_id'];
				$list_domain_id = $data['list_domain_id'];
				$idx++;
				if(!empty($list_hosting_id)){
					$list_hostings = $whmcs_db->getAll("select tblhosting.*,tblproductgroups.id AS group_id,tblproductgroups.name as group_name,tblproducts.name, (SELECT CONCAT(name,'|',ipaddress,'|',hostname) FROM tblservers WHERE tblservers.id=tblhosting.server) AS serverdetails, (SELECT tblpaymentgateways.value FROM tblpaymentgateways WHERE tblpaymentgateways.gateway=tblhosting.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1) AS paymentmethodname from tblhosting 
						INNER JOIN tblproducts ON tblproducts.id=tblhosting.packageid 
						INNER JOIN tblproductgroups ON tblproductgroups.id=tblproducts.gid 
						where tblhosting.userid='{$client_id}' and tblhosting.id in (".implode(',', $list_hosting_id).") order by tblhosting.id ASC");
					if(!empty($list_hostings)){
						foreach($list_hostings as $data){
							$id = $data['id'];
							$userid = $data["userid"];
							$pid = $data["packageid"];
							$name = $data["name"];
							if(empty($name)){
								$name = $clsVS_Product->getName($pid);
							}
							$group_name = $data['group_name'];
							$server = $data["server"];
							$regdate = $data["regdate"];
							$op_okrs = $clsOrderProduct->getByCond("pid={$id}","order_id");
							if(!empty($op_okrs)){
								$order_id_okrs = $op_okrs['order_id'];
								$order_detail_okrs = $clsOrder->getOneField('order_detail',$order_id_okrs);
								$order_detail_okrs = json_decode($order_detail_okrs,true);
								$domain = $order_detail_okrs["domain"];
							}else{
								$validdomain = $whmcs_db->getOne("select validdomain from mod_licensing where serviceid={$id}");
								if(!empty($validdomain)){
								$validdomain = explode(',',$validdomain);
								$domain = $validdomain[0];
								}else{
									$domain = $data["domain"];
								}
							}

							$paymentmethod = $data["paymentmethod"];
							$paymentmethodname = $data["paymentmethodname"];
							$firstpaymentamount = $data["firstpaymentamount"];
							$recurringamount = $data["amount"];
							$billingcycle = $data["billingcycle"];
							$nextduedate = $data["nextduedate"];
							$domainstatus = $data["domainstatus"];
							$username = $data["username"];
							$notes = $data["notes"];
							$subscriptionid = $data["subscriptionid"];
							$promoid = $data["promoid"];
							$overideautosuspend = $data["overideautosuspend"];
							$overidesuspenduntil = $data["overidesuspenduntil"];
							$ns1 = $data["ns1"];
							$ns2 = $data["ns2"];
							$dedicatedip = $data["dedicatedip"];
							$assignedips = $data["assignedips"];
							$diskusage = $data["diskusage"];
							$disklimit = $data["disklimit"];
							$bwusage = $data["bwusage"];
							$bwlimit = $data["bwlimit"];
							$lastupdate = $data["lastupdate"];
							$serverdetails = $data["serverdetails"];
							$serverdetails = explode("|", $serverdetails);
							$status = '';
							$state = '<span class="label label-default">'.__('Unsent').'</span>';
							$have_reply = 0;
							$have_check = true;
							$list_marks = $clsVS_SopMark->getAll("is_trash=0 and type='hosting' and client_id='{$client_id}' and relid='{$id}' and nextduedate='".strtotime($nextduedate)."' order by reg_date DESC");
							//$clsISO->print_pre($list_marks);
							$status_cn = '';
							$status_pay = '';
							$status_renew = '';
							$sop_renew_id = 0;
							if(!empty($list_marks)){
								$have_check = false;
								$sop_renew_id = $list_marks[0]['sop_renew_id'];
								$oneMoney = $clsVS_Money->getByCond("tp='renew' and gr='CONGNO' and resource_id={$sop_renew_id}");
								if(!empty($oneMoney)){
									$status_cn = '<span class="label label-success mr-1">Đã tạo C.Nợ</span>';
									$status_pay = $clsVS_Money->getStatus($oneMoney['is_done']);
									if(!empty($status_pay)) $status_pay = "-".$status_pay;
									$tmp = $clsVS_SopStatus->getAll("is_trash=0 and type='hosting' and relid='".$data['id']."' 
										and money_id='{$oneMoney['money_id']}' and client_id='{$client_id}' limit 0,1", "{$clsVS_SopStatus->pkey},`status`");
									if(!empty($tmp)){
										$status_renew = $clsVS_SopStatus->getStatus('hosting', $data['id'], $tmp[0]['status'], $data['nextduedate'], 1);
										if(!empty($status_renew)) $status_renew = "-".$status_renew;
									}
								}else{
									$status_cn = '<span class="label label-warning mr-1">Chưa tạo C.Nợ</span>';
								}
							}
							$sheet->setCellValue('A'.$idx, $have_check==false?'P':'');//✓
							$sheet->getStyle('A'.$idx)->getFont()->setName('Wingdings 2')->setSize(12)->setBold(true);
							$sheet->setCellValue('B'.$idx, $group_name);
							$sheet->setCellValue('C'.$idx, $name."\r\n"."Domain: ".$domain."\r\n".strip_tags($status_cn).strip_tags($status_pay).strip_tags($status_renew));
							$sheet->getStyle('C'.$idx)->getAlignment()->setWrapText(true);
							$sheet->setCellValue('D'.$idx, $clsISO->fromMySQLDate($nextduedate));
							$sheet->setCellValue('E'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('F'.$idx, "1");
							$sheet->setCellValue('G'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('H'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('I'.$idx, $billingcycle);
							$sheet->setCellValue('J'.$idx, $data['domainstatus']);
							$idx++;
						}
					}
				}
				#Addonand
				if(!empty($list_addon_id)){
					$list_addons = $whmcs_db->getAll("select tblhostingaddons.*,(
						SELECT tblpaymentgateways.value FROM tblpaymentgateways 
						WHERE tblpaymentgateways.gateway=tblhostingaddons.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1
					) AS paymentmethodname from tblhostingaddons 
					where tblhostingaddons.userid='{$client_id}' AND tblhostingaddons.id in (".implode(',',$list_addon_id).") order by tblhostingaddons.nextduedate ASC");
					if(!empty($list_addons)){
						foreach($list_addons as $data){
							$id = $data["id"];
							$userid = $data["userid"];
							$orderid = $data["orderid"];
							$hostingid = $data["hostingid"];
							$addonid = $data["addonid"];
							$name = $data['name'];
							if(empty($name)){
								$name = $clsVS_Addon->getName($addonid);
							}
							$type = $data["type"];
							$registrationdate = $data["registrationdate"];
							$domain = $data["domain"];
							$firstpaymentamount = $data["setupfee"];
							$recurringamount = $data["recurring"];
							$billingcycle = $data["billingcycle"];
							$status = $data["status"];
							$regdate = $data["regdate"];
							$nextduedate = $data["nextduedate"];
							$nextinvoicedate = $data["nextinvoicedate"];
							$paymentmethod = $data["paymentmethod"];
							$paymentmethodname = $data["paymentmethodname"];
							$have_reply = 0;
							$have_check = true;
							$list_marks = $clsVS_SopMark->getAll("is_trash=0 and type='addon' and client_id='{$client_id}' and relid='{$id}' and nextduedate='".strtotime($nextduedate)."' order by reg_date DESC");
							$status_cn = '';
							$status_pay = '';
							$status_renew = '';
							if(!empty($list_marks)){
								$have_check = false;
								$sop_renew_id = $list_marks[0]['sop_renew_id'];
								$oneMoney = $clsVS_Money->getByCond("tp='renew' and gr='CONGNO' and resource_id={$sop_renew_id}");
								if(!empty($oneMoney)){
									$status_cn = '<span class="label label-success mr-1">Đã tạo C.Nợ</span>';
									$status_pay = $clsVS_Money->getStatus($oneMoney['is_done']);
									if(!empty($status_pay)) $status_pay = "-".$status_pay;
									$tmp = $clsVS_SopStatus->getAll("is_trash=0 and type='addon' and relid='".$data['id']."' 
										and money_id='{$oneMoney['money_id']}' and client_id='{$client_id}' limit 0,1", "{$clsVS_SopStatus->pkey},`status`");
									if(!empty($tmp)){
										$status_renew = $clsVS_SopStatus->getStatus('addon', $data['id'], $tmp[0]['status'], $data['nextduedate'], 1);
										if(!empty($status_renew)) $status_renew = "-".$status_renew;
									}
								}else{
									$status_cn = '<span class="label label-warning mr-1">Chưa tạo C.Nợ</span>';
								}
							}
							$sheet->setCellValue('A'.$idx, $have_check==false?'P':'');//✓
							$sheet->getStyle('A'.$idx)->getFont()->setName('Wingdings 2')->setSize(12)->setBold(true);
							$sheet->setCellValue('B'.$idx, "AddOn");
							$sheet->setCellValue('C'.$idx, $name."\r\n".strip_tags($status_cn).strip_tags($status_pay).strip_tags($status_renew));
							$sheet->getStyle('C'.$idx)->getAlignment()->setWrapText(true);
							$sheet->setCellValue('D'.$idx, $clsISO->fromMySQLDate($nextduedate));
							$sheet->setCellValue('E'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('F'.$idx, "1");
							$sheet->setCellValue('G'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('H'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('I'.$idx, $billingcycle);
							$sheet->setCellValue('J'.$idx, $data['status']);
							$idx++;
						}
					}
				}
				# Domain
				if(!empty($list_domain_id)){
					$list_domain = $whmcs_db->getAll("select tbldomains.*,(
						SELECT tblpaymentgateways.value FROM tblpaymentgateways 
						WHERE tblpaymentgateways.gateway=tbldomains.paymentmethod AND tblpaymentgateways.setting='name' LIMIT 1
					) AS paymentmethodname from tbldomains 
					where tbldomains.userid='{$client_id}' and tbldomains.id in (".implode(',',$list_domain_id).")");
					if(!empty($list_domain)){
						foreach($list_domain as $data){
							$id = $data["id"];
							$userid = $data["userid"];
							$orderid = $data["orderid"];
							$type = $data["type"];
							$registrationdate = $data["registrationdate"];
							$domain = $data["domain"];
							$firstpaymentamount = $data["firstpaymentamount"];
							$recurringamount = $data["recurringamount"];
							$registrar = $data["registrar"];
							$registrationperiod = $data["registrationperiod"];
							$expirydate = $data["expirydate"];
							$nextduedate = $data["nextduedate"];
							$status = $data["status"];
							$subscriptionid = $data["subscriptionid"];
							$promoid = $data["promoid"];
							$additionalnotes = $data["additionalnotes"];
							$paymentmethod = $data["paymentmethod"];
							$paymentmethodname = $data["paymentmethodname"];
							$dnsmanagement = $data["dnsmanagement"];
							$emailforwarding = $data["emailforwarding"];
							$idprotection = $data["idprotection"];
							$donotrenew = $data["donotrenew"];
							$have_reply = 0;
							$have_check = true;
							$list_marks = $clsVS_SopMark->getAll("is_trash=0 and type='domain' and client_id='{$client_id}' and relid='{$id}' and nextduedate='".strtotime($nextduedate)."' order by reg_date DESC");
							$status_cn = '';
							$status_pay = '';
							$status_renew = '';
							if(!empty($list_marks)){
								$have_check = false;
								$sop_renew_id = $list_marks[0]['sop_renew_id'];
								$oneMoney = $clsVS_Money->getByCond("is_trash=0 and tp='renew' and gr='CONGNO' and resource_id={$sop_renew_id}");
								if(!empty($oneMoney)){
									$status_cn = '<span class="label label-success mr-1">Đã tạo C.Nợ</span>';
									$status_pay = $clsVS_Money->getStatus($oneMoney['is_done']);
									if(!empty($status_pay)) $status_pay = "-".$status_pay;
									$tmp = $clsVS_SopStatus->getAll("is_trash=0 and type='domain' and relid='".$data['id']."' 
										and money_id='{$oneMoney['money_id']}' and client_id='{$client_id}' limit 0,1", "{$clsVS_SopStatus->pkey},`status`");
									if(!empty($tmp)){
										$status_renew = $clsVS_SopStatus->getStatus('domain', $data['id'], $tmp[0]['status'], $data['nextduedate'], 1);
										if(!empty($status_renew)) $status_renew = "-".$status_renew;
									}
								}else{
									$status_cn = '<span class="label label-warning mr-1">Chưa tạo C.Nợ</span>';
								}
							}
							$sheet->setCellValue('A'.$idx, $have_check==false?'P':'');//✓
							$sheet->getStyle('A'.$idx)->getFont()->setName('Wingdings 2')->setSize(12)->setBold(true);
							$sheet->setCellValue('B'.$idx, $type."www");
							$sheet->setCellValue('C'.$idx, "Domain-Tên miền"."\r\n"."Domain:".$domain."\r\n".strip_tags($status_cn).strip_tags($status_pay).strip_tags($status_renew));
							$sheet->getStyle('C'.$idx)->getAlignment()->setWrapText(true);
							$sheet->setCellValue('D'.$idx, $clsISO->fromMySQLDate($nextduedate));
							$sheet->setCellValue('E'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('F'.$idx, "1");
							$sheet->setCellValue('G'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('H'.$idx, $clsISO->formatCurrency($recurringamount));
							$sheet->setCellValue('I'.$idx, $billingcycle);
							$sheet->setCellValue('J'.$idx, $data['status']);
							$idx++;
						}
					}
				}
				$k++;
			}
		}
		
		$titlePage = 'AccRenew';
		$sheet->setTitle($titlePage);
		#
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="exportAccRenew_'.date('dmYHi').'.xlsx');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public');
		//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		ob_end_clean();
		$writer->save('php://output');
		
		exit;
	}
?>