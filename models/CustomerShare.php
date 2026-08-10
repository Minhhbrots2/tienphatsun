<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CustomerShare model                                               # ||
|| #################################################################### ||
\*======================================================================*/
class CustomerShare extends dbBasic{
	function __construct(){
		$this->pkey = "customer_id";
		$this->tbl = DB_PREFIX."customer_share";
	}
	function getIdsByCustomer($customer_id){
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		$list = $this->getAll("`customer_id`='{$customer_id}' ORDER BY `admin_id` ASC", "admin_id");
		$ids = array();
		if(!empty($list)){
			foreach($list as $row){
				$val = (int) $row['admin_id'];
				if($val > 0){
					$ids[$val] = $val;
				}
			}
		}
		$ids = array_values($ids);
		sort($ids);
		return $ids;
	}
	function syncByCustomer($customer_id, $ids, $user_id=0){
		$customer_id = (int) $customer_id;
		$user_id = (int) $user_id;
		if($customer_id <= 0){
			return 0;
		}
		$this->deleteByCond("`customer_id`='{$customer_id}'");
		if(!empty($ids)){
			foreach($ids as $admin_id){
				$admin_id = (int) $admin_id;
				if($admin_id > 0){
					$this->insert(array(
						'customer_id' => $customer_id,
						'admin_id' => $admin_id,
						'reg_date' => time(),
						'upd_date' => time(),
						'user_id' => $user_id,
						'user_id_update' => $user_id
					));
				}
			}
		}
		return 1;
	}
}
