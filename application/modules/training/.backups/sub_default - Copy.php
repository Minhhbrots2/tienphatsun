<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsLeasing = new Leasing();
	$smarty->assign('clsLeasing', $clsLeasing);
	$clsProject = new Project();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	$current_page = $_SERVER['REQUEST_URI'];	
	$page = (int)Input::get("page",1);
	$assign_list['page'] = $page;
	
	if($cmd=="_detail"){
		$current_page = "/ct/";
		$leasing_id = Input::get('leasing_id', 0);
		$oneLeasing = $clsLeasing->getOne($leasing_id);
		$more_information = $oneLeasing['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		
		$url = $clsLeasing->getLink($leasing_id,$oneLeasing['stock_code']);
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->insert(array(
			'profile_id' => $profile_id,
			'url' => $url,
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'reg_date' => time()
		));
		
		if(isset($more_information['images']) && !empty($more_information['images'])){
			$image_page = reset($more_information['images']);
		}
		if($oneLeasing['is_soled'] || $oneLeasing['is_online']==0){
			$current_page= "/ct/me/";
		}
		$scriptJs.= '<a class="autoclick_'.$leasing_id.'"" stock_code="'.$oneLeasing['stock_code'].'" leasing_id="'.$leasing_id.'" onClick="$Core.leasing.open_leasing(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$leasing_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
		
		/*=============Title & Description Page==================*/
		$assign_list["image_page"] = PCMS_URL.$image_page;
		if($oneLeasing['title'] != ''){
			$title_page = $oneLeasing['title'].' - My Ocean City - '.PAGE_NAME;
		}else{
			$title_page = "Loại căn: ".$clsProperty->getTitle($oneLeasing[0]['bedroom_id'])." - Diện tích: ".$more_information['DT_TT']."m2 - Giá thuê: ".$clsISO->shortNumber($oneLeasing['price'])."/1 tháng".' - My Ocean City - '.PAGE_NAME;
		}
		
		$assign_list["title_page"] = $title_page;
		if(!empty($more_information['description_page'])) {
			$description_page = $more_information['description_page'];			
		}else{
			$description_page = $oneLeasing['stock_code']." + " . $clsProperty->getTitle($oneLeasing['building_id']);
			if(!empty($more_information['sop_type']) && $more_information['sop_type'] != _TYPE_HIGHLEVEL) {
				$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;
				if(!empty($more_information['bedroom_num'])) {
					$description_page .= (($description_page != "") ? " + " : "") . $more_information['bedroom_num']."PN";
				}
			}else{
				$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
				if($oneLeasing['bedroom_id'] > 0) {
					$description_page = (($description_page != "") ? " + " : "") . $clsProperty->getTitle($oneLeasing['bedroom_id']);
				}
			}
			if(!empty($more_information['DT_TT'])) {
				$description_page .=  (($description_page != "") ? " + " : "") . ($more_information['DT_TT']."m2");
			}
			if(!empty($oneLeasing['price'])) {
				$description_page .=  (($description_page != "") ? " + " : "") . $clsISO->shortNumber($oneLeasing['price'])."/1 tháng";
			}			
			$description_page .= " - " . PAGE_NAME;
			$more_information['description_page'] = $description_page;
		}
		$assign_list["description_page"] = $description_page;
	}else{
		/*=============Title & Description Page==================*/
		$title_page = 'Thuê và cho thuê căn hộ tại Vinhomes Ocean Park - My Ocean City - '.PAGE_NAME;
		$assign_list["title_page"] = $title_page;
		$description_page = ' Hàng nghìn giao dịch uy tín trên hệ thống cho thuê My OCean City - '.PAGE_NAME;
		$assign_list["description_page"] = $description_page;
	}
	$smarty->assign("scriptJs",$scriptJs);
	$smarty->assign("current_page",$current_page);
	##
	
	$_ss_view = Input::get("view","grid");
//	echo $_ss_view;die;
	vnSessionSetVar('_ss_view', $_ss_view);
	$_ss_add_stock = 0;
	if(vnSessionExist('_ss_add_stock')){
		$_ss_add_stock = vnSessionGetVar('_ss_add_stock');
		vnSessionDelVar('_ss_add_stock');
	} else if(vnSessionExist('_ss_view')){
		$_ss_view = vnSessionGetVar('_ss_view');
	}
	$smarty->assign('_ss_view', $_ss_view);
	$smarty->assign('_ss_add_stock', $_ss_add_stock);
	#
	$type_list = Input::get('type_list', "publish");
	$smarty->assign('type_list', $type_list);
	##
	$list_preloaders = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	##
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='"._PROJECT_DEF_ID."'", $field);
	$smarty->assign('list_blocks', $list_blocks);
	
	
	
}
function default_set_view(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	###
	$view = Input::post('view', 'grid');
	vnSessionSetVar('_ss_view', $view);
	// Return
	echo json_encode(array("link" => $clsISO->getLink("leasing").$view)); die();
}
function default_update_click(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	###
	$clsLeasing = new Leasing();
	$leasing_id = (int)Input::post('leasing_id', 0);
	$type = Input::post('type', 'view');
	$res = ["result" => false];
	$oneLeasing = $clsLeasing->getOne($leasing_id,'more_information');
	if(!empty($oneLeasing)){
		#update cache
		$url = $clsLeasing->getLink($leasing_id,$oneLeasing['stock_code']);
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->insert(array(
			'profile_id' => $profile_id,
			'url' => $url,
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'reg_date' => time()
		));
		
		$more_information = (array)json_decode($oneLeasing['more_information']);
		$number_view = (!empty($more_information['number_view']))?$more_information['number_view']:0;
		$click_call = (!empty($more_information['click_call']))?$more_information['click_call']:0;
		$click_zalo = (!empty($more_information['click_zalo']))?$more_information['click_zalo']:0;
//		var_dump($more_information);die;
		if($type == "view"){
			$more_information['number_view'] = ($number_view + 1);
		}else if($type == "call"){
			$more_information['click_call'] = ($click_call + 1);
		}else if($type == "zalo"){
			$more_information['click_zalo'] = ($click_zalo + 1);
		}
		if($clsLeasing->updateOne($leasing_id,['more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE)])){
			$res = ["result" => true];
		}
//		var_dump($more_information);die;
	}
	echo json_encode($res, JSON_UNESCAPED_UNICODE);die;
	
}
function default_like(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$clsLeasing = new Leasing();
	$clsProfile = new Profile();
	$leasing_id = (int)Input::post('leasing_id', 0);
	
	$oneLeasing = $clsLeasing->getOne($leasing_id);
	$stock_code = $oneLeasing['stock_code'];
	$url = "/ct/".$stock_code."-".$leasing_id.".html";
	
	$res = ["result" => false];
	$Profile = $clsProfile->getOne($profile_id);
	if($profile_id == 0){
		$res = ["result" => false,"type"=>"not_login","url"	=>	PCMS_URL.'/dang-nhap/ret='.$url];
	}
	$more_information = (array)json_decode($Profile['more_information']);
	$like_leasing = (!empty($more_information['like_leasing']))?$more_information['like_leasing']:[];
	if($leasing_id > 0){
		if (($key = array_search($leasing_id, $like_leasing)) !== false) {
			unset($like_leasing[$key]);
			$txt_title = "Thích";
		}else{
			$like_leasing[] = $leasing_id;
			$txt_title = "Bỏ thích";
		}
		$like_leasing = array_values($like_leasing);
		$more_information['like_leasing'] = $like_leasing;
		if($clsProfile->updateOne($profile_id,['more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE)])){
			$res = ["result" => true,"title"=>$txt_title];
		}
	}
	echo json_encode($res);
}
function default_open_shop(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$uid = $clsISO->getUniqid();
	$building_id = (int)Input::post("building_id",0);
	$clsShop = new Shop();
	$clsProperty = new Property();
	###
//	 $clsShop->setDeBug(1);
	$list_shops = $clsShop->getAll("`building_id`='{$building_id}'");	
//	var_dump($list_shops);die;
	foreach($list_shops as $key => $val){
		$list_cat_id = $val['list_cat_id'];
		$list_cat_id = $clsISO->getArrayByTextSlash($list_cat_id);
		if(!empty($list_cat_id)){
			$field = "{$clsProperty->pkey},title";
			$list_cats = $clsProperty->getAll("`property_id` IN (".implode(',', $list_cat_id).")", $field);
			$list_shops[$key]['list_cats'] = $list_cats;
		}
	}
	$assign_list['clsShop'] = $clsShop;
	$assign_list['list_shops'] = $list_shops;
	// Return
	$html = $core->build('_ajax.shop.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_utilities(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$uid = $clsISO->getUniqid();
	$leasing_id = (int)Input::post("leasing_id",0);
	$clsLeasing = new Leasing();	
	$clsProperty = new Property();
	
	$list_device_leasing = $clsProperty->getCacheItems('_DEVICE');
	$array_device_leasing = [];
	foreach($list_device_leasing as $key => $value){
		$array_device_leasing[$value['property_id']] = [
			'property_id'	=>	$value['property_id'],
			'title'			=>	$value['title'],
			'image'			=>	$value['image'],
		];
	}
	###
	$oneLeasing = $clsLeasing->getOne($leasing_id,"more_information");	
	$more_information = $clsISO->to_array_json($oneLeasing['more_information']);
//	var_dump($more_information);die;
	
	$total_utilities = 0;
	foreach($more_information['device_leasing'] as $key => $value){
		$total_utilities += count($value);
	}
	$assign_list['total_utilities'] = $total_utilities;
	$assign_list['array_device_leasing'] = $array_device_leasing;
	$assign_list['more_information'] = $more_information;
	// Return
	$html = $core->build('_ajax.utilities.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_openAddPayOther(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$html = $core->build('_ajax.formAddPayOther.tpl');
	$callback  = '';
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_showShop(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$building_id = (int)Input::post("building_id",0);
	
	$clsShop = new Shop();
	$assign_list['clsShop'] = $clsShop;
	
	$lstShop = $clsShop->getAll("building_id='{$building_id}'");	
	$assign_list['lstShop'] = $lstShop;
	$html = $core->build('_ajax.listShop.tpl');
	$callback  = '';
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_showService(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$leasing_id = (int)Input::post("leasing_id",0);
	
	$clsLeasing = new Leasing();
	$assign_list['clsLeasing'] = $clsLeasing;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	
	$list_device_leasing = $clsProperty->getCacheItems('_DEVICE');
	$array_device_leasing = [];
	foreach($list_device_leasing as $key => $value){
		$array_device_leasing[$value['property_id']] = [
			'property_id'	=>	$value['property_id'],
			'title'			=>	$value['title'],
			'image'			=>	$value['image'],
		];
	}
	$assign_list['array_device_leasing'] = $array_device_leasing;
	
	$oneLeasing = $clsLeasing->getOne($leasing_id);	
	$more_information = (array)json_decode($oneLeasing['more_information']);
	$assign_list['more_information'] = $more_information;
	$html = $core->build('_ajax.listServices.tpl');
	$callback  = '';
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_AddPayOther(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$leasing_id = (int)Input::post('leasing_id', 0);
	$title_pay_other = Input::post('title_pay_other', "");
	$type = Input::post('type', 'view');
	$res = ["result" => false];
	$oneLeasing = $clsLeasing->getOne($leasing_id,'more_information');
	if(!empty($oneLeasing) && $title_pay_other != ''){
		$more_information = (array)json_decode($oneLeasing['more_information']);
		$lst_payment_other = (!empty($more_information['lst_payment_other']))?$more_information['lst_payment_other']:array();
		$lst_payment_other[] = $title_pay_other;
		$more_information['lst_payment_other'] = $lst_payment_other;
		var_dump($more_information);die;
		$clsLeasing->updateOne($leasing_id,['more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE)]);
		$res = ["result" => true];
	}
	echo json_encode($res, JSON_UNESCAPED_UNICODE);die;
}
function default_list_leasing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType,$oneProfile,$type_list;
	$clsLeasing = new Leasing();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	### 
	$type_id = Input::post('type_id', 0);
	$project_id = Input::post('project_id', 0);
	$block_id = Input::post('block_id', 0);
	$building_ids = Input::post('building_ids');
	$status_ids = Input::post('status_ids');
	$bedroom_ids = Input::post('bedroom_ids');
	$home_direction_ids = Input::post('home_direction_ids');
	$floor_range = Input::post('floor_range');
	$price_min = Input::post('price_min', 0);
	$price_max = Input::post('price_max', 0);
	$user_id = Input::post('user_id', 0);
	$keyword = Input::post('keyword', "");
	$price_min = !empty($price_min) ? (int) $clsISO->processSmartNumber($price_min) : 0;
	$price_max = !empty($price_max) ? (int) $clsISO->processSmartNumber($price_max) : 0;
	$area_min = Input::post('area_min', 0);
	$area_max = Input::post('area_max', 0);
	$area_min = !empty($area_min) ? (int) $clsISO->processSmartNumber($area_min) : 0;
	$area_max = !empty($area_max) ? (int) $clsISO->processSmartNumber($area_max) : 0;
	$type_list = Input::post('type_list', 'publish');
	$sort_by = Input::post('sort_by', ($type_list=='publish'?"date_asc":"reg_date"));
	
	$rental_term = Input::post('rental_term');
	###
	$_ss_view = 'grid';
	if(vnSessionExist('_ss_view')){
		$_ss_view = vnSessionGetVar('_ss_view');
	}
	###
	if($type_list=='me'){
		if($profile_id != 289) {
			$cond = "`user_id`='{$profile_id}'";	
		}else{
			$cond = "1=1";	
		}
		$ret_url = "/ct/me/";
	} else if($type_list=='manager'){
		$cond = "1=1";
		$ret_url = "/ct/manager/";
	} else {
		$ret_url = "/ct/";
		$cond = "`is_trash`=0 and `is_online`='1'";
	}
	$url = $ret_url;
	
	if($keyword != ""){
		$cond.= " AND (`title` like '%{$keyword}%' or `stock_code` like '%{$keyword}%')";
		$url .= "&keyword=".$keyword;
	}
	if($user_id > 0) $cond.= " and `user_id`='{$user_id}'";
	#- Filter sop type
	if($type_id == _TYPE_HIGHLEVEL) {
		$cond.= " and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and JSON_EXTRACT(`more_information`,\"$.sop_type\") = '".$type_id."'";
		$url .= "&type=".$type_id;
	}else if($type_id == _TYPE_LOWFLOOR) {
		$cond.= " and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and JSON_EXTRACT(`more_information`,\"$.sop_type\") = '".$type_id."'";
		$url .= "&type=".$type_id;
	}
	#- Filter project
	if($project_id > 0){ 
		$cond.=" and project_id = '{$project_id}'";
		$url .= "&project=".$project_id;
	}
	#- Filter Block
	if($block_id > 0) {
		$cond.=" and (`block_id`='{$block_id}')";
		$url .= "&block=".$block_id;
	}
	#- Filter Status
	if(!empty($status_ids)) {
		$cond.= " and (`status_id` in (".implode(',', $status_ids)."))";
		$url .= "&status_ids=".implode(',', $status_ids);
	}
	#- Filter Building
	if(!empty($building_ids)) {
		$cond.= " and (`building_id` in (".implode(',', $building_ids)."))";
		$url .= "&building=".implode(',', $building_ids);
	}
//	echo $cond;die;
	#- Filter Bedroom
	if(!empty($bedroom_ids)) {
		$cond.= " and (`bedroom_id` in (".implode(',', $bedroom_ids)."))";
		$url .= "&bedroom=".implode(',', $bedroom_ids);
	}
	#- Filter Direction
	if(!empty($home_direction_ids)) {
		$cond.= " and (`home_direction_id` in (".implode(',', $home_direction_ids)."))";
		$url .= "&direction=".implode(',', $home_direction_ids); 
	}
	#- Filter Price
	if($price_min > 0 && $price_max == 0){
		$cond.= " and (`price`>='{$price_min}')";
		$url .= "&price_min=".$price_min;
	} else if($price_min == 0 && $price_max > 0){
		$cond.= " and (`price`<='{$price_max}')";
		$url .= "&price_min=".$price_min."&price_max=".$price_max;
	} else if($price_min > 0 && $price_max > 0){
		$cond.= " and (`price` between '{$price_min}' and '{$price_max}')";
		$url .= "&price_min=".$price_min."&price_max=".$price_max;
	}
	#- Filter Area
	if($area_min > 0 && $area_max == 0){
		$cond.= " and CAST( JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2) ) >= '".$area_min."'";
		$url .= "&area_min=".$area_min;
	} else if($area_min == 0 && $area_max > 0){
		$cond.= " and CAST( JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2) ) <='".$area_max."'";
		$url .= "&area_min=".$area_min."&area_max=".$area_max;
	} else if($area_min > 0 && $area_max > 0){
		$cond.= " and CAST( JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2) ) BETWEEN '".$area_min."' AND '".$area_max."'";
		$url .= "&area_min=".$area_min."&area_max=".$area_max;
	}
	if(!empty($floor_range)){
		$list_floors = array();
		foreach($floor_range as $floor){
			$tmp = explode('-', $floor);
			for($i=$tmp[0]; $i<=$tmp[1]; $i++){
				if($i==4) {
					$list_floors[] = '5A';
				} else if($i==7){
					$list_floors[] = '8A';
				} else if($i==13){
					$list_floors[] = '12A';
				}  else if($i==14){
					$list_floors[] = '15A';
				}
				$list_floors[] = $clsISO->parseNumber($i);
			}
		}
		if(!empty($list_floors)){
			$cond.= " and (`floor` in ('".implode('\',\'', $list_floors)."'))";
			$url .= "&floor_range=".implode(',', $list_floors);
		}
	}
	if(!empty($rental_term)){
		$cond .= "  AND `ms_period_id` IN (".implode(",",$rental_term).")";
	}
	$current_page = (int) Input::post('page',1);
	if($current_page > 1) {
		$url .= "&page=".$current_page;
	}
	$per_page = (int) Input::post('per_page',16);
//	$clsLeasing->setDeBug(1);
	$total_record = $clsLeasing->countItem($cond);
//	$clsISO->print_pre($total_record);die;
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$leasing_field = "{$clsLeasing->pkey},`title`,`stock_code`,`price`,`more_information`,`contact_name`
	,`contact_phone`,`stock_id`,`stock_type`,`bedroom_id`,`home_direction_id`,`is_verified`,`floor`,`block_id`,`building_id`,`is_locked`
	,`is_online`,`is_solded`,`status_id`,`upd_date`";
	if($type_list=='me'){
		$order_by = " order by `is_solded` ASC,`upd_date` DESC";
		if($sort_by=='date_asc'){
			$order_by = " order by `is_solded` ASC, `upd_date` ASC";
		} else if($sort_by=='date_desc'){
			$order_by = " order by `is_solded` ASC, `upd_date` DESC";
		} else if($sort_by=='price_asc'){
			$order_by = " order by `is_solded` DESC, `price` ASC";
		}  else if($sort_by=='price_desc'){
			$order_by = " order by `is_solded` DESC, `price` DESC";
		}
	} else {
		$order_by = " order by `price` ASC";
		if($sort_by=='date_asc'){
			$order_by = " order by `reg_date` ASC";
		} else if($sort_by=='date_desc'){
			$order_by = " order by `reg_date` DESC";
		} else if($sort_by=='price_desc'){
			$order_by = " order by `price` DESC";
		}
	}
	$list_leasing = $clsLeasing->getAll($cond.$order_by.$limitCond,$leasing_field);
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstHomeDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	
	if(!empty($list_leasing)){
		$arr_status_cached = $arr_property_cached = array();
		foreach($list_leasing as $key => $val){
			$block_idd = $val['block_id'];
			$building_id = $val['building_id'];
			$status_id = $val['status_id'];
			$bedroom_id = $val['bedroom_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_leasing[$key]['more_information'] = $more_information;
			###
			if(isset($more_information['is_owner']) && $more_information['is_owner'] == 1) {
				$list_leasing[$key]['label_owner']= '<span class="awe__leasing-badge zindex-2 bg-success" style="color:#FFF">Chính chủ</span>';
			}else{
				$list_leasing[$key]['label_owner'] = "";
			}
			if($status_id > 0 && !isset($arr_status_cached[$status_id])){
				$oProperty = $clsProperty->getOne($status_id, "title,bgcolor,textcolor");
				$arr_status_cached[$status_id] = sprintf(
					'<span class="awe__leasing-badge zindex-2" style="background:%s; color:%s">%s</span>', 
					$oProperty['bgcolor'], $oProperty['textcolor'], $oProperty['title']
				);
			}
			if($status_id > 0 && isset($arr_status_cached[$status_id])){
				$list_leasing[$key]['label_status'] = $arr_status_cached[$status_id];
			} else {
				$list_leasing[$key]['label_status'] = "";
			}
			$images = isset($more_information['images']) ? $more_information['images'] : array();
			$list_leasing[$key]['images'] = $images;
			###			
			$arr_property_cached[$block_idd] = $lstBlock[$block_idd]['title'];
			$arr_property_cached[$building_id] = $lstBuilding[$building_id]['title'];
			$arr_property_cached[$bedroom_id] = $lstBedroom[$bedroom_id]['title'];
			$arr_property_cached[$home_direction_id] = $lstHomeDirection[$home_direction_id]['title'];
			$list_leasing[$key]['block_name'] = $arr_property_cached[$block_idd];
			$list_leasing[$key]['building_name'] = $arr_property_cached[$building_id];
			$list_leasing[$key]['bedroom'] = $arr_property_cached[$bedroom_id];
			$list_leasing[$key]['home_direction'] = $arr_property_cached[$home_direction_id];	
			$list_leasing[$key]['having_dq'] = !(empty($more_information['having_dq']))?$more_information['having_dq']:0;
		}
	}
	
	$smarty->assign('clsLeasing', $clsLeasing);
	$smarty->assign('ret_url', $ret_url);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('list_leasing', $list_leasing);
	$smarty->assign('_ss_view', $_ss_view);
	$smarty->assign('type_list', $type_list);
	$smarty->assign('deviceType', $deviceType);
	// Return
	$profile_infomation =$oneProfile['more_information'];
	$like_leasing = (!empty($profile_infomation['like_leasing']))?$profile_infomation['like_leasing']:[];
	$smarty->assign('like_leasing', $like_leasing);
	
	$html = $core->build('_ajax.leasing.tpl');
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'url' => $url
	)); die();
}	
function default_load_project(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProject = new Project();
	##
	$tp = Input::post('tp', 'option');
	$type_id = Input::post("type_id",_TYPE_HIGHLEVEL);
	if($type_id == _TYPE_HIGHLEVEL) {
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($type_id > 0) {
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}		
	$field = "{$clsProject->pkey},title,more_information";
	$list_project = $clsProject->getAll("`is_trash`=0 AND project_id IN (".implode(",",_PROJECT_OCEAN_CITY).")" . $cond_project, $field);
	$html = "";
	if(!empty($list_project)){
		if($tp == 'radio'){
			foreach($list_project as $key => $val){
				$html.= '<label class="we-radio" for="rdo_'.$val[$clsProject->pkey].'">
							<input type="radio" onChange="$Core.leasing.select_project(this, event)" class="js__option-project" toId="js__dropdown-block" tp="radio" id="rdo_'.$val[$clsProject->pkey].'" name="project_id" value="'.$val[$clsProject->pkey].'" >
							<span>'.$val['title'].'</span>
						</label>';
			}
		} else {
			foreach($list_project as $key => $val){
				$html .= '<div class="dropdown-item">
							<div class="form-check cursor-pointer">
								<input type="radio" name="project_id" 
								class="form-check-input js__option-project" id="rdo_'.$val[$clsProject->pkey].'" value="'.$val[$clsProject->pkey].'" 
								title="'.$val['title'].'" onchange="$Core.leasing.select_project(this,event)" tp="option" toId="js__dropdown-block-list">
								<label class="form-check-label" for="rdo_'.$val[$clsProject->pkey].'">'.$val['title'].'</label>
							</div>
						</div>';
			}
		}
	}
	// Return
	echo $html; die();
}	
function default_load_block(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	##
	$tp = Input::post('tp', 'option');
	$type_id = (int) Input::post('type_id', _TYPE_HIGHLEVEL);
	if($type_id == _TYPE_HIGHLEVEL) {
		$cond = " AND parent_id='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
	}else if($type_id > 0) {
		$cond = " AND parent_id='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
	}
	$project_id = (int) Input::post('project_id', 0);
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("for_id='{$project_id}'".$cond, $field);
	##
	$html = '';
	if(!empty($list_blocks)){
		if($tp == 'radio'){
			foreach($list_blocks as $key => $val){
				$html.= '<label class="we-radio" for="rdo_'.$val[$clsProperty->pkey].'">
							<input type="radio" onChange="$Core.leasing.select_block(this, event)" class="js__option-block" toId="js__dropdown-building" tp="radio" id="rdo_'.$val[$clsProperty->pkey].'" name="block_id" value="'.$val[$clsProperty->pkey].'" >
							<span>'.$val['title'].'</span>
						</label>';
			}
		} else {
			foreach($list_blocks as $key => $val){
				$html.= '<div class="dropdown-item">
							<div class="form-check cursor-pointer">
								<input type="radio" name="block_id" 
								class="form-check-input js__option-block" id="rdo_'.$val[$clsProperty->pkey].'" value="'.$val[$clsProperty->pkey].'" 
								title="'.$val['title'].'" onchange="$Core.leasing.select_block(this,event)" tp="option" toId="js__dropdown-building-list">
								<label class="form-check-label" for="rdo_'.$val[$clsProperty->pkey].'">'.$val['title'].'</label>
							</div>
						</div>'; 
			}
		}
		
	}
	// Return
	echo $html; die();
}
function default_load_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$clsProperty = new Property();
	###
	$type_id = (int) Input::post('type_id', _TYPE_HIGHLEVEL);
	if($type_id == _TYPE_HIGHLEVEL) {
		$cond_type = " AND `property_type`='_BUILDING'";
	}else if($type_id > 0) {
		$cond_type = " AND `property_type`='_RANGE'";
	}
	$html = "";
	$tp = Input::post('tp', 'option');
	$block_id = (int)Input::post('block_id',0);
	
	$field = "{$clsProperty->pkey},title";	
	$list_buildings = $clsProperty->getAllCache("for_id='".$block_id."'".$cond_type, $field);
	
	if(!empty($list_buildings)) {
		if($tp == 'radio'){
			foreach($list_buildings as $key => $val){
				$html.= '<label class="we-checkbox" for="chk_'.$val[$clsProperty->pkey].'">
							<input class="js__option-building" type="checkbox" id="chk_'.$val[$clsProperty->pkey].'" name="building_id[]" value="'.$val[$clsProperty->pkey].'" >
							<span>'.$val['title'].'</span>
						</label>';
			}
		} else {
			$gid = $clsISO->getUniqid();
			foreach ($list_buildings as $key => $value) {
				$html .= '<div class="dropdown-item">
					<div class="form-check mb-0 cursor-pointer">
						<input gId="'.$gid.'" class="form-check-input chk_building js__option-building" onChange="$Core.leasing.select_checkbox(this,event)" 
							id="chk_'.$value["property_id"].'" value="'.$value["property_id"].'" title="'.$value["title"].'" type="checkbox">
						<label class="form-check-label" for="chk_'.$value["property_id"].'">'.$value["title"].'</label>
					</div>
				</div>';
			}
		}
	}
	// Return
	echo $html; die();
}
function default_edit(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$clsConfiguration,$loggedIn;
	$clsLeasing = new Leasing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$smarty->assign('clsLeasing', $clsLeasing);
	$smarty->assign('clsProperty', $clsProperty);
	if($loggedIn!= 1 || ($loggedIn==1 && $profile_id == _PROFILE_GENERAL_ID)){
		header('Location:/dang-nhap/ret=/ct/add');
		exit();
	}
	###
	$_token = CSRF::generate('upload-video');
	$smarty->assign('_token', $_token);
	###	
	
	$list_property = $clsProperty->getCacheItems('_HIDECODE');
	$smarty->assign('list_property', $list_property);
	
	$lst_codeLowFloor = $clsProperty->getCacheItems('_HIDECODELOWFLOOR');
	$smarty->assign('lst_codeLowFloor', $lst_codeLowFloor);
	
	$list_needs = $clsProperty->getCacheItems('_NEED_TYPE');
	$smarty->assign('list_needs', $list_needs);
	
	$list_phaply = $clsProperty->getCacheItems('_JURIDICAL');
	$smarty->assign('list_phaply', $list_phaply);

	$list_payment_leasing = $clsProperty->getCacheItems('_PAYMENT_LEASING');
	$smarty->assign('list_payment_leasing', $list_payment_leasing);

	$list_rental_term_leasing = $clsProperty->getCacheItems('_RENTAL_TERM_LEASING');
	$smarty->assign('list_rental_term_leasing', $list_rental_term_leasing);
	
	$list_device_leasing = $clsProperty->getCacheItems('_DEVICE');
	$array_decvice_leasing = $clsISO->convertDataArrayCache($list_device_leasing);
	$smarty->assign('array_decvice_leasing', $array_decvice_leasing);
	
	$list_advantage = $clsProperty->getCacheItems('_ADVANTAGE_LEASING');
	$smarty->assign('list_advantage', $list_advantage);
	
	$list_sop_type = $clsProperty->getCacheItems('_SOPTYPE');
	$smarty->assign('list_sop_type', $list_sop_type);
	
	$list_type_villa = $clsProperty->getCacheItems('_TYPE_VILLA');
	foreach ($list_type_villa as $k => $v) {
		if(!in_array($v['property_id'], _ARRAY_TYPE_VILLA) ){
			unset($list_type_villa[$k]);
		}
	}
	$smarty->assign('list_type_villa', $list_type_villa);
	###
	$leasing_id = Input::get('leasing_id', 0);
	$return_url = Input::get('return_url', '/ct/me/');
	if(!$return_url) $return_url = '/ct/me/';
	$action = Input::get('action', "action");
	###
	$total_images = 0;
	$sop_type = _TYPE_HIGHLEVEL;
	$oneLeasing = $more_information = array(
		'hide_code' => _STOCK_HIDECODE_NO_ID,
		'interior_id' => 0,
		'juridical_id' => 0,
		'fee_included' => 0,
		'ms_period_id'	=>	LEASING_CONTRACT_DEFAULT_ID,
		'is_owner' => 0,
		'sop_type' => $sop_type
	);
	if($action=='edit'){
		$oneLeasing = $clsLeasing->getOne($leasing_id);
		if($oneLeasing['user_id'] != $profile_id && !$clsISO->checkPermission('edit_transfer')){ 
			header('Location:'.$return_url);
			exit();
		}
		
		$more_information = $oneLeasing['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
//		$clsISO->print_pre($more_information);die;
		$more_information['DT_TT'] = str_replace(",",".",$more_information['DT_TT']);
		if(!isset($more_information['video_type']) || (isset($more_information['video_type']) && empty($more_information['video_type'])))
			$more_information['video_type'] = "";
		$total_images = isset($more_information['images']) && !empty($more_information['images']) 
			? count($more_information['images']) : 0;
		$device_leasing = $more_information['device_leasing'];
		
		$listBlocks = $clsProperty->getAllCache("property_type='_BLOCK' and for_id='".$oneLeasing['project_id']."'", $clsProperty->pkey.',title');
		$oneBlock = $clsProperty->getOne($oneLeasing['block_id'],'parent_id');
		if($oneBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$cond_building = " AND property_type='_RANGE'";
		}else{				
			$cond_building = " AND property_type='_BUILDING'";
		}
		$listBuildings = $clsProperty->getAllCache("for_id='".$oneLeasing['block_id']."'".$cond_building, $clsProperty->pkey.',title');
		$list_range_floors = [];
		if(!empty($oneLeasing['building_id'])){
			$oneBuilding = $clsProperty->getOne($oneLeasing['building_id'],"more_information");
			$moreInformation = $clsISO->to_array_json($oneBuilding['more_information']);
			$number_floor = $moreInformation['number_floor'];
			for($i=1; $i<= $number_floor; $i++){
				if($i==4) {
					$title_floor = '5A';
				} else if($i==7){
					$title_floor = '8A';
				} else if($i==13){
					$title_floor = '12A';
				}  else if($i==14){
					$title_floor = '15A';
				} else {
					$title_floor = $clsISO->parseNumber($i);
				}
				
				$list_range_floors[$i] = $title_floor;
			}
		}	
		$smarty->assign('listBlocks', $listBlocks);
		$smarty->assign('listBuildings', $listBuildings);
		$smarty->assign('list_range_floors', $list_range_floors);		
		$smarty->assign('device_leasing', $device_leasing);
		$sop_type = $more_information['sop_type'];
//		$clsStock->setDeBug(1);
		$checkDisabledSopType = $clsStock->countItem("`ms_code`='{$oneLeasing['stock_code']}' AND `stock_id`='{$oneLeasing['stock_id']}'");
//		$clsISO->print_pre($checkDisabledSopType);die;
		$smarty->assign('checkDisabledSopType', $checkDisabledSopType);	
	}	
	if($sop_type == _TYPE_HIGHLEVEL) {
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($sop_type > 0) {
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}		
	$field = "{$clsProject->pkey},title,more_information";
	$list_project = $clsProject->getAll("`is_trash`=0 AND project_id IN (".implode(",",_PROJECT_OCEAN_CITY).")" . $cond_project, $field);
	$smarty->assign('list_project', $list_project);
	
	$smarty->assign('action', $action);
	$smarty->assign('oneLeasing', $oneLeasing);
	$smarty->assign('total_images', $total_images);
	$smarty->assign('more_information', $more_information);
	if(isset($_POST['hid']) && $_POST['hid']=='hid'){
//		$clsISO->print_pre($_POST);die;
		$title = Input::post('title');
		$price_negotiable = Input::post('price_negotiable',0);
		if($price_negotiable == 0) {
			$price = Input::post('price');
		}else{
			$price = 0;
		}		
		$video = Input::post('video');
		$images = Input::post('images');
		$having_ns = Input::post('having_ns', 'no');
		$bedroom_num = (int)Input::post('bedroom_num', 0);
		if($bedroom_num == 0) {
			$bedroom_num = (int)Input::post('bedroom', 0);
		}
		$bathroom_num = (int)Input::post('bathroom_num', 0);
		if($bathroom_num == 0) {
			$bathroom_num = (int)Input::post('bathroom', 0);
		}
		$balcony_num = (int)Input::post('balcony_num', 0);
		if($balcony_num == 0) {
			$balcony_num = (int)Input::post('balcony', 0);
		}
		$sop_type = Input::post('sop_type',0);
		$project_id = Input::post('project_id');
		$building_id = Input::post('building_id');
		$block_id = Input::post('block_id');
		$floor = Input::post('floor_range');
		$code = Input::post('code');
		$stock_code = Input::post('stock_code');
		$stock_id=0;
		
		if(!empty($sop_type) && $sop_type != _TYPE_HIGHLEVEL) {
			$checkStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `building_id`='{$building_id}' AND `block_id`='{$block_id}' AND `code`='{$code}' AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'", $clsStock->pkey.",ms_code");
			if(!empty($checkStock)) {
				$stock_id=$checkStock[$clsStock->pkey];
			}
			$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;
		}else{
			$checkStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `building_id`='{$building_id}' AND `block_id`='{$block_id}' AND `floor`='{$floor}' AND `code`='{$code}' AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'", $clsStock->pkey.",ms_code");
			if(!empty($checkStock)) {
				$stock_id=$checkStock[$clsStock->pkey];
			}
			$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
		}
		if(!empty($video)) $more_information['video'] = $video;
		if(!empty($images)) $more_information['images'] = $images;
		$more_information['hide_code'] = Input::post('hide_code');
		$more_information['content'] = Input::post('content');
		$more_information['host_name'] = Input::post('host_name');
		$more_information['host_phone'] = Input::post('host_phone');
		$more_information['base_utensils_id'] = Input::post('base_utensils_id');
		$more_information['payment_leasing_id'] = Input::post('payment_leasing_id');
		$more_information['status_leasing_id'] = Input::post('status_leasing_id');
		$more_information['payment_electric_id'] = Input::post('payment_electric_id');
		$more_information['payment_water_id'] = Input::post('payment_water_id');
		$more_information['rental_period'] = Input::post('rental_period');
		$more_information['validity_period'] = Input::post('validity_period');
		$more_information['rental_term_leasing_id'] = Input::post('rental_term_leasing_id');
		$more_information['txt_rental_term'] = Input::post('txt_rental_term');
		$more_information['device_leasing'] = Input::post('device');
		$more_information['check_utilities'] = (int)Input::post('check_utilities',0);
		$more_information['floor_total'] 	= Input::post('floor_total',0);
		$more_information['type_villa'] 	= Input::post('type_villa',0);
		$str_slash = $clsLeasing->getStrListCode($_POST);
		// $clsISO->print_pre($more_information);die;
		$more_information['video_type'] = Input::post('video_type', 'youtube');
		$more_information['video_url'] = Input::post('video_url');
		$more_information['youtue_url'] = Input::post('youtue_url');
		$more_information['is_owner'] 	= Input::post('is_owner',0);	
		$more_information['having_dq'] 	= Input::post('having_dq',0);
		$more_information['sop_type'] 	= $sop_type;
		$more_information['floor_total'] 	= Input::post('floor_total',0);
		$more_information['price_negotiable'] 	= Input::post('price_negotiable',0);
		$more_information['type_villa'] 	= Input::post('type_villa',0);
		$more_information['bedroom_num'] 	= $bedroom_num;
		$more_information['bathroom_num'] 	= $bathroom_num;
		$more_information['balcony_num'] 	= $balcony_num;
		$more_information['advantage_ids'] = Input::post('advantage_ids',[]);
		$more_information['DT_TT'] = Input::post('DT_TT');
		$leasing_moderation = $clsConfiguration->getValue('leasing_moderation', 1);	
		if($leasing_id > 0){
			$arr_data = array(
				'title' 			=> $title,
				'slug' 				=> $core->replaceSpace($title),
				'stock_id' 			=> $stock_id,
				'stock_code' 		=> $stock_code,
				'stock_type' 		=> $stock_type,
				'price' 			=> $clsISO->processSmartNumber(Input::post('price')),
				'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'contact_name' 		=> Input::post('contact_name'),
				'contact_phone' 	=> Input::post('contact_phone'),
				'fee_included' 		=> Input::post('fee_included',0),
				'interior_id' 		=> Input::post('interior_id', 0),
				'juridical_id' 		=> Input::post('juridical_id', 0),
				'upd_date' 			=> time(),
				'user_id_update' 	=> $profile_id,
				'search_slash' 		=> $str_slash,
				'pay_electric_id' 	=> $more_information['payment_electric_id'],
				'pay_water_id' 		=> $more_information['payment_water_id'],
				'status_leasing_id' => $more_information['status_leasing_id'],
				'ms_utensil_id' 	=> $more_information['base_utensils_id'],
				'ms_period_id' 		=> $more_information['rental_term_leasing_id'],
				'contract_deadline' => strtotime(str_replace("/","-",$more_information['validity_period'])),
				'is_online' 		=> ((int) $leasing_moderation == 1 ? 1 : 0 ),
				'project_id' 		=> Input::post('project_id'),
				'block_id' 			=> Input::post('block_id'),
				'building_id' 		=> Input::post('building_id'),
				'floor' 			=> Input::post('floor_range'),
				'bedroom_id' 		=> Input::post('bedroom_id'),
				'home_direction_id' => Input::post('home_direction_id'),
				'code' 				=> Input::post('code'),
			);
//			$clsISO->print_pre($arr_data);die;
			if($clsLeasing->updateOne($leasing_id, $arr_data)){
				$msg = "_success";
				vnSessionSetVar('_ss_add_stock', 2);
				if($stock_id > 0){
					$clsLeasing->updateMeta($leasing_id);
				}else{
					$clsLeasing->updateMetaNoStockCode($leasing_id);
				}
				header('Location:'.$return_url);
				exit();
			}

		} else {
			$leasing_id = $clsLeasing->getMaxId();
			$arr_data = array(
				$clsLeasing->pkey 	=> $leasing_id,
				'title' 			=> $title,
				'slug' 				=> $core->replaceSpace($title),
				'stock_id' 			=> $stock_id,
				'stock_code' 		=> Input::post('stock_code'),
				'stock_type' 		=> $stock_type,
				'price' 			=> $clsISO->processSmartNumber(Input::post('price')),
				'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'contact_name' 		=> Input::post('contact_name'),
				'contact_phone' 	=> Input::post('contact_phone'),
				'fee_included' 		=> Input::post('fee_included',0),
				'interior_id' 		=> Input::post('interior_id', 0),
				'juridical_id' 		=> Input::post('juridical_id', 0),
				'reg_date' 			=> time(),
				'upd_date' 			=> time(),
				'user_id' 			=> $profile_id,
				'user_id_update' 	=> $profile_id,
				'search_slash' 		=> $str_slash,
				'pay_electric_id' 	=> $more_information['payment_electric_id'],
				'pay_water_id' 		=> $more_information['payment_water_id'],
				'status_leasing_id' => $more_information['status_leasing_id'],
				'ms_utensil_id' 	=> $more_information['base_utensils_id'],
				'ms_period_id' 		=> $more_information['rental_term_leasing_id'],
				'contract_deadline' => strtotime(str_replace("/","-",$more_information['validity_period'])),
				'is_online' 		=> ((int) $leasing_moderation == 1 ? 1 : 0 ),				
				'project_id' 		=> Input::post('project_id'),
				'block_id' 			=> Input::post('block_id'),
				'building_id' 		=> Input::post('building_id'),
				'floor' 			=> Input::post('floor_range'),
				'bedroom_id' 		=> Input::post('bedroom_id'),
				'home_direction_id' => Input::post('home_direction_id'),
				'code' 				=> Input::post('code'),
			);
//			$clsISO->print_pre($arr_data);die;
			if($clsLeasing->insert($arr_data)){
				$msg = "_success";
				vnSessionSetVar('_ss_add_stock', 1);
				if($stock_id > 0){
					$clsLeasing->updateMeta($leasing_id);
				}else{
					$clsLeasing->updateMetaNoStockCode($leasing_id);
				}
				header('Location:'.$return_url);
				exit();
			}
		}
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Đăng tin cho thuê | Vinhomes Ocean Park - My Ocean City '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Hàng nghìn giao dịch uy tín trên hệ thống cho thuê My OCean City - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_open(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$oneProfile;
	$clsLeasing = new Leasing();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsShop = new Shop();
	$smarty->assign('clsLeasing', $clsLeasing);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsShop', $clsShop);
	###
	$uid = $clsISO->getUniqid();
	$stock_id = Input::post('stock_id', 0);
	$leasing_id = Input::post('leasing_id', 0);
	$type_list = Input::post('type_list', 'publish');
	$return_url = Input::post('return_url');
	$return_url = str_replace(DOMAIN_URL, '', $return_url);
	$smarty->assign('uid', $uid);
	$smarty->assign('stock_id', $stock_id);
	$smarty->assign('leasing_id', $leasing_id);
	$smarty->assign('type_list', $type_list);
	$smarty->assign('return_url', $return_url);
	$field = "`stock_code`,`floor`,`more_information`,`user_id`,`upd_date`,`contact_phone`
	,`contact_name`,`price`,`fee_included`,`interior_id`,`block_id`,`home_direction_id`
	,`project_id`,`title`,`bedroom_id`,`building_id`,`is_solded`";
	$oneLeasing = $clsLeasing->getOne($leasing_id,$field);
	
	$user_id = $oneLeasing['user_id'];
	$more_information = $oneLeasing['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$oneLeasing['more_information'] = $more_information;
//	var_dump($more_information);die;
	$total_shops = $clsShop->countItem("`building_id`='{$oneLeasing['building_id']}'");	
	$smarty->assign('total_shops', $total_shops);
	#
	$oProfile = $clsProfile->getOne($user_id, "full_name,first_name,last_name,avatar");
	$smarty->assign('oProfile', $oProfile);
	#-Giá/m2
	$price_m2 = 0;
	$DT_TT = $more_information['DT_TT'];
	$price = $clsISO->processSmartNumber($oneLeasing['price']);
	if($price > 0 && !empty($DT_TT)){
		$price_m2 = $price / $clsISO->convertToNumber($DT_TT);
	}
	$smarty->assign('price_m2', $price_m2);
	###
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
	$smarty->assign('oneLeasing', $oneLeasing);
	$smarty->assign('list_medias', $list_medias);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('stock_information', $stock_information);
	$list_device_leasing = $clsProperty->getCacheItems('_DEVICE');
	$array_device_leasing = [];
	foreach($list_device_leasing as $key => $value){
		$array_device_leasing[$value['property_id']] = [
			'property_id'	=>	$value['property_id'],
			'title'			=>	$value['title'],
			'image'			=>	$value['image'],
		];
	}
	$smarty->assign('array_device_leasing', $array_device_leasing);
	$smarty->assign('uid', $clsISO->getUniqid());
	
	$profile_infomation =$oneProfile['more_information'];
	$like_leasing = (!empty($profile_infomation['like_leasing']))?$profile_infomation['like_leasing']:[];
	$smarty->assign('like_leasing', $like_leasing);
	
	$lstShop = $clsShop->getAll("building_id='{$oneLeasing['building_id']}'");
	foreach($lstShop as $key => $shop){
		$lstCatChildID = trim($shop['list_cat_id'],"|");
		if($lstCatChildID !=""){
			$lstCatChild = $clsProperty->getAll("property_id IN (".str_replace("|",",",$lstCatChildID).")",$clsProperty->pkey.',title');
			$lstShop[$key]['lstCatChild'] = $lstCatChild;
		}		
		unset($lstCatChildID,$lstCatChild);
	}
	$assign_list['lstShop'] = $lstShop;
	$advantage_ids = [];
	if(!empty($more_information['advantage_ids'])) {		
		$lstAdvantage = $clsProperty->getArraySearchByKey("_ADVANTAGE_LEASING");
		foreach ($more_information['advantage_ids'] as $k => $advantage) {
			if(isset($lstAdvantage[$advantage])) {
				$advantage_ids[]  = $lstAdvantage[$advantage];	
			}			
		}		
	}
	$smarty->assign('advantage_ids', $advantage_ids);
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
        'leasing_id' => $leasing_id
	)); die();
}
function default_delete(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsLeasing = new Leasing();
	$clsProperty = new Property();
	###
	$data = [
		"result"	=>	false,
		"msg"		=>	"_error",
	];
	$icon = "";
	$leasing_id = Input::post('leasing_id', 0);
	if($leasing_id > 0){
		$oneLeasing = $clsLeasing->getOne($leasing_id, "more_information,logs,title,user_id");
		$more_information = $oneLeasing['more_information'];
		$more_information = $clsISO->to_array_json($more_information);	
		
		
		if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1){
			$is_trash = 0;
			$is_online = 0;
			$is_deleted = 0;
			unset($more_information['is_deleted']);
			$html = '<a class="dropdown-item leasing__menu-delete-'.$leasing_id.'" onClick="$Core.leasing.delete(this, event)" leasing_id="'.$leasing_id.'" href="javascript:void(0);" data-is_deleted="0">
						<i class="material-icons-outlined">delete</i> Xoá</a>';
		} else {
			$is_trash = 1;
			$is_online = 0;
			$is_deleted = 1;
			$more_information['is_deleted'] = 1;
			$html = '<a class="dropdown-item leasing__menu-delete-'.$leasing_id.'" onClick="$Core.leasing.delete(this, event)" leasing_id="'.$leasing_id.'" href="javascript:void(0);"  data-is_deleted="1">
						<i class="material-icons-outlined">delete</i> Khôi phục</a>';
		}
		
		$logs = $oneLeasing["logs"];
		$logs = $clsISO->to_array_json($logs);
		$logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'user_id' => $profile_id,
			'to_value' => $is_deleted,
			'field' => 'is_deleted'
		);
		
		if($clsLeasing->updateOne($leasing_id, array(
			'upd_date' => time(),
			'is_trash' => $is_trash,
//			'is_online' => $is_online,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'logs' 				=> json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			if($is_trash == 1){
				$msg = "Xoá thành công";
			}else{
				$msg = "Khôi phục thành công";
			}
			$icon = $clsLeasing->getIcon($leasing_id);
			$data = [
				"result"	=>	true,
				"msg"		=>	$msg,
				"html"		=>	$html,
				"icon"		=>	$icon,
			];
			/*$clsNotify = new Notify();
			$title = ($is_deleted==1) ? 'xoá' : 'khôi phục';
			if($profile_id == $oneLeasing['user_id']){
				$txt_user = "Bạn";
			}else{
				$txt_user = "Quản trị viên";
			}
			$content = sprintf("%s đã %s tin cho thuê <strong>%s</strong> của bạn",$txt_user, $title, $oneLeasing['title']) ;
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), sprintf('|%s|', $oneLeasing['user_id']));*/
		}
	} else {
		$data = [
			"result"	=>	false,
			"msg"		=>	"_invalid",
		];
	}
	// Return
	echo json_encode($data); die();
}
function default_mark_lock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsLeasing = new Leasing();
	$clsProperty = new Property();
	###
	$html = ""; $icon_lock = ""; $icon = ""; $msg = "_error";
	$leasing_id = Input::post('leasing_id', 0);
	if($leasing_id > 0){
		$oLeasing = $clsLeasing->getOne($leasing_id, "is_locked,more_information,logs,user_id");
		$more_information = $oLeasing['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		// $clsISO->print_pre($more_information); die();
		$is_locked = ($oLeasing['is_locked'] ? 0 : 1);
		$more_information['is_locked'] = $is_locked;
		
		$logs = $oLeasing["logs"];
		$logs = $clsISO->to_array_json($logs);
		$logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'user_id' => $profile_id,
			'to_value' => $is_locked,
			'field' => 'is_locked'
		);
		if($clsLeasing->updateOne($leasing_id, array(
			'upd_date' 			=> time(),
			'is_locked' 		=> $is_locked,
			'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'logs' 				=> json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$icon = $clsLeasing->getIcon($leasing_id);
			if($oLeasing['is_locked'] == 0){
				$html = '<a href="javascript:void(0);" class="dropdown-item leasing__menu-lock-'.$leasing_id.'" onclick="$Core.leasing.mark_lock(this, event)" 
				leasing_id="'.$leasing_id.'"><i class="material-icons-outlined">lock_open</i> Mở khóa</a>';
				$icon_lock = "<i data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" title=\"Đã khóa\" class=\"material-icons-outlined fs-small text-danger\">lock</i>";
				if($profile_id == $oLeasing['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsLeasing->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã lock tin cho thuê <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oLeasing['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oLeasing['user_id']);
					$content = sprintf("Quản trị viên đã lock tin cho thuê <strong>%s</strong> của bạn", $oLeasing['title']);
				}
			} else {
				$html = '<a href="javascript:void(0);" class="dropdown-item leasing__menu-lock-'.$leasing_id.'" onclick="$Core.leasing.mark_lock(this, event)" 
				leasing_id="'.$leasing_id.'"><i class="material-icons-outlined">lock</i> Khoá</a>';
				$icon_lock = "";
				if($profile_id == $oLeasing['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsLeasing->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã hủy lock tin cho thuê <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oLeasing['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oLeasing['user_id']);
					$content = sprintf("Quản trị viên đã hủy lock tin cho thuê <strong>%s</strong> của bạn", $oLeasing['title']);
				}
			}
			$clsNotify = new Notify();
			$title = ($is_solded==0) ? 'khoá' : 'mở khoá';
			if($profile_id == $oLeasing['user_id']){
				$txt_user = "Bạn";
			}else{
				$txt_user = "Quản trị viên";
			}
			$content = sprintf("%s đã %s tin cho thuê <strong>%s</strong> của bạn",$txt_user, $title, $oLeasing['title']) ;
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), sprintf('|%s|', $oLeasing['user_id']));
		}
	} else {
		$msg = "_invalid";
	}
	
	
	// Return
	echo json_encode(array(
		'msg' 		=> 	$msg,
		'html' 		=> 	$html,
		'icon_lock' => 	$icon_lock,
		'icon' 		=>	$icon,
	)); die();
}
function default_mark_sold(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsLeasing = new Leasing();
	$clsProperty = new Property();
	$clsNotify = new Notify();
	###
	$html = "";$icon=""; $msg = "_error";
	$leasing_id = Input::post('leasing_id', 0);
	if($leasing_id > 0){
		$oLeasing = $clsLeasing->getOne($leasing_id, "is_solded,more_information,logs,title,user_id");
		$more_information = $oLeasing['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		// $clsISO->print_pre($more_information); die();
		$is_solded = ($oLeasing['is_solded'] ? 0 : 1);
		$more_information['is_solded'] = $is_solded;
		
		$logs = $oLeasing["logs"];
		$logs = $clsISO->to_array_json($logs);
		$logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'user_id' => $profile_id,
			'to_value' => $is_solded,
			'field' => 'is_solded'
		);
		
		if($clsLeasing->updateOne($leasing_id, array(
			'upd_date' => time(),
			'is_solded' => $is_solded,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'logs' 				=> json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$icon = $clsLeasing->getIcon($leasing_id);
			if($is_solded == 0){
				$html = '<a href="javascript:void(0);" class="dropdown-item leasing__menu-sold-'.$leasing_id.'" onclick="$Core.leasing.mark_sold(this,event)" leasing_id="'.$leasing_id.'" is_solded="0"><i class="material-icons-outlined">add_business</i> Báo đã cho thuê</a>';
				if($profile_id == $oLeasing['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsLeasing->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã báo cho thuê tin cho thuê <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oLeasing['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oLeasing['user_id']);
					$content = sprintf("Quản trị viên đã báo cho thuê tin cho thuê <strong>%s</strong> của bạn", $oLeasing['title']);
				}
			} else {
				$html = '<a href="javascript:void(0);" class="dropdown-item leasing__menu-sold-'.$leasing_id.'" onclick="$Core.leasing.mark_sold(this, event)" 
				leasing_id="'.$leasing_id.'" is_solded="1"><i class="material-icons-outlined">storefront</i> Mở cho thuê</a>';
				if($profile_id == $oLeasing['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsLeasing->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã hủy báo cho thuê tin cho thuê <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oLeasing['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oLeasing['user_id']);
					$content = sprintf("Quản trị viên đã hủy báo cho thuê tin cho thuê <strong>%s</strong> của bạn", $oLeasing['title']);
				}
			}
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), $list_user_notify_ids);
		}
	} else {
		$msg = "_invalid";
	}
	// Return
	echo json_encode(array(
		'msg' 	=> $msg,
		'html' 	=> $html,
		'icon'	=>	$icon
	)); die();
}
function default_upload_image(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
    $clsLeasing = new Leasing();
	$clsProperty = new Property();
    #
	$total_images = $total_upload = 0;
	$html = '';  $msg = "_error"; 
    if(!empty($_FILES['images'])){
        $images = $_FILES['images'];
		$total_images = (int) Input::post('total_images', 0);
		// $clsISO->print_pre($images); die();
        if(!empty($images['name']) && array_sum($images['error'])==0){
			$results = array();
            for($i=0; $i<count($images['name']); $i++){
                $img = array();
                $img['name'] = $images['name'][$i];
                $img['type'] = $images['type'][$i];
                $img['tmp_name'] = $images['tmp_name'][$i];
                $img['error'] = $images['error'][$i];
                $img['size'] = $images['size'][$i];
				$total_images+= 1;
                if(is_uploaded_file($img['tmp_name']) && $total_images <= 20){
					$total_upload+= 1;
                    $clsUploadFile = new UploadFile();
                    $up = $clsUploadFile->uploadItem($img,"/dropzone",EXTENSION_FILE_UPLOAD);
                    if(!empty($up) && @file_exists(ROOTPATH.$up)){
						$msg = "_success";
                        // $title = $image["name"];
                        // $mimeType = $image["type"];
                        // $clsGoogleDrive = new GoogleDrive();
                        // $createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$up,GOOGLE_DRIVE_FOLDER_WALL_ID);
                        // $google_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
                        $results[] = $up;
                        // @unlink(ROOTPATH.$up);
                     }
                }
            }
			// Return
			if(!empty($results)){
				foreach($results as $image){
					$html .= '<div class="item bg-lightest">
						<img src="'.$image.'" />
						<input type="hidden" name="images[]" value="'.$image.'" />
						<a class="delete" src="'.$image.'" onClick="$Core.leasing.delete(this, event)">x</a>
					</div>';
				}
			}
        }
    }
    // Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
		'total_upload' => $total_upload,
		'total_images' => $total_images
	)); die();
}
function default_delete_image(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	#
	$src = Input::post('src');
	if(!empty($src) && file_exists(ROOTPATH . $src)){
		@unlink(ROOTPATH . $src);
	}
	// Return
	echo 1; die();
}
function default_upload_video(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	
	$msg = "_error"; $html = "";
	if(isset($_POST['hid']) && $_POST['hid']=='hid'){
		$_token = Input::post('_token');
		$leasing_id = (int) Input::post('leasing_id', 0);
		if(CSRF::check($_token, 'upload-video')){
			if(@is_uploaded_file($_FILES['file_video']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$clsUploadFile->max_file_size = _max_upload_video_size;
				$up = $clsUploadFile->uploadItem($_FILES["file_video"],"/Video",EXTENSION_VIDEO_UPLOAD);
				if(!empty($up) && @file_exists(ROOTPATH.$up)){
					$msg = "_success";
					$title = $_FILES["file_video"]["name"];
					$mimeType = $_FILES["file_video"]["type"];
					$clsGoogleDrive = new GoogleDrive();
					$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$up,GOOGLE_DRIVE_FOLDER_VIDEO_ID);
					// $google_file = $clsISO->genGoogleURL($createdFile->getId(), 'direct_link');
					// $google_file = sprintf('https://drive.google.com/file/d/%s/view', $createdFile->getId());
					$google_file = sprintf('https://drive.google.com/file/d/%s/preview', $createdFile->getId());
					$html.= '<input type="hidden" name="video_url" value="'.$google_file.'" />
					<iframe class="rounded-2 mb-2" src="'.$google_file.'" width="100%" height="300px"></iframe>
					<div class="d-flex justify-content-between">
						<a href="javascript:void(0);" leasing_id="'.$leasing_id.'" class="btn-link text-main fs-11" onClick="$Core.leasing.cancel_video(this, event)"><i class="bx bx-x"></i> Xóa video</a>
						<span class="text-main fs-11">Video của bạn đang được xử lý</span>
					</div>';
					@unlink(ROOTPATH.$up);
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html
	)); die();	
}
function default_cancel_video(){
	global $clsISO,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$_frontIsLoggedin
	,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration;
	$clsLeasing = new Leasing();
	$leasing_id = (int) Input::post('leasing_id', 0);
	if($leasing_id  > 0){
		$more_information = $clsLeasing->getOneField('more_information', $leasing_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['video_url'] = "";
		$clsLeasing->updateOne($leasing_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE) 
		));
	}
	$html = '<div class="we-filedrop h-px-250 rounded-1">
		<div title="Chọn video cần tải nên" onClick="$Core.leasing.select_video(this, event)" 
		toId="leasing__select-video" class="pt-5 text-center cursor-pointer">
			'.ICON_UPLOAD.'
			<p class="mb-0 text-muted">Bấm để chọn video cần tải lên</p>
		</div>
	</div>';
	echo $html; die();
}
function default_check_stock_code(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
	,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$profile_id;
	$clsLeasing = new Leasing();
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$stock_id = $project_id = $building_id = $block_id = $floor = $bedroom_id = $home_direction_id = $DT_TT = 0; $msg = "_invalid";
	$url = "";
	$stock_code = Input::post('stock_code');
	$leasing_id = (int)Input::post('leasing_id',0);
	if(!empty($stock_code)){
		$stock_code = mb_strtoupper($stock_code); 
		$oStock = $clsStock->getByCond("`ms_code`='{$stock_code}'", $clsStock->pkey.",project_id,building_id,block_id,type_id,floor,bedroom_id,home_direction_id,more_information,code");
		if(!empty($oStock)){
			$oneBlock = $clsProperty->getOne($oStock['block_id'],"parent_id");
			if(!empty($oneBlock) && $oneBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE){
				$sop_type = _TYPE_LOWFLOOR;
			}else{
				$sop_type = _TYPE_HIGHLEVEL;
			}
			$condCheck = "";
			if($leasing_id > 0) {
				$condCheck = " AND `".$clsLeasing->pkey."` <> '{$leasing_id}'";
			}
			$checkExist = $clsLeasing->getByCond("`stock_code`='{$stock_code}' AND `user_id`='{$profile_id}'".$condCheck,$clsLeasing->pkey.",stock_id,stock_code");
			if(empty($checkExist)) {
				$more_information = $clsISO->to_array_json($oStock['more_information']);
				$msg = "_valid";
				$stock_id  = $oStock[$clsStock->pkey];
				$project_id  = $oStock['project_id'];
				$block_id  = $oStock['block_id'];
				$building_id  = $oStock['building_id'];
				$floor  = $oStock['floor'];
				$bedroom_id  = $oStock['bedroom_id'];
				$home_direction_id  = $oStock['home_direction_id'];
				$DT_TT  = $more_information['DT_TT'];
				$type_id  = $oStock['type_id'];
				$code  = $oStock['code'];
			}else{
				$msg = "_exist";
				$url = "/cn/edit/".$checkExist[$clsLeasing->pkey]."?return_url=/cn/me/";
				$stock_id  = $oStock[$clsStock->pkey];
				$project_id  = $oStock['project_id'];
				$block_id  = $oStock['block_id'];
				$building_id  = $oStock['building_id'];
				$floor  = $oStock['floor'];
				$bedroom_id  = $oStock['bedroom_id'];
				$home_direction_id  = $oStock['home_direction_id'];
				$DT_TT  = $more_information['DT_TT'];
				$type_id  = $oStock['type_id'];
				$code  = $oStock['code'];
			}
			
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'stock_id' => $stock_id,
		'url' => $url,
		'stock_code' => $stock_code,
		'project_id' => $project_id,
		'block_id' => $block_id,
		'building_id' => $building_id,
		'type_id' => $type_id,
		'floor' => $floor,
		'bedroom_id' => $bedroom_id,
		'home_direction_id' => $home_direction_id,
		'code' => $code,
		'sop_type' => $sop_type,
		'DT_TT' => str_replace(",",".",$DT_TT),
	)); die();
}
function default_open_notes(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	###
	$uid = $clsISO->getUniqid();
	$leasing_id = (int) Input::post('leasing_id', 0);
	$html = '<div class="modal-dialog modal-dialog-centered modal-sm">
		<form class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Xác nhận</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Lý do từ chối</label>
					<textarea class="form-control no-focus" placeholder="Nhập lý do..." name="reason_not_approved" rows="3" cols="255"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" leasing_id="'.$leasing_id.'" tp="confirm_refuse" onClick="$Core.leasing.confirm_refuse(this, event)" class="btn btn-primary">Lưu lại</button>
			</div>
		</form>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_approved(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop,$profile_id;
	###
	$clsLeasing = new Leasing();
	$clsNotify = new Notify();
	$tp = Input::post('tp', "agree");
	$leasing_id = (int) Input::post('leasing_id', 0);
	###
	$msg = "_error";$icon = "";
	$oneLeasing = $clsLeasing->getOne($leasing_id);
	$more_information = $oneLeasing['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	if($tp=='confirm_refuse'){
		$is_online = 2;
		$reason_not_approved = Input::post('reason_not_approved');
		$more_information['reason_not_approved'] = $reason_not_approved;
	} else {
		$is_online = ($tp=='agree') ? 1 : 0;
	}
	
	$logs = $oneLeasing["logs"];
	$logs = $clsISO->to_array_json($logs);
	$logs[$clsISO->getUniqid()] = array(
		'reg_date' => time(),
		'user_id' => $profile_id,
		'to_value' => $is_online,
		'field' => 'is_online'
	);
	if($clsLeasing->updateOne($leasing_id, array(
		'is_online' 		=> $is_online,
		'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'logs' 				=> json_encode($logs, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
		$icon = $clsLeasing->getIcon($leasing_id);
		if($tp=='confirm_refuse'){
			if(!empty($reason_not_approved)){
				$content = sprintf("Quản trị viên đã từ chối tin cho thuê <strong>%s</strong> của bạn với lý do <strong class=\"text-main\">%s</strong>", $oneLeasing['title'], $reason_not_approved);
			} else {
				$content = sprintf("Quản trị viên đã từ chối tin cho thuê <strong>%s</strong> của bạn", $oneLeasing['title']) ;
			}
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), sprintf('|%s|', $oneLeasing['user_id']));
		} else if($tp=='agree'){
			$content = sprintf("Quản trị viên đã phê duyệt tin cho thuê <strong>%s</strong> của bạn", $oneLeasing['title']) ;
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), sprintf('|%s|', $oneLeasing['user_id']));
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'icon' => $icon
	)); die();
}
function default_verified(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$clsLeasing = new Leasing();
	$clsProperty = new Property();
	$clsNotify = new Notify();
	$leasing_id = (int) Input::post('leasing_id', 0);
	###
	$msg = "_error";
	if($leasing_id > 0){
		$is_verified = (int) Input::post('is_verified', 0);
		$oneLeasing = $clsLeasing->getOne($leasing_id);
		$logs = $oneLeasing["logs"];
		$logs = $clsISO->to_array_json($logs);
		$logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'user_id' => $profile_id,
			'to_value' => $is_verified,
			'field' => 'is_verified'
		);
		if($clsLeasing->updateOne($leasing_id, array(
			'is_verified' => $is_verified,
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$title = ($is_verified==1) ? 'xác minh' : 'chưa xác minh';
			$content = sprintf("Quản trị viên đã %s tin cho thuê <strong>%s</strong> của bạn", $title, $oneLeasing['title']) ;
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), sprintf('|%s|', $oneLeasing['user_id']));
		}
	}
	// Return
	echo $msg; die();	
}
function default_get_select_seller(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsLeasing = new Leasing();
	$clsProfile = new Profile();
	
	$results = array();
	$field = "distinct t1.user_id,t2.full_name,t2.first_name,t2.last_name";
	$list_users = $dbconn->getAll("select {$field} from {$clsLeasing->tbl} as `t1` 
		inner join {$clsProfile->tbl} as `t2` on `t1`.`user_id`=`t2`.`profile_id` 
		where `t2`.`is_trash`=0 and t2.`is_active`='1'");
	if(!empty($list_users)){
		foreach($list_users as $key => $val){
			$results[] = array(
				'id' => $val['user_id'],
				'text' => $clsProfile->getFullName($val['user_id'], $val)
			);
		}
	}
	// Return.
	echo json_encode($results); die();
}
function default_addLog(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsLeasingLog = new LeasingLog();
	$clsProfile = new Profile();
	$data = ['result' =>	false];
	$leasing_id = (int)Input::post("leasing_id",0);
	$type = Input::post("type","");
	$content = [];
	if($leasing_id > 0 && $type != ""){
		$log_id = 0;
		$getOneLog = $clsLeasingLog->getAll("leasing_id='{$leasing_id}' LIMIT 0,1",$clsLeasingLog->pkey.",leasing_id,type,content,reg_date,is_trash");		
		if(!empty($getOneLog)){
			$leasingLog = $getOneLog[0];
			$content = $clsISO->to_array_json($leasingLog['content']);
			$log_id = $leasingLog['id'];
		}

//		var_dump($getOneLog);die;
		$content[] = [
			'type'			=>	$type,
			'user_id' 		=> 	$profile_id,
			'user_name' 	=> 	($profile_id > 0)?$clsProfile->getFullName($profile_id,$oneProfile):"Khách",
			'reg_date'		=>	time(),
			'user_ip' 		=> 	$_SERVER['REMOTE_ADDR'],
		];
		$dataLog = [
			'leasing_id' 		=> 	$leasing_id,
			'user_id'		=>	$profile_id,
			'content'		=>	json_encode($content),
			'reg_date' 		=> 	time(),
			'is_trash'		=>	0
		];
		
		if($log_id > 0){
			if($clsLeasingLog->updateOne($log_id, $dataLog)){
				$data = ['result' =>	true];
			}
		}else{
			$dataLog[$clsLeasingLog->pkey] = $clsLeasingLog->getMaxId();

			if($clsLeasingLog->insert($dataLog)){
				$data = ['result' =>	true];
			}
		}
	}	
	
	echo json_encode($data); die();
}
function default_showLog(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsLeasingLog = new LeasingLog();
	$clsProfile = new Profile();
	$data = ['result' =>	false];
	$leasing_id = (int)Input::post("leasing_id",0);
	if($leasing_id > 0){
		$log_id = 0;
		$getOneLog = $clsLeasingLog->getAll("leasing_id='{$leasing_id}' LIMIT 0,1");	
		if(!empty($getOneLog)){
			$leasingLog = $getOneLog[0];
			$content = $clsISO->to_array_json($leasingLog['content']);
			$assign_list['logs'] = $content;
		}		
		$uid = $clsISO->getUniqid();		
		$html = $core->build('_ajax.showLog.tpl');
		$data = [
			'result' 	=>	true,
			'uid'		=>	$uid,
			'html'		=>	$html
		];
	}	
	
	echo json_encode($data); die();
}
function default_load_pop_stock_code(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	
	$clsProperty = new Property();
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='"._PROJECT_DEF_ID."'", $field);
	$smarty->assign('list_blocks', $list_blocks);	
	
	$uid = $clsISO->getUniqid();		
	$html = $core->build('_ajax.loadStockCode.tpl');
	
	
	echo $html; die();
}
function default_loadStockCode(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsProperty = new Property();	
	$clsStock = new Stock();	
	$type = Input::post("field","");
	$action = Input::post("action","");
	$sop_type = (int)Input::post("sop_type",_TYPE_HIGHLEVEL);
	
	$cond_type = "";
	if($sop_type == _TYPE_HIGHLEVEL) {
		$cond_type = "	AND parent_id='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
		$cond_stock_type = " AND stock_type='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
		$cond_sop_type = " AND property_type = '_BLOCK'";
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($sop_type > 0) {
		$cond_type = "	AND parent_id='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
		$cond_stock_type = " AND stock_type='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
		$cond_sop_type = " AND property_type = '_RANGE'";
		$cond_project = " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' OR list_block_type LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}
	
	$html = "<option value=''>Chọn</option>";
	$field = "{$clsProperty->pkey},title";
	if($action == "_SHOW_STOCK_CODE") {
		$project_id = _PROJECT_DEF_ID;
		$block_id = (int)Input::post("block_id", 0);
		$building_id = (int)Input::post("building_id", 0);
		$floor_range = Input::post("floor_range", "");
		$stock_code = Input::post("stock_code", "");
		if($project_id > 0 && $block_id > 0 && $building_id > 0 && $floor_range != "" && $stock_code != "") {
			$checkStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `building_id`='{$building_id}' AND `block_id`='{$block_id}' AND `floor`='{$floor_range}' AND `code`='{$stock_code}'".$cond_stock_type, $clsStock->pkey.",ms_code");
			if(!empty($checkStock)) {
				echo json_encode(array(
					'result'		=>	true,
					'ms_code'		=>	$checkStock['ms_code'],
					'project_id'	=>	$project_id,
					'building_id'	=>	$building_id,
					'floor_range'	=>	$floor_range,
					'stock_code'	=>	$stock_code,
				));die;
			}
		}		
		echo json_encode(array(
			'result'		=>	false,
		));die;
	}else{
		if($type == "project_id"){
			$clsProject = new Project();
			$project_id = (int)Input::post("project_id",0);
//			$clsProject->setDeBug(1);
			$list_project = $clsProject->getAll("`is_trash`=0 AND project_id IN (".implode(",",_PROJECT_OCEAN_CITY).")" . $cond_project, $clsProject->pkey.',title');
//			var_dump($list_project);die;
			$smarty->assign('list_project', $list_project);
			$html = "<option value=''>Dự án</option>";
			foreach($list_project as $k => $v){
				$html .= "<option value='{$v[$clsProject->pkey]}' ".(($project_id == $v[$clsProject->pkey])?"selected":"").">{$v['title']}</option>";
			}
		}else if($type == "block_id"){
			$project_id = (int)Input::post("project_id",0);
			$block_id = (int)Input::post("block_id",0);
			$list_blocks = $clsProperty->getAll("for_id='".$project_id."'".$cond_type, $field);
			$smarty->assign('list_blocks', $list_blocks);
//			var_dump($list_blocks);die;
			$html = "<option value=''>Phân khu</option>";
			foreach($list_blocks as $k => $v){
				$html .= "<option value='{$v['property_id']}' ".(($block_id == $v['property_id'])?"selected":"").">{$v['title']}</option>";
			}
		}else if($type == "building_id"){
			$block_id = (int)Input::post("block_id",0);
			$building_id = (int)Input::post("building_id",0);
			$oneBlock = $clsProperty->getOne($block_id,'parent_id');
			if($oneBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE) {
				$cond_building = " AND property_type='_RANGE'";
			}else{				
				$cond_building = " AND property_type='_BUILDING'";
			}
			$list_buildings = $clsProperty->getAll("for_id='".$block_id."'".$cond_building, $field);
			$smarty->assign('list_buildings', $list_buildings);
			$html = "<option value=''>Toà/Dãy</option>";
			foreach($list_buildings as $k => $v){
				$html .= "<option value='{$v['property_id']}' ".(($building_id == $v['property_id'])?"selected":"").">{$v['title']}</option>";
			}
		}else if($type == "floor_range"){
			$html = "<option value=''>Tầng</option>";
			$building_id = (int)Input::post("building_id",0);
			if(!empty($building_id)){
				$oneBuilding = $clsProperty->getOne($building_id,"more_information");
				$more_information = $clsISO->to_array_json($oneBuilding['more_information']);
				$number_floor = $more_information['number_floor'];
				for($i=1; $i<= $number_floor; $i++){
					if($i==4) {
						$title_floor = '5A';
					} else if($i==7){
						$title_floor = '8A';
					} else if($i==13){
						$title_floor = '12A';
					}  else if($i==14){
						$title_floor = '15A';
					} else {
						$title_floor = $clsISO->parseNumber($i);
					}
					$html .= "<option value='{$title_floor}'>{$title_floor}</option>";
				}
			}
		}
		echo json_encode(array(
			'result'	=>	true,
			'html'		=>	$html,
		));die;
	}
	
}
function default_share(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsLeasing = new Leasing();	
	$smarty->assign('clsLeasing', $clsLeasing);
	###
	$uid = $clsISO->getUniqid();
	$leasing_id = Input::post('leasing_id', 0);
	$smarty->assign('uid', $uid);
	$smarty->assign('leasing_id', $leasing_id);
	
	$oneLeasing = $clsLeasing->getOne($leasing_id,"`title`,`stock_code`,`more_information`");	
	$smarty->assign('oneLeasing', $oneLeasing);
	
	$more_information = $oneLeasing['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$image_share = URL_IMAGES."/ocean-city-ha-noi.jpg";
	if(isset($more_information['images']) && !empty($more_information['images'])){
		$image_share = PCMS_URL.reset($more_information['images']);
	}
	$title_share = $more_information['title_page'];
	$description_share = $more_information['description_page'];
	$smarty->assign('image_share', $image_share);
	$smarty->assign('title_share', $title_share);
	$smarty->assign('description_share', $description_share);
	// Return
	$html = $core->build('_ajax.openShare.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
        'leasing_id' => $leasing_id,
	)); die();
}
function default_load_sidebar_search(){ 
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$clsSop = new Sop();
	$smarty->assign('clsSop', $clsSop);
	$clsLeasing = new Leasing();
	$smarty->assign('clsLeasing', $clsLeasing);
	$area_min = Input::post("area_min",0);
	$area_max = Input::post("area_max",0);

	$keyword = Input::post('keyword');
	$type_id = Input::post('type_id',_TYPE_HIGHLEVEL);
	$block_id = Input::post('block_id', 0);
	$building_ids = Input::post('building_ids');
	$bedroom_ids = Input::post('bedroom_ids');
	$home_direction_ids = Input::post('home_direction_ids');
	$floor_range = Input::post('floor_range');
	$price_min = Input::post("price_min",0);
	$price_max = Input::post("price_max",0);
	$price_min = !empty($price_min) ? (int) $clsISO->processSmartNumber($price_min) : 0;
	$price_max = !empty($price_max) ? (int) $clsISO->processSmartNumber($price_max) : 0;
	###
	$cond = "`is_trash`=0 and `is_online`='1'";
	$lstBedroom = $lstBathRoom = $lstBalcony = [];
	if($type_id == _TYPE_HIGHLEVEL) {
		$cond.= " and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and JSON_EXTRACT(`more_information`,\"$.sop_type\") = '".$type_id."'";
	}else if($type_id == _TYPE_LOWFLOOR) {
		$cond.= " and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and JSON_EXTRACT(`more_information`,\"$.sop_type\") = '".$type_id."'";
		$lstBedroom = $lstBathRoom = $lstBalcony = [
			"1"	=>	"1",
			"2"	=>	"2",
			"3"	=>	"3",
			"4"	=>	"4",
			"5"	=>	"5",
			"6"	=>	"6+",
		];
	}
	if(!empty($keyword)) $cond.=" and `stock_code` like '%{$keyword}%'";
	#- Filter Block
	if($block_id > 0){ 
		$cond.=" and (`block_id`='{$block_id}')";
	}
	#- Filter Building
	if(!empty($building_ids)) {
		$cond.= " and (`building_id` in (".implode(',', $building_ids)."))";
	}
	#- Filter Bedroom
	if(!empty($bedroom_ids)) {
		$cond.= " and (`bedroom_id` in (".implode(',', $bedroom_ids)."))";
	}
	#- Filter Direction
	if(!empty($home_direction_ids)) {
		$cond.= " and (`home_direction_id` in (".implode(',', $home_direction_ids)."))";
	}
	#- Filter Floor
	$list_floors = array();
	if(!empty($floor_range)){
		foreach($floor_range as $floor){
			$tmp = explode('-', $floor);
			for($i=$tmp[0]; $i<=$tmp[1]; $i++){
				if($i==4) {
					$list_floors[] = '5A';
					$list_floors[] = '05A';
				} else if($i==7){
					$list_floors[] = '8A';
					$list_floors[] = '08A';
				} else if($i==13){
					$list_floors[] = '12A';
				}  else if($i==14){
					$list_floors[] = '15A';
				}
				$list_floors[] = $clsISO->parseNumber($i);
			}
		}
		if(!empty($list_floors)){
			$cond.= " and (`floor` in ('".implode('\',\'', $list_floors)."'))";
		}
	}
	###
	$lstPriceRangeLeasing = $clsProperty->getCacheItems("_PRICE_RANGE_LEASING");
	foreach($lstPriceRangeLeasing as $key => $val) {			
		$more_information_leasing = $clsISO->to_array_json($val['more_information']);
		$MIN = (!empty($more_information_leasing['min'])) ? $more_information_leasing['min'] : 0;
		$MAX =  (!empty($more_information_leasing['max'])) ? $more_information_leasing['max'] : 0;
		$total_item = $clsLeasing->countItem($cond." AND `price` BETWEEN {$MIN} AND {$MAX}");
		if($total_item == 0){
			unset($lstPriceRangeLeasing[$key]);
			continue;
		}
		$lstPriceRangeLeasing[$key]['total'] = $total_item;
		$lstPriceRangeLeasing[$key]['min'] = $MIN;
		$lstPriceRangeLeasing[$key]['max'] = $MAX;	
		unset($$lstPriceRangeLeasing);
	}
	$smarty->assign('lstPriceRangeLeasing', $lstPriceRangeLeasing);

	$lstAreaRange = $clsProperty->getCacheItems("_AREA_RANGE");
	foreach($lstAreaRange as $key => $val) {
		$more_information_area = $clsISO->to_array_json($val['more_information']);
		$MIN = (!empty($more_information_area['min'])) ? $more_information_area['min'] : 0;
		$MAX =  (!empty($more_information_area['max'])) ? $more_information_area['max'] : 0;
		$total_item = $clsLeasing->countItem($cond." AND CAST( JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2) ) BETWEEN {$MIN} AND {$MAX}");	
		if($total_item == 0){
			unset($lstAreaRange[$key]);
			continue;
		}
		$lstAreaRange[$key]['total'] = $total_item;
		$lstAreaRange[$key]['min'] = (!empty($more_information_area['min'])) ? $more_information_area['min'] : 0;
		$lstAreaRange[$key]['max'] = (!empty($more_information_area['max'])) ? $more_information_area['max'] : 0;	
		unset($more_information_area);
	}

	$smarty->assign('price_min', $price_min);
	$smarty->assign('price_max', $price_max);
	$smarty->assign('area_min', $area_min);
	$smarty->assign('area_max', $area_max);
	$smarty->assign('lstAreaRange', $lstAreaRange);
	
	$smarty->assign('lstBedroom', $lstBedroom);
	$smarty->assign('lstBathRoom', $lstBathRoom);
	$smarty->assign('lstBalcony', $lstBalcony);
	// Return
	$html = $core->build('_ajax.sidebar_search.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}

function default_loadHideCode(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsProperty = new Property();	
	$smarty->assign('clsProperty', $clsProperty);
	###
	$sop_type = Input::post("sop_type", _TYPE_HIGHLEVEL);
	if($sop_type == _TYPE_HIGHLEVEL) {
		$lst_hideCode = $clsProperty->getCacheItems('_HIDECODE');
		$hide_code_default = _STOCK_HIDECODE_NO_ID;
	} else {
		$lst_hideCode = $clsProperty->getCacheItems('_HIDECODELOWFLOOR');
		$hide_code_default = _STOCK_HIDECODE_LOWFLOOR_NO_ID;
	}
	$arr_data = [
		'result' => false,
	];
	if(!empty($lst_hideCode)) {
		$html = "";
		foreach ($lst_hideCode as $k => $val) {
			$html .= '<label class="we-radio" for="rdo_'.$val['property_id'].'">
						<input type="radio" id="rdo_'.$val['property_id'].'" name="hide_code" ' . (($hide_code_default == $val['property_id']) ? "checked" : "") . ' value="'.$val['property_id'].'">
						<span>'.$val['title'].'</span>
					</label>';
		}	
		$arr_data = [
			'result' => true,
			'html' => $html,
		];
	}
	
	// Return
	echo json_encode($arr_data); die();
}