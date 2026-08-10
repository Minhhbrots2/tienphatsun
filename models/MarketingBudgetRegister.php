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
class MarketingBudgetRegister extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."marketing_budget_register";
	}
	function getTableField(){
		$arr_fields = [
			"department_name" 	=> "Phòng ban",
			"staff_name"	 	=> "Họ tên Sale",
			"project_name"	 	=> "Dự án chạy",
			"fb_ads"		 => "Facebook ADS",
			"gg_ads"	 	 => "Google ADS",
			"zalo_ads"	 	 => "Zalo ADS",
			"tiktok_ads"	 => "Tiktok ADS"
		];
		return $arr_fields;
	}
	function checkExistProject(){
		
	}
	function isEmpty($budgets = array()){
		global $core, $dbconn;
		$fb_ads = $core->get_field($budgets, "fb_ads", 0);
		$gg_ads = $core->get_field($budgets, "gg_ads", 0);
		$zalo_ads = $core->get_field($budgets, "zalo_ads", 0);
		$tiktok_ads = $core->get_field($budgets, "tiktok_ads", 0);
		if(!empty($fb_ads) || !empty($gg_ads) || !empty($zalo_ads) || !empty($tiktok_ads))
			return 0;
		return 1;
		
	}
}