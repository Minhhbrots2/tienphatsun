<?php
function default_default(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page,$oneSetting,$clsConfiguration;
	global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsCategory = new Category();
	$assign_list["clsCategory"] = $clsCategory;
	#
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$cat_id = (int) Input::post('cat_id', 0);
		$link = '';
		if($cat_id > 0) $link .= '&cat_id='.$cat_id;
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$pUrl = '';
	/*Get type of list news*/
	$type_list = Input::get('type_list', "");
	$cat_id = (int) Input::get('cat_id', 0);
	$assign_list["type_list"] = $type_list;
	$assign_list["cat_id"] = $cat_id;
	/**/
	$classTable = "Download";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*List all item*/
	$cond = "1='1'";
	if($cat_id > 0){
		$cond .= " and (cat_id='$cat_id')";
		$pUrl.='&cat_id='.$cat_id;
	}
	#Filter By Keyword
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	if(!empty($keyword)){
		$slug = $core->replaceSpace($keyword);
		$cond .= " and (slug like '%".$slug."%' 
			or slug_en like '%{$slug}%'
		)";	
	}
	$assign_list["pUrl"] = $pUrl;
	$cond2 = $cond;
	if($type_list=='Active'){
		$cond .= " and is_trash=0";
	}
	if($type_list=='Trash'){
		$cond .= " and is_trash=1";
	}
	$orderBy = " order_no desc";
	#-------Page Divide---------------------------------------------------------------
	$recordPerPage 	= 20;
	$currentPage = isset($_GET["page"])? $_GET["page"] : 1;
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";
	// $totalRecord = $clsClassTable->countItem($cond);
	
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
	#-------End Page Divide-----------------------------------------------------------
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	//print_r($cond." order by ".$orderBy.$limit);die();
	$assign_list["allItem"] = $allItem;
	#
	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"] = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_edit(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $oneSetting;
	global $core, $clsModule, $clsButtonNav,$dbconn, $clsConfiguration;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsCategory = new Category();
	$assign_list["clsCategory"] = $clsCategory;
	$cat_id = (int) Input::get('cat_id', 0);
	#
	$classTable = "Download";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	$assign_list['pvalTable'] = $pvalTable;
	
	$title = ($_LANG_ID == 'vn') ? 'title' : 'title_'.$_LANG_ID;
	$slug = ($_LANG_ID == 'vn') ? 'slug' : 'slug_'.$_LANG_ID;
	$content = ($_LANG_ID == 'vn') ? 'content' : 'content_'.$_LANG_ID;
	$assign_list["title"] = $title;
	$assign_list["slug"] = $slug;
	$assign_list["content"] = $content;
	#
	$oneItem = array(
		'cat_id' => 0,
		$title => '',
		$content => '',
		
		'attachment_file' => '',
		'attachment_url' => '',
		'image' => ''
	);
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		$cat_id = $oneItem['cat_id'];
	}
	$assign_list["cat_id"] = $cat_id;
	$assign_list["oneItem"] = $oneItem;
	#
	if($string!='' && $pvalTable==0){
		header('location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');
	}
	#
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form();
	$clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
	$assign_list["clsForm"] = $clsForm;
	$clsForm->addInputTextArea("full",$content,"",$content,255,25,5,1,"style='width:100%'");
	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		if($pvalTable>0){
			$set = ""; $firstAdd = 0;
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if(($tmp[1]=="attachment_url" && !is_uploaded_file($_FILES['attachment_file']['tmp_name'])) || $tmp[1]!="attachment_url"){
						if($firstAdd==0){
							$set .= $tmp[1]."='".addslashes($val)."'";
							$firstAdd = 1;
						} else{
							$set .= ",".$tmp[1]."='".addslashes($val)."'";
						}
					}					
				}
			}
			$set .= ",user_id_update='".addslashes($user_id)."',upd_date='".time()."'";
			$set .= ",slug='".$core->replaceSpace($_POST['iso-title'])."',is_online='".Input::post('is_online',0)."'";
			if(MULTIPLE_LANG){
				$set .= ",slug_en='".$core->replaceSpace($_POST['iso-title_en'])."'";
			}
			# - Upload
			$attachment_file = '';
			if(is_uploaded_file($_FILES['attachment_file']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($_FILES["attachment_file"],"/attachments","jpg,jpeg,gif,png,swf,doc,docx,xls,xlsx,pdf");
				if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
					$msg = "_success";
					// Set the file metadata for drive
					$title = 'FH_'.time().'_'.$_FILES["attachment_file"]["name"];
					$mimeType = $_FILES["attachment_file"]["type"];
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file);
					$attachment_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					@unlink(ROOTPATH . $upload_file);
				}
			}
			if(!empty($attachment_file) && $attachment_file != '0'){
				$set .= ",attachment_url='".addslashes($attachment_file)."'";
			}
			# - Image
			if(_isoman_use){
				$image = Input::post('isoman_url_image');
			} else {
				$image = Input::post('image_src');
			}
			if(!empty($image) && $image != '0'){
				$set .= ",image='".addslashes($image)."'";
			}
			#
			$pUrl = '';
			$cat_id = (int) Input::post('cat_id', 0);
			if($cat_id > 0) {
				$pUrl .= '&cat_id='.$cat_id;
				$set .= ",cat_id='{$cat_id}'";
			}
			if($clsClassTable->updateOne($pvalTable,$set)) {
				if($_POST['button']=='_EDIT'){
					header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$pvalTable.'&message=updateSuccess');
				}else{
					header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateSuccess');
				}
			} else{
				header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateFailed');
			}
		}
		else{
			$value = ""; $firstAdd = 0; $field = "";
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if(($tmp[1]=="attachment_url" && !is_uploaded_file($_FILES['attachment_file']['tmp_name'])) || $tmp[1]!="attachment_url"){
						if($firstAdd==0){
							$field .= $tmp[1];
							$value .= "'".addslashes($val)."'";
							$firstAdd = 1;
						} else{
							$field .= ','.$tmp[1];
							$value .= ",'".addslashes($val)."'";
						}
					}
				}
			}
			#
			$download_id = $clsClassTable->getMaxId();
			$order_no = $clsClassTable->getMaxOrderNo();
			#
			$field .= ",user_id,user_id_update,reg_date,upd_date,slug,{$pkeyTable},order_no,is_online";
			$value .= ",'".$user_id."','".$user_id."','".time()."','".time()."','".$core->replaceSpace(Input::post('iso-title'))."'";
			$value .= ",'".$download_id."','".$order_no."','".Input::post('is_online', 0)."'";
			if(MULTIPLE_LANG){
				$field.= ",slug_en";
				$value.= ",'".$core->replaceSpace(Input::post('iso-title_en'))."'";
			}
			# - Upload
			$attachment_file = '';
			if(is_uploaded_file($_FILES['attachment_file']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($_FILES["attachment_file"],"/attachments","jpg,jpeg,gif,png,swf,doc,docx,xls,xlsx,pdf");
				if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
					$msg = "_success";
					// Set the file metadata for drive
					$title = 'FH_'.time().'_'.$_FILES["attachment_file"]["name"];
					$mimeType = $_FILES["attachment_file"]["type"];
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file);
					$attachment_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					@unlink(ROOTPATH . $upload_file);
				}
			}
			if(!empty($attachment_file) && $attachment_file != '0'){
				$field .= ",attachment_url";
				$value .= ",'".addslashes($attachment_file)."'";
			}
			# - Image
			if(_isoman_use){
				$image = Input::post('isoman_url_image');
			} else {
				$image = Input::post('image_src');
			}
			if(!empty($image) && $image != '0'){
				$field .= ",image";
				$value .= ",'".addslashes($image)."'";
			}
			#
			$pUrl = '';
			$cat_id = (int) Input::post('cat_id', 0);
			if($cat_id > 0){
				$field.= ",cat_id";
				$value.= ",'{$cat_id}'";
				$pUrl .= '&cat_id='.$cat_id;
			}
			#
			if($clsClassTable->insertOne($field,$value,false)){
				if ($_POST['button'] == '_EDIT') {
					header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$pvalTable.'&message=insertSuccess');
				} else {
					header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertSuccess');
				}
			} else {
				header('location: '.PCMS_URL.'/?mod='.$mod.'&message=insertFailed');
			}
		}
	}
}
function default_trash(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Download";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$cat_id = Input::get('cat_id', 0);
	#
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
	}
}
function default_restore(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Download";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$downloadcat_id = isset($_GET['downloadcat_id'])? $_GET['downloadcat_id'] : "";
	
	$pUrl = '';
	if(intval($downloadcat_id)> 0){
		$pUrl .= '&downloadcat_id='.$downloadcat_id;
	}
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
	}
}
function default_delete(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Download";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$downloadcat_id = isset($_GET['downloadcat_id'])? $_GET['downloadcat_id'] : "";
	
	$pUrl = '';
	if(intval($downloadcat_id)> 0){
		$pUrl .= '&downloadcat_id='.$downloadcat_id;
	}
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
	}
}
function default_move(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Download";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$downloadcat_id = isset($_GET['downloadcat_id'])? $_GET['downloadcat_id'] : "";
	$direct = isset($_GET['direct'])? $_GET['direct']:'';
	
	$one = $clsClassTable->getOne($pvalTable);
	$order_no = $one['order_no'];
	if(($string!='' && $pvalTable == 0) || $direct==''){
		header('location: '.PCMS_URL.'/?mod='.$mod);
	}
	
	$where = '1=1 and is_trash=0';
	$pUrl = '';
	if(intval($downloadcat_id) > 0){
		$where.=" and downloadcat_id='$downloadcat_id'";
		$pUrl .= '&downloadcat_id='.$downloadcat_id;
	}
	if($direct=='moveup'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movedown'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movetop'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no > $order_no order by order_no asc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']-1)."'");	
		}
	}
	if($direct=='movebottom'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no < $order_no order by order_no desc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']+1)."'");	
		}
	}
	header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=PositionSuccess');
}
/*========== SITE NEWS CATEGORY =============*/
function default_category(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsConfiguration ;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$_LANG_ID,$clsISO;
	$user_id = $core->_USER['user_id'];
	$assign_list["msg"] = isset($_GET['message'])?$_GET['message']:'';
	#
	$classTable = "Category";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	
	$type = '_DOWNLOAD';
	$assign_list["type"] = $type;
}
?>