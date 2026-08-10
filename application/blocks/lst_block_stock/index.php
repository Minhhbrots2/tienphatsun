<?php
	global $core,$smarty,$list_projects,$profile_id,$oneProfile,$oneProject,$project_id,$building_id,$mod,$act;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject',$clsProject);
	$smarty->assign('clsProperty',$clsProperty);
	$clsCache = new Cache();
	$arr_block = [];
	if( $clsCache->has('_ss_project_cached')){
		$lst_projects = $clsCache->get('_ss_project_cached');
		$clsCache->delete('_ss_project_cached');
		foreach($lst_projects as $key => $val){
			$lst_projects[$key]['textcolor'] = '#696cff ';
			$lst_projects[$key]['bgcolor'] = '#ffffffff';
			if (in_array($val['code'], array_keys($colorProject))) {
				$lst_projects[$key]['bgcolor'] = $colorProject[$val['code']];
				$lst_projects[$key]['textcolor'] = '#ffffffff';
			}
			$list_blocks = !empty($val['block']) ? $val['block'] : [];
			if(!empty($list_blocks)){
				foreach($list_blocks as $okey => $oval){
					// Danh sách phân khu
					$list_blocks[$okey]['project_name'] = $val['title'];
					$list_blocks[$okey]['project_id'] = $val['project_id'];
					$list_blocks[$okey]['project_info'] = $lst_projects[$key];
					$list_all_blocks[$oval['property_id']] = $list_blocks[$okey];
					if(!empty($oval["more_information"]["is_hot"])) {
						$arr_block[] = $oval["property_id"];	
					}
				}
			}
		}
	} else {
		$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`more_information`";
		$lst_projects = $clsProject->getAll("`is_menu`='1' order by `reg_date` ASC", $field);
		if(!empty($lst_projects)){
			foreach($lst_projects as $key => $val){
				$project_id = $val[$clsProject->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$logo = isset($more_information['logo']) ? $more_information['logo'] : "";
				$lst_projects[$key]['logo'] = $logo;
				$lstBlockHighFloor = [];
				$lst_projects[$key]['order_no'] = ($project_id==_PROJECT_VHOP2_ID) ? 1 : 0;
				#color project
				$lst_projects[$key]['textcolor'] = '#696cff ';
				$lst_projects[$key]['bgcolor'] = '#ffffffff';
				if (in_array($val['code'], array_keys($colorProject))) {
					$lst_projects[$key]['bgcolor'] = $colorProject[$val['code']];
					$lst_projects[$key]['textcolor'] = '#ffffffff';
				}

				$list_blocks = $clsProperty->getOItems('_BLOCK',$project_id,"title,property_code,parent_id,more_information");
				if(!empty($list_blocks)){
					foreach($list_blocks as $okey => $oval){
						$block_id = $oval[$clsProperty->pkey];
						$order_no = ($block_id == _PROJECT_BLOCK_MGA_ID) ? 2 : 1;
						$more_information = $oval['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$list_blocks[$okey]['more_information'] = $more_information;
						if($oval['parent_id']==_BLOCK_TYPE_HIGHLEVEL_SALE){ // Cao tầng
							if(!empty($more_information["is_hot"])) {
								$arr_block[] = $oval["property_id"];	
							}
							$list_builings = $clsProperty->getOItems('_BUILDING', $block_id, "`property_code`,`for_id`,`more_information`");
							if(!empty($list_builings)){
								$arr_building = [];
								foreach($list_builings as $mkey => $mval){
									$building_id = $mval[$clsProperty->pkey];
									$more_information_bl = $mval['more_information'];
									$more_information_bl = $clsISO->to_array_json($more_information_bl);
									if(isset($more_information_bl['is_menu']) && (int) $more_information_bl['is_menu'] == 1 && empty($more_information_bl['is_out_stock'])){
										$arr_building[] = array(
											'order_no' => $order_no,
											'title' => sprintf('%s(%s)', $mval['title'], $mval['property_code']),
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
						## Danh sách phân khu
						$list_blocks[$okey]['project_name'] = $val['title'];
						$list_blocks[$okey]['project_id'] = $val['project_id'];
						$list_blocks[$okey]['project_info'] = $lst_projects[$key];
						$list_all_blocks[$oval['property_id']] = $list_blocks[$okey];
					}
				}
				$lst_projects[$key]['block'] = $lstBlockHighFloor;
			}
		}
		$clsCache->put('_ss_project_cached', $lst_projects);
	}
	$clsStock = new Stock();
	$more_profile = $oneProfile["more_information"];
	$sorted = [];
	if(!empty($more_profile["order_block"])) {
		$arr_block = $more_profile["order_block"];
//			$clsISO->print_pre($arr_block);die;
		foreach ($arr_block as $blockId) {
			if (array_key_exists($blockId, $list_all_blocks)) {
				$sorted[$blockId] = $list_all_blocks[$blockId];
			}
		}
	}else{
		$lstTotalStock = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `block_id` IN (".implode(',',$arr_block).") AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `agency_id`>0 GROUP BY block_id ORDER BY total DESC","`block_id`,COUNT(`stock_id`) as total");
		foreach($lstTotalStock as $key => $val) {
			if (array_key_exists($val["block_id"], $list_all_blocks)) {
				$sorted[$val["block_id"]] = $list_all_blocks[$val["block_id"]];
			}
		}
	}

	$remaining = array_diff_key($list_all_blocks, $sorted);
	$sorted = array_merge($sorted, $remaining);
	## end
	$smarty->assign('lstBlocksMenu',$sorted);

?>