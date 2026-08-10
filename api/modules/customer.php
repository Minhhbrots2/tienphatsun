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
$app->post('/customer/add', function ($request, $response, $args) use ($app) {
	global $dbconn, $core, $clsISO,$profile_id;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCampaign = new Campaign();
	$status_code = 2;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$inputs = $request->getParsedBody();
	$full_name = trim($helper->getFieldValue("full_name", $inputs));
	$type = $helper->getFieldValue("type", $inputs);
	$phone = $helper->getFieldValue("phone", $inputs);
	$phone = $clsCustomer->formatPhone($phone);
	$reg_date = $helper->getFieldValue("reg_date", $inputs);
	$upd_date = $helper->getFieldValue("upd_date", $inputs);
	$reg_date = !empty($reg_date) ? strtotime(str_replace("/","-",$reg_date)) : time();
	$upd_date = !empty($upd_date) ? strtotime(str_replace("/","-",$upd_date)) : time();
	$begin_need = $helper->getFieldValue("begin_need", $inputs);
	$status = trim($helper->getFieldValue("status_name", $inputs));
	$last_status = trim($helper->getFieldValue("last_status", $inputs));
	$staff_names = $helper->getFieldValue("staff_names", $inputs);
	$staff_notes = $helper->getFieldValue("notes", $inputs);
	$campaign = $helper->getFieldValue("campaign", $inputs);
	$user_id = $admin_id = _PROFILE_ROOT_ID; //Default
	$admin_name = $clsProfile->getFullName($admin_id);
	###
	$error_no = 0; $errors = array();
	if(empty($full_name)){
		$error_no += 1;
		$errors[] = "Họ và tên không được trống";
	}
	if(empty($phone)){
		$error_no += 1;
		$errors[] = "Số điện thoại không được trống";
	}
	$list_share_id = array_merge(_PROFILE_SUPPER_ID, array(_PROFILE_TAT_ID));
	$list_campaign_arrs = [];
	if(!empty($campaign)){
		$tmp = $clsCampaign->getByCond("`is_trash`=0 AND `campaign_type`='_campaign' AND `use_globe`=1 
			AND `slug`='{$clsISO->replaceSpace($campaign)}'", $clsCampaign->pkey);
		if(!empty($tmp)){
			$list_campaign_arrs[] = $tmp[$clsCampaign->pkey];
		} else {
			$campaign_id = $clsCampaign->getMaxId();
			$clsCampaign->insert(array(
				$clsCampaign->pkey => $campaign_id,
				'campaign_type' => '_campaign',
				'use_globe' => 1,
				'title' => $campaign,
				'slug' => $clsISO->replaceSpace($campaign),
				'user_id' => $admin_id,
				'user_id_update' => $admin_id,
				'reg_date' => $reg_date,
				'upd_date' => $reg_date
			));
			$list_campaign_arrs[] = $campaign_id;
		}
	}
	$list_share_id = $clsISO->makeSlashListFromArrayRoot($list_share_id);
	$list_campaign_id = $clsISO->makeSlashListFromArrayRoot($list_campaign_arrs);
	# tinh trang
	$status_id = $last_status_id = _CRM_STATUS_LEAD_ID;
	$arr_status = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
	if(!empty($status) || !empty($last_status)) {
		$status_slug = !empty($status) ? $clsISO->replaceSpace($status) : "";
		$last_status_slug = !empty($last_status) ? $clsISO->replaceSpace($last_status) : "";
		foreach($arr_status as $key => $val){
			if(!empty($status) && $val["slug"] == $status_slug) {
				$status_id = $val[$clsProperty->pkey];
				break;
			}
		}
		foreach($arr_status as $key => $val){
			if(!empty($last_status) && $val["slug"] == $last_status_slug) {
				$last_status_id = $val[$clsProperty->pkey];
				break;
			}
		}
	}
	$arr_cache_profile = [];
	if($error_no == 0){	
		$oneCustomer = $clsCustomer->getByCond("(`user_id`='{$user_id}' OR `list_share_id` LIKE '%|{$user_id}|%') and `phone`='{$phone}'");
		// $clsISO->print_pre($oneCustomer); die();
		if(!empty($oneCustomer)) {
			$customer_id = $oneCustomer[$clsCustomer->pkey];
			// $list_share_id_old = $oneCustomer['list_share_id'];
			// $more_information = $oneCustomer['more_information'];
			// $more_information = $clsISO->to_array_json($more_information);
			// $action_logs = $core->get_field($more_information, "action_logs", []);	
			$status_code = 200;
			$apiresults = array(
				'error' => 0, 
				'result' => 'success', 
				'message' => "Khách hàng này đã tồn tại"
			);
		} else {
			$more_information = $action_logs = $action_logs = $arr_notes = array();
			#log chuyen doi
			if(!empty($full_name)) $content_logs[] = sprintf('Tên khách hàng: %s', $full_name);
			if(!empty($phone)) $content_logs[] = sprintf('Điện thoại: %s', $phone);
			$content = sprintf('<strong>%s</strong> đã tạo mới khách hàng <strong>%s</strong>', $admin_name, implode(',', $content_logs));
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $user_id,
				'reg_date' => $reg_date,
				'content' => $content
			);
			if($status_id != $last_status_id){
				$content = sprintf('<strong>%s</strong> đã cập nhật tình trạng từ <strong>%s</strong> thành <strong>%s</strong>', 
					$admin_name, $clsProperty->getTitle($status_id), $clsProperty->getTitle($last_status_id));
				$action_logs[$clsISO->getUniqid()] = array(
					'user_id' => $user_id,
					'reg_date' => $upd_date,
					'content' => $content
				);
			}
			$more_information['action_logs'] = $action_logs;
			if(!empty($staff_notes)){
				foreach($staff_notes as $key => $val){
					if(!empty($val)){
						$admin_care_id = $admin_id;
						$staff_name = $staff_names[$key];
						if(!empty($staff_name)){
							$tmp = $clsProfile->getByCond("full_name_slug='".$$clsISO->replaceSpace($staff_name)."'", $clsProfile->pkey);
							if(!empty($tmp)) $admin_care_id = $tmp[$clsProfile->pkey];
						}
						$arr_notes[$key] = array(
							'type_id' 			=> _FOLLOWUP_CALL_ID,
							'status_id' 		=> $last_status_id,
							'intro'				=> $val,
							'_result' 			=> "",
							'date_id' 			=> $upd_date,
							'is_reminder' 		=> 0,
							'reminder_before' 	=> 0,
							'reminder_time' 	=> time(),
							'admin_id' 			=> $admin_care_id,
							'user_id' 			=> $admin_care_id,
							'user_id_update' 	=> $admin_care_id,
							'reg_date' 			=> $upd_date,
							'upd_date' 			=> $upd_date
						);
					}
				}
			}
			$status_id = ($last_status_id > 0) ? $last_status_id : $status_id;
			$customer_id = $clsCustomer->getMaxId();
			if($clsCustomer->insert(array(
				$clsCustomer->pkey => $customer_id,
				'name' => addslashes($full_name),
				'name_slug' => $clsISO->replaceSpace($full_name),
				'phone' =>	$phone,
				'status_id' => $status_id,
				'resource_id' => _CRM_RESOURCE_ADS_ID,
				'admin_id' => $admin_id,
				'begin_need' => $begin_need,
				'list_share_id' => $list_share_id,
				'list_campaign_id' => $list_campaign_id,
				'user_id' => $user_id,
				'user_id_update' => $user_id,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'reg_date' => $reg_date,
				'upd_date' => $upd_date,
				'use_globe' => 1
			))){				
				#followups
				if(!empty($arr_notes)){
					foreach($arr_notes as $key => $val){
						$clsFollowUp->insert(array_merge($val, array(
							'customer_id' => $customer_id
						)));
					}
				}
				++$number_upd;
				$status_code = 200;
				$apiresults = array(
					'error' => 0, 
					'result' => 'success', 
					'message' => "Thêm mới khách hàng thành công !"
				);
			}
		}
	} else {
		$status_code = 200;
		$apiresults = array(
			'error' => 0, 
			'result' => 'success', 
			'message' => implode(',', $errors)
		);
	}
	// Return
	echo echoResponse($status_code, $apiresults);
})
?>