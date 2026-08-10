<?php
	global $core,$smarty,$dbconn, $clsISO,$profile_id;
	$clsTag = new Tag();
	$clsProperty = new Property();
	
	$smarty->assign("clsTag",$clsTag);
	
	
	$keyword = Input::get("keyword",""); $assign_list["keyword"] = $keyword;
	$tags = Input::get("tags",""); $assign_list["tags"] = $tags;
	$_ss_tag_ids = explode(",",$tags);
	$_ss_project_id = (int) Input::get('project_id', _PROJECT_DEF_ID);
	$blocks_ids = Input::get('blocks_ids',"");
	$_ss_blocks_ids = explode(",",$blocks_ids);
	$building_ids = Input::get('building_ids', "");
	$_ss_building_ids = explode(",",$building_ids);
	$status_id = Input::get("status_id","");
	$birthday = Input::get("birthday","");
	$date_call = Input::get("date_call","");
//	$clsProperty->setDeBug(1);
	$list_ss_blocks = $clsProperty->getAll("`is_trash`=0 and `for_id`='{$_ss_project_id}' and `title`<>'' and `property_type`='_BLOCK'","{$clsProperty->pkey},`property_code`,`title`,`parent_id`");
	if(!empty($_ss_blocks_ids)){
		$list_ss_buildings = array();
		foreach($_ss_blocks_ids as $block_id){
			$list_ss_buildings[$block_id] = $clsProperty->getAll("property_type='_BUILDING' and for_id='".$block_id."'", "{$clsProperty->pkey},title,more_information");
		}
		$smarty->assign('list_ss_buildings', $list_ss_buildings);
	}
	// and (`user_id`='{$profile_id}')
	$list_tags = $clsTag->getAll("`tag_type`='_data_central' ORDER BY `{$clsTag->pkey}` DESC","{$clsTag->pkey},`title`");
	$smarty->assign('list_tags', $list_tags);

	$block_id = (int) Input::get('block_id', 0);
	$oneBlock = $clsProperty->getOne($block_id,"parent_id");
	
	$smarty->assign("oneBlock",$oneBlock);
	$smarty->assign("list_ss_blocks",$list_ss_blocks);
	$smarty->assign("_ss_project_id",$_ss_project_id);
	$smarty->assign("_ss_blocks_ids",$_ss_blocks_ids);
	$smarty->assign("_ss_building_ids",$_ss_building_ids);
	$smarty->assign("_ss_tag_ids",$_ss_tag_ids);
	$smarty->assign("keyword",$keyword);
	$smarty->assign("status_id",$status_id);
	$smarty->assign("birthday",$birthday);
	$smarty->assign("date_call",$date_call);
?>