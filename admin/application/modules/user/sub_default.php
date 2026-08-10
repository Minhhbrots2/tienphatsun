<?php
function default_default(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page,$oneSetting;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	
	$user_group_id = isset($_GET['user_group_id']) && $_GET['user_group_id']!='' ? $_GET['user_group_id'] : 0;
	$assign_list["user_group_id"] = $user_group_id;
	#
	$clsUserGroup = new UserGroup(); $assign_list["clsUserGroup"] = $clsUserGroup;
	$oneUserGroup = $clsUserGroup->getOne($user_group_id);		
	$assign_list["oneUserGroup"] = $oneUserGroup;
	/*Get type of list*/
	$type_list = isset($_GET['type_list']) ? $_GET['type_list'] : '';
	$assign_list["type_list"] = $type_list;
	/**/
	$classTable = "User";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$cond = "1=1 and user_id<>1";
	if($user_group_id!='0'){
		$cond .=" and user_group_id='$user_group_id'";
	}
	$cond2 = $cond;
	
	if($type_list=="Trash"){
		$cond .= " and is_trash=1";
	} else{
		$cond .= " and is_trash=0";
	}
	
	/*List all item*/
	$allItem = $clsClassTable->getAll($cond. " order by $pkeyTable asc");
	$assign_list["allItem"] = $allItem;
	#
	$allTrash =  $clsClassTable->getAll("is_trash=1 and ".$cond2);
	$assign_list["number_trash"] = $allTrash[0][$pkeyTable]!=''?count($allTrash):0;
	#
	$name_page = "Users";
	$assign_list["name_page"] = $name_page;
	
	$title_page = $name_page.' &laquo; ISOCMS';
	$assign_list["title_page"] = $title_page;
	
	$menu_current = 'userpanel';
	$assign_list["menu_current"] = $menu_current;
	
	$current_page = 'usergroup';
	$assign_list["current_page"] = $current_page;
	
	$assign_list["mod"] = $mod;
	$assign_list["act"] = $act;
}
function default_edit(){
	global $assign_list, $dbconn, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page,$oneSetting;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	#
	$clsISO = new ISO();
	$clsUserGroup = new UserGroup(); $assign_list["clsUserGroup"] = $clsUserGroup;
	$listUserGroup = $clsUserGroup->getAll("is_trash=0 order by user_group_id asc");		
	$assign_list["listUserGroup"] = $listUserGroup;
	#
	$classTable = "User";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsUser"] = $clsClassTable;
	#
	$string = isset($_GET[$pkeyTable])? $_GET[$pkeyTable] : "";
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	$assign_list[$pkeyTable] = $pvalTable;
	$oneItem = $clsClassTable->getOne($pvalTable);
	$assign_list["oneItem"] = $oneItem;
	$assign_list["pvalTable"] = $pvalTable;
	#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$user_name = $_POST['user_name'];
		$about_us = Input::post('about');
		$user_group_id = (int) Input::post('user_group_id', 0);
		$full_name = sprintf('%s %s', $first_name, $last_name);
		$assign_list["user_name"] = $user_name;
		$assign_list["full_name"] = $full_name;
		
		$email = $_POST['email'];
		$pass1Post = $_POST['pass1'];
		$pass2Post = $_POST['pass2'];
		$ok2 = 1;		
		if($pass1Post !='' && $pass2Post!='' && $pass1Post!=$pass2Post){
			$ok2 = 0;
			$assign_list["err_password"] = 'invalid';
		}
		if($ok2==0) return;
		if($pvalTable > 0){
			$set = "`first_name`='{$first_name}',`last_name`='{$last_name}',`full_name`='".addslashes($full_name)."',`user_group_id`='{$user_group_id}'";
			$set.= ",`email`='{$email}',`is_super`='".$_POST['is_super']."'";
			$set.= ",`about`='{$about_us}',`is_active`='".$_POST['is_active']."'";
			if($pass1Post!=''){
				$set .= ",user_pass='".$clsClassTable->encrypt($pass1Post)."'";
			}
			if($clsClassTable->updateOne($pvalTable,$set)){
				header('location: '.PCMS_URL.'/index.php?admin&mod='.$mod.'&message=updateSuccess');			
				exit();
			}
		} else {
			$field = "`user_name`,`first_name`,`last_name`,`full_name`,`email`,`user_pass`,`user_group_id`,`is_active`";
			$value = "'".$user_name."'";
			$value .= ",'".$first_name."'";
			$value .= ",'".$last_name."'";
			$value .= ",'".$full_name."'";
			$value .= ",'".$email."'";
			$value .= ",'".$clsClassTable->encrypt($pass1Post)."'"; 
			$value .= ",'".$user_group_id."'";
			$value .= ",'".$_POST['is_active']."'";
			
			$field .= ",is_super";
			$value.= ",'".$_POST['is_super']."'";
			$dbconn->debug = true;
			if($clsClassTable->insertOne($field,$value)){
				header('location: '.PCMS_URL.'/index.php?admin&mod='.$mod.'&user_group_id='.$user_group_id.'&message=insertSuccess');	
				exit();
			}
		}
	}	
	$name_page = 'Users';
	$assign_list["name_page"] = $name_page;
	$title_page = 'Edit Users &laquo; ISOCMS';
	$assign_list["title_page"] = $title_page;
}
function default_trash(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "User";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	#
	$string = isset($_GET[$pkeyTable])? $_GET[$pkeyTable] : "";
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod);
		exit();
	}
	$set = "is_trash='1'";
	if($clsClassTable->updateOne($pvalTable,$set)){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod.'&user_group_id='.$user_group_id.'&message=TrashSuccess');
	}
	$assign_list["mod"] = $mod;
	$assign_list["act"] = $act;
}
function default_restore(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	
	$classTable = "User";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$string = isset($_GET[$pkeyTable])? $_GET[$pkeyTable] : "";
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod);
		exit();
	}
	$set = "is_trash='0'";
	if($clsClassTable->updateOne($pvalTable,$set)){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod.'&user_group_id='.$user_group_id.'&message=RestoreSuccess');
	}
	$assign_list["mod"] = $mod;
	$assign_list["act"] = $act;
}
function default_delete(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	
	$classTable = "User";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$string = isset($_GET[$pkeyTable])? $_GET[$pkeyTable] : "";
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod);
		exit();
	}
	
	if($clsClassTable->deleteOne($pvalTable)){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod.'&user_group_id='.$user_group_id.'&message=DeleteSuccess');
	}
	$assign_list["mod"] = $mod;
	$assign_list["act"] = $act;
}
?>