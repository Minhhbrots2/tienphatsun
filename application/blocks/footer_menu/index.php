<?php
	global $core,$smarty;
	$clsMenu = new Menu();
	$smarty->assign('clsMenu',$clsMenu);
	
	$lstLink = $clsMenu->getLinks($menu_id);
	$smarty->assign('lstLink',$lstLink);
?>