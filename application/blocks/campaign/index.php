<?php 
	global $smarty, $core, $dbconn, $clsISO;
	$clsProfile = new Profile();
	$clsCampaign = new Campaign();
	$clsProperty = new Property();
	$clsBilling  = new Billing();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCampaign', $clsCampaign);
	
	$currentNow = time();
	$oneCampaign = $clsCampaign->getByCond("`start_date`<'{$currentNow}' 
	and `end_date`>'{$currentNow}' order by end_date ASC limit 0,1");
	if(!empty($oneCampaign)){
		$campaign_id = $oneCampaign[$clsCampaign->pkey];
		$start_date = $oneCampaign['start_date'];
		$end_date = $oneCampaign['end_date'];
		$campaign_info = $oneCampaign['campaign_info'];
		$campaign_info = !empty($campaign_info) 
			? json_decode(html_entity_decode($campaign_info), true) : array();
		// $clsISO->print_pre($campaign_info); die();
		if(!empty($campaign_info)){
			if($oneCampaign['is_terms']==1){
				$campaign_terms = $oneCampaign['campaign_terms'];
				$campaign_terms = !empty($campaign_terms) 
					? json_decode(html_entity_decode($campaign_terms), true) 
					: array();
				$list_terms = array();
				if(!empty($campaign_terms)){
					foreach($campaign_terms as $key => $val){
						if(!empty($val)){
							$list_terms[$key] = $val;
						}
					}
				}
				$smarty->assign('list_terms', $list_terms);
                if($oneCampaign['selector']=='staff'){
                    $cond = "`is_trash`=0 and `profile_type`<>'_sale' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
                    $cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
                    if($campaign_info['opt_staff']=='_sale'){
                        $cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%' and `role_id`<>'"._ROLE_GD_PROJECT."'";
                    } else if($campaign_info['opt_staff']=='_sale2'){
                        $cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";
                    } else if($campaign_info['opt_staff']=='_select'){
                        $ids = isset($campaign_info['staff']) && !empty($campaign_info['staff']) 
                            ? $campaign_info['staff'] : array();
                        if(!empty($ids)){
                            $cond.= " and {$clsProfile->pkey} in (".implode(',', $ids).")";
                        }
                    }
                    $field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";
                    $list_staffs = $clsProfile->getAll($cond, $field);
                    if(!empty($list_staffs)){
                        foreach($list_staffs as $key => $val){
                            $staff_id = $val[$clsProfile->pkey];
                            #- Điểm
                            $total_scores = 0;
                            $field = "{$clsBilling->pkey},billing_type";
                            $list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);
                            if(!empty($list_billings)){
                                foreach($list_billings as $okey => $oval){
                                    $total_scores += floatval($campaign_terms[$oval['billing_type']]);
                                }
                            }
                            $list_staffs[$key]['full_name'] = $clsProfile->getIndentityV2($staff_id, $val, false);
                            $list_staffs[$key]['total_scores'] = $total_scores;
                        }
                        $total_scores_arrs = @array_column($list_staffs, 'total_scores');
                        @array_multisort($total_scores_arrs, SORT_DESC, $list_staffs);
                    }
                    $smarty->assign('list_staffs', $list_staffs);
                } else {
                    $list_groups = array();
                    foreach($campaign_info as $key => $val){
                        $group_members = $val['group_members'];
                        #- Điểm
                        $total_scores = 0;
                        $field = "{$clsBilling->pkey},billing_type";
                        $list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);
                        if(!empty($list_billings)){
                            foreach($list_billings as $okey => $oval){
                                $total_scores += floatval($campaign_terms[$oval['billing_type']]);
                            }
                        }
                        $list_groups[] = array(
                            'name' => $val['group_name'],
                            'group_members' => $clsProfile->getShortNameArray($group_members),
                            'total_scores' => $total_scores
                        );
                    }
                    $total_scores_arrs = @array_column($list_groups, 'total_scores');
                    @array_multisort($total_scores_arrs, SORT_DESC, $list_groups);
                    $smarty->assign('list_groups', $list_groups);
                }  
			} else {
                $list_groups = array();
				if(!empty($campaign_info)){
					foreach($campaign_info as $key => $val){
						$group_members = $val['group_members'];
						$total_scores = 0;
						$list_groups[] = array(
							'name' => $val['group_name'],
							'group_members' => $clsProfile->getNameArray($group_members),
							'total_scores' => $total_scores
						);
					}
				}
			}
		}
		$smarty->assign('campaign_id', $campaign_id);
	}
	#- Tổng số giao dịch
	$total_transactions = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."'");
	$smarty->assign('total_transactions', $total_transactions);
	$smarty->assign('oneCampaign', $oneCampaign);
	// $clsISO->print_pre($oneCampaign); die();
?>