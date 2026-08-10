<?php 
function default_default(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsMember = new Member();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$assign_list["clsMember"] = $clsMember;
	###
	$classTable = "Order";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	###
	$pUrl2 = "";
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$keyword = Input::get('keyword', "");
	$assign_list["keyword"] = $keyword;
	/*List all item*/
	$cond = "1='1'";
	#Filter By Keyword
	if(!empty($keyword)){
		$cond .= " and (order_code='$keyword' or profile_id IN (SELECT profile_id FROM default_member WHERE email LIKE '%".$keyword."%' OR full_name LIKE '%".$keyword."%' OR first_name LIKE '%".$keyword."%' OR last_name LIKE '%".$keyword."%' ))";
		$pUrl2 .= "&keyword=".$keyword;
	}
	$cond2 = $cond;
	$orderBy = " reg_date DESC";
	#-------Page Divide---------------------------------------------------------------	
	$total_all = $clsClassTable->countItem($cond);	
	$total_active = $clsClassTable->countItem($cond." AND status=1");
	$total_noactive = $clsClassTable->countItem($cond." AND status=0 and is_cancel=0");
	$total_cancel = $clsClassTable->countItem($cond." AND is_cancel=1");	
	$assign_list["total_all"] = $total_all;
	$assign_list["total_active"] = $total_active;
	$assign_list["total_noactive"] = $total_noactive;
	$assign_list["total_cancel"] = $total_cancel;
	$status = Input::get("status","");
	if($status != "") {
		if($status == 1 || $status == 0){
			$cond .= " and status='{$status}' and is_cancel=0";
		}
		if($status == 2){
			$cond .= " and is_cancel='1'";
		}		
	}
	$total_record = $clsClassTable->countItem($cond);	
	
	$record_per_page = 50;
	$current_page = (int) Input::get('page',1);
	$pUrl .= '&page='.$current_page;
	
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $record_per_page,
		'link'	=> PCMS_URL.'/index.php'.$link_page_current_2
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links();
	$assign_list["html_pager"] = $html_pager;
	#
	$offset = ($current_page-1)*$record_per_page;
	$limit = " limit {$offset},{$record_per_page}";
	#-------End Page Divide-----------------------------------------------------------
//	$clsClassTable->setDeBug(1);
	$arrPackage = $clsProperty->getArraySearchByKey("_PACKAGE");
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
//	var_dump($allItem);die;
	if(!empty($allItem)){
		$arr_property_cached = array();
		$arr_cache_profile = [];
		foreach($allItem as $key => $val){
			if(!isset($arr_cache_profile[$val['profile_id']])) {
				$_oMember = $clsMember->getOne($val['profile_id']);
				$arr_cache_profile[$val['profile_id']] = $_oMember;
			}
			$allItem[$key]['package_name'] = $arrPackage[$val['package_id']]['title'];
			
			if ($val['time_package'] == "price_year") {
				$allItem[$key]['time_package'] = "12 tháng";
			}else if ($val['time_package'] == "price_6month") {
				$allItem[$key]['time_package'] = "6 tháng";
			}else if ($val['time_package'] == "price_3month") {
				$allItem[$key]['time_package'] = "3 tháng";
			}else if ($val['time_package'] == "price_month") {
				$allItem[$key]['time_package'] = "1 tháng";
			}
			
			$allItem[$key]['order_code'] = sprintf("MOC%s",$val['order_code']);
			$allItem[$key]['member_name'] = $arr_cache_profile[$val['profile_id']]['full_name'];
			$allItem[$key]['member_email'] = $arr_cache_profile[$val['profile_id']]['email'];
			$allItem[$key]['member_phone'] = $arr_cache_profile[$val['profile_id']]['phone'];
			$more_information = !empty($val['more_information']) ? $clsISO->to_array_json($val['more_information']) : [];
			$allItem[$key]['more_information'] = $more_information;
			$allItem[$key]['amount'] = $more_information["response"]['data']['amount'];
			$allItem[$key]['time_order'] = date("H:i d/m/Y",$val['reg_date']);
			$allItem[$key]['time_payment'] = ($val['status_date'] > 0) ? date("H:i d/m/Y",$val['status_date']) : "--";
			
		}
	}
//	$clsISO->print_pre($allItem); die();
	$assign_list["total_record"] = $total_record;
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
	$assign_list["pUrl2"] = $pUrl2;
}
function default_open(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	$clsService = new Service();
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$service_id = (int) Input::post('service_id', 0);
	$action = "_edit";
	$titlePage = "Thêm mới";
	$html_subcategory_options = "";
	$oneService = array('cat_id' => 0, 'project_id' => 0);
	$list_blocks = $list_buildings = $more_information = array();
	if($service_id > 0){
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneService = $clsService->getOne($service_id);
		$cat_id = $oneService['cat_id'];
		$block_id = $oneService['block_id'];
		$project_id = $oneService['project_id'];
		$list_cat_id = $oneService['list_cat_id'];
		##
		$more_information = $oneService['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		###
		$field = "{$clsProperty->pkey},`title`";
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
		$list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' and `for_id`='{$block_id}'", $field);
		##
		if($cat_id > 0){
			$field = "{$clsProperty->pkey},title";
			$tmp = $clsProperty->getAll("parent_id='{$cat_id}' order by `order_no` ASC", $field);
			if(!empty($tmp)){
				$arrs = !empty($list_cat_id) ? $clsISO->getArrayByTextSlash($list_cat_id) : array();
				foreach($tmp as $key => $val){
					$selected = in_array($val[$clsProperty->pkey], $arrs) ? " selected" : "";
					$html_subcategory_options.= sprintf('<option value="%s"%s>%s</option>', $val[$clsProperty->pkey], $selected, $val['title']);
				}
			}
		}
	}
	$smarty->assign('action', $action);
	$smarty->assign('service_id', $service_id);
	$smarty->assign('oneService', $oneService);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_buildings', $list_buildings);
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('html_subcategory_options', $html_subcategory_options);
	
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 AND is_menu='1' order by `reg_date` ASC", $field);
	$smarty->assign('list_projects', $list_projects);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.open.tpl');
	echo $html; die();
}
function default_updateStatus(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsMember = new Member(); 
	$clsOrder = new Order(); 
	$order_id = (int)Input::post("order_id",0);	
	$data = [
		"result"	=>	false
	];
	if($order_id > 0) {
		$oneOrder = $clsOrder->getOne($order_id);
		if(!empty($oneOrder)) {
			$oneMember = $clsMember->getOne($oneOrder['profile_id']);
			$more_information_mem = $oneMember["more_information"]; 
			$more_information_mem = $clsISO->to_array_json($more_information_mem);
			$more_information_mem['VIP']['start_date'] = $oneOrder['start_date'];
			$more_information_mem['VIP']['due_date'] = $oneOrder['due_date']; 
			if($more_information_mem['is_tried'] == 1) {
				$more_information_mem['is_tried'] = 2;
			}
			$clsMember->updateOne($oneOrder['profile_id'], array(
				'role_id' => $oneOrder['package_id'],
				'more_information' => json_encode($more_information_mem, JSON_UNESCAPED_UNICODE) 
			));
			$status_date = time();
			$clsOrder->updateOne($order_id, array(
				'status' => 1,
				'status_date' => $status_date	
			));
			$data = [
				"result"	=>	true,
				"status_date"	=>	date("H:i d/m/Y",$status_date)
			];
		}
	}	
	
	// Return
	echo json_encode($data); die();
}
function default_save(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	##
	$classTable = "Service";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	##
	$pvalTable = (int) Input::post($pkeyTable, 0);
	$name = Input::post('name');
//	 $clsISO->print_pre($_POST); die();
	if($pvalTable > 0){
		$oService = $clsClassTable->getOne($pvalTable);
		$more_information = $oService['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		$more_information['phone'] = Input::post('phone');
		$more_information['stock_code'] = Input::post('stock_code');
		$more_information['intro'] = addslashes(Input::post('intro'));
		$more_information['image'] = addslashes(Input::post('image'));
		$more_information['address'] = addslashes(Input::post('address'));
		if($clsClassTable->updateOne($pvalTable, array(
			'name' => $name,
			'slug'	=>	$core->replaceSpace($name),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'cat_id' => (int) Input::post('cat_id', 0),
			'project_id' => (int) Input::post('project_id', 0),
			'block_id' => (int) Input::post('block_id', 0),
			'building_id' => (int) Input::post('building_id', 0),
			'tags' => Input::post('tags', "")
		))){
			$msg = "_success";
		}
	} else {
		$more_information = array(
			'phone' => Input::post('phone'),
			'stock_code' => Input::post('stock_code'),
			'intro' => addslashes(Input::post('intro')),
			'image' => addslashes(Input::post('image')),
			'address' => addslashes(Input::post('address')),
		);
		if($clsClassTable->insert(array(
			$pkeyTable => $clsClassTable->getMaxId(),
			'name' => $name,
			'slug'	=>	$core->replaceSpace($name),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'cat_id' => (int) Input::post('cat_id', 0),
			'project_id' => (int) Input::post('project_id', 0),
			'block_id' => (int) Input::post('block_id', 0),
			'building_id' => (int) Input::post('building_id', 0),
			'tags' => Input::post('tags', ""),
			'reg_date' => time(),
			'user_id' => $user_id,
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Order";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$clsClassTable->setdeBug(1);
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->deleteOne($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		exit();
	}die;
}
?>