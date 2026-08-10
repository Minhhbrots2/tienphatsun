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
if (file_exists(DIR_MODULES . DS . 'crm' . DS . 'init.php')) {
	require_once(DIR_MODULES . DS . 'crm' . DS . 'init.php');
}
if (file_exists(DIR_MODULES . DS . 'crm' . DS . 'mod_default.php')) {
	require_once(DIR_MODULES . DS . 'crm' . DS . 'mod_default.php');
}
if (file_exists(DIR_MODULES . DS . 'crm' . DS . 'mod_setting.php')) {
	require_once(DIR_MODULES . DS . 'crm' . DS . 'mod_setting.php');
}
if (file_exists(DIR_MODULES . DS . 'crm' . DS . 'mod_member.php')) {
	require_once(DIR_MODULES . DS . 'crm' . DS . 'mod_member.php');
}
function default_get_select_city(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsCountry = new Country();
	$clsCity = new City();
	$country_id = (int) Input::post('country_id', 0);
	$html = $clsCity->makeSelectOption($country_id, 0);
	// return
	echo $html;
	die();
}
function default_default(){
	global $oSmarty, $smarty, $assign_list, $title_page, $profile_id, $core, $clsISO, $_LANG_ID, $oneProfile, $dbconn;
	global $deviceType;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$clsFollowUp = new FollowUp();
	$clsCampaign = new Campaign();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsFollowUp', $clsFollowUp);
	$smarty->assign('clsCampaign', $clsCampaign);
	##
	$list_boxs = array(
		'today' => array(
			'title' => 'Hôm nay',
			'icon' => 'bx-repost'
		),
		'tomorrow' => array(
			'title' => 'Ngày mai',
			'icon' => 'bx-chalkboard'
		),
		'next_10_days' => array(
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
	$assign_list['list_boxs'] = $list_boxs;
	$assign_list['list_alerts'] = $list_alerts;
	$more_information = $oneProfile['more_information'];
	$hide_help = (int) $core->get_field($more_information, "hide_help", 0);
	$_ss_view = $core->get_field($more_information, "_ss_view", "table");
	$desktop_followup_view = (int) $core->get_field($more_information, "desktop_followup_view", "_plan");
	$assign_list['_ss_view'] = $_ss_view;
	$assign_list['hide_help'] = $hide_help;
	$assign_list['desktop_followup_view'] = $desktop_followup_view;
	#
	$department_id = (int) $oneProfile['department_id'];
	$cond = "`is_trash`=0 and `campaign_type`='_campaign'";
	if ($department_id == _DEPARTMENT_MKT_ID) {
		$cond .= " and (`user_id` in (
			select `profile_id` from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='" . _DEPARTMENT_MKT_ID . "'
		) OR `use_globe`=1)";
	} else {
		$cond .= " and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	}
	$field = "{$clsCampaign->pkey},title";
	$list_campaigns = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", $field);
	##
	$is_all = (int) Input::get('is_all', 0);
	$tab = Input::get('tab', 'owner');
	$holderG = Input::get('holderG', '_desktop');
	$current_page = (int) Input::get('page', 1);
	$per_page = (int) Input::get('per_page', 20);
	$get_status_id = (int) Input::get('status_id', 0);
	$get_resource_id = (int) Input::get('resource_id', 0);
	$get_blocktype_id = (int) Input::get('blocktype_id', 0);
	$get_campaign_id = (int) Input::get('campaign_id', 0);
	$get_admin_id = (int) Input::get('admin_id', 0);
	$get_group_id = (int) Input::get('group_id', 0);
	$get_overdue = (int) Input::get('overdue', 0); // drill Leader Dashboard: khách bỏ quên >2 ngày
	$get_untouched = (int) Input::get('untouched', 0); // drill: chưa tương tác lần nào
	$get_hot = (int) Input::get('hot', 0); // drill: lead ưu tiên (lead_score cao)
	$assign_list['tab'] = $tab;
	$assign_list['is_all'] = $is_all;
	$assign_list['holderG'] = $holderG;
	$assign_list['current_page'] = $current_page;
	$assign_list['per_page'] = $per_page;
	$assign_list['get_status_id'] = $get_status_id;
	$assign_list['get_resource_id'] = $get_resource_id;
	$assign_list['get_blocktype_id'] = $get_blocktype_id;
	$assign_list['get_admin_id'] = $get_admin_id;
	$assign_list['get_group_id'] = $get_group_id;
	$assign_list['get_campaign_id'] = $get_campaign_id;
	$assign_list['get_overdue'] = $get_overdue;
	$assign_list['get_untouched'] = $get_untouched;
	$assign_list['get_hot'] = $get_hot;
	$assign_list['list_campaigns'] = $list_campaigns;
	$field = "{$clsProperty->pkey},`title`,`bgcolor`,`intro`";
	# đọc TƯƠI (getAll) thay vì cache 24h: khi trạng thái bị đổi tên/ẩn (is_trash) chip phải phản ánh ngay; list ≤10 dòng nên cache vô nghĩa lại gây hiện trạng thái đã ẩn do file-cache ADOdb cũ.
	$list_status_array = $clsProperty->getAll("`is_trash`=0 AND `property_type`='CUSTOMER_STATUS'
		AND {$clsProperty->pkey}<>'" . _CRM_STATUS_DONTCARE_ID . "' order by `order_no` asc", $field);
	$assign_list['list_status_array'] = $list_status_array;
	# U-P0a: chip tac nghiep tu _CRM_TASK + dem theo task_current_id (scope owner)
	$clsSetting = new Setting();
	$_crm_tasks = $clsSetting->getCacheItems('_CRM_TASK');
	$_task_counts = array();
	$_total_chip = 0;
	$_chip_owner_col = 'admin_id'; // Phụ trách = admin_id (cả MKT) — khớp scope tab owner của list
		$_tcr = $clsCustomer->getAll("`is_trash`=0 AND `{$_chip_owner_col}`='{$profile_id}' AND `status_id`<>'" . _CRM_STATUS_TRASH_ID . "' GROUP BY `task_current_id`", "`task_current_id`, COUNT(*) c");
	if (!empty($_tcr)) {
		foreach ($_tcr as $_r) {
			$_task_counts[(int)$_r['task_current_id']] = (int)$_r['c'];
			$_total_chip += (int)$_r['c'];
		}
	}
	$_lead_task = (int) _CRM_TASK_LEAD_ID;
	$list_crm_task_chips = array();
	if (!empty($_crm_tasks)) {
		foreach ($_crm_tasks as $_t) {
			$_tid = (int) $_t[$clsSetting->pkey];
			$_cnt = isset($_task_counts[$_tid]) ? $_task_counts[$_tid] : 0;
			if ($_tid == $_lead_task && isset($_task_counts[0])) {
				$_cnt += $_task_counts[0];
			}
			$list_crm_task_chips[] = array('task_id' => $_tid, 'title' => $_t['title'], 'count' => $_cnt);
		}
	}
	$assign_list['list_crm_task_chips'] = $list_crm_task_chips;
	$assign_list['total_crm_task_chips'] = $_total_chip;
		# chip Tình trạng (CUSTOMER_STATUS) — đếm theo status_id, cùng scope owner với chip tác nghiệp.
		$_status_counts = array();
		$_total_status_chip = 0;
		$_scr = $clsCustomer->getAll("`is_trash`=0 AND `{$_chip_owner_col}`='{$profile_id}' GROUP BY `status_id`", "`status_id`, COUNT(*) c");
		if (!empty($_scr)) {
			foreach ($_scr as $_r) {
				$_sid = (int) $_r['status_id'];
				$_status_counts[$_sid] = (int) $_r['c'];
				# status "trash" (363 Không thành công) bị ẩn khỏi list mặc định → loại khỏi tổng "Tất cả" cho khớp; chip riêng của nó vẫn giữ count thật.
				if ($_sid !== (int) _CRM_STATUS_TRASH_ID) { $_total_status_chip += (int) $_r['c']; }
			}
		}
		$list_crm_status_chips = array();
		if (!empty($list_status_array)) {
			foreach ($list_status_array as $_st) {
				$_sid = (int) $_st[$clsProperty->pkey];
				$list_crm_status_chips[] = array('status_id' => $_sid, 'title' => $_st['title'], 'bgcolor' => $_st['bgcolor'], 'count' => isset($_status_counts[$_sid]) ? $_status_counts[$_sid] : 0);
			}
		}
		$assign_list['list_crm_status_chips'] = $list_crm_status_chips;
		$assign_list['total_crm_status_chips'] = $_total_status_chip;
	#
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = $list_quaters = $list_preloaders = array();
	for ($i = $start_year; $i <= $end_year; $i++) {
		$list_years[] = $i;
	}
	for ($i = 1; $i <= date('m'); $i++) {
		$list_months[] = $i;
	}
	###
	for ($i = 0; $i < 50; $i++) {
		$list_preloaders[] = $i;
	}
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	$assign_list['list_preloaders'] = $list_preloaders;
	$cmd = Input::get("cmd", "");
	$scriptJs = "";
	if ($cmd == "_detail") {
		$customer_id = Input::get('customer_id', 0);
		$scriptJs .= '<a class="autoclick_' . $customer_id . ' view_customer" onClick="$Core.crm.open_customer(this,event)" customer_id="' . $customer_id . '"></a>
		<script type="text/javascript">
			$(function(){
				setTimeout(() => {
					$(\'.autoclick_' . $customer_id . '\').trigger(\'click\').remove();
				}, 500);
			});
		</script>';
	}
	$assign_list["scriptJs"] = $scriptJs;
	/*=============Title & Description Page==================*/
	$title_page = 'CRM | ' . PAGE_NAME;
	$description_page = 'CRM | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
	$assign_list['description_page'] = $description_page;
}
function default_load_desktop_counter(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile, $deviceType;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsBilling = new Billing();
	if ($deviceType == 'phone') {
		$list_couters = array(
			'NUM_CUS' => 'Số khách mới',
			'NUM_INTERACT' => 'Số tương tác',
			'NUM_CALL' => 'Số cuộc gọi',
			'NUM_MEET' => 'Số cuộc gặp',
			'NUM_SOLD' => 'Số căn chốt'
		);
		$list_totals = array(
			'PREV_MONTH' => array(
				'title' => 'Tháng<br />trước',
				'short_title' => 'Th.trước',
				'start_date' => strtotime('first day of last month'),
				'due_date' => strtotime('last day of last month 23:59:59')
			),
			'THIS_MONTH' => array(
				'title' => 'Tháng<br />này',
				'short_title' => 'Th.này',
				'start_date' => strtotime("first day of this month"),
				'due_date' => strtotime("last day of this month 23:59:59")
			),
			'PREV_WEEK' => array(
				'title' => 'Tuần<br />trước',
				'short_title' => 'Tu.trước',
				'start_date' => strtotime('monday last week'),
				'due_date' => strtotime('sunday last week 23:59:59')
			),
			'THIS_WEEK' => array(
				'title' => 'Tuần<br />này',
				'short_title' => 'Tu.này',
				'start_date' => strtotime('monday this week'),
				'due_date' => strtotime('sunday this week 23:59:59')
			)
		);
		$uid = $clsISO->getUniqid();
		$html = '<style type="text/css">
			.tbl' . $uid . ' > :not(caption) > * > *{
				padding:0.425rem 0.325rem !important;
			}
		</style>
		<div class="table-wrapper">
			<table class="table no-bootstrap table-bordered tbl' . $uid . '">';
		$html .= '<thead><tr>
				<th></th>';
		foreach ($list_totals as $key => $val) {
			$html .= '<th class="text-center">
						<a class="lh-1">' . $val['title'] . '</a>
					</th>';
		}
		$html .= '</tr></thead>';
		foreach ($list_couters as $key => $val) {
			$html .= '<tr>
					<th>' . $val . '</th>';
			foreach ($list_totals as $okey => $oval) {
				$start_date = $oval['start_date'];
				$due_date = $oval['due_date'];
				if ($key == 'NUM_CUS') {
					$total = $clsCustomer->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and (`reg_date` between '{$start_date}' and '{$due_date}')");
				} else if ($key == 'NUM_INTERACT') {
					$total = $clsFollowUp->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and (`date_id` between '{$start_date}' and '{$due_date}')");
				} else if ($key == 'NUM_CALL') {
					$total = $clsFollowUp->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and type_id='" . _FOLLOWUP_CALL_ID . "' and (`date_id` between '{$start_date}' and '{$due_date}')");
				} else if ($key == 'NUM_MEET') {
					$total = $clsFollowUp->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and type_id='" . _FOLLOWUP_TASK_ID . "' and (`date_id` between '{$start_date}' and '{$due_date}')");
				} else if ($key == 'NUM_SOLD') {
					$total = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}' and (`deposit_date` between '{$start_date}' and '{$due_date}')");
				}
				$html .= '<td class="text-center">' . ($key == 'NUM_SOLD' ? '<a class="font-bold text-main">' . $total . '</a>' : '<a href="javascript:void(0);" onClick="$Core.crm.kafa_customer(this, event)" tp="' . $key . '" start_date="' . $start_date . '" due_date="' . $due_date . '" title="' . $val . '" class="font-bold text-main">' . $total . '</a>') . '</td>';
			}
			$html .= '</tr>';
		}
		$html .= '</table>
		</div>';
	} else {
		$tp = Input::post('tp', 'customer');
		$list_totals = array(
			'PREV_MONTH' => array(
				'title' => 'Tháng trước',
				'icon' => 'bx-store-alt bx-sm',
				'total' => 0
			),
			'THIS_MONTH' => array(
				'title' => 'Tháng này',
				'icon' => 'bx-trophy bx-sm',
				'total' => 0
			),
			'PREV_WEEK' => array(
				'title' => 'Tuần trước',
				'icon' => 'bx-bullseye bx-sm',
				'total' => 0
			),
			'THIS_WEEK' => array(
				'title' => 'Tuần này',
				'icon' => 'bx-wallet bx-sm',
				'total' => 0
			)
		);
		$cond = "`is_trash`=0";
		if ($tp == 'customer') {
			$label = 'khách mới';
			$clsClassTable = $clsCustomer;
			if ($profile_id != 9) {
				$cond .= " and (`admin_id`='{$profile_id}' 
					or `customer_id` IN (SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` WHERE `meta_type`='share' AND `meta_id`='{$profile_id}')
				)";
			}
		} else if ($tp == 'follow-ups') {
			$label = 'cuộc gặp';
			$clsClassTable = $clsFollowUp;
			if ($profile_id != 9) {
				$cond .= " and (`admin_id`='{$profile_id}' and `type_id`='" . _FOLLOWUP_TASK_ID . "')";
			}
		}
		foreach ($list_totals as $key => $val) {
			if ($key == 'PREV_MONTH') {
				$start_date = strtotime('first day of last month');
				$due_date = strtotime('last day of last month');
			} else if ($key == 'THIS_MONTH') {
				$start_date = strtotime("first day of this month");
				$due_date = strtotime("last day of this month");
			} else if ($key == 'PREV_WEEK') {
				$start_date = strtotime('monday last week');
				$due_date = strtotime('sunday last week');
			} else if ($key == 'THIS_WEEK') {
				$start_date = strtotime('monday this week');
				$due_date = strtotime('sunday this week');
			}
			$total = $clsClassTable->countItem($cond . " and (`reg_date` between '{$start_date}' and '{$due_date}')");
			$list_totals[$key]['total'] = $total;
		}
		$smarty->assign('label', $label);
		$smarty->assign('list_totals', $list_totals);
		// Return
		$html = $core->build('_ajax.counter.tpl');
	}
	echo $html;
	die();
}
function default_hide_help(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	#
	$more_information = $oneProfile['more_information'];
	$more_information['hide_help'] = 1;
	#
	$msg = "_error";
	if ($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$msg = "_success";
	}
	// Return
	echo $msg;
	die();
}
function default_load_desktop_chart_cus(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$show = Input::post('show');
	$tp = Input::post('tp', "THIS_YEAR");
	###
	$cond = "`is_trash`=0 and `resource_id`<>'" . _CRM_RESOURCE_SALEMOC_ID . "'";
	if ($show == 'me') {
		$cond .= " and (`admin_id`='{$profile_id}')";
	} else if ($show == 'staff') {
		$staff_id = (int) Input::post('staff_id', 0);
		$cond .= " and (`admin_id`='{$staff_id}')";
	} else {
		if ($clsISO->checkPermissionGroup('DIRECTOR')) {
			// Next
		} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
			$department_id = $oneProfile['department_id'];
			$cond .= " and `admin_id` in (
				SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
				WHERE (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')
			)";
		}
	}
	$data = array();
	$dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	#
	if (in_array($tp, array('THIS_WEEK', 'PREV_WEEK'))) {
		$tmp = $clsISO->getRangeTime($tp);
		for ($i = $tmp['start_date']; $i <= $tmp['due_date']; $i = strtotime("+1 day", $i)) {
			$total = $clsCustomer->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='" . date('d/m/Y', $i) . "'");
			$dataPoints[] = array(
				'label'	=> sprintf('%s', $clsISO->getDayOfWeekAcronym($i)),
				'y'	=> $total * 1,
				'indexLabel' => (string) $total
			);
		}
	} else if (in_array($tp, array('THIS_MONTH', 'PREV_MONTH'))) {
		$tmp = $clsISO->getRangeTime($tp);
		for ($i = $tmp['start_date']; $i <= $tmp['due_date']; $i = strtotime("+1 day", $i)) {
			$total = $clsCustomer->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='" . date('d/m/Y', $i) . "'");
			$dataPoints[] = array(
				'label'	=> sprintf('%s', date('d', $i)),
				'y'	=> $total * 1,
				'indexLabel' => (string) $total
			);
		}
	} else if (in_array($tp, array('THIS_YEAR', 'PREV_YEAR'))) {
		$year = date('Y');
		if ($tp == 'PREV_YEAR') $year -= 1;
		for ($month = 1; $month <= 12; $month++) {
			$m = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
			$total = $clsCustomer->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='{$m}'");
			$dataPoints[] = array(
				'label'	=> sprintf('T%s', $month),
				'y'	=> $total * 1,
				'indexLabel' => (string) $total
			);
		}
	}
	###
	$data['type'] = 'column';
	//$data['showInLegend'] = 'true';
	//$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	$html = '<div class="chartContainer" style="height:300px">
		<div id="' . $uid . '" class="w-100 h-100"></div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData
	));
	die();
}
function default_load_desktop_chart_res(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	###
	$show = Input::post('show');
	$tp = Input::post('tp', "THIS_YEAR");
	###
	$cond = "`is_trash`=0";
	if ($show == 'me') {
		$cond .= " and (`admin_id`='{$profile_id}')";
	} else if ($show == 'staff') {
		$staff_id = (int) Input::post('staff_id', 0);
		$cond .= " and (`admin_id`='{$staff_id}')";
	} else {
		if ($clsISO->checkPermissionGroup('DIRECTOR')) {
			// Next
		} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
			$department_id = $oneProfile['department_id'];
			$cond .= " and `admin_id` in (
				SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
				WHERE (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')
			)";
		}
	}
	##
	$barChartData = array();
	$data = $dataPoints = array();
	$barChartData['animationEnabled'] = true;
	###
	$tmp = $clsISO->getRangeTime($tp);
	$start_date = $tmp['start_date'];
	$due_date 	= $tmp['due_date'];
	$cond .= " AND (`reg_date` BETWEEN {$start_date} AND {$due_date})";
	// echo $cond; die();
	$field = "{$clsProperty->pkey},title";
	$list_props = $clsProperty->getAllCache("`is_trash`=0 and {$clsProperty->pkey}<>'" . _CRM_RESOURCE_SALEMOC_ID . "' 
		and `property_type`='_CUSTOMER_RESOURCES' order by order_no ASC", $field);
	if (!empty($list_props)) {
		foreach ($list_props as $key => $val) {
			$property_id = $val[$clsProperty->pkey];
			$total_customers = $clsCustomer->countItem("{$cond} and `resource_id`='{$property_id}'");
			$dataPoints[] = array(
				'label'	=> $clsProperty->getTitle($property_id, $val),
				'y'	=> $total_customers * 1,
				'indexLabel' => (string) $total_customers,
			);
		}
		unset($list_props);
	}
	// Return
	$data['type'] = 'column';
	$data['theme'] = 'light2';
	//$data['showInLegend'] = 'true';
	//$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	//$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	$uid = $clsISO->getUniqid();
	$html = '<div class="chartContainer" style="height:300px">
		<div id="' . $uid . '" class="w-100 h-100"></div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData
	));
	die();
}
function default_load_calendar(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$holderG = Input::post('holderG', '_desktop');
	$typeHoldder = Input::post('typeHoldder', '_all');
	if ($holderG == '_desktop') {
		$html = '<style type="text/css">
			.fc-event-custom{ text-align:center;}
			.fc-event-skin{background-color:transparent !important; border:0 !important}
		</style>';
	} else {
		$html = '<style type="text/css">
			.fc-widget-header{height:30px; line-height:30px;}
			.fc-event-skin{background-color:transparent !important; border:0 !important}
			.fc-event-custom{ height:90px; position:relative;}
			.fc-event-list{ position:absolute; left:0px; bottom:5px;}
			.event{display:inline-block;width:9pt; height:9pt;margin:0 2px 2px 0;}
			.event{box-shadow:inset 0 0 5px 0 rgba(0,0,0,.4);border-radius:8px;border:1px solid #fff;}
			.event-lead-link{color: #009926;}
			.fc-event-custom>.badge{line-height:12px;}
			@media (min-width:768px){
				.btn-group-toolbar{
					margin-top:-45px;
				}
			}
		</style>';
	}
	if ($holderG == '_tablist') {
		$html .= '<div class="btn-group pull-right btn-group-toolbar">
			<button type="button" class="btn btn-sm js_filter-calendar btn-' . ($typeHoldder == '_all' ? 'primary' : 'default') . '" v="_all">' . $core->makeIcon('users', 'Tất cả') . '</button>
			<button type="button" class="btn btn-sm js_filter-calendar btn-' . ($typeHoldder == '_me' ? 'primary' : 'default') . '" v="_me">' . $core->makeIcon('user', 'Chỉ tôi') . '</button>
		</div>
		<div class="clearfix"></div>';
	}
	$html .= '<div id="calendar' . ($holderG == '_desktop' ? '_desktop' : '') . '" class="simple-calendar"></div>';
	if ($holderG == '_tablist') {
		$html .= '<div class="clearfix mt-2"></div>
		<div class="d-flex align-items-center pull-left">
			' . ($core->makeIcon('calendar mr-2')) . ' <a href="javascript:void(0);" class="text-link">
			<u>' . $core->get_Lang('Add to your Google Calendar') . '</u></a>
		</div>';
	}
	echo $html;
	die();
}
function default_load_cell_calendar(){
	global $oSmarty, $smarty, $core, $clsISO, $dbconn, $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	#- Params
	$start = (int) Input::get('start', 0);
	$end = (int) Input::get('end', 0);
	$tp = Input::get('tp', 'follow-ups');
	$cond = "`is_trash`=0";
	if ($tp == 'customer') {
		$field = "reg_date";
		$cond .= " and `admin_id`='{$profile_id}'";
		$clsClassTable = $clsCustomer;
	} else if ($tp == 'follow-ups') {
		$field = "date_id";
		$cond .= " and `admin_id`='{$profile_id}'";
		$clsClassTable = $clsFollowUp;
	}
	for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
		$date_id = date('d-m-Y', $i);
		$total_record = $clsClassTable->countItem("{$cond} and FROM_UNIXTIME({$field},'%d-%m-%Y')='{$date_id}'");
		$results[] = array(
			'tp' => $tp,
			'title' => PAGE_NAME,
			'number' => $total_record,
			'start' => date('Y-m-d H:i:s', $i)
		);
	}
	// Return
	echo @json_encode($results);
	die();
}
function default_load_followups_month(){
	global $oSmarty, $smarty, $core, $clsISO, $dbconn, $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	##
	$start = (int) Input::get('start', 0);
	$end = (int) Input::get('end', 0);
	$holderG = Input::get('holderG', '_desktop');
	##
	$cond = "is_trash=0";
	if ($clsISO->checkPermission('full_permissions_crm')) {
		if ($clsISO->checkPermissMs()) {
		} else if ($clsISO->checkHeadSale()) {
			$department_id = $oneProfile['department_id'];
			$list_staffs = $dbconn->getCol("select {$clsProfile->pkey} from {$clsProfile->tbl} 
				where `is_trash`=0 and `is_active`='1' and (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%')");
			$cond .= " and (`admin_id`='{$profile_id}' or `admin_id` in " . implode(',', $list_staffs) . ")";
		} else {
			$cond .= " and (`admin_id`='{$adminid}' or `user_id`='{$adminid}')";
		}
	} else {
		$cond .= " and (`admin_id`='{$profile_id}' or `user_id`='{$profile_id}')";
	}
	#
	$results = array();
	if ($holderG == '_desktop') {
		for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
			$date_id = date('d-m-Y', $i);
			$numberFollowUp = $clsFollowUp->countItem("{$cond} and FROM_UNIXTIME(date_id,'%d-%m-%Y')='{$date_id}'");
			$results[] = array(
				'title' => PAGE_NAME,
				'number' => $numberFollowUp,
				'start' => date('Y-m-d H:i:s', $i)
			);
		}
	} else {
		$typeHoldder = Input::get('typeHoldder', '_all');
		if ($clsISO->checkPermission('full_permissions_crm')) {
			if ($typeHoldder == '_me') {
				$cond .= " and `admin_id`='{$profile_id}'";
			}
		}
		for ($i = $start; $i <= $end; $i = strtotime('+1 day', $i)) {
			$date_id = date('d-m-Y', $i);
			$lstFollowUp = $clsFollowUp->getAll("{$cond} and FROM_UNIXTIME(date_id,'%d-%m-%Y')='{$date_id}' order by date_id ASC");
			$numberFollowUp = 0;
			$_htmlList = $_htmlTable = '';
			if (!empty($lstFollowUp)) {
				$numberFollowUp = count($lstFollowUp);
				$_htmlTable .= '<table class="table table-striped" cellpadding="0" cellspacing="0" width="100%">';
				foreach ($lstFollowUp as $followup) {
					$customer_id = $followup["customer_id"];
					$crm_followup_id = $followup[$clsFollowUp->pkey];
					$color = $clsFollowUp->getBackground($crm_followup_id, $followup);
					//$Potential = $clsPotential->getPotential($resource_id,false);
					// List
					$props = sprintf('customer_id="%s" crm_followup_id="%s" is_view="1"', $customer_id, $crm_followup_id);
					$_htmlList .= '<a class="event aj_open-followup" id="tooltip_' . $crm_followup_id . '" ' . $props . ' 
					style="background:' . $color . '"></a>';
					// Table
					$_htmlTable .= '<tr class="trFollowUpCalendar" ' . $props . '>
						<td class="aj_open-followup" ' . $props . '>
							<a class="event pull-left" style="background:' . $color . '; margin:3px 3px 0 0;"></a> 
							<span class="event-lead-time">' . $clsISO->convertTimeToText($followup['date_id']) . '</span>
							<span class="event-lead-link"><i class="fa fa-star"></i> ' . $Potential . '</span>
							<b>' . strip_tags(html_entity_decode($followup['content'])) . '<b>
						</td>
						<td class="text-center" width="15%">
							<div class="btn-group btn-group-xs ui-btn-group-custom" style="vertical-align:-5px;">
								<button class="btn aj_open-followup-reschedue" ' . $props . '><i class="fa fa-clock-o"></i></button>
								<button class="btn btn-danger" ' . $props . '>' . $clsISO->makeIcon('bx-trash') . '</button>
							</div>
						</td>
					</tr>';
				}
				$_htmlTable .= '</table>';
				unset($lstFollowUp);
			}
			$results[] = array(
				'start' => date('Y-m-d H:i:s', $i),
				'title' => "Danh sách Follow-Up(s) {$date_id}",
				'number' => $numberFollowUp,
				'htmlList' => $_htmlList,
				'htmlTable' => $_htmlTable
			);
		}
	}
	// output
	echo @json_encode($results);
	die();
}
function default_set_view_desktop_followup(){
	global $smarty, $profile_id, $oneProfile, $core, $dbconn, $clsISO;
	$clsProfile = new Profile();
	$more_information = $oneProfile['more_information'];
	#
	$tp = Input::post('tp', '_plan');
	$more_information['desktop_followup_view'] = $tp;
	#
	$msg = "_error";
	if ($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$msg = "_success";
	}
	// Return
	echo $msg;
	die();
}
function default_load_desktop_followups(){
	global $deviceType, $smarty, $profile_id, $oneProfile, $core, $dbconn, $clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	##
	$tp = Input::post('tp', 'today');
	$keysearch =  Input::post('keysearch', '');
	$sort_by =  Input::post('sort_by', 'date_id');
	$sort_type =  Input::post('sort_type', 'desc');
	$more_information = $oneProfile['more_information'];
	$desktop_followup_view = $core->get_field($more_information, "desktop_followup_view", "_plan");
	#- Cond
	$cond = "`followup_type`='_crm' AND `is_trash`=0 AND `admin_id`='{$profile_id}' 
		AND `type_id` in ('" . implode('\',\'', array(_FOLLOWUP_CALL_ID, _FOLLOWUP_TASK_ID, _FOLLOWUP_ZALO_ID)) . "')";
	if ($desktop_followup_view == "_plan") {
		// $cond.= " AND `status_id`='"._FOLLOWUP_STATUS_PLAN_ID."'";
	}
	if ($tp == 'tomorrow') {
		$date_id  = strtotime("+1 day");
		$cond .= " AND FROM_UNIXTIME(`date_id`,'%d/%m/%Y')='" . date('d/m/Y', $date_id) . "'";
	} else if ($tp == 'next_10_days') {
		$start_date = strtotime("+1 day");
		$end_date = strtotime("+10 days", $start_date);
		$cond .= " AND (`date_id` BETWEEN {$start_date} AND {$end_date})";
	} else {
		$date_id = time();
		$cond .= " AND FROM_UNIXTIME(`date_id`,'%d/%m/%Y')='" . date('d/m/Y', $date_id) . "'";
	}
	$html = '';
	$total_record = 0; // $dbconn->debug = true;
	$list_followups = $clsFollowUp->getAll($cond . " order by `date_id` DESC");
	if (!empty($list_followups)) {
		$ii = 1;
		$total_record = count($list_followups);
		$html .= '<table class="table table-no-border-end table-middle" width="100%">';
		$arr_property_cached = array();
		foreach ($list_followups as $followup) {
			$date_id = $followup['date_id'];
			$type_id = $followup['type_id'];
			$status_id = $followup['status_id'];
			$customer_id = $followup['customer_id'];
			$oneCustomer = $clsCustomer->getOne($customer_id, "phone");
			$followup_id = $followup[$clsFollowUp->pkey];
			$props = 'customer_id="' . $customer_id . '" followup_id="' . $followup_id . '"';
			if (isset($arr_property_cached[$type_id])) {
				$oneProperty = $arr_property_cached[$type_id];
			} else {
				$field = "{$clsProperty->pkey},bgcolor,textcolor,image";
				$oneProperty = $clsProperty->getOne($type_id, $field);
				$arr_property_cached[$type_id] = $oneProperty;
			}
			$link = "";
			if ($type_id == _FOLLOWUP_CALL_ID && !empty($oneCustomer['phone'])) {
				$link = "tel:" . $oneCustomer['phone'];
			} else if ($type == _FOLLOWUP_ZALO_ID && !empty($oneCustomer['phone'])) {
				$link = "https://zalo.me/" . $oneCustomer['phone'];
			} else {
				$link = "javascript:void(0)";
			}
			$html .= '<tr' . ($status_id == _FOLLOWUP_STATUS_DONE_ID ? ' class="tr-done nohover"' : '') . '>
				<td width="40px" class="text-center">
					<a href="' . $link . '" style="background:' . $oneProperty['bgcolor'] . '; color:' . $oneProperty['textcolor'] . '" class="d-block activity-icon mt-1 rounded-circle text-center border-0 shadow-none">
						<i class="bx ' . $oneProperty['image'] . ' fs-20 m-2"></i>
					</a>
				</td>
				' . ($deviceType == 'phone' ? '<td class="text-left">
					<div class="mb-n0"><a href="javascript:void(0)" class="font-bold  link goLink view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/' . $customer_id . '/overview" customer_id="' . $customer_id . '">' . $clsCustomer->getName($customer_id) . '</a></div>
					<div class="line-clamp-2 cursor-pointer" onclick="$Core.crm.view_activity(this, event);" customer_id="' . $customer_id . '">' . $followup['intro'] . '</div>
					<span class="text-' . ($date_id > time() ? 'main' : 'muted') . ' text-nowrap fs-12">
						<i class="material-icons-outlined">notifications_active</i>
						' . $clsISO->convertTimeToText($followup['date_id'], true) . '
					</span>
				</td>' : '<td class="text-left" style="width:25%">
					<div class="mb-n1"><a href="javascript:void(0)" class="font-bold  link goLink view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/' . $customer_id . '/overview" customer_id="' . $customer_id . '">' . $clsCustomer->getName($customer_id) . '</a></div>
					<span class="text-' . ($date_id > time() ? 'main' : 'muted') . ' text-nowrap fs-12">
						<i class="material-icons-outlined">notifications_active</i>
						' . $clsISO->convertTimeToText($followup['date_id'], true) . '
					</span>
				</td>
				<td class="text-left cursor-pointer" onclick="$Core.crm.view_activity(this, event);" customer_id="' . $customer_id . '">
					<div class="line-clamp-2">' . $followup['intro'] . '</div>
					' . (!empty($followup['_result']) ? '--- <br /> <strong>KQ:</strong> ' . $followup['_result'] : '') . '
				</td>') . '
				<td width="30px" class="text-center">
					<div class="btn-group">
						<a href="javascript:void(0);" title="Hoàn thành" tp="follow-ups" type_id="' . $type_id . '" class="btn btn-icon btn-sm btn-outline-default' . ($status_id == _FOLLOWUP_STATUS_DONE_ID ? ' disabled' : '') . '" onclick="$Core.crm.done_followup(this, event);" followup_id="' . $followup_id . '" customer_id="' . $customer_id . '"><i class="bx bx-check"></i></a>
						<button title="Follow-ups" onclick="$Core.crm.view_activity(this, event);" customer_id="' . $customer_id . '" class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-bell"></i></button>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		$html .= '</table>';
	} else {
		$html .= '<div class="py-3 text-center">
			<div class="py-2">
				' . CRM::renderHTMLNoDocument('Rất tiếc <br /> Bạn chưa có bất kỳ lịch làm việc với khách hàng vào 
				<strong class="text-main">' . ($tp == 'today' ? 'hôm nay' : ($tp == 'tomorrow' ? 'ngày mai' : '10 ngày tiếp theo'))) . '</strong>
			</div>
		</div>';
	}
	// output
	echo @json_encode(array(
		'cond' => $cond,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'html'	=> $html
	));
	die();
}
function default_load_followups(){
	global $smarty, $assign_list, $profile_id, $oneProfile, $core, $dbconn, $clsISO;
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$html = '';
	$holderG = Input::post('holderG', '_all'); // desktop, _search,
	$typeHolder = Input::post('typeHolder', '_all');
	$customer_id = (int) Input::post('customer_id', 0);
	$sort_by =  Input::post('sort_by', 'date_id');
	$sort_type =  Input::post('sort_type', 'desc');
	$keySearch =  Input::post('keySearch', '');
	// Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 5);
	$cond = "`is_trash`=0 and `customer_id`='{$customer_id}'";
	if (!empty($keySearch)) {
		$cond .= " and `customer_id` IN (
			select customer_id from " . $clsCustomer->tbl . " 
			where name like '%" . $keySearch . "%' 
				or name_slug like '%" . $core->replaceSpace($keySearch) . "%' 
				or email like '%" . $keySearch . "%' 
				or phone like '%" . $keySearch . "%' 
				or address like '%" . $keySearch . "%'
			)";
	}
	$total_record = $clsFollowUp->countItem($cond);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$list_followups = $clsFollowUp->getAll($cond . " order by {$sort_by} {$sort_type}" . $limitCond);
	// $clsISO->print_pre($list_followups); die();
	if (!empty($list_followups)) {
		$ii = 1;
		foreach ($list_followups as $followup) {
			$customer_id = $followup['customer_id'];
			$followup_id = $followup[$clsFollowUp->pkey];
			$props = 'customer_id="' . $customer_id . '" followup_id="' . $followup_id . '"';
			$htmlAction = '<div class="dropdown">
				<button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
					<i class="bx bx-dots-vertical-rounded" aria-hidden="true"></i>
				</button>
				<div class="dropdown-menu">
					<a href="javascript:;" class="dropdown-item" onClick="$Core.crm.open_followups(this, event)" ' . $props . '>' . $core->get_Lang('Edit') . '</a>
					<a href="javascript:;" class="dropdown-item" onClick="$Core.crm.delete_followups(this, event)" ' . $props . '>' . $core->get_Lang('Delete') . '</a>
				</div>
			</div>';
			$html .= '<tr>
				<td class="text-center">' . ($ii) . '</td>
				<td class="text-left">
					<i style="transform: translateY(5px);" class="material-icons-outlined mr-1">more_time</i> 
					' . $clsISO->convertTimeToText($followup['date_id'], true) . '
				</td>
				<td class="text-left">' . $clsFollowUp->getHTMLType($crm_followup_id, $followup) . '</td>
				<td class="text-left">' . $clsProfile->getFullName($followup['admin_id']) . '</td>
				<td class="text-left">' . $followup['intro'] . '</td>
				<td class="text-center">' . $htmlAction . '</td>
			</tr>';
			++$ii;
		}
	} else {
		$html .= '<tr>
			<td class="text-center" colspan="7">
				' . CRM::renderHTMLNoDocument('Not any foolow-Ups') . '
			</td>
		</tr>';
	}
	// output
	echo @json_encode(array(
		'cond' => $cond,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'html'	=> $html
	));
	die();
}
function default_open_file(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO, $dbconn;
	$clsCustomer = new Customer();
	$uid = $clsISO->getUniqid();
	$file_id = Input::post('file_id');
	$customer_id = Input::post('customer_id', 0);
	##
	$action = '_add';
	$oneFile = array();
	if (!empty($file_id)) {
		$action = '_edit';
		$files = $clsCustomer->getOneField('files', $customer_id);
		$files = !empty($files)
			? json_decode(html_entity_decode($files), true) : array();
		$oneFile = $files[$file_id];
		// $clsISO->print_pre($oneFile);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('file_id', $file_id);
	$smarty->assign('oneFile', $oneFile);
	$smarty->assign('customer_id', $customer_id);
	// Return
	$html = $core->build('_ajax.open_file.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_save_file(){
	global $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $oneSetting, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	##
	$msg = '_error';
	$file_id = Input::post('file_id', "");
	$customer_id = (int) Input::post('customer_id', 0);
	$files = $clsCustomer->getOneField('files', $customer_id);
	$files = $clsISO->to_array_json($files);
	if (isset($_POST['hid']) && $_POST['hid'] == 'upload') {
		$attachments = array();
		if (!empty($_FILES['attachments']['name'])) {
			for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if (!is_uploaded_file($file['name'])) {
					$clsUploadFile = new UploadFile(); // attachments
					$up = $clsUploadFile->uploadItem($file, '/ex', "pdf,doc,docx,xls,xlsx,csv,txt,zip,jpg,jpeg,png,gif");
					if (!empty($up) && file_exists(ABSPATH . $up)) {
						$attachments[] = $up;
					}
				}
			}
		}
		if (!empty($file_id)) {
			$files[$file_id]['description'] = Input::post('description');
			$files[$file_id]['group_id'] = Input::post('group_id', 0);
			if (!empty($attachments) && file_exists(ABSPATH . $attachment)) {
				$files[$file_id]['attachments'] = $attachments;
				$files[$file_id]['file_name'] = $file_name;
				$files[$file_id]['file_size'] = $file_size;
			}
			$files[$file_id]['user_id_update'] = $profile_id;
			$files[$file_id]['upd_date'] = time();
		} else {
			$files[$clsISO->getUniqid()] = array(
				'description' => Input::post('description'),
				'group_id' => Input::post('group_id', 0),
				'attachments' => $attachments,
				'file_name'	=> $file_name,
				'file_size'	=> $file_size,
				'user_id'	=> $profile_id,
				'user_id_update'	=> $profile_id,
				'reg_date'	=> time(),
				'upd_date' => time()
			);
		}
		if ($clsCustomer->updateOne($customer_id, array(
			'files'	=> json_encode($files, JSON_UNESCAPED_UNICODE)
		))) {
			$msg = '_success';
		}
	}
	// output
	echo ($msg);
	die();
}
function default_delete_file(){
	global $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $oneSetting, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	##
	$msg = '_error';
	$file_id = Input::post('file_id', "");
	$customer_id = (int) Input::post('customer_id', 0);
	$files = $clsCustomer->getOneField('files', $customer_id);
	$files = !empty($files) ? @json_decode(html_entity_decode($files), true) : array();
	##
	if (!empty($file_id) && !empty($files) && @array_key_exists($file_id, $files)) {
		$oneFile = $files[$file_id];
		unset($files[$file_id]); //Found
		if ($clsCustomer->updateOne($customer_id, array(
			'files'	=> json_encode($files, JSON_UNESCAPED_UNICODE)
		))) {
			$msg = '_success';
			@unlink(ABSPATH . $oneFile['attachment']);
		}
	}
	// Return
	echo ($msg);
	die();
}
function default_load_list_billing(){
	global $smarty, $core, $clsISO, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$clsProject = new Project();
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBilling', $clsBilling);
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$customer_id = Input::post('customer_id', 0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	#- Pagination
	$more_information_cus = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information_cus = $clsISO->to_array_json($more_information_cus);
	$list_billings = !empty($more_information_cus['list_billings'])
		? $more_information_cus['list_billings'] : array();
	$list_billings = array_reverse($list_billings);
	if (!empty($list_billings)) {
		$arr_property_cached = $arr_projects_cached = array();
		foreach ($list_billings as $key => $val) {
			$project_id = $val['project_id'];
			$billing_type = $val['billing_type'];
			if ($billing_type > 0) {
				if (isset($arr_property_cached[$billing_type])) {
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				} else {
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				}
			} else {
				$list_billings[$key]['billing_type'] = "";
			}
			###
			if ($project_id > 0) {
				if (isset($arr_projects_cached[$project_id])) {
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				} else {
					$arr_projects_cached[$project_id] = $clsProject->getTitle($project_id);
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				}
			} else {
				$list_billings[$key]['poroject_name'] = "";
			}
			$list_billings[$key]['billing_id'] = $key;
		}
	}
	// $clsISO->print_pre($list_billings); die();
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	// Return
	$html = $core->build('_ajax.billing.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'page' => $current_page,
		'per_page' => $per_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	));
	die();
}
function crm_enrich_followups($customer_id)
{
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$customer_id = (int) $customer_id;
	$list_followups = $clsFollowUp->getAll("`is_trash`=0 and `customer_id`='{$customer_id}' order by `reg_date` DESC");
	if (empty($list_followups)) {
		return array();
	}
	$arr_property_cached = $arr_profile_cached = $arr_name_cached = array();
	foreach ($list_followups as $key => $val) {
		$type_id = $val['type_id'];
		$admin_id = $val['admin_id'];
		$followup_id = $val[$clsFollowUp->pkey];
		if (!isset($arr_property_cached[$type_id])) {
			$arr_property_cached[$type_id] = $clsProperty->getOne($type_id, "title,image,bgcolor,textcolor");
		}
		$list_followups[$key]['oneProperty'] = $arr_property_cached[$type_id];
		if (!isset($arr_profile_cached[$admin_id])) {
			$arr_profile_cached[$admin_id] = $clsProfile->getAvatar($admin_id, array(), 30, 30);
		}
		$list_followups[$key]['avatar'] = $arr_profile_cached[$admin_id];
		if (!isset($arr_name_cached[$admin_id])) { $arr_name_cached[$admin_id] = $clsProfile->getFullName($admin_id); }
		$list_followups[$key]['admin_name'] = $arr_name_cached[$admin_id];
		$list_reply = $clsFollowUp->getAll("`parent_id`='{$followup_id}' order by `reg_date` ASC");
		if (!empty($list_reply)) {
			foreach ($list_reply as $okey => $oval) {
				$user_id = $oval['user_id'];
				if (!isset($arr_profile_cached[$user_id])) {
					$arr_profile_cached[$user_id] = $clsProfile->getAvatar($user_id, array(), 30, 30);
				}
				$list_reply[$okey]['avatar'] = $arr_profile_cached[$user_id];
			}
		}
		$list_followups[$key]['list_reply'] = $list_reply;
	}
	return $list_followups;
}
function default_view_activity(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	#
	$customer_id = (int) Input::post('customer_id', 0);
	$clsCustomer->blockIfReceivePending($customer_id);
	$field = "{$clsProperty->pkey},title,image";
	$list_activity = $clsProperty->getAllCache("`is_trash`=0 and `parent_id`='0'
	and `property_type`='FOLLOWUP_TYPE' order by `order_no` ASC", $field);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('list_activity', $list_activity);
	#- Total activity
	$total_activity = $clsFollowUp->countItem("customer_id='{$customer_id}'");
	$smarty->assign('total_activity', $total_activity);
	#
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$smarty->assign('oneCustomer', $oneCustomer);
	// Người đang quản lý khách (admin_id): avatar + tên
	$clsProfile = new Profile();
	$_owner_aid = (int)$oneCustomer['admin_id'];
	$smarty->assign('owner_aid', $_owner_aid);
	$smarty->assign('owner_name', $_owner_aid > 0 ? $clsProfile->getFullName($_owner_aid) : '');
	$smarty->assign('owner_avatar', $_owner_aid > 0 ? $clsProfile->getAvatar($_owner_aid, array(), 40, 40) : '');
	$_owner_dept = '';
	if ($_owner_aid > 0) {
		$_owner_prof = $clsProfile->getOne($_owner_aid, "department_id,more_information");
		$_owner_mi = (!empty($_owner_prof['more_information'])) ? json_decode(html_entity_decode($_owner_prof['more_information']), true) : array();
		if (!is_array($_owner_mi)) { $_owner_mi = array(); }
		if (!empty($_owner_mi['department_name'])) { $_owner_dept = $_owner_mi['department_name']; }
		elseif (!empty($_owner_prof['department_id'])) { $_owner_dept = $clsProperty->getTitle($_owner_prof['department_id']); }
	}
	$smarty->assign('owner_dept', $_owner_dept);
	$arr_share_ids = $clsCustomer->getShareIds($customer_id, $oneCustomer);
	#- Permiss
	$permiss_action = $permiss_notes = 0;
	if ($oneCustomer['admin_id'] == $profile_id || $clsCustomer->isFullPermiss()) {
		$permiss_action = 1;
	}
	if (in_array($profile_id, $arr_share_ids) || $clsCustomer->isFullPermiss()) {
		$permiss_notes = 1;
	}
	// permiss_assign: chủ / NGƯỜI TẠO (user_id) / super / quyền admin_assign_client — CHỈ để GÁN (đổi phụ trách / thêm người liên quan), KHÔNG mở full-edit
	$permiss_assign = 0;
	if ($oneCustomer['admin_id'] == $profile_id || $oneCustomer['user_id'] == $profile_id || $clsCustomer->isFullPermiss() || $clsISO->checkPermission('admin_assign_client')) {
		$permiss_assign = 1;
	}
	$smarty->assign('permiss_assign', $permiss_assign);
	$smarty->assign('permiss_notes', $permiss_notes);
	$smarty->assign('permiss_action', $permiss_action);
	#- Total Notes
	$notes = $oneCustomer['notes'];
	$list_notes = !empty($notes) ? json_decode(html_entity_decode($notes), true) : array();
	$total_notes = !empty($list_notes) ? count($list_notes) : 0;
	$smarty->assign('total_notes', $total_notes);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('template_type', '_modal');
	# Header giàu + 4 thẻ (Nhu cầu/Dự án/Chiến dịch/Tương tác cuối) — tính cho 1 khách, cùng idiom với thẻ cmc ở list (KHÔNG đụng vòng lặp list để khỏi regress màn chính).
	$clsCustomerMeta = new CustomerMeta();
	$clsCampaign = new Campaign();
	$clsSetting = new Setting();
	$_mi_av = $clsISO->to_array_json($oneCustomer['more_information']);
	$card_source = ((int) $oneCustomer['resource_id'] > 0) ? $clsProperty->getTitle((int) $oneCustomer['resource_id']) : '';
	$card_regdate = (!empty($oneCustomer['reg_date'])) ? date('d/m/Y', (int) $oneCustomer['reg_date']) : '';
	$card_last = (!empty($oneCustomer['upd_date'])) ? $clsISO->getTimeAgo((int) $oneCustomer['upd_date']) : '';
	$_need_parts = array();
	$_mp = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'purpose');
	$_mb = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'bedroom');
	if (!empty($_mp)) { $_t = trim($clsProperty->getTitleArray($_mp, false)); if ($_t !== '') { $_need_parts[] = $_t; } }
	if (!empty($_mb)) { $_t = trim($clsProperty->getTitleArray($_mb, false)); if ($_t !== '') { $_need_parts[] = $_t; } }
	$card_need = implode(' ', $_need_parts);
	$begin_need = $oneCustomer["begin_need"];
	$_proj = array();
	$_mblk = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'block');
	if (!empty($_mblk)) { foreach ($_mblk as $_bid) { $_bt = trim((string) $clsSetting->getTitle($_bid)); if ($_bt !== '') { $_proj[] = $_bt; } } }
	$card_project = implode(', ', $_proj);
	$_camp = array();
	$_mcp = $clsCustomer->getCampaignIds($customer_id, $oneCustomer);
	if (!empty($_mcp)) { foreach ($_mcp as $_cid) { $_ct = trim((string) $clsCampaign->getTitle($_cid)); if ($_ct !== '') { $_camp[] = $_ct; } } }
	$card_campaign = implode(', ', $_camp);
	$_fbUrl = '';
	if (is_array($_mi_av) && !empty($_mi_av['facebook'])) {
		$_fbRaw = trim($_mi_av['facebook']);
		$_fbUrl = (stripos($_fbRaw, 'http') === 0) ? $_fbRaw : 'https://www.facebook.com/' . ltrim($_fbRaw, '/@');
	}
	// Bước tiếp theo (V1): tác nghiệp kế + thời điểm hẹn (1 khách → getTitle 1 lần, không N+1).
	$_tn_av = (int) $oneCustomer['task_next_id'];
	$card_next_task = ($_tn_av > 0) ? trim((string) $clsSetting->getTitle($_tn_av)) : '';
	$_tr_av = (int) $oneCustomer['time_receipt'];
	$card_next_time = ''; $card_next_overdue = 0;
	if ($_tn_av > 0 && $_tr_av > 0) {
		$_now_av = time();
		$card_next_overdue = ($_tr_av <= $_now_av) ? 1 : 0;
		$_hm_av = date('H:i', $_tr_av);
		$_dd_av = (int) round((strtotime(date('Y-m-d', $_tr_av)) - strtotime(date('Y-m-d', $_now_av))) / 86400);
		if ($_dd_av === 0) { $card_next_time = $_hm_av . ' hôm nay'; }
		else if ($_dd_av === 1) { $card_next_time = $_hm_av . ' ngày mai'; }
		else if ($_dd_av === -1) { $card_next_time = $_hm_av . ' hôm qua'; }
		else { $card_next_time = $_hm_av . ' ' . date('d/m', $_tr_av); }
	}
	$smarty->assign('begin_need', $begin_need);
	$smarty->assign('card_source', $card_source);
	$smarty->assign('card_regdate', $card_regdate);
	$smarty->assign('card_last', $card_last);
	$smarty->assign('card_need', $card_need);
	$smarty->assign('card_project', $card_project);
	$smarty->assign('card_campaign', $card_campaign);
	// Badge "Lần N" — số lần gọi/nhắn (counter ladder); hiện khi tác nghiệp tiếp lặp lại task hiện tại.
	$_cur_av = (int) $oneCustomer['task_current_id'];
	$card_attempt_badge = ($_tn_av === $_cur_av) ? crm_task_attempt_badge(crm_task_attempt_count($_mi_av, $_cur_av)) : '';
	$smarty->assign('card_next_task', $card_next_task);
	$smarty->assign('card_next_time', $card_next_time);
	$smarty->assign('card_next_overdue', $card_next_overdue);
	$smarty->assign('card_attempt_badge', $card_attempt_badge);
	// Nút "Ghi kết quả" trong drawer (giống mobile): cho phép chọn kết quả/kết thúc chăm sóc khi mở khách.
	$smarty->assign('card_task_current_id', ($_cur_av > 0) ? $_cur_av : 0);
	$smarty->assign('fb_url', $_fbUrl);
	$html = $core->build('_ajax.activity.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_load_consulting(){
	global $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsStock = new Stock();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$clsCustomerMeta = new CustomerMeta();
	$list_stocks = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'stock');
	$html = '<div class="table-container no-shadow text-nowrap">
	<table cellpadding="0" cellspacing="0" width="100%" class="table ">
		<thead><tr>
			<th class="align-center bg-lighter">Mã căn</th>
			<th class="align-center bg-lighter">Diện tích</th>
			<th class="align-center bg-lighter">Giá</th>
		</tr></thead>';
	if (!empty($list_stocks)) {
		foreach ($list_stocks as $stock_id) {
			$field = "ms_code,more_information";
			$oneStock = $clsStock->getOne($stock_id);
			$ms_code = $oneStock['ms_code'];
			$more_information = $oneStock['more_information'];
			$more_information = !empty($more_information)
				? json_decode(html_entity_decode($more_information), true) : array();
			// $clsISO->print_pre($more_information); die();
			$total_price_vat = 0;
			if (isset($more_information['total_price_vat']) && !empty($more_information['total_price_vat'])) {
				$total_price_vat = $more_information['total_price_vat'];
				$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
				$total_price_vat = number_format((float) $clsISO->priceFormat($total_price_vat), 3, '.', '');
			}
			$html .= '<tr>
				<td><a href="/my-favourite/' . $ms_code . '" target="_blank">
					<i class="bx bx-link-external"></i> ' . $ms_code . '</a>
				</td>
				<td>' . $more_information['DT_TT'] . '</td>
				<td>' . $total_price_vat . '</td>
			</tr>';
		}
	} else {
		$html .= '<tr><td colspan="3">
			<div class="p-2">
				' . CRM::renderHTMLNoDocument('Chưa có tư vấn căn nào') . '
			</div>
		</td></tr>';
	}
	$html .= '</table>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	));
	die();
}
function default_load_logs(){
	global $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	##
	$customer_id = (int) Input::post('customer_id', 0);
	$oCustomer = $clsCustomer->getOne($customer_id, "more_information");
	$more_information = $oCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$action_logs = $core->get_field($more_information, "action_logs", []);
	// $clsISO->print_pre($action_logs); die();
	if (!empty($action_logs)) {
		$html = '<ul class="logs">';
		foreach ($action_logs as $key => $val) {
			$html .= '<li>' . $clsISO->convertTimeToText($val['reg_date'], true) . ': ' . $val['content'] . '</li>';
		}
		$html .= '</ul>';
	} else {
		$html .= '<div class="d-flex flex-column py-3 align-items-center justify-content-center">
			<img src="' . URL_IMAGES . '/empty.svg" class="w-px-100 mb-2" />
			<p class="text-muted">Chưa có bất kỳ thao tác nào được ghi nhận</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	));
	die();
}
function default_load_activity(){
	global $smarty, $profile_id, $core, $clsISO;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsFollowUp', $clsFollowUp);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$clsCustomer->blockIfReceivePending($customer_id, 'plain');
	$smarty->assign('customer_id', $customer_id);
	$list_followups = $clsFollowUp->getAll("`is_trash`=0 and `customer_id`='{$customer_id}' order by `reg_date` DESC");
	if (!empty($list_followups)) {
		$arr_property_cached = $arr_profile_cached = $arr_name_cached = array();
		foreach ($list_followups as $key => $val) {
			$type_id = $val['type_id'];
			$admin_id  = $val['admin_id'];
			$followup_id  = $val[$clsFollowUp->pkey];
			if (isset($arr_property_cached[$type_id])) {
				$oneProperty = $arr_property_cached[$type_id];
			} else {
				$oneProperty = $clsProperty->getOne($type_id, "title,image,bgcolor,textcolor");
				$arr_property_cached[$type_id] = $oneProperty;
			}
			$list_followups[$key]['oneProperty'] = $oneProperty;
			if (!isset($arr_profile_cached[$admin_id])) {
				$arr_profile_cached[$admin_id] = $clsProfile->getAvatar($admin_id, array(), 30, 30);
			}
			$list_followups[$key]['avatar'] = $arr_profile_cached[$admin_id];
			if (!isset($arr_name_cached[$admin_id])) { $arr_name_cached[$admin_id] = $clsProfile->getFullName($admin_id); }
			$list_followups[$key]['admin_name'] = $arr_name_cached[$admin_id];
			$list_reply = $clsFollowUp->getAll("`parent_id`='{$followup_id}' order by `reg_date` ASC");
			if (!empty($list_reply)) {
				foreach ($list_reply as $okey => $oval) {
					$user_id  = $oval['user_id'];
					if (!isset($arr_profile_cached[$user_id])) {
						$arr_profile_cached[$user_id] = $clsProfile->getAvatar($user_id, array(), 30, 30);
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
	#- Permiss
	$permiss_action = 0;
	if ($clsCustomer->isFullPermiss() || $clsCustomer->getOneField('admin_id', $customer_id) == $profile_id) {
		$permiss_action = 1;
	}
	$smarty->assign('permiss_action', $permiss_action);
	// Return
	$smarty->assign('template_type', '_list');
	$uid = $clsISO->getUniqid();
	$html = $core->build('_ajax.activity.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_open_activity(){
	global $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	##
	$tp = Input::post('tp', 'follow-ups');
	$customer_id = (int) Input::post('customer_id', 0);
	$clsCustomer->blockIfReceivePending($customer_id, 'open');
	$type_id = (int) Input::post('type_id', 0);
	$type_id = ($type_id > 0) ? $type_id : _FOLLOWUP_TASK_ID;
	$oCustomer = $clsCustomer->getOne($customer_id);
	if ($tp == 'notes') {
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
		$props = array(
			'tp' => $tp,
			'followup_id' => $followup_id,
			'customer_id' => $customer_id
		);
		$list_purpose_arr = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'purpose');
		$oCustomer['list_purpose_arr'] = $list_purpose_arr;
		// F4/F7 — Mẫu nội dung: kịch bản gọi (_CRM_SCRIPT) + mẫu tin Zalo (_CRM_ZALO_TEMPLATE)
		$clsSetting = new Setting();
		$_cusName = $clsCustomer->getName($customer_id);
		$arr_templates = array();
		$_tpl_groups = array(
			'_CRM_SCRIPT'			=> 'Kịch bản gọi điện',
			'_CRM_ZALO_TEMPLATE'	=> 'Mẫu tin Zalo'
		);
		foreach ($_tpl_groups as $_tplType => $_tplLabel) {
			$_rows = $clsSetting->getCacheItems($_tplType);
			$_items = array();
			if (!empty($_rows) && is_array($_rows)) {
				foreach ($_rows as $_r) {
					$_mi = $clsISO->to_array_json($_r['more_information']);
					$_intro = isset($_mi['intro']) ? trim($_mi['intro']) : '';
					if ($_intro === '') {
						continue;
					}
					$_intro = str_replace(array('{ten}', '{Ten}', '{name}', '{NAME}'), $_cusName, $_intro);
					$_items[] = array(
						'id'	=> $_r['setting_id'],
						'title'	=> $_r['title'],
						'intro'	=> $_intro
					);
				}
			}
			if (!empty($_items)) {
				$arr_templates[] = array(
					'label'	=> $_tplLabel,
					'items'	=> $_items
				);
			}
		}
		$smarty->assign('arr_templates', $arr_templates);
		// "Bước tiếp theo" (V1): dropdown đồng bộ catalog tác nghiệp _CRM_TASK (trừ Lead mới / Kết thúc / Chuyển GD + đã trash).
		$_excl_task = array();
		if (defined('_CRM_TASK_LEAD_ID')) { $_excl_task[] = (int) _CRM_TASK_LEAD_ID; }
		if (defined('_CRM_TASK_END_ID')) { $_excl_task[] = (int) _CRM_TASK_END_ID; }
		if (defined('_CRM_TASK_DEAL_ID')) { $_excl_task[] = (int) _CRM_TASK_DEAL_ID; }
		$_excl_sql = !empty($_excl_task) ? " and `setting_id` not in (" . implode(',', $_excl_task) . ")" : "";
		$_crm_tasks = $clsSetting->getAll("`is_trash`=0 and `_type`='_CRM_TASK'" . $_excl_sql . " order by `order_no` asc", "`setting_id`,`title`");
		$_crm_task_options = '<option value="0">— Chọn —</option>';
		if (!empty($_crm_tasks)) {
			foreach ($_crm_tasks as $_ct) {
				$_crm_task_options .= '<option value="' . (int) $_ct['setting_id'] . '">' . htmlspecialchars((string) $_ct['title'], ENT_QUOTES) . '</option>';
			}
		}
		$smarty->assign('crm_task_next_options', $_crm_task_options);
	}
	$smarty->assign('tp', $tp);
	$smarty->assign('type_id', $type_id);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('props', $clsISO->make_attrs_builder($props));
	##
	$time_def_id = $titlePage = "";
	if ($tp == 'notes') {
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
		if ($followup_id > 0) {
			$action = "_edit";
			$oneItem = $clsFollowUp->getOne($followup_id);
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
		$list_times['+1 day'] = 'Sau 1 ngày';
		$list_times['+2 days'] = 'Sau 2 ngày';
		$list_times['+7 days'] = 'Sau 7 ngày';
		$list_times['+15 days'] = 'Sau 15 ngày';
		$list_times['+30 days'] = 'Sau 30 ngày';
		$smarty->assign('list_times', $list_times);
		// Nếu không có FU nào thì mặc định time_def_id = 1day;
		if ($clsFollowUp->countItem("customer_id='{$customer_id}'") == 0) {
			$time_def_id = '1day';
			$oneItem['date_id'] = strtotime('+1 day');
			$oneItem['reminder_time'] = strtotime('+1 day');
		}
	}
	$smarty->assign('action', $action);
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
	));
	die();
}
function default_open_reply(){
	global $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsFollowUp = new FollowUp();
	$clsFcmToken = new FcmToken();
	$uid = $clsISO->getUniqid();
	$parent_id = (int) Input::post('parent_id', 0);
	$followup_id = (int) Input::post('followup_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	$smarty->assign('parent_id', $parent_id);
	$smarty->assign('followup_id', $followup_id);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('clsFollowUp', $clsFollowUp);
	// Return
	$html = $core->build('_ajax.reply.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_save_reply(){
	global $oSmarty, $smarty, $assign_list, $profile_id, $core, $clsISO;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$clsFcmToken = new FcmToken();
	###
	$msg = "_error";
	$time = time();
	$parent_id = (int) Input::post('parent_id', 0);
	$followup_id = (int) Input::post('followup_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	$content = Input::post('content');
	#- Follow-up Parents
	$oneFollowUp = $clsFollowUp->getOne($parent_id);
	if ($followup_id > 0) {
		if ($clsFollowUp->updateOne($followup_id, array(
			'intro' => $content,
			'upd_date' => $time,
			'user_id_update' => $profile_id
		))) {
			$msg = "_success";
			$more_information = $clsCustomer->getOneField("more_information", $customer_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			$action_logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(),
				'user_id' => $profile_id,
				'content' => sprintf("<strong>%s</strong> đã cập nhật phản hồi <strong>%s</strong> lại với nội dung <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oneFollowUp['intro'], $content)
			);
			$more_information['action_logs'] = $action_logs;
			$clsCustomer->updateOne($customer_id, array(
				'upd_date' => time(),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	} else {
		if ($clsFollowUp->insert(array(
			'customer_id' => 0,
			'parent_id' => $parent_id,
			'intro' => $content,
			'reg_date' => $time,
			'upd_date' => $time,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))) {
			$msg = "_success";
			$user_id = $oneFollowUp['user_id']; // Người tạo Follow-up
			#- Logs
			$more_information = $clsCustomer->getOneField("more_information", $customer_id);
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs = $core->get_field($more_information, "action_logs", []);
			$action_logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(),
				'user_id' => $profile_id,
				'content' => sprintf("<strong>%s</strong> đã thêm mơi phản hồi <strong>%s</strong> lại với nội dung <strong>%s</strong>", $clsProfile->getFullName($profile_id, $oneProfile), $oneFollowUp['intro'], $content)
			);
			$more_information['action_logs'] = $action_logs;
			$clsCustomer->updateOne($customer_id, array(
				'upd_date' => time(),
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			$clsNotification = new Notification();
			// Người reply Follow-up không phải là người tạo.
			if ($profile_id != $oneFollowUp['user_id']) {
				$contentNotify = sprintf(
					'<strong>%s</strong> đã bình luận vào hoạt động của bạn <strong>%s</strong> 
					với nội dung <strong>%s</strong> vào lúc <i>%s</i>',
					$clsProfile->getFullName($profile_id, $oneProfile),
					$clsFollowUp->getContent($parent_id, $oneFollowUp),
					$content,
					$clsISO->convertTimeToText($time, true)
				);
				$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $parent_id, $contentNotify, time(), '|' . $oneFollowUp['user_id'] . '|');
				$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
					and `user_id`='{$user_id}' and `token`<>''", "token");
				#thong bao app
				$params = [
					'title' => "CRM - Hoạt động khách hàng",
					'body' => strip_tags($contentNotify),
					'link' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $customer_id)
				];
				$clsNotification->doPushMessagingUser($params, [$oneFollowUp['user_id']]);
			} else if ($total_replys > 0) {
				$list_user_notify = array();
				$list_replys = $clsFollowUp->getAll("`parent_id`='{$parent_id}' and `user_id`<>'{$user_id}'", "user_id");
				if (!empty($list_replys)) {
					foreach ($list_replys as $key => $val) {
						$list_user_notify[] = $val['user_id'];
					}
				}
				if (!empty($list_user_notify)) {
					$contentNotify = sprintf(
						'<strong>%s</strong> đã bình luận vào hoạt động <strong>%s</strong> 
						với nội dung <strong>%s</strong> vào lúc <i>%s</i>',
						$clsProfile->getFullName($profile_id, $oneProfile),
						$clsFollowUp->getContent($parent_id, $oneFollowUp),
						$content,
						$clsISO->convertTimeToText($time, true)
					);
					// $clsISO->print_pre($contentNotify); die();
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $parent_id, $contentNotify, time(), $list_user_notify);
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
						and `user_id` in (" . implode(',', $list_user_notify) . ") and `token`<>''", "token");
					#thong bao app
					$params = [
						'title' => "CRM - Hoạt động khách hàng",
						'body' => strip_tags($contentNotify),
						'link' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $customer_id)
					];
					$clsNotification->doPushMessagingUser($params, $list_user_notify);
				}
			}
			/** Push notification */
			$subscribers = array();
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					if (!in_array($val['token'], $subscribers)) {
						$subscribers[] = $val['token'];
					}
				}
				if (!empty($subscribers)) {
					$clsNotify->send_subscriber_notification(array(
						'title' => "CRM - Bình luận mới",
						'message' => strip_tags($contentNotify),
						'url' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
					), $subscribers);
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	));
	die();
}
function default_campare_date_now(){
	global $oSmarty, $smarty, $assign_list, $profile_id, $core, $clsISO;
	$date_id = Input::post('date_id');
	$time_id = Input::post('time_id');
	$datetime = $clsISO->convertTextToTime($date_id, $time_id);
	###
	$is_done = 1;
	if ($datetime > time()) {
		$is_done = 0;
	}
	// Return
	echo $is_done;
	die();
}
function default_set_timerange(){
	global $oSmarty, $smarty, $assign_list, $profile_id, $core, $clsISO;
	$after_time = Input::post('after_time', "");
	$is_trigger_time = (int)Input::post('is_trigger_time', 0);
	$reminder_before = (int)Input::post("reminder_before", 0);
	$reminder_before = !empty($reminder_before) ? $reminder_before : 1;
	$time = time();
	if (!empty($after_time)) {
		$time = strtotime($after_time, $time);
	}
	$time_before = $time;
	if (!empty($is_trigger_time)) {
		$time_before = strtotime("-" . $reminder_before . " minutes", $time);
	}
	// Return
	echo json_encode(array(
		'date' => date('Y-m-d', $time),
		'time' => date('H:i', $time),
		'date_before' => date('d/m/Y', $time_before),
		'time_before' => date('H:i', $time_before),
		'reminder_before' => $reminder_before,
	));
	die();
}
function default_set_timebefore(){
	global $oSmarty, $smarty, $assign_list, $profile_id, $core, $clsISO;
	$date_id = Input::post('date_id', "");
	$time_id = Input::post('time_id', "");
	$is_trigger_time = (int)Input::post('is_trigger_time', 0);
	$reminder_before = (int)Input::post("reminder_before", 0);
	$reminder_before = !empty($reminder_before) ? $reminder_before : 1;
	$datetime = $clsISO->convertTextToTime($date_id, $time_id);
	$max_minutes = floor(($datetime - time()) / 60);
	$result = true;
	$msg = "";
	$time_before = strtotime("-" . $reminder_before . " minutes", $datetime);
	// Return
	echo json_encode(array(
		'date_before' => date('d/m/Y', $time_before),
		'time_before' => date('H:i', $time_before),
		'result'	=>	$result,
		'reminder_before' =>	$reminder_before,
		'msg'	=>	$msg,
	));
	die();
}
function default_save_activity(){
	global $oSmarty, $smarty, $profile_id, $core, $clsISO, $oneProfile, $dbconn;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsSetting = new Setting();
	##
	$msg = "_error";
	$tp = Input::post('tp', 'follow-ups');
	$intro = Input::post('intro', ""); // Content
	$customer_id = (int) Input::post('customer_id', 0);
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo '_need_confirm';
		die();
	}
	$oCustomer = $clsCustomer->getOne($customer_id, "`admin_id`,`status_id`,`more_information`,`name`,`notes`,`task_current_id`");
	$admin_id = (int) $oCustomer['admin_id'];
	$old_status_id = (int) $oCustomer['status_id'];
	$list_share_ids = $clsCustomer->getShareIds($customer_id, $oCustomer);
	$more_information = $oCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$action_logs = $core->get_field($more_information, "action_logs", []);
	if ($tp == 'follow-ups') {
		$type_id = (int) Input::post('type_id', 0);
		$followup_id = (int) Input::post('followup_id', 0);
		$cus_status_id = (int) Input::post('cus_status_id', 0);
		$cus_purpose_id = Input::post('cus_purpose_id', []);
		$is_done = (int) Input::post('is_done', 0);
		$status_id = ($is_done == 1 ? _FOLLOWUP_STATUS_DONE_ID : _FOLLOWUP_STATUS_PLAN_ID);
		$date_id = Input::post('date_id');
		$time_id = Input::post('time_id');
		$_result = Input::post('_result');
		$outcome_id = (int) Input::post('outcome_id', 0); // V1: bước tiếp = tác nghiệp tiếp (_CRM_TASK setting_id), đồng bộ pipeline
		if ($followup_id == 0 && $outcome_id > 0) {
			$_step = $clsSetting->getTitle($outcome_id);
			if (!empty($_step)) {
				$_result = $_step . (($_result != '') ? ' — ' . $_result : '');
			}
		}
		$datetime = $clsISO->toTime($date_id, $time_id);
		// Auto-nhắc: follow-up có giờ hẹn tương lai & chưa hoàn thành → bật nhắc, mốc bắn = 15 phút trước.
		// Modal không còn nhập is_reminder/reminder_* nên tự suy từ date_id (cron notify_task quét reminder_time).
		if ($datetime > time() && $is_done != 1) {
			$is_reminder = 1;
			$reminder_before = 15;
			$reminder_datetime = $datetime - (15 * 60);
		} else {
			$is_reminder = 0;
			$reminder_before = 0;
			$reminder_datetime = 0;
		}
		if ($followup_id == 0) {
			$followup_id = $clsFollowUp->getMaxId();
			$arr_property = $content_logs = array();
			$arr_property[] = $type_id;
			$arr_property[] = $status_id;
			if ($cus_status_id > 0) {
				$arr_property[] = $cus_status_id;
			}
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (" . implode(',', $arr_property) . ")", "{$clsProperty->pkey},`title`");
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					if ($val[$clsProperty->pkey] == $type_id) {
						$content_logs[] = sprintf('Loại: %s', $val['title']);
					} else if ($val[$clsProperty->pkey] == $status_id) {
						$content_logs[] = sprintf('Trạng thái: %s', $val['title']);
					} else if ($val[$clsProperty->pkey] == $cus_status_id) {
						$content_logs[] = sprintf('Tình trạng: %s', $val['title']);
					}
				}
			}
			$content_logs[] = sprintf('Nội dung: %s', $intro);
			if (!empty($_result)) $content_logs[] = sprintf('Kết quả: %s', $_result);
			$content_logs[] = sprintf('Thời gian: %s', $date_id . " " . $time_id);
			if (!empty($cus_purpose_id)) {
				$content_logs[] = sprintf('Mục đích: %s', $clsProperty->getTitleArray($cus_purpose_id));
			}
			if ($clsFollowUp->insert(array(
				$clsFollowUp->pkey => $followup_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'customer_id' => $customer_id,
				'intro' => $intro,
				'_result' => $_result,
				'date_id' => $datetime,
				'is_reminder' => $is_reminder,
				'reminder_before' => $reminder_before,
				'reminder_time' => $reminder_datetime,
				'is_send_reminder' => 0,
				'admin_id' => $profile_id,
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			))) {
				$msg = "_success";
				#activity log
				if ($datetime > time()) {
					$title = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào 
					lúc <strong>%s</strong>.', $clsProperty->getTitle($type_id) . ": 
					" . $intro, $clsCustomer->getName($customer_id, $oCustomer), sprintf('%s %s', $date_id, $time_id));
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $title, $datetime, '|' . $profile_id . '|');
				}
				// Người tạo là người phụ trách bắn thông báo cho người liên quan
				if ($admin_id == $profile_id && !empty($list_share_ids)) {
					$list_notify_users = $list_share_ids;
					$titleNotify = sprintf('<strong>%s</strong> đã thêm tương tác với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer), $intro, $clsISO->convertTimeToText($datetime, true));
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNotify, time(), $list_notify_users);
					#thong bao app
					$clsNotification = new Notification();
					$params = [
						'title' => "CRM - Hoạt động khách hàng",
						'body' => strip_tags($titleNotify),
						'link' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $customer_id)
					];
					$clsNotification->doPushMessagingUser($params, $list_notify_users);
					// Người tạo là người liên quan thì bắn notify cho người phụ trách
				} else if ($admin_id != $profile_id) {
					$titleNotify = sprintf('<strong>%s</strong> đã thêm tương tác với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id, $oCustomer), $intro, $clsISO->convertTimeToText($datetime, true));
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNotify, time(), '|' . $profile_id . '|');
					#thong bao app
					$clsNotification = new Notification();
					$params = [
						'title' => "CRM - Hoạt động khách hàng",
						'body' => strip_tags($titleNotify),
						'link' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $customer_id)
					];
					$clsNotification->doPushMessagingUser($params, [$profile_id]);
				}
				$content = sprintf(
					'<strong>%s</strong> đã thêm mới follow-ups với %s',
					$clsProfile->getFullName($profile_id, $oneProfile),
					implode(',', $content_logs)
				);
				$action_logs[$clsISO->getUniqid()] = array(
					'content' => $content,
					'user_id' => $profile_id,
					'reg_date' => time()
				);
				// $clsISO->print_pre($actions_logs); die();
				$more_information['action_logs'] = $action_logs;
				$_cusUpdate = array(
					'upd_date' => time(),
					'status_id' => ($cus_status_id > 0 ? $cus_status_id : $old_status_id),
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				);
				if ($outcome_id > 0) {
					// "Bước tiếp theo" = CHUYỂN tác nghiệp HIỆN TẠI sang tác nghiệp được chọn (advance) → danh sách phản ánh ngay
					// ở cột TÁC NGHIỆP, không để chờ ở cột TÁC NGHIỆP TIẾP. (KHÔNG trash nhắc _crm để tránh xoá nhầm lịch Gặp
					// cron sáng cần — nhắc 15' trước do chính follow-up báo cáo target_id=0 lo.)
					$_curTask = (int) $oCustomer['task_current_id'];
					if ($outcome_id === $_curTask) {
						// Chọn lại đúng tác nghiệp hiện tại (vd Gọi điện → Gọi lại): chỉ đặt lại giờ, GIỮ counter "(+N)", xoá task_next.
						$_cusUpdate['task_next_id'] = 0;
						$_cusUpdate['time_receipt'] = ($datetime > 0) ? $datetime : 0;
					} else {
						// Chuyển hẳn sang tác nghiệp mới: task_current = chọn; xoá next/result; reset counter ladder (như move_next).
						foreach (array_keys($more_information) as $_ck) {
							if (strpos((string) $_ck, 'task_counter_') === 0) { unset($more_information[$_ck]); }
						}
						$_cusUpdate['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
						$_cusUpdate['task_current_id'] = $outcome_id;
						$_cusUpdate['task_next_id'] = 0;
						$_cusUpdate['task_result_id'] = 0;
						$_cusUpdate['time_receipt'] = ($datetime > 0) ? $datetime : 0;
					}
				}
				if ($clsCustomer->updateOne($customer_id, $_cusUpdate)) {
					$clsCustomerMeta = new CustomerMeta();
					$clsCustomerMeta->syncByCustomerType($customer_id, 'purpose', $cus_purpose_id, $profile_id);
					if ($cus_status_id > 0 && $oCustomer['status_id'] != $cus_status_id) {
						$clsCustomerHistory = new CustomerHistory();
						$clsCustomerHistory->insert(array(
							'customer_id' => $customer_id,
							'from_status_id' => $old_status_id,
							'to_status_id' => $cus_status_id,
							'staff_id' => $profile_id,
							'action_date' => time()
						));
					}
				}
			}
		} else {
			$oneFollowup = $clsFollowUp->getOne($followup_id);
			// Chỉ re-arm nhắc khi mốc nhắc thực sự đổi; sửa nội dung mà giữ nguyên giờ hẹn thì không bật lại (tránh nhắc trùng).
			$reset_send = (isset($oneFollowup['reminder_time']) && $oneFollowup['reminder_time'] != $reminder_datetime) ? 0 : (int) $oneFollowup['is_send_reminder'];
			if ($clsFollowUp->updateOne($followup_id, array(
				'intro' => $intro,
				'date_id' => $datetime,
				'is_reminder' => $is_reminder,
				'reminder_before' => $reminder_before,
				'reminder_time' => $reminder_datetime,
				'is_send_reminder' => $reset_send,
				'status_id' => $status_id,
				'user_id_update' => $profile_id,
				'_result' => $_result,
				'upd_date' => time()
			))) {
				$msg = "_success";
				if ($cus_status_id > 0 && $oCustomer['status_id'] != $cus_status_id) {
					$content = sprintf(
						'<strong>%s</strong> đã cập nhật tình trạng khách hàng thành %s',
						$clsProfile->getFullName($profile_id, $oneProfile),
						$clsProperty->getTitle($cus_status_id)
					);
					$action_logs[$clsISO->getUniqid()] = array(
						'content' => $content,
						'user_id' => $profile_id,
						'reg_date' => time()
					);
					$more_information['action_logs'] = $action_logs;
				}
				$clsCustomer->updateOne($customer_id, array(
					'upd_date' => time(),
					'status_id' => ($cus_status_id > 0 ? $cus_status_id : $old_status_id),
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				));
				$clsCustomerMeta = new CustomerMeta();
				$clsCustomerMeta->syncByCustomerType($customer_id, 'purpose', $cus_purpose_id, $profile_id);
			}
		}
	} else {
		$current_now = time();
		$notes = $oCustomer['notes'];
		$notes_arrs = $clsISO->to_array_json($notes);
		$notes_arrs[$clsISO->getUniqid()] = array(
			'content' => $intro,
			'reg_date' => $current_now,
			'upd_date' => $current_now,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		);
		$action_logs[$clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'user_id' => $profile_id,
			'content' => sprintf(
				'<strong>%s</strong> đã thêm mới ghi chú <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$intro
			),
		);
		$more_information['action_logs'] = $action_logs;
		if ($clsCustomer->updateOne($customer_id, array(
			'notes' => json_encode($notes_arrs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))) {
			$msg = '_success';
			// Người tạo là người phụ trách bắn thông báo cho người liên quan
			if ($admin_id == $profile_id && !empty($list_share_ids)) {
				$list_notify_users = $list_share_ids;
				$titleNotify = sprintf('<strong>%s</strong> đã thêm ghi chú với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($current_now, true));
				// $dbconn->debug=true;
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNotify, time(), $list_notify_users);
			} else if ($admin_id != $profile_id) {
				// Người tạo là người liên quan thì bắn notify cho người phụ trách
				$titleNotify = sprintf('<strong>%s</strong> đã thêm ghi chú vào khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($current_now, true));
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNotify, time(), '|' . $admin_id . '|');
			}
		}
	}
	// Reuturn
	echo $msg;
	die();
}
function default_delete_activity(){
	global $oSmarty, $smarty, $assign_list, $profile_id, $core, $clsISO, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$followup_id = (int) Input::post('followup_id', 0);
	###
	$msg = "_error";
	$oneFollowup = $clsFollowUp->getOne($followup_id);
	if ($clsFollowUp->deleteOne($followup_id)) {
		$msg = "_success";
		#activity log
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("FollowUp", "delete", $oneFollowup);
	}
	// Return
	echo $msg;
	die();
}
function default_ajLoadLogsCRM(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsUser = new User();
	$clsLog = new Log();
	#
	$sortby = Input::post('sortby', 'reg_date');
	$sorttype = Input::post('sorttype', 'desc');
	$html = '<input type="hidden" class="sorthander_LogsCRM" value="' . implode('|', array($sortby, $sorttype)) . '" />
	<table id="CrmCustomerLatestCopntact" class="table table-striped table-hover table-responsive" width="100%">
		<thead><tr>
			<th class="text-center" width="5%">No.</th>
			<th class="text-left" width="15%">' . $core->get_Lang('Date') . '</th>
			<th class="text-left" width="15%">' . $core->get_Lang('Author') . '</th>
			<th class="text-left">' . $core->get_Lang('Content') . '</th>
			<th class="text-left" width="10%">' . $core->get_Lang('IP') . '</th>
		</tr></thead>
		<tr>
			<td class="hidden-xs"></td>
			<td data-label="' . $core->get_Lang('Date') . '"><input class="form-control InputSearchLog datepicker"  placeholder="dd/mm/yyyy" /></td>
			<td data-label="' . $core->get_Lang('Author') . '"><input class="form-control InputSearchLog" placeholder="' . $core->get_Lang('EnterKeyword') . '" /></td>
			<td data-label="' . $core->get_Lang('Content') . '"><input class="form-control InputSearchLog" placeholder="' . $core->get_Lang('EnterKeyword') . '" /></td>
			<td class="hidden-xs"></td>
		</tr>';
	// Pagination
	$currentPage = (int) Input::post('currentPage', 1);
	$number_per_page = (int) Input::post('number_per_page', 10);
	$cond = "user_id='{$adminid}' and type='CRM'";
	$totalRecord = $clsLog->countItem($cond);
	$totalPage = ceil($totalRecord / $number_per_page);
	$offset = ($currentPage - 1) * $number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	$lstLogs = $clsLog->GetAll($cond . " order by reg_date DESC" . $limitCond);
	if (!empty($lstLogs)) {
		$ii = 0; //Init
		foreach ($lstLogs as $log) {
			$html .= '<tr>
					<td data-label="No." class="text-center">' . ($ii + 1) . '</td>
					<td data-label="' . $core->get_Lang('Date') . '">' . $clsISO->getTimeAgo($log['reg_date'], true) . '</td>
					<td data-label="' . $core->get_Lang('Author') . '">' . $clsUser->getFullName($log['user_id']) . '</td>
					<td data-label="' . $core->get_Lang('Content') . '">' . $log['intro'] . '</td>
					<td data-label="' . $core->get_Lang('IP') . '">' . $log['ip'] . '</td>
				</tr>';
			++$ii;
		}
	} else {
		$html .= '<tr>
				<td class="text-center" colspan="5">
					' . CRM::renderHTMLNoDocument('Not any foolow-Ups') . '
				</div>
			</tr>';
	}
	$html .= '</tbody>
	</table>';
	if ($totalPage > 0) {
		$html .= '<div id="pp_LogsCRM" class="easyui-pagination" pageNumber="' . $currentPage . '"></div>';
	}
	// output
	echo @json_encode(array(
		'currentPage' => $currentPage,
		'number_per_page' => $number_per_page,
		'totalPage' => $totalPage,
		'totalRecord' => $totalRecord,
		'html'	=> $html
	));
	die();
}
function default_report(){
	global $smarty, $assign_list, $profile_id, $core, $clsISO, $_LANG_ID, $oneProfile;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$cond = "`is_trash`=0";
	if ($clsISO->checkPermissMs()) {
		// Next
	} else if ($clsISO->checkHeadSale($oneProfile['role_id'])) {
		// Staff ins
	} else {
		$cond .= " and `admin_id`='{$profile_id}'";
	}
	#
	$list_departments = array();
	$clsProperty->makeOption(0, '_DEPARTMENT', 0, 0, $list_departments);
	$assign_list["list_departments"] = $list_departments;
	#
	$list_filters = array();
	$list_filters['TODAY'] = $core->get_Lang('Today');
	$list_filters['YESTERDAY'] = $core->get_Lang('Yesterday');
	$list_filters['THIS_WEEK'] = $core->get_Lang('ThisWeek');
	$list_filters['PREV_WEEK'] = $core->get_Lang('PrevWeek');
	$list_filters['THIS_MONTH'] = $core->get_Lang('ThisMonth');
	$list_filters['PREV_MONTH'] = $core->get_Lang('PrevMonth');
	$list_filters['THIS_PERIOD'] = $core->get_Lang('ThisPeriod');
	$list_filters['PREV_PERIOD'] = $core->get_Lang('PrevPeriod');
	$list_filters['THIS_YEAR'] = $core->get_Lang('ThisYear');
	$list_filters['PREV_YEAR'] = $core->get_Lang('PrevYear');
	// $list_filters['OTHERS'] = $core->get_Lang('OtherDate');
	$smarty->assign('list_filters', $list_filters);
	// Min - Max date
	$tmp = $clsCustomer->getByCond($cond . " and `reg_date`>0 order by `reg_date` asc limit 0,1", "reg_date");
	$min_date = !empty($tmp) ? $tmp['reg_date'] : time();
	$start_year = date('Y', $min_date);
	unset($tmp);
	$end_year = date('Y', time());
	// Opts Year
	$htmlOptsYear = '';
	for ($ii = $start_year; $ii <= $end_year; $ii++) {
		$htmlOptsYear .= '<option value="' . $ii . '" ' . ($to == $ii ? 'selected' : '') . '>
			' . $core->get_Lang('Year') . ' ' . $ii . '
		</option>';
	}
	$smarty->assign('htmlOptsYear', $htmlOptsYear);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo CRM | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
}
function default_get_select_staff(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$html = sprintf('<option value="0">%s</option>', 'Lựa chọn nhân viên');
	$department_id = (int) Input::post('department_id', 0);
	$field = "{$clsProfile->pkey},code,full_name,last_name,first_name";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`=1 and `status_id`<>'" . _STATUS_STAFF_OFF_ID . "' 
	and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $field);
	if (!empty($list_staffs)) {
		foreach ($list_staffs as $key => $val) {
			$html .= sprintf('<option value="%s">%s</option>', $val[$clsProfile->pkey], $clsProfile->getIndentityV2($val[$clsProfile->pkey], $val));
		}
	}
	// return
	echo $html;
	die();
}
function default_load_cell_crm_report(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsBilling = new Billing();
	###
	$cell 			= (int) Input::request('cell', 1);
	$time_type 		= Input::post('time_type', 'THIS_MONTH');
	$staff_id 		= (int) Input::post('staff_id', 0);
	$department_id 	= (int) Input::post('department_id', 0);
	$assign_list['cell'] = $cell;
	###
	$total = 0;
	$cond = "`is_trash`=0";
	$field = in_array($cell, array(4, 5)) ? "staff_id" : "admin_id";
	if ($department_id > 0 && $staff_id == 0) {
		$tmp = $clsProfile->getAll("`status_id`<>'" . _STATUS_STAFF_OFF_ID . "' and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $clsProfile->pkey);
		if (!empty($tmp)) {
			$list_staffs = array();
			foreach ($tmp as $key => $val) {
				$list_staffs[] = $val[$clsProfile->pkey];
			}
			$cond .= " and `{$field}` in (" . implode(',', $list_staffs) . ")";
		}
	} else if ($staff_id > 0) {
		$cond .= " and `{$field}`='{$staff_id}'";
	}
	$tmp = $clsISO->getRangeTime($time_type);
	$start_date = $tmp['start_date'];
	$due_date = $tmp['due_date'];
	// $clsISO->print_pre($tmp); die();
	if ($cell == 1) {
		$field = "{$clsProperty->pkey},title";
		$list_status_reports = $clsProperty->getAllCache("`property_type`='CUSTOMER_STATUS' and {$clsProperty->pkey}<>'" . _CRM_STATUS_DONTCARE_ID . "' order by `order_no` asc", $field);
		if (!empty($list_status_reports)) {
			$cond .= " and (`reg_date` between {$start_date} and {$due_date})";
			$total = $clsCustomer->countItem($cond);
			foreach ($list_status_reports as $key => $val) {
				$property_id = $val[$clsProperty->pkey];
				$total_customers = $clsCustomer->countItem($cond . " and `status_id`='{$property_id}'");
				$list_status_reports[$key]['total_customers'] = $total_customers;
				$list_status_reports[$key]['percent'] = round($total_customers / $total * 100, 1);
			}
		}
		$assign_list['list_status_reports'] = $list_status_reports;
	} else if ($cell == 2) {
		$list_criterias = array(
			'NUM_CUS' => array(
				'title' => 'Số khách mới',
				'total' => 0
			),
			'NUM_INTERACT' => array(
				'title' => 'Số tương tác',
				'total' => 0
			),
			'NUM_CALL' => array(
				'title' => 'Số cuộc gọi',
				'total' => 0
			),
			'NUM_MEET' => array(
				'title' => 'Số cuộc gặp',
				'total' => 0
			),
			'NUM_MEET' => array(
				'title' => 'Số tiếp khách',
				'total' => 0
			),
			'NUM_SOLD' => array(
				'title' => 'Số căn chốt',
				'total' => 0
			)
		);
		foreach ($list_criterias as $key => $val) {
			$cond = "`is_trash`=0";
			if ($key == 'NUM_CUS') {
				$total_results = $clsCustomer->countItem("{$cond} and (`reg_date` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if ($key == 'NUM_INTERACT') {
				$total_results = $clsFollowUp->countItem("{$cond} and (`date_id` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if ($key == 'NUM_CALL') {
				$total_results = $clsFollowUp->countItem("{$cond} and `type_id`='" . _FOLLOWUP_CALL_ID . "' 
					and (`date_id` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if ($key == 'NUM_MEET') {
				$total_results = $clsFollowUp->countItem("{$cond} and `type_id`='" . _FOLLOWUP_TASK_ID . "' 
					and (`date_id` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if ($key == 'NUM_SOLD') {
				$total_results = $clsBilling->countItem("{$cond} and `is_cancel`=0 and `is_alliance`=0 
					and (`deposit_date` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			}
			$list_criterias[$key]['total'] = $total_results;
		}
		$assign_list['list_criterias'] = $list_criterias;
	} else if ($cell == 4) {
		$field = "{$clsProperty->pkey},title";
		$list_products = $clsProperty->getAllCache("`property_type`='_BILLING_TYPE' order by `order_no` asc", $field);
		if (!empty($list_products)) {
			$cond .= " and (`deposit_date` between {$start_date} and {$due_date})";
			foreach ($list_products as $key => $val) {
				$property_id = $val[$clsProperty->pkey];
				$total_billings = $clsBilling->countItem($cond . " and `is_cancel`=0 and `is_alliance`=0 and `billing_type`='{$property_id}'");
				$total += $total_billings;
				$list_products[$key]['total_billings'] = $total_billings;
			}
			$arr_total_billings = array_column($list_products, "total_billings");
			array_multisort($arr_total_billings, SORT_DESC, $list_products);
		}
		$assign_list['list_products'] = $list_products;
	} else if ($cell == 5) {
		$field = "{$clsProperty->pkey},title";
		$list_products = $clsProperty->getAllCache("`property_type`='_BILLING_TYPE' order by `order_no` asc", $field);
		if (!empty($list_products)) {
			$cond .= " and (`deposit_date` between {$start_date} and {$due_date})";
			foreach ($list_products as $key => $val) {
				$property_id = $val[$clsProperty->pkey];
				$total_grands = $clsBilling->sumItem("totalgrand", $cond . " and `is_cancel`=0 and `is_alliance`=0 and `billing_type`='{$property_id}'");
				$total += $total_grands;
				$list_products[$key]['total_grands'] = $total_grands;
			}
			$arr_total_grands = array_column($list_products, "total_grands");
			array_multisort($arr_total_grands, SORT_DESC, $list_products);
		}
		$total = ($total > 0) ? $clsISO->formatPrice($total) : 0;
		$assign_list['list_products'] = $list_products;
	}
	// Return
	$html = $core->build('_ajax.report.tpl');
	echo json_encode(array(
		'cond' => $cond,
		'html' => $html,
		'cell' => $cell,
		'total' => $total,
		'percent' => $percent
	));
	die();
}
function default_loadDataChartCrmResource(){
	global $smarty, $assign_list, $adminid, $core, $clsISO;
	global $core, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	#
	$cond = "`is_trash`=0";
	$data = $dataPoints = array();
	if ($clsISO->checkPermissMs()) {
		// Next
	} else if ($clsISO->checkHeadSale($oneProfile['role_id'])) {
		// Staff ins
	} else {
		$cond .= " and `admin_id`='{$profile_id}'";
	}
	$field = "{$clsProperty->pkey},title";
	$lstCustomerType = $clsProperty->getAll("`is_trash`='0' and `property_type`='_CUSTOMER_RESOURCES' order by `order_no` ASC", $field);
	if (!empty($lstCustomerType)) {
		foreach ($lstCustomerType as $property) {
			$resource_id = $property[$clsProperty->pkey];
			$total = $clsCustomer->countItem("{$cond} and `resource_id`='{$resource_id}'");
			$dataPoints[] = array(
				'label'	=> $property['title'],
				'y'	=> $total * 1
			);
		}
	}
	$data['type'] = 'pie';
	$data['showInLegend'] = true;
	$data['indexLabel'] = '{y}';
	$data['legendText'] = '{label}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode($barChartData);
	die();
}
function default_loadDataChartCrmStatus(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	#
	$data = $barChartData = $dataPoints  = array();
	$barChartData['animationEnabled'] = true;
	$cond = "`is_trash`=0";
	if ($clsISO->checkPermissMs()) {
		// Next
	} else if ($clsISO->checkHeadSale($oneProfile['role_id'])) {
		// Staff ins
	} else {
		$cond .= " and `admin_id`='{$profile_id}'";
	}
	$field = "{$clsProperty->pkey},title";
	$lstCustomerStatus = $clsProperty->GetAll("`is_trash`=0 and `parent_id`='0' and `property_type`='CUSTOMER_STATUS'", $field);
	if (!empty($lstCustomerStatus)) {
		foreach ($lstCustomerStatus as $property) {
			$status_id = $property[$clsProperty->pkey];
			$total_customers = $clsCustomer->countItem("{$cond} and `status_id`='{$status_id}'");
			$dataPoints[] = array(
				'label'	=> $property['title'],
				'y'	=> $total_customers * 1
			);
		}
	}
	$data['type'] = 'spline';
	$data['showInLegend'] = 'true';
	$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode($barChartData);
	die();
}
function default_open_upd_status(){
	global $profile_id, $core, $clsISO, $clsUser, $clsProperty;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$uid = Input::post('uid');
	$customer_id = (int) Input::post('customer_id', 0);
	$oneCustomer = $clsCustomer->getOne($customer_id, "status_id");
	$status_id = !empty($oneCustomer) ? $oneCustomer['status_id'] : 0;
	###
	$html = '<div class="modal-dialog modal-xss modal-dialog-centered">
		<form method="POST" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cập nhật tình trạng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="form-group mb-2">
					<select class="form-control form-select required" name="status_id">
						' . $clsProperty->getSelectByProperty('CUSTOMER_STATUS', $status_id) . '
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="p_field" value="status_id" />
				<button type="button" class="btn flex-fill btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn flex-fill btn-primary" onclick="$Core.crm.update_field(this,event);" p_field="status_id" 
					p_id="' . $customer_id . '" uid="' . $uid . '" p_field="status_id">' . $core->makeIcon('check', 'Cập nhật') . '
				</button>
			</div>
		</form>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	));
	die();
}
function default_update_field(){
	global $smarty, $mod, $act, $profile_id, $core, $clsISO, $clsUser, $clsProperty;
	global $profile_id, $oneProfile, $dbconn;
	$clsTag = new Tag();
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$clsCustomerHistory = new CustomerHistory();
	###
	$msg = "_error";
	$uid = Input::post('uid');
	$p_id = Input::post('p_id');
	$p_field = Input::post('p_field');
	$p_value = Input::post($p_field);
	if ($p_field == 'tags') {
		$_oTag = $clsCustomer->getOne($p_id, "admin_id");
		if (!((int)$_oTag['admin_id'] == $profile_id || $clsCustomer->isFullPermiss())) {
			echo '_error';
			die();
		} // U-P1a: va IDOR - chi chu KH/full-permiss sua tag
		if (!empty($p_value)) {
			$parts = @explode(',', $p_value);
			if (!empty($parts)) {
				$list_tags_id = array();
				foreach ($parts as $tag) {
					$tmp = $clsTag->getByCond("`user_id`='{$profile_id}' and `slug`='" . $core->replaceSpace($tag) . "'");
					if (!empty($tmp)) {
						$list_tags_id[] = $tmp[$clsTag->pkey];
					} else {
						$tag_id = $clsTag->getMaxId();
						$clsTag->insert(array(
							$clsTag->pkey => $tag_id,
							'tag_type' => '_crm',
							'user_id' => $profile_id,
							'title' => $tag,
							'slug' => $core->replaceSpace($tag)
						));
						$list_tags_id[] = $tag_id;
					}
				}
				$clsCustomerMeta = new CustomerMeta();
				$clsCustomerMeta->syncByCustomerType($p_id, 'tag', $list_tags_id, $profile_id);
				$msg = "_success|||" . $clsCustomer->getHTMLTags($p_id, [], '_activity');
			}
		} else {
			$clsCustomerMeta = new CustomerMeta();
			$clsCustomerMeta->syncByCustomerType($p_id, 'tag', array(), $profile_id);
			$msg = "_success|||";
		}
	} else if ($p_field == 'admin_id') {
		$oCustomer = $clsCustomer->getOne($p_id, "`name`,`admin_id`,`user_id`,`more_information`");
		// Cho phép GÁN: super / quyền admin_assign_client / chủ hiện tại / NGƯỜI TẠO (user_id) — người tạo được gán khách cho sale khác
		if (!(($clsCustomer->isFullPermiss() || $clsISO->checkPermission('admin_assign_client')) || (int)$oCustomer['admin_id'] == $profile_id || (int)$oCustomer['user_id'] == $profile_id)) {
			echo '_error';
			die();
		}
		if ($clsCustomer->isReceivePending($p_id)) {
			echo '_need_confirm';
			die();
		} // chưa xác nhận nhận khách thì không được giao lại
		$admin_id = (int) $oCustomer['admin_id'];
		$more_information = $oCustomer['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if ((int) $p_value > 0 && $admin_id != $p_value) {
			$action_logs = $core->get_field($more_information, "action_logs", []);
			$list_share_arrs = $clsCustomer->getShareIds($p_id, $oCustomer);
			$arr_profile = $adminProfile = $adminProfile_Old = array();
			if ($profile_id == $admin_id) { // Mình chuyển KH của mình
				$adminProfile_Old = $oneProfile;
			} else { // Mình chuyển KH của người khác
				$arr_profile[] = $admin_id;
			}
			$arr_profile[] = $p_value;
			$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (" . implode(',', $arr_profile) . ")", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					if ($val[$clsProfile->pkey] == (int) $p_value) {
						$adminProfile = $val;
					} else if ($val[$clsProfile->pkey] == $admin_id) {
						$adminProfile_Old = $val;
					}
				}
				unset($tmp);
			}
			$content = sprintf(
				'<strong>%s</strong> đã chuyển quyền quản lý từ <strong>%s</strong> thành <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$clsProfile->getFullName($admin_id, $adminProfile_Old),
				$clsProfile->getFullName($p_value, $adminProfile)
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			if (!empty($list_share_arrs) && !in_array($admin_id, $list_share_arrs)) {
				$list_share_arrs = array_unique($list_share_arrs);
				$list_share_arrs[] = $admin_id;
			}
			$list_share_arrs = $clsCustomer->normalizeIdArray($list_share_arrs);
			if ($clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'use_globe' => 1,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))) {
				$clsCustomer->syncShareIds($p_id, $list_share_arrs, $profile_id, false);
				// Giao hẳn qua "Thay người quản lý" (open_in_charge): tạo phiếu CHỜ XÁC NHẬN cho người nhận.
				$clsCustomerAssign = new CustomerAssign();
				$clsCustomerAssign->createPending($p_id, $p_value, $profile_id, 1);
				/** Gửi notify */
				$msg = "_success|||<a class=\"autoclick_" . $p_id . "\" customer_id=\"" . $p_id . "\" 
					onclick=\"$Core.crm.view_activity(this, event);\"></a>";
				$titleNoty = sprintf(
					'<strong>%s</strong> đã giao bạn phụ trách khách hàng <strong>%s</strong>',
					$clsProfile->getFullName($profile_id, $oneProfile),
					$clsCustomer->getName($p_id, $oCustomer)
				);
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $p_id, $titleNoty, time(), "|" . $p_value . "|");
				#thong bao app
				$clsNotification = new Notification();
				$params = [
					'title' => "CRM - Khách hàng mới",
					'body' => strip_tags($titleNotify),
					'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview/', $p_id)
				];
				$clsNotification->doPushMessagingUser($params, [$p_value]);
				/** Gửi thông báo qua Zalo */
				if ($clsCustomer->isRootProfile()) {
					/** Lưu lại giao cho ai */
					$clsCustomerSales->insert(array(
						$clsCustomerSales->pkey => $clsCustomerSales->getMaxId(),
						'customer_id' => $p_id,
						'admin_id' => $p_value,
						'assign_date' => time(),
						'user_id' => $profile_id
					));
					$zaloId = $clsProfile->getZaloId($p_value, $_swProfile);
					if (!empty($zaloId)) {
						$clsZalo = new Zalo();
						$message = sprintf("Xin chào %s", $clsProfile->getFullName($p_value, $_swProfile));
						$message .= "\r";
						$message .= sprintf(
							"[%s %s] đã giao cho bạn phụ trách khách hàng [%s]",
							$oneProfile['role_name'],
							$clsProfile->getFullName($profile_id, $oneProfile),
							$clsCustomer->getName($p_id, $oCustomer)
						);
						$message .= "\r";
						$message .= "Hãy truy cập CRM/Quản lý khách hàng (".DOMAIN_URL."/crm/) để bắt đầu chăm sóc khách hàng!";
						$message .= "\r";
						$message .= "\r";
						$message .= "⚙️ 1. Quy tắc chăm khách";
						$message .= "\r";
						$message .= "1️⃣ Phản hồi khách trong vòng 15 phút kể từ khi được cấp data.";
						$message .= "\r";
						$message .= "👉 Nếu quá 15 phút không có tương tác (call / note / update) → hệ thống auto chuyển khách sang sales khác.";
						$message .= "\r";
						$message .= "2️⃣ Tương tác ít nhất 3 lần trong 3 ngày đầu (gọi điện, nhắn tin, Zalo, trao đổi trực tiếp...).";
						$message .= "\r";
						$message .= "👉 Mục tiêu: xác nhận nhu cầu, xây dựng kết nối và tạo phản hồi ban đầu.";
						$message .= "\r";
						$message .= "3️⃣ Cập nhật và tương tác đều đặn trong 24–48h.";
						$message .= "\r";
						$message .= "👉 Nếu quá 24h không có note mới, hệ thống sẽ cảnh báo và hiển thị danh sách khách mới nhận trong 24h chưa có tương tác để sale chủ động xử lý.";
						$message .= "\r";
						$message .= "👉 Nếu quá 48h vẫn không có phản hồi hoặc chăm sóc, Quản trị viên sẽ review và quyết định có chuyển khách sang sales khác hay không, dựa vào mức độ chăm thật.";
						$message .= "\r";
						$message .= "4️⃣ Ghi chú chi tiết sau mỗi cuộc gọi hoặc tương tác: nêu rõ phản hồi, nhu cầu và hướng xử lý tiếp theo.";
						$message .= "\r";
						$clsZalo->sendMsg($zaloId, $_swProfile['phone'], $message);
					}
				}
			}
		}
	} else {
		$oCustomer = $clsCustomer->getOne($p_id, "admin_id,status_id,more_information");
		$admin_id = (int) $oCustomer['admin_id'];
		if (!($admin_id == $profile_id || $clsCustomer->isFullPermiss())) {
			echo '_error';
			die();
		} // U-P1a: va IDOR sua field inline (chi chu KH hoac full-permiss)
		if (!in_array($p_field, array('status_id'), true)) {
			echo '_error';
			die();
		} // hardening: whitelist - chi cho phep status_id qua nhanh generic nay
		if ($p_field == 'status_id' && (int)$p_value <= 0) {
			echo '_error';
			die();
		} // chong clobber status_id ve 0 (dat TRUOC history-insert de khong sinh orphan)
		if ($clsCustomer->isReceivePending($p_id)) {
			echo '_need_confirm';
			die();
		} // chan thao tac neu nguoi nhan chua xac nhan khach nay
		$status_id = (int) $oCustomer['status_id'];
		$more_information = $oCustomer['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$action_logs = $core->get_field($more_information, "action_logs", []);
		if ($p_value != $oCustomer['status_id']) {
			$arr_property = $content_logs = array();
			$arr_property[] = $p_value;
			$arr_property[] = $status_id;
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (" . implode(",", $arr_property) . ")", "{$clsProperty->pkey},title");
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					if ($val[$clsProperty->pkey] == $p_value) {
						$content_logs['to'] = $val['title'];
					} else if ($val[$clsProperty->pkey] == $status_id) {
						$content_logs['from'] = $val['title'];
					}
				}
			}
			$content = sprintf(
				'<strong>%s</strong> đã cập nhật tình trạng khách hàng từ <strong>%s</strong> tới <strong>%s</strong>',
				$clsProfile->getFullName($profile_id, $oneProfile),
				$content_logs['from'],
				$content_logs['to']
			);
			$action_logs[$clsISO->getUniqid()] = array(
				'content' => $content,
				'user_id' => $profile_id,
				'reg_date' => time()
			);
			$more_information['action_logs'] = $action_logs;
			$clsCustomerHistory->insert(array(
				'customer_id' => $p_id,
				'from_status_id' => $status_id,
				'to_status_id' => $p_value,
				'staff_id' => $profile_id,
				'action_date' => time()
			));
		}
		$permiss_action = ($admin_id == $profile_id) ? 1 : 0;
		###
		$_updated = ($p_field == 'status_id')
			? $clsCustomer->changeStatus($p_id, (int)$p_value, array('extra' => array('more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE))))
			: $clsCustomer->updateOne($p_id, array('upd_date' => time(), $p_field => $p_value, 'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)));
		if ($_updated) {
			$oProperty = $clsProperty->getOne($p_value, "bgcolor,textcolor");
			$html = '<a class="mr-1 cursor-pointer"' . ($permiss_action == 1 ? ' onClick="$Core.crm.open_upd_status(this, event)"' : '') . ' customer_id="' . $p_id . '" uid="' . $uid . '">' . $clsProperty->getLabel($p_value) . '</a>';
			$msg = "_success|||" . $html;
		}
	}
	// Reurn
	echo $msg;
	die();
}
function default_load_setting(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	#
	$CFG = new Configuration();
	$clsEmailTemplate = new EmailTemplate();
	$lstEmailTemplate = $clsEmailTemplate->getAll("_group='CRM'");
	$htmlOptsEmailTemplate = '';
	if (!empty($lstEmailTemplate)) {
		foreach ($lstEmailTemplate as $template) {
			$htmlOptsEmailTemplate .= '<option value="' . $template[$clsEmailTemplate->pkey] . '">
				' . $template['name'] . '
			</option>';
		}
		unset($lstEmailTemplate);
	}
	#
	$html = '<div class="mg-wrapper">
		<div class="card mb-2">
			<div class="card-header">
				<a class="back mr-2 goToPage" page="setting" title="' . $core->get_Lang('Back') . '" href="javascript:void();"><img src="' . ICON_BACK . '" /></a>
				<span class="text-upper">' . $core->get_Lang('Settings') . '</span>
			</div>
			<div class="card-body">
				<form class="frmIssue">
					<div class="form-group form-row mb-2">
						<label class="col-form-label col-12 col-md-3">Đồng bộ nên Google</label>
						<div class="col-12 col-md-9">
							<label class="switch">
								<input type="checkbox" name="CRM_GoogleCalendarSysc" value="1"  />
								<span class="slider round"></span>
							</label>
							<spann class="help-block">Allow calendar synchronization with Google calendar</span>
						</div>
					</div>
					<div class="form-group mb-2 form-row">
						<label class="col-form-label col-12 col-md-3">Google Calendar Name</label>
						<div class="col-12 col-md-9">
							<input class="form-control" name="CRM_GoogleCalendarName" value="" />
							<span class="help-block">Set name Google calendar</span>
						</div>
					</div>
					<div class="form-group mb-2 form-row">
						<label class="col-form-label col-12 col-md-3">' . $core->get_Lang('Reschedule Follow-up') . '</label>
						<div class="col-12 col-md-9">
							<select class="form-control" name="CRM_FollowUpRescheduleTemplate">
								' . $htmlOptsEmailTemplate . '
							</select>
							<span class="help-block">Choose the email template that will be used to notify admins about a follow-up being rescheduled</span>
						</div>
					</div>
					<div class="form-group mb-2 form-row">
						<label class="col-form-label col-12 col-md-3">' . $core->get_Lang('Follow-up Type On Contact Creation') . '</label>
						<div class="col-12 col-md-9">
							<select class="form-control" name="CRM_FollowUpTypeDefault"></select>
							<span class="help-block">Configure Type of Follow-up that will be created upon contact creation</span>
						</div>
					</div>
				</form>
			</div>
			<div class="card-footer border-top">
				<div class="d-flex justify-content-center">
					<button type="button" onClick="$Core.crm.save_setting(this, event)" class="btn btn-outline-primary"><span>' . $core->makeIcon('check', 'Cập nhật') . '</span></button>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<h5 class="card-header">' . $core->makeIcon('gavel', 'Cài đặt Cronb') . '</h5>
			<div class="card-body">
				<div class="note note-info">
					' . $core->get_Lang('Cron has to be set manually by an administrator. It will handle various functionalities such as sending emails at the specified time. It is recommended that cron run should be set at least once a day to review configured notifications in the system') . '.
				</div>
				<table class="table table-bordered table-setting-cronjob" width="100%">
					<tr class="even">
						<td class="text-right">Tình trạng</td>
						<td>
							<label class="switch">
							  <input type="checkbox" name="crm_cronjob_status" class="js_crm-cronjob-status" value="1" ' . ($CFG->getValue('crm_cronjob_status') == 1 ? 'checked' : '') . ' />
							  <span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr class="odd">
						<td width="15%" class="text-right">Path</td>
						<td width="85%"><input type="text" readonly="readonly" class="form-control" value="' . ABSPATH . '/cronjobs/CRM.php" /></td>
					</tr>
					<tr class="even">
						<td class="text-right">URL</td>
						<td><input type="text" class="form-control" readonly="readonly" value="' . PCMS_URL . '/cronjons/CRM.php" /></td>
					</tr>
					<tr class="even tr_cron-setting hidden">
						<td class="text-right">' . $core->get_Lang('Cron command') . '</td>
						<td><input type="text" name="crm_cronjob_command" class="form-control" value="* * * * * wget -O /dev/null ' . PCMS_URL . '/cronjobs/CRM.php >/dev/null 2>&1" /></td>
					</tr>
					<tr class="odd">
						<td class="text-right">Lần cuối</td>
						<td>2018-03-01 17:36:39 </td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card">
			<h5 class="card-header">' . $core->get_Lang('Property Type') . '</h5>
			<div class="card-body">';
	$list_property_array = array(
		'CUSTOMER_TYPE' => $core->get_Lang('Customer Type'),
		'CUSTOMER_STATUS' => $core->get_Lang('Customer Status'),
		'_SALE_STATUS' => $core->get_Lang('Sale Status'),
		'_FOLLOWUP_TYPE' => $core->get_Lang('Follow-Ups Types'),
		'_CUSTOMER_RESOURCES' => $core->get_Lang('Customer resources'),
		'_SERVICES_TYPE' => $core->get_Lang('Services type')
	);
	foreach ($list_property_array as $property_type => $text) {
		$html .= '<div class="bg-lighter p-3 rounded-3 mb-3">
						<div class="form-row">
							<div class="col-12 col-md-2 mb-2 mb-lg-0">
								<div class="d-flex d-md-block d-xl-block">
									<h3 class="col-form-label mr-2 mr-lg-0">' . $text . '</h3>
									<button class="btn btn-outline-default mt-half" onClick="open_property(this, event)" toId="crm" property_id="0" property_type="' . $property_type . '">' . $core->makeIcon('plus-circle', $core->get_Lang("Addnew")) . '</button>
								</div>
							</div>
							<div class="col-12 col-md-10">
								<div class="holder_setting_property_' . $property_type . '">
									<div class="text-center p-3">
										Loading...
									</div>
								</div>
							</div>
						</div>
					</div>';
	}
	$html .= '
			</div>
		</div>
	</div>';
	echo $html . '|||' . implode('|', array_keys($list_property_array));
	die();
}
function default_save_setting(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID, $clsConfiguration;
	$action = Input::post('action', '_general');
	$msg = '_error';
	if ($action == '_general') {
		$clsConfiguration->updateValue('CRM_GoogleCalendarSysc', Input::post('CRM_GoogleCalendarSysc', 0));
		$clsConfiguration->updateValue('CRM_GoogleCalendarName', Input::post('CRM_GoogleCalendarName', 0));
		$clsConfiguration->updateValue('CRM_FollowUpRescheduleTemplate', Input::post('CRM_FollowUpRescheduleTemplate'));
		$clsConfiguration->updateValue('CRM_FollowUpTypeDefault', Input::post('CRM_FollowUpTypeDefault', 0));
		$clsConfiguration->updateValue('notification_content_assign_admin', Input::post('notification_content_assign_admin'));
	} else if ($action == '_cronjob') {
		$crm_cronjob_status = Input::post('crm_cronjob_status', 0);
		$crm_cronjob_command = Input::post('crm_cronjob_command');
		$clsConfiguration->updateValue('crm_cronjob_status', $crm_cronjob_status);
		$clsConfiguration->updateValue('crm_cronjob_command', $crm_cronjob_command);
		#Update Cron
		$cron_file = ABSPATH . "/inc/crontabs/crm.txt";
		$cmd = $crm_cronjob_command . " >/dev/null 2>&1";
		if ($crm_cronjob_status == 1) {
			// Setup the cron jobs (Evry 5 min by default)
			@exec('crontab -r', $crontab);
			$output = shell_exec('crontab -l');
			file_put_contents($cron_file, $output . $cmd . PHP_EOL);
			exec("crontab $cron_file");
		} else {
			@exec('crontab -l', $crontab);
			//Find command
			if (is_array($crontab)) {
				$key = array_search($cmd, $crontab);
				unset($crontab[$key]);
			}
			file_put_contents($cron_file, implode(PHP_EOL, $crontab));
			@exec("crontab $cron_file");
		}
	}
	echo '_success' . $a;
	die();
}
function default_load_propery(){
	global $smarty, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	$html = '<table class="table table-striped table_setting_property_' . $property_type . '" width="100%">
		<thead><tr>
			<th class="text-center" width="3%"></th>
			<th class="text-center" width="3%">No.</th>
			<th class="text-left" width="20%">' . $core->get_Lang('Name') . '</th>
			<th class="text-left">' . $core->get_Lang('Description') . '</th>
			<th class="text-left" width="15%">' . $core->get_Lang('Actions') . '</th>
		</tr></thead>
		<tbody class="tbody_setting_property_' . $property_type . '">';
	$list_property = $clsProperty->getAll("property_type='{$property_type}' order by order_no ASC");
	// $clsISO->print_pre($list_property); die();
	if (!empty($list_property)) {
		$ii = 0; // Init
		foreach ($list_property as $property) {
			$property_id = $property[$clsProperty->pkey];
			$props = 'property_id="' . $property_id . '" property_type="' . $property['property_type'] . '"';
			$editAction = '<button type="button" class="btn btn-sm btn-default" onClick="open_property(this,event)" toId="crm" ' . $props . '><i class="bx bx-pencil"></i></button>';
			$deleteAction = '<button type="button" class="btn btn-sm btn-danger" onClick="$Core.crm.delete_property(this,event)" ' . $props . '><i class="bx bx-trash"></i></button>';
			// Status
			$html .= '<tr id="' . $property_id . '">
					<td class="text-center mySortableHandler">' . $core->makeIcon('arrows') . '</td>
					<td class="text-center">' . ($ii + 1) . '</td>
					<td class="text-left">' . $clsProperty->getTitle($property_id) . '</td>
					<td class="text-left">' . $clsProperty->getIntro($property_id) . '</td>
					<td class="text-center">
						<div class="btn-group btn-group-sm ui-btn-group-custom">
							' . $statusAction . $editAction . $deleteAction . '
						</div>
					</td>
				</tr>';
			++$ii;
		}
	}
	$html .= '</tbody>
	</table>';
	// Output
	echo $html;
	die();
}
function default_delete_property(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	$property_id = (int) Input::post('property_id', 0);
	###
	$msg = "_error";
	if ($clsProperty->deleteOne($property_id)) {
		$msg = '_success';
	}
	// Return
	echo $msg;
	die();
}
function default_sync_order_property(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	$list_ids = Input::post('list_ids', array());
	// $clsISO->print_pre($list_ids); die();
	$msg = "_error";
	if (!empty($list_ids)) {
		$ii = 1;
		$msg = "_success";
		foreach ($list_ids as $property_id) {
			$clsProperty->updateOne($property_id, array(
				'order_no' => $ii
			));
			++$ii;
		}
	}
	// Return
	echo $msg;
	die();
}
function default_getSelectboxPropertyTypeCRM(){
	global $adminid, $core, $clsISO, $_company_iom_id;
	$CFG = new Configuration();
	$holderG = Input::post('holderG');
	if ($property_type == '_FOLLOWUP_TYPE') {
		$html = $clsISO->getSelectByPropertyTypeNotTitle($holderG, $CFG->getValue('CRM_FollowUpTypeDefault'));
	}
	echo '0$$$' . $html;
	die();
}
function default_ajManageCRMMassMail(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsProperty = new Property();
	$html = '<div class="mg-wrapper">
		<div class="box light">
			<style type="text/css">.btnSearchPotential{ cursor:pointer;}</style>
			<div class="box-title">
				<div class="caption mr10">
					' . CRM::renderHTMLButtonBack(array('page' => 'massmail', 'action' => 'manage')) . '
					<span class="uppercase">' . $core->get_Lang('MassMail') . '</span>
				</div>
				<div class="input-group fl" style="max-width:250px">
					<span class="input-group-addon btnSearchMassMail"><i class="fa fa-search"></i></span>
					<input type="text" class="form-control txtSearchMassMail" placeholder="' . $core->get_Lang('Search') . '" />
				</div>
				<div class="pull-right">
					' . ($clsISO->checkPermission('create_new_massmail') ? '
					<button class="iso-button ajOpenCRMMassMail" massmail_id="0">' . $core->makeIcon('plus-circle', $core->get_Lang("Addnew")) . '</button>' : '') . '
				</div>
			</div>
			<div class="box-body" style="min-height:600px">
				<div id="holderCRMMassMail" class="holderCRMMassMail"></div>
			</div>
		</div>
	</div>';
	// Output
	echo ($html);
	die();
}
function default_ajLoadListCRMMassMail(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsProperty = new Property();
	$clsCRMMassMail = new CRMMassMail();
	$html = '<table class="table table-striped table-hover table-responsive" width="100%" cellpadding="2" cellspacing="2" border="0">
	<thead><tr>
		<th width="3%">No.</th>
		<th width="15%">' . $core->get_Lang('Date') . '</th>
		<th>' . $core->get_Lang('Description') . '</th>
		<th width="10%">' . $core->get_Lang('Type') . '</th>
		<th width="10%">' . $core->get_Lang('Target') . '</th>
		<th width="10%" class="text-center">' . $core->get_Lang('Total') . '</th>
		<th width="10%" class="text-center">' . $core->get_Lang('AlreadySent') . '</th>
		<th width="10%" class="text-center">' . $core->get_Lang('UnSent') . '</th>
		<th width="10%" class="text-center">' . $core->get_Lang('Status') . '</th>
		<th class="text-center" width="7%">' . $core->get_Lang('_Actions') . '</th>
	</tr></thead>';
	$currentPage = (int) Input::post('currentPage', 1);
	$number_per_page = (int) Input::post('number_per_page', 20);
	$cond = "is_trash=0 and user_id='{$adminid}'";
	$totalRecord = $clsCRMMassMail->countItem($cond);
	$totalPage = ceil($totalRecord / $number_per_page);
	$offset = ($currentPage - 1) * $number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	$keySearch = Input::post('keySearch');
	if (!empty($keySearch) && $keySearch != '0') {
		$cond .= " and (subject like '%" . $keySearch . "%' 
			or description like '%" . $keySearch . "%'
		)";
	}
	$lstMassMail = $clsCRMMassMail->GetAll("{$cond} order by reg_date DESC" . $limitCond);
	if (!empty($lstMassMail)) {
		$ii = 1;
		foreach ($lstMassMail as $massmail) {
			$massmail_id = $massmail[$clsCRMMassMail->pkey];
			$props = 'massmail_id="' . $massmail_id . '"';
			$html .= '<tr>
				<td data-label="No." class="text-center">' . $ii . '</td>
				<td data-label="' . $core->get_Lang('Date') . '">' . $clsISO->convertTimeToText($massmail['date_id'], true) . '</td>
				<td data-label="' . $core->get_Lang('Description') . '">' . $massmail['description'] . '</td>
				<td data-label="' . $core->get_Lang('Type') . '">' . $clsCRMMassMail->getMailType($massmail['message_type']) . '</td>
				<td data-label="' . $core->get_Lang('Target') . '">' . $clsCRMMassMail->getTargetType($massmail['target_type']) . '</td>
				<td data-label="' . $core->get_Lang('Total') . '" class="text-center">' . $clsCRMMassMail->getTotalItem($massmail_id) . '</td>
				<td data-label="' . $core->get_Lang('AlreadySent') . '" class="text-center">' . $clsCRMMassMail->getTotalSend($massmail_id) . '</td>
				<td data-label="' . $core->get_Lang('UnSent') . '" class="text-center">' . $clsCRMMassMail->getTotalQueue($massmail_id) . '</td>
				<td data-label="' . $core->get_Lang('Status') . '" class="text-center">' . $clsCRMMassMail->getStatus($massmail_id, $massmail) . '</td>
				<td data-label="' . $core->get_Lang('_Actions') . '" class="text-center">
					<div class="dropdown dropdown-action">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
						</a>
						<ul class="dropdown-menu icon">
							' . ($clsISO->checkPermission('view_massmail') ? '<li><a class="ajViewCRMMassMail" ' . $props . '>' . $core->makeIcon('eye') . ' ' . $core->get_Lang('View') . '</a></li>' : '') . '
							' . ($clsISO->checkPermission('edit_massmail') ? '<li><a class="ajOpenCRMMassMail" ' . $props . '>' . $core->makeIcon('pencil') . ' ' . $core->get_Lang('Edit') . '</a></li>' : '') . '
							' . ($clsISO->checkPermission('delete_massmail') ? '<li><a class="ajDeleteCRMMassMail" ' . $props . '>' . $core->makeIcon('trash') . ' ' . $core->get_Lang('Delete') . '</a></li>' : '') . '
						</ul>
					</div>
				</td>
			</tr>';
			++$ii;
		}
	} else {
		$html .= '<tr>
			<td class="text-center" colspan="10">
				' . CRM::renderHTMLNoDocument('Not any records') . '
			</td>
		</tr>';
	}
	$html .= '</table>';
	if ($totalPage > 0) {
		$html .= '<div class="easyui-pagination" id="PageCRMMassMail" pageNumber="' . $currentPage . '" pageList="[10,20,30,50]"></div>';
	}
	// output
	echo json_encode(array(
		'html'	=> $html,
		'currentPage'	=> $currentPage,
		'number_per_page'	=> $number_per_page,
		'totalRecord'	=> $totalRecord,
		'totalPage'	=> $totalPage
	));
	die();
}
function default_ajOpenCRMMassMail(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$user_id = $adminid;
	$clsProperty = new Property();
	$clsCRMMailbox = new CRMMailbox();
	$clsCRMMassMail = new CRMMassMail();
	$massmail_id = (int) Input::post('massmail_id', 0);
	$arrTargetType = $clsCRMMassMail->getListType();
	$arrMsgType = $clsCRMMassMail->getListMsgType();
	$oneMassMail = array(
		'mail_from'	=> 'system',
		'time_delay'	=> 10,
		'time_unit'	=> 'second'
	);
	$arrCustomerGroup = array();
	$arrCampaign = array();
	$target_type = 'customer';
	if ($massmail_id > 0) {
		$oneMassMail = $clsCRMMassMail->GetOne($massmail_id);
		$target_type = $oneMassMail['target_type'];
		if ($target_type == 'customergroup') {
			$arrCustomerGroup = @json_decode($oneMassMail['target_id'], true);
		} else if ($target_type == 'campaign') {
			$arrCampaign = @json_decode($oneMassMail['target_id'], true);
		}
	}
	// Option mailbox
	$htmlMailbox = sprintf('<option value="0">%s</option>', $core->get_Lang('Select mailbox'));
	$lstMailbox = $clsCRMMailbox->GetAll("user_id='{$adminid}'");
	if (!empty($lstMailbox)) {
		foreach ($lstMailbox as $mailbox) {
			$htmlMailbox .= '<option>' . $mailbox['name'] . '</option>';
		}
		unset($lstMailbox);
	}
	$html = '<div class="mg-wrapper">
		<form method="post" action="" enctype="multipart/form-data" id="frmMassMail_' . $massmail_id . '">
			<div class="box light">
				<div class="box-title">
					' . CRM::renderHTMLButtonBack(array('page' => 'massmail', 'action' => 'edit')) . '
					<div class="caption">
						<span class="uppercase">' . $core->get_Lang('NewMassMessage') . '</span>
					</div>
				</div>
				<div class="box-body form-horizontal">
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>' . $core->get_Lang('SendTo') . '</h5></div>
							<div class="col-md-9">
								<select class="form-control" name="target_type">
									' . CRM::getFORMSelectOptionsAdvanced(($massmail_id > 0 ? $oneMassMail['target_type'] : null), $arrTargetType) . '
								</select>
								<small class="help-block">' . $core->get_Lang('Message will be sent to all active clients in the system') . '</small>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>' . $core->get_Lang('MessageType') . '</h5></div>
							<div class="col-md-9">
								<select class="form-control" name="message_type">
									' . CRM::getFORMSelectOptionsAdvanced(($massmail_id > 0 ? $oneMassMail['message_type'] : null), $arrMsgType) . '
								</select>
								<small class="help-block">' . $core->get_Lang('This message will be sent in form of an email') . '</small>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right">
								<h5>' . $core->get_Lang('Subject') . ' <span class="required">*</span></h5>
							</div>
							<div class="col-md-9">
								<input type="text" class="form-control" name="subject" value="' . $oneMassMail['subject'] . '" />
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>' . $core->get_Lang('Date') . '</h5></div>
							<div class="col-md-9">
								<input type="text" class="form-control datepicker pull-left mr-half" readonly="readonly" value="' . $clsISO->convertTimeToText(($massmail_id > 0 ? $oneMassMail['date_id'] : time())) . '" name="date_id" />
								<input type="text" class="form-control timepicker" name="time" value="' . ($massmail_id > 0 ? $oneMassMail['time'] : date('H:i S')) . '" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2 col-md-custom-2 text-right">
							<h5>' . $core->get_Lang('Description') . ' <span class="required">*</span></h5>
						</div>
						<div class="col-md-10 col-md-custom-10">
							<input type="text" class="form-control" name="description" value="' . $oneMassMail['description'] . '" />
						</div>
					</div>
					<div class="form-group form-group-general form-group-customergroup" ' . ($target_type == 'customergroup' ? '' : 'style="display:none"') . '>
						<div class="col-md-2 col-md-custom-2 text-right">
							<h5>' . $core->get_Lang('CustomerGroup') . ' <span class="required">*</span></h5>
						</div>
						<div class="col-md-10 col-md-custom-10">
							<select class="form-control" multiple="multiple" name="customergroups[]" style="height:60px">';
	$lstCustomerGroup = $clsProperty->GetAll("is_trash=0 and parent_id='0' and property_type='LOAIKHACHHANG' 
							order by order_no ASC", $clsProperty->pkey);
	if (!empty($lstCustomerGroup)) {
		foreach ($lstCustomerGroup as $property) {
			$pop_id = $property[$clsProperty->pkey];
			$sltc = in_array($pop_id, $arrCustomerGroup) ? 'selected="selected"' : '';
			$html .= '<option value="' . $pop_id . '" ' . ($sltc) . '>' . $clsProperty->getTitle($pop_id) . '</option>';
		}
		unset($lstCustomerGroup);
	}
	$html .= '
							</select>
						</div>
					</div>
					<div class="form-group form-group-general form-group-campaign" ' . ($target_type == 'campaign' ? '' : 'style="display:none"') . '>
						<div class="col-md-2 col-md-custom-2 text-right">
							<h5>' . $core->get_Lang('Campaigns') . ' <span class="required">*</span></h5>
						</div>
						<div class="col-md-10 col-md-custom-10">
							<select class="form-control" name="campaigns[]" multiple="multiple" style="height:60px">';
	$clsBusinessCampaign = new BusinessCampaign();
	$lstCampain = $clsBusinessCampaign->GetAll("admin_list like '%|{$adminid}|%' and type='CRM' order by reg_date DESC");
	if (!empty($lstCampain)) {
		foreach ($lstCampain as $campain) {
			$business_campaign_id = $campain[$clsBusinessCampaign->pkey];
			$sltc = in_array($business_campaign_id, $arrCampaign) ? 'selected="selected"' : '';
			$html .= '<option value="' . $business_campaign_id . '" ' . $sltc . '>' . $campain['title'] . '</option>';
		}
		unset($lstCampain);
	}
	$html .= '
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right">
								<h5>' . $core->get_Lang('Email from') . '</h5>
							</div>
							<div class="col-md-5" style="margin-top:5px">
								<label class="mr-half"><input type="radio" style="vertical-align:-2px" name="mail_from" value="system" ' . ($oneMassMail['mail_from'] == 'system' ? 'checked' : '') . ' /> ' . $core->get_Lang('System') . '</label>
								<label><input type="radio" style="vertical-align:-2px" name="mail_from" value="personal" ' . ($oneMassMail['mail_from'] == 'personal' ? 'checked' : '') . ' /> ' . $core->get_Lang('Personal') . '</label>
							</div>
							<div class="col-md-4 mailbox ' . ($oneMassMail['mail_from'] == 'system' ? ' hidden' : '') . '">
								<select name="mailbox_id" class="form-control">
									' . $htmlMailbox . '
								</select>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>' . $core->get_Lang('TimeDelay') . '</h5></div>
							<div class="col-md-3">
								<select class="form-control" name="time_delay">
									' . $clsISO->getSelect(10, 60, $oneMassMail['time_delay']) . '
								</select>
							</div>
							<div class="col-md-3">
								<select name="time_unit" class="form-control">
									<option value="hour" ' . ($oneMassMail['time_unit'] == 'hour' ? 'selected' : '') . '>' . $core->get_Lang('Hours') . '</option>
									<option value="minute" ' . ($oneMassMail['time_unit'] == 'minute' ? 'selected' : '') . '>' . $core->get_Lang('Minutes') . '</option>
									<option value="second" ' . ($oneMassMail['time_unit'] == 'second' ? 'selected' : '') . '>' . $core->get_Lang('Seconds') . '</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7">
							<div class="form-group no-margin">
								<h5>' . $core->get_Lang('Content') . '<span class="required">*</span></h5>
								<textarea name="content" class="form-control" cols="255" rows="20" id="mceFull$core->get_Lang' . $massmail_id . '">' . $oneMassMail['content'] . '</textarea>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group no-margin">
								<h5>' . $core->get_Lang('Available Merge Fields') . '</h5>
								<style type="text/css">
									.tmslist{ display:block; margin:0; padding:0}
									.tmslist > li{ display:inline-block; width:100%; padding:5px 0px; cursor:pointer;}
									.tmslist > li .pleft{ width:38%; float:left; padding:0% 1%;}
									.tmslist > li .pright{ width:58%; float:right; padding:0% 1%;}
								</style>
								<div class="box light bordered">
									<div class="box-title">
										<span class="caption uppercase">' . $core->get_Lang('AssignedClient') . '</span>
										<a href="javascript:void(0);" class="btn-outline pull-right hidebox"><i class="fa fa-compress"></i></a>
									</div>
									<div class="box-body">
										<ul class="tmslist holderCRMMassMailClientVariable$core->get_Lang' . $massmail_id . '">';
	$lstClientVariable = $clsCRMMassMail->getListClientVariable();
	if ($massmail_id > 0 && $oneMassMail['target_type'] == 'campaign') {
		$lstClientVariable = $clsCRMMassMail->getListContactVariable();
	}
	foreach ($lstClientVariable as $kp => $vp) {
		$html .= '<li class="cmd" cmd="' . $kp . '">
												<div class="pleft">' . $kp . '</div>
												<div class="pright">' . $vp . '</div>
											</li>';
	}
	$html .= '</ul>
									</div>
								</div>
								<div class="box light hide bordered">
									<div class="box-title">
										<span class="caption uppercase">' . $core->get_Lang('SystemVariable') . '</span>
										<a href="javascript:void(0);" class="btn-outline pull-right hidebox"><i class="fa fa-compress"></i></a>
									</div>
									<div class="box-body">
									<ul class="tmslist">';
	$lstSystemVariable = $clsCRMMassMail->getListSystemVariable();
	foreach ($lstSystemVariable as $kp => $vp) {
		$html .= '<li class="cmd" cmd="' . $kp . '">
												<div class="pleft">' . $kp . '</div>
												<div class="pright">' . $vp . '</div>
											</li>';
	}
	$html .= '
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box-end text-center" style="background:#">
					<button class="iso-button saveCRMMassMail" massmail_id="' . $massmail_id . '">
						' . $core->get_Lang($massmail_id > 0 ? 'Update' : 'Add') . '
					</button>
				</div>
			</div>
		</form>
	</div>';
	// output
	echo $html;
	die();
}
function default_ajSaveCRMMassMail(){
	global $adminid, $core, $dbconn, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsProperty = new Property();
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent();
	$clsPotential = new Potential();
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	$massmail_id = (int) Input::post('massmail_id', 0);
	// Delete
	if (Input::exists('action', 'GET') && Input::get('action') == 'delete') {
		$subject = $clsCRMMassMail->GetOneField("subject", $massmail_id);
		if ($clsCRMMassMail->deleteOne($massmail_id)) {
			// Delete mass mail item sent logs
			$clsCRMMassMailSent = new CRMMassMailSent();
			$clsCRMMassMailSent->deleteByCond("massmail_id='{$massmail_id}'");
			// Logs
			$log_message = 'Mass mail has been deleted #' . $massmail_id . ' ' . $subject;
			$clsISO->logs($clsCRMMassMail->tbl, $clsCRMMassMail->pkey, $massmail_id, $log_message, 'CRM');
		}
		echo ($massmail_id);
		die();
	}
	// List ID
	$target_id = array();
	$target_type = Input::post('target_type');
	if ($target_type == 'customergroup') {
		$target_id = Input::post('customergroups');
	} else if ($target_type == 'campaign') {
		$target_id = Input::post('campaigns');
	}
	// Date ID
	$date_id = Input::post('date_id');
	$time = Input::post('time');
	$datetime = $clsISO->convertTextToTime($date_id, $time);
	//Time delay
	$time_unit = Input::post('time_unit', 'second');
	$time_delay = (int) Input::post('time_delay', 10);
	#---
	if ($massmail_id > 0) {
		$changed = false;
		if ($clsCRMMassMail->getOneField('date_id', $massmail_id) != $datetime) {
			$changed = true;
		}
		if ($changed == false && $target_type != $clsCRMMassMail->getOneField('target_type', $massmail_id)) {
			$changed = true;
		} else {
			if ($target_type == 'campaign' && $changed == false) {
				$target_old_id = $clsCRMMassMail->getOneField('target_id', $massmail_id);
				$target_old_id = !empty($target_old_id) ? json_decode($target_old_id, true) : array();
				if (!CRM::array_equal($target_old_id, $target_id)) {
					$changed = true;
				}
			}
		}
		// Valid change time_delay
		if ($changed == false) {
			if ($time_delay != $clsCRMMassMail->getOneField('time_delay', $massmail_id)) {
				$changed = true;
			}
		}
		if ($changed == false) {
			if ($time_unit != $clsCRMMassMail->getOneField('time_unit', $massmail_id)) {
				$changed = true;
			}
		}
		#
		if ($clsCRMMassMail->updateOne($massmail_id, array(
			'target_type'	=> $target_type,
			'target_id'	=> @json_encode($target_id),
			'message_type'	=> Input::post('message_type'),
			'subject'	=> Input::post('subject'),
			'description'	=> Input::post('description'),
			'content'	=> Input::post('content'),
			'date_id'	=> $datetime,
			'time'	=> $time,
			'mail_from'	=> Input::post('mail_from'),
			'mailbox_id'	=> Input::post('mailbox_id'),
			'time_delay'	=> Input::post('time_delay'),
			'time_unit'	=> Input::post('time_unit'),
			'upd_date'	=> time(),
			'user_id_update'	=> $adminid
		))) {
			if ($changed) {
				$clsCRMMassMailSent->deleteByCond("massmail_id='{$massmail_id}'");
				if ($time_unit == 'minute') {
					$time_delay = $time_delay * 60;
				} else if ($time_unit == 'hour') {
					$time_delay = $time_delay * 60 * 60;
				}
				if ($target_type == 'campaign') {
					$lstCampaign = $target_id;
					if (!empty($lstCampaign)) {
						$arrPotential = array();
						foreach ($lstCampaign as $campaign_id) {
							$lstCampaignPotential = $dbconn->GetAll("select t1.potential_id,t1.email from " . DB_PREFIX . "potential as t1 
							inner join " . $clsBusinessCampaignPotential->tbl . " as t2 on t1.potential_id=t2.potential_id 
							where t1.is_trash=0 and t2.business_campaign_id='{$campaign_id}' order by t1.reg_date ASC");
							if (!empty($lstCampaignPotential)) {
								$i = 0;
								foreach ($lstCampaignPotential as $potential) {
									$potential_id = $potential[$clsPotential->pkey];
									$to_email = $potential['email'];
									if ($clsISO->is_valid_email($to_email)) {
										$arrPotential[] = array(
											'potential_id'	=> $potential_id,
											'to_email'	=> $to_email
										);
									}
								}
								unset($lstCampaignPotential);
							}
						}
						if (!empty($arrPotential)) {
							$ii = 0;
							foreach ($arrPotential as $potential) {
								$tp = 'potential';
								$clsCRMMassMailSent->insert(array(
									'id'	=> $clsCRMMassMailSent->getMaxId(),
									'tp'	=> $tp,
									'massmail_id'	=> $massmail_id,
									'user_id'	=> $adminid,
									'company_id' => $potential['potential_id'],
									'to_email'	=> $potential['to_email'],
									'date_id'	=> $datetime + ($time_delay * $ii),
									'reg_date'	=> time()
								));
								++$ii;
							}
						}
						unset($arrPotential);
					}
				} else {
					if ($target_type == 'customergroup') {
						$lstCustomerGroup = $target_id;
						$lstCompany = $clsCompany->GetAll("is_trash=0 and customer_type in (" . implode(',', $lstCustomerGroup) . ")");
					} else {
						$lstCompany = $clsCompany->GetAll("is_trash=0");
					}
					if (!empty($lstCompany)) {
						$arrCompany = array();
						foreach ($lstCompany as $company) {
							$company_id = $company[$clsCompany->pkey];
							$to_email = $company['email'];
							if ($clsISO->is_valid_email($email)) {
								$arrCompany[] = array(
									'company_id'	=> $company_id,
									'to_email'	=> $to_email
								);
							}
						}
						if (!empty($arrCompany)) {
							$ii = 0;
							foreach ($arrCompany as $company) {
								$tp = 'company';
								$clsCRMMassMailSent->insert(array(
									'id'	=> $clsCRMMassMailSent->getMaxId(),
									'tp'	=> $tp,
									'massmail_id'	=> $massmail_id,
									'user_id'	=> $adminid,
									'company_id'	=> $company['company_id'],
									'to_email'	=> $company['to_email'],
									'date_id'	=> $datetime + ($time_delay * $ii),
									'reg_date'	=> time()
								));
								++$ii;
							}
						}
						unset($lstCompany);
					}
				}
			}
		}
	} else {
		$massmail_id = $clsCRMMassMail->getMaxId();
		if ($clsCRMMassMail->insert(array(
			'id'	=> $massmail_id,
			'target_type'	=> $target_type,
			'target_id'	=> @json_encode($target_id),
			'message_type'	=> Input::post('message_type'),
			'subject'	=> Input::post('subject'),
			'description'	=> Input::post('description'),
			'content'		=> Input::post('content'),
			'mail_from'		=> Input::post('mail_from'),
			'mailbox_id'	=> Input::post('mailbox_id'),
			'time_delay'	=> Input::post('time_delay'),
			'time_unit'	=> Input::post('time_unit'),
			'date_id'	=> $datetime,
			'time'	=> $time,
			'reg_date'	=> time(),
			'upd_date'	=> time(),
			'user_id'	=> $adminid,
			'user_id_update'	=> $adminid
		))) {
			if ($time_unit == 'minute') {
				$time_delay = $time_delay * 60;
			} else if ($time_unit == 'hour') {
				$time_delay = $time_delay * 60 * 60;
			}
			if ($target_type == 'campaign') {
				$lstCampaign = $target_id;
				if (!empty($lstCampaign)) {
					$arrPotential = array();
					foreach ($lstCampaign as $campaign_id) {
						$lstCampaignPotential = $dbconn->GetAll("select t1.potential_id,t1.email from " . DB_PREFIX . "potential as t1 
						inner join " . $clsBusinessCampaignPotential->tbl . " as t2 on t1.potential_id=t2.potential_id 
						where t1.is_trash=0 and t2.business_campaign_id='{$campaign_id}' order by t1.reg_date ASC");
						if (!empty($lstCampaignPotential)) {
							$i = 0;
							foreach ($lstCampaignPotential as $potential) {
								$potential_id = $potential[$clsPotential->pkey];
								$to_email = $potential['email'];
								if ($clsISO->is_valid_email($to_email)) {
									$arrPotential[] = array(
										'potential_id'	=> $potential_id,
										'to_email'	=> $to_email
									);
								}
							}
							unset($lstCampaignPotential);
						}
					}
					if (!empty($arrPotential)) {
						$ii = 0;
						foreach ($arrPotential as $potential) {
							$tp = 'potential';
							$clsCRMMassMailSent->insert(array(
								'id'	=> $clsCRMMassMailSent->getMaxId(),
								'tp'	=> $tp,
								'massmail_id'	=> $massmail_id,
								'user_id'	=> $adminid,
								'company_id'	=> $potential['potential_id'],
								'to_email'	=> $potential['to_email'],
								'date_id'	=> $datetime + ($time_delay * $ii),
								'reg_date'	=> time()
							));
							++$ii;
						}
					}
					unset($arrPotential);
				}
			} else {
				if ($target_type == 'customergroup') {
					$lstCustomerGroup = $target_id;
					$lstCompany = $clsCompany->GetAll("is_trash=0 and customer_type in (" . implode(',', $lstCustomerGroup) . ")");
				} else {
					$lstCompany = $clsCompany->GetAll("is_trash=0");
				}
				if (!empty($lstCompany)) {
					$arrCompany = array();
					foreach ($lstCompany as $company) {
						$company_id = $company[$clsCompany->pkey];
						$to_email = $company['email'];
						if ($clsISO->is_valid_email($email)) {
							$arrCompany[] = array(
								'company_id'	=> $company_id,
								'to_email'	=> $to_email
							);
						}
					}
					if (!empty($arrCompany)) {
						$ii = 0;
						foreach ($arrCompany as $company) {
							$tp = 'company';
							$clsCRMMassMailSent->insert(array(
								'id'	=> $clsCRMMassMailSent->getMaxId(),
								'tp'	=> $tp,
								'massmail_id'	=> $massmail_id,
								'user_id'	=> $adminid,
								'company_id'	=> $company['company_id'],
								'to_email'	=> $company['to_email'],
								'date_id'	=> $datetime + ($time_delay * $ii),
								'reg_date'	=> time()
							));
							++$ii;
						}
					}
					unset($lstCompany);
				}
			}
		}
	}
	// output
	echo ($massmail_id);
	die();
}
function default_open_view_massmail_detail(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent();
	$massmail_id = (int) Input::post('massmail_id', 0);
	if (!$massmail_id) {
		echo '_invalid';
		die();
	}
	$totalItem = $clsCRMMassMail->getTotalItem($massmail_id); // Total
	$totalSent = $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}' and mark_sent=1"); // Sent
	$totalRead = $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}' and mark_read=1"); // Read
	#
	$html = '<div class="modal right fade" id="OpenMassMail_' . $massmail_id . '" tabindex="-1" role="dialog" aria-labelledby="OpenMassMail_' . $massmail_id . '">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<button type="button" class="close closeEv" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="modal-header">
					<div class="links-bar-create-edit">
						<div class="profile-photo-create-edit">
							<img src="' . _ICON_GENERAL . '" width="48px" />
						</div>
					</div>
					<div class="head">
						<div class="subtitle">' . $core->get_Lang('Massmail') . '</div>
						<div class="title">' . $clsCRMMassMail->getOneField('subject', $massmail_id) . '</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="main-container">
						<div class="activity-stats" data-total-count="2">
							<div class="block-item">
								<div class="top-label">' . $core->get_Lang('Total') . '</div>
								<div class="bold-value">' . $totalItem . '</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width: 0%;"></div>
								</div>
							</div>
							<div class="block-item" title="1 Emails">
								<span class="top-label">' . $core->get_Lang('AlreadySent') . '</span>
								<div class="block-item-count">' . $totalSent . '</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:' . (($totalSent / $totalItem) * 100) . '%;"></div>
								</div>
							</div>
							<div class="block-item" title="0 Tasks">
								<span class="top-label">' . $core->get_Lang('UnSent') . '</span>
								<div class="block-item-count">' . ($totalItem - $totalSent) . '</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:' . ((($totalItem - $totalSent) / $totalItem) * 100) . '%;"></div>
								</div>
							</div>
							<div class="block-item" title="0 Events">
								<span class="top-label">' . $core->get_Lang('AlreadyRead') . '</span>
								<div class="block-item-count">' . $totalRead . '</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:' . (($totalRead / $totalItem) * 100) . '%;"></div>
								</div>
							</div>
							<div class="block-item">
								<div class="top-label">' . $core->get_Lang('UnRead') . '</div>
								<div class="bold-value">' . ($totalItem - $totalRead) . '</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:' . ((($totalItem - $totalRead) / $totalItem) * 100) . '%;"></div>
								</div>
							</div>
						</div>
						<div class="panel panel-bordered related-list panel-upcoming-activities">
							<div class="panel-heading">
								<h3 class="panel-title bold">' . $core->get_Lang('List sent') . '</div>
							</div>
							<div class="panel-content">
								<div class="holderMassMailSentItem_' . $massmail_id . '">
									<div class="loading-msg">
										' . $core->get_Lang('Loading') . '..
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	// Output
	echo $html;
	die();
}
function default_load_list_massmail_sent(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsVS_Client = new VS_Client();
	$clsPotential = new Potential();
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent();
	$massmail_id = (int) Input::post('massmail_id', 0);
	$html = '<table border="0" class="table table-striped table-responsive">
		<thead><tr>
			<th width="5%">No.</th>
			<th>' . $core->get_Lang('Name') . ' E-mail</th>
			<th>' . $core->get_Lang('To') . ' E-mail</th>
			<th>' . $core->get_Lang('Date') . '</th>
			<th>' . $core->get_Lang('Status') . '</th>
			<th>' . $core->get_Lang('TimeSent') . '</th>
			<th>' . $core->get_Lang('Read') . '</th>
			<th>' . $core->get_Lang('TimeRead') . '</th>
		</tr></thead>';
	$lstMassMailSent = $clsCRMMassMailSent->GetAll("massmail_id='{$massmail_id}' order by date_id asc");
	if (!empty($lstMassMailSent)) {
		$ii = 1; // Init
		foreach ($lstMassMailSent as $item) {
			if ($item['tp'] == 'potential') {
				$company = $clsPotential->getName($item['company_id']);
			} else {
				$company = $clsVS_Client->getIdentity($item['company_id']);
			}
			$html .= '<tr>
					<th data-label="No." width="5%">' . $ii . '</th>
					<td data-label="' . $core->get_Lang('Name') . '">' . $company . '</td>
					<td data-label="' . $core->get_Lang('To') . '">' . $item['to_email'] . '</td>
					<td data-label="' . $core->get_Lang('Date') . '">' . $clsISO->convertTimeToTextOrigin($item['date_id'], true) . '</td>
					<td data-label="' . $core->get_Lang('Status') . '">' . ($item['mark_sent'] ? $core->get_Lang('AlreadySent') : $core->get_Lang('Unsent')) . '</td>
					<td data-label="' . $core->get_Lang('TimeSent') . '">' . (!empty($item['time_sent']) ? $clsISO->convertTimeToText($item['time_sent'], true) : '') . '</td>
					<td data-label="' . $core->get_Lang('Read') . '">' . ($item['mark_read'] ? $core->get_Lang('AlreadyRead') : $core->get_Lang('UnRead')) . '</td>
					<td data-label="' . $core->get_Lang('TimeRead') . '">' . (!empty($item['time_sent']) ? $clsISO->convertTimeToText($item['time_read'], true) : '') . '</td>
				</tr>';
			++$ii;
		}
	} else {
		$html .= '<tr>
				<td class="text-center" colspan="8">
					' . CRM::renderHTMLNoDocument('Not any foolow-Ups') . '
				</div>
			</tr>';
	}
	$html .= '</table>';
	// Output
	echo $html;
	die();
}
function default_ajLoadCRMCampainVariable(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsCRMMassMail = new CRMMassMail();
	$target_type = Input::post('target_type', 'customer');
	$html = '';
	if ($target_type == 'customer' || $target_type == 'customergroup') {
		$lstClientVariable = $clsCRMMassMail->getListClientVariable();
	} else {
		$lstClientVariable = $clsCRMMassMail->getListContactVariable();
	}
	foreach ($lstClientVariable as $kp => $vp) {
		$html .= '<li class="cmd" cmd="' . $kp . '">
			<div class="pleft">' . $kp . '</div>
			<div class="pright">' . $vp . '</div>
		</li>';
	}
	echo $html;
	die();
}
function default_ajConfigCRMCronjob(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsCronjobs = new Cronjobs();
	$status = Input::post('status', 0);
	$msg = '_error';
	$cron_file = 'cronjobs/CRM.php';
	$cronjobs_id = $clsCronjobs->getId($cron_file);
	if ($clsCronjobs->updateOne($cronjobs_id, array(
		'is_active'	=> $status
	))) {
		$msg = '_success';
		$clsCronjobs->run();
	}
	echo ($msg);
	die();
}
function default_ajSaveCRMCronjob(){
	global $adminid, $core, $clsISO;
	if ($clsISO->validateLoginInformation() == 0) {
		echo ('');
		die();
	}
	$clsCronjobs = new Cronjobs();
	$cron_file = 'cronjobs/CRM.php';
	$cronjobs_id = Input::post('cronjobs_id', 0);
	if (intval($cronjobs_id) == 0) {
		$cronjobs_id = $clsCronjobs->getId($cron_file);
	}
	$msg = '_error';
	if ($clsCronjobs->updateOne($cronjobs_id, array(
		'minute'		=> Input::post('minute', '59'),
		'hour'			=> Input::post('hour', '23'),
		'month'			=> Input::post('month', '*'),
		'dayofmonth'	=> Input::post('dayofmonth', '*'),
		'dayofweek'		=> Input::post('dayofweek', '*'),
		'user_id_update' => $adminid,
		'upd_date'	=> time()
	))) {
		$msg = '_success';
		$clsCronjobs->run();
	}
	echo ($msg);
	die();
}
function default_ajChangeTableValue(){
	global $core, $_frontIsLoggedin_user_id, $clsISO, $profile_id;
	#
	if (!in_array($profile_id, _PROFILE_CRM_SUPER_ID)) {
		echo json_encode(array('error' => 1, 'message' => 'Khong co quyen.'));
		die();
	}
	$html = '';
	$tbl = Input::post('tbl', '');
	$pval = (int) Input::post('pval', 0);
	$ipn = Input::post('ipn', 'text');
	$field_id = Input::post('field_id', '');
	if (!preg_match('/^[a-zA-Z0-9_]+$/', $field_id)) {
		echo json_encode(array('error' => 1, 'message' => 'Truong khong hop le.'));
		die();
	}
	if (!class_exists($tbl) || !is_subclass_of($tbl, 'dbBasic')) {
		echo json_encode(array('error' => 1, 'message' => 'Bang khong hop le.'));
		die();
	}
	if ($ipn == 'number') {
		$value = $clsISO->processSmartNumber($_POST['value']);
	} else if ($ipn == 'date') {
		$value = strtotime($_POST['value']);
	} else {
		$value = $_POST['value'];
	}
	$clsClassTable = new $tbl;
	$clsClassTable->updateOne($pval, $field_id . "='" . addslashes($value) . "'");
	#
	echo ($value);
	die();
}
function default_open_contact(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	##
	$contact_id = (int) Input::post('contact_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	##
	$action = '_add';
	$oneContact = array();
	if ($contact_id > 0) {
		$action = '_edit';
		$oneContact = $clsContact->getOne($contact_id);
	}
	$smarty->assign('action', $action);
	$smarty->assign('contact_id', $contact_id);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('oneContact', $oneContact);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.contact.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	));
	die();
}
function default_save_contact(){
	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO, $profile_id;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	//$clsISO->print_pre($clsCustomer); die();
	$contact_id = (int) Input::post('contact_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	###
	$name = Input::post('name');
	$address = Input::post('address');
	$phone = Input::post('phone');
	$email = Input::post('email');
	$notes = Input::post('notes');
	$role_id = (int) Input::post('role_id', 0);
	###
	$msg = "_error";
	if ($contact_id > 0) {
		//$clsCustomer->setDebug(true);
		if ($clsContact->updateOne($contact_id, array(
			'name' => $name,
			'name_slug' => $core->replaceSpace($name),
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))) {
			$msg = "_success";
		}
	} else {
		//$clsCustomer->setDebug(true);
		$contact_id = $clsContact->getMaxId();
		if ($clsContact->insert(array(
			$clsContact->pkey => $contact_id,
			'customer_id' => $customer_id,
			'name' => $name,
			'name_slug' => $core->replaceSpace($name),
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		))) {
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'contact_id' => $contact_id
	));
	die();
}
function default_delete_contact(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	##
	$customer_id = (int) Input::post('customer_id', 0);
	$contact_id = (int) Input::post('contact_id', 0);
	##
	$msg = "_error";
	if ($clsContact->deleteOne($contact_id)) {
		$msg = '_success';
	}
	// Return
	echo $msg;
	die();
}
function default_load_contact(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	##
	$customer_id = (int) Input::post('customer_id', 0);
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 15);
	##
	$cond = "is_trash=0 and customer_id='{$customer_id}' order by reg_date DESC";
	$total_record = $clsContact->countItem($cond);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	##
	$list_contacts = $clsContact->getAll($cond . $limitCond);
	// $clsISO->print_pre($list_contacts); die();
	$smarty->assign('list_contacts', $list_contacts);
	$smarty->assign('customer_id', $customer_id);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.contact.tpl');
	echo json_encode(array(
		'html' => $html,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_record' => $total_record,
		'total_page' => $total_page
	));
	die();
}
function default_open_followups(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $profile_id, $clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	##
	$followup_id = (int) Input::post('followup_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	$action = '_add';
	$oneFollowUp = array(
		'type_id' => 0,
		'status_id' => 0,
		'date_id' => time(),
		'priority_id' => 0,
		'reminder_before' => 0,
		'reminder_unit' => 0,
		'admin_id' => $profile_id,
		'intro' => 0
	);
	$arr_selected_users = array();
	if ($followup_id > 0) {
		$action = '_edit';
		$oneFollowUp = $clsFollowUp->getOne($followup_id);
		$arr_selected_users = $clsISO->getArrayByTextSlash($oneFollowUp['list_user_slash']);
	}
	$smarty->assign('action', $action);
	$smarty->assign('followup_id', $followup_id);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('oneFollowUp', $oneFollowUp);
	$smarty->assign('arr_selected_users', $arr_selected_users);
	// Return
	$smarty->assign('holderG', '_form');
	$html = $core->build('_ajax.followups.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	));
	die();
}
function getTimeUnit($reminder_before, $reminder_unit)
{
	global $core, $dbconn;
	if ($reminder_unit == _UNIT_TIME_DAY)
		return $reminder_before * 24 * 60 * 60;
	if ($reminder_unit == _UNIT_TIME_HOUR)
		return $reminder_before * 60 * 60;
	if ($reminder_unit == _UNIT_TIME_MINUTE)
		return $reminder_before * 60;
	return 0;
}
function default_save_followups(){
	global $dbconn, $profile_id, $core, $clsISO, $clsProperty;
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsNotify = new Notify();
	###
	$customer_id = intval(Input::post('customer_id'));
	$followup_id = (int) Input::post('followup_id', 0);
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo '_need_confirm';
		die();
	}
	###
	$msg = "_error";
	if (Input::exists('hid', 'POST') && Input::post('hid') == "Update") {
		$type_id = (int) Input::post('type_id', 0);
		$status_id = (int) Input::post('status_id', 0);
		$date_id = Input::post('date_id');
		$time_id = Input::post('time_id');
		$datetime = $clsISO->convertTextToTime($date_id, $time_id);
		$reminder_before = Input::post('reminder_before');
		$reminder_unit = Input::post('reminder_unit');
		$reminder_time = $datetime - getTimeUnit($reminder_before, $reminder_unit);
		$list_user_id = Input::post('list_user_id');
		$list_user_slash = $clsISO->makeSlashListFromArray($list_user_id);
		if ($followup_id > 0) {
			$oneFollowUp = $clsFollowUp->getOne($followup_id);
			if ($clsFollowUp->updateOne($followup_id, array(
				'type_id' => $type_id,
				'status_id' => $status_id,
				'intro' => Input::post('intro'),
				'priority_id'   => Input::post('priority_id'),
				'admin_id' => Input::post('admin_id', 0),
				'list_user_slash' => $list_user_slash,
				'date_id' => $datetime,
				'reminder_before' => $reminder_before,
				'reminder_unit' => $reminder_unit,
				'reminder_time' => $reminder_time,
				'upd_date' => time(),
				'user_id_update' => $profile_id
			))) {
				$changed = 0;
				$content_change = '';
				if ($oneTable['date_id'] != $datetime) {
					$content_change = "Thời gian thay đổi từ <strong>" . $clsISO->convertTimeToText($oneFollowUp['date_id'])
						.  "</strong> tới <strong>" . $clsISO->convertTimeToText($datetime) . "</strong>";
					$changed = 1;
				}
				if ($oneTable['status_id'] != $status_id) {
					$content_change = ($changed == 1 ? "," : "") . "Tình trạng thay đổi từ <strong>" . $clsProperty->getTitle($oneFollowUp['status_id']) .  "</strong> tới <strong>" . $clsProperty->getTitle($status_id) . '</strong>';
					$changed = 1;
				}
				if ($oneTable['type_id'] != $type_id) {
					$content_change = ($changed == 1 ? "," : "") . "Phương thức thay đổi từ <strong>"
						. $clsProperty->getTitle($oneTable['type_id']) .  "</strong> tới <strong>"
						. $clsProperty->getTitle($type_id) . "</strong>";
					$changed = 1;
				}
				if ($changed == 1) {
					$notes = $clsCustomer->getOneField('notes', $customer_id);
					$list_notes = !empty($notes) ? json_decode(html_entity_decode($notes), true) : array();
					$list_notes[$clsISO->getUniqid()] = array(
						'content' => $content_change,
						'reg_date' => time(),
						'upd_date' => time(),
						'user_id' => $profile_id,
						'user_id_update' => $profile_id
					);
					$clsCustomer->updateOne($customer_id, array(
						'notes' => json_encode($list_notes, JSON_UNESCAPED_UNICODE)
					));
				}
				/** Send Email */
				//$clsFollowUp->sendEmail($followup_id);
				$msg = "_success";
			}
		} else {
			$followup_id = $clsFollowUp->getMaxId();
			//$clsFollowUp->setDebug(true);
			if ($clsFollowUp->insert(array(
				$clsFollowUp->pkey => $followup_id,
				'customer_id' => $customer_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'intro' => Input::post('intro'),
				'priority_id'   => Input::post('priority_id'),
				'admin_id' => Input::post('admin_id', 0),
				'list_user_slash' => $list_user_slash,
				'date_id' => $datetime,
				'reminder_before' => $reminder_before,
				'reminder_unit' => $reminder_unit,
				'reminder_time' => $reminder_time,
				'user_id' => $profile_id,
				'reg_date' => time()
			))) {
				$msg = "_success";
				/** Notification */
				$content = "<strong>" . $clsProfile->getFullName($profile_id, $oneProfile) . "</strong> đã tạo một lịch hẹn bằng <strong>" . $clsProperty->getTitle($type_id) . "</strong> vào lúc <strong>" . sprintf('%s %s', $date_id, $time_id) . "</strong>";
				$notes = $clsCustomer->getOneField('notes', $customer_id);
				$list_notes = !empty($notes) ? json_decode(html_entity_decode($notes), true) : array();
				$list_notes[$clsISO->getUniqid()] = array(
					'content' => $content,
					'reg_date' => time(),
					'upd_date' => time(),
					'user_id' => $profile_id,
					'user_id_update' => $profile_id
				);
				$clsCustomer->updateOne($customer_id, array(
					'notes' => json_encode($list_notes, JSON_UNESCAPED_UNICODE)
				));
				/** Send Email */
				//$clsFollowUp->sendEmail($crm_appointment_id);
			}
		}
	}
	// Return
	echo ($msg);
	die();
}
function default_load_followups_chart_care(){
	global $profile_id, $core, $clsISO, $clsUser, $clsProperty;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id');
	$html = '<div class="position-relative">
		<div id="chartContainer' . $uid . '" class="overflow-hidden" style="height:236px; width:100%;"></div>
		<div class="followup_chart_one_bg" tp="left"></div>
		<div class="followup_chart_one_bg"></div>
	</div>';
	$axisY = array(
		"title"	=> '',
		"lineColor"	=> "#666",
		"tickColor"	=> "#666",
		"labelFontColor" => "#666",
		"titleFontColor" => "#666",
		"prefix"	=> '',
		"gridColor"	=> "#eee",
		"margin"	=> 10,
		"viewportMinimum" => 0,
		/*"includeZero"=> true,
		"viewportMaximum"=> $totalFollowup,
		"viewportMinimum"=> '-'.$totalFollowup,*/
	);
	$axisX = array();
	$axisX['crosshair']['enabled'] = true;
	$axisX['crosshair']['snapToDataPoint'] = true;
	/*$toolTip['shared'] = true;
	$toolTip['contentFormatter'] = 'function (e) {
		var content = " ";
		for (var i = 0; i < e.entries.length; i++) {
			content += "<strong>" + (e.entries[i].dataPoint.y-1) + "</strong>";
		}
		return content;
	}';*/
	$dataPoints = array();
	$lstFollowType = $clsProperty->getAll("property_type='FOLLOWUP_TYPE'", "{$clsProperty->pkey},title");
	if (!empty($lstFollowType)) {
		foreach ($lstFollowType as $key => $val) {
			$property_id = $val[$clsProperty->pkey];
			$total_followups = $clsFollowUp->countItem("`customer_id`='{$customer_id}' and `type_id`='{$property_id}'");
			$dataPoints[] = array(
				"label"	=> $clsProperty->getTitle($property_id, $val),
				"y"	=> ($total_followups + 1)
			);
		}
	}
	$data = array(
		array(
			"type"	=> "spline",
			"color"	=> "#0062d1",
			"showInLegend"	=> false,
			"axisYIndex"	=> 0,
			"axisYType"	=> "secondary",
			"lineThickness"	=> 2,
			"dataPoints"	=> $dataPoints
		)
	);
	$callback = 'var chart = new CanvasJS.Chart("chartContainer' . $uid . '", {
		animationEnabled: true,
		axisX: ' . json_encode($axisX) . ',
		axisY: $.extend(' . json_encode($axisY) . ',{labelFormatter:function ( e ) {
            return (e.value-1);  
        }}),
		toolTip:{
			shared: true,
			contentFormatter:function (e) {
				var content = " ";
				for (var i = 0; i < e.entries.length; i++) {
					content += e.entries[i].dataPoint.label + ": <strong>" + (e.entries[i].dataPoint.y-1) + "</strong>";
				}
				return content;
			}
		},
		legend: {
			cursor: "pointer",
			//itemclick: toggleDataSeries
		},
		data: ' . json_encode($data) . '
	});
	chart.render();';
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	));
}
function default_load_followups_chart(){
	global $profile_id, $core, $clsISO, $clsUser, $clsProperty;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id');
	##
	$list_colors = $dataPoints = array();
	$total_followups = $clsFollowUp->countItem("customer_id={$customer_id}");
	$html = '<style type="text/css">
		.list-colors{margin:50px 0 0; padding:0; list-style:none;}
		.list-colors li.item {margin:0 0 8px 0; padding:0;}
		.list-colors li.item:after,
		.list-colors li.item:before{display:table; content:""; clear:both;}
		.list-colors .item-color {display:block; float:left; width:15px; height:15px; background:#C00000;}
		.list-colors .item-text{vertical-align:top; line-height:15px; color:#333;}
		.total_followup_chart{top:50%;left:0;right:0;transform:translateY(-50%);width:100px;}
		.canvasjs-chart-credit{opacity:0; visibility:hidden}
	</style>
	<div class="form-row">
		<div class="col-12 col-md-7">
			<div class="position-relative overflow-hidden">
			<div id="chartContainer_' . $uid . '" class="overflow-hidden" style="height:250px; width:100%;"></div>
				<div class="total_followup_chart mx-auto position-absolute text-center">
					<span class="fs-30">' . $total_followups . '</span><br> Follow-ups
				</div>
				<div class="followup_chart_one_bg position-absolute"></div>
				<div class="followup_chart_one_bg"></div>
			</div>
		</div>
		<div class="col-12 col-md-5">';
	$html .= '<ul class="list-colors">
			<li class="item">
				<span class="item-text font-bold">Tình trạng</span>
			</li>';
	$field = "{$clsProperty->pkey},title,bgcolor";
	$list_status_followups = $clsProperty->getAll("property_type='FOLLOWUP_STATUS' order by order_no ASC", $field);
	if (!empty($list_status_followups)) {
		foreach ($list_status_followups as $key => $val) {
			$status_id = $val[$clsProperty->pkey];
			$list_colors[] = $val['bgcolor'];
			$title = $clsProperty->getTitle($status_id, $val);
			$total = $clsFollowUp->countItem("`customer_id`='{$company_id}' and `status_id`='{$status_id}'") + mt_rand(1, 3);
			$total_tooltip = $total;
			if ($total_followups == 0 && $key == 0) {
				$total = 1 / 2;
			}
			$dataPoints[] = array(
				'y'	=> $total,
				'label'	=> $title,
				'total'	=> $total_tooltip
			);
			$html .= '<li class="item d-flex align-items-center">
						<span class="item-color mr-2" style="background:' . $val['bgcolor'] . '"></span> 
						<span class="item-text">' . $title . '</span>
					</li>';
		}
		unset($list_status_followups);
	}
	$html .= '</ul>';
	$html .= '</div>
	</div>';
	//$clsISO->pre($dataPoints);die;
	$callback = 'CanvasJS.addColorSet("greenShadesFollowupChart", ' . json_encode($list_colors) . ');
	var chart = new CanvasJS.Chart("chartContainer_' . $uid . '", {
		colorSet: "greenShadesFollowupChart",
		animationEnabled: true,
		data: [{
			type:"doughnut",
			//startAngle: 60,
			//innerRadius: 60,
			//indexLabelFontSize: 0,
			//indexLabel: false,
			animationEnabled: true,
			indexLabelPlacement: "outside",
			toolTipContent: "<b>{label}:</b> {total}",
			dataPoints: ' . json_encode($dataPoints) . '
		}]
	});
	chart.render();';
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	));
}
function default_open_change_assigned(){
	global $_frontIsLoggedin_user_id, $core, $clsISO, $clsUser, $clsProperty;
	global $profile_id, $oneProfile, $deviceType;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	#
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id', 0);
	$html_options = sprintf('<option value="0">%s</option>', 'Lựa chọn người quản lý');
	$field = "{$clsProfile->pkey},full_name,first_name,last_name";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'" . _STATUS_STAFF_OFF_ID . "' order by {$clsProfile->pkey} ASC", $field);
	if (!empty($list_staffs)) {
		foreach ($list_staffs as $key => $val) {
			$usr_id = $val[$clsProfile->pkey];
			$html_options .= sprintf('<option value="%s">%s</option>', $usr_id, $clsProfile->getFullName($usr_id, $val));
		}
		unset($list_staffs);
	}
	$html = '<div class="modal-dialog modal-sm" role="dialog">
		<form class="modal-content" method="post">
			<div class="modal-header">
				<h5 class="modal-title">' . $clsISO->makeIcon('bx-user', 'Thay đổi người phụ trách') . '</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<select class="form-control in-modal iso-select2" data-width="100%" 
				data-allow-clear="true" name="admin_id" data-placeholder="Thêm người theo dõi">
					' . $html_options . '
				</select>
			</div>
			<style type="text/css">
				.select2-container{ z-index:99999 !important;}
			</style>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" uid="' . $uid . '" customer_id="' . $customer_id . '" onClick="$Core.crm.do_change_assigned(this, event)" 
				class="btn btn-primary">Thay đổi</button>
			</div>
		</form>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_do_change_assigned(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerAssign = new CustomerAssign();
	###
	$msg = "_error";
	$admin_id = (int) Input::post('admin_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	//$clsISO->print_pre($_POST); die();
	$oCustomer = $clsCustomer->getOne($customer_id, 'admin_id,user_id,status_id,more_information');
	if (!(($clsCustomer->isFullPermiss() || $clsISO->checkPermission('admin_assign_client')) || (int)$oCustomer['admin_id'] == $profile_id)) {
		echo '_error';
		die();
	}
	if ($clsCustomer->isReceivePending($customer_id)) {
		echo '_need_confirm';
		die();
	}
	$admin_old_id = $oCustomer['admin_id'];
	$more_information = $oCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$list_logs = isset($more_information['logs']) && !empty($more_information['logs'])
		? $more_information['logs'] : array();
	$list_logs[$clsISO->getUniqid()] = array(
		'_type' => 'assign',
		'to_id' => $admin_id,
		'from_id' => $admin_old_id,
		'status_id' => $oCustomer['status_id'],
		'reg_date' => time()
	);
	$more_information['logs'] = $list_logs;
	$list_share_ids = $clsCustomer->getShareIds($customer_id, $oCustomer);
	$list_share_ids[] = $admin_old_id;
	$list_share_ids = $clsCustomer->normalizeIdArray($list_share_ids);
	$use_globe = ($oCustomer['user_id'] == $admin_id) ? 0 : 1;
	if ($clsCustomer->updateOne($customer_id, array(
		'upd_date' => time(),
		'admin_id' => $admin_id,
		'use_globe' => $use_globe,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$clsCustomer->syncShareIds($customer_id, $list_share_ids, $profile_id, false);
		// Tạo phiếu CHỜ XÁC NHẬN cho người nhận: chưa nhận thì không thao tác được trên khách này (createPending tự bỏ qua nếu tự giao cho mình).
		$clsCustomerAssign->createPending($customer_id, $admin_id, $profile_id, 1);
		$msg = "_success";
		$titleNoty = sprintf(
			'<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>',
			$clsProfile->getFullName($profile_id, $oneProfile),
			$clsCustomer->getName($customer_id, $oCustomer)
		);
		$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNoty, time(), "|" . $admin_id . "|");
		#thong bao app
		$clsNotification = new Notification();
		$params = [
			'title' => "CRM - Khách hàng mới",
			'body' => strip_tags($titleNoty),
			'link' => PCMS_URL . sprintf('/crm/#/customer/%s/overview', $customer_id)
		];
		$clsNotification->doPushMessagingUser($params, [$admin_id]);
	}
	// Return
	echo $msg;
	die();
}
function default_set_field(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	###
	$msg = "_error";
	$p_field = Input::post('p_field');
	$p_value = (int) Input::post('p_value', 0);
	$more_information = $oneProfile['more_information'];
	$more_information[$p_field] = $p_value;
	if ($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$msg = "_success";
	}
	// Return
	echo $msg;
	die();
}
function default_set_view(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	###
	$msg = "_error";
	$view = Input::post('view', 'table');
	$more_information = $oneProfile['more_information'];
	$more_information['_ss_view'] = $view;
	if ($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$msg = "_success";
	}
	// Return
	echo $msg;
	die();
}
function default_set_archived(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsArchived = new Archived();
	#
	$msg = "_error";
	$customer_id = (int) Input::post('customer_id', 0);
	$tmp = $clsArchived->getByCond("`profile_id`='{$profile_id}' and `customer_id`='{$customer_id}'");
	if (!empty($tmp)) {
		if ($clsArchived->deleteOne($tmp[$clsArchived->pkey])) {
			$msg = "_success";
		}
	} else {
		if ($clsArchived->insert(array(
			'reg_date' => time(),
			'profile_id' => $profile_id,
			'customer_id' => $customer_id
		))) {
			$msg = "_success";
		}
	}
	// Return
	echo $msg;
	die();
}
function default_open_in_charge(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::request('customer_id', 0);
	$oneCustomer = $clsCustomer->getOne($customer_id, "status_id");
	$status_id = !empty($oneCustomer) ? $oneCustomer['status_id'] : 0;
	$html = '<div class="modal-dialog modal-sm modal-dialog-centered">
		<form class="modal-content" method="post">
			<div class="modal-header">
				<h5 class="modal-title">Thay người quản lý</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="form-label mb-1">Chọn người quản lý</label>
					<select class="iso-selectizeImageSearch required" name="admin_id" data-width="100%" 
						data-placeholder="Người tham gia" data-url="' . PCMS_URL . '/index.php?mod=home&act=list_staff&holderG=active" data-width="100%">
						<option value="' . $profile_id . '" selected="selected">
							' . $clsProfile->getIndentityV2($profile_id, $oneProfile, false) . '
						</option>
					</select>
				</div>
			</div>
			<div class="modal-footer border-top pt-2">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" onclick="$Core.crm.update_field(this,event);" p_field="admin_id" uid="' . $uid . '" 
				p_id="' . $customer_id . '">' . $core->makeIcon('check', 'Cập nhật') . '</button>
			</div>
		</form>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_load_tags(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsTag = new Tag();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$results = array();
	$keyword = Input::get('keyword');
	$cond = "`tag_type`='_crm' and `user_id`='{$profile_id}'";
	if (!empty($keyword)) {
		$cond .= " and (`slug` like '%" . $core->replaceSpace($keyword) . "%')";
	}
	$list_tags = $clsTag->getAll($cond, "{$clsTag->pkey},title");
	if (!empty($list_tags)) {
		foreach ($list_tags as $key => $val) {
			$results[] = $val['title'];
		}
		unset($list_tags);
	}
	// Return
	echo json_encode($results, JSON_UNESCAPED_UNICODE);
	die();
}
function default_load_pop_tag(){
	global $profile_id, $oneProfile, $core, $clsISO, $clsUser, $clsProperty;
	$clsTag = new Tag();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$html_tags = array();
	$uid = $clsISO->getUniqid();
	$holderG = Input::request('holderG', '_pop');
	$customer_id = (int) Input::request('customer_id', 0);
	$clsCustomerMeta = new CustomerMeta();
	$list_tags_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'tag');
	if (!empty($list_tags_arrs)) {
		foreach ($list_tags_arrs as $tag_id) {
			$html_tags[] = $clsTag->getTitle($tag_id);
		}
	}
	###
	if ($holderG == "_modal") {
		$html = '<div class="modal-dialog" role="document">
			<form class="modal-content" method="post">
				<div class="modal-header">
					<h5 class="modal-title">Nhập Tags</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input class="tags" id="tags_' . $uid . '" name="tags" maxlength="255" autocomplete="off" 
					placeholder="Nhập tags" value="' . (!empty($html_tags) ? implode(',', $html_tags) : "") . '" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" uid="' . $uid . '" holderG="' . $holderG . '" p_id="' . $customer_id . '" p_field="tags" onClick="$Core.crm.update_field(this, event)" class="btn btn-primary">Lưu lại</button>
				</div>
			</form>
		</div>';
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html
		));
		die();
	} else {
		$html = '<form method="post">
			<div class="form-group mb-2">
				<input class="tags" id="tags_' . $uid . '" name="tags" id="input_tags_' . time() . '" maxlength="255" placeholder="Nhập tags" value="' . (!empty($html_tags) ? implode(',', $html_tags) : "") . '" />
			</div>
			<hr class="my-3" />
			<div class="form-group">
				<input type="hidden" name="p_field" value="tags" />
				<button type="button" class="btn btn-outline-primary" onclick="$Core.crm.update_field(this,event);" 
				p_field="tags" uid="' . $uid . '" p_id="' . $customer_id . '">' . $core->makeIcon('check', 'Cập nhật') . '</button>
			</div>
		</form>';
		// Return
		echo $html;
		die();
	}
}
function default_load_conversion_rate(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	##
	$show = Input::post('show');
	$tp = Input::post('tp', 'THIS_MONTH');
	$cond = "`is_trash`=0 and `resource_id`<>'" . _CRM_RESOURCE_SALEMOC_ID . "'";
	$cond .= " and JSON_EXTRACT(`more_information`,\"$.logs\")<>''";
	if ($show == 'me') {
		$cond .= " and `admin_id`='{$profile_id}'";
	} else if ($show == 'staff') {
		$staff_id = (int)Input::post("staff_id", 0);
		$cond .= " `admin_id`='{$staff_id}'";
	} else {
		if ($clsISO->checkPermissionGroup('DIRECTOR')) {
			// Next
		} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
			$department_id = $oneProfile['department_id'];
			$cond .= " and `admin_id` in (
				SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
				WHERE (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')
			)";
		}
	}
	$arr_status = [];
	$field = "{$clsProperty->pkey},title,bgcolor";
	$status_not_in = array(_CRM_STATUS_DONTCARE_ID, _CRM_STATUS_LEAD_ID, _CRM_STATUS_TRASH_ID, _CRM_STATUS_LONG_TERM_LEAD_ID);
	$tmp = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='CUSTOMER_STATUS' 
		AND `{$clsProperty->pkey}` NOT IN (" . implode(',', $status_not_in) . ") order by `order_no` asc", $field);
	if (!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_status[$val[$clsProperty->pkey]] = [
				"title"	=>	$val['title'],
				"total"		=>	0
			];
		}
	}
	#
	$tmp = $clsISO->getRangeTime($tp);
	$start_date = $tmp['start_date'];
	$due_date = $tmp['due_date'];
	$cond .= " and (`reg_date` BETWEEN {$start_date} AND {$due_date})";
	$total_customers = 0;
	$arr_log = [];
	$list_customers = $clsCustomer->getAll($cond, "more_information");
	if (!empty($list_customers)) {
		$total_customers = count($list_customers);
		foreach ($list_customers as $key => $val) {
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if (!empty($more_information["logs"])) {
				foreach ($more_information["logs"] as $k_log => $v_log) {
					if ($v_log["_type"] == "upd_status") {
						$arr_log[] = $v_log;
					}
				}
			}
		}
		// $clsISO->print_pre($arr_log);die;
		foreach ($arr_log as $key => $val) {
			if (isset($arr_status[$val['status_id']])) {
				$arr_status[$val['status_id']]["total"] += 1;
			}
		}
	}
	$data = array();
	$dataPoints = array();
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	##
	$total_first = $total_customers;
	$label_first = $clsProperty->getOneField("title", _CRM_STATUS_LEAD_ID);
	foreach ($arr_status as $key => $val) {
		$total = $val["total"];
		if ($key == _CRM_STATUS_TRASH_ID || $key == _CRM_STATUS_LONG_TERM_LEAD_ID) {
			$dataPoints[] = array(
				'label'	=> $val["title"],
				'y'	=> round(($total_customers > 0) ? ($total * 100 / $total_customers) : 0, 1),
				'total_to'	=>	$total,
				'total_from' =>	$total_customers,
			);
		} else {
			$total = ($total > $total_first) ? $total_first : $total;
			$dataPoints[] = array(
				'label'	=> $label_first . "->" . $val["title"],
				'y'	=> round(($total_first > 0) ? ($total * 100 / $total_first) : 0, 1),
				'total_to'	=>	$total,
				'total_from' =>	$total_first,
			);
		}
		$total_first = $total;
		$label_first = $val["title"];
	}
	$data['type'] = 'column';
	// $data['showInLegend'] = 'true';
	//$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$data['toolTipContent'] = "{label}: {y}% ({total_to}/{total_from} khách hàng)";
	$barChartData['data'] = $data;
	$uid = $clsISO->getUniqid();
	$html = '<div class="chartContainer" style="height:300px">
		<div id="' . $uid . '" class="w-100 h-100"></div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData
	));
	die();
}
function default_load_desktop_crm_group(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsGroupProfile = new GroupProfile();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$group_id = (int)Input::post("group_id", 0);
	$department_id = (int)Input::post("department_id", 0);
	$lstProfile = array();
	$total_member = 0;
	if (!empty($group_id)) {
		$oneItem = $clsGroupProfile->getOne($group_id, $clsGroupProfile->pkey . ',title,list_profile_id');
		if (!empty($oneItem)) {
			$list_profile_id = $clsISO->getArrayByTextSlash($oneItem['list_profile_id']);
			$total_member = count($list_profile_id);
			if (!empty($list_profile_id)) {
				$lstProfile = $clsProfile->getAll("`{$clsProfile->pkey}` IN (" . implode(',', $list_profile_id) . ")", $clsProfile->pkey . ",full_name");
				foreach ($lstProfile as $k => $_oProfile) {
					$totalCustomer = $clsCustomer->countItem("`admin_id` = '{$_oProfile[$clsProfile->pkey]}'");
					$lstProfile[$k]["total_customer"] = (int)$totalCustomer;
				}
			}
		}
	} else if (!empty($department_id)) {
		$lstProfile = $clsProfile->getAll("`status_id` <> '" . _STATUS_STAFF_OFF_ID . "' AND (`department_id` = '{$department_id}' OR `list_department_id` LIKE '|%{$department_id}%|')", $clsProfile->pkey . ",full_name");
		foreach ($lstProfile as $k => $_oProfile) {
			$totalCustomer = $clsCustomer->countItem("`admin_id` = '{$_oProfile[$clsProfile->pkey]}'");
			$lstProfile[$k]["total_customer"] = (int)$totalCustomer;
		}
	}
	#
	$data = array();
	$dataPoints = array();
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	##
	foreach ($lstProfile as $key => $val) {
		$dataPoints[] = array(
			'label'	=> $val["full_name"],
			'y'	=> $val["total_customer"],
		);
	}
	// Return
	$data['type'] = 'column';
	$data['showInLegend'] = 'true';
	$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$data['toolTipContent'] = "{label}: {y} khách hàng)";
	$barChartData['data'] = $data;
	$uid = $clsISO->getUniqid();
	$html = '<div id="' . $uid . '" class="chartContainer" style="height:300px"></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total_member' => $total_member,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData
	));
	die();
}
function default_setting_field(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	###
	$view_by = Input::post('view_by', "table");
	$customer_id = (int) Input::post('customer_id', 0);
	$field_name = Input::post('field_name', "fieldDataCustomer");
	$field = "{$clsProperty->pkey},title,image";
	###
	$more_information = $oneProfile['more_information'];
	if ($field_name == "fieldDataCustomerStatus") {
		$limit = 5;
		$field_data_default = _ARRAY_STATUS_FIELD_DEFAULT;
		$lstFieldData = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
	} else {
		$limit = 15;
		$field_data_default = (($view_by == "table") ? _ARRAY_TABLE_FIELD_DATA_CUSTOMER_DEFAULT : _ARRAY_COMPACT_FIELD_DATA_CUSTOMER_DEFAULT);
		$lstFieldData = $clsProperty->getArraySearchByKey("FIELD_DATA");
	}
	$fieldDataCustomer = $core->get_field($more_information, $field_name, $field_data_default);
	$lstFieldSelected = array();
	foreach ($fieldDataCustomer as $field_id) {
		$lstFieldSelected[] = !empty($lstFieldData[$field_id]) ? $lstFieldData[$field_id] : array();
	}
	$smarty->assign('lstFieldData', $lstFieldData);
	$smarty->assign('fieldDataCustomer', $fieldDataCustomer);
	$smarty->assign('lstFieldSelected', $lstFieldSelected);
	$smarty->assign('field_name', $field_name);
	$smarty->assign('limit', $limit);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('view_by', $view_by);
	$html = $core->build('_ajax.setting_field.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_load_setting_field(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsProperty = new Property();
	$gid = Input::post("gid", "");
	$type = Input::post("type", "SEARCH");
	$keyword = Input::post("keyword", "");
	$view_by = Input::post("view_by", "table");
	$field_name = Input::post('field_name', "fieldDataCustomer");
	$list_field_data = Input::post("list_field_data", array());
	if ($field_name == "fieldDataCustomerStatus") {
		$lstFieldData = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
		$field_data_default = _ARRAY_STATUS_FIELD_DEFAULT;
	} else {
		$lstFieldData = $clsProperty->getArraySearchByKey("FIELD_DATA");
		$field_data_default = (($view_by == "table") ? _ARRAY_TABLE_FIELD_DATA_CUSTOMER_DEFAULT : _ARRAY_COMPACT_FIELD_DATA_CUSTOMER_DEFAULT);
	}
	$html = "";
	$arr_search = [];
	if ($type == "DEFAULT") {
		foreach ($lstFieldData as $key => $value) {
			if ($clsISO->checkItemInArray($value['property_id'], $field_data_default)) {
				$arr_search[] = $value;
			}
		}
		if (!empty($arr_search)) {
			foreach ($arr_search as $key => $val) {
				$html .= '<li class="item_field_selected d-flex justify-content-between align-items-center p-2 bg-lighter rounded-1 mb-2 text-black cursor-pointer" title="' . $val['title'] . '" id="item_' . $gid . '_' . $val["property_id"] . '" key="' . $val["property_id"] . '">
					<div class="crm-flex filed-select">
						<i class="bx bx-grid-vertical"></i>
						<span class="title-ellipsis text misa-label">' . $val['title'] . '</span>
					</div>
					<button class="btn btn-sm text-main p-0" type="button" onClick="$Core.crm.deleteField(this,event)" toId="field_' . $gid . '_' . $val["property_id"] . '"><i class="bx bx-x"></i></button>
				</li>';
			}
		} else {
			$html = '<p class="text-center mb-0 fs-12 fw-italic">Không tìm thấy kết quả nào</p>';
		}
	} else {
		if (!empty($keyword)) {
			foreach ($lstFieldData as $key => $value) {
				if (stristr($value['title'], $keyword) || stristr($value['slug'], $keyword)) {
					$arr_search[] = $value;
				}
			}
		} else {
			$arr_search = $lstFieldData;
		}
		if (!empty($arr_search)) {
			foreach ($arr_search as $key => $val) {
				$checked = ($clsISO->checkItemInArray($val['property_id'], $list_field_data)) ? " checked " : "";
				$html .= '<li class="item-menu-settings-column crm-flex crm-align-items-center px-3 py-1" title="' . $val['title'] . '">
					<label class="form-check mb-0" for="field_' . $gid . '_' . $val["property_id"] . '">
						<input class="form-check-input" type="checkbox" value="' . $val["property_id"] . '" id="field_' . $gid . '_' . $val["property_id"] . '" onChange="$Core.crm.updateSettingField(this,event)"  toId="item_' . $gid . '_' . $val["property_id"] . '" data-title="' . $val['title'] . '" data-key="' . $val["property_id"] . '" ' . $checked . ' >
						<span class="form-check-label">' . $val['title'] . '</span>
					</label>
				</li>';
			}
		} else {
			$html = '<p class="text-center mb-0 fs-12 fw-italic">Không tìm thấy kết quả nào</p>';
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $gid,
		'html' => $html
	));
	die();
}
function default_save_setting_field(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$field_name = Input::post("field_name", "fieldDataCustomer");
	$list_field_data = Input::post("list_field_data", array());
	$more_information = $oneProfile['more_information'];
	$more_information[$field_name] = $list_field_data;
	$res = ["result" => false];
	if ($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$res = ["result" => true];
	}
	// Return
	echo json_encode($res);
	die();
}
function default_open_need(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	$uid = $clsISO->getUniqid();
	$need_id = Input::post('need_id', "");
	$customer_id = (int) Input::post('customer_id', 0);
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $oneCustomer['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	###
	$html = $msg = "";
	$action = "_add";
	$res = ["result" =>	false, "msg" =>	"ERROR"];
	$list_needs = !empty($more_information['list_needs'])
		? $more_information['list_needs'] : array();
	if (!empty($need_id)) {
		$action = "_edit";
		$oneItem = isset($list_needs[$need_id])
			? $list_needs[$need_id] : array();
	}
	###
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign("need_id", $need_id);
	$smarty->assign("customer_id", $customer_id);
	$smarty->assign("oneItem", $oneItem);
	// Return
	$html = $core->build('_ajax.open_need.tpl');
	$res = [
		"result"	=>	true,
		"uid"		=>	$uid,
		"html"		=>	$html
	];
	echo json_encode($res);
	die();
}
function default_save_need(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	$need_id = Input::post('need_id', "");
	$customer_id = (int)Input::post('customer_id', 0);
	$more_information = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information = $clsISO->to_array_json($more_information);
	$list_needs = isset($more_information['list_needs'])
		? $more_information['list_needs'] : array();
	#
	$html = $msg = "";
	$res = ["result" =>	false, "msg" =>	"ERROR"];
	$title = Input::post('title', "");
	$block_type_id = (int) Input::post('block_type_id', 0);
	$purpose_id = (int) Input::post('purpose_id', 0);
	$price_range_id = (int) Input::post('price_range_id', 0);
	$floor_range = Input::post('floor_range', "");
	$direction_id = (int) Input::post('direction_id', 0);
	$juridical_id = (int) Input::post('juridical_id', 0);
	$interior_type = (int) Input::post('interior_type', 0);
	$bedroom = (int) Input::post('bedroom', 0);
	$bedroom_count = Input::post('bedroom_count', '');
	$bathroom = (int) Input::post('bathroom', 0);
	$bathroom_count = Input::post('bathroom_count', "");
	$content = Input::post('content', "");
	$is_hot = (int) Input::post('is_hot', 0);
	if (!empty($need_id)) {
		$list_needs[$need_id]['title'] = $title;
		$list_needs[$need_id]['block_type_id'] = $block_type_id;
		$list_needs[$need_id]['purpose_id'] = $purpose_id;
		$list_needs[$need_id]['price_range_id'] = $price_range_id;
		$list_needs[$need_id]['floor_range'] = $floor_range;
		$list_needs[$need_id]['direction_id'] = $direction_id;
		$list_needs[$need_id]['juridical_id'] = $juridical_id;
		$list_needs[$need_id]['interior_type'] = $interior_type;
		$list_needs[$need_id]['bedroom'] = $bedroom;
		$list_needs[$need_id]['bedroom_count'] = $bedroom_count;
		$list_needs[$need_id]['bathroom'] = $bathroom;
		$list_needs[$need_id]['bathroom_count'] = $bathroom_count;
		$list_needs[$need_id]['content'] = $content;
		$list_needs[$need_id]['is_hot'] = $is_hot;
		$list_needs[$need_id]['user_updated_id'] = $profile_id;
		$list_needs[$need_id]['upd_date'] = time();
	} else {
		$need_id = $clsISO->getUniqid();
		$list_needs[$need_id] = array(
			"title"				=>	$title,
			"block_type_id"		=>	$block_type_id,
			"purpose_id"		=>	$purpose_id,
			"price_range_id"	=>	$price_range_id,
			"floor_range"		=>	$floor_range,
			"direction_id"		=>	$direction_id,
			"juridical_id"		=>	$juridical_id,
			"interior_type"		=>	$interior_type,
			"bedroom"			=>	$bedroom,
			"bedroom_count"		=>	$bedroom_count,
			"bathroom"			=>	$bathroom,
			"bathroom_count"	=>	$bathroom_count,
			"content"			=>	addslashes($content),
			"is_hot"			=>	$is_hot,
			'user_id'			=> $profile_id,
			'reg_date'			=> time()
		);
	}
	$more_information["list_needs"] = $list_needs;
	if ($clsCustomer->updateOne($customer_id, array(
		"more_information" => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$res = ["result" =>	true, "msg"	=>	$msg];
	}
	// Return
	echo json_encode($res);
	die();
}
function default_load_list_need(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	##
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	$customer_id = (int) Input::post('customer_id', 0);
	$more_information = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information = $clsISO->to_array_json($more_information);
	$list_needs = !empty($more_information['list_needs'])
		? $more_information['list_needs'] : array();
	// $clsISO->print_pre($list_needs); die();
	$total_record = 0;
	if (!empty($list_needs)) {
		$total_record = count($list_needs);
	}
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	###
	$i = $total = 0;
	$arr_needs = $arr_property_cache = array();
	foreach ($list_needs as $key => $val) {
		$block_type_id = (int) $val['block_type_id'];
		$purpose_id = (int) $val['purpose_id'];
		$price_range_id = (int) $val['price_range_id'];
		$direction_id = (int) $val['direction_id'];
		$juridical_id = (int) $val['juridical_id'];
		$interior_type = (int) $val['interior_type'];
		++$i;
		++$total;
		if ($i >= $offset && $total <= $per_page) {
			if ($block_type_id > 0 && !isset($arr_property_cache[$block_type_id])) {
				$arr_property_cache[$block_type_id] = $clsProperty->getTitle($block_type_id);
			}
			if ($purpose_id > 0 && !isset($arr_property_cache[$purpose_id])) {
				$arr_property_cache[$purpose_id] = $clsProperty->getTitle($purpose_id);
			}
			if ($price_range_id > 0 && !isset($arr_property_cache[$price_range_id])) {
				$arr_property_cache[$price_range_id] = $clsProperty->getTitle($price_range_id);
			}
			if ($direction_id > 0 && !isset($arr_property_cache[$direction_id])) {
				$arr_property_cache[$direction_id] = $clsProperty->getTitle($direction_id);
			}
			if ($juridical_id > 0 && !isset($arr_property_cache[$juridical_id])) {
				$arr_property_cache[$juridical_id] = $clsProperty->getTitle($juridical_id);
			}
			if ($interior_type > 0 && !isset($arr_property_cache[$interior_type])) {
				$arr_property_cache[$interior_type] = $clsProperty->getTitle($interior_type);
			}
			$block_type = !empty($arr_property_cache[$block_type_id]) ? $arr_property_cache[$block_type_id] : "";
			$purpose = !empty($arr_property_cache[$purpose_id]) ? $arr_property_cache[$purpose_id] : "";
			$price_range = !empty($arr_property_cache[$price_range_id]) ? $arr_property_cache[$price_range_id] : "";
			$direction = !empty($arr_property_cache[$direction_id]) ? $arr_property_cache[$direction_id] : "";
			$juridical = !empty($arr_property_cache[$juridical_id]) ? $arr_property_cache[$juridical_id] : "";
			$interior_type = !empty($arr_property_cache[$interior_type]) ? $arr_property_cache[$interior_type] : "";
			$bedroom_count = !empty($val['bedroom_count']) ? $val['bedroom_count'] : "";
			$bathroom_count = !empty($val['bathroom_count']) ? $val['bathroom_count'] : "";
			$bedroom = !empty($val['bedroom']) ? $val['bedroom'] : $bedroom;
			$bathroom = !empty($val['bathroom']) ? $val['bathroom'] : $bathroom_count;
			$arr_needs[$key] = array(
				"title"	=>	$val['title'],
				"block_type"	=>	$block_type,
				"purpose"	=>	$purpose,
				"price_range"	=>	$price_range,
				"floor_range"	=>	$val["floor_range"],
				"direction"	=>	$direction,
				"juridical"	=>	$juridical,
				"interior_type"	=>	$interior_type,
				"bedroom"	=>	$bedroom,
				"bathroom"	=>	$bathroom,
				"content"	=>	$val["content"],
				"is_hot"	=>	$val["is_hot"],
			);
		}
	}
	$smarty->assign('lstItem', $arr_needs);
	$smarty->assign('customer_id', $customer_id);
	// Return
	$html = $core->build('_ajax.need.tpl');
	echo json_encode(array(
		'html' => $html,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_record' => $total_record,
		'total_page' => $total_page
	));
	die();
}
function default_delete_need(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	###
	$need_id = Input::post('need_id', "");
	$customer_id = (int)Input::post('customer_id', 0);
	$more_information = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information = $clsISO->to_array_json($more_information);
	$list_needs = !empty($more_information['list_needs'])
		? $more_information['list_needs'] : array();
	#
	$html = $msg = "";
	$res = ["result" =>	false, "msg" =>	"ERROR"];
	if (!empty($list_needs) && !empty($need_id) && array_key_exists($need_id, $list_needs)) {
		unset($list_needs[$need_id]);
		$more_information["list_needs"] = $list_needs;
		if ($clsCustomer->updateOne($customer_id, array(
			"more_information" => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))) {
			$res = ["result" =>	true, "msg"	 =>	"Xóa thành công!"];
		}
	}
	// Return
	echo json_encode($res);
	die();
}
function default_open_billing(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	###
	$uid = $clsISO->getUniqid();
	$gId = Input::post('gId', "");
	$customer_id = (int)Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	###
	$more_information = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information = $clsISO->to_array_json($more_information);
	$list_billings = !empty($more_information['list_billings'])
		? $more_information['list_billings'] : array();
	$total_billings = !empty($list_billings) ? count($list_billings) : 0;
	##
	$html = $msg = "";
	$res = ["result" =>	false, "msg" => "ERROR"];
	$billing_code = sprintf('GD%s.%s', $customer_id, $clsISO->parseNumber($total_billings + 1));
	$action = "_add";
	$oneItem = array(
		'billing_code' => $billing_code,
		'deposit_date' => time(),
		'contract_status_id' => _CONTRACT_STATUS_WAIT_ID
	);
	// $clsISO->print_pre($oneItem); die();
	if (!empty($billing_id) && !empty($list_billings) && array_key_exists($billing_id, $list_billings)) {
		$action = "_edit";
		$oneItem = isset($list_billings[$billing_id])
			? $list_billings[$billing_id] : array();
	}
	$smarty->assign('gId', $gId);
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign("billing_id", $billing_id);
	$smarty->assign("customer_id", $customer_id);
	$smarty->assign("oneItem", $oneItem);
	$smarty->assign("more_information", $more_information);
	$html = $core->build('_ajax.open_billing.tpl');
	$res = [
		"result"	=>	true,
		"uid"		=>	$uid,
		"html"		=>	$html
	];
	// Return
	echo json_encode($res);
	die();
}
function default_save_billing(){
	global $smarty, $mod, $act, $adminid, $core, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	###
	$gId = Input::post('gId', "");
	$billing_id = Input::post('billing_id', "");
	$customer_id = (int)Input::post('customer_id', 0);
	$more_information = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information = $clsISO->to_array_json($more_information);
	$list_billings = !empty($more_information['list_billings'])
		? $more_information['list_billings'] : array();
	###
	$html = $msg = "";
	$res = ["result" =>	false, "msg" =>	"ERROR", "gId" => $gId];
	$billing_code = Input::post('billing_code', "");
	$billing_type = (int)Input::post('billing_type', 0);
	$deposit_date = Input::post('deposit_date', "");
	$deposit_date = !empty($deposit_date) ? $clsISO->toTime($deposit_date) : 0;
	$stock_code = Input::post('stock_code', "");
	$contract_status_id = (int)Input::post('contract_status_id', 0);
	$project_id = (int)Input::post('project_id', 0);
	$totalgrand = Input::post('totalgrand', "");
	//$capture_confirm_file = Input::post('capture_confirm_file',"");
	//$table_bonus_file = Input::post('table_bonus_file',"");
	//$sale_policy_file = Input::post('sale_policy_file',"");
	$staff_notes = Input::post('staff_notes', '');
	$more_information = array();
	//$more_information['stock_resource'] = $stock_resource;
	//$more_information['staff_notes'] = $staff_notes;
	//$more_information['customer_name'] = $oneCustomer["name"];
	//$more_information['customer_email'] = $oneCustomer["email"];
	//$more_information['customer_phone'] = $oneCustomer["phone"];
	//$more_information['sale_policy_file'] = $sale_policy_file;
	//$more_information['capture_confirm_file'] = $capture_confirm_file;
	//$more_information['table_bonus_file'] = $table_bonus_file;
	if (!empty($billing_id)) {
		$msg = "Cập nhật thành công!";
		$list_billings[$billing_id]['billing_code'] = $billing_code;
		$list_billings[$billing_id]['billing_type'] = $billing_type;
		$list_billings[$billing_id]['deposit_date'] = $deposit_date;
		$list_billings[$billing_id]['project_id'] = $project_id;
		$list_billings[$billing_id]['stock_code'] = $stock_code;
		$list_billings[$billing_id]['contract_status_id'] = $contract_status_id;
		$list_billings[$billing_id]['totalgrand'] = $clsISO->processSmartNumber($totalgrand);
		$list_billings[$billing_id]['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
		$list_billings[$billing_id]['staff_notes'] = $staff_notes;
		$list_billings[$billing_id]['upd_date'] = time();
		$list_billings[$billing_id]['user_id_update'] = $profile_id;
	} else {
		$msg = "Thêm mới thành công!";
		$billing_id = $clsISO->getUniqid();
		$list_billings[$billing_id] = array(
			'billing_code' 			=> $billing_code,
			'billing_type' 			=> $billing_type,
			'deposit_date'			=> $deposit_date,
			'project_id' 			=> $project_id,
			'stock_code' 			=> $stock_code,
			'contract_status_id' 	=> $contract_status_id,
			'totalgrand' 			=> $clsISO->processSmartNumber($totalgrand),
			'more_information' 		=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'staff_notes'			=> $staff_notes,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id'  => $profile_id,
			'user_id_update' => $profile_id
		);
	}
	$more_information["list_billings"] = $list_billings;
	if ($clsCustomer->updateOne($customer_id, array(
		"more_information" => json_encode($more_information)
	))) {
		$res = ["result" =>	true, "msg" => $msg, "gId" => $gId];
	}
	// Return
	echo json_encode($res);
	die();
}
function default_delete_billing(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $extLang, $profile_id, $clsISO;
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	###
	$billing_id = Input::post('billing_id', "");
	$customer_id = (int) Input::post('customer_id', 0);
	$more_information = $clsCustomer->getOneField('more_information', $customer_id);
	$more_information = $clsISO->to_array_json($more_information);
	$list_billings = !empty($more_information['list_billings'])
		? $more_information['list_billings'] : array();
	###
	if (!empty($billing_id) && !empty($list_billings) && array_key_exists($billing_id, $list_billings)) {
		unset($list_billings[$billing_id]);
		$more_information['list_billings'] = $list_billings;
		if ($clsCustomer->updateOne($customer_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))) {
			$msg = '_success';
		}
	}
	// Return
	echo $msg;
	die();
}
function default_upload_sp_file(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $extLang, $profile_id, $clsISO;
	$clsBilling = new Billing();
	#
	$msg = "_error";
	$to_field = Input::post('to_field');
	$billing_code = Input::post('billing_code');
	$billing_id = (int) Input::post('billing_id', 0);
	if (isset($_POST['hid']) && $_POST['hid'] == 'upload') {
		if (!empty($_FILES['upload_file']['name'])) {
			$person_info = array();
			if (@is_uploaded_file($_FILES['upload_file']['tmp_name'])) {
				$clsUploadFile = new UploadFile();
				$upload_file = $clsUploadFile->uploadItem($_FILES["upload_file"], "/GD", EXTENSION_FILE_UPLOAD);
				$file_name = $_FILES['upload_file']['name'];
				$file_size = $_FILES['upload_file']['size'];
				// Upload file to google drive
				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
				$folder_id = $clsGoogleUpload->create_folder($billing_code);
				// $clsISO->print_pre($folder_id); die();
				$createdFile = $clsGoogleUpload->upload($file_name, $mimeType, ROOTPATH . $upload_file, $folder_id);
				$uploaded_file = 'https://drive.google.com/file/d/' . $createdFile->getId() . '/view';
				$uploaded_url = $clsISO->genGoogleURL($createdFile->getId());
				@unlink(ROOTPATH . $upload_file);
				// Update to DB
				if ($billing_id > 0) {
					$oneBilling = $clsBilling->getOne($billing_id, "billing_code,more_information");
					$billing_code = $oneBilling['billing_code'];
					$more_information = $oneBilling['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$more_information[$to_field] = $uploaded_file;
					// $clsISO->print_pre($more_information); die();
					if ($clsBilling->updateOne($billing_id, array(
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))) {
						$msg = '_success|||';
						if ($to_field == 'ccid_front' || $to_field == 'ccid_back') {
							$msg .= '<img src="' . $uploaded_url . '" class="w-100 h-100 rounded-1" />|||' . implode('|||', $person_info);
						} else if ($to_field == 'sale_policy_file') {
							$msg .= '<a class="download" data-fancybox="true" target="_blank" href="' . $uploaded_url . '">' . $uploaded_file . '</a>';
						}
					}
				} else {
					$msg .= '_success|||
					<input type="hidden" value="' . $uploaded_file . '" name="' . $to_field . '" />
					<a class="download" data-fancybox="true" href="' . $uploaded_url . '">
						' . $clsISO->formatFileName($uploaded_file, 10) . '
					</a>';
				}
			}
		}
	}
	// Return
	echo $msg;
	die();
}
function default_view_billing(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $extLang, $profile_id, $clsISO;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsCustomer = new Customer();
	###
	$uid = $clsISO->getUniqid();
	$tabfocus = (int) Input::post('tabfocus', 1);
	$customer_id = (int)Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	if ($billing_id == "" || empty($oneCustomer) || !isset($lstBillings[$billing_id])) {
		echo '_invalid';
		die();
	}
	$oneBilling = $lstBillings[$billing_id];
	//	$clsISO->print_pre($oneBilling);die;
	$more_information = $oneBilling['more_information'];
	$more_information = !empty($more_information)
		? json_decode(html_entity_decode($more_information), true)
		: array();
	$commission = $oneBilling['commission'];
	if ((int) $commission > 0) {
		$totalgrand = $clsISO->processSmartNumber($oneBilling['totalgrand']);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('tabfocus', $tabfocus);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('billing_id', $billing_id);
	$smarty->assign('oneBilling', $oneBilling);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsCustomer', new Customer());
	$smarty->assign('clsStock', new Stock());
	// Return
	$html = $core->build('_ajax.billing.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_list_logs(){
	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $extLang, $profile_id, $clsISO;
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$customer_id = (int)Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	###
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$logs = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();;
	$list_logs = !empty($logs) ? @json_decode(html_entity_decode($logs), true) : array();
	$html = '';
	if (!empty($list_logs)) {
		$html .= '<ul class="logs">';
		foreach ($list_logs as $key => $val) {
			$html .= '<li>' . $clsISO->convertTimeToText($val['reg_date'], true) . ": " . $val['content'] . '</li>';
		}
		$html .= '<ul>';
	} else {
		$html .= '<div class="p-4 text-center">
			<img src="https://cdn-icons-png.flaticon.com/512/833/833602.png" width="50px" />
			<p class="text-muted mt-2">ChÆ°a cÃ³ lá»‹ch sá»­ giao dá»‹ch</p>
		</div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html
	));
	die();
}
function default_load_list_notes(){
	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsTable = Input::post('clsTable');
	$customer_id = (int) Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$list_notes = !empty($oneBilling['notes']) ? $oneBilling['notes'] : array();
	$html = '';
	if (!empty($list_notes) && is_array($list_notes)) {
		$list_notes = @array_reverse($list_notes);
		$arr_profile_cached = array();
		$html .= '<div class="timeline">';
		foreach ($list_notes as $key => $val) {
			$user_id = $val['user_id'];
			if ($profile_id != $user_id) {
				$arrProfile = $clsProfile->getOne($user_id, "full_name,avatar");
			} else {
				$arrProfile = $oneProfile;
			}
			if (isset($arr_profile_cached[$user_id])) {
				$avatar = $arr_profile_cached[$user_id];
			} else {
				$arr_profile_cached[$user_id] = $clsProfile->getAvatar($user_id, $arrProfile);
			}
			$html .= '<div class="timeline-item ">
				<div class="timeline-badge">
					<img class="avatar avatar-sm mr-2 rounded-pill" src="' . $arr_profile_cached[$user_id] . '" 
					onerror="this.src=\'' . URL_IMAGES . '/no-avatar.jpg\'" />
				</div>
				<div class="timeline-body">
					<div class="timeline-body-head d-flex justify-content-between">
						<div class="timeline-body-head-caption d-flex align-items-center">
							<span class="timeline-body-alerttitle font-green-haze mr-2">
								' . $arrProfile['full_name'] . '</span>
							<small>' . $clsISO->getTimeAgo($val['reg_date']) . '</small>
						</div>
						<div clas="timeline-body-head-action">
							<div class="dropdown dropdown-action">
								<button class="btn p-0 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="bx bx-dots-vertical-rounded"></i>
								</button> 
								<div class="dropdown-menu">';
			if (($user_id == $profile_id) && (($val['reg_date'] + 60 * 60) > time())) {
				$html .= '<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.helper.edit_notes(this,event);" note_id="' . $key . '" for_id="' . $for_id . '"><span>Sửa</span></a>
									<a href="javascript:void(0);" onClick="$Core.crm.delete_notes(this,event);" clsTable="' . $clsTable . '" class="dropdown-item" billing_id="' . $billing_id . '" note_id="' . $key . '" customer_id="' . $customer_id . '" ><span>Xóa</span></a>';
			}
			$html .= '</div>
							</div>
						</div>
					</div>
					<div class="timeline-body-content font-grey-cascade">
						<div class="timeline-body-content__' . $key . '">' . nl2br($val['content']) . '</div>
						<div class="timeline-body-edit__' . $key . ' d-none">
							<form class="frmIssue" name="" action="">
								<textarea class="form-control required" name="content" rows="3" placeholder="Nhập ghi chú">' . $val['content'] . '</textarea>
								<div class="clearfix mt-2">
									<button type="button" onClick="$Core.helper.cancel_notes(this,event)" class="btn btn-sm btn-outline-danger" for_id="' . $for_id . '" note_id="' . $key . '"><i class="icon-ok icon-white"></i> Hủy</button>
									<button type="button" onClick="$Core.crm.save_notes(this,event)" clsTable="' . $clsTable . '" tp="_update" billing_id="' . $billing_id . '" note_id="' . $key . '" customer_id="' . $customer_id . '" class="btn btn-sm btn-outline-primary"><i class="icon-ok icon-white"></i> Lưu</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>';
		}
		$html .= '</div>';
	} else {
		$html .= '<div class="p-4 no-result text-center">
			<img src="' . URL_IMAGES . '/notes.png" width="60px" />
			<p class="text-muted mt-2">Không có ghi chú nào được tạo</p>
		</div>';
	}
	// return
	echo $html;
	die();
}
function default_save_notes(){
	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO, $profile_id, $oneProfile;
	$clsCustomer = new Customer();
	$customer_id = (int) Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$action = Input::post('action', "");
	$note_id = Input::post('note_id', "");
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$list_notes = !empty($oneBilling['notes']) ? $oneBilling['notes'] : array();
	###
	if (!empty($note_id)) {
		if (!empty($list_notes) && @array_key_exists($note_id, $list_notes)) {
			if ($action == "_delete") {
				unset($list_notes[$note_id]);
				$_action = "delete";
			} else {
				$_action = "update";
				$_POST["notes"] = $list_notes[$note_id]['content'];
				$content = Input::post('content');
				$list_notes[$note_id]['content'] = $content;
				$list_notes[$note_id]['upd_date'] = time();
				$list_notes[$note_id]['user_id_update'] = $profile_id;
			}
		}
	} else {
		$content = Input::post('content');
		$list_notes[$clsISO->getUniqid()] = array(
			'content' => $content,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		);
		$_action = "insert";
		$_POST["notes"] = $content;
	}
	$msg = '_error';
	$oneBilling['notes'] = $list_notes;
	$lstBillings[$billing_id] = $oneBilling;
	$more_information['list_billings'] = $lstBillings;
	if ($clsCustomer->updateOne($customer_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))) {
		$msg = '_success';
	}
	// Return
	echo $msg;
	die();
}
function default_ms_save_file(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $oneSetting, $clsISO, $profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	###
	$msg = '_error';
	$customer_id = (int) Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$action = Input::post('action', "");
	$file_id = Input::post('file_id', "");
	$file_id = !empty($file_id) ? $file_id : $clsISO->getUniqid();
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$files = !empty($oneBilling['files']) ? $oneBilling['files'] : array();
	###
	if (isset($_POST['submit']) && $_POST['submit'] == 'Insert') {
		$file_size = 0;
		$file_name = '';
		$attachment = '';
		if (!empty($_FILES['attachment']['name'])) {
			if (@is_uploaded_file($_FILES['attachment']['tmp_name'])) {
				$clsUploadFile = new UploadFile();
				$attachment = $clsUploadFile->uploadItem($_FILES["attachment"], "/GD", EXTENSION_FILE_UPLOAD);
				$file_name = $_FILES['attachment']['name'];
				$file_size = $_FILES['attachment']['size'];
				// Upload file to google drive
				$billing_code = $oneBilling['billing_code'];
				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
				$folder_id = $clsGoogleUpload->create_folder($billing_code);
				// $clsISO->print_pre($folder_id); die();
				$createdFile = $clsGoogleUpload->upload($file_name, $mimeType, ROOTPATH . $attachment, $folder_id);
				$attachment = 'https://drive.google.com/file/d/' . $createdFile->getId() . '/view';
				@unlink(ROOTPATH . $attachment);
			}
		}
		$files[$file_id] = array(
			'description' => Input::post('description'),
			'attachment' => $attachment,
			'file_name'	=> $file_name,
			'file_size'	=> $file_size,
			'user_id'	=> $profile_id,
			'user_id_update'	=> $profile_id,
			'reg_date'	=> time(),
			'upd_date' => time()
		);
		$oneBilling['files'] = $files;
		$lstBillings[$billing_id] = $oneBilling;
		$more_information['list_billings'] = $lstBillings;
		if ($clsCustomer->updateOne($customer_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))) {
			$msg = '_success';
		}
	}
	// output
	echo ($msg);
	die();
}
function default_load_list_files(){
	global $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $oneSetting, $clsISO, $deviceType;
	$clsCustomer = new Customer();
	$customer_id = (int) Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$list_files = !empty($oneBilling['files']) ? $oneBilling['files'] : array();
	if ($deviceType == 'phone') {
		if (!empty($list_files)) {
			$ii = 0; //init
			foreach ($list_files as $file_id => $file) {
				$html .= (!empty($file['attachment']) ? '<a class="link download" href="' . $file['attachment'] . '" target="_blank">' . formatNameFile($file['file_name']) . ' (' . $clsISO->size_calculator($file['file_size']) . ')</a>' : '');
			}
		} else {
			$html .= '<div class="p-4 text-center">
				<img class="mb-2" src="' . URL_IMAGES . '/empty.svg" width="80px" />
				<p class="text-muted text-center">Chưa có tài liệu nào</p>
			</div>';
		}
	} else {
		$html .= '<div class="table-container no-shadow overflow-x-auto"> 
			<table class="table dragable mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
				<thead><tr>
					<th class="align-center h-px-35 bg-white text-left">Miêu tả</th>
					<th class="align-center h-px-35 bg-white text-left">File đính kèm</th>
					<th class="align-center h-px-35 bg-white text-right">Cập nhật</th>
					<th class="align-center h-px-35 bg-white" width="40px"></th>
				</tr></thead>';
		if (!empty($list_files)) {
			$ii = 0; //init
			function sortOrder($a, $b)
			{
				return $b['reg_date'] - $a['reg_date'];
			}
			@uasort($list_files, 'sortOrder');
			foreach ($list_files as $file_id => $file) {
				if (isset($file['attachments'])) {
					$html_file = "";
					foreach ($file['attachments'] as $oKey => $oFile) {
						$html_file .= '<a' . ($clsISO->isPDF($oFile) || $clsISO->isImage($oFile) ? ' data-fancybox="true"' : '') . ' class="link text-nowrap download" href="' . $oFile . '" target="_blank">' . formatNameFile(basename($oFile)) . '</a>';
					}
				} else {
					$html_file = '<a class="link mb-n1" href="' . $file['attachment'] . '" target="_blank">' . formatNameFile($file['file_name']) . ' (' . $clsISO->size_calculator($file['file_size']) . ')</a>';
				}
				$html .= '<tr class="iso_search_item bg-white">
						<td class="text-left">' . ($ii + 1) . '/' . $file['description'] . '</td>
						<td class="text-left"> ' . $html_file . '</td>
						<td class="text-right text-nowrap">' . $clsISO->convertTimeToText($file['reg_date'], true) . '</td>
						<td class="text-center">
							<div class="d-flex gap-1 align-items-center">
								<a class="btn btn-sm btn-icon btn-outline-default" onClick="$Core.crm.open_file_billing_cus(this,event);" file_id="' . $file_id . '" billing_id="' . $billing_id . '" customer_id="' . $customer_id . '" href="javascript:void(0);"><i class="bx bx-pencil"></i></a>
								<a class="btn btn-sm btn-icon btn-outline-default" onClick="$Core.crm.delete_file_billing_cus(this,event);" file_id="' . $file_id . '" billing_id="' . $billing_id . '" customer_id="' . $customer_id . '" href="javascript:void(0);"><i class="bx bx-trash"></i> </a>
							</div>
						</td>
					</tr>';
				++$ii;
			}
		} else {
			$html .= '<tr>
					<td class="text-center" colspan="3">
						Chưa có file đính kèm
					</td>
				</tr>';
		}
		$html .= '</table>
		</div>';
	}
	// Return
	echo @json_encode(array(
		'html'	=> $html
	));
	die();
}
function formatNameFile($name){
	global $core, $dbconn, $clsISO;
	$part_part = pathinfo($name);
	$filename = $part_part['filename'];
	$extension = $part_part['extension'];
	if (strlen($filename) > 30) {
		$l_filename = substr($filename, 0, 10);
		$r_filename = substr($filename, -10);
		return sprintf('%s...%s.%s', $l_filename, $r_filename, $extension);
	} else {
		return $name;
	}
}
function default_open_file_billing_cus(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO, $dbconn;
	$clsCustomer = new Customer();
	$uid = $clsISO->getUniqid();
	$file_id = Input::post('file_id');
	$customer_id = Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$list_files = !empty($oneBilling['files']) ? $oneBilling['files'] : array();
	##
	$action = '_add';
	$oneFile = array();
	if (!empty($file_id)) {
		$action = '_edit';
		$oneFile = $list_files[$file_id];
		// $clsISO->print_pre($oneFile);
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('file_id', $file_id);
	$smarty->assign('oneFile', $oneFile);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('billing_id', $billing_id);
	// Return
	$html = $core->build('_ajax.open_file_billing_cus.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_delete_file_billing_cus(){
	global $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $oneSetting, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	##
	$msg = '_error';
	$customer_id = (int) Input::post('customer_id', 0);
	$billing_id = Input::post('billing_id', "");
	$file_id = Input::post('file_id', "");
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$more_information = $clsISO->to_array_json($oneCustomer["more_information"]);
	$lstBillings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
	$oneBilling = $lstBillings[$billing_id];
	$files = !empty($oneBilling['files']) ? $oneBilling['files'] : array();
	##
	if (!empty($file_id) && !empty($files) && @array_key_exists($file_id, $files)) {
		$oneFile = $files[$file_id];
		unset($files[$file_id]); //Found
		$oneBilling['files'] = $files;
		$lstBillings[$billing_id] = $oneBilling;
		$more_information['list_billings'] = $lstBillings;
		if ($clsCustomer->updateOne($customer_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))) {
			$msg = '_success';
		}
	}
	// Return
	echo ($msg);
	die();
}
function default_loadStaff(){
	global $smarty, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $oneSetting;
	global $core, $clsModule, $clsButtonNav, $oneSetting, $clsISO, $profile_id;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	##
	$msg = '_error';
	$department_id = (int) Input::post('department_id', 0);
	$lstMember = $clsProfile->getAll("`status_id` <> '" . _STATUS_STAFF_OFF_ID . "' AND (`department_id` = '{$department_id}' OR `list_department_id` LIKE '|%{$department_id}%|')", $clsProfile->pkey . ",full_name");
	$html = '<option value="0">Chọn nhân viên</option>';
	foreach ($lstMember as $key => $val) {
		$html .= '<option value="' . $val["profile_id"] . '">' . $val["full_name"] . '</option>';
	}
	// Return
	echo (json_encode(array(
		"html"	=>	$html
	)));
	die();
}
function default_load_interact_customer(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$show = Input::post('show');
	$type = Input::post('type', "interactive");
	$cond = "`is_trash`=0";
	// $cond.= " and JSON_EXTRACT(`more_information`,\"$.logs\")<>''";
	if ($show == 'me') {
		$cond .= " and `admin_id`='{$profile_id}'";
	} else if ($show == 'staff') {
		$staff_id = (int)Input::post("staff_id", 0);
		$cond .= " `admin_id`='{$staff_id}'";
	} else {
		if ($clsISO->checkPermissionGroup('DIRECTOR')) {
			// Next
		} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
			$department_id = $oneProfile['department_id'];
			$cond .= " and `admin_id` in (
				SELECT `{$clsProfile->pkey}` FROM `{$clsProfile->tbl}` 
				WHERE (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')
			)";
		}
	}
	$total_1day = $total_7day = $total_1month = $total_3month = $total_6month = 0;
	$total_customers = $clsCustomer->countItem($cond . " and `resource_id`<>'" . _CRM_RESOURCE_SALEMOC_ID . "'");
	//	$clsFollowUp->setDeBug(1);
	$list_followups = $clsFollowUp->getAll($cond, $clsFollowUp->pkey . ",admin_id,type_id,reg_date");
	$arr_user_1_day = $arr_user_7_day = $arr_user_1_month = $arr_user_3_month = $arr_user_6_month = array();
	if (!empty($list_followups)) {
		foreach ($list_followups as $key => $val) {
			$admin_id = $val['admin_id'];
			if ($val['reg_date'] <= time() && $val['reg_date'] >= strtotime("-1 day", strtotime(date("d-m-Y")))) {
				if (!$clsISO->checkItemInArray($admin_id, $arr_user_1_day)) {
					++$total_1day;
					$arr_user_1_day[] = $admin_id;
				}
			}
			if ($val['reg_date'] <= time() && $val['reg_date'] >= strtotime("-7 days", strtotime(date("d-m-Y")))) {
				if (!$clsISO->checkItemInArray($admin_id, $arr_user_7_day)) {
					++$total_7day;
					$arr_user_7_day[] = $admin_id;
				}
			}
			if ($val['reg_date'] <= time() && $val['reg_date'] >= strtotime("-1 month", strtotime(date("d-m-Y")))) {
				if (!$clsISO->checkItemInArray($admin_id, $arr_user_1_month)) {
					++$total_1month;
					$arr_user_1_month[] = $admin_id;
				}
			}
			if ($val['reg_date'] <= time() && $val['reg_date'] >= strtotime("-3 months", strtotime(date("d-m-Y")))) {
				if (!$clsISO->checkItemInArray($admin_id, $arr_user_3_month)) {
					++$total_3month;
					$arr_user_3_month[] = $admin_id;
				}
			}
			if ($val['reg_date'] <= time() && $val['reg_date'] >= strtotime("-6 months", strtotime(date("d-m-Y")))) {
				if (!$clsISO->checkItemInArray($admin_id, $arr_user_6_month)) {
					++$total_6month;
					$arr_user_6_month[] = $admin_id;
				}
			}
		}
	}
	if ($type == "non-interective") {
		$total_1day = $total_customers - $total_1day;
		$total_7day = $total_customers - $total_7day;
		$total_1month = $total_customers - $total_1month;
		$total_3month = $total_customers - $total_3month;
		$total_6month = $total_customers - $total_6month;
	}
	$smarty->assign("type", $type);
	$smarty->assign("total_customers", $total_customers);
	$smarty->assign("total_1day", $total_1day);
	$smarty->assign("total_7day", $total_7day);
	$smarty->assign("total_1month", $total_1month);
	$smarty->assign("total_3month", $total_3month);
	$smarty->assign("total_6month", $total_6month);
	// Return
	$html = $core->build("_ajax.interact_customer.tpl");
	echo json_encode(array(
		"html"	=>	$html,
		"total_1day"	=>	$total_1day,
		"total_7day"	=>	$total_7day,
		"total_1month"	=>	$total_1month,
		"total_3month"	=>	$total_3month,
		"total_6month"	=>	$total_6month,
	));
}
function default_load_desktop_customer_birthday(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsArchived = new Archived();
	$date_range_follow = Input::post('date_range_follow', "");
	$time_type = Input::post('time_type', 'TODAY');
	$staff_id = Input::post('staff_id', 0);
	$profile_id = !empty($staff_id) ? $staff_id : $profile_id;
	$tmp = $clsISO->getRangeTime($time_type);
	$start_date = $tmp['start_date'];
	$due_date = $tmp['due_date'];
	$arr_dm = array();
	for ($ii = $start_date; $ii <= $due_date; $ii = strtotime("+1 day", $ii)) {
		$arr_dm[] = date("d/m", $ii);
	}
	$lstCustomer = $clsCustomer->getAll("`is_trash`=0 and `admin_id`='{$profile_id}' AND FROM_UNIXTIME(`birthday`,'%d/%m') IN('" . implode("','", $arr_dm) . "')", $clsCustomer->pkey . ",name,reg_date,birthday");
	$arr_customer = [];
	foreach ($lstCustomer as $key => $val) {
		$lstCustomer[$key]['age'] = $clsISO->getAge($val['birthday']);
		$lstCustomer[$key]['birthday'] = date("d/m/Y", $val['birthday']);
		$is_archived = 0;
		$tmp = $clsArchived->getByCond("`profile_id`='{$profile_id}' and `customer_id`='{$val["customer_id"]}'");
		if (!empty($tmp)) {
			$is_archived = 1;
		}
		$icon_archived = !empty($is_archived) ? $clsISO->makeIcon('bx bx-archive-in text-yellow') : $clsISO->makeIcon('bx bx-archive-in text-blank');
		$lstCustomer[$key]['icon_archived'] = $icon_archived;
		$total_followups = $total_followups_next = 0;
		$list_followups = $clsFollowUp->getAll("`customer_id`='{$val["customer_id"]}'", "{$clsFollowUp->pkey},`date_id`");
		if (!empty($list_followups)) {
			$total_followups = count($list_followups);
			foreach ($list_followups as $mkey => $mval) {
				if ($mval['date_id'] >= time()) {
					$total_followups_next += 1;
				}
			}
		}
		$lstCustomer[$key]['total_followups'] = $total_followups;
		$lstCustomer[$key]['total_followups_next'] = $total_followups_next;
	}
	$smarty->assign("lstCustomer", $lstCustomer);
	$html = $core->build("_ajax.customer_birthday.tpl");
	echo json_encode(array(
		"html"	=>	$html
	));
}
function default_load_customer_stats(){
	global $smarty, $assign_list, $core, $clsISO, $mod, $act, $profile_id, $oneProfile;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	$show = Input::post('show', "");
	$act_page = Input::post('_ACT', "default");
	$date_range = Input::post('date_range', "");
	$more_information = $oneProfile['more_information'];
	$sql_string = "";
	if ($show == 'me') {
		$sql_string .= " and `admin_id`='{$profile_id}'";
	} else if ($show  == 'staff') {
		$staff_id = (int) Input::post('staff_id', 0);
		$sql_string .= " and `admin_id`='{$staff_id}'";
	} else {
		if ($clsISO->checkPermissionGroup('DIRECTOR')) {
			// Next
		} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
			$department_id = $oneProfile['department_id'];
			$tmp = $clsProfile->getAll("`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%'", $clsProfile->pkey);
			$arr_staffs = array();
			if (!empty($tmp)) {
				foreach ($tmp as $key => $val) {
					$arr_staffs[] = $val[$clsProfile->pkey];
				}
				unset($tmp);
			}
			$sql_string .= " and `admin_id` in (" . implode(',', $arr_staffs) . ")";
		}
	}
	$property_arrs = $clsProperty->getArraySearchByKey("CUSTOMER_STATUS");
	$list_setting_fields = $core->get_field($more_information, "fieldDataCustomerStatus", _ARRAY_STATUS_FIELD_DEFAULT);
	$arr_status = array();
	if (!empty($list_setting_fields)) {
		foreach ($list_setting_fields as $property_id) {
			$arr_status[$property_id] = [
				"title"		 =>	$property_arrs[$property_id]["title"],
				'bgcolor' => $property_arrs[$property_id]["bgcolor"],
				'textcolor' => $property_arrs[$property_id]["textcolor"]
			];
		}
	}
	// Render html
	$total_customers = $clsCustomer->countItem("`is_trash`=0 " . $sql_string);
	$html = '<div class="gbox gotoLink flex-flow px-2 py-3">
		<div class="d-flex mb-2 align-items-center justify-content-between">
			<h5 class="mb-0 fs-14">Tổng</h5> 
			<a data-bs-toggle="tooltip" class="panel-help help_pop" title="" data-bs-original-title="Tổng số khách hàng quản lý">
				<i class="fa fa-question-circle"></i>
			</a>
		</div>
		<h3 class="fs-5 mb-0 fw-bold text-main">
			<span data-from="0" data-to="1089">' . $total_customers . '</span>
		</h3>
	</div>';
	if (!empty($arr_status)) {
		foreach ($arr_status as $property_id => $val) {
			$cond = "`is_trash`=0 and `status_id`='{$property_id}'";
			$total_customers = $clsCustomer->countItem($cond . $sql_string);
			$html .= '<div class="gbox flex-flow px-2 py-3" style="background-color:' . $val['bgcolor'] . '; border-color:' . $val['bgcolor'] . '">
				<div class="d-flex mb-2 align-items-center justify-content-between">
					<h5 class="mb-0 fs-14 text-nowrap text-white">' . $val["title"] . '</h5> 
					<a data-bs-toggle="tooltip" class="panel-help text-white help_pop" title="Tổng số khách hàng ' . $val["title"] . '">
						<i class="fa fa-question-circle"></i>
					</a>
				</div>
				<h3 class="fs-5 mb-0 fw-bold text-white">
					<span>' . $total_customers . '</span>
				</h3>
			</div>';
		}
	}
	// Return
	echo json_encode(array(
		"html"	=>	$html,
	));
}
function default_load_new_customer_number(){
	global $smarty, $dbconn, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	$sql_string = "";
	if ($clsISO->checkPermissionGroup('DIRECTOR')) {
	} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
		$department_id = $oneProfile["department_id"];
		$sql_string = "(`department_id` = '{$department_id}' OR `list_department_id` LIKE '%|{$department_id}|%')";
	} else {
		// $holderG = Input::post('holderG', "");
		$cond .= " and (`admin_id`='{$profile_id}')";
	}
	$list_customer_box = array(
		'today' => 'Hôm nay',
		'yesterday' => 'Hôm qua',
		'last7days' => '7 ngày qua',
		'thismonth'	=> 'Tháng này'
	);
	$html = "";
	foreach ($list_customer_box as $key => $title) {
		$cond = "is_trash=0";
		if ($key == 'today') {
			$cond .= " and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='" . date('d/m/Y') . "'";
		} else if ($key == 'yesterday') {
			$yesterday = strtotime('-1 day');
			$cond .= " and FROM_UNIXTIME(`reg_date`, '%d/%m/%Y')='" . date('d/m/Y', $yesterday) . "'";
		} else if ($key == 'last7days') {
			$start_week = strtotime("-7 days");
			$end_week = time();
			$cond .= " and (`reg_date` between {$start_week} AND {$end_week})";
		} else if ($key == 'thismonth') {
			$yesterday = strtotime('-1 day');
			$cond .= " and FROM_UNIXTIME(`reg_date`, '%m/%Y')='" . date('m/Y', $yesterday) . "'";
		}
		// $dbconn->debug= true;
		$total_customers = $clsCustomer->countItem($cond . $sql_string);
		$html .= '<div class="gbox flex-fill px-2 py-3">
			<div class="d-flex mb-2 align-items-center justify-content-between">
				<h5 class="mb-0 fs-14">' . $title . '</h5> 
				<a data-bs-toggle="tooltip" class="panel-help help_pop" title="Số khách hàng mới ' . strtolower($title) . '">
					<i class="fa fa-question-circle"></i>
				</a>
			</div>
			<h3 class="fs-5 mb-0 fw-bold text-main">
				<span>' . $total_customers . '</span>
			</h3>
		</div>';
	}
	// Return
	echo json_encode(array(
		"html"	=>	$html,
	));
}
function default_load_sys_staff(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	###
	$date_range = Input::post('date_range', "");
	$cond = "`is_trash`=0 and `status_id` <> '" . _STATUS_STAFF_OFF_ID . "'";
	if ($clsISO->checkPermissionGroup('DIRECTOR')) {
	} else if ($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {
		$department_id = $oneProfile["department_id"];
		$cond .= " AND (`department_id`='{$department_id}' 
			OR `list_department_id` LIKE '%|{$department_id}|%'
		)";
	}
	$sql_followups = "is_trash=0";
	$sql_customer = "`is_trash`=0 and resource_id<>'" . _CRM_RESOURCE_SALEMOC_ID . "'";
	if (!empty($date_range)) {
		if (!empty($date_range)) {
			$tmp = @explode('-', $date_range);
			$start_date = $clsISO->convertTextToTime($tmp[0]);
			$end_date 	= $clsISO->convertTextToTime($tmp[1], "23:59:59");
			$sql_customer .= "  AND (`reg_date` BETWEEN {$start_date} AND {$end_date})";
			$sql_followups .= " AND (`date_id` BETWEEN {$start_date} AND {$end_date})";
		}
	}
	$list_staffs = $clsProfile->getAll($cond);
	foreach ($list_staffs as $key => $val) {
		$staff_id = $val[$clsProfile->pkey];
		$total_call = $total_appointment = $total_zalo = $total_billings = $total_customers = 0;
		$list_customers = $clsCustomer->getAll("{$sql_customer} and `admin_id`='{$staff_id}'", "{$clsCustomer->pkey},more_information");
		if (!empty($list_customers)) {
			$total_customers = count($list_customers);
			foreach ($list_customers as $k_cus => $v_cus) {
				$more_information = $v_cus['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$list_billings = !empty($more_information['list_billings']) ? $more_information['list_billings'] : array();
				$total_billings += count($list_billings);
			}
		}
		$list_followups = $clsFollowUp->getAll("{$sql_followups} AND `admin_id`='{$staff_id}' 
			GROUP BY `type_id`", 'type_id,COUNT(type_id) as `total`');
		if (!empty($list_followups)) {
			foreach ($list_followups as $k_f => $v_f) {
				$type_id = $v_f['type_id'];
				if ($type_id == _FOLLOWUP_CALL_ID) {
					$total_call += (int) $v_f['total'];
				} else if ($type_id == _FOLLOWUP_ZALO_ID) {
					$total_zalo += (int) $v_f['total'];
				} else if ($type_id == _CRM_APPOINTMENT_ID) {
					$total_appointment += (int) $v_f['total'];
				}
			}
		}
		$list_staffs[$key]['total_customers'] = $total_customers;
		$list_staffs[$key]['total_billings'] = $total_billings;
		$list_staffs[$key]['total_call'] = $total_call;
		$list_staffs[$key]['total_zalo'] = $total_zalo;
		$list_staffs[$key]['total_appointment'] = $total_appointment;
	}
	$smarty->assign("list_staffs", $list_staffs);
	// Return
	$html = $core->build("_ajax.list_staff.tpl");
	echo json_encode(array(
		"html"	=>	$html,
		"total_member"	=>	count($lstProfile)
	));
}
function default_load_employ_followup(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile, $dbconn;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	$date_range = Input::post('date_range', "");
	$department_id = $oneProfile["department_id"];
	$arrFollowUpType = $clsproperty->getArraySearchByKey("FOLLOWUP_TYPE");
	$lstFollowUps = $dbconn->getAll("SELECT `t1`.*,`t2`.`full_name` FROM `{$clsFollowUp->tbl}` AS `t1` 
		JOIN `{$clsProfile->tbl}` AS `t2` ON `t1`.`admin_id` = `t2`.`{$clsProfile->pkey}` 
		WHERE `t2`.`department_id` = '{$department_id}' OR `t2`.`list_department_id` LIKE '%|{$department_id}|%' 
		ORDER BY `upd_date` DESC LIMIT 0,10");
	$array_cache_profile = array();
	foreach ($lstFollowUps as $key => $val) {
		$lstFollowUps[$key]["upd_date"] = $clsISO->formatDate($val['upd_date'], 4);
		$lstFollowUps[$key]["type_name"] = $arrFollowUpType[$val['type_id']]["title"];
	}
	$clsISO->print_pre($lstFollowUps);
	die;
	$smarty->assign("lstFollowUps", $lstFollowUps);
	$html = $core->build("_ajax.load_employ_followup.tpl");
	echo json_encode(array(
		"html"	=>	$html,
		"total_member"	=>	count($lstProfile)
	));
}
function default_load_trans_chart(){
	global $smarty, $assign_list, $core, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$group_id = (int)Input::post("group_id", 0);
	$member_id = (int)Input::post("member_id", 0);
	$time_type = Input::post('time_type', 'THIS_MONTH');
	$type = Input::post('type', 'revenue');
	$time_range = $clsISO->getRangeTime($time_type);
	$start_date  = $time_range['start_date'];
	$due_date  = $time_range['due_date'];
	##
	$data = array();
	$dataPoints = array();
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	##
	$cond = "`is_trash`=0 AND `more_information` <> ''";
	if ($clsISO->checkPermissMs()) {
		if (!empty($member_id)) {
			$cond .= " AND `admin_id` = '{$member_id}'";
		}
		// Next
	} else if ($clsISO->checkHeadSale($oneProfile['role_id'])) {
		$department_id = $oneProfile['department_id'];
		$cond .= " AND `admin_id` IN (SELECT {$clsProfile->pkey} FROM {$clsProfile->tbl} WHERE `department_id` = '{$department_id}' OR `list_department_id` LIKE '|%{$department_id}%|')";
		if (!empty($member_id)) {
			$cond .= " AND `admin_id` = '{$member_id}'";
		}
	} else {
		$cond .= " and `admin_id`='{$profile_id}'";
	}
	#
	$text_format = "d/m/Y";
	if (in_array($time_type, ['THIS_WEEK', 'PREV_WEEK'])) {
		$text_format = "d/m/Y";
	} else if (in_array($time_type, ['THIS_MONTH', 'PREV_MONTH'])) {
		$text_format = "d/m/Y";
	} else if (in_array($time_type, ['THIS_YEAR', 'PREV_YEAR'])) {
		$text_format = "m/Y";
	}
	//	$clsCustomer->setDeBug(1);
	$lstCustomer = $clsCustomer->getAll($cond, "more_information");
	//	$clsISO->print_pre($lstCustomer);die;
	$arr_billlings = $arr_time = $arr_bill = array();
	foreach ($lstCustomer as $key => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		if (!empty($more_information["list_billings"])) {
			foreach ($more_information["list_billings"] as $k => $v) {
				if (isset($arr_time[date($text_format, $v['reg_date'])])) {
					$arr_time[date($text_format, $v['reg_date'])]["total_price"] += $v["totalgrand"];
					$arr_time[date($text_format, $v['reg_date'])]["total_billing"] += 1;
				} else {
					//					echo $v["totalgrand"]."<br>";
					$arr_time[date($text_format, $v['reg_date'])]["total_price"] = $v["totalgrand"];
					$arr_time[date($text_format, $v['reg_date'])]["total_billing"] = 1;
				}
			}
		}
	}
	if (in_array($time_type, ['THIS_WEEK', 'PREV_WEEK'])) {
		for ($ii = $start_date; $ii <= $due_date; $ii = strtotime("+1 day", $ii)) {
			if ($type == "billings") {
				$value = (!empty($arr_time[date($text_format, $ii)])) ? $arr_time[date($text_format, $ii)]["total_billing"] : 0;
			} else {
				$value = (!empty($arr_time[date($text_format, $ii)])) ? $arr_time[date($text_format, $ii)]["total_price"] : 0;
			}
			$dataPoints[] = array(
				'label'	=> $clsISO->getDayOfWeek($ii),
				'y'	=> $value
			);
		}
	} else if (in_array($time_type, ['THIS_MONTH', 'PREV_MONTH'])) {
		for ($ii = $start_date; $ii <= $due_date; $ii = strtotime("+1 day", $ii)) {
			if ($type == "billings") {
				$value = (!empty($arr_time[date($text_format, $ii)])) ? $arr_time[date($text_format, $ii)]["total_billing"] : 0;
			} else {
				$value = (!empty($arr_time[date($text_format, $ii)])) ? $arr_time[date($text_format, $ii)]["total_price"] : 0;
			}
			$dataPoints[] = array(
				'label'	=> sprintf('Ngày %s', date('d', $ii)),
				'y'	=> $value
			);
		}
	} else if (in_array($time_type, ['THIS_YEAR', 'PREV_YEAR'])) {
		for ($ii = 1; $ii < 12; $ii++) {
			$y = ($time_type == 'THIS_YEAR') ? date('Y') : date('Y') - 1;
			$my = sprintf('%s/%s', $clsISO->parseNumber($ii), $y);
			if ($type == "billings") {
				$value = (!empty($arr_time[$my])) ? $arr_time[$my]["total_billing"] : 0;
			} else {
				$value = (!empty($arr_time[$my])) ? $arr_time[$my]["total_price"] : 0;
			}
			$dataPoints[] = array(
				'label'	=> sprintf('Tháng %s', $ii),
				'y'	=> $value
			);
		}
	}
	// Return
	if ($type == 'revenue') {
		$data['axisY'] = [
			"labelFormatter"	=>	1,
			"interval"	=> 'function (e) {
				var value = e.$value;
				if (value >= 1000000000) {
				  return (value / 1000000000) + " tỷ";
				} else if (value >= 1000000) {
				  return (value / 1000000) + " triệu";
				} else if (value >= 1000) {
				  return (value / 1000) + " nghìn";
				}
				return value;
			}'
		];
	}
	$data['type'] = 'column';
	//$data['showInLegend'] = 'true';
	//$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	$uid = $clsISO->getUniqid();
	$html = '<div class="chartContainer" style="height:300px">
		<div id="' . $uid . '" class="w-100 h-100"></div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 0,
		'barChartData' => $barChartData
	));
	die();
}
function default_setTimeRemind(){
	global $oSmarty, $smarty, $assign_list, $adminid, $core, $clsISO, $dbconn, $oneProfile, $profile_id;
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$action = Input::post('action', "_OPEN");
	$html = "";
	$result = "false";
	$more_information = $oneProfile["more_information"];
	if ($action == "_OPEN") {
		$time_remind = !empty($more_information['time_remind']) ? $more_information['time_remind'] : _CRM_TIME_REMIND_DEFAULT;
		$smarty->assign("time_remind", $time_remind);
		$list_times = [
			'-15 minutes'	=> 'Trước 15p',
			'-30 minutes'	=> 'Trước 30p',
			'-1 hour'		=> 'Trước 1h',
			'-2 hours'		=> 'Trước 2h',
			'-5 hours'		=> 'Trước 5h',
			'-8 hours'		=> 'Trước 8h',
			'-12 hours'		=> 'Trước 12h',
			'-18 hours'		=> 'Trước 18h',
			'-1 day'		=> 'Trước 1 ngày',
			'-2 days'		=> 'Trước 2 ngày',
			'-7 days'		=> 'Trước 7 ngày',
			'-15 days'		=> 'Trước 15 ngày',
			'-30 days'		=> 'Trước 30 ngày'
		];
		$smarty->assign('list_times', $list_times);
		$smarty->assign('time_remind', $time_remind);
		$html = $core->build("_ajax.set_time_remind.tpl");
		echo $html;
		die;
	} else if ($action == "_SAVE") {
		$time_remind = Input::post("time_remind", _CRM_TIME_REMIND_DEFAULT);
		$more_information['time_remind'] = $time_remind;
		$arr_upd = [
			"more_information"	=>	json_encode($more_information)
		];
		if ($clsProfile->updateOne($profile_id, $arr_upd)) {
			$result = "true";
		}
		echo json_encode(array(
			'uid' => $uid,
			'result'	=>	$result
		));
		die();
	}
}
function default_request_customer(){
	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $title_page, $description_page, $keyword_page, $clsConfiguration, $clsISO, $oneProfile, $profile_id;
	#
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$clsClassTable = new RequestCus();
	$pkeyTable = $clsClassTable->pkey;
	#
	$status = Input::get("status", "");
	$cond = "`_type`='_buy'";
	$total_record = $clsClassTable->countItem($cond);
	$total_assigned = $clsClassTable->countItem($cond . " AND `status`='1'");
	$total_unassigned = $clsClassTable->countItem($cond . " AND `status`='0'");
	if ($status != "") {
		if ($status == 2) {
			$cond .= " AND `status`='0'";
		} else {
			$cond .= " AND `status`='{$status}'";
		}
	}
	$arr_package = $clsSetting->getArraySearchByKey("_PACKAGE_DATA");
	$list_items = $clsClassTable->getAll($cond);
	$arr_profile_cached = array();
	$tmp = $clsProfile->getAll("`is_trash`=0", "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`");
	if (!empty($tmp)) {
		foreach ($tmp as $key => $val) {
			$arr_profile_cached[$val[$clsProfile->pkey]] = $clsProfile->getFullName($val[$clsProfile->pkey], $val);
		}
		unset($tmp);
	}
	foreach ($list_items as $key => $val) {
		$more_item = $clsISO->to_array_json($val["more_information"]);
		$total_paid = !empty($more_item["total_paid"]) ? (int)$more_item["total_paid"] : 0;
		$user_id = (int) $val['user_id'];
		$list_items[$key]["staff_name"] = $arr_profile_cached[$user_id];
		$list_items[$key]["user_update"] = !empty($val['user_status_id']) ? $arr_profile_cached[$val['user_status_id']] : "";
		$list_items[$key]["package_name"] = !empty($arr_package[$more_item["package_id"]]) ? $arr_package[$more_item["package_id"]]["title"] : "";
		$list_items[$key]["total_paid"] = $total_paid;
		$list_items[$key]["total_remining"] = $val["amount"] - $total_paid;
	}
	#
	$assign_list["list_items"] = $list_items;
	$assign_list['total_record'] = $total_record;
	$assign_list['total_assigned'] = $total_assigned;
	$assign_list['total_unassigned'] = $total_unassigned;
	$assign_list["html_pager"] = $html_pager;
	$assign_list['clsClassTable'] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý yêu cầu mua gói khách hàng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_open_data_customer(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	#
	$uid = $clsISO->getUniqid();
	$list_preloaders = array();
	for ($i = 0; $i < 50; $i++) {
		$list_preloaders[] = $i;
	}
	#- Campaign
	$c_field = "{$clsCampaign->pkey},title";
	$cond = "`campaign_type`='_campaign' and (`user_id`='{$profile_id}' OR `use_globe`=1)";
	$list_campaigns = $clsCampaign->getAll("{$cond} order by `reg_date` DESC", $c_field);
	$smarty->assign('uid', $uid);
	$smarty->assign('list_campaigns', $list_campaigns);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$html = $core->build('_ajax.open_data_customer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_loadAmountRequest(){
	global $smarty, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID, $extLang, $clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsRequestCus = new RequestCus();
	#
	$uid = $clsISO->getUniqid();
	$admin_id = (int)Input::post("admin_id", 0);
	$html = "";
	if (!empty($admin_id)) {
		$lstRequestCus = $clsRequestCus->getAll("(`status`='0' || `status`='2') AND `_type`='_buy' AND `user_id`='{$admin_id}'");
		$total = 0;
		if (!empty($lstRequestCus)) {
			$arr_remaining = [];
			foreach ($lstRequestCus as $key => $val) {
				$more_request = $clsISO->to_array_json($val['more_information']);
				$amount = $val["amount"];
				$total_paid = !empty($more_request["total_paid"]) ? (int)$more_request["total_paid"] : 0;
				$total_remaining = $amount - $total_paid;
				if ($total_remaining > 0) {
					$arr_remaining[] = [
						"request_id"	=>	$val[$clsRequestCus->pkey],
						"total"	=>	$total_remaining,
						"project_name"	=>	$val["project_name"],
					];
					$total += $total_remaining;
				}
			}
			if (!empty($arr_remaining)) {
				$html = '<div class="form-group form-row mb-2">
								<div class="col-12 mb-2">
									<div class="form-check form-switch mt-2">
										<input class="form-check-input" name="is_amount_paid" value="1" type="checkbox" id="is_amount_paid" onChange="$Core.crm.showRequest(this,event)" toId="slt_request">
										<label class="form-check-label" for="is_amount_paid">Trả khách</label>
									</div>
								</div>';
				$html .= '<div class="col-12 d-none" id="slt_request">
							<select name="request_id" class="form-control upd_field form-select">';
				foreach ($arr_remaining as $key => $_oItem) {
					$html .= '<option value="' . $_oItem["request_id"] . '">' . ($_oItem["total"] . " khách dự án " . $_oItem["project_name"]) . '</option>';
				}
				$html .= '</select>				
						</div>				
					</div>';
			}
		}
	}
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));
	die();
}
function default_backfill_customer_relations(){
	global $profile_id;
	@set_time_limit(0);
	@ini_set('memory_limit', '1024M');
	header('Content-Type: text/plain; charset=utf-8');
	$clsCustomer = new Customer();
	$clsCustomerMeta = new CustomerMeta();
	if (!$clsCustomer->isRootProfile()) {
		echo "FORBIDDEN\n";
		die();
	}
	echo "SKIP: legacy list_* columns removed\n";
	die();
	$list = $clsCustomer->getAll("1=1", "{$clsCustomer->pkey},user_id,list_share_id,list_campaign_id,list_need_id,list_type_id,list_purpose_id,list_block_id,list_stock_id,list_bedroom_id,list_tags_id");
	$totalCustomers = 0;
	$totalMetaRows = 0;
	$totalByType = array(
		'share' => 0,
		'campaign' => 0,
		'need' => 0,
		'type' => 0,
		'purpose' => 0,
		'block' => 0,
		'stock' => 0,
		'bedroom' => 0,
		'tag' => 0
	);
	if (!empty($list)) {
		foreach ($list as $idx => $row) {
			$customer_id = (int) $row[$clsCustomer->pkey];
			$user_id = (int) $row['user_id'];
			$map = array(
				'share' => $clsCustomer->normalizeIdArray($row['list_share_id']),
				'campaign' => $clsCustomer->normalizeIdArray($row['list_campaign_id']),
				'need' => $clsCustomer->normalizeIdArray($row['list_need_id']),
				'type' => $clsCustomer->normalizeIdArray($row['list_type_id']),
				'purpose' => $clsCustomer->normalizeIdArray($row['list_purpose_id']),
				'block' => $clsCustomer->normalizeIdArray($row['list_block_id']),
				'stock' => $clsCustomer->normalizeIdArray($row['list_stock_id']),
				'bedroom' => $clsCustomer->normalizeIdArray($row['list_bedroom_id']),
				'tag' => $clsCustomer->normalizeIdArray($row['list_tags_id'])
			);
			foreach ($map as $type => $ids) {
				$clsCustomerMeta->syncByCustomerType($customer_id, $type, $ids, $user_id);
				$totalByType[$type] += count($ids);
				$totalMetaRows += count($ids);
			}
			$totalCustomers++;
			if ($totalCustomers % 500 == 0) {
				echo "Processed {$totalCustomers} customers...\n";
			}
		}
	}
	echo "DONE\n";
	echo "customers={$totalCustomers}\n";
	echo "meta_rows={$totalMetaRows}\n";
	foreach ($totalByType as $type => $count) {
		echo "{$type}_rows={$count}\n";
	}
	die();
}
