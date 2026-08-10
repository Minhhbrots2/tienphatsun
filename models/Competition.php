<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Thi đua định danh — quản lý các CHƯƠNG TRÌNH thi đua (điểm → hạng).
 * Lưu ở Configuration key 'competition_programs' (JSON list) — KHÔNG bảng riêng, không migration.
 * Bảng xếp hạng tính real-time từ billing (xem engine ở module competition/report), model này chỉ lo CẤU HÌNH.
 */
class Competition{
	var $cfg_key = 'competition_programs';
	const RANK_CACHE_TTL = 900; // TTL backstop (giây) — 15 phút; version-bump lo tươi tức thì khi có GD mới

	// Whitelist CỘT giá trị billing cho phép làm căn cứ dò bậc (chống nhận cột tuỳ ý từ POST → SQL injection)
	function valueFields(){
		return array(
			'totalgrand'        => 'Tổng tiền giao dịch',
			'totalgrand_share'  => 'Doanh số ghi nhận',
		);
	}
	// ĐIỀU KIỆN GHI NHẬN DOANH SỐ (1 dropdown admin). Mỗi lựa chọn tự kèm mốc ngày để đưa GD vào kỳ.
	function revenueConditions(){
		return array(
			'both'     => 'Ký VBTT hoặc HĐMB (theo ngày ký)',
			'contract' => 'Chỉ ký HĐMB (theo ngày ký HĐMB)',
			'agree'    => 'Chỉ ký VBTT (theo ngày ký VBTT)',
			'closed'   => 'Chỉ cần chốt / phát sinh (theo ngày cọc)',
		);
	}
	// Chuẩn hoá + map giá trị cũ: date_field cũ contract_date→contract, agree_date→agree, deposit(_date)→closed, sign→both.
	function normalizeRevenueCondition($v){
		if(in_array($v, array('both', 'contract', 'agree', 'closed'), true)) return $v;
		if($v === 'contract_date') return 'contract';
		if($v === 'agree_date')    return 'agree';
		if($v === 'deposit' || $v === 'deposit_date') return 'closed';
		return 'both';
	}
	// Điều kiện của 1 chương trình (đọc revenue_condition mới, fallback date_field cũ để tương thích ngược).
	function conditionOf($program){
		$v = isset($program['revenue_condition']) ? $program['revenue_condition']
			: (isset($program['date_field']) ? $program['date_field'] : 'both');
		return $this->normalizeRevenueCondition($v);
	}
	// WHERE tính điểm theo điều kiện + rơi vào kỳ [start,end]:
	//   closed   → chỉ cần ngày cọc (deposit_date) ∈ kỳ (không cần trạng thái ký).
	//   contract → HĐMB đã ký (contract_status_id>0, gồm cả 132 chờ ký) & contract_date ∈ kỳ.
	//   agree    → VBTT đã ký (agree_status_id>0) & agree_date ∈ kỳ.
	//   both     → 1 trong 2 mốc ký thoả (dự án chỉ có HĐMB vẫn tính).
	// Mỗi billing là 1 dòng ⇒ cộng điểm 1 lần dù thoả 2 nhánh. Mọi giá trị đã ép (int) ⇒ an toàn SQL.
	function qualifyWhere($program, $start, $end, $alias = ''){
		$start = (int) $start; $end = (int) $end;
		$p = ($alias !== '') ? "`{$alias}`." : '';
		$c = $this->conditionOf($program);
		if($c === 'closed'){
			return "{$p}`deposit_date`>={$start} AND {$p}`deposit_date`<={$end}";
		}
		$parts = array();
		if($c === 'contract' || $c === 'both'){
			$parts[] = "({$p}`contract_status_id`>0 AND {$p}`contract_date`>={$start} AND {$p}`contract_date`<={$end})";
		}
		if($c === 'agree' || $c === 'both'){
			$parts[] = "({$p}`agree_status_id`>0 AND {$p}`agree_date`>={$start} AND {$p}`agree_date`<={$end})";
		}
		return '('.implode(' OR ', $parts).')';
	}
	// Biểu thức ngày (có alias billing) để hiển thị/sắp xếp trong popup chi tiết, theo điều kiện.
	function dateExprSql($program, $alias = 'b'){
		$p = "`{$alias}`.";
		$c = $this->conditionOf($program);
		if($c === 'closed')   return "{$p}`deposit_date`";
		if($c === 'contract') return "{$p}`contract_date`";
		if($c === 'agree')    return "{$p}`agree_date`";
		return "IF({$p}`contract_status_id`>0 AND {$p}`contract_date`>0, {$p}`contract_date`, {$p}`agree_date`)";
	}
	// Giá trị điểm của MỘT sale (co-sale) = giá trị billing (theo value_field) × tỷ lệ chia (%). $vexpr = biểu thức giá trị billing alias b.
	function saleValueSql($vexpr){
		return "ROUND(({$vexpr}) * IF(`bs`.`share_ratio`>0, `bs`.`share_ratio`, 100) / 100)";
	}
	function periodTypes(){
		return array('year'=>'Cả năm','quarter'=>'Quý','month'=>'Tháng','range'=>'Khoảng ngày');
	}
	// Suy khoảng thời gian [start,end] theo kỳ. Mốc tham chiếu = ngày bắt đầu nhập tay (nếu có), else hiện tại.
	// 'range' dùng nguyên start/end nhập tay; year/quarter/month tự tính trọn kỳ.
	function resolvePeriod($type, $start, $end){
		$start = (int) $start; $end = (int) $end;
		if($type == 'range') return array($start, $end);
		$ref = ($start > 0) ? $start : time();
		$y = (int) date('Y', $ref);
		if($type == 'year'){
			return array(strtotime("{$y}-01-01 00:00:00"), strtotime("{$y}-12-31 23:59:59"));
		}
		if($type == 'quarter'){
			$q = (int) ceil(((int) date('n', $ref)) / 3);
			$sm = ($q - 1) * 3 + 1;
			$s = strtotime(sprintf('%d-%02d-01 00:00:00', $y, $sm));
			$e = strtotime('last day of this month 23:59:59', strtotime(sprintf('%d-%02d-01 00:00:00', $y, $q * 3)));
			return array($s, $e);
		}
		if($type == 'month'){
			$m = (int) date('n', $ref);
			$s = strtotime(sprintf('%d-%02d-01 00:00:00', $y, $m));
			$e = strtotime('last day of this month 23:59:59', $s);
			return array($s, $e);
		}
		return array($start, $end);
	}

	function getPrograms(){
		global $clsISO;
		$clsISO = is_object($clsISO) ? $clsISO : new ISO();
		$clsConfiguration = new Configuration();
		$raw = $clsConfiguration->getValue($this->cfg_key);
		$list = $clsISO->to_array_json($raw);
		$list = is_array($list) ? $list : array();
		return $this->sortByOrder($list);
	}
	function getProgram($id){
		$id = (int) $id;
		foreach($this->getPrograms() as $p){
			if((int) $p['id'] == $id) return $p;
		}
		return array();
	}
	// Mã hash cho URL đẹp (/chuong-trinh-{hash}). md5 của id → chuỗi 32 hex, không lộ id tuần tự.
	function programHash($id){
		return md5((string) (int) $id);
	}
	// Tra chương trình theo hash (md5 không đảo ngược được → duyệt danh sách, so md5(id)).
	function getProgramByHash($hash){
		$hash = strtolower(trim($hash));
		if($hash === '') return array();
		foreach($this->getPrograms() as $p){
			if($this->programHash($p['id']) === $hash) return $p;
		}
		return array();
	}
	function savePrograms($list){
		$clsConfiguration = new Configuration();
		$clsConfiguration->updateValue($this->cfg_key, json_encode(array_values($list), JSON_UNESCAPED_UNICODE));
		return true;
	}
	// Thêm mới / cập nhật 1 chương trình (id=0 => thêm mới, cấp id kế tiếp). Trả về id.
	function saveProgram($data){
		$list = $this->getPrograms();
		$id = (int) (isset($data['id']) ? $data['id'] : 0);
		if($id <= 0){
			$max = 0;
			foreach($list as $p){ if((int) $p['id'] > $max) $max = (int) $p['id']; }
			$id = $max + 1;
			$data['id'] = $id;
			$list[] = $data;
		} else {
			$found = false;
			foreach($list as $k => $p){
				if((int) $p['id'] == $id){ $list[$k] = $data; $found = true; break; }
			}
			if(!$found){ $list[] = $data; }
		}
		$this->savePrograms($list);
		return $id;
	}
	function deleteProgram($id){
		$id = (int) $id;
		$list = $this->getPrograms();
		$out = array();
		foreach($list as $p){ if((int) $p['id'] != $id) $out[] = $p; }
		$this->savePrograms($out);
		return true;
	}

	// Chuẩn hoá + LỌC AN TOÀN dữ liệu 1 chương trình từ POST (chỉ nhận field whitelist, ép kiểu số).
	function sanitize($post){
		$value_field = isset($post['value_field']) ? $post['value_field'] : 'totalgrand_share';
		if(!isset($this->valueFields()[$value_field])) $value_field = 'totalgrand_share';
		$revenue_condition = $this->normalizeRevenueCondition(isset($post['revenue_condition']) ? $post['revenue_condition'] : 'both');
		$period_type = isset($post['period_type']) ? $post['period_type'] : 'year';
		if(!isset($this->periodTypes()[$period_type])) $period_type = 'year';
		# Thang điểm
		$tiers = array();
		if(!empty($post['tier_from']) && is_array($post['tier_from'])){
			foreach($post['tier_from'] as $i => $from){
				$tiers[] = array(
					'from'      => (float) $from,
					'to'        => (float) (isset($post['tier_to'][$i]) ? $post['tier_to'][$i] : 0),
					'exclusive' => (float) (isset($post['tier_excl'][$i]) ? $post['tier_excl'][$i] : 0),
					'cross'     => (float) (isset($post['tier_cross'][$i]) ? $post['tier_cross'][$i] : 0),
					'note'      => isset($post['tier_note'][$i]) ? trim($post['tier_note'][$i]) : '',
				);
			}
		}
		# Hạng
		$ranks = array();
		if(!empty($post['rank_from']) && is_array($post['rank_from'])){
			foreach($post['rank_from'] as $i => $from){
				$name = isset($post['rank_name'][$i]) ? trim($post['rank_name'][$i]) : '';
				if($name === '') continue;
				$ranks[] = array(
					'from'   => (float) $from,
					'name'   => $name,
					'reward' => isset($post['rank_reward'][$i]) ? trim($post['rank_reward'][$i]) : '',
				);
			}
		}
		# Sắp hạng theo ngưỡng tăng dần để dò từ cao xuống
		usort($ranks, function($a,$b){ return ($a['from'] <=> $b['from']); });
		$dept_ids = array();
		if(!empty($post['department_ids']) && is_array($post['department_ids'])){
			foreach($post['department_ids'] as $d){ $d = (int) $d; if($d > 0) $dept_ids[] = $d; }
		}
		$visibility = (isset($post['visibility']) && $post['visibility'] === 'department') ? 'department' : 'all';
		$color = isset($post['color']) ? trim($post['color']) : '';
		if($color !== '' && !preg_match('/^#[0-9a-fA-F]{3,8}$/', $color)) $color = ''; // chỉ nhận mã hex
		# Gradient nền board: color = mốc đầu, color2 = mốc cuối, angle = góc, animate = có chạy động không
		$color2 = isset($post['color2']) ? trim($post['color2']) : '';
		if($color2 !== '' && !preg_match('/^#[0-9a-fA-F]{3,8}$/', $color2)) $color2 = '';
		$gradient_angle = (int) (isset($post['gradient_angle']) ? $post['gradient_angle'] : 135);
		if($gradient_angle < 0) $gradient_angle = 0;
		if($gradient_angle > 360) $gradient_angle = 360;
		$gradient_animate = !empty($post['gradient_animate']) ? 1 : 0;
		return array(
			'id'                => (int) (isset($post['id']) ? $post['id'] : 0),
			'name'              => isset($post['name']) ? trim($post['name']) : '',
			'status'            => (int) (isset($post['status']) ? $post['status'] : 1),
			'order_no'          => (int) (isset($post['order_no']) ? $post['order_no'] : 0),
			'color'             => $color,
			'color2'            => $color2,
			'gradient_angle'    => $gradient_angle,
			'gradient_animate'  => $gradient_animate,
			'icon'              => isset($post['icon']) ? trim($post['icon']) : '',
			'visibility'        => $visibility,
			'period_type'       => $period_type,
			'start_date'        => (int) (isset($post['start_date']) ? $post['start_date'] : 0),
			'end_date'          => (int) (isset($post['end_date']) ? $post['end_date'] : 0),
			'department_ids'    => $dept_ids,
			'include_children'  => (int) (isset($post['include_children']) ? $post['include_children'] : 1),
			'value_field'       => $value_field,
			'revenue_condition' => $revenue_condition,
			'tiers'             => $tiers,
			'ranks'             => $ranks,
		);
	}
	// Sắp chương trình theo order_no tăng dần (khi hiển thị nhiều bảng).
	function sortByOrder($list){
		usort($list, function($a,$b){
			$ao = (int) (isset($a['order_no']) ? $a['order_no'] : 0);
			$bo = (int) (isset($b['order_no']) ? $b['order_no'] : 0);
			if($ao == $bo) return ((int)(isset($a['id'])?$a['id']:0)) <=> ((int)(isset($b['id'])?$b['id']:0));
			return $ao <=> $bo;
		});
		return $list;
	}
	// Người xem có được thấy chương trình không (visibility). 'all' → ai cũng thấy; 'department' → chỉ người thuộc phòng ban tham gia (+ phòng con).
	function visibleTo($program, $oneProfile){
		$vis = isset($program['visibility']) ? $program['visibility'] : 'all';
		if($vis !== 'department') return true;
		$dept_ids = isset($program['department_ids']) ? array_map('intval', $program['department_ids']) : array();
		if(empty($dept_ids)) return true;
		$my_dept = (int) (isset($oneProfile['department_id']) ? $oneProfile['department_id'] : 0);
		if(in_array($my_dept, $dept_ids)) return true;
		$my_list = isset($oneProfile['list_department_id']) ? (string) $oneProfile['list_department_id'] : '';
		foreach($dept_ids as $did){
			if(strpos($my_list, "|{$did}|") !== false) return true;
		}
		return false;
	}
	// Chi tiết các giao dịch tạo điểm của 1 người trong 1 chương trình (cho popup "xem chi tiết").
	function pointBreakdown($program, $staff_id){
		global $dbconn,$clsISO;
		$out = array();
		$staff_id = (int) $staff_id;
		if($staff_id <= 0 || empty($program)) return $out;
		$period = $this->resolvePeriod(
			isset($program['period_type']) ? $program['period_type'] : 'year',
			isset($program['start_date']) ? $program['start_date'] : 0,
			isset($program['end_date']) ? $program['end_date'] : 0
		);
		$start = (int) $period[0]; $end = (int) $period[1];
		if($start <= 0 || $end <= 0) return $out;
		$where = $this->qualifyWhere($program, $start, $end, 'b');
		$dateExpr = $this->dateExprSql($program, 'b');   // ngày hiển thị theo điều kiện, alias billing
		$saleVal = $this->coSaleValueSql($program);   // nhánh co-sale (có bs)
		$soloVal = $this->soloValueSql($program);     // nhánh fallback (chỉ b, KHÔNG có bs)
		// CO-SALE: phần điểm của riêng người này (share chia), + fallback GD không có dòng billing_sale.
//		$dbconn->debug=true;
		$sql = "SELECT `stock_code`, `val`, `billing_source_id`, `d` FROM (
				SELECT `b`.`stock_code` AS `stock_code`, {$saleVal} AS `val`, `b`.`billing_source_id` AS `billing_source_id`, {$dateExpr} AS `d`
				FROM `default_billing_sale` `bs`
				JOIN `default_billing` `b` ON `b`.`billing_id`=`bs`.`billing_id`
				WHERE `b`.`is_trash`=0 AND `b`.`is_cancel`=0 AND `bs`.`staff_id`={$staff_id} AND {$where}
				UNION ALL
				SELECT `b`.`stock_code` AS `stock_code`, {$soloVal} AS `val`, `b`.`billing_source_id` AS `billing_source_id`, {$dateExpr} AS `d`
				FROM `default_billing` `b`
				WHERE `b`.`is_trash`=0 AND `b`.`is_cancel`=0 AND `b`.`staff_id`={$staff_id} AND {$where}
				  AND NOT EXISTS(SELECT 1 FROM `default_billing_sale` `bs2` WHERE `bs2`.`billing_id`=`b`.`billing_id`)
			) `x`
			ORDER BY `d` ASC";
		$rows = $dbconn->GetAll($sql);
//		$clsISO->print_pre($rows);die;
		if(!empty($rows)){
			foreach($rows as $r){
				$sc = $this->scoreOfBilling($program, $r['val'], $r['billing_source_id']);
				if($sc == 0) continue;
				$out[] = array(
					'stock_code' => $r['stock_code'],
					'value'      => (float) $r['val'],
					'type'       => ((int) $r['billing_source_id'] == _BILLING_RESOURCE_F1_ID) ? 'Độc quyền' : 'Quỹ chéo',
					'date'       => (int) $r['d'],
					'score'      => $sc * 1,
				);
			}
		}
		return $out;
	}
	// Trả tên hạng theo tổng điểm (dò từ ngưỡng cao xuống). Không đạt ngưỡng nào → ''.
	function rankOf($program, $score){
		$score = (float) $score;
		$ranks = isset($program['ranks']) ? $program['ranks'] : array();
		$hit = '';
		foreach($ranks as $r){
			if($score >= (float) $r['from']) $hit = $r; // ranks đã sort tăng dần ⇒ cái cuối thoả = cao nhất
		}
		return $hit;
	}

	// Biểu thức GIÁ TRỊ billing gross (số, alias b). Chỉ còn 'totalgrand' dùng nhánh này;
	// 'totalgrand_share' cần alias `bs` nên xử lý riêng ở coSaleValueSql()/soloValueSql().
	function valueExpr($field, $a = 'b'){
		return "`{$a}`.`totalgrand`";
	}

	// Giá trị điểm của MỘT dòng CO-SALE (có alias `bs` + `b`).
	//   totalgrand_share → `share_value` ĐÃ là phần chia của sale này ⇒ dùng THẲNG, KHÔNG nhân lại share_ratio (tránh chia đôi điểm).
	//   field khác (mức-billing) → nhân tỷ lệ chia share_ratio của sale.
	function coSaleValueSql($program){
		$field = isset($program['value_field']) ? $program['value_field'] : 'totalgrand_share';
		if($field === 'totalgrand_share'){
			return "ROUND(`bs`.`share_value`)";
		}
		return $this->saleValueSql($this->valueExpr($field, 'b'));
	}
	// Giá trị điểm của GD KHÔNG có dòng billing_sale (chỉ alias `b`) — sale chính hưởng 100%.
	//   totalgrand_share → không có billing_sale ⇒ doanh số ghi nhận = trọn `totalgrand` của GD.
	function soloValueSql($program){
		$field = isset($program['value_field']) ? $program['value_field'] : 'totalgrand_share';
		if($field === 'totalgrand_share'){
			return "`b`.`totalgrand`";
		}
		return $this->valueExpr($field, 'b');
	}

	// Điểm 1 giao dịch theo thang bậc: value (VND) → tỷ → dò bậc → điểm theo loại (Độc Quyền/Quỹ Chéo).
	function scoreOfBilling($program, $value_vnd, $billing_source_id){
		$ty = (float) $value_vnd / 1000000000;
		$key = ((int) $billing_source_id == _BILLING_RESOURCE_F1_ID) ? 'exclusive' : 'cross';
		foreach((isset($program['tiers']) ? $program['tiers'] : array()) as $t){
			$from = (float) $t['from'];
			$to   = (float) $t['to'];
			if($ty >= $from && ($to == 0 || $ty < $to)){
				return (float) (isset($t[$key]) ? $t[$key] : 0);
			}
		}
		return 0;
	}

	// Danh sách profile_id tham gia: nhân sự thuộc các phòng ban đã chọn (+ phòng con nếu bật), đang làm việc.
	function participantIds($program){
		global $dbconn;
		$dept_ids = isset($program['department_ids']) ? $program['department_ids'] : array();
		if(empty($dept_ids)) return array();
		$dept_ids = array_map('intval', $dept_ids);
		$conds = array();
		$conds[] = "`department_id` IN (".implode(',', $dept_ids).")";
		if(!empty($program['include_children'])){
			foreach($dept_ids as $did){
				$conds[] = "`list_department_id` LIKE '%|{$did}|%'";
			}
		}
		$cond = "`is_trash`=0 AND `is_active`=1 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		$cond .= " AND `".( 'profile_id' )."`<>'"._PROFILE_PARTNER_ID."'";
		$cond .= " AND (".implode(' OR ', $conds).")";
		$rows = $dbconn->GetAll("SELECT `profile_id` FROM `default_profile` WHERE {$cond}");
		$ids = array();
		if(!empty($rows)){ foreach($rows as $r){ $ids[] = (int) $r['profile_id']; } }
		return $ids;
	}

	// Bảng xếp hạng tổng hợp (real-time): quét billing trong kỳ theo mốc ngày, cộng điểm cho người bán.
	// Trả list [{profile_id, score}] sắp giảm dần (chỉ tính điểm; tên/hạng gắn ở tầng hiển thị).
	function computeScores($program){
		global $dbconn;
		$out = array();
		$ids = $this->participantIds($program);
		if(empty($ids)) return $out;
		$period = $this->resolvePeriod(
			isset($program['period_type']) ? $program['period_type'] : 'year',
			isset($program['start_date']) ? $program['start_date'] : 0,
			isset($program['end_date']) ? $program['end_date'] : 0
		);
		$start = (int) $period[0];
		$end   = (int) $period[1];
		if($start <= 0 || $end <= 0 || $end < $start) return $out;
		$where = $this->qualifyWhere($program, $start, $end, 'b');
		$in = implode(',', array_map('intval', $ids));
		$saleVal = $this->coSaleValueSql($program);   // nhánh co-sale (có bs)
		$soloVal = $this->soloValueSql($program);     // nhánh fallback (chỉ b, KHÔNG có bs)
		// CO-SALE: mỗi sale (chính + phụ) trong default_billing_sale cộng điểm trên PHẦN CHIA của mình.
		// GD không có dòng billing_sale (số ít) → fallback: người bán chính + full giá trị.
		$sql = "SELECT `staff_id`, `val`, `billing_source_id` FROM (
				SELECT `bs`.`staff_id` AS `staff_id`, {$saleVal} AS `val`, `b`.`billing_source_id` AS `billing_source_id`
				FROM `default_billing_sale` `bs`
				JOIN `default_billing` `b` ON `b`.`billing_id`=`bs`.`billing_id`
				WHERE `b`.`is_trash`=0 AND `b`.`is_cancel`=0 AND `bs`.`staff_id` IN ({$in}) AND {$where}
				UNION ALL
				SELECT `b`.`staff_id` AS `staff_id`, {$soloVal} AS `val`, `b`.`billing_source_id` AS `billing_source_id`
				FROM `default_billing` `b`
				WHERE `b`.`is_trash`=0 AND `b`.`is_cancel`=0 AND `b`.`staff_id` IN ({$in}) AND {$where}
				  AND NOT EXISTS(SELECT 1 FROM `default_billing_sale` `bs2` WHERE `bs2`.`billing_id`=`b`.`billing_id`)
			) `x`";
		$rows = $dbconn->GetAll($sql);
		$scores = array();
		if(!empty($rows)){
			foreach($rows as $r){
				$sid = (int) $r['staff_id'];
				$sc = $this->scoreOfBilling($program, $r['val'], $r['billing_source_id']);
				if($sc == 0) continue;
				if(!isset($scores[$sid])) $scores[$sid] = 0;
				$scores[$sid] += $sc;
			}
		}
		// Đưa cả người tham gia CHƯA có điểm vào (score 0) để bảng đầy đủ; sắp giảm dần
		foreach($ids as $sid){ if(!isset($scores[$sid])) $scores[$sid] = 0; }
		arsort($scores);
		foreach($scores as $sid => $sc){
			$out[] = array('profile_id' => $sid, 'score' => round($sc, 2) * 1);
		}
		return $out;
	}

	// Bảng xếp hạng ĐÃ gắn tên/avatar/phòng/hạng — CÓ CACHE (dùng chung trang front + block dashboard).
	// Cache bản đầy đủ (cap 100) một lần; caller cắt theo $limit → block(10) và trang(50) chung 1 entry.
	function rankingWithProfiles($program, $limit = 50){
		if(empty($program)) return array();
		return array_slice($this->rankingCached($program), 0, (int) $limit);
	}
	// Cache-aside qua lớp Cache (remember). Key nhúng: id + hash cấu hình (admin sửa tiers/kỳ/phòng → tự mới)
	// + version toàn cục (bump khi GD thay đổi → tươi tức thì). TTL là chốt chặn cuối. Fail-soft: Redis chết → dựng thẳng.
	function rankingCached($program){
		$pid = (int) (isset($program['id']) ? $program['id'] : 0);
		$cfg = substr(md5(json_encode($program)), 0, 10);
		$ver = $this->cacheVersion();
		$key = "competition:rank:{$pid}:{$cfg}:{$ver}";
		try {
			$clsCache = new Cache();
			return $clsCache->remember($key, function() use ($program){
				return $this->buildRanking($program, 1000);
			}, self::RANK_CACHE_TTL);
		} catch(\Throwable $e){
			return $this->buildRanking($program, 1000);
		}
	}
	// Version cache hiện tại (get đọc đúng khoá increment ghi — dtkahl lưu key nguyên văn). Thiếu → '0'. Fail-soft.
	function cacheVersion(){
		try {
			$clsCache = new Cache();
			$v = $clsCache->get('competition:ver', '0');
			return (is_array($v) || $v === '') ? '0' : (string) $v;
		} catch(\Throwable $e){ return '0'; }
	}
	// Vô hiệu TOÀN BỘ cache bảng xếp hạng — gọi khi GD thay đổi (thêm/sửa/huỷ/trash). INCR O(1), fail-soft.
	public static function bumpCache(){
		try { $c = new Cache(); $c->increment('competition:ver'); } catch(\Throwable $e){}
	}
	// Dựng bảng xếp hạng THẬT (query billing + profile) — chỉ chạy khi cache miss.
	function buildRanking($program, $limit = 100){
		$ranking = array();
		if(empty($program)) return $ranking;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		$scores = $this->computeScores($program);
		$scores = array_slice($scores, 0, (int) $limit);
		if(empty($scores)) return $ranking;
		$ids = array();
		foreach($scores as $s){ $ids[] = (int) $s['profile_id']; }
		$profiles = array();
		$rows = $clsProfile->getAll("`profile_id` IN (".implode(',', $ids).")", "`profile_id`,`full_name`,`avatar`,`department_id`,`more_information`");
		if(!empty($rows)){ foreach($rows as $r){ $profiles[(int) $r['profile_id']] = $r; } }
		$stt = 1;
		foreach($scores as $s){
			$pid = (int) $s['profile_id'];
			$pf = isset($profiles[$pid]) ? $profiles[$pid] : array();
			$rk = $this->rankOf($program, $s['score']);
			$ranking[] = array(
				'stt'        => $stt,
				'profile_id' => $pid,
				'name'       => isset($pf['full_name']) ? $pf['full_name'] : '',
				'avatar'     => $clsProfile->getAvatar($pid, $pf),
				'dept'       => (isset($pf['department_id']) && $pf['department_id'] > 0) ? $clsProperty->getTitle($pf['department_id']) : '',
				'score'      => $s['score'] * 1,
				'rank_name'  => is_array($rk) ? $rk['name'] : '',
				'reward'     => is_array($rk) ? $rk['reward'] : '',
			);
			$stt++;
		}
		return $ranking;
	}

	// Chuỗi CSS gradient nền board. Bỏ trống color2 → tự sinh mốc đậm hơn từ color để vẫn thành gradient.
	function gradientCss($program){
		$c1 = (isset($program['color']) && $program['color'] !== '') ? $program['color'] : '#c0392b';
		$c2 = (isset($program['color2']) && $program['color2'] !== '') ? $program['color2'] : $this->shadeColor($c1, -28);
		$ang = isset($program['gradient_angle']) ? (int) $program['gradient_angle'] : 135;
		return "linear-gradient({$ang}deg, {$c1}, {$c2})";
	}
	// Board có bật hiệu ứng gradient chạy động không.
	function isGradientAnimated($program){
		return !empty($program['gradient_animate']) ? 1 : 0;
	}
	// Làm đậm (percent âm) / nhạt (dương) một mã hex — dùng tự sinh mốc gradient thứ 2 khi admin bỏ trống.
	function shadeColor($hex, $percent){
		$hex = ltrim($hex, '#');
		if(strlen($hex) == 3){ $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2]; }
		if(strlen($hex) < 6) return '#'.$hex;
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
		$adj = function($c) use ($percent){
			$c = $c + ($c * $percent / 100);
			return max(0, min(255, (int) round($c)));
		};
		return sprintf('#%02x%02x%02x', $adj($r), $adj($g), $adj($b));
	}
}
?>
