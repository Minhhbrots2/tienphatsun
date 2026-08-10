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
class MarketingSpending extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."marketing_spending";
	}
	function getTableField(){
		$arr_fields = [
			"department_name" 	=> "Phòng ban",
			"staff_name"	 		=> "Họ tên Sale",
			"project_name"	 	=> "Dự án chạy",
			"chanel_name"	 	=> "Kênh chạy",
			"ads_name"	 		=> "Tên tài khoản quảng cáo",
			"ads_id"	 		=> "Id quảng cáo",
			"ads_link"			=> "Link quảng cáo",
			"amount"	 	 	=> "Số tiền thực chạy",
			"company_support_rate"		=> "Mức hỗ trợ",
			"company_support_amount" 	=> "Số tiền hỗ trợ",
			"status_name"				=> "Tình trạng"
		];
		return $arr_fields;
	}
}