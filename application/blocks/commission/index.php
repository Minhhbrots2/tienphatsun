<?php
	global $core, $smarty, $clsISO, $profile_id, $oneProfile;
	$more_information = !empty($oneProfile) ? $clsISO->to_array_json($oneProfile['more_information']) : array();
	$cs = (isset($more_information['commission_summary']) && is_array($more_information['commission_summary'])) ? $more_information['commission_summary'] : array();
	$cs_total = isset($cs['total']) ? (int) $cs['total'] : 0;
	$cs_paid = isset($cs['paid']) ? (int) $cs['paid'] : 0;
	$cs_remain = isset($cs['remain']) ? (int) $cs['remain'] : 0;
	$cs_updated = isset($cs['updated']) ? (int) $cs['updated'] : 0;
	$smarty->assign('cs_total_fmt', number_format($cs_total, 0, ',', '.'));
	$smarty->assign('cs_paid_fmt', number_format($cs_paid, 0, ',', '.'));
	$smarty->assign('cs_remain_fmt', number_format($cs_remain, 0, ',', '.'));
	$smarty->assign('cs_updated_fmt', $cs_updated > 0 ? date('d/m/Y', $cs_updated) : '—');
?>