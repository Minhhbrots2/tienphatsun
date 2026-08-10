<?php 
function user_open_permiss_stock(){
	global $core,$smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsUser = new User();
	$clsProfile = new Profile();
	$smarty->assign('clsUser', $clsUser);
	$clsProfile = new Profile();
	$smarty->assign('clsProfile', $clsProfile);
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	
	$user_id = (int) Input::post("id", 0);
	$smarty->assign('user_id', $user_id);
	
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = "";
	if($user_id > 0) {
		$field = "{$clsProfile->pkey},full_name,email,phone";
		$list_profile = $clsProfile->getAll("is_trash=0 and status_id <> '"._STATUS_STAFF_OFF_ID."'", $field);
		$oneUser = $clsUser->getOne($user_id,"more_information,first_name, last_name");
		$more_information = $clsISO->to_array_json($oneUser['more_information']);
		$block_permiss = !empty ($more_information['block_permiss'] ) ? $more_information['block_permiss'] : array();
		$lstProject = $clsProject->getAll("is_trash='0'",$clsProject->pkey.',title');
		$lstBlock = $clsProperty->getAll("`property_type`='_BLOCK'",$clsProperty->pkey.',title,for_id');
		// $clsISO->print_pre($lstBlock);die;
		$arr_project = [];
		foreach ($lstProject as $k => $project) {
			$arr_block = [];
			foreach ($lstBlock as $k_block => $v_block) {
				if($v_block['for_id'] == $project[$clsProject->pkey]) {
					$arr_block[] = [
						"property_id"	=>	$v_block['property_id'],
						"title"			=>	$v_block['title']
					];
				}
			}				
			$arr_project[$project[$clsProject->pkey]] = [
				"title"	=>	$project['title'],
				"arr_block"	=>	$arr_block
			];			
		}
		$smarty->assign('oneUser', $oneUser);
		$smarty->assign('block_permiss', $block_permiss);
		$smarty->assign('arr_project', $arr_project);
		$smarty->assign('list_profile', $list_profile);
		$smarty->assign('more_information', $more_information);
		$html = $core->build('user'.DS.'_ajax.open_permiss_stock.tpl');
	}
	
	echo $html; die();
}
function user_save_permiss_stock(){
	global $core,$smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsUser = new User();
	$clsProfile = new Profile();
	$smarty->assign('clsUser', $clsUser);
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	
	$user_id = (int) Input::post("user_id", 0);
	$staff_permiss_id = (int) Input::post("staff_permiss_id", 0);
	$block_permiss = Input::post("block_permiss", array());
	
	$response = ["result"	=>	false, "msg"		=>	"Cập nhật thành công"];
	if($user_id > 0) {
		$oneUser = $clsUser->getOne($user_id,"more_information,first_name, last_name");
		$more_information = $clsISO->to_array_json($oneUser['more_information']);
		$more_information['block_permiss'] = $block_permiss;
		$more_information['staff_permiss_id'] = $staff_permiss_id;
		
		if($clsUser->updateOne($user_id, ["more_information" => json_encode($more_information)])) {
			$response = [
				"result"	=>	true,
				"msg"		=>	"Cập nhật thành công"
			];
			if(!empty($staff_permiss_id)) {
				$oneProfile = $clsProfile->getOne($staff_permiss_id);
				$moreProfile = $clsISO->to_array_json($oneProfile["more_information"]);
				$moreProfile["block_permiss"] = $block_permiss;
				$clsProfile->updateOne($staff_permiss_id,["more_information" => json_encode($moreProfile)]);
			}			
		}
	}
	
	echo json_encode($response); die();
}
?>