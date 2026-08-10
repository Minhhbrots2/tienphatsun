<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function crm_task_waiting_text($time_receipt){
	$time_receipt = (int) $time_receipt;
	if ($time_receipt <= 0) {
		return "--";
	}
	$now = time();
	if ($time_receipt <= $now) {
		return '<span class="text-danger">Quá hạn</span>';
	}
	$diff = $time_receipt - $now;
	$day = (int) floor($diff / 86400);
	$remain = $diff % 86400;
	$hour = (int) floor($remain / 3600);
	$remain = $remain % 3600;
	$minute = (int) floor($remain / 60);
	$second = (int) ($remain % 60);
	if ($day > 0) {
		return sprintf('%s Ngày %02d:%02d:%02d', $day, $hour, $minute, $second);
	}
	return sprintf('%02d:%02d:%02d', $hour, $minute, $second);
}
// Số lần đã thực hiện counter-result (Không nghe máy / Chưa phản hồi) của 1 task — đọc key task_counter_{task}_* trong customer.more_information.
function crm_task_attempt_count($more_info, $task_id){
	$task_id = (int) $task_id;
	if ($task_id <= 0 || !is_array($more_info)) { return 0; }
	$pfx = 'task_counter_' . $task_id . '_';
	$max = 0;
	foreach ($more_info as $k => $v) {
		if (strpos((string) $k, $pfx) === 0) { $n = (int) $v; if ($n > $max) { $max = $n; } }
	}
	return $max;
}
// Hậu tố "(+N)" — số lần đã thực hiện counter-result; mờ cam, đỏ ở bậc cuối (>=3 = sắp escalate). Trả '' nếu count<=0.
function crm_task_attempt_badge($attempt){
	$attempt = (int) $attempt;
	if ($attempt <= 0) { return ''; }
	$cls = ($attempt >= 3) ? 'crm-attempt-badge crm-attempt-badge--max' : 'crm-attempt-badge';
	return '<span class="' . $cls . '" title="Đã thực hiện ' . $attempt . ' lần">(+' . $attempt . ')</span>';
}
function crm_task_result_ids_by_task($task_ids, $clsCustomerTaskSetting){
	$map = array();
	$task_ids = array_values(array_unique(array_map('intval', (array)$task_ids)));
	$task_ids = array_filter($task_ids, function ($v) {
		return $v > 0;
	});
	if (empty($task_ids)) {
		return $map;
	}
	$list = $clsCustomerTaskSetting->getAll("`is_trash`=0 and `task_id` in (" . implode(',', $task_ids) . ") order by `order_no` asc, `customer_task_setting_id` asc", "`task_id`,`result_id`");
	if (!empty($list)) {
		foreach ($list as $row) {
			$task_id = (int) $row['task_id'];
			$result_id = (int) $row['result_id'];
			if ($task_id <= 0 || $result_id <= 0) {
				continue;
			}
			if (!isset($map[$task_id])) {
				$map[$task_id] = array();
			}
			if (!in_array($result_id, $map[$task_id])) {
				$map[$task_id][] = $result_id;
			}
		}
	}
	return $map;
}
function crm_task_result_select_html($customer_id, $task_current_id, $task_result_id, $arr_crm_result_cached, $task_result_ids_by_task){
	$task_current_id = (int) $task_current_id;
	$task_result_id = (int) $task_result_id;
	$allowed_result_ids = isset($task_result_ids_by_task[$task_current_id]) ? $task_result_ids_by_task[$task_current_id] : array();
	$html_task_result_options = '<option value="0">Chọn kết quả</option>';
	if (!empty($allowed_result_ids)) {
		foreach ($allowed_result_ids as $rid) {
			if (!isset($arr_crm_result_cached[$rid])) {
				continue;
			}
			// V1: đọc cờ need_datetime / is_counter từ more_information để FE chặn inline khi cần giờ.
			// Dùng json_decode trực tiếp tránh dependency $clsISO global.
			$_mi_raw = isset($arr_crm_result_cached[$rid]['more_information']) ? $arr_crm_result_cached[$rid]['more_information'] : '';
			if (is_array($_mi_raw)) { $_mi = $_mi_raw; }
			else if (is_string($_mi_raw) && $_mi_raw !== '') { $_mi = json_decode($_mi_raw, true); if (!is_array($_mi)) { $_mi = array(); } }
			else { $_mi = array(); }
			$_nd = !empty($_mi['need_datetime']) ? 1 : 0;
			$_ic = !empty($_mi['is_counter']) ? 1 : 0;
			$selected = ($task_result_id == (int)$rid) ? ' selected="selected"' : '';
			$html_task_result_options .= '<option value="' . $rid . '" data-need-datetime="' . $_nd . '" data-counter="' . $_ic . '"' . $selected . '>' . htmlspecialchars((string)$arr_crm_result_cached[$rid]['title'], ENT_QUOTES) . '</option>';
		}
	}
	return '<select class="form-control form-select form-control-sm w-px-150"
		onChange="$Core.crm.crm_task_result_change(this,event)"
		p_id="' . $customer_id . '" p_field="task_result_id" customer_id="' . $customer_id . '" tp="follow-ups">
		' . $html_task_result_options . '
	</select>';
}
function crm_task_next_cell_html($customer_id, $task_next_id, $arr_crm_task_cached, $clsSetting){
	$task_next_id = (int) $task_next_id;
	$label_html = '--';
	if ($task_next_id > 0 && isset($arr_crm_task_cached[$task_next_id])) {
		$label_html = $clsSetting->getLabel($task_next_id, $arr_crm_task_cached[$task_next_id]);
	}
	$btn = '';
	if ($task_next_id > 0) {
		$btn = '<button type="button" class="btn btn-sm btn-icon btn-link text-success p-0 ms-1" title="Chuyển sang tác nghiệp tiếp"
			onClick="$Core.crm.crm_task_move_next(this,event)" customer_id="' . $customer_id . '">
			<i class="bx bx-up-arrow-circle fs-5"></i>
		</button>';
	}
	return '<div class="d-inline-flex align-items-center justify-content-center gap-1">' . $label_html . $btn . '</div>';
}
function crm_task_waiting_cell_html($customer_id, $time_receipt, $task_next_id = 0){
	$task_next_id = (int) $task_next_id;
	if ($task_next_id <= 0) {
		return '<div class="d-inline-flex align-items-center justify-content-center gap-1">--</div>';
	}
	$text = crm_task_waiting_text($time_receipt);
	$btn = '';
	if ((int)$time_receipt > 0) {
		$btn = '<button type="button" class="btn btn-sm btn-icon btn-link text-success p-0 ms-1" title="Sửa lịch tác nghiệp"
			onClick="$Core.crm.crm_task_open_schedule(this,event)" customer_id="' . $customer_id . '">
			<i class="bx bx-time-five fs-5"></i>
		</button>';
	}
	return '<div class="d-inline-flex align-items-center justify-content-center gap-1">' . $text . $btn . '</div>';
}
function default_load_worklist(){
	// F1: work-queue/SLA cockpit theo rep — 4 bucket (Quá hạn / Hôm nay / Sắp đến hạn / Chưa từng chạm)
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$now = time();
	$end_today = strtotime(date('d-m-Y') . ' 23:59:59');
	$soon_until = $end_today + 3 * 86400; // +3 ngày
	$pid = (int) $profile_id;
	$ftbl = $clsFollowUp->tbl;
	$ctbl = $clsCustomer->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$LIMIT = 50;
	$fselect = "SELECT f.`customer_id`,f.`date_id`,f.`type_id`,f.`intro`,c.`name`,c.`phone` FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id`";
	$enrichF = function ($rows, $time_class) use ($clsISO, $clsCustomer, $now) {
		$out = array();
		if (!empty($rows)) {
			foreach ($rows as $r) {
				$nm = trim($r['name']);
				$ph = trim($r['phone']);
				$out[] = array(
					'customer_id' => (int) $r['customer_id'],
					'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
					'phone' => $ph,
					'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
					'phone_html' => $clsCustomer->getPhoneReveal($ph, true),
					'intro' => trim($r['intro']),
					'time_text' => ((int)$r['date_id'] >= $now ? $clsISO->getTimeMore($r['date_id']) : $clsISO->getTimeAgo($r['date_id'])),
					'time_class' => $time_class
				);
			}
		}
		return $out;
	};
	// 1) Quá hạn (định nghĩa LOCKED) — dùng idx_fu_admin_status_date
	$wOver = "f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id`='{$pid}' AND f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now}";
	$cntOverdue = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ftbl}` f WHERE {$wOver}");
	$lstOverdue = $enrichF($dbconn->GetAll("{$fselect} WHERE {$wOver} ORDER BY f.`date_id` ASC LIMIT {$LIMIT}"), 'text-danger');
	// 2) Hôm nay
	$wToday = "f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id`='{$pid}' AND f.`status_id`<>'{$DONE}' AND f.`date_id`>={$now} AND f.`date_id`<={$end_today}";
	$cntToday = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ftbl}` f WHERE {$wToday}");
	$lstToday = $enrichF($dbconn->GetAll("{$fselect} WHERE {$wToday} ORDER BY f.`date_id` ASC LIMIT {$LIMIT}"), 'text-primary');
	// 3) Sắp đến hạn (follow-up trong 3 ngày tới) — task_due_date còn trống nên dùng follow-up
	$wSoon = "f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id`='{$pid}' AND f.`status_id`<>'{$DONE}' AND f.`date_id`>{$end_today} AND f.`date_id`<={$soon_until}";
	$cntUpcoming = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ftbl}` f WHERE {$wSoon}");
	$lstUpcoming = $enrichF($dbconn->GetAll("{$fselect} WHERE {$wSoon} ORDER BY f.`date_id` ASC LIMIT {$LIMIT}"), 'text-success');
	// 4) Chưa từng chạm (khách của rep, chưa có follow-up nào)
	$wZero = "c.`is_trash`=0 AND c.`admin_id`='{$pid}' AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}') AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0)";
	$cntZero = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` c WHERE {$wZero}");
	$rowsZero = $dbconn->GetAll("SELECT c.`customer_id`,c.`name`,c.`phone`,c.`reg_date` FROM `{$ctbl}` c WHERE {$wZero} ORDER BY c.`reg_date` DESC LIMIT {$LIMIT}");
	$lstZero = array();
	if (!empty($rowsZero)) {
		foreach ($rowsZero as $r) {
			$nm = trim($r['name']);
			$ph = trim($r['phone']);
			$lstZero[] = array(
				'customer_id' => (int) $r['customer_id'],
				'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
				'phone' => $ph,
				'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
				'phone_html' => $clsCustomer->getPhoneReveal($ph, true),
				'intro' => '',
				'time_text' => 'Tạo ' . $clsISO->getTimeAgo($r['reg_date']),
				'time_class' => 'text-muted'
			);
		}
	}
	$buckets = array(
		array(
			'key'   => 'overdue',
			'title' => 'Quá hạn',
			'color' => 'warning',
			'icon'  => 'exclamation-triangle',
			'count' => $cntOverdue,
			'limit' => $LIMIT,
			'empty' => 'Không có việc quá hạn',
			'rows'  => $lstOverdue,
		),
		array(
			'key'   => 'today',
			'title' => 'Hôm nay',
			'color' => 'primary',
			'icon'  => 'calendar-check-o',
			'count' => $cntToday,
			'limit' => $LIMIT,
			'empty' => 'Hôm nay chưa có lịch hẹn',
			'rows'  => $lstToday,
		),
		array(
			'key'   => 'upcoming',
			'title' => 'Sắp đến hạn (3 ngày)',
			'color' => 'success',
			'icon'  => 'clock-o',
			'count' => $cntUpcoming,
			'limit' => $LIMIT,
			'empty' => 'Không có lịch sắp tới',
			'rows'  => $lstUpcoming,
		),
		array(
			'key'   => 'zero',
			'title' => 'Chưa từng chạm',
			'color' => 'info',
			'icon'  => 'user-plus',
			'count' => $cntZero,
			'limit' => $LIMIT,
			'empty' => 'Mọi khách đã được chăm sóc',
			'rows'  => $lstZero,
		),
	);
	$smarty->assign('buckets', $buckets);
	$html = $core->build('_ajax.worklist.tpl');
	echo json_encode(array(
		'html' => $html,
		'cntOverdue' => $cntOverdue,
		'cntToday' => $cntToday,
		'cntUpcoming' => $cntUpcoming,
		'cntZero' => $cntZero
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_load_sale_dashboard(){
	// B1.1 — Lăng kính Sale: dải KPI + Next-best-action + bảng SLA 4 cột (read-only, owner-scoped). Tái dùng logic 4 bucket của default_load_worklist().
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$now = time();
	$end_today = strtotime(date('d-m-Y') . ' 23:59:59');
	$soon_until = $end_today + 3 * 86400;
	$pid = (int) $profile_id;
	$ftbl = $clsFollowUp->tbl;
	$ctbl = $clsCustomer->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$HOT = (int) _CRM_LEAD_SCORE_HOT;
	$BOARD = 6; // số dòng/cột trên bảng SLA
	$initials = function ($name) {
		$name = trim($name);
		if ($name === '') {
			return '?';
		}
		$parts = preg_split('/\s+/', $name);
		$a = mb_substr($parts[0], 0, 1, 'UTF-8');
		$b = (count($parts) > 1) ? mb_substr($parts[count($parts) - 1], 0, 1, 'UTF-8') : '';
		return mb_strtoupper($a . $b, 'UTF-8');
	};
	$fselect = "SELECT f.`customer_id`,f.`date_id`,f.`type_id`,f.`intro`,c.`name`,c.`phone` FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id`";
	$enrichF = function ($rows, $time_class) use ($clsISO, $clsCustomer, $now, $initials) {
		$out = array();
		if (!empty($rows)) {
			foreach ($rows as $r) {
				$nm = trim($r['name']);
				$ph = trim($r['phone']);
				$out[] = array(
					'customer_id' => (int) $r['customer_id'],
					'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
					'initials' => $initials($nm),
					'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
					'phone_html' => $clsCustomer->getPhoneReveal($ph, true),
					'intro' => trim($r['intro']),
					'time_text' => ((int)$r['date_id'] >= $now ? $clsISO->getTimeMore($r['date_id']) : $clsISO->getTimeAgo($r['date_id'])),
					'time_class' => $time_class
				);
			}
		}
		return $out;
	};
	// ===== 4 bucket SLA (định nghĩa LOCKED giống worklist) =====
	$wOver = "f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id`='{$pid}' AND f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now}";
	$cntOverdue = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ftbl}` f WHERE {$wOver}");
	$lstOverdue = $enrichF($dbconn->GetAll("{$fselect} WHERE {$wOver} ORDER BY f.`date_id` ASC LIMIT {$BOARD}"), 'text-danger');
	$wToday = "f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id`='{$pid}' AND f.`status_id`<>'{$DONE}' AND f.`date_id`>={$now} AND f.`date_id`<={$end_today}";
	$cntToday = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ftbl}` f WHERE {$wToday}");
	$lstToday = $enrichF($dbconn->GetAll("{$fselect} WHERE {$wToday} ORDER BY f.`date_id` ASC LIMIT {$BOARD}"), 'text-primary');
	$wSoon = "f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id`='{$pid}' AND f.`status_id`<>'{$DONE}' AND f.`date_id`>{$end_today} AND f.`date_id`<={$soon_until}";
	$cntUpcoming = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ftbl}` f WHERE {$wSoon}");
	$lstUpcoming = $enrichF($dbconn->GetAll("{$fselect} WHERE {$wSoon} ORDER BY f.`date_id` ASC LIMIT {$BOARD}"), 'text-success');
	$wZero = "c.`is_trash`=0 AND c.`admin_id`='{$pid}' AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}') AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0)";
	$cntZero = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` c WHERE {$wZero}");
	$rowsZero = $dbconn->GetAll("SELECT c.`customer_id`,c.`name`,c.`phone`,c.`reg_date` FROM `{$ctbl}` c WHERE {$wZero} ORDER BY c.`reg_date` DESC LIMIT {$BOARD}");
	$lstZero = array();
	if (!empty($rowsZero)) {
		foreach ($rowsZero as $r) {
			$nm = trim($r['name']);
			$ph = trim($r['phone']);
			$lstZero[] = array(
				'customer_id' => (int) $r['customer_id'],
				'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
				'initials' => $initials($nm),
				'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
				'phone_html' => $clsCustomer->getPhoneReveal($ph, true),
				'intro' => '',
				'time_text' => 'Tạo ' . $clsISO->getTimeAgo($r['reg_date']),
				'time_class' => 'text-muted'
			);
		}
	}
	$sd_buckets = array(
		array(
			'key'   => 'overdue',
			'title' => 'Quá hạn',
			'color' => 'danger',
			'icon'  => 'error-circle',
			'count' => $cntOverdue,
			'empty' => 'Không có việc quá hạn',
			'rows'  => $lstOverdue,
		),
		array(
			'key'   => 'today',
			'title' => 'Hôm nay',
			'color' => 'primary',
			'icon'  => 'calendar-check',
			'count' => $cntToday,
			'empty' => 'Hôm nay chưa có lịch',
			'rows'  => $lstToday,
		),
		array(
			'key'   => 'upcoming',
			'title' => 'Sắp đến hạn',
			'color' => 'success',
			'icon'  => 'time-five',
			'count' => $cntUpcoming,
			'empty' => 'Không có lịch sắp tới',
			'rows'  => $lstUpcoming,
		),
		array(
			'key'   => 'zero',
			'title' => 'Chưa từng chạm',
			'color' => 'warning',
			'icon'  => 'user-plus',
			'count' => $cntZero,
			'empty' => 'Mọi khách đã được chăm',
			'rows'  => $lstZero,
		),
	);
	// ===== KPI: Khách đang chăm (active owned) =====
	$cntActive = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` WHERE `admin_id`='{$pid}' AND `is_trash`=0 AND `status_id` NOT IN ('{$CHOT}','{$TRASH}')");
	// ===== Next-best-action: top khách nên làm (ưu tiên quá hạn, rồi lead_score) =====
	$nbaRows = $dbconn->GetAll(
		"SELECT c.`customer_id`,c.`name`,c.`phone`,c.`status_id`,c.`reg_date`,c.`more_information`,"
			. " COUNT(f.`followup_id`) AS `fu_cnt`,"
			. " SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) AS `overdue_cnt`"
			. " FROM `{$ctbl}` c"
			. " LEFT JOIN `{$ftbl}` f ON f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0"
			. " WHERE c.`admin_id`='{$pid}' AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}')"
			. " GROUP BY c.`customer_id`"
			. " ORDER BY `overdue_cnt` DESC, CAST(JSON_EXTRACT(c.`more_information`,'\$.lead_score') AS UNSIGNED) DESC, c.`reg_date` ASC"
			. " LIMIT 5"
	);
	$status_ids = array();
	if (!empty($nbaRows)) {
		foreach ($nbaRows as $r) {
			$sid = (int) $r['status_id'];
			if ($sid > 0) {
				$status_ids[$sid] = $sid;
			}
		}
	}
	$arr_status = array();
	if (!empty($status_ids)) {
		$sres = $dbconn->GetAll("SELECT `property_id`,`title`,`bgcolor` FROM `{$clsProperty->tbl}` WHERE `property_id` IN (" . implode(',', array_values($status_ids)) . ")");
		if (!empty($sres)) {
			foreach ($sres as $s) {
				$arr_status[(int)$s['property_id']] = $s;
			}
		}
	}
	$nba_list = array();
	if (!empty($nbaRows)) {
		foreach ($nbaRows as $r) {
			$mi = $clsISO->to_array_json($r['more_information']);
			$ls = (is_array($mi) && isset($mi['lead_score'])) ? (int)$mi['lead_score'] : 0;
			$ov = (int) $r['overdue_cnt'];
			$nm = trim($r['name']);
			$ph = trim($r['phone']);
			$sid = (int) $r['status_id'];
			$nba_list[] = array(
				'customer_id' => (int) $r['customer_id'],
				'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
				'initials' => $initials($nm),
				'phone' => $ph,
				'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
				'phone_html' => $clsCustomer->getPhoneReveal($ph, true),
				'status' => isset($arr_status[$sid]) ? $arr_status[$sid]['title'] : '',
				'status_bg' => (isset($arr_status[$sid]) && !empty($arr_status[$sid]['bgcolor'])) ? $arr_status[$sid]['bgcolor'] : '#8592a3',
				'lead_score' => $ls,
				'is_hot' => ($ls >= $HOT) ? 1 : 0,
				'reason' => ($ov > 0) ? ($ov . ' việc quá hạn') : (($ls >= $HOT) ? 'Điểm tiềm năng cao' : (((int)$r['fu_cnt'] == 0) ? 'Chưa từng chạm' : 'Cần chăm sóc'))
			);
		}
	}
	$smarty->assign('sd_active', $cntActive);
	$smarty->assign('sd_today', $cntToday);
	$smarty->assign('sd_overdue', $cntOverdue);
	$smarty->assign('sd_zero', $cntZero);
	$smarty->assign('sd_buckets', $sd_buckets);
	$smarty->assign('sd_nba_top', !empty($nba_list) ? $nba_list[0] : array());
	$smarty->assign('sd_nba_queue', !empty($nba_list) ? array_slice($nba_list, 1) : array());
	$smarty->assign('sd_nba_count', count($nba_list));
	$smarty->assign('sd_has_nba', !empty($nba_list) ? 1 : 0);
	$html = $core->build('_ajax.sale_dashboard.tpl');
	echo json_encode(array(
		'html' => $html
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_load_marketing_control(){
	// Marketing control-view: khách Marketing tự đẩy (user_id=me), xếp theo mức độ bê trễ. Prereq idx_user.
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsCustomer = new Customer();
	if (!$clsCustomer->isFullPermiss()) {
		echo json_encode(array(
			'error' => 1,
			'message' => 'Chỉ Marketing/Quản lý xem được mục này.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$now = time();
	$pid = (int) $profile_id;
	$ctbl = $clsCustomer->tbl;
	$ftbl = $clsFollowUp->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$LIMIT = 100;
	$wScope = "c.`user_id`='{$pid}' AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}')";
	$total = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` c WHERE {$wScope}");
	// 1 truy vấn gộp: LEFT JOIN follow-up _crm, đếm/quá-hạn/last-contact theo khách (dùng idx_user + index_customer_activity)
	$rows = $dbconn->GetAll(
		"SELECT c.`customer_id`,c.`name`,c.`phone`,c.`admin_id`,c.`time_receipt`,c.`reg_date`,c.`status_id`,"
			. " COUNT(f.`followup_id`) AS `fu_count`, MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END) AS `last_fu`,"
			. " SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) AS `overdue_count`"
			. " FROM `{$ctbl}` c"
			. " LEFT JOIN `{$ftbl}` f ON f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0"
			. " WHERE {$wScope}"
			. " GROUP BY c.`customer_id`"
			. " ORDER BY (COUNT(f.`followup_id`)=0) DESC, COALESCE(MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END), c.`reg_date`) ASC"
			. " LIMIT {$LIMIT}"
	);
	// cache nhãn trạng thái (1 query)
	$arr_status = array();
	$sres = $dbconn->GetAll("SELECT `property_id`,`title`,`bgcolor`,`textcolor` FROM `{$clsProperty->tbl}` WHERE `property_type`='CUSTOMER_STATUS' AND `is_trash`=0");
	if (!empty($sres)) {
		foreach ($sres as $s) {
			$arr_status[(int)$s['property_id']] = $s;
		}
	}
	// cache tên Sale (1 query trên admin_id distinct)
	$sale_ids = array();
	$arr_sale = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$aid = (int) $r['admin_id'];
			if ($aid > 0) {
				$sale_ids[$aid] = $aid;
			}
		}
	}
	if (!empty($sale_ids)) {
		$sn = $dbconn->GetAll("SELECT `profile_id`,`full_name`,`more_information`,`avatar` FROM `{$clsProfile->tbl}` WHERE `profile_id` IN (" . implode(',', array_values($sale_ids)) . ")");
		if (!empty($sn)) {
			foreach ($sn as $s) {
				$arr_sale[(int)$s['profile_id']] = $s;
			}
		}
	}
	$lst = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$cid = (int)$r['customer_id'];
			$aid = (int)$r['admin_id'];
			$nm = trim($r['name']);
			$ph = trim($r['phone']);
			$last_fu = (int)$r['last_fu'];
			$fu_count = (int)$r['fu_count'];
			$over = (int)$r['overdue_count'];
			$last_act = $last_fu > 0 ? $last_fu : (int)$r['reg_date'];
			$days_idle = $last_act > 0 ? (int) max(0, floor(($now - $last_act) / 86400)) : 0;
			$oneS = isset($arr_status[(int)$r['status_id']]) ? $arr_status[(int)$r['status_id']] : null;
			$oneSale = isset($arr_sale[$aid]) ? $arr_sale[$aid] : null;
			$lst[] = array(
				'customer_id' => $cid,
				'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
				'phone' => $ph,
				'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
				'sale_id' => $aid,
				'sale_name' => ($aid === $pid ? 'Chưa phân (Marketing)' : ($oneSale && trim($oneSale['full_name']) !== '' ? trim($oneSale['full_name']) : '#' . $aid)),
				'sale_avatar' => $clsProfile->getAvatar($aid, ($oneSale ? $oneSale : array())),
				'is_undistributed' => ($aid === $pid ? 1 : 0),
				'status_title' => ($oneS ? $oneS['title'] : ''),
				'status_bg' => ($oneS ? $oneS['bgcolor'] : '#888888'),
				'status_color' => ($oneS ? $oneS['textcolor'] : '#ffffff'),
				'created_text' => ((int)$r['reg_date'] > 0 ? $clsISO->getTimeAgo($r['reg_date']) : '--'),
				'last_contact' => ($last_fu > 0 ? $clsISO->getTimeAgo($last_fu) : 'Chưa liên hệ'),
				'days_idle' => $days_idle,
				'fu_count' => $fu_count,
				'overdue' => $over
			);
		}
	}
	$smarty->assign('lst', $lst);
	$smarty->assign('mc_total', $total);
	$smarty->assign('mc_limit', $LIMIT);
	$html = $core->build('_ajax.marketing_control.tpl');
	echo json_encode(array(
		'html' => $html,
		'total' => $total
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_load_growth(){
	// Exec — dải KPI tăng trưởng từ reg_date (read-only, aggregate-over-all). KHÔNG phụ thuộc D4.
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsCustomer = new Customer();
	if (!($clsCustomer->isFullPermiss() || $clsISO->checkPermissionGroup('DIRECTOR'))) {
		echo json_encode(array(
			'error' => 1,
			'message' => 'Chỉ Ban điều hành/Quản lý cấp cao xem được mục này.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$now = time();
	$anchor = strtotime(date('Y-m-01', $now)); // mốc ngày-01: strtotime("-N months") KHÔNG tràn tháng khi hôm nay là ngày 29-31
	$ctbl = $clsCustomer->tbl;
	$MONTHS = 12;
	$start = strtotime("-" . ($MONTHS - 1) . " months", $anchor); // đầu tháng (MONTHS-1) tháng trước
	// 1 query: đếm KH mới theo tháng (reg_date BETWEEN start..now — sargable; FROM_UNIXTIME chỉ ở SELECT/GROUP trên tập đã lọc)
	$rows = $dbconn->GetAll("SELECT FROM_UNIXTIME(`reg_date`,'%Y-%m') AS ym, COUNT(*) AS c FROM `{$ctbl}` WHERE `is_trash`=0 AND `reg_date` BETWEEN {$start} AND {$now} GROUP BY ym");
	$by_month = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$by_month[$r['ym']] = (int) $r['c'];
		}
	}
	$total = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` WHERE `is_trash`=0 AND `reg_date`>0 AND `reg_date`<={$now}"); // tổng KH (clamp future-dated)
	$series = array();
	$prev = null;
	for ($i = $MONTHS - 1; $i >= 0; $i--) {
		$ts = strtotime("-{$i} months", $anchor);
		$ym = date('Y-m', $ts);
		$cnt = isset($by_month[$ym]) ? $by_month[$ym] : 0;
		$is_current = ($i === 0); // tháng hiện tại = partial (đang diễn ra) → không tính delta (sai lệch full-vs-partial)
		$has_delta = ($prev !== null && $prev > 0 && !$is_current);
		$series[] = array(
			'ym' => $ym,
			'label' => date('m/Y', $ts),
			'count' => $cnt,
			'is_current' => $is_current ? 1 : 0,
			'has_delta' => $has_delta ? 1 : 0,
			'delta_pct' => $has_delta ? round(($cnt - $prev) * 100 / $prev, 1) : 0,
			'is_up' => ($prev !== null && $cnt >= $prev) ? 1 : 0,
			'bar_pct' => 0
		);
		$prev = $cnt;
	}
	$max_cnt = 1;
	foreach ($series as $s) {
		if ($s['count'] > $max_cnt) {
			$max_cnt = $s['count'];
		}
	}
	foreach ($series as $k => $s) {
		$series[$k]['bar_pct'] = (int) round($s['count'] * 100 / $max_cnt);
	}
	$cur = $series[count($series) - 1];               // tháng hiện tại (partial)
	$last_done = isset($series[count($series) - 2]) ? $series[count($series) - 2] : $cur; // tháng gần nhất ĐÃ xong
	$series = array_reverse($series); // hiển thị mới→cũ (2026 lên đầu); delta đã tính theo thứ tự thời gian nên vẫn đúng
	$smarty->assign('growth_total', $total);
	$smarty->assign('growth_series', $series);
	$smarty->assign('growth_current', $cur);
	$smarty->assign('growth_last_done', $last_done);
	// Cơ cấu nguồn lead (cùng cửa sổ 12 tháng) — resource_id sạch; KHÔNG kèm %chốt (chốt-qua-status quá thưa → nhiễu)
	$clsPropertySrc = new Property();
	$src_rows = $dbconn->GetAll("SELECT p.`title` AS source, COUNT(*) AS leads FROM `{$ctbl}` c JOIN `{$clsPropertySrc->tbl}` p ON p.`property_id`=c.`resource_id` WHERE c.`is_trash`=0 AND c.`resource_id`>0 AND c.`status_id` NOT IN (0,1283) AND c.`reg_date` BETWEEN {$start} AND {$now} GROUP BY c.`resource_id` ORDER BY leads DESC LIMIT 12");
	$src_total = 0;
	$src_max = 1;
	if (!empty($src_rows)) {
		foreach ($src_rows as $sr) {
			$src_total += (int) $sr['leads'];
			if ((int) $sr['leads'] > $src_max) {
				$src_max = (int) $sr['leads'];
			}
		}
	}
	$sources = array();
	if (!empty($src_rows)) {
		foreach ($src_rows as $sr) {
			$ld = (int) $sr['leads'];
			$sources[] = array(
				'title' => $sr['source'],
				'leads' => $ld,
				'pct' => $src_total > 0 ? round($ld * 100 / $src_total, 1) : 0,
				'bar_pct' => (int) round($ld * 100 / $src_max)
			);
		}
	}
	$smarty->assign('growth_sources', $sources);
	$smarty->assign('growth_src_total', $src_total);
	// Phân bố trạng thái KH (org-wide, loại Rác 363 + 0/1283) — mỗi khách 1 status = phân bố (KHÔNG phải funnel tích luỹ)
	$st_rows = $dbconn->GetAll("SELECT p.`title` AS title, p.`bgcolor` AS bgcolor, COUNT(*) AS cnt FROM `{$ctbl}` c JOIN `{$clsPropertySrc->tbl}` p ON p.`property_id`=c.`status_id` WHERE c.`is_trash`=0 AND c.`status_id` NOT IN (0,1283,363) GROUP BY c.`status_id` ORDER BY p.`order_no` ASC");
	$st_total = 0;
	$st_max = 1;
	if (!empty($st_rows)) {
		foreach ($st_rows as $r) {
			$st_total += (int) $r['cnt'];
			if ((int) $r['cnt'] > $st_max) {
				$st_max = (int) $r['cnt'];
			}
		}
	}
	$statuses = array();
	if (!empty($st_rows)) {
		foreach ($st_rows as $r) {
			$cn = (int) $r['cnt'];
			$statuses[] = array(
				'title' => $r['title'],
				'bgcolor' => !empty($r['bgcolor']) ? $r['bgcolor'] : '#6c757d',
				'cnt' => $cn,
				'pct' => $st_total > 0 ? round($cn * 100 / $st_total, 1) : 0,
				'bar_pct' => (int) round($cn * 100 / $st_max)
			);
		}
	}
	$smarty->assign('growth_statuses', $statuses);
	$smarty->assign('growth_st_total', $st_total);
	// Nhịp độ gần đây: KH mới hôm nay / 7 ngày / 30 ngày + ▲▼ so kỳ liền trước (reg_date clamp <=now)
	$velRows = $dbconn->GetAll("SELECT SUM(`reg_date`>=UNIX_TIMESTAMP(CURDATE())) AS today, SUM(`reg_date`>=UNIX_TIMESTAMP()-604800) AS d7, SUM(`reg_date`>=UNIX_TIMESTAMP()-1209600 AND `reg_date`<UNIX_TIMESTAMP()-604800) AS d7p, SUM(`reg_date`>=UNIX_TIMESTAMP()-2592000) AS d30, SUM(`reg_date`>=UNIX_TIMESTAMP()-5184000 AND `reg_date`<UNIX_TIMESTAMP()-2592000) AS d30p FROM `{$ctbl}` WHERE `is_trash`=0 AND `reg_date`>0 AND `reg_date`<=UNIX_TIMESTAMP()");
	$v = !empty($velRows) ? $velRows[0] : array();
	$d7 = (int) (isset($v['d7']) ? $v['d7'] : 0);
	$d7p = (int) (isset($v['d7p']) ? $v['d7p'] : 0);
	$d30 = (int) (isset($v['d30']) ? $v['d30'] : 0);
	$d30p = (int) (isset($v['d30p']) ? $v['d30p'] : 0);
	$smarty->assign('vel_today', (int) (isset($v['today']) ? $v['today'] : 0));
	$smarty->assign('vel_d7', $d7);
	$smarty->assign('vel_d7_has', $d7p > 0 ? 1 : 0);
	$smarty->assign('vel_d7_up', $d7 >= $d7p ? 1 : 0);
	$smarty->assign('vel_d7_pct', $d7p > 0 ? round(($d7 - $d7p) * 100 / $d7p, 1) : 0);
	$smarty->assign('vel_d30', $d30);
	$smarty->assign('vel_d30_has', $d30p > 0 ? 1 : 0);
	$smarty->assign('vel_d30_up', $d30 >= $d30p ? 1 : 0);
	$smarty->assign('vel_d30_pct', $d30p > 0 ? round(($d30 - $d30p) * 100 / $d30p, 1) : 0);
	// TTFt — Tốc độ tiếp cận theo nguồn (12 tháng): thời gian từ lead vào (reg_date) → follow-up THẬT đầu tiên (Gọi 341/Gặp 342). Median tính ở PHP (MariaDB không hỗ trợ ổn định qua tunnel). Read-only aggregate.
	$clsFollowUpTtft = new FollowUp();
	$ftbl = $clsFollowUpTtft->tbl;
	$ttft_rows = array();
	$ttft_total = 0;
	$ttft_feed = $dbconn->GetAll("SELECT p.`title` AS source, c.`resource_id` AS rid, (ft.`first_touch` - c.`reg_date`) AS diff_sec FROM `{$ctbl}` c JOIN (SELECT `customer_id`, MIN(`reg_date`) AS `first_touch` FROM `{$ftbl}` WHERE `is_trash`=0 AND `type_id` IN (" . _FOLLOWUP_CALL_ID . "," . _CRM_APPOINTMENT_ID . ") AND `reg_date`>0 GROUP BY `customer_id`) ft ON ft.`customer_id`=c.`customer_id` JOIN `{$clsPropertySrc->tbl}` p ON p.`property_id`=c.`resource_id` WHERE c.`is_trash`=0 AND c.`reg_date` BETWEEN {$start} AND {$now} AND c.`resource_id`>0 AND ft.`first_touch`>=c.`reg_date`");
	if (!empty($ttft_feed)) {
		$ttft_by = array();    // rid => array(diff_sec)
		$ttft_title = array(); // rid => title
		foreach ($ttft_feed as $fr) {
			$rid = (int) $fr['rid'];
			$ttft_by[$rid][] = (int) $fr['diff_sec'];
			$ttft_title[$rid] = $fr['source'];
		}
		$ttft_tmp = array();
		$ttft_max_med = 1;
		foreach ($ttft_by as $rid => $arr) {
			$n = count($arr);
			sort($arr);
			$avg_h = round(array_sum($arr) / $n / 3600, 1);
			$mid = (int) floor($n / 2);
			$med_sec = ($n % 2 == 0) ? (($arr[$mid - 1] + $arr[$mid]) / 2) : $arr[$mid];
			$med_h = round($med_sec / 3600, 1);
			if ($med_h > $ttft_max_med) {
				$ttft_max_med = $med_h;
			}
			$ttft_total += $n;
			$ttft_tmp[] = array(
				'title' => $ttft_title[$rid],
				'n' => $n,
				'avg_h' => $avg_h,
				'med_h' => $med_h,
				'avg_label' => ($avg_h >= 48 ? round($avg_h / 24, 1) . ' ngày' : $avg_h . ' giờ'),
				'med_label' => ($med_h >= 48 ? round($med_h / 24, 1) . ' ngày' : $med_h . ' giờ'),
				'is_low' => ($n < 5) ? 1 : 0,
				'bar_pct' => 0
			);
		}
		usort($ttft_tmp, function ($a, $b) {
			return $b['n'] - $a['n'];
		});
		foreach ($ttft_tmp as $k => $row) {
			$ttft_tmp[$k]['bar_pct'] = (int) round($row['med_h'] * 100 / $ttft_max_med);
		}
		$ttft_rows = $ttft_tmp;
	}
	$smarty->assign('ttft_rows', $ttft_rows);
	$smarty->assign('ttft_total', $ttft_total);
	$html = $core->build('_ajax.exec_growth.tpl');
	echo json_encode(array(
		'html' => $html,
		'total' => $total
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_my_desktop(){
	// /crm/dashboard/ (act=my_desktop) — Bảng điều hành (Ban điều hành). Read-only, server-render. Gate: isFullPermiss || DIRECTOR.
	global $oSmarty, $smarty, $assign_list, $title_page, $profile_id, $core, $clsISO, $dbconn;
	$clsCustomer = new Customer();
	$can = ($clsCustomer->isFullPermiss() || $clsISO->checkPermissionGroup('DIRECTOR')) ? 1 : 0;
	$assign_list['exec_can'] = $can;
	$smarty->assign('exec_can', $can);
	$title_page = 'Bảng điều hành CRM | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
	if (!$can) {
		return;
	} // không quyền → tpl hiển thị thông báo, KHÔNG nạp dữ liệu nhạy cảm
	$now = time();
	$anchor = strtotime(date('Y-m-01', $now)); // mốc ngày-01 để -N months không tràn tháng
	$ctbl = $clsCustomer->tbl;
	$MONTHS = 12;
	$start = strtotime("-" . ($MONTHS - 1) . " months", $anchor);
	// ===== KPI + biểu đồ: KH mới theo tháng (reg_date) =====
	$rows = $dbconn->GetAll("SELECT FROM_UNIXTIME(`reg_date`,'%Y-%m') AS ym, COUNT(*) AS c FROM `{$ctbl}` WHERE `is_trash`=0 AND `reg_date` BETWEEN {$start} AND {$now} GROUP BY ym");
	$by_month = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$by_month[$r['ym']] = (int) $r['c'];
		}
	}
	$total = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` WHERE `is_trash`=0 AND `reg_date`>0 AND `reg_date`<={$now}");
	$series = array();
	$prev = null;
	for ($i = $MONTHS - 1; $i >= 0; $i--) {
		$ts = strtotime("-{$i} months", $anchor);
		$ym = date('Y-m', $ts);
		$cnt = isset($by_month[$ym]) ? $by_month[$ym] : 0;
		$is_current = ($i === 0);
		$has_delta = ($prev !== null && $prev > 0 && !$is_current);
		$series[] = array(
			'ym' => $ym,
			'label' => date('m/Y', $ts),
			'short' => date('m', $ts),
			'count' => $cnt,
			'count_f' => number_format($cnt, 0, ',', '.'),
			'is_current' => $is_current ? 1 : 0,
			'has_delta' => $has_delta ? 1 : 0,
			'delta_pct' => $has_delta ? round(($cnt - $prev) * 100 / $prev, 1) : 0,
			'is_up' => ($has_delta && $cnt >= $prev) ? 1 : 0,
			'bar_px' => 0,
			'is_peak' => 0
		);
		$prev = $cnt;
	}
	$max_cnt = 1;
	foreach ($series as $s2) {
		if ($s2['count'] > $max_cnt) {
			$max_cnt = $s2['count'];
		}
	}
	$peak_done = 0;
	foreach ($series as $k => $s2) {
		$series[$k]['bar_px'] = (int) max(2, round($s2['count'] * 150 / $max_cnt));
		if (!$peak_done && !$s2['is_current'] && $s2['count'] == $max_cnt) {
			$series[$k]['is_peak'] = 1;
			$peak_done = 1;
		}
	}
	$cur = $series[count($series) - 1];
	$last_done = isset($series[count($series) - 2]) ? $series[count($series) - 2] : $cur;
	$smarty->assign('ex_total_f', number_format($total, 0, ',', '.'));
	$smarty->assign('ex_series', $series); // cũ→mới (cho biểu đồ cột trái→phải)
	$smarty->assign('ex_current', $cur);
	$smarty->assign('ex_last_done', $last_done);
	// ===== Cơ cấu nguồn lead (12 tháng) =====
	$clsProp = new Property();
	$src_rows = $dbconn->GetAll("SELECT p.`title` AS source, COUNT(*) AS leads FROM `{$ctbl}` c JOIN `{$clsProp->tbl}` p ON p.`property_id`=c.`resource_id` WHERE c.`is_trash`=0 AND c.`resource_id`>0 AND c.`status_id` NOT IN (0,1283) AND c.`reg_date` BETWEEN {$start} AND {$now} GROUP BY c.`resource_id` ORDER BY leads DESC LIMIT 12");
	$src_total = 0;
	$src_max = 1;
	if (!empty($src_rows)) {
		foreach ($src_rows as $sr) {
			$src_total += (int) $sr['leads'];
			if ((int) $sr['leads'] > $src_max) {
				$src_max = (int) $sr['leads'];
			}
		}
	}
	$vizp = array('#696cff', '#03c3ec', '#71dd37', '#ffab00', '#ff3e1d', '#8592a3', '#233446');
	$sources = array();
	if (!empty($src_rows)) {
		$si = 0;
		foreach ($src_rows as $sr) {
			$ld = (int) $sr['leads'];
			$sources[] = array(
				'title' => $sr['source'],
				'leads_f' => number_format($ld, 0, ',', '.'),
				'bar_pct' => (int) round($ld * 100 / $src_max),
				'color' => $vizp[$si % count($vizp)]
			);
			$si++;
		}
	}
	$smarty->assign('ex_sources', $sources);
	$smarty->assign('ex_src_total_f', number_format($src_total, 0, ',', '.'));
	// ===== Phễu trạng thái KH (org-wide, theo order_no; loại Rác 363 + 0/1283). Map theo status_id để tính chốt/forecast chuẩn. =====
	$st_rows = $dbconn->GetAll("SELECT c.`status_id` AS sid, p.`title` AS title, p.`bgcolor` AS bgcolor, COUNT(*) AS cnt FROM `{$ctbl}` c JOIN `{$clsProp->tbl}` p ON p.`property_id`=c.`status_id` WHERE c.`is_trash`=0 AND c.`status_id` NOT IN (0,1283,363) GROUP BY c.`status_id` ORDER BY p.`order_no` ASC");
	$st_total = 0;
	$st_max = 1;
	$byId = array();
	if (!empty($st_rows)) {
		foreach ($st_rows as $r) {
			$st_total += (int) $r['cnt'];
			if ((int) $r['cnt'] > $st_max) {
				$st_max = (int) $r['cnt'];
			}
		}
	}
	$funnel = array();
	$prevc = null;
	if (!empty($st_rows)) {
		foreach ($st_rows as $r) {
			$cn = (int) $r['cnt'];
			$byId[(int) $r['sid']] = $cn;
			$has_conv = ($prevc !== null && $prevc > 0) ? 1 : 0;
			$conv = $has_conv ? min(100, (int) round($cn * 100 / $prevc)) : 0; // clamp ≤100 (đây là phân bố hiện tại, không phải conversion cohort)
			$drop = $has_conv ? (100 - $conv) : 0;
			$funnel[] = array(
				'title' => $r['title'],
				'bgcolor' => !empty($r['bgcolor']) ? $r['bgcolor'] : '#696cff',
				'cnt_f' => number_format($cn, 0, ',', '.'),
				'bar_pct' => (int) max(6, round(sqrt($cn / max(1, $st_max)) * 100)),
				'has_conv' => $has_conv,
				'conv' => $conv,
				'drop' => $drop
			);
			$prevc = $cn;
		}
	}
	$smarty->assign('ex_funnel', $funnel);
	$smarty->assign('ex_funnel_total_f', number_format($st_total, 0, ',', '.'));
	// ===== Tỉ lệ chốt: đã chốt theo status_id chuẩn (đồng bộ team board: _CRM_STATUS_CHOT_ID + 312) =====
	$closed = 0;
	$closed_ids = array((int) _CRM_STATUS_CHOT_ID, 312);
	foreach ($closed_ids as $cid) {
		if (isset($byId[$cid])) {
			$closed += $byId[$cid];
		}
	}
	$close_rate = $st_total > 0 ? round($closed * 100 / $st_total, 1) : 0;
	$smarty->assign('ex_closed_f', number_format($closed, 0, ',', '.'));
	$smarty->assign('ex_close_rate', number_format($close_rate, 1, ',', '.'));
	// ===== Dự báo doanh số (ƯỚC TÍNH): pipeline theo status_id × tỉ lệ chốt giả định × giá trị TB/deal. =====
	// Map status_id → tỉ lệ (loại trừ lẫn nhau theo ID). Thêm dòng 'Đặt cọc' khi biết status_id + tinh chỉnh rate/AVG_DEAL khi có dữ liệu giao dịch thật.
	$AVG_DEAL = 3.6; // tỷ/deal — GIẢ ĐỊNH
	$fc_stages = array(
		array('sid' => (int) _CRM_POTENTIAL_ID, 'name' => 'Tiềm năng', 'rate' => 0.12), // 294
		array('sid' => (int) _CRM_STATUS_HEN_ID, 'name' => 'Đã hẹn/gặp', 'rate' => 0.40) // 366
	);
	$fc_deals = 0;
	$fc_rows = array();
	foreach ($fc_stages as $stg) {
		$c = isset($byId[$stg['sid']]) ? $byId[$stg['sid']] : 0;
		$d = $c * $stg['rate'];
		$fc_deals += $d;
		$fc_rows[] = array('name' => $stg['name'], 'count_f' => number_format($c, 0, ',', '.'), 'rate' => (int) round($stg['rate'] * 100), 'deals' => (int) round($d));
	}
	$fc_base = (int) round($fc_deals);
	$smarty->assign('ex_fc_rows', $fc_rows);
	$smarty->assign('ex_fc_base', $fc_base);
	$smarty->assign('ex_fc_low', (int) round($fc_deals * 0.82));
	$smarty->assign('ex_fc_high', (int) round($fc_deals * 1.15));
	$smarty->assign('ex_fc_rev', number_format(round($fc_base * $AVG_DEAL, 1), 1, ',', '.'));
	$smarty->assign('ex_fc_avg_deal', number_format($AVG_DEAL, 1, ',', '.'));
	// ===== Nhịp độ gần đây: KH mới hôm nay / 7 ngày / 30 ngày + ▲▼ so kỳ liền trước (reg_date clamp <=now) =====
	$velRows = $dbconn->GetAll("SELECT SUM(`reg_date`>=UNIX_TIMESTAMP(CURDATE())) AS today, SUM(`reg_date`>=UNIX_TIMESTAMP()-604800) AS d7, SUM(`reg_date`>=UNIX_TIMESTAMP()-1209600 AND `reg_date`<UNIX_TIMESTAMP()-604800) AS d7p, SUM(`reg_date`>=UNIX_TIMESTAMP()-2592000) AS d30, SUM(`reg_date`>=UNIX_TIMESTAMP()-5184000 AND `reg_date`<UNIX_TIMESTAMP()-2592000) AS d30p FROM `{$ctbl}` WHERE `is_trash`=0 AND `reg_date`>0 AND `reg_date`<=UNIX_TIMESTAMP()");
	$v = !empty($velRows) ? $velRows[0] : array();
	$d7 = (int) (isset($v['d7']) ? $v['d7'] : 0);
	$d7p = (int) (isset($v['d7p']) ? $v['d7p'] : 0);
	$d30 = (int) (isset($v['d30']) ? $v['d30'] : 0);
	$d30p = (int) (isset($v['d30p']) ? $v['d30p'] : 0);
	$smarty->assign('ex_vel_today', (int) (isset($v['today']) ? $v['today'] : 0));
	$smarty->assign('ex_vel_d7', $d7);
	$smarty->assign('ex_vel_d7_has', $d7p > 0 ? 1 : 0);
	$smarty->assign('ex_vel_d7_up', $d7 >= $d7p ? 1 : 0);
	$smarty->assign('ex_vel_d7_pct', $d7p > 0 ? number_format(abs(round(($d7 - $d7p) * 100 / $d7p, 1)), 1, ',', '.') : '0');
	$smarty->assign('ex_vel_d30', $d30);
	$smarty->assign('ex_vel_d30_has', $d30p > 0 ? 1 : 0);
	$smarty->assign('ex_vel_d30_up', $d30 >= $d30p ? 1 : 0);
	$smarty->assign('ex_vel_d30_pct', $d30p > 0 ? number_format(abs(round(($d30 - $d30p) * 100 / $d30p, 1)), 1, ',', '.') : '0');
	// ===== TTFt — Tốc độ tiếp cận theo nguồn (12 tháng): lead → cú gọi/gặp đầu tiên (Gọi/Gặp). Trung vị tính ở PHP. =====
	$clsFollowUpTtft = new FollowUp();
	$ftbl = $clsFollowUpTtft->tbl;
	$ttft_rows = array();
	$ttft_total = 0;
	$ttft_feed = $dbconn->GetAll("SELECT p.`title` AS source, c.`resource_id` AS rid, (ft.`first_touch` - c.`reg_date`) AS diff_sec FROM `{$ctbl}` c JOIN (SELECT `customer_id`, MIN(`reg_date`) AS `first_touch` FROM `{$ftbl}` WHERE `is_trash`=0 AND `type_id` IN (" . _FOLLOWUP_CALL_ID . "," . _CRM_APPOINTMENT_ID . ") AND `reg_date`>0 GROUP BY `customer_id`) ft ON ft.`customer_id`=c.`customer_id` JOIN `{$clsProp->tbl}` p ON p.`property_id`=c.`resource_id` WHERE c.`is_trash`=0 AND c.`reg_date` BETWEEN {$start} AND {$now} AND c.`resource_id`>0 AND ft.`first_touch`>=c.`reg_date`");
	if (!empty($ttft_feed)) {
		$ttft_by = array();
		$ttft_title = array();
		foreach ($ttft_feed as $fr) {
			$rid = (int) $fr['rid'];
			$ttft_by[$rid][] = (int) $fr['diff_sec'];
			$ttft_title[$rid] = $fr['source'];
		}
		$ttft_tmp = array();
		$ttft_max_med = 1;
		foreach ($ttft_by as $rid => $arr) {
			$n = count($arr);
			sort($arr);
			$avg_h = round(array_sum($arr) / $n / 3600, 1);
			$mid = (int) floor($n / 2);
			$med_sec = ($n % 2 == 0) ? (($arr[$mid - 1] + $arr[$mid]) / 2) : $arr[$mid];
			$med_h = round($med_sec / 3600, 1);
			if ($med_h > $ttft_max_med) {
				$ttft_max_med = $med_h;
			}
			$ttft_total += $n;
			$ttft_tmp[] = array(
				'title' => $ttft_title[$rid],
				'n' => $n,
				'avg_label' => ($avg_h >= 48 ? number_format(round($avg_h / 24, 1), 1, ',', '.') . ' ngày' : number_format($avg_h, 1, ',', '.') . ' giờ'),
				'med_label' => ($med_h >= 48 ? number_format(round($med_h / 24, 1), 1, ',', '.') . ' ngày' : number_format($med_h, 1, ',', '.') . ' giờ'),
				'is_low' => ($n < 5) ? 1 : 0,
				'med_h' => $med_h,
				'bar_pct' => 0
			);
		}
		usort($ttft_tmp, function ($a, $b) {
			return $b['n'] - $a['n'];
		});
		foreach ($ttft_tmp as $k => $row) {
			$ttft_tmp[$k]['bar_pct'] = (int) round($row['med_h'] * 100 / $ttft_max_med);
		}
		$ttft_rows = $ttft_tmp;
	}
	$smarty->assign('ex_ttft_rows', $ttft_rows);
	$smarty->assign('ex_ttft_total', $ttft_total);
}
function default_load_team_board(){
	// Lăng kính Quản lý — sức khỏe nhóm: 1 dòng/rep. Server resolve nhóm theo manager_profile_id=me (BỎ QUA group_id client — đã vá IDOR P0-3).
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsCustomer = new Customer();
	if (!($clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss()) || $clsCustomer->isMarketing()) {
		echo json_encode(array(
			'error' => 1,
			'message' => 'Chỉ Quản lý nhóm xem được mục này.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$now = time();
	$pid = (int) $profile_id;
	$ctbl = $clsCustomer->tbl;
	$ftbl = $clsFollowUp->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$CALL = (int) _FOLLOWUP_CALL_ID;
	$ZALO = (int) _FOLLOWUP_ZALO_ID;
	$clsCustomerHistory = new CustomerHistory();
	$htbl = $clsCustomerHistory->tbl;
	// F5 — kỳ báo cáo (mặc định Tháng này, MTD). Kỳ chỉ ảnh hưởng các cột "kỳ": chốt kỳ / hoạt động kỳ / khách mới.
	$period = Input::post('period', 'this_month');
	$period_end = $now;
	switch ($period) {
		case 'last_month':
			$period_start = strtotime(date('Y-m-01 00:00:00', strtotime('first day of last month')));
			$period_end = strtotime(date('Y-m-t 23:59:59', strtotime('last day of last month')));
			$period_label = 'Tháng trước';
			break;
		case 'last_7_days':
			$period_start = $now - (7 * 86400);
			$period_label = '7 ngày qua';
			break;
		case 'last_30_days':
			$period_start = $now - (30 * 86400);
			$period_label = '30 ngày qua';
			break;
		case 'this_month':
		default:
			$period = 'this_month';
			$period_start = strtotime(date('Y-m-01 00:00:00'));
			$period_label = 'Tháng này';
			break;
	}
	$kpi = array(
		'active'		=> 0,
		'chot_period'	=> 0,
		'act_total'		=> 0,
		'overdue'		=> 0,
		'new_period'	=> 0,
		'chot_total'	=> 0,
		'conv_rate'		=> 0
	);
	// resolve reps từ các nhóm mình quản lý (full-permiss = tất cả nhóm). list_profile_id format |N| → getArrayByTextSlash.
	$gcond = $clsCustomer->isFullPermiss() ? "`is_trash`=0" : "`manager_profile_id`='{$pid}' AND `is_trash`=0";
	$groups = $clsGroupProfile->getAll($gcond, "{$clsGroupProfile->pkey},`title`,`list_profile_id`");
	$rep_ids = array();
	if (!empty($groups)) {
		foreach ($groups as $g) {
			$arr = !empty($g['list_profile_id']) ? $clsISO->getArrayByTextSlash($g['list_profile_id']) : array();
			foreach ($arr as $rid) {
				$rid = (int) $rid;
				if ($rid > 0) {
					$rep_ids[$rid] = $rid;
				}
			}
		}
	}
	$group_count = !empty($groups) ? count($groups) : 0;
	if (empty($rep_ids)) {
		$smarty->assign('tb_rows', array());
		$smarty->assign('tb_total_reps', 0);
		$smarty->assign('tb_group_count', $group_count);
		$smarty->assign('tb_period', $period);
		$smarty->assign('tb_period_label', $period_label);
		$smarty->assign('tb_kpi', $kpi);
		$html = $core->build('_ajax.team_board.tpl');
		echo json_encode(array(
			'html' => $html,
			'total' => 0
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$repCsv = implode(',', array_values($rep_ids));
	// A) khách/rep: active + chốt (tích luỹ) + chưa-chạm + khách mới trong kỳ
	$rowsCus = $dbconn->GetAll(
		"SELECT c.`admin_id`,"
			. " SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') THEN 1 ELSE 0 END) AS active_cus,"
			. " SUM(CASE WHEN c.`status_id` IN ('{$CHOT}','312') THEN 1 ELSE 0 END) AS chot,"
			. " SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0) THEN 1 ELSE 0 END) AS no_touch,"
			. " SUM(CASE WHEN c.`reg_date` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS new_period"
			. " FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND c.`admin_id` IN ({$repCsv}) GROUP BY c.`admin_id`"
	);
	// B) follow-up/rep: quá hạn + hoạt động gần nhất (past-only). v2: SIẾT theo khách rep ĐANG giữ + active (INNER JOIN c) → bỏ follow-up tồn của khách đã chuyển đi.
	$rowsFu = $dbconn->GetAll(
		"SELECT f.`admin_id`,"
			. " SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) AS overdue,"
			. " MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END) AS last_act"
			. " FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id` AND c.`admin_id`=f.`admin_id` AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}')"
			. " WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id` IN ({$repCsv}) GROUP BY f.`admin_id`"
	);
	// C) hoạt động trong kỳ: follow-up _crm đã HOÀN THÀNH có date_id thuộc kỳ — tách Gọi / Zalo
	$rowsAct = $dbconn->GetAll(
		"SELECT f.`admin_id`,"
			. " COUNT(*) AS act_total,"
			. " SUM(CASE WHEN f.`type_id`='{$CALL}' THEN 1 ELSE 0 END) AS act_call,"
			. " SUM(CASE WHEN f.`type_id`='{$ZALO}' THEN 1 ELSE 0 END) AS act_zalo"
			. " FROM `{$ftbl}` f WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`status_id`='{$DONE}'"
			. " AND f.`date_id` BETWEEN {$period_start} AND {$period_end} AND f.`admin_id` IN ({$repCsv}) GROUP BY f.`admin_id`"
	);
	// D) chốt trong kỳ: lịch sử chuyển status → CHỐT trong kỳ, quy về CHỦ SỞ HỮU khách (đồng bộ các cột khác = theo admin_id)
	$rowsChot = $dbconn->GetAll(
		"SELECT c.`admin_id`, COUNT(DISTINCT h.`customer_id`) AS chot_period"
			. " FROM `{$htbl}` h INNER JOIN `{$ctbl}` c ON c.`customer_id`=h.`customer_id`"
			. " WHERE h.`to_status_id` IN ('{$CHOT}','312') AND h.`action_date` BETWEEN {$period_start} AND {$period_end}"
			. " AND c.`is_trash`=0 AND c.`admin_id` IN ({$repCsv}) GROUP BY c.`admin_id`"
	);
	$cusMap = array();
	if (!empty($rowsCus)) {
		foreach ($rowsCus as $r) {
			$cusMap[(int)$r['admin_id']] = $r;
		}
	}
	$fuMap = array();
	if (!empty($rowsFu)) {
		foreach ($rowsFu as $r) {
			$fuMap[(int)$r['admin_id']] = $r;
		}
	}
	$actMap = array();
	if (!empty($rowsAct)) {
		foreach ($rowsAct as $r) {
			$actMap[(int)$r['admin_id']] = $r;
		}
	}
	$chotMap = array();
	if (!empty($rowsChot)) {
		foreach ($rowsChot as $r) {
			$chotMap[(int)$r['admin_id']] = $r;
		}
	}
	$arr_rep = array();
	$pn = $dbconn->GetAll("SELECT `profile_id`,`full_name`,`more_information`,`avatar` FROM `{$clsProfile->tbl}` WHERE `profile_id` IN ({$repCsv})");
	if (!empty($pn)) {
		foreach ($pn as $s) {
			$arr_rep[(int)$s['profile_id']] = $s;
		}
	}
	$rows = array();
	foreach ($rep_ids as $rid) {
		$cu = isset($cusMap[$rid]) ? $cusMap[$rid] : null;
		$fu = isset($fuMap[$rid]) ? $fuMap[$rid] : null;
		$ac = isset($actMap[$rid]) ? $actMap[$rid] : null;
		$ch = isset($chotMap[$rid]) ? $chotMap[$rid] : null;
		$last_act = ($fu && !empty($fu['last_act'])) ? (int) $fu['last_act'] : 0;
		$oRep = isset($arr_rep[$rid]) ? $arr_rep[$rid] : null;
		$active = $cu ? (int) $cu['active_cus'] : 0;
		$chot_total = $cu ? (int) $cu['chot'] : 0;
		$chot_period = $ch ? (int) $ch['chot_period'] : 0;
		$act_total = $ac ? (int) $ac['act_total'] : 0;
		$new_period = $cu ? (int) $cu['new_period'] : 0;
		$overdue = $fu ? (int) $fu['overdue'] : 0;
		$denom = $active + $chot_total;
		$conv = $denom > 0 ? (int) round($chot_total / $denom * 100) : 0;
		$rows[] = array(
			'rep_id' => $rid,
			'rep_name' => ($oRep && trim($oRep['full_name']) !== '' ? trim($oRep['full_name']) : '#' . $rid),
			'rep_avatar' => $clsProfile->getAvatar($rid, ($oRep ? $oRep : array())),
			'active' => $active,
			'chot' => $chot_total,
			'chot_period' => $chot_period,
			'act_total' => $act_total,
			'act_call' => $ac ? (int) $ac['act_call'] : 0,
			'act_zalo' => $ac ? (int) $ac['act_zalo'] : 0,
			'new_period' => $new_period,
			'conv_rate' => $conv,
			'no_touch' => $cu ? (int) $cu['no_touch'] : 0,
			'overdue' => $overdue,
			'last_text' => ($last_act > 0 ? $clsISO->getTimeAgo($last_act) : 'Chưa có'),
			'days_idle' => ($last_act > 0 ? (int) max(0, floor(($now - $last_act) / 86400)) : -1)
		);
		$kpi['active'] += $active;
		$kpi['chot_period'] += $chot_period;
		$kpi['act_total'] += $act_total;
		$kpi['overdue'] += $overdue;
		$kpi['new_period'] += $new_period;
		$kpi['chot_total'] += $chot_total;
	}
	$kpi_denom = $kpi['active'] + $kpi['chot_total'];
	$kpi['conv_rate'] = $kpi_denom > 0 ? (int) round($kpi['chot_total'] / $kpi_denom * 100) : 0;
	// xếp hạng hiệu suất: chốt kỳ ↓, hoạt động kỳ ↓, rồi quá hạn ↓ (vẫn lộ rep tồn đọng)
	usort($rows, function ($a, $b) {
		if ($b['chot_period'] != $a['chot_period']) {
			return $b['chot_period'] - $a['chot_period'];
		}
		if ($b['act_total'] != $a['act_total']) {
			return $b['act_total'] - $a['act_total'];
		}
		return $b['overdue'] - $a['overdue'];
	});
	$smarty->assign('tb_rows', $rows);
	$smarty->assign('tb_total_reps', count($rows));
	$smarty->assign('tb_group_count', $group_count);
	$smarty->assign('tb_period', $period);
	$smarty->assign('tb_period_label', $period_label);
	$smarty->assign('tb_kpi', $kpi);
	$html = $core->build('_ajax.team_board.tpl');
	echo json_encode(array(
		'html' => $html,
		'total' => count($rows)
	), JSON_UNESCAPED_UNICODE);
	die();
}
/* ===== Care Monitor — Giám sát chăm sóc khách (lãnh đạo/manager/MKT) ===== */
// Resolver phạm vi an toàn. Cờ `unrestricted` TÁCH khỏi `admin_ids` (KHÔNG dùng []=all → tránh IDOR/full-scan). MKT mode-a = khách user_id=me.
function _crm_care_scope(){
	global $dbconn, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$pid = (int) $profile_id;
	$scope = array('ok' => 0, 'unrestricted' => 0, 'admin_ids' => array(), 'mkt_user_id' => 0);
	$full = ($clsCustomer->isFullPermiss() || $clsISO->checkPermissionGroup('DIRECTOR'));
	if ($full || $clsCustomer->isTeamManager()) {
		$clsGroupProfile = new GroupProfile();
		$gcond = $full ? "`is_trash`=0" : "`manager_profile_id`='{$pid}' AND `is_trash`=0";
		$groups = $clsGroupProfile->getAll($gcond, "`list_profile_id`");
		$rep_ids = array();
		if (!empty($groups)) {
			foreach ($groups as $g) {
				$arr = !empty($g['list_profile_id']) ? $clsISO->getArrayByTextSlash($g['list_profile_id']) : array();
				foreach ($arr as $rid) {
					$rid = (int) $rid;
					if ($rid > 0) {
						$rep_ids[$rid] = $rid;
					}
				}
			}
		}
		$scope['ok'] = 1;
		$scope['unrestricted'] = $full ? 1 : 0;
		// Chỉ giữ nhân sự thuộc các phòng Kinh doanh (department dưới _DEPARTMENT_SALE_ID).
		$scope['admin_ids'] = _crm_care_filter_sales_reps(array_values($rep_ids));
		return $scope;
	}
	// MKT: nhân sự phòng/role Marketing (server-side, KHÔNG phải cờ tự-khai) → chỉ khách mình đẩy (user_id=me).
	if ($clsCustomer->isMarketing()) {
		$scope['ok'] = 1;
		$scope['mkt_user_id'] = $pid;
		return $scope;
	}
	return $scope; // ok=0 → chặn
}
// Kỳ báo cáo. Mặc định 30 ngày.
function _crm_care_period($period)
{
	$now = time();
	$end = $now;
	switch ($period) {
		case 'this_month':
			$start = strtotime(date('Y-m-01 00:00:00'));
			$label = 'Tháng này';
			break;
		case 'last_month':
			$start = strtotime(date('Y-m-01 00:00:00', strtotime('first day of last month')));
			$end   = strtotime(date('Y-m-t 23:59:59', strtotime('last day of last month')));
			$label = 'Tháng trước';
			break;
		case 'last_7_days':
			$start = $now - (7 * 86400);
			$label = '7 ngày qua';
			break;
		case 'last_30_days':
		default:
			$period = 'last_30_days';
			$start = $now - (30 * 86400);
			$label = '30 ngày qua';
			break;
	}
	return array($period, (int) $start, (int) $end, $label);
}
// Tập department_id khối Kinh doanh: parent _DEPARTMENT_SALE_ID + toàn bộ phòng/tổ con. Cache 1 request.
function _crm_care_sales_dept_ids(){
	static $cached = null;
	if ($cached !== null) {
		return $cached;
	}
	$clsProperty = new Property();
	$ids = array((int) _DEPARTMENT_SALE_ID);
	$clsProperty->getChilds((int) _DEPARTMENT_SALE_ID, $ids);
	$clean = array();
	foreach ($ids as $d) {
		$d = (int) $d;
		if ($d > 0) {
			$clean[$d] = $d;
		}
	}
	$cached = array_values($clean);
	return $cached;
}
// Lọc list profile_id → chỉ giữ nhân sự thuộc các phòng Kinh doanh (department dưới 40).
function _crm_care_filter_sales_reps($rep_ids)
{
	global $dbconn;
	$clean = array();
	foreach ((array) $rep_ids as $v) {
		$v = (int) $v;
		if ($v > 0) {
			$clean[$v] = $v;
		}
	}
	if (empty($clean)) {
		return array();
	}
	$deptIds = _crm_care_sales_dept_ids();
	if (empty($deptIds)) {
		return array_values($clean);
	}
	// "Thuộc phòng KD" theo convention codebase (report/takeleave/training): cột chính department_id HOẶC đa-phòng list_department_id (|id|).
	$likeParts = array();
	foreach ($deptIds as $d) {
		$likeParts[] = "`list_department_id` LIKE '%|{$d}|%'";
	}
	$deptCond = "`department_id` IN (" . implode(',', $deptIds) . ")";
	if (!empty($likeParts)) {
		$deptCond = "(" . $deptCond . " OR " . implode(' OR ', $likeParts) . ")";
	}
	$clsProfile = new Profile();
	$rows = $dbconn->GetAll("SELECT `profile_id` FROM `{$clsProfile->tbl}` WHERE `profile_id` IN (" . implode(',', $clean) . ") AND {$deptCond}");
	$out = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$out[] = (int) $r['profile_id'];
		}
	}
	return $out;
}
// Format TTFt (giây) → text gọn. -1 = không có dữ liệu.
function _crm_care_ttft_text($sec)
{
	$sec = (int) $sec;
	if ($sec < 0) {
		return '—';
	}
	if ($sec < 3600) {
		return max(1, (int) round($sec / 60)) . ' phút';
	}
	if ($sec < 86400) {
		return number_format($sec / 3600, 1) . ' giờ';
	}
	return number_format($sec / 86400, 1) . ' ngày';
}
// Danh sách Sale trong phạm vi (cho dropdown lọc).
function _crm_care_sales_list($scope){
	global $dbconn;
	$clsProfile = new Profile();
	$ids = array();
	if ((int)$scope['mkt_user_id'] > 0) {
		$clsCustomer = new Customer();
		$mkt = (int) $scope['mkt_user_id'];
		$rs = $dbconn->GetAll("SELECT DISTINCT `admin_id` FROM `{$clsCustomer->tbl}` WHERE `is_trash`=0 AND `user_id`='{$mkt}' AND `admin_id`>0");
		$tmp = array();
		if (!empty($rs)) {
			foreach ($rs as $r) {
				$tmp[] = (int)$r['admin_id'];
			}
		}
		foreach (_crm_care_filter_sales_reps($tmp) as $rid) {
			$ids[$rid] = $rid;
		} // MKT: lọc theo phòng KD
	} else {
		foreach ((array)$scope['admin_ids'] as $rid) {
			$rid = (int) $rid;
			if ($rid > 0) {
				$ids[$rid] = $rid;
			}
		}
	}
	$out = array();
	if (!empty($ids)) {
		$pn = $dbconn->GetAll("SELECT `profile_id`,`full_name` FROM `{$clsProfile->tbl}` WHERE `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND `profile_id` IN (" . implode(',', array_values($ids)) . ")");
		if (!empty($pn)) {
			foreach ($pn as $p) {
				$out[] = array(
					'id' => (int)$p['profile_id'], 
					'name' => (trim($p['full_name']) !== '' ? trim($p['full_name']) : '#' . (int)$p['profile_id']));
			}
		}
	}
	return $out;
}
// T1 aggregate — predicate COPY từ default_load_team_board (KHÔNG refactor handler live). Bound theo scope; count = DONE+kỳ; cột owner-scoped để T1↔T2 khớp.
function _crm_care_aggregate($scope, $period_start, $period_end){
	global $dbconn, $clsISO;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	$clsCustomerHistory = new CustomerHistory();
	$now = time();
	$ctbl = $clsCustomer->tbl;
	$ftbl = $clsFollowUp->tbl;
	$htbl = $clsCustomerHistory->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$CALL = (int) _FOLLOWUP_CALL_ID;
	$ZALO = (int) _FOLLOWUP_ZALO_ID;
	$APPT = (int) _CRM_APPOINTMENT_ID;
	$mkt = (int) $scope['mkt_user_id'];
	// rep_ids: rep path = scope.admin_ids; MKT = distinct admin_id của khách user_id=me.
	$rep_ids = array();
	if ($mkt > 0) {
		$rs = $dbconn->GetAll("SELECT DISTINCT `admin_id` FROM `{$ctbl}` WHERE `is_trash`=0 AND `user_id`='{$mkt}' AND `admin_id`>0");
		$tmp = array();
		if (!empty($rs)) {
			foreach ($rs as $r) {
				$tmp[] = (int)$r['admin_id'];
			}
		}
		foreach (_crm_care_filter_sales_reps($tmp) as $rid) {
			$rep_ids[$rid] = $rid;
		} // MKT: lọc theo phòng KD
	} else {
		foreach ((array)$scope['admin_ids'] as $rid) {
			$rid = (int) $rid;
			if ($rid > 0) {
				$rep_ids[$rid] = $rid;
			}
		} // full/manager: scope.admin_ids đã lọc
	}
	if (empty($rep_ids)) {
		return array();
	}
	$repCsv = implode(',', array_values($rep_ids));
	$cScope = $mkt > 0 ? "c.`user_id`='{$mkt}'" : "c.`admin_id` IN ({$repCsv})";
	// A) khách/rep: tổng + active + chốt + chưa-chạm + khách mới kỳ
	$rowsCus = $dbconn->GetAll(
		"SELECT c.`admin_id`, COUNT(*) AS total_cus,"
			. " SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') THEN 1 ELSE 0 END) AS active_cus,"
			. " SUM(CASE WHEN c.`status_id` IN ('{$CHOT}','312') THEN 1 ELSE 0 END) AS chot,"
			. " SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0) THEN 1 ELSE 0 END) AS no_touch,"
			. " SUM(CASE WHEN c.`reg_date` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS new_period"
			. " FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND {$cScope} GROUP BY c.`admin_id`"
	);
	// B) overdue + last_act (INNER c: siết theo khách rep đang giữ + active, đồng nhất Team Board)
	$rowsFu = $dbconn->GetAll(
		"SELECT c.`admin_id`,"
			. " SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) AS overdue,"
			. " MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END) AS last_act"
			. " FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id` AND c.`admin_id`=f.`admin_id` AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}')"
			. " WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND {$cScope} GROUP BY c.`admin_id`"
	);
	// C) số lần chăm kỳ (DONE + kỳ), tách Gọi/Zalo — owner-scoped (INNER c) để cộng T2 khớp T1
	$rowsAct = $dbconn->GetAll(
		"SELECT c.`admin_id`, COUNT(*) AS act_total,"
			. " SUM(CASE WHEN f.`type_id`='{$CALL}' THEN 1 ELSE 0 END) AS act_call,"
			. " SUM(CASE WHEN f.`type_id`='{$ZALO}' THEN 1 ELSE 0 END) AS act_zalo"
			. " FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id` AND c.`admin_id`=f.`admin_id` AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}')"
			. " WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`status_id`='{$DONE}' AND f.`date_id` BETWEEN {$period_start} AND {$period_end} AND {$cScope} GROUP BY c.`admin_id`"
	);
	// D) chốt kỳ: lịch sử chuyển status → CHỐT trong kỳ, quy về chủ sở hữu khách
	$rowsChot = $dbconn->GetAll(
		"SELECT c.`admin_id`, COUNT(DISTINCT h.`customer_id`) AS chot_period"
			. " FROM `{$htbl}` h INNER JOIN `{$ctbl}` c ON c.`customer_id`=h.`customer_id`"
			. " WHERE h.`to_status_id` IN ('{$CHOT}','312') AND h.`action_date` BETWEEN {$period_start} AND {$period_end} AND c.`is_trash`=0 AND {$cScope} GROUP BY c.`admin_id`"
	);
	// E) TTFt per-Sale: mean(giây) từ tạo khách → lần chạm DONE đầu (Gọi/Gặp). Việc MỚI — Team Board không có.
	$rowsTtft = $dbconn->GetAll(
		"SELECT t.`admin_id`, AVG(t.`ttft`) AS ttft_avg FROM ("
			. " SELECT c.`admin_id`, (MIN(f.`date_id`) - c.`reg_date`) AS ttft"
			. " FROM `{$ctbl}` c INNER JOIN `{$ftbl}` f ON f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`status_id`='{$DONE}' AND f.`type_id` IN ('{$CALL}','{$APPT}') AND f.`date_id`>0"
			. " WHERE c.`is_trash`=0 AND c.`reg_date`>0 AND {$cScope}"
			. " GROUP BY c.`customer_id` HAVING MIN(f.`date_id`) >= c.`reg_date`"
			. ") t GROUP BY t.`admin_id`"
	);
	$cusMap = array();
	foreach ((array)$rowsCus  as $r) {
		$cusMap[(int)$r['admin_id']]  = $r;
	}
	$fuMap = array();
	foreach ((array)$rowsFu   as $r) {
		$fuMap[(int)$r['admin_id']]   = $r;
	}
	$actMap = array();
	foreach ((array)$rowsAct  as $r) {
		$actMap[(int)$r['admin_id']]  = $r;
	}
	$chotMap = array();
	foreach ((array)$rowsChot as $r) {
		$chotMap[(int)$r['admin_id']] = $r;
	}
	$ttMap = array();
	foreach ((array)$rowsTtft as $r) {
		$ttMap[(int)$r['admin_id']]   = $r;
	}
	$arr_rep = array();
	$pn = $dbconn->GetAll("SELECT `profile_id`,`full_name`,`more_information`,`avatar` FROM `{$clsProfile->tbl}` WHERE `profile_id` IN ({$repCsv})");
	if (!empty($pn)) {
		foreach ($pn as $s) {
			$arr_rep[(int)$s['profile_id']] = $s;
		}
	}
	$rows = array();
	foreach ($rep_ids as $rid) {
		$cu = isset($cusMap[$rid]) ? $cusMap[$rid] : null;
		$fu = isset($fuMap[$rid]) ? $fuMap[$rid] : null;
		$ac = isset($actMap[$rid]) ? $actMap[$rid] : null;
		$ch = isset($chotMap[$rid]) ? $chotMap[$rid] : null;
		$last_act = ($fu && !empty($fu['last_act'])) ? (int) $fu['last_act'] : 0;
		$oRep = isset($arr_rep[$rid]) ? $arr_rep[$rid] : null;
		$active = $cu ? (int) $cu['active_cus'] : 0;
		$chot_total = $cu ? (int) $cu['chot'] : 0;
		$denom = $active + $chot_total;
		$ttft_avg = (isset($ttMap[$rid]) && $ttMap[$rid]['ttft_avg'] !== null) ? (int) round($ttMap[$rid]['ttft_avg']) : -1;
		$days_idle = ($last_act > 0) ? (int) max(0, floor(($now - $last_act) / 86400)) : -1;
		$rows[] = array(
			'rep_id' => $rid,
			'rep_name' => ($oRep && trim($oRep['full_name']) !== '' ? trim($oRep['full_name']) : '#' . $rid),
			'rep_avatar' => $clsProfile->getAvatar($rid, ($oRep ? $oRep : array())),
			'total_cus' => $cu ? (int) $cu['total_cus'] : 0,
			'active' => $active,
			'chot' => $chot_total,
			'chot_period' => $ch ? (int) $ch['chot_period'] : 0,
			'act_total' => $ac ? (int) $ac['act_total'] : 0,
			'act_call' => $ac ? (int) $ac['act_call'] : 0,
			'act_zalo' => $ac ? (int) $ac['act_zalo'] : 0,
			'new_period' => $cu ? (int) $cu['new_period'] : 0,
			'conv_rate' => $denom > 0 ? (int) round($chot_total / $denom * 100) : 0,
			'no_touch' => $cu ? (int) $cu['no_touch'] : 0,
			'overdue' => $fu ? (int) $fu['overdue'] : 0,
			'last_text' => ($last_act > 0 ? $clsISO->getTimeAgo($last_act) : 'Chưa có'),
			'days_idle' => $days_idle,
			'ttft_avg' => $ttft_avg,
			'ttft_text' => _crm_care_ttft_text($ttft_avg)
		);
	}
	// xếp worst-first: quá hạn ↓, rồi idle ↓ (chưa chạm = -1 → đẩy lên đầu)
	usort($rows, function ($a, $b) {
		if ($b['overdue'] != $a['overdue']) {
			return $b['overdue'] - $a['overdue'];
		}
		$ai = $a['days_idle'] < 0 ? PHP_INT_MAX : $a['days_idle'];
		$bi = $b['days_idle'] < 0 ? PHP_INT_MAX : $b['days_idle'];
		return $bi - $ai;
	});
	if (count($rows) > 200) {
		$rows = array_slice($rows, 0, 200);
	} // cap an toàn (mirror kỷ luật LIMIT marketing_control)
	return $rows;
}
// Vỏ trang Giám sát chăm sóc khách. T1+T2 nạp qua AJAX. Full-page.
function default_care_monitor(){
	global $smarty, $assign_list, $title_page;
	$title_page = 'Giám sát chăm sóc khách | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
	$scope = _crm_care_scope();
	$smarty->assign('cm_denied', $scope['ok'] ? 0 : 1);
	$smarty->assign('cm_sales', $scope['ok'] ? _crm_care_sales_list($scope) : array());
	$smarty->assign('cm_period', 'last_30_days');
}
// AJAX T1 — bảng tổng hợp per-Sale.
function default_load_care_monitor(){
	global $smarty, $core;
	$scope = _crm_care_scope();
	if (!$scope['ok']) {
		echo json_encode(array(
			'error' => 1, 
			'message' => 'Bạn không có quyền xem mục này.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	list($period, $period_start, $period_end, $period_label) = _crm_care_period(Input::post('period', 'last_30_days'));
	$rows = _crm_care_aggregate($scope, $period_start, $period_end);
	$smarty->assign('cm_rows', $rows);
	$smarty->assign('cm_total', count($rows));
	$smarty->assign('cm_period', $period);
	$smarty->assign('cm_period_label', $period_label);
	// Return
	$html = $core->build('_ajax.care_monitor.tpl');
	echo json_encode(array('error' => 0, 'html' => $html, 'total' => count($rows)), JSON_UNESCAPED_UNICODE);
	die();
}
// AJAX T2 — drill-down từng khách của 1 Sale. IDOR gate qua _crm_care_scope.
function default_load_care_monitor_detail(){
	global $smarty, $core, $clsISO, $dbconn;
	$scope = _crm_care_scope();
	if (!$scope['ok']) {
		echo json_encode(array('error' => 1, 'message' => 'Bạn không có quyền xem mục này.'), JSON_UNESCAPED_UNICODE);
		die();
	}
	$admin_id = (int) Input::post('admin_id', 0);
	if ($admin_id <= 0) {
		echo json_encode(array('error' => 1, 'message' => 'Thiếu thông tin tư vấn viên.'), JSON_UNESCAPED_UNICODE);
		die();
	}
	$mkt = (int) $scope['mkt_user_id'];
	// IDOR gate: full xem mọi Sale; manager chỉ Sale trong nhóm; MKT bất kỳ Sale NHƯNG ép user_id=me (chỉ khách mình đẩy).
	if (!$scope['unrestricted'] && $mkt <= 0 && !in_array($admin_id, array_map('intval', (array)$scope['admin_ids']), true)) {
		echo json_encode(array('error' => 0, 'html' => '', 'total' => 0), JSON_UNESCAPED_UNICODE);
		die();
	}
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsCustomerAssign = new CustomerAssign();
	$now = time();
	$ctbl = $clsCustomer->tbl;
	$ftbl = $clsFollowUp->tbl;
	$atbl = $clsCustomerAssign->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$CALL = (int) _FOLLOWUP_CALL_ID;
	$ZALO = (int) _FOLLOWUP_ZALO_ID;
	$APPT = (int) _CRM_APPOINTMENT_ID;
	list($period, $period_start, $period_end, $period_label) = _crm_care_period(Input::post('period', 'last_30_days'));
	$status_id = (int) Input::post('status_id', 0);
	$only_overdue = (int) Input::post('only_overdue', 0);
	$idle_min = (int) Input::post('idle_min', 0);
	$LIMIT = 100;
	// Phạm vi khách của Sale (đang chăm). MKT ép thêm user_id=me. owner-scoped → cộng khớp T1.
	$wScope = "c.`admin_id`='{$admin_id}' AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}')";
	if ($mkt > 0) {
		$wScope .= " AND c.`user_id`='{$mkt}'";
	}
	if ($status_id > 0) {
		$wScope .= " AND c.`status_id`='{$status_id}'";
	}
	$having = $only_overdue ? " HAVING SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) > 0" : "";
	$total = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` c WHERE {$wScope}");
	// 1 query gộp: số lần chăm = DONE + trong kỳ (khớp T1), tách Gọi/Zalo; last_fu + quá hạn (past-only).
	$rows = $dbconn->GetAll(
		"SELECT c.`customer_id`,c.`name`,c.`phone`,c.`reg_date`,c.`status_id`,"
			. " SUM(CASE WHEN f.`status_id`='{$DONE}' AND f.`date_id` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS fu_count,"
			. " SUM(CASE WHEN f.`status_id`='{$DONE}' AND f.`type_id`='{$CALL}' AND f.`date_id` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS fu_call,"
			. " SUM(CASE WHEN f.`status_id`='{$DONE}' AND f.`type_id`='{$ZALO}' AND f.`date_id` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS fu_zalo,"
			. " MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END) AS last_fu,"
			. " SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) AS overdue_count"
			. " FROM `{$ctbl}` c LEFT JOIN `{$ftbl}` f ON f.`customer_id`=c.`customer_id` AND f.`admin_id`=c.`admin_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0"
			. " WHERE {$wScope} GROUP BY c.`customer_id`{$having}"
			. " ORDER BY SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) DESC, COALESCE(MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END), c.`reg_date`) DESC LIMIT {$LIMIT}"
	);
	if ($rows === false) {
		@error_log('[care_monitor_detail] GetAll SQL error: ' . $dbconn->ErrorMsg());
		$rows = array();
	}
	$cids = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$cids[] = (int) $r['customer_id'];
		}
	}
	// batch "Ngày vào/giao": assigned_at (channel=1) theo người nhận hiện tại; thiếu → fallback reg_date (dấu ~).
	$assignMap = array();
	if (!empty($cids)) {
		$cinl = implode(',', $cids);
		$ar = $dbconn->GetAll("SELECT `customer_id`, MAX(`assigned_at`) AS a FROM `{$atbl}` WHERE `recipient_id`='{$admin_id}' AND `channel`='1' AND `is_trash`=0 AND `customer_id` IN ({$cinl}) GROUP BY `customer_id`");
		if (!empty($ar)) {
			foreach ($ar as $a) {
				$assignMap[(int)$a['customer_id']] = (int)$a['a'];
			}
		}
	}
	// batch TTFt: lần chạm DONE đầu (Gọi/Gặp) − reg_date. Đủ predicate followup_type/is_trash.
	$ttMap = array();
	if (!empty($cids)) {
		$cinl = implode(',', $cids);
		$tr = $dbconn->GetAll("SELECT `customer_id`, MIN(`date_id`) AS ft FROM `{$ftbl}` WHERE `followup_type`='_crm' AND `is_trash`=0 AND `status_id`='{$DONE}' AND `type_id` IN ('{$CALL}','{$APPT}') AND `date_id`>0 AND `customer_id` IN ({$cinl}) GROUP BY `customer_id`");
		if (!empty($tr)) {
			foreach ($tr as $t) {
				$ttMap[(int)$t['customer_id']] = (int)$t['ft'];
			}
		}
	}
	// nhãn trạng thái (1 query)
	$arr_status = array();
	$sres = $dbconn->GetAll("SELECT `property_id`,`title`,`bgcolor`,`textcolor` FROM `{$clsProperty->tbl}` WHERE `property_type`='CUSTOMER_STATUS' AND `is_trash`=0");
	if (!empty($sres)) {
		foreach ($sres as $s) {
			$arr_status[(int)$s['property_id']] = $s;
		}
	}
	$list = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$cid = (int) $r['customer_id'];
			$ph = trim($r['phone']);
			$reg = (int) $r['reg_date'];
			$last_fu = (int) $r['last_fu'];
			$last_act = $last_fu > 0 ? $last_fu : $reg;
			$days_idle = $last_act > 0 ? (int) max(0, floor(($now - $last_act) / 86400)) : -1;
			if ($idle_min > 0 && ($days_idle < 0 || $days_idle < $idle_min)) {
				continue;
			} // lọc idle (post-filter v1)
			$oS = isset($arr_status[(int)$r['status_id']]) ? $arr_status[(int)$r['status_id']] : null;
			$assigned_at = isset($assignMap[$cid]) ? (int) $assignMap[$cid] : 0;
			$is_proxy = $assigned_at > 0 ? 0 : 1;
			$assigned_eff = $assigned_at > 0 ? $assigned_at : $reg;
			$ft = isset($ttMap[$cid]) ? (int) $ttMap[$cid] : 0;
			$ttft = ($ft > 0 && $reg > 0 && $ft >= $reg) ? ($ft - $reg) : -1;
			$nm = trim($r['name']);
			$list[] = array(
				'customer_id' => $cid,
				'name' => ($nm !== '' ? ucfirst($nm) : 'Không tên'),
				'phone_mask' => ($ph !== '' ? $clsCustomer->mask($ph, true) : ''),
				'status_title' => ($oS ? $oS['title'] : ''),
				'status_bg' => ($oS ? $oS['bgcolor'] : '#888888'),
				'status_color' => ($oS ? $oS['textcolor'] : '#ffffff'),
				'assigned_text' => ($assigned_eff > 0 ? date('H:i d/m/Y', $assigned_eff) : '—'),
				'assigned_is_proxy' => $is_proxy,
				'last_contact' => ($last_fu > 0 ? $clsISO->getTimeAgo($last_fu) : 'Chưa liên hệ'),
				'days_idle' => $days_idle,
				'fu_count' => (int) $r['fu_count'],
				'fu_call' => (int) $r['fu_call'],
				'fu_zalo' => (int) $r['fu_zalo'],
				'overdue' => (int) $r['overdue_count'],
				'ttft' => $ttft,
				'ttft_text' => _crm_care_ttft_text($ttft)
			);
		}
	}
	$smarty->assign('cd_rows', $list);
	$smarty->assign('cd_total', $total);
	$smarty->assign('cd_limit', $LIMIT);
	$smarty->assign('cd_admin_id', $admin_id);
	$html = $core->build('_ajax.care_monitor_detail.tpl');
	echo json_encode(array('error' => 0, 'html' => $html, 'total' => $total), JSON_UNESCAPED_UNICODE);
	die();
}
function default_my_assigned(){
	// /crm/assigned/ (act=my_assigned) — Vỏ trang "Khách tôi giao"; KPI + bảng nạp qua AJAX (act=load_assigned). Full-page.
	global $smarty, $assign_list, $title_page, $profile_id;
	$title_page = 'Khách tôi giao | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
	$status = Input::request('status', 'all');
	if (!in_array($status, array('all', 'pending', 'confirmed'), true)) {
		$status = 'all';
	}
	$smarty->assign('ma_status', $status);
}
function default_load_assigned(){
	// AJAX cho /crm/assigned/ — KPI + bảng phiếu giao (assigned_by = mình). $status: all|pending|overdue|confirmed.
	global $smarty, $core, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsCustomerAssign = new CustomerAssign();
	$status = Input::request('status', 'all');
	if (!in_array($status, array('all', 'pending', 'overdue', 'confirmed'), true)) {
		$status = 'all';
	}
	// Viết tắt tên cho avatar-initial (chữ đầu họ + chữ đầu tên).
	$initials = function ($name) {
		$name = trim($name);
		if ($name === '') {
			return '?';
		}
		$parts = preg_split('/\s+/', $name);
		$a = mb_substr($parts[0], 0, 1, 'UTF-8');
		$b = (count($parts) > 1) ? mb_substr($parts[count($parts) - 1], 0, 1, 'UTF-8') : '';
		return mb_strtoupper($a . $b, 'UTF-8');
	};
	$counts = $clsCustomerAssign->countByAssigner($profile_id);
	$rows = $clsCustomerAssign->byAssigner($profile_id, $status);
	$cutoff = time() - CustomerAssign::OVERDUE_SECONDS;
	$list = array();
	if (!empty($rows)) {
		foreach ($rows as $r) {
			$cid = (int) $r['customer_id'];
			$confirmed = (int) $r['confirmed_at'];
			$assigned_at = (int) $r['assigned_at'];
			$cname = trim($r['customer_name']);
			$rname = trim($r['recipient_name']);
			$cname = ($cname !== '' ? $cname : '#' . $cid);
			$rname = ($rname !== '' ? $rname : '#' . (int) $r['recipient_id']);
			// Trạng thái dòng: confirmed = đã nhận; overdue = chưa nhận & quá ngưỡng; pending = chưa nhận & trong hạn.
			if ($confirmed > 0) {
				$rstatus = 'confirmed';
			} else if ($assigned_at < $cutoff) {
				$rstatus = 'overdue';
			} else {
				$rstatus = 'pending';
			}
			$list[] = array(
				'customer_id'    => $cid,
				'customer_name'  => $cname,
				'customer_ini'   => $initials($cname),
				'phone_mask'     => (!empty($r['phone']) ? $clsCustomer->mask($r['phone'], true) : ''),
				'recipient_name' => $rname,
				'recipient_ini'  => $initials($rname),
				'assigned_ago'   => $clsISO->getTimeAgo($assigned_at),
				'status'         => $rstatus,
				'is_confirmed'   => ($confirmed > 0 ? 1 : 0),
				'can_act'        => ($confirmed > 0 ? 0 : 1),
				'confirmed_ago'  => ($confirmed > 0 ? $clsISO->getTimeAgo($confirmed) : ''),
				'remind_count'   => (int) $r['remind_count']
			);
		}
	}
	$smarty->assign('ma_status', $status);
	$smarty->assign('ma_total', (int) $counts['total']);
	$smarty->assign('ma_pending', (int) $counts['pending']);
	$smarty->assign('ma_confirmed', (int) $counts['confirmed']);
	$smarty->assign('ma_overdue', (int) $counts['overdue']);
	$smarty->assign('ma_rows', $list);
	$html = $core->build('_ajax.assigned.tpl');
	echo json_encode(array('html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_do_reassign_given(){
	// Phân lại số DO MÌNH GIAO (chỉ khi người nhận CHƯA xác nhận) → đổi chủ sang người nhận mới + thu phiếu chờ cũ + tạo phiếu chờ mới.
	global $profile_id, $oneProfile, $clsISO;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerAssign = new CustomerAssign();
	$customer_id = (int) Input::post('customer_id', 0);
	$new_admin_id = (int) Input::post('admin_id', 0);
	$note = trim(Input::post('note', ''));
	$notify_manager = (int) Input::post('notify_manager', 0);
	if ($customer_id <= 0 || $new_admin_id <= 0) {
		echo '_error';
		die();
	}
	// IDOR: phải có phiếu CHỜ do CHÍNH MÌNH giao trên khách này (chưa xác nhận).
	$pend = $clsCustomerAssign->findPendingByGiver($customer_id, $profile_id);
	if (empty($pend)) {
		echo '_error';
		die();
	}
	if ($new_admin_id == (int) $pend['recipient_id']) {
		echo '_success';
		die();
	}
	$oCustomer = $clsCustomer->getOne($customer_id, 'admin_id,user_id,status_id,more_information');
	if (empty($oCustomer)) {
		echo '_error';
		die();
	}
	$more_information = $clsISO->to_array_json($oCustomer['more_information']);
	$list_logs = (isset($more_information['logs']) && !empty($more_information['logs'])) ? $more_information['logs'] : array();
	$list_logs[$clsISO->getUniqid()] = array('_type' => 'assign', 'to_id' => $new_admin_id, 'from_id' => (int) $oCustomer['admin_id'], 'status_id' => $oCustomer['status_id'], 'note' => $note, 'reg_date' => time());
	$more_information['logs'] = $list_logs;
	// Người nhận cũ CHƯA xác nhận ⇒ MẤT quyền xem (khác do_change_assigned: chủ cũ ở đó là chủ thật nên giữ share). Giữ người giao làm share.
	$old_recipient = (int) $oCustomer['admin_id'];
	$list_share_ids = $clsCustomer->getShareIds($customer_id, $oCustomer);
	$list_share_ids[] = (int) $profile_id;
	$list_share_ids = $clsCustomer->normalizeIdArray($list_share_ids);
	$list_share_ids = array_values(array_diff($list_share_ids, array($old_recipient)));
	$use_globe = ($oCustomer['user_id'] == $new_admin_id) ? 0 : 1;
	if ($clsCustomer->updateOne($customer_id, array('upd_date' => time(), 'admin_id' => $new_admin_id, 'use_globe' => $use_globe, 'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)))) {
		$clsCustomer->syncShareIds($customer_id, $list_share_ids, $profile_id, false);
		$clsCustomerAssign->trashPendingByGiver($customer_id, $profile_id);
		$clsCustomerAssign->createPending($customer_id, $new_admin_id, $profile_id, 1);
		$clsNotify = new Notify();
		$cusName = $clsCustomer->getName($customer_id, $oCustomer);
		$giverName = $clsProfile->getFullName($profile_id, $oneProfile);
		$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', $giverName, $cusName);
		if ($note !== '') {
			$titleNoty .= sprintf(' — ghi chú: <i>%s</i>', $note);
		}
		$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(), "|" . $new_admin_id . "|");
		$clsNotification = new Notification();
		$clsNotification->doPushMessagingUser(array('title' => 'CRM - Khách hàng mới', 'body' => strip_tags($titleNoty), 'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)), array($new_admin_id));
		// Báo Quản lý nhóm (CRM supers) nếu được chọn.
		if ($notify_manager) {
			$mgrTitle = sprintf('<strong>%s</strong> đã phân lại khách <strong>%s</strong> cho <strong>%s</strong>.', $giverName, $cusName, $clsProfile->getFullName($new_admin_id));
			$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $mgrTitle, time(), _PROFILE_CRM_SUPER_ID);
			$clsNotification->doPushMessagingUser(array('title' => 'CRM - Phân lại khách', 'body' => strip_tags($mgrTitle), 'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)), _PROFILE_CRM_SUPER_ID);
		}
		echo '_success';
		die();
	}
	echo '_error';
	die();
}
function default_do_take_back_given(){
	// Thu hồi số mình giao (chỉ khi người nhận CHƯA xác nhận) → đổi chủ về CHÍNH MÌNH + thu phiếu chờ.
	global $profile_id, $clsISO;
	$clsCustomer = new Customer();
	$clsCustomerAssign = new CustomerAssign();
	$customer_id = (int) Input::post('customer_id', 0);
	if ($customer_id <= 0) {
		echo '_error';
		die();
	}
	$pend = $clsCustomerAssign->findPendingByGiver($customer_id, $profile_id);
	if (empty($pend)) {
		echo '_error';
		die();
	}
	$oCustomer = $clsCustomer->getOne($customer_id, 'admin_id,user_id,status_id,more_information');
	if (empty($oCustomer)) {
		echo '_error';
		die();
	}
	$more_information = $clsISO->to_array_json($oCustomer['more_information']);
	$list_logs = (isset($more_information['logs']) && !empty($more_information['logs'])) ? $more_information['logs'] : array();
	$list_logs[$clsISO->getUniqid()] = array('_type' => 'reclaim', 'to_id' => (int) $profile_id, 'from_id' => (int) $oCustomer['admin_id'], 'status_id' => $oCustomer['status_id'], 'reg_date' => time());
	$more_information['logs'] = $list_logs;
	// Người nhận (chưa xác nhận) MẤT quyền xem khi bị thu hồi.
	$old_recipient = (int) $pend['recipient_id'];
	$list_share_ids = $clsCustomer->getShareIds($customer_id, $oCustomer);
	$list_share_ids = $clsCustomer->normalizeIdArray($list_share_ids);
	$list_share_ids = array_values(array_diff($list_share_ids, array($old_recipient)));
	$use_globe = ($oCustomer['user_id'] == $profile_id) ? 0 : 1;
	if ($clsCustomer->updateOne($customer_id, array('upd_date' => time(), 'admin_id' => (int) $profile_id, 'use_globe' => $use_globe, 'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)))) {
		$clsCustomer->syncShareIds($customer_id, $list_share_ids, $profile_id, false);
		$clsCustomerAssign->trashPendingByGiver($customer_id, $profile_id);
		echo '_success';
		die();
	}
	echo '_error';
	die();
}
function default_do_remind_given(){
	// Nhắc người nhận xác nhận: bump remind_count + reminded_at, RỒI bắn thông báo (in-app + app push + Zalo) cho người nhận.
	global $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsCustomerAssign = new CustomerAssign();
	$clsProfile = new Profile();
	$customer_id = (int) Input::post('customer_id', 0);
	if ($customer_id <= 0) {
		echo json_encode(array('status' => '_error'));
		die();
	}
	// IDOR + lấy người nhận: chỉ phiếu CHỜ do chính mình giao (chưa xác nhận).
	$pend = $clsCustomerAssign->findPendingByGiver($customer_id, $profile_id);
	if (empty($pend)) {
		echo json_encode(array('status' => '_error'));
		die();
	}
	$recipient_id = (int) $pend['recipient_id'];
	$count = $clsCustomerAssign->markReminded($customer_id, $profile_id);
	if ($count <= 0) {
		echo json_encode(array('status' => '_error'));
		die();
	}
	// ----- Bắn thông báo cho người nhận -----
	$cusName = $clsCustomer->getName($customer_id);
	$giverName = $clsProfile->getFullName($profile_id, $oneProfile);
	$title = sprintf('<strong>%s</strong> nhắc bạn xác nhận đã nhận khách <strong>%s</strong>.', $giverName, $cusName);
	// In-app notify + app push tới người nhận (theo mẫu do_change_assigned).
	$clsNotify = new Notify();
	$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $title, time(), '|' . $recipient_id . '|');
	$clsNotification = new Notification();
	$clsNotification->doPushMessagingUser(array(
		'title' => 'CRM - Nhắc xác nhận khách',
		'body'  => strip_tags($title),
		'link'  => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
	), array($recipient_id));
	// Zalo tới người nhận — sendZaloUser tự lấy zaloId, tự bỏ qua nếu không có (theo mẫu cronjobs/crm.php). Bizflow API, try/catch sẵn.
	$zmsg = sprintf('🔔 %s nhắc bạn XÁC NHẬN đã nhận khách [%s].', $giverName, $cusName) . "\r"
		. 'Vào CRM bấm "Nhận" để bắt đầu chăm sóc: '.DOMAIN_URL.'/crm/';
	$clsZalo = new Zalo();
	$clsZalo->sendZaloUser($recipient_id, $zmsg);
	echo json_encode(array('status' => '_success', 'count' => $count));
	die();
}
function default_open_reassign_given(){
	// Popup "Phân lại số" — header (icon+khách) + cảnh báo người nhận hiện tại + chọn người mới + ghi chú + báo quản lý. Submit qua $Core.crm.reassign_given_submit.
	global $core, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsCustomerAssign = new CustomerAssign();
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::request('customer_id', 0);
	$oCus = $customer_id > 0 ? $clsCustomer->getOne($customer_id, 'name,phone') : array();
	$cusName = (!empty($oCus['name']) ? $oCus['name'] : '#' . $customer_id);
	$phoneMask = (!empty($oCus['phone']) ? $clsCustomer->mask($oCus['phone'], true) : '');
	// Cảnh báo: người nhận hiện tại chưa xác nhận bao lâu.
	$warn = '';
	$pend = $clsCustomerAssign->findPendingByGiver($customer_id, $profile_id);
	if (!empty($pend)) {
		$recName = $clsProfile->getFullName((int) $pend['recipient_id']);
		$elapsed = time() - (int) $pend['assigned_at'];
		$dur = $elapsed >= 86400 ? (floor($elapsed / 86400) . ' ngày') : (max(1, floor($elapsed / 3600)) . ' giờ');
		$warn = '<div class="crm-rs-warn"><i class="bx bx-error-circle"></i><span>' . htmlspecialchars($recName, ENT_QUOTES) . ' chưa xác nhận sau ' . $dur . '.</span></div>';
	}
	$html = '<div class="modal-dialog modal-sm modal-dialog-centered">
		<form class="modal-content crm-rs-modal" method="post">
			<div class="modal-header align-items-center gap-2">
				<span class="crm-rs-ic"><i class="bx bx-transfer-alt"></i></span>
				<div class="flex-grow-1 min-w-0">
					<h5 class="modal-title mb-0">Phân lại số</h5>
					<div class="text-muted text-truncate" style="font-size:12px">' . htmlspecialchars($cusName, ENT_QUOTES) . ($phoneMask ? ' · ' . $phoneMask : '') . '</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				' . $warn . '
				<label class="form-label mb-1 fw-semibold">Người nhận mới</label>
				<select class="iso-selectizeImageSearch required" name="admin_id" data-width="100%"
					data-placeholder="Chọn người nhận" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff&holderG=active"></select>
				<label class="form-label mb-1 mt-3 fw-semibold">Ghi chú (tuỳ chọn)</label>
				<textarea name="note" class="form-control" rows="2" placeholder="Nhờ em chăm giúp khách này…"></textarea>
				<label class="d-flex align-items-center gap-2 mt-3 mb-0" style="font-size:13px;cursor:pointer">
					<input type="checkbox" name="notify_manager" value="1" checked class="form-check-input mt-0"> Báo Quản lý nhóm
				</label>
			</div>
			<div class="modal-footer border-top pt-2">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
				<button type="button" class="btn btn-primary" onclick="$Core.crm.reassign_given_submit(this,event)" uid="' . $uid . '"
					customer_id="' . $customer_id . '">' . $core->makeIcon('check', 'Xác nhận phân lại') . '</button>
			</div>
		</form>
	</div>';
	echo json_encode(array('uid' => $uid, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function _crm_my_team_scope() {
	// Shared scope resolver cho default_my_team() shell + 6 box handlers.
	// Đọc period từ POST (AJAX handlers) rồi fallback GET (shell render GET request).
	// Trả về array đầy đủ gồm gate, scope, period, constants — handlers chỉ cần gọi hàm này.
	global $dbconn, $profile_id, $clsISO;
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomerHistory = new CustomerHistory();
	$can = (($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') || $clsISO->checkPermissionGroup('DIRECTOR') || $clsCustomer->isFullPermiss()) && !$clsCustomer->isMarketing()) ? 1 : 0;
	if (!$can) {
		return array('team_can' => 0);
	}
	$now = time();
	$pid = (int) $profile_id;
	$ctbl = $clsCustomer->tbl;
	$ftbl = $clsFollowUp->tbl;
	$htbl = $clsCustomerHistory->tbl;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$CALL = (int) _FOLLOWUP_CALL_ID;
	$ZALO = (int) _FOLLOWUP_ZALO_ID;
	$SALEMOC = (int) _CRM_RESOURCE_SALEMOC_ID;
	$dirtyCsv = "'0','1283'";
	$HOT = (int) _CRM_LEAD_SCORE_HOT;
	// Ưu tiên POST (AJAX), fallback GET (shell)
	$_pPeriod = trim((string) Input::post('period', ''));
	$period = $_pPeriod !== '' ? $_pPeriod : trim((string) Input::get('period', 'this_month'));
	if ($period === '') { $period = 'this_month'; }
	$_pFrom = trim((string) Input::post('from', ''));
	$_pTo = trim((string) Input::post('to', ''));
	$_from = $_pFrom !== '' ? $_pFrom : trim((string) Input::get('from', ''));
	$_to = $_pTo !== '' ? $_pTo : trim((string) Input::get('to', ''));
	$period_end = $now;
	$rangeFrom = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_from) ? strtotime($_from . ' 00:00:00') : 0;
	$rangeTo = preg_match('/^\d{4}-\d{2}-\d{2}$/', $_to) ? strtotime($_to . ' 23:59:59') : 0;
	if ($rangeFrom > 0 && $rangeTo >= $rangeFrom) {
		$period = 'custom';
		$period_start = $rangeFrom;
		$period_end = $rangeTo;
		$period_label = date('d/m/Y', $rangeFrom) . ' – ' . date('d/m/Y', $rangeTo);
	} else {
		switch ($period) {
			case 'today':
				$period_start = strtotime(date('Y-m-d 00:00:00'));
				$period_label = 'Hôm nay';
				break;
			case 'this_week':
				$period_start = strtotime('monday this week 00:00:00');
				$period_label = 'Tuần này';
				break;
			case 'last_month':
				$period_start = strtotime(date('Y-m-01 00:00:00', strtotime('first day of last month')));
				$period_end = strtotime(date('Y-m-t 23:59:59', strtotime('last day of last month')));
				$period_label = 'Tháng trước';
				break;
			case 'last_7_days':
				$period_start = $now - (7 * 86400);
				$period_label = '7 ngày qua';
				break;
			case 'last_30_days':
				$period_start = $now - (30 * 86400);
				$period_label = '30 ngày qua';
				break;
			case 'this_month':
			default:
				$period = 'this_month';
				$period_start = strtotime(date('Y-m-01 00:00:00'));
				$period_label = 'Tháng này';
				break;
		}
	}
	// Scope CÂY PHÒNG BAN. Tầng see-all (BGĐ) = full-permiss HOẶC DIRECTOR → gốc = node Kinh doanh; còn lại gốc = phòng ban của user.
	$_me = $clsProfile->getOne($pid, "department_id");
	$myDept = (int) (!empty($_me['department_id']) ? $_me['department_id'] : 0);
	$isSeeAll = ($clsCustomer->isFullPermiss() || $clsISO->checkPermissionGroup('DIRECTOR')) ? 1 : 0;
	$rootDept = $isSeeAll ? (int) _DEPARTMENT_SALE_ID : $myDept;
	if (!$isSeeAll && $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') && $rootDept == (int) _DEPARTMENT_SALE_ID) {
		$rootDept = 0; // GĐV không full-permiss mà phòng = node gốc → chặn (red-team F11)
	}
	$scopeDept = $rootDept;
	// Bộ lọc Vùng/Phòng KD (BGĐ chọn cả cây, GĐV chọn phòng con): narrow scope theo node được chọn.
	// IDOR-safe: KHÔNG tin client — validate node chọn là _DEPARTMENT thật VÀ nằm trong subtree gốc của user.
	$_pDeptSel = (int) Input::post('dept_sel', 0);
	$deptSel = $_pDeptSel > 0 ? $_pDeptSel : (int) Input::get('dept_sel', 0);
	$deptSelValid = 0;
	if ($deptSel > 0 && $rootDept > 0) {
		// IDOR-safe: deptSel chỉ hợp lệ khi LÀ gốc HOẶC con cháu của rootDept.
		// Bảng property KHÔNG có cột list_department_id — cây phòng ban encode qua parent_id; đi ngược parent_id lên,
		// gặp rootDept = hợp lệ. Walk bounded (~vài hop), rẻ; mỗi node lọc _DEPARTMENT + is_trash=0.
		$_okDept = 0;
		if ($deptSel === $rootDept) {
			$_okDept = 1;
		} else {
			$_cur = $deptSel;
			$_guard = 0;
			while ($_cur > 0 && $_guard < 12) {
				$_par = (int) $dbconn->GetOne("SELECT `parent_id` FROM `" . DB_PREFIX . "property` WHERE `property_id`='{$_cur}' AND `property_type`='_DEPARTMENT' AND `is_trash`=0");
				if ($_par <= 0) { break; }
				if ($_par === $rootDept) { $_okDept = 1; break; }
				$_cur = $_par;
				$_guard++;
			}
		}
		if ($_okDept > 0) { $scopeDept = $deptSel; $deptSelValid = $deptSel; }
	}
	$rep_ids = ($scopeDept > 0) ? $clsProfile->getSubordinateStaffIds($scopeDept) : array();
	$scope_empty = empty($rep_ids) ? 1 : 0;
	$repCsv = $scope_empty ? '' : implode(',', array_values($rep_ids));
	// Profile cache (dùng trong các box cần tên sale)
	$arr_rep = array();
	if (!$scope_empty) {
		// Tối ưu: lấy từ Redis-cache profile (getProfileCached 'active', 1h) thay vì query IN(repCsv) lặp mỗi box AJAX.
		// Cùng filter (is_trash=0 + status<>OFF) + đủ field (full_name/avatar/list_department_id/department_id/more_information).
		$_allProfiles = $clsProfile->getProfileCached('active');
		foreach ($rep_ids as $_rid) {
			$_rid = (int) $_rid;
			if (isset($_allProfiles[$_rid])) { $arr_rep[$_rid] = $_allProfiles[$_rid]; }
		}
	}
	$dept_name = ($scopeDept > 0) ? $dbconn->GetOne("SELECT `title` FROM `" . DB_PREFIX . "property` WHERE `property_id`='{$scopeDept}'") : '';
	return array(
		'team_can'     => 1,
		'scope_empty'  => $scope_empty,
		'rep_ids'      => $rep_ids,
		'repCsv'       => $repCsv,
		'arr_rep'      => $arr_rep,
		'scopeDept'    => $scopeDept,
		'rootDept'     => $rootDept,
		'is_see_all'   => $isSeeAll,
		'dept_sel'     => $deptSelValid,
		'period_start' => $period_start,
		'period_end'   => $period_end,
		'period_label' => $period_label,
		'period'       => $period,
		'from'         => ($rangeFrom > 0 ? date('Y-m-d', $rangeFrom) : ''),
		'to'           => ($rangeTo > 0 ? date('Y-m-d', $rangeTo) : ''),
		'now'          => $now,
		'now_text'     => date('H:i', $now),
		'total_reps'   => count($rep_ids),
		'dept_name'    => $dept_name,
		'ctbl'         => $ctbl,
		'ftbl'         => $ftbl,
		'htbl'         => $htbl,
		'DONE'         => $DONE,
		'CHOT'         => $CHOT,
		'TRASH'        => $TRASH,
		'CALL'         => $CALL,
		'ZALO'         => $ZALO,
		'SALEMOC'      => $SALEMOC,
		'dirtyCsv'     => $dirtyCsv,
		'HOT'          => $HOT,
	);
}
function default_my_team(){
	// /crm/team/ (act=my_team) — Shell render: gate + scope + period header only. Nội dung từng box load AJAX sau.
	global $smarty, $assign_list, $title_page, $core, $clsISO;
	$S = _crm_my_team_scope();
	$can = $S['team_can'];
	$assign_list['team_can'] = $can;
	$smarty->assign('team_can', $can);
	$title_page = 'Sức khỏe nhóm CRM | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
	if (!$can) {
		return;
	}
	$smarty->assign('mt_scope_empty', $S['scope_empty']);
	$smarty->assign('mt_dept_name', $S['dept_name']);
	$smarty->assign('mt_now_text', $S['now_text']);
	$smarty->assign('mt_period', $S['period']);
	$smarty->assign('mt_period_label', $S['period_label']);
	$smarty->assign('mt_total_reps', $S['total_reps']);
	$smarty->assign('mt_from', $S['from']);
	$smarty->assign('mt_to', $S['to']);
	// Bộ lọc Vùng/Phòng KD: BGĐ (see-all) thấy cả cây từ node Kinh doanh; GĐV thấy cây phòng ban vùng mình;
	// GĐKD (1 phòng, cây rỗng) → ẩn select. Cây dựng bằng buildTree như /bao-cao-phong.
	$mt_departments = array();
	$_showDept = ($S['is_see_all'] || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')) ? 1 : 0;
	if ($_showDept && (int) $S['rootDept'] > 0) {
		$clsProperty = new Property();
		$arr_dept_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
		$mt_departments = $clsISO->buildTree($arr_dept_cached, (int) $S['rootDept'], 'property_id');
	}
	$smarty->assign('mt_show_dept', (!empty($mt_departments)) ? 1 : 0);
	$smarty->assign('mt_departments', $mt_departments);
	$smarty->assign('mt_dept_sel', (int) $S['dept_sel']);
	$smarty->assign('mt_is_see_all', (int) $S['is_see_all']);
}
// ===== MyTeam AJAX box handlers (act=mt_overview / mt_lists / mt_donut / mt_urgent / mt_momentum / mt_feed) =====
// Mỗi handler: (1) gate qua _crm_my_team_scope(), (2) chạy đúng query của box mình, (3) trả JSON {error,html}.
// IDOR-safe: scope re-derived server-side trong _crm_my_team_scope() — KHÔNG tin client repCsv/admin_id.
// Helper dùng chung cho box overview (KPI) + box tables (2 bảng): per-rep aggregate + dept_performance.
function _crm_mt_initials($name){
	// Viết tắt tên (chữ đầu + chữ đầu của từ cuối) cho avatar-initial. Dùng chung box urgent/feed.
	$name = trim((string) $name);
	if ($name === '') { return '?'; }
	$parts = preg_split('/\s+/', $name);
	$a = mb_substr($parts[0], 0, 1, 'UTF-8');
	$b = (count($parts) > 1) ? mb_substr($parts[count($parts) - 1], 0, 1, 'UTF-8') : '';
	return mb_strtoupper($a . $b, 'UTF-8');
}
function _crm_mt_aggregate($S){
	global $clsISO, $dbconn;
	$clsProfile = new Profile();
	$repCsv = $S['repCsv'];
	$rep_ids = $S['rep_ids'];
	$arr_rep = $S['arr_rep'];
	$now = $S['now'];
	$ctbl = $S['ctbl']; $ftbl = $S['ftbl']; $htbl = $S['htbl'];
	$DONE = $S['DONE']; $CHOT = $S['CHOT']; $TRASH = $S['TRASH'];
	$CALL = $S['CALL']; $ZALO = $S['ZALO']; $SALEMOC = $S['SALEMOC'];
	$dirtyCsv = $S['dirtyCsv']; $HOT = $S['HOT'];
	$period_start = $S['period_start']; $period_end = $S['period_end'];
	$scopeDept = $S['scopeDept'];
	$rowsCus = $dbconn->GetAll("SELECT c.`admin_id`, SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) THEN 1 ELSE 0 END) AS active_cus, SUM(CASE WHEN c.`status_id`='{$CHOT}' THEN 1 ELSE 0 END) AS chot, SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0) THEN 1 ELSE 0 END) AS no_touch, SUM(CASE WHEN c.`reg_date` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS new_period FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`admin_id` IN ({$repCsv}) GROUP BY c.`admin_id`");
	$rowsFu = $dbconn->GetAll("SELECT f.`admin_id`, SUM(CASE WHEN f.`status_id`<>'{$DONE}' AND f.`date_id`>0 AND f.`date_id`<{$now} THEN 1 ELSE 0 END) AS overdue, MAX(CASE WHEN f.`date_id`>0 AND f.`date_id`<{$now} THEN f.`date_id` END) AS last_act FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id` AND c.`admin_id`=f.`admin_id` AND c.`is_trash`=0 AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}') AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id` IN ({$repCsv}) GROUP BY f.`admin_id`");
	$rowsAct = $dbconn->GetAll("SELECT f.`admin_id`, COUNT(*) AS act_total, SUM(CASE WHEN f.`type_id`='{$CALL}' THEN 1 ELSE 0 END) AS act_call, SUM(CASE WHEN f.`type_id`='{$ZALO}' THEN 1 ELSE 0 END) AS act_zalo FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id` AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`status_id`='{$DONE}' AND f.`date_id` BETWEEN {$period_start} AND {$period_end} AND f.`admin_id` IN ({$repCsv}) GROUP BY f.`admin_id`");
	$rowsChot = $dbconn->GetAll("SELECT c.`admin_id`, COUNT(DISTINCT h.`customer_id`) AS chot_period FROM `{$htbl}` h INNER JOIN `{$ctbl}` c ON c.`customer_id`=h.`customer_id` WHERE h.`to_status_id`='{$CHOT}' AND h.`action_date` BETWEEN {$period_start} AND {$period_end} AND c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`admin_id` IN ({$repCsv}) GROUP BY c.`admin_id`");
	$wmark = $now - (2 * 86400);
	$rowsTouch = $dbconn->GetAll("SELECT t.`admin_id`, SUM(CASE WHEN t.`lt` >= {$wmark} THEN 1 ELSE 0 END) AS dang_cham, SUM(CASE WHEN t.`lt` > 0 AND t.`lt` < {$wmark} THEN 1 ELSE 0 END) AS bo_quen, SUM(CASE WHEN t.`lt` = 0 AND t.`reg_date` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS chua_data FROM (SELECT c.`admin_id`, c.`reg_date`, GREATEST(COALESCE((SELECT MAX(f.`reg_date`) FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0),0), COALESCE((SELECT MAX(h.`action_date`) FROM `{$htbl}` h WHERE h.`customer_id`=c.`customer_id`),0)) AS lt FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) AND c.`admin_id` IN ({$repCsv})) t GROUP BY t.`admin_id`");
	$kpiTop = $dbconn->GetRow("SELECT SUM(CASE WHEN `status_id` NOT IN ('{$TRASH}',{$dirtyCsv}) THEN 1 ELSE 0 END) AS tong_lead, SUM(CASE WHEN `status_id`='{$CHOT}' THEN 1 ELSE 0 END) AS deal, SUM(CASE WHEN `status_id`='294' THEN 1 ELSE 0 END) AS quan_tam, SUM(CASE WHEN `status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) AND CAST(JSON_EXTRACT(`more_information`,'\$.lead_score') AS UNSIGNED) >= {$HOT} THEN 1 ELSE 0 END) AS hot_lead FROM `{$ctbl}` WHERE `is_trash`=0 AND IFNULL(`resource_id`,0) <> {$SALEMOC} AND `admin_id` IN ({$repCsv})");
	$cusMap = array();
	if (!empty($rowsCus)) { foreach ($rowsCus as $r) { $cusMap[(int)$r['admin_id']] = $r; } }
	$fuMap = array();
	if (!empty($rowsFu)) { foreach ($rowsFu as $r) { $fuMap[(int)$r['admin_id']] = $r; } }
	$actMap = array();
	if (!empty($rowsAct)) { foreach ($rowsAct as $r) { $actMap[(int)$r['admin_id']] = $r; } }
	$chotMap = array();
	if (!empty($rowsChot)) { foreach ($rowsChot as $r) { $chotMap[(int)$r['admin_id']] = $r; } }
	$touchMap = array();
	if (!empty($rowsTouch)) { foreach ($rowsTouch as $r) { $touchMap[(int)$r['admin_id']] = $r; } }
	$rows = array();
	$max_active = 1;
	$kpi = array('active' => 0, 'chot_period' => 0, 'act_total' => 0, 'overdue' => 0, 'new_period' => 0, 'chot_total' => 0, 'no_touch' => 0, 'conv_rate' => 0);
	foreach ($rep_ids as $rid) {
		$cu = isset($cusMap[$rid]) ? $cusMap[$rid] : null;
		$fu = isset($fuMap[$rid]) ? $fuMap[$rid] : null;
		$ac = isset($actMap[$rid]) ? $actMap[$rid] : null;
		$ch = isset($chotMap[$rid]) ? $chotMap[$rid] : null;
		$last_act = ($fu && !empty($fu['last_act'])) ? (int) $fu['last_act'] : 0;
		$oRep = isset($arr_rep[$rid]) ? $arr_rep[$rid] : null;
		$active = $cu ? (int) $cu['active_cus'] : 0;
		$chot_total = $cu ? (int) $cu['chot'] : 0;
		$chot_period = $ch ? (int) $ch['chot_period'] : 0;
		$act_total = $ac ? (int) $ac['act_total'] : 0;
		$new_period = $cu ? (int) $cu['new_period'] : 0;
		$overdue = $fu ? (int) $fu['overdue'] : 0;
		$no_touch = $cu ? (int) $cu['no_touch'] : 0;
		$tch = isset($touchMap[$rid]) ? $touchMap[$rid] : null;
		$dang_cham = $tch ? (int) $tch['dang_cham'] : 0;
		$bo_quen = $tch ? (int) $tch['bo_quen'] : 0;
		$chua_data = $tch ? (int) $tch['chua_data'] : 0;
		$denom = $active + $chot_total;
		$conv = $denom > 0 ? (int) round($chot_total / $denom * 100) : 0;
		$days_idle = ($last_act > 0) ? (int) max(0, floor(($now - $last_act) / 86400)) : -1;
		$untouch_ratio = $active > 0 ? (int) round($no_touch * 100 / $active) : 0;
		if ($days_idle >= 60 || ($active > 0 && $untouch_ratio >= 90)) {
			$health = 'Rủi ro'; $htone = 'danger';
		} elseif ($untouch_ratio >= 40 || ($active > 0 && $overdue >= $active * 0.4) || $days_idle >= 14) {
			$health = 'Cần đốc'; $htone = 'warning';
		} else {
			$health = 'Tốt'; $htone = 'success';
		}
		$ratio_color = $untouch_ratio > 60 ? 'danger' : ($untouch_ratio > 35 ? 'warning' : 'success');
		$nm = ($oRep && trim($oRep['full_name']) !== '') ? trim($oRep['full_name']) : '#' . $rid;
		$ini = '';
		$parts = array_slice(preg_split('/\s+/', trim($nm)), -2);
		foreach ($parts as $p) { if ($p !== '') { $ini .= mb_substr($p, 0, 1, 'UTF-8'); } }
		$ini = mb_strtoupper($ini, 'UTF-8');
		if ($active > $max_active) { $max_active = $active; }
		$rows[] = array(
			'rep_id' => $rid, 'rep_name' => $nm, 'initials' => $ini,
			'rep_avatar' => $clsProfile->getAvatar($rid, ($oRep ? $oRep : array())),
			'active' => $active, 'chot' => $chot_total, 'chot_period' => $chot_period,
			'act_total' => $act_total, 'act_call' => $ac ? (int)$ac['act_call'] : 0,
			'act_zalo' => $ac ? (int)$ac['act_zalo'] : 0, 'new_period' => $new_period,
			'conv_rate' => $conv, 'no_touch' => $no_touch, 'dang_cham' => $dang_cham,
			'bo_quen' => $bo_quen, 'chua_data' => $chua_data,
			'untouch_ratio' => $untouch_ratio, 'ratio_color' => $ratio_color,
			'overdue' => $overdue,
			'last_text' => ($last_act > 0 ? $clsISO->getTimeAgo($last_act) : 'Chưa có'),
			'days_idle' => $days_idle, 'health' => $health, 'htone' => $htone,
		);
		$kpi['active'] += $active; $kpi['chot_period'] += $chot_period; $kpi['act_total'] += $act_total;
		$kpi['overdue'] += $overdue; $kpi['new_period'] += $new_period; $kpi['chot_total'] += $chot_total;
		$kpi['no_touch'] += $no_touch;
		$kpi['dang_cham'] = (isset($kpi['dang_cham']) ? $kpi['dang_cham'] : 0) + $dang_cham;
		$kpi['bo_quen'] = (isset($kpi['bo_quen']) ? $kpi['bo_quen'] : 0) + $bo_quen;
		$kpi['chua_data'] = (isset($kpi['chua_data']) ? $kpi['chua_data'] : 0) + $chua_data;
	}
	$kpi_denom = $kpi['active'] + $kpi['chot_total'];
	$kpi['conv_rate'] = $kpi_denom > 0 ? (int) round($kpi['chot_total'] / $kpi_denom * 100) : 0;
	$kpi['tong_lead'] = $kpiTop ? (int) $kpiTop['tong_lead'] : 0;
	$kpi['deal'] = $kpiTop ? (int) $kpiTop['deal'] : 0;
	$kpi['quan_tam'] = $kpiTop ? (int) $kpiTop['quan_tam'] : 0;
	$kpi['hot_lead'] = $kpiTop ? (int) $kpiTop['hot_lead'] : 0;
	$kpi['deal_period'] = (int) $kpi['chot_period'];
	$period_len = $period_end - $period_start;
	$prev_end = $period_start - 1;
	$prev_start = $prev_end - $period_len;
	$deal_prev = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT h.`customer_id`) FROM `{$htbl}` h INNER JOIN `{$ctbl}` c ON c.`customer_id`=h.`customer_id` WHERE h.`to_status_id`='{$CHOT}' AND h.`action_date` BETWEEN {$prev_start} AND {$prev_end} AND c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`admin_id` IN ({$repCsv})");
	$kpi['deal_prev'] = $deal_prev;
	$kpi['deal_wow_show'] = $deal_prev > 0 ? 1 : 0;
	$kpi['deal_wow'] = $deal_prev > 0 ? (int) round(($kpi['deal_period'] - $deal_prev) / $deal_prev * 100) : 0;
	usort($rows, function($a, $b) {
		if ($b['overdue'] != $a['overdue']) { return $b['overdue'] - $a['overdue']; }
		return $b['no_touch'] - $a['no_touch'];
	});
	// Dept performance (giống logic gốc)
	$dept_performance = array();
	foreach ($rows as $r) {
		$rep_dept_id = 9999;
		if (isset($arr_rep[$r['rep_id']])) {
			$p_info = $arr_rep[$r['rep_id']];
			$list_dept = isset($p_info['list_department_id']) ? $p_info['list_department_id'] : '';
			$direct_dept = isset($p_info['department_id']) ? (int)$p_info['department_id'] : 0;
			if (!empty($list_dept)) {
				$parts = array_filter(explode('|', $list_dept));
				$parts = array_values($parts);
				$idx = array_search((string)$scopeDept, $parts);
				if ($idx !== false) {
					if ($scopeDept == 40) {
						if (isset($parts[$idx + 2])) { $rep_dept_id = (int)$parts[$idx + 2]; }
						elseif (isset($parts[$idx + 1])) { $rep_dept_id = (int)$parts[$idx + 1]; }
					} else {
						if (isset($parts[$idx + 1])) { $rep_dept_id = (int)$parts[$idx + 1]; }
						else { $rep_dept_id = $direct_dept > 0 ? $direct_dept : 9999; }
					}
				} else {
					$rep_dept_id = $direct_dept > 0 ? $direct_dept : 9999;
				}
			} else {
				$rep_dept_id = $direct_dept > 0 ? $direct_dept : 9999;
			}
		}
		if (!isset($dept_performance[$rep_dept_id])) {
			$dept_performance[$rep_dept_id] = array(
				'dept_id' => $rep_dept_id, 'dept_name' => '', 'manager_name' => '',
				'active' => 0, 'dang_cham' => 0, 'bo_quen' => 0, 'chot_period' => 0,
				'chot_total' => 0, 'no_touch' => 0, 'new_period' => 0, 'rep_count' => 0,
			);
		}
		$dept_performance[$rep_dept_id]['active'] += $r['active'];
		$dept_performance[$rep_dept_id]['dang_cham'] += $r['dang_cham'];
		$dept_performance[$rep_dept_id]['bo_quen'] += $r['bo_quen'];
		$dept_performance[$rep_dept_id]['chot_period'] += $r['chot_period'];
		$dept_performance[$rep_dept_id]['chot_total'] += $r['chot'];
		$dept_performance[$rep_dept_id]['no_touch'] += $r['no_touch'];
		$dept_performance[$rep_dept_id]['new_period'] += $r['new_period'];
		$dept_performance[$rep_dept_id]['rep_count']++;
	}
	foreach ($dept_performance as $did => $dp) {
		if ($did == 9999) {
			$dept_performance[$did]['dept_name'] = 'Trực thuộc Vùng / Khác';
			$dept_performance[$did]['manager_name'] = '--';
		} else {
			$dept_name = $dbconn->GetOne("SELECT `title` FROM `".DB_PREFIX."property` WHERE `property_id`='{$did}'");
			$dept_performance[$did]['dept_name'] = !empty($dept_name) ? $dept_name : 'Phòng #' . $did;
			$dept_performance[$did]['manager_name'] = $dbconn->GetOne("SELECT t1.`full_name` FROM `".DB_PREFIX."profile` t1 INNER JOIN `".DB_PREFIX."property` t2 ON t1.`role_id`=t2.`property_id` WHERE t1.`department_id`='{$did}' AND t1.`is_trash`=0 AND t1.`status_id`<>'56' AND (t2.`title` LIKE '%Trưởng phòng%' OR t2.`title` LIKE '%Leader%' OR t2.`title` LIKE '%Quản lý%') LIMIT 1") ?: '--';
		}
		$denom = $dp['active'] + $dp['chot_total'];
		$dept_performance[$did]['conv_rate'] = $denom > 0 ? (int) round($dp['chot_total'] / $denom * 100) : 0;
	}
	return array('kpi' => $kpi, 'rows' => $rows, 'dept_performance' => $dept_performance);
}
// Box overview: 4 KPI cards + 4 action cards + banner bỏ quên.
function default_mt_overview(){
	global $smarty, $core;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$A = _crm_mt_aggregate($S);
	$smarty->assign('mt_kpi', $A['kpi']);
	$smarty->assign('mt_dept_name', $S['dept_name']);
	$smarty->assign('mt_now_text', $S['now_text']);
	$html = $core->build('_ajax.mt_overview.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
// Box tables: bảng hiệu suất theo Phòng + theo nhân viên (block riêng).
function default_mt_tables(){
	global $smarty, $core;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$A = _crm_mt_aggregate($S);
	$smarty->assign('mt_rows', $A['rows']);
	$smarty->assign('mt_dept_performance', $A['dept_performance']);
	$html = $core->build('_ajax.mt_tables.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_mt_lists(){
	// Box: Khách mới / Chưa gọi / Quan tâm chưa chốt (Q1/Q2/Q4).
	global $smarty, $core, $dbconn;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$repCsv = $S['repCsv'];
	$arr_rep = $S['arr_rep'];
	$now = $S['now'];
	$ctbl = $S['ctbl']; $ftbl = $S['ftbl']; $htbl = $S['htbl'];
	$CHOT = $S['CHOT']; $TRASH = $S['TRASH']; $SALEMOC = $S['SALEMOC'];
	$dirtyCsv = $S['dirtyCsv'];
	$period_start = $S['period_start']; $period_end = $S['period_end'];
	$initials = function ($name) {
		$name = trim($name);
		if ($name === '') { return '?'; }
		$parts = preg_split('/\s+/', $name);
		$a = mb_substr($parts[0], 0, 1, 'UTF-8');
		$b = (count($parts) > 1) ? mb_substr($parts[count($parts) - 1], 0, 1, 'UTF-8') : '';
		return mb_strtoupper($a . $b, 'UTF-8');
	};
	// Q1 — Khách mới trong kỳ
	$mt_new_list = array();
	$q1Rows = $dbconn->GetAll("SELECT c.`customer_id`, c.`name`, c.`phone`, c.`admin_id`, c.`reg_date` FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`reg_date` BETWEEN {$period_start} AND {$period_end} AND c.`admin_id` IN ({$repCsv}) ORDER BY c.`reg_date` DESC LIMIT 5");
	foreach ((array)$q1Rows as $r) {
		$aid = (int)$r['admin_id'];
		$sale = (isset($arr_rep[$aid]) && trim($arr_rep[$aid]['full_name']) !== '') ? trim($arr_rep[$aid]['full_name']) : '#' . $aid;
		$rt = (int)$r['reg_date'];
		$mt_new_list[] = array('customer_id' => (int)$r['customer_id'], 'name' => (trim((string)$r['name']) !== '' ? $r['name'] : 'Khách #' . $r['customer_id']), 'sale' => $sale, 'initials' => $initials((string)$r['name']), 'phone' => $r['phone'], 'pill' => (date('Y-m-d', $rt) == date('Y-m-d', $now) ? date('H:i', $rt) : date('d/m', $rt)), 'tone' => 'warning');
	}
	// Q2 — Khách chưa gọi, đang active
	$mt_uncalled_list = array();
	$q2Rows = $dbconn->GetAll("SELECT c.`customer_id`, c.`name`, c.`phone`, c.`admin_id`, c.`reg_date` FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) AND c.`reg_date` BETWEEN {$period_start} AND {$period_end} AND c.`admin_id` IN ({$repCsv}) AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0) ORDER BY c.`reg_date` ASC LIMIT 5");
	foreach ((array)$q2Rows as $r) {
		$aid = (int)$r['admin_id'];
		$sale = (isset($arr_rep[$aid]) && trim($arr_rep[$aid]['full_name']) !== '') ? trim($arr_rep[$aid]['full_name']) : '#' . $aid;
		$rt = (int)$r['reg_date'];
		$d = $rt > 0 ? (int)floor(($now - $rt) / 86400) : 0;
		$mt_uncalled_list[] = array('customer_id' => (int)$r['customer_id'], 'name' => (trim((string)$r['name']) !== '' ? $r['name'] : 'Khách #' . $r['customer_id']), 'sale' => $sale, 'initials' => $initials((string)$r['name']), 'phone' => $r['phone'], 'pill' => ($d > 0 ? $d . 'd' : 'mới'), 'tone' => ($d >= 2 ? 'danger' : 'warning'));
	}
	// Q4 — Quan tâm (294) chưa chốt
	$mt_interested_list = array();
	$q4Rows = $dbconn->GetAll("SELECT c.`customer_id`, c.`name`, c.`phone`, c.`admin_id`, GREATEST(COALESCE((SELECT MAX(f.`reg_date`) FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0),0), COALESCE((SELECT MAX(h.`action_date`) FROM `{$htbl}` h WHERE h.`customer_id`=c.`customer_id`),0)) AS lt FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`status_id`='294' AND c.`admin_id` IN ({$repCsv}) ORDER BY lt ASC LIMIT 5");
	foreach ((array)$q4Rows as $r) {
		$aid = (int)$r['admin_id'];
		$sale = (isset($arr_rep[$aid]) && trim($arr_rep[$aid]['full_name']) !== '') ? trim($arr_rep[$aid]['full_name']) : '#' . $aid;
		$lt = (int)$r['lt'];
		$d = $lt > 0 ? (int)floor(($now - $lt) / 86400) : -1;
		$stuck_date = (int)$dbconn->GetOne("SELECT MAX(`action_date`) FROM `{$htbl}` WHERE `customer_id`='{$r['customer_id']}' AND `to_status_id`='294'");
		if ($stuck_date > 0 && ($now - $stuck_date) > 10 * 86400) {
			$is_stuck = 1; $stuck_days = (int)floor(($now - $stuck_date) / 86400);
		} else {
			$is_stuck = 0; $stuck_days = 0;
		}
		$mt_interested_list[] = array('customer_id' => (int)$r['customer_id'], 'name' => (trim((string)$r['name']) !== '' ? $r['name'] : 'Khách #' . $r['customer_id']), 'sale' => $sale, 'initials' => $initials((string)$r['name']), 'phone' => $r['phone'], 'pill' => ($d < 0 ? 'chưa' : $d . 'd'), 'tone' => (($d < 0 || $d >= 2) ? 'danger' : 'warning'), 'is_stuck' => $is_stuck, 'stuck_days' => $stuck_days);
	}
	// Đếm số lượng cho header (new_period/chua_data/quan_tam từ aggregate query nhỏ)
	$counts = $dbconn->GetRow("SELECT SUM(CASE WHEN c.`reg_date` BETWEEN {$period_start} AND {$period_end} THEN 1 ELSE 0 END) AS new_period, SUM(CASE WHEN c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) AND c.`reg_date` BETWEEN {$period_start} AND {$period_end} AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0) THEN 1 ELSE 0 END) AS chua_data, SUM(CASE WHEN c.`status_id`='294' THEN 1 ELSE 0 END) AS quan_tam FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`admin_id` IN ({$repCsv})");
	$kpi_counts = array(
		'new_period' => $counts ? (int)$counts['new_period'] : 0,
		'chua_data'  => $counts ? (int)$counts['chua_data'] : 0,
		'quan_tam'   => $counts ? (int)$counts['quan_tam'] : 0,
	);
	$smarty->assign('mt_new_list', $mt_new_list);
	$smarty->assign('mt_uncalled_list', $mt_uncalled_list);
	$smarty->assign('mt_interested_list', $mt_interested_list);
	$smarty->assign('mt_kpi', $kpi_counts);
	$html = $core->build('_ajax.mt_lists.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_mt_donut(){
	// Box: Donut phân bố trạng thái.
	global $smarty, $core, $dbconn;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$repCsv = $S['repCsv'];
	$ctbl = $S['ctbl']; $TRASH = $S['TRASH']; $SALEMOC = $S['SALEMOC']; $dirtyCsv = $S['dirtyCsv'];
	$donutMap = array(291 => array('Mới', 'var(--ld-brand)'), 293 => array('Đang chăm', 'var(--ld-success)'), 294 => array('Quan tâm', 'var(--ld-warning)'), 295 => array('Đã chốt', 'var(--ld-info)'));
	$donutCnt = array(291 => 0, 293 => 0, 294 => 0, 295 => 0);
	$donutOther = 0;
	$dRows = $dbconn->GetAll("SELECT `status_id`, COUNT(*) AS n FROM `{$ctbl}` WHERE `is_trash`=0 AND IFNULL(`resource_id`,0) <> {$SALEMOC} AND `status_id` NOT IN ('{$TRASH}',{$dirtyCsv}) AND `admin_id` IN ({$repCsv}) GROUP BY `status_id`");
	foreach ((array)$dRows as $dr) {
		$dsid = (int)$dr['status_id'];
		if (isset($donutCnt[$dsid])) { $donutCnt[$dsid] = (int)$dr['n']; } else { $donutOther += (int)$dr['n']; }
	}
	$donutTotal = array_sum($donutCnt) + $donutOther;
	$donut = array();
	foreach ($donutMap as $dsid => $dm) {
		if ($donutCnt[$dsid] > 0) { $donut[] = array('label' => $dm[0], 'color' => $dm[1], 'count' => $donutCnt[$dsid], 'pct' => ($donutTotal > 0 ? round($donutCnt[$dsid] / $donutTotal * 100, 2) : 0)); }
	}
	if ($donutOther > 0) { $donut[] = array('label' => 'Khác', 'color' => 'var(--ld-ink4)', 'count' => $donutOther, 'pct' => ($donutTotal > 0 ? round($donutOther / $donutTotal * 100, 2) : 0)); }
	$grad = ''; $gacc = 0; $dn = count($donut);
	foreach ($donut as $di => $d) {
		$gstart = $gacc; $gacc += $d['pct'];
		$gend = ($di == $dn - 1) ? 100 : $gacc;
		$grad .= ($grad === '' ? '' : ', ') . $d['color'] . ' ' . $gstart . '% ' . $gend . '%';
	}
	$smarty->assign('mt_donut', $donut);
	$smarty->assign('mt_donut_total', $donutTotal);
	$smarty->assign('mt_donut_grad', $grad !== '' ? $grad : 'var(--ld-line) 0 100%');
	$html = $core->build('_ajax.mt_donut.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_mt_urgent(){
	// Box: Khách cần xử lý ngay (active quá hạn last_touch > 2 ngày).
	global $smarty, $core, $clsISO, $dbconn;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$repCsv = $S['repCsv'];
	$arr_rep = $S['arr_rep'];
	$now = $S['now'];
	$ctbl = $S['ctbl']; $ftbl = $S['ftbl']; $htbl = $S['htbl'];
	$CHOT = $S['CHOT']; $TRASH = $S['TRASH']; $SALEMOC = $S['SALEMOC']; $dirtyCsv = $S['dirtyCsv'];
	$wmark = $now - (2 * 86400);
	$urgent = array();
	$urgentRows = $dbconn->GetAll("SELECT c.`customer_id`, c.`name`, c.`phone`, c.`admin_id`, GREATEST(COALESCE((SELECT MAX(f.`reg_date`) FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0),0), COALESCE((SELECT MAX(h.`action_date`) FROM `{$htbl}` h WHERE h.`customer_id`=c.`customer_id`),0)) AS lt FROM `{$ctbl}` c WHERE c.`is_trash`=0 AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} AND c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirtyCsv}) AND c.`admin_id` IN ({$repCsv}) HAVING lt > 0 AND lt < {$wmark} ORDER BY lt ASC LIMIT 8");
	if (!empty($urgentRows)) {
		foreach ($urgentRows as $u) {
			$urid = (int)$u['admin_id'];
			$usale = (isset($arr_rep[$urid]) && trim($arr_rep[$urid]['full_name']) !== '') ? trim($arr_rep[$urid]['full_name']) : '#' . $urid;
			$udays = (int)floor(($now - (int)$u['lt']) / 86400);
			$urgent[] = array('initials' => _crm_mt_initials($u['name']), 'customer_id' => (int)$u['customer_id'], 'name' => (trim((string)$u['name']) !== '' ? $u['name'] : 'Khách #' . $u['customer_id']), 'phone' => $u['phone'], 'sale' => $usale, 'days' => $udays, 'tone' => $udays >= 7 ? 'danger' : 'warning');
		}
	}
	$smarty->assign('mt_urgent', $urgent);
	$html = $core->build('_ajax.mt_urgent.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_mt_momentum(){
	// Box: Momentum chuyển trạng thái kỳ.
	global $smarty, $core, $dbconn;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$repCsv = $S['repCsv'];
	$htbl = $S['htbl']; $ctbl = $S['ctbl']; $SALEMOC = $S['SALEMOC'];
	$period_start = $S['period_start']; $period_end = $S['period_end'];
	$stName = array(291 => 'Chưa tư vấn', 293 => 'Đang chăm', 294 => 'Quan tâm', 295 => 'Đã chốt', 363 => 'Không TC');
	$stStyle = array(291 => 'background:var(--ld-paper2);color:var(--ld-ink2)', 293 => 'background:var(--ld-success-soft);color:var(--ld-success-ink)', 294 => 'background:var(--ld-info-soft);color:var(--ld-info-ink)', 295 => 'background:var(--ld-warning-soft);color:var(--ld-warning-ink)', 363 => 'background:var(--ld-danger-soft);color:var(--ld-danger-ink)');
	$stInk = array(291 => 'var(--ld-ink2)', 293 => 'var(--ld-success-ink)', 294 => 'var(--ld-info-ink)', 295 => 'var(--ld-warning-ink)', 363 => 'var(--ld-danger-ink)');
	$momentum = array();
	$mRows = $dbconn->GetAll("SELECT h.`from_status_id`, h.`to_status_id`, COUNT(DISTINCT h.`customer_id`) AS n FROM `{$htbl}` h INNER JOIN `{$ctbl}` c ON c.`customer_id`=h.`customer_id` AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} WHERE h.`action_date` BETWEEN {$period_start} AND {$period_end} AND h.`staff_id` IN ({$repCsv}) AND h.`from_status_id` IN (291,293,294,295) AND h.`to_status_id` IN (293,294,295,363) AND h.`from_status_id`<>h.`to_status_id` GROUP BY h.`from_status_id`, h.`to_status_id` ORDER BY n DESC LIMIT 6");
	foreach ((array)$mRows as $mr) {
		$mf = (int)$mr['from_status_id']; $mtt = (int)$mr['to_status_id'];
		if (!isset($stName[$mf]) || !isset($stName[$mtt])) { continue; }
		$momentum[] = array('from' => $stName[$mf], 'from_style' => $stStyle[$mf], 'from_id' => $mf, 'to' => $stName[$mtt], 'to_style' => $stStyle[$mtt], 'to_ink' => $stInk[$mtt], 'to_id' => $mtt, 'n' => (int)$mr['n']);
	}
	$smarty->assign('mt_momentum', $momentum);
	$html = $core->build('_ajax.mt_momentum.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_mt_feed(){
	// Box: Feed hoạt động gần nhất của phòng.
	global $smarty, $core, $dbconn;
	$S = _crm_my_team_scope();
	if (!$S['team_can']) { echo json_encode(array('error' => 1, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	if ($S['scope_empty']) { echo json_encode(array('error' => 0, 'html' => ''), JSON_UNESCAPED_UNICODE); die(); }
	$repCsv = $S['repCsv'];
	$arr_rep = $S['arr_rep'];
	$now = $S['now'];
	$ctbl = $S['ctbl']; $ftbl = $S['ftbl']; $SALEMOC = $S['SALEMOC'];
	$CALL = $S['CALL']; $ZALO = $S['ZALO']; $DONE = $S['DONE'];
	$fTypeName = array((int)_FOLLOWUP_CALL_ID => 'Gọi điện', (int)_FOLLOWUP_ZALO_ID => 'Zalo', 342 => 'Gặp trực tiếp');
	$fBadge = array((int)_FOLLOWUP_STATUS_DONE_ID => array('Đã thực hiện', 'success'), 297 => array('Lên kế hoạch', 'info'), 302 => array('Quá hạn', 'danger'));
	$feed = array();
	$fRows = $dbconn->GetAll("SELECT f.`reg_date`, f.`type_id`, f.`status_id`, f.`admin_id`, f.`customer_id`, c.`name` AS cus_name FROM `{$ftbl}` f INNER JOIN `{$ctbl}` c ON c.`customer_id`=f.`customer_id` AND IFNULL(c.`resource_id`,0) <> {$SALEMOC} WHERE f.`followup_type`='_crm' AND f.`is_trash`=0 AND f.`admin_id` IN ({$repCsv}) ORDER BY f.`reg_date` DESC LIMIT 8");
	foreach ((array)$fRows as $fr) {
		$faid = (int)$fr['admin_id']; $ftid = (int)$fr['type_id']; $fsid = (int)$fr['status_id'];
		$fsale = (isset($arr_rep[$faid]) && trim($arr_rep[$faid]['full_name']) !== '') ? trim($arr_rep[$faid]['full_name']) : '#' . $faid;
		$fb = isset($fBadge[$fsid]) ? $fBadge[$fsid] : array('Tác nghiệp', 'secondary');
		$ftime = (int)$fr['reg_date'];
		$feed[] = array('initials' => _crm_mt_initials($fsale), 'customer_id' => (int)$fr['customer_id'], 'time' => (date('Y-m-d', $ftime) == date('Y-m-d', $now) ? date('H:i', $ftime) : date('d/m', $ftime)), 'sale' => $fsale, 'type' => (isset($fTypeName[$ftid]) ? $fTypeName[$ftid] : 'Tác nghiệp'), 'cus' => (trim((string)$fr['cus_name']) !== '' ? $fr['cus_name'] : 'Khách'), 'badge' => $fb[0], 'tone' => $fb[1]);
	}
	$smarty->assign('mt_feed', $feed);
	$html = $core->build('_ajax.mt_feed.tpl');
	echo json_encode(array('error' => 0, 'html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_mt_drill(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	// Drill-down: click 1 con số tổng ở dashboard giám sát → modal liệt kê khách theo ĐÚNG điều kiện lọc của ô đó.
	// IDOR-safe: scope tái dựng server-side qua _crm_my_team_scope(); rep_id/dept_id chỉ nhận khi nằm TRONG scope.
	// Đếm + danh sách dùng chung 1 mệnh đề WHERE (giống điều kiện ô gốc) nên số liệu modal khớp badge.
	global $smarty, $core, $clsISO, $dbconn;
	$S = _crm_my_team_scope();
	$uid = $clsISO->getUniqid();
	if (!$S['team_can']) { 
		echo json_encode(array(
			'error' => 1
		), JSON_UNESCAPED_UNICODE); die();
	 }
	$metric = trim((string) Input::post('metric', ''));
	$from_status = (int) Input::post('from_status', 0);
	$to_status = (int) Input::post('to_status', 0);
	$rep_id = (int) Input::post('rep_id', 0);
	$dept_id = (int) Input::post('dept_id', 0);
	$page = (int) Input::post('page', 1);
	if ($page < 1) { $page = 1; }
	$more = (int) Input::post('more', 0);
	$perPage = 25;
	// ----- Resolve repFilter (IDOR: rep_id/dept_id phải thuộc scope) -----
	$idSet = array();
	foreach ((array) $S['rep_ids'] as $_r) { $idSet[(int) $_r] = (int) $_r; }
	$repName = $deptName = '';
	$useIds = $idSet;
	if ($rep_id > 0) {
		$useIds = isset($idSet[$rep_id]) ? array($rep_id => $rep_id) : array();
		$repName = (isset($S['arr_rep'][$rep_id]['full_name']) && trim($S['arr_rep'][$rep_id]['full_name']) !== '') ? trim($S['arr_rep'][$rep_id]['full_name']) : '#' . $rep_id;
	} elseif ($dept_id > 0) {
		$clsProfile = new Profile();
		$deptReps = $clsProfile->getSubordinateStaffIds($dept_id);
		$useIds = array();
		foreach ((array) $deptReps as $_r) { $_r = (int) $_r; if (isset($idSet[$_r])) { $useIds[$_r] = $_r; } }
		$deptName = (string) $dbconn->GetOne("SELECT `title` FROM `" . DB_PREFIX . "property` WHERE `property_id`='" . (int) $dept_id . "'");
	}
	$repCsv = empty($useIds) ? '' : implode(',', array_values($useIds));
	// ----- Build WHERE theo metric (khớp điều kiện ô gốc) -----
	$ps = (int) $S['period_start']; $pe = (int) $S['period_end']; $now = (int) $S['now']; $wmark = $now - (2 * 86400);
	$ctbl = $S['ctbl']; $ftbl = $S['ftbl']; $htbl = $S['htbl'];
	$CHOT = (int) $S['CHOT']; $TRASH = (int) $S['TRASH']; $SALEMOC = (int) $S['SALEMOC']; $dirty = $S['dirtyCsv']; $HOT = (int) $S['HOT'];
	$activeCond = "c.`status_id` NOT IN ('{$CHOT}','{$TRASH}',{$dirty})";
	$ltExpr = "GREATEST(COALESCE((SELECT MAX(f.`reg_date`) FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0),0), COALESCE((SELECT MAX(h.`action_date`) FROM `{$htbl}` h WHERE h.`customer_id`=c.`customer_id`),0))";
	$noFu = "NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0)";
	$base = "c.`is_trash`=0 AND IFNULL(c.`resource_id`,0)<>{$SALEMOC} AND c.`admin_id` IN ({$repCsv})";
	$order = "c.`reg_date` DESC";
	$title = 'Danh sách khách';
	switch ($metric) {
		case 'active': $where = "{$base} AND {$activeCond}"; $title = 'Khách đang active'; break;
		case 'dang_cham': $where = "{$base} AND {$activeCond} AND ({$ltExpr}) >= {$wmark}"; $title = 'Khách đang chăm sóc'; break;
		case 'bo_quen': $where = "{$base} AND {$activeCond} AND ({$ltExpr}) > 0 AND ({$ltExpr}) < {$wmark}"; $order = "({$ltExpr}) ASC"; $title = 'Khách bị bỏ quên'; break;
		case 'chua_data': $where = "{$base} AND {$activeCond} AND c.`reg_date` BETWEEN {$ps} AND {$pe} AND {$noFu}"; $order = "c.`reg_date` ASC"; $title = 'Khách chưa tương tác'; break;
		case 'new_period': $where = "{$base} AND c.`reg_date` BETWEEN {$ps} AND {$pe}"; $title = 'Khách mới trong kỳ'; break;
		case 'quan_tam': $where = "{$base} AND c.`status_id`='294'"; $title = 'Khách quan tâm chưa chốt'; break;
		case 'hot': $where = "{$base} AND {$activeCond} AND CAST(JSON_EXTRACT(c.`more_information`,'$.lead_score') AS UNSIGNED) >= {$HOT}"; $title = 'Lead ưu tiên (Hot)'; break;
		case 'deal_period': $where = "{$base} AND c.`customer_id` IN (SELECT h.`customer_id` FROM `{$htbl}` h WHERE h.`to_status_id`='{$CHOT}' AND h.`action_date` BETWEEN {$ps} AND {$pe})"; $title = 'Deal chốt trong kỳ'; break;
		case 'momentum':
			if ($from_status <= 0 || $to_status <= 0) { $where = "1=0"; }
			else { $where = "IFNULL(c.`resource_id`,0)<>{$SALEMOC} AND c.`customer_id` IN (SELECT h.`customer_id` FROM `{$htbl}` h WHERE h.`from_status_id`='{$from_status}' AND h.`to_status_id`='{$to_status}' AND h.`from_status_id`<>h.`to_status_id` AND h.`action_date` BETWEEN {$ps} AND {$pe} AND h.`staff_id` IN ({$repCsv}))"; }
			$title = 'Khách chuyển trạng thái trong kỳ';
			break;
		default: $where = "1=0"; break;
	}
	if ($repCsv === '') { $where = "1=0"; }
	// ----- Đếm + lấy trang -----
	$total = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$ctbl}` c WHERE {$where}");
	$offset = ($page - 1) * $perPage;
	$rows = ($total > 0) ? $dbconn->GetAll("SELECT c.`customer_id`, c.`name`, c.`phone`, c.`admin_id` FROM `{$ctbl}` c WHERE {$where} ORDER BY {$order} LIMIT {$perPage} OFFSET {$offset}") : array();
	$arr_rep = $S['arr_rep'];
	$drill_rows = array();
	foreach ((array) $rows as $r) {
		$aid = (int) $r['admin_id'];
		$sale = (isset($arr_rep[$aid]['full_name']) && trim($arr_rep[$aid]['full_name']) !== '') ? trim($arr_rep[$aid]['full_name']) : '#' . $aid;
		$nm = (trim((string) $r['name']) !== '') ? $r['name'] : 'Khách #' . $r['customer_id'];
		$drill_rows[] = array('customer_id' => (int) $r['customer_id'], 'name' => $nm, 'sale' => $sale, 'initials' => _crm_mt_initials($nm), 'phone' => $r['phone']);
	}
	$has_more = (($offset + count($drill_rows)) < $total) ? 1 : 0;
	$suffix = ($repName !== '') ? ' · ' . $repName : (($deptName !== '') ? ' · ' . $deptName : '');
	$smarty->assign('drill_rows', $drill_rows);
	if ($more) {
		$rowsHtml = $core->build('_ajax.mt_drill_rows.tpl');
		echo json_encode(array(
			'error' => 0, 
			'html' => $rowsHtml, 
			'has_more' => $has_more, 
			'page' => $page
		), JSON_UNESCAPED_UNICODE); die();
	}
	$smarty->assign('drill_title', $title . $suffix);
	$smarty->assign('drill_total', $total);
	$smarty->assign('drill_has_more', $has_more);
	$smarty->assign('drill_metric', $metric);
	$smarty->assign('drill_from', $from_status);
	$smarty->assign('drill_to', $to_status);
	$smarty->assign('drill_rep', $rep_id);
	$smarty->assign('drill_dept', $dept_id);
	$smarty->assign('drill_next', $page + 1);
	
	$smarty->assign('uid', $uid);
	// Return
	$smarty->assign('drill_rows_html', $core->build('_ajax.mt_drill_rows.tpl'));
	$html = $core->build('_ajax.mt_drill.tpl');
	echo json_encode(array(
		'error' => 0, 
		'uid' => $uid, 
		'html' => $html
	), JSON_UNESCAPED_UNICODE); die();
}
// ===== END MyTeam AJAX handlers =====
function default_load_coaching(){
	// P2 — modal "Kèm cặp" chi tiết 1 rep (read-only thật). Gate: isTeamManager || isFullPermiss + IDOR (rep thuộc nhóm mình quản, trừ full-permiss).
	global $smarty, $core, $clsISO, $dbconn, $profile_id;
	$clsCustomer = new Customer();
	if (!($clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss())) {
		echo json_encode(array(
			'error' => 1,
			'message' => 'Không có quyền.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$rep_id = (int) Input::post('rep_id', 0);
	if ($rep_id <= 0) {
		echo json_encode(array(
			'error' => 1,
			'message' => 'Thiếu rep.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$pid = (int) $profile_id;
	if (!$clsCustomer->isFullPermiss()) {
		$clsGroupProfile = new GroupProfile();
		$groups = $clsGroupProfile->getAll("`manager_profile_id`='{$pid}' AND `is_trash`=0", "`list_profile_id`");
		$ok = 0;
		if (!empty($groups)) {
			foreach ($groups as $g) {
				$arr = !empty($g['list_profile_id']) ? $clsISO->getArrayByTextSlash($g['list_profile_id']) : array();
				foreach ($arr as $rr) {
					if ((int) $rr == $rep_id) {
						$ok = 1;
						break;
					}
				}
				if ($ok) {
					break;
				}
			}
		}
		if (!$ok) {
			echo json_encode(array(
				'error' => 1,
				'message' => 'Rep không thuộc nhóm bạn quản lý.'
			), JSON_UNESCAPED_UNICODE);
			die();
		}
	}
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$now = time();
	$ctbl = $clsCustomer->tbl;
	$ftbl = $clsFollowUp->tbl;
	$CHOT = (int) _CRM_STATUS_CHOT_ID;
	$TRASH = (int) _CRM_STATUS_TRASH_ID;
	$DONE = (int) _FOLLOWUP_STATUS_DONE_ID;
	$repArr = $dbconn->GetAll("SELECT `profile_id`,`full_name`,`more_information`,`avatar` FROM `{$clsProfile->tbl}` WHERE `profile_id`='{$rep_id}'");
	$oRep = !empty($repArr) ? $repArr[0] : null;
	$rep_name = ($oRep && trim($oRep['full_name']) !== '') ? trim($oRep['full_name']) : '#' . $rep_id;
	$stArr = $dbconn->GetAll("SELECT SUM(CASE WHEN c2.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') THEN 1 ELSE 0 END) AS active, SUM(CASE WHEN c2.`status_id` IN ('{$CHOT}','312') THEN 1 ELSE 0 END) AS chot, SUM(CASE WHEN c2.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') AND NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=c2.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0) THEN 1 ELSE 0 END) AS no_touch FROM `{$ctbl}` c2 WHERE c2.`is_trash`=0 AND c2.`admin_id`='{$rep_id}'");
	$st = !empty($stArr) ? $stArr[0] : array('active' => 0, 'chot' => 0, 'no_touch' => 0);
	$watch = $dbconn->GetAll("SELECT c.`customer_id`, c.`name`, c.`phone`, mx.`last_due` FROM `{$ctbl}` c INNER JOIN (SELECT `customer_id`, MAX(`date_id`) AS last_due FROM `{$ftbl}` WHERE `followup_type`='_crm' AND `is_trash`=0 AND `status_id`<>'{$DONE}' AND `date_id`>0 AND `date_id`<{$now} GROUP BY `customer_id`) mx ON mx.`customer_id`=c.`customer_id` WHERE c.`is_trash`=0 AND c.`admin_id`='{$rep_id}' AND c.`status_id` NOT IN ('{$CHOT}','312','{$TRASH}') ORDER BY mx.`last_due` ASC LIMIT 8");
	$watch_rows = array();
	if (!empty($watch)) {
		foreach ($watch as $w) {
			$watch_rows[] = array(
				'customer_id' => (int) $w['customer_id'],
				'name' => $w['name'],
				'phone' => !empty($w['phone']) ? $clsCustomer->mask($w['phone'], true) : '—',
				'overdue_text' => ((int) $w['last_due'] > 0 ? $clsISO->getTimeAgo((int) $w['last_due']) : '')
			);
		}
	}
	$smarty->assign('co_rep_id', $rep_id);
	$smarty->assign('co_rep_name', $rep_name);
	$smarty->assign('co_rep_avatar', $clsProfile->getAvatar($rep_id, ($oRep ? $oRep : array())));
	$smarty->assign('co_active', (int) (isset($st['active']) ? $st['active'] : 0));
	$smarty->assign('co_chot', (int) (isset($st['chot']) ? $st['chot'] : 0));
	$smarty->assign('co_no_touch', (int) (isset($st['no_touch']) ? $st['no_touch'] : 0));
	$smarty->assign('co_watch', $watch_rows);
	$html = $core->build('_ajax.coaching.tpl');
	echo json_encode(array('html' => $html), JSON_UNESCAPED_UNICODE);
	die();
}
function default_load_customers(){
	global $smarty, $core, $clsISO, $_LANG_ID, $dbconn, $clsProfile, $deviceType, $clsConfiguration;
	global $profile_id, $oneProfile;
	$clsStock 	 = new Stock();
	$clsCountry  = new Country();
	$clsCity 	 = new City();
	$clsArchived = new Archived();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsFollowUp = new FollowUp();
	$clsCustomerHistory = new CustomerHistory();
	$clsCampaign = new Campaign();
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$clsCustomerMeta = new CustomerMeta();
	$useJoinShare = false;
	$useJoinCampaign = false;
	$useJoinRelation = false;
	$joinSql = "";
	$smarty->assign("clsCustomer", $clsCustomer);
	$smarty->assign("clsFollowUp", $clsFollowUp);
	/* Global cond */
	$now = time();
	$start_time_today = strtotime(date('d-m-Y'));
	$end_time_today = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$start_time_7days = strtotime('-7 days', $now);
	#end_time_today
	$cond = $cnd = "`is_trash`=0";
	$has_search = $has_group = false;
	$action = Input::post('action', "");
	$tab = Input::post('tab', 'owner');
	$view = Input::post("view", "table");
	$holderG = Input::post('holderG', '_tablist');
	$keysearch = Input::post('keysearch', "");
	$status_id = (int) Input::post('status_id');
	$task_id = (int) Input::post('task_id', 0); // U-P0a: lọc theo tác nghiệp (_CRM_TASK) cho chip
	$block_id = (int) Input::post('block_id', 0);
	$bedroom_id = (int) Input::post('bedroom_id', 0);
	$sort_by = Input::post('sort_by', "last_contact");
	$priority_id = (int) Input::post('priority_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$typeHolder = Input::post('typeHolder', "_all");
	$_caller_full_permiss = in_array($profile_id, _PROFILE_CRM_SUPER_ID); // P0: capture caller permiss BEFORE override
	$staff_id = (int)Input::post('staff_id', 0);
	if ($staff_id > 0 && $_caller_full_permiss) {
		$profile_id = $staff_id;
	} // P0-2: only full-permiss may impersonate staff
	$is_all = (int) Input::post('is_all', 0);
	$reg_date = Input::post('reg_date', "");
	$reg_from = trim(Input::post('reg_from', "")); // lọc khoảng ngày tạo: từ ngày (YYYY-MM-DD)
	$reg_to = trim(Input::post('reg_to', "")); // lọc khoảng ngày tạo: tới ngày (YYYY-MM-DD)
	$group_id = (int) Input::post('group_id', 0, true);
	if ($group_id > 0 && !$_caller_full_permiss) {
		$group_id = 0;
	} // P0-3: only full-permiss may filter by arbitrary group
	$admin_id = (int) Input::post('admin_id', 0, true);
	// Drill "Giám sát đội ngũ": director theo ROLE (GĐKD/GĐV) scope theo CÂY PHÒNG BAN (đồng bộ getSubordinateStaffIds với màn my_team). Tính 1 lần, dùng cho guard admin_id + tab=team.
	$_dirTreeReps = array();
	if (!$_caller_full_permiss && ($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR'))) {
		$_meDept = $clsProfile->getOne($profile_id, "department_id");
		$_scopeDept = (int) (!empty($_meDept['department_id']) ? $_meDept['department_id'] : 0);
		if ($clsISO->checkPermissionGroup('REGIONAL_DIRECTOR') && $_scopeDept == (int) _DEPARTMENT_SALE_ID) {
			$_scopeDept = 0; // GĐV không được đứng ở node gốc Kinh doanh
		}
		if ($_scopeDept > 0) {
			foreach ((array) $clsProfile->getSubordinateStaffIds($_scopeDept, $profile_id) as $_drid) {
				$_drid = (int) $_drid;
				if ($_drid > 0) { $_dirTreeReps[$_drid] = $_drid; }
			}
		}
	}
	// P0-5: chống IDOR — full-permiss (super hoặc full_permiss_crm) lọc admin_id tự do; director theo cây phòng ban; quản lý nhóm theo nhóm; sale thường bỏ qua.
	if ($admin_id > 0 && !$_caller_full_permiss && !$clsISO->checkPermission('full_permiss_crm')) {
		$_admin_ok = false;
		if (isset($_dirTreeReps[$admin_id])) { $_admin_ok = true; } // director theo cây phòng ban
		// TP Marketing (_ROLE_HEAD_FREE): được lọc theo NV phòng Marketing (dept _DEPARTMENT_MKT_ID) — MKT lens dưới map admin_id→user_id (người tạo)
		if (!$_admin_ok && (int) $oneProfile['role_id'] === (int) _ROLE_HEAD_FREE) {
			$_tgtMkt = $clsProfile->getOne($admin_id, "department_id");
			if ((int) (!empty($_tgtMkt['department_id']) ? $_tgtMkt['department_id'] : 0) === (int) _DEPARTMENT_MKT_ID) { $_admin_ok = true; }
		}
		if (!$_admin_ok && $clsCustomer->isTeamManager()) {
			$_gpChk = new GroupProfile();
			$_gpRows = $_gpChk->getAll("`manager_profile_id`='{$profile_id}' AND `is_trash`=0", "`list_profile_id`");
			if (!empty($_gpRows)) {
				foreach ($_gpRows as $_gpR) {
					$_repArr = !empty($_gpR['list_profile_id']) ? $clsISO->getArrayByTextSlash($_gpR['list_profile_id']) : array();
					foreach ($_repArr as $_rid) {
						if ((int) $_rid === $admin_id) {
							$_admin_ok = true;
							break 2;
						}
					}
				}
			}
		}
		if (!$_admin_ok) {
			$admin_id = 0;
		}
	}
	$campaign_id = (int) Input::post('campaign_id', 0, true);
	$blocktype_id = (int) Input::post('blocktype_id', 0, true);
	$group_customer_sale = Input::post('group_customer_sale', "", true);
	$shareJoinAdminId = ($admin_id > 0) ? $admin_id : $profile_id;
	$useJoinShare = true;
	$joinSql .= " LEFT JOIN (
		SELECT `customer_id` AS `customer_ref`, `meta_id` AS `share_admin_id`
		FROM `{$clsCustomerMeta->tbl}`
		WHERE `meta_type`='share' AND `meta_id`='{$shareJoinAdminId}'
		GROUP BY `customer_id`, `meta_id`
	) AS `cs` ON `cs`.`customer_ref`=`customer_id`";
	if ($campaign_id > 0) {
		$useJoinCampaign = true;
		$joinSql .= " LEFT JOIN (
			SELECT `customer_id` AS `customer_ref`, `meta_id` AS `rel_campaign_id`
			FROM `{$clsCustomerMeta->tbl}`
			WHERE `meta_type`='campaign' AND `meta_id`='{$campaign_id}'
			GROUP BY `customer_id`, `meta_id`
		) AS `cc` ON `cc`.`customer_ref`=`customer_id`";
	}
	$useJoinRelation = ($useJoinShare || $useJoinCampaign) ? true : false;
	$from_notify = 0;
	$more = $arr_columns = array();
	$is_checkbox = $clsCustomer->isFullPermiss() ? true : false;
	if (!empty($keysearch)) {
		$has_search = true;
		$arr_ids = @explode(',', $keysearch);
		$slug = $core->replaceSpace($keysearch);
		if (!empty($arr_ids)) {
			$from_notify = 1;
			$cond .= " and (`name` like '%{$keysearch}%' 
				or `name_slug` like '%{$slug}%' 
				or `email` like '%{$keysearch}%' 
				or `phone` like '%{$keysearch}%' 
				or `address` like '%{$keysearch}%' 
				or `{$clsCustomer->pkey}` in ('" . implode("','", $arr_ids) . "')
			)";
		} else {
			$cond .= " and (`name` like '%{$keysearch}%' 
				or `name_slug` like '%{$slug}%' 
				or `email` like '%{$keysearch}%' 
				or `phone` like '%{$keysearch}%' 
				or `address` like '%{$keysearch}%'
			)";
		}
	}
	# Q4: bỏ filter priority_id — cột KHÔNG tồn tại trong DB (gây Unknown column / hỏng list)
	# filter by resource_id
	if ($resource_id > 0) {
		$has_search = true;
		$cnd .= " AND `resource_id`='{$resource_id}'";
		$cond .= " AND `resource_id`='{$resource_id}'";
	}
	// F2 — chip "Hot": lọc lead_score >= ngưỡng (JSON_EXTRACT; áp trên tab owner = tập nhỏ nên chấp nhận non-sargable).
	$hot = (int) Input::post('hot', 0);
	if ($hot > 0) {
		$has_search = true;
		$cnd .= " AND CAST(JSON_EXTRACT(`more_information`,'\$.lead_score') AS UNSIGNED) >= " . _CRM_LEAD_SCORE_HOT;
		$cond .= " AND CAST(JSON_EXTRACT(`more_information`,'\$.lead_score') AS UNSIGNED) >= " . _CRM_LEAD_SCORE_HOT;
	}
	// Drill-down filters: overdue (bỏ quên > 2 ngày) và untouched (chưa gọi)
	$overdue = (int) Input::post('overdue', 0);
	if ($overdue > 0) {
		$has_search = true;
		$ftbl = $clsFollowUp->tbl;
		$htbl = $clsCustomerHistory->tbl;
		$ctbl = $clsCustomer->tbl;
		$wmark = time() - 2 * 86400;
		$sub_touch = "GREATEST(COALESCE((SELECT MAX(f.`reg_date`) FROM `{$ftbl}` f WHERE f.`customer_id`=`{$ctbl}`.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0),0), COALESCE((SELECT MAX(h.`action_date`) FROM `{$htbl}` h WHERE h.`customer_id`=`{$ctbl}`.`customer_id`),0))";
		$cnd .= " AND {$sub_touch} > 0 AND {$sub_touch} < {$wmark}";
		$cond .= " AND {$sub_touch} > 0 AND {$sub_touch} < {$wmark}";
	}
	$untouched = (int) Input::post('untouched', 0);
	if ($untouched > 0) {
		$has_search = true;
		$ftbl = $clsFollowUp->tbl;
		$ctbl = $clsCustomer->tbl;
		$sub_uncalled = "NOT EXISTS (SELECT 1 FROM `{$ftbl}` f WHERE f.`customer_id`=`{$ctbl}`.`customer_id` AND f.`followup_type`='_crm' AND f.`is_trash`=0)";
		$cnd .= " AND {$sub_uncalled}";
		$cond .= " AND {$sub_uncalled}";
	}
	if ($block_id > 0) {
		$has_search = true;
		$cnd .= " AND `customer_id` IN (
			SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` 
			WHERE `meta_type`='block' AND `meta_id`='{$block_id}'
		)";
		$cond .= " AND `customer_id` IN (
			SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` 
			WHERE `meta_type`='block' AND `meta_id`='{$block_id}'
		)";
	}
	if ($bedroom_id > 0) {
		$has_search = true;
		$cnd .= " AND `customer_id` IN (
			SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` 
			WHERE `meta_type`='bedroom' AND `meta_id`='{$bedroom_id}'
		)";
		$cond .= " AND `customer_id` IN (
			SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` 
			WHERE `meta_type`='bedroom' AND `meta_id`='{$bedroom_id}'
		)";
	}
	# filter by reg_date
	if (!empty($reg_date)) {
		$cnd .= " AND FROM_UNIXTIME(`reg_date`,'%Y-%m-%d')='{$reg_date}'";
		$cond .= " AND FROM_UNIXTIME(`reg_date`,'%Y-%m-%d')='{$reg_date}'";
	}
	# filter by reg_date range (ngày tạo: từ ngày → tới ngày). reg_date là unix; strtotime → int an toàn (không nội suy chuỗi thô vào SQL)
	if ($reg_from !== "") {
		$_regFromTs = (int) strtotime($reg_from . " 00:00:00");
		if ($_regFromTs > 0) {
			$cnd .= " AND `reg_date` >= {$_regFromTs}";
			$cond .= " AND `reg_date` >= {$_regFromTs}";
		}
	}
	if ($reg_to !== "") {
		$_regToTs = (int) strtotime($reg_to . " 23:59:59");
		if ($_regToTs > 0) {
			$cnd .= " AND `reg_date` <= {$_regToTs}";
			$cond .= " AND `reg_date` <= {$_regToTs}";
		}
	}
	# filter by campaign
	if ($campaign_id > 0) {
		$cnd .= " AND `cc`.`rel_campaign_id`='{$campaign_id}'";
		$cond .= " AND `cc`.`rel_campaign_id`='{$campaign_id}'";
	}
	# filter by status_id
	if (!empty($status_id)) {
		$has_search = true;
		$cond .= " AND `status_id`='{$status_id}'";
	} else {
		if ($is_all == 0) {
			$cond .= " AND `status_id`<>'" . _CRM_STATUS_TRASH_ID . "'";
		}
	}
	# U-P0a: filter by tac nghiep (chip) — task_current_id=0 mac dinh la Lead moi
	if ($task_id > 0 && $tab != 'following') {
		$has_search = true;
		if ($task_id == (int) _CRM_TASK_LEAD_ID) {
			$cond .= " AND (`task_current_id`='{$task_id}' OR `task_current_id`=0)";
		} else {
			$cond .= " AND `task_current_id`='{$task_id}'";
		}
	}
	$orderBy = ""; // Set Order Default
	if ($group_id > 0) {
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array();
		if (!empty($list_profile_arrs)) {
			$has_group = true;
			$cnd .= " AND (`admin_id` in (" . implode(',', $list_profile_arrs) . "))";
			$cond .= " AND (`admin_id` in (" . implode(',', $list_profile_arrs) . "))";
		}
	}
	if ($blocktype_id > 0) {
		$cnd .= " AND `blocktype_id`='{$blocktype_id}'";
		$cond .= " AND `blocktype_id`='{$blocktype_id}'";
	}
	if ($is_all == 0) {
		$cnd .= " AND `customer_id` not in (
			select `customer_id` from `{$clsArchived->tbl}` 
			where `profile_id`='{$profile_id}'
		)";
		$cond .= " AND `customer_id` not in (
			select `customer_id` from `{$clsArchived->tbl}` 
			where `profile_id`='{$profile_id}'
		)";
	}
	if ($group_customer_sale != "") {
		$group_customer = $clsConfiguration->getValue('group_customer_sale');
		$group_customer = !empty($group_customer) ? $clsISO->to_array_json($group_customer) : [];
		$arr_group_customer_sale = !empty($group_customer[$profile_id]) ? $group_customer[$profile_id] : [];
		if (!empty($arr_group_customer_sale[$group_customer_sale])) {
			$list_ids = $arr_group_customer_sale[$group_customer_sale]["list_ids"];
			$cnd .= " AND `customer_id` IN (" . implode(',', $list_ids) . ")";
			$cond .= " AND `customer_id` IN (" . implode(',', $list_ids) . ")";
		}
	}
	// #46 tab Team: resolve reps nhóm mình quản lý (SERVER theo manager_profile_id=me, bỏ qua client group_id). Không phải manager / nhóm rỗng → fallback owner.
	$team_rep_csv = '';
	$_treps = array();
	if ($tab == 'team') {
		if (($clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss() || !empty($_dirTreeReps)) && !$clsCustomer->isMarketing()) {
			$clsGroupProfileTab = new GroupProfile();
			$_tgcond = $clsCustomer->isFullPermiss() ? "`is_trash`=0" : "`manager_profile_id`='{$profile_id}' AND `is_trash`=0";
			$_tgroups = $clsGroupProfileTab->getAll($_tgcond, "`list_profile_id`");
			if (!empty($_tgroups)) {
				foreach ($_tgroups as $_tg) {
					$_arr = !empty($_tg['list_profile_id']) ? $clsISO->getArrayByTextSlash($_tg['list_profile_id']) : array();
					foreach ($_arr as $_rid) {
						$_rid = (int) $_rid;
						if ($_rid > 0) {
							$_treps[$_rid] = $_rid;
						}
					}
				}
			}
			foreach ($_dirTreeReps as $_drid) { $_treps[$_drid] = $_drid; } // merge subtree dept-tree (director theo role)
			$team_rep_csv = !empty($_treps) ? implode(',', array_values($_treps)) : '';
		}
		if ($team_rep_csv == '') {
			$tab = 'owner';
		}
		// nếu drill xuống 1 rep cụ thể nhưng rep đó KHÔNG thuộc nhóm mình quản lý → bỏ qua admin_id (chỉ hiện cả nhóm).
		else if ($admin_id > 0 && !in_array($admin_id, $_treps)) {
			$admin_id = 0;
		}
	}
	$sql_string = $sql_cond = $cond;
	if ($admin_id > 0) {
		// Ẩn checkbox vì mình không phải là người quản lý.
		$is_checkbox = $clsCustomer->isFullPermiss() ? true : false;
		if ($tab == 'owner' || $tab == 'converted_customer' || $tab == 'team') {
			if($clsCustomer->isMarketing()){
				$cnd .= " AND (`user_id`='{$admin_id}')";
				$cond .= " AND (`user_id`='{$admin_id}')";
			} else {
				$cnd .= " AND (`admin_id`='{$admin_id}')";
				$cond .= " AND (`admin_id`='{$admin_id}')";
			}
		} else {
			$cnd .= " AND (`admin_id`<>'{$admin_id}' AND `cs`.`share_admin_id`='{$admin_id}')";
			$cond .= " AND (`admin_id`<>'{$admin_id}' AND `cs`.`share_admin_id`='{$admin_id}')";
		}
	}
	#- Order by
	if (($tab == 'owner' || $tab == 'converted_customer') && $has_group == false) {
		if ($admin_id == 0) {
			// Phụ trách = khách mình LÀ CHỦ (admin_id), áp dụng cả MKT (bỏ special-case user_id cũ).
			$_oc = 'admin_id';
			$cnd .= " AND `{$_oc}`='{$profile_id}'";
			$cond .= " AND `{$_oc}`='{$profile_id}'";
		}
		$is_checkbox = true;
		$orderBy = "order by `upd_date` " . ($sort_by == 'first_contact' ? 'ASC' : 'DESC');
	} else if ($tab == 'following' && $has_group == false) {
		if ($admin_id == 0) {
			if ($clsCustomer->isMarketing()) {
				// MKT: Theo dõi = khách mình tạo (user_id) HOẶC được chia sẻ (share) — KHÔNG loại trừ khách mình là chủ.
				$_followCond = " AND (`user_id`='{$profile_id}' or `cs`.`share_admin_id`='{$profile_id}')";
			} else {
				// Sale: Theo dõi = khách mình KHÔNG là chủ nhưng mình tạo/được chia.
				$_followCond = " AND `admin_id`<>'{$profile_id}' AND (`user_id`='{$profile_id}' or `cs`.`share_admin_id`='{$profile_id}')";
			}
			$cnd .= $_followCond;
			$cond .= $_followCond;
		}
		$orderBy = " order by `use_globe` DESC, `upd_date` " . ($sort_by == 'first_contact' ? 'ASC' : 'DESC');
	} else if ($tab == 'team' && $team_rep_csv != '' && $has_group == false) {
		// #46 tab Team: khách của các rep trong nhóm mình quản lý (read-only).
		if ($admin_id == 0) {
			$cnd .= " AND `admin_id` IN ({$team_rep_csv})";
			$cond .= " AND `admin_id` IN ({$team_rep_csv})";
		}
		$is_checkbox = $clsCustomer->isFullPermiss() ? true : false;
		$orderBy = "order by `upd_date` " . ($sort_by == 'first_contact' ? 'ASC' : 'DESC');
	}
	$smarty->assign("is_checkbox", $is_checkbox);
	// #46 tab Team: chỉ-đọc với quản lý (không phải full-permiss) → ẩn nút ghi (Ghi nhận / Lưu trữ), chỉ giữ nút xem.
	$smarty->assign("team_readonly", ($tab == 'team' && !$clsCustomer->isFullPermiss()));
	#time
	$date_type = Input::post('date_type', '_month');
	if ($date_type == '_month') {
		$quarter = 0; // Init value
		$month  = (int) Input::post('month', 0);
	} else if ($date_type == '_half_year') {
		$month = $quarter = 0;
		$half_year = (int) Input::post('month', 0);
	} else if ($date_type == '_quarter') {
		$month = 0; // Init value
		$quarter  = (int) Input::post('month', 0);
	}
	$year  = (int) Input::post('year', 0);
	###
	if (!empty($year)) {
		if ($date_type == '_month' && $month > 0) {
			$date_my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$cnd .= " and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'";
			$cond .= " and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$date_my}'";
		} else if ($date_type == '_half_year' && $half_year > 0) {
			if ($half_year == 1) {
				$start_month = 1;
				$end_month = 6;
			} else if ($half_year == 2) {
				$start_month = 7;
				$end_month = 12;
			}
			$start_date = strtotime(sprintf('01-%s-%s', $start_month, $year));
			$end_day = cal_days_in_month(CAL_GREGORIAN, $end_month, $year);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $end_month, $year));
			$cnd .= " AND (`reg_date` BETWEEN {$start_date} AND {$end_date})";
			$cond .= " AND (`reg_date` BETWEEN {$start_date} AND {$end_date})";
		} else if ($date_type == '_quarter' && $quarter > 0) {
			if ($quarter == 1) {
				$start_month = 1;
				$end_month = 3;
			} else if ($quarter == 2) {
				$start_month = 4;
				$end_month = 6;
			} else if ($quarter == 3) {
				$start_month = 7;
				$end_month = 9;
			} else if ($quarter == 4) {
				$start_month = 10;
				$end_month = 12;
			}
			$start_time = date(sprintf('%s-%s-01 00:00', $year, $clsISO->parseNumber($start_month)));
			$end_time = date(sprintf('%s-%s-t 23:59:59', $year, $clsISO->parseNumber($end_month)));
			$cnd .= " and `reg_date` BETWEEN " . strtotime($start_time) . " AND " . strtotime($end_time);
			$cond .= " and `reg_date` BETWEEN " . strtotime($start_time) . " AND " . strtotime($end_time);
		} else {
			$cnd .= " and FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
			$cond .= " and FROM_UNIXTIME(`reg_date`,'%Y')='{$year}'";
		}
	}
	#- beign pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	$per_page = ($per_page > 0) ? $per_page : 20;
	$current_page = ($current_page > 0) ? $current_page : 1;
	$countByWhere = function ($where) use ($dbconn, $clsCustomer, $joinSql) {
		$sql = "SELECT COUNT(DISTINCT `customer_id`) AS `total` 
			FROM `{$clsCustomer->tbl}` {$joinSql} 
			WHERE {$where}";
		$tmp = $dbconn->GetOne($sql);
		return (int) $tmp;
	};
	$total_record = $useJoinRelation ? $countByWhere($cond) : $clsCustomer->countItem($cond);
	$total_page = ($total_record > 0) ? (int) ceil($total_record / $per_page) : 1;
	if ($current_page > $total_page) {
		$current_page = $total_page;
	}
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$smarty->assign('index', $offset);
	$smarty->assign('action', $action);
	#- end pagination
	if ($has_group == false) {
		if ($admin_id > 0) {
			$total_manage = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`='{$admin_id}')") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`='{$admin_id}')");
			$total_assign = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`<>'{$admin_id}' and `cs`.`share_admin_id`='{$admin_id}')") : 0;
			$total_converted = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id`='{$admin_id}') and (`status_id`='" . _CRM_STATUS_CHOT_ID . "')") : $clsCustomer->countItem("{$sql_string} AND (`admin_id`='{$admin_id}') and (`status_id`='" . _CRM_STATUS_CHOT_ID . "')");
		} else {
			// Phụ trách = admin_id (cả MKT). Theo dõi = khách mình tạo (user_id) HOẶC được chia sẻ (share), không loại trừ khách mình là chủ.
			$_oc = 'admin_id';
			$total_manage = $useJoinRelation ? $countByWhere("{$sql_string} AND (`{$_oc}`='{$profile_id}')") : $clsCustomer->countItem("{$sql_string} AND (`{$_oc}`='{$profile_id}')");
			// Theo dõi khớp list: MKT = (user_id OR share); sale = admin!=me AND (user_id OR share).
			$_assignCond = $clsCustomer->isMarketing() ? "(`user_id`='{$profile_id}' OR `cs`.`share_admin_id`='{$profile_id}')" : "(`admin_id`<>'{$profile_id}' AND (`user_id`='{$profile_id}' OR `cs`.`share_admin_id`='{$profile_id}'))";
			$total_assign = $useJoinRelation ? $countByWhere("{$sql_string} AND {$_assignCond}") : 0;
			$total_converted = $useJoinRelation ? $countByWhere("{$sql_string} AND (`{$_oc}`='{$profile_id}') AND (`status_id`='" . _CRM_STATUS_CHOT_ID . "')") : $clsCustomer->countItem("{$sql_string} AND (`{$_oc}`='{$profile_id}') AND (`status_id`='" . _CRM_STATUS_CHOT_ID . "')");
		}
	} else {
		$total_assign = 0;
		$total_manage = $total_record;
	}
	// Badge "Nhóm" trên segmented scope: tổng khách của các rep trong nhóm mình quản lý (chỉ tính cho team-manager/full-permiss — người duy nhất thấy tab Nhóm)
	$total_team = 0;
	if ($clsCustomer->isTeamManager() || $clsCustomer->isFullPermiss()) {
		$_team_reps_cnt = $_treps; // tái dùng nếu tab==team đã resolve; nếu rỗng → resolve riêng cho badge
		if (empty($_team_reps_cnt)) {
			$clsGroupProfileCnt = new GroupProfile();
			$_gcond = $clsCustomer->isFullPermiss() ? "`is_trash`=0" : "`manager_profile_id`='{$profile_id}' AND `is_trash`=0";
			$_grs = $clsGroupProfileCnt->getAll($_gcond, "`list_profile_id`");
			if (!empty($_grs)) {
				foreach ($_grs as $_g) {
					$_a = !empty($_g['list_profile_id']) ? $clsISO->getArrayByTextSlash($_g['list_profile_id']) : array();
					foreach ($_a as $_rid) {
						$_rid = (int) $_rid;
						if ($_rid > 0) {
							$_team_reps_cnt[$_rid] = $_rid;
						}
					}
				}
			}
		}
		if (!empty($_team_reps_cnt)) {
			$_trc = implode(',', array_values($_team_reps_cnt));
			$total_team = $useJoinRelation ? $countByWhere("{$sql_string} AND (`admin_id` IN ({$_trc}))") : $clsCustomer->countItem("{$sql_string} AND (`admin_id` IN ({$_trc}))");
		}
	}
	#
	$field = "{$clsCustomer->pkey},CONVERT(BINARY(CONVERT(`phone` USING latin1)) USING utf8mb4) as `phone`";
	// Q1: câu LIST chỉ join khi WHERE tham chiếu alias join (cs/cc) — owner-tab bỏ lateral join + DISTINCT (count vẫn dùng $useJoinRelation)

	//$clsISO->print_pre($cond); die();
	$listNeedsJoin = (strpos($cond, '`cs`.') !== false || strpos($cond, '`cc`.') !== false);
	if ($listNeedsJoin) {
		$sqlList = "SELECT DISTINCT `{$clsCustomer->tbl}`.*
			FROM `{$clsCustomer->tbl}` {$joinSql} 
			WHERE {$cond} {$orderBy} {$limitCond}";
		$list_customers = $dbconn->GetAll($sqlList);
	} else {
		$list_customers = $clsCustomer->getAll("{$cond} {$orderBy}" . $limitCond);
	}
	$meta_map = array();
	$task_result_ids_by_task = array();
	if (!empty($list_customers)) {
		$customer_ids = array();
		$task_current_ids = array();
		foreach ($list_customers as $row) {
			$cid = (int) $row[$clsCustomer->pkey];
			if ($cid > 0) {
				$customer_ids[$cid] = $cid;
			}
			$_task_current_id = (int) $row['task_current_id'];
			if ($_task_current_id <= 0 && defined('_CRM_TASK_LEAD_ID')) {
				$_task_current_id = (int) _CRM_TASK_LEAD_ID;
			}
			if ($_task_current_id > 0) {
				$task_current_ids[$_task_current_id] = $_task_current_id;
			}
		}
		$meta_map = $clsCustomerMeta->getMapByCustomerIds(
			array_values($customer_ids),
			array('tag', 'stock', 'campaign', 'purpose', 'need', 'type', 'bedroom', 'block', 'share')
		);
		$task_result_ids_by_task = crm_task_result_ids_by_task(array_values($task_current_ids), $clsCustomerTaskSetting);
		// Q5: gom archived + followups thành 2 truy vấn bulk-IN (thay N+1 trong vòng lặp render)
		$followups_map = $archived_map = array();
		$_cid_csv = implode(',', array_values($customer_ids));
		if ($_cid_csv !== '') {
			$_arch_rows = $dbconn->GetAll("SELECT `customer_id` FROM `{$clsArchived->tbl}` WHERE `profile_id`='{$profile_id}' AND `customer_id` IN ({$_cid_csv})");
			if (!empty($_arch_rows)) {
				foreach ($_arch_rows as $_ar) {
					$archived_map[(int)$_ar['customer_id']] = 1;
				}
			}
			$_fu_rows = $dbconn->GetAll("SELECT `customer_id`,`{$clsFollowUp->pkey}`,`date_id`,`intro` FROM `{$clsFollowUp->tbl}` WHERE `customer_id` IN ({$_cid_csv}) ORDER BY `customer_id` ASC, `reg_date` DESC");
			if (!empty($_fu_rows)) {
				foreach ($_fu_rows as $_fr) {
					$_fcid = (int) $_fr['customer_id'];
					if (!isset($followups_map[$_fcid])) {
						$followups_map[$_fcid] = array();
					}
					$followups_map[$_fcid][] = $_fr;
				}
			}
		}
	}
	$list_data_fields = $clsProperty->getArraySearchByKey("FIELD_DATA");
	$more_information = $oneProfile['more_information'];
	$def_field = ($view_by == "table") ? _ARRAY_TABLE_FIELD_DATA_CUSTOMER_DEFAULT : _ARRAY_COMPACT_FIELD_DATA_CUSTOMER_DEFAULT;
	$list_setting_field = $core->get_field($more_information, "fieldDataCustomer", $def_field);
	if (!empty($list_setting_field)) {
		foreach ($list_setting_field as $id) {
			if (!isset($list_data_fields[$id])) {
				$list_data_fields[$id] = $clsProperty->getOne($id, "`title`,`property_code`");
			}
			$arr_columns[$list_data_fields[$id]['property_code']] = $list_data_fields[$id];
		}
	}
	if (isset($arr_columns['task_next']) && !isset($arr_columns['waiting_time'])) {
		$_tmp_columns = array();
		foreach ($arr_columns as $_k => $_v) {
			$_tmp_columns[$_k] = $_v;
			if ($_k == 'task_next') {
				$_tmp_columns['waiting_time'] = array(
					'title' => 'Thời gian chờ',
					'property_code' => 'waiting_time'
				);
			}
		}
		$arr_columns = $_tmp_columns;
	}
	// Đưa cột "Phân loại khách" (status) lên ngay sau cột Tên khách
	if (isset($arr_columns['status']) && isset($arr_columns['name'])) {
		$_st_col = $arr_columns['status'];
		$_reordered = array();
		foreach ($arr_columns as $_ck => $_cv) {
			if ($_ck === 'status') {
				continue;
			}
			$_reordered[$_ck] = $_cv;
			if ($_ck === 'name') {
				$_reordered['status'] = $_st_col;
			}
		}
		$arr_columns = $_reordered;
	}
	$smarty->assign('arr_columns', $arr_columns);
	#
	$lst_data = [];
	$arr_field = !empty($arr_columns) ? @array_keys($arr_columns) : [];
	// $clsISO->print_pre($arr_field); die();	
	if (in_array('need', $arr_field)) $arr_need = $clsProperty->getArraySearchByKey("NEED");
	if (in_array('finance', $arr_field)) $arr_finance = $clsProperty->getArraySearchByKey("FINANCE");
	if (in_array('purpose', $arr_field)) $arr_purpose = $clsProperty->getArraySearchByKey("PURPOSE");
	if (in_array('bedroom', $arr_field)) $arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	if (in_array('customer_type', $arr_field)) $arr_customer_type = $clsProperty->getArraySearchByKey("CUSTOMER_TYPE");
	###
	$total_cus_new = $total_cus_7days = 0;
	$arr_status_cached = $arr_property_cached = $arr_block_cached = array();
	$s_field = "{$clsProperty->pkey},`property_type`,`title`,`property_code`,`bgcolor`,`intro`,`more_information`,`is_trash`";
	$arr_property_ins = array('CUSTOMER_STATUS', '_AGENCY', '_GENDER');
	$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type` in ('" . implode('\',\'', $arr_property_ins) . "') order by `order_no` ASC", $s_field);
	if (!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			if ($val['property_type'] == 'CUSTOMER_STATUS') {
				$arr_status_cached[$val[$clsProperty->pkey]] = $val;
			} else {
				$arr_property_cached[$val[$clsProperty->pkey]] = $val;
			}
		}
		unset($tmp);
	}
	$tmp = $clsSetting->getCacheItems('_PROJECT');
	if (!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_block_cached[$val[$clsSetting->pkey]] = $val["title"];
		}
	}
	$arr_crm_task_cached = array();
	$tmp = $clsSetting->getCacheItems('_CRM_TASK');
	if (!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_crm_task_cached[$val[$clsSetting->pkey]] = $val;
		}
	}
	$arr_crm_result_cached = array();
	$tmp = $clsSetting->getCacheItems('_CRM_RESULT');
	if (!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_crm_result_cached[$val[$clsSetting->pkey]] = $val;
		}
	}
	if (!empty($list_customers)) {
		$ii = 0; // Init
		$arr_profile_cached = $clsProfile->getProfileCached();
		$arr_country_cached = $arr_city_cached = $arr_campaign_cached = array();
		foreach ($list_customers as $k_cus => $cus) {
			$reg_date = $cus['reg_date'];
			$admin_id = $cus['admin_id'];
			$status_id = (int) $cus['status_id'];
			$customer_id = $cus[$clsCustomer->pkey];
			$resource_id = (int) $cus['resource_id'];
			$blocktype_id = (int) $cus['blocktype_id'];
			$task_current_id = (int) $cus['task_current_id'];
			$task_result_id = (int) $cus['task_result_id'];
			$task_next_id = (int) $cus['task_next_id'];
			$time_receipt = (int) $cus['time_receipt'];
			$meta = isset($meta_map[$customer_id]) ? $meta_map[$customer_id] : array();
			$list_tags_arrs = isset($meta['tag']) ? $meta['tag'] : array();
			$list_stock_arrs = isset($meta['stock']) ? $meta['stock'] : array();
			$campaign_arrs = isset($meta['campaign']) ? $meta['campaign'] : array();
			$list_purpose_ids = isset($meta['purpose']) ? $meta['purpose'] : array();
			$list_need_ids = isset($meta['need']) ? $meta['need'] : array();
			$list_type_ids = isset($meta['type']) ? $meta['type'] : array();
			$list_bedroom_ids = isset($meta['bedroom']) ? $meta['bedroom'] : array();
			$list_block_ids = isset($meta['block']) ? $meta['block'] : array();
			$list_share_ids = isset($meta['share']) ? $meta['share'] : array();
			$more_info = $cus['more_information'];
			$more_info = $clsISO->to_array_json($more_info);
			#
			if ($status_id == _CRM_STATUS_LEAD_ID && ($reg_date > $start_time_today && $reg_date < $end_time_today)) {
				$total_cus_new += 1;
			}
			if ($status_id != _CRM_STATUS_CHOT_ID && ($reg_date > $start_time_7days && $reg_date < $now)) {
				$total_cus_7days += 1;
			}
			$icon = ($admin_id == $profile_id) ? 'plus' : 'gavel';
			$list_customers[$k_cus]['icon'] = $icon;
			#- Mục đích
			$title_purpose = "";
			$list_archived = $cus['list_archived'];
			if (!empty($list_purpose_ids)) {
				$title_purpose = " " . $clsProperty->getTitleArray($list_purpose_ids, true);
			}
			$list_customers[$k_cus]['title_purpose'] = $title_purpose;
			$list_customers[$k_cus]['name'] = (!empty($cus['name']) ? ucfirst($cus['name']) : "Không tên");
			$list_customers[$k_cus]['begin_need'] = (!empty($cus['begin_need']) ? htmlspecialchars($cus['begin_need']) : "");
			#
			$is_archived = isset($archived_map[(int)$customer_id]) ? 1 : 0; // Q5: tra map bulk-IN thay N+1
			$icon_archived = $clsISO->makeIcon($is_archived ? 'bx bx-archive-in text-yellow' : 'bx bx-archive-in text-blank');
			$list_customers[$k_cus]['icon_archived'] = $icon_archived;
			###
			$html_follow_ups = "";
			$props = 'customer_id="' . $customer_id . '"';
			$total_followups = $total_followups_next = 0;
			$list_followups = isset($followups_map[(int)$customer_id]) ? $followups_map[(int)$customer_id] : array(); // Q5: tra map bulk-IN thay N+1
			if (!empty($list_followups)) {
				$kk = 1;
				$total_followups = count($list_followups);
				$html_follow_ups .= '<ul class="mb-0 list-unstyled lh-xs" style="min-width:200px">';
				foreach ($list_followups as $mkey => $mval) {
					if ($kk == 1) {
						$html_follow_ups .= '<li class="fs-12 my-0">
							<span class="text-' . ($mval['date_id'] > $now ? 'main' : 'primary') . '">' . ($mval['date_id'] > $now ? $clsISO->getTimeMore($mval['date_id']) : $clsISO->getTimeAgo($mval['date_id'])) . '</span> - ' . ucfirst($mval['intro']) . '
						</li>';
					}
					if ($mval['date_id'] >= time()) {
						$total_followups_next += 1;
					}
					++$kk;
				}
				$html_follow_ups .= '</ul>';
			}
			$list_customers[$k_cus]['total_followups'] = $total_followups;
			$list_customers[$k_cus]['total_followups_next'] = $total_followups_next;
			#
			// $html_tags = $clsCustomer->getHTMLTags($customer_id, $cus);
			$html_stocks = !empty($list_stock_arrs)
				? sprintf('<div class="d-flex gap-1 mb-1">%s</div>', $clsStock->getTitleArray($list_stock_arrs, "_list")) : "";
			// $list_customers[$k_cus]['html_tags'] = $html_tags;
			$list_customers[$k_cus]['html_stocks'] = $html_stocks;
			$list_customers[$k_cus]['html_follow_ups'] = $html_follow_ups;
			$oneStatus = $arr_status_cached[$status_id];
			$bgcolor = $oneStatus['bgcolor'];
			$textcolor = $oneStatus['textcolor'];
			$list_customers[$k_cus]['bgcolor'] = $bgcolor;
			$list_customers[$k_cus]['textcolor'] = $textcolor;
			// Mobile: dữ liệu thêm cho thẻ danh sách bản phone (tiêu đề trạng thái / lead_score / giới tính)
			if ($deviceType == "phone") {
				$list_customers[$k_cus]['status_title'] = $clsProperty->getTitle($status_id, $oneStatus);
				$list_customers[$k_cus]['lead_score'] = (int) $core->get_field($more_info, 'lead_score', 0);
				$_gid_mb = (int) $core->get_field($more_info, 'gender_id', 0);
				$_gt_mb = ($_gid_mb > 0) ? $clsProperty->getTitle($_gid_mb) : '';
				$list_customers[$k_cus]['gender_sign'] = (mb_stripos($_gt_mb, 'Nữ') !== false) ? 'f' : ((mb_stripos($_gt_mb, 'Nam') !== false) ? 'm' : '');
				// ===== Thẻ khách bản mobile kiểu mới: dữ liệu bổ sung =====
				// Ngày tạo + thời điểm tương tác lần cuối
				$list_customers[$k_cus]['reg_date_text'] = ($reg_date > 0) ? date('d/m/Y', $reg_date) : '--';
				$_upd_mb = (int) $cus['upd_date'];
				$list_customers[$k_cus]['last_act_text'] = ($_upd_mb > 0) ? $clsISO->getTimeAgo($_upd_mb) : '';
				// Người phụ trách (admin_id): avatar + tên + phòng ban — lấy từ profile cache (không thêm query)
				$_owner = isset($arr_profile_cached[$admin_id]) ? $arr_profile_cached[$admin_id] : array();
				$list_customers[$k_cus]['owner_name'] = (!empty($_owner['full_name'])) ? $_owner['full_name'] : '';
				$list_customers[$k_cus]['owner_avatar'] = ($admin_id > 0) ? $clsProfile->getAvatar($admin_id, $_owner) : '';
				$_owner_mi = (isset($_owner['more_information']) && is_array($_owner['more_information'])) ? $_owner['more_information'] : array();
				$_owner_dept = (!empty($_owner_mi['department_name'])) ? $_owner_mi['department_name'] : '';
				if ($_owner_dept === '') {
					$_owner_dept_id = (int) (isset($_owner['department_id']) ? $_owner['department_id'] : 0);
					if ($_owner_dept_id > 0) {
						if (!isset($arr_dept_cached[$_owner_dept_id])) { $arr_dept_cached[$_owner_dept_id] = $clsProperty->getTitle($_owner_dept_id); }
						$_owner_dept = $arr_dept_cached[$_owner_dept_id];
					}
				}
				$list_customers[$k_cus]['owner_dept'] = $_owner_dept;
				// Nhu cầu = Mục đích (purpose) + Loại căn (bedroom) — TEXT THUẦN (label=false).
				// KHÔNG dùng $title_purpose: nó được dựng với label=true (HTML <label>) cho ô Tên desktop.
				$_need_parts = array();
				if (!empty($list_purpose_ids)) {
					$_pp_mb = trim($clsProperty->getTitleArray($list_purpose_ids, false));
					if ($_pp_mb !== '') { $_need_parts[] = $_pp_mb; }
				}
				if (!empty($list_bedroom_ids)) {
					$_bed_mb = trim($clsProperty->getTitleArray($list_bedroom_ids, false));
					if ($_bed_mb !== '') { $_need_parts[] = $_bed_mb; }
				}
				$list_customers[$k_cus]['need_text'] = !empty($_need_parts) ? implode(' ', $_need_parts) : '';
				// Dự án/Phân khu (tái dùng arr_block_cached như cột "block")
				$_blocks_mb = array();
				if (!empty($list_block_ids)) {
					foreach ($list_block_ids as $_bid_mb) {
						if (!empty($arr_block_cached[$_bid_mb])) { $_blocks_mb[] = $arr_block_cached[$_bid_mb]; }
					}
				}
				$list_customers[$k_cus]['project_text'] = !empty($_blocks_mb) ? implode(', ', $_blocks_mb) : '';
				// Chiến dịch (text thuần, không bọc link như desktop)
				$_camp_mb = array();
				if (!empty($campaign_arrs)) {
					foreach ($campaign_arrs as $_cpid_mb) {
						if (!isset($arr_campaign_cached[$_cpid_mb])) {
							$arr_campaign_cached[$_cpid_mb] = $clsCampaign->getTitle($_cpid_mb);
						}
						if (!empty($arr_campaign_cached[$_cpid_mb])) { $_camp_mb[] = $arr_campaign_cached[$_cpid_mb]; }
					}
				}
				$list_customers[$k_cus]['campaign_text'] = !empty($_camp_mb) ? implode(', ', $_camp_mb) : '';
				// Tương tác gần nhất (tối đa 2 dòng) + icon suy ra từ nội dung intro
				$_acts_mb = array();
				if (!empty($list_followups)) {
					$_cnt_mb = 0;
					foreach ($list_followups as $_fu_mb) {
						if ($_cnt_mb >= 2) { break; }
						$_intro_mb = trim((string) $_fu_mb['intro']);
						if ($_intro_mb === '') { continue; }
						$_dx_mb = (int) $_fu_mb['date_id'];
						$_lc_mb = mb_strtolower($_intro_mb, 'UTF-8');
						$_ico_mb = 'bx-bell'; $_col_mb = '#8a93a2';
						if (mb_strpos($_lc_mb, 'zalo') !== false) { $_ico_mb = 'bx-message-rounded-dots'; $_col_mb = '#1c5fc2'; }
						elseif (mb_strpos($_lc_mb, 'facebook') !== false) { $_ico_mb = 'bxl-facebook-circle'; $_col_mb = '#1c5fc2'; }
						elseif (mb_strpos($_lc_mb, 'hẹn') !== false || mb_strpos($_lc_mb, 'gặp') !== false) { $_ico_mb = 'bx-calendar-check'; $_col_mb = '#1a8a52'; }
						elseif (mb_strpos($_lc_mb, 'gọi') !== false || mb_strpos($_lc_mb, 'call') !== false) {
							$_ico_mb = 'bx-phone';
							$_col_mb = (mb_strpos($_lc_mb, 'không nghe') !== false || mb_strpos($_lc_mb, 'không liên lạc') !== false || mb_strpos($_lc_mb, 'máy bận') !== false || mb_strpos($_lc_mb, 'từ chối') !== false) ? '#e0506a' : '#1a8a52';
						}
						$_acts_mb[] = array(
							'date_text' => ($_dx_mb > 0) ? date('d/m', $_dx_mb) : '',
							'intro' => $_intro_mb,
							'icon' => $_ico_mb,
							'color' => $_col_mb,
						);
						$_cnt_mb++;
					}
				}
				$list_customers[$k_cus]['recent_acts'] = $_acts_mb;
				// Bước tiếp theo (V1): tác nghiệp kế + thời điểm hẹn (tái dùng arr_crm_task_cached + time_receipt, không thêm query).
				$_tn_mb = (int) $cus['task_next_id'];
				$list_customers[$k_cus]['task_next_text'] = ($_tn_mb > 0 && isset($arr_crm_task_cached[$_tn_mb]['title'])) ? $arr_crm_task_cached[$_tn_mb]['title'] : '';
				$_tr_mb2 = (int) $cus['time_receipt'];
				$_when_mb = ''; $_over_mb = 0;
				if ($_tn_mb > 0 && $_tr_mb2 > 0) {
					$_now_mb2 = time();
					$_over_mb = ($_tr_mb2 <= $_now_mb2) ? 1 : 0;
					$_hm_mb = date('H:i', $_tr_mb2);
					$_dday_mb = (int) round((strtotime(date('Y-m-d', $_tr_mb2)) - strtotime(date('Y-m-d', $_now_mb2))) / 86400);
					if ($_dday_mb === 0) { $_when_mb = $_hm_mb . ' hôm nay'; }
					else if ($_dday_mb === 1) { $_when_mb = $_hm_mb . ' ngày mai'; }
					else if ($_dday_mb === -1) { $_when_mb = $_hm_mb . ' hôm qua'; }
					else { $_when_mb = $_hm_mb . ' ' . date('d/m', $_tr_mb2); }
				}
				$list_customers[$k_cus]['task_next_time'] = $_when_mb;
				$list_customers[$k_cus]['task_next_overdue'] = $_over_mb;
				// Badge "Lần N" — số lần gọi/nhắn; hiện khi tác nghiệp tiếp lặp lại task hiện tại (vòng counter).
				$list_customers[$k_cus]['task_attempt_badge'] = ($_tn_mb === $task_current_id) ? crm_task_attempt_badge(crm_task_attempt_count($more_info, $task_current_id)) : '';
					// Nút "Ghi kết quả" (mobile): card .cmc thiếu ô Kết quả như desktop → cho phép chọn kết quả/kết thúc chăm sóc.
					$list_customers[$k_cus]['task_current_id'] = ($task_current_id > 0) ? (int) $task_current_id : 0;
				// Menu "Chuyển" (đổi trạng thái) — chỉ chủ KH hoặc full-permiss
				$_canChg_mb = ($cus['admin_id'] == $profile_id || $clsCustomer->isFullPermiss());
				$list_customers[$k_cus]['can_change_status'] = $_canChg_mb ? 1 : 0;
				$_stMenu_mb = '';
				if ($_canChg_mb && !empty($arr_status_cached)) {
					foreach ($arr_status_cached as $_oSt_mb) {
						$_sid_mb = (int) $_oSt_mb[$clsProperty->pkey];
						if (!empty($_oSt_mb['is_trash']) && $_sid_mb != $status_id) { continue; }
						$_stMenu_mb .= '<a href="javascript:void(0)" class="dropdown-item cmc-st-item' . ($_sid_mb == $status_id ? ' active' : '') . '" data-customer-id="' . $customer_id . '" data-status-id="' . $_sid_mb . '" data-bg="' . htmlspecialchars($_oSt_mb['bgcolor']) . '" onClick="$Core.crm.mb_change_status(this, event)"><span class="cmc-st-swatch" style="background:' . htmlspecialchars($_oSt_mb['bgcolor']) . '"></span>' . htmlspecialchars($_oSt_mb['title']) . '</a>';
					}
				}
				$list_customers[$k_cus]['status_menu_html'] = $_stMenu_mb;
			}
			// Số ĐT: chủ khách (admin_id==profile_id) hoặc super được bấm 👁 để hiện full số; người khác giữ che
			$_isOwnerPhone = ($cus['admin_id'] == $profile_id || $clsCustomer->isFullPermiss());
			// Chưa xác nhận nhận khách này → che SĐT (không lộ data-full để bấm xem), kể cả chủ/full-permiss.
			$_isRecvPending = $clsCustomer->isReceivePending($customer_id);
			$list_customers[$k_cus]['is_pending'] = $_isRecvPending ? 1 : 0;
			if ($_isRecvPending) {
				$_isOwnerPhone = 0;
			}
			$list_customers[$k_cus]['is_owner_phone'] = $_isOwnerPhone ? 1 : 0;
			if (!empty($cus['phone'])) {
				$_pmask = $clsCustomer->mask($cus['phone'], true);
				$_phWrap = $_isOwnerPhone
					? '<span class="crm-phone-wrap js__reveal-phone cursor-pointer" data-full="' . $cus['phone'] . '" onClick="$Core.crm.reveal_phone(this, event)" title="Xem số đầy đủ">
						<span class="js__ph-text text-nowrap">' . $_pmask . '</span> <i class="bx bx-show js__ph-eye fs-13"></i></span>'
					: '<span class="crm-phone-wrap"><span class="js__ph-text text-nowrap">' . $_pmask . '</span></span>';
			} else {
				$_phWrap = '<span>—</span>';
			}
			#
			if (!isset($arr_property_cached[$status_id])) {
				if ($deviceType == "phone") {
					$arr_property_cached[$status_id] = $clsProperty->getLabel($status_id, " mr-1", false);
				} else {
					$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id, $oneStatus);
				}
			}
			$status_name = $arr_property_cached[$status_id];
			$list_customers[$k_cus]['status_name'] = $arr_property_cached[$status_id];
			#
			if ($resource_id > 0 && !isset($arr_property_cached[$resource_id])) {
				$arr_property_cached[$resource_id] = $clsProperty->getTitle($resource_id);
			}
			$resource_name = $arr_property_cached[$resource_id];
			$list_customers[$k_cus]['resource_name'] = $resource_name;
			#
			$blocktype_name = "--";
			if ($blocktype_id > 0) {
				if (isset($arr_property_cached[$blocktype_id])) {
					$blocktype_name = $arr_property_cached[$blocktype_id];
				} else {
					$arr_property_cached[$blocktype_id] = $clsProperty->getTitleCache('_BLOCK_TYPE', $blocktype_id);
					$blocktype_name = $arr_property_cached[$blocktype_id];
				}
			}
			$list_customers[$k_cus]['blocktype_name'] = $blocktype_name;
			// Link Facebook khách (more_information.facebook) — hiện cạnh Zalo (desktop + mobile). Chấp nhận URL đầy đủ hoặc username/handle.
			$_fbUrl = '';
			if (is_array($more_info) && !empty($more_info['facebook'])) {
				$_fbRaw = trim($more_info['facebook']);
				$_fbUrl = (stripos($_fbRaw, 'http') === 0) ? $_fbRaw : 'https://www.facebook.com/' . ltrim($_fbRaw, '/@');
			}
			$_fbLink = ($_fbUrl !== '') ? '<a href="' . htmlspecialchars($_fbUrl, ENT_QUOTES) . '" target="_blank" class="fb_chat" title="Xem Facebook"><i class="bx bxl-facebook-circle"></i></a>' : '';
			$list_customers[$k_cus]['facebook_url'] = $_fbUrl;
			if ($clsISO->checkItemInArray("name", $arr_field)) {
				// B1.2 — ô Tên theo mockup: avatar initial + tên + badge Hot/Warm/Cold (theo lead_score F2)
				$_nm_full = !empty($cus['name']) ? htmlspecialchars(ucfirst($cus['name']), ENT_QUOTES) : "Không tên"; // escape: chống stored-XSS từ tên KH (ô Tên desktop)
				$_nm_ini = htmlspecialchars(mb_strtoupper(mb_substr((trim($cus['name']) !== '' ? $cus['name'] : '?'), 0, 1, 'UTF-8'), 'UTF-8'), ENT_QUOTES);
				$_ls_b = (is_array($more_info) && isset($more_info['lead_score'])) ? (int) $more_info['lead_score'] : 0;
				$_score_badge = '';
				if ($_ls_b >= _CRM_LEAD_SCORE_HOT) {
					$_score_badge = '<span class="crm-badge crm-badge--hot ms-1"><i class="bx bxs-hot"></i>Hot</span>';
				} else if ($_ls_b >= 30) {
					$_score_badge = '<span class="crm-badge crm-badge--warm ms-1"><i class="bx bx-sun"></i>Warm</span>';
				} else if ($_ls_b > 0) {
					$_score_badge = '<span class="crm-badge crm-badge--cold ms-1"><i class="material-icons-outlined no-translate">ac_unit</i>Cold</span>';
				}
				$arr_data["name"] = '<td class="text-left">
					<div class="d-flex align-items-center gap-2">
						<span class="avatar avatar-sm flex-shrink-0"><span class="avatar-initial rounded-circle bg-label-primary">' . $_nm_ini . '</span></span>
						<div class="min-w-0">
							<div class="d-block text-nowrap"><a href="javascript:void(0);" onClick="$Core.crm.open_customer(this,event)" class="link goLink font-bold view_customer fs-6" route="/customer/' . $customer_id . '/overview" customer_id="' . $customer_id . '">' . $_nm_full . '</a>' . $_score_badge . $title_purpose . '</div>
							<div class="d-flex align-items-center gap-1 fs-12 text-muted crm-name-phone">
								' . (!empty($cus["phone"]) ? '<i class="bx bx-phone fs-14"></i>' . $_phWrap . '<a href="https://zalo.me/' . $cus["phone"] . '" target="_blank" class="zalo_chat" title="Chat Zalo"></a>' : '<span>—</span>') . $_fbLink . '
							</div>
						</div>
					</div>
				</td>';
			}
			if ($clsISO->checkItemInArray("phone", $arr_field)) {
				$arr_data["phone"] = '<td class="text-left">
					<div class="d-flex gap-1 align-items-center">' .
					(!empty($cus['phone']) ? ('<a href="https://zalo.me/' . $cus['phone'] . '" target="_blank" class="zalo_chat"></a>
							<a href="tel:' . $cus['phone'] . '" class="js_clicktocall text-nowrap text-body fs-13">
							<img src="' . URL_IMAGES . '/phone-icon.png"></a>' . $_phWrap . '') : '---') . $_fbLink . '
					</div>
				</td>';
			}
			if ($clsISO->checkItemInArray("admin", $arr_field)) {
				$arr_data["admin"] = '<td class="text-center">
					<a href="javascript:void(0);" ' . (($cus['admin_id'] == $profile_id || $cus['user_id'] == $profile_id || $clsCustomer->isFullPermiss() || $clsISO->checkPermission('admin_assign_client')) ? 'onClick="$Core.crm.change_assigned(this, event)"' : '')
					. ' customer_id="' . $customer_id . '" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id=' . $cus['admin_id'] . '" data-toggle="webui-popover" data-trigger="hover" data-width="350">
						<img class="avatar avatar-xs mr-2 rounded-pill" src="' . $clsProfile->getAvatar($cus['admin_id']) . '">
						<span class="cre">' . $core->makeIcon($icon) . '</span>
					</a>
				</td>';
			}
			if ($clsISO->checkItemInArray("follow-ups", $arr_field)) {
				// $html_stocks . $html_follow_ups . $html_tags
				$arr_data["follow-ups"] = '<td style="width:55px" class="text-center">
					<a onClick="$Core.crm.view_activity(this, event);" customer_id="' . $customer_id . '" class="btn btn-sm btn-icon btn-outline-default text-main">' . $total_followups . '</a>
				</td>
				<td class="text-left">
					' . $html_stocks . $html_follow_ups . '
				</td>';
			}
			if ($clsISO->checkItemInArray("resource", $arr_field)) {
				$arr_data["resource"] = '<td class="text-left text-nowrap">' . $resource_name . '</td>';
			}
			if ($clsISO->checkItemInArray("campaign", $arr_field)) {
				$campaign_group_name = "";
				$title_campaign_arrs = array();
				if (!empty($campaign_arrs)) {
					foreach ($campaign_arrs as $campaign_id) {
						if (isset($arr_campaign_cached[$campaign_id])) {
							$title_campaign_arrs[] = '<a data-bs-toggle="tooltip" title="Lọc khách hàng" campaign_id="' . $campaign_id . '" 
								class="text-muted cursor-pointer" onClick="$Core.crm.set_campaign(this, event)">
								' . $arr_campaign_cached[$campaign_id] . '
							</a>';
						} else {
							$arr_campaign_cached[$campaign_id] = $clsCampaign->getTitle($campaign_id);
							$title_campaign_arrs[] = '<a data-bs-toggle="tooltip" title="Lọc khách hàng" campaign_id="' . $campaign_id . '" 
								class="text-muted cursor-pointer" onClick="$Core.crm.set_campaign(this, event)">
								' . $arr_campaign_cached[$campaign_id] . '
							</a>';
						}
					}
				} else {
					$title_campaign_arrs[] = "--";
				}
				$campaign_group_name = implode(',', $title_campaign_arrs);
				$list_customers[$k_cus]['title_campaign_arrs'] = $campaign_group_name;
				$arr_data["campaign"] = '<td class="text-left text-nowrap">' . $campaign_group_name . '</td>';
			}
			if ($clsISO->checkItemInArray("blocktype", $arr_field)) {
				$arr_data["blocktype"] = '<td class="text-left text-nowrap">' . $blocktype_name . '</td>';
			}
			if ($clsISO->checkItemInArray("begin_need", $arr_field)) {
				$arr_data["begin_need"] = '<td class="align-center">' . htmlspecialchars($cus['begin_need']) . '</td>';
			}
			if ($clsISO->checkItemInArray("agency_name", $arr_field)) {
				$agent_id = $core->get_field($more_info, "agent_id", 0);
				$agency_name = ($agent_id > 0 && isset($arr_property_cached[$agent_id])) ? $arr_property_cached[$agent_id]['title'] : "";
				$arr_data["agency_name"] = '<td class="align-center">' . $agency_name . '</td>';
			}
			if ($clsISO->checkItemInArray("gender_name", $arr_field)) {
				$gender_id = $core->get_field($more_info, "gender_id", 0);
				$gender_name = ($gender_id > 0 && isset($arr_property_cached[$gender_id])) ? $arr_property_cached[$gender_id]['title'] : "";
				$arr_data["gender_name"] = '<td class="align-center">' . $gender_name . '</td>';
			}
			#bedroom
			if ($clsISO->checkItemInArray("bedroom", $arr_field)) {
				$arrBedroom = [];
				if (!empty($list_bedroom_ids)) {
					foreach ($list_bedroom_ids as $bedroom_id) {
						if (!empty($arr_bedroom[$bedroom_id])) {
							$arrBedroom[] = $arr_bedroom[$bedroom_id]['title'];
						}
					}
				}
				$arr_data["bedroom"] = '<td class="align-center">' . (!empty($arrBedroom) ? implode(", ", $arrBedroom) : "---") . '</td>';
			}
			if ($clsISO->checkItemInArray("task_current", $arr_field)) {
				$task_current_label = '--';
				if ($task_current_id > 0 && isset($arr_crm_task_cached[$task_current_id])) {
					$task_current_label = $clsSetting->getLabel($task_current_id, $arr_crm_task_cached[$task_current_id]);
					// "(+N)" số lần đã gọi/nhắn — gắn LUÔN vào trong chip tác nghiệp ("Gọi điện (+1)"), không tách span ngoài.
					$_attempt_badge = crm_task_attempt_badge(crm_task_attempt_count($more_info, $task_current_id));
					if ($_attempt_badge !== '') {
						$task_current_label = preg_replace('/<\/label>/', $_attempt_badge . '</label>', $task_current_label, 1);
					}
				}
				$arr_data["task_current"] = '<td id="task_current_' . $customer_id . '" class="align-center task_current task_current_' . $customer_id . '">
					' . $task_current_label . '
				</td>';
			}
			if ($clsISO->checkItemInArray("task_result", $arr_field)) {
				$arr_data["task_result"] = '<td id="task_result_' . $customer_id . '" class="align-center task_result task_result_' . $customer_id . '">
					' . (!empty($_isRecvPending) ? '<span class="text-muted small">— chưa nhận —</span>' : crm_task_result_select_html($customer_id, $task_current_id, $task_result_id, $arr_crm_result_cached, $task_result_ids_by_task)) . '
				</td>';
			}
			if ($clsISO->checkItemInArray("task_next", $arr_field)) {
				$arr_data["task_next"] = '<td id="task_next_' . $customer_id . '" class="task_next task_next_' . $customer_id . ' align-center">
					' . crm_task_next_cell_html($customer_id, $task_next_id, $arr_crm_task_cached, $clsSetting) . '
				</td>';
			}
			if ($clsISO->checkItemInArray("waiting_time", $arr_field)) {
				$arr_data["waiting_time"] = '<td id="waiting_time_' . $customer_id . '" class="waiting_time waiting_time_' . $customer_id . ' align-center">
					' . crm_task_waiting_cell_html($customer_id, $time_receipt, $task_next_id) . '
				</td>';
			}
			#block
			if ($clsISO->checkItemInArray("block", $arr_field)) {
				$arr_blocks = [];
				if (!empty($list_block_ids)) {
					foreach ($list_block_ids as $block_id) {
						if (!empty($arr_block_cached[$block_id])) {
							$arr_blocks[] = $arr_block_cached[$block_id];
						}
					}
				}
				// $clsISO->print_pre($arr_blocks); die();
				$arr_data["block"] = '<td class="align-center">
					' . (!empty($arr_blocks) ? implode(", ", $arr_blocks) : "---") . '
				</td>';
			}
			if ($clsISO->checkItemInArray("status", $arr_field)) {
				// U-P1a: "Phân loại khách" inline 1-click qua <select> -> update_field (ghi qua changeStatus/D3). Chỉ chủ KH hoặc full-permiss được sửa.
				if ($cus['admin_id'] == $profile_id || $clsCustomer->isFullPermiss()) {
					$_optStatus = '';
					if (!empty($arr_status_cached)) {
						foreach ($arr_status_cached as $_oSt) {
							$_sid = (int) $_oSt[$clsProperty->pkey];
							if (!empty($_oSt['is_trash']) && $_sid != $status_id) {
								continue;
							} // ẩn status đã xoá khỏi dropdown (giữ status hiện tại của KH nếu nó đã bị trash)
							$_optStatus .= '<option value="' . $_sid . '" data-bg="' . htmlspecialchars($_oSt['bgcolor']) . '"' . ($_sid == $status_id ? ' selected' : '') . '>' . htmlspecialchars($_oSt['title']) . '</option>';
						}
					}
					$arr_data["status"] = '<td class="text-left text-nowrap"><select class="form-select form-select-sm crm-inline-status w-100 border-0 text-white fw-bold" style="background-color:' . (!empty($oneStatus['bgcolor']) ? htmlspecialchars($oneStatus['bgcolor']) : '#6c757d') . '" customer_id="' . $customer_id . '" data-prev="' . $status_id . '" onChange="$Core.crm.inline_change_status(this, event)">' . $_optStatus . '</select></td>';
				} else {
					$arr_data["status"] = '<td class="text-left text-nowrap">
						<label class="w-100 badge" style="background:' . $oneStatus['bgcolor'] . ' !important; color:' . $oneStatus['textcolor'] . ' !important">' . $status_name . '</label>
					</td>';
				}
			}
			#list share
			if ($clsISO->checkItemInArray("list_share", $arr_field)) {
				$arr_shares = [];
				$list_share_ids = array_unique($list_share_ids);
				if (!empty($list_share_ids)) {
					foreach ($list_share_ids as $share_id) {
						if (isset($arr_profile_cached[$share_id]) && $admin_id != $share_id) {
							$arr_shares[] = '<img data-bs-toggle="tooltip" class="avatar avatar-xxs rounded-pill" alt="' . $arr_profile_cached[$share_id]["full_name"] . '" 
								title="' . $arr_profile_cached[$share_id]["full_name"] . '" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" src="' . $clsProfile->getAvatar($share_id, $arr_profile_cached[$share_id], 30, 30) . '" />';
						}
					}
				}
				$arr_data["list_share"] = '<td class="align-center">
					' . (!empty($arr_shares) ? '<div class="avatar-group">' . implode(",", $arr_shares) . '</div>' : "--") . '
				</td>';
			}
			if ($clsISO->checkItemInArray("email", $arr_field)) {
				$arr_data["email"] = '<td class="align-center">' . (!empty($cus['email']) ? $cus['email'] : "---") . '</td>';
			}
			if ($clsISO->checkItemInArray("address", $arr_field)) {
				$arr_data["address"] = '<td class="align-center">' . (!empty($cus['address']) ? $cus['address'] : "---") . '</td>';
			}
			#country
			if ($clsISO->checkItemInArray("country", $arr_field)) {
				$country_id = (int) $cus['country_id'];
				if ($country_id > 0 && !isset($arr_country_cached[$country_id])) {
					$arr_country_cached[$country_id] = $clsCountry->getTitle($country_id);
				}
				$arr_data["country"] = '<td class="align-center text-nowrap">
					' . (!empty($arr_country_cached[$country_id]) ? $arr_country_cached[$country_id] : "---") . '
				</td>';
			}
			#city
			if ($clsISO->checkItemInArray("city", $arr_field)) {
				$city_id = (int) $cus['city_id'];
				if ($city_id > 0 && !isset($arr_city_cached[$city_id])) {
					$arr_city_cached[$city_id] = $clsCity->getTitle($city_id);
				}
				$arr_data["city"] = '<td class="align-center text-nowrap">
					' . (!empty($arr_city_cached[$city_id]) ? $arr_city_cached[$city_id] : "---") . '
				</td>';
			}
			#birdthday			
			if ($clsISO->checkItemInArray("birthday", $arr_field)) {
				$arr_data["birthday"] = '<td class="align-center">' . (!empty($cus['birthday']) ? $clsISO->convertTimeToText($cus['birthday'], 0, "/") : '--') . '</td>';
			}
			#customer_type			
			if ($clsISO->checkItemInArray("customer_type", $arr_field)) {
				$arrType = [];
				if (!empty($list_type_ids)) {
					foreach ($list_type_ids as $type_id) {
						if (!empty($arr_customer_type[$type_id])) {
							$arrType[] = $arr_customer_type[$type_id]['title'];
						}
					}
				}
				$arr_data["customer_type"] = '<td class="align-center">
					' . (!empty($arrType) ? @implode(", ", $arrType) : "--") . '
				</td>';
			}
			#finance			
			if ($clsISO->checkItemInArray("finance", $arr_field)) {
				$finance_id = $cus['finance_id'];
				$arr_data["finance"] = '<td class="align-center">
					' . (!empty($arr_finance[$finance_id]) ? $arr_finance[$finance_id]['title'] : "---") . '
				</td>';
			}
			#purpose			
			if ($clsISO->checkItemInArray("purpose", $arr_field)) {
				$arrPurpose = [];
				if (!empty($list_purpose_ids)) {
					foreach ($list_purpose_ids as $purpose_id) {
						if (!empty($arr_purpose[$purpose_id])) {
							$arrPurpose[] = $arr_purpose[$purpose_id]['title'];
						}
					}
				}
				$arr_data["purpose"] = '<td class="align-center">' . (!empty($arrPurpose) ? implode(", ", $arrPurpose) : "---") . '</td>';
			}
			#need			
			if ($clsISO->checkItemInArray("need", $arr_field)) {
				$arrNeed = [];
				if (!empty($list_need_ids)) {
					foreach ($list_need_ids as $need_id) {
						if (!empty($arr_need[$need_id])) {
							$arrNeed[] = $arr_need[$need_id]['title'];
						}
					}
				}
				$arr_data["need"] = '<td class="align-center">' . (!empty($arrNeed) ? implode(", ", $arrNeed) : "---") . '</td>';
			}
			if ($clsISO->checkItemInArray("reg_date", $arr_field)) {
				$arr_data["reg_date"] = '<td class="text-left text-nowrap">' . (!empty($cus['reg_date']) ? $clsISO->getTimeAgo($cus['reg_date']) : '--') . '</td>';
			}
			if ($clsISO->checkItemInArray("user_id", $arr_field)) {
				$_uploader_id = (int) $cus['user_id'];
				$arr_data["user_id"] = '<td class="text-center">' . ($_uploader_id > 0 ? '<a href="javascript:void(0);" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id=' . $_uploader_id . '" data-toggle="webui-popover" data-trigger="hover" data-width="350"><img class="avatar avatar-xs mr-2 rounded-pill" src="' . $clsProfile->getAvatar($_uploader_id) . '"></a>' : '--') . '</td>';
			}
			// F2 — cột Điểm tiềm năng (lead_score) từ more_information (cron F2a điền). 🔥 khi >= ngưỡng Hot.
			if ($clsISO->checkItemInArray("lead_score", $arr_field)) {
				$_ls = (int) $core->get_field($more_info, "lead_score", 0);
				$_hot = ($_ls >= _CRM_LEAD_SCORE_HOT);
				$_lscls = $_hot ? "bg-label-danger" : ($_ls >= 30 ? "bg-label-warning" : "bg-label-secondary");
				$arr_data["lead_score"] = '<td class="text-center text-nowrap"><span class="badge ' . $_lscls . '">' . ($_hot ? "🔥 " : "") . $_ls . '</span></td>';
			}
			$lst_data[$customer_id] = [
				"customer_id" => $customer_id,
				"icon_archived" => $icon_archived,
				"total_followups_next" => $total_followups_next,
				"data_html" => $arr_data
			];
			++$ii;
		}
		// Xác nhận nhận số: đánh dấu khách mà người đang xem CHƯA xác nhận nhận (1 query). Áp dụng MỌI người nhận, kể cả full-permiss.
		$assign_pending_map = array();
		if (!empty($lst_data) && (int) $profile_id > 0) {
			$clsCustomerAssign = new CustomerAssign();
			$assign_pending_map = $clsCustomerAssign->pendingIdsIn($profile_id, array_keys($lst_data));
		}
		$lst_data = array_map(function ($item) use ($arr_field, $assign_pending_map) {
			$sorted_item = $item; // Giữ nguyên cus_id
			$sorted_item["is_pending"] = isset($assign_pending_map[(int) $item["customer_id"]]) ? 1 : 0;
			$sorted_item["data_html"] = [];
			foreach ($arr_field as $field) {
				if (isset($item["data_html"][$field])) {
					$sorted_item["data_html"][$field] = $item["data_html"][$field];
				}
			}
			return $sorted_item;
		}, $lst_data);
		$smarty->assign("lst_data", $lst_data);
	} else {
		$html_empty = '<div class="crm-empty-state"><div class="crm-empty-state__ico"><i class="bx bx-user-x"></i></div><div class="crm-empty-state__title">Chưa có khách hàng nào</div><div class="crm-empty-state__desc">Thử đổi bộ lọc hoặc thêm khách mới để bắt đầu.</div></div>';
		$smarty->assign("html_empty", $html_empty);
	}
	#
	$html_briefs = $html_warning = "";
	if ($action != "load_more") {
		if (!empty($arr_status_cached)) {
			$ii = 1;
			// Q6: gom đếm theo status_id thành 1 GROUP BY (thay N countItem per-status)
			$funnel_status_counts = array();
			if ($useJoinRelation) {
				$_grpRows = $dbconn->GetAll("SELECT `status_id`, COUNT(DISTINCT `customer_id`) AS `c` FROM `{$clsCustomer->tbl}` {$joinSql} WHERE {$cnd} GROUP BY `status_id`");
			} else {
				$_grpRows = $dbconn->GetAll("SELECT `status_id`, COUNT(*) AS `c` FROM `{$clsCustomer->tbl}` WHERE {$cnd} GROUP BY `status_id`");
			}
			if (!empty($_grpRows)) {
				foreach ($_grpRows as $_gr) {
					$funnel_status_counts[(int)$_gr['status_id']] = (int)$_gr['c'];
				}
			}
			// $clsISO->print_pre($_grpRows); die();
			foreach ($arr_status_cached as $key => $val) {
				$property_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if ((int) $core->get_field($more_information, "is_funnel_active", 0) == 1) {
					$total_customers = isset($funnel_status_counts[(int)$property_id]) ? $funnel_status_counts[(int)$property_id] : 0;
					$html_briefs .= '<div onClick="$Core.crm.set_status(this,event)" status_id="' . $property_id . '" 
						class="funnel-stage cursor-pointer" style="background:' . $val['bgcolor'] . '">
						<span class="label">' . $val['title'] . '</span>
						<span class="value">' . $total_customers . ' </span>
					</div>';
				}
				++$ii;
			}
		}
	}
	#
	$smarty->assign("typeHolder", $typeHolder);
	$smarty->assign("date_id", $date_id);
	$smarty->assign("holderG", $holderG);
	$smarty->assign("is_checkbox", $is_checkbox);
	$smarty->assign("sort_by", $sort_by);
	$smarty->assign("list_customers", $list_customers);
	$smarty->assign("per_page", $per_page);
	$smarty->assign("current_page", $current_page);
	$smarty->assign("total_page", $total_page);
	// Output
	$html = $core->build("_ajax.list_customer.tpl");
	echo json_encode(array_merge($more, array(
		'html' => $html,
		'cond' => $cond,
		'from_notify' => $from_notify,
		'current_page'	=> $current_page,
		'total_assign' => $total_assign,
		'total_manage' => $total_manage,
		'total_team' => $total_team,
		'total_converted' => $total_converted,
		'total_page'	=> $total_page,
		'total_record'	=> $total_record,
		'per_page'	=> $per_page,
		'action'	=> $action,
		'html_briefs'	=> $html_briefs
	)), JSON_UNESCAPED_UNICODE);
	die();
}
function default_load_converted_rates(){
	global $smarty, $core, $clsISO, $oneProfile, $profile_id, $dbconn;
	global $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsCustomerHistory = new CustomerHistory();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$html = "";
	if ($clsISO->checkPermissionGroup('DIRECTOR')) {
		$query = "SELECT
			`s`.`from_status_id`,
			`s`.`to_status_id`,
			COUNT(DISTINCT `s`.`customer_id`) AS `total_move`,
			b.total_from,
			ROUND(
				COUNT(DISTINCT `s`.`customer_id`) / `b`.`total_from` * 100,
				0
			) AS `conversion_rate`
		FROM {$clsCustomerHistory->tbl} `s`
		JOIN (
			SELECT
				`from_status_id`,
				COUNT(DISTINCT customer_id) AS `total_from`
			FROM {$clsCustomerHistory->tbl}
			WHERE `from_status_id` > 0
			GROUP BY `from_status_id`
		) `b` ON `s`.`from_status_id` = `b`.`from_status_id`
		WHERE `s`.`from_status_id` > 0
		GROUP BY `s`.`from_status_id`, s.to_status_id
		ORDER BY `s`.`from_status_id`, `s`.`to_status_id`;";
	} else {
		$query = "SELECT
			s.from_status_id,
			s.to_status_id,
			COUNT(DISTINCT s.customer_id) AS total_move,
			b.total_from,
			ROUND(
				COUNT(DISTINCT s.customer_id) / b.total_from * 100,
				2
			) AS conversion_rate
		FROM {$clsCustomerHistory->tbl} s
		JOIN {$clsCustomer->tbl} c ON c.customer_id = s.customer_id
		JOIN (
			SELECT
				h.from_status_id,
				COUNT(DISTINCT h.customer_id) AS total_from
			FROM {$clsCustomerHistory->tbl} h
			JOIN {$clsCustomer->tbl} c2 ON c2.customer_id = h.customer_id
			WHERE c2.admin_id = {$profile_id}
			  AND h.from_status_id > 0
			GROUP BY h.from_status_id
		) b ON s.from_status_id = b.from_status_id
		WHERE c.admin_id = {$profile_id}
		  AND s.from_status_id > 0
		GROUP BY s.from_status_id, s.to_status_id
		ORDER BY s.from_status_id;";
	}
	// $dbconn->debug = true;
	$list = $dbconn->getAll($query);
	if (!empty($list)) {
		$ii = 0;
		$arr_property_cached = array();
		$tmp = $clsProperty->getCacheItems("CUSTOMER_STATUS");
		if (!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach ($list as $key => $val) {
			$from_status_id = (int) $val['from_status_id'];
			$to_status_id = (int) $val['to_status_id'];
			if ($from_status_id != $to_status_id) {
				$html .= '<div class="d-flex flex-column mb-1' . ($ii <= 4 ? '' : ' d-none toggleRow') . '">
					<div class="d-flex align-items-center justify-content-between text-fs-13 mb-0">
						<span class="text-muted">' . $arr_property_cached[$from_status_id] . ' (' . $val['total_from'] . ')</span>
						<span class="">' . $arr_property_cached[$to_status_id] . ' (' . $val['total_move'] . ')</span>
					</div>
					<div class="progress w-100 h-px-12">
						<div class="progress-bar bg-info" role="progressbar" style="width:' . $val['conversion_rate'] . '%">' . $val['conversion_rate'] . '%</div>
					</div>
				</div>';
				++$ii;
			}
		}
		$html .= '<div class="d-flex">
			<a onClick="$Core.util.toggle_tr(this, event)" toCls="toggleRow" class="btn btn-sm btn-outline-default">Xem thêm
				<i class=\'bx bx-chevron-down\'></i>
			</a>
		</div>';
	} else {
		$html .= '<div class="d-flex p-3 flex-column align-items-center justify-content-center">
			<img src="' . URL_IMAGES . '/empty.svg" class="w-px-100 mb-2" />
			<p class="text-muted">Chưa có khách hàng nào được chuyển đổi!</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	));
	die();
}
function default_load_report_campaign(){
	global $smarty, $core, $clsISO, $oneProfile, $profile_id, $dbconn;
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsGroupProfile = new GroupProfile();
	$useJoinShare = false;
	$useJoinCampaign = false;
	$useJoinRelation = false;
	$joinSql = "";
	###
	$uid = $clsISO->getUniqid();
	$month = (int) Input::post('month', 0);
	$year = (int) Input::post('year', date('Y'));
	$date_type = Input::post('date_type', '_month');
	$resource_id = (int) Input::post('resource_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	$group_id = (int) Input::post('group_id', 0);
	if ($group_id > 0 && !in_array($profile_id, _PROFILE_CRM_SUPER_ID)) {
		$group_id = 0;
	} // P0-3
	$admin_id = (int) Input::post('admin_id', 0, true);
	// P0-5: chống IDOR admin_id (báo cáo chiến dịch) — full-permiss / quản lý nhóm (rep thuộc nhóm) mới được lọc; còn lại bỏ qua.
	if ($admin_id > 0 && !in_array($profile_id, _PROFILE_CRM_SUPER_ID)) {
		$_admin_ok = false;
		if ($clsCustomer->isTeamManager()) {
			$_gpChk = new GroupProfile();
			$_gpRows = $_gpChk->getAll("`manager_profile_id`='{$profile_id}' AND `is_trash`=0", "`list_profile_id`");
			if (!empty($_gpRows)) {
				foreach ($_gpRows as $_gpR) {
					$_repArr = !empty($_gpR['list_profile_id']) ? $clsISO->getArrayByTextSlash($_gpR['list_profile_id']) : array();
					foreach ($_repArr as $_rid) {
						if ((int) $_rid === $admin_id) {
							$_admin_ok = true;
							break 2;
						}
					}
				}
			}
		}
		if (!$_admin_ok) {
			$admin_id = 0;
		}
	}
	$shareJoinAdminId = ($admin_id > 0) ? $admin_id : $profile_id;
	$useJoinShare = true;
	$joinSql .= " LEFT JOIN (
		SELECT `customer_id` AS `customer_ref`, `meta_id` AS `share_admin_id`
		FROM `{$clsCustomerMeta->tbl}`
		WHERE `meta_type`='share' AND `meta_id`='{$shareJoinAdminId}'
		GROUP BY `customer_id`, `meta_id`
	) AS `cs` ON `cs`.`customer_ref`=`customer_id`";
	if ($campaign_id > 0) {
		$useJoinCampaign = true;
		$joinSql .= " LEFT JOIN (
			SELECT `customer_id` AS `customer_ref`, `meta_id` AS `rel_campaign_id`
			FROM `{$clsCustomerMeta->tbl}`
			WHERE `meta_type`='campaign' AND `meta_id`='{$campaign_id}'
			GROUP BY `customer_id`, `meta_id`
		) AS `cc` ON `cc`.`customer_ref`=`customer_id`";
	}
	$useJoinRelation = ($useJoinShare || $useJoinCampaign) ? true : false;
	$_tp = Input::get('tp', "chart");
	$cond = "`is_trash`=0";
	if ($resource_id > 0) {
		$cond .= " AND `resource_id`='{$resource_id}'";
	}
	if ($campaign_id > 0) {
		$cond .= " AND `cc`.`rel_campaign_id`='{$campaign_id}'";
	}
	if ($admin_id > 0) {
		$cond .= " AND (`admin_id`<>'{$admin_id}' AND `cs`.`share_admin_id`='{$admin_id}')";
	} else {
		$cond .= " AND (`admin_id`='{$profile_id}' OR `user_id`='{$profile_id}' OR `cs`.`share_admin_id`='{$profile_id}')";
	}
	if ($group_id > 0) {
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array();
		if (!empty($list_profile_arrs)) {
			$cond .= " AND (`admin_id` in (" . implode(',', $list_profile_arrs) . "))";
		}
	}
	//	echo $cond;die;
	$list_ranges = array();
	if ($month == 0) {
		$f = '%m/%Y';
		$format_time = '%Y';
		if ($year == date("Y")) {
			if (date('n') >= 5) {
				$start_month = strtotime('first day of january this year 00:00:00');
				$to_month = time();
			} else {
				$to_month = time();
				$start_month = strtotime('-12 months', $to_month);
			}
		} else {
			$start_month = strtotime(date(sprintf("%s-01-01 00:00:00", $year)));
			$to_month = strtotime(date(sprintf("%s-12-31 23:59:59", $year)));
		}
		for ($i = $start_month; $i <= $to_month; $i = strtotime('+1 month', $i)) {
			$list_ranges[] = date('m/Y', $i);
		}
		$time = $year;
	} else {
		$f = '%d/%m/%Y';
		$format_time = '%m/%Y';
		$number_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		for ($i = 1; $i <= $number_day; $i++) {
			$list_ranges[] = sprintf('%s/%s/%s', $clsISO->parseNumber($i), $clsISO->parseNumber($month), $year);
		}
		$time = $clsISO->parseNumber($month) . "/" . $year;
	}
	if ($useJoinRelation) {
		$query = "SELECT 
			`reg_date`,
			`status_id`,
			FROM_UNIXTIME(`reg_date`,'{$f}') as `date_time`,
			FROM_UNIXTIME(`reg_date`,'{$format_time}') as `format_time`,
			COUNT(DISTINCT `customer_id`) AS `total_customer`
		FROM `{$clsCustomer->tbl}` {$joinSql}
		WHERE {$cond}
		GROUP BY FROM_UNIXTIME(`reg_date`,'{$f}'),`status_id`";
		$lstCustomer = $dbconn->GetAll($query);
	} else {
		$lstCustomer = $clsCustomer->getAll($cond . " GROUP BY FROM_UNIXTIME(`reg_date`,'{$f}'),`status_id`", "`reg_date`,`status_id`,FROM_UNIXTIME(`reg_date`,'{$f}') as `date_time`,FROM_UNIXTIME(`reg_date`,'{$format_time}') as `format_time`, COUNT(`customer_id`) AS `total_customer`");
	}
	$arr_total_time = $arr_total_status = [];
	foreach ($lstCustomer as $key => $val) {
		if (!isset($arr_total_time[$val["date_time"]])) {
			$arr_total_time[$val["date_time"]] = (int)$val["total_customer"];
		} else {
			$arr_total_time[$val["date_time"]] += (int)$val["total_customer"];
		}
		if (!isset($arr_total_status[$val["format_time"]][$val["status_id"]])) {
			$arr_total_status[$val["format_time"]][$val["status_id"]] = (int)$val["total_customer"];
		} else {
			$arr_total_status[$val["format_time"]][$val["status_id"]] += (int)$val["total_customer"];
		}
	}
	//	$clsISO->print_pre($arr_total_time);die;
	if ($_tp == "chart") {
		$data = $dataPoints = $dataTotalPoints = $barChartData = array();
		$barChartData['animationEnabled'] = true;
		//		$data['axisY']['labelFormatter'] = 1;
		foreach ($list_ranges as $date) {
			$tmp = explode('/', $date);
			$end_day = cal_days_in_month(CAL_GREGORIAN, $tmp[0], $tmp[1]);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $tmp[0], $tmp[1]));
			$field = "{$clsCustomer->pkey},`totalgrand`";
			//		$dbconn->debug=true;
			$total_cus = (!empty($arr_total_time[$date])) ? $arr_total_time[$date] : 0;
			$dataPoints[] = array(
				'label'	=> sprintf('%s', $date),
				'y'	=> (int)$total_cus,
				'indexLabel' => $total_cus . " KH"
			);
		}
		$data['type'] = 'spline';
		$data['indexLabel'] = '{y}';
		$data['title']['fontColor'] = 'rgb(159,34,58)';
		$data['toolTipContent'] = '{label}<br /> Tổng: {y} khách hàng';
		$data['indexLabelPlacement'] = 'inside';
		$data['indexLabelFontColor'] = '#36454F';
		$data['dataPoints'] = $dataPoints;
		$barChartData['data'] = $data;
		$html = '<div id="' . $uid . '" class="chartContainer p-0 mb-3" style="height:200px"></div>';
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html,
			'html_briefs' => $html_briefs,
			'drawchart' => '1',
			'barChartData' => $barChartData
		));
		die();
	} else {
		$arr_status_cached = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
		$html = "";
		$arr_total_status_time = !empty($arr_total_status[$time]) ? $arr_total_status[$time] : [];
		if (!empty($arr_status_cached)) {
			$ii = 1;
			$html = '<div class="form-row row-cols-1 row-cols-md-2">';
			foreach ($arr_status_cached as $key => $val) {
				$property_id = $val[$clsProperty->pkey];
				$more_information = $val['more_information'];
				$total_customers = !empty($arr_total_status_time[$property_id]) ? $arr_total_status_time[$property_id] : 0;
				$html .= '<div class="col mb-2"><div class="p-2 d-flex justify-content-between fs-16 align-items-center" style="background:' . $val['bgcolor'] . ';color:#FFF">
					<span class="label fs-6">' . $val['title'] . '</span>
					<span class="value">' . $total_customers . ' </span>
				</div></div>';
				++$ii;
			}
			$html .= '</div>';
		}
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html,
		));
		die();
	}
}
function default_load_pop_action(){
	global $smarty, $core, $profile_id, $clsISO, $_LANG_ID;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$html_req = "";
	if ($clsISO->_DEV()) {
		$html = '<form class="p-2">
			<div class="form-group mb-2">
				<label class="form-label mb-1 text-nowrap">Tình trạng</label>
				<select name="status_id" class="form-control upd_field form-select">
					<option value="0">Tình trạng</option>
					' . $clsProperty->getSelectByProperty('CUSTOMER_STATUS', 0) . '
				</select>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Loại hình</label>
					<select name="blocktype_id" class="form-control upd_field form-select">
						<option value="0">Loại hình</option>
						' . $clsProperty->getSelectByProperty('_BLOCK_TYPE', 0) . '
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Nguồn khách</label>
					<select name="resource_id" class="form-control upd_field form-select">
						<option value="0">Nguồn khách</option>
						' . $clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0) . '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Người quản lý</label>
				<div class="clearfix"></div>
				<select class="iso-selectizeNotSearch upd_field" name="admin_id" data-width="100%" data-placeholder="Người quản lý" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff" data-width="100%" onChange="$Core.crm.loadAmountRequest(this,event)" toId="amount_request" ></select>
			</div>
			<div class="mt-2 d-none" id="amount_request"></div>
			<hr class="my-2" />
			<div class="alert alert-warning fs-12">
				<u>Lưu ý</u>: Khi nhấp áp dụng sẽ cập nhật những khách hàng đã chọn với field bên trên!
			</div>
			<button type="button" onClick="$Core.crm.do_action(this, event)" class="btn btn-block btn-primary">
				<i class="bx bx-check"></i>
				<span>Áp dụng</span>
			</button>
		</form>';
	} else {
		$html = '<form class="p-2">
			<div class="form-group mb-2">
				<label class="form-label mb-1 text-nowrap">Tình trạng</label>
				<select name="status_id" class="form-control upd_field form-select">
					<option value="0">Tình trạng</option>
					' . $clsProperty->getSelectByProperty('CUSTOMER_STATUS', 0) . '
				</select>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Loại hình</label>
					<select name="blocktype_id" class="form-control upd_field form-select">
						<option value="0">Loại hình</option>
						' . $clsProperty->getSelectByProperty('_BLOCK_TYPE', 0) . '
					</select>
				</div>
				<div class="col-6">
					<label class="form-label mb-1 text-nowrap">Nguồn khách</label>
					<select name="resource_id" class="form-control upd_field form-select">
						<option value="0">Nguồn khách</option>
						' . $clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0) . '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label mb-1">Người quản lý</label>
				<div class="clearfix"></div>
				<select class="iso-selectizeNotSearch upd_field" name="admin_id" data-width="100%" data-placeholder="Người quản lý" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff" data-width="100%"></select>
			</div>
			<hr class="my-2" />
			<div class="alert alert-warning fs-12">
				<u>Lưu ý</u>: Khi nhấp áp dụng sẽ cập nhật những khách hàng đã chọn với field bên trên!
			</div>
			<button type="button" onClick="$Core.crm.do_action(this, event)" class="btn btn-block btn-primary">
				<i class="bx bx-check"></i>
				<span>Áp dụng</span>
			</button>
		</form>';
	}
	// Return
	echo $html;
	die();
}
function default_do_action(){
	global $smarty, $core, $profile_id, $oneProfile, $dbconn, $clsISO, $_LANG_ID, $clsConfiguration;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsCustomerSales = new CustomerSales();
	$clsCustomerAssign = new CustomerAssign();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsRequestCus = new RequestCus();
	###
	$msg = "_error";
	$list_ids = Input::post('list_ids');
	$update_field = Input::post('update_field');
	#request buy
	$is_amount_paid = (int)Input::post('is_amount_paid', 0);
	$request_id = (int)Input::post('request_id', 0);
	unset($update_field["request_id"]);
	$total_remaining = $amount_paid = 0;
	if (!empty($is_amount_paid) && !empty($request_id)) {
		$oneRequestCus = $clsRequestCus->getOne($request_id);
		if (!empty($oneRequestCus)) {
			$more_request = $clsISO->to_array_json($oneRequestCus["more_information"]);
			$amount = $oneRequestCus["amount"];
			$total_paid = !empty($more_request["total_paid"]) ? (int)$more_request["total_paid"] : 0;
			$total_remaining = $amount - $total_paid;
		}
	}
	if (!empty($list_ids) && !empty($update_field)) {
		if (@array_key_exists("admin_id", $update_field) && !empty($update_field["admin_id"])) {
			$group_customer_sale = $clsConfiguration->getValue('group_customer_sale');
			$group_customer_sale = !empty($group_customer_sale) ? $clsISO->to_array_json($group_customer_sale) : [];
			$group_customer_sale[$profile_id][$clsISO->getUniqid()] = [
				'user_id'	=>	$profile_id,
				'admin_id'	=>	$update_field["admin_id"],
				"total"		=>	count($list_ids),
				'list_ids'	=>	$list_ids,
				'time'		=>	time()
			];
			$clsConfiguration->updateValue('group_customer_sale', json_encode($group_customer_sale, JSON_UNESCAPED_UNICODE));
		}
		$customer_insert = [];
		foreach ($list_ids as $customer_id) {
			// Chan thao tac neu nguoi dang lam CHUA xac nhan nhan khach nay (nguoi giao khong bi: ho khong phai nguoi nhan).
			if ($clsCustomer->isReceivePending($customer_id)) {
				continue;
			}
			$field = "`admin_id`,`user_id`,`name`,`phone`,`begin_need`,`status_id`,`resource_id`,`blocktype_id`,`more_information`";
			$oCustomer = $clsCustomer->getOne($customer_id, $field);
			$more_information = $oCustomer['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			##
			$s_field = 'admin_id';
			if (@array_key_exists($s_field, $update_field) && !empty($update_field[$s_field])) {
				$admin_id = (int) $update_field[$s_field]; // Admin chuyển tới
				if ($clsCustomer->isRootProfile()) {
					$list_share_arrs = array_merge(_PROFILE_SUPPER_ID, array(_PROFILE_TAT_ID));
				} else {
					$list_share_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'share');
				}
				$action_logs = $core->get_field($more_information, "action_logs", []);
				$arr_profile = $adminProfile = $adminProfileOld = array();
				if ($profile_id == $oCustomer[$s_field]) { // Mình chuyển KH của mình
					$adminProfileOld = $oneProfile;
				} else { // Mình chuyển KH của người khác
					$arr_profile[] = $oCustomer[$s_field];
				}
				$arr_profile[] = $admin_id;
				$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (" . implode(',', $arr_profile) . ")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
				if (!empty($tmp)) {
					foreach ($tmp as $key => $val) {
						if ($val[$clsProfile->pkey] == $admin_id) {
							$adminProfile = $val;
						} else if ($val[$clsProfile->pkey] == $oCustomer[$s_field]) {
							$adminProfileOld = $val;
						}
					}
					unset($tmp);
				}
				$customer_insert[] = [
					"customer_name"	=>	$oCustomer["name"],
					"phone"	=>	$oCustomer["phone"],
					"begin_need"	=>	$oCustomer["begin_need"],
				];
				$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($oCustomer[$s_field], $adminProfileOld), $clsProfile->getFullName($admin_id, $adminProfile));
				$action_logs[$clsISO->getUniqid()] = array(
					'content' => $content,
					'user_id' => $profile_id,
					'reg_date' => time()
				);
				$more_information['action_logs'] = $action_logs;
				if (!in_array($oCustomer[$s_field], $list_share_arrs)) {
					$list_share_arrs[] = $oCustomer[$s_field];
				}
				$use_globe = ($oCustomer['user_id'] == $admin_id) ? 0 : 1;
				$update_field['use_globe'] = $use_globe;
				#request buy
				if ($total_remaining > 0) {
					$more_information["is_customer_buy"] = 1;
				}
			} else {
				$arr_property = $content_logs = array();
				$is_status = $is_blocktype = $is_resource = 0;
				if (
					isset($update_field['status_id']) && (int) $update_field['status_id'] > 0
					&& (int) $update_field['status_id'] != $oCustomer['status_id']
				) {
					$is_status = 1;
					$arr_property[] = $update_field['status_id'];
				}
				if (
					isset($update_field['blocktype_id']) && (int) $update_field['blocktype_id'] > 0
					&& (int) $update_field['blocktype_id'] != $oCustomer['blocktype_id']
				) {
					$is_blocktype = 1;
					$arr_property[] = $update_field['blocktype_id'];
				}
				if (
					isset($update_field['resource_id']) && (int) $update_field['resource_id'] > 0
					&& (int) $update_field['resource_id'] != $oCustomer['resource_id']
				) {
					$is_resource = 1;
					$arr_property[] = $update_field['resource_id'];
				}
				if (!empty($arr_property)) {
					$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (" . implode(',', $arr_property) . ")", "{$clsProperty->pkey},`title`");
					if (!empty($tmp)) {
						foreach ($tmp as $key => $val) {
							if ($is_status == 1 && $val[$clsProperty->pkey] == (int) $update_field['status_id']) {
								$content_logs[] = sprintf('Tình trạng: %s', $val['title']);
							} else if ($is_blocktype == 1 && $val[$clsProperty->pkey] == (int) $update_field['blocktype_id']) {
								$content_logs[] = sprintf('Loại hình: %s', $val['title']);
							} else if ($is_resource == 1 && $val[$clsProperty->pkey] == (int) $update_field['resource_id']) {
								$content_logs[] = sprintf('Nguồn khách: %s', $val['title']);
							}
						}
					}
					$content = sprintf(
						'<strong>%s</strong> đã thay đổi <strong>%s</strong>',
						$clsProfile->getFullName($profile_id, $oneProfile),
						implode(',', $content_logs)
					);
					$action_logs[$clsISO->getUniqid()] = array(
						'content' => $content,
						'user_id' => $profile_id,
						'reg_date' => time()
					);
					$more_information['action_logs'] = $action_logs;
				}
			}
			$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
			if ($clsCustomer->updateOne($customer_id, $update_field)) {
				$msg = "_success";
				if (@array_key_exists($s_field, $update_field)) {
					$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $list_share_arrs, $profile_id);
				}
				if (@array_key_exists($s_field, $update_field)) {
					$admin_id = $update_field[$s_field];
					// Bulk giao hẳn: tạo phiếu CHỜ XÁC NHẬN cho người nhận (createPending tự bỏ qua nếu giao cho chính mình).
					$clsCustomerAssign->createPending($customer_id, $admin_id, $profile_id, 1);
					if ($clsCustomer->isRootProfile()) {
						/** Lưu lại giao cho ai */
						$clsCustomerSales->insert(array(
							$clsCustomerSales->pkey => $clsCustomerSales->getMaxId(),
							'customer_id' => $customer_id,
							'admin_id' => $admin_id,
							'assign_date' => time(),
							'user_id' => $profile_id
						));
					}
					/** Push notification */
					#request buy
					if ($total_remaining > 0) {
						--$total_remaining;
						++$amount_paid;
						$titleNoty = sprintf(
							'<strong>%s</strong> đã trả khách hàng <strong>%s</strong> yêu cầu mua dự án <strong>%s</strong>',
							$clsProfile->getFullName($profile_id, $oneProfile),
							$clsCustomer->getName($customer_id, $oCustomer),
							$oneRequestCus["project_name"]
						);
					} else {
						$titleNoty = sprintf(
							'<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>',
							$clsProfile->getFullName($profile_id, $oneProfile),
							$clsCustomer->getName($customer_id, $oCustomer)
						);
					}
					$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(), "|" . $admin_id . "|");
					$subscribers = array();
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id`='{$admin_id}' and `token`<>''", "token");
					if (!empty($tmp)) {
						foreach ($tmp as $key => $val) {
							if (!in_array($val['token'], $subscribers)) {
								$subscribers[] = $val['token'];
							}
						}
						$clsNotify->send_subscriber_notification(array(
							'title' => "CRM - Khách hàng mới",
							'message' => strip_tags($titleNoty),
							'url' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
						), $subscribers);
						#thong bao app
						$clsNotification = new Notification();
						$params = [
							'title' => "CRM - Khách hàng mới",
							'body' => strip_tags($titleNoty),
							'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
						];
						$clsNotification->doPushMessagingUser($params, [$admin_id]);
					}
				}
			}
		}
		// Send Zalo
		$s_field = 'admin_id';
		if (@array_key_exists($s_field, $update_field) && !empty($update_field[$s_field])) {
			$total_customers = count($list_ids); // Tổng khách hàng
			$admin_id = $update_field[$s_field]; // Người phụ trách
			$_oProfile = $clsProfile->getOne($admin_id);
			$zaloId = $clsProfile->getZaloId($admin_id, $_oProfile);
			if (!empty($zaloId)) {
				$clsZalo = new Zalo();
				#request buy
				if ($amount_paid > 0) {
					//update
					$total_paid = !empty($more_request["total_paid"]) ? (int)$more_request["total_paid"] : 0;
					$total_paid = $total_paid + $amount_paid;
					$more_request["total_paid"] = $total_paid;
					$status = ($total_paid < $oneRequestCus["amount"]) ? 2 : 1;
					$clsRequestCus->updateOne($request_id, ["status" => $status, "more_information" => json_encode($more_request, JSON_UNESCAPED_UNICODE)]);
					$message = sprintf("Xin chào {color:#C00000}%s{/color}", $clsProfile->getFullName($admin_id, $_oProfile));
					$message .= "\n";
					$message .= sprintf(
						"[%s %s] đã trả +%s khách hàng tiềm năng cho yêu cầu mua dự án **%s**",
						$oneProfile['role_name'],
						$clsProfile->getFullName($profile_id, $oneProfile),
						$total_customers,
						$oneRequestCus["project_name"]
					);
					$message .= "\n";
					$message .= "Hãy truy cập CRM/Quản lý khách hàng (".DOMAIN_URL."/crm/) để bắt đầu chăm sóc khách hàng!";
					$clsZalo->sendMsgSchedule($zaloId, $_oProfile['phone'], $message);
				} else {
					$clsZalo->sendNotifyCRMZalo($admin_id, $_oProfile, $total_customers, $customer_insert);
				}
			}
		}
	}
	// Return
	echo $msg;
	die();
}
function default_open_customer(){
	global $smarty, $core, $profile_id, $clsISO, $_LANG_ID;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsCountry = new Country();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	$clsCustomerMeta = new CustomerMeta();
	$clsCustomerMeta = new CustomerMeta();
	// $clsBusinessCampaign = new BusinessCampaign();
	$field = "{$clsProperty->pkey},title,image";
	$list_activity = $clsProperty->getAllCache("`is_trash`=0 and `parent_id`='0' 
	and `property_type`='FOLLOWUP_TYPE' order by `order_no` ASC", $field);
	$smarty->assign('list_activity', $list_activity);
	$rollback = (int) Input::post('rollback', 1);
	$customer_id = Input::post('customer_id');
	$clsCustomer->blockIfReceivePending((int) $customer_id, 'open');
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $oneCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();	
	$arr_share_ids = $clsCustomer->getShareIds($customer_id, $oneCustomer);
	$list_campaign_id = $clsCustomer->getCampaignIds($customer_id, $oneCustomer);
	$list_type_id = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'type');
	$list_need_id = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'need');
	$list_stock_id = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'stock');
	$list_purpose_id = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'purpose');
	$list_bedroom_id = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'bedroom');
	$list_block_id = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'block');
	$oneCustomer['list_need_id'] = $list_need_id;
	$oneCustomer['list_purpose_id'] = $list_purpose_id;
	$oneCustomer['list_stock_id'] = $list_stock_id;
	$oneCustomer['list_type_id'] = $list_type_id;
	$oneCustomer['list_bedroom_id'] = $list_bedroom_id;
	$oneCustomer['list_block_id'] = $list_block_id;
	$oneCustomer['list_campaign_id'] = $list_campaign_id;
	$country_id = $onePotential['country_id'];
	$props = 'customer_id="' . $customer_id . '" disp="item"';
	/** Permission */
	$permiss = $clsISO->checkPermission('full_permissions_crm') ? 1 : 0;
	if (!$permiss) $permiss = (in_array($profile_id, array($oneCustomer['user_id'], $oneCustomer['admin_id']))) ? 1 : 0;
	/** End Permission */
	// Trạng thái bàn giao khách (ai đã nhận / còn chờ xác nhận) — chỉ hiện cho người có quyền (chủ/admin/full-permiss).
	$assign_track = array();
	if ($permiss) {
		$clsCustomerAssign = new CustomerAssign();
		$_atrows = $clsCustomerAssign->getByCustomerNamed($customer_id);
		if (!empty($_atrows)) {
			foreach ($_atrows as $_at) {
				$_cf = (int) $_at['confirmed_at'];
				$_rn = trim($_at['recipient_name']);
				$assign_track[] = array(
					'recipient_name'	=> ($_rn !== '' ? $_rn : '#' . (int) $_at['recipient_id']),
					'assigner_name'		=> trim($_at['assigner_name']),
					'is_confirmed'		=> ($_cf > 0 ? 1 : 0),
					'assigned_ago'		=> $clsISO->getTimeAgo((int) $_at['assigned_at']),
					'confirmed_ago'		=> ($_cf > 0 ? $clsISO->getTimeAgo($_cf) : '')
				);
			}
		}
	}
	$smarty->assign('assign_track', $assign_track);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('oneCustomer', $oneCustomer);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsCampaign', $clsCampaign);
	$smarty->assign('clsSetting', $clsSetting);
	###
	$permiss_action = $permiss_edit = $permiss_notes = 0;
	if ($oneCustomer['admin_id'] == $profile_id || $clsCustomer->isFullPermiss()) {
		$permiss_action = $permiss_edit = 1;
	}
	if (@in_array($profile_id, $arr_share_ids) || $clsCustomer->isFullPermiss()) {
		$permiss_notes = 1;
	}
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_notes', $permiss_notes);
	$smarty->assign('permiss_action', $permiss_action);
	#
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.customer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_send_zalo_template(){
	// F4-send — Gửi tin Zalo theo mẫu. CỔNG AN TOÀN: gửi thật bị KHOÁ mặc định (Configuration _CRM_ZALO_LIVE_SEND=0).
	// Khi cổng TẮT: trả nội dung đã soạn để Sale tự sao chép & gửi thủ công (không phát sinh side-effect gửi tin thật).
	global $core, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsSetting = new Setting();
	$clsConfiguration = new Configuration();
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$template_id = (int) Input::post('template_id', 0);
	$message = trim(Input::post('message', ''));
	###
	$oCustomer = ($customer_id > 0) ? $clsCustomer->getOne($customer_id, "phone,name,admin_id") : array();
	if (empty($oCustomer)) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Không tìm thấy khách hàng.'
		));
		die();
	}
	// Quyền gửi: full-permiss / quản lý nhóm / đúng người phụ trách
	$_allow = $clsCustomer->isFullPermiss() || $clsCustomer->isTeamManager() || ((int) $oCustomer['admin_id'] === (int) $profile_id);
	if (!$_allow) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Bạn không có quyền gửi tin cho khách này.'
		));
		die();
	}
	// Nội dung: ưu tiên message gửi lên; nếu trống thì lấy từ mẫu Zalo (Setting _CRM_ZALO_TEMPLATE)
	if ($message === '' && $template_id > 0) {
		$oTpl = $clsSetting->getOne($template_id);
		if (!empty($oTpl)) {
			$_mi = $clsISO->to_array_json($oTpl['more_information']);
			$message = isset($_mi['intro']) ? trim($_mi['intro']) : '';
		}
	}
	$message = str_replace(array('{ten}', '{Ten}', '{name}', '{NAME}'), $clsCustomer->getName($customer_id), $message);
	if ($message === '') {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Nội dung tin Zalo đang trống.'
		));
		die();
	}
	$phone = isset($oCustomer['phone']) ? preg_replace('/[^0-9]/', '', $oCustomer['phone']) : '';
	// CỔNG AN TOÀN — mặc định TẮT gửi tự động (chỉ bật khi đã duyệt nội dung + cấu hình Zalo OA)
	$live_send = (int) $clsConfiguration->getValue('_CRM_ZALO_LIVE_SEND', 0);
	if ($live_send !== 1) {
		echo json_encode(array(
			'status'	=> 'preview',
			'message'	=> 'Gửi Zalo tự động đang TẮT. Đã sao chép nội dung — vui lòng gửi thủ công qua Zalo.',
			'preview'	=> $message,
			'phone'		=> $phone
		));
		die();
	}
	// ----- Gửi thật (chỉ chạy khi cổng đã được bật) -----
	if ($phone === '') {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Khách chưa có số điện thoại để gửi Zalo.'
		));
		die();
	}
	$clsZalo = new Zalo();
	$zaloId = $clsZalo->getZaloId('', $phone);
	if (empty($zaloId)) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Không tìm thấy Zalo của khách theo số điện thoại.'
		));
		die();
	}
	$result = $clsZalo->sendMsgSchedule2($zaloId, $phone, array(
		'message'	=> $message,
		'styles'	=> array()
	));
	$ok = ($result === 'Success');
	echo json_encode(array(
		'status'	=> $ok ? 'success' : 'error',
		'message'	=> $ok ? 'Đã gửi tin Zalo cho khách.' : sprintf('Gửi Zalo thất bại: %s', $result)
	));
	die();
}
function default_load_duplicates(){
	// F3 — Phát hiện khách TRÙNG theo SĐT (read-only). Match nhiều biến thể SĐT (raw + chuẩn hoá + +84/84/840) qua idx_phone (sargable). KHÔNG merge (F3-merge tách riêng).
	global $smarty, $core, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$customer_id = (int) Input::post('customer_id', 0);
	$one = ($customer_id > 0) ? $clsCustomer->getOne($customer_id, 'phone,name') : array();
	$raw = !empty($one['phone']) ? trim($one['phone']) : '';
	$norm = ($raw != '') ? $clsCustomer->formatPhone($raw) : '';
	$dup_rows = array();
	if ($customer_id > 0 && strlen($norm) >= 9) {
		// tập biến thể tương đương để bắt cả số đã chuẩn hoá lẫn +84/84/840 — đều là tra cứu '=' nên idx_phone vẫn dùng được
		$variants = array();
		$variants[$raw] = $raw;
		$variants[$norm] = $norm;
		$bare = ltrim($norm, '0');
		if ($bare != '') {
			$variants['+84' . $bare] = '+84' . $bare;
			$variants['84' . $bare] = '84' . $bare;
			$variants['840' . $bare] = '840' . $bare;
		}
		$in = array();
		foreach ($variants as $vv) {
			$in[] = "'" . addslashes($vv) . "'";
		}
		$cond = "`is_trash`=0 AND `customer_id`<>'{$customer_id}' AND `phone` IN (" . implode(',', $in) . ")";
		$list = $clsCustomer->getAll($cond . " order by `reg_date` desc", "customer_id,name,phone,admin_id,user_id,status_id,resource_id,reg_date");
		if (!empty($list) && is_array($list)) {
			foreach ($list as $d) {
				$aid = (int) $d['admin_id'];
				$dup_rows[] = array(
					'customer_id' => (int) $d['customer_id'],
					'name' => !empty($d['name']) ? $d['name'] : '(không tên)',
					'phone_mask' => $clsCustomer->mask($d['phone'], true),
					'status_title' => ((int)$d['status_id'] > 0) ? $clsProperty->getTitle($d['status_id']) : '',
					'owner_name' => ($aid > 0) ? $clsProfile->getIndentity($aid, false) : '<span class="text-muted">Chưa giao</span>',
					'source_title' => ((int)$d['resource_id'] > 0) ? $clsProperty->getTitle($d['resource_id']) : '',
					'reg_text' => !empty($d['reg_date']) ? date('d/m/Y', $d['reg_date']) : '',
					'can_open' => ($clsCustomer->isFullPermiss() || $aid == (int) $profile_id) ? 1 : 0
				);
			}
		}
	}
	$total_dup = count($dup_rows);
	$smarty->assign('dup_rows', $dup_rows);
	$smarty->assign('total_dup', $total_dup);
	$smarty->assign('customer_id', $customer_id);
	$html = $core->build('_ajax.duplicates.tpl');
	echo json_encode(array(
		'html' => $html,
		'total' => $total_dup
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_do_merge_duplicate(){
	// F3-merge — Gộp khách TRÙNG: re-point toàn bộ dữ liệu con từ "loser" → "survivor", rồi soft-delete loser (đảo ngược được).
	// KHÔNG hard-delete. Khoá 2 phía (A3 GET_LOCK). Gate chặt. Rào an toàn: chỉ gộp khi trùng SĐT chuẩn hoá.
	global $dbconn, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	###
	$survivor_id = (int) Input::post('merge_to', 0);
	$loser_id = (int) Input::post('merge_from', 0);
	if ($survivor_id <= 0 || $loser_id <= 0 || $survivor_id === $loser_id) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Tham số gộp không hợp lệ.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$oSurvivor = $clsCustomer->getOne($survivor_id, "name,phone,status_id,admin_id,is_trash,more_information");
	$oLoser = $clsCustomer->getOne($loser_id, "name,phone,status_id,admin_id,is_trash,more_information");
	if (empty($oSurvivor) || empty($oLoser)) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Không tìm thấy một trong hai hồ sơ.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	// Quyền: chỉ full-permiss HOẶC người phụ trách CẢ HAI hồ sơ (tránh gộp xuyên nhân sự).
	$_full = $clsCustomer->isFullPermiss();
	$_ownBoth = ((int) $oSurvivor['admin_id'] === (int) $profile_id) && ((int) $oLoser['admin_id'] === (int) $profile_id);
	if (!($_full || $_ownBoth)) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Bạn không đủ quyền gộp (cần full-permiss hoặc phụ trách cả hai hồ sơ).'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	// Rào an toàn: hồ sơ nhận chưa ở thùng rác; hồ sơ trùng chưa từng bị gộp.
	$svMi = $clsISO->to_array_json($oSurvivor['more_information']);
	$loMi = $clsISO->to_array_json($oLoser['more_information']);
	if ((int) $oSurvivor['is_trash'] === 1 || (int) $oSurvivor['status_id'] === (int) _CRM_STATUS_TRASH_ID) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Hồ sơ nhận đang ở thùng rác — không thể gộp vào.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	if (!empty($loMi['merged_into'])) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Hồ sơ trùng này đã được gộp trước đó.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	// Rào an toàn: chỉ gộp khi SĐT chuẩn hoá KHỚP (đúng tiêu chí phát hiện trùng F3).
	$svPhone = !empty($oSurvivor['phone']) ? $clsCustomer->formatPhone(trim($oSurvivor['phone'])) : '';
	$loPhone = !empty($oLoser['phone']) ? $clsCustomer->formatPhone(trim($oLoser['phone'])) : '';
	if ($svPhone === '' || $svPhone !== $loPhone) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Chỉ gộp được hai hồ sơ trùng số điện thoại.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	// ===== Khoá 2 phía (lower id trước) để tuần tự hoá toàn bộ thao tác gộp =====
	$lockLo = addslashes('cmi_' . $clsCustomer->tbl . '_' . min($survivor_id, $loser_id));
	$lockHi = addslashes('cmi_' . $clsCustomer->tbl . '_' . max($survivor_id, $loser_id));
	$gotLo = (int) $dbconn->GetOne("SELECT GET_LOCK('{$lockLo}', 10)");
	$gotHi = (int) $dbconn->GetOne("SELECT GET_LOCK('{$lockHi}', 10)");
	if ($gotLo !== 1 || $gotHi !== 1) {
		if ($gotHi === 1) {
			$dbconn->GetOne("SELECT RELEASE_LOCK('{$lockHi}')");
		}
		if ($gotLo === 1) {
			$dbconn->GetOne("SELECT RELEASE_LOCK('{$lockLo}')");
		}
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Hồ sơ đang được thao tác bởi tiến trình khác, thử lại sau.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	$ts = time();
	// Bọc giao dịch (smart-trans): nếu có Execute lỗi giữa chừng → CompleteTrans tự RollBack (bảng InnoDB).
	$dbconn->StartTrans();
	// ===== 1) Re-point dữ liệu con: UPDATE customer_id loser → survivor (các bảng không có UNIQUE trên customer_id) =====
	$repoint_tables = array(
		'followups',
		'customer_history',
		'customer_sales',
		'data_central_history',
		'archived',
		'billing',
		'contact',
		'issue'
	);
	$moved = array();
	foreach ($repoint_tables as $t) {
		$tbl = DB_PREFIX . $t;
		$dbconn->Execute("UPDATE `{$tbl}` SET `customer_id`='{$survivor_id}' WHERE `customer_id`='{$loser_id}'");
		$moved[$t] = (int) $dbconn->Affected_Rows();
	}
	// ===== 2) customer_meta: HỢP NHẤT theo từng meta_type (logical-unique (customer_id,meta_type,meta_id) → phải union, không UPDATE thẳng) =====
	$metaMap = $clsCustomerMeta->getMapByCustomerIds(array($survivor_id, $loser_id));
	$meta_synced = 0;
	if (isset($metaMap[$loser_id]) && is_array($metaMap[$loser_id])) {
		foreach ($metaMap[$loser_id] as $mtype => $loserIds) {
			$survIds = (isset($metaMap[$survivor_id][$mtype]) && is_array($metaMap[$survivor_id][$mtype])) ? $metaMap[$survivor_id][$mtype] : array();
			$union = array_values(array_unique(array_merge($survIds, is_array($loserIds) ? $loserIds : array())));
			$clsCustomerMeta->syncByCustomerType($survivor_id, $mtype, $union, $profile_id);
			++$meta_synced;
		}
	}
	$moved['customer_meta_types'] = $meta_synced;
	// ===== 3) Hợp nhất more_information của survivor (union list_billings/list_needs, max lead_score, điền field trống) + back-ref + log =====
	$clsCustomer->saveMoreInfo($survivor_id, function ($mi) use ($loMi, $loser_id, $profile_id, $ts) {
		$mi = is_array($mi) ? $mi : array();
		foreach (array('list_billings', 'list_needs') as $k) {
			$lv = (isset($loMi[$k]) && is_array($loMi[$k])) ? $loMi[$k] : array();
			if (!empty($lv)) {
				$sv = (isset($mi[$k]) && is_array($mi[$k])) ? $mi[$k] : array();
				// list_billings/list_needs là MAP keyed theo uniqid (KEY = billing_id/need_id) → union GIỮ KEY (KHÔNG array_values reindex, sẽ phá id).
				$mi[$k] = $sv + $lv;
			}
		}
		$svScore = isset($mi['lead_score']) ? (int) $mi['lead_score'] : 0;
		$lvScore = isset($loMi['lead_score']) ? (int) $loMi['lead_score'] : 0;
		if ($lvScore > $svScore) {
			$mi['lead_score'] = $lvScore;
		}
		foreach (array('gender_id', 'tiktok', 'facebook', 'agent_id', 'staff_notes') as $k) {
			$blank = (!isset($mi[$k]) || $mi[$k] === '' || $mi[$k] === 0 || $mi[$k] === '0');
			if ($blank && !empty($loMi[$k])) {
				$mi[$k] = $loMi[$k];
			}
		}
		if (!isset($mi['merged_from']) || !is_array($mi['merged_from'])) {
			$mi['merged_from'] = array();
		}
		$mi['merged_from'][] = array(
			'loser_id'	=> $loser_id,
			'merged_at'	=> $ts,
			'by'		=> $profile_id
		);
		if (!isset($mi['action_logs']) || !is_array($mi['action_logs'])) {
			$mi['action_logs'] = array();
		}
		$mi['action_logs']['mg' . $ts . 's' . $loser_id] = array(
			'content'	=> sprintf('Đã gộp hồ sơ trùng #%d vào hồ sơ này', $loser_id),
			'user_id'	=> $profile_id,
			'reg_date'	=> $ts
		);
		return $mi;
	});
	// ===== 4) Loser: soft-delete (status=TRASH + is_trash=1) + lưu metadata đảo ngược (atomic cùng more_information) =====
	$loserPrevStatus = (int) $oLoser['status_id'];
	$loserPrevAdmin = (int) $oLoser['admin_id'];
	$clsCustomer->saveMoreInfo($loser_id, function ($mi) use ($survivor_id, $profile_id, $ts, $loserPrevStatus, $loserPrevAdmin, $moved) {
		$mi = is_array($mi) ? $mi : array();
		$mi['merged_into'] = array(
			'survivor_id'		=> $survivor_id,
			'merged_at'			=> $ts,
			'by'				=> $profile_id,
			'prev_status_id'	=> $loserPrevStatus,
			'prev_admin_id'		=> $loserPrevAdmin,
			'moved'				=> $moved
		);
		if (!isset($mi['action_logs']) || !is_array($mi['action_logs'])) {
			$mi['action_logs'] = array();
		}
		$mi['action_logs']['mg' . $ts . 'l' . $survivor_id] = array(
			'content'	=> sprintf('Hồ sơ này đã được gộp vào #%d (đưa vào thùng rác)', $survivor_id),
			'user_id'	=> $profile_id,
			'reg_date'	=> $ts
		);
		return $mi;
	}, array(
		'status_id'	=> (int) _CRM_STATUS_TRASH_ID,
		'is_trash'	=> 1,
		'upd_date'	=> $ts
	));
	// ===== Commit (CompleteTrans tự RollBack nếu có lỗi giữa chừng) =====
	$commit_ok = $dbconn->CompleteTrans();
	// ===== 5) Audit toàn cục (chỉ ghi khi merge đã commit) =====
	if ($commit_ok !== false) {
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("Customer", "update", array(
			'merge'			=> 1,
			'survivor_id'	=> $survivor_id,
			'loser_id'		=> $loser_id,
			'moved'			=> $moved
		));
	}
	// ===== Nhả khoá =====
	$dbconn->GetOne("SELECT RELEASE_LOCK('{$lockHi}')");
	$dbconn->GetOne("SELECT RELEASE_LOCK('{$lockLo}')");
	if ($commit_ok === false) {
		echo json_encode(array(
			'status'	=> 'error',
			'message'	=> 'Gộp thất bại và đã được hoàn tác. Vui lòng thử lại.'
		), JSON_UNESCAPED_UNICODE);
		die();
	}
	echo json_encode(array(
		'status'		=> 'success',
		'message'		=> sprintf('Đã gộp hồ sơ #%d vào #%d.', $loser_id, $survivor_id),
		'survivor_id'	=> $survivor_id,
		'loser_id'		=> $loser_id,
		'moved'			=> $moved
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_edit_inline_field(){
	//ini_set('display_errors',1);
	global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $clsISO, $profile_id, $oneProfile;
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsCustomerMeta = new CustomerMeta();
	$clsCustomerHistory = new CustomerHistory();
	$clsProperty = new Property();
	$clsSetting = new Setting();
	$clsProfile = new Profile();
	$clsStock = new Stock();
	###
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id', 0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action', '_open');
	if ($p_action == '_save') {
		$p_value = Input::post('p_value');
		if (isset($_POST['p_value'])) {
			$p_value = $_POST['p_value'];
		} else if (isset($_POST['p_value[]'])) {
			$p_value = $_POST['p_value[]'];
		}
		if (in_array($p_field, array('twitter', 'facebook', 'tiktok', 'linkedin', 'instagram', 'finance'))) {
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			if ($p_field == 'twitter') $text_field = "Twitter";
			if ($p_field == 'facebook') $text_field = "Facebook";
			if ($p_field == 'tiktok') $text_field = "Tiktok";
			if ($p_field == 'linkedin') $text_field = "Linkedin";
			if ($p_field == 'instagram') $text_field = "Instagram";
			if ($p_field == 'finance') $text_field = "Tài chính";
			$content = sprintf(
				'<strong>%s</strong> đã cập nhật <strong>%s</strong> thành <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$text_field,
				$p_value
			);
			// $clsISO->print_pre($content); die();
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information[$p_field] = $p_value;
			$more_information['action_logs'] = $action_logs;
			$clsCustomer->updateOne($p_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if ($p_field == 'list_block_id') {
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			#
			$text_value_field = $clsSetting->getTitleArray($p_value);
			$content = sprintf(
				'<strong>%s</strong> đã cập nhật <strong>Dự án/Phân khu</strong> thành <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$text_value_field
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			if (!is_array($p_value)) {
				if (is_string($p_value) && strlen($p_value) > 1 && $p_value[0] == '[') {
					$tmp_json = json_decode($p_value, true);
					if (is_array($tmp_json)) {
						$p_value = $tmp_json;
					}
				}
			}
			if (!is_array($p_value)) {
				if ($p_value === "" || $p_value === null) {
					$p_value_arr = array();
				} else if (strpos($p_value, ",") !== false) {
					$p_value_arr = array_map('trim', explode(",", $p_value));
				} else {
					$p_value_arr = array($p_value);
				}
			} else {
				$p_value_arr = $p_value;
			}
			$p_value_arr = $clsCustomer->normalizeIdArray($p_value_arr);
			$clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			$clsCustomerMeta->syncByCustomerType($p_id, 'block', $p_value_arr, $profile_id);
		} else if (in_array($p_field, array(
			'list_purpose_id',
			'list_need_id',
			'list_stock_id',
			'list_type_id',
			'list_bedroom_id',
			'list_campaign_id'
		))) {
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			if (!is_array($p_value)) {
				if (is_string($p_value) && strlen($p_value) > 1 && $p_value[0] == '[') {
					$tmp_json = json_decode($p_value, true);
					if (is_array($tmp_json)) {
						$p_value = $tmp_json;
					}
				}
			}
			if (!is_array($p_value)) {
				if ($p_value === "" || $p_value === null) {
					$p_value_arr = array();
				} else if (strpos($p_value, ",") !== false) {
					$p_value_arr = array_map('trim', explode(",", $p_value));
				} else {
					$p_value_arr = array($p_value);
				}
			} else {
				$p_value_arr = $p_value;
			}
			$p_value_arr = $clsCustomer->normalizeIdArray($p_value_arr);
			if ($p_field == 'list_purpose_id') {
				$text_field = "Mục đích";
				$text_value_field = $clsProperty->getTitleArray($p_value_arr);
			} else if ($p_field == 'list_need_id') {
				$text_field = "Nhu cầu";
				$text_value_field = $clsProperty->getTitleArray($p_value_arr);
			} else if ($p_field == 'list_stock_id') {
				$text_field = "Căn hộ";
				$text_value_field = $clsStock->getCodeArray($p_value_arr);
			} else if ($p_field == 'list_bedroom_id') {
				$text_field = "Loại căn hộ";
				$text_value_field = $clsProperty->getTitleArray($p_value_arr);
			} else if ($p_field == 'list_type_id') {
				$text_field = "Loại khách hàng";
				$text_value_field = $clsProperty->getTitleArray($p_value_arr);
			} else if ($p_field == 'list_campaign_id') {
				$text_field = "Chiến dịch";
				$text_value_field = $clsCampaign->getTitleArray($p_value_arr);
			}
			$content = sprintf(
				'<strong>%s</strong> đã cập nhật <strong>%s</strong> thành <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$text_field,
				$text_value_field
			);
			// $clsISO->print_pre($content); die();
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			$clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			if ($p_field == 'list_purpose_id') {
				$clsCustomerMeta->syncByCustomerType($p_id, 'purpose', $p_value_arr, $profile_id);
			} else if ($p_field == 'list_need_id') {
				$clsCustomerMeta->syncByCustomerType($p_id, 'need', $p_value_arr, $profile_id);
			} else if ($p_field == 'list_stock_id') {
				$clsCustomerMeta->syncByCustomerType($p_id, 'stock', $p_value_arr, $profile_id);
			} else if ($p_field == 'list_type_id') {
				$clsCustomerMeta->syncByCustomerType($p_id, 'type', $p_value_arr, $profile_id);
			} else if ($p_field == 'list_bedroom_id') {
				$clsCustomerMeta->syncByCustomerType($p_id, 'bedroom', $p_value_arr, $profile_id);
			} else if ($p_field == 'list_campaign_id') {
				$clsCustomerMeta->syncByCustomerType($p_id, 'campaign', $p_value_arr, $profile_id);
			}
		} else if ($p_field == 'birthday') {
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			##
			$content = sprintf(
				'<strong>%s</strong> đã cập nhật <strong>Ngày sinh</strong> thành <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$p_value
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$p_value = !empty($p_value) ? $clsISO->convertTextToTime($p_value) : 0;
			$clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if ($p_field == 'admin_id') {
			$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},`name`,`user_id`,`status_id`,`more_information`");
			$admin_old_id = $oCustomer[$p_field];
			$more_information = $oCustomer['more_information'];
			#
			$list_share_arrs = $clsCustomerMeta->getIdsByCustomerType($p_id, 'share');
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			#
			$arr_profile = $adminProfile = $adminProfile_Old = array();
			if ($profile_id == $admin_old_id) { // Mình chuyển KH của mình
				$adminProfile_Old = $oneProfile;
			} else { // Mình chuyển KH của người khác
				$arr_profile[] = $admin_old_id;
			}
			$arr_profile[] = $p_value;
			$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (" . implode(',', $arr_profile) . ")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					if ($val[$clsProfile->pkey] == (int) $p_value) {
						$adminProfile = $val;
					} else if ($val[$clsProfile->pkey] == $admin_id) {
						$adminProfile_Old = $val;
					}
				}
				unset($tmp);
			}
			$content = sprintf(
				'<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$clsProfile->getFullName($admin_id, $adminProfile_Old),
				$clsProfile->getFullName($p_value, $adminProfile)
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			##
			$list_share_arrs[] = $admin_old_id;
			$list_share_arrs = $clsCustomer->normalizeIdArray($list_share_arrs);
			$use_globe = ($oCustomer['user_id'] == $p_value) ? 0 : 1;
			if ($clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time(),
				'use_globe' => $use_globe,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))) {
				$clsCustomerMeta->syncByCustomerType($p_id, 'share', $list_share_arrs, $profile_id);
				$clsCustomer->syncShareIds($p_id, $list_share_arrs, $profile_id, false);
				/** Push notification */
				$clsNotify = new Notify();
				$clsFcmToken = new FcmToken();
				$titleNoty = sprintf(
					'<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>',
					$clsProfile->getFullName($profile_id, $oneProfile),
					$clsCustomer->getName($p_id, $oCustomer)
				);
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $p_id, $titleNoty, time(), "|" . $p_value . "|");
				##
				$subscribers = array();
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' and `user_id`='{$p_value}' and `token`<>''", "token");
				if (!empty($tmp)) {
					foreach ($tmp as $key => $val) {
						if (!in_array($val['token'], $subscribers)) {
							$subscribers[] = $val['token'];
						}
					}
					$clsNotify->send_subscriber_notification(array(
						'title' => "CRM - Khách hàng mới",
						'message' => strip_tags($titleNoty),
						'url' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $p_id)
					), $subscribers);
				}
				/** Send Zalo */
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "CRM - Khách hàng mới",
					'body' => strip_tags($titleNoty),
					'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $p_id)
				];
				$clsNotification->doPushMessagingUser($params, [$p_value]);
			}
		} else if ($p_field == 'status_id') {
			$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},`status_id`,`more_information`");
			$status_id = (int) $oCustomer['status_id'];
			$more_information = $oCustomer['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			##
			$arr_property = $content_logs = array();
			$arr_property[] = $p_value;
			$arr_property[] = $status_id;
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (" . implode(",", $arr_property) . ")", "{$clsProperty->pkey},title");
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					if ($val[$clsProperty->pkey] == $p_value) {
						$content_logs['to'] = $val['title'];
					} else if ($val[$clsProperty->pkey] == $status_id) {
						$content_logs['from'] = $val['title'];
					}
				}
			}
			$content = sprintf(
				'<strong>%s</strong> đã cập nhật tình trạng từ <strong>%s</strong> tới <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$content_logs['from'],
				$content_logs['to']
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			if ($clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))) {
				$clsCustomerHistory->insert(array(
					'customer_id' => $p_id,
					'from_status_id' => $status_id,
					'to_status_id' => $p_value,
					'staff_id' => $profile_id,
					'action_date' => time()
				));
			}
		} else {
			$more = array();
			if (in_array($p_field, ['name', 'email', 'phone', 'address'])) {
				if ($p_field == 'name') $text_field = "Họ và tên";
				if ($p_field == 'email') $text_field = "E-mail";
				if ($p_field == 'phone') $text_field = "Điện thoại";
				if ($p_field == 'address') $text_field = "Địa chỉ";
				if ($p_field == 'begin_need') $text_field = "Nhu cầu ban đâu";
				$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},`more_information`");
				$more_information = $oCustomer['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$action_logs = $core->get_field($more_information, "action_logs", []);
				###
				$_logFromRaw = !empty($oCustomer[$p_field]) ? $oCustomer[$p_field] : "";
				$_dispFrom = ($_logFromRaw !== "") ? $_logFromRaw : "N/A";
				$_dispTo = $p_value;
				if ($p_field == 'phone') {
					// Che bớt SĐT khi hiển thị (giữ 4 số cuối); raw_from/raw_to lưu số nguyên bản để khôi phục khi cần.
					$_dispFrom = ($_logFromRaw !== "") ? $clsCustomer->mask($_logFromRaw, true) : "N/A";
					$_dispTo = $clsCustomer->mask($p_value, true);
				}
				$content = sprintf(
					'<strong>%s</strong> đã cập nhật <strong>%s</strong> từ <strong>%s</strong> tới <strong>%s</strong>',
					$clsProfile->getFullName($profile_id, $oneProfile),
					$text_field,
					$_dispFrom,
					$_dispTo
				);
				$_log_entry = array(
					'content' => $content,
					'user_id' => $profile_id,
					'reg_date' => time()
				);
				if ($p_field == 'phone') {
					$_log_entry['raw_from'] = $_logFromRaw;
					$_log_entry['raw_to'] = $p_value;
				}
				$action_logs[$clsISO->getUniqid()] = $_log_entry;
				$more_information['action_logs'] = $action_logs;
				$more = array('more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE));
			}
			$clsCustomer->updateOne($p_id, array_merge($more, array(
				$p_field => $p_value,
				'upd_date' => time()
			)));
		}
	}
	if (in_array($p_field, array('name', 'email', 'phone', 'address', 'CCID'))) {
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if ($p_action == '_cancel' || $p_action == '_save') {
			$html .= '<div class="metadata-row-editable-triggerArea">' . $oCustomer[$p_field] . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="full_name" p_id="{$_profile_id}">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_input = '<input name="' . $p_field . '" class="form-control form-control-sm edit_customer_field_' . $p_field . '_' . $p_id . '" value="' . $oCustomer[$p_field] . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '" />';
		}
	} else if (in_array($p_field, array('admin_id'))) {
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		$p_value = $oCustomer[$p_field];
		// $clsISO->print_pre($oCustomer); die();
		if ($p_action == '_cancel' || $p_action == '_save') {
			$html .= '<div class="metadata-row-editable-triggerArea">
				' . $clsProfile->getIndentity($p_value, false) . '
			</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="full_name" p_id="{$_profile_id}">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_input = '<select name="' . $p_field . '" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff" class="iso-selectizeNotSearch w-px-200 edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '">
				<option value="' . $p_value . '" selected="selected">
					' . $clsProfile->getIndentity($p_value, false) . '
				</option>
			</select>';
		}
	} else if (in_array($p_field, array('birthday'))) {
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if ($p_action == '_cancel' || $p_action == '_save') {
			$p_value = $oCustomer[$p_field];
			$p_date = !empty($p_value) ? $clsISO->convertTimeToText($p_value) : "";
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_date . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="full_name" p_id="{$_profile_id}">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$p_value = $oCustomer[$p_field];
			$p_date = !empty($p_value) ? $clsISO->convertTimeToText($p_value) : "";
			$html_input = '<input name="' . $p_field . '" class="form-control datepicker form-control-sm edit_customer_field_' . $p_field . '_' . $p_id . '" placeholder="dd/mm/yy" autocomplete="off" value="' . $p_date . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '" />
			<style type="text/css">.ui-datepicker{ z-index:9999 !important}</style>';
		}
	} else if ($p_field == 'begin_need') {
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if ($p_action == '_cancel' || $p_action == '_save') {
			$html .= '<div class="metadata-row-editable-triggerArea">' . $oCustomer[$p_field] . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="full_name" p_id="{$_profile_id}">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_input = '<textarea class="form-control form-control-sm edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '" />' . $oCustomer[$p_field] . '</textarea>';
		}
	} else if (in_array($p_field, array('facebook', 'tiktok', 'twitter', 'linkedin', 'instagram'))) {
		$more_information = $clsCustomer->getOneField('more_information', $p_id);
		$more_information = !empty($more_information)
			? json_decode(html_entity_decode($more_information), true) : array();
		if ($p_action == '_cancel' || $p_action == '_save') {
			$html .= $more_information[$p_field];
			$html .= '<div class="metadata-row-editable-triggerArea">' . $oCustomer[$p_field] . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="full_name" p_id="{$_profile_id}">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_input = '<input class="form-control form-control-sm edit_customer_field_' . $p_field . '_' . $p_id . '" value="' . $more_information[$p_field] . '" name="edit_customer_field_' . $p_field . '_' . $p_id . '" />';
		}
	} else if (in_array($p_field, array('status_id', 'finance_id', 'resource_id', 'blocktype_id'))) {
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if ($p_action == '_cancel' || $p_action == '_save') {
			if (isset($oCustomer[$p_field]) && (int) $oCustomer[$p_field] > 0) {
				$p_text = $clsProperty->getTitle($oCustomer[$p_field]);
			} else {
				$p_text = "--";
			}
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_text . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$label = 'Tình trạng';
			$property_type = 'CUSTOMER_STATUS';
			if ($p_field == 'type_id') {
				$label = 'Loại';
				$property_type = 'CUSTOMER_TYPE';
			} else if ($p_field == 'finance_id') {
				$label = 'Tài chính';
				$property_type = 'FINANCE';
			} else if ($p_field == 'resource_id') {
				$label = 'Nguồn gốc';
				$property_type = '_CUSTOMER_RESOURCES';
			} else if ($p_field == 'blocktype_id') {
				$label = 'Loại hình';
				$property_type = '_BLOCK_TYPE';
			}
			$html_input = '<select class="form-control form-select form-control-sm edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '" />
				' . $clsISO->getSelectByPropertyTypeTitle($property_type, $oCustomer['status_id'], $label) . '
			</select>';
		}
	} else if ($p_field == 'list_block_id') {
		$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'block');
		if ($p_action == '_cancel' || $p_action == '_save') {
			$p_text = "";
			if (!empty($p_array)) {
				$p_text = $clsSetting->getTitleArray($p_array);
			} else {
				$p_text .= "--";
			}
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_text . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_options = "";
			$tmp = $clsSetting->getCacheItems('_PROJECT');
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					$html_options .= '<option' . (in_array($val[$clsSetting->pkey], $p_array) ? ' selected="selected"' : '') . ' 
						value="' . $val[$clsSetting->pkey] . '">' . $val['title'] . '</option>';
				}
				unset($tmp);
			}
			$html_input = '<select data-placeholder="' . $label . '" data-allow-clear="true" multiple="multiple" class="form-control iso-select2 edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '[]" />
				' . $html_options . '
			</select>';
		}
	} else if ($p_field == 'list_bedroom_id') {
		$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'bedroom');
		if ($p_action == '_cancel' || $p_action == '_save') {
			$p_text = "";
			if (!empty($p_array)) {
				$p_text .= $clsProperty->getTitleArray($p_array);
			} else {
				$p_text .= "--";
			}
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_text . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_options = "";
			$tmp = $clsProperty->getCacheItems('_BEDROOM');
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					$html_options .= '<option' . (in_array($val[$clsProperty->pkey], $p_array) ? ' selected="selected"' : '') . ' 
						value="' . $val[$clsProperty->pkey] . '">' . $val['title'] . '</option>';
				}
				unset($tmp);
			}
			$tmp = $clsProperty->getCacheItems('_TYPE_VILLA');
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					$html_options .= '<option' . (in_array($val[$clsProperty->pkey], $p_array) ? ' selected="selected"' : '') . ' 
						value="' . $val[$clsProperty->pkey] . '">' . $val['title'] . '</option>';
				}
				unset($tmp);
			}
			$html_input = '<select data-placeholder="' . $label . '" data-allow-clear="true" multiple="multiple" 
				class="form-control iso-select2 edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '[]" />
				' . $html_options . '
			</select>';
		}
	} else if (in_array($p_field, array('list_need_id', 'list_purpose_id', 'list_type_id'))) {
		$p_array = array();
		if ($p_field == 'list_purpose_id') {
			$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'purpose');
		} else if ($p_field == 'list_need_id') {
			$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'need');
		} else if ($p_field == 'list_type_id') {
			$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'type');
		}
		if ($p_action == '_cancel' || $p_action == '_save') {
			$p_text = "";
			if (!empty($p_array)) {
				$p_text .= $clsProperty->getTitleArray($p_array);
			} else {
				$p_text .= "--";
			}
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_text . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			if ($p_field == 'list_purpose_id') {
				$label = 'Mục đích';
				$property_type = 'PURPOSE';
			} else if ($p_field == 'list_need_id') {
				$label = 'Nhu cầu';
				$property_type = 'NEED';
			} else if ($p_field == 'list_type_id') {
				$label = 'Loại khách hàng';
				$property_type = 'CUSTOMER_TYPE';
			}
			$html_input = '<select data-placeholder="' . $label . '" data-allow-clear="true" multiple="multiple" class="form-control iso-select2 edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '[]" />
				' . $clsProperty->getSelectByPropertyV2($property_type, $p_array, 'Phòng ban') . '
			</select>';
		}
	} else if ($p_field == 'list_campaign_id') {
		$uid = $clsISO->getUniqid();
		$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'campaign');
		if ($p_action == '_cancel' || $p_action == '_save') {
			$p_text = "";
			if (!empty($p_array)) {
				$p_text = $clsCampaign->getTitleArray($p_array);
			}
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_text . '</div>
			<a class="metadata-row-editable-button editInlineField"  onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_input = '<div class="input-group align-items-center" style="width:calc(100% - 62px)">
				<div class="select2-container no-border-right" style="width:calc(100% - 40px)">
					<select multiple="multiple" id="slb_Campaign_' . $uid . '" data-width="100%" data-placeholder="Chọn chiến dịch" class="form-control iso-select2 edit_customer_field_' . $p_field . '_' . $p_id . '" name="edit_profile_field_' . $p_field . '_' . $p_id . '[]">';
			$field = "{$clsCampaign->pkey},title";
			$list_campaigns = $clsCampaign->getAll("`campaign_type`='_campaign' 
						and `user_id`='{$profile_id}' order by `reg_date` DESC", $field);
			if (!empty($list_campaigns)) {
				foreach ($list_campaigns as $key => $val) {
					$html_input .= '<option' . (in_array($val[$clsCampaign->pkey], $p_array) ? ' selected' : '') . ' value="' . $val[$clsCampaign->pkey] . '">' . $val['title'] . '</option>';
				}
			}
			$html_input .= '</select>
				</div>
				<button type="button" toId="' . $uid . '" onClick="$Core.crm.open_campaign(this, event)" 
				class="btn btn-icon btn-outline-default">' . $clsISO->makeIcon('bx-plus') . '</button>
			</div>';
		}
	} else if ($p_field == 'list_stock_id') {
		$p_array = $clsCustomerMeta->getIdsByCustomerType($p_id, 'stock');
		if ($p_action == '_cancel' || $p_action == '_save') {
			$p_text = "";
			if (!empty($p_array)) {
				$p_text .= $clsStock->getTitleArray($p_array);
			} else {
				$p_text .= "--";
			}
			$html .= '<div class="metadata-row-editable-triggerArea">' . $p_text . '</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\'' . $p_field . '\', \'p_id\':\'' . $p_id . '\'})" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $clsISO->makeIcon('bx-pencil') . '</a>';
		} else {
			$html_input = '<select multiple="multiple" class="form-control iso-selectizeLiveSearch edit_customer_field_' . $p_field . '_' . $p_id . '" data-url="' . PCMS_URL . '/index.php?mod=home&act=load_stock_search" data-optgroup="false" name="edit_profile_field_' . $p_field . '_' . $p_id . '[]">';
			if (!empty($p_array)) {
				foreach ($p_array as $stock_id) {
					$html_input .= '<option value="' . $stock_id . '" selected="selected">
							' . $clsStock->getMsCode($stock_id) . '</option>';
				}
			}
			$html_input .= '</select>';
		}
	}
	if ($p_action == '_cancel' || $p_action == '_save') {
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container">
			' . $html_input . '
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.crm.save_edit_field(this, event)" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $core->makeIcon('check') . '</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.crm.cancel_edit_field(this, event)" p_field="' . $p_field . '" p_id="' . $p_id . '">' . $core->makeIcon('undo') . '</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_crm_task_result_change(){
	global $core, $profile_id, $clsISO, $dbconn;
	$clsCustomer = new Customer();
	$clsSetting = new Setting();
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$clsProfile = new Profile();
	$customer_id = (int) Input::post('p_id', 0);
	$task_result_id = (int) Input::post('p_value', 0);
	if ($customer_id <= 0) {
		echo json_encode(array(
			'error' => 1,
			'message' => 'Dữ liệu không hợp lệ'
		)); die();
	}
	// Chặn tác nghiệp khi khách CHƯA xác nhận nhận (đồng bộ gate xem chi tiết/SĐT/ghi-nhận đã có).
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo json_encode(array('error' => 1, 'message' => 'Khách chưa xác nhận nhận — bấm "Nhận" ở danh sách trước khi tác nghiệp.'));
		die();
	}
	$oneCustomer = $clsCustomer->getOne($customer_id, "`task_current_id`,`task_next_id`,`status_id`,`more_information`");
	$task_current_id = (int) $oneCustomer['task_current_id'];
	if ($task_current_id <= 0 && defined('_CRM_TASK_LEAD_ID')) {
		$task_current_id = (int) _CRM_TASK_LEAD_ID;
	}
	$task_next_id = 0;
	$time_receipt = 0;
	$delay_value = 0;
	$delay_unit = 'minute';
	// Map kết quả tác nghiệp → tình trạng khách + cờ kết thúc pipeline (terminal: hết tác nghiệp tiếp, không reminder).
	$_new_status_id = 0; // 0 = không đổi tình trạng
	$_is_terminal = false;
	if (in_array($task_result_id, array(_CRM_RESULT_REJECT_ID, _CRM_RESULT_NO_NEED_ID), true)) {
		$_new_status_id = _CRM_STATUS_TRASH_ID;
		$_is_terminal = true;
	} else if ($task_result_id == _CRM_RESULT_DEPOSIT_ID) {
		$_new_status_id = _CRM_STATUS_CHOT_ID;
		$_is_terminal = true;
	} else if (in_array($task_result_id, array(_CRM_RESULT_THINK_ID, _CRM_RESULT_ASK_FAMILY_ID, _CRM_RESULT_APPOINTMENT_ID), true)) {
		$_new_status_id = _CRM_STATUS_NET_ID;
	}
	if (!$_is_terminal && $task_current_id > 0 && $task_result_id > 0) {
		$oneRule = $clsCustomerTaskSetting->getByCond("`is_trash`=0 and `task_id`='{$task_current_id}' and `result_id`='{$task_result_id}' order by `order_no` asc, `customer_task_setting_id` asc", "*");
		if (!empty($oneRule)) {
			$task_next_id = (int) $oneRule['next_task_id'];
			$delay_value = (int) $oneRule['delay_value'];
			$delay_unit = !empty($oneRule['delay_unit']) ? $oneRule['delay_unit'] : 'minute';
			if (!in_array($delay_unit, array('minute', 'hour', 'day'))) {
				$delay_unit = 'minute';
			}
			$seconds = 0;
			if ($delay_value > 0) {
				if ($delay_unit == 'minute') {
					$seconds = $delay_value * 60;
				} else if ($delay_unit == 'hour') {
					$seconds = $delay_value * 3600;
				} else if ($delay_unit == 'day') {
					$seconds = $delay_value * 86400;
				}
			}
			if ($seconds > 0) {
				$time_receipt = time() + $seconds;
			}
		} else {
			echo json_encode(array('error' => 1, 'message' => 'Chưa cấu hình tác nghiệp tiếp cho kết quả này — vào Thiết lập tác nghiệp để thêm rule.'));
			die();
		}
	}
	$more_information = $oneCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	// ===== ENGINE V1 (Phase 3): result.more_information drives counter ladder / need_datetime / auto status / terminal =====
	// getCacheItems trả LIST đánh số (không keyed theo setting_id) → phải build map keyed mới lookup được.
	$_tmp_result_v1 = $clsSetting->getCacheItems('_CRM_RESULT');
	$arr_crm_result_cached_v1 = array();
	if (!empty($_tmp_result_v1)) {
		foreach ($_tmp_result_v1 as $_rv) {
			if (isset($_rv[$clsSetting->pkey])) { $arr_crm_result_cached_v1[(int)$_rv[$clsSetting->pkey]] = $_rv; }
		}
	}
	$r_mi = array();
	if ($task_result_id > 0 && isset($arr_crm_result_cached_v1[$task_result_id]['more_information'])) {
		$r_mi = $clsISO->to_array_json($arr_crm_result_cached_v1[$task_result_id]['more_information']);
	}
	$r_is_counter   = !empty($r_mi['is_counter']) ? 1 : 0;
	$r_need_datetime = !empty($r_mi['need_datetime']) ? 1 : 0;
	$r_status_id    = isset($r_mi['customer_status_id']) ? (int) $r_mi['customer_status_id'] : 0;
	$r_ladder       = (isset($r_mi['ladder']) && is_array($r_mi['ladder'])) ? $r_mi['ladder'] : array();
	// need_datetime: bắt nhập giờ; FE phải POST p_datetime; thiếu → trả error 2 để FE mở modal Ghi nhận.
	$p_datetime_ts = 0;
	if ($r_need_datetime === 1 && !$_is_terminal) {
		$p_datetime = trim((string) Input::post('p_datetime', ''));
		if ($p_datetime === '') {
			echo json_encode(array('error' => 2, 'need_datetime' => 1, 'message' => 'Kết quả này cần giờ hẹn — bấm "Ghi nhận" để chọn giờ.'));
			die();
		}
		$p_datetime_ts = strtotime($p_datetime);
		if ($p_datetime_ts <= 0) {
			echo json_encode(array('error' => 1, 'message' => 'Giờ hẹn không hợp lệ.'));
			die();
		}
		if ($p_datetime_ts < time()) {
			echo json_encode(array('error' => 1, 'message' => 'Giờ hẹn phải lớn hơn hiện tại.'));
			die();
		}
	}
	// Counter ladder: result có is_counter=1 + ladder không rỗng → đè next_task/delay theo bậc đếm, tăng counter.
	if ($r_is_counter === 1 && !empty($r_ladder) && !$_is_terminal && $task_current_id > 0) {
		$cnt_key = 'task_counter_' . $task_current_id . '_' . $task_result_id;
		$cur_cnt = isset($more_information[$cnt_key]) ? (int) $more_information[$cnt_key] : 0;
		$step_idx = $cur_cnt; if ($step_idx >= count($r_ladder)) { $step_idx = count($r_ladder) - 1; } // clamp bậc cuối
		$step = $r_ladder[$step_idx];
		$task_next_id = (int) ($step['next_task_id'] ?? 0);
		$delay_value  = (int) ($step['delay_value'] ?? 0);
		$_du = isset($step['delay_unit']) ? (string) $step['delay_unit'] : 'minute';
		if (in_array($_du, array('minute', 'hour', 'day'), true)) { $delay_unit = $_du; }
		$seconds = 0;
		if ($delay_value > 0) {
			if ($delay_unit === 'minute')   { $seconds = $delay_value * 60; }
			else if ($delay_unit === 'hour'){ $seconds = $delay_value * 3600; }
			else if ($delay_unit === 'day') { $seconds = $delay_value * 86400; }
		}
		$time_receipt = ($seconds > 0) ? (time() + $seconds) : 0;
		$more_information[$cnt_key] = $cur_cnt + 1; // tăng counter
	}
	// need_datetime: time_receipt = giờ sale nhập (override delay nếu có cả 2).
	if ($r_need_datetime === 1 && $p_datetime_ts > 0) {
		$time_receipt = $p_datetime_ts;
	}
	// Auto status V1: override V0 nếu result V1 có customer_status_id.
	if ($r_status_id > 0) {
		$_new_status_id = $r_status_id;
	}
	// Terminal V1: next_task_id là END/DEAL → set status + KHÔNG sinh reminder (time_receipt=0 đủ để skip follow-up bên dưới).
	if ($task_next_id > 0 && defined('_CRM_TASK_END_ID') && $task_next_id === (int) _CRM_TASK_END_ID) {
		$_new_status_id = _CRM_STATUS_TRASH_ID; // Không thành công
		$_is_terminal = true;
		$time_receipt = 0;
	} else if ($task_next_id > 0 && defined('_CRM_TASK_DEAL_ID') && $task_next_id === (int) _CRM_TASK_DEAL_ID) {
		$_new_status_id = _CRM_STATUS_CHOT_ID; // Chốt
		$_is_terminal = true;
		$time_receipt = 0;
	}
	// ===== /ENGINE V1 =====
	$action_logs = $core->get_field($more_information, "action_logs", array());
	$task_result_title = ($task_result_id > 0) ? $clsSetting->getTitle($task_result_id) : "N/A";
	$task_next_title = ($task_next_id > 0) ? $clsSetting->getTitle($task_next_id) : "N/A";
	$_log_content = $_is_terminal
		? sprintf('<strong>%s</strong> cập nhật kết quả tác nghiệp thành <strong>%s</strong> → kết thúc quá trình tác nghiệp', $clsProfile->getFullName($profile_id), $task_result_title)
		: sprintf('<strong>%s</strong> cập nhật kết quả tác nghiệp thành <strong>%s</strong>, tác nghiệp tiếp <strong>%s</strong>', $clsProfile->getFullName($profile_id), $task_result_title, $task_next_title);
	$action_logs[$clsISO->getUniqid()] = array(
		'content' => $_log_content,
		'user_id' => $profile_id,
		'reg_date' => time()
	);
	$more_information['action_logs'] = $action_logs;
	$_old_status_id = (int) $oneCustomer['status_id'];
	$_arr_update = array(
		'task_current_id' => $task_current_id,
		'task_result_id' => $task_result_id,
		'task_next_id' => $task_next_id,
		'time_receipt' => $time_receipt,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'upd_date' => time()
	);
	if ($_new_status_id > 0 && $_new_status_id != $_old_status_id) {
		$_arr_update['status_id'] = $_new_status_id;
	}
	$clsCustomer->updateOne($customer_id, $_arr_update);
	// Ghi lịch sử đổi tình trạng (đúng pattern save_activity) khi kết quả tác nghiệp làm chuyển trạng thái.
	if ($_new_status_id > 0 && $_new_status_id != $_old_status_id) {
		$clsCustomerHistory = new CustomerHistory();
		$clsCustomerHistory->insert(array(
			'customer_id' => $customer_id,
			'from_status_id' => $_old_status_id,
			'to_status_id' => $_new_status_id,
			'staff_id' => $profile_id,
			'action_date' => time()
		));
	}
	// Tác nghiệp tiếp → tạo Follow-up có reminder để theo dõi + nhắc trước 15'. target_id = marker auto (dedup).
	$clsFollowUp = new FollowUp();
	// Đóng follow-up auto cũ (đã bị thay bởi tác nghiệp tiếp mới) → tránh nhắc tồn đọng khi đổi kết quả nhiều lần.
	$dbconn->Execute("UPDATE `{$clsFollowUp->tbl}` SET `is_trash`=1, `upd_date`=" . time() . " WHERE `customer_id`={$customer_id} AND `target_id`>0 AND `status_id`<>" . _FOLLOWUP_STATUS_DONE_ID . " AND `is_trash`=0");
	if ($task_next_id > 0 && $time_receipt > 0) {
		$_owner = (int) $clsCustomer->getOneField('admin_id', $customer_id);
		if ($_owner <= 0) { $_owner = (int) $profile_id; }
		$_rt = ($time_receipt - 15 * 60);
		$clsFollowUp->insert(array(
			'followup_type'    => '_crm',
			'target_id'        => $task_next_id,
			'customer_id'      => $customer_id,
			'type_id'          => _FOLLOWUP_CALL_ID,
			'status_id'        => _FOLLOWUP_STATUS_PLAN_ID,
			'intro'            => sprintf('Tác nghiệp tiếp: %s', $task_next_title),
			'date_id'          => $time_receipt,
			'is_reminder'      => 1,
			'reminder_before'  => '15',
			'reminder_time'    => ($_rt > 0 ? $_rt : 0),
			'is_send_reminder' => 0,
			'admin_id'         => $_owner,
			'user_id'          => $_owner,
			'user_id_update'   => $profile_id,
			'reg_date'         => time(),
			'upd_date'         => time(),
		));
	}
	$arr_crm_task_cached = array();
	$tmp_task = $clsSetting->getCacheItems('_CRM_TASK');
	if (!empty($tmp_task)) {
		foreach ($tmp_task as $it) {
			$arr_crm_task_cached[(int)$it['setting_id']] = $it;
		}
	}
	$task_next_html = crm_task_next_cell_html($customer_id, $task_next_id, $arr_crm_task_cached, $clsSetting);
	$waiting_time_html = crm_task_waiting_cell_html($customer_id, $time_receipt, $task_next_id);
	$is_meeting = 0;
	if (defined('_CRM_TASK_MEETING_ID') && (int)_CRM_TASK_MEETING_ID > 0) {
		if ($task_result_id == (int)_CRM_TASK_MEETING_ID || $task_next_id == (int)_CRM_TASK_MEETING_ID) {
			$is_meeting = 1;
		}
	}
	echo json_encode(array(
		'error' => 0,
		'task_next_id' => $task_next_id,
		'time_receipt' => $time_receipt,
		'task_next_html' => $task_next_html,
		'waiting_time_html' => $waiting_time_html,
		'is_meeting' => $is_meeting
	));
	die();
}
function default_crm_task_move_next(){
	global $profile_id, $clsISO, $core, $dbconn;
	$clsCustomer = new Customer();
	$clsSetting = new Setting();
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$customer_id = (int) Input::post('customer_id', 0);
	if ($customer_id <= 0) {
		echo json_encode(array('error' => 1, 'message' => 'Dữ liệu không hợp lệ'));
		die();
	}
	// Chặn tác nghiệp khi khách CHƯA xác nhận nhận.
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo json_encode(array('error' => 1, 'message' => 'Khách chưa xác nhận nhận — bấm "Nhận" ở danh sách trước khi tác nghiệp.'));
		die();
	}
	$oneCustomer = $clsCustomer->getOne($customer_id, "`task_next_id`,`more_information`");
	$task_next_id = (int) $oneCustomer['task_next_id'];
	if ($task_next_id <= 0) {
		echo json_encode(array('error' => 1, 'message' => 'Chưa có tác nghiệp tiếp'));
		die();
	}
	// D1b: chặn promote vào task chưa cấu hình kết quả nào (nếu không sẽ kẹt im lặng — dropdown kết quả rỗng)
	$_nextHasRule = $clsCustomerTaskSetting->getByCond("`is_trash`=0 and `task_id`='{$task_next_id}'", "customer_task_setting_id");
	// $clsISO->print_pre($_nextHasRule); die();
	if (empty($_nextHasRule)) {
		echo json_encode(array('error' => 1, 'message' => 'Tác nghiệp tiếp chưa cấu hình kết quả — vào Thiết lập tác nghiệp để thêm rule.'));
		die();
	}
	$task_next_title = $clsSetting->getTitle($task_next_id) ?: 'N/A';
	// Ghi action_logs (đồng bộ timeline với đổi-kết-quả): chuyển sang thực hiện tác nghiệp tiếp.
	$more_information = $clsISO->to_array_json($oneCustomer['more_information']);
	$action_logs = $core->get_field($more_information, "action_logs", array());
	$action_logs[$clsISO->getUniqid()] = array(
		'content' => sprintf('<strong>%s</strong> chuyển sang thực hiện tác nghiệp <strong>%s</strong>', $clsProfile->getFullName($profile_id), $task_next_title),
		'user_id' => $profile_id,
		'reg_date' => time()
	);
	$more_information['action_logs'] = $action_logs;
	// Reset counter ladder V1 khi chuyển task — engine sẽ đếm lại từ bậc 1 trên task mới.
	foreach (array_keys($more_information) as $_k) {
		if (strpos((string)$_k, 'task_counter_') === 0) { unset($more_information[$_k]); }
	}
	$clsCustomer->updateOne($customer_id, array(
		'task_current_id' => $task_next_id,
		'task_result_id' => 0,
		'task_next_id' => 0,
		'time_receipt' => 0,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'upd_date' => time()
	));
	// "Thực hiện tác nghiệp tiếp" = hoàn thành follow-up reminder mà đổi-kết-quả đã tạo (target_id = marker auto): đánh dấu DONE + chặn cron bắn nốt.
	$dbconn->Execute("UPDATE `{$clsFollowUp->tbl}` SET `status_id`=" . _FOLLOWUP_STATUS_DONE_ID . ", `is_send_reminder`=1, `upd_date`=" . time() . " WHERE `customer_id`={$customer_id} AND `target_id`={$task_next_id} AND `status_id`<>" . _FOLLOWUP_STATUS_DONE_ID . " AND `is_trash`=0");
	$current_row = $clsSetting->getOne($task_next_id, "title,more_information");
	$task_current_html = $clsSetting->getLabel($task_next_id, $current_row);
	$arr_crm_task_cached = array();
	$tmp_task = $clsSetting->getCacheItems('_CRM_TASK');
	if (!empty($tmp_task)) {
		foreach ($tmp_task as $it) {
			$arr_crm_task_cached[(int)$it['setting_id']] = $it;
		}
	}
	$arr_crm_result_cached = array();
	$tmp_result = $clsSetting->getCacheItems('_CRM_RESULT');
	if (!empty($tmp_result)) {
		foreach ($tmp_result as $it) {
			$arr_crm_result_cached[(int)$it['setting_id']] = $it;
		}
	}
	$task_result_ids_by_task = crm_task_result_ids_by_task(array($task_next_id), $clsCustomerTaskSetting);
	// Return
	echo json_encode(array(
		'error' => 0,
		'task_current_id' => $task_next_id,
		'task_current_html' => $task_current_html,
		'task_result_html' => crm_task_result_select_html($customer_id, $task_next_id, 0, $arr_crm_result_cached, $task_result_ids_by_task),
		'task_next_html' => crm_task_next_cell_html($customer_id, 0, $arr_crm_task_cached, $clsSetting),
		'waiting_time_html' => crm_task_waiting_cell_html($customer_id, 0, 0)
	)); die();
}
function default_crm_task_result_sheet(){
	// Mobile/drawer: card .cmc + drawer không có ô "Kết quả" như bảng desktop → sheet này cho sale chọn kết quả
	// (gồm cả kết quả KẾT THÚC / CHỐT) để đẩy pipeline / kết thúc chăm sóc. Tap 1 phát → engine crm_task_result_change.
	global $clsISO;
	$clsCustomer = new Customer();
	$clsSetting = new Setting();
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$customer_id = (int) Input::post('customer_id', 0);
	if ($customer_id <= 0) {
		echo json_encode(array('error' => 1, 'message' => 'Dữ liệu không hợp lệ'));
		die();
	}
	// Chặn tác nghiệp khi khách CHƯA xác nhận nhận.
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo json_encode(array('error' => 1, 'message' => 'Khách chưa xác nhận nhận — bấm "Nhận" ở danh sách trước khi tác nghiệp.'));
		die();
	}
	$oneCustomer = $clsCustomer->getOne($customer_id, "`task_current_id`,`task_result_id`");
	$task_current_id = (int) $oneCustomer['task_current_id'];
	if ($task_current_id <= 0 && defined('_CRM_TASK_LEAD_ID')) {
		$task_current_id = (int) _CRM_TASK_LEAD_ID;
	}
	if ($task_current_id <= 0) {
		echo json_encode(array('error' => 1, 'message' => 'Khách chưa có tác nghiệp hiện tại'));
		die();
	}
	$task_result_id = (int) $oneCustomer['task_result_id'];
	$task_title = $clsSetting->getTitle($task_current_id) ?: 'Tác nghiệp';
	// Keyed result cache: getCacheItems trả LIST đánh số → phải build map keyed theo setting_id mới lookup được.
	$arr_crm_result_cached = array();
	$tmp_result = $clsSetting->getCacheItems('_CRM_RESULT');
	if (!empty($tmp_result)) {
		foreach ($tmp_result as $it) {
			$arr_crm_result_cached[(int) $it['setting_id']] = $it;
		}
	}
	$task_result_ids_by_task = crm_task_result_ids_by_task(array($task_current_id), $clsCustomerTaskSetting);
	$allowed = isset($task_result_ids_by_task[$task_current_id]) ? $task_result_ids_by_task[$task_current_id] : array();
	// Map result_id → next_task_id (1 query) để đánh dấu kết quả KẾT THÚC (END) / CHỐT (DEAL).
	$next_by_result = array();
	$_rules = $clsCustomerTaskSetting->getAll("`is_trash`=0 and `task_id`='{$task_current_id}'", "`result_id`,`next_task_id`");
	if (!empty($_rules)) {
		foreach ($_rules as $_r) {
			$_rrid = (int) $_r['result_id'];
			if (!isset($next_by_result[$_rrid])) { $next_by_result[$_rrid] = (int) $_r['next_task_id']; }
		}
	}
	$_end_id = defined('_CRM_TASK_END_ID') ? (int) _CRM_TASK_END_ID : 0;
	$_deal_id = defined('_CRM_TASK_DEAL_ID') ? (int) _CRM_TASK_DEAL_ID : 0;
	$rows_html = '';
	foreach ($allowed as $rid) {
		$rid = (int) $rid;
		if (!isset($arr_crm_result_cached[$rid])) { continue; }
		$_mi_raw = isset($arr_crm_result_cached[$rid]['more_information']) ? $arr_crm_result_cached[$rid]['more_information'] : '';
		if (is_array($_mi_raw)) { $_mi = $_mi_raw; }
		else if (is_string($_mi_raw) && $_mi_raw !== '') { $_mi = json_decode($_mi_raw, true); if (!is_array($_mi)) { $_mi = array(); } }
		else { $_mi = array(); }
		$_nd = !empty($_mi['need_datetime']) ? 1 : 0;
		$_ic = !empty($_mi['is_counter']) ? 1 : 0;
		$_next = isset($next_by_result[$rid]) ? (int) $next_by_result[$rid] : 0;
		$_is_end = ($_end_id > 0 && $_next === $_end_id);
		$_is_deal = ($_deal_id > 0 && $_next === $_deal_id);
		$_cls = 'crm-rsheet-item';
		if ($_is_end) { $_cls .= ' crm-rsheet-item--end'; }
		else if ($_is_deal) { $_cls .= ' crm-rsheet-item--deal'; }
		if ($task_result_id === $rid) { $_cls .= ' crm-rsheet-item--active'; }
		$_meta = '';
		if ($_nd) { $_meta .= '<i class="bx bx-time-five" title="Cần đặt giờ hẹn"></i>'; }
		if ($_ic) { $_meta .= '<i class="bx bx-revision" title="Đếm số lần"></i>'; }
		if ($_is_end) { $_meta .= '<span class="crm-rsheet-tag crm-rsheet-tag--end">Kết thúc</span>'; }
		else if ($_is_deal) { $_meta .= '<span class="crm-rsheet-tag crm-rsheet-tag--deal">Chốt</span>'; }
		$rows_html .= '<button type="button" class="' . $_cls . '"
			onClick="$Core.crm.crm_task_result_pick(this,event)"
			customer_id="' . $customer_id . '" data-result-id="' . $rid . '" data-need-datetime="' . $_nd . '" data-counter="' . $_ic . '">
			<span class="crm-rsheet-lb">' . htmlspecialchars((string) $arr_crm_result_cached[$rid]['title'], ENT_QUOTES) . '</span>
			<span class="crm-rsheet-meta">' . $_meta . '</span>
		</button>';
	}
	if ($rows_html === '') {
		$rows_html = '<div class="text-muted text-center py-3">Tác nghiệp này chưa cấu hình kết quả nào — vào Thiết lập tác nghiệp để thêm.</div>';
	}
	$uid = $clsISO->getUniqid();
	$html = '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable crm-rsheet-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="bx bx-list-check me-1"></i> Ghi kết quả · ' . htmlspecialchars((string) $task_title, ENT_QUOTES) . '</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body crm-rsheet-body">
				' . $rows_html . '
			</div>
		</div>
	</div>';
	echo json_encode(array('error' => 0, 'uid' => $uid, 'html' => $html));
	die();
}
function default_crm_task_open_schedule(){
	global $dbconn, $core, $clsISO;
	$clsCustomer = new Customer();
	$clsSetting = new Setting();
	$customer_id = (int) Input::post('customer_id', 0);
	$result_id = (int) Input::post('result_id', 0); // V1 Phase 3c: >0 = đặt giờ hẹn cho kết quả need_datetime (chưa commit)
	if ($customer_id <= 0) {
		echo json_encode(array(
			'error' => 1, 
			'message' => 'Dữ liệu không hợp lệ'
		)); die();
	}
	// Chặn đặt giờ tác nghiệp khi khách CHƯA xác nhận nhận.
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo json_encode(array('error' => 1, 'message' => 'Khách chưa xác nhận nhận — bấm "Nhận" ở danh sách trước khi tác nghiệp.'));
		die();
	}
	if ($result_id > 0) {
		// Phase 3c: đặt giờ hẹn cho 1 kết quả need_datetime — chưa có task_next, save gọi engine resolve.
		$result_title = $clsSetting->getTitle($result_id);
		$time_value = date('H:i');
		$date_value = date('Y-m-d');
		$modal_title = 'Đặt giờ hẹn';
		$subtitle = '<div class="mb-2 text-muted small">Kết quả: <strong>' . htmlspecialchars((string)$result_title, ENT_QUOTES) . '</strong></div>';
		$extra_field = '<input type="hidden" name="result_id" value="' . $result_id . '">';
		$save_onclick = '$Core.crm.crm_task_save_datetime(this,event)';
	} else {
		$oneCustomer = $clsCustomer->getOne($customer_id, "`time_receipt`,`task_next_id`");
		if ((int)$oneCustomer['task_next_id'] <= 0) {
			echo json_encode(array(
				'error' => 1, 
				'message' => 'Chưa có tác nghiệp tiếp để đặt lịch'
			)); die();
		}
		$time_receipt = (int) $oneCustomer['time_receipt'];
		$time_value = !empty($time_receipt) ? date('H:i', $time_receipt) : date('H:i');
		$date_value = !empty($time_receipt) ? date('Y-m-d', $time_receipt) : date('Y-m-d');
		$modal_title = 'Sửa lịch tác nghiệp';
		$subtitle = $extra_field = '';
		$save_onclick = '$Core.crm.crm_task_save_schedule(this,event)';
	}
	$uid = $clsISO->getUniqid();
	$html = '<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">' . $modal_title . '</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="js__crm-task-schedule-form">
					' . $subtitle . $extra_field . '
					<div class="mb-3">
						<label class="form-label"><span class="text-danger">*</span> Giờ :</label>
						<input type="time" class="form-control" name="task_time" value="' . $time_value . '">
					</div>
					<div class="mb-3">
						<label class="form-label"><span class="text-danger">*</span> Ngày tác nghiệp tiếp :</label>
						<input type="date" class="form-control" name="task_date" value="' . $date_value . '">
					</div>
					<div class="d-flex gap-2">
						<button type="button" class="btn btn-primary" onClick="' . $save_onclick . '" customer_id="' . $customer_id . '">Lưu</button>
						<button type="button" class="btn btn-outline-default closeEv" data-bs-dismiss="modal">Thoát</button>
					</div>
				</form>
			</div>
		</div>
	</div>';
	echo json_encode(array(
		'error' => 0,
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_crm_task_save_schedule(){
	global $dbconn;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$customer_id = (int) Input::post('customer_id', 0);
	$task_time = Input::post('task_time', '');
	$task_date = Input::post('task_date', '');
	if ($customer_id <= 0 || empty($task_time) || empty($task_date)) {
		echo json_encode(array('error' => 1, 'message' => 'Vui lòng nhập đủ ngày giờ'));
		die();
	}
	$time_receipt = strtotime($task_date . ' ' . $task_time . ':00');
	if (empty($time_receipt)) {
		echo json_encode(array('error' => 1, 'message' => 'Ngày giờ không hợp lệ'));
		die();
	}
	if ($time_receipt < time()) {
		echo json_encode(array('error' => 1, 'message' => 'Không thể đặt lịch trong quá khứ'));
		die();
	}
	$oneCustomer = $clsCustomer->getOne($customer_id, "`task_next_id`");
	$task_next_id = (int) $oneCustomer['task_next_id'];
	if ($task_next_id <= 0) {
		echo json_encode(array('error' => 1, 'message' => 'Chưa có tác nghiệp tiếp để đặt lịch'));
		die();
	}
	$clsCustomer->updateOne($customer_id, array(
		'time_receipt' => $time_receipt,
		'upd_date' => time()
	));
	// Đổi lịch tác nghiệp → đồng bộ follow-up reminder đang chờ (date_id + reminder_time + re-arm) để nhắc bám giờ mới.
	$dbconn->Execute("UPDATE `{$clsFollowUp->tbl}` SET `date_id`={$time_receipt}, `reminder_time`=" . ($time_receipt - 900) . ", `is_send_reminder`=0, `upd_date`=" . time() . " WHERE `customer_id`={$customer_id} AND `target_id`={$task_next_id} AND `status_id`<>" . _FOLLOWUP_STATUS_DONE_ID . " AND `is_trash`=0");
	echo json_encode(array(
		'error' => 0,
		'time_receipt' => $time_receipt,
		'waiting_time_html' => crm_task_waiting_cell_html($customer_id, $time_receipt, $task_next_id)
	));
	die();
}
function default_done_followup(){
	global $smarty, $adminid, $core, $clsISO;
	$clsFollowUp = new FollowUp();
	$customer_id = (int) Input::post('customer_id', 0);
	$followup_id = (int) Input::post('followup_id', 0);
	/** Update */
	$msg = '_error';
	if ($clsFollowUp->updateOne($followup_id, array(
		'status_id'	=> _FOLLOWUP_STATUS_DONE_ID,
		'upd_date'	=> time()
	))) {
		$msg = '_success';
	}
	// Return
	echo json_encode(array(
		"msg"	=> $msg
	));
	die;
}
function default_open_import(){
	global $core, $adminid, $clsISO, $profile_id, $oneProfile, $clsISO;
	$uid = $clsISO->getUniqid();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	###
	$html_campaigns = "";
	$cond = "`is_trash`=0";
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	if ($department_id == _DEPARTMENT_MKT_ID) {
		$cond .= " and (`user_id` in (
			select `profile_id` from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='" . _DEPARTMENT_MKT_ID . "'
		) OR `use_globe`=1)";
	} else {
		$cond .= " and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	}
	$field = "{$clsCampaign->pkey},`title`";
	$list_campaigns = $clsCampaign->getAll("{$cond} and `campaign_type`='_campaign' order by `reg_date` DESC", $field);
	if (!empty($list_campaigns)) {
		foreach ($list_campaigns as $key => $val) {
			$html_campaigns .= sprintf('<option value="%s">%s</option>', $val[$clsCampaign->pkey], $val['title']);
		}
		unset($list_campaigns);
	}
	$html = '<div class="modal-dialog modal-lg">
		<form method="post" action="" enctype="multipart/form-data" class="modal-content" id="frmIssue">
			<div class="modal-header">
				<h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-import text-primary"></i> Nhập khách hàng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body crm-imp-form">
				<div class="crm-imp-sec-h"><i class="bx bx-spreadsheet text-primary"></i> Nguồn dữ liệu</div>
				<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
					<div class="text-muted" style="font-size:13px">Tải file mẫu, điền dữ liệu rồi tải lên — hoặc dán link Google Sheet.</div>
					<a href="' . PCMS_URL . '/templates/Customer.xlsx" target="_blank" class="btn btn-sm btn-outline-success text-nowrap"><i class="bx bx-download me-1"></i> Tải mẫu Excel</a>
				</div>
				<label class="form-label fw-semibold mb-1">File Excel (.xls, .xlsx)</label>
				<div class="input-group mb-1">
					<input type="hidden" name="file_id" value="" />
					<input type="file" name="fileimport" class="form-control fileimport" accept=".xls,.xlsx" onChange="$Core.crm.config_column(this,event)" />
					<button type="button" onClick="$Core.crm.config_column(this,event)" tp="upload_file" class="btn btn-outline-secondary text-nowrap" title="Ánh xạ cột Excel sang trường"><i class="bx bx-cog me-1"></i> Cấu hình cột</button>
				</div>
				<small class="text-muted d-block mb-3">Không cần cột Ngày tạo / Ngày cập nhật. Sau khi chọn file, bấm <b>Cấu hình cột</b> để ánh xạ.</small>
				<div class="crm-imp-or"><span>hoặc</span></div>
				<label class="form-label fw-semibold mb-1">Link Google Sheet</label>
				<div class="input-group mb-1">
					<span class="input-group-text"><i class="bx bxl-google"></i></span>
					<input type="text" class="form-control" name="spreadsheetId" placeholder="Dán URL Google Sheet…" />
					<button type="button" onClick="$Core.crm.config_column(this,event)" tp="google_sheet" class="btn btn-outline-secondary text-nowrap"><i class="bx bx-cog me-1"></i> Cấu hình cột</button>
				</div>
				<small class="text-muted d-block mb-3">Sheet cần Import phải đặt tên <strong class="text-main">"Data"</strong>.</small>
				<div class="crm-imp-sec-h"><i class="bx bx-slider-alt text-primary"></i> Thiết lập nhập</div>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label mb-1">Nguồn khách</label>
						<select name="resource_id" class="form-control form-select required">
							<option value="0">Nguồn gốc</option>
							' . $clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', _CRM_RESOURCE_ADS_ID) . '
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label mb-1 d-flex justify-content-between align-items-center">Chiến dịch <a onClick="$Core.crm.open_campaign(this, event)" href="javascript:void(0);" toId="Import_' . $uid . '" class="text-primary" style="font-size:12px"><i class="bx bx-plus"></i> Thêm</a></label>
						<select name="list_campaign_id[]" id="slb_Campaign_Import_' . $uid . '" data-placeholder="Chiến dịch" multiple="true" class="form-control iso-select2" data-width="100%" data-allow-clear="true">
							<option value="0">Lựa chọn chiến dịch</option>
							' . $html_campaigns . '
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label mb-1">Người phụ trách</label>
						<select class="iso-selectizeNotSearch required" name="admin_id" data-placeholder="Người phụ trách" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff">
							<option value="' . $profile_id . '" selected="selected">' . $clsProfile->getIndentityV2($profile_id, $oneProfile, false) . '</option>
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label mb-1">Người liên quan</label>
						<select class="iso-selectizeNotSearch" name="list_share_id[]" data-placeholder="Người liên quan" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff" multiple="true"></select>
					</div>
					<div class="col-12">
						<div class="d-flex gap-2 align-items-center">
							<label class="switch mb-0"><input type="checkbox" name="auto_create_followups" value="1" /><span class="slider round"></span></label>
							<span style="font-size:13.5px">Tự tạo lịch nhắc (follow-up) sau khi nhập</span>
						</div>
					</div>
				</div>
				<div id="import_preview_' . $uid . '" class="crm-imp-preview mt-3"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" onClick="$Core.crm.preview_import(this, event)" data-preview="import_preview_' . $uid . '"><i class="bx bx-show-alt me-1"></i> Xem trước</button>
			</div>
		</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_preview_import(){
	// Xem trước Import: nạp nguồn (file cache HOẶC Google Sheet) + map cột như do_import nhưng KHÔNG insert. Mỗi dòng: Mới / Trùng (SĐT đã có) / Bỏ qua (thiếu tên-SĐT). Vẫn cho phép trùng.
	global $core, $profile_id, $clsISO, $dbconn;
	$clsCustomer = new Customer();
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	$decoder = new Webmozart\Json\JsonDecoder();
	$file_id = Input::post('file_id');
	$spreadsheetId = Input::post('spreadsheetId');
	$columns = array("name", "phone", "email", "address", "status_id", "begin_need");
	$cachedFileColumn = sprintf('%s/customer/column_%s.json', DIR_CACHE_JSON, $profile_id);
	if (@file_exists($cachedFileColumn)) {
		$columns = $decoder->decodeFile($cachedFileColumn);
	}
	$tblData = array();
	if (!empty($spreadsheetId)) {
		if ($clsISO->checkContainer($spreadsheetId, "docs.google.com", "")) {
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $m);
			$spreadsheetId = isset($m[0]) ? $m[0] : $spreadsheetId;
		}
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		$resp = $service->spreadsheets_values->get($spreadsheetId, 'Data');
		$tblData = $resp->getValues();
	} else if (!empty($file_id)) {
		$cachedFile = DIR_CACHE_JSON . '/customer/' . $file_id . '.json';
		if (@file_exists($cachedFile)) {
			$tblData = $decoder->decodeFile($cachedFile);
		}
	}
	$total = is_array($tblData) ? count($tblData) : 0;
	if ($total <= 1) {
		echo json_encode(array('result' => 0, 'html' => '<div class="alert alert-warning mb-0">Chưa đọc được dữ liệu. Hãy chọn file và bấm <b>cấu hình cột</b> (nút ⚙) trước khi xem trước.</div>'), JSON_UNESCAPED_UNICODE);
		die();
	}
	$rows = array();
	for ($i = 1; $i < $total; $i++) {
		$rc = array();
		foreach ($columns as $ic => $pf) {
			$rc[$pf] = isset($tblData[$i][$ic]) ? $tblData[$i][$ic] : '';
		}
		$name = trim((string) (isset($rc['name']) ? $rc['name'] : ''));
		$phoneRaw = isset($rc['phone']) ? $rc['phone'] : '';
		$phone = !empty($phoneRaw) ? $clsCustomer->formatPhone($phoneRaw) : '';
		$rows[] = array(
			'name'    => $name,
			'phone'   => $phone,
			'email'   => trim((string) (isset($rc['email']) ? $rc['email'] : '')),
			'address' => trim((string) (isset($rc['address']) ? $rc['address'] : ''))
		);
	}
	$existing = array();
	$phones = array();
	foreach ($rows as $r) {
		if ($r['phone'] !== '') {
			$phones[$r['phone']] = 1;
		}
	}
	if (!empty($phones)) {
		$ctbl = DB_PREFIX . 'customer';
		$inList = implode(',', array_map(function ($p) {
			return "'" . addslashes($p) . "'";
		}, array_keys($phones)));
		$res = $dbconn->GetAll("SELECT DISTINCT `phone` FROM `{$ctbl}` WHERE `is_trash`=0 AND `phone` IN ({$inList})");
		if (!empty($res)) {
			foreach ($res as $x) {
				$existing[(string) $x['phone']] = 1;
			}
		}
	}
	$cMoi = $cTrung = $cBo = 0;
	$trs = '';
	$shown = 0;
	$limit = 500;
	foreach ($rows as $idx => $r) {
		if ($r['name'] === '' || $r['phone'] === '') {
			$st = 'skip';
			$cBo++;
			$badge = '<span class="badge bg-label-danger">Bỏ qua</span>';
		} else if (isset($existing[$r['phone']])) {
			$st = 'dup';
			$cTrung++;
			$badge = '<span class="badge bg-label-warning">Trùng</span>';
		} else {
			$st = 'new';
			$cMoi++;
			$badge = '<span class="badge bg-label-success">Mới</span>';
		}
		if ($shown < $limit) {
			$trs .= '<tr class="crm-imp-' . $st . '"><td class="text-muted">' . ($idx + 1) . '</td>'
				. '<td>' . ($r['name'] !== '' ? htmlspecialchars($r['name'], ENT_QUOTES) : '<span class="text-danger">— thiếu —</span>') . '</td>'
				. '<td class="ff-num">' . ($r['phone'] !== '' ? htmlspecialchars($r['phone'], ENT_QUOTES) : '<span class="text-danger">— thiếu —</span>') . '</td>'
				. '<td class="text-muted">' . htmlspecialchars($r['email'], ENT_QUOTES) . '</td>'
				. '<td>' . $badge . '</td></tr>';
			$shown++;
		}
	}
	$willImport = $cMoi + $cTrung;
	$more = (count($rows) > $limit) ? '<div class="text-muted small mt-1">… và ' . (count($rows) - $limit) . ' dòng nữa (đã tính vào tổng).</div>' : '';
	$html = '<div class="crm-imp-sum d-flex flex-wrap gap-2 mb-2">'
		. '<span class="badge bg-label-primary">Tổng ' . count($rows) . ' dòng</span>'
		. '<span class="badge bg-label-success">Mới ' . $cMoi . '</span>'
		. '<span class="badge bg-label-warning">Trùng ' . $cTrung . '</span>'
		. '<span class="badge bg-label-danger">Bỏ qua ' . $cBo . '</span>'
		. '<span class="ms-auto fw-semibold text-main">Sẽ nhập: ' . $willImport . '</span></div>'
		. '<div class="crm-imp-tblwrap"><table class="table table-sm table-hover align-middle mb-0"><thead><tr class="text-muted">'
		. '<th width="44">#</th><th>Tên khách</th><th>SĐT</th><th>Email</th><th width="90">Trạng thái</th></tr></thead><tbody>'
		. $trs . '</tbody></table></div>' . $more
		. '<div class="text-end mt-2"><button type="button" class="btn btn-primary"' . ($willImport > 0 ? '' : ' disabled') . ' onClick="$Core.crm.do_import(this, event)"><i class="bx bx-import me-1"></i> Xác nhận nhập (' . $willImport . ')</button></div>';
	echo json_encode(array('result' => 1, 'html' => $html, 'will_import' => $willImport), JSON_UNESCAPED_UNICODE);
	die();
}
function default_do_import_customer(){
	global $core, $profile_id, $oneProfile, $clsISO, $dbconn;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$clsCustomerAssign = new CustomerAssign();
	$clsCustomerMeta = new CustomerMeta(); // N1.6: thiếu instantiate -> syncByCustomerType() fatal khi import
	$clsCustomerHistory = new CustomerHistory();
	$clsFollowUp = new FollowUp();
	$clsZalo = new Zalo();
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	###
	$current_time = time();
	$totalInsert = $totalDuplicate = 0;
	if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
		$file_id = Input::post('file_id');
		$spreadsheetId = Input::post('spreadsheetId');
		$resource_id = (int) Input::post('resource_id', 0);
		$admin_id = (int) Input::post('admin_id', $profile_id);
		$list_share_id  = Input::post('list_share_id', []);
		$list_campaign_arrs = Input::post('list_campaign_id');
		$auto_create_followups = (int) Input::post('auto_create_followups', 0);
		$general_logs = array();
		if ($resource_id > 0)
			$general_logs[] = sprintf('Nguồn gốc: %s', $clsProperty->getTitle($resource_id));
		if (!empty($list_share_id))
			$general_logs[] = sprintf('Người liên quan: %s', $clsProfile->getNameArray($list_share_id, ","));
		if (!empty($list_campaign_arrs))
			$general_logs[] = sprintf('Chiến dịch: %s', $clsCampaign->getTitleArray($list_campaign_arrs));
		$ad_field = "`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
		$adProfile = $clsProfile->getOne($admin_id, $ad_field);
		if (!empty($file_id) || !empty($spreadsheetId)) {
			$columns = ["name", "phone", "email", "address", "status_id", "begin_need"];
			$cachedColumnName = sprintf('column_%s.json', $profile_id);
			$cachedFileColumn = sprintf('%s/customer/%s', DIR_CACHE_JSON, $cachedColumnName);
			if (@file_exists($cachedFileColumn)) {
				$columns = $decoder->decodeFile($cachedFileColumn);
			}
			if (!empty($spreadsheetId)) {
				if ($clsISO->checkContainer($spreadsheetId, "docs.google.com", "")) {
					@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
					// $clsISO->print_pre($matches); die();
					$spreadsheetId = $matches[0];
				}
				#- Require library		
				require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
				/** Init Client */
				$client = new Google_Client();
				$client->setClientId(GOOGLE_CLIENT_ID);
				$client->setClientSecret(GOOGLE_CLIENT_SECRET);
				$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
				$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
				$service = new Google_Service_Sheets($client);
				// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
				// get all the rows of a sheet
				$range = 'Data'; // here we use the name of the Sheet to get all the rows
				$response = $service->spreadsheets_values->get($spreadsheetId, $range);
				$tblData = $response->getValues();
			} else {
				$tblData = [];
				$cachedName = sprintf('%s.json', $file_id);
				$cachedFile = DIR_CACHE_JSON . '/customer/' . $cachedName;
				if (@file_exists($cachedFile)) {
					$tblData = $decoder->decodeFile($cachedFile);
					@unlink($cachedFile);
				}
			}
			if (!empty($tblData)) {
				$arr_data_customer = array();
				$total_record = @count($tblData);
				if ($total_record > 1) {
					for ($i = 1; $i < $total_record; $i++) {
						$arr_customer = [];
						foreach ($columns as $i_col => $p_field) {
							$arr_customer[$p_field] = $tblData[$i][$i_col];
						}
						$arr_data_customer[] = $arr_customer;
					}
				}
				$customer_insert = [];
				foreach ($arr_data_customer as $key => $_oCus) {
					$name = $_oCus["name"];
					$email = !empty($_oCus["email"]) ? $_oCus["email"] : "";
					$phone = !empty($_oCus["phone"]) ? $clsCustomer->formatPhone($_oCus["phone"]) : "";
					$address = !empty($_oCus["address"]) ? $_oCus["address"] : "";
					$status = !empty($_oCus["status"]) ? $_oCus["status"] : "";
					$begin_need = !empty($_oCus["begin_need"]) ? $_oCus["begin_need"] : "";
					$action_date = !empty($_oCus["action_date"]) ? $_oCus["action_date"] : "";
					$customer_sales = !empty($_oCus["customer_sales"]) ? $_oCus["customer_sales"] : "";
					$customer_content = !empty($_oCus["customer_content"]) ? $_oCus["customer_content"] : "";
					$gender_name = !empty($_oCus["gender"]) ? $_oCus["gender"] : "";
					$birthday = !empty($_oCus["birthday"]) ? $_oCus["birthday"] : "";
					$agency_name = !empty($_oCus["agent_id"]) ? $_oCus["agent_id"] : "";
					$tiktok_link = !empty($_oCus["tiktok_link"]) ? $_oCus["tiktok_link"] : "";
					$facebook_link = !empty($_oCus["facebook_link"]) ? $_oCus["facebook_link"] : "";
					/** Khai báo logs */
					$content_logs = $more_information = $action_logs = array();
					if (!empty($name)) $content_logs[] = sprintf('Tên khách hàng: %s', $name);
					if (!empty($address)) $content_logs[] = sprintf('Địa chỉ: %s', $address);
					if (!empty($begin_need)) $content_logs[] = sprintf('Nhu cầu: %s', $begin_need);
					$content_logs = array_merge($content_logs, $general_logs);
					$action_logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(),
						'user_id' => $profile_id,
						'content' => sprintf(
							'<strong>%s</strong> đã thêm mới khách hàng với %s',
							$clsProfile->getFullName($profile_id, $oneProfile),
							implode(',', $content_logs)
						)
					);
					if ($admin_id != $profile_id) {
						$list_share_id[] = $profile_id;
						$action_logs[$clsISO->getUniqid()] = array(
							'reg_date' => time(),
							'user_id' => $profile_id,
							'content' => sprintf(
								'<strong>%s</strong> đã giao <strong>%s</strong> quản lý khách hàng',
								$clsProfile->getFullName($profile_id, $oneProfile),
								$clsProfile->getFullName($admin_id, $adProfile)
							)
						);
					}
					$agent_id = $gender_id = 0;
					if (!empty($agency_name)) {
						$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_AGENCY' AND (`slug`='" . $core->replaceSpace($agency_name) . "' OR `property_code`='{$agency_name}')", $clsProperty->pkey);
						$agent_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					if (!empty($gender_name)) {
						$tmp = $clsProperty->getByCond("`is_trash`=0 AND `property_type`='_GENDER' AND (`slug`='" . $core->replaceSpace($gender_name) . "' OR `property_code`='{$gender_name}')", $clsProperty->pkey);
						$gender_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
					}
					$more_information['agent_id'] = $agent_id;
					$more_information['gender_id'] = $gender_id;
					$more_information['tiktok'] = $tiktok_link;
					$more_information['facebook'] = $facebook_link;
					$more_information['action_logs'] = $action_logs;
					if (!empty($name) && !empty($phone)) {
						// N1.6: đóng nháy dedup + cho phép trùng (vẫn insert) + cờ is_dup_phone set rõ mỗi dòng (chống leak)
						$lstcheck = $clsCustomer->getByCond("`admin_id`='{$profile_id}' AND `phone`='{$phone}'", $clsCustomer->pkey);
						$more_information['is_dup_phone'] = !empty($lstcheck) ? 1 : 0;
						if (!empty($lstcheck)) {
							$totalDuplicate++;
						} {
							$customer_id = $clsCustomer->getMaxId();
							if ($clsCustomer->insert(array(
								$clsCustomer->pkey => $customer_id,
								'name'	=> $name,
								'name_slug'	=> $core->replaceSpace($name),
								'status_id'	=> _CRM_STATUS_LEAD_ID,
								'task_current_id' => defined('_CRM_TASK_LEAD_ID') ? (int)_CRM_TASK_LEAD_ID : 0,
								'resource_id' => $resource_id,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'birthday' => $clsCustomer->dobToTimestamp($birthday),
								'email'	=> $email,
								'phone'	=> $phone,
								'address'  => $address,
								'admin_id' => $admin_id,
								'begin_need' => $begin_need,
								'user_id'	 => $profile_id,
								'user_id_update' => $profile_id,
								'reg_date'	=> $current_time,
								'upd_date'	=> $current_time,
							))) {
								$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $list_share_id, $profile_id);
								// MKT tạo + nguồn Quảng Cáo → auto-thêm Huynh (_PROFILE_PTH_ID) làm người liên quan
								$clsCustomer->ensureMarketingAdsShare($customer_id, $resource_id);
								$clsCustomerMeta->syncByCustomerType($customer_id, 'campaign', $list_campaign_arrs, $profile_id);
								// Import giao cho sale khác: tạo phiếu CHỜ XÁC NHẬN (createPending tự bỏ qua nếu giao cho chính mình).
								$clsCustomerAssign->createPending($customer_id, $admin_id, $profile_id, 1);
								$totalInsert++;
								$customer_insert[] = [
									"customer_name"	=>	$name,
									"phone"	=>	$phone,
									"begin_need"	=>	$begin_need,
								];
								$clsCustomerHistory->insert(array(
									'customer_id' => $customer_id,
									'from_status_id' => 0,
									'to_status_id' => _CRM_STATUS_LEAD_ID,
									'staff_id' => $admin_id,
									'action_date' => $current_time
								));
								if (!empty($action_date) && !empty($customer_sales) && !empty($customer_content)) {
									$tmp = $clsProfile->getByCond("`is_trash`=0 AND `full_name_slug`='" . $core->replaceSpace($customer_sales) . "'", $clsProfile->pkey);
									if (!empty($tmp)) {
										$clsFollowUp->insert(array(
											'type_id' => _FOLLOWUP_CALL_ID,
											'status_id' => _FOLLOWUP_STATUS_DONE_ID,
											'customer_id' => $customer_id,
											'date_id' => $clsISO->convertTextToTime($action_date),
											'intro' => $customer_content,
											'admin_id' => $tmp[$clsProfile->pkey],
											'user_id' => $tmp[$clsProfile->pkey],
											'user_id_update' => $tmp[$clsProfile->pkey],
											'reg_date' => $current_time,
											'upd_date' => $current_time
										));
									}
								}
								if ($auto_create_followups == 1) {
									$timer = strtotime(date('d-m-Y'));
									$list_times = array(
										'1day' => strtotime('+1 day', $timer),
										'2day' => strtotime('+2 days', $timer),
										'5day' => strtotime('+5 days', $timer),
										'15day' => strtotime('+15 days', $timer),
										'30day' => strtotime('+30 days', $timer)
									);
									foreach ($list_times as $key => $date_id) {
										if ($clsFollowUp->insert(array(
											'type_id' => _FOLLOWUP_CALL_ID,
											'status_id' => _FOLLOWUP_STATUS_PLAN_ID,
											'customer_id' => $customer_id,
											'date_id' => $date_id,
											'intro' => 'Call liên hệ lại khách',
											'admin_id' => $admin_id,
											'user_id' => $profile_id,
											'user_id_update' => $profile_id,
											'reg_date' => $current_time,
											'upd_date' => $current_time
										))) {
											$titleNoty = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProperty->getTitle(_FOLLOWUP_CALL_ID) . ": Call liên hệ lại khách", $clsCustomer->getName($customer_id), $clsISO->convertTimeToText($date_id, true));
											$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNoty, $date_id, '|' . $admin_id . '|');
										}
									}
								}
								if ($admin_id != $profile_id) {
									if ($clsCustomer->isRootProfile()) {
										$clsCustomerSales->insert(array(
											'customer_id' => $customer_id,
											'admin_id' => $admin_id,
											'assign_date' => time()
										));
									}
									$oCustomer = array('name' => $name);
									$titleNoty = sprintf(
										'<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>',
										$clsProfile->getFullName($profile_id, $oneProfile),
										$clsCustomer->getName($customer_id, $oCustomer)
									);
									$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(), "|" . $admin_id . "|");
									#thong bao app
									$clsNotification = new Notification();
									$params = [
										'title' => "CRM - Khách hàng mới",
										'body' => strip_tags($titleNoty),
										'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
									];
									$clsNotification->doPushMessagingUser($params, [$admin_id]);
								}
							}
						}
					}
				}
				if ($totalInsert > 0 && $admin_id != $profile_id) {
					$zaloId = $clsProfile->getZaloId($admin_id, $adProfile);
					if (!empty($zaloId)) {
						$clsZalo->sendNotifyCRMZalo($admin_id, $adProfile, $totalInsert, $customer_insert);
					}
				}
			}
		}
	}
	// Return
	echo ('0$$$' . $totalInsert . '$$$' . $totalDuplicate);
	die();
}
function default_manager_campaign(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsCampaign = new Campaign();
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for ($i = 0; $i < 20; $i++) {
		$list_preloaders[] = $i;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_manager');
	$html = $core->build('_ajax.campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_load_campaigns(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$mapCountCampaign = array();
	$tmpCounts = $dbconn->GetAll("SELECT `meta_id` AS `campaign_id`, COUNT(DISTINCT `customer_id`) AS `total_customer`
		FROM `{$clsCustomerMeta->tbl}`
		WHERE `meta_type`='campaign'
		GROUP BY `meta_id`");
	if (!empty($tmpCounts)) {
		foreach ($tmpCounts as $row) {
			$mapCountCampaign[(int)$row['campaign_id']] = (int)$row['total_customer'];
		}
	}
	$html = '';
	$list_campaigns = $clsCampaign->getAll("`campaign_type`='_campaign'  ORDER BY `reg_date` DESC");
	if (!empty($list_campaigns)) {
		$ii = 1;
		foreach ($list_campaigns as $campaign) {
			$campaign_id = $campaign[$clsCampaign->pkey];
			$total_cus = isset($mapCountCampaign[$campaign_id]) ? (int)$mapCountCampaign[$campaign_id] : 0;
			$html .= '<tr>
				<td class="text-center">' . ($ii) . '</td>
				<td class="align-center">' . $campaign['title'] . '</td>
				<td class="align-center text-center">' . $total_cus . '</td>
				<td class="text-center">
					<div class="dropdown">
						<button type="button" data-bs-toggle="dropdown" 
							class="btn btn-link btn-icon btn-sm dropdown-toggle hide-arrow rounded-pill text-muted">
							<i class=\'bx bx-dots-vertical-rounded\'></i>
						</button>
						<div class="dropdown-menu min-w-px-150">
							<a class="dropdown-item cursor-pointer" onClick="$Core.global.crm.open_campaign(this, event)" 
								campaign_id="' . $campaign_id . '"><i class="bx bx-pencil"></i> Sửa</a>
							<a class="dropdown-item cursor-pointer" onClick="$Core.global.crm.delete_campaign(this, event)" 
								campaign_id="' . $campaign_id . '"><i class="bx bx-trash"></i> Xóa</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item cursor-pointer" onClick="$Core.global.crm.merge_campaign(this, event)" 
								campaign_id="' . $campaign_id . '"><i class="bx bx-vector"></i> Gộp khách</a>	
						</ul>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		unset($list_campaigns);
	}
	// Return
	echo json_encode(array(
		'html' => $html
	));
	die();
}
function default_open_campaign(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsSetting = new Setting();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	#
	$uid = $clsISO->getUniqid();
	$toId = Input::post('toId');
	$campaign_id = (int) Input::post('campaign_id', 0);
	#
	$field = "{$clsProject->pkey},`title`";
	$arr_projects = $clsProject->getAll("`is_trash`=0", $field);
	$smarty->assign('arr_projects', $arr_projects);
	#
	$action = "_add";
	$arr_blocks = array();
	$oneCampaign = array('title' => '');
	$campaign_config = array('project_mapping_id' => 0);
	$titlePage = 'Thêm mới chiến dịch';
	if ($campaign_id > 0) {
		$action = "_edit";
		$titlePage = 'Cập nhật chiến dịch';
		$oneCampaign = $clsCampaign->getOne($campaign_id);
		$campaign_config = $oneCampaign['campaign_config'];
		$campaign_config = $clsISO->to_array_json($campaign_config);
		$project_id = (int) $core->get_field($campaign_config, "project_id", 0);
		if ($project_id > 0) {
			$field = "{$clsProperty->pkey},`title`";
			$arr_blocks = $clsProperty->getAll("`property_type`='_BLOCK' AND `for_id`='{$project_id}'", $field);
			// $clsISO->print_pre($arr_blocks); die();
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('toId', $toId);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('campaign_id', $campaign_id);
	$smarty->assign('arr_blocks', $arr_blocks);
	$smarty->assign('oneCampaign', $oneCampaign);
	$smarty->assign('campaign_config', $campaign_config);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_pop_save_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	###
	$msg = "_error";
	$campaign_id = (int) Input::post('campaign_id', 0);
	$title = Input::post('title');
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$use_globe = (int) Input::post('use_globe', 0);
	if ($campaign_id == 0) {
		$campaign_id = $clsCampaign->getMaxId();
		$campaign_config = $campaign_info = array();
		$campaign_config['project_id'] = $project_id;
		$campaign_config['block_id'] = $block_id;
		$dbconn->debug = true;
		if ($clsCampaign->insert(array(
			$clsCampaign->pkey => $campaign_id,
			'campaign_type' => '_campaign',
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'campaign_info' => json_encode($campaign_info, JSON_UNESCAPED_UNICODE),
			'campaign_config' => json_encode($campaign_config, JSON_UNESCAPED_UNICODE),
			'use_globe' => $use_globe,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))) {
			$msg = "_success";
		}
	} else {
		$oneCampaign = $clsCampaign->getOne($campaign_id);
		$campaign_config = $oneCampaign['campaign_config'];
		$campaign_config = $clsISO->to_array_json($campaign_config);
		$campaign_config['project_id'] = $project_id;
		$campaign_config['block_id'] = $block_id;
		if ($clsCampaign->updateOne($campaign_id, array(
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'campaign_config' => json_encode($campaign_config, JSON_UNESCAPED_UNICODE),
			'use_globe' => $use_globe,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))) {
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'name' => $title,
		'campaign_id' => $campaign_id
	));
	die();
}
function default_delete_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	#
	$msg = "_error";
	$campaign_id = (int) Input::post('campaign_id', 0);
	if ($campaign_id > 0 && $clsCampaign->deleteOne($campaign_id)) {
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		"msg" => $msg
	));
	die();
}
function default_merge_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	#
	$msg = "_error";
	$uid = $clsISO->getUniqid();
	$campaign_id = (int) Input::post('campaign_id', 0);
	$oneCampaign = $clsCampaign->getOne($campaign_id);
	#
	$html_campaigns = "";
	$list_campaigns = $clsCampaign->getAll("{$clsCampaign->pkey}<>'{$campaign_id}' AND `campaign_type`='_campaign' ORDER BY `reg_date` DESC");
	if (!empty($list_campaigns)) {
		$ii = 1;
		foreach ($list_campaigns as $campaign) {
			$html_campaigns .= sprintf('<option value="%s">%s</option>', $campaign[$clsCampaign->pkey], $campaign['title']);
			++$ii;
		}
		unset($list_campaigns);
	}
	$smarty->assign('campaign_id', $campaign_id);
	$smarty->assign('oneCampaign', $oneCampaign);
	$smarty->assign('html_campaigns', $html_campaigns);
	// Return
	$smarty->assign('template_type', '_merge');
	$html = $core->build('_ajax.campaign.tpl');
	echo json_encode(array(
		"uid" => $uid,
		"html" => $html
	));
	die();
}
function default_do_merge_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsCampaign = new Campaign();
	#
	$msg = "_error";
	$merge_id = (int) Input::post('merge_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	#
	if ($merge_id > 0 && $campaign_id > 0) {
		$list_customers = $clsCustomerMeta->getAll("`meta_type`='campaign' AND `meta_id`='{$merge_id}'", "customer_id");
		if (!empty($list_customers)) {
			$done = array();
			foreach ($list_customers as $key => $val) {
				$customer_id = (int) $val['customer_id'];
				if ($customer_id <= 0 || isset($done[$customer_id])) {
					continue;
				}
				$done[$customer_id] = 1;
				$campaign_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'campaign');
				$campaign_arrs = array_values(array_diff($campaign_arrs, array($merge_id)));
				$campaign_arrs[] = $campaign_id;
				$campaign_arrs = $clsCustomer->normalizeIdArray($campaign_arrs);
				$clsCustomerMeta->syncByCustomerType($customer_id, 'campaign', $campaign_arrs, $profile_id);
				$msg = "_success";
			}
			unset($list_customers);
		}
	}
	// Return
	echo json_encode(array('msg' => $msg));
	die();
}
function default_open_help(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$SiteMsg_CRM_Help = $clsConfiguration->getValue('SiteMsg_CRM_Help');
	$html = '<div class="modal-dialog modal-ipad modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">Hướng dẫn sử dụng CRM</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="tinyContent">
					' . html_entity_decode($SiteMsg_CRM_Help) . '
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'uid' => $clsISO->getUniqid()
	));
	die();
}
function default_open_participant(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id', 0);
	$arr_share_ids = $clsCustomer->getShareIds($customer_id);
	#
	$html_user_participants = "";
	if (!empty($arr_share_ids)) {
		foreach ($arr_share_ids as $key => $val) {
			$html_user_participants .= '<option value="' . $val . '" selected>' . $clsProfile->getFullName($val) . '</option>';
		}
	}
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('html_user_participants', $html_user_participants);
	// Return
	$html = $core->build('_ajax.participant.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_pop_save_participant(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$customer_id = (int) Input::post('customer_id', 0);
	$user_participants = Input::post('user_participants');
	$oCustomer = $clsCustomer->getOne($customer_id, "`name`,`admin_id`,`more_information`");
	$admin_id = (int) $oCustomer['admin_id'];
	$more_information = $oCustomer['more_information'];
	$list_share_arrs = $clsCustomer->getShareIds($customer_id, $oCustomer);
	$more_information = $clsISO->to_array_json($more_information);
	$action_logs = $core->get_field($more_information, "action_logs", []);
	###
	$use_globe = 0;
	if (!empty($user_participants)) {
		$use_globe = 1;
		$user_participants = @array_diff($user_participants, array($admin_id));
	}
	$participants_added_arrs = $participants_removed_arrs = array();
	if (!empty($user_participants) && empty($list_share_arrs)) {
		$participants_added_arrs = $user_participants;
	} else if (!empty($user_participants) && !empty($list_share_arrs)) {
		foreach ($user_participants as $usr_id) {
			if (!in_array($usr_id, $list_share_arrs)) {
				$participants_added_arrs[] = $usr_id;
			}
		}
	}
	if (!empty($list_share_arrs) && empty($user_participants)) {
		$participants_removed_arrs = $list_share_arrs;
	} else if (!empty($user_participants) && !empty($list_share_arrs)) {
		foreach ($list_share_arrs as $key => $val) {
			if (!in_array($val, $user_participants)) {
				$participants_removed_arrs[] = $val;
			}
		}
	}
	if (!empty($participants_added_arrs) || !empty($participants_removed_arrs)) {
		$participants_name_added_arrs = $participants_name_removed_arrs = array();
		$participants_merge_arrs = array_merge($participants_added_arrs, $participants_removed_arrs);
		if (!empty($participants_merge_arrs)) {
			$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (" . implode(',', $participants_merge_arrs) . ")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
			foreach ($tmp as $key => $val) {
				if (!empty($participants_added_arrs) && in_array($val[$clsProfile->pkey], $participants_added_arrs)) {
					$participants_name_added_arrs[] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
				} else if (!empty($participants_removed_arrs) && in_array($val[$clsProfile->pkey], $participants_removed_arrs)) {
					$participants_name_removed_arrs[] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
				}
			}
			unset($tmp);
		}
		if (!empty($participants_added_arrs) && empty($participants_removed_arrs)) {
			$content = sprintf(
				"<strong>%s</strong> đã thêm <strong>%s</strong> vào danh sách nguời liên quan",
				$clsProfile->getFullName($profile_id, $oneProfile),
				implode(',', $participants_name_added_arrs)
			);
		} else if (empty($participants_added_arrs) && !empty($participants_removed_arrs)) {
			$content = sprintf(
				"<strong>%s</strong> đã gỡ bỏ <strong>%s</strong> trong danh sách nguời liên quan",
				$clsProfile->getFullName($profile_id, $oneProfile),
				implode(',', $participants_name_removed_arrs)
			);
		} else {
			$content = sprintf("<strong>%s</strong> đã thêm vào <strong></strong> và gỡ bỏ <strong>%s</strong> danh sách nguời liên quan", $clsProfile->getFullName($profile_id, $oneProfile), implode(',', $participants_name_added_arrs), implode(',', $participants_name_removed_arrs));
		}
		$action_logs[$clsISO->getUniqid()] = array(
			'content' => $content,
			'user_id' => $user_id,
			'reg_date' => time()
		);
		$more_information['action_logs'] = $action_logs;
	}
	// $clsISO->print_pre($participants_added_arrs); die();
	if ($clsCustomer->updateOne($customer_id, array(
		'use_globe' => $use_globe,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $user_participants, $profile_id);
		$clsCustomer->syncShareIds($customer_id, $user_participants, $profile_id, false);
		$msg = "_success";
		if (!empty($participants_added_arrs)) {
			$titleNoty = sprintf(
				'<strong>%s</strong> đã gán bạn liên quan tới khách hàng <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$clsCustomer->getName($customer_id, $oCustomer)
			);
			$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(), $participants_added_arrs);
		}
		if (!empty($participants_removed_arrs)) {
			$titleNoty = sprintf(
				'<strong>%s</strong> đã xóa bạn liên quan tới khách hàng <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$clsCustomer->getName($customer_id, $oCustomer)
			);
			$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(), $participants_removed_arrs);
		}
	}
	// return
	echo $msg;
	die();
}
function default_setting_config_crawl(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID, $profile_id, $clsConfiguration;
	$clsProject = new Project();
	$type = Input::post("_type", "_OPEN");
	$html = "";
	if ($type == "_OPEN") {
		$crm_crawl_config = $clsConfiguration->getValue("crm_crawl_config");
		$crm_crawl_config = $clsISO->to_array_json($crm_crawl_config);
		$uid = $clsISO->getUniqid();
		$smarty->assign('uid', $uid);
		$smarty->assign('crm_crawl_config', $crm_crawl_config);
		$html = $core->build('_ajax.open_setting.tpl');
	} else if ($type == "_SAVE") {
		$crawl_config = Input::post('crawl_config', array());
		$crm_crawl_config = [];
		if (!empty($crawl_config["spreadsheet_id"])) {
			foreach ($crawl_config["spreadsheet_id"] as $key => $spreadsheet_id) {
				$crm_crawl_config[$key] = [
					"spreadsheet_id"	=>	$spreadsheet_id,
					"sheet_id"	=>	$crawl_config["sheet_id"][$key],
					"sheet_name"	=>	$crawl_config["sheet_name"][$key],
				];
			}
		}
		$clsConfiguration->updateValue('crm_crawl_config', json_encode($crm_crawl_config, JSON_UNESCAPED_UNICODE));
	}
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	));
	die();
}
function default_open_sheet(){
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $core, $clsISO, $_LANG_ID, $dbconn;
	$clsTemporary = new Temporary();
	$clsCrawl = new Crawl();
	##
	$uid = $clsISO->getUniqid();
	$gId = Input::post('gId');
	$spreadsheetId = Input::post('spreadsheetId');
	$arr_worksheets = $list_worksheets = array();
	if (!empty($spreadsheetId)) {
		if ($clsISO->checkContainer($spreadsheetId, "docs.google.com", "")) {
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			// $clsISO->print_pre($matches); die();
			$spreadsheetId = $matches[0];
		}
		/** Required Lib */
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes([Google_Service_Drive::DRIVE]);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		try {
			$resource_id = $spreadsheetId;
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$list_worksheets = $spreadsheet->sheets;
		} catch (Exception $ex) {
			$msg_error = $ex->getMessage();
			if (json_decode($msg_error)->error->status == "FAILED_PRECONDITION") {
				$spreadsheetIdCopy = $clsCrawl->copySpreadsheet($spreadsheetId, [], 0, 0, 0);
				$resource_id = $spreadsheetIdCopy;
				$spreadsheet = $service->spreadsheets->get($spreadsheetIdCopy);
				$list_worksheets = $spreadsheet->sheets;
			}
		}
		if (!empty($list_worksheets)) {
			foreach ($list_worksheets as $sheet) {
				$id = $sheet->properties['sheetId'];
				$name = $sheet->properties['title'];
				$arr_worksheets[$id] = $name;
			}
		}
	}
	$smarty->assign('gId', $gId);
	$smarty->assign('spreadsheetId', $spreadsheetId);
	$smarty->assign('arr_worksheets', $arr_worksheets);
	// Return
	$html = $core->build('_ajax.open_sheet.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	));
	die();
}
function default_open_config_column(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list, $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $menu_current,
		$current_page, $core, $clsModule, $clsButtonNav, $clsConfiguration, $clsISO, $dbconn;
	$clsStock = new Stock();
	$clsCustomer = new Customer();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsTemporary = new Temporary();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	$uid = $clsISO->getUniqid();
	#
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	#- Init Client
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID_DEV);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET_DEV);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN_DEV);
	$client->setScopes([Google_Service_Drive::DRIVE]);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	$sid = Input::post('sid');
	$spreadsheetId = Input::post('spreadsheetId', "");
	$sheet_ids = Input::post('sheet_id', "");
	$ranges = Input::post('sheet_name', "");
	$arr_data = $column_data = array();
	if (!empty($ranges) && !empty($spreadsheetId)) {
		$sheet_ids = explode("|", $sheet_ids);
		$ranges = explode("|", $ranges);
		$arr_sheet_range = [];
		foreach ($ranges as $key => $range) {
			$arr_sheet_range["'" . $range . "'"] = $sheet_ids[$key];
		}
		$arr_data = $clsCustomer->getDataConfigColumn($spreadsheetId, $ranges, $client);
		$cachedFileData = DIR_CACHE_JSON . '/customer/data_crawl.json';
		$encoder->encodeFile($arr_data, $cachedFileData);
		$number_column_sheet = [];
		foreach ($arr_data as $key => $data_sheet) {
			$highestColumnIndex = 0;
			foreach ($data_sheet as $data) {
				$max_column = count($data);
				if ($max_column > $highestColumnIndex) {
					$highestColumnIndex = $max_column;
				}
			}
			$number_column_sheet[$key] = $highestColumnIndex + 1;
		}
		$number_check = [];
		$cachedFile = DIR_CACHE_JSON . '/customer/config_crawl_customer.json';
		if (file_exists($cachedFile)) {
			$decoder = new Webmozart\Json\JsonDecoder();
			$data_config = $decoder->decodeFile($cachedFile);
			$number_check = !empty($data_config["number_check"]) ? $data_config["number_check"] : [];
		}
		$highestColumnIndex = ($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) ? 25 : 35;
		$smarty->assign("number_column_sheet", $number_column_sheet);
		$smarty->assign("arr_sheet_range", $arr_sheet_range);
		$smarty->assign("number_check", $number_check);
		$smarty->assign("clsStock", $clsStock);
		$smarty->assign("column_data", $column_data);
		$smarty->assign("agency_id", $agency_id);
		$smarty->assign("target_id", $target_id);
		$smarty->assign("stock_type", $stock_type);
		$smarty->assign("highestColumnIndex", $highestColumnIndex);
		$smarty->assign("arr_data", $arr_data);
		$smarty->assign("uid", $uid);
		// Return
		$html = $core->build("_ajax.open_config_column.tpl");
		echo json_encode(array(
			'result' =>	true,
			'uid' =>	$uid,
			'html' => $html
		));
		die();
	} else {
		$res = array(
			"result" =>	false,
			'msg' => "Vui lòng nhập đủ thông tin spreasheetID và sheetname",
		);
	}
	echo json_encode($res);
	die();
	//	$clsISO->print_pre($arr_data);die;
}
function default_do_config_column(){
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule, $clsButtonNav, $clsConfiguration, $clsISO, $dbconn, $smarty;
	$decoder = new Webmozart\Json\JsonDecoder();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawlLowFloor = new CrawlLowFloor();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	#
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$encoder = new Webmozart\Json\JsonEncoder();
	#
	$uid = $clsISO->getUniqid();
	$sheet_name = 	Input::post('sheet_name', "");
	$number_check = 	Input::post('number_check', array());
	$columns = 	Input::post('columns', array());
	$spreadsheetId = 	Input::post('sheetID', "");
	$gId = 	Input::post('gId', "");
	$cachedFileData = DIR_CACHE_JSON . '/customer/data_crawl.json';
	if (file_exists($cachedFileData)) {
		$cache_data = $decoder->decodeFile($cachedFileData);
		//		@unlink($cachedFileData);
	}
	$column_data = array();
	if (!empty($number_check) && !empty($cache_data)) {
		$cachedFile = DIR_CACHE_JSON . '/customer/config_crawl_customer.json';
		$field_column = [];
		if (!empty($cache_data)) {
			foreach ($cache_data as $key => $arr_data) {
				$row_check = $number_check[$key];
				foreach ($arr_data as $k => $v) {
					if ($k == $row_check) {
						$field_column[$key] = $v;
						break;
					}
				}
			}
		}
		$arr_data = [
			"number_check" => $number_check,
			"field_column" => $field_column,
		];
		$encoder->encodeFile($arr_data, $cachedFile);
	}
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid
	));
	die();
}
function default_req_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	##
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for ($i = 0; $i < 30; $i++) {
		$list_preloaders[] = $i;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('_ajax.req_customer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_load_req_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	##
	$html = "";
	$uid = Input::post('uid');
	$status = (int) Input::post('status', 0);
	$total_record = $total_assigned = $total_unassigned = 0;
	$list_items = $clsRequestCus->getAll("`_type` <>'_buy' ORDER BY `reg_date` DESC");
	if (!empty($list_items)) {
		$total_record = count($list_items);
		$arr_profile_cached = array();
		$tmp = $clsProfile->getAll("`is_trash`=0", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
		if (!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				$arr_profile_cached[$val[$clsProfile->pkey]] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
			}
			unset($tmp);
		}
		foreach ($list_items as $key => $val) {
			$user_id = (int) $val['user_id'];
			$user_status_id = (int) $val['user_status_id'];
			$status_date = $val['status_date'];
			if ($val['status'] == 1) {
				$total_assigned += 1;
			} else {
				$total_unassigned += 1;
			}
			$html .= '<tr class="' . ($status == 1 ? ($val['status'] == 0 ? 'd-none' : "") : ($status == 2 ? ($val['status'] == 1 ? "d-none" : "") : '')) . '">
				<td class="align-center">' . $clsISO->convertTimeToText($val['reg_date'], true) . '</td>
				<td class="align-center">' . $arr_profile_cached[$user_id] . '</td>
				<td class="align-center text-center">' . $val['amount'] . '</td>
				<td class="align-center">' . $val['project_name'] . '</td>
				<td class="align-center text-center">
					<label class="switch" title="Đã xử lý">
						<input type="checkbox" value="1" name="status" uid="' . $uid . '" onChange="$Core.global.crm.status_req_customer(this, event)"' . ($val['status'] == 1 ? ' checked' : '') . ' request_cus_id="' . $val[$clsRequestCus->pkey] . '">
						<span class="slider round"></span>
					</label></td>
				<td class="align-center">' . ($user_status_id > 0 ? $arr_profile_cached[$user_status_id] : "--") . '</td>
				<td class="align-center">' . ($status_date > 0 ? $clsISO->convertTimeToText($status_date, true) : "--") . '</td>
			</tr>';
		}
	} else {
		$html .= '<tr>
			<td colspan="7" class="text-center border-end">
				<img src="' . URL_IMAGES . '/DataEmpty.svg" class="w-px-100" />
				<p class="text-muted">Chưa có yêu cầu nào</p>
			</td>
		</tr>';
	}
	$html_briefs = '<div onClick="$Core.global.crm.do_search_req_customer(this, event)" 
		class="brief-item a1a bg-orange flex-fill cursor-pointer" status="0" uid="' . $uid . '">
		<p class="text-fs-14 mb-0">Tổng y/c</p>
		<hr class="w-px-50 my-2">
		<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">' . $total_record . '</h3>
	</div>
	<div onClick="$Core.global.crm.do_search_req_customer(this, event)" status="1" 
		class="brief-item a2a bg-azure flex-fill cursor-pointer" uid="' . $uid . '">
		<p class="text-fs-14 mb-0">Đã xử lý</p>
		<hr class="w-px-50 my-2">
		<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">' . $total_assigned . '</h3>
	</div>
	<div onClick="$Core.global.crm.do_search_req_customer(this, event)" status="2" 
		class="brief-item a2a bg-purple flex-fill cursor-pointer" uid="' . $uid . '">
		<p class="text-fs-14 mb-0">Chưa xử lý</p>
		<hr class="w-px-50 my-2">
		<h3 class="text-fs-20 xs:text-fs-16 mb-0 text-white">' . $total_unassigned . '</h3>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'html_briefs' => $html_briefs
	));
	die();
}
function default_status_req_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	$msg = "_error";
	$status = (int) Input::post('status', 0);
	$request_cus_id = (int) Input::post('request_cus_id', 0);
	if ($request_cus_id > 0) {
		if ($clsRequestCus->updateOne($request_cus_id, array(
			'status' => $status,
			'user_status_id' => $profile_id,
			'status_date' => time()
		))) {
			$msg = "_error";
		}
	}
}
function default_open_req_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsCity = new City();
	$clsCountry = new Country();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.req_customer.tpl');
	echo json_encode(array(
		'uid' => 'modal' . $clsISO->getUniqid(),
		'html' => $html
	));
	die();
}
function default_save_req_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	$clsZalo = new Zalo();
	###
	$msg = "_error";
	$amount = (int) Input::post('amount', 0);
	$project_name = Input::post('project_name');
	$notes = Input::post('notes');
	$more_information = array(
		'amount' => $amount,
		'project_name' => $project_name,
		'notes' => $notes
	);
	// $dbconn->debug = true;
	if ($clsRequestCus->insert(array(
		'amount' => $amount,
		'project_name' => $project_name,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'user_id' => $profile_id,
		'reg_date' => time()
	))) {
		$msg = "_success";
		// Send Zalo
		$message = "======================";
		$message .= "\n";
		$message .= sprintf("📢**%s %s** yêu cầu được cấp data khách hàng", $oneProfile['role_name'], $clsProfile->getFullName($profile_id, $oneProfile));
		$message .= "\n";
		$message .= "Số lượng: {color:#C00000}" . $amount . "{/color} khách hàng";
		$message .= "\n";
		$message .= sprintf("Dự án: %s", $project_name);
		if (!empty($notes)) {
			$message .= sprintf("Ghi chú: %s", $notes);
		}
		$message .= "\n";
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
		));
		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' => $message,
			'group_id' => _CRM_DATA_GROUP_ZALO_ID
		));
	}
	// return
	echo $msg;
	die();
}
function default_open_select(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$uid = Input::post('uid', $clsISO->getUniqid());
	$call_from = Input::post('call_from', "");
	$smarty->assign('uid', $uid);
	$smarty->assign('call_from', $call_from);
	// Return
	$html = $core->build('_ajax.open_select.tpl');
	echo json_encode(array(
		'uid' => sprintf('open_select_%s', $uid),
		'html' => $html
	));
	die();
}
function default_load_ms_customers(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	$html = '';
	$uid = $clsISO->getUniqid();
	$status_id = (int) Input::post('status_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$sql_query = "`is_trash`=0 AND `admin_id`='{$profile_id}'";
	if ($status_id > 0) $sql_query .= " AND `status_id`='{$status_id}'";
	if ($resource_id > 0) $sql_query .= " AND `resource_id`='{$resource_id}'";
	#- Begin pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 50);
	$total_record = $clsCustomer->countItem($sql_query);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$field = "{$clsCustomer->pkey},`name`,`phone`,`resource_id`,`status_id`,`upd_date`";
	$list_customers = $clsCustomer->getAll("{$sql_query} ORDER BY `upd_date` DESC" . $limitCond, $field);
	$meta_map = array();
	if (!empty($list_customers)) {
		$customer_ids = array();
		foreach ($list_customers as $row) {
			$cid = (int) $row[$clsCustomer->pkey];
			if ($cid > 0) {
				$customer_ids[$cid] = $cid;
			}
		}
		$meta_map = $clsCustomerMeta->getMapByCustomerIds(array_values($customer_ids), array('campaign'));
	}
	if (!empty($list_customers)) {
		$arr_property_cached = $arr_status_cached = $arr_campaign_cached = array();
		$arr_in = array('_CUSTOMER_RESOURCES', 'CUSTOMER_STATUS');
		$p_field = "{$clsProperty->pkey},`property_type`,`title`,`bgcolor`,`textcolor`";
		$tmp = $clsProperty->getAll("`property_type` IN ('" . implode('\',\'', $arr_in) . "')", $p_field);
		if (!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				if (in_array($val['property_type'], array('_CUSTOMER_RESOURCES'))) {
					$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
				} else {
					$arr_status_cached[$val[$clsProperty->pkey]] = $val;
				}
			}
			unset($tmp);
		}
		#
		$cond = "`is_trash`=0 AND `campaign_type`='_campaign' AND (`user_id`='{$profile_id}' OR `use_globe`=1)";
		$tmp = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", "{$clsCampaign->pkey},`title`");
		if (!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				$arr_campaign_cached[$val[$clsCampaign->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach ($list_customers as $key => $val) {
			$status_id = (int) $val['status_id'];
			$resource_id = (int) $val['resource_id'];
			$customer_id = (int) $val[$clsCustomer->pkey];
			$meta = isset($meta_map[$customer_id]) ? $meta_map[$customer_id] : array();
			$list_campaign_arrs = isset($meta['campaign']) ? $meta['campaign'] : array();
			$oneStatus = $status_id > 0 && isset($arr_status_cached[$status_id]) ? $arr_status_cached[$status_id] : array();
			$html .= '<div id="' . $val[$clsCustomer->pkey] . '" class="d-flex bg-lighter item_share_customer rounded-2 p-2 mb-1">
				<label class="d-flex align-items-center gap-2 ant-checkbox">
					<input type="radio" uid="' . $uid . '" onChange="$Core.global.crm.handle_select_customer(this, event)" name="customer_id" 
						value="' . $val[$clsCustomer->pkey] . '" full_name="' . $val['name'] . '" phone="' . $val['phone'] . '" class="js__customer_item">
					<div class="d-flex flex-column gap text-nowrap">
						<div class="d-flex align-items-center gap-1">
							<span class="badge rounded-pill" style="background:' . $oneStatus['bgcolor'] . ' !important; color:' . $oneStatus['textcolor'] . ' !important">' . $oneStatus['title'] . '</span> 
							<span class="fw-bold">' . $val['name'] . '</span>
						</div>
						<div class="d-flex align-items-center gap-2 text-fs-12">
							' . ($resource_id > 0 && isset($arr_property_cached[$resource_id])
				? '<span class="text-muted">' . $arr_property_cached[$resource_id] . '</span>' : '') . '
							' . (!empty($list_campaign_arrs) ? '<span class="text-muted">
								' . $clsCampaign->getTitleFromCached($list_campaign_arrs, $arr_campaign_cached) . '
							</span>' : '') . '
							<span class="d-flex align-items-center gap-1 text-muted">
								<i class="material-icons-outlined fs-13 no-translate">more_time</i> 
								' . $clsISO->getTimeAgo($val['upd_date']) . '
							</span>
						</div>
					</div>
				<label>
			</label></div>';
		}
	} else {
		$html = '<div class="d-flex empty py-3 flex-column align-items-center">
			<img src="' . URL_IMAGES . '/empty.svg" class="w-px-100" />
			<p class="text-muted">Không có khách hàng</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'per_page' => $per_page
	));
	die();
}
function default_data_distribution(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	#
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for ($i = 0; $i < 50; $i++) {
		$list_preloaders[] = $i;
	}
	#- Campaign
	$c_field = "{$clsCampaign->pkey},title";
	$cond = "`campaign_type`='_campaign' and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	$list_campaigns = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", $c_field);
	$smarty->assign('uid', $uid);
	$smarty->assign('list_campaigns', $list_campaigns);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$html = $core->build('_ajax.data_distribution.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_load_share_customers(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsCampaign = new Campaign();
	$useJoinCampaign = false;
	$joinSql = "";
	$html = '';
	$uid = $clsISO->getUniqid();
	$status_id = (int) Input::post('status_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	if ($campaign_id > 0) {
		$useJoinCampaign = true;
		$joinSql = " LEFT JOIN (
			SELECT `customer_id` AS `customer_ref`, `meta_id` AS `rel_campaign_id`
			FROM `{$clsCustomerMeta->tbl}`
			WHERE `meta_type`='campaign' AND `meta_id`='{$campaign_id}'
			GROUP BY `customer_id`, `meta_id`
		) AS `cc` ON `cc`.`customer_ref`=`customer_id`";
	}
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 50);
	$sql_query = "`is_trash`=0 AND `admin_id`='{$profile_id}'";
	if ($status_id > 0) $sql_query .= " AND `status_id`='{$status_id}'";
	if ($resource_id > 0) $sql_query .= " AND `resource_id`='{$resource_id}'";
	if ($campaign_id > 0) {
		$sql_query .= " AND `cc`.`rel_campaign_id`='{$campaign_id}'";
	}
	#- Begin pagination
	if ($useJoinCampaign) {
		$total_record = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT `customer_id`) 
			FROM `{$clsCustomer->tbl}` {$joinSql} WHERE {$sql_query}");
	} else {
		$total_record = $clsCustomer->countItem($sql_query);
	}
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	// $dbconn->debug = true;
	$field = "{$clsCustomer->pkey},`name`,`resource_id`,`status_id`,`upd_date`";
	if ($useJoinCampaign) {
		$list_customers = $dbconn->GetAll("SELECT DISTINCT `{$clsCustomer->tbl}`.{$field} 
			FROM `{$clsCustomer->tbl}` {$joinSql} 
			WHERE {$sql_query} ORDER BY `upd_date` DESC {$limitCond}");
	} else {
		$list_customers = $clsCustomer->getAll("{$sql_query} ORDER BY `upd_date` DESC" . $limitCond, $field);
	}
	$meta_map = array();
	if (!empty($list_customers)) {
		$customer_ids = array();
		foreach ($list_customers as $row) {
			$cid = (int) $row[$clsCustomer->pkey];
			if ($cid > 0) {
				$customer_ids[$cid] = $cid;
			}
		}
		$meta_map = $clsCustomerMeta->getMapByCustomerIds(array_values($customer_ids), array('campaign'));
	}
	if (!empty($list_customers)) {
		$arr_property_cached = $arr_status_cached = $arr_campaign_cached = array();
		$arr_in = array('_CUSTOMER_RESOURCES', 'CUSTOMER_STATUS');
		$p_field = "{$clsProperty->pkey},`property_type`,`title`,`bgcolor`,`textcolor`";
		$tmp = $clsProperty->getAll("`property_type` IN ('" . implode('\',\'', $arr_in) . "')", $p_field);
		if (!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				if (in_array($val['property_type'], array('_CUSTOMER_RESOURCES'))) {
					$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
				} else {
					$arr_status_cached[$val[$clsProperty->pkey]] = $val;
				}
			}
			unset($tmp);
		}
		#
		$cond = "`is_trash`=0 AND `campaign_type`='_campaign'";
		$cond .= " AND (`user_id`='{$profile_id}' OR `use_globe`=1)";
		$tmp = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", "{$clsCampaign->pkey},`title`");
		if (!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				$arr_campaign_cached[$val[$clsCampaign->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		foreach ($list_customers as $key => $val) {
			$status_id = (int) $val['status_id'];
			$resource_id = (int) $val['resource_id'];
			$customer_id = (int) $val[$clsCustomer->pkey];
			$meta = isset($meta_map[$customer_id]) ? $meta_map[$customer_id] : array();
			$list_campaign_arrs = isset($meta['campaign']) ? $meta['campaign'] : array();
			$oneStatus = $status_id > 0 && isset($arr_status_cached[$status_id])
				? $arr_status_cached[$status_id] : array();
			$html .= '<div id="' . $val[$clsCustomer->pkey] . '" class="d-flex bg-lighter item_share_customer rounded-2 p-2 mb-1">
				<label class="d-flex align-items-center gap-2 ant-checkbox">
					<input uid="' . $uid . '" onChange="$Core.crm.handle_cus_staff(this, event)" name="list_customers[]" 
						value="' . $val[$clsCustomer->pkey] . '" type="checkbox" class="js__customer_item">
					<div class="d-flex flex-column gap text-nowrap">
						<strong><label class="badge_status badge" style="background:' . $oneStatus['bgcolor'] . ' !important; color:' . $oneStatus['textcolor'] . ' !important">' . $oneStatus['title'] . '</label> ' . $val['name'] . '</strong>
						<div class="d-flex align-items-center gap-2 text-fs-12">
							' . ($resource_id > 0 && isset($arr_property_cached[$resource_id])
				? '<span class="text-muted">' . $arr_property_cached[$resource_id] . '</span>' : '') . '
							' . (!empty($list_campaign_arrs) ? '<span class="text-muted">
								' . $clsCampaign->getTitleFromCached($list_campaign_arrs, $arr_campaign_cached) . '
							</span>' : '') . '
							<!-- <span class="d-flex align-items-center gap-1 text-muted">
								<i class="material-icons-outlined fs-13 no-translate">more_time</i> 
								' . $clsISO->getTimeAgo($val['upd_date']) . '
							</span> -->
						</div>
					</div>
				<label>
			</label></div>';
		}
	} else {
		$html = '<div class="d-flex empty py-3 flex-column align-items-center">
			<img src="' . URL_IMAGES . '/empty.svg" class="w-px-100" />
			<p class="text-muted">Không có khách hàng</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'per_page' => $per_page
	));
	die();
}
function default_load_share_staffs(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$department_id = Input::post('department_id', 0);
	$keysearch =  Input::post('keysearch');
	if ($clsISO->_DEV()) {
		$sql_query = "`t1`.`is_trash`=0 AND `t1`.`status_id`='" . _STATUS_STAFF_ON_ID . "' ";
	} else {
		$sql_query = "`t1`.`is_trash`=0 AND `t1`.`status_id`='" . _STATUS_STAFF_ON_ID . "' 
		AND `t1`.`list_department_id` LIKE '%|" . _DEPARTMENT_SALE_ID . "|%'";
	}
	if ($department_id > 0) $sql_query .= " AND `t1`.`department_id`='{$department_id}'";
	if (!empty($keysearch)) $sql_query .= " AND `full_name_slug` LIKE '%" . $core->replaceSpace($keysearch) . "%'";
	$field = "`t1`.{$clsProfile->pkey},`t1`.`code`,`t1`.`full_name`,`t1`.`first_name`,`t1`.`last_name`";
	$field .= ",`t1`.`avatar`,`t2`.`title` AS `department_name`,`t3`.`title` as `role_name`";
	$list_staffs = $dbconn->getAll("SELECT {$field} FROM {$clsProfile->tbl} AS `t1` 
		INNER JOIN {$clsProperty->tbl} AS `t2` ON `t1`.`department_id`=`t2`.`property_id`
		INNER JOIN {$clsProperty->tbl} AS `t3` ON `t1`.`role_id`=`t3`.`property_id`
		WHERE {$sql_query}");
	$html = '';
	if (!empty($list_staffs)) {
		foreach ($list_staffs as $key => $val) {
			$html .= '<div class="d-flex bg-lighter rounded-2 p-2 mb-1">
				<label class="d-flex align-items-center gap-2 ant-checkbox">
					<input uid="' . $uid . '" name="list_staffs[]" onChange="$Core.crm.handle_selected_staff(this, event)" 
						value="' . $val[$clsProfile->pkey] . '" type="checkbox" class="js__staff_item">
					<div class="d-flex gap-2 align-items-center">
						<img class="avatar avatar-xs rounded-pill" onerror="this.src=\'' . URL_IMAGES . '/no-avatar.png\'" 
							src="' . $clsProfile->getAvatar($val[$clsProfile->pkey], $val, 30, 30) . '" />
						<div class="d-flex flex-column gap-0">
							<span>' . sprintf('%s %s', $val['code'], $clsProfile->getFullName($val[$clsProfile->pkey], $val)) . '</span>
							<div class="d-flex align-items-center text-fs-12 gap-2">
								<span class="text-muted">' . $val['department_name'] . '</span>
								<span class="text-muted">' . $val['role_name'] . '</span>
							</div>
						</div>
					</div>
				<label>
			</div>';
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	));
	die();
}
function default_do_confirm_receipt(){
	// Người NHẬN xác nhận đã nhận khách (đơn hoặc hàng loạt). IDOR-safe: confirm() chỉ động vào phiếu của chính $profile_id.
	global $profile_id;
	$clsCustomerAssign = new CustomerAssign();
	$recipient_id = (int) $profile_id;
	$ids = Input::post('list_ids');
	if (empty($ids)) {
		$single = (int) Input::post('customer_id', 0);
		$ids = $single > 0 ? array($single) : array();
	}
	$done = 0;
	if (is_array($ids)) {
		foreach ($ids as $cid) {
			$cid = (int) $cid;
			if ($cid > 0 && $clsCustomerAssign->confirm($cid, $recipient_id)) {
				$done++;
			}
		}
	}
	echo json_encode(array(
		'error' => $done > 0 ? 0 : 1,
		'confirmed' => $done,
		'message' => $done > 0 ? ('Đã xác nhận nhận ' . $done . ' khách.') : 'Không có khách nào cần xác nhận.'
	), JSON_UNESCAPED_UNICODE);
	die();
}
function default_do_share_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsCustomerSales = new CustomerSales();
	$isRootProfile = $clsCustomer->isRootProfile();
	$msg = "_error";
	$list_staffs = Input::post('list_staffs', []);
	$list_customers = Input::post('list_customers', []);
	$customer_insert = [];
	if (!empty($list_customers) && !empty($list_staffs)) {
		$msg = "_success";
		$total_staffs = count($list_staffs);
		$total_customers = count($list_customers);
		if ($total_staffs == 1) {
			$total_assigned = 0;
			$admin_id = $list_staffs[0];
			$p_field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
			$adminProfile = $clsProfile->getOne($admin_id, $p_field);
			foreach ($list_customers as $customer_id) {
				$field = "`admin_id`,`name`,`phone`,`begin_need`,`more_information`";
				$oCustomer = $clsCustomer->getOne($customer_id, $field);
				if (!empty($oCustomer)) {
					$more_information = $oCustomer['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$list_share_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'share');
					$action_logs = $core->get_field($more_information, "action_logs", []);
					$adminProfile_old = array();
					if ($profile_id == $oCustomer['admin_id']) { // Mình chuyển KH của mình
						$adminProfile_old = $oneProfile;
					} else { // Mình chuyển KH của người khác
						$adminProfile_old = $clsProfile->getOne($oCustomer['admin_id'], $p_field);
					}
					$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($oCustomer['admin_id'], $adminProfile_old), $clsProfile->getFullName($admin_id, $adminProfile));
					$action_logs[$clsISO->getUniqid()] = array(
						'content' => $content,
						'user_id' => $profile_id,
						'reg_date' => time()
					);
					$more_information['action_logs'] = $action_logs;
					if (!in_array($oCustomer['admin_id'], $list_share_arrs)) {
						$list_share_arrs[] = $oCustomer['admin_id'];
					}
					$update_field['use_globe'] = 1; // Mặc định share
					$update_field['admin_id'] = $admin_id;
					$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
					if ($clsCustomer->updateOne($customer_id, $update_field)) {
						$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $list_share_arrs, $profile_id);
						$total_assigned++;
						// Giao hẳn qua do_share_customer: tạo phiếu CHỜ XÁC NHẬN cho người nhận.
						$clsCustomerAssign = new CustomerAssign();
						$clsCustomerAssign->createPending($customer_id, $admin_id, $profile_id, 1);
						$clsCustomerSales->insert(array(
							'customer_id' => $customer_id,
							'admin_id' => $admin_id,
							'user_id' => $profile_id,
							'assign_date' => time(),
							'is_stop' => ($isRootProfile ? 0 : 1)
						));
						$customer_insert[] = [
							"customer_name"	=>	$oCustomer["name"],
							"phone"	=>	$oCustomer["phone"],
							"begin_need"	=>	$oCustomer["begin_need"],
						];
					}
				}
			}
			if ($total_assigned > 0) {
				$clsZalo = new Zalo();
				$clsZalo->sendNotifyCRMZalo($admin_id, $adminProfile, $total_assigned, $customer_insert);
			}
		} else {
			$extra = $total_customers % $total_staffs;
			$base = intdiv($total_customers, $total_staffs);
			$index = 0;
			$arr_staffs_customers = [];
			foreach ($list_staffs as $i => $staff_id) {
				$take = $base + ($i < $extra ? 1 : 0);
				$arr_staffs_customers[$staff_id] = array_slice($list_customers, $index, $take);
				$index += $take;
			}
			// $clsISO->print_pre($arr_staffs_customers); die();
			if (!empty($arr_staffs_customers)) {
				foreach ($arr_staffs_customers as $admin_id => $arr_customers) {
					if (!empty($arr_customers)) {
						$total_assigned = 0;
						$p_field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
						$adminProfile = $clsProfile->getOne($admin_id, $p_field);
						$customer_insert = [];
						foreach ($arr_customers as $customer_id) {
							$field = "`admin_id`,`more_information`";
							$oCustomer = $clsCustomer->getOne($customer_id, $field);
							if (!empty($oCustomer)) {
								$more_information = $oCustomer['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$list_share_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'share');
								$action_logs = $core->get_field($more_information, "action_logs", []);
								$adminProfile_old = array();
								if ($profile_id == $oCustomer['admin_id']) { // Mình chuyển KH của mình
									$adminProfile_old = $oneProfile;
								} else { // Mình chuyển KH của người khác
									$adminProfile_old = $clsProfile->getOne($oCustomer['admin_id'], $p_field);
								}
								$content = sprintf('<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsProfile->getFullName($oCustomer['admin_id'], $adminProfile_old), $clsProfile->getFullName($admin_id, $adminProfile));
								$action_logs[$clsISO->getUniqid()] = array(
									'content' => $content,
									'user_id' => $profile_id,
									'reg_date' => time()
								);
								$more_information['action_logs'] = $action_logs;
								if (!in_array($oCustomer['admin_id'], $list_share_arrs)) {
									$list_share_arrs[] = $oCustomer['admin_id'];
								}
								$update_field['use_globe'] = 1; // Mặc định share
								$update_field['admin_id'] = $admin_id;
								$update_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
								// $clsISO->print_pre($update_field); die();
								if ($clsCustomer->updateOne($customer_id, $update_field)) {
									$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $list_share_arrs, $profile_id);
									$total_assigned++;
									// Giao hẳn qua do_share_customer (nhánh root): tạo phiếu CHỜ XÁC NHẬN cho người nhận.
									$clsCustomerAssign = new CustomerAssign();
									$clsCustomerAssign->createPending($customer_id, $admin_id, $profile_id, 1);
									$clsCustomerSales->insert(array(
										'customer_id' => $customer_id,
										'admin_id' => $admin_id,
										'user_id' => $profile_id,
										'assign_date' => time(),
										'is_stop' => ($isRootProfile ? 0 : 1)
									));
									$customer_insert[] = [
										"customer_name"	=>	$oCustomer["name"],
										"phone"	=>	$oCustomer["phone"],
										"begin_need"	=>	$oCustomer["begin_need"],
									];
								}
							}
						}
						if ($total_assigned > 0) {
							$clsZalo = new Zalo();
							$clsZalo->sendNotifyCRMZalo($admin_id, $adminProfile, $total_assigned, $customer_insert);
						}
					}
				}
			}
		}
	}
	// Return
	echo $msg;
	die();
}
function default_unShare(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $_LANG_ID, $dbconn, $clsProfile, $deviceType, $smarty, $clsConfiguration;
	$clsStock 	 = new Stock();
	$clsCountry  = new Country();
	$clsCity 	 = new City();
	$clsArchived = new Archived();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsFollowUp = new FollowUp();
	$clsCampaign = new Campaign();
	$clsCustomerSales = new CustomerSales();
	$smarty->assign("clsCustomer", $clsCustomer);
	$smarty->assign("clsFollowUp", $clsFollowUp);
	/* Global cond */
	$now = time();
	$action = Input::post('action', "");
	$tab = Input::post('tab', 'owner');
	$view = Input::post("view", "table");
	$holderG = Input::post('holderG', '_tablist');
	$keysearch = Input::post('keysearch', "");
	$status_id = (int) Input::post('status_id');
	$sort_by = Input::post('sort_by', "last_contact");
	$priority_id = (int) Input::post('priority_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$typeHolder = Input::post('typeHolder', "_all");
	$_caller_full_permiss = in_array($profile_id, _PROFILE_CRM_SUPER_ID); // P0: capture caller permiss BEFORE override
	$staff_id = (int)Input::post('staff_id', 0);
	if ($staff_id > 0 && $_caller_full_permiss) {
		$profile_id = $staff_id;
	} // P0-2: only full-permiss may impersonate staff
	$is_all = (int) Input::post('is_all', 0);
	$reg_date = Input::post('reg_date', "");
	$reg_from = trim(Input::post('reg_from', "")); // lọc khoảng ngày tạo: từ ngày (YYYY-MM-DD)
	$reg_to = trim(Input::post('reg_to', "")); // lọc khoảng ngày tạo: tới ngày (YYYY-MM-DD)
	$group_id = (int) Input::post('group_id', 0, true);
	if ($group_id > 0 && !$_caller_full_permiss) {
		$group_id = 0;
	} // P0-3: only full-permiss may filter by arbitrary group
	$admin_id = (int) Input::post('admin_id', 0, true);
	// P0-5: chống IDOR — full-permiss (super hoặc full_permiss_crm) lọc admin_id tự do; quản lý nhóm theo nhóm; sale thường bỏ qua.
	if ($admin_id > 0 && !$_caller_full_permiss && !$clsISO->checkPermission('full_permiss_crm')) {
		$_admin_ok = false;
		if ($clsCustomer->isTeamManager()) {
			$_gpChk = new GroupProfile();
			$_gpRows = $_gpChk->getAll("`manager_profile_id`='{$profile_id}' AND `is_trash`=0", "`list_profile_id`");
			if (!empty($_gpRows)) {
				foreach ($_gpRows as $_gpR) {
					$_repArr = !empty($_gpR['list_profile_id']) ? $clsISO->getArrayByTextSlash($_gpR['list_profile_id']) : array();
					foreach ($_repArr as $_rid) {
						if ((int) $_rid === $admin_id) {
							$_admin_ok = true;
							break 2;
						}
					}
				}
			}
		}
		if (!$_admin_ok) {
			$admin_id = 0;
		}
	}
	$campaign_id = (int) Input::post('campaign_id', 0, true);
	$blocktype_id = (int) Input::post('blocktype_id', 0, true);
	$group_customer_sale = Input::post('group_customer_sale', "", true);
	if ($clsCustomer->isFullPermiss()) {
		$cond =  "`t2`.`is_trash`=0 AND (`t2`.`user_id`='{$profile_id}' OR `t2`.`user_id`='" . _PROFILE_ROOT_ID . "')";
	} else {
		$cond =  "`t2`.`is_trash`=0 AND `t2`.`user_id`='{$profile_id}'";
	}
	if (!empty($keysearch)) {
		$arr_ids = @explode(',', $keysearch);
		$slug = $core->replaceSpace($keysearch);
		if (!empty($arr_ids)) {
			$cond .= " and (`t2`.`name` like '%{$keysearch}%' 
				or `t2`.`name_slug` like '%{$slug}%' 
				or `t2`.`email` like '%{$keysearch}%' 
				or `t2`.`phone` like '%{$keysearch}%' 
				or `t2`.`address` like '%{$keysearch}%' 
				or `t2`.`{$clsCustomer->pkey}` in ('" . implode("','", $arr_ids) . "')
			)";
		} else {
			$cond .= " and (`t2`.`name` like '%{$keysearch}%' 
				or `t2`.`name_slug` like '%{$slug}%' 
				or `t2`.`email` like '%{$keysearch}%' 
				or `t2`.`phone` like '%{$keysearch}%' 
				or `t2`.`address` like '%{$keysearch}%'
			)";
		}
	}
	# Q4: bỏ filter priority_id — cột KHÔNG tồn tại trong DB
	# filter by resource_id
	if ($resource_id > 0) {
		$cond .= " and `t2`.`resource_id`='{$resource_id}'";
	}
	# filter by reg_date
	if (!empty($reg_date)) {
		$cond .= " and FROM_UNIXTIME(`t2`.`reg_date`,'%Y-%m-%d')='{$reg_date}'";
	}
	# filter by reg_date range (đồng bộ bộ lọc với list để thu hồi đúng tập đang xem)
	if ($reg_from !== "") {
		$_regFromTs = (int) strtotime($reg_from . " 00:00:00");
		if ($_regFromTs > 0) {
			$cond .= " and `t2`.`reg_date` >= {$_regFromTs}";
		}
	}
	if ($reg_to !== "") {
		$_regToTs = (int) strtotime($reg_to . " 23:59:59");
		if ($_regToTs > 0) {
			$cond .= " and `t2`.`reg_date` <= {$_regToTs}";
		}
	}
	# filter by campaign
	if ($campaign_id > 0) {
		$cond .= " and `t2`.`customer_id` IN (
			SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` 
			WHERE `meta_type`='campaign' AND `meta_id`='{$campaign_id}'
		)";
	}
	# filter by status_id
	if (!empty($status_id)) {
		$cond .= " and `t2`.`status_id`='{$status_id}'";
	} else {
		if ($is_all == 0) {
			$cond .= " AND `t2`.`status_id`<>'" . _CRM_STATUS_TRASH_ID . "'";
		}
	}
	$orderBy = ""; // Set Order Default
	if ($group_id > 0) {
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) ? $clsISO->getArrayByTextSlash($list_profile_id) : array();
		if (!empty($list_profile_arrs)) {
			$cond .= " and (`t2`.`admin_id` in (" . implode(',', $list_profile_arrs) . "))";
		}
	}
	if ($blocktype_id > 0) {
		$cond .= " and `t2`.`blocktype_id`='{$blocktype_id}'";
	}
	if ($is_all == 0) {
		$cond .= " and `t2`.`customer_id` not in (
			select `customer_id` from `{$clsArchived->tbl}` 
			where `profile_id`='{$profile_id}'
		)";
	}
	if ($group_customer_sale != "") {
		$group_customer = $clsConfiguration->getValue('group_customer_sale');
		$group_customer = !empty($group_customer) ? $clsISO->to_array_json($group_customer) : [];
		$arr_group_customer_sale = !empty($group_customer[$profile_id]) ? $group_customer[$profile_id] : [];
		if (!empty($arr_group_customer_sale[$group_customer_sale])) {
			$list_ids = $arr_group_customer_sale[$group_customer_sale]["list_ids"];
			$cond .= " and `t2`.`customer_id` IN (" . implode(',', $list_ids) . ")";
		}
	}
	$sql_string = $sql_cond = $cond;
	if ($admin_id > 0) {
		// Ẩn checkbox vì mình không phải là người quản lý.
		$cond .= " and (`t2`.`admin_id`<>'{$admin_id}' and `t2`.`customer_id` IN (
			SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` 
			WHERE `meta_type`='share' AND `meta_id`='{$admin_id}'
		))";
	}
	#
	$field = "`t1`.*,`t2`.`name`,`t2`.`more_information`";
	//	$dbconn->debug = true;
	$items = $dbconn->getAll("SELECT {$field} FROM {$clsCustomerSales->tbl} AS `t1` INNER JOIN {$clsCustomer->tbl} AS `t2` ON `t1`.`customer_id`=`t2`.`customer_id` WHERE t1.`is_stop`=0 AND {$cond}");
	//	$clsISO->print_pre($items);die;
	$_minutes_after = _CRM_TIME_AFTER_FOLLOWUP / 60;
	$idle_hours = (int) Input::post('idle_hours', 0); // unShare-cfg: >0 = nới auto-reclaim theo SỐ GIỜ không hoạt động; 0 = giữ nguyên hành vi cũ (15' + chưa follow-up nào)
	$total = 0;
	if (!empty($items)) {
		foreach ($items as $key => $val) {
			$admin_id = (int) $val['admin_id'];
			$customer_id = (int) $val['customer_id'];
			$assign_date = (int) $val['assign_date'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_share_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'share');
			$list_logs = isset($more_information['logs']) ? $more_information['logs'] : array();
			// $dbconn->debug = true;
			$list_followups = $clsFollowUp->getAll("`admin_id`='{$admin_id}' AND `customer_id`='{$customer_id}' ORDER BY `reg_date` DESC");
			// unShare-cfg: idle_hours>0 → reclaim theo "không hoạt động trong idle_hours" (gồm cả khi có follow-up CŨ); mặc định (0) giữ nguyên 15' + chưa-follow-up — không đổi hành vi LIVE.
			if ($idle_hours > 0) {
				$_last_fu = !empty($list_followups) ? (int) $list_followups[0]['reg_date'] : 0;
				$_last_touch = max($assign_date, $_last_fu);
				$_do_revert = (($_last_touch + ($idle_hours * 3600)) < $now);
			} else {
				$_do_revert = (strtotime("+{$_minutes_after} minutes", $assign_date) < time() && empty($list_followups));
			}
			if ($_do_revert) {
				$list_logs[$clsISO->getUniqid()] = array(
					'_type' => 'assign',
					'from_id' => $admin_id,
					'to_id' => $val["user_id"],
					'status_id' => 0,
					'reg_date' => time()
				);
				$more_information['logs'] = $list_logs;
				if ($clsCustomer->updateOne($customer_id, array(
					'admin_id' => $val["user_id"],
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				))) {
					/** Xoá bỏ quyền CS */
					$clsCustomerSales->updateOne($val[$clsCustomerSales->pkey], array(
						'is_stop' => 1
					));
					++$total;
				}
			}
		}
		unset($items);
	}
	// Output
	echo json_encode([
		"total"	=>	$total,
		"msg"	=>	"Đã thu hồi {$total} khách hàng không được chăm sóc"
	], JSON_UNESCAPED_UNICODE);
	die();
}
function default_configColumn(){
	global $core, $profile_id, $oneProfile, $clsISO, $smarty;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	###
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', 'upload');
	$totalInsert = $totalDuplicate = 0;
	if ($tp == 'google_sheet') {
		$spreadsheetId = Input::post('spreadsheetId');
		if (!empty($spreadsheetId)) {
			if ($clsISO->checkContainer($spreadsheetId, "docs.google.com", "")) {
				@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
				$spreadsheetId = $matches[0];
			}
			#- Require library		
			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
			/** Init Client */
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
			// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
			// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
			// get all the rows of a sheet
			$range = 'Data'; // here we use the name of the Sheet to get all the rows
			$response = $service->spreadsheets_values->get($spreadsheetId, $range);
			$tblData = $response->getValues();
			// $clsISO->print_pre($tblData); die();
			$select_default = ["name", "phone", "email", "address", "status_id", "begin_need"];
			$cachedColumnName = sprintf('column_%s.json', $profile_id);
			$cachedFile = DIR_CACHE_JSON . '/customer/' . $cachedColumnName;
			if (file_exists($cachedFile)) {
				$select_default = $decoder->decodeFile($cachedFile);
			}
			$data_select = $clsCustomer->getDataColumnCustomer();
			$highestColumnIndex = 15;
			$widthColumn = 100 / $highestColumnIndex;
			$smarty->assign("data_select", $data_select);
			$smarty->assign("select_default", $select_default);
			$smarty->assign("widthColumn", $widthColumn);
			$smarty->assign("highestColumnIndex", $highestColumnIndex);
			$smarty->assign("tblData", $tblData);
			// Return
			$html = $core->build("_ajax.configColumn.tpl");
			echo json_encode(array(
				'uid' => $uid,
				'result' =>	true,
				'html' => $html
			));
			die();
		}
	} else {
		if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
			if (@is_uploaded_file($_FILES['fileimport']['tmp_name'])) {
				$target_dir = PCMS_DIR . "/tmp/";
				$file_ext = explode('.', basename($_FILES["fileimport"]["name"]));
				$file_ext = strtolower(end($file_ext));
				$target_file = $target_dir . time() . '.' . $file_ext;
				if (move_uploaded_file($_FILES["fileimport"]["tmp_name"], $target_file)) {
					$html = '';
					$inputFileName = $target_file;
					require_once DIR_INCLUDES . "/phpexcel/Classes/PHPExcel.php";
					require_once DIR_INCLUDES . "/phpexcel/Classes/PHPExcel/IOFactory.php";
					$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
					try {
						$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFileName);
					} catch (Exception $e) {
						die($e->getMessage());
					}
					$worksheet = $objPHPExcel->getActiveSheet();
					$worksheetTitle     = $worksheet->getTitle();
					$highestRow         = $worksheet->getHighestRow();
					$highestColumn      = $worksheet->getHighestColumn();
					$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
					##
					$index = 0;
					$tblData = array();
					for ($row = 1; $row <= $highestRow; ++$row) {
						for ($col = 0; $col < $highestColumnIndex; ++$col) {
							$cell = $worksheet->getCellByColumnAndRow($col, $row);
							$tblData[$index][] = $cell->getValue();
						}
						++$index;
					}
					// Remove file uploaded
					@unlink($inputFileName);
					$select_default = ["name", "phone", "email", "address", "status_id", "begin_need"];
					$cachedColumnName = sprintf('column_%s.json', $profile_id);
					$cachedFile = DIR_CACHE_JSON . '/customer/' . $cachedColumnName;
					if (file_exists($cachedFile)) {
						$select_default = $decoder->decodeFile($cachedFile);
					}
					$highestColumnIndex = 15;
					$widthColumn = 100 / $highestColumnIndex;
					$data_select = $clsCustomer->getDataColumnCustomer();
					if (!empty($tblData)) {
						$cachedName = sprintf('%s.json', $uid);
						$cachedFile = DIR_CACHE_JSON . '/customer/' . $cachedName;
						$encoder->encodeFile($tblData, $cachedFile);
					}
					$smarty->assign("uid", $uid);
					$smarty->assign("data_select", $data_select);
					$smarty->assign("select_default", $select_default);
					$smarty->assign("widthColumn", $widthColumn);
					$smarty->assign("highestColumnIndex", $highestColumnIndex);
					$smarty->assign("tblData", $tblData);
					// Return
					$html = $core->build("_ajax.configColumn.tpl");
					echo json_encode(array(
						'uid' => $uid,
						'result' =>	true,
						'html' => $html
					));
					die();
				}
			}
		}
	}
	// Return
	$res = array(
		"result"	=>	false,
		'msg' => "Vui lòng upload file excel",
	);
	echo json_encode($res);
	die();
}
function default_continue_config(){
	global $core, $profile_id, $oneProfile, $clsISO, $smarty;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	require_once(DIR_INCLUDES . '/json_master/autoload.php');
	###
	$uid = Input::post("uid");
	$columns = Input::post("columns", array());
	// $clsISO->print_pre($columns); die();
	if (!empty($columns)) {
		$error_field = 0;
		$arr_fields = array();
		foreach ($columns as $key => $p_field) {
			if (!empty($p_field)) {
				if (!in_array($p_field, $arr_fields)) {
					$arr_fields[$key] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
		if (!in_array("name", $arr_fields)) {
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột họ tên khách hàng chưa được xác định"
			]);
			die();
		}
		if (!in_array("phone", $arr_fields)) {
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột số điện thoại khách hàng chưa được xác định"
			]);
			die();
		}
		if ($error_field > 0) {
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Các cột dữ liệu không được trùng nhau"
			]);
			die();
		}
		$cachedColumnName = sprintf('column_%s.json', $profile_id);
		$cachedFile = DIR_CACHE_JSON . '/customer/' . $cachedColumnName;
		$encoder = new Webmozart\Json\JsonEncoder();
		// $clsISO->print_pre($arr_fields); die();
		$encoder->encodeFile($arr_fields, $cachedFile);
		$res = array(
			"result"	=>	true,
			'msg' => "Cài đặt thành công",
		);
	} else {
		$res = array(
			"result"	=>	false,
			'msg' => "Có lỗi xảy ra. Xin vui lòng thử lại!",
		);
	}
	// Return	
	echo json_encode($res);
	die();
}