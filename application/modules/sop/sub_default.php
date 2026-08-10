<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$cmd;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$cmd = Input::get('cmd', "");
	$current_page = $_SERVER['REQUEST_URI'];
	$page = (int)Input::get("page",1);
	$assign_list['page'] = $page;
	$smarty->assign("current_page",$current_page);
	##
	$type_list = Input::get('type_list', "publish");
	$smarty->assign('type_list', $type_list);
	##
	$list_preloaders = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý dịch vụ chuyển nhượng - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = ' Hàng nghìn giao dịch uy tín trên hệ thống chuyển nhượng My OCean City - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_list_sop(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	### 
	$permiss_view_sop_source = $clsISO->checkPermission('view_sop_source');
	$smarty->assign('permiss_view_sop_source', $permiss_view_sop_source);
	###
	$www = (int) Input::post('www', 1920);
	$keyword = Input::post('keyword');
	$user_id = (int) Input::post('user_id', 0);
	$sop_type = (int) Input::post('sop_type', _SOP_TYPE_HIGHLEVEL);
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$source_id = (int) Input::post('source_id', 0);
	$building_ids = Input::post('building_ids');
	$need_ids = Input::post('need_ids');
	$bedroom_ids = Input::post('bedroom_ids');
	$home_direction_ids = Input::post('home_direction_ids');
	$floor_range = Input::post('floor_range');
	$price_min = Input::post('price_min', 0);
	$price_max = Input::post('price_max', 0);
	$price_min = !empty($price_min) ? (int) $clsISO->processSmartNumber($price_min) : 0;
	$price_max = !empty($price_max) ? (int) $clsISO->processSmartNumber($price_max) : 0;
	$area_min = Input::post('area_min', 0);
	$area_max = Input::post('area_max', 0);
	$area_min = !empty($area_min) ? (int) $clsISO->processSmartNumber($area_min) : 0;
	$area_max = !empty($area_max) ? (int) $clsISO->processSmartNumber($area_max) : 0;
	$type_list = Input::post('type_list', 'publish');
	$sort_by = Input::post('sort_by', ($type_list=='publish'?"date_asc":"reg_date"));
	$return_url = Input::post('return_url');
	$smarty->assign('sop_type', $sop_type);
	$smarty->assign('return_url', $return_url);
	###
	$cond = "1=1";
	$url = $ret_url = '/chuyen-nhuong.html';
	if(!empty($keyword)) $cond.=" and (`stock_code` like '%{$keyword}%' 
		OR title LIKE '%{$keyword}%' 
		OR slug LIKE '%{$keyword}%'
	)";
	if($user_id > 0) {
		$cond.= " and `user_id`='{$user_id}'";
		$url .= "&user=".$user_id;
	}
	#- Filter sop type
	if($sop_type > 0){
		$cond.= " AND JSON_EXTRACT(`more_information`,\"$.sop_type\")='{$sop_type}'";
		$url .= "&sop_type=".$sop_type;
	}
	#- Filter project
	if($project_id > 0){ 
		$cond.=" AND `project_id`='{$project_id}'";
		$url .= "&project=".$project_id;
	}
	#- Filter Block
	if($block_id > 0){ 
		$cond.=" AND (`block_id`='{$block_id}')";
		$url .= "&block=".$block_id;
	}
	#- Filter source_id
	if($source_id > 0){ 
		$cond.=" AND (`agency_id`='{$source_id}')";
		$url .= "&agency_id=".$source_id;
	}
	#- Filter Status
	if(!empty($need_ids)){
		$cond.= " AND (`need_id` in (".implode(',', $need_ids)."))";
	}
	#- Filter Building
	if(!empty($building_ids)) {
		$cond.= " AND (`building_id` in (".implode(',', $building_ids)."))";
		$url .= "&building=".implode(',', $building_ids);
	}
	#- Filter Bedroom
	if(!empty($bedroom_ids)) {
		$cond.= " AND (`bedroom_id` in (".implode(',', $bedroom_ids)."))";
		$url .= "&bedroom=".implode(',', $bedroom_ids);
	}
	#- Filter Direction
	if(!empty($home_direction_ids)) {
		$cond.= " AND (`home_direction_id` in (".implode(',', $home_direction_ids)."))";
		$url .= "&direction=".implode(',', $home_direction_ids);
	}
	#- Filter Area
	if($area_min > 0 && $area_max == 0){
		$cond.= " AND CAST( JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2) )>='".$area_min."'";
		$url .= "&area_min=".$area_min;
	} else if($area_min == 0 && $area_max > 0){
		$cond.= " AND CAST( JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2) )<='".$area_max."'";
		$url .= "&area_min=".$area_min."&area_max=".$area_max;
	} else if($area_min > 0 && $area_max > 0){
		$cond.= " AND CAST(JSON_EXTRACT(`more_information`,\"$.DT_TT\") AS DECIMAL(10, 2)) 
		BETWEEN '".$area_min."' AND '".$area_max."'";
		$url .= "&area_min=".$area_min."&area_max=".$area_max;
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
	// $cond.= " and (`DT_TT` between '{$price_min}' and '{$price_max}')";
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
			$url .= "&floor_range=".implode(',', $list_floors);
		}
	}
	$current_page = (int) Input::post('page',1);
	if($current_page > 1) {
		$url .= "&page=".$current_page;
	} 
	$per_page = (int) Input::post('per_page', 50);
	$total_record = $clsSop->countItem($cond);	
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$sop_field = "{$clsSop->pkey},`title`,`stock_code`,`price`,`more_information`,`stock_id`,`bedroom_id`
	,`home_direction_id`,`is_verified`,`floor`,`project_id`,`block_id`,`building_id`,`code`,`is_locked`,
	`is_online`,`is_solded`,`need_id`,`upd_date`,`reg_date`,`price_owner`,`price_m2`,`agency_id`";
	###
	$order_by = " order by `is_solded` ASC,`upd_date` DESC";
	if($sort_by=='date_asc'){
		$order_by = " order by `is_solded` ASC, `upd_date` ASC";
	} else if($sort_by=='date_desc'){
		$order_by = " order by `is_solded` ASC, `upd_date` DESC";
	} else if($sort_by=='price_asc'){
		$order_by = " order by `is_solded` ASC, `price` ASC";
	} else if($sort_by=='price_desc'){
		$order_by = " order by `is_solded` ASC, `price` DESC";
	} else if($sort_by=='price_m2_asc'){
		$order_by = " order by `is_solded` ASC, `price_m2` ASC";
	} else if($sort_by=='price_m2_desc'){
		$order_by = " order by `is_solded` ASC, `price_m2` DESC";
	}
	// $dbconn->debug = true;
	$list_sop = $clsSop->getAll($cond.$order_by.$limitCond,$sop_field);
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
	$lstRange = $clsProperty->getArraySearchByKey("_RANGE");
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstHomeDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	if(!empty($list_sop)){
		$arr_status_cached = $arr_property_cached = array();
		foreach($list_sop as $key => $val){
			$need_id = $val['need_id'];
			$sop_type = $val['sop_type'];
			$agency_id = $val['agency_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$type_villa_id = $core->get_field($more_information, 'type_villa_id', 0);
			// $more_information['hide_code'] = _STOCK_HIDECODE_FLOOR_ID;
			//$clsSop->updateOne($val[$clsSop->pkey], array(
			//	'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			//));
			$list_sop[$key]['more_information'] = $more_information;
			$block_idd = $val['block_id'];
			$building_id = $val['building_id'];
			$bedroom_id = $val['bedroom_id'];
			$home_direction_id = $val['home_direction_id'];		
			###
			$list_sop[$key]['label_owner'] = "";
			if(isset($more_information['is_owner']) && $more_information['is_owner'] == 1) {
				$list_sop[$key]['label_owner']= '<span class="awe__sop-badge text-white bg-success">Chính chủ</span>';
			}
			if($need_id > 0 && !isset($arr_status_cached[$need_id])){
				$oProperty = $clsProperty->getOne($need_id, "title,bgcolor,textcolor");
				$arr_status_cached[$need_id] = sprintf(
					'<span class="badge" style="background:%s; color:%s">%s</span>', 
					$oProperty['bgcolor'], $oProperty['textcolor'], $oProperty['title']
				);
			}
			$list_sop[$key]['label_status'] = "";
			if($need_id > 0 && isset($arr_status_cached[$need_id])){
				$list_sop[$key]['label_status'] = $arr_status_cached[$need_id];
			}
			$images = isset($more_information['images']) ? $more_information['images'] : array();
			if($deviceType == 'phone'){
				if(!empty($images)){
					$has_img = 1;
					$image = reset($images);
				} else {
					$has_img = 0;
					$image = URL_IMAGES . '/no-image.jpg';
				}
				$list_sop[$key]['image'] = $image;
				$list_sop[$key]['has_img'] = $has_img;
			} else {
				$list_sop[$key]['images'] = $images;
			}			
			###
			$arr_property_cached[$block_idd] = $lstBlock[$block_idd]['title'];
			if($sop_type == _SOP_TYPE_HIGHLEVEL) {			
				$arr_property_cached[$building_id] = $lstBuilding[$building_id]['title'];
			}else{				
				$arr_property_cached[$building_id] = $lstRange[$building_id]['title'];
			}
			if($agency_id > 0 && !isset($arr_property_cached[$agency_id])){
				$arr_property_cached[$agency_id] = $clsProperty->getTitle($agency_id);
			}
			if($type_villa_id > 0 && !isset($arr_property_cached[$type_villa_id])){
				$arr_property_cached[$type_villa_id] = $clsProperty->getTitle($type_villa_id);
			}
			$arr_property_cached[$bedroom_id] = $lstBedroom[$bedroom_id]['title'];
			$arr_property_cached[$home_direction_id] = $lstHomeDirection[$home_direction_id]['title'];
			$list_sop[$key]['block_name'] = $arr_property_cached[$block_idd];
			$list_sop[$key]['type_name'] = $arr_property_cached[$type_villa_id];
			$list_sop[$key]['building_name'] = $arr_property_cached[$building_id];
			$list_sop[$key]['bedroom_name'] = $arr_property_cached[$bedroom_id];
			$list_sop[$key]['home_direction'] = $arr_property_cached[$home_direction_id];
			$list_sop[$key]['agency_name'] = $arr_property_cached[$agency_id];
			$list_sop[$key]['is_executable'] = (int) $core->get_field($more_information,'is_executable',0);
			###
			$price_m2 = 0;
			$DT_TT = $more_information['DT_TT'];
			$price = $clsISO->processSmartNumber($val['price']);
			if($price > 0 && !empty($DT_TT)){
				$price_m2 = $price / $clsISO->convertToNumber($DT_TT);
			}
			// $price = $val['price'];
			// $price_m2 = $clsISO->processSmartNumber($price)/$val['DT_TT'];
			$list_sop[$key]['price_m2'] = $price_m2;
			#location
			$location = [];
			$stock_code = $clsSop->getCode($list_sop[$key]);
			if(!empty($stock_code)){
				$location[] = $stock_code;
			}
			if(!empty($arr_property_cached[$building_id])) {
				$location[] = $arr_property_cached[$building_id];
			}
			if(!empty($arr_property_cached[$block_idd])) {
				$location[] = $arr_property_cached[$block_idd];
			}
			$list_sop[$key]['location'] = implode(", ",$location);
		}
	}
	//	$clsISO->print_pre($list_sop);die;
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('ret_url', $ret_url);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('list_sop', $list_sop);
	$smarty->assign('type_list', $type_list);
	$smarty->assign('deviceType', $deviceType);
	// Return
	$html = $core->build('_ajax.sop.tpl');
	echo json_encode(array(
		'url' => $url,
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	), JSON_UNESCAPED_UNICODE); die();
}	
function default_load_project(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProject = new Project();
	##
	$tp = Input::post('tp', 'option');
	$sop_type = Input::post("sop_type",_SOP_TYPE_HIGHLEVEL);
	$sql_project = "`is_trash`=0";
	if($sop_type == _SOP_TYPE_HIGHLEVEL) {
		$sql_project.= " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			OR `list_block_type` LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($sop_type > 0) {
		$sql_project.= " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
			OR `list_block_type` LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}		
	$field = "{$clsProject->pkey},`title`,`more_information`";
	$list_project = $clsProject->getAll("{$sql_project} AND `project_id` IN (".implode(",",_PROJECT_OCEAN_CITY).")", $field);
	//	$clsISO->print_pre($list_project);die;
	$html = "";
	if(!empty($list_project)){
		if($tp == 'radio'){
			foreach($list_project as $key => $val){
				$html.= '<label class="we-radio we-radio-search" for="rdo_md_'.$val[$clsProject->pkey].'">
					<input type="radio" onChange="$Core.sop.select_project(this, event)" class="js__option-project" toId="js__dropdown-block" tp="radio" id="rdo_md_'.$val[$clsProject->pkey].'" name="project_id" 
					value="'.$val[$clsProject->pkey].'" >
					<span class="text-dark">'.$val['title'].'</span>
				</label>';
			}
		} else {
			foreach($list_project as $key => $val){
				$html .= '<div class="dropdown-item">
					<div class="form-check cursor-pointer">
						<input type="radio" name="project_id" 
						class="form-check-input js__option-project" id="rdo_'.$val[$clsProject->pkey].'" 
						value="'.$val[$clsProject->pkey].'" title="'.$val['title'].'" onchange="$Core.sop.select_project(this,event)" tp="option" toId="js__dropdown-block-list">
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
	$sop_type = (int) Input::post('sop_type', _SOP_TYPE_HIGHLEVEL);
	if($sop_type == _SOP_TYPE_HIGHLEVEL) {
		$cond = " AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
	}else if($sop_type > 0) {
		$cond = " AND `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
	}
	$project_id = (int) Input::post('project_id', 0);
	$field = "{$clsProperty->pkey},`title`";
	$list_blocks = $clsProperty->getAll("`for_id`='{$project_id}'".$cond, $field);
	##
	$html = '';
	if(!empty($list_blocks)){
		if($tp == 'radio'){
			foreach($list_blocks as $key => $val){
				$html.= '<label class="we-radio we-radio-search" for="rdo_'.$val[$clsProperty->pkey].'">
					<input type="radio" onChange="$Core.sop.select_block(this, event)" class="js__option-block" toId="js__dropdown-building" tp="radio" id="rdo_'.$val[$clsProperty->pkey].'" name="block_id" value="'.$val[$clsProperty->pkey].'" >
					<span class="text-dark">'.$val['title'].'</span>
				</label>';
			}
		} else {
			foreach($list_blocks as $key => $val){
				$html.= '<div class="dropdown-item">
					<div class="form-check cursor-pointer">
						<input type="radio" name="block_id" 
						class="form-check-input js__option-block" id="rdo_'.$val[$clsProperty->pkey].'" value="'.$val[$clsProperty->pkey].'" 
						title="'.$val['title'].'" onchange="$Core.sop.select_block(this,event)" tp="option" toId="js__dropdown-building-list">
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
	$sop_type = (int) Input::post('sop_type', _SOP_TYPE_HIGHLEVEL);
	if($sop_type == _SOP_TYPE_HIGHLEVEL) {
		$cond_type = " AND `property_type`='_BUILDING'";
	}else if($sop_type > 0) {
		$cond_type = " AND `property_type`='_RANGE'";
	}
	$html = "";
	$tp = Input::post('tp', 'option');
	$block_id = (int)Input::post('block_id',0);
	$field = "{$clsProperty->pkey},title";	
	$list_buildings = $clsProperty->getAllCache("`for_id`='".$block_id."'".$cond_type, $field);
	if(!empty($list_buildings)) {
		if($tp == 'radio'){
			foreach($list_buildings as $key => $val){
				$html.= '<label class="form-check form-check-dark w-50 mb-2" for="chk_'.$val[$clsProperty->pkey].'"> 
					<input class="form-check-input js__option-building" type="checkbox" name="building_id[]" value="'.$val[$clsProperty->pkey].'" id="chk_'.$val[$clsProperty->pkey].'">
					<span class="form-check-label text-dark">'.$val['title'].'</span>
				</label>';
			}
		} else {
			$gid = $clsISO->getUniqid();
			foreach ($list_buildings as $key => $value) {
				$html .= '<div class="dropdown-item">
					<div class="form-check mb-0 cursor-pointer">
						<input gId="'.$gid.'" class="form-check-input chk_building js__option-building" onChange="$Core.sop.select_checkbox(this,event)" 
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
function default_delete(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsSopLog = new SopLog();
	$clsProperty = new Property();
	###
	$icon = ""; $msg = "_error";
	$sop_id = Input::post('sop_id', 0);
	if($sop_id > 0){
		$oneSop = $clsSop->getOne($sop_id, "more_information");
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);	
		$is_deleted = (int) $core->get_field($more_information, 'is_deleted', 0);
		// $clsISO->print_pre($is_deleted); die();
		$more_information['is_deleted'] = ($is_deleted == 1 ? 0 : 1);
		if($clsSop->updateOne($sop_id, array(
			'upd_date' => time(),
			'is_trash' => ($is_deleted ? 1 : 0),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$icon = $clsSop->getIcon($sop_id);
			if($is_deleted == 1){
				$html = '<a class="dropdown-item sop__menu-delete-'.$sop_id.'" onClick="$Core.sop.delete(this, event)" sop_id="'.$sop_id.'" href="javascript:void(0);"><i class="material-icons-outlined">settings_backup_restore</i> Khôi phục</a>';
			} else {
				$html = '<a class="dropdown-item sop__menu-delete-'.$sop_id.'" onClick="$Core.sop.delete(this, event)" sop_id="'.$sop_id.'" href="javascript:void(0);"><i class="material-icons-outlined">delete</i> Xoá</a>';
			}
		}
	} else {
		$msg = "_invalid";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
		'icon' => $icon,
		'sop_id' => $sop_id
	)); die();
}
function default_mark_hot(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsProperty = new Property();
	$clsNotifyMOC = new NotifyMOC();
	
	$msg = "_error";
	$sop_id = (int) Input::post('sop_id', 0);
	$is_hot = (int) Input::post('is_hot', 0);
	if($sop_id > 0){
		$oSop = $clsSop->getOne($sop_id, "`more_information`,`user_id`,`title`");
		$more_information = $oSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['is_hot'] = $is_hot;
		if($clsSop->updateOne($sop_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_mark_lock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsNotifyMOC = new NotifyMOC();
	$clsProperty = new Property();
	###
	$html = ""; $icon = ""; $msg = "_error";
	$sop_id = Input::post('sop_id', 0);
	if($sop_id > 0){
		$oSop = $clsSop->getOne($sop_id, "`is_locked`,`more_information`,`user_id`,`title`");
		$more_information = $oSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$is_locked = ($oSop['is_locked'] == 1) ? 0 : 1;
		$more_information['is_locked'] = $is_locked;
		// $clsISO->print_pre($oSop); die();
		if($clsSop->updateOne($sop_id, array(
			'upd_date' => time(),
			'is_locked' => $is_locked,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			if($oSop['is_locked'] == 0){
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-lock-'.$sop_id.'" sop_id="'.$sop_id.'" onclick="$Core.sop.mark_lock(this, event)"><i class="material-icons-outlined">lock_open</i> Mở khóa</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã lock tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã lock tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			} else {
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-lock-'.$sop_id.'" sop_id="'.$sop_id.'" onclick="$Core.sop.mark_lock(this, event)"><i class="material-icons-outlined">lock</i> Khoá</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã hủy lock tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã hủy lock tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			}
			$icon = $clsSop->getIcon($sop_id);
			$clsNotifyMOC->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), $list_user_notify_ids);
		}
	} else {
		$msg = "_invalid";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'icon' => $icon,
		'html' => $html,
		'is_locked' => $is_locked
	)); die();
}
function default_mark_sold(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
		,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$oneProfile;
	$clsSop = new Sop();
	$clsNotifyMOC = new NotifyMOC();
	$clsProperty = new Property();
	###
	$html = ""; $msg = "_error";
	$sop_id = Input::post('sop_id', 0);
	if($sop_id > 0){
		$oSop = $clsSop->getOne($sop_id, "`is_solded`,`more_information`,`user_id`,`title`");
		$more_information = $oSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$is_solded = ($oSop['is_solded']==1 ? 0 : 1);
		$more_information['is_solded'] = $is_solded;
		$logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'to_value' => $is_solded,
			'field' => 'is_solded'
		);
		if($clsSop->updateOne($sop_id, array(
			'upd_date' => time(),
			'is_locked' => 0, // Unlock
			'is_solded' => $is_solded,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			if($oSop['is_solded'] == 0){
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-sold-'.$sop_id.'" sop_id="'.$sop_id.'" onclick="$Core.sop.mark_sold(this,event)"><i class="material-icons-outlined">add_business</i> Mở bán</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã báo bán tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã báo bán tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			} else {
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-sold-'.$sop_id.'" sop_id="'.$sop_id.'" onclick="$Core.sop.mark_sold(this, event)"><i class="material-icons-outlined">storefront</i> Báo bán</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã hủy bán bán tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã hủy báo bán tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			}
			$icon = $clsSop->getIcon($sop_id);
			$clsNotifyMOC->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), $list_user_notify_ids);
			
		}
	} else {
		$msg = "_invalid";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'icon' => $icon,
		'html' => $html,
		'is_solded' => $is_solded
	)); die();
}
function default_open_notes(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	###
	$uid = $clsISO->getUniqid();
	$sop_id = (int) Input::post('sop_id', 0);
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
				<button type="button" sop_id="'.$sop_id.'" tp="confirm_refuse" onClick="$Core.sop.confirm_refuse(this, event)" class="btn btn-primary">Lưu lại</button>
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
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$clsSop = new Sop();
	$clsNotifyMOC = new NotifyMOC();
	$tp = Input::post('tp', "agree");
	$sop_id = (int) Input::post('sop_id', 0);
	###
	$icon = ""; $msg = "_error";
	$oneSop = $clsSop->getOne($sop_id);
	$more_information = $oneSop['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	if($tp=='confirm_refuse'){
		$is_online = 2;
		$reason_not_approved = Input::post('reason_not_approved');
		$more_information['reason_not_approved'] = $reason_not_approved;
	} else {
		$is_online = ($tp=='agree') ? 1 : 0;
	}
	if($clsSop->updateOne($sop_id, array(
		'is_online' => $is_online,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
		if($tp=='confirm_refuse'){
			if(!empty($reason_not_approved)){
				$content = sprintf("Quản trị viên đã từ chối tin chuyển nhượng <strong>%s</strong> của bạn với lý do <strong class=\"text-main\">%s</strong>", $oneSop['title'], $reason_not_approved);
			} else {
				$content = sprintf("Quản trị viên đã từ chối tin chuyển nhượng <strong>%s</strong> của bạn", $oneSop['title']);
			}
		} else if($tp=='agree'){
			$content = sprintf("Quản trị viên đã phê duyệt tin chuyển nhượng <strong>%s</strong> của bạn", $oneSop['title']);
		}
		$icon = $clsSop->getIcon($sop_id);
		$clsNotifyMOC->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), sprintf('|%s|', $oneSop['user_id']));
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
	$clsSop = new Sop();
	$clsSopLog = new SopLog();
	$clsNotifyMOC = new NotifyMOC();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$sop_id = (int) Input::post('sop_id', 0);
	###
	if($sop_id > 0){
		$is_verified = (int) Input::post('is_verified', 0);
		$oneSop = $clsSop->getOne($sop_id, "`title`,`user_id`,`more_information`");
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['is_verified'] = $is_verified;
		if($clsSop->updateOne($sop_id, array(
			'is_verified' => $is_verified,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$clsSopLog->insert(array(
				'type' => '_update',
				'sop_id' => $sop_id,
				'reg_date' => time(),
				'user_id' => $profile_id,
				'content' => json_encode(array(
					'from_site' => '_front',
					'field' => 'is_verified',
					'to_value' => $is_verified
				), JSON_UNESCAPED_UNICODE)
			));
			$title = ($is_verified==1) ? 'xác minh' : 'chưa xác minh';
			$content = sprintf("Quản trị viên đã %s tin chuyển nhượng <strong>%s</strong> của bạn", $title, $oneSop['title']) ;
			$clsNotifyMOC->insertNotify($clsSop->tbl,$clsSop->pkey, $sop_id,$content,time(),sprintf('|%s|', $oneSop['user_id']));
		}
	}
	// Return
	echo $msg; die();	
}
function default_get_select_seller(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsSop = new Sop();
	$clsProfile = new Profile();
	###
	$results = array();
	$field = "distinct t1.user_id,t2.full_name,t2.first_name,t2.last_name";
	$list_users = $dbconn->getAll("select {$field} from {$clsSop->tbl} as `t1` 
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
function default_showLog(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsSopLog = new SopLog();
	$clsProfile = new Profile();
	$data = ['result' =>	false];
	$sop_id = (int)Input::post("sop_id",0);
	if($sop_id > 0){
		$log_id = 0;
		$getOneLog = $clsSopLog->getAll("sop_id='{$sop_id}' LIMIT 0,1");	
		if(!empty($getOneLog)){
			$sopLog = $getOneLog[0];
			$content = $clsISO->to_array_json($sopLog['content']);
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
function default_share(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();	
	$smarty->assign('clsSop', $clsSop);
	###
	$uid = $clsISO->getUniqid();
	$sop_id = Input::post('sop_id', 0);
	$field = "`title`,`stock_code`,`more_information`";
	$oneSop = $clsSop->getOne($sop_id, $field);
	$more_information = $oneSop['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	// $oneSop['stock_code'] = $clsSop->getCode($oneSop);
	$image_share = URL_IMAGES."/ocean-city-ha-noi.jpg";
	if(isset($more_information['images']) && !empty($more_information['images'])){
		$image_share = PCMS_URL.reset($more_information['images']);
	}
	$title_share = $more_information['title_share'];
	$link_share = PCMS_URL . $clsSop->getLink($sop_id, $more_information['stock_code']);
	$more_information['image_share'] = $image_share;
	$more_information['link_share'] = $link_share;
	###
	$smarty->assign('uid', $uid);
	$smarty->assign('sop_id', $sop_id);
	$smarty->assign('more_information', $more_information);
	// Return
	$html = $core->build('_ajax.share.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
        'sop_id' => $sop_id,
		'link_share' => $link_share,
		'title_share' => $title_share
	)); die();
}
function default_do_crawl(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	###
	$msg = "_error";
	$total_inserted = $total_updated = 0;
	if($tmp = $clsSop->crawl()){
		$msg = $tmp['msg'];
		$total_inserted = $tmp['total_inserted'];
		$total_updated = $tmp['total_updated'];
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'total_inserted' => $total_inserted,
		'total_updated' => $total_updated
	)); die();
}
function default_do_tt_crawl(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	###
	$msg = "_error";
	$total_inserted = $total_updated = 0;
	if($tmp = $clsSop->crawl_tt()){
		$msg = $tmp['msg'];
		$total_inserted = $tmp['total_inserted'];
		$total_updated = $tmp['total_updated'];
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'total_inserted' => $total_inserted,
		'total_updated' => $total_updated
	)); die();
}
function default_gen_content(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	###
	/* $tmp = $clsSop->getAll("1=1");
	foreach($tmp as $key => $val){
		$more_information = $val['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$html_content = $clsSop->gen_content($val[$clsSop->pkey], $val);
		$more_information['content'] = $html_content;
		$clsSop->updateOne($val[$clsSop->pkey], array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
	}
	die(1); */
	$msg = "_error";
	$sop_id = (int) Input::post('sop_id', 0);
	$oneSop = $clsSop->getOne($sop_id);
	// $clsISO->print_pre($oneSop); die();
	$more_information = $oneSop['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	
	$html_content = $clsSop->gen_content($sop_id, $oneSop);
	// $clsISO->print_pre($html_content); die();
	$more_information['content'] = $html_content;
	// Update to CSDL
	if($clsSop->updateOne($sop_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_load_edit_inline_field(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsProperty = new Property();
	###
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action','_open');
	if($p_action == '_save'){
		$p_value = Input::post('p_value');
		if($p_field=='agency_id'){
			$more_information = $clsSop->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$more_information[$p_field] = $p_value;
			$clsSop->updateOne($p_id, array(
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else {
			$clsSop->updateOne($p_id, array(
				$p_field => $p_value
			));
		}
	} 
	if($p_field == 'agency_id'){
		$oSop = $clsSop->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_value = $core->get_field($oSop, $p_field, 0);
			$html = ($p_value >0 ? $clsProperty->getTitle($p_value) : "") . '<a href="javascript:void(0);" onClick="$Core.sop.editInlineField(this,event)" p_field="'.$p_field.'" p_id="'.$p_id.'" class="text-muted">
				'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<select class="form-control form-select edit_sop_field_'.$p_field.'_'.$p_id.'">
				'.$clsISO->getSelectByPropertyTypeTitle('_SOURCE',$oSop[$p_field],'Chọn nguồn').'
			</select>';
		}
	}
	if($p_action=='_cancel' || $p_action=='_save'){
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container">
			'.$html_input.'
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.sop.save_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.sop.cancel_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_map(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();	
	$clsCache = new Cache();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	$smarty->assign('clsSop', $clsSop);
	###
	$project_id = (int) Input::get('project_id', 0);
	if($project_id == 0){
		header('Location : /');
		exit();
	}
	$field = "{$clsProject->pkey},title,more_information";
	$oneProject = $clsProject->getOne($project_id, $field);
	$more_information = $oneProject['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$img_layout = $core->get_field($more_information, "layout", "");
	$has_block = (int) $core->get_field($more_information, "has_block", 1);
	$scriptJS = '<script type="text/javascript">
		var image_maps = {},
			project_id = \''.$project_id.'\',
			block_id = \''.$block_id.'\';
			image_maps[\'project\'] = \''.sprintf('%s%s', FH_URL, $img_layout).'\';';
	$list_blocks = $list_img_preloaders = array();
	if($has_block == 1){
		if($clsCache->has('_stock_block_'.$project_id.'_cached')){
			$list_blocks = $clsCache->get('_stock_block_'.$project_id.'_cached');
			// $clsISO->print_pre($list_blocks); die();
		} else {
			$field = "{$clsProperty->pkey},`title`,`more_information`";
			$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}' 
			and `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` ASC", $field);
			if(!empty($list_blocks)){
				foreach($list_blocks as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_blocks[$key]['more_information'] = $more_information;
				}
				$clsCache->put('_stock_block_'.$project_id.'_cached', $list_blocks);
			} else {
				if($clsCache->has('_stock_block_'.$project_id.'_cached')){
					$clsCache->delete('_stock_block_'.$project_id.'_cached');
				}
			}
		}
	}
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$block_id = $val[$clsProperty->pkey];
			$more_information = $val['more_information'];
			$layout_ms = $core->get_field($more_information, "layout_ms", "");
			if(!empty($layout_ms)){
				$scriptJS.= '
			image_maps['.$block_id.']=\''.sprintf('%s%s', FH_URL, $layout_ms).'\';';
				$list_img_preloaders[] = sprintf('%s%s', FH_URL, $layout_ms);
			}
		}
	}
	$scriptJS.= '</script>';
	###
	$arr_blocks = array(
		'today' => array(
			'title' => 'Hôm nay',
			'bgcolor' => '#4a047a'
		), 'yesterday' => array(
			'title' => 'Hôm qua',
			'bgcolor' => '#146602'
		), 'this_month' => array(
			'title' => 'Tháng này',
			'bgcolor' => '#690b0b'
		), 'uncheck' => array(
			'title' => 'Chưa check',
			'bgcolor' => '#876e04'
		), 'checked' => array(
			'title' => 'Đã check',
			'bgcolor' => '#058f9b'
		), 'soldout' => array(
			'title' => 'Đã bán',
			'bgcolor' => '#0058c0'
		)
	);
	###
	$assign_list["img_layout"] = $img_layout;
	$assign_list["project_id"] = $project_id;
	$assign_list["oneProject"] = $oneProject;
	$assign_list["scriptJS"] = $scriptJS;
	$assign_list["arr_blocks"] = $arr_blocks;
	$assign_list["list_img_preloaders"] = $list_img_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = sprintf('Mặt bằng dự án %s - ', $oneProject['title']) . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_get_shapes(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	$clsTelesale = new Telesale();
	##
	$holderG = 'stock';
	$block_id = (int) Input::post('block_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$oneProject = $clsProject->getOne($project_id, "more_information");
	$more_information = $oneProject['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$has_block = (int) $core->get_field($more_information, 'has_block', 0);
	$_results = array('msg' => '_error', 'holderG' => $holderG);
	##
	$cond = "`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}'";
	$arr_blocks = array(
		'today' => array(
			'title' => 'Hôm nay',
			'bgcolor' => '#4a047a'
		), 'yesterday' => array(
			'title' => 'Hôm qua',
			'bgcolor' => '#146602'
		), 'this_month' => array(
			'title' => 'Tháng này',
			'bgcolor' => '#690b0b'
		), 'uncheck' => array(
			'title' => 'Chưa check',
			'bgcolor' => '#876e04'
		), 'checked' => array(
			'title' => 'Đã check',
			'bgcolor' => '#058f9b'
		), 'soldout' => array(
			'title' => 'Đã bán',
			'bgcolor' => '#0058c0'
		)
	);
	$total_record = 0; $html_blocks = "";
	foreach($arr_blocks as $_oKey => $_oBlock){
		if($_oKey == 'today'){
			// $dbconn->debug = true;
			$sql_string = $cond." AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'";
		} else if($_oKey == 'yesterday'){
			$sql_string = $cond." AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y', strtotime("-1 day"))."'";
		} else if($_oKey == 'this_month'){
			$sql_string = $cond." AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='".date('m/Y')."'";
		} else if($_oKey == 'uncheck'){
			$sql_string = $cond." AND `status_id`='"._TELESALE_STATUS_UNCHECK_ID."'";
		} else if($_oKey == 'checked'){
			$sql_string = $cond." AND `status_id`='"._TELESALE_STATUS_CHECKED_ID."'";
		} else if($_oKey == 'soldout'){
			$sql_string = $cond." AND `status_id`='"._TELESALE_STATUS_SOLD_ID."'";
		}
		$total = $clsTelesale->countItem($sql_string);
		$html_blocks.= '<div class="col mb-2 flex-fill mb-lg-0 ">
			<div class="p-3 rounded-2 text-white tele_box relative tele_box-'.$_oKey.'" 
				style="background-color:'.$_oBlock['bgcolor'].'">
				<h3 class="mb-2 fs-3">'.$total.'</h3>
				<p class="mb-0">'.$_oBlock['title'].'</p>
			</div>
		</div>';
	}
	$_results['html_blocks'] = $html_blocks;
	##
	if($block_id > 0) {
		$cond.= " and `block_id`='{$block_id}'";
	} else if($has_block == 1) {
		$holderG = 'block';
		$_results['holderG'] = 'block';
	}
	$tmp = $clsStockShape->getByCond($cond);
	if(!empty($tmp)){
		$shapes = $tmp['shapes'];
		$shapes = $clsISO->to_array_json($shapes);
		if(!empty($shapes)){
			if($holderG == 'block' && $has_block == 1){
				foreach($shapes as $key => $val){
					$block_id = $val['block_id'];
					$shapes[$key]['name'] = $clsProperty->getTitle($block_id);
				}
			} else {
				$list_stock_ids = $list_shapes = array();
				foreach($shapes as $key => $val){
					$stock_id = $val['stock_id'];
					if($stock_id >0 && !in_array($stock_id, $list_stock_ids)){
						$list_shapes[$stock_id] = $val;
						$list_stock_ids[] = $stock_id;	
					}
				}
				$shapes = array();
				if(!empty($list_stock_ids) && !empty($list_shapes)){
					$field.= "`t1`.*";
					$list_stocks = $dbconn->getAll("SELECT {$field} FROM {$clsTelesale->tbl} as `t1` 
					INNER JOIN {$clsStock->tbl} as `t2` ON `t1`.`stock_code`=`t2`.`ms_code` 
					WHERE `t2`.`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
					AND `t2`.`project_id`='{$project_id}' AND `t2`.{$clsStock->pkey} in (".implode(',', $list_stock_ids).")");
					if(!empty($list_stocks)){
						$total_record = count($total_record);
						foreach($list_stocks as $key => $val){
							$stock_id = $val[$clsStock->pkey];
							$oneShape = $list_shapes[$stock_id];
							$oneShape['status_id'] = $val['status_id'];
							$oneShape['agency_id'] = $val['agency_id'];
							$shapes[] = $oneShape;
						}
					}
				}
			}
		}
		$_results['msg'] = '_success';
		$_results['shapes'] = $shapes;
	}
	$_results['total_record'] = $total_record;
	// Return
	echo json_encode($_results); die();
}
function default_save_setting(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	###
	$msg = "_error";
	$more_information = $oneProfile['more_information'];
	// $clsISO->print_pre($more_information); die();
	$more_information['sop_configs'] = array(
		'project_id' => Input::post('project_id', _PROJECT_VHOP2_ID),
		'stock_type' => Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE),
	);
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_telesale(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$oneProfile;
	$clsSop = new Sop();	
	$clsCache = new Cache();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$more_information = $oneProfile['more_information'];
	$sop_configs = $core->get_field($more_information, "sop_configs", []);
	if((int) $core->get_field($sop_configs, 'stock_type', 0) == 0){
		$sop_configs['stock_type'] = _BLOCK_TYPE_LOWFLOOR_SALE;
	}
	if((int) $core->get_field($sop_configs, 'project_id', 0) == 0){
		$sop_configs['project_id'] = _PROJECT_VHOP2_ID;
	}
	$smarty->assign('sop_configs', $sop_configs);
	###
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	###
	$arr_projects = $arr_blocks = array();
	$p_field = "{$clsProperty->pkey},`title`";
	$field = "{$clsProject->pkey},`title`,`code`";
	if($sop_configs['stock_type'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
		$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID);
		$arr_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
		AND `for_id`='".$sop_configs['project_id']."' ORDER BY `order_no` ASC", $p_field);
	} else {
		$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID);
		$arr_blocks = $clsProperty->getAll("`property_type`='_RANGE' AND `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
		AND `for_id`='".$sop_configs['project_id']."' ORDER BY `order_no` ASC", $p_field);
	}
	$arr_projects = $clsProject->getAll("`project_id` in (".implode(',', $arr_projects).")");
	$smarty->assign('arr_projects', $arr_projects);
	$smarty->assign('arr_blocks', $arr_blocks);
	// $clsISO->print_pre($arr_blocks); die();
	### 
	$arr_sum_blocks = array(
		'today' => array(
			'title' => 'Hôm nay',
			'bgcolor' => '#4a047a'
		), 'yesterday' => array(
			'title' => 'Hôm qua',
			'bgcolor' => '#146602'
		), 'this_month' => array(
			'title' => 'Tháng này',
			'bgcolor' => '#690b0b'
		), 'uncheck' => array(
			'title' => 'Chưa check',
			'bgcolor' => '#876e04'
		), 'checked' => array(
			'title' => 'Đã check',
			'bgcolor' => '#058f9b'
		), 'soldout' => array(
			'title' => 'Đã bán',
			'bgcolor' => '#0058c0'
		)
	);
	$smarty->assign('arr_sum_blocks', $arr_sum_blocks);
	$arr_prices = array(
		'price_owner' => array(
			'title' => 'Giá chủ',
			'min' => 0,
			'max' => 50000000000
		), 'fee_included' => array(
			'title' => 'Bao phí',
			'min' => 0,
			'max' => 50000000000
		), 'price' => array(
			'title' => 'Giá khách',
			'min' => 0,
			'max' => 50000000000
		), 'price_m2' => array(
			'title' => 'Giá/m2',
			'min' => 0,
			'max' => 1000000000
		)
	);
	$smarty->assign('arr_prices', $arr_prices);
	/*=============Title & Description Page==================*/
	$title_page = sprintf('Mặt bằng dự án %s - ', $oneProject['title']) . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_list_telesale(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();	
	$clsSetting = new Setting();
	$clsTelesale = new Telesale();
	$clsProperty = new Property();
	
	$stock_code = Input::post('stock_code');
	$contact_name = Input::post('contact_name');
	$contact_phone = Input::post('contact_phone');
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$project_id = (int) Input::post('project_id', _PROJECT_VHOP2_ID);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$type_id = (int) Input::post('type_id', 0);
	$bedroom_id = Input::post('bedroom_id', []);
	$status_id = (int) Input::post('status_id', 0);
	$interior_id = (int) Input::post('interior_id', 0);
	$juridical_id = (int) Input::post('juridical_id', 0);
	$fee_included = (int) Input::post('fee_included', 0);
	$finish_status_id = (int) Input::post('finish_status_id', 0);
	$status_viewing_id = (int) Input::post('status_viewing_id', 0);
	$upd_date = Input::post("upd_date");
	$sort_type = Input::post('sort_type', 'reg_date');
	$sort_by = Input::post('sort_by', 'DESC');
	#- Price field
	$price_owner_min = Input::post('price_owner_min');
	$price_owner_max = Input::post('price_owner_max');
	$price_min = Input::post('price_min');
	$price_max = Input::post('price_max');
	$price_m2_min = Input::post('price_m2_min');
	$price_m2_max = Input::post('price_m2_max');
	$price_owner_min = $clsISO->processSmartNumber($price_owner_min);
	$price_owner_max = $clsISO->processSmartNumber($price_owner_max);
	$price_min = $clsISO->processSmartNumber($price_min);
	$price_max = $clsISO->processSmartNumber($price_max);
	$price_m2_min = $clsISO->processSmartNumber($price_m2_min);
	$price_m2_max = $clsISO->processSmartNumber($price_m2_max);
	$smarty->assign('stock_type', $stock_type);
	##
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page', 50);
	###
	$cond = "`is_trash`=0 and `stock_type`='{$stock_type}'";
	if(!$clsISO->checkPermission('telesale_all')){
		$cond.= " and `sale_care_id`='{$profile_id}'";
	}
	if($project_id > 0){
		$cond.= " and `project_id`='{$project_id}'";
	}
	if($block_id > 0){
		$cond.= " and `block_id`='{$block_id}'";
	}
	if($building_id > 0){
		$cond.= " and `building_id`='{$building_id}'";
	}
	if(!empty($stock_code)){
		$cond.= " and `stock_code` like '%{$stock_code}%'";
	}
	if(!empty($contact_name)){
		$cond.= " and JSON_EXTRACT(`more_information`,\"$.contact_name\") like '%{$contact_name}%'";
	}
	if(!empty($contact_phone)){
		$cond.= " and JSON_EXTRACT(`more_information`,\"$.contact_phone\") like '%{$contact_phone}%'";
	}
	if($type_id > 0){
		$cond.= " and `type_id`='{$type_id}'";
	}
	if($status_id > 0){
		$cond.= " and `status_id`='{$status_id}'";
	}
	if($fee_included > 0){
		$cond.= " and `fee_included`='{$fee_included}'";
	}
	if($finish_status_id > 0){
		$cond.= " and `finish_status_id`='{$finish_status_id}'";
	}
	if($interior_id > 0){
		$cond.= " AND JSON_EXTRACT(`more_information`,\"$.interior_id\")='{$interior_id}'";
	}
	if($juridical_id > 0){
		$cond.= " AND JSON_EXTRACT(`more_information`,\"$.juridical_id\")='{$juridical_id}'";
	}
	if(!empty($bedroom_id)){
		$cond.= " AND `bedroom_id` in (".implode(',', $bedroom_id).")";
	}
	if($status_viewing_id > 0){
		$cond.= " AND JSON_EXTRACT(`more_information`,\"$.status_viewing_id\")='{$status_viewing_id}'";
	}
	$cond.= " AND (`price_owner` BETWEEN {$price_owner_min} AND {$price_owner_max})";
	$cond.= " AND (`price` BETWEEN {$price_min} AND {$price_max})";
	if(!empty($upd_date)){
		$cond.= " AND FROM_UNIXTIME(`upd_date`,'%Y-%m-%d')='{$upd_date}'";
		// $clsISO->print_pre($upd_date); die();
	}
	# Begin pagination
	$total_record = (int) $clsTelesale->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	# End pagination
	$total_items = 0;
	$listItem = $clsTelesale->getAll($cond." order by `{$sort_by}` {$sort_type}".$limitCond);
	if(!empty($listItem)){ $ii = 1;
		$total_items = count($listItem);
		$arr_setting_cached = $arr_property_cached = array();
		foreach($listItem as $key => $val){
			$type_id = (int) $val['type_id'];
			$bedroom_id = (int) $val['bedroom_id'];
			$stock_type = (int) $val['stock_type'];
			$bedroom_id = (int) $val['bedroom_id'];
			$status_id = (int) $val['status_id'];
			$fee_included = (int) $val['fee_included'];
			$finish_status_id = (int) $val['finish_status_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$listItem[$key]['more_information'] = $more_information;
			if($status_id > 0 && !isset($arr_setting_cached[$status_id])){
				$arr_setting_cached[$status_id] = $clsSetting->getLabel($status_id);
			}
			$listItem[$key]['status_name'] = $arr_setting_cached[$status_id];
			#
			if($fee_included > 0){
				if(!isset($arr_property_cached[$fee_included])){
					$arr_property_cached[$fee_included] = $clsProperty->getLabel($fee_included);
				}
			} else {
				$fee_included = 0;
				$arr_property_cached[$fee_included] = '<span class="badge bg-warning">Chưa rõ</span>';
			}
			$listItem[$key]['fee_included_name'] = $arr_property_cached[$fee_included];
			#
			if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
				if($type_id > 0){
					if(!isset($arr_property_cached[$type_id])){
						$arr_property_cached[$type_id] = $clsProperty->getTitle($type_id);
					}
				} else {
					$type_id = 10000;
					$arr_property_cached[$type_id] = '--';
				}
				$listItem[$key]['type_name'] = $arr_property_cached[$type_id];
			} else {
				if($bedroom_id > 0){
					if(!isset($arr_property_cached[$bedroom_id])){
						$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
					}
				} else {
					$bedroom_id = 10000;
					$arr_property_cached[$bedroom_id] = '--';
				}
				$listItem[$key]['bedroom_name'] = $arr_property_cached[$bedroom_id];
			}
			#
			if($finish_status_id > 0){
				if(!isset($arr_setting_cached[$finish_status_id])){
					$arr_setting_cached[$finish_status_id] = $clsSetting->getTitle($finish_status_id);
				}
			} else {
				$finish_status_id = 0;
				$arr_setting_cached[$finish_status_id] = '--';
			}
			$listItem[$key]['finish_status_name'] = $arr_setting_cached[$finish_status_id];
			$listItem[$key]['stt'] = $offset + $ii;
			++$ii;
		}
	}
	$list_blanks = array();
	if($total_items < $per_page){
		for($i=0; $i<($per_page - $total_items); $i++){
			$list_blanks[] = $i;
		}
	}
	$smarty->assign('total_items', $total_items);
	$smarty->assign('list_blanks', $list_blanks);
	$smarty->assign('listItem', $listItem);
	// $clsISO->print_pre($listItem); die();
	$arr_blocks = array(
		'today' => array(
			'title' => 'Hôm nay',
			'bgcolor' => '#4a047a'
		), 'yesterday' => array(
			'title' => 'Hôm qua',
			'bgcolor' => '#146602'
		), 'this_month' => array(
			'title' => 'Tháng này',
			'bgcolor' => '#690b0b'
		), 'uncheck' => array(
			'title' => 'Chưa check',
			'bgcolor' => '#876e04'
		), 'checked' => array(
			'title' => 'Đã check',
			'bgcolor' => '#058f9b'
		), 'soldout' => array(
			'title' => 'Đã bán',
			'bgcolor' => '#0058c0'
		)
	);
	$html_blocks = "";
	foreach($arr_blocks as $_oKey => $_oBlock){
		if($_oKey == 'today'){
			// $dbconn->debug = true;
			$sql_string = $cond." AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'";
		} else if($_oKey == 'yesterday'){
			$sql_string = $cond." AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y', strtotime("-1 day"))."'";
		} else if($_oKey == 'this_month'){
			$sql_string = $cond." AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='".date('m/Y')."'";
		} else if($_oKey == 'uncheck'){
			$sql_string = $cond." AND `status_id`='"._TELESALE_STATUS_UNCHECK_ID."'";
		} else if($_oKey == 'checked'){
			$sql_string = $cond." AND `status_id`='"._TELESALE_STATUS_CHECKED_ID."'";
		} else if($_oKey == 'soldout'){
			$sql_string = $cond." AND `status_id`='"._TELESALE_STATUS_SOLD_ID."'";
		}
		$total = $clsTelesale->countItem($sql_string);
		$html_blocks.= '<div class="col mb-2 flex-fill mb-lg-0 ">
			<div class="p-3 rounded-2 text-white tele_box relative tele_box-'.$_oKey.'" 
				style="background-color:'.$_oBlock['bgcolor'].'">
				<h3 class="mb-1 fs-3">'.$total.'</h3>
				<p class="mb-0">'.$_oBlock['title'].'</p>
			</div>
		</div>';
	}
	// Return
	$html = $core->build('_ajax.telesale.tpl');
	echo json_encode(array(
		'html' => $html,
		'stock_type' => $stock_type,
		'html_blocks' => $html_blocks,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'per_page' => $per_page,
	)); die();
}
function default_get_select_options(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$oneProfile;
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$more = array();
	$html_blocks = ""; $html_options = "";
	$holderG = Input::post('holderG', 'project');
	$more_information = $oneProfile['more_information'];
	$sop_configs = $core->get_field($more_information, 'sop_configs', []);
	##
	if($holderG == 'project'){
		$project_id = (int) Input::post('project_id', 0);
		$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID);
		} else {
			$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID);
		}
		$sop_configs['stock_type'] = $stock_type;
		$arr_projects = $clsProject->getAll("`project_id` in (".implode(',', $arr_projects).")");
		if(!empty($arr_projects)){
			foreach($arr_projects as $key => $val){
				$html_options.= '<option'.($project_id==$val[$clsProject->pkey]?' selected':'').' value="'.$val[$clsProject->pkey].'">'.$val['title'].'</option>';
			}
		}
		#- Get Block
		$QueryBuilder = DB::table($clsProperty->tbl);
		$QueryBuilder->select('property_id,title');
		$QueryBuilder->where('property_type', '_BLOCK');
		$QueryBuilder->where('parent_id', $stock_type);
		$QueryBuilder->where('for_id', $project_id);
		$tmp = $QueryBuilder->get();
		
		$html_blocks = '<option vlue="0">Phân khu/Block</option>';
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html_blocks.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
			}
			unset($tmp);
		}
		$more['html_blocks'] = $html_blocks;
	} else if($holderG == 'block'){
		$project_id = (int )Input::post('project_id', 0);
		$stock_type = Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
		###
		$QueryBuilder = DB::table($clsProperty->tbl);
		$QueryBuilder->select('property_id,title');
		$QueryBuilder->where('property_type', '_BLOCK');
		$QueryBuilder->where('parent_id', $stock_type);
		$QueryBuilder->where('for_id', $project_id);
		$tmp = $QueryBuilder->get();
		
		$sop_configs['project_id'] = $project_id;
		$html_options = '<option vlue="0">Phân khu/Block</option>';
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
			}
			unset($tmp);
		}
	} else if($holderG == 'building'){
		$block_id = (int )Input::post('block_id', 0);
		$stock_type = Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
		$property_type = ($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE) ? '_RANGE' : '_BUILDING';
		
		$QueryBuilder = DB::table($clsProperty->tbl);
		$QueryBuilder->select('property_id,title');
		$QueryBuilder->where('property_type', $property_type);
		$QueryBuilder->where('for_id', $block_id);
		$tmp = $QueryBuilder->get();
		
		$sop_configs['block_id'] = $block_id;
		$html_options = '<option vlue="0">Toà nhà/Dãy</option>';
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
			}
			unset($tmp);
		}
	}
	$more_information['sop_configs'] = $sop_configs;
	$clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	));
	// Return
	echo json_encode(array_merge($more, array(
		'holderG' => $holderG,
		'html_options' => $html_options
	))); die();
}
function default_open_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();	
	$clsStock = new Stock();
	$clsSetting = new Setting();
	$clsTelesale = new Telesale();
	$clsProperty = new Property();
	$smarty->assign('clsSetting', $clsSetting);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$telesale_id = (int) Input::post('telesale_id', 0);
	##
	$field = "`t1`.*,IF(`t1`.`type_id`>0,`t1`.`type_id`,`t2`.`type_id`) AS `type_id`,IF(`t1`.`stock_type`>0,`t1`.`stock_type`,`t2`.`stock_type`) AS `stock_type`";
	$field.= ",`t2`.`more_information` as `stock_information`,`t2`.`home_direction_id`";
	$oneStock = $dbconn->getRow("SELECT {$field} FROM {$clsTelesale->tbl} as `t1` 
		LEFT JOIN {$clsStock->tbl} as `t2` ON `t1`.`stock_id`=`t2`.`stock_id` 
		WHERE `t1`.{$clsTelesale->pkey}='{$telesale_id}'");
	// $clsISO->print_pre($oneStock); die();
	$more_information = $oneStock['more_information'];
	$stock_information = $oneStock['stock_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$stock_information = $clsISO->to_array_json($stock_information);
	
	$list_images = array(); $total_images = 0;
	$image_folder = $core->get_field($more_information, 'image_folder', []);
	if(!empty($image_folder)){
		$clsProjectMeta = new ProjectMeta();
		$tmp = $clsProjectMeta->crawl($image_folder);
		if(isset($tmp['list_files']) && !empty($tmp['list_files'])){
			foreach($tmp['list_files'] as $okey => $oval){
				$total_images += 1;
				$list_images[] = $clsISO->genGoogleURL($okey, 'view');
			}
		}
	}
	$smarty->assign('telesale_id', $telesale_id);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('oneStock', $oneStock);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('stock_information', $stock_information);
	$smarty->assign('list_images', $list_images);
	$smarty->assign('total_images', $total_images);
	/// Return
	$html = $core->build('_ajax.open_stock.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total_images' => $total_images
	)); die();
}
function default_load_reminders(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	
	$uid = $clsISO->getUniqid();
	$telesale_id = (int) Input::post('telesale_id', 0);
	$smarty->assign('telesale_id', $telesale_id);
	
	$field = "{$clsFollowUp->pkey},reminder_time,admin_id,intro";
	$list_reminders = $clsFollowUp->getAll("`followup_type`='_telesale' and `is_reminder`=1 
		AND `customer_id`='{$telesale_id}' order by `reg_date` DESC", $field);
	if(!empty($list_reminders)){
		$arr_Profile_cached = array();
		foreach($list_reminders as $key => $val){
			$admin_id = $val['admin_id'];
			if(!isset($arr_profile_cached[$admin_id])){
				$arr_profile_cached[$admin_id] = $clsProfile->getIndentity($admin_id, true);
			}
			$list_reminders[$key]['html_admin'] = $arr_profile_cached[$admin_id];
		}
	}
	$smarty->assign('list_reminders', $list_reminders);
	
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.reminder.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_reminder(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsFollowUp = new FollowUp();
	##
	$uid = $clsISO->getUniqid();
	$reminder_id = (int) Input::post('reminder_id', 0);
	$telesale_id = (int) Input::post('telesale_id', 0);
	##
	$action = "_add";
	$oneReminder = array();
	if($reminder_id > 0){
		$action = "_edit";
		$oneReminder = $clsFollowUp->getOne($reminder_id);
		$reminder_time = $oneReminder['reminder_time'];
		$reminder_time = date('Y-m-d\TH:i', $reminder_time);
		$oneReminder['reminder_time'] = $reminder_time;
	}
	$smarty->assign('action', $action);
	$smarty->assign('oneReminder', $oneReminder);
	$smarty->assign('reminder_id', $reminder_id);
	$smarty->assign('telesale_id', $telesale_id);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.reminder.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_reminder(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsFollowUp = new FollowUp();
	###
	$msg = "_error";
	$parent_id = (int) Input::post('parent_id', 0);
	$reminder_id = (int) Input::post('reminder_id', 0);
	$telesale_id = (int) Input::post('telesale_id', 0);
	$reminder_time = Input::post('reminder_time', 0);
	$reminder_time = !empty($reminder_time) ? $clsISO->toTime($reminder_time) : 0;
	if($reminder_id > 0){
		if($clsFollowUp->updateOne($reminder_id, array(
			'intro' => Input::post('content'),
			'reminder_time' => $reminder_time,
			'admin_id' => Input::post('admin_id', $profile_id),
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	} else {
		$reminder_id = $clsFollowUp->getMaxId();
		if($clsFollowUp->insert(array(
			$clsFollowUp->pkey => $reminder_id,
			'followup_type' => '_telesale',
			'customer_id' => $telesale_id,
			'parent_id' => $parent_id,
			'type_id' => _FOLLOWUP_CALL_ID,
			'is_reminder' => 1,
			'reminder_time' => $reminder_time,
			'intro' => Input::post('content'),
			'admin_id' => Input::post('admin_id', $profile_id),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_reminder(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsFollowUp = new FollowUp();
	###
	$msg = "_error";
	$reminder_id = (int) Input::post('reminder_id', 0);
	$telesale_id = (int) Input::post('telesale_id', 0);
	if($reminder_id > 0){
		if($clsFollowUp->deleteOne($reminder_id)){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_open_contact(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	##
	$uid = $clsISO->getUniqid();
	$contact_id = (int) Input::post('contact_id', 0);
	$telesale_id = (int) Input::post('telesale_id', 0);
	##
	$field = "{$clsProperty->pkey},title,image";
	$list_activity = $clsProperty->getAllCache("`is_trash`=0 and `parent_id`='0' 
	and `property_type`='FOLLOWUP_TYPE' order by `order_no` ASC", $field);
	##
	$action = "_add"; 
	$oneContact = array(
		'type_id' => _FOLLOWUP_CALL_ID,
		'intro' => ""
	);
	if($contact_id > 0){
		$action = '_edit';
		$oneContact = $clsFollowUp->getOne($contact_id);
	}
	$smarty->assign('contact_id', $contact_id);
	$smarty->assign('telesale_id', $telesale_id);
	$smarty->assign('list_activity', $list_activity);
	$smarty->assign('oneContact', $oneContact);
	//$clsISO->print_pre($list_activity); die();
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.contact.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_contact(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsTelesale = new Telesale();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	##
	$telesale_id = (int) Input::post('telesale_id', 0);
	$oneTelesale = $clsTelesale->getOne($telesale_id);
	$more_information = $oneTelesale['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$owner_notes = $core->get_field($more_information, "owner_notes", "");
	##
	$htmlNotFound = CRM::renderHTMLNoDocument('Không có bất kỳ hoạt động nào<br /> với khách hàng này');
	$list_followups = $clsFollowUp->getAll("`followup_type`='_telesale' and `is_reminder`=0 
		AND `customer_id`='{$telesale_id}' order by `reg_date` DESC");
	if(!empty($list_followups)){
		$arr_profile_cached = array();
		foreach($list_followups as $key => $val){
			$user_id = $val['user_id'];
			if(isset($arr_profile_cached[$user_id])){
				$oProfile = $arr_profile_cached[$user_id];
			} else {
				$oProfile = $clsProfile->getOne($user_id, "full_name,first_name,last_name,avatar");
				$arr_profile_cached[$user_id] = $oProfile;
			}
			$list_followups[$key]['oProfile'] = $oProfile;
		}
	}
	$smarty->assign('telesale_id', $telesale_id);
	$smarty->assign('oneTelesale', $oneTelesale);
	$smarty->assign('owner_notes', $owner_notes);
	$smarty->assign('htmlNotFound', $htmlNotFound);
	$smarty->assign('htmlNotFound', $htmlNotFound);
	$smarty->assign('list_followups', $list_followups);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.contact.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_save_contact(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsFollowUp = new FollowUp();
	###
	$msg = "_error";
	$contact_id = (int) Input::post('contact_id', 0);
	$telesale_id = (int) Input::post('telesale_id', 0);
	$parent_id = (int) Input::post('parent_id', 0);
	$type_id = (int) Input::post('type_id', _FOLLOWUP_CALL_ID);
	if($contact_id > 0){
		if($clsFollowUp->updateOne($contact_id, array(
			'type_id' => $type_id,
			'intro' => Input::post('content'),
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	} else {
		$contact_id = $clsFollowUp->getMaxId();
		if($clsFollowUp->insert(array(
			$clsFollowUp->pkey => $contact_id,
			'followup_type' => '_telesale',
			'customer_id' => $telesale_id,
			'parent_id' => $parent_id,
			'type_id' => _FOLLOWUP_CALL_ID,
			'intro' => Input::post('content'),
			'admin_id' => $profile_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
			$clsTelesale->updateOne(array(
				'upd_date' => time(),
				'user_id_update' => $profile_id
			));
		}
	}
	// Return
	echo $msg; die();
}
function default_chatlogs(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsFollowUp = new FollowUp();
	
	$list_preloaders = array();
	for($i=0; $i<30; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	/*=============Title & Description Page==================*/
	$title_page = sprintf('Chatlogs - %s', PAGE_NAME);
	$assign_list["title_page"] = $title_page;
}
function default_load_chatlogs(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;
	$clsSop = new Sop();
	$clsSopChatLog = new SopChatLog();
	$clsZaloGroup = new ZaloGroup();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$html = ""; $sql_string = "1=1"; 
	$action = Input::post('action', "_load");
	$type_list = Input::post('type_list', "");
	$keysearch = Input::post('keysearch', "");
	$reg_date = (int) Input::post('reg_date', 0);
	$last_date = (int) Input::post('last_date', 0);
	if(!empty($type_list)){
		$sql_string.= " AND JSON_EXTRACT(`stock_logs`,\"$.loai\")='{$type_list}'";
	} 
	if($reg_date > 0 && $action == "_more") {
		$sql_string.= " AND `reg_date`<'{$reg_date}'";
	}
	if($last_date > 0 && $action == "_append"){
		 $sql_string.= " and `reg_date`>'{$last_date}'";
	}
	// $dbconn->debug = true;
	$list_chatlogs = $clsSopChatLog->getAll("{$sql_string} ORDER BY `reg_date` DESC limit 0,15");
	if(!empty($list_chatlogs)){
		$last_date = $list_chatlogs[0]['reg_date'];
		$arr_zalo_group_cached = array();
		foreach($list_chatlogs as $key => $val){
			$sender_id = $val['sender_id'];
			$id_group = $val['id_group'];
			$data_logs = $val['data_logs'];
			$sop_chatlog_id = $val[$clsSopChatLog->pkey];
			$data_logs = $clsISO->to_array_json($data_logs);
			$zalo_name = $data_logs['data']['data']['groupMsgs'][0]['dName'];
			$name_group = isset($data_logs['name_group']) ? $data_logs['name_group'] : "";
			// $clsISO->print_pre($data_logs); die();
			$html.= '<div class="awe__chat-item js__chat-item" data-reg_date="'.$val['reg_date'].'">
				<div class="w-100 d-flex gap-2">
					<div class="awe__chat-avatar" title="Tư Lê Văn">
						<a data-url="/index.php?mod=home&amp;act=load_profile_popover&amp;user_id=976" data-toggle="webui-popover" data-trigger="hover" data-arrow="true" data-width="300" class="bs-webui-popover" data-target="webuiPopover19">
							<img src="https://myoceancity.vn/application/themes/images/no-avatar.jpg" alt="Tư Lê Văn" onerror="this.src=\'https://myoceancity.vn/application/themes/images/no-avatar.jpg\'" class="rounded-pill" width="40" height="40">
						</a>
					</div>
					<!-- shadow-sm -->
					<div class="awe__chat-item-body relative py-2 px-4 rounded-3 bg-white">
						<p class="fs-13 text-muted mb-1">'.$zalo_name.'('.$name_group.')</p>
						<div class="awe__chat-description mb-1">'.nl2br($data_logs['content']).'</div>
						<div class="awe__chat-time">
							<span class="text-muted">
								<i class=\'bx bx-time text-fs-12\'></i> '.$clsISO->getTimeAgo($val['reg_date']).'
							</span>
						</div>
					</div>
					<div class="awe__chat-cmd  d-flex align-items-end">
						<div class="d-flex p-1 align-items-center rounded-pill bg-white dropdown awe__chat-dropdown">
							<a data-bs-toggle="dropdown" class="awe__chat-action rounded-pill dropdown-toggle hide-arrow cursor-pointer" title="Công cụ"><i class="bx bx-dots-horizontal-rounded"></i></a>
							<div class="dropdown-menu w-px-150">
								<a class="dropdown-item cursor-pointer text-primary me-2" onclick="$Core.global.sop.reply_chat(this, event)" sop_chatlog_id="'.$sop_chatlog_id.'"><i class="bx bx-chat"></i> Reply tin nhóm</a>
								<a class="dropdown-item cursor-pointer text-danger me-2" onclick="$Core.global.sop.open_chat(\''.$sender_id.'\', \''.$sop_chatlog_id.'\')"><i class="bx bx-chat"></i> Gửi tin nhắn</a>
								<hr class="dropdown-divider" />
								<a class="dropdown-item cursor-pointer me-2" onclick="$Core.global.sop.open_sop(this, event)" sop_chatlog_id="'.$sop_chatlog_id.'"><i class="bx bx-plus"></i> Tạo tin CN</a>
								<a class="dropdown-item cursor-pointer me-2" onclick="$Core.global.sop.do_copy(this, event)" sop_chatlog_id="'.$sop_chatlog_id.'"><i class="bx bx-copy"></i> Sao chép</a>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
		$html.= '';
	} else {
		$html = "_empty";
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'action' => $action,
		'last_date' => $last_date
	)); die();
}
function default_reply_chat(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSopChatLog = new SopChatLog();
	#
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$sop_chatlog_id = (int) Input::post('sop_chatlog_id', 0);
	$oneChatLog = $clsSopChatLog->getOne($sop_chatlog_id);
	// $clsISO->print_pre($oneChatLog); die();
	$data_logs = $oneChatLog['data_logs'];
	$data_logs = $clsISO->to_array_json($data_logs);
	
	$html = '<div class="modal-dialog modal-dialog-centered">
		<form class="modal-content">
			<div class="modal-header border-bottom position-relative">
				<h5 class="modal-title">
					<i class="bx bx-chat"></i> 
					Trả lời tin nhắn nhóm
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="p-3 mb-2 bg-lighter">
					<div class="pl-2" style="border-left:5px solid rgb(221,221,221)">
						'.nl2br($data_logs['content']).'
					</div>
				</div>
				<label class="form-label">Nội dung tin nhắn</label>
				<textarea class="form-control required autosize" name="message" 
					placeholder="Nhập nội dung tin nhắn sẽ phản hồi" rows="4"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn xs:flex-fill btn-outline-default" data-bs-dismiss="modal">Đóng</button>
				<button type="button" onClick="$Core.global.sop.do_reply(this, event)" sop_chatlog_id="'.$sop_chatlog_id.'" 
					class="btn xs:flex-fill btn-primary">Gửi tin nhắn</button>
			</div>
		</form>
	</div>';
	// Return 
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_reply(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSopChatLog = new SopChatLog();
	#
	$msg = "_error";
	$message = Input::post('message');
	$sop_chatlog_id = (int) Input::post('sop_chatlog_id', 0);
	$oneChatLog = $clsSopChatLog->getOne($sop_chatlog_id);
	$id_group = $oneChatLog['id_group'];
	$data_logs = $oneChatLog['data_logs'];
	$data_logs = $clsISO->to_array_json($data_logs);
	$reply_message = isset($data_logs['data']['data']['groupMsgs'][0]) 
		? $data_logs['data']['data']['groupMsgs'][0] : []; 
	// $clsISO->print_pre($reply_message); die();
	$curl = new \Curl\Curl();
	$curl->setHeaders(array(
		'Content-Type' => 'application/json',
		'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTJkYjg2NTAxOTEwMmQ0MjhmNjAzZCIsImlhdCI6MTc1NDQ1NDkxOCwiZXhwIjoxNzg1OTkwOTE4fQ.WWk31rV6cZuoQKXGefDwOy9dG3tRwxGoTIWrxkxWTOY'
	));
	$curl->post('https://public-api.bizflow.vn/functions/6892db865019102d428f603d', array(
		'message' => $message,
		'group_id' => $id_group,
		'reply_message' => $reply_message
	));
	if(!$curl->error){
		$response = toArray($curl->response);
		if(isset($response['status']) && $response['status'] == 200){
			$msg = "success";
		}
	} else {
		$msg = "_curl_error";
	}
	// Return
	echo $msg; die();
}
function default_open_chat(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsZaloUser = new ZaloUser();
	$clsSopChatLog = new SopChatLog();
	##
	$sender_id = Input::post('sender_id', 0);
	$sop_chatlog_id = (int) Input::post('sop_chatlog_id', 0);
	if($sender_id == 0 && $sop_chatlog_id > 0){
		$oneChatLog = $clsSopChatLog->getOne($sop_chatlog_id);
		// $clsISO->print_pre($oneChatLog); die();
		$sender_id = $oneChatLog['sender_id'];
	}
	$tmp = $clsZaloUser->getByCond("`user_id`='{$sender_id}'");
	$oneSender = !empty($tmp) ? $clsISO->to_array_json($tmp['user_info']) : [];
	$smarty->assign('oneSender', $oneSender);
	$smarty->assign('sender_id', $sender_id);
	$smarty->assign('sop_chatlog_id', $sop_chatlog_id);
	// $clsISO->print_pre($sender_id); die();
	$list_preloaders = array();
	for($i=0; $i<10; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('_ajax.chat.tpl');
	echo json_encode(array(
		'html' => $html,
		'sender_id' => $sender_id
	)); die();
}
function default_load_chats(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsZaloSop = new ZaloSop();
	$clsZaloUser = new ZaloUser();
	$clsZaloChat = new ZaloChat();
	$clsSopChatLog = new SopChatLog();
	###
	$oneOwner = $oneSender = array(); 
	$sender_id = Input::post('sender_id', 0);
	$tmp = $clsZaloUser->getAll("user_id in (".implode(',', [$sender_id, _SOP_ZALO_ID]).")");
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			if($val['user_id'] == $sender_id){
				$oneSender = $val['user_info'];
				$oneSender = $clsISO->to_array_json($oneSender);
			} else if($val['user_id'] == _SOP_ZALO_ID){
				$oneOwner = $val['user_info'];
				$oneOwner = $clsISO->to_array_json($oneOwner);
			}
		}
	}
	$smarty->assign('oneOwner', $oneOwner);
	$smarty->assign('oneSender', $oneSender);
	$list_chats = $clsZaloChat->getAll("(`chat_type`='_send' AND `sender_id`='"._SOP_ZALO_ID."' AND `receiver_id`='{$sender_id}') OR (`chat_type`='_received' AND sender_id='{$sender_id}' AND `receiver_id`='"._SOP_ZALO_ID."') ORDER BY `reg_date` DESC limit 0,20");
	if(!empty($list_chats)){
		$list_chats = array_reverse($list_chats);
		foreach($list_chats as $key => $val){
			$is_me = false;
			if($val['sender_id'] == _SOP_ZALO_ID){
				$is_me = true;
			} else {
				$message = $val['message'];
				$message = $clsISO->to_array_json($message);
				$message = isset($message['data']['data']['msgs'][0]['content']) 
					? $message['data']['data']['msgs'][0]['content'] : "";
				$list_chats[$key]['message'] = $message;
				// $clsISO->print_pre($message); die();
			}
			$list_chats[$key]['is_me'] = $is_me;
		}
	}
	$smarty->assign('list_chats', $list_chats);
	
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.chat.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_send_chat(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsZaloSop = new ZaloSop();
	$clsZaloChat = new ZaloChat();
	$clsSopChatLog = new SopChatLog();
	###
	$msg = "_error";
	$sender_id = Input::post('sender_id', 0);
	$message = Input::post('message', "");
	if(!empty($sender_id) && !empty($message)){
		$clsZaloChat->insert(array(
			'chat_type' => '_send',
			'sender_id' => _SOP_ZALO_ID,
			'receiver_id' => $sender_id,
			'message' => $message,
			'reg_date' => time()
		));
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTFmMjJkOGRiYmVlMmJmYjIyNTc4NSIsImlhdCI6MTc1NDM5NTE4MSwiZXhwIjoxNzg1OTMxMTgxfQ.5LfPjf3GVlbCO8rHY_4UpLwO2zsqqRhVbEFR31gyLFM'
		));
		$curl->post('https://public-api.bizflow.vn/functions/6891f22d8dbbee2bfb225785', array(
			'message' => $message,
			'user_id' => $sender_id
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && $response['status'] == 200){
				$msg = "success";
			}
		} else {
			$msg = "_curl_error";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'sender_id' => $sender_id
	)); die();
}
function normalizeString(string $str): string {
    // Chuyển về chữ thường
    $str = mb_strtolower($str, 'UTF-8');
    // Xóa các ký tự . và -
    $str = str_replace(['.', '-'], '', $str);
    // Xóa khoảng trắng thừa nếu có
    $str = trim($str);
    return $str;
}
function startsWithIgnoreSpecial(string $haystack, string $needle): bool {
    $haystack = normalizeString($haystack);
    $needle   = normalizeString($needle);
    return strncmp($haystack, $needle, strlen($needle)) === 0;
}
function default_open_sop(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsSopChatLog = new SopChatLog();
	$clsProperty = new Property();
	##
	$list_needs = $clsProperty->getCacheItems('_NEED_TYPE');
	$smarty->assign('list_needs', $list_needs);
	##
	$list_phaply = $clsProperty->getCacheItems('_JURIDICAL');
	$smarty->assign('list_phaply', $list_phaply);
	##
	$list_sop_type = $clsProperty->getCacheItems('_SOPTYPE');
	$smarty->assign('list_sop_type', $list_sop_type);
	##
	$list_type_villa = $clsProperty->getCacheItems('_TYPE_VILLA');
	foreach ($list_type_villa as $k => $v) {
		if(!in_array($v['property_id'], _ARRAY_TYPE_VILLA) ){
			unset($list_type_villa[$k]);
		}
	}
	$smarty->assign('list_type_villa', $list_type_villa);
	##
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$sop_id = (int) Input::post('sop_id', 0);
	$sop_chatlog_id = (int) Input::post('sop_chatlog_id', 0);
	$oneChatLog = $clsSopChatLog->getOne($sop_chatlog_id);
	$data_logs = $oneChatLog['data_logs'];
	$stock_logs = $oneChatLog['stock_logs'];
	$data_logs = $clsISO->to_array_json($data_logs);
	$stock_logs = $clsISO->to_array_json($stock_logs);
	$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID);
	##
	$stock_code = $core->get_field($stock_logs, 'ma_can', "");
	$loai_can = $core->get_field($stock_logs, 'loai_can', "");
	$gia_ban = $core->get_field($stock_logs, 'gia_ban', 0);
	$phap_ly = $core->get_field($stock_logs, 'phap_ly', "");
	$huong = $core->get_field($stock_logs, 'huong', "");
	$DT_TT = $core->get_field($stock_logs, 'dien_tich', 0);
	$stock_code = !empty($stock_code) ? $clsSop->format_stock_code($stock_code) : "";
	$price = !empty($gia_ban) ? $clsSop->convertToCurrency($gia_ban) : 0;
	$content = isset($data_logs['content']) ? $data_logs['content'] : "";
	##
	$home_direction_id = 0;
	if(!empty($huong)){
		$tmp = $clsProperty->getByCond("property_type='_DIRECTION' AND (`slug`='".$core->replaceSpace($huong)."' OR `slug_vn`='".$core->replaceSpace($huong)."')", $clsProperty->pkey);
		$home_direction_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
	}
	$oneSop = array(
		'need_id' => _SOP_NEED_URGENT_ID,
		'sop_type' => _SOP_TYPE_HIGHLEVEL,
		'project_id' => _PROJECT_DEF_ID,
		'stock_code' => $stock_code,
		'price' => $price,
		'code' => "",
		'floor' => "",
		'bedroom_id' => $bedroom_id,
		'home_direction_id' => $home_direction_id
	);
	$sop_type = _SOP_TYPE_HIGHLEVEL;
	$list_sop_blocks = $list_sop_buildings = $more_information = $arr_titles = array();
	$more_information['DT_TT'] = $clsISO->convertToNumber($DT_TT);
	$more_information['content'] = $content;
	// $clsISO->print_pre($stock_logs); die();
	if(!empty($stock_logs) && isset($stock_logs['loai']) && $stock_logs['loai'] == 'can_ban' 
		&& isset($stock_logs['ma_can']) && !empty($stock_logs['ma_can'])){
		if(isset($stock_logs['loai_can']) && !empty($stock_logs['loai_can'])){
			$arr_titles['bedroom_name'] = sprintf('căn %s', $stock_logs['loai_can']);
		}
		if(isset($stock_logs['toa_nha']) && !empty($stock_logs['toa_nha'])){
			$arr_titles['building_name'] = sprintf('toà %s', $stock_logs['toa_nha']);
		}
		if(isset($stock_logs['huong']) && !empty($stock_logs['huong'])){
			$arr_titles['home_direction_name'] = sprintf('hướng %s', $stock_logs['huong']);
		}
		if(isset($stock_logs['dien_tich']) && !empty($stock_logs['dien_tich'])){
			$arr_titles['DT_TT'] = sprintf('%s m2', $stock_logs['dien_tich']);
		}
		$stock_code = strtoupper($stock_logs['ma_can']);
		$stock_code = $clsSop->format_stock_code($stock_code);
		$ms_code = preg_replace('/\s+/', '', $stock_code);
		$stock_code = str_replace('.','', $ms_code);
		$stock_code = str_replace("-","",$stock_code);
		$stock_field = "{$clsStock->pkey},`stock_type`,`more_information`,`bedroom_id`
		,`home_direction_id`,`project_id`,`block_id`,`building_id`,`floor`,`code`";
		$oneStock = $clsStock->getByCond("(`ms_code`='{$ms_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$stock_code}') AND `project_id` IN (".implode(',',$arr_projects).")", $stock_field);
		if(!empty($oneStock)){
			$stock_type = (int) $oneStock['stock_type'];
			$project_id = (int) $oneStock['project_id'];
			$block_id = (int) $oneStock['block_id'];
			$building_id= (int) $oneStock['building_id'];
			$bedroom_id = (int) $oneStock['bedroom_id'];
			$home_direction_id = $oneStock['home_direction_id'];
			$stock_information = $oneStock['more_information'];
			$stock_information = $clsISO->to_array_json($stock_information);
			$DT_TT = $core->get_field($stock_information, "DT_TT", 0);
			if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
				$property_type = '_RANGE';
				$sop_type = _SOP_TYPE_LOWFLOOR; 
			} else {
				$property_type = '_BUILDING';
				$sop_type = _SOP_TYPE_HIGHLEVEL;
			}
			$oneSop['stock_id'] = $oneStock[$clsStock->pkey];
			$oneSop['sop_type'] = $sop_type;
			$oneSop['project_id'] = $project_id;
			$oneSop['block_id'] = $block_id;
			$oneSop['building_id'] = $building_id;
			$oneSop['bedroom_id'] = $bedroom_id;
			$oneSop['home_direction_id'] = $home_direction_id;
			$oneSop['DT_TT'] = $DT_TT;
			$oneSop['code'] = $oneStock['code'];
			$oneSop['floor'] = $oneStock['floor'];
			$more_information['DT_TT'] = $DT_TT;
			#
			$field = "{$clsProperty->pkey},`title`";
			$list_sop_blocks = $clsProperty->getAll("is_trash=0 and `property_type`='_BLOCK' AND for_id='{$project_id}'", $field);
			$list_sop_buildings = $clsProperty->getAll("is_trash=0 and `property_type`='{$property_type}' AND for_id='{$block_id}'", $field);
			###
			if($building_id > 0){
				$arr_titles['building_name'] = sprintf('toà %s', $clsProperty->getTitle($building_id));
			}
			if($building_id > 0){
				$arr_titles['home_direction_name'] = sprintf('hướng %s', $clsProperty->getTitle($home_direction_id));
			}
			$arr_titles['DT_TT'] = sprintf('%s m2', $DT_TT);
		} else {
			$project_id = _PROJECT_DEF_ID; 
			$list_sop_blocks = $list_sop_buildings = array();
			$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID);
			$field = "`t1`.{$clsProperty->pkey},`t1`.`for_id` as `block_id`,`t2`.`for_id` as `project_id`";
			$field.= ",`t1`.`property_code`,`t2`.`parent_id` as `stock_type`";
			// $dbconn->debug = true;
			$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsProperty->tbl} AS t1 
			INNER JOIN {$clsProperty->tbl} AS t2 ON `t1`.`for_id`=t2.`property_id` 
			WHERE `t1`.`property_type`='_BUILDING' AND `t2`.`for_id` IN (".implode(',', $arr_projects).")");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$property_code = $val['property_code'];
					if(startsWithIgnoreSpecial($stock_code, $property_code)){
						$building_id = $val[$clsProperty->pkey];
						$block_id = (int) $val['block_id'];
						$project_id = (int) $val['project_id'];
						$stock_type = (int) $val['stock_type'];
						if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
							$property_type = '_RANGE';
							$sop_type = _SOP_TYPE_LOWFLOOR; 
						} else {
							$property_type = '_BUILDING';
							$sop_type = _SOP_TYPE_HIGHLEVEL;
						}
						$oneSop['sop_type'] = $sop_type;
						$oneSop['stock_type'] = $stock_type;
						$oneSop['project_id'] = $project_id;
						$oneSop['block_id'] = $block_id;
						$oneSop['building_id'] = $building_id;
						#- Danh sách building
						$field = "{$clsProperty->pkey},`title`";
						$list_sop_buildings = $clsProperty->getAll("`property_type`='{$property_type}' AND `for_id`='{$block_id}'", $field);
						if($building_id > 0){
							$arr_titles['building_name'] = sprintf('toà, %s', $clsProperty->getTitle($building_id));
						}
					}
				}
				unset($tmp);
			}
			$field = "{$clsProperty->pkey},`title`";
			$list_sop_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `for_id`='{$project_id}'", $field);
			// $clsISO->print_pre($list_sop_blocks); die();
			if(isset($stock_logs['loai_can']) && !empty($stock_logs['loai_can']) && $sop_type == _SOP_TYPE_HIGHLEVEL){
				$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_BEDROOM' 
				AND `slug`='".$core->replaceSpace($stock_logs['loai_can'])."'", $clsProperty->pkey);
				if(!empty($tmp)){
					$oneSop['bedroom_id'] = $tmp[$clsProperty->pkey];
					unset($tmp);
				}
			}
		}
	}
	$oneSop['title'] = "Cần bán " . implode(', ', array_map(function($v) {
		// Bỏ khoảng trắng và dấu phẩy thừa
		return preg_replace('/\s*,\s*/', ' ', trim($v));
	}, $arr_titles));
	$smarty->assign('sop_id', $sop_id);
	$smarty->assign('oneSop', $oneSop);
	$smarty->assign('sop_chatlog_id', $sop_chatlog_id);
	$smarty->assign('list_sop_blocks', $list_sop_blocks);
	$smarty->assign('list_sop_buildings', $list_sop_buildings);
	$smarty->assign('more_information', $more_information);
	##
	$sql_project = "`is_trash`=0";
	if($sop_type == _SOP_TYPE_HIGHLEVEL) {
		$sql_project.= " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			OR `list_block_type` LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($sop_type > 0) {
		$sql_project.= " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
			OR `list_block_type` LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}		
	$field = "{$clsProject->pkey},`title`,`more_information`";
	$list_sop_projects = $clsProject->getAll("{$sql_project} AND `project_id` IN (".implode(",",_PROJECT_OCEAN_CITY).")", $field);
	$smarty->assign('list_sop_projects', $list_sop_projects);
	// Return
	$html = $core->build('_ajax.open_sop.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_sop(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsSopChatLog = new SopChatLog();
	$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID);
	##
	$msg = "_error";
	$sop_id = (int) Input::post('sop_id', 0);
	$sop_chatlog_id = (int) Input::post('sop_chatlog_id', 0);
	$stock_logs = $clsSopChatLog->getOneField('stock_logs', $sop_chatlog_id);
	$stock_logs = $clsISO->to_array_json($stock_logs);
	##
	$_PostData = $_POST;
	// $clsISO->print_pre($_PostData); die();
	$sop_type = (int) $core->get_field($_PostData, 'sop_type', _SOP_TYPE_HIGHLEVEL);
	$project_id = (int) $core->get_field($_PostData, 'project_id', _PROJECT_DEF_ID);
	$block_id = (int) $core->get_field($_PostData, 'block_id', 0);
	$building_id = (int) $core->get_field($_PostData, 'building_id', 0);
	$bedroom_id = (int) $core->get_field($_PostData, 'bedroom_id', 0);
	$floor_count = $core->get_field($_PostData, 'floor_count', 0);
	$agency_id = (int) $core->get_field($_PostData, 'agency_id', 0);
	$type_villa_id = (int) $core->get_field($_PostData, 'type_villa_id', 0);
	
	$title = $core->get_field($_PostData, 'title', "");
	$stock_id = (int) $core->get_field($_PostData, 'stock_id', 0);
	$stock_code = $core->get_field($_PostData, 'stock_code', "");
	$bedroom_id = (int) $core->get_field($_PostData, 'bedroom_id', 0);
	$home_direction_id = (int) $core->get_field($_PostData, 'home_direction_id', 0);
	$floor = $core->get_field($_PostData, 'floor', "");
	$code = $core->get_field($_PostData, 'code', "");
	$DT_TT = $core->get_field($_PostData, 'DT_TT', "");
	$price = $core->get_field($_PostData, 'price', 0);
	$price = !empty($price) ? $clsISO->processSmartNumber($price) : 0;
	$price_m2 = !empty($price) && !empty($DT_TT) ? round($price/$clsISO->convertToNumber($DT_TT)) : 0;
	$price_owner = $core->get_field($_PostData, 'price_owner', 0);
	$price_owner = !empty($price_owner) ? $clsISO->processSmartNumber($price_owner) : 0;
	$need_id = (int) $core->get_field($_PostData, 'need_id', 0);
	$pass_door = $core->get_field($_PostData, 'pass_door', "");
	$view_id = (int) $core->get_field($_PostData, 'view_id', 0);
	$view_name = $core->get_field($_PostData, 'view_name', "");
	$content = $core->get_field($_PostData, 'content', "");
	$status_viewing_id = $core->get_field($_PostData, 'status_viewing_id', 0);
	$juridical_id = (int) $core->get_field($_PostData, 'juridical_id', 0);
	$interior_id = (int) $core->get_field($_PostData, 'interior_id', 0);
	$fee_included = (int) $core->get_field($_PostData, 'fee_included', 0);
	if($sop_id > 0){
		
	} else {
		$sop_id = $clsSop->getMaxId();
		$more_information = array(
			'sop_type' 	=> $sop_type,
			// 'sop_code' => $sop_code,
			// 'stock_hidden_code' => $stock_hidden_code,
			'hide_code' => _STOCK_HIDECODE_FLOOR_ID,
			'pass_door' => $pass_door,
			'view_id' => $view_id,
			'view_name' => $view_name,
			'DT_TT' => $DT_TT,
			'content' => $content,
			'agency_id' => $agency_id,
			'need_id' => $need_id,
			'type_villa_id' => $type_villa_id,
			'status_viewing_id' => $status_viewing_id,
			'juridical_id' => $juridical_id,
			'interior_id' => $interior_id,
			'fee_included' => $fee_included,
			'floor_count' => $floor_count,
			'sop_chatlog_id' => $sop_chatlog_id,
			'contact_name' => _SOP_CONTACT_NAME,
			'contact_phone' => _SOP_CONTACT_PHONE,
			'notes' => "",
			'images' => "",
			'video_type' => 'upload',
			'is_furnished' 	=> 0,
			'is_owner' 		=> 0,
			'is_exclusive' 	=> 0,
			'is_locked' 	=> 0,
			'is_solded' 	=> 0,
			'is_deleted' 	=> 0
		);
		if($clsSop->insert(array(
			$clsSop->pkey => $sop_id,
			'sop_type' => $sop_type,
			'project_id' => $project_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'agency_id' => $agency_id,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'need_id' => $need_id,
			'stock_id' => $stock_id,
			'stock_code' => $stock_code,
			'bedroom_id' => $bedroom_id,
			'home_direction_id' => $home_direction_id,
			'floor' => $floor,
			'code' => $code,
			'price' => $price,
			'price_m2' => $price_m2,
			'price_owner' => $price_owner,
			'fee_included' => $fee_included,
			'juridical_id' => $juridical_id,
			'interior_id' => $interior_id,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id' => _PROFILE_SOP_ADMIN_ID,
			'user_id_update' => _PROFILE_SOP_ADMIN_ID,
			'is_online' => 1,
			'is_locked' => 0,
			'is_solded' => 0,
			'reg_date' => time(),
			'upd_date' => time(),
		))){
			$msg = "_success";
			$stock_logs['sop_logs'] = array(
				'sop_id' => $sop_id,
				'stock_code' => $stock_code,
				'reg_date' => time()
			);
			$clsSopChatLog->updateOne($sop_chatlog_id, array(
				'stock_logs' => json_encode($stock_logs, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_load_form_field(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$type = Input::post("field","");
	$action = Input::post("action","");
	$sop_type = (int)Input::post("sop_type",_SOP_TYPE_HIGHLEVEL);
	###
	$sql_project = $sql_block = "`is_trash`=0";
	if($sop_type == _SOP_TYPE_HIGHLEVEL) {
		$sql_block.= " AND `parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
		$sql_project.= " AND (`block_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' OR `list_block_type` LIKE '%|"._BLOCK_TYPE_HIGHLEVEL_SALE."|%')";
	}else if($sop_type == _SOP_TYPE_LOWFLOOR) {
		$sql_block.= " AND `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
		$sql_project.= " AND (`block_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' OR `list_block_type` LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%')";
	}
	$html = "<option value='0'>Chọn</option>";
	$field = "{$clsProperty->pkey},`title`";
	if($type == "project_id"){
		$project_id = (int) Input::post("project_id",0);
		$field = "{$clsProject->pkey},`title`,`code`";
		$tmp = $clsProject->getAll("{$sql_project} AND `project_id` IN (".implode(",",_PROJECT_OCEAN_CITY).")", $field);
		$html = "<option value=''>Dự án</option>";
		if(!empty($tmp)){
			foreach($tmp as $k => $v){
				$html.= "<option value='{$v[$clsProject->pkey]}'".(($project_id == $v[$clsProject->pkey])?" selected":"").">{$v['title']}</option>";
			}
			unset($tmp);
		}
	}else if($type == "block_id"){
		$project_id = (int) Input::post("project_id",0);
		$block_id = (int) Input::post("block_id",0);
		$tmp = $clsProperty->getAll("{$sql_block} AND `for_id`='{$project_id}'", $field);
		$html = "<option value=''>Phân khu</option>";
		if(!empty($tmp)){
			foreach($tmp as $k => $v){
				$html .= "<option value='{$v['property_id']}'".(($block_id == $v['property_id'])?" selected":"").">{$v['title']}</option>";
			}
			unset($tmp);
		}
	}else if($type == "building_id"){
		$block_id = (int) Input::post("block_id",0);
		$building_id = (int) Input::post("building_id",0);
		$stock_type = $clsProperty->getOneField('parent_id', $block_id);
		$cond = " AND `property_type`='_BUILDING'";
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$cond = " AND `property_type`='_RANGE'";
		}
		$html = "<option value=''>Toà/Dãy</option>";
		$tmp = $clsProperty->getAll("`is_trash`=0 and `for_id`='{$block_id}'".$cond, $field);
		if(!empty($tmp)){
			foreach($tmp as $k => $v){
				$html .= "<option value='{$v['property_id']}'".(($building_id == $v['property_id'])?" selected":"").">{$v['title']}</option>";
			}
			unset($tmp);
		}
	}else if($type == "floor_range"){
		$html = "<option value=''>Tầng</option>";
		$building_id = (int)Input::post("building_id",0);
		if(!empty($building_id)){
			$tmp = $clsProperty->getOne($building_id,"more_information");
			$more_information = $tmp['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
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
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'html'		=>	$html,
	)); die();
}
