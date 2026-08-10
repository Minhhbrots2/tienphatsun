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

function default_list_leasing(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType,$oneProfile,$type_list;
	$clsLeasing = new Leasing();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	### 
	$approved = Input::post('approved','');
	$keyword = Input::post('keyword');
	$user_id = (int) Input::post('user_id', 0);
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
	$cond = "1=1";
	$ret_url = "/cho-thue.html";
	$url = $ret_url;
	
	if($keyword != ""){
		$cond.= " AND (`title` like '%{$keyword}%' or `stock_code` like '%{$keyword}%')";
		$url .= "&keyword=".$keyword;
	}
	if($user_id > 0) {
		$cond.= " and `user_id`='{$user_id}'";
		$url .= "&user=".$user_id;
	}
	#- Filter sop type
	if($type_id == _TYPE_HIGHLEVEL) {
		$cond.= " and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and JSON_EXTRACT(`more_information`,\"$.sop_type\") = '".$type_id."'";
		$url .= "&type=".$type_id;
	}else if($type_id == _TYPE_LOWFLOOR) {
		$cond.= " and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and JSON_EXTRACT(`more_information`,\"$.sop_type\") = '".$type_id."'";
		$url .= "&type=".$type_id;
	}
	if($approved != "") {
		$cond.= " and `is_online`='".$approved."'";
		$url .= "&approved=".$approved;
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
				$html.= '<label class="we-radio we-radio-search" for="rdo_md_'.$val[$clsProject->pkey].'">
							<input type="radio" onChange="$Core.leasing.select_project(this, event)" class="js__option-project" toId="js__dropdown-block" tp="radio" id="rdo_md_'.$val[$clsProject->pkey].'" name="project_id" value="'.$val[$clsProject->pkey].'" >
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
				$html.= '<label class="we-radio we-radio-search" for="rdo_'.$val[$clsProperty->pkey].'">
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
				$html.= '<label class="form-check form-check-dark w-50 mb-2" for="chk_'.$val[$clsProperty->pkey].'"> 
							<input class="form-check-input js__option-building" type="checkbox" name="building_id[]" value="'.$val[$clsProperty->pkey].'" id="chk_'.$val[$clsProperty->pkey].'">
							<span class="form-check-label">'.$val['title'].'</span>
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
			$content = sprintf("%s đã %s tin cho thuê <strong>%s</strong> của bạn","Quản trị viên", $title, $oneLeasing['title']) ;
			$clsNotify->insertNotify($clsLeasing->tbl, $clsLeasing->pkey, $leasing_id, $content, time(), sprintf('|%s|', $oneLeasing['user_id']));
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