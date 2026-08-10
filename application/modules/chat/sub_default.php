<?php if(!defined('ABSPATH')) exit('No direct script access allowed');
// Fallback hằng số chat — phòng khi config.php chưa upload kịp. Idempotent: nếu config.php đã define trước thì bỏ qua.
if(!defined('_CHAT_MSG_RECALLED')){ define('_CHAT_MSG_RECALLED', 9); }		// type tin đã thu hồi
if(!defined('_CHAT_RECALL_WINDOW')){ define('_CHAT_RECALL_WINDOW', 86400); }	// cửa thu hồi 24h (giây)
if(!defined('_CHAT_PIN_MAX')){ define('_CHAT_PIN_MAX', 5); }			// số tin ghim tối đa/kênh
/**
 * Module chat (front) — Chat nội bộ + Check-in.
 * Increment 1: backend Check-in submit.
 *   Luật: check-in ở VP NÀO CŨNG ĐƯỢC (khớp VP gần nhất trong bán kính),
 *   trong khung giờ global (_CHECKIN_WINDOW_START..END), 1 lần/ngày.
 */
/* Trang /chat đầy đủ (UI chính nằm ở block chat — trang này dự phòng). */
function default_default(){
	global $assign_list;
}
/* AJAX: gửi check-in (ảnh base64 + GPS) → resolve VP gần nhất + ghi nhận. */
function default_checkin(){
	global $profile_id,$dbconn,$clsISO;
	$clsOC = new OfficeCheckin();
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$now = time();
	// GPS vẫn BẮT BUỘC (chỉ bỏ kiểm tra khớp văn phòng — check-in tự do, nhiều lần, bất kỳ đâu).
	$lat = (float) Input::post('lat', 0);
	$lng = (float) Input::post('lng', 0);
	$accuracy = (int) Input::post('accuracy', 0);
	if($lat == 0 || $lng == 0){ chat_json(1, 'Thiếu vị trí GPS'); }
	// Ghi chú (tuỳ chọn) + địa chỉ (client gửi sẵn từ act=geocode → khỏi gọi Google lần 2). Cắt theo cột VARCHAR(255).
	$note = chat_trim255(Input::post('note', ''));
	$address = chat_trim255(Input::post('address', ''));
	// Tag hoạt động (CHỌN NHIỀU) — client gửi "id,id"; whitelist theo _CHECKIN_TAGS (setting_id) + bỏ trùng. Lưu more_information.tags[].
	$clsSettingTag = new Setting();
	$tag_rows = $clsSettingTag->getArraySearchByKey('_CHECKIN_TAGS');
	$valid_tags = is_array($tag_rows) ? array_map('strval', array_keys($tag_rows)) : array();
	$tags = array();
	foreach(explode(',', (string) Input::post('tags', '')) as $t){
		$t = trim($t);
		if($t !== '' && in_array($t, $valid_tags, true) && !in_array($t, $tags, true)){ $tags[] = $t; }
	}
	// Ngoài VP → BẮT BUỘC có tag HOẶC ghi chú (chống check-in trống bên ngoài văn phòng). Resolve VP từ GPS.
	$resolved = report_resolve_office_name((float)$lat, (float)$lng, $clsSettingTag->getArraySearchByKey('_OFFICE'));
	$at_office = ($resolved !== 'Ngoài VP');
	if(!$at_office && empty($tags) && $note === ''){
		chat_json(1, 'Do bạn đang checkin bên ngoài VP làm việc, vui lòng ghi rõ nội dung và hoạt động.');
	}
	$io = Input::post('io', 'in');
	if($io === 'out') {
		$io = 'out'; 
		$check_type = 1;
	}else{		
		$io = 'in'; 
		$check_type = 0;
	}
	$more = array('io' => $io);	// nút toggle: check-in (bắt đầu ngày) / check-out (kết thúc ngày) — lưu cờ, cùng luồng ảnh/tag/note
	if($address !== ''){ $more['address'] = $address; }
	if(!empty($tags)){ $more['tags'] = $tags; }
	$more_json = json_encode($more, JSON_UNESCAPED_UNICODE);	// io luôn có → luôn lưu more_information
	// Ảnh
	$photo = chat_upload_checkin_photo();
	if(empty($photo)){ chat_json(1, 'Thiếu ảnh check-in'); }
	// Ghi nhận: KHÔNG khung giờ, KHÔNG khớp VP (office_id=0/distance_m=0), CHO nhiều lần/ngày.
	$newId = $clsOC->insert(array(
		'office_id'      => 0,
		'profile_id'     => (int) $profile_id,
		'department_id'  => chat_profile_dept($profile_id),
		'work_date'      => (int) date('Ymd', $now),
		'checkin_time'   => $now,
		'check_type'   	 => $check_type,
		'photo'          => $photo,
		'photo_hash'     => md5($photo),
		'note'           => ($note !== '') ? $note : null,
		'lat'            => $lat,
		'lng'            => $lng,
		'distance_m'     => 0,
		'accuracy_m'     => $accuracy,
		// địa chỉ + tags[] lưu trong more_information (cột text json) → journey đọc lại
		'more_information' => $more_json,
		'reg_date'       => $now,
	));
	if(!empty($newId)){ 	
	//	===============================
		#send zalo
		/*$addr      = $clsOC->chat_checkin_address($more_json);
		$location = ($resolved !== 'Ngoài VP') ? $resolved : (!empty($addr) ? $addr : 'Ngoài VP');
		$tag_names = [];
		if(!empty($tags)) {
			foreach($tags as $tag_id) {
				if(isset($tag_rows[$tag_id])) {
					$tag_names[] = (!empty($tag_rows[$tag_id]["icon_zalo"]) ? ($tag_rows[$tag_id]["icon_zalo"]." ") : "" ).$tag_rows[$tag_id]["title"];	
				}			
			}
		}		
		$path = chat_upload_image_data($_POST["photo"],'/CHECKIN/'.date('Ym'),"path");
		$arr_msg = [
			"io"	=>	$io,
			"tags"	=>	$tag_names,
			"location"	=>	$location,
			"photo"	=>	$path,
			"note"	=>	$note,		
			"link_map"	=>	"https://www.google.com/maps?q=".$lat.",".$lng."",		
		];
		$send = $clsOC->sendMsg($arr_msg);*/
	//	===============================
		chat_json(0, 'Đã check-in', array(
			'time'    => date('H:i', $now),
			'photo'   => $photo,
			'lat'     => $lat,
			'lng'     => $lng,
			'note'    => $note,
			'address' => $address,
		));
	}else{ 
		chat_json(1, 'Lưu check-in thất bại, thử lại'); 
	}
}
/* AJAX: NHẬT KÝ mọi lần check-in hôm nay theo phòng (subtree) — mỗi lần 1 dòng, kèm toạ độ. */
function default_feed(){
	global $profile_id,$clsISO;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$work_date = (int) date('Ymd', time());
	$offset = (int) Input::request('offset', 0); if($offset < 0){ $offset = 0; }
	$limit = 10;
	$clsProfile = new Profile();
	// Scope theo VAI TRÒ: BLĐ (_PROFILE_BLD_ID) xem TẤT CẢ NV active; GĐ Vùng/GĐ KD/NV chỉ xem subtree phòng ban của mình
	if(in_array((int) $profile_id, _PROFILE_BLD_ID, true)){
		$staff = $clsProfile->getProfileCached('active');
	} else {
		$dept = chat_profile_dept($profile_id);
		$staff = $clsProfile->getProfileDep($dept, 1, 'active');	// subtree phòng ban qua list_department_id
	}
	$staffIds = array_map('intval', array_keys($staff));
	$deptMap = (new Property())->getArraySearchByKey('_DEPARTMENT');	// map id => {property_code,...} (cached, chống N+1)
	$list = array();
	$mine = array();	// check-in của CHÍNH MÌNH (đầy đủ, KHÔNG phân trang) → "Của tôi" luôn hiện
	$rows = array();
	$has_more = false;
	if(!empty($staffIds)){
		$clsSetting = new Setting();
		$offices = $clsSetting->getArraySearchByKey('_OFFICE');
		$clsOC = new OfficeCheckin();
		// Số lượt check-in hôm nay / người (TOÀN NGÀY, không theo trang) → FE hiện nút "Hành trình" khi >=2
		$cntMap = array();
		$cRows = $clsOC->getAll("`work_date`='{$work_date}' AND `profile_id` IN (".implode(',', $staffIds).") GROUP BY `profile_id`", "`profile_id`, COUNT(*) AS `c`");
		if(is_array($cRows)){ foreach($cRows as $cr){ $cntMap[(int) $cr['profile_id']] = (int) $cr['c']; } }
		// MỌI lần check-in hôm nay TRONG scope (IN → LIMIT map đúng số hiện), mới nhất lên đầu; lấy limit+1 để dò has_more
		$rows = $clsOC->getAll("`work_date`='{$work_date}' AND `profile_id` IN (".implode(',', $staffIds).") ORDER BY `checkin_time` DESC LIMIT {$offset}, ".($limit + 1), "`profile_id`,`checkin_time`,`lat`,`lng`,`photo`,`note`,`more_information`");
		if(!is_array($rows)){ $rows = array(); }
		$has_more = (count($rows) > $limit);
		if($has_more){ $rows = array_slice($rows, 0, $limit); }
		foreach($rows as $r){
			$resolved = report_resolve_office_name((float)$r['lat'], (float)$r['lng'], $offices);
			$addr      = $clsOC->chat_checkin_address($r['more_information']);
			$location = ($resolved !== 'Ngoài VP') ? $resolved : (!empty($addr) ? $addr : 'Ngoài VP');
			$pid = (int) $r['profile_id'];
			$p = isset($staff[$pid]) ? $staff[$pid] : array();
			$info_staff  = isset($staff[$pid]["more_information"]) ? $staff[$pid]["more_information"] : [];
			$deptId = isset($p['department_id']) ? (int) $p['department_id'] : 0;
			$list[] = array(
				'profile_id' => $pid,
				'name'       => isset($p['full_name']) ? $p['full_name'] : '',
				'dept'       => !empty($info_staff["department_name"]) ? $info_staff["department_name"] : (isset($deptMap[$deptId]['property_code']) ? $deptMap[$deptId]['property_code'] : ''),	// code phòng ban (vd FH18)
				'avatar'     => $clsProfile->getAvatar($pid, $p),
				'time'       => date('H:i', $r['checkin_time']),
				'lat'        => (float) $r['lat'],
				'lng'        => (float) $r['lng'],
				'photo'      => $r['photo'],
				'note'       => isset($r['note']) ? (string) $r['note'] : '',
				//'address'    => chat_checkin_address($r['more_information']),	// địa chỉ đã geocode (rỗng nếu không có)
				'address'    => $location,	// địa chỉ đã geocode (rỗng nếu không có)
				'count'      => isset($cntMap[$pid]) ? $cntMap[$pid] : 1,	// số lượt CI hôm nay của người này
			);
		}
		// "Của tôi": check-in của CHÍNH MÌNH hôm nay (ĐẦY ĐỦ, không theo trang) → luôn hiện dù list phân trang đẩy mình xuống trang sau
		if($offset === 0){
			$me_p    = isset($staff[(int)$profile_id]) ? $staff[(int)$profile_id] : array();
			$me_info = (isset($me_p["more_information"]) && is_array($me_p["more_information"])) ? $me_p["more_information"] : array();
			$me_dept = isset($me_p['department_id']) ? (int)$me_p['department_id'] : 0;
			$myRows  = $clsOC->getAll("`work_date`='{$work_date}' AND `profile_id`='".(int)$profile_id."' ORDER BY `checkin_time` DESC", "`profile_id`,`checkin_time`,`lat`,`lng`,`photo`,`note`,`more_information`");
			if(is_array($myRows)){
				foreach($myRows as $r){
					$mr   = report_resolve_office_name((float)$r['lat'], (float)$r['lng'], $offices);
					$ma   = $clsOC->chat_checkin_address($r['more_information']);
					$mloc = ($mr !== 'Ngoài VP') ? $mr : (!empty($ma) ? $ma : 'Ngoài VP');
					$mine[] = array(
						'profile_id' => (int)$profile_id,
						'name'       => isset($me_p['full_name']) ? $me_p['full_name'] : '',
						'dept'       => !empty($me_info["department_name"]) ? $me_info["department_name"] : (isset($deptMap[$me_dept]['property_code']) ? $deptMap[$me_dept]['property_code'] : ''),
						'avatar'     => $clsProfile->getAvatar((int)$profile_id, $me_p),
						'time'       => date('H:i', $r['checkin_time']),
						'lat'        => (float) $r['lat'],
						'lng'        => (float) $r['lng'],
						'photo'      => $r['photo'],
						'note'       => isset($r['note']) ? (string) $r['note'] : '',
						'address'    => $mloc,
						'count'      => isset($cntMap[(int)$profile_id]) ? $cntMap[(int)$profile_id] : count($myRows),
					);
				}
			}
		}
	}
	echo json_encode(array(
		'error'       => 0,
		'mine'        => $mine,		// check-in của chính mình (đầy đủ) — "Của tôi" lấy từ đây, KHÔNG từ list phân trang
		'list'        => $list,
		'count'       => count($list),
		'has_more'    => $has_more ? 1 : 0,
		'next_offset' => $offset + (is_array($rows) ? count($rows) : 0),
	), JSON_UNESCAPED_UNICODE);
	die();
}
function report_haversine($lat1, $lng1, $lat2, $lng2){
	$R = 6371000; $t = M_PI / 180;
	$dla = ($lat2 - $lat1) * $t;
	$dlo = ($lng2 - $lng1) * $t;
	$a = sin($dla/2)*sin($dla/2) + cos($lat1*$t)*cos($lat2*$t)*sin($dlo/2)*sin($dlo/2);
	return (int) round($R * 2 * atan2(sqrt($a), sqrt(1-$a)));
}
function report_resolve_office_name($lat, $lng, $offices){
	global $clsISO;
	if(empty($lat) || empty($lng)){ return 'Ngoài VP'; }
	$best = null; $best_dist = PHP_INT_MAX;
	foreach($offices as $o){
		$mi    = isset($o['more_information']) ? $o['more_information'] : array();
		/* Bỏ qua VP không hoạt động (is_active=0), mặc định 1 nếu chưa set */
		if(isset($mi['is_active']) && !(int)$mi['is_active']){ continue; }
		$olat  = (float) (isset($mi['lat'])  ? $mi['lat']  : 0);
		$olng  = (float) (isset($mi['lng'])  ? $mi['lng']  : 0);
		if($olat == 0 || $olng == 0){ continue; }
		$radius = (int) (isset($mi['radius_m']) ? $mi['radius_m'] : 200);
 		$dist   = report_haversine($lat, $lng, $olat, $olng);
		if($dist <= $radius && $dist < $best_dist){
			$best_dist = $dist;
			$best      = $o['title'];
		}
	}
	return !empty($best) ? $best : 'Ngoài VP';
}
/* HELPER (bản chat2, copy report): subtree dept theo parent_id, GỒM root. */
function report_dept_descendants($flat, $root_id){
	$root_id = (int) $root_id;
	if($root_id <= 0){ return array(); }
	$by_parent = array();
	foreach($flat as $id => $row){ $pid = (int)(isset($row['parent_id']) ? $row['parent_id'] : 0); $by_parent[$pid][] = (int) $id; }
	$result = array($root_id); $stack = array($root_id);
	while(!empty($stack)){ $cur = array_pop($stack); if(!empty($by_parent[$cur])){ foreach($by_parent[$cur] as $cid){ $result[] = $cid; $stack[] = $cid; } } }
	return array_unique($result);
}
/* HELPER: dept_ids người xem được — DIRECTOR/BO = toàn công ty; else = subtree phòng ban mình. */
function chat_checkin_scope($profile_id, $clsProperty){
	global $clsISO;
	$arr = $clsProperty->getArraySearchByKey('_DEPARTMENT');
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO')){
		$allowed = array_keys($arr);
	} else {
		$allowed = report_dept_descendants($arr, chat_profile_dept($profile_id));
	}
	return array_unique(array_filter(array_map('intval', $allowed)));
}
/* Duyệt cây phòng ban dưới $parent theo thứ tự cha→con, kèm depth để FE thụt lề.
   Chỉ lấy 1 cấp thì cây 4 tầng (Khối Kinh doanh → Phòng Kinh doanh → Khối S1 → Phòng KD01)
   chỉ lòi ra tầng 2 — vùng và phòng thật không bao giờ chọn được trong bộ lọc. */
function chat_dept_tree($arr_dept, $parent, $skip = 0, $depth = 0){
	$out = array();
	if($depth > 8){ return $out; }			// chặn đệ quy vô hạn nếu dữ liệu lỡ có vòng cha-con
	foreach($arr_dept as $id => $row){
		$rid = (int) $id;
		if((int) $row['parent_id'] !== (int) $parent){ continue; }
		if($rid === (int) $skip){ continue; }
		$out[] = array('id' => $rid, 'title' => isset($row['title']) ? $row['title'] : '', 'depth' => $depth);
		$out = array_merge($out, chat_dept_tree($arr_dept, $rid, $skip, $depth + 1));
	}
	return $out;
}
/* AJAX: Thống kê hôm nay (Đã CI / Chưa CI / Tổng NS) theo PHẠM VI + NGÀY. DIRECTOR/BO/BUSINESS_AREA/SALE_DIRECTOR. */
function default_stats(){
	global $profile_id, $clsISO, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$isDir  = $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BO');
	$isArea = $clsISO->checkPermissionGroup('BUSINESS_AREA');
	$isSale = $clsISO->checkPermissionGroup('SALE_DIRECTOR');
	if(!$isDir && !$isArea && !$isSale && !$clsISO->_DEV()){ chat_json(1, 'Không có quyền xem thống kê'); }
	$clsProperty = new Property();
	$arr_dept  = $clsProperty->getArraySearchByKey('_DEPARTMENT');
	$sale_root = defined('_DEPARTMENT_SALE_ID') ? (int) _DEPARTMENT_SALE_ID : 40;
	$mode = $isDir ? 'director' : ($isArea ? 'region' : 'sale');
	/* ---- Ngày (Ymd, mặc định hôm nay) ---- */
	$date = (int) Input::request('date', 0);
	if($date <= 0 || $date > (int) date('Ymd')){ $date = (int) date('Ymd'); }
	/* ---- Scope quyền + phạm vi chọn ($dept: 0=toàn scope) ---- */
	$scoped = chat_checkin_scope($profile_id, $clsProperty);
	$dept = (int) Input::request('dept', 0);
	if($dept > 0 && in_array($dept, $scoped, true)){
		$q = array_values(array_intersect(report_dept_descendants($arr_dept, $dept), $scoped));
		if(empty($q)){ $q = array($dept); }
	} else { $dept = 0; $q = $scoped; }
	/* ---- Tổng NS (roster active trong scope) ---- */
	$clsProfileObj = new Profile();
	$roster = array();
	foreach($q as $dep){
		$staff = $clsProfileObj->getProfileDep((int)$dep, 0, 'active');
		if(is_array($staff)){ foreach($staff as $pv){ $roster[(int)$pv['profile_id']] = 1; } }
	}
	$total = count($roster);
	/* ---- Đã CI = distinct profile check-in ngày $date trong scope, ∩ roster ---- */
	$checked = 0;
	if(!empty($q)){
		$clsOC  = new OfficeCheckin();
		$ciRows = $clsOC->getAll("`work_date`={$date} AND `department_id` IN (".implode(',', $q).") GROUP BY `profile_id`", "`profile_id`");
		if(is_array($ciRows)){ foreach($ciRows as $r){ if(isset($roster[(int)$r['profile_id']])){ $checked++; } } }
	}
	$not = max(0, $total - $checked);
	/* ---- Filter options theo role (FE dựng dropdown) ---- */
	$dir_id  = defined('_DEPARTMENT_DIRECTOR_ID') ? (int) _DEPARTMENT_DIRECTOR_ID : 0;	// BGĐ — loại khỏi bộ lọc (giống load_checkin_region)
	$regions = array(); $blocks = array(); $region_depts = array(); $my_region = null;
	if($mode === 'director'){
		$regions = chat_dept_tree($arr_dept, $sale_root, $dir_id);			// cả cây dưới khối kinh doanh
		foreach($arr_dept as $id => $row){
			$rid = (int) $id;
			if((int) $row['parent_id'] !== 0){ continue; }
			if($rid === $sale_root || $rid === $dir_id){ continue; }			// bỏ khối kinh doanh (đã liệt kê ở trên) + BGĐ
			$blocks[] = array('id'=>$rid, 'title'=>isset($row['title']) ? $row['title'] : '', 'depth'=>0);
			$blocks = array_merge($blocks, chat_dept_tree($arr_dept, $rid, $dir_id, 1));
		}
	} elseif($mode === 'region'){
		$myr = chat_profile_dept($profile_id);
		$my_region = array('id'=>$myr, 'title'=>isset($arr_dept[$myr]['title']) ? $arr_dept[$myr]['title'] : 'Vùng của tôi');
		$region_depts = chat_dept_tree($arr_dept, $myr, $dir_id);			// cả cây dưới vùng mình
	}
	$label = ($dept > 0 && isset($arr_dept[$dept]['title'])) ? $arr_dept[$dept]['title'] : ($mode === 'director' ? 'Toàn công ty' : ($my_region ? $my_region['title'] : ''));
	echo json_encode(array(
		'error'        => 0,
		'mode'         => $mode,
		'regions'      => $regions,
		// Tên thật của khối gốc kinh doanh: đừng hardcode ở tpl — mỗi bản nhân bản đặt tên một kiểu.
		'sale_root_title' => isset($arr_dept[$sale_root]['title']) ? $arr_dept[$sale_root]['title'] : '',
		'blocks'       => $blocks,
		'region_depts' => $region_depts,
		'my_region'    => $my_region,
		'dept'         => $dept,
		'date'         => $date,
		'label'        => $label,
		'stats'        => array('checked_in'=>$checked, 'not_checked_in'=>$not, 'total'=>$total),
		'updated'      => date('H:i'),
	), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: Danh sách người theo type (all/has_checkin/not_checkin) — popup khi click số trên card thống kê. Cùng scope+roster với default_stats nên số liệu khớp. */
function default_stats_list(){
	global $profile_id, $clsISO;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	if(!$clsISO->checkPermissionGroup('DIRECTOR') && !$clsISO->checkPermissionGroup('BO') && !$clsISO->checkPermissionGroup('BUSINESS_AREA') && !$clsISO->checkPermissionGroup('SALE_DIRECTOR') && !$clsISO->_DEV()){ chat_json(1, 'Không có quyền'); }
	$clsProperty = new Property();
	$arr_dept = $clsProperty->getArraySearchByKey('_DEPARTMENT');
	$type = Input::request('type', 'has_checkin');
	if(!in_array($type, array('all', 'has_checkin', 'not_checkin'), true)){ $type = 'has_checkin'; }
	$date = (int) Input::request('date', 0);
	if($date <= 0 || $date > (int) date('Ymd')){ $date = (int) date('Ymd'); }
	/* Scope + phạm vi (GIỐNG default_stats) */
	$scoped = chat_checkin_scope($profile_id, $clsProperty);
	$dept = (int) Input::request('dept', 0);
	if($dept > 0 && in_array($dept, $scoped, true)){
		$q = array_values(array_intersect(report_dept_descendants($arr_dept, $dept), $scoped));
		if(empty($q)){ $q = array($dept); }
	} else { $q = $scoped; }
	/* Roster (kèm hồ sơ để hiển thị) */
	$clsProfileObj = new Profile();
	$roster = array();
	foreach($q as $dep){
		$staff = $clsProfileObj->getProfileDep((int)$dep, 0, 'active');
		if(is_array($staff)){ foreach($staff as $pv){ $roster[(int)$pv['profile_id']] = $pv; } }
	}
	/* Check-in ngày $date trong scope: MIN(vào)/MAX(ra)/COUNT mỗi người */
	$ci_min = array(); $ci_max = array(); $ci_cnt = array();
	if(!empty($q)){
		$clsOC = new OfficeCheckin();
		$rows  = $clsOC->getAll("`work_date`={$date} AND `department_id` IN (".implode(',', $q).") ORDER BY `checkin_time` ASC", "`profile_id`,`checkin_time`");
		if(is_array($rows)){
			foreach($rows as $r){
				$pid = (int) $r['profile_id']; $t = (int) $r['checkin_time'];
				if(!isset($ci_min[$pid])){ $ci_min[$pid] = $t; }
				$ci_max[$pid] = $t;
				$ci_cnt[$pid] = (isset($ci_cnt[$pid]) ? $ci_cnt[$pid] : 0) + 1;
			}
		}
	}
	/* Lọc theo type + build list */
	$list = array();
	foreach($roster as $pid => $pv){
		$has = isset($ci_cnt[$pid]);
		if($type === 'has_checkin' && !$has){ continue; }
		if($type === 'not_checkin' && $has){ continue; }
		$mi     = (isset($pv['more_information']) && is_array($pv['more_information'])) ? $pv['more_information'] : array();
		$dep_id = isset($pv['department_id']) ? (int) $pv['department_id'] : 0;
		$cnt    = isset($ci_cnt[$pid]) ? $ci_cnt[$pid] : 0;
		$list[] = array(
			'profile_id' => (int) $pid,
			'name'       => isset($pv['full_name']) ? $pv['full_name'] : '',
			'dept'       => !empty($mi['department_name']) ? $mi['department_name'] : (isset($arr_dept[$dep_id]['property_code']) ? $arr_dept[$dep_id]['property_code'] : ''),
			'avatar'     => $clsProfileObj->getAvatar((int)$pid, $pv),
			'time_in'    => isset($ci_min[$pid]) ? date('H:i', $ci_min[$pid]) : '',
			'time_out'   => ($cnt >= 2 && isset($ci_max[$pid])) ? date('H:i', $ci_max[$pid]) : '',
			'count'      => $cnt,
		);
	}
	/* CI nhiều lần lên đầu; còn lại giữ thứ tự roster */
	$sortc = array();
	foreach($list as $it){ $sortc[] = $it['count']; }
	if(!empty($list)){ array_multisort($sortc, SORT_DESC, $list); }
	$uid  = $clsISO->getUniqid();
	$html = chat_stats_list_html($list, $type);	// modal HTML (Bootstrap) — mở bằng $Core.popup như report
	echo json_encode(array('uid'=>$uid, 'html'=>$html), JSON_UNESCAPED_UNICODE);
	die();
}
/* Build HTML modal danh sách người (Bootstrap thuần → styled global). Click 1 người → $Core.chat2_checkin.load_profile_journey. */
function chat_stats_list_html($list, $type){
	$titles = array('all'=>'Danh sách nhân sự', 'has_checkin'=>'Danh sách đã check-in', 'not_checkin'=>'Danh sách chưa check-in');
	$title  = isset($titles[$type]) ? $titles[$type] : 'Danh sách';
	$noav   = URL_IMAGES.'/no-avatar.jpg';
	$h  = '<div class="modal-dialog"><div class="modal-content">';
	$h .= '<div class="modal-header"><h3 class="modal-title"><strong>'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'</strong></h3><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>';
	$h .= '<div class="modal-body pt-0"><ul class="list-group list-group-flush overflow-y-auto" style="max-height:calc(100vh - 200px)">';
	if(!empty($list)){
		foreach($list as $o){
			$pid = (int) $o['profile_id'];
			$nm  = htmlspecialchars(isset($o['name']) ? $o['name'] : '', ENT_QUOTES, 'UTF-8');
			$dp  = htmlspecialchars(isset($o['dept']) ? $o['dept'] : '', ENT_QUOTES, 'UTF-8');
			$av  = htmlspecialchars(!empty($o['avatar']) ? $o['avatar'] : $noav, ENT_QUOTES, 'UTF-8');
			$ti  = !empty($o['time_in'])  ? htmlspecialchars($o['time_in'],  ENT_QUOTES, 'UTF-8') : '';
			$to  = !empty($o['time_out']) ? htmlspecialchars($o['time_out'], ENT_QUOTES, 'UTF-8') : '';
			$cnt = (int) $o['count'];
			$h .= '<li class="list-group-item d-flex align-items-center gap-2 py-2 px-3">';
			$h .= '<a href="javascript:void(0);" onclick="$Core.chat2_checkin.load_profile_journey(this, event)" data-profile="'.$pid.'" class="d-flex align-items-center gap-2 text-decoration-none flex-grow-1 text-dark" title="Xem hành trình check-in">';
			$h .= '<img class="rounded-pill" src="'.$av.'" onerror="this.src=\''.htmlspecialchars($noav, ENT_QUOTES, 'UTF-8').'\'" width="36" height="36" alt="">';
			$h .= '<div class="flex-grow-1 overflow-hidden"><small class="text-muted d-block">'.$dp.'</small>';
			$h .= '<span class="fw-semibold text-truncate d-block" style="max-width:180px" title="'.$nm.'">'.$nm.'</span>';
			$h .= '<small class="d-block" style="font-size:11px"><span class="text-muted">Vào:</span> <span class="fw-semibold text-success">'.($ti !== '' ? $ti : '--').'</span>';
			if($to !== ''){ $h .= ' <span class="text-muted ms-2">Ra:</span> <span class="fw-semibold text-danger">'.$to.'</span>'; }
			$h .= '</small></div>';
			if($cnt > 0){ $h .= '<span class="badge bg-label-primary flex-shrink-0">'.$cnt.' lần</span>'; }
			$h .= '<i class="bx bx-chevron-right text-muted fs-5 flex-shrink-0"></i></a></li>';
		}
	} else {
		$h .= '<li><div class="text-center p-4 fs-6">Không có nhân sự</div></li>';
	}
	$h .= '</ul></div></div></div>';
	return $h;
}
/* AJAX: Hành trình 1 người (modal) — mở/đổi trong popup danh sách qua $Core.chat2_checkin. Trả {uid, html, day}. */
function default_stats_journey(){
	global $profile_id, $clsISO;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$date = (int) Input::request('date', 0);
	if($date <= 0 || $date > (int) date('Ymd')){ $date = (int) date('Ymd'); }
	$target  = (int) Input::request('pid', 0);
	$clsProperty = new Property();
	$scoped  = chat_checkin_scope($profile_id, $clsProperty);	// KHỚP phạm vi danh sách stats (DIRECTOR/BO=all, còn lại=subtree) → ai xuất hiện trong list đều xem được hành trình
	$allowed = ($target > 0 && (in_array(chat_profile_dept($target), $scoped, true) || $target === (int) $profile_id));	// enforce scope (chống IDOR)
	$uid = $clsISO->getUniqid();
	if(!$allowed){
		echo json_encode(array('uid'=>$uid, 'html'=>chat_stats_journey_html(false, array(), array(), array(), '', 1), 'day'=>$date), JSON_UNESCAPED_UNICODE);
		die();
	}
	$data = chat_journey_data($target, $date);
	/* Resolve tags (setting_id → {label,color}) + dot_color mỗi item (giống c.ckCat bên Angular) */
	$clsSetting = new Setting();
	$tagRaw = $clsSetting->getArraySearchByKey('_CHECKIN_TAGS');
	$tagMap = array();
	if(is_array($tagRaw)){ foreach($tagRaw as $tid => $trow){ $tmi = $clsISO->to_array_json((!empty($trow['more_information'])) ? $trow['more_information'] : ''); $tagMap[(int) $tid] = array('label'=>isset($trow['title']) ? $trow['title'] : '', 'color'=>!empty($tmi['bgcolor']) ? $tmi['bgcolor'] : '#64748b'); } }
	$items = array();
	foreach($data['items'] as $it){
		$rtags = array();
		if(!empty($it['tags'])){ foreach($it['tags'] as $tk){ $tk = (int) $tk; if(isset($tagMap[$tk])){ $rtags[] = $tagMap[$tk]; } } }
		$items[] = array('time'=>$it['time'], 'place'=>$it['place'], 'address'=>$it['address'], 'note'=>$it['note'], 'photo'=>$it['photo'], 'tags'=>$rtags, 'dot_color'=>(!empty($rtags) ? $rtags[0]['color'] : '#94a3b8'));
	}
	$is_today = ($date === (int) date('Ymd'));
	$html = chat_stats_journey_html(true, $data['profile'], $data['stats'], $items, chat_journey_day_label($date), $is_today);
	echo json_encode(array('uid'=>$uid, 'html'=>$html, 'day'=>$date), JSON_UNESCAPED_UNICODE);
	die();
}
/* Nhãn ngày hành trình: "Hôm nay · Thứ Hai, 07/07/2026" (bỏ "Hôm nay ·" nếu khác hôm nay). */
function chat_journey_day_label($date){
	$date = (int) $date;
	$y = (int) substr((string) $date, 0, 4); $m = (int) substr((string) $date, 4, 2); $d = (int) substr((string) $date, 6, 2);
	$ts = mktime(0, 0, 0, $m, $d, $y);
	$wd = array('Chủ nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy');
	return (($date === (int) date('Ymd')) ? 'Hôm nay · ' : '') . $wd[(int) date('w', $ts)] . ', ' . date('d/m/Y', $ts);
}
/* Build HTML modal hành trình (class .cj-* trong chat2.css, màu literal vì modal ở body ngoài #fhchat). */
function chat_stats_journey_html($allowed, $profile, $stats, $items, $day_label, $is_today){
	$noav = URL_IMAGES.'/no-avatar.jpg';
	$esc  = function($s){ return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
	$h  = '<div class="modal-dialog modal-dialog-centered"><div class="modal-content cj-modal border-0 pb-4">';
	$h .= '<div class="cj-top"><button type="button" class="cj-back" onclick="$Core.chat2_checkin.back_to_list()" title="Quay lại danh sách"><i class="bx bx-chevron-left"></i></button>';
	$h .= '<div class="cj-top-tt"><div class="cj-top-t">Timeline Check-in</div><div class="cj-top-s">Xem hành trình hoạt động trong ngày</div></div>';
	$h .= '<button type="button" class="cj-back" data-bs-dismiss="modal" title="Đóng"><i class="bx bx-x"></i></button></div>';
	$h .= '<div class="cj-scroll">';
	if(!$allowed){
		return $h.'<div class="text-center text-muted py-5 px-3">Không có quyền xem hoặc không tìm thấy nhân sự.</div></div></div></div>';
	}
	$av = !empty($profile['avatar']) ? $profile['avatar'] : $noav;
	$h .= '<div class="cj-profile"><img class="cj-av" src="'.$esc($av).'" onerror="this.src=\''.$esc($noav).'\'" alt="">';
	$h .= '<div class="cj-pi overflow-hidden"><div class="cj-nm text-truncate">'.$esc($profile['name']).'</div>';
	if(!empty($profile['role'])){ $h .= '<div class="cj-role">'.$esc($profile['role']).'</div>'; }
	if(!empty($profile['dept'])){ $h .= '<div class="cj-dept">Phòng ban: '.$esc($profile['dept']).'</div>'; }
	$h .= '</div></div>';
	$h .= '<div class="cj-datebar"><button type="button" class="cj-nav" onclick="$Core.chat2_checkin.journey_day(-1)" title="Ngày trước"><i class="bx bx-chevron-left"></i></button>';
	$h .= '<div class="cj-date">'.$esc($day_label).'</div>';
	$h .= '<button type="button" class="cj-nav" onclick="$Core.chat2_checkin.journey_day(1)"'.($is_today ? ' disabled' : '').' title="Ngày sau"><i class="bx bx-chevron-right"></i></button></div>';
	$h .= '<div class="cj-ov"><div class="cj-ovh">Tổng quan hoạt động trong ngày</div><div class="cj-stats">';
	$h .= '<div class="cj-stat"><span class="cj-sic" style="color:#16a34a;background:rgba(22,163,74,.12)"><i class="bx bx-map-pin"></i></span><b>'.(int) $stats['count'].'</b><span class="cj-statl">Lần check-in</span></div>';
	$h .= '<div class="cj-stat"><span class="cj-sic" style="color:#d97706;background:rgba(217,119,6,.12)"><i class="bx bx-been-here"></i></span><b>'.(int) $stats['places'].'</b><span class="cj-statl">Địa điểm</span></div>';
	$h .= '<div class="cj-stat"><span class="cj-sic" style="color:#7c3aed;background:rgba(124,58,237,.12)"><i class="bx bx-camera"></i></span><b>'.(int) $stats['photos'].'</b><span class="cj-statl">Ảnh đã gửi</span></div>';
	$h .= '</div></div><div class="cj-tlh">Timeline check-in trong ngày</div>';
	if(empty($items)){
		$h .= '<div class="text-center text-muted py-4">Không có check-in trong ngày này.</div>';
	} else {
		$h .= '<div class="cj-timeline">';
		foreach($items as $it){
			$dot = $esc($it['dot_color']);
			$h .= '<div class="cj-item"><div class="cj-side"><span class="cj-time">'.$esc($it['time']).'</span><span class="cj-dot" style="background:'.$dot.'"></span></div>';
			$h .= '<div class="cj-card"><div class="cj-cardtop justify-content-between"><div class="d-flex flex-column gap-2"><div class="cj-cardmain">';
			$h .= '<div class="cj-place"><i class="bx bx-map-pin" style="color:'.$dot.'"></i> '.$esc($it['place']).'</div>';
			if(!empty($it['tags'])){
				$h .= '<div class="cj-tags">';
				foreach($it['tags'] as $tg){ $h .= '<span class="cj-tag" style="color:'.$esc($tg['color']).';background:'.$esc($tg['color']).'22">'.$esc($tg['label']).'</span>'; }
				$h .= '</div>';
			}
			$h .= '</div>';
			if($it['note'] !== ''){ $h .= '<div class="cj-note">'.nl2br($esc($it['note'])).'</div>'; }
			if(!empty($it['address'])){ $h .= '<div class="cj-addr" title="'.$esc($it['address']).'"><i class="bx bx-map"></i> '.$esc($it['address']).'</div>'; }
			$h .= '</div>';
			if(!empty($it['photo'])){ $h .= '<a href="'.$esc($it['photo']).'" data-fancybox="check_in_'.$profile['profile_id'].'" target="_blank" class="cj-photo-wrap"><img class="cj-photo" src="'.$esc($it['photo']).'" onerror="this.style.display=\'none\'" alt=""></a>'; }
			$h .= '</div></div></div>';
		}
		$h .= '</div>';
	}
	$h .= '<div class="cj-foot"><i class="bx bx-info-circle"></i> Dữ liệu check-in được cập nhật tự động theo thời gian thực</div>';
	return $h.'</div></div></div>';
}
/* AJAX: số lần + giờ check-in cuối hôm nay của tôi (KHÔNG khoá — luôn cho check-in tiếp). */
function default_my(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$work_date = (int) date('Ymd', time());
	$clsOC = new OfficeCheckin();
	$count = (int) $clsOC->countItem("`profile_id`='".(int) $profile_id."' AND `work_date`='{$work_date}'");
	$last = '';
	if($count > 0){
		$row = $clsOC->getByCond("`profile_id`='".(int) $profile_id."' AND `work_date`='{$work_date}' ORDER BY `checkin_time` DESC", "`checkin_time`");
		if(!empty($row) && is_array($row)){ $last = date('H:i', (int) $row['checkin_time']); }
	}
	echo json_encode(array(
		'error'     => 0,
		'count'     => $count,		// số lần check-in hôm nay
		'last_time' => $last,		// giờ lần cuối ('' nếu chưa lần nào)
	), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: Danh sách tag check-in (từ default_setting _type='_CHECKIN_TAGS'). key=setting_id; icon+màu(bgcolor) từ more_information. */
function default_checkin_tags(){
	$clsSetting = new Setting();
	$rows = $clsSetting->getArraySearchByKey('_CHECKIN_TAGS');
	$list = array();
	if(is_array($rows)){
		foreach($rows as $sid => $row){
			$mi = (isset($row['more_information']) && is_array($row['more_information'])) ? $row['more_information'] : array();
			$list[] = array(
				'key'   => (string) $sid,
				'label' => isset($row['title']) ? $row['title'] : '',
				'color' => !empty($mi['bgcolor']) ? $mi['bgcolor'] : '#64748b',
				'icon'  => !empty($mi['icon']) ? $mi['icon'] : 'bx bx-purchase-tag',
			);
		}
	}
	echo json_encode(array('error' => 0, 'tags' => $list), JSON_UNESCAPED_UNICODE);
	die();
}
/* Ai được xem hành trình của $target? BLĐ = mọi người; còn lại = subtree phòng ban mình (KHỚP scope feed). */
function chat_checkin_can_view($me, $target){
	$me = (int) $me; $target = (int) $target;
	if($me <= 0 || $target <= 0){ return false; }
	if($me === $target){ return true; }
	if(in_array($me, _PROFILE_BLD_ID, true)){ return true; }
	$clsProfile = new Profile();
	$staff = $clsProfile->getProfileDep(chat_profile_dept($me), 1, 'active');	// subtree phòng ban qua list_department_id
	return is_array($staff) && isset($staff[$target]);
}
/* Dữ liệu hành trình 1 người trong 1 ngày (Ymd) — DÙNG CHUNG cho default_journey (JSON) + default_stats_journey (HTML modal).
   Trả {profile, stats{count,places,photos}, items[{time,place,address,tags(setting_id thô),note,photo,lat,lng}]}. KHÔNG enforce scope (caller lo). */
function chat_journey_data($target, $date){
	global $clsISO;
	$target = (int) $target; $date = (int) $date;
	$clsProfile = new Profile();
	$me = $clsProfile->getOne($target, "profile_id,full_name,avatar,department_id,more_information");
	if(!is_array($me)){ $me = array(); }
	$mi_me = $clsISO->to_array_json((!empty($me['more_information'])) ? $me['more_information'] : '');
	$profile = array(
		'name'   => isset($me['full_name']) ? $me['full_name'] : '',
		'avatar' => $clsProfile->getAvatar($target, $me),
		'role'   => isset($mi_me['role_name']) ? $mi_me['role_name'] : '',			// chức danh (vd "Giám đốc Dịch vụ")
		'dept'   => isset($mi_me['department_name']) ? $mi_me['department_name'] : '',	// tên phòng ban
		'badge'  => '',
	);
	$clsOC = new OfficeCheckin();
	$rows  = $clsOC->getAll("`profile_id`='".$target."' AND `work_date`='{$date}' ORDER BY `checkin_time` ASC", "`checkin_time`,`lat`,`lng`,`photo`,`note`,`office_id`,`more_information`");
	if(!is_array($rows)){ $rows = array(); }
	$clsSetting = new Setting();
	$offices = $clsSetting->getArraySearchByKey('_OFFICE');
	$items = array(); $places = array(); $photos = 0;
	foreach($rows as $r){
		$mi   = $clsISO->to_array_json((!empty($r['more_information'])) ? $r['more_information'] : '');
		$tags = (isset($mi['tags']) && is_array($mi['tags'])) ? $mi['tags'] : (!empty($mi['category']) ? array($mi['category']) : array());	// tags[] mới; fallback category (bản ghi cũ)
		$oid  = (int) $r['office_id'];
		if($oid > 0 && isset($offices[$oid])){
			$place = $offices[$oid]['title'];
		} else {
			$resolved = report_resolve_office_name((float) $r['lat'], (float) $r['lng'], $offices);
			$place = ($resolved !== 'Ngoài VP') ? $resolved : (!empty($mi['address']) ? $mi['address'] : 'Ngoài VP');
		}
		$addr = $clsOC->chat_checkin_address($r['more_information']);
		$items[] = array(
			'time'     => !empty($r['checkin_time']) ? date('H:i', (int) $r['checkin_time']) : '',
			'place'    => $place,
			'address'  => $addr,
			'tags'     => $tags,
			'note'     => isset($r['note']) ? (string) $r['note'] : '',
			'photo'    => isset($r['photo']) ? $r['photo'] : '',
			'lat'      => (float) $r['lat'],
			'lng'      => (float) $r['lng'],
		);
		if($place !== ''){ $places[$place] = 1; }
		if(!empty($r['photo'])){ $photos++; }
	}
	return array('profile'=>$profile, 'stats'=>array('count'=>count($items), 'places'=>count($places), 'photos'=>$photos), 'items'=>$items);
}
/* AJAX: Hành trình (Timeline Check-in) — check-in của 1 người trong 1 ngày (Ymd), sắp theo giờ tăng dần.
   Mặc định BẢN THÂN; nhận `pid` để xem người khác (enforce scope). Trả kèm hồ sơ + tags[] + place. */
function default_journey(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$date = (int) Input::request('date', 0);				// Ymd; mặc định hôm nay
	if($date <= 0){ $date = (int) date('Ymd'); }
	/* Người cần xem: mặc định bản thân; `pid` để xem người khác — ngoài quyền thì fallback bản thân (chống IDOR). */
	$target = (int) Input::request('pid', 0);
	if($target <= 0 || ($target !== (int) $profile_id && !chat_checkin_can_view($profile_id, $target))){ $target = (int) $profile_id; }
	$data = chat_journey_data($target, $date);
	echo json_encode(array('error'=>0, 'profile'=>$data['profile'], 'stats'=>$data['stats'], 'items'=>$data['items']), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: reverse-geocode toạ độ → địa chỉ (cho form xác nhận check-in hiển thị "Tên đường…"). Server giữ key Google. */
function default_geocode(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$lat = (float) Input::post('lat', 0);
	$lng = (float) Input::post('lng', 0);
	if($lat == 0 || $lng == 0){ chat_json(1, 'Thiếu toạ độ'); }
	$address = chat_reverse_geocode($lat, $lng, report_resolve_office_name($lat, $lng, (new Setting())->getArraySearchByKey('_OFFICE')) === 'Ngoài VP');	// NGOÀI VP → địa chỉ chỉ xã/phường + tỉnh/thành phố (bỏ đường/số nhà)
	if($address === null){ chat_json(1, 'Không lấy được địa chỉ'); }		// client tự fallback về toạ độ
	$geo_at_office = (report_resolve_office_name($lat, $lng, (new Setting())->getArraySearchByKey('_OFFICE')) !== 'Ngoài VP');	// báo FE: ngoài VP → bắt buộc tag/note
	chat_json(0, '', array('address' => $address, 'at_office' => $geo_at_office ? 1 : 0));
}
/* AJAX: danh sách kênh của người xem (Toàn công ty + kênh phòng ban) + số chưa đọc. */
function default_channels(){
	global $profile_id, $clsProfile;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cls = isset($clsProfile) ? $clsProfile : new Profile();
	$me = $cls->getOne((int) $profile_id, "department_id,list_department_id");
	$dept = (int) (isset($me['department_id']) ? $me['department_id'] : 0);
	$clsCh = new ChatChannel();
	// Kênh hệ thống (ghim đầu): Toàn cty (type1) + Phòng ban (type2).
	$sys = array();
	$company = chat_company_channel();
	if(!empty($company) && is_array($company)){ $sys[] = array('ch' => $company, 'role' => 0); }
	if($dept > 0){
		$deptCh = chat_dept_channel($dept);
		if(!empty($deptCh)){ $sys[] = array('ch' => $deptCh, 'role' => 0); }
	}
	$groups = chat_user_groups($profile_id);	// map cid => {ch, role}
	// Gom cids → 1 cụm query meta (last-msg + unread) batched, chống N+1.
	$cids = array();
	foreach($sys as $s){ $cids[] = (int) $s['ch']['channel_id']; }
	foreach($groups as $cid => $g){ $cids[] = (int) $cid; }
	$metaMap = chat_channels_meta($cids, $profile_id);
	$out = array();
	foreach($sys as $s){
		$cid = (int) $s['ch']['channel_id'];
		$m = isset($metaMap[$cid]) ? $metaMap[$cid] : array('last' => null, 'unread' => 0);
		$out[] = chat_channel_out($s['ch'], $profile_id, array('role' => 0, 'unread' => $m['unread'], 'last' => $m['last'], 'pinned' => isset($m['pinned']) ? $m['pinned'] : 0, 'muted' => isset($m['muted']) ? $m['muted'] : 0));
	}
	foreach($groups as $cid => $g){
		$m = isset($metaMap[$cid]) ? $metaMap[$cid] : array('last' => null, 'unread' => 0);
		$out[] = chat_channel_out($g['ch'], $profile_id, array('role' => (int) $g['role'], 'unread' => $m['unread'], 'last' => $m['last'], 'pinned' => isset($m['pinned']) ? $m['pinned'] : 0, 'muted' => isset($m['muted']) ? $m['muted'] : 0));
	}
	echo json_encode(array('error' => 0, 'channels' => $out), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: tin nhắn của 1 kênh. since>0 = lấy tin mới hơn (poll); since=0 = 50 tin gần nhất. */
function default_history(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$since = (int) Input::post('since', 0);
	$before = (int) Input::post('before', 0);
	$chAcc = chat_access($cid, $profile_id);
	if(empty($chAcc)){ chat_json(1, 'Không có quyền truy cập kênh'); }
	$ctype = (int) $chAcc['type'];
	$clsMsg = new ChatMessage();
	$fields = "`message_id`,`profile_id`,`type`,`content`,`image`,`reply_to_id`,`reg_date`";
	$HLIMIT = 50; $hasMore = false;
	if($since > 0){
		$rows = $clsMsg->getAll("`channel_id`='{$cid}' AND `message_id`>'{$since}' AND `is_trash`=0 ORDER BY `message_id` ASC", $fields);
	} elseif($before > 0){
		// Tải tin CŨ HƠN $before (load-more quá khứ): 50 tin liền trước, đảo về ASC
		$rows = $clsMsg->getAll("`channel_id`='{$cid}' AND `message_id`<'{$before}' AND `is_trash`=0 ORDER BY `message_id` DESC LIMIT {$HLIMIT}", $fields);
		$hasMore = (is_array($rows) && count($rows) >= $HLIMIT);
		$rows = is_array($rows) ? array_reverse($rows) : array();
	} else {
		$rows = $clsMsg->getAll("`channel_id`='{$cid}' AND `is_trash`=0 ORDER BY `message_id` DESC LIMIT {$HLIMIT}", $fields);
		$hasMore = (is_array($rows) && count($rows) >= $HLIMIT);
		$rows = is_array($rows) ? array_reverse($rows) : array();
	}
	$pmap = (new Profile())->getProfileCached('all');
	$list = array();
	if(!empty($rows)){
		$mids = array();
		foreach($rows as $r){ $mids[] = (int) $r['message_id']; }
		$reactMap = chat_react_map($mids, $profile_id, $cid);	// 1 query cho cả dải tin nạp (chống N+1) + channel-scope
		foreach($rows as $r){ $list[] = chat_msg_out($r, $pmap, $profile_id, $cid, $reactMap); }
	}
	if($before > 0){ echo json_encode(array('error' => 0, 'list' => $list, 'has_more' => $hasMore), JSON_UNESCAPED_UNICODE); die(); }
	// Poll tự-chữa cảm xúc: client gửi react_ids (tin đang hiển thị) → trả counts+mine tươi (badge tự đồng bộ ≤15s khi broadcast rớt).
	$react_refresh = array();
	$rawIds = Input::post('react_ids', array());
	if(is_array($rawIds) && !empty($rawIds)){ $react_refresh = chat_react_map(array_slice($rawIds, 0, 80), $profile_id, $cid); }	// channel-scope chống lộ tally tin kênh khác
	// last_read_id (TRƯỚC khi mark-read) → client đặt divider "Tin chưa đọc".
	echo json_encode(array('error' => 0, 'list' => $list, 'last_read_id' => chat_last_read($cid, $profile_id), 'react_refresh' => $react_refresh, 'pinned' => chat_channel_pinned($cid, $pmap), 'seen' => ($ctype === 4) ? chat_seen_list($cid, $profile_id, $pmap) : array(), 'has_more' => $hasMore), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: gửi 1 tin (text) vào kênh → lưu DB, trả tin đã định dạng. (Realtime push = P-A4.) */
function default_send(){
	global $profile_id, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$content = trim(Input::post('content', ''));
	$replyTo = (int) Input::post('reply_to_id', 0);	// trích dẫn tin gốc (quote)
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền gửi vào kênh'); }
	if($content === ''){ chat_json(1, 'Nội dung trống'); }
	if(mb_strlen($content) > 2000){ $content = mb_substr($content, 0, 2000); }
	$now = time();
	$clsMsg = new ChatMessage();
	$ok = $clsMsg->insert(array(
		'channel_id'  => $cid,
		'profile_id'  => (int) $profile_id,
		'type'        => 1,
		'content'     => $content,
		'reply_to_id' => $replyTo,
		'reg_date'    => $now,
	));
	if(!$ok){ chat_json(1, 'Gửi thất bại'); }
	$mid = (int) $dbconn->insert_Id();
	chat_mark_read($cid, $profile_id, $mid);
	$pmap = (new Profile())->getProfileCached('all');
	$row = array('message_id' => $mid, 'profile_id' => $profile_id, 'type' => 1, 'content' => $content, 'image' => '', 'reply_to_id' => $replyTo, 'reg_date' => $now);
	$out = chat_msg_out($row, $pmap, $profile_id, $cid);
	chat_push_node($cid, $out);	// đẩy realtime tới room kênh (best-effort; rớt thì client poll bù)
	echo json_encode(array('error' => 0, 'message' => $out), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: gửi 1 tin ẢNH (1 hoặc NHIỀU base64 qua images[]) → upload từng tấm → lưu JSON mảng path (type=2) → push realtime. content=caption. */
function default_send_image(){
	global $profile_id, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền gửi vào kênh'); }
	$content = trim(Input::post('content', ''));
	if(mb_strlen($content) > 2000){ $content = mb_substr($content, 0, 2000); }
	$replyTo = (int) Input::post('reply_to_id', 0);	// trích dẫn tin gốc (quote)
	// Nhận nhiều ảnh qua images[] đọc RAW từ $_POST (KHÔNG qua Input để base64 không bị xss_clean); fallback 1 ảnh 'image'.
	$raw = array();
	if(isset($_POST['images']) && is_array($_POST['images'])){ $raw = $_POST['images']; }
	else if(isset($_POST['image']) && $_POST['image'] !== ''){ $raw = array($_POST['image']); }
	if(count($raw) > 9){ $raw = array_slice($raw, 0, 9); }	// cap 9 ảnh/lần
	$subdir = '/CHAT/'.date('Ym');
	$paths = array();
	foreach($raw as $one){
		$p = chat_upload_image_data($one, $subdir);
		if(!empty($p)){ $paths[] = $p; }
	}
	if(empty($paths)){ chat_json(1, 'Thiếu ảnh hoặc tải ảnh lỗi'); }
	$now = time();
	$imageField = json_encode($paths, JSON_UNESCAPED_SLASHES);	// lưu JSON mảng path (cột image đã nới TEXT)
	$clsMsg = new ChatMessage();
	$ok = $clsMsg->insert(array(
		'channel_id'  => $cid,
		'profile_id'  => (int) $profile_id,
		'type'        => 2,
		'content'     => $content,
		'image'       => $imageField,
		'reply_to_id' => $replyTo,
		'reg_date'    => $now,
	));
	if(!$ok){ chat_json(1, 'Gửi ảnh thất bại'); }
	$mid = (int) $dbconn->insert_Id();
	chat_mark_read($cid, $profile_id, $mid);
	$pmap = (new Profile())->getProfileCached('all');
	$row = array('message_id' => $mid, 'profile_id' => $profile_id, 'type' => 2, 'content' => $content, 'image' => $imageField, 'reply_to_id' => $replyTo, 'reg_date' => $now);
	$out = chat_msg_out($row, $pmap, $profile_id, $cid);
	chat_push_node($cid, $out);
	echo json_encode(array('error' => 0, 'message' => $out), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: đánh dấu đã đọc kênh tới message last_id. */
function default_read(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$lastId = (int) Input::post('last_id', 0);
	// GATE trước khi mark-read: chat_mark_read tạo lười chat_member → với nhóm (type4) = tự cấp membership.
	// Không gate ⇒ forge act=read cid nhóm sẽ tự thêm mình vào nhóm → bypass chat_access. PHẢI gate như mọi handler.
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	chat_mark_read($cid, $profile_id, $lastId);
	chat_bump_seenrev($cid);	// đọc → bump seenrev RIÊNG (chỉ refetch THREAD đang mở, KHÔNG fan-out lại danh sách kênh)
	echo json_encode(array('error' => 0), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: danh sách NV active cho picker tạo nhóm (lọc phòng/search ở client). */
function default_staff(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cls = new Profile();
	$map = $cls->getProfileCached('active');	// keyed by id; đã lọc người nghỉ
	$list = array();
	if(!empty($map)){
		foreach($map as $id => $p){
			$id = (int) $id;
			if($id <= 0){ continue; }
			$mi = isset($p['more_information']) && is_array($p['more_information']) ? $p['more_information'] : array();
			$list[] = array(
				'id'        => $id,
				'name'      => isset($p['full_name']) ? $p['full_name'] : '',
				'avatar'    => $cls->getAvatar($id, $p),
				'dept_id'   => (int) (isset($p['department_id']) ? $p['department_id'] : 0),
				'dept_name' => isset($mi['department_name']) ? $mi['department_name'] : '',
			);
		}
	}
	echo json_encode(array('error' => 0, 'list' => $list), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: tạo nhóm (type=4). Mọi NV đăng nhập tạo được. Creator=owner(role=1), members=role=0. */
function default_create_group(){
	global $profile_id, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$creator = (int) $profile_id;
	$name = trim((string) Input::post('name', ''));
	if($name === ''){ chat_json(1, 'Nhập tên nhóm'); }
	if(mb_strlen($name) > 120){ $name = mb_substr($name, 0, 120); }
	// Ảnh: base64 RAW từ $_POST (KHÔNG qua Input::post — xss_clean phá chuỗi base64). Mẫu default_send_image.
	$rawImg = (isset($_POST['image']) && is_string($_POST['image'])) ? $_POST['image'] : '';
	$image = chat_upload_group_image($rawImg);	// '' nếu trống/lỗi → client fallback glyph
	// Members: mảng id; ép int, bỏ trùng + bỏ creator (creator là owner-row riêng).
	$rawMem = Input::post('members', array());
	$memIds = array();
	if(is_array($rawMem)){
		foreach($rawMem as $m){
			$m = (int) $m;
			if($m > 0 && $m !== $creator){ $memIds[$m] = $m; }
		}
	}
	// Whitelist: chỉ NV active (chống nhồi id rác / người đã nghỉ). Cap 200/lần (chống abuse — chốt với user).
	$active = (new Profile())->getProfileCached('active');
	$valid = array();
	foreach($memIds as $m){ if(isset($active[$m])){ $valid[$m] = $m; } }
	if(count($valid) > 200){ $valid = array_slice($valid, 0, 200, true); }
	$now = time();
	$clsCh = new ChatChannel();
	$clsCh->insert(array(
		'name'      => $name,
		'type'      => 4,
		'image'     => $image,
		'icon'      => 'group',
		'is_system' => 0,
		'is_active' => 1,
		'order_no'  => 10,
		'reg_date'  => $now,
	));
	$cid = (int) $dbconn->insert_Id();
	if($cid <= 0){ chat_json(1, 'Tạo nhóm thất bại'); }
	$clsMem = new ChatMember();
	$clsMem->insert(array('channel_id' => $cid, 'profile_id' => $creator, 'role' => 1, 'last_read_message_id' => 0, 'reg_date' => $now));	// owner
	foreach($valid as $m){
		$clsMem->insert(array('channel_id' => $cid, 'profile_id' => (int) $m, 'role' => 0, 'last_read_message_id' => 0, 'reg_date' => $now));
	}
	$ch = $clsCh->getByCond("`channel_id`='".$cid."' AND `is_trash`=0", "`channel_id`,`name`,`type`,`image`");
	// Truyền meta role=1 (creator=owner) → is_owner=1; nhóm mới chưa có tin → unread=0, last=null.
	$out = chat_channel_out($ch, $creator, array('role' => 1, 'unread' => 0, 'last' => null));
	$out['role'] = 1; $out['is_owner'] = 1;	// chốt chắc (creator luôn owner)
	echo json_encode(array('error' => 0, 'channel' => $out), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: thông tin nhóm (TV bất kỳ xem được): danh sách TV+role, tên/ảnh nhóm, is_owner người xem. */
function default_group_info(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$ch = chat_access($cid, $profile_id);
	if(empty($ch) || (int) $ch['type'] !== 4){ chat_json(1, 'Không có quyền truy cập nhóm'); }
	$rows = (new ChatMember())->getAll("`channel_id`='".$cid."'", "`profile_id`,`role`");
	$cls = new Profile();
	$pmap = $cls->getProfileCached('all');
	$members = array();
	if(!empty($rows)){
		foreach($rows as $r){
			$pid = (int) $r['profile_id'];
			$p = isset($pmap[$pid]) ? $pmap[$pid] : array();
			$members[] = array(
				'profile_id' => $pid,
				'name'       => isset($p['full_name']) ? $p['full_name'] : ('#'.$pid),
				'avatar'     => !empty($p) ? $cls->getAvatar($pid, $p) : '',
				'role'       => (int) $r['role'],
			);
		}
	}
	$info = (new ChatChannel())->getByCond("`channel_id`='".$cid."'", "`name`,`image`");
	echo json_encode(array(
		'error'    => 0,
		'members'  => $members,
		'is_owner' => chat_group_owner_gate($cid, $profile_id) ? 1 : 0,
		'name'     => isset($info['name']) ? $info['name'] : '',
		'image'    => isset($info['image']) ? $info['image'] : '',
	), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: thêm/xoá thành viên nhóm (owner-only). op=add (mảng member_ids, cap 200) | op=remove (member_id). */
function default_group_members(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$ch = chat_access($cid, $profile_id);
	if(empty($ch) || (int) $ch['type'] !== 4){ chat_json(1, 'Không có quyền truy cập nhóm'); }
	// chat_access đã chốt: chỉ THÀNH VIÊN nhóm tới được đây (chặn IDOR). Thêm TV = mọi thành viên (Zalo); Xoá TV = chỉ owner (gate trong nhánh remove).
	$op = (string) Input::post('op', '');
	$clsMem = new ChatMember();
	if($op === 'add'){
		$raw = Input::post('member_ids', array());
		$ids = array();
		foreach((array) $raw as $x){ $x = (int) $x; if($x > 0){ $ids[$x] = $x; } }
		if(empty($ids)){ chat_json(1, 'Chưa chọn thành viên'); }
		if(count($ids) > 200){ $ids = array_slice($ids, 0, 200, true); }	// cap chống abuse (chốt user)
		$active = (new Profile())->getProfileCached('active');	// chỉ thêm NV active
		$now = time();
		foreach($ids as $x){
			if(!isset($active[$x])){ continue; }
			$exist = $clsMem->getByCond("`channel_id`='".$cid."' AND `profile_id`='".$x."'", "`id`");	// dedup uq_channel_profile
			if(!empty($exist) && is_array($exist)){ continue; }
			$clsMem->insert(array('channel_id' => $cid, 'profile_id' => $x, 'role' => 0, 'last_read_message_id' => 0, 'reg_date' => $now));
		}
		chat_json(0, 'Đã thêm thành viên');
	} else if($op === 'remove'){
		if(!chat_group_owner_gate($cid, $profile_id)){ chat_json(1, 'Chỉ chủ nhóm mới xoá được thành viên'); }	// xoá TV GIỮ owner-only
		$mid = (int) Input::post('member_id', 0);
		if($mid <= 0){ chat_json(1, 'Thiếu thành viên'); }
		if($mid === (int) $profile_id){ chat_json(1, 'Không thể tự xoá (dùng Rời nhóm hoặc Chuyển quyền)'); }
		$tar = $clsMem->getByCond("`channel_id`='".$cid."' AND `profile_id`='".$mid."'", "`id`,`role`");
		if(empty($tar) || !is_array($tar)){ chat_json(1, 'Thành viên không tồn tại'); }
		if((int) $tar['role'] === 1){ chat_json(1, 'Không thể xoá chủ nhóm'); }
		$clsMem->deleteByCond("`channel_id`='".$cid."' AND `profile_id`='".$mid."'");
		chat_kick_node($cid, $mid);	// đóng rò: ép client bị xoá rời room ngay
		chat_json(0, 'Đã xoá thành viên');
	}
	chat_json(1, 'Thao tác không hợp lệ');
}
/* AJAX: đổi tên/ảnh nhóm (owner-only). Chỉ set field có đổi. */
function default_group_update(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$ch = chat_access($cid, $profile_id);
	if(empty($ch) || (int) $ch['type'] !== 4){ chat_json(1, 'Không có quyền truy cập nhóm'); }
	if(!chat_group_owner_gate($cid, $profile_id)){ chat_json(1, 'Chỉ chủ nhóm mới thực hiện được'); }
	$set = array();
	$name = trim((string) Input::post('name', ''));
	if($name !== ''){ if(mb_strlen($name) > 120){ $name = mb_substr($name, 0, 120); } $set['name'] = $name; }
	// Ảnh base64 RAW từ $_POST (KHÔNG qua Input — xss_clean phá base64); tái dùng helper P2 (256 + validate getimagesize).
	$rawImg = (isset($_POST['image']) && is_string($_POST['image'])) ? $_POST['image'] : '';
	if($rawImg !== ''){ $img = chat_upload_group_image($rawImg); if($img !== ''){ $set['image'] = $img; } }
	if(empty($set)){ chat_json(1, 'Không có thay đổi'); }
	(new ChatChannel())->updateOne($cid, $set);
	$info = (new ChatChannel())->getByCond("`channel_id`='".$cid."'", "`name`,`image`");
	chat_json(0, 'Đã cập nhật nhóm', array('name' => isset($info['name']) ? $info['name'] : '', 'image' => isset($info['image']) ? $info['image'] : ''));
}
/* AJAX: chuyển quyền chủ nhóm (owner-only) sang to_id (phải là TV). Atomic: mới=1 TRƯỚC, cũ=0 SAU → luôn ≥1 chủ. */
function default_group_transfer(){
	global $profile_id, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$ch = chat_access($cid, $profile_id);
	if(empty($ch) || (int) $ch['type'] !== 4){ chat_json(1, 'Không có quyền truy cập nhóm'); }
	if(!chat_group_owner_gate($cid, $profile_id)){ chat_json(1, 'Chỉ chủ nhóm mới thực hiện được'); }
	$to = (int) Input::post('to_id', 0);
	if($to === (int) $profile_id){ chat_json(1, 'Bạn đã là chủ nhóm'); }
	$clsMem = new ChatMember();
	$tar = $clsMem->getByCond("`channel_id`='".$cid."' AND `profile_id`='".$to."'", "`id`");
	if($to <= 0 || empty($tar) || !is_array($tar)){ chat_json(1, 'Người nhận chưa là thành viên nhóm'); }
	$dbconn->StartTrans();
	$clsMem->updateOne((int) $tar['id'], array('role' => 1));	// mới = chủ TRƯỚC (invariant: không bao giờ 0 chủ)
	$me = $clsMem->getByCond("`channel_id`='".$cid."' AND `profile_id`='".(int) $profile_id."'", "`id`");
	if(!empty($me) && is_array($me)){ $clsMem->updateOne((int) $me['id'], array('role' => 0)); }	// cũ = TV SAU
	$dbconn->CompleteTrans();
	chat_json(0, 'Đã chuyển quyền chủ nhóm');
}
/* AJAX: rời nhóm. TV thường xoá row của mình; CHỦ bị từ chối (phải transfer trước → nhóm không mồ côi). */
function default_group_leave(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$ch = chat_access($cid, $profile_id);
	if(empty($ch) || (int) $ch['type'] !== 4){ chat_json(1, 'Không có quyền truy cập nhóm'); }
	if(chat_group_owner_gate($cid, $profile_id)){ chat_json(1, 'Hãy chuyển quyền chủ nhóm trước khi rời'); }
	(new ChatMember())->deleteByCond("`channel_id`='".$cid."' AND `profile_id`='".(int) $profile_id."'");
	chat_json(0, 'Đã rời nhóm');
}
/* AJAX: giải tán nhóm (owner-only). Soft-trash channel (is_trash=1) → mọi chat_access sau = false; tin giữ trong DB. */
function default_group_disband(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$ch = chat_access($cid, $profile_id);
	if(empty($ch) || (int) $ch['type'] !== 4){ chat_json(1, 'Không có quyền truy cập nhóm'); }
	if(!chat_group_owner_gate($cid, $profile_id)){ chat_json(1, 'Chỉ chủ nhóm mới thực hiện được'); }
	(new ChatChannel())->updateOne($cid, array('is_trash' => 1));
	chat_kick_node($cid, 0);	// kick cả room (profile_id=0)
	chat_json(0, 'Đã giải tán nhóm');
}
/* AJAX: thả/đổi/gỡ cảm xúc 1 tin (toggle theo UNIQUE message_id+profile_id). emoji = CODE trong 6 mã. */
function default_react(){
	global $profile_id, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$mid = (int) Input::post('message_id', 0);
	$emoji = trim((string) Input::post('emoji', ''));	// CODE ASCII (Input::post xss_clean an toàn với code)
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$codes = array('love', 'like', 'haha', 'wow', 'sad', 'angry');
	if(!in_array($emoji, $codes, true)){ chat_json(1, 'Cảm xúc không hợp lệ'); }
	// Tin PHẢI thuộc kênh này (chống thả vào tin kênh khác → lộ/ghi chéo phòng — bài học channel-scope).
	$msg = (new ChatMessage())->getByCond("`message_id`='".$mid."' AND `channel_id`='".$cid."' AND `is_trash`=0 AND `type`<>'"._CHAT_MSG_RECALLED."'", "`message_id`");
	if(empty($msg) || !is_array($msg)){ chat_json(1, 'Tin không tồn tại'); }
	// Toggle: chọn lại cái cũ → gỡ (hard delete); chọn cái khác → đổi; chưa có → thêm.
	$clsR = new ChatReaction();
	$cur = $clsR->getByCond("`message_id`='".$mid."' AND `profile_id`='".(int) $profile_id."'", "`id`,`emoji`");
	$now = time();
	if(!empty($cur) && is_array($cur)){
		if($cur['emoji'] === $emoji){
			$dbconn->Execute("DELETE FROM `".DB_PREFIX."chat_reaction` WHERE `id`='".(int) $cur['id']."'");
		} else {
			$clsR->updateOne((int) $cur['id'], array('emoji' => $emoji, 'reg_date' => $now));
		}
	} else {
		$clsR->insert(array('message_id' => $mid, 'channel_id' => $cid, 'profile_id' => (int) $profile_id, 'emoji' => $emoji, 'reg_date' => $now));
	}
	$sum = chat_react_summary($mid, (int) $profile_id);	// {counts,mine,total} cho người thả (CÓ mine)
	// Broadcast KHÔNG kèm mine (mine khác nhau mỗi người nhận) — tái dùng /chat-message, phân biệt bằng key 'reaction'.
	chat_push_node($cid, array('reaction' => array('message_id' => $mid, 'channel_id' => $cid, 'counts' => $sum['counts'], 'total' => $sum['total'])));
	echo json_encode(array('error' => 0, 'reactions' => $sum), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: thu hồi tin (gỡ với MỌI người) — chỉ người gửi, trong 24h kể từ lúc gửi. Đánh dấu recalled + xoá nội dung/ảnh/cảm xúc. */
function default_recall(){
	global $profile_id, $dbconn;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$mid = (int) Input::post('message_id', 0);
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$clsMsg = new ChatMessage();
	$msg = $clsMsg->getByCond("`message_id`='".$mid."' AND `channel_id`='".$cid."' AND `is_trash`=0", "`message_id`,`profile_id`,`type`,`reg_date`");
	if(empty($msg) || !is_array($msg)){ chat_json(1, 'Tin không tồn tại'); }
	if((int) $msg['profile_id'] !== (int) $profile_id){ chat_json(1, 'Chỉ người gửi mới thu hồi được'); }	// chỉ người gửi
	if((int) $msg['type'] === _CHAT_MSG_RECALLED){ echo json_encode(array('error' => 0, 'message_id' => $mid), JSON_UNESCAPED_UNICODE); die(); }	// đã recall → idempotent
	if(time() - (int) $msg['reg_date'] > _CHAT_RECALL_WINDOW){ chat_json(1, 'Quá 24h, không thể thu hồi'); }	// CHỐT CỬA server-side (chân lý cuối)
	$clsMsg->updateOne($mid, array('type' => _CHAT_MSG_RECALLED, 'content' => '', 'image' => '', 'upd_date' => time()));	// xoá nội dung + ảnh
	$dbconn->Execute("DELETE FROM `".DB_PREFIX."chat_reaction` WHERE `message_id`='".$mid."'");	// Zalo: thu hồi xoá luôn cảm xúc
	// Nếu tin đang được GHIM → bỏ ghim luôn (giải phóng slot + xoá khỏi banner; tránh banner treo "đã thu hồi").
	$clsCh = new ChatChannel();
	$chRow = $clsCh->getOne($cid, 'more_information');
	$pinInfo = json_decode((!empty($chRow) && isset($chRow['more_information'])) ? $chRow['more_information'] : '', true);
	$pinIds = chat_pinned_ids($pinInfo);
	if(in_array($mid, $pinIds, true)){
		if(!is_array($pinInfo)){ $pinInfo = array(); }
		$keep = array(); foreach($pinIds as $x){ if((int) $x !== $mid){ $keep[] = (int) $x; } }
		unset($pinInfo['pinned_message_id']);
		if(!empty($keep)){ $pinInfo['pinned_message_ids'] = array_values($keep); } else { unset($pinInfo['pinned_message_ids']); }
		$clsCh->updateOne($cid, array('more_information' => json_encode($pinInfo, JSON_UNESCAPED_UNICODE), 'upd_date' => time()));
		chat_push_node($cid, array('pin' => array('channel_id' => $cid, 'pinned' => chat_channel_pinned($cid, (new Profile())->getProfileCached('all')))));
	}
	chat_push_node($cid, array('recall' => array('message_id' => $mid, 'channel_id' => $cid)));	// realtime: reuse /chat-message key 'recall'
	echo json_encode(array('error' => 0, 'message_id' => $mid), JSON_UNESCAPED_UNICODE);
	die();
}
/* AJAX: ghim/bỏ ghim 1 hội thoại (per-user) — lưu is_pinned ở chat_member; sort hội thoại ghim lên đầu. */
function default_pin_channel(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$pin = ((int) Input::post('pin', 1)) ? 1 : 0;
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$clsMem = new ChatMember();
	$mem = $clsMem->getByCond("`channel_id`='".$cid."' AND `profile_id`='".(int) $profile_id."'", "`id`");
	if(!empty($mem) && is_array($mem)){
		$clsMem->updateOne((int) $mem['id'], array('is_pinned' => $pin));
	} else {
		$clsMem->insert(array('channel_id' => $cid, 'profile_id' => (int) $profile_id, 'role' => 0, 'last_read_message_id' => 0, 'last_read_at' => 0, 'is_muted' => 0, 'is_pinned' => $pin, 'reg_date' => time()));
	}
	chat_json(0, '', array('pinned' => $pin));
}
/* AJAX: tắt/bật thông báo 1 hội thoại (per-user) — lưu is_muted ở chat_member; client chặn chuông+notify kênh muted. */
function default_mute_channel(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$mute = ((int) Input::post('mute', 1)) ? 1 : 0;
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$clsMem = new ChatMember();
	$mem = $clsMem->getByCond("`channel_id`='".$cid."' AND `profile_id`='".(int) $profile_id."'", "`id`");
	if(!empty($mem) && is_array($mem)){
		$clsMem->updateOne((int) $mem['id'], array('is_muted' => $mute));
	} else {
		$clsMem->insert(array('channel_id' => $cid, 'profile_id' => (int) $profile_id, 'role' => 0, 'last_read_message_id' => 0, 'last_read_at' => 0, 'is_muted' => $mute, 'is_pinned' => 0, 'reg_date' => time()));
	}
	chat_json(0, '', array('muted' => $mute));
}
/* AJAX: ghim/bỏ ghim 1 TIN trong kênh (any member). Lưu pinned_message_id ở channel.more_information; banner đầu thread. */
/* Đọc mảng id tin ĐANG GHIM từ more_information (back-compat khoá scalar pinned_message_id cũ). */
function chat_pinned_ids($info){
	if(!is_array($info)){ return array(); }
	if(isset($info['pinned_message_ids']) && is_array($info['pinned_message_ids'])){
		$out = array(); foreach($info['pinned_message_ids'] as $x){ $x = (int) $x; if($x > 0){ $out[] = $x; } } return $out;
	}
	if(isset($info['pinned_message_id']) && (int) $info['pinned_message_id'] > 0){ return array((int) $info['pinned_message_id']); }
	return array();
}
/* AJAX: ghim/bỏ ghim 1 TIN (any member, tối đa _CHAT_PIN_MAX tin/kênh — như Zalo). pin=1 thêm / pin=0 gỡ. */
function default_pin_message(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$mid = (int) Input::post('message_id', 0);
	$pin = ((int) Input::post('pin', 1)) ? 1 : 0;	// 1 = ghim, 0 = bỏ ghim tin này
	if($mid <= 0){ chat_json(1, 'Thiếu tin'); }
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$clsCh = new ChatChannel();
	$ch = $clsCh->getOne($cid, 'more_information');
	if(empty($ch) || !is_array($ch)){ chat_json(1, 'Kênh không tồn tại'); }
	$info = json_decode(isset($ch['more_information']) ? $ch['more_information'] : '', true);
	if(!is_array($info)){ $info = array(); }
	$ids = chat_pinned_ids($info);
	if($pin){
		$msg = (new ChatMessage())->getByCond("`message_id`='".$mid."' AND `channel_id`='".$cid."' AND `is_trash`=0", "`message_id`,`type`");
		if(empty($msg) || !is_array($msg)){ chat_json(1, 'Tin không tồn tại'); }
		if((int) $msg['type'] === _CHAT_MSG_RECALLED){ chat_json(1, 'Tin đã thu hồi, không ghim được'); }
		if(!in_array($mid, $ids, true)){
			if(count($ids) >= _CHAT_PIN_MAX){ chat_json(1, 'Đã ghim tối đa '._CHAT_PIN_MAX.' tin. Bỏ bớt rồi ghim tin mới.'); }
			$ids[] = $mid;	// thêm cuối (ghim mới nhất nằm cuối mảng)
		}
	} else {
		$keep = array(); foreach($ids as $x){ if((int) $x !== $mid){ $keep[] = (int) $x; } } $ids = $keep;
	}
	unset($info['pinned_message_id']);	// dọn khoá scalar cũ
	if(!empty($ids)){ $info['pinned_message_ids'] = array_values($ids); } else { unset($info['pinned_message_ids']); }
	$clsCh->updateOne($cid, array('more_information' => json_encode($info, JSON_UNESCAPED_UNICODE), 'upd_date' => time()));
	$pmap = (new Profile())->getProfileCached('all');
	$list = chat_channel_pinned($cid, $pmap);	// mảng snippet (mới ghim trước)
	chat_push_node($cid, array('pin' => array('channel_id' => $cid, 'pinned' => $list)));	// realtime reuse /chat-message key 'pin'
	echo json_encode(array('error' => 0, 'pinned' => $list), JSON_UNESCAPED_UNICODE);
	die();
}
/* Danh sách tin đang ghim của kênh → MẢNG snippet {message_id,name,preview} (mới ghim trước), bỏ tin đã xoá. */
function chat_channel_pinned($cid, $pmap){
	$ch = (new ChatChannel())->getOne((int) $cid, 'more_information');
	if(empty($ch) || !is_array($ch)){ return array(); }
	$info = json_decode(isset($ch['more_information']) ? $ch['more_information'] : '', true);
	$ids = chat_pinned_ids($info);
	if(empty($ids)){ return array(); }
	$ids = array_reverse($ids);	// thêm-cuối → đảo cho tin ghim mới nhất hiện đầu
	$out = array();
	foreach($ids as $mid){
		$snip = chat_reply_snippet((int) $mid, $pmap, (int) $cid);	// null nếu tin đã xoá → bỏ
		if($snip !== null){ $out[] = $snip; }
	}
	return $out;
}
/* Bỏ token mention @[Tên](pid) → @Tên cho preview (list hội thoại + reply snippet). */
function chat_strip_mentions($text){
	$text = (string) $text;
	if(strpos($text, '@[') === false){ return $text; }
	return preg_replace('/@\[([^\]]+)\]\(\d+\)/', '@$1', $text);
}
/* Ai đã ĐỌC tin mới nhất của kênh (đã xem) → list {profile_id,name,avatar}, trừ người xem. Chỉ ý nghĩa với nhóm. */
function chat_seen_list($cid, $viewer, $pmap){
	global $dbconn;
	$cid = (int) $cid;
	$maxRow = $dbconn->GetRow("SELECT MAX(`message_id`) AS m FROM `".DB_PREFIX."chat_message` WHERE `channel_id`='".$cid."' AND `is_trash`=0");
	$maxMid = (!empty($maxRow) && isset($maxRow['m'])) ? (int) $maxRow['m'] : 0;
	if($maxMid <= 0){ return array(); }
	$rows = (new ChatMember())->getAll("`channel_id`='".$cid."' AND `last_read_message_id`>='".$maxMid."' AND `profile_id`<>'".(int) $viewer."'", "`profile_id`");
	if(empty($rows)){ return array(); }
	$cls = new Profile();
	$out = array();
	foreach($rows as $r){
		$pid = (int) $r['profile_id'];
		$p = isset($pmap[$pid]) ? $pmap[$pid] : array();
		$out[] = array('profile_id' => $pid, 'name' => isset($p['full_name']) ? $p['full_name'] : '', 'avatar' => $cls->getAvatar($pid, $p));
	}
	return $out;
}
/* AJAX: ai đã thả cảm xúc 1 tin (channel-scope) → list {profile_id,name,avatar,emoji} theo thứ tự thả. */
function default_reactors(){
	global $profile_id, $clsProfile;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$mid = (int) Input::post('message_id', 0);
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$msg = (new ChatMessage())->getByCond("`message_id`='".$mid."' AND `channel_id`='".$cid."' AND `is_trash`=0", "`message_id`");
	if(empty($msg) || !is_array($msg)){ chat_json(1, 'Tin không tồn tại'); }
	$rows = (new ChatReaction())->getAll("`message_id`='".$mid."' AND `channel_id`='".$cid."' ORDER BY `reg_date` ASC", "`profile_id`,`emoji`");
	$cls = isset($clsProfile) ? $clsProfile : new Profile();
	$pmap = $cls->getProfileCached('all');
	$list = array();
	if(!empty($rows)){
		foreach($rows as $r){
			$pid = (int) $r['profile_id'];
			$p = isset($pmap[$pid]) ? $pmap[$pid] : array();
			$list[] = array('profile_id' => $pid, 'name' => isset($p['full_name']) ? $p['full_name'] : '', 'avatar' => $cls->getAvatar($pid, $p), 'emoji' => $r['emoji']);
		}
	}
	chat_json(0, '', array('list' => $list));
}
/* ---------------- helpers ---------------- */
/* Gom cảm xúc 1 tin: {counts:{code:n}, mine:code|null, total}. */
function chat_react_summary($mid, $viewer){
	$rows = (new ChatReaction())->getAll("`message_id`='".(int) $mid."'", "`profile_id`,`emoji`");
	$counts = array(); $mine = null; $total = 0;
	if(!empty($rows)){
		foreach($rows as $r){
			$e = $r['emoji'];
			$counts[$e] = isset($counts[$e]) ? $counts[$e] + 1 : 1;
			$total++;
			if((int) $r['profile_id'] === (int) $viewer){ $mine = $e; }
		}
	}
	return array('counts' => $counts, 'mine' => $mine, 'total' => $total);
}
/* Map message_id => {counts,mine,total} cho danh sách tin — 1 query IN(...) chống N+1. */
function chat_react_map($mids, $viewer, $cid = 0){
	$ids = array();
	foreach((array) $mids as $m){ $m = (int) $m; if($m > 0){ $ids[$m] = $m; } }
	if(empty($ids)){ return array(); }
	$out = array();
	$cond = "`message_id` IN (".implode(',', $ids).")";
	if((int) $cid > 0){ $cond .= " AND `channel_id`='".(int) $cid."'"; }	// channel-scope: react_ids kênh khác → 0 dòng (chống lộ tally chéo)
	$rows = (new ChatReaction())->getAll($cond, "`message_id`,`profile_id`,`emoji`");
	if(!empty($rows)){
		foreach($rows as $r){
			$mid = (int) $r['message_id']; $e = $r['emoji'];
			if(!isset($out[$mid])){ $out[$mid] = array('counts' => array(), 'mine' => null, 'total' => 0); }
			$out[$mid]['counts'][$e] = isset($out[$mid]['counts'][$e]) ? $out[$mid]['counts'][$e] + 1 : 1;
			$out[$mid]['total']++;
			if((int) $r['profile_id'] === (int) $viewer){ $out[$mid]['mine'] = $e; }
		}
	}
	return $out;
}
/* Định dạng 1 kênh ra cho client (kèm số chưa đọc + token ký để subscribe realtime an toàn). */
/* Định dạng 1 kênh ra client. $meta = {role,unread,last} đã batched; rỗng → fallback chat_unread (giữ tương thích caller cũ). */
function chat_channel_out($ch, $viewer, $meta = array()){
	$cid = (int) $ch['channel_id'];
	$role = isset($meta['role']) ? (int) $meta['role'] : (isset($ch['role']) ? (int) $ch['role'] : 0);
	$unread = isset($meta['unread']) ? (int) $meta['unread'] : chat_unread($cid, $viewer);
	$last = isset($meta['last']) ? $meta['last'] : null;	// {type,content,reg_date} hoặc null
	$out = array(
		'channel_id' => $cid,
		'name'       => $ch['name'],
		'type'       => (int) $ch['type'],
		'image'      => isset($ch['image']) ? $ch['image'] : '',
		'is_owner'   => ($role == 1) ? 1 : 0,
		'role'       => $role,
		'unread'     => $unread,
		'pinned'     => isset($meta['pinned']) ? (int) $meta['pinned'] : 0,	// pin hội thoại (per-user) → sort lên đầu
		'muted'      => isset($meta['muted']) ? (int) $meta['muted'] : 0,	// tắt thông báo (per-user)
		// HMAC(tenant|channel_id|profile_id) — Node verify trước khi cho join room kênh; chỉ cấp cho kênh user được phép.
		// Tenant nằm TRONG chữ ký: relay dùng chung nhiều website mà channel_id là ID trong DB riêng từng
		// khách → chắc chắn trùng số. Không ký kèm tenant thì token khách này mở được kênh khách kia.
		'token'      => substr(hash_hmac('sha256', _TENANT_ID.'|'.$cid.'|'.(int) $viewer, _CHAT_SOCKET_SECRET), 0, 32),
	);
	if($last !== null){
		$out['last_msg'] = ((int) $last['type'] === _CHAT_MSG_RECALLED) ? 'Tin đã thu hồi' : (((int) $last['type'] === 2) ? '[Hình ảnh]' : mb_substr(chat_strip_mentions($last['content']), 0, 80));
		$out['last_at']  = date('H:i', (int) $last['reg_date']);
		$out['last_ts']  = (int) $last['reg_date'];	// client sort hội thoại (phase UI)
		$sid = isset($last['sender_id']) ? (int) $last['sender_id'] : 0;
		$out['last_sender'] = ($sid > 0 && $sid === (int) $viewer) ? 'Bạn' : (isset($last['sender']) ? $last['sender'] : '');	// "ai nói" preview
	} else {
		$out['last_msg'] = ''; $out['last_at'] = ''; $out['last_ts'] = 0; $out['last_sender'] = '';
	}
	return $out;
}
/* Nhóm (type4) mà viewer là thành viên → map channel_id => {ch, role}. 2 query (member + channel IN), lọc type=4 để không lẫn kênh hệ thống. */
function chat_user_groups($viewer){
	$clsMem = new ChatMember();
	$rows = $clsMem->getAll("`profile_id`='".(int) $viewer."'", "`channel_id`,`role`");
	$roleByCid = array(); $cids = array();
	if(!empty($rows)){
		foreach($rows as $r){ $cid = (int) $r['channel_id']; $roleByCid[$cid] = (int) $r['role']; $cids[$cid] = $cid; }
	}
	if(empty($cids)){ return array(); }
	$clsCh = new ChatChannel();
	$chs = $clsCh->getAll("`channel_id` IN (".implode(',', $cids).") AND `type`=4 AND `is_active`=1 AND `is_trash`=0 ORDER BY `channel_id` DESC", "`channel_id`,`name`,`type`,`image`");
	$out = array();
	if(!empty($chs)){
		foreach($chs as $ch){ $cid = (int) $ch['channel_id']; $out[$cid] = array('ch' => $ch, 'role' => isset($roleByCid[$cid]) ? $roleByCid[$cid] : 0); }
	}
	return $out;
}
/* Gom last-msg + unread cho danh sách kênh trong ~5 query cố định (chống N+1). Trả map cid => {last,unread}. */
/* Tên ngắn (tên gọi = từ cuối họ-tên VN) cho preview hội thoại: "Lương Tiến Dũng" → "Dũng". */
function chat_short_name($full){
	$full = trim((string) $full);
	if($full === ''){ return ''; }
	$pos = mb_strrpos($full, ' ');
	return ($pos === false) ? $full : mb_substr($full, $pos + 1);
}
function chat_channels_meta($cids, $viewer){
	$ids = array();
	foreach((array) $cids as $c){ $c = (int) $c; if($c > 0){ $ids[$c] = $c; } }
	if(empty($ids)){ return array(); }
	$in = implode(',', $ids);
	$meta = array();
	foreach($ids as $c){ $meta[$c] = array('last' => null, 'unread' => 0, 'pinned' => 0, 'muted' => 0); }
	global $dbconn;
	// (a) last-msg mỗi kênh: MAX(message_id) per channel → join content (2 query, không phụ thuộc số kênh).
	$maxRows = $dbconn->GetAll("SELECT `channel_id`, MAX(`message_id`) AS mid FROM `".DB_PREFIX."chat_message` WHERE `channel_id` IN (".$in.") AND `is_trash`=0 GROUP BY `channel_id`");
	$midList = array();
	if(!empty($maxRows)){
		foreach($maxRows as $r){ $mid = (int) $r['mid']; if($mid > 0){ $midList[] = $mid; } }
	}
	if(!empty($midList)){
		$lastRows = $dbconn->GetAll("SELECT `message_id`,`channel_id`,`type`,`content`,`reg_date`,`profile_id` FROM `".DB_PREFIX."chat_message` WHERE `message_id` IN (".implode(',', $midList).")");
		if(!empty($lastRows)){
			$pmap = (new Profile())->getProfileCached('all');	// 1 lần (Redis 1h) → tên người nói cuối cho preview
			foreach($lastRows as $r){
				$cid = (int) $r['channel_id']; $sid = (int) $r['profile_id'];
				$sname = (isset($pmap[$sid]['full_name']) && $pmap[$sid]['full_name'] !== '') ? chat_short_name($pmap[$sid]['full_name']) : '';
				$meta[$cid]['last'] = array('type' => (int) $r['type'], 'content' => $r['content'], 'reg_date' => (int) $r['reg_date'], 'sender_id' => $sid, 'sender' => $sname);
			}
		}
	}
	// (b) unread: last_read của viewer cho các kênh (1 query) → đếm tin mới hơn per-channel, bỏ tin của mình (1 query OR-ghép).
	$clsMem = new ChatMember();
	$lrRows = $clsMem->getAll("`profile_id`='".(int) $viewer."' AND `channel_id` IN (".$in.")", "`channel_id`,`last_read_message_id`,`is_pinned`,`is_muted`");
	$lrByCid = array();
	if(!empty($lrRows)){
		foreach($lrRows as $r){ $lrByCid[(int) $r['channel_id']] = (int) $r['last_read_message_id']; $meta[(int) $r['channel_id']]['pinned'] = (int) $r['is_pinned']; $meta[(int) $r['channel_id']]['muted'] = (int) $r['is_muted']; }
	}
	$ors = array();
	foreach($ids as $c){ $lr = isset($lrByCid[$c]) ? $lrByCid[$c] : 0; $ors[] = "(`channel_id`='".$c."' AND `message_id`>'".$lr."')"; }
	$unreadRows = $dbconn->GetAll("SELECT `channel_id`, COUNT(*) AS c FROM `".DB_PREFIX."chat_message` WHERE (".implode(' OR ', $ors).") AND `is_trash`=0 AND `type`<>'"._CHAT_MSG_RECALLED."' AND `profile_id`<>'".(int) $viewer."' GROUP BY `channel_id`");
	if(!empty($unreadRows)){
		foreach($unreadRows as $r){ $meta[(int) $r['channel_id']]['unread'] = (int) $r['c']; }
	}
	return $meta;
}
/* Tìm-hoặc-tạo kênh Toàn công ty (type1). Phải tự seed như kênh phòng ban: mỗi bản nhân bản cho
   khách mới khởi đầu với bảng kênh TRỐNG, không ai nhớ INSERT tay thì cả công ty mất chỗ chat
   chung mà chẳng có lỗi nào báo. Admin tắt kênh (is_active=0) → trả false để ẩn, KHÔNG tạo bản
   thứ hai đè lên ý admin. Tie-break channel_id: lỡ 2 người mở chat cùng lúc tạo trùng thì mọi
   người vẫn hội tụ về cùng một kênh, không tách đôi cuộc trò chuyện. */
function chat_company_channel(){
	global $dbconn;
	$clsCh = new ChatChannel();
	$ch = $clsCh->getByCond("`type`=1 AND `is_trash`=0 ORDER BY `order_no` ASC, `channel_id` ASC", "`channel_id`,`name`,`type`,`image`,`is_active`");
	if(!empty($ch) && is_array($ch)){
		return ((int) $ch['is_active'] === 1) ? $ch : false;
	}
	$name = 'Toàn công ty';
	$clsCh->insert(array(
		'name'               => $name,
		'type'               => 1,
		'list_department_id' => '',
		'icon'               => 'globe',
		'is_system'          => 0,
		'is_active'          => 1,
		'order_no'           => 1,
		'reg_date'           => time(),
	));
	$cid = (int) $dbconn->insert_Id();
	return array('channel_id' => $cid, 'name' => $name, 'type' => 1, 'image' => '');
}
/* Tìm-hoặc-tạo kênh phòng ban (type2) theo department_id. */
function chat_dept_channel($dept){
	global $dbconn;
	$clsCh = new ChatChannel();
	$tag = '|'.(int) $dept.'|';
	$ch = $clsCh->getByCond("`type`=2 AND `is_trash`=0 AND `list_department_id`='".addslashes($tag)."'", "`channel_id`,`name`,`type`,`list_department_id`,`image`");
	if(!empty($ch) && is_array($ch)){ return $ch; }
	$title = (new Setting())->getTitle((int) $dept);
	$name = !empty($title) ? $title : ('Phòng ban '.(int) $dept);
	$clsCh->insert(array(
		'name'               => $name,
		'type'               => 2,
		'list_department_id' => $tag,
		'icon'               => 'users',
		'is_system'          => 0,
		'is_active'          => 1,
		'order_no'           => 5,
		'reg_date'           => time(),
	));
	$cid = (int) $dbconn->insert_Id();
	return array('channel_id' => $cid, 'name' => $name, 'type' => 2, 'list_department_id' => $tag, 'image' => '');
}
/* Gate quyền truy cập kênh → trả row kênh hoặc false. type1=mọi NV; type2=thành viên phòng; type4=member nhóm. */
function chat_access($cid, $profile_id){
	if((int) $cid <= 0){ return false; }
	$clsCh = new ChatChannel();
	$ch = $clsCh->getByCond("`channel_id`='".(int) $cid."' AND `is_trash`=0", "`channel_id`,`type`,`list_department_id`,`is_active`,`image`");
	if(empty($ch) || !is_array($ch)){ return false; }
	$type = (int) $ch['type'];
	if($type == 1){ return $ch; }
	if($type == 2){
		// Leaf-only: chỉ NV thuộc ĐÚNG phòng của kênh (khớp default_channels). Tránh phòng con đọc kênh phòng cha.
		global $clsProfile;
		$cls = isset($clsProfile) ? $clsProfile : new Profile();
		$me = $cls->getOne((int) $profile_id, "department_id");
		$myDept = (int) (isset($me['department_id']) ? $me['department_id'] : 0);
		$D = (int) trim($ch['list_department_id'], '|');
		if($D > 0 && $D == $myDept){ return $ch; }
		return false;
	}
	if($type == 4){
		// Nhóm: membership qua chat_member là chân-lý (kênh tồn tại KHÔNG đủ → chống forge channel_id).
		if((int) $ch['is_active'] !== 1){ return false; }	// đồng bộ với chat_user_groups (list lọc is_active=1) → gate==list
		$clsMem = new ChatMember();
		$mem = $clsMem->getByCond("`channel_id`='".(int) $cid."' AND `profile_id`='".(int) $profile_id."'", "`role`");
		if(!empty($mem) && is_array($mem)){
			$ch['role'] = (int) $mem['role'];	// kèm role để caller suy is_owner
			return $ch;
		}
		return false;
	}
	return false;
}
/* Số tin chưa đọc của 1 kênh (message_id > last_read, không tính tin của chính mình). */
function chat_unread($cid, $viewer){
	$clsMem = new ChatMember();
	$mem = $clsMem->getByCond("`channel_id`='".(int) $cid."' AND `profile_id`='".(int) $viewer."'", "`last_read_message_id`");
	$lastRead = (!empty($mem) && is_array($mem)) ? (int) $mem['last_read_message_id'] : 0;
	$clsMsg = new ChatMessage();
	return (int) $clsMsg->countItem("`channel_id`='".(int) $cid."' AND `message_id`>'".$lastRead."' AND `profile_id`<>'".(int) $viewer."' AND `is_trash`=0 AND `type`<>'"._CHAT_MSG_RECALLED."'");
}
/* Mốc đã đọc của NV trong kênh (message_id). 0 nếu chưa có. */
function chat_last_read($cid, $viewer){
	$clsMem = new ChatMember();
	$mem = $clsMem->getByCond("`channel_id`='".(int) $cid."' AND `profile_id`='".(int) $viewer."'", "`last_read_message_id`");
	return (!empty($mem) && is_array($mem)) ? (int) $mem['last_read_message_id'] : 0;
}
/* Upsert mốc đã đọc của NV trong kênh. */
function chat_mark_read($cid, $profile_id, $lastId){
	$clsMem = new ChatMember();
	$mem = $clsMem->getByCond("`channel_id`='".(int) $cid."' AND `profile_id`='".(int) $profile_id."'", "`id`,`last_read_message_id`");
	$now = time();
	if(!empty($mem) && is_array($mem)){
		if((int) $lastId > (int) $mem['last_read_message_id']){
			$clsMem->updateOne((int) $mem['id'], array('last_read_message_id' => (int) $lastId, 'last_read_at' => $now));
		}
	} else {
		$clsMem->insert(array('channel_id' => (int) $cid, 'profile_id' => (int) $profile_id, 'last_read_message_id' => (int) $lastId, 'last_read_at' => $now, 'reg_date' => $now));
	}
}
/* Định dạng 1 tin nhắn ra cho client (kèm tên/avatar người gửi). images = mảng path (album); ảnh đơn cũ gói thành mảng 1. */
function chat_msg_out($r, $pmap, $viewer, $cid = 0, $reactMap = array()){
	global $clsProfile;
	$pid = (int) $r['profile_id'];
	$p = isset($pmap[$pid]) ? $pmap[$pid] : array();
	$cls = isset($clsProfile) ? $clsProfile : new Profile();
	$rawImg = isset($r['image']) ? $r['image'] : '';
	$dec = ($rawImg !== '') ? json_decode($rawImg, true) : null;
	$images = is_array($dec) ? array_values($dec) : (($rawImg !== '') ? array($rawImg) : array());	// JSON mảng → album; path đơn cũ → mảng 1
	$ts = (int) $r['reg_date'];
	$day = ($ts >= strtotime('today')) ? 'Hôm nay' : (($ts >= strtotime('yesterday')) ? 'Hôm qua' : date('d/m/Y', $ts));	// nhãn divider ngày
	return array(
		'message_id' => (int) $r['message_id'],
		'profile_id' => $pid,
		'name'       => isset($p['full_name']) ? $p['full_name'] : '',
		'avatar'     => $cls->getAvatar($pid, $p),
		'type'       => (int) $r['type'],
		'content'    => isset($r['content']) ? $r['content'] : '',
		'image'      => !empty($images) ? $images[0] : '',	// back-compat: ảnh đầu
		'images'     => $images,
		'time'       => date('H:i', $ts),
		'ts'         => $ts,	// unix gửi → client check cửa 24h ẩn/hiện nút Thu hồi (server vẫn chân lý)
		'recalled'   => ((int) $r['type'] === _CHAT_MSG_RECALLED) ? 1 : 0,
		'day'        => $day,
		'reply_to'   => chat_reply_snippet(isset($r['reply_to_id']) ? $r['reply_to_id'] : 0, $pmap, $cid),	// trích dẫn tin gốc (quote) hoặc null
		'reactions'  => isset($reactMap[(int) $r['message_id']]) ? $reactMap[(int) $r['message_id']] : array('counts' => array(), 'mine' => null, 'total' => 0),
		'is_me'      => ($pid === (int) $viewer) ? 1 : 0,
	);
}
/* Snippet tin gốc cho quote: {message_id,name,preview} hoặc null.
   PHẢI cùng channel (chống reply_to_id trỏ tin kênh khác → lộ tên/nội dung liên phòng). Cache trong request chống N+1. */
function chat_reply_snippet($replyToId, $pmap, $cid){
	$replyToId = (int) $replyToId;
	$cid = (int) $cid;
	if($replyToId <= 0 || $cid <= 0){ return null; }
	static $cache = array();
	$key = $cid.':'.$replyToId;
	if(array_key_exists($key, $cache)){ return $cache[$key]; }
	$r = (new ChatMessage())->getByCond("`message_id`='".$replyToId."' AND `channel_id`='".$cid."' AND `is_trash`=0", "`profile_id`,`type`,`content`,`image`");
	if(empty($r) || !is_array($r)){ $cache[$key] = null; return null; }
	$pid = (int) $r['profile_id'];
	$p = isset($pmap[$pid]) ? $pmap[$pid] : array();
	$preview = ((int) $r['type'] === _CHAT_MSG_RECALLED) ? 'Tin nhắn đã thu hồi' : (((int) $r['type'] === 2) ? '[Hình ảnh]' : mb_substr(chat_strip_mentions(isset($r['content']) ? $r['content'] : ''), 0, 80));
	$out = array('message_id' => $replyToId, 'name' => (isset($p['full_name']) ? $p['full_name'] : ''), 'preview' => $preview);
	$cache[$key] = $out;
	return $out;
}
/* Trả JSON rồi dừng. */
function chat_json($error, $message, $extra = array()){
	$out = array('error' => $error ? 1 : 0, 'message' => $message);
	if(!empty($extra)){ $out = array_merge($out, $extra); }
	echo json_encode($out, JSON_UNESCAPED_UNICODE);
	die();
}
/* Redis revision per kênh: bump khi có thay đổi (tin/cảm xúc/thu hồi/ghim). Best-effort — Redis rớt thì bỏ qua. */
function chat_bump_rev($cid){
	$cid = (int) $cid; if($cid <= 0){ return; }
	try { (new Cache())->put('chat:rev:'.$cid, (string) microtime(true), 7*24*60*60); } catch(\Exception $e) {}
}
/* Redis seenrev per kênh: bump khi có người ĐỌC. Tách khỏi rev nội-dung để "đã xem" chỉ làm mới thread đang mở, không kéo cả list. */
function chat_bump_seenrev($cid){
	$cid = (int) $cid; if($cid <= 0){ return; }
	try { (new Cache())->put('chat:seenrev:'.$cid, (string) microtime(true), 7*24*60*60); } catch(\Exception $e) {}
}
/* Typing fallback qua Redis (chạy KHÔNG cần Node): 1 key/kênh = map {pid:{n:tên,e:hết_hạn}}; dọn hết hạn + ghi mình. TTL ngắn. */
function chat_typing_set($cid, $pid, $name){
	$cid = (int) $cid; $pid = (int) $pid; if($cid <= 0 || $pid <= 0){ return; }
	try {
		$cache = new Cache(); $key = 'chat:typing:'.$cid;
		$map = $cache->get($key, array(), false);	// Cache::get tự json_decode JSON → trả thẳng MẢNG map (KHÔNG phải string)
		if(!is_array($map)){ $map = array(); }
		$now = time();
		foreach($map as $k => $v){ if(!isset($v['e']) || (int) $v['e'] < $now){ unset($map[$k]); } }
		$map[$pid] = array('n' => $name, 'e' => $now + 5);
		$cache->put($key, json_encode($map, JSON_UNESCAPED_UNICODE), 15);
	} catch(\Exception $e) {}
}
/* Tên những người KHÁC đang gõ trong kênh (đã dọn hết hạn). */
function chat_typing_others($cid, $viewer){
	$cid = (int) $cid; $viewer = (int) $viewer; $out = array();
	if($cid <= 0){ return $out; }
	try {
		$map = (new Cache())->get('chat:typing:'.$cid, array(), false);	// Cache::get đã json_decode → MẢNG sẵn
		if(!is_array($map) || empty($map)){ return $out; }
		$now = time();
		foreach($map as $pid => $v){
			if((int) $pid === $viewer){ continue; }
			if(!isset($v['e']) || (int) $v['e'] < $now){ continue; }
			$nm = isset($v['n']) ? $v['n'] : '';
			if($nm !== ''){ $out[] = $nm; }
		}
	} catch(\Exception $e) {}
	return $out;
}
/* AJAX poll-nhẹ: client gửi cids đang theo dõi → trả rev hiện tại từ REDIS (KHÔNG đụng DB). Client diff → chỉ fetch kênh đổi. */
function default_ping(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cids = Input::post('cids', array());
	$open = (int) Input::post('open', 0);
	$out = array(); $sout = array();
	if(is_array($cids)){
		$cache = new Cache();
		foreach(array_slice($cids, 0, 100) as $cid){
			$cid = (int) $cid; if($cid <= 0){ continue; }
			$v = $cache->get('chat:rev:'.$cid, '', false); $out[$cid] = is_array($v) ? '' : (string) $v;
			$sv = $cache->get('chat:seenrev:'.$cid, '', false); $sout[$cid] = is_array($sv) ? '' : (string) $sv;
		}
	}
	$typing = array();
	if($open > 0 && !empty(chat_access($open, $profile_id))){ $typing = chat_typing_others($open, $profile_id); }
	chat_json(0, '', array('revs' => $out, 'seenrevs' => $sout, 'typing' => $typing));
}
/* AJAX: client báo 'tôi đang gõ' → ghi Redis (fallback typing không cần Node). Throttle client ~2s. */
function default_typing(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	if($cid <= 0){ chat_json(1, 'Thiếu kênh'); }
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	$pmap = (new Profile())->getProfileCached('all');
	$name = (isset($pmap[$profile_id]['full_name']) && $pmap[$profile_id]['full_name'] !== '') ? chat_short_name($pmap[$profile_id]['full_name']) : 'Ai đó';
	chat_typing_set($cid, $profile_id, $name);
	echo json_encode(array('error' => 0), JSON_UNESCAPED_UNICODE); die();
}
/* AJAX: tìm tin nhắn theo nội dung trong 1 kênh → trả tối đa 30 tin (mới nhất trước) để nhảy tới. */
function default_search(){
	global $profile_id;
	if(empty($profile_id)){ chat_json(1, 'Bạn chưa đăng nhập'); }
	$cid = (int) Input::post('channel_id', 0);
	$q = trim((string) Input::post('q', ''));
	if($cid <= 0 || $q === ''){ chat_json(0, '', array('list' => array())); }
	if(empty(chat_access($cid, $profile_id))){ chat_json(1, 'Không có quyền'); }
	if(function_exists('mb_substr') && mb_strlen($q) > 100){ $q = mb_substr($q, 0, 100); }
	$qLike = addslashes($q);	// chống SQL-injection (theo pattern hiện có); %/_ coi như wildcard (chấp nhận)
	$clsMsg = new ChatMessage();
	$rows = $clsMsg->getAll("`channel_id`='{$cid}' AND `is_trash`=0 AND `type`<>9 AND `content` LIKE '%{$qLike}%' ORDER BY `message_id` DESC LIMIT 30", "`message_id`,`profile_id`,`content`,`reg_date`");
	$pmap = (new Profile())->getProfileCached('all');
	$list = array();
	if(!empty($rows)){
		foreach($rows as $r){
			$pid = (int) $r['profile_id'];
			$nm = (isset($pmap[$pid]['full_name']) && $pmap[$pid]['full_name'] !== '') ? chat_short_name($pmap[$pid]['full_name']) : ('#'.$pid);
			$ct = chat_strip_mentions(isset($r['content']) ? $r['content'] : '');
			if(function_exists('mb_substr') && mb_strlen($ct) > 120){ $ct = mb_substr($ct, 0, 120).'…'; }
			$list[] = array('message_id' => (int) $r['message_id'], 'name' => $nm, 'content' => $ct, 'time' => date('d/m H:i', (int) $r['reg_date']));
		}
	}
	chat_json(0, '', array('list' => $list));
}
/* Đẩy 1 tin sang Node để emit realtime tới room kênh. Best-effort: Node rớt → bỏ qua (poll bù).
   Tái dùng pattern PHP→Node của cronjobs/notify_task.php (\Curl\Curl). is_me do client tự tính lại theo người nhận. */
function chat_push_node($cid, $message){
	chat_bump_rev($cid);	// Redis: đánh dấu kênh có thay đổi → poll ping bỏ qua DB khi không đổi
	if(!class_exists('\\Curl\\Curl')){
		$autoload = (defined('DIR_INCLUDES') ? DIR_INCLUDES : '').'/curl/vendor/autoload.php';
		if(is_file($autoload)){ require_once $autoload; }
	}
	if(!class_exists('\\Curl\\Curl')){ return; }
	try {
		$curl = new \Curl\Curl();
		$curl->setHeader('Content-Type', 'application/json');
		$curl->setOpt(CURLOPT_CONNECTTIMEOUT, 1);
		$curl->setOpt(CURLOPT_TIMEOUT, 2);
		$curl->post(_CHAT_SOCKET_URL.'/chat-message', array(
			'tenant'     => _TENANT_ID,
			'secret'     => _CHAT_SOCKET_SECRET,
			'channel_id' => (int) $cid,
			'message'    => $message,
		));
	} catch(\Exception $e) { /* nuốt lỗi: tin đã lưu DB + trả người gửi; client khác nhận qua poll bù */ }
}
/* Người gọi có phải CHỦ nhóm (role=1) của kênh? chống thao tác quản trị từ TV thường. */
function chat_group_owner_gate($cid, $pid){
	$mem = (new ChatMember())->getByCond("`channel_id`='".(int) $cid."' AND `profile_id`='".(int) $pid."' AND `role`='1'", "`id`");
	return (!empty($mem) && is_array($mem));
}
/* Đẩy lệnh kick sang Node → emit chat:kick + force-leave room. profile_id=0 = kick cả room (disband). Best-effort. */
function chat_kick_node($cid, $pid){
	if(!class_exists('\\Curl\\Curl')){
		$autoload = (defined('DIR_INCLUDES') ? DIR_INCLUDES : '').'/curl/vendor/autoload.php';
		if(is_file($autoload)){ require_once $autoload; }
	}
	if(!class_exists('\\Curl\\Curl')){ return; }
	try {
		$curl = new \Curl\Curl();
		$curl->setHeader('Content-Type', 'application/json');
		$curl->setOpt(CURLOPT_CONNECTTIMEOUT, 1);
		$curl->setOpt(CURLOPT_TIMEOUT, 2);
		$curl->post(_CHAT_SOCKET_URL.'/chat-kick', array(
			'tenant'     => _TENANT_ID,
			'secret'     => _CHAT_SOCKET_SECRET,
			'channel_id' => (int) $cid,
			'profile_id' => (int) $pid,
		));
	} catch(\Exception $e) { /* nuốt lỗi: DB đã ghi; TV mất quyền ở load kế dù realtime rớt */ }
}
/* Khớp GPS với MỌI VP _OFFICE đang bật. Trả mảng:
   ['match'   => VP hợp lệ gần nhất (trong bán kính + đủ độ chính xác) | null,
    'nearest' => VP gần nhất bất kể đạt/không (title/distance/radius/max_acc) | null] — dùng dựng thông báo lỗi. */
function chat_resolve_office($lat, $lng, $accuracy){
	global $core;
	$clsSetting = new Setting();
	$offices = $clsSetting->getArraySearchByKey('_OFFICE');
	$best = null;
	$nearest = null;
	if(!empty($offices)){
		foreach($offices as $o){
			$mi = isset($o['more_information']) ? $o['more_information'] : array();
			if(!(int) $core->get_field($mi, 'is_active', 1)){ continue; }
			$olat = (float) $core->get_field($mi, 'lat', 0);
			$olng = (float) $core->get_field($mi, 'lng', 0);
			if($olat == 0 || $olng == 0){ continue; }
			$dist = chat_haversine($lat, $lng, $olat, $olng);
			$radius = (int) $core->get_field($mi, 'radius_m', 200);
			$max_acc = (int) $core->get_field($mi, 'max_accuracy_m', _CHECKIN_MAX_ACCURACY);
			// VP gần nhất (bất kể pass/fail) để thông báo lỗi nêu rõ số đo.
			if($nearest === null || $dist < $nearest['distance']){
				$nearest = array('title' => $o['title'], 'distance' => $dist, 'radius' => $radius, 'max_acc' => $max_acc);
			}
			if($dist > $radius){ continue; }
			if($accuracy > 0 && $accuracy > $max_acc){ continue; }
			if($best === null || $dist < $best['distance']){
				$best = array('setting_id' => (int) $o['setting_id'], 'title' => $o['title'], 'distance' => $dist);
			}
		}
	}
	return array('match' => $best, 'nearest' => $nearest);
}
/* Dựng thông báo lỗi khi không khớp VP — nêu rõ sai số GPS đo được + VP gần nhất. */
function chat_resolve_reason($nearest, $accuracy){
	if(empty($nearest)){ return 'Chưa có văn phòng nào cấu hình toạ độ. Vui lòng liên hệ quản trị.'; }
	$d = (int) $nearest['distance'];
	$r = (int) $nearest['radius'];
	$max = (int) $nearest['max_acc'];
	$name = $nearest['title'];
	// Trong bán kính nhưng GPS quá nhiễu → lỗi sai số (kèm số đo thực tế).
	if($d <= $r && $accuracy > 0 && $accuracy > $max){
		return 'GPS lệch ~'.$accuracy.'m, vượt mức cho phép ≤'.$max.'m của VP "'.$name.'" (bạn đang cách '.$d.'m). Ra ngoài trời / bật định vị chính xác rồi thử lại.';
	}
	// Ngoài bán kính → lỗi khoảng cách.
	return 'Bạn cách VP gần nhất "'.$name.'" ~'.$d.'m (chỉ cho phép trong '.$r.'m). Hãy tới gần văn phòng để check-in.';
}
/* Khoảng cách Haversine (mét). */
function chat_haversine($lat1, $lng1, $lat2, $lng2){
	$R = 6371000; $t = M_PI / 180;
	$dla = ($lat2 - $lat1) * $t;
	$dlo = ($lng2 - $lng1) * $t;
	$a = sin($dla / 2) * sin($dla / 2) + cos($lat1 * $t) * cos($lat2 * $t) * sin($dlo / 2) * sin($dlo / 2);
	return (int) round($R * 2 * atan2(sqrt($a), sqrt(1 - $a)));
}
/* Upload 1 ảnh từ CHUỖI base64 (RAW) + resize ≤1280. Trả path hoặc ''. */
function chat_upload_image_data($data, $subdir, $type="driver"){
	global $clsISO,$header_configs;
	$clsGoogleDrive = new GoogleDrive();
	$data = trim((string) $data);
	if($data === ''){ return ''; }
	$pos = strpos($data, 'base64,');
	if($pos !== false){ $data = substr($data, $pos + 7); }
	// Chặn payload base64 quá lớn (client đã resize ≤1280; đây là rào server-side phòng client giả).
	if(strlen($data) > 6 * 1024 * 1024){ return ''; }
	$clsUpload = new UploadFile();
	// Tên duy nhất mỗi lần → tránh 2 ảnh cùng giây ghi đè nhau (helper core chỉ uniquify theo giây).
	$title = uniqid('img_', true).'.jpg';
	$path = $clsUpload->base642imagejpeg($data, $title, $subdir);
	if(!empty($path)){ chat_resize_inplace(ROOTPATH.$path, 1280); }
	if($type != "driver") {
		return $path;
	}
	$info_image = @getimagesize(ROOTPATH.$path);
	$createdFile = $clsGoogleDrive->upload($title, $info_image["mime"], ROOTPATH.$path, $header_configs["gdrive_folder_checkin"]);
	$link_image = "";
	if(!empty($path)){
		$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
	}		
	@unlink(ROOTPATH . $path);
	return $link_image;
}
/* Ảnh ĐẠI DIỆN NHÓM: base64 (RAW) → /CHAT/GROUP/YYYYMM (uppercase — khớp P3, host Linux case-sensitive), resize ≤256.
   Tách riêng khỏi chat_upload_image_data (ảnh tin ≤1280) — KHÔNG sửa nghĩa helper cũ. Trả path hoặc ''. */
function chat_upload_group_image($data){
	$data = trim((string) $data);
	if($data === ''){ return ''; }
	$pos = strpos($data, 'base64,');
	if($pos !== false){ $data = substr($data, $pos + 7); }
	if(strlen($data) > 4 * 1024 * 1024){ return ''; }	// avatar nhóm nhỏ; rào server-side
	$clsUpload = new UploadFile();
	$path = $clsUpload->base642imagejpeg($data, uniqid('grp_', true).'.jpg', '/CHAT/GROUP/'.date('Ym'));
	if(!empty($path)){
		$abs = ROOTPATH.$path;
		$info = @getimagesize($abs);	// xác thực ảnh THẬT (chống lưu byte rác .jpg công khai); chỉ giữ JPEG/PNG
		if(empty($info) || ($info[2] !== IMAGETYPE_JPEG && $info[2] !== IMAGETYPE_PNG)){
			@unlink($abs);
			return '';
		}
		chat_resize_inplace($abs, 256);
	}
	return $path;
}
/* Upload ảnh base64 đọc RAW từ $_POST[$field] (1 ảnh). Dùng chung check-in + chat. */
function chat_upload_image($field, $subdir){
	return chat_upload_image_data(isset($_POST[$field]) ? $_POST[$field] : '', $subdir);
}
/* Ảnh check-in (field 'photo' → /CHECKIN/YYYYMM). */
function chat_upload_checkin_photo(){
	return chat_upload_image('photo', '/CHECKIN/'.date('Ym'));
}
/* Resize JPEG/PNG tại chỗ về tối đa $maxW. Bỏ qua nếu thiếu GD hoặc ảnh đã nhỏ. */
function chat_resize_inplace($absFile, $maxW){
	if(!file_exists($absFile) || !function_exists('imagecreatefromjpeg')){ return; }
	$info = @getimagesize($absFile);
	if(empty($info) || $info[0] <= $maxW){ return; }
	$w = $info[0]; $h = $info[1];
	if($info[2] == IMAGETYPE_JPEG){
		$src = @imagecreatefromjpeg($absFile);
	} else if($info[2] == IMAGETYPE_PNG){
		$src = @imagecreatefrompng($absFile);
	} else {
		return;
	}
	if(!$src){ return; }
	$nw = $maxW;
	$nh = (int) round($h * $maxW / $w);
	$dst = imagecreatetruecolor($nw, $nh);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
	@imagejpeg($dst, $absFile, 82);
	imagedestroy($src);
	imagedestroy($dst);
}
/* Phòng ban của NV (snapshot, để báo cáo/tính vắng theo phòng). */
function chat_profile_dept($profile_id){
	$clsProfile = new Profile();
	$one = $clsProfile->getOne((int) $profile_id, 'department_id');
	return (int) (isset($one['department_id']) ? $one['department_id'] : 0);
}
/* Cắt chuỗi UTF-8 về tối đa 255 ký tự (khớp cột VARCHAR(255) note/address). */
function chat_trim255($s){
	$s = trim((string) $s);
	if(function_exists('mb_substr')){ return mb_substr($s, 0, 255, 'UTF-8'); }
	return substr($s, 0, 255);
}
/* Từ 1 result Google Geocoding → địa chỉ THÔ: xã/phường + tỉnh/thành phố (bỏ đường/số nhà). Cho check-in NGOÀI VP. Cơ cấu HC VN mới 2 cấp: tỉnh → xã/phường. */
function chat_coarse_address($result){
	$comps = (isset($result['address_components']) && is_array($result['address_components'])) ? $result['address_components'] : array();
	$ward = ''; $district = ''; $prov = '';
	foreach($comps as $c){
		$types = (isset($c['types']) && is_array($c['types'])) ? $c['types'] : array();
		$name  = isset($c['long_name']) ? trim($c['long_name']) : '';
		if($name === ''){ continue; }
		if($ward === '' && (in_array('administrative_area_level_3', $types, true) || in_array('sublocality_level_1', $types, true) || in_array('sublocality', $types, true))){ $ward = $name; }
		elseif($district === '' && in_array('administrative_area_level_2', $types, true)){ $district = $name; }
		if($prov === '' && in_array('administrative_area_level_1', $types, true)){ $prov = $name; }
	}
	$local = ($ward !== '') ? $ward : $district;	// ưu tiên xã/phường; thiếu thì quận/huyện
	$parts = array();
	if($local !== ''){ $parts[] = $local; }
	if($prov !== ''){ $parts[] = $prov; }
	return implode(', ', $parts);	// "Phường X, Tỉnh Y" (rỗng → caller fallback formatted_address)
}
/* Reverse-geocode toạ độ → địa chỉ Google trả (formatted_address). null nếu lỗi/không có.
   ⚠ _API_KEY_GOOGLE_MAP (gmap_keys[2]) TẮT BILLING cho Geocoding → thử LẦN LƯỢT cả gmap_keys,
   bỏ qua key REQUEST_DENIED/hết quota, lấy key đầu trả OK (key[0]/[1] đang còn billing). */
function chat_reverse_geocode($lat, $lng, $coarse = false){
	$keys = defined('gmap_keys') && is_array(gmap_keys) ? gmap_keys : array();
	if(defined('_API_KEY_GOOGLE_MAP') && _API_KEY_GOOGLE_MAP !== ''){ $keys[] = _API_KEY_GOOGLE_MAP; }	// fallback cuối
	$keys = array_values(array_unique(array_filter($keys)));
	if(empty($keys)){ return null; }
	$lat = (float) $lat; $lng = (float) $lng;
	$latlng = rawurlencode($lat.','.$lng);
	$ctx = stream_context_create(array('http' => array('timeout' => 6)));	// đừng treo request nếu Google chậm
	foreach($keys as $key){
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.'&language=vi&key='.urlencode($key);
		$resp = @file_get_contents($url, false, $ctx);
		if($resp === false){ continue; }		// mạng lỗi → thử key kế
		$json = json_decode($resp, true);
		if(!is_array($json) || empty($json['status'])){ continue; }
		if($json['status'] === 'OK' && !empty($json['results'][0]['formatted_address'])){
			if($coarse){		// NGOÀI VP → chỉ xã/phường + tỉnh/thành phố (bỏ đường/số nhà)
				$cs = chat_coarse_address($json['results'][0]);
				if($cs !== ''){ return chat_trim255($cs); }
			}
			return chat_trim255($json['results'][0]['formatted_address']);	// trong VP (hoặc không bóc được component) → địa chỉ đầy đủ
		}
		if($json['status'] === 'ZERO_RESULTS'){ return null; }	// toạ độ hợp lệ nhưng không có địa chỉ → key khác cũng vậy
		// REQUEST_DENIED (billing/chưa bật API) / OVER_QUERY_LIMIT / INVALID_REQUEST → thử key kế
	}
	return null;
}
