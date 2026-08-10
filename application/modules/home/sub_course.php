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
function course_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	if($cmd=="_detail"){
		$course_id = Input::get('course_id', 0);
		$scriptJs.= '<a class="autoclick_'.$course_id.'"" course_id="'.$course_id.'" 
		onClick="$Core.course.open(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$course_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}
	$assign_list["scriptJs"] = $scriptJs;
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$permiss_add = $clsISO->checkPermission("create_course");
	$permiss_edit = $clsISO->checkPermission("edit_code");
	$assign_list["permiss_add"] = $permiss_add;
	$assign_list["permiss_edit"] = $permiss_edit;
	###
	$list_preloaders = array();
	for($i= 0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
    /*=============Title & Description Page==================*/
	$title_page = 'Sự kiện - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function course_load_courses(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$html = "";
	##
	$keySearch = Input::post('keySearch', "");
	$cat_id = (int) Input::post('cat_id', "0");
	$start_date = Input::post('start_date', "");
	$end_date = Input::post('end_date', "");
	$start_date = !empty($start_date) ? $clsISO->convertTextToTime($start_date):0;
	$end_date =  !empty($end_date) ? $clsISO->convertTextToTime($end_date, "23:59:59") :0;
	##
	$cond = "`is_trash`=0 and `is_online`=1";
	if($cat_id > 0) $cond.= " and `cat_id`='{$cat_id}'";
	if(!empty($keySearch)) {
		$cond .= " and (title like '%{$keySearch}%' 
			or slug like '%".$core->replaceSpace($keySearch)."%'
		)";
	}
	if($start_date > 0 && $end_date > 0){
		$cond .= " 	AND {$start_date} <= `due_date` AND `start_date` <= {$end_date}";
	}else if($start_date > 0 && $end_date == 0){
		$cond .= " AND {$start_date} BETWEEN `start_date` AND `due_date`";
	}else if($start_date == 0 && $end_date > 0){
		$cond .= " AND {$end_date} BETWEEN `start_date AND due_date`";
	}
	$permiss_edit = $clsISO->checkPermission("edit_course") ? 1 : 0;
	$smarty->assign('permiss_edit', $permiss_edit);
	$department_id = $oneProfile['department_id'];
	$list_department_id = $oneProfile['list_department_id'];
	if(!$clsISO->checkPermission("view_all_course")){
		$sql_group = "";
		$list_profile_groups = $clsGroupProfile->getAll("`list_profile_id` like '%|{$profile_id}|%'", $clsGroupProfile->pkey);
		if(!empty($list_profile_groups)){ $ii = 0;
			foreach($list_profile_groups as $key => $val){
				$sql_group.= ($ii==0 ? "" : " OR "). " `list_group_profile_id` like '%|{$val[$clsGroupProfile->pkey]}|%'";
				++$ii;
			}
		}
		$cond.= " and (";
		$cond.= " (is_all_staff = '1' OR (is_all_staff='0' and (";
		$arr_department_ids = !empty($list_department_id) 
			? $clsISO->getArrayByTextSlash($list_department_id) 
			: array();
		$arr_department_ids[] = $department_id;
		$arr_department_ids = array_unique($arr_department_ids);
		$ii = 0;
		foreach($arr_department_ids as $id){
			$cond.= ($ii==0 ? "": " or ")."`list_department_id` like '%|{$id}|%'";
			++$ii;
		}
		$cond .= " or `list_profile_id` LIKE '%|{$profile_id}|%'";
		$cond .= ")))".(!empty($list_profile_groups) ? " OR (`is_all_staff`='2' AND ({$sql_group}))" : "")."";
		// $cond .= " or user_id='{$profile_id}'";
		$cond .= ")";
	}
	#- Begin Pagination
	$current_page = Input::post('page',1);
	$per_page  = Input::post('per_page',20);
	$total_record = $clsCourse->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	$list_courses = $clsCourse->getAll($cond." order by start_date DESC".$limitCond);
	if(!empty($list_courses)){		
		$i=0; $arr_cache = [];
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list_courses as $key => $val){
			$course_id = $val[$clsCourse->pkey];
			$start_date = $val['start_date'];
			$due_date = $val['due_date'];
			$user_id = (int) $val['user_id'];
			$cat_id = (int) $val['cat_id'];
			$list_profile_id = $val['list_profile_id'];
			$list_department_id = $val['list_department_id'];
			$list_group_profile_id = $val['list_group_profile_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if($user_id > 0 && isset($arr_profile_cached[$user_id])){
				$oneUser = $arr_profile_cached[$user_id];
				$list_courses[$key]['oneUser'] = $oneUser;
			}
			if(!isset($arr_cache["category"][$cat_id])){
				$arr_cache["category"][$cat_id] = $clsProperty->getTitle($cat_id);
			}			
			$cat_name = $arr_cache["category"][$cat_id];
			$list_courses[$key]['cat_name'] = $cat_name;
			#phòng ban
			$arr_department_ids = $clsISO->getArrayByTextSlash($list_department_id, ',', []);
			$arr_profile = $arr_department = []; $cond_profile = "";
			if(!empty($arr_department_ids)){
				foreach($arr_department_ids as $dep_id){
					if(!isset($arr_cache['department'][$dep_id])){
						$arr_cache['department'][$dep_id] = $clsProperty->getTitle($dep_id);
					}
					$arr_department[] = $arr_cache['department'][$dep_id];
					$cond_profile .= (($cond_profile != "")?" OR ":"")." `department_id`='{$dep_id}' OR `list_department_id` like '%|{$dep_id}|%' " ;
				}
			}
			$list_courses[$key]['department'] = implode(", ",$arr_department);
			#nhóm nhân viên
			$arr_group_profile_id = $clsISO->getArrayByTextSlash($list_group_profile_id, ",", []);
			if(!empty($arr_group_profile_id)) {
				$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 and group_profile_id IN (".implode(',',$arr_group_profile_id).")");	
				$arr_profile_group = $arr_title_group = [];
				foreach ($lstGroupProfile as $kgp => $vgp) {
					$arr_profile_group = array_merge($arr_profile_group,$clsISO->getArrayByTextSlash($vgp['list_profile_id']));
					$arr_title_group[] = $vgp['title'];
				}
				$arr_profile_group = array_unique($arr_profile_group);
				if(!empty($arr_profile_group)){					
					$list_courses[$key]['group_profile'] = implode(", ",$arr_title_group);
					$cond_profile .= " profile_id IN (".implode(',',$arr_profile_group).") ";
				}
			}		
			#nhân viên
			$arr_profile_id = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
			if(!empty($arr_profile_id)){
				foreach($arr_profile_id as $profile){
					if(!isset($arr_cache['profile'][$profile])){
						$arr_cache['profile'][$profile] = $clsProfile->getFullName($profile);
					}
					$arr_profile[] = $arr_cache['profile'][$profile];
					$cond_profile .= " OR `profile_id`='{$profile}' ";
				}
			}
			$cond_profile  = ($cond_profile != "")?"and (".$cond_profile.")":"";
			$total_profile = $clsProfile->countItem("`status_id`<>'"._STATUS_STAFF_OFF_ID."' ".$cond_profile);
			$list_courses[$key]['total_profile'] = $total_profile;
			$list_courses[$key]['profile'] = implode(", ",$arr_profile);
			unset($cond_profile,$total_profile);
			$list_courses[$key]['start_date'] = date("d/m/Y H:i",$val['start_date']);
			$list_courses[$key]['due_date'] = date("d/m/Y H:i",$val['due_date']);
			#tham gia	
			if ($cat_id == _MEDIA_DISSEMINATION){
				$upload_share = $core->get_field($more_information, "upload_share", []);
				$count_accept = count($upload_share);
//				$list_courses[$key]['txt_join'] = "Báo cáo: ".$count_accept;
			}else{
				$count_accept = $clsCheckIn->countItem("`event_id`='{$course_id}'");
//				$list_courses[$key]['txt_join'] = "Tham gia: ".$count_accept;
			}
			$list_courses[$key]['txt_join'] = $count_accept;
			$list_courses[$key]['count_accept'] = $count_accept;
			$list_courses[$key]['fileUpload'] = $core->get_field($more_information, "fileUpload", "");
			#trạng thái
			if(time() < $val['start_date']){
				$list_courses[$key]['status'] = '<span class="badge bg-label-info">Sắp diễn ra</span>';
			}else if($val['start_date'] < time() && time() < $val['due_date']){
				$list_courses[$key]['status'] = '<span class="badge bg-label-success">Đang diễn ra</span>';
			}else{
				$list_courses[$key]['status'] = '<span class="badge bg-label-danger">Đã diễn ra</span>';
			}
			unset($upload_share,$more_information,$arr_profile,$arr_department);
			++$i;
		}
	}
	$assign_list['list_courses'] = $list_courses;
	// Return
	$html = $core->build('course'.DS.'_ajax.listCourse.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'per_page' => $per_page
	));
}
function course_open_course(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$assign_list,$profile_id,$oneProfile;
	$clsZalo = new Zalo();
	$clsCache = new Cache();
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$_tp = Input::post("_tp","");
	$type = Input::post("type","open");
	$course_id = (int)Input::post("course_id",0);
	$smarty->assign('uid', $uid);
	$smarty->assign('_tp', $_tp);
	$smarty->assign('course_id', $course_id);
	###
	$field = "{$clsProfile->pkey},first_name,last_name,full_name";
	$list_profiles = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
	and `profile_id` not in(".implode(',',_PROFILE_NOTIN_ID).") order by `code` ASC", $field);
	$assign_list["list_profiles"] = $list_profiles;
	$lstGroupProfile = $clsGroupProfile->getAll("`is_trash`=0 and is_online=1 ORDER BY `upd_date` DESC");
	$assign_list["lstGroupProfile"] = $lstGroupProfile;
	###
	$action = "add";
	$data = ['result' => false,"msg"=>"Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại"];
	$start_date = date("Y-m-d\TH:i");
	$due_date = date("Y-m-d\TH:i",strtotime("+1 days"));
	if($course_id > 0){
		$action = "edit";
		$oneItem = $clsCourse->getOne($course_id);
		$manager_ids = $oneItem['manager_ids'];
		$manager_ids = $clsISO->to_array_json($manager_ids);
		$start_date = date("Y-m-d\TH:i",$oneItem['start_date']);
		$due_date = date("Y-m-d\TH:i",$oneItem['due_date']);
		$oneItem['start_date'] = $start_date;
		$oneItem['due_date'] = $due_date;
		$oneItem['manager_ids'] = $manager_ids;
	}	
	$assign_list["start_date"] = $start_date;
	$assign_list["due_date"] = $due_date;
	$assign_list["action"] = $action;
	$assign_list["oneItem"] = $oneItem;
	$more_information = (!empty($oneItem['more_information']))?$clsISO->to_array_json($oneItem['more_information']):array();
	$assign_list["more_information"] = $more_information;
	if($type == "open"){
		if($_tp == "event") {
			$cat_id = _CAT_EVENT_ID;
			$assign_list["cat_id"] = $cat_id;
		}
		$list_projects = $clsCache->get('_header_project_cached');
		$assign_list["list_projects"] = $list_projects;
		$html = $core->build('course'.DS.'_ajax.open_course.tpl');
		$data = [
			'uid' => $uid,
			'result' =>	true,
			'html' => $html
		];
	}else if($type == 'add' || $type == 'edit'){
		$title = Input::post('title',"");
		$slug = $core->replaceSpace($title);
		$cat_id = (int)Input::post('cat_id',0);
		$start_date = Input::post('start_date',"");
		$due_date = Input::post('due_date',"");
		$location = Input::post('location',"");
		$content = Input::post('content',"");
		$manager_ids = Input::post('manager_ids');
		$manager_ids = !empty($manager_ids) ? explode(',', $manager_ids) : [];
		$is_all_staff = Input::post('is_all_staff',0);
		$file_name = Input::post('file',"");
		$link = Input::post('link',"");
		$file = $link_image = "";
		$list_profile_id = Input::post('list_profile_id',"");
		$list_department_id = Input::post('list_department_id',"");
		$list_group_profile_id = Input::post('list_group_profile_id',"");
		if($_tp == "event") {
			$project_id = (int)Input::post("project_id",0);
			$block_id = (int)Input::post("block_id",0);
			$more_information["project_id"] = $project_id;
			$more_information["block_id"] = $block_id;
		}
		$more_information["link"] = $link;
		$more_information["manager_ids"] = $manager_ids;
		/*$fileUpload = $_FILES['fileUpload'];
		if(is_uploaded_file($fileUpload['tmp_name'])){
			$clsUploadFile = new UploadFile();
			$file = $clsUploadFile->uploadItem($fileUpload,"/COURSE/fileUpload",EXTENSION_FILE_UPLOAD);
			if(!empty($file) && file_exists(ROOTPATH . $file)){
				// Set the file metadata for drive
			}
		}
		$fileUp = (!empty($file))?$file:$file_name;
		if($fileUp == ""){
			$fileOld = (!empty($more_information['fileUpload']))?$more_information['fileUpload']:"";
			if($fileOld != ""){
				@unlink(ROOTPATH . $fileOld);
			}
		}
		$more_information['fileUpload'] = $fileUp;*/
		#kiem tra trung
		$time_check = strtotime("-1 hours");
		$cond = "`slug`='{$slug}' AND `cat_id`='{$cat_id}' AND `reg_date` > '{$time_check}'";
		if($type == "edit") {
			$cond .= " AND `{$clsCourse->pkey}` <> '{$course_id}'";
		}
		$check_title_exist = $clsCourse->getByCond($cond,"`title`");
		if(!empty($check_title_exist)) {
			$data = ['result' => false,"msg"=>"Sự kiện `{$title}` đã được đăng tải trước đó!"];
			echo json_encode($data);die;
		}
		$arr_field = [
			'title'				=>	addslashes($title),
			'slug'				=>	$slug,
			'image'				=>	$link_image,
			'cat_id'			=>	$cat_id,
			'content'			=>	addslashes($content),
			'start_date'		=>	$clsISO->toTime($start_date),
			'due_date'			=>	(!empty($due_date)) ? $clsISO->toTime($due_date) : 0,
			'manager_ids'		=>  json_encode($manager_ids, JSON_UNESCAPED_UNICODE),
			'is_all_staff'		=>	$is_all_staff,
			'location'			=>	$location,
			'user_id_update'	=>	$profile_id,
			'upd_date'			=>	time(),
			'more_information'	=>	json_encode($more_information, JSON_UNESCAPED_UNICODE)
		];
		// $clsISO->print_pre($arr_field); die();
		if($type == "add"){
			$arr_field["reg_date"] = time(); 
			$arr_field["user_id"] = $profile_id; 
			$arr_field["order_no"] = $clsCourse->getMaxOrderNo(); 
			$arr_field["is_trash"] = 0; 
			$arr_field["is_online"] = 1; 
		}
		if($is_all_staff==0){
			$list_department_id = ($list_department_id != "")?explode(",",$list_department_id):array();
			$list_profile_id = ($list_profile_id != "")?explode(",",$list_profile_id):array();
			$list_department_id = !empty($list_department_id) ? $clsISO->makeSlashListFromArray($list_department_id,'|', true) : "";
			$list_profile_id = !empty($list_profile_id) ? $clsISO->makeSlashListFromArray($list_profile_id,'|', true) : "";
			$arr_field["list_department_id"] = $list_department_id; 
			$arr_field["list_profile_id"] = $list_profile_id; 
		}else if($is_all_staff == 2) {
			$list_group_profile_id = ($list_group_profile_id != "")?explode(",",$list_group_profile_id):array();
			$list_group_profile_id = !empty($list_group_profile_id) ? $clsISO->makeSlashListFromArray($list_group_profile_id,'|', true) : "";
			$arr_field["list_group_profile_id"] = $list_group_profile_id; 
		}
		$lstProfileID = $clsCourse->getLstIDProfile($list_department_id,$list_profile_id,$list_group_profile_id);			
		if($type == "add"){
			$max_id = $clsCourse->getMaxID();
			if($clsCourse->insert($arr_field)){
				$data=["result"	=>	true,"cat_id"=>$cat_id];
				if($_tp == "_ZALO") {
					#thông báo 
					if($cat_id == _MEDIA_DISSEMINATION){
						$message.= "**Bạn có** {green}__1 lời mời __{/green} **tham gia sự kiện lan tỏa**";
					}else{
						$message.= "**Bạn có** {green}__1 lời mời __{/green} **tham gia sự kiện**";
					}
					$message.= "\n";
					$message.= "**Tiêu đề:** {red}**".$title."**{/red}";
					$message.= "\n";
					if(!empty($content)) {		
						$message.= "**Nội dung:** {green}__".$content."__{/green} ";
						$message.= "\n";
					}					
					if(!empty($link)) {		
						$message.= "**Link chia sẻ:** {blue}".$link."{/blue}";
						$message.= "\n";	
					}
					$message.= "**Nhấn link sự kiện để tham gia ngay:** {blue}".PCMS_URL.$clsCourse->getLink($max_id)."{/blue}";
					$message.= "\n";
					$body = $clsZalo->parseZaloMessage($message);
					foreach ($lstProfileID as $profileId) {
						$clsZalo->sendZaloUser2($profileId,$body);
					}
				}
				$clsNotify = new Notify();	
				/*if($cat_id == _MEDIA_DISSEMINATION){
					$titleNoty = sprintf('<strong>%s</strong> đã yêu cầu bạn báo cáo sự kiện lan toả <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $title);
				}else{
					$titleNoty = sprintf('<strong>%s</strong> gửi lời mời tham gia sự kiện <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $title);
				}
				$clsNotify->insertNotify('Course',$clsCourse->pkey, $max_id, $titleNoty, time(), $lstProfileID);*/
			}
		}else if($type == 'edit'){
			if($clsCourse->updateOne($course_id,$arr_field)){
				if($_tp == "_ZALO") {
					#thông báo 
					if($cat_id == _MEDIA_DISSEMINATION){
						$message.= "**Bạn có** {green}__1 lời mời __{/green} **tham gia sự kiện lan tỏa**";
					}else{
						$message.= "**Bạn có** {green}__1 lời mời __{/green} **tham gia sự kiện**";
					}
					$message.= "\n";
					$message.= "**Tiêu đề:** {red}**".$title."**{/red}";
					$message.= "\n";
					if(!empty($content)) {		
						$message.= "**Nội dung:** {green}__".$content."__{/green} ";
						$message.= "\n";
					}					
					if(!empty($link)) {		
						$message.= "**Link chia sẻ:** {blue}".$link."{/blue}";
						$message.= "\n";	
					}
					$message.= "**Nhấn link sự kiện để tham gia ngay:** {blue}".PCMS_URL.$clsCourse->getLink($course_id)."{/blue}";
					$message.= "\n";
					$body = $clsZalo->parseZaloMessage($message);
					foreach ($lstProfileID as $profileId) {
						$clsZalo->sendZaloUser2($profileId,$body);
					}
				}
				$data=["result"	=>	true,"cat_id"=>$cat_id];
				/*if($is_all_staff==0 || $is_all_staff==2){					
					$clsNotify = new Notify();
					$lstProfileIdOld = $clsCourse->getLstIDProfile($oneItem['list_department_id'],$oneItem['list_profile_id']);
					$lstProfileIdUpdate = array_diff($lstProfileID,$lstProfileIdOld);
					if($cat_id == _MEDIA_DISSEMINATION){
						$titleNoty = sprintf('<strong>%s</strong> đã yêu cầu bạn báo cáo sự kiện lan toả <strong>%s</strong>', $clsProfile->getFullName($profile_id), $title);
					}else{
						$titleNoty = sprintf('<strong>%s</strong> gửi lời mời tham gia sự kiện <strong>%s</strong>', $clsProfile->getFullName($profile_id), $title);
					}
					$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, time(), $lstProfileIdUpdate);
				}*/
			}
		}
	}
	// Return
	echo json_encode($data); die();
}
function course_view_course(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsGroupProfile = new GroupProfile();
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsComment = new Comment();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	#
	$uid = $clsISO->getUniqid();
	$course_id = (int) Input::post('course_id', 0);
	$smarty->assign('uid', $uid);
	$smarty->assign('course_id', $course_id);
	$smarty->assign('_MEDIA_DISSEMINATION', _MEDIA_DISSEMINATION);
	#
	$oneCourse = $clsCourse->getOne($course_id);
	$cat_id = $oneCourse['cat_id'];
	$start_date = $oneCourse['start_date'];
	$due_date = $oneCourse['due_date'];
	$manager_ids = $oneCourse['manager_ids'];
	$list_profile_id = $oneCourse['list_profile_id'];
	$list_department_id = $oneCourse['list_department_id'];
	$list_group_profile_id = $oneCourse['list_group_profile_id'];
	$more_information = $oneCourse['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$manager_ids = $clsISO->to_array_json($manager_ids);
	$arr_profile_ids = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : [];
	$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : [];
	$arr_group_profile_ids = !empty($list_group_profile_id) ? $clsISO->getArrayByTextSlash($list_group_profile_id) : [];
	$oneCourse['manager_ids'] = $manager_ids; // Gán vào
	$smarty->assign('more_information', $more_information);
	// Lan tỏa truyền thông
	$arr_property_cached = array();
	if($cat_id == _MEDIA_DISSEMINATION){
		$check_upload = $total_report_staffs = $total_unreport_staffs = 0;
		$list_report_staffs_ids = array(); 
		$list_report_staffs = $core->get_field($more_information, "upload_share", []);
		if(!empty($list_report_staffs)){
			$total_report_staffs = count($list_report_staffs);
			$list_report_staffs_ids = array_keys($list_report_staffs);
			foreach($list_report_staffs as $key => $val){
				$staff_id = $val['profile_id'];
				if($staff_id == $profile_id) $check_upload = 1;
				$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
				$oProfile = $clsProfile->getOne($staff_id, $field);
				$department_id = $oProfile['department_id'];
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] =  $clsProperty->getTitle($department_id);
				}
				$list_report_staffs[$key]['full_name'] = $clsProfile->getFullName($staff_id, $oProfile);
				$list_report_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			}
		}
		$arr_report = @array_column($list_report_staffs, 'time');
		@array_multisort($arr_report, SORT_DESC, $list_report_staffs);
		$list_unreport_staffs = array();
		$cond = "`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and `profile_id` not in (".implode(',',_PROFILE_NOTIN_ID).")";
		if(!empty($list_report_staffs_ids))
			$cond.= " and {$clsProfile->pkey} not in (".implode(',', $list_report_staffs_ids).")";
		if($oneCourse['is_all_staff'] == 0){
			if(!empty($arr_profile_ids) || !empty($arr_department_ids)){
				$cond.= " and ("; $hasCond = false;
				if(!empty($arr_department_ids)){ $ii = 0;
					$hasCond = true;
					foreach($arr_department_ids as $department_id){
						$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
						++$ii;
					}
				}
				if(!empty($arr_profile_ids)){
					$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
				}
				$cond.= ")";
			}
		}else if($oneCourse['is_all_staff'] == 2) {
			$lstProfileGroup = $clsCourse->getLstIDProfile("","",$oneCourse['list_group_profile_id']);
			if(!empty($lstProfileGroup)){
				$cond .= " and profile_id IN (".implode(',',$lstProfileGroup).")";
			}
		}
		$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
		$list_unreport_staffs = $clsProfile->getAll($cond, $field);
		if(!empty($list_unreport_staffs)){
			$total_unreport_staffs = count($list_unreport_staffs);
			foreach($list_unreport_staffs as $key => $val){
				$department_id = $val['department_id'];
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] =  $clsProperty->getTitle($department_id);
				}
				$list_unreport_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			}
		}
		$smarty->assign('check_upload', $check_upload);
		$smarty->assign('total_report_staffs', $total_report_staffs);
		$smarty->assign('total_unreport_staffs', $total_unreport_staffs);
		$smarty->assign('list_report_staffs', $list_report_staffs);
		$smarty->assign('list_unreport_staffs', $list_unreport_staffs);
	} else {
		$cond = "`t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' and `t1`.`profile_id` not in (".implode(',',_PROFILE_NOTIN_ID).")";
		if($oneCourse['is_all_staff'] == 0){
			if(!empty($arr_department_ids) || !empty($arr_profile_ids)){
				$cond.= " and ("; $hasCond = false;
				if(!empty($arr_department_ids)){ $ii = 0;
					$hasCond = true;
					foreach($arr_department_ids as $department_id){
						$cond.= ($ii==0 ? "": " or ")."(`t1`.`department_id`='{$department_id}' 
							or `t1`.`list_department_id` like '%|{$department_id}|%')";
						++$ii;
					}
				} 
				if(!empty($arr_profile_ids)){
					$cond.= ($hasCond ? " or " : "") . "(`t1`.`profile_id` in (".implode(',',$arr_profile_ids)."))";
				}
				$cond.= ")";
			}
		}else if($oneCourse['is_all_staff'] == 2) {
			$lstProfileGroup = $clsCourse->getLstIDProfile("","",$oneCourse['list_group_profile_id']);
			if(!empty($lstProfileGroup)){
				$cond .= " and `t1`.`profile_id` IN (".implode(',',$lstProfileGroup).")";
			}
		}
		$field = "`t1`.`profile_id`,`t1`.`code`,`t1`.`full_name`,`t1`.`avatar`,`t1`.`phone`,`t1`.`role_id`,`t1`.`department_id`";
		$field.= ",`t2`.`checked_in`,`t2`.`reg_date`,`t2`.`checked_in_date`";
		$list_staffs = $dbconn->getAll("SELECT {$field} FROM {$clsProfile->tbl} AS `t1` 
			INNER JOIN `{$clsCheckIn->tbl}` AS `t2` ON `t1`.`profile_id`=`t2`.`profile_id` 
			WHERE `t2`.`event_id`='{$course_id}' AND {$cond} ORDER BY `t2`.`reg_date` DESC");
		$total_checkin_staffs = !empty($list_staffs) ? count($list_staffs) : 0;
		$is_joined = $is_checked_in = 0;
		$oCheckIn = $clsCheckIn->getByCond("`event_id`='{$course_id}' and `profile_id`='{$profile_id}'");
		if(!empty($oCheckIn)){
			$is_joined = 1;
			$is_checked_in = $oCheckIn['checked_in'];
		}
		$is_time_checkin = 0;
		if(time() >= strtotime("-15 minutes", $oneCourse["start_date"])) {
			$is_time_checkin = 1;
		}
		$oneCourse['is_joined'] = $is_joined;
		$oneCourse['is_checked_in'] = $is_checked_in;
		$oneCourse['is_time_checkin'] = $is_time_checkin;
		$smarty->assign('list_staffs', $list_staffs);
		$oneCourse['total_checkin_staffs'] = $total_checkin_staffs;
	}
	##
	$check_overTime = 0;
	if(time() > $oneCourse['due_date']){
		$check_overTime = 1;
	}
	$total_comments = $clsComment->getTotalComment($course_id, "Course");
	###
	$oneCourse['department_name'] = $oneCourse['profile_name'] = $oneCourse['group_name'] = "";
	$cond = "`status_id`<>'"._STATUS_STAFF_OFF_ID."' and `profile_id` not in (".implode(',',_PROFILE_NOTIN_ID).")";
	if($oneCourse['is_all_staff'] == 0){
		if(!empty($arr_profile_ids) || !empty($arr_department_ids)){
			$cond.= " and ("; $hasCond = false;
			$profile_arrs = $department_arrs = array();
			if(!empty($arr_department_ids)){ $ii = 0;
				$hasCond = true;
				foreach($arr_department_ids as $department_id){
					$department_arrs[] = $clsProperty->getTitle($department_id);
					$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$department_id}' 
						or `list_department_id` like '%|{$department_id}|%')";
					++$ii;
				}
				$oneCourse['department_name'] = implode(", ",$department_arrs);
			}
			#nhân viên
			if(!empty($arr_profile_ids)){
				foreach($arr_profile_ids as $profile_id){
					$profile_arrs[] = $clsProfile->getFullName($profile_id);
				}
				$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
				$oneCourse['profile_name'] = implode(", ",$profile_arrs);
			}
			$cond.= ")";
		}
	}else if($oneCourse['is_all_staff'] == 2) {
		$group_name_arrs = array();
		$lstProfileGroup = $clsCourse->getLstIDProfile("","",$oneCourse['list_group_profile_id']);
		if(!empty($arr_group_profile_ids)){
			foreach($arr_group_profile_ids as $group_id){
				$group_name_arrs[] = $clsGroupProfile->getTitle($group_id);
			}
			$cond.= " and `profile_id` IN (".implode(',',$lstProfileGroup).")";
		}
		$oneCourse['group_name'] = implode(', ', $group_name_arrs);
	}
	$total_staffs = $clsProfile->countItem($cond);
	$oneCourse['total_staffs'] = $total_staffs;
	#
	$smarty->assign('check_overTime', $check_overTime);
	$smarty->assign('total_comments', $total_comments);
	$smarty->assign('oneCourse', $oneCourse);
	// Return
	$html = $core->build('course'.DS.'_ajax.course.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cat_id' => $cat_id
	)); die();
}
function course_view_join_course(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id;
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$uid = $clsISO->getUniqid();
	$course_id = (int) Input::post('course_id', 0);
	$smarty->assign('uid', $uid);
	$smarty->assign('course_id', $course_id);
	$smarty->assign('_MEDIA_DISSEMINATION', _MEDIA_DISSEMINATION);
	$oneCourse = $clsCourse->getOne($course_id);
	$cat_id = $oneCourse['cat_id'];
	$start_date = $oneCourse['start_date'];
	$due_date = $oneCourse['due_date'];
	$list_profile_id = $oneCourse['list_profile_id'];
	$list_department_id = $oneCourse['list_department_id'];
	$more_information = $oneCourse['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$arr_profile_ids = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array();
	$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
	// Lan tỏa truyền thông
	$arr_property_cached = array();
	if($cat_id == _MEDIA_DISSEMINATION){
		$check_upload = $total_report_staffs = $total_unreport_staffs = 0;
		$list_report_staffs = $list_report_staffs_ids = array(); 
		$list_report_staffs = isset($more_information['upload_share']) 
			? $more_information['upload_share'] : array();
		if(!empty($list_report_staffs)){
			$total_report_staffs = count($list_report_staffs);
			$list_report_staffs_ids = array_keys($list_report_staffs);
			foreach($list_report_staffs as $key => $val){
				$staff_id = $val['profile_id'];
				if($staff_id == $profile_id) $check_upload = 1;
				$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
				$oProfile = $clsProfile->getOne($staff_id, $field);
				$department_id = $oProfile['department_id'];
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] =  $clsProperty->getTitle($department_id);
				}
				$list_report_staffs[$key]['full_name'] = $clsProfile->getFullName($staff_id, $oProfile);
				$list_report_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			}
		}
		$list_unreport_staffs = array();
		$cond = "`status_id`<>'"._STATUS_STAFF_OFF_ID."' 
		and `profile_id` not in (".implode(',',_PROFILE_NOTIN_ID).")";
		if(!empty($list_report_staffs_ids))
			$cond.= " and {$clsProfile->pkey} not in (".implode(',', $list_report_staffs_ids).")";
		if($oneCourse['is_all_staff'] == 0){
			if(!empty($arr_profile_ids) || !empty($arr_department_ids)){
				$cond.= " and ("; $hasCond = false;
				if(!empty($arr_department_ids)){ $ii = 0;
					$hasCond = true;
					foreach($arr_department_ids as $department_id){
						$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
						++$ii;
					}
				}
				if(!empty($arr_profile_ids)){
					$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
				}
				$cond.= ")";
			}
		}
		$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
		$list_unreport_staffs = $clsProfile->getAll($cond, $field);
		if(!empty($list_unreport_staffs)){
			$total_unreport_staffs = count($list_unreport_staffs);
			foreach($list_unreport_staffs as $key => $val){
				$department_id = $val['department_id'];
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] =  $clsProperty->getTitle($department_id);
				}
				$list_unreport_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			}
		}
		$smarty->assign('check_upload', $check_upload);
		$smarty->assign('total_report_staffs', $total_report_staffs);
		$smarty->assign('total_unreport_staffs', $total_unreport_staffs);
		$smarty->assign('list_report_staffs', $list_report_staffs);
		$smarty->assign('list_unreport_staffs', $list_unreport_staffs);
		// $clsISO->print_pre($list_unreport_staffs); die();
	} else {
		$cond = "`t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		if($oneCourse['is_all_staff'] == 0){
			if(!empty($arr_department_ids) || !empty($arr_profile_ids)){
				$cond.= " and ("; $hasCond = false;
				if(!empty($arr_department_ids)){ $ii = 0;
					$hasCond = true;
					foreach($arr_department_ids as $department_id){
						$cond.= ($ii==0 ? "": " or ")."(`t1`.`department_id`='{$department_id}' 
							or `t1`.`list_department_id` like '%|{$department_id}|%')";
						++$ii;
					}
				} 
				if(!empty($arr_profile_ids)){
					$cond.= ($hasCond ? " or " : "") . "(`t1`.`profile_id` in (".implode(',',$arr_profile_ids)."))";
				}
				$cond.= ")";
			}
		}
		$field = "`t1`.`profile_id`,`t1`.`code`,`t1`.`full_name`,`t1`.`avatar`,`t1`.`department_id`,`t1`.`role_id`";
		$field.= ",`t2`.`checked_in`,`t2`.`reg_date`,`t2`.`checked_in_date`";
		$list_staffs = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as `t1` 
			inner join `{$clsCheckIn->tbl}` as `t2` on `t1`.`profile_id`=`t2`.`profile_id` 
			where `t2`.`event_id`='{$course_id}' and {$cond} order by `t2`.`reg_date` DESC");
		$total_checkin_staffs = !empty($list_staffs) ? count($list_staffs) : 0;
		// $clsISO->print_pre($list_staffs); die();
		$is_joined = $is_checked_in = 0;
		$oCheckIn = $clsCheckIn->getByCond("`event_id`='{$course_id}' and `profile_id`='{$profile_id}'");
		if(!empty($oCheckIn)){
			$is_joined = 1;
			$is_checked_in = $oCheckIn['checked_in'];
		}
		$oneCourse['is_joined'] = $is_joined;
		$oneCourse['is_checked_in'] = $is_checked_in;
		$smarty->assign('list_staffs', $list_staffs);
		$oneCourse['total_checkin_staffs'] = $total_checkin_staffs;
	}
	##
	$check_overTime = 0;
	if(time() > $oneCourse['due_date']){
		$check_overTime = 1;
	}
	$smarty->assign('check_overTime', $check_overTime);
	##
	$clsComment = new Comment();	
	$total_comments = $clsComment->getTotalComment($course_id, "Course");
	$smarty->assign('total_comments', $total_comments);
	###
	$oneCourse['department_name'] = $oneCourse['profile_name'] = "";
	$cond = "`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	if($oneCourse['is_all_staff'] == 0){
		if(!empty($arr_profile_ids) || !empty($arr_department_ids)){
			$cond.= " and ("; $hasCond = false;
			$profile_arrs = $department_arrs = array();
			#phòng ban
			if(!empty($arr_department_ids)){ $ii = 0;
				$hasCond = true;
				foreach($arr_department_ids as $department_id){
					$department_arrs[] = $clsProperty->getTitle($department_id);
					$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$department_id}' 
						or `list_department_id` like '%|{$department_id}|%')";
					++$ii;
				}
				$oneCourse['department_name'] = implode(", ",$department_arrs);
			}
			#nhân viên
			if(!empty($arr_profile_ids)){
				foreach($arr_profile_ids as $profile_id){
					$profile_arrs[] = $clsProfile->getFullName($profile_id);
				}
				$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
				$oneCourse['profile_name'] = implode(", ",$profile_arrs);
			}
			$cond.= ")";
		}
	}else if($oneCourse['is_all_staff'] == 2) {
		$lstProfileGroup = $clsCourse->getLstIDProfile("","",$oneCourse['list_group_profile_id']);
		if(!empty($lstProfileGroup)){
			$cond .= " and profile_id IN (".implode(',',$lstProfileGroup).")";
		}
	}
	$total_staffs = $clsProfile->countItem($cond);
	$oneCourse['total_staffs'] = $total_staffs;
	$smarty->assign('oneCourse', $oneCourse);
	// Return
	$html = $core->build('course'.DS.'_ajax.join_course.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'cat_id' => $cat_id
	)); die();
}
function course_delete_course(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id;
	$clsCourse = new Course();
	$clsCheckIn = new CheckIn();
	$course_id = (int) Input::post('course_id', 0);
	###
	$msg = "_error";
	if($course_id > 0){
		$oneCourse = $clsCourse->getOne($course_id);
		$more_information = (!empty($oneCourse['more_information']))?$clsISO->to_array_json($oneCourse['more_information']):array();
		if(!empty($oneCourse)){			
			if($clsCourse->deleteOne($course_id)){
				$clsCheckIn->deleteByCond("`faq_id`='{$course_id}'");				
				/*$fileOld = (!empty($more_information['fileUpload']))?$more_information['fileUpload']:"";
				if($fileOld != ""){
					@unlink(ROOTPATH . $fileOld);
				}*/
				$msg = '_success';
			}
		}		
	}
	// Return
	echo $msg; die();
}
function course_upload_image(){
	global $core,$_frontIsLoggedin_user_id,$clsISO,$profile_id,$oneProfile;
	$clsCourse = new Course();
	$clsProfile = new Profile();
	$clsCheckIn = new CheckIn();
	$clsNotify = new Notify();
	$type = Input::post('type', "");
	$course_id = (int)Input::post("course_id",0);
	###
	$msg = "_error";
	$current_Now = time();
	$oCourse = $clsCourse->getOne($course_id, "title,user_id,more_information");
	$user_id = $oCourse['user_id']; // Create event Id
	$more_information = $oCourse['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$image = "";		
		if(is_uploaded_file($_FILES['image']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			$image = $clsUploadFile->uploadItem($_FILES["image"],"/MEDIA_DISSEMINATION",EXTENSION_FILE_UPLOAD);
			if(!empty($image) && file_exists(ROOTPATH . $image)){
				$msg = "_success";
				// Set the file metadata for drive
				$filename = $_FILES["image"]["name"];
				$mimeType = $_FILES["image"]["type"];
				$clsGoogleDrive = new GoogleDrive();
				$createdFile = $clsGoogleDrive->upload($filename, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_EVENT_ID);
				$upload_file = sprintf('https://drive.google.com/file/d/%s/view', $createdFile->getId());
				$upload_image = sprintf("https://drive.google.com/thumbnail?id=%s&sz=w1000", $createdFile->getId());
				if($type=='checkin'){
					$oCheckin = $clsCheckIn->getByCond("`event_id`='{$course_id}' and `profile_id`='{$profile_id}'");
					if(!empty($oCheckin)){
						if($clsCheckIn->updateOne($oCheckin[$clsCheckIn->pkey], array(
							'image' => $upload_file,
							'checked_in' => 1,
							'upd_date' => time(),
							'checked_in_date' => time()
						))){
							$msg.="|||<button type=\"button\" class=\"btn btn-sm btn-primary text-nowrap btn_checkin btn_checkin\" onClick=\"$Core.course.checkin(this,event)\" course_id=\"".$course_id."\" holderG=\"cancel\" openFrom=\"_pop\" staff_id=\"".$profile_id."\">Đã xác nhận</button>
							<button type=\"buton\" class=\"btn btn-sm btn-outline-success text-nowrap\"><i class=\"bx bx-check-double\"></i> Đã check-In</button>";
							$titleNoty = sprintf('<strong>%s</strong> đã checkin sự kiện 
							<strong>%s</strong> vào lúc <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $oCourse['title'], $clsISO->convertTimeToText($current_Now));
							$list_user_id = _PROFILE_RECEIVE_NOTIFY_EVENT_ID;
							if($profile_id != $user_id) {
								$list_user_id[] = $user_id;
							}
							$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, $current_Now, $list_user_id);
							#thong bao app
							$clsNotification = new Notification();
							$params = [
								'title' => "Sự kiện - Đào tạo",
								'body' => strip_tags($titleNoty),
								'link' => PCMS_URL . $clsCourse->getLink($course_id)
							];
							$clsNotification->doPushMessagingUser($params,$list_user_id);
						}
					} else {
						if($clsCheckIn->insert(array(
							$clsCheckIn->pkey => $clsCheckIn->getMaxId(),
							'profile_id' => $profile_id,
							'event_id' => $course_id,
							'image' => $upload_file,
							'checked_in' => 1,
							'reg_date' => time(),
							'upd_date' => time(),
							'image' => $upload_file
						))){
							$titleNoty = sprintf('<strong>%s</strong> đã checkin sự kiện <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $oCourse['title'], $clsISO->convertTimeToText($current_Now));
							$list_user_id = _PROFILE_RECEIVE_NOTIFY_EVENT_ID;
							if($profile_id != $user_id) {
								$list_user_id[] = $user_id;
							}
							$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, $current_Now, $list_user_id);
							#thong bao app
							$clsNotification = new Notification();
							$params = [
								'title' => "Sự kiện - Đào tạo",
								'body' => strip_tags($titleNoty),
								'link' => PCMS_URL . $clsCourse->getLink($course_id)
							];
							$clsNotification->doPushMessagingUser($params,$list_user_id);
						}
					}
				} else {
					$upload_share = isset($more_information['upload_share']) && !empty($more_information['upload_share']) 
						? $more_information['upload_share'] : array();
					$upload_share[$profile_id] = array(
						'profile_id' 	=> $profile_id,
						'driver_image'	=>	$upload_file,
						'image'			=>	$upload_image,
						'time'			=>	time()
					);
					$more_information['upload_share'] = $upload_share;
					if($clsCourse->updateOne($course_id, array(
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						$msg.="|||<span class=\"btn btn-sm btn-primary\"><i class='bx bx-user-check'></i> Đã báo cáo</span>";
						$oneCourse = $clsCourse->getOne($course_id,"title,user_id");
						$titleNoty = sprintf('<strong>%s</strong> đã báo cáo chia sẻ sự kiện lan toả truyền thông <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProfile->getFullName($profile_id), $oCourse['title'], $clsISO->convertTimeToText($current_Now));
						$list_user_id = [];
						if($profile_id != $user_id) {
							$list_user_id[] = $user_id;
						}
						$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, $current_Now, $list_user_id);
						#thong bao app
						$clsNotification = new Notification();
						$params = [
							'title' => "Sự kiện - Đào tạo",
							'body' => strip_tags($titleNoty),
							'link' => PCMS_URL . $clsCourse->getLink($course_id)
						];
						$clsNotification->doPushMessagingUser($params,$list_user_id);
					}
				}
				@unlink(ROOTPATH . $image);
			}
		}
	}
	// Return	
	echo $msg; die;
}
function course_load_list_user(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	#
	$type = Input::get("type","all");
	$course_id = (int) Input::get("course_id",0);
	$oneCourse = $clsCourse->getOne($course_id);
	$list_profile_id = $oneCourse['list_profile_id'];
	$list_department_id = $oneCourse['list_department_id'];
	$more_information = $oneCourse['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	#
	$cond = "`is_trash`=0 AND `profile_id` NOT IN(".implode(',',_PROFILE_NOTIN_ID).")";
	if($type == "accept"){
		if($oneCourse['cat_id'] == _MEDIA_DISSEMINATION){
			$upload_share = $core->get_field($more_information, "upload_share", []);
			$cond.= " AND `profile_id` IN (".implode(",",array_keys($upload_share)).")";
		}else{
			$cond .= " AND `profile_id` IN (SELECT `profile_id` FROM {$clsCheckIn->tbl} WHERE `faq_id`='{$course_id}')";
		}		
	}
	if($oneCourse['is_all_staff'] == 0){
		$arr_profile_ids = $clsISO->getArrayByTextSlash($list_profile_id, ",", []);
		$arr_department_ids = $clsISO->getArrayByTextSlash($list_department_id, ",", []);
		if(!empty($arr_department_ids) || !empty($arr_profile_ids)){
			$cond.= " and ("; $hasCond = false;
			if(!empty($arr_department_ids)){ $ii = 0;
				$hasCond = true;
				foreach($arr_department_ids as $id){
					$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$id}' or `list_department_id` like '%|{$id}|%')";
					++$ii;
				}
			}
			if(!empty($arr_profile_ids)){
				$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
			}
			$cond.= ")";
		}
	}
	$field = "{$clsProfile->pkey}"; // ,`first_name`,`last_name`,`full_name`,`avatar`,`more_information`
	$arr_staffs = $clsProfile->getAll($cond, $field);	
	$html = "<ul class='list-unstyled p-2 mb-0 dropdown-scrollable'>";
	if(!empty($arr_staffs)){
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($arr_staffs as $key => $val){
			$html.= '<li class="py-1 border-bottom">
				'.$clsProfile->getIndentityV5($val[$clsProfile->pkey], $arr_profile_cached[$val[$clsProfile->pkey]]).'
			</li>';
		}
		unset($arr_staffs);
	} else {
		$html.= '<li class="d-flex flex-column align-items-center justify-content-center">
			<img src="'.URL_IMAGES.'/listing-empty.svg" class="w-px-100" />
			<p>Chưa có người tham gia</p>
		</li>';
	}
	$html .= "</ul>";
	// Return
	echo $html;die;
}
function course_load_reports(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$course_id = (int) Input::post('course_id', 0);
	$oneCourse = $clsCourse->getOne($course_id);
	$cat_id = $oneCourse['cat_id'];
	$start_date = $oneCourse['start_date'];
	$due_date = $oneCourse['due_date'];
	$list_department_id = $oneCourse['list_department_id'];
	$arr_department_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$more_information = $oneCourse['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$_MEDIA_DISSEMINATION = ($cat_id == _MEDIA_DISSEMINATION) ? 1 : 0;
	$arr_property_cached = array();
	if($cat_id == _MEDIA_DISSEMINATION){
		$total_report_staffs = $total_unreport_staffs = 0;
		$list_report_staffs = $list_report_staffs_ids = array(); 
		$list_report_staffs = isset($more_information['upload_share']) 
			? $more_information['upload_share'] : array();
		if(!empty($list_report_staffs)){
			$total_report_staffs = count($list_report_staffs);
			$list_report_staffs_ids = array_keys($list_report_staffs);
			foreach($list_report_staffs as $key => $val){
				$staff_id = $val['profile_id'];
				if($staff_id == $profile_id) $check_upload = 1;
				$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
				$oProfile = $clsProfile->getOne($staff_id, $field);
				$department_id = $oProfile['department_id'];
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] =  $clsProperty->getTitle($department_id);
				}
				$list_report_staffs[$key]['full_name'] = $clsProfile->getFullName($staff_id, $oProfile);
				$list_report_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			}
		}
		$list_unreport_staffs = array();
		$cond = "`status_id`<>'"._STATUS_STAFF_OFF_ID."' and `profile_id` not in (".implode(',',_PROFILE_NOTIN_ID).")";
		if(!empty($list_report_staffs_ids))
			$cond.= " and {$clsProfile->pkey} not in (".implode(',', $list_report_staffs_ids).")";
		if($oneCourse['is_all_staff'] == 0){
			$list_profile_id = $oneCourse['list_profile_id'];
			$list_department_id = $oneCourse['list_department_id'];
			$arr_profile_ids = !empty($list_profile_id) 
				? $clsISO->getArrayByTextSlash($list_profile_id) : array();
			$arr_department_ids = !empty($list_department_id) 
				? $clsISO->getArrayByTextSlash($list_department_id) : array();
			if(!empty($arr_profile_ids) || !empty($arr_department_ids)){
				$cond.= " and ("; $hasCond = false;
				if(!empty($arr_department_ids)){ $ii = 0;
					$hasCond = true;
					foreach($arr_department_ids as $department_id){
						$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
						++$ii;
					}
				}
				if(!empty($arr_profile_ids)){
					$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
				}
				$cond.= ")";
			}
		}
		$field = "{$clsProfile->pkey},full_name,first_name,last_name,avatar,department_id";
		$list_unreport_staffs = $clsProfile->getAll($cond, $field);
		if(!empty($list_unreport_staffs)){
			$total_unreport_staffs = count($list_unreport_staffs);
			foreach($list_unreport_staffs as $key => $val){
				$department_id = $val['department_id'];
				if($department_id > 0 && !$arr_property_cached[$department_id]){
					$arr_property_cached[$department_id] =  $clsProperty->getTitle($department_id);
				}
				$list_unreport_staffs[$key]['department_name'] = $arr_property_cached[$department_id];
			}
		}
		$html_report_staffs = "";
		if(!empty($list_report_staffs)){ $ii = 1;
			foreach($list_report_staffs as $key => $val){
				$html_report_staffs.= '<tr class="tr">
					'.($deviceType!='phone' ? '<td class="text-center">'.$ii.'</td>': '').'
					<td class="text-left">'.$val['full_name'].'</td>
					<td class="text-left">'.$val['department_name'].'</td>
					<td class="text-left">'.$clsISO->convertTimeToText($val['time'],1).'</td>
					<td class="text-center">
						<a class="download" data-fancybox href="'.$val['image'].'">
							Xem kết quả
						</a>
					</td>
					<td class="text-center d-none">
						<div class="d-flex justify-content-between">
							'.($val['profile_id'] == $profile_id ? '<form action="" enctype="multipart/form-data">
								<input class="d-none" type="file" name="image" onChange="$Core.course.upload_image(this,event)"  data-course_id="'.$course_id.'" openFrom="_pop" data-type="report">
								<a href="javascript:void(0)" class="btn-link">Sửa</a>
							</form>' : '').'
						</div>
					</td>
				</tr>';
				++$ii;
			}
		}
		$html_unreport_staffs = "";
		if(!empty($list_unreport_staffs)){
			foreach($list_unreport_staffs as $key => $val){
				$html_unreport_staffs.= '<tr class="tr">
					'.($deviceType!='phone' ? '<td class="text-center">'.$ii.'</td>': '').'
					<td class="text-left">'.$val['full_name'].'</td>
					<td class="text-left">'.$val['department_name'].'</td>
				</tr>';
				++$ii;
			}
		}
		echo json_encode(array(
			'msg' => "_success",
			'_MEDIA_DISSEMINATION' => $_MEDIA_DISSEMINATION,
			'html_report_staffs' => $html_report_staffs,
			'html_unreport_staffs' => $html_unreport_staffs
		)); die();
	} else {
		$cond = "`t1`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		if($oneCourse['is_all_staff'] == 0){
			$list_profile_id = $oneCourse['list_profile_id'];
			$list_department_id = $oneCourse['list_department_id'];
			$arr_profile_ids = !empty($list_profile_id) 
				? $clsISO->getArrayByTextSlash($list_profile_id) : array();
			$arr_department_ids = !empty($list_department_id) 
				? $clsISO->getArrayByTextSlash($list_department_id) : array();
			if(!empty($arr_department_ids) || !empty($arr_profile_ids)){
				$cond.= " and ("; $hasCond = false;
				if(!empty($arr_department_ids)){ $ii = 0;
					$hasCond = true;
					foreach($arr_department_ids as $department_id){
						$cond.= ($ii==0 ? "": " or ")."(`t1`.`department_id`='{$department_id}' 
							or `t1`.`list_department_id` like '%|{$department_id}|%')";
						++$ii;
					}
				}
				if(!empty($arr_profile_ids)){
					$cond.= ($hasCond ? " or " : "") . "(``t1`.profile_id` in (".implode(',',$arr_profile_ids)."))";
				}
				$cond.= ")";
			}
		}
		$field = "`t1`.`profile_id`,`t1`.`code`,`t1`.`full_name`,`t1`.`avatar`,`t1`.`department_id`";
		$field.= ",`t1`.`role_id`,`t2`.`checked_in`,`t2`.`reg_date`,`t2`.`checked_in_date`";
		$list_staffs = $dbconn->getAll("select {$field} from {$clsProfile->tbl} as `t1` 
			inner join `{$clsCheckIn->tbl}` as `t2` on `t1`.`profile_id`=`t2`.`profile_id` 
			where `t2`.`event_id`='{$course_id}' and {$cond} order by `t2`.`reg_date` DESC");
		$html_checkin_staffs = "";
		$total_checkin_staffs = 0;
		if(!empty($list_staffs)){ $ii = 1;
			$total_checkin_staffs = count($list_staffs);
			foreach($list_staffs as $key => $val){
				$html_checkin_staffs.= '<tr class="tr">
					<td class="text-center">'.$ii.'</td>
					<td class="text-left">'.$clsProfile->getIndentityV5($val['profile_id'], $val).'</td>
					<td class="text-center">
						<i class="material-icons-outlined">more_time</i> 
						'.$clsISO->convertTimeToText($val['reg_date'], true).'
					</td>
					<td class="text-center">'.($val['checked_in'] ? '<span class="text-success">Đã check-In</span>' : '<span class="text-muted">Chưa check-In</span>').'</td>
					<td class="text-center">'.($val['checked_in'] ? '<i class="material-icons-outlined">more_time</i> 
						'.$clsISO->convertTimeToText($val['checked_in_date'], true) : '--').'
					</td>
				</tr>';
				++$ii;
			}
		}
		echo json_encode(array(
			'msg' => "_success",
			'_MEDIA_DISSEMINATION' => $_MEDIA_DISSEMINATION,
			'total_checkin_staffs' => $total_checkin_staffs,
			'html_checkin_staffs' => $html_checkin_staffs
		)); die();
	}
	// Return
}
function course_home_events(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsCache = new Cache();
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$arr_profile_groups = array();
	$cache_name = sprintf("_profile_group_%s_cached",$profile_id);
	if($clsCache->has($cache_name) && 1==2){
		$arr_profile_groups = $clsCache->get($cache_name);
	} else {
		$arr_profile_groups = array();
		$tmp = $clsGroupProfile->getAll("`list_profile_id` like '%|{$profile_id}|%'", $clsGroupProfile->pkey);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_profile_groups[] = $val[$clsGroupProfile->pkey];
			}
			unset($tmp);
		}
		// Save Cached
		//$clsCache->put($cache_name, $arr_profile_groups, 60*60);
	}
	$department_id = $oneProfile['department_id'];
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$arr_department_ids[] = $department_id;
	$arr_department_ids = array_unique($arr_department_ids);
	$cond = "and (`is_all_staff` = '1' OR ((`is_all_staff`='0' and (";
	foreach($arr_department_ids as $id){
		$cond.= ($ii==0 ? "": " or ")."`list_department_id` like '%|{$id}|%'";
		++$ii;
	}
	$sql_group = "";
	if(!empty($arr_profile_groups)){$ii = 0;
		foreach($arr_profile_groups as $group_id){
			$sql_group.= ($ii==0 ? "" : " or "). " `list_group_profile_id` like '%|{$group_id}|%'";
			++$ii;
		}
	}
	$cond .= " or `list_profile_id` LIKE '%|{$profile_id}|%' 
		or `user_id`='{$profile_id}'))".(!empty($arr_profile_groups)? " 
		or (`is_all_staff`=2 and (".$sql_group."))" : "")."))";
	$total_events = 0;
	// $dbconn->debug=true;
	$list_events = $clsCourse->getAll("`is_trash`=0 and `is_online`=1 and ((".time()." between `start_date` and `due_date`) or (`start_date` between ".time()." and ".strtotime("+5days").")) ".$cond." order by `start_date`,`due_date` asc");
	if(!empty($list_events)){
		$arr_property_cached = array();
		$total_events = count($list_events);
		foreach($list_events as $key => $val){
			$course_id = $val[$clsCourse->pkey];
			$cat_id = (int) $val['cat_id'];
			$start_date = $val['start_date'];
			$due_date = $val['due_date'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(!isset($arr_property_cached[$cat_id])){
				$arr_property_cached[$cat_id] = $clsProperty->getTitle($cat_id);
			}
			$list_events[$key]['cat_name'] = $arr_property_cached[$cat_id];
			$have_cancel = 1;
			if(time() > $start_date){
				$have_cancel = 0;
			}
			if ($cat_id == _MEDIA_DISSEMINATION){
				$upload_share = $core->get_field($more_information, "upload_share", []);
				$list_events[$key]['total_joined'] = !empty($upload_share) ? @count($upload_share) : 0;
				$is_joined = 0;
				if(!empty($upload_share)){
					foreach($upload_share as $okey => $oval){
						if($oval['profile_id'] == $profile_id){
							$is_joined = 1;
							break;
						}
					}
				}
				$list_events[$key]['is_joined'] = $is_joined;
				$list_events[$key]['upload_share'] = $upload_share;
			}else{
				$total_joined = $clsCheckIn->countItem("`event_id`='{$course_id}'");
				$list_events[$key]['total_joined'] = $total_joined;
				$is_joined = $is_checked_in = 0;
				$tmp = $clsCheckIn->getByCond("`event_id`='{$course_id}' and `profile_id`='{$profile_id}'");
				if(!empty($tmp)){
					$is_joined = 1;
					$is_checked_in = $tmp['checked_in'];
				}
			}
			$is_time_checkin = 0;
			if(time() >= strtotime("-15 minutes",$start_date)) {
				$is_time_checkin = 1;
			}
			if(time() < $start_date){
				$list_events[$key]['status'] = '<span class="badge bg-label-info">Sắp diễn ra</span>';
			}else if($start_date < time() && time() < $due_date){
				$list_events[$key]['status'] = '<span class="badge bg-label-success">Đang diễn ra</span>';
			}else{
				$list_events[$key]['status'] = '<span class="badge bg-label-danger">Đã diễn ra</span>';
			}
			$list_events[$key]['have_cancel'] = $have_cancel;
			$list_events[$key]['is_joined'] = $is_joined;
			$list_events[$key]['is_checked_in'] = $is_checked_in;
			$list_events[$key]['is_time_checkin'] = $is_time_checkin;
			#- Qúa hạn
			$is_expired = (time() > $due_date) ? 1 : 0;
			$list_events[$key]['is_expired'] = $is_expired;
		}
	}
	$smarty->assign("list_events",$list_events);
	// Return
	$callback = '';
	$html = $core->build('course'.DS.'_ajax.home_events.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback,
		'total_events' => $total_events
	)); die();
}
function course_checkin(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id,$oneProfile;
	$clsNotify = new Notify();
	$clsCourse = new Course();
	$clsCheckIn = new CheckIn();
	$clsProfile = new Profile();
	###
	$html = "";
	$toId = Input::post('toId');
	$tp = Input::post('tp', '_item');
	$holderG = Input::post('holderG');
	$course_id = (int) Input::post('course_id', 0);
	$oneCourse = $clsCourse->getOne($course_id);
	$user_id = $oneCourse['user_id']; // create event
	// Xác nhận tham gia
	if($holderG == 'confirm'){
		$checkin_id = $clsCheckIn->getMaxId();
		if($clsCheckIn->insert(array(
			'checkin_id' => $checkin_id,
			'profile_id' => $profile_id,
			'event_id' => $course_id,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$titleNoty = sprintf('<strong>%s</strong> đồng ý tham gia sự kiện 
			<strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $oneCourse['title']);
			$list_user_id = _PROFILE_RECEIVE_NOTIFY_EVENT_ID;
			if($profile_id != $user_id) {
				$list_user_id[] = $user_id;
			}
			$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, time(), $list_user_id);
			if(time() < strtotime("-15 minutes",$oneCourse["start_date"])) {
				$html = '<button type="button" class="btn btn-sm btn-primary text-nowrap btn_checkin" onClick="$Core.course.checkin(this,event)" course_id="'.$course_id.'" holderG="cancel" toId="'.$toId.'" openFrom="_pop"><i class="bx bx-user-check"></i> Đã xác nhận</button>
				<form action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="hid" value="hid" />
					<input type="file" class="d-none" id="upload_image_'.$toId.'" name="image" onChange="$Core.course.upload_image(this,event)" data-course_id="'.$course_id.'" openFrom="_pop" data-type="checkin">
					<button type="buton" openFrom="_pop" disabled toId="'.$toId.'" class="btn btn-sm btn-outline-danger text-nowrap"><i class="bx bx-user-check"></i> Check-In</button>
				</form>';
			}else{
				$html = '<button type="button" class="btn btn-sm btn-primary text-nowrap btn_checkin" onClick="$Core.course.checkin(this,event)" course_id="'.$course_id.'" holderG="cancel" toId="'.$toId.'" openFrom="_pop"><i class="bx bx-user-check"></i> Đã xác nhận</button>
				<form action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="hid" value="hid" />
					<input type="file" class="d-none" id="upload_image_'.$toId.'" name="image" onChange="$Core.course.upload_image(this,event)" data-course_id="'.$course_id.'" openFrom="_pop" data-type="checkin">
					<button type="buton" onClick="$Core.course.select_image(this, event)" openFrom="_pop" toId="'.$toId.'" class="btn btn-sm btn-outline-danger text-nowrap"><i class="bx bx-user-check"></i> Check-In</button>
				</form>';
			}
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Sự kiện - Đào tạo",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . $clsCourse->getLink($course_id)
			];
			$clsNotification->doPushMessagingUser($params,$list_user_id);
		}
	} else {
		// Hủy tham gia
		$clsCheckIn->deleteByCond("`profile_id`='{$profile_id}' and `event_id`='{$course_id}'");
		$titleNoty = sprintf('<strong>%s</strong> đã huỷ tham gia sự kiện 
		<strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $oneCourse['title']);
		$list_user_id = array_merge(_PROFILE_RECEIVE_NOTIFY_EVENT_ID, [$user_id]);
		$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, time(), $list_user_id);
		$html = '<button type="button" class="btn btn-sm btn-outline-primary btn_checkin" onClick="$Core.course.checkin(this,event)" course_id="'.$course_id.'" holderG="confirm" openFrom="_pop" toId="'.$toId.'"><i class="bx bx-user-check"></i> Xác nhận</button>';
		#thong bao app
		$clsNotification = new Notification();
		$params = [
			'title' => "Sự kiện - Đào tạo",
			'body' => strip_tags($titleNoty),
			'link' => PCMS_URL . $clsCourse->getLink($course_id)
		];
		$clsNotification->doPushMessagingUser($params,$list_user_id);
	}
	// Return
	echo $html; die();
}
function course_slide(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$title_page,$description_page;
	$clsTag = new Tag();
	$clsSlide = new Slide();
	$clsProfile = new Profile();
	$assign_list["clsTag"] = $clsTag;
	$assign_list["clsSlide"] = $clsSlide;
	##
	$show = Input::get('show',"all");
	if($show=='tag'){
		$tag_id = (int) Input::get('tag_id',0);
	}else if($show=='detail'){
		$slide_id = (int) Input::get('slide_id',0);
		$oneSlide = $clsSlide->getOne($slide_id);
		$scriptJs = "";
		if($oneSlide['slide_type'] == 'url'){
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.$oneSlide['link'].'" data-fancybox data-type="iframe" title="Đọc nhanh"></a>';
		}else if($oneSlide['slide_type'] == 'upload'){
			if($clsSlide->isPdf(DOMAIN_URL.$oneSlide['link'])) {
				$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.DOMAIN_URL.$oneSlide['link'].'" data-fancybox data-type="iframe" title="Đọc nhanh"></a>';
			}else{
				$scriptJs.= '<a class="autoclick_'.$slide_id.'" target="_blank" href="https://view.officeapps.live.com/op/view.aspx?src='.DOMAIN_URL.$oneSlide['link'].'" title="Đọc nhanh"></a>';
			}
		}else if($oneSlide['slide_type'] == 'post' || $oneSlide['slide_type'] == 'image') {
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" target="_blank" href="javascript:void()" onClick="$Core.slide.view(this, event)" slide_id="'.$slide_id.'" title="Đọc nhanh"></a>';
		}else if($oneSlide['slide_type'] == 'video') {
			$scriptJs.= '<a class="autoclick_'.$slide_id.'" href="'.DOMAIN_URL.$oneSlide['link'].'" data-fancybox title="Xem nhanh"></a>';
		}
		$scriptJs.= '
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					//history.pushState("", "", "/hoc-tap.html");
					$(\'.autoclick_'.$slide_id.'\').trigger(\'click\');
				}, 500);
			})
		</script>';
		$assign_list["scriptJs"] = $scriptJs;
	}
	$assign_list["show"] = $show;
	$assign_list["tag_id"] = $tag_id;
	/*=============Title & Description Page==================*/
	$title_page = 'Kho tài liệu - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function course_list_slide(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id,$clsProfile;
	// ini_set('display_errors',1);
	$clsSlide = new Slide();
	$smarty->assign('clsSlide', $clsSlide);
	###
	$show = Input::post('show', "");
	$tag_id = (int) Input::post('tag_id',0);
	$keySearch= Input::post('keySearch',"");
	$cat_id = (int) Input::post('cat_id',0);
	$user_id = (int) Input::post('user_id',0);
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',12);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('page', $current_page);
	###
	$cond = "is_trash=0";
	if($cat_id > 0) $cond.= " and (`cat_id`='{$cat_id}' or `list_cat_id` like '%|{$cat_id}|%')";
	if($user_id > 0) $cond.= " and (`user_id`='{$user_id}')";
	if($show=='tag' && $tag_id > 0) $cond.= " and list_tag_id like '%|{$tag_id}|%'";
	if(!empty($keySearch)) $cond.= " and (slug like '%{$core->replaceSpace($keySearch)}%')";
	$total_record = $clsSlide->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$list_slides = $clsSlide->getAll("{$cond} order by reg_date DESC".$limitCond);
	if(!empty($list_slides)){
		$arr_profile_cached = array();
		foreach($list_slides as $key => $val){
			$user_id = $val['user_id'];
			$slide_type = $val['slide_type'];
			$link = $val['link'];
			if($slide_type == 'upload'){
				$link = str_replace(DOMAIN_URL, '', $link);
				$link = DOMAIN_URL. $link;
				$list_slides[$key]['link'] = $link;
			}
			if(isset($arr_profile_cached[$user_id])){
				$list_slides[$key]['oneProfile'] = $arr_profile_cached[$user_id];
			} else {
				$field = "full_name,first_name,last_name,avatar,department_id";
				$arr_profile_cached[$user_id] = $clsProfile->getOne($user_id, $field);
				$list_slides[$key]['oneProfile'] = $arr_profile_cached[$user_id];
			}
		}
	}
	$smarty->assign('list_slides', $list_slides);
	// $clsISO->print_pre($list_slides); die();
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('course'.DS.'_ajax.slide.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'per_page' => $per_page,
	)); die();
}
function course_open_slide(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id;
	// ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsTag = new Tag();
	$clsSlide = new Slide();
	$uid = $clsISO->getUniqid();
	$slide_id = (int) Input::post('slide_id',0);
	$smarty->assign('uid', $uid);
	$smarty->assign('slide_id', $slide_id);
	$list_type = array(
		'video' => 'Video',
		'url' => 'URL',
		'upload' => 'Upload File',
		'post'	=> 'Bài viết',
		'image' => 'Hình ảnh'
	);
	$smarty->assign('list_type', $list_type);
	###
	$action = "_add";
	$oneSlide = array("slide_type" => 'video', 'cat_id' => 0);
	if($slide_id > 0){
		$action = "_edit";
		$oneSlide = $clsSlide->getOne($slide_id);
		$list_tag_id = $oneSlide['list_tag_id'];
		$tags_arrs = $clsISO->getArrayByTextSlash($list_tag_id);
		$html_tags = $clsTag->getTags($tags_arrs);
	} else {
		$html_tags = "";
	}
	$smarty->assign('action', $action);
	$smarty->assign('html_tags', $html_tags);
	$smarty->assign('oneSlide', $oneSlide);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('course'.DS.'_ajax.slide.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'slide_id' => $slide_id,
		'slide_type' => $oneSlide['slide_type']
	)); die();
}
function course_search_tag(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id;
	$clsTag = new Tag();
	###
	$results = array();
	$list_tags = $clsTag->getAll("is_trash=0");
	if(!empty($list_tags)){
		foreach($list_tags as $key => $val){
			$results[] = array(
				'id' => $val[$clsTag->pkey],
				'text' => $val['title']
			);
		}
		unset($list_tags);
	}
	// Return
	echo json_encode($results); die();
}
function course_load_content(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile, $clsProfile;
	$clsSlide = new Slide();
	###
	$slide_id = (int) Input::post('slide_id', 0);
	$slide_type = Input::post('slide_type', 'video');
	$oneSlide = array('link' => '', 'content' => "");
	if($slide_id > 0){
		if(in_array($slide_type, array('video','url','upload'))){
			$field = "link";
		} else if($slide_type=='post'){
			$field = "content";
		} else if($slide_type=='image'){
			$field = "content,images";
		}
		$oneSlide = $clsSlide->getOne($slide_id, $field);
	}
	$html= '';
	if($slide_type=='video' || $slide_type=='url'){
		$html.= '<div class="formgroup mb-2">
			<label class="form-label mb-1">Tài liệu</label>
			<input type="text" class="form-control" name="link" value="'.$oneSlide['link'].'" 
			placeholder="'.($slide_type=='video'?'https://youtube.com/':'Nhập URL').'">
		</div>';
	} else if($slide_type=='upload'){
		$html.= '<div class="formgroup mb-2">
			<label class="form-label mb-1">Tài liệu</label>';
			if(!empty($oneSlide['link']) && file_exists(ABSPATH.$oneSlide['link'])){
				$html.= '<div class="mb-2">
					<a class="label bg-primary" href="'.$oneSlide['link'].'" target="_blank">'.$oneSlide['link'].'</a>
				</div>';
			}
			$html.= '<input type="file" class="form-control" name="file_upload">
				<div class="form-text">Tài liệu có thể là file .doc,.docx,.xls,.xlsx,.pdf</div>
		</div>';
	} else if($slide_type=='post'){
		$html.= '<div class="formgroup mb-2">
			<label class="form-label mb-1">Tài liệu</label>
			<textarea data-name="content" class="form-control isoTextArea" id="'.$clsISO->getUniqid().'" rows="20" cols="255">'.$oneSlide['content'].'</textarea>
		</div>';
	} else if($slide_type=='image'){
		$uid = $clsISO->getUniqid();
		$images = $oneSlide['images'];
		$images_arrs = !empty($images) 
			? json_decode(html_entity_decode($images), true) 
			: array();
		$html_images = "";
		if(!empty($images_arrs)){
			foreach($images_arrs as $image){
				if(!empty($image) && file_exists(ABSPATH.$image)){
					$html_images.= '<span class="item">
						<img src="'.$image.'" />
						<input type="hidden" name="images[]" value="'.$image.'" />
						<a href="javascript:void(0)" title="Xóa" class="delete" src="'.$image.'" onClick="$Core.upload.delete(this, event)"></a>
					</span>';
				}
			}
		}
		$html.= '<div class="formgroup mb-2">
			<label class="form-label mb-1">Nội dung</label>
			<textarea data-name="content" class="form-control isoTextArea" id="'.$clsISO->getUniqid().'" rows="5" cols="255">'.$oneSlide['content'].'</textarea>
		</div>
		<div class="formgroup mb-2">
			<label class="form-label mb-1">Tài liệu</label>
			<div class="we-filedrop-wrapper">
				<input id="'.$uid.'" uid="'.$uid.'" class="d-none upload_image_slide" accept="image/*,.heic,.heif" multiple="multiple" name="images[]" type="file" tabindex="-1">
				<div class="we-filedrop mb-1" uid="'.$uid.'" onclick="$Core.slide.upload_slide(this,event);"> 
					<svg class="mb-2" width="70" height="70" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path><path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path></svg> 
					<p class="mb-0">Bấm để chọn một hoặc nhiều hình ảnh cần tải lên !!!</p>
				</div>
				<div id="imageList_'.$uid.'" class="d-flex flex-wrap box-done-img">'.$html_images.'</div> 
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function course_pop_save_slide(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id;
	$clsTag = new Tag();
	$clsSlide = new Slide();
	$clsProperty = new Property();
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$slide_id = (int) Input::post('slide_id', 0);
	$msg = "_error";
	if(isset($_POST['submit']) && $_POST['submit'] == 'Insert'){
		$more = array();
		$tags = Input::post('tags');
		$title = Input::post('title');
		$cat_id = (int) Input::post('cat_id', 0);
		$slide_type = Input::post('slide_type', 'video');
		if(!empty($tags)){
			$list_tag_id = array();
			$tags = explode(',', $tags);
			foreach($tags as $tag){
				$tmp = $clsTag->getByCond("slug='{$core->replaceSpace($tag)}'");
				if(!empty($tmp)){
					$tag_id = $tmp[$clsTag->pkey];
				} else {
					$tag_id = $clsTag->getMaxId();
					$clsTag->insert(array(
						$clsTag->pkey => $tag_id,
						'title' => $tag,
						'slug' => $core->replaceSpace($tag)
					));
				}
				$list_tag_id[] = $tag_id;
			}
			$more['list_tag_id'] = $clsISO->makeSlashListFromArrayRoot($list_tag_id);
		} else {
			$more['list_tag_id'] = "";
		}
		if($cat_id > 0){
			$list_cat_id = $clsProperty->getListParent($cat_id);
			$more['list_cat_id'] = $list_cat_id;
		}
		###
		if($slide_type=='url' || $slide_type=='video'){
			$more['link'] = Input::post('link');
		} else if($slide_type=='post') {
			$more['content'] = Input::post('content');
		} else if($slide_type=='image'){
			$content = Input::post('content');
			$images = Input::post('images', array());
			$more['content'] = $content;
			if(!empty($images)) $more['images'] = json_encode($images, JSON_UNESCAPED_UNICODE);
		} else if($slide_type=='upload') {
			if(!empty($_FILES['file_upload']['name'])){
				$clsUploadFile = new UploadFile();
				$allowExt = "doc,docx,xls,xlsx,pdf,jpg,gif,png";
				$file_upload = $clsUploadFile->uploadItem($_FILES['file_upload'],"/slide",$allowExt);
				if(!empty($file_upload) && @file_exists(ABSPATH . $file_upload)){
					$more['link'] = $file_upload;
				}
			}
		}
		if($slide_id > 0){
			// $clsSlide->setDebug(true);
			if($clsSlide->updateOne($slide_id, array_merge($more, array(
				'title' => $title,
				'slug' => $core->replaceSpace($title),
				'cat_id' => $cat_id,
				//'image' => Input::post('image'),
				'slide_type' => $slide_type,
				'upd_date' => time(),
				'user_id_update' => $profile_id
			)))){
				$msg = "_success";
			}
		} else {
			$slide_id = $clsSlide->getMaxId();
			// $clsSlide->setDebug(true);
			if($clsSlide->insert(array_merge($more, array(
				$clsSlide->pkey => $slide_id,
				'slide_type' => $slide_type,
				'title' => $title,
				'slug' => $core->replaceSpace($title),
				//'image' => Input::post('image'),
				'cat_id' => $cat_id,
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id
			)))){
				$current_Now = time();
				$lstProfile = $clsProfile->getAll("is_active=1 and is_verified=1 and is_trash=0 and profile_id <> '{$profile_id}'",$clsProfile->pkey);
				$arr_profile = [];
				foreach($lstProfile as $key => $value){
					$arr_profile[] = $value['profile_id'];
				}
				$titleNoty = sprintf('<strong>%s</strong> đã thêm ['.strtoupper($slide_type).'] <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProfile->getFullName($profile_id), $title, $clsISO->convertTimeToText($current_Now));
				$clsNotify->insertNotify('Slide',$clsSlide->pkey, $slide_id, $titleNoty, $current_Now, $arr_profile);
				$msg = "_success";
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Sự kiện - Đào tạo",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . sprintf('/hoc-tap/%s.html', $slide_id)
				];
				$clsNotification->doPushMessagingUser($params,$arr_profile);
			}
		}
	}
	// Return
	echo $msg; die();
}
function course_view_slide(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile, $clsProfile;
	$clsSlide = new Slide();
	$uid = $clsISO->getUniqid();
	$slide_id = (int) Input::post('slide_id',0);
	$smarty->assign('uid', $uid);
	$smarty->assign('slide_id', $slide_id);
	$smarty->assign('clsSlide', $clsSlide);
	$oneSlide = $clsSlide->getOne($slide_id);
	$user_id = $oneSlide['user_id'];
	$slide_type = $oneSlide['slide_type'];
	if($user_id==$profile_id){
		$oneSlide['oneProfile'] = $oneProfile;
	} else {
		$field = "full_name,first_name,last_name,avatar,department_id";
		$oneSlide['oneProfile'] = $clsProfile->getOne($user_id, $field);
	}
	$list_images = array();
	if($slide_type=='image'){
		$images = $oneSlide['images'];
		$list_images = !empty($images) 
			? json_decode(html_entity_decode($images)) 
			: array();
	}
	$smarty->assign('list_images', $list_images);
	$smarty->assign('slide_type', $slide_type);
	$smarty->assign('oneSlide', $oneSlide);
	// Return
	$html = $core->build('course'.DS.'_ajax.view.slide.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function course_delete_image(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	###
	if(Input::exists('src')){
		$file_src = Input::post('src');
	} else {
		$file_src = Input::post('image');
	}
	if(!empty($file_src) && file_exists(ABSPATH . $file_src)){
		unlink(ABSPATH . $file_src);
	}
	// Return
	echo(1); die();
}
function course_upload_image_slide(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	###
	$msg = '_error'; $html = '';
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$uid = Input::post('uid');
		if(!empty($_FILES['image']['name'])){
			if(is_uploaded_file($_FILES['image']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$image = $clsUploadFile->uploadItem($_FILES["image"],"/slide","jpeg,jpg,gif,png");
				if(!empty($image) && file_exists(ROOTPATH.$image)){
					$msg = '_success';
					$html = '<span class="item">
						<input type="hidden" name="images[]" value="'.$image.'" />
						<a href="javascript:void(0);" class="delete" src="'.$image.'" uid="'.$uid.'" onClick="$Core.upload.delete(this, event)"></a>
						<img class="img-responsive" style="max-width:100%;" src="'.$image.'" />
					</span>';
				}
			}
		}
	}
	// return
	echo sprintf('%s|||%s', $msg, $html); die();
}
function course_delete_slide(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO;
	global $profile_id;
	$clsSlide = new Slide();
	$slide_id = (int) Input::post('slide_id', 0);
	###
	$msg = "_error";
	if($slide_id > 0){
		$oneSlide = $clsSlide->getOne($slide_id);
		$slide_type = $oneSlide['slide_type'];
		if($slide_type=='upload'){
			if(!empty($oneSlide['link']) && @file_exists(ABSPATH.$oneSlide['link'])){
				@unlink(ABSPATH.$oneSlide['link']);
			} 
		} else if($slide_type=='image'){
			$images = $oneSlide['images'];
			$images_arrs = !empty($images) 
				? json_decode(html_entity_decode($images), true) : array(); 
			if(!empty($images_arrs)){
				foreach($images_arrs as $image){
					if(!empty($image) && @file_exists(ABSPATH.$image)){
						@unlink(ABSPATH . $image);
					}
				}
			}
		}
		if($clsSlide->deleteOne($slide_id)){
			$msg = '_success';
		}
	}
	// Return
	echo $msg; die();
}
function course_ns_club(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsCourse = new Course(); 
	$clsProfile = new Profile(); 
	$clsGroupProfile = new GroupProfile(); 
	$assign_list['clsCourse'] = $clsCourse;
	$start_date = strtotime("-4 days"); 
	$start_date = strtotime(date("d-m-Y", $start_date));
	$end_date = strtotime(date("d-m-Y 23:59:00")); 
	$assign_list["start_date"] = $start_date;
	$assign_list["end_date"] = $end_date;
	$lstDate = [];
	for($i = $start_date; $i <= $end_date; $i+=86400) {
		$due_date = strtotime(date("d-m-Y 23:59:59",$i));
		$lstGourse = $clsCourse->getAll("list_group_profile_id LIKE '|%"._GROUP_NS_CLUB."%|' AND ((start_date BETWEEN {$i} AND {$due_date}) OR (due_date BETWEEN {$i} AND {$due_date}))",$clsCourse->pkey.',title,more_information,start_date,due_date');
		foreach ( $lstGourse as $key => $value ) {
			$arr_upload = [];
			$more_information = $clsISO->to_array_json($value['more_information']);
			$upload_share = !empty($more_information['upload_share']) ? $more_information['upload_share'] : array();
			foreach ($upload_share as $k_upload => $v_upload) {
				if(strtotime(date("d-m-Y",$v_upload['time'])) == $i){
					$arr_upload[] = $v_upload['profile_id'];
				}				
			}		
			$lstGourse[$key]['profile_ids'] = $arr_upload;
			unset($upload_share,$more_information,$min_date,$max_date);
		}
		$lstDate[] = [
			'time'	=>	$i,
			'lstCourse'	=>	$lstGourse
		];
		unset($lstGourse);		
	}
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list['list_preloaders'] = $list_preloaders;
	$assign_list["lstDate"] = $lstDate;
    /*=============Title & Description Page==================*/
	$title_page = 'Báo cáo lan tỏa CLB Ngôi Sao - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function course_load_report_course(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$smarty;
	$clsGroupProfile = new GroupProfile(); 
	$clsProfile = new Profile(); 
	$clsCourse = new Course(); $assign_list['clsCourse'] = $clsCourse;
	$start_date = input::post("start_date"); 
	$end_date = input::post("end_date"); 
	$start_date = strtotime(str_replace("-","/",$start_date));
	$end_date = strtotime(str_replace("-","/",$end_date)." 23:59:59");
	$lstDate = [];
	$total_col = 4;
	for($i = $start_date; $i <= $end_date; $i+=86400) {
		$due_date = strtotime(date("d-m-Y 23:59:59",$i));
		$lstGourse = $clsCourse->getAll("list_group_profile_id LIKE '|%"._GROUP_NS_CLUB."%|' AND FROM_UNIXTIME (start_date,'%d/%m/%Y') = '".date("d/m/Y",$i)."'",$clsCourse->pkey.',title,more_information,start_date,due_date');
		foreach ( $lstGourse as $key => $value ) {
			$arr_upload = [];
			$more_information = $clsISO->to_array_json($value['more_information']);
			$upload_share = !empty($more_information['upload_share']) ? $more_information['upload_share'] : array();
			foreach ($upload_share as $k_upload => $v_upload) {
				if(strtotime(date("d-m-Y",$v_upload['time'])) == $i){
					$arr_upload[] = $v_upload['profile_id'];
				}				
			}		
			$lstGourse[$key]['profile_ids'] = $arr_upload;
			unset($upload_share,$more_information,$min_date,$max_date);
		}
		$total_col += count($lstGourse);
		$lstDate[] = [
			'time'	=>	$i,
			'lstCourse'	=>	$lstGourse
		];
		unset($lstGourse);		
	}
	$oneGroup = $clsGroupProfile->getOne(_GROUP_NS_CLUB);
	$assign_list['oneGroup'] = $oneGroup;
	$profile_ids = $clsISO->getArrayByTextSlash($oneGroup["list_profile_id"]);
	if(!empty($profile_ids)) {
		$lstProfile = $clsProfile->getAll($clsProfile->pkey . " IN (" . implode(',',$profile_ids). ") ",$clsProfile->pkey.",full_name");
		$smarty->assign("lstProfile",$lstProfile);
	}
	$smarty->assign("total_col",$total_col);
	$smarty->assign("lstDate",$lstDate);
	$assign_list["lstGourse"] = $lstGourse;
	$html = $core->build("course".DS."_ajax.load_ns_club.tpl");
    echo $html;die;
}
function course_event(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile;
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$cat_id = _CAT_EVENT_ID;
	$assign_list["cat_id"] = $cat_id;
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	if($cmd=="_detail"){
		$course_id = Input::get('course_id', 0);
		$scriptJs.= '<a class="autoclick_'.$course_id.'"" course_id="'.$course_id.'" 
		onClick="$Core.course.open(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$course_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
	}
	$assign_list["scriptJs"] = $scriptJs;
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$permiss_add = $clsISO->checkPermission("create_course");
	$permiss_edit = $clsISO->checkPermission("edit_code");
	$assign_list["permiss_add"] = $permiss_add;
	$assign_list["permiss_edit"] = $permiss_edit;
	###
	$list_preloaders = array();
	for($i= 0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
    /*=============Title & Description Page==================*/
	$title_page = 'Khóa học/Đào tạo - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function course_open_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsCache = new Cache();
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$arr_profile_groups = array();
	$course_id = Input::post("course_id",0);
	// Return
	$callback = '';
	$smarty->assign('course_id', $course_id);
	$html = $core->build('course'.DS.'_ajax.open_report.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'callback' => $callback,
		'total_events' => $total_events
	)); die();
}
function course_save_report(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsCache = new Cache();
	$clsCourse = new Course();
	$clsProfile = new Profile(); 
	$clsProperty = new Property();
	$clsCheckIn = new CheckIn();
	$clsUploadFile = new UploadFile();
	$clsGroupProfile = new GroupProfile();
	$clsNotify = new Notify();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$arr_profile_groups = array();
	$course_id = Input::post("course_id",0);
	$link = Input::post("link","");
	$msg = "error";
	// Return
	$current_Now = time();
	$oCourse = $clsCourse->getOne($course_id, "title,user_id,more_information");
	$user_id = $oCourse['user_id']; // Create event Id
	$more_information = $oCourse['more_information'];
	$more_information = $clsISO->to_array_json($more_information);	
	if(is_uploaded_file($_FILES['image']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		$image = $clsUploadFile->uploadItem($_FILES["image"],"/MEDIA_DISSEMINATION",EXTENSION_FILE_UPLOAD);
		if(!empty($image) && file_exists(ROOTPATH . $image)){
			$msg = "_success";
			// Set the file metadata for drive
			$filename = $_FILES["image"]["name"];
			$mimeType = $_FILES["image"]["type"];
			$clsGoogleDrive = new GoogleDrive();
			$createdFile = $clsGoogleDrive->upload($filename, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_EVENT_ID);
			$upload_file = sprintf('https://drive.google.com/file/d/%s/view', $createdFile->getId());
			$upload_image = sprintf("https://drive.google.com/thumbnail?id=%s&sz=w1000", $createdFile->getId());
			$upload_share = isset($more_information['upload_share']) && !empty($more_information['upload_share']) 
					? $more_information['upload_share'] : array();
			$upload_share[$profile_id] = array(
				'profile_id' 	=> $profile_id,
				'driver_image'	=>	$upload_file,
				'image'			=>	$upload_image,
				'link'			=>	$link,
				'time'			=>	time()
			);
			$more_information['upload_share'] = $upload_share;
			if($clsCourse->updateOne($course_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$msg.="|||<span class=\"btn btn-sm btn-primary\"><i class='bx bx-user-check'></i> Đã báo cáo</span>";
				$oneCourse = $clsCourse->getOne($course_id,"title,user_id");
				$titleNoty = sprintf('<strong>%s</strong> đã báo cáo chia sẻ sự kiện lan toả truyền thông <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProfile->getFullName($profile_id), $oCourse['title'], $clsISO->convertTimeToText($current_Now));
				$list_user_id = [];
				if($profile_id != $user_id) {
					$list_user_id[] = $user_id;
				}
				$clsNotify->insertNotify('Course',$clsCourse->pkey, $course_id, $titleNoty, $current_Now, $list_user_id);
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "Sự kiện - Đào tạo",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . $clsCourse->getLink($course_id)
				];
				$clsNotification->doPushMessagingUser($params,$list_user_id);
			}
			@unlink(ROOTPATH . $image);
		}
	}
	echo $msg; die();
}