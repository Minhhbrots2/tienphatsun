<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
if(file_exists(DIR_MODULES.DS.'crm'.DS.'init.php')){
	require_once(DIR_MODULES.DS.'crm'.DS.'init.php');
}
if(file_exists(DIR_MODULES.DS.'crm'.DS.'mod_default.php')){
	require_once(DIR_MODULES.DS.'crm'.DS.'mod_default.php');
}
function default_get_select_city(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
	$clsCountry = new Country();
	$clsCity = new City();
	$country_id = (int) Input::post('country_id', 0);
	$html = $clsCity->makeSelectOption($country_id, 0);
	// return
	echo $html; die();
}
function default_default(){
	global $oSmarty,$smarty,$assign_list,$title_page,$profile_id,$core,$clsISO,$_LANG_ID;
	if(!$clsISO->checkPermission('access_crm')){
		header('Location:/');
		exit();
	}
	//if($profile_id != 9) die();
	//$clsNotify = new Notify();
	$clsCustomer = new Customer();
	// Delete potential
	$clsCustomer->deleteByCond("`is_trash`=1 and `user_id`='{$profile_id}'");
	/*=============Title & Description Page==================*/
	$title_page = 'CRM | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
}
function default_load_desktop(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$dbconn,$clsISO;
	global $oneProfile,$deviceType;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$smarty->assign('clsProperty',$clsProperty);
	$smarty->assign('clsCustomer',$clsCustomer);
	$smarty->assign('clsFollowUp',$clsFollowUp);
	##
	$now = time();
	$type_id = Input::post('type_id','customer');
	$smarty->assign('type_id',$type_id);
	$more_information = $oneProfile['more_information'];
	$hide_help = isset($more_information['hide_help']) 
		? $more_information['hide_help'] : 0;
	$desktop_followup_view = isset($more_information['desktop_followup_view']) 
		? $more_information['desktop_followup_view'] : '_plan';
	$_ss_view_by = isset($more_information['_ss_view_by']) 
		? $more_information['_ss_view_by'] : "table";	
	$smarty->assign('_ss_view_by',$_ss_view_by);	
		
	// $clsISO->print_pre($oneProfile); die();
	$cond = "`is_trash`=0 and `admin_id`='{$profile_id}'";
	//$total_customers = $clsCustomer->countItem($cond);
	//$smarty->assign('total_customers',$total_customers);
	//$htmlSelectUserAdmin = '<option value="0">'.$core->get_Lang('Select admin').'</option>';
	$field = "{$clsProperty->pkey},title,bgcolor";
	$limitCond = ($deviceType=='phone') ? " limit 0,6" : "";
	$list_status_array = $clsProperty->getAllCache("property_type='CUSTOMER_STATUS' and {$clsProperty->pkey}<>'"._CRM_STATUS_DONTCARE_ID."' order by order_no asc".$limitCond,$field);
	$smarty->assign('list_status_array',$list_status_array);
	###
	$list_date_ranges = array(
		array('id' => '_all', 'title' => 'Tất cả'),
		array('id' => '0-3', 'title' => '3 ngày trước'),
		array('id' => '4-7', 'title' => '4-7 ngày trước'),
		array('id' => '8-15', 'title' => '8-15 ngày trước'),
		array('id' => '16-30', 'title' => '16-30 ngày trước'),
		array('id' => '31-60', 'title' => '31-60 ngày trước'),
		array('id' => '61-120', 'title' => '61-120 ngày trước'),	
	);
	foreach($list_date_ranges as $key => $val){
		$list_date_ranges[$key]['total']= 0;
	}
	$smarty->assign('list_date_ranges', $list_date_ranges);
	
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('hide_help',$hide_help);
	$smarty->assign('desktop_followup_view',$desktop_followup_view);
	$smarty->assign('list_preloaders',$list_preloaders);
	$smarty->assign('htmlLoading',CRM::renderHTMLLoading());
	// Return
	$html = $core->build('desktop.tpl');
	echo $html; die();
}
function default_load_desktop_counter(){
	global $smarty,$assign_list,$core,$clsISO;
	global $profile_id, $oneProfile,$deviceType;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsBilling = new Billing();
	
	if($deviceType=='phone'){
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
			), 'THIS_MONTH' => array(
				'title' => 'Tháng<br />này',
				'short_title' => 'Th.này',
				'start_date' => strtotime("first day of this month"),
				'due_date' => strtotime("last day of this month 23:59:59")
			), 'PREV_WEEK' => array(
				'title' => 'Tuần<br />trước',
				'short_title' => 'Tu.trước',
				'start_date' => strtotime('monday last week'),
				'due_date' => strtotime('sunday last week 23:59:59')
			), 'THIS_WEEK' => array(
				'title' => 'Tuần<br />này',
				'short_title' => 'Tu.này',
				'start_date' => strtotime('monday this week'),
				'due_date' => strtotime('sunday this week 23:59:59')
			)
		);
		$uid = $clsISO->getUniqid();
		$html = '<style type="text/css">
			.tbl'.$uid.' > :not(caption) > * > *{
				padding:0.425rem 0.325rem !important;
			}
		</style>
		<div class="table-wrapper">
			<table class="table no-bootstrap table-bordered tbl'.$uid.'">';
			$html.= '<thead><tr>
				<th></th>';
				foreach($list_totals as $key => $val){
					$html.= '<th class="text-center">
						<a class="lh-1">'.$val['title'].'</a>
					</th>';
				}
			$html.='</tr></thead>';
			foreach($list_couters as $key => $val){
				$html.= '<tr>
					<th>'.$val.'</th>';
					foreach($list_totals as $okey => $oval){
						$start_date = $oval['start_date'];
						$due_date = $oval['due_date'];
						if($key=='NUM_CUS'){
							$total = $clsCustomer->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and (`reg_date` between '{$start_date}' and '{$due_date}')");
						} else if($key=='NUM_INTERACT'){
							$total = $clsFollowUp->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and (`date_id` between '{$start_date}' and '{$due_date}')");
						} else if($key=='NUM_CALL'){
							$total = $clsFollowUp->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and type_id='"._FOLLOWUP_CALL_ID."' and (`date_id` between '{$start_date}' and '{$due_date}')");
						} else if($key=='NUM_MEET'){
							$total = $clsFollowUp->countItem("`is_trash`=0 and `admin_id`='{$profile_id}' and type_id='"._FOLLOWUP_TASK_ID."' and (`date_id` between '{$start_date}' and '{$due_date}')");
						} else if($key=='NUM_SOLD') {
							$total = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}' and (`deposit_date` between '{$start_date}' and '{$due_date}')");
						}
						
						$html.='<td class="text-center">'.($key=='NUM_SOLD'?'<a class="font-bold text-main">'.$total.'</a>':'<a href="javascript:void(0);" onClick="$Core.crm.kafa_customer(this, event)" tp="'.$key.'" start_date="'.$start_date.'" due_date="'.$due_date.'" title="'.$val.'" class="font-bold text-main">'.$total.'</a>').'</td>';
					}
				$html.='</tr>';
			}
			$html.='</table>
		</div>';
	} else {
		$tp = Input::post('tp', 'customer');
		$list_totals = array(
			'PREV_MONTH' => array(
				'title' => 'Tháng trước',
				'icon' => 'bx-store-alt bx-sm',
				'total' => 0
			), 'THIS_MONTH' => array(
				'title' => 'Tháng này',
				'icon' => 'bx-trophy bx-sm',
				'total' => 0
			), 'PREV_WEEK' => array(
				'title' => 'Tuần trước',
				'icon' => 'bx-bullseye bx-sm',
				'total' => 0
			), 'THIS_WEEK' => array(
				'title' => 'Tuần này',
				'icon' => 'bx-wallet bx-sm',
				'total' => 0
			)
		);
		
		$cond = "`is_trash`=0";
		if($tp=='customer'){
			$label = 'khách mới';
			$clsClassTable = $clsCustomer;
			if($profile_id != 9){
				$cond.= " and (`admin_id`='{$profile_id}' 
					or `list_share_id` like '%|{$profile_id}|%'
				)";
			}
		} else if($tp=='follow-ups'){
			$label = 'cuộc gặp';
			$clsClassTable = $clsFollowUp;
			if($profile_id != 9){
				$cond.= " and (`admin_id`='{$profile_id}' and `type_id`='"._FOLLOWUP_TASK_ID."')";
			}
		}
		foreach($list_totals as $key => $val){
			if($key=='PREV_MONTH'){
				$start_date = strtotime('first day of last month');
				$due_date = strtotime('last day of last month');
			} else if($key=='THIS_MONTH'){
				$start_date = strtotime("first day of this month");
				$due_date = strtotime("last day of this month");
			} else if($key=='PREV_WEEK'){
				$start_date = strtotime('monday last week');
				$due_date = strtotime('sunday last week');
			} else if($key=='THIS_WEEK'){
				$start_date = strtotime('monday this week');
				$due_date = strtotime('sunday this week');
			}
			$total = $clsClassTable->countItem($cond." and (`reg_date` between '{$start_date}' and '{$due_date}')");
			$list_totals[$key]['total'] = $total;
		}
		$smarty->assign('label',$label);
		$smarty->assign('list_totals',$list_totals);
		// Return
		$html = $core->build('_ajax.counter.tpl');
	}
	echo $html; die();
}
function default_hide_help(){
	global $smarty,$assign_list,$core,$clsISO;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	#
	$more_information = $oneProfile['more_information'];
	$more_information['hide_help'] = 1;
	#
	$msg = "_error";
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_load_desktop_chart_cus(){
	global $smarty,$assign_list,$core,$clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	#
	$data = array();
	$dataPoints = array();
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	#
	$cond = "`is_trash`=0";
	if($clsISO->checkPermissMs() && 1==2){
		// Next
	} else if($clsISO->checkHeadSale($oneProfile['role_id']) && 1==2){
		// Staff ins
	} else {
		$cond.= " and `admin_id`='{$profile_id}'";
	}
	for($i=1; $i<=12; $i++){
		$m = $clsISO->parseNumber($i)."/".date('Y');
		// $clsCustomer->setDebug(true);
		$total = $clsCustomer->countItem("{$cond} and FROM_UNIXTIME(`reg_date`,'%m/%Y')='".$m."'");
		$dataPoints[] = array(
			'label'	=> sprintf('T%s', $i),
			'y'	=> $total*1
		);
	}
	// Return
	$data['type'] = 'spline';
	$data['showInLegend'] = 'false';
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
function default_load_desktop_chart_res(){
	global $smarty,$assign_list,$core,$clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	##
	$data = array();
	$dataPoints = array();
	$barChartData = array();
	$barChartData['animationEnabled'] = true;
	##
	$cond = "`is_trash`=0";
	if($clsISO->checkPermissMs() && 1==2){
		// Next
	} else if($clsISO->checkHeadSale($oneProfile['role_id']) && 1==2){
		// Staff ins
	} else {
		$cond.= " and `admin_id`='{$profile_id}'";
	}
	$field = "{$clsProperty->pkey},title";
	$list_props = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='_CUSTOMER_RESOURCES'", $field);
	if(!empty($list_props)){
		foreach($list_props as $key => $val){
			$property_id = $val[$clsProperty->pkey];
			$total = $clsCustomer->countItem("{$cond} and `resource_id`='{$property_id}'");
			$dataPoints[] = array(
				'label'	=> $clsProperty->getTitle($property_id, $val),
				'y'	=> $total*1
			);
		}
		unset($list_props);
	}
	// Return
	$data['type'] = 'column';
	$data['showInLegend'] = 'true';
	$data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'outside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode($barChartData);
	die();
}
function default_load_calendar(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	
	$holderG = Input::post('holderG','_desktop');
	$typeHoldder = Input::post('typeHoldder','_all');
	if($holderG=='_desktop'){
		$html = '<style type="text/css">
			.fc-event-custom{ text-align:center;}
			.fc-event-skin{background-color:transparent !important; border:0 !important}
		</style>';
	}else{
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
	if($holderG=='_tablist'){
		$html .= '<div class="btn-group pull-right btn-group-toolbar">
			<button type="button" class="btn btn-sm js_filter-calendar btn-'.($typeHoldder=='_all'?'primary':'default').'" v="_all">'.$core->makeIcon('users','Tất cả').'</button>
			<button type="button" class="btn btn-sm js_filter-calendar btn-'.($typeHoldder=='_me'?'primary':'default').'" v="_me">'.$core->makeIcon('user', 'Chỉ tôi').'</button>
		</div>
		<div class="clearfix"></div>';
	}
	$html .= '<div id="calendar'.($holderG=='_desktop'?'_desktop':'').'" class="simple-calendar"></div>';
	if($holderG=='_tablist'){
		$html .= '<div class="clearfix mt-2"></div>
		<div class="d-flex align-items-center pull-left">
			'.($core->makeIcon('calendar mr-2')).' <a href="javascript:void(0);" class="text-link">
			<u>'.$core->get_Lang('Add to your Google Calendar').'</u></a>
		</div>';
	}
	echo $html; die();
}
function default_load_cell_calendar(){
	global $oSmarty,$smarty,$core,$clsISO,$dbconn,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	#- Params
	$start = (int) Input::get('start',0);
	$end = (int) Input::get('end',0);
	$tp = Input::get('tp','follow-ups');
	
	$cond = "`is_trash`=0";
	if($tp=='customer'){
		$field = "reg_date";
		$cond.= " and `admin_id`='{$profile_id}'";
		$clsClassTable = $clsCustomer;
	} else if($tp=='follow-ups'){
		$field = "date_id";
		$cond.= " and `admin_id`='{$profile_id}'";
		$clsClassTable = $clsFollowUp;
	}
	for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
		$date_id = date('d-m-Y',$i);
		$total_record = $clsClassTable->countItem("{$cond} and FROM_UNIXTIME({$field},'%d-%m-%Y')='{$date_id}'");
		$results[] = array(
			'tp' => $tp,
			'title' => PAGE_NAME,
			'number' => $total_record,
			'start' => date('Y-m-d H:i:s',$i)
		);
	}
	// Return
	echo @json_encode($results);
	die();
}
function default_load_followups_month(){
	global $oSmarty,$smarty,$core,$clsISO,$dbconn,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	##
	$start = (int) Input::get('start',0);
	$end = (int) Input::get('end',0);
	$holderG = Input::get('holderG','_desktop');
	##
	$cond = "is_trash=0";
	if($clsISO->checkPermission('full_permissions_crm')){
		if($clsISO->checkPermissMs()){
			
		} else if($clsISO->checkHeadSale()){
			$department_id = $oneProfile['department_id'];
			$list_staffs = $dbconn->getCol("select {$clsProfile->pkey} from {$clsProfile->tbl} 
				where `is_trash`=0 and `is_active`='1' and (`department_id`='{$department_id}' 
				or `list_department_id` like '%|{$department_id}|%')");
			$cond .= " and (`admin_id`='{$profile_id}' or `admin_id` in ".implode(',', $list_staffs).")";
		} else {
			$cond .= " and (`admin_id`='{$adminid}' or `user_id`='{$adminid}')";
		}
	} else {
		$cond .= " and (`admin_id`='{$profile_id}' or `user_id`='{$profile_id}')";
	}
	#
	$results = array();
	if($holderG=='_desktop'){
		for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
			$date_id = date('d-m-Y',$i);
			$numberFollowUp = $clsFollowUp->countItem("{$cond} and FROM_UNIXTIME(date_id,'%d-%m-%Y')='{$date_id}'");
			$results[] = array(
				'title' => PAGE_NAME,
				'number' => $numberFollowUp,
				'start' => date('Y-m-d H:i:s',$i)
			);
		}
	}else{
		$typeHoldder = Input::get('typeHoldder','_all');
		if($clsISO->checkPermission('full_permissions_crm')){
			if($typeHoldder=='_me'){
				$cond .= " and `admin_id`='{$profile_id}'";
			}
		}
		for($i = $start; $i <= $end; $i = strtotime('+1 day',$i)){
			$date_id = date('d-m-Y',$i);
			$lstFollowUp = $clsFollowUp->getAll("{$cond} and FROM_UNIXTIME(date_id,'%d-%m-%Y')='{$date_id}' order by date_id ASC");
			$numberFollowUp = 0;
			$_htmlList = $_htmlTable = '';
			if(!empty($lstFollowUp)){
				$numberFollowUp = count($lstFollowUp);
				$_htmlTable .= '<table class="table table-striped" cellpadding="0" cellspacing="0" width="100%">';
				foreach($lstFollowUp as $followup){
					$customer_id = $followup["customer_id"];
					$crm_followup_id = $followup[$clsFollowUp->pkey];
					$color = $clsFollowUp->getBackground($crm_followup_id, $followup);
					//$Potential = $clsPotential->getPotential($resource_id,false);
					// List
					$props = sprintf('customer_id="%s" crm_followup_id="%s" is_view="1"',$customer_id,$crm_followup_id);
					$_htmlList .= '<a class="event aj_open-followup" id="tooltip_'.$crm_followup_id.'" '.$props.' 
					style="background:'.$color.'"></a>';
					// Table
					$_htmlTable .= '<tr class="trFollowUpCalendar" '.$props.'>
						<td class="aj_open-followup" '.$props.'>
							<a class="event pull-left" style="background:'.$color.'; margin:3px 3px 0 0;"></a> 
							<span class="event-lead-time">'.$clsISO->convertTimeToText($followup['date_id']).'</span>
							<span class="event-lead-link"><i class="fa fa-star"></i> '.$Potential.'</span>
							<b>'.strip_tags(html_entity_decode($followup['content'])).'<b>
						</td>
						<td class="text-center" width="15%">
							<div class="btn-group btn-group-xs ui-btn-group-custom" style="vertical-align:-5px;">
								<button class="btn aj_open-followup-reschedue" '.$props.'><i class="fa fa-clock-o"></i></button>
								<button class="btn btn-danger" '.$props.'>'.$clsISO->makeIcon('bx-trash').'</button>
							</div>
						</td>
					</tr>';
				}
				$_htmlTable .= '</table>';
				unset($lstFollowUp);
			}
			$results[] = array(
				'start' => date('Y-m-d H:i:s',$i),
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
function default_openlinkgooglecalendar(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
	$html.= '<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
				<h3 class="modal-title">
					<strong>'.$core->get_Lang('Add Follow-ups To Google Calendar').'</strong>
				</h3>
			</div>
			<div class="modal-body">
				<div class="entity-content">
					<p>'.$core->get_Lang('Add your tasks to Google Calendar by adding the URL below into Google Calendar').'. 
					'.$core->get_Lang('Google will then update your calendar with new task changes every few hours').'.</p>
					<div class="alert alert-info text-center mb-half">
						<p>'.$core->get_Lang('The URL to add to your Google Calendar is').'</p>
						<p><strong>'.PCMS_URL.'/calendar/followups/'.CRM::encryptID($adminid).'</strong></p>
					</div>
					<u>'.$core->get_Lang('Notes').'</u>
					<ul>
						<li><strong>'.$core->get_Lang('Step').' 1</strong>: '.$core->get_Lang('In the "Other Calendars" section,select "Add by URL"').'</li>
						<li><strong>'.$core->get_Lang('Step').' 2</strong>: '.$core->get_Lang('Paste the URL for your calendar,select whether to make the calendar publicly accessible,and click "Add Calendar"').'</li>
						<li><strong>'.$core->get_Lang('Step').' 3.</strong>: '.$core->get_Lang('A new calendar called "My TraelMaster Cal" will appear in your list of other calendars.').'</li>
					</ul>
				</div>
			</div>
			<div class="modal-footer"> 
				<button class="btn btn-warning close_pop" data-dismiss="modal" aria-hidden="true">'.$core->get_Lang('Close').'</button>
			</div>
		</div>
	</div>';
	// Output
	echo $html; die();
}
function default_set_view_desktop_followup(){
	global $smarty,$profile_id,$oneProfile,$core,$dbconn,$clsISO;
	$clsProfile = new Profile();
	$more_information = $oneProfile['more_information'];
	#
	$tp = Input::post('tp','_plan');
	$more_information['desktop_followup_view'] = $tp;
	#
	$msg = "_error";
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_load_desktop_followups(){
	global $deviceType,$smarty,$profile_id,$oneProfile,$core,$dbconn,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	##
	$more_information = $oneProfile['more_information'];
	$desktop_followup_view = isset($more_information['desktop_followup_view']) 
		&& !empty($more_information['desktop_followup_view']) 
		? $more_information['desktop_followup_view'] : "_plan";
	###
	$html = '';
	$typeHolder = Input::post('typeHolder','_all');
	$keysearch =  Input::post('keysearch','');
	$sort_by =  Input::post('sort_by','date_id');
	$sort_type =  Input::post('sort_type','desc');
	#- Cond
	$cond = "`is_trash`=0 and `admin_id`='{$profile_id}' 
		and `type_id` in (".implode(',', array(_FOLLOWUP_CALL_ID,_FOLLOWUP_TASK_ID,_FOLLOWUP_ZALO_ID)).") ";
	if($desktop_followup_view=="_plan"){
		$cond.= " and `status_id`='"._FOLLOWUP_STATUS_PLAN_ID."'";
	}
	if($typeHolder=='_calendar'){
		$date_id = Input::post('date_id');
		$cond.= " and FROM_UNIXTIME(`date_id`,'%d/%m/%Y')='{$date_id}'";
	} else {
		$tp = Input::post('tp', 'today');
		if($tp=='yesterday') {
			$date_id  = strtotime("-1 day");
		} else if($tp=='tomorrow'){
			$date_id  = strtotime("+1 day");
		} else {
			$date_id = time();
		}
		$cond.= " and FROM_UNIXTIME(`date_id`,'%d/%m/%Y')='".date('d/m/Y', $date_id)."'";
		// $clsISO->print_pre($date_id); die();
	}
	$html= '';
	#- Pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',10);
	$total_record = $clsFollowUp->countItem($cond);
	// $total_page = @ceil($total_record/$per_page);
	// $offset = ($current_page-1) * $per_page;
	// $limitCond = " limit {$offset},{$per_page}";
	#- End Pgination
	$list_followups = $clsFollowUp->getAll($cond." order by `date_id` DESC");
	if(!empty($list_followups)){ $ii= 1;
		$html.= ($typeHolder=='_calendar'?'
		<input type="hidden" class="search_followups" data-field="date_id" value="'.$date_id.'" />
		<input type="hidden" class="search_followups" data-field="typeHolder" value="'.$typeHolder.'" />':'').'
		<table class="table table-no-border-end table-middle" width="100%">';
		$arr_property_cached = array();
		foreach($list_followups as $followup){
			$date_id = $followup['date_id'];
			$type_id = $followup['type_id'];
			$status_id = $followup['status_id'];
			$customer_id = $followup['customer_id'];
			$oneCustomer = $clsCustomer->getOne($customer_id, "phone");
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
					<div class="mb-n0"><a href="javascript:void(0)" class="font-bold  link goLink view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/'.$customer_id.'/overview" customer_id="'.$customer_id.'">'.$clsCustomer->getName($customer_id).'</a></div>
					<div class="line-clamp-2 cursor-pointer" onclick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'">'.$followup['intro'].'</div>
					<span class="text-'.($date_id>time()?'main':'muted').' text-nowrap fs-12">
						<i class="material-icons-outlined">notifications_active</i>
						'.$clsISO->convertTimeToText($followup['date_id'],true).'
					</span>
				</td>':'<td class="text-left">
					<div class="mb-n1"><a href="javascript:void(0)" class="font-bold  link goLink view_customer" onClick="$Core.crm.open_customer(this,event)" route="/customer/'.$customer_id.'/overview" customer_id="'.$customer_id.'">'.$clsCustomer->getName($customer_id).'</a></div>
					<span class="text-'.($date_id>time()?'main':'muted').' text-nowrap fs-12">
						<i class="material-icons-outlined">notifications_active</i>
						'.$clsISO->convertTimeToText($followup['date_id'],true).'
					</span>
				</td>
				<td class="text-left cursor-pointer" onclick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'">
					<div class="line-clamp-2">'.$followup['intro'].'</div>
					'.(!empty($followup['_result']) ? '--- <br /> <strong>KQ:</strong> '.$followup['_result'] : '' ).'
				</td>').'
				<td width="30px" class="text-center">
					<div class="btn-group">
						<a href="javascript:void(0);" title="Hoàn thành" tp="follow-ups" type_id="'.$type_id.'" class="btn btn-icon btn-sm btn-outline-default'.($status_id==_FOLLOWUP_STATUS_DONE_ID?' disabled':'').'" onclick="$Core.crm.done_followup(this, event);" followup_id="'.$followup_id.'" customer_id="'.$customer_id.'"><i class="bx bx-check"></i></a>
						<button title="Follow-ups" onclick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="btn btn-icon btn-sm btn-outline-default"><i class="bx bx-bell"></i></button>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		$html.= '</table>';
	}else{
		$html .= '<div class="py-3 text-center">
			<div class="py-2">
				'.CRM::renderHTMLNoDocument('Rất tiếc <br /> 
				Bạn chưa có bất kỳ follow-up(s) nào được tạo').'
			</div>
		</div>';
	}
	// output
	echo @json_encode(array(
		'cond' => $cond,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'html'	=> $html
	)); die();
}
function default_load_followups(){
	global $smarty,$assign_list,$profile_id,$oneProfile,$core,$dbconn,$clsISO;
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	
	$html = '';
	$holderG = Input::post('holderG','_all'); // desktop, _search,
	$typeHolder = Input::post('typeHolder','_all');
	$customer_id = (int) Input::post('customer_id',0);
	$sort_by =  Input::post('sort_by','date_id');
	$sort_type =  Input::post('sort_type','desc');
	$keySearch =  Input::post('keySearch','');
	// Pagination
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',5);
	$cond = "`is_trash`=0 and `customer_id`='{$customer_id}'";
	if(!empty($keySearch)){
		$cond .= " and `customer_id` IN (
			select customer_id from ".$clsCustomer->tbl." 
			where name like '%".$keySearch."%' 
				or name_slug like '%".$core->replaceSpace($keySearch)."%' 
				or email like '%".$keySearch."%' 
				or phone like '%".$keySearch."%' 
				or address like '%".$keySearch."%'
			)";	
	}
	$total_record = $clsFollowUp->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$list_followups = $clsFollowUp->getAll($cond." order by {$sort_by} {$sort_type}".$limitCond);
	// $clsISO->print_pre($list_followups); die();
	if(!empty($list_followups)){ $ii= 1;
		foreach($list_followups as $followup){
			$customer_id = $followup['customer_id'];
			$followup_id = $followup[$clsFollowUp->pkey];
			$props = 'customer_id="'.$customer_id.'" followup_id="'.$followup_id.'"';
			$htmlAction = '<div class="dropdown">
				<button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
					<i class="bx bx-dots-vertical-rounded" aria-hidden="true"></i>
				</button>
				<div class="dropdown-menu">
					<a href="javascript:;" class="dropdown-item" onClick="$Core.crm.open_followups(this, event)" '.$props.'>'.$core->get_Lang('Edit').'</a>
					<a href="javascript:;" class="dropdown-item" onClick="$Core.crm.delete_followups(this, event)" '.$props.'>'.$core->get_Lang('Delete').'</a>
				</div>
			</div>';
			$html.= '<tr>
				<td class="text-center">'.($ii).'</td>
				<td class="text-left">
					<i style="transform: translateY(5px);" class="material-icons-outlined mr-1">more_time</i> 
					'.$clsISO->convertTimeToText($followup['date_id'],true).'
				</td>
				<td class="text-left">'.$clsFollowUp->getHTMLType($crm_followup_id,$followup).'</td>
				<td class="text-left">'.$clsProfile->getFullName($followup['admin_id']).'</td>
				<td class="text-left">'.$followup['intro'].'</td>
				<td class="text-center">'.$htmlAction.'</td>
			</tr>';
			++$ii;
		}
	}else{
		$html .= '<tr>
			<td class="text-center" colspan="7">
				'.CRM::renderHTMLNoDocument('Not any foolow-Ups').'
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
	)); die();
}
function default_open_file(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO,$dbconn;
	$clsCustomer = new Customer();
	$uid = $clsISO->getUniqid();
	$file_id = Input::post('file_id');
	$customer_id = Input::post('customer_id', 0);
	##
	$action = '_add';
	$oneFile = array();
	if(!empty($file_id)){
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
	)); die();
}
function default_save_file(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$profile_id;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	##
	$msg = '_error';
	$file_id = Input::post('file_id', "");
	$customer_id = (int) Input::post('customer_id', 0);
	$files = $clsCustomer->getOneField('files', $customer_id);
	$files = $clsISO->to_array_json($files);
	if(isset($_POST['hid']) && $_POST['hid'] == 'upload'){
		$attachments = array();
		if(!empty($_FILES['attachments']['name'])){
			for($i=0;$i<count($_FILES['attachments']['name']);$i++){
				$file = array();
				$file["name"] = $_FILES['attachments']['name'][$i];
				$file["type"] = $_FILES['attachments']['type'][$i];
				$file["tmp_name"] = $_FILES['attachments']['tmp_name'][$i];
				$file["error"] = $_FILES['attachments']['error'][$i];
				$file["size"] = $_FILES['attachments']['size'][$i];
				if(!is_uploaded_file($file['name'])){
					$clsUploadFile = new UploadFile(); // attachments
					$up = $clsUploadFile->uploadItem($file,'/ex',"pdf,doc,docx,xls,xlsx,csv,txt,zip,jpg,jpeg,png,gif");
					if(!empty($up) && file_exists(ABSPATH . $up)){
						$attachments[] = $up;
					}
				}
			}
		}
		if(!empty($file_id)){
			$files[$file_id]['description'] = Input::post('description');
			$files[$file_id]['group_id'] = Input::post('group_id', 0);
			if(!empty($attachments) && file_exists(ABSPATH.$attachment)){
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
		if($clsCustomer->updateOne($customer_id, array(
			'files'	=> json_encode($files, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
		}
	}
	// output
	echo($msg); die();
}
function default_delete_file(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$profile_id;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	##
	$msg = '_error';
	$file_id = Input::post('file_id', "");
	$customer_id = (int) Input::post('customer_id', 0);
	$files = $clsCustomer->getOneField('files', $customer_id);
	$files = !empty($files) ? @json_decode(html_entity_decode($files), true) : array();
	##
	if(!empty($file_id) && !empty($files) && @array_key_exists($file_id, $files)){
		$oneFile = $files[$file_id];
		unset($files[$file_id]); //Found
		if($clsCustomer->updateOne($customer_id, array(
			'files'	=> json_encode($files, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			@unlink(ABSPATH.$oneFile['attachment']);
		}
	}
	// Return
	echo($msg); die();
}
function default_load_list_billing(){
	global $smarty,$core,$clsISO,$oneProfile; 
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
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',20);
	#- Pagination
	$cond = "is_trash=0 and `customer_id`='{$customer_id}'";
	$total_record = $clsBilling->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$field = "*";
	$list_billings = $clsBilling->getAll($cond." order by reg_date DESC".$limitCond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		foreach($list_billings as $key => $val){
			$partner_id = $val['partner_id'];
			$project_id = $val['project_id'];
			$billing_type = $val['billing_type'];
			###
			if($partner_id > 0){
				if(isset($arr_property_cached[$partner_id])){
					$list_billings[$key]['partner_name'] = $arr_property_cached[$partner_id];
				} else {
					$arr_property_cached[$partner_id] = $clsProperty->getTitle($partner_id);
					$list_billings[$key]['partner_name'] = $arr_property_cached[$partner_id];
				}
			} else {
				$list_billings[$key]['partner_name'] = "";
			}
			###
			if($billing_type > 0){
				if(isset($arr_property_cached[$billing_type])){
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				} else {
					$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
					$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
				}
			} else {
				$list_billings[$key]['billing_type'] = "";
			}
			###
			if($project_id > 0){
				if(isset($arr_projects_cached[$project_id])){
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				} else {
					$arr_projects_cached[$project_id] = $clsProject->getTitle($project_id);
					$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
				}
			} else {
				$list_billings[$key]['poroject_name'] = "";
			}
		}
	}
	// $clsISO->print_pre($list_billings); die();
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
	)); die();
}
function default_view_activity(){
	global $smarty,$mod,$act,$adminid,$core,$clsISO,$profile_id;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	#
	$customer_id = (int) Input::post('customer_id', 0);
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
	$list_share_id = $oneCustomer['list_share_id'];
	$arr_share_ids = $clsISO->getArrayByTextSlash($list_share_id);
	#- Permiss
	$permiss_action = $permiss_notes = 0;
	if($oneCustomer['admin_id'] == $profile_id){
		$permiss_action = 1;
	}
	if(in_array($profile_id, $arr_share_ids)){
		$permiss_notes = 1;
	}
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
	$html = $core->build('_ajax.activity.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_consulting(){
	global $smarty,$assign_list,$adminid,$core,$clsISO;
	$clsStock = new Stock();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$list_stock_id = $clsCustomer->getOneField('list_stock_id', $customer_id);
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
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	##
	$customer_id = (int) Input::post('customer_id', 0);
	$oCustomer = $clsCustomer->getOne($customer_id, "more_information");
	$more_information = $oCustomer['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$list_logs = !empty($more_information['logs']) ? $more_information['logs'] : array();
	$html = '<div class="table-container no-shadow overflow-x-auto text-nowrap">
	<table cellpadding="0" cellspacing="0" class="table mb-0">
		<thead><tr>
			<th class="align-center bg-lighter">Người</th>
			<th class="align-center bg-lighter" width="130px">Thời gian</th>
			<th class="align-center bg-lighter" width="20%">T.Trạng</th>
		</tr></thead>';
	if(!empty($list_logs)){
		$arr_profile_cached = array();
		$arr_property_cached = array();
		foreach($list_logs as $key => $val){
			$_type = $val['_type'];
			$from_id = $val['from_id'];
			$status_id = $val['status_id'];
			if(!isset($arr_profile_cached[$from_id])){
				$arr_profile_cached[$from_id] = $clsProfile->getAvatar($from_id,array(),30,30);
			}
			if(!isset($arr_property_cached[$status_id])){
				$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
			}
			$html.='<tr class="text-nowrap">';	
				if($_type=='upd_status'){
					$from_status_id = $val['from_status_id'];
					if(!isset($arr_property_cached[$from_status_id])){
						$arr_property_cached[$from_status_id] = $clsProperty->getTitle($from_status_id);
					}
					$html.= '<td class="text-left">
						<img class="avatar avatar-xxs rounded-pill" src="'.$arr_profile_cached[$from_id].'" />
					</td>
					<td class="text-left fs-13">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
					<td class="text-left">'.$arr_property_cached[$from_status_id].' 
						'.$core->makeIcon('long-arrow-right').' '.$arr_property_cached[$status_id].'</td>';
				} else {
					$to_id = $val['to_id'];
					if(!isset($arr_profile_cached[$to_id])){
						$arr_profile_cached[$to_id] = $clsProfile->getAvatar($to_id,array(),30,30);
					}
					$html.= '<td class="text-left">
						<img class="avatar avatar-xxs rounded-pill" src="'.$arr_profile_cached[$from_id].'" />
						'.$core->makeIcon('long-arrow-right').'
						<img class="avatar avatar-xxs rounded-pill" src="'.$arr_profile_cached[$to_id].'" />
					</td>
					<td class="text-left fs-13">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
					<td class="text-left">'.$arr_property_cached[$status_id].'</td>';
				}
			$html.= '<tr>';
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
function default_load_activity(){
	global $smarty,$profile_id,$core,$clsISO;
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsFollowUp', $clsFollowUp);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$customer_id = (int) Input::post('customer_id', 0);
	$smarty->assign('customer_id', $customer_id);
	$list_followups = $clsFollowUp->getAll("`is_trash`=0 and `customer_id`='{$customer_id}' order by `reg_date` DESC");
	if(!empty($list_followups)){
		$arr_property_cached = $arr_profile_cached = array();
		foreach($list_followups as $key => $val){
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
			$list_reply = $clsFollowUp->getAll("`parent_id`='{$followup_id}' order by reg_date ASC");
			$list_followups[$key]['list_reply'] = $list_reply;
		}
	} else {
		$htmlNotFound = CRM::renderHTMLNoDocument('Không có bất kỳ hoạt động nào<br /> với khách hàng này');
		$smarty->assign('htmlNotFound', $htmlNotFound);
	}
	$smarty->assign('list_followups', $list_followups);
	#- Permiss
	$permiss_action = 0;
	if($clsCustomer->getOneField('admin_id', $customer_id)==$profile_id){
		$permiss_action = 1;
	}
	$smarty->assign('permiss_action', $permiss_action);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.activity.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_activity(){
	global $smarty,$assign_list,$adminid,$core,$clsISO;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsCustomer', $clsCustomer);
	##
	$tp = Input::post('tp');
	$customer_id = (int) Input::post('customer_id', 0);
	$type_id = (int) Input::post('type_id', _FOLLOWUP_CALL_ID);
	if($tp=='notes'){
		$note_id = Input::post('note_id');
		$smarty->assign('note_id', $note_id);
		$props = array(
			'tp' => $tp,
			'note_id' => $note_id,
			'customer_id' => $customer_id
		);
	} else {
		$followup_id = (int) Input::post('followup_id', 0);
		$smarty->assign('followup_id', $followup_id);
		$props = array(
			'tp' => $tp,
			'followup_id' => $followup_id,
			'customer_id' => $customer_id
		);
		$oCustomer = $clsCustomer->getOne($customer_id,"status_id,list_purpose_id");
		$list_purpose_id = $oCustomer['list_purpose_id'];
		$list_purpose_arr = !empty($list_purpose_id) ? $clsISO->getArrayByTextSlash($list_purpose_id) : array();
		$oCustomer['list_purpose_arr'] = $list_purpose_arr;
		$smarty->assign('oCustomer', $oCustomer);
	}
	$smarty->assign('tp', $tp);
	$smarty->assign('type_id', $type_id);
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('props', $clsISO->make_attrs_builder($props));
	##
	$time_def_id = $titlePage = "";
	if($tp=='notes'){
		$titlePage = "Thêm ghi chú";
	} else {
		$action = "_add";
		$titlePage = "Thêm ".$clsProperty->getTitle($type_id);
		$title = sprintf('%s - %s', $clsProperty->getTitle($type_id), $clsCustomer->getName($customer_id));
		$oneItem = array("date_id" => time(), "title" => $title, "content" => "");
		if($followup_id > 0){
			$action = "_edit";
			$oneItem = $clsFollowUp->getOne($followup_id);
		}
		###
		$list_times = array();
		$list_times['15minutes'] = 'Sau 15p';
		$list_times['30minutes'] = 'Sau 30p';
		$list_times['1hour'] = 'Sau 1h';
		$list_times['2hours'] = 'Sau 2h';
		$list_times['5hours'] = 'Sau 5h';
		$list_times['8hours'] = 'Sau 8h';
		$list_times['12hours'] = 'Sau 12h';
		$list_times['18hours'] = 'Sau 18h';
		$list_times['1day'] = 'Sau 1 ngày';
		$list_times['2days'] = 'Sau 2 ngày';
		$list_times['7days'] = 'Sau 7 ngày';
		$list_times['15days'] = 'Sau 15 ngày';
		$list_times['30days'] = 'Sau 30 ngày';
		$smarty->assign('list_times', $list_times);
		// Nếu không có FU nào thì mặc định time_def_id = 1day;
		if($clsFollowUp->countItem("customer_id='{$customer_id}'") == 0){
			$time_def_id = '1day';
			$oneItem['date_id'] = strtotime('+1 day');
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
	)); die();
}
function default_open_reply(){
	global $smarty,$assign_list,$adminid,$core,$clsISO;
	$clsFollowUp = new FollowUp();
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
	)); die();
}
function default_save_reply(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$clsISO;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	###
	$msg = "_error"; $time = time();
	$parent_id = (int) Input::post('parent_id', 0);
	$followup_id = (int) Input::post('followup_id', 0);
	$customer_id = (int) Input::post('customer_id', 0);
	$content = Input::post('content');
	if($followup_id > 0){
		if($clsFollowUp->updateOne($followup_id, array(
			'intro' => $content,
			'upd_date' => $time,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		if($clsFollowUp->insert(array(
			'customer_id' => 0,
			'parent_id' => $parent_id,
			'intro' => $content,
			'reg_date' => $time,
			'upd_date' => $time,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
			$oneFollowUp = $clsFollowUp->getOne($parent_id);
			$user_id = $oneFollowUp['user_id']; // Người tạo Follow-up
			// Người reply Follow-up không phải là người tạo.
			if($profile_id != $oneFollowUp['user_id']){
				$contentNotify = sprintf('<strong>%s</strong> đã bình luận vào hoạt động của bạn <strong>%s</strong> 
					với nội dung <strong>%s</strong> vào lúc <i>%s</i>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsFollowUp->getContent($parent_id, $oneFollowUp), 
					$content, $clsISO->convertTimeToText($time, true));
				$clsNotify->insertNotify('FollowUp',$clsFollowUp->pkey,$parent_id,$contentNotify,time(),'|'.$oneFollowUp['user_id'].'|');
			} else if($total_replys > 0){
				$list_user_notify = array();
				$list_replys = $clsFollowUp->getAll("`parent_id`='{$parent_id}' and `user_id`<>'{$user_id}'", "user_id");
				if(!empty($list_replys)){
					foreach($list_replys as $key => $val){
						$list_user_notify[] = $val['user_id'];
					}
				}
				if(!empty($list_user_notify)){
					$contentNotify = sprintf('<strong>%s</strong> đã bình luận vào hoạt động <strong>%s</strong> 
						với nội dung <strong>%s</strong> vào lúc <i>%s</i>', 
						$clsProfile->getFullName($profile_id, $oneProfile), 
						$clsFollowUp->getContent($parent_id, $oneFollowUp), 
						$content, $clsISO->convertTimeToText($time, true));
					// $clsISO->print_pre($contentNotify); die();
					$clsNotify->insertNotify('FollowUp',$clsFollowUp->pkey,$parent_id,$contentNotify,time(),$list_user_notify);
				}
			}
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_campare_date_now(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$clsISO;
	$date_id = Input::post('date_id');
	$time_id = Input::post('time_id');
	$datetime = $clsISO->convertTextToTime($date_id, $time_id);
	###
	$is_done = 1;
	if($datetime > time()){
		$is_done = 0;
	}
	// Return
	echo $is_done; die();
}
function default_set_timerange(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$clsISO;
	$after_time = Input::post('after_time');
	$time = time();
	if($after_time=='15minutes'){
		$time = strtotime("+15 minutes", $time);
	} else if($after_time=='30minutes'){
		$time = strtotime("+30 minutes", $time);
	} else if($after_time=='1hour'){
		$time = strtotime("+1 hour", $time);
	} else if($after_time=='2hours'){
		$time = strtotime("+2 hours", $time);
	} else if($after_time=='5hours'){
		$time = strtotime("+5 hours", $time);
	} else if($after_time=='8hours'){
		$time = strtotime("+8 hours", $time);
	} else if($after_time=='12hours'){
		$time = strtotime("+12 hours", $time);
	} else if($after_time=='18hours'){
		$time = strtotime("+18 hours", $time);
	} else if($after_time=='1day'){
		$time = strtotime("1 day", $time);
	} else if($after_time=='2days'){
		$time = strtotime("+2 days", $time);
	} else if($after_time=='7days'){
		$time = strtotime("+7 days", $time);
	}  else if($after_time=='15days'){
		$time = strtotime("+15 days", $time);
	}  else if($after_time=='30days'){
		$time = strtotime("+30 days", $time);
	} 
	// Return
	echo json_encode(array(
		'date' => date('d/m/Y', $time),
		'time' => date('H:i', $time)
	)); die();
}
function default_save_activity(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$clsISO,$oneProfile;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();	
	
	$msg = "_error";
	$tp = Input::post('tp', 'follow-ups');
	$intro = Input::post('intro', ""); // Content
	$customer_id = (int) Input::post('customer_id', 0);
	$oCustomer = $clsCustomer->getOne($customer_id, "admin_id,list_share_id,status_id,more_information,notes,name");
	$admin_id = $oCustomer['admin_id'];
	$list_share_id = $oCustomer['list_share_id']; 
	$more_information = $oCustomer['more_information']; 
	$more_information = $clsISO->to_array_json($more_information);
	$list_logs = isset($more_information['logs']) && !empty($more_information['logs']) 
		? $more_information['logs'] : array();
	if($tp=='follow-ups'){
		$type_id = (int) Input::post('type_id', 0);
		$followup_id = (int) Input::post('followup_id', 0);
		$cus_status_id = (int) Input::post('cus_status_id', 0);
		$cus_purpose_id = Input::post('cus_purpose_id', 0);
		$is_done = (int) Input::post('is_done', 0);
		$status_id = ($is_done==1 ? _FOLLOWUP_STATUS_DONE_ID: _FOLLOWUP_STATUS_PLAN_ID);
		$date_id = Input::post('date_id');
		$time_id = Input::post('time_id');
		$_result = Input::post('_result');
		$datetime = $clsISO->convertTextToTime($date_id, $time_id);
		$list_purpose_id = !empty($cus_purpose_id) ? $clsISO->makeSlashListFromArray($cus_purpose_id) : "";
		// $clsISO->print_pre($list_purpose_id); die();
		if($followup_id == 0){
			$followup_id = $clsFollowUp->getMaxId();
			// $clsFollowUp->setDebug(true);
			if($clsFollowUp->insert(array(
				$clsFollowUp->pkey => $followup_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'customer_id' => $customer_id,
				'intro' => $intro,
				'_result' => $_result,
				'date_id' => $datetime,
				'admin_id' => $profile_id,
				'user_id' => $profile_id,
				'user_id_update' => $profile_id,
				'reg_date' => time(),
				'upd_date' => time()
			))){
				$msg = "_success";	
				#activity log
				$clsActivityLog = new ActivityLog();			
				$log = $clsActivityLog->addActivityLog("FollowUp","insert",$_POST);
				if($datetime > time()){
					$title = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào 
					lúc <strong>%s</strong>.', $clsProperty->getTitle($type_id) .": 
					".$intro, $clsCustomer->getName($customer_id), sprintf('%s %s', $date_id, $time_id));
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $title, $datetime, '|'.$profile_id.'|');
				}
				// Người tạo là người phụ trách bắn thông báo cho người liên quan
				if($admin_id == $profile_id && !empty($list_share_id)){
					$list_notify_users = $clsISO->getArrayByTextSlash($list_share_id);
					$titleNotify = sprintf('<strong>%s</strong> đã thêm tương tác với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($datetime, true));
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNotify, time(), $list_notify_users);
				// Người tạo là người liên quan thì bắn notify cho người phụ trách
				} else if($admin_id != $profile_id){
					$titleNotify = sprintf('<strong>%s</strong> đã thêm tương tác với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($datetime, true));
					$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNotify, time(), '|'.$profile_id.'|');
				}
				if($oCustomer['status_id'] != $cus_status_id){
					$list_logs[$clsISO->getUniqid()] = array(
						'_type' => 'upd_status',
						'from_id' => $profile_id,
						'from_status_id' => $oCustomer['status_id'],
						'status_id' => $cus_status_id,
						'reg_date' => time()
					);
					$more_information['logs'] = $list_logs;
				}
				$clsCustomer->updateOne($customer_id, array(
					'upd_date' => time(),
					'status_id' => $cus_status_id,
					'list_purpose_id' => $list_purpose_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				));
			}
		} else {
			$oneFollowup = $clsFollowUp->getOne($followup_id);
			if($clsFollowUp->updateOne($followup_id, array(
				'intro' => $intro,
				'date_id' => $datetime,
				'status_id' => $status_id,
				'user_id_update' => $profile_id,
				'_result' => $_result,
				'upd_date' => time()
			))){
				$msg = "_success";			
				#activity log	
				$clsActivityLog = new ActivityLog();		
				$log = $clsActivityLog->addActivityLog("FollowUp","update",$_POST);
				if($oCustomer['status_id'] != $cus_status_id){
					$list_logs[$clsISO->getUniqid()] = array(
						'_type' => 'upd_status',
						'from_id' => $profile_id,
						'from_status_id' => $oCustomer['status_id'],
						'status_id' => $cus_status_id,
						'reg_date' => time()
					);
					$more_information['logs'] = $list_logs;
				}
				$clsCustomer->updateOne($customer_id, array(
					'upd_date' => time(),
					'status_id' => $cus_status_id,
					'list_purpose_id' => $list_purpose_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				));
			}
		}
	} else {
		$current_now = time();
		$notes_arrs = !empty($oCustomer['notes']) 
			? @json_decode(html_entity_decode($oCustomer['notes']), true) : array();
		$notes_arrs[$clsISO->getUniqid()] = array(
			'content' => $intro,
			'reg_date' => $current_now,
			'upd_date' => $current_now,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		);
		$oCustomer = $clsCustomer->getOne($customer_id);
		if($clsCustomer->updateOne($customer_id, array(
			'notes' => json_encode($notes_arrs, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			// Người tạo là người phụ trách bắn thông báo cho người liên quan
			if($admin_id == $profile_id && !empty($list_share_id)){
				$list_notify_users = $clsISO->getArrayByTextSlash($list_share_id);
				$titleNotify = sprintf('<strong>%s</strong> đã thêm ghi chú với khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($current_now, true));
				$dbconn->debug=true;
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNotify, time(), $list_notify_users);
			} else if($admin_id != $profile_id){
				// Người tạo là người liên quan thì bắn notify cho người phụ trách
				$titleNotify = sprintf('<strong>%s</strong> đã thêm ghi chú vào khách hàng <strong>%s</strong> với nội dung \'<strong>%s</strong>\' vào lúc <i>%s</i>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($customer_id), $intro, $clsISO->convertTimeToText($current_now, true));
				$clsNotify->insertNotify('Customer', $clsCustomer->pkey, $customer_id, $titleNotify, time(), '|'.$admin_id.'|');
			}	
		}
	}
	// Reuturn
	echo $msg; die();
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
	if($clsFollowUp->deleteOne($followup_id)){
		$msg = "_success";
		#activity log
		$clsActivityLog = new ActivityLog();	
		$log = $clsActivityLog->addActivityLog("FollowUp","delete",$oneFollowup);
	}
	// Return
	echo $msg; die();
}
function default_ajLoadLogsCRM(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsUser = new User();
	$clsLog = new Log();
	#
	$sortby = Input::post('sortby','reg_date');
	$sorttype = Input::post('sorttype','desc');
	$html = '<input type="hidden" class="sorthander_LogsCRM" value="'.implode('|',array($sortby,$sorttype)).'" />
	<table id="CrmCustomerLatestCopntact" class="table table-striped table-hover table-responsive" width="100%">
		<thead><tr>
			<th class="text-center" width="5%">No.</th>
			<th class="text-left" width="15%">'.$core->get_Lang('Date').'</th>
			<th class="text-left" width="15%">'.$core->get_Lang('Author').'</th>
			<th class="text-left">'.$core->get_Lang('Content').'</th>
			<th class="text-left" width="10%">'.$core->get_Lang('IP').'</th>
		</tr></thead>
		<tr>
			<td class="hidden-xs"></td>
			<td data-label="'.$core->get_Lang('Date').'"><input class="form-control InputSearchLog datepicker"  placeholder="dd/mm/yyyy" /></td>
			<td data-label="'.$core->get_Lang('Author').'"><input class="form-control InputSearchLog" placeholder="'.$core->get_Lang('EnterKeyword').'" /></td>
			<td data-label="'.$core->get_Lang('Content').'"><input class="form-control InputSearchLog" placeholder="'.$core->get_Lang('EnterKeyword').'" /></td>
			<td class="hidden-xs"></td>
		</tr>';
		// Pagination
		$currentPage = (int) Input::post('currentPage',1);
		$number_per_page = (int) Input::post('number_per_page',10);
		$cond = "user_id='{$adminid}' and type='CRM'";
		$totalRecord = $clsLog->countItem($cond);
		$totalPage = ceil($totalRecord/$number_per_page);
		$offset = ($currentPage-1) * $number_per_page;
		$limitCond = " limit {$offset},{$number_per_page}";
		$lstLogs = $clsLog->GetAll($cond." order by reg_date DESC".$limitCond);
		if(!empty($lstLogs)){ $ii=0; //Init
			foreach($lstLogs as $log){
				$html .= '<tr>
					<td data-label="No." class="text-center">'.($ii+1).'</td>
					<td data-label="'.$core->get_Lang('Date').'">'.$clsISO->getTimeAgo($log['reg_date'],true).'</td>
					<td data-label="'.$core->get_Lang('Author').'">'.$clsUser->getFullName($log['user_id']).'</td>
					<td data-label="'.$core->get_Lang('Content').'">'.$log['intro'].'</td>
					<td data-label="'.$core->get_Lang('IP').'">'.$log['ip'].'</td>
				</tr>';
				++$ii;
			}
		}else{
			$html .= '<tr>
				<td class="text-center" colspan="5">
					'.CRM::renderHTMLNoDocument('Not any foolow-Ups').'
				</div>
			</tr>';
		}
	$html .= '</tbody>
	</table>';
	if($totalPage > 0){
		$html .= '<div id="pp_LogsCRM" class="easyui-pagination" pageNumber="'.$currentPage.'"></div>';
	}
	// output
	echo @json_encode(array(
		'currentPage' => $currentPage,
		'number_per_page' => $number_per_page,
		'totalPage' => $totalPage,
		'totalRecord' => $totalRecord,
		'html'	=> $html
	)); die();
}
function default_report(){
	global $smarty,$assign_list,$profile_id,$core,$clsISO,$_LANG_ID,$oneProfile;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$cond = "`is_trash`=0";
	
	if($clsISO->checkPermissMs()){
		// Next
	} else if($clsISO->checkHeadSale($oneProfile['role_id'])){
		// Staff ins
	} else {
		$cond.= " and `admin_id`='{$profile_id}'";
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
	$tmp = $clsCustomer->getByCond($cond." and `reg_date`>0 order by `reg_date` asc limit 0,1","reg_date");
	$min_date = !empty($tmp) ? $tmp['reg_date'] : time();
	$start_year = date('Y',$min_date); unset($tmp);
	$end_year = date('Y',time());
	// Opts Year
	$htmlOptsYear = '';
	for($ii=$start_year; $ii<=$end_year; $ii++){
		$htmlOptsYear.= '<option value="'.$ii.'" '.($to==$ii?'selected':'').'>
			'.$core->get_Lang('Year').' '.$ii.'
		</option>';
	}
	$smarty->assign('htmlOptsYear', $htmlOptsYear);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo CRM | ' . PAGE_NAME;
	$assign_list['title_page'] = $title_page;
}
function default_get_select_staff(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$html = sprintf('<option value="0">%s</option>', 'Lựa chọn nhân viên');
	$department_id = (int) Input::post('department_id', 0);
	$field = "{$clsProfile->pkey},code,full_name,last_name,first_name";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
	and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$html.= sprintf('<option value="%s">%s</option>', $val[$clsProfile->pkey], $clsProfile->getIndentityV2($val[$clsProfile->pkey], $val));
		}
	}
	// return
	echo $html; die();
}
function default_load_cell_crm_report(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO;
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
	$field = in_array($cell, array(4,5)) ? "staff_id" : "admin_id";
	if($department_id > 0 && $staff_id == 0){
		$tmp = $clsProfile->getAll("`status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $clsProfile->pkey);
		if(!empty($tmp)){
			$list_staffs = array();
			foreach($tmp as $key => $val){
				$list_staffs[] = $val[$clsProfile->pkey];
			}
			$cond.=" and `{$field}` in (".implode(',', $list_staffs).")";
		}	
	} else if($staff_id > 0){
		$cond.= " and `{$field}`='{$staff_id}'";
	}
	$tmp = $clsISO->getRangeTime($time_type);
	$start_date = $tmp['start_date'];
	$due_date = $tmp['due_date'];
	// $clsISO->print_pre($tmp); die();
	if($cell==1){
		$field = "{$clsProperty->pkey},title";
		$list_status_reports = $clsProperty->getAllCache("`property_type`='CUSTOMER_STATUS' and {$clsProperty->pkey}<>'"._CRM_STATUS_DONTCARE_ID."' order by `order_no` asc", $field);
		if(!empty($list_status_reports)){
			$cond.= " and (`reg_date` between {$start_date} and {$due_date})";
			$total = $clsCustomer->countItem($cond);
			foreach($list_status_reports as $key => $val){
				$property_id = $val[$clsProperty->pkey];
				$total_customers = $clsCustomer->countItem($cond." and `status_id`='{$property_id}'");
				$list_status_reports[$key]['total_customers'] = $total_customers;
				$list_status_reports[$key]['percent'] = round($total_customers/$total*100,1);
			}
		}
		$assign_list['list_status_reports'] = $list_status_reports;
	} else if($cell==2) {
		$list_criterias = array(
			'NUM_CUS' => array(
				'title' => 'Số khách mới',
				'total' => 0
			), 'NUM_INTERACT' => array(
				'title' => 'Số tương tác',
				'total' => 0 
			), 'NUM_CALL' => array(
				'title' => 'Số cuộc gọi',
				'total' => 0
			), 'NUM_MEET' => array(
				'title' => 'Số cuộc gặp',
				'total' => 0
			), 'NUM_MEET' => array(
				'title' => 'Số tiếp khách',
				'total' => 0
			), 'NUM_SOLD' => array(
				'title' => 'Số căn chốt',
				'total' => 0
			)
		);
		foreach($list_criterias as $key => $val){
			$cond = "`is_trash`=0";
			if($key=='NUM_CUS'){
				$total_results = $clsCustomer->countItem("{$cond} and (`reg_date` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if($key=='NUM_INTERACT'){
				$total_results = $clsFollowUp->countItem("{$cond} and (`date_id` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if($key=='NUM_CALL'){
				$total_results = $clsFollowUp->countItem("{$cond} and `type_id`='"._FOLLOWUP_CALL_ID."' 
					and (`date_id` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if($key=='NUM_MEET'){
				$total_results = $clsFollowUp->countItem("{$cond} and `type_id`='"._FOLLOWUP_TASK_ID."' 
					and (`date_id` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			} else if($key=='NUM_SOLD') {
				$total_results = $clsBilling->countItem("{$cond} and `is_cancel`=0 and `is_alliance`=0 
					and (`deposit_date` between '{$start_date}' and '{$due_date}')");
				$total += $total_results;
			}
			$list_criterias[$key]['total'] = $total_results;
		}
		$assign_list['list_criterias'] = $list_criterias;
	} else if($cell==4){
		$field = "{$clsProperty->pkey},title";
		$list_products = $clsProperty->getAllCache("`property_type`='_BILLING_TYPE' order by `order_no` asc", $field); 
		if(!empty($list_products)){
			$cond.=" and (`deposit_date` between {$start_date} and {$due_date})";
			foreach($list_products as $key => $val){
				$property_id = $val[$clsProperty->pkey];
				$total_billings = $clsBilling->countItem($cond." and `is_cancel`=0 and `is_alliance`=0 and `billing_type`='{$property_id}'");
				$total += $total_billings;
				$list_products[$key]['total_billings'] = $total_billings;
				
			}
			$arr_total_billings = array_column($list_products, "total_billings");
			array_multisort($arr_total_billings, SORT_DESC, $list_products);
		}
		$assign_list['list_products'] = $list_products;
	} else if($cell==5){
		$field = "{$clsProperty->pkey},title";
		$list_products = $clsProperty->getAllCache("`property_type`='_BILLING_TYPE' order by `order_no` asc", $field); 
		if(!empty($list_products)){
			$cond.=" and (`deposit_date` between {$start_date} and {$due_date})";
			foreach($list_products as $key => $val){
				$property_id = $val[$clsProperty->pkey];
				$total_grands = $clsBilling->sumItem("totalgrand", $cond." and `is_cancel`=0 and `is_alliance`=0 and `billing_type`='{$property_id}'");
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
	)); die();
}
function default_loadDataChartCrmResource(){
	global $smarty,$assign_list,$adminid,$core,$clsISO;
	global $core, $oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	#
	$cond = "`is_trash`=0";
	$data = $dataPoints = array();
	if($clsISO->checkPermissMs()){
		// Next
	} else if($clsISO->checkHeadSale($oneProfile['role_id'])){
		// Staff ins
	} else {
		$cond.= " and `admin_id`='{$profile_id}'";
	}
	$field = "{$clsProperty->pkey},title";
	$lstCustomerType = $clsProperty->getAll("`is_trash`='0' and `property_type`='_CUSTOMER_RESOURCES' order by `order_no` ASC", $field);
	if(!empty($lstCustomerType)){
		foreach($lstCustomerType as $property){
			$resource_id = $property[$clsProperty->pkey];
			$total = $clsCustomer->countItem("{$cond} and `resource_id`='{$resource_id}'");
			$dataPoints[] = array(
				'label'	=> $property['title'],
				'y'	=> $total*1
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
	echo json_encode($barChartData); die();
}
function default_loadDataChartCrmStatus(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	#
	$data = $barChartData = $dataPoints  = array();
	$barChartData['animationEnabled'] = true;
	
	$cond = "`is_trash`=0";
	if($clsISO->checkPermissMs()){
		// Next
	} else if($clsISO->checkHeadSale($oneProfile['role_id'])){
		// Staff ins
	} else {
		$cond.= " and `admin_id`='{$profile_id}'";
	}
	$field = "{$clsProperty->pkey},title";
	$lstCustomerStatus = $clsProperty->GetAll("`is_trash`=0 and `parent_id`='0' and `property_type`='CUSTOMER_STATUS'", $field);
	if(!empty($lstCustomerStatus)){
		foreach($lstCustomerStatus as $property){
			$status_id = $property[$clsProperty->pkey];
			$total_customers = $clsCustomer->countItem("{$cond} and `status_id`='{$status_id}'");
			$dataPoints[] = array(
				'label'	=> $property['title'],
				'y'	=> $total_customers*1
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
function default_load_pop_upd_status(){
	global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$uid = Input::request('uid', 0);
	$customer_id = (int) Input::request('customer_id',0);
	$oneCustomer = $clsCustomer->getOne($customer_id, "status_id");
	$status_id = !empty($oneCustomer) ? $oneCustomer['status_id'] : 0;
	###
    $html = '<form method="post">
        <div class="form-group mb-2">
			<select class="form-control form-select required" name="status_id">
				'.$clsProperty->getSelectByProperty('CUSTOMER_STATUS', $status_id).'
			</select>
        </div>
        <div class="form-group">
			<input type="hidden" name="p_field" value="status_id" />
            <button type="button" class="btn btn-primary" onclick="$Core.crm.update_field(this,event);" p_field="status_id" p_id="'.$customer_id.'" uid="'.$uid.'">'.$core->makeIcon('check', 'Cập nhật').'</button>
        </div>
    </form>';
    // Return
    echo  $html; die();
}
function default_update_field(){
	global $smarty,$mod,$act,$profile_id,$core,$clsISO,$clsUser,$clsProperty;
	global $profile_id,$oneProfile;
	$clsTag = new Tag();
	$clsNotify = new Notify();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$uid = Input::post('uid');
	$p_id = Input::post('p_id');
	$p_field = Input::post('p_field');
	$p_value = Input::post($p_field);
	if($p_field=='tags'){
		if(!empty($p_value)){
			$parts = @explode(',', $p_value);
			if(!empty($parts)){
				$list_tags_id = array();
				foreach($parts as $tag){
					$tmp = $clsTag->getByCond("`user_id`='{$profile_id}' and `slug`='".$core->replaceSpace($tag)."'");
					if(!empty($tmp)){
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
				if($clsCustomer->updateOne($p_id, array(
					'list_tags_id' => $clsISO->makeSlashListFromArray($list_tags_id)
				))){
					$msg = "_success|||".$clsCustomer->getHTMLTags($p_id, [], '_activity');
				}
			}
		} else {
			if($clsCustomer->updateOne($p_id, array(
				'list_tags_id' => ""
			))){
				$msg = "_success|||";
			}
		}
	} else if($p_field == 'admin_id'){
		$oCustomer = $clsCustomer->getOne($p_id, "name,admin_id,list_share_id");
		$admin_id = $oCustomer['admin_id'];
		$list_share_id = $oCustomer['list_share_id'];
		if($admin_id > 0 && $admin_id != $p_value){
			if( $clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'use_globe' => 1,
				'list_share_id' => $list_share_id,
			))){
				$msg = "_success|||<a class=\"autoclick_".$p_id."\" customer_id=\"".$p_id."\" onclick=\"$Core.crm.view_activity(this, event);\"></a>";
				$titleNoty = sprintf('<strong>%s</strong> đã giao bạn phụ trách khách hàng <strong>%s</strong>', $clsProfile->getFullName($profile_id, $oneProfile), $clsCustomer->getName($p_id, $oCustomer));
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$p_id,$titleNoty,time(),"|".$p_value."|");
			}
		}
	} else {
		$oCustomer = $clsCustomer->getOne($p_id, "admin_id,status_id,more_information");
		$admin_id = $oCustomer['admin_id']; 
		$more_information = $oCustomer['more_information']; 
		$more_information = $clsISO->to_array_json($more_information);
		$list_logs = isset($more_information['logs']) && !empty($more_information['logs']) 
			? $more_information['logs'] : array();
		$list_logs[$clsISO->getUniqid()] = array(
			'_type' => 'upd_status',
			'from_id' => $profile_id,
			'from_status_id' => $oCustomer['status_id'],
			'status_id' => $p_value,
			'reg_date' => time()
		);
		$permiss_action = ($admin_id==$profile_id) ? 1 : 0;
		###
		$more_information['logs'] = $list_logs;
		//$clsCustomer->setDebug(true);
		if($clsCustomer->updateOne($p_id, array(
			'upd_date' => time(),
			$p_field => $p_value,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$oProperty = $clsProperty->getOne($p_value,"bgcolor,textcolor");
			$html = '<a class="mr-1"'.($permiss_action==1?' data-toggle="webui-popover"':'').' data-trigger="click" data-width="210px" data-type="async" id="'.$uid.'" data-closeable="false" data-url="'.$PCMS_URL.'/index.php?mod='.$mod.'&act=load_pop_upd_status&customer_id='.$p_id.'&uid='.$uid.'">'.$clsProperty->getLabel($p_value).'</a>';
			$msg = "_success|||".$html;
		}
	}
	// Reurn
	echo $msg; die();
}
function default_load_setting(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID;
	#
	$CFG = new Configuration();
	$clsEmailTemplate = new EmailTemplate();
	$lstEmailTemplate = $clsEmailTemplate->getAll("_group='CRM'");
	$htmlOptsEmailTemplate = '';
	if(!empty($lstEmailTemplate)){
		foreach($lstEmailTemplate as $template){
			$htmlOptsEmailTemplate .= '<option value="'.$template[$clsEmailTemplate->pkey].'">
				'.$template['name'].'
			</option>';
		}
		unset($lstEmailTemplate);
	}
	#
	$html = '<div class="mg-wrapper">
		<div class="card mb-2">
			<div class="card-header">
				<a class="back mr-2 goToPage" page="setting" title="'.$core->get_Lang('Back').'" href="javascript:void();"><img src="'.ICON_BACK.'" /></a>
				<span class="text-upper">'.$core->get_Lang('Settings').'</span>
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
						<label class="col-form-label col-12 col-md-3">'.$core->get_Lang('Reschedule Follow-up').'</label>
						<div class="col-12 col-md-9">
							<select class="form-control" name="CRM_FollowUpRescheduleTemplate">
								'.$htmlOptsEmailTemplate.'
							</select>
							<span class="help-block">Choose the email template that will be used to notify admins about a follow-up being rescheduled</span>
						</div>
					</div>
					<div class="form-group mb-2 form-row">
						<label class="col-form-label col-12 col-md-3">'.$core->get_Lang('Follow-up Type On Contact Creation').'</label>
						<div class="col-12 col-md-9">
							<select class="form-control" name="CRM_FollowUpTypeDefault"></select>
							<span class="help-block">Configure Type of Follow-up that will be created upon contact creation</span>
						</div>
					</div>
				</form>
			</div>
			<div class="card-footer border-top">
				<div class="d-flex justify-content-center">
					<button type="button" onClick="$Core.crm.save_setting(this, event)" class="btn btn-outline-primary"><span>'.$core->makeIcon('check', 'Cập nhật').'</span></button>
				</div>
			</div>
		</div>
		<div class="card mb-2">
			<h5 class="card-header">'.$core->makeIcon('gavel', 'Cài đặt Cronb').'</h5>
			<div class="card-body">
				<div class="note note-info">
					'.$core->get_Lang('Cron has to be set manually by an administrator. It will handle various functionalities such as sending emails at the specified time. It is recommended that cron run should be set at least once a day to review configured notifications in the system').'.
				</div>
				<table class="table table-bordered table-setting-cronjob" width="100%">
					<tr class="even">
						<td class="text-right">Tình trạng</td>
						<td>
							<label class="switch">
							  <input type="checkbox" name="crm_cronjob_status" class="js_crm-cronjob-status" value="1" '.($CFG->getValue('crm_cronjob_status')==1?'checked':'').' />
							  <span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr class="odd">
						<td width="15%" class="text-right">Path</td>
						<td width="85%"><input type="text" readonly="readonly" class="form-control" value="'.ABSPATH.'/cronjobs/CRM.php" /></td>
					</tr>
					<tr class="even">
						<td class="text-right">URL</td>
						<td><input type="text" class="form-control" readonly="readonly" value="'.PCMS_URL.'/cronjons/CRM.php" /></td>
					</tr>
					<tr class="even tr_cron-setting hidden">
						<td class="text-right">'.$core->get_Lang('Cron command').'</td>
						<td><input type="text" name="crm_cronjob_command" class="form-control" value="* * * * * wget -O /dev/null '.PCMS_URL.'/cronjobs/CRM.php >/dev/null 2>&1" /></td>
					</tr>
					<tr class="odd">
						<td class="text-right">Lần cuối</td>
						<td>2018-03-01 17:36:39 </td>
					</tr>
				</table>
			</div>
		</div>
		<div class="card">
			<h5 class="card-header">'.$core->get_Lang('Property Type').'</h5>
			<div class="card-body">';
				$list_property_array = array(
					'CUSTOMER_TYPE'=>$core->get_Lang('Customer Type'),
					'CUSTOMER_STATUS'=>$core->get_Lang('Customer Status'),
					'_SALE_STATUS'=>$core->get_Lang('Sale Status'),
					'_FOLLOWUP_TYPE'=>$core->get_Lang('Follow-Ups Types'),
					'_CUSTOMER_RESOURCES'=>$core->get_Lang('Customer resources'),
					'_SERVICES_TYPE'=>$core->get_Lang('Services type')
				);
				foreach($list_property_array as $property_type => $text){
					$html .= '<div class="bg-lighter p-3 rounded-3 mb-3">
						<div class="form-row">
							<div class="col-12 col-md-2 mb-2 mb-lg-0">
								<div class="d-flex d-md-block d-xl-block">
									<h3 class="col-form-label mr-2 mr-lg-0">'.$text.'</h3>
									<button class="btn btn-outline-default mt-half" onClick="open_property(this, event)" toId="crm" property_id="0" property_type="'.$property_type.'">'.$core->makeIcon('plus-circle',$core->get_Lang("Addnew")).'</button>
								</div>
							</div>
							<div class="col-12 col-md-10">
								<div class="holder_setting_property_'.$property_type.'">
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
	echo $html.'|||'.implode('|',array_keys($list_property_array)); die();
}
function default_save_setting(){
	global $oSmarty,$smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID ,$clsConfiguration;
	$action = Input::post('action','_general');
	
	$msg = '_error';
	if($action=='_general'){
		$clsConfiguration->updateValue('CRM_GoogleCalendarSysc',Input::post('CRM_GoogleCalendarSysc',0));
		$clsConfiguration->updateValue('CRM_GoogleCalendarName',Input::post('CRM_GoogleCalendarName',0));
		$clsConfiguration->updateValue('CRM_FollowUpRescheduleTemplate',Input::post('CRM_FollowUpRescheduleTemplate'));
		$clsConfiguration->updateValue('CRM_FollowUpTypeDefault',Input::post('CRM_FollowUpTypeDefault',0));
		$clsConfiguration->updateValue('notification_content_assign_admin',Input::post('notification_content_assign_admin'));
	} else if($action=='_cronjob'){
		$crm_cronjob_status = Input::post('crm_cronjob_status',0);
		$crm_cronjob_command = Input::post('crm_cronjob_command');
		$clsConfiguration->updateValue('crm_cronjob_status',$crm_cronjob_status);
		$clsConfiguration->updateValue('crm_cronjob_command',$crm_cronjob_command);
		#Update Cron
		$cron_file = ABSPATH."/inc/crontabs/crm.txt";
		$cmd = $crm_cronjob_command. " >/dev/null 2>&1";
		if($crm_cronjob_status==1){
			// Setup the cron jobs (Evry 5 min by default)
			@exec('crontab -r',$crontab);
			$output = shell_exec('crontab -l');
			file_put_contents($cron_file,$output.$cmd.PHP_EOL);
			exec("crontab $cron_file");
		}else{
			@exec('crontab -l',$crontab);
			//Find command
			if(is_array($crontab)){
				$key = array_search($cmd,$crontab);
				unset($crontab[$key]);
			}
			file_put_contents($cron_file,implode(PHP_EOL,$crontab));
			@exec("crontab $cron_file");
		}
	}
	echo '_success'.$a; die();
}
function default_load_propery(){
	global $smarty,$adminid,$core,$clsISO,$_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	
	$html = '<table class="table table-striped table_setting_property_'.$property_type.'" width="100%">
		<thead><tr>
			<th class="text-center" width="3%"></th>
			<th class="text-center" width="3%">No.</th>
			<th class="text-left" width="20%">'.$core->get_Lang('Name').'</th>
			<th class="text-left">'.$core->get_Lang('Description').'</th>
			<th class="text-left" width="15%">'.$core->get_Lang('Actions').'</th>
		</tr></thead>
		<tbody class="tbody_setting_property_'.$property_type.'">';
		$list_property = $clsProperty->getAll("property_type='{$property_type}' order by order_no ASC");
		// $clsISO->print_pre($list_property); die();
		if(!empty($list_property)){ $ii=0; // Init
			foreach($list_property as $property){
				$property_id = $property[$clsProperty->pkey];
				$props = 'property_id="'.$property_id.'" property_type="'.$property['property_type'].'"';
				$editAction = '<button type="button" class="btn btn-sm btn-default" onClick="open_property(this,event)" toId="crm" '.$props.'><i class="bx bx-pencil"></i></button>';
				$deleteAction = '<button type="button" class="btn btn-sm btn-danger" onClick="$Core.crm.delete_property(this,event)" '.$props.'><i class="bx bx-trash"></i></button>';
				// Status
				$html .= '<tr id="'.$property_id.'">
					<td class="text-center mySortableHandler">'.$core->makeIcon('arrows').'</td>
					<td class="text-center">'.($ii+1).'</td>
					<td class="text-left">'.$clsProperty->getTitle($property_id).'</td>
					<td class="text-left">'.$clsProperty->getIntro($property_id).'</td>
					<td class="text-center">
						<div class="btn-group btn-group-sm ui-btn-group-custom">
							'.$statusAction.$editAction.$deleteAction.'
						</div>
					</td>
				</tr>';
				++$ii;
			}
		}
		$html .= '</tbody>
	</table>';
	// Output
	echo $html; die();
}
function default_delete_property(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	$property_id = (int) Input::post('property_id',0);
	###
	$msg = "_error";
	if($clsProperty->deleteOne($property_id)){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_sync_order_property(){
	global $smarty,$assign_list,$adminid,$core,$clsISO,$_LANG_ID;
	$clsProperty = new Property();
	$property_type = Input::post('property_type');
	$list_ids = Input::post('list_ids', array());
	// $clsISO->print_pre($list_ids); die();
	$msg = "_error";
	if(!empty($list_ids)){ $ii=1;
		$msg = "_success";
		foreach($list_ids as $property_id){
			$clsProperty->updateOne($property_id, array(
				'order_no' => $ii
			));
			++$ii;
		}
	}
	// Return
	echo $msg; die();
}
function default_getSelectboxPropertyTypeCRM(){
	global $adminid,$core,$clsISO,$_company_iom_id; 
	$CFG = new Configuration();
	$holderG = Input::post('holderG');
	if($property_type=='_FOLLOWUP_TYPE'){
		$html = $clsISO->getSelectByPropertyTypeNotTitle($holderG,$CFG->getValue('CRM_FollowUpTypeDefault'));
	}
	echo '0$$$'.$html; die();
}
function default_ajManageCRMMassMail(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$html = '<div class="mg-wrapper">
		<div class="box light">
			<style type="text/css">.btnSearchPotential{ cursor:pointer;}</style>
			<div class="box-title">
				<div class="caption mr10">
					'.CRM::renderHTMLButtonBack(array('page'=>'massmail','action'=>'manage')).'
					<span class="uppercase">'.$core->get_Lang('MassMail').'</span>
				</div>
				<div class="input-group fl" style="max-width:250px">
					<span class="input-group-addon btnSearchMassMail"><i class="fa fa-search"></i></span>
					<input type="text" class="form-control txtSearchMassMail" placeholder="'.$core->get_Lang('Search').'" />
				</div>
				<div class="pull-right">
					'.($clsISO->checkPermission('create_new_massmail')?'
					<button class="iso-button ajOpenCRMMassMail" massmail_id="0">'.$core->makeIcon('plus-circle',$core->get_Lang("Addnew")).'</button>':'').'
				</div>
			</div>
			<div class="box-body" style="min-height:600px">
				<div id="holderCRMMassMail" class="holderCRMMassMail"></div>
			</div>
		</div>
	</div>';
	// Output
	echo($html);die();
}
function default_ajLoadListCRMMassMail(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$clsCRMMassMail = new CRMMassMail();
	$html = '<table class="table table-striped table-hover table-responsive" width="100%" cellpadding="2" cellspacing="2" border="0">
	<thead><tr>
		<th width="3%">No.</th>
		<th width="15%">'.$core->get_Lang('Date').'</th>
		<th>'.$core->get_Lang('Description').'</th>
		<th width="10%">'.$core->get_Lang('Type').'</th>
		<th width="10%">'.$core->get_Lang('Target').'</th>
		<th width="10%" class="text-center">'.$core->get_Lang('Total').'</th>
		<th width="10%" class="text-center">'.$core->get_Lang('AlreadySent').'</th>
		<th width="10%" class="text-center">'.$core->get_Lang('UnSent').'</th>
		<th width="10%" class="text-center">'.$core->get_Lang('Status').'</th>
		<th class="text-center" width="7%">'.$core->get_Lang('_Actions').'</th>
	</tr></thead>';
	
	$currentPage = (int) Input::post('currentPage',1);
	$number_per_page = (int) Input::post('number_per_page',20);
	$cond = "is_trash=0 and user_id='{$adminid}'";
	$totalRecord = $clsCRMMassMail->countItem($cond);
	$totalPage = ceil($totalRecord/$number_per_page);
	$offset = ($currentPage-1)*$number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	
	$keySearch = Input::post('keySearch');
	if(!empty($keySearch) && $keySearch != '0'){
		$cond .= " and (subject like '%".$keySearch."%' 
			or description like '%".$keySearch."%'
		)";
	}
	$lstMassMail = $clsCRMMassMail->GetAll("{$cond} order by reg_date DESC".$limitCond);
	if(!empty($lstMassMail)){ $ii=1;
		foreach($lstMassMail as $massmail){
			$massmail_id = $massmail[$clsCRMMassMail->pkey];
			$props = 'massmail_id="'.$massmail_id.'"';
			$html .= '<tr>
				<td data-label="No." class="text-center">'.$ii.'</td>
				<td data-label="'.$core->get_Lang('Date').'">'.$clsISO->convertTimeToText($massmail['date_id'],true).'</td>
				<td data-label="'.$core->get_Lang('Description').'">'.$massmail['description'].'</td>
				<td data-label="'.$core->get_Lang('Type').'">'.$clsCRMMassMail->getMailType($massmail['message_type']).'</td>
				<td data-label="'.$core->get_Lang('Target').'">'.$clsCRMMassMail->getTargetType($massmail['target_type']).'</td>
				<td data-label="'.$core->get_Lang('Total').'" class="text-center">'.$clsCRMMassMail->getTotalItem($massmail_id).'</td>
				<td data-label="'.$core->get_Lang('AlreadySent').'" class="text-center">'.$clsCRMMassMail->getTotalSend($massmail_id).'</td>
				<td data-label="'.$core->get_Lang('UnSent').'" class="text-center">'.$clsCRMMassMail->getTotalQueue($massmail_id).'</td>
				<td data-label="'.$core->get_Lang('Status').'" class="text-center">'.$clsCRMMassMail->getStatus($massmail_id,$massmail).'</td>
				<td data-label="'.$core->get_Lang('_Actions').'" class="text-center">
					<div class="dropdown dropdown-action">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
						</a>
						<ul class="dropdown-menu icon">
							'.($clsISO->checkPermission('view_massmail')?'<li><a class="ajViewCRMMassMail" '.$props.'>'.$core->makeIcon('eye').' '.$core->get_Lang('View').'</a></li>':'').'
							'.($clsISO->checkPermission('edit_massmail')?'<li><a class="ajOpenCRMMassMail" '.$props.'>'.$core->makeIcon('pencil').' '.$core->get_Lang('Edit').'</a></li>':'').'
							'.($clsISO->checkPermission('delete_massmail')?'<li><a class="ajDeleteCRMMassMail" '.$props.'>'.$core->makeIcon('trash').' '.$core->get_Lang('Delete').'</a></li>':'').'
						</ul>
					</div>
				</td>
			</tr>';
			++$ii;
		}
	}else{
		$html .= '<tr>
			<td class="text-center" colspan="10">
				'.CRM::renderHTMLNoDocument('Not any records').'
			</td>
		</tr>';
	}
	$html .= '</table>';
	if($totalPage > 0){
		$html .= '<div class="easyui-pagination" id="PageCRMMassMail" pageNumber="'.$currentPage.'" pageList="[10,20,30,50]"></div>';
	}
	// output
	echo json_encode(array(
		'html'	=> $html,
		'currentPage'	=> $currentPage,
		'number_per_page'	=> $number_per_page,
		'totalRecord'	=> $totalRecord,
		'totalPage'	=> $totalPage
	)); die();
}
function default_ajOpenCRMMassMail(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$user_id = $adminid;
	$clsProperty = new Property();
	$clsCRMMailbox = new CRMMailbox();
	$clsCRMMassMail = new CRMMassMail();
	$massmail_id = (int) Input::post('massmail_id',0);
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
	if($massmail_id > 0){
		$oneMassMail = $clsCRMMassMail->GetOne($massmail_id);
		$target_type = $oneMassMail['target_type'];
		if($target_type=='customergroup'){
			$arrCustomerGroup = @json_decode($oneMassMail['target_id'],true);
		} else if($target_type=='campaign'){
			$arrCampaign = @json_decode($oneMassMail['target_id'],true);
		}
	}
	// Option mailbox
	$htmlMailbox = sprintf('<option value="0">%s</option>',$core->get_Lang('Select mailbox'));
	$lstMailbox = $clsCRMMailbox->GetAll("user_id='{$adminid}'");
	if(!empty($lstMailbox)){
		foreach($lstMailbox as $mailbox){
			$htmlMailbox .= '<option>'.$mailbox['name'].'</option>';
		}
		unset($lstMailbox);
	}
	$html = '<div class="mg-wrapper">
		<form method="post" action="" enctype="multipart/form-data" id="frmMassMail_'.$massmail_id.'">
			<div class="box light">
				<div class="box-title">
					'.CRM::renderHTMLButtonBack(array('page'=>'massmail','action'=>'edit')).'
					<div class="caption">
						<span class="uppercase">'.$core->get_Lang('NewMassMessage').'</span>
					</div>
				</div>
				<div class="box-body form-horizontal">
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>'.$core->get_Lang('SendTo').'</h5></div>
							<div class="col-md-9">
								<select class="form-control" name="target_type">
									'.CRM::getFORMSelectOptionsAdvanced(($massmail_id>0?$oneMassMail['target_type']:null),$arrTargetType).'
								</select>
								<small class="help-block">'.$core->get_Lang('Message will be sent to all active clients in the system').'</small>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>'.$core->get_Lang('MessageType').'</h5></div>
							<div class="col-md-9">
								<select class="form-control" name="message_type">
									'.CRM::getFORMSelectOptionsAdvanced(($massmail_id>0?$oneMassMail['message_type']:null),$arrMsgType).'
								</select>
								<small class="help-block">'.$core->get_Lang('This message will be sent in form of an email').'</small>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right">
								<h5>'.$core->get_Lang('Subject').' <span class="required">*</span></h5>
							</div>
							<div class="col-md-9">
								<input type="text" class="form-control" name="subject" value="'.$oneMassMail['subject'].'" />
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>'.$core->get_Lang('Date').'</h5></div>
							<div class="col-md-9">
								<input type="text" class="form-control datepicker pull-left mr-half" readonly="readonly" value="'.$clsISO->convertTimeToText(($massmail_id>0?$oneMassMail['date_id']:time())).'" name="date_id" />
								<input type="text" class="form-control timepicker" name="time" value="'.($massmail_id>0?$oneMassMail['time']:date('H:i S')).'" />
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2 col-md-custom-2 text-right">
							<h5>'.$core->get_Lang('Description').' <span class="required">*</span></h5>
						</div>
						<div class="col-md-10 col-md-custom-10">
							<input type="text" class="form-control" name="description" value="'.$oneMassMail['description'].'" />
						</div>
					</div>
					<div class="form-group form-group-general form-group-customergroup" '.($target_type=='customergroup'?'':'style="display:none"').'>
						<div class="col-md-2 col-md-custom-2 text-right">
							<h5>'.$core->get_Lang('CustomerGroup').' <span class="required">*</span></h5>
						</div>
						<div class="col-md-10 col-md-custom-10">
							<select class="form-control" multiple="multiple" name="customergroups[]" style="height:60px">';
							$lstCustomerGroup = $clsProperty->GetAll("is_trash=0 and parent_id='0' and property_type='LOAIKHACHHANG' 
							order by order_no ASC",$clsProperty->pkey);
							if(!empty($lstCustomerGroup)){
								foreach($lstCustomerGroup as $property){
									$pop_id = $property[$clsProperty->pkey];
									$sltc = in_array($pop_id,$arrCustomerGroup)?'selected="selected"':'';
									$html .= '<option value="'.$pop_id.'" '.($sltc).'>'.$clsProperty->getTitle($pop_id).'</option>';
								}
								unset($lstCustomerGroup);
							}
						$html .= '
							</select>
						</div>
					</div>
					<div class="form-group form-group-general form-group-campaign" '.($target_type=='campaign'?'':'style="display:none"').'>
						<div class="col-md-2 col-md-custom-2 text-right">
							<h5>'.$core->get_Lang('Campaigns').' <span class="required">*</span></h5>
						</div>
						<div class="col-md-10 col-md-custom-10">
							<select class="form-control" name="campaigns[]" multiple="multiple" style="height:60px">';
								$clsBusinessCampaign = new BusinessCampaign();
								$lstCampain = $clsBusinessCampaign->GetAll("admin_list like '%|{$adminid}|%' and type='CRM' order by reg_date DESC");
								if(!empty($lstCampain)){
									foreach($lstCampain as $campain){
										$business_campaign_id = $campain[$clsBusinessCampaign->pkey];
										$sltc = in_array($business_campaign_id,$arrCampaign)?'selected="selected"':'';
										$html .= '<option value="'.$business_campaign_id.'" '.$sltc.'>'.$campain['title'].'</option>';
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
								<h5>'.$core->get_Lang('Email from').'</h5>
							</div>
							<div class="col-md-5" style="margin-top:5px">
								<label class="mr-half"><input type="radio" style="vertical-align:-2px" name="mail_from" value="system" '.($oneMassMail['mail_from']=='system'?'checked':'').' /> '.$core->get_Lang('System').'</label>
								<label><input type="radio" style="vertical-align:-2px" name="mail_from" value="personal" '.($oneMassMail['mail_from']=='personal'?'checked':'').' /> '.$core->get_Lang('Personal').'</label>
							</div>
							<div class="col-md-4 mailbox '.($oneMassMail['mail_from']=='system'?' hidden':'').'">
								<select name="mailbox_id" class="form-control">
									'.$htmlMailbox.'
								</select>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<div class="col-md-3 text-right"><h5>'.$core->get_Lang('TimeDelay').'</h5></div>
							<div class="col-md-3">
								<select class="form-control" name="time_delay">
									'.$clsISO->getSelect(10,60,$oneMassMail['time_delay']).'
								</select>
							</div>
							<div class="col-md-3">
								<select name="time_unit" class="form-control">
									<option value="hour" '.($oneMassMail['time_unit']=='hour'?'selected':'').'>'.$core->get_Lang('Hours').'</option>
									<option value="minute" '.($oneMassMail['time_unit']=='minute'?'selected':'').'>'.$core->get_Lang('Minutes').'</option>
									<option value="second" '.($oneMassMail['time_unit']=='second'?'selected':'').'>'.$core->get_Lang('Seconds').'</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7">
							<div class="form-group no-margin">
								<h5>'.$core->get_Lang('Content').'<span class="required">*</span></h5>
								<textarea name="content" class="form-control" cols="255" rows="20" id="mceFull$core->get_Lang'.$massmail_id.'">'.$oneMassMail['content'].'</textarea>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group no-margin">
								<h5>'.$core->get_Lang('Available Merge Fields').'</h5>
								<style type="text/css">
									.tmslist{ display:block; margin:0; padding:0}
									.tmslist > li{ display:inline-block; width:100%; padding:5px 0px; cursor:pointer;}
									.tmslist > li .pleft{ width:38%; float:left; padding:0% 1%;}
									.tmslist > li .pright{ width:58%; float:right; padding:0% 1%;}
								</style>
								<div class="box light bordered">
									<div class="box-title">
										<span class="caption uppercase">'.$core->get_Lang('AssignedClient').'</span>
										<a href="javascript:void(0);" class="btn-outline pull-right hidebox"><i class="fa fa-compress"></i></a>
									</div>
									<div class="box-body">
										<ul class="tmslist holderCRMMassMailClientVariable$core->get_Lang'.$massmail_id.'">';
										$lstClientVariable = $clsCRMMassMail->getListClientVariable();
										if($massmail_id>0 && $oneMassMail['target_type']=='campaign'){
											$lstClientVariable = $clsCRMMassMail->getListContactVariable();
										}
										foreach($lstClientVariable as $kp => $vp){
											$html .= '<li class="cmd" cmd="'.$kp.'">
												<div class="pleft">'.$kp.'</div>
												<div class="pright">'.$vp.'</div>
											</li>';
										}
										$html .= '</ul>
									</div>
								</div>
								<div class="box light hide bordered">
									<div class="box-title">
										<span class="caption uppercase">'.$core->get_Lang('SystemVariable').'</span>
										<a href="javascript:void(0);" class="btn-outline pull-right hidebox"><i class="fa fa-compress"></i></a>
									</div>
									<div class="box-body">
									<ul class="tmslist">';
										$lstSystemVariable = $clsCRMMassMail->getListSystemVariable();
										foreach($lstSystemVariable as $kp => $vp){
											$html .= '<li class="cmd" cmd="'.$kp.'">
												<div class="pleft">'.$kp.'</div>
												<div class="pright">'.$vp.'</div>
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
					<button class="iso-button saveCRMMassMail" massmail_id="'.$massmail_id.'">
						'.$core->get_Lang($massmail_id>0?'Update':'Add').'
					</button>
				</div>
			</div>
		</form>
	</div>';
	// output
	echo $html; die();
}
function default_ajSaveCRMMassMail(){
	global $adminid,$core,$dbconn,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent(); 
	$clsPotential = new Potential();
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	$massmail_id = (int) Input::post('massmail_id',0);
	// Delete
	if(Input::exists('action','GET') && Input::get('action')=='delete'){
		$subject = $clsCRMMassMail->GetOneField("subject",$massmail_id);
		if($clsCRMMassMail->deleteOne($massmail_id)){
			// Delete mass mail item sent logs
			$clsCRMMassMailSent = new CRMMassMailSent();
			$clsCRMMassMailSent->deleteByCond("massmail_id='{$massmail_id}'");
			// Logs
			$log_message = 'Mass mail has been deleted #'.$massmail_id.' '.$subject;
			$clsISO->logs($clsCRMMassMail->tbl,$clsCRMMassMail->pkey,$massmail_id,$log_message,'CRM');
		}
		echo($massmail_id); die();
	}
	// List ID
	$target_id = array();
	$target_type = Input::post('target_type');
	if($target_type=='customergroup'){
		$target_id = Input::post('customergroups');
	} else if($target_type=='campaign'){
		$target_id = Input::post('campaigns');
	}
	// Date ID
	$date_id = Input::post('date_id');
	$time = Input::post('time');
	$datetime = $clsISO->convertTextToTime($date_id,$time);
	//Time delay
	$time_unit = Input::post('time_unit','second');
	$time_delay = (int) Input::post('time_delay',10);
	#---
	if($massmail_id > 0){
		$changed = false;
		if($clsCRMMassMail->getOneField('date_id',$massmail_id) != $datetime){
			$changed = true;
		}
		if($changed==false && $target_type != $clsCRMMassMail->getOneField('target_type',$massmail_id)){
			$changed = true;	
		}else{
			if($target_type=='campaign' && $changed==false){
				$target_old_id = $clsCRMMassMail->getOneField('target_id',$massmail_id);
				$target_old_id = !empty($target_old_id) ? json_decode($target_old_id,true) : array();
				if(!CRM::array_equal($target_old_id,$target_id)){
					$changed = true;
				}
			}
		}
		// Valid change time_delay
		if($changed==false){
			if($time_delay != $clsCRMMassMail->getOneField('time_delay',$massmail_id)){
				$changed = true;
			}
		}
		if($changed==false){
			if($time_unit != $clsCRMMassMail->getOneField('time_unit',$massmail_id)){
				$changed = true;
			}
		}
		#
		if($clsCRMMassMail->updateOne($massmail_id,array(
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
		))){
			if($changed){
				$clsCRMMassMailSent->deleteByCond("massmail_id='{$massmail_id}'");
				if($time_unit=='minute'){
					$time_delay = $time_delay * 60;
				}else if($time_unit=='hour'){
					$time_delay = $time_delay * 60 * 60;
				}
				if($target_type=='campaign'){
					$lstCampaign = $target_id;
					if(!empty($lstCampaign)){ 
						$arrPotential = array();
						foreach($lstCampaign as $campaign_id){
							$lstCampaignPotential = $dbconn->GetAll("select t1.potential_id,t1.email from ".DB_PREFIX."potential as t1 
							inner join ".$clsBusinessCampaignPotential->tbl." as t2 on t1.potential_id=t2.potential_id 
							where t1.is_trash=0 and t2.business_campaign_id='{$campaign_id}' order by t1.reg_date ASC");
							if(!empty($lstCampaignPotential)){ $i=0;
								foreach($lstCampaignPotential as $potential){
									$potential_id = $potential[$clsPotential->pkey];
									$to_email = $potential['email'];
									if($clsISO->is_valid_email($to_email)){
										$arrPotential[] = array(
											'potential_id'	=> $potential_id,
											'to_email'	=> $to_email
										);
									}
								}
								unset($lstCampaignPotential);
							}
						}
						if(!empty($arrPotential)){ $ii=0;
							foreach($arrPotential as $potential){
								$tp = 'potential';
								$clsCRMMassMailSent->insert(array(
									'id'	=> $clsCRMMassMailSent->getMaxId(),
									'tp'	=> $tp,
									'massmail_id'	=> $massmail_id,
									'user_id'	=> $adminid,
									'company_id'=> $potential['potential_id'],
									'to_email'	=> $potential['to_email'],
									'date_id'	=> $datetime+($time_delay*$ii),
									'reg_date'	=> time()
								));
								++$ii;
							}				  
						}
						unset($arrPotential);
					}
				}else{
					if ($target_type=='customergroup'){
						$lstCustomerGroup = $target_id;
						$lstCompany = $clsCompany->GetAll("is_trash=0 and customer_type in (".implode(',',$lstCustomerGroup).")");
					}else{
						$lstCompany = $clsCompany->GetAll("is_trash=0");
					}
					if(!empty($lstCompany)){
						$arrCompany = array();
						foreach($lstCompany as $company){
							$company_id = $company[$clsCompany->pkey];
							$to_email = $company['email'];
							if($clsISO->is_valid_email($email)){
								$arrCompany[] = array(
									'company_id'	=> $company_id,
									'to_email'	=> $to_email
								);
							}
						}
						if(!empty($arrCompany)){ $ii=0;
							foreach($arrCompany as $company){
								$tp = 'company';
								$clsCRMMassMailSent->insert(array(
									'id'	=> $clsCRMMassMailSent->getMaxId(),
									'tp'	=> $tp,
									'massmail_id'	=> $massmail_id,
									'user_id'	=> $adminid,
									'company_id'	=> $company['company_id'],
									'to_email'	=> $company['to_email'],
									'date_id'	=> $datetime+($time_delay*$ii),
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
	}else{
		$massmail_id = $clsCRMMassMail->getMaxId();
		if($clsCRMMassMail->insert(array(
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
		))){
			if($time_unit=='minute'){
				$time_delay = $time_delay * 60;
			}else if($time_unit=='hour'){
				$time_delay = $time_delay * 60 * 60;
			}
			if($target_type=='campaign'){
				$lstCampaign = $target_id;
				if(!empty($lstCampaign)){
					$arrPotential = array();
					foreach($lstCampaign as $campaign_id){
						$lstCampaignPotential = $dbconn->GetAll("select t1.potential_id,t1.email from ".DB_PREFIX."potential as t1 
						inner join ".$clsBusinessCampaignPotential->tbl." as t2 on t1.potential_id=t2.potential_id 
						where t1.is_trash=0 and t2.business_campaign_id='{$campaign_id}' order by t1.reg_date ASC");
						if(!empty($lstCampaignPotential)){ $i=0;
							foreach($lstCampaignPotential as $potential){
								$potential_id = $potential[$clsPotential->pkey];
								$to_email = $potential['email'];
								if($clsISO->is_valid_email($to_email)){
									$arrPotential[] = array(
										'potential_id'	=> $potential_id,
										'to_email'	=> $to_email
									);
								}
							}
							unset($lstCampaignPotential);
						}
					}
					if(!empty($arrPotential)){ $ii=0;
						foreach($arrPotential as $potential){
							$tp = 'potential';
							$clsCRMMassMailSent->insert(array(
								'id'	=> $clsCRMMassMailSent->getMaxId(),
								'tp'	=> $tp,
								'massmail_id'	=> $massmail_id,
								'user_id'	=> $adminid,
								'company_id'	=> $potential['potential_id'],
								'to_email'	=> $potential['to_email'],
								'date_id'	=> $datetime+($time_delay*$ii),
								'reg_date'	=> time()
							));
							++$ii;
						}				  
					}
					unset($arrPotential);
				}
			}else{
				if ($target_type=='customergroup'){
					$lstCustomerGroup = $target_id;
					$lstCompany = $clsCompany->GetAll("is_trash=0 and customer_type in (".implode(',',$lstCustomerGroup).")");
				}else{
					$lstCompany = $clsCompany->GetAll("is_trash=0");
				}
				if(!empty($lstCompany)){
					$arrCompany = array();
					foreach($lstCompany as $company){
						$company_id = $company[$clsCompany->pkey];
						$to_email = $company['email'];
						if($clsISO->is_valid_email($email)){
							$arrCompany[] = array(
								'company_id'	=> $company_id,
								'to_email'	=> $to_email
							);
						}
					}
					if(!empty($arrCompany)){ $ii=0;
						foreach($arrCompany as $company){
							$tp = 'company';
							$clsCRMMassMailSent->insert(array(
								'id'	=> $clsCRMMassMailSent->getMaxId(),
								'tp'	=> $tp,
								'massmail_id'	=> $massmail_id,
								'user_id'	=> $adminid,
								'company_id'	=> $company['company_id'],
								'to_email'	=> $company['to_email'],
								'date_id'	=> $datetime+($time_delay*$ii),
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
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}	
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent();
	$massmail_id = (int) Input::post('massmail_id',0);
	if(!$massmail_id){
		echo '_invalid';
		die();
	}
	$totalItem = $clsCRMMassMail->getTotalItem($massmail_id); // Total
	$totalSent = $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}' and mark_sent=1"); // Sent
	$totalRead = $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}' and mark_read=1"); // Read
	#
	$html = '<div class="modal right fade" id="OpenMassMail_'.$massmail_id.'" tabindex="-1" role="dialog" aria-labelledby="OpenMassMail_'.$massmail_id.'">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<button type="button" class="close closeEv" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="modal-header">
					<div class="links-bar-create-edit">
						<div class="profile-photo-create-edit">
							<img src="'._ICON_GENERAL.'" width="48px" />
						</div>
					</div>
					<div class="head">
						<div class="subtitle">'.$core->get_Lang('Massmail').'</div>
						<div class="title">'.$clsCRMMassMail->getOneField('subject',$massmail_id).'</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="main-container">
						<div class="activity-stats" data-total-count="2">
							<div class="block-item">
								<div class="top-label">'.$core->get_Lang('Total').'</div>
								<div class="bold-value">'.$totalItem.'</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width: 0%;"></div>
								</div>
							</div>
							<div class="block-item" title="1 Emails">
								<span class="top-label">'.$core->get_Lang('AlreadySent').'</span>
								<div class="block-item-count">'.$totalSent.'</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:'.(($totalSent/$totalItem)*100).'%;"></div>
								</div>
							</div>
							<div class="block-item" title="0 Tasks">
								<span class="top-label">'.$core->get_Lang('UnSent').'</span>
								<div class="block-item-count">'.($totalItem-$totalSent).'</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:'.((($totalItem-$totalSent)/$totalItem)*100).'%;"></div>
								</div>
							</div>
							<div class="block-item" title="0 Events">
								<span class="top-label">'.$core->get_Lang('AlreadyRead').'</span>
								<div class="block-item-count">'.$totalRead.'</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:'.(($totalRead/$totalItem)*100).'%;"></div>
								</div>
							</div>
							<div class="block-item">
								<div class="top-label">'.$core->get_Lang('UnRead').'</div>
								<div class="bold-value">'.($totalItem-$totalRead).'</div>
								<div class="fp-product-count-holder">
									<div class="fp-product-count-total"></div>
									<div class="fp-product-count-percent" style="width:'.((($totalItem-$totalRead)/$totalItem)*100).'%;"></div>
								</div>
							</div>
						</div>
						<div class="panel panel-bordered related-list panel-upcoming-activities">
							<div class="panel-heading">
								<h3 class="panel-title bold">'.$core->get_Lang('List sent').'</div>
							</div>
							<div class="panel-content">
								<div class="holderMassMailSentItem_'.$massmail_id.'">
									<div class="loading-msg">
										'.$core->get_Lang('Loading').'..
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
	echo $html; die();
}
function default_load_list_massmail_sent(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}	
	$clsVS_Client = new VS_Client();
	$clsPotential = new Potential();
	$clsCRMMassMail = new CRMMassMail();
	$clsCRMMassMailSent = new CRMMassMailSent();
	
	$massmail_id = (int) Input::post('massmail_id',0);
	$html = '<table border="0" class="table table-striped table-responsive">
		<thead><tr>
			<th width="5%">No.</th>
			<th>'.$core->get_Lang('Name').' E-mail</th>
			<th>'.$core->get_Lang('To').' E-mail</th>
			<th>'.$core->get_Lang('Date').'</th>
			<th>'.$core->get_Lang('Status').'</th>
			<th>'.$core->get_Lang('TimeSent').'</th>
			<th>'.$core->get_Lang('Read').'</th>
			<th>'.$core->get_Lang('TimeRead').'</th>
		</tr></thead>';
		$lstMassMailSent = $clsCRMMassMailSent->GetAll("massmail_id='{$massmail_id}' order by date_id asc");
		if(!empty($lstMassMailSent)){ $ii = 1;// Init
			foreach($lstMassMailSent as $item){
				if($item['tp']=='potential'){
					$company = $clsPotential->getName($item['company_id']);
				}else{
					$company = $clsVS_Client->getIdentity($item['company_id']);
				}
				$html .= '<tr>
					<th data-label="No." width="5%">'.$ii.'</th>
					<td data-label="'.$core->get_Lang('Name').'">'.$company.'</td>
					<td data-label="'.$core->get_Lang('To').'">'.$item['to_email'].'</td>
					<td data-label="'.$core->get_Lang('Date').'">'.$clsISO->convertTimeToTextOrigin($item['date_id'],true).'</td>
					<td data-label="'.$core->get_Lang('Status').'">'.($item['mark_sent']?$core->get_Lang('AlreadySent'):$core->get_Lang('Unsent')).'</td>
					<td data-label="'.$core->get_Lang('TimeSent').'">'.(!empty($item['time_sent'])?$clsISO->convertTimeToText($item['time_sent'],true):'').'</td>
					<td data-label="'.$core->get_Lang('Read').'">'.($item['mark_read']?$core->get_Lang('AlreadyRead'):$core->get_Lang('UnRead')).'</td>
					<td data-label="'.$core->get_Lang('TimeRead').'">'.(!empty($item['time_sent'])?$clsISO->convertTimeToText($item['time_read'],true):'').'</td>
				</tr>';
				++$ii;
			}
		}else{
			$html .= '<tr>
				<td class="text-center" colspan="8">
					'.CRM::renderHTMLNoDocument('Not any foolow-Ups').'
				</div>
			</tr>';
		}
	
	$html .= '</table>';
	// Output
	echo $html; die();
}
function default_ajLoadCRMCampainVariable(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}	
	$clsCRMMassMail = new CRMMassMail();
	$target_type = Input::post('target_type','customer');
	
	$html = '';
	if($target_type=='customer' || $target_type=='customergroup'){
		$lstClientVariable = $clsCRMMassMail->getListClientVariable();
	}else{
		$lstClientVariable = $clsCRMMassMail->getListContactVariable();
	}
	foreach($lstClientVariable as $kp => $vp){
		$html .= '<li class="cmd" cmd="'.$kp.'">
			<div class="pleft">'.$kp.'</div>
			<div class="pright">'.$vp.'</div>
		</li>';
	}
	echo $html; die();
}
function default_ajConfigCRMCronjob(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}	
	$clsCronjobs = new Cronjobs();
	$status = Input::post('status',0);
	
	$msg = '_error';
	$cron_file = 'cronjobs/CRM.php';
	$cronjobs_id = $clsCronjobs->getId($cron_file);
	if($clsCronjobs->updateOne($cronjobs_id,array(
		'is_active'	=> $status
	))){
		$msg = '_success';
		$clsCronjobs->run();
	}
	echo($msg); die();
}
function default_ajSaveCRMCronjob(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}	
	$clsCronjobs = new Cronjobs();
	$cron_file = 'cronjobs/CRM.php';
	$cronjobs_id = Input::post('cronjobs_id',0);
	if(intval($cronjobs_id)==0){
		$cronjobs_id = $clsCronjobs->getId($cron_file);
	}
	$msg = '_error';
	if($clsCronjobs->updateOne($cronjobs_id,array(
		'minute'		=> Input::post('minute','59'),
		'hour'			=> Input::post('hour','23'),
		'month'			=> Input::post('month','*'),
		'dayofmonth'	=> Input::post('dayofmonth','*'),
		'dayofweek'		=> Input::post('dayofweek','*'),
		'user_id_update'=> $adminid,
		'upd_date'	=> time()
	))){
		$msg = '_success';
		$clsCronjobs->run();
	}
	echo($msg); die();
}
function default_ajChangeTableValue(){
	global $core,$_frontIsLoggedin_user_id,$clsISO;
	#
	$html = '';
	$tbl = Input::post('tbl','');
	$pval = Input::post('pval',0);
	$ipn = Input::post('ipn','text');
	$field_id = Input::post('field_id','');
	if($ipn=='number'){
		$value = $clsISO->processSmartNumber($_POST['value']);
	}
	else if($ipn=='date'){
		$value = strtotime($_POST['value']);
	}
	else{
		$value = $_POST['value'];
	}
	$clsClassTable = new $tbl;
	$clsClassTable->updateOne($pval,$field_id."='".addslashes($value)."'");
	#
	echo($value); die();
}
function default_open_contact(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	##
	$contact_id = (int) Input::post('contact_id',0);
	$customer_id = (int) Input::post('customer_id',0);
	##
	$action = '_add';
	$oneContact = array();
	if($contact_id > 0){
		$action = '_edit';
		$oneContact = $clsContact->getOne($contact_id);
	}
	$smarty->assign('action',$action);
	$smarty->assign('contact_id',$contact_id);
	$smarty->assign('customer_id',$customer_id);
	$smarty->assign('oneContact',$oneContact);
	// Return
	$smarty->assign('template_type','_form');
	$html = $core->build('_ajax.contact.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function default_save_contact(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	//$clsISO->print_pre($clsCustomer); die();
	$contact_id = (int) Input::post('contact_id',0);
	$customer_id = (int) Input::post('customer_id',0);
	###
	$name = Input::post('name');
	$address = Input::post('address');
	$phone = Input::post('phone');
	$email = Input::post('email');
	$notes = Input::post('notes');
	$role_id = (int) Input::post('role_id',0);
	###
	$msg = "_error";
	if($contact_id > 0){
		//$clsCustomer->setDebug(true);
		if($clsContact->updateOne($contact_id,array(
			'name' => $name,
			'name_slug' => $core->replaceSpace($name),
			'address' => $address,
			'phone' => $phone,
			'email' => $email,
			'user_id_update' => $profile_id,
			'upd_date' => time()
		))){
			$msg = "_success";
		}
	} else {
		//$clsCustomer->setDebug(true);
		$contact_id = $clsContact->getMaxId();
		if($clsContact->insert(array(
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
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'contact_id' => $contact_id
	)); die();
}
function default_delete_contact(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	##
	$customer_id = (int) Input::post('customer_id',0);
	$contact_id = (int) Input::post('contact_id',0);
	##
	$msg = "_error";
	if($clsContact->deleteOne($contact_id)){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_load_contact(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	##
	$customer_id = (int) Input::post('customer_id',0);
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',15);
	##
	$cond = "is_trash=0 and customer_id='{$customer_id}' order by reg_date DESC";
	$total_record = $clsContact->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	##
	$list_contacts = $clsContact->getAll($cond.$limitCond);
	// $clsISO->print_pre($list_contacts); die();
	$smarty->assign('list_contacts',$list_contacts);
	$smarty->assign('customer_id',$customer_id);
	// Return
	$smarty->assign('template_type','_list');
	$html = $core->build('_ajax.contact.tpl');
	echo json_encode(array(
		'html' => $html,
		'current_page' => $current_page,
		'per_page' => $per_page,
		'total_record' => $total_record,
		'total_page' => $total_page
	)); die();
}
function default_open_followups(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$clsISO;
	$clsContact = new Contact();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	##
	$followup_id = (int) Input::post('followup_id',0);
	$customer_id = (int) Input::post('customer_id',0);
	
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
	if($followup_id > 0){
		$action = '_edit';
		$oneFollowUp = $clsFollowUp->getOne($followup_id);
		$arr_selected_users = $clsISO->getArrayByTextSlash($oneFollowUp['list_user_slash']);
	}
	$smarty->assign('action',$action);
	$smarty->assign('followup_id',$followup_id);
	$smarty->assign('customer_id',$customer_id);
	$smarty->assign('oneFollowUp',$oneFollowUp);
	$smarty->assign('arr_selected_users',$arr_selected_users);
	// Return
	$smarty->assign('holderG','_form');
	$html = $core->build('_ajax.followups.tpl');
	echo json_encode(array(
		'uid' => $clsISO->getUniqid(),
		'html' => $html
	)); die();
}
function getTimeUnit($reminder_before, $reminder_unit){
    global $core, $dbconn;
    if($reminder_unit== _UNIT_TIME_DAY)
        return $reminder_before*24*60*60;
    if($reminder_unit== _UNIT_TIME_HOUR)
        return $reminder_before*60*60;
    if($reminder_unit==_UNIT_TIME_MINUTE)
        return $reminder_before*60;
    return 0;
}
function default_save_followups(){
    global $dbconn,$profile_id,$core,$clsISO,$clsProperty;
    $clsFollowUp = new FollowUp();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
    $clsNotify = new Notify();
    ###
    $customer_id = intval(Input::post('customer_id'));
    $followup_id = (int) Input::post('followup_id', 0);
	###
	$msg = "_error";
	if(Input::exists('hid','POST') && Input::post('hid')=="Update"){
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
		if($followup_id > 0){
			$oneFollowUp = $clsFollowUp->getOne($followup_id); 
            if($clsFollowUp->updateOne($followup_id, array(
                'type_id' => $type_id,
				'status_id' => $status_id,
				'intro' => Input::post('intro'),
                'priority_id'   => Input::post('priority_id'),
                'admin_id' => Input::post('admin_id',0),
                'list_user_slash' => $list_user_slash,
                'date_id' => $datetime,
                'reminder_before' => $reminder_before,
                'reminder_unit' => $reminder_unit,
                'reminder_time' => $reminder_time,
                'upd_date' => time(),
                'user_id_update' => $profile_id
            ))){
                $changed = 0;
                $content_change = '';
                if($oneTable['date_id'] != $datetime){
                    $content_change = "Thời gian thay đổi từ <strong>" . $clsISO->convertTimeToText($oneFollowUp['date_id']) 
                    .  "</strong> tới <strong>" . $clsISO->convertTimeToText($datetime). "</strong>";
                    $changed = 1;
                }
                if($oneTable['status_id'] != $status_id){
                    $content_change = ($changed==1?",":"") . "Tình trạng thay đổi từ <strong>" . $clsProperty->getTitle($oneFollowUp['status_id']) .  "</strong> tới <strong>" . $clsProperty->getTitle($status_id).'</strong>';
                    $changed = 1;
                }
                if($oneTable['type_id'] != $type_id){
                    $content_change = ($changed==1?",":"") . "Phương thức thay đổi từ <strong>" 
                    . $clsProperty->getTitle($oneTable['type_id']) .  "</strong> tới <strong>" 
                    . $clsProperty->getTitle($type_id). "</strong>";
                    $changed = 1;
                }
                if($changed==1){
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
			if($clsFollowUp->insert(array(
				$clsFollowUp->pkey => $followup_id,
				'customer_id' => $customer_id,
				'type_id' => $type_id,
				'status_id' => $status_id,
				'intro' => Input::post('intro'),
				'priority_id'   => Input::post('priority_id'),
				'admin_id' => Input::post('admin_id',0),
				'list_user_slash' => $list_user_slash,
				'date_id' => $datetime,
				'reminder_before' => $reminder_before,
				'reminder_unit' => $reminder_unit,
				'reminder_time' => $reminder_time,
				'user_id' => $profile_id,
				'reg_date' => time()
			))){
				$msg = "_success";
				/** Notification */
				$content = "<strong>".$clsProfile->getFullName($profile_id, $oneProfile)."</strong> đã tạo một lịch hẹn bằng <strong>".$clsProperty->getTitle($type_id)."</strong> vào lúc <strong>".sprintf('%s %s', $date_id, $time_id)."</strong>";
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
    echo($msg); die();
}
function default_load_followups_chart_care(){
	global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id');
	$html = '<div class="position-relative">
		<div id="chartContainer'.$uid.'" class="overflow-hidden" style="height:236px; width:100%;"></div>
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
	$lstFollowType = $clsProperty->getAll("property_type='_ISSUE_TYPE'","{$clsProperty->pkey},title");
	if(!empty($lstFollowType)){
		foreach($lstFollowType as $key => $val){
			$property_id = $val[$clsProperty->pkey];
			$total_followups = $clsFollowUp->countItem("`customer_id`='{$customer_id}' and `type_id`='{$property_id}'");
			$dataPoints[] = array(
				"label"	=> $clsProperty->getTitle($property_id, $val),
				"y"	=> ($total_followups+1)
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
	$callback = 'var chart = new CanvasJS.Chart("chartContainer'.$uid.'", {
		animationEnabled: true,
		axisX: '.json_encode($axisX).',
		axisY: $.extend('.json_encode($axisY).',{labelFormatter:function ( e ) {
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
		data: '.json_encode($data).'
	});
	chart.render();';
	// Return
	echo json_encode(array(
		'html' => $html,
		'callback' => $callback
	));
}
function default_load_followups_chart(){
	global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
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
			<div id="chartContainer_'.$uid.'" class="overflow-hidden" style="height:250px; width:100%;"></div>
				<div class="total_followup_chart mx-auto position-absolute text-center">
					<span class="fs-30">'.$total_followups.'</span><br> Follow-ups
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
			if(!empty($list_status_followups)){
				foreach($list_status_followups as $key => $val){
					$status_id = $val[$clsProperty->pkey];
					$list_colors[] = $val['bgcolor'];
					$title = $clsProperty->getTitle($status_id, $val);
					$total = $clsFollowUp->countItem("`customer_id`='{$company_id}' and `status_id`='{$status_id}'")+mt_rand(1,3);
					$total_tooltip = $total;
					if($total_followups == 0 && $key==0){
						$total = 1/2;
					}
					$dataPoints[] = array(
						'y'	=> $total,
						'label'	=> $title,
						'total'	=> $total_tooltip
					);
					$html .= '<li class="item d-flex align-items-center">
						<span class="item-color mr-2" style="background:'.$val['bgcolor'].'"></span> 
						<span class="item-text">'.$title.'</span>
					</li>';
				}
				unset($list_status_followups);
			}
		$html .= '</ul>';
	$html .= '</div>
	</div>';
	//$clsISO->pre($dataPoints);die;
	$callback = 'CanvasJS.addColorSet("greenShadesFollowupChart", '.json_encode($list_colors).');
	var chart = new CanvasJS.Chart("chartContainer_'.$uid.'", {
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
			dataPoints: '.json_encode($dataPoints).'
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
    global $_frontIsLoggedin_user_id,$core,$clsISO,$clsUser,$clsProperty;
	global $profile_id, $oneProfile, $deviceType;
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
    #
	$uid = $clsISO->getUniqid();
    $customer_id = (int) Input::post('customer_id',0);
	$html_options = sprintf('<option value="0">%s</option>', 'Lựa chọn người quản lý');
	$field = "{$clsProfile->pkey},full_name,first_name,last_name";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by {$clsProfile->pkey} ASC", $field);
	if(!empty($list_staffs)){
		foreach($list_staffs as $key => $val){
			$usr_id = $val[$clsProfile->pkey];
			$html_options.= sprintf('<option value="%s">%s</option>', $usr_id, $clsProfile->getFullName($usr_id, $val));
		}
		unset($list_staffs);
	}
    $html = '<div class="modal-dialog modal-sm" role="dialog">
		<form class="modal-content" method="post">
			<div class="modal-header">
				<h5 class="modal-title">'.$clsISO->makeIcon('bx-user','Thay đổi người phụ trách').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<select class="form-control in-modal '.($deviceType=='phone'?'form-select':'iso-select2').'" data-width="100%" 
				data-allow-clear="true" name="admin_id" data-placeholder="Thêm người theo dõi">
					'.$html_options.'
				</select>
			</div>
			<style type="text/css">
				.select2-container{ z-index:99999 !important;}
			</style>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" uid="'.$uid.'" customer_id="'.$customer_id.'" onClick="$Core.crm.do_change_assigned(this, event)" 
				class="btn btn-primary">Thay đổi</button>
			</div>
		</form>
	</div>';
    // Return
    echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_do_change_assigned(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	###
	$msg = "_error";
	$admin_id = (int) Input::post('admin_id',0);
	$customer_id = (int) Input::post('customer_id',0);
	//$clsISO->print_pre($_POST); die();
	$oCustomer = $clsCustomer->getOne($customer_id, 'admin_id,user_id,status_id,list_share_id,more_information');
	$admin_old_id = $oCustomer['admin_id'];
	$list_share_id = $oCustomer['list_share_id'];
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
	$list_share_id.= "|".$admin_old_id."|";
	$use_globe = ($oCustomer['user_id'] == $admin_id) ? 0 : 1;
	if($clsCustomer->updateOne($customer_id, array(
		'upd_date' => time(),
		'admin_id' => $admin_id,
		'use_globe' => $use_globe,
		'list_share_id' => $list_share_id,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
		$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
			$clsProfile->getFullName($profile_id, $oneProfile), 
			$clsCustomer->getName($customer_id, $oCustomer)
		);
		$clsNotify->insertNotify('Customer',$clsCustomer->pkey, $customer_id, $titleNoty, time(),"|".$admin_id."|");
	}
	// Return
	echo $msg; die();
}
function default_set_all(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	###
	$msg = "_error";
	$is_all = (int) Input::post('is_all', 0);
	$more_information = $oneProfile['more_information'];
	$more_information['crm_view_all'] = $is_all;
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_set_view(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	###
	$msg = "_error";
	$view_by = Input::post('view_by', 'table');
	$more_information = $oneProfile['more_information'];
	$more_information['_ss_view_by'] = $view_by;
	if($clsProfile->updateOne($profile_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_set_archived(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	$clsArchived = new Archived();
	#
	$msg = "_error";
	$customer_id = (int) Input::post('customer_id', 0);
	$tmp = $clsArchived->getByCond("`profile_id`='{$profile_id}' and `customer_id`='{$customer_id}'");
	if(!empty($tmp)){
		if($clsArchived->deleteOne($tmp[$clsArchived->pkey])){
			$msg = "_success";
		}
	} else {
		if($clsArchived->insert(array(
			'reg_date' => time(),
			'profile_id' => $profile_id,
			'customer_id' => $customer_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_load_pop_upd_admin(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::request('customer_id',0);
	$oneCustomer = $clsCustomer->getOne($customer_id, "status_id");
	$status_id = !empty($oneCustomer) ? $oneCustomer['status_id'] : 0;
	###
    $html = '<form method="post">
        <div class="form-group mb-2">
			<select class="iso-selectizeNotSearch required" name="admin_id" data-width="100%" data-placeholder="Người tham gia" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" data-width="100%">
				<option value="'.$profile_id.'" selected="selected">
					'.$clsProfile->getIndentityV2($profile_id, $oneProfile, false).'
				</option>
			</select>
        </div>
        <div class="form-group">
			<input type="hidden" name="p_field" value="admin_id" />
            <button type="button" class="btn btn-primary" onclick="$Core.crm.update_field(this,event);" p_field="admin_id" uid="'.$uid.'" 
			p_id="'.$customer_id.'">'.$core->makeIcon('check', 'Cập nhật').'</button>
        </div>
    </form>';
    // Return
    echo  $html; die();
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
	$cond = "`tag_type`='_crm' and `user_id`='{$profile_id}'";
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
function default_load_pop_tag(){
	global $profile_id,$oneProfile,$core,$clsISO,$clsUser,$clsProperty;
	$clsTag = new Tag();
	$clsProfile = new Profile();
    $clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$html_tags = array();
	$uid = $clsISO->getUniqid();
	$holderG = Input::request('holderG', '_pop');
	$customer_id = (int) Input::request('customer_id',0);
	$list_tags_id = $clsCustomer->getOneField('list_tags_id', $customer_id);
	$list_tags_arrs = !empty($list_tags_id) 
		? $clsISO->getArrayByTextSlash($list_tags_id) : array();
	if(!empty($list_tags_arrs)){
		foreach($list_tags_arrs as $tag_id){
			$html_tags[] = $clsTag->getTitle($tag_id);
		}
	}
	###
	if($holderG == "_modal"){
		$html = '<div class="modal-dialog" role="document">
			<form class="modal-content" method="post">
				<div class="modal-header">
					<h5 class="modal-title">Nhập Tags</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input class="tags" id="tags_'.$uid.'" name="tags" maxlength="255" autocomplete="off" 
					placeholder="Nhập tags" value="'.(!empty($html_tags) ? implode(',',$html_tags) : "").'" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" uid="'.$uid.'" holderG="'.$holderG.'" p_id="'.$customer_id.'" p_field="tags" onClick="$Core.crm.update_field(this, event)" class="btn btn-primary">Lưu lại</button>
				</div>
			</form>
		</div>';
		// Return
		echo json_encode(array(
			'uid' => $uid,
			'html' => $html
		)); die();
	} else {
		$html = '<form method="post">
			<div class="form-group mb-2">
				<input class="tags" id="tags_'.$uid.'" name="tags" id="input_tags_'.time().'" maxlength="255" placeholder="Nhập tags" value="'.(!empty($html_tags) ? implode(',',$html_tags) : "").'" />
			</div>
			<hr class="my-3" />
			<div class="form-group">
				<input type="hidden" name="p_field" value="tags" />
				<button type="button" class="btn btn-outline-primary" onclick="$Core.crm.update_field(this,event);" 
				p_field="tags" uid="'.$uid.'" p_id="'.$customer_id.'">'.$core->makeIcon('check', 'Cập nhật').'</button>
			</div>
		</form>';
		 // Return
		echo $html; die();
	}
}

?>
