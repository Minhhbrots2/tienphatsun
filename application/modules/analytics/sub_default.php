<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| Module: analytics — Dashboard Quản trị BOD (MyFuture)
|| Render server-side toàn bộ (markup crm-ld + bar CSS). Bộ lọc qua GET param
|| (reload): period (kỳ) -> số "mới/kỳ" ở ①③; project_id/region/investor -> ② Quỹ hàng.
|| Input whitelist/cast + ADOdb qstr -> chống SQLi. Data: fhgroupt_user ($dbconn).
\*======================================================================*/
function analytics_can_access(){
	global $clsISO;
	return ($clsISO->checkDEV() || $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('analytics_access'));
}
function analytics_initials($name){
	$name = trim((string)$name);
	if($name === '') return '?';
	$parts = array_values(array_filter(preg_split('/\s+/u', $name)));
	$n = count($parts);
	if($n >= 2){
		return mb_strtoupper(mb_substr($parts[$n-2],0,1,'UTF-8').mb_substr($parts[$n-1],0,1,'UTF-8'),'UTF-8');
	}
	return mb_strtoupper(mb_substr($name,0,2,'UTF-8'),'UTF-8');
}

function analytics_src_rows($rows, $palette){
	$rows = is_array($rows) ? $rows : array();
	$max = 1;
	foreach($rows as $r){ if((int)$r['units'] > $max) $max = (int)$r['units']; }
	$out = array(); $i = 0;
	foreach($rows as $r){
		$name = (isset($r['name']) && trim((string)$r['name']) !== '') ? trim($r['name']) : 'Khác';
		$u = (int)$r['units'];
		$out[] = array('name'=>$name, 'units'=>$u, 'pct'=>round($u*100/$max), 'color'=>$palette[$i % count($palette)]);
		$i++;
	}
	return $out;
}

// Trả danh sách giá trị distinct của 1 khoá JSON trong more_information (loại null/rỗng).
function analytics_distinct_json($field){
	global $dbconn, $clsISO;
	if(!in_array($field, array('project_area','investor'), true)) return array(); // chỉ cho khoá JSON đã biết
	$pre = DB_PREFIX;
	$rows = $dbconn->GetAll("SELECT DISTINCT JSON_UNQUOTE(JSON_EXTRACT(more_information,'\$.{$field}')) AS r FROM {$pre}project WHERE is_trash=0 ORDER BY r ASC");
	$out = array();
	foreach((array)$rows as $x){
		$v = trim((string)$x['r']);
		if($v !== '' && strtolower($v) !== 'null') $out[] = $v;
	}
	return $out;
}

function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$stdio,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;

	$assign_list['analytics_access'] = analytics_can_access();
	if(!$assign_list['analytics_access']){
		$title_page = 'Dashboard Quản trị BOD - ' . PAGE_NAME;
		$assign_list["title_page"] = $title_page;
		return;
	}

	$pre = DB_PREFIX;
	$now = time();
	$startToday = strtotime(date('Y-m-d 00:00:00', $now));
	$sold_id = defined('_STOCK_STATUS_SOLD_ID') ? (int)_STOCK_STATUS_SOLD_ID : 32;
	$lock_id = defined('_STOCK_STATUS_LOCK_ID') ? (int)_STOCK_STATUS_LOCK_ID : 33;

	// ===== Bộ lọc (GET, whitelist/cast) =====
	$period = $stdio ? $stdio->GET('period', 'month') : 'month';
	if(!in_array($period, array('month','quarter','year'), true)) $period = 'month';
	$project_id = $stdio ? (int)$stdio->GET('project_id', 0) : 0;
	$region   = $stdio ? html_entity_decode(trim((string)$stdio->GET('region', '')), ENT_QUOTES) : '';
	$investor = $stdio ? html_entity_decode(trim((string)$stdio->GET('investor', '')), ENT_QUOTES) : '';
	$tab = $stdio ? $stdio->GET('tab', 'an-growth') : 'an-growth';
	if(!in_array($tab, array('an-growth','an-inventory','an-affiliate','an-health'), true)) $tab = 'an-growth';

	// Danh sách filter (cũng dùng để whitelist region/investor)
	$list_regions   = analytics_distinct_json('project_area');
	$list_investors = analytics_distinct_json('investor');
	$list_projects  = $dbconn->GetAll("SELECT project_id, title FROM {$pre}project WHERE is_trash=0 ORDER BY title ASC");
	$list_projects  = is_array($list_projects) ? $list_projects : array();
	if($region !== '' && !in_array($region, $list_regions, true)) $region = '';
	if($investor !== '' && !in_array($investor, $list_investors, true)) $investor = '';

	// Kỳ thời gian -> mốc bắt đầu
	if($period === 'year'){ $periodStart = strtotime(date('Y-01-01 00:00:00', $now)); $periodLabel = 'năm nay'; }
	elseif($period === 'quarter'){ $qm = (intdiv((int)date('n', $now)-1, 3))*3 + 1; $periodStart = strtotime(date('Y-', $now).sprintf('%02d', $qm).'-01 00:00:00'); $periodLabel = 'quý này'; }
	else { $periodStart = strtotime(date('Y-m-01 00:00:00', $now)); $periodLabel = 'tháng này'; }
	$periodStartStr = date('Y-m-d H:i:s', $periodStart);

	// Mảnh JOIN/WHERE cho ② (region/investor cần join project; project_id chỉ cần s.)
	$invJoin = ''; $invWhere = '';
	if($project_id > 0){ $invWhere .= " AND s.project_id={$project_id}"; }
	if($region !== '' || $investor !== ''){
		$invJoin = " JOIN {$pre}project p ON p.project_id=s.project_id";
		if($region !== '')   $invWhere .= " AND JSON_UNQUOTE(JSON_EXTRACT(p.more_information,'\$.project_area'))=".$dbconn->qstr($region);
		if($investor !== '') $invWhere .= " AND JSON_UNQUOTE(JSON_EXTRACT(p.more_information,'\$.investor'))=".$dbconn->qstr($investor);
	}
	$invActive = ($project_id > 0 || $region !== '' || $investor !== '');

	// ===== KPI người dùng (DAU/WAU/MAU = cửa sổ cố định, KHÔNG theo filter) =====
	$total_users     = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}member WHERE is_trash=0");
	$new_users_month = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}member WHERE is_trash=0 AND reg_date>={$periodStart}");
	$dau = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}stat_active_daily WHERE d=CURDATE()");
	$wau = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT user_id) FROM {$pre}stat_active_daily WHERE d>=CURDATE()-INTERVAL 6 DAY");
	$mau = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT user_id) FROM {$pre}stat_active_daily WHERE d>=CURDATE()-INTERVAL 29 DAY");
	$stickiness = $mau > 0 ? round($dau * 100 / $mau, 1) : 0;

	// ===== KPI quỹ hàng (áp filter ②) =====
	$total_units = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}stock s{$invJoin} WHERE s.is_trash=0 AND s.status_id>0{$invWhere}");
	$sold_units  = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}stock s{$invJoin} WHERE s.is_trash=0 AND s.status_id={$sold_id}{$invWhere}");
	$lock_units  = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}stock s{$invJoin} WHERE s.is_trash=0 AND s.status_id={$lock_id}{$invWhere}");
	$avail_units = $total_units - $sold_units - $lock_units; if($avail_units < 0) $avail_units = 0;
	$absorption  = $total_units > 0 ? round($sold_units * 100 / $total_units, 1) : 0;
	if($invActive){
		$total_projects = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT s.project_id) FROM {$pre}stock s{$invJoin} WHERE s.is_trash=0 AND s.status_id>0{$invWhere}");
	} else {
		$total_projects = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}project WHERE is_trash=0");
	}

	// ===== KPI affiliate (F1 theo kỳ) =====
	$total_affiliates = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}affiliate_accounts");
	$active_referrers = (int) $dbconn->GetOne("SELECT COUNT(DISTINCT referrer_member_id) FROM {$pre}affiliate_referrals");
	$f1_new_month     = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}affiliate_referrals WHERE created_at>='{$periodStartStr}'");
	$commission_month = (float) $dbconn->GetOne("SELECT COALESCE(SUM(commission_amount),0) FROM {$pre}affiliate_commissions WHERE created_at>='{$periodStartStr}'");
	$searches_today = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}log WHERE from_site='_user' AND type='search' AND reg_date>={$startToday}");

	$assign_list['kpi'] = array(
		'total_users'=>$total_users, 'new_users_month'=>$new_users_month,
		'total_projects'=>$total_projects, 'total_units'=>$total_units,
		'sold_units'=>$sold_units, 'lock_units'=>$lock_units, 'avail_units'=>$avail_units, 'absorption'=>$absorption,
		'total_affiliates'=>$total_affiliates, 'active_referrers'=>$active_referrers,
		'f1_new_month'=>$f1_new_month, 'commission_month'=>$commission_month,
		'searches_today'=>$searches_today, 'dau'=>$dau, 'wau'=>$wau, 'mau'=>$mau, 'stickiness'=>$stickiness,
	);

	// ===== ① User mới 12 tháng (bar) — trend, không theo filter =====
	$rows = $dbconn->GetAll("SELECT DATE_FORMAT(FROM_UNIXTIME(reg_date),'%Y-%m') AS ym, COUNT(*) AS n
		FROM {$pre}member WHERE is_trash=0 AND reg_date>=UNIX_TIMESTAMP(CURDATE()-INTERVAL 12 MONTH) GROUP BY ym ORDER BY ym");
	$rows = array_slice(is_array($rows)?$rows:array(), -12);
	$growth_bars = array();
	foreach($rows as $r){
		$growth_bars[] = array('m'=>substr($r['ym'],5,2), 'val'=>(int)$r['n']);
	}
	// Điểm dữ liệu CanvasJS (dùng chung ① User mới + ⑤ Tăng trưởng user)
	$growth_points = array();
	foreach($growth_bars as $b){ $growth_points[] = array('label'=>'T'.((int)$b['m']), 'y'=>(int)$b['val']); }
	$assign_list['growth_points'] = $growth_points;

	// MoM delta user mới (cho KPI sub + caption)
	$mom_text = ''; $mom_up = true; $gc = count($growth_bars);
	if($gc >= 2 && $growth_bars[$gc-2]['val'] > 0){
		$cur = $growth_bars[$gc-1]['val']; $prev = $growth_bars[$gc-2]['val'];
		$mp = round(($cur - $prev) * 100 / $prev); $mom_up = ($mp >= 0);
		$mom_text = ($mom_up ? '+' : '') . $mp . '% vs tháng trước';
	}
	$assign_list['mom_text'] = $mom_text;
	$assign_list['mom_up'] = $mom_up;

	// ===== ① DAU 30 ngày (bar) =====
	$rows = $dbconn->GetAll("SELECT d, COUNT(*) AS n FROM {$pre}stat_active_daily WHERE d>=CURDATE()-INTERVAL 29 DAY GROUP BY d ORDER BY d");
	$map = array(); foreach((array)$rows as $r){ $map[$r['d']] = (int)$r['n']; }
	$dau_points = array();
	for($k=29; $k>=0; $k--){
		$day = date('Y-m-d', strtotime("-{$k} day"));
		$n = isset($map[$day]) ? (int)$map[$day] : 0;
		$dau_points[] = array('label'=>date('d/m', strtotime($day)), 'y'=>$n);
	}
	$assign_list['dau_points'] = $dau_points;

	// ===== ① Retention D1/D7/D30 — đọc precompute stat_cohort theo TỪNG mốc; mốc nào chưa
	// precompute (cron chưa chạy / chạy dở) thì tính live (cùng công thức, cùng biên ngày). =====
	$cmap = array();
	$rows = $dbconn->GetAll("SELECT day_n, SUM(cohort_size) sz, SUM(retained) ret
		FROM {$pre}stat_cohort WHERE cohort_date >= CURDATE()-INTERVAL 90 DAY GROUP BY day_n");
	foreach((array)$rows as $r){ $cmap[(int)$r['day_n']] = array((int)$r['sz'], (int)$r['ret']); }
	$retention = array();
	foreach(array(1,7,30) as $nd){
		$nd = (int)$nd;
		if(isset($cmap[$nd])){
			$sz = $cmap[$nd][0]; $rt = $cmap[$nd][1];
		} else {
			$sz = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}member
				WHERE is_trash=0 AND reg_date>=UNIX_TIMESTAMP(CURDATE()-INTERVAL 90 DAY)
				  AND reg_date<UNIX_TIMESTAMP(CURDATE()-INTERVAL {$nd} DAY)");
			$rt = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}member m
				JOIN {$pre}stat_active_daily a ON a.user_id=m.profile_id AND a.d=DATE(FROM_UNIXTIME(m.reg_date))+INTERVAL {$nd} DAY
				WHERE m.is_trash=0 AND m.reg_date>=UNIX_TIMESTAMP(CURDATE()-INTERVAL 90 DAY)
				  AND m.reg_date<UNIX_TIMESTAMP(CURDATE()-INTERVAL {$nd} DAY)");
		}
		$retention[] = array('n'=>'D'.$nd, 'cohort'=>$sz, 'ret'=>$rt, 'pct'=>($sz>0 ? round($rt*100/$sz,1) : 0));
	}
	$assign_list['retention'] = $retention;

	// ===== ① Cơ cấu user theo gói _MF_PACKAGE (snapshot tổng, KHÔNG theo filter kỳ) =====
	// Map: VVIP=12020 · Pro=12019 · Free = tất cả còn lại (mặc định/chưa nâng cấp). Cùng tập "Tổng user".
	$pkg_pro_id  = defined('_MEMBER_PACKAGE_PRO_ID')  ? (int)_MEMBER_PACKAGE_PRO_ID  : 12019;
	$pkg_vvip_id = defined('_MEMBER_PACKAGE_VVIP_ID') ? (int)_MEMBER_PACKAGE_VVIP_ID : 12020;
	$pkg_vvip = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}member WHERE is_trash=0 AND package_id={$pkg_vvip_id}");
	$pkg_pro  = (int) $dbconn->GetOne("SELECT COUNT(*) FROM {$pre}member WHERE is_trash=0 AND package_id={$pkg_pro_id}");
	$pkg_free = $total_users - $pkg_vvip - $pkg_pro; if($pkg_free < 0) $pkg_free = 0;
	$pkg_base = $total_users > 0 ? $total_users : 1;
	$assign_list['pkg_tiers'] = array(
		array('key'=>'free', 'label'=>'Miễn phí (Free)',     'count'=>$pkg_free, 'pct'=>round($pkg_free*100/$pkg_base,2), 'color'=>'#8592a3', 'valcls'=>'',        'icon'=>'bx-user',  'sub'=>'chưa nâng cấp'),
		array('key'=>'pro',  'label'=>'Chuyên nghiệp (Pro)', 'count'=>$pkg_pro,  'pct'=>round($pkg_pro*100/$pkg_base,2),  'color'=>'#696cff', 'valcls'=>'is-pro',  'icon'=>'bx-medal', 'sub'=>'trả phí'),
		array('key'=>'vvip', 'label'=>'Tinh hoa (VVIP)',     'count'=>$pkg_vvip, 'pct'=>round($pkg_vvip*100/$pkg_base,2), 'color'=>'#f5b50a', 'valcls'=>'is-vvip', 'icon'=>'bx-crown', 'sub'=>'trả phí'),
	);

	// ===== ① Tăng trưởng user mới theo gói (12 tháng, stacked column) =====
	// 3 series (Free/Pro/VVIP) theo tháng cho CanvasJS stackedColumn.
	$grows = $dbconn->GetAll("SELECT DATE_FORMAT(FROM_UNIXTIME(reg_date),'%Y-%m') ym, COUNT(*) total,
		SUM(CASE WHEN package_id={$pkg_vvip_id} THEN 1 ELSE 0 END) vvip,
		SUM(CASE WHEN package_id={$pkg_pro_id} THEN 1 ELSE 0 END) pro
		FROM {$pre}member WHERE is_trash=0 AND reg_date>=UNIX_TIMESTAMP(CURDATE()-INTERVAL 12 MONTH)
		GROUP BY ym ORDER BY ym");
	$grows = array_slice(is_array($grows) ? $grows : array(), -12);
	$pkg_free_pts = array(); $pkg_pro_pts = array(); $pkg_vvip_pts = array();
	foreach($grows as $r){
		$lab = 'T'.((int)substr($r['ym'],5,2));
		$tot=(int)$r['total']; $gv=(int)$r['vvip']; $gp=(int)$r['pro']; $gf=$tot-$gv-$gp; if($gf<0) $gf=0;
		$pkg_free_pts[] = array('label'=>$lab, 'y'=>$gf);
		$pkg_pro_pts[]  = array('label'=>$lab, 'y'=>$gp);
		$pkg_vvip_pts[] = array('label'=>$lab, 'y'=>$gv);
	}
	$assign_list['pkg_free_pts'] = $pkg_free_pts;
	$assign_list['pkg_pro_pts']  = $pkg_pro_pts;
	$assign_list['pkg_vvip_pts'] = $pkg_vvip_pts;

	// ===== ② Hấp thụ theo dự án (top 10, áp filter) =====
	$rows = $dbconn->GetAll("SELECT s.project_id, p.title, COUNT(*) AS total,
		SUM(CASE WHEN s.status_id={$sold_id} THEN 1 ELSE 0 END) AS sold,
		SUM(CASE WHEN s.status_id={$lock_id} THEN 1 ELSE 0 END) AS lock_hold
		FROM {$pre}stock s LEFT JOIN {$pre}project p ON p.project_id=s.project_id
		WHERE s.is_trash=0 AND s.status_id>0{$invWhere} GROUP BY s.project_id, p.title ORDER BY total DESC LIMIT 10");
	$proj_rows = array();
	foreach((array)$rows as $r){
		$t = (int)$r['total']; $sd = (int)$r['sold']; $lk = (int)$r['lock_hold'];
		$av = $t - $sd - $lk; if($av < 0) $av = 0;
		$pct = $t > 0 ? round($sd*100/$t, 1) : 0;
		$proj_rows[] = array(
			'title'=>(trim((string)$r['title']) !== '' ? $r['title'] : ('#'.$r['project_id'])),
			'total'=>$t, 'sold'=>$sd, 'lock'=>$lk, 'avail'=>$av, 'pct'=>$pct,
			'bc'=>($pct>=80 ? 'success' : ($pct>=50 ? 'warning' : 'danger')),
		);
	}
	$assign_list['proj_rows'] = $proj_rows;

	// ===== ② Cơ cấu khu vực + chủ đầu tư (áp filter) =====
	$palette = array('#696cff','#03c3ec','#71dd37','#ffab00','#ff3e1d','#8592a3','#233446');
	$rows = $dbconn->GetAll("SELECT JSON_UNQUOTE(JSON_EXTRACT(p.more_information,'\$.project_area')) AS name, COUNT(*) AS units
		FROM {$pre}stock s JOIN {$pre}project p ON p.project_id=s.project_id
		WHERE s.is_trash=0 AND s.status_id>0{$invWhere} GROUP BY name ORDER BY units DESC LIMIT 6");
	$assign_list['region_rows'] = analytics_src_rows($rows, $palette);
	$rows = $dbconn->GetAll("SELECT JSON_UNQUOTE(JSON_EXTRACT(p.more_information,'\$.investor')) AS name, COUNT(*) AS units
		FROM {$pre}stock s JOIN {$pre}project p ON p.project_id=s.project_id
		WHERE s.is_trash=0 AND s.status_id>0{$invWhere} GROUP BY name ORDER BY units DESC LIMIT 6");
	$assign_list['investor_rows'] = analytics_src_rows($rows, $palette);

	// ===== ③ Affiliate: thế hệ F1/F2/F3 + BXH =====
	$clsRef = new AffiliateReferral();
	$edges = $clsRef->getEdges();
	$gen = $clsRef->computeGenerations($edges);
	$gt = $gen['totals'];
	$gen_points = array(
		array('label'=>'F1',  'y'=>(int)$gt[1]),
		array('label'=>'F2',  'y'=>(int)$gt[2]),
		array('label'=>'F3',  'y'=>(int)$gt[3]),
		array('label'=>'F4+', 'y'=>(int)$gt['4plus']),
	);
	$assign_list['gen_points'] = $gen_points;

	// Giới thiệu MỚI theo kỳ, tách F1/F2/F3 (từ by_month đã tính sẵn; so chuỗi 'YYYY-MM')
	$periodMonth = date('Y-m', $periodStart);
	$new_gen = array(1=>0, 2=>0, 3=>0, '4plus'=>0);
	foreach($gen['by_month'] as $ym => $b){
		if($ym >= $periodMonth){
			$new_gen[1] += (int)$b[1]; $new_gen[2] += (int)$b[2]; $new_gen[3] += (int)$b[3]; $new_gen['4plus'] += (int)$b['4plus'];
		}
	}
	$new_gen['total'] = $new_gen[1] + $new_gen[2] + $new_gen[3] + $new_gen['4plus'];
	$assign_list['new_gen'] = $new_gen;

	// BXH theo TỔNG mạng lưới: downline F1/F2/F3 của chính mỗi affiliate, top 8 theo tổng
	$children = $clsRef->childrenMap($edges);
	$dlAll = array();
	foreach(array_keys($children) as $root){
		$dl = $clsRef->downline($root, $children);
		if($dl['total'] > 0) $dlAll[$root] = $dl;
	}
	uasort($dlAll, function($a, $b){ return $b['total'] - $a['total']; });
	$topRoots = array_slice($dlAll, 0, 8, true);
	$nameMap = array();
	if(!empty($topRoots)){
		$inIds = implode(',', array_map('intval', array_keys($topRoots)));
		$nrows = $dbconn->GetAll("SELECT profile_id, full_name FROM {$pre}member WHERE profile_id IN({$inIds})");
		foreach((array)$nrows as $nr){ $nameMap[(int)$nr['profile_id']] = $nr['full_name']; }
	}
	$aff_top = array(); $rk = 0;
	foreach($topRoots as $root => $dl){
		$rk++;
		$name = (isset($nameMap[$root]) && trim((string)$nameMap[$root]) !== '') ? $nameMap[$root] : ('#'.$root);
		$aff_top[] = array(
			'rank'=>$rk, 'rankcls'=>($rk==1?'r1':($rk==2?'r2':($rk==3?'r3':'rn'))),
			'name'=>$name, 'f1'=>(int)$dl[1], 'f2'=>(int)$dl[2], 'f3'=>(int)$dl[3], 'total'=>(int)$dl['total'],
		);
	}
	$assign_list['aff_top'] = $aff_top;

	// ===== Filter state + lists cho view =====
	$assign_list['filters'] = array(
		'period'=>$period, 'period_label'=>$periodLabel,
		'project_id'=>$project_id, 'region'=>$region, 'investor'=>$investor, 'active'=>$invActive,
	);
	$assign_list['list_projects'] = $list_projects;
	$assign_list['list_regions'] = $list_regions;
	$assign_list['list_investors'] = $list_investors;
	$assign_list['active_tab'] = $tab;

	$title_page = 'Dashboard Quản trị BOD - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
?>