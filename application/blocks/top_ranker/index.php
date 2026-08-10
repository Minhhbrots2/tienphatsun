<?php
	// Block Thi đua định danh — hiển thị TẤT CẢ chương trình đang chạy, mỗi cái 1 bảng theo điều kiện riêng.
	global $core, $smarty, $clsISO, $oneProfile, $profile_id;
	$clsComp = new Competition();
	$programs = $clsComp->getPrograms();
	$boards = array();
	foreach($programs as $p){
		if(empty($p['status'])) continue;
		if(!$clsComp->visibleTo($p, $oneProfile)) continue; // phạm vi hiển thị
		$period = $clsComp->resolvePeriod($p['period_type'], $p['start_date'], $p['end_date']);
		$boards[] = array(
			'program'   => $p,
			'start_txt' => date('d/m/Y', $period[0]),
			'end_txt'   => date('d/m/Y', $period[1]),
			'ranking'   => $clsComp->rankingWithProfiles($p, 10),
		);
	}
	$smarty->assign('clsComp', $clsComp);
	$smarty->assign('boards', $boards);
	$smarty->assign('me_id', (int) $profile_id);
	$smarty->assign('current_year', date('Y'));
?>
