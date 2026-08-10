<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
	global $core, $dbconn, $clsISO,$clsConfiguration;
	$clsNews = new News(); $smarty->assign('clsNews',$clsNews);
	$clsNewsBlock = new NewsBlock(); $smarty->assign('clsNewsBlock',$clsNewsBlock);
	#
	$blocks_info = $clsConfiguration->getValue('blocks_info');
	$blocks_info = !empty($blocks_info) ? @json_decode(html_entity_decode($blocks_info), true) : array();
	$smarty->assign('blocks_info',$blocks_info);
?>