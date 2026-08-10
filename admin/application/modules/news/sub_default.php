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
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$user_id = $core->_USER['user_id'];
	#
	$clsProperty = new Property(); 
	$assign_list["clsProperty"] = $clsProperty;
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$domain_id = (int) Input::post('domain_id', 0);
		$cat_id = (int) Input::post('cat_id', 0);
		$keyword = Input::post('keyword', "");
		
		$link = "";
		if($domain_id > 0) $link.= "&domain_id=".$domain_id;
		if($cat_id > 0) $link.= "&cat_id=".$cat_id;
		if(!empty($keyword)) $link.= "&keyword=".$keyword;
		// var_dump($_POST); die();
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&act='.$act.$link);
		exit();
	}
	/*Get type of list news*/
	$domain_id = (int) Input::get('domain_id',0);
	$cat_id = (int) Input::get('cat_id',0);
	$type_list = Input::get('type_list','');
	$keyword = Input::get('keyword', "");
	$assign_list["domain_id"] = $domain_id;
	$assign_list["cat_id"] = $cat_id;
	$assign_list["type_list"] = $type_list;
	$assign_list["keyword"] = $keyword;
	/**/
	$classTable = "News";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	$assign_list["classTable"] = $classTable;
	
	/*List all item*/
	$pUrl = ''; $cond = "1='1'";
	if($cat_id > 0){
		$pUrl.='&cat_id='.$cat_id;
		$cond .= " and (`cat_id`='{$cat_id}' OR `cat_id` IN (
			SELECT {$clsProperty->pkey} FROM {$clsProperty->tbl} 
			WHERE `parent_id`='{$cat_id}'
		))";
	} else {
		$cond.= " and `cat_id`!= 186 and `cat_id`!= 185";
	}
	if (!empty($domain_id)) {
		$pUrl.='&domain_id='.$domain_id;
		$cond .= " AND `domain_id`='{$domain_id}'";
	}
	#Filter By Keyword
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	$cond2 = $cond;
	if(!empty($type_list)){
		if($type_list=='Trash'){
			$cond .= " and `is_trash`=1";
		} else {
			$cond .= " and `is_trash`=0";
		}
	}
	$orderBy = " order_no desc";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 20;
	$current_page = (int) Input::get('page',1);
//	$clsClassTable->setDeBug(1);
	$total_record = $clsClassTable->countItem($cond);
//	echo $total_record;die;
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
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	$arr_cat_domain = [];
	if(empty($catParent_id)) {
		foreach ($list_domains as $key => $val) {
			if(!empty($val['cat_id'])) {
				$arr_cat_domain[$val['cat_id']] = $val;
			}			
			
		}		
	}
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
//	$clsISO->print_pre($arr_cat_domain);
//	$clsISO->print_pre($allItem);die;	
	
	foreach ($allItem as $key => $val ) {
		$domain_link = $arr_cat_domain[$val['domain_id']]["link"];
		$allItem[$key]["domain_link"] = $domain_link;
		$allItem[$key]["domain_name"] = $arr_cat_domain[$val['domain_id']]["domain"];
		$images = $clsISO->to_array_json($val['images']);
		$allItem[$key]["images"] = $images;
		$allItem[$key]["image"] = !empty($images[0]) ? FH_URL.$images[0] : URL_IMAGES . "/no-image.png";
	}
	//	$clsISO->print_pre($allItem);die;
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
	#
	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"] = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_edit(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsConfiguration, $clsISO;
	$clsSetting = new Setting();
	$clsProperty = new Property();
    $clsTag = new Tag;
	$assign_list["clsSetting"] = $clsSetting;
	$assign_list["clsProperty"] = $clsProperty;
	$oUser = $core->_USER;
	$more_user = $clsISO->to_array_json($oUser["more_information"]);
	$user_id = !empty($more_user["staff_permiss_id"]) ? $more_user["staff_permiss_id"] : $core->_USER['user_id'];
	#
	$classTable = "News";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$clsCategory = new Category(); 
	$assign_list["clsCategory"] = $clsCategory;
	$cat_id = (int) Input::get('cat_id',0);
	
	$lstProject = $clsSetting->getArraySearchByKey("_PROJECT");
	$assign_list["lstProject"] = $lstProject;
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = !empty($string) ? intval($core->decryptID($string)) : 0;
	$oneItem = $more_information = array(
		'post_type' => '_news',
		'show_website' => 'all'
	);
	$catParent_id = 0;
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		$images = $oneItem['images'];
		$more_information = $oneItem['more_information'];
		$list_images = $clsISO->to_array_json($images);
		$catParent_id = !empty($oneItem['cat_domain_id']) ? $oneItem['cat_domain_id'] : 0;
		$more_information = $clsISO->to_array_json($more_information);
		if(!empty($list_images)){
			foreach($list_images as $key => $val){
				$oneItem['image'] = $val;
				break;
			}
		}
		$listProjectIds = $clsISO->getArrayByTextSlash($oneItem["list_project_ids"]);
		$oneItem['listProjectIds'] = $listProjectIds;
	}	
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
    
	if(empty($catParent_id)) {
		foreach ($list_domains as $key => $val) {
			if(!empty($val['cat_id'])) {
				$catParent_id = $val['cat_id'];
				break;
			}
		}		
	}
    $imageGallery = '';
    if (!empty($more_information['config_gallery']['images'])) {
        $imageGallery = json_encode($more_information['config_gallery']['images'], JSON_UNESCAPED_UNICODE);
    }
    if($core->_USER['user_id'] == '64') {
        // echo "<pre>"; print_r($list_domains);die;
    }
   
    $tags = $clsTag->getAll("is_trash = 0", "$clsTag->pkey, title, slug");

    $selected_tags = explode(',', $oneItem['list_tags']);
    
    $assign_list['imageGallery'] = $imageGallery;
    $assign_list['user_id'] = $user_id;
	$assign_list["list_domains"] = $list_domains;
	$assign_list["catParent_id"] = $catParent_id;
	$assign_list['pvalTable'] = $pvalTable;
	$assign_list["oneItem"] = $oneItem;
	$assign_list["tags"] = $tags;
	$assign_list["selected_tags"] = $selected_tags;
	$assign_list['more_information'] = $more_information;
	// $clsISO->print_pre($more_information); die();
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form();
	$clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
	$assign_list["clsForm"] = $clsForm;
	//$clsForm->addInputTextArea("",'intro',"",'intro',255,25,4,1,"style='width:100%'");
	$clsForm->addInputTextArea("full",'content',"",'content',255,25,22,1,"style='width:100%'");
	if($string!='' && $pvalTable==0){
		header('Location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');
	}

	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
        $type_display_gallery = Input::post('type_display_gallery', 'slide');
        $arr_image_gallery = Input::post('image_gallery', []);
        $more_information['config_gallery'] = array(
            'type_display_gallery' => $type_display_gallery,
            'images' => $arr_image_gallery
        );
        $project_ids = Input::post('project_ids', []);
		$list_project_ids = $clsISO->makeSlashListFromArray($project_ids);
		$post_type = Input::post('post_type', '_news');
		$domain_id = (int) Input::post("domain_id",0);
		$title = Input::post("iso-title","");
		$slug = $core->replaceSpace($title);
        $list_tags = Input::post('list_tags');
		if($pvalTable>0){
			$set = ""; $firstAdd = 0;
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$set .= $tmp[1]."='".addslashes($val)."'";
						$firstAdd = 1;
					} else{
						$set .= ",".$tmp[1]."='".addslashes($val)."'";
					}
				}
			}
			$set .= ",upd_date='".time()."',post_type='{$post_type}'";
			$set .= ",is_online='".Input::post('is_online',0)."'";
			$set .= ",user_id_update='".$user_id."'";
			$set .= ",domain_id='".$domain_id."'";
			$more_information['show_website'] = Input::post('show_website','_all');
			$set .= ",more_information='".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'";
			$set .= ",slug='".$slug."'";
			$set .= ",list_project_ids='".$list_project_ids."'";
			if($post_type == '_whatnew'){
				$start_date = Input::post('start_date');
				$set.= ",start_date='".$clsISO->convertTextToTime($start_date)."'";
			}
			#--Special Field: image
			$image = array();
			if(_isoman_use){
				$image[] = Input::post('isoman_url_image');
			} else{
				$image[] = Input::post('image_src');
			}
			if(!empty($image)){
				$set .= ",images='".json_encode($image, JSON_UNESCAPED_UNICODE)."'";
			}
			#
			$pUrl = '';
			if($clsClassTable->updateOne($pvalTable,$set)) {
				if($_POST['button']=='_EDIT'){
					header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$string.'&message=updateSuccess');
				}else{
					header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateSuccess');
				}
			} else{
				header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateFailed');
			}
		} else{
			$value = ""; $firstAdd = 0; $field = "";
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$field .= $tmp[1];
						$value .= "'".addslashes($val)."'";
						$firstAdd = 1;
					} else{
						$field .= ','.$tmp[1];
						$value .= ",'".addslashes($val)."'";
					}
				}
			}
			#
			$pvalTable = $clsClassTable->getMaxId();
			$more_information['show_website'] = Input::post('show_website','_all');
			$field.= ",user_id,user_id_update,reg_date,upd_date,post_type,{$clsClassTable->pkey},order_no,is_online,more_information,domain_id,slug,list_project_ids";
			$value.= ",'".$user_id."','".$user_id."','".time()."','".time()."','".$post_type."'";
			$value.= ",'".$pvalTable."','".$clsClassTable->getMaxOrderNo()."','".Input::post('is_online',0)."'";
			$value.= ",'".json_encode($more_information, JSON_UNESCAPED_UNICODE)."'"; 
			$value.= ",'".$domain_id."'"; 
			$value.= ",'".$slug."'";
			$value.= ",'".$list_project_ids."'";
			if($post_type == '_whatnew'){
				$field.= ",start_date";
				$start_date = Input::post('start_date');
				//$clsISO->print_pre($start_date); die();
				$value.= ",'".$clsISO->convertTextToTime($start_date)."'";
			}
			#--Special Field: image
			$image = array();
			if(_isoman_use){
				$image[] = Input::post('isoman_url_image');
			} else{
				$image[] = Input::post('image_src');
			}
			if(!empty($image)){
				$field.= ",images";
				$value.= ",'".json_encode($image, JSON_UNESCAPED_UNICODE)."'";
			}
			#
			$pUrl = '';
			if($clsClassTable->insertOne($field,$value)){
				if ($_POST['button'] == '_EDIT') {
					header('Location:'.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$core->encryptID($pvalTable).'&message=insertSuccess');
				}else {
					header('Location:'.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertSuccess');
				}
			} else{
				header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=insertFailed');
			}
		}
	}
}
function default_trash(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "News";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	#
	$cat_id = (int) Input::get('cat_id',0);
	$domain_id = (int) Input::get('domain_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	#
	$pUrl = '';
	if($cat_id > 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($domain_id > 0){
		$pUrl .= '&domain_id='.$domain_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
	}
}
function default_restore(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "News";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$cat_id = (int) Input::get('cat_id',0);
	$domain_id = (int) Input::get('domain_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if(intval($domain_id)> 0){
		$pUrl .= '&domain_id='.$domain_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
	}
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "News";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$cat_id = (int) Input::get('cat_id',0);
	$domain_id = (int) Input::get('domain_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if(intval($domain_id)> 0){
		$pUrl .= '&domain_id='.$domain_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
	}
}
function default_move(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "News";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#
	$cat_id = (int) Input::get('cat_id',0);
	$domain_id = (int) Input::get('domain_id',0);
	$direct = (int) Input::get('direct',"moveup");
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$one = $clsClassTable->getOne($pvalTable);
	$order_no = $one['order_no'];
	if(($string!='' && $pvalTable == 0) || $direct==''){
		header('Location: '.PCMS_URL.'/?mod='.$mod);
	}
	$pUrl = ''; $where = '1=1 and is_trash=0';
	if(intval($cat_id) > 0){
		$pUrl .= '&cat_id='.$cat_id;
		$where.=" and (cat_id='$cat_id' OR cat_id IN (SELECT {$clsProperty->pkey} FROM {$clsProperty->tbl} WHERE `parent_id`='{$cat_id}'))";
	}
	if(intval($domain_id) > 0){
		$pUrl .= '&domain_id='.$domain_id;
		$where.=" and `domain_id`='{$domain_id}'";
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
function default_loadCategory(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$cat_id = (int) Input::post('cat_id', "0");
	$domain_id = (int) Input::post('domain_id', "0");
	$html= '<option value="0">Chọn</option>';
	$html.= $clsProperty->getListOption("_NEWS_CATEGORY", $cat_id, $domain_id);
	// Return
	echo $html; die();
}

function default_search_tag(){
    global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
           ,$keyword_page,$extLang,$clsISO;
    $clsNews = new News();

    $results = array();
    $list_props = $clsNews->getAll("`is_trash`=0 and `list_tags`<>''", "list_tags");
    // $clsISO->print_pre($list_props); die();
    if(!empty($list_props)){
        foreach($list_props as $val){
            $tags = $val['list_tags'];
            $arr_tags = @explode(',', $tags);
            foreach($arr_tags as $tag){
                $results[] = array(
                    'id' => $tag,
                    'text' => $tag
                );
            }
        }
        unset($list_props);
    }
    // Return
    echo json_encode($results); die();
}

/*======================================================================*\
|| News-crawl GĐ4 — 3 màn trong module news (KHÔNG phát sinh module mới)
|| Acts: crawl_source(+_save/_delete/_test) · crawl_list(+_view/_approve/_reject)
|| Curated: cron crawl → staging default_news_crawl → biên tập viên duyệt+viết lại
|| → đẩy sang default_news dạng NHÁP (is_online=0). Không tự đăng.
\*======================================================================*/

/** Trang: cấu hình nguồn báo (list + form thêm/sửa). View: crawl_source.tpl */
function default_crawl_source(){
	global $assign_list, $core, $clsISO, $mod;
	$cls = new NewsCrawlSource();
	$assign_list['sources'] = $cls->getAll("`is_trash`=0 ORDER BY `source_id` ASC");
	$edit = false;
	$sid = (int) Input::get('source_id', 0);
	if($sid > 0){
		$one = $cls->getOne($sid);
		if(!empty($one)) $edit = $one;
	}
	$assign_list['edit'] = $edit;
}

/** Lưu nguồn (POST). Gate đăng nhập + validate scheme RSS (defense-in-depth; fetch_safe re-validate SSRF lúc crawl). */
function default_crawl_source_save(){
	global $core, $mod;
	$user_id = (int) $core->_USER['user_id'];
	if($user_id <= 0){ header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=crawl_source&message=notPermission'); exit(); }
	$cls = new NewsCrawlSource();
	$sid = (int) Input::post('source_id', 0);
	$name = trim(strip_tags(Input::post('name', '')));
	$code = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim(Input::post('code', ''))));
	$rss_url = trim(isset($_POST['rss_url']) ? $_POST['rss_url'] : '');
	$list_url = trim(isset($_POST['list_url']) ? $_POST['list_url'] : '');
	$is_rss = ((int) Input::post('is_rss', 1) === 0) ? 0 : 1;   // 1=RSS feed, 0=trang danh sách HTML
	$cat_id = (int) Input::post('cat_id', 0);
	$crawl_limit = (int) Input::post('crawl_limit', 20);
	if($crawl_limit < 1) $crawl_limit = 1; if($crawl_limit > 50) $crawl_limit = 50;
	$retention_days = (int) Input::post('retention_days', 30);
	if($retention_days < 1) $retention_days = 1; if($retention_days > 365) $retention_days = 365;
	$content_selector = trim(strip_tags(isset($_POST['content_selector']) ? $_POST['content_selector'] : ''));
	if(strlen($content_selector) > 255) $content_selector = substr($content_selector, 0, 255);
	$remove_selectors = trim(strip_tags(isset($_POST['remove_selectors']) ? $_POST['remove_selectors'] : ''));
	if(strlen($remove_selectors) > 500) $remove_selectors = substr($remove_selectors, 0, 500);
	$is_active = Input::post('is_active', 0) ? 1 : 0;
	# Validate tối thiểu: URL theo KIỂU nguồn (RSS dùng rss_url, HTML dùng list_url)
	$primary = $is_rss ? $rss_url : $list_url;
	$scheme = strtolower((string) parse_url($primary, PHP_URL_SCHEME));
	if($name === '' || $code === '' || $primary === '' || ($scheme !== 'http' && $scheme !== 'https')){
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=crawl_source&message=insertFailed'); exit();
	}
	$data = array(
		'name'=>$name, 'code'=>$code, 'rss_url'=>$rss_url, 'list_url'=>$list_url, 'is_rss'=>$is_rss,
		'content_selector'=>$content_selector, 'remove_selectors'=>$remove_selectors,
		'cat_id'=>$cat_id, 'crawl_limit'=>$crawl_limit, 'retention_days'=>$retention_days,
		'is_active'=>$is_active, 'upd_date'=>time()
	);
	if($sid > 0){
		$cls->updateOne($sid, $data);            // updateOne auto-escape qstr → truyền raw
	} else {
		$data['reg_date'] = time();
		$data['source_id'] = $cls->getMaxId();   // tiền-cấp id (insert không trả id ổn định)
		$cls->insert($data);
	}
	header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=crawl_source&message=updateSuccess'); exit();
}

/** Xoá mềm nguồn (is_trash=1). */
function default_crawl_source_delete(){
	global $core, $mod;
	$user_id = (int) $core->_USER['user_id'];
	if($user_id <= 0){ header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=crawl_source'); exit(); }
	$cls = new NewsCrawlSource();
	$sid = (int) Input::get('source_id', 0);
	if($sid > 0) $cls->updateOne($sid, array('is_trash'=>1, 'is_active'=>0, 'upd_date'=>time()));
	header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=crawl_source&message=TrashSuccess'); exit();
}

/** AJAX: test 1 bài từ RSS (giống cron nhưng 1 item, không lưu). Trả JSON cho crawlTest() JS. */
function default_crawl_test(){
	global $core, $clsISO;
	$is_rss = ((int) Input::post('is_rss', 1) === 0) ? 0 : 1;
	$sel = trim(strip_tags(isset($_POST['content_selector']) ? $_POST['content_selector'] : ''));
	$ferr = ''; $link = '';
	if($is_rss){
		$rss = trim(isset($_POST['rss_url']) ? $_POST['rss_url'] : '');
		if($rss === '' || !NewsExtractor::_ssrf_safe($rss)){ echo json_encode(array('ok'=>0, 'error'=>'RSS URL trống hoặc không an toàn (SSRF)')); die(); }
		$raw = NewsExtractor::fetch_safe($rss, $ferr);
		if($raw === false){ echo json_encode(array('ok'=>0, 'error'=>'Không tải được RSS: '.$ferr)); die(); }
		$xml = @simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($xml === false || empty($xml->channel) || empty($xml->channel->item)){ echo json_encode(array('ok'=>0, 'error'=>'RSS không đọc được (sai định dạng?)')); die(); }
		foreach($xml->channel->item as $it){ $link = trim((string) $it->link); break; }
	} else {
		$list = trim(isset($_POST['list_url']) ? $_POST['list_url'] : '');
		if($list === '' || !NewsExtractor::_ssrf_safe($list)){ echo json_encode(array('ok'=>0, 'error'=>'URL danh sách trống hoặc không an toàn (SSRF)')); die(); }
		$raw = NewsExtractor::fetch_safe($list, $ferr);
		if($raw === false){ echo json_encode(array('ok'=>0, 'error'=>'Không tải được trang danh sách: '.$ferr)); die(); }
		$links = NewsExtractor::extract_links($raw, $list);
		if(empty($links)){ echo json_encode(array('ok'=>0, 'error'=>'Không bóc được link bài (trang JS hoặc URL sai mẫu)')); die(); }
		$link = $links[0];
	}
	if($link === ''){ echo json_encode(array('ok'=>0, 'error'=>'Không tìm thấy link bài viết')); die(); }
	$r = NewsExtractor::extract($link, array('content_selector'=>$sel));
	if(!empty($r['blocked'])){ echo json_encode(array('ok'=>0, 'error'=>'Bài bị chặn SSRF: '.$link)); die(); }
	if(empty($r['ok'])){ echo json_encode(array('ok'=>0, 'error'=>(!empty($r['error']) ? $r['error'] : 'Bóc tách thất bại'))); die(); }
	echo json_encode(array(
		'ok'=>1, 'tier_log'=>$r['tier_log'], 'title'=>$r['title'], 'summary'=>$r['summary'],
		'content'=>$r['content'], 'images'=>$r['images'], 'source_url'=>$link
	), JSON_UNESCAPED_UNICODE); die();
}

/** Trang: danh sách tin đã crawl (staging). View: crawl_list.tpl */
function default_crawl_list(){
	global $assign_list, $core, $clsISO, $dbconn, $mod;
	$cls = new NewsCrawl();
	$clsSource = new NewsCrawlSource();
	$f_source = (int) Input::get('f_source', 0);
	$f_status = Input::get('f_status', '');
	$kw = trim(Input::get('kw', ''));
	$cond = "1=1";
	if($f_source > 0) $cond .= " AND `source_id`=".$f_source;
	$allowed = array('new','blocked','pushed','rejected');
	if($f_status !== '' && in_array($f_status, $allowed, true)) $cond .= " AND `status`=".$dbconn->qstr($f_status);
	if($kw !== '') $cond .= " AND `title` LIKE ".$dbconn->qstr('%'.$kw.'%');
	# Phân trang
	$per = 20;
	$page = (int) Input::get('page', 1); if($page < 1) $page = 1;
	$total = (int) $cls->countItem($cond);
	$total_page = ($total > 0) ? ceil($total / $per) : 1;
	$offset = ($page - 1) * $per;
	$rows = $cls->getAll($cond." ORDER BY `crawl_id` DESC LIMIT ".$offset.",".$per);
	foreach((array) $rows as $k => $rv){           // đếm ảnh ở PHP (tránh @count(null) fatal trong .tpl PHP8)
		$ia = json_decode((string) $rv['images'], true);
		$rows[$k]['img_count'] = is_array($ia) ? count($ia) : 0;
	}
	$qs = '';
	if($f_source > 0) $qs .= '&f_source='.$f_source;
	if($f_status !== '') $qs .= '&f_status='.urlencode($f_status);
	if($kw !== '') $qs .= '&kw='.urlencode($kw);
	$assign_list['rows'] = $rows;
	$assign_list['sources'] = $clsSource->getAll("`is_trash`=0 ORDER BY `name` ASC");
	$assign_list['total'] = $total;
	$assign_list['total_page'] = $total_page;
	$assign_list['page'] = $page;
	$assign_list['qs'] = $qs;
	$assign_list['f'] = array('f_source'=>$f_source, 'f_status'=>$f_status, 'kw'=>$kw);
}

/** AJAX: dựng modal xem/duyệt 1 tin. Trả {html}. */
function default_crawl_view(){
	global $core, $clsISO, $clsConfiguration;
	$id = (int) Input::post('crawl_id', 0);
	$cls = new NewsCrawl();
	$o = $cls->getOne($id);
	if(empty($o)){ echo json_encode(array('html'=>'<div style="padding:24px">Không tìm thấy tin.</div>')); die(); }
	$imgs = json_decode((string) $o['images'], true);
	$o['images_arr'] = is_array($imgs) ? $imgs : array();
	# Website (domain) + danh mục để biên tập chọn nơi đăng (mirror form edit news)
	$clsProperty = new Property();
	if(empty($clsConfiguration)) $clsConfiguration = new Configuration();
	$list_domains = $clsISO->to_array_json($clsConfiguration->getValue('list_domains'));
	$domains = array();
	foreach((array) $list_domains as $d){ if(!empty($d['cat_id'])) $domains[] = $d; }
	$def_domain = !empty($domains[0]['cat_id']) ? (int) $domains[0]['cat_id'] : 0;  // domain mặc định = đầu list
	$src_cat = (int) $o['cat_hint'];   // cat_id cấu hình nguồn (cron lưu ở cat_hint) → preselect nếu khớp
	$html = $core->build('_ajax.crawl_view.tpl', array(
		'o'=>$o, 'domains'=>$domains, 'clsProperty'=>$clsProperty,
		'def_domain'=>$def_domain, 'src_cat'=>$src_cat
	));
	echo json_encode(array('html'=>$html)); die();
}

/** AJAX: duyệt → ĐĂNG NGAY lên default_news (is_online=1) vào website + danh mục đã chọn,
 *  ảnh đại diện do biên tập chọn. Bắt buộc đã viết lại (gate bản quyền) + chọn danh mục. */
function default_crawl_approve(){
	global $core, $clsISO, $dbconn;
	$user_id = (int) $core->_USER['user_id'];
	if($user_id <= 0){ echo json_encode(array('msg'=>'_noauth')); die(); }
	$id = (int) Input::post('crawl_id', 0);
	$cls = new NewsCrawl();
	$o = $cls->getOne($id);
	if(empty($o)){ echo json_encode(array('msg'=>'_notfound')); die(); }
	if($o['status'] === 'pushed'){ echo json_encode(array('msg'=>'_already', 'news_id'=>(int) $o['news_id'])); die(); }
	# Gate bản quyền: phải đánh dấu đã viết lại
	if((int) Input::post('is_rewritten', 0) !== 1){ echo json_encode(array('msg'=>'_need_rewrite')); die(); }
	# Website + danh mục (biên tập chọn). Danh mục bắt buộc vì đăng thẳng lên web.
	$domain_id = (int) Input::post('domain_id', 0);
	$cat_id = (int) Input::post('cat_id', 0);
	if($cat_id <= 0){ echo json_encode(array('msg'=>'_need_cat')); die(); }
	$title = trim(strip_tags(isset($_POST['title']) ? $_POST['title'] : ''));
	if($title === '') $title = trim(strip_tags((string) $o['title']));
	$summary = trim(strip_tags(isset($_POST['summary']) ? $_POST['summary'] : ''));
	# Nội dung là rich HTML → đọc RAW (Input::post xss_clean sẽ phá), re-sanitize server-side
	$content_raw = isset($_POST['content']) ? $_POST['content'] : '';
	$content = NewsExtractor::_sanitize_html($content_raw);
	$slug = $core->replaceSpace($title);
	# Ảnh đại diện: CHỈ nhận URL nằm trong tập ảnh đã crawl của tin này (chống SSRF/abuse)
	$imgs_arr = json_decode((string) $o['images'], true);
	if(!is_array($imgs_arr)) $imgs_arr = array();
	$cover = isset($_POST['cover']) ? trim($_POST['cover']) : '';
	if($cover === '' || !in_array($cover, $imgs_arr, true)){
		$cover = !empty($imgs_arr[0]) ? $imgs_arr[0] : '';   // fallback ảnh đầu nếu chọn rỗng/không hợp lệ
	}
	# TẢI ẢNH về local bằng cURL (NewsExtractor::download_image — thay uploadImageFromUrl vốn lỗi:
	# _upload_ftp=0 → cURLdownload không follow-redirect + bỏ qua lỗi → ảnh hỏng). Làm TRƯỚC insert.
	@set_time_limit(240);   // tải tối đa 13 ảnh × 15s/ảnh = đủ thời gian hoàn tất trước insert (dù proxy có 504, PHP vẫn chạy xong)
	$content = NewsExtractor::localize_images($content, $slug);                       // ảnh trong nội dung → local
	$cover_err = '';
	$cover_local = ($cover !== '') ? NewsExtractor::download_image($cover, $slug, $cover_err) : '';
	$images_json = ($cover_local !== '') ? json_encode(array($cover_local), JSON_UNESCAPED_UNICODE) : '';
	$clsNews = new News();
	$pre_id = $clsNews->getMaxId();             // tiền-cấp news_id
	$order_no = $clsNews->getMaxOrderNo();
	$more = array(
		'crawl_source'=>$o['source_code'], 'crawl_source_url'=>$o['source_url'],
		'crawl_id'=>(int) $id, 'is_rewritten'=>1, 'show_website'=>'_all'
	);
	$ok = $clsNews->insert(array(             // insert auto-escape qstr → truyền raw (KHÔNG addslashes)
		'news_id'=>$pre_id, 'post_type'=>'_news', 'cat_id'=>$cat_id, 'domain_id'=>$domain_id,
		'title'=>$title, 'slug'=>$slug, 'intro'=>$summary, 'content'=>$content,
		'images'=>$images_json, 'is_online'=>1, 'is_trash'=>0,   // ĐĂNG NGAY lên web (không còn nháp)
		'more_information'=>json_encode($more, JSON_UNESCAPED_UNICODE),
		'order_no'=>$order_no, 'reg_date'=>time(), 'upd_date'=>time(),
		'user_id'=>$user_id, 'user_id_update'=>$user_id,
		'list_tags'=>'', 'list_project_ids'=>''
	));
	if(!$ok){ echo json_encode(array('msg'=>'_insert_fail')); die(); }
	$cls->updateOne($id, array('status'=>'pushed', 'news_id'=>$pre_id, 'upd_date'=>time()));
	echo json_encode(array('msg'=>'_success', 'news_id'=>$pre_id, 'cover'=>($cover_local !== '' ? 1 : 0))); die();
}

/** AJAX: bỏ tin trong staging (status=rejected). */
function default_crawl_reject(){
	global $core;
	$user_id = (int) $core->_USER['user_id'];
	if($user_id <= 0){ echo json_encode(array('msg'=>'_noauth')); die(); }
	$id = (int) Input::post('crawl_id', 0);
	$cls = new NewsCrawl();
	$o = $cls->getOne($id);
	if(empty($o)){ echo json_encode(array('msg'=>'_notfound')); die(); }
	$cls->updateOne($id, array('status'=>'rejected', 'upd_date'=>time()));
	echo json_encode(array('msg'=>'_success')); die();
}
?>