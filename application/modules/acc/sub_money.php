<?php 
function money_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	
	$list_preloaders = array();
	for($i=1; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Import kế toán - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Import kế toán - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function money_load_import_logs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	$clsImportLogs = new ImportLogs();
	
	$html = '';
	$list = $clsImportLogs->getAll("`import_type`='_money' ORDER BY `reg_date` DESC");
	if(!empty($list)){ $ii = 1;
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($list as $key => $val){
			$user_id = $val['user_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$date_type = $core->get_field($more_information, "date_type", "month");
			$html.= '<tr>
				<td class="text-center">'.$ii.'</td>
				<td>'.$more_information['date'].'</td>
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
function money_open_import(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$smarty->assign("uid", $uid);
	#
	$money_import_configs = $clsConfiguration->getValue('money_import_configs');
	$money_import_configs = $clsISO->to_array_json($money_import_configs);
	$smarty->assign("money_import_configs", $money_import_configs);
	// Return
	$html = $core->build($sub .DS. '_ajax.open_import.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function money_open_config(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsMoney = new Money();
	#
	$money_import_configs = $clsConfiguration->getValue('money_import_configs');
	$money_import_configs = $clsISO->to_array_json($money_import_configs);
	$smarty->assign("money_import_configs", $money_import_configs);
	
	$config_id = Input::post('config_id', "");
	$smarty->assign("config_id", $config_id);
	#
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
				for($row=4; $row <= $highestRow; ++ $row){
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
				$select_default = [];
				if(!empty($money_import_configs) && !empty($config_id) 
					&& array_key_exists($config_id, $money_import_configs)){
					$one_config = $money_import_configs[$config_id];
					$select_default = $core->get_field($one_config, "columns", []);
				}
				// Remove file uploaded
				@unlink($inputFileName);
				$highestColumnIndex = 15;
				$widthColumn = 100/$highestColumnIndex;	
				$data_select = $clsMoney->getTableField();
				$smarty->assign("data_select",$data_select);
				$smarty->assign("select_default",$select_default);
				$smarty->assign("widthColumn",$widthColumn);
				$smarty->assign("highestColumnIndex",$highestColumnIndex);
				$smarty->assign("tblData", $tblData);
			}
		}
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
function money_open_field(){
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
			<button type="button" gId="'.$gId.'" uid="'.$uid.'" onClick="$Core.money.save_field(this, event)" 
				class="btn btn-outline-primary cursor-pointer">Lưu lại</button>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function money_do_config(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsAttendance = new Attendance();
	#
	$gId = Input::post("gId");
	$columns = Input::post("columns", array());
	$money_import_configs = $clsConfiguration->getValue('money_import_configs');
	$money_import_configs = $clsISO->to_array_json($money_import_configs);
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
			if(!empty($money_import_configs) && array_key_exists($config_id, $money_import_configs)){
				$holderG = "_update";
				$money_import_configs[$config_id]['columns'] = $arr_fields;
			} else {
				$holderG = "_new";
				$money_import_configs[$config_id] = array(
					'config_name' => $config_name,
					'columns' => $arr_fields
				);
			}
			if($clsConfiguration->updateValue("money_import_configs", json_encode($money_import_configs, JSON_UNESCAPED_UNICODE))){
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
function markParentChild($array) {
    // Bước 1: Lấy danh sách parent_id
    $parentIds = [];
    foreach ($array as $item) {
        if ($item['parent_id'] != 0) {
            $parentIds[] = $item['parent_id'];
        }
    }
    // Tối ưu lookup
    $parentIds = array_unique($parentIds);
    $parentMap = array_flip($parentIds);
    // Bước 2: Gắn nhãn
    foreach ($array as &$item) {
        if (isset($parentMap[$item['setting_id']])) {
            $item['type'] = 'parent'; // có con
        } else {
            $item['type'] = 'child'; // không có con
        }
    }
    return $array;
}
function money_do_import(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsSetting = new Setting();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsMoney = new Money();
	$clsMoneyItem = new MoneyItem();
	$clsImportLogs = new ImportLogs();
	#
	$msg = "_error"; 
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$map_account_arrs = $arr_account_slug = $arr = array();
		$tmp = $clsSetting->getCacheItems("_ACCOUNT");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$setting_code = $core->get_field($more_information, "setting_code", "");
				$arr[$val[$clsSetting->pkey]] = array(
					'setting_id' => $val[$clsSetting->pkey],
					'parent_id' => $val['parent_id'],
					'title' => $val['title']
				);
				$arr_account_slug[$core->replaceSpace($setting_code)] = $val[$clsSetting->pkey];
			}
		}
		$map_account_arrs = markParentChild($arr);
		#
		$money_import_configs = $clsConfiguration->getValue('money_import_configs');
		$money_import_configs = $clsISO->to_array_json($money_import_configs);
		#
		$company_id = (int) Input::post('company_id', 0);
		$date = Input::post('date', date('Y-m-d'));
		$config_id = Input::post('config_id', 0);
		$config_name = $core->get_field($money_import_configs[$config_id], "config_name", "");
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
					$columns = $core->get_field($money_import_configs[$config_id], "columns", []);
					for($i=4; $i<$total_record; $i++){
						$arr_row = array('row_number' => $i);
						foreach($columns as $i_col => $p_field){
							$arr_row[$p_field] = trim($tblData[$i][$i_col]);
						}
						$arr_data_import[] = $arr_row;
					}
					if(!empty($arr_data_import)){
						$total_errors = 0; 
						$errors = $arr_project_cached = $arr_staff_cached = $arr_setting_cached = $arr_property_cached = array();
						foreach($arr_data_import as $key => $val){
							$row_number = $val['row_number'];
							$accounting_date = $core->get_field($val, "accounting_date", "");
							$document_date = $core->get_field($val, "document_date", "");
							$document_no = $core->get_field($val, "document_no", "");
							$description = $core->get_field($val, "description", "");
							$account_name = $core->get_field($val, "account_name", "");
							$corresponding_account_name = $core->get_field($val, "corresponding_account_name", "");
							$debit_amount = $core->get_field($val, "debit_amount", "");
							$credit_amount = $core->get_field($val, "credit_amount", "");
							$staff_code = $core->get_field($val, "staff_code", "");
							$staff_name = $core->get_field($val, "staff_name", "");
							$department_code = $core->get_field($val, "department_code", "");
							$department_name = $core->get_field($val, "department_name", "");
							$project_code = $core->get_field($val, "project_code", "");
							$project_name = $core->get_field($val, "project_name", "");
							#
							$arr_data_import[$key]['account_id'] = 0;
							$arr_data_import[$key]['corresponding_account_id'] = 0;
							
							if(!empty($account_name)){
								$account_slug = $core->replaceSpace($account_name);
								if(!empty($arr_account_slug) && isset($arr_account_slug[$account_slug])){
									$arr_data_import[$key]['account_id'] = $arr_account_slug[$account_slug];
								} else {
									if(empty($arr_setting_cached) && isset($arr_setting_cached[$account_name])){
										$arr_data_import[$key]['account_id'] = $arr_setting_cached[$account_name];
									} else {
										$tmp = $clsSetting->getByCond("`_type`='_ACCOUNT' AND `code`='{$account_name}'", $clsSetting->pkey);
										if(!empty($tmp)){
											$arr_setting_cached[$account_name] = $tmp[$clsSetting->pkey];
											$arr_data_import[$key]['account_id'] = $tmp[$clsSetting->pkey];
										} else {
											$total_errors += 1;
											$errors[$row_number] = sprintf("Thông tin tài khoản <strong>%s</strong> không khớp", $account_name);
										}
									}
								}
							}
							if(!empty($corresponding_account_name)){
								$corresponding_account_slug = $core->replaceSpace($corresponding_account_name);
								if(!empty($corresponding_account_slug) && isset($arr_account_slug[$corresponding_account_slug])){
									$arr_data_import[$key]['corresponding_account_id'] = $arr_account_slug[$corresponding_account_slug];
								} else {
									if(!empty($arr_setting_cached) && isset($arr_setting_cached[$corresponding_account_name])){
										$arr_data_import[$key]['corresponding_account_id'] = $arr_setting_cached[$corresponding_account_name];
									} else {
										$tmp = $clsSetting->getByCond("`_type`='_ACCOUNT' AND `code`='{$corresponding_account_name}'", $clsSetting->pkey);
										if(!empty($tmp)){
											$arr_setting_cached[$corresponding_account_name] = $tmp[$clsSetting->pkey];
											$arr_data_import[$key]['corresponding_account_id'] = $tmp[$clsSetting->pkey];
										}
									}
								}
							}
							#
							$project_id = $block_id = 0;
							if(!empty($project_name) && !empty($project_code)){
								if(isset($arr_project_cached[$project_code])){
									$tmp = $arr_project_cached[$project_code];
									$project_id = $tmp['project_id'];
									$block_id = $tmp['block_id'];
								} else {
									$tmp = $clsProject->getAll("code='{$project_code}' OR `slug`='{$core->replaceSpace($project_name)}'", $clsProject->pkey);
									if(!empty($tmp)){
										$project_id = $tmp[$clsProject->pkey];
									} else {
										$tmp = $clsProperty->getByCond("property_type='_BLOCK' AND (`property_code`='{$project_code}' OR `slug`='{$core->replaceSpace($project_name)}')", "{$clsProperty->pkey},`for_id`");
										if(!empty($tmp)){
											$project_id = $tmp["for_id"];
											$block_id = $tmp[$clsProperty->pkey];
										} else {
											$total_errors += 1;
											$errors[$row_number] = sprintf("Thông tin dự án <strong>%s</strong> không khớp", $project_name);
										}
									}
									$arr_project_cached[$property_code] = array(
										'project_id' => $project_id,
										'block_id' => $block_id,
									);
								}
							}
							$arr_data_import[$key]['project_id'] = $project_id;
							$arr_data_import[$key]['block_id'] = $block_id;
							#
							$staff_id = $regional_id = 0;
							if(!empty($department_code) && !empty($department_name)){
								if($clsISO->checkContainer($department_code, "CN_", "")){
									if(!empty($arr_setting_cached) && isset($arr_setting_cached[$department_code])){
										$regional_id = (int) $arr_setting_cached[$department_code];
									} else {
										$tmp = $clsSetting->getByCond("`_type`='_OFFICE' 
											AND (`code`='{$department_code}' OR `slug`='".$core->replaceSpace($department_name)."')", $clsSetting->pkey);
										if(!empty($tmp)){
											$regional_id = $tmp[$clsSetting->pkey];
											$arr_property_cached[$department_code] = $regional_id;
										}
									}
								} else {
									if(!empty($arr_property_cached) && isset($arr_property_cached[$department_code])){
										$regional_id = (int) $arr_property_cached[$department_code];
									} else {
										$tmp = $clsProperty->getByCond("`property_type`='_DEPARTMENT' 
											AND `slug`='".$core->replaceSpace($department_code)."'", $clsProperty->pkey);
										if(!empty($tmp)){
											$regional_id = $tmp[$clsProperty->pkey];
											$arr_property_cached[$department_code] = $regional_id;
										}
									}
								}
							}
							if(!empty($staff_name) && !empty($staff_code)){
								if(isset($arr_staff_cached[$staff_code])){
									$staff_id = $arr_staff_cached[$staff_code];
								} else {
									$sql_query = "`full_name_slug`='{$core->replaceSpace($staff_name)}'";
									if($regional_id > 0) {
										$sql_full_query = "{$sql_query} AND (`regional_id`='{$regional_id}' OR `department_id`='{$regional_id}')";
										$tmp = $clsProfile->getByCond($sql_full_query, "{$clsProfile->pkey}");
										if(!empty($tmp)){
											$staff_id = $tmp[$clsProfile->pkey];
										} else {
											$tmp = $clsProfile->getByCond($sql_query, "{$clsProfile->pkey}");
											$staff_id = !empty($tmp) ? $tmp[$clsProfile->pkey] : 0;
										}
									} else {
										$tmp = $clsProfile->getByCond($sql_query, $clsProfile->pkey);
										$staff_id = !empty($tmp) ? $tmp[$clsProfile->pkey] : 0;
									}
									$arr_staff_cached[$staff_code] = $staff_id;
								}
							}
							$arr_data_import[$key]['staff_id'] = $staff_id;
							$arr_data_import[$key]['regional_id'] = $regional_id;
						}
						// $clsISO->print_pre($arr_data_import); die();
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
							$clsImportLogs->insert(array(
								'import_type' => '_money',
								'more_information' => json_encode(array(
									'date' => $date,
									'config_id' => $config_id,
									'config_name' => $config_name,
									'file_name' => $fileimport["name"]
								), JSON_UNESCAPED_UNICODE),
								'user_id' => $profile_id,
								'reg_date' => time()								
							));
							// Xoá dữ liệu theo ngày
							$dbconn->Execute("DELETE `t1` FROM {$clsMoneyItem->tbl} AS `t1`
								JOIN {$clsMoney->tbl} AS `t2` ON `t2`.`money_id` = `t1`.`money_id`
								WHERE FROM_UNIXTIME(`t1`.`reg_date`,`%Y-%m-%d`) = '{$date}'");
							$clsMoney->deleteByCond("FROM_UNIXTIME(`reg_date`,`%Y-%m-%d`)='{$date}'");
							// Thêm dữ liệu
							foreach($arr_data_import as $key => $val){
								$accounting_date = $core->get_field($val, "accounting_date", "");
								$document_date = $core->get_field($val, "document_date", "");
								$document_no = $core->get_field($val, "document_no", "");
								$description = $core->get_field($val, "description", "");
								$account_id = (int) $core->get_field($val, "account_id", 0);
								$corresponding_account_id = (int) $core->get_field($val, "corresponding_account_id", 0);
								$staff_id = (int) $core->get_field($val, "staff_id", 0);
								$regional_id = (int) $core->get_field($val, "regional_id", 0);
								$project_id = (int) $core->get_field($val, "project_id", 0);
								$block_id = (int) $core->get_field($val, "block_id", 0);
								$debit_amount = $core->get_money_field($val, "debit_amount", 0);
								$credit_amount = $core->get_money_field($val, "credit_amount", 0);
								if(!empty($document_no) && !empty($document_date) && !empty($accounting_date) 
									&& !empty($description) && (!empty($debit_amount) || !empty($credit_amount)) 
									&& isset($map_account_arrs[$account_id]) && $map_account_arrs[$account_id]['type'] == 'child'){
									$hash = md5(sprintf('%s|%s|%s|%s', $company_id, $document_no, $document_date, $accounting_date));
									$tmp = $clsMoney->getByCond("`company_id`='{$company_id}' AND `hash`='{$hash}'", $clsMoney->pkey);
									if(!empty($tmp)){
										if($clsMoneyItem->insert(array(
											'money_id' => $tmp[$clsMoney->pkey],
											'account_id' => $account_id,
											'description' => $description,
											'corresponding_account_id' => $corresponding_account_id,
											'debit_amount' => $clsISO->processSmartNumber($debit_amount),
											'credit_amount' => $clsISO->processSmartNumber($credit_amount),
											'staff_id' => $staff_id,
											'regional_id' => $regional_id,
											'project_id' => $project_id,
											'block_id' => $block_id,
											'reg_date' => time(),
											'upd_date' => time()
										))){
											$msg = "_success";
										}
									} else {
										$money_id = $clsMoney->getMaxId();
										if($clsMoney->insert(array(
											$clsMoney->pkey => $money_id,
											'hash' => $hash,
											'company_id' => $company_id,
											'document_no' => $document_no,
											'document_date' => $clsISO->toTime($document_date),
											'accounting_date' => $clsISO->toTime($accounting_date),
											'user_id' => $profile_id,
											'user_id_update' => $profile_id,
											'reg_date' => time(),
											'upd_date' => time()
										))){
											$msg = "_success";
											$clsMoneyItem->insert(array(
												'money_id' => $money_id,
												'company_id' => $company_id,
												'account_id' => $account_id,
												'description' => $description,
												'corresponding_account_id' => $corresponding_account_id,
												'debit_amount' => $clsISO->processSmartNumber($debit_amount),
												'credit_amount' => $clsISO->processSmartNumber($credit_amount),
												'staff_id' => $staff_id,
												'regional_id' => $regional_id,
												'project_id' => $project_id,
												'block_id' => $block_id,
												'reg_date' => time(),
												'upd_date' => time()
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
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
?>