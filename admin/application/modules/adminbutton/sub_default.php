<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$oneSetting;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	#
	$classTable = "AdminButton";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["pkeyTable"] = $pkeyTable;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$cond = "1=1";
	$_type = Input::get('_type', '_HOME');
	$assign_list["_type"] = $_type;
	
	if($_type=='_HOME'){
		$cond .= " and is_group=1 and _type='{$_type}'";
	} else {
		$cond .= " and _type='{$_type}'";
		if($_type!='_TOP' && $_type!='_SETTING'){
			$cond .= ' and is_group=1';
		}
	}
	//print_r($cond); die();
	/*List all item*/
	$allItem = $clsClassTable->getAll($cond. " order by order_no asc");
	$assign_list["allItem"] = $allItem;
}
function default_edit(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$oneSetting,$core, $clsModule, $_loged_id;
	global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
	#
	$classTable = "AdminButton";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0; 
	if(!empty($string) && $pvalTable==0){
		header('location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');
		exit();
	}
	$oneItem = $clsClassTable->getOne($pvalTable);
	$assign_list['pvalTable'] = $pvalTable;
	$assign_list["oneItem"] = $oneItem;
	
	$permiss_access = '';
	$_type = Input::request('_type', '_HOME');
	if($pvalTable > 0){
		$_type = $oneItem['_type'];
		$permiss_access = $oneItem['permiss_access'];
	}
	$assign_list["_type"] = $_type;
	$assign_list["permiss_access"] = $permiss_access;
	$listParent = $clsClassTable->getAll("is_trash=0 and _type='{$_type}' and is_group=1 order by order_no asc");
	$assign_list["listParent"] = $listParent;
	#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){		
		$title = $_POST['title'];$assign_list["title"] = $title;
		$desc = $_POST['desc'];$assign_list["desc"] = $desc;
		$order_no = $_POST['order_no'];$assign_list["order_no"] = $order_no;
		$url_page = str_replace(PCMS_URL.'/?','',$_POST['url_page']);
		$url_page = str_replace(PCMS_URL.'/index.php?','',$url_page);
		$assign_list["url_page"] = $url_page;
		if($pvalTable>0){
			$set = "title_page='".addslashes($title)."'";
			$set .= ",desc_page='".addslashes($desc)."'";
			$set .= ",order_no='".addslashes($order_no)."'";
			$set .= ",url_page='".addslashes($url_page)."'";
			$set .= ",image='".addslashes($_POST['image'])."'";
			$set .= ",mod_page='".addslashes($_POST['mod_page'])."'";
			$set .= ",act_page='".addslashes($_POST['act_page'])."'";
			$set .= ",_type='".addslashes($_POST['_type'])."'";
			$set .= ",is_group='".intval($_POST['is_group'])."'";
			if($_POST['is_group']==0){
				$set .= ",parent_id='".intval($_POST['parent_id'])."'";
			}
			$set .= ",class_page='".$_POST['class_page']."'";
			$set .= ",class_iconpage='".$_POST['class_iconpage']."'";
			$set .= ",dev_access='".Input::post('dev_access', 0)."'";
			$permiss_access = $clsISO->makeSlashListFromArray($_POST['permiss']);
			$set .= ",permiss_access='".$permiss_access."'";
			$set .= ",CONFIGURATION_KEY='".addslashes($_POST['configuration_key'])."'";
			#
			$clsClassTable->updateOne($pvalTable,$set);
			header('location: '.PCMS_URL.'/?mod='.$mod.'&message=updateSuccess&_type='.$_POST['_type']);
		}else{
			$f = "title_page,desc_page,is_active,order_no,url_page,image,mod_page,act_page,_type,is_group,parent_id,class_page,class_iconpage,dev_access";
			$v = "'".addslashes($title)."','".addslashes($desc)."','1','".addslashes($order_no)."','".addslashes($url_page)."','".addslashes($_POST['image'])."'
				,'".addslashes($_POST['mod_page'])."'
				,'".addslashes($_POST['act_page'])."'
				,'".addslashes($_POST['_type'])."'
				,'".intval($_POST['is_group'])."'
				,'".intval($_POST['parent_id'])."'
				,'".addslashes($_POST['class_page'])."'
				,'".addslashes($_POST['class_iconpage'])."'
				,'".addslashes(Input::post('dev_access', 0))."'
				";
			#
			$permiss_access = $clsISO->makeSlashListFromArray($_POST['permiss']);
			$f.= ",permiss_access";
			$v.= ",'$permiss_access'";
			$f.= ",CONFIGURATION_KEY";
			$v.= ",'".addslashes($_POST['configuration_key'])."'";
			//$a = $clsClassTable->insertOne($f, $v, true);
			//print_r($a); die();
			if($clsClassTable->insertOne($f, $v, false)){
				header('location: '.PCMS_URL.'/?mod='.$mod.'&message=insertSuccess&_type='.$_POST['_type']);
			}else{
				header('location: '.PCMS_URL.'/?mod='.$mod.'&message=insertFailed&_type='.$_POST['_type']);
			}
		}
	}
}
function default_delete(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act,$core, $clsModule;
	$user_id = $core->_USER['user_id'];
	
	$classTable = "AdminButton";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string)); 

	if($pvalTable == 0||$string=='') 
		header('location: '.PCMS_URL.'/?admin&mod='.$mod);
	
	if($clsClassTable->doDelete($pvalTable)){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod.'&message=DeleteSuccess');
	}
}
?>