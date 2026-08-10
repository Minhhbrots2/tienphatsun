<?php
	global $core,$smarty,$profile_id,$oneProfile,$assign_list;
	$clsProperty = new Property();
	$clsDocs = new Docs();
	$clsProfile = new Profile();
	$clsFolder = new Folder();
	$show = Input::get("show","cat");
	$cat_id = (int)Input::get("cat_id",0);
	$authorized_person = (int)Input::get("p",0);
	$effective_date = Input::get("d","");
	$keyword = Input::get("k","");

	$list_department_id = $clsISO->getArrayByTextSlash($oneProfile['list_department_id']);
	$cond = "(`user_id`='{$profile_id}' OR `type`='0'";
	if(!empty($list_department_id)) {
		$cond .= " OR (`type`='1' AND (";
		foreach($list_department_id as $k => $department_id) {
			$cond .= (($k == 0) ? "" : " OR ") . "`department_ids` LIKE '%|{$department_id}|%'";
		}
		$cond .="))";
	}
	$cond .=")";
	$lstCategory_doc = $clsFolder->getAll($cond);
	$list_staffs = $clsProfile->getAll("`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $clsProfile->pkey.",full_name");
	$smarty->assign("lstCategory_doc",$lstCategory_doc);
	$smarty->assign("list_staffs",$list_staffs);
	$smarty->assign("clsProperty",$clsProperty);
	$smarty->assign("clsDocs",$clsDocs);
	$smarty->assign("clsFolder",$clsFolder);
	$smarty->assign("show",$show);
	$smarty->assign("cat_id",$cat_id);
	$smarty->assign("authorized_person",$authorized_person);
	$smarty->assign("effective_date",$effective_date);
	$smarty->assign("keyword",$keyword);
	
?>