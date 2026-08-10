<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CustomerMeta model                                                # ||
|| #################################################################### ||
\*======================================================================*/
class CustomerMeta extends dbBasic{
	function __construct(){
		$this->pkey = "customer_meta_id";
		$this->tbl = DB_PREFIX."customer_meta";
	}
	function getIdsByCustomerType($customer_id, $meta_type){
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		$meta_type = addslashes($meta_type);
		$list = $this->getAll("`customer_id`='{$customer_id}' AND `meta_type`='{$meta_type}' ORDER BY `meta_id` ASC", "meta_id");
		$ids = array();
		if(!empty($list)){
			foreach($list as $row){
				$val = (int) $row['meta_id'];
				if($val > 0){
					$ids[$val] = $val;
				}
			}
		}
		$ids = array_values($ids);
		sort($ids);
		return $ids;
	}
	function getMapByCustomerIds($customer_ids, $meta_types=array()){
		$ids = array();
		if(is_array($customer_ids)){
			foreach($customer_ids as $val){
				$val = (int) $val;
				if($val > 0){
					$ids[$val] = $val;
				}
			}
		} else if(is_numeric($customer_ids)){
			$val = (int) $customer_ids;
			if($val > 0){
				$ids[$val] = $val;
			}
		}
		$ids = array_values($ids);
		if(empty($ids)){
			return array();
		}
		$cond = "`customer_id` IN (".implode(',', $ids).")";
		if(!empty($meta_types)){
			$types = array();
			foreach($meta_types as $t){
				$t = trim($t);
				if($t !== ''){
					$types[] = addslashes($t);
				}
			}
			if(!empty($types)){
				$cond .= " AND `meta_type` IN ('".implode("','", $types)."')";
			}
		}
		$rows = $this->getAll($cond, "customer_id,meta_type,meta_id");
		$map = array();
		if(!empty($rows)){
			foreach($rows as $row){
				$cid = (int) $row['customer_id'];
				$type = $row['meta_type'];
				$mid = (int) $row['meta_id'];
				if($cid > 0 && $mid > 0){
					if(!isset($map[$cid])){
						$map[$cid] = array();
					}
					if(!isset($map[$cid][$type])){
						$map[$cid][$type] = array();
					}
					$map[$cid][$type][$mid] = $mid;
				}
			}
			foreach($map as $cid => $types){
				foreach($types as $type => $vals){
					$arr = array_values($vals);
					sort($arr);
					$map[$cid][$type] = $arr;
				}
			}
		}
		return $map;
	}
	function syncByCustomerType($customer_id, $meta_type, $ids, $user_id=0){
		$customer_id = (int) $customer_id;
		$user_id = (int) $user_id;
		if($customer_id <= 0){
			return 0;
		}
		$meta_type = addslashes($meta_type);
		$this->deleteByCond("`customer_id`='{$customer_id}' AND `meta_type`='{$meta_type}'");
		if(!empty($ids)){
			foreach($ids as $meta_id){
				$meta_id = (int) $meta_id;
				if($meta_id > 0){
					$this->insert(array(
						'customer_id' => $customer_id,
						'meta_type' => $meta_type,
						'meta_id' => $meta_id,
						'reg_date' => time(),
						'user_id' => $user_id
					));
				}
			}
		}
		return 1;
	}
}
