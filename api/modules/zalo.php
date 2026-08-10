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
$app->post('/v1/zalo/chatlogs', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$helper = new Helper();
	$clsZaloUser = new ZaloUser();
	$clsZaloGroup = new ZaloGroup();
	$clsZaloChatLog = new ZaloChatLog();
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
	// $clsISO->print_pre($sender_id); die();
	if($event == 'GROUP_RECEIVED_MESSAGE' && in_array($msgType, ['webchat']) 
		&& $clsZaloChatLog->countItem("`id_chat`='{$id_chat}'") == 0){
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
				$clsZaloUser->init($sender_id);
				if($clsZaloChatLog->insert(array(
					'id' => $clsZaloChatLog->getMaxId(),
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