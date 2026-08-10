<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function project_default1(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$list_projects;
	$clsProperty = new Property();
	$clsNews = new News();
	$clsProjectMeta = new ProjectMeta();
	$clsShop = new Shop();
	$clsPolicy = new Policy();
	$list_utilities = $clsProperty->getCacheItems("_UTILITIES_PROJECT");
	$cond_cat_docs = "`is_trash`=0 and `property_type`='_CATEGORY_DOCS'";
	$field_cat_docs = "{$clsProperty->pkey},title,image";
	$arr_notins_project = array(_PROJECT_DOCS_BM_CATID, _PROJECT_DOCS_CHTH_CATID, _PROJECT_DOCS_INTERIOR_CATID);
	$arr_notins_block = array(_PROJECT_DOCS_BM_CATID);
	$arr_data = [];
	#project
//	$clsISO->print_pre($list_projects);die;
	foreach ($list_projects as $k_pro => $_oProject) {
		$more_information = $_oProject['more_information'];
		$list_category_docs_project = $clsProperty->getAll("{$cond_cat_docs} and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."' and `parent_id`='0' order by `order_no` ASC", $field_cat_docs);
		$arr_menu = [];
		
		$arr_menu[] = [
			"cat_id"	=>	0,
			"cat_name"	=>	"Thông tin dự án",
			"status"	=>	!empty($more_information["attrs"]) ? 1 : 0
		];
		
		foreach ($list_category_docs_project  as $k_cat_doc => $v_cat_doc) {
			$menu_child = [];
			if($v_cat_doc['property_id'] != _PROJECT_DOCS_LAYOUT_CATID) {
				$cond = "`is_trash`='0' AND `type`='project' AND `project_id`='{$_oProject['project_id']}'";
//				$clsProperty->setDeBug(1);
				$list_childs = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins_project).") and `parent_id`='{$v_cat_doc["property_id"]}' order by order_no ASC", "{$clsProperty->pkey},title");
				$pattern = sprintf('DA-%s', $_oProject['project_id']);
				foreach ($list_childs as $k_child => $v_child) {
					$subcat_id = $v_child[$clsProperty->pkey];
					if($subcat_id == _PROJECT_DOCS_PR_CATID){
						$field = "{$clsNews->pkey},title,content,images,reg_date";
						$tmp = $clsNews->countItem("`is_trash`=0 and `post_type`='_news' and JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'");
						$menu_child[$v_child['title']] = !empty($tmp) ? 1 : 0;
					}else{
						$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$subcat_id}'");
						$menu_child[$v_child['title']] = !empty($tmp) ? 1 : 0;
					}
				}					
			}else{
				$menu_child["Tổng thể"] = !empty($more_information['layout']) ? $more_information['layout'] : 0;
			}
			$arr_menu[] = [
				"cat_id"		=>	$v_cat_doc['property_id'],
				"cat_name"		=>	$v_cat_doc['title'],
				"menu_child"	=>	$menu_child,
				"status"		=>	1
			];
		}
		#menu tiện ích
		$utilities = $_oProject['utilities'];
		$utilities = $clsISO->to_array_json($utilities);
		$total_ultilities = 0;
		foreach($utilities as $k_ulti => $v_ulti) {
			if($v_ulti['block_id'] == 0) {
				++$total_ultilities;
			}
		}
		$arr_menu[] = [
			"cat_id"	=>	_PROJECT_DOCS_UTILITY_CATID,
			"cat_name"	=>	"Tiện ích",
			"status"	=>	!empty($total_ultilities) ? 1 : 0
		];
		
		#block
		$lstBlock = $clsProperty->getAllCache("`for_id`='{$_oProject['project_id']}'");
		$arr_block = [];
		foreach($lstBlock as $k_block => $_oBlock) {			
			$more_information_block = $clsISO->to_array_json($_oBlock['more_information']);
			$cond_block = "";
			if($_oBlock['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
				$cond_block.= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."'";
			}
			$list_category_docs_block = $clsProperty->getAll("{$cond_cat_docs} ".$cond_block." and `parent_id`='0' order by `order_no` ASC", $field_cat_docs);
			
			$arr_menu_block = [];
			
			$arr_menu_block[] = [
				"cat_id"	=>	0,
				"cat_name"	=>	"Thông tin phân khu",
				"status"	=>	!empty($more_information_block["attrs"]) ? 1 : 0
			];
			foreach ($list_category_docs_block  as $k_cat_doc_block => $v_cat_doc_block) {
				$menu_child_block = [];
				if($v_cat_doc_block['property_id'] != _PROJECT_DOCS_LAYOUT_CATID) {
					$cond = "`is_trash`='0' AND `type`='block' AND `block_ids` LIKE '%|{$_oBlock['property_id']}|%'";
//					$clsProperty->setDeBug(1);
					$cond_block = "";
					if($_oBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE){
						$cond_block.= " and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins_block).")";
						if($v_cat_doc_block['property_id'] == _PROJECT_DOCS_CSBH_CATID) {
							$menu_child_block["Ảnh CSBH tóm tắt"] = !empty($more_information_block['sales_policy']) ? 1 : 0;
//							$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$v_cat_doc_block["property_id"]}'");
							$pattern = sprintf('%s_%s', $_oProject["project_id"], $_oBlock[$clsProperty->pkey]);
							$list_policy_docs = $clsPolicy->countItem("`is_trash`=0 and `block_type`='{$_oBlock["parent_id"]}' 
						and `scope_slash` like '%|{$pattern}|%'");
							$menu_child_block["Tài liệu CSBH"] = !empty($list_policy_docs) ? 1 : 0;
						}
					}
					$list_childs_block = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `parent_id`='{$v_cat_doc_block["property_id"]}' ".$cond_block." order by order_no ASC", "{$clsProperty->pkey},title");
					$pattern = sprintf('PK-%s', $_oBlock['property_id']);
					foreach ($list_childs_block as $k_child_block => $v_child_block) {
						$subcat_id = $v_child_block[$clsProperty->pkey];
						if($subcat_id == _PROJECT_DOCS_PR_CATID){
							$field = "{$clsNews->pkey},title,content,images,reg_date";
							$tmp = $clsNews->countItem("`is_trash`=0 and `post_type`='_news' and JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'");
							$menu_child_block[$v_child_block['title']] = !empty($tmp) ? 1 : 0;
						}else{
							$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$subcat_id}'");
							$menu_child_block[$v_child_block['title']] = !empty($tmp) ? 1 : 0;
						}
					}					
				}else{
					$menu_child_block["Tổng thể"] = !empty($more_information_block['layout_ns']) ? $more_information_block['layout_ns'] : 0;
				}
				$arr_menu_block[] = [
					"cat_id"		=>	$v_cat_doc_block['property_id'],
					"cat_name"		=>	$v_cat_doc_block['title'],
					"menu_child"	=>	$menu_child_block,
					"status"		=>	1
				];
			}
			#menu tiện ích
			$utilities = $_oProject['utilities'];
			$utilities = $clsISO->to_array_json($utilities);
			$total_ultilities = 0;
			foreach($utilities as $k_ulti => $v_ulti) {
				if($v_ulti['block_id'] == 0) {
					++$total_ultilities;
				}
			}
			$arr_menu_block[] = [
				"cat_id"	=>	_PROJECT_DOCS_UTILITY_CATID,
				"cat_name"	=>	"Tiện ích",
				"status"	=>	!empty($total_ultilities) ? 1 : 0
			];
			
			#building
			$arr_building = [];
			$lstBuilding = $clsProperty->getAll("`property_type`='_BUILDING' AND `is_trash`='0' AND `for_id`='{$_oBlock['property_id']}'");
			foreach ($lstBuilding as $k_building => $_oBuilding ) {		
				$more_information_building = $clsISO->to_array_json($_oBuilding['more_information']);	
//				$clsISO->print_pre($more_information_building);die;
				$list_category_docs_building = $clsProperty->getAll("{$cond_cat_docs} and `parent_id`='0' order by `order_no` ASC", $field_cat_docs);
				$arr_menu_building = [];				
				$arr_menu_building[] = [
					"cat_id"	=>	0,
					"cat_name"	=>	"Thông tin tòa nhà",
					"status"	=>	!empty($more_information_building["attrs"]) ? 1 : 0
				];
				foreach ($list_category_docs_building  as $k_cat_doc_building => $v_cat_doc_building) {
					$menu_child_building = [];
					if($v_cat_doc_building['property_id'] != _PROJECT_DOCS_LAYOUT_CATID) {
						$cond = "`is_trash`='0' AND `type`='building' AND `building_ids` LIKE '%|{$_oBuilding['property_id']}|%'";
		//				$clsProperty->setDeBug(1);
						$list_childs_building = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `parent_id`='{$v_cat_doc_building["property_id"]}' order by order_no ASC", "{$clsProperty->pkey},title");
						$pattern = sprintf('TOA-%s', $_oProject['project_id']);
						$CSBH = 0;
						foreach ($list_childs_building as $k_child_building => $v_child_building) {
							$subcat_id = $v_child_building[$clsProperty->pkey];
							if($subcat_id == _PROJECT_DOCS_PR_CATID){
								$field = "{$clsNews->pkey},title,content,images,reg_date";
								$tmp = $clsNews->countItem("`is_trash`=0 and `post_type`='_news' and JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'");
								$menu_child_building[$v_child_building['title']] = !empty($tmp) ? 1 : 0;
							}else{
								$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$subcat_id}'");
								$menu_child_building[$v_child_building['title']] = !empty($tmp) ? 1 : 0;
							}
						}	
						if($v_cat_doc_building['property_id'] == _PROJECT_DOCS_CSBH_CATID) {
							$is_policy = 0;
							if(!empty($more_information_block['sales_policy'])) {
								foreach($more_information_block['sales_policy'] as $k_policy => $v_policy) {
									if($clsISO->checkItemInArray($_oBuilding["property_id"],$v_policy['building'])) {
										$is_policy = 1;
									}
								}								
							}							
							$menu_child_building["Ảnh CSBH tóm tắt"] = $is_policy;
//							$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$v_cat_doc_building["property_id"]}'");
							$pattern = sprintf('%s_%s_%s', $_oProject["project_id"], $_oBlock[$clsProperty->pkey], $_oBuilding[$clsProperty->pkey]);
							$list_policy_docs = $clsPolicy->countItem("`is_trash`=0 and `block_type`='{$_oBlock["parent_id"]}' 
						and `scope_slash` like '%|{$pattern}|%'");
							$menu_child_building["Tài liệu CSBH"] = !empty($list_policy_docs) ? 1 : 0;
						}				
					}else{
						$menu_child_building["Layout tầng"] = !empty($more_information_building['layout_ns']) ? 1 : 0;
					}
					$arr_menu_building[] = [
						"cat_id"		=>	$v_cat_doc_building['property_id'],
						"cat_name"		=>	$v_cat_doc_building['title'],
						"menu_child"	=>	$menu_child_building,
						"status"		=>	1
					];
				}
				#menu tiện ích
				$total_shop = $clsShop->countItem("`is_trash`='0' AND `building_id`='{$_oBuilding['property_id']}'");
				$arr_menu_building[] = [
					"cat_id"	=>	_PROJECT_DOCS_UTILITY_CATID,
					"cat_name"	=>	"Tiện ích",
					"status"	=>	!empty($total_shop) ? 1 : 0
				];	
				$arr_building[] = [
					"building_id"	=>	$_oBuilding['property_id'],
					"building_name"	=>	"Tòa ".$_oBuilding['title'],
					"menu"			=>	$arr_menu_building,
				];
//				$clsISO->print_pre($arr_menu_building);die;
			}

			$arr_block[] = [
				"block_id"		=>	$_oBlock['property_id'],
				"block_name"	=>	"Phân khu ".$_oBlock['title'],
				"menu"			=>	$arr_menu_block,
				"list_building"	=>	$arr_building
			];
		}
		
		
		$arr_data[] = [
			"project_id"	=>	$_oProject['project_id'],
			"project_name"	=>	$_oProject['title'],
			"menu"			=>	$arr_menu,
			"list_block"	=>	$arr_block
		];
//	$clsISO->print_pre($utilities);die;
	}
	$assign_list["arr_data"] = $arr_data;
//	$clsISO->print_pre($arr_data);die;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách tình trạng thông tin dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function getLink($type,$options) {
	global $clsISO;
	$project_id = !empty($options['project_id']) ? $options['project_id'] : 0;
	$cat_id = !empty($options['cat_id']) ? $options['cat_id'] : 0;
	$block_id = !empty($options['block_id']) ? $options['block_id'] : 0;
	$building_id = !empty($options['building_id']) ? $options['building_id'] : 0;
	$stock_type = !empty($options['stock_type']) ? $options['stock_type'] : 0;
	$tab = !empty($options['tab']) ? $options['tab'] : "content";
	$link = "";
	switch ($type){
		case "project" : 
			$link  = FH_URL."/admin/?mod=project&act=edit";
			break;
		case "media" : 
			$link  = FH_URL."/admin/?mod=docs&cmd=open";
			break;
		case "block" : 
			$link  = FH_URL."/admin/?mod=project&cmd=open_block";
			break;
		case "building" : 
			$link  = FH_URL."/admin/?mod=project&cmd=open_building";
			break;
		case "policy" : 
			$link  = FH_URL."/admin/?mod=policy&cmd=open";
			break;
		case "news" : 
			$link  = $clsISO->getLink("news")."?cmd=open";
			break;
		case "shop" : 
			$link  = FH_URL."/admin/?mod=shop&cmd=open";
			break;
	}
	if(!empty($options)) {
		foreach($options as $key => $val) {
			if(!empty($val)) {
				$link .= "&".$key."=".$val;
			}
		}
	}
	return $link;
}
function project_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$list_projects;
	$clsProperty = new Property();
	$clsNews = new News();
	$clsProjectMeta = new ProjectMeta();
	$clsShop = new Shop();
	$clsPolicy = new Policy();
	$list_utilities = $clsProperty->getCacheItems("_UTILITIES_PROJECT");
	$cond_cat_docs = "`is_trash`=0 and `property_type`='_CATEGORY_DOCS'";
	$field_cat_docs = "{$clsProperty->pkey},title,image";
	$arr_notins_project = array(_PROJECT_DOCS_BM_CATID, _PROJECT_DOCS_CHTH_CATID, _PROJECT_DOCS_INTERIOR_CATID);
	$arr_notins_block = array(_PROJECT_DOCS_BM_CATID);
	$arr_data = [];
	#project
	foreach ($list_projects as $k_pro => $_oProject) {
		$more_information = $_oProject['more_information'];
		$list_category_docs_project = $clsProperty->getAll("{$cond_cat_docs} and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."' and `parent_id`='0' order by `order_no` ASC", $field_cat_docs);
		$arr_menu = [];
		$total = $total_success = 0;		
		
		$arr_menu[] = [
			"cat_id"	=>	0,
			"cat_name"	=>	"Thông tin dự án",
			"link_edit"	=>	@getLink("project",["project_id"=>$_oProject["project_id"]]),
			"status"	=>	!empty($more_information["attrs"]) ? 1 : 0
		];
		++$total;
		if(!empty($more_information["attrs"])) {
			++$total_success;
		}
		
		foreach ($list_category_docs_project  as $k_cat_doc => $v_cat_doc) {
			$menu_child = [];
			if($v_cat_doc['property_id'] != _PROJECT_DOCS_LAYOUT_CATID) {
				$cond = "`is_trash`='0' AND `type`='project' AND `project`='{$_oProject['project_id']}'";
				$list_childs = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins_project).") and `parent_id`='{$v_cat_doc["property_id"]}' order by order_no ASC", "{$clsProperty->pkey},title");
				$pattern = sprintf('DA-%s', $_oProject['project_id']);
				foreach ($list_childs as $k_child => $v_child) {
					$subcat_id = $v_child[$clsProperty->pkey];
					++$total;
					if($subcat_id == _PROJECT_DOCS_PR_CATID){
						$field = "{$clsNews->pkey},title,content,images,reg_date";
						$tmp = $clsNews->countItem("`is_trash`=0 and `post_type`='_news' and JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'");
						$tmp_media = $clsProjectMeta->getAll($cond." and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
						$menu_child[] = [
							"title"	=>	$v_child['title'],
							"link_edit"	=>	@getLink("media",["project_id"=>$_oProject["project_id"],"cat_id"=>$subcat_id]),
							"status"	=>	(!empty($tmp) || !empty($tmp_media)) ? 1 : 0
						];
						if(!empty($tmp) || !empty($tmp_media)) {
							++$total_success;
						}
					}else{
						$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$subcat_id}'");
						$menu_child[] = [
							"title"		=>	$v_child['title'],
							"link_edit"	=>	@getLink("media",["project_id"=>$_oProject["project_id"],"cat_id"=>$subcat_id]),
							"status"	=>	!empty($tmp) ? 1 : 0
						];
						if(!empty($tmp)) {
							++$total_success;
						}
					}
				}					
			}else{
				++$total;
				$menu_child[] = [
					"title"	=>	"Tổng thể",
					"link_edit"	=>	@getLink("project",["project_id"=>$_oProject["project_id"]]),
					"status"	=>	!empty($more_information['layout']) ? 1 : 0
				];
				if(!empty($more_information['layout'])) {
					++$total_success;
				}
			}
			$arr_menu[] = [
				"cat_id"		=>	$v_cat_doc['property_id'],
				"cat_name"		=>	$v_cat_doc['title'],
				"menu_child"	=>	$menu_child,
				"link_edit"		=>	@getLink("media",["project_id"=>$_oProject["project_id"],"cat_id"=>$v_cat_doc['property_id']]),
				"status"		=>	1
			];
		}
		#menu tiện ích
		$utilities = $_oProject['utilities'];
		$utilities = $clsISO->to_array_json($utilities);
		$total_ultilities = 0;
		foreach($utilities as $k_ulti => $v_ulti) {
			if($v_ulti['block_id'] == 0) {
				++$total_ultilities;
			}
		}
		$arr_menu[] = [
			"cat_id"	=>	_PROJECT_DOCS_UTILITY_CATID,
			"cat_name"	=>	"Tiện ích",
			"link_edit"	=>	@getLink("project",["project_id"=>$_oProject["project_id"]]),
			"status"	=>	!empty($total_ultilities) ? 1 : 0
		];
		++$total;
		if(!empty($total_ultilities)) {
			++$total_success;
		}
		
		#block
		$lstBlock = $clsProperty->getAll("`for_id`='{$_oProject['project_id']}'");
		$arr_block = [];
		foreach($lstBlock as $k_block => $_oBlock) {			
			$more_information_block = $clsISO->to_array_json($_oBlock['more_information']);
			$cond_block = "";
			if($_oBlock['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
				$cond_block.= " and {$clsProperty->pkey}<>'"._PROJECT_DOCS_CSBH_CATID."'";
			}
			$list_category_docs_block = $clsProperty->getAll("{$cond_cat_docs} ".$cond_block." and `parent_id`='0' order by `order_no` ASC", $field_cat_docs);
			
			$arr_menu_block = [];
			
			$arr_menu_block[] = [
				"cat_id"	=>	0,
				"cat_name"	=>	"Thông tin phân khu",
				"link_edit"	=>	@getLink("block",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"tab"=>"content"]),
				"status"	=>	!empty($more_information_block["attrs"]) ? 1 : 0
			];
			++$total;
			if(!empty($more_information_block["attrs"])) {
				++$total_success;
			}
			foreach ($list_category_docs_block  as $k_cat_doc_block => $v_cat_doc_block) {
				$menu_child_block = [];
				if($v_cat_doc_block['property_id'] != _PROJECT_DOCS_LAYOUT_CATID) {
					$cond = "`is_trash`='0' AND `type`='block' AND `block_ids` LIKE '%|{$_oBlock['property_id']}|%'";
					$cond_block = "";
					if($_oBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE){
						$cond_block.= " and `{$clsProperty->pkey}` not in(".implode(',', $arr_notins_block).")";
						if($v_cat_doc_block['property_id'] == _PROJECT_DOCS_CSBH_CATID) {
							$menu_child_block[] = [
								"title"	=>	"Ảnh CSBH tóm tắt",
								"link_edit"	=>	@getLink("block",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"tab"=>"csbh"]),
								"status"	=>	!empty($more_information_block['sales_policy']) ? 1 : 0
							];
							++$total;
							if($more_information_block['sales_policy']) {
								++$total_success;
							}
							
							$pattern = sprintf('%s_%s', $_oProject["project_id"], $_oBlock[$clsProperty->pkey]);
							$list_policy_docs = $clsPolicy->countItem("`is_trash`=0 and `block_type`='{$_oBlock["parent_id"]}' 
						and `scope_slash` like '%|{$pattern}|%'");
							$menu_child_block[] = [
								"title"	=>	"Tài liệu CSBH",
								"link_edit"	=>	@getLink("policy",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"stock_type"=>_BLOCK_TYPE_LOWFLOOR_SALE]),
								"status"	=>	!empty($list_policy_docs) ? 1 : 0
							];							
							++$total;
							if(!empty($list_policy_docs)) {
								++$total_success;
							}
						}
					}
					$list_childs_block = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `parent_id`='{$v_cat_doc_block["property_id"]}' ".$cond_block." order by order_no ASC", "{$clsProperty->pkey},title");
					$pattern = sprintf('PK-%s', $_oBlock['property_id']);
					foreach ($list_childs_block as $k_child_block => $v_child_block) {
						$subcat_id = $v_child_block[$clsProperty->pkey];
						if($subcat_id == _PROJECT_DOCS_PR_CATID){
							$field = "{$clsNews->pkey},title,content,images,reg_date";
							$tmp = $clsNews->countItem("`is_trash`=0 and `post_type`='_news' and JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'");
							$tmp_media = $clsProjectMeta->getAll($cond." and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
							$menu_child_block[] = [
								"title"	=>	$v_child_block['title'],
								"link_edit"	=>	@getLink("news",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id']]),
								"status"	=>	(!empty($tmp) || !empty($tmp_media)) ? 1 : 0
							];
							++$total;
							if(!empty($tmp) || !empty($tmp_media)) {
								++$total_success;
							}
						}else{
							$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$subcat_id}'");
							$menu_child_block[] = [
								"title"	=>	$v_child_block['title'],
								"link_edit"	=>	@getLink("media",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"cat_id"=>$subcat_id]),
								"status"	=>	!empty($tmp) ? 1 : 0
							];
							++$total;
							if(!empty($tmp)) {
								++$total_success;
							}
						}
					}					
				}else{
					$menu_child_block[] = [
						"title"		=>	"Tổng thể",
						"link_edit"	=>	@getLink("block",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id']]),
						"status"	=>	!empty($more_information_block['layout_ns']) ? 1 : 0
					];
					++$total;
					if(!empty($more_information_block['layout_ns'])) {
						++$total_success;
					}					
				}
				$arr_menu_block[] = [
					"cat_id"		=>	$v_cat_doc_block['property_id'],
					"cat_name"		=>	$v_cat_doc_block['title'],
					"menu_child"	=>	$menu_child_block,
					"link_edit"		=>	@getLink("block",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id']]),
					"status"		=>	1
				];
			}
			#menu tiện ích
			$total_ultilities_block = 0;
			foreach($utilities as $k_ulti => $v_ulti) {
				if($v_ulti['block_id'] == $_oBlock['property_id']) {
					++$total_ultilities_block;
				}
			}
			$arr_menu_block[] = [
				"cat_id"	=>	_PROJECT_DOCS_UTILITY_CATID,
				"cat_name"	=>	"Tiện ích",
				"link_edit"	=>	@getLink("project",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id']]),
				"status"	=>	!empty($total_ultilities_block) ? 1 : 0
			];			
			++$total;
			if(!empty($total_ultilities_block)) {
				++$total_success;
			}
			
			#building
			$arr_building = [];
			$lstBuilding = $clsProperty->getAll("`property_type`='_BUILDING' AND `is_trash`='0' AND `for_id`='{$_oBlock['property_id']}'");
			foreach ($lstBuilding as $k_building => $_oBuilding ) {		
				$more_information_building = $clsISO->to_array_json($_oBuilding['more_information']);	
				$list_category_docs_building = $clsProperty->getAll("{$cond_cat_docs} and {$clsProperty->pkey}<>'"._PROJECT_DOCS_IMGVIDEO_CATID."' and `parent_id`='0' order by `order_no` ASC", $field_cat_docs);
				$arr_menu_building = [];				
				$arr_menu_building[] = [
					"cat_id"	=>	0,
					"cat_name"	=>	"Thông tin tòa nhà",
					"link_edit"	=>	@getLink("building",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id']]),
					"status"	=>	!empty($more_information_building["attrs"]) ? 1 : 0
				];							
				++$total;
				if(!empty($more_information_building["attrs"])) {
					++$total_success;
				}
				foreach ($list_category_docs_building  as $k_cat_doc_building => $v_cat_doc_building) {
					$menu_child_building = [];
					if($v_cat_doc_building['property_id'] != _PROJECT_DOCS_LAYOUT_CATID) {
						$cond = "`is_trash`='0' AND `type`='building' AND `building_ids` LIKE '%|{$_oBuilding['property_id']}|%'";
						$list_childs_building = $clsProperty->getAll("`is_trash`=0 and `property_type`='_CATEGORY_DOCS' and `parent_id`='{$v_cat_doc_building["property_id"]}' order by order_no ASC", "{$clsProperty->pkey},title");
						$pattern = sprintf('TOA-%s', $_oProject['project_id']);
						$CSBH = 0;
						foreach ($list_childs_building as $k_child_building => $v_child_building) {
							$subcat_id = $v_child_building[$clsProperty->pkey];
							if($subcat_id == _PROJECT_DOCS_PR_CATID){
								$field = "{$clsNews->pkey},title,content,images,reg_date";
								$tmp = $clsNews->countItem("`is_trash`=0 and `post_type`='_news' and JSON_EXTRACT(`more_information`,'$.lst_tags') like '%|{$pattern}|%'");
								$tmp_media = $clsProjectMeta->getAll($cond." and `cat_id`='{$subcat_id}' order by `reg_date` DESC");
								$arr_menu_building[] = [
									"cat_name"	=>	$v_child_building['title'],
									"link_edit"	=>	@getLink("news",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id']]),
									"status"	=>	(!empty($tmp) || !empty($tmp_media)) ? 1 : 0
								];							
								++$total;
								if(!empty($tmp) || !empty($tmp_media)) {
									++$total_success;
								}
							}else{
								$tmp = $clsProjectMeta->countItem($cond." and `cat_id`='{$subcat_id}'");
								$arr_menu_building[] = [
									"cat_name"	=>	$v_child_block['title'],
									"link_edit"	=>	@getLink("media",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id'],"cat_id"=>$v_cat_doc_building['property_id']]),
									"status"	=>	!empty($tmp) ? 1 : 0
								];						
								++$total;
								if(!empty($tmp)) {
									++$total_success;
								}
							}
						}	
						if($v_cat_doc_building['property_id'] == _PROJECT_DOCS_CSBH_CATID) {
							$is_policy = 0;
							if(!empty($more_information_block['sales_policy'])) {
								foreach($more_information_block['sales_policy'] as $k_policy => $v_policy) {
									if($clsISO->checkItemInArray($_oBuilding["property_id"],$v_policy['building'])) {
										$is_policy = 1;
										break;
									}
								}								
							}							
							$menu_child_building[] = [
								"title"	=>	"Ảnh CSBH tóm tắt",
								"link_edit"	=>	@getLink("block",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id'],"tab"=>"csbh"]),
								"status"	=>	$is_policy
							];													
							++$total;
							if(!empty($is_policy)) {
								++$total_success;
							}
							$pattern = sprintf('%s_%s_%s', $_oProject["project_id"], $_oBlock[$clsProperty->pkey], $_oBuilding[$clsProperty->pkey]);
							$list_policy_docs = $clsPolicy->countItem("`is_trash`=0 and `block_type`='{$_oBlock["parent_id"]}' 
						and `scope_slash` like '%|{$pattern}|%'");
							$menu_child_building[] = [
								"title"	=>	"Tài liệu CSBH",
								"link_edit"	=>	@getLink("policy",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id'],"stock_type"=>_BLOCK_TYPE_HIGHLEVEL_SALE]),
								"status"	=>	!empty($list_policy_docs) ? 1 : 0
							];													
							++$total;
							if(!empty($list_policy_docs)) {
								++$total_success;
							}
						}				
					}else{
						$menu_child_building[] = [
							"title"		=>	"Layout tầng",
							"link_edit"	=>	@getLink("building",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id']]),
							"status"	=>	!empty($more_information_building['layout_ns']) ? 1 : 0
						];												
						++$total;
						if(!empty($more_information_building['layout_ns'])) {
							++$total_success;
						}
					}
					$arr_menu_building[] = [
						"cat_id"		=>	$v_cat_doc_building['property_id'],
						"cat_name"		=>	$v_cat_doc_building['title'],
						"link_edit"		=>	@getLink("building",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id']]),
						"menu_child"	=>	$menu_child_building,
						"status"		=>	1
					];
				}
				#menu tiện ích
				$total_ultilities_building = 0;
				foreach($utilities as $k_ulti => $v_ulti) {
					if($v_ulti['block_id'] == $_oBlock['property_id']) {
						++$total_ultilities_building;
						break;
					}
				}
				$arr_menu_building[] = [
					"cat_id"	=>	_PROJECT_DOCS_UTILITY_CATID,
					"cat_name"	=>	"Tiện ích",
					"link_edit"	=>	@getLink("project",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id']]),
					"status"	=>	!empty($total_ultilities_building) ? 1 : 0
				];												
				++$total;
				if(!empty($total_ultilities_building)) {
					++$total_success;
				}
				
				$arr_building[] = [
					"building_id"	=>	$_oBuilding['property_id'],
					"building_name"	=>	"Tòa ".$_oBuilding['title'],
					"link_edit"		=>	@getLink("building",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id'],"building_id"=>$_oBuilding['property_id']]),
					"menu"			=>	$arr_menu_building,
				];
			}

			$arr_block[] = [
				"block_id"		=>	$_oBlock['property_id'],
				"block_name"	=>	"Phân khu ".$_oBlock['title'],
				"link_edit"		=>	@getLink("block",["project_id"=>$_oProject["project_id"],"block_id"=>$_oBlock['property_id']]),
				"menu"			=>	$arr_menu_block,
				"list_building"	=>	$arr_building
			];
		}
		
		
		$arr_data[] = [
			"project_id"	=>	$_oProject['project_id'],
			"project_name"	=>	$_oProject['title'],
			"menu"			=>	$arr_menu,
			"list_block"	=>	$arr_block,
			"total"			=>	$total,
			"total_success"	=>	$total_success,
		];
//	$clsISO->print_pre($utilities);die;
	}
	$assign_list["arr_data"] = $arr_data;
//	die;
//	$clsISO->print_pre($arr_data);die;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách tình trạng thông tin dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}

function project_report_dq(){
	global $clsISO,$assign_list,$dbconn,$title_page;
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsStock = new Stock();
	$lstStock = $clsStock->getAll(" `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `agency_id`='"._AGENCY_FH_ID."' ",$clsStock->pkey.",ms_code,more_information,block_id");
	$arr_stock = [];
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_stock = [
		"msq" => [
			"total"	=>	0,
			"total_price_vat" => 0,
			"title"	=>	"Sky Quarter",
			"block_id"	=>	"msq"
		],
		"csd" => [
			"total"	=>	0,
			"total_price_vat" => 0,
			"title"	=>	"Capital Square",
			"block_id"	=>	"csd"
		],
	];
	$total = $total_price = 0;
	foreach ($lstStock as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		if(!empty($more_information["total_price_vat"])){
			++$total;
			$total_price += (int)$more_information["total_price_vat"];
			if(!isset($arr_stock[$val["block_id"]]) && !$clsISO->checkItemInArray($val["block_id"],_PROJECT_BLOCK_MSQ_ARRAY) && !$clsISO->checkItemInArray($val["block_id"],_PROJECT_BLOCK_CSD_ARRAY)) {			
				$arr_stock[$val["block_id"]] = [
					"total"	=>	1,
					"total_price_vat" => $more_information["total_price_vat"],
					"title"	=>	($val["block_id"] == _PROJECT_BLOCK_SLC_ID) ? "Sunshine Legend City" : $arr_block[$val["block_id"]]["title"],
					"block_id"	=>	$val["block_id"]
				];
			}else {
				if($clsISO->checkItemInArray($val["block_id"],_PROJECT_BLOCK_MSQ_ARRAY)) {
					$arr_stock["msq"]["total"] += 1;
					$arr_stock["msq"]["total_price_vat"] += $more_information["total_price_vat"];
				}elseif($clsISO->checkItemInArray($val["block_id"],_PROJECT_BLOCK_CSD_ARRAY)) {
					$arr_stock["csd"]["total"] += 1;
					$arr_stock["csd"]["total_price_vat"] += $more_information["total_price_vat"];
				}else{
					$arr_stock[$val["block_id"]]["total"] += 1;
					$arr_stock[$val["block_id"]]["total_price_vat"] += $more_information["total_price_vat"];
				}			
			}
		}
		
	}
	$total_stock = @array_column($arr_stock, 'total_price_vat');
	@array_multisort($total_stock, SORT_DESC, $arr_stock);
	$assign_list["arr_stock"] = $arr_stock;
	$assign_list["total"] = $total;
	$assign_list["total_price"] = $total_price;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách quỹ độc quyền dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_view_stock_dq(){
	global $clsISO,$assign_list,$dbconn,$smarty,$core;
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsStock = new Stock();
	$uid = $clsISO->getUniqid();
	$block_id = Input::post("block_id");
	$cond = " `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `agency_id`='"._AGENCY_FH_ID."' ";
	$html = "";
	if(!empty($block_id)) {
		$total_price = 0;
		if($block_id == "msq") {
			$cond .= " AND `block_id` IN (".implode(',',_PROJECT_BLOCK_MSQ_ARRAY).")";
			$title_page = "Quỹ độc quyền Sky Quarter";
		}elseif($block_id == "csd") {
			$cond .= " AND `block_id` IN (".implode(',',_PROJECT_BLOCK_CSD_ARRAY).")";
			$title_page = "Quỹ độc quyền Capital Square";
		}else{
			$oneBlock = $clsProperty->getArraySearchByKey("_BLOCK",$block_id);
			$cond .= " AND `block_id`='{$block_id}'";
			$title_page = "Quỹ độc quyền ".$oneBlock["title"];
		}
		$lstStock = $clsStock->getAll($cond,$clsStock->pkey.",ms_code,more_information,block_id");
		foreach ($lstStock as $key => $val) {
			$more_information = $clsISO->to_array_json($val["more_information"]);
			$lstStock[$key]["total_price_vat"] = $more_information["total_price_vat"];
			$total_price += (int)$more_information["total_price_vat"];
		}
		$smarty->assign("lstStock",$lstStock);
		$smarty->assign("uid",$uid);
		$smarty->assign("title_page",$title_page);
		$smarty->assign("total_price",$total_price);
		
		$html = $core->build('project'.DS.'_ajax.view_stock_dq.tpl');
		
	}
	echo json_encode(array(
		"uid"	=>	$uid,
		"html"	=>	$html
	),JSON_UNESCAPED_UNICODE);die;
}
function project_report_stock_highfloor(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$deviceType;
	$clsProperty = new Property();
	$clsStock = new Stock();
	$list_bedroom = $clsProperty->getCacheItems("_BEDROOM");
	$field = "{$clsProperty->pkey},title,more_information";
	$lstBlock = [
		_PROJECT_BLOCK_ALUMI_ID	=>	"ALM",
		_PROJECT_BLOCK_SLC_ID	=>	"SLC",
		_PROJECT_BLOCK_PARKLAND_ID	=>	"Parkland",
		_PROJECT_BLOCK_MGC_ID	=>	"MGC",
		_PROJECT_BLOCK_MEL_ID	=>	"MEL",
		_PROJECT_BLOCK_LEK_ID	=>	"LEK",
	];
	$lstBlockId = array_keys($lstBlock);
//	$dbconn->debug=true;
	$lstStock = $clsStock->getAll("`block_id` IN (".implode(',',$lstBlockId).") 
	AND `DT_TT` > 0 
	AND `total_price_early` > 1000000000 
	AND `total_price_bank` > 1000000000 
	AND `total_price_progress` > 1000000000 
	AND `status_id`>0 AND `stock_id` <> '118127'
	GROUP BY `block_id`,`bedroom_id`",
	"MIN(`total_price_early` / `DT_TT`) AS `min_price_early`,
	  MAX(`total_price_early` / `DT_TT`) AS `max_price_early`,
	  MIN(`total_price_progress` / `DT_TT`) AS `min_price_progress`,
	  MAX(`total_price_progress` / `DT_TT`) AS `max_price_progress`,
	  MIN(`total_price_bank` / `DT_TT`) AS `min_price_bank`,
	  MAX(`total_price_bank` / `DT_TT`) AS `max_price_bank`,
	  `block_id`,`bedroom_id`
	");
	$arr_min_max = [];
	$arr_bedroom_cache = $clsProperty->getArraySearchByKey("_BEDROOM");
	$arr_bedroom_id = [];
	foreach ($lstStock as $key => $val){
		$min_price_early = $val["min_price_early"];
		$max_price_early = $val["max_price_early"];
		$min_price_bank = $val["min_price_bank"];
		$max_price_bank = $val["max_price_bank"];
		$min_price_progress = $val["min_price_progress"];
		$max_price_progress = $val["max_price_progress"];
		
		$arr_min_max[$val["bedroom_id"]][$val["block_id"]]["price_early"] = $clsStock->genMinMax($min_price_early,$max_price_early);
		$arr_min_max[$val["bedroom_id"]][$val["block_id"]]["price_bank"] = $clsStock->genMinMax($min_price_bank,$max_price_bank);
		$arr_min_max[$val["bedroom_id"]][$val["block_id"]]["price_progress"] = $clsStock->genMinMax($min_price_progress,$max_price_progress);
		$arr_min_max[$val["bedroom_id"]][$val["block_id"]]["title"] = $lstBlock[$val["block_id"]];
		$arr_bedroom_id[] = $val["bedroom_id"];
	}
//	$clsISO->print_pre($arr_min_max);die;
	$arr_bedroom = [];
	foreach ($arr_bedroom_cache as $key => $val) {
		if($clsISO->checkItemInArray($key,$arr_bedroom_id)) {
			$arr_bedroom[$key]["title"] = $val["title"];
			$arr_bedroom[$key]["list_block"] = $arr_min_max[$key];
		}
		
	}
//	$clsISO->print_pre($arr_bedroom);die;
	$assign_list["arr_bedroom"] = $arr_bedroom;
	$assign_list["lstBlock"] = $lstBlock;
	$assign_list["arr_min_max"] = $arr_min_max;
	###
	/*=============Title & Description Page==================*/
	$title_page = 'Tổng quan giá các phân khu cao tầng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = "Chi tiết giá trên m2 và giá vay của tất cả các phân khu đang mở bán VHOP1-2-3, Cổ Loa... - " . PAGE_NAME;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function project_report_stock_lowfloor(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$deviceType;
	$clsProperty = new Property();
	$clsStock = new Stock();
	$list_bedroom = $clsProperty->getCacheItems("_BEDROOM");
	$field = "{$clsProperty->pkey},title,more_information";
	$list_blocks = $clsProperty->getAll("`property_type`='_BLOCK' and `for_id`='"._PROJECT_VHOP2_ID."' 
		AND `parent_id`='"._BLOCK_TYPE_LOWFLOOR_SALE."' order by `order_no` DESC, JSON_EXTRACT(`more_information`,\"$.on_sale\") DESC", $field);
	$assign_list["list_blocks"] = $list_blocks;
	###
	/*=============Title & Description Page==================*/
	$title_page = 'Tổng quan giá các phân khu thấp tầng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = "Chi tiết giá trên m2 và giá vay của tất cả các phân khu đang mở bán VHOP1-2-3, Cổ Loa... - " . PAGE_NAME;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function project_load_report_price_lowfloor(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$deviceType;
	$clsProperty = new Property();
	$clsStock = new Stock();
	$project_id = (int)Input::post("project_id",0);
	$block_id = (int)Input::post("block_id",0);
	
	$arr_type_villa_cached = $clsProperty->getArraySearchByKey("_TYPE_VILLA");
	$cond = "`project_id`='{$project_id}' AND `block_id`='{$block_id}' AND `type_id`>0 AND `agency_id`<>'"._AGENT_LEASING_ID."' AND DT_TT <> '' AND `status_id`>0";
//	$cond .= " AND DT_TT='140' ";
	$lstStock = $clsStock->getAll($cond);
	$arr_type = [];
	foreach ($lstStock as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		$type_name = $arr_type_villa_cached[$val["type_id"]]["title"];
		$total_price_vat = !empty($more_information["total_price_vat"]) ? $clsISO->processSmartNumber($more_information["total_price_vat"]) : 0;
		$total_price_early = !empty($more_information["total_price_early"]) ? $clsISO->processSmartNumber($more_information["total_price_early"]) : 0;
		$total_price_bank = !empty($more_information["total_price_bank"]) ? $clsISO->processSmartNumber($more_information["total_price_bank"]) : 0;
		$total_price_progress = !empty($more_information["total_price_progress"]) ? $clsISO->processSmartNumber($more_information["total_price_progress"]) : 0;
		
		if(!isset($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]])) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["price_max"] = $total_price_vat;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["price_min"] = $total_price_vat;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tts_max"] = $total_price_early;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tts_min"] = $total_price_early;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["bank_max"] = $total_price_bank;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["bank_min"] = $total_price_bank;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tttd_max"] = $total_price_progress;
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tttd_max"] = $total_price_progress;
		}
		
		if($more_information["total_price_early"] > 0){
//			$clsISO->print_pre($val);die;
//			echo $more_information["total_price_early"];die;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["price_min"] > $total_price_vat) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["price_min"] = $total_price_vat;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["price_max"] < $total_price_vat) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["price_max"] = $total_price_vat;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tts_min"] > $total_price_early) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tts_min"] = $total_price_early;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tts_max"] < $total_price_early) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tts_max"] = $total_price_early;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["bank_min"] > $total_price_bank) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["bank_min"] = $total_price_bank;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["bank_max"] < $total_price_bank) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["bank_max"] = $total_price_bank;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tttd_min"] > $total_price_progress) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tttd_min"] = $total_price_progress;
		}
		if($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tttd_max"] < $total_price_progress) {
			$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["tttd_max"] = $total_price_progress;
		}
		$arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]["ms_code"] = $val["ms_code"];
		if($more_information["total_price_early"] > 0 && $val["stock_id"] != 48319){
//			echo $val['stock_id'];
//			$clsISO->print_pre($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]);die;
		}
		if($val["stock_id"] == 44906) {
//			echo $val['stock_id'];
//			$clsISO->print_pre($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]);die;
		}
//		echo $type_name;die;
//		echo $val['stock_id'];
//		$clsISO->print_pre($arr_type[$val["type_id"]]["lst_area"][$val["DT_TT"]]);die;
  		
		$arr_type[$val["type_id"]]["type_name"] = $type_name;
	}
//	$clsISO->print_pre($arr_type);die;	
	foreach ($arr_type as $type_id => $_oItem) {
		$lst_area = $_oItem["lst_area"];
		if(!empty($lst_area)) {
			foreach($lst_area as $key => $val) {
				if(empty($val["price_min"]) && empty($val["price_max"]) 
				   && empty($val["tts_min"]) && empty($val["tts_max"])
				   && empty($val["bank_min"]) && empty($val["bank_max"])
				   && empty($val["tttd_min"]) && empty($val["tttd_max"])
				  ) {
					unset($lst_area[$key]);
				}
			}
			$arr_type[$type_id]["lst_area"] = $lst_area;
			if(empty($lst_area)) {
				unset($arr_type[$type_id]);
			}
			unset($lst_area);
		}else{
			unset($arr_type[$type_id]);
		}
	}
	$smarty->assign("arr_type", $arr_type);
	$html = $core->build("project".DS."_ajax.load_report_price.tpl");
	echo json_encode([
		"html"	=>	$html
	],JSON_UNESCAPED_UNICODE);
}
function project_load_pop_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
		,$description_page,$keyword_page,$clsConfiguration,$clsISO,$project_id,$oneProject,$smarty;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$project_id = (int)Input::post('project_id', 0);
	$block_id = (int)Input::post('block_id', 0);
	$type_id = (int)Input::post('type_id', 0);
	$DT_TT = (int)Input::post('DT_TT', 0);
	$html = "";
	if(!empty($project_id) && !empty($block_id) && !empty($type_id) && !empty($DT_TT)) {
		$arr_home_direction_cached = $clsProperty->getArraySearchByKey("_DIRECTION");
		$arr_status_cached = $clsProperty->getArraySearchByKey("_STATUS");
//		$clsISO->print_pre($arr_status_cached);die;	
		###
		$total_price_vat = !empty($more_information["total_price_vat"]) ? $clsISO->processSmartNumber($more_information["total_price_vat"]) : 0;
		$total_price_early = !empty($more_information["total_price_early"]) ? $clsISO->processSmartNumber($more_information["total_price_early"]) : 0;
		$total_price_bank = !empty($more_information["total_price_bank"]) ? $clsISO->processSmartNumber($more_information["total_price_bank"]) : 0;
		$total_price_progress = !empty($more_information["total_price_progress"]) ? $clsISO->processSmartNumber($more_information["total_price_progress"]) : 0;
		
		$cond = "`project_id`='{$project_id}' AND `block_id`='{$block_id}' AND `type_id` = '{$type_id}' AND `agency_id`<>'"._AGENT_LEASING_ID."' AND DT_TT = '{$DT_TT}' AND `status_id`> 0 AND (JSON_EXTRACT(`more_information`,\"$.total_price_vat\") > 0 OR JSON_EXTRACT(`more_information`,\"$.total_price_early\") > 0 OR JSON_EXTRACT(`more_information`,\"$.total_price_bank\") > 0 OR JSON_EXTRACT(`more_information`,\"$.total_price_progress\") > 0)";
//		$dbconn->debug=true;
		$list_stocks = $clsStock->getAll($cond);
//		$clsISO->print_pre($list_stocks);die;
		if(!empty($list_stocks)){
			foreach($list_stocks as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$type_name = $arr_type_villa_cached[$val["type_id"]]["title"];
				$total_price_vat = !empty($more_information["total_price_vat"]) ? $clsISO->processSmartNumber($more_information["total_price_vat"]) : 0;
				$total_price_early = !empty($more_information["total_price_early"]) ? $clsISO->processSmartNumber($more_information["total_price_early"]) : 0;
				$total_price_bank = !empty($more_information["total_price_bank"]) ? $clsISO->processSmartNumber($more_information["total_price_bank"]) : 0;
				$total_price_progress = !empty($more_information["total_price_progress"]) ? $clsISO->processSmartNumber($more_information["total_price_progress"]) : 0;
				$list_stocks[$key]["DT_TT"] =  !empty($more_information["DT_TT"]) ? $more_information["DT_TT"] : 0;
				$list_stocks[$key]["home_direction_name"] = $arr_home_direction_cached[$home_direction_id]["title"];
				$list_stocks[$key]["total_price_vat"] = $total_price_vat;
				$list_stocks[$key]["total_price_early"] = $total_price_early;
				$list_stocks[$key]["total_price_bank"] = $total_price_bank;
				$list_stocks[$key]["total_price_progress"] = $total_price_progress;
				$list_stocks[$key]["status_name"] = '<span class="label" style="background:'.$arr_status_cached[$val["status_id"]]["bgcolor"].';color:'.$arr_status_cached[$val["status_id"]]["textcolor"].'">'.$arr_status_cached[$val["status_id"]]["title"].'</span>';				
			}	
			
		}	
		$smarty->assign("list_stocks",$list_stocks);
		$html = $core->build("project".DS."_ajax.load_report_price_stock.tpl");
	}
	// Return
	echo json_encode([		
		"html"	=>	$html,
		"uid"	=>	$clsISO->getUniqid()	
	]); die();
}
function project_stock_sold(){
	global $clsISO,$assign_list,$dbconn,$title_page,$list_projects;
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsStock = new Stock();
	$lstStock = $clsStock->getAll(" `status_id`>0 and `status_id`='"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `agency_id`='"._AGENCY_FH_ID."' ",$clsStock->pkey.",ms_code,more_information,block_id");
	$arr_stock = [];
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_agency = $clsProperty->getArraySearchBykey("_AGENCY");
	
	$list_preloaders = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$assign_list["list_preloaders"] = $list_preloaders;
	$assign_list["arr_agency"] = $arr_agency;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách căn bán dự án - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function project_load_report_stock_sold(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$sub,$act,$_LANG_ID,$clsISO,$oneProfile,$profile_id,$clsConfiguration,$deviceType;
	$clsLog = new Log();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsProject  = new Project ();
	
	$uid = $clsISO->getUniqid();
	$project_id = (int)Input::post("project_id",0);
	$agency_id = (int)Input::post("agency_id",0);
	$start_date = Input::post("start_date","");
	$end_date = Input::post("end_date","");
	$start_date = !empty($start_date) ? $start_date : date("Y-m-d");
	$end_date = !empty($end_date) ? $end_date : date("Y-m-d");
	$start_time = strtotime($start_date);
	$end_time = strtotime(sprintf("%s 23:59:59",$start_date));
	$cond = "`agency_id`>0 AND `status_id`='"._STOCK_STATUS_SOLD_ID."' AND (`ms_date` BETWEEN {$start_time} AND {$end_time})";
	if(!empty($project_id)) {
		$cond .= " AND `project_id`='{$project_id}'";
	}
	if(!empty($agency_id)) {
		$cond .= " AND `agency_id`='{$agency_id}'";
	}
	$arr_stocks = array();
	$list_stock_sold = $clsStock->getAll($cond, "`{$clsStock->pkey}`,`agency_id`,`ms_code`,`ms_date`,`project_id`,`block_id`,`building_id`,`stock_type`,`bedroom_id`,`type_id`");
	$arr_project_cached = $clsProject->getListProject();
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_building_cached = $clsProperty->getArraySearchByKey("_BUILDING");
	$arr_ranger_cached = $clsProperty->getArraySearchByKey("_RANGE");
	$arr_agency_cached = $clsProperty->getArraySearchByKey("_AGENCY");
	$arr_bedroom_cached = $clsProperty->getArraySearchByKey("_BEDROOM");
	$arr_type_cached = $clsProperty->getArraySearchByKey("_TYPE_VILLA");
	if(!empty($list_stock_sold)){
		foreach($list_stock_sold as $key => $val){
			$list_stock_sold[$key]["project_name"] = !empty($arr_project_cached[$val["project_id"]]) ? $arr_project_cached[$val["project_id"]]["title"] : "--";
			$list_stock_sold[$key]["block_name"] = !empty($arr_block_cached[$val["block_id"]]) ? $arr_block_cached[$val["block_id"]]["title"] : "--";
			if($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$list_stock_sold[$key]["building_name"] = !empty($arr_building_cached[$val["building_id"]]) ? $arr_building_cached[$val["building_id"]]["title"] : "--";
				$list_stock_sold[$key]["type_name"] = !empty($arr_bedroom_cached[$val["bedroom_id"]]) ? $arr_bedroom_cached[$val["bedroom_id"]]["title"] : "--";
			}else{
				$list_stock_sold[$key]["building_name"] = !empty($arr_ranger_cached[$val["building_id"]]) ? $arr_ranger_cached[$val["building_id"]]["title"] : "--";
				$list_stock_sold[$key]["type_name"] = !empty($arr_type_cached[$val["type_id"]]) ? $arr_type_cached[$val["type_id"]]["title"] : "--";
			}
			$list_stock_sold[$key]["agency_name"] = !empty($arr_agency_cached[$val["agency_id"]]) ? $arr_agency_cached[$val["agency_id"]]["title"] : "--";
		}
	}
	$smarty->assign("list_stock_sold",$list_stock_sold);
	$html = $core->build($sub.DS."_ajax.load_report_sold.tpl");
	// Return 
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total'	=>	count($list_stock_sold)
	)); die();
}
?>