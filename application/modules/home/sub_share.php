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
function share_gen_image(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	// Return
	$html = $core->build('share'.DS.'_ajax.share.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function share_do_gen_image(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsShare = new Share();
	##
	$image = "";
	$msg = "_error";
	$imgdata = $_POST['imgdata'];
	if(!empty($imgdata)){
		$image = $clsShare->base64ToJPEG($imgdata, 'VD');
		if(!empty($image) && @file_exists(ABSPATH.$image)){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'image' => $image
	)); die();
}