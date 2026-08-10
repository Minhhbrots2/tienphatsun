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
function news_load_block_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$html_dropdowns = '';
	$uid = $clsISO->getUniqid();
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$type = Input::post('type', "block");
	if($type == "block") {
		$cond .= "`for_id`='{$project_id}' AND `property_type`='_BLOCK'";
	}else{
		$cond .= "`for_id`='{$block_id}' AND (`property_type`='_BUILDING' OR `property_type`='_RANGE')";
	}
	$field = "{$clsProperty->pkey},title";	
	$listItem = $clsProperty->getAll($cond, $field);
	$html = "";
	if(!empty($listItem)){
		if($type == "block") {
			$html = '<option value="">Chọn phân khu</option>';
		}else{
			$html = '<option value="">Chọn Tòa/Dãy</option>';
		}
		foreach($listItem as $key => $val){
			$html .= '<option value="'.$val["property_id"].'">'.$val["title"].'</option>';
		}
		unset($listItem);
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function news_load_tags(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsTag = new Tag();
	$clsProject = new Project();
	$clsProperty = new Property();
	###
	$results = array();
	$list_tags = Input::post('list_tags',array());
	$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`image`,`more_information`";
	$lstProjects = $clsProject->getAll("`is_menu`='1' order by `reg_date` ASC", $field);
	$html = "";
	if(!empty($lstProjects)) {
		foreach($lstProjects as $k_pj => $v_pj) {
			$html .= "<option value='DA-".$v_pj['project_id']."' ".($clsISO->checkItemInArray("DA-".$v_pj['project_id'],$list_tags) ? 'selected' : '').">".$v_pj['title']."</option>";
			$lstBlock = $clsProperty->getAll("`for_id`='".$v_pj['project_id']."' AND `property_type`='_BLOCK'", $clsProperty->pkey.',title,parent_id');
			if(!empty($lstBlock)) {
				foreach($lstBlock as $k_bl => $v_bl){
					$html .= "<option value='PK-".$v_bl['property_id']."' ".(($clsISO->checkItemInArray("PK-".$v_bl['property_id'],$list_tags)) ? 'selected' : '').">--Phân khu ".$v_bl['title']."</option>";
					$lstBuilding = $clsProperty->getAll("`for_id`='".$v_bl['property_id']."' AND (`property_type`='_BUILDING' OR `property_type`='_RANGE')",$clsProperty->pkey.',title,parent_id');
					if(!empty($lstBuilding)) {
						foreach($lstBuilding as $k_bu => $v_bu){
							if($v_bl['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
								$html .= "<option value='TD-".$v_bu['property_id']."' ".(($clsISO->checkItemInArray("TD-".$v_bu['property_id'],$list_tags)) ? 'selected' : '').">----Tòa ".$v_bu['title']."</option>";
							}							
						}
					}
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		"html"	=>	$html,
		"list_tags"	=>	$list_tags
	), JSON_UNESCAPED_UNICODE);
	die();
}