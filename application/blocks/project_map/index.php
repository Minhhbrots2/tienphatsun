<?php 
	global $core, $dbconn, $clsISO;
	// $more_information = $oneProject['more_information'];
	// $more_information = $clsISO->to_array_json($more_information);
	$scriptJs = '<script type="text/javascript">
		var map_configs= {};
		map_configs[\'max_zoom\'] = '.$more_information['max_zoom'].';
		map_configs[\'tiles\'] = \''.$more_information['tiles_link'].'\';
		map_configs[\'max_bounds\'] = '.$core->get_field($more_information, 'max_bound','\'\'').';
		map_configs[\'center_point\'] = '.$core->get_field($more_information, 'center_point','\'\'').';
		map_configs[\'tms_enable\'] = '.((int) $core->get_field($more_information,'tms_enable',0) ==1 ? 'true': 'false').';
	</script>';
	$smarty->assign('scriptJs', $scriptJs);
	// $smarty->assign('project_information', $more_information);
?>