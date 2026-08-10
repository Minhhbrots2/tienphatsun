<?php 
function default_default(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	###
	$classTable = "Company";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	
	###
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$keyword = Input::get('keyword', "");
	$assign_list["keyword"] = $keyword;
	
	$cond = "1=1";
	#Filter By Keyword
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	if(!empty($type_list)){
		if($type_list=='Trash'){
			$cond .= " and is_trash=1";
		} else {
			$cond .= " and is_trash=0";
		}
	}
	$orderBy = " reg_date DESC";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 50;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsClassTable->countItem($cond);
	$pUrl .= '&page='.$current_page;
	
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
//	$clsISO->print_pre($allItem); die();
	$assign_list["total_record"] = $total_record;
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
}
function default_open(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	$clsCompany= new Company();
	$clsProperty= new Property();
	#
	$company_id = (int) Input::post('company_id', 0);
	$action = "_edit";
	$titlePage = "Thêm mới";
	$list_blocks = $list_buildings = $more_information = array();
	if($company_id > 0){
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneItem = $clsCompany->getOne($company_id);
		$more_information = !empty($oneItem['more_information'])? $clsISO->to_array_json($oneItem['more_information']) : [];
		$smarty->assign('oneItem', $oneItem);
		$smarty->assign('more_information', $more_information);
	}
	$smarty->assign('action', $action);
	$smarty->assign('company_id', $company_id);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_buildings', $list_buildings);
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('html_subcategory_options', $html_subcategory_options);
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
	$classTable = "Company";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	##
	$pvalTable = (int) Input::post($pkeyTable, 0);
	$title_vn = addslashes(Input::post('title_vn'));
	$title = addslashes(Input::post('title'));
	$phone = Input::post('phone');
	$zalo = Input::post('zalo');
	$image = Input::post('image');
	$banner = Input::post('banner');
	$address = addslashes(Input::post('address'));
	$show_room = addslashes(Input::post('show_room'));
	$factory = addslashes(Input::post('factory'));
	$website = addslashes(Input::post('website'));
	$intro = addslashes(Input::post('intro'));
	$type = (int)Input::post('type',0);
	$more_information = [
		"show_room"	=>	$show_room,
		"factory"	=>	$factory,
		"website"	=>	$website,
	];
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		if($clsClassTable->updateOne($pvalTable, array(
			'title_vn' 	=> $title_vn,
			'title' 	=> $title,
			'slug_vn'	=>	$core->replaceSpace($title_vn),
			'slug'		=>	$core->replaceSpace($title),
			'type' 		=> $type,
			'phone' 	=> $phone,
			'image' 	=> $image,
			'banner' 	=> $banner,
			'zalo' 		=> $zalo,
			'address' 	=> $address,
			'intro' 	=> $intro,
			'more_information' 	=> json_encode($more_information),
			'upd_date'	=>	time()
		))){
			$msg = "_success";
		}
	} else {
		if($clsClassTable->insert(array(
			$pkeyTable => $clsClassTable->getMaxId(),
			'title_vn' 	=> $title_vn,
			'title' 	=> $title,
			'slug_vn'	=>	$core->replaceSpace($title_vn),
			'slug'		=>	$core->replaceSpace($title),
			'type' 		=> $type,
			'phone' 	=> $phone,
			'image' 	=> $image,
			'banner' 	=> $banner,
			'zalo' 		=> $zalo,
			'address' 	=> $address,
			'intro' 	=> $intro,
			'more_information' 	=> json_encode($more_information),
			'reg_date'	=>	time(),
			'upd_date'	=>	time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Company";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		exit();
	}
}
function default_upload_file(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	##
	$msg = "_error";
	$upload_file = "";
	if(!empty($_FILES['upload_file']['name'])){
		if(is_uploaded_file($_FILES['upload_file']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			// $clsISO->print_pre($clsUploadFile); die();
			$upload_file = $clsUploadFile->uploadItem($_FILES["upload_file"],"/Company",EXTENSION_FILE_UPLOAD);
			$msg = "_success";
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'upload_file' => $upload_file
	)); die();
}
?>