<?php $sub = $stdio->GET("sub", "default");$act = $stdio->GET("act", "default");
global $clsISO,$core;
if(!$clsISO->checkPermission('issue_access')){
	if(in_array($act, array('default','target','report'))){ $core->redirect('/'); }
	echo '_noaccess'; die();
}
$tmp = explode('/',__FILE__);$clsModule = new Module($tmp[count($tmp)-2]);$clsModule->run($sub, $act); $assign_list["sub"] = $sub;$assign_list["act"] = $act;?>