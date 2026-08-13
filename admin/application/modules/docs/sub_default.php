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
# Options phân khu của (nhiều) dự án — dùng chung cho lúc mở form và lúc AJAX đổi dự án,
# nhờ vậy markup 2 nơi luôn giống nhau.
function docs_getBlockOptions($project_ids, $selected_ids = array(), $is_multiple = 0){
	global $clsISO;
	$clsProject = new Project();
	$clsProperty = new Property();
	$html = $is_multiple ? "" : '<option value="0">Chọn phân khu</option>';
	$project_ids = array_values(array_unique(array_filter(array_map('intval', (array) $project_ids))));
	if(empty($project_ids)) return $html;
	$field = "{$clsProperty->pkey},for_id,title";
	$cond = "property_type='_BLOCK' and for_id IN(".implode(',', $project_ids).") order by `order_no` ASC";
	$list_blocks = $clsProperty->getAll($cond, $field);
	if(empty($list_blocks)) return $html;
	$grouped = array();
	foreach($list_blocks as $val){
		$grouped[$val['for_id']][] = $val;
	}
	# Nhiều dự án → gom optgroup để phân biệt phân khu trùng tên giữa các dự án
	$has_group = count($project_ids) > 1;
	foreach($project_ids as $project_id){
		if(empty($grouped[$project_id])) continue;
		if($has_group) {
			$html.= '<optgroup label="'.htmlspecialchars($clsProject->getTitle($project_id), ENT_QUOTES).'">';
		}
		foreach($grouped[$project_id] as $val){
			$block_id = $val[$clsProperty->pkey];
			$selected = $clsISO->checkItemInArray($block_id, (array) $selected_ids) ? ' selected="selected"' : '';
			$html.= '<option value="'.$block_id.'"'.$selected.'>'.htmlspecialchars($val['title'], ENT_QUOTES).'</option>';
		}
		if($has_group) $html.= '</optgroup>';
	}
	return $html;
}
# Options toà nhà của (nhiều) phân khu
function docs_getBuildingOptions($block_ids, $selected_ids = array(), $is_multiple = 0){
	global $clsISO;
	$clsProperty = new Property();
	$html = $is_multiple ? "" : '<option value="0">Chọn toà nhà</option>';
	$block_ids = array_values(array_unique(array_filter(array_map('intval', (array) $block_ids))));
	if(empty($block_ids)) return $html;
	$field = "{$clsProperty->pkey},for_id,title";
	$cond = "property_type='_BUILDING' and for_id IN(".implode(',', $block_ids).") order by `order_no` ASC";
	$list_buildings = $clsProperty->getAll($cond, $field);
	if(empty($list_buildings)) return $html;
	$grouped = array();
	foreach($list_buildings as $val){
		$grouped[$val['for_id']][] = $val;
	}
	$has_group = count($block_ids) > 1;
	foreach($block_ids as $block_id){
		if(empty($grouped[$block_id])) continue;
		if($has_group) {
			$html.= '<optgroup label="'.htmlspecialchars($clsProperty->getTitle($block_id), ENT_QUOTES).'">';
		}
		foreach($grouped[$block_id] as $val){
			$building_id = $val[$clsProperty->pkey];
			$selected = $clsISO->checkItemInArray($building_id, (array) $selected_ids) ? ' selected="selected"' : '';
			$html.= '<option value="'.$building_id.'"'.$selected.'>'.htmlspecialchars($val['title'], ENT_QUOTES).'</option>';
		}
		if($has_group) $html.= '</optgroup>';
	}
	return $html;
}
# Dự án chính (cột project_id) — không còn chỗ nào đọc, chỉ ghi để cột legacy không bị lệch
# với project_ids (phòng khi query tay). Giữ nguyên khi vẫn được chọn, chỉ đổi khi bị bỏ chọn.
function docs_getPrimaryProjectId($project_ids_arr, $current_project_id = 0){
	$current_project_id = (int) $current_project_id;
	if($current_project_id > 0 && in_array($current_project_id, $project_ids_arr)) {
		return $current_project_id;
	}
	return !empty($project_ids_arr) ? $project_ids_arr[0] : 0;
}
# Danh sách dự án của 1 tài liệu — đọc từ project_ids (mọi row đã backfill từ project_id)
function docs_getProjectIds($oneItem){
	global $clsISO;
	return !empty($oneItem['project_ids']) ? $clsISO->getArrayByTextSlash($oneItem['project_ids']) : array();
}
# Tài liệu dùng chung nhiều dự án thì không cho xoá — xoá 1 phát là các dự án kia mất theo.
# Trả về "" nếu được phép xoá, ngược lại là lý do để hiện cho người dùng.
function docs_getBlockDeleteReason($project_meta_id){
	$clsProjectMeta = new ProjectMeta();
	$oneItem = $clsProjectMeta->getOne((int) $project_meta_id, "project_ids");
	$project_ids = docs_getProjectIds($oneItem);
	if(count($project_ids) <= 1) return "";
	$clsProject = new Project();
	$names = array();
	foreach($project_ids as $project_id){
		$names[] = $clsProject->getTitle($project_id);
	}
	return sprintf('Tài liệu đang thuộc %d dự án (%s). Hãy sửa tài liệu, bỏ bớt dự án rồi mới xoá được.',
		count($project_ids), implode(', ', $names));
}
# Bổ sung tên dự án/phân khu/toà/danh mục cho bảng danh sách (dùng chung trang list và AJAX phân trang)
function docs_decorateList($list_docs){
	global $clsISO;
	if(empty($list_docs)) return $list_docs;
	$clsProject = new Project();
	$clsProperty = new Property();
	$arrCategoryDocs = $clsProperty->getArraySearchByKey("_CATEGORY_DOCS");
	$arr_cache_project = $arr_cache_block = $arr_cache_building = array();
	foreach($list_docs as $key => $val){
		$tags = $val['tags'];
		$list_docs[$key]['list_tags'] = !empty($tags) ? explode(',', $tags) : array();
		$list_docs[$key]['image'] = !empty($val['thumb_image']) ? $val['thumb_image'] : '';
		$list_docs[$key]['file_type'] = !empty($val['file_type']) ? $val['file_type'] : '';
		# Dự án: gộp tên của tất cả dự án tài liệu đang thuộc về
		$txt_project_name = "";
		foreach(docs_getProjectIds($val) as $project_id){
			if(!isset($arr_cache_project[$project_id])) {
				$arr_cache_project[$project_id] = $clsProject->getTitle($project_id);
			}
			$txt_project_name.= ((($txt_project_name != "") ? ", " : "").$arr_cache_project[$project_id]);
		}
		$list_docs[$key]['project_name'] = $txt_project_name;
		$txt_block_name = "";
		if(!empty($val["block_ids"])) {
			$block_ids = $clsISO->getArrayByTextSlash($val['block_ids']);
			foreach($block_ids as $block_id){
				if(!isset($arr_cache_block[$block_id])) {
					$arr_cache_block[$block_id] = $clsProperty->getTitle($block_id);
				}
				$txt_block_name.= ((($txt_block_name != "") ? ", " : "").$arr_cache_block[$block_id]);
			}
		}
		$list_docs[$key]['block_name'] = $txt_block_name;
		$txt_building_name = "";
		if(!empty($val["building_ids"])) {
			$building_ids = $clsISO->getArrayByTextSlash($val['building_ids']);
			foreach($building_ids as $building_id){
				if(!isset($arr_cache_building[$building_id])) {
					$arr_cache_building[$building_id] = $clsProperty->getTitle($building_id);
				}
				$txt_building_name.= ((($txt_building_name != "") ? ", " : "").$arr_cache_building[$building_id]);
			}
		}
		$list_docs[$key]['building_name'] = $txt_building_name;
		$list_docs[$key]['cat_name'] = !empty($arrCategoryDocs[$val['cat_id']]) ? $arrCategoryDocs[$val['cat_id']]["title"] : "";
	}
	return $list_docs;
}
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	$clsProject = new Project();
	$clsProperty = new Property();
	/**/
	$classTable = "ProjectMeta";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["classTable"] = $classTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	#
	$clsProject = new Project();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$assign_list["list_projects"] = $list_projects;
	
	
	$cmd = Input::get("cmd","");
	$project_id = (int)Input::get("project_id",0);
	$block_id = (int)Input::get("block_id",0);
	$building_id = (int)Input::get("building_id",0);
	$cat_id = (int)Input::get("cat_id",0);
	$keyword = Input::get('keyword', "");
	$type = Input::get('type', "");
	$type_list = Input::get('type_list', "");

	$assign_list["project_id"] = $project_id;
	$assign_list["block_id"] = $block_id;
	$assign_list["building_id"] = $building_id;
	$assign_list["cat_id"] = $cat_id;
	$assign_list["keyword"] = $keyword;
	$assign_list["type_list"] = $type_list;

	$script = "";
	if($cmd == "open") {
		$script = '<script>setTimeout(function(){
			$(".js_create_add").trigger("click");
		},500);</script>';
	}
	$assign_list["script"] = $script;
	
	
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$project_id = Input::post('project_id', 0);
		$block_id = Input::post('block_id', 0);
		$building_id = Input::post('building_id', 0);
		$cat_id = Input::post('cat_id', 0);
		$keyword = Input::post('keyword', "");
		$type = Input::post('type', "");
		$type_list = Input::post('type_list', "");

		$link = "";
		if($project_id > 0) $link.= "&project_id=".$project_id;
		if($block_id > 0) $link.= "&block_id=".$block_id;
		if($building_id > 0) $link.= "&building_id=".$building_id;
		if($keyword != "") $link.= "&keyword=".$keyword;
		if($cat_id > 0) $link.= "&cat_id=".$cat_id;
		if($type != "") $link.= "&type=".$type;
		if($type_list != "") $link.= "&type_list=".$type_list;
		// var_dump($_POST); die();
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&act='.$act.$link);
		exit();
	}
	
	#
	$cond = ($type_list=='trash') ? "`is_trash`=1" : "`is_trash`=0";
	if(!empty($project_id)){
		$cond.= " and ".$clsProjectMeta->condByProject($project_id);
		$pUrl.='&project_id='.$project_id;

		$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'", "{$clsProperty->pkey},title");
		$assign_list["list_blocks"] = $list_blocks;
	}
	if(!empty($block_id)){
		$cond.= " and `id` IN (SELECT meta_id FROM ".DB_PREFIX."project_meta_block WHERE block_id='{$block_id}')";
		$pUrl.='&block_id='.$block_id;
		
		$list_buildings = $clsProperty->getAll("property_type='_BUILDING' and for_id='{$block_id}'", "{$clsProperty->pkey},title");
		$assign_list["list_buildings"] = $list_buildings;
	}
	if(!empty($building_id)){
		$cond.= " and `id` IN (SELECT meta_id FROM ".DB_PREFIX."project_meta_building WHERE building_id='{$building_id}')";
		$pUrl.='&building_id='.$building_id;
	}
	if(!empty($type)){
		$cond.= " and `type`='{$type}'";
		$pUrl.='&type='.$type;
	}
	if(!empty($keyword)) {
		$cond .= " and (`title` like '%{$keyword}%' or `slug` like '%{$core->replaceSpace($keyword)}%' or `title_search` like '%{$keyword}%')";
		$pUrl.='&keyword='.$keyword;
	}
	if(!empty($cat_id)) {
		$cond .= " and (`cat_id` = '{$cat_id}' or `list_cat_id` like '%|{$cat_id}|%')";
		$pUrl.='&cat_id='.$cat_id;
	}
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 20;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsProjectMeta->countItem($cond);
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
	
	$list_docs = $clsProjectMeta->getAll($cond." order by reg_date DESC".$limit);
	$list_docs = docs_decorateList($list_docs);
	$assign_list["list_docs"] = $list_docs;

}
function default_load_docs(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	#
	$type_list = Input::post('type_list', "");
	$cond = ($type_list=='trash') ? "`is_trash`=1" : "`is_trash`=0";
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$building_id = (int) Input::post('building_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	$keyword = Input::post('keyword', "");
	$type = Input::post('type', "");
	$current_page = (int) Input::post('page', 1);
	$per_page = 20;
	if(!empty($project_id)){
		$cond.= " and ".$clsProjectMeta->condByProject($project_id);
	}
	if(!empty($block_id)){
		$cond.= " and `id` IN (SELECT meta_id FROM ".DB_PREFIX."project_meta_block WHERE block_id='{$block_id}')";
	}
	if(!empty($building_id)){
		$cond.= " and `id` IN (SELECT meta_id FROM ".DB_PREFIX."project_meta_building WHERE building_id='{$building_id}')";
	}
	if(!empty($type)){
		$cond.= " and `type`='{$type}'";
	}
	if(!empty($keyword)) {
		$cond .= " and (`title` like '%{$keyword}%' or `slug` like '%{$core->replaceSpace($keyword)}%' or `title_search` like '%{$keyword}%')";
	}
	if(!empty($cat_id)) {
		$cond .= " and (`cat_id` = '{$cat_id}' or `list_cat_id` like '%|{$cat_id}|%')";
	}
	$total_record = $clsProjectMeta->countItem($cond);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page-1) * $per_page;
	$list_docs = $clsProjectMeta->getAll($cond." order by reg_date DESC limit {$offset},{$per_page}");
	$list_docs = docs_decorateList($list_docs);
	$smarty->assign('list_docs', $list_docs);
	$smarty->assign('type_list', $type_list);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.docs.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_record' => $total_record,
		'total_page' => $total_page,
		'current_page' => $current_page
	)); die();
}
function default_open(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	#
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$smarty->assign('list_projects', $list_projects);
	#
	$action = "_add";
	$titlePage = "Thêm tài liệu";
	$project_meta_id = (int) Input::post('project_meta_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$project_ids = !empty($project_id) ? array($project_id) : array();
	$block_id = (int) Input::post('block_id', 0);
	$block_ids = $building_ids = [];
	if(!empty($block_id)) {
		$block_ids[] = $block_id;
	}

	$building_id = (int) Input::post('building_id', 0);
	if(!empty($building_id)) {
		$building_ids[] = $building_id;
	}
	$cat_id = (int) Input::post('cat_id', 0);
	$oneItem = $more_information = array(
		'is_expanded' => 1
	);


	if($project_meta_id > 0){
		$action = "_edit";
		$titlePage = "Sửa tài liệu";
		$oneItem = $clsProjectMeta->getOne($project_meta_id);
		$project_ids = docs_getProjectIds($oneItem);
		$block_ids = !empty($oneItem["block_ids"]) ? $clsISO->getArrayByTextSlash($oneItem["block_ids"]) : array();
		$building_ids = !empty($oneItem["building_ids"]) ? $clsISO->getArrayByTextSlash($oneItem["building_ids"]) : array();
		$type = $oneItem['type'];
		$more_information = $oneItem['more_information'];
		$more_information = !empty($more_information)
			? json_decode(html_entity_decode($more_information), true)
			: array();
	}
//	$clsISO->print_pre($more_information);die;
	# Phân khu/toà nhà phụ thuộc dự án đang chọn — dựng sẵn options bằng cùng helper mà AJAX dùng
	$smarty->assign('project_ids', $project_ids);
	$smarty->assign('html_block_options', docs_getBlockOptions($project_ids, $block_ids, 1));
	$smarty->assign('html_building_options', docs_getBuildingOptions($block_ids, $building_ids, 1));
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('action', $action);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('project_meta_id', $project_meta_id);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_save(){
	// error_reporting(E_ALL);
	// ini_set('display_errors',1);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	$clsProperty = new Property();
	
	$msg = "_error";
	$title = Input::post('title',"");
	$cat_id = (int)Input::post('cat_id',0);
	$project_meta_id = (int) Input::post('project_meta_id', 0);
	$project_ids = Input::post('project_ids',array()); // Array — 1 tài liệu có thể thuộc nhiều dự án
	if(empty($project_ids)) $project_ids = Input::post('project_id', 0); // tương thích chỗ gọi cũ (1 dự án)
	$project_ids_arr = array_values(array_unique(array_filter(array_map('intval', (array) $project_ids))));
	$list_project_ids = $clsISO->makeSlashListFromArrayRoot($project_ids_arr);
	$block_ids = Input::post('block_ids',array()); // Array
	$building_ids = Input::post('building_ids',array()); // Array
	$block_ids_arr = array_values(array_filter(array_map('intval', (array) $block_ids)));     // cho junction
	$building_ids_arr = array_values(array_filter(array_map('intval', (array) $building_ids)));
	$tags = Input::post('tags');
	$intro = Input::post('intro');
	$image = Input::post('image');
	$content = Input::post('content');
	$folder_id = Input::post('folder_id');
	$is_expanded = (int) Input::post('is_expanded', 0);
	$is_model = (int) Input::post('is_model', 0);
	$is_handoverSpecs = (int) Input::post('is_handoverSpecs', 0);
	$list_cat_id = $clsProperty->getListParent($cat_id,"_CATEGORY_DOCS");
	$type = 'project';
	if(!empty($block_ids) && empty($building_ids)){
		$type = 'block';
	} else if(!empty($block_ids) && !empty($building_ids)){
		$type = 'building';
	}
	$arr_tag = !empty($tags) ? explode(",",$tags) : array();
	$arr_tags_slug = [];
	if(!empty($arr_tag)) {
		foreach ($arr_tag as $tag) {
			$arr_tags_slug[] = 	$core->replaceSpace($tag);
		}	
	}
//	$project_meta_id = 0;
	// title_search = tiêu đề + tên dự án/phân khu/toà/danh mục/tags (cho tìm kiếm)
	$title_search = trim($title);
	foreach($project_ids_arr as $_pid){ $title_search .= ' '.$clsProject->getTitle($_pid); }
	if(!empty($block_ids) && is_array($block_ids)){ foreach($block_ids as $_bid){ if($_bid) $title_search .= ' '.$clsProperty->getTitle($_bid); } }
	if(!empty($building_ids) && is_array($building_ids)){ foreach($building_ids as $_bid){ if($_bid) $title_search .= ' '.$clsProperty->getTitle($_bid); } }
	if(!empty($cat_id)) $title_search .= ' '.$clsProperty->getTitle($cat_id);
	if(!empty($tags)) $title_search .= ' '.$tags;
	if($project_meta_id==0){
		$project_id = docs_getPrimaryProjectId($project_ids_arr);
		$more_information = array();
		$more_information['intro'] = $intro;
		$more_information['folder_id'] = $folder_id;
		$more_information['is_expanded'] = $is_expanded;
		$more_information['is_model'] = $is_model;
		$more_information['is_handoverSpecs'] = $is_handoverSpecs;
		try { $more = $clsProjectMeta->crawl($content); } catch(\Throwable $e){ $more = array(); } // Drive lỗi vẫn lưu record
		if(!empty($image) && file_exists(ROOTPATH . $image)){
			$more_information['image'] = $image;
			unset($more['image']);
		}
		$more_information = array_merge($more_information, $more);
		// Cột "nóng" (dual-write — vẫn giữ trong JSON)
		$col_file_type = isset($more_information['type']) ? $more_information['type'] : '';
		$col_image = isset($more_information['image']) ? $more_information['image'] : '';
		$col_gg_id = isset($more_information['gg_id']) ? $more_information['gg_id'] : '';

		$more_type = [
			"project_id" =>	$project_id,
			"project_ids" =>	$project_ids_arr,
			"block_ids" =>	$block_ids,
			"building_ids" =>	$building_ids,
		];
		$block_ids = $clsISO->makeSlashListFromArrayRoot($block_ids);
		$building_ids = $clsISO->makeSlashListFromArrayRoot($building_ids);

		$order_no = $clsProjectMeta->getMaxOrderNo();
		$new_id = $clsProjectMeta->getMaxId();
		if($clsProjectMeta->insert(array(
			$clsProjectMeta->pkey => $new_id,
			'type' => $type,
			'for_id' => 0,
			'project_id' => $project_id,
			'project_ids' => $list_project_ids,
			'block_ids' => $block_ids,
			'building_ids' => $building_ids,
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'title_search' => $title_search,
			'cat_id' => $cat_id,
			'list_cat_id' => $list_cat_id,
			'content' => $content,
			'file_type' => $col_file_type,
			'thumb_image' => $col_image,
			'gg_id' => $col_gg_id,
			'is_model' => $is_model,
			'is_handover' => $is_handoverSpecs,
			'tags' => $tags,
			'user_id' => $user_id,
			'user_update_id' => $user_id,
			'tags_slug' => $clsISO->makeSlashListFromArrayRoot($arr_tags_slug),
			'more_information' => json_encode(array_merge($more_information, $more_type), JSON_UNESCAPED_UNICODE),
			'order_no' => $order_no,
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$msg = "_success";
			$clsProjectMeta->syncRelations($new_id, $block_ids_arr, $building_ids_arr, $arr_tags_slug, $arr_tag);
			#activity log
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("ProjectMeta","insert");
		}
		
	} else {
		$oneOld = $clsProjectMeta->getOne($project_meta_id, "project_id,content,more_information");
		$project_id = docs_getPrimaryProjectId($project_ids_arr, $oneOld['project_id']);
		$more_information = $clsISO->to_array_json($oneOld['more_information']);
		$more_information['intro'] = $intro;
		$more_information['folder_id'] = $folder_id;
		$more_information['is_expanded'] = $is_expanded;
		$more_information['is_model'] = $is_model;
		$more_information['is_handoverSpecs'] = $is_handoverSpecs;
		$more_information["project_id"] = $project_id;
		$more_information["project_ids"] = $project_ids_arr;
		$more_information["block_ids"] = $block_ids;
		$more_information["building_ids"] = $building_ids;
		// Chỉ crawl lại Google Drive khi link (content) thay đổi → tránh gọi Drive thừa
		if(trim($content) !== trim($oneOld['content'])){
			try { $more = $clsProjectMeta->crawl($content); } catch(\Throwable $e){ $more = array(); }
			$more_information = array_merge($more_information, $more);
		}
		if(!empty($image) && file_exists(ROOTPATH . $image)){
			$more_information['image'] = $image;
		}
		$col_file_type = isset($more_information['type']) ? $more_information['type'] : '';
		$col_image = isset($more_information['image']) ? $more_information['image'] : '';
		$col_gg_id = isset($more_information['gg_id']) ? $more_information['gg_id'] : '';

		$block_ids = $clsISO->makeSlashListFromArrayRoot($block_ids);
		$building_ids = $clsISO->makeSlashListFromArrayRoot($building_ids);
//		$clsISO->print_pre($more_information);die;
//		 $dbconn->debug=true;
		if($clsProjectMeta->updateOne($project_meta_id, array(
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'title_search' => $title_search,
			'type' => $type,
			'project_id' => $project_id,
			'project_ids' => $list_project_ids,
			'block_ids' => $block_ids,
			'building_ids' => $building_ids,
			'content' => $content,
			'file_type' => $col_file_type,
			'thumb_image' => $col_image,
			'gg_id' => $col_gg_id,
			'is_model' => $is_model,
			'is_handover' => $is_handoverSpecs,
			'cat_id' => $cat_id,
			'list_cat_id' => $list_cat_id,
			'tags' => $tags,
			'tags_slug' => $clsISO->makeSlashListFromArrayRoot($arr_tags_slug),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_update_id' => $user_id,
			'upd_date' => time()
		))){
			$msg = "_success";
			$clsProjectMeta->syncRelations($project_meta_id, $block_ids_arr, $building_ids_arr, $arr_tags_slug, $arr_tag);
		}
	}
	// Return
	echo $msg; die();
}
function default_delete(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsProjectMeta = new ProjectMeta();
	#
	$msg = "_error";
	$project_meta_id = (int) Input::post('project_meta_id', 0);
	$message = docs_getBlockDeleteReason($project_meta_id);
	if($message == "" && $clsProjectMeta->updateOne($project_meta_id, array('is_trash'=>1,'upd_date'=>time()))){
		$msg = "_success";
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("ProjectMeta","trash");
	}
	// Return
	echo json_encode(array('msg' => $msg, 'message' => $message)); die();
}
function default_restore(){
	global $core,$clsISO;
	$clsProjectMeta = new ProjectMeta();
	$msg = "_error";
	$project_meta_id = (int) Input::post('project_meta_id', 0);
	if($clsProjectMeta->updateOne($project_meta_id, array('is_trash'=>0,'upd_date'=>time()))){
		$msg = "_success";
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("ProjectMeta","restore");
	}
	echo $msg; die();
}
function default_force_delete(){
	global $core,$clsISO;
	$clsProjectMeta = new ProjectMeta();
	$msg = "_error";
	$project_meta_id = (int) Input::post('project_meta_id', 0);
	$message = docs_getBlockDeleteReason($project_meta_id);
	if($message == "" && $clsProjectMeta->deleteOne($project_meta_id)){
		$msg = "_success";
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("ProjectMeta","delete");
	}
	echo json_encode(array('msg' => $msg, 'message' => $message)); die();
}
function default_delete_all(){
	global $core,$clsISO;
	$clsProjectMeta = new ProjectMeta();
	$msg = "_error"; $message = "";
	$type_list = Input::post('type_list', "");
	$p_key = Input::post('p_key', array());
	if(!is_array($p_key)) $p_key = array($p_key);
	# Chặn cả lô nếu có tài liệu dùng chung — xoá một phần rồi báo lỗi thì người dùng không biết cái nào đã mất
	$ids = array(); $total_blocked = 0;
	foreach($p_key as $id){
		$id = (int) $id;
		if($id <= 0) continue;
		if(docs_getBlockDeleteReason($id) != ""){
			$total_blocked++;
			continue;
		}
		$ids[] = $id;
	}
	if($total_blocked > 0){
		$message = sprintf('Có %d tài liệu đang thuộc nhiều dự án nên không xoá được. Bỏ chọn chúng, hoặc sửa để bỏ bớt dự án, rồi thử lại.', $total_blocked);
		echo json_encode(array('msg' => $msg, 'message' => $message)); die();
	}
	$total = 0;
	foreach($ids as $id){
		if($type_list == 'trash'){
			if($clsProjectMeta->deleteOne($id)) $total++;                                          // trong thùng rác → xoá vĩnh viễn
		} else {
			if($clsProjectMeta->updateOne($id, array('is_trash'=>1,'upd_date'=>time()))) $total++; // ngoài → soft-delete
		}
	}
	if($total > 0){
		$msg = "_success";
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("ProjectMeta", ($type_list=='trash' ? "delete" : "trash"));
	}
	echo json_encode(array('msg' => $msg, 'message' => $message)); die();
}
function default_db_cleanup_run(){
	global $core, $clsISO;
	$clsProjectMeta = new ProjectMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$last_id = (int) Input::post('last_id', 0);
	$limit = 10;
	$field = "id,project_id,project_ids,block_ids,building_ids,cat_id,title,tags,tags_slug,content,more_information";
	$rows = $clsProjectMeta->getAll("`is_trash`=0 AND `id` > {$last_id} order by `id` ASC limit {$limit}", $field);
	$processed = 0; $new_last = $last_id;
	if(!empty($rows)){
		foreach($rows as $val){
			$id = (int) $val['id']; $new_last = $id;
			$content = $val['content'];
			$more_information = $clsISO->to_array_json($val['more_information']);
			if(!empty($content)){
				try { $more = $clsProjectMeta->crawl($content); } catch(\Throwable $e){ $more = array(); }
				if(!empty($more)) $more_information = array_merge($more_information, $more);
			}
			$col_file_type = isset($more_information['type']) ? $more_information['type'] : '';
			$col_image = isset($more_information['image']) ? $more_information['image'] : '';
			$col_gg_id = isset($more_information['gg_id']) ? $more_information['gg_id'] : '';
			$is_model = !empty($more_information['is_model']) ? 1 : 0;
			$is_handover = !empty($more_information['is_handoverSpecs']) ? 1 : 0;
			$block_arr = !empty($val['block_ids']) ? $clsISO->getArrayByTextSlash($val['block_ids']) : array();
			$building_arr = !empty($val['building_ids']) ? $clsISO->getArrayByTextSlash($val['building_ids']) : array();
			$project_arr = docs_getProjectIds($val); // đồng thời backfill project_ids cho dữ liệu cũ
			$title_search = trim($val['title']);
			foreach($project_arr as $p){ if($p) $title_search .= ' '.$clsProject->getTitle($p); }
			foreach($block_arr as $b){ if($b) $title_search .= ' '.$clsProperty->getTitle($b); }
			foreach($building_arr as $b){ if($b) $title_search .= ' '.$clsProperty->getTitle($b); }
			if(!empty($val['cat_id'])) $title_search .= ' '.$clsProperty->getTitle($val['cat_id']);
			if(!empty($val['tags'])) $title_search .= ' '.$val['tags'];
			$more_information['project_ids'] = array_map('intval', $project_arr);
			$clsProjectMeta->updateOne($id, array(
				'title_search' => $title_search,
				'project_ids' => $clsISO->makeSlashListFromArrayRoot($project_arr),
				'file_type' => $col_file_type,
				'thumb_image' => $col_image,
				'gg_id' => $col_gg_id,
				'is_model' => $is_model,
				'is_handover' => $is_handover,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			$tags_slug_arr = !empty($val['tags_slug']) ? $clsISO->getArrayByTextSlash($val['tags_slug']) : array();
			$tags_name_arr = !empty($val['tags']) ? explode(',', $val['tags']) : array();
			$clsProjectMeta->syncRelations($id, $block_arr, $building_arr, $tags_slug_arr, $tags_name_arr);
			$processed++;
		}
	}
	$remaining = (int) $clsProjectMeta->countItem("`is_trash`=0 AND `id` > {$new_last}");
	echo json_encode(array('processed'=>$processed, 'last_id'=>$new_last, 'remaining'=>$remaining)); die();
}
function default_search_tag(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsProjectMeta = new ProjectMeta();
	
	$results = array();
	// Lấy tag distinct từ bảng junction (index) thay vì quét toàn bộ project_meta
	$rs = $dbconn->Execute("SELECT DISTINCT tag_name FROM ".DB_PREFIX."project_meta_tag WHERE tag_name<>'' ORDER BY tag_name LIMIT 1000");
	if($rs){
		while(!$rs->EOF){
			$tag = $rs->fields['tag_name'];
			$results[] = array('id' => $tag, 'text' => $tag);
			$rs->MoveNext();
		}
	}
	// Return
	echo json_encode($results); die();
}
function default_load_block(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$project_ids = Input::post('project_ids', array());
	$selected_ids = Input::post('selected_ids', array());
	$is_multiple = (int) Input::post('is_multiple', 0);
	// Return
	echo docs_getBlockOptions($project_ids, (array) $selected_ids, $is_multiple); die();
}
function default_load_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$list_block_ids = Input::post('list_block_ids', array());
	$selected_ids = Input::post('selected_ids', array());
	$is_multiple = (int) Input::post('is_multiple', 0);
	// Return
	echo docs_getBuildingOptions($list_block_ids, (array) $selected_ids, $is_multiple); die();
}
function default_create_folder(){
	// ini_set('display_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	###
	$msg = "_error"; $folder_link = "";
	$folder_name = Input::post('folder_name');
	// $clsISO->print_pre($folder_name); die();
	if(!empty($folder_name)){
		$msg = "_success";
		$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_DOCS_ID, true);
		$folder_id = $clsGoogleUpload->create_folder($folder_name);
		$folder_link= sprintf('https://drive.google.com/drive/u/0/folders/%s', $folder_id);
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'folder_id' => $folder_id,
		'folder_link' => $folder_link
	)); die();
}
function default_upload_file(){
//	 ini_set('display_errors', '1');
//	 error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error";
	$upload_files = $_FILES["upload_file"];
	if(!empty($upload_files)){
//		$clsISO->print_pre($_FILES["upload_file"]);die;
		$folder_id = Input::post('folder_id');	
		if(!empty($upload_files["name"])) {
			$fileNames = $upload_files["name"];
			$types = $upload_files["type"];
			$tmp_names = $upload_files["tmp_name"];
			$errors = $upload_files["error"];
			$sizes = $upload_files["size"];
			for ($i=0; $i < count($fileNames); $i++) {
				$file = [
					"name"	=>	$fileNames[$i],
					"tmp_name"	=>	$tmp_names[$i],
					"type"	=>	$types[$i],
					"error"	=>	$errors[$i],
					"size"	=>	$sizes[$i],
				];
				
				if(is_uploaded_file($file['tmp_name'])){
					$extension = strtolower(substr(strrchr($file["name"],"."),1));
					$clsUploadFile = new UploadFile();
					if (strpos(EXTENSION_VIDEO_UPLOAD, $extension)==1){
						$upload_file = $clsUploadFile->uploadItem($file,"/document",EXTENSION_VIDEO_UPLOAD);
					}else{
						$upload_file = $clsUploadFile->uploadItem($file,"/document",EXTENSION_FILE_UPLOAD);
					}
					if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
						$msg = "_success";
						// Set the file metadata for drive
						$title = 'FH_'.time().'_'.$file["name"];
						$mimeType = $file["type"];
						if(!empty($folder_id)){
							$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_DOCS_ID, true);
							$createdFile = $clsGoogleUpload->upload($title,$mimeType,ROOTPATH.$upload_file,$folder_id);
						} else {
							$clsGoogleDrive = new GoogleDrive();
							$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file, GOOGLE_DRIVE_DOCS_ID);
						}
						@unlink(ROOTPATH . $upload_file);
						$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					}
				}	
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'upload_file' => $upload_file
	)); die();
}
function default_upload_image(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error";
	if(!empty($_FILES['upload_image']['name'])){
		if(is_uploaded_file($_FILES['upload_image']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			// $clsISO->print_pre($clsUploadFile); die();
			$upload_image = $clsUploadFile->uploadItem($_FILES["upload_image"],"/PTG",EXTENSION_FILE_UPLOAD);
			if(!empty($upload_image) && file_exists(ROOTPATH . $upload_image)){
				$msg = "_success";
				// Set the file metadata for drive
				$title = 'FH_'.time().'_'.$_FILES["upload_image"]["name"];
				$mimeType = $_FILES["upload_image"]["type"];
				$clsGoogleDrive = new GoogleDrive();
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_image);
				$upload_image = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				@unlink(ROOTPATH . $upload_image);
			}
		}	
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'upload_image' => $upload_image
	)); die();
}
function isEmptyRow($row) {
    foreach($row as $cell){
        if (null !== $cell) return false;
    }
    return true;
}
function default_do_import_file(){
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
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
			@unlink($inputFileName);
			//$clsISO->print_pre($tblData); die();
			if(!empty($tblData)){
				$total_insert = 0;
				for($i=0; $i<count($tblData); $i++){
					$title = $tblData[$i][0];
					$content = $tblData[$i][1];
					$tags = $tblData[$i][2];
					$arr_tag = !empty($tags) ? explode(",",$tags) : array();
					$arr_tags_slug = [];
					if(!empty($arr_tag)) {
						foreach ($arr_tag as $tag) {
							$arr_tags_slug[] = 	$core->replaceSpace($tag);
						}	
					}
					###
					if($clsProjectMeta->insert(array(
						$clsProjectMeta->pkey => $clsProjectMeta->getMaxId(),
						//'type' => $type,
						//'for_id' => $project_id,
						'title' => $title,
						'slug' => $core->replaceSpace($title),
						'content' => $content,
						'tags' => $tags,
						'tags_slug' => $clsISO->makeSlashListFromArrayRoot($arr_tags_slug),
						//'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'order_no' => $clsProjectMeta->getMaxOrderNo(),
						'reg_date' => time(),
						'upd_date' => time()
					))){
						$msg = "_success";
						++ $total_insert;
					}
				}
				if($total_insert > 0) {								
					#activity log		
					$clsActivityLog = new ActivityLog();
					$log = $clsActivityLog->addActivityLog("ProjectMeta","insert");
				}
			}
		}
	}
	// Return
	echo $msg; die();
}
?>