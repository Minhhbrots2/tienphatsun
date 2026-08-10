<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Attendance extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."attendance";
	}
	function getTableField(){
		$arr_fields = [
			"department_name" 		=> "Mã phòng ban",
			"staff_code" 			=> "Mã nhân viên",
			"staff_name"	 		=> "Tên nhân viên",
			"work_date"	 			=> "Ngày chấm",
			"check_in"	 			=> "Giờ vào",
			"check_out"	 			=> "Giờ ra",
			"check_in_2"	 		=> "Giờ vào 2",
			"check_out_2"	 		=> "Giờ ra 2",
			"total_work_hours"		=> "Tổng giờ",
			// "late_minutes"			=> "Tổng giờ",
			// 'early_leave_minutes' 	=> 'Về sớm',
			// 'overtime_minutes' 		=> 'Ngoài giờ' 
		];
		return $arr_fields;
	}
}
?>