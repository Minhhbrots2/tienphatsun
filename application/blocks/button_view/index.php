<?php
	global $core,$smarty,$mod,$sub,$act;
	$_ss_view_docs = 'grid';
	if(vnSessionExist('_ss_view_docs')){
		$_ss_view_docs = vnSessionGetVar('_ss_view_docs');
	}
	$smarty->assign("_ss_view_docs",$_ss_view_docs);
	$type = "";
	if($act == "default") {
		$type = "pin";
	}
	$smarty->assign("type",$type);
?>