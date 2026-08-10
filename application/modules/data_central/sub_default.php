<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$clsConfiguration,$oneProfile;
	$clsDataCentral = new DataCentral(); $assign_list['clsDataCentral'] = $clsDataCentral;
	$clsStock = new Stock();
	$clsCampaign = new Campaign();
	##	
	if(!$clsISO->checkPermission("data_central")){
		$core->redirect('/');
	}
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$list_data_fields = $clsConfiguration->getValue('field_data_center');
	$list_data_fields = $clsISO->to_array_json($list_data_fields);
	$arr_columns = $def_field = array();
	foreach($list_data_fields as $key => $val) {
		if(!empty($val['is_default'])) {
			$def_field[] = $key;
		}
	}
	###
	$more_information = $oneProfile['more_information'];
	$list_setting_field = $core->get_field($more_information, "fieldDataCentral", $def_field);
	if(!empty($list_setting_field)){
		foreach($list_setting_field as $id) {
			$arr_columns[$list_data_fields[$id]['code']] = $list_data_fields[$id];
		}
	}
	$assign_list["arr_columns"] = $arr_columns;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý dữ liệu cư dân Ocean Park - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = ' Thông tin cư dân - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_load_data_central(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$deviceType,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsTag = new Tag();
	$clsPolicy = new Policy();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsDataCentral  = new DataCentral ();
	$clsCampaign  = new Campaign();
	$clsFollowUp  = new FollowUp();
	$smarty->assign("clsDataCentral",$clsDataCentral);
	###
	$keyword = Input::post("keyword",""); 
	$tags = Input::post("tags",array());
	$project_id = (int) Input::post('project_id', 0); 
	$project_id = ($project_id > 0) ? $project_id : _PROJECT_DEF_ID;
	$campaign_id = (int) Input::post('campaign_id', 0); 
	$blocks_ids = Input::post('blocks_ids', array());
	$building_ids = Input::post('building_ids', array());
	$birthday = Input::post('birthday', "");
	$date_call = Input::post('date_call', "");
	$status_id = Input::post('status_id', "");
	$_tp = Input::post('_tp', "");
	
	$assign_list["project_id"] = $project_id;
	$assign_list["keyword"] = $keyword;
	$assign_list["tags"] = $tags;
	$assign_list["blocks_ids"] = $blocks_ids;
	$assign_list["building_ids"] = $building_ids;
	$assign_list["birthday"] = $birthday;
	$assign_list["date_call"] = $date_call;
	$assign_list["status_id"] = $status_id;
	$assign_list["_tp"] = $_tp;
	$assign_list["campaign_id"] = $campaign_id;
	// $clsISO->print_pre($_POST);die;
	$cnd = $cond = "1=1";
	$html_briefs = "";
	$list_campaign = $clsCampaign->getAll("`campaign_type`='_data_central'",$clsCampaign->pkey.",`title`,`campaign_info`,`start_date`,`end_date`");
	$arr_campaign = $arr_staff = [];
	$lstProfile = $clsProfile->getProfileCached("active");
//	$clsISO->print_pre($lstProfile);die;
	$check_expired = 0;
	if(!empty($list_campaign)) {
		foreach ($list_campaign as $key => $val) {
			if($campaign_id == $val[$clsCampaign->pkey]) {
				if($val["end_date"] < time() || $val["start_date"] > time()) {
					$check_expired = 1;
				}
			}
			$campaign_info = $clsISO->to_array_json($val["campaign_info"]);
			$data_staff = !empty($campaign_info["data_staff"]) ? $campaign_info["data_staff"] : [];
			foreach ($data_staff as $staffId => $data_ids) {
				if(isset($lstProfile[$staffId])) {					
					foreach ($data_ids as $k => $data_id) {
						$more_profile = $lstProfile[$staffId]["more_information"];
						$arr_staff[$data_id][] = '<div><span class="mb-0 text-info">['.$more_profile["department_name"].']'.$lstProfile[$staffId]["full_name"].'</span>-<span class="mb-0 text-warning fw-bold">'.$val["title"].'</span></div>' ;
					}					
				}
			}
			/*$arr_campaign[$val[$clsCampaign->pkey]] = [
				"{$clsCampaign->pkey}"	=>	$val[$clsCampaign->pkey],
				"title"					=>	$val["title"],
				"data_staff"			=>	$data_staff,
			];*/
		}
	}	
	$assign_list["check_expired"] = $check_expired;
//			$clsISO->print_pre($arr_staff);die;
//	$clsISO->print_pre($arr_campaign);die;
	if($_tp == "_campaign" ){
		$cond_camp = "`campaign_type`='_data_central'";
		if(!$clsISO->checkPermissionGroup('DIRECTOR')) {
			$cond_camp .= " AND JSON_CONTAINS(campaign_info, '{$profile_id}', '$.list_staffs')";
		}
		$lstCampaign = $clsCampaign->getAll($cond_camp,$clsCampaign->pkey.",`campaign_info`");
		$lst_campaign_id = [];
		if(!empty($lstCampaign)) {
			$cond_data = "";
			foreach ($lstCampaign as $key => $val) {
				$campaign_info = $clsISO->to_array_json($val["campaign_info"]);
				$data_staff = !empty($campaign_info["data_staff"]) ? $campaign_info["data_staff"] : [];
				if(!empty($data_staff[$profile_id])) {
					$cond_data .= ($cond_data != "" ? " OR " : "" ) . " `{$clsDataCentral->pkey}` IN (".implode(',',$data_staff[$profile_id]).")";
				}				
			}
			if($cond_data != "") {
				$cond .= "	AND (".$cond_data.")";
				$cnd .= "	AND (".$cond_data.")";
			}
		}
	}
	$arr_tag = [];
	
	$check_url = 0;
	$field = "*";
	if(!empty($project_id)) {
		// $cond .= " AND JSON_CONTAINS(project_stored, '".$project_id."', '$')";
		$purl .= ((empty($purl)) ? "?" : "&") . "project_id=".$project_id;		
	}
	if(!empty($campaign_id)) {
		$cond .= " AND `list_campaign_id` LIKE '%|".$campaign_id."|%'";
		$cnd .= " AND `list_campaign_id` LIKE '%|".$campaign_id."|%'";
		$purl .= ((empty($purl)) ? "?" : "&") . "campaign_id=".$campaign_id;		
	}
	if(!empty($blocks_ids)) {	
		$cond .= " AND (";
		foreach($blocks_ids as $key => $block_id){
			$cond .= (($key > 0) ? " OR " : "") . "JSON_CONTAINS(`block_stored`, '".$block_id."', '$')";
		}
		$cond .= " )";
		$purl .= ((empty($purl)) ? "?" : "&") . "blocks_ids=".implode(",",$blocks_ids);
		$check_url = 1;
	}
	if(!empty($building_ids)) {	
		$cond .= " AND (";
		foreach($building_ids as $key => $building_id){
			$cond .= (($key > 0) ? " OR " : "") . "JSON_CONTAINS(`building_stored`, '".$building_id."', '$')";
		}
		$cond .= " )";
		$purl .= ((empty($purl)) ? "?" : "&") . "`building_ids`=".implode(",",$building_ids);
		$check_url = 1;
	}
	if(!empty($keyword)) {
		$cond .= " AND ( `full_name` LIKE '%{$keyword}%' OR LOWER(`full_name`) LIKE '%".strtolower($keyword)."%' OR `phone` = '{$keyword}' OR `ms_codes` LIKE '%|{$keyword}|%' OR JSON_CONTAINS(`email_stored`, '\"".$keyword."\"', '$'))";
		$cnd .= " AND ( `full_name` LIKE '%{$keyword}%' OR LOWER(`full_name`) LIKE '%".strtolower($keyword)."%' OR `phone` = '{$keyword}' OR `ms_codes` LIKE '%|{$keyword}|%' OR JSON_CONTAINS(`email_stored`, '\"".$keyword."\"', '$'))";
		$purl .= ((empty($purl)) ? "?" : "&") . "keyword=".$keyword;
		$check_url = 1;
	}
	
	if(!empty($birthday)) {	
		$cond .= " AND FROM_UNIXTIME(`birthday`,'%Y-%m-%d')='".$birthday."'";
		$cnd .= " AND FROM_UNIXTIME(`birthday`,'%Y-%m-%d')='".$birthday."'";
		$purl .= ((empty($purl)) ? "?" : "&") . "`birthday`=".$birthday;
		$check_url = 1;
	}
	if($date_call != "") {
		$lst_date_call = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.`id`
		FROM `{$clsDataCentral->tbl}`,
		JSON_TABLE(
		  JSON_EXTRACT(`{$clsDataCentral->tbl}`.action_logs, '$.call_log'),
		  '$.*' COLUMNS (
			profile_id INT PATH '$.profile_id',
			reg_date INT PATH '$.reg_date'
		  )
		) AS jt
		WHERE jt.profile_id = '{$profile_id}'
		  AND FROM_UNIXTIME(jt.reg_date, '%Y-%m-%d') = '".$date_call."'");
		$arr_date_call = [];
		if(!empty($lst_date_call)) {
			foreach ($lst_date_call as $key => $val) {
				$arr_date_call[] = $val['id'];
			}	
		}
		
		$cond .= " AND `{$clsDataCentral->pkey}` IN (".implode(',',$arr_date_call).")";
		$purl .= ((empty($purl)) ? "?" : "&") . "date_call=".$date_call;
		$check_url = 1;
	}
	if($status_id != "") {	
		if($_tp == "_campaign") {
			if($status_id == _DATA_STATUS_DONTCARE_ID) {
				$cond .= " AND (JSON_EXTRACT(`action_logs`, '$.status_campaign.{$campaign_id}')='".$status_id."' OR JSON_EXTRACT(`action_logs`, '$.status_campaign.{$campaign_id}') IS NULL)";
			}else{
				$cond .= " AND JSON_EXTRACT(`action_logs`, '$.status_campaign.{$campaign_id}')='".$status_id."'";
			}
			
		}else{
			$lst_has_call = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.`id`
			FROM `{$clsDataCentral->tbl}`,
			JSON_TABLE(
			  JSON_EXTRACT(`{$clsDataCentral->tbl}`.action_logs, '$.call_log'),
			  '$.*' COLUMNS (
				profile_id INT PATH '$.profile_id',
				reg_date INT PATH '$.reg_date'
			  )
			) AS jt
			WHERE jt.profile_id = '{$profile_id}' ");
	//		$clsISO->print_pre($lst_has_call);die;

			$arr_has_call = [];
			if(!empty($lst_has_call)) {
				foreach ($lst_has_call as $key => $val) {
					$arr_has_call[] = $val['id'];
				}	
			}
			if($status_id == "1") {
				$cond .= " AND `{$clsDataCentral->pkey}` IN (".implode(',',$arr_has_call).")";
			}else {
				$cond .= " AND `{$clsDataCentral->pkey}` NOT IN (".implode(',',$arr_has_call).")";
			}
		}
		
		
		$purl .= ((empty($purl)) ? "?" : "&") . "status_id=".$status_id;
		$check_url = 1;
	}
	$arr_tags_id = [];
	if(!empty($tags)) {	
		$cond .= " AND (";
		foreach ($tags as $key => $tag_id) {
			$cond .= (($key > 0) ? " OR " : "") . " (`tags` LIKE '%|".$tag_id."|%')";
			$arr_tags_id[] = $tag_id;
		}	
		$cond .= ")";
		$purl .= ((empty($purl)) ? "?" : "&") . "tags=".implode(",",$tags);
		$check_url = 1;
	}
	$assign_list['arr_tags_id'] = $arr_tags_id;
	$current_page = (int)Input::post('page',1);
	$per_page = Input::post('per_page',50);
//	$clsDataCentral->setDeBug(1);
	$total_record = $clsDataCentral->countItem($cond);
//	echo $total_record;die;
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	$index = $offset + 1;
	$smarty->assign("index",$index);
	//=====================
	$list_data_fields = $clsConfiguration->getValue('field_data_center');
	$list_data_fields = $clsISO->to_array_json($list_data_fields);
	$def_field = array();
	foreach ($list_data_fields as $key => $val) {
		if(!empty($val['is_default'])) {
			$def_field[] = $key;
		}
	}
	
	$now = time();
	$start_time_today = strtotime(date('d-m-Y'));
	$end_time_today = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$start_time_7days = strtotime('-7 days', $now);
	
	#	
	if($_tp == "_campaign"){ 			
		$arr_status_cached = $clsProperty->getArraySearchByKey("DATA_CENTRAL_STATUS");
	   	$lstBilling = $clsDataCentral->getAll($cnd);
//		$clsISO->print_pre($lstBilling);die;
		$arr_total_billing_status = [];
		foreach ($lstBilling as $key => $val) {
			$action_logs = $clsISO->to_array_json($val["action_logs"]);
			$status_campaign = !empty($action_logs["status_campaign"]) ? $action_logs["status_campaign"] : [];
			$status_id = !empty($status_campaign[$campaign_id]) ? $status_campaign[$campaign_id] : _DATA_STATUS_DONTCARE_ID;
			if(!isset($arr_total_billing_status[$status_id])) {
				$arr_total_billing_status[$status_id] = 1;
			}else{
				$arr_total_billing_status[$status_id] += 1;
			}
		}
//		$clsISO->print_pre($arr_total_billing_status);die;
		$ii = 1;
		foreach($arr_status_cached as $key => $val){
			$property_id = $val[$clsProperty->pkey];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$total_customers = !empty($arr_total_billing_status[$property_id]) ? $arr_total_billing_status[$property_id] : 0;
			/*$html_briefs.= '<div onClick="$Core.data_central.set_status(this,event)" status_id="'.$property_id.'" 
				class="brief-item cursor-pointer" style="background:'.$val['bgcolor'].'">
				<span class="label">'.$val['title'].'</span>
				<span class="value">'.$total_customers.' </span>
			</div>';*/
			$html_briefs.= '<div class="brief-item cursor-pointer a'.($ii).'a h-px-100" style="background-color:'.$val['bgcolor'].'" onClick="$Core.data_central.set_status(this,event)" status_id="'.$property_id.'">
				<p class="text-fs-16 mb-0 d-inline-block pb-2 border-bottom mb-2">'.$val['title'].'</p>
				<h3 class="text-fs-24 xs:text-fs-16 mb-0 text-white">'.$total_customers.'</h3>
			</div>';
			++$ii;
		}
		$arr_task_type = $arr_call_type = []; $total_overdue_followups = 0;
		$list_followups = $clsFollowUp->getAll("`admin_id`='{$profile_id}' AND `status_id`<>'"._FOLLOWUP_STATUS_DONE_ID."' AND `followup_type`='_data_central' AND `campaign_id`='".$campaign_id."' 
			AND FROM_UNIXTIME(`date_id`,'%m/%Y')='".date('m/Y')."'", "`date_id`,`type_id`");
		$start_time_tomorrow = strtotime(date('d-m-Y', strtotime('+1 day')));
		$end_time_tomorrow = strtotime(sprintf('%s 23:59:59', date('d-m-Y', strtotime('+1 day'))));
		// $clsISO->print_pre($list_followups); die();
		if(!empty($list_followups)){
			foreach($list_followups as $key => $val){
				$date_id = $val['date_id'];
				$type_id = (int) $val['type_id'];
				if($type_id == _FOLLOWUP_TASK_ID){
					if($date_id < $now){
						$total_overdue_followups += 1;
					}
					$today_key = sprintf('today_%s', $type_id);
					$tomorrow_key = sprintf('tomorrow_%s', $type_id);
					if($date_id >= $start_time_today && $date_id <= $end_time_today){
						if(isset($arr_type[$today_key])){
							$arr_task_type[$today_key]['total'] += 1;
						} else {
							$arr_task_type[$today_key] = array(
								'time' => 'today',
								'type_id' => $type_id,
								'total' => 1
							);
						}
					} else if($date_id >= $start_time_tomorrow && $date_id <= $end_time_tomorrow){
						if(isset($arr_type[$tomorrow_key])){
							$arr_task_type[$tomorrow_key]['total'] += 1;
						} else {
							$arr_task_type[$tomorrow_key] = array(
								'time' => 'tomorrow',
								'type_id' => $type_id,
								'total' => 1
							);
						}
					}
				} else if($type_id == _FOLLOWUP_CALL_ID) {
					if($date_id >= $start_time_today && $date_id <= $end_time_today){
						if(isset($arr_call_type[$today_key])){
							$arr_call_type[$today_key]['total'] += 1;
						} else {
							$arr_call_type[$today_key] = array(
								'time' => 'today',
								'type_id' => $type_id,
								'total' => 1
							);
						}
					}
				}
			}
//			$clsISO->print_pre($arr_task_type);die;
			if(!empty($arr_task_type)){
				foreach($arr_task_type as $key => $val){
					$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-primary p-2 rounded-2">
						<div class="w-px-30 p-2"><i class="fa fa-clock-o text-fs-20"></i></div>
						<div class="d-flex flex-column">
							<h3 class="mb-1 text-fs-14"><strong class="fs-6">'.$val['total'].'</strong> cuộc gặp vào '.($val['time'] == 'today' ? 'hôm nay' : ' ngày mai').'</h3>
							<small>Cần chuẩn bị nội dung & tài liệu đầy đủ</small>
						</div>
					</div>';
				}
			}
			if(!empty($arr_call_type)){
				foreach($arr_call_type as $key => $val){
					$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-purple p-2 rounded-2">
						<div class="w-px-30 p-2"><i class="fa fa-phone-square text-fs-20"></i></div>
						<div class="d-flex flex-column">
							<h3 class="mb-1 text-fs-14"><strong class="fs-6">'.$val['total'].'</strong> cuộc gọi gọi điện vào '.($val['time'] == 'today' ? 'hôm nay' : ' ngày mai').'</h3>
							<small>Cần chuẩn bị nội dung & tài liệu đầy đủ</small>
						</div>
					</div>';
				}
			}
			if($total_overdue_followups > 0){
				$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-warning p-2 rounded-2">
					<div class="w-px-30 p-2"><i class="fa fa-exclamation-triangle text-fs-20"></i></div>
					<div class="d-flex flex-column">
						<h3 class="mb-1 text-fs-14">'.$total_overdue_followups.' follow-ups đã  bị quá hạn</h3>
						<small>Hãy lên lịch liên hệ để giữ nhịp chăm sóc</small>
					</div>
				</div>';
			}
		} else {
			$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-warning p-2 rounded-2">
				<div class="w-px-30 p-2"><i class="fa fa-exclamation-triangle text-fs-20"></i></div>
				<div class="d-flex flex-column">
					<h3 class="mb-1 text-fs-14">30 ngày bạn chưa tương tác với khách hàng</h3>
					<small>Hãy lên lịch liên hệ để giữ nhịp chăm sóc</small>
				</div>
			</div>';
		}
		// $clsISO->print_pre($list_followups); die();
	}
	
	###
	$more_information = $oneProfile['more_information'];
	$list_setting_field = (!empty($more_information["fieldDataCentral"])) ? $more_information["fieldDataCentral"] : $def_field;
	$arr_columns = array();
	foreach($list_setting_field as $id) {
		$arr_columns[$list_data_fields[$id]['code']] = $list_data_fields[$id];
	} 
	// $clsISO->print_pre($arr_columns);die;
	$smarty->assign("arr_columns",$arr_columns);
	$arr_field = array_keys($arr_columns);
	$lst_data = [];
	$field = "{$clsDataCentral->pkey},`full_name`,`phone`,`birthday`,`more_information`,`ms_codes`,`tags`,`action_logs`";
	
	// $dbconn->debug = true;
	$lstDataCenter = $clsDataCentral->getAll($cond." ORDER BY `reg_date` DESC".$limitCond, $field);
//	 $clsISO->print_pre($lstDataCenter);die;
	$total = 0;
	if(!empty($lstDataCenter)){
		$arr_tag_cached = array();
		$tmp = $clsTag->getAll("`tag_type`='_data_central'", "{$clsTag->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_tag_cached[$val[$clsTag->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		$total_cus_7days = $total_cus_new = 0;
		foreach ($lstDataCenter as $key => $val) {
			$tags = $val['tags'];
			$action_logs = $val['action_logs'];
			$more_information = $val['more_information'];
			$action_logs = $clsISO->to_array_json($action_logs);
			$more_information = $clsISO->to_array_json($more_information);
			$lstDataCenter[$key]['more_information'] = $more_information;
			
			#
			$reg_date = $val['reg_date'];
			$status_campaign = !empty($action_logs["status_campaign"]) ? $action_logs["status_campaign"] : [] ;
//			$clsISO->print_pre($status_campaign);die;
			$status_id = !empty($status_campaign[$campaign_id]) ? (int) $status_campaign[$campaign_id] : _DATA_STATUS_DONTCARE_ID;
			if($status_id == _DATA_STATUS_DONTCARE_ID){
				$total_cus_new += 1;
			}
			if($status_id != _CRM_STATUS_CHOT_ID && ($reg_date > $start_time_7days && $reg_date < $now)){
				$total_cus_7days += 1;
			}			
			
			$phone = $val['phone'];
			$lstDataCenter[$key]['phone2'] = array_filter($more_information['phone'], function($item) use ($phone) {
				return $item != $phone;
			});
			$lstDataCenter[$key]['birthday'] = !empty($val['birthday']) ? date("d/m/Y",$val['birthday']) : "--";
			$lstDataCenter[$key]['email'] = !empty($more_information['email']) ? $more_information['email'][0] : "";
			$lstDataCenter[$key]['more_email'] = (count($more_information['email']) > 1) ? (count($more_information['email']) - 1) : 0;
			
			$lstDataCenter[$key]['address'] = $more_information['address'];		
			$ms_codes = !empty($val["ms_codes"]) ? $clsISO->getArrayByTextSlash($val["ms_codes"]) : array();
			$ms_codes = $clsDataCentral->shortMsCode($ms_codes,$blocks_ids,$building_ids);
			$lstDataCenter[$key]['ms_codes'] =  $ms_codes;
			$lstDataCenter[$key]['more_ms_code'] = (count($ms_codes) > 2) ? (count($ms_codes) - 2) : 0;
			
			
			$lstTag = array();
			$arr_tags = $clsISO->getArrayByTextSlash($tags, ",", []);
			// $tag_name = $clsTag->getTagArrs($arr_tags, $arr_tag_cached);
			if(!empty($arr_tags)){
				foreach($arr_tags as $tag_id){
					$lstTag[] = array(
						'tag_id' => $tag_id,
						'title' => $arr_tag_cached[$tag_id]
					);
				}
			}
			$lstDataCenter[$key]['lstTag'] = $lstTag;
			$lstDataCenter[$key]['more_tag'] = (count($lstTag) > 4) ? (count($lstTag) - 4) : 0;
			
			$log_call = $action_logs['call_log'];
			$total_call = $call_last_time = $check_call_date = $is_call = 0;
			foreach ($log_call as $k => $v){
				if($v['profile_id'] == $profile_id) {
					++$total_call;
					$is_call = 1;
					$call_last_time = $v['reg_date'];
					if($date_call == date("Y-m-d",$v['reg_date'])) {
						$check_call_date = 1;
					}
				}
			}
			
			$lstDataCenter[$key]['total_call'] = $total_call;
			$lstDataCenter[$key]['call_last_time'] = $call_last_time;
			$potential_CRM_log = $action_logs['potential_CRM_log'];
			$lstDataCenter[$key]["has_CRM"] = (!empty($potential_CRM_log) && !empty($potential_CRM_log[$profile_id])) ? 1 : 0;
			$view_phone_log = $action_logs['view_phone'];
			$lstDataCenter[$key]["view_phone"] = (!empty($view_phone_log) && !empty($view_phone_log[$profile_id])) ? 1 : 0;
			$call_success_log = $action_logs['call_success'];
			$lstDataCenter[$key]["call_success"] = (!empty($call_success_log) && !empty($call_success_log[$profile_id])) ? 1 : 0;
			unset($more_information,$action_logs,$log_call);
		}
		
		if($total_cus_new > 0){
			$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-label-success p-2 rounded-2">
				<div class="w-px-30 p-2"><i class="fa fa-bell-o text-fs-20"></i></div>
				<div class="d-flex flex-column">
					<h3 class="mb-1 text-fs-14">'.$total_cus_new.' data tiềm năng cần xử lý</h3>
					<small>Ưu tiên liên hệ sớm để nắm bắt cơ hội.</small>
				</div>
			</div>';
		}
		/*if($total_cus_7days == 0){
			$html_warning.= '<div class="d-flex align-items-center gap-2 alert-message bg-lighter p-2 rounded-2">
				<div class="w-px-30 p-2"><i class="fa fa-info-circle text-fs-20"></i></div>
				<div class="d-flex flex-column">
					<h3 class="mb-1 text-fs-14">7 ngày gần đây bạn chưa có khách hàng mới</h3>
					<small>Cần chủ động tiếp cận để tạo cơ hội bán hàng.</small>
				</div>
			</div>';
		}*/
		if(empty($html_warning)){
			$html_warning .= '<div class="border text-center rounded-2 p-3 border-dashed">
				Bạn chưa có thông báo nào !
			</div>'; 
		}
	}
	
	$smarty->assign("arr_staff",$arr_staff);
	$smarty->assign("lstDataCenter",$lstDataCenter);
	// Return
	$link = $clsISO->getLink("data_central");
	if($_tp == "_campaign") {
		$link = $clsISO->getLink("campaign_data");
	}
	$smarty->assign("link",$link);
	$html = $core->build("_ajax.load_data_central.tpl");
	echo json_encode(array(
		'html' => $html,
		'html_briefs' => $html_briefs,
		'html_warning' => $html_warning,
		'cond' => $cond,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'url'	=>	$link.(!empty($check_url) ? $purl : "" )
	)); die();
}
function default_open_import(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod,$_LANG_ID,$act,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$uid = $clsISO->getUniqid();
	$smarty->assign("uid",$uid);
	
	// Return
	$html = $core->build("_ajax.open_import.tpl");
	echo json_encode([
		"uid"	=>	$uid,
		"html"	=>	$html
	]);die;
}
function default_get_sheets(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	/** Init Client */
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	$spreadsheetId = Input::post("spreadsheetId");
	#
	$result = false; $msg = "_error";
	$html = '<option value="">Chọn sheet</option>';
	if(!empty($spreadsheetId)) {
		try {
			$result = true;
			$response = $service->spreadsheets->get($spreadsheetId);
			$sheets = $response->getSheets();
			if(!empty($sheets)){
				foreach($sheets as $sheet) {
					$title = $sheet->getProperties()->getTitle();
					$html.= "<option value='".$title."'>".$title."</option>";
				}
			}
		} catch (Google_Service_Exception $e) {
			$msg = "Lỗi spreadsheetId";
		} catch (Exception $e) {
			$msg = "Lỗi spreadsheetId";
		}
	}
	// Return
	echo json_encode([
		"result" =>	$result,
		"msg"	=>	$msg,
		"html"	=>	$html
	]); die();
}
function default_search_tag(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsTag = new Tag();
	$clsProperty = new Property();
	
	$results = array();
	$list_tags = $clsTag->getAll("`is_trash`=0 and `tag_type`='_data_central'", "{$clsTag->pkey},`title`,`slug`");
	// $clsISO->print_pre($list_tags); die();
	if(!empty($list_tags)){
		foreach($list_tags as $key => $val){
			$title = $val['title'];
			$results[] = array(
				'id' => $val[$clsTag->pkey],
				'text' => $title
			);
		}
		unset($list_tags);
	}
	// Return
	echo json_encode($results); die();
}
function getInfoStock($ms_code, $array) {
    foreach ($array as $key => $value) {
        if (strpos($ms_code, $key) === 0) {
            return $key;
        }
    }
    return null;
}
function getLstTagId($arr_tags) {
	global $core;
	$clsTag = new Tag();
    $arr_tag_id = [];
	foreach ($arr_tags as $tag_name) {
		$slug = $core->replaceSpace($tag_name);
		$oneTag = $clsTag->getByCond("`is_trash`=0 AND `tag_type`='_data_central' AND `slug`='{$slug}'");
		if(!empty($oneTag)) {
			$arr_tag_id[] = $oneTag['tag_id'];
		}else{
			$tag_id = $clsTag->getMaxID();
			if($clsTag->insert([
				"tag_id"	=>	$tag_id,
				"tag_type"	=>	"_data_central",
				"user_id"	=>	0,
				"title"		=>	$tag_name,
				"slug"		=>	$slug,
				"is_trash"	=>	0,
			])){
				$arr_tag_id[] = $tag_id;
			}
		}
	}
	return $arr_tag_id;
}
function default_open_config(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$core,$clsModule,$clsConfiguration,$clsISO,$dbconn;
	global $profile_id, $oneProfile;
	$clsDataCentral = new DataCentral();
	#
	$uid = $clsISO->getUniqid();
	$sheet_name = Input::post('sheet_name');
	$spreadsheetId = Input::post('spreadsheetId');
	if(!empty($spreadsheetId) && !empty($sheet_name)){
		#- Require library		
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
		// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
		// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
		// get all the rows of a sheet
		$range = $sheet_name; // here we use the name of the Sheet to get all the rows
		$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$range."'"], array(
			'valueRenderOption' => 'FORMATTED_VALUE'
		));
		$tblData = $response->getValues();
		// $clsISO->print_pre($response); die();
		$more_information = $oneProfile['more_information'];
		$select_default = $core->get_field($more_information, "data_central_column_config", []);
		$data_select = $clsDataCentral->getTableField();
		$highestColumnIndex = 15;
		$widthColumn = (100 / $highestColumnIndex);	
		$smarty->assign("data_select",$data_select);
		$smarty->assign("select_default",$select_default);
		$smarty->assign("widthColumn",$widthColumn);
		$smarty->assign("highestColumnIndex",$highestColumnIndex);
		$smarty->assign("tblData", $tblData);
	}
	// Return
	$html = $core->build("_ajax.config_column.tpl");
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_config(){
	global $smarty,$core,$dbconn,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$uid = Input::post("uid");
	$columns = Input::post("columns", array());
	// $clsISO->print_pre($columns); die();
	if(!empty($columns)) {
		$error_field = 0; $arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[$key] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
		if($error_field > 0){
			$res = array(
				'result' =>	false,
				'msg'	 =>	"Các cột dữ liệu không được trùng nhau"
			);
		} else {
			$more_information = $oneProfile['more_information'];
			$more_information['data_central_column_config'] = $arr_fields;
			if($clsProfile->updateOne($profile_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE) 
			))){
				$res = array(
					"result" =>	true,
					'msg' => "Cài đặt thành công",
				);
			}
		}
	}else{
		$res = array(
			"result" =>	false,
			'msg' => "Có lỗi xảy ra. Xin vui lòng thử lại!",
		);
	}
	// Return	
	echo json_encode($res); die();
}
function explode_multi($string){
    if ($string === null || $string === '') {
        return [];
    }
    return array_values(
        array_filter(
            array_map(
                'trim',
                preg_split('/[;&\/]/', $string)
            ),
            'strlen'
        )
    );
}
function default_import_data(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$core,$clsModule,$clsConfiguration,$clsISO,$dbconn;
	global $profile_id, $oneProfile;
	#- Bỏ limit time
	set_time_limit(0);
	ini_set('max_execution_time', 300);
	ini_set('memory_limit', '7048M');
	#
	$clsTag = new Tag();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsDataCentral = new DataCentral();
	#
	$uid = $clsISO->getUniqid();
	$stock_type = (int) Input::post('stock_type', 0);
	$project_id = (int) Input::post('project_id', 0);
	$spreadsheetId = Input::post('spreadsheetId', "");
	$range = Input::post('sheet_name', "DATA");
	$start_row = (int) Input::post('start_row', 1);
	$is_regex_stock = (int) Input::post('is_regex_stock', 0);
	$regex_stock = Input::post('regex_stock', "");
	$is_crawl_stock = (int) Input::post('is_crawl_stock', 0);
	$tags = Input::post('tag', "");
	#
	$arr_tag_id = array();
	if(!empty($tags)){
		$arr_tags = @explode(',', trim($tags));
		$arr_tag_id = $clsTag->getTagsId($arr_tags);
	}
	$arr_blocks = [];
	if($project_id > 0){
		$tmp = $clsProperty->getAll("`is_trash`='0' and `for_id`='{$project_id}'", "{$clsProperty->pkey},`title`");
		foreach($tmp as $key => $val) {
			$block_id = $val[$clsProperty->pkey];
			$arr_blocks[$block_id] = $val['title'];
			$list_childs = $clsProperty->getAll("`is_trash`='0' and `for_id`='{$block_id}'", "{$clsProperty->pkey},`title`");
			if(!empty($list_childs)){
				foreach($list_childs as $okey => $oval) {
					$arr_blocks[$oval[$clsProperty->pkey]] = $oval['title'];
				}
				unset($list_childs);
			}
		}
	}
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');				
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	$result = true;
	try {
		$response = $service->spreadsheets_values->get($spreadsheetId, ["'".$range."'"], array(
			'valueRenderOption' => 'FORMATTED_VALUE'
		));
		$tblData = $response->getValues();
		if(!empty($tblData)){
			$arr_data_customer = array();
			$more_information = $oneProfile['more_information'];
			$columns = $core->get_field($more_information, "data_central_column_config", []);
			$total_records = count($tblData);
			for($i=$start_row; $i<$total_records; $i++){
				$arr_customer = array();
				foreach($columns as $i_col => $p_field){
					$arr_customer[$p_field] = trim($tblData[$i][$i_col]);
				}
				$arr_data_customer[] = $arr_customer;
			}		
			$total_update = 0;
			foreach ($arr_data_customer as $key => $_oCus) {
				$full_name = isset($_oCus['full_name']) ? $_oCus['full_name'] : "";
				$phone = isset($_oCus['phone']) ? $_oCus['phone'] : "";
				$phone2 = isset($_oCus['phone2']) ? $_oCus['phone2'] : "";
				$email = isset($_oCus['email']) ? $_oCus['email'] : "";
				$address = isset($_oCus['address']) ? $_oCus['address'] : "";
				$birthday = isset($_oCus['birthday']) ? $_oCus['birthday'] : "";
				$ms_code = isset($_oCus['ms_code']) ? $_oCus['ms_code'] : "";
				$data_upd = [];
				if(!empty($full_name) && !empty($phone)){
					$arr_phone_input = $arr_email_input = array();
					if(!empty($phone) && $phone != '.'){
						if($clsISO->checkContainer($phone, ";", "")){
							$tmp = @explode(";", $phone);
							$phone = $clsDataCentral->formatPhone($tmp[0]);
							if(count($tmp) > 1){
								for($i=1; $i<count($tmp); $i++){
									$arr_phone_input[] = $clsDataCentral->formatPhone($tmp[$i]);
								}
							}
						} else if($clsISO->checkContainer($phone, "/", "")){
							$tmp = @explode("/", $phone);
							$phone = $clsDataCentral->formatPhone($tmp[0]);
							if(count($tmp) > 1){
								for($i=1; $i<count($tmp); $i++){
									$arr_phone_input[] = $clsDataCentral->formatPhone($tmp[$i]);
								}
							}
						}  else if($clsISO->checkContainer($phone, ",", "")){
							$tmp = @explode(",", $phone);
							$phone = $clsDataCentral->formatPhone($tmp[0]);
							if(count($tmp) > 1){
								for($i=1; $i<count($tmp); $i++){
									$arr_phone_input[] = $clsDataCentral->formatPhone($tmp[$i]);
								}
							}
						} else if (preg_match("/\r\n|\r|\n/", $phone)) {
							$tmp = preg_split('/\r\n|\r|\n/', $phone);
							$phone = $clsDataCentral->formatPhone($tmp[0]);
							if(count($tmp) > 1){
								for($i=1; $i<count($tmp); $i++){
									$arr_phone_input[] = $clsDataCentral->formatPhone($tmp[$i]);
								}
							}
						} else {
							$phone = $clsDataCentral->formatPhone($phone);
							$arr_phone_input[] = $phone;
						}
					}
					if(!empty($phone2) && $phone2 != '.'){
						if($clsISO->checkContainer($phone2, ";", "")){
							$tmp = @explode(";", $phone2);
							$phone2 = $clsDataCentral->formatPhone($tmp[1]);
							if(count($tmp) > 1){
								for($i=1; $i<count($tmp); $i++){
									$phone_more = $clsDataCentral->formatPhone($tmp[$i]);
									if(!in_array($phone_more, $arr_phone_input)){
										$arr_phone_input[] = $clsDataCentral->formatPhone($phone_more);
									}
								}
							}
						} else if($clsISO->checkContainer($phone2, "/", "")){
							$tmp = @explode("/", $phone2);
							$phone2 = $clsDataCentral->formatPhone($tmp[0]);
							if(count($tmp) > 1){
								for($i=1; $i<count($tmp); $i++){
									$phone_more = $clsDataCentral->formatPhone($tmp[$i]);
									if(!in_array($phone_more, $arr_phone_input)){
										$arr_phone_input[] = $phone_more;
									}
								}
							}
						} else {
							$phone2 = $clsDataCentral->formatPhone($phone2);
							if(!in_array($phone2, $arr_phone_input)){
								$arr_phone_input[] = $phone2;
							}
						}
					}
					if(!empty($email) && $email != "k có"){
						$email = preg_replace('/\s+/', '', $email);
						if($clsISO->checkContainer($phone, ";", "")){
							$arr_email_input = explode(";", $email);
						} else {
							$arr_email_input[] = $email;
						}
					}
					if(!empty($birthday)){
						$birthday = $clsISO->convertTextToTime($birthday);
					}
					$block_id = $building_id = 0;
					if(!empty($ms_code) && $is_regex_stock == 1){
						preg_match('/'.$regex_stock.'/', $ms_code, $matches);
						$ms_code = $matches[0] ?? '';	
					}
					if(!empty($ms_code) && (int) $is_crawl_stock == 1){
						$project_id = (int) $project_id;
						$ms_code = preg_replace('/\s+/', '', $ms_code);
						$ms_code = $clsDataCentral->format_stock_code($ms_code);
						$s_field = "{$clsStock->pkey},`building_id`,`block_id`";
						$oneStock = $clsStock->getByCond("`project_id`='{$project_id}' AND `ms_code`='{$ms_code}'", $s_field);
						if(!empty($oneStock)){
							$tag_more = [];
							$block_id = $oneStock['block_id'];
							$building_id = $oneStock['building_id'];
							$block_name = ($block_id > 0 && array_key_exists($block_id, $arr_blocks)) ? $arr_blocks[$block_id] : "";
							$building_name = ($building_id > 0 && array_key_exists($building_id, $arr_blocks)) ? $arr_blocks[$building_id] : "";
							if(!empty($block_name)) $tag_more[] = $block_name;
							if(!empty($building_name)) $tag_more[] = $building_name;
							if(!empty($tag_more)){
								$arr_tag_more_id = $clsTag->getTagsId($tag_more);
								$arr_tag_id = array_merge($arr_tag_id, $arr_tag_more_id);
							}
						}
					}
					// $clsISO->print_pre($arr_tag_id); die();
					$oneData = $clsDataCentral->getByCond("`phone`='{$phone}'");
					if(!empty($oneData)) {
						$tags = $oneData['tags'];
						$ms_codes = $oneData['ms_codes'];
						$more_information = $oneData['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$arr_phone = $core->get_field($more_information, "phone", []);
						$arr_email = $core->get_field($more_information, "email", []);
						$arr_project = $core->get_field($more_information, "project_ids", []);
						$arr_block = $core->get_field($more_information, "block_ids", []);
						$arr_building = $core->get_field($more_information, "building_ids", []);
						$arr_phone = !empty($arr_phone) ? array_unique(array_merge($arr_phone, $arr_phone_input)) : $arr_phone_input;
						$arr_phone = !empty($arr_email) ? array_unique(array_merge($arr_email, $arr_email_input)) : $arr_email_input;
						$more_information["phone"] = array_unique($arr_phone);					
						$more_information["email"] = array_unique($arr_email);
						$more_information["address"] = $address;
						if($project_id > 0 && !in_array($project_id, $arr_project))
							$arr_project[] = $project_id;
						if($block_id > 0 && !in_array($block_id, $arr_block))
							$arr_block[] = $block_id;
						if($building_id > 0 && !in_array($building_id, $arr_building))
							$arr_building[] = $building_id;
							
						$more_information["project_ids"] = $arr_project;
						$more_information["block_ids"] = $arr_block;
						$more_information["building_ids"] = $arr_building;
						$arr_ms_code = $clsISO->getArrayByTextSlash($ms_codes, ",", []);
						if(!empty($ms_code) && !in_array($ms_code, $arr_ms_code)) $arr_ms_code[] = $ms_code;
						
						$arr_tags = $clsISO->getArrayByTextSlash($tags, ",", []);
						if(!empty($arr_tags) && !empty($arr_tag_id)){
							$arr_tags = array_unique(array_merge($arr_tags, $arr_tag_id));
						} else if(empty($arr_tags) && !empty($arr_tag_id)){
							$arr_tags = $arr_tag_id;
						}
						$data_upd = [
							"ms_codes"	=>	$clsISO->makeSlashListFromArrayRoot($arr_ms_code),
							"tags"		=>	$clsISO->makeSlashListFromArrayRoot($arr_tags),
							"more_information"	=>	json_encode($more_information),
							"upd_date"	=>	time(),
							"phone"		=>	$phone
						];
						if(!empty($birthday)) $data_upd['birthday'] = $birthday;
						if(!empty($full_name)) $data_upd['full_name'] = $full_name;
						if($clsDataCentral->updateOne($oneData[$clsDataCentral->pkey], $data_upd)) {
							++$total_update;
						}
					}else{
						$id = $clsDataCentral->getMaxId();
						$more = $more_information = array();
						$more_information["phone"] = $arr_phone_input;
						$more_information["email"] = $arr_email_input;
						$more_information["address"] = $address;
						if($project_id > 0) $more_information['project_ids'][] = $project_id;
						if($block_id > 0) $more_information['block_ids'][] = $block_id;
						if($building_id > 0) $more_information['building_ids'][] = $building_id;
						if(!empty($arr_tag_id)) $more['tags'] = $clsISO->makeSlashListFromArrayRoot($arr_tag_id);
						if(!empty($ms_code)) $more['ms_codes'] = "|{$ms_code}|";
						if($clsDataCentral->insert(array_merge($more, array(
							"id"	=>	$id,
							"full_name"	=>	$full_name,
							"birthday"	=>	$birthday,
							"phone"		=>	$phone,
							"more_information"	=>	json_encode($more_information),
							"reg_date"	=>	time(),
							"upd_date"	=>	time(),
						)))){
							++$total_update;
						}
					}
				}
			}
		}
	} catch (Google_Service_Exception $e) {
		$result = false;
	} catch (Exception $e) {
		$result = false;
	}
	// Return
	echo json_encode(array(
		'result' =>	$result,
		'total_update' => $total_update
	)); die();
}

function default_log(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsDataCentral = new DataCentral();
	
	$id = (int)Input::post("id",0);
	$type = Input::post("type","call_log");
	$oneItem = $clsDataCentral->getOne($id);
	$res = ["result"	=>	false];
	if(!empty($oneItem)) {
		$action_logs = $clsISO->to_array_json($oneItem["action_logs"]);
		$log_type = $action_logs[$type];
		$key_log = ($type == "view_phone" || $type == "call_success") ? $profile_id : $clsISO->getUniqid();
		$log_type[$key_log] = [
			"profile_id"	=>	$profile_id,
			"reg_date"		=>	time()
		];
		$action_logs[$type] = $log_type;
		if($clsDataCentral->updateOne($id,[
			"action_logs"	=>	json_encode($action_logs)
		])){
			$res = ["result"	=>	true];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_log_call_success(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsDataCentral = new DataCentral();
	
	$id = (int)Input::post("id",0);
	$type = Input::post("type","_OPEN");
	if($type == "_OPEN") {
		$smarty->assign("id",$id);
		$html = $core->build("_ajax.log_call_success.tpl");
		$res = [
			"result"	=>	true,
		   	"html"	=>	$html
		];		
	}else if($type == "_SAVE") {
		$notes = Input::post("notes","");
		$oneItem = $clsDataCentral->getOne($id);
		$res = ["result"	=>	false];
		if(!empty($oneItem)) {
			$action_logs = $clsISO->to_array_json($oneItem["action_logs"]);
			$log_type = $action_logs["call_success"];
			$key_log = ($type == "view_phone" || $type == "call_success") ? $profile_id : $clsISO->getUniqid();
			$log_type[$profile_id] = [
				"profile_id"	=>	$profile_id,
				"reg_date"		=>	time(),
				"notes"			=>	addslashes($notes)
			];
			$action_logs["call_success"] = $log_type;
			if($clsDataCentral->updateOne($id,[
				"action_logs"	=>	json_encode($action_logs)
			])){
				$res = ["result"	=>	true];
			}
		}
	}
	
	// Return
	echo json_encode($res); die();
}
function default_loadlog(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsDataCentral = new DataCentral();
	$clsProfile = new Profile();
	$id = (int)Input::post("id",0);
	$oneItem = $clsDataCentral->getOne($id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$action_logs = $clsISO->to_array_json($oneItem["action_logs"]);
	$log_call = $action_logs['call_log'];
	if(!$clsISO->checkPermissionGroup('DIRECTOR')){
		foreach ($log_call as $key => $val) {
			if ($val['profile_id'] != $profile_id) {
				unset($log_call[$key]);
			}
		}
	}
	$smarty->assign("clsDataCentral",$clsDataCentral);
	$smarty->assign("clsProfile",$clsProfile);
	$smarty->assign("log_call",$log_call);
	$uid = $clsISO->getUniqid();
	$smarty->assign("uid",$uid);
	$html = $core->build("_ajax.lstLog.tpl");	
	echo json_encode([
		"uid"	=>	$uid,
		"html"	=>	$html
	]);die;
}
function default_load_pop_tag(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsTag = new Tag();
	$clsProfile = new Profile();
    $clsDataCentral = new DataCentral();
	$clsProperty = new Property();
	###
	$html_tags = array();
	$uid = $clsISO->getUniqid();
	$holderG = Input::request('holderG', '_pop');
	$id = (int) Input::request('id',0);
	$list_tags_id = $clsDataCentral->getOneField('tags', $id);
	$list_tags_arrs = !empty($list_tags_id) 
		? $clsISO->getArrayByTextSlash($list_tags_id) : array();
	$lstTags = $clsTag->getAll("user_id='{$profile_id}' AND `tag_id` IN (".implode(',',$list_tags_arrs).")",$clsTag->pkey.",title");
	if(!empty($lstTags)){
		foreach($lstTags as $tag){
			$html_tags[] = $tag["title"];
		}
	}
	###
	$html = '<form method="post">
		<div class="form-group mb-2">
			<input class="tags" id="tags_'.$uid.'" name="tags" id="input_tags_'.time().'" maxlength="255" placeholder="Nhập tags" value="'.(!empty($html_tags) ? implode(',',$html_tags) : "").'" />
		</div>
		<hr class="my-3" />
		<div class="form-group">
			<input type="hidden" name="p_field" value="tags" />
			<button type="button" class="btn btn-outline-primary" onclick="$Core.data_central.update_tags(this,event);" 
			p_field="tags" uid="'.$uid.'" p_id="'.$id.'">'.$core->makeIcon('check', 'Cập nhật').'</button>
		</div>
	</form>';
	 // Return
	echo $html; die();
}

function default_load_tags(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsTag = new Tag();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$results = array();
	$keyword = Input::get('keyword');
	$cond = "`tag_type`='_data_central' and `user_id`='{$profile_id}'";
	if(!empty($keyword)){
		$cond.= " and (`slug` like '%".$core->replaceSpace($keyword)."%')";
	}
	$list_tags = $clsTag->getAll($cond, "{$clsTag->pkey},title");
	if(!empty($list_tags)){
		foreach($list_tags as $key => $val){
			$results[] = $val['title'];
		}
		unset($list_tags);
	}
	// Return
	echo json_encode($results, JSON_UNESCAPED_UNICODE);
	die();
}
function default_update_tags(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsTag = new Tag();
	$clsProfile = new Profile();
    $clsDataCentral = new DataCentral();
	$clsProperty = new Property();
	###
	$p_id = (int)Input::post("p_id",0);
	$oneItem = $clsDataCentral->getOne($p_id);
	$list_tags_id = !empty($oneItem["tags"]) ? $clsISO->getArrayByTextSlash($oneItem["tags"]) : array();
	$tags = Input::post("tags","");
	$lst_tag_selected = Input::post("lst_tag_selected",array());
	$res = ["result"	=>	false];
	if(!empty($tags)){
		$parts = @explode(',', $tags);
		if(!empty($parts)){
			foreach($parts as $tag){
				$tmp = $clsTag->getByCond("`user_id`='{$profile_id}' and `slug`='".$core->replaceSpace($tag)."'");
				if(!empty($tmp)){
					$list_tags_id[] = $tmp[$clsTag->pkey];
				} else {
					$tag_id = $clsTag->getMaxId();
					$clsTag->insert(array(
						$clsTag->pkey => $tag_id,
						'tag_type' => '_data_central',
						'user_id' => $profile_id,
						'title' => $tag,
						'slug' => $core->replaceSpace($tag)
					));
					$list_tags_id[] = $tag_id;
				}
			}
			$list_tags_id = array_values(array_unique($list_tags_id));
//			var_dump($clsISO->makeSlashListFromArray($list_tags_id));die;
			if($clsDataCentral->updateOne($p_id, array(
				'tags' => $clsISO->makeSlashListFromArray($list_tags_id)
			))){
				$lstTag = $clsTag->getAll("`tag_type`='_data_central' AND `user_id`='{$profile_id}' AND `tag_id` IN (".implode(',',$list_tags_id).")");
				$html_tag = "";
				foreach ($lstTag as $key => $tag) {
					$html_tag .= '<a href="'.$clsISO->getLink('data_central').'?tag='.$tag["tag_id"].'" class="btn btn-xs btn-default rounded-1">'.$tag["title"].'</a>';	
				}
				
				$html_option = $clsTag->getOption("_data_central",$lst_tag_selected);
				$res = [
					"result"	=>true,
					"html_tag"	=>	$html_tag,
					"html_option"	=>	$html_option,
				];
			}
		}
	}
	// Return
	echo json_encode($res, JSON_UNESCAPED_UNICODE);
	die();
}
function default_potential_CRM(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $oneProfile,$profile_id,$clsProfile;
	$clsDataCentral = new DataCentral();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$id = (int) Input::post('id', 0);
	$oneItem = $clsDataCentral->getOne($id);
	$action_logs = $clsISO->to_array_json($oneItem["action_logs"]);
	$log_potential_CRM = !empty($action_logs['potential_CRM_log']) ? $action_logs['potential_CRM_log'] : array();
	###
	$res = [
		"result"	=>	false,
		"msg"	=>	"Lỗi"
	];
	if(!empty($oneItem)) {
		$more_information = $clsISO->to_array_json($oneItem['more_information']);
		if(!empty($oneItem["phone"]) && $clsCustomer->countItem("phone='{$oneItem["phone"]}' and admin_id='{$profile_id}'")){
			$res = [
				"result"	=>	false,
				"msg"	=>	"Khách hàng đã tồn tại"
			];
		} else {
			if($clsCustomer->insert(array(
				$clsCustomer->pkey => $customer_id,
				'name' => $oneItem['full_name'],
				'name_slug' => $core->replaceSpace($oneItem['full_name']),
				'address' => $more_information['address'],
				'phone' => $oneItem["phone"],
				'email' => !empty($more_information['email']) ? $more_information['email'][0] : "",
				'admin_id' => $profile_id,
				'status_id' => _CRM_POTENTIAL_ID,
				'use_globe' => 0,
				'begin_need' => "",
				'list_share_id' => "",
				'list_block_id' => "",
				'list_bedroom_id' => "",
				'list_campaign_id' => "",
				'blocktype_id' => 0,
				'country_id' => 0,
				'city_id' => 0,
				'resource_id' => 336,
				'more_information' => json_encode(array(), JSON_UNESCAPED_UNICODE),
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			))){
				$res = [
					"result"	=>	true,
					"msg"	=>	"Chuyển dữ liệu khách hàng tiềm năng CRM thành công!"
				];	
				
				#activity log			
				/*$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Customer","insert",$_POST);*/
			}
		}
		
		if(!isset($log_potential_CRM[$profile_id])) {
			$action_logs['potential_CRM_log'][$profile_id] = [
				"profile_id"	=>	$profile_id,
				"reg_date"		=>	time()
			];
			$clsDataCentral->updateOne($id,["action_logs" => json_encode($action_logs)]);
		}
		
	}
	
	// Return
	echo json_encode($res); die();
}
function default_setting_field(){
	global $smarty,$mod,$act,$adminid,$core,$clsISO,$profile_id,$oneProfile,$clsConfiguration;
	$clsDataCentral = new DataCentral();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	###
	$view_by = Input::post('view_by', "table");
	$customer_id = (int) Input::post('customer_id', 0);
	$field = "{$clsProperty->pkey},title,image";
	
	$lstFieldData = $clsConfiguration->getValue('field_data_center');
	$lstFieldData = $clsISO->to_array_json($lstFieldData);
	$field_data_default = array();
	foreach ($lstFieldData as $key => $val) {
		if(!empty($val['is_default'])) {
			$field_data_default[$key] = $val;
		}
	}
	
	###
	$more_information = $oneProfile['more_information'];
	$limit = 15;
	$fieldData = (!empty($more_information["fieldDataCentral"])) ? $more_information["fieldDataCentral"] : array_keys($field_data_default);
	$lstFieldSelected = array();
	foreach($fieldData as $field_id) {
		if(!empty($lstFieldData[$field_id])) {
			$lstFieldSelected[$field_id] = $lstFieldData[$field_id];	
		}
		
	}
	$smarty->assign('lstFieldData', $lstFieldData);
	$smarty->assign('fieldData', $fieldData);
	$smarty->assign('lstFieldSelected', $lstFieldSelected);
	$smarty->assign('limit', $limit);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.setting_field.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_setting_field(){
	global $smarty,$mod,$act,$adminid,$core,$clsISO,$profile_id,$oneProfile,$clsConfiguration;
	$clsProperty = new Property();
	$gid = Input::post("gid","");
	$type = Input::post("type","SEARCH");
	$keyword = Input::post("keyword","");
	$view_by = Input::post("view_by","table");
	$list_field_data = Input::post("list_field_data",array());
	$lstFieldData = $clsConfiguration->getValue('field_data_center');
	$lstFieldData = $clsISO->to_array_json($lstFieldData);
	$field_data_default = array();
	foreach ($lstFieldData as $key => $val) {
		if(!empty($val['is_default'])) {
			$field_data_default[$key] = $val;
		}
	}
	$html = ""; $arr_search = [];
	if($type == "DEFAULT") {
		if(!empty($field_data_default)) {
			foreach($field_data_default as $key => $val) {
				$html .= '<li class="item_field_selected d-flex justify-content-between align-items-center p-2 bg-lighter rounded-1 mb-2 text-black cursor-pointer" title="'.$val['title'].'" id="item_'.$gid.'_'.$key.'" key="'.$key.'">
					<div class="crm-flex filed-select">
						<i class="bx bx-grid-vertical"></i>
						<span class="title-ellipsis text misa-label">'.$val['title'].'</span>
					</div>
					<button class="btn btn-sm text-main p-0" type="button" onClick="$Core.data_central.deleteField(this,event)" toId="field_'.$gid.'_'.$key.'"><i class="bx bx-x"></i></button>
				</li>';
			}
		}else{
			$html = '<p class="text-center mb-0 fs-12 fw-italic">Không tìm thấy kết quả nào</p>';
		}
	}else{
		if(!empty($keyword)) {
			foreach ($lstFieldData as $key => $value){
				if(stristr($value['title'],$keyword)){
					$arr_search[$key] = $value;
				}	
			}
		}else{
			$arr_search = $lstFieldData;
		}	
		if(!empty($arr_search)) {
			foreach($arr_search as $key => $val) {
				$checked = ($clsISO->checkItemInArray($key,$list_field_data)) ? " checked " : "";
				$html .= '<li class="item-menu-settings-column crm-flex crm-align-items-center px-3 py-1" title="'.$val['title'].'">
					<label class="form-check mb-0" for="field_'.$gid.'_'.$key.'">
						<input class="form-check-input" type="checkbox" value="'.$key.'" id="field_'.$gid.'_'.$key.'" onChange="$Core.data_central.add_setting_field(this,event)"  toId="item_'.$gid.'_'.$key.'" data-title="'.$val['title'].'" data-key="'.$key.'" '.$checked.' >
						<span class="form-check-label">'.$val['title'].'</span>
					</label>
				</li>';
			}
		}else{
			$html = '<p class="text-center mb-0 fs-12 fw-italic">Không tìm thấy kết quả nào</p>';
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $gid,
		'html' => $html
	)); die();
}
function default_save_setting_field(){
	global $smarty,$mod,$act,$adminid,$core,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$list_field_data = Input::post("list_field_data",array());
//	$clsISO->print_pre($list_field_data);die;
	$more_information = $oneProfile['more_information'];
	$more_information["fieldDataCentral"] = $list_field_data;
	$res = ["result" => false];
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$res = ["result" => true];
	}
	// Return
	echo json_encode($res); die();
}
function default_load_building(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	
	$html_options = '';
	$block_id = (int)Input::post('block_id',0);
	$building_id = (int)Input::post('building_id',0);
	if(!empty($block_id)){
		$oneBlock = $clsProperty->getOne($block_id,"parent_id");
		$property_type = ($oneBlock['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE) ? "_BUILDING" : "_RANGE";
		$txt_default = ($oneBlock['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE) ? "Chọn tòa" : "Chọn dãy";
//		$clsProperty->setDeBug(1);
		$html = $clsProperty->getSelectByPropertyOrigin($property_type,$block_id,$building_id,$txt_default);
//		echo 1;die;
	}
	// Return
	echo $html; die();
}
function default_load_more(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsDataCentral = new DataCentral();
	$id = (int)Input::get("id",0);
	$field = Input::get("field","");
	$type = Input::get("type","hide");
	$oneItem = $clsDataCentral->getOne($id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$uid = $clsISO->getUniqid();
	$html = "";
	if($field == "phone") {
		$uid = Input::get("uid","");
		$phone = $oneItem['phone'];
		$arr_more = array_filter($more_information['phone'], function($item) use ($phone) {
			return $item != $phone;
		});
		if(!empty($arr_more)) {
			$html = '<div class="profile-wrap">';	
				foreach($arr_more as $phone_more) {
					$phoneMore = ($type == "full") ? $phone_more : $clsDataCentral->mask($phone_more,1);
					$html .= '<a href="javascript:void(0)" onClick="$Core.data_central.log_call(this,event)" data-id="'.$id.'" data-href="https://zalo.me/'.$phone_more.'" class="text-nowrap"><span class="zalo_chat me-1"><img src="'.URL_IMAGES.'/logo_white_s_40.png" width="12" height="12" alt=""></span><span class="phone '.$uid.'" data-phone="'.$phone_more.'">'.$phoneMore.'</span></a>';
				}				
			$html .= '</div>';
		}
	}else if($field == "ms_code"){
		$ms_codes = !empty($oneItem["ms_codes"]) ? $clsISO->getArrayByTextSlash($oneItem["ms_codes"]) : array();
		$html = '<div clas="profile-wrap">';
			for($i=2; $i < count($ms_codes); $i++) {	
				$html .= (($i > 2) ? ", " : "") . $ms_codes[$i];
			}
		$html .= '</div>';
	}else if($field == "email"){
		if(!empty($more_information['email']) && count($more_information['email']) > 1) {
			$html = '<div class="profile-wrap">';	
				foreach($more_information['email'] as $key => $email) {
					if($key > 0) {
						$html .= '<a  class="text-nowrap">'.$email.'</a>';	
					}
					
				}				
			$html .= '</div>';
		}
	}else if($field == "tag"){
		$clsTag = new Tag();
		$tag_ids =  !empty($oneItem["tags"]) ? $clsISO->getArrayByTextSlash($oneItem["tags"]) : array();
		$lstTag = $clsTag->getAll("`tag_type`='_data_central' AND (`user_id`='0' OR `user_id`='{$profile_id}') AND `tag_id` IN (".implode(',',$tag_ids).") ",$clsTag->pkey.',title');
		if(!empty($lstTag) && count($lstTag) > 4) {
			$html = '<div class="profile-wrap overflow-y-auto" style="max-height:300px"><div class="d-flex flex-wrap gap-1">';	
				foreach($lstTag as $key => $_oItem) {
					if($key > 3) {
						$html .= '<a href="'.$clsISO->getLink("data_central").'?tags='.$_oItem["title"].'" class="btn btn-xs btn-default rounded-1 text-nowrap">'.$_oItem["title"].'</a>';	
					}					
				}				
			$html .= '</div></div>';
		}
	}
		
	// Return
	echo $html; die();
}
function default_load_phone(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	$clsDataCentral = new DataCentral();
	$id = (int)Input::get("id",0);
	$oneItem = $clsDataCentral->getOne($id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$phone = $oneItem['phone'];
	$phone2 = array_filter($more_information['phone'], function($item) use ($phone) {
		return $item != $phone;
	});
	$uid = $clsISO->getUniqid();
	$html = "";
	if(!empty($phone2)) {
		$html = '<div clas="profile-wrap">';	
				foreach($phone2 as $phone) {
					$html .= '<a href="javascript:void(0)" onClick="$Core.data_central.log_call(this,event)" data-id="'.$id.'" data-href="https://zalo.me/'.$phone.'" class="text-nowrap"><span class="zalo_chat me-1"><img src="'.URL_IMAGES.'/logo_white_s_40.png" width="12" height="12" alt=""></span>'.$clsDataCentral->mask($phone,1).'</a>';
				}
		$html .= '</div>';
	}	
	// Return
	echo $html; die();
	
	echo json_encode([
		"uid"	=>	$uid,
		"html"	=>	$html
	]);die;
}
function default_open_campaign(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$campaign_id = (int)Input::post("campaign_id",0);
	$date_min = date('Y-m-d\TH:i');
	if(!empty($campaign_id)) {
		$oneItem = $clsCampaign->getOne($campaign_id);
		$smarty->assign('oneItem', $oneItem); 
	}
	#
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}	
	$smarty->assign('uid', $uid);
	$smarty->assign('date_min', $date_min);
	// Return
	$html = $core->build('_ajax.open_campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_add_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	$clsDataCentral = new DataCentral();
	###
	$msg = "_error";
	$result = false;
	$campaign_id = (int) Input::post('campaign_id', 0);
	$title = Input::post('title',"");
	$slug = $core->replaceSpace($title);
	$intro = Input::post('intro',"");
	$start_date = Input::post('start_date',"");
	$end_date = Input::post('end_date',"");
	$start_date = !empty($start_date) ? strtotime($start_date) : time();
	$end_date = !empty($end_date) ? strtotime($end_date) : strtotime("+1 hours",$start_date);
	
	$checkCampaign = $clsCampaign->getByCond("`slug`='{$slug}' AND `user_id`='{$profile_id}'");
	if($checkCampaign) {
		echo json_encode(array(
			'result' => false,
			'msg' => "_invalid"
		)); die();
	}
	
	if($campaign_id == 0){	
		$campaign_id = $clsCampaign->getMaxId();
//		 $dbconn->debug = true;
		if($clsCampaign->insert(array(
			$clsCampaign->pkey => $campaign_id,
			'campaign_type' => '_data_central',
			'title' => $title,
			'slug' => $slug,
			"intro"	=>	$intro,
			"start_date"	=>	$start_date,
			"end_date"	=>	$end_date,
			'use_globe' => 0,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$result = true;
			$msg = "_success";
		}
	} else {
		if($clsCampaign->updateOne($campaign_id,array(
			'title' => $title,
			'slug' => $slug,
			"intro"	=>	$intro,
			"start_date"	=>	$start_date,
			"end_date"	=>	$end_date,
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$result = true;
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'result' => $result,
		'msg' => $msg,
		'name' => $title,
		'campaign_id' => $campaign_id
	)); die();
}
function default_open_data_campaign(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$campaign_id = (int)Input::post("campaign_id",0);
	$list_ids = Input::post("list_ids",[]);
	$date_min = date('Y-m-d\TH:i');
	if(!empty($campaign_id)) {
		$oneItem = $clsCampaign->getOne($campaign_id);
		$smarty->assign('oneItem', $oneItem); 
	}
	$cond = "`campaign_type`='_data_central'";
	if(!$clsISO->checkPermissionGroup('DIRECTOR')) {
		$cond .= " AND `user_id`='{$profile_id}'";
	}
	$lstCampaign = $clsCampaign->getAll($cond,$clsCampaign->pkey.",title");
	$list_staffs = $clsProfile->getProfileCached("active");
	#
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}	
	$smarty->assign('total_data', count($list_ids));
	$smarty->assign('list_ids', json_encode($list_ids,JSON_UNESCAPED_UNICODE));
	$smarty->assign('uid', $uid);
	$smarty->assign('date_min', $date_min);
	$smarty->assign('lstCampaign', $lstCampaign);
	$smarty->assign('list_staffs', $list_staffs);
	// Return
	$html = $core->build('_ajax.open_data_campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_check_data_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	$clsDataCentral = new DataCentral();
	###
	$campaign_id = (int) Input::post('campaign_id', 0);
	$list_ids = Input::post('list_ids',[]);
	$arr_data_central_id = $clsISO->to_array_json($list_ids);
	$staff_id = (int)Input::post('staff_id',0);
	$is_exist = $total_exist = 0;
	if($campaign_id > 0){	
		$oneItem = $clsCampaign->getOne($campaign_id,"campaign_info");
		$campaign_info = $clsISO->to_array_json($oneItem["campaign_info"]);
		$list_staffs = !empty($campaign_info["list_staffs"]) ? $campaign_info["list_staffs"] : [];
		$data_staff = !empty($campaign_info["data_staff"]) ? $campaign_info["data_staff"] : [];
//		$clsISO->print_pre($data_staff);die;
		if(!empty($data_staff)){
			foreach ($data_staff as $staffId => $lstData) {
				$arr_data = array_intersect($lstData, $arr_data_central_id);
//				var_dump($lstData, $arr_data_central_id);die;
//				$clsISO->print_pre($arr_data);die;
				if($staff_id != $staffId && !empty($arr_data)) {
					$is_exist = 1;
					$total_exist += count($arr_data);
				}
				unset($arr_data);
			}
		}
	}	
	// Return
	echo json_encode(array(
		'is_exist' => $is_exist,
		'total_exist' => $total_exist,
		'data' => $_POST,
	)); die();
}
function default_add_data_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	$clsDataCentral = new DataCentral();
	###
	$msg = "_error";
	$result = false;
	$campaign_id = (int) Input::post('campaign_id', 0);
	$list_ids = Input::post('list_ids',[]);
	$arr_data_central_id = $clsISO->to_array_json($list_ids);
	$staff_id = (int)Input::post('staff_id',0);
	$is_confirm = Input::post('is_confirm',"");
	$toId = Input::post('toId',"");
//	$is_confirm = 1;
//	$clsISO->print_pre($_POST);die;
	if($campaign_id > 0){	
		$oneItem = $clsCampaign->getOne($campaign_id,"campaign_info");
		$campaign_info = $clsISO->to_array_json($oneItem["campaign_info"]);
		$list_staffs = !empty($campaign_info["list_staffs"]) ? $campaign_info["list_staffs"] : [];
		if(!$clsISO->checkItemInArray($staff_id,$list_staffs)) {
			$list_staffs[] = $staff_id;
		}
		#
		$data_staff = !empty($campaign_info["data_staff"]) ? $campaign_info["data_staff"] : [];
		if(!empty($data_staff)){
			foreach ($data_staff as $staffId => $lstData) {
				if($is_confirm == "1") { //chuyển data cho người phụ trách mới
					$data_staff[$staffId] = array_values(array_diff($lstData,$arr_data_central_id));
				}elseif($is_confirm == "0"){ //chỉ lấy data chưa có người phụ trách
					$arr_data_central_id = array_values(array_diff($arr_data_central_id, $lstData));
				}
			}
		}
		$data_staff[$staff_id] = !empty($data_staff[$staff_id]) ? array_merge($data_staff[$staff_id], array_diff($arr_data_central_id, $data_staff[$staff_id])) : $arr_data_central_id;
		$campaign_info["list_staffs"] = $list_staffs;
		$campaign_info["data_staff"] = $data_staff;
		$data_upd = [
			"campaign_info"	=>	json_encode($campaign_info,JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		];
		if($clsCampaign->updateOne($campaign_id,$data_upd)){
			$check_upd = 1;
			$msg = "_success";
			$result = true;
		}
		if(!empty($check_upd)) {
//			$dbconn->debug = true;
			$cond = "`{$clsDataCentral->pkey}` IN (".implode(",",$arr_data_central_id).")";
			$lstDataCentral = $clsDataCentral->getAll($cond);
//			$clsISO->print_pre($lstDataCentral);die;
			if(!empty($lstDataCentral)) {
				foreach ($lstDataCentral as $key => $val) {
					$arr_list_campaign_id = $clsISO->getArrayByTextSlash($val["list_campaign_id"]);
					$action_logs = $clsISO->to_array_json($val["action_logs"]);
					if(!$clsISO->checkItemInArray($campaign_id,$arr_list_campaign_id)) {
						$arr_list_campaign_id[] = $campaign_id;						
						$list_campaign_id = $clsISO->makeSlashListFromArray($arr_list_campaign_id);
						$action_logs["campaign_data"] = [
							"list_campaign_id"	=>	$list_campaign_id,
							"reg_date"	=>	time(),
							"user_id"	=>	$profile_id,
						];
						$data_upd = [
							"list_campaign_id"	=>	$list_campaign_id,
							"action_logs"	=>	json_encode($action_logs,JSON_UNESCAPED_UNICODE)
						];
						$clsDataCentral->updateOne($val[$clsDataCentral->pkey],$data_upd);
					}				
				}
			}
		}
	}
	
	// Return
	echo json_encode(array(
		'result' => $result,
		'msg' => $msg,
		'toId' => $toId
	)); die();
}
function default_load_share_staffs(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	
	$uid = $clsISO->getUniqid();
	$department_id = Input::post('department_id', 0);
	if(!empty($department_id)) {
		$list_staffs = $clsProfile->getProfileDep($department_id,1,"active");
	}
		
	$html = '';
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$html .= '<option value="'.$val[$clsProfile->pkey].'">'.sprintf('%s %s', $val['code'], $clsProfile->getFullName($val[$clsProfile->pkey], $val)).'</option>';	
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}

function default_campaign(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$clsConfiguration,$oneProfile;
	$clsDataCentral = new DataCentral(); $assign_list['clsDataCentral'] = $clsDataCentral;
	$clsStock = new Stock();
	$clsCampaign = new Campaign();
	$clsTag = new Tag();
	$clsProperty = new Property();
	#
	$tags = Input::get("tags",""); $assign_list["tags"] = $tags;
	$_ss_tag_ids = explode(",",$tags);
	$assign_list['_ss_tag_ids'] = $_ss_tag_ids;
	#
	$list_tags = $clsTag->getAll("`tag_type`='_data_central' ORDER BY `{$clsTag->pkey}` DESC","{$clsTag->pkey},`title`");
	$assign_list['list_tags'] = $list_tags;
	##
	$list_preloaders = array();
	for($i=0; $i<50; $i++){
		$list_preloaders[] = $i;
	}
	$list_data_fields = $clsConfiguration->getValue('field_data_center');
	$list_data_fields = $clsISO->to_array_json($list_data_fields);
	$arr_columns = $def_field = array();
	foreach($list_data_fields as $key => $val) {
		if(!empty($val['is_default'])) {
			$def_field[] = $key;
		}
	}
	###
	$more_information = $oneProfile['more_information'];
	$list_setting_field = $core->get_field($more_information, "fieldDataCentral", $def_field);
	if(!empty($list_setting_field)){
		foreach($list_setting_field as $id) {
			$arr_columns[$list_data_fields[$id]['code']] = $list_data_fields[$id];
		}
	}
	
	$cond_camp = "`campaign_type`='_data_central'";
	if(!$clsISO->checkPermissionGroup('DIRECTOR')) {
		$cond_camp .= " AND JSON_CONTAINS(campaign_info, '{$profile_id}', '$.list_staffs')";
	}
//	$dbconn->debug=true;
	$lstCampaign = $clsCampaign->getAll($cond_camp,$clsCampaign->pkey.",title");
//	$clsISO->print_pre($lstCampaign);die;
	
	$list_boxs = array(
		'today' => array(
			'title' => 'Hôm nay',
			'icon' => 'bx-repost'
		), 'tomorrow' => array(
			'title' => 'Ngày mai',
			'icon' => 'bx-chalkboard'
		), 'next_10_days' => array(
			'title' => '10 ngày tiếp',
			'icon' => 'bx-task'
		),
	);
	$list_alerts = array(
		'danger' => 'exclamation-triangle',
		'warning' => 'info-circle',
		'success' => 'question',
		'primary' => 'bell-o',
		'secondary' => 'exclamation-triangle',
	);
	$lstStatus = $clsProperty->getArraySearchByKey("DATA_CENTRAL_STATUS");
	$assign_list['list_boxs'] = $list_boxs;
	$assign_list['list_alerts'] = $list_alerts;	
	$assign_list["lstCampaign"] = $lstCampaign;
	$assign_list["arr_columns"] = $arr_columns;
	$assign_list["lstStatus"] = $lstStatus;
	$assign_list["list_preloaders"] = $list_preloaders;
	/*=============Title & Description Page==================*/
	$title_page = 'Chiến dịch dữ liệu cư dân Ocean Park - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = ' Thông tin cư dân - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_open_activity(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$assign_list,$adminid,$core,$clsISO,$dbconn,$profile_id;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsDataCentral = new DataCentral();
	$clsCampaign = new Campaign();
	$clsFollowUp = new FollowUp();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	##
	$tp = Input::post('tp', 'follow-ups');
	$customer_id = (int) Input::post('customer_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	$type_id = (int) Input::post('type_id', 0);
	$type_id = ($type_id > 0) ? $type_id : _FOLLOWUP_TASK_ID;
	if($tp=='notes'){
		$note_id = Input::post('note_id');
		$smarty->assign('note_id', $note_id);
		$props = array(
			'tp' => $tp,
			'note_id' => $note_id,
			'customer_id' => $customer_id
		);
	} else {
		$field = "{$clsProperty->pkey},title,image";
		$list_activity = $clsProperty->getAllCache("`is_trash`=0 AND `parent_id`='0' 
			AND `property_type`='FOLLOWUP_TYPE' order by `order_no` ASC", $field);
		$smarty->assign('list_activity', $list_activity);
		#
		$followup_id = (int) Input::post('followup_id', 0);
		$smarty->assign('followup_id', $followup_id);
		$tp = "_data_central";
		$props = array(
			'tp' => $tp,
			'followup_id' => $followup_id,
			'customer_id' => $customer_id
		);
	}
//	$dbconn->debug=true;
	$oCustomer = $clsDataCentral->getOne($customer_id,"more_information,list_campaign_id,action_logs");
	$more_information = $clsISO->to_array_json($oCustomer["more_information"]);
	$list_purpose_arr = !empty($more_information['list_purpose_id']) ? $more_information['list_purpose_id'] : [];
	$oCustomer['list_purpose_arr'] = $list_purpose_arr;
	$status_id = !empty($more_information['status_id']) ? $more_information['status_id'] : 0;	
	$oCustomer['status_id'] = $status_id;	
	
	$action_logs_cus = !empty($oCustomer["action_logs"]) ? $clsISO->to_array_json($oCustomer["action_logs"]) : [];	
	$status_campaign = !empty($action_logs_cus["status_campaign"]) ? $action_logs_cus["status_campaign"] : [];
	#
	$list_capaign_id = !empty($oCustomer["list_campaign_id"]) ? $clsISO->getArrayByTextSlash($oCustomer["list_campaign_id"]) : [];
	$lstCampaign = $clsCampaign->getAll("`campaign_type`='_data_central' AND `{$clsCampaign->pkey}` IN (".implode(',',$list_capaign_id).") AND JSON_CONTAINS(campaign_info, '{$profile_id}', '$.list_staffs')");
	$smarty->assign('lstCampaign', $lstCampaign);
	$smarty->assign('oCustomer', $oCustomer);
	$smarty->assign('tp', $tp);
	$smarty->assign('type_id', $type_id);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('props', $clsISO->make_attrs_builder($props));
	##
	$time_def_id = $titlePage = "";
	if($tp=='notes'){
		$titlePage = "ghi chú";
	} else {
		$action = "_add";
		$titlePage = sprintf("%s", strtolower($clsProperty->getTitle($type_id)));
		$title = sprintf('%s - %s', $clsProperty->getTitle($type_id), $clsCustomer->getName($customer_id));
		$oneItem = array(
			"type_id" => $type_id,
			"date_id" => time(),
			"reminder_time" => time(), 
			"title" => $title, 
			"content" => ""
		);
		if($followup_id > 0){
			$action = "_edit";
			$oneItem = $clsFollowUp->getOne($followup_id);
			$status_cus = !empty($status_campaign[$oneItem["campaign_id"]]) ? $status_campaign[$oneItem["campaign_id"]] : 0;
			$campaign_id = $oneItem["campaign_id"];
			$smarty->assign('status_cus', $status_cus);
		}
		###
		$list_times = array();
		$list_times['+15 minutes'] = 'Sau 15p';
		$list_times['+30 minutes'] = 'Sau 30p';
		$list_times['+1 hour'] = 'Sau 1h';
		$list_times['+2 hours'] = 'Sau 2h';
		$list_times['+5 hours'] = 'Sau 5h';
		$list_times['+8 hours'] = 'Sau 8h';
		$list_times['+12 hours'] = 'Sau 12h';
		$list_times['+18 hours'] = 'Sau 18h';
		$list_times['+1 day'] = 'Sau 1 ngÃ y';
		$list_times['+2 days'] = 'Sau 2 ngÃ y';
		$list_times['+7 days'] = 'Sau 7 ngÃ y';
		$list_times['+15 days'] = 'Sau 15 ngÃ y';
		$list_times['+30 days'] = 'Sau 30 ngÃ y';
		$smarty->assign('list_times', $list_times);
		// Náº¿u khÃ´ng cÃ³ FU nÃ o thÃ¬ máº·c Ä‘á»‹nh time_def_id = 1day;
		if($clsFollowUp->countItem("target_id='{$customer_id}'") == 0){
			$time_def_id = '1day';
			$oneItem['date_id'] = strtotime('+1 day');
			$oneItem['reminder_time'] = strtotime('+1 day');
		}
	}
	$smarty->assign('action', $action);
	$smarty->assign('campaign_id', $campaign_id);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('time_def_id', $time_def_id);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.open_activity.tpl');
	echo @json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_activity(){
	global $oSmarty,$smarty,$profile_id,$core,$clsISO,$oneProfile,$dbconn;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsDataCentral = new DataCentral();
	$clsFollowUp = new FollowUp();	
	$clsCampaign = new Campaign();	
	##
	$msg = "_error";
	$tp = Input::post('tp', '_data_central');
	$intro = Input::post('intro', ""); // Content
	$customer_id = (int) Input::post('customer_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	$oCustomer = $clsDataCentral->getOne($customer_id, "`more_information`,`full_name`,`action_logs`");
	$oneCampaign = $clsCampaign->getOne($campaign_id);
	$campaign_info = $clsISO->to_array_json($oneCampaign['campaign_info']); 
	$list_share_id = !empty($campaign_info["list_staffs"]) ? $campaign_info["list_staffs"] : []; 
	$more_information = $clsISO->to_array_json($oCustomer['more_information']);
	$action_logs = $core->get_field($more_information, "action_logs", []);
	$list_notify_users = array_diff($list_share_id,[$profile_id]);
	if($tp=='_data_central'){
		$type_id = (int) Input::post('type_id', 0);
		$followup_id = (int) Input::post('followup_id', 0);
		$cus_status_id = (int) Input::post('cus_status_id', 0);
		$cus_purpose_id = Input::post('cus_purpose_id', []);
		$is_done = (int) Input::post('is_done', 0);
		$status_id = ($is_done==1 ? _FOLLOWUP_STATUS_DONE_ID: _FOLLOWUP_STATUS_PLAN_ID);
		$date_id = Input::post('date_id');
		$time_id = Input::post('time_id');
		$reminder_date_id = Input::post('reminder_date_id');
		$reminder_time_id = Input::post('reminder_time_id');
		$is_reminder = (int)Input::post('is_reminder',0);
		$reminder_before = (int)Input::post('reminder_before',0);
		$_result = Input::post('_result');
		$datetime = $clsISO->toTime($date_id, $time_id);
		$reminder_datetime = $clsISO->convertTextToTime($reminder_date_id, $reminder_time_id);
		$more_information["list_purpose_id"] = $cus_purpose_id;		
		
		$action_logs_cus = !empty($oCustomer["action_logs"]) ? $clsISO->to_array_json($oCustomer["action_logs"]) : [];
		$status_campaign = !empty($action_logs_cus["status_campaign"]) ? $action_logs_cus["status_campaign"] : [];
		$cus_status_id_old = !empty($status_campaign[$campaign_id]) ? $status_campaign[$campaign_id] : 0;
		$status_campaign[$campaign_id] = $cus_status_id;
		$action_logs_cus["status_campaign"] = $status_campaign;
//		$clsISO->print_pre($action_logs_cus);die;
//		$dbconn->debug=true;
		if($followup_id == 0){
			$followup_id = $clsFollowUp->getMaxId();
			$arr_property = $content_logs = array();
			$arr_property[] = $type_id;
			$arr_property[] = $status_id;
			if($cus_status_id > 0){
				$arr_property[] = $cus_status_id;
			}
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(',', $arr_property).")", "{$clsProperty->pkey},`title`");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if($val[$clsProperty->pkey] == $type_id){
						$content_logs[] = sprintf('Loại: %s', $val['title']);
					} else if($val[$clsProperty->pkey] == $status_id){
						$content_logs[] = sprintf('Trạng thái: %s', $val['title']);
					} else if($val[$clsProperty->pkey] == $cus_status_id){
						$content_logs[] = sprintf('Tình trạng: %s', $val['title']);
					}
				}
			}
			$content_logs[] = sprintf('Nội dung: %s', $intro);
			if(!empty($_result)) $content_logs[] = sprintf('Kết quả: %s', $intro);
			$content_logs[] = sprintf('Thời gian: %s', $date_id. " ". $time_id);
			if(!empty($cus_purpose_id)){
				$content_logs[] = sprintf('Mục đích: %s', $clsProperty->getTitleArray($cus_purpose_id));
			}
			
			if($clsFollowUp->insert(array(
				$clsFollowUp->pkey => $followup_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'followup_type' => "_data_central",
				'target_id' => $customer_id,
				'intro' => $intro,
				'_result' => $_result,
				'date_id' => $datetime,
				'is_reminder' => $is_reminder,
				'reminder_before' => $reminder_before,
				'reminder_time' => $reminder_datetime,
				'admin_id' => $profile_id,
				'user_id' => $profile_id,
				'campaign_id' => $campaign_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			))){
				$msg = "_success";	
				#activity log
	//			$clsActivityLog = new ActivityLog();			
	//			$log = $clsActivityLog->addActivityLog("FollowUp","insert",$_POST);
				if($datetime > time()){
					$title = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào 
					lúc <strong>%s</strong>.', $clsProperty->getTitle($type_id) .": 
					".$intro, $oCustomer["full_name"], sprintf('%s %s', $date_id, $time_id));
					$clsNotify->insertNotify('DataCentral', $clsFollowUp->pkey, $followup_id, $title, $datetime, '|'.$profile_id.'|');
				}
				// Người tạo là người phụ trách bắn thông báo cho người liên quan
				if(!empty($list_notify_users)){
					$titleNotify = sprintf('<strong>%s</strong> đã thêm tương tác với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $oCustomer["full_name"], $intro, $clsISO->convertTimeToText($datetime, true));
					$clsNotify->insertNotify('DataCentral', $clsFollowUp->pkey, $followup_id, $titleNotify, time(), $list_notify_users);
					#thong bao app
					/*$clsNotification = new Notification();
					$params = [
						'title' => "Chiến dịch khách hàng",
						'body' => strip_tags($titleNotify),
						'link' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $customer_id)
					];
					$clsNotification->doPushMessagingUser($params,$list_notify_users);*/
				// Người tạo là người liên quan thì bắn notify cho người phụ trách
				}
				$content = sprintf('<strong>%s</strong> đã thêm mới follow-ups với %s', 
					$clsProfile->getFullName($profile_id, $oneProfile), implode(',', $content_logs));
				$action_logs[$clsISO->getUniqid()] = array(
					'content' => $content,
					'user_id' => $profile_id,
					'reg_date' => time()
				);
	//			// $clsISO->print_pre($actions_logs); die();
				$more_information['action_logs'] = $action_logs;	
				
				if($clsDataCentral->updateOne($customer_id, array(
					'upd_date' => time(),
					'action_logs' => json_encode($action_logs_cus, JSON_UNESCAPED_UNICODE),
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				))){
					if($cus_status_id > 0 && $cus_status_id_old != $cus_status_id){
						$clsDataCentralHistory = new DataCentralHistory();
						$clsDataCentralHistory->insert(array(
							'campaign_id' => $campaign_id,
							'customer_id' => $customer_id,
							'from_status_id' => $cus_status_id_old,
							'to_status_id' => $cus_status_id,
							'staff_id' => $profile_id,
							'action_date' => time()
						));
					}
				}
			}
		} else {
			$oneFollowup = $clsFollowUp->getOne($followup_id);
			if($clsFollowUp->updateOne($followup_id, array(
				'intro' => $intro,
				'date_id' => $datetime,
				'is_reminder' => $is_reminder,
				'reminder_before' => $reminder_before,
				'reminder_time' => $reminder_datetime,
				'status_id' => $status_id,
				'user_id_update' => $profile_id,
				'campaign_id' => $campaign_id,
				'_result' => $_result,
				'upd_date' => time()
			))){
				$msg = "_success";			
				#activity log	
				// $clsActivityLog = new ActivityLog();		
				// $log = $clsActivityLog->addActivityLog("FollowUp","update",$_POST);
				if($cus_status_id > 0 && $cus_status_id_old != $cus_status_id){
					$content = sprintf('<strong>%s</strong> đã cập nhật tình trạng khách hàng thành %s', 
						$clsProfile->getFullName($profile_id, $oneProfile), $clsProperty->getTitle($cus_status_id));
					$action_logs[$clsISO->getUniqid()] = array(
						'content' => $content,
						'user_id' => $profile_id,
						'reg_date' => time()
					);
					$more_information['action_logs'] = $action_logs;	
				}
				if($clsDataCentral->updateOne($customer_id, array(
					'upd_date' => time(),
					'action_logs' => json_encode($action_logs_cus, JSON_UNESCAPED_UNICODE),
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				))){
					if($cus_status_id > 0 && $cus_status_id_old != $cus_status_id){
						$clsDataCentralHistory = new DataCentralHistory();
						$clsDataCentralHistory->insert(array(
							'campaign_id' => $campaign_id,
							'customer_id' => $customer_id,
							'from_status_id' => $cus_status_id_old,
							'to_status_id' => $cus_status_id,
							'staff_id' => $profile_id,
							'action_date' => time()
						));
					}
				}
			}
		}
	} else {
		$current_now = time();
		$notes = $oCustomer['notes'];
		$notes_arrs = $clsISO->to_array_json($notes);
		$notes_arrs[$clsISO->getUniqid()] = array(
			'content' => $intro,
			'campaign_id' => $campaign_id,
			'reg_date' => $current_now,
			'upd_date' => $current_now,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		);
		$action_logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'campaign_id' => $campaign_id,
			'user_id' => $profile_id,
			'content' => sprintf('<strong>%s</strong> đã thêm mới ghi chú <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $intro),
		);
		$more_information['action_logs'] = $action_logs;
		if($clsDataCentral->updateOne($customer_id, array(
			'notes' => json_encode($notes_arrs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			// Người tạo là người phụ trách bắn thông báo cho người liên quan
			/*if($admin_id == $profile_id && !empty($list_share_id)){
				$list_notify_users = $clsISO->getArrayByTextSlash($list_share_id);
				$titleNotify = sprintf('<strong>%s</strong> đã thêm ghi chú với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($current_now, true));
				// $dbconn->debug=true;
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNotify, time(), $list_notify_users);
			} else if($admin_id != $profile_id){
				// Người tạo là người liên quan thì bắn notify cho người phụ trách
				$titleNotify = sprintf('<strong>%s</strong> đã thêm ghi chú vào khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($current_now, true));
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNotify, time(), '|'.$admin_id.'|');
			}	*/
		}
	}
	// Reuturn
	echo $msg; die();
}
function default_view_activity(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$mod,$act,$adminid,$core,$clsISO,$profile_id;
	$clsDataCentral = new DataCentral();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$clsProfile = new Profile();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsDataCentral', $clsDataCentral);
	#
	$customer_id = (int) Input::post('customer_id', 0);
	$campaign_id = (int) Input::post('campaign_id', 0);
	$field = "{$clsProperty->pkey},title,image";
	$list_activity = $clsProperty->getAllCache("`is_trash`=0 and `parent_id`='0' 
	and `property_type`='FOLLOWUP_TYPE' order by `order_no` ASC", $field);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('list_activity', $list_activity);
	#
	$list_campaign = $clsCampaign->getAll("`campaign_type`='_data_central'",$clsCampaign->pkey.",`title`,`campaign_info`,`start_date`,`end_date`");
	$arr_campaign = $arr_staff = [];
	$lstProfile = $clsProfile->getProfileCached("active");
	$check_expired = 0;
	if(!empty($list_campaign)) {
		foreach ($list_campaign as $key => $val) {
			if($campaign_id == $val[$clsCampaign->pkey]) {
				if($val["end_date"] < time() || $val["start_date"] > time()) {
					$check_expired = 1;
					break;
				}
			}
		}
	}
//	$clsISO->print_pre($check_expired);die;
	$smarty->assign('check_expired', $check_expired);
	
	#- Total activity
	$total_activity = $clsFollowUp->countItem("`followup_type`='_data_central' AND `target_id`='{$customer_id}'");
	$smarty->assign('total_activity', $total_activity);
	#
	$oneCustomer = $clsDataCentral->getOne($customer_id);
	$smarty->assign('oneCustomer', $oneCustomer);
	$list_share_id = $oneCustomer['list_share_id'];
	$arr_share_ids = $clsISO->getArrayByTextSlash($list_share_id);
	#- Total Notes
	$notes = $oneCustomer['notes'];
	$list_notes = !empty($notes) ? json_decode(html_entity_decode($notes), true) : array();
	$total_notes = !empty($list_notes) ? count($list_notes) : 0;
	$smarty->assign('total_notes', $total_notes);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('template_type', '_modal');
	$html = $core->build('_ajax.activity.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_activity(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$profile_id,$core,$clsISO,$dbconn;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$clsProfile = new Profile();
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsFollowUp', $clsFollowUp);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$smarty->assign('customer_id', $customer_id);
//	$list_followups = $clsFollowUp->getAll("`is_trash`=0 and `followup_type`='_data_central' AND `target_id`='{$customer_id}' order by `reg_date` DESC");
	$list_followups = $dbconn->getAll("SELECT `t1`.*,`t2`.`title` as `campaign_name`,`t2`.`start_date`,`t2`.`end_date` FROM `{$clsFollowUp->tbl}` AS `t1` LEFT JOIN `{$clsCampaign->tbl}` AS `t2` ON `t1`.`campaign_id`=`t2`.`campaign_id` WHERE `t1`.`is_trash`=0 AND `t1`.`followup_type`='_data_central' AND `t1`.`target_id`='{$customer_id}' order by `reg_date` DESC");
	if(!empty($list_followups)){
		$arr_property_cached = $arr_profile_cached = array();
		foreach($list_followups as $key => $val){
			$list_followups[$key]["check_expired"] = ($val["end_date"] < time() || $val["start_date"] > time()) ? 1 : 0;
			$type_id = $val['type_id'];
			$admin_id  = $val['admin_id'];
			$followup_id  = $val[$clsFollowUp->pkey];
			if(isset($arr_property_cached[$type_id])){
				$oneProperty = $arr_property_cached[$type_id];
			} else {
				$oneProperty = $clsProperty->getOne($type_id, "title,image,bgcolor,textcolor");
				$arr_property_cached[$type_id] = $oneProperty;
			}
			$list_followups[$key]['oneProperty'] = $oneProperty;
			if(!isset($arr_profile_cached[$admin_id])){
				$arr_profile_cached[$admin_id] = $clsProfile->getAvatar($admin_id,array(),30,30);
			} 
			$list_followups[$key]['avatar'] = $arr_profile_cached[$admin_id];
			$list_reply = $clsFollowUp->getAll("`parent_id`='{$followup_id}' order by `reg_date` ASC");
			if(!empty($list_reply)){
				foreach($list_reply as $okey => $oval){
					$user_id  = $oval['user_id'];
					if(!isset($arr_profile_cached[$user_id])){
						$arr_profile_cached[$user_id] = $clsProfile->getAvatar($user_id,array(),30,30);
					} 
					$list_reply[$okey]['avatar'] = $arr_profile_cached[$user_id];
				}
			}
			$list_followups[$key]['list_reply'] = $list_reply;	
		}
	} else {
		$htmlNotFound = CRM::renderHTMLNoDocument('Không có bất kỳ hoạt động nào<br /> với khách hàng này');
		$smarty->assign('htmlNotFound', $htmlNotFound);
	}
	$smarty->assign('list_followups', $list_followups);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.activity.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete_activity(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$clsISO,$oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$followup_id = (int) Input::post('followup_id', 0);
	###
	$msg = "_error";
	$oneFollowup = $clsFollowUp->getOne($followup_id);	
//	$clsISO->print_pre($oneFollowup);die;
	if($clsFollowUp->deleteOne($followup_id)){
		$msg = "_success";
		#activity log
//		$clsActivityLog = new ActivityLog();	
//		$log = $clsActivityLog->addActivityLog("FollowUp","delete",$oneFollowup);
	}
	// Return
	echo $msg; die();
}
function default_load_consulting(){
	global $smarty,$assign_list,$adminid,$core,$clsISO;
	$clsStock = new Stock();
	$clsDataCentral = new DataCentral();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$list_stock_id = $clsDataCentral->getOneField('list_stock_id', $customer_id);
	$list_stocks = !empty($list_stock_id) 
		? $clsISO->getArrayByTextSlash($list_stock_id) 
		: array();
	$html = '<div class="table-container no-shadow text-nowrap">
	<table cellpadding="0" cellspacing="0" width="100%" class="table ">
		<thead><tr>
			<th class="align-center bg-lighter">Mã căn</th>
			<th class="align-center bg-lighter">Diện tích</th>
			<th class="align-center bg-lighter">Giá</th>
		</tr></thead>';
	if(!empty($list_stocks)){
		foreach($list_stocks as $stock_id){
			$field = "ms_code,more_information";
			$oneStock = $clsStock->getOne($stock_id);
			$ms_code = $oneStock['ms_code'];
			$more_information = $oneStock['more_information'];
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			// $clsISO->print_pre($more_information); die();
			$total_price_vat= 0;
			if(isset($more_information['total_price_vat']) && !empty($more_information['total_price_vat'])){
				$total_price_vat = $more_information['total_price_vat'];
				$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
				$total_price_vat = number_format((float) $clsISO->priceFormat($total_price_vat),3,'.','');
			}
			$html.='<tr>
				<td><a href="/my-favourite/'.$ms_code.'" target="_blank">
					<i class="bx bx-link-external"></i> '.$ms_code.'</a>
				</td>
				<td>'.$more_information['DT_TT'].'</td>
				<td>'.$total_price_vat.'</td>
			</tr>';
		}
	} else {
		$html.= '<tr><td colspan="3">
			<div class="p-2">
				'.CRM::renderHTMLNoDocument('Chưa có tư vấn căn nào').'
			</div>
		</td></tr>';
	}
	$html .= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_logs(){
	global $smarty,$assign_list,$adminid,$core,$clsISO;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsDataCentral = new DataCentral();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	##
	$customer_id = (int) Input::post('customer_id', 0);
	$oCustomer = $clsDataCentral->getOne($customer_id, "more_information");
	$more_information = $oCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$action_logs = $core->get_field($more_information, "action_logs", []);
	// $clsISO->print_pre($action_logs); die();
	if(!empty($action_logs)){
		$html = '<ul class="logs">';
		foreach($action_logs as $key => $val){
			$html.= '<li>'.$clsISO->convertTimeToText($val['reg_date'], true).': '.$val['content'].'</li>';
		}
		$html.= '</ul>';
	} else {
		$html.= '<div class="d-flex flex-column py-3 align-items-center justify-content-center">
			<img src="'.URL_IMAGES.'/empty.svg" class="w-px-100 mb-2" />
			<p class="text-muted">Chưa có bất kỳ thao tác nào được ghi nhận</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_done_followup(){
	global $smarty,$adminid,$core,$clsISO;
	$clsFollowUp = new FollowUp();
	$customer_id = (int) Input::post('customer_id',0);
	$followup_id = (int) Input::post('followup_id',0);
	/** Update */
	$msg = '_error';
	if($clsFollowUp->updateOne($followup_id, array(
		'status_id'	=> _FOLLOWUP_STATUS_DONE_ID,
		'upd_date'	=> time()
	))){
		$msg = '_success';
	}
	// Return
	echo json_encode(array(
		"msg"	=> $msg
	));die;
}
function default_load_desktop_followups(){
	global $deviceType,$smarty,$profile_id,$oneProfile,$core,$dbconn,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsDataCentral = new DataCentral();
	##
	$tp = Input::post('tp', 'today');
	$keyword =  Input::post('keyword','');
	$sort_by =  Input::post('sort_by','date_id');
	$campaign_id =  (int)Input::post('campaign_id',0);
	$sort_type =  Input::post('sort_type','desc');
	$more_information = $oneProfile['more_information'];
	$desktop_followup_view = $core->get_field($more_information, "desktop_followup_view", "_plan");
	#- Cond
	$cond = " `followup_type`='_data_central' AND `is_trash`=0 AND `type_id` in ('".implode('\',\'', array(_FOLLOWUP_CALL_ID,_FOLLOWUP_TASK_ID,_FOLLOWUP_ZALO_ID))."')";
	if(!$clsISO->checkPermissionGroup('DIRECTOR')) {
		$cond .= " AND `admin_id`='{$profile_id}'";
	}
	if(!empty($campaign_id)){
		 $cond.= " AND `campaign_id`='".$campaign_id."'";
	}
	if($tp=='tomorrow'){
		$date_id  = strtotime("+1 day");
		$cond.= " AND FROM_UNIXTIME(`date_id`,'%d/%m/%Y')='".date('d/m/Y', $date_id)."'";
	} else if($tp == 'next_10_days'){
		$start_date = strtotime("+1 day");
		$end_date = strtotime("+10 days", $start_date);
		$cond.= " AND (`date_id` BETWEEN {$start_date} AND {$end_date})";
	} else {
		$date_id = time();
		$cond.= " AND FROM_UNIXTIME(`date_id`,'%d/%m/%Y')='".date('d/m/Y', $date_id)."'";
	}
//	echo date("d/m/Y",1770721860);die;
	$html= ''; $total_record = 0; 
//	$dbconn->debug = true;
	$list_followups = $clsFollowUp->getAll($cond." order by `date_id` DESC");
//	$clsISO->print_pre($list_followups);die;
	if(!empty($list_followups)){ $ii= 1;
		$total_record = count($list_followups);
		$html.= '<table class="table table-no-border-end table-middle" width="100%">';
		$arr_property_cached = array();
		foreach($list_followups as $followup){
			$date_id = $followup['date_id'];
			$type_id = $followup['type_id'];
			$status_id = $followup['status_id'];
			$customer_id = $followup['target_id'];
			$oneCustomer = $clsDataCentral->getOne($customer_id, "phone,full_name");
//			$clsISO->print_pre($oneCustomer);die;
			$followup_id = $followup[$clsFollowUp->pkey];
			$props = 'customer_id="'.$customer_id.'" followup_id="'.$followup_id.'"';
			if(isset($arr_property_cached[$type_id])){
				$oneProperty = $arr_property_cached[$type_id];
			} else {
				$field = "{$clsProperty->pkey},bgcolor,textcolor,image";
				$oneProperty = $clsProperty->getOne($type_id, $field);
				$arr_property_cached[$type_id] = $oneProperty;
			}
			$link = "";
			if($type_id==_FOLLOWUP_CALL_ID && !empty($oneCustomer['phone'])){
				$link = "tel:".$oneCustomer['phone'];
			} else if($type==_FOLLOWUP_ZALO_ID && !empty($oneCustomer['phone'])){
				$link = "https://zalo.me/".$oneCustomer['phone'];
			} else {
				$link = "javascript:void(0)";
			}
			$html.= '<tr'.($status_id==_FOLLOWUP_STATUS_DONE_ID?' class="tr-done nohover"':'').'>
				<td width="40px" class="text-center">
					<a href="'.$link.'" style="background:'.$oneProperty['bgcolor'].'; color:'.$oneProperty['textcolor'].'" class="d-block activity-icon mt-1 rounded-circle text-center border-0 shadow-none">
						<i class="bx '.$oneProperty['image'].' fs-20 m-2"></i>
					</a>
				</td>
				'.($deviceType=='phone'? '<td class="text-left">
					<div class="mb-n0"><a href="javascript:void(0)" class="font-bold  link goLink view_customer" onClick="$Core.data_central.open_customer(this,event)" route="/customer/'.$customer_id.'/overview" customer_id="'.$customer_id.'">'.$oneCustomer["full_name"].'</a></div>
					<div class="line-clamp-2 cursor-pointer" onclick="$Core.data_central.view_activity(this, event);" customer_id="'.$customer_id.'">'.$followup['intro'].'</div>
					<span class="text-'.($date_id>time()?'main':'muted').' text-nowrap fs-12">
						<i class="material-icons-outlined">notifications_active</i>
						'.$clsISO->convertTimeToText($followup['date_id'],true).'
					</span>
				</td>':'<td class="text-left" style="width:25%">
					<div class="mb-n1"><a href="javascript:void(0)" class="font-bold  link goLink view_customer" onClick="$Core.data_central.open_customer(this,event)" route="/customer/'.$customer_id.'/overview" customer_id="'.$customer_id.'">'.$oneCustomer["full_name"].'</a></div>
					<span class="text-'.($date_id>time()?'main':'muted').' text-nowrap fs-12">
						<i class="material-icons-outlined">notifications_active</i>
						'.$clsISO->convertTimeToText($followup['date_id'],true).'
					</span>
				</td>
				<td class="text-left cursor-pointer" onclick="$Core.data_central.view_activity(this, event);" customer_id="'.$customer_id.'">
					<div class="line-clamp-2">'.$followup['intro'].'</div>
					'.(!empty($followup['_result']) ? '--- <br /> <strong>KQ:</strong> '.$followup['_result'] : '' ).'
				</td>').'
				<td width="30px" class="text-center">
					<div class="btn-group">
						<a href="javascript:void(0);" title="Hoàn thành" tp="follow-ups" type_id="'.$type_id.'" class="btn btn-icon btn-sm btn-outline-default'.($status_id==_FOLLOWUP_STATUS_DONE_ID?' disabled':'').'" onclick="$Core.data_central.done_followup(this, event);" followup_id="'.$followup_id.'" customer_id="'.$customer_id.'"><i class="bx bx-check"></i></a>
						<button title="Follow-ups" onclick="$Core.data_central.view_activity(this, event);" customer_id="'.$customer_id.'" class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-bell"></i></button>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		$html.= '</table>';
	}else{
		$html .= '<div class="py-3 text-center">
			<div class="py-2">
				'.CRM::renderHTMLNoDocument('Rất tiếc <br /> Chưa có lịch làm việc với khách hàng vào 
				<strong class="text-main">'.($tp == 'today' ? 'hôm nay' : ($tp == 'tomorrow' ? 'ngày mai' : '10 ngày tiếp theo'))).'</strong>
			</div>
		</div>';
	}
	// output
	echo @json_encode(array(
		'cond' => $cond,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'html'	=> $html
	)); die();
}
function default_load_converted_rates(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn;
	global $profile_id, $oneProfile;
	$clsDataCentral = new DataCentral();
	$clsDataCentralHistory = new DataCentralHistory();
	$clsProfile = new Profile();
	$clsCampaign = new Campaign();
	$clsProperty = new Property();
	$campaign_id = (int)Input::post("campaign_id",0);
	if(!empty($campaign_id)) {
		$oneCampaign = $clsCampaign->getOne($campaign_id);
		$campaign_info = $clsISO->to_array_json($oneCampaign["campaign_info"]);
		$data_staff = !empty($campaign_info["data_staff"]) ? $campaign_info["data_staff"] : [];
		if($clsISO->checkPermissionGroup('DIRECTOR')) {
			$arrData = [];
			if(!empty($data_staff)) {
				foreach ($data_staff as $profileId => $lstData) {
					$arrData = array_unique(array_merge($arrData,$lstData));
				}
			}
		}else{
			$arrData = !empty($data_staff[$profile_id]) ? $data_staff[$profile_id] : [];
		}
		
		$html = "";
		$cond = "`from_status_id` > 0 AND `campaign_id`='{$campaign_id}' AND `customer_id` IN (SELECT `{$clsDataCentral->pkey}` FROM `{$clsDataCentral->tbl}` WHERE `list_campaign_id` LIKE '%|".$campaign_id."|%')";
		if(!$clsISO->checkPermissionGroup('DIRECTOR')){
			$cond .= " AND `customer_id` IN (".implode(',',$arrData).")";
		}
		$group_by = " GROUP BY `from_status_id`, `to_status_id`";
		$list = $clsDataCentralHistory->getAll($cond.$group_by, "`from_status_id`,`to_status_id`,COUNT(DISTINCT `customer_id`) AS `total_move`,COUNT(`customer_id`) AS `total_from`");
		if(!empty($list)){
			$arr_property_cached = $clsProperty->getArraySearchByKey("DATA_CENTRAL_STATUS");
			foreach($list as $key => $val){
				$conversion_rate = !empty($arrData) ? round((int)$val["total_move"] / count($arrData) * 100) : 0;
				$from_status_id = (int) $val['from_status_id'];
				$to_status_id = (int) $val['to_status_id'];
				$html.= '<div class="d-flex flex-column mb-1">
					<div class="d-flex align-items-center justify-content-between text-fs-13 mb-0">
						<span class="text-muted">'.$arr_property_cached[$from_status_id]["title"].' ('.$val['total_from'].')</span>
						<span class="">'.$arr_property_cached[$to_status_id]["title"].' ('.$val['total_move'].')</span>
					</div>
					<div class="progress w-100 h-px-12">
						<div class="progress-bar bg-info" role="progressbar" style="width:'.$conversion_rate.'%">'.$conversion_rate.'%</div>
					</div>
				</div>';
			}
		} else {
			$html.= '<div class="d-flex p-3 flex-column align-items-center justify-content-center">
				<img src="'.URL_IMAGES.'/empty.svg" class="w-px-100 mb-2" />
				<p class="text-muted">Chưa có khách hàng nào được chuyển đổi!</p>
			</div>';
		}
	}
	
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_performance_campaign(){
	global $smarty,$core,$clsISO,$oneProfile,$profile_id,$dbconn; 
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	$clsDataCentralHistory = new DataCentralHistory();
	$smarty->assign("clsProfile",$clsProfile);
	
	$lstProfileCached = $clsProfile->getProfileCached("active");
	$arr_status_cached = $clsProperty->getArraySearchByKey("DATA_CENTRAL_STATUS");
	###
	$uid = $clsISO->getUniqid();
	$campaign_id = (int) Input::post('campaign_id', 0);
	$_tp = Input::get('tp', "chart");
	$oneCampaign = $clsCampaign->getOne($campaign_id);
	if(!empty($oneCampaign)) {
		$campaign_info = $clsISO->to_array_json($oneCampaign["campaign_info"]);
		$data_staff = !empty($campaign_info["data_staff"]) ? $campaign_info["data_staff"] : [];
//		$clsISO->print_pre($data_staff);die;
		$lstStaff = array_keys($data_staff);
		$arr_staff_flollowup = $arr_status_data = [];
//		$dbconn->debug = true;
		$lstFollowup = $clsFollowUp->getAll("`followup_type`='_data_central' AND `campaign_id`='{$campaign_id}' AND `status_id`='"._FOLLOWUP_STATUS_DONE_ID."' AND `admin_id` IN (".implode(',',$lstStaff).") GROUP BY `target_id`");
		foreach ($lstFollowup as $key => $val) {
			if(!isset($arr_staff_flollowup[$val["admin_id"]])) {
				$arr_staff_flollowup[$val["admin_id"]] = 1;
			}else{
				$arr_staff_flollowup[$val["admin_id"]] += 1;
			}
		}
		$lstStatusData = $clsDataCentralHistory->getAll("`campaign_id`='{$campaign_id}' AND `staff_id` IN (".implode(',',$lstStaff).") GROUP BY `customer_id`");
		foreach ($lstStatusData as $key => $val) {
			if(!isset($arr_status_data[$val["staff_id"]][$val["to_status_id"]])) {
				$arr_status_data[$val["staff_id"]][$val["to_status_id"]] = 1;
			}else{
				$arr_status_data[$val["staff_id"]][$val["to_status_id"]] += 1;
			}
		}
//		$clsISO->print_pre($arr_status_data);die;
		$lstItem = [];
		foreach ($lstStaff as $profileId) {
			if(isset($lstProfileCached[$profileId])) {
				$more_profile = $lstProfileCached[$profileId]["more_information"];
				$staff_name = "[".$more_profile["department_name"]."]".$lstProfileCached[$profileId]["full_name"];
				$total_data = !empty($data_staff[$profileId]) ? count($data_staff[$profileId]) : 0;
				$total_followup = !empty($arr_staff_flollowup[$profileId]) ? count($arr_staff_flollowup[$profileId]) : 0;
				$arr_status = [];
				foreach ($arr_status_cached as $key => $val) {
					if($val["property_id"] != _DATA_STATUS_DONTCARE_ID) {
						$arr_status[$val["property_id"]] = !empty($arr_status_data[$profileId][$val["property_id"]]) ? $arr_status_data[$profileId][$val["property_id"]] : 0;
					}					
				}
				$lstItem[] = [
					"staff_id"	=>	$profileId,
					"oneStaff"	=>	$lstProfileCached[$profileId],
					"staff_name"	=>	$staff_name,
					"total_data"	=>	$total_data,
					"total_followup"	=>	$total_followup,
					"arr_status"	=>	$arr_status,
				];
			}
			
		}
		$smarty->assign("arr_status_cached",$arr_status_cached);
		$smarty->assign("lstItem",$lstItem);
	}	
	$html = $core->build("_ajax.load_performation_data.tpl");
	echo json_encode(array(
		'html' => $html
	)); die();
}