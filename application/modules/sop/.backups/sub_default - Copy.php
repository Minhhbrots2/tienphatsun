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
	,$description_page,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$cmd;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsProperty', $clsProperty);
	##
	$scriptJs = "";
	$cmd = Input::get('cmd', "");
	$current_page = $_SERVER['REQUEST_URI'];
	if($cmd=="_detail"){
		$current_page = "/cn/";
		$sop_id = Input::get('sop_id', 0);
		$field = "`more_information`,`is_online`,`is_solded`,`stock_code`";
		$oneSop = $clsSop->getOne($sop_id, $field);
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if(isset($more_information['images']) && !empty($more_information['images'])){
			$image_page = PCMS_URL.reset($more_information['images']);
		}
		$url = $clsSop->getLink($sop_id,$oneSop['stock_code']);
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->insert(array(
			'profile_id' => $profile_id,
			'url' => $url,
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'reg_date' => time()
		));
		
		if($oneSop['is_soled'] || $oneSop['is_online']==0){
			$current_page= "/cn/me/";
		}
		$scriptJs.= '<a class="autoclick_'.$sop_id.'"" stock_code="'.$more_information['stock_code'].'" sop_id="'.$sop_id.'" 
		onClick="$Core.sop.open_sop(this, event)"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_'.$sop_id.'\').trigger(\'click\').remove();
				}, 200);
			});
		</script>';
		/*=============Title & Description Page==================*/
		$assign_list["image_page"] = $image_page;
		$title_page = $more_information['title_page'];
		$assign_list["title_page"] = $title_page;
		$description_page = $more_information['description_page'];
		$assign_list["description_page"] = $description_page;
	} else {
		/*=============Title & Description Page==================*/
		$title_page = ' Chuyển nhượng Ocean Park - Hệ thống giao dịch thứ cấp Ocean City - My Ocean City - '.PAGE_NAME;
		$assign_list["title_page"] = $title_page;
		$description_page = ' Hàng nghìn giao dịch uy tín trên hệ thống chuyển nhượng My OCean City - '.PAGE_NAME;
		$assign_list["description_page"] = $description_page;
	}
	$smarty->assign("scriptJs",$scriptJs);
	$smarty->assign("current_page",$current_page);
	##
	$_ss_view = 'grid';
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
	echo 1; die();
}
function default_list_sop(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsSopLog = new SopLog();
	### 
	$www = (int) Input::post('www', 1920);
	$keyword = Input::post('keyword');
	$user_id = (int) Input::post('user_id', 0);
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
	$type_list = Input::post('type_list', 'publish');
	$sort_by = Input::post('sort_by', ($type_list=='publish'?"date_asc":"reg_date"));
	###
	$_ss_view = 'grid';
	if(vnSessionExist('_ss_view')){
		$_ss_view = vnSessionGetVar('_ss_view');
	}
	###
	if($type_list=='me'){
		if($profile_id != "289"){
			$cond.= "`t1`.`user_id`='{$profile_id}'";	
		}else{
			$cond = "1=1";
		}
		$ret_url = "/cn/me/";
	} else if($type_list=='manager'){
		$cond = "1=1";
		$ret_url = "/cn/manager/";
	} else {
		$ret_url = "/cn/";
		$cond = "`t1`.`is_trash`=0 and `t1`.`is_solded`=0  and `t1`.`is_online`='1'";
	}
	$cond.= " and `t2`.`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
	if(!empty($keyword)) $cond.=" and `t1`.`stock_code` like '%{$keyword}%'";
	if($user_id > 0) $cond.= " and `t1`.`user_id`='{$user_id}'";
	#- Filter Block
	if($block_id > 0) $cond.=" and (`t2`.`block_id`='{$block_id}')";
	#- Filter Status
	if(!empty($status_ids)) $cond.= " and (`t1`.`status_id` in (".implode(',', $status_ids)."))";
	#- Filter Building
	if(!empty($building_ids)) $cond.= " and (`t2`.`building_id` in (".implode(',', $building_ids)."))";
	#- Filter Bedroom
	if(!empty($bedroom_ids)) $cond.= " and (`t2`.`bedroom_id` in (".implode(',', $bedroom_ids)."))";
	#- Filter Direction
	if(!empty($home_direction_ids)) $cond.= " and (`t2`.`home_direction_id` in (".implode(',', $home_direction_ids)."))";
	#- Filter Price
	if($price_min > 0 && $price_max == 0){
		$cond.= " and (`t1`.`price`>='{$price_max}')";
	} else if($price_min == 0 && $price_max > 0){
		$cond.= " and (`t1`.`price`<='{$price_max}')";
	} else if($price_min > 0 && $price_max > 0){
		$cond.= " and (`t1`.`price` between '{$price_min}' and '{$price_max}')";
	}
	if(!empty($floor_range)){
		$list_floors = array();
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
			$cond.= " and (`t2`.`floor` in ('".implode('\',\'', $list_floors)."'))";
		}
	}
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page', ($www > 1600 ? 16 : 12));
	$total_record = $dbconn->getOne("select count(1) as total_record from {$clsSop->tbl} as `t1` 
		inner join {$clsStock->tbl} as `t2` on `t1`.`stock_code`=`t2`.`ms_code` where {$cond}");
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$stock_field = "t1.{$clsSop->pkey},`t1`.`title`,`t1`.`stock_code`,`t1`.`price`,`t1`.`more_information`,`t1`.`contact_name`
	,`t1`.`contact_phone`,`t2`.`stock_id`,`t2`.`more_information` as `stock_information`,`t2`.`stock_type`,`t2`.`bedroom_id`
	,`t2`.`home_direction_id`,`t1`.`is_verified`,`t2`.`code`,`t2`.`floor`,`t2`.`block_id`,`t2`.`building_id`,`t1`.`is_locked`
	,`t1`.`is_online`,`t1`.`is_solded`,`t1`.`status_id`,`t1`.`upd_date`";
	###
	$order_by = " order by `t1`.`is_solded` ASC,`t1`.`upd_date` DESC";
	if($sort_by=='date_asc'){
		$order_by = " order by `t1`.`is_solded` ASC, `t1`.`upd_date` ASC";
	} else if($sort_by=='date_desc'){
		$order_by = " order by `t1`.`is_solded` ASC, `t1`.`upd_date` DESC";
	} else if($sort_by=='price_asc'){
		$order_by = " order by `t1`.`is_solded` DESC, `t1`.`price` ASC";
	}  else if($sort_by=='price_desc'){
		$order_by = " order by `t1`.`is_solded` DESC, `t1`.`price` DESC";
	}
	$list_sop = $dbconn->getAll("select {$stock_field} from {$clsSop->tbl} as `t1` 
		inner join {$clsStock->tbl} as `t2` on `t1`.`stock_id`=`t2`.`stock_id` 
		where {$cond}".$order_by.$limitCond);
	if(!empty($list_sop)){
		$arr_status_cached = $arr_property_cached = array();
		foreach($list_sop as $key => $val){
			$block_id = $val['block_id'];
			$building_id = $val['building_id'];
			$status_id = $val['status_id'];
			$bedroom_id = $val['bedroom_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$stock_information = $val['stock_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$stock_information = $clsISO->to_array_json($stock_information);
			$list_sop[$key]['more_information'] = $more_information;
			$list_sop[$key]['stock_information'] = $stock_information;
			$liked = $clsSop->checkLiked($val[$clsSop->pkey]) ? 1 : 0;
			$list_sop[$key]['liked'] = $liked;
			###
			if($status_id > 0 && !isset($arr_status_cached[$status_id])){
				$oProperty = $clsProperty->getOne($status_id, "title,bgcolor,textcolor");
				$arr_status_cached[$status_id] = sprintf(
					'<span class="awe__sop-badge zindex-2" style="background:%s; color:%s">%s</span>', 
					$oProperty['bgcolor'], $oProperty['textcolor'], $oProperty['title']
				);
			}
			if($status_id > 0 && isset($arr_status_cached[$status_id])){
				$list_sop[$key]['label_status'] = $arr_status_cached[$status_id];
			} else {
				$list_sop[$key]['label_status'] = "";
			}
			$images = isset($more_information['images']) ? $more_information['images'] : array();
			$list_sop[$key]['images'] = $images;
			###
			if($block_id > 0 && !isset($arr_property_cached[$block_id])){
				$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
			} else {
				$arr_property_cached[$block_id] = "";
			}
			if($building_id > 0 && !isset($arr_property_cached[$building_id])){
				$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
			} else {
				$arr_property_cached[$building_id] = "";
			}
			if($bedroom_id > 0 && !isset($arr_property_cached[$bedroom_id])){
				$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
			}
			if($home_direction_id > 0 && !isset($arr_property_cached[$home_direction_id])){
				$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
			}
			$list_sop[$key]['block_name'] = $arr_property_cached[$block_id];
			$list_sop[$key]['building_name'] = $arr_property_cached[$building_id];
			$list_sop[$key]['bedroom'] = $arr_property_cached[$bedroom_id];
			$list_sop[$key]['home_direction'] = $arr_property_cached[$home_direction_id];
		}
	}
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsSopLog', $clsSopLog);
	$smarty->assign('ret_url', $ret_url);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('list_sop', $list_sop);
	$smarty->assign('_ss_view', $_ss_view);
	$smarty->assign('type_list', $type_list);
	$smarty->assign('deviceType', $deviceType);
	// Return
	$html = $core->build('_ajax.sop.tpl');
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_record' => $total_record
	)); die();
}	
function default_load_block(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	##
	$project_id = (int) Input::post('project_id', 0);
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'", $field);
	##
	$html_options = '<option value="0">Phân khu/Block</option>';
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html_options.= '<option value="'.$val[$clsProperty->pkey].'"'.($val[$clsProperty->pkey]==_BLOCK_DEF_ID?' selected':'').'>'.$val['title'].'</option>'; 
		}
	}
	// Return
	echo $html_options; die();
}
function default_load_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO;
	$clsProperty = new Property();
	###
	$html = "";
	$tp = Input::post('tp', 'option');
	$field = "{$clsProperty->pkey},title";
	if($tp == 'radio'){
		$block_id = Input::post('block_id', 0);
		$cond = "`property_type`='_BUILDING' and `for_id`='{$block_id}'";
		$list_buildings = $clsProperty->getAll($cond, $field);
		if(!empty($list_buildings)){
			foreach($list_buildings as $key => $val){
				$html.= '<label class="we-checkbox" for="chk_'.$val[$clsProperty->pkey].'">
					<input type="checkbox" id="chk_'.$val[$clsProperty->pkey].'" name="building_id[]" value="'.$val[$clsProperty->pkey].'">
					<span>'.$val['title'].'</span>
				</label>';
			}
			unset($list_buildings);
		}
	} else {
		$list_block_ids = Input::post('list_block_ids');
		if(!empty($list_block_ids)){
			foreach($list_block_ids as $key => $block_id){
				$field = "{$clsProperty->pkey},title";
				$cond = "property_type='_BUILDING' and for_id='{$block_id}'";
				$list_buildings = $clsProperty->getAll($cond, $field);
				if(!empty($list_buildings)){
					$html.= '<optgroup label="'.$clsProperty->getTitle($block_id).'">';
					foreach($list_buildings as $okey => $oval){
						$html.= '<option value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
					}
					unset($list_buildings);
					$html.= '</optgroup>';
				}
			}
		}
	}
	// Return
	echo $html; die();
}
function default_edit(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$clsConfiguration,$loggedIn;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsProperty', $clsProperty);
	if($loggedIn!= 1 || ($loggedIn==1 && $profile_id == _PROFILE_GENERAL_ID)){
		header('Location:/dang-nhap/ret=/cn/add');
		exit();
	}
	###
	$_token = CSRF::generate('upload-video');
	$smarty->assign('_token', $_token);
	###
	$list_property = $clsProperty->getCacheItems('_HIDECODE');
	$smarty->assign('list_property', $list_property);
	$list_needs = $clsProperty->getCacheItems('_NEED_TYPE');
	$smarty->assign('list_needs', $list_needs);
	$list_phaply = $clsProperty->getCacheItems('_JURIDICAL');
	$smarty->assign('list_phaply', $list_phaply);
	$list_devices = $clsProperty->getCacheItems('_DEVICE');
	$list_devices = $clsISO->get_array_snippet($list_devices);
	$smarty->assign('list_devices', $list_devices);
	###
	$sop_id = Input::get('sop_id', 0);
	$return_url = Input::get('return_url', '/cn/me/');
	if(!$return_url) $return_url = '/cn/me/';
	$action = Input::get('action', "action");
	###
	$total_images = 0;
	$oneSop = $more_information = $device_ids = array(
		'hide_code' => _STOCK_HIDECODE_NO_ID,
		'video_type' => "",
		'having_ns' => 'no',
		'interior_id' => 0,
		'juridical_id' => 0,
		'fee_included' => 0
	);
	if($action=='edit'){
		$oneSop = $clsSop->getOne($sop_id);
		if($oneSop['user_id'] != $profile_id && !$clsISO->checkPermission('edit_transfer')){
			header('Location:' . $return_url);
			exit();
		}
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if(!isset($more_information['video_type']) || (isset($more_information['video_type']) && empty($more_information['video_type'])))
			$more_information['video_type'] = "";
		if(!isset($more_information['having_ns']) || (isset($more_information['having_ns']) && empty($more_information['having_ns']))) 
			$more_information['having_ns'] = "no";
		$device_ids = isset($more_information['device_ids']) 
			? $more_information['device_ids'] : array();
		$total_images = isset($more_information['images']) 
			? count($more_information['images']) : 0;
	}
	$smarty->assign('sop_id', $sop_id);
	$smarty->assign('action', $action);
	$smarty->assign('oneSop', $oneSop);
	$smarty->assign('total_images', $total_images);
	$smarty->assign('device_ids', $device_ids);
	$smarty->assign('more_information', $more_information);
	if(isset($_POST['hid']) && $_POST['hid']=='hid'){
		$title = Input::post('title');
		$video = Input::post('video');
		$images = Input::post('images');
		$having_ns = Input::post('having_ns', 'no');
		$device_ids = Input::post('device_ids', []);
		if(!empty($video)) $more_information['video'] = $video;
		if(!empty($images)) $more_information['images'] = $images;
		$more_information['hide_code'] = Input::post('hide_code');
		$more_information['content'] = Input::post('content');
		$more_information['host_name'] = Input::post('host_name');
		$more_information['host_phone'] = Input::post('host_phone');
		$more_information['having_ns'] = Input::post('having_ns');
		$more_information['device_ids'] = Input::post('device_ids');
		$more_information['video_type'] = Input::post('video_type', 'youtube');
		$more_information['video_url'] = Input::post('video_url');
		$more_information['youtue_url'] = Input::post('youtue_url');
		$sop_moderation = $clsConfiguration->getValue('sop_moderation', 1);
		if($sop_id > 0){
			if($clsSop->updateOne($sop_id, array(
				'title' => $title,
				'slug' => $core->replaceSpace($title),
				'status_id' => Input::post('status_id'),
				'stock_id' => Input::post('stock_id', 0),
				'stock_code' => Input::post('stock_code'),
				'price' => $clsISO->processSmartNumber(Input::post('price')),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'contact_name' => Input::post('contact_name'),
				'contact_phone' => Input::post('contact_phone'),
				'fee_included' => Input::post('fee_included',0),
				'interior_id' => Input::post('interior_id', 0),
				'juridical_id' => Input::post('juridical_id', 0),
				'upd_date' => time(),
				'user_id_update' => $profile_id,
				'is_online' => ((int) $sop_moderation==1?1:0)
			))){
				$msg = "_success";
				// Update SEO & Meta Tag
				$clsSop->updateMeta($sop_id);
			}
		} else {
			$sop_id = $clsSop->getMaxId();
			if($clsSop->insert(array(
				$clsSop->pkey => $sop_id,
				'title' => $title,
				'slug' => $core->replaceSpace($title),
				'status_id' => Input::post('status_id'),
				'stock_id' => Input::post('stock_id', 0),
				'stock_code' => Input::post('stock_code'),
				'price' => $clsISO->processSmartNumber(Input::post('price')),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'contact_name' => Input::post('contact_name'),
				'contact_phone' => Input::post('contact_phone'),
				'fee_included' => Input::post('fee_included',0),
				'interior_id' => Input::post('interior_id', 0),
				'juridical_id' => Input::post('juridical_id', 0),
				'reg_date' => time(),
				'upd_date' => time(),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'is_online' => ((int) $sop_moderation==1?1:0)
			))){
				$msg = "_success";
				// Update SEO & Meta Tag
				$clsSop->updateMeta($sop_id);
			}
		}
		if($msg=='_success'){
			vnSessionSetVar('_ss_add_stock', 1);
			header('Location:'.$return_url);
			exit();
		}
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Đăng bán chuyển nhượng Ocean Park - Hệ thống giao dịch thứ cấp Ocean City - My Ocean City '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Hàng nghìn giao dịch uy tín trên hệ thống chuyển nhượng My OCean City - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_open(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsShop = new Shop();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsProject = new Project();
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	###
	$uid = $clsISO->getUniqid();
	$sop_id = Input::post('sop_id', 0);
	$stock_id = Input::post('stock_id', 0);
	$type_list = Input::post('type_list', 'publish');
	$return_url = Input::post('return_url');
	$return_url = str_replace(DOMAIN_URL, '', $return_url);
	// $clsISO->print_pre($return_url); die();
	$smarty->assign('uid', $uid);
	$smarty->assign('sop_id', $sop_id);
	$smarty->assign('stock_id', $stock_id);
	$smarty->assign('type_list', $type_list);
	$smarty->assign('return_url', $return_url);
	$field = "`t1`.`stock_code`,`t1`.`more_information`,`t1`.`user_id`,`t1`.`upd_date`,`t2`.`more_information` as `stock_information`";
	$field.= ",`t1`.`contact_phone`,`t1`.`contact_name`,`t1`.`price`,`t1`.`fee_included`,`t1`.`interior_id`,`t2`.`block_id`,`t2`.`home_direction_id`";
	$field.= ",`t2`.`bedroom_id`,`t2`.`building_id`,`t2`.`floor`,`t2`.`code`,`t1`.`is_locked`,`t1`.`is_solded`";
	$field.= ",`t2`.`project_id`,`t1`.`title`,`t1`.`is_verified`,`t1`.`juridical_id`";
	###
	$oneSop = $dbconn->getRow("select {$field} from `{$clsSop->tbl}` as `t1` 
		inner join {$clsStock->tbl} as `t2` on `t1`.`stock_id`=`t2`.`stock_id` 
		where `t1`.`sop_id`='{$sop_id}'");
	$user_id = $oneSop['user_id'];
	$building_id = $oneSop['building_id'];
	$more_information = $oneSop['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$stock_information = $oneSop['stock_information'];
	$stock_information = $clsISO->to_array_json($stock_information);
	$oneSop['more_information'] = $more_information;
	$oneSop['stock_information'] = $stock_information;
	###
	$total_shops = $clsShop->countItem("`building_id`='{$building_id}'");	
	$smarty->assign('total_shops', $total_shops);
	#
	$oProfile = $clsProfile->getOne($user_id, "full_name,first_name,last_name,avatar");
	$oProfile[$clsProfile->pkey] = $user_id;
	$smarty->assign('oProfile', $oProfile);
	#-Giá/m2
	$price_m2 = 0;
	$DT_TT = $stock_information['DT_TT'];
	$price = $clsISO->processSmartNumber($oneSop['price']);
	if($price > 0 && !empty($DT_TT)){
		$price_m2 = $price / $clsISO->convertToNumber($DT_TT);
	}
	$smarty->assign('price_m2', $price_m2);
	###
	$total_medias = 0;
	$list_medias = array();
	if(isset($more_information['images']) && !empty($more_information['images'])){
		$total_medias = count($more_information['images']);
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
	$smarty->assign('total_medias', $total_medias);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('stock_information', $stock_information);
	// $clsISO->print_pre($more_information); die();
	$list_devices = array();
	if(isset($more_information['having_ns']) 
		&& $more_information['having_ns'] == 'yes'){
		$device_ids = isset($more_information['device_ids']) && !empty($more_information['device_ids']) 
			? $more_information['device_ids'] : array();
		$list_devices = $clsProperty->getCacheItems('_DEVICE');
		$list_devices = $clsISO->get_array_snippet($list_devices);
		if(!empty($list_devices)){
			foreach($list_devices as $key => $val){
				$list_child = $val['list_child'];
				if(!empty($list_child)){
					foreach($list_child as $okey => $oval){
						if(in_array($oval['property_id'], $device_ids)){
							// Has
						} else {
							unset($list_child[$okey]);
						}
					}
					$list_child = !empty($list_child) ? @array_values($list_child) : array();
					if(!empty($list_child)){
						$list_devices[$key]['list_child'] = $list_child;
					} else {
						unset($list_devices[$key]);
					}
				}
			}
		}
	}
	// $clsISO->print_pre($list_devices); die();
	$smarty->assign('list_devices', $list_devices);
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
        'sop_id' => $sop_id,
		'total_medias' => $total_medias
	)); die();
}
function default_delete(){
	 global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsProperty = new Property();
	###
	$icon = ""; $msg = "_error";
	$sop_id = Input::post('sop_id', 0);
	if($sop_id > 0){
		$oneSop = $clsSop->getOne($sop_id, "more_information");
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);	
		if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1){
			$is_trash = 0;
			unset($more_information['is_deleted']);
			$html = '<a class="dropdown-item sop__menu-delete-'.$sop_id.'" onClick="$Core.sop.delete(this, event)" sop_id="'.$sop_id.'" href="javascript:void(0);"><i class="material-icons-outlined">delete</i> Xoá</a>';
		} else {
			$is_trash = 1;
			$more_information['is_deleted'] = 1;
			$html = '<a class="dropdown-item sop__menu-delete-'.$sop_id.'" onClick="$Core.sop.delete(this, event)" sop_id="'.$sop_id.'" href="javascript:void(0);"><i class="material-icons-outlined">settings_backup_restore</i> Khôi phục</a>';
		}
		if($clsSop->updateOne($sop_id, array(
			'upd_date' => time(),
			'is_trash' => $is_trash,
			//'is_online' => $is_online,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$icon = $clsSop->getIcon($sop_id);
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
function default_mark_lock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsSop = new Sop();
	$clsNotify = new Notify();
	$clsProperty = new Property();
	###
	$html = ""; $icon = ""; $msg = "_error";
	$sop_id = Input::post('sop_id', 0);
	if($sop_id > 0){
		$oSop = $clsSop->getOne($sop_id, "`is_locked`,`logs`,`more_information`,`user_id`,`title`");
		$logs = $oSop['logs'];
		$more_information = $oSop['more_information'];
		$logs = $clsISO->to_array_json($logs);
		$more_information = $clsISO->to_array_json($more_information);
		// $clsISO->print_pre($more_information); die();
		$is_locked = ($oSop['is_locked']==1 ? 0 : 1);
		$more_information['is_locked'] = $is_locked;
		$logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'to_value' => $is_locked,
			'field' => 'is_locked'
		);
		if($clsSop->updateOne($sop_id, array(
			'upd_date' => time(),
			'is_locked' => $is_locked,
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			if($oSop['is_locked'] == 0){
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-lock-'.$sop_id.'" onclick="$Core.sop.mark_lock(this, event)" 
				sop_id="'.$sop_id.'"><i class="material-icons-outlined">lock_open</i> Mở khóa</a>';
				$icon = "<i data-bs-toggle=\"tooltip\" data-bs-trigger=\"hover\" title=\"Đã khóa\" class=\"material-icons-outlined fs-small text-danger\">lock</i>";
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã lock tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã lock tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			} else {
				$icon = $clsSop->getIcon($sop_id);
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-lock-'.$sop_id.'" onclick="$Core.sop.mark_lock(this, event)" 
				sop_id="'.$sop_id.'"><i class="material-icons-outlined">lock</i> Khoá</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã hủy lock tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã hủy lock tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			}
			$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), $list_user_notify_ids);
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
	$clsNotify = new Notify();
	$clsProperty = new Property();
	###
	$html = ""; $msg = "_error";
	$sop_id = Input::post('sop_id', 0);
	if($sop_id > 0){
		$oSop = $clsSop->getOne($sop_id, "`is_solded`,`more_information`,`logs`,`user_id`,`title`");
		$logs = $oSop['logs'];
		$more_information = $oSop['more_information'];
		$logs = $clsISO->to_array_json($logs);
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
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			if($oSop['is_solded'] == 0){
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-sold-'.$sop_id.'" onclick="$Core.sop.mark_sold(this,event)" sop_id="'.$sop_id.'"><i class="material-icons-outlined">add_business</i> Mở bán</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã báo bán tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã báo bán tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			} else {
				$html = '<a href="javascript:void(0);" class="dropdown-item sop__menu-sold-'.$sop_id.'" onclick="$Core.sop.mark_sold(this, event)" 
				sop_id="'.$sop_id.'"><i class="material-icons-outlined">storefront</i> Báo bán</a>';
				if($profile_id == $oSop['user_id']){
					$list_user_notify_ids = sprintf('|%s|', implode('|', $clsSop->getUserNotify()));
					$content = sprintf("<strong>%s</strong> đã hủy bán bán tin chuyển nhượng <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oSop['title']);
				} else {
					$list_user_notify_ids = sprintf('|%s|', $oSop['user_id']);
					$content = sprintf("Quản trị viên đã hủy báo bán tin chuyển nhượng <strong>%s</strong> của bạn", $oSop['title']);
				}
			}
			$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), $list_user_notify_ids);
			$icon = $clsSop->getIcon($sop_id);
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
function default_upload_image(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
    $clsSop = new Sop();
	$clsProperty = new Property();
    #
	$total_images = $total_upload = 0;
	$html = '';  $msg = "_error"; 
    if(!empty($_FILES['images'])){
        $images = $_FILES['images'];
		$total_images = (int) Input::post('total_images', 0);
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
						<a class="delete" src="'.$image.'" onClick="$Core.sop.delete_image(this, event)">x</a>
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
		$sop_id = (int) Input::post('sop_id', 0);
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
						<a href="javascript:void(0);" sop_id="'.$sop_id.'" class="btn-link text-main fs-11" onClick="$Core.sop.cancel_video(this, event)"><i class="bx bx-x"></i> Xóa video</a>
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
	$clsSop = new Sop();
	$sop_id = (int) Input::post('sop_id', 0);
	if($sop_id  > 0){
		$more_information = $clsSop->getOneField('more_information', $sop_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['video_url'] = "";
		$clsSop->updateOne($sop_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE) 
		));
	}
	$html = '<div class="we-filedrop h-px-250 rounded-1">
		<div title="Chọn video cần tải nên" onClick="$Core.sop.select_video(this, event)" 
		toId="sop__select-video" class="pt-5 text-center cursor-pointer">
			'.ICON_UPLOAD.'
			<p class="mb-0 text-muted">Bấm để chọn video cần tải lên</p>
		</div>
	</div>';
	echo $html; die();
}
function default_check_stock_code(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
	,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor;
	$clsSop = new Sop();
	$clsStock = new Stock();
	###
	$stock_id = 0; $msg = "_invalid";
	$stock_code = Input::post('stock_code');
	if(!empty($stock_code)){
		$oStock = $clsStock->getByCond("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `ms_code`='{$stock_code}'", $clsStock->pkey);
		if(!empty($oStock)){
			$msg = "_valid";
			$stock_id  = $oStock[$clsStock->pkey];
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'stock_id' => $stock_id
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
	###
	$clsSop = new Sop();
	$clsNotify = new Notify();
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
		$icon = $clsSop->getIcon($sop_id);
		if($tp=='confirm_refuse'){
			if(!empty($reason_not_approved)){
				$content = sprintf("Quản trị viên đã từ chối tin chuyển nhượng <strong>%s</strong> của bạn với lý do <strong class=\"text-main\">%s</strong>", $oneSop['title'], $reason_not_approved);
			} else {
				$content = sprintf("Quản trị viên đã từ chối tin chuyển nhượng <strong>%s</strong> của bạn", $oneSop['title']) ;
			}
			$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), sprintf('|%s|', $oneSop['user_id']));
		} else if($tp=='agree'){
			$content = sprintf("Quản trị viên đã phê duyệt tin chuyển nhượng <strong>%s</strong> của bạn", $oneSop['title']) ;
			$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), sprintf('|%s|', $oneSop['user_id']));
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'icon' => $icon
	)); die();
}
function default_update_click(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$loggedIn;
	###
	$msg = "_error";
	$clsSop = new Sop();
	$clsProfile = new Profile();
	$type = Input::post('type', 'view');
	$sop_id = (int)Input::post('sop_id', 0);
	$oSop = $clsSop->getOne($sop_id,'more_information,stock_code');
	if(!empty($oSop)){
		#update cache
		$url = $clsSop->getLink($sop_id,$oSop['stock_code']);
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->insert(array(
			'profile_id' => $profile_id,
			'url' => $url,
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'reg_date' => time()
		));
		
		$more_information = $oSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$number_view = (!empty($more_information['number_view']))?$more_information['number_view']:0;
		$click_call = (!empty($more_information['click_call']))?$more_information['click_call']:0;
		$click_zalo = (!empty($more_information['click_zalo']))?$more_information['click_zalo']:0;
		// var_dump($more_information);die;
		if($type == "view"){
			$more_information['number_view'] = ($number_view + 1);
		}else if($type == "call"){
			$more_information['click_call'] = ($click_call + 1);
		}else if($type == "zalo"){
			$more_information['click_zalo'] = ($click_zalo + 1);
		}
		// $clsISO->print_pre($more_information); die();
		if($clsSop->updateOne($sop_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_like(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	###
	$clsSop = new Sop();
	$clsProfile = new Profile();
	$type = Input::post('type', 'like');
	$sop_id = (int)Input::post('sop_id', 0);
	
	$msg = "_error";
	$result = false;
	$oProfile = $clsProfile->getOne($profile_id, "more_information");
	$more_information = $oProfile['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$liked_sop = isset($more_information['liked_sop']) 
		? $more_information['liked_sop'] : array();
	// $clsISO->print_pre($liked_sop); die();
	if($sop_id > 0){
		if($type=='like'){
			$liked_sop[]= $sop_id;
			$title = "Bỏ thích";
		} else {
			$key = array_search($sop_id, $liked_sop);
			unset($liked_sop[$key]);
			$title = "Thích";
		}
		$more_information['liked_sop'] = array_values($liked_sop);
		if($clsProfile->updateOne($profile_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			$result = true;
		}
	}
	// Return
	echo json_encode(array(
		"result"	=>	$result,
		'msg' => $msg,
		'title' => $title
	)); die();
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
	$list_shops = $clsShop->getAll("`building_id`='{$building_id}'");	
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
	$sop_id = (int)Input::post("sop_id",0);
	$clsSop = new Sop();	
	$clsProperty = new Property();
	
	$oneSop = $clsSop->getOne($sop_id,"more_information");
	$more_information = $oneSop['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	
	$list_devices = array();
	if(isset($more_information['having_ns']) 
		&& $more_information['having_ns'] == 'yes'){
		$device_ids = isset($more_information['device_ids']) && !empty($more_information['device_ids']) 
			? $more_information['device_ids'] : array();
		$list_devices = $clsProperty->getCacheItems('_DEVICE');
		$list_devices = $clsISO->get_array_snippet($list_devices);
		if(!empty($list_devices)){
			foreach($list_devices as $key => $val){
				$list_child = $val['list_child'];
				if(!empty($list_child)){
					foreach($list_child as $okey => $oval){
						if(in_array($oval['property_id'], $device_ids)){
							// Has
						} else {
							unset($list_child[$okey]);
						}
					}
					$list_child = !empty($list_child) ? @array_values($list_child) : array();
					if(!empty($list_child)){
						$list_devices[$key]['list_child'] = $list_child;
					} else {
						unset($list_devices[$key]);
					}
				}
			}
		}
	}
	// $clsISO->print_pre($list_devices); die();
	$smarty->assign('list_devices', $list_devices);
//	var_dump($list_devices);die;
	
	$total_utilities = 0;
	foreach($list_devices as $key => $value){
		$total_utilities += count($value['list_child']);
	}
	$assign_list['total_utilities'] = $total_utilities;
	$assign_list['list_devices'] = $list_devices;
	// Return
	$html = $core->build('_ajax.utilities.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
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
			$title = ($is_verified==1) ? 'xác minh' : 'chưa xác minh';
			$content = sprintf("Quản trị viên đã %s tin chuyển nhượng <strong>%s</strong> của bạn", $title, $oneSop['title']) ;
			$clsNotify->insertNotify($clsSop->tbl, $clsSop->pkey, $sop_id, $content, time(), sprintf('|%s|', $oneSop['user_id']));
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
function default_addLog(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	$clsSopLog = new SopLog();
	$clsProfile = new Profile();
	$data = ['result' =>	false];
	$sop_id = (int)Input::post("sop_id",0);
	$type = Input::post("type","");
	$content = [];
	if($sop_id > 0 && $type != ""){
		$log_id = 0;
		$getOneLog = $clsSopLog->getAll("sop_id='{$sop_id}' LIMIT 0,1",$clsSopLog->pkey.",sop_id,type,content,reg_date,is_trash");		
		if(!empty($getOneLog)){
			$sopLog = $getOneLog[0];
			$content = $clsISO->to_array_json($sopLog['content']);
			$log_id = $sopLog['id'];
		}
//		var_dump($getOneLog);die;
		$content[] = [
			'type'			=>	$type,
			'user_id' 		=> 	$profile_id,
			'user_name' 	=> 	($profile_id > 0)?$clsProfile->getFullName($profile_id,$oneProfile):"Khách",
			'reg_date'		=>	time(),
			'user_ip' 		=> 	$_SERVER['REMOTE_ADDR'],
		];
		$data = [
			'sop_id' 		=> 	$sop_id,
			'user_id'		=>	$profile_id,
			'content'		=>	json_encode($content),
			'reg_date' 		=> 	time(),
			'is_trash'		=>	0
		];
		
		if($log_id > 0){
			if($clsSopLog->updateOne($log_id, $data)){
				$data = ['result' =>	true];
			}
		}else{
			$data[$clsSopLog->pkey] = $clsSopLog->getMaxId();
			if($clsSopLog->insert($data)){
				$data = ['result' =>	true];
			}
		}
	}	
	
	echo json_encode($data); die();
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
	$html = "<option value=''>Chọn</option>";
	$field = "{$clsProperty->pkey},title";
	if($type == "building_id"){
		$block_id = (int)Input::post("block_id",0);
		$list_buildings = $clsProperty->getAll("property_type='_BUILDING' and for_id='".$block_id."'", $field);
		$smarty->assign('list_buildings', $list_buildings);
		$html = "<option value=''>Toà nhà</option>";
		foreach($list_buildings as $k => $v){
			$html .= "<option value='{$v['property_id']}'>{$v['title']}</option>";
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
				$html .= "<option value='{$i}'>{$title_floor}</option>";
			}
		}
	}
	echo $html;die;
}