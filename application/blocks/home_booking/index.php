<?php 
	global $core, $smarty, $dbconn, $clsISO, $profile_id, $oneProfile;
	$clsCache = new Cache();
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$project_dir_selected = array('project_id' => 0, 'block_id' => 0);
	$list_preloaders = $type_of_date_arrs = $role_arrs = $arr_projects = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$type_of_date_arrs['date'] = 'Ngày';
	$type_of_date_arrs['week'] = 'Tuần';
	$type_of_date_arrs['month'] = 'Tháng';
	$type_of_date_arrs['year'] = 'Năm';
	
	$is_dir_sale = $is_dir_project = 0;
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY')){ // GĐKD
		$is_dir_sale = 1;
		$role_arrs[_ROLE_STAFF_SALE] = 'Sales';
		$role_arrs[_ROLE_GD_SALE] = 'GĐ KD';
	}
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){ // GĐDA
		$is_dir_project = 1;
		/** Vai trò */
		$role_arrs[_ROLE_STAFF_SALE] = 'Sales';
		$role_arrs[_ROLE_GD_PROJECT] = 'GĐ DA';
		/** Dự án đã được cấu hình */
		$prof_information = $oneProfile['more_information'];
		/** Dự án mình quản lý */
		/* $l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`,`t1`.`title` as `block_name`,`t2`.`title` as `project_name`";
		$r_field = "0 AS `block_id`,`project_id`,`title` as `block_name`,`title` as `project_name`";
		$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\")=1 AND JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\") LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_EXTRACT(`more_information`,\"$.is_booking\")=1 AND JSON_EXTRACT(`more_information`,\"$.project_admins_slash\") LIKE '%|{$profile_id}|%')"); */
	}
	$smarty->assign('current_week', date("W"));
	$smarty->assign('current_month', date("Y-m"));
	$smarty->assign('is_dir_sale', $is_dir_sale);
	$smarty->assign('is_dir_project', $is_dir_project);
	$smarty->assign('role_arrs', $role_arrs);
	$smarty->assign('type_of_date_arrs', $type_of_date_arrs);
	$smarty->assign('list_preloaders', $list_preloaders);
	$smarty->assign('arr_projects', $arr_projects);
?>