<?php
function default_default(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page,$oneSetting;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	/*Get type of list news*/
	$type_list = Input::get('type_list');
	$assign_list["type_list"] = $type_list;
	/*End Get type of list news*/
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$link = '';
		$keyword = Input::post('keyword');
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		header('location: '.PCMS_URL.'/index.php?mod='.$mod.$link);
	}
	/**/
	$classTable = "EmailTemplate";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*List all item*/
	
	$cond="1=1 and is_trash=0";
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	
	if(!empty($keyword)){
		$slug = $core->replaceSpace($keyword);
		$cond .= " and (title like '%".$keyword."%' or slug like '%".$slug."%')";	
	}
	$cond2 = $cond;
	if(!empty($type_list)){
		if($type_list=='Active'){
			$cond .= " and is_trash=0";
		} else if($type_list=='Trash'){
			$cond .= " and is_trash=1";
		}
	}
	$orderBy = " email_template_id asc";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 20;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsClassTable->countItem($cond);
	
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
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy); //print_r($cond." order by ".$orderBy.$limit);die();
	$assign_list["allItem"] = $allItem;	
	#
	$allTrash =  $clsClassTable->getAll("is_trash=1 and ".$cond2);
	$assign_list["number_trash"] = $allTrash[0][$pkeyTable]!=''?count($allTrash):0;	
	#
	$allUnTrash =  $clsClassTable->getAll("is_trash=0 and ".$cond2);
	$assign_list["number_item"] = $allUnTrash[0][$pkeyTable]!=''?count($allUnTrash):0;	
	#
	$allAll =  $clsClassTable->getAll($cond2);
	$assign_list["number_all"] = $allAll[0][$pkeyTable]!=''?count($allAll):0;	
}
function default_edit(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page,$oneSetting;
	global $core, $clsModule, $clsButtonNav,$dbconn;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "EmailTemplate";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$string = isset($_GET[$pkeyTable])? $_GET[$pkeyTable] : "";
	$pvalTable = !empty($string) ? $core->decryptID($string) : 0;
	if(!empty($string) && $pvalTable==0){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	$assign_list['pvalTable'] = $pvalTable;
	$assign_list['pkeyTable'] = $pkeyTable;
	$oneItem = $clsClassTable->getOne($pvalTable);
	$assign_list["oneItem"] = $oneItem;	
	
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){		
		if($pvalTable > 0){
			$set = ""; $firstAdd = 0;
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$set .= $tmp[1]."='".addslashes($val)."'";
						$firstAdd = 1;
					}else{
						$set .= ",".$tmp[1]."='".addslashes($val)."'";
					}
				}
			}
			// Time update.
			$set .= ",upd_date='".time()."'";
			$set .= ",user_id_update='{$user_id}'";
			//print_r($set); die();
			if($clsClassTable->updateOne($pvalTable,$set)){
				header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$string.'&message=UpdateSuccess');
				exit();
			}else{
				header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$string.'&message=updateFailed');
				exit();				
			}
		}else{
			$value = ""; $firstAdd = 0; $field = "";
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$field .= $tmp[1];
						$value .= "'".addslashes($val)."'";
						$firstAdd = 1;
					}else{
						$field .= ','.$tmp[1];
						$value .= ",'".addslashes($val)."'";
					}
				}
			}
			#
			$pvalTable = $clsClassTable->getMaxId();
			$field .= ",{$pkeyTable},user_id,reg_date";
			$value .= ",'{$pvalTable}','$user_id','".time()."'";
			//print_r($field.$value); die();
			if($clsClassTable->insertOne($field,$value)){
				header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$core->encryptID($pvalTable).'&message=InsertSuccess');		
				exit();
			}else{
				header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$core->encryptID($pvalTable).'&message=InsertFailed');	
				exit();
			}
		}
	}
}
function default_delete(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "EmailTemplate";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	if($pvalTable == 0) 
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
	
	if($clsClassTable->deleteOne($pvalTable)){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=DeleteSuccess');
	}
}
function default_open_email_notifier(){
	global $smarty,$_CONFIG,$core,$_SITE_ROOT,$mod,$act;
	global $core, $clsModule, $clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	
	$clsUser = new User();
	$lstUser = $clsUser->getAll("is_trash=0 and is_active='1'", "{$clsUser->pkey}");
	$smarty->assign('clsUser', $clsUser);
	$smarty->assign('lstUser', $lstUser);
	
	$holderG = Input::post('holderG');
	$smarty->assign('holderG', $holderG);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('template', '_form');
	$html = $core->build('email.tpl');
	echo $html; die();
}
function default_add_email_notifier(){
	global $smarty,$_CONFIG,$core,$_SITE_ROOT,$mod,$act,$clsConfiguration;
	global $core, $clsModule, $clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	$clsUser = new User();
	
	$holderG = Input::post('holderG', 'order');
	$field = 'email_'.$holderG;
	$fieldValue = $clsConfiguration->getValue($field);
	$fieldValue = !empty($fieldValue) 
		? @json_decode($fieldValue, true) : array();
	
	$msg = '_error';
	if(isset($_POST['submit']) && $_POST['submit']=='Update'){
		$msg = '_success';
		$email_type = Input::post('email_type', 'email');
		if($email_type=='email'){
			$fieldValue[$clsISO->getUniqid()] = array(
				'status' => 1,
				'email_type' => $email_type,
				'email_address' => Input::post('email_address')
			);
		} else {
			$email_name = $clsUser->getFullName($email_type);
			$email_address = $clsUser->getEmail($email_type);
			$fieldValue[$clsISO->getUniqid()] = array(
				'status' => 1,
				'email_type' => $email_type,
				'email_name' => $email_name,
				'email_address' => $email_address
			);
		}
		$clsConfiguration->updateValue($field, @json_encode($fieldValue));
	}
	// Return
	echo $msg; die();
}
function default_status_email_notifier(){
	global $smarty,$_CONFIG,$core,$_SITE_ROOT,$mod,$act,$clsConfiguration;
	global $core, $clsModule, $clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	
	$holderG = Input::post('holderG', 'order');
	$email_id = Input::post('email_id', "");
	$status = (int) Input::post('status', 1);
	
	$field_name = 'email_'.$holderG;
	$field_value = $clsConfiguration->getValue($field_name);
	$field_value = !empty($field_value) 
		? @json_decode($field_value, true) : array();
	
	$msg= '_error';
	if(!empty($field_value) && array_key_exists($email_id, $field_value)){
		$msg = '_success';
		$field_value[$email_id]['status'] = $status;
		$clsConfiguration->updateValue($field_name, @json_encode($field_value));
	}
	echo $html; die();
}
function default_stop_email_notifier(){
	global $smarty,$_CONFIG,$core,$_SITE_ROOT,$mod,$act,$clsConfiguration;
	global $core, $clsModule, $clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	
	$holderG = Input::post('holderG', 'order');
	$email_id = Input::post('email_id');
	
	$field_name = 'email_'.$holderG;
	$field_value = $clsConfiguration->getValue($field_name);
	$field_value = !empty($field_value) 
		? @json_decode($field_value, true) : array();
	
	$msg= '_error';
	if(!empty($field_value) && array_key_exists($email_id, $field_value)){
		$msg = '_success';
		unset($field_value[$email_id]);
		$clsConfiguration->updateValue($field_name, @json_encode($field_value));
	}
	echo $html; die();
}
function default_load_list_email_notifier(){
	global $smarty,$_CONFIG,$core,$_SITE_ROOT,$mod,$act,$clsConfiguration;
	global $core, $clsModule, $clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	
	$holderG = Input::post('holderG', 'order');
	$field_name = 'email_'.$holderG;
	$field_value = $clsConfiguration->getValue($field_name);
	$lstEmail = !empty($field_value) 
		? @json_decode($field_value, true) : array();
	$smarty->assign('lstEmail', $lstEmail);
	$smarty->assign('holderG', $holderG);
	
	$clsUser = new User();
	$smarty->assign('clsUser', $clsUser);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$smarty->assign('template', '_list');
	$html = $core->build('email.tpl');
	echo $html; die();
}
?>