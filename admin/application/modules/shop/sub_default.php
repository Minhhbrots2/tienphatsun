<?php 
function default_crawl_step1(){
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsCrawl = new Crawl();
	
	$project_id = 3; // 3: VHOP3, 2: VHOP2, 1: VHOP1
	require_once(DIR_INCLUDES . '/simple_html_dom.php');
	for($i=1; $i<=26; $i++){
		if($i==1){
			$link = 'https://camnangvinhomes.com/vinhomes-ocean-park-3.html';
		} else {
			$link = 'https://camnangvinhomes.com/vinhomes-ocean-park-3.html?page='.$i;
		}
	//	$link = 'https://camnangvinhomes.com/vinhomes-ocean-park-1.html';
		$html = file_get_html($link);
		$row = $html->find('div.gv_division_term div.container div.row', 0);
		echo $link."<br>";
		$clsCrawl->setDeBug(1);
		foreach($row->find('.locale_item') as $element){
			$permalink = $element->find('div.locale_inner div.image a', 0)->href;
//			 var_dump($permalink); 
			if(!empty($permalink) && $clsCrawl->countItem("project_id='{$project_id}' and permalink='{$permalink}'")==0){
				$clsCrawl->insert(array(
					$clsCrawl->pkey => $clsCrawl->getMaxId(),
					'project_id' => $project_id,
					'permalink' => $permalink,
					'reg_date' => time()
				));
			}
		}
		$row_review = $html->find('div.gv_division_review div.container div.row', 0);
		foreach($row_review->find('.review_item') as $element){
			$permalink = $element->find('div.locale_inner div.image a', 0)->href;
//			  var_dump($permalink); 
			if(!empty($permalink) && $clsCrawl->countItem("project_id='{$project_id}' and permalink='{$permalink}'")==0){
				$clsCrawl->insert(array(
					$clsCrawl->pkey => $clsCrawl->getMaxId(),
					'project_id' => $project_id,
					'permalink' => $permalink,
					'reg_date' => time()
				));
			}
		}		
		$row_must = $html->find('div.gv_division_must div.container div.row', 0);
		foreach($row_must->find('.must_item') as $element){
			$permalink = $element->find('div.locale_inner div.image a', 0)->href;
			if(!empty($permalink) && $clsCrawl->countItem("project_id='{$project_id}' and permalink='{$permalink}'")==0){
				$clsCrawl->insert(array(
					$clsCrawl->pkey => $clsCrawl->getMaxId(),
					'project_id' => $project_id,
					'permalink' => $permalink,
					'reg_date' => time()
				));
			}
		}
		$html->__destruct();
		
	}
	die("Success");
}
function default_crawl_step2(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsCrawl = new Crawl();
	$clsShop = new Shop();
	$clsProperty = new Property();
	
	$list_crawls = $clsCrawl->getAll("is_updated=0");
	if(!empty($list_crawls)){
		require_once(DIR_INCLUDES . '/simple_html_dom.php');
		foreach($list_crawls as $crawl){
			$permalink = $crawl['permalink'];
			$project_id = $crawl['project_id'];
			$html = file_get_html($permalink);
			###
			$cat_name = $html->find('div.breadcrumb ul li',2)->find('a', 0)->innertext;
			$gv_locale_single = $html->find('.gv_locale_single', 0);
			$title = $gv_locale_single->find('h1.title',0)->innertext;
			$image = $gv_locale_single->find('div.image a img',0)->src;
			$address = $gv_locale_single->find('div.info ul.param li span', 0)->innertext;
			$phone = $gv_locale_single->find('div.info ul.param li span', 1)->innertext;
			$time_open = $gv_locale_single->find('div.info ul.param li span', 3)->innertext;
			$map = $gv_locale_single->find('div.action a.map', 1)->href;
			###
			$field = "{$clsProperty->pkey}";
			$tmp = $clsProperty->getByCond("`property_type`='_SHOP' and slug='".$core->replaceSpace($cat_name)."'", $field);
			$cat_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
			// 
			$clsShop->setDeBug(1);
			if($clsShop->countItem("project_id='{$project_id}' and slug='".$core->replaceSpace($title)."'") == 0){
				$more_information = array(
					'phone' => $phone,
					'map' => $map,
					'address' => $address,
					'time_open' => $time_open
				);
				if($clsShop->insert(array(
					$clsShop->pkey => $clsShop->getMaxId(),
					'cat_id' => $cat_id,
					'project_id' => $project_id,
					'title' => $title,
					'slug' => $core->replaceSpace($title),
					'image' => $image,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'reg_date' => time(),
					'upd_date' => time(),
					'order_no' => $clsShop->getMaxOrderNo(),
					'user_id' => $core->_USER['user_id'],
					'user_id_update' => $core->_USER['user_id']
				))){
					$clsCrawl->updateOne($crawl[$clsCrawl->pkey], array(
						'is_updated' => 1
					));
				}
			}
			$html->__destruct();
		}
	}
	die("Success");
}
function default_default(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$dev;
	
	$dev = vnSessionExist('dev') ? (int) vnSessionGetVar('dev') : 0;
	if(isset($_GET['dev'])){
		$dev = (int) $_GET['dev'];
		vnSessionSetVar('dev', $dev);
	}
	
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$classTable = "Shop";
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
		$cond .= " and `cat_id`='$cat_id'";
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
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	$cond2 = $cond;
	if(!empty($type_list)){
		if($type_list=='Trash'){
			$cond .= " and is_trash=1";
		} else {
			$cond .= " and is_trash=0";
		}
	}
	$orderBy = " upd_date DESC, reg_date DESC";
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
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	if(!empty($allItem)){
		$arr_property_cached = array();
		foreach($allItem as $key => $val){
			$cat_id = (int) $val['cat_id'];
			$block_id = (int) $val['block_id'];
			$building_id = (int) $val['building_id'];
			$list_cat_id = $val['list_cat_id'];
			if(!isset($arr_property_cached[$cat_id])){
				$arr_property_cached[$cat_id] = $clsProperty->getTitle($cat_id);
			}
			$allItem[$key]['cat_name'] = $arr_property_cached[$cat_id];
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
	$clsShop = new Shop();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	#
	$shop_id = (int) Input::post('shop_id', 0);
	$dev = (int) Input::post('dev', 0);
	$action = "_edit";
	$titlePage = "Thêm mới";
	$html_subcategory_options = "";
	$oneShop = array('cat_id' => 0, 'project_id' => 0);
	$list_blocks = $list_buildings = $more_information = array();
	if($shop_id > 0){
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneShop = $clsShop->getOne($shop_id);
		$cat_id = $oneShop['cat_id'];
		$block_id = $oneShop['block_id'];
		$project_id = $oneShop['project_id'];
		$list_cat_id = $oneShop['list_cat_id'];
		##
		$more_information = $oneShop['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		if($dev) {
//			$more_information["image_menu"] = is_array($more_information["image_menu"]) ? $more_information["image_menu"] : [$more_information["image_menu"]];
		}
		###
		$field = "{$clsProperty->pkey},`title`";
		$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='{$project_id}'", $field);
//		$clsProperty->setDeBug(1);
		$list_buildings = $clsProperty->getAll("`for_id`='{$block_id}'", $field);
//		$clsISO->print_pre($list_buildings);die;
		##
		if($cat_id > 0){
			$field = "{$clsProperty->pkey},title";
			$tmp = $clsProperty->getAll("parent_id='{$cat_id}' order by `order_no` ASC", $field);
			if(!empty($tmp)){
				$arrs = !empty($list_cat_id) ? $clsISO->getArrayByTextSlash($list_cat_id) : array();
				foreach($tmp as $key => $val){
					$selected = in_array($val[$clsProperty->pkey], $arrs) ? " selected" : "";
					$html_subcategory_options.= sprintf('<option value="%s"%s>%s</option>', $val[$clsProperty->pkey], $selected, $val['title']);
				}
			}
		}
        $settingTemplate = null;
        if (isset($more_information['dynamic']['template_id'])) {
            $settingTemplate = $clsSetting->getOne($more_information['dynamic']['template_id']);
        }
        $configForm = [];
        if (!empty($settingTemplate)) {
            $more_information_setting = !empty($settingTemplate['more_information']) ? $clsISO->to_array_json($settingTemplate['more_information']) : [];
            $configForm = !empty($more_information_setting) ? $more_information_setting['configForm'] : [];
        }
	}
    $lstSetting = $clsSetting->getAll("`is_trash`=0 and `_type`='"._LIST_FORM_BUSINESS."' order by `order_no` ASC");
    $lstSetting = !empty($lstSetting) ? array_map(function($query) use($clsISO) {
        $query['more_information'] = $clsISO->to_array_json($query['more_information']);
        return $query;
    }, $lstSetting) : [];
	$smarty->assign('action', $action);
	$smarty->assign('lstSetting', $lstSetting);
	$smarty->assign('configForm', $configForm);
	$smarty->assign('shop_id', $shop_id);
	$smarty->assign('oneShop', $oneShop);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('list_buildings', $list_buildings);
	$smarty->assign('list_blocks', $list_blocks);
	$smarty->assign('html_subcategory_options', $html_subcategory_options);
	
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$smarty->assign('list_projects', $list_projects);
	// Return
	$smarty->assign('core', $core);
	
	if($dev){
		$html = $core->build('_ajax.open2.tpl');	
	}else{
		$html = $core->build('_ajax.open.tpl');
	}
	
	echo $html; die();
}
function default_search_tag(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsShop = new Shop();
	$clsProperty = new Property();
	###
	$results = array();
	$list_props = $clsShop->getAll("`is_trash`=0 and `tags`<>''", "tags");
	if(!empty($list_props)){
		foreach($list_props as $key => $val){
			$tags = $val['tags'];
			$arr_tags = @explode(',', $tags);
			foreach($arr_tags as $tag){
				$results[] = array('id' => $tag, 'text' => $tag);
			}
		}
		unset($list_props);
	}
	// Return
	echo json_encode($results); die();
}
function default_get_subcategory(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	###
	$clsProperty = new Property();
	$parent_id = (int) Input::post('parent_id', 0);
	###
	$html = "";
	$field = "{$clsProperty->pkey},title";
	$tmp = $clsProperty->getAll("`parent_id`='{$parent_id}' order by `order_no` ASC", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$html.= sprintf('<option value="%s">%s</option>', $val[$clsProperty->pkey], $val['title']);
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
	$classTable = "Shop";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	##
	$pvalTable = (int) Input::post($pkeyTable, 0);
	$list_cat_id = Input::post('list_cat_id', array());
	$title = Input::post('title');
	$tags = Input::post('tags');
	$arr_tag = !empty($tags) ? explode(',',$tags) : array();
	$arr_slug_tag = [];
	foreach ($arr_tag as $tag) {
		$arr_slug_tag[] = $core->replaceSpace($tag);
	}
    $getAll = Input::post();
	if($pvalTable > 0){
		$oShop = $clsClassTable->getOne($pvalTable);
		$more_information = $oShop['$more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		$more_information['map'] = Input::post('map');
		$more_information['phone'] = Input::post('phone');
		$more_information['open_at_time'] = Input::post('open_at_time');
		$more_information['close_at_time'] = Input::post('close_at_time');
		$more_information['image_shop_gallery'] = Input::post('image_gallery');
		$image_menu = Input::post('image_menu',array());
		foreach($image_menu as $key=>$img) {
			$image_menu[$key] = trim($img);
		}
		$more_information['image_menu'] = $image_menu;
		$more_information['dynamic']['template_id'] = Input::post('template_id', 0);
        foreach ($getAll as $keyField => $valueField) {
            if (strpos($keyField, 'dynamic_') === 0) {
                $more_information['dynamic'][$keyField] = $valueField;
            }
        }
		if($clsClassTable->updateOne($pvalTable, array(
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'list_cat_id' => $clsISO->makeSlashListFromArrayRoot($list_cat_id),
			'intro' => Input::post('intro'),
			'image' => Input::post('image'),
			'cat_id' => (int) Input::post('cat_id', 0),
			'project_id' => (int) Input::post('project_id', 0),
			'block_id' => (int) Input::post('block_id', 0),
			'building_id' => (int) Input::post('building_id', 0),
			'tags' => $tags,
			'slug_tags' => $clsISO->makeSlashListFromArrayRoot($arr_slug_tag),
			'upd_date' => time(),
			'user_id_update' => $user_id
		))){
			$msg = "_success";
			#activity log		
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Shop","update");
		}
	} else {
		$image_menu = Input::post('image_menu',array());
		foreach($image_menu as $key=>$img) {
			$image_menu[$key] = trim($img);
		}
		$more_information = array(
			'map' => Input::post('map'),
			'phone' => Input::post('phone'),
			'open_at_time' => Input::post('open_at_time', ''),
			'close_at_time' => Input::post('close_at_time', ''),
            'image_shop_gallery' => Input::post('image_gallery'),
            'image_menu' => $image_menu,
		);
        $more_information['dynamic']['template_id'] = Input::post('template_id', 0);
        foreach ($getAll as $keyField => $valueField) {
            if (strpos($keyField, 'dynamic_') === 0) {
                $more_information['dynamic'][$keyField] = $valueField;
            }
        }
		if($clsClassTable->insert(array(
			$pkeyTable => $clsClassTable->getMaxId(),
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'list_cat_id' => $clsISO->makeSlashListFromArrayRoot($list_cat_id),
			'intro' => Input::post('intro'),
			'image' => Input::post('image'),
			'cat_id' => (int) Input::post('cat_id', 0),
			'project_id' => (int) Input::post('project_id', 0),
			'block_id' => (int) Input::post('block_id', 0),
			'building_id' => (int) Input::post('building_id', 0),
			'tags' => $tags,
			'slug_tags' => $clsISO->makeSlashListFromArrayRoot($arr_slug_tag),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $user_id,
			'user_id_update' => $user_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_trash(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Shop";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	#
	$pUrl = '';
	if($cat_id > 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){	
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Shop","trash");
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
		exit();
	}
}
function default_restore(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Shop";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	#
	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	#
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Shop","restore");
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
		exit();
	}
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Shop";
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
function default_move(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#
	$cat_id = (int) Input::get('cat_id',0);
	$direct = (int) Input::get('direct',"moveup");
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$one = $clsClassTable->getOne($pvalTable);
	$order_no = $one['order_no'];
	if(($string!='' && $pvalTable == 0) || $direct==''){
		header('Location: '.PCMS_URL.'/?mod='.$mod);
	}
	
	$pUrl = '';
	$where = '1=1 and is_trash=0';
	if(intval($cat_id) > 0){
		$pUrl .= '&cat_id='.$cat_id;
		$where.=" and (cat_id='$cat_id' or list_cat_id like '%|$cat_id|%')";
	}
	if($direct=='moveup'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movedown'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movetop'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no > $order_no order by order_no asc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']-1)."'");	
		}
	}
	if($direct=='movebottom'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no < $order_no order by order_no desc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']+1)."'");	
		}
	}
	header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=PositionSuccess');
}

function default_template()
{
    global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$smarty->assign('core',$core);
    $clsSetting = new Setting();
    // $clsActivityLog = new ActivityLog();
    // $getAll = $clsActivityLog->getAll(' profile_id = 64');
    // foreach ($getAll as &$row) {
    //     foreach ($row as $key => $val) {
    //         if (is_string($val)) {
    //             $row[$key] = utf8_decode($val);
    //         }
    //     }
    // }
    // unset($row);
    // echo "<pre>";print_r($getAll);die;
    $clsShop = new Shop();
    $setting_id = (int) Input::post('setting_id');
    $shop_id = (int) Input::post('shop_id');
    $oneSetting = $clsSetting->getOne($setting_id);

    $oneShop = !empty($shop_id) ? $clsShop->getOne($shop_id) : null;
    $more_informationShop= $information = [];
    $template_id = null;
    if (!empty($oneShop)) {
        $more_informationShop = $oneShop['more_information'];
        $more_informationShop = $clsISO->to_array_json($more_informationShop);
        $information = $more_informationShop['dynamic'];
        $template_id = !empty($more_informationShop['dynamic']['template_id']) ? $more_informationShop['dynamic']['template_id'] : null ;
    }

    $more_information = $oneSetting['more_information'];
    $more_information = $clsISO->to_array_json($more_information);
    $configForm = !empty($more_information['configForm']) ? $more_information['configForm'] : [];
	$smarty->assign('setting_id',$setting_id);
	$smarty->assign('configForm',$configForm);
    if ($setting_id == $template_id) {
        $smarty->assign('information',$information);
    }
	$html = $core->build('_ajax.template.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
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
		$folder_id = Input::post('folder_id');	
		if(!empty($upload_files["name"])) {
			$file = $upload_files;
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
						$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_SHOP_ID, true);
						$createdFile = $clsGoogleUpload->upload($title,$mimeType,ROOTPATH.$upload_file,$folder_id);
					} else {
						$clsGoogleDrive = new GoogleDrive();
						$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file, GOOGLE_DRIVE_SHOP_ID);
					}
					@unlink(ROOTPATH . $upload_file);
					$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
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
//	 ini_set('display_errors', '1');
//	 error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$msg = "_error";
	$upload_files = $_FILES["upload_image"];
	$arr_image_thumb = $arr_image_full = [];
	if(!empty($upload_files)){
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
				$image = @upload_driver($file,$folder_id);
				if(!empty($image)) {
					$arr_image_thumb[] = $clsISO->getGoogleUrl($image,100);
					$arr_image_full[] = $clsISO->getGoogleUrl($image,1000);
				}				
			}
			if(!empty($arr_image_thumb)) {
				$msg = "_success";
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'arr_image_thumb' => $arr_image_thumb,
		'arr_image_full' => $arr_image_full,
	)); die();
}
function upload_driver($file,$folder_id=""){
//	 ini_set('display_errors', '1');
//	 error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO;
	$clsStock = new Stock();
	$clsProject = new Project();
	##
	$file_upload = "";
	if(!empty($file)){
		if(!empty($file["name"])) {
			if(is_uploaded_file($file['tmp_name'])){
				$extension = strtolower(substr(strrchr($file["name"],"."),1));
				$clsUploadFile = new UploadFile();
				if (strpos(EXTENSION_VIDEO_UPLOAD, $extension)==1){
					$upload_file = $clsUploadFile->uploadItem($file,"/tmp",EXTENSION_VIDEO_UPLOAD);
				}else{
					$upload_file = $clsUploadFile->uploadItem($file,"/tmp",EXTENSION_FILE_UPLOAD);
				}
				if(!empty($upload_file) && file_exists(ROOTPATH . $upload_file)){
					$msg = "_success";
					// Set the file metadata for drive
					$title = 'FH_'.time().'_'.$file["name"];
					$mimeType = $file["type"];
					if(!empty($folder_id)){
						$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_SHOP_ID, true);
						$createdFile = $clsGoogleUpload->upload($title,$mimeType,ROOTPATH.$upload_file,$folder_id);
					} else {
						$clsGoogleDrive = new GoogleDrive();
						$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$upload_file, GOOGLE_DRIVE_SHOP_ID);
					}
					@unlink(ROOTPATH . $upload_file);
					$file_upload = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
				}
			}
		}
	}
	return $file_upload;
}
?>