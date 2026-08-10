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
$app->post('/v1/sop/chatlogs', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$helper = new Helper();
	$clsZaloUser = new ZaloUser();
	$clsZaloGroup = new ZaloGroup();
	$clsSopChatLog = new SopChatLog();
	$clsConfiguration = new Configuration();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$inputs = $request->getParsedBody();
	$id_group = $inputs['id_group'] ? trim($inputs['id_group']) : 0;
	$sender_id = isset($inputs['sender_id']) ? (int) $inputs['sender_id'] : 0;
	$data_logs = isset($inputs['data_logs']) ? $inputs['data_logs'] : [];
	$stock_logs = isset($inputs['stock_logs']) ? $clsISO->to_array_json($inputs['stock_logs']) : [];
	$id_chat = isset($data_logs['id']) ? $data_logs['id'] : "";
	$msgType = $data_logs['data']['data']['groupMsgs'][0]['msgType'];
	$event = isset($data_logs['event']) ? trim($data_logs['event']) : 'UNKNOWN';
	// $clsISO->print_pre($inputs); die();
	if($event == 'GROUP_RECEIVED_MESSAGE' && in_array($msgType, ['webchat']) 
		&& $clsSopChatLog->countItem("`id_chat`='{$id_chat}'") == 0){
		if((int) $id_group > 0){
			$is_blocked = 0;
			$tmp = $clsZaloGroup->getByCond("`id_group`='{$id_group}'");
			if(!empty($tmp)){
				$is_blocked = (int) $tmp['is_blocked'];
				$name_group = $tmp['name_group'];
			} else {
				$curl = new \Curl\Curl();
				$curl->setHeaders(array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4N2M2NTk1NDgzMDRmY2FkN2IxYThlOCIsImlhdCI6MTc1Mjk4MjkzNCwiZXhwIjoxNzg0NTE4OTM0fQ.IQmx_v6KQUO-qj1fALIUivq9BcCi9rsnjMCUQao14Ko'
				));
				$curl->post('https://public-api.bizflow.vn/functions/687c659548304fcad7b1a8e8', array(
					'group_id' => $id_group
				));
				if(!$curl->error){
					$response = toArray($curl->response);
					$name_group = $response['data']['data']['gridInfoMap'][$id_group]['name'];
					$group_id = $clsZaloGroup->getMaxId();
					$clsZaloGroup->insert(array(
						$clsZaloGroup->pkey => $group_id,
						'id_group' => $id_group,
						'name_group' => $name_group,
						'more_information' => json_encode($response, JSON_UNESCAPED_UNICODE),
						'reg_date' => time()
					));
				}
			}
			$data_logs['name_group'] = $name_group;
			if($is_blocked == 0){
				// $dbconn->debug = true;
				$clsZaloUser->init($sender_id);
				if($clsSopChatLog->insert(array(
					'id' => $clsSopChatLog->getMaxId(),
					'id_chat' => $id_chat,
					'sender_id' => $sender_id,
					'id_group' => $id_group,
					'data_logs' => json_encode($data_logs, JSON_UNESCAPED_UNICODE),
					'stock_logs' => json_encode($stock_logs, JSON_UNESCAPED_UNICODE),
					'reg_date' => time()
				))){
					$apiresults = array(
						'error' => 0,
						'result' => 'success',
						'message' => 'Success'
					);
				}
			} else {
				$apiresults = array(
					'error' => '1',
					'result' => 'blocked',
					'message' => 'Group is blocked'
				);
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/sop/zalo/received', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO, $core;
	$clsISO = new ISO();
	$clsZaloChat = new ZaloChat();
	$apiresults = array(
		'error' => 1,
		'result' => 'error',
		'message' => 'Error'
	);
	$inputs = $request->getParsedBody();
	$sender_id = isset($inputs['sender_id']) ? $inputs['sender_id'] : "";
	$receiver_id = isset($inputs['receiver_id']) ? $inputs['receiver_id'] : "";
	$message = isset($inputs['message']) ? $inputs['message'] : [];
	if($clsZaloChat->insert(array(
		'chat_type' => '_received',
		'sender_id' => $sender_id,
		'receiver_id' => $receiver_id,
		'message' => json_encode($message, JSON_UNESCAPED_UNICODE ),
		'reg_date' => time()
	))){
		$apiresults = array(
			'error' => 0,
			'result' => 'success',
			'message' => 'Success'
		);
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/sop/sync', function ($request, $response) use ($app) {
	global $dbconn, $clsISO, $core;
	#- Require library
	$clsISO = new ISO();
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	$tblData = $request->getParsedBody();
	$updated_at = $tblData['updated_at'];
	$status_name = $tblData['status_name'];
	$sop_code = $tblData['sop_code'];
	$stock_code = $tblData['stock_code'];
	$stock_hidden_code = $tblData['stock_hidden_code'];
	$bedroom_name = $tblData['bedroom_name'];
	$DT_TT = $tblData['DT_TT'];
	$price_owner = $clsISO->processSmartNumber($tblData['price_owner']);
	$fee_included_name = $tblData['fee_included_name'];
	$price = $clsISO->processSmartNumber($tblData['price']);
	$home_direction_name = $tblData['home_direction_name'];
	$view_name = $tblData['view_name'];
	$interior_name = $tblData['interior_name'];
	$juridical_name = $tblData['juridical_name'];
	$short_intro = $tblData['short_intro'];
	$image_folder = $tblData['image_folder'];
	$status_viewing = $tblData['status_viewing'];
	$pass_door = $core->get_field($tblData, "pass_door", "");
	$contact_name = $core->get_field($tblData, "contact_name", "Hải Lý");
	$contact_phone = $core->get_field($tblData, "contact_phone", "0983 886 538");
	$source_name = $core->get_field($tblData, "source_name", "");;
	$arr_projects = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID);
	$notes = $tblData['notes'];
	// $clsISO->print_pre($tblData); die();
	if(!empty($stock_code)){
		$is_locked = $is_solded = $is_deleted = 0;
		if($status_name == 'Đang lock'){
			$is_locked = 1;
		} else if($status_name == 'Đã bán'){
			$is_solded = 1;
		} else if($status_name == 'Đã xóa'){
			$is_deleted = 1;
		}
		$stock_code = strtoupper($stock_code);
		$stock_code = preg_replace('/\s+/', '', $stock_code);
		$stock_code = $clsSop->format_stock_code($stock_code);
		$stock_code_xs = str_replace('.', '', $stock_code);
		$stock_code_xs = str_replace('-', '', $stock_code_xs);	
		if((int) $is_solded == 1 || (int) $is_deleted == 1){
			$oneSop = $clsSop->getByCond("`is_trash`=0 AND `user_id`='"._PROFILE_SOP_ADMIN_ID."' 
			AND (`stock_code`='{$stock_code}' OR REPLACE(REPLACE(`stock_code`,'-',''),'.','')='{$stock_code_xs}') 
			AND `project_id` IN (".implode(',',$arr_projects).")", "{$clsSop->pkey},`more_information`,`is_solded`,`is_deleted`");
			if($is_solded == 1 && $oneSop['is_solded'] == 1){
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'stock_code' => $stock_code,
					'message' => 'Cập nhật thành công đã bán'
				);
			} else if($is_deleted==1 && $oneSop['is_deleted']==1){
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'stock_code' => $stock_code,
					'message' => 'Cập nhật thành công xóa'
				);
			} else {
				if(!empty($oneSop)){
					$more_information = $oneSop['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information['is_solded'] = $is_solded;
					$more_information['is_deleted'] = $is_deleted;
					if($clsSop->updateOne($oneSop[$clsSop->pkey], array(
						'is_solded' => $is_solded,
						'is_deleted' => $is_deleted,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						$apiresults = array(
							'error' => 0,
							'result' => 'success',
							'stock_code' => $stock_code,
							'message' => 'Cập nhật thành công'
						);
					}
				}
			}
		} else {
			$project_id = $block_id = $building_id = $is_stocked = 0;
			$stock_id = $home_direction_id = $bedroom_id = $floor = $code = 0;
			$field = "{$clsStock->pkey},`stock_type`,`more_information`,`bedroom_id`
			,`home_direction_id`,`project_id`,`block_id`,`building_id`,`floor`,`code`";
			$ms_code = str_replace('.', '', $stock_code);
			$ms_code = str_replace('-', '', $ms_code);
			$tmp = $clsStock->getByCond("`is_trash`=0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
			AND (`ms_code`='{$stock_code}' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$stock_code_xs}') 
			AND `project_id` in (".implode(',',$arr_projects).") limit 0,1", $field);
			if(!empty($tmp)){
				$is_stocked = 1;
				$code = $tmp['code'];
				$floor = $tmp['floor'];
				$stock_id = $tmp[$clsStock->pkey];
				$project_id = $tmp['project_id'];
				$block_id = $tmp['block_id'];
				$building_id = $tmp['building_id'];
				$bedroom_id = $tmp['bedroom_id'];
				$home_direction_id = $tmp['home_direction_id'];
				$more_information = $tmp['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(!$DT_TT) $DT_TT = $more_information['DT_TT'];
			}
			$sop_type = _SOP_TYPE_HIGHLEVEL;
			if(strlen($price) <= 4) $price *= 1000000;
			if(strlen($price_owner) <= 4) $price_owner *= 1000000;
			#-- Price/m2
			$price_m2 = 0;
			if($price > 0 && !empty($DT_TT)){
				$price_m2 = round($price / $clsISO->convertToNumber($DT_TT), 2);
			}
			// $clsISO->print_pre($tmp); die();
			if($bedroom_id == 0 && !empty($bedroom_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_BEDROOM' 
					and `slug`='".$clsISO->replaceSpace($bedroom_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $bedroom_id = $tmp[$clsProperty->pkey];
			}
			if($home_direction_id == 0 && !empty($home_direction_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
					and `slug`='".$clsISO->replaceSpace($home_direction_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $home_direction_id = $tmp[$clsProperty->pkey];
			}
			$view_id = 0;
			if(!empty($view_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_VIEW' 
					and `slug`='".$clsISO->replaceSpace($view_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)){
					$view_id = $tmp[$clsProperty->pkey];
				}
			}
			$fee_included = _FEE_INCLUDED_NO_ID;
			if(!empty($fee_included_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_FEE_TYPE' 
					and `slug`='".$clsISO->replaceSpace($fee_included_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $fee_included = $tmp[$clsProperty->pkey];
			}
			$interior_id = _INTERIOR_TYPE_BASIC_ID;
			if(!empty($interior_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_INTERIOR_TYPE' 
					and `slug`='".$clsISO->replaceSpace($interior_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $interior_id = $tmp[$clsProperty->pkey];
			}
			$juridical_id = _JURIDICAL_NO_LOAN_ID;
			if(!empty($juridical_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_JURIDICAL' 
					and `slug`='".$clsISO->replaceSpace($juridical_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $juridical_id = $tmp[$clsProperty->pkey];
			}
			$status_viewing_id = _SOP_STATUS_VIEWING_NO_ID;
			if(!empty($status_viewing)){
				$tmp = $clsProperty->getByCond("`property_type`='_STATUS_VIEWING' 
					and `slug`='".$clsISO->replaceSpace($status_viewing)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $status_viewing_id = $tmp[$clsProperty->pkey];
			}
			$agency_id = 0;
			if(!empty($source_name)){
				$tmp = $clsProperty->getByCond("`property_type`='_SOURCE' 
					and `slug`='".$clsISO->replaceSpace($source_name)."' limit 0,1", $clsProperty->pkey);
				if(!empty($tmp)) $agency_id = $tmp[$clsProperty->pkey];
			}
			$images = array();
			if(!empty($image_folder)){
				$clsProjectMeta = new ProjectMeta();
				$tmp = $clsProjectMeta->crawl($image_folder);
				if(isset($tmp['list_files']) && !empty($tmp['list_files'])){
					foreach($tmp['list_files'] as $okey => $oval){
						$images[] = $clsISO->genGoogleURL($okey, 'view');
					}
				}
			}
			$tmp = $clsSop->getByCond("`user_id`='"._PROFILE_SOP_ADMIN_ID."' AND `stock_code`='{$stock_code}'");
			if(!empty($tmp)){
				$update_data = array();
				$more_information = $tmp['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$more_information['price_m2'] = $price_m2;
				$more_information['is_locked'] = $is_locked;
				$more_information['is_solded'] = $is_solded;
				$more_information['is_deleted'] = $is_deleted;
				$more_information['view_name'] = $view_name;
				$more_information['sop_code'] = $sop_code;
				$update_data['is_locked'] = $is_locked;
				$update_data['is_solded'] = $is_solded;
				if(!empty($images)) $more_information['images'] = $images;
				if($project_id > 0) $update_data['project_id'] = $project_id;
				if($block_id > 0) $update_data['block_id'] = $block_id;
				if($building_id > 0) $update_data['building_id'] = $building_id;
				if($stock_id > 0) $update_data['stock_id'] = $stock_id;
				if($bedroom_id > 0) $update_data['bedroom_id'] = $bedroom_id;
				if($home_direction_id > 0 && $clsSop->compare($tmp, "home_direction_id", $home_direction_id)) {
					$update_data['home_direction_id'] = $home_direction_id;
				}
				if(!empty($floor) && $clsSop->compare($tmp, "floor", $floor)) {
					$update_data['floor'] = $floor;
				}
				if(!empty($code) && $clsSop->compare($tmp, "code", $code)) {
					$update_data['code'] = $code;
				}
				if($status_viewing_id>0 && $clsSop->compare($more_information,'status_viewing_id',$status_viewing_id)) {
					$more_information['status_viewing_id'] = $status_viewing_id;
				}
				if($juridical_id>0 && $clsSop->compare($more_information,'juridical_id',$juridical_id)) {
					$update_data['juridical_id'] = $juridical_id;
					$more_information['juridical_id'] = $juridical_id;
				}
				if($fee_included>0 && $clsSop->compare($more_information,'fee_included',$fee_included)) {
					$update_data['fee_included'] = $fee_included;
					$more_information['fee_included'] = $fee_included;
				}
				if($view_id>0 && $clsSop->compare($more_information,'view_id',$view_id)) {
					$more_information['view_id'] = $view_id;
				}
				if($interior_id>0 && $clsSop->compare($more_information,'interior_id',$interior_id)) {
					$update_data['interior_id'] = $interior_id;
					$more_information['interior_id'] = $interior_id;
				}
				if($agency_id > 0 && $clsSop->compare($tmp, 'agency_id', $agency_id)){
					$update_data['agency_id'] = $agency_id;
					$more_information['agency_id'] = $agency_id;
				}
				if(!empty($short_intro) && $clsSop->compare($more_information,'short_intro',$short_intro)){
					$more_information['short_intro'] = $short_intro;
				}
				if(!empty($contact_name) && $clsSop->compare($more_information,'contact_name',$contact_name)){
					$more_information['contact_name'] = $contact_name;
				}
				if(!empty($contact_phone) && $clsSop->compare($more_information,'contact_phone',$contact_phone)){
					$more_information['contact_phone'] = $contact_phone;
				}
				// $clsISO->print_pre($more_information); die();
				if($clsSop->updateOne($tmp[$clsSop->pkey], array_merge($update_data, array(
					'stock_code' => $stock_code,
					'price' => $price,
					'price_m2' => $price_m2,
					'price_owner' => $price_owner,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'user_id_update' => $profile_id,
					'upd_date' => time(),
				)))){
					if($is_stocked){
						// $clsSop->updateMeta($tmp[$clsSop->pkey]);
					} else {
						// $clsSop->updateMetaNoStockCode($tmp[$clsSop->pkey]);
					}
					$apiresults = array(
						'error' => 0,
						'result' => 'success',
						'stock_code' => $stock_code,
						'message' => 'Cập nhật thành công'
					);
				}
			} else {
				$sop_id = $clsSop->getMaxId();
				$more_information = array(
					'sop_code' => $sop_code,
					'stock_hidden_code' => $stock_hidden_code,
					'hide_code' => _STOCK_HIDECODE_FLOOR_ID,
					'pass_door' => $pass_door,
					'view_id' => $view_id,
					'view_name' => $view_name,
					'DT_TT' => $DT_TT,
					'short_intro' => $short_intro,
					'agency_id' => $agency_id,
					'status_viewing_id' => $status_viewing_id,
					'juridical_id' => $juridical_id,
					'interior_id' => $interior_id,
					'fee_included' => $fee_included,
					'contact_name' => $contact_name,
					'contact_phone' => $contact_phone,
					'notes' => $notes,
					'images' => $images,
					'video_type' => 'upload',
					'sop_type' 	=> $sop_type,
					'is_furnished' 	=> 0,
					'is_owner' 		=> 0,
					'is_exclusive' 	=> 0,
					'is_locked' 	=> $is_locked,
					'is_solded' 	=> $is_solded,
					'is_deleted' 	=> $is_deleted
				);
				// $dbconn->debug = true;
				if($clsSop->insert(array(
					$clsSop->pkey => $sop_id,
					'sop_type' => $sop_type,
					'project_id' => $project_id,
					'block_id' => $block_id,
					'building_id' => $building_id,
					'agency_id' => $agency_id,
					'stock_id' => $stock_id,
					'stock_code' => $stock_code,
					'bedroom_id' => $bedroom_id,
					'home_direction_id' => $home_direction_id,
					'floor' => $floor,
					'code' => $code,
					'price' => $price,
					'price_m2' => $price_m2,
					'price_owner' => $price_owner,
					'fee_included' => $fee_included,
					'juridical_id' => $juridical_id,
					'interior_id' => $interior_id,
					'is_online' => 1,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
					'user_id' => _PROFILE_SOP_ADMIN_ID,
					'user_id_update' => _PROFILE_SOP_ADMIN_ID,
					'is_locked' 	=> $is_locked,
					'is_solded' 	=> $is_solded,
					'reg_date' => time(),
					'upd_date' => time(),
				))){
					if($is_stocked){
						$clsSop->updateMeta($sop_id);
					} else {
						$clsSop->updateMetaNoStockCode($sop_id);
					}
					$apiresults = array(
						'error' => 0,
						'result' => 'success',
						'stock_code' => $stock_code,
						'message' => 'Thêm mới thành công'
					);
				}
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});
$app->post('/v1/telesale/sync', function ($request, $response) use ($app) {
	global $dbconn, $clsISO, $core;
	#- Require library
	$clsISO = new ISO();
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsSetting = new Setting();
	$clsProperty = new Property();
	$clsTelesale = new Telesale();
	$clsProfile = new Profile();
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'message' => "Error"
	);
	$tblData = $request->getParsedBody();
	$stock_type = $core->get_field($tblData, 'stock_type', _BLOCK_TYPE_LOWFLOOR_SALE);
	$project_id = $core->get_field($tblData, 'project_id', _PROJECT_VHOP2_ID);
	$reg_date = $core->get_field($tblData, "reg_date", date('d-m-Y'));
	$status_name = $core->get_field($tblData, "status_name", "");
	$product_code = $core->get_field($tblData, "product_code", "");
	$stock_code = $core->get_field($tblData, "stock_code", "");
	$DT_TT = $core->get_field($tblData, "DT_TT", "");
	$contact_name = $core->get_field($tblData, "contact_name", "");
	$contact_phone = $core->get_field($tblData, "contact_phone", "");
	$sales_contact_info = $core->get_field($tblData, "sales_contact_info", "");
	$owner_notes = $core->get_field($tblData, "owner_notes", "");
	$block_name = $core->get_field($tblData, "block_name", "");
	$type_name = $core->get_field($tblData, "type_name", "");
	$bedroom_name = $core->get_field($tblData, "bedroom_name", "");
	$price_owner = $core->get_field($tblData, "price_owner", "");
	$fee_included_name = $core->get_field($tblData, "fee_included_name", "");
	$price = $core->get_field($tblData, "price", "");
	$home_direction_name = $core->get_field($tblData, "home_direction_name", "");
	$view_name = $core->get_field($tblData, "view_name", "");
	$finish_status_name = $core->get_field($tblData, "finish_status_name", "");
	$interior_name = $core->get_field($tblData, "interior_name", "");
	$juridical_name = $core->get_field($tblData, "juridical_name", "");
	$image_folder = $core->get_field($tblData, "image_folder", "");
	$status_viewing = $core->get_field($tblData, "status_viewing", "");
	$sale_care_name = $core->get_field($tblData, "sale_care_name", "");
	$notes = $core->get_field($tblData, "notes", "");
	// $clsISO->print_pre($tblData); die();
	// $clsISO->print_pre($status_name); die();
	if(!empty($stock_code)){
		#- Tình trạng
		$status_id = 0;
		if(!empty($status_name)){
			$tmp = $clsSetting->getByCond("`is_trash`=0 and `_type`='_STATUS_TELESALE' 
				AND `slug`='".$clsISO->replaceSpace($status_name)."'", $clsSetting->pkey);
			if(!empty($tmp)){
				$status_id = $tmp[$clsSetting->pkey];
				unset($tmp);
			} else {
				$status_id = $clsSetting->getMaxId();
				$clsSetting->insert(array(
					$clsSetting->pkey => $status_id,
					'_type' => '_STATUS_TELESALE',
					'title' => $status_name,
					'slug' => $clsISO->replaceSpace($status_name),
					'order_no' => $clsSetting->getMaxOrderNo(),
					'reg_date' => time()
				));
			}
		}
		#- Loại căn
		$type_id = 0;
		if(!empty($type_name)){
			$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE_VILLA' 
				AND `slug`='".$clsISO->replaceSpace($type_name)."'", $clsProperty->pkey);
			// $clsISO->print_pre($tmp); die();
			$type_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
		}
		#- Loại căn
		$bedroom_id = 0;
		if(!empty($bedroom_name)){
			$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BEDROOM' 
				AND `slug`='".$clsISO->replaceSpace($bedroom_name)."'", $clsProperty->pkey);
			$bedroom_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
		}
		#- Pháp lý
		$juridical_id = 0;
		if(!empty($juridical_name)){
			$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_JURIDICAL' 
				AND `slug`='".$clsISO->replaceSpace($juridical_name)."'", $clsProperty->pkey);
			if(!empty($tmp)){
				$juridical_id = $tmp[$clsProperty->pkey];
				unset($tmp);
			}
		}
		#- Xem nhà
		$status_viewing_id = 0;
		if(!empty($status_viewing)){
			$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS_VIEWING' 
				AND `slug`='".$clsISO->replaceSpace($status_viewing)."'", $clsProperty->pkey);
			if(!empty($tmp)){
				$status_viewing_id = $tmp[$clsProperty->pkey];
				unset($tmp);
			}
		}
		#- Nội thất
		$interior_id = 0;
		if(!empty($interior_name)){
			$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_INTERIOR_TYPE' 
				AND `slug`='".$clsISO->replaceSpace($interior_name)."'", $clsProperty->pkey);
			if(!empty($tmp)){
				$interior_id = $tmp[$clsProperty->pkey];
				unset($tmp);
			}
		}
		#- Bao phí
		$fee_included = 0;
		if(!empty($fee_included_name)){
			$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_FEE_TYPE' 
				AND `slug`='".$clsISO->replaceSpace($fee_included_name)."'", $clsProperty->pkey);
			if(!empty($tmp)){
				$fee_included = $tmp[$clsProperty->pkey];
				unset($tmp);
			}
		}
		#- Hoàn thiện
		$finish_status_id = 0;
		if(!empty($finish_status_name)){
			$tmp = $clsSetting->getByCond("`is_trash`=0 AND `_type`='_FINISH_STATUS' 
				AND `slug`='".$clsISO->replaceSpace($finish_status_name)."'", $clsSetting->pkey);
			if(!empty($tmp)){
				$finish_status_id = $tmp[$clsSetting->pkey];
				unset($tmp);
			} else {
				$finish_status_id = $clsSetting->getMaxId();
				$clsSetting->insert(array(
					$clsSetting->pkey => $finish_status_id,
					'_type' => '_FINISH_STATUS',
					'title' => $finish_status_name,
					'slug' => $clsISO->replaceSpace($finish_status_name),
					'order_no' => $clsSetting->getMaxOrderNo(),
					'reg_date' => time()
				));
			}
		}
		#- Price format
		if(!empty($price)){
			$price = $clsISO->processSmartNumber($price); 
			if(strlen($price) <= 7){
				$price = $price * 1000000;
			}
		}
		if(!empty($price_owner)){
			$price_owner = $clsISO->processSmartNumber($price_owner); 
			if(strlen($price_owner) <= 7){
				$price_owner = $price_owner * 1000000;
				// $clsISO->print_pre($price_owner); die();
			}
		}
		#- Sales Care
		$sale_care_id = 0;
		if(!empty($sale_care_name)){
			$tmp = $clsProfile->getByCond("`full_name_slug`='".$clsISO->replaceSpace($sale_care_name)."'", $clsProfile->pkey);
			$sale_care_id = !empty($tmp) ? $tmp[$clsProfile->pkey] : 0;
		}
		#
		$block_id = 0;
		if(!empty($block_name)){
			$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND `slug`='".$clsISO->replaceSpace($block_name)."'", $clsProperty->pkey);
			$block_id = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
		}
		# End Format
		$tmp = $clsTelesale->getByCond("`stock_code`='{$stock_code}'");
		if(!empty($tmp)){
			$more = array();
			$more_information = $tmp['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['product_code'] = $product_code;
			$more_information['DT_TT'] = $DT_TT;
			$more_information['contact_name'] = $contact_name;
			$more_information['contact_phone'] = $contact_phone;
			$more_information['sales_contact_info'] = $sales_contact_info;
			$more_information['owner_notes'] = $owner_notes;
			$more_information['image_folder'] = $image_folder;
			$more_information['status_viewing'] = $status_viewing;
			$more_information['fee_included'] = $fee_included;
			$more_information['finish_status_id'] = $finish_status_id;
			$more_information['interior_id'] = $interior_id;
			$more_information['juridical_id'] = $juridical_id;
			$more_information['status_viewing_id'] = $status_viewing_id;
			$field = "{$clsStock->pkey},`stock_type`,`project_id`,`block_id`,`building_id`,`bedroom_id`";
			$tmp_stock = $clsStock->getByCond("`is_trash`=0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND `ms_code`='{$stock_code}'", $field);
			if(!empty($tmp_stock)){
				$more['stock_id'] = $tmp_stock[$clsStock->pkey];
				$more['stock_type'] = $tmp_stock['stock_type'];
				$more['project_id'] = $tmp_stock['project_id'];
				$more['block_id'] = $tmp_stock['block_id'];
				$more['building_id'] = $tmp_stock['building_id'];
				$more['bedroom_id'] = $tmp_stock['bedroom_id'];
			} else {
				$more['bedroom_id'] = $bedroom_id;
				$more['stock_type'] = $stock_type;
				$more['project_id'] = $project_id;
				$more['block_id'] = $block_id;
			}
			if($clsTelesale->updateOne($tmp[$clsTelesale->pkey], array_merge($more, array(
				'project_id' => $project_id,
				'block_id' => $block_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'sale_care_id' => $sale_care_id,
				'stock_code' => $stock_code,
				'price_owner' => $price_owner,
				'price' => $price,
				'fee_included' => $fee_included,
				'finish_status_id' => $finish_status_id,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			)))){
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'message' => 'Cập nhật thành công'
				);
			}
		} else {
			$more = array();
			$field = "{$clsStock->pkey},`stock_type`,`project_id`,`block_id`,`building_id`,`bedroom_id`";
			$tmp_stock = $clsStock->getByCond("`is_trash`=0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 
				AND `ms_code`='{$stock_code}'", $field);
			if(!empty($tmp_stock)){
				$more['stock_id'] = $tmp_stock[$clsStock->pkey];
				$more['stock_type'] = $tmp_stock['stock_type'];
				$more['project_id'] = $tmp_stock['project_id'];
				$more['block_id'] = $tmp_stock['block_id'];
				$more['building_id'] = $tmp_stock['building_id'];
				$more['bedroom_id'] = $tmp_stock['bedroom_id'];
			} else {
				$more['stock_type'] = $stock_type;
				$more['project_id'] = $project_id;
				$more['block_id'] = $block_id;
				$more['bedroom_id'] = $bedroom_id;
			}
			$more_information = $tblData;
			$more_information['fee_included'] = $fee_included;
			$more_information['finish_status_id'] = $finish_status_id;
			$more_information['interior_id'] = $interior_id;
			$more_information['juridical_id'] = $juridical_id;
			$more_information['status_viewing_id'] = $status_viewing_id;
			// $clsISO->print_pre($tmp_stock); die();
			if($clsTelesale->insert(array_merge($more, array(
				'type_id' => $type_id,
				'status_id' => $status_id,
				'sale_care_id' => $sale_care_id,
				'stock_code' => $stock_code,
				'price_owner' => $price_owner,
				'price' => $price,
				'fee_included' => $fee_included,
				'finish_status_id' => $finish_status_id,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'reg_date' => $clsISO->convertTextToTime($reg_date),
				'upd_date' => $clsISO->convertTextToTime($reg_date)
			)))){
				$apiresults = array(
					'error' => 0,
					'result' => 'success',
					'message' => 'Thêm mới thành công'
				);
			}
		}
	}
	// Return
	echo echoResponse('200', $apiresults);
});