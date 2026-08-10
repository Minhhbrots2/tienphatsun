<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
$app->post('/v1/webhook/zalo-group/listen-chat', function ($request, $response, $args) use ($app) {
	global $dbconn, $clsISO;
	$clsZaloGroup = new ZaloGroup();
	$clsZaloChatLog = new ZaloChatLog();
	$clsConfiguration = new Configuration();
	$apiresults = array(
		'error' => 1, 
		'result' => 'error',
		'message' => "Error"
	);
	$inputs = $request->getParsedBody();
	$event = isset($inputs['event']) ? trim($inputs['event']) : "UNKOWN";
	$id_chat = isset($inputs['id']) ? $inputs['id'] : "";
	$id_group = isset($inputs['thread_id']) ? $inputs['thread_id'] : 0;
	$sender_id = isset($inputs['sender_id']) ? (int) $inputs['sender_id'] : 0;
	$msgType = $inputs['data']['data']['groupMsgs'][0]['msgType'];
	// 'chat.photo',
	if($event == 'GROUP_RECEIVED_MESSAGE' && in_array($msgType,['webchat']) 
		&& $clsZaloChatLog->countItem("`id_chat`='{$id_chat}'") == 0){
		$group_id = 0;
		if((int) $id_group > 0 ){
			$is_blocked = 0;
			$tmp = $clsZaloGroup->getByCond("`id_group`='{$id_group}'");
			if(!empty($tmp)) $is_blocked = $tmp['is_blocked'];
			if($is_blocked == 0){
				if(!empty($tmp)){
					$group_id = $tmp[$clsZaloGroup->pkey];
				} else {
					$curl = new \Curl\Curl();
					$curl->setHeaders(array(
						'Content-Type' => 'application/json',
						'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MjQyNWQ0NGRlM2U0MjE2NTI2ZGYxZiIsImlhdCI6MTc0NzE5OTQ0NCwiZXhwIjoxNzc4NzM1NDQ0fQ.XmOOFIcKs8rOsAxYiVl-O5NNm132rMmtMv8KGYSq0W8'
					));
					$curl->post('https://public-api.bizflow.vn/functions/682425d44de3e4216526df1f', array(
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
				if($clsZaloChatLog->insert(array(
					'id_chat' => $id_chat,
					'id_group' => $id_group,
					'sender_id' => $sender_id,
					'group_id' => $group_id,
					'data_logs' => json_encode($inputs, JSON_UNESCAPED_UNICODE),
					'created_at' => time()
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