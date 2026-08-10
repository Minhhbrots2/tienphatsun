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
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	
	$clsCategory = new Category(); 
	$clsProperty = new Property(); 
	$assign_list["clsCategory"] = $clsCategory;
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$cat_id = (int) Input::post('cat_id', 0);
		$keyword = Input::post('keyword', "");
		
		$link = "";
		if($cat_id > 0) $link .= "&cat_id=".$cat_id;
		if(!empty($keyword)) $link .= "&keyword=".$keyword;
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&act='.$act.$link);
		exit();
	}
	/*Get type of list news*/
	$cat_id = (int) Input::get('cat_id',0);
	$type_list = Input::get('type_list','');
	$keyword = Input::get('keyword', "");
	$assign_list["cat_id"] = $cat_id;
	$assign_list["type_list"] = $type_list;
	$assign_list["keyword"] = $keyword;
	/**/
	$classTable = "Interior";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	
	/*List all item*/
	$pUrl = '';
	$cond = "1='1'";
	if($cat_id > 0){
		$pUrl.='&cat_id='.$cat_id;
		$cond .= " and cat_id='$cat_id'";
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
	$orderBy = " reg_date desc";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 20;
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
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy); 
	$array_style = $clsProperty->getArraySearchByKey("_STYLESINTERIOR");
	$arr = [];
	foreach ($array_style as $key => $val) {
		$arr[$key] = $val['title'];
	}
	foreach($allItem as $key => $value) {
		$more_information = !empty($value['more_information']) ? $clsISO->to_array_json($value['more_information']) : [];
		$arr_intro = !empty($more_information['arr_intro']) ? $more_information['arr_intro'] : [];
//		echo $arr_intro['style']."<br>";
		if(!empty($arr_intro['style'])) {
			if($arr_intro['style'] == "Scandinavian"){
				$id_style = 1363;
			}else{
				$id_style = array_search(trim($arr_intro['style']),$arr);
			}
			
			if(!empty($id_style)) {
				$clsClassTable->setDeBug(1);
				$clsClassTable->updateOne($value['interior_id'],["style_ids"=>"|".$id_style."|"]);
			}
		}		
	}die;
	$clsISO->print_pre($allItem); die();
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
	#
	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"] = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_edit(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsConfiguration,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$clsCategory = new Category(); 
	$assign_list["clsCategory"] = $clsCategory;
	$cat_id = (int) Input::get('cat_id',0);
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$assign_list['pvalTable'] = $pvalTable;
	#
	$oneItem = array();
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		$cat_id = (int) $oneItem['cat_id'];
	}
	$assign_list["cat_id"] = $cat_id;
	$assign_list["oneItem"] = $oneItem;
	#-------------Update Config Meta
	$clsMeta = new Meta(); 
	$assign_list["clsMeta"] = $clsMeta;
	$linkMeta = $clsClassTable->getLink($pvalTable);
	$allMeta = $clsMeta->getAll("config_link='{$linkMeta}' limit 0,1",$clsMeta->pkey);
	$meta_id = (int) $allMeta[0]['meta_id'];
	$assign_list["meta_id"] = $meta_id; 
	$assign_list["oneMeta"] = $clsMeta->getOne($meta_id); 
	
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form();
	$clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
	$assign_list["clsForm"] = $clsForm;
	$clsForm->addInputTextArea("",'intro',"",'intro',255,25,3,1,"style='width:100%'");
	$clsForm->addInputTextArea("full",'content',"",'content',255,25,15,1,"style='width:100%'");
	if($string!='' && $pvalTable==0){
		header('Location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');
	}
	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		$cat_id = (int) Input::post('iso-cat_id');
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
			$set .= ",upd_date='".time()."'";
			$set .= ",is_online='".Input::post('is_online',0)."'";
			$set .= ",user_id_update='".$user_id."'";
			$set .= ",slug='".$core->replaceSpace($_POST['iso-title'])."'";
			#--Special Field: image
			if(_isoman_use){
				$image = Input::post('isoman_url_image');
			} else{
				$image = Input::post('image_src');
			}
			if(!empty($image)){
				$set .= ",image='".addslashes($image)."'";
			}
			#
			$pUrl = '';
			if($cat_id > 0) $pUrl .= '&cat_id='.$cat_id;
			if($clsClassTable->updateOne($pvalTable,$set)) {
				//$clsClassTable->crawImage($pvalTable);
				$config_value_title = Input::post('config_value_title');
				$config_value_intro = Input::post('config_value_intro');
				if(!empty($config_value_title)){
					if($meta_id==0){
						$clsMeta->insertOne("config_link,reg_date,meta_id","'".$linkMeta."','".time()."','".$clsMeta->getMaxId()."'");
						$allMeta = $clsMeta->getAll("config_link='".$linkMeta."'",$clsMeta->pkey);
						$meta_id = (int) $allMeta[0]['meta_id'];
					}
					$clsMeta->updateOne($meta_id,"config_value_intro='".addslashes($config_value_intro)."',config_value_title='".addslashes($config_value_title)."',upd_date='".time()."'");
				}
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
			$field.= ",user_id,user_id_update,reg_date,upd_date,slug,{$clsClassTable->pkey},is_online";
			$value.= ",'".$user_id."','".$user_id."','".time()."','".time()."'";
			$value.= ",'".$core->replaceSpace($_POST['iso-title'])."','".$pvalTable."'";
			$value.= ",'".Input::post('is_online',0)."'"; //print_r($value); die();
			#--Special Field: image
			if(_isoman_use){
				$image = Input::post('isoman_url_image');
			} else{
				$image = Input::post('image_src');
			}
			if(!empty($image)){
				$field.= ",image";
				$value.= ",'".addslashes($image)."'";
			}
			#
			$pUrl = '';
			if($cat_id > 0) $pUrl .= '&cat_id='.$cat_id;
			//$clsISO->print_pre($field.'<br />'.$value); die();
			//$clsClassTable->setDebug(true);
			if($clsClassTable->insertOne($field,$value,false)){
				//$clsClassTable->crawImage($pvalTable);
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
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if($cat_id > 0){
		$pUrl .= '&cat_id='.$cat_id;
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
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
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
	$classTable = "Page";
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

function default_getData(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$profile_id;
	$clsInterior = new Interior();
	
	require_once(DIR_INCLUDES . '/simple_html_dom.php');
	for($i=1; $i<=1; $i++){
		if($i==1){
			$link = 'https://myanhome.com.vn/category/du-an/can-ho/';
		} else {
			$link = 'https://myanhome.com.vn/category/du-an/can-ho/page/'.$i.'/';
		}
	
	$link = 'https://myanhome.com.vn/category/du-an/can-ho/page/2/';
		$html = file_get_html($link);
		$row = $html->find('div.blog-wrapper div.row.row-large', 0);
		$cat_id = 1329;
		$company_id = 2;
		foreach($row->find('div.post-item') as $element){
			$permalink = $element->find('div.col-inner a', 0)->href;
			$html_detail = file_get_html($permalink);
			$title = $html_detail->find('h1.entry-title', 0)->innertext;
			$image = [];
			foreach($html_detail->find('div.col-inner') as $elm){
				$link = $elm->find("a.image-lightbox.lightbox-gallery",0)->href;
				if($link != ""){
					$image[] = $link;	
				}			
			}
			
			$arr_info = [];
			$i=0;
			foreach($html_detail->find('div.entry-content.single-page ul li') as $elm){
				++$i;			
				$text = $elm->innertext;
				if($i == 1){
					$arr_info["project"] = str_replace("Dự án: ","",$text);
				}
				if($i == 2){
					$arr_info["style"] = str_replace("Phong cách: ","",$text);
				}
				if($i == 3){
					$arr_info["perfor"] = str_replace("Công năng: ","",$text);
				}
				
			}
			$image = array_unique($image);
			if(!empty($title) && !empty($image)){
				$slug = $core->replaceSpace($title);
				$getBySlug = $clsInterior->getByCond("slug='{$slug}'");
				if(!empty($getBySlug)) {
					$more_information = !empty($getBySlug['more_information']) ? $clsISO->to_array_json($getBySlug['more_information']) : [];
					$more_information["arr_intro"] = $arr_info;
					$interior_id = $getBySlug["interior_id"];
					$clsInterior->setDeBug(1);
					$clsInterior->updateOne($interior_id, array(						
						'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'cat_id' 			=> $cat_id,
					));
					
				}else{
					$more_information['images'] = $image;
					$clsInterior->setDeBug(1);
		//			echo "<pre>";
					$clsInterior->insert(array(
						$clsInterior->pkey => $clsInterior->getMaxID(),
						'title' 			=> $title,
						'slug' 				=> $core->replaceSpace($title),
						'tags' 				=> "",
						'content' 			=> "",
						'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'reg_date' 			=> time(),
						'upd_date' 			=> time(),
						'is_online' 		=> 1,
						'cat_id' 			=> $cat_id,
						'company_id' 		=> $company_id,
					));
				}
				
			}
			$html_detail->__destruct();
		}
		$html->__destruct();
	}
	die("Success");
}
function default_getDataDBHome(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$profile_id;
	$clsInterior = new Interior();
	$type = Input::get("type","cat");
	require_once(DIR_INCLUDES . '/simple_html_dom.php');
	for($i=1; $i<=1; $i++){
		if($i==1){
			$link = 'https://dbhomearch.vn/thiet-ke-noi-that/phong-cach-hien-dai-modern-style';
		} else {
			$link = 'https://dbhomearch.vn/thiet-ke-noi-that/phong-cach-hien-dai-modern-style?page='.$i;
		}
	
		/*$link = 'https://dbhomearch.vn/thiet-ke-noi-that/phong-cach-wabi-sabi';
		$cat_id = 0;
		$company_id = 3;
		$style_ids = "|1364|";
		$style_name = "Wabisabi";*/
		
		$link = 'https://dbhomearch.vn/du-an/van-phong';		
		$cat_id = 1331;
		$company_id = 3;
		$style_ids = "";
		$style_name = "";
		
		$html = file_get_html($link);
		$row = $html->find('div.blog-wrapper div.row', 0);
		foreach($row->find('div.post-item') as $element){
			$permalink = $element->find('div.col-inner a', 0)->href;
			$title = $element->find('div.col-inner a h5.post-title', 0)->innertext;
			$title = ucfirst(mb_strtolower($title));
			$html_detail = file_get_html($permalink);
			$image = [];
			foreach($html_detail->find('div.col-inner') as $elm){
				foreach($html_detail->find('div.img-description p img') as $elm){
					$link = $elm->src;
					if($link != ""){
						$image[] = $link;	
					}
				}		
			}
			$image = array_unique($image);
			$arr_info = [];
			$arr_info = [
				"project"	=>	"",
				"style"	=>	$style_name,
				"perfor"	=>	"",
			];
			$image = array_unique($image);
			$more_information["arr_intro"] = $arr_info;
			if(!empty($title) && !empty($image)){
				$slug = $core->replaceSpace($title);
				$getBySlug = $clsInterior->getByCond("slug='{$slug}'");
				if(!empty($getBySlug)) {
					$more_information = !empty($getBySlug['more_information']) ? $clsISO->to_array_json($getBySlug['more_information']) : [];
					
					$interior_id = $getBySlug["interior_id"];
					$clsInterior->setDeBug(1);
//					echo 1;die;
					$clsInterior->updateOne($interior_id, array(						
						'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'cat_id' 			=> $cat_id,
					));
					
				}else{
					$more_information['images'] = $image;
//					$clsInterior->setDeBug(1);
		//			echo "<pre>";
					$arr_data = array(
						$clsInterior->pkey => $clsInterior->getMaxID(),
						'title' 			=> $title,
						'slug' 				=> $core->replaceSpace($title),
						'tags' 				=> "",
						'content' 			=> "",
						'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
						'reg_date' 			=> time(),
						'upd_date' 			=> time(),
						'is_online' 		=> 1,
						'cat_id' 			=> $cat_id,
						'company_id' 		=> $company_id,
						'style_ids' 		=> $style_ids,
					);
					$clsISO->print_pre($arr_data);
					$clsInterior->insert($arr_data);
				}
				
			}
			$html_detail->__destruct();
		}
		$html->__destruct();
	}
	die("Success");
}
?>