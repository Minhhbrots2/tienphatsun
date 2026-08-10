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
function manager_manager(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$clsConfiguration,$oneProfile;
	$clsDataCentral = new DataCentral(); $assign_list['clsDataCentral'] = $clsDataCentral;
	$clsProfile = new Profile(); $assign_list['clsProfile'] = $clsProfile;

	$field = "{$clsProfile->pkey},`code`,`full_name`";
	$lstUser = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' AND `department_id`='{$oneProfile["department_id"]}' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $field);
	$assign_list["lstUser"] = $lstUser;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý chăm sóc dữ liệu cư dân Ocean Park - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = ' Thông tin cư dân - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function manager_load_total_chart(){
	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$image_page,$extLang,$clsISO,$profile_id,$clsConfiguration,$oneProfile;
	$clsDataCentral = new DataCentral(); $assign_list['clsDataCentral'] = $clsDataCentral;
	$clsProfile = new Profile(); $assign_list['clsProfile'] = $clsProfile;
	$staff_id = (int)Input::post("staff_id",0);
	$total_record = $clsDataCentral->countItem("1=1");
	
	$cond = " jt.profile_id IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` WHERE `is_trash`=0 and `is_active`='1' AND `department_id`='{$oneProfile["department_id"]}' and `status_id`<>'"._STATUS_STAFF_OFF_ID."')";	
	if(!empty($staff_id)) {
		$cond .= " AND jt.profile_id = '{$staff_id}'";
	}	
	$order_by = " ORDER BY time_call desc";
	$lstDataCall = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.*,jt.reg_date as time_call,jt.profile_id
		FROM `{$clsDataCentral->tbl}`,
		JSON_TABLE(
		  JSON_EXTRACT(`{$clsDataCentral->tbl}`.logs, '$.call_success'),
		  '$.*' COLUMNS (
			profile_id INT PATH '$.profile_id',
			reg_date INT PATH '$.reg_date'
		  )
		) AS jt
		WHERE " . $cond);
	$total_call = !empty($lstDataCall) ? count($lstDataCall) : 0;
	
	$start_date = strtotime(date("d-m-Y"));
	$due_date = strtotime(date("d-m-Y 23:59"));
	$lstDataCallToday = $lstDataCall = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.*,jt.reg_date as time_call,jt.profile_id
		FROM `{$clsDataCentral->tbl}`,
		JSON_TABLE(
		  JSON_EXTRACT(`{$clsDataCentral->tbl}`.logs, '$.call_success'),
		  '$.*' COLUMNS (
			profile_id INT PATH '$.profile_id',
			reg_date INT PATH '$.reg_date'
		  )
		) AS jt
		WHERE " . $cond." AND jt.reg_date BETWEEN {$start_date} AND {$due_date}");
	$total_call_today = !empty($lstDataCallToday) ? count($lstDataCallToday) : 0;
	
	$smarty->assign("total_record",$total_record);
	$smarty->assign("total_call",$total_call);
	$smarty->assign("total_call_today",$total_call_today);
	$html = $core->build("_ajax.load_total_chart.tpl");	
	echo json_encode([
		"uid"	=>	$uid,
		"html"	=>	$html
	]);die;
}

function manager_load_chart_call(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsDataCentral = new DataCentral();
	$clsProfile = new Profile();
	###
	$html = "";
	$date = Input::post('date',"");
	$due_date = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$due_date = !empty($date) ? $clsISO->toTime(sprintf('%s 23:59:59', $date)) : $due_date;	
	$start_date = strtotime('-10 days', strtotime(date("d-m-Y",$due_date)));
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	#- End Pagination	
	$arr_time = [];	
	$limit = 7;
	for($i=0; $i<$limit; $i++){
		$arr_time[] = strtotime("-".$i."days",$due_date);
	}
	$arr_time = array_reverse($arr_time);
	$cond = " jt.profile_id IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` WHERE `is_trash`=0 and `is_active`='1' AND `department_id`='{$oneProfile["department_id"]}' and `status_id`<>'"._STATUS_STAFF_OFF_ID."') AND jt.reg_date BETWEEN {$start_date} AND {$due_date}";	
	$order_by = " ORDER BY time_call desc";
	$lstDataCall = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.id,jt.reg_date as time_call,jt.profile_id
		FROM `{$clsDataCentral->tbl}`,
		JSON_TABLE(
		  JSON_EXTRACT(`{$clsDataCentral->tbl}`.logs, '$.call_success'),
		  '$.*' COLUMNS (
			profile_id INT PATH '$.profile_id',
			reg_date INT PATH '$.reg_date'
		  )
		) AS jt
		WHERE " . $cond);
	$data = [];
	$t=1;
	if(!empty($lstDataCall)){
		$arr_profile_cached = array();
		foreach($lstDataCall as $key => $val){
			if(isset($data[date("d/m/Y",$val['time_call'])])) {
				$data[date("d/m/Y",$val['time_call'])] += 1;
			}else{
				$data[date("d/m/Y",$val['time_call'])] = 1;
			}
		}
	}
	for($i=0; $i<count($arr_time); $i++){
		$day = date("d/m/Y",$arr_time[$i]);
		if(isset($data[$day])) {
			$dataPoints[] = array(
				'label'	=> $day,
				'y'	=> $data[$day]
			);
		}else{
			$dataPoints[] = array(
				'label'	=> $day,
				'y'	=> 0
			);
		}
	}
	$barChartData['data'] = array(
		array(
			"type"    => "spline",
			"color"	 => "#1d6a01",
			"name"    => "Cuộc gọi",
			"yValueFormatString"	=> "# cuộc gọi",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		)
	);
	$uid = $clsISO->getUniqid();
	echo json_encode( array(
		'uid' => $uid,
		'barChartData' => $barChartData,
		'drawchart'	=> 1,
//		'multichart'	=> 0,
		'html'	=>	"<div id='".$uid."' class='chartContainer' style='height:260px'></div>"
	));die;
}
function manager_load_chart_call_staff(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsDataCentral = new DataCentral();
	$clsProfile = new Profile();
	###
	$html = "";
	$date = Input::post('date',"");
	$due_date = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
	$due_date = !empty($date) ? $clsISO->toTime(sprintf('%s 23:59:59', $date)) : $due_date;	
	$start_date = strtotime('-10 days', strtotime(date("d-m-Y",$due_date)));
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$lstUser = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' AND `department_id`='{$oneProfile["department_id"]}' and `status_id`<>'"._STATUS_STAFF_OFF_ID."'", "{$clsProfile->pkey},full_name");
	
	$cond = " jt.profile_id IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` WHERE `is_trash`=0 and `is_active`='1' AND `department_id`='{$oneProfile["department_id"]}' and `status_id`<>'"._STATUS_STAFF_OFF_ID."') AND jt.reg_date BETWEEN {$start_date} AND {$due_date}";	
	$order_by = " ORDER BY time_call desc";
	$lstDataCall = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.id,jt.reg_date as time_call,jt.profile_id
		FROM `{$clsDataCentral->tbl}`,
		JSON_TABLE(
		  JSON_EXTRACT(`{$clsDataCentral->tbl}`.logs, '$.call_success'),
		  '$.*' COLUMNS (
			profile_id INT PATH '$.profile_id',
			reg_date INT PATH '$.reg_date'
		  )
		) AS jt
		WHERE " . $cond);
	$data = [];
	$t=1;
	if(!empty($lstDataCall)){
		$arr_profile_cached = array();
		foreach($lstDataCall as $key => $val){
			if(isset($data[$val['profile_id']])) {
				$data[$val['profile_id']] += 1;
			}else{
				$data[$val['profile_id']] = 1;
			}
		}
	}
	foreach ($lstUser as $key => $val) {
		$full_name = $clsProfile->getFullName($val['profile_id'],$val);
		if(isset($data[$val['profile_id']])) {
			$dataPoints[] = array(
				'label'	=> $full_name,
				'y'	=> $data[$val['profile_id']]
			);
		}else{
			$dataPoints[] = array(
				'label'	=> $full_name,
				'y'	=> 0
			);
		}
	}
//	$clsISO->print_pre($dataPoints);die;
	$barChartData['data'] = array(
		array(
			"type"    => "spline",
			"color"	 => "#1d6a01",
			"name"    => "Cuộc gọi",
			"yValueFormatString"	=> "# cuộc gọi",
			"showInLegend"    => true,
			"dataPoints"    => $dataPoints
		)
	);
	$uid = $clsISO->getUniqid();
	echo json_encode( array(
		'uid' => $uid,
		'barChartData' => $barChartData,
		'drawchart'	=> 1,
//		'multichart'	=> 0,
		'html'	=>	"<div id='".$uid."' class='chartContainer' style='height:260px'></div>"
	));die;
}

function manager_load_list_log(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$oneProfile,$dbconn;
	$clsDataCentral = new DataCentral();
	$clsProfile = new Profile();
	$gId = Input::post("gId","");
	$start_date = Input::post("start_date","");
	$due_date = Input::post("due_date","");
	$staff_id = (int)Input::post("staff_id",0);
	$type = Input::get("type","call_success");
	$log_type = !empty($type) ? $type : "call_success";
	
	$start_date = !empty($start_date) ? strtotime($start_date) : strtotime(date("d-m-Y"));
	$due_date = !empty($due_date) ? strtotime(sprintf("%s %s",$due_date,"23:59")) : strtotime(date("d-m-Y 23:59"));
	
	$cond = " jt.reg_date BETWEEN {$start_date} AND {$due_date} AND jt.profile_id IN (SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` WHERE `is_trash`=0 and `is_active`='1' AND `department_id`='{$oneProfile["department_id"]}' and `status_id`<>'"._STATUS_STAFF_OFF_ID."')";
	
	if(!empty($staff_id)) {
		$cond .= " AND jt.profile_id = '{$staff_id}'";
	}
	
	$order_by = " ORDER BY time_call desc";
	$lstDataCenter = $dbconn->getAll("SELECT `{$clsDataCentral->tbl}`.*,jt.reg_date as time_call,jt.profile_id
		FROM `{$clsDataCentral->tbl}`,
		JSON_TABLE(
		  JSON_EXTRACT(`{$clsDataCentral->tbl}`.logs, '$.".$log_type."'),
		  '$.*' COLUMNS (
			profile_id INT PATH '$.profile_id',
			reg_date INT PATH '$.reg_date'
		  )
		) AS jt
		WHERE " . $cond . $order_by);
	
	$arr_cache_profile = array();
	foreach ($lstDataCenter as $key => $val) {
		$log = $clsISO->to_array_json($val['logs']);
		$more_information = $clsISO->to_array_json($val['more_information']);
		$lstDataCenter[$key]['more_information'] = $more_information;
		$phone = $val['phone'];
		$lstDataCenter[$key]['phone2'] = array_filter($more_information['phone'], function($item) use ($phone) {
			return $item != $phone;
		});
			
		$ms_codes = !empty($val["ms_codes"]) ? $clsISO->getArrayByTextSlash($val["ms_codes"]) : array();
		$lstDataCenter[$key]['ms_codes'] =  $ms_codes;
		$lstDataCenter[$key]['more_ms_code'] = (count($ms_codes) > 2) ? (count($ms_codes) - 2) : 0;
		
		$logs = $clsISO->to_array_json($val["logs"]);
		$log_call = $logs['call_log'];
		
		if(!isset($arr_cache_profile[$val['profile_id']])) {
			$arr_cache_profile[$val['profile_id']] = $clsProfile->getOne($val['profile_id'],$clsProfile->pkey.",full_name,avatar");
		}
		
		$lstDataCenter[$key]['time_call'] = $clsISO->formatDate($val['time_call'],4);
		$lstDataCenter[$key]['profile'] = $arr_cache_profile[$val['profile_id']];
		unset($more_information,$logs,$log_call);
	}
	$smarty->assign("lstDataCenter",$lstDataCenter);
	$html = $core->build("manager".DS."_ajax.loadListCall.tpl");
//	echo $html;die;
	// Return
	echo json_encode([
		"html"	=>	$html,
		"gId"	=>	$gId,
		"total_record"	=>	!empty($lstDataCenter) ? count($lstDataCenter) : 0
	]);die;
}