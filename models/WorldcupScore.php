<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| # WorldcupScore — BXH (cache điểm) mini game World Cup 2026         # ||
\*======================================================================*/
class WorldcupScore extends dbBasic {
	function __construct(){
		$this->pkey = "profile_id";
		$this->tbl  = DB_PREFIX."worldcup_score";
	}
	/** Upsert điểm cho 1 user (profile_id là PK, không AUTO_INCREMENT) */
	function upsert($profile_id, $pts, $correct, $played, $streak){
		$profile_id = (int)$profile_id;
		$data = array(
			'total_points'  => (int)$pts,
			'total_correct' => (int)$correct,
			'total_played'  => (int)$played,
			'streak'        => (int)$streak,
			'upd_date'      => time()
		);
		$exists = $this->countItem("profile_id='{$profile_id}'");
		if($exists > 0){
			return $this->updateOne($profile_id, $data);
		}
		$data['profile_id'] = $profile_id;
		return $this->insert($data);
	}
	/** Lấy điểm 1 user (hoặc mặc định 0) */
	function getScore($profile_id){
		$one = $this->getByCond("profile_id='".(int)$profile_id."' LIMIT 1");
		if(empty($one)){
			return array('total_points'=>0,'total_correct'=>0,'total_played'=>0,'streak'=>0);
		}
		return $one;
	}
	/** Hạng của user = số người điểm cao hơn + 1 */
	function getRank($profile_id){
		global $clsISO;
		$pts = (int)$this->getOneField('total_points', (int)$profile_id);
		//$above = $this->countItem("total_points > '{$pts}'");
		$tmp = $this->getAll("total_points > '{$pts}' GROUP BY total_points");
		$above = !empty($tmp) ? count($tmp) : 0;
		
		return $above + 1;
	}
	/** Top N cho BXH */
	function getTop($limit = 50){
		$limit = (int)$limit;
		return $this->getAll("1=1 ORDER BY total_points DESC, total_correct DESC, upd_date ASC LIMIT 0, {$limit}");
	}
}
