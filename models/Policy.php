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
class Policy extends dbBasic{
	function __construct(){
		$this->pkey = "policy_id";
		$this->tbl = DB_PREFIX."policy";
	}
	function getTitle($pval,$_args=array()){
		if(!isset($_args['title'])){
			$_args = $this->getOne($pval,"title");
		}
		return $_args['title'];
	}
	function syncPolicyScope($policy_id, $scope) {
		global $core, $dbconn, $clsISO;
		$clsPolicyScope = new PolicyScope();
		// Xóa an toàn bằng ID (để tránh lỗi MySQL sql_safe_updates khi cột policy_id không có index)
		$old_records = $clsPolicyScope->getAll("policy_id = '$policy_id'", "id");
		if (!empty($old_records)) {
			foreach ($old_records as $rec) {
				$clsPolicyScope->deleteOne($rec['id']);
			}
		}
		if (empty($scope)) return false;
		// Parse JSON nếu là chuỗi
		$scope_data = $clsISO->to_array_json($scope);
		if (!is_array($scope_data) || empty($scope_data)) return false;
		$fields = "policy_id, project_id, block_id, building_id";
		foreach ($scope_data as $key => $item) {
			$project_id = isset($item['project_id']) ? intval($item['project_id']) : 0;
			$block_id = isset($item['block_id']) ? intval($item['block_id']) : 0;
			// Chuẩn hóa building_id (hỗ trợ cả mảng, chuỗi phẩy, hoặc số)
			$b_ids = [];
			if (isset($item['building_id'])) {
				if (is_array($item['building_id'])) {
					$b_ids = $item['building_id'];
				} elseif (is_string($item['building_id']) && $item['building_id'] !== '') {
					$b_ids = explode(',', $item['building_id']);
				} elseif (is_numeric($item['building_id']) && $item['building_id'] > 0) {
					$b_ids = [$item['building_id']];
				}
			}
			if (!empty($b_ids)) {
				foreach ($b_ids as $b_id) {
					$b_id = intval($b_id);
					$values = "'$policy_id', '$project_id', '$block_id', '$b_id'";
					$clsPolicyScope->insertOne($fields, $values);
				}
			} else {
				// Nếu không có building_id cụ thể, set building_id = 0
				$values = "'$policy_id', '$project_id', '$block_id', '0'";
				$clsPolicyScope->insertOne($fields, $values);
			}
		}
		return true;
	}
}