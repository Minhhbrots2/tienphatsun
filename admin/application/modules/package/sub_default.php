<?php
function default_default(){
	global $assign_list,$mod,$core,$clsModule,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	// Tìm kiếm -> redirect cho gọn URL
	if(isset($_POST['filter']) && $_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$link = !empty($keyword) ? '&keyword='.$keyword : '';
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$cond = "property_type='_MF_PACKAGE' and is_trash=0";
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and title like '%".$keyword."%'";
	}
	#-------Phân trang---------------------------------------------------------------
	$recordPerPage = 20;
	$currentPage = (int) Input::get('page',1);
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";
	$totalRecord = $clsProperty->countItem($cond);
	$totalPage = ceil($totalRecord / $recordPerPage);
	$assign_list['totalRecord'] = $totalRecord;
	$assign_list['totalPage'] = $totalPage;
	$assign_list['currentPage'] = $currentPage;
	$listPageNumber = array();
	for($i=1; $i<=$totalPage; $i++){ $listPageNumber[] = $i; }
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
	#-------Hết phân trang-----------------------------------------------------------
	$field = "{$clsProperty->pkey},title,property_code,more_information,order_no,is_trash,upd_date";
	$allItem = $clsProperty->getAll($cond." order by order_no ASC".$limit, $field);
	if(!empty($allItem)){
		foreach($allItem as $k => $v){
			$mi = $clsISO->to_array_json($v['more_information']);
			if(!is_array($mi)) $mi = array();
			$mi['price_3month_f'] = !empty($mi['price_3month']) ? number_format($mi['price_3month'],0,',','.') : '-';
			$mi['price_6month_f'] = !empty($mi['price_6month']) ? number_format($mi['price_6month'],0,',','.') : '-';
			$mi['price_year_f']   = !empty($mi['price_year'])   ? number_format($mi['price_year'],0,',','.')   : '-';
			$allItem[$k]['mi'] = $mi;
		}
	}
	$assign_list["allItem"] = $allItem;
}
function default_open(){
	global $smarty,$core,$clsISO;
	$clsProperty = new Property();
	$smarty->assign('clsISO',$clsISO);
	$property_id = (int) Input::post('property_id', 0);
	$action = "_add";
	$oneItem = array('title'=>'','property_code'=>'','order_no'=>0,'bgcolor'=>'');
	$mi = array();
	if($property_id > 0){
		$action = "_edit";
		$oneItem = $clsProperty->getOne($property_id);
		$mi = $clsISO->to_array_json($oneItem['more_information']);
		if(!is_array($mi)) $mi = array();
	}
	$smarty->assign('action',$action);
	$smarty->assign('property_id',$property_id);
	$smarty->assign('oneItem',$oneItem);
	$smarty->assign('mi',$mi);
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.package.tpl');
	echo json_encode(array('html'=>$html)); die();
}
function default_pop_save(){
	global $core,$clsISO;
	$clsProperty = new Property();
	###
	$msg = "_error";
	$property_id = (int) Input::post('property_id', 0);
	$user_id = $core->_USER['user_id'];
	// Giữ nguyên các key more_information khác (nếu sửa)
	$mi = array();
	if($property_id > 0){
		$mi = $clsISO->to_array_json($clsProperty->getOneField('more_information', $property_id));
		if(!is_array($mi)) $mi = array();
	}
	$mi['price_3month'] = (int) $clsISO->processSmartNumber(Input::post('price_3month',0));
	$mi['price_6month'] = (int) $clsISO->processSmartNumber(Input::post('price_6month',0));
	$mi['price_year']   = (int) $clsISO->processSmartNumber(Input::post('price_year',0));
	$mi['day_trial']    = (int) Input::post('day_trial',0);
	$mi['show_pricing'] = (int) Input::post('show_pricing',0);
	$mi['package_intro']= Input::post('package_intro');
	$mi['trial_terms']  = Input::post('trial_terms');
	$mi['features']     = Input::post('features');
	$data = array(
		'property_type'  => '_MF_PACKAGE',
		'title'          => Input::post('title'),
		'property_code'  => Input::post('property_code'),
		'bgcolor'        => Input::post('bgcolor'),
		'order_no'       => (int) Input::post('order_no',0),
		'more_information'=> json_encode($mi, JSON_UNESCAPED_UNICODE),
		'upd_date'       => time(),
		'user_id_update' => $user_id
	);
	$clsActivityLog = new ActivityLog();
	if($property_id > 0){
		if($clsProperty->updateOne($property_id, $data)){ $msg = "_success"; }
		$clsActivityLog->addActivityLog("Property","update",array("property_type"=>"_MF_PACKAGE","property_id"=>$property_id));
	} else {
		$property_id = $clsProperty->getMaxId();
		$data[$clsProperty->pkey] = $property_id;
		$data['is_trash'] = 0;
		$data['user_id']  = $user_id;
		$data['reg_date'] = time();
		if($clsProperty->insert($data)){ $msg = "_success"; }
		$clsActivityLog->addActivityLog("Property","insert",array("property_type"=>"_MF_PACKAGE","property_id"=>$property_id));
	}
	echo json_encode(array('msg'=>$msg)); die();
}
function default_delete(){
	global $mod,$core;
	$clsProperty = new Property();
	$id = isset($_GET['property_id']) ? (int)$_GET['property_id'] : 0;
	if($id == 0){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	if($clsProperty->updateOne($id, array('is_trash'=>1,'upd_date'=>time()))){
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("Property","trash",array("property_type"=>"_MF_PACKAGE","property_id"=>$id));
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=TrashSuccess');
		exit();
	}
}
// ===== Phân quyền cho gói (_MF_PACKAGE) — lưu vào property.more_information.permiss_mod =====
function default_open_permiss(){
	global $smarty,$core,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	$for_id = (int) Input::post('for_id', 0);
	$profile_type = Input::post('profile_type', "MF");
	$smarty->assign('for_id', $for_id);
	$smarty->assign('profile_type', $profile_type);
	$more_information = $clsISO->to_array_json($clsProperty->getOneField('more_information', $for_id));
	if(!is_array($more_information)) $more_information = array();
	$permiss_mod = isset($more_information['permiss_mod']) ? $more_information['permiss_mod'] : array();
	$field = "{$clsPermiss->pkey},title,code";
	$list_permiss = $clsPermiss->getAll("`parent_id`=0 and `profile_type`='{$profile_type}' order by `order_no` ASC", $field);
	if(!empty($list_permiss)){
		foreach($list_permiss as $key => $val){
			$parent_id = $val[$clsPermiss->pkey];
			$list_items = $clsPermiss->getAll("`parent_id`='{$parent_id}' and `profile_type`='{$profile_type}' order by `order_no` ASC", $field);
			if(!empty($list_items)){
				foreach($list_items as $okey => $oval){
					$list_items[$okey]['checked'] = in_array($oval['code'], @array_keys($permiss_mod)) ? 1 : 0;
				}
			}
			$list_permiss[$key]['list_items'] = $list_items;
		}
	}
	$smarty->assign('list_permiss', $list_permiss);
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.permiss.tpl');
	echo json_encode(array('html' => $html)); die();
}
function default_pop_save_permiss(){
	global $core,$clsISO;
	$clsProperty = new Property();
	$for_id = (int) Input::post('for_id', 0);
	$permiss_mod = Input::post('permiss_mod', array());
	$more_information = $clsISO->to_array_json($clsProperty->getOneField('more_information', $for_id));
	if(!is_array($more_information)) $more_information = array();
	$more_information['permiss_mod'] = $permiss_mod;
	$msg = "_error";
	if($clsProperty->updateOne($for_id, array('more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)))){
		$msg = "_success";
	}
	echo $msg; die();
}
?>
