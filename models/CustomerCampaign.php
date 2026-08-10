<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CustomerCampaign model                                            # ||
|| #################################################################### ||
\*======================================================================*/
class CustomerCampaign extends dbBasic{
	function __construct(){
		$this->pkey = "customer_id";
		$this->tbl = DB_PREFIX."customer_campaign";
	}
	function getIdsByCustomer($customer_id){
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		$list = $this->getAll("`customer_id`='{$customer_id}' ORDER BY `campaign_id` ASC", "campaign_id");
		$ids = array();
		if(!empty($list)){
			foreach($list as $row){
				$val = (int) $row['campaign_id'];
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
			foreach($ids as $campaign_id){
				$campaign_id = (int) $campaign_id;
				if($campaign_id > 0){
					$this->insert(array(
						'customer_id' => $customer_id,
						'campaign_id' => $campaign_id,
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
