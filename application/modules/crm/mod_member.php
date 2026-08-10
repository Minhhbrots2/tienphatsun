<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Sale thị trường → CRM (member MOC/MF → default_customer)         # ||
|| # Future Homes Group — CRM frontend module                        # ||
|| #################################################################### ||
\*======================================================================*/

/**
 * Quyền vào màn "Sale thị trường → CRM": quản lý (full-permiss) HOẶC cờ permiss_mod['sale_market_crm'].
 * permiss_mod đã merge own+role ở _header → $oneProfile['permiss_mod'] (mảng). Gate cho cả list lẫn convert.
 */
function _crm_can_marketplace_pool(){
	global $oneProfile;
	$clsCustomer = new Customer();
	if ($clsCustomer->isFullPermiss()) {
		return true;
	}   // quản lý luôn vào được
	return !empty($oneProfile['permiss_mod']['sale_market_crm']);
}

/**
 * Profile $pid có quyền sale_market_crm (đọc permiss_mod RIÊNG của họ) — validate người nhận khi gán.
 */
function _crm_profile_has_market_perm($pid){
	global $dbconn, $clsISO;
	$pid = (int) $pid;
	if ($pid <= 0) {
		return false;
	}
	$clsProfile = new Profile();
	$pm = $dbconn->getOne("SELECT `permiss_mod` FROM `" . $clsProfile->tbl . "` WHERE `" . $clsProfile->pkey . "`=" . $pid);
	if (empty($pm)) {
		return false;
	}
	$arr = $clsISO->to_array_json($pm);
	return !empty($arr['sale_market_crm']);
}

/**
 * Đọc bộ lọc Sale thị trường từ request (dùng chung cho vỏ trang + AJAX). Guard mảng ?kw[]=… → ''.
 */
function _crm_mktpool_filters(){
	$kw       = Input::request('kw', '');
	$kw      = is_array($kw)      ? '' : trim((string)$kw);
	$f_ptype  = Input::request('f_ptype', '');
	$f_ptype = is_array($f_ptype) ? '' : (string)$f_ptype;   // '' | MOC | MF
	$f_tacc   = Input::request('f_tacc', '');
	$f_tacc  = is_array($f_tacc)  ? '' : (string)$f_tacc;    // '' | 0 | 1 | 2
	$f_from   = Input::request('f_from', '');
	$f_from  = is_array($f_from)  ? '' : trim((string)$f_from); // yyyy-mm-dd
	$f_to     = Input::request('f_to', '');
	$f_to    = is_array($f_to)    ? '' : trim((string)$f_to);
	$f_unconv = (int) Input::request('f_unconv', 0);
	return array('kw' => $kw, 'f_ptype' => $f_ptype, 'f_tacc' => $f_tacc, 'f_from' => $f_from, 'f_to' => $f_to, 'f_unconv' => $f_unconv);
}

/**
 * GĐ2 — Vỏ trang Sale thị trường (MOC/MF). Chỉ dựng khung + bộ lọc; KPI + bảng + phân trang nạp AJAX
 * qua default_load_sale_marketplace() → $Core.crm.mktpool_load(). Route: crm/sale-thi-truong.html.
 */
function default_sale_marketplace_crm(){
	global $assign_list, $dbconn, $title_page, $profile_id, $clsISO;

	if (!_crm_can_marketplace_pool()) {
		$assign_list['denied'] = 1;
		return;
	}

	$clsCustomer  = new Customer();
	$has_link_col = !empty($dbconn->getOne("SHOW COLUMNS FROM `" . $clsCustomer->tbl . "` LIKE 'member_profile_id'"));

	$assign_list['has_link_col'] = $has_link_col;
	$assign_list['f']            = _crm_mktpool_filters();   // prefill khi deep-link ?kw=…

	// Gán cho người khác: chỉ quản lý (full-permiss) mới chọn; danh sách người nhận = người có quyền sale_market_crm.
	$can_assign = $clsCustomer->isFullPermiss() ? 1 : 0;
	$assignees  = array();
	if ($can_assign) {
		$clsProfile = new Profile();
		$rows = $dbconn->getAll("SELECT `" . $clsProfile->pkey . "` AS `pid`, `full_name`, `permiss_mod` FROM `" . $clsProfile->tbl . "` WHERE `permiss_mod` LIKE '%\"sale_market_crm\"%' ORDER BY `full_name`");
		foreach ((array) $rows as $r) {
			$pm = $clsISO->to_array_json($r['permiss_mod']);   // lọc khớp validator: bỏ entry tắt (":0")
			if (empty($pm['sale_market_crm'])) {
				continue;
			}
			$assignees[] = array('id' => (int) $r['pid'], 'name' => (string) $r['full_name']);
		}
	}
	$assign_list['mkt_can_assign'] = $can_assign;
	$assign_list['mkt_assignees']  = $assignees;
	$assign_list['mkt_me']         = (int) $profile_id;

	$title_page = 'Sale thị trường → CRM - ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
}

/**
 * AJAX — danh sách Sale thị trường: lọc + phân trang + cờ trạng thái + KPI theo trang.
 * Trả {html} render từ _ajax.sale_marketplace_crm.tpl. Pre-render reg_text/_ini vì partial KHÔNG có $clsISO.
 */
function default_load_sale_marketplace(){
	global $smarty, $core, $dbconn, $clsISO;

	if (!_crm_can_marketplace_pool()) {
		echo json_encode(array('html' => '<div class="alert alert-danger mb-0">Bạn không có quyền truy cập màn này.</div>'), JSON_UNESCAPED_UNICODE);
		die();
	}

	$clsMember    = new Member();
	$clsCustomer  = new Customer();
	$has_link_col = !empty($dbconn->getOne("SHOW COLUMNS FROM `" . $clsCustomer->tbl . "` LIKE 'member_profile_id'"));

	$f    = _crm_mktpool_filters();
	$page = max(1, (int) Input::request('page', 1));
	$per  = 500;

	// build $cond (escape MỌI input)
	$cond = "`profile_type` IN ('MOC','MF') AND `is_trash`=0";
	if ($f['kw'] !== '') {
		$kw_esc = $dbconn->qstr('%' . $f['kw'] . '%');
		$cond .= " AND (`full_name` LIKE $kw_esc OR `phone` LIKE $kw_esc)";
	}
	if ($f['f_ptype'] === 'MOC' || $f['f_ptype'] === 'MF') {
		$cond .= " AND `profile_type`=" . $dbconn->qstr($f['f_ptype']);
	}
	if ($f['f_tacc'] !== '' && is_numeric($f['f_tacc'])) {
		$cond .= " AND `type_account_id`=" . (int)$f['f_tacc'];
	}
	if ($f['f_from'] !== '') {
		$ts = strtotime($f['f_from'] . ' 00:00:00');
		if ($ts) {
			$cond .= " AND `reg_date`>=" . $ts;
		}
	}
	if ($f['f_to']   !== '') {
		$ts = strtotime($f['f_to'] . ' 23:59:59');
		if ($ts) {
			$cond .= " AND `reg_date`<=" . $ts;
		}
	}
	if ($f['f_unconv'] === 1 && $has_link_col) {
		$cond .= " AND NOT EXISTS(SELECT 1 FROM `" . $clsCustomer->tbl . "` c WHERE c.`member_profile_id`=`" . $clsMember->tbl . "`.`profile_id`)";
	}

	$total      = (int) $clsMember->countItem($cond);
	$total_page = max(1, (int) ceil($total / $per));
	if ($page > $total_page) {
		$page = $total_page;
	}
	$offset = ($page - 1) * $per;

	$rows = $clsMember->getAll(
		$cond . " ORDER BY `reg_date` DESC LIMIT $offset,$per",
		"profile_id,code,full_name,phone,email,profile_type,type_account_id,role_id,reg_date,avatar"
	);

	// cờ 2-trạng-thái cho ĐÚNG trang (1 query)
	$converted = array();
	$phonedup  = array();
	if (!empty($rows)) {
		$pids = array();
		$phones = array();
		foreach ($rows as $r) {
			$pids[] = (int) $r['profile_id'];
			$ph = trim((string) $r['phone']);
			if ($ph !== '') {
				$phones[$ph] = true;
			}
		}
		$where = array();
		if ($has_link_col) {
			$where[] = "`member_profile_id` IN (" . implode(',', $pids) . ")";
		}
		if (!empty($phones)) {
			$q = array();
			foreach (array_keys($phones) as $ph) {
				$q[] = $dbconn->qstr($ph);
			}
			$where[] = "`phone` IN (" . implode(',', $q) . ")";
		}
		if (!empty($where)) {
			$sel = $has_link_col ? "`member_profile_id`,`phone`" : "`phone`";
			$crows = $dbconn->getAll("SELECT $sel FROM `" . $clsCustomer->tbl . "` WHERE " . implode(' OR ', $where));
			foreach ((array) $crows as $cr) {
				if ($has_link_col) {
					$mp = (int) $cr['member_profile_id'];
					if ($mp > 0) {
						$converted[$mp] = true;
					}
				}
				$cph = trim((string) $cr['phone']);
				if ($cph !== '' && isset($phones[$cph])) {
					$phonedup[$cph] = true;
				}
			}
		}
	}

	// gắn cờ + đếm KPI theo trang + chữ tắt avatar (mb-safe) + ngày ĐK (pre-render vì partial không có $clsISO)
	$cnt_conv = $cnt_pdup = $cnt_pick = 0;
	foreach ($rows as $i => $r) {
		$pid = (int) $r['profile_id'];
		$ph  = trim((string) $r['phone']);
		$is_conv = isset($converted[$pid]) ? 1 : 0;
		$is_pdup = (!$is_conv && $ph !== '' && isset($phonedup[$ph])) ? 1 : 0;
		$rows[$i]['_is_conv'] = $is_conv;
		$rows[$i]['_is_pdup'] = $is_pdup;
		$rows[$i]['_no_phone'] = ($ph === '') ? 1 : 0;   // không SĐT → không convert được → chặn chọn
		if ($is_conv) {
			$cnt_conv++;
		} elseif ($is_pdup) {
			$cnt_pdup++;
		} else {
			$cnt_pick++;
		}
		$nm = trim((string) $r['full_name']);
		$ini = '';
		if ($nm !== '') {
			$pp  = preg_split('/\s+/', $nm);
			$ini = mb_strtoupper(mb_substr($pp[0], 0, 1, 'UTF-8'), 'UTF-8');
			if (count($pp) > 1) {
				$ini .= mb_strtoupper(mb_substr(end($pp), 0, 1, 'UTF-8'), 'UTF-8');
			}
		}
		$rows[$i]['_ini']     = ($ini !== '') ? $ini : '?';
		$rows[$i]['reg_text'] = $clsISO->convertTimeToText((int) $r['reg_date'], true);
	}

	$total_all = (int) $clsMember->countItem("`profile_type` IN ('MOC','MF') AND `is_trash`=0");

	$pg_start  = max(1, $page - 2);
	$pg_end    = min($total_page, $page + 2);
	$page_list = range($pg_start, $pg_end);

	$smarty->assign('rows', $rows);
	$smarty->assign('page_list', $page_list);
	$smarty->assign('total', $total);
	$smarty->assign('total_all', $total_all);
	$smarty->assign('page', $page);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('has_link_col', $has_link_col);
	$smarty->assign('cnt_conv', $cnt_conv);
	$smarty->assign('cnt_pdup', $cnt_pdup);
	$smarty->assign('cnt_pick', $cnt_pick);
	$smarty->assign('cnt_done', $cnt_conv + $cnt_pdup);
	$smarty->assign('mkt_img', URL_IMAGES);

	$kpi  = $core->build('_ajax.sale_marketplace_kpi.tpl');   // KPI nạp riêng (vùng trên bộ lọc)
	$html = $core->build('_ajax.sale_marketplace_crm.tpl');   // bảng + phân trang (thân card)
	echo json_encode(array('kpi' => $kpi, 'html' => $html, 'total' => $total, 'page' => $page, 'total_page' => $total_page), JSON_UNESCAPED_UNICODE);
	die();
}
/**
 * GĐ3 — AJAX: chuyển member (Sale thị trường) đã chọn → default_customer (lead chuẩn).
 * POST: list_ids[] (profile_id thô). Operator tự-nhận: admin_id=user_id=$profile_id (forced, chống IDOR).
 * Reuse-minimal đường tạo-lead home/sub_default.php:8425-8601 (status 291 + CustomerHistory + 5 FollowUp,
 * GIỮ insertNotify lịch, BỎ doPushMessagingUser/Zalo/meta-sync). echo json; die().
 */
function default_pop_convert_marketplace_to_crm(){
	global $profile_id, $oneProfile, $core, $clsISO, $dbconn;
	if (!_crm_can_marketplace_pool()) {
		echo json_encode(array('msg' => '_error'));
		die();
	} // gate AJAX (GĐ4)
	$clsMember      = new Member();
	$clsCustomer    = new Customer();
	$clsHistory     = new CustomerHistory();
	$clsFollowUp    = new FollowUp();
	$clsNotify      = new Notify();
	$clsProperty    = new Property();
	$clsActivityLog = new ActivityLog();
	$clsProfile     = new Profile();
	// SECURITY: operator (người thao tác). Người NHẬN mặc định = operator (gán cho mình); CHỈ quản lý (full-permiss)
	// + target thực sự có quyền sale_market_crm mới đổi người nhận (chống IDOR gán khách bừa).
	$operator_id = (int) $profile_id;
	$assignee_id = $operator_id;
	$target = (int) Input::post('target_admin_id', 0);
	if ($target > 0 && $target !== $operator_id && $clsCustomer->isFullPermiss() && _crm_profile_has_market_perm($target)) {
		$assignee_id = $target;
	}
	// nguồn: hằng user đăng ký (GĐ1); fallback 8817 "SaleMOC" (đã có sẵn) để test trước khi đăng ký
	$resource_id = defined('_RESOURCE_MARKET_SALE_ID') ? (int) _RESOURCE_MARKET_SALE_ID : 8817;

	// cột link đã có chưa? (cho phép chạy TRƯỚC ALTER GĐ1 — dedup tạm theo phone, link lưu more_information)
	$has_link_col = !empty($dbconn->getOne("SHOW COLUMNS FROM `" . $clsCustomer->tbl . "` LIKE 'member_profile_id'"));

	$ids = Input::post('list_ids', array());
	$ids = array_values(array_unique(array_filter(array_map('intval', (array) $ids), function ($v) {
		return $v > 0;
	})));
	if (count($ids) > 200) {
		echo json_encode(array('msg' => '_error', 'detail' => 'max_200'));
		die();
	} // cap CỨNG
	$made = $skip_dup = $skip_no_phone = $skip_invalid = $lastCid = 0;
	$timer    = strtotime(date('d-m-Y'));   // seed mốc (KHÔNG dùng time() → tránh lệch giờ)
	$schedule = array(1, 2, 5, 15, 30);          // cadence — đồng bộ home/sub_default.php:8535-8546

	foreach ($ids as $pid) {
		$m = $clsMember->getOne($pid, "profile_id,full_name,phone,email,profile_type");
		if (empty($m) || !in_array($m['profile_type'], array('MOC', 'MF'), true)) {
			$skip_invalid++;
			continue;
		} // whitelist type
		$phone = trim((string) $m['phone']);
		if ($phone === '') {
			$skip_no_phone++;
			continue;
		}
		// DEDUP 1 (chính): cùng member đã chuyển? (chỉ khi có cột link)
		if ($has_link_col && $clsCustomer->countItem("`member_profile_id`=" . $pid)) {
			$skip_dup++;
			continue;
		}
		// DEDUP 2 (global theo phone): phone đã là customer bất kỳ? (qstr escape)
		if ($clsCustomer->countItem("`phone`=" . $dbconn->qstr($phone))) {
			$skip_dup++;
			continue;
		}
		$cid  = $clsCustomer->getMaxId();
		$name = $m['full_name'];
		$logContent = sprintf('<strong>%s</strong> chuyển từ Sale thị trường', $clsProfile->getFullName($operator_id, $oneProfile));
		if ($assignee_id !== $operator_id) {
			$logContent .= sprintf(' · gán cho <strong>%s</strong>', $clsProfile->getFullName($assignee_id));
		}
		$logs = array($clsISO->getUniqid() => array(
			'user_id'  => $operator_id,
			'reg_date' => time(),
			'content'  => $logContent
		));
		$more = array(
			'action_logs' => $logs, 
			'source' => 'market_sale', 
			'source_member_profile_id' => $pid
		);
		$cus = array(
			$clsCustomer->pkey => $cid,
			'name'           => $name,
			'name_slug'      => $core->replaceSpace($name),
			'phone'          => $phone,
			'email'          => $m['email'],
			'admin_id'       => $assignee_id,   // người nhận (mặc định operator; manager có thể gán người khác)
			'user_id'        => $assignee_id,
			'user_id_update' => $operator_id,
			'status_id'      => _CRM_STATUS_LEAD_ID,   // 291
			'resource_id'    => $resource_id,
			'use_globe'      => 0,
			'more_information' => json_encode($more, JSON_UNESCAPED_UNICODE),
			'reg_date'       => time(),
			'upd_date'       => time()
			// KHÔNG set task_current_id → DB default 9935 (_CRM_TASK_LEAD_ID)
		);
		if ($has_link_col) {
			$cus['member_profile_id'] = $pid;
		} // link cột (sau khi ALTER GĐ1)

		if ($clsCustomer->insert($cus)) {
			$clsHistory->insert(array(
				'customer_id'    => $cid,
				'from_status_id' => 0,
				'to_status_id'   => _CRM_STATUS_LEAD_ID,
				'staff_id'       => $operator_id,
				'action_date'    => time()
			));
			// $clsActivityLog->addActivityLog("Customer", "insert_from_member", array('customer_id' => $cid, 'member_profile_id' => $pid));
			// pipeline: 5 follow-up (calendar insertNotify YES; push NO — chống bão N×5)
			foreach ($schedule as $d) {
				$date_id = strtotime('+' . $d . ' day', $timer);
				$fid     = $clsFollowUp->getMaxId();
				if ($clsFollowUp->insert(array(
					$clsFollowUp->pkey => $fid,
					'type_id'        => _FOLLOWUP_CALL_ID,        // 341
					'status_id'      => _FOLLOWUP_STATUS_PLAN_ID, // 297
					'customer_id'    => $cid,
					'date_id'        => $date_id,
					'intro'          => 'Call liên hệ lại khách',
					'admin_id'       => $assignee_id,
					'user_id'        => $assignee_id,
					'user_id_update' => $operator_id,
					'reg_date'       => time(),
					'upd_date'       => time()
				))) {
					$titleNoty = sprintf(
						'Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào lúc <strong>%s</strong>',
						$clsProperty->getTitle(_FOLLOWUP_CALL_ID) . ": Call liên hệ lại khách",
						$name,
						$clsISO->convertTimeToText($date_id, true)
					);
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $fid, $titleNoty, $date_id, '|' . $assignee_id . '|');
					// CHỦ Ý: KHÔNG gọi doPushMessagingUser (home/sub_default.php:8595) → tránh bão N×5 push
				}
			}
			$made++;
			$lastCid = $cid;
		}
	}
	// Bắn 1 Notify + Zalo cho NGƯỜI NHẬN khi gán cho người khác (gộp cả batch — chống bão N×5 như 5 follow-up).
	if ($assignee_id !== $operator_id && $made > 0) {
		$opName    = $clsProfile->getFullName($operator_id, $oneProfile);
		$noteTitle = sprintf('<strong>%s</strong> vừa giao cho bạn <strong>%d</strong> khách mới từ Sale thị trường.', $opName, $made);
		$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $lastCid, $noteTitle, time(), '|' . $assignee_id . '|');
		$clsNotification = new Notification();
		$clsNotification->doPushMessagingUser(array(
			'title' => 'CRM - Khách mới được giao',
			'body'  => strip_tags($noteTitle),
			'link'  => PCMS_URL . '/crm/'
		), array($assignee_id));
		$zmsg = sprintf('🔔 %s vừa giao cho bạn %d khách mới từ Sale thị trường.', $opName, $made) . "\r"
			. 'Vào CRM để bắt đầu chăm sóc: '.DOMAIN_URL.'/crm/';
		$clsZalo = new Zalo();
		$clsZalo->sendZaloUser($assignee_id, $zmsg);
	}
	echo json_encode(array(
		'msg'              => '_success',
		'made'             => $made,
		'skipped_dup'      => $skip_dup,
		'skipped_no_phone' => $skip_no_phone,
		'skipped_invalid'  => $skip_invalid,
		'total'            => count($ids)
	));
	die();
}
