<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||

|| #################################################################### ||

\*======================================================================*/

function default_default(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$clsConfiguration;

	$clsStoclk = new Stock();

	$clsProperty = new Property();

	##

	$tmp = $clsConfiguration->getValues(array('agency_hidden_stock_FH', 'agency_hidden_stock_MOC'));

	$agency_hidden_stock_FH = $core->get_field($tmp, 'agency_hidden_stock_FH');

	$agency_hidden_stock_MOC = $core->get_field($tmp, 'agency_hidden_stock_MOC');

	$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);

	$agency_hidden_stock_MOC = $clsISO->to_array_json($agency_hidden_stock_MOC);

	$assign_list['agency_hidden_stock_FH'] = $agency_hidden_stock_FH;

	$assign_list['agency_hidden_stock_MOC'] = $agency_hidden_stock_MOC;

	$assign_list['clsProperty'] = $clsProperty;

	###

	$list_preloaders = array();

	for($i= 0; $i<100; $i++){

		$list_preloaders[] = $i;

	}

	$assign_list["list_preloaders"] = $list_preloaders;

	/*=============Title & Description Page==================*/

	$title_page = 'Quản lý đại lý - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = ' Các đại lý '.BRAND_NAME.' và đối tác - '.PAGE_NAME;

	$assign_list["description_page"] = $description_page;

}

function default_list(){

	global $smarty,$assign_list,$user_id,$core,$clsISO,$_LANG_ID,$clsConfiguration;

	$clsProperty = new Property();

	$smarty->assign('clsProperty',$clsProperty);

	###

	$property_type = Input::post('property_type');

	$smarty->assign('property_type',$property_type);

	###

	$tmp = $clsConfiguration->getValues(array('agency_hidden_stock_FH', 'agency_hidden_stock_MOC'));

	$agency_hidden_stock_FH = $core->get_field($tmp, 'agency_hidden_stock_FH');

	$agency_hidden_stock_MOC = $core->get_field($tmp, 'agency_hidden_stock_MOC');

	$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);

	$agency_hidden_stock_MOC = $clsISO->to_array_json($agency_hidden_stock_MOC);

	$smarty->assign('agency_hidden_stock_FH',$agency_hidden_stock_FH);

	$smarty->assign('agency_hidden_stock_MOC',$agency_hidden_stock_MOC);

	###

	$cond = "`is_locked`=0 and `parent_id`='0' and `property_type`='_AGENCY'";

	$list_items = $clsProperty->getAll($cond." order by order_no ASC");

	if(!empty($list_items)){

		foreach($list_items as $key=> $val){

			$more_information = $clsISO->to_array_json($val['more_information']);

			$list_items[$key]['more_information'] = $more_information;

		}

	}

	$smarty->assign('core',$core);

	$smarty->assign('list_items',$list_items);

	// Return

	$html = $core->build('_ajax.list.tpl');

	echo $html; die();

}

function default_hide_stock_globe(){

	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;

	###

	$clsProperty = new Property();

	$agency_id = (int) Input::post('agency_id',0);

	$to_field = Input::post('to_field', 'hide_stock_globe');

	$status = (int) Input::post('status',0,true);

	#

	$msg = "_error";

	$more_information = $clsProperty->getOneField('more_information', $agency_id);

	$more_information = $clsISO->to_array_json($more_information);

	$more_information[$to_field] = $status;

	// $clsProperty->setDeBug(1);

	if($clsProperty->updateOne($agency_id, array(

		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

	))){

		$msg = "_success";

	}die;

	// Return

	echo $msg; die();

}

function default_set_trashed(){

	global $smarty,$core,$dbconn,$mod,$act,$clsISO;

	$clsProperty = new Property();

	###

	$msg = "_error";

	$agency_id = (int) Input::post('agency_id', 0);

	$is_trash = (int) Input::post('is_trash', 0);

	if($clsProperty->updateOne($agency_id, array(

		'is_trash' => $is_trash

	))){

		$msg = "_success";

	}

	// Return

	echo $msg; die();

}

function default_add_folder_price_sheets(){

	global $smarty,$core,$dbconn,$mod,$act,$clsISO;

	$clsPermiss = new Permiss();

	$clsProperty = new Property();

	###

	$uid = $clsISO->getUniqid();

	$html = '<div class="form-group form-row mb-2 group_price_sheets">

		<div class="col-12 col-md-6 mb-2 mb-lg-0">

			<label for="title" class="form-label mb-1">Folder PTG</label>

			<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets['.$uid.'][title]" value="" placeholder="Tên folder" maxlength="255">

		</div>

		<div class="col-12 col-md-6 mb-2 mb-lg-0">

			<label for="title" class="form-label mb-1">Link PTG</label>

			<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets['.$uid.'][link]" value="" placeholder="Link folder" maxlength="255">

		</div>

	</div>';

	// Return

	echo $html; die();

}

function default_open(){

	// ini_set('display_errors', '1');

	// ini_set('display_startup_errors', '1');

	// error_reporting(E_ALL);

	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;

	$clsProperty = new Property();

	$smarty->assign('clsProperty',$clsProperty);

	###

	$uid = $clsISO->getUniqid();

	$agency_id = (int) Input::post('agency_id',0);

	$smarty->assign('toId',$toId);

	$smarty->assign('agency_id',$agency_id);

	###

	$action = '_form';

	$smarty->assign('action',$action);

	###

	$oneProperty = array(

		'is_trash' => 0,

		'parent_id' => $parent_id

	);

	$titlePage = $core->get_Lang('Addnew');

	$list_user_group_id = $more_information = array(

		'stock_status_id' => _STOCK_STATUS_LOCK_ID

	);

	$list_folder_price_sheets = array(

		$clsISO->getUniqid() => array(

			'title' => 'Vinhomes',

			'link' => ''

		), 

		$clsISO->getUniqid() => array(

			'title' => 'Masteri',

			'link' => ''

		)

	);

	$list_folder_interior_ns = array(

		$clsISO->getUniqid() => array(

			'title' => '',

			'link' => '',

			'video' => ''

		), 

		$clsISO->getUniqid() => array(

			'title' => '',

			'link' => '',

			'video' => ''

		)

	);

	if($agency_id >0){

		$titlePage = $core->get_Lang('Update');

		$oneItem = $clsProperty->getOne($agency_id);

//		$clsISO->print_pre($oneProperty);die;

		$for_id = $oneItem['for_id'];

		$more_information = $oneItem['more_information'];

		$more_information = $clsISO->to_array_json($more_information);

		if(isset($more_information['folder_price_sheets']) && !empty($more_information['folder_price_sheets'])){

			$list_folder_price_sheets = $more_information['folder_price_sheets'];

		}

		if(isset($more_information['folder_interior_ns']) && !empty($more_information['folder_interior_ns'])){

			$list_folder_interior_ns = $more_information['folder_interior_ns'];

		}

		if(!isset($more_information['stock_status_id'])) 

			$more_information['stock_status_id'] = _STOCK_STATUS_LOCK_ID;

	}

	$smarty->assign('for_id',$for_id);

	$smarty->assign('titlePage',$titlePage);

	$smarty->assign('oneItem',$oneItem);

	$smarty->assign('more_information',$more_information);

	$smarty->assign('stock_sheet_configs',$stock_sheet_configs);

	$smarty->assign('list_folder_interior_ns', $list_folder_interior_ns);

	$smarty->assign('list_folder_price_sheets', $list_folder_price_sheets);

	// Output

	$smarty->assign('core',$core);

	$html = $core->build('_ajax.open.tpl');

	$callback  = '';

	echo json_encode(array(

		'html' => $html,

		'uid' => $uid

	)); die();

}

function default_save_agency(){

//	ini_set('display_errors', '1');

//ini_set('display_startup_errors', '1');

//error_reporting(E_ALL);

	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;

	###

	$clsProperty = new Property();

	$property_type = Input::post('property_type');

	$agency_id = (int) Input::post('agency_id',0);

	// Action

	if(Input::exists('action','GET')){

		$action = Input::get('action');

		if($action=='_saveorder'){

			$orderNo = Input::post('orderNo');

			for($i=0; $i<count($orderNo); $i++){

				$clsProperty->updateOne($orderNo[$i], array(

					'order_no'	=> ($i+1)	

				));

			}

			echo(1); die();

		}else if($action=='_delete'){

			if($clsProperty->countItem("property_type='_AGENCY' and parent_id='{$agency_id}'") > 0){

				echo '_invalid';

				die();

			}else{

				$log_message = __('Property has been deleted')." : ". $clsProperty->getTitle($agency_id);

				if($clsProperty->deleteOne($agency_id)){					

					#activity log		

					/*$clsActivityLog = new ActivityLog();

					$log = $clsActivityLog->addActivityLog("Property","delete",["property_type"=>"_AGENCY"]);*/

				}	

			}

			echo(1); die();

		}

	}

	// End action

	$title = Input::post('title');

	$slug = $core->replaceSpace($title);

	$for_id = (int) Input::post('for_id',0);

	$parent_id = (int) Input::post('parent_id',0);

	$property_code = Input::post('property_code', "");

	$deposit = Input::post('deposit', 0);

	if($agency_id > 0){

		$cond = "`is_trash`=0 and `parent_id`='{$parent_id}' and `property_type`='_AGENCY'";

		if($for_id > 0) $cond .= " and for_id='{$for_id}'";

		if($clsProperty->countItem("{$cond} and `property_id`<>'{$agency_id}' and `slug`='{$slug}'") > 0){

			echo '_invalid'; 

			die();	

		}else{

			$oneProperty = $clsProperty->getOne($agency_id);

			$more = array();

			$more_information = $oneProperty["more_information"];

			$more_information = !empty($more_information) 

				? json_decode(html_entity_decode($more_information), true) : array();

			

			$group_zalo = Input::post('group_zalo');

			$spreadsheetId = Input::post('spreadsheetId');

			$stock_status_id = Input::post('stock_status_id');

			$stock_sheet_configs = Input::post('stock_sheet_configs');

			$folder_price_sheets = Input::post('folder_price_sheets');

			$MOC_content  = Input::post('MOC_content');

			$hide_stock_globe = Input::post('hide_stock_globe', 0);

			if(!$stock_status_id) $stock_status_id = _STOCK_STATUS_LOCK_ID;			

			$more_information['stock_sheet_configs'] = $stock_sheet_configs;

			$more_information['group_zalo'] = $group_zalo;

			$more_information['spreadsheetId'] = $spreadsheetId;

			$more_information['stock_status_id'] = $stock_status_id;

			$more_information['hide_stock_globe'] = $hide_stock_globe;

			$more_information['stock_sheets'] = $stock_sheets;

			$more_information['folder_price_sheets'] = $folder_price_sheets;

			$more_information['MOC_content'] = $MOC_content;

			$title_log = "%s đã sửa đại lý %s";

			$arr_data = array(

				'property_type'	=> "_AGENCY",

				'property_code'	=> $property_code,

				'parent_id'	=> $parent_id,

				'for_id' => $for_id,

				'title'	=> $title,

				'slug'	=> $core->replaceSpace($title),

				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),

				'intro'	=> Input::post('intro'),

				'image'	=> Input::post('image'),

				'bgcolor'	=> Input::post('bgcolor'),

				'textcolor'	=> Input::post('textcolor'),

				'upd_date'	=> time(),

				'user_id_update' => 0

			);

//			$clsISO->print_pre($arr_data);die;

			if($clsProperty->updateOne($agency_id, array_merge($more, $arr_data))) {

				#activity log		

				/*$clsActivityLog = new ActivityLog();

				$log = $clsActivityLog->addActivityLog("Property","update",["property_type"=>"_AGENCY"]);*/

			}

		}

	}else{

		$cond = "is_trash=0 and parent_id='{$parent_id}' and property_type='_AGENCY'";

		if($for_id > 0) $cond .= " and for_id='{$for_id}'";

		if($clsProperty->countItem("{$cond} and slug='{$slug}'") > 0){

			echo '_invalid'; 

			die();

		}else{

			$more = $more_information = array();

			$agency_id = $clsProperty->getMaxId();

			$title_vn = Input::post('title_vn');

			$more['title_vn'] = $title_vn;

			$more['slug_vn'] = $core->replaceSpace($title_vn);

			$group_zalo = Input::post('group_zalo');

			$spreadsheetId = Input::post('spreadsheetId');

			$stock_status_id = Input::post('stock_status_id');

			if(!$stock_status_id) $stock_status_id = _STOCK_STATUS_LOCK_ID;

			$hide_stock_globe = Input::post('hide_stock_globe', 0);

			$stock_sheet_configs = Input::post('stock_sheet_configs');

			$folder_price_sheets = Input::post('folder_price_sheets');

			$MOC_content = Input::post('MOC_content');

			$more_information['stock_sheet_configs'] = $stock_sheet_configs;

			$more_information['group_zalo'] = $group_zalo;

			$more_information['spreadsheetId'] = $spreadsheetId;

			$more_information['stock_status_id'] = $stock_status_id;

			$more_information['hide_stock_globe'] = $hide_stock_globe;

			$more_information['folder_price_sheets'] = $folder_price_sheets;

			$more_information['MOC_content'] = $MOC_content;

			$title_log = "%s đã thêm mới đại lý %s";

			$arr_data = array(

				'property_id'	=> $agency_id,

				'property_type'	=> "_AGENCY",

				'property_code' => $property_code,

				'parent_id'	=> $parent_id,

				'for_id' => $for_id,

				'title'	=> $title,

				'slug'	=> $slug,

				'intro'	=> Input::post('intro'),

				'image'	=> Input::post('image'),

				'bgcolor'	=> Input::post('bgcolor'),

				'textcolor'	=> Input::post('textcolor'),

				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),

				'reg_date'	=> time(),

				'upd_date'	=> time(),

				'user_id'	=> 0,

				'user_id_update'	=> 0,

				'order_no'	=> $clsProperty->getMaxOrderNo()

			);

//			$clsISO->print_pre($arr_data);die;

			if($clsProperty->insert(array_merge($more, $arr_data))) {				

				#activity log		

				/*$clsActivityLog = new ActivityLog();

				$log = $clsActivityLog->addActivityLog("Property","insert",["property_type"=>"_AGENCY"]);*/

			}

		}

	}

	// Return

	echo($agency_id); die();

}

function default_storage_cache(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;

	$clsProperty = new Property();

	###

	$msg = "_error";

	$property_type = Input::post('property_type', '_AGENCY');

	if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){

		$cache = new Cache();

		$field = "{$clsProperty->pkey},`title`,`title_vn`,`property_code`,`slug`,`image`";

		$field.= ",`parent_id`,`intro`,`more_information`,`textcolor`,`bgcolor`";

		$lstProperty = $clsProperty->getAll("`is_trash`=0 and `property_type`='{$property_type}' 

			order by `order_no` ASC", $field);

		if($cache->set("_AGENCY", $lstProperty)){

			$msg = "_success";			

		}

	} else {

		$cachedName = sprintf('%s.json', $property_type);

		$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;

		$encoder = new Webmozart\Json\JsonEncoder();

		if($encoder->encodeFile($tblData, $lstProperty)) {

			$msg = "_success";

		} 

	}

	// Return

	echo $msg; die();

}

function default_open_setting(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$smarty;

	$clsProperty = new Property();

	###

	$uid = $clsISO->getUniqid();

	$smarty->assign("uid",$uid);

	$html = $core->build('_ajax.open_setting.tpl');

	// Return

	echo json_encode(array(

		"html"	=>	$html,

		"uid"	=>	$uid 

	));die;

}

function default_load_setting_field(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration,$smarty;

	$clsProperty = new Property();

	###

	$tmp = $clsConfiguration->getValues(array('agency_hidden_stock_FH','agency_hidden_stock_MOC'));

	$agency_hidden_stock_FH = $tmp['agency_hidden_stock_FH'];

	$agency_hidden_stock_MOC = $tmp['agency_hidden_stock_MOC'];

	$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);

	$agency_hidden_stock_MOC = $clsISO->to_array_json($agency_hidden_stock_MOC);

	//	 $clsISO->print_pre($agency_hidden_stock); die();

	$smarty->assign("clsProperty",$clsProperty);

	$smarty->assign("agency_hidden_stock_FH",$agency_hidden_stock_FH);

	$smarty->assign("agency_hidden_stock_MOC",$agency_hidden_stock_MOC);

	$html = $core->build("_ajax.setting_field.tpl");

	// Return

	echo json_encode(array(

		"html"	=>	$html

	));die;

}

function default_open_setting_field(){

	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	

	$clsProperty = new Property();

	$clsProject = new Project();

	##

	$uid = $clsISO->getUniqid();

	$id = Input::post('id', "");

	$type = Input::post('type', "_FH");

	$agency_hidden_stock = $clsConfiguration->getValue('agency_hidden_stock'.$type);

	$agency_hidden_stock = $clsISO->to_array_json($agency_hidden_stock);

	$oneItem = $core->get_field($agency_hidden_stock, $id, []);

	$project_id = $core->get_field($oneItem, 'project_id', 0);

	##

	$action = "_edit"; $titlePage = "Thêm mới";

	$list_blocks = $list_buildings = $more_information = array();

	if(!empty($agency_hidden_stock[$agency_hidden_stock_id])) {

		$action = '_edit';

		$titlePage= 'Chỉnh sửa';

	}

	$smarty->assign('oneItem', $oneItem);

	$smarty->assign('id', $id);

	$smarty->assign('uid', $uid);

	$smarty->assign('titlePage', $titlePage);

	$smarty->assign('clsProperty', $clsProperty);

	$smarty->assign('clsProject', $clsProject);

	$smarty->assign('project_id', $project_id);

	// Return

	$html = $core->build('_ajax.open_setting_field.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_save_setting_field(){

	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	

	##

	$uid = $clsISO->getUniqid();

	$id = Input::post('id', "", ['ex' => 'no_empty']);

	if(empty($id)) $id = $uid;

	$type = Input::post('type', "_FH");

	$action = Input::post('action', "save");

	$res = ["result"	=>	false];

	if($action == "save") {

		$title = Input::post('title', "");

		$code = Input::post('code', "");

		$project_id = (int)Input::post('project_id', 0);

		$block_id = (int)Input::post('block_id', 0);

		$site = Input::post('site', "CA");

		$is_vin = (int)Input::post('is_vin', 0);

		$agency_hidden_stock = $clsConfiguration->getValue('agency_hidden_stock'.$type);

		$agency_hidden_stock = $clsISO->to_array_json($agency_hidden_stock);

		$agency_hidden_stock[$id] = [

			"title"	=>	$title,

			"code"	=>	$code,

			"project_id" =>	$project_id,

			"block_id"	=>	$block_id,

			"site"	=>	$site,

			"is_vin"	=>	$is_vin 

		];

	}elseif($action == "delete"){

		$agency_hidden_stock = $clsConfiguration->getValue('agency_hidden_stock'.$type);

		$agency_hidden_stock = $clsISO->to_array_json($agency_hidden_stock);

		if(!empty($agency_hidden_stock) && !empty($id) && array_key_exists($id, $agency_hidden_stock)){

			unset($agency_hidden_stock[$id]);

		}

		// $clsISO->print_pre($agency_hidden_stock); die();

	}

	if($clsConfiguration->updateValue("agency_hidden_stock".$type, 

		json_encode($agency_hidden_stock, JSON_UNESCAPED_UNICODE))){

		$res = ["result" =>	true];

	}

	// Return

	echo json_encode($res); die();

}