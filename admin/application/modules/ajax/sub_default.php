<?php
function default_saveField(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod, $act;
	global $core, $clsModule, $clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$clsTable = Input::post('clsTable');
	$pval_id = (int) Input::post('pval_id', 0);
	$toField = Input::post('toField');
	$value = Input::post('value');
	
	$msg = '_error';
	$clsClassTable = new $clsTable();
	if($clsClassTable->updateOne($pval_id, array(
		$toField => $value
	))){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_ajaxPopSiteHelp(){
	global $core;
	$clsHelp = new Help();
	$mod_page = $_POST['mod_page'];
	$act_page = $_POST['act_page'];
	$area_page = $_POST['area_page'];
	$SiteHelpPage = 'Site_Help_'.$mod_page.'_'.$act_page;
	if($area_page != ''){
		$SiteHelpPage .= '_'.$area_page;
	}
	#
	$html='<div class="headPop"> 
		<a class="closeEv close_pop" data-dismiss="modal" aria-hidden="true">&nbsp;</a> 
		<h3>'.$core->get_Lang('infohelpmod').' '.$core->get_Lang($mod_page).'</h3>
	</div>';
	if(_DEV == '1') {
		$html.='<form method="post" action="" id="formHelp" class="frmform formborder" enctype="multipart/form-data">
			<div class="wrap">
				<div class="fl" style="width:100%">
					<div class="row-span">
						<div class="fieldlabel" style="text-align:right">'.$core->get_Lang('content').'</div>
						<div class="fieldarea">
							<textarea id="textarea_help_content_editor_'.time().'" class="textarea_help_content_editor" name="'.$SiteHelpPage.'" style="width:100%">'.$clsHelp->getValue($SiteHelpPage).'</textarea>
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="modal-footer"> 
			<button class="btn btn-primary btnSaveSiteHelpPage" mod_page="'.$mod_page.'" act_page="'.$act_page.'" area_page="'.$area_page.'"><i class="icon-ok icon-white"></i> '.$core->get_Lang('Save').'</button> 
			<button type="reset" class="btn btn-warning close_pop">
				<i class="icon-retweet icon-white"></i> <span>Đóng lại</span>
			</button>
		</div>';
	} else {
		$html .= '
		<style>.formatTextStandard{width:99%;padding-right:1%;max-height:470px;overflow-y:scroll}</style>';
		$html.= '<div class="formatTextStandard">';
		if($clsHelp->getValue($SiteHelpPage) != ''){
			$html .= $clsHelp->getValue($SiteHelpPage);
		}else{
			$html .= $core->get_Lang('Help content empty !');
		}
		$html .= '</div>';
	}
	echo $html; die();
}
function default_ajaxSaveSiteHelp(){
	global $core;
	#
	$clsHelp = new Help();
	$mod_page = $_POST['mod_page'];
	$act_page = $_POST['act_page'];
	$area_page = $_POST['area_page'];
	
	$SiteHelpPage = 'Site_Help_'.$mod_page.'_'.$act_page;
	if($area_page != ''){
		$SiteHelpPage .= '_'.$area_page;
	}
	$help_content = isset($_POST['help_content'])?addslashes($_POST['help_content']):'';
	$clsHelp->updateValue($SiteHelpPage,$help_content);
	echo(1); die();
}
#-------------------------- / Media / -----------------------------#
function default_init_gallery(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav,$clsISO;
	$clsImage = new Image();
	$type = Input::post('type');
	$table_id = Input::post('table_id', 0);
	$html='<div class="form-search form-inline">
		<div class="form-group">
			<div class="input-group">
				<input type="hidden" name="type" value="'.$type.'" />
				<input type="hidden" name="table_id" value="'.$table_id.'" />
				<input type="text" class="form-control txtKeySearch" name="keyword" placeholder="Tìm kiếm" />
				<div class="input-group-btn">
					<button type="button" style="padding:9px 10px;" class="btn btn-default">'.$core->makeIcon('search').'</button>
				</div>
			</div>
		</div>
		<div class="pull-right">
			<input type="file" class="d-none" onChange="file_upload_change(this)" _type="'.$type.'" table_id="'.$table_id.'" multiple="" name="image_list[]" id="AddFileAttach" />
			<button type="button" class="btn btn-default selectFile" handler="AddFileAttach">
				'.$core->makeIcon('plus-circle', 'Chọn ảnh từ máy tính').'
			</button>
		</div>
	</div>';
	$html.='<div id="tblHolderGallery">Loading...</div>';
	echo $html; die();
}
function default_file_upload_gallery(){
	global $core, $_LANG_ID, $clsISO;
	$user_id = $core->_USER['user_id'];
	$clsImage = new Image();
	
	$type = Input::post('type');
	$table_id = Input::post('table_id', 0);
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$image_list = $_FILES['image_list'];
		if(!empty($image_list['name'])){
			for($i = 0; $i < count($image_list); $i++){
				$clsUploadFile = new UploadFile();
				$_image = array();
				$_image["name"] = $image_list['name'][$i];
				$_image["type"] = $image_list['type'][$i];
				$_image["tmp_name"] = $image_list['tmp_name'][$i];
				$_image["error"] = $image_list['error'][$i];
				$_image["size"] = $image_list['size'][$i];
				#
				$up = $clsUploadFile->uploadItem($_image,"/content","jpg,gif,png");
				if(!empty($up)){
					$field ="user_id,table_id,type,image,order_no,reg_date,upd_date";
					$value = "'{$user_id}','{$table_id}','{$type}','".addslashes($up)."'
					,'".$clsImage->getMaxOrderNo($table_id, $type)."','".time()."','".time()."'";
					$clsImage->insertOne($field,$value);
				}
			}
			echo '_success'; die();
		}else{
			echo '_error'; die();
		}
	}else{
		echo '_error'; die();
	}
}
function default_load_list_gallery(){
	global $core, $dbconn, $_LANG_ID, $clsISO;
	$clsImage=new Image();
	$clsPagination = new Pagination();
	
	$type = Input::post('type');
	$table_id = Input::post('table_id', 0);
	$keyword = Input::post('keyword' , "");
	$cond="is_trash=0 and type='{$type}' and table_id='{$table_id}'";
	if(!empty($keyword)){
		$slug = $core->replaceSpace($keyword);
		$cond.=" and (title like '%{$keyword}%' or slug like '%{$slug}%')";
	}
	
	$current_page = (int) Input::post('page', 1);
	$number_per_page = (int) Input::post('number_per_page', 10);
	$total_record = $clsImage->countItem($cond);
	$total_page = ceil($total_record/$number_per_page);
	$offset = ($current_page - 1)*$number_per_page;
	$limitCond = " LIMIT {$offset}, {$number_per_page}";
	#
	$html = '<table class="table m-0 table-vertical table-striped" width="100%" cellpadding="0" cellspacing="0">
	<thead><tr>
		<th class="text-center" width="4%">No.</th>
		<th class="text-center" width="40px">Hình ảnh</th>
		<th class="text-left">Tiêu đề</th>
		<th class="text-right" width="20%">Cập nhật</th>
		<th class="text-center" width="3%"><i class="icon-circle-arrow-up"></i></th>
		<th class="text-center" width="3%"><i class="icon-circle-arrow-down"></i></th>
		<th class="text-center" width="3%"><i class="icon-arrow-up"></i></th>
		<th class="text-center" width="3%"><i class="icon-arrow-down"></i></th>
		<th class="text-center" width="8%">Tools</th>
	</tr></thead>';
	$lstImages = $clsImage->getAll($cond." ORDER BY order_no DESC".$limitCond);
	if(!empty($lstImages)){ $ii=0; // Init
		$total_images = count($lstImages);
		foreach($lstImages as $image){
			$image_id = $image[$clsImage->pkey];
			$html.='<tr>
				<td class="text-center">'.($ii+1).'</td>
				<td class="text-center" width="40px">
					<div class="aspect-ratio aspect-ratio--square aspect-ratio--square--40 aspect-ratio--interactive">
						<img class="aspect-ratio__content" src="'.$image['image'].'" width="40px" />
					</div>
				</td>
				<td class="text-left"><a href="javascript:void();" image_id="'.$image_id.'" type="'.$type.'" table_id="'.$table_id.'" title="Sửa" class="btn_editphoto_gallery">'.$image['title'].'</a></td>
				<td class="text-right red">'.$clsISO->convertTimeToText($image['reg_date'], true).'</td>
				<td class="text-center">'.($ii==0?'':'<a href="javascript:void(0);" image_id="'.$image_id.'" class="btn_movephoto_gallery" direct="movetop" title="Lên trên cùng" image_id="'.$image_id.'" type="'.$type.'" table_id="'.$table_id.'"><i class="icon-circle-arrow-up"></i></a>').'</td>
				<td class="text-center">'.($ii==$total_images-1?'':'<a href="javascript:void(0);" image_id="'.$image_id.'" class="btn_movephoto_gallery" direct="movebottom" title="Xuống dưới cùng" image_id="'.$image_id.'" type="'.$type.'" table_id="'.$table_id.'"><i class="icon-circle-arrow-down"></i></a>').'</td>
				<td class="text-center">'.($ii==0?'':'<a href="javascript:void(0);" image_id="'.$image_id.'" class="btn_movephoto_gallery" direct="moveup" title="Lên" image_id="'.$image_id.'" type="'.$type.'" table_id="'.$table_id.'"><i class="icon-arrow-up"></i></a>').'</td>
				<td class="text-center">'.($ii==$total_images-1?'':'<a href="javascript:void();" image_id="'.$image_id.'" class="btn_movephoto_gallery" direct="movedown" title="Xuống" image_id="'.$image_id.'" type="'.$type.'" table_id="'.$table_id.'"><i class="icon-arrow-down"></i></a>').'</td>
				<td class="text-center">
					<div class="btn-group"><a href="javascript:void(0);" image_id="'.$image_id.'" type="'.$type.'" table_id="'.$table_id.'" title="Edit" class="btn_editphoto_gallery btn btn-xs btn-primary"><i class="icon-edit icon-white"></i></a>
					<a href="javascript:void(0);" type="'.$type.'" table_id="'.$table_id.'" image_id="'.$image_id.'" title="Delete" class="btn btn_deletephoto_gallery btn-xs btn-warning"><i class="icon-remove icon-white"></i></a>
				</div></td>
			</tr>';
			++$ii;
		}
	}else{
		$html.='<tr>
			<td colspan="9" style="text-align:center;">Chưa có dữ liệu!</td>
	    </tr>';
	}
	$html .= '</table>';
	if($total_record > 0){
		$html .= '<div class="easyui-pagination" id="pager_photo_gallery" pageNumber="'.$current_page.'" pageList="[10,20]"></div>';
	}
	echo @json_encode(array(
		'html' => $html,
		'total_record' => $total_record,
		'number_per_page' => $number_per_page
	)); die();
}
function default_open_editphoto_gallery(){
	global $smarty,$dbconn,$core,$clsISO;
	$clsImage = new Image();
	
	$type = Input::post('type');
	$table_id = (int) Input::post('table_id', 0);
	$image_id = (int) Input::post('image_id', 0);
	$oneItem = $clsImage->getOne($image_id);
	$smarty->assign('type', 	$type);;
	$smarty->assign('table_id', $table_id);
	$smarty->assign('image_id', $image_id);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('titlePage', $core->get_Lang('EditImage'));
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('gallery.tpl');
	echo $html; die();
}
function default_savephoto_gallery(){
	global $core, $_LANG_ID;
	$user_id = $core->_USER['user_id'];
	$clsImage=new Image();
	$image_id = (int) Input::post('image_id', 0);
	$title = Input::post('title');
	if($image_id > 0){
		$set = "title='".addslashes($title)."'
		,slug='".$core->replaceSpace($title)."'
		,user_id_update='$user_id'
		,upd_date='".time()."'";
		if($clsImage->updateOne($image_id,$set)){
			echo('_success'); die();
		}else{
			echo('_error'); die();
		}
	}
}
function default_delete_photo_gallery(){
	global $core, $_LANG_ID;
	$clsImage=new Image();
	$image_id = (int) Input::post('image_id', 0);
	
	$msg = '_error';
	if($image_id){
		$image = $clsImage->getOneField('image', $image_id);
		$clsImage->deleteOne($image_id);
		if(!empty($image) && file_exists(ABSPATH.$image)){
			$msg = '_success';
			$image = ABSPATH.$image;
			@unlink($image);
		}
	}
	// Return
	echo(1); die();
}
function default_movephoto_gallery(){
	global $core, $_LANG_ID;
	$clsImage=new Image();
	
	$direct = Input::post('direct');
	$type = Input::post('type');
	$table_id = (int) Input::post('table_id', 0);
	$image_id = (int) Input::post('image_id', 0);
	
	$order_no = $clsImage->getOneField('order_no', $image_id);
	$where = "table_id='{$table_id}' and type='{$type}'";
	if($direct=='moveup'){
		$lst = $clsImage->getAll("{$where} and order_no>'{$order_no}' order by order_no asc limit 0,1");
		$clsImage->updateOne($image_id,"order_no='".$lst[0]['order_no']."'");
		$clsImage->updateOne($lst[0][$clsImage->pkey],"order_no='".$order_no."'");
	} else if($direct=='movedown'){
		$lst = $clsImage->getAll("{$where} and order_no<'{$order_no}' order by order_no desc limit 0,1");
		$clsImage->updateOne($image_id,"order_no='".$lst[0]['order_no']."'");
		$clsImage->updateOne($lst[0][$clsImage->pkey],"order_no='".$order_no."'");
	} else if($direct=='movetop'){
		$lst = $clsImage->getAll("{$where} and order_no>'{$order_no}' order by order_no asc");
		$clsImage->updateOne($image_id,"order_no='".$lst[count($lst)-1]['order_no']."'");
		unset($lst);
		$clsImage->updateByCond("{$where} and image_id<>'{$image_id}' and order_no>'{$order_no}'","order_no=order_no-1");
	} else if($direct=='movebottom'){
		$lst = $clsImage->getAll("{$where} and order_no<'{$order_no}' order by order_no desc");
		$clsImage->updateOne($image_id,"order_no='".$lst[count($lst)-1]['order_no']."'");
		unset($lst);
		$clsImage->updateByCond("{$where} and image_id<>'{$image_id}' and order_no<'{$order_no}'","order_no=order_no+1");	
	}
	echo(1); die();
}
function default_ajCountPhotosGallery(){
	$clsImage=new Image();
	$type = $_POST['type'];
	$table_id = $_POST['table_id'];
	#
	$total_record = $clsImage->countItem("table_id='$table_id' and type='$type'");
	echo $total_record; die();
}
function default_ajSysPositionGallery(){
	$clsImage = new Image();
	$type = $_POST['type'];
	$table_id = $_POST['table_id'];
	#
	$lstItem = $clsImage->getAll("is_trash=0 and type='$type' and table_id='$table_id' order by image_id asc");
	if(!empty($lstItem)){
		for($i=0; $i<count($lstItem); $i++){
			$image_id = $lstItem[$i][$clsImage->pkey];
			$clsImage->updateOne($image_id,"order_no='".($i+1)."'");
		}
	}
	echo(1); die();
}
/** End Photo Gallery */
function default_load_list_property(){
	global $smarty,$assign_list,$user_id,$core,$clsISO,$_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	
	$property_type = Input::post('property_type');
	$action = '_list';
	
	$cond = "`property_type`='{$property_type}'";
	if($property_type=='_AGENCY') $cond = " AND `is_locked`=0";
	$lstProperty = $clsProperty->getAll($cond." AND `parent_id`='0' order by order_no ASC");
	// $clsISO->print_pre($lstProperty); die();
	if(!empty($lstProperty)){
		foreach($lstProperty as $key=> $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$lstProperty[$key]['more_information'] = $more_information;
		}
	}
	$smarty->assign('action',$action);
	$smarty->assign('lstProperty',$lstProperty);
	$smarty->assign('property_type',$property_type);
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('property.tpl');
	echo $html; die();
}
function default_open_property(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsProject = new Project();
	$smarty->assign('clsProperty',$clsProperty);
	$smarty->assign('clsProject',$clsProject);
	###
	$toId = Input::post('toId');
	$for_id = Input::post('for_id', 0);
	$_reload = Input::post('_reload',0);
	$parent_id = Input::post('parent_id', 0);
	$property_type = Input::post('property_type');
	$property_id = (int) Input::post('property_id',0);
	$smarty->assign('toId',$toId);
	$smarty->assign('_reload',$_reload);
	$smarty->assign('parent_id',$parent_id);
	$smarty->assign('property_type',$property_type);
	$smarty->assign('property_id',$property_id);
	###
	$action = '_form';
	$smarty->assign('action', $action);
	$oneProperty = array('is_trash' => 0, 'parent_id' => $parent_id);
	$titlePage = "Thêm mới thuộc tính";
	$list_user_group_id = $more_information = array(
		'stock_status_id' => _STOCK_STATUS_LOCK_ID,
		'business_area_id' => 0,
		'office_id' => 0,
		'office_cost_cat_id' => 0,
	);
	if($property_type == '_REPORT_TEMPLATE'){
		$field = "{$clsProperty->pkey},title";
		$list_roles = $clsProperty->getAll("`property_type`='_ROLE' 
			and `parent_id`='"._ROLE_STAFF_SALE."' order by `order_no` ASC", $field);
		$smarty->assign('list_roles', $list_roles);
	}
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
		), $clsISO->getUniqid() => array(
			'title' => '',
			'link' => '',
			'video' => ''
		)
	);
	if($property_type == '_BILLING_TYPE'){
		$list_group_zalo = array(
			$clsISO->getUniqid() => array(
				'project_id' => '',
				'group_zalo_id' => '',
			)
		);
	} else if($property_type == '_OPS_COST_CAT'){
		$arr_offices = $clsSetting->getCacheItems('_OFFICE');
		$arr_categories = $clsSetting->getCacheItems('_OFFICE_COST_CATEGORY');
		$smarty->assign('arr_offices', $arr_offices);
		$smarty->assign('arr_categories', $arr_categories);
	}
	$list_roles = array(
		'DIRECTOR' => 'Ban điều hành',
		'REGIONAL_DIRECTOR' => 'Giám đốc vùng',
		'SALE_DIRECTOR' => 'Giám đốc kinh doanh',
		'SALE' => 'Sale',
		'MARKING' => 'Marketing',
		'ADMIN_PROJECT' => 'Admin',
		'ACCOUNTANT' => 'Kế toán',
	);
	$smarty->assign('list_roles', $list_roles);
	#
	if(in_array($property_type, array('_BILLING_TYPE', '_TRANSACTION_PROJECT', '_DEPARTMENT', '_BUSINESS_AREA'))){
		$clsProfile = new Profile();
		$field = "{$clsProfile->pkey},`code`,`full_name`,`first_name`,`last_name`";
		$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'");
		if(!empty($list_staffs)){
			foreach($list_staffs as $key => $val){
				$list_staffs[$key]['full_name'] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
			}
		}
		$smarty->assign('list_staffs',$list_staffs);
	}
	if($property_id >0){
		$titlePage = $core->get_Lang('Update');
		$oneProperty = $clsProperty->getOne($property_id);
		$for_id = $oneProperty['for_id'];
		$more_information = $oneProperty['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$list_folder_price_sheets = $core->get_field($more_information, "folder_price_sheets", []);
		$list_folder_interior_ns = $core->get_field($more_information, "folder_interior_ns", []);
		$list_group_zalo = $core->get_field($more_information, "list_group_zalo", []);
		$info_agency = $core->get_field($more_information, "info_agency", []);
		$stock_status_id = $core->get_field($more_information, "stock_status_id", _STOCK_STATUS_LOCK_ID);
		if($property_type == '_REPORT_TEMPLATE'){
			$permiss_role = $core->get_field($more_information, "permiss_role", []);
			$smarty->assign('permiss_role', $permiss_role);
		}
	}
	$smarty->assign('for_id',$for_id);
	$smarty->assign('titlePage',$titlePage);
	$smarty->assign('oneProperty',$oneProperty);
	$smarty->assign('more_information',$more_information);
	$smarty->assign('stock_sheet_configs',$stock_sheet_configs);
	$smarty->assign('list_folder_interior_ns', $list_folder_interior_ns);
	$smarty->assign('list_folder_price_sheets', $list_folder_price_sheets);
	$smarty->assign('list_group_zalo', $list_group_zalo);
	$smarty->assign('info_agency', $info_agency);
	if($property_type == '_GROUP_RANGE_VHGG'){
		$html_options_range = "";
		$field = "{$clsProperty->pkey},title";
		$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
			and `for_id`='"._PROJECT_VHGG_ID."' order by order_no ASC", $field);
		if(!empty($list_blocks)){
			$list_selected_ranges = $core->get_field($more_information, "list_ranges", []);
			foreach($list_blocks as $key => $val){
				$block_id = $val[$clsProperty->pkey];
				$html_options_range.= '<optgroup label="'.$val['title'].'">';
				$list_ranges = $clsProperty->getAll("`is_trash`=0 and `property_type`='_RANGE' 
					and `for_id`='{$block_id}' order by `order_no` ASC", $field);
				if(!empty($list_ranges)){
					foreach($list_ranges as $okey => $oval){
						$html_options_range.= '<option'.(in_array($oval[$clsProperty->pkey],$list_selected_ranges)?' selected':'').' value="'.$oval[$clsProperty->pkey].'">'.$oval['title'].'</option>';
					}
					unset($list_ranges);
				}
				$html_options_range.= '</optgroup>';
			}
			unset($list_blocks);
		}
		$smarty->assign('html_options_range',$html_options_range);
	}
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('property.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	)); die();
}
function default_save_property(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$dbconn;
	$clsProperty = new Property();
	$user_id = $core->_USER['user_id'];
	$property_type = Input::post('property_type');
	$property_id = (int) Input::post('property_id',0,true);
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
			if($clsProperty->countItem("property_type='{$property_type}' and parent_id='{$property_id}'") > 0){
				echo '_invalid';
				die();
			}else{
				$log_message = __('Property has been deleted')." : ". $clsProperty->getTitle($property_id);
				if($clsProperty->deleteOne($property_id)){	
					$clsActivityLog = new ActivityLog();
					$log = $clsActivityLog->addActivityLog("Property","delete",["property_type"=>"$property_type"]);
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
	$propery_icon = Input::post('icon', "");
	$badge = Input::post('badge', "");
	$deposit = Input::post('deposit', 0);
	if($property_id > 0){
		$cond = "`is_trash`=0 and `parent_id`='{$parent_id}' and `property_type`='{$property_type}'";
		if($for_id > 0) $cond .= " and `for_id`='{$for_id}'";
		if($clsProperty->countItem("{$cond} and `property_id`<>'{$property_id}' and `slug`='{$slug}'") > 0){
			echo '_invalid'; 
			die();	
		}else{
			$more = $arr_edit = array();
			$oneProperty = $clsProperty->getOne($property_id);
			$more_information = $oneProperty["more_information"];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['badge'] = $badge;
			if($oneProperty['title'] != $title) {
				$arr_edit["Tiêu đề"] = !empty($oneProperty['title']) ? $oneProperty['title'] : $title;
			}
			if($oneProperty['property_code'] != $property_code) {
				$arr_edit["Mã"] = !empty($oneProperty['property_code']) ? $oneProperty['property_code'] : $property_code;
			}
			if($oneProperty['parent_id'] != $parent_id) {
				$arr_edit["Danh mục gốc"] = !empty($oneProperty['parent_id']) ? $oneProperty['parent_id'] : $parent_id;
			}
			if(in_array($property_type, array('_TYPE_VILLA','_DIRECTION','_AGENCY','_REPORT_TEMPLATE','_BILLING_TYPE'))){
				$title_vn = Input::post('title_vn');
				$more['title_vn'] = $title_vn;
				$more['slug_vn'] = $core->replaceSpace($title_vn);
				if($property_type == "_TYPE_VILLA"){
					$title_log = "%s đã sửa loại hình thấp tầng %s";
				}elseif($property_type == "_DIRECTION"){
					$title_log = "%s đã sửa hướng nhà %s";
				}
				if($oneProperty['title_vn'] != $title_vn) {
					$arr_edit["Tiêu đề đầy đủ"] = !empty($oneProperty['title_vn']) ? $oneProperty['title_vn'] : $title_vn;
				}
			}
			if($property_type=='_BEDROOM'){
				$folder_interior_ns = Input::post('folder_interior_ns');
				$bathroom = (int) Input::post('bathroom', 0);
				$ms_value = (int) Input::post('ms_value', 0);
				$more['ms_value'] = $ms_value;
				if($more_information['bathroom'] != $bathroom) {
					$arr_edit["Số phòng tắm"] = $core->get_field($more_information, "bathroom", $bathroom);
				}
				$more_information['folder_interior_ns'] = $folder_interior_ns;
				$more_information['bathroom'] = $bathroom;
				$title_log = "%s đã sửa loại phòng ngủ %s";
			} else if($property_type=='_AGENCY'){
				$group_zalo = Input::post('group_zalo');
				$group_zalo_lowrise = Input::post('group_zalo_lowrise');
				$spreadsheetId = Input::post('spreadsheetId');
				$stock_status_id = Input::post('stock_status_id');
				$stock_sheet_configs = Input::post('stock_sheet_configs');
				$folder_price_sheets = Input::post('folder_price_sheets');
				$MOC_content  = Input::post('MOC_content');
				$info_agency = Input::post('info_agency', array());
				$hide_stock_globe = Input::post('hide_stock_globe', 0);
				if(!$stock_status_id) $stock_status_id = _STOCK_STATUS_LOCK_ID;
				if($more_information['group_zalo'] != $group_zalo) {
					$arr_edit["Group zalo cao tầng"] = $core->get_field($more_information, "group_zalo", $group_zalo);
				}
				if($more_information['group_zalo_lowrise'] != $group_zalo_lowrise) {
					$arr_edit["Group zalo thấp tầng"] = $core->get_field($more_information, "group_zalo_lowrise", $group_zalo_lowrise);
				}
				if($more_information['spreadsheetId'] != $spreadsheetId) {
					$arr_edit["Spreadsheet ID"] = $core->get_field($more_information, "spreadsheetId", $spreadsheetId);
				}
				if($more_information['stock_status_id'] != $stock_status_id) {
					$arr_edit["Tình trạng"] = $core->get_field($more_information, "stock_status_id", $stock_status_id);
				}
				if($more_information['stock_sheet_configs'] != $stock_sheet_configs) {
					$arr_edit["stock_sheet_configs"] = $core->get_field($more_information, "stock_sheet_configs", $stock_sheet_configs);
				}
				if($more_information['MOC_content'] != $MOC_content) {
					$arr_edit["Nội dung"] = $core->get_field($more_information, "MOC_content", $MOC_content);
				}
				if($more_information['hide_stock_globe'] != $hide_stock_globe) {
					$arr_edit["Ẩn khỏi CA.FH"] = $core->get_field($more_information, "hide_stock_globe", $hide_stock_globe);
				}			
				$more_information['stock_sheet_configs'] = $stock_sheet_configs;
				$more_information['group_zalo'] = $group_zalo;
				$more_information['group_zalo_lowrise'] = $group_zalo_lowrise;
				$more_information['spreadsheetId'] = $spreadsheetId;
				$more_information['stock_status_id'] = $stock_status_id;
				$more_information['hide_stock_globe'] = $hide_stock_globe;
				$more_information['stock_sheets'] = $stock_sheets;
				$more_information['folder_price_sheets'] = $folder_price_sheets;
				$more_information['MOC_content'] = $MOC_content;
				$more_information['info_agency'] = $info_agency;
				$title_log = "%s đã sửa đại lý %s";
			} else if($property_type=='_PACKAGE'){
				$price_month = (int)Input::post('price_month',0);
				$price_3month = (int)Input::post('price_3month',0);
				$price_6month = (int)Input::post('price_6month',0);
				$price_year = (int)Input::post('price_year',0);
				$day_trial = (int)Input::post('day_trial',0);
				$show_pricing = (int)Input::post('show_pricing',0);
				$package_intro = Input::post('package_intro');
				$trial_terms = Input::post('trial_terms');				
				if($more_information['price_month'] != $price_month) {
					$arr_edit["Giá gói 1 tháng"] = $core->get_field($more_information, "price_month", $price_month);
				}			
				if($more_information['price_3month'] != $price_3month) {
					$arr_edit["Giá gói 3 tháng"] = $core->get_field($more_information, "price_3month", $price_3month);
				}		
				if($more_information['price_6month'] != $price_6month) {
					$arr_edit["Giá gói 6 tháng"] = $core->get_field($more_information, "price_6month", $price_6month);
				}		
				if($more_information['price_year'] != $price_year) {
					$arr_edit["Giá gói 12 tháng"] = $core->get_field($more_information, "price_year", $price_year);
				}		
				if($more_information['day_trial'] != $day_trial) {
					$arr_edit["Số ngày dùng thử gói"] = $core->get_field($more_information, "day_trial", $day_trial);
				}		
				if($more_information['show_pricing'] != $show_pricing) {
					$arr_edit["Trạng thái hiển thị gói"] = $core->get_field($more_information, "show_pricing", $show_pricing);
				}		
				if($more_information['trial_terms'] != $trial_terms) {
					$arr_edit["Điều khoản dùng thử gói"] = $core->get_field($more_information, "trial_terms", $trial_terms);
				}
				$more_information['price_month'] = $price_month;
				$more_information['price_3month'] = $price_3month;
				$more_information['price_6month'] = $price_6month;
				$more_information['price_year'] = $price_year;
				$more_information['day_trial'] = $day_trial;
				$more_information['package_intro'] = $package_intro;
				$more_information['show_pricing'] = $show_pricing;
				$more_information['trial_terms'] = $trial_terms;
				$title_log = "%s đã sửa gói tài khoản %s";
			} else if($property_type == '_BILLING_TYPE'){
				$project_director_id = (int) Input::post('project_director_id', 0);
				if($more_information['project_director_id'] != $project_director_id) {
					$arr_edit["Giám đốc dự án"] = $core->get_field($more_information, "project_director_id", $project_director_id);
				}
				if($more_information['deposit'] != $deposit) {
					$arr_edit["Số tiền cọc"] = $core->get_field($more_information, "deposit", $deposit);
				}
				$more_information['deposit'] = $deposit;
				$more_information['project_director_id'] = $project_director_id;
				$list_group_zalo = Input::post('group_zalo', array());
				$more_information['list_group_zalo'] = $list_group_zalo;
				$title_log = "%s đã sửa loại hình giao dịch %s";
			} else if($property_type == '_BUSINESS_AREA'){
				$regional_director_id = Input::post('regional_director_id');
				$more_information['regional_director_id'] = $regional_director_id;
			} else if($property_type == '_GROUPSIZE'){
				$size_group = (int) Input::post('size_group', 0);
				if($more_information['size_group'] != $size_group) {
					$arr_edit["Số thành viên"] = $core->get_field($more_information, "size_group", $size_group);
				}
				$more_information['size_group'] = $size_group;				
				$title_log = "%s đã sửa nhóm thành viên %s";
			} else if($property_type == '_FEATURE_MOC'){
				$link = Input::post('link', "");
				if($more_information['link'] != $link) {
					$arr_edit["Đường dẫn"] = $core->get_field($more_information, "link", $link);
				}
				$more_information['link'] = $link;
				$title_log = "%s đã sửa tính năng trên MOC %s";
			} else if(in_array($property_type, array('_PRICE_RANGE_SOP','_PRICE_RANGE_LEASING','_AREA_RANGE'))){				
				$min  = Input::post('min');
				$max  = Input::post('max');
				if($more_information['min'] != $min) {
					$arr_edit["Giá trị min"] = $core->get_field($more_information, "min", $min); 
				}
				if($more_information['max'] != $max) {
					$arr_edit["Giá trị max"] = $core->get_field($more_information, "max", $max);
				}
				$more_information['min'] = str_replace(".","",$min);
				$more_information['max'] = str_replace(".","",$max);
				if($property_type == "_PRICE_RANGE_SOP"){
					$title_log = "%s đã sửa khoảng giá chuyển nhượng MOC %s";
				}elseif($property_type == "_PRICE_RANGE_LEASING"){
					$title_log = "%s đã sửa khoảng giá cho thuê MOC %s";
				}elseif($property_type == "_AREA_RANGE"){
					$title_log = "%s đã sửa khoảng diện tích MOC %s";
				}	
			} else if($property_type=='_REPORT_TEMPLATE'){
				$unit_name = Input::post('unit_name');
				$permiss_role = Input::post('permiss_role');
				if($more_information['unit_name'] != $unit_name) {
					$arr_edit["Đơn vị"] = $core->get_field($more_information, "unit_name", $unit_name);
				}
				if($more_information['permiss_role'] != $permiss_role) {
					$arr_edit["Vai trò"] = $core->get_field($more_information, "permiss_role", $permiss_role);
				}
				$more_information['unit_name'] = $unit_name;
				$more_information['permiss_role'] = $permiss_role;
				$title_log = "%s đã sửa mẫu báo cáo %s";
			} else if($property_type=='_TRANSACTION_PROJECT'){
				$project_director_id = (int) Input::post('project_director_id', 0);
				$project_admin_id = (int) Input::post('project_admin_id', 0);
				if($more_information['project_director_id'] != $project_director_id) {
					$_oStaffOld = $_oStaffNew = array();
					if(!empty($more_information['project_director_id'])) {
						$_oStaffOld = $clsProfile->getOne($more_information['project_director_id'],"code,full_name");
					}elseif(!empty($project_director_id)) {
						$_oStaffNew = $clsProfile->getOne($project_director_id,"code,full_name");
					}					
					$arr_edit["Giám đốc dự án"] = !empty($_oStaffOld) ? ( $_oStaffOld['code']." ". $_oStaffOld['full_name']) : ( $_oStaffNew['code']." ". $_oStaffNew['full_name']);
				}
				if($more_information['project_admin_id'] != $project_admin_id) {
					$_oStaffOld = $_oStaffNew = array();
					if(!empty($more_information['project_admin_id'])) {
						$_oStaffOld = $clsProfile->getOne($more_information['project_admin_id'],"code,full_name");
					}elseif(!empty($project_admin_id)) {
						$_oStaffNew = $clsProfile->getOne($project_admin_id,"code,full_name");
					}					
					$arr_edit["Admin dự án"] = !empty($_oStaffOld) ? ( $_oStaffOld['code']." ". $_oStaffOld['full_name']) : ( $_oStaffNew['code']." ". $_oStaffNew['full_name']);
				}
				$more_information['project_admin_id'] = $project_admin_id;
				$more_information['project_director_id'] = $project_director_id;
				$title_log = "%s đã sửa dự án xác nhận giao dịch %s";
			} else if($property_type == '_GROUP_RANGE_VHGG'){
				$list_ranges = Input::post('list_ranges');
				$list_blocks = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' and `for_id`='"._PROJECT_VHGG_ID."' order by order_no ASC", "{$clsProperty->pkey},title");
				if($more_information['list_ranges'] != $list_ranges) {
					$arr_edit["Đơn vị"] = !empty($more_information['unit_name']) ? $more_information['unit_name'] : $unit_name;
				}
				$more_information['list_ranges'] = $list_ranges;
				$title_log = "%s đã sửa nhóm phân Khu VHGG %s";
			} else if($property_type == '_ULTILITIES'){
				$link = Input::post('link');
				$attr = Input::post('attr');
				$group = Input::post('group');
				$role = Input::post('role',array());
				$icon = Input::post('icon');
				$more_information['link'] = $link;
				$more_information['attr'] = $attr;
				$more_information['role'] = $role;
				$more_information['group'] = $group;
				$more_information['icon'] = $icon;
				$title_log = "%s đã sửa tiện ích hệ thông %s";
			} else if($property_type == '_DEPARTMENT'){
				$head_of_dep_id = (int) Input::post('head_of_dep_id', 0);
				$is_business_area = (int) Input::post('is_business_area', 0);
				$office_id = (int) Input::post('office_id', 0);
				$more_information['head_of_dep_id'] = $head_of_dep_id;
				$more_information['is_business_area'] = $is_business_area;
				$more_information['office_id'] = $office_id;
			} else if($property_type == '_OPS_COST_CAT'){
				$ms_value = Input::post('ms_value', 0);
				$office_id = (int) Input::post('office_id', 0);
				$office_cost_cat_id = (int) Input::post('office_cost_cat_id', 0);
				$more['ms_value'] = $clsISO->processSmartNumber($ms_value);
				$more_information['office_id'] = $office_id;
				$more_information['office_cost_cat_id'] = $office_cost_cat_id;
			} else if($property_type == '_CATEGORY_DOCS'){
				$view = Input::post('view', "grid");
				$more_information['view'] = $view;
			} else if($property_type == 'CUSTOMER_STATUS'){
				$is_funnel_active = (int) Input::post('is_funnel_active', 0);
				$more_information['is_funnel_active'] = $is_funnel_active;
			}
			if($clsProperty->updateOne($property_id, array_merge($more, array(
				'property_type'	=> $property_type,
				'property_code'	=> $property_code,
				'parent_id'	=> $parent_id,
				'for_id' => $for_id,
				'title'	=> $title,
				'slug'	=> $core->replaceSpace($title),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'intro'	=> Input::post('intro'),
                'icon' => $propery_icon,
				'image'	=> Input::post('image'),
				'bgcolor'	=> Input::post('bgcolor'),
				'textcolor'	=> Input::post('textcolor'),
				'upd_date'	=> time(),
				'user_id_update' => $user_id
			)))) {
				#activity log		
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Property","update",["property_type"=>"$property_type"]);
			}
		}
	}else{
		$cond = "is_trash=0 and parent_id='{$parent_id}' and property_type='{$property_type}'";


		if($for_id > 0) $cond .= " and for_id='{$for_id}'";
		if($clsProperty->countItem("{$cond} and slug='{$slug}'") > 0){
			echo '_invalid'; 
			die();
		}else{
			$more = $more_information = array();
			$property_id = $clsProperty->getMaxId();
			if(in_array($property_type, array('_TYPE_VILLA','_DIRECTION','_AGENCY','_REPORT_TEMPLATE','_BILLING_TYPE'))){
				$title_vn = Input::post('title_vn');
				$more['title_vn'] = $title_vn;
				$more['slug_vn'] = $core->replaceSpace($title_vn);
				if($property_type == "_TYPE_VILLA"){
					$title_log = "%s đã thêm mới loại hình thấp tầng %s";
				}elseif($property_type == "_DIRECTION"){
					$title_log = "%s đã thêm mới hướng nhà %s";
				}
			}
			if($property_type=='_AGENCY'){
				$group_zalo = Input::post('group_zalo');
				$group_zalo_lowrise = Input::post('group_zalo_lowrise');
				$spreadsheetId = Input::post('spreadsheetId');
				$stock_status_id = Input::post('stock_status_id');
				if(!$stock_status_id) $stock_status_id = _STOCK_STATUS_LOCK_ID;
				$hide_stock_globe = Input::post('hide_stock_globe', 0);
				$stock_sheet_configs = Input::post('stock_sheet_configs');
				$folder_price_sheets = Input::post('folder_price_sheets');
				$MOC_content = Input::post('MOC_content');
				$info_agency = Input::post('info_agency', array());
				$more_information['stock_sheet_configs'] = $stock_sheet_configs;
				$more_information['group_zalo'] = $group_zalo;
				$more_information['group_zalo_lowrise'] = $group_zalo_lowrise;
				$more_information['spreadsheetId'] = $spreadsheetId;
				$more_information['stock_status_id'] = $stock_status_id;
				$more_information['hide_stock_globe'] = $hide_stock_globe;
				$more_information['folder_price_sheets'] = $folder_price_sheets;
				$more_information['MOC_content'] = $MOC_content;
				$more_information['info_agency'] = $info_agency;
				$title_log = "%s đã thêm mới đại lý %s";
			} else if($property_type=='_BEDROOM'){
				$image_interior_title_pa1 = Input::post('image_interior_title_pa1');
				$image_interior_link_pa1 = Input::post('image_interior_link_pa1');
				$image_interior_title_pa2 = Input::post('image_interior_title_pa2');
				$image_interior_link_pa2 = Input::post('image_interior_link_pa2');
				$video_interior_ns = Input::post('video_interior_ns');
				$bathroom = Input::post('bathroom');
				$ms_value = (int)Input::post('ms_value', 0);
				$more['ms_value'] = $ms_value;
				$more_information['image_interior_title_pa1'] = $image_interior_title_pa1;
				$more_information['image_interior_link_pa1'] = $image_interior_link_pa1;
				$more_information['image_interior_title_pa2'] = $image_interior_title_pa2;
				$more_information['image_interior_link_pa2'] = $image_interior_link_pa2;
				$more_information['video_interior_ns'] = $video_interior_ns;
				$more_information['bathroom'] = $bathroom;
				$title_log = "%s đã thêm mới loại phòng ngủ %s";
			} else if($property_type=='_PACKAGE'){
				$price_month = (int)Input::post('price_month',0);
				$price_3month = (int)Input::post('price_3month',0);
				$price_6month = (int)Input::post('price_6month',0);
				$price_year = (int)Input::post('price_year',0);
				$package_intro = Input::post('package_intro');
				$show_pricing = (int)Input::post('show_pricing',0);
				$trial_terms = Input::post('trial_terms');
				$more_information['price_month'] = $price_month;
				$more_information['price_3month'] = $price_3month;
				$more_information['price_6month'] = $price_6month;
				$more_information['price_year'] = $price_year;
				$more_information['package_intro'] = $package_intro;
				$more_information['show_pricing'] = $show_pricing;
				$more_information['trial_terms'] = $trial_terms;
				$title_log = "%s đã thêm mới gói tài khoản %s";
			} else if($property_type == '_BILLING_TYPE'){
				$project_director_id = (int) Input::post('project_director_id', 0);
				$more_information['project_director_id'] = $project_director_id;
				$list_group_zalo = Input::post('group_zalo', "");
				$more_information['list_group_zalo'] = $list_group_zalo;
				$title_log = "%s đã thêm mới loại hình giao dịch %s";
			} else if($property_type == '_BUSINESS_AREA'){
				$regional_director_id = Input::post('regional_director_id');
				$more_information['regional_director_id'] = $regional_director_id;
			} else if($property_type == '_OPS_COST_CAT'){
				$ms_value = Input::post('ms_value', 0);
				$office_id = (int) Input::post('office_id', 0);
				$office_cost_cat_id = (int) Input::post('office_cost_cat_id', 0);
				$more['ms_value'] = $clsISO->processSmartNumber($ms_value);
				$more_information['office_id'] = $office_id;
				$more_information['office_cost_cat_id'] = $office_cost_cat_id;
			} else if($property_type == '_GROUPSIZE'){
				$size_group = (int) Input::post('size_group', 0);
				$more_information['size_group'] = $size_group;			
				$title_log = "%s đã thêm mới nhóm thành viên %s";
			} else if($property_type == '_FEATURE_MOC'){
				$link = Input::post('link', "");
				$more_information['link'] = $link;
				$title_log = "%s đã thêm mới tính năng trên MOC %s";
			} else if(in_array($property_type, array('_PRICE_RANGE_SOP','_PRICE_RANGE_LEASING','_AREA_RANGE'))){			
				$min  = Input::post('min');
				$max  = Input::post('max');
				$more_information['min'] = str_replace(".","",$min);
				$more_information['max'] = str_replace(".","",$max);
				if($property_type == "_PRICE_RANGE_SOP"){
					$title_log = "%s đã thêm mới khoảng giá chuyển nhượng MOC %s";
				}elseif($property_type == "_PRICE_RANGE_LEASING"){
					$title_log = "%s đã thêm mới khoảng giá cho thuê MOC %s";
				}elseif($property_type == "_AREA_RANGE"){
					$title_log = "%s đã thêm mới khoảng diện tích MOC %s";
				}	
			} else if($property_type=='_REPORT_TEMPLATE'){
				$unit_name = Input::post('unit_name');
				$permiss_role = Input::post('permiss_role');
				$more_information['unit_name'] = $unit_name;
				$more_information['permiss_role'] = $permiss_role;
				$title_log = "%s đã thêm mới mẫu báo cáo %s";
			} else if($property_type=='_TRANSACTION_PROJECT'){
				$project_admin_id = (int) Input::post('project_admin_id', 0);
				$project_director_id = (int) Input::post('project_director_id', 0);
				$more_information['project_admin_id'] = $project_admin_id;
				$more_information['project_director_id'] = $project_director_id;
				$title_log = "%s đã thêm mới dự án xác nhận giao dịch %s";
			} else if($property_type == '_GROUP_RANGE_VHGG'){
				$list_ranges = Input::post('list_ranges');
				$more_information['list_ranges'] = $list_ranges;
				$title_log = "%s đã thêm mới nhóm phân Khu VHGG %s";
			} else if($property_type == '_ULTILITIES'){
				$link = Input::post('link');
				$more_information['link'] = $link;
				$group = Input::post('group');
				$more_information['group'] = $group;
				$role = Input::post('role',array());
				$more_information['role'] = $role;
				$icon = Input::post('icon');
				$more_information['icon'] = $icon;
				$title_log = "%s đã thêm mới tiện ích hệ thông %s";
			} else if($property_type == '_DEPARTMENT'){
				$head_of_dep_id = (int) Input::post('head_of_dep_id', 0);
				$is_business_area = (int) Input::post('is_business_area', 0);			
				$office_id = (int) Input::post('office_id', 0);
				$more_information['head_of_dep_id'] = $head_of_dep_id;
				$more_information['is_business_area'] = $is_business_area;
				$more_information['office_id'] = $office_id;
			} else if($property_type == '_CATEGORY_DOCS'){
				$view = Input::post('view', "grid");
				$more_information['view'] = $view;
			} else if($property_type == 'CUSTOMER_STATUS'){
				$is_funnel_active = (int) Input::post('is_funnel_active', 0);
				$more_information['is_funnel_active'] = $is_funnel_active;
			}
			if($clsProperty->insert(array_merge($more, array(
				'property_id'	=> $property_id,
				'property_type'	=> $property_type,
				'property_code' => $property_code,
				'parent_id'	=> $parent_id,
				'for_id' => $for_id,
				'title'	=> $title,
				'slug'	=> $slug,
				'intro'	=> Input::post('intro'),
				'image'	=> Input::post('image'),
                'icon' => $propery_icon,
				'bgcolor'	=> Input::post('bgcolor'),
				'textcolor'	=> Input::post('textcolor'),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'reg_date'	=> time(),
				'upd_date'	=> time(),
				'user_id'	=> $user_id,
				'user_id_update'	=> $user_id,
				'order_no'	=> $clsProperty->getMaxOrderNo()
			)))) {			
				#activity log		
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Property","insert",["property_type"=>"$property_type"]);
			}
		}
	}
	// Return
	echo($property_id); die();
}
function default_load_select_property(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	####
	$for_id = (int) Input::post('for_id', 0);
	$property_id = Input::post('property_id');
	$property_type = Input::post('property_type');
	$title = "";
	if($property_type=='_PROJECT') $title = "Chọn dự án";
	if($property_type=='_BLOCK') $title = "Chọn phân khu";
	if($property_type=='_BUILDING') $title = "Chọn tòa nhà";
	####
	if($for_id > 0){
		$html = $clsProperty->getSelectByPropertyOrigin($property_type, $for_id, $property_id, $title);
	} else {
		$html = $clsProperty->getSelectByProperty($property_type, $for_id, $property_id, $title);
	}
	// return
	echo $html; die();
}
function default_hide_stock_globe(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$user_id = $core->_USER['user_id'];
	###
	$clsProperty = new Property();
	$to_field = Input::post('to_field', 'hide_stock_globe');
	$property_id = (int) Input::post('property_id',0,true);
	$status = (int) Input::post('status',0,true);
	#
	$msg = "_error";
	$more_information = $clsProperty->getOneField('more_information', $property_id);
	$more_information = $clsISO->to_array_json($more_information);
	$more_information[$to_field] = $status;
	if($clsProperty->updateOne($property_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_save_menu(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsMenu = new Menu();
	$menu_id = (int) Input::post('menu_id',0);
	$lstLink = $clsMenu->getLinks($menu_id);
	// Action
	$orderNo = Input::post('orderNo');
	for($i=0; $i<count($orderNo); $i++){
		$clsMenu->updateOne($orderNo[$i], array(
			'id'	=> ($i+1)	
		));
	}
}
function default_delete_blobe(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$user_id = $core->_USER['user_id'];
	$pkey = Input::post('pkey');
	$pval_id = (int) Input::post('pval_id', 0);
	$clsTable = Input::post('clsTable');
	
	$msg = '_error';
	if(!empty($clsTable) && $pval_id > 0){
		$clsClassTable = new $clsTable();
		if(method_exists($clsClassTable, 'doDelete')){
			if($clsClassTable->doDelete($pval_id)){
				$msg = '_success';
			}
		} else {
			if($clsClassTable->deleteOne($pval_id)){
				$msg = '_success';
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_get_select_city(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	
	$clsCity = new City();
	$country_id = (int) Input::post('country_id',0);
	$html = $clsCity->makeSelectOption($country_id,0);
	// Return
	echo $html; die();
}
function default_get_select_district(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	
	$clsDistrict = new District();
	$city_id = (int) Input::post('city_id',0);
	$html = $clsDistrict->makeSelectOption($city_id,0);
	// Return
	echo $html; die();
}
function default_storage_cache(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$property_type = Input::post("property_type","");
	###
	$msg = "_error";
	if(!empty($property_type)){
		$msg = "_success";
		if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){
			$cache = new Cache();
			$field = "{$clsProperty->pkey},`title`,`title_vn`,`property_code`,`slug`,`image`,`parent_id`,`intro`,`more_information`,`textcolor`,`bgcolor`,`is_trash`";
			$tmp = $clsProperty->getAll("`is_trash`=0 and `property_type`='{$property_type}' order by `order_no` ASC", $field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$tmp[$key]['more_information'] = $more_information;
				}
			}
			$cache->set($property_type, json_encode($tmp, JSON_UNESCAPED_UNICODE));
		} else {
			$cachedName = sprintf('%s.json', $property_type);
			$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($tblData, $lstProperty); 
		}
	}
	// Return
	echo $msg; die();
}
function default_storage_cache_all(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$cache = new Cache();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$list = $clsProperty->getAll("`property_type`<>'' GROUP BY `property_type`","property_type");
	// $clsISO->print_pre($list); die();
	if(!empty($list)){
		$msg = "_success";
		if(defined('CACHE_DRIVER') && CACHE_DRIVER == 'REDIS'){
			foreach ($list as $k => $v) {
				$property_type = $v['property_type'];
				$field = "{$clsProperty->pkey},`title`,`title_vn`,`property_code`,`slug`,`image`
				,`parent_id`,`intro`,`more_information`,`textcolor`,`bgcolor`";
				$tmp = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_type`='{$property_type}' order by `order_no` ASC", $field);
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$tmp[$key]['more_information'] = $more_information;
					}
				}
				$cache->set($property_type, $tmp);
				unset($lstProperty);
			}			
		}
	}
	// Return
	echo $msg; die();
}
function default_open_setting(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsConfiguration = new Configuration();
	$smarty->assign('clsConfiguration', $clsConfiguration);
	###
	$settings = array();
	$mod_page = Input::post('mod_page', '');
	if($mod_page=='sop'){
		$settings[] = 'sop_moderation';
	} else if($mod_page=='leasing'){
		$settings[] = 'leasing_moderation';
	}
	$arr_setting = $clsConfiguration->getValues($settings);
	$smarty->assign('mod_page', $mod_page);
	$smarty->assign('arr_setting', $arr_setting);
	// Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('setting.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_save_setting(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsConfiguration = new Configuration();
	
	$msg = "_error";
	$mod_page = Input::post('mod_page');
	if($mod_page=='sop'){
		$sop_moderation = (int) Input::post('sop_moderation',0);
		$clsConfiguration->updateValue('sop_moderation', $sop_moderation);
	} else if($mod_page=='leasing'){
		$leasing_moderation = (int) Input::post('leasing_moderation',0);
		$clsConfiguration->updateValue('leasing_moderation', $leasing_moderation);
	}
	$msg = "_success";
	// Return
	echo $msg; die();
}
function default_ajOpenBannerStock(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_frontIsLoggedin_user_id,$core,$clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	###
//	$clsISO->print_pre($_POST);die;
	$action= '_add';
	$project_id = (int) Input::post('project_id',0);
	$banner_stock_id = Input::post('banner_stock_id', "");
	$oneItem = $list_buildings = array();
	$uid = $clsISO->getUniqid();
	###
	$titlePage = "Thêm mới";
	if(!empty($banner_stock_id)){
		$action = '_edit';
		$titlePage = "Sửa";
		$banner_stock = $clsProject->getOneField('banner_stock', $project_id);
		$banner_stock = $clsISO->to_array_json($banner_stock);
		$oneItem = $banner_stock[$banner_stock_id];
	}
	// TitlePage
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('banner_stock_id', $banner_stock_id);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('action', $action);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('uid', $uid);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.banner_stock.tpl');
	echo $html; die();
}
function default_saveBannerStock(){ 
	global $_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
	$clsUser = new User();
	$clsProject = new Project();
	$clsStock = new Stock();
	#
	$res = array(
		"result"	=>	false,
		"msg"		=>	"ERROR"
	);
	$project_id = (int) Input::post('project_id',0);
	$banner_stock_id = Input::post('banner_stock_id', "");
	$ms_code = trim(Input::post('ms_code', ""));
	$image = Input::post('image', "");
	$oneStock = $clsStock->getByCond("`ms_code`='{$ms_code}'");
	if(!empty($oneStock)) {
		$banner_stock = $clsProject->getOneField('banner_stock', $project_id);
		$banner_stock = !empty($banner_stock) ? json_decode($banner_stock, true) : array();
		###
		if(Input::exists('action','GET') && Input::get('action')=='delete'){
			unset($banner_stock[$banner_stock_id]);
			if($clsProject->updateOne($project_id, array(
				'banner_stock' => json_encode($banner_stock, JSON_UNESCAPED_UNICODE)
			))){
				$res = array(
					"result"	=>	true,
					"msg"		=>	"Xóa thành công!"
				);
			}
		} else if(Input::exists('action','GET') && Input::get('action')=='update_status'){
			$status_id = (int)Input::post("status_id",0);
			$banner_stock[$banner_stock_id]['status_id'] = $status_id;
			if($clsProject->updateOne($project_id, array(
				'banner_stock' => json_encode($banner_stock, JSON_UNESCAPED_UNICODE)
			))){
				$res = array(
					"result"	=>	true,
					"msg"		=>	"Xóa thành công!"
				);
			}
		} else {
			$link_image = "";
			$msg = "Cập nhật thành công!";
			if(empty($banner_stock_id)){
				$banner_stock_id = $clsISO->getUniqid();
				$msg = "Thêm mới thành công!";
			}		
			$banner_stock[$banner_stock_id] = array(
				'banner_stock_id' 	=> $banner_stock_id,
				'ms_code'			=> $ms_code,
				'image'				=> $image,
			);
			if($clsProject->updateOne($project_id, array(
				'banner_stock' => json_encode($banner_stock, JSON_UNESCAPED_UNICODE)
			))){
				$res = array(
					"result"	=>	true,
					"msg"		=>	$msg
				);
			}
		}
	}else{
		$res = array(
			"result"	=>	false,
			"msg"		=>	"Mã căn không tồn tại"
		);
	}
	
	// output
	echo json_encode($res); die();
}
function default_ajLoadListBannerStock(){
	global $_frontIsLoggedin_user_id,$datastore_folder,$core,$clsISO;
	$clsUser = new User();
	$clsProperty = new Property();
	$clsProject = new Project();
	$project_id = Input::post('project_id',0);
	$uid = $clsISO->getUniqid();
	$html = '<div class="'.$uid.' freeze-table dragscroll">
	<table class="table table-stripped" style="width:1800px; white-space:nowrap">
		<thead><tr>
			<th class="text-left">Mã căn</th>
			<th class="text-center" width="10%">Hình ảnh</th>
			<th class="text-center" width="10%">Hiển thị</th>
			<th width="10%"></th>
		</tr></thead>';
	$banner_stock = $clsProject->getOneField('banner_stock', $project_id);
	$lstBannerStock = !empty($banner_stock) ? json_decode($banner_stock, true) : array();
//	 $clsISO->print_pre($lstUtilities); die();
	$array_cache_block = $array_cache_cat = [];
	if(!empty($lstBannerStock)){ $ii=0; // init
		foreach($lstBannerStock as $key => $val){
			if(!isset($array_cache_block[$_oUtilities['block_id']])) {
				$array_cache_block[$val['block_id']] = $clsProperty->getTitle($val['block_id']);
			}
			if(!isset($array_cache_cat[$val['cat_id']])) {
				$array_cache_cat[$val['cat_id']] = $clsProperty->getTitle($val['cat_id']);
			}
			$checked = ($val['status_id'] == 1) ? "checked" : "";
			$html .= '<tr>
				<td class="fieldarea right_click">'.($ii+1).'. '.$val['ms_code'].'</td>
				<td class="fieldarea right_click"><img src="'.$val["image"].'" width="50" height="50" /></td>
				<td class="fieldarea right_click">
					<label class="switch">
						<input type="checkbox" onchange="$Core.global.project.status_banner_stock(this, event)" value="1" banner_stock_id="'.$key.'" ms_code="'.$val['ms_code'].'" project_id="'.$project_id.'" '.$checked.'>
						<span class="slider round"></span>
					</label>
				</td>
				<td class="text-center">
					<button class="btn btn-xs btn-default" project_id="'.$project_id.'" banner_stock_id="'.$key.'" onclick="$Core.global.project.open_banner(this,event)">'.$core->makeIcon('pencil').'</button>
					<button class="btn btn-xs btn-default deleteDocShare" project_id="'.$project_id.'" ms_code="'.$val['ms_code'].'" banner_stock_id="'.$key.'" onclick="$Core.global.project.deleteBannerStock(this,event)">'.$core->makeIcon('trash').'</button>
				</td>
			</tr>';
			++$ii;
		}
		unset($lstUtilities);
	}
	$html .= '</table>
	</div>';
	// Output
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_crawl_doc_sheet_by_link(){
	ini_set('memory_limit', '5048M');
    global $core,$clsISO,$dbconn,$smarty;
	#
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawl = new Crawl();
	###
	$action = Input::post('action', "_SAVE");
	$project_id = (int) Input::post('project_id', 0);
	$config_stock	= Input::post('config_stock', array());
    if (!empty($config_stock) && !empty($project_id)) {
		// $oneProject = $clsProject->getOne($project_id,"more_information");
		$more_information = $clsProject->getOneField('more_information', $project_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information["config_stock"] = $config_stock;
		if($clsProject->updateOne($project_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		)) && $action == "_SAVE"){
			$data_return = [
				'status' => 200,
				'msg' => 'Lưu cấu hình thành công',
			];
			echo json_encode($data_return,JSON_UNESCAPED_UNICODE);die;
		}
		// _PREVIEW dựng đúng bộ dữ liệu mà _CREATE sẽ ghi, nhưng không đụng vào DB,
		// để user soát lại trên popup trước khi thật sự khởi tạo các căn thấp tầng.
		$is_preview = ($action == "_PREVIEW");
		if($action == "_CREATE" || $is_preview) {
			$spreadsheetId = !empty($config_stock["spreadsheet_id"]) ? $config_stock["spreadsheet_id"] : "";
			$gid = !empty($config_stock["sheet_id"]) ? $config_stock["sheet_id"] : 0;
			$sheet_name = !empty($config_stock["sheet_name"]) ? $config_stock["sheet_name"] : "";
			$convertFrom = !empty($config_stock["convert_from"]) ? $config_stock["convert_from"] : array();
			$convertTo = !empty($config_stock["convert_to"]) ? $config_stock["convert_to"] : array();
			$stock_template = !empty($config_stock["stock_template"]) ? $config_stock["stock_template"] : "";
			$stock_template = str_replace(" ","",$stock_template);
			$convert_arr = [];
			if (!empty($convertTo) && !empty($convertFrom)) {
				foreach ($convertFrom as $keyC => $valueC) {
					if (isset($convertTo[$keyC])) {
						$convert_arr[trim($valueC)] = trim($convertTo[$keyC]);
					}
				}
			}
			$time = time();
			$user_id = $core->_USER['user_id'];    
			if (!empty($spreadsheetId) && $stock_template != "") {
				$url = "https://docs.google.com/spreadsheets/d/".$spreadsheetId."/export?format=csv&gid=".$gid;
				### get data
				$data = file_get_contents($url);
				$rows = array_map("str_getcsv", explode("\n", $data));
				array_shift($rows);
				### Lấy tất cả các property thuộc _DIRECTION, _TYPE_VILLA, _BLOCK (theo project_id)
				$types_arr = array(_TYPE_DIRECTION_PROPERTY, _TYPE_VILLA_PROPERTY);
				$type_string = "'" . implode("','", $types_arr) . "'";
//				$dbconn->debug=true;
				$types_in_property = $clsProperty->getAll("`is_trash`=0 AND ((`property_type` IN ({$type_string}) OR (`property_type`='"._TYPE_BLOCK_PROPERTY."' AND `for_id`='{$project_id}' AND `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."')))");
//				$clsISO->print_pre($types_in_property);die;
				### khởi tạo mảng
				$type_low_floor_key_by_code = []; // mảng ds loại thấp tầng có key là property code
				$type_low_floor_key_by_id = []; // mảng ds laoji thấp tầng có key là property_id
				$type_low_floor_key_by_slug = []; // mảng ds laoji thấp tầng có key mảng là slug
				$home_direct_type_key_by_slug = []; // mảng ds hướng có key mảng là slug
				$list_blocks_by_code = []; // mảng ds phân khu có key mảng là property code
				$list_builings_by_code = []; // mảng ds tòa/dãy có key là property_code
				$list_builings_by_block_id = []; // mảng ds tòa/dãy có key là for_id
				$arr_block_ids = []; // mảng block_id
				$property_title_by_id = []; // mảng tên property có key là property_id, dùng cho bảng xem trước
				### process cho từng mảng
				if (!empty($types_in_property)) {
					foreach ($types_in_property as $type_property) {
						$propety_code = $type_property['property_code'] ?? '';
						$property_id = $type_property['property_id'] ?? 0;
						$propety_slug = $type_property['slug'] ?? '';
						$property_type = $type_property['property_type'];
						$property_title_by_id[$property_id] = $type_property['title'] ?? '';
						if ($property_type == _TYPE_VILLA_PROPERTY) {
							$type_low_floor_key_by_code[$propety_code] = $type_property;
							$type_low_floor_key_by_id[$property_id] = $type_property;
							$type_low_floor_key_by_slug[$propety_slug] = $type_property;
						}
						if ($property_type == _TYPE_DIRECTION_PROPERTY) {
							$home_direct_type_key_by_slug[$propety_slug] = $type_property;
						}
						if ($property_type == _TYPE_BLOCK_PROPERTY) {
							$list_blocks_by_code[$propety_code] = $type_property;
							$arr_block_ids[] = $property_id;
						}
					}
				}
//				$clsISO->print_pre($type_low_floor_key_by_code);die;
				### Tòa/Dãy
				$block_ids_string = "'" . implode("','", $arr_block_ids) . "'";
//				$dbconn->debug=true;
				$list_ranges = $clsProperty->getAll("is_trash = 0 AND property_type = '".TYPE_RANGE_PROPERTY."' AND for_id IN ({$block_ids_string})");
//				$clsISO->print_pre($list_ranges);die;
				foreach ($list_ranges as $range) {
					$list_builings_by_code[$range['property_code']] = $range;
					$list_builings_by_block_id[$range['for_id']][] = $range;
					$property_title_by_id[$range['property_id']] = $range['title'] ?? '';
				}
				$arr_ms_code_file = array_column($rows, 4); // lấy ds mã căn trong file
				$data_save = $data_update = $arr_stock_project = [];
				$lstStockProject = $clsStock->getAll("`is_trash`=0 AND `project_id` = '{$project_id}' AND `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'");
				$arr_stock_update = [];
				if(!empty($lstStockProject)) {
					foreach ($lstStockProject as $key => $val) {
						$ms_code = isset($convert_arr[$val["code"]]) ? str_replace($val["code"],$convert_arr[$val["code"]],$val['ms_code']) : $val['ms_code'];
						
						$matches = [];
						if(!preg_match('/(.*?)(\d+[A-Za-z]?)$/', $ms_code, $matches)){
							// ms_code cũ không có phần số ở cuối -> không tái tạo được, bỏ qua
							continue;
						}
						// Cắt ký tự ngăn cách cuối tiền tố, nếu không mẫu "[MaDay]-[CanHo]" sẽ chèn
						// thêm dấu lần nữa ("HG-01" -> prefix "HG-" -> "HG--01") và không khớp được.
						$prefix = rtrim($matches[1], "-_ .");
						$num = $matches[2];
						$num = isset($convert_arr[$clsISO->parseNumber($num)]) ? $convert_arr[$clsISO->parseNumber($num)] : $num;
						//$ms_code = $prefix . $num;
						$ms_code = str_replace("[MaDay]",$prefix,$stock_template);
						$ms_code = str_replace("[CanHo]",$num,$ms_code);
						$arr_stock_project[$val[$clsStock->pkey]] = $ms_code;
						$arr_stock_update[$val[$clsStock->pkey]] = $val;
						unset($ms_code);
					}
				}
				$total_upd = 0;
				$kc = 0;
				$failed = [];
				$preview_rows = []; // ds dòng render ở popup xem trước
				$preview_new_no = 0; // bộ đếm sinh khóa tạm cho căn mới khi xem trước (chưa có stock_id thật)
				$total_insert = 0;
				$total_update = 0;
				$total_error = 0; // số căn có dữ liệu chưa khớp, còn lỗi thì chặn không cho tạo
				foreach ($rows as $row) {
					$range_value = isset($row[4]) ? trim($row[4]) : "";
					$build_code = isset($row[2]) ? trim($row[2]) : "";
					if ($range_value === "") {
						continue;
					}
//					preg_match('/(.*?)(\d+[A-Za-z]?)$/', $range_value, $matches);
//					$clsISO->print_pre($range_value);die;
					$list_codes = [];
					if (strpos($range_value, '>') !== false) {
						$arr = explode('>', $range_value);
						$start_full = $arr[0];
						$start_full = $clsCrawl->getCodeNotTemplate($start_full);
						$end_full = $arr[1];
						$matches = [];
						$prefix = $build_code;
						$start = $start_full;
						$build_code_norm = $clsCrawl->getCodeNotTemplate($build_code);
						if ($build_code_norm != "" && strpos($start, $build_code_norm) === 0) {
							$start = substr($start, strlen($build_code_norm));
						}
						if (!ctype_digit($start)) {
							$start = preg_match('/(\d+)$/', $start, $matches_start) ? $matches_start[1] : "";
						}
						$step = 1;
						$subs = ["lẻ", "le", "l", "chẵn", "chan", "c"];
						$end_lower = trim($end_full);
						$end_lower = function_exists('mb_strtolower') ? mb_strtolower($end_lower, 'UTF-8') : strtolower($end_lower);
						foreach ($subs as $sub) {
							if (strpos($end_lower, $sub) !== false) {
								$step = 2;
								break;
							}
						}
						$end = preg_replace('/[^0-9]/', '', $end_full);

						if ($start !== "" && $end !== "") {
							$start_int = (int)$start;
							$end_int = (int)$end;

							for ($i = $start_int; $i <= $end_int; $i+=$step) {
								$num = isset($convert_arr[$clsISO->parseNumber($i)]) ? $convert_arr[$clsISO->parseNumber($i)] : str_pad($i, strlen($start), '0', STR_PAD_LEFT);
								//$ms_code = $prefix . $num;								
								$ms_code = str_replace("[MaDay]",$build_code,$stock_template);
								$ms_code = str_replace("[CanHo]",$num,$ms_code);
								$list_codes[$ms_code] = $num;
							}
						}
					} else {						
//						==================
						$build_code = trim($row[2]);
						$prefix = $build_code;
						$num = substr_replace($range_value, '', 0, strlen($build_code));
						$num = preg_replace('/^[^0-9A-Za-z]+/u', '', $num);
						$num = isset($convert_arr[$clsISO->parseNumber($num)]) ? $convert_arr[$clsISO->parseNumber($num)] : $num;

						//$ms_code = $prefix . $num;
						$ms_code = str_replace("[MaDay]",$build_code,$stock_template);
						$ms_code = str_replace("[CanHo]",$num,$ms_code);
						$list_codes[$ms_code] = $num;
					}
						
					// Tra không ra thì để 0, không để '': các cột này là INT nên MySQL strict mode
					// từ chối chuỗi rỗng và cả dòng bị mất trắng.
					$block_id = isset($list_blocks_by_code[trim($row[1])])
						? $list_blocks_by_code[trim($row[1])]['property_id'] : 0;
					$building_id = isset($list_builings_by_code[trim($row[2])])
						? $list_builings_by_code[trim($row[2])]['property_id'] : 0;
					$building_is_new = false;
					if(empty($building_id) && !empty($block_id) && trim($row[2]) != "") {
						$check = $clsProperty->getByCond("`property_type`='_RANGE' AND `for_id`='{$block_id}' AND `property_code`=".$dbconn->qstr(trim($row[2])),$clsProperty->pkey);
						if(!$check) {
							$building_is_new = true;
							$property_code = trim($row[2]);
							$title = trim($row[3]);
							// Xem trước thì không được đẻ property mới, chỉ đánh dấu để popup báo "sẽ tạo mới"
							if(!$is_preview) {
								$building_id = $clsProperty->getMaxId();
								$clsProperty->insert(array(
									$clsProperty->pkey => $building_id,
									'property_type' => '_RANGE',
									'property_code' => $property_code,
									'for_id' => $block_id,
									'title' => $title,
									'slug' => $core->replaceSpace($title),
									'order_no' => $clsProperty->getMaxOrderNo(),
									'reg_date' => time(),
									'upd_date' => time(),
									'user_id' => $core->_USER['user_id'],
									'user_id_update' => $core->_USER['user_id'],
								));
							}
						}else{
							$building_id = $check[$clsProperty->pkey];
						}
					}
					
					$type = 0;
					if(!empty($row[5])){
						$type = isset($type_low_floor_key_by_code[trim($row[5])]) 
							? $type_low_floor_key_by_code[trim($row[5])]['property_id'] 
							: (isset($type_low_floor_key_by_slug[$core->replaceSpace($row[5])]) 
							? $type_low_floor_key_by_slug[$core->replaceSpace($row[5])]['property_id'] : 0);
					}	
					$direct_slug = $core->replaceSpace(trim($row[8]));
					$direct = isset($home_direct_type_key_by_slug[$direct_slug])
						? $home_direct_type_key_by_slug[$direct_slug]['property_id'] : 0;
					$DT_TT = !empty(trim($row[6])) ? $clsISO->formatNumber2(trim($row[6])) : 0;
					$DT_Tim = !empty(trim($row[7])) ? $clsISO->formatNumber2(trim($row[7])) : '';
					// $clsISO->print_pre($more_information_save); die();
					// Nhãn + cảnh báo cho popup xem trước: tính 1 lần cho mỗi dòng sheet, dùng lại cho mọi mã căn của dòng đó
					// $preview_errors chặn nút tạo, $preview_notes chỉ để báo cho biết
					$preview_labels = [];
					$preview_errors = [];
					$preview_notes = [];
					if ($is_preview) {
						$raw_block = isset($row[1]) ? trim($row[1]) : "";
						$raw_building = isset($row[2]) ? trim($row[2]) : "";
						$raw_type = isset($row[5]) ? trim($row[5]) : "";
						$raw_direct = isset($row[8]) ? trim($row[8]) : "";
						// Không tra ra property thì hiển thị nguyên giá trị trên sheet để user biết chỗ nào lệch
						$preview_labels = [
							'block' => !empty($property_title_by_id[$block_id]) ? $property_title_by_id[$block_id] : $raw_block,
							'building' => !empty($property_title_by_id[$building_id]) ? $property_title_by_id[$building_id] : $raw_building,
							'type' => !empty($property_title_by_id[$type]) ? $property_title_by_id[$type] : $raw_type,
							'direction' => !empty($property_title_by_id[$direct]) ? $property_title_by_id[$direct] : $raw_direct,
						];
						if (empty($block_id)) {
							$preview_errors[] = 'Chưa khớp phân khu';
						}
						// Tòa/dãy chưa có sẽ được tự tạo lúc chạy thật nên không tính là lỗi
						if ($building_is_new) {
							$preview_notes[] = 'Tòa/dãy sẽ được tạo mới';
						} else if (empty($building_id) && $raw_building != "") {
							$preview_errors[] = 'Chưa khớp tòa/dãy';
						}
						if (empty($type) && $raw_type != "") {
							$preview_errors[] = 'Chưa khớp loại căn';
						}
						if (empty($direct) && $raw_direct != "") {
							$preview_errors[] = 'Chưa khớp hướng';
						}
					}
					$arr_building_cached = [];
					foreach($list_codes as $key_code=>$code) {
						$row_act = '';
						$stock_id = array_search($key_code, $arr_stock_project, true);
						if (!empty($stock_id)) {
							$more_information_save = $clsISO->to_array_json($arr_stock_update[$stock_id]["more_information"]);
							$more_information_save["DT_TT"] = $DT_TT;
							$more_information_save["DT_Tim"] = $DT_Tim;
							$more_information_save["home_direction_id"] = $direct;
							$more_information_save["type_id"] = $type;
							$ms_code = $key_code;
							$data_update = [
								'stock_type' => _BLOCK_TYPE_LOWFLOOR_SALE,
								'project_id' => $project_id,
								'block_id' => $block_id,
								'building_id' => $building_id,
								'DT_TT' => $DT_TT,
								'ms_code' => $ms_code,
								'code' => $code,
								'type_id' => $type,
								'more_information' => json_encode($more_information_save, JSON_UNESCAPED_UNICODE),
								'home_direction_id' => $direct,
								'user_id_update' => $user_id,
								'upd_date' => $time,
							];
							$data_save[] = $data_update;
							$row_act = 'update';
							++$total_update;
//							echo "update {$key_code}<br>";
//							$clsISO->print_pre($data_update);
							 if (!$is_preview) {
								 if($clsStock->updateOne($stock_id, $data_update)){
									 $kc ++;
								 } else {
									 $failed[] = [
										 'act' => 'update',
										 'ms_code' => $key_code,
										 'stock_id' => $stock_id,
										 'error' => $dbconn->ErrorMsg()
									 ];
//									 echo "<br>";
//									 echo "Không update được ms_code: " . $key_code . " -> id: " . $stock_id;
//									 echo "<br>";
								 }
							 }
						} else if(trim($key_code) != ""){							
							$more_information_save = [
								'DT_TT' => $DT_TT,
								'DT_Tim' => $DT_Tim,
								'home_direction_id' => $direct,
								'type_id' => $type
							];
							// Xem trước chưa được cấp id thật, dùng khóa tạm để vẫn nhận diện được mã trùng ở các dòng sau
							$new_stock_id = $is_preview ? '_preview_'.(++$preview_new_no) : $clsStock->getMaxId();
							$data_create = [
								"{$clsStock->pkey}"	=>	$new_stock_id,
								'stock_type' => _BLOCK_TYPE_LOWFLOOR_SALE,
								'project_id' => $project_id,
								'block_id' => $block_id,
								'building_id' => $building_id,
								'DT_TT' => $DT_TT,
								'ms_code' => $key_code,
								'code' => $code,
								'type_id' => $type,
								'more_information' => json_encode($more_information_save, JSON_UNESCAPED_UNICODE),
								'home_direction_id' => $direct,
								'user_id' => $user_id,
								'user_id_update' => $user_id,
								'reg_date' => $time,
								'upd_date' => $time,
							];
							$data_save[] = $data_create;
							$row_act = 'insert';
							++$total_insert;
//							echo "insert <br>";
//							$clsISO->print_pre($data_create);
//							$dbconn->debug=true;
							 if($is_preview){
								 // Ghi nhận mã vừa dựng: dòng sheet sau ra trùng mã sẽ hiện là cập nhật, đúng như luồng tạo thật
								 $arr_stock_project[$new_stock_id] = $key_code;
								 $arr_stock_update[$new_stock_id] = $data_create;
							 } else if($clsStock->insert($data_create)){
								 $kc ++;
								 // Ghi nhận mã vừa tạo: dòng sheet sau ra trùng mã sẽ update chứ không insert lại
								 $arr_stock_project[$new_stock_id] = $key_code;
								 $arr_stock_update[$new_stock_id] = $data_create;
							 } else {
								 $failed[] = [
									 'act' => 'insert',
									 'ms_code' => $key_code,
									 'stock_id' => $new_stock_id,
									 'error' => $dbconn->ErrorMsg()
								 ];
//								  echo "<br>";
//								 echo "Không insert được ms_code: " . $key_code;
//								 $clsISO->print_pre($data_create);
//								 echo "<br>";
							 }
						}
						if($is_preview && $row_act != ''){
							$preview_rows[] = array_merge($preview_labels, [
								'act' => $row_act,
								'ms_code' => $key_code,
								'code' => $code,
								'DT_TT' => $DT_TT,
								'DT_Tim' => $DT_Tim,
								'errors' => $preview_errors,
								'notes' => $preview_notes
							]);
							if(!empty($preview_errors)){
								++$total_error;
							}
						}
					}
				}
				if($is_preview){
					$preview_total = count($preview_rows);
					$smarty->assign('preview_rows', $preview_rows);
					$smarty->assign('preview_total', $preview_total);
					$smarty->assign('total_insert', $total_insert);
					$smarty->assign('total_update', $total_update);
					$smarty->assign('total_error', $total_error);
					$data_return = [
						'status' => 200,
						'msg' => 'Đã dựng dữ liệu xem trước',
						'total_generated' => $preview_total,
						'total_insert' => $total_insert,
						'total_update' => $total_update,
						'total_error' => $total_error,
						'html' => $core->build('preview_low_floor.tpl')
					];
				} else {
					$msg = 'Tạo bảng hàng thành công: '.$kc.'/'.count($data_save).' căn';
					if(!empty($failed)){
						$msg .= ', lỗi: '.count($failed).' căn';
					}
					$data_return = [
						'status' => 200,
						'msg' => $msg,
						'total_generated' => count($data_save),
						'total_saved' => $kc,
						'total_failed' => count($failed),
						'failed' => $failed,
						'data' => $data_save
					];
				}
			} else {
				$data_return = [
					'status' => 400,
					'msg' => "Bạn chưa nhập vào Google Sheet ID",
				];
			}
		}
    } else {
        $data_return = [
            'status' => 400,
            'msg' => 'Cần chọn sheet cấu hình',
        ];
    }
	// Dấu hiệu build: đặt lên đầu response để không phải cuộn qua mảng data mới thấy
	$data_return = array_merge(array('build' => '2026-07-30-fix-le-chan'), $data_return);
    echo json_encode($data_return); die();
}
function default_open_setting_update_table(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID, $profile_id;
	$clsProject = new Project();
    $project_id = (int) Input::post('project_id', 0);
	if(!empty($project_id)) {
		$oneProject = $clsProject->getOne($project_id,"more_information");
		$more_information = $clsISO->to_array_json($oneProject["more_information"]);
		$config_stock = !empty($more_information["config_stock"]) ? $more_information["config_stock"] : array();
		$assign_list['config_stock'] = $config_stock;
	}
	$uid = $clsISO->getUniqid();
	$smarty->assign('core',$core);
    $assign_list['project_id'] = $project_id;
    $assign_list['uid'] = $uid;
	$callback  = '$(\'#inputor\').atwho({at: \'%\',
		data:[\'[MaDay]\', \'[CanHo]\']
	});';
    $html = $core->build('upload_low_floor.tpl');
	echo json_encode(array(
		'callback' => $callback,
		'html' => $html,
	)); die();
}
function default_open_sheet(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	$clsTemporary = new Temporary();
	$clsCrawl = new Crawl();
	##
	$uid = Input::post('uid');
	$gId = Input::post('gId');
	$stock_type = (int)Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$spreadsheetId = Input::post('spreadsheetId');
	$arr_worksheets = $list_worksheets = array();
	if(!empty($spreadsheetId)){
		if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			// $clsISO->print_pre($matches); die();
			$spreadsheetId = $matches[0];
		}
		/** Required Lib */
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes([Google_Service_Drive::DRIVE]);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		try {
			$resource_id = $spreadsheetId;
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$list_worksheets = $spreadsheet->sheets;
		} catch(Exception $ex){
			$msg_error = $ex->getMessage();
			if(json_decode($msg_error)->error->status == "FAILED_PRECONDITION"){
				$spreadsheetIdCopy = $clsCrawl->copySpreadsheet($spreadsheetId, [],0, 0,0);
				$resource_id = $spreadsheetIdCopy;
				$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
				$list_worksheets = $spreadsheet->sheets;
			}				
		}
		if(!empty($list_worksheets)){
			foreach($list_worksheets as $sheet){
				$id = $sheet->properties['sheetId'];   
				$name = $sheet->properties['title']; 
				$arr_worksheets[$id] = $name;
			}
		}
	}
	$smarty->assign('gId', $gId);
	$smarty->assign('stock_type', $stock_type);
	$smarty->assign('spreadsheetId', $spreadsheetId);
	$smarty->assign('arr_worksheets', $arr_worksheets);
	// Return
	$html = $core->build('_ajax.open_sheet.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_saveStatusContract(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID, $profile_id;
	$clsProject = new Project();
	$clsProperty = new Property();
    $clsTable = Input::post('tp', "Project");
	$clsClassTable = new $clsTable;
    $for_id = (int)Input::post('for_id', 0);
    $status_contract = (int)Input::post('status_contract', 0);
	$res = ["result" => false];
	if($for_id > 0) {
		$oneItem = $clsClassTable->getOne($for_id);
		if(!empty($oneItem)) {
			$more_information = $clsISO->to_array_json($oneItem["more_information"]);
			$more_information["status_contract"] = $status_contract;
			if($clsClassTable->updateOne($for_id, array(
				"more_information" => json_encode($more_information)
			))){
				$res = ["result" => true];
			}
		}
		
	}
	echo json_encode($res,JSON_UNESCAPED_UNICODE); die();
}
?>