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
function default_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsMember = new Member();
	$clsProperty = new Property();
	$clsRequestPTG = new RequestPTG();
	$assign_list['clsStock'] = $clsStock;
	$assign_list['clsProfile'] = $clsProfile;
	$assign_list['clsRequestPTG'] = $clsRequestPTG;	
	
	$status = Input::get("status","");
	$cond = $cond_all = "1=1";
	if($status != "") {
		$cond.= " AND `t1`.`status_id`='{$status}'";
		$cond_all.= " AND `status_id`='{$status}'";
	}
	#
	$list_ranges = array(
		'24h' => array(
			'title' => '24 giờ',
			'avg_time' => '0'
		),
		'1-2day' => array(
			'title' => '1-2 ngày',
			'avg_time' => '0'
		),
		'3-7days' => array(
			'title' => '3-7 ngày',
			'avg_time' => '0'
		)
	);
	$current_time = time();
	foreach($list_ranges as $key => $val){
		if($key == '24h'){
			$start_time = strtotime('-24 hours', $current_time);
			$end_time = $current_time;
			$sql = " and (`reg_date` between {$start_time} and {$end_time})";
		} else if($key == '1-2day'){
			$start_time = strtotime('-2 days', $current_time);
			$end_time = strtotime('-1 day', $current_time);
			$sql = " and (`reg_date` between {$start_time} and {$end_time})";
		} else if($key == '3-7days'){
			$start_time = strtotime('-7 days', $current_time);
			$end_time = strtotime('-3 days', $current_time);
			$sql = " and (`reg_date` between {$start_time} and {$end_time})";
		}
		$tmp = $dbconn->getRow("SELECT AVG(upd_date-reg_date) as `avg_time` 
			FROM {$clsRequestPTG->tbl} 
			WHERE `status_id`='1' and `upd_date`>=`reg_date`".$sql);
		if(!empty($tmp)){
			$list_ranges[$key]['avg_time'] = $clsISO->getTime($tmp['avg_time']);
		}
	}
	$assign_list['list_ranges'] = $list_ranges;
	$total_record = $clsRequestPTG->countItem("1=1");
	$lst_success = $clsRequestPTG->getAll("`status_id` = '1'","reg_date,upd_date");
	$total_success = count($lst_success);
	$total_pendding = $clsRequestPTG->countItem("`status_id` = '0'");
	
	$current_page = Input::get('page',1);
	$per_page  = 50;
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";	
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link'	=> str_replace(".html","/",$clsISO->getLink("request_ptg"))
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(1,$str_url);
	$assign_list["html_pager"] = $html_pager;
	#
	$order_by = " ORDER BY status_id ASC,`reg_date` DESC";
	$list_requests = $dbconn->getAll("SELECT `t1`.*,`t2`.`ms_code`,`t2`.`stock_type`,`t2`.`more_information` 
		FROM `{$clsRequestPTG->tbl}` as `t1` INNER JOIN `{$clsStock->tbl}` as `t2` ON `t1`.`stock_id`=`t2`.`stock_id` 
		WHERE ".$cond.$order_by.$limitCond);
	// $clsISO->print_pre($list_requests); die();
	$arr_profile_cached = $arr_member_cached = $arr_property_cached = array();
	foreach ($list_requests as $key => $val) {
		$user_id = $val['user_id'];
		$stock_type = $val['stock_type'];
		$more_information = $val['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$price_sheets = isset($more_information['price_sheets']) 
				&& !empty($more_information['price_sheets']) ? $more_information['price_sheets'] : array();
		} else {
			$price_sheets = isset($more_information['price_temporary_ns']) 
				&& !empty($more_information['price_temporary_ns']) ? $more_information['price_temporary_ns'] : "";
			if(empty($price_sheets)) {
				$price_sheets = isset($more_information['price_sheets']) 
				&& !empty($more_information['price_sheets']) ? $more_information['price_sheets'] : array();
			}
		}	
		$list_requests[$key]['price_sheets'] = $price_sheets;
		if($val['status_id'] == 1) {
			$time = $val["upd_date"] - $val['reg_date'];
			$list_requests[$key]['time_feedback'] = $time;
			unset($time);
		}else{
			$time = time() - $val['reg_date'];
			$list_requests[$key]['time_waiting'] = $time;
			unset($time);
		}
		if($val['_from'] == 1) {
			if(!isset($arr_profile_cached[$user_id])){
				$oStaff = $clsProfile->getOne($user_id, "full_name,first_name,last_name,department_id");
				$department_id = $oStaff['department_id'];
				$arr_profile_cached[$user_id] = sprintf('<span class="text-main">(%s)</span> %s', 
					$clsProperty->getTitle($department_id),
					$clsProfile->getFullName($user_id, $oStaff));
			}
			$list_requests[$key]['staff_name'] = $arr_profile_cached[$user_id];
		}else{
			if(!isset($arr_member_cached[$user_id])){
				$oStaff = $clsMember->getOne($user_id, "full_name,first_name,last_name,phone");
				$arr_member_cached[$user_id] = sprintf('<span class="text-main">[MOC]</span> %s', 
					$clsMember->getFullName($user_id, $oStaff)) . '<a href="https://zalo.me/'.$oStaff['phone'].'" target="_blank" class="zalo_chat"></a>';
			}			
			$list_requests[$key]['staff_name'] = $arr_member_cached[$user_id];
		}
		unset($price_sheets);
	}
	$assign_list['avg_time_feedback'] = $avg_time_feedback;	
	$assign_list['list_requests'] = $list_requests;	
	$assign_list['total_record'] = $total_record;	
	$assign_list['total_pendding'] = $total_pendding;	
	$assign_list['total_success'] = $total_record - $total_pendding;	
	$assign_list['status'] = $status;	
    /*=============Title & Description Page==================*/
	$title_page = 'Danh sách yêu cầu phiếu tính giá | ' . BRAND_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_request_PTG(){
	global $smarty,$core,$clsISO,$oneProfile,$deviceType,$dbconn; 
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsRequestPTG = new RequestPTG();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	
	$html = '';
	$list_items = $dbconn->getAll("SELECT `t1`.*,`t2`.`ms_code`,`t2`.`more_information` FROM `{$clsRequestPTG->tbl}` as `t1` JOIN `{$clsStock->tbl}` as `t2` ON `t1`.`stock_id` = `t2`.`stock_id` WHERE `t1`.`status_id`='0' ORDER BY status_id,reg_date DESC LIMIT 0,9");
	if(!empty($list_items)){ 
		$ii = 0;
		foreach($list_items as $key => $val){
			$more_information = $clsISO->to_array_json($val['more_information']);
			$price_sheets = isset($more_information['price_sheets']) && !empty($more_information['price_sheets']) ? $more_information['price_sheets'] : array();
			$html.= '<tr>'.(($deviceType != 'phone') ? '<td class="text-center">'.($ii+1).'</td>' : '').'
			<td><a href="javascript:void(0)" '.(($deviceType == 'phone')?'onClick="$Core.helper.open_stock('.$val["stock_id"].');"' : 'data-url="'.PCMS_URL.'/index.php?mod=home&sub=project&act=load_stock_popover&stock_id='.$val["stock_id"].'" data-toggle="webui-popover" data-trigger="click" data-placement="auto"').' data-width="350">'.$val['ms_code']."</a>";
				if ($deviceType == 'phone' && $val['status_id'] == '0'){
					$html .= '<div class="btn-group ml-1">
							<button data-toggle="ripple" type="button" onclick="$Core.helper.open_quick_stock(this,event)" tp="sheet_price" stock_id="'.$val["stock_id"].'" class="btn btn-icon btn-sm btn-outline-default text-nowrap" from="request_ptg"><i class="fa fa-plus"></i> </button>
							<button data-toggle="ripple" type="button" onclick="$Core.helper.soldout_stock(this,event)" tp="sheet_price" stock_id="'.$val["stock_id"].'" request_ptg_id="'.$val["id"].'" class="btn btn-icon btn-sm btn-outline-success text-nowrap" from="request_ptg"><i class="fa fa-check"></i></button>';
					if(!empty($price_sheets)) {
						$html .='<button data-toggle="ripple" type="button" onclick="$Core.helper.exist_ptg(this,event)" tp="sheet_price" stock_id="'.$val["stock_id"].'" class="btn btn-icon btn-sm btn-outline-danger text-nowrap" from="request_ptg"><i class="bx bx-circle"></i></button>';
					}
					$html .='</div>';	
				}
			
			$html .='</td>';
			$html .='<td class="align-center text-center">'.$clsProfile->getFullName($val['user_id']).'</td>';
			$html .='<td class="align-center">
						<div class="btn-group">
							<button data-toggle="ripple" type="button" onclick="$Core.helper.open_quick_stock(this,event)" tp="sheet_price" stock_id="'.$val["stock_id"].'" class="btn btn-sm btn-outline-default text-nowrap" from="request_ptg"><i class="fa fa-plus"></i> PTG</button>
							<button data-toggle="ripple" type="button" onclick="$Core.helper.soldout_stock(this,event)" tp="sheet_price" stock_id="'.$val["stock_id"].'" request_ptg_id="'.$val["id"].'" class="btn btn-sm btn-outline-success text-nowrap" from="request_ptg"><i class="fa fa-check"></i> Đã bán</button>';
				if(!empty($price_sheets)) {
					$html .='<button data-toggle="ripple" type="button" onclick="$Core.helper.exist_ptg(this,event)" tp="sheet_price" stock_id="'.$val["stock_id"].'"  class="btn btn-sm btn-outline-danger text-nowrap" from="request_ptg"><i class="bx bx-circle"></i> Đã có</button>';
				}
			$html .= '</div>
				</td>
			</tr>';
			++$ii;
		}
	}else{
		$html.= '<tr>
			<td class="text-center" colspan="4">Danh sách trống</td>
		</tr>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_requestPTG(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$core,$clsModule
	,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$oneProfile,$header_configs;
	$clsRequestPTG = new RequestPTG();
	$clsProfile = new Profile();
	$clsStock = new Stock();
	$clsFcmToken = new FcmToken(); 
	$clsNotify = new Notify(); 
	$clsProperty = new Property(); 
	$clsNotification = new Notification();
	###
	$uid = $clsISO->getUniqid();
	$department_id = $oneProfile['department_id'];
	$stock_id = (int)Input::post('stock_id', 0);
	$smarty->assign("stock_id",$stock_id);
	$action = Input::post("action","_OPEN");
	###
	$response = ["result"	=>	false];
	if($action == "_OPEN") {
		$html = $core->build('_ajax.open_request_ptg.tpl');
		$response = [
			"result"	=>	true,
			"uid"		=>	$clsISO->getUniqid(),
			"html"		=>	$html
		];
	}else if($action == "_SAVE"){
		$content = Input::post("content");
		$is_urgent = (int) Input::post("is_urgent",0);
		$oStock = $clsStock->getOne($stock_id, "ms_code,block_id");
		$request_ptg_id = $clsRequestPTG->getMaxId();
		$insert_data = array(
			$clsRequestPTG->pkey =>	$request_ptg_id,
			"stock_id"	=> $stock_id,
			"user_id"	=> $profile_id,
			"is_urgent" => $is_urgent,
			"notes"	=> addslashes($content),
			"reg_date" => time()
		);
		$arr_admin_zalo = array();
		$field = "{$clsProfile->pkey},`full_name`,`phone`,`more_information`";
		$list_admins = [];
		if(!empty($header_configs["role_request_ptg"])) {
			$role_request_ptg = $clsISO->to_array_json($header_configs["role_request_ptg"]);
			$list_admins = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `role_id` IN (".implode(",",$role_request_ptg).") and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and {$clsProfile->pkey}<>'{$profile_id}'", $field);
		}
		if(!empty($list_admins)){
			foreach($list_admins as $k => $v) {
				$more_information = $v['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(!$clsISO->checkItemInArray($v[$clsProfile->pkey],$arr_admin)) {
					$arr_admin[] = $v[$clsProfile->pkey];
					$zaloId = $clsProfile->getZaloId($v[$clsProfile->pkey]);
					if(!empty($zaloId)){
						$arr_admin_zalo[] = array(
							'full_name' => $v['full_name'],
							'phone_number' => $v['phone'],
							'user_id' => $zaloId
						);
					}
				}
			}
			unset($list_admins);
		}
		$head_of_dep = $clsProfile->getHeadOfDep($department_id);
		if($head_of_dep > 0 && $head_of_dep != $profile_id){
			$arr_admin[] = $head_of_dep;
		}
		
		#- GĐ Dự án
		$block_id = $oStock['block_id'];
		$oneBlock = $clsProperty->getOne($block_id,"more_information");
		if(!empty($oneBlock)) {
			$more_information = $oneBlock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$project_manager_id = (int) $core->get_field($more_information, 'project_manager', 0);
			if($project_manager_id > 0 && !in_array($project_manager_id, $arr_admin)) {
				$arr_admin[] = $project_manager_id;				
			}
		}
		$arr_admin = @array_unique($arr_admin);
		if($clsRequestPTG->insert($insert_data)){
			$titleNoty = sprintf('<strong>%s(%s)</strong> yêu cầu phiếu tính giá căn [<strong>%s</strong>]', 
			$clsProfile->getFullName($profile_id, $oneProfile), $clsProperty->getTitle($department_id), $oStock['ms_code']);
			$clsNotify->insertNotify('RequestPTG',$clsRequestPTG->pkey, $request_ptg_id, $titleNoty, time(), $arr_admin);
			/** Gửi thông báo Zalo */
			if(!empty($arr_admin_zalo) && SEND_ZALO == 1){
				foreach($arr_admin_zalo as $key => $val){
					$message = "Xin chào {$val['full_name']}\r".strip_tags($titleNoty)."\rHãy truy cập ".DOMAIN_URL."/yeu-cau-phieu-tinh-gia.html để phản hồi yêu cầu!";
					$clsZalo = new Zalo();
					$clsZalo->sendMsgSchedule($val['user_id'], $val['phone_number'], $message);
				}
			}
			/** Gửi thông báo tới người phụ trách */
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id` in (".implode(',', $arr_admin).") and `token`<>''", "token");		
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => ($is_urgent==1?"[GẤP] ":"")."Yêu cầu phiếu tính giá",
					'message' => strip_tags($titleNoty),
					'url' => PCMS_URL . $clsISO->getLink("request_ptg")
				), $subscribers);
			}
			$response = array(
				"result" =>	true,
				"html"	 =>	'<button class="btn btn-sm btn-success" type="button" style="cursor:no-drop">
					<i class="fa fa-check"></i> 
					<span>Đã yêu cầu PTG</span>
				</button>'
			);
			
		}
	}
	// Return
	echo json_encode($response);die;
}
function default_exist_ptg(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsStock = new Stock();
	$clsNotify = new Notify();
	$clsNotifyMOC = new NotifyMOC();
	$clsFcmToken = new FcmToken();
	$clsRequestPTG = new RequestPTG();
	$clsProfile = new Profile();
	$clsNotification = new Notification();
	###
	$msg = "_error";
	$stock_id = (int) Input::post('stock_id', 0);
	$field = "stock_type,ms_code,type_id,status_id,logs,more_information";
	$oStock = $clsStock->getOne($stock_id, $field);
	#cập nhật request PTG và gửi thông báo
	$arr_user_request = $arr_request_id = $arr_user_requestMOC = $arr_request_MOC_id = array();
	$list_requests = $clsRequestPTG->getAll("`stock_id`='{$stock_id}' AND `status_id`='0'");
	$set = "`status_id`='1',`user_updated_id`='{$profile_id}',`upd_date`='".time()."'";
	$clsRequestPTG->updateByCond("`stock_id`='{$stock_id}' AND `status_id`='0'", $set);		
	if(!empty($list_requests)){
		$msg = "_success";
		foreach($list_requests as $k => $v) {
			if($v['_from'] == 1) {
				$arr_request_id[] = $v[$clsRequestPTG->pkey];
				if(!$clsISO->checkItemInArray($v['user_id'],$arr_user_request) && $v['user_id'] != $profile_id) {
					$arr_user_request[] = $v['user_id'];
					$clsNotify->sendEmailRequestPTG($v['user_id'],$oStock['ms_code'],"exist");
				}
			}else{
				$arr_request_MOC_id[] = $v[$clsRequestPTG->pkey];
				if(!$clsISO->checkItemInArray($v['user_id'],$arr_user_requestMOC)) {
					$arr_user_requestMOC[] = $v['user_id'];
				}
			}
			
		}
		
		$list_request_notify = array();
		if(!empty($arr_request_id)) {
			$list_request_notify = $clsNotify->getAll("`tbl`='RequestPTG' and `pkey`='id' 
			and `pval` in (".implode(',',$arr_request_id).")");
			if(!empty($list_request_notify)){
				foreach($list_request_notify as $key => $val){
					$clsNotify->updateOne($val[$clsNotify->pkey], array(
						'list_user_read' => $val['list_user_slash']
					));
				}
				unset($list_request_notify);
			}
		}
		
		if(!empty($arr_user_request)) {	
			$titleNoty = sprintf('<strong>%s</strong> báo căn [<strong>%s</strong>] đã có phiếu tính giá', 
			$clsProfile->getFullName($profile_id, $oneProfile), $oStock['ms_code']);
			$clsNotify->insertNotify('Stock',$clsStock->pkey, $stock_id, $titleNoty, time(), $arr_user_request);
			/** Gửi thông báo tới người gửi yêu cầu */
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id` in (".implode(',', $arr_user_request).") and `token`<>''", "token");	
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => "Cập nhật phiếu tính giá",
					'message' => strip_tags($titleNoty),
					'url' => PCMS_URL . $clsStock->getLink($oStock["ms_code"])
				), $subscribers);
			}			
			#thong bao app
			$params = [
				'title' => "Yêu cầu PTG",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL . $clsStock->getLink($oStock["ms_code"])
			];
			$clsNotification->doPushMessagingUser($params,$arr_user_request);
		}
	}
	// Return
	echo $msg; die();
}
?>