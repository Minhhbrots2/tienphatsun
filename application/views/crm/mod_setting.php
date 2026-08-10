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
function default_open_task_setting(){
	global $core, $clsISO, $smarty;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$list_task = $clsSetting->getCacheItems('_CRM_TASK');
	$list_result = $clsSetting->getCacheItems('_CRM_RESULT');
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', 'Thiết lập tác nghiệp');
	$smarty->assign('list_task', $list_task);
	$smarty->assign('list_result', $list_result);
	$smarty->assign('delay_options', crm_task_setting_delay_options());
	$html = $core->build('_ajax.crm_task_setting.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}

function crm_task_setting_delay_options($selected_value=0, $selected_unit='minute'){
	$options = array(
		array('minute', 5, '5 phút'),
		array('minute', 10, '10 phút'),
		array('minute', 15, '15 phút'),
		array('minute', 25, '25 phút'),
		array('hour', 1, '1 giờ'),
		array('hour', 2, '2 giờ'),
		array('hour', 4, '4 giờ'),
		array('day', 1, '1 ngày'),
		array('day', 2, '2 ngày'),
		array('day', 3, '3 ngày'),
		array('day', 5, '5 ngày'),
		array('day', 7, '7 ngày'),
		array('day', 9, '9 ngày'),
		array('day', 12, '12 ngày'),
		array('day', 15, '15 ngày'),
		array('day', 20, '20 ngày'),
		array('day', 30, '30 ngày')
	);
	$html = '<option value="">Chọn thời gian</option>';
	foreach($options as $opt){
		$unit = $opt[0];
		$val = (int) $opt[1];
		$label = $opt[2];
		$selected = ($val===$selected_value && $unit===$selected_unit) ? ' selected' : '';
		$html .= '<option value="'.$val.'|'.$unit.'"'.$selected.'>'.$label.'</option>';
	}
	return $html;
}

function crm_task_setting_build_map($list){
	$map = array();
	if(!empty($list)){
		foreach($list as $item){
			$map[(int)$item['setting_id']] = $item['title'];
		}
	}
	return $map;
}

function crm_task_setting_render_row($row, $map_task, $map_result){
	if(empty($row['delay_unit'])){
		$row['delay_unit'] = 'minute';
	}
	$task_title = !empty($map_task[(int)$row['task_id']]) ? $map_task[(int)$row['task_id']] : '';
	$result_title = !empty($map_result[(int)$row['result_id']]) ? $map_result[(int)$row['result_id']] : '';
	$next_task_title = !empty($map_task[(int)$row['next_task_id']]) ? $map_task[(int)$row['next_task_id']] : '';
	$delay_options = crm_task_setting_delay_options((int)$row['delay_value'], $row['delay_unit']);
	$upd_date = !empty($row['upd_date']) ? date("H:i d/m/Y", (int)$row['upd_date']) : '';
	$admin_name = !empty($row['admin_name']) ? $row['admin_name'] : '';
	return '<tr data-id="'.(int)$row['customer_task_setting_id'].'">'.
		'<td>'.$task_title.'</td>'.
		'<td>'.$result_title.'</td>'.
		'<td>'.$next_task_title.'</td>'.
		'<td><select class="form-select form-select-sm js__crm-task-delay">'.$delay_options.'</select></td>'.
		'<td>'.$upd_date.'<br />'.$admin_name.'</td>'.
		'<td class="text-center"><button class="btn btn-sm btn-outline-default js__crm-task-delete" data-id="'.(int)$row['customer_task_setting_id'].'"><i class="bx bx-trash"></i></button></td>'.
	'</tr>';
}

function crm_task_setting_render_paging($current_page, $total_page){
	if($total_page <= 1){
		return '';
	}
	$start = max(1, $current_page - 2);
	$end = min($total_page, $current_page + 2);
	if($end - $start < 4){
		$start = max(1, $end - 4);
		$end = min($total_page, $start + 4);
	}
	$html = '<ul class="pagination pagination-sm mb-0">';
	$prev = $current_page > 1 ? $current_page - 1 : 1;
	$next = $current_page < $total_page ? $current_page + 1 : $total_page;
	$html .= '<li class="page-item'.($current_page<=1?' disabled':'').'"><a class="page-link js__crm-task-page" data-page="'.$prev.'" href="javascript:void(0)">«</a></li>';
	for($i=$start; $i<=$end; $i++){
		$html .= '<li class="page-item'.($i==$current_page?' active':'').'"><a class="page-link js__crm-task-page" data-page="'.$i.'" href="javascript:void(0)">'.$i.'</a></li>';
	}
	$html .= '<li class="page-item'.($current_page>=$total_page?' disabled':'').'"><a class="page-link js__crm-task-page" data-page="'.$next.'" href="javascript:void(0)">»</a></li>';
	$html .= '</ul>';
	return $html;
}

function default_load_crm_task_setting(){
	global $core, $profile_id, $dbconn;
	$clsSetting = new Setting();
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$clsProfile = new Profile();
	$task_id = (int) Input::get('task_id', 0);
	$result_id = (int) Input::get('result_id', 0);
	$next_task_id = (int) Input::get('next_task_id', 0);
	$page = (int) Input::get('page', 1);
	$per_page = (int) Input::get('per_page', 10);
	if($page <= 0) $page = 1;
	if($per_page <= 0) $per_page = 10;
	$list_task = $clsSetting->getCacheItems('_CRM_TASK');
	$list_result = $clsSetting->getCacheItems('_CRM_RESULT');
	$map_task = crm_task_setting_build_map($list_task);
	$map_result = crm_task_setting_build_map($list_result);
	$cond = "`is_trash`=0";
	if($task_id>0) $cond .= " and `task_id`='{$task_id}'";
	if($result_id>0) $cond .= " and `result_id`='{$result_id}'";
	if($next_task_id>0) $cond .= " and `next_task_id`='{$next_task_id}'";
	$total = (int) $dbconn->GetOne("SELECT COUNT(*) FROM ".$clsCustomerTaskSetting->tbl." WHERE {$cond}");
	$total_page = $total > 0 ? (int) ceil($total / $per_page) : 1;
	if($page > $total_page) $page = $total_page;
	$offset = ($page - 1) * $per_page;
	$list = $clsCustomerTaskSetting->getAll($cond." order by `order_no` asc, `customer_task_setting_id` desc limit {$offset},{$per_page}");
	$html = '';
	if(!empty($list)){
		foreach($list as $row){
			$row['admin_name'] = $clsProfile->getFullName($row['user_id']);
			$html .= crm_task_setting_render_row($row, $map_task, $map_result);
		}
	}
	$from = $total>0 ? ($offset + 1) : 0;
	$to = $total>0 ? min($offset + $per_page, $total) : 0;
	$summary = $from.'-'.$to.' of '.$total.' items';
	$pagination = crm_task_setting_render_paging($page, $total_page);
	echo json_encode(array(
		'error' => 0,
		'html' => $html,
		'summary' => $summary,
		'pagination' => $pagination
	)); die();
}

function default_add_crm_task_setting(){
	global $core, $clsISO, $profile_id, $dbconn;
	$clsSetting = new Setting();
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$clsProfile = new Profile();

	$task_id = (int) Input::post('task_id', 0);
	$result_id = (int) Input::post('result_id', 0);
	$next_task_id = (int) Input::post('next_task_id', 0);
	if($task_id<=0 || $result_id<=0 || $next_task_id<=0){
		echo json_encode(array(
			'error' => 1,
			'message' => 'Vui lòng chọn đủ tác nghiệp cần, kết quả và tác nghiệp tiếp.'
		)); die();
	}
	$delay_value = (int) Input::post('delay_value', 0);
	$delay_unit = Input::post('delay_unit', 'minute');
	$delay_combo = Input::post('delay_combo', '');
	if(!empty($delay_combo) && strpos($delay_combo, '|') !== false){
		$tmp = explode('|', $delay_combo);
		$delay_value = (int) $tmp[0];
		$delay_unit = !empty($tmp[1]) ? $tmp[1] : $delay_unit;
	}
	if(!in_array($delay_unit, array('minute','hour','day'))){
		$delay_unit = 'minute';
	}
	$now = time();
	$order_no = $clsCustomerTaskSetting->getMaxOrderNo();
	$clsCustomerTaskSetting->insert(array(
		'task_id' => $task_id,
		'result_id' => $result_id,
		'next_task_id' => $next_task_id,
		'delay_value' => $delay_value,
		'delay_unit' => $delay_unit,
		'order_no' => $order_no,
		'reg_date' => $now,
		'upd_date' => $now,
		'user_id' => $profile_id,
		'is_trash' => 0
	));
	$insert_id = (int) $dbconn->insert_Id();

	$list_task = $clsSetting->getCacheItems('_CRM_TASK');
	$list_result = $clsSetting->getCacheItems('_CRM_RESULT');
	$map_task = crm_task_setting_build_map($list_task);
	$map_result = crm_task_setting_build_map($list_result);
	$row = array(
		'customer_task_setting_id' => $insert_id,
		'task_id' => $task_id,
		'result_id' => $result_id,
		'next_task_id' => $next_task_id,
		'delay_value' => $delay_value,
		'delay_unit' => $delay_unit,
		'upd_date' => $now,
		'admin_name' => $clsProfile->getFullName($profile_id)
	);
	$html = crm_task_setting_render_row($row, $map_task, $map_result);
	echo json_encode(array(
		'error' => 0,
		'html' => $html
	)); die();
}

function default_update_crm_task_setting(){
	global $profile_id;
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$crm_task_setting_id = (int) Input::post('id', 0);
	$delay_combo = Input::post('delay_combo', '');
	$delay_value = 0;
	$delay_unit = 'minute';
	if(!empty($delay_combo) && strpos($delay_combo, '|') !== false){
		$tmp = explode('|', $delay_combo);
		$delay_value = (int) $tmp[0];
		$delay_unit = !empty($tmp[1]) ? $tmp[1] : $delay_unit;
	}
	if(!in_array($delay_unit, array('minute','hour','day'))){
		$delay_unit = 'minute';
	}
	if($crm_task_setting_id<=0){
		echo json_encode(array('error'=>1,'message'=>'Dữ liệu không hợp lệ.')); die();
	}
	$clsCustomerTaskSetting->updateOne($crm_task_setting_id, sprintf("delay_value='%s', delay_unit='%s', upd_date='%s', user_id='%s'",
		$delay_value, $delay_unit, time(), $profile_id
	));
	echo json_encode(array('error'=>0)); die();
}

function default_delete_crm_task_setting(){
	$clsCustomerTaskSetting = new CustomerTaskSetting();
	$crm_task_setting_id = (int) Input::post('id', 0);
	if($crm_task_setting_id<=0){
		echo json_encode(array('error'=>1,'message'=>'Dữ liệu không hợp lệ.')); die();
	}
	$clsCustomerTaskSetting->updateOne($crm_task_setting_id, "is_trash=1");
	echo json_encode(array('error'=>0)); die();
}
