<?php if(!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Office Management — quản lý toạ độ/geofence văn phòng cho tính năng Check-in.
 * Văn phòng = các dòng _OFFICE có sẵn trong default_setting. Ở đây chỉ MERGE thêm
 * khoá geofence (lat/lng/radius_m/max_accuracy_m/address/is_active) vào
 * more_information JSON — KHÔNG đụng các khoá cũ (fund/acc đang dùng).
 */

/* Trang chính: list 7 VP + form cấu hình (framework render views/office/default.tpl). */
function default_default(){
	global $assign_list, $core, $clsModule;
	$assign_list['clsModule'] = $clsModule;
	// JSON_HEX_* chống vỡ thẻ <script> nếu tên VP chứa ký tự đặc biệt
	$assign_list['offices_json'] = json_encode(office_get_list(), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	// Khung giờ check-in TOÀN HỆ THỐNG (cấu hình được qua Configuration 'checkin_configs')
	$clsOC = new OfficeCheckin();
	$cfg = $clsOC->getConfig();
	$assign_list['win_start'] = $cfg['window_start'];
	$assign_list['win_end'] = $cfg['window_end'];
	$assign_list['checkin_window'] = $cfg['window_start'].' – '.$cfg['window_end'];
}

/* AJAX: lưu khung giờ check-in toàn hệ thống. */
function default_save_window(){
	$start = trim(Input::post('window_start', ''));
	$end = trim(Input::post('window_end', ''));
	$re = '/^([01]\d|2[0-3]):[0-5]\d$/';
	if(!preg_match($re, $start) || !preg_match($re, $end)){ office_json_error('Giờ không hợp lệ (định dạng HH:MM)'); }
	if(strtotime('2000-01-01 '.$start) >= strtotime('2000-01-01 '.$end)){ office_json_error('Giờ mở phải nhỏ hơn giờ đóng'); }
	$clsOC = new OfficeCheckin();
	$clsOC->saveConfig(array('window_start' => $start, 'window_end' => $end));
	echo json_encode(array(
		'error'   => 0,
		'message' => 'Đã lưu khung giờ '.$start.' – '.$end,
		'window'  => $start.' – '.$end,
	), JSON_UNESCAPED_UNICODE);
	die();
}

/* Đọc 7 VP _OFFICE kèm geofence đã parse (đọc thẳng DB để form luôn mới nhất). */
function office_get_list(){
	global $core, $clsISO;
	$clsSetting = office_setting();
	$rows = $clsSetting->getAll("`is_trash`=0 AND `_type`='_OFFICE' ORDER BY `order_no` ASC", "`setting_id`,`code`,`title`,`more_information`");
	$list = array();
	if(!empty($rows)){
		foreach($rows as $r){
			$mi = $clsISO->to_array_json($r['more_information']);
			if(!is_array($mi)){ $mi = array(); }
			$list[] = array(
				'setting_id'     => (int) $r['setting_id'],
				'code'           => (string) $r['code'],
				'title'          => (string) $r['title'],
				'address'        => (string) $core->get_field($mi, 'address', ''),
				'lat'            => (float) $core->get_field($mi, 'lat', 0),
				'lng'            => (float) $core->get_field($mi, 'lng', 0),
				'radius_m'       => (int) $core->get_field($mi, 'radius_m', 200),
				'max_accuracy_m' => (int) $core->get_field($mi, 'max_accuracy_m', 80),
				'is_active'      => (int) $core->get_field($mi, 'is_active', 1),
			);
		}
	}
	return $list;
}

/* AJAX: lưu geofence vào more_information của 1 dòng _OFFICE (đọc-sửa-ghi = MERGE). */
function default_save(){
	global $core, $clsISO;
	$clsSetting = office_setting();
	$setting_id = (int) Input::post('setting_id', 0);
	if($setting_id <= 0){ office_json_error('Thiếu văn phòng'); }
	$one = $clsSetting->getOne($setting_id);
	if(empty($one) || $one['_type'] != '_OFFICE'){ office_json_error('Văn phòng không hợp lệ'); }
	$lat = (float) Input::post('lat', 0);
	$lng = (float) Input::post('lng', 0);
	if($lat < -90 || $lat > 90 || $lat == 0 || $lng < -180 || $lng > 180 || $lng == 0){
		office_json_error('Toạ độ chưa hợp lệ');
	}
	$radius = (int) Input::post('radius_m', 200);
	if($radius < 30){ $radius = 30; }
	if($radius > 250){ $radius = 250; } // cap chống "đứng xa vẫn tính"
	$max_acc = (int) Input::post('max_accuracy_m', 80);
	if($max_acc < 20){ $max_acc = 20; }
	// MERGE: giữ nguyên các khoá cũ, chỉ set lại geofence
	$mi = $clsISO->to_array_json($one['more_information']);
	if(!is_array($mi)){ $mi = array(); }
	$mi['address']        = Input::post('address', '');
	$mi['lat']            = $lat;
	$mi['lng']            = $lng;
	$mi['radius_m']       = $radius;
	$mi['max_accuracy_m'] = $max_acc;
	$mi['is_active']      = ((int) Input::post('is_active', 1)) ? 1 : 0;
	$mi['upd_date']       = time();	// default_setting KHÔNG có cột upd_date → lưu mốc cập nhật vào JSON
	$ok = $clsSetting->updateOne($setting_id, array(
		'more_information' => json_encode($mi, JSON_UNESCAPED_UNICODE),
	));
	if(!$ok){ office_json_error('Lưu thất bại'); }
	office_clear_cache();
	echo json_encode(array(
		'error'    => 0,
		'message'  => 'Đã lưu toạ độ văn phòng',
		'radius_m' => $radius,
	), JSON_UNESCAPED_UNICODE);
	die();
}

/* Helper: lấy instance Setting (ưu tiên global sẵn có). */
function office_setting(){
	global $clsSetting;
	if(empty($clsSetting)){ $clsSetting = new Setting(); }
	return $clsSetting;
}

/* Helper: xoá Redis cache để front check-in đọc được geofence mới. */
function office_clear_cache(){
	try{
		$clsCache = new Cache();
		$clsCache->delete('setting__OFFICE_cached');
	} catch(Exception $e){}
}

/* Helper: trả JSON lỗi rồi dừng. */
function office_json_error($message){
	echo json_encode(array('error' => 1, 'message' => $message), JSON_UNESCAPED_UNICODE);
	die();
}
