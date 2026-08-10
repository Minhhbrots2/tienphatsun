<?php
/*======================================================================*\
|| Learning Path (Lộ trình học) — admin controller (Giai đoạn 1)        ||
|| Tạo/sửa/xoá lộ trình + quản lý danh sách khoá học trong lộ trình.    ||
|| Mẫu tham chiếu: module training.                                     ||
\*======================================================================*/

/* ============================ DANH SÁCH ============================ */
function default_default(){
	global $assign_list,$mod,$core,$clsModule,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];

	# Lọc -> redirect (giữ keyword trên URL)
	if(isset($_POST['filter']) && $_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$link = '';
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
	}

	$clsClassTable = new LearningPath();
	$pkeyTable = $clsClassTable->pkey;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"]     = $pkeyTable;

	$cond = "1='1'";
	$keyword  = Input::get('keyword');
	$type_list = Input::get('type_list');
	$assign_list["keyword"]   = $keyword;
	$assign_list["type_list"] = $type_list;

	if(!empty($keyword)){
		$kw = $core->replaceSpace($keyword);
		$cond .= " and (slug like '%".$kw."%' OR title LIKE '%".addslashes($keyword)."%')";
	}
	$cond2 = $cond;
	if($type_list == "Trash"){
		$cond .= " and is_trash = '1'";
	} else {
		$cond .= " and is_trash = '0'";
	}
	$orderBy = " reg_date desc";

	# Phân trang
	$recordPerPage = 20;
	$currentPage = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
	if($currentPage < 1) $currentPage = 1;
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";

	$lstAllItem  = $clsClassTable->getAll($cond);
	$totalRecord = (is_array($lstAllItem) && count($lstAllItem)>0) ? count($lstAllItem) : 0;
	$totalPage   = ceil($totalRecord / $recordPerPage);
	$assign_list['totalRecord']   = $totalRecord;
	$assign_list['recordPerPage'] = $recordPerPage;
	$assign_list['totalPage']     = $totalPage;
	$assign_list['currentPage']   = $currentPage;

	$listPageNumber = array();
	for($i=1; $i<=$totalPage; $i++) $listPageNumber[] = $i;
	$assign_list['listPageNumber'] = $listPageNumber;

	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	$link_page_current = '';
	for($i=0; $i<count($lst_query_string); $i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0) ? '?'.$lst_query_string[$i] : '&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current'] = $link_page_current;

	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit);
	$assign_list["allItem"] = $allItem;

	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"]  = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"]   = $clsClassTable->countItem($cond2);
}

/* ============================== SỬA ============================== */
function default_edit(){
	global $assign_list,$mod,$core,$clsModule,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];

	$clsLP = new LearningPath();
	$assign_list["clsLP"] = $clsLP;

	$string  = isset($_GET['path_id']) ? $_GET['path_id'] : '';
	$path_id = !empty($string) ? intval($core->decryptID($string)) : 0;
	$assign_list["path_id"] = $path_id;

	$oneItem = $clsLP->getOne($path_id);
	$assign_list["oneItem"] = $oneItem;
	if(empty($oneItem)){
		header('location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission'); exit();
	}

	# Lưu
	if(isset($_POST['submit']) && $_POST['submit']=='Update'){
		if($path_id > 0){
			$arr_data = array(
				"upd_date"       => time(),
				"user_update_id" => $user_id,
				"title"          => Input::post('iso-title'),
				"slug"           => $core->replaceSpace(Input::post('iso-title')),
				"description"    => Input::post('iso-description'),
				"level"          => Input::post('level',''),
				"is_online"      => (int)Input::post('is_online',0),
			);
			$image = Input::post('isoman_url_image');
			if(!empty($image)) $arr_data["image"] = $image;

			if($clsLP->updateOne($path_id, $arr_data)){
				$clsLP->recalcStats($path_id);
				if(Input::post('button')=='_EDIT'){
					header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&path_id='.$core->encryptID($path_id).'&message=updateSuccess'); exit();
				}else{
					header('location: '.PCMS_URL.'/?mod='.$mod.'&message=updateSuccess'); exit();
				}
			}else{
				header('location: '.PCMS_URL.'/?mod='.$mod.'&message=updateFailed'); exit();
			}
		}
	}
}

/* ===================== TRASH / RESTORE / DELETE ===================== */
function default_trash(){
	global $mod,$core;
	$clsClassTable = new LearningPath();
	$string = isset($_GET['path_id']) ? $_GET['path_id'] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable == "")
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
	if($clsClassTable->updateOne($pvalTable, "is_trash='1'"))
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=TrashSuccess');
}
function default_restore(){
	global $mod,$core;
	$clsClassTable = new LearningPath();
	$string = isset($_GET['path_id']) ? $_GET['path_id'] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable == "")
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
	if($clsClassTable->updateOne($pvalTable, "is_trash='0'"))
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=RestoreSuccess');
}
function default_delete(){
	global $mod,$core;
	$clsClassTable = new LearningPath();
	$string = isset($_GET['path_id']) ? $_GET['path_id'] : '';
	$pvalTable = intval($core->decryptID($string));
	if($string == '' && $pvalTable == 0)
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
	if(isset($_POST['agree']) && $_POST['agree']=='agree'){
		if($clsClassTable->doDelete($pvalTable))
			header('location: '.PCMS_URL.'/?mod='.$mod.'&message=DeleteSuccess');
	}
}

/* ===================== TẠO NHANH LỘ TRÌNH (AJAX) ===================== */
function default_addPath(){
	global $core,$mod,$clsISO;
	$clsLP = new LearningPath();
	$type  = Input::post("type","_OPEN");
	$title = Input::post("title","");
	$user_id = $core->_USER['user_id'];

	if($type == "_OPEN"){
		$html = $core->build('_ajax.open_path.tpl');
		$data = array('result'=>true, 'uid'=>$clsISO->getUniqid(), 'html'=>$html);
	} else if($type == "_SAVE"){
		$data = array("result"=>false, "msg"=>"ERROR!");
		if(trim($title)!="" && $core->replaceSpace($title)!=""){
			$check = $clsLP->countItem("slug='".$core->replaceSpace($title)."'");
			if($check > 0){
				$data = array("result"=>false, "msg"=>"Tên lộ trình đã tồn tại!");
			}else{
				$path_id = $clsLP->getMaxId();
				$arr_data = array(
					'path_id'   => $path_id,
					'title'     => $title,
					'slug'      => $core->replaceSpace($title),
					'user_id'   => $user_id,
					'reg_date'  => time(),
					'is_online' => 1,
				);
				if($clsLP->insert($arr_data)){
					$data = array(
						"result" => true,
						"msg"    => "SUCCESS!",
						"link"   => PCMS_URL.'?mod='.$mod.'&act=edit&path_id='.$core->encryptID($path_id)
					);
				}
			}
		}
	}
	echo json_encode($data); die();
}

/* ============== COURSE PICKER: tìm khoá học (AJAX) ============== */
function default_searchCourse(){
	global $core,$clsISO;
	$clsTraining = new Training();
	$clsLPC = new LearningPathCourse();
	$path_id = (int)Input::post('path_id',0);
	$keyword = trim(Input::post('keyword',''));

	$cond = "is_trash=0 AND is_online=1";
	if($keyword != ''){
		$cond .= " AND (title LIKE '%".addslashes($keyword)."%' OR slug LIKE '%".$core->replaceSpace($keyword)."%')";
	}
	# loại các khoá đã có trong lộ trình
	$existing = array();
	foreach($clsLPC->getByPath($path_id) as $r) $existing[] = (int)$r['training_id'];
	if(!empty($existing)) $cond .= " AND training_id NOT IN(".implode(',',$existing).")";

	$courses = $clsTraining->getAll($cond." ORDER BY reg_date DESC LIMIT 20", "training_id,title,time_training");

	$html = "";
	if(!empty($courses)){
		foreach($courses as $c){
			$mins = (int)$c['time_training'];
			$html .= '<a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
						onClick="$Core.learning_path.addCourse(this,event)"
						data-training_id="'.$c['training_id'].'" data-path_id="'.$path_id.'">
						<span>'.htmlspecialchars($c['title']).($mins>0?' <small class="text-muted">('.$mins.' phút)</small>':'').'</span>
						<span class="btn btn-sm btn-primary"><i class="fa fa-plus"></i></span>
					</a>';
		}
	}else{
		$html .= '<div class="list-group-item text-center text-muted">Không tìm thấy khoá học phù hợp</div>';
	}
	echo $html; die();
}

/* ============== COURSE PICKER: thêm / xoá / sắp xếp (AJAX) ============== */
function default_addCourse(){
	$clsLPC = new LearningPathCourse();
	$clsLP  = new LearningPath();
	$path_id     = (int)Input::post('path_id',0);
	$training_id = (int)Input::post('training_id',0);
	$data = array("result"=>false, "msg"=>"ERROR!");
	if($path_id>0 && $training_id>0){
		if($clsLPC->existsInPath($path_id,$training_id)){
			$data = array("result"=>false, "msg"=>"Khoá học đã có trong lộ trình!");
		}else if($clsLPC->addCourse($path_id,$training_id)){
			$clsLP->recalcStats($path_id);
			$data = array("result"=>true, "msg"=>"SUCCESS");
		}
	}
	echo json_encode($data); die();
}
function default_removeCourse(){
	$clsLPC = new LearningPathCourse();
	$clsLP  = new LearningPath();
	$id      = (int)Input::post('id',0);
	$path_id = (int)Input::post('path_id',0);
	$data = array("result"=>false);
	if($id>0){
		$clsLPC->deleteOne($id);
		if($path_id>0) $clsLP->recalcStats($path_id);
		$data = array("result"=>true);
	}
	echo json_encode($data); die();
}
function default_toggleRequired(){
	$clsLPC = new LearningPathCourse();
	$id  = (int)Input::post('id',0);
	$val = (int)Input::post('val',0);
	$data = array("result"=>false);
	if($id>0){
		$clsLPC->updateOne($id, array("is_required"=>$val));
		$data = array("result"=>true);
	}
	echo json_encode($data); die();
}
function default_sortCourse(){
	$clsLPC = new LearningPathCourse();
	$path_id = (int)Input::post('path_id',0);
	$orderNo = Input::post("orderNo", array());
	$data = array("result"=>false, "msg"=>"ERROR!");
	if($path_id>0 && !empty($orderNo)){
		$clsLPC->reorder($path_id, $orderNo);
		$data = array("result"=>true, "msg"=>"SUCCESS");
	}
	echo json_encode($data); die();
}

/* ============== COURSE PICKER: render danh sách khoá (AJAX) ============== */
function default_loadCourseList(){
	global $clsISO;
	$clsLP = new LearningPath();
	$path_id = (int)Input::post('path_id',0);
	$html = "";
	if($path_id > 0){
		$courses = $clsLP->getCourses($path_id);
		if(!empty($courses)){
			$i = 1;
			foreach($courses as $row){
				$title = ($row['title']!='' && $row['title']!==null)
					? htmlspecialchars($row['title'])
					: '<span class="text-danger">(Khoá học đã bị xoá #'.$row['training_id'].')</span>';
				$mins  = (int)$row['time_training'];
				$html .= '<tr id="'.$row['id'].'">
					<td class="text-center mySortableHandler ui-sortable-handle" style="color:#2A5F8B; cursor:move">
						<i class="fa fa-bars"></i>
					</td>
					<td class="text-center">'.$i.'</td>
					<td>'.$title.($mins>0?' <small class="text-muted">('.$mins.' phút)</small>':'').'</td>
					<td class="text-center">
						<label class="m-0" style="cursor:pointer">
							<input type="checkbox" onClick="$Core.learning_path.toggleRequired(this,event)" data-id="'.$row['id'].'" '.($row['is_required']?'checked':'').'> Bắt buộc
						</label>
					</td>
					<td class="text-center">
						<a href="javascript:void(0)" class="text-danger" title="Xoá khỏi lộ trình"
							onClick="$Core.learning_path.removeCourse(this,event)" data-id="'.$row['id'].'" data-path_id="'.$path_id.'">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>';
				++$i;
			}
		}else{
			$html .= '<tr><td class="text-center text-muted" colspan="5">Chưa có khoá học nào trong lộ trình</td></tr>';
		}
	}
	echo $html; die();
}
?>
