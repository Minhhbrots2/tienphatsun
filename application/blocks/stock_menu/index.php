<?php 
	global $core,$smarty;
	$clsProject = new Project();
	$clsProperty = new Property();
	$list_projects = $list_quick_menus = array();
	$list_mwf_builings = $list_lsb_builings = $list_mga_builings = array();
	$field = "{$clsProject->pkey},code,title,link,is_menu";
	$list_projects = $clsProject->getAllCache("`is_menu`='1' order by `reg_date` ASC", $field);
	// $clsISO->print_pre($list_projects); die();
	$lstBlockHighFloor = [];
	if(!empty($list_projects)){
		foreach($list_projects as $key => $val){
			$project_id = $val[$clsProject->pkey];
			$list_projects[$key]['order_no'] = ($project_id==_PROJECT_VHOP2_ID) ? 1 : 0;
			$list_blocks = $clsProperty->getOItems('_BLOCK', $project_id,"title,parent_id,more_information");
			if(!empty($list_blocks)){
				foreach($list_blocks as $okey => $oval){
					$block_id = $oval[$clsProperty->pkey];
					$order_no = ($block_id == _PROJECT_BLOCK_MGA_ID) ? 2 : 1;
					if($oval['parent_id']==_BLOCK_TYPE_HIGHLEVEL_SALE){ // Cao tầng
						$list_builings = $clsProperty->getOItems('_BUILDING', $block_id, "`title_vn`,`for_id`,`more_information`");
						if(!empty($list_builings)){
							$arr_building = [];
							foreach($list_builings as $mkey => $mval){
								$building_id = $mval[$clsProperty->pkey];
								$more_information = $mval['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								if(isset($more_information['is_menu']) && (int) $more_information['is_menu'] == 1){
									$arr_building[] = array(
										'order_no' => $order_no,
										'title' => $mval['title'],
										'link' => sprintf('/project/p%s/b%s.html', $project_id, $building_id)
									);
								}
							}							
							if(!empty($arr_building)) {
								$list_blocks[$okey]['building'] = $arr_building;	
								$lstBlockHighFloor[] = $list_blocks[$okey];
							}								
						}					
					}
				}
				
			}
		}
	}
	$arrs_order_no = array_column($lstBlockHighFloor, "order_no");
	array_multisort($arrs_order_no, SORT_DESC, $lstBlockHighFloor);
//$clsISO->print_pre($lstBlockHighFloor);die;
	$smarty->assign("lstBlockHighFloor",$lstBlockHighFloor);
?>