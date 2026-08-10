<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class Report extends dbBasic {
	function __construct () {
		$this->pkey = "report_id";
		$this->tbl = DB_PREFIX."report";
	}
	function genCode(){
		global $core, $clsISO;
		$report_id = $this->getMaxId();
		if($report_id < 10) return sprintf('BC00000%s/FH',$report_id);
		if($report_id >= 10 && $report_id<100) return sprintf('BC0000%s/FH',$report_id);
		if($report_id >= 100 && $report_id<1000) return sprintf('BC000%s/FH',$report_id);
		if($report_id >= 1000 && $report_id<10000) return sprintf('BC00%s/FH',$report_id);
		if($report_id >= 10000 && $report_id<100000) return sprintf('BC0%s/FH',$report_id);
		return sprintf('GD%s/FH',$report_id);
	}
	function getOnWeek($week_id){
		global $core, $clsISO;
		$week = "";
		$list_weeks = $clsISO->getListWeeks();
		if(!empty($list_weeks)){
			foreach($list_weeks as $key => $val){
				$start_date = $val['start_date'];
				$end_date = $val['end_date'];
				if($key==$week_id){
					$week = sprintf('Tuần %s(%s-%s)', $key, $start_date, $end_date);
					break;
				}
			}
		}
		// Return
		return $week;
	}
	function getValue($prop_id, $report_store, $def=""){
		if(isset($report_store[$prop_id]))
			return $report_store[$prop_id];
		return $def;
	}
	function check_time_send_report(){
		global $core, $dnconn, $clsISO;
		// Lấy thời gian hiện tại
		$currentTime = new DateTime();
		// Lấy ngày hôm nay và ngày mai để tạo các mốc thời gian
		$today = new DateTime();
		$tomorrow = new DateTime('tomorrow');
		// Định nghĩa thời gian giới hạn
		$startTime = (clone $today)->setTime(18, 0); // 18:00 hôm nay
		$endTime = (clone $tomorrow)->setTime(9, 0); // 09:00 ngày mai
		// Kiểm tra xem thời gian hiện tại có nằm trong khoảng từ 18h hôm nay đến 9h ngày mai không
		if ($currentTime >= $startTime && $currentTime <= $endTime) {
			return true; // Cho phép gửi báo cáo
		} else {
			return false; // Không cho phép gửi báo cáo
		}
	}
	function check_time_send_report_date($date) {
		// Chuyển đổi chuỗi ngày tháng thành đối tượng DateTime
		$f = date('Y-m-d H:i', $date);
		$inputDate = new DateTime($f);
		// Tạo bản sao ngày hôm nay và ngày mai từ ngày nhập
		$startTime = (clone $inputDate)->setTime(18, 0); // 18:00 của ngày nhập
		$endTime = (clone $inputDate)->modify('+1 day')->setTime(9, 0); // 09:00 của ngày hôm sau
		// Lấy thời gian hiện tại
		$currentTime = new DateTime();
		// Kiểm tra xem thời gian hiện tại có nằm trong khoảng từ 18h của ngày đó đến 9h của ngày hôm sau không
		if ($currentTime >= $startTime && $currentTime <= $endTime) {
			return true; // Cho phép gửi báo cáo
		} else {
			return false; // Không cho phép gửi báo cáo
		}
	}
}
?>