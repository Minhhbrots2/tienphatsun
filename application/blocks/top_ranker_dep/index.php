<?php
	global $core,$smarty,$clsProduct,$oneProfile,$clsISO;
	$clsProperty = new Property();
	#
	$lstDepartment = $clsProperty->getAll("is_trash=0 and property_type='_DEPARTMENT' and parent_id='"._DEPARTMENT_SALE_ID."' order by order_no ASC", "{$clsProperty->pkey},title");
	#
	$smarty->assign("clsProperty",$clsProperty);
	$smarty->assign("department_name",$clsProperty->getTitle($oneProfile["department_id"]));
	$smarty->assign("lstDepartment",$lstDepartment);
?>