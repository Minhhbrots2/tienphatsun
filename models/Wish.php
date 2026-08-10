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
class Wish extends dbBasic{
	function __contructor() {
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."wish";
    }
	function sendWish($billing_id,$arr_data) {
		global $clsISO,$core,$profile_id;
		$clsStock = new Stock();
		$clsTemplate = new Template();
		$clsProperty = new Property();
		$clsBilling = new Billing();
		$clsProfile = new Profile();
		#
		$billing_type = $arr_data["billing_type"];
		$project_id = $arr_data["project_id"];
		$stock_code = $arr_data["stock_code"];
		$is_send_zalo = $arr_data["is_send_zalo"];
		$template_zalo_id = $arr_data["template_zalo_id"];
		$image_poster = $arr_data["image_poster"];
		$staff_id = $arr_data["staff_id"];
		$status_id = !empty($is_send_zalo) ? 1 : 0;
		$totalgrand = $arr_data["totalgrand"];
		$totalgrand = $clsISO->processSmartNumber($totalgrand);
		$totalgrand = $clsISO->shortNumber($totalgrand,0);
		#
		$oneBillingType = $clsProperty->getOne($billing_type,$clsProperty->pkey.",more_information");
		$more_billing_type = $clsISO->to_array_json($oneBillingType["more_information"]);
		$group_zalo_id = !empty($more_billing_type["group_zalo_id"]) ? $more_billing_type["group_zalo_id"] : "";
		
		$oneStock = $clsStock->getByCond("`ms_code`='{$stock_code}' AND `project_id`='{$project_id}'",$clsStock->pkey.",block_id");		
		
		if(!empty($oneStock)) {
			$stock_id = $oneStock[$clsStock->pkey];
			//send zalo						
			$oneTemplate = $clsTemplate->getOne($template_zalo_id,"content");
			$content = !empty($oneTemplate["content"]) ? trim($oneTemplate["content"]) : "";
			$danh_xung = "Chiến binh";
			$content = str_replace("[DANH_XUNG]","Chiến binh",$content);
			$content = str_replace("[HO_TEN]",$clsProfile->getFullName($staff_id),$content);
			$content = str_replace("[DU_AN]",$clsProperty->getCode($oneStock["block_id"]),$content);
			$content = str_replace("[MA_CAN]",$stock_code,$content);
			$content = str_replace("[GIA_TRI]",$totalgrand,$content);
			//end send zalo
			$check_wish = $this->getByCond("`billing_id`='{$billing_id}' AND `staff_id`='{$staff_id}' AND `stock_id`='{$stock_id}'");
			if(!empty($check_wish)) {
				$data_upd = [
					"status_id"		=>	1,
					"template_id"		=>	$template_zalo_id,	
					"image"				=>	$image_poster,
					"user_update" 	=> 	$profile_id,
					"upd_date" 		=> 	time(),
				];
				if(empty($check_wish["status_id"]) && $status_id == 1 && $clsTemplate->updateOne($check_wish[$this->pkey],$data_upd)) {
					//send zalo
					if(!empty($content) && !empty($image_poster) && !empty($group_zalo_id)) {
						$this->sendZalo($content,$clsISO->getGoogleUrl($image_poster,"preview"),$group_zalo_id);
					}
				}
			}else{
				$data = [
					$this->pkey			=>	$this->getMaxID(),
					"billing_id"		=>	$billing_id,
					"staff_id"			=>	$staff_id,
					"image"				=>	$image_poster,
					"stock_id"			=> 	$stock_id,
					"template_id"		=>	$template_zalo_id,		
					"status_id"			=>	$status_id,		
					"user_id"			=>	$profile_id,		
					"user_update_id"	=>	$profile_id,		
					"reg_date"			=>	time(),		
					"upd_date"			=>	time(),		
				];
				$clsISO->print_pre($data);die;
				if($this->insert($data)) {
					if($status_id == 1) {
						//send zalo						
						if(!empty($content) && !empty($image_poster) && !empty($group_zalo_id)) {
							$this->sendZalo($content,$clsISO->getGoogleUrl($image_poster,"preview"),$group_zalo_id);
						}
					}
				}
			}
		}
		
		
	}
	function sendZalo($content,$image,$group_zalo_id){
		global $clsISO;
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTQ5MjY4NTAxOTEwMmQ0MjhmOWY5ZiIsImlhdCI6MTc1NDU2NzI3MiwiZXhwIjoxNzg2MTAzMjcyfQ.fmEaolefHG9tAJf9oHD0m7Yn-bMjxZhphx7pf7pG_hg'
		));
		$curl->post('https://public-api.bizflow.vn/functions/689492685019102d428f9f9f', array(
			'url'		 	=> $image,
			'desc' 			=> $content,
			'group_id' 		=> $group_zalo_id,
			'groupLayoutId' => 1,
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			$clsISO->print_pre($response);die;
			if(isset($response['status']) && $response['status'] == 200){
				return 1;
			}
		}
		// Return
		return 0;
	}
}