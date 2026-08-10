<?php
	global $core, $smarty, $clsISO, $profile_id, $deviceType;
	$clsProfile = new Profile();
	$clsBirthday = new Birthday();
	$clsProperty = new Property();
	$smarty->assign("clsProfile",$clsProfile);
	$smarty->assign("clsBirthday",$clsBirthday);
	###
	$profile_arrs = array();
	$list_birthdays = $clsBirthday->getAll("FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."' 
		and `profile_id`='{$profile_id}'");
	if(!empty($list_birthdays)){
		foreach($list_birthdays as $key => $val){
			$profile_arrs[] = $val['staff_id'];
		}
	}
	$sql_birthday = "";
	if(!empty($profile_arrs)) {
		$sql_birthday = " AND `profile_id` NOT IN (".implode(',',$profile_arrs).") ";
	}
	$field = "{$clsProfile->pkey},`full_name`,`role_id`,`department_id`";
	$birthday = $clsProfile->getByCond("`is_trash`=0 and `is_active`='1' 
		and FROM_UNIXTIME(`birthday`,'%d/%m')='".date('d/m')."'".$sql_birthday, $field);
	if(!empty($birthday)) {
		$role_id = $birthday['role_id'];
		$department_id = $birthday['department_id'];
		$role_name = $clsProperty->getTitle($role_id);
		$department_name = $clsProperty->getTitle($department_id);
		$birthday['role_name'] = $role_name;
		$birthday['department_name'] = $department_name;
		$clsBirthday->insert(array(
			'staff_id' => $birthday[$clsProfile->pkey],
			'profile_id' => $profile_id,
			'reg_date' => time()
		));
	}
	###
	$fontSize = 50;
	$lineHeight = 60;
	$charSpacing = 40;
	if($deviceType == 'phone'){
		$fontSize = 20;
		$lineHeight = 30;
		$charSpacing = 20;
	}
	$smarty->assign("birthday",$birthday);
	$smarty->assign("fontSize",$fontSize);
	$smarty->assign("lineHeight",$lineHeight);
	$smarty->assign("charSpacing",$charSpacing);
?>