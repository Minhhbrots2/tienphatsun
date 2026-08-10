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
/* ===== Task Setting V1 — 3 tác nghiệp (Gọi điện/Nhắn tin/Gặp trực tiếp) + 2 terminal ===== */
// Gate cấu hình tác nghiệp = full-permiss (CRM super). Trả TRUE nếu được phép.
function crm_task_setting_can_manage(){
	$clsCustomer = new Customer();
	return $clsCustomer->isFullPermiss();
}
function default_open_task_setting(){
	global $core, $clsISO, $smarty;
	if(!crm_task_setting_can_manage()){
		$uid = $clsISO->getUniqid();
		echo json_encode(array('uid' => $uid, 'html' => '<div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content"><div class="modal-body p-4 text-center text-danger">Bạn không có quyền cấu hình tác nghiệp.</div></div></div>')); die();
	}
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$tasks = crm_task_v1_tasks();
	// Map result theo từng tác nghiệp đầu (cho combobox client-side); rỗng tới khi seed Phase 2.
	$results_by_task = array();
	foreach($tasks['action'] as $tid => $_t){
		$results_by_task[$tid] = array();
		$rows = $clsSetting->getAll("is_trash=0 and _type='_CRM_RESULT' and parent_id='".(int)$tid."' order by order_no asc", "setting_id,title,more_information");
		if(!empty($rows)){
			foreach($rows as $r){
				$f = crm_task_setting_result_flags((int)$r['setting_id'], $r);
				$results_by_task[$tid][] = array(
					'id' => (int)$r['setting_id'],
					'title' => $r['title'],
					'is_counter' => $f['is_counter'],
					'need_datetime' => $f['need_datetime'],
					'customer_status_id' => $f['customer_status_id'],
				);
			}
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', 'Thiết lập tác nghiệp');
	$smarty->assign('list_action_task', $tasks['action']);   // 3 tác nghiệp đầu
	$smarty->assign('list_next_task', $tasks['all']);         // 3 + 2 terminal (dropdown "tác nghiệp kế")
	$smarty->assign('status_options', crm_task_v1_status_options());
	$smarty->assign('delay_options', crm_task_setting_delay_options());
	$smarty->assign('groups_html', crm_task_setting_render_groups());
	$smarty->assign('results_by_task_json', json_encode($results_by_task, JSON_UNESCAPED_UNICODE));
	// Return
	$html = $core->build('_ajax.crm_task_setting.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
/* ===== Helpers: catalog + render ===== */
// Nhận diện 3 action + 2 terminal QUA CONSTANTS (chắc chắn, không dựa more_information.task_type).
function crm_task_v1_tasks(){
	$clsSetting = new Setting();
	$type_map = array(
		(int)_CRM_TASK_CALL_ID => 'call',
		(int)_CRM_TASK_MSG_ID  => 'message',
		(int)_CRM_TASK_MEET_ID => 'meeting',
		(int)_CRM_TASK_END_ID  => 'end',
		(int)_CRM_TASK_DEAL_ID => 'deal',
	);
	$action_ids = array((int)_CRM_TASK_CALL_ID, (int)_CRM_TASK_MSG_ID, (int)_CRM_TASK_MEET_ID);
	$terminal_ids = array((int)_CRM_TASK_END_ID, (int)_CRM_TASK_DEAL_ID);
	$ids = array_merge($action_ids, $terminal_ids);
	$title = array();
	$rows = $clsSetting->getAll("setting_id IN (".implode(',', $ids).")", "setting_id,title");
	if(!empty($rows)){ foreach($rows as $r){ $title[(int)$r['setting_id']] = $r['title']; } }
	$mk = function($id) use ($title, $type_map){
		$id = (int)$id;
		return array('id'=>$id, 'title'=>(isset($title[$id]) ? $title[$id] : ('#'.$id)), 'task_type'=>(isset($type_map[$id]) ? $type_map[$id] : ''));
	};
	$action = array(); foreach($action_ids as $i){ $action[(int)$i] = $mk($i); }
	$terminal = array(); foreach($terminal_ids as $i){ $terminal[(int)$i] = $mk($i); }
	$all = $action + $terminal;
	// Return
	return array(
		'all'=>$all,
		'action'=>$action,
		'terminal'=>$terminal,
		'type_map'=>$type_map,
		'action_ids'=>$action_ids,
		'terminal_ids'=>$terminal_ids
	);
}
// Dropdown trạng thái KH (map theo Excel V1 → status CA). 0 = không đổi.
function crm_task_v1_status_options($selected=0){
	$list = array(
		(int)_CRM_STATUS_LEAD_ID => 'Chưa tư vấn (Lead mới)',
		(int)_CRM_STATUS_HEN_ID  => 'Đang chăm sóc',
		(int)_CRM_STATUS_NET_ID  => 'Quan tâm (Hẹn gặp)',
		(int)_CRM_STATUS_CHOT_ID => 'Chốt',
		(int)_CRM_STATUS_TRASH_ID => 'Không thành công',
		(int)_CRM_STATUS_LONG_TERM_LEAD_ID => 'Lead dài hạn (Chăm sóc dài)',
	);
	$html = '<option value="0">— Không đổi —</option>';
	foreach($list as $id=>$label){
		$html .= '<option value="'.$id.'"'.((int)$selected===(int)$id?' selected':'').'>'.htmlspecialchars($label, ENT_QUOTES).'</option>';
	}
	return $html;
}
function crm_task_setting_delay_options($selected_value=0, $selected_unit='minute'){
	$options = array(
        array('minute', 1, '1 phút'),
		array('minute', 5, '5 phút'),
		array('minute', 10, '10 phút'),
		array('minute', 15, '15 phút'),
		array('minute', 25, '25 phút'),
        array('minute', 30, '30 phút'),
		array('hour', 1, '1 giờ'),
		array('hour', 2, '2 giờ'),
		array('hour', 4, '4 giờ'),
        array('hour', 12, '12 giờ'),
        array('hour', 18, '18 giờ'),
        array('hour', 20, '20 giờ'),
		array('day', 1, '1 ngày'),
		array('day', 2, '2 ngày'),
		array('day', 3, '3 ngày'),
		array('day', 5, '5 ngày'),
		array('day', 7, '7 ngày'),
		array('day', 9, '9 ngày'),
		array('day', 12, '12 ngày'),
		array('day', 15, '15 ngày'),
		array('day', 20, '20 ngày'),
		array('day', 30, '30 ngày')
	);
	$html = '<option value="0|minute">Ngay (0)</option>';
	foreach($options as $opt){
		$unit = $opt[0];
		$val = (int) $opt[1];
		$label = $opt[2];
		$selected = ($val===$selected_value && $unit===$selected_unit) ? ' selected' : '';
		$html .= '<option value="'.$val.'|'.$unit.'"'.$selected.'>'.$label.'</option>';
	}
	return $html;
}
function crm_task_setting_delay_label($delay_value, $delay_unit){
	$delay_value = (int) $delay_value;
	if($delay_value <= 0){ return 'Ngay'; }
	$delay_unit = crm_task_setting_validate_unit($delay_unit);
	$units = array('minute'=>'phút', 'hour'=>'giờ', 'day'=>'ngày');
	$label = isset($units[$delay_unit]) ? $units[$delay_unit] : $delay_unit;
	return $delay_value.' '.$label;
}
// Đọc cờ hành vi từ more_information của 1 result: is_counter / need_datetime / customer_status_id / is_active.
function crm_task_setting_result_flags($result_id, $oResult = array()){
	global $clsISO;
	$flags = array('is_counter'=>0, 'need_datetime'=>0, 'customer_status_id'=>0, 'is_active'=>1);
	$result_id = (int)$result_id;
	if($result_id <= 0){ return $flags; }
	if(empty($oResult) || !isset($oResult['more_information'])){
		$clsSetting = new Setting();
		$oResult = $clsSetting->getOne($result_id, "more_information");
	}
	if(!empty($oResult) && isset($oResult['more_information'])){
		$mi = $clsISO->to_array_json($oResult['more_information']);
		if(is_array($mi)){
			if(isset($mi['is_counter'])) $flags['is_counter'] = (int)$mi['is_counter'];
			if(isset($mi['need_datetime'])) $flags['need_datetime'] = (int)$mi['need_datetime'];
			if(isset($mi['customer_status_id'])) $flags['customer_status_id'] = (int)$mi['customer_status_id'];
			if(isset($mi['is_active'])) $flags['is_active'] = (int)$mi['is_active'];
		}
	}
	return $flags;
}
// Chip màu/icon cho 1 tác nghiệp kế tiếp.
function crm_task_setting_next_chip($next_id, $tasks){
	$next_id = (int)$next_id;
	$t = isset($tasks['all'][$next_id]) ? $tasks['all'][$next_id] : null;
	$type = $t ? $t['task_type'] : '';
	$label = $t ? $t['title'] : '(đã xoá)';
	$cfg = array(
		'call'    => array('cls'=>'ct-call', 'icon'=>'bx-phone'),
		'message' => array('cls'=>'ct-msg',  'icon'=>'bx-message-rounded'),
		'meeting' => array('cls'=>'ct-meet', 'icon'=>'bx-group'),
		'end'     => array('cls'=>'ct-end',  'icon'=>'bx-flag'),
		'deal'    => array('cls'=>'ct-deal', 'icon'=>'bx-dollar-circle'),
	);
	$c = isset($cfg[$type]) ? $cfg[$type] : array('cls'=>'ct-na','icon'=>'bx-minus');
	return '<span class="crm-ts-chip '.$c['cls'].'"><i class="bx '.$c['icon'].'"></i> '.htmlspecialchars($label, ENT_QUOTES).'</span>';
}
// Render bảng luật gom theo 3 tác nghiệp đầu (View A). Hiện cả luật active + tắt (is_trash=1, mờ + badge "tắt").
function crm_task_setting_render_groups(){
	global $dbconn;
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$tasks = crm_task_v1_tasks();
	$action_ids = $tasks['action_ids'];
	if(empty($action_ids)){ return ''; }
	// Đọc luật active + tắt của 3 tác nghiệp đầu (~36 dòng, không cần phân trang). Active trước, rồi tắt.
	$rows = $clsCustomerTaskSetting->getAll("task_id IN (".implode(',', $action_ids).") and is_trash IN (0,1) order by task_id asc, is_trash asc, order_no asc, customer_task_setting_id asc");
	$ids = $byTask = array(); 
	foreach($action_ids as $aid){ 
		$byTask[$aid] = array(); 
	}
	if(!empty($rows)){
		foreach($rows as $r){
			$tid = (int)$r['task_id'];
			if(!isset($byTask[$tid])){ continue; }
			$byTask[$tid][] = $r;
			$ids[] = (int)$r['result_id'];
		}
	}
	$map_title = crm_task_setting_titles_by_ids($ids); // result titles
	// batch-load more_information các result (tránh N+1 query trong vòng lặp)
	$res_mi = array();
	if(!empty($ids)){
		$uids = array_values(array_unique(array_map('intval', $ids)));
		$mrows = (new Setting())->getAll("setting_id IN (".implode(',', $uids).")", "setting_id,more_information");
		if(!empty($mrows)){ foreach($mrows as $mr){ $res_mi[(int)$mr['setting_id']] = $mr; } }
	}
	$icon_map = array('call'=>'bx-phone-call', 'message'=>'bx-message-rounded-dots', 'meeting'=>'bx-group');
	$html = '';
	foreach($tasks['action'] as $tid => $_t){
		$type = $_t['task_type'];
		$gicon = isset($icon_map[$type]) ? $icon_map[$type] : 'bx-task';
		$grules = $byTask[$tid];
		$cnt = 0; foreach($grules as $_gr){ if((int)$_gr['is_trash'] === 0){ $cnt++; } }
		$html .= '<tr class="crm-ts-grp ct-'.($type==='call'?'call':($type==='message'?'msg':'meet')).'">
			<td colspan="4">
				<span class="crm-ts-gi"><i class="bx '.$gicon.'"></i> '.htmlspecialchars($_t['title'], ENT_QUOTES).' <span class="crm-ts-cnt">'.$cnt.' luật</span></span>
			</td>
		</tr>';
		if(!empty($grules)){
			foreach($grules as $r){
				$rid = (int)$r['customer_task_setting_id'];
				$result_id = (int)$r['result_id'];
				$next_id = (int)$r['next_task_id'];
				$is_off = ((int)$r['is_trash'] === 1) ? 1 : 0;
				$result_title = $result_id>0 ? (isset($map_title[$result_id]) ? $map_title[$result_id] : '(đã xoá)') : '—';
				$flags = crm_task_setting_result_flags($result_id, isset($res_mi[$result_id]) ? $res_mi[$result_id] : array());
				$delay_unit = !empty($r['delay_unit']) ? $r['delay_unit'] : 'minute';
				$delay_value = (int)$r['delay_value'];
				$delay_combo = $delay_value.'|'.$delay_unit;
				// badge sau kết quả
				$result_badges = '';
				if($flags['is_counter']){ $result_badges .= ' <span class="crm-ts-bdg b-info">đếm lần</span>'; }
				if($is_off){ $result_badges .= ' <span class="crm-ts-bdg b-danger">tắt</span>'; }
				// cột "sau bao lâu"
				if($flags['need_datetime']){
					$when_cell = '<span class="crm-ts-bdg b-warn">cần lịch hẹn</span>';
				} else {
					$when_cell = htmlspecialchars(crm_task_setting_delay_label($delay_value, $delay_unit), ENT_QUOTES);
				}
				$next_chip = crm_task_setting_next_chip($next_id, $tasks);
				$data_attr = 'data-id="'.$rid.'" data-task="'.$tid.'" data-result="'.$result_id.'" data-result-title="'.htmlspecialchars($result_title===''?'':$result_title, ENT_QUOTES).'" data-next="'.$next_id.'" data-delay="'.$delay_combo.'" data-counter="'.$flags['is_counter'].'" data-need-datetime="'.$flags['need_datetime'].'" data-status="'.$flags['customer_status_id'].'" data-active="'.($is_off?0:1).'"';
				$html .= '<tr data-id="'.$rid.'" class="'.($is_off?'crm-ts-off':'').'">'.
					'<td>'.htmlspecialchars($result_title, ENT_QUOTES).$result_badges.'</td>'.
					'<td>'.$next_chip.'</td>'.
					'<td>'.$when_cell.'</td>'.
					'<td class="crm-ts-delcol">'.
						'<i class="bx bx-edit crm-ts-act" title="Sửa" '.$data_attr.' onclick="$Core.crm.crm_task_edit(this, event)"></i>'.
						'<i class="bx bx-x crm-ts-act" title="Xoá" data-id="'.$rid.'" onclick="$Core.crm.crm_task_delete(this, event)"></i>'.
					'</td>'.
				'</tr>';
			}
		}
		$html .= '<tr class="crm-ts-addr"><td colspan="4"><span class="crm-ts-addbtn" data-task="'.$tid.'" onclick="$Core.crm.crm_task_open_form(this, event)"><i class="bx bx-plus"></i> Thêm luật cho '.htmlspecialchars($_t['title'], ENT_QUOTES).'</span></td></tr>';
	}
	return $html;
}
// Đọc title tác nghiệp/kết quả trực tiếp từ default_setting theo id (bỏ phụ thuộc cache stale)
function crm_task_setting_titles_by_ids($ids){
	$clean = array();
	if(!empty($ids)){
		foreach($ids as $id){
			$id = (int) $id;
			if($id > 0){ $clean[$id] = $id; }
		}
	}
	if(empty($clean)){ return array(); }
	$clsSetting = new Setting();
	$list = $clsSetting->getAll("setting_id IN (".implode(',', $clean).")", "setting_id,title");
	$map = array();
	if(!empty($list)){
		foreach($list as $item){
			$map[(int)$item['setting_id']] = $item['title'];
		}
	}
	return $map;
}
function crm_task_setting_validate_unit($unit){
	return in_array($unit, array('minute','hour','day')) ? $unit : 'minute';
}
function crm_task_setting_parse_delay($delay_combo, $delay_value=0, $delay_unit='minute'){
	if(!empty($delay_combo) && strpos($delay_combo, '|') !== false){
		$tmp = explode('|', $delay_combo);
		$delay_value = (int) $tmp[0];
		$delay_unit = !empty($tmp[1]) ? $tmp[1] : $delay_unit;
	}
	$delay_unit = crm_task_setting_validate_unit($delay_unit);
	return array($delay_value, $delay_unit);
}
// Tạo / cập nhật 1 _CRM_RESULT (parent_id=task) kèm 4 cờ trong more_information. Trả về result_id.
function crm_task_setting_upsert_result($task_id, $result_id, $result_title, $flags){
	global $dbconn, $profile_id;
	$clsSetting = new Setting();
	$tbl = $clsSetting->tbl;
	$task_id = (int)$task_id;
	$mi = json_encode(array(
		'is_counter' => (int)$flags['is_counter'],
		'need_datetime' => (int)$flags['need_datetime'],
		'customer_status_id' => (int)$flags['customer_status_id'],
		'is_active' => 1,
	), JSON_UNESCAPED_UNICODE);
	// Đã có result_id (chọn sẵn) → MERGE cờ vào more_information rồi trả id.
	if((int)$result_id > 0){
		$cur = $clsSetting->getOne((int)$result_id, "more_information");
		$base = array();
		if(!empty($cur) && isset($cur['more_information'])){ $dec = json_decode((string)$cur['more_information'], true); if(is_array($dec)){ $base = $dec; } }
		$base['is_counter'] = (int)$flags['is_counter'];
		$base['need_datetime'] = (int)$flags['need_datetime'];
		$base['customer_status_id'] = (int)$flags['customer_status_id'];
		if(!isset($base['is_active'])){ $base['is_active'] = 1; }
		$dbconn->Execute("UPDATE `{$tbl}` SET `more_information`=".$dbconn->qstr(json_encode($base, JSON_UNESCAPED_UNICODE))." WHERE `setting_id`=".(int)$result_id);
		return (int)$result_id;
	}
	// Tạo mới theo title — idempotent theo title+parent.
	$title = trim((string)$result_title);
	if($title === ''){ return 0; }
	$exist = (int)$dbconn->GetOne("SELECT `setting_id` FROM `{$tbl}` WHERE `_type`='_CRM_RESULT' AND `parent_id`=".$task_id." AND `title`=".$dbconn->qstr($title)." AND `is_trash`=0 LIMIT 1");
	if($exist > 0){
		$dbconn->Execute("UPDATE `{$tbl}` SET `more_information`=".$dbconn->qstr($mi).", `is_trash`=0 WHERE `setting_id`=".$exist);
		return $exist;
	}
	// Clone 1 dòng _CRM_RESULT mẫu để đủ cột NOT NULL; override field quản lý.
	$tpl = $dbconn->GetRow("SELECT * FROM `{$tbl}` WHERE `_type`='_CRM_RESULT' ORDER BY `setting_id` ASC LIMIT 1");
	$now = time();
	$override = array(
		'_type' => '_CRM_RESULT',
		'parent_id' => $task_id, 
		'title' => $title,
		'more_information' => $mi, 
		'is_trash' => 0, 
		'for_id' => 0,
	);
	if(is_array($tpl)){
		if(array_key_exists('order_no', $tpl)){ $override['order_no'] = (int)$tpl['order_no']; }
		if(array_key_exists('reg_date', $tpl)){ $override['reg_date'] = $now; }
		if(array_key_exists('upd_date', $tpl)){ $override['upd_date'] = $now; }
		if(array_key_exists('slug', $tpl)){ $override['slug'] = 'crm-result-'.$now; }
		if(array_key_exists('user_id', $tpl)){ $override['user_id'] = (int)$profile_id; }
	}
	$cols = array(); $vals = array();
	if(is_array($tpl)){
		foreach($tpl as $k => $v){
			if($k === 'setting_id'){ continue; }
			$cols[] = "`{$k}`";
			$vals[] = array_key_exists($k, $override) ? $dbconn->qstr($override[$k]) : $dbconn->qstr($v);
			unset($override[$k]);
		}
	}
	foreach($override as $k => $v){ $cols[] = "`{$k}`"; $vals[] = $dbconn->qstr($v); }
	$dbconn->Execute("INSERT INTO `{$tbl}` (".implode(',', $cols).") VALUES (".implode(',', $vals).")");
	$new_id = (int)$dbconn->Insert_ID();
	// clear cache result
	try{ 
		$clsCache = new Cache(); 
		$clsCache->delete('setting__CRM_RESULT_cached'); 
	} catch(Exception $e){}
	return $new_id;
}
/* ===== Task Setting: List (grouped) ===== */
function default_load_crm_task_setting(){
	global $core;
	if(!crm_task_setting_can_manage()){ 
		echo json_encode(array(
			'error'=>1,
			'message'=>'Bạn không có quyền cấu hình tác nghiệp.'
		), JSON_UNESCAPED_UNICODE); die(); 
	}
	echo json_encode(array(
		'error' => 0,
		'html' => crm_task_setting_render_groups()
	)); die();
}
/* ===== Task Setting: Save (thêm + sửa 1 luật) ===== */
function default_save_crm_task_setting(){
	global $core, $clsISO, $profile_id, $dbconn;
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	if(!crm_task_setting_can_manage()){ 
		echo json_encode(array(
			'error'=>1,
			'message'=>'Bạn không có quyền cấu hình tác nghiệp.'
		), JSON_UNESCAPED_UNICODE); die();
	 }
	$id = (int) Input::post('id', 0);
	$task_id = (int) Input::post('task_id', 0);
	$result_id = (int) Input::post('result_id', 0);
	$result_title = trim((string) Input::post('result_title', ''));
	$next_task_id = (int) Input::post('next_task_id', 0);
	$need_datetime = (int) Input::post('need_datetime', 0) > 0 ? 1 : 0;
	$is_counter = (int) Input::post('is_counter', 0) > 0 ? 1 : 0;
	$customer_status_id = (int) Input::post('customer_status_id', 0);
	$is_active = (int) Input::post('is_active', 1) > 0 ? 1 : 0;
	if($task_id<=0 || ($result_id<=0 && $result_title==='') || $next_task_id<=0){
		echo json_encode(array(
			'error' => 1,
			'message' => 'Vui lòng chọn tác nghiệp đầu, kết quả và tác nghiệp tiếp.'
		)); die();
	}
	// need_datetime → delay bỏ qua (0); else lấy từ delay_combo.
	if($need_datetime){
		$delay_value = 0; $delay_unit = 'minute';
	} else {
		list($delay_value, $delay_unit) = crm_task_setting_parse_delay(Input::post('delay_combo', ''), 0, 'minute');
	}
	// Tạo/cập nhật result + ghi 4 cờ vào more_information.
	$flags = array(
		'is_counter'=>$is_counter, 
		'need_datetime'=>$need_datetime, 
		'customer_status_id'=>$customer_status_id, 
		'is_active'=>1
	);
	$result_id = crm_task_setting_upsert_result($task_id, $result_id, $result_title, $flags);
	if($result_id <= 0){
		echo json_encode(array(
			'error'=>1,
			'message'=>'Không tạo được kết quả.'
		)); die();
	}
	// Chống rule trùng (task,result) active — loại trừ chính nó khi sửa.
	$dupCond = "`is_trash`=0 and `task_id`='{$task_id}' and `result_id`='{$result_id}'";
	if($id > 0){ $dupCond .= " and `customer_task_setting_id`!='{$id}'"; }
	$_dupRule = $clsCustomerTaskSetting->getByCond($dupCond, "customer_task_setting_id");
	if(!empty($_dupRule)){
		echo json_encode(array(
			'error'=>1,
			'message'=>'Đã có luật cho cặp Tác nghiệp + Kết quả này.'
		)); die();
	}
	$now = time();
	$rule_trash = $is_active ? 0 : 1;
	if($id <= 0){
		// Thêm mới
		$order_no = $clsCustomerTaskSetting->getMaxOrderNo();
		$clsCustomerTaskSetting->insert(array(
			'task_id' => $task_id,
			'result_id' => $result_id,
			'next_task_id' => $next_task_id,
			'delay_value' => $delay_value,
			'delay_unit' => $delay_unit,
			'order_no' => $order_no,
			'reg_date' => $now,
			'upd_date' => $now,
			'user_id' => $profile_id,
			'is_trash' => $rule_trash
		));
		$new_id = (int) $dbconn->insert_Id();
		echo json_encode(array('error'=>0, 'id'=>$new_id, 'is_new'=>1)); die();
	}
	// Sửa: lấy cặp (task,next) cũ trước khi đổi để dọn khách nếu cặp đổi / luật bị tắt
	$_old = $clsCustomerTaskSetting->getOne($id, "task_id,next_task_id");
	$clsCustomerTaskSetting->updateOne($id, sprintf("task_id='%s', result_id='%s', next_task_id='%s', delay_value='%s', delay_unit='%s', is_trash='%s', upd_date='%s', user_id='%s'",
		$task_id, $result_id, $next_task_id, $delay_value, $delay_unit, $rule_trash, $now, $profile_id
	));
	if(!empty($_old)){
		$_old_task = (int)$_old['task_id']; $_old_next = (int)$_old['next_task_id'];
		// reset khách trỏ next cũ khi: đổi cặp (task,next) HOẶC luật bị tắt (is_trash=1) → tránh orphan task_next_id (mirror delete)
		$_pair_changed = ($_old_task!=$task_id || $_old_next!=$next_task_id);
		if($_old_task>0 && $_old_next>0 && ($_pair_changed || $rule_trash==1)){
			$clsCustomer = new Customer();
			$dbconn->Execute("UPDATE `".$clsCustomer->tbl."` SET `task_next_id`=0, `task_due_date`=0, `upd_date`=".$now." WHERE `task_current_id`='{$_old_task}' AND `task_next_id`='{$_old_next}'");
		}
	}
	echo json_encode(array('error'=>0, 'id'=>$id, 'is_new'=>0)); die();
}
/* ===== Task Setting: Delete ===== */
function default_delete_crm_task_setting(){
	global $dbconn;
	if(!crm_task_setting_can_manage()){ 
		echo json_encode(array(
		'error'=>1,
		'message'=>'Bạn không có quyền cấu hình tác nghiệp.'),
		 JSON_UNESCAPED_UNICODE
		); 
		die(); 
	}
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$customer_task_setting_id = (int) Input::post('id', 0);
	if($customer_task_setting_id<=0){
		echo json_encode(array(
			'error'=>1,
			'message'=>'Dữ liệu không hợp lệ.'
		)); die();
	}
	// lấy rule TRƯỚC khi trash để dọn khách đang trỏ next-task của nó (tránh state cụt khi move_next)
	$_rule = $clsCustomerTaskSetting->getOne($customer_task_setting_id, "task_id,next_task_id");
	$clsCustomerTaskSetting->updateOne($customer_task_setting_id, "is_trash=1");
	if(!empty($_rule) && (int)$_rule['next_task_id'] > 0 && (int)$_rule['task_id'] > 0){
		$clsCustomer = new Customer();
		$_t = (int)$_rule['task_id']; $_n = (int)$_rule['next_task_id'];
		// reset pending next-task -> khách re-resolve khi chọn kết quả lần sau (KHÔNG đụng time_receipt = ngày-nhận dashboard)
		$dbconn->Execute("UPDATE `".$clsCustomer->tbl."` SET `task_next_id`=0, `task_due_date`=0, `upd_date`=".time()." WHERE `task_current_id`='{$_t}' AND `task_next_id`='{$_n}'");
	}
	// Return
	echo json_encode(array(
		'error'=>0
	)); die();
}
