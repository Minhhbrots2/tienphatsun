<?php
	global $core,$smarty,$dbconn,$profile_id,$oneProfile;
	$clsCity = new City();
	$clsCountry = new Country();
	$clsProfile = new Profile();
	$smarty->assign('clsCity', $clsCity);
	$smarty->assign('clsCountry', $clsCountry);
	$crm_field = ($holderG == '_report') ? 'search_report_field' : 'search_field';
	$smarty->assign('crm_field', $crm_field);
	###
	$keysearch = Input::get('keysearch');
	$smarty->assign('keysearch', $keysearch);
	###
	$list_date_ranges = array(
		'0-3' => '3 ngày trước',
		'4-7' => '4-7 ngày trước',
		'8-15' => '8-15 ngày trước',
		'16-30' => '16-30 ngày trước',
		'31-60' => '31-60 ngày trước',
		'61-120' => '61-120 ngày trước'
	);
	$smarty->assign('list_date_ranges', $list_date_ranges);
	###
	$clsCampaign = new Campaign();
	$cond = "`is_trash`=0 and `campaign_type`='_campaign'";
	$field = "{$clsCampaign->pkey},title";
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	if($department_id == _DEPARTMENT_MKT_ID){
		$cond.= " and `user_id` in (
			select `profile_id` from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='"._DEPARTMENT_MKT_ID."'
		)";
	} else {
		$cond.= " and `user_id`='{$profile_id}'";
	}
	$list_campaigns = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", $field);
	$smarty->assign('list_campaigns', $list_campaigns); unset($list_campaigns);
?>