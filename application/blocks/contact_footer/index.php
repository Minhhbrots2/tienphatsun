<?php
	global $smarty,$clsConfiguration,$deviceType;
	$number_show = ($deviceType=='phone') ? 4 : 8;
	$smarty->assign('number_show', $number_show);
?>