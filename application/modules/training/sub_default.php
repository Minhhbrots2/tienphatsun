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

function default_default(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;

	$clsProfile = new Profile(); 

	$clsProperty = new Property();

	$clsGroupProfile = new GroupProfile(); 

	$clsTraining = new Training(); 

	$assign_list['clsTraining'] = $clsTraining;

	if(!empty($_POST['submit']) && $_POST['submit'] == "search") {

		$keyword = Input::post("keyword","");

		$cat_ids = Input::post("cat_ids",array());

		$string = "";

		if(!empty($keyword)) {

			$string = "?k=".str_replace(" ","+",$keyword);

		}

		if(!empty($cat_ids)) {

			$string .= (($string != "") ? "&" : "?") . "&c=".implode(",",$cat_ids);

		}

		header("Location: ".$clsISO->getLink('training').$string);exit();

	}

	$scriptJs = "";

	$keyword = Input::get('k', "");	

	$cat_ids = Input::get("c","");

	$assign_list["key_search"] = $keyword;

	$assign_list["cat_ids"] = $cat_ids;

	$list_department_id = $oneProfile['list_department_id'];

	$arr_department_ids = !empty($list_department_id) 

		? $clsISO->getArrayByTextSlash($list_department_id) : array();

	$list_group = $clsGroupProfile->getAll("`is_trash`='0' AND `is_online`='1' AND `list_profile_id` LIKE '%|".$profile_id."|%'",$clsGroupProfile->pkey);

	###

	$str_url = "";

	if(in_array($profile_id, _PROFILE_NOT_ACESS_LOGS_ID)){

		$cond = "1=1";

	} else {

		$cond = "`is_trash`='0' AND `is_online`='1'";

	}

	$cond .= " AND `cat_id` <> '"._CAT_INSPIRE_ID."'";

	if(trim($keyword) != "") {

		$cond .= " AND (`title` LIKE '%{$keyword}%' OR `slug` LIKE '%{$keyword}%')";

		$str_url .= "?keyword=".str_replace(" ","+",$keyword);

	}

	if(!empty($cat_ids)) {

		$arr_cat = explode(",",$cat_ids);

		$cond .= " AND (`cat_id` IN ({$cat_ids}) ";

		foreach ($arr_cat as $cat_id) {

			$cond .= " OR `list_cat_id` LIKE '%|".$cat_id."|%'";

		}

		$cond .= ")";

		$str_url .= (($str_url != "") ? "&" : "?") . "&c=".implode(",",$cat_ids);

	}

	$cond .= " AND (`is_all_staff`='1' ";

	if(!empty($arr_department_ids) || !empty($list_group) ) {

		$first = 0;

		$cond .= " OR  (`is_all_staff`='0' AND ( `list_profile_id` LIKE '%|{$profile_id}|%'";

		if(!empty($arr_department_ids)) {

			foreach ($arr_department_ids as $department_id) {

				$cond .= " OR `list_department_id` LIKE '%|{$department_id}|%'";

			}

			

		}

		if(!empty($list_group)) {

			foreach ($list_group as $k => $_oGroup) {

				$cond .= " OR `list_group_profile_id` LIKE '%|{$_oGroup[$clsGroupProfile->pkey]}|%'";	

			}	

		}

		$cond .="))";

	}

	$cond .= " )";

	

	$current_page = Input::get('page',1);

	$per_page  = 20;

	$total_record = $clsTraining->countItem($cond);

	$total_page = @ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " LIMIT {$offset},{$per_page}";

	$order_by = " ORDER BY upd_date DESC ";

	$lst_cat = $clsProperty->getArraySearchByKey("_TRAINING_CAT");

	$config = array(

		'total'	=> $total_record,

		'current_page'	=> $current_page,

		'number_per_page'	=> $per_page,

		'link'	=> str_replace(".html","/",$clsISO->getLink("training"))

	);

	$clsPagination = new Pagination();

	$clsPagination->initianize($config);

	$html_pager = $clsPagination->create_links_page(1,$str_url);

	$assign_list["html_pager"] = $html_pager;

	

	$lstTraining = $clsTraining->getAll($cond.$order_by.$limitCond);

	foreach ($lstTraining as $key => $val) {

		$total_view = 0;

		$arr_profile = [];

		$lesson = $clsISO->to_array_json($val['lesson']);

		$more_information = $val['more_information'];

		$more_information = $clsISO->to_array_json($more_information);

		$history_learning = !empty($more_information['history_learning']) ? $more_information['history_learning'] : array();

		foreach ($history_learning as $k_his => $v_his) {

			$total_view += count($v_his);

			foreach ($v_his as $history) {

				if(!empty($history['profile_id']) && !$clsISO->checkItemInArray($history['profile_id'],$arr_profile)){

					$arr_profile[] = $history['profile_id'];

				}

			}			

		}

		###

		//$rnd = mt_rand(300, 500);

		$rnd_learning = 0;

		//$more_information['rnd'] = $rnd;

		//$more_information['rnd_learning'] = $rnd_learning;

		/*$clsTraining->updateOne($val[$clsTraining->pkey], array(

			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

		));*/ 

		$rnd = (int) $core->get_field($more_information, 'rnd', 0);

//		$rnd_learning = (int) $core->get_field($more_information, 'rnd_learning', 0);

		$lstTraining[$key]["total_profile_learning"] = $rnd_learning + count($arr_profile);

		$lstTraining[$key]["total_view"] = $rnd_learning + $total_view;

		$lstTraining[$key]["total_lesson"] = count($lesson);

		$lstTraining[$key]["cat_name"] = !empty($lst_cat[$val['cat_id']]) ? $lst_cat[$val['cat_id']]["title"] : "";

		###

		$cond_profile = "";

		if($val['is_all_staff'] == 0) {

			#phòng ban

			$arr_department_ids = !empty($val['list_department_id']) ? $clsISO->getArrayByTextSlash($val['list_department_id']) : array();

			foreach($arr_department_ids as $department){

				$cond_profile .= (($cond_profile != "")?" OR ":"")." department_id='{$department}' OR list_department_id like '%|{$department}|%' " ;

			}

			#nhóm nhân viên

			$arr_profile_group = [];

			$arr_group_profile_id = !empty($val['list_group_profile_id']) ? $clsISO->getArrayByTextSlash($val['list_group_profile_id']) : array();

			if(!empty($arr_group_profile_id)) {

				$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 and group_profile_id IN (".implode(',',$arr_group_profile_id).")");	

				foreach ($lstGroupProfile as $kgp => $vgp) {

					$arr_profile_group = array_merge($arr_profile_group,$clsISO->getArrayByTextSlash($vgp['list_profile_id']));

				}

				$arr_profile_group = array_unique($arr_profile_group);

				if(!empty($arr_profile_group)){					

					$cond_profile .= (($cond_profile != "")?" OR ":"")." profile_id IN (".implode(',',$arr_profile_group).") ";

				}

			}	

			#nhân viên

			$arr_profile_id = !empty($val['list_profile_id']) ? $clsISO->getArrayByTextSlash($val['list_profile_id']) : array();

			foreach($arr_profile_id as $profile){

				$cond_profile .= " OR profile_id='{$profile}' ";

			}

			$cond_profile  = ($cond_profile != "")?" and (".$cond_profile.")":"";

		}

		$total_profile = $clsProfile->countItem("`status_id`<>'"._STATUS_STAFF_OFF_ID."' ".$cond_profile);

		$lstTraining[$key]['total_profile'] = $total_profile;

	}

	$assign_list["lstTraining"] = $lstTraining;

	

	$view_training = 'grid';

	if(vnSessionExist('view_training')){

		$view_training = vnSessionGetVar('view_training');

	}

	//	echo $view_training;die;

	$assign_list['view_training'] = $view_training;

	$assign_list['total_record'] = $total_record;

    /*=============Title & Description Page==================*/

	$title_page = 'Khóa học đào tạo - ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $clsConfiguration->getValue('meta_description');

	$assign_list["description_page"] = $description_page;

	$keyword_page = $clsConfiguration->getValue('meta_keyword');

	$assign_list["keyword_page"] = $keyword_page;

}

function default_load_list_participants(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;

	$clsTraining = new Training(); $assign_list['clsTraining'] = $clsTraining;

	$clsGroupProfile = new GroupProfile(); 

	$clsProfile = new Profile(); 

	$clsProperty = new Property(); 

	

	$scriptJs = "";

	$training_id = (int)Input::get('training_id', 0);

	$html = "<h3 class='text-main fs-18 p-2 mb-1'>Thành phần tham gia</h3>";

	if($training_id > 0) {

		$oneItem = $clsTraining->getOne($training_id);

		if(!empty($oneItem)) {

			if($oneItem['is_all_staff'] == 1) {

				$total_profile = $clsProfile->countItem("`status_id`<>'"._STATUS_STAFF_OFF_ID."'");

				$html .= "<div class='text-dark p-2'>Tất cả nhân viên (".$total_profile.")</div>";

			}else{

				#phòng ban

				$arr_department_ids = !empty($oneItem['list_department_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_department_id']) : array();

				$arr_department = [];

				foreach($arr_department_ids as $department){

					if(!isset($arr_cache['department'][$department])){

						$arr_cache['department'][$department] = $clsProperty->getTitle($department);

					}

					$arr_department[] = $arr_cache['department'][$department];

				}

				if(!empty($arr_department)) {

					$html .= "<div class='px-2 py-1'><span class='fw-bold text-dark'>Phòng ban:</span> ".implode(", ",$arr_department)."</div>";

				}

				#nhóm nhân viên

				$arr_title_group = [];

				$arr_group_profile_id = !empty($oneItem['list_group_profile_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_group_profile_id']) : array();

				if(!empty($arr_group_profile_id)) {

					$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 and group_profile_id IN (".implode(',',$arr_group_profile_id).")");	

					foreach ($lstGroupProfile as $kgp => $vgp) {

						$arr_title_group[] = $vgp['title'];

					}

				}	

				if(!empty($arr_title_group)) {

					$html .= "<div class='px-2 py-1'><span class='fw-bold text-dark'>Nhóm nhân viên:</span> ".implode(", ",$arr_title_group)."</div>";

				}



				#nhân viên

				$arr_profile_id = !empty($oneItem['list_profile_id']) ? $clsISO->getArrayByTextSlash($oneItem['list_profile_id']) : array();

				$arr_profile = [];

				foreach($arr_profile_id as $profile){

					if(!isset($arr_cache['profile'][$profile])){

						$arr_cache['profile'][$profile] = $clsProfile->getFullName($profile);

					}

					$arr_profile[] = $arr_cache['profile'][$profile];

				}

				if(!empty($arr_profile)) {

					$html .= "<div class='px-2 py-1'><span class='fw-bold text-dark'>Nhân viên:</span> ".implode(", ",$arr_profile)."</div>";

				}

			}

		}		

	}

	// Return

	echo $html;die;

}

function default_open(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	$clsTraining = new Training();

	$clsProperty = new Property();

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('clsTraining', $clsTraining);

	#

	$uid = $clsISO->getUniqid();

	$training_id = (int) Input::post('training_id', 0);

	$oneItem = $clsTraining->getOne($training_id);

	$lstLesson = $clsISO->to_array_json($oneItem['lesson']);

	$more_information = $oneProfile['more_information'];

	$training = (!empty($more_information["training"])) ? $more_information["training"] : [];

	$lesson_complete = !empty($training[$training_id]["lesson_complete"]) ? $training[$training_id]["lesson_complete"] : [];

	$lesson_complete = array_keys($lesson_complete);

	if($clsISO->_DEV()){

		$clsQuiz = new Quiz();

		$clsGroupProfile = new GroupProfile();

		$lstDepartmentId = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);

		$cond = "`is_trash`='0' AND `is_online`='1' AND `quiz_type`='training' AND `quiz_type_id`='{$training_id}' AND ((`is_all_staff` = 1 )";

		if(!empty($lstDepartmentId)) {

			$cond .= " OR (`is_all_staff`='2' AND ( `list_profile_id` LIKE '%|{$profile_id}|%'";

			foreach ($lstDepartmentId as $key => $department_id) {

				$cond .= " OR `list_department_id` LIKE '%|{$department_id}|%'";

			}			

			$cond .= "))";

		}



		$list_profile_groups = $clsGroupProfile->getAll("`list_profile_id` like '%|{$profile_id}|%'", $clsGroupProfile->pkey);

		if(!empty($list_profile_groups)){ $ii = 0;

			 $cond .= " OR (`is_all_staff`='3' AND ( ";

			foreach($list_profile_groups as $key => $val){

				$cond.= ($ii==0 ? "" : " OR "). " `list_group_profile_id` like '%|{$val[$clsGroupProfile->pkey]}|%'";

				++$ii;

			}

			$cond .= "))";

		}

		$cond .= ")";

		$cond .= " AND ( (`end_date` < ".time()." AND `start_date`='0') OR (`start_date` <= ".time()." AND `end_date`='0') OR (`start_date` <= ".time()." AND `end_date`> ".time()."))";

		$oneQuiz = $clsQuiz->getByCond($cond." ORDER BY `upd_date` DESC");

		$smarty->assign('clsQuiz', $clsQuiz);

		$smarty->assign('oneQuiz', $oneQuiz);

	}

	

	// Return

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('training_id', $training_id);

	$smarty->assign('oneItem', $oneItem);

	$smarty->assign('lstLesson', $lstLesson);

	$smarty->assign('lesson_complete', $lesson_complete);

	$smarty->assign('uid', $uid);

	$html = $core->build('_ajax.open.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_loadHistory(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	$clsTraining = new Training();

	$clsProperty = new Property();

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('clsTraining', $clsTraining);

	#

	$uid = $clsISO->getUniqid();

	$training_id = (int) Input::post('training_id', 0);

	$oneItem = $clsTraining->getOne($training_id);	

	$more_information_training = $clsISO->to_array_json($oneItem['more_information']);

	$history_learning = !empty($more_information_training['history_learning']) ? $more_information_training['history_learning'] : array();

//		$clsISO->print_pre($history_learning);die;

	$arr_profile = $lstProfile = array();

	foreach ($history_learning as $k_his => $v_his) {

		$total_view += count($v_his);

		$v_his = array_reverse($v_his);

		foreach ($v_his as $history) {

			if(!empty($history['profile_id'])) {

				if(!isset($arr_profile[$history['profile_id']])){

					$_oProfile = $clsProfile->getOne($history['profile_id'],$clsProfile->pkey.',department_id,avatar,role_id,full_name');		

					if(!isset($arr_property_cached[$_oProfile["department_id"]])){

						$arr_property_cached[$_oProfile["department_id"]] = $clsProperty->getTitle($_oProfile["department_id"]);

					}

					$_oProfile["department_name"] = $arr_property_cached[$_oProfile["department_id"]];

					# department

					if(!isset($arrCached[$_oProfile['role_id']])){

						$arrCached[$_oProfile['role_id']] = $clsProperty->getTitle($_oProfile['role_id']);

					}	

					$_oProfile['role_name'] = $arrCached[$_oProfile['role_id']];

					$_oProfile['total_view'] = 1;

					$_oProfile['time_last'] = $clsISO->getTimeAgo($history['end_time']);

					$arr_profile[$history['profile_id']] = $_oProfile;

					unset($_oProfile);

				}else{

					$arr_profile[$history['profile_id']]['total_view'] += 1; 

				}

			}				

		}			

	}

	// Return

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('lstProfile', $arr_profile);

	$smarty->assign('training_id', $training_id);

	$smarty->assign('oneItem', $oneItem);

	$smarty->assign('uid', $uid);

	$html = $core->build('_ajax.loadHistory.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_learning(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	$clsTraining = new Training();

	$smarty->assign('clsTraining', $clsTraining);

	#

	$uid = $clsISO->getUniqid();

	$lesson_id = Input::post('lesson_id');

	$training_id = (int) Input::post('training_id', 0);

	$oneItem = $clsTraining->getOne($training_id);

	

	$more_informaton_training = $oneItem['more_information'];

	$more_informaton_training = $clsISO->to_array_json($more_informaton_training);

	$number_view = !empty($more_informaton_training['history_learning'][$lesson_id]) 

		? count($more_informaton_training['history_learning'][$lesson_id]) : 0;

	

	$lstLesson = $clsISO->to_array_json($oneItem['lesson']);

	$smarty->assign('number_view', $number_view);

	$html = "";

	$oneLesson = [];

	if(isset($lstLesson[$lesson_id])){

		$oneLesson = $lstLesson[$lesson_id];		

	}

	$more_information  = $oneProfile['more_information'];

	$training = (!empty($more_information["training"])) ? $more_information["training"] : [];

	$lesson_complete = !empty($training[$training_id]["lesson_complete"]) ? $training[$training_id]["lesson_complete"] : [];

	// Return

	$smarty->assign('training_id', $training_id);

	$smarty->assign('lesson_id', $lesson_id);

	$smarty->assign('oneLesson', $oneLesson);

	$smarty->assign('oneItem', $oneItem);

	$smarty->assign('lstLesson', $lstLesson);

	$smarty->assign('lesson_complete', $lesson_complete);

	$smarty->assign('uid', $uid);

	

	$start_time = time();

	$smarty->assign('start_time', $start_time);

	#log view

	/*$more_information['history_learning'][$profile_id][] = [

		"training_id"	=>	$training_id,

		"lesson_id"		=>	$lesson_id,

		"time"			=>	time()

	];	

	$clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information)]);*/

	$html = $core->build('_ajax.learning.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'result' =>	true,

		'html' => $html,

		"start_time" =>	$start_time

	)); die();

}

function default_log_training(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;

	$clsTraining = new Training();

	$clsProfile = new Profile();

	$smarty->assign('clsTraining', $clsTraining);

	$uid = $clsISO->getUniqid();

	$start_time = Input::post("start_time",0);

	$training_id = (int) Input::post('training_id', 0);

	$lesson_id = Input::post('lesson_id', "");

	#	

	$oneItem = $clsTraining->getOne($training_id);

	$lstLesson = $clsISO->to_array_json($oneItem['lesson']);

	$more_information = $clsISO->to_array_json($oneItem['more_information']);

	

	$data = ["result"	=> false];

	if(!empty($oneItem)){

		if(isset($lstLesson[$lesson_id])){

			$oneLesson = $lstLesson[$lesson_id];	

			$history_learning = (!empty($more_information["history_learning"])) ? $more_information["history_learning"] : [];

			$more_information['history_learning'][$lesson_id][] = [

				"profile_id"	=>	$profile_id,

				"start_time"		=>	$start_time,

				"end_time"			=>	time()

			];

			if($clsTraining->updateOne($training_id,["more_information" => json_encode($more_information)])) {

				$data = ["result"	=> true];

			}

		}

	}

	// Return

	echo json_encode($data);die;

}

function default_completed(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;

	$clsTraining = new Training();

	$clsProfile = new Profile();

	$smarty->assign('clsTraining', $clsTraining);

	###

	$uid = $clsISO->getUniqid();

	$lesson_id = Input::post('lesson_id');

	$training_id = (int) Input::post('training_id', 0);

	$oneItem = $clsTraining->getOne($training_id);	

	$lstLesson = $clsISO->to_array_json($oneItem['lesson']);

	$more_information = $oneProfile['more_information'];

	###

	$html = ""; $oneLesson = [];

	$data = ["result"	=>	false];

	if(isset($lstLesson[$lesson_id])){

		$oneLesson = $lstLesson[$lesson_id];	

		$point_lesson = $oneLesson['point'];

		$more_information['training']["total_point"] = $more_information['training']["total_point"] + $point_lesson;

		$more_information['training'][$training_id]["training_id"] = $training_id;

		$arr_completed = [ "time"	=>	time(),"point"	=>	$point_lesson];

		$more_information['training'][$training_id]["lesson_complete"][$lesson_id] = $arr_completed;

		if($clsProfile->updateOne($profile_id, array(

			"more_information" => json_encode($more_information, JSON_UNESCAPED_UNICODE)

		))) {

			$progress = $clsTraining->getProgress($training_id, $oneItem);

			$data = ["result" => true, "progress" => $progress];

		}

	}

	// Return

	echo json_encode($data); die();

}

function default_set_view(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id;

	###

	$view = Input::post('view', 'grid');

	vnSessionSetVar('view_training', $view);

	// Return

	echo 1; die();

}

function default_inspire(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;

	$clsProfile = new Profile(); 

	$clsProperty = new Property();

	$clsGroupProfile = new GroupProfile(); 

	$clsTraining = new Training(); 

	$assign_list['clsTraining'] = $clsTraining;

	if(!empty($_POST['submit']) && $_POST['submit'] == "search") {

		$keyword = Input::post("keyword","");

		$string = "";

		if(!empty($keyword)) {

			$string = "?k=".str_replace(" ","+",$keyword);

		}

		header("Location: ".$clsISO->getLink('inspire').$string);exit();

	}

	$scriptJs = "";

	$keyword = Input::get('k', "");	

	$assign_list["key_search"] = $keyword;

	$str_url = "";

	if(in_array($profile_id, _PROFILE_NOT_ACESS_LOGS_ID)){

		$cond = "1=1";

	} else {

		$cond = "`is_trash`='0' AND (`is_online`='1' OR `user_id`='{$profile_id}')";

	}

	###

	$cond .= "	AND `cat_id`='"._CAT_INSPIRE_ID."'";

	if(trim($keyword) != "") {

		$cond .= " AND (`title` LIKE '%{$keyword}%' OR `slug` LIKE '%{$keyword}%')";

		$str_url .= "?keyword=".str_replace(" ","+",$keyword);

	}

	$cond .= " AND `is_all_staff`='1'";

	

	$current_page = Input::get('page',1);

	$per_page  = 20;

	$total_record = $clsTraining->countItem($cond);

	$total_page = @ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " LIMIT {$offset},{$per_page}";

	$order_by = " ORDER BY upd_date DESC ";

	$config = array(

		'total'	=> $total_record,

		'current_page'	=> $current_page,

		'number_per_page'	=> $per_page,

		'link'	=> str_replace(".html","/",$clsISO->getLink("inspire"))

	);

	$clsPagination = new Pagination();

	$clsPagination->initianize($config);

	$html_pager = $clsPagination->create_links_page(1,$str_url);

	$assign_list["html_pager"] = $html_pager;

//	$dbconn->debug=true;

	$lstTraining = $clsTraining->getAll($cond.$order_by.$limitCond);

//	$clsISO->print_pre($lstTraining);die;

	foreach ($lstTraining as $key => $val) {

		$total_view = 0;

		$arr_profile = [];

		$lesson = $clsISO->to_array_json($val['lesson']);

		$lesson_id = array_keys($lesson)[0];

		$lstTraining[$key]["lesson_id"] = $lesson_id;

		$more_information = $clsISO->to_array_json($val['more_information']);

		$lstTraining[$key]["more_information"] = $more_information;

		$history_learning = !empty($more_information['history_learning']) ? $more_information['history_learning'] : array();

		foreach ($history_learning as $k_his => $v_his) {

			$total_view += count($v_his);

			foreach ($v_his as $history) {

				if(!empty($history['profile_id']) && !$clsISO->checkItemInArray($history['profile_id'],$arr_profile)){

					$arr_profile[] = $history['profile_id'];

				}

			}			

		}

		$lst_liked = !empty($more_information["lst_liked"]) ? $more_information["lst_liked"] : [];

		$lstTraining[$key]["total_liked"] = count($lst_liked);

		###

		//$rnd_learning = mt_rand(200, 500);

		$rnd_learning = 0;

		$rnd = (int) $core->get_field($more_information, 'rnd', 0);

		$lstTraining[$key]["total_profile_learning"] = $rnd_learning + count($arr_profile);

		$lstTraining[$key]["total_view"] = $rnd_learning + $total_view;

		$lstTraining[$key]["total_lesson"] = count($lesson);

		###

	}

	$assign_list["lstTraining"] = $lstTraining;

	$assign_list['total_record'] = $total_record;

    /*=============Title & Description Page==================*/

	$title_page = 'Truyền cảm hứng - ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $clsConfiguration->getValue('meta_description');

	$assign_list["description_page"] = $description_page;

	$keyword_page = $clsConfiguration->getValue('meta_keyword');

	$assign_list["keyword_page"] = $keyword_page;

}



function default_open_inspire(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	$clsTraining = new Training();

	$clsProperty = new Property();

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('clsTraining', $clsTraining);

	#

	$uid = $clsISO->getUniqid();

	$training_id = (int) Input::post('training_id', 0);

	$lesson_id = $link_video = "";

	if($training_id > 0) {

		$oneItem = $clsTraining->getOne($training_id);

		$lstLesson = $clsISO->to_array_json($oneItem['lesson']);

		$more_information = $clsISO->to_array_json($oneItem['more_information']);

		$lesson = $clsISO->to_array_json($oneItem['lesson']);

		$lesson_id = !empty($lesson) ? array_keys($lesson)[0] : "";

		$link_video = !empty($lesson[$lesson_id]["video"]) ? $lesson[$lesson_id]["video"] : "";

		$smarty->assign('oneItem', $oneItem);

		$smarty->assign('lstLesson', $lstLesson);

	}

	// Return

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('training_id', $training_id);

	$smarty->assign('lesson_id', $lesson_id);

	$smarty->assign('link_video', $link_video);

	$smarty->assign('uid', $uid);

	$html = $core->build('_ajax.open_inspire.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_save_inspire(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;

	###

	$clsTraining = new Training();

	$clsProperty = new Property();	

	$clsProjectMeta = new ProjectMeta();

	$training_id = (int)Input::post("training_id",0);

	$title = Input::post("title",""); 

	$slug = $core->replaceSpace($title); 

	$author = Input::post("author",""); 

	$link_video = Input::post("link_video",""); 

	$cat_id = (int)Input::post("cat_id",0); 

	$content = Input::post("content",""); 

	$video = Input::post("video",""); 

	$file = Input::post("file",""); 

	$lesson_id = Input::post("lesson_id",""); 

	$point = (int)Input::post("point",0); 

	$assign_list['field_id'] = $field_id;

	$data=["result"	=>	false, 'msg'		=>	"ERROR!"];	

	$uid = $clsISO->getUniqid();

//	$clsISO->print_pre($_POST);die;

	if($title != "" && $slug != "" && $link_video != "" ) {

		$data_video = $clsProjectMeta->crawl($link_video);

		$data_video["link"] = $link_video;

		$image = "";

		if(!empty($data_video) && !empty($data_video["image"])){

			$image = $data_video["image"];

		}

		$is_all_staff = 1;

		$is_online = 1;

		$lesson_id = !empty($lesson_id) ? $lesson_id : $clsISO->getUniqid();

		$lesson[$lesson_id] = [

			"title"		=>	"",

			"content"	=>	"",

			"video"		=>	addslashes($link_video),

			"file"		=>	"",

			"point"		=>	0,

		];

		$list_cat_id = $clsProperty->getListParent($cat_id,"_TRAINING_CAT");

		$arr_data = [

			"title"				=>	$title,

			"slug"				=>	$slug,

			"author"			=>	$author,

			"image"				=>	$image,

			"user_update_id"	=>	$profile_id,

			"cat_id"			=>	$cat_id,

			"list_cat_id"		=>	$list_cat_id,

			"is_all_staff"		=>	$is_all_staff,

			"is_online"			=>	$is_online,

			"lesson"			=>	json_encode($lesson,JSON_UNESCAPED_UNICODE),

			"_from"				=>	"_CA",

			"upd_date"			=>	time(),

		];

		$res = [

			"result"	=>	false,

			"msg"	=>	"Lỗi!",

		];

		if($training_id > 0) {

			$oneItem = $clsTraining->getOne($training_id);

			$more_information = json_decode($oneProfile['more_information']);

			$more_information["data_video"] = $data_video;

			$arr_data["more_information"] = json_encode($more_information,JSON_UNESCAPED_UNICODE);

			if($clsTraining->updateOne($training_id,$arr_data)) {

				$res = [

					"result"	=>	true,

					"msg"	=>	"Cập nhật thành công!",

				];

				$clsTraining->calculatorTimeTraining($training_id);

			}

		}else{

			$more_information = ["data_video" => $data_video];

			$arr_data["more_information"] = json_encode($more_information,JSON_UNESCAPED_UNICODE);

			$training_id = $clsTraining->getMaxID();

			$arr_data[$clsTraining->pkey]  = $training_id;

			$arr_data["user_id"]  = $profile_id;

			$arr_data["reg_date"]  = time();

			if($clsTraining->insert($arr_data)) {

				$clsTraining->calculatorTimeTraining($training_id);

				$res = [

					"result"	=>	true,

					"msg"	=>	"Thêm mới thành công!",

				];

			}

		}

	}	

	echo json_encode($res); die();

}

function default_uploadVideo(){

	global $core,$_frontIsLoggedin_user_id,$clsISO,$profile_id;

	$image = '';

	/*if(is_uploaded_file($_FILES['video']['tmp_name'])){

		$clsUploadFile = new UploadFile();

		$file = $clsUploadFile->uploadItem($_FILES["video"],"/video",EXTENSION_VIDEO_UPLOAD);

		if(!empty($file) && file_exists(ROOTPATH . $file)){

			// Set the file metadata for drive

			$title = $_FILES["video"]["name"];

			$mimeType = $_FILES["video"]["type"];

			$clsGoogleDrive = new GoogleDrive();

			$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$file, GOOGLE_DRIVE_FOLDER_VIDEO_ID);

			@unlink(ROOTPATH . $file);

			$image = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';

		}

	}*/

	$image = 'https://drive.google.com/file/d/1YO73w_F9kcJR9kiQ5NdcnjylYh-kTqGG/view';

	// Return

	echo $image; die();

}

function default_inspire_like(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;

	$clsTraining= new Training();

	$training_id = (int) Input::post('training_id', 0);

	$_type = Input::post('_type', "");

	#

	if($training_id == 0){

		echo json_encode([

			"result"	=>	false,

			"msg"	=>	"_invalid",

		 ],JSON_UNESCAPED_UNICODE);die;

	} else {

		$html_like = '';

		$oneTraining = $clsTraining->getOne($training_id,"more_information");

		$more_information = $clsISO->to_array_json($oneTraining["more_information"]);

		$lst_liked = !empty($more_information["lst_liked"]) ? $more_information["lst_liked"] : [];

		if(in_array($profile_id, $lst_liked)){

			$action = 'unlike';

			$lst_liked = array_diff($lst_liked, array($profile_id));

			$html_like = (count($lst_liked) > 0) ? ('<a href="javascript:void(0)" onClick="$Core.inspire.like(this,event)" class="" data-url="/index.php?mod=training&act=load_list_like&training_id='.$training_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300" >' . count($lst_liked) . ' người thích video này</a>') : '';

		} else {

			$action = 'like';			

			if(count($lst_liked) == 0) {

				$html_like = '<a href="javascript:void(0)" class="" data-url="/index.php?mod=training&act=load_list_like&training_id='.$training_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300" >Bạn đã thích video này</a>';

			}else{

				$html_like = '<a href="javascript:void(0)" class="" data-url="/index.php?mod=training&act=load_list_like&training_id='.training_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300" >Bạn và ' . count($lst_liked) . ' người thích video này</a>';

			}

			$lst_liked[] = $profile_id;

		}

		$more_information["lst_liked"] = $lst_liked;

		if($clsTraining->updateOne($training_id, array(

			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

		))){

			$total_likes = !empty($lst_liked) ? count($lst_liked) : 0;

			$html = '<a href="javascript:void(0);" training_id="'.$training_id.'" _type="home" onClick="$Core.inspire.like(this,event)" class="inspire_like inspire_'.$training_id.' text-dark"><i class="bx '.($action=='like' ? 'bxs-heart text-main' : 'bx-heart').'"></i> '.($total_likes > 0 ? $total_likes . " " : '').'</a>';		

		}

		// Return

		echo json_encode([

			"result"	=>	true,

			"html"	=>	$html,

			"html_like"	=>	$html_like,

		 ],JSON_UNESCAPED_UNICODE);die;

	}

}



function default_load_list_like(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsTraining = new Training();

	$training_id = (int) Input::get('training_id', 0);

	#

	$callback = "";

	$list_dep = $clsProperty->getArraySearchByKey("_DEPARTMENT");

	if(!empty($training_id)){

		$listProfile = $clsProfile->getProfileCached("active");

		$oneTraining = $clsTraining->getOne($training_id,"more_information");

		$more_information = $clsISO->to_array_json($oneTraining["more_information"]);

		$lst_liked = !empty($more_information["lst_liked"]) ? $more_information["lst_liked"] : [];

		if(!empty($lst_liked)) {			

			$html= '<ul class="p-0 px-2 m-0 overflow-y-auto" style="max-height: 250px">';

			foreach($lst_liked as $profile_id) {

				$oProfile = $listProfile[$profile_id];

				$department_name = $list_dep[$oProfile["department_id"]]["title"];

				$html .= '<li class="d-flex align-items-cecnter'.($key==$total_billings-1? '' : ' mb-1 pb-2').'">

					<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" >

						<img src="'.$clsProfile->getAvatar($profile_id, $oProfile, 40, 40).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" alt="'.$oProfile["full_name"].'" class="rounded-pill" />

						'.$clsProfile->get_icon_verified($profile_id, $oProfile["more_information"]).'

					</div>

					<div class="w-100">

						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">

							<small class="text-muted d-block">'.$oProfile["code"].'-'.$department_name.'</small>

						</div>

						<h6 class="mb-0">'.$oProfile["full_name"].'</h6>

					</div>

				</li>';

			}

			$html.= '</ul>';

		}

		// Return

		echo $html; die();

	}

	echo json_encode(array(

		'html' => $html,

		'callback' => $callback

	)); die();

}

function default_update_field(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsTraining = new Training();

	$training_id = (int) Input::post('training_id', 0);

	$action =  Input::post('action', "_trash");

	#

	$res = ["result" => false, "msg" => "Lỗi!"];

	if(!empty($training_id)){

		$oneTraining = $clsTraining->getOne($training_id,"more_information");

		$upd_data = [

			"upd_date"	=>	time,

			"user_update_id"	=>	$profile_id

		];

		if($action == "_trash") {

			$upd_data["is_online"] = 0;

		}elseif($action == "_untrash") {

			$upd_data["is_online"] = 1;

		}

		if($clsTraining->updateOne($training_id,$upd_data)) {

			$res = ["result" => true, "msg" => "Thành công!"];

		}

	}

	// Return

	echo json_encode($res); die();

}

