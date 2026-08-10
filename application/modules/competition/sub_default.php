<?php
// Thi đua định danh — trang bảng xếp hạng tổng hợp (front, mọi người xem).
// Điểm tính real-time từ billing qua Competition::computeScores (không ghi DB).

// Dựng danh sách xếp hạng đã gắn tên/avatar/hạng — dùng chung logic ở model.
function _competition_build_ranking($clsComp, $program, $limit = 50){
	return $clsComp->rankingWithProfiles($program, $limit);
}

function default_default(){
	global $assign_list, $smarty, $core, $mod, $clsISO, $profile_id, $oneProfile, $title_page, $description_page, $keyword_page;
	$clsComp = new Competition();
	// Ưu tiên URL đẹp /chuong-trinh-{hash}; hash sai → only_id=-1 (không khớp chương trình nào). Fallback ?id=.
	$hash = trim(Input::request('h', ''));
	if($hash !== ''){
		$oneP = $clsComp->getProgramByHash($hash);
		$only_id = !empty($oneP) ? (int) $oneP['id'] : -1;
	} else {
		$only_id = (int) Input::request('id', 0);
	}
	$programs = $clsComp->getPrograms();
	$active_programs = array();
	foreach($programs as $p){ if(!empty($p['status'])) $active_programs[] = $p; }
	// Trang này hiển thị danh sách ĐẦY ĐỦ (cap 1000). Có id → chỉ chương trình đó; không → tất cả chương trình.
	$boards = array();
	foreach($active_programs as $p){
		if($only_id > 0 && (int) $p['id'] !== $only_id) continue;
		if(!$clsComp->visibleTo($p, $oneProfile)) continue;
		$period = $clsComp->resolvePeriod($p['period_type'], $p['start_date'], $p['end_date']);
		$boards[] = array(
			'program'   => $p,
			'start_txt' => date('d/m/Y', $period[0]),
			'end_txt'   => date('d/m/Y', $period[1]),
			'ranking'   => _competition_build_ranking($clsComp, $p, 1000),
		);
	}
	$assign_list['clsComp'] = $clsComp;
	$assign_list['active_programs'] = $active_programs;
	$assign_list['boards'] = $boards;
	$assign_list['me_id'] = (int) $profile_id;
	$title_page = ($only_id > 0 && !empty($boards)) ? ('Bảng xếp hạng: ' . $boards[0]['program']['name'] . ' - ' . PAGE_NAME) : ('Thi đua định danh - ' . PAGE_NAME);
	$assign_list['title_page'] = $title_page;
	$assign_list['description_page'] = $title_page;
	$assign_list['keyword_page'] = $title_page;
}

// AJAX: đổi chương trình không reload — trả HTML bảng (dùng cho trang + swap block TOP 10).
function default_load_ranking(){
	global $core, $smarty;
	$clsComp = new Competition();
	$program_id = (int) Input::request('program_id', 0);
	$program = ($program_id > 0) ? $clsComp->getProgram($program_id) : array();
	if(empty($program)){
		$active = array();
		foreach($clsComp->getPrograms() as $p){ if(!empty($p['status'])){ $active = $p; break; } }
		$program = $active;
	}
	$limit = (int) Input::request('limit', 50);
	$ranking = _competition_build_ranking($clsComp, $program, $limit);
	$smarty->assign('program', $program);
	$smarty->assign('ranking', $ranking);
	$smarty->assign('clsComp', $clsComp);
	$html = $core->build('_ajax.ranking.tpl');
	echo $html; die();
}

// AJAX cho BLOCK dashboard (thay "TOP 10 THI ĐUA" cũ): trả JSON {html: <tr>...</tr>} để loader .ajax inject vào tbody.
function default_block_ranking(){
	global $core, $smarty;
	$clsComp = new Competition();
	$program_id = (int) Input::request('program_id', 0);
	$program = ($program_id > 0) ? $clsComp->getProgram($program_id) : array();
	if(empty($program)){
		foreach($clsComp->getPrograms() as $p){ if(!empty($p['status'])){ $program = $p; break; } }
	}
	$ranking = _competition_build_ranking($clsComp, $program, 10);
	$smarty->assign('ranking', $ranking);
	$html = $core->build('_ajax.rows.tpl');
	echo json_encode(array('result' => 1, 'html' => $html), JSON_UNESCAPED_UNICODE); die();
}

// AJAX: chi tiết điểm 1 người trong 1 chương trình (popup) — liệt kê các giao dịch tạo điểm.
function default_detail(){
	global $core, $smarty, $clsISO;
	$clsComp = new Competition();
	$clsProfile = new Profile();
	$program_id = (int) Input::request('program_id', 0);
	$staff_id = (int) Input::request('staff_id', 0);
	$program = ($program_id > 0) ? $clsComp->getProgram($program_id) : array();
	$breakdown = $clsComp->pointBreakdown($program, $staff_id);
	$total = 0;
	foreach($breakdown as $b){ $total += $b['score']; }
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('program', $program);
	$smarty->assign('staff_name', $clsProfile->getFullName($staff_id));
	$smarty->assign('breakdown', $breakdown);
	$smarty->assign('total_score', $total * 1);
	$html = $core->build('_ajax.detail.tpl');
	echo json_encode(array('result' => 1, 'html' => $html, 'uid' => $uid), JSON_UNESCAPED_UNICODE); die();
}
?>
