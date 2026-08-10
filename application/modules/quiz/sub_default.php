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
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsGroupProfile = new GroupProfile(); 
	$clsProperty = new Property(); 
	$clsPagination = new Pagination(); 
	$clsProfile = new Profile(); 
	$cond = "`user_id`='{$profile_id}' AND `is_trash`='0'";
	
	$current_page = Input::get('page',1);
	$per_page  = 50;
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	
	$total_record = $clsQuiz->countItem($cond);
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> str_replace(".html","/",$clsISO->getLink("request_ptg"))
	);
	
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(1,$str_url);
	$assign_list["html_pager"] = $html_pager;
	
	$order_by = " ORDER BY `reg_date` DESC";
	$lstQuiz = $clsQuiz->getAll($cond.$order_by.$limitCond);
	foreach ($lstQuiz as $key => $val) {
		$total_question = 0;
		$more_information = $clsISO->to_array_json($val['more_information']);
		$lstGroup = $clsISO->to_array_json($val['questions']);
		foreach ($lstGroup as $k_g => $v_g) {
			$total_question += (!empty($v_g['questions']) ? count($v_g['questions']) : 0);
		}
		$lstQuiz[$key]['questions'] = $lstGroup;
		$lstQuiz[$key]['total_question'] = $total_question;
		$lstQuiz[$key]['ref_name']       = $clsQuiz->getRefName($val);
		if($val['is_all_staff'] == 2) {
			$arr_profile = [];
			#phòng ban
			$arr_department_ids = !empty($val['list_department_id']) ? $clsISO->getArrayByTextSlash($val['list_department_id']) : array();
			$arr_department = [];
			$cond_profile = "";
			foreach($arr_department_ids as $department){
				if(!isset($arr_cache['department'][$department])){
					$arr_cache['department'][$department] = $clsProperty->getTitle($department);
				}
				$arr_department[] = $arr_cache['department'][$department];
				
				$cond_profile .= (($cond_profile != "")?" OR ":"")." department_id='{$department}' OR list_department_id like '%|{$department}|%' " ;
			}
			$lstQuiz[$key]['department'] = implode(", ",$arr_department);	
			#nhân viên
			$arr_profile_id = !empty($val['list_profile_id']) ? $clsISO->getArrayByTextSlash($val['list_profile_id']) : array();
			$arr_profile = [];
			foreach($arr_profile_id as $profile){
				if(!isset($arr_cache['profile'][$profile])){
					$arr_cache['profile'][$profile] = $clsProfile->getFullName($profile);
				}
				$arr_profile[] = $arr_cache['profile'][$profile];
				$cond_profile .= (($cond_profile != "")?" OR ":"")." profile_id='{$profile}' ";
			}
			$cond_profile  = ($cond_profile != "") ? " and (".$cond_profile.")" : "";
			$lstQuiz[$key]['profile'] = implode(", ",$arr_profile);
		}else if($val['is_all_staff'] == 3){			
			#nhóm nhân viên
			$arr_group_profile_id = !empty($val['list_group_profile_id']) ? $clsISO->getArrayByTextSlash($val['list_group_profile_id']) : array();
			if(!empty($arr_group_profile_id)) {
				$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 and group_profile_id IN (".implode(',',$arr_group_profile_id).")");	
				$arr_profile_group = $arr_title_group = [];
				foreach ($lstGroupProfile as $kgp => $vgp) {
					$arr_profile_group = array_merge($arr_profile_group,$clsISO->getArrayByTextSlash($vgp['list_profile_id']));
					$arr_title_group[] = $vgp['title'];
				}
				$arr_profile_group = array_unique($arr_profile_group);
				if(!empty($arr_profile_group)){					
					$lstQuiz[$key]['group_profile'] = implode(", ",$arr_title_group);
					$cond_profile .= " profile_id IN ('".implode("','",$arr_profile_group)."') ";
				}
			}
			$cond_profile  = ($cond_profile != "") ? " and (".$cond_profile.")" : "";
		}
		$total_profile = $clsProfile->countItem("`status_id`<>'"._STATUS_STAFF_OFF_ID."' ".$cond_profile);
		$lstQuiz[$key]['total_profile'] = $total_profile;
//		$clsQuizAnswers->setDeBug(1);
		$total_answer = $clsQuizAnswers->getByCond("`quiz_id`='{$val[$clsQuiz->pkey]}' AND `is_trash`='0' ","COUNT(DISTINCT `user_id`) as total");
		$total_answer = $total_answer["total"];
		$total_answer_completed = $clsQuizAnswers->countItem("`quiz_id`='{$val[$clsQuiz->pkey]}' AND `is_completed`='1' AND `is_trash`='0' ");
		$lstQuiz[$key]['total_answer'] = $total_answer;
		$lstQuiz[$key]['total_answer_completed'] = $total_answer_completed;
	}
//	$clsISO->print_pre($lstQuiz);die;
	$assign_list["show"] = $show;
	$assign_list["clsQuiz"] = $clsQuiz;
	$assign_list["lstQuiz"] = $lstQuiz;
	
	$scriptJs = "";
	
    /*=============Title & Description Page==================*/
	$title_page = 'Trắc nghiệm - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_edit(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsQuiz = new Quiz(); 
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	
	$quiz_id = (int)Input::get("quiz_id",0);
	$oneItem = $questions = $more_information = array();
	$group_id = $clsISO->getUniqid();
	$question_id = $clsISO->getUniqid();
	if(!empty($quiz_id)) {
		$oneItem = $clsQuiz->getOne($quiz_id);
		$questions = $clsISO->to_array_json($oneItem['questions']);	
		$more_information = $clsISO->to_array_json($oneItem['more_information']);	
		$group_id = end(array_keys($questions));
	}	
	$assign_list["oneItem"] = $oneItem;		
	$assign_list["questions"] = $questions;	
	$assign_list["more_information"] = $more_information;	
	
	$answer_options = [
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
	];
	$is_split_points = 1;
	
	$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 ORDER BY upd_date DESC");
	$field = "{$clsProfile->pkey},first_name,last_name,full_name";
	$list_profiles = $clsProfile->getAll("is_trash=0 and status_id<>'"._STATUS_STAFF_OFF_ID."' order by code ASC", $field);
	$assign_list["list_profiles"] = $list_profiles;
	
	$assign_list["quiz_id"] = $quiz_id;	
	$assign_list["clsGroupProfile"] = $clsGroupProfile;	
	$assign_list["lstGroupProfile"] = $lstGroupProfile;	
	$assign_list["group_id"] = $group_id;
	$assign_list["question_id"] = $question_id;
	$assign_list["answer_options"] = $answer_options;
	$assign_list["is_split_points"] = $is_split_points;
	$clsQuizTestCategory = new QuizTestCategory();
	$clsProject = new Project();
	$clsTraining = new Training();
	$assign_list += array(
		'categoryOptions'  => $clsQuizTestCategory->getSelectOptions($oneItem["quiz_type_id"]),
		'projectOptions'   => $clsProject->getSelectOptions($oneItem["quiz_type_id"]),
		'trainingOptions'   => $clsTraining->getSelectOptions($oneItem["quiz_type_id"])
	);
	
    /*=============Title & Description Page==================*/
	$title_page = 'Thêm mới trắc nghiệm - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_quiz(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsQuiz', $clsQuiz);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$type = Input::post("type","open");
	$quiz_id = (int)Input::post("quiz_id",0);
	$assign_list["quiz_id"] = $quiz_id;
	###
	$field = "{$clsProfile->pkey},first_name,last_name,full_name";
	$list_profiles = $clsProfile->getAll("is_trash=0 and status_id<>'"._STATUS_STAFF_OFF_ID."' 
	and profile_id not in(".implode(',',_PROFILE_NOTIN_ID).") order by code ASC", $field);
	$assign_list["list_profiles"] = $list_profiles;
	$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 ORDER BY upd_date DESC");
	$assign_list["lstGroupProfile"] = $lstGroupProfile;
	###
	$action = "add";
	$data = ['result' =>	false];
	$start_date = date("Y-m-d\TH:i");
	$due_date = date("Y-m-d\TH:i",strtotime("+1 days"));
	if($quiz_id > 0){
		$action = "edit";
		$oneItem = $clsQuiz->getOne($quiz_id);
		$start_date = date("Y-m-d\TH:i",$oneItem['start_date']);
		$end_date = date("Y-m-d\TH:i",$oneItem['end_date']);
		$oneItem['start_date'] = $start_date;
		$oneItem['due_date'] = $due_date;
		// var_dump($oneItem);die;
		$assign_list["oneItem"] = $oneItem;
	}	
	$assign_list["start_date"] = $start_date;
	$assign_list["due_date"] = $due_date;
	$assign_list["action"] = $action;
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$assign_list["more_information"] = $more_information;
	if($type == "open"){
		$html = $core->build('_ajax.open_quiz.tpl');
		$data = [
			'result'	=>	true,
			'uid' => $uid,
			'html' => $html
		];
	}else if($type == 'add' || $type == 'edit'){
		$quiz_id = (int)Input::post('quiz_id',0);
		$title = Input::post('title',"");
		$content = Input::post('content',"");
		$banner = Input::post('banner',"");
		$start_date = Input::post('start_date',"");
		$start_date = $clsISO->toTime($start_date);
		$end_date = Input::post('end_date',"");
		$end_date = $clsISO->toTime($due_date);
		$hours = (int)Input::post('hours',0);
		$minutes = (int)Input::post('minutes',0);
		$duration = $minutes;
		if($hours > 0) {
			$duration += ($hours * 60);
		}
		$score = (int)Input::post('score',0);
		$submission_count = (int)Input::post('submission_count',0);
		$is_all_staff = (int)Input::post('is_all_staff',0);
		$list_department_id = Input::post('list_department_id',array());
		$list_department_id = !empty($list_department_id) ? $clsISO->makeSlashListFromArrayRoot($list_department_id) : "";
		$list_profile_id = Input::post('list_profile_id',array());
		$list_profile_id = !empty($list_profile_id) ? $clsISO->makeSlashListFromArrayRoot($list_profile_id) : "";
		$list_group_profile_id = Input::post('list_group_profile_id',array());
		$list_group_profile_id = !empty($list_group_profile_id) ? $clsISO->makeSlashListFromArrayRoot($list_group_profile_id) : "";
		$is_group = (int)Input::post('is_group',0);
		$groups = Input::post('groups',array());
		$questions = Input::post('questions',array());
		
		$more_information["hours"] = $hours;
		$more_information["minutes"] = $minutes;
		$more_information["questions"] = $questions;
		$more_information["groups"] = $groups;
		if(!empty($is_group) && !empty($groups)) {
			foreach ($groups as $k_gr => $group) {
				$lst_question = !empty($group["questions"]) ? $group["questions"] : array();
				foreach ($lst_question as $key => $val) {
					$lst_question[$key] = $clsISO->to_array_json($val);
				}
				$groups[$k_gr]["questions"] = $lst_question;
			}
			$questions = $groups;
		}else{
			foreach ($questions as $key => $val) {
				$questions[$key] = $clsISO->to_array_json($val);
			}
		}
		$arr_field = [
			'title'				=>	addslashes($title),
			'slug'				=>	$core->replaceSpace($title),
			'banner'			=>	$banner,
			'intro'				=>	addslashes($content),
			'start_date'		=>	$start_date,
			'end_date'			=>	$end_date,
			'duration'			=>	$duration,
			'submission_count'	=>	$submission_count,
			'score'				=>	$score,
			'is_group'			=>	$is_group,
			'is_all_staff'		=>	$is_all_staff,
			'list_profile_id'	=>	$list_profile_id,
			'list_department_id'	=>	$list_department_id,
			'list_group_profile_id'	=>	$list_group_profile_id,
			'questions'			=>	$questions,
			'user_update_id'	=>	$profile_id,
			'upd_date'			=>	time(),
			'more_information'	=>	json_encode($more_information),
		];
		$lstProfileID = $clsQuiz->getLstIDProfile($list_department_id,$list_profile_id,$list_group_profile_id);
		if($quiz_id == 0){
			$quiz_id = $clsQuiz->getMaxID();
			$more = [
				"quiz_id"	=>	$quiz_id,
				"reg_date"	=>	time(),
				"user_id"	=>	$profile_id,
				"is_trash"	=>	0,
				"is_online"	=>	1,
			]; 
//			$clsQuiz->setDeBug(1);
			if($clsQuiz->insert(array_merge($arr_field,$more))){
				$data=["result"	=>	true,];
				/*$clsNotify = new Notify();
				$titleNoty = sprintf('<strong>%s</strong> gửi lời mời tham gia bài trắc nghiệm <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $title);
				$clsNotify->insertNotify('Quiz',$clsQuiz->pkey, $quiz_id, $titleNoty, time(), $lstProfileID);
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Trắc nghiệm online",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsQuiz->getLink($quiz_id)
				];
				$clsNotification->doPushMessagingUser($params,$lstProfileID);
				*/				
			}
			
		}else{
			if($clsQuiz->updateOne($quiz_id,$arr_field)){
				$data=["result"	=>	true];
				if($is_all_staff==0 || $is_all_staff==2){					
					/*$clsNotify = new Notify();
					$lstProfileIdOld = $clsQuiz->getLstIDProfile($oneItem['list_department_id'],$oneItem['list_profile_id']);
					$lstProfileIdUpdate = array_diff($lstProfileID,$lstProfileIdOld);
					$titleNoty = sprintf('<strong>%s</strong> gửi lời mời tham gia bài trắc nghiệm <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $title);
					$clsNotify->insertNotify('Quiz',$clsQuiz->pkey, $quiz_id, $titleNoty, time(), $lstProfileIdUpdate);
					#thong bao app
					$clsNotification = new Notification();
					$params = [
						'title' => "Trắc nghiệm online",
						'body' => strip_tags($titleNoty),
						'link' => PCMS_URL . $clsQuiz->getLink($quiz_id)
					];
					$clsNotification->doPushMessagingUser($params,$lstProfileIdUpdate);
					*/
				}
			}
		}
	}
	// Return
	echo json_encode($data); die();
}
function default_loadType(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$smarty->assign('clsQuiz', $clsQuiz);
	###
	$is_group = (int)Input::post("is_group",0);
	$toId = Input::post("toId","");
	$gId = $clsISO->getUniqid();
	if(!empty($is_group)) {
		$html = '<div class="content_quiz" id="content_quiz_'.$gId.'">
					<div class="item_group p-2 border rounded-1 mb-2">
						<div class="form-group mb-2">					
							<label class="form-label mb-1">Nhóm câu hỏi</label>
							<div class="d-flex align-items-center gap-1">
								<input type="text" class="form-control required form-field" name="group['.$gId.'][title]" placeholder="Tiêu đề" value="">	
								<button class="btn btn-outline-primary btn-icon" type="button" onClick="$Core.quiz.addGroupQuestion(this,event)" toId="content_quiz_'.$gId.'" data-bs-toggle="tooltip" title="Thêm nhóm câu hỏi"><i class="bx bx-list-plus" ></i></button>	
							</div>
						</div>	
						<button class="btn btn-outline-primary btn-sm" type="button" toId="lst_question_'.$gId.'" group_id="'.$gId.'" question_id="" onClick="$Core.quiz.addQuestion(this,event)" data-type="_OPEN" data-bs-toggle="tooltip" title="Thêm câu hỏi">Thêm câu hỏi</button>
						<div class="lst_question" id="lst_question_'.$gId.'">
							
						</div>
					</div>
				</div>';
	}else{
		$html = '<div class="content_quiz" id="content_quiz_'.$gId.'">
				<div class="lst_question" id="lst_question_'.$gId.'">
					
				</div>
		</div>';
	}
	// Return
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$gId
	]); die();
}
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error'; 
	$check = true;
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
//		$clsISO->print_pre($_FILES);die;
		$banner = $_FILES['banner'];
		if(!empty($banner['name'])){ 
			if($banner['size'] > 4194304){
				$msg = "_limit_size";
				$check = false;
			}
			if($check){
				$results = array();
				$clsUploadFile = new UploadFile();
				$up = $clsUploadFile->uploadItem($banner,"/Quiz","jpg,jpeg,webp,png", array(
					'resize' => false,
					'resize_x' => 847,
					'resize_y' => 510,
					'watermark' => true,
				));
				$html = '';
				if(!empty($up) && @file_exists(ABSPATH . $up)){
					$html .= '<span class="d-block w-100">
							<img class="w-100 object-fit-cover" height="200" src="'.$up.'" />
							<input type="hidden" name="banner" value="'.$up.'" />
							<a class="delete" src="'.$up.'" onClick="$Core.upload.delete(this, event)"></a>
						</span>';
					// Return
					$msg = '_success|||' .$html;
				}
			}
		}
	}
	// Return
	echo $msg; die();
}

function default_addAnswer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$smarty->assign('clsQuiz', $clsQuiz);
	###
	$group_id = Input::post("group_id","");
	$question_id = Input::post("question_id","");
	$question_type = Input::post("question_type","1");
	$uid = $clsISO->getUniqid();
	$answer_id = $clsISO->getUniqid();
	$smarty->assign('group_id', $group_id);
	$smarty->assign('question_id', $question_id);
	$smarty->assign('answer_id', $answer_id);
	$smarty->assign('is_group', $is_group);
	$smarty->assign('gId', $gId);
	$smarty->assign('toId', $toId);
	$smarty->assign('number_question', $number_question);
	$smarty->assign('question_type', $question_type);
	$question_id = !empty($question_id) ? $question_id : $clsISO->getUniqid();
	$smarty->assign('question_id', $question_id);
	$html = $core->build('_ajax.add_answer.tpl');
	// Return
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$gId
	]); die();
}
function default_addQuestion(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$smarty->assign('clsQuiz', $clsQuiz);
	###
	$group_id = Input::post("group_id","");
	$number_question = Input::post("number_question","1");
	$question_id = $clsISO->getUniqid();
	$answer_id = $clsISO->getUniqid();
	$is_split_points = 1;
	$question_type = 1;
	$smarty->assign('group_id', $group_id);
	$smarty->assign('is_group', $is_group);
	$smarty->assign('question_id', $question_id);
	$smarty->assign('answer_id', $answer_id);
	$smarty->assign('number_question', $number_question);
	$smarty->assign('is_split_points', $is_split_points);
	$answer_options = [
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
		"{$clsISO->getUniqid()}" =>	[
			"title"	=>	"",
			"is_correct"	=>	0
		],
	];
	$smarty->assign('answer_options', $answer_options);
	$smarty->assign('question_type', $question_type);
	$html = $core->build('_ajax.add_question.tpl');
	// Return
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$gId
	]); die();
}
function default_addGroupQuestion(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$smarty->assign('clsQuiz', $clsQuiz);
	###
	$group_id = $clsISO->getUniqid();
	$smarty->assign('group_id', $group_id);
	$html = $core->build('_ajax.add_group.tpl');
	// Return
	echo json_encode([
		"html"	=>	$html,
		"group_id"	=>	$group_id
	]); die();
}

function default_addQuiz(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsFcmToken = new FcmToken();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsQuiz', $clsQuiz);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	
	###
	$data = ['result' =>	false];	
	$type = Input::post('type',"publish");
	$quiz_id = (int)Input::post('quiz_id',0);
	$title = Input::post('title',"");
	$content = Input::post('content',"");
	$banner = Input::post('banner',"");
	$is_all_staff = (int)Input::post('is_all_staff',0);
	$is_duration = (int)Input::post('is_duration',0);
	$duration = (int)Input::post('duration',0);
	$is_start_date = (int)Input::post('is_start_date',0);
	$is_end_date = (int)Input::post('is_end_date',0);
	$start_date = Input::post('start_date',"");
	$start_date = $clsISO->toTime($start_date);
	$end_date = Input::post('end_date',"");
	$end_date = $clsISO->toTime($end_date);
	$score = (int)Input::post('score',0);
	$is_split_points = (int)Input::post('is_split_points',0);
	$submission_count = (int)Input::post('submission_count',0);
	$groups = Input::post('groups', array());
	
	$quiz_type    = Input::post('quiz_type', 'video');
	if($quiz_type == "training") {
		$quiz_type_id = (int)Input::post("training_id",0);
	}elseif($quiz_type == "module") {
		$quiz_type_id = (int)Input::post("category_id",0);
	}elseif($quiz_type == "project") {
		$quiz_type_id = (int)Input::post("project_id",0);
	}else{
		$link_video    = Input::post('link_video', '');
	}	
	
	$list_department_id = Input::post('list_department_id',array());
	$list_department_id = !empty($list_department_id) ? $clsISO->makeSlashListFromArrayRoot($list_department_id) : "";
	$list_profile_id = Input::post('list_profile_id',array());
	$list_profile_id = !empty($list_profile_id) ? $clsISO->makeSlashListFromArrayRoot($list_profile_id) : "";
	$list_group_profile_id = Input::post('list_group_profile_id',array());
	$list_group_profile_id = !empty($list_group_profile_id) ? $clsISO->makeSlashListFromArrayRoot($list_group_profile_id) : "";

	$more_information["is_duration"] = $is_duration;
	$more_information["is_start_date"] = $is_start_date;
	$more_information["is_end_date"] = $is_end_date;
	$more_information["is_split_points"] = $is_split_points;
	$url = $clsISO->getLink("quiz");
	$arr_field = [
		'title'				=>	addslashes($title),
		'slug'				=>	$core->replaceSpace($title),
		'banner'			=>	$banner,
		'intro'				=>	addslashes($content),
		'start_date'		=>	$start_date,
		'end_date'			=>	$end_date,
		'duration'			=>	$duration,
		'submission_count'	=>	$submission_count,
		'score'				=>	$score,
		'is_all_staff'		=>	$is_all_staff,
		'list_profile_id'		=>	$list_profile_id,
		'list_department_id'		=>	$list_department_id,
		'list_group_profile_id'		=>	$list_group_profile_id,
		'questions'			=>	json_encode($groups,JSON_UNESCAPED_UNICODE),
		'user_update_id'	=>	$profile_id,
		'upd_date'			=>	time(),
		'more_information'	=>	json_encode($more_information),
		"is_online"			=>	($type == "publish") ? 1 : 0,
		'quiz_type'	=>	$quiz_type,
		'quiz_type_id'	=>	!empty($quiz_type_id) ? $quiz_type_id : 0,
		'link_video'	=>	!empty($link_video) ? $link_video : "",
	];
//	$clsISO->print_pre($arr_field);die;
	
	/*$lstProfileID = $clsQuiz->getLstIDProfile($list_department_id,$list_profile_id,$list_group_profile_id);*/
	$lstProfileID = ["289"];
	if($quiz_id == 0){
		$quiz_id = $clsQuiz->getMaxID();
		$more = [
			"quiz_id"	=>	$quiz_id,
			"reg_date"	=>	time(),
			"user_id"	=>	$profile_id,
			"is_trash"	=>	0,
			
		]; 
		if($clsQuiz->insert(array_merge($arr_field,$more))){
			$data=["result"	=>	true,"url" => $url];
			if($type == "publish"){
				$clsNotify = new Notify();
				$titleNoty = sprintf('<strong>%s</strong> gửi lời mời tham gia bài trắc nghiệm <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $title);
				$clsNotify->insertNotify('Quiz',$clsQuiz->pkey, $quiz_id, $titleNoty, time(), $lstProfileID);
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id` in (".implode(',', $lstProfileID).") and `token`<>''", "token");	
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						if(!in_array($val['token'], $subscribers)){
							$subscribers[] = $val['token'];
						}
					}
					$clsNotify->send_subscriber_notification(array(
						'title' => sprintf('Bài trắc nghiệm [%s]', $arr_field['title']),
						'message' => strip_tags($titleNoty),
						'url' => PCMS_URL . $clsQuiz->getLink($quiz_id)
					), $subscribers);
				}
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Trắc nghiệm online",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsQuiz->getLink($quiz_id)
				];
				$clsNotification->doPushMessagingUser($params,$lstProfileID);
			}
		}

	}else{
		if($clsQuiz->updateOne($quiz_id,$arr_field)){
			$data=["result"	=>	true,"url" => $url];
			if($type == "publish"){
				$clsNotify = new Notify();
				$titleNoty = sprintf('<strong>%s</strong> gửi lời mời tham gia bài trắc nghiệm <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $title);
				$clsNotify->insertNotify('Quiz',$clsQuiz->pkey, $quiz_id, $titleNoty, time(), $lstProfileID);
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id` in (".implode(',', $lstProfileID).") and `token`<>''", "token");	
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						if(!in_array($val['token'], $subscribers)){
							$subscribers[] = $val['token'];
						}
					}
					$clsNotify->send_subscriber_notification(array(
						'title' => sprintf('Bài trắc nghiệm [%s]', $arr_field['title']),
						'message' => strip_tags($titleNoty),
						'url' => PCMS_URL . $clsQuiz->getLink($quiz_id)
					), $subscribers);
				}
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Trắc nghiệm online",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsQuiz->getLink($quiz_id)
				];
				$clsNotification->doPushMessagingUser($params,$lstProfileID);
			}
		}
	}
	// Return
	echo json_encode($data); die();
}
function default_deleteQuiz(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsQuiz', $clsQuiz);
	###
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	
	###
	$res = ['result' =>	false];	
	$quiz_id = (int)Input::post('quiz_id',0);
	
	if($clsQuiz->updateOne($quiz_id,["is_trash" => 1])){
		$res = ['result' =>	true];	
	}
	// Return
	echo json_encode($res); die();
}
function default_my_quiz(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsGroupProfile = new GroupProfile(); 
	$clsProperty = new Property(); 
	$clsPagination = new Pagination(); 
	$clsProfile = new Profile(); 	
	$cmd = Input::get("cmd","_list");
	$current_page= "/trac-nghiem/me";
	$lstQuizAnsers = $clsQuizAnswers->getAll("`user_id`='{$profile_id}' AND `time_end` < ".time()." AND `is_trash`='0' AND `is_completed`='0' ");
//	echo time();
//	$clsISO->print_pre($lstQuizAnsers);die;
	if(!empty($lstQuizAnsers)) {
		foreach ($lstQuizAnsers as $key => $val) {
			$clsQuizAnswers->updateOne($val[$clsQuizAnswers->pkey],['is_completed' => 1, "time_completed"	=>	$val['elapsed_time']]);
		}
	}
	
	if($cmd=="_detail") {
		$slug = Input::get('slug', "");
		$quiz_id = (int)Input::get('quiz_id', 0);
		$field = "`more_information`,`is_online`,`is_solded`,`stock_code`,`price`,`building_id`";
		$oneQuiz = $clsQuiz->getByCond("`quiz_id`='{$quiz_id}' AND `slug`='{$slug}' AND `is_online`='1' AND `is_trash`='0'");
		$title_page = 'Bài tập trắc nghiệm - ' . PAGE_NAME;
		if(!empty($oneQuiz)) {
			$total_question = 0;
			$more_information = $clsISO->to_array_json($oneQuiz['more_information']);
			$oneQuiz['more_information'] = $more_information;
			$lstGroup = $clsISO->to_array_json($oneQuiz['questions']);
			foreach ($lstGroup as $k_g => $v_g) {
				$total_question += (!empty($v_g['questions']) ? count($v_g['questions']) : 0);
			}
			$oneQuiz['questions'] = $lstGroup;
			$oneQuiz['total_question'] = $total_question;
			$scriptJs = "";
			#trạng thái
			$quizAnswers = $clsQuizAnswers->getByCond("`quiz_id`='{$quiz_id}' AND `user_id`='{$profile_id}' and `is_trash`='0'");
			$start_quiz = 1;			
			if((time() < $oneQuiz['end_date'] && $oneQuiz['start_date'] == 0) || (time() >= $oneQuiz['start_date'] && $oneQuiz['end_date'] == 0) || ((time() >= $oneQuiz['start_date'] && time() < $oneQuiz['end_date']))) {
				if(!empty($quizAnswers)) {
					if($quizAnswers['is_completed'] == 1) {
						$is_view = 1;
						if(empty($oneQuiz['submission_count']) || $quizAnswers['is_scored'] == 1 ) {
							$msg = "Bạn đã hoàn thành bài trắc nghiệm";
							$start_quiz = 0;
						}else{
							$msg = "Làm lại";
						}
					}
				}
			}else{
				$is_quiz = 0;
				if(time() < $oneQuiz['start_date']){
					$quiz_time_start = ($oneQuiz['start_date'] - time())/60;
					$status_text = "Chưa bắt đầu";
					$msg = "Bài trắc nghiệm bắt đầu vào lúc ".$clsISO->formatDate($oneQuiz['start_date'],4);
					$start_quiz = 0;
				}else{
					if($quizAnswers['is_completed'] == 1) {
						$start_quiz = 0;
					}
					if(!empty($quizAnswers) && $quizAnswers['is_completed'] == 1) {
						$status_text = "Đã làm bài";
						$start_quiz = 0;
					}else{
						$status_text = "Đã kết thúc";
						$start_quiz = 0;
					}	
					$msg = "Bài trắc nghiệm đã kết thúc";			
				}
			}
	//		echo $start_quiz;die;
			if(!empty($start_quiz)) {
				$scriptJs.= '<a class="autoclick_'.$quiz_id.'"" quiz_id="'.$quiz_id.'" onclick="$Core.quiz.begin_test(this,event)"></a>
				<script type="text/javascript">
					$(function(){
						setTimeout(() => {
							console.log("1");
							$(\'.autoclick_'.$quiz_id.'\').trigger(\'click\').remove();
						}, 200);
					});
				</script>';
				$title_page = $oneQuiz['title'].' - Bài tập trắc nghiệm - ' . PAGE_NAME;
			}else{
				$scriptJs.= '<script type="text/javascript">
					$(function(){
						setTimeout(() => {
							$Core.swal.error("'.$msg.'","");
							$Core.util.popstate("'.$current_page.'");
						}, 200);
					});
				</script>';	
			}
		}else{
			$scriptJs.= '<script type="text/javascript">
				$(function(){
					setTimeout(() => {
						$Core.swal.error("Bài trắc nghiệm đã bị hủy!","");
						$Core.util.popstate("'.$current_page.'");
					}, 200);
				});
			</script>';	
			
		}
		
		/*=============Title & Description Page==================*/
		$assign_list["current_page"] = $current_page;
		$assign_list["status_text"] = $status_text;
		$assign_list["start_quiz"] = $start_quiz;
		$assign_list["scriptJs"] = $scriptJs;
		$assign_list["image_page"] = $image_page;
		
		$assign_list["title_page"] = $title_page;
		$description_page = $more_information['description_page'];
		$assign_list["description_page"] = $description_page;
	} else {
		/*=============Title & Description Page==================*/
		$title_page = 'Bài tập trắc nghiệm - ' . PAGE_NAME;
		$assign_list["title_page"] = $title_page;
		$description_page = $clsConfiguration->getValue('meta_description');
		$assign_list["description_page"] = $description_page;
		$keyword_page = $clsConfiguration->getValue('meta_keyword');
		$assign_list["keyword_page"] = $keyword_page;
	}
	
    
}
function default_loadMyQuiz(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsGroupProfile = new GroupProfile(); 
	$clsProperty = new Property(); 
	$clsPagination = new Pagination(); 
	$clsProfile = new Profile(); 
	$show = Input::get("show","my_quiz");
	$cond = "`is_trash`='0' AND `is_online`='1' ";
	$type = Input::post("type","_ALL");
	
	$lstDepartmentId = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	$cond .= " AND ((`is_all_staff` = 1 )";
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
	if($type == "_YES") {
		$cond .= " AND `quiz_id` IN (SELECT `quiz_id` FROM `{$clsQuizAnswers->tbl}` WHERE `is_completed` = 1 AND `user_id`='{$profile_id}' AND `is_trash`='0')";
	}else if($type == "_NO") {
		$cond .= " AND `quiz_id` NOT IN (SELECT `quiz_id` FROM `{$clsQuizAnswers->tbl}` WHERE `is_completed` = 1 AND `user_id`='{$profile_id}' AND `is_trash`='0')";
	}else if($type == "_ON_GOING"){
		$cond .= " AND ( (`end_date` < ".time()." AND `start_date`='0') OR (`start_date` <= ".time()." AND `end_date`='0') OR (`start_date` <= ".time()." AND `end_date`> ".time()."))";
	}
	
	$current_page = Input::get('page',1);
	$per_page  = 20;
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
//	$clsQuiz->setDeBUg(1);
	$total_record = $clsQuiz->countItem($cond);
//	$clsISO->print_pre($total_record);die;
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> str_replace(".html","/",$clsISO->getLink("request_ptg"))
	);
	
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(1,$str_url);
	$assign_list["html_pager"] = $html_pager;
	
	$order_by = " ORDER BY `reg_date` DESC";
	$lstQuiz = $clsQuiz->getAll($cond .$order_by.$limitCond);
	foreach ($lstQuiz as $key => $val) {
		$total_question = 0;
		$more_information = $clsISO->to_array_json($val['more_information']);
		$lstQuiz[$key]['more_information'] = $more_information;
		$lstGroup = $clsISO->to_array_json($val['questions']);
		foreach ($lstGroup as $k_g => $v_g) {
			$total_question += (!empty($v_g['questions']) ? count($v_g['questions']) : 0);
		}
		$lstQuiz[$key]['questions'] = $lstGroup;
		$lstQuiz[$key]['total_question'] = $total_question;
		if($val['is_all_staff'] == 2) {
			$arr_profile = [];
			#phòng ban
			$arr_department_ids = !empty($val['list_department_id']) ? $clsISO->getArrayByTextSlash($val['list_department_id']) : array();
			$arr_department = [];
			$cond_profile = "";
			foreach($arr_department_ids as $department){
				if(!isset($arr_cache['department'][$department])){
					$arr_cache['department'][$department] = $clsProperty->getTitle($department);
				}
				$arr_department[] = $arr_cache['department'][$department];
				
				$cond_profile .= (($cond_profile != "")?" OR ":"")." department_id='{$department}' OR list_department_id like '%|{$department}|%' " ;
			}
			$lstQuiz[$key]['department'] = implode(", ",$arr_department);	
			#nhân viên
			$arr_profile_id = !empty($val['list_profile_id']) ? $clsISO->getArrayByTextSlash($val['list_profile_id']) : array();
			$arr_profile = [];
			foreach($arr_profile_id as $profile){
				if(!isset($arr_cache['profile'][$profile])){
					$arr_cache['profile'][$profile] = $clsProfile->getFullName($profile);
				}
				$arr_profile[] = $arr_cache['profile'][$profile];
				$cond_profile .= " OR profile_id='{$profile}' ";
			}
			$cond_profile  = ($cond_profile != "")?"and (".$cond_profile.")":"";
			$lstQuiz[$key]['profile'] = implode(", ",$arr_profile);
		}else if($val['is_all_staff'] == 3){			
			#nhóm nhân viên
			$arr_group_profile_id = !empty($val['list_group_profile_id']) ? $clsISO->getArrayByTextSlash($val['list_group_profile_id']) : array();
			if(!empty($arr_group_profile_id)) {
				$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 and group_profile_id IN (".implode(',',$arr_group_profile_id).")");
				$arr_profile_group = $arr_title_group = [];
				foreach ($lstGroupProfile as $kgp => $vgp) {
					$arr_profile_group = array_merge($arr_profile_group,$clsISO->getArrayByTextSlash($vgp['list_profile_id']));
					$arr_title_group[] = $vgp['title'];
				}
				$arr_profile_group = array_unique($arr_profile_group);
				if(!empty($arr_profile_group)){					
					$lstQuiz[$key]['group_profile'] = implode(", ",$arr_title_group);
					$cond_profile .= " AND profile_id IN (".implode(',',$arr_profile_group).") ";
				}
			}
		}
		
//		$clsProfile->setDeBug(1);
		$total_profile = $clsProfile->countItem("`status_id`<>'"._STATUS_STAFF_OFF_ID."' ".$cond_profile);
//		echo $total_profile;die;
		$lstQuiz[$key]['total_profile'] = $total_profile;
		
		#trạng thái
		$do_again = $is_view = $quiz_time_start = 0;
		$is_quiz = 1;
		$status_text = "Bắt đầu kiểm tra";
		$class_button = "bg-info";
//		$clsQuizAnswers->setDeBug(1);
		$quizAnswers = $clsQuizAnswers->getByCond("`quiz_id`='{$val['quiz_id']}' AND `user_id`='{$profile_id}' and `is_trash`='0'");
//		$clsISO->print_pre($quizAnswers);die;
		
		if((time() < $val['end_date'] && $val['start_date'] == 0) || (time() >= $val['start_date'] && $val['end_date'] == 0) || ((time() >= $val['start_date'] && time() < $val['end_date']))) {
			if(!empty($quizAnswers)) {
				if($quizAnswers['is_completed'] == 1) {
					$is_view = 1;
					if(empty($val['submission_count']) || $quizAnswers['is_scored'] == 1 || (strtotime("+".$val['duration']." minutes",$quizAnswers['time_start']) <= time() )) {
						$is_quiz = 0;
						$class_button = "bg-success";
						$status_text = "Đã làm bài";
					}else {
						$do_again = 1;
						$class_button = "bg-warning";
						$status_text = "Làm lại";
					}
				}else if($val['duration'] > 0) {
					if(strtotime("+".$val['duration']." minutes",$quizAnswers['time_start']) <= time() ){
						$is_quiz = 0;
						$is_view = 1;
						$status_text = "Hết giờ làm bài";
						$class_button = "bg-black";
						$clsQuizAnswers->updateByCond("`quiz_id`='{$val['quiz_id']}' AND `user_id`='{$profile_id}' and `is_trash`='0' and `is_completed`='0'","`is_completed`='1', `time_completed`='".time()."',`elapsed_time`='".time()."'");
					}else{
						$status_text = "Tiếp tục làm bài";
						$class_button = "bg-warning";
					}
				}
			}
		}else{
			$is_quiz = 0;
			if(time() < $val['start_date']){
				$quiz_time_start = ($val['start_date'] - time())/60;
				$status_text = "Chưa bắt đầu";
				$class_button = "bg-dark";
			}else{
				if($quizAnswers['is_completed'] == 1) {
					$is_view = 1;					
				}
				if(!empty($quizAnswers) && $quizAnswers['is_completed'] == 1) {
					$class_button = "bg-success";
					$status_text = "Đã làm bài";
				}else{
					$status_text = "Đã kết thúc";
					$class_button = "bg-black";
				}				
			}
		}
//		echo $status_text;die;
		$lstQuiz[$key]['quiz_time_start'] = $quiz_time_start;
		$lstQuiz[$key]['do_again'] = $do_again;
		$lstQuiz[$key]['is_quiz'] = $is_quiz;
		$lstQuiz[$key]['is_view'] = $is_view;
		$lstQuiz[$key]['status_text'] = $status_text;
		$lstQuiz[$key]['class_button'] = $class_button;
	}
//	$clsISO->print_pre($lstQuiz);die;
	$assign_list["show"] = $show;
	$assign_list["clsQuiz"] = $clsQuiz;
	$assign_list["lstQuiz"] = $lstQuiz;
	
	$html = $core->build("_ajax.loadMyQuiz.tpl");
	
    echo json_encode([
		"html"			=>	$html,
		"total_record"	=>	$total_record,
		"per_page"	=>	$per_page,
		"current_page"	=>	$current_page,
	]);
}
function default_begin_test(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	
	$quiz_id = (int)Input::post("quiz_id",0);
	$action = Input::post("action","quiz");
	
	$oneItem = $questions = $more_information = array();
	$oneItem = $clsQuiz->getOne($quiz_id);
	$questions = $clsISO->to_array_json($oneItem['questions']);	
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
//	$clsISO->print_pre($questions);die;
	$total_question = 0;
	foreach ($questions as $k_g => $v_g) {
		$total_question += (!empty($v_g['questions']) ? count($v_g['questions']) : 0);
	}	
	
	if($oneItem["quiz_type"] == "training" && !empty($oneItem["quiz_type_id"])) {
		$clsTraining = new Training();
		$oneTrainng = $clsTraining->getOne($oneItem["quiz_type_id"]);
		$lstLesson = $clsISO->to_array_json($oneTrainng['lesson']);
		$assign_list['training_id'] = $oneItem["quiz_type_id"];
		$assign_list['lstLesson'] = $lstLesson;
	}
	
	$cond = "`user_id` = '{$profile_id}' AND `quiz_id`='{$quiz_id}' AND `is_trash`=0";
	$answersResult = $clsQuizAnswers->getByCond($cond);
//	var_dump($answersResult);die;
	$result = $clsISO->to_array_json($answersResult['results']);
	$correct_answers = $clsISO->to_array_json($answersResult["correct_answers"]);
	if(empty($answersResult)) {
		$quiz_answers_id = $clsQuizAnswers->getMaxID();
		$time_start = time();
		if($oneItem['duration'] > 0) {
			if(!empty($more_information["is_end_date"])) {
				$time_end = (strtotime("+ ".$oneItem['duration']."minutes") < $oneItem['end_date']) ? strtotime("+ ".$oneItem['duration']."minutes") : $oneItem['end_date'];				
			}else{
				$time_end = strtotime("+ ".$oneItem['duration']."minutes");
			}
		}else{
			$time_end = 0;
		}
		
		$answersResult = array(
			"id"	=>	$quiz_answers_id,
			"quiz_id"	=>	$quiz_id,
			"user_id"	=>	$profile_id,
			"time_start"	=>	$time_start,
			"time_end"	=>	$time_end,
			"elapsed_time"	=>	0,
			"results"	=>	json_encode(array()),
			"score"	=>	0,
			"questions"	=>	$oneItem['questions'],
			"is_completed"	=>	0,	
		);
		$clsQuizAnswers->insert($answersResult);		
	}else{
		$time_start = $answersResult["time_start"];
		if(!empty($more_information["is_end_date"]) || $oneItem['duration'] > 0) {
			if($oneItem['duration'] > 0) {
				$time_end = (strtotime("+ ".$oneItem['duration']."minutes",$answersResult['time_start']) > time()) ? strtotime("+ ".$oneItem['duration']."minutes",$answersResult['time_start']) : $oneItem['end_date'];
			}else{
				$time_end = ($time_start < $oneItem['end_date']) ? strtotime("+ ".$oneItem['duration']."minutes") : $oneItem['end_date'];	
			}
		}else{
			$time_end = 0;
		}
	}
	foreach ($questions as $key => $val) {
		$lstQuestions = $val["questions"];
		foreach ($lstQuestions as $k_q => $v_q) {
			$check_result = 0;
			if($v_q['question_type'] == 3) {
				$lstQuestions[$k_q]['result'] = $result[$k_q];
				if(isset($correct_answers[$k_q])) {
					$lstQuestions[$k_q]['is_true'] = $correct_answers[$k_q]["is_true"];
					$lstQuestions[$k_q]['score'] = $correct_answers[$k_q]["score"];
				}
				
			}else{	
				$lstAnswer = $v_q['answer_options'];				
				$check_true = $total_true = $total_core = 0;
				foreach ($lstAnswer as $k_aws => $v_aws) {						
					if(!empty($v_aws['is_correct'])) {
						++$total_true;
					}
					if($v_q['question_type'] == 1){
						if($result[$k_q] == $k_aws) { //đáp án chọn
							$lstAnswer[$k_aws]['result'] = 1;
							if(!empty($v_aws['is_correct'])) { // chọn đúng
								$lstAnswer[$k_aws]['is_true'] = 1;
								++$check_true;
							}else{// chọn sai
								$lstAnswer[$k_aws]['is_true'] = 0;
							}
						}else{
							$lstAnswer[$k_aws]['result'] = 0;
						}
					}else{
						if($clsISO->checkItemInArray($k_aws,$result[$k_q])) {
							$lstAnswer[$k_aws]['result'] = 1;
							if(!empty($v_aws['is_correct'])) {
								$lstAnswer[$k_aws]['is_true'] = 1;
								++$check_true;
							}else{
								$lstAnswer[$k_aws]['is_true'] = 0;
							}
						}else{
							$lstAnswer[$k_aws]['result'] = 0;
						}
					}					
	//				
				}
				if($check_true == $total_true) {
					$lstQuestions[$k_q]['is_true'] = 1;
					$total_core += $lstQuestions[$k_q]['score'];
				}else{
					$lstQuestions[$k_q]['is_true'] = 0;
				}
				
				$lstQuestions[$k_q]['answer_options'] = $lstAnswer;
			}
		}
		$questions[$key]["questions"] = $lstQuestions;
	}
	$return_url = $clsQuiz->getLink($quiz_id,$oneItem);
	
	$assign_list["result"] = $result;
	$assign_list["str_time_start"] = $time_start;
	$assign_list["str_time_end"] = $time_end;
	$second = ($time_end > time()) ? ($time_end - time())/60 : 0;
	$time_end = (!empty($time_end)) ? $clsISO->convertTimeToTextFormat($time_end,"H:i d/m/Y") : 0;
	$time_start = $clsISO->convertTimeToTextFormat($time_start,"H:i d/m/Y");	
	$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	$assign_list["clsQuiz"] = $clsQuiz;		
	$assign_list["oneItem"] = $oneItem;		
	$assign_list["questions"] = $questions;	
	$assign_list["total_question"] = $total_question;	
	$assign_list["more_information"] = $more_information;	
	$assign_list["time_start"] = $time_start;	
	$assign_list["time_end"] = $time_end;	
	
	$assign_list["quiz_id"] = $quiz_id;	
	$assign_list["group_id"] = $group_id;
	$assign_list["question_id"] = $question_id;
	$assign_list["answer_options"] = $answer_options;
	$assign_list["alphabet"] = $alphabet;
	$assign_list["second"] = $second;
	$assign_list["answersResult"] = $answersResult;
	$assign_list["total_core"] = $total_core;
	$uid = $clsISO->getUniqid();
	$assign_list["uid"] = $uid;
	$html = $core->build("_ajax.begin_test.tpl");
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$uid,
		"second"	=>	$second,
		"return_url"	=>	$return_url,
	]);die;
}
function default_view_quiz(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	
	$quiz_id = (int)Input::post("quiz_id",0);
	$action = Input::post("action","quiz");
	
	$oneItem = $clsQuiz->getOne($quiz_id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$total_question = 0;
	foreach ($questions as $k_g => $v_g) {
		$total_question += (!empty($v_g['questions']) ? count($v_g['questions']) : 0);
	}	
	
	$cond = "`user_id` = '{$profile_id}' AND `quiz_id`='{$quiz_id}' AND `is_trash`=0";
	if($oneItem['submission_count'] == 0 || empty($oneItem['submission_count'])) {
//		$cond .= " AND `is_completed`='0'";
	}
	$answersResult = $clsQuizAnswers->getByCond($cond);
	$questions = $clsISO->to_array_json($answersResult['questions']);	
//	var_dump($answersResult);die;
	$result = $clsISO->to_array_json($answersResult['results']);
	$correct_answers = $clsISO->to_array_json($answersResult["correct_answers"]);
	$time_start = $answersResult["time_start"];
	$time_end = $answersResult["time_completed"];
	
	foreach ($questions as $key => $val) {
		$lstQuestions = $val["questions"];
		foreach ($lstQuestions as $k_q => $v_q) {
			$check_result = 0;
			if($v_q['question_type'] == 3) {
				$lstQuestions[$k_q]['result'] = $result[$k_q];
				if(isset($correct_answers[$k_q])) {
					$lstQuestions[$k_q]['is_true'] = $correct_answers[$k_q]["is_true"];
					$lstQuestions[$k_q]['score'] = $correct_answers[$k_q]["score"];
				}
				
			}else{	
				$lstAnswer = $v_q['answer_options'];				
				$check_true = $total_true = $total_core = 0;
				foreach ($lstAnswer as $k_aws => $v_aws) {						
					if(!empty($v_aws['is_correct'])) {
						++$total_true;
					}
					if($v_q['question_type'] == 1){
						if($result[$k_q] == $k_aws) { //đáp án chọn
							$lstAnswer[$k_aws]['result'] = 1;
							if(!empty($v_aws['is_correct'])) { // chọn đúng
								$lstAnswer[$k_aws]['is_true'] = 1;
								++$check_true;
							}else{// chọn sai
								$lstAnswer[$k_aws]['is_true'] = 0;
							}
						}else{
							$lstAnswer[$k_aws]['result'] = 0;
						}
					}else{
						if($clsISO->checkItemInArray($k_aws,$result[$k_q])) {
							$lstAnswer[$k_aws]['result'] = 1;
							if(!empty($v_aws['is_correct'])) {
								$lstAnswer[$k_aws]['is_true'] = 1;
								++$check_true;
							}else{
								$lstAnswer[$k_aws]['is_true'] = 0;
							}
						}else{
							$lstAnswer[$k_aws]['result'] = 0;
						}
					}					
	//				
				}
				if($check_true == $total_true) {
					$lstQuestions[$k_q]['is_true'] = 1;
					$total_core += $lstQuestions[$k_q]['score'];
				}else{
					$lstQuestions[$k_q]['is_true'] = 0;
				}
				
				$lstQuestions[$k_q]['answer_options'] = $lstAnswer;
			}
		}
		$questions[$key]["questions"] = $lstQuestions;
	}
//	$clsISO->print_pre($questions);die;
	$assign_list["result"] = $result;
	$assign_list["str_time_start"] = $time_start;
	$assign_list["str_time_end"] = $time_end;
	$second = ($time_end > time()) ? ($time_end - time())/60 : 0;
//	echo $second;die;
//	var_dump($time_end,$time_start);die;
//	$second = 5000;//fake
	$time_end = (!empty($time_end)) ? $clsISO->convertTimeToTextFormat($time_end,"H:i d/m/Y") : 0;
//	echo $time_end;die;
	$time_start = $clsISO->convertTimeToTextFormat($time_start,"H:i d/m/Y");	
	$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	$assign_list["oneItem"] = $oneItem;		
	$assign_list["questions"] = $questions;	
	$assign_list["total_question"] = $total_question;	
	$assign_list["more_information"] = $more_information;	
	$assign_list["time_start"] = $time_start;	
	$assign_list["time_end"] = $time_end;	
	
	$assign_list["quiz_id"] = $quiz_id;	
	$assign_list["group_id"] = $group_id;
	$assign_list["question_id"] = $question_id;
	$assign_list["answer_options"] = $answer_options;
	$assign_list["is_split_points"] = $is_split_points;
	$assign_list["alphabet"] = $alphabet;
	$assign_list["second"] = $second;
	$assign_list["answersResult"] = $answersResult;
	$assign_list["total_core"] = $total_core;
	$uid = $clsISO->getUniqid();
	$assign_list["uid"] = $uid;
	$html = $core->build("_ajax.view_quiz.tpl");
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$uid,
		"second"	=>	$second,
	]);die;
}
function default_sendResult(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	$res = ["result" => false,"msg"	=>	"ERROR"];
	$quiz_id = (int)Input::post("quiz_id",0);
	$result = Input::post("result",array());
	$time_start = (int)Input::post("time_start",0);
	$time_end = (int)Input::post("time_end",0);
	$type = Input::post("type","publish");
	$elapsed_time = time();
	$oneItem = $questions = $more_information = array();
	$oneItem = $clsQuiz->getOne($quiz_id);
	$questions = $clsISO->to_array_json($oneItem['questions']);	
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	
	$cond = "`user_id` = '{$profile_id}' AND `quiz_id`='{$quiz_id}' AND `is_trash`='0'";
	if($oneItem['submission_count'] == 0 || empty($oneItem['submission_count'])) {
		$cond .= " AND `is_completed`='0'";
	}
	$is_scored = 0;
	$quiz_answers = $clsQuizAnswers->getByCond($cond);
	if($type == "draft") {
		$is_completed = 0;
		$msg = "Bài thi đã được lưu";
	}else{
		$is_completed = 1;
		$msg = "Nộp bài thành công";
		if(time() < $oneItem['time_end']) {
			$is_scored = 1;
		}
	}	
	$correct_answers = array();
	$total_core = $number_correct = 0;
	foreach ($questions as $key => $val) {
		$lstQuestions = $val["questions"];
		foreach ($lstQuestions as $k_q => $v_q) {
			$check_result = 0;
			$lstAnswer = $v_q['answer_options'];				
			$check_true = $total_true = 0;
			foreach ($lstAnswer as $k_aws => $v_aws) {						
				if(!empty($v_aws['is_correct'])) {
					++$total_true;
				}
				if($v_q['question_type'] == 1 && $result[$k_q] == $k_aws && !empty($v_aws['is_correct'])){
					++$check_true;
				}else if($clsISO->checkItemInArray($k_aws,$result[$k_q]) && !empty($v_aws['is_correct'])) {
					++$check_true;
				}	
			}
			if($check_true == $total_true) {
				$correct_answers[$k_q] = [
					"is_true"	=>	1,
					"score"	=>	$lstQuestions[$k_q]['score'],
				];
				$total_core += $lstQuestions[$k_q]['score'];
				++$number_correct;
			}else{
				$correct_answers[$k_q] = [
					"is_true"	=>	0,
					"score"	=>	0,
				];
			}
		}
	}
	
	if(!empty($quiz_answers)) {
		$quiz_answers_id = $quiz_answers["id"];
		$data = array(
			"elapsed_time"		=>	time(),
			"questions"			=>	$oneItem['questions'],
			"results"			=>	json_encode($result,JSON_UNESCAPED_UNICODE),
			"correct_answers"	=>	json_encode($correct_answers,JSON_UNESCAPED_UNICODE),
			"score"				=>	$total_core,
			"number_correct"	=>	$number_correct,
		);
		if($type != "draft") {
			$data["is_completed"] = $is_completed;
			$data["is_scored"] = $is_scored;
			$data["time_completed"] = time();
		}
//		$clsQuizAnswers->setDeBug(1);
//		$clsQuizAnswers->updateOne($quiz_answers_id,$data);die;
		if($clsQuizAnswers->updateOne($quiz_answers_id,$data)) {
			$res = ["result" => true,"msg"	=>	$msg];
		}
	}
	
	echo json_encode($res);die;
}
function default_cancelPublicQuiz(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	$res = ["result" => false,"msg"	=>	"ERROR"];
	$quiz_id = (int)Input::post("quiz_id",0);
//	$clsQuiz->setDeBug(1);
	if($clsQuiz->updateOne($quiz_id,["is_online" => 0])) {
		$clsQuizAnswers->updateByCond("`quiz_id`='{$quiz_id}' AND `is_trash`='0'","`is_trash`='1'");
		$res = ["result" => true,"msg"	=>	"Thành công!"];
	}	
	echo json_encode($res);die;
}

function default_list_question_answer(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$clsQuizAnswers = new QuizAnswers();
	$clsProfile = new Profile();
	$smarty->assign('clsQuiz', $clsQuiz);
	$smarty->assign('clsQuizAnswers', $clsQuizAnswers);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$quiz_id = (int)Input::post("quiz_id",0);
	
	$oneQuiz = $clsQuiz->getOne($quiz_id);
	$questions = $clsISO->to_array_json($oneQuiz['questions']);	
	
	$cond = "`quiz_id`='{$quiz_id}' AND `is_completed`='1' AND `is_trash`='0'";
	$lstItem = $clsQuizAnswers->getAll($cond);
	$arr_cache_profile = array();
	$is_gradeExam_all = 0;
	foreach ($lstItem as $key => $val) {
		if(!isset($arr_cache_profile[$val['user_id']])) {
			$arr_cache_profile[$val['user_id']] = $clsProfile->getOne($val['user_id']);
		}
		$lstItem[$key]['profile'] = $arr_cache_profile[$val['user_id']];
		
		$result = !empty($val['results']) ? $clsISO->to_array_json($val['results']) : array();
		$lstItem[$key]['total_answered'] = count($result);
		if(empty($is_gradeExam_all) && empty($val['is_scored'])) {
			$is_gradeExam_all = 1;
		}
		if(empty($val["time_completed"])) {
			$lstItem[$key]["time_completed"] = $val["time_end"];
			$clsISO->print_pre($val);die;
		}
	}
	
	
	$uid = $clsISO->getUniqid();
	$smarty->assign('quiz_id', $quiz_id);
	$smarty->assign('question_id', $question_id);
	$smarty->assign('lstItem', $lstItem);
	$smarty->assign('is_gradeExam_all', $is_gradeExam_all);
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.list_question_answer.tpl');
	// Return
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$uid
	]); die();
}
function default_gradeExam(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$clsQuizAnswers = new QuizAnswers();
	$clsProfile = new Profile();
	$smarty->assign('clsQuiz', $clsQuiz);
	$smarty->assign('clsQuizAnswers', $clsQuizAnswers);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$quiz_id = (int)Input::post("quiz_id",0);
	$quiz_answer_id = (int)Input::post("quiz_answer_id",0);
	$action = Input::post("action","_ONE");
	
	
	$res = ["result" => false];
	$quiz_answer = array();
	if($action == "_ONE") {
		$oneQuizAnsers = $clsQuizAnswers->getOne($quiz_answer_id);
		if($clsQuizAnswers->updateOne($quiz_answer_id,["is_scored"=>'1'])) {
			$quiz_answer[] = [
				"quiz_answers_id"	=>	$quiz_answer_id,
				"number_correct"	=>	$oneQuizAnsers['number_correct'],
				"score"	=>	$oneQuizAnsers['score']
			];
			$res = ["result" => true,"quiz_answer"=>$quiz_answer];
		}
	}else{
		$cond = "`is_completed`='1' AND `is_scored`='0' AND `quiz_id`='{$quiz_id}'";
		$lstQuizAnser = $clsQuizAnswers->getAll($cond);
		if(!empty($lstQuizAnser)) {
			foreach ($lstQuizAnser as $key => $val) {
				$quiz_answer[] = [
					"quiz_answers_id"	=>	$val["id"],
					"number_correct"	=>	$val['number_correct'],
					"score"	=>	$val['score']
				];
			}
			$clsQuizAnswers->updateByCond($cond,"`is_scored`='1'");
			$res = ["result" => true,"quiz_answer"=>$quiz_answer];
		}
	}
	
	// Return
	echo json_encode($res); die();
}
function default_grading_answers(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsQuiz = new Quiz(); 
	$clsQuizAnswers = new QuizAnswers(); 
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	
	$quiz_id = (int)Input::post("quiz_id",0);
	$quiz_answer_id = (int)Input::post("quiz_answer_id",0);
	
	$oneItem = $questions = $more_information = array();
	$oneItem = $clsQuiz->getOne($quiz_id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
//	$clsISO->print_pre($questions);die;
	$total_question = 0;
	foreach ($questions as $k_g => $v_g) {
		$total_question += (!empty($v_g['questions']) ? count($v_g['questions']) : 0);
	}	
	
	/*$cond = "`user_id` = '{$profile_id}' AND `quiz_id`='{$quiz_id}' AND `is_trash`='0'";
	if($oneItem['submission_count'] == 0 || empty($oneItem['submission_count'])) {
//		$cond .= " AND `is_completed`='0'";
	}*/
	$answersResult = $clsQuizAnswers->getOne($quiz_answer_id);
	$questions = $clsISO->to_array_json($answersResult['questions']);	
	$result = $clsISO->to_array_json($answersResult['results']);
	$correct_answers = $clsISO->to_array_json($answersResult["correct_answers"]);
//	$clsISO->print_pre($answersResult);die;
	$time_start = $answersResult["time_start"];
	if(!empty($more_information["is_end_date"])) {
		$time_end = (strtotime("+ ".$oneItem['duration']."minutes",$time_start) < $oneItem['end_date']) ? strtotime("+ ".$oneItem['duration']."minutes",$time_start) : $oneItem['end_date'];
	}else{
		$time_end = 0;
	}
	foreach ($questions as $key => $val) {
		$lstQuestions = $val["questions"];
		foreach ($lstQuestions as $k_q => $v_q) {
//			var_dump($v_q,$result,$lstAnswer);die;
			$check_result = 0;
			if($v_q['question_type'] == 3) {
				$lstQuestions[$k_q]['result'] = $result[$k_q];
				if(isset($correct_answers[$k_q])) {
					$lstQuestions[$k_q]['is_true'] = $correct_answers[$k_q]["is_true"];
					$lstQuestions[$k_q]['score'] = $correct_answers[$k_q]["score"];
				}
			}else{	
				$lstAnswer = $v_q['answer_options'];				
				$check_true = $total_true = $total_core = 0;
				foreach ($lstAnswer as $k_aws => $v_aws) {						
					if(!empty($v_aws['is_correct'])) {
						++$total_true;
					}
					if($v_q['question_type'] == 1){
						if($result[$k_q] == $k_aws) { //đáp án chọn
							$lstAnswer[$k_aws]['result'] = 1;
							if(!empty($v_aws['is_correct'])) { // chọn đúng
								$lstAnswer[$k_aws]['is_true'] = 1;
								++$check_true;
							}else{// chọn sai
								$lstAnswer[$k_aws]['is_true'] = 0;
							}
						}else{
							$lstAnswer[$k_aws]['result'] = 0;
						}
					}else{
						if($clsISO->checkItemInArray($k_aws,$result[$k_q])) {
							$lstAnswer[$k_aws]['result'] = 1;
							if(!empty($v_aws['is_correct'])) {
								$lstAnswer[$k_aws]['is_true'] = 1;
								++$check_true;
							}else{
								$lstAnswer[$k_aws]['is_true'] = 0;
							}
						}else{
							$lstAnswer[$k_aws]['result'] = 0;
						}
					}					
	//				
				}
				if($check_true == $total_true) {
					$lstQuestions[$k_q]['is_true'] = 1;
					$total_core += $lstQuestions[$k_q]['score'];
				}else{
					$lstQuestions[$k_q]['is_true'] = 0;
				}
				
				$lstQuestions[$k_q]['answer_options'] = $lstAnswer;
			}
		}
		$questions[$key]["questions"] = $lstQuestions;
	}
	$assign_list["result"] = $result;
	$assign_list["str_time_start"] = $time_start;
	$assign_list["str_time_end"] = $time_end;
	$second = ($time_end > $time_start) ? ($time_end - $time_start) : 0;
//	var_dump($time_end,$time_start);die;
//	$second = 5000;//fake
	$time_end = (!empty($time_end)) ? $clsISO->convertTimeToTextFormat($time_end,"H:i d/m/Y") : 0;
	$time_start = $clsISO->convertTimeToTextFormat($time_start,"H:i d/m/Y");	
	$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	$assign_list["oneItem"] = $oneItem;		
	$assign_list["questions"] = $questions;	
	$assign_list["total_question"] = $total_question;	
	$assign_list["more_information"] = $more_information;	
	$assign_list["time_start"] = $time_start;	
	$assign_list["time_end"] = $time_end;	
	
	$assign_list["quiz_id"] = $quiz_id;	
	$assign_list["quiz_answer_id"] = $quiz_answer_id;	
	$assign_list["group_id"] = $group_id;
	$assign_list["question_id"] = $question_id;
	$assign_list["answer_options"] = $answer_options;
	$assign_list["is_split_points"] = $is_split_points;
	$assign_list["alphabet"] = $alphabet;
	$assign_list["second"] = $second;
	$assign_list["answersResult"] = $answersResult;
	$assign_list["total_core"] = $total_core;
	$uid = $clsISO->getUniqid();
	$assign_list["uid"] = $uid;
	$html = $core->build("_ajax.exam_grading.tpl");
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$uid,
		"second"	=>	$second,
	]);die;
}
function default_sendGrading(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$clsQuizAnswers = new QuizAnswers();
	$clsProfile = new Profile();
	$smarty->assign('clsQuiz', $clsQuiz);
	$smarty->assign('clsQuizAnswers', $clsQuizAnswers);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$quiz_answer_id = (int)Input::post("quiz_answer_id",0);
	$correct_result = Input::post("correct_result",array());
	$total_correct = Input::post("total_correct",0);
	$result_score = Input::post("result_score",0);
	
	$res = ["result" => false,"msg"	=>	"Lỗi"];
	$oneQuizAnswers = $clsQuizAnswers->getOne($quiz_answer_id);
//	$clsISO->print_pre($oneQuizAnswers);die;
	if(!empty($oneQuizAnswers)) {
		$logs = [
			'user_id'			=>	$profile_id,
			'time'				=> time(),
			'correct_answers'	=>	$correct_result,
			'score'				=>	$result_score,
			'total_correct'		=>	$total_correct
		];
		$upd_data = [
			"correct_answers"	=>	json_encode($correct_result),
			"is_scored"			=>	1,
			'score'				=>	$result_score,
			'number_correct'	=>	$total_correct,
			"logs"				=>	json_encode($logs),
		];
		if($clsQuizAnswers->updateOne($quiz_answer_id,$upd_data)) {
			$res = ["result" => true,"msg"	=>	"Xác nhận điểm thành công"];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_import_file(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$msg = "error";
	$res = ["result" => false, "msg"	=>	"error"];
	if(isset($_FILES) && $_FILES['upload_excel']['tmp_name'] != ""){	
		if(is_uploaded_file($_FILES['upload_excel']['tmp_name'])){
			$target_dir = ROOTPATH."/tmp/";
			$file_ext = explode('.',basename($_FILES["upload_excel"]["name"]));
			$file_ext = strtolower(end($file_ext));
			$target_file = $target_dir . time().'.'.$file_ext;
			if (move_uploaded_file($_FILES["upload_excel"]["tmp_name"], $target_file)) {
				$html = '';
				$inputFileName = $target_file;
				require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
				require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
				} catch(Exception $e) {
					die($e->getMessage());
				}
				$worksheet = $objPHPExcel->getActiveSheet();
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow();
				$highestColumn      = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				###
				$index = 0; $tblData =array();
				for($row = 1; $row <= $highestRow; ++ $row) {
					$rowData = $worksheet->rangeToArray('A'. $row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
					if(isEmptyRow(reset($rowData))) { continue; } // skip empty row
					for($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						if(PHPExcel_Shared_Date::isDateTime($cell)){
							if(!empty($cell)){
								$date = trim($cell->getValue());
								$date = PHPExcel_Shared_Date::ExcelToPHPObject();
								$tblData[$index][] = date_format($date,'d/m/Y');
							}else{
								$tblData[$index][] = trim($cell->getValue());
							}
						}else{
							$tblData[$index][] = trim($cell->getValue());
						}
					}
					++$index;
				}
				@unlink($inputFileName);
				$html = '<div class="modal-dialog modal-xl mh-100 overflow-hidden">
					<div class="modal-content mh-100">
						<div class="modal-header"> 
							<h3 class="modal-title"><strong>Import câu hỏi</strong></h3>
							<button class="btn btn-icon btn-outline-default" data-bs-dismiss="modal" aria-label="Close" type="button"><i class="bx bx-x"></i></button>
						</div>
						<form method="POST">
							<div class="modal-body overflow-y-auto" style="max-height: calc(100vh - 200px)">
								<div class="table-container no-shadow overflow-x-auto">
									<table class="table" width="100%" cellpadding="0" cellspacing="0">';
										$row = 0;
										if(!empty($tblData)){ $ii = 0; // Init
											$cachedName = sprintf('quiz_excel_%s.json', $uid);
											$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
											$encoder = new Webmozart\Json\JsonEncoder();
											$encoder->encodeFile($tblData, $cachedFile); 

											foreach($tblData as $key => $val){
												$html.= '<tr>';
													for($col=0; $col<$highestColumnIndex; $col++){
														$html.= '<td class="text-left'.(($ii==0)?" text-nowrap":"").'" '.(($col==1)?"  width='100px'":"").'><span class="limit_2line">'.$val[$col].'</span></td>';
													}
												$html.= '</tr>';
												++$ii;
											}
										}
							$html .= '</table> 
								</div>
							</div>
							<div class="modal-footer">
								<div class="d-flex align-items-center justify-content-between">
									<div class="d-flex">
										<button type="button" class="btn btn-default mr-2" data-bs-dismiss="modal">Đóng</button>
										<button type="button" class="btn btn-success" uid="'.$uid.'" onClick="$Core.quiz.addQuestionExcel(this, event)"><span>Xác nhận</span></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>';
				// Return
				$res = ["result" => true,"uid"=>$uid, "html"	=>	$html,"cachedFile"=>$cachedFile];
			}
		}
	}
	echo json_encode($res);die;
}
function isEmptyRow($row) {
    foreach($row as $cell){
        if (null !== $cell) return false;
    }
    return true;
}
function default_addQuestionExcel(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsQuiz = new Quiz();
	$smarty->assign('clsQuiz', $clsQuiz);
	###
	$group_id = Input::post("group_id","");
	$gId = Input::post("gId","");
	$number_question = Input::post("number_question","1");
	$is_split_points = 1;
	$question_type = 1;
	$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$tblData = array();
	$cachedName = sprintf('quiz_excel_%s.json', $gId);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	$groups = $questions = [];
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
		
//		 $clsISO->print_pre($tblData); die();
		if(!empty($tblData)) {
			foreach ($tblData as $key => $val) {
				if($key > 0 && trim($val[2]) != "") {						
					$question_id = $clsISO->getUniqid();
					$answer_id = $clsISO->getUniqid();
					$correct = str_replace(" ","",$val[3]);
					$correct = explode(",",$correct);
					if(count($correct) > 1) {
						$question_type = 2;
					}else{
						$question_type = 1;
					}		
					$answer_options = array();
//					$clsISO->print_pre($val);die;
					for($i=4; $i <= count($val); $i ++) {
						if(trim($val[$i]) != "") {
							$answer_id = $clsISO->getUniqid();
							$key_answ = $alphabet[$i-4];							
							$is_correct = in_array($key_answ,$correct) ? 1 : 0;
							$answer_options[$answer_id] = [
								"title"	=>	$val[$i],
								"is_correct"	=>	$is_correct
							];
						}						
					}							
					$groups[$val[1]]["questions"][$question_id] = [
						"title"	=>	$val[2],
						"question_type"	=>	$question_type,
						"answer_options"	=>	$answer_options,		
						"score"		=>	0
					];
				}
			}
		}
//		$clsISO->print_pre($groups);die;
		@unlink($cachedFile);
	}
	$smarty->assign('group_id', $group_id);
	$smarty->assign('groups', $groups);
	$smarty->assign('questions', $questions);
	$smarty->assign('is_export', 1);
	$html = $core->build('_ajax.add_question.tpl');
	// Return
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$gId
	]); die();
}
function default_uploadVideo(){
	global $clsISO;
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['video_file']['name'])) {
		echo '_error'; die();
	}
	$video = $_FILES['video_file'];
	if ($video['size'] > 104857600) { // 100 MB
		echo '_limit_size'; die();
	}
	$clsUpload = new UploadFile();
	$uploaded_url = "";
	if(@is_uploaded_file($_FILES['video_file']['tmp_name'])){
		$clsUploadFile = new UploadFile();
//		$upload_file = $clsUploadFile->uploadItem($_FILES["video_file"],"/QuizTest",EXTENSION_VIDEO_UPLOAD);
		$uploaded_url = "/images/QuizTest/FutureHomes_1777084642video.mp4";
		/*$clsISO->print_pre($upload_file);die;
		if(!empty($upload_file)) {
			$file_name = $_FILES['video_file']['name'];
			$file_size = $_FILES['video_file']['size'];
			// Upload file to google drive
			$folder_id = GOOGLE_DRIVE_FOLDER_VIDEO_ID;
			$clsGoogleUpload = new GoogleUpload($folder_id, true);
			$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
			$uploaded_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
			$uploaded_url = $clsISO->genGoogleURL($createdFile->getId());
			@unlink(ROOTPATH . $upload_file);
		}*/
		
	}
//	$path = $clsUpload->uploadItem($video, '/QuizTest', 'mp4,webm,ogg,mov', array('resize' => false));

	echo (!empty($uploaded_url) && @file_exists(ABSPATH . $uploaded_url))
		? '_success|||' . $uploaded_url
		: '_error';
	die();
}
function default_addCategory(){
	global $core;
	$clsCategory = new QuizTestCategory();
	$title       = trim(Input::post('title', ''));

	if (empty($title)) {
		echo json_encode(array('result' => false));die;
	}

	$cat_id = $clsCategory->getMaxID();
	$clsCategory->insert(array(
		'category_id' => $cat_id,
		'title'       => addslashes($title),
		'slug'        => $core->replaceSpace($title),
		'order_no'    => 0,
		'is_trash'    => 0,
	));
	echo json_encode(array('result' => true, 'category_id' => $cat_id, 'title' => $title));die;
}

function default_open_import(){
	global $core,$adminid,$clsISO,$profile_id,$oneProfile,$clsISO;
	$uid = $clsISO->getUniqid();
	###
	$html= $core->build("_ajax.open_import.tpl");
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));die();
}
function default_configColumn(){
	global $core,$profile_id,$oneProfile,$clsISO,$smarty;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsQuiz = new Quiz();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');		
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	###
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', 'upload');
	$totalInsert = $totalDuplicate = 0;
	if($tp == 'google_sheet'){
		$spreadsheetId = Input::post('spreadsheetId');
		if(!empty($spreadsheetId)){
			if($clsISO->checkContainer($spreadsheetId, "docs.google.com","")){
				@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
				$spreadsheetId = $matches[0];
			}
			#- Require library		
			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
			/** Init Client */
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
			// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
			// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
			// get all the rows of a sheet
			$range = 'QUIZ_TEST'; // here we use the name of the Sheet to get all the rows
			$response = $service->spreadsheets_values->get($spreadsheetId, $range);
			$tblData = $response->getValues();
			// $clsISO->print_pre($tblData); die();
			$select_default = ["stt","question","correct","A","B","C","D"];
			$cachedColumnName = sprintf('column_%s.json', $profile_id);
			$cachedFile = DIR_CACHE_JSON.'/quiz/'.$cachedColumnName;
			if(file_exists($cachedFile)){
				$select_default = $decoder->decodeFile($cachedFile);
			}
			$data_select = $clsQuiz->getDataColumnQuestion();
			$highestColumnIndex = 15;
			$widthColumn = 100/$highestColumnIndex;	
			$smarty->assign("data_select",$data_select);
			$smarty->assign("select_default",$select_default);
			$smarty->assign("widthColumn",$widthColumn);
			$smarty->assign("highestColumnIndex",$highestColumnIndex);
			$smarty->assign("tblData",$tblData);
			// Return
			$html = $core->build("_ajax.configColumn.tpl");
			echo json_encode(array(
				'uid' => $uid,
				'result' =>	true,
				'html' => $html
			)); die();
		}
	} else {
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
			if(@is_uploaded_file($_FILES['fileimport']['tmp_name'])){
				$target_dir = PCMS_DIR."/tmp/";
				$file_ext =explode('.',basename($_FILES["fileimport"]["name"]));
				$file_ext =strtolower(end($file_ext));
				$target_file = $target_dir . time().'.'.$file_ext;
				if (move_uploaded_file($_FILES["fileimport"]["tmp_name"], $target_file)) {
					$html = '';
					$inputFileName = $target_file;
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
					require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel/IOFactory.php";
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch(Exception $e) {
						die($e->getMessage());
					}
					$worksheet = $objPHPExcel->getActiveSheet();
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow();
					$highestColumn      = $worksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					##
					$index = 0; $tblData =array();
					for($row=1; $row <= $highestRow; ++ $row){
						for($col=0; $col < $highestColumnIndex; ++$col){
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$tblData[$index][] = $cell->getValue();
						}
						++$index;
					}
					// Remove file uploaded
					@unlink($inputFileName);
					$select_default = ["stt","question","correct","A","B","C","D"];
					$cachedColumnName = sprintf('column_%s.json', $profile_id);
					$cachedFile = DIR_CACHE_JSON.'/quiz/'.$cachedColumnName;
					if(file_exists($cachedFile)){
						$select_default = $decoder->decodeFile($cachedFile);
					}
					$highestColumnIndex = 15;
					$widthColumn = 100/$highestColumnIndex;	
					$data_select = $clsQuiz->getDataColumnQuestion();
					if(!empty($tblData)){
						$cachedName = sprintf('%s.json', $uid);
						$cachedFile = DIR_CACHE_JSON.'/quiz/'.$cachedName;
						$encoder->encodeFile($tblData, $cachedFile);
					}
					$smarty->assign("uid", $uid);
					$smarty->assign("data_select",$data_select);
					$smarty->assign("select_default",$select_default);
					$smarty->assign("widthColumn",$widthColumn);
					$smarty->assign("highestColumnIndex",$highestColumnIndex);
					$smarty->assign("tblData",$tblData);
					// Return
					$html = $core->build("_ajax.configColumn.tpl");
					echo json_encode(array(
						'uid' => $uid,
						'result' =>	true,
						'html' => $html
					)); die();
				}
			}
		}
	}
	// Return
	$res = array(
		"result"	=>	false,
		'msg' => "Vui lòng upload file excel",
	);
	echo json_encode($res); die();
}
function default_continue_config(){
	global $core,$profile_id,$oneProfile,$clsISO,$smarty;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	###
	$uid = Input::post("uid");
	$columns = Input::post("columns", array());
	// $clsISO->print_pre($columns); die();
	if(!empty($columns)) {
		$error_field = 0; $arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[$key] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
		if(!in_array("question", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột câu hỏi chưa được xác định"
			]); die();
		}
		if(!in_array("correct", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột đáp án đúng chưa được xác định"
			]); die();
		}
		$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
		$arr_answer = array_values(array_intersect($arr_fields,$alphabet));
		if(empty($arr_answer) || count($arr_answer) < 2) {
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Chọn tối thiểu 2 cột phương án lựa chọn"
			]); die();
		}
		if($error_field > 0){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Các cột dữ liệu không được trùng nhau"
			]); die();
		}
		$cachedColumnName = sprintf('column_%s.json', $profile_id);
		$cachedFile = DIR_CACHE_JSON.'/quiz/'.$cachedColumnName;
		$encoder = new Webmozart\Json\JsonEncoder();
		// $clsISO->print_pre($arr_fields); die();
		$encoder->encodeFile($arr_fields, $cachedFile);
		$res = array(
			"result"	=>	true,
			'msg' => "Cài đặt thành công",
		);
	}else{
		$res = array(
			"result"	=>	false,
			'msg' => "Có lỗi xảy ra. Xin vui lòng thử lại!",
		);
	}
	// Return	
	echo json_encode($res); die();
}
function default_do_import(){
	global $core,$profile_id,$oneProfile,$clsISO,$dbconn,$smarty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$clsCustomerHistory = new CustomerHistory();
	$clsFollowUp = new FollowUp();	
	$clsZalo = new Zalo();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');		
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	###
	$current_time = time();
	$totalInsert = $totalDuplicate = 0;
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		$file_id = Input::post('file_id');
		$spreadsheetId = Input::post('spreadsheetId');
		if(!empty($file_id) || !empty($spreadsheetId)){
			$columns = ["stt","question","correct","A","B","C","D"];
			$cachedColumnName = sprintf('column_%s.json', $profile_id);
			$cachedFileColumn = sprintf('%s/quiz/%s', DIR_CACHE_JSON, $cachedColumnName);
			if(@file_exists($cachedFileColumn)){
				$columns = $decoder->decodeFile($cachedFileColumn);
			}
			if(!empty($spreadsheetId)){
				if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
					@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
					// $clsISO->print_pre($matches); die();
					$spreadsheetId = $matches[0];
				}
				#- Require library		
				require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
				/** Init Client */
				$client = new Google_Client();
				$client->setClientId(GOOGLE_CLIENT_ID);
				$client->setClientSecret(GOOGLE_CLIENT_SECRET);
				$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
				$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
				$service = new Google_Service_Sheets($client);
				// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
				// get all the rows of a sheet
				$range = 'QUIZ_TEST'; // here we use the name of the Sheet to get all the rows
				$response = $service->spreadsheets_values->get($spreadsheetId, $range);
				$tblData = $response->getValues();
			} else {
				$tblData = [];
				$cachedName = sprintf('%s.json', $file_id);
				$cachedFile = DIR_CACHE_JSON.'/quiz/'.$cachedName;
				if(@file_exists($cachedFile)){
					$tblData = $decoder->decodeFile($cachedFile);
					@unlink($cachedFile);
				}
			}
			if(!empty($tblData)){
				$arr_data = array();
				$total_record = @count($tblData);
				if($total_record > 1){
					for($i=1; $i<$total_record; $i++){
						$tmp = [];
						foreach($columns as $i_col => $p_field){
							$tmp[$p_field] = $tblData[$i][$i_col];
						}
						$arr_data[] = $tmp;
					}
				}
				$customer_insert = $questions = [];
				foreach($arr_data as $key => $_oQuestion) {
					$question_id = $clsISO->getUniqid();
					$answer_id = $clsISO->getUniqid();
					$correct = str_replace(" ","",$_oQuestion["correct"]);
					$correct = explode(",",$correct);
					if(count($correct) > 1) {
						$question_type = 2;
					}else{
						$question_type = 1;
					}		
					$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
					$arr_answer = array_intersect(array_keys($_oQuestion),$alphabet);					
					$answer_options = array();
					foreach ($arr_answer as $k => $alpha) {
						$answer_id = $clsISO->getUniqid();
						$is_correct = in_array($alpha,$correct) ? 1 : 0;
						$answer_options[$answer_id] = [
							"title"	=>	$_oQuestion[$alpha],
							"is_correct"	=>	$is_correct
						];
					}
												
					$questions[$question_id] = [
						"title"	=>	$_oQuestion["question"],
						"question_type"	=>	$question_type,
						"answer_options"	=>	$answer_options,		
						"score"		=>	0
					];
					
				}				
			}
		}
	}
	// Return
	$smarty->assign('group_id', $group_id);
	$smarty->assign('questions', $questions);
	$smarty->assign('is_export', 1);
	$html = $core->build('_ajax.add_question.tpl');
	// Return
	echo json_encode([
		"html"	=>	$html,
		"uid"	=>	$gId
	]); die();
}
?>