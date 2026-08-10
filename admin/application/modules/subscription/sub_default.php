<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # IZCMS Module Private By Technical Group(buivanthiem.it@gmail.com)# ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 by Technical Group       # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- NOT FREE SOFTWARE ----------------              # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page,$oneSetting;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	/*Get type of list news*/
	$type_list = isset($_GET['type_list']) ? $_GET['type_list'] : '';
	$assign_list["type_list"] = $type_list;
	/**/
	$classTable = "Subscribe";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	#
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		if($_POST['keyword']!='' && $_POST['keyword']!='Transport title, intro'){
			$link .= '&keyword='.$_POST['keyword'];
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
	}
	
	$cond = "is_trash=0";
	#- Filter By Keyword
	if(isset($_GET['keyword']) && $_GET['keyword']!=''){
		$cond .= " and (name like '%".$_GET['keyword']."%' or email like '%".$_GET['keyword']."%')";
		$assign_list["keyword"] = $_GET['keyword'];
	}
	$cond2 = $cond;
	if($type_list=='Active'){
		$cond .= " and is_trash=0";
	}
	if($type_list=='Trash'){
		$cond .= " and is_trash=1";
	}

	$orderBy = " reg_date asc";
	#-------Page Divide---------------------------------------------------------------
	$recordPerPage 	= 20;
	$currentPage = isset($_GET["page"])? $_GET["page"] : 1;
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";
	$lstAllItem = $clsClassTable->getAll($cond);
	$totalRecord = (is_array($lstAllItem)&&count($lstAllItem)>0)?count($lstAllItem):0;
	$totalPage = ceil($totalRecord / $recordPerPage);
	$assign_list['totalRecord'] = $totalRecord;
	$assign_list['recordPerPage'] = $recordPerPage;
	$assign_list['totalPage'] = $totalPage;
	$assign_list['currentPage'] = $currentPage;
	$listPageNumber =  array();
	for ($i=1; $i<=$totalPage; $i++){
		$listPageNumber[] = $i;
	}
	$assign_list['listPageNumber'] = $listPageNumber;
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	$link_page_current = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current'] = $link_page_current;
	#
	$link_page_current_2 = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page'&&$tmp[0]!='type_list')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;

	#-------End Page Divide-----------------------------------------------------------
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); //print_r($cond." order by ".$orderBy.$limit);die();
	$assign_list["allItem"] = $allItem;

	if(!empty($allItem)) {
		$htmlEmail = '';
		for($i=0;$i<count($allItem);$i++){
			$htmlEmail.= ($i==0 ? '' : ', ').$allItem[$i]['email'];
		}
		$assign_list["htmlEmail"] = $htmlEmail;
	}
	#
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_delete(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Subscribe";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$string = isset($_GET[$pkeyTable]) ? $_GET[$pkeyTable] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable == ""){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
	}
			
	if($clsClassTable->deleteOne($pvalTable)){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=DeleteSuccess');
	}
}
function default_export(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Subscribe";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	$file_type = Input::get('file_type', 'excel');
	
	require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    $callStartTime = microtime(true);
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	$creator = PAGE_NAME;	
	$titlePage = 'Subscribe list email';
	// Set document properties
	$objPHPExcel->getProperties()->setCreator($creator)
								 ->setLastModifiedBy("")
								 ->setTitle($titlePage)
								 ->setSubject($titlePage)
								 ->setDescription("")
								 ->setKeywords("email list")
								 ->setCategory($creator);						 
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Name');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Email');
	$listItem = $clsClassTable->getAll("1=1 order by reg_date DESC");
	if(!empty($listItem)){
		$rowbegin = 2;
		for($i=0;$i<count($listItem);$i++){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($rowbegin), $listItem[$i][$clsClassTable->pkey]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($rowbegin), $clsClassTable->getName($listItem[$i][$clsClassTable->pkey]));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($rowbegin), $clsClassTable->getEmail($listItem[$i][$clsClassTable->pkey]));
			++$rowbegin;
		}
	}
	// Rename worksheet
	//$clsISO->print_pre($objPHPExcel); die();
	
	$objPHPExcel->getActiveSheet()->setTitle($titlePage);
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	// Redirect output to a client's web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="SubscribeEmailList_'.$clsISO->formatDate(time()).'.'.$file_type.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, ($file_type=='csv'?'CSV':'Excel5'));
	ob_end_clean();
    $objWriter->save('php://output');
    exit; echo(0); die();
}
?>