<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function lucky_wheel_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsLuckyWheel = new LuckyWheel();
	$smarty->assign('clsProperty', $clsProperty);
	
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý vòng quay may mắn - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function lucky_wheel_load_lucky_wheel(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	$clsLuckyWheelSpin = new LuckyWheelSpin();
	$cond = "`is_trash`=0";
	#- Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	$total_record = $clsLuckyWheel->countItem($cond);
	$total_page = ceil($total_record / $per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#
	$list_lucky_wheels = $clsLuckyWheel->getAll($cond. " ORDER BY reg_date DESC".$limitCond);
	if(!empty($list_lucky_wheels)){
		foreach($list_lucky_wheels as $key => $val){
			$total_prizes = $clsLuckyWheelPrize->countItem("`wheel_id`='".$val[$clsLuckyWheel->pkey]."'");
			$total_spins = $clsLuckyWheelSpin->countItem("`wheel_id`='".$val[$clsLuckyWheel->pkey]."'");
			$list_lucky_wheels[$key]['total_prizes'] = $total_prizes;
			$list_lucky_wheels[$key]['total_spins'] = $total_spins;
		}
	}
	$smarty->assign('clsLuckyWheel', $clsLuckyWheel);
	$smarty->assign('list_lucky_wheels', $list_lucky_wheels);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('lucky_wheel'.DS.'_ajax.list.tpl');
	Response::echoResponse(200, array(
		'html' => $html,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'per_page' => $per_page,
	)); die();
}
function lucky_wheel_open(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsLuckyWheel = new LuckyWheel();
	#
	$uid = $clsISO->getUniqid();
	$wheel_id = Input::post('wheel_id', 0);
	##
	$arr_type = array(
		'lucky_wheel' => 'Vòng quay may mắn',
		'gift_box' => 'Hộp quà bí mật'
	);
	$arr_targets = array(
		'random_event' => 'Sự kiện ngẫu nhiên',
		'transaction_bonus' => 'Thưởng giao dịch',
	);
	#
	$action = "_add";
	$oneLuckyWheel = array(
		'program_type' => 'lucky_wheel',
		'reward_type' => 'random_event'
	);
	if($wheel_id > 0){
		$action = '_edit';
		$oneLuckyWheel = $clsLuckyWheel->getOne($wheel_id);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('wheel_id', $wheel_id);
	$smarty->assign('oneLuckyWheel', $oneLuckyWheel);
	$smarty->assign('arr_type', $arr_type);
	$smarty->assign('arr_targets', $arr_targets);
	// Return
	$html = $core->build('lucky_wheel'.DS.'_ajax.open.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function lucky_wheel_save(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsLuckyWheel = new LuckyWheel();
	// $dbconn->debug = true;
	$msg = "_error";
	$wheel_id = (int) Input::post('wheel_id', 0);
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_date = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	$program_type = Input::post('program_type', 'lucky_wheel');
	$reward_type = Input::post('reward_type', 'random_event');
	$max_spin_per_user = (int) Input::post('max_spin_per_user', 0);
	#
	if($wheel_id > 0){
		if($clsLuckyWheel->updateOne($wheel_id, array(
			'title' => Input::post('title'),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'description' => Input::post('description'),
			'program_type' => $program_type,
			'reward_type' => $reward_type,
			'max_spin_per_user' => $max_spin_per_user,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		$wheel_id = $clsLuckyWheel->getMaxId();
		if($clsLuckyWheel->insert(array(
			$clsLuckyWheel->pkey => $wheel_id,
			'title' => Input::post('title'),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'description' => Input::post('description'),
			'program_type' => $program_type,
			'reward_type' => $reward_type,
			'max_spin_per_user' => $max_spin_per_user,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	Response::echoResponse(200, array(
		'msg' => $msg
	)); die();
}
function lucky_wheel_view_wheel_prizes(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsLuckyWheel = new LuckyWheel();
	#
	$list_preloaders = array();
	$uid = $clsISO->getUniqid();
	$wheel_id = Input::post('wheel_id', 0);
	$oneLuckyWheel = $clsLuckyWheel->getOne($wheel_id);
	
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('wheel_id', $wheel_id);
	$smarty->assign('oneLuckyWheel', $oneLuckyWheel);
	$assign_list["list_preloaders"] = $list_preloaders;
	// Return
	$smarty->assign('template_type', '_manager');
	$html = $core->build('lucky_wheel'.DS.'_ajax.wheel_prizes.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function lucky_wheel_load_lucky_wheel_prizes(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	
	$wheel_id = Input::post('wheel_id', 0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	
	$cond = "`wheel_id`='{$wheel_id}'";
	#- Pagination
	$total_record = $clsLuckyWheelPrize->countItem($cond);
	$total_page = ceil($total_record / $per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	// $dbconn->debug = true;
	$total_quantity = $total_probability = 0;
	$list_lucky_wheel_prizes = $clsLuckyWheelPrize->getAll($cond. " ORDER BY `order_no` DESC".$limitCond);
	if(!empty($list_lucky_wheel_prizes)){
		foreach($list_lucky_wheel_prizes as $key => $val){
			$total_quantity += $val['quantity'];
			$total_probability += $val['probability'];
		}
	}
	// $clsISO->print_pre($list_lucky_wheel_prizes); die();
	$smarty->assign('wheel_id', $wheel_id);
	$smarty->assign('list_lucky_wheel_prizes', $list_lucky_wheel_prizes);
	$smarty->assign('total_quantity', $total_quantity);
	$smarty->assign('total_probability', $total_probability);
	// Return
	$smarty->assign('template_type', '_prize');
	$html = $core->build('lucky_wheel'.DS.'_ajax.list.tpl');
	Response::echoResponse(200, array(
		'html' => $html,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'per_page' => $per_page,
	)); die();
}
function lucky_wheel_open_wheel_prizes(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	#
	$uid = $clsISO->getUniqid();
	$wheel_id = Input::post('wheel_id', 0);
	$prize_id = Input::post('prize_id', 0);
	$arr_type = array(
		'gift' => 'Giải thưởng',
		'spin' => 'Lượt quay'
	);
	#
	$action = "_add";
	$onePrize = array(
		'prize_type' => 'gift'
	);
	if($prize_id > 0){
		$action = "_edit";
		$onePrize = $clsLuckyWheelPrize->getOne($prize_id);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('wheel_id', $wheel_id);
	$smarty->assign('prize_id', $prize_id);
	$smarty->assign('onePrize', $onePrize);
	$smarty->assign('arr_type', $arr_type);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('lucky_wheel'.DS.'_ajax.wheel_prizes.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function lucky_wheel_upload_image(){
	global $core,$_frontIsLoggedin_user_id,$clsISO,$profile_id;
	$image = '';
	if(is_uploaded_file($_FILES['image']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		$image = $clsUploadFile->uploadItem($_FILES["image"],"/lucky_wheel",EXTENSION_FILE_UPLOAD);
		// $clsISO->print_pre($image); die();
		if(!empty($image) && file_exists(ROOTPATH . $image)){
			
		}
	}
	// Return
	echo $image; die();
}
function lucky_wheel_save_prize(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	// $dbconn->debug = true;
	$msg = "_error";
	$wheel_id = (int) Input::post('wheel_id', 0);
	$prize_id = (int) Input::post('prize_id', 0);
	$quantity = (int) Input::post('quantity', 0);
	$probability = (float) Input::post('probability', 0);
	#
	if($prize_id > 0){
		if($clsLuckyWheelPrize->updateOne($prize_id, array(
			'bgcolor' => Input::post('bgcolor'),
			'prize_name' => Input::post('prize_name'),
			'prize_type' => Input::post('prize_type'),
			'description' => Input::post('description'),
			'image' => Input::post('image'),
			'quantity' => $quantity,
			'remaining' => $quantity,
			'probability' => $probability,
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	} else {
		$prize_id = $clsLuckyWheelPrize->getMaxId();
		if($clsLuckyWheelPrize->insert(array(
			$clsLuckyWheelPrize->pkey => $prize_id,
			'wheel_id' => $wheel_id,
			'bgcolor' => Input::post('bgcolor'),
			'prize_name' => Input::post('prize_name'),
			'prize_type' => Input::post('prize_type'),
			'description' => Input::post('description'),
			'image' => Input::post('image'),
			'quantity' => $quantity,
			'remaining' => $quantity,
			'probability' => $probability,
			'order_no' => $clsLuckyWheelPrize->getMaxOrderNo(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	Response::echoResponse(200, array(
		'msg' => $msg
	)); die();
}
function lucky_wheel_delete_prize(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	// $dbconn->debug = true;
	$msg = "_error";
	$wheel_id = (int) Input::post('wheel_id', 0);
	$prize_id = (int) Input::post('prize_id', 0);
	if($prize_id > 0 && $clsLuckyWheelPrize->deleteOne($prize_id)){
		$msg = "_success";
	}
	// Return
	Response::echoResponse(200, array(
		'msg' => $msg
	)); die();
}
function lucky_wheel_detail(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsProperty = new Property();
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelSpin = new LuckyWheelSpin();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	#- Max lượt quay
	$max_spin_per_user = $core->get_field($oneProfile, "max_spin_per_user", 0);
	$smarty->assign('max_spin_per_user', $max_spin_per_user);
	#- Preloaders
	$list_preloaders = array();
	for($i=0; $i<=30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	#- Get ID
	$string = Input::get('wheel_id');
	$wheel_id = !empty($string) ? (int) $clsISO->base64url_decode($string) : 0;
	if($wheel_id == 0) {
		header('Location : /');
		exit();
	}
	$oneLuckyWheel = $clsLuckyWheel->getOne($wheel_id);	
	$arr_lucky_prizes = array();
	$tmp = $clsLuckyWheelPrize->getAll("`wheel_id`='{$wheel_id}' AND `remaining`>0"); 
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_lucky_prizes[] = array(
				'color' => $val['bgcolor'],
				'prize_id' => $val[$clsLuckyWheelPrize->pkey],
				'label' => $val['prize_name'],
				'image' => PCMS_URL.$val['image'],
				'weight' => ($val['probability'] / 100)
			);
		}
	}
	// $clsISO->print_pre($arr_lucky_prizes); die();
	$scriptJS = '<script>
		var wheel_id = \''.$wheel_id.'\';
			max_spin_per_user = \''.$max_spin_per_user.'\';
			sectors = '.json_encode($arr_lucky_prizes).';';
	$scriptJS.= '</script>';
	$smarty->assign('scriptJS', $scriptJS);
	$smarty->assign('oneLuckyWheel', $oneLuckyWheel);
	$smarty->assign('wheel_id', $wheel_id);
	$smarty->assign('remaining_turn', $remaining_turn);
	/*=============Title & Description Page==================*/
	$title_page = sprintf('Vòng quay may mắn - %s - ', $oneLuckyWheel['title']) . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function lucky_wheel_spinner(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsLuckyWheel = new LuckyWheel();
	$clsLuckyWheelSpin = new LuckyWheelSpin();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	#
	$msg = "_error"; 
	$uid = $clsISO->getUniqid();
	$wheel_id = (int) Input::post('wheel_id', 0);
	$prize_id = (int) Input::post('prize_id', 0);
	$oneLuckyWheel = $clsLuckyWheel->getOne($wheel_id);
	$oneLuckyPrize = $clsLuckyWheelPrize->getOne($prize_id);
	#- Cập nhật số lượng quay còn lại
	$max_spin_per_user = (int) $oneProfile['max_spin_per_user'];
	if($max_spin_per_user > 0 && $onePrize['prize_type'] != 'spin'){
		$max_spin_per_user -= 1;
		$clsProfile->updateOne($profile_id, array(
			'max_spin_per_user' => $max_spin_per_user
		));
	}
	#- Số lượng quà tặng
	$quantity = $oneLuckyPrize["quantity"];
	#- Số lượng quà tặng còn lại
	$remaining = $oneLuckyPrize["quantity"];
	#- Thêm mới lượt quay
	if($clsLuckyWheelSpin->insert(array(
		$clsLuckyWheelSpin->pkey => $clsLuckyWheelSpin->getMaxId(),
		'wheel_id' => $wheel_id,
		'user_id' => $profile_id,
		'prize_id' => $prize_id,
		'reg_date' => time()
	))){
		$msg = "_success";
		$remaining -= 1; // Giảm số lượng quà
		$clsLuckyWheelPrize->updateOne($prize_id, array(
			'remaining' => $remaining
		));
	}
	$smarty->assign("onePrize", $oneLuckyPrize);
	$html = $core->build('lucky_wheel'.DS.'_ajax.success.tpl');
	#
	$arr_lucky_prizes = array();
	$tmp = $clsLuckyWheelPrize->getAll("`wheel_id`='{$wheel_id}' AND `remaining` > 0");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$arr_lucky_prizes[] = array(
				'color' => $val['bgcolor'],
				'prize_id' => $val[$clsLuckyWheelPrize->pkey],
				'label' => $val['prize_name'],
				'image' => PCMS_URL . $val['image'],
				'weight' => ($val['probability'] / 100)
			);
		}
		unset($tmp);
	}
	// Return
	echo json_encode(array(
		"uid"	=>	$uid,
		"msg"	=>	$msg,
		"html"	=>	$html,
		'max_spin_per_user' => $max_spin_per_user,
		"sectors"	=>	$arr_lucky_prizes,
	),JSON_UNESCAPED_UNICODE); die();
}
function lucky_wheel_load_spinner(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	global $oneProfile, $profile_id,$clsConfiguration;
	$clsCache = new Cache();
	$clsProfile = new Profile();
	$clsProperty = new Property();	
	$clsLuckyWheelSpin = new LuckyWheelSpin();
	$clsLuckyWheelPrize = new LuckyWheelPrize();
	#
	$html = "";
	$wheel_id = (int) Input::post("wheel_id",0);
	$field = "{$clsLuckyWheelPrize->pkey},prize_name,description,image,bgcolor";
	$list_wheel_prizes = $clsLuckyWheelPrize->getAll("`wheel_id`='{$wheel_id}' AND `is_active`='1'", $field);
	$arr_wheel_prizes = $arr_wheel_prizes_id = [];
	if(!empty($list_wheel_prizes)){
		foreach ($list_wheel_prizes as $key => $val) {
			$arr_wheel_prizes[$val[$clsLuckyWheelPrize->pkey]] = $val;
			$arr_wheel_prize_id[] = $val[$clsLuckyWheelPrize->pkey];
		}
		unset($list_wheel_prizes);
	}
	$field = "{$clsLuckyWheelSpin->pkey},`prize_id`,`user_id`,`wheel_id`";
	$list_wheel_spins = $clsLuckyWheelSpin->getAll("`prize_id` IN (".implode(',',$arr_wheel_prize_id).") AND `wheel_id`='{$wheel_id}' ORDER BY `reg_date` DESC", $field);
	// $clsISO->print_pre($list_wheel_spins); die();
	if(!empty($list_wheel_spins)){
		$arr_profile_cached = [];
		foreach ($list_wheel_spins as $key => $val) {
			$user_id = (int) $val['user_id'];
			$prize_id = (int) $val['prize_id'];
			if($user_id > 0 && !isset($arr_cache_profile[$user_id])) {
				$p_field = "{$clsProfile->pkey},`full_name`,`avatar`";
				$arr_profile_cached[$user_id] = $clsProfile->getOne($user_id, $p_field);
			}
			$html.= '<div class="d-flex align-items-center justify-content-between py-1/5 px-2 bg-white-100 rounded-2 mb-1">
				<div class="d-flex w-50 flex-fill gap-1 align-items-center">
					<img class="avatar avatar-xs rounded-pill" src="'.$clsProfile->getAvatar($user_id, $arr_profile_cached[$user_id]).'" alt="Avatar" />
					<div class="d-flex text-white flex-column gap-0">
						<h4 class="text-fs-13 mb-0">'.$clsProfile->getFullName($user_id, $arr_profile_cached[$user_id]).'</h4>
					</div>
				</div>
				<div class="d-flex w-50 flex-fill gap-2 text-white align-items-center justify-content-end">
					<img src="'.$arr_wheel_prizes[$prize_id]["image"].'" alt="'.$arr_wheel_prizes[$prize_id]["prize_name"].'" class="w-px-20" style="object-fit:contain">
					<span class="lh-xs">'.$arr_wheel_prizes[$prize_id]["prize_name"].'</span>
				</div>
			</div>';
		}
	} else {
		$html = '<div class="d-flex align-items-center justify-content-center py-1/5 px-2 bg-white-100 rounded-2 mb-1">
			<span class="text-white">Chưa có ai nhận được quà 🎁</span>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
