<?php
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$dbconn;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	#
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$department_id = (int) Input::post('department_id', 0);
		$role_id = (int) Input::post('role_id', 0);
		$status_id = (int) Input::post('status_id', 0);
		$exist_phone = Input::post('exist_phone', '');
		$is_FH = Input::post('is_FH', '');
		if($department_id>0) $link .= '&department_id='.$department_id;
		if($role_id>0) $link .= '&role_id='.$role_id;
		if($status_id>0) $link .= '&status_id='.$status_id;
		if($exist_phone != '') $link .= '&exist_phone='.$exist_phone;
		if($is_FH != '') $link .= '&is_FH='.$is_FH;
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	/* End Filter */
	$classTable = "Member";
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
	$is_FH = Input::get('is_FH', '');
	$keyword = Input::get('keyword');
	$assign_list["department_id"] = $department_id;
	$assign_list["role_id"] = $role_id;
	$assign_list["status_id"] = $status_id;
	$assign_list["keyword"] = $keyword;
	$assign_list["exist_phone"] = $exist_phone; 
	$assign_list["is_FH"] = $is_FH; 
	// $all = $clsClassTable->getAll("1=1", "{$pkeyTable},more_information");
	// foreach($all as $key => $val){
	//	$more_information = $val['more_information'];
	//	$more_information = $clsISO->to_array_json($more_information);
	//	$permiss_mod = isset($more_information['permiss_mod']) ? $more_information['permiss_mod'] : array();
	//	$clsClassTable->updateOne($val[$pkeyTable], array(
	//		'permiss_mod' => json_encode($permiss_mod, JSON_UNESCAPED_UNICODE)
	//	));
	//}
	$pUrl = ""; $cond = "1='1'";
	if($role_id > 0){
		$cond .= " and `role_id`='{$role_id}'";
		$pUrl .= "&role_id=".$role_id;
	}
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
	if($is_FH != ''){
		if($is_FH == 0){
			$cond .= " and `email` NOT IN (SELECT `email` FROM `default_profile` )";
		}else if($is_FH == 1){
			$cond .= " and `email` IN (SELECT `email` FROM `default_profile` WHERE `status_id` <> '"._STATUS_STAFF_OFF_ID."')";
		}
		$pUrl .= "&is_FH=".$is_FH;
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
	} else {
		$cond .= " and `is_trash`=0";
	}
	$orderBy = " `reg_date` desc";
	$assign_list["pUrl"] = $pUrl;
	#-------Page Divide---------------------------------------------------------------
	$recordPerPage = 20;
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
//	$clsClassTable->setDeBug(1);
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
//	$clsISO->print_pre($allItem);die;
	$assign_list["allItem"] = $allItem;
}
function default_send_email(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$clsMember = new Member();
	$clsEmailTemplate = new EmailTemplate();
	###
	$msg = "_error";
	$source = Input::post('source', 'MOC');
	$email_type = Input::post('email_type', 'wellcome');
	$profile_id = (int) Input::post('profile_id', 0);
	if($profile_id > 0){
		$oneMember = $clsMember->getOne($profile_id);
		$customer_name = $clsMember->getFullName($profile_id, $oneMember);
		$customer_email = trim($oneMember['email']);
		// Tên gói/hạng cho {package_name} (MF: package_id _MF_PACKAGE; MOC: role_id _PACKAGE) — catalog ở Property (DB chính).
		$_pkgId = ($source == 'MF') ? (int) (isset($oneMember['package_id']) ? $oneMember['package_id'] : 0) : (int) (isset($oneMember['role_id']) ? $oneMember['role_id'] : 0);
		$clsProperty = new Property();
		$package_name = ($_pkgId > 0) ? (string) $clsProperty->getOneField('title', $_pkgId) : '';
		if($package_name === ''){ $package_name = 'VIP'; }
		###
		$email_template_id = ($source == 'MF') ? _MAIL_MEMBER_MF_VIP_ID  : _MAIL_MEMBER_VIP_ID;
		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);
		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);
		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);
		$fromname = $clsEmailTemplate->getFromName($email_template_id, $oneEmailTemplate);
		$fromemail = $clsEmailTemplate->getFromEmail($email_template_id, $oneEmailTemplate);
		$cc = $clsEmailTemplate->getCopyTo($email_template_id, $oneEmailTemplate);
		###
		$replace = array(
			'{time}' => date('d/m/Y H:i:s'),
			'{customer_name}' => $customer_name,
			'{customer_email}' => $customer_email,
			'{package_name}' => $package_name
		);
		foreach($replace as $key => $val){
			$subject = @str_replace($key, $val, $subject);
			$message = @str_replace($key, $val, $message);
		}
		$toname = $customer_name;
		$toemail = trim($customer_email);
		$is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);
		// $is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);
		if($is_send) $msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_import(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	
	require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
    require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
	$inputFileName = ABSPATH . "/Update_DS CBNV Future Homes.xlsx";
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
					'user_pass' => $clsClassTable->encrypt('futurehomes@2023'),
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
	
	$classTable = "Member";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["pkeyTable"] = $pkeyTable;
	$assign_list["clsClassTable"] = $clsClassTable;
	
	$pvalTable = isset($_GET[$pkeyTable])?intval($_GET[$pkeyTable]):0;
	$assign_list['pvalTable'] = $pvalTable;
	$oneItem = $clsClassTable->getOne($pvalTable);
	$more_information = $oneItem['more_information'];
	$more_information = !empty($more_information) 
		? json_decode($more_information, true) : array();
	$banks_info = isset($more_information['banks_info']) && !empty($more_information['banks_info']) 
		? $more_information['banks_info'] : array();
	$logs_update = isset($more_information['logs_update']) && !empty($more_information['logs_update']) 
		? $more_information['logs_update'] : array();
	
	foreach($logs_update as $key => $value){
		$old = $value['old'];
		$update = $value['update'];
		if($update['full_name'] == ""){
			unset($old['full_name'],$update['full_name']);
		}else{
			
		}
		if($update['phone'] == ""){
			unset($old['phone'],$update['phone']);
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
	
	$user_id = (int) Input::post('user_id',0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 10);
	
	$clsLog = new Log(); $assign_list["clsLog"] = $clsLog;
	
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
//	$clsISO->print_pre($list_logs);die;	
	
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
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$IsoEditor,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	
	$user_id = (int) Input::post('user_id',0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 10);
	
	$clsFPoint = new FPoint(); $assign_list["clsFPoint"] = $clsFPoint;
	$clsUserAdmin = new UserAdmin();
	
	$list_logs = $clsFPoint->getAll("profile_id='{$user_id}' order by reg_date DESC",$clsFPoint->pkey.',act,ns_type,score,content,reg_date,user_id');
//	var_dump($list_logs);die;
	$html = "";
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
	#
	$clsCountry = new Country();
	$clsProperty = new Property();
	$assign_list["clsCountry"] = $clsCountry;
	$assign_list["clsProperty"] = $clsProperty;
	#
	$classTable = "Member";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$pvalTable = isset($_GET[$pkeyTable])?intval($_GET[$pkeyTable]):0;
	$assign_list['pvalTable'] = $pvalTable;
	$lstFieldMoreInfomation['certificate'] = array(
		'title'	=> 'Bằng cấp chứng chỉ'
	);
	$lstFieldMoreInfomation['project'] = array(
		'title'	=> 'Dự án đã tham gia'
	);
	$assign_list['lstFieldMoreInfomation'] = $lstFieldMoreInfomation;
	#
	$action = '_add';
	$more_information = $banks_info = array();
	$html_role_options = '<option value="0">Chọn vai trò</option>';
	if($pvalTable > 0){
		$action = '_edit';
		$oneItem = $clsClassTable->getOne($pvalTable);
		$department_id = $oneItem['department_id'];
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$banks_info = isset($more_information['banks_info']) && 
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$arrOption = array();
		$clsProperty->makeOption($for_id, '_ROLE', 0, $arrOption);
		if(!empty($arrOption)){
			foreach($arrOption as $key => $val){
				$html_role_options .= sprintf(
					'<option value="%s"%s>%s</option>',
					$key, ($oneItem['role_id']==$key ? ' selected':''), $val
				);
			}
		}
	}
	$assign_list['action'] = $action;
	$assign_list['oneItem'] = $oneItem;
	$assign_list['banks_info'] = $banks_info;
	$assign_list['more_information'] = $more_information;
	$assign_list['html_role_options'] = $html_role_options;
	# Gói tài khoản: gói đang dùng + lịch sử
	$assign_list['member_active_package'] = array();
	$assign_list['member_package_history'] = array();
	$assign_list['member_package_names'] = array();
	if($pvalTable > 0){
		$clsMemberPackage = new MemberPackage();
		$assign_list['member_active_package'] = $clsMemberPackage->getActive($pvalTable);
		$assign_list['member_package_history'] = $clsMemberPackage->getHistory($pvalTable);
		$assign_list['member_package_names'] = _member_package_names($assign_list['member_package_history']);
	}
	#=========================================#
	$err_msg = "";
	$errors = array();
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		$sale_type = 0;
		$about = Input::post('about');
		$certificate = Input::post('certificate');
		$work_process = Input::post('work_process');
		$dictum_live = Input::post('dictum_live');
		$achievements = Input::post('achievements');
		$project_joined = Input::post('project_joined');
		if(isset($_POST['about']))          $more_information['about'] = addslashes($about);
		if(isset($_POST['certificate']))    $more_information['certificate'] = addslashes($certificate);
		if(isset($_POST['work_process']))   $more_information['work_process'] = addslashes($work_process);
		if(isset($_POST['dictum_live']))    $more_information['dictum_live'] = addslashes($dictum_live);
		if(isset($_POST['achievements']))   $more_information['achievements'] = addslashes($achievements);
		if(isset($_POST['project_joined'])) $more_information['project_joined'] = addslashes($project_joined);
		# Field hồ sơ lưu trong more_information (không có cột riêng trên default_member)
		foreach(array('agency','experience','number_sale','total_sales','linkedin','instagram','facebook','twitter') as $_f){
			if(isset($_POST[$_f])) $more_information[$_f] = addslashes(Input::post($_f));
		}
		if(isset($_POST['iso-start_date'])) $more_information['start_date'] = addslashes(Input::post('iso-start_date'));
		###
		$role_id = (int) Input::post('iso-role_id', _PACKAGE_ROLE_SALE_FREE);
		$send_vip_email = 0;
		// $role_id > 0 chặn PHP loose-compare 0 == chuỗi hằng chưa define → tránh gửi email khi gỡ vai trò về 0
		if($role_id > 0 && in_array($role_id, array(_MEMBER_PARKAGE_MAS_ID, _MEMBER_PARKAGE_VIP_ID, _MEMBER_PARKAGE_VVIP_ID))){
			$sale_type = 1;
			$start_date = Input::post('start_date');
			$due_date = Input::post('due_date');
			$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
			$due_date = !empty($due_date) ? $clsISO->toTime($due_date) : 0;
			// Cho phép để trống ngày = gói vĩnh viễn (không bắt buộc). Hạn theo thời gian quản lý ở modal "Gán gói".
			$more_information['VIP']['start_date'] = $start_date;
			$more_information['VIP']['due_date'] = $due_date;
			$role_logs  = isset($more_information['role_logs']) ? $more_information['role_logs'] : array();
			$role_logs[$clsISO->getUniqid()] = array(
				'start_date' => $start_date,
				'due_date' => $due_date,
				'role_id' => $role_id,
				'reg_date' => time()
			);
			$more_information['role_logs'] = $role_logs;
			// Chỉ gửi email chúc mừng khi vai trò THAY ĐỔI sang hạng VIP (không gửi lại mỗi lần lưu hồ sơ);
			// gửi SAU khi lưu thành công (ở nhánh update/insert bên dưới). Member mới: role cũ = 0.
			$old_role_id = (!empty($oneItem) && isset($oneItem['role_id'])) ? (int) $oneItem['role_id'] : 0;
			if($old_role_id != $role_id){
				$send_vip_email = 1;
			}
		}
		if(empty($errors)){
			// $clsISO->print_pre($more_information); die();
			if($pvalTable>0){
				$set = ""; $firstAdd = 0;
				// full_name xử lý riêng ở special-field (kèm slug/permalink) — để vòng iso-* ghi nữa là trùng cột trong SET
				$skip_iso = array('first_name','last_name','full_name','start_date');
				foreach($_POST as $key=>$val){
					$tmp = explode('-',$key);
					if($tmp[0]=='iso' && !in_array($tmp[1], $skip_iso)){
						if($firstAdd==0){
							$set .= $tmp[1]."='".addslashes($val)."'";
							$firstAdd = 1;
						} else{
							$set .= ",".$tmp[1]."='".addslashes($val)."'";
						}
					}
				}
				#- Special Field
				// Form hiện tại gửi 1 ô iso-full_name; form cũ tách iso-first_name/iso-last_name — nhận cả hai,
				// và không ghi đè tên cũ bằng chuỗi trống khi form thiếu field
				$full_name = trim(Input::post('iso-full_name'));
				if($full_name == ''){
					$full_name = trim(Input::post('iso-first_name') . ' ' . Input::post('iso-last_name'));
				}
				if($full_name == ''){
					$full_name = trim($oneItem['full_name']);
				}
				$user_pass = Input::post('user_pass');
				$user_cpass = Input::post('user_cpass');
				#
				if(!empty($user_pass)){
					if($user_pass != $user_cpass){
						$errors[] = $core->get_Lang('Password and confirm password invalid !');
					} else {
						$set.= ",user_pass='".$clsClassTable->encrypt($user_pass)."'";
					}
				}
				#
				$set .= ",upd_date='".time()."',full_name='".addslashes($full_name)."'
				,full_name_slug='".$core->replaceSpace($full_name)."'
				,more_information='".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'";
				$getPermalink = $clsClassTable->setPermalink($core->replaceSpace($full_name),1,$pvalTable);
				$set .= ",permalink='".$getPermalink."'";
				#--Special Field: image
				$avatar = Input::post('avatar');
				if($avatar!='' && $avatar!='0'){
					$set .= ",avatar='".addslashes($avatar)."'";
				}
				#-- Special Field: birthday
				$birthday = Input::post('birthday');
				if(!empty($birthday)){
					$set.=",birthday='{$clsISO->toTime($birthday)}'";
				}
				#--Special Field: Notes
				$note = Input::post('note');
				if(!empty($note) && $note != '0'){
					$notes = $clsISO->to_array_json($oneItem['notes']);
					$notes[$clsISO->getUniqid()] = array(
						'content'	=> $note,
						'user_id'	=> $core->_USER['user_id'],
						'user_id_update'	=> $core->_USER['user_id'],
						'reg_date'	=> time(),
						'upd_date'	=> time()
					);
					$set .= ",notes='".json_encode($notes)."'";
				}
				#- End Notes
				if(empty($errors)){
					if($clsClassTable->updateOne($pvalTable, $set)) {
						$oneItem= $clsClassTable->getOne($pvalTable);
						if($send_vip_email == 1){
							// Gửi sau khi lưu thành công — module member = nguồn MF (template có {package_name})
							$clsClassTable->sendEmailVIPBySource($pvalTable, 'MF');
						}
						$clsHubJS = new HubJS();
						$sale_type = ($oneItem['role_id'] == _ROLE_MOC_VIP) ? 1 : 0;
						$clsHubJS->sendData($oneItem, "sale", $sale_type);
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
				// full_name append riêng bên dưới — để vòng iso-* ghi nữa là INSERT trùng cột (lỗi 1110)
				$skip_iso = array('first_name','last_name','full_name','start_date');
				foreach($_POST as $key=>$val){
					$tmp = explode('-',$key);
					if($tmp[0]=='iso' && !in_array($tmp[1], $skip_iso)){
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
				// Nhận cả form mới (1 ô iso-full_name) lẫn form cũ (tách iso-first_name/iso-last_name)
				$full_name = trim(Input::post('iso-full_name'));
				if($full_name == ''){
					$full_name = trim(Input::post('iso-first_name') . ' ' . Input::post('iso-last_name'));
				}
				#
				$user_name = Input::post('iso-user_name');
				$user_email = Input::post('iso-email');
				$user_pass = Input::post('user_pass');
				$user_cpass = Input::post('user_cpass');
				#
				$check_user = $clsClassTable->checkValidUsername($user_name);
				if($check_user){
					$errors[] = $core->get_Lang('This account already exists with that email address');
				}
				#- Valid password
				if($user_pass != $user_cpass){
					$errors[] = $core->get_Lang('Password and confirm password invalid !');
				}
				#
				$pvalTable = $clsClassTable->getMaxId();
				$field .= ",reg_date,upd_date,full_name,full_name_slug,{$pkeyTable},user_pass,more_information";
				$value .= ",'".time()."','".time()."','".addslashes($full_name)."'
				,'".$core->replaceSpace($full_name)."','{$pvalTable}'
				,'".$clsClassTable->encrypt($user_pass)."'
				,'".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'";
				#--Special Field: avatar
				$avatar = Input::post('avatar');
				if(!empty($avatar)){
					$field.= ",avatar";
					$value .= ",'".addslashes($avatar)."'";
				}
				# (Đã bỏ list_department_id: cột không tồn tại trên default_member → gây lỗi INSERT)
				#-- Special Field: birthday
				$birthday = Input::post('birthday');
				if(!empty($birthday)){
					$field.= ",birthday";
					$value.=",'{$clsISO->toTime($birthday)}'";
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
					$field.= ",notes";
					$value .= ",'".addslashes(json_encode($notes))."'";
				}
				#- End Notes
				if(empty($errors)) {
					if($clsClassTable->insertOne($field,$value)){
						$oneItem = $clsClassTable->getOne($pvalTable);
						if($send_vip_email == 1){
							// Gửi sau khi tạo mới thành công — module member = nguồn MF
							$clsClassTable->sendEmailVIPBySource($pvalTable, 'MF');
						}
						$clsHubJS = new HubJS();
						$clsHubJS->sendData($oneItem, "sale", $sale_type);
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
		} else {
			foreach($errors as $err){
				$errMsg.= sprintf('&bull; %s', $err);
			}
		}
	}
	$assign_list["errMsg"] = $errMsg;
}
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error';
	$clsMember = new Member();
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$images = $_FILES['images'];
		$profile_id = (int)Input::post("profile_id",0);
		$type = Input::post("type","");
		if(!empty($images['name']) && $profile_id >0){ 
			$ii = 0; //Init
			$oneMember = $clsMember->getOne($profile_id,"more_information,target_id");	
			if(!empty($oneMember)){
				$more_information = (array)json_decode($oneMember['more_information']);
				$arr_images = (!empty($more_information['image']))?$more_information['image']:array();
				$results = $clsMember->uploadImageGoogleDriver($images,$profile_id);
				if($type == "images"){
					$arr_images = array_merge($arr_images,$results);
					$more_information['image'] = $arr_images;
					if($clsMember->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
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
					if(!empty($results) && $clsMember->updateOne($profile_id,["avatar"=>$results[0]])){
						$msg = '_success|||' .$results[0]; 
					}
				}else if($type == 'banner'){
					$more_information['banner'] = $results[0];
					if($clsMember->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
						$msg = '_success|||' .$results[0]; 
					}
				}
			}			
		}
	}
	// Return
	echo $msg; die();
}
function default_addVideo(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$html = '';
	$clsMember = new Member();
	$profile_id = (int)Input::post("profile_id",0);
	$link_file = Input::post("link_file","");
	if(!empty($link_file) && $profile_id >0){ 
		$oneMember = $clsMember->getOne($profile_id,"more_information,target_id");	
		if(!empty($oneMember)){
			$more_information = (array)json_decode($oneMember['more_information']);
			$more_information['link_video'] = $link_file;
			if($clsMember->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
				// Return
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
	$classTable = "Member";
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
	$html = '<select class="iso-selectize required" required name="iso-role_id" id="role_id">
		<option value="0">Chọn vai trò</option>';
	$department_id = (int) Input::post('department_id', 0);
	if($department_id > 0){
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$arrOption = array();
		$clsProperty->makeOption($for_id, '_ROLE', 0, 0, $arrOption);
		if(!empty($arrOption)){
			foreach($arrOption as $key => $val){
				$html .= sprintf('<option value="%s">%s</option>', $key, $val);
			}
		}
	}
	$html .= '</select>';
	// Return
	echo $html; die();	
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
	$clsMember = new Member();
	#
	$note_id = Input::post('note_id');
	$profile_id = (int) Input::post('profile_id',0);
	$notes = $clsMember->getOneField('notes', $profile_id);
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
	$msg = "_error";
	if($clsMember->updateOne($profile_id,array(
		'notes' => @json_encode($notes)
	))){
		$msg = "_success";
	}
	// output
	echo($msg); die();
}
function default_ajLoadListNote(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsISO,$clsUser;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$clsMember = new Member();
	$profile_id = (int) Input::post('profile_id',0);
	$notes = $clsMember->getOneField('notes',$profile_id);
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
									<button type="button" class="btn btn-dafault btncancel_note" profile_id="'.$profile_id.'" note_id="'.$note_id.'">'.$core->makeIcon('reply',$core->get_Lang('Cancel')).'</button>
									<button type="button" class="btn btn-success btnsave_note" profile_id="'.$profile_id.'" note_id="'.$note_id.'">'.$core->makeIcon('check',$core->get_Lang('Update')).'</button>
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
	$profile_id = Input::post('profile_id', 0);
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
	#
	$clsMember = new Member();
	$profile_id = (int) Input::post('profile_id' , 0);
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
		$old_avatar = $clsMember->getOneField('avatar', $profile_id);
		if(!empty($old_avatar) && @file_exists(ROOTPATH.$old_avatar)){
			@unlink(ROOTPATH.$old_avatar);
		}
		if($clsMember->updateOne($profile_id, "`avatar`='".addslashes($avatar)."'")){
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
function default_open_password(){
	
}
function default_export(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$_frontIsLoggedin_user_id;
	$clsMember = new Member();
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
	$objPHPExcel->getActiveSheet()->getStyle('A:G')->getNumberFormat()->setFormatCode(
		PHPExcel_Style_NumberFormat::FORMAT_TEXT
	);
	$type_list = Input::get("type_list", "");
	$role_id = (int)Input::get("role_id", 0);
	$department_id = (int)Input::get("department_id", 0);
	$status_id = (int)Input::get("status_id", 0);
	$exist_phone = (int)Input::get("exist_phone", 0);
	$is_FH = Input::get("is_FH", '');
	$keyword = Input::get("keyword", "");
	$cond = "`is_trash`=0"; //  and {$clsMember->pkey}='1300' 
	if($role_id > 0){
		$cond .= " and (`role_id`='{$role_id}')";
	}
	#Filter By department_id
	if($department_id > 0){
		$cond .= " and `department_id`='{$department_id}'";
	}
	if($status_id > 0){
		$cond .= " and `status_id`='{$status_id}'";
	}
	if($exist_phone != ''){
		if($exist_phone == 0){
			$cond .= " and `phone`=''";
		}else if($exist_phone == 1){
			$cond .= " and `phone`<>''";
		}
	}
	if($is_FH != ''){
		if($is_FH == 0){
			$cond .= " and `email` NOT IN (SELECT `email` FROM `default_profile`)";
		}else if($is_FH == 1){
			$cond .= " and `email` IN (SELECT `email` FROM `default_profile` WHERE `status_id` <> '"._STATUS_STAFF_OFF_ID."')";
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
//	 $clsISO->print_pre($cond); die();
	$field = "{$clsMember->pkey},full_name,first_name,last_name,phone,email,address,reg_date,ip_register";
	$list_staffs = $clsMember->getAll("{$cond} order by reg_date DESC", $field);
	// $clsISO->print_pre($list_staffs); die();
	if(!empty($list_staffs)){
		$row = 2;
		foreach($list_staffs as $key => $val){
			$member_id = $val[$clsMember->pkey];
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $member_id);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $clsMember->getFullName($member_id, $val));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $val['phone']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $val['email']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $val['address']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $clsISO->convertTimeToText($val['reg_date']));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $val['ip_register']);
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
	try {
		$clsPermiss = new Permiss();
		// Phân quyền member lưu ở default_member_source (model MemberSource) — MemberMeta là bảng meta bài đăng của broker, schema khác
		$clsMemberSource = new MemberSource();
		#
		$source = Input::post('source', 'MOC');
		$source = ($source === 'MF') ? 'MF' : 'MOC';
		$profile_id = (int) Input::post('profile_id', 0);
		$smarty->assign('profile_id', $profile_id);
		$smarty->assign('source', $source);
		$smarty->assign('profile_type', $source); // back-compat tên biến cũ trong template
		$smarty->assign('clsISO', $clsISO);
		#- Mặc định gói (để hiển thị badge) + quyền hiệu lực hiện tại (= gói ⊕ override cá nhân)
		$default   = $clsMemberSource->getPackageDefault($profile_id, $source);
		$effective = $clsMemberSource->getEffective($profile_id, $source);
		#- Cây quyền theo hệ
		$list_permiss = $clsPermiss->getTree($source);
		if(!empty($list_permiss)){
			foreach($list_permiss as $key => $val){
				$list_items = $val['list_items'];
				if(!empty($list_items)){
					foreach($list_items as $okey => $oval){
						$code = $oval['code'];
						$list_items[$okey]['checked']    = isset($effective[$code]) ? 1 : 0;
						$list_items[$okey]['is_default']  = isset($default[$code]) ? 1 : 0;
					}
				}
				$list_permiss[$key]['list_items'] = $list_items;
			}
		}
		$smarty->assign('list_permiss', $list_permiss);
		// Return
		$html = $core->build('_ajax.permiss.tpl');
		echo json_encode(array('html' => $html)); die();
	} catch (\Throwable $e) {
		echo json_encode(array('html' => '<div style="padding:16px;color:#a4161a;font-size:13px">LỖI: '.htmlspecialchars($e->getMessage()).'<br><small>'.htmlspecialchars($e->getFile()).':'.$e->getLine().'</small></div>')); die();
	}
}
function default_pop_save_permiss(){
	global $core,$clsISO;
	$clsPermiss = new Permiss();
	$clsMemberSource = new MemberSource();
	###
	$source = Input::post('source', 'MOC');
	$source = ($source === 'MF') ? 'MF' : 'MOC';
	$profile_id = (int) Input::post('profile_id', 0);
	$permiss_mod = Input::post('permiss_mod', array()); // chỉ các code được tick (value=1)
	###
	$checked_codes = array();
	if(is_array($permiss_mod)){
		foreach($permiss_mod as $code => $v){ if((int) $v === 1) $checked_codes[] = $code; }
	}
	$all_codes = $clsPermiss->getAllCodes($source);
	$msg = "_error";
	if($profile_id > 0 && $clsMemberSource->savePermiss($profile_id, $source, $checked_codes, $all_codes)){
		$msg = "_success";
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("MemberSource", "update", array("member_id" => $profile_id, "source" => $source));
	}
	// Return
	echo $msg; die();
}
function default_add_employ(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsMember = new Member();
	$clsProfile = new Profile();
	###
	$msg = "_error";
	$profile_id = Input::post('profile_id', 0);
	$oProfile = $clsMember->getOne($profile_id);
	$tmp = $clsProfile->getByCond("`email`='{$oneProfile['email']}'");
	if(empty($tmp)){
		$profile_id = $clsProfile->getMaxId();
		$profile_code = $clsProfile->getCode();
		if($clsMember->updateOne($profile_id, array(
			'target_id'	=>	$profile_id,
			'code'		=>	$profile_code
		))){
			$insert_data = [];
			foreach($oProfile as $key => $value){
				if($key == "profile_id"){
					$insert_data["profile_id"] = $profile_id;
				} elseif($key == "code"){
					$insert_data[$key] = $profile_code;
				} else if($key == 'status_id'){
					$insert_data[$key] = _STATUS_STAFF_ON_ID;
				} elseif($key == "target_id" || $key == 'profile_type' || $key == 'is_verified' || $key == 'total_point'){
					
				} else{
					$insert_data[$key] = $value;
				}
			}
			// $dbconn->debug = true;
			if($clsProfile->insert($insert_data)){
				$msg = "_success";
			}
		}
	} else {
		$msg = "_duplicated";
	}
	// Return
	echo $msg; die();
}
function default_formAddField(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsMember = new Member();
	$type = Input::post("type","open");
	$field_id = Input::post("field_id",""); $assign_list['field_id'] = $field_id;
	$field = Input::post("field",""); $assign_list['field'] = $field;
	$profile_id = Input::post("profile_id",""); $assign_list['profile_id'] = $profile_id;
	$data=["result"	=>	false];
	if($type == "open"){
		if($field_id != ""){
			$Member = $clsMember->getOne($profile_id);
			$more_information = $clsISO->to_array_json($Member['more_information']);
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
		$Member = $clsMember->getOne($profile_id);
		$more_information = $clsISO->to_array_json($Member['more_information']);
		$arr_field = !empty($more_information[$field])?$more_information[$field]:[];
		//	var_dump($image);die;
		if(!empty($image["name"])){
			if(@is_uploaded_file($image['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($image,"/member/".$field,EXTENSION_FILE_UPLOAD);
				$file_name = $image['name'];
				$file_size = $image['size'];
				// Upload file to google drive
				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
				$folder_id = $clsGoogleUpload->create_folder($profile_id);
				// $clsISO->print_pre($folder_id); die();
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
		if($clsMember->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
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
		$Member = $clsMember->getOne($profile_id);
		$more_information = $clsISO->to_array_json($Member['more_information']);
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
		if($clsMember->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
			$data=[
				"result"	=>	true,
				"html"		=>	$html
			];
		}
	}else if($type == "delete"){
		$Member = $clsMember->getOne($profile_id);
		if(!empty($Member)){
			$more_information = $clsISO->to_array_json($Member['more_information']);
			$arr_field = !empty($more_information[$field])?$more_information[$field]:[];

			$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();
			if($field != "" && $field_id != ""){
				unset($arr_field[$field_id]);
				$assign_list['arr_field'] = $arr_field;
				$html = $core->build('_ajax.loadListField.tpl');
				$more_information[$field] = $arr_field;
				if($clsMember->updateOne($profile_id,["more_information"=>json_encode($more_information)])){
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
function default_add_point(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	###
	$clsMember = new Member();
	$clsMocPoint = new MocPoint();
	$type = Input::post("type","open");
	$profile_id = Input::post("profile_id",""); $assign_list['profile_id'] = $profile_id;
	$data=["result"	=>	false];
	$uid = $clsISO->getUniqid(); 
	$assign_list['uid'] = $uid;
	$oneItem = $clsMember->getOne($profile_id,"total_point,target_id");
	$assign_list['oneItem'] = $oneItem;
	$total_point = (int)$oneItem['total_point'];
	$user_id = $core->_USER['user_id'];
	if($type == "open"){		
		$html = $core->build('_ajax.formAddPoint.tpl');
		$data = [
			'result'	=>	true,
			'uid'	=>	$uid,
			'html' => $html,
		];
	}else if($type == 'add'){		
		$score = (int)Input::post('score',0);
		$content = Input::post('content',"");
		$total_point += $score;
		if($clsMember->updateOne($profile_id,["total_point"=>$total_point])){
			$clsMocPoint->insert(array(
				$clsMocPoint->pkey	=> $clsMocPoint->getMaxId(),
				'profile_id'			=>	$profile_id,
				'profile_type'		=>	"MOC",
				'user_id'			=>	$user_id,
				'ns_type'			=>	"plus",
				'act'				=>	"recharge",
				'score'				=>	$score,
				'content'			=>	addslashes($content),
				'reg_date'			=>	time(),
			));
			$data = [
				'result'	=>	true,
				'msg'		=>	"Thêm thành công",
				'total_point' => $total_point
			];
		}
	}
	echo json_encode($data); die();
}
function default_get_member_search(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneMember,$assign_list;
	$clsMember = new Member();
	
	$_results = array();
	$keysearch = Input::get('keysearch');
	if(!empty($keysearch)){
		$field = "{$clsMember->pkey},code,phone,full_name";
		$list_members = $clsMember->getAll("is_trash=0 and (`full_name_slug` like '%".$core->replaceSpace($keysearch)."%' 
			or `phone` like '%".$keysearch."%' 
			or `email` like '%".$keysearch."%')", $field);
		if(!empty($list_members)){
			foreach($list_members as $key => $val){
				$_results[] = array(
					'id' => $val[$clsMember->pkey],
					'text' => sprintf('%s(%s)', $val['full_name'], $val['code'])
				);
			}
		}
	}
	// Return
	echo json_encode($_results); die();
}
// ===== Gói tài khoản: gán cho member + lịch sử =====
function default_open_assign_package(){
	global $smarty,$core,$clsISO;
	$clsProperty = new Property();
	$clsMemberPackage = new MemberPackage();
	$profile_id = (int) Input::post('profile_id', 0);
	$mp_id = (int) Input::post('mp_id', 0);
	$list_packages = $clsProperty->getAll("property_type='_MF_PACKAGE' and is_trash=0 order by order_no ASC", "property_id,title,property_code,more_information");
	if(!empty($list_packages)){
		foreach($list_packages as $key => $val){
			$mi = !empty($val['more_information']) ? json_decode($val['more_information'], true) : array();
			if(!is_array($mi)) $mi = array();
			// Giá theo thời hạn (catalog) để auto-fill "Giá đã thu" khi chọn preset 1/3/6/12 tháng
			$list_packages[$key]['prices'] = array(
				1  => isset($mi['price_month'])  ? (int) $mi['price_month']  : 0,
				3  => isset($mi['price_3month']) ? (int) $mi['price_3month'] : 0,
				6  => isset($mi['price_6month']) ? (int) $mi['price_6month'] : 0,
				12 => isset($mi['price_year'])   ? (int) $mi['price_year']   : 0,
			);
			unset($list_packages[$key]['more_information']);
		}
	}
	$mp = array();
	if($mp_id > 0){
		$mp = $clsMemberPackage->getOne($mp_id);
		if(!is_array($mp)) $mp = array();
	}
	$smarty->assign('list_packages', $list_packages);
	$smarty->assign('mp', $mp);
	$smarty->assign('profile_id', $profile_id);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.assign_package.tpl');
	echo json_encode(array('html'=>$html)); die();
}
function default_save_member_package(){
	global $core,$clsISO;
	$clsMemberPackage = new MemberPackage();
	$msg = "_error";
	$profile_id = (int) Input::post('profile_id', 0);
	$mp_id = (int) Input::post('mp_id', 0);
	$package_id = (int) Input::post('package_id', 0);
	if($profile_id <= 0 || $package_id <= 0){ echo json_encode(array('msg'=>$msg)); die(); }
	$start_text = Input::post('start_date','');
	$end_text = Input::post('end_date','');
	$start_date = !empty($start_text) ? $clsISO->convertTextToTime($start_text) : 0;
	$end_date = !empty($end_text) ? $clsISO->convertTextToTime($end_text) : 0;
	// Giá nhập "450.000" → 450000 (cột BIGINT); bảng KHÔNG có cột package_name/duration_label (tên gói tra runtime qua Property)
	$price_paid = (int) preg_replace('/\D+/', '', (string) Input::post('price_paid',''));
	$status = Input::post('status','active');
	// Whitelist theo giá trị thực tế trên bảng chung (live dùng 'cancelled'; open() ghi 'expired')
	if(!in_array($status, array('active','expired','cancelled'))){ $status = 'active'; }
	$note = Input::post('note','');
	$user_id = (int) $core->_USER['user_id'];
	$clsActivityLog = new ActivityLog();
	if($mp_id > 0){
		// Sửa 1 dòng lịch sử: chỉ cột THẬT + tính lại level theo gói (cron myfuture đòi level>0 mới hạ hạn)
		if($clsMemberPackage->updateOne($mp_id, array(
			'package_id' => $package_id,
			'level'      => $clsMemberPackage->levelOfPackage($package_id),
			'start_date' => $start_date,
			'end_date'   => $end_date,
			'price_paid' => $price_paid,
			'status'     => $status,
			'note'       => $note,
			'upd_date'   => time(),
		))){ $msg = "_success"; }
		// Dội tier hiện hành về member bên MF (đổi gói/ngày/huỷ trên dòng active phải sync lại)
		_member_sync_mf_tier($profile_id);
		// $clsActivityLog->addActivityLog("MemberPackage","update",array("member_id"=>$profile_id,"package_id"=>$package_id));
	} else if($status == 'active'){
		// Gán gói mới đang hiệu lực → grant() (điểm cấp gói duy nhất, contract chung với myfuture):
		// tự tính level + đóng dòng active cũ + đồng bộ ngược default_member.package_id (DB MF)
		if($clsMemberPackage->grant($profile_id, $package_id, $start_date, $end_date, array(
			'price_paid'  => $price_paid,
			'change_type' => 'new',
			'source'      => 'admin',
			'note'        => $note,
			'user_id'     => $user_id,
		))){ $msg = "_success"; }
		// $clsActivityLog->addActivityLog("MemberPackage","insert",array("member_id"=>$profile_id,"package_id"=>$package_id));
	} else {
		// Thêm bản ghi lịch sử KHÔNG hiệu lực (đã hủy) → ghi thẳng, không side-effect lên tier
		if($clsMemberPackage->insert(array(
			'id'          => $clsMemberPackage->getMaxId(),
			'member_id'   => $profile_id,
			'package_id'  => $package_id,
			'level'       => $clsMemberPackage->levelOfPackage($package_id),
			'start_date'  => $start_date,
			'end_date'    => $end_date,
			'price_paid'  => $price_paid,
			'status'      => $status,
			'change_type' => 'new',
			'source'      => 'admin',
			'note'        => $note,
			'user_id'     => $user_id,
			'reg_date'    => time(),
			'upd_date'    => time(),
		))){ $msg = "_success"; }
		// $clsActivityLog->addActivityLog("MemberPackage","insert",array("member_id"=>$profile_id,"package_id"=>$package_id));
	}
	_member_sync_package_cache($profile_id);
	echo json_encode(array('msg'=>$msg)); die();
}
function default_load_member_packages(){
	global $smarty,$core,$clsISO;
	$clsMemberPackage = new MemberPackage();
	$profile_id = (int) Input::post('profile_id', 0);
	$list = $clsMemberPackage->getHistory($profile_id);
	$smarty->assign('list', $list);
	$smarty->assign('active', $clsMemberPackage->getActive($profile_id));
	$smarty->assign('pkg_names', _member_package_names($list));
	$smarty->assign('profile_id', $profile_id);
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	echo $core->build('_ajax.member_packages.tpl'); die();
}
// Map package_id -> tên gói (tra catalog Property; bảng member_package không có cột package_name)
function _member_package_names($list){
	$names = array();
	if(empty($list)) return $names;
	$clsProperty = new Property();
	foreach($list as $_mp){
		$_pid = (int) $_mp['package_id'];
		if($_pid > 0 && !isset($names[$_pid])){
			$names[$_pid] = (string) $clsProperty->getOneField('title', $_pid);
		}
	}
	return $names;
}
function default_delete_member_package(){
	global $core;
	$clsMemberPackage = new MemberPackage();
	$msg = "_error";
	$mp_id = (int) Input::post('mp_id', 0);
	$profile_id = (int) Input::post('profile_id', 0);
	if($mp_id > 0 && $clsMemberPackage->deleteOne($mp_id)){
		$msg = "_success";
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("MemberPackage","delete",array("member_id"=>$profile_id,"mp_id"=>$mp_id));
		// Xoá dòng active cuối → hạ tier MF về Free (không để member giữ quyền VIP treo)
		_member_sync_mf_tier($profile_id);
		_member_sync_package_cache($profile_id);
	}
	echo json_encode(array('msg'=>$msg)); die();
}
// Dội tier hiện hành về member bên MF theo bảng gói: còn dòng active → sync gói + hạn VIP;
// KHÔNG còn active → hạ về gói Miễn phí + xoá hạn (chốt 2026-07-04, tránh giữ quyền VIP vô hạn).
function _member_sync_mf_tier($profile_id){
	$profile_id = (int) $profile_id;
	if($profile_id <= 0) return;
	$clsMF_Member = new MF_Member();
	$clsMemberPackage = new MemberPackage();
	$active = $clsMemberPackage->getActive($profile_id);
	$mi = $clsMF_Member->getOneField('more_information', $profile_id);
	$more = (!empty($mi)) ? json_decode($mi, true) : array();
	if(!is_array($more)) $more = array();
	if(!isset($more['VIP']) || !is_array($more['VIP'])) $more['VIP'] = array();
	if(!empty($active)){
		$package_id = (int) $active['package_id'];
		$more['VIP']['start_date'] = (int) $active['start_date'];
		$more['VIP']['due_date'] = (int) $active['end_date'];
	} else {
		$package_id = _MEMBER_PACKAGE_MF_FREE_ID;
		$more['VIP']['start_date'] = 0;
		$more['VIP']['due_date'] = 0;
	}
	$clsMF_Member->updateOne($profile_id, array(
		'package_id'       => $package_id,
		'more_information' => json_encode($more, JSON_UNESCAPED_UNICODE),
		'upd_date'         => time(),
	));
}
// Đồng bộ gói active vào member phía CA (default_member DB chính): cột package_id + cache more_information['package'].
// Cột package_id CA là nguồn đọc của form Sửa / email / MemberSource — phải chạy theo gói active (không còn active → Free).
function _member_sync_package_cache($profile_id){
	global $clsISO;
	$profile_id = (int)$profile_id;
	if($profile_id <= 0) return;
	$clsMember = new Member();
	$clsProperty = new Property();
	$clsMemberPackage = new MemberPackage();
	$active = $clsMemberPackage->getActive($profile_id);
	$mi = $clsISO->to_array_json($clsMember->getOneField('more_information', $profile_id));
	if(!is_array($mi)) $mi = array();
	if(!empty($active)){
		$package_id = (int) $active['package_id'];
		// Tên gói tra runtime qua catalog Property (bảng member_package không có cột package_name)
		$mi['package'] = array(
			'mp_id'        => $active['id'],
			'package_id'   => $package_id,
			'package_name' => (string) $clsProperty->getOneField('title', $package_id),
			'end_date'     => $active['end_date']
		);
	} else {
		$package_id = _MEMBER_PACKAGE_MF_FREE_ID;
		unset($mi['package']);
	}
	$clsMember->updateOne($profile_id, array(
		'package_id'       => $package_id,
		'more_information' => json_encode($mi, JSON_UNESCAPED_UNICODE)
	));
}
?>