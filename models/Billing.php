<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Billing extends dbBasic {
    function __construct() {
        $this->pkey = "billing_id";
        $this->tbl = DB_PREFIX . "billing";
    }
	function genCode(){
		global $core, $clsISO;
		$billing_id = $this->getMaxId();
		if($billing_id < 10) return sprintf('GD00000%s',$billing_id);
		if($billing_id >= 10 && $billing_id<100) return sprintf('GD0000%s',$billing_id);
		if($billing_id >= 100 && $billing_id<1000) return sprintf('GD000%s',$billing_id);
		if($billing_id >= 1000 && $billing_id<10000) return sprintf('GD00%s',$billing_id);
		if($billing_id >= 10000 && $billing_id<100000) return sprintf('GD0%s',$billing_id);
		return sprintf('GD%s',$billing_id);
	}
	function getScore($billing_id, $oBilling){
		global $core, $dbconn, $clsISO;
		$curr_score = 11; $total_scores = 11; $ul = array();
		$more_information = $oBilling['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if(empty($oBilling['contract_date'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ngày ký HĐMB";
		}
		if(empty($more_information['customer_name']) 
			|| empty($more_information['customer_email']) 
			|| empty($more_information['customer_phone'])){
			$curr_score -= 1;
			$ul[] = "Thiếu thông tin khách hàng";
		}
		if(empty($more_information['identity_card']) 
			|| empty($more_information['issuance_date']) 
			|| empty($more_information['issuance_location'])){
			$curr_score -= 1;
			$ul[] = "Thiếu thông tin CMTND/CCID";
		}
		if(empty($more_information['ccid_front']) 
			|| empty($more_information['ccid_back'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ảnh CMTND/CCID mặt trước OR sau";
		}
		if(empty($more_information['sale_policy_file'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ảnh chính sách bán hàng";
		}
		if(empty($more_information['commission'])){
			$curr_score -= 1;
			$ul[] = "Thiếu % hoa hồng sale bán";
		}
		if(empty($more_information['capture_confirm_file'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ảnh chụp xác nhận";
		}
		if(empty($more_information['price_sheet_file'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ảnh phiếu tính giá";
		}
		if(empty($more_information['payment_order'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ảnh ủy nhiệm chi";
		}
		if(empty($more_information['image_poster'])){
			$curr_score -= 1;
			$ul[] = "Thiếu ảnh vinh danh";
		}
		if(empty($more_information['contract_files'])){
			$curr_score -= 1;
			$ul[] = "Thiếu file HĐMB";
		}
		return '<span class="d-flex mx-auto align-items-center cursor-pointer justify-content-center fs-10 p-1 w-px-30 text-white rounded-pill bg-'.($curr_score == $total_scores ? 'success' : ($curr_score >= 5 ? 'primary' :'danger')).'" data-content=\'<ul><li>'.implode('</li><li>',$ul).'</li></ul>\' data-toggle="webui-popover" data-html="true" data-trigger="click" data-width="200">'.$curr_score.'/'.$total_scores.'</span>';
	}
	function getArrayInfoScore(){
		$arr = [
			"contract_date"		=>	"Ngày ký HĐMB",
			"customer_name"		=>	"Thông tin khách hàng",
			"identity_card"		=>	"Thông tin CMTND/CCID",
			"ccid_front"		=>	"Ảnh CMTND/CCID mặt trước OR sau",
			"sale_policy_file"	=>	"Chính sách bán hàng",
			"commission"		=>	"% hoa hồng sale bán",
			"capture_confirm_file"	=>	"Ảnh chụp xác nhận",
			"price_sheet_file"	=>	"Ảnh phiếu tính giá",
			"payment_order"		=>	"Ảnh ủy nhiệm chi",
			"image_poster"		=>	"Ảnh vinh danh",
			"contract_files"	=>	"File HĐMB",
		];
		return $arr;
	}
	function genHash($billing_id, $billing_type){
		return base64_encode();
	}
	function getBillingSource($billing_source_id){
		$clsProperty = new Property();
		$oProperty = $clsProperty->getOne($billing_source_id, "title,property_code,bgcolor");
		return sprintf('<span class="label d-inline-block" title="%s" style="background:%s; font-size:%s; transform:translateY(-2px)">%s</span>', $oProperty['title'], $oProperty['bgcolor'], '65%', $oProperty['property_code']);
	}
	function getFieldName($field){
		if($field == 'stock_code') return 'Mã căn';
		if($field == 'staff_id') return 'Sale bán';
		if($field == 'totalgrand') return 'Doanh số';
		if($field == 'realized_sales') return 'Doanh số thực';
	}
	function sync_commission($billing_id, $oBilling = array(), $action = "add"){
		global $core, $dbconn, $clsISO, $profile_id;
		$clsProperty = new Property();
		$clsCommission = new Commission();
		if(empty($oBilling)){
			$oBilling = $this->getOne($billing_id);
		}
		$billing_information = $oBilling['more_information'];
		$billing_information = $clsISO->to_array_json($billing_information);
		if($oBilling['is_cancel'] == 1 && $action == 'cancel'){
			$commission_id = $billing_information['commission_id'];
			$clsCommission->updateOne($commission_id, array(
				'status_id' => _COMMISSION_CANCELED_STATUS_ID,
				'upd_date' => time(),
				'user_id_update' => $profile_id
			));
		} else if($action == 'edit'){
			$commission_id = $billing_information['commission_id'];
			$oneCommission = $clsCommission->getOne($commission_id);
			$more_information = $oneCommission['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['stock_code'] = $oBilling['stock_code'];
			$more_information['total_amount'] = $oBilling['totalgrand'];
			$more_information['deposit_date'] = $oBilling['deposit_date'];
			$more_information['sales_commission'] = $oBilling['commission'];
			$more_information['sale_bonus'] = $billing_information['commission'];
			$more_information['sale_department_support_money'] = $oBilling['support_sale'];
			$clsCommission->updateOne($commission_id, array(
				'total_amount' => $oBilling['totalgrand'],
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if($action == 'add') {
			$commission_id = $clsCommission->getMaxId();
			if(isset($billing_information['commission_id']) && (int) $billing_information[$commission_id] > 0){
				$billing_information['commission_id'] = $commission_id;
				$this->updateOne($billing_id, array(
					'more_information' => json_encode($billing_information, JSON_UNESCAPED_UNICODE)
				));
			} else {
				$more_information = array(
					'stock_code' => $oBilling['stock_code'],
					'total_amount' => $oBilling['totalgrand'],
					'deposit_date' => $oBilling['deposit_date'],
					'seller_commission' => $oBilling['commission'], // HH Sale
					'commission' => '0', // HH DL
					'sale_department_commission' => '0', // HH GD Khối
					'project_director_commission' => '0', // HH GDDA
					'sale_department_support_money' => $billing_information['support_sale'],
					'seller_bonus' => $billing_information['sale_bonus'],
					'agency_bonus' => '0',
					'marketing_bonus' => '0',
					'sale_department_support_money' => '0',
					'deposit_money_company_received' => '0',
					'deposit_money' => '0'
				);
				// $clsISO->print_pre($more_information); die();
				if($clsCommission->insert(array(
					$clsCommission->pkey => $commission_id,
					'billing_id' => $billing_id,
					'total_amount' => $oBilling['totalgrand'],
					'status_id' => _COMMISSION_BILLING_UNREISTED_CONTRACT_ID,
					'status_company_id' => _COMMISSION_UNPAID_STATUS_ID,
					'status_sale_id' => _COMMISSION_UNPAID_STATUS_ID,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'reg_date' => $oBilling['reg_date'],
					'upd_date' => $oBilling['upd_date'],
					'user_id' => $oBilling['user_id'],
					'user_id_update' => $oBilling['user_id_update']
				))){
					$billing_information['commission_id'] = $commission_id;
					$this->updateOne($billing_id, array(
						'more_information' => json_encode($billing_information, JSON_UNESCAPED_UNICODE)
					));
				}
			}
		}
	}
	function sendWish($billing_id,$arr_data) {
		global $clsISO,$core,$profile_id;
		$clsStock = new Stock();
		$clsTemplate = new Template();
		$clsProperty = new Property();
		$clsProfile = new Profile();
		#
		if($billing_id > 0) {
			$oneBilling = $this->getOne($billing_id);
			$more_billing = $clsISO->to_array_json($oneBilling["more_information"]);
			$billing_type = $oneBilling["billing_type"];
			$project_id = $oneBilling["project_id"];
			$stock_code = $oneBilling["stock_code"];
			$staff_id = $oneBilling["staff_id"];
			$totalgrand = $oneBilling["totalgrand"];
			$totalgrand = $clsISO->shortNumber($totalgrand,0);
			
			$is_send_zalo = $arr_data["is_send_zalo"];
			$staff_wish_id = !empty($arr_data["staff_wish_id"]) ? $arr_data["staff_wish_id"] : $staff_id;
			$template_zalo_id = $arr_data["template_zalo_id"];
			$image_poster = !empty($arr_data["image_poster"]) ? $clsISO->getGoogleUrl($arr_data["image_poster"],"preview") : "";
			
			#
			$oneStock = $clsStock->getByCond("`ms_code`='{$stock_code}' AND `project_id`='{$project_id}'",$clsStock->pkey.",block_id,ms_code,stock_type");	
			
			$oneBillingType = $clsProperty->getOne($billing_type,$clsProperty->pkey.",more_information");
			$more_billing_type = $clsISO->to_array_json($oneBillingType["more_information"]);
			$list_group_zalo = !empty($more_billing_type["list_group_zalo"]) ? $more_billing_type["list_group_zalo"] : array();
			

			if(!empty($oneStock)) {
				foreach ($list_group_zalo as $key => $val) {
					if($val["project_id"] == $project_id) {
						if($val["block_id"] == 0) {
							$group_zalo_id = $val["group_zalo_id"];
						}else if($val["block_id"] == $oneStock["block_id"]) {
							$group_zalo_id = $val["group_zalo_id"];
							break;
						}
					}
				}
				
				$stock_id = $oneStock[$clsStock->pkey];
				//send zalo						
				$oneTemplate = $clsTemplate->getOne($template_zalo_id,"content");
				$content = !empty($oneTemplate["content"]) ? trim($oneTemplate["content"]) : "";
				$danh_xung = "Chiến binh";
				$content = str_replace("[DANH_XUNG]","Chiến binh",$content);
				$content = str_replace("[HO_TEN]",$clsProfile->getFullName($staff_wish_id),$content);
				$content = str_replace("[DU_AN]",$clsProperty->getCode($oneStock["block_id"]),$content);
				$content = str_replace("[MA_CAN]",$stock_code,$content);
				$content = str_replace("[GIA_TRI]",$totalgrand,$content);
				$more_billing["is_send_zalo"] = $is_send_zalo;
				$more_billing["staff_wish_id"] = $staff_wish_id;
				$more_billing["template_zalo_id"] = $template_zalo_id;
				$more_billing["image_poster"] = $image_poster;
				//end send zalo
				if($this->updateOne($billing_id,[
					"more_information"	=>	json_encode($more_billing,JSON_UNESCAPED_UNICODE)
				])) {
					//send zalo
					if(!empty($content) && !empty($image_poster) && !empty($group_zalo_id)) {	
						if($this->sendZaloContent($content,$group_zalo_id)) {
							$this->sendZaloImage($image_poster,$group_zalo_id);	
						}
						
					}
					
				}
			}
		}		
		
	}
	function sendZaloContent($content,$group_zalo_id){
		global $clsISO;
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MDExYjFkYjQ5YzhlZTdkM2VhYzAxMCIsImlhdCI6MTc0NDkwMjk0MSwiZXhwIjoxNzc2NDM4OTQxfQ.lv4JEMevPdhqzWlWBS7vJGwFkwmYCL4ZD6_aaujVtrA'
		));
		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' 		=> $content,
			'group_id' 		=> $group_zalo_id,
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && $response['status'] == 200){
				return 1;
			}
		}
		// Return
		return 0;
	}
	function sendZaloImage($image,$group_zalo_id){
		global $clsISO;
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTQ5MjY4NTAxOTEwMmQ0MjhmOWY5ZiIsImlhdCI6MTc1NDU2NzI3MiwiZXhwIjoxNzg2MTAzMjcyfQ.fmEaolefHG9tAJf9oHD0m7Yn-bMjxZhphx7pf7pG_hg'
		));
		$curl->post('https://public-api.bizflow.vn/functions/689492685019102d428f9f9f', array(
			'url'		 	=> $image,
			'desc' 			=> "",
			'group_id' 		=> $group_zalo_id,
			'groupLayoutId' => 0,
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && $response['status'] == 200){
				return 1;
			}
		}
		// Return
		return 0;
	}
}
?>