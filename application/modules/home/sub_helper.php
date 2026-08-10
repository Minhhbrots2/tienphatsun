<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is Ã‚Â©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function helper_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
}
function helper_open_gift_box(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsLuckyWheelSpin = new LuckyWheelSpin();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	##
	$wheel_id = 2;
	$oneLuckyPrize = array();
	$arr_lucky_prizes = $clsLuckyWheelPrize->getAll("`wheel_id`='{$wheel_id}' AND `remaining`>0"); 
	$oneLuckyPrize = $clsLuckyWheelPrize->getOne(7);
	if($clsLuckyWheelSpin->insert(array(
		$clsLuckyWheelSpin->pkey => $clsLuckyWheelSpin->getMaxId(),
		'wheel_id' => $wheel_id,
		'user_id' => $profile_id,
		'prize_id' => $oneLuckyPrize[$clsLuckyWheelPrize->pkey],
		'reg_date' => time()
	))){
		$msg = "_success";
		$remaining = $oneLuckyPrize['remaining'] - 1; // Giảm số lượng quà
		$clsLuckyWheelPrize->updateOne($oneLuckyPrize[$clsLuckyWheelPrize->pkey], array(
			'remaining' => $remaining
		));
	}
	$smarty->assign('oneLuckyPrize', $oneLuckyPrize);
	// Return
	$html = $core->build('helper'.DS.'_ajax.gift_box.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}