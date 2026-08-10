<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| # WorldcupPick — Lượt dự đoán của user (mini game World Cup 2026)   # ||
\*======================================================================*/
class WorldcupPick extends dbBasic {
	function __construct(){
		$this->pkey = "pick_id";
		$this->tbl  = DB_PREFIX."worldcup_pick";
	}
	/** Lấy 1 dự đoán của user cho 1 trận (hoặc rỗng) */
	function getOnePick($match_id, $profile_id){
		return $this->getByCond("match_id='".(int)$match_id."' AND profile_id='".(int)$profile_id."' LIMIT 1");
	}
	/** Map match_id => pick row cho 1 user (merge ở tầng app) */
	function getMapByProfile($profile_id){
		$map = array();
		$rows = $this->getAll("profile_id='".(int)$profile_id."'", "pick_id, match_id, pick, points, is_correct");
		if(!empty($rows)){
			foreach($rows as $r){ $map[$r['match_id']] = $r; }
		}
		return $map;
	}
	/** Tổng hợp điểm/đúng/chơi của 1 user (chỉ tính trận đã chốt is_correct IS NOT NULL) */
	function aggregate($profile_id){
		$rows = $this->getAll("profile_id='".(int)$profile_id."' AND is_correct IS NOT NULL",
			"SUM(points) as pts, SUM(is_correct) as correct, COUNT(*) as played");
		if(empty($rows)){ return array('pts'=>0,'correct'=>0,'played'=>0); }
		return array(
			'pts'     => (int)$rows[0]['pts'],
			'correct' => (int)$rows[0]['correct'],
			'played'  => (int)$rows[0]['played']
		);
	}
	/** Streak hiện tại: chuỗi đoán đúng liên tiếp gần nhất (theo thứ tự giờ bóng lăn) */
	function currentStreak($profile_id){
		$rows = $this->getAll("profile_id='".(int)$profile_id."' AND is_correct IS NOT NULL ORDER BY kickoff ASC",
			"is_correct");
		$streak = 0;
		if(!empty($rows)){
			foreach($rows as $r){
				if((int)$r['is_correct'] === 1){ $streak++; } else { $streak = 0; }
			}
		}
		return $streak;
	}
}
