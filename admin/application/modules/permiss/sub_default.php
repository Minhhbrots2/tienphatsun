<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||

|| #################################################################### ||

\*======================================================================*/

function default_default(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;

	$clsPermiss = new Permiss();

	

	$profile_type = Input::get('profile_type', 'user.fh');

	$smarty->assign('profile_type', $profile_type);

	// $clsPermiss->setDebug();

	$list_groups = $clsPermiss->getAll("`is_trash`=0 and `profile_type`='{$profile_type}' and `parent_id`=0 order by `order_no` ASC");

	if(!empty($list_groups)){

		foreach($list_groups as $key => $val){

			$parent_id = $val[$clsPermiss->pkey];

			$list_permiss = $clsPermiss->getAll("`is_trash`=0 and `parent_id`='{$parent_id}' 

			and `profile_type`='{$profile_type}' order by `order_no` ASC");

			$list_groups[$key]['list_permiss'] = $list_permiss;

		}

	}

	$smarty->assign('list_groups', $list_groups);

	// $clsISO->print_pre($list_groups); die();

}

function default_open_permiss(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;

	$clsPermiss = new Permiss();

	#

	$tp = Input::post('tp', "group");

	$parent_id = Input::post('parent_id', 0);

	$permiss_id = Input::post('permiss_id', 0);

	$profile_type = Input::post('profile_type', "user.fh");

	$smarty->assign('tp', $tp);

	$smarty->assign('parent_id', $parent_id);

	$smarty->assign('permiss_id', $permiss_id);

	$smarty->assign('profile_type', $profile_type);

	###

	$action = "_add";

	$onePermiss = array();

	if($permiss_id > 0){

		$action = "_edit";

		$onePermiss = $clsPermiss->getOne($permiss_id);

	}

	$smarty->assign('action', $action);

	$smarty->assign('onePermiss', $onePermiss);

	// Return

	$html = $core->build('_ajax.permiss.tpl');

	echo $html; die();

}

function default_pop_save_permiss(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;

	$clsPermiss = new Permiss();

	#

	$msg = "_error";

	$parent_id = (int) Input::post('parent_id', 0);

	$permiss_id = (int) Input::post('permiss_id', 0);

	$profile_type = Input::post('profile_type',"user.fh");

	if($permiss_id > 0){

		if($clsPermiss->updateOne($permiss_id, array(

			'code' => Input::post('code'),

			'title' => Input::post('title'),

			'description' => Input::post('description'),

		))){

			$msg = "_success";

		}

	} else {

		$permiss_id = $clsPermiss->getMaxId();

		//$clsPermiss->setDebug(true);

		if($clsPermiss->insert(array(

			$clsPermiss->pkey => $permiss_id,

			'profile_type' => $profile_type,

			'parent_id' => $parent_id,

			'code' => Input::post('code'),

			'title' => Input::post('title'),

			'description' => Input::post('description'),

			'order_no' => $clsPermiss->getMaxOrderNo()

		))){

			$msg = "_success";

		}

	}

	// Return

	echo $msg; die();

}
function default_delete_permiss(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;

	$clsPermiss = new Permiss();

	$msg = "_error";

	$permiss_id = (int) Input::post('permiss_id', 0);

	$tp = Input::post('tp', 'permiss');

	if($permiss_id > 0){

		if($clsPermiss->updateOne($permiss_id, array('is_trash' => 1))){

			$msg = "_success";

		}

		// Xoa nhom -> xoa mem ca quyen con

		if($tp == 'group'){

			$dbconn->Execute("UPDATE `".$clsPermiss->tbl."` SET `is_trash`=1 WHERE `parent_id`='".$permiss_id."'");

		}

	}

	echo $msg; die();

}

function default_toggle_permiss(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;

	$clsPermiss = new Permiss();

	$msg = "_error";

	$permiss_id = (int) Input::post('permiss_id', 0);

	$tp = Input::post('tp', 'permiss');

	$is_active = ((int) Input::post('is_active', 1) == 1) ? 1 : 0;

	if($permiss_id > 0){

		if($clsPermiss->updateOne($permiss_id, array('is_active' => $is_active))){

			$msg = "_success";

		}

		// An/hien nhom -> cascade quyen con

		if($tp == 'group'){

			$dbconn->Execute("UPDATE `".$clsPermiss->tbl."` SET `is_active`=".$is_active." WHERE `parent_id`='".$permiss_id."'");

		}

	}

	echo $msg; die();

}

?>