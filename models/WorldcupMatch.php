<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| # WorldcupMatch — Trận đấu mini game Dự đoán World Cup 2026         # ||
\*======================================================================*/
class WorldcupMatch extends dbBasic {
	function __construct(){
		$this->pkey = "match_id";
		$this->tbl  = DB_PREFIX."worldcup_match";
	}
	/** Tất cả trận chưa xóa, sắp theo giờ bóng lăn */
	function getAllActive(){
		return $this->getAll("is_trash=0 ORDER BY kickoff ASC");
	}
	/** Cộng/trừ 1 phiếu cho 1 cửa (H/D/A). $delta = +1 hoặc -1 */
	function bumpVote($match_id, $side, $delta){
		$col = ($side=='H') ? 'vote_h' : (($side=='A') ? 'vote_a' : 'vote_d');
		$delta = (int)$delta;
		// GREATEST để phiếu không bao giờ âm; dùng updateOne với biểu thức SQL
		return $this->updateOne((int)$match_id, "`{$col}`=GREATEST(0,`{$col}`+({$delta})), upd_date='".time()."'");
	}
}
