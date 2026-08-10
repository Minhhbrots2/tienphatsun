<?php
	global $smarty, $core, $dbconn;
	$clsSlide = new Slide(); 
	$smarty->assign('clsSlide',$clsSlide);
	#
	$field = "{$clsSlide->pkey},title,slogan,intro,link,image";
	$lstSlide = $clsSlide->getAll("is_trash=0 and is_online=1 and image<>'' order by order_no DESC", $field);
	//$clsISO->print_pre($lstSlide, true); die();
	$smarty->assign('lstSlide',$lstSlide);
?>