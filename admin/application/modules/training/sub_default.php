<?php
function default_default(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$keyword = Input::post('keyword');		
		$cat_id = (int)Input::post('cat_id',0);		
		$link = '';
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		if(!empty($cat_id)) $link .= '&cat_id='.$cat_id;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
	}
	
	$classTable = "Training";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*List all item*/
	$cond = "1='1'";
	#Filter By Keyword
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	$type_list = Input::get('type_list');
	$cat_id = (int)Input::get('cat_id',0);
	$assign_list["keyword"] = $keyword;
	$assign_list["cat_id"] = $cat_id;
	$assign_list["type_list"] = $type_list;
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and (slug like '%".$keyword."%' OR title LIKE '%{$keyword}%')";
	}
	if(!empty($cat_id)){
		$cond .= " and (cat_id='{$cat_id}' OR list_cat_id LIKE '%|{$cat_id}%|')";
	}
	$assign_list["pUrl"] = $pUrl;
	$cond2 = $cond;
	if($type_list == "Trash"){
		$cond .= " and is_trash = '1'";
	}
	$orderBy = " reg_date desc";
	#-------Page Divide--------
	$recordPerPage 	= 20;
	$currentPage = isset($_GET["page"])? $_GET["page"] : 1;
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";
	$lstAllItem = $clsClassTable->getAll($cond);
//	$clsISO->print_pre($lstAllItem);die;
	$totalRecord = (is_array($lstAllItem)&&count($lstAllItem)>0)?count($lstAllItem):0;
	$totalPage = ceil($totalRecord / $recordPerPage);
	$assign_list['totalRecord'] = $totalRecord;
	$assign_list['recordPerPage'] = $recordPerPage;
	$assign_list['totalPage'] = $totalPage;
	$assign_list['currentPage'] = $currentPage;
	$listPageNumber =  array();
	for ($i=1; $i<=$totalPage; $i++){
		$listPageNumber[] = $i;
	}
	$assign_list['listPageNumber'] = $listPageNumber;
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	$link_page_current = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current'] = $link_page_current;
	#
	$link_page_current_2 = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page'&&$tmp[0]!='type_list')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;
	#-------End Page Divide-----------------------------------------------------------
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); //print_r($cond." order by ".$orderBy.$limit);die();
	$assign_list["allItem"] = $allItem;
	#
	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"] = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_edit(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsConfiguration,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	$clsGroupProfile = new GroupProfile(); $assign_list["clsGroupProfile"] = $clsGroupProfile;
	$clsTraining = new Training();
	$clsProperty = new Property();
	$assign_list["clsTraining"] = $clsTraining;
	$training_id = (int) Input::get('training_id',0);
	$string = isset($_GET[$clsTraining->pkey])? ($_GET[$clsTraining->pkey]) : '';
	$training_id = !empty($string) ? intval($core->decryptID($string)) : 0;
	$assign_list["training_id"] = $training_id;
	$oneItem = $clsTraining->getOne($training_id);
//	$clsISO->print_pre($oneItem);die;
	$assign_list["oneItem"] = $oneItem;
	if(empty($oneItem)) {
		header('location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');exit();
	}	
	$lstLesson = $clsISO->to_array_json($oneItem['lesson']);
	$assign_list["lstLesson"] = $lstLesson;
	
	$field = "{$clsProfile->pkey},first_name,last_name,full_name";
	$list_profiles = $clsProfile->getAll("is_trash=0 and status_id<>'"._STATUS_STAFF_OFF_ID."' 
	and profile_id not in(".implode(',',_PROFILE_NOTIN_ID).") order by code ASC", $field);
	$assign_list["list_profiles"] = $list_profiles;
//	$clsISO->print_pre($list_profiles);die;
	
	$list_profile_id = $oneItem['list_profile_id'];
	$list_department_id = $oneItem['list_department_id'];
	$arr_profile_ids = !empty($list_profile_id) 
		? $clsISO->getArrayByTextSlash($list_profile_id) : array();
	$arr_departments_ids = !empty($list_department_id) 
		? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$assign_list['arr_profile_ids'] = $arr_profile_ids;
	$assign_list['arr_departments_ids'] = $arr_departments_ids;
	
	$lstGroupProfile = $clsGroupProfile->getAll("is_trash=0 and is_online=1 ORDER BY upd_date DESC");
	$assign_list["lstGroupProfile"] = $lstGroupProfile;
	
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form();
	$clsForm->setDbTable("Training",$clsTraining->pkey,$training_id);
	$assign_list["clsForm"] = $clsForm;
	$clsForm->addInputTextArea("full",'content',"",'content',255,25,10,1,"style='width:100%'");
	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
//		$clsISO->print_pre($_POST);die;
		$arr_data = [];
		if($training_id>0){
			
			$is_all_staff = Input::post('is_all_staff',0);
			$is_online = Input::post('is_online',0);
			$cat_id = (int)Input::post('cat_id',0);
			$list_cat_id = $clsProperty->getListParent($cat_id,"_TRAINING_CAT");
			$arr_data = [
				"upd_date"			=>	time(),
				"user_update_id"	=>	$user_id,
				"cat_id"			=>	$cat_id,
				"list_cat_id"		=>	$list_cat_id,
				"slug"				=>	$core->replaceSpace(Input::post('iso-title')),
				"is_all_staff"		=>	$is_all_staff,
				"is_online"			=>	$is_online,
			];
			if($is_all_staff==0){				
				$list_department_id = Input::post('list_department_id');
				$list_profile_id = Input::post('list_profile_id');
				$list_group_profile_id = Input::post('list_group_profile_id');
				$list_department_id = !empty($list_department_id) ? $clsISO->makeSlashListFromArrayRoot($list_department_id) : "";
				$list_profile_id = !empty($list_profile_id) ? $clsISO->makeSlashListFromArrayRoot($list_profile_id) : "";
				$list_group_profile_id = !empty($list_group_profile_id) ? $clsISO->makeSlashListFromArrayRoot($list_group_profile_id) : "";
				$arr_data["list_department_id"] = $list_department_id; 
				$arr_data["list_profile_id"] = $list_profile_id; 				
				$arr_data["list_group_profile_id"] = $list_group_profile_id; 
			}
			$set = ""; $firstAdd = 0;
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					$arr_data[$tmp[1]] = addslashes($val);
				}
			}
			#
			#--Special Field: image
			$image = Input::post('isoman_url_image');
			if(!empty($image)){
				$arr_data["image"] = $image; 
			}
//			 $clsISO->print_pre($arr_data); die();
//			$clsTraining->setDeBug(1);
//			$clsTraining->updateOne($training_id,$arr_data);die;
			if($clsTraining->updateOne($training_id,$arr_data)) {
				if($_POST['button']=='_EDIT'){
					header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&training_id='.$core->encryptID($training_id).'&message=updateSuccess');
					exit();
				}else{
					header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateSuccess');
					exit();
				}
			} else{
				header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateFailed');
				exit();
			}
		}
	}
}
function default_trash(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Training";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';	
	if($pvalTable == "")
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');

	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
	}
}
function default_restore(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Training";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';	
	if($pvalTable == "")
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=notPermission');

	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
	}
}
function default_delete(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Training";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if($string = '' && $pvalTable == 0)
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		
	if(isset($_POST['agree']) && $_POST['agree']=='agree'){
		if($clsClassTable->doDelete($pvalTable)){
			header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		}
	}
}
function default_addTraining(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsTraining = new Training();
	$type = Input::post("type","_OPEN");
	$title = Input::post("title",""); 
	$user_id = $core->_USER['user_id'];
	if($type == "_OPEN") {
		$html = $core->build('_ajax.open_training.tpl');
		$uid = $clsISO->getUniqid();
		$data = [
			'result'	=>	true,
			'uid'	=>	$uid,
			'html' => $html,
		];
	}else if($type == "_SAVE"){
		$data = [
			"result"	=>	false,
			"msg"		=>	"ERROR!"
		];
		if(trim($title) != "" && $core->replaceSpace($title) != "") {			
			$check = $clsTraining->countItem("slug='".$core->replaceSpace($title)."'");
			if($check > 0) {
				$data = [
					"result"	=>	false,
					"msg"		=>	"Tiêu đề khóa học đã tồn tại!"
				];
			}else{
				$training_id = $clsTraining->getMaxID();
				$arr_data = [
					'training_id'	=>	$training_id,
					'title'			=>	$title,
					'user_id'		=>	$user_id,
					'slug'			=>	$core->replaceSpace($title),
					'reg_date'		=>	time(),
					'is_online'		=>	1,
				];
				$dbconn->debug = true;
				if($clsTraining->insert($arr_data)) {
					$data = [
						"result"	=>	true,
						"msg"		=>	"SUCCESS!",
						"link"		=>	PCMS_URL.'?mod='.$mod.'&act=edit&training_id='.$core->encryptID($training_id )
					];
				}
			}			
		}		
	}
	echo json_encode($data); die();
}
function default_open_lesson(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsTraining = new Training();
	$training_id = (int)Input::post("training_id",0);
	$lesson_id = Input::post("lesson_id",""); 
	$assign_list['field_id'] = $field_id;
	$data=["result"	=>	false];
	
	$uid = $clsISO->getUniqid();
	if($training_id > 0){
		if($lesson_id != ""){
			$oneTraining = $clsTraining->getOne($training_id);
			$lesson = $clsISO->to_array_json($oneTraining['lesson']);
			if(isset($lesson[$lesson_id])) {
				$oneItem = $lesson[$lesson_id];
				$assign_list['oneItem'] = $oneItem;
			}
		}else{
			$lesson_id = $uid;
		}
		$assign_list['uid'] = $uid;
		$assign_list['lesson_id'] = $lesson_id;
		$assign_list['training_id'] = $training_id;
		$html = $core->build('_ajax.open_lesson.tpl');
		$data = [
			'result'	=>	true,
			'uid'	=>	$uid,
			'html' => $html,
		];
	}else{
		$data = [
			'result'	=>	false,
		];
	}
	echo json_encode($data); die();
}
function default_save_lesson(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsTraining = new Training();
	$training_id = (int)Input::post("training_id",0);
	$lesson_id = Input::post("lesson_id",""); 
	$title = Input::post("title",""); 
	$content = Input::post("content",""); 
	$video = Input::post("video",""); 
	$file = Input::post("file",""); 
	$point = (int)Input::post("point",0); 
	$assign_list['field_id'] = $field_id;
	$data=["result"	=>	false, 'msg'		=>	"ERROR!"];	
	$uid = $clsISO->getUniqid();
	if($training_id > 0){
		$oneTraining = $clsTraining->getOne($training_id);
		$lesson = $clsISO->to_array_json($oneTraining['lesson']);
		$lesson[$lesson_id] = [
			"title"		=>	addslashes($title),
			"content"	=>	addslashes($content),
			"video"		=>	addslashes($video),
			"file"		=>	addslashes($file),
			"point"		=>	$point,
		];
		if($clsTraining->updateOne($training_id,["lesson" => json_encode($lesson)])) {
			$clsTraining->calculatorTimeTraining($training_id);
			$data = [
				'result'	=>	true,
				'msg'		=>	"SUCCESS"
			];
		}
	}	
	echo json_encode($data); die();
}
function default_deleteLesson(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsTraining = new Training();
	$training_id = (int)Input::post("training_id",0);
	$lesson_id = Input::post("lesson_id",""); 
	$data=["result"	=>	false, 'msg'		=>	"ERROR!"];	
	$uid = $clsISO->getUniqid();
	if($training_id > 0){
		$oneTraining = $clsTraining->getOne($training_id);
		$lesson = $clsISO->to_array_json($oneTraining['lesson']);
		if(isset($lesson[$lesson_id])) {
			unset($lesson[$lesson_id]);
		}
		if($clsTraining->updateOne($training_id,["lesson" => json_encode($lesson)])) {
			$clsTraining->calculatorTimeTraining($training_id);
			$data = [
				'result'	=>	true,
			];
		}
	}	
	echo json_encode($data); die();
}
function default_loadListLesson(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsTraining = new Training();
	$training_id = (int)Input::post("training_id",0);
	$html = "";
	if($training_id > 0){
		$oneTraining = $clsTraining->getOne($training_id);
		$lesson = $clsISO->to_array_json($oneTraining['lesson']);
		if(!empty($lesson)) {
			$i = 1;
			foreach ($lesson as $key => $val ) {
				$html .= '<tr id="'.$key.'">
							<td data-label="" class="text-center mySortableHandler ui-sortable-handle" style="color:#2A5F8B">
								<i class="fa fa-bars"></i>
							</td>
							<td class="text-center">'.($i).'</td>
							<td>'.$val["title"].'</td>
							<td>
								<div class="btn-group">
									<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
									<ul class="dropdown-menu" style="right:0px !important; left: auto">
										<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.training.open_lesson(this,event)" data-training_id="'.$training_id.'" data-lesson_id="'.$key.'"  data-field="history_sale" data-type="edit">Sửa</a></li>
										<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.training.deleteLesson(this,event)" data-training_id="'.$training_id.'" data-lesson_id="'.$key.'">Xoá</a></li>
									</ul>
								</div>
							</td>
						</tr>';
				++$i;
			}
		}else{
			$html .= '<tr>
						<td class="text-center" colspan="2">Danh sách bài học trống</td>	
					</tr>';
		}
	}	
	echo $html; die();
}
function default_upload_video(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	
	$msg = "_error"; $html = "";
	if(!empty($_FILES['upload_video']['name'])){
		$_token = Input::post('_token');
		//if(CSRF::check($_token, 'upload-video')){}
		if(@is_uploaded_file($_FILES['upload_video']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			$up = $clsUploadFile->uploadItem($_FILES["upload_video"],"/TRAINING",EXTENSION_VIDEO_UPLOAD);
			if(!empty($up) && @file_exists(ROOTPATH.$up)){
				$msg = "_success";
				$title = $_FILES["upload_video"]["name"];
				$mimeType = $_FILES["upload_video"]["type"];
				$clsGoogleDrive = new GoogleDrive();
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$up,GOOGLE_DRIVE_FOLDER_VIDEO_ID);
				$upload_video = sprintf('https://drive.google.com/file/d/%s/view', $createdFile->getId());		
				
				@unlink(ROOTPATH.$up);
			}else{
				$msg = "Video tải lên có định dạng ".EXTENSION_VIDEO_UPLOAD;
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'upload_file' => $upload_video
	)); die();
}
function default_sort_lesson(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsTraining = new Training();
	$training_id = (int)Input::post("training_id",0);
	$orderNo = Input::post("orderNo",array()); 
	
	$data=["result"	=>	false, 'msg'		=>	"ERROR!"];	
	$uid = $clsISO->getUniqid();
	if($training_id > 0 && !empty($orderNo)){
		$oneTraining = $clsTraining->getOne($training_id);
		$lesson = $clsISO->to_array_json($oneTraining['lesson']);
		$lesson_sort = [];
		foreach($orderNo as $key => $val) {
			$lesson_sort[$val] = $lesson[$val];
		}
		if($clsTraining->updateOne($training_id,["lesson" => json_encode($lesson_sort)])) {
			$data = [
				'result'	=>	true,
				'msg'		=>	"SUCCESS"
			];
		}
	}	
	echo json_encode($data); die();
}
function default_upload_file(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error";
	if(!empty($_FILES['upload_file']['name'])){
		if(is_uploaded_file($_FILES['upload_file']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			// $clsISO->print_pre($clsUploadFile); die();
			$upload_file = $clsUploadFile->uploadItem($_FILES["upload_file"],"/TRAINING",EXTENSION_FILE_UPLOAD);
			if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
				$msg = "_success";
				// Set the file metadata for drive
				$title = 'FH_'.time().'_'.$_FILES["upload_file"]["name"];
				$mimeType = $_FILES["upload_file"]["type"];
				$clsGoogleDrive = new GoogleDrive();
//				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file);
//				$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				$upload_file = 'https://drive.google.com/file/d/1dCAPq1rKJISOZdLw5XuUUjrqXMwNyB9O/view';
				@unlink(ROOTPATH . $upload_file);
			}else{
				$msg = "Lỗi upload. File tải lên có định dạng (".EXTENSION_FILE_UPLOAD.")";
			}
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'upload_file' => $upload_file
	)); die();
}
?>