<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Learning Path Course — model                                    # ||
|| # Bảng nối lộ trình <-> khoá học (training), có thứ tự sort_order. # ||
|| #################################################################### ||
\*======================================================================*/
class LearningPathCourse extends dbBasic{
	function __construct() {
		$this->pkey = "id";
		$this->tbl  = DB_PREFIX."learning_path_course";
	}
	/** Các dòng khoá học của một lộ trình, theo thứ tự. */
	function getByPath($path_id){
		return $this->getAll("path_id='".intval($path_id)."' ORDER BY sort_order ASC, id ASC");
	}
	/** Khoá học đã có trong lộ trình chưa? (chống trùng — khớp UNIQUE) */
	function existsInPath($path_id, $training_id){
		return $this->countItem("path_id='".intval($path_id)."' AND training_id='".intval($training_id)."'") > 0;
	}
	/** sort_order kế tiếp trong phạm vi 1 lộ trình. */
	function getNextSort($path_id){
		global $dbconn;
		$path_id = intval($path_id);
		$res = $dbconn->GetAll("SELECT MAX(sort_order) AS mx FROM ".$this->tbl." WHERE path_id='{$path_id}'");
		return isset($res[0]['mx']) ? intval($res[0]['mx']) + 1 : 1;
	}
	/** Thêm 1 khoá vào cuối lộ trình. Trả false nếu trùng hoặc tham số sai. */
	function addCourse($path_id, $training_id, $is_required=1){
		$path_id = intval($path_id); $training_id = intval($training_id);
		if($path_id <= 0 || $training_id <= 0) return false;
		if($this->existsInPath($path_id, $training_id)) return false;
		return $this->insert(array(
			"path_id"     => $path_id,
			"training_id" => $training_id,
			"sort_order"  => $this->getNextSort($path_id),
			"is_required" => intval($is_required),
			"reg_date"    => time(),
		));
	}
	/** Lưu lại thứ tự theo mảng id dòng (từ jQuery UI sortable toArray). */
	function reorder($path_id, $orderArr){
		if(empty($orderArr) || !is_array($orderArr)) return false;
		$i = 1;
		foreach($orderArr as $id){
			$id = intval($id);
			if($id > 0){
				$this->updateOne($id, array("sort_order" => $i));
				++$i;
			}
		}
		return true;
	}
}
