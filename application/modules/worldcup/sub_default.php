<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
/**
 * Mini game Dự đoán World Cup 2026.
 * - Frontend: AngularJS wcApp (views/worldcup/default.tpl + js/jquery.worldcup.js)
 * - Backend : các API default_api_* lưu DB (4 bảng default_worldcup_*)
 * - Quyền   : XEM công khai; BÌNH CHỌN cần đăng nhập + is_verified=1;
 *             QUẢN TRỊ cần $clsISO->checkDEV().
 */

/*============= [Worldcup - default_default] - START =============*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$clsConfiguration,$image_page,$profile_id;
	/*=============Title & Description Page==================*/
	$title_page = 'Dự đoán World Cup 2026 - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Dự đoán mọi trận đấu World Cup 2026, cộng điểm mỗi round, leo bảng xếp hạng real-time và rinh quà khủng.';
	$assign_list["description_page"] = $description_page;
	$image_page = URL_IMAGES."/bg_worldcup.png";
	$assign_list["image_page"] = $image_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;

	/* Dữ liệu bootstrap cho frontend (chỉ trạng thái đăng nhập + cấu hình, KHÔNG phải dữ liệu trận) */
	$clsP = new Profile();
	$logged = $clsP->isLoggedIn() ? 1 : 0;
	$pid = (int)$profile_id;
	$verified = ($logged && $pid > 0) ? ((int)$clsP->getOneField('is_verified', $pid) === 1 ? 1 : 0) : 0;
	$isDev = wc_is_dev();
	$wc_boot = array(
		'isLoggedIn' => $logged,
		'isVerified' => 1,
		'isDev'      => $isDev,
		'profileId'  => $pid,
		'serverTime' => time() * 1000,
		'config'     => wc_config(),
		'loginUrl'   => PCMS_URL.'/index.php?mod=auth&act=signin'
	);
	$smarty->assign('wc_boot', $wc_boot);
}
/*============= [Worldcup - default_default] - END =============*/

/*============= [Worldcup - Helpers] - START =============*/
/** Cấu hình luật chơi (mặc định; có thể override bằng Configuration 'worldcup_config' dạng JSON) */
function wc_config(){
	$defaultStages = array(
		'group' => 1,   // Vòng bảng
		'r32'   => 2,   // Vòng 32 đội (1/16)
		'r16'   => 4,   // Vòng 16 đội (1/8)
		'qf'    => 8,   // Tứ kết
		'sf'    => 16,  // Bán kết
		'third' => 20,  // Tranh hạng 3
		'final' => 30   // Chung kết
	);
	$cfg = array(
		'openMin'       => 60,  // MỞ bình chọn trước giờ bóng lăn (phút)
		'lockMin'       => 30,  // ĐÓNG bình chọn trước giờ bóng lăn (phút)
		'basePoints'    => 1,   // điểm fallback nếu trận có stage lạ
		'showCommunity' => 1,
		'stagePoints'   => $defaultStages  // điểm cộng khi đoán ĐÚNG, theo vòng (chỉnh được)
	);
	$clsConfiguration = new Configuration();
	$raw = $clsConfiguration->getValue('worldcup_config');
	if(!empty($raw)){
		$j = json_decode($raw, true);
		if(is_array($j)){
			$override = isset($j['stagePoints']) && is_array($j['stagePoints']) ? $j['stagePoints'] : array();
			$cfg = array_merge($cfg, $j);
			$cfg['stagePoints'] = array_merge($defaultStages, $override); // deep-merge: override thiếu khóa vẫn đủ
		}
	}
	return $cfg;
}
/** Kiểm tra quyền DEV (không phụ thuộc global $clsISO có sẵn hay không trong context AJAX) */
function wc_is_dev(){
	global $clsISO, $profile_id;
	$iso = (isset($clsISO) && is_object($clsISO)) ? $clsISO : new ISO();
	return $clsISO->checkPermission("manager_edit_wc") ? 1 : 0;
}
/** Xuất JSON rồi dừng */
function wc_json($data){
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
	die();
}
/** Đọc tham số POST (frontend gửi form-urlencoded) */
function wc_post($k, $def = ''){ 
	return isset($_POST[$k]) ? $_POST[$k] : $def; 
}

/** Đảm bảo đã đăng nhập; trả profile_id (>0) hoặc xuất lỗi JSON */
function wc_require_login(){
	global $profile_id;
	$clsP = new Profile();
	if(!$clsP->isLoggedIn()){ wc_json(array('ok'=>0,'error'=>'login_required','message'=>'Vui lòng đăng nhập để dự đoán.')); }
	return (int)$profile_id;
}
/** Đảm bảo đã đăng nhập + đã xác thực; trả profile_id hoặc xuất lỗi JSON */
function wc_require_voter(){
	$pid = wc_require_login();
	$clsP = new Profile();
	//$verified = (int)$clsP->getOneField('is_verified', $pid);
	$verified = 1;
	if($verified !== 1){ wc_json(array('ok'=>0,'error'=>'not_verified','message'=>'Tài khoản chưa xác thực. Vui lòng xác thực để được bình chọn.')); }
	return $pid;
}
/** Đảm bảo quyền DEV (quản trị) hoặc xuất lỗi JSON */
function wc_require_dev(){
	global $clsISO, $profile_id;
	$clsP = new Profile(); $clsP->isLoggedIn();
	if(!wc_is_dev()){ wc_json(array('ok'=>0,'error'=>'forbidden','message'=>'Bạn không có quyền quản trị.')); }
	return (int)$profile_id;
}
/** Trận loại trực tiếp (knockout) = mọi vòng trừ vòng bảng → chỉ H/A, có luân lưu */
function wc_is_knockout($stage){ return $stage !== null && $stage !== '' && $stage !== 'group'; }
/** Dựng object trận cho frontend (merge team + pick của user) */
function wc_match_view($m, $teamMap, $myPicks){
	$h = isset($teamMap[$m['home_team_id']]) ? $teamMap[$m['home_team_id']] : array('code'=>'','name'=>'?','short'=>'?');
	$a = isset($teamMap[$m['away_team_id']]) ? $teamMap[$m['away_team_id']] : array('code'=>'','name'=>'?','short'=>'?');
	$mid = (int)$m['match_id'];
	$mine = isset($myPicks[$mid]) ? $myPicks[$mid] : null;
	$hasResult = ((int)$m['status'] === 1 && $m['result'] !== null && $m['result'] !== '');	
	$open_minutes = isset($m['open_minutes']) ? (int)$m['open_minutes'] : 60;
	$open_minutes = 60*24*12;
	$last_time_vote = strtotime("- ".$m["lock_minutes"]." minutes",$m["kickoff"]);
	$d = (int) date("d",$last_time_vote);
	$d_today = (int) date("d");
	if($d - $d_today == 0) {
		$last_time_vote = date("H:i",$last_time_vote)." Hôm nay";
	}else if($d - $d_today == 1) {
		$last_time_vote = date("H:i",$last_time_vote)." Ngày mai";
	}else{
		$last_time_vote = date("H:i d/m",$last_time_vote);
	}
	return array(
		'id'       => $mid,
		'round'    => $m['round'],
		'stage'    => isset($m['stage']) ? $m['stage'] : 'group',
		'isKnockout' => wc_is_knockout(isset($m['stage']) ? $m['stage'] : 'group') ? 1 : 0,
		'slot'     => (int)$m['kickoff'] * 1000,   // ms cho JS
		'lockMin'  => (int)$m['lock_minutes'],
		'last_time_vote'  => $last_time_vote,
		'openMin'  => $open_minutes,
		'homeId'   => (int)$m['home_team_id'],
		'awayId'   => (int)$m['away_team_id'],
		'home'     => array('code'=>$h['code'],'name'=>$h['name'],'short'=>$h['short']),
		'away'     => array('code'=>$a['code'],'name'=>$a['name'],'short'=>$a['short']),
		'votes'    => array('H'=>(int)$m['vote_h'],'D'=>(int)$m['vote_d'],'A'=>(int)$m['vote_a']),
		'score'    => $hasResult ? array((int)$m['home_score'],(int)$m['away_score']) : null,
		'pen'      => ($hasResult && isset($m['pen_home']) && $m['pen_home'] !== null && isset($m['pen_away']) && $m['pen_away'] !== null) ? array((int)$m['pen_home'],(int)$m['pen_away']) : null,
		'result'   => $hasResult ? $m['result'] : null,
		'status'   => (int)$m['status'],
		'myPick'   => $mine ? $mine['pick'] : null,
		'myPoints' => $mine ? (int)$mine['points'] : 0,
		'myCorrect'=> ($mine && $mine['is_correct'] !== null) ? (int)$mine['is_correct'] : null
	);
}
/** Phần DÙNG CHUNG của api_state (đội + danh sách trận nền, KHÔNG có pick riêng user).
 *  Cache Redis 60 phút; miss thì dựng lại từ DB. Phần riêng (pick/điểm/giờ) tính tươi ở api_state. */
function wc_state_catalog(){
	$key = 'worldcup_state_catalog';
	$cache = null;
	/*try {
		$cache = new Cache();
		$cached = $cache->get($key, null);
		if(is_array($cached) && isset($cached['teams']) && isset($cached['matches'])){ return $cached; }
	} catch(\Throwable $e){ $cache = null; }*/

	$clsTeam  = new WorldcupTeam();
	$clsMatch = new WorldcupMatch();
	$teamMap  = $clsTeam->getMap();
	$matches = array();
	$rows = $clsMatch->getAllActive();
	if(!empty($rows)){
		foreach($rows as $m){ $matches[] = wc_match_view($m, $teamMap, array()); } // myPicks rỗng → phần riêng để null, phủ sau
	}
	$teams = array();
	$teamRows = $clsTeam->getListActive();
	if(!empty($teamRows)){
		foreach($teamRows as $t){
			$teams[] = array('id'=>(int)$t['team_id'],'code'=>$t['code'],'name'=>$t['name'],'short'=>$t['short']);
		}
	}
	$catalog = array('teams'=>$teams, 'matches'=>$matches);
	try { if(!$cache){ $cache = new Cache(); } $cache->put($key, $catalog, 60*60); } catch(\Throwable $e){}
	return $catalog;
}
/** Xóa cache dùng chung (gọi khi trận/đội/vote/kết quả thay đổi). */
function wc_state_cache_flush(){
	try { $c = new Cache(); $c->delete('worldcup_state_catalog'); } catch(\Throwable $e){}
}
/*============= [Worldcup - Helpers] - END =============*/

/*============= [Worldcup - API state] - START =============*/
/** Toàn bộ state ban đầu: trận + đội + thông tin user + cấu hình + giờ server */
function default_api_state(){
	global $dbconn, $profile_id, $clsISO, $oneProfile;
	$clsProfile = new Profile();
	$logged = $clsProfile->isLoggedIn() ? 1 : 0;
	$verified = ($logged && $profile_id > 0) ? ((int)$oneProfile["is_verified"] === 1 ? 1 : 0) : 0;

	/*=== Cache 60' phần DÙNG CHUNG (đội + trận nền); phần riêng user phủ tươi bên dưới ===*/
	$catalog = wc_state_catalog();
	$teams   = isset($catalog['teams'])   ? $catalog['teams']   : array();
	$matches = isset($catalog['matches']) ? $catalog['matches'] : array();

	$clsScore = new WorldcupScore();
	$clsPick  = new WorldcupPick();
	$myPicks  = ($logged && $profile_id>0) ? $clsPick->getMapByProfile($profile_id) : array();
	// Phủ pick RIÊNG của user lên matches nền (không cache)
	if(!empty($myPicks) && !empty($matches)){
		foreach($matches as $i => $mm){
			$mid = isset($mm['id']) ? (int)$mm['id'] : 0;
			if($mid && isset($myPicks[$mid])){
				$mp = $myPicks[$mid];
				$matches[$i]['myPick']    = $mp['pick'];
				$matches[$i]['myPoints']  = (int)$mp['points'];
				$matches[$i]['myCorrect'] = ($mp['is_correct'] !== null) ? (int)$mp['is_correct'] : null;
			}
		}
	}

	$me = null;
	if($logged && $profile_id>0){
		$sc = $clsScore->getScore($profile_id);
		$me = array(
			'profileId' => $profile_id,
			'name'      => $clsProfile->getFullName($profile_id,$oneProfile),
			'pts'       => (int)$sc['total_points'],
			'correct'   => (int)$sc['total_correct'],
			'played'    => (int)$sc['total_played'],
			'streak'    => (int)$sc['streak'],
			'rank'      => $clsScore->getRank($profile_id)
		);
	}

	wc_json(array(
		'ok'         => 1,
		'isLoggedIn' => $logged,
		'isVerified' => 1,
		'isDev'      => wc_is_dev(),
		'serverTime' => time() * 1000,
		'config'     => wc_config(),
		'teams'      => $teams,
		'matches'    => $matches,
		'me'         => $me
	));
}
/*============= [Worldcup - API state] - END =============*/

/*============= [Worldcup - API pick] - START =============*/
/** Chốt/đổi dự đoán 1 trận (cần đăng nhập + xác thực) */
function default_api_pick(){
	$pid = wc_require_voter();
	$match_id = (int)wc_post('match_id', 0);
	$pick = strtoupper(trim(wc_post('pick', '')));
	if($match_id <= 0 || !in_array($pick, array('H','D','A'))){
		wc_json(array('ok'=>0,'error'=>'bad_request','message'=>'Dữ liệu không hợp lệ.'));
	}
	$clsMatch = new WorldcupMatch();
	$clsPick  = new WorldcupPick();
	$m = $clsMatch->getOne($match_id);
	if(empty($m) || (int)$m['is_trash'] === 1){
		wc_json(array('ok'=>0,'error'=>'not_found','message'=>'Không tìm thấy trận đấu.'));
	}
	if((int)$m['status'] === 1){
		wc_json(array('ok'=>0,'error'=>'finished','message'=>'Trận đã có kết quả.'));
	}
	if(wc_is_knockout(isset($m['stage']) ? $m['stage'] : 'group') && $pick === 'D'){
		wc_json(array('ok'=>0,'error'=>'no_draw','message'=>'Trận loại trực tiếp chỉ được chọn đội thắng.'));
	}
	$openMin = isset($m['open_minutes']) ? (int)$m['open_minutes'] : 60;
	$openMin = 60*24*12;
	$openAt = (int)$m['kickoff'] - $openMin * 60;
	if(time() < $openAt){
		wc_json(array('ok'=>0,'error'=>'not_open','message'=>'Chưa mở bình chọn cho trận này.'));
	}
	$lockAt = (int)$m['kickoff'] - (int)$m['lock_minutes'] * 60;
	if(time() >= $lockAt){
		wc_json(array('ok'=>0,'error'=>'locked','message'=>'Đã đóng bình chọn cho trận này.'));
	}
	$now = time();
	$existing = $clsPick->getOnePick($match_id, $pid);
	if(!empty($existing)){
		if($existing['pick'] === $pick){
			// không đổi gì
		} else {
			$clsMatch->bumpVote($match_id, $existing['pick'], -1);
			$clsMatch->bumpVote($match_id, $pick, 1);
			$clsPick->updateOne((int)$existing['pick_id'], array('pick'=>$pick,'upd_date'=>$now));
		}
	} else {
		$clsPick->insert(array(
			'match_id'   => $match_id,
			'profile_id' => $pid,
			'pick'       => $pick,
			'kickoff'    => (int)$m['kickoff'],
			'points'     => 0,
			'is_correct' => null,
			'reg_date'   => $now,
			'upd_date'   => $now
		));
		$clsMatch->bumpVote($match_id, $pick, 1);
	}
	wc_state_cache_flush(); // vote đổi → làm mới cache trận dùng chung
	$m2 = $clsMatch->getOne($match_id);
	wc_json(array(
		'ok'     => 1,
		'matchId'=> $match_id,
		'myPick' => $pick,
		'votes'  => array('H'=>(int)$m2['vote_h'],'D'=>(int)$m2['vote_d'],'A'=>(int)$m2['vote_a'])
	));
}
/*============= [Worldcup - API pick] - END =============*/

/*============= [Worldcup - API leaderboard] - START =============*/
/** Bảng xếp hạng (công khai) — top 50 + hạng của user hiện tại */
function default_api_leaderboard(){
	global $profile_id,$clsISO;
	$clsP = new Profile();
	$logged = $clsP->isLoggedIn();
	$pid = (int)$profile_id;

	$clsScore = new WorldcupScore();
	$top = $clsScore->getTop(250);
	$list = array();
	$rank = 0;$score = 0;
	if(!empty($top)){
		foreach($top as $key => $row){
			if($key == 0 || $score > (int)$row['total_points']) {
				$score = (int)$row['total_points'];
				$rank++;
			}
			
			$rpid = (int)$row['profile_id'];
			$name = $clsP->getOneField('full_name', $rpid);
			$list[] = array(
				'rank'    => $rank,
				'name'    => $name ? $name : ('User #'.$rpid),
				'pts'     => (int)$row['total_points'],
				'correct' => (int)$row['total_correct'],
				'streak'  => (int)$row['streak'],
				'avt'  	 => $clsP->getAvatar($rpid,54,54),
				'me'      => ($logged && $rpid === $pid)
			);
		}
	}
	$myRank = ($logged && $pid>0) ? $clsScore->getRank($pid) : 0;
	wc_json(array('ok'=>1,'list'=>$list,'myRank'=>$myRank));
}
/*============= [Worldcup - API leaderboard] - END =============*/

/*============= [Worldcup - API admin save_match] - START =============*/
/** Thêm/sửa 1 trận (chỉ DEV) */
function default_api_admin_save_match(){
	global $dbconn;
	$pid = wc_require_dev();
	$match_id    = (int)wc_post('match_id', 0);
	$home_id     = (int)wc_post('home_team_id', 0);
	$away_id     = (int)wc_post('away_team_id', 0);
	$kickoff     = (int)wc_post('kickoff', 0); // unix seconds
	$round       = trim(wc_post('round', ''));
	$stage       = trim(wc_post('stage', 'group'));
	$allowedStage= array('group','r32','r16','qf','sf','third','final');
	if(!in_array($stage, $allowedStage)){ $stage = 'group'; }
	$cfg = wc_config();
	$lock_minutes= (int)wc_post('lock_minutes', 0);
	$open_minutes= (int)wc_post('open_minutes', 0);
	if($lock_minutes <= 0){ $lock_minutes = (int)$cfg['lockMin']; }
	if($open_minutes <= 0){ $open_minutes = (int)$cfg['openMin']; }
	if($open_minutes <= $lock_minutes){ $open_minutes = $lock_minutes + 1; } // mở phải sớm hơn đóng
	if($home_id<=0 || $away_id<=0 || $home_id===$away_id){
		wc_json(array('ok'=>0,'error'=>'bad_teams','message'=>'Hai đội không hợp lệ.'));
	}
	if($kickoff<=0){
		wc_json(array('ok'=>0,'error'=>'bad_time','message'=>'Thiếu giờ bóng lăn.'));
	}
	$clsMatch = new WorldcupMatch();
	$now = time();
	if($match_id > 0){
		$clsMatch->updateOne($match_id, array(
			'home_team_id'=>$home_id,'away_team_id'=>$away_id,'round'=>$round,'stage'=>$stage,
			'kickoff'=>$kickoff,'lock_minutes'=>$lock_minutes,'open_minutes'=>$open_minutes,
			'user_id_update'=>$pid,'upd_date'=>$now
		));
	} else {
		$newId = $clsMatch->insert(array(
			'home_team_id'=>$home_id,'away_team_id'=>$away_id,'round'=>$round,'stage'=>$stage,
			'kickoff'=>$kickoff,'lock_minutes'=>$lock_minutes,'open_minutes'=>$open_minutes,'status'=>0,
			'vote_h'=>0,'vote_d'=>0,'vote_a'=>0,'is_trash'=>0,
			'user_id'=>$pid,'user_id_update'=>$pid,'reg_date'=>$now,'upd_date'=>$now
		));
		// insert() trả ID khi OK; rỗng = lỗi DB (thường do bảng thiếu cột stage/open_minutes)
		if(empty($newId)){
			wc_json(array('ok'=>0,'error'=>'db_error','message'=>'Lưu thất bại — bảng default_worldcup_match có thể thiếu cột stage/open_minutes. Hãy chạy phần ALTER trong worldcup_schema.sql.'));
		}
	}
	wc_state_cache_flush(); // trận mới/sửa → làm mới cache
	wc_json(array('ok'=>1));
}
/*============= [Worldcup - API admin save_match] - END =============*/

/*============= [Worldcup - API admin import] - START =============*/
/** Import nhiều trận từ CSV (chỉ DEV). rows = JSON mảng {home_team_id,away_team_id,kickoff,round,lock_minutes} */
function get_stage($round){
	$arr = [
		"group"	=>	"Vòng bảng",
		"r32"	=>	"Vòng 32 đội (1/16)",
		"r16"	=>	"Vòng 16 đội (1/8)",
		"qf"	=>	"Tứ kết",
		"sf"	=>	"Bán kết",
		"third"	=>	"Tranh hạng 3",
		"final"	=>	"Tranh hạng 3",
	];
	return array_search($round,$arr);
}
function default_api_admin_import(){
	global $clsISO;
	$pid = wc_require_dev();
	$rows = Input::post("rows","");
	$rows = $clsISO->to_array_json($rows);
	if(!is_array($rows)){ wc_json(array('ok'=>0,'error'=>'bad_request','message'=>'Dữ liệu import không hợp lệ.')); }
	$cfg = wc_config();
	$clsMatch = new WorldcupMatch();
	$now = time();
	$inserted = 0;
	foreach($rows as $r){
		$home_id = isset($r['home_team_id']) ? (int)$r['home_team_id'] : 0;
		$away_id = isset($r['away_team_id']) ? (int)$r['away_team_id'] : 0;
		$kickoff = isset($r['kickoff']) ? (int)$r['kickoff'] : 0;
		$round   = isset($r['round']) ? trim($r['round']) : '';
		$stage = @get_stage($round);
		if(!in_array($stage, array('group','r32','r16','qf','sf','third','final'))){ $stage = 'group'; }
		$lock_minutes = isset($r['lock_minutes']) && (int)$r['lock_minutes']>0 ? (int)$r['lock_minutes'] : (int)$cfg['lockMin'];
		$open_minutes = isset($r['open_minutes']) && (int)$r['open_minutes']>0 ? (int)$r['open_minutes'] : (int)$cfg['openMin'];
		if($open_minutes <= $lock_minutes){ $open_minutes = $lock_minutes + 1; }
		if($home_id<=0 || $away_id<=0 || $home_id===$away_id || $kickoff<=0){ continue; }
		$clsMatch->insert(array(
			'home_team_id'=>$home_id,'away_team_id'=>$away_id,'round'=>$round,'stage'=>$stage,
			'kickoff'=>$kickoff,'lock_minutes'=>$lock_minutes,'open_minutes'=>$open_minutes,'status'=>0,
			'vote_h'=>0,'vote_d'=>0,'vote_a'=>0,'is_trash'=>0,
			'user_id'=>$pid,'user_id_update'=>$pid,'reg_date'=>$now,'upd_date'=>$now
		));
		$inserted++;
	}
	wc_state_cache_flush(); // import xong → làm mới cache
	wc_json(array('ok'=>1,'inserted'=>$inserted));
}
/*============= [Worldcup - API admin import] - END =============*/

/*============= [Worldcup - API admin set_result] - START =============*/
/** Nhập tỉ số → chốt kết quả + cộng điểm (settle) cho mọi dự đoán (chỉ DEV) */
function default_api_admin_set_result(){
	global $clsISO;
	$pid = wc_require_dev();
	$match_id = (int)wc_post('match_id', 0);
	$hs_raw = wc_post('home_score', '');
	$as_raw = wc_post('away_score', '');
	if($match_id<=0 || $hs_raw==='' || $as_raw===''){
		wc_json(array('ok'=>0,'error'=>'bad_request','message'=>'Thiếu tỉ số.'));
	}
	$home_score = (int)$hs_raw;
	$away_score = (int)$as_raw;
	$result = $home_score > $away_score ? 'H' : ($home_score < $away_score ? 'A' : 'D');

	$clsMatch = new WorldcupMatch();
	$clsPick  = new WorldcupPick();
	$clsScore = new WorldcupScore();
	$m = $clsMatch->getOne($match_id);
	if(empty($m)){ wc_json(array('ok'=>0,'error'=>'not_found','message'=>'Không tìm thấy trận.')); }

	// Tính đội thắng: knockout luôn có thắng/bại (hòa sau hiệp phụ → luân lưu)
	$knockout = wc_is_knockout(isset($m['stage']) ? $m['stage'] : 'group');
	$pen_home = $pen_away = null;
	if($home_score > $away_score){ $result = 'H'; }
	elseif($home_score < $away_score){ $result = 'A'; }
	else {
		if($knockout){
			$ph_raw = wc_post('pen_home', ''); $pa_raw = wc_post('pen_away', '');
			if($ph_raw === '' || $pa_raw === ''){
				wc_json(array('ok'=>0,'error'=>'need_pen','message'=>'Hòa sau hiệp phụ — nhập tỉ số luân lưu để xác định đội thắng.'));
			}
			$pen_home = (int)$ph_raw; $pen_away = (int)$pa_raw;
			if($pen_home === $pen_away){
				wc_json(array('ok'=>0,'error'=>'pen_tie','message'=>'Tỉ số luân lưu phải có đội thắng (không được hòa).'));
			}
			$result = $pen_home > $pen_away ? 'H' : 'A';
		} else {
			$result = 'D';
		}
	}
	$now = time();
	$upd = array(
		'home_score'=>$home_score,'away_score'=>$away_score,
		'result'=>$result,'status'=>1,
		'user_id_update'=>$pid,'upd_date'=>$now
	);
	if($knockout){ $upd['pen_home'] = $pen_home; $upd['pen_away'] = $pen_away; } // chỉ knockout mới đụng cột pen
	$clsMatch->updateOne($match_id, $upd);

	$cfg = wc_config();
	// Điểm theo vòng (stage) của trận; fallback basePoints nếu stage lạ
	$stage = isset($m['stage']) ? $m['stage'] : 'group';
	$stagePts = isset($cfg['stagePoints'][$stage]) ? (int)$cfg['stagePoints'][$stage] : (int)$cfg['basePoints'];

	// Chốt điểm từng dự đoán (idempotent: ghi đè points/is_correct mỗi lần chạy)
	$affected = array();
	$picks = $clsPick->getAll("match_id='{$match_id}'", "pick_id, profile_id, pick");
	if(!empty($picks)){
		foreach($picks as $p){
			$correct = ($p['pick'] === $result) ? 1 : 0;
			$points = $correct ? $stagePts : 0;
			$clsPick->updateOne((int)$p['pick_id'], array('is_correct'=>$correct,'points'=>$points,'upd_date'=>$now));
			$affected[(int)$p['profile_id']] = true;
		}
	}
	// Tính lại điểm tổng cho từng user bị ảnh hưởng (từ đầu → không double count)
	foreach(array_keys($affected) as $apid){
		$agg = $clsPick->aggregate($apid);
		$streak = $clsPick->currentStreak($apid);
		$clsScore->upsert($apid, $agg['pts'], $agg['correct'], $agg['played'], $streak);
	}
	wc_state_cache_flush(); // chốt kết quả → làm mới cache
	wc_json(array('ok'=>1,'result'=>$result,'settled'=>count($affected)));
}
/*============= [Worldcup - API admin set_result] - END =============*/

/*============= [Worldcup - API admin save_config] - START =============*/
/** Lưu điểm theo vòng (chỉ DEV) — gộp vào Configuration 'worldcup_config' (giữ override khác) */
function default_api_admin_save_config(){
	global $clsISO;
	$pid = wc_require_dev();
	$allowed = array('group','r32','r16','qf','sf','third','final');
	$stage_points = Input::post("stage_points","");
	$sp = $clsISO->to_array_json($stage_points);
	if(!is_array($sp)){ wc_json(array('ok'=>0,'error'=>'bad_request','message'=>'Dữ liệu điểm không hợp lệ.')); }
	$clean = array();
	foreach($allowed as $k){
		if(isset($sp[$k])){
			$v = (int)$sp[$k];
			if($v < 0){ $v = 0; }
			$clean[$k] = $v;
		}
	}
	if(empty($clean)){ wc_json(array('ok'=>0,'error'=>'bad_request','message'=>'Không có điểm vòng nào để lưu.')); }
	$clsCfg = new Configuration();
	$raw = $clsCfg->getValue('worldcup_config');
	$existing = array();
	if(!empty($raw)){ $d = json_decode($raw, true); if(is_array($d)){ $existing = $d; } }
	$existing['stagePoints'] = isset($existing['stagePoints']) && is_array($existing['stagePoints'])
		? array_merge($existing['stagePoints'], $clean) : $clean;
	$clsCfg->updateValue('worldcup_config', json_encode($existing, JSON_UNESCAPED_UNICODE));
	$newCfg = wc_config();
	wc_json(array('ok'=>1,'stagePoints'=>$newCfg['stagePoints']));
}
/*============= [Worldcup - API admin save_config] - END =============*/
?>
