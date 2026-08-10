<?php 
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting
		,$clsConfiguration,$core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	
	$field = "{$clsProperty->pkey},title";
	$list_block_type = $clsProperty->getAll("property_type='_BLOCK_TYPE' order by order_no ASC", $field);
	$assign_list["list_block_type"] = $list_block_type;
	/*Get type of list news*/
	$type_list = Input::get('type_list');
	$assign_list["type_list"] = $type_list;
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$link = '';
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$classTable = "Policy";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*List all item*/
	$cond = "1='1'";
	#Filter By Keyword
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and title like '%".$keyword."%'";
	}
	$assign_list["pUrl"] = $pUrl;
	$cond2 = $cond;
	if($type_list=='Active'){
		$cond .= " and is_trash=0";
	}
	if($type_list=='Trash'){
		$cond .= " and is_trash=1";
	}
	$orderBy = " reg_date desc";
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
	$listPageNumber =  array();
	for ($i=1; $i<=$totalPage; $i++){
		$listPageNumber[] = $i;
	}
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
	#
	$link_page_current_2 = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page'&&$tmp[0]!='type_list')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;
	#-------End Page Divide-----------------------------------------------------------
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	if(!empty($allItem)){
		$arr_property_cached = array();
		foreach($allItem as $key => $val){
			$block_type = $val['block_type'];
			if(!isset($arr_property_cached[$block_type])){
				$arr_property_cached[$block_type] = $clsProperty->getTitle($block_type);
			}
		}
		$assign_list["arr_property_cached"] = $arr_property_cached;
	}
	
	// $clsISO->print_pre($allItem); die();
	// print_r($cond." order by ".$orderBy.$limit);die();
	$assign_list["allItem"] = $allItem;
	
	$cmd = Input::get("cmd","");
	$project_id = (int)Input::get("project_id",0);
	$block_id = (int)Input::get("block_id",0);
	$building_id = (int)Input::get("building_id",0);
	$stock_type = Input::get("stock_type","");
	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	$script = "";
	if($cmd == "open") {
		$script = '<script>setTimeout(function(){
			$(".policy_'.$stock_type.'").trigger("click");
		},500);</script>';
	}
	$assign_list["script"] = $script;
}
function default_open_policy(){
	global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
	$user_id = $core->_USER['user_id'];
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsPolicy',$clsPolicy);
	$smarty->assign('clsProject',$clsProject);
	$smarty->assign('clsProperty',$clsProperty);
	##
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_trash=0",$field);
	$smarty->assign('list_projects',$list_projects);
	##
	$action = "_add";
	$list_scopes = array();
	$oneItem = $more_information = array('ms_date' => time());
	$policy_id = (int) Input::post('policy_id',0);
	$block_type = (int) Input::post('block_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	if($policy_id > 0){
		$action = '_edit';
		$oneItem = $clsPolicy->getOne($policy_id);
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$list_scopes = !empty($oneItem['scope']) 
			? json_decode($oneItem['scope'], true) : array();
		if(!empty($list_scopes)){
			foreach($list_scopes as $key => $val){
				$project_id = $val['project_id'];
				$list_blocks = $clsProperty->getOItems('_BLOCK', $project_id, 'parent_id,property_code');
				if($block_type==_BLOCK_TYPE_LOWFLOOR_SALE){
					$arrs_blocks = !empty($val['block_id']) ? $val['block_id'] : array();
					if(!empty($list_blocks)){
						foreach($list_blocks as $okey => $oval){
							if(in_array($oval[$clsProperty->pkey], $arrs_blocks)){
								$list_blocks[$okey]['selected'] = 1;
							} else {
								$list_blocks[$okey]['selected'] = 0;
							}
						}
					}
				} else {
					$block_id = $val['block_id'];
					$arrs_building = !empty($val['building_id']) ? $val['building_id'] : array();
					$list_buildings = $clsProperty->getOItems('_BUILDING', $block_id);
					if(!empty($list_buildings)){
						foreach($list_buildings as $okey => $oval){
							if(in_array($oval[$clsProperty->pkey], $arrs_building)){
								$list_buildings[$okey]['selected'] = 1;
							} else {
								$list_buildings[$okey]['selected'] = 0;
							}
						}
					}
					$list_scopes[$key]['list_buildings'] = $list_buildings;
				}
				$list_scopes[$key]['list_blocks'] = $list_blocks;
			}
		}
	}else{
		$project_id = (int) Input::post('project_id',0);
		$block_id = (int) Input::post('block_id',0);
		$building_id = (int) Input::post('building_id',0);
		$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'", "{$clsProperty->pkey},title");
		if(!empty($block_id) && $block_type==_BLOCK_TYPE_HIGHLEVEL_SALE) {
			$list_buildings = $clsProperty->getAll("property_type='_BUILDING' and for_id='{$block_id}'", "{$clsProperty->pkey},title");
			$smarty->assign('list_buildings',$list_buildings);
		}
		$smarty->assign('project_id',$project_id);
		$smarty->assign('block_id',$block_id);
		$smarty->assign('building_id',$building_id);
		$smarty->assign('list_blocks',$list_blocks);
	}
	//$clsISO->print_pre($list_scopes); die();
	$smarty->assign('action',$action);
	$smarty->assign('policy_id',$policy_id);
	$smarty->assign('block_type',$block_type);
	$smarty->assign('oneItem',$oneItem);
	$smarty->assign('more_information',$more_information);
	$smarty->assign('list_scopes',$list_scopes);
	// Return
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.policy.tpl');
	echo $html; die();
}
function default_upload_file(){
	// ini_set('display_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error"; $gg_id = "";
	if(!empty($_FILES['upload_file']['name'])){
		$folder_id = Input::post('folder_id');
		if(is_uploaded_file($_FILES['upload_file']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			// $clsISO->print_pre($clsUploadFile); die();
			$upload_file = $clsUploadFile->uploadItem($_FILES["upload_file"],"/PTG",EXTENSION_FILE_UPLOAD);
			if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
				$msg = "_success";
				// Set the file metadata for drive
				$title = 'FH_'.time().'_'.$_FILES["upload_file"]["name"];
				$mimeType = $_FILES["upload_file"]["type"];
				$clsGoogleDrive = new GoogleDrive();
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file, GOOGLE_DRIVE_FOLDER_PTG_ID);
				$gg_id = $createdFile->getId();
				@unlink(ROOTPATH . $upload_file);
			}
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'gg_id' => $gg_id
	)); die();
}
function default_add_scope(){
	global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
	$clsProject = new Project();
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_trash=0",$field);
	$block_type = (int) Input::post('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	
	$uid = $clsISO->getUniqid();
	$html = '<div class="scope_item scope_item_'.$uid.'">
		<div class="form-group form-row">
			<div class="col-md-6">
				<label class="col-form-label">Chọn dự án</label>
				<select uid="'.$uid.'"" class="form-control required iso-select2" onchange="load_option_block(this,event)" name="scope['.$uid.'][project_id]" toId="block_'.$uid.'" data-error="Chưa chọn dự án">
					<option valyue="0">Chọn dự án</option>';
					foreach($list_projects as $project){
						$html.= '<option value="'.$project[$clsProject->pkey].'">'.$project['title'].'</option>';
					}
				$html.= '</select>
			</div>
			<div class="col-md-6">
				<label class="col-form-label">Chọn phân khu</label>
				'.($block_type==_BLOCK_TYPE_LOWFLOOR_SALE?'<select uid="'.$uid.'"" class="form-control required iso-select2" id="block_'.$uid.'" toId="building_'.$uid.'" name="scope['.$uid.'][block_id][]" multiple="multiple" data-error="Chưa chọn phân khu">
					<option value="0">Chọn phân khu</option>
				</select>':'<select uid="'.$uid.'"" class="form-control required iso-select2" id="block_'.$uid.'" onchange="load_option_building(this,event)" toId="building_'.$uid.'" name="scope['.$uid.'][block_id]" data-error="Chưa chọn phân khu">
					<option value="0">Chọn phân khu</option>
				</select>').'
			</div>
		</div>
		'.($block_type==_BLOCK_TYPE_HIGHLEVEL_SALE?'<div id="building_group_'.$uid.'" class="form-group d-none">
			<label class="col-form-label">Chọn tòa áp dụng</label>
			<select class="form-control iso-select2" multiple="multiple" id="building_'.$uid.'" 
			data-placeholder="Chọn tòa nhà" name="scope['.$uid.'][building_id][]"></select>
		</div>':'').'
		<div class="d-flex">
			<button type="button" uid="'.$uid.'"" onClick="delete_scope(this,event)" class="btn btn-sm btn-default">'.$core->makeIcon('trash','Xóa').'</button>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_load_option_block(){
	global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$project_id = Input::post('project_id',0);
	$field = "{$clsProperty->pkey},parent_id,title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'",$field);
	##
	$html = sprintf('<option parent_id="%s" value="0">%s</option>',_BLOCK_TYPE_LOWFLOOR_SALE,'Lựa chọn phân phu');
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html.= sprintf(
				'<option parent_id="%s" value="%s">%s</option>',
				$val['parent_id'],
				$val[$clsProperty->pkey],
				$val['title']
			);
		}
	}
	// Return
	echo $html; die();
}
function default_load_option_building(){
	global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
	$clsProject = new Project();
	$clsProperty = new Property();
	##
	$block_id = Input::post('block_id',0);
	$call_from = Input::post('call_from', "_policy");
	$field = "{$clsProperty->pkey},title";
	$list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' and `for_id`='{$block_id}'",$field);
	##
	$html = "";
	if(!empty($list_buildings)){
		foreach($list_buildings as $key => $val){
			$html.= sprintf('<option value="%s">%s</option>',$val[$clsProperty->pkey],$val['title']);
		}
		unset($list_buildings);
	}
	// Return
	echo $html; die();
}
function default_pop_save_policy(){
	global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$policy_id = (int) Input::post('policy_id',0);
	$block_type = (int) Input::post('block_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$title = Input::post("title"); // Tên CSBH
	$intro = Input::post("intro"); // Giới thiệu CSBH
	$ms_date = Input::post('ms_date');
	$ms_date = !empty($ms_date) ? $clsISO->convertTextToTime($ms_date) : 0;
	$link_ns = Input::post('link_ns'); // Link CSBH
	$link_ms = Input::post('link_ms'); // Link PTG
	$scope = Input::post('scope');
	$applicable_fund_type = (int)Input::post('applicable_fund_type',0);
	#
	$scope_slash = "";
	if(!empty($scope)){
		foreach($scope as $key => $val){
			$project_id = isset($val['project_id']) ? (int)$val['project_id'] : 0;
			if($block_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
				$block_id = isset($val['block_id']) ? (int) $val['block_id'] : 0;
				$building_ids = isset($val['building_id']) && !empty($val['building_id']) ? $val['building_id'] : array();
				$m = sprintf('|%s_%s', $project_id, $block_id);
				if(!empty($building_ids)){
					foreach($building_ids as $id){
						$k = $m.'_'.$id.'|';
						$scope_slash.= $k;
					}
				} else {
					$scope_slash.= $m.'|';
				}
			} else {
				$block_ids = isset($val['block_id']) && !empty($val['block_id']) ? $val['block_id'] : array();
				if(!empty($block_ids)){
					foreach($block_ids as $block_id){
						$m = sprintf('|%s_%s', $project_id, $block_id);
						$scope_slash.= $m."|";
					}
				}
			}
		}
	}
	// $clsISO->print_pre($scope_slash); die();
	if($policy_id == 0){
		$policy_id = $clsPolicy->getMaxId();
		$more_information = array();
		$more_information['price_sheet_id'] = Input::post('price_sheet_id');
		$more_information['spreadsheet_cell_stock'] = Input::post('spreadsheet_cell_stock');
		if($clsPolicy->insert(array(
			$clsPolicy->pkey => $policy_id,
			'block_type' => $block_type,
			'title' => $title,
			'intro' => $intro,
			'ms_date' => $ms_date,
			'link_ns' => $link_ns,
			'link_ms' => $link_ms,
			'scope_slash' => $scope_slash,
			'applicable_fund_type' => $applicable_fund_type,
			'scope' => json_encode($scope,JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information,JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $core->_USER['user_id'],
			'user_id_update'=> $core->_USER['user_id']
		))){
			$msg = "_success";
			$clsPolicy->syncPolicyScope($policy_id, $scope);
		}
	} else {
		$more_information = $clsPolicy->getOneField('more_information', $policy_id);
		$more_information = $clsISO->to_array_json($more_information);	
		$more_information['price_sheet_id'] = Input::post('price_sheet_id');
		$more_information['spreadsheet_cell_stock'] = Input::post('spreadsheet_cell_stock');
		if($clsPolicy->updateOne($policy_id,array(
			'title' => $title,
			'intro' => $intro,
			'ms_date' => $ms_date,
			'link_ns' => $link_ns,
			'link_ms' => $link_ms,
			'scope_slash' => $scope_slash,
			'applicable_fund_type' => $applicable_fund_type,
			'scope' => json_encode($scope,JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information,JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update'=> $core->_USER['user_id']
		))){
			$msg = "_success";
			$clsPolicy->syncPolicyScope($policy_id, $scope);
		}
	}
	// Return
	echo $msg; die();
}
function default_delete(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Policy";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	$pvalTable = isset($_GET[$pkeyTable])? intval($_GET[$pkeyTable]) : 0;
	##
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	if($clsClassTable->deleteOne($pvalTable)){
		header('location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		exit();
	}
}