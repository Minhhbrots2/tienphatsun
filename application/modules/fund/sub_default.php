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
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	// $clsISO->print_pre($list_bank_accounts); die();
	$permiss_add = $clsISO->checkPermission('create_fund') ? 1 : 0;
	$smarty->assign('permiss_add', $permiss_add);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Thu chi nội bộ - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$list_THUCCHI = $clsProperty->getCacheItems('THUCCHI');
	$list_THUCTHU = $clsProperty->getCacheItems('THUCTHU');
	$list_menus = array(
		'THUCCHI' => array(
			'title' => 'Chi',
			'list_items' => $list_THUCCHI
		), 'THUCTHU' => array(
			'title' => 'Thu',
			'list_items' => $list_THUCTHU
		)
	);
	$assign_list["list_menus"] = $list_menus;
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo thu chi nội bộ - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_export(){
	// error_reporting(E_ALL);
	// ini_set('display_errors',1);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsMember = new Member();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	$callStartTime = microtime(true);
	$objPHPExcel = new PHPExcel();
	
	$creator = PAGE_NAME;
    $titlePage = 'Export Thu/Chi Nội Bộ FG';
	// Set document properties
    $objPHPExcel->getProperties()->setCreator($creator)
            ->setLastModifiedBy("")
            ->setTitle($titlePage)
            ->setSubject($titlePage)
            ->setDescription("")
            ->setKeywords("Export user list")
            ->setCategory($creator);
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
	//$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
	// End create a new worksheet, after the default sheet
	$tblBackgroundHeader = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'b8cce4;')
        )
    );
	$tblBackgroundHeaderRequired = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'a09f9f;')
        )
    );
	$tblBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'ccc'),
            ),
        ),
    );
	$tblBorderCell = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000000'),
			)
		),
	);
	$style_cell = array(
		'alignment' => array(
			'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		)
	);
	$style_header = array(
	   'alignment' => array(
		   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		   'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
	   ) 
	); 
	//$clsISO->print_pre($objPHPExcel); die();
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'THỜI GIAN');
	$objPHPExcel->getActiveSheet()->setCellValue('B1','SỐ HIỆU CHỨNG TỪ');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'DIỄN GIẢI');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'TÀI KHOẢN QUỸ');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'SỐ PHÁT SINH');
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'SỐ TỒN');
	$objPHPExcel->getActiveSheet()->mergeCells('A1:A2'); // Merge Row
	$objPHPExcel->getActiveSheet()->mergeCells('D1:D2'); // Merge Row
	$objPHPExcel->getActiveSheet()->mergeCells('E1:E2'); // Merge Row
	$objPHPExcel->getActiveSheet()->mergeCells('H1:H2'); // Merge Row
	$objPHPExcel->getActiveSheet()->mergeCells('B1:C1'); // Merge Col
	$objPHPExcel->getActiveSheet()->mergeCells('F1:G1'); // Merge Col
	$objPHPExcel->getActiveSheet()->setCellValue('B2', 'THU');
	$objPHPExcel->getActiveSheet()->setCellValue('C2', 'CHI');
	$objPHPExcel->getActiveSheet()->setCellValue('F2', 'THU');
	$objPHPExcel->getActiveSheet()->setCellValue('G2', 'CHI');
	$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($tblBorderCell);
	$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($tblBorderCell);
	$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->applyFromArray($tblBackgroundHeader);
	$objPHPExcel->getActiveSheet()->getStyle('A1:H2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($style_header);
	$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
	$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(20);
	// Set document autosize column
	PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
	foreach(range('A','H') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	//$objPHPExcel->getActiveSheet()->getStyle('F:H')->getNumberFormat()->setFormatCode(
	//	PHPExcel_Style_NumberFormat:FORMAT_NUMBER_COMMA_SEPARATED1
	//);
	$objPHPExcel->getActiveSheet()->getStyle('A:E')->getNumberFormat()->setFormatCode(
		PHPExcel_Style_NumberFormat::FORMAT_TEXT
	);
	$gr = Input::get("gr", "_all");
	$keysearch = Input::get("keysearch", "");
	$date_range = Input::get("date_range", "");
	$tmp = @explode('-', preg_replace('/\s+/','', $date_range));
	$start_date = $clsISO->convertTextToTime($tmp[0]);
	$end_date = $clsISO->convertTextToTime($tmp[1]);
	$bank_account_id = (int) Input::get("bank_account_id", 0);
	
	$cnd = $cond = "`is_trash`=0";
	if($bank_account_id > 0) {
		$cond.= " and `bank_account_id`='{$bank_account_id}'";
	}
	$cond2 = $cond;
	if($gr != '_all'){
		$cnd.= " and `gr`='{$gr}'";
		$cond.= " and `gr`='{$gr}'";
	}
	if($start_date > 0 && $end_date > 0){
		$cnd.= " and (`payment_date` between '{$start_date}' and '{$end_date}')";
		$cond.= " and (`payment_date` between '{$start_date}' and '{$end_date}')";
	}
	$row = 3; $total_income = $total_expense = 0;
	$list_funds = $clsFund->getAll($cond." order by `reg_date` DESC");
	// $clsISO->print_pre($list_funds); die();
	if(!empty($list_funds)){
		$arr_property_cached = array(); 
		foreach($list_funds as $key => $val){
			$gr = $val['gr'];
			$amount = $clsISO->processSmartNumber($val['amount']);
			//$reg_date = $val['reg_date'];
			$account_date = $val['account_date'];
			$payment_date = $val['payment_date'];
			$bank_account_id = $val['bank_account_id'];
			if(isset($arr_property_cached[$bank_account_id])){
				$bank_account_name = $arr_property_cached[$bank_account_id];
			} else {
				$bank_account_name = $clsProperty->getTitle($bank_account_id);
				$arr_property_cached[$bank_account_id] = $bank_account_name;
			}
			$balance = $clsFund->getTotalBalance($bank_account_id, $payment_date, $gr, $amount);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $clsISO->convertTimeToText($val['payment_date'], true));
			if($gr == 'THUCTHU'){
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['code']);
			} else if($gr == 'THUCCHI'){
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['code']);
			}
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['content']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $bank_account_name);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$row.':H'.$row)->getNumberFormat()->setFormatCode('#,##0');
			if($gr == 'THUCTHU'){
				$total_income += $clsISO->processSmartNumber($amount);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $amount);
				
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, "");
			} else {
				$total_expense += $clsISO->processSmartNumber($amount);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, "");
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $amount);
			}
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $balance);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($tblBorderCell);
			$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($style_cell);
			++$row;
		}
	}
	$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$row.':G'.$row)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($style_cell);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($tblBorderCell);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, "Tổng");
	$objPHPExcel->getActiveSheet()->getStyle('F'.$row.':H'.$row)->getNumberFormat()->setFormatCode('#,##0');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $total_income);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $total_expense);
	
	// $clsISO->print_pre($list_staffs); die();
	$nameFile = 'Thu/Chi nội bộ_'.date('dmY');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client's web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.($nameFile).'.xls');
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
    echo(0);
    die();
}
function default_load_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	###
	$gr = '_general';
	$cat_id = Input::post('cat_id', 0);
	$smarty->assign('gr', $gr);
	$smarty->assign('cat_id', $cat_id);
	###
	$start_year = 2024;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	// Return
	$smarty->assign('template_type', '_general');
	$html = $core->build('_ajax.report.tpl');
	echo json_encode(array(
		'html' => $html,
	)); die();
}
function default_load_apec_fund(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$deviceType;
	$clsFund = new Fund();
	$clsProperty = new Property();
	
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$cond = $cnd = "`is_trash`=0";
	if($month > 0){
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$start_date = strtotime(sprintf('01-%s-%s', $clsISO->parseNumber($month), $year));
		$cond.= " and (FROM_UNIXTIME(`payment_date`, '%m/%Y')='{$m}')";
	} else {
		$start_date = strtotime(sprintf('01-01-%s', $year));
		$cond.= " and (FROM_UNIXTIME(`payment_date`, '%Y')='{$year}')";
	}
	// Quỹ đầu kì
	$total_period = $clsFund->getTotalStat(0);
	#- Tổng thu đầu quỹ
	$total_income = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCTHU' and `payment_date`<'{$start_date}'");
	$total_expense = $clsFund->sumItem("amount", "{$cnd} and `gr`='THUCCHI' and `payment_date`<'{$start_date}'");
	$total_period+= ($total_income - $total_expense);
	// Tổng thu
	$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance = $total_period + $total_income - $total_expense;
	if($bank_account_id > 0 && $end_date > 0){
		$total_balance += $clsFund->getTotalTrans($bank_account_id, $end_date);
	}	
	$html = '<div class="brief-item bg-orange p-3 clickable">
		<p class="fs-16 mb-3 text-white">Quỹ đầu kỳ(₫)</p>
		<h3 class="'.(($deviceType == "phone")?'fs-16':'fs-32').' mb-0 text-white">'.$clsISO->formatNumberToEasyRead($total_period).'</h3>
	</div>
	<div class="brief-item p-3 bg-azure">
		<p class="fs-16 mb-3 text-white">Tổng thu(₫)</p>
		<h3 class="'.(($deviceType == "phone")?'fs-16':'fs-32').' mb-0 text-white">'.$clsISO->formatNumberToEasyRead($total_income).'</h3>
	</div>
	<div class="brief-item p-3 bg-cyan">
		<p class="fs-16 mb-3 text-white">Tổng chi(₫)</p>
		<h3 class="'.(($deviceType == "phone")?'fs-16':'fs-32').' mb-0 text-white">'.$clsISO->formatNumberToEasyRead($total_expense).'</h3>
	</div>
	<div class="brief-item p-3 bg-green">
		<p class="fs-16 mb-3 text-white">Tồn quỹ(₫)</p>
		<h3 class="'.(($deviceType == "phone")?'fs-16':'fs-32').' mb-0 text-white">'.$clsISO->formatNumberToEasyRead($total_balance).'</h3>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_chart_fund(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$data = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$dataPoints_Expense = $dataPoints_Income = array();
	$html = '<div id="chart_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	$cond = "`is_trash`=0";
	###
	if($month == 0){
		for($i=1; $i<=12; $i++){
			$m = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
			$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI' and FROM_UNIXTIME(`payment_date`,'%m/%Y')='{$m}'");
			$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU' and FROM_UNIXTIME(`payment_date`,'%m/%Y')='{$m}'");
			$dataPoints_Expense[] = array(
				'label' => sprintf('T%s', $i),
				'y' => $total_expense*1,
				'indexLabel' => $clsISO->shortNumber($total_expense)
			);
			$dataPoints_Income[] = array(
				'label' => sprintf('T%s', $i),
				'y' => $total_income*1,
				'indexLabel' => $clsISO->shortNumber($total_income)
			);
		}
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1; $i<= $end_day; $i++){
			$d = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
			$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI' and FROM_UNIXTIME(`payment_date`,'%d/%m/%Y')='{$d}'");
			$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU' and FROM_UNIXTIME(`payment_date`,'%d/%m/%Y')='{$d}'");
			$dataPoints_Expense[] = array(
				'label' => sprintf('%s/%s', $i, $month),
				'y' => $total_expense*1,
				'indexLabel' => $clsISO->shortNumber($total_expense)
			);
			$dataPoints_Income[] = array(
				'label' => sprintf('%s/%s', $i, $month),
				'y' => $total_income*1,
				'indexLabel' => $clsISO->shortNumber($total_income)
			);
		}
	}
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Thực chi",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints_Expense
		),
		array(
			"type"  => "column",
			"indexLabel" => "{y}",
			"name"	=> "Thực thu",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataPoints_Income
		)
	);
	$callback = '$Core.chart.canvas_multi(\'chart_'.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function default_load_chart_expense(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	###
	$gr = 'THUCCHI';
	$uid = $clsISO->getUniqid();
	$chart_type = Input::post('chart_type', 'bar');
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$html = '<div id="chart_'.$gr.'_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	###
	$cond = "`is_trash`=0 and `gr`='{$gr}'";
	if($month == 0){
		$cond.= " and FROM_UNIXTIME(`payment_date`,'%Y')='{$year}'";
	} else {
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`payment_date`,'%m/%Y')='{$m}'";
	}
	$list_items = $clsProperty->getCacheItems('THUCCHI');
	if(!empty($list_items)){
		foreach($list_items as $key => $val){
			$type_id = $val[$clsProperty->pkey];
			$total_amount = $clsFund->sumItem("amount", "{$cond} and `type_id`='{$type_id}'");
			$dataPoints[] = array(
				'label' => $val["title"],
				'y' => $total_amount*1,
				'indexLabel' => $clsISO->shortNumber($total_amount)
			);
		}
	}
	$data['type'] = $chart_type;
	$data['showInLegend'] = true;
	$data['indexLabel'] = '{y}';
	$data['legendText'] = '{label}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	###
	$callback = '$Core.chart.canvas(\'chart_'.$gr.'_'.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function default_load_chart_realincome(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	###
	$gr = 'THUCTHU';
	$uid = $clsISO->getUniqid();
	$chart_type = Input::post('chart_type', 'bar');
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$html = '<div id="chart_'.$gr.'_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	###
	$cond = "`is_trash`=0 and `gr`='{$gr}'";
	if($month == 0){
		$cond.= " and FROM_UNIXTIME(`payment_date`,'%Y')='{$year}'";
	} else {
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`payment_date`,'%m/%Y')='{$m}'";
	}
	$list_items = $clsProperty->getCacheItems('THUCTHU');
	if(!empty($list_items)){
		foreach($list_items as $key => $val){
			$type_id = $val[$clsProperty->pkey];
			$total_amount = $clsFund->sumItem("amount", "{$cond} and `type_id`='{$type_id}'");
			$dataPoints[] = array(
				'label' => $val["title"],
				'y' => $total_amount*1,
				'indexLabel' => $clsISO->shortNumber($total_amount)
			);
		}
	}
	$data['type'] = $chart_type;
	$data['showInLegend'] = true;
	$data['indexLabel'] = '{y}';
	$data['legendText'] = '{label}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	###
	$callback = '$Core.chart.canvas(\'chart_'.$gr.'_'.$uid.'\',respJson.barChartData);';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData,
		'callback' => $callback
	)); die();
}
function default_load_content_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$gr = Input::post('gr', 'THUCCHI');
	$cat_id = (int) Input::post('cat_id', 0);
	
	$gr = Input::post('gr', 'THUCCHI');
	$cat_id = (int) Input::post('cat_id', 0);
	$titlePage = $clsProperty->getTitle($cat_id);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('gr', $gr);
	$smarty->assign('cat_id', $cat_id);
	$assign_list["list_preloaders"] = $list_preloaders;
	$assign_list["titlePage"] = $titlePage;
	###
	$start_year = 2024;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	
	// Return
	$smarty->assign('template_type', '_content');
	$html = $core->build('_ajax.report.tpl');
	echo json_encode(array(
		'html' => $html,
	)); die();
}
function default_load_table_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$smarty->assign('clsFund', $clsFund);
	$smarty->assign('clsProperty', $clsProperty);
	
	$gr = Input::post('gr', 'THUCCHI');
	$cat_id = (int) Input::post('cat_id', 0);
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$cond = "gr='{$gr}' and type_id='{$cat_id}'";
	if($month == 0){
		$cond.= " and FROM_UNIXTIME(`payment_date`,'%Y')='{$year}'";
	} else {
		$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$cond.= " and FROM_UNIXTIME(`payment_date`,'%m/%Y')='{$m}'";
	}
	#- Pagination
	$current_page = Input::post('page', 1);
	$per_page = Input::post('per_page', 15);
	$total_record = $clsFund->countItem($cond);
	$total_page = @ceil($total_record/ $per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$total_amount = $clsFund->sumItem("amount", $cond);
	$list_items = $clsFund->getAll($cond." order by `reg_date` DESC ".$limitCond);
	if(!empty($list_items)){
		$arr_property_cached = array();
		foreach($list_items as $key => $val){
			$bank_account_id = $val['bank_account_id'];
			if(isset($arr_property_cached[$bank_account_id])){
				$bank_account_name = $arr_property_cached[$bank_account_id];
			} else {
				$bank_account_name = $clsProperty->getTitle($bank_account_id);
				$arr_property_cached[$bank_account_id] = $bank_account_name;
			}
			$list_items[$key]['bank_account_name'] = $bank_account_name;
		}
	}
	$smarty->assign('gr', $gr);
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('current_page', $current_page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('list_items', $list_items);
	// Return
	$html = $core->build('_ajax.table_report.tpl');
	echo json_encode(array(
		'html' => $html,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'total_amount' => $clsISO->formatPrice($total_amount),
	)); die();
}
function default_load_chart_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$gr = Input::post('gr', 'THUCCHI');
	$cat_id = (int) Input::post('cat_id', 0);
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	###
	$cond = "`is_trash`=0 and `gr`='{$gr}' and `type_id`='{$cat_id}'";
	$html = '<div id="chart_'.$uid.'" class="chartContainer w-100 h-px-300"></div>';
	###
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	if($month == 0){
		for($i=1; $i<=12; $i++){
			$m = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
			$total_funds = $clsFund->sumItem("amount", "{$cond} and FROM_UNIXTIME(`payment_date`,'%m/%Y')='{$m}'");
			$dataPoints[] = array(
				'label' => sprintf('T%s', $i),
				'y' => $total_funds*1,
				'indexLabel' => $clsISO->shortNumber($total_funds)
			);	
		}
	} else {
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1; $i<= $end_day; $i++){
			$d = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
			$total_funds = $clsFund->sumItem("amount", "{$cond} and FROM_UNIXTIME(`payment_date`,'%d/%m/%Y')='{$d}'");
			$dataPoints[] = array(
				'label' => sprintf('%s/%s', $i, $month),
				'y' => $total_funds*1,
				'indexLabel' => $clsISO->shortNumber($total_funds)
			);
		}
	}
	$data['type'] = 'spline';
	$data['showInLegend'] = true;
	$data['indexLabel'] = '{y}';
	$data['legendText'] = '{label}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData
	)); die();
}
function default_load_desktop_bank_accounts(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$clsBankTransfer = new BankTransfer();
	#
	$field = "{$clsProperty->pkey},property_code,title,textcolor,bgcolor,image";
	$list_bank_accounts = $clsProperty->getAll("property_type='BANK_ACCOUNT' order by order_no ASC", $field);
	$bank_account_id = vnSessionExist('_ss_bank_account_id') ? (int) vnSessionGetVar('_ss_bank_account_id') : 0;
	#
	$html = '<div bank_account_id="0" onClick="$Core.fund.do_search(this, event)" class="obank bg-primary pointer-event text-center'.($bank_account_id==0 ? ' current' : '').'">
		<img class="FUND my-2" src="'.URL_IMAGES.'/FUND.png" width="120px" />
		<div class="fs-14 text-white fwd-bold">Tất cả quỹ</div>
	</div>';
	if(!empty($list_bank_accounts)){
		foreach($list_bank_accounts as $key => $val){
			$image = $val['image'];
			$html.= '<div bank_account_id="'.$val[$clsProperty->pkey].'" style="background:'.$val['bgcolor'].'" 
			onClick="$Core.fund.do_search(this, event)" class="obank pointer-event text-center'.($bank_account_id==$val[$clsProperty->pkey] ? ' current' : '').'">
				<img class="'.$image.' mb-1" src="'.($image=='QTM'?URL_IMAGES.'/QTM.png':'https://api.vietqr.io/img/'.$image.'.png').'" width="120px" />
				<div class="fs-13 text-white text-nowrap fwd-bold">'.$val['title'].'</div>
			</div>';
		}
	}
	// Return
	echo $html; die();
}
function default_open_manage(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	// Return
	$html = $core->build('_ajax.fund.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_bank_accounts(){
	global $smarty,$adminid,$core,$clsISO,$_LANG_ID;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	#
	$html = '<style type="text/css">
		@media screen and (max-width:678px){
			.table_setting_property_'.$property_type.' th:nth-child(1),
			.table_setting_property_'.$property_type.' th:nth-child(4),
			.table_setting_property_'.$property_type.' td:nth-child(1),
			.table_setting_property_'.$property_type.' td:nth-child(4){
				display:none !important;
			}
		}		
	</style>
	<table class="table table-striped table_setting_property_'.$property_type.'" width="100%">
		<thead><tr>
			<th class="text-center" width="3%">No.</th>
			<th class="text-left" width="25%">Tài khoản/ Quỹ</th>
			<th class="text-right" width="20%">Số tiền</th>
			<th class="text-left">Miêu tả</th>
			<th class="text-center" width="100px">'.$core->get_Lang('Actions').'</th>
		</tr></thead>
		<tbody class="tbody_setting_property_'.$property_type.'">';
		$list_property = $clsProperty->getAll("property_type='{$property_type}' order by order_no ASC");
		// $clsISO->print_pre($list_property); die();
		if(!empty($list_property)){ $ii=0; // Init
			foreach($list_property as $property){
				$property_id = $property[$clsProperty->pkey];
				$total_balance = $clsFund->getTotalBank($property_id);
				$props = 'property_id="'.$property_id.'" property_type="'.$property['property_type'].'" toId="fund"';
				$editAction = '<button type="button" class="btn btn-default p-1 mr-1" onClick="$Core.property.open_property(this,event)" '.$props.'>
					<i class="bx bx-pencil"></i></button>';
				$deleteAction = '<button type="button" class="btn btn-default p-1" onClick="$Core.property.delete_property(this,event)" '.$props.'>
					<i class="bx bx-trash"></i></button>';
				// Status
				$html .= '<tr>
					<td class="text-center">'.($ii+1).'</td>
					<td class="text-left">'.$clsProperty->getTitle($property_id).'</td>
					<td class="text-right">'.$clsISO->formatNumberToEasyRead($total_balance).' '.$clsISO->getRate().'</td>
					<td class="text-left">'.$clsProperty->getIntro($property_id).'</td>
					<td class="text-center">'.$editAction.$deleteAction.'</td>
				</tr>';
				++$ii;
			}
		}
		$html .= '</tbody>
	</table>';
	// Output
	echo $html; die();
}
function default_bank_transfer(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	
	$field = "{$clsProperty->pkey},property_code,title,textcolor,bgcolor,image";
	$list_bank_accounts = $clsProperty->getAll("property_type='BANK_ACCOUNT' order by order_no ASC", $field);
	$smarty->assign('list_bank_accounts', $list_bank_accounts);
	/*=============Title & Description Page==================*/
	$title_page = 'Chuyển quỹ - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_list_bank_transfer(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsBankTransfer = new BankTransfer();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBankTransfer', $clsBankTransfer);
	##
	$cond = "is_trash=0";
	$keyword = Input::post('keyword');
	$action = Input::post('action', "_all");
	$bank_account_id = Input::post('bank_account_id');
	$date_range = Input::post('date_range');
	$start_date = $end_date = 0;
	if(!empty($date_range)){
		$tmp = @explode('-', $date_range);
		$start_date = $tmp[0]; $end_date = $tmp[1];
		$start_date = !empty($start_date) ? $clsISO->convertTextToTime($start_date, "00:00:00") : 0;
		$end_date = !empty($end_date) ? $clsISO->convertTextToTime($end_date, "23:59:59") : 0;
	}
	##
	if(!empty($keyword)){
		$cond.= " and (`code` like '%{$keyword}%' 
			or `content` like '%{$keyword}%'
		)";
	}
	if($bank_account_id > 0) {
		if($action == '_in'){
			$cond.= " and `bank_account_to`='{$bank_account_id}'";
		} else if($action == '_out'){
			$cond.= " and `bank_account_from`='{$bank_account_id}'";
		} else {
			$cond.= " and (`bank_account_from`='{$bank_account_id}' or `bank_account_to`='{$bank_account_id}')";
		}
	}
	if($start_date > 0 && $end_date > 0){
		$cond.= " and (`payment_date` between '{$start_date}' and '{$end_date}')";
	}
	#- Begin pagination
	$current_page = Input::post('page',1);
	$per_page = Input::post('per_page',30);
	$total_record = $clsBankTransfer->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination	
	$list_bank_transfers = $clsBankTransfer->getAll($cond." order by `payment_date` DESC".$limitCond);
	if(!empty($list_bank_transfers)){
		$arr_property_cached = array();
		foreach($list_bank_transfers as $key => $val){
			$bank_account_from = $val['bank_account_from'];
			$bank_account_to = $val['bank_account_to'];
			if(isset($arr_property_cached[$bank_account_from])){
				$bank_account_from_name = $arr_property_cached[$bank_account_from];
			} else {
				$bank_account_from_name = $clsProperty->getTitle($bank_account_from);
				$arr_property_cached[$bank_account_from_name] = $bank_account_from_name;
			}
			#
			if(isset($arr_property_cached[$bank_account_to])){
				$bank_account_to_name = $arr_property_cached[$bank_account_to];
			} else {
				$bank_account_to_name = $clsProperty->getTitle($bank_account_to);
				$arr_property_cached[$bank_account_to_name] = $bank_account_to_name;
			}
			$list_bank_transfers[$key]['bank_account_from_name'] = $bank_account_from_name;
			$list_bank_transfers[$key]['bank_account_to_name'] = $bank_account_to_name;
		}
	}
	// $clsISO->print_pre($list_bank_transfers); die();
	$smarty->assign('list_bank_transfers', $list_bank_transfers);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	// Return
	$html = $core->build('_ajax.list_bank_transfer.tpl');
	echo json_encode(array(
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
	)); die();
}
function default_open_bank_transfer(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsBankTransfer = new BankTransfer();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBankTransfer', $clsBankTransfer);
	#
	$action = "_add";
	$list_attachments = array();
	$oneBankTransfer = array(
		'bank_account_from' => 0,
		'bank_account_to' => 0,
		'payment_date' => time(),
		'account_date' => time()
	);
	$uid = $clsISO->getUniqid();
	$bank_transfer_id = (int) Input::post('bank_transfer_id', "0");
	$titlePage = "Thêm phiếu chuyển quỹ";
	if($bank_transfer_id > 0){
		$action = "_edit";
		$titlePage = "Cập nhật phiếu chuyển quý";
		$oneBankTransfer = $clsBankTransfer->getOne($bank_transfer_id);
		$attachments = $oneBankTransfer['attachments'];
		$list_attachments = !empty($attachments) ? json_decode(html_entity_decode($attachments), true) : array();
	}
	$payment_date = date('Y-m-d\TH:i', $oneBankTransfer['payment_date']);
	$account_date = date('Y-m-d', $oneBankTransfer['account_date']);
	$oneBankTransfer['payment_date'] = $payment_date;
	$oneBankTransfer['account_date'] = $account_date;
	###
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('bank_transfer_id', $bank_transfer_id);
	$smarty->assign('oneBankTransfer', $oneBankTransfer);
	$smarty->assign('list_attachments', $list_attachments);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$html = $core->build('_ajax.bank_transfer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_bank_transfer(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$profile_id,$clsISO;
	$clsBankTransfer = new BankTransfer();
	###
	$bank_transfer_id = (int) Input::post('bank_transfer_id','0');
	$amount = Input::post('amount');
	$payment_date = Input::post('payment_date');
	$account_date = Input::post('account_date');
	$amount= !empty($amount) ? $clsISO->processSmartNumber($amount) : 0;
	$payment_date = !empty($payment_date) ? $clsISO->toTime($payment_date) : 0;
	$account_date = !empty($account_date) ? $clsISO->toTime($account_date) : 0;
	$msg = "_error";
	if(isset($_POST['submit']) && $_POST['submit'] == 'Update'){
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(!is_uploaded_file($file['name'])){
					$clsUploadFile = new UploadFile();
					$up = $clsUploadFile->uploadItem($file,'/attachments',"pdf,doc,docx,xls,xlsx,csv,txt,zip,jpg,jpeg,png,gif");
					if(!empty($up) && file_exists(ABSPATH . $up)){
						$attachments[] = $up;
					}
				}
			}
		}
		if($bank_transfer_id == 0){
			$bank_transfer_id = $clsBankTransfer->getMaxId();
			// $clsBankTransfer->setDebug(true);
			if($clsBankTransfer->insert(array(
				$clsBankTransfer->pkey => $bank_transfer_id,
				'code' => Input::post('code'),
				'payment_date' => $payment_date,
				'account_date' => $account_date,
				'bank_account_from' => Input::post('bank_account_from', 0),
				'bank_account_to' => Input::post('bank_account_to', 0),
				'amount' => $amount,
				'content' => Input::post('content'),
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			))){
				$msg = "_success";
			}
		} else {
			if($clsBankTransfer->updateOne($bank_transfer_id, array(
				'payment_date' => $payment_date,
				'account_date' => $account_date,
				'bank_account_from' => Input::post('bank_account_from', 0),
				'bank_account_to' => Input::post('bank_account_to', 0),
				'amount' => $amount,
				'content' => Input::post('content'),
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'upd_date' => time(),
				'user_id_update' => $profile_id
			))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_check_amount_bank_transfer(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$clsBankTransfer = new BankTransfer();
	#
	$amount = Input::post('amount');
	$amount = !empty($amount) ? $clsISO->processSmartNumber($amount) : 0;
	$bank_account_from = (int) Input::post('bank_account_from', 0);
	#
	$msg = "_valid";
	$total_fund = $clsFund->getTotalBank($bank_account_from);
	// $clsISO->print_pre($total_fund); die();
	if($amount > $total_fund){
		$msg = "_invalid";
	}
	// Return 
	echo $msg; die();
}
function default_delete_bank_transfer(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsBankTransfer = new BankTransfer();
	###
	$msg = "_error";
	$bank_transfer_id = (int) Input::post('bank_transfer_id','0');
	if($clsBankTransfer->deleteOne($bank_transfer_id)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_list(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$smarty->assign('clsFund', $clsFund);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$cnd = $cond = "`is_trash`=0";
	$gr = Input::post('gr', "_all");
	$def_bank_account_id = vnSessionExist("_ss_bank_account_id") 
		? (int) vnSessionGetVar('_ss_bank_account_id') : 0;
	$bank_account_id = (int) Input::post('bank_account_id', $def_bank_account_id);
	$date_range = Input::post('date_range');
	$tmp = @explode('-', $date_range);
	$start_date = $tmp[0]; $end_date = $tmp[1];
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date." 00:00:00") : 0;
	$end_date = !empty($end_date) ? $clsISO->toTime($end_date." 23:59:59") : 0;
	// $clsISO->print_pre(date('d/m/Y h:i:s', $start_date)); 
	// $clsISO->print_pre(date('d/m/Y h:i:s', $end_date)); die();
	if($bank_account_id > 0) {
		$cond.= " and `bank_account_id`='{$bank_account_id}'";
	}
	$cond2 = $cond;
	if($gr != '_all'){
		$cnd.= " and `gr`='{$gr}'";
		$cond.= " and `gr`='{$gr}'";
	}
	if($start_date > 0 && $end_date > 0){
		$cnd.= " and (`payment_date` between '{$start_date}' and '{$end_date}')";
		$cond.= " and (`payment_date` between '{$start_date}' and '{$end_date}')";
	}
	// Quỹ đầu kì
	$total_period = $clsFund->getTotalStat($bank_account_id);
	#- Tổng thu đầu quỹ
	$total_income = $clsFund->sumItem("amount", "{$cond2} and `gr`='THUCTHU' and `payment_date`<'{$start_date}'");
	$total_expense = $clsFund->sumItem("amount", "{$cond2} and `gr`='THUCCHI' and `payment_date`<'{$start_date}'");
	$total_period+= ($total_income - $total_expense);
	// Tổng thu
	$total_income = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCTHU'");
	// Tổng chi
	$total_expense = $clsFund->sumItem("amount", "{$cond} and `gr`='THUCCHI'");
	// Tổng tồn
	$total_balance = $total_period + $total_income - $total_expense;
	if($bank_account_id > 0 && $end_date > 0){
		$total_balance += $clsFund->getTotalTrans($bank_account_id, $end_date);
	}
	#- Begin pagination
	$current_page = Input::post('page',1);
	$per_page = Input::post('per_page',30);
	$total_record = $clsFund->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination	
	$list_funds = $clsFund->getAll($cond." order by `payment_date` DESC".$limitCond);
	if(!empty($list_funds)){
		$arr_property_cached = array();
		foreach($list_funds as $key => $val){
			$gr = $val['gr'];
			$amount = $val['amount'];
			//$reg_date = $val['reg_date'];
			$account_date = $val['account_date'];
			$payment_date = $val['payment_date'];
			$bank_account_id = $val['bank_account_id'];
			if(isset($arr_property_cached[$bank_account_id])){
				$bank_account_name = $arr_property_cached[$bank_account_id];
			} else {
				$bank_account_name = $clsProperty->getTitle($bank_account_id);
				$arr_property_cached[$bank_account_id] = $bank_account_name;
			}
			$list_funds[$key]['bank_account_name'] = $bank_account_name;
			$list_funds[$key]['balance'] = $clsFund->getTotalBalance($bank_account_id, $payment_date, $gr, $amount);
		}
	}
	// $clsISO->print_pre($list_funds); die();
	$smarty->assign('list_funds', $list_funds);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	/** Permiss */
	$permiss_edit = $clsISO->checkPermission('edit_fund') ? 1 : 0;
	$permiss_delete = $clsISO->checkPermission('delete_fund') ? 1 : 0;
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_delete', $permiss_delete);
	
	$html_briefs = "";
	if($current_page == 1){
		$list_briefs = array(
			'_TOTAL_PERIOD' => 'Quỹ đầu kỳ',
			'_TOTAL_INCOME' => 'Tổng thu',
			'_TOTAL_EXPENSE' => 'Tổng chi',
			'_TOTAL_BALANCE' => 'Tồn quỹ',
			'_TOTAL_TAX' => 'Thuế VAT',
			'_TOTAL_PERSON_INCOME' => 'Thu nhập cá nhân',
			//'_TOTAL_MONEY_WAITING_PAY' => 'Tiền chờ tất toán',
			//'_TOTAL_KPI_TRAVEL' => 'KPI, Quỹ du lịch',
			//'_TOTAL_INVOICE_ISSUED' => 'Tổng hóa đơn đã xuất',
			//'_TOTAL_MONEY_BACK' => 'Tiền đã về',
			//'_TOTAL_MONEY_WAITING_RETURN' => 'Tiền chờ về',
		);
		foreach($list_briefs as $key => $val){
			if($key == '_TOTAL_PERIOD'){ // 'Quỹ đầu kỳ'
				$total = $clsISO->formatNumberToEasyRead($total_period);
			} else if($key == '_TOTAL_INCOME'){ // 'Tổng thu'
				$total = $clsISO->formatNumberToEasyRead($total_income);
			} else if($key == '_TOTAL_EXPENSE'){ // 'Tổng chi'
				$total = $clsISO->formatNumberToEasyRead($total_expense);
			} else if($key == '_TOTAL_BALANCE'){ // 'Tồn quỹ'
				$total = $clsISO->formatNumberToEasyRead($total_balance);
			} else if($key == '_TOTAL_TAX'){ // 'Thuế VAT'
				$total = 'Updating...';
			} else if($key == '_TOTAL_PERSON_INCOME'){ // 'Thu nhập cá nhân'
				$total = $clsFund->sumItem("amount", $cnd." and `is_person_tax`=1 and `gr`='THUCCHI'");
				$total = ($total > 0) ? $clsISO->formatNumberToEasyRead($total * 0.1) : 0;
			} else if($key == '_TOTAL_MONEY_WAITING_PAY'){
				$total = 'Updating...';
			} else if($key == '_TOTAL_KPI_TRAVEL'){
				$total = $clsFund->sumItem("amount", $cnd." and `type_id`='"._THUCCHI_TYPE_BONUS_ID."' and `gr`='THUCCHI'");
				$total = ($total > 0) ? $clsISO->formatNumberToEasyRead($total) : 0;
			} else if($key == '_TOTAL_INVOICE_ISSUED'){
				$total = 'Updating...';
			} else if($key == '_TOTAL_MONEY_BACK'){
				$total = 'Updating...';
			} else if($key == '_TOTAL_MONEY_WAITING_RETURN'){
				$total = 'Updating...';
			}
			$html_briefs.= '<div class="brief-item xs:w-40 w-15 flex-fill bg-white">
				<p class="fs-16 mb-2 text-nowrap text-dark">'.$val.'(₫)</p>
				<h3 class="fs-18 mb-0">
					<span class="total_income">'.$total.'</span>
				</h3>
			</div>';
		}
	}
	// Return
	$html = $core->build('_ajax.list.tpl');
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'html_briefs' => $html_briefs
	)); die();
}
function default_storage_sess_bank_account(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	
	$bank_account_id = (int) Input::post('bank_account_id', 0);
	vnSessionSetVar('_ss_bank_account_id', $bank_account_id);
	// Return
	echo (1); die();
}
function default_open_setting(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	
	$uid = $clsISO->getUniqid();
	$field = "{$clsProperty->pkey},title,ms_value";
	$list_property = $clsProperty->getAll("property_type='THUCCHI'", $field);
	// $clsISO->print_pre($list_property); die();
	$smarty->assign('list_property', $list_property);
	// Return
	$html = $core->build('_ajax.setting.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_setting(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	###
	$msg = "_error";
	$group = Input::post('group');
	$prop_id = (int) Input::post('prop_id', 0);
	###
	$ms_value = 0;
	if($group=='expense') $ms_value = _THUCCHI_GROUP_EXPENSE_ID;
	if($group=='invest') $ms_value = _THUCCHI_GROUP_INVEST_ID;
	if($clsProperty->updateOne($prop_id, array(
		'ms_value' => $ms_value
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_open(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFund = new Fund();
	$smarty->assign('clsFund', $clsFund);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$uid = $clsISO->getUniqid();
	$gr = Input::post('gr', "THUCTHU");
	$fund_id = (int) Input::post('fund_id', "0");
	
	$action = "_add";
	$list_attachments = array();
	$oneFund = array(
		'payment_date' => time(),
		'account_date' => time(), 
		'is_person_tax' => 1,
		'upd_date' => time()
	);
	$titlePage = "Thêm phiếu " . ($gr=='THUCTHU' ? "thu" : "chi");
	if($fund_id > 0){
		$action = "_edit";
		$oneFund = $clsFund->getOne($fund_id);
		$attachments = $oneFund['attachments'];
		$list_attachments = $clsISO->to_array_json($attachments);
		$oneFund['oProfile'] = $clsProfile->getOne($oFund['user_id_update'], "full_name,first_name,last_name");
		$titlePage = "Cập nhật phiếu " . ($gr=='THUCTHU' ? "thu" : "chi");
	}
	$payment_date = date('Y-m-d\TH:i', $oneFund['payment_date']);
	$account_date = date('Y-m-d', $oneFund['account_date']);
	$oneFund['payment_date'] = $payment_date;
	$oneFund['account_date'] = $account_date;
	#
	$smarty->assign('gr', $gr);
	$smarty->assign('uid', $uid);
	$smarty->assign('fund_id', $fund_id);
	$smarty->assign('action', $action);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('oneFund', $oneFund);
	$smarty->assign('list_attachments', $list_attachments);
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_duplicate(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$profile_id,$clsISO;
	$clsFund = new Fund();
	$gr = Input::post('gr','THUCTHU');
	$fund_id = (int) Input::post('fund_id','0');
	// $clsISO->print_pre($gr); die();
	$html = "";
	$msg = "_error";
	$duplicate_id = 0;
	if($fund_id > 0){
		$oFund = $clsFund->getOne($fund_id);
		$duplicate_id = $clsFund->getMaxId();
		$code = $clsFund->genCode($gr);
		if($clsFund->insert(array(
			$clsFund->pkey => $duplicate_id,
			'gr' => $gr,
			'code' => $code,
			'payment_date' => time(),
			'account_date' => time(),
			'type_id' => $oFund['type_id'],
			'bank_account_id' => $oFund['bank_account_id'],
			'payment_method' => $oFund['payment_method'],
			'person' => $oFund['person'],
			'person_address' => $oFund['person_address'],
			'amount' => $oFund['amount'],
			'content' => $oFund['content'],
			'attachments' => $oFund['attachments'],
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = '_success|||<a href="javascript:void(0)" class="autoclick_'.$duplicate_id.'" 
				onClick="$Core.fund.open(this, event)" gr="'.$gr.'" fund_id="'.$duplicate_id.'"></a>';
		}
	}
	// Return
	echo json_encode(array(
		'gr' => $gr,
		'msg' => $msg,
		'duplicate_id' => $duplicate_id
	)); die();
}
function default_pop_save_fund(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$profile_id,$clsISO;
	$clsFund = new Fund();
	$clsBilling = new Billing();
	$gr = Input::post('gr','THUCTHU');
	$fund_id = (int) Input::post('fund_id','0');
	$amount = Input::post('amount');
	$payment_date = Input::post('payment_date');
	$account_date = Input::post('account_date');
	$person = Input::post('person');
	$person_address = Input::post('person_address');
	$amount= !empty($amount) ? $clsISO->processSmartNumber($amount) : 0;
	$payment_date = !empty($payment_date) ? $clsISO->toTime($payment_date) : 0;
	$account_date = !empty($account_date) ? $clsISO->toTime($account_date) : 0;
	###
	$person_id = 0; $curent_time = time();
	$begin_date = strtotime('01-05-2024');
	if(!empty($person)){
		$clsPerson = new Person();
		$tmp = $clsPerson->getByCond("JSON_EXTRACT(`more_information`,'$.name_slug')='".$core->replaceSpace($person)."'");
		if(!empty($tmp)){
			$person_id = $tmp[$clsPerson->pkey];
		} else {
			$person_id = $clsPerson->getMaxId();
			$more_information = array(
				'name' => $person,
				'name_slug' => $core->replaceSpace($person),
				'address' => $person_address
			);
			$clsPerson->insert(array(
				$clsPerson->pkey => $person_id,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'upd_date' => $profile_id
			));
		}
	}
	$msg = "_error";
	if($payment_date > $curent_time || $payment_date < $begin_date){
		echo json_encode(array(
			'msg' => '_invalid'
		)); die();
	}
	if(isset($_POST['submit']) && $_POST['submit'] == 'Update'){
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(!is_uploaded_file($file['name'])){
					$clsUploadFile = new UploadFile();
					$up = $clsUploadFile->uploadItem($file,'/attachments',"pdf,doc,docx,xls,xlsx,csv,txt,zip,jpg,jpeg,png,gif");
					if(!empty($up) && file_exists(ABSPATH . $up)){
						$attachments[] = $up;
					}
				}
			}
		}
		if($fund_id == 0){
			$fund_id = $clsFund->getMaxId();
			if($clsFund->insert(array(
				$clsFund->pkey => $fund_id,
				'gr' => $gr,
				'code' => Input::post('code'),
				'payment_date' => $payment_date,
				'account_date' => $account_date,
				'type_id' => Input::post('type_id', 0),
				'billing_id' => Input::post('billing_id', 0),
				'bank_account_id' => Input::post('bank_account_id', 0),
				'payment_method' => Input::post('payment_method', 0),
				'person_id' => $person_id,
				'person' 	=> $person,
				'person_address' => $person_address,
				'is_person_tax' => Input::post('is_person_tax', 1),
				'amount' => $amount,
				'content' => Input::post('content'),
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			))){
				$msg = "_success";		
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Fund","insert",["gr"=>$gr]);
			}
		} else {
			$oneFund = $clsFund->getOne($fund_id);
			$attachments_old = $oneFund['attachments'];
			$attachments_old = !empty($attachments_old) 
				? json_decode(html_entity_decode($attachments_old), true) 
				: array();
			$attachments = array_merge($attachments_old, $attachments);
			if($clsFund->updateOne($fund_id, array(
				'code' => Input::post('code'),
				'payment_date' => $payment_date,
				'account_date' => $account_date,
				'type_id' => Input::post('type_id', 0),
				'billing_id' => Input::post('billing_id', 0),
				'bank_account_id' => Input::post('bank_account_id', 0),
				'payment_method' => Input::post('payment_method', 0),
				'person_id' => $person_id,
				'person' 	=> $person,
				'person_address' => $person_address,
				'is_person_tax' => Input::post('is_person_tax', 1),
				'amount' => $amount,
				'content' => Input::post('content'),
				'attachments' => json_encode($attachments, JSON_UNESCAPED_UNICODE),
				'upd_date' => time(),
				'user_id_update' => $profile_id
			))){
				$msg = "_success";
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Fund","update",["gr"=>$gr]);
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_delete_fund(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsFund = new Fund();
	###
	$msg = "_error";
	$fund_id = (int) Input::post('fund_id','0');
	if($clsFund->deleteOne($fund_id)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_view(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsFund = new Fund();
	
	$uid = $clsISO->getUniqid();
 	$fund_id = (int) Input::post('fund_id', 0);
	if($fund_id==0) {
		echo '_invalid';
		die();
	}
	#
	$oneFund = $clsFund->getOne($fund_id);
	$titlePage = ($oneFund['gr'] == 'THUCTHU') ? 'Phiếu thu' : 'Phiếu chi';
	// $clsISO->print_pre($oneFund); die();
	$html .= '<div class="modal-dialog modal-standard">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">'.$titlePage.' '.$oneFund['code'].'</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div style="margin:10px 20px;">
				<div class="printArea printArea_'.$uid.'">
					<style media="print">
						@media print {
							*, *::before, *::after {
								box-sizing: border-box;
								-webkit-print-color-adjust: exact !important;
								color-adjust: exact !important;
							}
							.printArea{}
							@page {
								margin: 0;
								size:A4 landscape;
								margin-left: 0px;
								margin-right: 0px;
								margin-top: 0px;
								margin-bottom: 0px;
								-webkit-print-color-adjust: exact;
							}
						}
					</style>
					<div class="mainContainer" style="margin:0;padding:0;width:100%;">
						<div class="paymentFormContainer" style="font-family:Arial; font-size:14px; color:#000; width:720px; margin:auto; padding:0px 0 15px 0;">
							<div style="height:70px; margin-bottom:15px;">
								<div style="width:250px; float:left;">
									<img src="'.URL_IMAGES.'/logo-fh.png" height="50px">
								</div>
								<div style="float:right; width:calc(100% - 400px); text-align:center;">
									<strong>Mẫu số 02 - TT</strong>
									<p>(Ban hành theo thông tư 200/2014/TT-BTC ngày 22/12/2014 của BTC)</p>					
								</div>
							</div>
							<table border="0" cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<td style="text-align:center" width="76%">
										<h3 style="margin-bottom: 10px; color: black; text-transform:uppercase; font-weight: bold;">'.$titlePage.'</h3>
										<span style="font-style:italic">Ngày.'.date('d', $oneFund['account_date']).'.tháng.'.date('m', $oneFund['account_date']).'.năm.'.date('Y', $oneFund['account_date']).'.</span>
									</td>
									<td width="24%">
										<div style="width:100%; display:inline-block">
											<div style="width:45%; text-align:right; float:left; height:24px; line-height:32px;">Quyển số: </div>
											<div style="width:55%; height:20px; float:right; border-bottom:1px dashed #DDD"></div>
										</div>
										<div style="width:100%; display:inline-block">
											<div style="width:45%; text-align:right; float:left; height:24px; line-height:32px;">Số: </div>
											<div style="width:55%; height:20px; float:right; border-bottom:1px dashed #DDD"></div>
										</div>
										<div style="width:100%; display:inline-block">
											<div style="width:45%; text-align:right; float:left; height:24px; line-height:32px;">Nợ: </div>
											<div style="width:55%; height:20px; float:right; border-bottom:1px dashed #DDD"></div>
										</div>
										<div style="width:100%; display:inline-block">
											<div style="width:45%; text-align:right; float:left; height:24px; line-height:32px;">Có: </div>
											<div style="width:55%; height:20px; float:right; border-bottom:1px dashed #DDD"></div>
										</div>
									</td>
								</tr>
							</table>
							<div style="margin-bottom:50px">
								<div style="width:100%; display:flex; margin-bottom:5px; border-bottom:1px dashed #DDD; line-height:24px;">
									<div style="transform:translateY(8px); background: #FFF; line-height: 26px;">Họ và tên người nhận:</div>
									<div style="transform:translateY(7px);">&nbsp;'.$oneFund['person'].'</div>
								</div>
								<div style="width:100%; display:flex; margin-bottom:5px; border-bottom:1px dashed #DDD; line-height:24px;">
									<div style="transform:translateY(8px); background: #FFF; line-height: 26px;">Địa chỉ:</div>
									<div style="transform:translateY(7px);">&nbsp;'.$oneFund['person_address'].'</div>
								</div>
								<div style="width:100%; display:flex; margin-bottom:5px; border-bottom:1px dashed #DDD; line-height:24px;">
									<div style="transform:translateY(8px); background:#FFF; line-height:26px;">Lý do:</div>
									<div style="transform:translateY(7px);">&nbsp;'.$oneFund['content'].'</div>
								</div>
								<div style="width:100%; display:flex; margin-bottom:5px; background:url('.URL_IMAGES.'/dot.png) repeat center 28px;  line-height:24px; min-height:60px;">
									<div style="width:25%; display:flex;">
										<div style="transform:translateY(8px); background: #FFF; height:26px; line-height:26px;">Số tiền:</div>
										<div style="transform:translateY(7px);">&nbsp;'.$clsISO->formatPrice($oneFund['amount']).$clsISO->getRate().'</div>
									</div>
									<div style="width:75%; display:flex;">
										<div style="transform:translateY(8px); white-space:nowrap; background: #FFF; height:26px; line-height: 26px;">(Bằng chữ):</div>
										<div style="transform:translateY(7px); line-height:28px;">&nbsp;'.convertNumberToString($oneFund['amount']).'</div>
									</div>
								</div>
								<div style="width:50%; display:flex; border-bottom:1px dashed #DDD; line-height:26px;">
									<div style="transform:translateY(8px); background: #FFF; line-height:26px;">Kèm theo:</div>
									<div style="transform:translateY(8px); width:50%">&nbsp;</div>
									<div>Chứng từ gốc</div>
								</div>
							</div>
							<p style="text-align:right">Ngày <strong>'.date('d', $oneFund['account_date']).'</strong> tháng <strong>'.date('m', $oneFund['account_date']).'</strong> năm <strong>'.date('Y', $oneFund['account_date']).'</strong></p>
							<div style="width:100%; display:inline-block;">
								<div style="width:20%; text-align:center; float:left; padding:0px 10px;">
									<p style="font-weight:bold; margin-bottom:60px">Giám đốc</p>
									<p style="font-style:italic">Ký họ tên, đóng dấu</p>
								</div>
								<div style="width:20%; text-align:center; float:left; padding:0px 10px;">
									<p style="font-weight:bold; margin-bottom:60px">Kế toán trưởng</p>
									<p style="font-style:italic">Ký, họ tên</p>
								</div>
								<div style="width:20%; text-align:center; float:left; padding:0px 10px;">
									<p style="font-weight:bold; margin-bottom:60px">Thủ quỹ</p>
									<p style="font-style:italic">Ký, họ tên</p>
								</div>
								<div style="width:20%; text-align:center; float:left; padding:0px 10px;">
									<p style="font-weight:bold; margin-bottom:60px">Người lập phiếu</p>
									<p style="font-style:italic">Ký, họ tên</p>
								</div>
								<div style="width:20%; text-align:center; float:left; padding:0px 10px;">
									<p style="font-weight:bold; margin-bottom:60px">Người nhận</p>
									<p style="font-style:italic">Ký, họ tên</p>
								</div>
							</div>
						</div>
						<!--end paymentFormContainer-->
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="'.$uid.'" onClick="$Core.fund.printThis(this, event)" class="btn btn-primary">'.$clsISO->makeIcon('bx-printer','In').'</button>
		</div>
	</div></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_get_person(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsPerson = new Person();
	###
	$person_id = Input::post('person_id', 0);
	$oneClient = $clsPerson->getOne($person_id);
	$more_information = $oneClient['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	###
	$full_name = $clsISO->getValue("name", $more_information, "");
	$address = $clsISO->getValue("address", $more_information, "");
	###
	echo @json_encode(array(
		'name' => $full_name,
		'address' => $address,
	)); die();
}
function default_list_person(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsPerson = new Person();
	$clsProperty = new Property();
	$term = Input::get("term", "");
	
	$results = array();
	$cond = "`is_trash`='0'";
	if(!empty($term)) {
		$cond.= " and JSON_EXTRACT(`more_information`,'$.name_slug') like '%".$core->replaceSpace($term)."%'";
	}
	$tmp = $clsPerson->getAll($cond);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$results[] = array(
				'id' => $val[$clsPerson->pkey],
				'label' => $more_information['name'],
				'address' => $more_information['address']
			);
		}
	}
	// Return
	echo json_encode($results);
	die();
}
function default_open_person(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsCity = new City();
	$clsCountry = new Country();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	###
	$uid = Input::post('uid');
	$gr = Input::post('gr', 'THUCTHU');
	$modal_id = $clsISO->getUniqid();
	$smarty->assign('gr', $gr);
	$smarty->assign('uid', $uid);
	$smarty->assign('modal_id', $modal_id);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$arr_type = array(
		'personal' => 'Cá nhân',
		'business' => 'Doanh nghiệp'
	);
	$smarty->assign('arr_type', $arr_type);
	// Return
	$html = $core->build('_ajax.person.tpl');
	echo json_encode(array(
		'uid' => sprintf('modal%s', $modal_id),
		'html' => $html
	)); die();
}
function default_pop_save_person(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $oneProfile,$profile_id,$clsProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsPerson = new Person();
	$person_id = (int) Input::post('person_id', 0);
	###
	$gr = Input::post('gr');
	$_type = Input::post('_type');
	$name = Input::post('name');
	$address = Input::post('address');
	$phone = Input::post('phone');
	$email = Input::post('email');
	$notes = Input::post('notes');
	$more_information = array();
	###
	$msg = "_error";
	if(!empty($name) && $clsPerson->countItem("`is_trash`=0 
		and JSON_EXTRACT(`more_information`,'$.name_slug')='".$core->replaceSpace($name)."'")){
		$msg = "_duplicated";
	} else {
		$client_id = $clsPerson->getMaxId();
		$more_information = array(
			'name' => $name,
			'name_slug' => $core->replaceSpace($name),
			'email' => $email,
			'phone' => $phone,
			'address' => $address
		);
		if($clsPerson->insert(array(
			$clsPerson->pkey => $client_id,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'id' => $client_id,
		'name' => $name,
		'address' => $address
	)); die();
}
function isEmptyRow($row) {
    foreach($row as $cell){
        if (null !== $cell) return false;
    }
    return true;
}
function default_open_import(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $oneProfile,$profile_id,$clsProfile;
	$clsFund = new Fund();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	
	$html = ""; $msg = "_error";
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		if(is_uploaded_file($_FILES['attachment']['tmp_name'])){
			$target_dir = ROOTPATH."/tmp/";
			$file_ext = explode('.',basename($_FILES["attachment"]["name"]));
			$file_ext = strtolower(end($file_ext));
			$target_file = $target_dir . time().'.'.$file_ext;
			if (@move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
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
				$index = 0; $tblData =array();
				for($row = 1; $row <= $highestRow; ++ $row) {
					$rowData = $worksheet->rangeToArray('A'. $row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
					if(isEmptyRow(reset($rowData))) { continue; } // skip empty row
					for($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$cellValue = trim($cell->getValue());
						if (PHPExcel_Shared_Date::isDateTime($cell) && is_numeric($cellValue)) {
							$date = PHPExcel_Shared_Date::ExcelToPHPObject($cellValue);
							if ($date->format('H:i:s') !== '00:00:00') {
								 // Lấy cả ngày và giờ
								$tblData[$index][] = $date->format('d/m/Y H:i:s');
							} else {
								// Chỉ lấy ngày nếu không có giờ
								$tblData[$index][] = $date->format('d/m/Y'); 
							}
						} else {
							$tblData[$index][] = $cellValue;
						}
					}
					++$index;
				}
				@unlink($inputFileName);
				if(!empty($tblData)){
					$msg = "_success";
					$html = '<div class="modal-dialog modal-xl">
						<form class="modal-content" method="POST">
							<div class="modal-header"> 
								<h5 class="modal-title"><strong>Import bảng hàng</strong></h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<style type="text/css">
									.tableFixHead{ position:relative; max-height:calc(100vh - 200px);}
									.tableFixHead thead th{position:sticky; top:0; left:0; background:var(--bs-white);}
								</style>
								<div class="bg-lighter rounded-2 p-2"><div class="form-row">
									<label class="col-form-label col-4 col-xxxl-2 text-right">Tài khoản</label>
									<div class="col-8 col-xxxl-3">
										<select name="bank_account_id" data-width="100%" class="form-control iso-select2 required">
											<option value="0">Chọn tài khoản</option>
											'.$clsProperty->getSelectByProperty('BANK_ACCOUNT', 0).'
										</select>
									</div>
								</div></div>
								<hr class="my-2" />
								<div class="tableFixHead overflow-auto">
									<table class="table table-striped">
										<thead><tr>';
										for($col=0; $col<$highestColumnIndex-1; $col++){
											$html .= '<th style="min-width:100px" width="'.(100/$highestColumnIndex).'%">
												<select name="columns['.$col.']" class="form-control form-select stock_import_field">
													<option value="">Lựa chọn</option>
													'.$clsFund->renderOptionColumnField($col).'
												</select>
											</th>';
										}
										$html .= '</tr></thead>';
										if(!empty($tblData)){ $ii = 0; // Init
											require_once(DIR_INCLUDES.'/json_master/autoload.php');
											$cachedName = sprintf('fund_%s.json', $uid);
											$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
											$encoder = new Webmozart\Json\JsonEncoder();
											$encoder->encodeFile($tblData, $cachedFile); 
											foreach($tblData as $key => $val){
												$html.= '<tr>';
													for($col=0; $col<$highestColumnIndex; $col++){
														$html.= '<td class="text-left">'.$val[$col].'</td>';
													}
												$html.= '</tr>';
												++$ii;
											}
										}
							$html .= '</table>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
								<button type="button" class="btn btn-primary" uid="'.$uid.'"
									onClick="$Core.fund.do_import(this, event)"><span>Import</span></button>
							</div>
						</form>
					</div>';
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'msg' => $msg,
		'html' => $html
	)); die();
}
function is_valid_number($str) {
    // Loại bỏ khoảng trắng đầu và cuối
    $str = trim($str);
    // Kiểm tra nếu chứa cả dấu phẩy và dấu chấm (tránh trường hợp 1,089.56)
    if (strpos($str, ',') !== false && strpos($str, '.') !== false) {
        return false;
    }
    // Nếu có dấu phẩy, kiểm tra xem đó là phân cách hàng nghìn hay dấu thập phân
    if (strpos($str, ',') !== false) {
        // Nếu số có dạng "1,089", loại bỏ dấu phẩy (coi như phân cách hàng nghìn)
        $str = str_replace(',', '', $str);
    }
    // Kiểm tra xem sau khi xử lý có phải là số không
    return is_numeric($str);
}
function default_do_import(){
	ini_set('post_max_size', 0);
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$dbconn
		,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	$clsFund = new Fund();
	$clsPerson = new Person();
	$clsProperty = new Property();
	###
	$uid = Input::post('uid');
	$columns = Input::post('columns', array());
	//$tblData = Input::post('tblData', array());
	$bank_account_id = Input::post('bank_account_id', 0);
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$tblData = array();
	$cachedName = sprintf('fund_%s.json', $uid);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
		//@unlink($cachedFile);
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
			}
		}
		if($error_field == 0){
			$errors = array();
			if(!in_array('payment_date', $arr_fields)){
				$error_field += 1;
				$errors[] = "Bạn chưa chọn trường ngày giao dịch/thanh toán";
			}
			if(!in_array('account_date', $arr_fields)){
				$error_field += 1;
				$errors[] = "Bạn chưa chọn trường ngày hiệu lực/hạch toán";
			}
			if(!in_array('transaction_code', $arr_fields)){
				$error_field += 1;
				$errors[] = "Bạn chưa chọn trường mã giao dịch";
			}
			/*if(!in_array('person', $arr_fields)){
				$error_field += 1;
				$errors[] = "Bạn chưa chọn trường người nộp";
			}*/
			if(!in_array('expense', $arr_fields) && !in_array('income', $arr_fields)){
				$error_field += 1;
				$errors[] = "Bạn chưa chọn trường khoản thu & khoản chi";
			}
			if($error_field > 0){
				$html_error = '';
				foreach($errors as $err){
					$html_error.= '&bull' . $err;
				}
				echo json_encode(array(
					'msg' => '_error_missing_field',
					'html' => $html_error
				)); die();
			}
		}
	}
	// $clsISO->print_pre($columns); die();
	$msg = "_error"; $total_inserted = 0; $k = "";
	if($error_field == 0 && !empty($tblData)){
		$msg = "_success"; $date_cached = 0;
		$total_records = @count($tblData);
		$tblData = @array_reverse($tblData);
		for($i=0; $i < ($total_records - 1); $i++){
			$uid = $clsISO->getUniqid(); 
			$insert_field = $more_information = array();
			foreach($columns as $p_key => $p_field){
				if(!empty($p_field) && !empty($tblData[$i][$p_key])){
					$p_val = $tblData[$i][$p_key];
					if($p_field == 'account_date'){
						$account_date = str_replace('/', '-', $p_val);
						$insert_field['account_date'] = strtotime($account_date);
					} else if($p_field == 'payment_date'){
						$payment_date = str_replace('/', '-', $p_val);
						$payment_date = strtotime($payment_date);
						if(date('H:i', $payment_date) == '00:00'){
							if($date_cached > 0){
								if(date('d/m/Y', $payment_date) == date('d/m/Y', $date_cached)){
									$payment_date = strtotime("+5 minutes", $date_cached);
									$date_cached = $payment_date;
								} else {
									$payment_date = strtotime('+5 minutes', $payment_date);
									$date_cached = $payment_date;
								}
							} else {
								$payment_date = strtotime('+5 minutes', $payment_date);
								$date_cached = $payment_date;
							}
						} else {
							$date_cached = $payment_date;
						}
						$insert_field['payment_date'] = $payment_date;
					} else if($p_field == 'income'){
						if(is_valid_number($p_val)){
							$gr = 'THUCTHU';
							$insert_field['gr'] = $gr;
							$insert_field['amount'] = $clsISO->toPositive($clsISO->processSmartNumber($p_val));
						}
					} else if($p_field == 'expense'){
						if(is_valid_number($p_val)){
							$gr = 'THUCCHI';
							$insert_field['gr'] = $gr;
							$insert_field['amount'] = $clsISO->toPositive($clsISO->processSmartNumber($p_val));
						}
					} else if($p_field == 'person'){
						$tmp = $clsPerson->getByCond("JSON_EXTRACT(`more_information`,'$.name_slug')='".$core->replaceSpace($p_val)."'");
						if(!empty($tmp)){
							$person_id = $tmp[$clsPerson->pkey];
						} else {
							$person_id = $clsPerson->getMaxId();
							$more_information = array(
								'name' => $p_val,
								'name_slug' => $core->replaceSpace($p_val),
								'address' => ""
							);
							$clsPerson->insert(array(
								$clsPerson->pkey => $person_id,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'reg_date' => time(),
								'upd_date' => time(),
								'user_id' => $profile_id,
								'upd_date' => $profile_id
							));
						}
						$insert_field[$p_field] = $p_val;
						$insert_field['person_id'] = $person_id;
					} else if($p_field == 'transaction_code'){
						$transaction_code = $p_val;
						$insert_field[$p_field] = $transaction_code;
					} else {
						$insert_field[$p_field] = $p_val;
					}
				}
			}
			$tmp = $clsFund->getByCond("`transaction_code`='{$transaction_code}' 
				and `bank_account_id`='{$bank_account_id}'");
			if(!empty($tmp)){
				// $insert_field['code'] = $clsFund->genCode($gr);
				$insert_field['payment_method'] = _PAYMENT_METHOD_BANK_ID;
				$insert_field['bank_account_id'] = $bank_account_id;
				$insert_field['user_id_update'] = $profile_id;
				$insert_field['upd_date'] = time();
				if($clsFund->updateOne($tmp[$clsFund->pkey], $insert_field)){
					$total_inserted += 1;
				}
			} else {
				$fund_id = $clsFund->getMaxId();
				$insert_field[$clsFund->pkey] = $fund_id;
				$insert_field['code'] = $clsFund->genCode($gr);
				$insert_field['payment_method'] = _PAYMENT_METHOD_BANK_ID;
				$insert_field['bank_account_id'] = $bank_account_id;
				$insert_field['user_id'] = $profile_id;
				$insert_field['user_id_update'] = $profile_id;
				$insert_field['reg_date'] = time();
				$insert_field['upd_date'] = time();
				// $clsISO->print_pre($insert_field); die();
				if($clsFund->insert($insert_field)){
					$total_inserted += 1;
				}
			}	
		}
		#activity log
		if($total_inserted > 0) {		
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Fund","insert");
		}	
	} else if($error_field > 0){
		echo json_encode(array(
			'msg' => '_error_field',
			'html' => "Có cột trùng lặp trong bảng cần import"
		)); die();
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'total_inserted' => $total_inserted
	)); die();
}
function default_cre_bank_tranfer(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$dbconn
		,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	$clsFund = new Fund();
	$clsPerson = new Person();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$fund_id = (int) Input::post('fund_id');
	$html = '<div class="modal-dialog modal-dialog-centered modal-sm">
		<form method="POST" enctype="multipart/form-data" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Chuyển quỹ</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group mb-2">
					<label class="form-label mb-1">Tài khoản gốc</label>
					<div class="clearfix"></div>
					<select name="bank_account_from" onChange="$Core.fund.check_amount_bank_transfer(this, event)" 
					data-width="100%" class="form-control iso-select2 required">
						<option value="0">Chọn tài khoản gốc</option>
						'.$clsProperty->getSelectByProperty('BANK_ACCOUNT',0).'
					</select>
				</div>
				<div class="d-flex justify-content-center">
					<i class=\'bx bx-chevrons-down\'></i>
				</div>
				<div class="form-group mb-2">
					<label class="form-label mb-1">Tài khoản đích</label>
					<div class="clearfix"></div>
					<select name="bank_account_to" data-width="100%" class="form-control iso-select2 required">
						<option value="0">Chọn tài khoản đích</option>
						'.$clsProperty->getSelectByProperty('BANK_ACCOUNT',0).'
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button"fund_id="'.$fund_id.'" onClick="$Core.fund.do_bank_transfer(this, event)" 
					class="btn btn-primary">Thực hiện</button>
			</div>
		</form>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_bank_transfer(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$dbconn
		,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	$clsFund = new Fund();
	$clsPerson = new Person();
	$clsProperty = new Property();
	$clsBankTransfer = new BankTransfer();
	###
	$msg = "_error";
	$fund_id = (int) Input::post('fund_id', 0);
	$bank_account_from = (int) Input::post('bank_account_from', 0);
	$bank_account_to = (int) Input::post('bank_account_to', 0);
	###
	if($bank_account_from == $bank_account_to){
		$msg = "_invalid";
	} else {
		$bank_transfer_id = $clsBankTransfer->getMaxId();
		$bank_transfer_code = $clsBankTransfer->genCode();
		// $clsISO->print_pre($bank_transfer_code); die();
		$oneFund = $clsFund->getOne($fund_id);
		//$clsBankTransfer->setDebug(true);
		if($clsBankTransfer->insert(array(
			$clsBankTransfer->pkey => $bank_transfer_id,
			'code' => $bank_transfer_code,
			'payment_date' => $oneFund['payment_date'],
			'account_date' => $oneFund['account_date'],
			'bank_account_from' => Input::post('bank_account_from', 0),
			'bank_account_to' => Input::post('bank_account_to', 0),
			'amount' => $oneFund['amount'],
			'content' => $oneFund['content'],
			'attachments' => $oneFund['attachments'],
			'reg_date' => $oneFund['payment_date'],
			'upd_date' => $oneFund['payment_date'],
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
			// Xoá giao dịch
			$clsFund->deleteOne($fund_id);
		}
	}
	// Return
	echo $msg; die();
}
function default_ops_cost(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$list_preloaders = array();
	for($i=1; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Chi vận hành - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_list_ops_cost(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsOpsCost = new OpsCost();
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$cond = "`is_trash`=0";
	$type = Input::post("type","list");
	$smarty->assign("type", $type);
	//$year = date("Y");
	//$this_month = date("m/Y");
	//$last_month = date("m/Y",strtotime("- 1 months"));
	//$total_in_year = $clsOpsCost->sumItem("amount",$cond." AND FROM_UNIXTIME(`expense_date`,'%Y')='{$year}'");
	//$total_this_month = $clsOpsCost->sumItem("amount",$cond." AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$this_month}'");	
	//$total_last_month = $clsOpsCost->sumItem("amount",$cond." AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$last_month}'");	
	$keyword = Input::post('keyword',"");
	$date_range = Input::post('date_range');
	$tmp = @explode('-', $date_range);
	$start_date = $tmp[0]; $end_date = $tmp[1];
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date." 00:00:00") : 0;
	$end_date = !empty($end_date) ? $clsISO->toTime($end_date." 23:59:59") : 0;
	if($start_date > 0 && $end_date > 0){
		$cond.= " and (`expense_date` between '{$start_date}' and '{$end_date}')";
	}
	if(!empty($keyword)) {
		$cond .= " AND (`expense_code` LIKE '%{$keyword}%' 
			OR JSON_EXTRACT(`more_information`,'$.reason') LIKE '%{$keyword}%'
		)";
	}
	#- Begin pagination
	$current_page = Input::post('page',1);
	$per_page = Input::post('per_page',30);
	$total_record = $clsOpsCost->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination	
	$list_ops_cost = $clsOpsCost->getAll($cond." order by `expense_date` DESC".$limitCond);
	// var_dump($list_ops_cost); die();
	if(!empty($list_ops_cost)){
		$arr_property_cached = $arr_setting_cached = array();
		foreach($list_ops_cost as $key => $val){
			$type_id = (int) $val['type_id'];
			$status_id = (int) $val['status_id'];
			$department_id = (int) $val['department_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$type_name = "";
			if($type_id > 0) {
				if(isset($arr_property_cached[$type_id])){
					$type_name = $arr_property_cached[$type_id];
				} else {
					$type_name = $clsProperty->getTitle($type_id);
					$arr_property_cached[$type_id] = $type_name;
				}
			}
			$list_ops_cost[$key]["type_name"] = $type_name;
			$list_ops_cost[$key]["more_information"] = $more_information;
			
			$department_name = $status_name = "";
			if($status_id > 0) {
				if(isset($arr_setting_cached[$status_id])){
					$status_name = $arr_setting_cached[$status_id];
				} else {
					$status_name = $clsSetting->getTitle($status_id);
					$arr_setting_cached[$status_id] = $status_name;
				}
			}
			if($department_id > 0) {
				if(isset($arr_setting_cached[$department_id])){
					$department_name = $arr_setting_cached[$department_id];
				} else {
					$department_name = $clsSetting->getTitle($department_id);
					$arr_setting_cached[$department_id] = $department_name;
				}
			}
			$list_ops_cost[$key]["status_name"] = $status_name;
			$list_ops_cost[$key]["department_name"] = $department_name;
		}
	}
	$smarty->assign('list_ops_cost', $list_ops_cost);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	
	// Return
	$html = $core->build('_ajax.ops_cost.tpl');
	// var_dump($html); die();
	echo json_encode(array(
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
	)); die();
}
function default_opscost_dashboard(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	###
	$list_blocks = array(
		'today' => array(
			'title' => 'Chi vận hành hôm nay',
			'bgcolor' => '#410256'
		), 'this_month' => array(
			'title' => 'Chi vận hành tháng này',
			'bgcolor' => '#001131'
		), 'prev_month' => array(
			'title' => 'So với tháng trước',
			'bgcolor' => '#804302'
		), 'avg_3month' => array(
			'title' => sprintf('Trung bình %s tháng, %s', $number_month, $year),
			'bgcolor' => '#5605dd'
		), 'all_year' => array(
			'title' => sprintf('Chi vận hành năm <u>%s</u>', $year),
			'bgcolor' => '#124c25'
		)
	);
	$smarty->assign('list_blocks', $list_blocks);
	
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$assign_list['prev_year'] = $prev_year;
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = strtotime(sprintf('01-01-%s', $end_year));
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $end_year);
	$to_date = strtotime(sprintf('%s-12-%s', $end_day, $end_year));
	$assign_list['start_date'] = $start_date;
	$assign_list['to_date'] = $to_date;
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	/*=============Title & Description Page==================*/
	$title_page = 'Thống kê chi vận hành - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_get_opscost_total(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOpsCost = new OpsCost();
	$clsProperty = new Property();
	$helper = new Helper();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	##
	define('START_YEAR', 2025);
	$year = Input::post('year', date('Y'));
	$call_from = Input::request('call_from','fund');
	##
	$uid  = $clsISO->getUniqid();
	$start_date = sprintf('01-01-%s', $year);
	$end_day_year = cal_days_in_month(CAL_GREGORIAN, 12, $year);
	$end_date = sprintf('%s-12-%s', $end_day_year, $year);
	$start_time = strtotime($start_date);
	$end_time = strtotime($end_date);
	##
	$cond = "`is_trash`=0";
	$total_year = $clsOpsCost->sumItem("amount", "{$cond} AND (`expense_date` BETWEEN {$start_time} AND {$end_time})"); 
	if($year == START_YEAR){
		$total_year+= $clsProperty->sumItem("ms_value", "`parent_id`=0 and `property_type`='_OPS_COST_CAT'");
	}
	#- TRUNG BÌNH CẢ NĂM
	$avg_in_year = 0;
	if($year == date('Y')){
		$number_month = date('n');
		$avg_in_year = round($total_year/$number_month, 3);
	} else {
		$number_month = 12;
		$avg_in_year = round($number_month/12, 3);
	}
	$list_blocks = array(
		'today' => array(
			'title' => 'Hôm nay',
			'bgcolor' => '#410256'
		), 'this_month' => array(
			'title' => 'Tháng này',
			'bgcolor' => '#001131'
		), 'prev_month' => array(
			'title' => 'So với tháng trước',
			'bgcolor' => '#804302'
		), 'avg_3month' => array(
			'title' => sprintf('Trung bình %s tháng, %s', $number_month, $year),
			'bgcolor' => '#5605dd'
		), 'all_year' => array(
			'title' => sprintf('Năm %s', $year),
			'bgcolor' => '#124c25'
		)
	);
	$more = array();
	if($call_from == 'dashboard'){
		$data = $dataPoints = $barChartData = $arr_middles = array();
		$data['axisX'] = array(
			//'titleFontColor' => '#333',
			//'lineColor' => '#333',
			//'labelFontColor' => '#C00000',
			//'tickColor' => '#333',
			'interval' => 1
		);
		###
		$end_os_time = time();
		$start_os_time = strtotime("-12 months", $end_os_time);
		$tmp = $clsOpsCost->getAll("{$cond} AND (`expense_date` BETWEEN {$start_os_time} AND {$end_os_time})", "`expense_date`,`amount`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$amount = $val['amount'];
				$expense_date = $val['expense_date'];
				$key = date('mY', $expense_date);
				if(isset($arr_middles[$key])){
					$arr_middles[$key]+= $clsISO->processSmartNumber($amount);
				} else {
					$arr_middles[$key] = $clsISO->processSmartNumber($amount);
				}
			}
			unset($tmp);
		}
		for($i=$start_os_time; $i<=$end_os_time; $i = strtotime("+1 month", $i)){
			$month = date('mY', $i);
			$total = isset($arr_middles[$month]) ? $arr_middles[$month] : 0;
			$dataPoints[] = array(
				'y'	=> $total*1,
				'label'	=> sprintf('T%s', date('m/Y', $i)),
				'indexLabel' => $clsISO->shortNumber($total)
			);
		}
		$data['type'] = 'spline';
		$data['indexLabel'] = '{symbol} - {y}';
		$data['yValueFormatString'] = '#,##0.0\"%\"';
		$data['showInLegend'] = false;
		$data['dataPoints'] = $dataPoints;
		$barChartData['data'] = $data;
		// Return
		$more = array(
			'uid' => $uid,
			'drawchart' => 1,
			'multichart' => 0,
			'barChartData' => $barChartData,
		);
		$html.= '<div class="col-12 col-md-5 mb-2 mb-lg-0">
			<ul class="list-unstyled mb-0">';
			$ii = 0;
			foreach($list_blocks as $key => $val){
				if($key == 'today'){
					$date_dmy = date('d/m/Y'); 
					$total = $clsOpsCost->sumItem("amount","{$cond} AND FROM_UNIXTIME(`expense_date`,'%d/%m/%Y')='{$date_dmy}'");
				} else if($key == 'this_month'){
					$date_my = date('m/Y'); 
					$total = $clsOpsCost->sumItem("amount","{$cond} AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$date_my}'");
				} else if($key == 'prev_month'){
					$date_my = date('m/Y', strtotime('-1 month')); 
					$total = $clsOpsCost->sumItem("amount","{$cond} AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$date_my}'");
				} else if($key == 'avg_3month'){
					$total = $avg_in_year;
				} else if($key == 'all_year'){
					$total = $total_year;
				}
				$html.= '<li class="d-flex align-items-center justify-content-between py-1">
					<span>'.html_entity_decode($val['title']).'</span> 
					<strong style="color:'.$val['bgcolor'].'" class="fs-15">'.$clsISO->formatPrice($total).$clsISO->getRate().'</strong>
				</li>';
				++$ii;
			}
			$html.= '</ul>
		</div>
		<div class="col-12 col-md-7">
			<div id="'.$uid.'" class="chartContainer h-px-175"></div>
		</div>';
	} else {
		foreach($list_blocks as $key => $val){
			if($key == 'today'){
				$date_dmy = date('d/m/Y'); 
				$total = $clsOpsCost->sumItem("amount","{$cond} AND FROM_UNIXTIME(`expense_date`,'%d/%m/%Y')='{$date_dmy}'");
			} else if($key == 'this_month'){
				$date_my = date('m/Y'); 
				$total = $clsOpsCost->sumItem("amount","{$cond} AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$date_my}'");
			} else if($key == 'prev_month'){
				$date_my = date('m/Y', strtotime('-1 month')); 
				$total = $clsOpsCost->sumItem("amount","{$cond} AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$date_my}'");
			} else if($key == 'avg_3month'){
				$total = $avg_in_year;
			} else if($key == 'all_year'){
				$total = $total_year;
			}
			$html.= '<div class="col mb-2 mb-lg-0 gotoLink" href="'.$clsISO->getLink('chart_operation_fee').'">
				<div class="p-3 rounded-2 fund_box fund_'.$key.' relative" style="background-color:'.$val['bgcolor'].'">
					<h5 class="mb-1 fs-5 fw-bold text-white">'.$clsISO->formatPrice($total).$clsISO->getRate().'</h5>
					<hr class="w-px-100 my-2" />
					<span class="text-white text-nowrap">'.html_entity_decode($val['title']).'</span>		
				</div>
			</div>';
		}
	}
	// Return
	echo json_encode(array_merge($more, array(
		'html' => $html
	)), JSON_UNESCAPED_UNICODE); die();
}
function default_load_ops_cost_category(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOpsCost = new OpsCost();
	$clsProperty = new Property();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	#- Tổng cả năm
	define('START_YEAR', 2025);
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post("month",0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$format = "%m/%Y";
		$date = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	} else {
		$format = "%Y";
		$date = $year;
	}
	###
	$arr_category = [];
	$tmp = $clsProperty->getArraySearchByKey("_OPS_COST_CAT");
	if(!empty($tmp)){
		foreach($tmp as $key => $val) {
			if($val['parent_id'] == 0){
				$arr_category[$val["property_id"]][] = $val["property_id"];
			}else{
				$arr_category[$val["parent_id"]][] = $val["property_id"];
			}
		}
	}
	$dataPoints = array();
	$data = $barChartData = array();
	$data['axisX'] = array(
		//'titleFontColor' => '#333',
		//'lineColor' => '#333',
		//'labelFontColor' => '#C00000',
		//'tickColor' => '#333',
		'interval' => 1
	);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	###
	$cond = "`is_trash`=0"; //  AND (`status_id`='"._SETING_PAID_ID."' OR `status_id`='"._SETING_RECIEVED_ID."')
	if(!empty($arr_category)){ $ii = 0;
		foreach($arr_category as $key => $val){
			if($year == START_YEAR && ($month == 0 || $month <=4)){
				$total = $clsProperty->sumItem("ms_value", "`property_type`='_OPS_COST_CAT' and {$clsProperty->pkey}='{$key}'");
				$total+= $clsOpsCost->sumItem("amount","{$cond} AND `type_id` IN (".implode(',',$val).") 
					AND FROM_UNIXTIME(`expense_date`,'{$format}')='{$date}'");
			} else {
				$total = $clsOpsCost->sumItem("amount","{$cond} AND `type_id` IN (".implode(',',$val).") 
					AND FROM_UNIXTIME(`expense_date`,'{$format}')='{$date}'");
			}
			$exploded = ($ii==0) ? true : false;
			$dataPoints[] = array(
				'y'	=> $total*1,
				'label'	=> $tmp[$key]["title"],
				'indexLabel' => $clsISO->shortNumber($total)
			);
			++$ii;
		}
	}
	$data['type'] = 'column';
	$data['indexLabel'] = '{symbol} - {y}';
	$data['yValueFormatString'] = '#,##0.0\"%\"';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData,
	)); die();
}
function default_load_chart_month_opscost(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOpsCost = new OpsCost();
	$clsProperty = new Property();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	#- Tổng cả năm
	define('START_YEAR', 2025);
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	###
	$dataPoints = array();
	$data = $barChartData = array();
	$data['axisX'] = array(
		//'titleFontColor' => '#333',
		//'lineColor' => '#333',
		//'labelFontColor' => '#C00000',
		//'tickColor' => '#333',
		'interval' => 1
	);
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	###
	$cond = "`is_trash`=0"; // AND (`status_id`='"._SETING_PAID_ID."' OR `status_id`='"._SETING_RECIEVED_ID."')
	if($month > 0){
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for($i=1; $i<=$end_day; $i++){
			$date_dmy = sprintf('%s-%s-%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
			$total = $clsOpsCost->sumItem("amount", "{$cond} AND FROM_UNIXTIME(`expense_date`,'%d-%m-%Y')='{$date_dmy}'");
			if($total > 0){
				$dataPoints[] = array(
					'y'	=> $total*1,
					'label'	=> date('d/m', strtotime($date_dmy)),
					'indexLabel' => $clsISO->shortNumber($total)
				);
			}
		}
	} else {
		for($i=1; $i<=12; $i++){
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($i), $year);
			if($year == START_YEAR && $i == 4){
				$total = $clsProperty->sumItem("ms_value", "`parent_id`=0 and `property_type`='_OPS_COST_CAT'");
			} else {
				$total = $clsOpsCost->sumItem("amount", "{$cond} AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$date_my}'");
			}
			if($total > 0){
				$dataPoints[] = array(
					'y'	=> $total*1,
					'label'	=> $date_my,
					'indexLabel' => $clsISO->shortNumber($total)
				);
			}
		}
	}
	##
	$data['type'] = 'line';
	//$data['indexLabel'] = '{symbol} - {y}';
	$data['yValueFormatString'] = '#,##0.0\"%\"';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData,
	)); die();
}
function default_load_chart_year_opscost(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOpsCost = new OpsCost();
	$clsProperty = new Property();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	#- Tổng cả năm
	define('START_YEAR', 2025);
	$uid = $clsISO->getUniqid();
	$current_year = date('Y');
	$start_year = $current_year - 5;
	###
	$dataPoints = array();
	$data = $barChartData = array();
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	###
	$cond = "`is_trash`=0 AND (`status_id`='"._SETING_PAID_ID."' OR `status_id`='"._SETING_RECIEVED_ID."')";
	for($year=$start_year; $year<=$current_year; $year++){
		if($year == START_YEAR){
			$total = $clsProperty->sumItem("ms_value", "`parent_id`=0 and `property_type`='_OPS_COST_CAT'");
			$total+= $clsOpsCost->sumItem("amount", "{$cond} AND FROM_UNIXTIME(`expense_date`,'%Y')='".$clsISO->parseNumber($year)."'");
		} else {
			$total = $clsOpsCost->sumItem("amount", "{$cond} AND FROM_UNIXTIME(`expense_date`,'%Y')='".$clsISO->parseNumber($year)."'");
		}
		$dataPoints[] = array(
			'y'	=> $total*1,
			'label'	=> $year,
			'indexLabel' => $clsISO->shortNumber($total)
		);
	}
	##
	$data['type'] = 'line';
	//$data['indexLabel'] = '{symbol} - {y}';
	$data['yValueFormatString'] = '#,##0.0\"%\"';
	$data['showInLegend'] = false;
	// $data['legendText'] = "";
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData,
	)); die();
}
function default_load_chart_department_opscost(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsOpsCost = new OpsCost();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	#- Tổng cả năm
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$format  = '%m/%Y';
		$date = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
	} else {
		$format  = '%Y';
		$date = $year;
	}
	###
	$dataPoints = array();
	$data = $barChartData = array();
	$html = '<div id="'.$uid.'" class="chartContainer h-px-300"></div>';
	###
	$cond = "`is_trash`=0"; //  AND (`status_id`='"._SETING_PAID_ID."' OR `status_id`='"._SETING_RECIEVED_ID."')
	$list_settings = $clsSetting->getAll("`_type`='_DEPARTMENT' order by `order_no` ASC");
	if(!empty($list_settings)){
		foreach($list_settings as $key => $val){
			$total = $clsOpsCost->sumItem("amount", "{$cond} AND `department_id`='".$val[$clsSetting->pkey]."' 
				AND FROM_UNIXTIME(`expense_date`,'{$format}')='{$date}'");
			$dataPoints[] = array(
				'y' => $total*1,
				'label' => $val['title'],
				'z' => $clsISO->shortNumber($total)
			);
		}
	}
	##
	$barChartData['data'] = array(
		array(
			"type"    => "line",
			//"axisYType" => "secondary",
			//"color"	 => "#1d6a01",
			//"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		),
		array(
			"type"  => "column",
			"indexLabel" => "{z}",
			// "color"	 => "#9f223a",
			//"showInLegend" => true,
			"dataPoints"   => $dataPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cond' => $cond,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData,
	)); die();
}
function default_office_operating_cost_stats(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsOpsCost = new OpsCost();
	#
	$month = (int) Input::post("month", 0);
	$year = (int) Input::post("year", date('Y'));
	if($month > 0){
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$sql_query = " AND FROM_UNIXTIME(`expense_date`,'%m/%Y')='{$my}'";
	} else {
		$sql_query = " AND FROM_UNIXTIME(`expense_date`,'%Y')='{$year}'";
	}
	$arr_offices = $clsSetting->getCacheItems('_OFFICE');
	$arr_category = $clsSetting->getCacheItems('_OFFICE_COST_CATEGORY');
	#
	$html = '<div class="table-container overflow-x-auto no-shadow">
		<table cellpadding="0" cellspacing="0" class="table table-bordered">
			<thead><tr>
				<th style="'.($deviceType=='phone'? 'width:50vw; max-width:50vw' : '').'" class="bg-lighter h-px-35" rowspan="2">Hạnh mục</th>
				<th class="bg-lighter h-px-35 text-center" colspan="6">Văn phòng</th>
				<th class="bg-lighter h-px-35" rowspan="2">TỔNG</th>
			</tr>
			<tr>';
			if(!empty($arr_offices)){ $ii= 0;
				foreach($arr_offices as $val){
					$office_id = $val[$clsSetting->pkey];
					${'total_offices_'.$office_id} = 0;
					$html.= '<th style="border-right-width:1px !important" class="bg-lighter'.($ii==0?' no-sticky':'').' h-px-35 text-center">'.$val['title'].'</th>';
					++$ii;
				}
			}
			$html.= '</tr></thead>';
			$total_alls = 0;
			if(!empty($arr_category) && !empty($arr_offices)){ $ii = 1;
				$total_category = count($arr_category);
				$arr_OPS_COST_CAT = $clsProperty->getCacheItems('_OPS_COST_CAT');
				foreach($arr_category as $key => $val){
					$office_cost_cat_id = $val[$clsSetting->pkey];
					${'total_categories_'.$office_cost_cat_id} = 0;
					$html.= '<tr>';
						$html.= '<td class="align-center'.($ii==$total_category?'':' text-nowrap').' fw-bold">'.$val['title'].'</td>';
						foreach($arr_offices as $okey => $oval){
							$ops_cat_id = 0;
							$office_id = $oval[$clsSetting->pkey];
							if(!empty($arr_OPS_COST_CAT)){
								foreach($arr_OPS_COST_CAT as $nkey => $nval){
									$more_information = $nval['more_information'];
									$more_information = $clsISO->to_array_json($more_information);
									if($core->get_field($more_information, "office_id", 0) == $office_id 
										&& $core->get_field($more_information, "office_cost_cat_id", 0) == $office_cost_cat_id){
										$ops_cat_id = (int) $nval[$clsProperty->pkey];
										break;
									}
								}
							}
							$total = $clsOpsCost->sumItem("amount", "`type_id`='{$ops_cat_id}'".$sql_query);
							$total_alls += $total;
							${'total_offices_'.$office_id} += $total;
							${'total_categories_'.$office_cost_cat_id} += $total;
							$html.= '<td class="align-center text-nowrap text-right">
								'.$clsISO->formatPrice($total)." ".$clsISO->getRate().'
							</td>';
						}
						$html.= '<td class="align-center text-nowrap fw-bold text-right">
							'.$clsISO->formatPrice(${'total_categories_'.$office_cost_cat_id})." ".$clsISO->getRate().'
						</td>';
					$html.= '</tr>';
					++$ii;
				}
			}
			$html.= '<tr>
				<td class="bg-lighter fw-bold text-center">TỔNG CỘNG</td>';
			if(!empty($arr_offices)){
				foreach($arr_offices as $val){
					$office_id = $val[$clsSetting->pkey];
					$html.= '<td class="bg-lighter text-nowrap fw-bold text-right">
						'.$clsISO->formatPrice(${'total_offices_'.$office_id})." ".$clsISO->getRate().'
					</td>';
				}
			}
		$html.= '<td class="bg-lighter fw-bold text-nowrap text-right">
					'.$clsISO->formatPrice($total_alls)." ".$clsISO->getRate().'
				</td>
			<tr>
		</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_cash(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsCashFund = new CashFund();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$field = "{$clsProperty->pkey},`property_code`,`title`,`intro`";
	$list_bank_accounts = $clsProperty->getAll("`property_type`='BANK_ACCOUNT' order by `order_no` ASC", $field);
	
	$cash_fund_id = 0; $more_information = array();
	$oneCash = $clsCashFund->getByCond("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'");
	if(!empty($oneCash)){
		$cash_fund_id = $oneCash[$clsCashFund->pkey];
		$more_information = $oneCash['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
	} else {
		$oneCash = $clsCashFund->getByCond("1=1 order by `upd_date` DESC");
		if(!empty($oneCash)){
			$more_information = $oneCash['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
		} else {
			foreach($list_bank_accounts as $key => $val){
				$bank_account_id = $val[$clsProperty->pkey];
				$more_information[$bank_account_id] = 0;
			}
		}
	}
	$smarty->assign('oneCash', $oneCash);
	$smarty->assign('cash_fund_id', $cash_fund_id);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_bank_accounts', $list_bank_accounts);
	// Return
	$html = $core->build('_ajax.open_cash.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_cash_fund(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsCashFund = new CashFund();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$cash_fund_id = (int) Input::post('cash_fund_id');
	$more_information = Input::post('more_information');
	if($cash_fund_id > 0){
		$oneCash = $clsCashFund->getOne($cash_fund_id, "logs,more_information");
		$_logs = $oneCash['logs'];
		$_more_information = $oneCash['more_information'];
		$_logs = $clsISO->to_array_json($_logs);
		$_more_information = $clsISO->to_array_json($_more_information);
		$_logs[$clsISO->getUniqid()] = array(
			'upd_date' => time(),
			'user_id' => $profile_id,
			'more_information' => $_more_information
		);
		if($clsCashFund->updateOne($cash_fund_id, array(
			'logs' => json_encode($_logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		$logs = array();
		$logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'more_information' => $more_information
		);
		if($clsCashFund->insert(array(
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
