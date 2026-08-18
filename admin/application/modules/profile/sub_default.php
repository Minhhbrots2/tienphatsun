<?php
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$dbconn;
	$user_id = $core->_USER['user_id'];
	$assign_list["clsModule"] = $clsModule;
	#
	$clsHubJS = new HubJS();
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	#
	$arr_departments = array();
	$clsProperty->makeOption(0, '_DEPARTMENT', 0, $arr_departments);
	$assign_list["arr_departments"] = $arr_departments;
	#
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$link = "";
		$keyword = Input::post('keyword');
		$department_id = (int) Input::post('department_id', 0);
		$role_id = (int) Input::post('role_id', 0);
		$status_id = (int) Input::post('status_id', 0);
		$exist_phone = Input::post('exist_phone', '');
		$birthday = Input::post('birthday', '');
		//	var_dump($_POST);die; 
		if($department_id>0) $link .= '&department_id='.$department_id;
		if($role_id>0) $link .= '&role_id='.$role_id;
		if($status_id>0) $link .= '&status_id='.$status_id;
		if($exist_phone != '') $link .= '&exist_phone='.$exist_phone;
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		if(!empty($birthday)) {
			$link .= '&birthday='.$birthday;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	/* End Filter */
	$classTable = "Profile";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*List all item*/
	$department_id = (int) Input::get('department_id', 0);
	$role_id = (int) Input::get('role_id', 0);
	$status_id = (int) Input::get('status_id', 0);
	$exist_phone = Input::get('exist_phone', '');
	$keyword = Input::get('keyword');
	$birthday = Input::get('birthday','');
	$assign_list["department_id"] = $department_id;
	$assign_list["role_id"] = $role_id;
	$assign_list["status_id"] = $status_id;
	$assign_list["keyword"] = $keyword;
	$assign_list["exist_phone"] = $exist_phone; 
	$assign_list["birthday"] = $birthday; 
	#
	$pUrl = ""; $cond = "1='1'";
	#Filter By department_id
	if($department_id > 0){
		$cond .= " and `department_id`='{$department_id}'";
		$pUrl .= "&department_id=".$department_id;
	}
	if($status_id > 0){
		$cond .= " and `status_id`='{$status_id}'";
		$pUrl .= "&status_id=".$status_id;
	}
	if($exist_phone != ''){
		if($exist_phone == 0){
			$cond .= " and `phone`=''";
		}else if($exist_phone == 1){
			$cond .= " and `phone`<>''";
		}
		$pUrl .= "&exist_phone=".$exist_phone;
	}
	if(!empty($birthday)) {
		$month = date("m",strtotime($birthday));
		$cond .= " 	AND FROM_UNIXTIME(`birthday`,'%m') = '{$month}' ";
	}
	#Filter By Keyword
	if(!empty($keyword)){
		$slug = $core->replaceSpace($keyword);
		$cond .= " and (`{$pkeyTable}`='{$keyword}' 
			or `code` like '%{$keyword}%' 
			or `full_name_slug` like '%".$slug."%' 
			or `email` like '%{$keyword}%' 
			or `phone` like '%{$keyword}%'
		)";
		$pUrl .= "&keyword=".$keyword;
	}
	$cond2 = $cond;
	#
	if($type_list=='Trash'){
		$cond .= " and `is_trash`=1";
		$pUrl .= "&type_list=".$type_list;
	} else {
		$cond .= " and `is_trash`=0";
	}
	$orderBy = " `reg_date` desc";
	$assign_list["pUrl"] = $pUrl;
	#-------Page Divide---------------------------------------------------------------
	$recordPerPage = 100;
	$currentPage = (int) Input::get('page',1);
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";
	$totalRecord = $clsClassTable->countItem($cond);
	$totalPage = ceil($totalRecord / $recordPerPage);
	$assign_list['totalRecord'] = $totalRecord;
	$assign_list['recordPerPage'] = $recordPerPage;
	$assign_list['totalPage'] = $totalPage;
	$assign_list['currentPage'] = $currentPage;
	$listPageNumber = array();
	for ($i=1; $i<=$totalPage; $i++){
		$listPageNumber[] = $i;
	}
	$assign_list['listPageNumber'] = $listPageNumber;
	#
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	$link_page_current = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	#
	$link_page_current_2 = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page') // &&$tmp[0]!='type_list'
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$config = array(
		'total'	=> $totalRecord,
		'current_page'	=> $currentPage,
		'number_per_page'	=> $recordPerPage,
		'link'	=> PCMS_URL.'/index.php'.$link_page_current_2
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links();
	$assign_list["html_pager"] = $html_pager;
	#-------End Page Divide-----------------------------------------------------------
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); //$clsISO->print_pre($allItem);die();
	if(!empty($allItem)){
		$arrCached = array();
		$clsProperty = new Property();
		foreach($allItem as $key => $val){
			$status_id = $val['status_id'];
			$department_id = $val['department_id'];
			if($department_id > 0 && isset($arrCached[$department_id])){
				$allItem[$key]['department'] = $arrCached[$department_id];
			} else {
				if($department_id > 0){
					$arrCached[$department_id] = $clsProperty->getTitle($department_id);
					$allItem[$key]['department'] = $arrCached[$department_id];
				} else {
					$allItem[$key]['department'] = '--';
				}
			}
			if($status_id > 0 && isset($arrCached[$status_id])){
				$allItem[$key]['status_name'] = $arrCached[$status_id];
			} else {
				if($status_id > 0){
					$arrCached[$status_id] = sprintf(
						'<span class="label label-default">%s</span>', 
						$clsProperty->getTitle($status_id)
					);
					$allItem[$key]['status_name'] = $arrCached[$status_id];
				} else {
					$allItem[$key]['status_name'] = '--';
				}
			}
			$allItem[$key]['birthday'] = !empty($val['birthday']) ? date("d/m/Y",$val['birthday']) : "--";
		}
	}
	$assign_list["allItem"] = $allItem;
}
function default_import(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
    require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
	$inputFileName = ABSPATH . "/Update_DS_CBNV.xlsx";
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
	
	$tblColumn =array();
	for ($col = 0; $col < $highestColumnIndex; ++ $col) {
		$cell = $worksheet->getCellByColumnAndRow($col, 1);
		$tblColumn[] = $cell->getValue();
	}
	$index = 0;
	$tblData =array();
	for ($row = 2; $row <= $highestRow; ++ $row) {
		for ($col = 0; $col < $highestColumnIndex; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
			if(PHPExcel_Shared_Date::isDateTime($cell)){
				if(!empty($cell)){
					$date= PHPExcel_Shared_Date::ExcelToPHPObject($cell->getValue());
					$tblData[$index][] =  date_format($date,'d/m/Y');
				}else{
					$tblData[$index][] = $cell->getValue();
				}
			}else{
				$tblData[$index][] = $cell->getValue();
			}
		}
		++$index;
	}
	$clsCity = new City();
	$clsProperty = new Property();
	if(!empty($tblData) && 1==2){
		foreach($tblData as $key => $val){
			$code = $val[1];
			$full_name = $val[4];
			$department = $val[5];
			$role = $val[6];
			$email = $val[7];
			$phone = $val[8];
			$birthday = $val[9];
			$CCID = $val[10];
			$city = $val[11];
			$bank_name = $val[12];
			$bank_account = $val[13];
			$address = $val[14];
			$start_date = $val[17];
			$status = $val[18];
			if(!empty($email) && $clsClassTable->countItem("user_name='{$email}'") == 0){
				$name_parts = explode(" ", $full_name);
				if(count($parts) > 1) {
					$last_name = array_pop($name_parts);
					$first_name = implode(" ", $name_parts);
				}else{
					$first_name = $full_name;
					$last_name = " ";
				}
				###
				$city_id = 0;
				if(!empty($city)){
					$tmp = $clsCity->getAll("slug='".$core->replaceSpace($city)."'");
					$city_id = $tmp[0][$clsCity->pkey];
					unset($tmp);
				}
				#
				$department_id = 0;
				if(!empty($department)){
					$tmp = $clsProperty->getAll("property_type='_DEPARTMENT' and slug='".$core->replaceSpace($department)."'");
					$department_id = $tmp[0][$clsProperty->pkey];
					unset($tmp);
				}
				$role_id = 0;
				if(!empty($role)){
					$tmp = $clsProperty->getAll("property_type='_ROLE' and slug='".$core->replaceSpace($role)."'");
					$role_id = $tmp[0][$clsProperty->pkey];
					unset($tmp);
				}
				$status_id = 0;
				if(!empty($role)){
					$tmp = $clsProperty->getAll("property_type='_STATUS_STAFF' and slug='".$core->replaceSpace($status)."'");
					$status_id = $tmp[0][$clsProperty->pkey];
					unset($tmp);
				}
				$more_information = "";
				if(!empty($bank_name) && !empty($bank_account)){
					$more_information = array();
					$more_information['banks_info'] = array(
						 $clsISO->getUniqid() => array(
							'account_number' => $bank_account,
							'account_person' => $full_name,
							'bank_name' => $bank_name,
							'location' => ""
						)
					);
					$more_information = json_encode($more_information);
				}
				$clsClassTable->insert(array(
					$pkeyTable => $clsClassTable->getMaxId(),
					'code' => $code,
					'user_name' => $email,
					'user_pass' => $clsClassTable->encrypt('ca_global@2026'),
					'email' => $email,
					'first_name' => $first_name,
					'last_name' => $last_name,
					'full_name' => $full_name,
					'full_name_slug' => $core->replaceSpace($full_name),
					'status_id' => $status_id,
					'phone' => $phone,
					'address' => $address,
					'country_id' => '1',
					'city_id' => $city_id,
					'department_id' => $department_id,
					'role_id' => $role_id,
					'birthday' => $birthday,
					'start_date' => $start_date,
					'CCID' => $CCID,
					'reg_date' => time(),
					'upd_date' => time(),
					'oauth_provider' => '_register',
					'oauth_email' => $email,
					'more_information' => $more_information,
					'is_active' => 1
				));
				//$clsISO->print_pre($a); die();
			}
		}
	}
}
function default_view(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	
	$classTable = "Profile";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["pkeyTable"] = $pkeyTable;
	$assign_list["clsClassTable"] = $clsClassTable;
	###
	$pvalTable = isset($_GET[$pkeyTable])?intval($_GET[$pkeyTable]):0;
	$assign_list['pvalTable'] = $pvalTable;
	$oneItem = $clsClassTable->getOne($pvalTable);
	$more_information = $oneItem['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = $core->get_field($more_information, "banks_info", []);
	$logs_update = $core->get_field($more_information, "logs_update", []);
	###
	foreach($logs_update as $key => $value){
		$old = $value['old'];
		$update = $value['update'];
		if($update['full_name'] == ""){
			unset($old['full_name'],$update['full_name']);
		}
		if($update['phone'] == ""){
			unset($old['phone'], $update['phone']);
		}
		if($update['birthday'] == ""){
			unset($old['birthday'],$update['birthday']);
		}else{
			$old['birthday'] = date("d/m/Y",$old['birthday']);
			$update['birthday'] = date("d/m/Y",$update['birthday']);
		}
		if($update['gender_id'] == ""){
			unset($old['gender_id'],$update['gender_id']);
		}
		if($update['address'] == ""){
			unset($old['address'],$update['address']);
		}
		$logs_update[$key] = [
			"old"		=>	$old,
			"update"	=>	$update,
			"time"	=>	$value['time']
		];
		unset($old,$update);
	}
	$arr_txt_key = [
		"full_name"	=>	"Họ tên",
		"phone"		=>	"Điện thoại",
		"birthday"	=>	"Ngày sinh",
		"gender_id"	=>	"Giới tính",
		"address"	=>	"Địa chỉ",
	];
	$arr_gender_key = [
		"1"	=>	"Nam",
		"2"		=>	"Nữ",
		"3"	=>	"Khác",
	];
	#
	$assign_list["oneItem"] = $oneItem;
	$assign_list["more_information"] = $more_information;
	$assign_list["banks_info"] = $banks_info;
	$assign_list["logs_update"] = $logs_update;
	$assign_list["arr_txt_key"] = $arr_txt_key;
	$assign_list["arr_gender_key"] = $arr_gender_key;
}
function default_load_sale_logs(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	###
	$user_id = (int) Input::post('user_id',0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 10);
	$clsLog = new Log(); 
	$assign_list["clsLog"] = $clsLog;
	###
	$total_record = $clsLog->countItem("user_id='{$user_id}'");
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$list_logs = $clsLog->getAll("user_id='{$user_id}' order by reg_date DESC");
	$html = "";
	if(!empty($list_logs)){
		$arr_profile_cached = array();
		foreach($list_logs as $key => $_oLog){
			$type = $_oLog['type'];
			$title = !empty($_oLog['title']) ? @strtok($_oLog['title'],'?') : "";
			if($type=='view'){
				$action = "<i class='bx bx-table' ></i> Xem bảng hàng";
				$contentHTML= $title;
			} else if($type='view_stock'){
				$action = "<i class='bx bxs-show'></i> Xem căn hộ";
				$stock_id = $clsLog->getStockId($title);
				$contentHTML= $title;
			} else if($type=='search'){
				$action = "<i class='bx bx-search'></i> Tìm kiếm căn hộ";
				$stock_id = $clsLog->getStockId($title);
				$contentHTML= $title;
			}
			$html.= '<tr>
				<td data-label="H.Động" clas="text-left">'.$action.'</td>
				<td data-label="Thời gian" class="border-end">'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'</td>
				<td data-label="Nội dung">'.$contentHTML.'</td>
			</tr>';
		}
	}
	//	$clsISO->print_pre($list_logs); die;	
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'per_page' => $per_page
	)); die();	
}
function default_load_logs_point(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$clsUserAdmin = new UserAdmin();
	$clsFPoint = new FPoint(); 
	$assign_list["clsFPoint"] = $clsFPoint;
	##
	$html = "";
	$user_id = (int) Input::post('user_id',0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 10);
	##
	$field = "{$clsFPoint->pkey},act,ns_type,score,content,reg_date,user_id";
	$list_logs = $clsFPoint->getAll("`profile_id`='{$user_id}' order by `reg_date` DESC", $field);
	//	var_dump($list_logs);die;
	if(!empty($list_logs)){
		$arr_profile_cached = array();
		foreach($list_logs as $key => $_oLog){			
			if($_oLog['ns_type'] == "plus"){
				$point = "<span class='text-green'>+".$_oLog['score']."</span>";
			}else{
				$point = "<span class='text-red'>-".$_oLog['score']."</span>";
			}
			$content = $_oLog["content"];
			if(!isset($arr_profile_cached[$_oLog["user_id"]])){
				$arr_profile_cached[$_oLog["user_id"]] = $clsUserAdmin->getFullName($_oLog["user_id"]);
			}
				
			if($_oLog['act'] == 'recharge'){
				$content .= (' | Thêm bởi: <strong>'.$arr_profile_cached[$_oLog["user_id"]].'</strong>');
			}
			$html.= '<tr>
				<td data-label="Thời gian" clas="text-left">'.$clsISO->convertTimeToText($_oLog['reg_date'], true).'</td>
				<td data-label="Điểm" class="text-center border-end">'.$point.'</td>
				<td data-label="Nội dung">'.$content.'</td>
			</tr>';
			unset($point);
		}
	}
	//	$clsISO->print_pre($list_logs);die;	
	echo json_encode(array(
		'html' => $html,
	)); die();	
}
function default_edit(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	###
	$clsHubJS = new HubJS();
	$clsCache = new Cache();
	$clsMember = new Member();
	$clsCountry = new Country();
	$clsProperty = new Property();
	$assign_list["clsMember"] = $clsMember;
	$assign_list["clsCountry"] = $clsCountry;
	$assign_list["clsProperty"] = $clsProperty;
	$assign_list["_ROLE_STAFF_ADMIN"] = _ROLE_STAFF_ADMIN;
	###
	$classTable = "Profile";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	###
	$pvalTable = isset($_GET[$pkeyTable])?intval($_GET[$pkeyTable]):0;
	$assign_list['pvalTable'] = $pvalTable;
	###
	$action = '_add'; $old_status_id = 0;
	$oneItem = $more_information = $banks_info = $arrOption = array();
	$html_team_options = '<option value="0">Chọn team</option>';
	$html_role_options = '<option value="0">Chọn vai trò</option>';
	if($pvalTable > 0){
		$action = '_edit';
		$oneItem = $clsClassTable->getOne($pvalTable);
		$old_status_id = $oneItem['status_id'];
		$department_id = $oneItem['department_id'];
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$banks_info = $core->get_field($more_information, "banks_info", []);
		###
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$clsProperty->makeOption($for_id, '_ROLE', 0, $arrOption);
		if(!empty($arrOption)){
			foreach($arrOption as $key => $val){
				$html_role_options .= sprintf(
					'<option value="%s"%s>%s</option>',
					$key, ($oneItem['role_id']==$key ? ' selected':''), $val
				);
			}
		}
		$oneItem["block_ids"] = !empty($oneItem["block_ids"]) ? $clsISO->getArrayByTextSlash($oneItem["block_ids"]) : [];
	}
	$assign_list['action'] = $action;
	$assign_list['oneItem'] = $oneItem;
	$assign_list['html_role_options'] = $html_role_options;
	$assign_list['html_team_options'] = $html_team_options;
	$assign_list['banks_info'] = $banks_info;
	$assign_list['more_information'] = $more_information;
	//	$clsISO->print_pre($more_information);die;  
	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		$about = Input::post('about');
		$banks_info = Input::post('banks_info');
		if(!empty($banks_info)){
			foreach($banks_info as $key => $val){
				if(empty($val['account_number']) 
				   && empty($val['account_person']) 
				   && empty($val['bank_name']) 
				   && empty($val['location'])){
					unset($banks_info[$key]);
				}
			}
		}
		$more_information['banks_info'] = $banks_info;
		$more_information['about'] = addslashes($about);
		//	$clsISO->print_pre($more_information);die;
		if($pvalTable>0){
			$set = ""; $firstAdd = 0;
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$set .= $tmp[1]."='".addslashes($val)."'";
						$firstAdd = 1;
					} else{
						$set .= ",".$tmp[1]."='".addslashes($val)."'";
					}
				}
			}
			#- Special Field
			$first_name = Input::post('iso-first_name');
			$last_name = Input::post('iso-last_name');
			$full_name = $first_name . " " . $last_name;
			$user_pass = Input::post('user_pass');
			$user_cpass = Input::post('user_cpass');
			$block_ids = Input::post('block_ids',[]);
			#
			$errNo = 0; $errMsg = '';
			if(!empty($user_pass)){
				if($user_pass != $user_cpass){
					$errNo ++;
					$errMsg .= $core->get_Lang('Password and confirm password invalid !');
				} else {
					$set.= ",user_pass='".$clsClassTable->encrypt($user_pass)."'";
				}
			}
			#
			$set .= ",upd_date='".time()."',full_name='{$full_name}',full_name_slug='".$core->replaceSpace($full_name)."'
			,more_information='".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'
			,block_ids='".$clsISO->makeSlashListFromArrayRoot($block_ids)."'";
			$getPermalink = $clsClassTable->setPermalink($core->replaceSpace($full_name),1,$pvalTable);
			$set .= ",`permalink`='{$getPermalink}'";
			#--Special Field: image
			$avatar = Input::post('avatar');
			if($avatar!='' && $avatar!='0'){
				$set .= ",`avatar`='".addslashes($avatar)."'";
			}
			#-- Special Field: birthday
			$birthday = Input::post('birthday');
			if(!empty($birthday)){
				$set.=",`birthday`='{$clsISO->toTime($birthday)}'";
			}
			#-- Special Field: start_date
			$status_id = (int) Input::post('iso-status_id', 0);
			$start_date = Input::post('start_date');
			$end_date = Input::post('end_date');
			if(!empty($start_date)){
				$set.=",`start_date`='{$clsISO->toTime($start_date)}'";
			}
			if($status_id == _STATUS_STAFF_OFF_ID && !empty($end_date)){
				$set.=",`end_date`='{$clsISO->toTime($end_date)}'";
			} else if($old_status_id == _STATUS_STAFF_ON_ID && $status_id == _STATUS_STAFF_OFF_ID && empty($end_date)){
				$set.=",`end_date`='".time()."'";
			}
			#--Special Field: department_id
			$department_id = (int) Input::post('iso-department_id', 0);
			$list_department_id = ($department_id > 0) ? $clsProperty->getListParent($department_id) : "";
			$set .= ",`list_department_id`='".addslashes($list_department_id)."'";
			// $clsISO->print_pre($set); die();
			#--Special Field: Notes
			$note = Input::post('note');
			if(!empty($note) && $note != '0'){
				$notes = $clsISO->to_array_json($notes);
				$notes[$clsISO->getUniqid()] = array(
					'content'	=> $note,
					'user_id'	=> $core->_USER['user_id'],
					'user_id_update'	=> $core->_USER['user_id'],
					'reg_date'	=> time(),
					'upd_date'	=> time()
				);
				$set .= ",notes='".json_encode($notes, JSON_UNESCAPED_UNICODE)."'";
			}
			#- End Notes
			if($errNo > 0){
				$assign_list["errMsg"] = $errMsg;
			} else {
				// $clsISO->print_pre($set); die();
				if($clsClassTable->updateOne($pvalTable,$set)) {
					$clsISO->clean_cache('profile');
					$oneProfile = $clsClassTable->getOne($pvalTable);
					$clsClassTable->updateMore($pvalTable, $oneProfile);
					// Send Data
					// $api_results = $clsHubJS->sendData($oneProfile);
					// End Send Data
					$clsMember->updateByCond("`target_id`='{$pvalTable}'",$set);
					if($_POST['button']=='_EDIT'){
						header('Location:'.PCMS_URL.'/?mod='.$mod.'&act='.$act.'&'.$pkeyTable.'='.$pvalTable.'&message=updateSuccess');
						exit();
					} else{
						header('Location:'.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateSuccess');
						exit();
					}
				} else{
					header('Location:'.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateFailed');
					exit();
				}
			}
		} else{
			$value = ""; $firstAdd = 0; $field = "";
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$field .= $tmp[1];
						$value .= "'".addslashes($val)."'";
						$firstAdd = 1;
					} else{
						$field .= ','.$tmp[1];
						$value .= ",'".addslashes($val)."'";
					}
				}
			}
			#
			$first_name = Input::post('iso-first_name');
			$last_name = Input::post('iso-last_name');
			$full_name = sprintf('%s %s', $first_name, $last_name);
			$user_name = Input::post('iso-user_name');
			$user_email = Input::post('iso-email');
			$user_pass = Input::post('user_pass');
			$user_cpass = Input::post('user_cpass');
			$block_ids = Input::post('block_ids',[]);
			#
			$errNo = 0; $errMsg = '';
			$check_user = $clsClassTable->checkValidUsername($user_name);
			if($check_user){
				$errNo ++;
				$errMsg .= $core->get_Lang('This account already exists with that email address');
			}
			#- Valid password
			if($user_pass != $user_cpass){
				$errNo ++;
				$errMsg .= $core->get_Lang('Password and confirm password invalid !');
			}
			#
			$pvalTable = $clsClassTable->getMaxId();
			$field .= ",reg_date,upd_date,full_name,full_name_slug,{$pkeyTable},user_pass,more_information,block_ids";
			$value .= ",'".time()."','".time()."','{$full_name}','".$core->replaceSpace($full_name)."'
			,'{$pvalTable}','".$clsClassTable->encrypt($user_pass)."','".json_encode($more_information, JSON_UNESCAPED_UNICODE)."','".$clsISO->makeSlashListFromArrayRoot($block_ids)."'";
			#--Special Field: avatar
			$avatar = Input::post('avatar');
			if(!empty($avatar)){
				$field.= ",`avatar`";
				$value .= ",'".addslashes($avatar)."'";
			}
			#--Special Field: department_id
			$department_id = (int) Input::post('iso-department_id', 0);
			$list_department_id = ($department_id > 0) ? $clsProperty->getListParent($department_id) : "";
			$field.= ",`list_department_id`";
			$value.= ",'".addslashes($list_department_id)."'";
			#-- Special Field: birthday
			$birthday = Input::post('birthday');
			if(!empty($birthday)){
				$field.= ",`birthday`";
				$value.=",'{$clsISO->toTime($birthday)}'";
			}
			#-- Special Field: start_date
			$start_date = Input::post('start_date');
			if(!empty($start_date)){
				$field.= ",`start_date`";
				$value.=",'{$clsISO->toTime($start_date)}'";
			}
			#--Special Field: Notes
			$note = Input::post('note');
			if(!empty($note) && $note != '0'){
				$notes[$clsISO->getUniqid()] = array(
					'content'	=> $note,
					'user_id'	=> $core->_USER['user_id'],
					'user_id_update'	=> $core->_USER['user_id'],
					'reg_date'	=> time(),
					'upd_date'	=> time()
				);
				$field.= ",`notes`";
				$value .= ",'".addslashes($notes)."'";
			}
			#- End Notes
			if($errNo > 0) {
				$assign_list["errMsg"] = $errMsg;
			} else {
				if($clsClassTable->insertOne($field,$value)){
					$clsISO->clean_cache('profile');
					$oneProfile = $clsClassTable->getOne($pvalTable);
					$clsClassTable->updateMore($pvalTable, $oneProfile);
					#- Send data HubJS
					$api_results = $clsHubJS->sendData($oneProfile);
					#- Send email
					$clsClassTable->sendMailWellcome($pvalTable);
					#- Create MOC
					$member_id = $clsMember->getMaxId();
					$clsMember->insertOne("{$field},{$clsMember->pkey}", "{$value},'{$member_id}'");
					// End
					if ($_POST['button'] == '_EDIT') {
						header('Location:'.PCMS_URL.'/?mod='.$mod.'&act='.$act.'&'.$pkeyTable.'='.$pvalTable.'&message=insertSuccess');
						exit();
					}else {
						header('Location:'.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertSuccess');
						exit();
					}
				} else{
					header('Location:'.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertFailed');
					exit();
				}
			}
		}
	}
}
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error';
	$clsProfile = new Profile();	
	$clsMember = new Member();
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$images = $_FILES['images'];
		$type = Input::post("type","");
		$profile_id = (int) Input::post("profile_id",0);
		if(!empty($images['name']) && $profile_id >0){ $ii = 0; //Init
			$oneProfile = $clsProfile->getOne($profile_id,"more_information");	
			$more_information = $oneProfile['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$arr_images = $core->get_field($more_information, "image", []);
			$results = $clsProfile->uploadImageGoogleDriver($images,$profile_id);
			if($type == "images"){
				$arr_images = array_merge($arr_images,$results);
				$more_information['image'] = $arr_images;
				if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
					$clsMember->updateByCond("`target_id`='{$profile_id}'","`more_information`='".json_encode($more_information)."'");
					// Return
					$html = '';
					if(!empty($results)){
						foreach($results as $image){
							$html .= '<div class="item col-xs-3 mb-3" data-fancybox="gallery" href="'.$image.'">
								<img class="rounded drag-item cursor-pointer" src="'.$image.'" alt="avatar" style="width: 100%;height: auto">
							</div>';
						}
					}
					$msg = '_success|||' .$html;
				}
			}else if($type == 'avatar'){
				if(!empty($results) && $clsProfile->updateOne($profile_id,["avatar"=>$results[0]])){
					$clsMember->updateByCond("target_id='{$profile_id}'","`avatar`='".$results[0]."'");
					$msg = '_success|||' .$results[0]; 
				}
			}else if($type == 'banner'){
				$more_information['banner'] = $results[0];
				if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
					$msg = '_success|||' .$results[0]; 
					$clsMember->updateByCond("`target_id`='{$profile_id}'","`more_information`='".json_encode($more_information)."'");
				}
			}			
		}
	}
	// Return
	echo $msg; die();
}
function default_addVideo(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,
	$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$html = '';
	$clsMember = new Member();
	$clsProfile = new Profile();
	$profile_id = (int)Input::post("profile_id",0);
	$link_file = Input::post("link_file","");
	if(!empty($link_file) && $profile_id >0){ 
		$oneProfile = $clsProfile->getOne($profile_id,"more_information");	
		if(!empty($oneProfile)){
			$more_information = (array)json_decode($oneProfile['more_information']);
			$more_information['link_video'] = $link_file;
			if($clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information)])){
				$clsMember->updateByCond("target_id='{$profile_id}'","`more_information`='".json_encode($more_information)."'");
				$html = $clsISO->getEmbedVideo($link_file,'100%',150); 
			}
		}
	}
	// Return
	echo $html; die();
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Profile";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	#
	$pvalTable = isset($_GET[$pkeyTable])? (int) $_GET[$pkeyTable] : 0;
	if($pvalTable==0){
		 header('location:' . PCMS_URL . '/index.php?&mod=' . $mod . '&message=notPermission');
		 exit();
	}
	if($clsClassTable->updateOne($pvalTable, array(
		'is_trash' => 1,
		'is_active' => 0
	))){
		header('location: '.PCMS_URL.'/?admin&mod='.$mod.'&clsTable='.$pvalTable.'&message=DeleteSuccess');
		exit();
	}
}
function default_load_option_role(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	#
	$html_role_options = '<select class="iso-selectize required" required name="iso-role_id" id="role_id">
		<option value="0">Chọn vai trò</option>';
	$department_id = (int) Input::post('department_id', 0);
	if($department_id > 0){
		$arrOption = array();
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$clsProperty->makeOption($for_id, '_ROLE', 0, $arrOption);
		if(!empty($arrOption)){
			foreach($arrOption as $key => $val){
				$html_role_options .= sprintf('<option value="%s">%s</option>', $key, $val);
			}
		}
	}
	$html_role_options .= '</select>';
	// Return
	echo json_encode(array(
		'html_role_options' => $html_role_options
	)); die();	
}
function default_load_option_block(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$oneProfile;
	$clsProperty = new Property();
	#
	$role_id = (int) Input::post('role_id', 0);
	$block_ids = !empty($oneProfile["block_ids"]) ? $clsISO->getArrayByTextSlash($oneProfile["block_ids"]) : [];
	$html = "";
	$data= ["result"=>false];
	if($role_id == _ROLE_STAFF_ADMIN){
		$html = '<select class="iso-selectize" name="block_ids[]" id="block_ids" multiple placeholder="Dự án">';
		$html .= $clsProperty->getSelectByPropertyV2('_BLOCK',$block_ids,'',0);
		$html .= '</select>';
		$data= ["result"=>true,"html"=>$html];
	}
	// Return
	echo json_encode($data); die();	
}
function default_loadDistrict(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	/**/
	$clsCountry = new Country(); 
	$clsCity = new City();
	$clsDistrict = new District(); 
	#
	$country_id = isset($_POST['country_id'])?intval($_POST['country_id']):1;
	$city_id = isset($_POST['city_id'])?intval($_POST['city_id']):0;
	$district_id = isset($_POST['district_id'])?intval($_POST['district_id']):0;
	#
	$cond = "is_trash=0";
	if(intval($country_id) > 0) $cond.= "  and country_id=".$country_id;
	if(intval($city_id) > 0) $cond.= "  and city_id=".$city_id;
	#
	$allItem = $clsDistrict->getAll($cond." order by slug asc");
	#
	$html = '<option value="0">Chọn quận huyện</option>';
	if(!empty($allItem[0]['district_id'])) {
		for($i=0;$i<count($allItem);$i++){
			$selected = $district_id==$allItem[$i]['district_id']?'selected="selected"':'';
			$html .= '<option '.$selected.' title="'.$clsDistrict->getTitle($allItem[$i]['district_id']).'" value="'.$allItem[$i]['district_id'].'">'.$clsDistrict->getTitle($allItem[$i]['district_id']).'</option>';
		}
	}
	echo($html);die();
}
function default_ajLoadDataCustomerChoice(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$clsMember = new Member();
	###
	$res = array();
	$lstData = $clsMember->GetAll("");
	if(!empty($lstData)){
		foreach($lstData as $customer){
			$res[] = array(
				'id'	=> $customer[$clsMember->pkey],
				'name'	=> $customer['full_name'],
				'email'	=> $customer['email']
			);
		}
	}
	echo json_encode($res); die();
}
function default_ajSaveNote(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$clsISO = new ISO();
	$clsProfile = new Profile();
	#
	$note_id = Input::post('note_id');
	$profile_id = (int) Input::post('profile_id',0);
	$notes = $clsProfile->getOneField('notes', $profile_id);
	#
	$notes = !empty($notes) ? @json_decode($notes,true) : array();
	if(Input::exists('action','GET') && Input::get('action')=='_delete'){
		unset($notes[$note_id]);
	}else{
		$content = Input::post('content');
		if(!empty($note_id)){
			$note = $notes[$note_id];
			$note['content'] = $content;
			$note['user_id_update'] = $core->_USER['user_id'];
			$note['upd_date'] = time();
		} else {
			$note_id = $clsISO->getUniqid();
			$note = array(
				'content'	=> $content,
				'user_id'	=> $core->_USER['user_id'],
				'user_id_update'	=> $core->_USER['user_id'],
				'reg_date'	=> time(),
				'upd_date'	=> time()
			);
		}
		$notes[$note_id] = $note;
	}
	if($clsProfile->updateOne($profile_id,array(
		'notes' => @json_encode($notes)
	))){
		$clsMember = new Member();
		$clsMember->updateByCond("target_id='{$profile_id}'","`notes`='".@json_encode($notes)."'");
	}
	// output
	echo('_success'); die();
}
function default_ajLoadListNote(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsISO,$clsUser;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$clsProfile = new Profile();
	$profile_id = (int) Input::post('profile_id',0);
	$notes = $clsProfile->getOneField('notes',$profile_id);
	$notes = !empty($notes) ? @json_decode($notes,true) : array();
	#
	$html = '';
	if(!empty($notes)){
		$notes = array_reverse($notes);
		$html = '<div class="timeline">';
		foreach($notes as $note_id => $note){
			$html .= '<div class="timeline-item showOnMouseOverContainer">
				<div class="timeline-badge">
					<div class="timeline-icon">
						<i class="fa fa-user"></i>
					</div>
				</div>
				<div class="timeline-body">
					<div class="timeline-body-arrow"></div>
					<div class="timeline-body-head">
						<div class="timeline-body-head-caption">
							<a href="javascript:void(0);" class="timeline-body-alerttitle font-green-haze">'.$clsUser->getFullName($note['user_id']).'</a>
							<small>'.$clsISO->convertTimeToText($note['reg_date']).'</small>
						</div>
						<div class="timeline-body-head-actions">
							<div class="btn-group btn-group-sm">
								<button type="button" note_id="'.$note_id.'" class="btn btn-sm btn-success btnedit_note"><i class="fa fa-pencil"></i></button>
								<button type="button" note_id="'.$note_id.'" class="btn btn-sm btn-danger btn-inverse btndelete_note"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</div>
					<div class="timeline-body-content font-grey-cascade">
						<div class="timeline-body-content__'.$note_id.'">
							<div class="angular-with-newlines">'.html_entity_decode($note['content']).'</div>
						</div>
						<div class="timeline-content-edit__'.$note_id.'" style="display:none">
							<form method="post" action="" enctype="multipart/form-data">
								<div class="form-group">
									 <textarea class="form-control CrmNote_intro_'.$note_id.'" rows="3">'.$note['content'].'</textarea>
								</div>
								<div class="form-group">
									<button type="button" class="btn btn-dafault btncancel_note" member_id="'.$member_id.'" note_id="'.$note_id.'">'.$core->makeIcon('reply',$core->get_Lang('Cancel')).'</button>
									<button type="button" class="btn btn-success btnsave_note" member_id="'.$member_id.'" note_id="'.$note_id.'">'.$core->makeIcon('check',$core->get_Lang('Update')).'</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>';
		}
		$html.= '</div>';
	}else{
		$html = '<div class="text-center">
			<img src="'._IMG_NODOCUMENT.'" width="40px" />
			<p>Không có bất kỳ ghi chú nào</p>
		</div>';
	}
	// output
	echo($html); die();
}
function default_aj_search_members(){
	global $smarty,$assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$clsMember = new Member();
	###
	$results = array();
	$keysearch = Input::get('keysearch');
	if(!empty($keysearch)){
		$slug = $core->replaceSpace($keysearch);
		$field = "{$clsMember->pkey},full_name,email,phone";
		$list_members = $clsMember->getAll("is_trash=0 and is_active='1' and (full_name_slug like '%{$slug}%' 
			or email like '%{$keysearch}%' 
			or phone like '%{$keysearch}%'
		)", $field);
		if(!empty($list_members)){
			foreach($list_members as $key => $val){
				$results[] = array(
					'id' => $val[$clsMember->pkey],
					'text' => $val['full_name'] . '('.$val['email'].')'
				);
			}
			unset($list_members);
		}
	}
	// Return
	echo json_encode($results); die();
}
function default_open_cropper(){
	//ini_set('display_errors', 1);
	//error_reporting(E_ALL ^ E_NOTICE);
	global $smarty,$_frontIsLoggedin_user_id,$core,$clsISO;
	#
	$member_id = Input::post('member_id', 0);
	$imgdata = Input::post('imgdata');
	$smarty->assign('objectUrl', $imgdata);
	#
	$openFrom = Input::post('openFrom', 'image');
	$smarty->assign('openFrom', $openFrom);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.cropper.tpl');
	echo $html; die();
}
function default_upload_avatar(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_lang,$clsConfiguration;
	###
	$clsMember = new Member();
	$clsProfile = new Profile();
	$profile_id = (int) Input::post('member_id' , 0);
	$imgdata = Input::post('imgdata');
	$filename = Input::post('filename');
	if(!$filename) $filename = $clsISO->getUniqid().'.jpg';
	#
	$avatar = ''; 
	$msg = 'error';
	if($imgdata){
		$clsUploadFile = new UploadFile();
		$avatar = $clsUploadFile->base642imagejpeg($imgdata, $filename, "/avatar");
	}
	if(!empty($avatar) && file_exists(ROOTPATH.$avatar)){
		$old_avatar = $clsProfile->getOneField('avatar', $profile_id);
		if(!empty($old_avatar) && @file_exists(ROOTPATH.$old_avatar)){
			@unlink(ROOTPATH.$old_avatar);
		}
		if($clsProfile->updateOne($profile_id, "`avatar`='".addslashes($avatar)."'")){
			$clsMember->updateByCond("target_id='{$profile_id}'","`avatar`='".addslashes($avatar)."'");
			$msg = 'success';
		}
	}
	// Return
	echo $msg.'|||'.$avatar; die();
}
function default_add_bank(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$_frontIsLoggedin_user_id;
	$uid = $clsISO->getUniqid();
	$html = '<div class="bank-item">
		<a class="remove_bank" onClick="remove_bank(this, event)"></a>
		<div class="form-group">
			<label class="colf-form-label">Số tài khoản</label>
			<input type="text" class="form-control account_number numberonly" name="banks_info['.$uid.'][account_number]" 
			placeholder="Số tài khoản" />
		</div>
		<div class="form-group">
			<label class="colf-form-label">Chủ tài khoản</label>
			<input type="text" class="form-control" name="banks_info['.$uid.'][account_person]" placeholder="Chủ tài khoản" />
		</div>
		<div class="form-group row">
			<div class="col-xs-12 col-md-6 pr-0">
				<label class="colf-form-label">Ngân hàng</label>
				<input type="text" class="form-control" mask="Mm/yy" name="banks_info['.$uid.'][bank_name]" placeholder="Tên ngân hàng" />
			</div>
			<div class="col-xs-12 col-md-6">
				<label class="colf-form-label">Chi nhánh</label>
				<input type="text" class="form-control" name="banks_info['.$uid.'][location]" placeholder="Tên chi nhánh (nếu có)" />
			</div>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_open_password(){}
function default_export(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$_frontIsLoggedin_user_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	$callStartTime = microtime(true);
	$objPHPExcel = new PHPExcel();
	
	$creator = PAGE_NAME;
    $titlePage = 'Export User List';
	// Set document properties
    $objPHPExcel->getProperties()->setCreator($creator)
            ->setLastModifiedBy("")
            ->setTitle($titlePage)
            ->setSubject($titlePage)
            ->setDescription("")
            ->setKeywords("Export user list")
            ->setCategory($creator);
	
	//$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
	// End create a new worksheet, after the default sheet
	$objPHPExcel->setActiveSheetIndex(0);
	$tblBackgroundHeader = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'b8cce4;')
        )
    );
	$tblBackgroundHeaderRequired = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'a09f9f;')
        )
    );
	$tblBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'ccc'),
            ),
        ),
    );
	//$clsISO->print_pre($objPHPExcel); die();
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
	$objPHPExcel->getActiveSheet()->setCellValue('B1','Họ và tên');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Số điện thoại');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Email');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Địa chỉ');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Ngày đăng ký');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Địa chỉ IP');
	$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
	// Set document autosize column
	PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
	foreach(range('A','G') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	$type_list = Input::get("type_list", "");
	$department_id = (int)Input::get("department_id", 0);
	$status_id = (int)Input::get("status_id", 0);
	$exist_phone = (int)Input::get("exist_phone", 0);
	$keyword = Input::get("keyword", "");
	$cond = "`is_trash`=0";
	if($department_id > 0){
		$cond .= " and `department_id`='{$department_id}'";
	}
	if($status_id > 0){
		$cond .= " and `status_id`='{$status_id}'";
	}
	if($exist_phone != ''){
		if($exist_phone == 0){
			$cond .= " and `phone`=''";
		}elseif($exist_phone == 1){
			$cond .= " and `phone`<>''";
		}
	}
	#Filter By Keyword
	if(!empty($keyword)){
		$slug = $core->replaceSpace($keyword);
		$cond .= " and (`profile_id`='{$keyword}' 
			or `code` like '%{$keyword}%' 
			or `full_name_slug` like '%".$slug."%' 
			or `email` like '%{$keyword}%' 
			or `phone` like '%{$keyword}%'
		)";
	}
	$cond2 = $cond;
	if($type_list=='Trash'){
		$cond .= " and `is_trash`=1";
	} else {
		$cond .= " and `is_trash`=0";
	}
	// $clsISO->print_pre($cond); die();
	$field = "{$clsProfile->pkey},full_name,first_name,last_name,phone,email,address,reg_date";
	$list_staffs = $clsProfile->getAll("{$cond} order by reg_date DESC", $field);
	if(!empty($list_staffs)){
		$row = 2;
		foreach($list_staffs as $key => $val){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $val[$clsProfile->pkey]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $val['full_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['phone']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['email']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['address']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $clsISO->convertTimeToText($val['reg_date']));
			 $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':G'.$row)->applyFromArray($tblBorderOutline);
			++$row;
		}
	}
	// $clsISO->print_pre($list_staffs); die();
	$nameFile = 'Danh sách người dùng_'.date('dmY');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client's web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.($nameFile).'.xls');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
    $objWriter->save('php://output');
    exit;
    echo(0);
    die();
}
function default_open_permiss(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	#
	$profile_type = 'user.fh';
	$profile_id = Input::post('profile_id', 0);
	$smarty->assign('profile_id', $profile_id);
	#
	$field = "permiss_mod,role_id,department_id,more_information";
	$oProfile = $clsProfile->getOne($profile_id, $field);
	$role_id = $oProfile['role_id'];
	$department_id = $oProfile['department_id'];
	$more_information = $oProfile['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$more_information_role = $clsProperty->getOneField('more_information', $role_id);
	$more_information_role = $clsISO->to_array_json($more_information_role);
	$permiss_mod_role = $core->get_field($more_information_role, "permiss_mod", []); // For role
	
	$permiss_mod = $oProfile['permiss_mod'];
	$permiss_mod = $clsISO->to_array_json($permiss_mod); // For Profile
	// $clsISO->print_pre($permiss_mod); die();
	if(empty($permiss_mod) && !empty($permiss_mod_role)){
		$permiss_mod = $permiss_mod_role;
	} else if(!empty($permiss_mod) && !empty($permiss_mod_role)){
		foreach($permiss_mod_role as $key => $val){
			if(!isset($permiss_mod[$key])){
				$permiss_mod[$key] = $val;
			}
		}
	}
	if(in_array($role_id, array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$clsProject = new Project();
		$field = "{$clsProject->pkey},`title`";
		$list_projects = $clsProject->getAll("`is_menu`=1", $field);
		$title_permiss = 'Admin';
		if($role_id == _ROLE_GD_PROJECT){
			$title_permiss = 'GD dự án';
		}elseif($role_id == _ROLE_GD_SALE){
			$title_permiss = 'GD kinh doanh';
		}
		if(!empty($list_projects)){
			$permiss_billing = isset($more_information['permiss_billing']) 
				? $more_information['permiss_billing'] : array();
			foreach($list_projects as $key => $val){
				$project_id = $val[$clsProject->pkey];
				$list_projects[$key]['permiss_billing'] = isset($permiss_billing[$project_id]) 
					? $permiss_billing[$project_id] : array();
			}
		}
		$smarty->assign('title_permiss', $title_permiss);
		$smarty->assign('list_projects', $list_projects);
		$field = "{$clsProperty->pkey},`title`";
		$list_billing_types = $clsProperty->getCacheItems("_BILLING_TYPE"); 
		$clsProperty->getAll("property_type='_BILLING_TYPE'", $field);
		$smarty->assign('list_billing_types', $list_billing_types);
	}
	// $clsISO->print_pre($permiss_mod); die();
	$field = "{$clsPermiss->pkey},title,code";
	$list_permiss = $clsPermiss->getAll("`parent_id`=0 and `profile_type`='{$profile_type}' and `is_active`=1 order by `order_no` ASC", $field);
	if(!empty($list_permiss)){
		foreach($list_permiss as $key => $val){
			$parent_id = $val[$clsPermiss->pkey];
			$list_items = $clsPermiss->getAll("`parent_id`='{$parent_id}' and `profile_type`='{$profile_type}' and `is_active`=1 
			order by `order_no` ASC", $field);
			if(!empty($list_items)){
				foreach($list_items as $okey => $oval){
					if(in_array($oval['code'], @array_keys($permiss_mod)) && $permiss_mod[$oval['code']] == 1){
						$list_items[$okey]['checked'] = 1;
					} else {
						$list_items[$okey]['checked'] = 0;
					}
				}
			}
			$list_permiss[$key]['list_items'] = $list_items;
		}
	}
	$smarty->assign('list_permiss', $list_permiss);
	// Return
	$html = $core->build('_ajax.permiss.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_pop_save_permiss(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsMember = new Member();
	###
	$msg = "_error";
	$profile_id = Input::post('profile_id', 0);
	$profile_type = Input::post('profile_type', "_user");
	$permiss_mod = Input::post('permiss_mod', array());
	// $clsISO->print_pre($permiss_mod); die();
	$update_field = array();
	$oProfile = $clsProfile->getOne($profile_id, "role_id,permiss_mod,more_information");
	$role_id = $oProfile['role_id'];
	$permiss_mod_old = $oProfile['permiss_mod'];
	$more_information = $oProfile['more_information'];
	$permiss_mod_old = $clsISO->to_array_json($permiss_mod_old);
	$more_information = $clsISO->to_array_json($more_information);
	$__hidden_codes = $clsPermiss->getHiddenCodes('user.fh'); // chua quyen an (is_active=0), khong tat nham khi luu
	if(!empty($permiss_mod_old)){
		foreach($permiss_mod_old as $okey => $oval){
			if(!isset($permiss_mod[$okey])){
				$permiss_mod[$okey] = in_array($okey, $__hidden_codes) ? $oval : 0;
			}
		}
	}
	// $clsISO->print_pre($permiss_mod); die();
	$update_field['permiss_mod'] = json_encode($permiss_mod, JSON_UNESCAPED_UNICODE);
	if(in_array($role_id, array(_ROLE_STAFF_ADMIN, _ROLE_GD_PROJECT,_ROLE_GD_SALE))){
		$permiss_billing = Input::post('permiss_billing');
		$more_information['permiss_billing'] = $permiss_billing;
	}
	$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
	if($clsProfile->updateOne($profile_id, $update_field)){	
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_add_employ(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsProfile = new Profile();
	$profile_id = Input::post('profile_id', 0);
	$profile_type = Input::post('profile_type', "_user");
	###
	$msg = "_error";
	if($clsProfile->updateOne($profile_id, array(
		'profile_type' => $profile_type
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_formAddField(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$clsMember = new Member();
	$clsProfile = new Profile();
	$type = Input::post("type","open");
	$field_id = Input::post("field_id",""); $assign_list['field_id'] = $field_id;
	$field = Input::post("field",""); $assign_list['field'] = $field;
	$member_id = Input::post("member_id",""); $assign_list['member_id'] = $member_id;
	$data = ["result" =>	false];
	if($type == "open"){
		if($field_id != ""){
			$Profile = $clsProfile->getOne($member_id);
			$more_information = $clsISO->to_array_json($Profile['more_information']);
			$arr_field = !empty($more_information[$field])?$more_information[$field]:[];
		 	$assign_list['oneItem'] = $arr_field[$field_id];
		}
		// var_dump($field_id,$field,$arr_field);die;
		$uid = $clsISO->getUniqid();
		$assign_list['uid'] = $uid;
		$html = $core->build('_ajax.formAddField.tpl');
		$data = [
			'result'	=>	true,
			'uid'	=>	$uid,
			'html' => $html,
		];
	}else if($type == 'add'){
		$title = Input::post('title',"");
		$image_hidden = Input::post('image_hidden',"");
		$total_sale_project = (int)Input::post('total_sale_project',"0");
		$star = (int)Input::post('star',"0");
		$content = Input::post('content',"");
		$files = $_FILES['imgdata'];
		$clsUploadFile = new UploadFile();
		$image = array();
		$image["name"] = $files['name'];
		$image["type"] = $files['type'];
		$image["tmp_name"] = $files['tmp_name'];
		$image["error"] = $files['error'];
		$image["size"] = $files['size'];
		$date = Input::post("date","");
		$link_image = $image_hidden;
		$Profile = $clsProfile->getOne($member_id);
		$more_information = $clsISO->to_array_json($Profile['more_information']);
		$arr_field = !empty($more_information[$field])?$more_information[$field]:[];
//		var_dump($image);die;
		if(!empty($image["name"])){
			if(@is_uploaded_file($image['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($image,"/member/".$field,EXTENSION_FILE_UPLOAD);
				$file_name = $image['name'];
				$file_size = $image['size'];
				// Upload file to google drive
				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
				$folder_id = $clsGoogleUpload->create_folder($member_id);
//				 $clsISO->print_pre($folder_id); die();
				$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
				$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';							
				if(!empty($upload_file)){
					$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
				}
				@unlink(ROOTPATH . $upload_file);
				// Update to DB
			}
		}
		$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();
		if(!empty($arr_field[$field_id])){
			$image_old = $arr_field[$field_id]['image'];
		}
		$arr_field[$field_id] = [
			'title'					=>	addslashes($title),
			'total_sale_project'	=>	$total_sale_project,
			'image'					=>	$link_image,
			'star'					=>	$star,
			'content'				=>	addslashes($content),
			'date'					=>	date("d/m/Y"),
		];
		$assign_list['arr_field'] = $arr_field;
		$html = $core->build('_ajax.loadListField.tpl');
		$more_information[$field] = $arr_field;
		if($clsProfile->updateOne($member_id,["more_information"=>json_encode($more_information)])){
			$clsMember->updateByCond("target_id='{$member_id}'","more_information='".json_encode($more_information)."'");
			$data=[
				"result"	=>	true,
				"html"		=>	$html
			];
		}
	}else if($type == "addHistorySale"){
		$stock_code = Input::post("stock_code","");
		$project = Input::post("project","");
		$customer_name = Input::post("customer_name","");
		$price = Input::post("price","");
		$date_trading = Input::post("date_trading","");
		$Profile = $clsProfile->getOne($member_id);
		$more_information = $clsISO->to_array_json($Profile['more_information']);
		$arr_field = !empty($more_information[$field])?$more_information[$field]:[];
		
		$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();
		$arr_field[$field_id] = [
			'stock_code'	=>	$stock_code,
			'project'		=>	$project,
			'customer_name'	=>	$customer_name,
			'price'			=>	$clsISO->processSmartNumber($price),
			'date_trading'	=>	$date_trading
		];
		$assign_list['arr_field'] = $arr_field;
		$html = $core->build('_ajax.loadListField.tpl');
		$more_information[$field] = $arr_field;
		if($clsProfile->updateOne($member_id,["more_information"=>json_encode($more_information)])){
			$clsMember->updateByCond("target_id='{$member_id}'","more_information='".json_encode($more_information)."'");
			$data=[
				"result"	=>	true,
				"html"		=>	$html
			];
		}
	}else if($type == "delete"){
		$Profile = $clsProfile->getOne($member_id);
		if(!empty($Profile)){
			$more_information = $clsISO->to_array_json($Profile['more_information']);
			$arr_field = !empty($more_information[$field])?$more_information[$field]:[];
			$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();
			if($field != "" && $field_id != ""){
				unset($arr_field[$field_id]);
				$assign_list['arr_field'] = $arr_field;
				$html = $core->build('_ajax.loadListField.tpl');
				$more_information[$field] = $arr_field;
				if($clsProfile->updateOne($member_id,["more_information"=>json_encode($more_information)])){
					$clsMember->updateByCond("target_id='{$member_id}'","more_information='".json_encode($more_information)."'");
					$data=[
						"result"	=>	true,
						"html"		=>	$html
					];
				}
			}
		}
	}
	echo json_encode($data); die();
}
function default_send_email(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$clsProfile = new Profile();
	$clsEmailTemplate = new EmailTemplate();
	###
	$msg = "_error";
	$profile_id = (int) Input::post('profile_id', 0);
	if($profile_id > 0){
		$is_send = $clsProfile->sendMailWellcome($profile_id);
		/*$oneMember = $clsProfile->getOne($profile_id);
		$more_information = $clsISO->to_array_json($oneMember["more_information"]);
		$department_name = $more_information["department_name"];
		$staff_name = $clsProfile->getFullName($profile_id, $oneMember);
		$last_name = $clsProfile->getLastName($profile_id, $oneMember, false);
		$staff_email = $oneMember['email'];
		###
//		echo _MAIL_WELLCOME_STAFF_ID;die;
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_WELLCOME_STAFF_ID);
		$subject = $clsEmailTemplate->getSubject(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$message = $clsEmailTemplate->getContent(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$fromname = $clsEmailTemplate->getFromName(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$cc = $clsEmailTemplate->getCopyTo(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		###
		$replace = array(
			'{time}' => date('d/m/Y H:i:s'),
			'{last_name}' => $staff_name,
			'{staff_name}' => ((!empty($department_name)) ? "[".$department_name."]" : "").$staff_name,
			'{staff_email}' => $staff_email
		);
		foreach($replace as $key => $val){
			$subject = str_replace($key, $val, $subject);
			$message = str_replace($key, $val, $message);
		}
		$toname = $staff_name;
		$toemail = trim($staff_email);
		$is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);*/
		if($is_send) $msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_upd_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field');
	$p_value = Input::post('p_value');
	if($p_id > 0 && !empty($p_field)){
		$more_field = array();
		if($p_field == 'department_id'){
			if($p_value > 0)
				$more_field['list_department_id'] = $clsProperty->getListParent($p_value);
		}
		// $clsISO->print_pre($more_field); die();
		if($clsProfile->updateOne($p_id, array_merge($more_field, array(
			$p_field => $p_value
		)))) {
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
/* =========================================================================
 * IMPORT NHÂN SỰ TỪ GOOGLE SHEET (dán link -> chọn/map cột -> thêm/cập nhật)
 * Mẫu tham chiếu: form import bảng hàng của module stock.
 * ========================================================================= */
#- Danh sách trường đích để map cột (key = cột DB / khoá more_information)
function _profile_import_fields(){
	return array(
		'code'          => 'Mã nhân viên',
		'full_name'     => 'Họ và tên',
		'email'         => 'Email (tài khoản) *',
		'department_id' => 'Phòng ban / Bộ phận (ưu tiên)',
		'department_alt' => 'Phòng ban (dự phòng)',
		'role_id'       => 'Chức danh',
		'status_id'     => 'Tình trạng (đang làm/thử việc/nghỉ)',
		'start_date'    => 'Ngày vào làm / vào công ty',
		'end_date'      => 'Ngày nghỉ việc',
		'birthday'      => 'Ngày sinh',
		'phone'         => 'Điện thoại',
		'address'       => 'Địa chỉ',
		'CCID'          => 'Số CCCD',
		'avatar'        => 'Ảnh đại diện',
		'cmnd'          => 'Số CCMG / CMND',
		'cccd_date'     => 'Ngày cấp',
		'cccd_place'    => 'Nơi cấp'
	);
}
#- Đoán trường cho 1 cột theo tên tiêu đề; $used giữ các trường đã gán để không trùng
function _profile_import_guess_field($header, $used){
	$h = function_exists('mb_strtolower') ? mb_strtolower(trim($header), 'UTF-8') : strtolower(trim($header));
	$h = preg_replace('/\s+/', ' ', $h); // gộp xuống dòng/khoảng trắng (tiêu đề Sheet có thể có \n)
	if($h === '') return '';
	$rules = array(
		array('email',         array('email')),
		array('full_name',     array('họ và tên','họ tên','full_name','fullname')),
		array('code',          array('mã số nhân viên','mã nhân viên','mã nv','mã số','mã')),
		array('department_id', array('bộ phận')),
		array('department_alt', array('phòng ban')),
		array('role_id',       array('chức danh','chức vụ','vị trí')),
		array('status_id',     array('tình trạng','trạng thái','tinh trang')),
		array('end_date',      array('ngày nghỉ','nghỉ việc','ngày thôi việc','ngày kết thúc')),
		array('start_date',    array('ngày vào làm','vào làm','ngày vào công ty','vào công ty','ngày vào','ngày ký')),
		array('birthday',      array('ngày sinh','năm sinh','sinh nhật')),
		array('phone',         array('điện thoại','số điện thoại','sđt','phone')),
		array('address',       array('địa chỉ')),
		array('CCID',          array('số cccd','cccd','căn cước')),
		array('avatar',        array('ảnh đại diện','ảnh','avatar')),
		array('cmnd',          array('số ccmg','ccmg','cmnd','cmt','chứng minh')),
		array('cccd_date',     array('ngày cấp')),
		array('cccd_place',    array('nơi cấp'))
	);
	foreach($rules as $r){
		if(isset($used[$r[0]])) continue;
		foreach($r[1] as $kw){
			if(strpos($h, $kw) !== false) return $r[0];
		}
	}
	return '';
}
#- Chuẩn hoá 1 giá trị lấy từ Sheet trước khi đem đi so khớp: bỏ khoảng trắng đặc biệt
#- (NBSP/zero-width do copy từ Word, Drive) và gộp khoảng trắng thừa.
function _profile_import_norm_name($name){
	$name = str_replace(array("\xC2\xA0", "\xE2\x80\x8B", "\xEF\xBB\xBF"), ' ', (string) $name);
	return trim(preg_replace('/\s+/', ' ', $name));
}
#- Sheet nhân sự thường có dòng tiêu đề trang ("DANH SÁCH NHÂN SỰ") nằm trên hàng tên cột.
#- Hàng tên cột là hàng đoán ra được nhiều trường nhất trong các dòng đầu; mọi dòng phía trên bị bỏ.
function _profile_import_header_index($rows){
	$best = 0;
	$best_score = -1;
	$limit = min(10, count($rows));
	for($i = 0; $i < $limit; $i++){
		if(!is_array($rows[$i])) continue;
		$used = array();
		$score = 0;
		foreach($rows[$i] as $cell){
			$guess = _profile_import_guess_field(_profile_import_norm_name($cell), $used);
			if($guess === '') continue;
			$used[$guess] = true;
			$score++;
		}
		if($score <= $best_score) continue; // hoà điểm thì giữ hàng ở trên, tránh nhảy xuống hàng dữ liệu
		$best_score = $score;
		$best = $i;
	}
	return $best;
}
#- Điều kiện tìm 1 property theo tên lấy từ Sheet. Khớp lần lượt mã, tên hiển thị, slug và mã đã bỏ
#- khoảng trắng — Sheet hay ghi "TPS KD HN01" trong khi mã lưu trong hệ thống là "TPSKDHN01".
function _profile_import_property_cond($property_type, $name){
	global $core;
	$esc = addslashes($name);
	$slug = addslashes((string) $core->replaceSpace($name));
	$flat = addslashes(str_replace(' ', '', $name));
	$cond = "`property_type`='".addslashes($property_type)."'";
	$cond .= " and (`property_code`='".$esc."' OR `title`='".$esc."'";
	$cond .= " OR `slug`='".$slug."'";
	$cond .= " OR REPLACE(`property_code`, ' ', '')='".$flat."')";
	return $cond;
}
#- Tìm property theo tên lấy từ Sheet, trả property_id (0 = không khớp).
#- Không dùng getByCond vì hàm đó lấy đại dòng đầu MySQL trả về: default_property đang có 2 dòng cùng mã
#- 'TPS KD HN01' và nó vớ phải dòng cũ nằm sai nhánh cây phòng ban. Ở đây xếp hạng theo độ chắc chắn của
#- tín hiệu khớp (mã > tên > slug), bỏ bản ghi đã xoá, và báo ngược ra cho admin khi còn trùng.
function _profile_import_match_property($clsProperty, $property_type, $name, &$dupes){
	global $core;
	$pkey = $clsProperty->pkey;
	$field = "`{$pkey}`, `property_code`, `title`, `slug`, `is_trash`";
	$rows = $clsProperty->getAll(_profile_import_property_cond($property_type, $name), $field);
	if(empty($rows)) return 0;
	$slug = (string) $core->replaceSpace($name);
	$flat = str_replace(' ', '', $name);
	$ranked = array();
	foreach($rows as $row){
		if((int) $row['is_trash'] !== 0) continue; // phòng ban/chức danh trong thùng rác thì không gán
		$rank = 3;
		if(str_replace(' ', '', (string) $row['property_code']) === $flat) $rank = 2;
		if((string) $row['slug'] === $slug) $rank = 1;
		if((string) $row['title'] === $name) $rank = 1;
		if((string) $row['property_code'] === $name) $rank = 0;
		$ranked[$rank][] = (int) $row[$pkey];
	}
	if(empty($ranked)) return 0;
	ksort($ranked);
	$best = reset($ranked);
	if(count($best) > 1 && !in_array($name, $dupes)) $dupes[] = $name;
	rsort($best); // còn trùng thì lấy bản ghi mới nhất, bản cũ thường là nhánh đã bỏ
	return $best[0];
}
#- 1 dòng rỗng khi mọi ô đều trống
function _profile_import_row_empty($row){
	if(!is_array($row)) return true;
	foreach($row as $cell){
		if(trim((string) $cell) !== '') return false;
	}
	return true;
}
#- Đổi link Google Sheet -> URL export CSV (tách id + gid)
function _profile_import_csv_url($url){
	$id = '';
	$gid = '';
	if(preg_match('#/spreadsheets/d/([a-zA-Z0-9_-]+)#', $url, $m)) $id = $m[1];
	if(preg_match('#[?&\#]gid=([0-9]+)#', $url, $m)) $gid = $m[1];
	if(empty($id)) return '';
	$export = 'https://docs.google.com/spreadsheets/d/'.$id.'/export?format=csv';
	if($gid !== '') $export .= '&gid='.$gid;
	return $export;
}
#- Tải nội dung 1 URL (curl -> fallback file_get_contents)
function _profile_import_http_get($url, $timeout = 30){
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		$data = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($data !== false && $code >= 200 && $code < 400) return $data;
		return '';
	}
	$ctx = stream_context_create(array(
		'http' => array('timeout' => $timeout, 'follow_location' => 1),
		'ssl'  => array('verify_peer' => false, 'verify_peer_name' => false)
	));
	$data = @file_get_contents($url, false, $ctx);
	return ($data !== false) ? $data : '';
}
#- Link chia sẻ Google Drive (/file/d/ID/view, open?id=ID, uc?id=ID) trỏ tới trang xem, không phải file ảnh.
#- Đổi sang endpoint thumbnail để tải được đúng bytes ảnh (và nhẹ hơn bản gốc trong Drive).
function _profile_import_direct_image_url($url){
	if(stripos($url, 'google.com') === false) return $url;
	$id = '';
	if(preg_match('#/file/d/([a-zA-Z0-9_-]{10,})#', $url, $m)){
		$id = $m[1];
	} else if(preg_match('#[?&]id=([a-zA-Z0-9_-]{10,})#', $url, $m)){
		$id = $m[1];
	}
	if($id === '') return $url;
	return 'https://drive.google.com/thumbnail?id='.$id.'&sz=w600';
}
#- Tải ảnh đại diện từ link trong Sheet về /images/avatar/, trả đường dẫn nội bộ ('' nếu không tải được).
#- Phải tải về chứ không lưu thẳng link Drive: cột avatar chứa URL ngoài thì Profile::getAvatar() trả
#- nguyên URL đó, nên mỗi lần mở danh sách nhân sự trình duyệt gọi sang Google cho từng dòng và tải ảnh
#- cỡ gốc (không qua được /files/thumb).
function _profile_import_fetch_avatar($url, $code){
	$url = trim($url);
	if($url === '') return '';
	if(strpos($url, '/') === 0) return $url; // đã là đường dẫn sẵn trong host
	if(!preg_match('#^https?://#i', $url)) return '';
	$dir = ROOTPATH.ftp_abs_path_info.'/avatar';
	if(!is_dir($dir) && !@mkdir($dir, 0777, true)) return '';
	$slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($code)), '-');
	$stem = ($slug !== '' ? $slug.'-' : '').substr(md5($url), 0, 10);
	#- Tên file suy ra từ chính link nguồn: chạy lại import không tải lại ảnh đã có
	foreach(array('jpg', 'png', 'gif', 'webp') as $known){
		if(file_exists($dir.'/'.$stem.'.'.$known)) return ftp_abs_path_info.'/avatar/'.$stem.'.'.$known;
	}
	$data = _profile_import_http_get(_profile_import_direct_image_url($url), 20);
	if($data === '' || strlen($data) > 8388608) return ''; // 8MB: quá cỡ 1 ảnh đại diện, nhiều khả năng không phải ảnh
	$info = @getimagesizefromstring($data);
	if(empty($info[2])) return ''; // Drive trả trang HTML khi file chưa chia sẻ công khai
	$ext_map = array(IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp');
	if(!isset($ext_map[$info[2]])) return '';
	$name = $stem.'.'.$ext_map[$info[2]];
	if(@file_put_contents($dir.'/'.$name, $data) === false) return '';
	return ftp_abs_path_info.'/avatar/'.$name;
}
#- Parse chuỗi CSV -> mảng dòng (fgetcsv xử lý ô có dấu phẩy/xuống dòng trong ngoặc kép).
#- Dùng php://temp thay tempnam(): stream tự huỷ khi fclose/kết thúc script, nên lúc tiến trình bị
#- kill giữa chừng cũng không để lại file rác trong /tmp (thư mục này còn đang chứa session).
function _profile_import_parse_csv($csv){
	$rows = array();
	$mem_limit_byte = 8 * 1024 * 1024; // giữ trong RAM tới ngưỡng này, vượt thì PHP tự tràn ra file tạm
	$h = fopen('php://temp/maxmemory:'.$mem_limit_byte, 'r+');
	if($h === false) return $rows;
	fwrite($h, $csv);
	rewind($h);
	while(($data = fgetcsv($h, 0, ',')) !== false){
		$rows[] = $data;
	}
	fclose($h);
	return $rows;
}
#- Nạp 1 lần quan hệ cha-con của bảng property (id => parent_id) + đánh dấu id nào là phòng ban.
#- Thay cho Property::getListParent() trong vòng lặp import: hàm đó SELECT * cả bảng phòng ban rồi
#- đệ quy 1 query/cấp cho MỖI dòng -> đủ để LiteSpeed giết tiến trình và trả 503.
function _profile_import_dept_tree($clsProperty){
	$tree = array('parent' => array(), 'is_dept' => array());
	$pkey = $clsProperty->pkey;
	#- Lấy cả bảng (mọi property_type) nhưng chỉ 3 cột nhẹ, để giữ nguyên hành vi leo cây của checkIsParent()
	$rows = $clsProperty->getAll("", "`{$pkey}`, `parent_id`, `property_type`");
	if(empty($rows)) return $tree;
	foreach($rows as $row){
		$id = (int) $row[$pkey];
		$tree['parent'][$id] = (int) $row['parent_id'];
		if($row['property_type'] === '_DEPARTMENT') $tree['is_dept'][$id] = true;
	}
	return $tree;
}
#- Chính nó + mọi tổ tiên là phòng ban -> "|id|id|...|" (đúng định dạng Property::getListParent trả về)
function _profile_import_list_department($department_id, $tree){
	$ids = array($department_id);
	$seen = array($department_id => true);
	$current = $department_id;
	while(isset($tree['parent'][$current])){
		$parent = $tree['parent'][$current];
		if($parent <= 0) break;
		if(isset($seen[$parent])) break; // cây dữ liệu lỗi trỏ vòng -> dừng, tránh lặp vô hạn
		$seen[$parent] = true;
		if(isset($tree['is_dept'][$parent])) $ids[] = $parent;
		$current = $parent;
	}
	return '|'.implode('|', $ids).'|';
}
#- Bước 1: mở modal nhập link Google Sheet
function default_open_import(){
	global $smarty, $core, $clsConfiguration;
	$smarty->assign('core', $core);
	#- Chưa cấu hình ở màn Cấu hình hệ thống thì để rỗng, admin tự nhập
	$smarty->assign('default_pass', $clsConfiguration->getValue('profile_default_pass', ''));
	// Return
	echo $core->build('_ajax.open_import.tpl');
	die();
}
#- Bước 2: tải Sheet -> cache JSON -> render bảng preview kèm select map cột
function default_read_import(){
	global $core, $clsISO, $mod;
	$sheet_url = trim(Input::post('sheet_url', ''));
	$default_pass = trim(Input::post('default_pass', ''));
	if(empty($sheet_url)){ echo 'ERROR|||Vui lòng dán link Google Sheet.'; die(); }
	#- Bắt buộc có mật khẩu ngay từ bước này, tài khoản mới tạo ở bước 3 luôn cần một mật khẩu thật
	if($default_pass === ''){ echo 'ERROR|||Vui lòng nhập mật khẩu mặc định cho tài khoản mới.'; die(); }
	$csv_url = _profile_import_csv_url($sheet_url);
	if(empty($csv_url)){ echo 'ERROR|||Link Google Sheet không hợp lệ.'; die(); }
	$csv = _profile_import_http_get($csv_url);
	if(empty($csv)){ echo 'ERROR|||Không tải được dữ liệu. Kiểm tra Sheet đã chia sẻ công khai (Bất kỳ ai có đường liên kết) chưa.'; die(); }
	if(stripos(substr($csv, 0, 300), '<html') !== false){ echo 'ERROR|||Sheet đang riêng tư. Hãy đặt chia sẻ công khai rồi thử lại.'; die(); }
	$rows = _profile_import_parse_csv($csv);
	unset($csv); // sheet đã nằm trong $rows, thả chuỗi CSV để không ôm 2 bản cùng lúc
	while(!empty($rows) && _profile_import_row_empty($rows[0])){ array_shift($rows); }
	if(count($rows) < 2){ echo 'ERROR|||Sheet không có dữ liệu nhân sự.'; die(); }
	if(isset($rows[0][0])){ $rows[0][0] = preg_replace('/^\xEF\xBB\xBF/', '', $rows[0][0]); }
	#- Cắt bỏ các dòng tiêu đề trang phía trên hàng tên cột để $rows[0] luôn là hàng tên cột
	$head_at = _profile_import_header_index($rows);
	if($head_at > 0) $rows = array_slice($rows, $head_at);
	if(count($rows) < 2){ echo 'ERROR|||Sheet không có dữ liệu nhân sự.'; die(); }
	$header = $rows[0];
	$colCount = 0;
	foreach($rows as $r){ if(count($r) > $colCount) $colCount = count($r); }
	$uid = $clsISO->getUniqid();
	if(!is_dir(DIR_CACHE_JSON)){ @mkdir(DIR_CACHE_JSON, 0777, true); }
	$json = json_encode($rows, JSON_UNESCAPED_UNICODE);
	@file_put_contents(DIR_CACHE_JSON.'/'.$uid.'.json', $json);
	unset($json); // thả bản JSON ngay sau khi ghi, phần dưới chỉ còn cần $rows
	$fields = _profile_import_fields();
	$total_data = count($rows) - 1;
	#- Lấy tối đa 2 ví dụ dữ liệu cho mỗi cột (dòng có giá trị)
	$samples = array();
	for($col = 0; $col < $colCount; $col++){
		$vals = array();
		for($i = 1; $i < count($rows) && count($vals) < 2; $i++){
			$c = isset($rows[$i][$col]) ? trim((string) $rows[$i][$col]) : '';
			if($c !== '') $vals[] = $c;
		}
		$samples[$col] = $vals;
	}
	#- Mỗi cột 1 hàng dọc: tên cột · ví dụ · select gán trường (gọn, vừa modal thường)
	$used = array();
	$maprows = '';
	for($col = 0; $col < $colCount; $col++){
		$hname = isset($header[$col]) ? trim(preg_replace('/\s+/', ' ', (string) $header[$col])) : '';
		$guess = _profile_import_guess_field($hname, $used);
		if($guess !== '') $used[$guess] = true;
		$opts = '<option value="">-- Bỏ qua --</option>';
		foreach($fields as $fk => $fl){
			$sel = ($fk === $guess) ? ' selected' : '';
			$opts .= '<option value="'.$fk.'"'.$sel.'>'.htmlspecialchars($fl).'</option>';
		}
		$ex = htmlspecialchars(implode(' · ', $samples[$col]));
		$maprows .= '<tr>
			<td><b>'.htmlspecialchars($hname).'</b></td>
			<td class="text-muted" style="max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="'.$ex.'">'.$ex.'</td>
			<td><select name="columns['.$col.']" class="form-control input-sm">'.$opts.'</select></td>
		</tr>';
	}
	$html = '<div class="modal-dialog" style="width:640px;max-width:96vw">
		<div class="modal-content">
			<div class="modal-header">
				<a href="javascript:void(0)" class="closeEv close_pop close"><span>×</span></a>
				<h3 class="modal-title"><strong>Import nhân sự — chọn cột ('.$total_data.' dòng)</strong></h3>
			</div>
			<form method="POST" id="form_profile_do_import">
				<div class="modal-body" style="max-height:60vh;overflow:auto">
					<p class="text-muted mb-1">Gán mỗi cột trong Sheet vào 1 trường (đã tự chọn sẵn, chỉnh nếu cần). Bắt buộc có <b>Email</b>.</p>
					<table class="table table-bordered table-sm mb-0">
						<thead><tr><th style="width:34%">Cột trong Sheet</th><th style="width:26%">Ví dụ</th><th style="width:40%">Gán vào trường</th></tr></thead>
						<tbody>'.$maprows.'</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<div class="form-inline mb-2">
						<label class="col-form-label mr-1">Khi trùng Email:</label>
						<div class="radio mr-2"><input name="opt_over" id="p_imp_insert" type="radio" value="Insert" checked> <label for="p_imp_insert">Thêm mới</label></div>
						<div class="radio"><input name="opt_over" id="p_imp_update" type="radio" value="Update"> <label for="p_imp_update">Cập nhật</label></div>
					</div>
					<div class="form-inline mb-2">
						<label class="col-form-label mr-1">Mật khẩu (tài khoản mới):</label>
						<div class="radio mr-1"><input name="pass_mode" id="p_pm_def" type="radio" value="default" checked> <label for="p_pm_def">Mặc định</label></div>
						<input type="text" name="default_pass" value="'.htmlspecialchars($default_pass).'" class="form-control input-sm mr-3" style="width:150px" />
						<div class="radio"><input name="pass_mode" id="p_pm_cccd" type="radio" value="cccd"> <label for="p_pm_cccd">= Số CCCD (cột đã gán)</label></div>
					</div>
					<input type="hidden" name="uid" value="'.$uid.'" />
					<button type="button" class="btn btn-default mr-2" onclick="$Core.popup.close($(\'#profile_import_map\'))">'.$core->get_Lang('Close').'</button>
					<button type="button" class="btn btn-success" onClick="do_import(this, event)"><span>Import vào hệ thống</span></button>
				</div>
			</form>
		</div>
	</div>';
	// Return
	echo 'OK|||'.$html; die();
}
#- Bước 3: đọc cache + mapping -> thêm/cập nhật nhân sự, trả JSON tổng kết
function default_do_import(){
	global $core, $clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = preg_replace('/[^a-zA-Z0-9]/', '', Input::post('uid', ''));
	$columns = Input::post('columns', array());
	$opt_over = Input::post('opt_over', 'Insert');
	$default_pass = trim(Input::post('default_pass', ''));
	$pass_mode = Input::post('pass_mode', 'default'); // 'default' | 'cccd' (= Số CCCD từ cột đã map)
	$cachedFile = DIR_CACHE_JSON.'/'.$uid.'.json';
	$rows = array();
	if($uid !== '' && file_exists($cachedFile)){
		$rows = json_decode(file_get_contents($cachedFile), true);
		@unlink($cachedFile);
	}
	if(empty($rows)){ echo json_encode(array('result' => '_error', 'message' => 'Hết phiên dữ liệu, vui lòng tải lại Sheet.')); die(); }
	#- Map trường -> chỉ số cột (mỗi trường 1 cột)
	$map = array();
	$dupe = 0;
	if(is_array($columns)){
		foreach($columns as $col => $field){
			if($field === '' || $field === null) continue;
			if(isset($map[$field])){ $dupe++; continue; }
			$map[$field] = (int) $col;
		}
	}
	if($dupe > 0){ echo json_encode(array('result' => '_error', 'message' => 'Có 2 cột trỏ về cùng 1 trường. Mỗi trường chỉ chọn 1 cột.')); die(); }
	if(!isset($map['code']) && !isset($map['email'])){ echo json_encode(array('result' => '_error', 'message' => 'Bắt buộc map ít nhất Mã nhân viên hoặc Email để chống trùng.')); die(); }
	if($pass_mode === 'cccd' && !isset($map['CCID'])){ echo json_encode(array('result' => '_error', 'message' => 'Chọn mật khẩu = Số CCCD nhưng chưa gán cột "Số CCCD". Hãy map cột đó rồi thử lại.')); die(); }
	#- Không có mật khẩu mặc định thì dừng, tránh tạo tài khoản mật khẩu trống (chế độ CCCD vẫn cần nó cho dòng thiếu CCCD)
	if($default_pass === ''){ echo json_encode(array('result' => '_error', 'message' => 'Chưa nhập mật khẩu mặc định cho nhân sự mới.')); die(); }
	@set_time_limit(300);
	$pkey = $clsProfile->pkey;
	$inserted = $updated = $skipped = $noid = $failed = 0;
	$avatar_failed = $avatar_skipped = 0;
	$unmatched_dept = $unmatched_role = $dupe_prop = array();
	$started_at = time();
	#- Trần thời gian dành cho việc tải ảnh đại diện, chừa phần còn lại của set_time_limit cho việc ghi DB
	$avatar_budget_sec = 180;
	#- id trạng thái "Đang thử việc" (tra 1 lần; fallback = đang làm việc)
	$tmpProb = $clsProperty->getByCond("`property_type`='_STATUS_STAFF' and `slug`='dang-thu-viec'", $clsProperty->pkey);
	$status_probation_id = !empty($tmpProb) ? (int) $tmpProb[$clsProperty->pkey] : _STATUS_STAFF_ON_ID;
	$status_cache = array(); // nhớ kết quả khớp tình trạng theo từng giá trị (tránh query lặp)
	$dept_cache = array(); // tên phòng ban đã tra -> property_id (0 = không khớp)
	$role_cache = array(); // tên chức danh đã tra -> property_id (0 = không khớp)
	$dept_tree = _profile_import_dept_tree($clsProperty); // nạp 1 lần, dùng thay getListParent() ở mỗi dòng
	$getVal = function($row, $field) use ($map){
		return (isset($map[$field]) && isset($row[$map[$field]])) ? trim((string) $row[$map[$field]]) : '';
	};
	for($i = 1; $i < count($rows); $i++){
		$row = $rows[$i];
		if(_profile_import_row_empty($row)) continue;
		#- Định danh chống trùng: Mã NV (code) là khoá chính, email là phụ
		$email = strtolower($getVal($row, 'email'));
		$code = $getVal($row, 'code');
		if($email === '' && $code === ''){ $noid++; continue; }
		#- Chống trùng: khoá chính = Mã NV (code), phụ = email/user_name. Tra sớm để dòng sẽ bị bỏ qua
		#- không phải tra phòng ban/chức danh và nhất là không phải tải ảnh đại diện.
		$existing = array();
		if($code !== ''){
			$existing = $clsProfile->getByCond("`code`='".addslashes($code)."'", $pkey);
		}
		if(empty($existing) && $email !== ''){
			$existing = $clsProfile->getByCond("`user_name`='".addslashes($email)."'", $pkey);
		}
		if(!empty($existing) && $opt_over !== 'Update'){ $skipped++; continue; }
		$full_name = $getVal($row, 'full_name');
		#- Phòng ban: thử cột chính (Bộ phận/leaf) trước, rỗng hoặc không khớp thì thử cột dự phòng (Phòng ban/cấp trên)
		$department_id = 0;
		$dept_report = '';
		foreach(array($getVal($row, 'department_id'), $getVal($row, 'department_alt')) as $dept_raw){
			$dept_name = _profile_import_norm_name($dept_raw);
			if($dept_name === '') continue;
			if($dept_report === '') $dept_report = $dept_name;
			if(!array_key_exists($dept_name, $dept_cache)){
				$dept_cache[$dept_name] = _profile_import_match_property($clsProperty, '_DEPARTMENT', $dept_name, $dupe_prop);
			}
			if($dept_cache[$dept_name] > 0){ $department_id = $dept_cache[$dept_name]; break; }
		}
		if($department_id === 0 && $dept_report !== '' && !in_array($dept_report, $unmatched_dept)) $unmatched_dept[] = $dept_report;
		$role_id = 0;
		$role_name = _profile_import_norm_name($getVal($row, 'role_id'));
		if($role_name !== ''){
			if(!array_key_exists($role_name, $role_cache)){
				$role_cache[$role_name] = _profile_import_match_property($clsProperty, '_ROLE', $role_name, $dupe_prop);
			}
			$role_id = $role_cache[$role_name];
			if($role_id === 0 && !in_array($role_name, $unmatched_role)) $unmatched_role[] = $role_name;
		}
		$first_name = $full_name;
		$last_name = ' ';
		$parts = preg_split('/\s+/', trim($full_name));
		if(count($parts) > 1){
			$last_name = array_pop($parts);
			$first_name = implode(' ', $parts);
		}
		$more_extra = array();
		$cmnd = $getVal($row, 'cmnd');
		$cccd_date = $getVal($row, 'cccd_date');
		$cccd_place = $getVal($row, 'cccd_place');
		if($cmnd !== '') $more_extra['cmnd'] = $cmnd;
		if($cccd_date !== '') $more_extra['cccd_issue_date'] = $cccd_date;
		if($cccd_place !== '') $more_extra['cccd_issue_place'] = $cccd_place;
		#- Bộ trường chung (thêm & cập nhật)
		$fieldset = array();
		$phone = $getVal($row, 'phone');
		$address = $getVal($row, 'address');
		$ccid = $getVal($row, 'CCID');
		$avatar = '';
		$avatar_src = $getVal($row, 'avatar');
		if($avatar_src !== ''){
			#- Ngừng tải ảnh khi hết ngân sách để phần ghi DB còn kịp chạy; chạy lại import sẽ tải nốt
			if(time() - $started_at >= $avatar_budget_sec){
				$avatar_skipped++;
			} else {
				$avatar = _profile_import_fetch_avatar($avatar_src, $code);
				if($avatar === '') $avatar_failed++;
			}
		}
		$birthday = $getVal($row, 'birthday');
		$start_date = $getVal($row, 'start_date');
		if($code !== '') $fieldset['code'] = $code;
		if($email !== '') $fieldset['email'] = $email;
		if($full_name !== ''){
			$fieldset['full_name'] = $full_name;
			$fieldset['full_name_slug'] = $core->replaceSpace($full_name);
			$fieldset['first_name'] = $first_name;
			$fieldset['last_name'] = $last_name;
		}
		if($phone !== '') $fieldset['phone'] = $phone;
		if($address !== '') $fieldset['address'] = $address;
		if($ccid !== '') $fieldset['CCID'] = $ccid;
		if($avatar !== '') $fieldset['avatar'] = $avatar;
		if($birthday !== '') $fieldset['birthday'] = $clsISO->toTime($birthday);
		if($start_date !== '') $fieldset['start_date'] = $clsISO->toTime($start_date);
		#- Tình trạng -> status_id: ưu tiên khớp _STATUS_STAFF theo mã/tên/slug, không khớp mới suy đoán từ khoá
		$status_txt = _profile_import_norm_name($getVal($row, 'status_id'));
		if($status_txt !== ''){
			if(!array_key_exists($status_txt, $status_cache)){
				$matched_status = _profile_import_match_property($clsProperty, '_STATUS_STAFF', $status_txt, $dupe_prop);
				if($matched_status > 0){
					$status_cache[$status_txt] = $matched_status;
				} else {
					$st = function_exists('mb_strtolower') ? mb_strtolower($status_txt, 'UTF-8') : strtolower($status_txt);
					if(strpos($st, 'nghỉ') !== false || strpos($st, 'nghi') !== false){
						$status_cache[$status_txt] = _STATUS_STAFF_OFF_ID;
					} else if(strpos($st, 'thử') !== false || strpos($st, 'tts') !== false || strpos($st, 'thực tập') !== false || strpos($st, 'thu viec') !== false){
						$status_cache[$status_txt] = $status_probation_id;
					} else {
						$status_cache[$status_txt] = _STATUS_STAFF_ON_ID;
					}
				}
			}
			$fieldset['status_id'] = $status_cache[$status_txt];
		}
		#- Ngày nghỉ việc -> end_date
		$end_date = $getVal($row, 'end_date');
		if($end_date !== '') $fieldset['end_date'] = $clsISO->toTime($end_date);
		if($department_id > 0){
			$fieldset['department_id'] = $department_id;
			$fieldset['list_department_id'] = _profile_import_list_department($department_id, $dept_tree);
		}
		if($role_id > 0) $fieldset['role_id'] = $role_id;
		$fieldset['upd_date'] = time();
		if(!empty($existing)){
			if(!empty($more_extra)){
				$one = $clsProfile->getOne((int) $existing[$pkey], 'more_information');
				$exMore = $clsISO->to_array_json($one['more_information']);
				$fieldset['more_information'] = json_encode(array_merge($exMore, $more_extra), JSON_UNESCAPED_UNICODE);
			}
			if($clsProfile->updateOne((int) $existing[$pkey], $fieldset)){ $updated++; } else { $failed++; }
		} else {
			#- Người mới: user_name = email nếu có, không thì lấy Mã NV (nhân sự cũ/đã nghỉ không cần đăng nhập)
			$login = ($email !== '') ? $email : $code;
			$fieldset[$pkey] = $clsProfile->getMaxId();
			$fieldset['user_name'] = $login;
			#- Mật khẩu: = Số CCCD nếu chọn chế độ 'cccd' và dòng có CCID, không thì mật khẩu mặc định
			$pass_plain = ($pass_mode === 'cccd' && $ccid !== '') ? $ccid : $default_pass;
			if($pass_plain === ''){ $failed++; continue; } // thiếu cả CCCD lẫn mật khẩu mặc định -> bỏ dòng, không tạo tài khoản trống
			$fieldset['user_pass'] = $clsProfile->encrypt($pass_plain);
			$fieldset['oauth_provider'] = '_register';
			if($email !== '') $fieldset['oauth_email'] = $email;
			$fieldset['country_id'] = 1;
			if(!isset($fieldset['status_id'])) $fieldset['status_id'] = _STATUS_STAFF_ON_ID;
			$fieldset['is_active'] = 1;
			$fieldset['reg_date'] = time();
			if(!empty($more_extra)) $fieldset['more_information'] = json_encode($more_extra, JSON_UNESCAPED_UNICODE);
			if($clsProfile->insert($fieldset)){ $inserted++; } else { $failed++; }
		}
	}
	$clsISO->clean_cache('profile');
	$clsActivityLog = new ActivityLog();
	$clsActivityLog->addActivityLog("Profile", "insert", array('title' => sprintf('Import nhân sự: +%d mới, %d cập nhật, %d bỏ qua', $inserted, $updated, $skipped)));
	$msg = sprintf('Hoàn tất (chống trùng theo Mã NV): <b>%d</b> thêm mới, <b>%d</b> cập nhật, <b>%d</b> bỏ qua (đã có, chế độ Thêm mới), <b>%d</b> thiếu cả Mã NV & Email', $inserted, $updated, $skipped, $noid);
	if($failed > 0) $msg .= ', <b>'.$failed.'</b> lỗi';
	$msg .= '.';
	if(!empty($unmatched_dept)) $msg .= '<br><span class="text-danger">Phòng ban không khớp (để trống):</span> '.htmlspecialchars(implode(', ', $unmatched_dept));
	if(!empty($unmatched_role)) $msg .= '<br><span class="text-danger">Chức danh không khớp (để trống):</span> '.htmlspecialchars(implode(', ', $unmatched_role));
	if(!empty($dupe_prop)) $msg .= '<br><span class="text-warning">Trùng mã trong danh mục (đã lấy bản ghi mới nhất):</span> '.htmlspecialchars(implode(', ', $dupe_prop)).' — nên gộp lại ở Danh mục phòng ban / chức danh.';
	if($avatar_failed > 0) $msg .= '<br><span class="text-danger">Không tải được '.$avatar_failed.' ảnh đại diện</span> — kiểm tra link ảnh đã chia sẻ "Bất kỳ ai có đường liên kết" chưa.';
	if($avatar_skipped > 0) $msg .= '<br><span class="text-warning">Còn '.$avatar_skipped.' ảnh chưa tải do quá thời gian.</span> Chạy lại import ở chế độ Cập nhật để tải nốt.';
	echo json_encode(array('result' => '_success', 'message' => $msg));
	die();
}
?>