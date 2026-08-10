<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| # WorldcupTeam — Đội tuyển cho mini game Dự đoán World Cup 2026     # ||
\*======================================================================*/
class WorldcupTeam extends dbBasic {
	function __construct(){
		$this->pkey = "team_id";
		$this->tbl  = DB_PREFIX."worldcup_team";
	}
	/** Trả map team_id => row (để merge ở tầng app, tránh JOIN SQL) */
	function getMap(){
		$map = array();
		$rows = $this->getAll("is_trash=0", "team_id, code, name, short, group_name");
		if(!empty($rows)){
			foreach($rows as $r){ $map[$r['team_id']] = $r; }
		}
		return $map;
	}
	/** Danh sách đội cho dropdown admin */
	function getListActive(){
		return $this->getAll("is_trash=0 ORDER BY order_no ASC, name ASC",
			"team_id, code, name, short, group_name");
	}
}
