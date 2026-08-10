<?php 
function attendance_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	
	$list_preloaders = array();
	for($i=1; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Import chấm công - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Import chấm công - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function attendance_load_import_logs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	$clsImportLogs = new ImportLogs();
	
	$html = '';
	$list = $clsImportLogs->getAll();
	if(!empty($list)){ $ii = 1;
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list as $key => $val){
			$user_id = $val['user_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$date_type = $core->get_field($more_information, "date_type", "month");
			$html.= '<tr>
				<td class="text-center">'.$ii.'</td>
				<td>'.($date_type == 'month' ? 'Tháng ' : 'Tuần ') . $more_information['date_value'].'</td>
				<td>'.$more_information['office_name'].'</td>
				<td>'.$more_information['file_name'].'</td>
				<td>'.$arr_profile_cached[$user_id]['full_name'].'</td>
				<td class="text-right">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
			</tr>';
			++$ii;
		}
	}
	// return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function attendance_open_import(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$smarty->assign("uid", $uid);
	#
	$arr_offices = $clsSetting->getCacheItems('_OFFICE');
	$attendance_import_configs = $clsConfiguration->getValue('attendance_import_configs');
	$attendance_import_configs = $clsISO->to_array_json($attendance_import_configs);
	$smarty->assign("arr_offices", $arr_offices);
	$smarty->assign("attendance_import_configs", $attendance_import_configs);
	// Return
	$html = $core->build($sub .DS. '_ajax.open_import.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function attendance_open_config(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsAttendance = new Attendance();
	
	$attendance_import_configs = $clsConfiguration->getValue('attendance_import_configs');
	$attendance_import_configs = $clsISO->to_array_json($attendance_import_configs);
	$smarty->assign("attendance_import_configs", $attendance_import_configs);
	
	$gId = Input::post('gId');
	$uid = $clsISO->getUniqid();
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$fileimport = $_FILES['fileimport'];
		if(@is_uploaded_file($fileimport['tmp_name'])){
			$target_dir = PCMS_DIR."/tmp/";
			$file_ext = explode('.', basename($fileimport["name"]));
			$file_ext = strtolower(end($file_ext));
			$target_file = $target_dir . time().'.'.$file_ext;
			if (@move_uploaded_file($fileimport["tmp_name"], $target_file)) {
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
				$worksheet 			= $objPHPExcel->getActiveSheet();
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow();
				$highestColumn      = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				##
				$index = 0; $tblData = $select_default = array();
				for($row=1; $row <= $highestRow; ++ $row){
					for($col=0; $col < $highestColumnIndex; ++$col){
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$value = $cell->getValue(); 
						// Nếu là date
						if (PHPExcel_Shared_Date::isDateTime($cell)) {
							$value = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($value));
						}
						$tblData[$index][] = $value;
					}
					++$index;
				}
				$more_information = $oneProfile['more_information'];
				$select_default = $core->get_field($more_information, "attendance_config_column", []);
				// Remove file uploaded
				@unlink($inputFileName);
				$highestColumnIndex = 15;
				$widthColumn = 100/$highestColumnIndex;	
				$data_select = $clsAttendance->getTableField();
				$smarty->assign("data_select",$data_select);
				$smarty->assign("select_default",$select_default);
				$smarty->assign("widthColumn",$widthColumn);
				$smarty->assign("highestColumnIndex",$highestColumnIndex);
				$smarty->assign("tblData", $tblData);
			}
		} else {
			
		}
	} else {
		
	}
	// Return
	$smarty->assign("uid", $uid);
	$smarty->assign("gId", $gId);
	$html = $core->build($sub .DS. '_ajax.open_config.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function attendance_open_field(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsAttendance = new Attendance();
	
	$gId = Input::post('gId');
	$uid = $clsISO->getUniqid();
	$html = '<div class="modal-dialog modal-sm modal-dialog-centered">
	<form class="modal-content" onsubmit="return false;">
		<div class="modal-header">
			<h5 class="modal-title">Cài đặt config</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<label class="form-label mb-1">Nhập tên</label>
			<input type="text" class="form-control autofocus requried" placeholder="Nhập tên" name="title" />
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng lại</button>
			<button type="button" gId="'.$gId.'" uid="'.$uid.'" onClick="$Core.attendance.save_field(this, event)" 
				class="btn btn-outline-primary cursor-pointer">Lưu lại</button>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function attendance_do_config(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsAttendance = new Attendance();
	#
	$gId = Input::post("gId");
	$columns = Input::post("columns", array());
	$attendance_import_configs = $clsConfiguration->getValue('attendance_import_configs');
	$attendance_import_configs = $clsISO->to_array_json($attendance_import_configs);
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
		if($error_field > 0){
			$res = array(
				'result' =>	false,
				'msg'	 =>	"Các cột dữ liệu không được trùng nhau"
			);
		} else {
			$config_id = Input::post('config_id');
			$config_name = Input::post('config_name');
			if(!empty($attendance_import_configs) && array_key_exists($config_id, $attendance_import_configs)){
				$holderG = "_update";
				$attendance_import_configs[$config_id]['columns'] = $arr_fields;
			} else {
				$holderG = "_new";
				$attendance_import_configs[$config_id] = array(
					'config_name' => $config_name,
					'columns' => $arr_fields
				);
			}
			if($clsConfiguration->updateValue("attendance_import_configs", json_encode($attendance_import_configs, JSON_UNESCAPED_UNICODE))){
				$res = array(
					"result" =>	true,
					'holderG' => $holderG,
					'config_id' => $config_id,
					'config_name' => $config_name,
					'msg' => "Cài đặt thành công",
				);
			}
		}
	}else{
		$res = array(
			"result" =>	false,
			'msg' => "Có lỗi xảy ra. Xin vui lòng thử lại!",
		);
	}
	// Return
	echo json_encode($res); die();
}
function attendance_do_import(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsSetting = new Setting();
	$clsProfile = new Profile();
	$clsImportLogs = new ImportLogs();
	$clsAttendance = new Attendance();
	$clsAttendanceLog = new AttendanceLog();
	#
	$msg = "_error"; 
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$attendance_import_configs = $clsConfiguration->getValue('attendance_import_configs');
		$attendance_import_configs = $clsISO->to_array_json($attendance_import_configs);
		#
		$date_type = Input::post('date_type', 'month');
		$date_value = Input::post('date_value', date('m'));
		$office_id = (int) Input::post('office_id', '0');
		$office_name = $clsSetting->getTitle($office_id);
		$config_id = Input::post('config_id', 0);
		$config_name = $core->get_field($attendance_import_configs[$config_id], "config_name", "");
		$fileimport = $_FILES["fileimport"];
		// $clsISO->print_pre($_POST); die();
		if(@is_uploaded_file($fileimport['tmp_name'])){
			$target_dir = PCMS_DIR."/tmp/";
			$file_ext = explode('.', basename($fileimport["name"]));
			$file_ext = strtolower(end($file_ext));
			// $clsISO->print_pre($fileimport); die();
			$target_file = $target_dir . time().'.'.$file_ext;
			if (@move_uploaded_file($fileimport["tmp_name"], $target_file)) {
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
				$worksheet 			= $objPHPExcel->getActiveSheet();
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow();
				$highestColumn      = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				##
				$index = 0; $tblData =array();
				for($row=1; $row <= $highestRow; ++ $row){
					for($col=0; $col < $highestColumnIndex; ++$col){
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$value = $cell->getValue(); 
						// Nếu là date
						if (PHPExcel_Shared_Date::isDateTime($cell)) {
							$value = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($value));
						}
						$tblData[$index][] = $value;
					}
					++$index;
				}
				// Remove file uploaded
				@unlink($inputFileName);
				if(!empty($tblData)){
					$arr_data_import = array();
					$total_record = count($tblData);
					$columns = $core->get_field($attendance_import_configs[$config_id], "columns", []);
					for($i=4; $i<$total_record; $i++){
						$arr_row = array('row_number' => $i);
						foreach($columns as $i_col => $p_field){
							$arr_row[$p_field] = trim($tblData[$i][$i_col]);
						}
						$arr_data_import[] = $arr_row;
					}
					if(!empty($arr_data_import)){
						$total_errors = 0; $errors = array();
						foreach($arr_data_import as $key => $val){
							$row_number = $val['row_number'];
							$work_date = $core->get_field($val, "work_date", "");
							$staff_code = $core->get_field($val, "staff_code", "");
							$staff_name = $core->get_field($val, "staff_name", "");
							$department_name = $core->get_field($val, "department_name", "");
							$check_in = $core->get_field($val, "check_in", "");
							$check_out = $core->get_field($val, "check_out", "");
							$check_in_2 = $core->get_field($val, "check_in_2", "");
							$check_out_2 = $core->get_field($val, "check_out_2", "");
							$total_work_hours = $core->get_field($val, "total_work_hours", "");
							if(!empty($staff_name) && !empty($work_date) && !empty($check_in)){
								$sql_query = "`full_name_slug`='{$core->replaceSpace($staff_name)}'";
								if(!empty($staff_code)){
									$staff_code = sprintf('FH%s', str_replace('FH', '', $staff_code));
									$staff_code = $clsProfile->normalizeCode($staff_code);
									$sql_query.= " AND `code`='{$staff_code}'";
								}
								$tmp = $clsProfile->getByCond($sql_query, $clsProfile->pkey);
								if(!empty($tmp)){
									$arr_data_import[$key]['staff_id'] = $tmp[$clsProfile->pkey];
								} else {
									$total_errors += 1;
									$errors[$row_number] = sprintf("Thông tin nhân viên <strong>%s</strong> không khớp", $staff_name);
								}
							}
						}
						if($total_errors > 0){
							$html_errors = '<div class="modal-dialog modal-dialog-scrollable">
								<form class="modal-content" onsubmit="return false;">
									<div class="modal-header">
										<h5 class="modal-title">Lỗi import dữ liệu</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<table class="table">
											<thead><tr>
												<th width="5%" class="align-center bg-lighter h-px-35">Dòng.</th>
												<th class="align-center bg-lighter h-px-35">Nội dung</th>
											</tr></thead>';
											foreach($errors as $row_number => $error){
												$html_errors.= '<tr>
													<td class="text-center align-center">'.($row_number+1).'</td>
													<td class="align-center">'.$error.'</td>
												</tr>';
											}
										$html_errors.= '</table>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng lại</button>
									</div>
								</form>
							</div>';
							echo json_encode(array(
								'msg' => "_invalid",
								'uid' => $clsISO->getUniqid(),
								'html_errors' => $html_errors,
							)); die();
 						} else {
							$msg = "_success";
							$more_information = array(
								'date_type' => $date_type,
								'date_value' => $date_value,
								'office_id' => $office_id,
								'office_name' => $office_name,
								'config_id' => $config_id,
								'config_name' => $config_name,
								'file_name' => $fileimport["name"]
							);
							// $clsISO->print_pre($more_information); die();
							$clsImportLogs->insert(array(
								'import_type' => '_attendance',
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'user_id' => $profile_id,
								'reg_date' => time()								
							));
							foreach($arr_data_import as $key => $val){
								$work_date = $core->get_field($val, "work_date", "");
								$staff_id = (int) $core->get_field($val, "staff_id", 0);
								$staff_code = $core->get_field($val, "staff_code", "");
								$staff_name = $core->get_field($val, "staff_name", "");
								$department_name = $core->get_field($val, "department_name", "");
								$check_in = $core->get_field($val, "check_in", "");
								$check_out = $core->get_field($val, "check_out", "");
								$check_in_2 = $core->get_field($val, "check_in_2", "");
								$check_out_2 = $core->get_field($val, "check_out_2", "");
								$total_work_hours = $core->get_field($val, "total_work_hours", 0);
								if(!empty($staff_name) && !empty($work_date) && !empty($check_in)){
									$first_check_in = $check_in;
									$last_check_out = !empty($check_out) ? (!empty($check_out_2) ? $check_out_2 : $check_out) : 0;
									$action_arrs = array();
									if(!empty($check_in)) 
										$action_arrs['check_in'] = $clsISO->toTime($work_date, $check_in);
									if(!empty($check_out)) 
										$action_arrs['check_out'] = $clsISO->toTime($work_date, $check_out);
									if(!empty($check_in_2)) 
										$action_arrs['check_in'] = $clsISO->toTime($work_date, $check_in_2);
									if(!empty($check_out_2)) 
										$action_arrs['check_out'] = $clsISO->toTime($work_date, $check_out_2);
									// $clsISO->print_pre($action_arrs); die();
									$work_time = $clsISO->toTime($work_date);
									$first_check_in_time = $clsISO->toTime($work_date, $first_check_in);
									$last_check_out_time = !empty($last_check_out) 
										? $clsISO->toTime($work_date, $last_check_out) : 0;
									
									if(!empty($total_work_hours)){
										$total_work_hours = round($total_work_hours, 2);
									} else {
										$total_work_hours = 0;
										if(!empty($last_check_out)){
											$total_work_hours = round(($first_check_in_time - $last_check_out_time) / 3600, 2);
										}
									}
									$work_unit = 0;
									$checkin_before  = $clsISO->toTime($work_date, '10:30:00');
									$checkout_after = $clsISO->toTime($work_date, '15:00:00');
									// Check-in trước 10h:30
									if ($first_check_in && $first_check_in_time < $checkin_before) {
										$work_unit += 0.5;
									}
									// Check out sau 15h00
									if ($last_check_out && $last_check_out_time > $checkout_after) {
										$work_unit += 0.5;
									}
									// trường hợp quên checkin
									if ($first_check_in && $first_check_in_time > $checkout_after && $last_check_out_time == 0){
										$work_unit += 0.5;
									}
									$tmp = $clsAttendance->getByCond("`staff_id`='{$staff_id}' AND `work_date`='".$work_time."'", $clsAttendance->pkey);
									if(!empty($tmp)){
										// $clsISO->print_pre($val); die();
										$clsAttendance->updateOne($tmp[$clsAttendance->pkey], array(
											'first_check_in' => $first_check_in_time,
											'last_check_out' => $last_check_out_time,
											'work_unit' => $work_unit,
											'total_work_hours' => $total_work_hours
										));
									} else {
										if($clsAttendance->insert(array(
											'staff_id' => $staff_id,
											'work_date ' => $clsISO->toTime($work_date),
											'first_check_in' => $clsISO->toTime($work_date, $first_check_in),
											'last_check_out' => !empty($last_check_out) ? $clsISO->toTime($work_date, $last_check_out) : 0,
											'work_unit' => $work_unit,
											'total_work_hours' => $total_work_hours
										))){
											foreach($action_arrs as $log_type => $log_time){
												$clsAttendanceLog->insert(array(
													'staff_id' => $staff_id,
													'log_time' => $log_time,
													'log_type' => $log_type,
													'reg_date' => time()
												));
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
?>