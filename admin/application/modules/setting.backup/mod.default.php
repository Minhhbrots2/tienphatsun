<?php
function default_setting(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	$group = Input::get('group','general');
	$agency_id = (int)Input::get('agency',0);
	$assign_list["agency_id"] = $agency_id;  
	$assign_list["group"] = $group;  
	$lstSetting_Type = array();
	$lstSetting_Type['_PROJECT'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh sách dự án',
		'description'	=> 'Mapping dự án'
	);
	$lstSetting_Type['_TYPE_STOCK_LOCK'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại lock căn',
		'description'	=> 'Khóa căn theo loại'
	);
	$lstSetting_Type['_CATEGORYFAQS_MHNC'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Danh mục FAQ',
		'description'	=> 'FAQ'
	);
	$lstSetting_Type['_DOCS_FILE_TYPE'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại tài liệu',
		'description'	=> 'Loại tài liệu'
	);
	$lstSetting_Type['_ZALO_GROUP_TYPE'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại nhóm Zalo',
		'description'	=> 'Loại nhóm Zalo'
	);
	$lstSetting_Type['_LIST_FORM_BUSINESS'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại hình kinh doanh',
		'description'	=> 'Loại hình kinh doanh'
	);
	$lstSetting_Type['_TYPE_BOOKING'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Loại Booking',
		'description'	=> 'Loại Booking'
	);
	$lstSetting_Type['_PACKAGE_DATA'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Gói data khách hàng',
		'description'	=> 'Gói data khách hàng'
	);
	$lstSetting_Type['_CHANEL_ADS'] = array(
		'icon'			=> 1,
		'image'			=> 0,
		'color'			=> 0,
		'name'			=> 'Kênh truyền thông',
		'description'	=> 'Kênh truyền thông'
	);
	$assign_list["lstSetting_Type"] = $lstSetting_Type;  
}
function default_load_block(){
	global $assign_list,$_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$project_id = (int) Input::post('project_id', 0);
	###
	$field = "{$clsProperty->pkey},`title`";
	$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
	
	$html_options = '<option value="0">Chọn phân khu</option>';
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html_options.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';
		}
	}
	// Return
	echo $html_options; die();
}
function default_open_setting(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsSetting',$clsSetting);
	#
	$action = '_form';
	$toId = Input::post('toId');
	$for_id = Input::post('for_id', 0);
	$_reload = Input::post('_reload',0);
	$parent_id = Input::post('parent_id', 0);
	$setting_type = Input::post('setting_type');
	$setting_id = (int) Input::post('setting_id',0);
	#
	$smarty->assign('toId',$toId);
	$smarty->assign('action',$action);
	$smarty->assign('_reload',$_reload);
	$smarty->assign('parent_id',$parent_id);
	$smarty->assign('setting_type',$setting_type);
	$smarty->assign('setting_id',$setting_id);
	#
	$oneSetting = array('is_trash' => 0, 'parent_id' => $parent_id);
	$titlePage = $core->get_Lang('Addnew');
	$more_information = array('project_id' => 0, 'block_id' => 0);
	if(in_array($setting_type, array('_TYPE_STOCK_LOCK'))){
		$field = "{$clsProfile->pkey},`code`,`full_name`,`first_name`,`last_name`";
		$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'");
		if(!empty($list_staffs)){
			foreach($list_staffs as $key => $val){
				$list_staffs[$key]['full_name'] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
			}
		}
		$smarty->assign('list_staffs',$list_staffs);
	}
	if($setting_id >0){
		$titlePage = $core->get_Lang('Update');
		$oneSetting = $clsSetting->getOne($setting_id);
		$for_id = $oneSetting['for_id'];
		$more_information = $oneSetting['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
	}
	if($setting_type == '_PROJECT'){
		$list_blocks = array();
		$field = "{$clsProject->pkey},`title`";
		$list_projects = $clsProject->getAll("is_trash=0", $field);
		$project_id = (int) $core->get_field($more_information, "project_id", 0);
		if($project_id > 0){
			$field = "{$clsProperty->pkey},`title`";
			$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND for_id='{$project_id}'", $field);
		}
		$smarty->assign('list_blocks', $list_blocks);
		$smarty->assign('list_projects', $list_projects);
	}
	$smarty->assign('for_id',$for_id);
	$smarty->assign('titlePage',$titlePage);
	$smarty->assign('oneSetting',$oneSetting);
	$smarty->assign('more_information',$more_information);
	$smarty->assign('stock_sheet_configs',$stock_sheet_configs);
	$smarty->assign('list_folder_interior_ns', $list_folder_interior_ns);
	$smarty->assign('list_folder_price_sheets', $list_folder_price_sheets);
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.setting.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_save_setting(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	$user_id = $core->_USER['user_id'];
    $setting_type = Input::post('setting_type');
	$setting_id = (int) Input::post('setting_id',0,true);
	$is_range_time = (int) Input::post('is_range_time',0);
	// Action
	if(Input::exists('action','GET')){
		$action = Input::get('action');
		if($action=='_saveorder'){
			$orderNo = Input::post('orderNo');
			for($i=0; $i<count($orderNo); $i++){
				$clsSetting->updateOne($orderNo[$i], array(
					'order_no'	=> ($i+1)	
				));
			}
			echo(1); die();
		}else if($action=='_delete'){
			if($clsSetting->countItem("_type='{$setting_type}' and parent_id='{$setting_id}'") > 0){
				echo '_invalid';
				die();
			}else{
				$log_message = __('Setting has been deleted')." : ". $clsSetting->getTitle($setting_id);
				if($clsSetting->deleteOne($setting_id)){	
					$clsActivityLog = new ActivityLog();
					$log = $clsActivityLog->addActivityLog("Setting","delete",["_type"=>"$setting_type"]);
				}	
			}
			echo(1); die();
		}
	}
	// End action
	$msg = "_error";
	$title = Input::post('title');
	$slug = $core->replaceSpace($title);
	$for_id = (int) Input::post('for_id',0);
	$parent_id = (int) Input::post('parent_id',0);
	$setting_code = Input::post('setting_code', "");
	if($setting_id > 0){
		$cond = "`is_trash`=0 and `parent_id`='{$parent_id}' and `_type`='{$setting_type}'";
		if($for_id > 0) $cond .= " and `for_id`='{$for_id}'";
		if($clsSetting->countItem("{$cond} and `setting_id`<>'{$setting_id}' and `slug`='{$slug}'") > 0){
			echo '_invalid'; 
			die();	
		}else{
			$oneSetting = $clsSetting->getOne($setting_id);
			$more_information = $oneSetting["more_information"];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['setting_code'] = $setting_code;
            $more_information['intro'] = Input::post('intro');
            $more_information['image'] = Input::post('image');
            $more_information['bgcolor'] = Input::post('bgcolor');
            $more_information['textcolor'] = Input::post('textcolor');
            $more_information['user_id_update'] = Input::post('user_id_update');
			if($setting_type == '_PROJECT'){
				$project_id = (int) Input::post('project_id',0);
				$block_id = (int) Input::post('block_id',0);
				$more_information['project_id'] = $project_id;
				$more_information['block_id'] = $block_id;
			}
			$data_update = array(
				'_type'	=> $setting_type,
				'parent_id'	=> $parent_id,
				'for_id' => $for_id,
				'title'	=> $title,
				'slug'	=> $core->replaceSpace($title),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			);
			if($clsSetting->updateOne($setting_id, $data_update)) {
				$msg = "_success";
			}
		}
	}else{
		$cond = "is_trash=0 and parent_id='{$parent_id}' and _type='{$setting_type}'";
		if($for_id > 0) $cond .= " and for_id='{$for_id}'";
		if($clsSetting->countItem("{$cond} and slug='{$slug}'") > 0){
			echo '_invalid'; 
			die();
		}else{
			$more_information = array();
			$setting_id = $clsSetting->getMaxId();
			$more_information['setting_code'] = $setting_code;
            $more_information['intro'] = Input::post('intro');
            $more_information['image'] = Input::post('image');
            $more_information['bgcolor'] = Input::post('bgcolor');
            $more_information['textcolor'] = Input::post('textcolor');
            $more_information['user_id'] = $user_id;
            $more_information['user_id_update'] = $user_id;
			if($setting_type == '_PROJECT'){
				$project_id = (int) Input::post('project_id',0);
				$block_id = (int) Input::post('block_id',0);
				$more_information['project_id'] = $project_id;
				$more_information['block_id'] = $block_id;
			}
			$data_insert = array(
				'setting_id'	=> $setting_id,
				'_type'	=> $setting_type,
				'parent_id'	=> $parent_id,
				'for_id' => $for_id,
				'title'	=> $title,
				'slug'	=> $slug,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'order_no'	=> $clsSetting->getMaxOrderNo(),
				'reg_date'	=> time()
			);
			if($clsSetting->insert($data_insert)) {			
				$msg = "_success";
			}
		}
	}
	// Return
	echo($setting_id); die();
}
function default_load_select_setting(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	####
	$for_id = (int) Input::post('for_id', 0);
	$setting_id = Input::post('setting_id');
	$setting_type = Input::post('setting_type');
	$title = "";
	if($setting_type=='_PROJECT') $title = "Chọn dự án";
	if($setting_type=='_BLOCK') $title = "Chọn phân khu";
	if($setting_type=='_BUILDING') $title = "Chọn tòa nhà";
	####
	if($for_id > 0){
		$html = $clsSetting->getSelectBySettingOrigin($setting_type, $for_id, $setting_id, $title);
	} else {
		$html = $clsSetting->getSelectBySetting($setting_type, $for_id, $setting_id, $title);
	}
	// return
	echo $html; die();
}
function default_load_list_setting(){
	global $smarty,$assign_list,$user_id,$core,$clsISO,$_LANG_ID;
	$clsSetting = new Setting();
	$smarty->assign('clsSetting',$clsSetting);
	#
	$action = '_list';
	$setting_type = Input::post('setting_type');
	$smarty->assign('action',$action);
	$smarty->assign('setting_type',$setting_type);
	#
	$cond = "1=1";
	if($setting_type=='_AGENCY') $cond = "is_locked=0";
	$lstSetting = $clsSetting->getAll($cond." and parent_id='0' and _type='{$setting_type}' order by order_no ASC");
	if(!empty($lstSetting)){
		foreach($lstSetting as $key=> $val){
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) ? json_decode(html_entity_decode($more_information), true) : array();
			$lstSetting[$key]['more_information'] = $more_information;
		}
	}
	$smarty->assign('lstSetting',$lstSetting);
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.setting.tpl');
	echo $html; die();
}
function default_storage_cache(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsSetting = new Setting();
	$setting_type = Input::post("setting_type","");
	###
	$msg = "_error";
	if(!empty($setting_type)){
		$msg = "_success";
		if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){
			$cache = new Cache();
			$field = "{$clsSetting->pkey},`title`,`slug`,`parent_id`,`more_information`";
			$list_settings = $clsSetting->getAll("`is_trash`=0 and `_type`='{$setting_type}' order by `order_no` ASC", $field);
			if(!empty($list_settings)){
				foreach($list_settings as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_settings[$key]['more_information'] = $more_information;
				}
			}
			$cache->set($setting_type, $list_settings);
		} else {
			$cachedName = sprintf('%s_cached.json', $setting_type);
			$cachedFile = DIR_CACHE_JSON.'/setting/'.$cachedName;
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($tblData, $list_settings); 
		}
	}
	// Return
	echo $msg; die();
}
function default_storage_cache_all(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsSetting = new Setting();
	###
	$msg = "_error";
	$list_items = $clsSetting->getAll("`_type`<>'' GROUP BY `_type`","_type");
	if(!empty($list_items)){
		$msg = "_success";
		foreach($list_items as $k => $v) {
			$setting_type = $v['_type'];
			$field = "{$clsSetting->pkey},`title`,`slug`,`parent_id`,`more_information";
			$list_settings = $clsSetting->getAll("`is_trash`=0 and `_type`='{$setting_type}' order by `order_no` ASC", $field);
			if(!empty($list_settings)){
				foreach($list_settings as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_settings[$key]['more_information'] = $more_information;
				}
			}
			if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){
				$cache = new Cache();
				$cache->set($setting_type, $list_settings);
			} else {
				$cachedName = sprintf('%s_cached.json', $setting_type);
				$cachedFile = DIR_CACHE_JSON.'/setting/'.$cachedName;
				$encoder = new Webmozart\Json\JsonEncoder();
				$encoder->encodeFile($tblData, $list_settings); 
			}
			unset($list_settings);
		}			
	}	
	// Return
	echo $msg; die();
}
function default_open_add_property(){
    global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$smarty->assign('core',$core);
    $clsSetting = new Setting();
    $setting_id = (int) Input::post('setting_id');
    $oneSetting = $clsSetting->getOne($setting_id);
    $more_information = $oneSetting['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $configForm = !empty($more_information['configForm']) ? $more_information['configForm'] : [];
	$smarty->assign('setting_id',$setting_id);
	$smarty->assign('configForm',$configForm);
	$html = $core->build('_ajax.open_add_field.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}

function default_save_add_property(){
    global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$getAll = Input::post();
    $clsSetting = new Setting();
    $field = Input::post('field');
    $field_code = Input::post('field_code');
    $field_placeholder = Input::post('field_placeholder');
    $type = Input::post('type');
    $option = Input::post('option');
    $setting_id = Input::post('setting_id');
    $typeArrAccept = _LIST_TYPE_ARRAY_HAS_OPTION;
    $oneSetting = $clsSetting->getOne($setting_id);
    $arr_field_code = [];
    if (!empty($oneSetting)) {
        $more_information = $oneSetting['more_information'];
        $more_information = $clsISO->to_array_json($more_information);
        $configForm = [];
        if (isset($field)) {
            foreach ($field as $indexField=>$field) {
                $configFormItem = [];
                $configFormItem['field'] = $field;
                $configFormItem['field_placeholder'] = !empty(trim($field_placeholder[$indexField])) ? trim($field_placeholder[$indexField]) : '';
                if (!empty(trim($field_code[$indexField]))) {
                    $configFormItem['field_code'] = trim($field_code[$indexField]);
                    $arr_field_code[] = trim($field_code[$indexField]);
                } else {
                    $data = [
                        'status' => 400,
                        'msg' => 'Dữ liệu không đầy đủ. Xin hãy kiểm tra lại',
                    ];
                    echo json_encode($data);die;
                }
                if (!empty(trim($type[$indexField]))) {
                    $configFormItem['type'] = trim($type[$indexField]);
                } else {
                    $data = [
                        'status' => 400,
                        'msg' => 'Dữ liệu không đầy đủ. Xin hãy kiểm tra lại',
                    ];
                    echo json_encode($data);die;
                }
                if (in_array(trim($type[$indexField]), $typeArrAccept)) {
                    if (!empty($option[$configFormItem['field_code']])) {
                        $configFormItem['option'] = $option[$configFormItem['field_code']];
                    } else {
                        $data = [
                            'status' => 400,
                            'msg' => 'Dữ liệu không đầy đủ. Xin hãy kiểm tra lại',
                        ];
                        echo json_encode($data);die;
                    }
                }
                $configForm[] = $configFormItem;
            }
            if (!empty($arr_field_code)) {
                $arr_field_code = @array_filter($arr_field_code);
                $countUnique = count(array_unique($arr_field_code));
                if (count($arr_field_code) != $countUnique) {
                    $data = [
                        'status' => 201,
                        'msg' => 'Dữ liệu của trường "Mã" không được trùng lặp',
                    ];
                    echo json_encode($data);die;
                }
                if (!$core->validateArrayFieldsLatin($arr_field_code)) {
                    $data = [
                        'status' => 201,
                        'msg' => 'Dữ liệu của trường "Mã" bao gồm là dạng chữ viết thường không dấu, số 0->9 và cách nhau bởi dấu "_"',
                    ];
                    echo json_encode($data);die;
                }
            }
            $updateStatus = false;
            if (!empty($configForm)) {
                $more_information['configForm'] = $configForm;
                if($clsSetting->updateOne($setting_id, array(
                    'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
                ))){
                    $updateStatus = true;
                }
            }
            if ($updateStatus) {
                $data = [
                    'status' => 200,
                    'msg' => 'lưu dữ liệu thành công',
                ];
            } else {
                $data = [
                    'status' => 201,
                    'msg' => 'lưu dữ liệu không thành công',
                ];
            }
        } else {
            $data = [
                'status' => 404,
                'msg' => 'Không có dữ liệu đẩy lên',
            ];
        }
    } else {
        $data = [
            'status' => 404,
            'msg' => 'không tìm thấy cài đặt',
        ];
    }
    // echo "<pre>";print_r($configForm);die;
    echo json_encode($data);die;
}