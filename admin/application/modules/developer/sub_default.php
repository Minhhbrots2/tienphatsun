<?php 
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$dbconn;
	$clsStock = new Stock();
	$clsCache = new Cache();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsBooking = new Booking();
	// die("xxx");
	$tmp = $clsProfile->getAll("`status_id`<>'"._STATUS_STAFF_OFF_ID."' AND `list_department_id` LIKE '%|"._DEPARTMENT_SALE_ID."|%'", "{$clsProfile->pkey},`department_id`,`list_department_id`,`role_id`,`more_information`");
	// $clsISO->print_pre($tmp); die();
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$role_id = (int) $val['role_id'];
			$department_id = (int) $val['department_id'];
			$list_department_id = $val['list_department_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$department_arrs = $clsISO->getArrayByTextSlash($list_department_id, ",", []);
			$root_role_id = $clsProperty->getRootId($role_id);
			$oneDep = $clsProperty->getOne($department_id, "parent_id");
			$parent_id = $oneDep['parent_id'];
			if(in_array(_DEPARTMENT_SALE_ID, $department_arrs)){
				// $clsISO->print_pre($more_information); die();
				if($parent_id == _DEPARTMENT_SALE_ID){
					$regional_id = $department_id;
					//$role_name = $clsProperty->getTitle($role_id);
					//$department_name = $clsProperty->getTitle($department_id);
				} else {
					$regional_id = $parent_id;
					//$role_name = $clsProperty->getTitle($role_id);
					//$department_name = sprintf('%s-%s', $clsProperty->getTitle($department_id), $clsProperty->getTitle($parent_id));
				}
			} else {
				$regional_id = $department_id;
				//$role_name = $clsProperty->getTitle($role_id);
				//$department_name = $clsProperty->getTitle($department_id);
			}
			//$more_information['role_name'] = $role_name;
			//$more_information['root_role_id'] = $root_role_id;
			//$more_information['department_name'] = $department_name;
			// $clsISO->print_pre($more_information); die();
			$clsProfile->updateOne($val[$clsProfile->pkey], array(
				'regional_id' => $regional_id,
				// 'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	} $clsISO->print_pre($tmp); die();
	// $clsISO->clean_cache('profile');
	die("xxx");
}
/**
 * Dọn rác mồ côi (không thuộc dự án nào còn tồn tại):
 *  - phân khu (_BLOCK) trỏ tới dự án đã bị xoá; tòa/dải tầng (_BUILDING/_RANGE) dưới phân khu mồ côi;
 *  - quỹ căn (default_stock) có project_id không thuộc dự án nào;
 *  - ghi chú căn lạc (default_stock_meta) có stock_id không còn trong bảng stock.
 * Truy cập: ?mod=developer&act=clean_orphan_property. Preview đếm rác trước; chỉ xoá khi POST clean_confirm=1.
 */
function default_clean_orphan_property(){
	global $core, $dbconn, $clsISO;
	$clsProperty = new Property();
	$clsProject = new Project();
	$pre = DB_PREFIX;
	#- Tập project_id còn tồn tại
	$valid_projects = array();
	$projectRows = $clsProject->getAll("1=1", "project_id");
	if(!empty($projectRows)){
		foreach($projectRows as $row){
			$valid_projects[(int) $row['project_id']] = true;
		}
	}
	#- Phân khu mồ côi: _BLOCK có for_id không thuộc dự án nào (kể cả for_id=0)
	$orphan_block_ids = array();
	$valid_block_ids = array();
	$blockRows = $clsProperty->getAll("property_type='_BLOCK'", "property_id, for_id");
	if(!empty($blockRows)){
		foreach($blockRows as $row){
			$pid = (int) $row['property_id'];
			$for_id = (int) $row['for_id'];
			if(isset($valid_projects[$for_id])){
				$valid_block_ids[$pid] = true;
			} else {
				$orphan_block_ids[] = $pid;
			}
		}
	}
	#- Tòa/dải tầng mồ côi: _BUILDING/_RANGE có for_id không thuộc phân khu hợp lệ
	$orphan_building_ids = array();
	$buildingRows = $clsProperty->getAll("property_type in ('_BUILDING','_RANGE')", "property_id, for_id");
	if(!empty($buildingRows)){
		foreach($buildingRows as $row){
			$pid = (int) $row['property_id'];
			$for_id = (int) $row['for_id'];
			if(!isset($valid_block_ids[$for_id])){
				$orphan_building_ids[] = $pid;
			}
		}
	}
	#- Stock mồ côi: project_id không thuộc dự án nào còn tồn tại (kể cả project_id=0)
	$clsStock = new Stock();
	$orphan_stock_ids = array();
	$count_stock = 0;
	$count_stock_meta = 0;
	$projectIdList = implode(',', array_keys($valid_projects));
	if($projectIdList !== ''){
		$stockRows = $clsStock->getAll("project_id NOT IN ({$projectIdList})", "stock_id");
		if(!empty($stockRows)){
			foreach($stockRows as $row){
				$orphan_stock_ids[] = (int) $row['stock_id'];
			}
		}
		$count_stock = count($orphan_stock_ids);
		#- stock_meta cần xoá = ghi chú của stock mồ côi + ghi chú lạc (stock_id không còn trong bảng stock)
		$count_stock_meta = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$pre}stock_meta` sm LEFT JOIN `{$pre}stock` s ON sm.stock_id = s.stock_id WHERE s.stock_id IS NULL OR s.project_id NOT IN ({$projectIdList})");
	}
	$count_block = count($orphan_block_ids);
	$count_building = count($orphan_building_ids);
	$back = PCMS_URL.'/?mod=project';
	$self = PCMS_URL.'/?mod=developer&act=clean_orphan_property';
	$css = 'font-family:Arial,sans-serif;max-width:640px;margin:40px auto;padding:24px;border:1px solid #e5e7eb;border-radius:10px';
	$total_orphan = $count_block + $count_building + $count_stock + $count_stock_meta;
	#- Preview (chưa xác nhận)
	if(Input::post('clean_confirm', '') != '1'){
		$html = '<div style="'.$css.'">';
		$html.= '<h2 style="margin-top:0">Dọn rác phân khu / tòa / quỹ căn mồ côi</h2>';
		if($total_orphan == 0){
			$html.= '<p style="color:#16a34a">Không có dữ liệu mồ côi. Dữ liệu sạch.</p>';
			$html.= '<p><a href="'.$back.'">&larr; Về danh sách dự án</a></p>';
		} else {
			$html.= '<p>Phát hiện dữ liệu <b>không thuộc dự án nào còn tồn tại</b>:</p>';
			$html.= '<ul><li><b>'.$count_block.'</b> phân khu (_BLOCK)</li>';
			$html.= '<li><b>'.$count_building.'</b> tòa / dải tầng (_BUILDING, _RANGE)</li>';
			$html.= '<li><b>'.$count_stock.'</b> quỹ căn (stock)</li>';
			$html.= '<li><b>'.$count_stock_meta.'</b> ghi chú căn lạc (stock_meta)</li></ul>';
			$html.= '<p style="color:#b91c1c">Xoá vĩnh viễn, không khôi phục được.</p>';
			$html.= '<form method="post" action="'.$self.'">';
			$html.= '<input type="hidden" name="clean_confirm" value="1" />';
			$html.= '<button type="submit" style="background:#dc2626;color:#fff;border:0;border-radius:6px;padding:10px 20px;cursor:pointer" onclick="return confirm(\'Xoá vĩnh viễn toàn bộ dữ liệu mồ côi?\')">Dọn rác ('.$total_orphan.' bản ghi)</button> ';
			$html.= '<a href="'.$back.'" style="margin-left:12px">Huỷ</a>';
			$html.= '</form></div>';
		}
		echo $html; die();
	}
	#- Thực thi: property_id đã (int)-cast nên IN-list an toàn; xoá tòa trước rồi phân khu
	@set_time_limit(300);
	$deleted_building = 0;
	$deleted_block = 0;
	foreach(array_chunk($orphan_building_ids, 500) as $chunk){
		if(!empty($chunk)){
			$idList = implode(',', $chunk);
			if($dbconn->Execute("DELETE FROM `{$pre}property` WHERE property_id IN ({$idList})") !== false){
				$deleted_building += count($chunk);
			}
		}
	}
	foreach(array_chunk($orphan_block_ids, 500) as $chunk){
		if(!empty($chunk)){
			$idList = implode(',', $chunk);
			if($dbconn->Execute("DELETE FROM `{$pre}property` WHERE property_id IN ({$idList})") !== false){
				$deleted_block += count($chunk);
			}
		}
	}
	#- Stock mồ côi: xoá stock (project_id không thuộc dự án) trước, rồi dọn mọi stock_meta lạc (stock_id đã mất)
	$deleted_stock = 0;
	$deleted_stock_meta = 0;
	if($projectIdList !== '' && !empty($orphan_stock_ids)){
		$clsStock->deleteByCond("project_id NOT IN ({$projectIdList})");
		$deleted_stock = count($orphan_stock_ids);
	}
	if($dbconn->Execute("DELETE sm FROM `{$pre}stock_meta` sm LEFT JOIN `{$pre}stock` s ON sm.stock_id = s.stock_id WHERE s.stock_id IS NULL") !== false){
		$deleted_stock_meta = $count_stock_meta;
	}
	$clsActivityLog = new ActivityLog();
	$clsActivityLog->addActivityLog("Property","delete",array('field' => 'clean_orphan', 'title' => sprintf('Dọn rác mồ côi: %d phân khu + %d tòa + %d quỹ căn + %d ghi chú căn', $deleted_block, $deleted_building, $deleted_stock, $deleted_stock_meta)));
	$html = '<div style="'.$css.'">';
	$html.= '<h2 style="margin-top:0;color:#16a34a">Đã dọn xong</h2>';
	$html.= '<ul><li>Đã xoá <b>'.$deleted_block.'</b> phân khu</li>';
	$html.= '<li>Đã xoá <b>'.$deleted_building.'</b> tòa / dải tầng</li>';
	$html.= '<li>Đã xoá <b>'.$deleted_stock.'</b> quỹ căn (stock)</li>';
	$html.= '<li>Đã xoá <b>'.$deleted_stock_meta.'</b> ghi chú căn (stock_meta)</li></ul>';
	$html.= '<p><a href="'.$self.'">Kiểm tra lại</a> &nbsp;|&nbsp; <a href="'.$back.'">Về danh sách dự án</a></p></div>';
	echo $html; die();
}
/**
 * Backfill more_information cho hồ sơ cũ: ghi department_name/role_name/regional_id (vùng KD theo is_business_area)
 * bằng Profile::updateMore cho mọi hồ sơ chưa trash. Truy cập: ?mod=developer&act=backfill_profile_more
 * Preview đếm trước; chỉ chạy khi POST clean_confirm=1.
 */
function default_backfill_profile_more(){
	global $core, $clsISO;
	$clsProfile = new Profile();
	$back = PCMS_URL.'/?mod=profile';
	$self = PCMS_URL.'/?mod=developer&act=backfill_profile_more';
	$css = 'font-family:Arial,sans-serif;max-width:640px;margin:40px auto;padding:24px;border:1px solid #e5e7eb;border-radius:10px';
	$field = "{$clsProfile->pkey}, `role_id`, `department_id`, `list_department_id`, `more_information`";
	$all = $clsProfile->getAll("`is_trash`=0", $field);
	$total = is_array($all) ? count($all) : 0;
	#- Preview
	if(Input::post('clean_confirm', '') != '1'){
		$html = '<div style="'.$css.'">';
		$html.= '<h2 style="margin-top:0">Backfill more_information hồ sơ</h2>';
		$html.= '<p>Tính lại &amp; ghi <b>department_name</b>, <b>role_name</b>, <b>regional_id</b> (vùng KD theo <code>is_business_area=1</code>) cho <b>'.$total.'</b> hồ sơ (chưa xoá).</p>';
		$html.= '<p style="color:#6b7280">An toàn, chạy lại nhiều lần được (idempotent).</p>';
		$html.= '<form method="post" action="'.$self.'">';
		$html.= '<input type="hidden" name="clean_confirm" value="1" />';
		$html.= '<button type="submit" style="background:#0d6efd;color:#fff;border:0;border-radius:6px;padding:10px 20px;cursor:pointer">Chạy backfill ('.$total.' hồ sơ)</button> ';
		$html.= '<a href="'.$back.'" style="margin-left:12px">Huỷ</a>';
		$html.= '</form></div>';
		echo $html; die();
	}
	#- Thực thi: gọi updateMore cho từng hồ sơ (đã truyền sẵn data nên không query lại profile)
	@set_time_limit(600);
	$done = 0;
	if(!empty($all)){
		foreach($all as $p){
			$clsProfile->updateMore((int) $p[$clsProfile->pkey], $p);
			$done++;
		}
	}
	$clsISO->clean_cache('profile');
	$clsActivityLog = new ActivityLog();
	$clsActivityLog->addActivityLog("Profile", "update", array('title' => sprintf('Backfill more_information: %d hồ sơ', $done)));
	$html = '<div style="'.$css.'">';
	$html.= '<h2 style="margin-top:0;color:#16a34a">Đã backfill xong</h2>';
	$html.= '<p>Đã cập nhật <b>'.$done.'</b> hồ sơ (department_name / role_name / regional_id).</p>';
	$html.= '<p><a href="'.$self.'">Kiểm tra lại</a> &nbsp;|&nbsp; <a href="'.$back.'">Về danh sách nhân sự</a></p></div>';
	echo $html; die();
}
?>