<?php 
function default_default(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$classTable = "Service";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	###
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$smarty->assign('list_projects', $list_projects);
	###
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		$project_id = (int) Input::post('project_id', 0);
		$block_id = (int) Input::post('block_id', 0);
		$building_id = (int) Input::post('building_id', 0);
		$cat_id = (int) Input::post('cat_id', 0);
		if($project_id > 0) {
			$link .= '&project_id='.$project_id;
			$hasCond = true;
		}
		if($block_id > 0) {
			if($clsProperty->countItem("`property_type`='_BLOCK' and `for_id`='{$project_id}' and `property_id`='{$block_id}'") > 0){
				$link .= '&block_id='.$block_id;
			}
		}
		if($building_id > 0) {
			$link .= '&building_id='.$building_id;
		}
		if($cat_id > 0) {
			$link .= '&cat_id='.$cat_id;
		}
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$project_id = (int) Input::get('project_id', 0);
	$block_id = (int) Input::get('block_id', 0);
	$building_id = (int) Input::get('building_id', 0);
	$cat_id = (int) Input::get('cat_id', 0);
	$keyword = Input::get('keyword', "");
	$assign_list["cat_id"] = $cat_id;
	$assign_list["building_id"] = $building_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["project_id"] = $project_id;
	$assign_list["keyword"] = $keyword;
	
	$list_blocks = $list_buildings = array();
	if($project_id > 0){
		$list_blocks = $clsProperty->getOItems('_BLOCK', $project_id,"");
	}
	if($block_id > 0){
		$list_buildings = $clsProperty->getOItems('_BUILDING', $block_id, "");
	}
	$assign_list["list_blocks"] = $list_blocks;
	$assign_list["list_buildings"] = $list_buildings;
	/*List all item*/
	$cond = "1='1'";
	if($cat_id > 0){
		$pUrl.='&cat_id='.$cat_id;
		$cond .= " and `cat_id` LIKE '%|{$cat_id}|%'";
	}
	if($project_id > 0){
		$pUrl.='&project_id='.$project_id;
		$cond .= " and `project_id`='$project_id'";
	}
	if($block_id > 0){
		$pUrl.='&block_id='.$block_id;
		$cond .= " and `block_id`='$block_id'";
	}
	if($building_id > 0){
		$pUrl.='&building_id='.$building_id;
		$cond .= " and `building_id`='$building_id'";
	}
	#Filter By Keyword
	if(!empty($keyword)){
		$cond .= " and (slug like '%".$core->replaceSpace($keyword)."%' or tags like '%|".$keyword."|%' or tags like '%|".$keyword."' or tags like '".$keyword."|%')";
	}
	$cond2 = $cond;
	if(!empty($type_list)){
		if($type_list=='Trash'){
			$cond .= " and is_trash=1";
		} else {
			$cond .= " and is_trash=0";
		}
	}
	$orderBy = " reg_date DESC";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 50;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsClassTable->countItem($cond);
	$pUrl .= '&page='.$current_page;
	
	$link_page_current = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
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
		if($tmp[0]!='page'&&$tmp[0]!='vpc_status')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;
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
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
//	var_dump($allItem);die;
	if(!empty($allItem)){
		$arr_property_cached = array();
		foreach($allItem as $key => $val){
			$cat_id = $clsISO->getArrayByTextSlash($val['cat_id']);
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$building_id = (int) $val['building_id'];
			$more_information = !empty($val['more_information']) ? $clsISO->to_array_json($val['more_information']) : [];
			$arr_cat_name = [];
			if(!empty($cat_id)) {
				$lstCat = $clsProperty->getAll("property_id IN (".implode(',',$cat_id).")",$clsProperty->pkey.',title');	
				foreach ($lstCat as $k_cat => $v_cat) {
					$arr_cat_name[] = $v_cat['title'];
				}
			}			
			$allItem[$key]['cat_name'] = implode(" | ",$arr_cat_name);
			$allItem[$key]['address'] = $more_information["address"];
			$allItem[$key]['phone'] = $more_information["phone"];
			if($project_id > 0){
				if(!isset($arr_property_cached[$project_id])){
					$arr_property_cached[$project_id] = $clsProject->getTitle($project_id);
				}
				$allItem[$key]['project_name'] = $clsProject->getTitle($project_id);
			} else {
				$allItem[$key]['project_name'] = '';
			}
			if($block_id > 0){
				if(!isset($arr_property_cached[$block_id])){
					$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
				}
				$allItem[$key]['block_name'] = $arr_property_cached[$block_id];
			} else {
				$allItem[$key]['block_name'] = '';
			}
			if($building_id > 0){
				if(!isset($arr_property_cached[$building_id])){
					$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
				}
				$allItem[$key]['building_name'] = $arr_property_cached[$building_id];
			} else {
				$allItem[$key]['building_name'] = '';
			}
			$allItem[$key]['tags'] = str_replace("|",", ",$val['tags']);
		}
	}
	//$clsISO->print_pre($allItem); die();
	$assign_list["total_record"] = $total_record;
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
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
		$cat_ids = $clsISO->getArrayByTextSlash($oneService['cat_id']);
		$block_id = $oneService['block_id'];
		$project_id = $oneService['project_id'];
		##
		$more_information = $oneService['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		###
		$field = "{$clsProperty->pkey},`title`";
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
		$list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' and `for_id`='{$block_id}'", $field);
		##
	}
	$smarty->assign('action', $action);
	$smarty->assign('service_id', $service_id);
	$smarty->assign('cat_ids', $cat_ids);
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
function default_search_tag(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsService = new Service(); 
	
	$results = array();
	$list_tags = $clsService->getAll("`is_online`=1 and `tags`<>''", "tags");
	if(!empty($list_tags)){
		foreach($list_tags as $key => $val){
			$tags = $val['tags'];
			$arr_tags = @explode('|', $tags);
			foreach($arr_tags as $tag){
				$results[] = array(
					'id' => $tag,
					'text' => $tag
				);
			}
		}
		unset($list_tags);
	}
	// Return
	echo json_encode($results); die();
}
function default_load_block(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	##
	$project_id = (int) Input::post('project_id', 0);
	$field = "{$clsProperty->pkey},title";
	$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
	##
	$html_options = '<option value="0">Phân khu/Block</option>';
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>'; 
		}
	}
	// Return
	echo $html_options; die();
}
function default_load_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	###
	$block_id = Input::post('block_id');
	$property_type = '_BUILDING';
	if($clsProperty->getOneField('parent_id', $block_id) == _BLOCK_TYPE_LOWFLOOR_SALE){
		$property_type = '_RANGE';
	}
	$field = "{$clsProperty->pkey},title";
	$cond = "`property_type`='{$property_type}' and `for_id`='{$block_id}'";
	$list_buildings = $clsProperty->getAll($cond, $field);
	##
	$html_options = '<option value="0">Chọn toà nhà</option>';
	if(!empty($list_buildings)){
		foreach($list_buildings as $okey => $oval){
			$html_options.= '<option value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
		}
		unset($list_buildings);
	}
	// Return
	echo $html_options; die();
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
	$project_id = (int) Input::post('project_id', 0);
	$project_id = ($project_id > 0) ? $project_id : _VHOP1;
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
		$cat_ids = Input::post('cat_id', array());
		if($clsClassTable->updateOne($pvalTable, array(
			'name' => $name,
			'slug'	=>	$core->replaceSpace($name),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'cat_id' => $clsISO->makeSlashListFromArrayRoot($cat_ids),
			'project_id' => $project_id,
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
			'cat_id' => $clsISO->makeSlashListFromArrayRoot($cat_ids),
			'project_id' => $project_id,
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
	$classTable = "Service";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		exit();
	}
}
function default_check_stock_code(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
		,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$clsStock = new Stock();
	###
	$msg = "_valid";
	$stock_code = Input::post('stock_code');
	if(!empty($stock_code)){
		if($clsStock->countItem("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `ms_code`='{$stock_code}'") == 0){
			$msg = "_invalid";
		}
	}
	// Return
	echo $msg; die();
}
function isEmptyRow($row) {
    foreach($row as $cell){
        if (null !== $cell) return false;
    }
    return true;
}
function default_import_file(){
//	ini_set('display_errors',1);
//	error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	$classTable = "Service";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$msg = "error";
	if(is_uploaded_file($_FILES['import_file']['tmp_name'])){
		$target_dir = ROOTPATH."/tmp/";
		$file_ext = explode('.',basename($_FILES["import_file"]["name"]));
		$file_ext = strtolower(end($file_ext));
		$target_file = $target_dir . time().'.'.$file_ext;
		if (@move_uploaded_file($_FILES["import_file"]["tmp_name"], $target_file)) {
			$html = '';
			$inputFileName = $target_file;
			require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
			require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
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
			###
			$index = 0; $tblData =array();
			for($row = 2; $row <= $highestRow; ++ $row) {
				$rowData = $worksheet->rangeToArray('A'. $row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
				if(isEmptyRow(reset($rowData))) { continue; } // skip empty row
				for($col = 1; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					if(PHPExcel_Shared_Date::isDateTime($cell)){
						if(!empty($cell)){
							$date = trim($cell->getValue());
							$date = PHPExcel_Shared_Date::ExcelToPHPObject();
							$tblData[$index][] = date_format($date,'d/m/Y');
						}else{
							$tblData[$index][] = trim($cell->getValue());
						}
					}else{
						if($cell->isFormula()){
							$tblData[$index][] = trim($cell->getCalculatedValue());
						} else {
							$tblData[$index][] = trim($cell->getValue());
						}
					}
				}
				++$index;
			}
			@unlink($inputFileName);
//			$clsISO->print_pre($tblData); die();
			if(!empty($tblData)){
				for($i=0; $i<count($tblData); $i++){
					$lst_cat = $tblData[$i][0];
					$arr_cat = explode("|",$lst_cat);
					$cat_ids = [];
					foreach ($arr_cat as $cat_title) {
						$slug_cat = $core->replaceSpace($cat_title);
						$oneCat = $clsProperty->getByCond("slug='{$slug_cat}'",$clsProperty->pkey);
						if(!empty($oneCat)) {
							$cat_ids[] = $oneCat[$clsProperty->pkey];
						}
					}
					$lst_cat_ids = $clsISO->makeSlashListFromArrayRoot($cat_ids);

					$name = $tblData[$i][1];
					$slug = $core->replaceSpace($name);
					$phone = $tblData[$i][2];
					$address = $tblData[$i][3];
					$lst_tag = $tblData[$i][4];
					$project_code = $tblData[$i][5];
					$oneProject = $clsProject->getByCond("code='{$project_code}'",$clsProject->pkey);
					if(!empty($oneProject)) {
						$project_id = $oneProject[$clsProject->pkey];
					}else{
						$project_id = _VHOP1;
					}
					$tags = str_replace(", ","|",$lst_tag);
					$more_information = array(
						"phone"			=>	$phone,
						'stock_code'	=> "",
						'intro' 		=> "",
						'image' 		=> "",
						"address"		=>	$address
					);
					$arr_data = [
						$pkeyTable 			=> $clsClassTable->getMaxId(),
						"name"				=>	$name,
						"slug"				=>	$slug,
						"cat_id"			=>	$lst_cat_ids,
						"project_id"		=>	$project_id,
						"tags"				=>	$tags,
						"more_information"	=>	json_encode($more_information),
						"reg_date" 			=> time(),
						"is_online" 		=> 1,
					];
					###
					if($clsClassTable->insert($arr_data)){
						$msg = "_success";
					}
		//			die;
				}
			}
		}
	}
	
	// Return
	echo $msg; die();
}
?>