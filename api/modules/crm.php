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
$app->post('/crm/add_customer', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	###
	$inputs = $request->getParsedBody();
	$admin_id  = _PROFILE_LTD_ID;// _PROFILE_TECH_ID
	$full_name = $helper->getFieldValue("full_name", $inputs);
	$email = $helper->getFieldValue("email", $inputs);
	$phone = $helper->getFieldValue("phone", $inputs);
	$phone = $clsCustomer->formatPhone($phone); // A9: chuan hoa SDT tai cua de dedup khop so da luu
	$districts = $helper->getFieldValue("districts", $inputs);
	$purposes = $helper->getFieldValue("purposes", $inputs);
	$minPrice = $helper->getFieldValue("minPrice", $inputs);
	$maxPrice = $helper->getFieldValue("maxPrice", $inputs);
	$availableTimes = $helper->getFieldValue("availableTimes", $inputs);
	$bedroomCount = $helper->getFieldValue("bedroomCount", $inputs);
	$contactRequestListings = $helper->getFieldValue("contactRequestListings", $inputs);
	$contactAgentListing = $helper->getFieldValue("contactAgentListing", $inputs);
	$contactRequestListings = $clsISO->to_array_json($contactRequestListings);
	$contactAgentListing = $clsISO->to_array_json($contactAgentListing);
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
	if(!empty($phone) && $clsCustomer->countItem("`admin_id`='{$admin_id}' and `phone`='{$phone}'") > 0){
		$error_no += 1;
		$errors[] = "Số điện thoại này đã tồn tại";
	}
	if($error_no == 0){
		$more_information = array();
		$more_information['districts'] = $districts;
		$more_information['purposes'] = $purposes;
		$more_information['minPrice'] = $minPrice;
		$more_information['maxPrice'] = $maxPrice;
		$more_information['availableTimes'] = $availableTimes;
		$more_information['bedroomCount'] = $bedroomCount;
		$more_information['bathroomCount'] = $bathroomCount;
		$more_information['contactRequestListings'] = $contactRequestListings;
		$more_information['contactAgentListing'] = $contactAgentListing;
		$list_purpose_id = $clsCustomer->getPurpose($purposes);
		$customer_id = $clsCustomer->getMaxId();
		if($clsCustomer->insert(array(
			$clsCustomer->pkey => $clsCustomer->getMaxId(),
			'name' => addslashes($full_name),
			'name_slug' => $clsISO->replaceSpace($full_name),
			'email' => $email,
			'phone' => $clsCustomer->formatPhone($phone),
			'status_id' => _CRM_STATUS_LEAD_ID,
			'resource_id' => _CRM_RESOURCE_HOUSENOW_ID,
			'list_purpose_id' => $list_purpose_id,
			'admin_id' => $admin_id,
			'notes' => $inputs['notes'],
			'user_id' => _PROFILE_LTD_ID,
			'user_id_update' => _PROFILE_LTD_ID,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time()
		))){
			$titleNoty = sprintf('Bạn đã nhận được thông tin khách hàng <strong>%s</strong> từ HouseNow', $full_name);
			$clsNotify->setDebug(true);
			$clsNotify->insertNotify("Customer", $clsCustomer->pkey, $customer_id, $titleNoty, time(), 
				sprintf('|%s|', $admin_id), _PROFILE_LTD_ID);
			/** Gửi thông báo */
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id`='{$admin_id}' and `token`<>''", "token");		
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => "[HouseNow] Khách hàng mới",
					'message' => strip_tags($titleNoty),
					'url' =>  PCMS_URL . '/crm/#/activity/'.$customer_id
				), $subscribers);
			}
			$status_code = 200;
			$apiresults = array(
				'error' => 0, 
				'result' => 'success', 
				'message' => "Thêm mới khách hàng thành công !"
			);
		}
	} else {
		$status_code = 410;
		$apiresults = array(
			'error' => 1, 
			'result' => 'success', 
			'message' => implode(',', $errors)
		);
	}
	// Return
	echo echoResponse($status_code, $apiresults);
})->add($authenticate);
/**
 * GET /crm/customers — Danh sách khách hàng cho Future Mind.
 * Query: page (>=1, mặc định 1), limit (1-500, mặc định 200).
 * Social đọc từ more_information (key facebook/tiktok) — extract ngay trong SQL
 * để không kéo nguyên blob more_information (chứa action_logs rất nặng) về PHP.
 */
$app->get('/crm/customers', function ($request, $response, $args) use ($app) {
	$clsCustomer = new Customer();
	$params = $request->getQueryParams();
	$page = isset($params['page']) ? (int) $params['page'] : 1;
	$limit = isset($params['limit']) ? (int) $params['limit'] : 200;
	if($page < 1){ $page = 1; }
	if($limit < 1){ $limit = 1; }
	if($limit > 500){ $limit = 500; }
	$offset = ($page - 1) * $limit;
	$total = (int) $clsCustomer->countItem("`is_trash`=0 AND `resource_id`='" . _CRM_RESOURCE_SALEMOC_ID . "'");
	$field = "{$clsCustomer->pkey},`name`,`phone`,`email`
		,JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.facebook\")) AS `facebook`
		,JSON_UNQUOTE(JSON_EXTRACT(`more_information`,\"$.tiktok\")) AS `tiktok`";
	$rows = $clsCustomer->getAll("`is_trash`=0 AND `resource_id`='"._CRM_RESOURCE_SALEMOC_ID."'". " ORDER BY {$clsCustomer->pkey} ASC LIMIT {$offset},{$limit}", $field);
	$data = array();
	if(!empty($rows)){
		foreach($rows as $row){
			$social = array();
			// Key không tồn tại → SQL NULL; key mang JSON null → chuỗi 'null' — loại cả hai
			if(!empty($row['facebook']) && $row['facebook'] !== 'null'){
				$social['facebook'] = $row['facebook'];
			}
			if(!empty($row['tiktok']) && $row['tiktok'] !== 'null'){
				$social['tiktok'] = $row['tiktok'];
			}
			$data[] = array(
				'id' => (int) $row[$clsCustomer->pkey],
				'name' => (string) $row['name'],
				'phone' => (string) $row['phone'],
				'email' => (string) $row['email'],
				'social' => (object) $social // (object) để rỗng encode thành {} thay vì []
			);
		}
	}
	echoResponse(200, array(
		'error' => 0,
		'result' => 'success',
		'total' => $total,
		'page' => $page,
		'limit' => $limit,
		'data' => $data
	));
})->add($authenticate);
?>