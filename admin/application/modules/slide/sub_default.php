<?php 
function default_default(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	###
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	$arr_domain = [];
	foreach ($list_domains as $key => $val) {
		$arr_domain[$val["domain"]] = $val;
	}
	$assign_list["arr_domain"] = $arr_domain;
	###
	###
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		$type = Input::post('type',"home");
		if($type > 0) {
			$link .= '&type='.$type;
		}
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$keyword = Input::get('keyword', "");
	$type = Input::get('type', "home");
	
	$assign_list["keyword"] = $keyword;
	$assign_list["type"] = $type;
	/*List all item*/
	$cond = "1='1'";
	if(!empty($type)){
		$pUrl.='&type='.$type;
		$cond .= " and `slide_type` = '{$type}'";
	}
	#Filter By Keyword
	if(!empty($keyword)){
		$cond .= " and (`slug` LIKE '%".$core->replaceSpace($keyword)."%' OR `title` '%|".$keyword."|%')";
	}
	$cond2 = $cond;
	if(!empty($type_list)){
		if($type_list=='Trash'){
			$cond .= " and is_trash=1";
		} else {
			$cond .= " and is_trash=0";
		}
	}
	
	$orderBy = " `order_no` DESC";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 50;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsClassTable->countItem($cond);
	$pUrl .= '&page='.$current_page;	
	$assign_list["pUrl"] = $pUrl;
	
	$link_page_current = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
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
		if($tmp[0]!='page'&&$tmp[0]!='vpc_status')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $record_per_page,
		'link'	=> PCMS_URL.'/index.php'.$link_page_current_2
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links();
	$assign_list["html_pager"] = $html_pager;
	#
	$offset = ($current_page-1)*$record_per_page;
	$limit = " limit {$offset},{$record_per_page}";
	#-------End Page Divide-----------------------------------------------------------
//	$clsClassTable->setDeBug(1);
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	foreach ($allItem as $key => $val) {
		$images = $clsISO->to_array_json($val["images"]);
		$allItem[$key]["image"] = !empty($images[0]) ? $images[0] : "";
	}
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
}
function default_open(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;
	$user_id = $core->_USER['user_id'];
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	#
	$pvalTable = (int) Input::post($pkeyTable, 0);
	$type = Input::post('type', "home");
	$action = "_edit";
	$titlePage = "Thêm mới";
	$html_subcategory_options = "";
	$oneItem = array();
	$list_blocks = $list_buildings = $more_information = array();
	if($pvalTable > 0){
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneItem = $clsClassTable->getOne($pvalTable);
		$images = $clsISO->to_array_json($oneItem["images"]);
		$oneItem["image"] = !empty($images[0]) ? $images[0] : "";
	}
	
	$smarty->assign('list_domains', $list_domains);
	$smarty->assign('clsClassTable', $clsClassTable);
	$smarty->assign('pkeyTable', $pkeyTable);
	$smarty->assign('pvalTable', $pvalTable);
	$smarty->assign('action', $action);
	$smarty->assign('pvalTable', $pvalTable);
	$smarty->assign('type', $type);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.open.tpl');
	echo $html; die();
}
function default_save(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	##
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	##
	$pvalTable = (int) Input::post($pkeyTable, 0);
	$title = Input::post('title', "");
	$link = Input::post('link', "");
	$type = Input::post('type', "home");
	$image = Input::post('image', "");
	$image_mobile = Input::post('image_mobile', "");
	$content = Input::post('content', "");
	$_site = Input::post('_site', "");
	if($pvalTable > 0){
		$oSlide = $clsClassTable->getOne($pvalTable);
		if($clsClassTable->updateOne($pvalTable, array(
			'title' => $title,
			'slide_type' => $type,
			'link' => $link,
			'content' => addslashes($content),
			'images' => json_encode([$image], JSON_UNESCAPED_UNICODE),
			'image_mobile' => $image_mobile,
			'_site' => $_site,
			'user_id_update' => $user_id,
			'upd_date' => time(),
		))){
			$msg = "_success";
		}
	} else {
		if($clsClassTable->insert(array(
			$pkeyTable => $clsClassTable->getMaxId(),
			'title' => $title,
			'slide_type' => $type,
			'link' => $link,
			'content' => addslashes($content),
			'images' => json_encode([$image], JSON_UNESCAPED_UNICODE),
			'image_mobile' => $image_mobile,
			'_site' => $_site,
			'reg_date' => time(),
			'upd_date' => time(),
			'is_online' => 1,
			'is_trash' => 0,
			'order_no' => $clsClassTable->getMaxOrderNo(),
			'user_id' => $user_id,
			'user_id_update' => $user_id,
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_trash(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$type = Input::get("type","home");
	#
	$pUrl = '';
	if(!empty($type)){
		$pUrl .= '&type='.$type;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');
		exit();
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){	
		#activity log		
//		$clsActivityLog = new ActivityLog();
//		$log = $clsActivityLog->addActivityLog("Slide","trash");
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
		exit();
	}
}
function default_restore(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	#
	$type = Input::get("type","home");
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	#
	$pUrl = '';
	if(!empty($type)){
		$pUrl .= '&type='.$type;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');
		exit();
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		#activity log		
//		$clsActivityLog = new ActivityLog();
//		$log = $clsActivityLog->addActivityLog("Slide","restore");
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
		exit();
	}
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$type = Input::get("type","home");
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(!empty($type)){
		$pUrl .= '&type='.$type;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');
		exit();
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		exit();
	}
}
function default_move(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Slide";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#
	$type = Input::get("type","home");
	$direct = Input::get('direct',"moveup");
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$one = $clsClassTable->getOne($pvalTable);
	$order_no = $one['order_no'];
	if(($string!='' && $pvalTable == 0) || $direct==''){
		header('Location: '.PCMS_URL.'/?mod='.$mod);
	}
	
	$pUrl = '';
	$where = '1=1 and is_trash=0';
	if(!empty($type)){
		$pUrl .= '&type='.$type;
		$where.=" and (slide_type='$type')";
	}
	if($direct=='moveup'){
		$lst = $clsClassTable->getAll($where." and order_no > {$order_no} order by order_no asc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movedown'){
		$lst = $clsClassTable->getAll($where." and order_no < {$order_no} order by order_no desc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movebottom'){
		$lst = $clsClassTable->getAll($where." and order_no > {$order_no} order by order_no asc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no > {$order_no} order by order_no asc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']-1)."'");	
		}
	}
	if($direct=='movetop'){
		$clsClassTable->setDeBug(1);
		$lst = $clsClassTable->getAll($where." and order_no < {$order_no} order by order_no desc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no < {$order_no} order by order_no desc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']+1)."'");	
		}
	}
	header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=PositionSuccess');
}

function default_upload_file(){
//	 ini_set('display_errors', '1');
//	 error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error";
	$upload_files = $_FILES["upload_file"];
	/*if(!empty($upload_files) && !empty($upload_files["name"]) && is_uploaded_file($upload_files['tmp_name'])) {
		$extension = strtolower(substr(strrchr($upload_files["name"],"."),1));
		$clsUploadFile = new UploadFile();
		$upload_file = $clsUploadFile->uploadItem($upload_files,"/slide",EXTENSION_IMAGE_UPLOAD);
		if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
			$msg = "_success";
			// Set the file metadata for drive
			$title = 'MOC_'.time().'_'.$upload_files["name"];
			$mimeType = $upload_files["type"];
			$clsGoogleDrive = new GoogleDrive();
			$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file, GOOGLE_DRIVE_SLIDER_MOC_ID);
			@unlink(ROOTPATH . $upload_file);
			$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
		}
	}*/
	$msg = "_success";
	$upload_file = "https://drive.google.com/file/d/158ryd-Lh-IwRAJIWkWyWipEj3u5VOIns/view";
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'upload_file' => $upload_file
	)); die();
}

?>