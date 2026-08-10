<?php
function default_default(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsCategory = new Category();
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	$assign_list["clsCategory"] = $clsCategory;
	/*Get cat_id */
	$cat_id = Input::get('cat_id',0);
	/*Get type of list news*/
	$type_list = Input::get('type_list');
	$assign_list["cat_id"] = $cat_id;
	$assign_list["type_list"] = $type_list;
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$cat_id = (int) Input::post('cat_id',0);
		$keyword = Input::post('keyword');
		
		$link = '';
		if($cat_id > 0) $link .= '&cat_id='.$cat_id;
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
	}
	
	$classTable = "FAQ";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*List all item*/
	$cond = "1='1'";
	#Filter By Keyword
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	if($cat_id > 0){
		$pUrl.='&cat_id='.$cat_id;
		$cond .= " and cat_id = '".$cat_id."'";
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
	#
	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"] = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_edit(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsConfiguration,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsCategory = new Category();
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	$assign_list["clsCategory"] = $clsCategory;
	$cat_id = (int) Input::get('cat_id',0);
	
	$classTable = "FAQ";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$assign_list["clsProfile"] = $clsProfile;
	$field = "{$clsProfile->pkey},first_name,last_name,full_name";
	$list_profiles = $clsProfile->getAll("is_trash=0 and status_id<>'"._STATUS_STAFF_OFF_ID."' 
	and profile_id not in(".implode(',',_PROFILE_NOTIN_ID).") order by code ASC", $field);
	$assign_list["list_profiles"] = $list_profiles;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	if(!empty($string) && $pvalTable==0){
		header('location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');
	}
	$oneItem = array('is_all_staff' => 1);
	$arr_profile_ids = $arr_departments_ids = array();
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		$cat_id = $oneItem['cat_id'];
		$list_profile_id = $oneItem['list_profile_id'];
		$list_department_id = $oneItem['list_department_id'];
		$arr_profile_ids = !empty($list_profile_id) 
			? $clsISO->getArrayByTextSlash($list_profile_id) : array();
		$arr_departments_ids = !empty($list_department_id) 
			? $clsISO->getArrayByTextSlash($list_department_id) : array();
	}
	$assign_list['pvalTable'] = $pvalTable;
	$assign_list["oneItem"] = $oneItem;
	$assign_list["cat_id"] = $cat_id;
	$assign_list['arr_profile_ids'] = $arr_profile_ids;
	$assign_list['arr_departments_ids'] = $arr_departments_ids;
	
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form();
	$clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
	$assign_list["clsForm"] = $clsForm;
	$clsForm->addInputTextArea("full",'content',"",'content',255,25,10,1,"style='width:100%'");
	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		$cat_id = (int) Input::post('iso-cat_id',0);
		$pUrl = '&cat_id='.$cat_id;
		if($pvalTable>0){
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
			#
			$set .= ",upd_date='".time()."'";
			$set .= ",user_id_update='".$user_id."'";
			$set .= ",slug='".$core->replaceSpace(Input::post('iso-title'))."'";
			#--Special Field: start_date
			$start_date = Input::post('start_date');
			$due_date = Input::post('due_date');
			if(!empty($start_date)) $set .= ",start_date='".$clsISO->toTime($start_date)."'";
			if(!empty($due_date)) $set .= ",due_date='".$clsISO->toTime($due_date)."'";
			#--Special Field: image
			$image = Input::post('isoman_url_image');
			if(!empty($image)) $set .= ",image='".$image."'";
			#--Special Field: Permission
			$is_all_staff = (int) Input::post('is_all_staff', 0);
			$set .= ",`is_all_staff`='".$is_all_staff."'";
			if($is_all_staff==0){
				$list_department_id = Input::post('list_department_id');
				$list_profile_id = Input::post('list_profile_id');
				$list_department_id = !empty($list_department_id) 
					? $clsISO->makeSlashListFromArray($list_department_id,'|', false) : "";
				$list_profile_id = !empty($list_profile_id) 
					? $clsISO->makeSlashListFromArray($list_profile_id,'|', false) : "";
				$set.= ",`list_department_id`='".$list_department_id."'";
				$set.= ",`list_profile_id`='".$list_profile_id."'";
			}
			// $clsISO->print_pre($set); die();
			if($clsClassTable->updateOne($pvalTable,$set)) {
				if($_POST['button']=='_EDIT'){
					header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$string.'&message=updateSuccess');
					exit();
				}else{
					header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateSuccess');
					exit();
				}
			} else{
				header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateFailed');
				exit();
			}
		} else{
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
			$faq_id = $clsClassTable->getMaxID();
			$field .= ",user_id,user_id_update,reg_date,upd_date,slug,{$pkeyTable},order_no";
			$value .= ",'".addslashes($user_id)."','".addslashes($user_id)."','".time()."','".time()."'";
			$value .= ",'".$core->replaceSpace($_POST['iso-title'])."','".$faq_id."','".$clsClassTable->getMaxOrderNo()."'";
			#--Special Field: start_date
			$start_date = Input::post('start_date');
			$due_date = Input::post('due_date');
			if(!empty($start_date)){
				$field .= ",start_date";
				$value .= ",'".$clsISO->toTime($start_date)."'";
			}
			if(!empty($due_date)){
				$field .= ",due_date";
				$value .= ",'".$clsISO->toTime($due_date)."'";
			}
			#--Special Field: image
			$image = Input::post('isoman_url_image');
			if(!empty($image)) {
				$field .= ",image";
				$value .= ",'".$image."'";
			}
			#--Special Field: Permission
			$is_all_staff = (int) Input::post('is_all_staff', 0);
			$field .= ",is_all_staff";
			$value .= ",'".$is_all_staff."'";
			if($is_all_staff==1){
				$list_department_id = Input::post('list_department_id');
				$list_profile_id = Input::post('list_profile_id');
				$list_department_id = !empty($list_department_id) 
					? $clsISO->makeSlashListFromArray($list_department_id,'|', false) : "";
				$list_profile_id = !empty($list_profile_id) 
					? $clsISO->makeSlashListFromArray($list_profile_id,'|', false) : "";
				$field .= ",list_department_id,list_profile_id";
				$value .= ",'".$list_department_id."','".$list_profile_id."'";
			}
			###
			if($clsClassTable->insertOne($field,$value)){
				if ($_POST['button'] == '_EDIT') {
					header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$core->encryptID($faq_id).$pUrl.'&message=updateSuccess');
					exit();
				}else {
					header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertSuccess');
					exit();
				}
			} else{
				header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertFailed');
				exit();
			}
		}
	}
}
function default_trash(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "FAQ";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)!=0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	
	if($pvalTable == "")
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');

	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
	}
}
function default_restore(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "FAQ";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)!=0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	
	if($pvalTable == "")
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');

	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
	}
}
function default_delete(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "FAQ";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)!=0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($string = '' && $pvalTable == 0)
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		
	if(isset($_POST['agree']) && $_POST['agree']=='agree'){
		if($clsClassTable->doDelete($pvalTable)){
			header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		}
	}
}
function default_move(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "FAQ";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#
	
	$cat_id = (int) Input::get('cat_id',0);
	$direct = Input::get('direct','up');
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$one = $clsClassTable->getOne($pvalTable);
	$order_no = $one['order_no'];
	if(($string!='' && $pvalTable == 0) || $direct==''){
		header('location: '.PCMS_URL.'/?mod='.$mod);
	}
	
	$pUrl = '';
	$where = 'is_trash=0 ';
	if(intval($cat_id) > 0){
		$pUrl .= '&cat_id='.$cat_id;
		$where.=" and cat_id=".$cat_id;
	}
	if($direct=='movedown'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='moveup'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movebottom'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no < $order_no order by order_no asc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']+1)."'");	
		}
	}
	if($direct=='movetop'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no > $order_no order by order_no asc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']-1)."'");	
		}
	}
	header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=PositionSuccess');
}
function default_setting(){
	global $assign_list,$_CONFIG,$_LANG_ID,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsConfiguration,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	if(isset($_POST['submit'])){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
			if($tmp[0]=='date'){
				$clsConfiguration->updateValue($tmp[1],strtotime($val));
			}
		}
		$extUrl = '';
		if($_POST['submit']=='UpdateConfiguration'){
			$extUrl = '#isotab0';
		}
		if($_POST['submit']=='UpdateConfiguration1'){
			$extUrl = '#isotab1';
		}
		if($_POST['submit']=='UpdateConfiguration2'){
			$extUrl = '#isotab2';
		}
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=UpdateSuccess'.$extUrl);
	}	
}
/*============ SITE FAQS CATEGORY ============*/
function default_cat(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act,$clsConfiguration ;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$_LANG_ID,$clsISO;
	$user_id = $core->_USER['user_id'];
	$assign_list["msg"] = isset($_GET['message'])?$_GET['message']:'';
	#
	$classTable = "Category";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	
	$type = '_FAQs';
	$assign_list["type"] = $type;
}
?>