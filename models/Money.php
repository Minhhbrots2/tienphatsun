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
class Money extends dbBasic{
	function __construct(){
		$this->pkey = "money_id";
		$this->tbl = DB_PREFIX."money";
	}
	function getTableField(){
		$arr_fields = [
			"document_no" 			=> "Mã chứng từ",
			"document_date" 		=> "Ngày chứng từ",
			"accounting_date"	 	=> "Ngày hạch toán",
			"description"	 		=> "Diễn giải",
			"account_name"	 		=> "Tài khoản",
			"corresponding_account_name"	 		=> "Tài khoản đối ứng",
			"debit_amount"	 		=> "Phát sinh nợ",
			"credit_amount"	 		=> "Phát sinh có",
			"staff_code"	 		=> "Mã nhân viên",
			"staff_name"	 		=> "Tên Nhân viên",
			"project_code"	 		=> "Mã dự án",
			"project_name"	 		=> "Tên dự án",
			"department_code"	 	=> "Mã phòng ban",
			"department_name"	 	=> "Tên phòng ban",
			// "late_minutes"			=> "Tổng giờ",
			// 'early_leave_minutes' 	=> 'Về sớm',
			// 'overtime_minutes' 		=> 'Ngoài giờ' 
		];
		return $arr_fields;
	}
	function getChild($parentId = 0, $treeMap = []) {
		$result = [];
		foreach ($treeMap as $element) {
			if ($element['parent_id'] == $parentId) {
				$children = $this->getChild($element['setting_id'], $treeMap);
				if ($children) {
					$element['children'] = $children;
				}
				$result[] = $element;
			}
		}
		return $result;
	}
	function getIdChild($parentId, $treeMap, &$visited = []) {
		if (isset($visited[$parentId])) return [];
		$visited[$parentId] = true;
		$result = [];
		if (isset($treeMap[$parentId])) {
			foreach ($treeMap[$parentId] as $childId) {
				$result[] = $childId;
				$result = array_merge($result, $this->getIdChild($childId, $treeMap, $visited));
			}
		}
		return $result;
	}
	function getAccountByType($type){
		global $core, $dbconn, $clsISO;
		$result = [];
		
		$clsSetting = new Setting();
		$tmp = $clsSetting->getCacheItems("_ACCOUNT");
		// $clsISO->print_pre($tmp); die();
		if(!empty($tmp)){
			$treeMap = array();
			foreach ($tmp as $item) {
				$parentId = $item['parent_id'] ?? 0;
				$treeMap[$parentId][] = $item['setting_id'];
			}
			foreach ($tmp as $key => $val) {
				$id = $val[$clsSetting->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$query_tags = $core->get_field($more_information, "query_tags", []);
				if(!empty($query_tags) && !in_array($id, $result) && in_array($type, $query_tags)) {
					$result[] = $id;
					$children = $this->getIdChild($id, $treeMap);
					if(!empty($children)){
						foreach($children as $childId) {
							if(!in_array($childId, $result)) 
								$result[] = $childId;
						}
					}
				}
			}
		}
		return $result;
	}
	function getLinkDashboard($type){
		switch($type) {
			case "marketing":
				return "/marketing.html";
				break;
			case "branch":
				return "/tai-chinh/chi-nhanh.html";
				break;
			case "project":
				return "/tai-chinh/du-an.html";
				break;
			case "cash_flow":
				return "/tai-chinh/dong-tien.html";
				break;
			default: 
				return "/tai-chinh.html";
		}
	}
	function getPreviousDate($start_date,$end_date){
		$start_date = sprintf("%s 00:00:00",$start_date);
		$end_date = sprintf("%s 23:59:59",$end_date);
		$startTs = strtotime($start_date);
		$endTs = strtotime($end_date);
		// Format chuẩn hóa
		$startDate = date('Y-m-d', $startTs);
		$endDate   = date('Y-m-d', $endTs);
		// ===== 1. CHECK FULL MONTH =====
		if (
			date('d', $startTs) == '01' &&
			date('d', $endTs) == date('t', $endTs) &&
			date('Y-m', $startTs) == date('Y-m', $endTs)
		) {
			return [
				"start_time"	=>	$start_date,
				"end_time"	=>	$end_date,
				'start_time_prev' => date('Y-m-01 00:00:00', strtotime('-1 month', $startTs)),
				'end_time_prev'   => date('Y-m-t 23:59:59', strtotime('-1 month', $endTs))
			];
		}
		// ===== 2. CHECK FULL YEAR =====
		if (
			$startDate == date('Y-01-01', $startTs) &&
			$endDate   == date('Y-12-31', $endTs)
		) {
			return [
				"start_time"	=>	$start_date,
				"end_time"	=>	$end_date,
				'start_time_prev' => date('Y-01-01 00:00:00', strtotime('-1 year', $startTs)),
				'end_time_prev'   => date('Y-12-31 23:59:59', strtotime('-1 year', $endTs)),
			];
		}
		// ===== 3. CHECK FULL QUARTER =====
		$startMonth = (int)date('m', $startTs);
		$endMonth   = (int)date('m', $endTs);

		$quarterStartMonths = [1, 4, 7, 10];

		if (
			in_array($startMonth, $quarterStartMonths) &&
			$endMonth == $startMonth + 2 &&
			date('d', $startTs) == '01' &&
			date('d', $endTs) == date('t', $endTs)
		) {
			return [
				"start_time"	=>	$start_date,
				"end_time"	=>	$end_date,
				'start_time_prev' => date('Y-m-01 00:00:00', strtotime('-3 months', $startTs)),
				'end_time_prev'   => date('Y-m-t 23:59:59', strtotime('-3 months', $endTs)),
			];
		}
		// ===== 4. DEFAULT: SAME LENGTH =====
		$seconds = $endTs - $startTs + 1;
		return [
			"start_time"	=>	$start_date,
			"end_time"	=>	$end_date,
			'start_time_prev' => date('Y-m-d H:i:s', $startTs - $seconds),
			'end_time_prev'   => date('Y-m-d H:i:s', $endTs - $seconds),
		];
	}
}