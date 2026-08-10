<?php
function map_map_FH(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsUser = new User();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStockShape = new StockShape();
	###
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$project_id = (int) Input::get('project_id', _PROJECT_VHOP2_ID);
	$block_id = (int) Input::get('block_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	$assign_list['stock_type'] = $stock_type;
	$assign_list['project_id'] = $project_id;
	$assign_list['block_id'] = $block_id;
	$assign_list['building_id'] = $building_id;
	###
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_trash=0 and list_block_type like '%|{$stock_type}|%'", $field);
	$assign_list['list_projects'] = $list_projects;
	// $clsISO->print_pre($list_projects); die();
	if(isset($_POST['hid']) && $_POST['hid'] == 'hid'){
		$stock_type = (int) Input::post('stock_type', 0);
		$project_id = (int) Input::post('project_id', 0);
		$block_id = (int) Input::post('block_id', 0);
		$building_id = (int) Input::post('building_id', 0);
		$link = sprintf('index.php?mod=%s&sub=map&act=%s&stock_type=%s&project_id=%s&block_id=%s', 
			$mod, $act, $stock_type, $project_id, $block_id);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && $building_id > 0){
			$link.= sprintf('&building_id=%s', $building_id);
		}
		// Header
		header('Location: ' . $link);
		exit();
	}
	$list_blocks = $list_buildings = array();
	if($project_id > 0){
		$field = "{$clsProperty->pkey},title";
		$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
		and `for_id`='{$project_id}' and `parent_id`='{$stock_type}'", $field);
		$assign_list['list_blocks'] = $list_blocks;
	}
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && $block_id > 0){
		$field = "{$clsProperty->pkey},title";
		$list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
			and for_id='{$block_id}' order by `order_no` ASC", $field);
		$assign_list['list_buildings'] = $list_buildings;
	}
	$image_map_src = "";
	$more_information = $clsProperty->getOneField('more_information', $building_id);
	// $clsISO->print_pre($more_information); die();
	$more_information = $clsISO->to_array_json($more_information);
	$image_map_src = isset($more_information['layout_map_FH']) ? $more_information['layout_map_FH'] : "";
	$assign_list['image_map_src'] = $image_map_src;
	
	$tmp = $clsStockShape->getByCond("`holderG`='stock_FH' and `stock_type`='{$stock_type}' 
		and `project_id`='{$project_id}' and `block_id`='{$block_id}' and `building_id`='{$building_id}'");
	if(!empty($tmp)){
		$shapes = $tmp['shapes'];
		$shapes = $clsISO->to_array_json($shapes);
		if(!empty($shapes)){
			foreach($shapes as $key => $val){
				if($val['shape_id'] == $shape_id){
					$_oShape = $val;
					$has_data = 1;
				}else if(!empty($val['code'])){
					$arr_code[] = $val['code'];
				}
				
			}
		}
	}
	$assign_list["shapes"] = $shapes;
	//$clsISO->print_pre($shapes);die;
}
function map_open_map(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockShape = new StockShape();
	$clsStock = new Stock();
	###
	$uid = $clsISO->getUniqid();
	$_leaflet_id = Input::get('_leaflet_id', 0);
	$holderG = "stock_FH";
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$type = Input::post('type', "_ADD");
	$shape_id = Input::post('shape_id', "");
	$html_options = '<option value="0">Chọn block</option>';
	$_oShape = array('stock_id' => 0, 'stock_code' => '');
	
	$tmp = $clsStockShape->getByCond("`holderG`='stock_FH' and `stock_type`='{$stock_type}' 
		and `project_id`='{$project_id}' and `block_id`='{$block_id}' and `building_id`='{$building_id}'");	
	
	$oneBuilding = $clsProperty->getOne($building_id);
	$more_information = $clsISO->to_array_json($oneBuilding["more_information"]);
	$number_house = !empty($more_information['number_house']) ? $more_information['number_house'] : 0;
	$has_data = 0;
	$arr_code = [];
	if(!empty($tmp)){
		$shapes = $tmp['shapes'];
		$shapes = $clsISO->to_array_json($shapes);
		if(!empty($shapes)){
			foreach($shapes as $key => $val){
				if($key == $shape_id){
					$_oShape = $val;
					$has_data = 1;
				}else if(!empty($val['code'])){
					$arr_code[] = $val['code'];
				}
				
			}
		}
	}
	$html_code = "";
	
	for($i=1; $i <= (int)$more_information['number_house']; $i++) {
		if(($building_id == 10372 || $building_id == 10374) && $i==7) {
			$code = "06A";
		}else{
			$code = $clsStock->getCode($i);	
		}
		
		if(!in_array((string)$code,$arr_code)) {
			$html_code .= '<option'.(($_oShape['code']==(string)$code) ? ' selected' : '').' value="'.$code.'">'.$code.'</option>';	
		}		
	}
	$smarty->assign("project_id",$project_id);
	$smarty->assign("block_id",$block_id);
	$smarty->assign("building_id",$building_id);
	$smarty->assign("html_code",$html_code);
	$smarty->assign("type",$type);
	$smarty->assign("shape_id",$shape_id);
	// Return
	$html = $core->build('map'.DS.'_ajax.open_map.tpl');
	echo json_encode(array(
		"html"	=>	$html,
		"uid"	=>	$clsISO->getUniqid()
	)); die();
}
function map_save_map(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsUser = new User();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStockShape = new StockShape();
	$clsStock = new Stock();
	###
	$msg  = "_error";
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$shape_id = (int) Input::post('shape_id', 0);
	$top = (float) Input::post('top', 0);
	$left = (float) Input::post('left', 0);
	$action = (float) Input::post('action', 0);
	$code = Input::post('code', "");
//	$clsISO->print_pre($_POST);die;
	$cond = "`stock_type`='".$stock_type."' and `project_id`='{$project_id}' AND holderG='stock_FH'";
	if($block_id > 0) $cond.= " and `block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " and `building_id`='{$building_id}'";
	$tmp = $clsStockShape->getByCond($cond);
	$shapes = !empty($tmp['shapes']) ? $clsISO->to_array_json($tmp['shapes']) : array();
	$shapes_render = !empty($tmp['shapes_render']) ? $clsISO->to_array_json($tmp['shapes_render']) : array();
	$shap_id = (!empty($shape_id)) ? $shape_id : $clsISO->getUniqid();
	$data_shape = [
		"code"	=>	$code,
		"top"	=>	$top,
		"left"	=>	$left,
	];
	$shapes[$shap_id] = $data_shape;
	/*if(!empty($shapes)) {
		$arr_shapes = array();
		$arr_shapes = $clsISO->to_array_json($shapes);
		$arr_shapes_id=[];
		foreach($arr_shapes as $key => $val) {
			$shape_id = $val['shape_id'];
			$arr_shapes_id[] = $shape_id;
			$lstStock = $clsStock->getAll("`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `code`='".$val['code']."' AND `building_id`='{$building_id}' AND `agency_id`='"._AGENCY_FH_ID."'");
			if(!empty($lstStock)) {
				foreach($lstStock as $k => $v) {
					$stock_id = $v['stock_id'];
					if(empty($shapes_render[$shape_id][$stock_id])) {
						$shapes_render_stock = $val;
						$shapes_render_stock["stock_id"] = $stock_id;
						$shapes_render[$shape_id][$stock_id]= $shapes_render_stock;
					}						
				}
			}else{
				unset($shapes_render[$shape_id]);
			}	
			unset($arr_shapes[$key]['html_shapes']);
		}
		$shapes_render = array_filter($shapes_render, function ($value, $key) use ($arr_shapes_id) {
			return in_array((int)$key, $arr_shapes_id);
		}, ARRAY_FILTER_USE_BOTH);
		$shapes = json_encode($arr_shapes);
	}*/
	$html = "";
	if(!empty($tmp)){
		if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
			'shapes' => json_encode($shapes),
			'user_id_update' => $core->_USER['user_id'],
			'upd_date' => time()
		))){
			$msg = "_success";
			$html = '<div id="item_drag_'.$shap_id.'" shap_id="'.$shap_id.'" class="draggable item_drag" project_id="'.$project_id.'" block_id="'.$block_id.'" building_id="'.$building_id.'" code="'.$code.'" style="top:'.$top.'%;left:'.$left.'%;\'">'.$code.'</div>';
		}
	} else {
		if($clsStockShape->insert(array(
			$clsStockShape->pkey => $clsStockShape->getMaxId(),
			'holderG' => "stock_FH",
			'stock_type' => $stock_type,
			'project_id' => $project_id,
			'building_id' => $building_id,
			'block_id' => $block_id,
			'shapes' => $shapes,
			'shapes_render' => json_encode($shapes_render),
			'user_id' => $core->_USER['user_id'],
			'user_id_update' => $core->_USER['user_id'],
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$html = '<div id="item_drag_'.$shap_id.'" shap_id="'.$shap_id.'" class="draggable item_drag" project_id="'.$project_id.'" block_id="'.$block_id.'" building_id="'.$building_id.'" code="'.$code.'" style="top:'.$top.'%;left:'.$left.'%;\'">'.$code.'</div>';
		}
	}
	
	// Return
	echo json_encode(array(
		"html"	=>	$html,
		"shap_id"	=>	$shap_id
	)); die();
}
function map_update_shape(){
	global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
	$clsUser = new User();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStockShape = new StockShape();
	$clsStock = new Stock();
	###
	$msg  = "_error";
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$shapes = Input::post('shapes', "");
	//$clsISO->print_pre($_POST);die;
	$cond = "`stock_type`='".$stock_type."' and `project_id`='{$project_id}' AND holderG='stock_FH'";
	if($block_id > 0) $cond.= " and `block_id`='{$block_id}'";
	if($building_id > 0) $cond.= " and `building_id`='{$building_id}'";
	$tmp = $clsStockShape->getByCond($cond);
	//$clsISO->print_pre($tmp);die;
	$shapes_render = array();
	if(!empty($tmp)){
		if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
			'shapes' => json_encode($shapes),
			'user_id_update' => $core->_USER['user_id'],
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		"shap_id"	=>	$shap_id
	)); die();
}
?>