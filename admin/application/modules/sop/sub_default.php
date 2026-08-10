<?php
function default_default(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	
	$clsProperty = new Property();
	$clsMember = new Member();
	$classTable = "Sop";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	
	$list_users = $dbconn->getAll("select `t1`.`user_id`, `t2`.`full_name` from {$clsClassTable->tbl} as `t1` 
		inner join {$clsMember->tbl} as `t2` on `t1`.`user_id`=`t2`.`profile_id` 
		where `t2`.`is_trash`=0 and t2.`is_active`='1' ORDER BY `t2`.`full_name` ASC");
	$lstMember = [];
	if(!empty($list_users)){
		foreach($list_users as $key => $val){
			if(!isset($lstMember["user_id"])) {
				$lstMember[$val['user_id']] = $val;	
			}			
		}
	}
	$assign_list["lstMember"] = $lstMember;
	#
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$block_id = (int) Input::post('block_id', 0);
		$building_id = (int) Input::post('building_id', 0);
		$bedroom_id = (int) Input::post('bedroom_id', 0);
		$user_id = (int) Input::post('user_id', 0);
		$type_list = Input::post('type_list', '');
		$is_hot = Input::post('is_hot', "");
		if($department_id>0) $link .= '&department_id='.$department_id;
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		if($user_id != "") $link .= '&user_id='.$user_id;
		if($type_list != "") $link .= '&type_list='.$type_list;
		if($is_hot != "") $link .= '&is_hot='.$is_hot;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	/* End Filter */
	/*List all item*/
	$block_id = (int) Input::get('block_id', 0);
	$building_id = (int)Input::get('building_id');
	$bedroom_id = (int)Input::get('bedroom_id');
	$type_list = Input::get('type_list',"");
	$keyword = Input::get('keyword');
	$user_id = Input::get('user_id',0);
	$is_hot = Input::get('is_hot','');
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	$assign_list["bedroom_id"] = $bedroom_id;
	$assign_list["keyword"] = $keyword;
	$assign_list["type_list"] = $type_list;
	$assign_list["user_id"] = $user_id;
	$assign_list["is_hot"] = $is_hot;
	#
	$cond = "1='1'";
	if($keyword != ""){
		$cond .= " AND (stock_code LIKE '%".$keyword."%' )";
	}
	if($type_list != ""){
		$cond .= " AND is_online = '{$type_list}'";
	}
	if($is_hot != ""){
		$cond .= " AND is_hot = '{$is_hot}'";
	}
	if(!empty($user_id)){
		$cond .= " AND user_id = '{$user_id}'";
	}
//	echo $cond;die;
	
	#Filter By Keyword
	$orderBy = " `upd_date` desc";
	$assign_list["pUrl"] = $pUrl;
	#-------Page Divide---------------------------------------------------------------
	$recordPerPage 	= 20;
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
	$clsStock = new Stock();
	$field = "{$clsClassTable->pkey},`title`,`stock_code`,`price`,`more_information`,`contact_name`
	,`contact_phone`,`stock_id`,`stock_type`,`bedroom_id`,`home_direction_id`,`is_verified`,`floor`,`project_id`,`block_id`,`building_id`,`code`,`is_locked`
	,`is_online`,`is_solded`,`status_id`,`upd_date`,`reg_date`,`is_hot`";
//	$clsClassTable->setDeBug(1);
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit,$field);
//	$clsISO->print_pre($allItem);die;	
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
	$lstRange = $clsProperty->getArraySearchByKey("_RANGE");
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstHomeDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	$arr_property_cached = [];
	foreach($allItem as $key => $value){
		$allItem[$key]['more_information'] = $clsISO->to_array_json($value['more_information']);
		$bedroom_id = $value['bedroom_id'];
		$arr_property_cached[$bedroom_id] = $lstBedroom[$bedroom_id]['title'];
		$allItem[$key]['bedroom'] = $arr_property_cached[$bedroom_id];
		unset($bedroom_id);
	}
//	$clsISO->print_pre($allItem);die();
	$assign_list["allItem"] = $allItem;
}
function default_approve_sop(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsSop = new Sop();
	$clsNotify = new Notify();
	$clsProperty = new Property();
	###
	$msg = "_success";
	$action = Input::post('action', "approve_multi");
	if($action == "approve_multi"){
		$sop_ids = Input::post('sop_id', array());		
		for($i=0; $i<count($sop_ids); $i++){
			$clsSop->updateOne($sop_ids[$i], "`is_online`=1");
		}
	} else if($action == "noapprove_multi"){
		$sop_ids = Input::post('sop_id', '');
		$sop_ids = explode(",",$sop_ids);
		$notes = Input::post('notes', '');
		for($i=0; $i<count($sop_ids); $i++){
			$oneSop = $clsSop->getOne($sop_ids[$i],'more_information');
			if(!empty($oneSop)){
				$more_information = json_decode($oneSop['more_information']);
				$more_information->reason_not_approved = $notes;
				$clsSop->updateOne($sop_ids[$i], "is_online=2,more_information='".json_encode($more_information)."'");
			}
		}
	}else if($action == "approve"){
		$sop_id = (int) Input::post('sop_id', 0);
		if($sop_id > 0){
			if($clsSop->updateOne($sop_id, array(
				'is_online' => 1
			))){
				$field = "{$clsSop->pkey},`title`,`user_id`";
				$oneSop = $clsSop->getOne($sop_id, $field);
				$content = sprintf("Quản trị viên đã phê duyệt tin chuyển nhượng <strong>%s</strong> của bạn", $oneSop['title']) ;
				$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), sprintf('|%s|', $oneSop['user_id']));
			}
		}else{
			$msg = "_error";
		}
	}else if($action == "unapprove"){
		$sop_id = (int)Input::post('sop_id', 0);
		if($sop_id > 0){
			$clsSop->updateOne($sop_id, "is_online=0");
		}else{
			$msg = "_error";
		}
	}else if($action == "noapprove"){
		$sop_id = (int)Input::post('sop_id', 0);
		$notes = Input::post('notes', '');
		if($sop_id > 0){
			$oneSop = $clsSop->getOne($sop_id,'more_information');
			if(!empty($oneSop)){
				$more_information = json_decode($oneSop['more_information']);
				$more_information->reason_not_approved = $notes;
				$clsSop->updateOne($sop_id, "is_online=2,more_information='".json_encode($more_information)."'");
			}
		}else{
			$msg = "_error";
		}
	}else{
		$msg = "_error";
	}
	// Return
	echo ($msg); die();
}
function default_delete_sop(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsSop = new Sop();
	###	
	$sop_id = (int)Input::post('sop_id', 0); 
	if($sop_id > 0){
		if($clsSop->doDelete($sop_id)){
			$msg = "_success";
		}else{
			$msg = "_error";
		}
	}else{
		$msg = "_error";
	}
	// Return
	echo ($msg); die();
}
function default_show_notes(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$assign_list;
	$clsSop = new Sop();
	$clsProperty = new Property();
	###
	$msg = "_success";
	$action = Input::post('action', ""); $assign_list['action']=$action;
	if($action == 'noapprove_multi'){
		$sop_ids = Input::post('sop_id', array()); 
		$assign_list['sop_ids']=implode(",",$sop_ids);
	}else{
		$sop_id = (int) Input::post('sop_id', 0);
		$assign_list['sop_ids'] = $sop_id;
	}	
	// Return
	$html = $core->build('_ajax.open_notes_sop.tpl');
	echo $html;die;
}
function default_open(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	###
	$uid = $clsISO->getUniqid();
	$sop_id = Input::post('sop_id', 0);
	$stock_id = Input::post('stock_id', 0);
	$smarty->assign('uid', $uid);
	$smarty->assign('sop_id', $sop_id);
	$smarty->assign('stock_id', $stock_id);
	$field = "`t1`.`stock_code`,`t1`.`more_information`,`t1`.`user_id`,`t1`.`upd_date`,`t2`.`more_information` as `stock_information`";
	$field.= ",`t1`.`contact_phone`,`t1`.`price`,`t1`.`fee_included`,`t2`.`block_id`,`t2`.`home_direction_id`,`t2`.`bedroom_id`,`t2`.`building_id`";
	// $dbconn->debug = true;
	$oneSop = $dbconn->getRow("select {$field} from `{$clsSop->tbl}` as `t1` 
		left join {$clsStock->tbl} as `t2` on `t1`.`stock_code`=`t2`.`ms_code` 
		where `t1`.`sop_id`='{$sop_id}'");
	$price = $oneSop['price'];
	$more_information = $oneSop['more_information'];
	$stock_information = $oneSop['stock_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$stock_information = $clsISO->to_array_json($stock_information);
//	$clsISO->print_pre($stock_information);die;
	$oneSop['more_information'] = $more_information;
	$DT_TT = $stock_information['DT_TT']; 
	#-Giá/m2
	$price_m2 = 0;
	if($price > 0 && !empty($DT_TT)){
		$price_m2 = $clsISO->processSmartNumber($price) / $clsISO->convertToNumber($DT_TT);
	}
	$smarty->assign('price_m2', $price_m2);
	##
	$list_medias = array();
	if(isset($more_information['images']) && !empty($more_information['images'])){
		foreach($more_information['images'] as $val){
			$list_medias[] = array(
				'type' => 'image',
				'image' => $val
			);
		}
	}
	if(isset($more_information['video']) && !empty($more_information['video'])){
		$list_medias[] = array(
			'type' => 'video',
			'video' => $more_information['video']
		);
	}
	$smarty->assign('oneSop', $oneSop);
	$smarty->assign('list_medias', $list_medias);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('stock_information', $stock_information);
	$domain_myocean = MYOCEAN_URL; $smarty->assign('domain_myocean', $domain_myocean);
	// $clsISO->print_pre($more_information); die();
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
        'sop_id' => $sop_id
	)); die();
}
function default_verified(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$clsSop = new Sop();
	$clsNotify = new Notify();
	$clsProperty = new Property();
	$sop_id = (int) Input::post('sop_id', 0);
	###
	$msg = "_error";
	if($sop_id > 0){
		$is_verified = (int) Input::post('is_verified', 0);
		$oneSop = $clsSop->getOne($sop_id, "`logs`,`title`,`user_id`");
		$logs = $clsISO->to_array_json($oneSop['logs']);
		$logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'user_id' => $profile_id,
			'to_value' => $is_verified,
			'field' => 'is_verified'
		);
		if($clsSop->updateOne($sop_id, array(
			'is_verified' => $is_verified,
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$title = ($is_verified==1) ? 'xác minh' : 'hủy xác minh';
			$content = sprintf("Quản trị viên đã %s tin chuyển nhượng <strong>%s</strong> của bạn", $title, $oneSop['title']) ;
			$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), sprintf('|%s|', $oneSop['user_id']));
		}
	}
	// Return
	echo $msg; die();	
}
?>