<?php
function isEmptyRow($row) {
    foreach($row as $cell){
        if (null !== $cell) return false;
    }
    return true;
}
function default_update_layout(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
    $clsStock = new Stock();
    $clsProject = new Project();
    $clsProperty = new Property();
    #- Require library
    require_once(DIR_INCLUDES.'/json_master/autoload.php');
    #- End require
    $inputFileName = ABSPATH . DS . '/Layout.xlsx';
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
    for($row = 1; $row <= $highestRow; ++ $row) {
        $rowData = $worksheet->rangeToArray('A'. $row.':'.$highestColumn.$row,NULL,TRUE,FALSE);
        if(isEmptyRow(reset($rowData))) { continue; } // skip empty row
        for($col = 0; $col < $highestColumnIndex; ++ $col) {
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
    if(!empty($tblData)){
        for($i=0; $i<count($tblData); $i++){
            $building_code = $tblData[$i][0];
            $stock_code = $tblData[$i][1];
            $layout_ns = $tblData[$i][2];
            if(!empty($layout_ns) && !empty($stock_code)){
                $stock_code = str_replace('CH','',$stock_code);
                // $clsISO->print_pre($stock_code); die();
                $field = "{$clsProperty->pkey},more_information";
                $tmp = $clsProperty->getByCond("`property_type`='_BUILDING' and `property_code`='{$building_code}' limit 0,1", $field);
                if(!empty($tmp)){
                    $more_information = $tmp['more_information'];
                    $more_information = !empty($more_information)
                        ? json_decode(html_entity_decode($more_information), true)
                        : array();
                    $template = isset($more_information['template']) && !empty($more_information['template'])
                        ? $more_information['template']
                        : array();
                    $clsISO->print_pre($template); die();
                    if(!empty($template)){
                        foreach($template as $key => $val){
                            if($val['code'] == $stock_code){
                                $template[$key]['layout_ns'] = $layout_ns;
                                $more_information['template'] = $template;
                                $clsProperty->updateOne($tmp[$clsProperty->pkey], array(
                                    'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
                                ));
                                break;
                                //$clsISO->print_pre($more_information); die();
                            }
                        }
                    }
                }
            }
        }
    }
    $clsISO->print_pre($tblData); die();
}
function default_default(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$dbconn;
    $assign_list["clsModule"] = $clsModule;
    $user_id = $core->_USER['user_id'];
    $clsProperty = new Property();
    $assign_list["clsProperty"] = $clsProperty;
    /*Get type of list news*/
    $type_list = isset($_GET['type_list']) ? $_GET['type_list'] : '';
    $assign_list["type_list"] = $type_list;
    /**/
    $classTable = "Project";
    $clsClassTable = new $classTable;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey ;
    $assign_list["clsClassTable"] = $clsClassTable;
    $assign_list["pkeyTable"] = $pkeyTable;
    /**/
    if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
        if($_POST['keyword']!=''&&$_POST['keyword']!='testimonial title,intro'){
            $link .= '&keyword='.$_POST['keyword'];
        }
        header('location: '.PCMS_URL.'/?mod='.$mod.$link);
    }
    /*List all item*/
    $cond = "1='1'";
    #Filter By Keyword
    if(isset($_GET['keyword'])){
        if($_GET['keyword'] !=''){
            $keyword = $core->replaceSpace($_GET['keyword']);
            $cond .= " and slug like '%".$keyword."%'";
            $assign_list["keyword"] = $_GET['keyword'];
        }
    }
    $cond2 = $cond;
    if($type_list=='Trash'){
        $cond .= " and is_trash=1";
    } else {
        $cond .= " and is_trash=0";
    }
    $orderBy = " reg_date desc";
    #-------Page Divide---------------------------------------------------------------
    # per_page qua URL (?per_page=): whitelist để chặn giá trị lạ đẩy limit lớn gây nặng query.
    $allowPerPage = array(20, 50, 100, 200);
    $recordPerPage = (int) (isset($_GET['per_page']) ? $_GET['per_page'] : 50);
    if(!in_array($recordPerPage, $allowPerPage, true)){ $recordPerPage = 50; }
    $currentPage = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    if($currentPage < 1){ $currentPage = 1; }
    $start_limit = ($currentPage-1)*$recordPerPage;
    $limit = " limit $start_limit,$recordPerPage";
    $totalRecord = (int) $clsClassTable->countItem($cond);
    $totalPage = ceil($totalRecord / $recordPerPage);
    $assign_list['totalRecord'] = $totalRecord;
    $assign_list['recordPerPage'] = $recordPerPage;
    $assign_list['totalPage'] = $totalPage;
    $assign_list['currentPage'] = $currentPage;
    $assign_list['allowPerPage'] = $allowPerPage;
    $query_string = $_SERVER['QUERY_STRING'];
    $lst_query_string = explode('&',$query_string);
    # Link nền cho nút trang: bỏ 'page', GIỮ 'per_page' (đổi trang không mất cỡ trang).
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
    # Link nền cho ô chọn cỡ trang: bỏ cả 'page' lẫn 'per_page' → đổi cỡ thì về trang 1.
    $link_page_perpage = '';
    for($i=0;$i<count($lst_query_string);$i++){
        $tmp = explode('=',$lst_query_string[$i]);
        if($tmp[0]!='page'&&$tmp[0]!='per_page')
            $link_page_perpage .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
    }
    $assign_list['link_page_perpage'] = $link_page_perpage;
    # Nút bấm trang — tái dùng Pagination (chuẩn hệ thống, style .paginate_button có sẵn ở admin.css).
    $clsPagination = new Pagination();
    $clsPagination->initianize(array(
        'total'           => $totalRecord,
        'current_page'    => $currentPage,
        'number_per_page' => $recordPerPage,
        'link'            => PCMS_URL.'/'.$link_page_current,
    ));
    $assign_list['html_pager'] = $clsPagination->create_links();
    #-------End Page Divide-----------------------------------------------------------
    $allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit);
	$clsCity = new City();
	$lstCity = $clsCity->getAll("country_id='1'");
	$arr_slug_city = [];
	foreach($lstCity as $key => $val) {
		$arr_slug_city[$val["slug"]] = $val["city_id"];
	}
    if(!empty($allItem)){
        foreach($allItem as $key => $val){
            $more_project = $clsISO->to_array_json($val["more_information"]);
            $allItem[$key]["is_lock"] = !empty($more_project["is_lock"]) ? $more_project["is_lock"] : 0;
			
			/*$project_area_slug = $core->replaceSpace($more_project["project_area"]);
			if(!empty($arr_slug_city[$project_area_slug])) {
				$city_id = $arr_slug_city[$project_area_slug];
				$more_project["city_id"] = $city_id;
				$clsClassTable->updateOne($val[$clsClassTable->pkey],["more_information" => json_encode($more_project,JSON_UNESCAPED_UNICODE)]);
			}*/
        }
    }
    $assign_list["allItem"] = $allItem;
}
// Trang Tổng quan dự án (act=overview) — landing riêng mỗi dự án
function default_overview(){
    global $assign_list,$mod,$core,$clsModule,$clsISO,$smarty;
    $assign_list["clsModule"] = $clsModule;
    $clsProperty = new Property();
    $assign_list["clsProperty"] = $clsProperty;
    $clsClassTable = new Project();
    $assign_list["clsClassTable"] = $clsClassTable;
    $assign_list["pkeyTable"] = $clsClassTable->pkey;
    $project_id = (int) Input::get('project_id', 0);
    $oneItem = $project_id > 0 ? $clsClassTable->getOne($project_id) : array();
    if(empty($oneItem)){
        header('location: '.PCMS_URL.'/?mod='.$mod);
        exit();
    }
    $more_information = $clsISO->to_array_json($oneItem['more_information']);
    $oneItem['is_lock'] = !empty($more_information['is_lock']) ? $more_information['is_lock'] : 0;
    $oneItem['is_menu'] = !empty($more_information['is_menu']) ? $more_information['is_menu'] : 0;
    $assign_list["oneItem"] = $oneItem;
    $assign_list["project_id"] = $project_id;
    $assign_list["more_information"] = $more_information;
    # Loại hình (cao/thấp tầng) của dự án -> chọn đúng stock_type cho Bản đồ / Cấu hình
    $list_block_type = isset($oneItem['list_block_type']) ? $oneItem['list_block_type'] : '';
    $block_type_arrs = !empty($list_block_type) ? $clsISO->getArrayByTextSlash($list_block_type) : array();
    $assign_list["block_type_arrs"] = $block_type_arrs;
	$list_status_contract = $clsProperty->getAll("`property_type`='_STATUS_CONTRACT' AND `is_trash`='0' AND `property_id` <> '"._CONTRACT_STATUS_DONE_ID."'");
    # Cây Phân khu -> Tòa + thống kê (dùng chung helper)
    $aBlocks = _project_overview_blocks($project_id);
    $assign_list["list_blocks"] = $aBlocks['list_blocks'];
    $assign_list["stat_blocks"] = $aBlocks['stat_blocks'];
    $assign_list["stat_buildings"] = $aBlocks['stat_buildings'];
    # Build sẵn HTML cây block ngay trong PHP (Smarty {include} không resolve được trong codebase này)
    $smarty->assign('project_id', $project_id);
    $smarty->assign('list_blocks', $aBlocks['list_blocks']);
    $smarty->assign('clsProperty', $clsProperty);
    $smarty->assign('core', $core);
    $smarty->assign('list_status_contract', $list_status_contract);
    $assign_list["blocks_html"] = $core->build('_overview_blocks.tpl');
}
// Reload AJAX phần cây Phân khu -> Tòa (giữ toggle quick-menu)
function default_overview_blocks(){
    global $smarty,$core,$clsISO;
    $clsProperty = new Property();
    $project_id = (int) Input::get('project_id', Input::post('project_id', 0));
    $aBlocks = _project_overview_blocks($project_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('list_blocks', $aBlocks['list_blocks']);
    $smarty->assign('clsProperty', $clsProperty);
    $smarty->assign('core', $core);
    $html = $core->build('_overview_blocks.tpl');
    echo $html; die();
}
// Helper dùng chung: build cây block->building + thống kê cho trang overview
function _project_overview_blocks($project_id){
    global $clsISO;
    $clsProperty = new Property();
    $field = "{$clsProperty->pkey},title,parent_id,more_information,upd_date";
    $list_blocks = $clsProperty->getAll("is_trash=0 and property_type='_BLOCK' and for_id='{$project_id}' order by order_no ASC", $field);
    $count_buildings = 0;
    if(!empty($list_blocks)){
        foreach($list_blocks as $okey => $oval){
            $block_id = $oval[$clsProperty->pkey];
            $mi = $clsISO->to_array_json($oval['more_information']);
            if(!isset($mi['is_quick_menu'])) $mi['is_quick_menu'] = 0;
            $list_blocks[$okey]['more_information'] = $mi;
            # Thấp tầng dùng _RANGE (dải/căn), cao tầng dùng _BUILDING (tòa) — theo parent_id
            $property_type = ($oval['parent_id']==_BLOCK_TYPE_LOWFLOOR_SALE) ? '_RANGE' : '_BUILDING';
            $list_buildings = $clsProperty->getAll("is_trash=0 and property_type='{$property_type}' and for_id='{$block_id}' order by order_no ASC", $field);
            if(!empty($list_buildings)){
                foreach($list_buildings as $mkey => $mval){
                    $mii = $clsISO->to_array_json($mval['more_information']);
                    if(!isset($mii['is_quick_menu'])) $mii['is_quick_menu'] = 0;
                    $list_buildings[$mkey]['more_information'] = $mii;
                }
                $count_buildings += count($list_buildings);
            }
            $list_blocks[$okey]['list_buildings'] = $list_buildings;
        }
    }
    return array(
        'list_blocks' => $list_blocks,
        'stat_blocks' => is_array($list_blocks) ? count($list_blocks) : 0,
        'stat_buildings' => $count_buildings
    );
}
function default_open_project(){
    global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
    $user_id = $core->_USER['user_id'];
    $smarty->assign('core', $core);
    $clsProject = new Project();
    $smarty->assign('clsProject', $clsProject);
    $project_id = (int) Input::post('project_id',0);
    $smarty->assign('project_id', $project_id);
    // Return
    $html = $core->build('_ajax.project.tpl');
    echo $html; die();
}
function default_pop_create_project(){
    global $core,$smarty,$dbconn,$assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
    $user_id = $core->_USER['user_id'];
    $clsProject = new Project();
    $project_id = $clsProject->getMaxId();
    $title = Input::post('title');
    #
    $msg = '_error'; $link = '';
    if($clsProject->countItem("slug='".$core->replaceSpace($title)."'") > 0){
        $msg = '_duplicate';
    } else {
        $more_information = array(
            'properties' => array(
                'layout' => array(
                    'title' => 'Layout',
                    'lock' => 1,
                    'link' => ''
                )
            )
        );
        if($clsProject->insert(array(
            'project_id' => $project_id,
            'title' => $title,
            'slug' => $core->replaceSpace($title),
            'content' => Input::post('content'),
            'user_id' => $user_id,
            'reg_date' => time(),
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = '_success';
            $link = PCMS_URL.'/index.php?mod=project&act=edit&project_id='.$project_id;

            #activity log
            $clsActivityLog = new ActivityLog();
            $log = $clsActivityLog->addActivityLog("Project","insert",['title' => $title]);
        }
    }
    // Return
    echo json_encode(array(
        'msg' => $msg,
        'link' => $link
    )); die();
}
function default_open_group(){
    global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
    $user_id = $core->_USER['user_id'];
    $smarty->assign('core', $core);
    $clsProject = new Project();
    $smarty->assign('clsProject', $clsProject);
    $project_id = (int) Input::post('project_id',0);
    $group_id = Input::post('group_id', "");
    $smarty->assign('project_id', $project_id);
    $smarty->assign('group_id', $group_id);
    $more_information = $clsProject->getOneField('more_information', $project_id);
    $more_information = !empty($more_information)
        ? json_decode(html_entity_decode($more_information), true)
        : array();
    $groups = isset($more_information['groups']) && !empty($more_information['groups'])
        ? $more_information['groups']
        : array();
    $oneGroup = array();
    $titlePage = 'Thêm mới Group';
    if(!empty($group_id) && array_key_exists($group_id, $groups)){
        $oneGroup = $groups[$group_id];
        $titlePage = 'Cập nhật Group';
    }
    $smarty->assign('titlePage', $titlePage);
    $smarty->assign('oneGroup', $oneGroup);
    // Return
    $html = $core->build('_ajax.group.tpl');
    echo $html; die();
}
function default_pop_save_group(){
    global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
    $user_id = $core->_USER['user_id'];
    $clsProject = new Project();
    ###
    $project_id = (int) Input::post('project_id',0);
    $group_id = Input::post('group_id', "");
    $group_title = Input::post('title', "");
    ###
    $more_information = $clsProject->getOneField('more_information', $project_id);
    $more_information = !empty($more_information)
        ? json_decode(html_entity_decode($more_information), true)
        : array();
    $groups = isset($more_information['groups']) && !empty($more_information['groups'])
        ? $more_information['groups']
        : array();
    if(!empty($group_id) && array_key_exists($group_id, $groups)){
        $action = "_edit";
        $groups[$group_id]['title'] = $group_title;
        $groups[$group_id]['slug'] = $core->replaceSpace($group_title);
        $groups[$group_id]['upd_date'] = time();
        $groups[$group_id]['user_id_update'] = $user_id;
    } else {
        $action = "_add";
        $group_id = $clsISO->getUniqid();
        $groups[$group_id] = array(
            'title' => $group_title,
            'slug' => $core->replaceSpace($group_title),
            'reg_date' => time(),
            'upd_date' => time(),
            'user_id' => $user_id,
            'user_id_update' => $user_id
        );
    }
    $more_information['groups'] = $groups;
    ###
    $msg = "_error";
    if($clsProject->updateOne($project_id, array(
        'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
    ))){
        if($action=='_add'){
            $msg = "_update_success|||<tbody id=\"".$group_id."\" class=\"tbody_property is_group connectedSortable ui-sortable\">
			<tr id=\"".$group_id."\" class=\"tr_property ".$group_id."\">
				<td colspan=\"3\">
					<div class=\"d-flex align-items-center justify-content-between\">
						<div class=\"text-upper\">
							<button onClick=\"open_group(this, event)\" group_id=\"".$group_id."\" project_id=\"".$project_id."\" class=\"btn btn-xs btn-default\">".$core->makeIcon('pencil')."</button> 
							<span id=\"title_group_".$group_id."\">".$group_title."</span>
						</div>
						<button type=\"button\" class=\"ui-button ui-button--link add_property\" onClick=\"add_property(this, event)\" group_id=\"".$group_id."\" _openFrom=\"_project\" project_id=\"".$project_id."\">+ Thêm</button>
					</div>
				</td>
				<td class=\"text-center\">
					<button class=\"btn btn-default\" onClick=\"delete_group(this, event)\" group_id=\"".$group_id."\" project_id=\"".$project_id."\" title=\"Xóa nhóm\">".$core->makeIcon('trash')."</button>
				</td>
			</tr>";
        } else {
            $msg = '_update_success|||'.$group_title;
        }
    }
    // Return
    echo $msg; die();
}
function default_delete_group(){
    global $core,$smarty,$dbconn,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsModule,$clsISO,$_LANG_ID;
    $user_id = $core->_USER['user_id'];
    $clsProject = new Project();
    ###
    $project_id = (int) Input::post('project_id',0);
    $group_id = Input::post('group_id', "");
    $more_information = $clsProject->getOneField('more_information', $project_id);
    $more_information = !empty($more_information)
        ? json_decode(html_entity_decode($more_information), true) : array();
    $groups = isset($more_information['groups']) && !empty($more_information['groups'])
        ? $more_information['groups'] : array();
    //$clsISO->print_pre($groups); die();
    $msg = "_error";
    if(!empty($group_id) && @array_key_exists($group_id, $groups)){
        unset($groups[$group_id]);
        //$clsISO->print_pre($groups); die();
        $more_information['groups'] = $groups;
        if($clsProject->updateOne($project_id, array(
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = "_success";
        }
    }
    // Return
    echo $msg; die();
}
function default_upload_file(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
    $up = "";
    if(!empty($_FILES['attachment']['name'])){
        if(is_uploaded_file($_FILES['attachment']['tmp_name'])){
            $mimeType = $_FILES["attachment"]["type"];
            $filename = 'FH_'.time().'_'.$_FILES["attachment"]["name"];
            $clsUploadFile = new UploadFile();
            $up = $clsUploadFile->uploadItem($_FILES["attachment"],"/attachments",EXTENSION_FILE_UPLOAD);
            ###
            $clsGoogleDrive = new GoogleDrive();
            $createdFile = $clsGoogleDrive->upload($filename, $mimeType, ROOTPATH.$up);
            $up = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
            @unlink(ROOTPATH.$up);
        }
    }
    // Return
    echo $up; die();
}
function default_edit(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$clsConfiguration;
    $assign_list["clsModule"] = $clsModule;
    $user_id = $core->_USER['user_id'];
    $clsProfile = new Profile();
    $clsProperty = new Property();
    $clsSetting = new Setting();
    $clsProjectSop = new ProjectSop();
    $clsProjectMeta = new ProjectMeta();
    $clsCity = new City();
    $clsStock = new Stock();
    $assign_list["clsProfile"] = $clsProfile;
    $assign_list["clsProperty"] = $clsProperty;
    $assign_list["clsSetting"] = $clsSetting;
    $assign_list["clsProjectSop"] = $clsProjectSop;
    $assign_list["clsProjectMeta"] = $clsProjectMeta;
    $assign_list["clsCity"] = $clsCity;
    ##
    $classTable = "Project";
    $clsClassTable = new $classTable;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey ;
    $assign_list['pkeyTable'] = $pkeyTable;
    $assign_list["clsClassTable"] = $clsClassTable;
    ##
    $pvalTable = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : 0;
    $assign_list['pvalTable'] = $pvalTable;
    $oneItem = $clsClassTable->getOne($pvalTable);
    $list_block_type = $oneItem['list_block_type'];
    $more_information = $oneItem['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $block_type_arrs = !empty($list_block_type)
        ? $clsISO->getArrayByTextSlash($list_block_type) : array();
    // $clsISO->print_pre($more_information); die();
    $project_cat_menu_def = [_PROJECT_DOCS_IMGVIDEO_CATID,_PROJECT_DOCS_LAYOUT_CATID];
    $project_cat_menu = $core->get_field($more_information, "project_cat_menu", $project_cat_menu_def);
    $assign_list["oneItem"] = $oneItem;
    $assign_list["project_cat_menu"] = $project_cat_menu;
    $assign_list["block_type_arrs"] = $block_type_arrs;
    $assign_list["more_information"] = $more_information;
    $list_meta_fields = array(
        'arcreage' => array(
            'title' => 'Diện tích',
            'placeholder' => 'Nhập diện tích, VD 80'
        ), 'dg_price' => array(
            'title' => 'Mức giá',
            'placeholder' => 'Nhập giá, VD 12000000'
        ), 'price_per_m2' => array(
            'title' => 'Giá/m2',
            'placeholder' => 'Nhập giá/m2, 15000000'
        ), 'investor' => array(
            'title' => 'Chủ đầu tư',
            'placeholder' => 'Ví dụ: Tập đoàn Vingroup'
        ), 'building_density' => array(
            'title' => 'Mật độ xây dựng',
            'placeholder' => 'Ví dụ: 40'
        ), 'scale' => array(
            'title' => 'Quy mô',
            'placeholder' => 'Ví dụ: Gồm 2 block'
        ), 'completion_time' => array(
            'title' => 'Thời điểm hoàn thành',
            'placeholder' => 'Ví dụ: 2022'
        ), 'building' => array(
            'title' => 'Số tòa',
            'placeholder' => 'Ví dụ: 5'
        ), 'apartment' => array(
            'title' => 'Số căn hộ',
            'placeholder' => 'Ví dụ: 400'
        ), 'total_investment' => array(
            'title' => 'Tổng vốn đầu tư',
            'placeholder' => '3 tỷ'
        ), 'construction_type' => array(
            'title' => 'Loại hình',
            'placeholder' => '3 tỷ'
        ), 'handover' => array(
            'title' => 'Bàn giao',
            'placeholder' => 'Quý 1/2026'
        ), 'legal_status' => array(
            'title' => 'Pháp lý',
            'placeholder' => 'Sổ hồng'
        ),
    );
    $field = "{$clsProfile->pkey},full_name,first_name,last_name";
    $list_admins = $clsProfile->getAll("`is_trash`=0 and `is_active`='1'", $field);
    $list_profile = $clsProfile->getAll("`is_trash`=0 and status_id <> '"._STATUS_STAFF_OFF_ID."'", $field);
    $list_admin_id = $core->get_field($more_information, "list_admin_id", []);
    $arr_project_admins = $core->get_field($more_information, "project_admins", []);
    $assign_list["arr_project_admins"] = $arr_project_admins;
    $list_groups = array(
        _BLOCK_TYPE_LOWFLOOR_SALE => array(
            'title' => 'Thấp tầng',
            'field' => 'spreadsheetId',
            'list_admins' => (isset($list_admin_id[_BLOCK_TYPE_LOWFLOOR_SALE])
                ? $list_admin_id[_BLOCK_TYPE_LOWFLOOR_SALE] : array())
        ), _STOCK_TYPE_LEASING => array(
            'title' => 'Cho thuê',
            'field' => 'spreadsheetLeasingId',
            'list_admins' => (isset($list_admin_id[_STOCK_TYPE_LEASING])
                ? $list_admin_id[_STOCK_TYPE_LEASING] : array())
        ), _BLOCK_TYPE_HIGHLEVEL_SALE => array(
            'title' => 'Cao tầng',
            'field' => 'spreadsheetStockId',
            'list_admins' => (isset($list_admin_id[_BLOCK_TYPE_HIGHLEVEL_SALE])
                ? $list_admin_id[_BLOCK_TYPE_HIGHLEVEL_SALE] : array())
        )
    );
    $assign_list["clsProjectSop"] = $clsProjectSop;
    /** Init default Property Sop */
    $list_sops = $clsProjectSop->getAll("`project_id`='{$pvalTable}' order by `order_no` ASC");
    if(!empty($list_sops)){
        foreach($list_sops as $key => $val){
            $more_info = $val['more_information'];
            $more_info = $clsISO->to_array_json($more_info);
            $list_sops[$key]['more_information'] = $more_info;
        }
    } else {
        $field = "{$clsProperty->pkey},title,slug";
        $list_property_sops = $clsProperty->getAll("`is_trash`=0 and `property_type`='_Sop' order by `order_no` ASC", $field);
        // $clsISO->print_pre($list_property_sops);die;
        if(!empty($list_property_sops)){
            foreach($list_property_sops as $prop){
                $more_information_sop = array(
                    'field_type' => '_textarea',
                    'template_type' => '_tab',
                    'title' => $prop['title'],
                    'slug' => $core->replaceSpace($prop['title']),
                    'content' => '',
                    'image' => '',
                    'position' => '',
                    'is_image' => 0,
                    'is_background' => 0,
                    'background_color' => "",
                );
                $clsProjectSop->insert(array(
                    $clsProjectSop->pkey => $clsProjectSop->getMaxId(),
                    'project_id' => $pvalTable,
                    'more_information' => json_encode($more_information_sop,  JSON_UNESCAPED_UNICODE),
                    'order_no' => $clsProjectSop->getMaxOrderNo()
                ));
            }
        }
        $list_sops = $clsProjectSop->getAll("`project_id`='{$pvalTable}' order by `order_no` ASC");
        foreach($list_sops as $key => $val){
            $more_info = $val['more_information'];
            $more_info = $clsISO->to_array_json($more_info);
            $list_sops[$key]['more_information'] = $more_info;
        }
    }
    // $clsISO->print_pre($list_sops); die();
    $assign_list["list_sops"] = $list_sops;
    # map khoanh vùng
    $info_map_territory = $core->get_field($more_information, "map_territory", []);
    $assign_list["info_map_territory"] = $info_map_territory;
    #
    $assign_list["list_admins"] = $list_admins;
    $assign_list["list_groups"] = $list_groups;
    $assign_list["list_profile"] = $list_profile;
    $assign_list["list_meta_fields"] = $list_meta_fields;
    $list_props = $clsProjectMeta->getAll("`type`='project' and `for_id`='{$pvalTable}' order by `order_no` ASC");
    $assign_list["list_props"] = $list_props;
    #
    $list_domains = $clsConfiguration->getValue('list_domains');
    $list_domains = $clsISO->to_array_json($list_domains);
    $assign_list["list_domains"] = $list_domains;
    #menu thông tin dự án
    $list_category_menu = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `parent_id`='0' order by `order_no` ASC", "{$clsProperty->pkey},title");
    $assign_list["list_category_menu"] = $list_category_menu;
    #phân khu: dùng cho bộ lọc danh sách tiện ích
    $field = "{$clsProperty->pkey},title";
    $list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='".((int) $pvalTable)."' order by `order_no` ASC", $field);
    $assign_list["list_blocks"] = $list_blocks;
    #
    $lstField_highfloor = $clsStock->getTableField(_BLOCK_TYPE_HIGHLEVEL_SALE);
    $html_select_field_highfloor = '<select name="config_column[]" id="" class="form-select form-control required">';
    $html_select_field_highfloor .='<option value="" >--Chọn--</option>';
    foreach ($lstField_highfloor as $field => $label) {
        $html_select_field_highfloor .='<option value="'.$field.'" >'.$label.'</option>';
    }
    $html_select_field_highfloor .='</select>';
    $lstField_lowfloor = $clsStock->getTableField(_BLOCK_TYPE_LOWFLOOR_SALE);
    $html_select_field_lowfloor = '<select name="config_column[]" id="" class="form-select form-control required">';
    $html_select_field_lowfloor .='<option value="" >--Chọn--</option>';
    foreach ($lstField_lowfloor as $field => $label) {
        $html_select_field_lowfloor .='<option value="'.$field.'" >'.$label.'</option>';
    }
    $html_select_field_lowfloor .='</select>';
    $assign_list["html_select_field_highfloor"] = $html_select_field_highfloor;
    $assign_list["html_select_field_lowfloor"] = $html_select_field_lowfloor;

    #------------------------
    if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
//		$clsISO->print_pre($_POST);die;
        $value = ""; $firstAdd = 0;
        foreach($_POST as $key=>$val){
            $tmp = explode('-',$key);
            if($tmp[0]=='iso'){
                if($firstAdd==0){
                    $value .= $tmp[1]."='".addslashes($val)."'";
                    $firstAdd = 1;
                } else{
                    $value .= ",".$tmp[1]."='".addslashes($val)."'";
                }
            }
        }
        #

        $properties = Input::post('properties');
        $block_type  = Input::post('block_type');
        $list_admin_id = Input::post('list_admin_id');
        $investor_id = (int) Input::post('investor_id', 0);
        $project_admins = Input::post('project_admins',[]);
        #--Special Field: block_type
        $list_block_type = !empty($block_type)
            ? $clsISO->makeSlashListFromArray($block_type) : "";
        // $clsISO->print_pre($list_block_type); die();
        $value.= ",`list_block_type`='{$list_block_type}'";
        #--Special Field: #date
        $value.= ",`upd_date`='".time()."',`user_id`='{$user_id}'";
        #--Special Field: slug
        $value.= ",`slug`='".$core->replaceSpace($_POST['iso-title'])."'";
        #--Special Field: image
        if(_isoman_use){
            $image = Input::post('isoman_url_image');
            $logo = Input::post('isoman_url_logo');
            $layout = Input::post('isoman_url_layout');
            $banner = Input::post('isoman_url_banner');
        } else{
            $image = Input::post('image_src');
            $logo = Input::post('logo_src');
            $layout = Input::post('layout_src');
            $banner = Input::post('banner_src');
        }
        if(!empty($image)){
            $value.= ",`image`='".addslashes($image)."'";
        }
        if(!empty($logo)){
            $more_information['logo'] = $logo;
        }
        if(!empty($layout)){
            $more_information['layout'] = $layout;
        }
        if(!empty($banner)){
            $more_information['banner'] = $banner;
        }
        $project_cat_menu = Input::post("project_cat_menu",array());
        #--Special Field: notes
        $more_information['project_cat_menu'] = $project_cat_menu;
        $more_information['investor_id'] = $investor_id;
        $more_information['list_admin_id'] = $list_admin_id;
        $more_information['project_admins'] = $project_admins;
        $more_information['project_admins_slash'] = $clsISO->makeSlashListFromArrayRoot($project_admins);
        $more_information['notes'] = trim(Input::post('notes'));
        $more_information['address'] = trim(Input::post('address'));
        $more_information['project_area'] = trim(Input::post('project_area'));
		$more_information['city_id'] = (int)Input::post('city_id',0);
        $more_information['vr_link'] = trim(Input::post('vr_link'));
        $more_information['vr_source'] = trim(Input::post('vr_source'));
        $more_information['is_tiles'] = Input::post('is_tiles',0);
        $more_information['is_map_tiles'] = Input::post('is_map_tiles',0);
        $more_information['tiles_link'] = trim(Input::post('tiles_link'));
        $more_information['center_point'] = trim(Input::post('center_point'));
        $more_information['max_bound'] = trim(Input::post('max_bound'));
        $more_information['max_zoom'] = trim(Input::post('max_zoom'));
        $more_information['tms_enable'] = trim(Input::post('tms_enable'));
        $more_information['spreadsheetId'] = trim(Input::post('spreadsheetId'));
        $more_information['spreadsheetStockId'] = trim(Input::post('spreadsheetStockId'));
        $more_information['spreadsheetLeasingId'] = trim(Input::post('spreadsheetLeasingId'));
        $more_information['has_block'] = (int) Input::post('has_block',1);
        $more_information['is_booking'] = (int) Input::post('is_booking',0);
        $more_information['project_has_block'] = (int)Input::post('project_has_block',1);
        $more_information['site_manager_ids'] = Input::post('site_manager_ids',array());
        $more_information['bgcolor'] = Input::post('bgcolor');
        $more_information['textcolor'] = Input::post('textcolor');
        #
        $map_address = Input::post('map_address');
        $map_la = Input::post('map_la');
        $map_lo = Input::post('map_lo');
        $more_information['map_address'] = $map_address;
        $more_information['map_la'] = $map_la;
        $more_information['map_lo'] = $map_lo;

        # map phân khu
        $map_block_id_arr = Input::post('map_block_id');
        $map_block_lat_arr = Input::post('map_block_lat');
        $map_block_lng_arr = Input::post('map_block_lng');
        $map_address_block_arr = Input::post('map_address_block');
        if (!empty($map_block_id_arr)
            && !empty($map_block_lat_arr)
            && !empty($map_block_lng_arr)
            && !empty($map_address_block_arr)) {
            $map_block = [];
            foreach ($map_block_id_arr as $key=>$item) {
                if (!empty($map_block_lat_arr[$key]) && !empty($map_block_lng_arr[$key]) ) {
                    $map_block[$item] = array(
                        'block_id' => $item,
                        'lat' =>  $map_block_lat_arr[$key],
                        'lng' =>  $map_block_lng_arr[$key],
                        'address' => $map_address_block_arr[$key]
                    );
                }
            }
            $more_information['location_block'] = $map_block;
        }
        # địa điểm nổi bật
        $map_Highlight_name_arr = Input::post('map_highlight_name');
        $map_highlight_lat_arr = Input::post('map_highlight_lat');
        $map_highlight_lng_arr = Input::post('map_highlight_lng');
        $map_address_highlight_arr = Input::post('map_address_highlight');
        $map_zoom = Input::post('map_zoom');
        if (isset($map_zoom) ) {
            if(empty($map_zoom)) {
                $more_information['map_zoom'] = '';
            } elseif (is_numeric($map_zoom)){
                $more_information['map_zoom'] = $map_zoom;
            }
        }
        if (!empty($map_Highlight_name_arr)
            && !empty($map_highlight_lat_arr)
            && !empty($map_highlight_lng_arr)
            && !empty($map_address_highlight_arr)) {
            $map_highligh = [];
            foreach ($map_Highlight_name_arr as $key=>$item) {
                if (!empty($map_highlight_lat_arr[$key]) && !empty($map_highlight_lng_arr[$key]) ) {
                    $map_highligh[] = array(
                        'name' => $item,
                        'lat' =>  $map_highlight_lat_arr[$key],
                        'lng' =>  $map_highlight_lng_arr[$key],
                        'address' => $map_address_highlight_arr[$key]
                    );
                }
            }
            $more_information['location_highlight'] = $map_highligh;
        }
        # khoanh vùng vị trí
        $map_territory_lat_arr = Input::post('map_territory_lat');
        $map_territory_lng_arr = Input::post('map_territory_lng');
        if (!empty($map_territory_lat_arr) && !empty($map_territory_lng_arr)) {
            $map_territory = [];
            foreach ($map_territory_lat_arr as $key_territory=>$item_territory) {
                foreach ($item_territory as $key => $item) {
                    if (!empty($map_territory_lng_arr[$key_territory][$key]) ) {
                        $map_territory[$key_territory][] = array($item, $map_territory_lng_arr[$key_territory][$key]);
                    }
                }
                $map_territory[$key_territory][] = array($map_territory_lat_arr[$key_territory][0], $map_territory_lng_arr[$key_territory][0]);
            }
            $more_information['map_territory'] = $map_territory;
        } else if (empty($map_territory_lat_arr) && empty($map_territory_lng_arr)) {
            $more_information['map_territory'] = [];
        }
        $map_address_territory = Input::post('map_address_territory');
        $more_information['map_address_territory'] = $map_address_territory;
        $attrs = Input::post('attrs');
        if(!empty($attrs)){
            foreach($attrs as $key => $val){
                $title = addslashes(trim($val['title']));
                $content = addslashes(trim($val['content']));
                $attrs[$key]["title"] = $title;
                $attrs[$key]["content"] = $content;
                if(empty($title) && empty($content)){
                    unset($attrs[$key]);
                }
            }
        }
        $more_information['attrs'] = $attrs;
        ###
        $more_info_field = Input::post('more_info_field');
        if(!empty($more_info_field)){
            foreach($more_info_field as $key => $val){
                $more_information[$key] = trim($val);
            }
        }
//		 $clsISO->print_pre($more_information); die();
        $value.= ",more_information='".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'";
        if($clsClassTable->updateOne($pvalTable,$value)){
            $sop = Input::post("sop",array());
            if(!empty($sop)) {
                foreach ($sop as $sop_id => $val) {
                    $image_sop = !empty($val["image"]) ? $val["image"] : "";
                    $position_sop = !empty($val["position"]) ? $val["position"] : "";
                    $title_content_sop = !empty($val["title_content"]) ? trim($val["title_content"]) : "";
                    $title_list_sop = !empty($val["title_list"]) ? trim($val["title_list"]) : "";
                    $content_sop = !empty($val["content"]) ? addslashes($val["content"]) : "";
                    $is_image_sop = !empty($val["is_image"]) ? $val["is_image"] : 0;
                    $is_background_sop = !empty($val["is_background"]) ? $val["is_background"] : 0;
                    $oneSop = $clsProjectSop->getOne($sop_id,"more_information");
                    $more_information_sop = $clsISO->to_array_json($oneSop["more_information"]);
                    $background_color = !empty($val["background_color"]) ? $val["background_color"] : "";
                    $more_information_sop["image"] = $image_sop;
                    $more_information_sop["position"] = $position_sop;
                    $more_information_sop["title_content"] = $title_content_sop;
                    $more_information_sop["title_list"] = $title_list_sop;
                    $more_information_sop["content"] = $content_sop;
                    $more_information_sop["is_image"] = $is_image_sop;
                    $more_information_sop["is_background"] = $is_background_sop;
                    $more_information_sop["background_color"] = $background_color;
                    $clsProjectSop->updateOne($sop_id,['more_information' => json_encode($more_information_sop,  JSON_UNESCAPED_UNICODE)]);
                }
            }
            #activity log
            #$clsActivityLog = new ActivityLog();
            #$log = $clsActivityLog->addActivityLog("Project","update",['title' => $oneItem['title']]);
            if($_POST['button'] == '_EDIT') {
                header('location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$pvalTable.'&message=UpdateSuccess');
                exit();
            }else {
                header('location: '.PCMS_URL.'/?mod='.$mod.'&message=UpdateSuccess');
                exit();
            }
        }else{
            header('location: '.PCMS_URL.'/?mod='.$mod.'&message=updateFailed');
            exit();
        }
    }

    # Modal Icon
    $icons[] = "bx bx-child";
    $icons[] = "bx bx-sushi";
    $icons[] = "bx bx-shower";
    $icons[] = "bx bx-rfid";
    $icons[] = "bx bx-universal-access";
    $icons[] = "bx bx-shield-minus";
    $icons[] = "bx bx-shield-plus";
    $icons[] = "bx bx-vertical-bottom";
    $icons[] = "bx bx-vertical-top";
    $icons[] = "bx bx-horizontal-right";
    $icons[] = "bx bx-horizontal-left";
    $icons[] = "bx bx-objects-vertical-bottom";
    $icons[] = "bx bx-objects-vertical-center";
    $icons[] = "bx bx-objects-vertical-top";
    $icons[] = "bx bx-objects-horizontal-right";
    $icons[] = "bx bx-objects-horizontal-center";
    $icons[] = "bx bx-objects-horizontal-left";
    $icons[] = "bx bx-color";
    $icons[] = "bx bx-reflect-horizontal";
    $icons[] = "bx bx-reflect-vertical";
    $icons[] = "bx bx-cart-add";
    $icons[] = "bx bx-cart-download";
    $icons[] = "bx bx-no-signal";
    $icons[] = "bx bx-signal-5";
    $icons[] = "bx bx-signal-4";
    $icons[] = "bx bx-signal-3";
    $icons[] = "bx bx-signal-2";
    $icons[] = "bx bx-signal-1";
    $icons[] = "bx bx-cheese";
    $icons[] = "bx bx-hard-hat";
    $icons[] = "bx bx-home-alt-2";
    $icons[] = "bx bx-lemon";
    $icons[] = "bx bx-cable-car";
    $icons[] = "bx bx-cricket-ball";
    $icons[] = "bx bx-male-female";
    $icons[] = "bx bx-baguette";
    $icons[] = "bx bx-fork";
    $icons[] = "bx bx-knife";
    $icons[] = "bx bx-circle-half";
    $icons[] = "bx bx-circle-three-quarter";
    $icons[] = "bx bx-circle-quarter";
    $icons[] = "bx bx-bowl-rice";
    $icons[] = "bx bx-bowl-hot";
    $icons[] = "bx bx-popsicle";
    $icons[] = "bx bx-cross";
    $icons[] = "bx bx-scatter-chart";
    $icons[] = "bx bx-money-withdraw";
    $icons[] = "bx bx-candles";
    $icons[] = "bx bx-math";
    $icons[] = "bx bx-party";
    $icons[] = "bx bx-leaf";
    $icons[] = "bx bx-injection";
    $icons[] = "bx bx-expand-vertical";
    $icons[] = "bx bx-expand-horizontal";
    $icons[] = "bx bx-collapse-vertical";
    $icons[] = "bx bx-collapse-horizontal";
    $icons[] = "bx bx-collapse-alt";
    $icons[] = "bx bx-qr";
    $icons[] = "bx bx-qr-scan";
    $icons[] = "bx bx-podcast";
    $icons[] = "bx bx-checkbox-minus";
    $icons[] = "bx bx-speaker";
    $icons[] = "bx bx-registered";
    $icons[] = "bx bx-phone-off";
    $icons[] = "bx bx-buildings";
    $icons[] = "bx bx-store-alt";
    $icons[] = "bx bx-bar-chart-alt-2";
    $icons[] = "bx bx-message-dots";
    $icons[] = "bx bx-message-rounded-dots";
    $icons[] = "bx bx-memory-card";
    $icons[] = "bx bx-wallet-alt";
    $icons[] = "bx bx-slideshow";
    $icons[] = "bx bx-message-square";
    $icons[] = "bx bx-message-square-dots";
    $icons[] = "bx bx-book-content";
    $icons[] = "bx bx-chat";
    $icons[] = "bx bx-edit-alt";
    $icons[] = "bx bx-mouse-alt";
    $icons[] = "bx bx-bug-alt";
    $icons[] = "bx bx-notepad";
    $icons[] = "bx bx-video-recording";
    $icons[] = "bx bx-shape-square";
    $icons[] = "bx bx-shape-triangle";
    $icons[] = "bx bx-ghost";
    $icons[] = "bx bx-mail-send";
    $icons[] = "bx bx-code-alt";
    $icons[] = "bx bx-grid";
    $icons[] = "bx bx-user-pin";
    $icons[] = "bx bx-run";
    $icons[] = "bx bx-copy-alt";
    $icons[] = "bx bx-transfer-alt";
    $icons[] = "bx bx-book-open";
    $icons[] = "bx bx-landscape";
    $icons[] = "bx bx-comment";
    $icons[] = "bx bx-comment-dots";
    $icons[] = "bx bx-pyramid";
    $icons[] = "bx bx-cylinder";
    $icons[] = "bx bx-lock-alt";
    $icons[] = "bx bx-lock-open-alt";
    $icons[] = "bx bx-left-arrow-alt";
    $icons[] = "bx bx-right-arrow-alt";
    $icons[] = "bx bx-up-arrow-alt";
    $icons[] = "bx bx-down-arrow-alt";
    $icons[] = "bx bx-shape-circle";
    $icons[] = "bx bx-cycling";
    $icons[] = "bx bx-dna";
    $icons[] = "bx bx-bowling-ball";
    $icons[] = "bx bx-search-alt-2";
    $icons[] = "bx bx-plus-medical";
    $icons[] = "bx bx-street-view";
    $icons[] = "bx bx-droplet";
    $icons[] = "bx bx-paint-roll";
    $icons[] = "bx bx-shield-alt-2";
    $icons[] = "bx bx-error-alt";
    $icons[] = "bx bx-square";
    $icons[] = "bx bx-square-rounded";
    $icons[] = "bx bx-polygon";
    $icons[] = "bx bx-cube-alt";
    $icons[] = "bx bx-cuboid";
    $icons[] = "bx bx-user-voice";
    $icons[] = "bx bx-accessibility";
    $icons[] = "bx bx-building-house";
    $icons[] = "bx bx-doughnut-chart";
    $icons[] = "bx bx-log-in-circle";
    $icons[] = "bx bx-log-out-circle";
    $icons[] = "bx bx-check-square";
    $icons[] = "bx bx-message-alt";
    $icons[] = "bx bx-message-alt-dots";
    $icons[] = "bx bx-no-entry";
    $icons[] = "bx bx-palette";
    $icons[] = "bx bx-basket";
    $icons[] = "bx bx-purchase-tag-alt";
    $icons[] = "bx bx-receipt";
    $icons[] = "bx bx-line-chart";
    $icons[] = "bx bx-map-pin";
    $icons[] = "bx bx-hive";
    $icons[] = "bx bx-band-aid";
    $icons[] = "bx bx-credit-card-alt";
    $icons[] = "bx bx-wifi-off";
    $icons[] = "bx bx-brightness-half";
    $icons[] = "bx bx-brightness";
    $icons[] = "bx bx-filter-alt";
    $icons[] = "bx bx-dialpad-alt";
    $icons[] = "bx bx-border-right";
    $icons[] = "bx bx-border-left";
    $icons[] = "bx bx-border-top";
    $icons[] = "bx bx-border-bottom";
    $icons[] = "bx bx-border-all";
    $icons[] = "bx bx-mobile-landscape";
    $icons[] = "bx bx-mobile-vibration";
    $icons[] = "bx bx-gas-pump";
    $icons[] = "bx bx-pie-chart-alt-2";
    $icons[] = "bx bx-time-five";
    $icons[] = "bx bx-briefcase-alt-2";
    $icons[] = "bx bx-brush-alt";
    $icons[] = "bx bx-customize";
    $icons[] = "bx bx-radio";
    $icons[] = "bx bx-printer";
    $icons[] = "bx bx-sort-a-z";
    $icons[] = "bx bx-sort-z-a";
    $icons[] = "bx bx-conversation";
    $icons[] = "bx bx-exit";
    $icons[] = "bx bx-extension";
    $icons[] = "bx bx-face";
    $icons[] = "bx bx-file-find";
    $icons[] = "bx bx-label";
    $icons[] = "bx bx-check-shield";
    $icons[] = "bx bx-border-radius";
    $icons[] = "bx bx-add-to-queue";
    $icons[] = "bx bx-archive-in";
    $icons[] = "bx bx-archive-out";
    $icons[] = "bx bx-alarm-add";
    $icons[] = "bx bx-space-bar";
    $icons[] = "bx bx-image-alt";
    $icons[] = "bx bx-image-add";
    $icons[] = "bx bx-fridge";
    $icons[] = "bx bx-dish";
    $icons[] = "bx bx-spa";
    $icons[] = "bx bx-cake";
    $icons[] = "bx bx-bolt-circle";
    $icons[] = "bx bx-tone";
    $icons[] = "bx bx-bitcoin";
    $icons[] = "bx bx-lira";
    $icons[] = "bx bx-ruble";
    $icons[] = "bx bx-rupee";
    $icons[] = "bx bx-euro";
    $icons[] = "bx bx-pound";
    $icons[] = "bx bx-won";
    $icons[] = "bx bx-yen";
    $icons[] = "bx bx-shekel";
    $icons[] = "bx bx-health";
    $icons[] = "bx bx-clinic";
    $icons[] = "bx bx-male";
    $icons[] = "bx bx-female";
    $icons[] = "bx bx-male-sign";
    $icons[] = "bx bx-female-sign";
    $icons[] = "bx bx-food-tag";
    $icons[] = "bx bx-food-menu";
    $icons[] = "bx bx-meh-alt";
    $icons[] = "bx bx-wink-tongue";
    $icons[] = "bx bx-happy-alt";
    $icons[] = "bx bx-cool";
    $icons[] = "bx bx-tired";
    $icons[] = "bx bx-smile";
    $icons[] = "bx bx-angry";
    $icons[] = "bx bx-happy-heart-eyes";
    $icons[] = "bx bx-dizzy";
    $icons[] = "bx bx-wink-smile";
    $icons[] = "bx bx-confused";
    $icons[] = "bx bx-sleepy";
    $icons[] = "bx bx-shocked";
    $icons[] = "bx bx-happy-beaming";
    $icons[] = "bx bx-meh-blank";
    $icons[] = "bx bx-laugh";
    $icons[] = "bx bx-upside-down";
    $icons[] = "bx bx-diamond";
    $icons[] = "bx bx-align-left";
    $icons[] = "bx bx-align-middle";
    $icons[] = "bx bx-align-right";


    $modal_icon = '<div class="modal-dialog modal-lg" role="document" style="max-width: 1000px; width: 100%;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Chọn Icon</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$Core.popup.close($(\'.choose_icon_modal\'))">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<input type="text" class="form-control" id="search-icon-input" placeholder="Nhập từ khóa tìm kiếm icon..." onkeyup="
									var val = $(this).val().toLowerCase();
									$(this).closest(\'.modal-body\').find(\'.icon-item\').each(function(){
										if($(this).data(\'icon\').toLowerCase().indexOf(val) > -1) {
											$(this).show();
										} else {
											$(this).hide();
										}
									});
								" />
							</div>
							<div class="list-results" style="width: 100%; min-height: 200px; max-height: 350px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; box-sizing: border-box; overflow-x: hidden;">';
    foreach ($icons as $icon) {
        $modal_icon .= '<div class="icon-item text-center" data-icon="' . $icon . '" style="float: left; font-size: 28px; width: 40px; height: 40px; line-height: 70px; text-align: center; cursor: pointer; border: 1px solid #eee; border-radius: 6px; margin: 4px; background: #f9f9f9; align-content:center;transition: all 0.2s;" onclick="$(this).parent().find(\'.icon-item\').css(\'border-color\', \'transparent\').removeClass(\'selected\'); $(this).css(\'border-color\', \'#007bff\').addClass(\'selected\');"><i class="bx ' . $icon . '"></i></div>';
    }
    $modal_icon .= '				<div style="clear:both;"></div>
						</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$Core.popup.close($(\'.choose_icon_modal\'))">Đóng</button>
							<button type="button" class="btn btn-primary btn-confirm-icon" id="">Xác nhận</button>
						</div>
					</div>
				</div>';


    $assign_list['modal_icon'] = $modal_icon;
}
function default_map_project(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$clsConfiguration;
    $assign_list["clsModule"] = $clsModule;
    $user_id = $core->_USER['user_id'];
    $clsProfile = new Profile();
    $clsProperty = new Property();
    $clsSetting = new Setting();
    $clsProjectSop = new ProjectSop();
    $clsProjectMeta = new ProjectMeta();
    $clsStock = new Stock();
    $assign_list["clsProfile"] = $clsProfile;
    $assign_list["clsProperty"] = $clsProperty;
    $assign_list["clsSetting"] = $clsSetting;
    $assign_list["clsProjectSop"] = $clsProjectSop;
    $assign_list["clsProjectMeta"] = $clsProjectMeta;
    ##
    $classTable = "Project";
    $clsClassTable = new $classTable;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey ;
    $assign_list['pkeyTable'] = $pkeyTable;
    $assign_list["clsClassTable"] = $clsClassTable;
    ##
    $project_id = isset($_GET["project_id"])? ($_GET["project_id"]) : 0;
    $assign_list['project_id'] = $project_id;
    $oneItem = $clsClassTable->getOne($project_id);
    $list_block_type = $oneItem['list_block_type'];
    $more_information = $oneItem['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $block_type_arrs = !empty($list_block_type)
        ? $clsISO->getArrayByTextSlash($list_block_type) : array();
    // $clsISO->print_pre($more_information); die();
    $project_cat_menu_def = [_PROJECT_DOCS_IMGVIDEO_CATID,_PROJECT_DOCS_LAYOUT_CATID];
    $project_cat_menu = $core->get_field($more_information, "project_cat_menu", $project_cat_menu_def);
    $assign_list["oneItem"] = $oneItem;
    $assign_list["project_cat_menu"] = $project_cat_menu;
    $assign_list["block_type_arrs"] = $block_type_arrs;
    $assign_list["more_information"] = $more_information;
    # map khoanh vùng
    $info_map_territory = $core->get_field($more_information, "map_territory", []);
    $assign_list["info_map_territory"] = $info_map_territory;
    if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
        $firstAdd = 0;
        #--Special Field: #date
        $value= "`upd_date`='".time()."',`user_id`='{$user_id}'";
        #
        $map_address = Input::post('map_address');
        $map_la = Input::post('map_la');
        $map_lo = Input::post('map_lo');
        $more_information['map_address'] = $map_address;
        $more_information['map_la'] = $map_la;
        $more_information['map_lo'] = $map_lo;

        # map phân khu
        $map_block_id_arr = Input::post('map_block_id');
        $map_block_lat_arr = Input::post('map_block_lat');
        $map_block_lng_arr = Input::post('map_block_lng');
        $map_address_block_arr = Input::post('map_address_block');
        if (!empty($map_block_id_arr)
            && !empty($map_block_lat_arr)
            && !empty($map_block_lng_arr)
            && !empty($map_address_block_arr)) {
            $map_block = [];
            foreach ($map_block_id_arr as $key=>$item) {
                if (!empty($map_block_lat_arr[$key]) && !empty($map_block_lng_arr[$key]) ) {
                    $map_block[$item] = array(
                        'block_id' => $item,
                        'lat' =>  $map_block_lat_arr[$key],
                        'lng' =>  $map_block_lng_arr[$key],
                        'address' => $map_address_block_arr[$key]
                    );
                }
            }
            $more_information['location_block'] = $map_block;
        }
        # địa điểm nổi bật
        $map_Highlight_name_arr = Input::post('map_highlight_name');
        $map_highlight_lat_arr = Input::post('map_highlight_lat');
        $map_highlight_lng_arr = Input::post('map_highlight_lng');
        $map_address_highlight_arr = Input::post('map_address_highlight');
        $map_zoom = Input::post('map_zoom');
        if (isset($map_zoom) ) {
            if(empty($map_zoom)) {
                $more_information['map_zoom'] = '';
            } elseif (is_numeric($map_zoom)){
                $more_information['map_zoom'] = $map_zoom;
            }
        }
        if (!empty($map_Highlight_name_arr)
            && !empty($map_highlight_lat_arr)
            && !empty($map_highlight_lng_arr)
            && !empty($map_address_highlight_arr)) {
            $map_highligh = [];
            foreach ($map_Highlight_name_arr as $key=>$item) {
                if (!empty($map_highlight_lat_arr[$key]) && !empty($map_highlight_lng_arr[$key]) ) {
                    $map_highligh[] = array(
                        'name' => $item,
                        'lat' =>  $map_highlight_lat_arr[$key],
                        'lng' =>  $map_highlight_lng_arr[$key],
                        'address' => $map_address_highlight_arr[$key]
                    );
                }
            }
            $more_information['location_highlight'] = $map_highligh;
        }
        # khoanh vùng vị trí
        $map_territory_lat_arr = Input::post('map_territory_lat');
        $map_territory_lng_arr = Input::post('map_territory_lng');
        if (!empty($map_territory_lat_arr) && !empty($map_territory_lng_arr)) {
            $map_territory = [];
            foreach ($map_territory_lat_arr as $key_territory=>$item_territory) {
                foreach ($item_territory as $key => $item) {
                    if (!empty($map_territory_lng_arr[$key_territory][$key]) ) {
                        $map_territory[$key_territory][] = array($item, $map_territory_lng_arr[$key_territory][$key]);
                    }
                }
                $map_territory[$key_territory][] = array($map_territory_lat_arr[$key_territory][0], $map_territory_lng_arr[$key_territory][0]);
            }
            $more_information['map_territory'] = $map_territory;
        } else if (empty($map_territory_lat_arr) && empty($map_territory_lng_arr)) {
            $more_information['map_territory'] = [];
        }
        $map_address_territory = Input::post('map_address_territory');
        $more_information['map_address_territory'] = $map_address_territory;
        $value.= ",more_information='".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'";
        if($clsClassTable->updateOne($project_id,$value)){
            if($_POST['button'] == '_EDIT') {
                header('location: '.PCMS_URL.'/?mod='.$mod.'&act=map_project&project_id='.$project_id.'&message=UpdateSuccess');
                exit();
            }else {
                header('location: '.PCMS_URL.'/?mod='.$mod.'&act=overview&message=UpdateSuccess');
                exit();
            }
        }else{
            header('location: '.PCMS_URL.'/?mod='.$mod.'&act=overview&message=updateFailed');
            exit();
        }
    }
}
function default_trash(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id = $core->_USER['user_id'];
    #
    $classTable = "Project";
    $clsClassTable = new $classTable;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey ;
    $pvalTable = isset($_GET[$pkeyTable]) ? (int) $_GET[$pkeyTable] : 0;
    #
    if($pvalTable == 0){
        header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
        exit();
    } else {
        $oneItem = $clsClassTable->getOne($pvalTable,"title");
        if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){
            #activity log
            $clsActivityLog = new ActivityLog();
            $log = $clsActivityLog->addActivityLog("Project","trash",['title' => $oneItem['title']]);
            header('location: '.PCMS_URL.'/?mod='.$mod.'&message=TrashSuccess');
            exit();
        }
    }
}
function default_restore(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id = $core->_USER['user_id'];
    ##
    $classTable = "Project";
    $clsClassTable = new $classTable;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey ;
    $pvalTable = isset($_GET[$pkeyTable]) ? (int) $_GET[$pkeyTable] : 0;
    ##
    if($pvalTable == 0){
        header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
        exit();
    }
    $oneItem = $clsClassTable->getOne($pvalTable,"title");
    if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
        #activity log
        $clsActivityLog = new ActivityLog();
        $log = $clsActivityLog->addActivityLog("Project","restore",['title' => $oneItem['title']]);
        header('location: '.PCMS_URL.'/?mod='.$mod.'&message=RestoreSuccess');
        exit();
    }
}
/**
 * Xoá toàn bộ dữ liệu liên quan của 1 dự án: tài liệu (project_meta + junction),
 * quỹ căn (stock_meta -> stock), chính sách bán hàng (policy/policy_scope).
 * Xoá con trước, row dự án xoá sau cùng ở default_delete() — lỗi giữa chừng vẫn bấm xoá lại được.
 * Policy áp cho nhiều dự án thì chỉ gỡ dự án này khỏi scope, không xoá policy.
 */
function _project_delete_related($project_id){
    global $core, $dbconn, $clsISO;
    $project_id = (int) $project_id;
    $counts = array('ok' => false, 'meta' => 0, 'meta_updated' => 0, 'stock' => 0, 'stock_meta' => 0, 'block' => 0, 'building' => 0, 'policy_deleted' => 0, 'policy_updated' => 0);
    if($project_id <= 0){
        return $counts;
    }
    #- ADOdb không throw exception: Execute() trả false khi lỗi SQL nên phải tự gom cờ $ok từng bước
    $ok = true;
    $pre = DB_PREFIX;
    #-- 1. Tài liệu của dự án + mô tả (type='project', for_id=X) và 3 bảng junction theo meta_id
    #-- 1 tài liệu có thể thuộc nhiều dự án: chỉ xoá khi không còn thuộc dự án nào khác,
    #-- dùng chung thì chỉ gỡ dự án này ra (giống cách xử lý policy áp nhiều dự án bên dưới)
    $clsProjectMeta = new ProjectMeta();
    $metaCond = "(".$clsProjectMeta->condByProject($project_id).") or (type='project' and for_id='{$project_id}')";
    $metaRows = $clsProjectMeta->getAll($metaCond, "id,type,for_id,project_id,project_ids");
    $meta_ids = array();
    if(!empty($metaRows)){
        foreach($metaRows as $row){
            $meta_id = (int) $row['id'];
            $project_list = !empty($row['project_ids']) ? $clsISO->getArrayByTextSlash($row['project_ids']) : array();
            $project_remain = array_values(array_diff(array_map('intval', $project_list), array($project_id)));
            if(empty($project_remain)){
                $meta_ids[] = $meta_id;
                continue;
            }
            $upd = array(
                'project_ids' => $clsISO->makeSlashListFromArrayRoot($project_remain),
                'upd_date' => time()
            );
            if((int) $row['project_id'] === $project_id){
                $upd['project_id'] = $project_remain[0];
            }
            if($row['type'] == 'project' && (int) $row['for_id'] === $project_id){
                $upd['for_id'] = 0;
            }
            if(!$clsProjectMeta->updateOne($meta_id, $upd)){
                $ok = false;
            }
            $counts['meta_updated']++;
        }
    }
    if(!empty($meta_ids)){
        foreach(array_chunk($meta_ids, 500) as $chunk){
            $idList = implode(',', $chunk);
            if($dbconn->Execute("DELETE FROM `{$pre}project_meta_block` WHERE meta_id IN ({$idList})") === false){
                $ok = false;
            }
            if($dbconn->Execute("DELETE FROM `{$pre}project_meta_building` WHERE meta_id IN ({$idList})") === false){
                $ok = false;
            }
            if($dbconn->Execute("DELETE FROM `{$pre}project_meta_tag` WHERE meta_id IN ({$idList})") === false){
                $ok = false;
            }
            $clsProjectMeta->deleteByCond("id in ({$idList})");
        }
        $counts['meta'] = count($meta_ids);
    }
    #-- 2. Quỹ căn: stock_meta nối qua stock_id (không có project_id) nên phải xoá bằng JOIN khi row stock còn.
    #-- Lỗi ở bước stock_meta thì KHÔNG xoá stock — mất row stock là mất luôn dấu vết JOIN để dọn stock_meta.
    $clsStock = new Stock();
    $counts['stock'] = (int) $clsStock->countItem("`project_id`='{$project_id}'");
    if($counts['stock'] > 0){
        $totalStockMeta = $dbconn->GetOne("SELECT COUNT(*) FROM `{$pre}stock_meta` sm INNER JOIN `{$pre}stock` s ON sm.stock_id = s.stock_id WHERE s.project_id = {$project_id}");
        if($totalStockMeta === false){
            $ok = false;
        } else {
            $counts['stock_meta'] = (int) $totalStockMeta;
            $rsStockMeta = true;
            if($counts['stock_meta'] > 0){
                $rsStockMeta = $dbconn->Execute("DELETE sm FROM `{$pre}stock_meta` sm INNER JOIN `{$pre}stock` s ON sm.stock_id = s.stock_id WHERE s.project_id = {$project_id}");
            }
            if($rsStockMeta === false){
                $ok = false;
            } else {
                $clsStock->deleteByCond("`project_id`='{$project_id}'");
            }
        }
    }
    #-- 2b. Phân khu (_BLOCK, for_id=project) + tòa/dải tầng (_BUILDING/_RANGE, for_id=block_id) trong default_property
    $clsProperty = new Property();
    $blockRows = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'", "property_id");
    if(!empty($blockRows)){
        $block_ids = array();
        foreach($blockRows as $row){
            $block_ids[] = (int) $row['property_id'];
        }
        $counts['block'] = count($block_ids);
        foreach(array_chunk($block_ids, 500) as $chunk){
            $idList = implode(',', $chunk);
            $counts['building'] += (int) $clsProperty->countItem("property_type in ('_BUILDING','_RANGE') and for_id in ({$idList})");
            $clsProperty->deleteByCond("property_type in ('_BUILDING','_RANGE') and for_id in ({$idList})");
        }
        $clsProperty->deleteByCond("property_type='_BLOCK' and for_id='{$project_id}'");
    }
    #-- 3. Chính sách bán hàng: gom policy_id từ bảng scope + quét scope_slash (policy cũ chưa đồng bộ bảng)
    $clsPolicy = new Policy();
    $clsPolicyScope = new PolicyScope();
    $policy_ids = array();
    $scopeRows = $clsPolicyScope->getAll("project_id='{$project_id}'", "policy_id");
    if(!empty($scopeRows)){
        foreach($scopeRows as $row){
            $policy_ids[(int) $row['policy_id']] = true;
        }
    }
    $slashRows = $clsPolicy->getAll("scope_slash like '%|{$project_id}\\\\_%'", "policy_id");
    if(!empty($slashRows)){
        foreach($slashRows as $row){
            $policy_ids[(int) $row['policy_id']] = true;
        }
    }
    foreach(array_keys($policy_ids) as $policy_id){
        $onePolicy = $clsPolicy->getOne($policy_id, "policy_id, scope, scope_slash");
        if(empty($onePolicy)){
            continue;
        }
        #- Lọc scope JSON: giữ lại entry của dự án khác
        $scope_data = $clsISO->to_array_json($onePolicy['scope']);
        $scope_remain = array();
        if(is_array($scope_data)){
            foreach($scope_data as $key => $item){
                $item_project_id = isset($item['project_id']) ? (int) $item['project_id'] : 0;
                if($item_project_id != $project_id){
                    $scope_remain[$key] = $item;
                }
            }
        }
        #- Gỡ token |{project}_{block}_{building}| của dự án này khỏi scope_slash
        $slash_remain = preg_replace('/\|'.$project_id.'_[^|]*\|/', '', (string) $onePolicy['scope_slash']);
        #- Policy còn thuộc dự án khác không? (xét cả 3 nguồn: JSON, bảng scope, scope_slash)
        $other_scope = (int) $clsPolicyScope->countItem("policy_id='{$policy_id}' and project_id!='{$project_id}'");
        $has_other = !empty($scope_remain) || $other_scope > 0 || preg_match('/\|\d+_/', $slash_remain) === 1;
        if(!$has_other){
            #- Policy chỉ áp cho dự án này -> xoá hẳn (syncPolicyScope rỗng dọn luôn row scope)
            $clsPolicy->deleteOne($policy_id);
            $clsPolicy->syncPolicyScope($policy_id, array());
            $counts['policy_deleted']++;
        } else {
            #- Policy dùng chung -> chỉ gỡ dự án này khỏi scope
            $upd = array('scope_slash' => $slash_remain, 'upd_date' => time());
            if(is_array($scope_data) && !empty($scope_data)){
                $upd['scope'] = json_encode($scope_remain, JSON_UNESCAPED_UNICODE);
            }
            if(!$clsPolicy->updateOne($policy_id, $upd)){
                $ok = false;
            }
            if(!empty($scope_remain)){
                $clsPolicy->syncPolicyScope($policy_id, $scope_remain);
            } else {
                #- scope JSON không đọc được: chỉ xoá row scope của dự án này, giữ nguyên phần còn lại
                $oldScopes = $clsPolicyScope->getAll("policy_id='{$policy_id}' and project_id='{$project_id}'", "id");
                if(!empty($oldScopes)){
                    foreach($oldScopes as $row){
                        $clsPolicyScope->deleteOne($row['id']);
                    }
                }
            }
            $counts['policy_updated']++;
        }
    }
    #-- Hậu kiểm: còn dữ liệu con là chưa an toàn — default_delete() sẽ giữ lại row dự án để bấm xoá lại được
    if((int) $clsProjectMeta->countItem($metaCond) > 0){
        $ok = false;
    }
    if((int) $clsStock->countItem("`project_id`='{$project_id}'") > 0){
        $ok = false;
    }
    if((int) $clsProperty->countItem("property_type='_BLOCK' and for_id='{$project_id}'") > 0){
        $ok = false;
    }
    if((int) $clsPolicyScope->countItem("project_id='{$project_id}'") > 0){
        $ok = false;
    }
    $counts['ok'] = $ok;
    return $counts;
}
function default_delete(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id = $core->_USER['user_id'];
    #
    $classTable = "Project";
    $clsClassTable = new $classTable;
    $tableName = $clsClassTable->tbl;
    $pkeyTable = $clsClassTable->pkey;
    #- GET giống Trash/Restore/Delete của mọi module (xác nhận nằm ở modal confirm phía JS)
    $pvalTable = isset($_GET[$pkeyTable]) ? (int) $_GET[$pkeyTable] : 0;
    ##
    if($pvalTable == 0){
        header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
        exit();
    }
    $oneItem = $clsClassTable->getOne($pvalTable,"title");
    if(empty($oneItem)){
        header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
        exit();
    }
    #- Xoá dữ liệu liên quan trước (tài liệu, quỹ căn, CSBH); quỹ căn có thể hàng chục nghìn row
    @set_time_limit(300);
    $related = _project_delete_related($pvalTable);
    if(empty($related['ok'])){
        #- Lỗi SQL/lock giữa chừng: dữ liệu con còn sót -> giữ row dự án để bấm xoá lại (không xoá row dự án, không báo thành công ảo)
        header('location: '.PCMS_URL.'/?mod='.$mod.'&act=overview&project_id='.$pvalTable);
        exit();
    }
    if($clsClassTable->deleteOne($pvalTable)){
        #activity log
        $log_title = sprintf('%s (kèm %d tài liệu, %d căn, %d ghi chú căn, %d phân khu, %d tòa, xoá %d / gỡ %d CSBH)',
            $oneItem['title'], $related['meta'], $related['stock'], $related['stock_meta'],
            $related['block'], $related['building'], $related['policy_deleted'], $related['policy_updated']);
        $clsActivityLog = new ActivityLog();
        $log = $clsActivityLog->addActivityLog("Project","delete",['title' => $log_title]);
        header('location: '.PCMS_URL.'/?mod='.$mod.'&message=DeleteSuccess');
        exit();
    }
}
function default_add_property(){
    global $core,$_frontIsLoggedin_user_id,$clsISO;
    $uid = $clsISO->getUniqid();
    $_holderG = Input::post('_holderG', '_properties');
    $_openFrom = Input::post('_openFrom', '_project');
    $total_record = (int) Input::post('total_record', 0);
    $fieldname = ($_holderG=='_attrs') ? 'attrs' : 'properties';
    ###
    $html = '<tr id="'.$uid.'" class="tr_'.($_holderG=='_attrs'?'attrs':'property').' '.$cls.'">
		<td class="text-center">
			'.($_openFrom=='_project'?'<div class="mySortableHandler">
				'.$core->makeIcon('arrows').'
			</div>':($total_record+1)).'
		</td>
		<td class="text-left">
			<input type="hidden" name="'.$fieldname.'['.$uid.'][id]" value="0">
			<input type="hidden" name="'.$fieldname.'['.$uid.'][lock]" value="0">
			<input class="form-control title_field_'.$uid.'" name="'.$fieldname.'['.$uid.'][title]" placeholder="Nhập tiêu đề" type="text" autocomplete="off"  />
		</td>
		<td class="text-left">
			<input type="text" class="form-control content_field_'.$uid.'" name="'.$fieldname.'['.$uid.'][content]" placeholder="Nhập giá trị" maxlength="255" autocomplete="off" />
		</td>
		'.($_holderG=='_properties' ? '<td class="text-center">
			<label class="switch">
				<input type="checkbox" name="'.$fieldname.'['.$uid.'][is_hot]" value="1">
				<span class="slider round"></span>
			</label>
		</td>':'').'
		<td class="text-center">
			'.($_holderG=='_attrs'?'<a title="Xóa" href="javascript:void(0);" class="btn btn-default" uid="'.$uid.'" onClick="delete_property(this, event)">'.$core->makeIcon('trash').'</a>':'<div class="btn-group">
				<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown">
					<i class="icon-cog"></i> 
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" style="right:0px !important;left:auto; min-width:130px">
					<li><a href="javascript:void(0);" title="Tải File" uid="'.$uid.'" 
					onClick="select_file(this, event)">'.$core->makeIcon('upload', 'Tải File').'</a></li>
					<li><a href="javascript:void(0);" title="Xóa"  uid="'.$uid.'" 
					onClick="delete_property(this, event)">'.$core->makeIcon('trash', 'Xóa').'</a></li>
				</ul>
			</div>').'
		</td>
	</tr>';
    // Return
    echo $html; die();
}
function default_open_block(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsStock = new Stock();
    $clsMember = new Member();
    $clsProject = new Project();
    $clsProjectMeta = new ProjectMeta();
    $clsProperty = new Property();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $_openFrom = Input::post('_openFrom', '_project');
    if($_openFrom=='_stock'){
        $toId = Input::post('toId');
        $smarty->assign('toId', $toId);
    }
    #
    $field = "{$clsProperty->pkey},title";
    $list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' AND `for_id`='{$block_id}'", $field);
    $smarty->assign('list_buildings', $list_buildings);
    $titlePgae = "Thêm phân khu";
    $stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
    $oneBlock = $list_props = $arr_project_admins = $price_field_configs = array();
    $more_information = array('investor_id' => 0);
    $info_more = [
        "total_stock"	=>	[
            "title"	=>	"Số căn",
            "value"	=>	"",
            "class"	=>	"numberonly",
            "placeholder"	=>	"1000",
        ], "con_start_date"	=>	[
            "title"	=>	"Ngày khởi công",
            "value"	=>	"",
            "class"	=>	"",
            "placeholder"	=>	"Q1/2023",
        ], "handover_date"	=>	[
            "title"	=>	"Ngày ban giao",
            "value"	=>	"",
            "class"	=>	"QIV/2028",
            "placeholder"	=>	"1000",
        ], "price_range"	=>	[
            "title"	=>	"Khoảng giá",
            "value"	=>	"",
            "class"	=>	"",
            "placeholder"	=>	"70-100",
        ], "price_range_m2"	=>	[
            "title"	=>	"Khoảng giá/m2",
            "value"	=>	"",
            "class"	=>	"",
            "placeholder"	=>	"70-100",
        ],
    ];
    if(!empty($block_id)){
        $titlePgae = "Sửa phân khu";
        $oneBlock = $clsProperty->getOne($block_id);
        $stock_type = $oneBlock['parent_id'];
        $more_information = $oneBlock['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $price_field_configs = $core->get_field($more_information, "price_field_configs", []);
        $start_booking = !empty($more_information["start_booking"]) ? date("Y-m-d\TH:i",$more_information["start_booking"]) : "";
        $end_booking = !empty($more_information["end_booking"]) ? date("Y-m-d\TH:i",$more_information["end_booking"]) : "";
        $smarty->assign('start_booking', $start_booking);
        $smarty->assign('end_booking', $end_booking);
        $arr_project_admins = $core->get_field($more_information, "project_admins", []);
        $list_props = $clsProjectMeta->getAll("`type`='block' and `for_id`='{$block_id}' order by `order_no` ASC");
        if(!empty($more_information["info_more"])) {
            $info_more_db = $core->get_field($more_information, "info_more", []);
            foreach($info_more as $key => $val){
                if(isset($info_more_db[$key])){
                    $info_more[$key]['value'] = $info_more_db[$key]['value'];
                }
            }
        }
    } else {
        $oneBlock['order_no'] = $clsProperty->getMaxOrderNo();
    }
    $clsProfile = new Profile();
    $field = "{$clsProfile->pkey},full_name,email,phone";
    $list_profile = $clsProfile->getAll("is_trash=0 and status_id <> '"._STATUS_STAFF_OFF_ID."'", $field);
    $smarty->assign('list_profile', $list_profile);
    $list_price_fields = $clsStock->getPriceField($stock_type);
	$list_status_contract = $clsProperty->getAll("`property_type`='_STATUS_CONTRACT' AND `is_trash`='0' AND `property_id` <> '"._CONTRACT_STATUS_DONE_ID."'");
    $smarty->assign('list_status_contract', $list_status_contract);
    $smarty->assign('list_price_fields', $list_price_fields);
    // $clsISO->print_pre($list_price_fields); die();
    $smarty->assign('info_more', $info_more);
    $smarty->assign('block_id', $block_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('oneBlock', $oneBlock);
    $smarty->assign('list_props', $list_props);
    $smarty->assign('more_information', $more_information);
    $smarty->assign('arr_project_admins', $arr_project_admins);
    $smarty->assign('price_field_configs', $price_field_configs);
    $smarty->assign('titlePgae', $titlePgae);
    $smarty->assign('_openFrom', $_openFrom);
    $smarty->assign('clsProperty', $clsProperty);
    $smarty->assign('clsMember', $clsMember);
    // Return
    $smarty->assign('core', $core);
    $smarty->assign('template_type', '_form');
    $html = $core->build('_ajax.block.tpl');
    echo $html; die();
}

function default_pop_save_block(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProjectMeta = new ProjectMeta();
    $clsProperty = new Property();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $property_code = Input::post('property_code', "");
    $price_field_configs = Input::post('price_field_configs');
    // $clsISO->print_pre($price_field_configs); die();
    $investor_id = (int) Input::post('investor_id', 0);
    $info_more = Input::post('info_more',[]);
    $project_admins = Input::post('project_admins',[]);
    $project_manager = Input::post('project_manager',0);
    $stock_support_id = (int) Input::post('stock_support_id', 0);
    // $construction_type = Input::post('construction_type');
    // $construction_style = Input::post('construction_style');
    $layout_ns = Input::post('layout_ns');
    $layout_ms = Input::post('layout_ms');
    $sales_policy = Input::post('sales_policy');
    $on_sale = (int) Input::post('on_sale',0);
    $price_range = Input::post('price_range');
    $billing_type = (int) Input::post('billing_type', 0);
    $tiles_link = Input::post('tiles_link');
    $is_tiles = (int) Input::post('is_tiles', 0);
    $is_map_tiles = (int) Input::post('is_map_tiles', 0);
    $max_zoom = (int) Input::post('max_zoom', 0);
    $center_point = Input::post('center_point');
    $max_bound = Input::post('max_bound');
    $tms_enable = (int) Input::post('tms_enable', 0);
    // $street = Input::post('street');
    // $ward = Input::post('ward');
    $title = Input::post('title');
    $attrs = Input::post('attrs');
    $is_stock_fund_of = (int) Input::post('is_stock_fund_of', 0);
    $vr_link = Input::post('vr_link');
    $vr_source = Input::post('vr_source');
    $image = Input::post('image',"");
    $bgcolor = Input::post('bgcolor',"#FFFFFF");
    $textcolor = Input::post('textcolor',"#000000");
    $is_project = (int) Input::post('is_project', 0);
    $is_booking = (int) Input::post('is_booking', 0);
    $start_booking = Input::post('start_booking', "");
    $end_booking = Input::post('end_booking', "");
    $status_contract = Input::post('status_contract', []);
    $start_booking = (!empty($is_booking) && !empty($start_booking)) ? strtotime($start_booking) : "";
    $end_booking = (!empty($is_booking) && !empty($end_booking)) ? strtotime($end_booking) : "";

    if(!empty($attrs)){
        foreach($attrs as $key => $val){
            if(empty($val['title']) && empty($val['content'])){
                unset($attrs[$key]);
            }
        }
    }
    #
    $msg = '_error';
    $cnd = "`property_type`='_BLOCK' and `for_id`='{$project_id}'";
    if($block_id > 0){
        $oneBlock = $clsProperty->getOne($block_id);
        $more_information = $oneBlock['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $more_information['attrs'] = $attrs;
        $more_information['vr_link'] = $vr_link;
        $more_information['vr_source'] = $vr_source;
        $more_information['investor_id'] = $investor_id;
        $more_information['stock_support_id'] = $stock_support_id;
        $more_information['price_field_configs'] = $price_field_configs;
        $more_information['project_manager'] = $project_manager;
        $more_information['project_admins'] = $project_admins;
        $more_information['info_more'] = $info_more;
        $more_information['status_contract'] = $status_contract;
        $more_information['project_admins_slash'] = $clsISO->makeSlashListFromArrayRoot($project_admins);
        // $more_information['construction_type'] = $construction_type;
        // $more_information['construction_style'] = $construction_style;
        $more_information['on_sale'] = $on_sale;
        $more_information['is_booking'] = $is_booking;
        $more_information['start_booking'] = $start_booking;
        $more_information['end_booking'] = $end_booking;
        $more_information['is_project'] = $is_project;
        $more_information['layout_ns'] = $layout_ns;
        $more_information['layout_ms'] = $layout_ms;
        $more_information['sales_policy'] = $sales_policy;
        $more_information['price_range'] = $price_range;
        $more_information['billing_type'] = $billing_type;
        #- Map Tiles
        $more_information['tiles_link'] = $tiles_link;
        $more_information['is_tiles'] = $is_tiles;
        $more_information['is_map_tiles'] = $is_map_tiles;
        $more_information['max_zoom'] = $max_zoom;
        $more_information['center_point'] = $center_point;
        $more_information['max_bound'] = $max_bound;
        $more_information['tms_enable'] = $tms_enable;
        $more_information['project_id'] = $project_id;
        // $more_information['street'] = $street;
        // $more_information['ward'] = $ward;
        $more_information['is_stock_fund_of'] = $is_stock_fund_of;
        $more_information['bgcolor'] = $bgcolor;
        $more_information['textcolor'] = $textcolor;
        if($clsProperty->updateOne($block_id, array(
            'title' => $title,
            'property_code' => $property_code,
            'slug' => $core->replaceSpace($title),
            'parent_id' => Input::post('parent_id', 0),
            'intro' => Input::post('intro'),
            'order_no' => Input::post('order_no'),
            'upd_date' => time(),
            'image' => $image,
            'user_id_update' => $core->_USER['user_id'],
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = '_success';
            #activity log
            $oneProject = $clsProject->getOne($project_id,"title");
            $clsActivityLog = new ActivityLog();
            $log = $clsActivityLog->addActivityLog("Property","update",['field' => "block","property_type" =>"BLOCK","title" =>$title,"title_project" =>$oneProject['title']]);
        }
    } else {
        $more_information = array();
        $more_information['attrs'] = $attrs;
        $more_information['vr_link'] = $vr_link;
        $more_information['vr_source'] = $vr_source;
        $more_information['investor_id'] = $investor_id;
        $more_information['stock_support_id'] = $stock_support_id;
        $more_information['price_field_configs'] = $price_field_configs;
        $more_information['project_manager'] = $project_manager;
        $more_information['project_admins'] = $project_admins;
        $more_information['info_more'] = $info_more;
        $more_information['status_contract'] = $status_contract;
        $more_information['project_admins_slash'] = $clsISO->makeSlashListFromArrayRoot($project_admins);
        // $more_information['construction_type'] = $construction_type;
        // $more_information['construction_style'] = $construction_style;
        $more_information['layout_ns'] = $layout_ns;
        $more_information['layout_ms'] = $layout_ms;
        $more_information['sales_policy'] = $sales_policy;
        $more_information['on_sale'] = $on_sale;
        $more_information['is_booking'] = $is_booking;
        $more_information['start_booking'] = $start_booking;
        $more_information['end_booking'] = $end_booking;
        $more_information['is_project'] = $is_project;
        $more_information['price_range'] = $price_range;
        $more_information['billing_type'] = $billing_type;
        #- Map Tiles
        $more_information['tiles_link'] = $tiles_link;
        $more_information['is_tiles'] = $is_tiles;
        $more_information['is_map_tiles'] = $is_map_tiles;
        $more_information['max_zoom'] = $max_zoom;
        $more_information['center_point'] = $center_point;
        $more_information['max_bound'] = $max_bound;
        $more_information['tms_enable'] = $tms_enable;
        $more_information['project_id'] = $project_id;
        // $more_information['street'] = $street;
        // $more_information['ward'] = $ward;
        $more_information['is_stock_fund_of'] = $is_stock_fund_of;
        if($clsProperty->countItem("{$cnd} and slug='".$core->replaceSpace($title)."'") > 0){
            $msg = '_duplicate';
        } else {
            $block_id = $clsProperty->getMaxId();
            if($clsProperty->insert(array(
                $clsProperty->pkey => $block_id,
                'property_type' => '_BLOCK',
                'property_code' => $property_code,
                'for_id' => $project_id,
                'parent_id' => Input::post('parent_id', 0),
                'title' => $title,
                'slug' => $core->replaceSpace($title),
                'intro' => Input::post('intro'),
                'order_no' => Input::post('order_no'),
                'reg_date' => time(),
                'upd_date' => time(),
                'user_id' => $core->_USER['user_id'],
                'user_id_update' => $core->_USER['user_id'],
                'image' => $image,
                'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
            ))){
                $msg = '_success';
                #activity log
                $oneProject = $clsProject->getOne($project_id,"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","insert",['field' => "block","property_type" =>"BLOCK","title" =>$title,"title_project" =>$oneProject['title']]);
            }
        }
    }
    // Return
    echo json_encode(array(
        'msg' => $msg,
        'block_id' => $block_id,
        'property_type' => '_BLOCK'
    )); die();
}
function default_delete_block(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    ####
    $result = '_error'; $message = "";
    if($block_id > 0){
        $oneBlock = $clsProperty->getOne($block_id,"title");
        if($clsProperty->countItem("property_type='_BUILDING' and for_id='{$block_id}'") > 0){
            $message = 'Lỗi! Phân khu này tồn tại tòa nhà';
        } else {
            if($clsProperty->deleteOne($block_id)){
                $result = '_success';

                #activity log
                $oneProject = $clsProject->getOne($project_id,"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","delete",['field' => "block","property_type" =>"BLOCK","title" =>$oneBlock["title"],"title_project" =>$oneProject['title']]);
            }
        }
    }
    // Return
    echo json_encode(array(
        'result' => $result,
        'message' => $message
    )); die();
}
function default_load_blocks(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $project_id = (int) Input::post('project_id', 0);
    $field = "{$clsProperty->pkey},title,`is_locked`,`is_trash`,`parent_id`,`order_no`";
    $list_blocks = $clsProperty->getAll("is_trash=0 and property_type='_BLOCK' 
	and for_id='{$project_id}' order by order_no ASC", $field);
    if(!empty($list_blocks)){
        foreach($list_blocks as $key => $val){
            $block_id = $val[$clsProperty->pkey];
            $property_type = ($val['parent_id']==_BLOCK_TYPE_LOWFLOOR_SALE) ? '_RANGE': '_BUILDING';
            $list_buildings = $clsProperty->getAll("`property_type`='{$property_type}' and `for_id`='{$block_id}' order by order_no ASC", $field);
            $list_blocks[$key]['list_buildings'] = $list_buildings;
        }
    }
    $smarty->assign('project_id', $project_id);
    $smarty->assign('list_blocks', $list_blocks);
    // Return
    $smarty->assign('core', $core);
    $smarty->assign('template_type', '_list');
    $html = $core->build('_ajax.block.tpl');
    echo json_encode(array(
        'html' => $html
    )); die();
}
function default_add_progress_media(){
    global $core,$_frontIsLoggedin_user_id,$clsISO,$smarty;
    $uid = $clsISO->getUniqid();
    $_holderG = Input::post('_holderG', '_properties');
    $_openFrom = Input::post('_openFrom', '_project');
    $total_record = (int) Input::post('total_record', 0);
    $fieldname = ($_holderG=='_attrs') ? 'attrs' : 'properties';
    ###

    $smarty->assign("core",$core);
    $smarty->assign("uid",time());
    $html = $core->build("_ajax.progress_media.tpl");
    // Return
    echo $html; die();
}

function default_delete_progress_media(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProgressMedia = new ProjectProgressMedia();
    $index = 0;
    $id = (int) Input::post('progress_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    ####
    $result = '_error'; $message = $html = "";
    if($id > 0){
        if($clsProgressMedia->deleteOne($id)){
            $result = '_success';
        }
    }
    $smarty->assign("core", $core);
    $list_progress = $clsProgressMedia->getAll("block_id=$block_id and project_id=$project_id");
    foreach ($list_progress as $item) {
        $html .= $core->build('_ajax.progress_media.tpl', ["item" => $item, "uid" => $item['id'], "i" => ++$index]);
    }

    // Return
    echo json_encode(array(
        'result' => $result,
        'message' => $message,
        'html' => $html
    )); die();
}
function default_add_progress(){
    global $core,$_frontIsLoggedin_user_id,$clsISO,$smarty;
    $uid = $clsISO->getUniqid();
    $_holderG = Input::post('_holderG', '_properties');
    $_openFrom = Input::post('_openFrom', '_project');
    $total_record = (int) Input::post('total_record', 0);
    $fieldname = ($_holderG=='_attrs') ? 'attrs' : 'properties';
    ###

    $smarty->assign("core",$core);
    $smarty->assign("uid",time());
    $html = $core->build("_ajax.progress_item.tpl");
    // Return
    echo $html; die();
}
function default_open_progress() {
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;

    $clsProjectProgress = new ProjectProgressItems();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $_openFrom = Input::post('_openFrom', '_project');
    if($_openFrom=='_stock'){
        $toId = Input::post('toId');
        $smarty->assign('toId', $toId);
    }
    #
    $field = "$clsProjectProgress->pkey, title,progress_date,is_active,media,description,project_id,building_id,block_id";
    $cond = "project_id = $project_id and block_id = $block_id";
    $titlePage = ($block_id > 0) ? "Tiến độ phân khu" : "Tiến độ dự án";
    $list_project_progress = array();
    if(!empty($project_id)) {
        # Mốc hiển thị theo id ASC (thứ tự thêm) — progress_date là text tự do, không sort được
        $list_project_progress = $clsProjectProgress->getAll($cond." order by $clsProjectProgress->pkey ASC", $field);
        if(!empty($list_project_progress)){
            foreach($list_project_progress as $k => $v){
                $list_project_progress[$k]['media_json'] = !empty($v['media']) ? $v['media'] : '[]';
            }
        }
    }
    // Return
    $smarty->assign('block_id', $block_id);
    $smarty->assign('building_id', $building_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('_openFrom', $_openFrom);
    $smarty->assign('core', $core);
    $smarty->assign('list_project_progress', $list_project_progress);
    $smarty->assign('titlePage', $titlePage);
    $smarty->assign('template_type', '_form');
    $html = $core->build('_ajax.progress.tpl');
    echo $html; die();
}
function default_pop_save_progress(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProjectProgress = new ProjectProgressItems();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);

    #
    $msg = '_error';

    # progress items (mỗi mốc kèm media JSON) — project_id>0 (block_id=0 = tiến độ dự án)
    $attrs = $_POST['attrs'] ?? [];
    if ($project_id > 0) {
        foreach ($attrs as $key => $row) {
            if(!isset($row['title']) && !isset($row['media'])) continue;
            # chuẩn hóa cột media: JSON hợp lệ hoặc rỗng
            $media = isset($row['media']) ? trim($row['media']) : '';
            if(!empty($media)){
                $tmp = $clsISO->to_array_json($media);
                $media = !empty($tmp) ? json_encode($tmp, JSON_UNESCAPED_UNICODE) : '';
            }
            $data = array(
                'project_id'     => $project_id,
                'block_id'       => $block_id,
                'building_id'    => $building_id,
                'title'          => trim($row['title']),
                'progress_date'  => $row['progress_date'],
                'is_active'      => $row['is_active'],
                'media'          => $media,
                'description'    => isset($row['description']) ? $row['description'] : '',
                'upd_date'       => time(),
                'user_id_update' => $core->_USER['user_id'],
            );
            $existing = $clsProjectProgress->getOne($key, $clsProjectProgress->pkey);
            if ($existing) {
                $clsProjectProgress->updateOne($key, $data);
            } else {
                $id = $clsProjectProgress->getMaxId();
                $data[$clsProjectProgress->pkey] = $id;
                $data['reg_date'] = time();
                $data['user_id']  = $core->_USER['user_id'];
                $clsProjectProgress->insert($data);
            }
        }
        $msg = '_success';
    }
    // Return
    echo json_encode(array(
        'msg' => $msg,
        'block_id' => $block_id,
        'property_type' => '_BLOCK'
    )); die();
}
# Đồng bộ media từ folder Google Drive -> trả danh sách item (video trước, ảnh sau)
function default_progress_sync_drive(){
    global $core,$clsISO;
    $folder_url = trim(Input::post('folder_url', ''));
    $existing = Input::post('existing', '');
    $folder_id = $clsISO->getGoogleId($folder_url);
    if(empty($folder_id)){
        echo json_encode(array('error' => 'Link folder Google Drive không hợp lệ (cần dạng .../drive/folders/...).')); die();
    }
    $files = array(); $errMsg = '';
    try {
        $clsGoogleUpload = new GoogleUpload();
        $files = $clsGoogleUpload->listFolderMedia($folder_id);
    } catch (\Throwable $e) {
        $errMsg = $e->getMessage();
    }
    if(empty($files)){
        echo json_encode(array(
            'error' => !empty($errMsg) ? ('Drive lỗi: '.$errMsg) : 'Không đọc được folder. Kiểm tra folder đã chia sẻ "Bất kỳ ai có link – Người xem".',
            'fallback_iframe' => $clsISO->getIframeUrl($folder_url)
        )); die();
    }
    $videos = array(); $images = array();
    foreach($files as $f){
        $id = $f['id'];
        if($f['type'] == 'video'){
            $videos[] = array(
                'source' => 'drive', 'type' => 'video', 'ref' => $id, 'name' => $f['name'],
                'url'   => sprintf('https://drive.google.com/file/d/%s/preview', $id),
                'thumb' => sprintf('https://drive.google.com/thumbnail?id=%s&sz=w800', $id)
            );
        } else {
            $images[] = array(
                'source' => 'drive', 'type' => 'image', 'ref' => $id, 'name' => $f['name'],
                'url'   => sprintf('https://drive.google.com/file/d/%s/view', $id),
                'thumb' => sprintf('https://drive.google.com/thumbnail?id=%s&sz=w1200', $id)
            );
        }
    }
    $drive_items = array_merge($videos, $images);
    # Giữ lại item nguồn upload/youtube/url do user thêm tay (chỉ thay nhóm drive)
    $keep = array();
    $ex = !empty($existing) ? $clsISO->to_array_json($existing) : array();
    if(!empty($ex) && is_array($ex)){
        foreach($ex as $it){
            if(isset($it['source']) && $it['source'] != 'drive'){ $keep[] = $it; }
        }
    }
    echo json_encode(array(
        'items' => array_merge($drive_items, $keep),
        'count' => count($drive_items),
        'folder' => $folder_url
    )); die();
}
# Upload 1 ảnh lên server -> trả 1 item
function default_progress_upload(){
    global $core;
    $url = '';
    if(!empty($_FILES['file']) && !empty($_FILES['file']['name'])){
        $clsUploadFile = new UploadFile();
        $up = $clsUploadFile->uploadItem($_FILES['file'], "/progress", EXTENSION_IMAGE_UPLOAD);
        if(!empty($up) && $up !== 0){ $url = $up; }
    }
    if(empty($url)){
        echo json_encode(array('error' => 'Upload thất bại.')); die();
    }
    echo json_encode(array('item' => array(
        'source' => 'upload', 'type' => 'image', 'ref' => $url,
        'url' => $url, 'thumb' => $url, 'name' => basename($url)
    ))); die();
}
# Dán 1 link lẻ (YouTube / Drive file / ảnh / video) -> trả 1 item
function default_progress_add_link(){
    global $core,$clsISO;
    $link = trim(Input::post('link', ''));
    if(empty($link)){ echo json_encode(array('error'=>'Chưa nhập link.')); die(); }
    $item = null;
    if($clsISO->checkContainer($link, 'youtu', '')){
        if(preg_match("/(?:youtu\.be\/|youtube\.com\/(?:.*v=|embed\/|v\/))([^\?&\"'>]+)/", $link, $m)){
            $vid = $m[1];
            $item = array('source'=>'youtube','type'=>'video','ref'=>$vid,
                'url'=>'https://www.youtube.com/embed/'.$vid,
                'thumb'=>'https://img.youtube.com/vi/'.$vid.'/hqdefault.jpg','name'=>'YouTube');
        }
    } else if($clsISO->checkContainer($link, 'drive.google.com', '')){
        $id = $clsISO->getGoogleId($link);
        if(!empty($id)){
            $clsGoogleUpload = new GoogleUpload();
			$file_type = $clsGoogleUpload->getFileType($id);
			$isVid = ($file_type != "" && $file_type == "video/mp4") ? true : false;
            //$isVid = $clsISO->checkContainer($link, '/preview', '') ? true : false;
            $item = array('source'=>'drive','type'=>($isVid?'video':'image'),'ref'=>$id,
                'url'=> $isVid ? sprintf('https://drive.google.com/file/d/%s/preview',$id) : sprintf('https://drive.google.com/file/d/%s/view',$id),
                'thumb'=>sprintf('https://drive.google.com/thumbnail?id=%s&sz=w1200',$id),'name'=>'Drive');
        }
    } else if($clsISO->isImage($link)){
        $item = array('source'=>'url','type'=>'image','ref'=>$link,'url'=>$link,'thumb'=>$link,'name'=>basename($link));
    } else if($clsISO->isFileVideo($link)){
        $item = array('source'=>'url','type'=>'video','ref'=>$link,'url'=>$link,'thumb'=>'','name'=>basename($link));
    }
    if(empty($item)){ echo json_encode(array('error'=>'Link không nhận diện được (YouTube / Drive / ảnh / video).')); die(); }
    echo json_encode(array('item'=>$item)); die();
}
function default_delete_progress(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProjectProgress = new ProjectProgressItems();
    $clsProperty = new Property();
    $index = 0;
    $id = (int) Input::post('progress_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    ####
    $result = '_error'; $message = $html = "";
    if($id > 0){
        if($clsProjectProgress->deleteOne($id)){
            $result = '_success';
        }
    }
    $smarty->assign("core", $core);
    $list_progress = $clsProjectProgress->getAll("block_id=$block_id and project_id=$project_id");
    foreach ($list_progress as $item) {
        $html .= $core->build('_ajax.progress_item.tpl', ["_Item" => $item, "uid" => $item['id'], "index" => ++$index, "media_json" => (!empty($item['media']) ? $item['media'] : '[]')]);
        // (description đã có trong $item -> template đọc {$_Item.description})
    }

    // Return
    echo json_encode(array(
        'result' => $result,
        'message' => $message,
        'html' => $html
    )); die();
}
function default_load_progess(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $project_id = (int) Input::post('project_id', 0);
    $field = "{$clsProperty->pkey},title,`is_locked`,`is_trash`,`parent_id`,`order_no`";
    $list_blocks = $clsProperty->getAll("is_trash=0 and property_type='_BLOCK' 
	and for_id='{$project_id}' order by order_no ASC", $field);
    if(!empty($list_blocks)){
        foreach($list_blocks as $key => $val){
            $block_id = $val[$clsProperty->pkey];
            $property_type = ($val['parent_id']==_BLOCK_TYPE_LOWFLOOR_SALE) ? '_RANGE': '_BUILDING';
            $list_buildings = $clsProperty->getAll("`property_type`='{$property_type}' and `for_id`='{$block_id}' order by order_no ASC", $field);
            $list_blocks[$key]['list_buildings'] = $list_buildings;
        }
    }
    $smarty->assign('project_id', $project_id);
    $smarty->assign('list_blocks', $list_blocks);
    // Return
    $smarty->assign('core', $core);
    $smarty->assign('template_type', '_list');
    $html = $core->build('_ajax.block.tpl');
    echo json_encode(array(
        'html' => $html
    )); die();
}
function default_updateOrder(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $block_id = (int) Input::post('block_id', 0);
    $order_no = (int) Input::post('order_no', 0);
    $res = ["result" => false];
    if($clsProperty->updateOne($block_id, array(
        'order_no' => $order_no
    ))){
        $res = ["result" => true];
    }
    echo json_encode($res); die();
}
function default_add_policy(){
    // error_reporting(E_ALL);
    // ini_set('display_errors', '1');
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProperty = new Property();
    $gId = $clsISO->getUniqid();
    $toId = Input::post('toId');
    $block_id = (int) Input::post('block_id', 0);
    ###
    $field = "{$clsProperty->pkey},title";
    $list_buildings = $clsProperty->getAll("property_type='_BUILDING' and for_id='{$block_id}'", $field);
    $html = '<tr class="tr_csbh">
		<td class="text-left">
			<input type="text" name="sales_policy['.$gId.'][title]" 
				class="form-control requried" placeholder="Tiêu đề" />
		</td>
		<td class="text-left">
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Hình ảnh CSBH" 
					name="sales_policy['.$gId.'][image]" id="sales_policy_'.$gId.'" value="" />
				<div class="input-group-btn">
					<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gid="sales_policy_'.$gId.'" toId="'.$toId.'"><i class="fa fa-upload"></i></button>
				</div>
			</div>
		</td>
		<td class="text-left">
			<select class="form-control iso-select2" 
				name="sales_policy['.$gId.'][building]" multiple="true">';
    if(!empty($list_buildings)){
        foreach($list_buildings as $key => $val){
            $html.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
        }
        unset($list_buildings);
    }
    $html.= '</select>
		</td>
		<td class="text-center">
			<button type="button" class="btn btn-icon btn-default" 
				onClick="$Core.project.delete_policy(this, event)">
				<i class="fa fa-trash"></i>
			</button>
		</td>
	</tr>';
    // Return
    echo $html; die();
}
function default_open_building(){
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsMember = new Member();
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsProjectMeta = new ProjectMeta();
    $smarty->assign('clsMember', $clsMember);
    $smarty->assign('clsProject', $clsProject);
    $smarty->assign('clsProperty', $clsProperty);
    ###
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $_openFrom = Input::post('_openFrom', "_project");
    $toId = Input::post('toId', "");
    $oneBlock = $clsProperty->getOne($block_id, "parent_id,more_information");
    $stock_type = (int) $oneBlock['parent_id'];
    $more_information = $oneBlock['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $stock_support_id = $core->get_field($more_information, "stock_support_id", 0);
    if($stock_type==0) $stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
    $smarty->assign('stock_type', $stock_type);
    $info_more = [
        "total_stock"	=>	[
            "title"	=>	"Số căn",
            "value"	=>	"",
            "class"	=>	"numberonly",
            "placeholder"	=>	"1000",
        ],
        "price_range_m2"	=>	[
            "title"	=>	"Khoảng giá/m2",
            "value"	=>	"",
            "class"	=>	"",
            "placeholder"	=>	"70-100",
        ],
    ];
    ###
    if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
        $field = "{$clsProperty->pkey},title";
        $list_blocks = $clsProperty->getAll("property_type='_BLOCK' and `for_id`='{$project_id}' 
			order by `order_no` ASC", $field);
        $smarty->assign('list_blocks', $list_blocks);
    }
    $titlePgae = "Thêm ".($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE?"tòa":"dãy")." nhà";
    $oneBuilding = $list_props = $floor_range_configs = $floor_level_configs = array();
    $more_information = array(
        'properties' => array(
            'layout' => array('title' => 'Layout', 'lock' => true, 'link' => ''),
            'handover' => array('title' => 'Tiêu chuẩn bàn giao', 'lock' => true, 'link' => ''),
            'utility' => array('title' => 'Tiện ích nội khu', 'lock' => true, 'link' => ''),
            'roof' => array('title' => 'Layout bóc mái', 'lock' => true,'link' => ''),
            'video' => array('title' => 'Video căn mẫu', 'lock' => true, 'link' => '')
        ),
        'stock_support_id' => $stock_support_id,
        'hide_row_floor_special' => 0
    );
    $floor_level_configs = array(
        'low_floor' => array(
            'title' => 'Tầng thấp',
            'from' => 0,
            'to' => 0,
        ), 'mid_floor' => array(
            'title' => 'Tầng trung',
            'from' => 0,
            'to' => 0,
        ), 'high_floor' => array(
            'title' => 'Tầng cao',
            'from' => 0,
            'to' => 0,
        )
    );
    if(!empty($building_id)){
        $titlePgae = "Sửa ".($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE?"tòa":"dãy")." nhà";
        $oneBuilding = $clsProperty->getOne($building_id);
        $more_information = $oneBuilding['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $floor_range_configs = $core->get_field($more_information, "floor_range_configs", []);
        $floor_level_configs = $core->get_field($more_information, "floor_level_configs", $floor_level_configs);
        $list_props = $clsProjectMeta->getAll("`type`='building' AND `for_id`='{$building_id}' 
			ORDER BY `order_no` ASC");
        if(!empty($more_information["info_more"])) {
            $info_more = $more_information["info_more"];
        }
        // $clsISO->print_pre($more_information); die();
        $field = "{$clsProperty->pkey},title";
        $arrViews = $clsProperty->getAll("property_type='_VIEW' order by order_no ASC", $field);
        $arrBedRooms = $clsProperty->getAll("property_type='_BEDROOM' order by order_no ASC", $field);
        $arrDirections = $clsProperty->getAll("property_type='_DIRECTION' order by order_no ASC", $field);
        $smarty->assign('arrViews', $arrViews);
        $smarty->assign('arrBedRooms', $arrBedRooms);
        $smarty->assign('arrDirections', $arrDirections);
    }
    $smarty->assign('info_more', $info_more);
    $smarty->assign('toId', $toId);
    $smarty->assign('_openFrom', $_openFrom);
    $smarty->assign('block_id', $block_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('building_id', $building_id);
    $smarty->assign('oneBuilding', $oneBuilding);
    $smarty->assign('list_props', $list_props);
    $smarty->assign('floor_range_configs', $floor_range_configs);
    $smarty->assign('floor_level_configs', $floor_level_configs);
    $smarty->assign('more_information', $more_information);
    $smarty->assign('titlePgae', $titlePgae);
    // Return
    $smarty->assign('core', $core);
    $html = $core->build('_ajax.building.tpl');
    $callback  = '$(\'#inputor\').atwho({at: \'%\',
		data:[\'[MaToa]\', \'[Tang]\', \'[CanHo]\']
	});';
    echo json_encode(array(
        'html' => $html,
        'toId' => $toId,
        'callback' => $callback
    )); die();
}
function default_pop_save_building(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsProjectMeta = new ProjectMeta();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
    $number_house = (int) Input::post('number_house', 0);
    $number_floor = (int) Input::post('number_floor', 0);
    $stock_support_id = (int) Input::post('stock_support_id', 0);
    $is_locked = (int) Input::post('is_locked', 0);
    $is_trash = (int) Input::post('is_trash', 0);
    $floor = Input::post('floor', "");
    $layout_ns = Input::post('layout_ns', "");
    $property_code = Input::post('property_code', "");
    $stock_template = Input::post('stock_template', "");
    $number_of_elevator = Input::post('number_of_elevator', "");
    $number_of_besement = Input::post('number_of_besement', "");
    $construction_style = Input::post('construction_style', "");
    $on_sale = (int)Input::post('on_sale', 0);
    $price_range = Input::post('price_range', "");
    $hide_row_floor_special = (int) Input::post('hide_row_floor_special', 0);
    $stock_template = !empty($stock_template)
        ? preg_replace('/\s+/','',$stock_template) : "";
    $image = Input::post('image',"");
    ####
    $title = Input::post('title');
    $title_vn = Input::post('title_vn');
    if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
        $ns_block_id = (int) Input::post('ns_block_id', $block_id);
        if($building_id > 0){
            if($clsProperty->updateOne($building_id, array(
                'property_code' => $property_code,
                'for_id' => $ns_block_id,
                'title' => $title,
                'title_vn' => $title_vn,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'upd_date' => time(),
                'image' => $image,
                'user_id_update' => $core->_USER['user_id']
            ))){
                $msg = "_success";
            }
        } else {
            $building_id = $clsProperty->getMaxId();
            if($clsProperty->insert(array(
                $clsProperty->pkey => $building_id,
                'property_type' => '_RANGE',
                'property_code' => $property_code,
                'for_id' => $ns_block_id,
                'title' => $title,
                'title_vn' => $title_vn,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'order_no' => $clsProperty->getMaxOrderNo(),
                'reg_date' => time(),
                'upd_date' => time(),
                'user_id' => $core->_USER['user_id'],
                'image' => $image,
                'user_id_update' => $core->_USER['user_id']
            ))){
                $msg = "_success";
            }
        }
    } else {
        $attrs = Input::post('attrs');
        $title_ts = Input::post('title_ts');
        $template = Input::post('template');
        $properties = Input::post('properties');
        $floor_hierarchy = Input::post('floor_hierarchy');
        $clsISO->print_pre($floor_hierarchy); die();
        if(!empty($attrs)){
            foreach($attrs as $key => $val){
                if(empty($val['title']) && empty($val['content'])){
                    unset($attrs[$key]);
                }
            }
        }
        if(!empty($properties)){
            foreach($properties as $key => $val){
                if(empty($val['title']) && empty($val['content'])){
                    unset($properties[$key]);
                }
            }
        }
        #
        $msg = '_error';
        $cnd = "`property_type`='_BUILDING' and `for_id`='{$block_id}'";
        if($building_id > 0){
            $oneBuilding = $clsProperty->getOne($building_id);
            $more_information = $oneBuilding['more_information'];
            $more_information = $clsISO->to_array_json($more_information);
            $more_information['floor'] = $floor;
            $more_information['layout_ns'] = $layout_ns;
            $more_information['title_ts'] = $title_ts;
            $more_information['template'] = $template;
            $more_information['stock_template'] = $stock_template;
            $more_information['number_house'] = $number_house;
            $more_information['number_floor'] = $number_floor;
            $more_information['stock_support_id'] = $stock_support_id;
            $more_information['hide_row_floor_special'] = $hide_row_floor_special;
            $more_information['number_of_elevator'] = $number_of_elevator;
            $more_information['number_of_besement'] = $number_of_besement;
            $more_information['construction_style'] = $construction_style;
            $more_information['on_sale'] = $on_sale;
            $more_information['price_range'] = $price_range;
            $more_information['attrs'] = $attrs;
            // $clsProperty->setDebug(false);
            if($clsProperty->updateOne($building_id, array(
                'title' => $title,
                'title_vn' => $title_vn,
                'property_code' => $property_code,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'intro' => Input::post('intro'),
                'is_locked' => $is_locked,
                'is_trash' => $is_trash,
                'upd_date' => time(),
                'user_id_update' => $core->_USER['user_id'],
                'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
            ))){
                $msg = '_success';
                if(!empty($properties)){ $ii = 1;
                    $ids = array();
                    foreach($properties as $key => $val){
                        $ids[] = $val['id'];
                        if(isset($val['id']) && $val['id'] > 0){
                            $clsProjectMeta->updateOne($val['id'], array(
                                'title' => $val['title'],
                                'content' => $val['content'],
                                'is_hot' => (int) $val['is_hot'],
                                'reg_date' => time(),
                                'order_no' => $ii
                            ));
                        } else {
                            $id = $clsProjectMeta->getMaxId();
                            $ids[] = $id;
                            // $clsProjectMeta->setDebug(true);
                            $clsProjectMeta->insert(array(
                                $clsProjectMeta->pkey => $id,
                                'for_id' => $building_id,
                                'type' => 'building',
                                'title' => $val['title'],
                                'content' => $val['content'],
                                'is_hot' => (int) $val['is_hot'],
                                'reg_date' => time(),
                                'order_no' => $ii
                            ));
                        }
                        ++$ii;
                    }
                }
            }
        } else {
            $more_information = array(
                'floor' => $floor,
                'is_templated' => 0,
                'number_house' => $number_house,
                'number_floor' => $number_floor,
                'stock_template' => $stock_template,
                'stock_support_id' => $stock_support_id);
            $more_information['attrs'] = $attrs;
            $more_information['title_ts'] = $title_ts;
            $more_information['template'] = $template;
            $more_information['layout_ns'] = $layout_ns;
            $more_information['number_of_elevator'] = $number_of_elevator;
            $more_information['number_of_besement'] = $number_of_besement;
            $more_information['construction_style'] = $construction_style;
            $more_information['on_sale'] = $on_sale;
            $more_information['price_range'] = $price_range;
            $more_information['hide_row_floor_special'] = $hide_row_floor_special;
            if($clsProperty->countItem("{$cnd} and slug='".$core->replaceSpace($title)."'") > 0){
                $msg = '_duplicate';
            } else {
                $title_arrs = @explode(',', $title);
                foreach($title_arrs as $title){
                    $building_id = $clsProperty->getMaxId();
                    if($clsProperty->insert(array(
                        $clsProperty->pkey => $building_id,
                        'property_type' => '_BUILDING',
                        'property_code' => $property_code,
                        'for_id' => $block_id,
                        'title' => $title,
                        'title_vn' => $title_vn,
                        'slug' => $core->replaceSpace($title),
                        'slug_vn' => $core->replaceSpace($title_vn),
                        'order_no' => $clsProperty->getMaxOrderNo(),
                        'is_locked' => $is_locked,
                        'is_trash' => $is_trash,
                        'reg_date' => time(),
                        'upd_date' => time(),
                        'user_id' => $core->_USER['user_id'],
                        'user_id_update' => $core->_USER['user_id'],
                        'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
                    ))){
                        $msg = '_success';
                        if(!empty($properties)){ $ii = 1;
                            foreach($properties as $key => $val){
                                $clsProjectMeta->insert(array(
                                    $clsProjectMeta->pkey => $clsProjectMeta->getMaxId(),
                                    'for_id' => $building_id,
                                    'type' => 'building',
                                    'title' => $val['title'],
                                    'content' => $val['content'],
                                    'is_hot' => (int) $val['is_hot'],
                                    'reg_date' => time(),
                                    'order_no' => $ii
                                ));
                                ++$ii;
                            }
                        }
                    }
                }
            }
        }
    }
    // Return
    echo json_encode(array(
        'msg' => $msg,
        'building_id' => $building_id,
        'property_type' => '_BUILDING'
    )); die();
}
function default_pop_save_buildingV2(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsProjectMeta = new ProjectMeta();
    $block_id = (int) Input::post('block_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
    $number_house = (int) Input::post('number_house', 0);
    $number_floor = (int) Input::post('number_floor', 0);
    $stock_support_id = (int) Input::post('stock_support_id', 0);
    $is_locked = (int) Input::post('is_locked', 0);
    $is_trash = (int) Input::post('is_trash', 0);
    $floor = Input::post('floor', "");
    $floor_specical = Input::post('floor_specical', array());
    $layout_ns = Input::post('layout_ns', "");
    $layout_ms = Input::post('layout_ms');
    $sales_policy = Input::post('sales_policy');
    $property_code = Input::post('property_code', "");
    $stock_template = Input::post('stock_template', "");
    $number_of_elevator = Input::post('number_of_elevator', "");
    $number_of_besement = Input::post('number_of_besement', "");
    $construction_style = Input::post('construction_style', "");
    $handover_time = Input::post('handover_time', "");
    $on_sale = (int)Input::post('on_sale', 0);
    $price_range = Input::post('price_range', "");
    $hide_row_floor_special = (int) Input::post('hide_row_floor_special', 0);
    $is_symbol = (int) Input::post('is_symbol', 0);
    $is_symbol_floor = (int) Input::post('is_symbol_floor', 0);
    $stock_template = !empty($stock_template)
        ? preg_replace('/\s+/','',$stock_template) : "";
    $image = Input::post('image', "");
    ####
    $title = Input::post('title');
    $title_vn = Input::post('title_vn');
    if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
        $ns_block_id = (int) Input::post('ns_block_id', $block_id);
        if($building_id > 0){
            if($clsProperty->updateOne($building_id, array(
                'property_code' => $property_code,
                'for_id' => $ns_block_id,
                'title' => $title,
                'title_vn' => $title_vn,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'upd_date' => time(),
                'image' => $image,
                'user_id_update' => $core->_USER['user_id']
            ))){
                $msg = "_success";
                #activity log
                $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","update",["property_type" =>"RANGE","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
            }
        } else {
            $building_id = $clsProperty->getMaxId();
            if($clsProperty->insert(array(
                $clsProperty->pkey => $building_id,
                'property_type' => '_RANGE',
                'property_code' => $property_code,
                'for_id' => $ns_block_id,
                'title' => $title,
                'title_vn' => $title_vn,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'order_no' => $clsProperty->getMaxOrderNo(),
                'reg_date' => time(),
                'upd_date' => time(),
                'image' => $image,
                'user_id' => $core->_USER['user_id'],
                'user_id_update' => $core->_USER['user_id']
            ))){
                $msg = "_success";
                #activity log
                $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","insert",["property_type" =>"RANGE","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
            }
        }
    } else {

        $attrs = Input::post('attrs');
        $title_ts = Input::post('title_ts');
        $template = Input::post('template');
        $layout_map = Input::post('layout_map');
        $layout_map_FH = Input::post('layout_map_FH');
        $template_specical = Input::post('template_specical');
        $properties = Input::post('properties');
        $floor_hierarchy = Input::post('floor_hierarchy');
        $vr_link = Input::post('vr_link');
        $vr_source = Input::post('vr_source');
        $floor_range_configs = Input::post('floor_range_configs');
        $floor_level_configs = Input::post('floor_level_configs');
        $info_more = Input::post('info_more', array());
        // $clsISO->print_pre($floor_range_configs); die();
        if(!empty($attrs)){
            foreach($attrs as $key => $val){
                if(empty($val['title']) && empty($val['content'])){
                    unset($attrs[$key]);
                }
            }
        }
        #
        $msg = '_error';
        $cnd = "`property_type`='_BUILDING' and `for_id`='{$block_id}'";
        if($building_id > 0){
            $oneBuilding = $clsProperty->getOne($building_id);
            $more_information = $oneBuilding['more_information'];
            $more_information = $clsISO->to_array_json($more_information);
            $more_information['info_more'] = $info_more;
            $more_information['floor'] = $floor;
            $more_information['floor_specical'] = $floor_specical;
            $more_information['layout_ns'] = $layout_ns;
            $more_information['layout_ms'] = $layout_ms;
            $more_information['sales_policy'] = $sales_policy;
            $more_information['title_ts'] = $title_ts;
            $more_information['template'] = $template;
            $more_information['template_specical'] = $template_specical;
            $more_information['stock_template'] = $stock_template;
            $more_information['number_house'] = $number_house;
            $more_information['number_floor'] = $number_floor;
            $more_information['stock_support_id'] = $stock_support_id;
            $more_information['number_of_elevator'] = $number_of_elevator;
            $more_information['number_of_besement'] = $number_of_besement;
            $more_information['construction_style'] = $construction_style;
            $more_information['handover_time'] = $handover_time;
            $more_information['on_sale'] = $on_sale;
            $more_information['layout_map'] = $layout_map;
            $more_information['layout_map_FH'] = $layout_map_FH;
            $more_information['price_range'] = $price_range;
            $more_information['floor_hierarchy'] = $floor_hierarchy;
            $more_information['hide_row_floor_special'] = $hide_row_floor_special;
            $more_information['is_symbol'] = $is_symbol;
            $more_information['is_symbol_floor'] = $is_symbol_floor;
            $more_information['vr_link'] = $vr_link;
            $more_information['vr_source'] = $vr_source;
            $more_information['attrs'] = $attrs;
            $more_information['floor_range_configs'] = $floor_range_configs;
            $more_information['floor_level_configs'] = $floor_level_configs;
            $more_information['project_id'] = $project_id;
            $more_information['block_id'] = $block_id;
            if($clsProperty->updateOne($building_id, array(
                'title' => $title,
                'title_vn' => $title_vn,
                'property_code' => $property_code,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'intro' => Input::post('intro'),
                'is_locked' => $is_locked,
                'is_trash' => $is_trash,
                'upd_date' => time(),
                'image' => $image,
                'user_id_update' => $core->_USER['user_id'],
                'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
            ))){
                $msg = '_success';
                #activity log
                $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","update",["property_type" =>"BUILDING","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
            }
        } else {
            $more_information = array(
                'floor' => $floor,
                'floor_specical' => $floor_specical,
                'is_templated' => 0,
                'number_house' => $number_house,
                'number_floor' => $number_floor,
                'stock_template' => $stock_template,
                'stock_support_id' => $stock_support_id
            );
            $more_information['info_more'] = $info_more;
            $more_information['attrs'] = $attrs;
            $more_information['title_ts'] = $title_ts;
            $more_information['template'] = $template;
            $more_information['stock_template'] = $stock_template;
            $more_information['layout_ns'] = $layout_ns;
            $more_information['layout_ms'] = $layout_ms;
            $more_information['sales_policy'] = $sales_policy;
            $more_information['number_of_elevator'] = $number_of_elevator;
            $more_information['number_of_besement'] = $number_of_besement;
            $more_information['construction_style'] = $construction_style;
            $more_information['handover_time'] = $handover_time;
            $more_information['on_sale'] = $on_sale;
            $more_information['layout_map'] = $layout_map;
            $more_information['layout_map_FH'] = $layout_map_FH;
            $more_information['price_range'] = $price_range;
            $more_information['floor_hierarchy'] = $floor_hierarchy;
            $more_information['hide_row_floor_special'] = $hide_row_floor_special;
            $more_information['is_symbol'] = $is_symbol;
            $more_information['is_symbol_floor'] = $is_symbol_floor;
            $more_information['vr_link'] = $vr_link;
            $more_information['vr_source'] = $vr_source;
            $more_information['floor_range_configs'] = $floor_range_configs;
            $more_information['floor_level_configs'] = $floor_level_configs;
            $more_information['project_id'] = $project_id;
            $more_information['block_id'] = $block_id;
            if($clsProperty->countItem("{$cnd} and slug='".$core->replaceSpace($title)."'") > 0){
                $msg = '_duplicate';
            } else {
                $title_arrs = @explode(',', $title);
                foreach($title_arrs as $title){
                    $building_id = $clsProperty->getMaxId();
                    if($clsProperty->insert(array(
                        $clsProperty->pkey => $building_id,
                        'property_type' => '_BUILDING',
                        'property_code' => $property_code,
                        'for_id' => $block_id,
                        'title' => $title,
                        'title_vn' => $title_vn,
                        'slug' => $core->replaceSpace($title),
                        'slug_vn' => $core->replaceSpace($title_vn),
                        'order_no' => $clsProperty->getMaxOrderNo(),
                        'is_locked' => $is_locked,
                        'is_trash' => $is_trash,
                        'reg_date' => time(),
                        'upd_date' => time(),
                        'image' => $image,
                        'user_id' => $core->_USER['user_id'],
                        'user_id_update' => $core->_USER['user_id'],
                        'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
                    ))){
                        $msg = '_success';
                        #activity log
                        $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                        $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                        $clsActivityLog = new ActivityLog();
                        $log = $clsActivityLog->addActivityLog("Property","insert",["property_type" =>"BUILDING","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
                    }
                }
            }
        }
    }
    // Return
    echo json_encode(array(
        'msg' => $msg,
        'building_id' => $building_id,
        'property_type' => '_BUILDING'
    )); die();
}
function default_pop_save_buildingV3(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsProjectMeta = new ProjectMeta();
    $data = Input::post("data","");
    $ARRAY_POST = $clsISO->to_array_json($data);
    $block_id = (int) $core->get_field($ARRAY_POST, "block_id", 0);
    $project_id = (int) $core->get_field($ARRAY_POST, "project_id", 0);
    $building_id = (int) $core->get_field($ARRAY_POST, "building_id", 0);
    $stock_type = (int) $core->get_field($ARRAY_POST, "stock_type", _BLOCK_TYPE_HIGHLEVEL_SALE);
    $number_house = (int) $core->get_field($ARRAY_POST, "number_house", 0);
    $number_floor = (int) $core->get_field($ARRAY_POST, "number_floor", 0);
    $stock_support_id = (int) $core->get_field($ARRAY_POST, "stock_support_id", 0);
    $is_locked = (int) $core->get_field($ARRAY_POST, "is_locked", 0);
    $is_trash = (int) $core->get_field($ARRAY_POST, "is_trash", 0);
    $floor = $core->get_field($ARRAY_POST, "floor", "");
    $floor_specical = $core->get_field($ARRAY_POST, "floor_specical", []);
    $layout_ns = $core->get_field($ARRAY_POST, "layout_ns", "");
    $layout_ms = $core->get_field($ARRAY_POST, "layout_ms", "");
    $sales_policy = $core->get_field($ARRAY_POST, "sales_policy", "");
    $property_code = $core->get_field($ARRAY_POST, "property_code", '');
    $stock_template = $core->get_field($ARRAY_POST, "stock_template", '');
    $number_of_elevator = $core->get_field($ARRAY_POST, "number_of_elevator", '');
    $number_of_besement = $core->get_field($ARRAY_POST, "number_of_besement", '');
    $construction_style = $core->get_field($ARRAY_POST, "construction_style", '');
    $handover_time = $core->get_field($ARRAY_POST, "handover_time", '');
    $on_sale = (int)$core->get_field($ARRAY_POST, "on_sale", 0);
    $price_range = $core->get_field($ARRAY_POST, "price_range", '');
    $hide_row_floor_special = (int) $core->get_field($ARRAY_POST, "hide_row_floor_special", 0);
    $is_symbol = (int) $core->get_field($ARRAY_POST, "is_symbol", 0);
    $is_symbol_floor = (int) $core->get_field($ARRAY_POST, "is_symbol_floor", 0);
    $stock_template = !empty($stock_template)
        ? preg_replace('/\s+/','',$stock_template) : "";
    $image = $core->get_field($ARRAY_POST, "image", '');
    ####
    $title = $core->get_field($ARRAY_POST, "title", '');
    $title_vn = $core->get_field($ARRAY_POST, "title_vn", '');
    if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
        $ns_block_id = (int) $core->get_field($ARRAY_POST, "ns_block_id", $block_id);
        if($building_id > 0){
            if($clsProperty->updateOne($building_id, array(
                'property_code' => $property_code,
                'for_id' => $ns_block_id,
                'title' => $title,
                'title_vn' => $title_vn,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'upd_date' => time(),
                'image' => $image,
                'user_id_update' => $core->_USER['user_id']
            ))){
                $msg = "_success";
                #activity log
                $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","update",["property_type" =>"RANGE","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
            }
        } else {
            $building_id = $clsProperty->getMaxId();
            if($clsProperty->insert(array(
                $clsProperty->pkey => $building_id,
                'property_type' => '_RANGE',
                'property_code' => $property_code,
                'for_id' => $ns_block_id,
                'title' => $title,
                'title_vn' => $title_vn,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'order_no' => $clsProperty->getMaxOrderNo(),
                'reg_date' => time(),
                'upd_date' => time(),
                'image' => $image,
                'user_id' => $core->_USER['user_id'],
                'user_id_update' => $core->_USER['user_id']
            ))){
                $msg = "_success";
                #activity log
                $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","insert",["property_type" =>"RANGE","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
            }
        }
    } else {

        $attrs = $core->get_field($ARRAY_POST, "attrs", '');
        $title_ts = $core->get_field($ARRAY_POST, "title_ts", '');
        $template = $core->get_field($ARRAY_POST, "template", '');
        $layout_map = $core->get_field($ARRAY_POST, "layout_map", '');
        $layout_map_FH = $core->get_field($ARRAY_POST, "layout_map_FH", '');
        $template_specical = $core->get_field($ARRAY_POST, "template_specical", '');
        $properties = $core->get_field($ARRAY_POST, "properties", '');
        $floor_hierarchy = $core->get_field($ARRAY_POST, "floor_hierarchy", '');
        $vr_link = $core->get_field($ARRAY_POST, "vr_link", '');
        $vr_source = $core->get_field($ARRAY_POST, "vr_source", '');
        $floor_range_configs = $core->get_field($ARRAY_POST, "floor_range_configs", '');
        $floor_level_configs = $core->get_field($ARRAY_POST, "floor_level_configs", '');
        $intro = $core->get_field($ARRAY_POST, "intro", '');
        $info_more = $core->get_field($ARRAY_POST, "info_more", []);
        // $clsISO->print_pre($floor_range_configs); die();
        if(!empty($attrs)){
            foreach($attrs as $key => $val){
                if(empty($val['title']) && empty($val['content'])){
                    unset($attrs[$key]);
                }
            }
        }
        #
        $msg = '_error';
        $cnd = "`property_type`='_BUILDING' and `for_id`='{$block_id}'";
        $dbconn->debug=true;
        if($building_id > 0){
            $oneBuilding = $clsProperty->getOne($building_id);
            $more_information = $oneBuilding['more_information'];
            $more_information = $clsISO->to_array_json($more_information);
            $more_information['info_more'] = $info_more;
            $more_information['floor'] = $floor;
            $more_information['floor_specical'] = $floor_specical;
            $more_information['layout_ns'] = $layout_ns;
            $more_information['layout_ms'] = $layout_ms;
            $more_information['sales_policy'] = $sales_policy;
            $more_information['title_ts'] = $title_ts;
            $more_information['template'] = $template;
            $more_information['template_specical'] = $template_specical;
            $more_information['stock_template'] = $stock_template;
            $more_information['number_house'] = $number_house;
            $more_information['number_floor'] = $number_floor;
            $more_information['stock_support_id'] = $stock_support_id;
            $more_information['number_of_elevator'] = $number_of_elevator;
            $more_information['number_of_besement'] = $number_of_besement;
            $more_information['construction_style'] = $construction_style;
            $more_information['handover_time'] = $handover_time;
            $more_information['on_sale'] = $on_sale;
            $more_information['layout_map'] = $layout_map;
            $more_information['layout_map_FH'] = $layout_map_FH;
            $more_information['price_range'] = $price_range;
            $more_information['floor_hierarchy'] = $floor_hierarchy;
            $more_information['hide_row_floor_special'] = $hide_row_floor_special;
            $more_information['is_symbol'] = $is_symbol;
            $more_information['is_symbol_floor'] = $is_symbol_floor;
            $more_information['vr_link'] = $vr_link;
            $more_information['vr_source'] = $vr_source;
            $more_information['attrs'] = $attrs;
            $more_information['floor_range_configs'] = $floor_range_configs;
            $more_information['floor_level_configs'] = $floor_level_configs;
            $more_information['project_id'] = $project_id;
            $more_information['block_id'] = $block_id;
            if($clsProperty->updateOne($building_id, array(
                'title' => $title,
                'title_vn' => $title_vn,
                'property_code' => $property_code,
                'slug' => $core->replaceSpace($title),
                'slug_vn' => $core->replaceSpace($title_vn),
                'intro' => $intro,
                'is_locked' => $is_locked,
                'is_trash' => $is_trash,
                'upd_date' => time(),
                'image' => $image,
                'user_id_update' => $core->_USER['user_id'],
                'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
            ))){
                $msg = '_success';
                #activity log
                $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Property","update",["property_type" =>"BUILDING","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
            }
        } else {
            $more_information = array(
                'floor' => $floor,
                'floor_specical' => $floor_specical,
                'is_templated' => 0,
                'number_house' => $number_house,
                'number_floor' => $number_floor,
                'stock_template' => $stock_template,
                'stock_support_id' => $stock_support_id
            );
            $more_information['info_more'] = $info_more;
            $more_information['attrs'] = $attrs;
            $more_information['title_ts'] = $title_ts;
            $more_information['template'] = $template;
            $more_information['stock_template'] = $stock_template;
            $more_information['layout_ns'] = $layout_ns;
            $more_information['layout_ms'] = $layout_ms;
            $more_information['sales_policy'] = $sales_policy;
            $more_information['number_of_elevator'] = $number_of_elevator;
            $more_information['number_of_besement'] = $number_of_besement;
            $more_information['construction_style'] = $construction_style;
            $more_information['handover_time'] = $handover_time;
            $more_information['on_sale'] = $on_sale;
            $more_information['layout_map'] = $layout_map;
            $more_information['layout_map_FH'] = $layout_map_FH;
            $more_information['price_range'] = $price_range;
            $more_information['floor_hierarchy'] = $floor_hierarchy;
            $more_information['hide_row_floor_special'] = $hide_row_floor_special;
            $more_information['is_symbol'] = $is_symbol;
            $more_information['is_symbol_floor'] = $is_symbol_floor;
            $more_information['vr_link'] = $vr_link;
            $more_information['vr_source'] = $vr_source;
            $more_information['floor_range_configs'] = $floor_range_configs;
            $more_information['floor_level_configs'] = $floor_level_configs;
            $more_information['project_id'] = $project_id;
            $more_information['block_id'] = $block_id;
            if($clsProperty->countItem("{$cnd} and slug='".$core->replaceSpace($title)."'") > 0){
                $msg = '_duplicate';
            } else {
                $title_arrs = @explode(',', $title);
                foreach($title_arrs as $title){
                    $building_id = $clsProperty->getMaxId();
                    if($clsProperty->insert(array(
                        $clsProperty->pkey => $building_id,
                        'property_type' => '_BUILDING',
                        'property_code' => $property_code,
                        'for_id' => $block_id,
                        'title' => $title,
                        'title_vn' => $title_vn,
                        'slug' => $core->replaceSpace($title),
                        'slug_vn' => $core->replaceSpace($title_vn),
                        'order_no' => $clsProperty->getMaxOrderNo(),
                        'is_locked' => $is_locked,
                        'is_trash' => $is_trash,
                        'reg_date' => time(),
                        'upd_date' => time(),
                        'image' => $image,
                        'user_id' => $core->_USER['user_id'],
                        'user_id_update' => $core->_USER['user_id'],
                        'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
                    ))){
                        $msg = '_success';
                        #activity log
                        $oneBlock = $clsProperty->getOne($ns_block_id,"title,for_id");
                        $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
                        $clsActivityLog = new ActivityLog();
                        $log = $clsActivityLog->addActivityLog("Property","insert",["property_type" =>"BUILDING","title" =>$title,"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
                    }
                }
            }
        }
    }
    // Return
    echo json_encode(array(
        'msg' => $msg,
        'building_id' => $building_id,
        'property_type' => '_BUILDING'
    )); die();
}
function default_delete_building(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    ####
    $msg = '_error';
    if($building_id > 0 && $clsStock->countItem("project_id='{$project_id}' 
	and block_id='{$block_id}' and building_id='{$building_id}'")==0){
        $oneBuilding = $clsProperty->getOne($building_id,"title,for_id");
        if($clsProperty->deleteOne($building_id)){
            $msg = '_success';

            #activity log
            $oneBlock = $clsProperty->getOne($oneBuilding["for_id"],"title,for_id,parent_id");
            $property_type = ($oneBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE) ? "RANGE" : "BUILDING";
            $oneProject = $clsProject->getOne($oneBlock["for_id"],"title");
            $clsActivityLog = new ActivityLog();
            $log = $clsActivityLog->addActivityLog("Property","delete",["property_type" =>$property_type,"title" =>$oneBuilding["title"],"title_block" =>$oneBlock['title'],"title_project" =>$oneProject['title']]);
        }
    }
    // Return
    echo $msg; die();
}
function default_upd_order_billing(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProperty = new Property();
    ###
    $list_ids = Input::post('list_ids');
    if(!empty($list_ids)){ $ii = 1;
        foreach($list_ids as $id){
            $clsProperty->updateOne($id, array(
                'order_no' => $ii
            ));
            ++$ii;
        }
    }
    // Return
    echo (1); die();
}
function default_load_template_buildingV2(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProperty = new Property();
//	$toId = Input::post('toId');
    $toId = $clsISO->getUniqid();
//	$action = Input::post('action');
    $building_id = (int) Input::post('building_id', 0);
    $floor_specical_new = Input::post('floor_specical_new',"");
    $template = Input::post('template',array());
    $field = "{$clsProperty->pkey},title";
    $arrViews = $clsProperty->getAll("property_type='_VIEW' AND `is_locked`='0' order by order_no ASC", $field);
    $arrBedRooms = $clsProperty->getAll("property_type='_BEDROOM' order by order_no ASC", $field);
    $arrDirections = $clsProperty->getAll("property_type='_DIRECTION' order by order_no ASC", $field);
    $number_house = (int) Input::post('number_house', 0);
    $template_active = Input::post('template_active', "general");
    if($template_active == "general") {
        $template = Input::post('template',array());
    }else{
        $template_specical = Input::post('template_specical',array());
        $template = $template_specical[$template_active];
    }
    $html_input_specical = $html_specical = $html_tab_specical = '';
//	 $clsISO->print_pre($_POST); die();
//	$clsISO->print_pre($floor_specical_new);die;
    if(!empty($floor_specical_new)) {
        $arr_specical = explode(",",$floor_specical_new); sort($floor_specical_new);
        $txtfloor_specical = "";
        foreach($arr_specical as $floor) {
            if(trim($floor) != "") {
                $str_floor = trim($floor);
                $int_floor = (int)$str_floor;
                if((string)$int_floor == $str_floor) {
                    $str_floor = $clsISO->parseNumber($str_floor);
                }else{
                    $tmp = str_replace((string)$int_floor,"",$str_floor);
                    $tmp = str_replace($clsISO->parseNumber($int_floor),"",$str_floor);
                    $str_floor = $clsISO->parseNumber($str_floor).$tmp;
                }
                $txtfloor_specical .= (($txtfloor_specical != "") ? ",":"") . $str_floor;
            }
        }
        $html_input_specical = '<input type="hidden" autocomplete="off" class="form-control required mr-2 floor_specical '.$toId.'" placeholder="Số tầng" name="floor_specical['.$toId.']" value="'.$txtfloor_specical.'">';
        $html_tab_specical = '<li class="nav-item  nav-item_tab_floor" key="'.$toId.'">
								<a class="nav-link" data-toggle="tab" href="#tab_'.$toId.'">
								<span class="txt_nav">Tầng '.$txtfloor_specical.' </span> 
								<button class="btn btn-sm btn-default ml-2 border-0 px-1" type="button" title="Xóa tầng" onclick="$Core.project.deleteFloorSpecical(this,event)" toId="'.$toId.'"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
								<button class="btn btn-sm btn-default ml-1 border-0 px-1" type="button" title="Sửa tầng" onclick="$Core.project.gen_floor(this,event)" toId="'.$toId.'" data-type="_EDIT"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
								</a>											
							</li>';
        $html_specical .=
            '<div id="tab_'.$toId.'" class="tab-pane fade overflow-x-auto holder_template_building">
				<table class="table no-maxwidth mb-2" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">
					<thead>
						<tr>
							<th width="7%">Căn số</th>
							<th width="7%">Symbol</th>
							<th width="10%">Số PN</th>
							<th width="10%">Hướng BC</th>
							<th width="10%">DT_TT(m2)</th>
							<th width="10%">DT_Tim(m2)</th>
							<th width="10%">View</th>
							<th width="15%">Layout</th>
							<th width="15%">Layout chi tiết</th>
							<th width="15%">Video</th>
						</tr>
					</thead>
					<tbody class="holder_template_building_specical">';
        foreach($template as $k => $v){
            $html_specical .= '<tr class="tr_template_'.$building_id.'">
							<td class="text-left">
								<input type="text" name="template_specical['.$toId.']['.$k.'][code]" class="form-control" value="'.(!empty($v['code']) ? $v['code'] : $clsISO->parseNumber($k+1)).'" value="'.$v['code'].'"/>
							</td>
							<td class="text-left">
								<input type="text" name="template_specical['.$toId.']['.$k.'][symbol]" class="form-control" value="'.$v['symbol'].'"/>
							</td>
							<td class="text-left">
								<select class="form-control iso-select2" name="template_specical['.$toId.']['.$k.'][bedroom_id]">
									'.$clsProperty->getSelectOptimizeProperty('_BEDROOM',$v['bedroom_id'],$arrBedRooms).'
								</select>
							</td>
							<td class="text-left">
								<select class="form-control iso-select2" name="template_specical['.$toId.']['.$k.'][home_direction_id]">
									'.$clsProperty->getSelectOptimizeProperty('_DIRECTION',$v['home_direction_id'],$arrDirections).'
								</select>
							</td>
							<td class="text-left">
								<div class="input-group-suffix">
									<input type="text" name="template_specical['.$toId.']['.$k.'][DT_TT]" class="form-control w-100px" value="'.$v['DT_TT'].'" />
									<span class="suffix">m2</span>
								</div>
							</td>
							<td class="text-left">
								<div class="input-group-suffix">
									<input type="text" name="template_specical['.$toId.']['.$k.'][DT_Tim]" class="form-control w-100px" value="'.$v['DT_Tim'].'" />
									<span class="suffix">m2</span>
								</div>
							</td>
							<td class="text-left">
								<select class="form-control iso-select2" name="template_specical['.$toId.']['.$k.'][view_id]">
									'.$clsProperty->getSelectOptimizeProperty('_VIEW',$v['view_id'], $arrViews).'
								</select>
							</td>
							<td width="15%">
								<div class="input-group">
									<input type="text" id="layout_'.$toId.'_'.$k.'" name="template_specical['.$toId.']['.$k.'][layout]" class="form-control" 
									maxlength="255" placeholder="URL Layout"  value="'.$v['layout'].'"/>
									<div class="input-group-btn">
										<button type="button" onClick="$Core.project.select_image(this, event)" toId="'.$toId.'" 
										gId="layout_'.$toId.'_'.$k.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
									</div>
								</div>
							</td>
							<td width="15%">
								<div class="input-group">
									<input type="text" id="layout_ns_'.$toId.'_'.$k.'" name="template_specical['.$toId.']['.$k.'][layout_ns]" value="'.$v['layout_ns'].'" 
									class="form-control" placeholder="URL Layout" maxlength="255" />
									<div class="input-group-btn">
										<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$toId.'_'.$k.'" toId="'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
									</div>
								</div>
							</td>
							<td width="15%">
								<input type="text" name="template_specical['.$toId.']['.$k.'][video]" class="form-control" maxlength="255" placeholder="URL Youtube" value="'.$v['video'].'"/>
							</td>
						</tr>';
        }
        $html_specical .=
            '</tbody>
			</table>
		</div>';
    }
//	echo $html_specical;die;
    // Return
    echo json_encode(array(
        "uid"								=>	$toId,
        "html_input_specical"				=>	$html_input_specical,
        "html_content_specical"				=> 	$html_specical,
        "html_tab_specical"					=> 	$html_tab_specical,
        "txtfloor_specical"					=> 	$txtfloor_specical,
    ));die;
}
function default_load_template_building(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProperty = new Property();
    $toId = Input::post('toId');
    $action = Input::post('action');
    $building_id = (int) Input::post('building_id', 0);
    $field = "{$clsProperty->pkey},title";
//	$clsISO->print_pre($_POST);die;
    $arrViews = $clsProperty->getAll("property_type='_VIEW' AND `is_locked`='0' order by order_no ASC", $field);
    $arrBedRooms = $clsProperty->getAll("property_type='_BEDROOM' order by order_no ASC", $field);
    $arrDirections = $clsProperty->getAll("property_type='_DIRECTION' order by order_no ASC", $field);
    if($action=='copypaste'){ $i = 0;
        $data = Input::post('data');
        foreach($data as $val){
            $direction = $val[1];
            $bedroom = $val[2];
            $DT_TT = $val[3];
            $DT_Tim = $val[4];
            $view = $val[5];
            $layout = $val[6];
            $video = $val[7];
            ###
            $home_direction_id = 0;
            if(!empty($direction)){
                $tmp = $clsProperty->getAll("property_type='_DIRECTION' and (
					slug='".$core->replaceSpace($direction)."' 
					or slug_vn='".$core->replaceSpace($direction)."'
				)");
                $home_direction_id = !empty($tmp) ? $tmp[0][$clsProperty->pkey] : 0;
            }
            ###
            $bedroom_id = 0;
            if(!empty($bedroom)){
                $bedroom = preg_replace('/\s+/', '', $bedroom);
                $tmp = $clsProperty->getAll("property_type='_BEDROOM' 
				and slug='".$core->replaceSpace($bedroom)."'");
                $bedroom_id = !empty($tmp) ? $tmp[0][$clsProperty->pkey] : 0;
                unset($tmp);
            }
            ###
            $view_id = 0;
            if(!empty($view)){
                $tmp = $clsProperty->getAll("property_type='_VIEW' 
				and slug='".$core->replaceSpace($view)."'");
                $view_id = !empty($tmp) ? $tmp[0][$clsProperty->pkey] : 0;
                unset($tmp);
            }
            $html .= '<tr class="tr_template_'.$building_id.'">
				<td class="text-left">
					<input type="text" name="template['.$i.'][code]" class="form-control" value="'.$val[0].'" />
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][bedroom_id]">
						'.$clsProperty->getSelectOptimizeProperty('_BEDROOM', $bedroom_id, $arrBedRooms).'
					</select>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][home_direction_id]">
						'.$clsProperty->getSelectOptimizeProperty('_DIRECTION', $home_direction_id,$arrDirections).'
					</select>
				</td>
				<td class="text-left">
					<div class="input-group-suffix">
						<input type="text" name="template['.$i.'][DT_TT]" class="form-control" value="'.$DT_TT.'" />
						<span class="suffix">m2</span>
					</div>
				</td>
				<td class="text-left">
					<div class="input-group-suffix">
						<input type="text" name="template['.$i.'][DT_Tim]" class="form-control" value="'.$DT_Tim.'" />
						<span class="suffix">m2</span>
					</div>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][view_id]">
						'.$clsProperty->getSelectOptimizeProperty('_VIEW',$view_id, $arrViews).'
					</select>
				</td>
				<td width="20%">
					<div class="input-group">
						<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout]" value="'.$layout.'" 
						class="form-control" placeholder="URL Layout" maxlength="255" />
						<div class="input-group-btn">
							<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
						</div>
					</div>
				</td>
				<td width="20%">
					<div class="input-group">
						<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout_ns]" value="'.$layout_ns.'" 
						class="form-control" placeholder="URL Layout" maxlength="255" />
						<div class="input-group-btn">
							<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
						</div>
					</div>
				</td>
				<td width="20%">
					<input type="text" name="template['.$i.'][video]" class="form-control" maxlength="255" 
					value="'.$video.'" placeholder="URL Youtube" />
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)">
						<i class="fa fa-trash"></i></button>
				</td>
			</tr>';
            ++$i;
        }
        echo $html; die();
    } else if($action=='add_quick') {
        $html = $html_specical = '';
        $number_house = (int) Input::post('number_house', 0);
        $floor_specical = Input::post('floor_specical', array());
        $arr_html = [];
        if(!empty($floor_specical)) {
            foreach($floor_specical as $key => $val){
                $html_specical = '';
                for($i=0; $i<$number_house; $i++){
                    $html_specical .= '<tr class="tr_template_'.$building_id.'">
							<td class="text-left">
								<input type="text" name="template_specical['.$key.']['.$i.'][code]" class="form-control" value="'.$clsISO->parseNumber($i+1).'" />
							</td>
							<td class="text-left">
								<input type="text" name="template_specical['.$toId.']['.$k.'][symbol]" class="form-control" value="'.$val['symbol'].'"/>
							</td>
							<td class="text-left">
								<select class="form-control" name="template_specical['.$key.']['.$i.'][bedroom_id]">
									'.$clsProperty->getSelectOptimizeProperty('_BEDROOM',0,$arrBedRooms).'
								</select>
							</td>
							<td class="text-left">
								<select class="form-control" name="template_specical['.$key.']['.$i.'][home_direction_id]">
									'.$clsProperty->getSelectOptimizeProperty('_DIRECTION',0,$arrDirections).'
								</select>
							</td>
							<td class="text-left">
								<div class="input-group-suffix">
									<input type="text" name="template_specical['.$key.']['.$i.'][DT_TT]" class="form-control" />
									<span class="suffix">m2</span>
								</div>
							</td>
							<td class="text-left">
								<div class="input-group-suffix">
									<input type="text" name="template_specical['.$key.']['.$i.'][DT_Tim]" class="form-control" />
									<span class="suffix">m2</span>
								</div>
							</td>
							<td class="text-left">
								<select class="form-control" name="template_specical['.$key.']['.$i.'][view_id]">
									'.$clsProperty->getSelectOptimizeProperty('_VIEW',0, $arrViews).'
								</select>
							</td>
							<td width="20%">
								<div class="input-group">
									<input type="text" id="layout_'.$key.'_'.$i.'" name="template_specical['.$key.']['.$i.'][layout]" class="form-control" 
									maxlength="255" placeholder="URL Layout" />
									<div class="input-group-btn">
										<button type="button" onClick="$Core.project.select_image(this, event)" toId="'.$key.'" 
										gId="layout_'.$key.'_'.$i.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
									</div>
								</div>
							</td>
							<td width="20%">
								<div class="input-group">
									<input type="text" id="layout_ns_'.$key.'_'.$i.'" name="template_specical['.$key.']['.$i.'][layout_ns]" value="'.$layout_ns.'" 
									class="form-control" placeholder="URL Layout" maxlength="255" />
									<div class="input-group-btn">
										<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$key.'_'.$i.'" toId="'.$key.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
									</div>
								</div>
							</td>
							<td width="20%">
								<input type="text" name="template_specical['.$key.']['.$i.'][video]" class="form-control" maxlength="255" placeholder="URL Youtube" />
							</td>
							<td class="text-center">
								<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)"><i class="fa fa-trash"></i></button>
							</td>
						</tr>';
                }
                $arr_html[$key] = $html_specical;
            }
        }
        for($i=0; $i<$number_house; $i++){
            $html .= '<tr class="tr_template_'.$building_id.'">
				<td class="text-left">
					<input type="text" name="template['.$i.'][code]" class="form-control" value="'.$clsISO->parseNumber($i+1).'" />
				</td>
				<td class="text-left">
					<input type="text" name="template['.$i.'][symbol]" class="form-control" value=""/>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][bedroom_id]">
						'.$clsProperty->getSelectOptimizeProperty('_BEDROOM',0,$arrBedRooms).'
					</select>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][home_direction_id]">
						'.$clsProperty->getSelectOptimizeProperty('_DIRECTION',0,$arrDirections).'
					</select>
				</td>
				<td class="text-left">
					<div class="input-group-suffix">
						<input type="text" name="template['.$i.'][DT_TT]" class="form-control" />
						<span class="suffix">m2</span>
					</div>
				</td>
				<td class="text-left">
					<div class="input-group-suffix">
						<input type="text" name="template['.$i.'][DT_Tim]" class="form-control" />
						<span class="suffix">m2</span>
					</div>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][view_id]">
						'.$clsProperty->getSelectOptimizeProperty('_VIEW',0, $arrViews).'
					</select>
				</td>
				<td width="20%">
					<div class="input-group">
						<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout]" class="form-control" 
						maxlength="255" placeholder="URL Layout" />
						<div class="input-group-btn">
							<button type="button" onClick="$Core.project.select_image(this, event)" toId="'.$toId.'" 
							gId="layout_'.$i.'_'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
						</div>
					</div>
				</td>
				<td width="20%">
					<div class="input-group">
						<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout_ns]" value="'.$layout_ns.'" 
						class="form-control" placeholder="URL Layout" maxlength="255" />
						<div class="input-group-btn">
							<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
						</div>
					</div>
				</td>
				<td width="20%">
					<input type="text" name="template['.$i.'][video]" class="form-control" maxlength="255" placeholder="URL Youtube" />
				</td>
				<td type="button" class="text-center">
					<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)">
						<i class="fa fa-trash"></i></button>
				</td>
			</tr>';
        }
        $arr_html["general"] = $html;
    }
//	echo $html;die;
    // Return
    echo json_encode($arr_html); die();
}
function default_addItemFloorSpecical(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProperty = new Property();
    $uid = $clsISO->getUniqid();
    $html = '<div class="item d-flex gap-2 mb-2 position-relative">
				<div class="w-100 position-relative">
					<label class="col-form-label position-absolute btn_create_floor"><a href="javascript:void(0);" onclick="$Core.project.gen_floor(this, event)" data-toggle="tooltip" title="" toid="'.$uid.'" data-original-title="Tạo nhanh danh sách tầng"><i class="fa fa-plus-circle"></i></a></label>
					<input autocomplete="off" class="form-control required mr-2 floor_specical '.$uid.'" placeholder="Số tầng" name="floor_specical['.$uid.']" value="">
				</div>
				<button class="btn btn-icon ml-2" type="button" onClick="$Core.project.deleteItemFloorSpecical(this,event)"><i class="fa fa-minus" aria-hidden="true"></i></button>
			</div>';
    // Return
    echo $html; die();
}
function default_open_copyfrom_building(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProperty = new Property();
    $uid = $clsISO->getUniqid();
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $html_options = "";
    $field = "{$clsProperty->pkey},title";
    $list_blocks = $clsProperty->getAll("property_type='_BLOCK'", $field);
    if(!empty($list_blocks)){
        foreach($list_blocks as $key => $val){
            $block_id = $val[$clsProperty->pkey];
            $list_buildings = $clsProperty->getAll("property_type='_BUILDING' and for_id='{$block_id}'", $field);
            if(!empty($list_buildings)){
                $html_options.= '<optgroup label="'.$val['title'].'">';
                foreach($list_buildings as $okey => $oval){
                    $html_options.= '<option value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
                }
                $html_options .= '</optgroup>';
            }
        }
    }
    $html = '<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
				<h3 class="modal-title"><strong>Copy thuộc tính từ tòa</strong></h3>
			</div>
			<form method="post" action="" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<label class="col-form-label">Chọn tòa cần copy<span class="text-red">*</span></label>
						<select name="from_building_id" class="form-control required '.$uid.'">
							<option value="0">Lựa chọn toà</option>
							'.$html_options.'
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success pull-right" uid="'.$uid.'" 
					onClick="$Core.project.start_copyfrom_building(this, event)" project_id="'.$project_id.'" building_id="'.$building_id.'">
						<span>Cập nhật</span>
					</button>
					<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
						<span>'.$core->get_Lang('Close').'</span>
					</button>
				</div>
			</form>
		</div>
	</div>';
    // Return
    echo $html; die();
}
function default_open_copypaste_excel(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $uid = $clsISO->getUniqid();
    $toId = Input::post('toId');
    $building_id = (int) Input::post('building_id', 0);
    $html = '<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
				<h3 class="modal-title"><strong>Copy/Paste Excel</strong></h3>
			</div>
			<form method="post" action="" enctype="multipart/form-data">
				<div class="modal-body">
					<fieldset>
						<legend>Mẫu</legend>
						<table class="table table-bordered" width="100%">
							<thead><tr>
								<th width="10%">Căn số</th>
								<th width="15%">Số PN</th>
								<th width="15%">Hướng BC</th>
								<th width="15%">DT_TT(m2)</th>
								<th width="15%">DT_Tim(m2)</th>
								<th width="15%">View(Nếu có)</th>
							</tr></thead>
							<tr>				
								<td>01</td>
								<td>ĐB</td>
								<td>Studio</td>
								<td>35,18</td>
								<td>31,74</td>
								<td>Nội khu</td>
							</tr>
							<tr>				
								<td>02</td>
								<td>ĐB</td>
								<td>2PN</td>
								<td>66,88</td>
								<td>61,27</td>
								<td>Nội khu</td>
							</tr>			
						</table>
					</fieldset>
					<div class="form-group">
						<label class="col-form-label">Copy nội dung File Excel<span class="text-red">*</span></label>
						<textarea rows="10" cols="255" class="form-control '.$uid.'"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success pull-right" uid="'.$uid.'" 
					onClick="start_copypaste_excel(this, event)" toId="'.$toId.'" building_id="'.$building_id.'">Cập nhật</button>
					<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
						'.$core->get_Lang('Close').'
					</button>
				</div>
			</form>
		</div>
	</div>';
    // Return
    echo $html; die();
}
function default_start_copyfrom_building(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
    $clsProject = new Project();
    $clsProperty = new Property();
    ###
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $from_building_id = (int) Input::post('from_building_id', 0);
    $more_information = $clsProperty->getOneField('more_information', $from_building_id);
    $more_information = !empty($more_information)
        ? json_decode(html_entity_decode($more_information), true) : array();
    $template = isset($more_information['template'])
        ? $more_information['template'] : array();
    $template_specical = isset($more_information['template_specical'])
        ? $more_information['template_specical'] : array();
    $floor_specical = isset($more_information['floor_specical'])
        ? $more_information['floor_specical'] : array();
    $html = "";
    if(!empty($template)){$i = 0;
        $html = '<div id="tab_general" class="tab-pane fade overflow-x-auto holder_template_building active in">
				<table class="table no-maxwidth" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">
					<thead>
						<tr>
							<th width="7%">Căn số</th>
							<th width="7%">Symbol</th>
							<th width="10%">Số PN</th>
							<th width="10%">Hướng BC</th>
							<th width="10%">DT_TT(m2)</th>
							<th width="10%">DT_Tim(m2)</th>
							<th width="10%">View</th>
							<th width="15%">Layout</th>
							<th width="15%">Layout chi tiết</th>
							<th width="15%">Video</th>
						</tr>
					</thead>
					<tbody class="holder_template_general_building">';
        foreach($template as $key => $val){
            $html.= '<tr class="tr_template_'.$building_id.'">
				<td class="text-left">
					<input type="text" name="template['.$i.'][code]" class="form-control" value="'.$val['code'].'" />
				</td>
				<td class="text-left">
					<input type="text" name="template['.$i.'][symbol]" class="form-control" value="'.$val['symbol'].'">
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][bedroom_id]">
						'.$clsProperty->getSelectOptimizeProperty('_BEDROOM', $val['bedroom_id'], $arrBedRooms).'
					</select>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][home_direction_id]">
						'.$clsProperty->getSelectOptimizeProperty('_DIRECTION', $val['home_direction_id'],$arrDirections).'
					</select>
				</td>
				<td class="text-left">
					<div class="input-group-suffix">
						<input type="text" name="template['.$i.'][DT_TT]" class="form-control" value="'.$val['DT_TT'].'" />
						<span class="suffix">m2</span>
					</div>
				</td>
				<td class="text-left">
					<div class="input-group-suffix">
						<input type="text" name="template['.$i.'][DT_Tim]" class="form-control" value="'.$val['DT_Tim'].'" />
						<span class="suffix">m2</span>
					</div>
				</td>
				<td class="text-left">
					<select class="form-control" name="template['.$i.'][view_id]">
						'.$clsProperty->getSelectOptimizeProperty('_VIEW',$val['view_id'], $arrViews).'
					</select>
				</td>
				<td width="15%">
					<div class="input-group">
						<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout]" value="'.$val['layout'].'" 
						class="form-control" placeholder="URL Layout" maxlength="255" />
						<div class="input-group-btn">
							<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">
								'.$core->makeIcon('upload','&nbsp;').'
							</button>
						</div>
					</div>
				</td>
				<td width="15%">
					<div class="input-group">
						<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout_ns]" value="'.$val['layout_ns'].'" 
						class="form-control" placeholder="URL Layout" maxlength="255" />
						<div class="input-group-btn">
							<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">
								'.$core->makeIcon('upload','&nbsp;').'
							</button>
						</div>
					</div>
				</td>
				<td width="15%">
					<input type="text" name="template['.$i.'][video]" class="form-control" maxlength="255" 
					value="'.$val['video'].'" placeholder="URL Youtube" />
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)">
					<i class="fa fa-trash"></i></button>
				</td>
			</tr>';
            ++$i;
        }
        $html.='</tbody>
				</table>
			</div>';
    }
//	==================
    $html_input_specical = $html_specical = "";
    $html_tab = '<li class="nav-item active nav-item_tab_floor" key="general"><a class="nav-link" data-toggle="tab" href="#tab_general">Tầng chung</a></li>';
    if(!empty($floor_specical)) {
        foreach ($floor_specical as $key => $val) {
            $html_input_specical .= '<input type="hidden" autocomplete="off" class="form-control required mr-2 floor_specical '.$key.'" placeholder="Số tầng" name="floor_specical['.$key.']" value="'.$val.'">';
            $html_tab .= '<li class="nav-item  nav-item_tab_floor" key="'.$key.'">
										<a class="nav-link" data-toggle="tab" href="#tab_'.$key.'">Tầng '.$val.' <button class="btn btn-sm btn-default ml-2 border-0" type="button" title="Xóa tầng" onclick="$Core.project.deleteFloorSpecical(this,event)" toId="'.$key.'"><i class="fa fa-minus-circle" aria-hidden="true"></i></button></a>	
									</li>';
        }
    }
    if(!empty($template_specical)) {
        foreach ($template_specical as $key => $template) {
//			$clsISO->print_pre($template);die;
            $html_specical .=
                '<div id="tab_'.$key.'" class="tab-pane fade overflow-x-auto holder_template_building">
					<table class="table no-maxwidth mb-2" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">
						<thead>
							<tr>
								<th width="7%">Căn số</th>
								<th width="7%">Symbol</th>
								<th width="10%">Số PN</th>
								<th width="10%">Hướng BC</th>
								<th width="10%">DT_TT(m2)</th>
								<th width="10%">DT_Tim(m2)</th>
								<th width="10%">View</th>
								<th width="15%">Layout</th>
								<th width="15%">Layout chi tiết</th>
								<th width="15%">Video</th>
							</tr>
						</thead>
						<tbody class="holder_template_building_specical">';
            foreach($template as $k => $v){
                $html_specical .= '<tr class="tr_template_'.$building_id.'">
								<td class="text-left">
									<input type="text" name="template_specical['.$key.']['.$k.'][code]" class="form-control" value="'.$clsISO->parseNumber($k+1).'" value="'.$v['code'].'"/>
								</td>								
								<td class="text-left">
									<input type="text" name="template_specical['.$key.']['.$k.'][symbol]" class="form-control" value="'.$v['symbol'].'">
								</td>
								<td class="text-left">
									<select class="form-control" name="template_specical['.$key.']['.$k.'][bedroom_id]">
										'.$clsProperty->getSelectOptimizeProperty('_BEDROOM',$v['bedroom_id'],$arrBedRooms).'
									</select>
								</td>
								<td class="text-left">
									<select class="form-control" name="template_specical['.$key.']['.$k.'][home_direction_id]">
										'.$clsProperty->getSelectOptimizeProperty('_DIRECTION',$v['home_direction_id'],$arrDirections).'
									</select>
								</td>
								<td class="text-left">
									<div class="input-group-suffix">
										<input type="text" name="template_specical['.$key.']['.$k.'][DT_TT]" class="form-control" value="'.$v['DT_TT'].'" />
										<span class="suffix">m2</span>
									</div>
								</td>
								<td class="text-left">

									<div class="input-group-suffix">
										<input type="text" name="template_specical['.$key.']['.$k.'][DT_Tim]" class="form-control" value="'.$v['DT_Tim'].'" />
										<span class="suffix">m2</span>
									</div>
								</td>
								<td class="text-left">
									<select class="form-control" name="template_specical['.$key.']['.$k.'][view_id]">
										'.$clsProperty->getSelectOptimizeProperty('_VIEW',$v['view_id'], $arrViews).'
									</select>
								</td>
								<td width="20%">
									<div class="input-group">
										<input type="text" id="layout_'.$key.'_'.$k.'" name="template_specical['.$key.']['.$k.'][layout]" class="form-control" 
										maxlength="255" placeholder="URL Layout"  value="'.$v['layout'].'"/>
										<div class="input-group-btn">
											<button type="button" onClick="$Core.project.select_image(this, event)" toId="'.$key.'" 
											gId="layout_'.$key.'_'.$k.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
										</div>
									</div>
								</td>
								<td width="20%">
									<div class="input-group">
										<input type="text" id="layout_ns_'.$key.'_'.$k.'" name="template_specical['.$key.']['.$k.'][layout_ns]" value="'.$v['layout_ns'].'" 
										class="form-control" placeholder="URL Layout" maxlength="255" />
										<div class="input-group-btn">
											<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$key.'_'.$k.'" toId="'.$key.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
										</div>
									</div>
								</td>
								<td width="20%">
									<input type="text" name="template_specical['.$key.']['.$k.'][video]" class="form-control" maxlength="255" placeholder="URL Youtube" value="'.$v['video'].'"/>
								</td>
							</tr>';
            }
            $html_specical .=
                '</tbody>
				</table>
			</div>';
        }
    }
//	==================
    // Return
    echo json_encode(array(
        "html_tab"				=>	$html_tab,
        "html_input_specical"	=>	$html_input_specical,
        "html_content_tab"		=>	$html.$html_specical,
    ));die;
}
function default_start_create_stock(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
    $clsStock = new Stock();
    $clsProperty = new Property();
    ###
    $holderG = Input::post('holderG', "create");
    $project_id = (int) Input::post('project_id',0);
    $block_id = (int) Input::post('block_id',0);
    $building_id = (Int) Input::post('building_id',0);
    if($project_id==0){
        echo 'project_error';
        die();
    } else if($block_id==0){
        echo 'block_error';
        die();
    } else if($building_id == 0){
        echo 'building_error';
        die();
    } else {
        $oneBuilding = $clsProperty->getOne($building_id);
        $more_information = $oneBuilding['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $floor_db = $core->get_field($more_information, "floor", "");
        $arr_floors = !empty($floor_db) ? explode(',', $floor_db) : array();
        // $clsISO->print_pre($holderG); die();
        if(!empty($arr_floors)){
            $template = $core->get_field($more_information, "template", []);
            $template_specical = $core->get_field($more_information, "template_specical", []);
            $stock_template = $core->get_field($more_information, "stock_template", "");
            $floor_specical = $core->get_field($more_information, "floor_specical", "");
            $arr_floor_specical = [];
            foreach ($floor_specical as $key => $val){
                $arr_fl = explode(",",$val);
                foreach($arr_fl as $fl) {
                    $arr_floor_specical[$fl] = $key;
                }
            }
            if(!empty($template_specical)){
                if(!empty($stock_template)){
                    foreach($floor_specical as $k_floor => $lst_floor){
                        $arrfloor = (!empty($lst_floor)) ? explode(",",$lst_floor) : array();
                        if(!empty($arrfloor)) {
                            for($i=0; $i < count($arrfloor); $i ++) {
                                $arr_stock_id_specical = [];
                                $floor = $arrfloor[$i];
                                foreach($template_specical[$k_floor] as $k_stock => $stock){
                                    $building_code = $oneBuilding['property_code'];
                                    if(!empty($stock['code'])) {
                                        if(!empty($more_information['is_symbol'])) {
                                            $building_code = $stock["symbol"];
                                        }
                                        $code = $stock['code'];
                                        if(!empty($more_information['is_symbol_floor']) && !empty($stock["symbol"])) {
                                            $ms_code = $stock["symbol"] . $stock['code'];
                                            $building_code_floor = $stock["symbol"];
                                        }else{
                                            $building_code_floor = "";
                                            $ms_code = str_replace('[MaToa]', $building_code, $stock_template);
                                            $ms_code = str_replace('[Tang]', $floor, $ms_code);
                                            $ms_code = str_replace('[CanHo]', $code, $ms_code);
                                        }
                                        ##
                                        $field = "{$clsStock->pkey},`more_information`";
                                        /*$oneStock = $clsStock->getByCond("`code`='".$code."' AND `floor`='{$floor}' and `building_id`='{$building_id}'
										and `status_id`<>'"._STOCK_STATUS_NON_ID."' limit 0,1", $field);*/
                                        $oneStock = $clsStock->getByCond("`ms_code`='".$ms_code."'  and `building_id`='{$building_id}' 
										and `status_id`<>'"._STOCK_STATUS_NON_ID."' limit 0,1", $field);
                                        /*$oneStock = $clsStock->getByCond("`ms_code`='{$ms_code}' and `building_id`='{$building_id}'
											and `status_id`<>'"._STOCK_STATUS_NON_ID."' limit 0,1", $field);*/
                                        if($holderG== 'create'){
                                            if(!empty($oneStock)){
                                                // Continue
                                            } else {
                                                $more_information_stock = array(
                                                    'status_id' => 0,
                                                    'DT_TT' => str_replace(",",".",$stock['DT_TT']),
                                                    'DT_Tim' => str_replace(",",".",$stock['DT_Tim']),
                                                    'type_id' => _STOCK_TYPE_NEW_ID,
                                                    'total_price' => 0,
                                                    'total_price_vat'=> 0,
                                                    'view_id' => $stock['view_id'],
                                                    'bedroom_id' => $stock['bedroom_id'],
                                                    'home_direction_id' => $stock['home_direction_id'],
                                                    'building_code' => $building_code,
                                                    'building_code_floor' => $building_code_floor,
                                                );
                                                if($clsStock->insert(array(
                                                    $clsStock->pkey => $clsStock->getMaxId(),
                                                    'stock_type' => _BLOCK_TYPE_HIGHLEVEL_SALE,
                                                    'project_id' => $project_id,
                                                    'block_id' => $block_id,
                                                    'building_id' => $building_id,
                                                    'floor' => $floor,
                                                    'code' => $code,
                                                    'ms_code' => $ms_code,
                                                    'view_id' => $stock['view_id'],
                                                    'type_id' => _STOCK_TYPE_NEW_ID,
                                                    'bedroom_id' => $stock['bedroom_id'],
                                                    'home_direction_id' => $stock['home_direction_id'],
                                                    'DT_TT' => str_replace(",",".",$stock['DT_TT']),
                                                    'status_id' => 0,
                                                    'reg_date' => time(),
                                                    'user_id' => $core->_USER['user_id'],
                                                    'more_information' => json_encode($more_information_stock, JSON_UNESCAPED_UNICODE)
                                                ))){
                                                    $insert_no++;
                                                }
                                            }
                                        } else if($holderG == 'update'){
                                            if(!empty($oneStock)){
                                                $more_information_stock = $oneStock['more_information'];
                                                $more_information_stock = $clsISO->to_array_json($more_information_stock);
                                                $more_information_stock['ms_code'] = $ms_code;
                                                $more_information_stock['DT_TT'] = str_replace(",",".",$stock['DT_TT']);
                                                $more_information_stock['DT_Tim'] = str_replace(",",".",$stock['DT_Tim']);
                                                $more_information_stock['view_id'] = $stock['view_id'];
                                                $more_information_stock['bedroom_id'] = $stock['bedroom_id'];
                                                $more_information_stock['home_direction_id'] = $stock['home_direction_id'];
                                                $more_information_stock['building_code'] = $building_code;
                                                $more_information_stock['building_code_floor'] = $building_code_floor;
                                                // $clsISO->print_pre($stock);
                                                if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
                                                    'stock_type' => _BLOCK_TYPE_HIGHLEVEL_SALE,
                                                    'floor' => $floor,
                                                    'code' => $code,
                                                    'view_id' => $stock['view_id'],
                                                    'bedroom_id' => $stock['bedroom_id'],
                                                    'home_direction_id' => $stock['home_direction_id'],
                                                    'DT_TT' => str_replace(",",".",$stock['DT_TT']),
                                                    'more_information' => json_encode($more_information_stock, JSON_UNESCAPED_UNICODE)
                                                ))){
                                                    $insert_no++;
                                                }
                                            }
                                        }
                                    }
                                } // End For
                                /*if(!empty($arr_stock_id_specical)){
									$clsStock->updateByCond("`{$clsStock->pkey}` NOT IN (".implode(',',$arr_stock_id_specical).") AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `floor`='".$clsISO->parseNumber($arrfloor[$i])."' AND `building_id`='{$building_id}'","`status_id`='"._STOCK_STATUS_NON_ID."'");
								}*/
                            }
                        }
                    } // End for
                }else{
                    $msg =  'stock_template_error';
                }
            }
            if(!empty($template)){
                if(!empty($stock_template)){
                    $insert_no = 0;
                    foreach($arr_floors as $floor){
                        if(!isset($arr_floor_specical[$floor])) {
                            foreach($template as $stock){
                                $building_code = $oneBuilding['property_code'];
                                $code = $stock['code'];
                                if(!empty($more_information['is_symbol'])) {
                                    $building_code = $stock["symbol"];
                                }
                                if(!empty($more_information['is_symbol_floor']) && !empty($stock["symbol"])) {
                                    $ms_code = $stock["symbol"] . $stock['code'];
                                    $building_code_floor = $stock["symbol"];
                                }else{
                                    $building_code_floor = "";
                                    $ms_code = str_replace('[MaToa]', $building_code, $stock_template);
                                    $ms_code = str_replace('[Tang]', $floor, $ms_code);
                                    $ms_code = str_replace('[CanHo]', $code, $ms_code);
                                }

                                ##
                                $field = "{$clsStock->pkey},`more_information`";
                                $oneStock = $clsStock->getByCond("`ms_code`='".$ms_code."'  and `building_id`='{$building_id}' 
									and `status_id`<>'"._STOCK_STATUS_NON_ID."' limit 0,1", $field);
                                if($holderG== 'create'){
                                    if(!empty($oneStock)){
                                        // Continue
                                    } else {
                                        $more_information_stock = array(
                                            'status_id' => 0,
                                            'DT_TT' => str_replace(",",".",$stock['DT_TT']),
                                            'DT_Tim' => str_replace(",",".",$stock['DT_Tim']),
                                            'type_id' => _STOCK_TYPE_NEW_ID,
                                            'total_price' => 0,
                                            'total_price_vat'=> 0,
                                            'view_id' => $stock['view_id'],
                                            'bedroom_id' => $stock['bedroom_id'],
                                            'home_direction_id' => $stock['home_direction_id'],
                                            'building_code' => $building_code,
                                            'building_code_floor' => $building_code_floor,
                                        );
										
                                        if($clsStock->insert(array(
                                            $clsStock->pkey => $clsStock->getMaxId(),
                                            'stock_type' => _BLOCK_TYPE_HIGHLEVEL_SALE,
                                            'project_id' => $project_id,
                                            'block_id' => $block_id,
                                            'building_id' => $building_id,
                                            'floor' => $floor,
                                            'code' => $code,
                                            'ms_code' => $ms_code,
                                            'view_id' => $stock['view_id'],
                                            'type_id' => _STOCK_TYPE_NEW_ID,
                                            'bedroom_id' => $stock['bedroom_id'],
                                            'home_direction_id' => $stock['home_direction_id'],
                                            'DT_TT' => str_replace(",",".",$stock['DT_TT']),
                                            'status_id' => 0,
                                            'reg_date' => time(),
                                            'user_id' => $core->_USER['user_id'],
                                            'more_information' => json_encode($more_information_stock, JSON_UNESCAPED_UNICODE)
                                        ))){
                                            $insert_no++;
                                        }
                                    }
                                } else if($holderG == 'update'){
                                    if(!empty($oneStock)){
                                        $more_information_stock = $oneStock['more_information'];
                                        $more_information_stock = $clsISO->to_array_json($more_information_stock);
                                        $more_information_stock['ms_code'] = $ms_code;
                                        $more_information_stock['DT_TT'] = str_replace(",",".",$stock['DT_TT']);
                                        $more_information_stock['DT_Tim'] = str_replace(",",".",$stock['DT_Tim']);
                                        $more_information_stock['view_id'] = $stock['view_id'];
                                        $more_information_stock['bedroom_id'] = $stock['bedroom_id'];
                                        $more_information_stock['home_direction_id'] = $stock['home_direction_id'];
                                        $more_information_stock['building_code'] = $building_code;
                                        $more_information_stock['building_code_floor'] = $building_code_floor;
                                        // $clsISO->print_pre($stock);
                                        if($clsStock->updateOne($oneStock[$clsStock->pkey], array(
                                            'stock_type' => _BLOCK_TYPE_HIGHLEVEL_SALE,
                                            'floor' => $floor,
                                            'code' => $code,
                                            'view_id' => $stock['view_id'],
                                            'bedroom_id' => $stock['bedroom_id'],
                                            'home_direction_id' => $stock['home_direction_id'],
                                            'DT_TT' => str_replace(",",".",$stock['DT_TT']),
                                            'more_information' => json_encode($more_information_stock, JSON_UNESCAPED_UNICODE)
                                        ))){
                                            $insert_no++;
                                        }
                                    }
                                }
                            } // End For
                        }

                    } // End for
                    // Return
                    $msg = '_success|'.$insert_no;
                } else {
                    $msg =  'stock_template_error';
                }
            } else {
                $msg =  'template_error';
            }
            ##
        } else {
            echo 'floor_error';
            die();
        }
    }
}
function default_stock_upload_file(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
    $image = "";
    if(!empty($_FILES['image']['name'])){
        if(is_uploaded_file($_FILES['image']['tmp_name'])){
            $clsUploadFile = new UploadFile();
            $image = $clsUploadFile->uploadItem($_FILES["image"],"/PTG",EXTENSION_FILE_UPLOAD);
            if(!empty($image) && file_exists(ROOTPATH . $image)){
                // Set the file metadata for drive
                $title = $_FILES["image"]["name"];
                $mimeType = $_FILES["image"]["type"];
                $clsGoogleDrive = new GoogleDrive();
                $createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_DOCS_ID);
                $image = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
                // $image = 'https://drive.google.com/uc?export=view&id='.$createdFile->getId();
                @unlink(ROOTPATH . $image);
            }
        }
    }
    // return
    echo $image; die();
}
function default_set_quick_menu(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsStock = new Stock();
    $clsProperty = new Property();
    $clsProject = new Project();
    ###
    $tp = Input::post('tp');
    $to_field = Input::post('to_field');
    $status = (int) Input::post('status', 0);
    $for_id = (int) Input::post('for_id', 0);
    ###
    $msg = "_error";
    if($tp=='_project'){
        if($to_field == "is_lock") {
            $more_information = $clsProject->getOneField('more_information', $for_id);
            $more_information = !empty($more_information)
                ? json_decode(html_entity_decode($more_information), true) : array();
            $more_information[$to_field] = $status;
            if($clsProject->updateOne($for_id, array(
                'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
            ))){
                $msg = "_success";
            }
        }else{
            if($clsProject->updateOne($for_id, array(
                "{$to_field}" => $status
            ))){
                $msg = "_success";
                $oneItem = $clsProject->getOne($for_id,"title");
                #activity log
                $clsActivityLog = new ActivityLog();
                $log = $clsActivityLog->addActivityLog("Project","update",['title' => $oneItem['title']]);
            }
        }

    } else {
        $more_information = $clsProperty->getOneField('more_information', $for_id);
        $more_information = !empty($more_information)
            ? json_decode(html_entity_decode($more_information), true) : array();
        $more_information[$to_field] = $status;
        if($clsProperty->updateOne($for_id, array(
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = "_success";
        }
    }
    // Return
    echo $msg; die();
}
function default_add_template_line(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsStock = new Stock();
    $clsProperty = new Property();
    ###
    $toId = Input::post('toId');
    $key = Input::post('key');
    $project_id = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $total_stocks = (int) Input::post('total_stocks', 0);
    ###
    $field = "{$clsProperty->pkey},title";
    $arrViews = $clsProperty->getAll("property_type='_VIEW' order by order_no ASC", $field);
    $arrBedRooms = $clsProperty->getAll("property_type='_BEDROOM' order by order_no ASC", $field);
    $arrDirections = $clsProperty->getAll("property_type='_DIRECTION' order by order_no ASC", $field);
    ###
    $i = $total_stocks;
    $html = "";
    if($key == "general") {
        $html = '<tr class="tr_template_'.$building_id.'">
			<td class="text-left">
				<input type="text" name="template['.$i.'][code]" class="form-control" value="'.$clsISO->parseNumber($total_stocks+1).'" />
			</td>
			<td class="text-left">
				<input type="text" name="template['.$i.'][symbol]" class="form-control" value="">
			</td>
			<td class="text-left">
				<select class="form-control" name="template['.$i.'][bedroom_id]">
					'.$clsProperty->getSelectOptimizeProperty('_BEDROOM',0,$arrBedRooms).'
				</select>
			</td>
			<td class="text-left">
				<select class="form-control" name="template['.$i.'][home_direction_id]">
					'.$clsProperty->getSelectOptimizeProperty('_DIRECTION',0,$arrDirections).'
				</select>
			</td>
			<td class="text-left">
				<div class="input-group-suffix">
					<input type="text" name="template['.$i.'][DT_TT]" class="form-control" />
					<span class="suffix">m2</span>
				</div>
			</td>
			<td class="text-left">
				<div class="input-group-suffix">
					<input type="text" name="template['.$i.'][DT_Tim]" class="form-control" />
					<span class="suffix">m2</span>
				</div>
			</td>
			<td class="text-left">
				<select class="form-control" name="template['.$i.'][view_id]">
					'.$clsProperty->getSelectOptimizeProperty('_VIEW',0, $arrViews).'
				</select>
			</td>
			<td width="15%">
				<div class="input-group">
					<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout]" class="form-control" 
					placeholder="URL Layout" maxlength="255" />
					<div class="input-group-btn">
						<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
					</div>
				</div>
			</td>
			<td width="15%">
				<div class="input-group">
					<input type="text" id="layout_'.$i.'_'.$toId.'" name="template['.$i.'][layout_ns]" class="form-control" 
					placeholder="URL Layout" maxlength="255" />
					<div class="input-group-btn">
						<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$i.'_'.$toId.'" toId="'.$toId.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
					</div>
				</div>
			</td>
			<td width="15%">
				<input type="text" name="template['.$i.'][video]" class="form-control" maxlength="255" placeholder="URL Youtube" />
			</td>
			<td class="text-center">
				<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)">
					<i class="fa fa-trash"></i></button>
			</td>
		</tr>';
    }else{
        $html = '<tr class="tr_template_'.$building_id.'">
			<td class="text-left">
				<input type="text" name="template_specical['.$key.']['.$i.'][code]" class="form-control" value="'.$clsISO->parseNumber($i+1).'" />
			</td>
			<td class="text-left">
				<input type="text" name="template_specical['.$key.']['.$i.'][symbol]" class="form-control" value="">
			</td>
			<td class="text-left">
				<select class="form-control" name="template_specical['.$key.']['.$i.'][bedroom_id]">
					'.$clsProperty->getSelectOptimizeProperty('_BEDROOM',0,$arrBedRooms).'
				</select>
			</td>
			<td class="text-left">
				<select class="form-control" name="template_specical['.$key.']['.$i.'][home_direction_id]">
					'.$clsProperty->getSelectOptimizeProperty('_DIRECTION',0,$arrDirections).'
				</select>
			</td>
			<td class="text-left">
				<div class="input-group-suffix">
					<input type="text" name="template_specical['.$key.']['.$i.'][DT_TT]" class="form-control" />
					<span class="suffix">m2</span>
				</div>
			</td>
			<td class="text-left">
				<div class="input-group-suffix">
					<input type="text" name="template_specical['.$key.']['.$i.'][DT_Tim]" class="form-control" />
					<span class="suffix">m2</span>
				</div>
			</td>
			<td class="text-left">
				<select class="form-control" name="template_specical['.$key.']['.$i.'][view_id]">
					'.$clsProperty->getSelectOptimizeProperty('_VIEW',0, $arrViews).'
				</select>
			</td>
			<td width="20%">
				<div class="input-group">
					<input type="text" id="layout_'.$key.'_'.$i.'" name="template_specical['.$key.']['.$i.'][layout]" class="form-control" 
					maxlength="255" placeholder="URL Layout" />
					<div class="input-group-btn">
						<button type="button" onClick="$Core.project.select_image(this, event)" toId="'.$key.'" 
						gId="layout_'.$key.'_'.$i.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
					</div>
				</div>
			</td>
			<td width="20%">
				<div class="input-group">
					<input type="text" id="layout_ns_'.$key.'_'.$i.'" name="template_specical['.$key.']['.$i.'][layout_ns]" value="'.$layout_ns.'" 
					class="form-control" placeholder="URL Layout" maxlength="255" />
					<div class="input-group-btn">
						<button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_'.$key.'_'.$i.'" toId="'.$key.'" class="btn btn-default">'.$core->makeIcon('upload','&nbsp;').'</button>
					</div>
				</div>
			</td>
			<td width="20%">
				<input type="text" name="template_specical['.$key.']['.$i.'][video]" class="form-control" maxlength="255" placeholder="URL Youtube" />
			</td>
			<td class="text-center">
				<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)">
					<i class="fa fa-trash"></i></button>
			</td>
		</tr>';
    }
    // Return
    echo $html; die();
}
/* ===== Ảnh căn hộ theo LOẠI CĂN (_BEDROOM) × TYPE (vd "Type 5") cho Tòa/Phân khu: Bóc mái + Nội thất =====
   Ưu tiên Tòa → fallback Phân khu (ApartmentMedia::getEffective). Tái dùng component media Tiến độ (.pm-*). */
function default_open_interior_ns(){
    global $smarty,$core,$clsISO;
    $clsApartmentMedia = new ApartmentMedia();
    $clsProperty = new Property();
    $project_id  = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $block_id    = (int) Input::post('block_id', 0);
    # Có building_id => quản lý cấp Tòa; ngược lại cấp Phân khu.
    $parent_type  = $building_id > 0 ? 'building' : 'block';
    $parent_id    = $building_id > 0 ? $building_id : $block_id;
    $level_label  = $building_id > 0 ? 'Tòa' : 'Phân khu';
    $parent_title = $parent_id > 0 ? $clsProperty->getOneField('title', $parent_id) : '';
    $arrBedrooms  = $clsProperty->getAll("property_type='_BEDROOM' and is_trash=0 order by order_no ASC", "property_id,title");
    # Type-card đã lưu của cấp đang mở
    $rows = ($parent_id > 0) ? $clsApartmentMedia->getByParent($parent_id, $parent_type) : array();
    $sections = array();
    foreach($rows as $r){
        $sections[] = array(
            'row_id'          => $r['id'],
            'uid'             => 'am_'.$r['id'],
            'bedroom_id'      => $r['bedroom_id'],
            'type_label'      => $r['type_label'],
            'media_boc_mai'   => $r['media_boc_mai'],
            'folder_boc_mai'  => $r['folder_boc_mai'],
            'media_noi_that'  => $r['media_noi_that'],
            'folder_noi_that' => $r['folder_noi_that'],
        );
    }
    # Gợi ý kế thừa: mở ở Tòa nhưng tòa chưa có dữ liệu, mà phân khu lại có
    $inherit_note = '';
    if($building_id > 0 && empty($rows) && $block_id > 0){
        if(!empty($clsApartmentMedia->getByParent($block_id, 'block')))
            $inherit_note = 'Tòa này chưa có ảnh riêng — website đang lấy ảnh của Phân khu. Thêm ở đây để ghi đè riêng cho tòa (ưu tiên Tòa).';
    }
    $smarty->assign('arrBedrooms', $arrBedrooms);
    $smarty->assign('sections', $sections);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('building_id', $building_id);
    $smarty->assign('block_id', $block_id);
    $smarty->assign('level_label', $level_label);
    $smarty->assign('parent_title', $parent_title);
    $smarty->assign('inherit_note', $inherit_note);
    $smarty->assign('core', $core);
    $html = $core->build('_ajax.open_interior_ns.tpl');
    echo $html; die();
}
# Render 1 Type-card rỗng khi bấm "+ Thêm Type"
function default_add_interior_type(){
    global $core,$clsISO;
    $clsProperty = new Property();
    $arrBedrooms = $clsProperty->getAll("property_type='_BEDROOM' and is_trash=0 order by order_no ASC", "property_id,title");
    $s = array(
        'row_id'=>0, 'uid'=>'new_'.$clsISO->getUniqid(), 'bedroom_id'=>0, 'type_label'=>'',
        'media_boc_mai'=>'', 'folder_boc_mai'=>'', 'media_noi_that'=>'', 'folder_noi_that'=>'',
    );
    $html = $core->build('_ajax.interior_type.tpl', array('s'=>$s, 'arrBedrooms'=>$arrBedrooms, 'core'=>$core));
    echo json_encode(array('html'=>$html)); die();
}
# Lưu cả modal: upsert mọi Type-card (theo row_id), xóa card đã gỡ
function default_save_interior_ns(){
    global $core;
    $clsApartmentMedia = new ApartmentMedia();
    $project_id  = (int) Input::post('project_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $block_id    = (int) Input::post('block_id', 0);
    $parent_type = $building_id > 0 ? 'building' : 'block';
    $parent_id   = $building_id > 0 ? $building_id : $block_id;
    if($parent_id <= 0){ echo json_encode(array('msg'=>'_error')); die(); }
    $rows = Input::post('rows', array());
    $uid  = (int) $core->_USER['user_id'];
    $present = array(); $order = 0;
    if(is_array($rows)){
        foreach($rows as $r){
            if(!is_array($r)) continue;
            if((int)($r['bedroom_id'] ?? 0) <= 0) continue; // card chưa chọn loại căn -> bỏ (card cũ sẽ bị xóa bên dưới)
            $r['order_no'] = $order++;
            $sid = $clsApartmentMedia->saveType($parent_id, $parent_type, (int)($r['row_id'] ?? 0), $r, $uid);
            if($sid > 0) $present[] = (int)$sid;
        }
    }
    # Dọn Type-card đã gỡ khỏi modal
    $existing = $clsApartmentMedia->getByParent($parent_id, $parent_type);
    if(!empty($existing)) foreach($existing as $er){
        if(!in_array((int)$er['id'], $present)) $clsApartmentMedia->deleteOne($er['id']);
    }
    echo json_encode(array('msg'=>'_success', 'building_id'=>$building_id, 'block_id'=>$block_id)); die();
}
function default_sync_search(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsStock = new Stock();
    $clsProperty = new Property();
    $clsProject = new Project();
    $clsProjectMeta = new ProjectMeta();
    ###
    $tmp = $clsProjectMeta->getAll("1=1", "{$clsProjectMeta->pkey},for_id,type,title");
    if(!empty($tmp)){
        foreach($tmp as $key => $val){
            $for_id = $val['for_id'];
            $title_search = $val['title'];
            if($val['type']=='block'){
                $oneBlock = $clsProperty->getOne($for_id, "title,for_id");
                $title_search.= " " . $oneBlock['title'];
                $title_search.= ", " . $clsProject->getTitle($oneBlock['for_id']);
                //$clsISO->print_pre($title_search); die();
            } else if($val['type']=='building'){
                $oneBuilding = $clsProperty->getOne($for_id, "title,for_id");
                $title_search.= ", Toà ". $oneBuilding['title'];
                $oneBlock = $clsProperty->getOne($oneBuilding['for_id'],"title,for_id");
                $title_search.= ", Phân khu ". $oneBlock['title'];
                $title_search.= ", " . $clsProject->getTitle($oneBlock['for_id']);
            } else if($val['type']=='project'){
                $title_search.= " " . $clsProject->getTitle($for_id);
            }
            $slug_search = $core->replaceSpace($title_search);
            $clsProjectMeta->updateOne($val[$clsProjectMeta->pkey], array(
                'slug' => $slug_search,
                'title_search' => $title_search
            ));
        }
    }
    // Return
    echo(1); die();
}
function default_open_setup_floor(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $building_id = (int) Input::post('building_id', 0);
    $smarty->assign('building_id', $building_id);
    $smarty->assign('clsProperty', $clsProperty);
    $field = "more_information";
    $oBuilding = $clsProperty->getOne($building_id, $field);
    $more_information = $oBuilding['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $list_floors = isset($more_information['floor']) ? $more_information['floor'] : "";
    ####
    $field = "{$clsProperty->pkey},title";
    $arrFloorType = $clsProperty->getAll("property_type='_FLOOR_TYPE' order by order_no ASC", $field);
    $smarty->assign('arrFloorType', $arrFloorType);
    // $clsISO->print_pre($floor); die();
    $floor_arrs = array();
    if(isset($more_information['floor_config']) && !empty($more_information['floor_config'])){
        $tmp = !empty($list_floors) ? @explode(',', $list_floors) : array();
        $floor_arrs = $more_information['floor_config'];
        if(!empty($tmp)){
            foreach($tmp as $key => $floor){
                if(!isset($floor_arrs[$floor])) {
                    $floor_arrs[$floor] = array(
                        'floor' => $floor,
                        'is_special' => 0,
                        'floor_type' => _FLOOR_TYPE_NORMAL_ID
                    );
                }
            }
        }
    } else {
        $tmp = !empty($list_floors) ? @explode(',', $list_floors) : array();
        if(!empty($tmp)){
            foreach($tmp as $key => $floor){
                $floor_arrs[$floor] = array(
                    'floor' => $floor,
                    'is_special' => 0,
                    'floor_type' => _FLOOR_TYPE_NORMAL_ID
                );
            }
        }
    }
    $smarty->assign('lst_floor', $tmp);
    $smarty->assign('floor_arrs', $floor_arrs);
    // Return
    $smarty->assign('core', $core);
    $html = $core->build('_ajax.setup_floor.tpl');
    echo json_encode(array(
        'html' => $html
    )); die();
}
function default_save_setup_floor(){
    global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
           ,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $building_id = (int) Input::post('building_id', 0);
    ###
    $field = "more_information";
    $oBuilding = $clsProperty->getOne($building_id, $field);
    $more_information = $oBuilding['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    ###
    $floor_config = Input::post('floor_config');
    if(!empty($floor_config)){
        foreach($floor_config as $key => $val){
            if(!isset($val['is_special'])){
                $floor_config[$key]['is_special'] = 0;
            }
        }
    }
    $more_information['floor_config'] = $floor_config;
    ###
    $msg = "_error";
    if($clsProperty->updateOne($building_id, array(
        'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
    ))){
        $msg = "_success";
    }
    // Return
    echo $msg; die();
}
function default_ajOpenUtilities(){
    global $smarty,$_frontIsLoggedin_user_id,$core,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    ###
    $action= '_add';
    $project_id = (int) Input::post('project_id',0);
    $utilities_id = Input::post('utilities_id', "");
    $oneItem = $list_buildings = array();
    ###
    $titlePage = $core->get_Lang('AddDocument');
    if(!empty($utilities_id)){
        $action = '_edit';
        $titlePage = $core->get_Lang('EditUtilities');
        $utilities = $clsProject->getOneField('utilities', $project_id);
        $utilities = $clsISO->to_array_json($utilities);
        $oneItem = $utilities[$utilities_id];
        $block_id = isset($oneItem['block_id'])
            ? intval($oneItem['block_id']) : 0;
        if($block_id > 0){
            $field = "{$clsProperty->pkey},title";
            $list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' and `for_id`='{$block_id}'", $field);
        }
    }
    $lstBlock = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}'");
    // TitlePage
    $smarty->assign('titlePage', $titlePage);
    $smarty->assign('oneItem', $oneItem);
    $smarty->assign('utilities_id', $utilities_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('lstBlock', $lstBlock);
    $smarty->assign('list_buildings', $list_buildings);
    $smarty->assign('action', $action);
    $smarty->assign('clsProperty', $clsProperty);
    // Return
    $smarty->assign('core', $core);
    $html = $core->build('_ajax.utility_project.tpl');
    echo $html; die();
}
function default_saveUtilities(){
    global $_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
    $clsUser = new User();
    $clsProject = new Project();
    $clsUploadFile = new UploadFile();
    #
    $msg = '_error';
    $project_id = (int) Input::post('project_id',0);
    $utilities_id = Input::post('utilities_id',"");
    $utilities = $clsProject->getOneField('utilities', $project_id);
    $utilities = !empty($utilities) ? json_decode($utilities, true) : array();
    ###
    if(Input::exists('action','GET') && Input::get('action')=='delete'){
        unset($utilities[$utilities_id]);
        if($clsProject->updateOne($project_id, array(
            'utilities' => json_encode($utilities, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = '_success';

            #activity log
            $oneItem = $clsProject->getOne($project_id,"title");
            $clsActivityLog = new ActivityLog();
            $log = $clsActivityLog->addActivityLog("Project","delete",['field' => "utilities","title" =>$oneItem['title']]);
        }
    } else {
        $link_image = "";
        if(empty($utilities_id)){
            /*$image = array();
			$image["name"] = $_FILES['image']['name'];
			$image["type"] = $_FILES['image']['type'];
			$image["tmp_name"] = $_FILES['image']['tmp_name'];
			$image["error"] = $_FILES['image']['error'];
			$image["size"] = $_FILES['image']['size'];
			//	var_dump($image);die;
			if(!empty($image["name"])){
				if(@is_uploaded_file($image['tmp_name'])){
					$clsUploadFile = new UploadFile();
					$upload_file = $clsUploadFile->uploadItem($image,"/utilities/",EXTENSION_FILE_UPLOAD);
					$file_name = $image['name'];
					$file_size = $image['size'];
					// Upload file to google drive
					$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
					$folder_id = $clsGoogleUpload->create_folder($project_id);
					// $clsISO->print_pre($folder_id); die();
					$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
					$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					if(!empty($upload_file)){
						$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
					}
					@unlink(ROOTPATH . $upload_file);
					// Update to DB
				}
			}*/
            // Image
            $utilities_id = $clsISO->getUniqid();
            $utilities[$utilities_id] = array(
                'utilities_id' => $utilities_id,
                'title'		=> Input::post('title'),
                'block_id'	=> (int) Input::post('block_id', 0),
                'building_ids' => (int) Input::post('building_ids', 0),
                'cat_id'	=> Input::post('cat_id', 0),
                'content'	=> Input::post('content'),
                'reg_date'	=> time(),
                'upd_date'	=> time(),
                'user_id'	=> $core->_USER['user_id'],
                'user_id_update'	=> $core->_USER['user_id'],
                'image'		=> Input::post('image'),
                'icon'		=> Input::post('icon'),
            );
            $action_log = "insert";
        }else{
            $utilities[$utilities_id]['title'] = Input::post('title');
            $utilities[$utilities_id]['block_id'] = Input::post('block_id', 0);
            $utilities[$utilities_id]['building_ids'] = Input::post('building_ids', 0);
            $utilities[$utilities_id]['cat_id'] = Input::post('cat_id', 0);
            $utilities[$utilities_id]['content'] = Input::post('content');
            $utilities[$utilities_id]['upd_date'] = time();
            $utilities[$utilities_id]['user_id_update'] = $core->_USER['user_id'];
            $utilities[$utilities_id]['image'] = Input::post('image');
            $utilities[$utilities_id]['icon'] = Input::post('icon');
            // Image
            /*if(!empty($_FILES['image']['name'])){
				$image = array();
				$image["name"] = $_FILES['image']['name'];
				$image["type"] = $_FILES['image']['type'];
				$image["tmp_name"] = $_FILES['image']['tmp_name'];
				$image["error"] = $_FILES['image']['error'];
				$image["size"] = $_FILES['image']['size'];
				//	var_dump($image);die;
				if(!empty($image["name"])){
					if(@is_uploaded_file($image['tmp_name'])){
						$clsUploadFile = new UploadFile();
						$upload_file = $clsUploadFile->uploadItem($image,"/utilities/",EXTENSION_FILE_UPLOAD);
						$file_name = $image['name'];
						$file_size = $image['size'];
						// Upload file to google drive
						$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
						$folder_id = $clsGoogleUpload->create_folder($project_id);
						// $clsISO->print_pre($folder_id); die();
						$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
						$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
						if(!empty($upload_file)){
							$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
						}
						@unlink(ROOTPATH . $upload_file);
						$utilities[$utilities_id]['image'] = $link_image;
						// Update to DB
					}
				}
			}*/
            $action_log = "update";
        }
        $msg = '_error';
        if($clsProject->updateOne($project_id, array(
            'utilities' => json_encode($utilities, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = '_success';

            #activity log
            $oneItem = $clsProject->getOne($project_id,"title");
            $clsActivityLog = new ActivityLog();
            $log = $clsActivityLog->addActivityLog("Project",$action_log,['field' => "utilities","title" =>$oneItem['title']]);
        }
    }
    // output
    echo($msg); die();
}
function default_ajLoadListUtilities(){
    global $_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
    $clsUser = new User();
    $clsProperty = new Property();
    $clsProject = new Project();
    $project_id = Input::post('project_id',0);
    $block_id = (int) Input::post('block_id', 0);
    $uid = $clsISO->getUniqid();
    $html = '<div class="'.$uid.' dragscroll ui-resize-y">
	<table class="table table-vertical mb-0 table-stripped">
		<thead><tr>
			<th class="text-left">Tiêu đề</th>
			<th class="text-center" width="10%">Phân khu</th>
			<th class="text-center" width="10%">Loại hình</th>
			<th class="text-left" width="10%">Ngày cập nhật</th>
			<th width="10%"></th>
		</tr></thead>';
    $utilities = $clsProject->getOneField('utilities', $project_id);
    $lstUtilities = !empty($utilities) ? json_decode($utilities, true) : array();
//	 $clsISO->print_pre($lstUtilities); die();
    $array_cache_block = $array_cache_cat = [];
    $ii = 0; // số dòng thực sự hiển thị sau khi lọc theo phân khu
    if(!empty($lstUtilities)){
        foreach($lstUtilities as $k_utilities => $_oUtilities){
            $_block_id = isset($_oUtilities['block_id']) ? (int) $_oUtilities['block_id'] : 0;
            if($block_id > 0 && $_block_id != $block_id){
                continue;
            }
            if(!isset($array_cache_block[$_block_id])) {
                $array_cache_block[$_block_id] = $clsProperty->getTitle($_block_id);
            }
            if(!isset($array_cache_cat[$_oUtilities['cat_id']])) {
                $array_cache_cat[$_oUtilities['cat_id']] = $clsProperty->getTitle($_oUtilities['cat_id']);
            }
            $html .= '<tr>
				<td class="fieldarea right_click" '.$props.'><div class="limit_1line">'.($ii+1).'. '.$_oUtilities['title'].'</div></td>
				<td class="fieldarea right_click" '.$props.'>'.$array_cache_block[$_block_id].'</td>
				<td class="fieldarea right_click" '.$props.'>'.$array_cache_cat[$_oUtilities['cat_id']].'</td>
				<td class="fieldarea right_click text-nowrap" '.$props.'>'.$clsISO->convertTimeToText($_oUtilities['reg_date'], true).'</td>
				<td class="text-center">
					<button class="btn btn-xs btn-default" project_id="'.$project_id.'" utilities_id="'.$k_utilities.'" onclick="$Core.utilities.open(this,event)">'.$core->makeIcon('pencil').'</button>
					<button class="btn btn-xs btn-default deleteDocShare" project_id="'.$project_id.'" utilities_id="'.$k_utilities.'" onclick="$Core.utilities.delete(this,event)">'.$core->makeIcon('trash').'</button>
				</td>
			</tr>';
            ++$ii;
        }
        unset($lstUtilities);
    }
    if($ii == 0){
        $html .= '<tr>
			<td class="text-center" colspan="5">Chưa có dữ liệu</td>
		</tr>';
    }
    $html .= '</table>
	</div>';
    // Output
    echo json_encode(array(

        'uid' => $uid,
        'html' => $html
    )); die();
}
function default_load_block(){
    global $assign_list,$_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $project_id = (int) Input::post('project_id', 0);
    $stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    ###
    $field = "{$clsProperty->pkey},title";
    $list_blocks = $clsProperty->getAll("is_trash=0 and property_type='_BLOCK' 
	and for_id='{$project_id}' and parent_id='{$stock_type}'", $field);
    ###
    $html_options = '<option value="">Chọn phân khu</option>';
    if(!empty($list_blocks)){
        foreach($list_blocks as $key => $val){
            $html_options.= '<option value="'.$val[$clsProperty->pkey].'">Phân khu '.$val['title'].'</option>';
        }
    }
    // Return
    echo $html_options; die();
}
function default_map(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsUser = new User();
    $clsProperty = new Property();
    $clsProject = new Project();
    $clsStockShape = new StockShape();
    ###
    $stock_type = (int) Input::get('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    $project_id = (int) Input::get('project_id', _PROJECT_VHOP2_ID);
    $block_id = (int) Input::get('block_id', 0);
    $assign_list['stock_type'] = $stock_type;
    $assign_list['project_id'] = $project_id;
    $assign_list['block_id'] = $block_id;
    ###
    $field = "{$clsProject->pkey},title";
    $list_projects = $clsProject->getAll("`is_trash`=0 and `list_block_type` like '%|{$stock_type}|%'", $field);
    $assign_list['list_projects'] = $list_projects;
    // $clsISO->print_pre($list_projects); die();
    if(isset($_POST['hid']) && $_POST['hid'] == 'hid'){
        $stock_type = (int) Input::post('stock_type', 0);
        $project_id = (int) Input::post('project_id', 0);
        $block_id = (int) Input::post('block_id', 0);
        $link = sprintf('index.php?mod=%s&act=%s&stock_type=%s&project_id=%s&block_id=%s',
            $mod, $act, $stock_type, $project_id,$block_id);
        // Header
        header('Location: ' . $link);
        exit();
    }
    $oneProject = $clsProject->getOne($project_id);
    $more_information = $oneProject['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    if($block_id > 0) {
        $oneBlock = $clsProperty->getOne($block_id,"more_information");
        $block_information = $oneBlock['more_information'];
        $block_information = $clsISO->to_array_json($block_information);
        if(isset($block_information['is_project']) && (int) $block_information['is_project'] == 1) {
            $more_information = $block_information;
        }
    }
    $list_blocks = $list_buildings = array();
    if(!empty($oneProject)){
        $field = "{$clsProperty->pkey},title";
        $list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
		and `for_id`='{$project_id}' and `parent_id`='{$stock_type}'", $field);
    }
    $scriptJs = '<script type="text/javascript">
		var map_configs= {};
		map_configs[\'max_zoom\'] = '.$more_information['max_zoom'].';
		map_configs[\'tiles\'] = \''.$more_information['tiles_link'].'\';
		map_configs[\'max_bounds\'] = '.$core->get_field($more_information, 'max_bound', '\'\'').';
		map_configs[\'center_point\'] = '.$core->get_field($more_information, 'center_point', '\'\'').';
		map_configs[\'tms_enable\'] = '.((int) $core->get_field($more_information,'tms_enable', 0)==1 ? 'true': 'false').';
	</script>';
    $assign_list['scriptJs'] = $scriptJs;

    $map_configs = array();
    if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
        $map_configs['min_zoom'] = -1;
        $map_configs['max_zoom'] = 10;
    }
    $tmp = $clsStockShape->getByCond("`stock_type`='{$stock_type}' and `project_id`='{$project_id}' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}'");
    if(!empty($tmp)){
        $map_configs = $tmp['map_configs'];
        $map_configs = $clsISO->to_array_json($map_configs);
        if(isset($map_configs['status']) && $map_configs['status'] == '_draw'){
            $map_configs['min_zoom'] = -1;
            $map_configs['max_zoom'] = 10;
        }
    } else {
        $map_configs['status'] = '_draw';
        $map_configs['min_zoom'] = -2.5;
        $map_configs['max_zoom'] = -2.5;
    }
    // $clsISO->print_pre($map_configs); die();
    $assign_list['map_configs'] = $map_configs;
    $assign_list['list_blocks'] = $list_blocks;
    ###
    $assign_list['more_information'] = $more_information;
    $assign_list['image_map_src'] = $image_map_src;
}
function default_draw_map(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsUser = new User();
    $clsProperty = new Property();
    $clsProject = new Project();
    $clsStockShape = new StockShape();
    ###
    $stock_type = (int) Input::get('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
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
        $link = sprintf('index.php?mod=%s&act=%s&stock_type=%s&project_id=%s&block_id=%s',
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
    }
    if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && $block_id > 0){
        $field = "{$clsProperty->pkey},title";
        $list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
			and for_id='{$block_id}' order by `order_no` ASC", $field);
    }
    $image_map_src = "";
    if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
        if($project_id > 0 && $block_id > 0){
            $more_information = $clsProperty->getOneField('more_information', $block_id);
            $more_information = $clsISO->to_array_json($more_information);
            $image_map_src = isset($more_information['layout_ms']) ? $more_information['layout_ms'] : "";
        } else {
            $more_information = $clsProject->getOneField('more_information', $project_id);
            $more_information = $clsISO->to_array_json($more_information);
            $image_map_src = isset($more_information['layout']) ? $more_information['layout'] : "";
        }
    } else {
        $more_information = $clsProperty->getOneField('more_information', $building_id);
        // $clsISO->print_pre($more_information); die();
        $more_information = $clsISO->to_array_json($more_information);
        $image_map_src = isset($more_information['layout_map']) ? $more_information['layout_map'] : "";
    }
    $map_configs = array();
    if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
        $map_configs['min_zoom'] = -1;
        $map_configs['max_zoom'] = 10;
    }
    $tmp = $clsStockShape->getByCond("`stock_type`='{$stock_type}' and `project_id`='{$project_id}' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}'");
    if(!empty($tmp)){
        $map_configs = $tmp['map_configs'];
        $map_configs = $clsISO->to_array_json($map_configs);
        if(isset($map_configs['status']) && $map_configs['status'] == '_draw'){
            $map_configs['min_zoom'] = -1;
            $map_configs['max_zoom'] = 10;
        }
    } else {
        $map_configs['status'] = '_draw';
        $map_configs['min_zoom'] = -2.5;
        $map_configs['max_zoom'] = -2.5;
    }
    // $clsISO->print_pre($map_configs); die();
    $assign_list['map_configs'] = $map_configs;
    ###
    $assign_list['list_blocks'] = $list_blocks;
    $assign_list['list_buildings'] = $list_buildings;
    $assign_list['more_information'] = $more_information;
    $assign_list['image_map_src'] = $image_map_src;
}
function default_merge_stock_shapes_bkc(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsStockShape = new StockShape();
    ##
    $msg = "_error";
    $holderG = Input::post('holderG', 'project');
    $stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    $project_id = (int) Input::post('project_id', _PROJECT_VHOP2_ID);
    ##
    $today = date("d/m/Y");
    $tmp = $clsStockShape->getByCond("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}' 
		AND `project_id`='{$project_id}' AND `{$clsStockShape->pkey}`='76'");
    $_STORE_SHAPES = !empty($tmp['shapes']) ? $clsISO->to_array_json($tmp['shapes']) : array();
    // $clsISO->print_pre($_STORE_SHAPES); die();
    $list_shapes = $clsStockShape->getAll("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}' 
	AND `project_id`='{$project_id}' and {$clsStockShape->pkey}<>'76'");
    if(!empty($list_shapes)){
        foreach($list_shapes as $key => $val){
            $shapes = $val['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            // $clsISO->print_pre($shapes); die();
            if(!empty($shapes)){
                if(!empty($_STORE_SHAPES)){
                    foreach($shapes as $okey => $oval){
                        $_STORE_SHAPES[] = $oval;
                    }
                } else {
                    $_STORE_SHAPES = $shapes;
                }
            }
        }
    }
    //$clsISO->print_pre($list_shapes); die();
    if(!empty($tmp)) {
        if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
            'shapes' => json_encode($_STORE_SHAPES, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = "_success";
        }
    }else{
        if($clsStockShape->insert(array(
            $clsStockShape->pkey => $clsStockShape->getMaxId(),
            'holderG' => $holderG,
            'stock_type' => $stock_type,
            'project_id' => $project_id,
            'shapes' => json_encode($_STORE_SHAPES, JSON_UNESCAPED_UNICODE),
            'user_id' => _USER_ADMIN_SUPER_ID,
            'user_id_update' => _USER_ADMIN_SUPER_ID,
            'reg_date' => time(),
            'upd_date' => time()
        ))){
            $msg = "_success";
        }
    }

    ##
    /*$tmp = $clsStockShape->getByCond("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}'
		AND `project_id`='{$project_id}' AND `user_id`='".$core->_USER['user_id']."' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".$today."'");

	$_STORE_SHAPES = !empty($tmp['shapes']) ? $clsISO->to_array_json($tmp['shapes']) : array();
//	 $clsISO->print_pre($_STORE_SHAPES); die();
	$list_shapes = $clsStockShape->getAll("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}'
	AND `project_id`='{$project_id}' AND `user_id`='".$core->_USER['user_id']."' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')<>'".$today."'");
//	$clsISO->print_pre($list_shapes);die;
	if(!empty($list_shapes)){
		foreach($list_shapes as $key => $val){
			$shapes = $val['shapes'];
			$shapes = $clsISO->to_array_json($shapes);
			// $clsISO->print_pre($shapes); die();
			if(!empty($shapes)){
				if(!empty($_STORE_SHAPES)){
					foreach($shapes as $okey => $oval){
						$_STORE_SHAPES[] = $oval;
					}
				} else {
					$_STORE_SHAPES = $shapes;
				}
			}
			$shapes = array(); // Tạo mảng mới
			$clsStockShape->updateOne($val[$clsStockShape->pkey], array(
				'shapes' => json_encode($shapes, JSON_UNESCAPED_UNICODE)
			));
		}
	}
//		$clsISO->print_pre($_STORE_SHAPES); die();
	if(!empty($tmp)) {
		if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
			'shapes' => json_encode($_STORE_SHAPES, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}else{
		if($clsStockShape->insert(array(
			$clsStockShape->pkey => $clsStockShape->getMaxId(),
			'holderG' => $holderG,
			'stock_type' => $stock_type,
			'project_id' => $project_id,
			'shapes' => json_encode($_STORE_SHAPES, JSON_UNESCAPED_UNICODE),
			'user_id' => $core->_USER['user_id'],
			'user_id_update' => $core->_USER['user_id'],
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	}*/

    // Return
    echo $msg; die();
}
function default_merge_stock_shapes(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsStockShape = new StockShape();
    ##
    $msg = "_error";
    $holderG = Input::post('holderG', 'project');
    $stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    $project_id = (int) Input::post('project_id', _PROJECT_VHOP2_ID);
    ##
    $arr_stocks = array();
    $tmp = $clsStockShape->getByCond("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}' 
		AND `project_id`='{$project_id}' AND `user_id`='"._USER_ADMIN_SUPER_ID."'");
    $_STORE_SHAPES = !empty($tmp['shapes']) ? $clsISO->to_array_json($tmp['shapes']) : array();
    if(!empty($_STORE_SHAPES)){
        foreach($_STORE_SHAPES as $key => $val){
            if(!empty($val['stock_id']) && !empty($val['stock_code'])){
                $arr_stocks[] = $val['stock_code'];
            }
        }
    }
    // $clsISO->print_pre($arr_stocks); die();
    $list_shapes = $clsStockShape->getAll("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}' 
	AND `project_id`='{$project_id}' and `user_id`<>'"._USER_ADMIN_SUPER_ID."'");
    if(!empty($list_shapes)){
        foreach($list_shapes as $key => $val){
            $shapes = $val['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            if(!empty($shapes)){
                foreach($shapes as $okey => $oval){
                    if(!empty($oval['stock_id']) && !empty($oval['stock_code']) && !in_array($oval['stock_code'], $arr_stocks)){
                        $arr_stocks[] = $oval['stock_code'];
                        $_STORE_SHAPES[] = $oval;
                    }
                }
            }
        }
    }
    // $clsISO->print_pre($_STORE_SHAPES); die();
    if(!empty($tmp)) {
        if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
            'shapes' => json_encode($_STORE_SHAPES, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = "_success";
        }
    }else{
        if($clsStockShape->insert(array(
            $clsStockShape->pkey => $clsStockShape->getMaxId(),
            'holderG' => $holderG,
            'stock_type' => $stock_type,
            'project_id' => $project_id,
            'shapes' => json_encode($_STORE_SHAPES, JSON_UNESCAPED_UNICODE),
            'user_id' => _USER_ADMIN_SUPER_ID,
            'user_id_update' => _USER_ADMIN_SUPER_ID,
            'reg_date' => time(),
            'upd_date' => time()
        ))){
            $msg = "_success";
        }
    }
    // Return
    echo $msg; die();
}
function default_save_stock_shapes(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsUser = new User();
    $clsProperty = new Property();
    $clsProject = new Project();
    $clsStockShape = new StockShape();
    $clsStock = new Stock();
    ##
    $msg  = "_error";
    $holderG = Input::post('holderG', 'stock');
    $stock_type = (int) Input::post('stock_type', 0);
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $shapes = Input::post('shapes');
    ##
    $cond = "`stock_type`='{$stock_type}' and `project_id`='{$project_id}'";
    if($block_id > 0) {
        $more_information = $clsProperty->getOneField("more_information", $block_id);
        $more_information = $clsISO->to_array_json($more_information);
        if(isset($more_information['is_project']) && (int) $more_information['is_project'] == 1) {
            $cond.= " and `block_id`='{$block_id}'";
        }
    }
    if($holderG == "stock_FH") {
        $cond .= " AND `holderG`='stock_FH'";
    } else if($holderG == 'project'){
        $cond.= " and `holderG`='{$holderG}' and `user_id`='".$core->_USER['user_id']."'";
    } else{
        $cond .= " AND `holderG`<>'stock_FH'";
    }
    if($holderG != 'project'){
        $building_id = (int) Input::post('building_id', 0);
        if($block_id > 0) $cond.= " and `block_id`='{$block_id}'";
        if($building_id > 0) $cond.= " and `building_id`='{$building_id}'";
    }
    $tmp = $clsStockShape->getByCond($cond,$clsStockShape->pkey.",shapes_render");
    // $clsISO->print_pre($tmp);die;
    $shapes_render = $tmp['shapes_render'];
    $shapes_render = $clsISO->to_array_json($shapes_render);
    if($holderG == "stock_FH") {
        if(!empty($shapes)) {
            $arr_shapes = $arr_shapes_id = array();
            $arr_shapes = $clsISO->to_array_json($shapes);
            foreach($arr_shapes as $key => $val) {
                $shape_id = $val['shape_id'];
                $arr_shapes_id[] = $shape_id;
                $lstStock = $clsStock->getAll("`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `code`='".$val['code']."' 
					AND `building_id`='{$building_id}' AND `agency_id`='"._AGENCY_FH_ID."'",$lstStock->pkey);
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
        }
        // $clsISO->print_pre($arr_shapes);die;
    }
    if(!empty($tmp)){
        $more = array();
        if($holderG=='stock_FH'){
            $more['shapes_render'] = json_encode($shapes_render);
        }
        if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array_merge($more, array(
            'shapes' => $shapes,
            'user_id_update' => $core->_USER['user_id'],
            'upd_date' => time()
        )))){
            $msg = "_success";
        }
    } else {
        $more = array();
        if($holderG == 'stock' || $holderG == 'block' || $holderG == 'stock_FH'){
            $more['block_id'] = $block_id;
            $more['building_id'] = $building_id;
            $more['shapes_render'] = json_encode($shapes_render);
        }
        if($clsStockShape->insert(array_merge($more, array(
            $clsStockShape->pkey => $clsStockShape->getMaxId(),
            'holderG' => $holderG,
            'stock_type' => $stock_type,
            'project_id' => $project_id,
            'block_id' => $block_id,
            'shapes' => $shapes,
            'user_id' => $core->_USER['user_id'],
            'user_id_update' => $core->_USER['user_id'],
            'reg_date' => time(),
            'upd_date' => time()
        )))){
            $msg = "_success";
        }
    }
    // Return
    echo $msg; die();
}
function default_add_block(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $msg = "_error";
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $shape_id = (int) Input::post('shape_id', 0);
    ###
    $tmp = $clsStockShape->getByCond("`holderG`='block' and `project_id`='{$project_id}'");
    if(!empty($tmp)){
        $shapes = $tmp['shapes'];
        $shapes = $clsISO->to_array_json($shapes);
        // $clsISO->print_pre($shapes); die();
        if(!empty($shapes)){
            foreach($shapes as $key => $val){
                if($val['shape_id'] == $shape_id){
                    $shapes[$key]['block_id'] = $block_id;
                    break;
                }
            }
        }
        // $clsISO->print_pre($shapes); die();
        if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
            'shapes' => json_encode($shapes, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = "_success";
        }
    }
    // Return
    echo $msg; die();
}
function default_add_stock(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $msg = "_error";
    $stock_code = Input::post('stock_code', 0);
    $stock_id = (int) Input::post('stock_id', 0);
    $direction = (int) Input::post('direction', 'top');
    $shape_id = (int) Input::post('shape_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    ###
    $tmp = $clsStockShape->getByCond("`holderG`='stock' 
		and `project_id`='{$project_id}' and `block_id`='{$block_id}'");
    // $clsISO->print_pre($tmp); die();
    if(!empty($tmp)){
        $shapes = $tmp['shapes'];
        $shapes = $clsISO->to_array_json($shapes);
        if(!empty($shapes)){
            foreach($shapes as $key => $val){
                if($val['shape_id'] == $shape_id){
                    $shapes[$key]['stock_id'] = $stock_id;
                    $shapes[$key]['stock_code'] = $stock_code;
                    $shapes[$key]['direction'] = $direction;
                    break;
                }
            }
        }
        if($clsStockShape->updateOne($tmp[$clsStockShape->pkey], array(
            'shapes' => json_encode($shapes, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = "_success";
        }
    }
    // Return
    echo $msg; die();
}
function default_get_stock_shapes(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsUser = new User();
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    $clsStock = new Stock();
    ###
    $holderG = Input::get('holderG', "project");
    $project_id = (int) Input::get('project_id', 0);
    $block_id = (int) Input::get('block_id', 0);
    $stock_type = (int) Input::get('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    ###
    $_results = array('msg' => '_error', 'holderG' => $holderG);
    $cond = "`stock_type`='{$stock_type}' AND `holderG`='{$holderG}' AND `project_id`='{$project_id}' 
		AND block_id='{$block_id}'";
    // $cond = " AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".$today."'";
    $tmp = $clsStockShape->getByCond($cond);
    // $clsISO->print_pre($tmp); die();
    $merge_shapes = $shapes = array();
    $check = 0;
    if(!empty($tmp)){
        $shapes = $tmp['shapes'];
        $shapes = $clsISO->to_array_json($shapes);
        // $clsISO->print_pre($shapes); die();
        /*$arr_shape = array();
		foreach ($shapes as $key => $val) {
			if(empty($arr_shape[$val["shape_id"]])) {
				$arr_shape[$val["shape_id"]] = $val;
			}

		}
		$shapes = array_values($arr_shape);*/
        $check = 1;
    }
    $cond_merge = "`stock_type`='{$stock_type}' AND `holderG`='{$holderG}' AND `project_id`='{$project_id}' 
		AND block_id=`{$block_id}` AND `user_id`<>'".$core->_USER['user_id']."'";
    $tmp_merge = $clsStockShape->getAll($cond_merge);
    if(!empty($tmp_merge)){
        foreach($tmp_merge as $okey => $oval){
            $_shapes = $oval['shapes'];
            $_shapes = $clsISO->to_array_json($_shapes);
            $merge_shapes = @array_merge($merge_shapes, $_shapes);
        }
        $check = 1;
    }
    if(!empty($check)) {
        $_results['msg'] = '_success';
        $_results['shapes'] = $shapes;
        $_results['merge_shapes'] = $merge_shapes;
        $_results['stock_shape_id'] = $tmp[$clsStockShape->pkey];
    }
    // Return
    echo json_encode($_results);
    die();
}
function default_get_stock_shapes_bkc(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsUser = new User();
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    $clsStock = new Stock();
    ###
    $holderG = Input::get('holderG', "project");
    $project_id = (int) Input::get('project_id', 0);
    $stock_type = (int) Input::get('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    ###
    $_results = array('msg' => '_error', 'holderG' => $holderG);
    $cond = "`stock_type`='{$stock_type}' AND `holderG`='{$holderG}' AND `project_id`='{$project_id}'";
    $tmp = $clsStockShape->getByCond($cond);
    if(!empty($tmp)) {
        $shapes = $tmp['shapes'];
        $shapes = $clsISO->to_array_json($shapes);
        ###
        $_results['msg'] = '_success';
        $_results['shapes'] = $shapes;
        $_results['merge_shapes'] = array();
        $_results['stock_shape_id'] = $tmp[$clsStockShape->pkey];
    }
    // Return
    echo json_encode($_results);
    die();
}
function default_get_shapes(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    $clsStock = new Stock();
    ###
    $type = Input::get('type', "draw_map");
    $holderG = Input::get('holderG', "stock");
    $stock_type = (int) Input::get('stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
    $project_id = (int) Input::get('project_id', 0);
    $block_id = (int) Input::get('block_id', 0);
    $building_id = (int) Input::get('building_id', 0);
    $arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
    $arr_direction = $clsProperty->getArraySearchByKey("_DIRECTION");
    ###
    $_results = array('msg' => '_error', 'holderG' => $holderG);
    $cond = "`stock_type`='{$stock_type}' AND `project_id`='{$project_id}'";
    if($holderG== 'stock'){
        if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && $building_id > 0){
            $cond.= " and `building_id`='{$building_id}'";
        }
        $more_information = $clsProject->getOneField('more_information', $project_id);
        $more_information = $clsISO->to_array_json($more_information);
        if($block_id > 0) {
            $cond.= " and `block_id`='{$block_id}'";
        } else {
            if($more_information['has_block'] == 1) {
                $_results['holderG'] = 'block';
            }else{
                $_results['holderG'] = 'stock';
            }
        }
        if($type == "draw_map_FH") {
            $_results['holderG'] = 'stock_FH';
            $cond.= " AND `holderG`='stock_FH'";
        } else{
            $cond.= " AND `holderG`<>'stock_FH'";
        }
        $tmp = $clsStockShape->getAll($cond);
    } else if($holderG == 'project'){
        if($core->_USER['user_id'] != _USER_ADMIN_SUPER_ID){
            $cond.= " AND `holderG`='{$holderG}' AND (`user_id`='".$core->_USER['user_id']."' OR `user_id`='"._USER_ADMIN_SUPER_ID."')";
        } else {
            $cond.= " AND `holderG`='{$holderG}' AND `user_id`='".$core->_USER['user_id']."'";
        }
        $tmp = $clsStockShape->getAll($cond);
    }
    // $clsISO->print_pre($tmp); die();
    if(!empty($tmp)){
        $shapes = array();
        foreach($tmp as $key => $val){
            $_shapes = $val['shapes'];
            $_shapes = $clsISO->to_array_json($_shapes);
            if(!empty($_shapes)){
                foreach($_shapes as $okey => $oval){
                    $shapes[] = $oval;
                }
            }
        }
        $_results['msg'] = '_success';
        $_results['shapes'] = $shapes;
        $_results['stock_shape_id'] = $tmp[$clsStockShape->pkey];
    }
    // Return
    echo json_encode($_results);
    die();
}
function default_update_pos(){
    global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,
           $title_page,$description_page,$keyword_page,$clsISO,$deviceType;
    $clsStock = new Stock();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    ###
    $msg = "_error";
    $stock_shape_id = (int) Input::post('stock_shape_id', 0);
    $shape_id = (int) Input::post('shape_id', 0);
    $stock_id = (int) Input::post('stock_id', 0);
    $latlng = Input::post('latlng');
    $latlng = @json_decode($latlng, true);
    $oneShapes = $clsStockShape->getOne($stock_shape_id);
    $shapes = $oneShapes["shapes"];
    $shapes = $clsISO->to_array_json($shapes);
//	 $clsISO->print_pre($shapes); die();
    $shapes_render = $oneShapes["shapes_render"];
    $shapes_render = !empty($shapes_render) ? $clsISO->to_array_json($shapes_render) : array();
    if(!empty($shapes)){
        foreach($shapes as $key=> $val){
            $arr_stock_id = array();
            if($val['shape_id'] == $shape_id){
                $shapes_render_default = $val;
                $shapes_render_default["stock_id"] = $stock_id;
                $shapes_render_default["latlng"] = $latlng;
                $shapes_render[$shape_id][$stock_id]= $shapes_render_default;
                $arr_stock_id = array_merge($arr_stock_id,array_keys($shapes_render[$shape_id]));
//				$shapes[$key]['latlng'] = $latlng;
                break;
            }
            //cập nhật những căn chưa được set tọa độ
            $lstStock = $clsStock->getAll("`status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `code`='".$val['code']."' AND `building_id`='{$oneShapes["building_id"]}' AND `agency_id`='"._AGENCY_FH_ID."' AND `{$clsStock->pkey}` NOT IN (".implode(',',$arr_stock_id).")");
            if(!empty($lstStock)) {
                foreach($lstStock as $k => $v) {
                    $shapes_render_stock = $val;
                    $shapes_render_stock["stock_id"] = $v['stock_id'];
                    $shapes_render[$shape_id][$v['stock_id']]= $shapes_render_stock;
                }
            }
        }
//		$clsStockShape->setDeBug(1);
        if($clsStockShape->updateOne($stock_shape_id, array(
            'shapes_render' => json_encode($shapes_render, JSON_UNESCAPED_UNICODE)
        ))) {
            $msg = '_success';
        }
    }
    // Return
    echo $msg; die();
}
function default_get_pop(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    $clsStock = new Stock();
    ###
    $uid = $clsISO->getUniqid();
    $shape_id = Input::get('shape_id', 0);
    $_leaflet_id = Input::get('_leaflet_id', 0);
    $holderG = Input::get('holderG', 'stock');
    $stock_type = (int) Input::get('stock_type', 0);
    $project_id = (int) Input::get('project_id', 0);
    $block_id = (int) Input::get('block_id', 0);
    $building_id = (int) Input::get('building_id', 0);
    $html_options = '<option value="0">Chọn block</option>';
    $today = date("d/m/Y");
    if($core->_USER["user_id"] == _USER_ADMIN_SUPER_ID) {
        // $today = date("d/m/Y",1752045592);
    }
    if($holderG == 'stock'){
        $_oShape = array('stock_id' => 0, 'stock_code' => '');
        $tmp = $clsStockShape->getByCond("`holderG`='stock' and `stock_type`='{$stock_type}' 
			and `project_id`='{$project_id}' and `block_id`='{$block_id}' and `building_id`='{$building_id}'");
        $has_data = 0;
        if(!empty($tmp)){
            $shapes = $tmp['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            if(!empty($shapes)){
                foreach($shapes as $key => $val){
                    if($val['shape_id'] == $shape_id){
                        $_oShape = $val;
                        $has_data = 1;
                        break;
                    }
                }
            }
        }
        // $clsISO->print_pre($_oShape); die();
        if($_oShape["stock_id"]==0 && ($project_id == _PROJECT_VWC_ID || in_array($block_id,_BLOCK_NOT_SEARCH))) {
            $oneProject = $clsProject->getOne($project_id,"more_information");
            $more_information = $clsISO->to_array_json($oneProject['more_information']);
            $cond_project = "";
            if(isset($more_information['has_block']) && $more_information['has_block'] == 1) {
                $cond_project .= "  and `block_id`='{$block_id}' ";
            }
            ###
            $html_stock = "";
            $cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
            $cond.= " and `project_id`='{$project_id}'";
            $cond .= $cond_project;
            if($project_id != 10 && !in_array($block_id,_BLOCK_NOT_SEARCH)) {
                $cond.= " and (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
					AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
                $cond.= " and `agency_id`<>'0'";
            }

            if(in_array($block_id,_BLOCK_NOT_ĐLBM)) {
                $cond .= " AND `ms_code` NOT LIKE 'ĐLBM-%'";
            }
            $cond .= " AND `ms_code` NOT LIKE 'ĐLHD-%'";
            ###
            $arr_not_ins = array();
            $tmp = $clsStockShape->getByCond("`holderG`='stock' 
				and `project_id`='{$project_id}'".$cond_project);
            if($tmp){
                $shapes = $tmp['shapes'];
                $shapes = $clsISO->to_array_json($shapes);
                if(!empty($shapes)){
                    foreach($shapes as $key => $val){
                        if(!empty($val['stock_id']) && !in_array($val['stock_id'], $arr_not_ins)){
                            $arr_not_ins[] = $val['stock_id'];
                        }
                    }
                }
                unset($tmp);
            }
            if(!empty($arr_not_ins))
                $cond.= " and `{$clsStock->pkey}` not in (".implode(',', $arr_not_ins).")";
            $field = "{$clsStock->pkey},`ms_code`,`more_information`";

            $oneStock = $clsStock->getByCond($cond, $field);
            $_oShape["stock_id"] = $oneStock[$clsStock->pkey];
            $_oShape["stock_code"] = $oneStock['ms_code'];
        }
    } else if($holderG == 'project'){
        $_oShape = array('stock_id' => 0, 'stock_code' => '');
        $tmp = $clsStockShape->getByCond("`holderG`='{$holderG}' AND `stock_type`='{$stock_type}' AND `project_id`='{$project_id}' 
			AND `block_id`='{$block_id}' AND `user_id`='".$core->_USER['user_id']."'");
        // $cond = " AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".$today."'";
        $has_data = 0;
        if(!empty($tmp)){
            $shapes = $tmp['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            if(!empty($shapes)){
                foreach($shapes as $key => $val){
                    if($val['shape_id'] == $shape_id){
                        $_oShape = $val;
                        $has_data = 1;
                        break;
                    }
                }
            }
        }
        if(($core->_USER["user_id"] == 57) && empty($_oShape["stock_id"])) {
            $arr_not_ins = array();
            $tmp_shape = $clsStockShape->getAll("`holderG`='{$holderG}' and `stock_type`='{$stock_type}' and `project_id`='{$project_id}' 
			and `user_id`='".$core->_USER['user_id']."'");
            if($tmp_shape){
                foreach ($tmp_shape as $k => $v) {
                    $shapes = $v['shapes'];
                    $shapes = $clsISO->to_array_json($shapes);
                    if(!empty($shapes)){
                        foreach($shapes as $key => $val){
                            if(!empty($val['stock_id']) && !in_array($val['stock_id'], $arr_not_ins)){
                                $arr_not_ins[] = $val['stock_id'];
                            }
                        }
                    }
                }
//				$clsISO->print_pre($arr_not_ins);die;
//				unset($tmp);
            }
            $cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and `project_id`='{$project_id}' and `block_id`>0 ";
            if(!empty($block_id)){
                $cond .= " AND `block_id`='{$block_id}'";
            }
            if(!empty($arr_not_ins)) {
                $cond.= " and `{$clsStock->pkey}` not in (".implode(',', $arr_not_ins).")";
            }
            $field = "{$clsStock->pkey},`ms_code`,`more_information`";
//			$dbconn->debug=true;
            $oneStock = $clsStock->getByCond($cond." ORDER BY `stock_id` ASC", $field);
//			$clsISO->print_pre($oneStock);die;
            $_oShape["stock_id"] = $oneStock[$clsStock->pkey];
            $_oShape["stock_code"] = $oneStock['ms_code'];
        }
    } else if($holderG == 'block'){
        $field = "{$clsProperty->pkey},title";
        $list_blocks = $clsProperty->getAll("is_trash=0 and property_type='_BLOCK' 
		and for_id='{$project_id}' and parent_id='"._BLOCK_TYPE_LOWFLOOR_SALE."'", $field);
        ###
        $_oShape = array('block_id' => 0);
        $tmp = $clsStockShape->getByCond("`holderG`='block' and `project_id`='{$project_id}'");
        if(!empty($tmp)){
            $shapes = $tmp['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            // $clsISO->print_pre($shapes); die();
            if(!empty($shapes)){
                foreach($shapes as $key => $val){
                    if($val['shape_id'] == $shape_id){
                        $_oShape = $val;
                        break;
                    }
                }
            }
        }
        if(!empty($list_blocks)){
            foreach($list_blocks as $key => $val){
                $html_options.= '<option value="'.$val[$clsProperty->pkey].'"'.($_oShape['block_id']==$val[$clsProperty->pkey]?' selected':'').'>'.$val['title'].'</option>';
            }
            unset($list_blocks);
        }
    }
    ###
    $html = '<div class="d-flex align-items-center gap-2 mb-3">
		<button type="button" title="Sửa" class="btn flex-fill btn-sm btn-default 
		js__edit_shape_'.$shape_id.'" shape_id="'.$shape_id.'" _leaflet_id="'.$_leaflet_id.'" onclick=\'edit_shape(this, event)\'><i class="fa fa-pencil"></i> Sửa</button>
		<button type="button" title="Xoá" class="btn flex-fill btn-sm btn-default js__delete_shape  
		js__delete_shape_'.$shape_id.'" shape_id="'.$shape_id.'" _leaflet_id="'.$_leaflet_id.'" onclick=\'delete_shape(this, event)\'><i class="fa fa-trash"></i>️ Xóa</button>
		<button type="button" title="Xoá" class="btn flex-fill btn-sm btn-default js__copy_shape 
		js__copy_shape_'.$shape_id.'" shape_id="'.$shape_id.'" holderG="'.$holderG.'" _leaflet_id="'.$_leaflet_id.'" onclick=\'copy_shape(this, event)\'><i class="fa fa-copy"></i>️ Nhân bản</button>
	</div>
	<hr class="my-3" />
	<div class="form-group mb-2">
		<label class="form-label mb-1">'.(($holderG=='stock' || $holderG=='project') ? 'Mã căn hộ' : 'Phân khu').':</label>
		'.(($holderG=='stock' || $holderG == 'project') ? '<div class="position-relative">
			<div class="input-loading loading position-relative">
				<input uid="'.$uid.'" type="hidden" name="stock_id" value="'.$_oShape['stock_id'].'" 
					shape_id="'.$shape_id.'" class="js__input_stock_id" />
				<input type="text" autocomplete="off" onKeyup="$Core.project.do_search(this, event)" 
				uid="'.$uid.'" shape_id="'.$shape_id.'" holderG="'.$holderG.'" project_id="'.$project_id.'" block_id="'.$block_id.'" 
				class="form-control js__input_stock_code" name="stock_code" placeholder="Nhập mã căn hộ để tìm kiếm" 
				value="'.$_oShape['stock_code'].'" building_id="'.$building_id.'" stock_type="'.$stock_type.'">
				<span class="icon-loader position-absolute text-muted">
					<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i>
				</span>
			</div>
			<div id="'.$uid.'" class="autosugget d-none"></div>
		</div>' : '<select name="block_id" shape_id="'.$shape_id.'" 
			class="form-control form-select">'.$html_options.'</select>').' 
	</div>
	'.($holderG=='stock' && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE ? '
		<div class="form-group">
			<label class="form-label mb-1">Vị trí:</label>
			<select class="form-control" name="direction" uid="'.$uid.'" shape_id="'.$shape_id.'" project_id="'.$project_id.'" block_id="'.$block_id.'" building_id="'.$building_id.'" stock_type="'.$stock_type.'">
				<option'.($_oShape['direction']=='right' ? ' selected' : '').' value="right">Trái</option>
				<option'.($_oShape['direction']=='left' ? ' selected' : '').' value="left">Phải</option>
				<option'.($_oShape['direction']=='bottom' ? ' selected' : '').' value="bottom">Trên</option>
				<option'.($_oShape['direction']=='top' ? ' selected' : '').' value="top">Dưới</option>
			</select>
		</div>
	' : '').'
	<div class="form-group">
		<button holderG="'.$holderG.'" _leaflet_id="'.$_leaflet_id.'" project_id="'.$project_id.'" block_id="'.$block_id.'" shape_id="'.$shape_id.'" uid="'.$uid.'" onclick="add_stock(this, event)" class="btn btn-block btn-success js__btn_add_stock">Lưu</button>
	</div>';
    // Return
    echo $html; die();
}
function default_search_stock(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsStock = new Stock();
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    ###
    $uid = Input::post('uid');
    $holderG = Input::post('holderG', 'stock');
    $stock_type = (int) Input::post('stock_type', 0);
    $project_id = (int) Input::post('project_id', 0);
    $keysearch = Input::post('keysearch');
    ###
    $html_stock = "";
    $cond = "`is_trash`=0 and `stock_type`='{$stock_type}'";
    $cond.= " and `project_id`='{$project_id}'";
    if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
        $block_id = (int) Input::post('block_id', 0);
        $building_id = (int) Input::post('building_id', 0);
        $cond.= "and `block_id`='{$block_id}'";
    }
    $cond.= " and (`status_id`<>'"._STOCK_STATUS_NON_ID."')";
    $sql_string = ""; $arr_not_ins = array();
    if($holderG == 'stock'){
        $sql_string.= " and `block_id`='{$block_id}'";
        if($clsISO->checkContainer($keysearch,'X','')){
            $key2search = str_replace('X', 'XX', $keysearch);
            $cond.= " AND (`ms_code` like '%".str_replace('X','_',$keysearch)."' 
				or `ms_code` like '%".str_replace('X','_',$key2search)."'
			)";
        } else {
            $cond.= " and `ms_code` like '%{$keysearch}%'"; // and `agency_id`>0
        }
    } else {
        $cond.= " and `ms_code` like '%{$keysearch}%'"; // and `agency_id`>0
    }
    if($holderG == "project") {
        $sql_string.= " and `user_id`='".$core->_USER["user_id"]."'";
    }

    $tmp = $clsStockShape->getByCond("`holderG`='{$holderG}' and `project_id`='{$project_id}'".$sql_string);
    if($tmp){
        $shapes = $tmp['shapes'];
        $shapes = $clsISO->to_array_json($shapes);
        if(!empty($shapes)){
            foreach($shapes as $key => $val){
                if(!empty($val['stock_id']) && !in_array($val['stock_id'], $arr_not_ins)){
                    $arr_not_ins[] = $val['stock_id'];
                }
            }
        }
        unset($tmp);
    }
    if(!empty($arr_not_ins)) {
        $cond.= " and `{$clsStock->pkey}` not in (".implode(',', $arr_not_ins).")";
    }
    // $dbconn->debug = true;
    $field = "{$clsStock->pkey},`ms_code`";
    $list_stocks = $clsStock->getAll($cond." limit 0,20", $field);
    // $clsISO->print_pre($list_stocks); die();
    if(!empty($list_stocks)){
        $html_stock .= '<ul class="list-unstyled">';
        foreach($list_stocks as $key => $val){
            $html_stock .= '<li>
				<a href="javascript:void(0);" onClick="$Core.project.select_stock(this, event)" 
					stock_id="'.$val[$clsStock->pkey].'" uid="'.$uid.'">'.$val['ms_code'].'</a>
			</li>';
        }
        $html_stock .= '</ul>';
    } else {
        $html_stock = '';
    }
    // Return
    echo json_encode(array(
        'html' => $html_stock
    )); die();
}
function default_loadModalStock(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$smarty;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $uid = $clsISO->getUniqid();
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $holderG = Input::post('holderG', 'stock');
    ###
    $html_stock = "";
    $cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
    $cond.= " and `project_id`='{$project_id}' and `block_id`='{$block_id}'";
    $cond.= " and (`status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
    $cond.= " and `agency_id`<>'0'";
    ###
    $oneProject = $clsProject->getOne($project_id,"more_information");
    $more_information = $clsISO->to_array_json($oneProject['more_information']);
    $arr_not_ins = array();
    $cond = "`holderG`='".$holderG."' 
		and `project_id`='{$project_id}'";
    if(isset($more_information['has_block']) && $more_information['has_block'] == 1 && $holderG != "project") {
        $cond .= "  and `block_id`='{$block_id}' ";
    }
    $tmp = $clsStockShape->getByCond($cond);
    if($tmp){
        $shapes = $tmp['shapes'];
        $shapes = $clsISO->to_array_json($shapes);
        if(!empty($shapes)){
            foreach($shapes as $key => $val){
                if(!empty($val['stock_id']) && !in_array($val['stock_id'], $arr_not_ins)){
                    $arr_not_ins[] = $val['stock_id'];
                }
            }

        }
        unset($tmp);
    }
    if(!empty($arr_not_ins))
        $cond.= " and `{$clsStock->pkey}` not in (".implode(',', $arr_not_ins).")";
    // $dbconn->debug = true;
    $field = "{$clsStock->pkey},`ms_code`";
//	$clsStock->setDeBug(1);
    $list_stocks = $clsStock->getAll($cond, $field);
//	$clsISO->print_pre($list_stocks);die;
    $smarty->assign("uid",$uid);
    $smarty->assign("list_stocks",$list_stocks);
    $html = $core->build('_ajax.load_modal_stock.tpl');
    // Return
    echo json_encode(array(
        'html' => $html,
        'uid'	=> $uid,
        "total" 	=>	!empty($list_stocks) ? count($list_stocks) : 0
    )); die();
}
function default_listStock(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$smarty;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $uid = $clsISO->getUniqid();
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $holderG = Input::post('holderG', "stock");

    $oneProject = $clsProject->getOne($project_id,"more_information");
    $more_information = $clsISO->to_array_json($oneProject['more_information']);
    $cond_project = "";
    /*if(isset($more_information['has_block']) && $more_information['has_block'] == 1 && $holderG != "project") {
		$cond_project .= "  and `block_id`='{$block_id}' ";
	}*/

    ###
    $html_stock = "";
    $cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
    $cond.= " and `project_id`='{$project_id}'";
//	$cond .= $cond_project;

    if(!empty($block_id)) {
        $cond .= "  and `block_id`='{$block_id}' ";
    }
    /*if($project_id != 10 && !in_array($block_id,_BLOCK_NOT_SEARCH)) {
		$cond.= "AND `status_id`<>'"._STOCK_STATUS_NON_ID."'";
		$cond.= " AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `agency_id`<>'0' and`status_id`>0";
	}*/


    if($holderG == "project") {
//		$cond_project .= "  and `user_id`='{$core->_USER["user_id"]}' ";
    }
    ###
    $arr_not_ins = array();
//	$clsStockShape->setDeBug(1);
    $lstShape = $clsStockShape->getAll("`holderG`='".$holderG."' 
		and `project_id`='{$project_id}'".$cond_project);
//	$clsISO->print_pre($lstShape);die;
    if(!empty($lstShape)){
        foreach ($lstShape as $k => $shape) {
            $shapes = $shape['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            if(!empty($shapes)){
                foreach($shapes as $key => $val){
                    if(!empty($val['stock_id']) && !in_array($val['stock_id'], $arr_not_ins)){
                        $arr_not_ins[] = $val['stock_id'];
                    }
                }
            }
        }
    }
    if(!empty($arr_not_ins))
        $cond.= " and `{$clsStock->pkey}` not in (".implode(',', $arr_not_ins).")";
//	 $dbconn->debug = true;
    $field = "{$clsStock->pkey},`ms_code`,`more_information`";
//	$clsStock->setDeBug(1);
    $total = $clsStock->countItem($cond);
    $list_stocks = $clsStock->getAll($cond."ORDER BY `stock_id` ASC", $field);
//	$clsISO->print_pre($list_stocks);die;
    foreach ($list_stocks as $key => $val) {
        $moreInformation = $clsISO->to_array_json($val['more_information']);
        $list_stocks[$key]["more_information"] = $moreInformation;
    }
//	$clsISO->print_pre($list_stocks);die;
    $smarty->assign("uid",$uid);
    $smarty->assign("list_stocks",$list_stocks);
    $html = $core->build('_ajax.load_list_stock.tpl');
    // Return
    echo json_encode(array(
        'html' => $html,
        'uid'	=> $uid,
        "total" 	=>	$total
    )); die();
}
function default_open_map_config(){
    global $smarty,$mod,$act,$core,$dbconn,$clsISO,$smarty;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $msg = "_error";
    $uid = $clsISO->getUniqid();
    $stock_type = (int) Input::post('stock_type', 0);
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $type = Input::post('type', "");
    ###
    $field = "{$clsProperty->pkey},title";
    $list_buildings = $clsProperty->getAll("`is_trash`=0 and property_type='_BUILDING' and for_id='{$block_id}'", $field);
    $smarty->assign('list_buildings', $list_buildings);
    ###
    $stock_shape_id = 0;
    $map_configs = array();
    $tmp = $clsStockShape->getByCond("`stock_type`='{$stock_type}' and `project_id`='{$project_id}' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}'". (($type == "stock_FH") ? " AND `holderG`='stock_FH'" : " AND `holderG`<>'stock_FH'"));
    if(!empty($tmp)){
        $stock_shape_id = $tmp[$clsStockShape->pkey];
        $map_configs = $tmp['map_configs'];
        $map_configs = $clsISO->to_array_json($map_configs);
        $list_building_arrs = $core->get_field($map_configs, "list_building_id", []);
    } else {
        $list_building_arrs = array();
    }
    $smarty->assign('stock_shape_id', $stock_shape_id);
    $smarty->assign('map_configs', $map_configs);
    $smarty->assign('list_building_arrs', $list_building_arrs);
    // Return
    $html = $core->build('_ajax.config.tpl');
    echo json_encode(array(
        'html' => $html
    )); die();
}
function default_map_save_config(){
    global $smarty,$mod,$act,$core,$dbconn,$clsISO,$smarty;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $msg = "_error";
    $stock_shape_id = (int) Input::post('stock_shape_id', 0);
    $list_building_id = Input::post('list_building_id', []);
    $map_configs = $clsStockShape->getOneField('map_configs', $stock_shape_id);
    $map_configs = $clsISO->to_array_json($map_configs);
    // $clsISO->print_pre($map_configs); die();
    $map_configs['min_zoom'] = Input::post('min_zoom');
    $map_configs['max_zoom'] = Input::post('max_zoom');
    $map_configs['show_tooltip'] = Input::post('show_tooltip', 0);
    $map_configs['is_all_block'] = Input::post('is_all_block', 0);
    $map_configs['list_building_id'] = $list_building_id;
    $map_configs['list_building_slash'] = $clsISO->makeSlashListFromArray($list_building_id);
    $map_configs['enable_tooltip_position'] = Input::post('enable_tooltip_position', 0);
    if($clsStockShape->updateOne($stock_shape_id, array(
        'map_configs' => json_encode($map_configs, JSON_UNESCAPED_UNICODE)
    ))){
        $msg = "_success";
    }
    // Return
    echo $msg; die();
}
function default_open_setup_tooltip(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$smarty;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockShape = new StockShape();
    ###
    $uid = $clsISO->getUniqid();
    $smarty->assign('uid', $uid);
    $stock_type = (int) Input::post('stock_type', 0);
    $project_id = (int) Input::post('project_id', 0);
    $block_id = (int) Input::post('block_id', 0);
    $building_id = (int) Input::post('building_id', 0);
    $type = Input::post('type', "");
    $more_information = $clsProperty->getOneField('more_information', $building_id);
    $more_information = $clsISO->to_array_json($more_information);
    if($type == "stock_FH") {
        $image_map_src = isset($more_information['layout_map_FH'])
            ? sprintf('%s?v=%s', $more_information['layout_map_FH'], time()) : "";
    }else{
        $image_map_src = isset($more_information['layout_map'])
            ? sprintf('%s?v=%s', $more_information['layout_map'], time()) : "";
    }

    $_getType = $type=="stock_FH" ? "&type=draw_map_FH" : "";
    ###
    $callback = 'var _map = L.map(\'map_canvas_'.$uid.'\', {
		crs: L.CRS.Simple,
		attributionControl: false,
		boxZoom: false,
		doubleClickZoom: true,
		dragging: true,
		keyboard: false,
		maxBoundsViscosity: 1.0,
		maxZoom: 5.2,
		minZoom: -2.2,
		scrollWheelZoom: true,
		tap: true,
		touchZoom: true,
		zoomControl: true,
		zoomSnap: 0
	});
	// Thêm lớp công cụ vẽ vào bản đồ
	var _drawnItems = new L.FeatureGroup();
	_map.addLayer(_drawnItems);
	// Thêm ảnh nền
	var _img = new Image(), 
		imageUrl = \''.$image_map_src.'\';
	_img.src = imageUrl;
	_img.onload = () => {
		var natWidth = _img.naturalWidth,
			natHeight = _img.naturalHeight,
			imageBounds = [[0,0], [natHeight, natWidth]],
			maxBounds = [[-1500,-1500], [natHeight+1500, natWidth+1500]]; 
		L.imageOverlay(imageUrl, imageBounds).addTo(_map);
		// Cố định bản đồ vào đúng vùng ảnh
		_map.fitBounds(imageBounds);
		// Giới hạn di chuyển bản đồ trong phạm vi ảnh
		_map.setMaxBounds(maxBounds); 
		// Vẽ shapes
		get_shapes(\''.$stock_type.'\', \''.$project_id.'\', 
			\''.$block_id.'\',\''.$building_id.'\', _map, _drawnItems);
		_map.on("zoom", updateTooltipScale);
		_map.on("load", updateTooltipScale);
	}
	async function updateTooltipScale() {
		var zoom = _map.getZoom();
		var scale = Math.pow(2, zoom);
		console.log(scale);
		$(".box_tooltip").css("transform", `scale(${scale})`);
	}
	async function get_shapes(stock_type, project_id, block_id, building_id, _map, _drawnItems){
		try {
			let response = await fetch(`/admin/index.php?mod=${mod}
				&act=get_shapes&stock_type=${stock_type}&project_id=${project_id}
				&block_id=${block_id}&building_id=${building_id}'.$_getType.'`),
				respJson = await response.json();
			if(respJson.msg.indexOf(\'_success\') >= 0){
				_drawnItems.clearLayers();
				$Core.project.shapes = respJson.shapes;
				$.each(respJson.shapes, (i, shape) => {
					if(shape.html_shapes != ""){
						let _layer = null;
						if (shape.shape_type === \'polygon\') {
							_layer = L.polygon(shape.coordinates,{weight:1});
						} else if (shape.shape_type === \'rectangle\') {
							_layer = L.rectangle(shape.coordinates,{weight:1});
						} 
						_layer.addTo(_map);
						let _latlng = _layer.getBounds().getCenter();
						if(typeof(shape.latlng) !== \'undefined\'){
							_latlng = shape.latlng;
						}
						
						// 2. Tạo một div tạm để đo kích thước
						const $temp = $(\'<div>\').css({
						  position: \'absolute\',
						  visibility: \'hidden\'
						}).addClass(\'box_tooltip\').html(`${shape.html_shapes}`).appendTo(\'body\');
						const width = $temp.outerWidth();
						const height = $temp.outerHeight();
						$temp.remove(); // Xoá sau khi lấy kích thước
						console.log(width,height);
						// 3. Tạo marker dùng DivIcon
						const icon = L.divIcon({
						  className: \'transparent-drag-marker\', // không chứa gì, chỉ làm vùng drag
						  iconSize: [width, height],
						  iconAnchor: [width / 2, height / 2]
						});
						
						var _direction = \'top\',
							_marker = L.marker(_latlng, {
							draggable: true,
							zIndexOffset: 1000,
							opacity: 0 // Ẩn marker
						}).addTo(_map);
						if(typeof(shape.direction) !== \'undefined\'){
							_direction = shape.direction;
						}
						// 5. Tạo tooltip gắn vào overlayPane
						const $tooltipDiv = $(\'<div class="box_tooltip">\').html(`${shape.html_shapes}`).appendTo(_map.getPanes().overlayPane);
						function updateTooltipPosition(latlng) {
						  const point = _map.latLngToLayerPoint(latlng);
    						L.DomUtil.setPosition($tooltipDiv[0], point.subtract(L.point(width/2, height/2)));
							$.post(`${path_ajax_script}/index.php?mod=${mod}&act=update_pos`, {
								 \'stock_shape_id\' : respJson.stock_shape_id,
								 \'shape_id\' : shape.shape_id,
								 \'stock_id\' : shape.stock_id,
								 \'latlng\' : JSON.stringify(latlng)
							}, (html) => {
								console.log(html);
							});
						}
						updateTooltipPosition(_latlng);
						// 6. Cập nhật vị trí khi drag
						_marker.on(\'drag\', function (e) {
						  updateTooltipPosition(e.latlng);
						});
						// 7. Cập nhật lại vị trí khi zoom/move
						_map.on(\'zoom viewreset move\', function () {
						  updateTooltipPosition(_marker.getLatLng());
						});
						/*_marker.bindTooltip(`${shape.html_shapes}`, {
							permanent: true, 
							direction: _direction, 
							interactive: true,
							className : "box_tool_tip"
						}).openTooltip();						
						
						_marker.on("dragend", function (e) {
							var latlng = e.target.getLatLng();
							_marker.setLatLng(latlng);
							$.post(`${path_ajax_script}/index.php?mod=${mod}&act=update_pos`, {
								 \'stock_shape_id\' : respJson.stock_shape_id,
								 \'shape_id\' : shape.shape_id,
								 \'stock_id\' : shape.stock_id,
								 \'latlng\' : JSON.stringify(latlng)
							}, (html) => {
								console.log(html);
							});
						});*/
						function getCentroid(latlngs) {
							let latSum = 0,
								lngSum = 0,
								count = latlngs.length;
							latlngs.forEach(latlng => {
								latSum += latlng.lat;
								lngSum += latlng.lng;
							});
							return L.latLng(latSum / count, lngSum / count);
						}
						var centroid = getCentroid(_layer.getLatLngs()[0]),
							polyline = L.polyline([centroid, _latlng], { 
							color: \'yellow\', 
							weight: 3
						}).addTo(_map);
						_drawnItems.addLayer(_layer);
						
					}
					
				});
			}
		} catch (err) {
			console.error("Lỗi khi load hình:", err);
		}
	}';
    $smarty->assign("uid",$uid);
    $smarty->assign("image_map_src",$image_map_src);
    $smarty->assign("stock_type",$stock_type);
    $smarty->assign("project_id",$project_id);
    $smarty->assign("block_id",$block_id);
    $smarty->assign("building_id",$building_id);
//	$callback = $core->build("_ajax.callback_mappos.tpl");
    // Return
    $html = $core->build('_ajax.mappos.tpl');
    echo json_encode(array(
        'html' => $html,
        'callback' => $callback
    )); die();
}
function default_draw_map_FH(){
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
        $link = sprintf('index.php?mod=%s&act=%s&stock_type=%s&project_id=%s&block_id=%s',
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
    }
    if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && $block_id > 0){
        $field = "{$clsProperty->pkey},title";
        $list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BUILDING' 
			and for_id='{$block_id}' order by `order_no` ASC", $field);
    }
    $image_map_src = "";
    if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
        if($project_id > 0 && $block_id > 0){
            $more_information = $clsProperty->getOneField('more_information', $block_id);
            $more_information = $clsISO->to_array_json($more_information);
            $image_map_src = isset($more_information['layout_ms']) ? $more_information['layout_ms'] : "";
        } else {
            $more_information = $clsProject->getOneField('more_information', $project_id);
            $more_information = $clsISO->to_array_json($more_information);
            $image_map_src = isset($more_information['layout']) ? $more_information['layout'] : "";
        }
    } else {
        $more_information = $clsProperty->getOneField('more_information', $building_id);
        // $clsISO->print_pre($more_information); die();
        $more_information = $clsISO->to_array_json($more_information);
        $image_map_src = isset($more_information['layout_map_FH']) ? $more_information['layout_map_FH'] : "";
    }
    $map_configs = array();
    if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
        $map_configs['min_zoom'] = -1;
        $map_configs['max_zoom'] = 10;
    }
    $tmp = $clsStockShape->getByCond("`stock_type`='{$stock_type}' and `project_id`='{$project_id}' 
		and `block_id`='{$block_id}' and `building_id`='{$building_id}'");
    if(!empty($tmp)){
        $map_configs = $tmp['map_configs'];
        $map_configs = $clsISO->to_array_json($map_configs);
        if(isset($map_configs['status']) && $map_configs['status'] == '_draw'){
            $map_configs['min_zoom'] = -1;
            $map_configs['max_zoom'] = 10;
        }
    } else {
        $map_configs['status'] = '_draw';
        $map_configs['min_zoom'] = -2.5;
        $map_configs['max_zoom'] = -2.5;
    }
    // $clsISO->print_pre($map_configs); die();
    $assign_list['map_configs'] = $map_configs;
    ###
    $assign_list['list_blocks'] = $list_blocks;
    $assign_list['list_buildings'] = $list_buildings;
    $assign_list['more_information'] = $more_information;
    $assign_list['image_map_src'] = $image_map_src;
}
function default_get_pop_FH(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    $clsStockShape = new StockShape();
    $clsStock = new Stock();
    ###
    $uid = $clsISO->getUniqid();
    $shape_id = Input::get('shape_id', 0);
    $_leaflet_id = Input::get('_leaflet_id', 0);
    $holderG = "stock_FH";
    $stock_type = (int) Input::get('stock_type', 0);
    $project_id = (int) Input::get('project_id', 0);
    $block_id = (int) Input::get('block_id', 0);
    $building_id = (int) Input::get('building_id', 0);
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
                if($val['shape_id'] == $shape_id){
                    $_oShape = $val;
                    $has_data = 1;
                }else if(!empty($val['code'])){
                    $arr_code[] = $val['code'];
                }

            }
        }
    }
//	 $clsISO->print_pre($arr_code); die();
    if($_oShape["stock_id"]==0 && ($project_id == _PROJECT_VWC_ID || in_array($block_id,_BLOCK_NOT_SEARCH))) {
        $oneProject = $clsProject->getOne($project_id,"more_information");
        $more_information = $clsISO->to_array_json($oneProject['more_information']);
        $cond_project = "";
        if(isset($more_information['has_block']) && $more_information['has_block'] == 1) {
            $cond_project .= "  and `block_id`='{$block_id}' ";
        }
        ###
        $html_stock = "";
        $cond = "`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'";
        $cond.= " and `project_id`='{$project_id}'";
        $cond .= $cond_project;
        if($project_id != 10 && !in_array($block_id,_BLOCK_NOT_SEARCH)) {
            $cond.= " and (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
            $cond.= " and `agency_id`<>'0'";
        }
        if(in_array($block_id,_BLOCK_NOT_ĐLBM)) {
            $cond .= " AND `ms_code` NOT LIKE 'ĐLBM-%'";
        }
        $cond .= " AND `ms_code` NOT LIKE 'ĐLHD-%'";
        ###
        $arr_not_ins = array();
        $tmp = $clsStockShape->getByCond("`holderG`='stock' 
			and `project_id`='{$project_id}'".$cond_project);
        if($tmp){
            $shapes = $tmp['shapes'];
            $shapes = $clsISO->to_array_json($shapes);
            if(!empty($shapes)){
                foreach($shapes as $key => $val){
                    if(!empty($val['stock_id']) && !in_array($val['stock_id'], $arr_not_ins)){
                        $arr_not_ins[] = $val['stock_id'];
                    }
                }
            }
            unset($tmp);
        }
        if(!empty($arr_not_ins))
            $cond.= " and `{$clsStock->pkey}` not in (".implode(',', $arr_not_ins).")";
        $field = "{$clsStock->pkey},`ms_code`,`more_information`";
        $oneStock = $clsStock->getByCond($cond, $field);
        $_oShape["stock_id"] = $oneStock[$clsStock->pkey];
        $_oShape["stock_code"] = $oneStock['ms_code'];
        $_oShape["code"] = $oneStock['code'];
    }
    //	var_dump($_oShape);
    $html_code = "";
    //	var_dump($arr_code);die;
    for($i=1; $i <= (int)$more_information['number_house']; $i++) {
        $code = $clsStock->getCode($i);
        if(!in_array((string)$code,$arr_code)) {
            $html_code .= '<option'.(($_oShape['code']==(string)$code) ? ' selected' : '').' value="'.$code.'">'.$code.'</option>';
        }
    }
    ###
    $html = '<div class="d-flex align-items-center gap-2 mb-3">
		<button type="button" title="Sửa" class="btn flex-fill btn-sm btn-default 
		js__edit_shape_'.$shape_id.'" shape_id="'.$shape_id.'" _leaflet_id="'.$_leaflet_id.'" onclick=\'edit_shape(this, event)\'><i class="fa fa-pencil"></i> Sửa</button>
		<button type="button" title="Xoá" class="btn flex-fill btn-sm btn-default js__delete_shape  
		js__delete_shape_'.$shape_id.'" shape_id="'.$shape_id.'" _leaflet_id="'.$_leaflet_id.'" onclick=\'delete_shape(this, event)\'><i class="fa fa-trash"></i>️ Xóa</button>
		<button type="button" title="Xoá" class="btn flex-fill btn-sm btn-default js__copy_shape 
		js__copy_shape_'.$shape_id.'" shape_id="'.$shape_id.'" holderG="'.$holderG.'" _leaflet_id="'.$_leaflet_id.'" onclick=\'copy_shape(this, event)\'><i class="fa fa-copy"></i>️ Nhân bản</button>
	</div>
	<hr class="my-3" />
	<div class="form-group mb-2">
		<label class="form-label mb-1">Căn số:</label>
		<select class="form-control" name="code" >
			'.$html_code.'
		</select>
	</div>
	<div class="form-group">
		<label class="form-label mb-1">Vị trí:</label>
		<select class="form-control" name="direction" uid="'.$uid.'" shape_id="'.$shape_id.'" project_id="'.$project_id.'" block_id="'.$block_id.'" building_id="'.$building_id.'" stock_type="'.$stock_type.'">
			<option'.($_oShape['direction']=='right' ? ' selected' : '').' value="right">Trái</option>
			<option'.($_oShape['direction']=='left' ? ' selected' : '').' value="left">Phải</option>
			<option'.($_oShape['direction']=='bottom' ? ' selected' : '').' value="bottom">Trên</option>
			<option'.($_oShape['direction']=='top' ? ' selected' : '').' value="top">Dưới</option>
		</select>
	</div>
	<div class="form-group">
		<button type="button" holderG="'.$holderG.'" _leaflet_id="'.$_leaflet_id.'" project_id="'.$project_id.'" block_id="'.$block_id.'" shape_id="'.$shape_id.'" uid="'.$uid.'" onclick="add_'.$holderG.'(this, event)" class="btn btn-block btn-success js__btn_add_'.$holderG.'">Lưu</button>
	</div>';
    // Return
    echo $html; die();
}
function default_sync_price_sheet(){
    global $assign_list,$mod,$act,$core,$dbconn,$clsISO;
    $clsProject = new Project();
    $clsProperty = new Property();
    // Tải thư viện
    require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
    $client->setScopes([Google_Service_Drive::DRIVE, Google_Service_Sheets::SPREADSHEETS]);
    // Khởi tạo dịch vụ Google Drive và Sheets
    $drive = new \Google_Service_Drive($client);
    $service = new \Google_Service_Sheets($client);

    $msg = "_error";
    $spreadsheetId = '1h9JMNpV-WzNXPA2WXOfQd87FwWJuEbewG_YlihWZ7so';
    $field = "{$clsProject->pkey},title,code";
    $list_projects = $clsProject->getAll("`is_trash`=0", $field);
    if(!empty($list_projects)){
        $values = array();
        //$values[] = array('Tên', 'Tên viết tắt', 'Ký hiệu', 'Danh mục', 'Loại');
        foreach($list_projects as $val){
            $project_id = $val[$clsProject->pkey];
            $field = "{$clsProperty->pkey},`title`,`title_vn`,`property_code`,`parent_id`";
            $list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
            if(!empty($list_blocks)){
                foreach($list_blocks as $okey => $oval){
                    $property_type = '_RANGE';
                    $stock_type = $oval['parent_id'];
                    $block_id = $oval[$clsProperty->pkey];
                    $field = "{$clsProperty->pkey},`title`,`title_vn`,`property_code`";
                    if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
                        $property_type = '_BUILDING';
                    }
                    $values[] = array(sprintf('%s',$oval['title']), '', $oval['property_code'], "", 'Phân khu',  ($property_type=='_BUILDING'?'cao_tang':'thap_tang'));
                    $list_buildings = $clsProperty->getAll("`is_trash`=0 and `property_type`='{$property_type}' 
						and `for_id`='{$block_id}'", $field);
                    if(!empty($list_buildings)){
                        $prefix = ($property_type=='_BUILDING') ? 'Toà' : 'Dãy';
                        foreach($list_buildings as $mkey => $mval){
                            $values[] = array(sprintf('%s',$mval['title']), $mval['title_vn'], $mval['property_code'], $oval['title'], $prefix, ($property_type=='_BUILDING'?'cao_tang':'thap_tang'));
                        }
                    }
                }
            }
        }
        if(!empty($values)){
            // $clsISO->print_pre($values); die();
            $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
            $range = 'Danh sách';
            if($service->spreadsheets_values->append($spreadsheetId, $range, $body, array(
                'valueInputOption' => 'RAW'
            ))){
                $msg = "_success";
            }
        }
    }
    // Return
    echo $msg; die();
}
function default_open_sop(){
    global $smarty, $_CONFIG,  $_SITE_ROOT, $mod, $act, $clsISO;
    global $core, $clsModule,$oneSetting;
    $user_id = $core->_USER['user_id'];
    $clsProject = new Project();
    $clsProjectSop = new ProjectSop();
    ##
    $sop_id = (int) Input::post('sop_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $titlePage= "Cập nhật";
    if($sop_id==0){
        $titlePage = "Thêm mới";
        $sop_id = $clsProjectSop->getMaxId();
        $more_information = array(
            'field_type' => '_textarea',
            'template_type' => '_tab',
            'title' => '',
            'content' => '',
            'image' => '',
            'position' => '',
            'is_image' => 0,
            'is_background' => 0,
        );
        $clsProjectSop->insert(array(
            $clsProjectSop->pkey => $sop_id,
            'project_id' => $project_id,
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
            'order_no' => $clsProjectSop->getMaxOrderNo(),
            'user_id' => $user_id,
            'is_trash' => 1
        ));
    }
    $oneItem = $clsProjectSop->getOne($sop_id);
    $more_information = $oneItem['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $oneItem = array_merge($oneItem, $more_information);
    // $clsISO->print_pre($oneItem); die();
    $smarty->assign('sop_id', $sop_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('oneItem', $oneItem);
    $smarty->assign('titlePage', $titlePage);
    // Return
    $smarty->assign('core', $core);
    $smarty->assign('template', '_sop');
    $html = $core->build('_ajax.sop.tpl');
    echo $html; die();
}
function default_pop_save_sop(){
    global $assign_list, $_CONFIG, $dbconn, $_SITE_ROOT, $mod , $_LANG_ID, $act,$oneSetting;
    global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
    $clsProject = new Project();
    $clsProjectSop = new ProjectSop();
    $sop_id = Input::post('sop_id',0);
    $project_id = Input::post('project_id',0);
    ##
    $msg = '_error';
    if($sop_id > 0){
        $oneSop = $clsProjectSop->getOne($sop_id);
        $more_information = $oneSop['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $title = Input::post('title');
        $slug = $core->replaceSpace($title);
        $more_information['title'] = $title;
        $more_information['slug'] = $slug;
        $more_information['field_type'] = Input::post('field_type');
        $more_information['template_type'] = Input::post('template_type', '_tab');
        $more_information['is_background'] = Input::post('is_background', 0);
        if($clsProjectSop->updateOne($sop_id, array(
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = '_success';
        }
    }
    // Return
    echo $msg; die();
}
function default_open_data_picker(){
    global $smarty, $core, $clsISO;
    $clsProject = new Project();
    $clsProjectSopItem = new ProjectSopItem();
    $sop_id = (int) Input::post('sop_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    // Danh sách tiện ích của dự án (cột utilities)
    $utilities = $clsISO->to_array_json($clsProject->getOneField('utilities', $project_id));
    // Pre-check theo TIÊU ĐỀ: tiện ích được tick nếu sop đã có item cùng tiêu đề
    $existing_titles = array();
    $items = $clsProjectSopItem->getAll("`sop_id`='{$sop_id}' AND `is_trash`=0");
    if(!empty($items)){
        foreach($items as $it){
            if(isset($it['title']) && $it['title'] !== '') $existing_titles[$it['title']] = 1;
        }
    }
    $checked = array();
    if(!empty($utilities) && is_array($utilities)){
        foreach($utilities as $uid => $u){
            if(isset($u['title']) && isset($existing_titles[$u['title']])) $checked[$uid] = 1;
        }
    }
    $smarty->assign('sop_id', $sop_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('utilities', $utilities);
    $smarty->assign('checked', $checked);
    $smarty->assign('core', $core);
    $html = $core->build('_ajax.data_picker.tpl');
    echo $html; die();
}
function default_save_data_items(){
    global $core, $clsISO;
    $user_id = $core->_USER['user_id'];
    $clsProject = new Project();
    $clsProjectSopItem = new ProjectSopItem();
    $sop_id = (int) Input::post('sop_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $utilities_ids = Input::post('utilities_ids', array());
    $msg = '_error';
    if($sop_id > 0){
        $utilities = $clsISO->to_array_json($clsProject->getOneField('utilities', $project_id));
        // Tập tiêu đề thuộc danh sách tiện ích (để nhận diện item cũ cần xoá)
        $util_titles = array();
        if(!empty($utilities) && is_array($utilities)){
            foreach($utilities as $u){
                if(isset($u['title']) && $u['title'] !== '') $util_titles[$u['title']] = 1;
            }
        }
        // Chỉ xoá item cũ THUỘC danh sách tiện ích (match theo tiêu đề), giữ nguyên item khác
        $old_items = $clsProjectSopItem->getAll("`sop_id`='{$sop_id}'");
        if(!empty($old_items)){
            foreach($old_items as $it){
                if(isset($util_titles[$it['title']])){
                    $clsProjectSopItem->deleteOne($it[$clsProjectSopItem->pkey]);
                }
            }
        }
        if(!empty($utilities_ids) && is_array($utilities_ids)){
            $item_id = $clsProjectSopItem->getMaxId();
            $order_no = $clsProjectSopItem->getMaxOrderNo();
            foreach($utilities_ids as $uid){
                if(!isset($utilities[$uid])) continue;
                $u = $utilities[$uid];
                $title = isset($u['title']) ? $u['title'] : '';
                $icon = isset($u['icon']) ? trim($u['icon']) : '';
                $image = isset($u['image']) ? $u['image'] : '';
                $use_icon = ($icon !== '') ? 1 : 0;
                $more_information = array(
                    'title' => $title,
                    'content' => isset($u['content']) ? $u['content'] : '',
                    'image' => $use_icon ? $icon : $image,
                    'position' => 'left',
                    'is_icon' => $use_icon,
                    'data_source' => 'utilities',
                    'data_key' => $uid,
                );
                $clsProjectSopItem->insert(array(
                    $clsProjectSopItem->pkey => $item_id,
                    'sop_id' => $sop_id,
                    'order_no' => $order_no,
                    'title' => $title,
                    'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
                    'is_trash' => 0,
                    'user_id' => $user_id,
                ));
                $item_id++;
                $order_no++;
            }
        }
        $msg = '_success';
    }
    echo $msg; die();
}
function default_move_sop(){
    global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$oneSetting;
    global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO,$dbconn;
    $clsProject = new Project();
    $clsProjectSop = new ProjectSop();
    $sop_id = Input::post('sop_id',0);
    $project_id = Input::post('project_id',0);
    $direct = Input::post('direct',"");
    ##
    $msg = '_error';
    if($sop_id > 0){
        $oneSop = $clsProjectSop->getOne($sop_id);
        $order_no = $oneSop['order_no'];
        echo $order_no."----";
        $where = "`project_id`='{$project_id}'";
        if($direct=='up'){
            $lst = $clsProjectSop->getAll($where." and order_no < $order_no order by order_no DESC limit 0,1");
            $clsProjectSop->updateOne($sop_id ,"order_no='".$lst[0]['order_no']."'");
            $clsProjectSop->updateOne($lst[0][$clsProjectSop->pkey],"order_no='".$order_no."'");
        }
        if($direct=='down'){
            $lst = $clsProjectSop->getAll($where." and order_no > $order_no order by order_no ASC limit 0,1");
            $clsProjectSop->updateOne($sop_id ,"order_no='".$lst[0]['order_no']."'");
            $clsProjectSop->updateOne($lst[0][$clsProjectSop->pkey],"order_no='".$order_no."'");
        }
    }
    // Return
    echo $msg; die();
}
function default_delete_sop(){
    global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
    global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
    $clsProjectSop = new ProjectSop();
    $sop_id = Input::post('sop_id',0);
    $project_id = Input::post('project_id',0);

    $msg = '_error';
    if($clsProjectSop->deleteOne($sop_id)){
        $msg = '_success';
    }
    // Return
    echo $msg; die();
}
function default_open_sop_item(){
    global $smarty, $_CONFIG,  $_SITE_ROOT, $mod, $act, $clsISO;
    global $core, $clsModule,$oneSetting;
    $user_id = $core->_USER['user_id'];
    $clsProjectSop = new ProjectSop();
    $clsProjectSopItem = new ProjectSopItem();
    $sop_id = (int) Input::post('sop_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    $sop_item_id = (int) Input::post('sop_item_id', 0);
    #
    $titlePage= "Cập nhật";
    if($sop_item_id == 0){
        $titlePage= "Thêm mới";
        $clsProjectSopItem->deleteByCond("`is_trash`=1 and `user_id`='{$user_id}' and `sop_id`='{$sop_id}'");
        $sop_item_id = $clsProjectSopItem->getMaxId();
        $clsProjectSopItem->insert(array(
            $clsProjectSopItem->pkey => $sop_item_id,
            'sop_id' => $sop_id,
            'order_no' => $clsProjectSopItem->getMaxOrderNo(),
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
            'is_trash' => 1,
            'user_id' => $user_id
        ));
    }
    $oneItem = $clsProjectSopItem->getOne($sop_item_id);
    $oneSop = $clsProjectSop->getOne($sop_id);
	if(!empty($oneItem)) {
		$more_information = $clsISO->to_array_json($oneItem["more_information"]);
	}else{
    	$more_information = $oneSop['more_information'];	
    	$more_information = $clsISO->to_array_json($more_information);	
	}	
	$sop_more_information = $clsISO->to_array_json($oneSop['more_information']);
    #
	$more_information["content"] = preg_replace('/<!--(.*?)-->/s', '', $more_information["content"]);
    $smarty->assign('sop_id', $sop_id);
    $smarty->assign('project_id', $project_id);
    $smarty->assign('sop_item_id', $sop_item_id);
    $smarty->assign('oneItem', $oneItem);
    $smarty->assign('more_information', $more_information);
    $smarty->assign('sop_info', $sop_more_information);
    $smarty->assign('oneSop', $oneSop);
    $smarty->assign('titlePage', $titlePage);
    // Return
    $smarty->assign('core', $core);
    $smarty->assign('template', '_sopItem');
    $html = $core->build('_ajax.sop.tpl');
    echo $html; die();
}
function default_save_sop_item(){
    global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$oneSetting;
    global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO,$dbconn;
    $clsProjectSop = new ProjectSop();
    $clsProjectSopItem = new ProjectSopItem();
    $sop_id = Input::post('sop_id',0);
    $project_id = Input::post('project_id',0);
    $sop_item_id = Input::post('sop_item_id',0);
    // Action
    /*if(Input::exists('action','GET')){
		$action = Input::get('action');
		if($action=='_saveorder'){
			$orderNo = Input::post('orderNo');
			for($i=0; $i<count($orderNo); $i++){
				$clsSop->updateOne($orderNo[$i], array(
					'order_no'	=> ($i+1)
				));
			}
			echo(1); die();
		}
	}*/
    // End action
    $msg = '_error';
    if($sop_item_id > 0) {
        $more_information = $clsProjectSopItem->getOneField('more_information', $sop_item_id);
        $more_information = $clsISO->to_array_json($more_information);
        $more_information['title'] = Input::post('title');
        $more_information['content'] = Input::post('content');
        $more_information['image'] = Input::post('image');
        $more_information['is_icon'] = Input::post('is_icon', 0);
        $more_information['position'] = Input::post('position', "left");
        if($clsProjectSopItem->updateOne($sop_item_id, array(
            'is_trash'	=> 0,
            'title'	=> Input::post('title'),
            'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
        ))){
            $msg = '_success';
        }
    }
    // Return
    echo $msg; die();
}
function default_delete_sop_item(){
    global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act,$oneSetting;
    global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
    $clsProjectSop = new ProjectSop();
    $clsProjectSopItem = new ProjectSopItem();
    $sop_id = Input::post('sop_id',0);
    $project_id = Input::post('project_id',0);
    $sop_item_id = Input::post('sop_item_id',0);
    #
    $msg = '_error';
    if($clsProjectSopItem->deleteOne($sop_item_id)){
        $msg = '_success';
    }
    // Return
    echo $msg; die();
}
function default_load_sop_items(){
    global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $oneSetting;
    global $core, $clsModule, $clsButtonNav,$oneSetting, $clsISO;
    $clsProperty = new Property();
    $clsProjectSop = new ProjectSop();
    $clsProjectSopItem = new ProjectSopItem();
    $sop_id = (int) Input::post('sop_id', 0);
    $project_id = (int) Input::post('project_id', 0);
    ##
    //	$more_information = $clsProjectSop->getOneField('more_information', $sop_id);
    //	$more_information = $clsISO->to_array_json($more_information);
    //	$list_items = $core->get_field($more_information, "list_items", []);
    $list_items = $clsProjectSopItem->getAll("`sop_id`='{$sop_id}' ORDER BY `order_no` ASC");
    ##
    $html = '<table class="table table-vertical table-striped TableListSop_'.$sop_id.'" cellpadding="0" cellspacing="0" width="100%">
	<thead style="position: sticky;top: 0;z-index: 2;background: #FFF"><tr>
		<th class="" width="5%"><strong>STT</strong></th>
		<th class="" width="60px"><strong>H.Ảnh</strong></th>
		<th class=""><strong>Tiêu đề</strong></th>
		<th class="" width="10%"><strong>Công cụ</strong></th>
	</tr></thead>';
    if(!empty($list_items)){ $ii=0;// init
        foreach($list_items as $sopItem){
            $sop_item_id = $sopItem[$clsProjectSopItem->pkey];
            $props = 'sop_item_id="'.$sop_item_id.'" sop_id="'.$sop_id.'"  project_id="'.$project_id.'"';
            $editAction = '<button type="button" class="btn btn-default" onClick="$Core.project.open_sop_item(this)" '.$props.'>'.$core->makeIcon('pencil').'</button>';
            $deleteAction = '<button type="button" class="btn btn-danger" onClick="$Core.project.delete_sop_item(this)" '.$props.'>'.$core->makeIcon('trash').'</button>';
            $html .= '<tr id="'.$sop_item_id.'">
				<td class="text-center mySortableHandler">'.$core->makeIcon('arrows').'</td>
				<td class="text-center">
					'.$clsISO->genIMG($sopItem['image'],40,40).'
				</td>
				<td>'.$sopItem['title'].'</td>
				<td class="text-center">
					<div class="btn-group btn-group-xs d-flex">'.$editAction.$deleteAction.'</div>
				</td>
			</tr>';
            ++$ii;
        }
    }else{
        $html .= '<tr>
			<td class="text-center" colspan="4">Chưa có dữ liệu</td>
		</tr>';
    }
    $html.= '</table>';
    // Return
    echo $html; die();
}
function default_open_config_column(){
    // ini_set('display_errors', '1');
    // ini_set('display_startup_errors', '1');
    // error_reporting(E_ALL);
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsMember = new Member();
    $clsProject = new Project();
    $clsStock = new Stock();
    $clsProperty = new Property();
    $clsProjectMeta = new ProjectMeta();
    $smarty->assign('clsMember', $clsMember);
    $smarty->assign('clsProject', $clsProject);
    $smarty->assign('clsProperty', $clsProperty);
    ###
    $project_id = (int) Input::post('project_id', 0);
    $block_type = (int) Input::post('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
    $uid = $clsISO->getUniqid();
    ###
    $html = "";
    $oneProject = $clsProject->getOne($project_id);
    if(!empty($oneProject)) {
        $more_information = $clsISO->to_array_json($oneProject["more_information"]);
        if($block_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
            $title_content = "Cấu hình cột tìm kiếm cao tầng";
            $lst_config_column = !empty($more_information["config_column_highfloor"]) ? $more_information["config_column_highfloor"] : ARRAY_CONFIG_COLUMN_SEARCH_HIGHFLOOR;
        }else {
            $title_content = "Cấu hình cột tìm kiếm thấp tầng";
            $lst_config_column = !empty($more_information["config_column_lowfloor"]) ? $more_information["config_column_lowfloor"] : ARRAY_CONFIG_COLUMN_SEARCH_LOWFLOOR;
        }
        $lstField = $clsStock->getTableField($block_type);
        $arr_field = [];
        foreach ($lst_config_column as $key => $field) {
            $arr_field[$field] = $lstField[$field];
        }

        $smarty->assign('project_id', $project_id);
        $smarty->assign('block_type', $block_type);
        $smarty->assign('uid', $uid);
        $smarty->assign('arr_field', $arr_field);
        $smarty->assign('lstField', $lstField);
        $smarty->assign('title_content', $title_content);
        $html = $core->build('_ajax.config_column.tpl');
    }
    echo json_encode(array(
        'html' => $html,
        'uid' => $uid
    )); die();
}
function default_save_config_column(){
    global $smarty,$core,$_frontIsLoggedin_user_id,$clsISO;
    $clsMember = new Member();
    $clsProject = new Project();
    $clsStock = new Stock();
    $clsProperty = new Property();
    $clsProjectMeta = new ProjectMeta();
    $smarty->assign('clsMember', $clsMember);
    $smarty->assign('clsProject', $clsProject);
    $smarty->assign('clsProperty', $clsProperty);
    ###
    $res = ["result" => false, "msg"	=>	"Lỗi!"];
    $project_id = (int) Input::post('project_id', 0);
    $block_type = (int) Input::post('block_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
    $config_column = Input::post('config_column', array());
    if(!empty($project_id) || !empty($block_type)) {
        $oneProject = $clsProject->getOne($project_id);
        $more_information = $clsISO->to_array_json($oneProject["more_information"]);
        if($block_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
            $more_information["config_column_highfloor"] = !empty($config_column) ? $config_column : ARRAY_CONFIG_COLUMN_SEARCH_HIGHFLOOR;
        }else{
            $more_information["config_column_lowfloor"] = !empty($config_column) ? $config_column : ARRAY_CONFIG_COLUMN_SEARCH_LOWFLOOR;
        }
        if($clsProject->updateOne($project_id,["more_information" => json_encode($more_information,JSON_UNESCAPED_UNICODE)])) {
            $res = ["result" => true, "msg"	=>	"Thành công!"];
        }
    }
    echo json_encode($res); die();
}