<?php
	global $core,$smarty,$profile_id,$oneProfile,$dbconn,$clsISO,$dev;	
	$clsProperty = new Property();
	$arr_deparment_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$list_dep_area = $clsISO->buildTree($arr_deparment_cached, _DEPARTMENT_SALE_ID, 'property_id');
	$department_id = $oneProfile['department_id'];
	if(isset($list_dep_area[$department_id])) {
		$oneDepartment = $list_dep_area[$department_id];
	}else{
		$oneDepartment = $arr_deparment_cached[$department_id];
	}
	$smarty->assign('oneDepartment', $oneDepartment);
?>