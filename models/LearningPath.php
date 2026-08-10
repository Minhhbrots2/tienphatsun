<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Learning Path (Lộ trình học) — model                            # ||
|| # Định nghĩa một lộ trình học gồm danh sách khóa học có thứ tự.    # ||
|| # Khóa học lấy từ bảng {prefix}training (cùng CSDL CA Admin).      # ||
|| #################################################################### ||
\*======================================================================*/
class LearningPath extends dbBasic{
	function __construct() {
		$this->pkey = "path_id";
		$this->tbl  = DB_PREFIX."learning_path";
	}
	function getTitle($path_id, $one=null) {
		if(!isset($one["title"])) {
			$one = $this->getOne($path_id);
		}
		return $one['title'];
	}
	function getLink($path_id, $one=null){
		if(!isset($one["slug"])) {
			$one = $this->getOne($path_id);
		}
		return "/lo-trinh-hoc/".$one["slug"]."-p".$path_id.".html";
	}
	/** Xoá lộ trình kèm toàn bộ khoá học liên kết của nó. */
	function doDelete($pvalTable){
		global $dbconn;
		$pvalTable = intval($pvalTable);
		$dbconn->Execute("DELETE FROM ".DB_PREFIX."learning_path_course WHERE path_id='{$pvalTable}'");
		$this->deleteOne($pvalTable);
		return 1;
	}
	/** Danh sách khoá học trong lộ trình, join thông tin từ training, theo thứ tự. */
	function getCourses($path_id){
		global $dbconn;
		$path_id = intval($path_id);
		$sql = "SELECT lpc.id, lpc.training_id, lpc.sort_order, lpc.is_required, lpc.note,
					t.title, t.image, t.time_training,
					t.is_online AS course_online, t.is_trash AS course_trash
				FROM ".DB_PREFIX."learning_path_course lpc
				LEFT JOIN ".DB_PREFIX."training t ON t.training_id = lpc.training_id
				WHERE lpc.path_id = '{$path_id}'
				ORDER BY lpc.sort_order ASC, lpc.id ASC";
		return $dbconn->GetAll($sql);
	}
	/** Tính lại cache: total_course = số khoá, est_minutes = tổng time_training. */
	function recalcStats($path_id){
		global $dbconn;
		$path_id = intval($path_id);
		$sql = "SELECT COUNT(*) AS total_course, COALESCE(SUM(t.time_training),0) AS est_minutes
				FROM ".DB_PREFIX."learning_path_course lpc
				LEFT JOIN ".DB_PREFIX."training t ON t.training_id = lpc.training_id
				WHERE lpc.path_id = '{$path_id}'";
		$res = $dbconn->GetAll($sql);
		$total   = isset($res[0]['total_course']) ? intval($res[0]['total_course']) : 0;
		$minutes = isset($res[0]['est_minutes'])  ? intval($res[0]['est_minutes'])  : 0;
		$this->updateOne($path_id, array(
			"total_course" => $total,
			"est_minutes"  => $minutes,
		));
		return array("total_course" => $total, "est_minutes" => $minutes);
	}
	/** <option> cho các select (tái dùng ở admin/API sau này). */
	function getSelectOptions($path_id){
		$html = '';
		$tmp = $this->getAll("`is_trash`=0 AND `is_online`='1' ORDER BY `reg_date` DESC", "{$this->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $val){
				$html.= '<option'.($path_id==$val[$this->pkey]?' selected':'').' value="'.$val[$this->pkey].'">'.$val['title'].'</option>';
			}
		}
		return $html;
	}
}
