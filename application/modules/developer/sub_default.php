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
function getAccessToken($jsonKeyFilePath) {
    $jsonKey = json_decode(file_get_contents($jsonKeyFilePath), true);
    $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $jwtClaim = base64_encode(json_encode([
        'iss' => $jsonKey['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => $jsonKey['token_uri'],
        'exp' => time() + 3600,
        'iat' => time(),
    ]));
    $signature = '';
    openssl_sign($jwtHeader . '.' . $jwtClaim, $signature, $jsonKey['private_key'], 'SHA256');
    $jwt = $jwtHeader . '.' . $jwtClaim . '.' . base64_encode($signature);
    // Lấy access token
    $response = json_decode(file_get_contents($jsonKey['token_uri'], false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ])
        ]
    ])), true);
    return $response['access_token'];
}
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsBooking = new Booking();
	$clsFPoint = new FPoint();
	$clsSetting = new Setting();
	
	$tmp = $clsCustomer->getAll("`list_block_id`<>''", "{$clsCustomer->pkey},`list_block_id`");
	if(!empty($tmp)){
		$arr_block_cached = $arr_project_cached = $arr_setting_cached = array();
		$arr_projects = $clsSetting->getCacheItems('_PROJECT');
		if(!empty($arr_projects)){
			foreach($arr_projects as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$project_id = $core->get_field($more_information, "project_id", 0);
				$block_id = $core->get_field($more_information, "block_id", 0);
				$key = sprintf('%s_%s', $project_id, $block_id);
				$arr_project_cached[$key] = $val[$clsSetting->pkey];
			}
		}
		foreach($tmp as $key => $val){
			$list_block_id = $val['list_block_id'];
			$arr_block = $clsISO->getArrayByTextSlash($list_block_id);
			foreach($arr_block as $block_id){
				if(!isset($arr_block_cached[$block_id])){
					$oneBlock = $clsProperty->getOne($block_id, "`title`,`for_id`");
					$arr_block_cached[$block_id] = $oneBlock;
				} else {
					$oneBlock = $arr_block_cached[$block_id];
				}
				$project_id_old = $oneBlock['for_id'];
				$key = sprintf('%s_%s', $project_id_old, $block_id);
				if(isset($arr_project_cached[$key])){
					$project_id = $arr_project_cached[$key];
					$list_block_id = str_replace($block_id, $project_id, $list_block_id);
					// $clsISO->print_pre($list_block_id); die();
				}
			}
			$clsCustomer->updateOne($val[$clsCustomer->pkey], array(
				'list_block_id' => $list_block_id
			));
		}
	}
	die("xx");
	
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$fpoint_configs = array();
	$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$fpoint_configs = $decoder->decodeFile($cachedFile);
	}
	// $clsISO->print_pre($fpoint_configs); die();
	
	// $clsFPoint->deleteByCond("ns_type='Lpoint'");
	// $clsProfile->updateByCond("1=1", "`total_Lpoint`='0'");
	// die();
	if(1==2){
		$year = 2025;
		$clsBilling = new Billing();
		$list_billings = $clsBilling->getAll("`staff_id`<>'"._PROFILE_PARTNER_ID."' and `is_cancel`=0 and `is_alliance`='0' 
			and FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}' ORDER BY reg_date DESC");
		// $clsISO->print_pre($list_billings); die();
		if(!empty($list_billings)){
			foreach($list_billings as $key => $val){
				$clsFPoint->insert_billing_LPoint($val[$clsBilling->pkey], $val);
			}
		}
		$clsISO->print_pre($list_billings); die();
	}
	if(1==2){
		$year = 2025;
		// $year = date('Y');
		for($month=1; $month<=12; $month++){
			$field = "{$clsProfile->pkey},`role_id`,`department_id`,`level_id`,`start_date`,`contract_date`,`total_Lpoint`,`full_name`";
			$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`='1' and 
				`status_id`='"._STATUS_STAFF_ON_ID."' order by {$clsProfile->pkey} asc", $field);
			$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			$last_day = strtotime(sprintf('%s-%s-%s', $end_day, $month, $year));
			if(!empty($list_staffs)){
				foreach($list_staffs as $key => $val){
					$clsFPoint->insert_LPoint($val[$clsProfile->pkey], $val, $last_day);
				}
			}
		}
	}
	if(1==2){
		$tmp = $dbconn->getAll("SELECT * from `default_fpoint_bkc` WHERE `ns_type`='Lpoint' AND `act` NOT LIKE '%_seniority'");
		foreach($tmp as $key => $val){
			$profile_id = $val['profile_id'];
			$ns_type = $val['ns_type'];
			$act = $val['act'];
			$score = (int) $val['score'];
			$for_id = $val['for_id'];
			$reg_date = $val['reg_date'];
			if($clsFPoint->countItem("`ns_type`='{$ns_type}' AND `for_id`='{$for_id}' AND `profile_id`='{$profile_id}' 
				AND `act`='{$act}' AND `reg_date`='{$reg_date}'") == 0){
				$total_Lpoint = $clsProfile->getOneField("total_Lpoint", $profile_id);
				$total_Lpoint += (int) $score;
				if($clsFPoint->insert(array(
					'ns_type' => $ns_type,
					'for_id' => $for_id,
					'score' => $score,
					'profile_id' => $profile_id,
					'act' => $act,
					'reg_date' => $reg_date,
					'content' => $val['content'],
					'is_cancel' => $val['is_cancel']
				))){
					$clsProfile->updateOne($profile_id, array(
						'total_Lpoint' => $total_Lpoint
					));
				}
			}
		}
		$tmp = $dbconn->getAll("SELECT * from default_fpoint WHERE `ns_type`='Lpoint' AND (`act` NOT LIKE '%_seniority' AND `act` NOT LIKE '%_259') AND `score`=0");
		foreach($tmp as $key => $val){
			$for_id = $val['for_id'];
			$profile_id = $val['profile_id'];
			$act = $val['act'];
			$oneBilling = $clsBilling->getOne($for_id);
			// $clsISO->print_pre($val); die();
			if(isset($fpoint_configs[$act]) && !empty($fpoint_configs[$act])){
				if($clsISO->checkContainer($act, 'dept_', "") || $clsISO->checkContainer($act, 'manage_', "")){
					$oneFPoint = $dbconn->getRow("select * FROM `default_fpoint_bkc` where `id`='{$for_id}'");
					$for_id = $oneFPoint['for_id'];
					$oneBilling = $clsBilling->getOne($for_id);
					$score = $fpoint_configs[$act]['score'];
					$content = $fpoint_configs[$act]['content'];
					$content = sprintf($content, $clsProfile->getFullName($oneBilling['staff_id']), $oneBilling['stock_code']);
				} else {
					$oneBilling = $clsBilling->getOne($for_id);
					$score = $fpoint_configs[$act]['score'];
					$content = $fpoint_configs[$act]['content'];
					$content = sprintf($content, $oneBilling['stock_code']);
				}
				$total_Lpoint = $clsProfile->getOneField("total_Lpoint", $profile_id);
				$total_Lpoint += (int) $score;
				if(!empty($content) && $clsFPoint->updateOne($val[$clsFPoint->pkey], array(
					'score' => $score,
					'content' => $content
				))){
					$clsProfile->updateOne($profile_id, array(
						'total_Lpoint' => $total_Lpoint
					));
				}
			}
			// $clsISO->print_pre($total_Lpoint); die();
		}
	}
	die();
	$list_bookings = $clsBooking->getAll("1=1 order by `reg_date` ASC");
	if(!empty($list_bookings)){
		foreach($list_bookings as $key => $val){
			$block_id = $val['block_id'];
			$booking_type = $val['booking_type'];
			$reg_date = !empty($val['booking_date']) ? $val['booking_date'] : $val['reg_date'];
			$booking_code = $clsBooking->getCode($booking_type, $block_id, $val['reg_date']);
			// $clsISO->print_pre($booking_code); die();
			$clsBooking->updateOne($val[$clsBooking->pkey], array(
				'booking_code' => $booking_code 
			));
		}
	}
	$clsISO->print_pre($list_bookings); die();
	die();

	/* 
	$tmp = $clsCustomer->getAll("`list_campaign_id` like '%|30|%'", "{$clsCustomer->pkey}");
	// $clsISO->print_pre($tmp); die();
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$list_share_id = array_merge(_PROFILE_SUPPER_ID, array(_PROFILE_TAT_ID, _PROFILE_ROOT_ID));
			$list_share_id = $clsISO->makeSlashListFromArrayRoot($list_share_id);
			$clsCustomer->updateOne($val[$clsCustomer->pkey], array(
				'list_share_id' => $list_share_id
			));
		}
	}
	$clsISO->print_pre(count($tmp)); die();
	
	$arr_staffs = $clsProfile->getAll("`is_trash`=0 and `gender_id`='2' and status_id='"._STATUS_STAFF_ON_ID."'", $clsProfile->pkey);
	if(!empty($arr_staffs) && 1==2){
		foreach($arr_staffs as $key => $val){
			$clsProfile->updateOne($val[$clsProfile->pkey], array(
				'max_spin_per_user' => 1
			));
		}
	}
	die();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `status_id`='"._STOCK_STATUS_LOCK_ID."' 
	AND building_id='10623'", "{$clsStock->pkey},`show_website`");
	$clsISO->print_pre($list_stocks); die();
	if(!empty($list_stocks)){
		foreach($list_stocks as $key => $val){
			$stock_id = $val[$clsStock->pkey];
			// $show_website = $val['show_website'];
			$show_website= '|user.fh||MOC||partner.fh|';
			$clsStock->updateOne($stock_id, array(
				'show_website' => $show_website
			));
		}
	}
	$clsISO->print_pre($list_stocks); die(); */
	
	#- Reuired Library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');				
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	#- Init Client
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID_DEV);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET_DEV);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN_DEV);
	$client->setScopes([Google_Service_Drive::DRIVE]);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	
	$service = new Google_Service_Sheets($client);
	// 1DYQknf2wWDfrzA7xiYOIauY60bFEIMrjXAp0bbfdv2Q
	// $spreadsheetId = '1eeB4Oee_H81tYA2hZg-zEn0PXWPdO5i94BZolXMG46Y';
	$spreadsheetId = '1UqbPLJHxFrpgitkzaQ5j7x5Gf6voLgZp2PAMWm7rX5M';
	
	$project_id = 3;
	$billing_type = 10984;
	$tmp = $clsBilling->getAll("`project_id`='{$project_id}' and `billing_type`='{$billing_type}'");
	if(!empty($tmp)){
		foreach($tmp as $key => $oval){
			// $clsISO->print_pre($oval); die();
			$billing_source_id = $oval['billing_source_id'];
			$deposit_date = $oval['deposit_date'];
			$commission = $oval['commission'];
			$otp_date = $oval['otp_date'];
			$contract_date = $oval['contract_date'];
			$more_information = $oval['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information['billing_source_id'] = $billing_source_id;
			$more_information['deposit_date'] = $deposit_date;
			$more_information['commission'] = $commission;
			$more_information['contract_date'] = $contract_date;
			$more_information['otp_date'] = $otp_date;
			$clsBilling->updateOne($oval[$clsBilling->pkey], array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	$clsISO->print_pre($tmp); die();
}
function default_load_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	#
	$role_id = (int) $oneProfile['role_id'];
	$team_id = (int) $oneProfile['team_id'];
	$department_id = (int) $oneProfile['department_id'];
	$prof_information = $oneProfile['more_information'];
	$is_dir_sale = $is_dir_project = 0;
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
		$is_dir_project = 1;
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY')){
		$is_dir_sale = 1;
	}
	$toId = $clsISO->getUniqid();
	$gId = Input::post('gId', $uid);
	$date_type = Input::post('date_type', 'month'); // Ngày, tuần, quý, tháng, năm.
	$project_id = (int) Input::post('project_id', 0);
	$block_id = (int) Input::post('block_id', 0);
	$role_type = _ROLE_STAFF_SALE; // Sales
	if($is_dir_project == 1 && $is_dir_sale == 1){
		$role_type = (int) Input::post('role_type', _ROLE_GD_SALE); // GĐ KD
	}
	$html = "";
	if(($role_type == _ROLE_GD_SALE) || ($is_dir_sale == 1 && $is_dir_project == 0)){
		$sql_query = "`booking_type`='"._BOOKING_TYPE_INTERNAL_ID."'";
		if($project_id > 0) {
			$sql_query.= " AND `project_id`='{$project_id}'";
		}
		if($block_id > 0) {
			$sql_query.= " AND `block_id`='{$block_id}'";
		}
		if($date_type == 'date'){
			$date = Input::post('date', date('Y-m-d'));
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y-%m-%d')='{$date}'";
		} else if($date_type == 'week'){
			$week = Input::post('week', date('Y-W'));
			$week = str_replace('W','',$week);
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%x-%v')='{$week}'";
		} else if($date_type == 'month'){
			$month = Input::post('month', date('Y-m'));
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y-%m')='{$month}'";
		} else if($date_type == 'year'){
			$year = Input::post('year', date('Y'));
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y')='{$year}'";
		}
		$sql_where = $sql_query;
		$arr_staffs = $arr_staffs_in = $arr_uniqid_staffs = array();
		$field = "{$clsProfile->pkey},`code`,`full_name`,'0' AS `total_bookings`";
		$tmp = $clsProfile->getAll("`is_trash`=0 AND `department_id`='{$department_id}'", $field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$staff_id = $val[$clsProfile->pkey];
				$arr_staffs_in[] = $staff_id;
				$arr_staffs[$staff_id] = $val;
			}
			unset($tmp);
			$sql_query.= " AND JSON_EXTRACT(`more_information`,\"$.staff_id\") IN (".implode(',',$arr_staffs_in).")";
		}
		$total_bookings = $total_matched_bookings = $total_refund_bookings = 0;
		$total_amount_bookings = $total_matched_bookings = $total_amount_matched_bookings = 0;
		$field = "{$clsBooking->pkey},`booking_date`,`more_information`,`status_id`,`amount`";
		$list_bookings = $clsBooking->getAll($sql_query, $field);
		if(!empty($list_bookings)){
			$total_bookings = count($list_bookings);
			foreach($list_bookings as $key => $val){
				$status_id = $val['status_id'];
				$amount = $val['amount'];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$total_amount_bookings += $clsISO->processSmartNumber($amount);
				$staff_id = (int) $core->get_field($more_information, "staff_id", 0);
				if($staff_id > 0){
					$arr_staffs[$staff_id]['total_bookings'] += 1;
					if(!in_array($staff_id, $arr_uniqid_staffs)){
						$arr_uniqid_staffs[] = $staff_id;
					}	
				}
				if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
					$total_matched_bookings += 1;
					$total_amount_matched_bookings += $clsISO->processSmartNumber($amount);
				} else if($status_id== _BOOKING_REFUND_DEPOSIT_ID){
					$total_refund_bookings += 1;
					$total_amount_refund_bookings += $clsISO->processSmartNumber($amount);
				}
			}
		}
		$html.= '<div class="briefs gap-2 gap-lg-2 d-flex flex-wrap">
			<div onClick="$Core.dashboard.open_booking(this, event)" holderG="dep" status_id="0" title="Xem chi tiết" data-bs-toggle="tooltip" 
				class="brief-item a1a bg-orange cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'">
				<p class="mb-1">Tổng số</p>
				<h4 class="mb-1 text-fs-15">'.$total_bookings.' <small>booking</small></h4>
				<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_bookings,2).'</h3>
			</div>
			<div onClick="$Core.dashboard.open_booking(this, event)" holderG="dep" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'" 
				class="brief-item a2a bg-azure cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
				title="Xem chi tiết" data-bs-toggle="tooltip">
				<p class="mb-1">Đã khớp</p>
				<h4 class="mb-1 text-fs-15">'.$total_matched_bookings.' <small>booking</small></h4>
				<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_matched_bookings,2).'</h3>
			</div>
			<div onClick="$Core.dashboard.open_booking(this, event)" holderG="dep" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'" 
				class="brief-item a3a bg-solid cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
				title="Xem chi tiết" data-bs-toggle="tooltip">
				<p class="mb-1">Đã hoàn</p>
				<h4 class="mb-1 text-fs-15">'.$total_refund_bookings.' <small>booking</small></h4>
				<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_refund_bookings,2).'</h3>
			</div>
		</div>';
		if($total_bookings > 0 && !empty($arr_uniqid_staffs)){ $ii = 1;
			$total_staffs = count($arr_uniqid_staffs);
			$columns = @array_column($arr_staffs, 'total_bookings');
			@array_multisort($columns, SORT_DESC, $arr_staffs);
			$html.= '<div class="d-flex my-2">
				<span class="badge bg-label-primary rounded-pill">Booking theo nhân viên</span>
			</div>
			<div class="table-container no-shadow mb-2">
				<table cellpadding="0" cellspacing="0" class="table table-bordered mb-1">
					<thead><tr>
						<th class="h-px-35 w-px-50 bg-lighter text-center">No.</th>
						<th class="h-px-35 bg-lighter">Tên Sales</th>
						<th class="h-px-35 bg-lighter text-center">Số BK</th>
					</tr></thead>';
				foreach($arr_staffs as $key => $val){
					if($val['total_bookings'] > 0){
						$staff_id = $val[$clsProfile->pkey];
						$where = $sql_where. " AND JSON_EXTRACT(`more_information`,\"$.staff_id\")='{$staff_id}'";
						$html.= '<tr toId="'.$toId.'" class="'.($ii > 5 ? 'toggle-row d-none' : 'visible-row').'">
							<td class="text-center">'.$ii.'</td>
							<td>'.$clsProfile->getIndentityV2($staff_id, $val).'</td>
							<td class="text-center fw-bold">
								<a onClick="$Core.dashboard.open_booking(this, event)" status_id="0" holderG="staff" query_string="'.$clsISO->base64url_encode($where).'" title="Xem chi tiết" data-bs-toggle="tooltip" class="text-main cursor-pointer">'.$val['total_bookings'].' <i class=\'bx bx-link-external text-fs-11\'></i></a>
							</td>
						</tr>';
						++$ii;
					}	
				}
			$html.= '</table></div>
			'.($total_staffs > 5 ? '<div class="d-flex align-items-center justify-content-center">
				<button toId="'.$toId.'" data-toggle="ripple" onclick="$Core.util.toogle_tr(this, event)" 
					class="btn btn-sm btn-outline-default rounded-pill px-3">
					<i class="bx bx-chevron-down"></i>
					<span>Xem thêm</span>
				</button>
			</div>' : '').'';
		}
	} else if(($is_dir_sale == 0 && $is_dir_project == 1) || ($role_type == _ROLE_GD_PROJECT)){
		$is_required = 0;
		$project_dir_current = $core->get_field($prof_information, "project_dir_current", []);
		if(!empty($project_dir_current)){
			$project_id = $core->get_field($project_dir_current, "project_id", 0);
			$block_id = $core->get_field($project_dir_current, "block_id", 0);
		} else {
			$l_field = "`t1`.{$clsProperty->pkey} as `block_id`,`t1`.`for_id` as `project_id`";
			$r_field = "0 AS `block_id`,`project_id`";
			$arr_projects = $dbconn->getAll("(SELECT {$l_field} FROM {$clsProperty->tbl} as `t1` INNER JOIN {$clsProject->tbl} as `t2` ON `t1`.`for_id`=`t2`.`project_id` WHERE `t1`.`is_trash`=0 AND `t1`.`property_type`='_BLOCK' AND JSON_EXTRACT(`t1`.`more_information`,\"$.is_booking\")=1 AND JSON_EXTRACT(`t1`.`more_information`,\"$.project_admins_slash\") LIKE '%|{$profile_id}|%') UNION ALL (SELECT {$r_field} FROM {$clsProject->tbl} WHERE JSON_EXTRACT(`more_information`,\"$.is_booking\")=1 AND JSON_EXTRACT(`more_information`,\"$.project_admins_slash\") LIKE '%|{$profile_id}|%')");
			if(!empty($arr_projects)) {
				if(count($arr_projects) == 1){
					$oneProject = $arr_projects[0];
					$project_id = $oneProject['project_id'];
					$block_id = $oneProject['block_id'];
				} else {
					$is_required = 1;
				}
			}
		}
		if($is_required == 0){
			$sql_query = "`booking_type`='"._BOOKING_TYPE_INTERNAL_ID."'";
			if($project_id > 0) {
				$sql_query.= " AND `project_id`='{$project_id}'";
			}
			if($block_id > 0) {
				$sql_query.= " AND `block_id`='{$block_id}'";
			}
			if($date_type == 'date'){
				$date = Input::post('date', date('Y-m-d'));
				$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y-%m-%d')='{$date}'";
			} else if($date_type == 'week'){
				$week = Input::post('week', date('Y-W'));
				$week = str_replace('W','', $week);
				$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%x-%v')='{$week}'";
			} else if($date_type == 'month'){
				$month = Input::post('month', date('Y-m'));
				$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y-%m')='{$month}'";
			} else if($date_type == 'year'){
				$year = Input::post('year', date('Y'));
				$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y')='{$year}'";
			}
			$sql_where = $sql_query; $arr_staffs = array();
			$total_bookings = $total_matched_bookings = $total_refund_bookings = 0;
			$total_amount_bookings = $total_matched_bookings = $total_amount_matched_bookings = 0;
			$field = "{$clsBooking->pkey},`booking_date`,`more_information`,`status_id`,`amount`";
			$list_bookings = $clsBooking->getAll($sql_query, $field);
			if(!empty($list_bookings)){
				$total_bookings = count($list_bookings);
				foreach($list_bookings as $key => $val){
					$amount = $val['amount'];
					$status_id = $val['status_id'];
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$total_amount_bookings += $clsISO->processSmartNumber($amount);
					$staff_id = (int) $core->get_field($more_information, "staff_id", 0);
					if($staff_id > 0){
						if(isset($arr_staffs[$staff_id])){
							$arr_staffs[$staff_id]['total_bookings'] += 1;
						} else {
							$arr_staffs[$staff_id] = array(
								'total_bookings' => 1
							);
						}
					}
					if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
						$total_matched_bookings += 1;
						$total_amount_matched_bookings += $clsISO->processSmartNumber($amount);
					} else if($status_id== _BOOKING_REFUND_DEPOSIT_ID){
						$total_refund_bookings += 1;
						$total_amount_refund_bookings += $clsISO->processSmartNumber($amount);
					}
				}
			}
			$html.= '<div class="briefs gap-2 gap-lg-2 d-flex flex-wrap">
				<div onClick="$Core.dashboard.open_booking(this, event)" holderG="dep" status_id="0" title="Xem chi tiết" data-bs-toggle="tooltip" 
					class="brief-item a1a bg-orange cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'">
					<p class="mb-1">Tổng số</p>
					<h4 class="mb-1 text-fs-15">'.$total_bookings.' <small>booking</small></h4>
					<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_bookings,2).'</h3>
				</div>
				<div onClick="$Core.dashboard.open_booking(this, event)" holderG="dep" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'" 
					class="brief-item a2a bg-azure cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
					title="Xem chi tiết" data-bs-toggle="tooltip">
					<p class="mb-1">Đã khớp</p>
					<h4 class="mb-1 text-fs-15">'.$total_matched_bookings.' <small>booking</small></h4>
					<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_matched_bookings,2).'</h3>
				</div>
				<div onClick="$Core.dashboard.open_booking(this, event)" holderG="dep" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'" 
					class="brief-item a3a bg-solid cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
					title="Xem chi tiết" data-bs-toggle="tooltip">
					<p class="mb-1">Đã hoàn</p>
					<h4 class="mb-1 text-fs-15">'.$total_refund_bookings.' <small>booking</small></h4>
					<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_refund_bookings,2).'</h3>
				</div>
			</div>';
			if($total_bookings > 0 && !empty($arr_staffs)){ $ii = 1;
				$arr_uniqid_staffs = array_keys($arr_staffs);
				$field = "{$clsProfile->pkey},`code`,`full_name`";
				$tmp = $clsProfile->getAll("{$clsProfile->pkey} in (".implode(',', $arr_uniqid_staffs).")", $field);
				if(!empty($tmp)){
					foreach($tmp as $key => $val){
						$staff_id = $val[$clsProfile->pkey];
						$arr_staffs[$staff_id]['code'] = $val['code'];
						$arr_staffs[$staff_id]['full_name'] = $val['full_name'];
					}
					unset($tmp);
				}
				$total_staffs = count($arr_uniqid_staffs);
				$columns = @array_column($arr_staffs, 'total_bookings');
				@array_multisort($columns, SORT_DESC, $arr_staffs);
				$html.= '<div class="d-flex my-2">
					<span class="badge bg-label-primary rounded-pill">Booking theo nhân viên</span>
				</div>
				<div class="table-container no-shadow mb-2">
					<table cellpadding="0" cellspacing="0" class="table table-bordered mb-1">
						<thead><tr>
							<th class="h-px-35 w-px-50 bg-lighter text-center">No.</th>
							<th class="h-px-35 bg-lighter">Tên Sales</th>
							<th class="h-px-35 bg-lighter text-center">Số BK</th>
						</tr></thead>';
					foreach($arr_staffs as $key => $val){
						if($val['total_bookings'] > 0){
							$staff_id = $val[$clsProfile->pkey];
							$where = $sql_where. " AND JSON_EXTRACT(`more_information`,\"$.staff_id\")='{$staff_id}'";
							$html.= '<tr toId="'.$toId.'" class="'.($ii > 5 ? 'toggle-row d-none' : 'visible-row').'">
								<td class="text-center">'.$ii.'</td>
								<td>'.$clsProfile->getIndentityV2($staff_id, $val).'</td>
								<td class="text-center fw-bold">
									<a onClick="$Core.dashboard.open_booking(this, event)" status_id="0" holderG="staff" query_string="'.$clsISO->base64url_encode($where).'" title="Xem chi tiết" data-bs-toggle="tooltip" class="text-main cursor-pointer">'.$val['total_bookings'].' <i class=\'bx bx-link-external text-fs-11\'></i></a>
								</td>
							</tr>';
							++$ii;
						}	
					}
				$html.= '</table></div>
				'.($total_staffs > 5 ? '<div class="d-flex align-items-center justify-content-center">
					<button toId="'.$toId.'" data-toggle="ripple" onclick="$Core.util.toogle_tr(this, event)" 
						class="btn btn-sm btn-outline-default rounded-pill px-3">
						<i class="bx bx-chevron-down"></i>
						<span>Xem thêm</span>
					</button>
				</div>' : '').'';
			}
		} else {
			$html.= '<div class="p-3 border-dahsed text-center rounded-2">
				<p><i class=\'bx bx-bell\'></i> Bạn chưa cài đặt dự án. <br /> 
					Hãy tiếp tục bằng cách cài đặt dự án để ưu tiên hiển thị!</p>
				<div class="d-flex align-items-center justify-content-center">
					<button data-bs-toggle="tooltip" title="Cài đặt dự án" onClick="$Core.dashboard.open_config_project(this, event)" 
						class="btn btn-link bg-grayter" gId="'.$gId.'"><i class="bx bx-cog"></i> Cài đặt dự án</button>
				</div>
			</div>';
		}
	} else if(($is_dir_sale == 0 && $is_dir_project == 0) 
		|| ($role_type == _ROLE_STAFF_SALE || ($is_dir_sale == 1 || $is_dir_project == 1))){
		$sql_query = "`booking_type`='"._BOOKING_TYPE_INTERNAL_ID."'";
		$sql_query.= " AND JSON_EXTRACT(`more_information`,\"$.staff_id\")='{$profile_id}'";
		if($project_id > 0) {
			$sql_query.= " AND `project_id`='{$project_id}'";
		}
		if($block_id > 0) {
			$sql_query.= " AND `block_id`='{$block_id}'";
		}
		if($date_type == 'date'){
			$date = Input::post('date', date('Y-d-m'));
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%d-%m-%Y')='{$date}'";
		} else if($date_type == 'week'){
			$week = Input::post('week', date('Y-W'));
			$week = str_replace('W','',$week);
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%x-%v')='{$week}'";
		} else if($date_type == 'month'){
			$month = Input::post('month', date('Y-m'));
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y-%m')='{$month}'";
		} else if($date_type == 'year'){
			$year = Input::post('year', date('Y'));
			$sql_query.= " AND FROM_UNIXTIME(`booking_date`,'%Y')='{$year}'";
		}
		$total_bookings = $total_matched_bookings = $total_refund_bookings = 0;
		$field = "{$clsBooking->pkey},`booking_date`,`more_information`,`status_id`,`amount`";	
		$list_bookings = $clsBooking->getAll($sql_query, $field);
		if(!empty($list_bookings)){
			$total_bookings = count($list_bookings);
			foreach($list_bookings as $key => $val){
				$amount = $val['amount'];
				$status_id = $val['status_id'];
				$total_amount_bookings += $clsISO->processSmartNumber($amount);
				if($status_id == _BOOKING_MATCHED_DEPOSIT_ID){
					$total_matched_bookings += 1;
					$total_amount_matched_bookings += $clsISO->processSmartNumber($amount);
				} else if($status_id== _BOOKING_REFUND_DEPOSIT_ID){
					$total_refund_bookings += 1;
					$total_amount_refund_bookings += $clsISO->processSmartNumber($amount);
				}
			}
		}
		$html.= '<div class="briefs gap-2 gap-lg-2 d-flex flex-wrap">
			<div  onClick="$Core.dashboard.open_booking(this, event)" holderG="staff" status_id="0" 
				class="brief-item a1a bg-orange cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
				title="Xem chi tiết" data-bs-toggle="tooltip">
				<p class="mb-1">Tổng số</p>
				<h4 class="mb-1 text-fs-15">'.$total_bookings.' <small>booking</small></h4>
				<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_bookings,2).'</h3>
			</div>
			<div onClick="$Core.dashboard.open_booking(this, event)" holderG="staff" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'" 
				class="brief-item bg-azure cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
				title="Xem chi tiết" data-bs-toggle="tooltip">
				<p class="mb-1">Đã khớp</p>
				<h4 class="mb-1 text-fs-15">'.$total_matched_bookings.' <small>booking</small></h4>
				<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_matched_bookings,2).'</h3>
			</div>
			<div onClick="$Core.dashboard.open_booking(this, event)" holderG="staff" status_id="'._BOOKING_MATCHED_DEPOSIT_ID.'" 
				class="brief-item bg-solid cursor-pointer flex-fill" query_string="'.$clsISO->base64url_encode($sql_query).'" 
				title="Xem chi tiết" data-bs-toggle="tooltip">
				<p class="mb-1">Đã hoàn</p>
				<h4 class="mb-1 text-fs-15">'.$total_refund_bookings.' <small>booking</small></h4>
				<h3 class="mb-0 text-fs-16">'.$clsISO->shortNumber($total_amount_matched_bookings,2).'</h3>
			</div>
		</div>';
	}
	$smarty->assign('is_dir_sale', $is_dir_sale);
	$smarty->assign('is_dir_project', $is_dir_project);
	// Return
	// $html = $core->build('_ajax.booking.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsProfile = new Profile();
	$uid = Input::post('uid', $clsISO->getUniqid());
	$smarty->assign('uid', $uid);
	#
	$list_preloaders = array();
	for($i=0; $i<20; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('_ajax.booking.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_report_booking(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$profile_id;
	$clsBooking = new Booking();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	
	$holderG = Input::post('holderG', 'staff');
	$status_id = (int) Input::post('status_id', 0);
	$role_id = (int) $oneProfile['role_id'];
	$team_id = (int) $oneProfile['team_id'];
	$department_id = (int) $oneProfile['department_id'];
	$query_string = Input::post('query_string');
	$sql_query = !empty($query_string) ? $clsISO->base64url_decode($query_string) : "";
	// $clsISO->print_pre($sql_query); die();
	if($status_id > 0) $sql_query.= " AND `status_id`='{$status_id}'";
	#- Pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 20);
	$total_record = $clsBooking->countItem($sql_query);
	$total_page = @ceil($total_record / $per_page);
	$offset = ($current_page - 1) * $per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
	$field = "{$clsBooking->pkey},`booking_code`,`booking_date`,`status_id`,`project_id`,`block_id`,`amount`,`more_information`";
	$list_bookings = $clsBooking->getAll($sql_query." ORDER BY `reg_date` DESC".$limitCond, $field);
	if(!empty($list_bookings)){
		$arr_project_cached = $arr_property_cached = $arr_profile_cached = $arr_status_cached = array();
		$list_projects = $clsProject->getAll("`is_trash`=0", "{$clsProject->pkey},`code`,`title`");
		if(!empty($list_projects)){
			foreach($list_projects as $key => $val){
				$arr_project_cached[$val[$clsProject->pkey]] = $val['code'];
			}
			unset($list_projects);
		}
		$p_field = "{$clsProperty->pkey},`title`,`image`";
		$tmp = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_BOOKING_STATUS'", $p_field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$image = $val['image'];
				$status_id = $val[$clsProperty->pkey];
				$arr_status_cached[$status_id] = '<span class="badge '.$image.' text-upper">'.$val['title'].'</span>';
			}
			unset($tmp);
		}
		foreach($list_bookings as $key => $val){
			$project_id = (int) $val['project_id'];
			$block_id = (int) $val['block_id'];
			$status_id = (int) $val['status_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$staff_id = (int) $core->get_field($more_information, "staff_id", 0);
			if($staff_id > 0 && isset($arr_profile_cached[$staff_id])){
				$oStaff = $arr_profile_cached[$staff_id];
			} else {
				$field = "`t1`.`code`,`t1`.`full_name`,`t1`.`first_name`,`t1`.`last_name`,`t2`.`title` as `department_name`";
				$oStaff = $dbconn->getRow("select {$field} FROM {$clsProfile->tbl} AS `t1` 
				INNER JOIN {$clsProperty->tbl} AS `t2` ON t1.department_id=t2.property_id 
				WHERE `t1`.profile_id='{$staff_id}'");
				$arr_profile_cached[$staff_id] = $oStaff;
			}
			// $department_name = $oProfile['department_name'];
			$list_bookings[$key]['oStaff'] = $oStaff;
			$block_name = $status_name = "";
			if($block_id > 0){
				if(isset($arr_property_cached[$block_id])){
					$block_name = $arr_property_cached[$block_id];
				} else {
					$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
					$block_name = $arr_property_cached[$block_id];
				}
			}
			if($status_id > 0 && isset($arr_status_cached[$status_id])){
				$status_name = $arr_status_cached[$status_id];
			}
			$list_bookings[$key]['status_name'] = $status_name;
			$list_bookings[$key]['bedroom_name'] = $bedroom_name;
			if(!empty($block_name)){
				$list_bookings[$key]['project_name'] = sprintf('%s / %s', $arr_project_cached[$project_id], $block_name);
			} else {
				$list_bookings[$key]['project_name'] = $arr_project_cached[$project_id];
			}
			$list_bookings[$key]['more_information'] = $more_information;
		}
	}
	$smarty->assign('list_bookings', $list_bookings);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('_ajax.booking.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'per_page' => $per_page,
	)); die();
}
function default_rebuild_loyalty_transaction_points(){
	// Tính lại điểm Loyalty GIAO DỊCH 2026 theo MA TRẬN vị trí + cascade GĐKD/GĐ Vùng theo cây phòng ban.
	// Bước 1: xóa log cơ chế giá-trị cũ (exclusive_score/cross_score). Bước 2: chấm lại từng GD. Bước 3: đồng bộ total từ log.
	// Idempotent: engine khóa theo for_id=billing_id — chạy lại không cộng đúp.
	global $core, $dbconn, $clsISO, $profile_id;
	if(!in_array($profile_id, _PROFILE_SUPPER_ID)){
		die('Access denied');
	}
	@set_time_limit(0);
	@ignore_user_abort(true);
	$clsBilling = new Billing();
	$clsFPoint = new FPoint();
	$touched = array();
	// 0) Guard cấu hình: thang cascade phải CÓ ĐIỂM trên server trước khi ghi thật
	// (log cá nhân đã chèn thì lần chạy lại KHÔNG tự bù cascade thiếu — khóa idempotent nằm ở log cá nhân).
	$start_2026 = strtotime('2026-01-01 00:00:00');
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$fpoint_configs = array();
	$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$fpoint_configs = $decoder->decodeFile($cachedFile);
	}
	$_bt_rows = $dbconn->GetAll("SELECT DISTINCT `billing_type` FROM `{$clsBilling->tbl}` WHERE `is_trash`=0 AND `is_cancel`=0 AND `deposit_date`>='{$start_2026}'");
	$_dept_sum = 0;
	$_region_sum = 0;
	echo '<p>Thang phụ trách đọc từ fpoint.json: ';
	foreach($_bt_rows as $_btr){
		$_bt = (int) $_btr['billing_type'];
		$_ds = isset($fpoint_configs['dept_' . $_bt]['score']) ? (float) $fpoint_configs['dept_' . $_bt]['score'] : 0;
		$_rs = isset($fpoint_configs['region_director_' . $_bt]['score']) ? (float) $fpoint_configs['region_director_' . $_bt]['score'] : 0;
		$_dept_sum += $_ds;
		$_region_sum += $_rs;
		echo 'loại ' . $_bt . ': GĐKD=' . ($_ds * 1) . ' / GĐV=' . ($_rs * 1) . ' &nbsp; ';
	}
	echo '</p>';
	if($_dept_sum <= 0 || $_region_sum <= 0){
		die('DỪNG (chưa ghi gì): thang phụ trách (Đội ngũ bán / Đội ngũ khu vực) đang TRỐNG trong cache/json/fpoint.json trên server — vào admin mod=setting&act=fpoint điền điểm + Lưu rồi chạy lại.');
	}
	// 1) Gỡ log cơ chế giá trị (nếu còn)
	$_val_cond = "`ns_type`='Lpoint' and `act` in ('exclusive_score','cross_score')";
	$old_logs = $clsFPoint->getAll($_val_cond, "`profile_id`");
	$deleted = 0;
	if(!empty($old_logs)){
		foreach($old_logs as $row){
			$touched[(int) $row['profile_id']] = 1;
		}
		$deleted = count($old_logs);
		$clsFPoint->deleteByCond($_val_cond);
	}
	// 2) Chấm lại GD 2026 theo ma trận + cascade
	$field = "{$clsBilling->pkey},`billing_code`,`staff_id`,`billing_type`,`deposit_date`,`stock_code`,`project_id`,`more_information`,`is_cancel`,`is_trash`";
	$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `deposit_date`>='{$start_2026}' order by `deposit_date` ASC", $field);
	$total = !empty($list_billings) ? count($list_billings) : 0;
	$personal = $dept = $region = $manage = 0;
	$skip_rows = array();
	if(!empty($list_billings)){
		foreach($list_billings as $oBilling){
			$billing_id = (int) $oBilling[$clsBilling->pkey];
			$ret = $clsFPoint->insert_billing_LPoint_role($billing_id, $oBilling);
			if(!empty($ret)){
				$personal += (int) $ret['personal'];
				$dept += (int) $ret['dept'];
				$region += (int) $ret['region'];
				$manage += (int) $ret['manage'];
				$touched[(int) $oBilling['staff_id']] = 1;
			} else {
				$skip_rows[] = $billing_id . ' - ' . $oBilling['billing_code'];
			}
		}
	}
	// 3) Đồng bộ total từ log cho NV bị đụng + mọi GĐ nhận cascade (quét lại theo act phụ trách cho chắc)
	$boss_logs = $clsFPoint->getAll("`ns_type`='Lpoint' and (`act` like 'dept\_%' or `act` like 'region\_director\_%' or `act` like 'manage\_%') and `reg_date`>='{$start_2026}'", "`profile_id`");
	if(!empty($boss_logs)){
		foreach($boss_logs as $row){
			$touched[(int) $row['profile_id']] = 1;
		}
	}
	$synced = 0;
	foreach(array_keys($touched) as $pid){
		$sync = $clsFPoint->recompute_total_from_logs($pid);
		if($sync['changed'] == 1){
			$synced++;
		}
	}
	echo '<h3>Tính lại điểm Loyalty giao dịch 2026 (ma trận vị trí + cascade GĐKD/GĐ Vùng)</h3>';
	echo '<p>Gỡ log giá-trị cũ: ' . $deleted . ' | GD quét: ' . $total . ' | Cá nhân: ' . $personal . ' | Phụ trách GĐKD: ' . $dept . ' | Phụ trách GĐ Vùng: ' . $region . ' | Phụ trách GĐ Dự Án: ' . $manage . ' | NV đồng bộ total: ' . $synced . '</p>';
	if(!empty($skip_rows)){
		echo '<p>Bỏ qua (đã chấm / người bán Partner-BO / vị trí chưa cấu hình điểm):</p>';
		$clsISO->print_pre($skip_rows);
	}
	die();
}
function default_rebuild_loyalty_seniority_points(){
	// Chạy bù điểm thâm niên 2026 (cron ngừng từ 12/2025): quét mốc 28 từng tháng, tự dừng trước tháng chưa tới.
	// Idempotent: engine khóa 1 log thâm niên/người/tháng — chạy lại không cộng đúp.
	global $core, $dbconn, $clsISO, $profile_id;
	if(!in_array($profile_id, _PROFILE_SUPPER_ID)){
		die('Access denied');
	}
	@set_time_limit(0);
	@ignore_user_abort(true);
	$clsProfile = new Profile();
	$clsFPoint = new FPoint();
	$field = "{$clsProfile->pkey},`role_id`,`level_id`,`department_id`,`start_date`,`full_name`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`=1 and `status_id`='"._STATUS_STAFF_ON_ID."' and `department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'", $field);
	$total_staffs = !empty($list_staffs) ? count($list_staffs) : 0;
	$now = time();
	$report = array();
	for($month = 1; $month <= 12; $month++){
		$last_day = strtotime(sprintf('28-%s-2026', $month));
		if($last_day > $now){
			break; // mốc tháng này chưa tới
		}
		$done = 0;
		if(!empty($list_staffs)){
			foreach($list_staffs as $val){
				if($clsFPoint->insert_LPoint_seniority($val[$clsProfile->pkey], $val, $last_day)){
					$done++;
				}
			}
		}
		$report[sprintf('T%s/2026', $month)] = $done . ' / ' . $total_staffs . ' NV được cộng';
	}
	echo '<h3>Chạy bù điểm thâm niên 2026 (Sale theo vị trí, BO theo bậc)</h3>';
	$clsISO->print_pre($report);
	die();
}
function default_rebuild_loyalty_totals(){
	// Đồng bộ total_Lpoint = SUM log (điểm trừ '_minus' là âm, bỏ is_cancel) cho TOÀN BỘ nhân viên ĐANG LÀM VIỆC.
	// Idempotent — chạy lại lúc nào cũng được; NV nghỉ việc không bị đụng.
	global $core, $dbconn, $clsISO, $profile_id;
	if(!in_array($profile_id, _PROFILE_SUPPER_ID)){
		die('Access denied');
	}
	@set_time_limit(0);
	@ignore_user_abort(true);
	$clsProfile = new Profile();
	$clsFPoint = new FPoint();
	$field = "{$clsProfile->pkey},`full_name`,`total_Lpoint`";
	$list_staffs = $clsProfile->getAll("`is_trash`=0 and `is_active`=1 and `status_id`='"._STATUS_STAFF_ON_ID."'", $field);
	$total_staffs = !empty($list_staffs) ? count($list_staffs) : 0;
	$changed = array();
	if(!empty($list_staffs)){
		foreach($list_staffs as $val){
			$staff_id = (int) $val[$clsProfile->pkey];
			$sync = $clsFPoint->recompute_total_from_logs($staff_id);
			if($sync['changed'] == 1){
				$_delta = ($sync['new'] - $sync['old']) * 1;
				$changed[] = sprintf('%s - %s: %s -> %s (%s%s)', $staff_id, $val['full_name'], $sync['old'] * 1, $sync['new'] * 1, ($_delta >= 0 ? '+' : ''), $_delta);
			}
		}
	}
	echo '<h3>Đồng bộ total_Lpoint từ log (NV đang làm việc)</h3>';
	echo '<p>Quét: ' . $total_staffs . ' NV | Đã sửa: ' . count($changed) . ' | Không đổi: ' . ($total_staffs - count($changed)) . '</p>';
	if(!empty($changed)){
		$clsISO->print_pre($changed);
	}
	die();
}
function default_rebuild_billing_dep_logs(){
	// Chuẩn hoá thông tin phụ trách trên GIAO DỊCH đặt cọc từ 2026 theo cây phòng ban hiện tại của NGƯỜI BÁN:
	// dep_logs.region_id (id Vùng) + dep_logs.regional_director_id (GĐ Vùng) + dep_logs.head_of_dep_id (GĐ Kinh doanh).
	// project_director_id GIỮ NGUYÊN (nguồn riêng: form billing / GĐ dự án).
	// Không xác định được người bán (GD trống staff / hồ sơ đã xóa) thì BỎ QUA, không ghi 0 đè dữ liệu cũ.
	// Mặc định DRY-RUN chỉ in khác biệt, KHÔNG ghi; thêm &run=1 mới ghi thật.
	global $core, $dbconn, $clsISO, $profile_id;
	if(!in_array($profile_id, _PROFILE_SUPPER_ID)){
		die('Access denied');
	}
	@set_time_limit(0);
	@ignore_user_abort(true);
	$is_run = ((int) Input::get('run') == 1);
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$profile_map = $clsProfile->getProfileCached('all');
	$start_2026 = strtotime('2026-01-01 00:00:00');
	$field = "`{$clsBilling->pkey}`,`staff_id`,`stock_code`,`is_cancel`,`regional_id`";
	$list_billings = $clsBilling->getAll("`is_trash`=0 and `deposit_date`>='{$start_2026}' ORDER BY `deposit_date` ASC, `{$clsBilling->pkey}` ASC", $field);
	$dep_cache = array();
	$changes = array();
	$total = 0;
	$updated = 0;
	$unchanged = 0;
	$skipped = 0;
	$keys = array('region_id', 'regional_director_id', 'head_of_dep_id');
	if(!empty($list_billings)){
		foreach($list_billings as $oneBilling){
			$total++;
			$billing_id = (int) $oneBilling[$clsBilling->pkey];
			$staff_id = (int) $oneBilling['staff_id'];
			$stock_label = $oneBilling['stock_code'] . ((int) $oneBilling['is_cancel'] == 1 ? ' (đã hủy)' : '');
			if($staff_id <= 0){
				$skipped++;
				$changes[] = array('id' => $billing_id, 'code' => $stock_label, 'staff' => '#0', 'note' => 'SKIP: GD không có người bán');
				continue;
			}
			if(!isset($profile_map[$staff_id])){
				$skipped++;
				$changes[] = array('id' => $billing_id, 'code' => $stock_label, 'staff' => '#' . $staff_id, 'note' => 'SKIP: hồ sơ người bán không còn — giữ nguyên dep_logs cũ');
				continue;
			}
			$oneStaff = $profile_map[$staff_id];
			$staff_label = sprintf('%s-%s', $oneStaff['code'], $oneStaff['full_name']);
			$dept_id = (int) $oneStaff['department_id'];
			if(!isset($dep_cache[$dept_id])){
				$dep_cache[$dept_id] = $clsProperty->resolveStaffDepChain($dept_id);
			}
			$resolved = $dep_cache[$dept_id];
			// Đọc more_information NGAY TRƯỚC khi so/ghi (không dùng bản chụp đầu phiên) — tránh đè thao tác ai đó vừa lưu
			$oneRow = $clsBilling->getOne($billing_id, "`more_information`");
			$raw_more = !empty($oneRow) ? $oneRow['more_information'] : '';
			$more_information = $clsISO->to_array_json($raw_more);
			if(!is_array($more_information) || (!empty($raw_more) && $raw_more != '[]' && $raw_more != '{}' && empty($more_information))){
				$skipped++;
				$changes[] = array('id' => $billing_id, 'code' => $stock_label, 'staff' => $staff_label, 'note' => 'SKIP: more_information không decode được');
				continue;
			}
			$dep_logs = $core->get_field($more_information, 'dep_logs', array());
			if(!is_array($dep_logs)){
				$dep_logs = array();
			}
			$note = '';
			$dep_changed = 0;
			foreach($keys as $k){
				$_old = (int) $core->get_field($dep_logs, $k, 0);
				$_new = (int) $resolved[$k];
				if($_old != $_new){
					$note .= ($note != '' ? ' | ' : '') . $k . ': ' . $_old . ' → ' . $_new;
					$dep_logs[$k] = $_new;
					$dep_changed = 1;
				}
			}
			// Cột regional_id (Vùng ID) đồng bộ theo cùng resolver
			$_col_old = (int) $oneBilling['regional_id'];
			$_col_new = (int) $resolved['region_id'];
			$col_changed = ($_col_old != $_col_new) ? 1 : 0;
			if($col_changed == 1){
				$note .= ($note != '' ? ' | ' : '') . 'cột regional_id: ' . $_col_old . ' → ' . $_col_new;
			}
			if($note == ''){
				$unchanged++;
				continue;
			}
			$changes[] = array(
				'id' => $billing_id,
				'code' => $stock_label,
				'staff' => $staff_label,
				'note' => $note
			);
			if($is_run){
				$_upd = array();
				if($dep_changed == 1){
					$more_information['dep_logs'] = $dep_logs;
					$_upd['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
				}
				if($col_changed == 1){
					$_upd['regional_id'] = $_col_new;
				}
				$clsBilling->updateOne($billing_id, $_upd);
			}
			$updated++;
		}
	}
	echo '<h3>Chuẩn hoá dep_logs giao dịch 2026 (Vùng / GĐ Vùng / GĐ Kinh doanh) — ' . ($is_run ? 'ĐÃ GHI' : 'DRY-RUN, thêm &run=1 để ghi thật') . '</h3>';
	echo '<p>Quét: ' . $total . ' GD | Khác biệt: ' . $updated . ' | Không đổi: ' . $unchanged . ' | Bỏ qua: ' . $skipped . '</p>';
	if(!empty($changes)){
		echo '<table border="1" cellpadding="4" cellspacing="0"><tr><th>Billing</th><th>Mã căn</th><th>Người bán</th><th>Thay đổi</th></tr>';
		foreach($changes as $row){
			printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $row['id'], htmlspecialchars($row['code']), htmlspecialchars($row['staff']), htmlspecialchars($row['note']));
		}
		echo '</table>';
	}
	die();
}
?>