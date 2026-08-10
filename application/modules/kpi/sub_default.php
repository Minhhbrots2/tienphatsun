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

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;

	$clsKPI = new KPI();

	###

	$uid = $clsISO->getUniqid();

	$_ss_view_kpi = 'month';

	if(vnSessionExist('_ss_view_kpi')){

		$_ss_view_kpi = vnSessionGetVar('_ss_view_kpi');

	}

	$current_year = date('Y');

	$quarter = $clsISO->get_dates_of_quarter('this', $current_year, 'd-m-Y');

	#

	$list_preloaders = array();

	for($i=0; $i<100; $i++){

		$list_preloaders[] = $i;

	}

	#

	$arr_periods = array(

		'month' => 'Tháng',

		'quarter' => 'Quý',

		'year' => 'Năm'

	);

	$assign_list["uid"] = $uid;

	$assign_list["quarter"] = $quarter;

	$assign_list["arr_periods"] = $arr_periods;

	$assign_list["_ss_view_kpi"] = $_ss_view_kpi;

	$assign_list["list_preloaders"] = $list_preloaders;

	/*=============Title & Description Page==================*/

	$title_page = 'Doanh số bán hàng - ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $title_page;

	$assign_list["description_page"] = $description_page;

}

function default_config(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;

	$clsKPI = new KPI();

	$clsProperty = new Property();

	$list_kpis = $clsKPI->getAll("is_trash=0 order by reg_date DESC");

	$smarty->assign('list_kpis', $list_kpis);

	/*=============Title & Description Page==================*/

	$title_page = 'KPI - ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $title_page;

	$assign_list["description_page"] = $description_page;

	$keyword_page = $description_page;

	$assign_list["keyword_page"] = $keyword_page;

}

function default_open_kpi(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$smarty->assign('clsKPI', $clsKPI);

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('clsProperty', $clsProperty);

	##

	$uid = $clsISO->getUniqid();

	$current_month = date('n');

	$current_year = date('Y');

	$list_months = array();

	for($i=1; $i<=12; $i++){

		$list_months[] = $clsISO->parseNumber($i);

	}

	$smarty->assign('current_month', $current_month);

	$smarty->assign('current_year', $current_year);

	$smarty->assign('list_months', $list_months);

	###

	$field = "{$clsProperty->pkey},title";

	$list_departments = $clsProperty->getAll("`property_type`='_DEPARTMENT' 

		AND `parent_id`="._DEPARTMENT_SALE_ID." ORDER BY `order_no` ASC", $field);

	if(!empty($list_departments)){

		foreach($list_departments as $key => $val){

			$department_id = $val[$clsProperty->pkey];

			if($department_id == _DEPARTMENT_SALE_ID){

				$list_teams = array();

			} else {

				$list_teams = $clsProperty->getAll("`property_type`='_DEPARTMENT' 

					and `parent_id`='{$department_id}' order by `order_no` ASC", $field);

			}

			$list_departments[$key]['teams'] = $list_teams;

		}

	}

	// $clsISO->print_pre($list_departments); die();

	$list_departments[] = array(

		$clsProperty->pkey => 'OTHER',

		'title' => 'Nhóm tổng hợp',

		'teams' => array()

	);

	$list_departments[] = array(

		'teams' => array(),

		$clsProperty->pkey => 'PARTNER',

		'title' => $clsProfile->getFullName(_PROFILE_PARTNER_ID),

	);

	$smarty->assign('list_departments', $list_departments);

	###

	$kpi_id = (int) Input::post('kpi_id', 0);

	if($kpi_id > 0){

		$titlePage = 'Thiết lập chỉ tiêu';

		// $titlePage = 'Cập nhật KPI';

		$oneKPI = $clsKPI->getOne($kpi_id);

		$period = $oneKPI['period'];

		$month_period = $oneKPI['month_period'];

		if($period=='MONTH'){

			$month_period = json_decode(html_entity_decode($month_period), true);

			$oneKPI['month_period'] = $month_period;

			$oneKPI['total_month'] = count($month_period);

		}

		$configs = $oneKPI['configs'];

		$configs_arrs = !empty($configs) 

			? json_decode(html_entity_decode($configs), true) : array();

	} else {

		$titlePage = "Thêm mới chỉ tiêu";

		/** Clear Temp*/

		$clsKPI->deleteByCond("user_id='{$profile_id}' and is_trash=1");

		// Add New

		$kpi_id = $clsKPI->getMaxId();

		$clsKPI->insert(array(

			'kpi_id' => $kpi_id,

			'period' => 'ALLMONTH',

			'year_period' => $current_year,

			'reg_date' => time(),

			'upd_date' => time(),

			'user_id' => $profile_id,

			'user_id_update' => $profile_id,

			'is_team' => 0,

			'is_trash' => 1

		));

		$configs_arrs = array();

		$oneKPI = $clsKPI->getOne($kpi_id);

		if(!empty($list_departments)){

			foreach($list_departments as $key => $val){

				$department_id = $val[$clsProperty->pkey];

				$configs_arrs[$department_id] = array(

					'total_sale' => 1,

					'status' => 1

				);

			}

		}

	}

	$smarty->assign('uid', $uid);

	$smarty->assign('kpi_id', $kpi_id);

	$smarty->assign('oneKPI', $oneKPI);

	$smarty->assign('clsKPI', $clsKPI);

	$smarty->assign('configs_arrs', $configs_arrs);

	$smarty->assign('titlePage', $titlePage);

	// Return

	$html = $core->build('_ajax.open_kpi.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html,

		'kpi_id' => $kpi_id 

	)); die();

}

function default_open_target(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	##

	$uid = $clsISO->getUniqid();

	$smarty->assign('uid', $uid);

	$smarty->assign('clsKPI', $clsKPI);

	$smarty->assign('clsProfile', $clsProfile);

	$smarty->assign('clsProperty', $clsProperty);

	#

	$kpi_id = (int) Input::post('kpi_id', 0);

	$kpi_target_id = Input::post('kpi_target_id', "");

	$smarty->assign('kpi_id', $kpi_id);

	$smarty->assign('kpi_target_id', $kpi_target_id);

	$target_info = $clsKPI->getOneField('target_info', $kpi_id);

	$target_info = $clsISO->to_array_json($target_info);

	#

	$action = "_add";

	$oneTarget = array('target_field'=>"");

	$titlePage = 'Thêm mục tiêu';

	if(!empty($target_info) && !empty($kpi_target_id) && array_key_exists($kpi_target_id, $target_info)){

		$action = "_edit";

		$titlePage = 'Sửa mục tiêu';

		$oneTarget = $target_info[$kpi_target_id];

	}

	$smarty->assign('action', $action);

	$smarty->assign('titlePage', $titlePage);

	$smarty->assign('oneTarget', $oneTarget);

	// Return

	$html = $core->build('_ajax.open_target.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_delete_target(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	

	$kpi_id = (int) Input::post('kpi_id', 0);

	$kpi_target_id = Input::post('kpi_target_id', "");

	$target_info = $clsKPI->getOneField('target_info', $kpi_id);

	$target_info = !empty($target_info) 

		? json_decode(html_entity_decode($target_info), true) 

		: array();

	$msg = "_error";

	if(!empty($target_info) && !empty($kpi_target_id) 

		&& array_key_exists($kpi_target_id, $target_info)){

		unset($target_info[$kpi_target_id]);

		if($clsKPI->updateOne($kpi_id, array(

			'target_info' => json_encode($target_info, JSON_UNESCAPED_UNICODE)

		))){

			$msg = '_success';

		}

	}

	// Return

	echo $msg; die();

}

function default_pop_save_target(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	

	$kpi_id = (int) Input::post('kpi_id', 0);

	$kpi_target_id = Input::post("kpi_target_id");

	if($kpi_id == 0){

		echo '_error';

		die();

	} else {

		$msg= "_error";

		$target_info = $clsKPI->getOneField('target_info', $kpi_id);

		$target_info = !empty($target_info) 

			? json_decode(html_entity_decode($target_info), true) 

			: array();

		$target_field = Input::post('target_field');

		$target_unit = $clsKPI->getUnit($target_field);

		if(!empty($kpi_target_id)){

			$_validated = 1;

			if(!empty($target_info)){

				foreach($target_info as $key => $val){

					if($key != $kpi_target_id && $val['target_field'] == $target_field){

						$_validated = 0;

						break;

					}

				}

			}

			if($_validated==1){

				$target_info[$kpi_target_id]['title'] = Input::post('title');

				$target_info[$kpi_target_id]['intro'] = Input::post('intro');

				$target_info[$kpi_target_id]['position'] = Input::post('position');

				$target_info[$kpi_target_id]['target_field'] = $target_field;

				$target_info[$kpi_target_id]['target_unit'] = $target_unit;

				$target_info[$kpi_target_id]['user_id_update'] = $profile_id;

				$target_info[$kpi_target_id]['upd_date'] = time();

				if($clsKPI->updateOne($kpi_id, array(

					'target_info' => json_encode($target_info, JSON_UNESCAPED_UNICODE)

				))){

					$msg = '_success';

				}

			} else {

				$msg = "_invalid";

			}

		} else {

			$_validated = 1;

			if(!empty($target_info)){

				foreach($target_info as $key => $val){

					if($val['target_field'] == $target_field){

						$_validated = 0;

						break;

					}

				}

			}

			if($_validated==1){

				$kpi_target_id = $clsISO->getUniqid();

				if(!empty($target_info)){

					$target_percent = 0;

				} else {

					$target_percent = 100;

				}

				$target_info[$kpi_target_id] = array(

					'title' => Input::post('title'),

					'intro' => Input::post('intro'),

					'position' => Input::post('position'),

					'target_number' => 0,

					'target_unit' => $target_unit, 

					'target_field' => $target_field,

					'target_percent' => $target_percent,

					'user_id' => $profile_id,

					'user_id_update' => $profile_id,

					'reg_date' => time(),

					'upd_date' => time()

				);

				if($clsKPI->updateOne($kpi_id, array(

					'target_info' => json_encode($target_info, JSON_UNESCAPED_UNICODE)

				))){

					$msg = '_success';

				}

			} else {

				$msg = "_invalid";

			}

		}

		// Return

		echo $msg; die();

	}

}

function default_load_kpi_target(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	

	$kpi_id = Input::post('kpi_id', 0);

	$target_info = $clsKPI->getOneField('target_info', $kpi_id);

	$list_targets = !empty($target_info) 

		? json_decode(html_entity_decode($target_info), true) 

		: array();

		

	$html = '';

	if(!empty($list_targets)){ $ii= 0;

		foreach($list_targets as $key => $val){

			$html .= '<tr class="trTarget trTarget_'.$kpi_id.'">

				<td ><strong>'.$val['title'].'</strong>

					<div class="text-muted fs-12">'.$val['intro'].'</div>

				</td>

				<td class="text-left">

					<input type="text" class="form-control numberonly" onChange="$Core.kpi.autosave_field(this, event)" p_field="target_number" p_id="'.$key.'" kpi_id="'.$kpi_id.'" value="'.$val['target_number'].'" maxlength="255" />

				</td>

				<td class="text-left">

					<input type="text" class="form-control" onChange="$Core.kpi.autosave_field(this, event)" p_field="target_unit" p_id="'.$key.'" kpi_id="'.$kpi_id.'" value="'.$val['target_unit'].'"  maxlength="255" />

				</td>

				<td class="text-left">

					<div class="input-group input-group-merge">

						<input type="text" class="form-control numberonly" onChange="$Core.kpi.autosave_field(this, event)" p_field="target_percent" p_id="'.$key.'" kpi_id="'.$kpi_id.'" value="'.$val['target_percent'].'" maxlength="255" />

						<span class="input-group-text">%</span>

					</div>

				</td>

				<td  class="text-center">

					<div class="btn-group">

						<button kpi_target_id="'.$key.'" kpi_id="'.$kpi_id.'" onClick="$Core.kpi.open_target(this, event)" class="btn btn-icon btn-default">'.$core->makeIcon('pencil').'</button>

						<button onClick="$Core.kpi.delete_target(this, event)" kpi_target_id="'.$key.'" kpi_id="'.$kpi_id.'" class="btn btn-icon btn-default">'.$core->makeIcon('trash').'</button>

					</div>

				</td>

			</tr>';

			++$ii;

		}

	}

	// Return

	echo json_encode(array(

		'html' => $html

	)); die();

}

function default_autosave_field(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	

	$kpi_id = Input::post('kpi_id', 0);

	$p_id = Input::post('p_id', "");

	$p_field = Input::post('p_field', "");

	$p_value = Input::post('p_value', "");

	$target_info = $clsKPI->getOneField('target_info', $kpi_id);

	$target_info = !empty($target_info) 

		? json_decode(html_entity_decode($target_info), true) 

		: array();

	

	$msg = "_error";

	if(!empty($target_info) && !empty($p_id) 

		&& array_key_exists($p_id, $target_info)){

		$target_info[$p_id][$p_field] = $p_value;

		if($clsKPI->updateOne($kpi_id, array(

			'target_info' => json_encode($target_info, JSON_UNESCAPED_UNICODE)

		))){

			$msg = '_success';

		}

	}

	// Return

	echo $msg; die();

}

function default_pop_save_kpi(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsKPI = new KPI();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	#

	$msg = "_error";

	$kpi_id = (int) Input::post('kpi_id', 0);

	$period = Input::post('period', 'ALLMONTH');

	$year_period = Input::post('year_period');

	$configs = Input::post('configs');

	#

	$more = array();

	if($period=='MONTH'){

		$month_period = Input::post('month_period');

		$more['month_period'] = json_encode($month_period, JSON_UNESCAPED_UNICODE);

		$more['month_slash']= $clsISO->makeSlashListFromArrayRoot($month_period);

	}

	//$clsKPI->setDebug(true);

	if($clsKPI->updateOne($kpi_id, array_merge($more, array(

		'is_trash' => 0,

		'period' => $period,

		'year_period' => $year_period,

		'title' => Input::post('title'),

		//'intro' => Input::post('intro'),

		'is_team' => Input::post('is_team', 0),

		'configs' => json_encode($configs, JSON_UNESCAPED_UNICODE),

		'upd_date' => time()

	)))){

		$msg = "_success";

	}

	// Return

	echo $msg; die();

}

function default_set_month(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;

	global $profile_id, $oneProfile;

	

	$_ss_view_kpi = 'month';

	if(vnSessionExist('_ss_view_kpi')){

		$_ss_view_kpi = vnSessionGetVar('_ss_view_kpi');

	}

	$tp = Input::post('tp', 'next');

	if($_ss_view_kpi == 'month'){

		$month = Input::post('month', "");

		$date = sprintf('01/%s', $month);

		$int_date = $clsISO->convertTextToTime($date);

		if($tp=='next') $date_new = strtotime("+1 month", $int_date);

		if($tp=='prev') $date_new = strtotime("-1 month", $int_date);

		// Return

		echo date('m/Y', $date_new); die();

	

	} else if($_ss_view_kpi=='quarter'){

		$q = Input::post('month', "");

		if($tp=='next'){			

//			$quarter = $clsISO->get_dates_of_quarter('next', date('Y'), 'd-m-Y');

			$quarter = $clsISO->getQuarter($q,$tp,'d-m-Y');

		} else {

//			$quarter = $clsISO->get_dates_of_quarter('previous', date('Y'), 'd-m-Y');

			$quarter = $clsISO->getQuarter($q,$tp,'d-m-Y');

		}

//		var_dump($quarter);die;

		// Return

		echo sprintf('Q%s/%s', $quarter['quarter'], $quarter['year']); die();

	} else {

		$year = Input::post('month', date('Y'));

//		$time = strtotime('01-01-'.$year);

//		if($tp=='next') $year_new = strtotime("+1 year", $time);

//		if($tp=='prev') $year_new = strtotime("-1 year", $time);

		if($tp=='next') $year_new = $year + 1;

		if($tp=='prev') $year_new = $year - 1;

		// Return

		echo $year_new; die();

	}

}

function default_set_view(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO;

	global $profile_id, $oneProfile;

	

	$tp = Input::post('tp', 'month');

	vnSessionSetVar('_ss_view_kpi', $tp);

	// Return

	echo (1); die();

}

function default_load_kpi_old(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsKPI = new KPI();

	$clsBilling = new Billing();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	

	$_ss_view_kpi = 'month';

	if(vnSessionExist('_ss_view_kpi')){

		$_ss_view_kpi = vnSessionGetVar('_ss_view_kpi');

	}

	$m = Input::post('month', date('m/Y'));

	$tmp = explode('/', $m);

	$month 	= $tmp[0];

	$year 	= $tmp[1];

	$oneKPI = $clsKPI->getByCond("period='MONTH' and year_period='{$year}' and month_slash like '%|{$month}|%'");

	if(empty($oneKPI)){

		$oneKPI = $clsKPI->getByCond("period='ALLMONTH' and year_period='{$year}'");

	}

	if(!empty($oneKPI)){

		//$clsISO->print_pre($tmp); die();

		$configs = $oneKPI['configs'];

		$configs_arrs = !empty($configs) 

			? json_decode(html_entity_decode($configs), true) : array();

		// $clsISO->print_pre($configs_arrs); die();

		$list_configs = array();

		$total_sales = $total_billings = $total_goals = 0;

		if(!empty($configs_arrs)){

			$list_staffs_notin = array();

			foreach($configs_arrs as $department_id => $val){

				if(isset($val['status']) && $val['status']==1 && !in_array($department_id, ['PARTNER','OTHER'])){

					$list_configs[$department_id] = $val;

					$list_staffs_in = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 

					and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $clsProfile->pkey);

					if(!empty($list_staffs_in)){

						foreach($list_staffs_in as $staff){

							$list_staffs_notin[] = $staff[$clsProfile->pkey];

						}

					}

					$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(*) as `total_billings`";

					$tmp = $dbconn->getRow("select {$field} from {$clsBilling->tbl} as `t1` 

						inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 

						where `t2`.`is_trash`=0 and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' and (`t2`.`department_id`='{$department_id}' or `t2`.`list_department_id` like '%|{$department_id}|%') and `t1`.`is_cancel`=0 and FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$m}'");

					

					if($_ss_view_kpi=='year'){

						

					} else if($_ss_view_kpi=='quarter'){

						

					} else {

						$regis_goals = $clsISO->processSmartNumber($val['total_sale']);

					}

					$total_goals += $regis_goals;

					$total_month_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;

					$total_month_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;

					$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

					$list_configs[$department_id]['department_id'] = $department_id;

					$list_configs[$department_id]['total_billings'] = $total_month_billings;

					$list_configs[$department_id]['total_sales'] = $total_month_sales;

					$list_configs[$department_id]['kpi_percent'] = $kpi_percent;

					$total_sales += $total_month_sales;

					$total_billings += $total_month_billings;

				}

			} 

			// $clsISO->print_pre($list_staffs_notin); die();

			$total_sales_arrs = @array_column($list_configs, 'total_sales');

			@array_multisort($total_sales_arrs, SORT_DESC, $list_configs);

			if(array_key_exists('OTHER', $configs_arrs) && $configs_arrs['OTHER']['status']==1){

				$list_staffs_notin[] = _PROFILE_PARTNER_ID;

				$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(*) as `total_billings`";

				// $dbconn->debug = true;

				$tmp = $dbconn->getRow("select {$field} from {$clsBilling->tbl} as `t1` 

					inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 

					where `t2`.`is_trash`=0 and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' 

					and t1.staff_id not in (".implode(',',$list_staffs_notin).") 

					and FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$m}'");

				if($_ss_view_kpi=='year'){

							

				} else if($_ss_view_kpi=='quarter'){

					

				} else {

					$regis_goals = $clsISO->processSmartNumber($configs_arrs['PARTNER']['total_sale']);

				}

				// $clsISO->print_pre($tmp); die();

				$total_month_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;

				$total_month_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;

				$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

				###

				$list_configs['OTHER']['status'] = 1;

				$list_configs['OTHER']['total_sale'] = $clsISO->formatNumberToEasyRead($regis_goals);

				$list_configs['OTHER']['department_id'] = 'OTHER';

				$list_configs['OTHER']['total_billings'] = $total_month_billings;

				$list_configs['OTHER']['total_sales'] = $total_month_sales;

				$list_configs['OTHER']['kpi_percent'] = $kpi_percent;

				$total_sales += $total_month_sales;

				$total_billings += $total_month_billings;

			}

			if(array_key_exists('PARTNER', $configs_arrs) && $configs_arrs['PARTNER']['status']==1){

				$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(*) as `total_billings`";

				$tmp = $dbconn->getRow("select {$field} from {$clsBilling->tbl} as `t1` 

					inner join {$clsProfile->tbl} as `t2` on `t1`.`staff_id`=`t2`.`profile_id` 

					where `t2`.`is_trash`=0 and `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' and t1.staff_id='"._PROFILE_PARTNER_ID."' and FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$m}'");

				if($_ss_view_kpi=='year'){

							

				} else if($_ss_view_kpi=='quarter'){

					

				} else {

					$regis_goals = $clsISO->processSmartNumber($configs_arrs['PARTNER']['total_sale']);

				}

				$total_month_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;

				$total_month_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;

				$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

				###

				$list_configs['PARTNER']['status'] = 1;

				$list_configs['PARTNER']['total_sale'] = $clsISO->formatNumberToEasyRead($regis_goals);

				$list_configs['PARTNER']['department_id'] = 'PARTNER';

				$list_configs['PARTNER']['total_billings'] = $total_month_billings;

				$list_configs['PARTNER']['total_sales'] = $total_month_sales;

				$list_configs['PARTNER']['kpi_percent'] = $kpi_percent;

				$total_sales += $total_month_sales;

				$total_billings += $total_month_billings;

			}

		}	

		//$clsISO->print_pre($regis_goals); die();

		$smarty->assign('list_configs', $list_configs);

		$smarty->assign('oneKPI', $oneKPI);

		$smarty->assign('clsKPI', $clsKPI);

		$smarty->assign('total_sales', $total_sales);

		$smarty->assign('total_billings', $total_billings);

		$smarty->assign('total_goals', $total_goals);

		// Render

		$html = $core->build('_ajax.view_kpi.tpl');

	} else {

		$html.= '<div class="alert text-center alert-warning" role="alert">

			'.$m.' — chưa được cài đặt chỉ tiêu cho các phòng ban

		</div>';

	}

	// Return

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function findNumberLargest(array $arr, $pos=1){

	//If array is empty then return

	if(empty($arr)) {

		return;

	}

	//sort the array in ascending order

	sort($arr);

	//save the element from the second last position of sorted array

	$numberLargest = $arr[sizeof($arr)- $pos];

	//return second-largest number

	return $numberLargest;

}

function default_load_kpi(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsKPI = new KPI();

	$clsBilling = new Billing();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	#

	$_ss_view_kpi = 'month';

	if(vnSessionExist('_ss_view_kpi')){

		$_ss_view_kpi = vnSessionGetVar('_ss_view_kpi');

	}

	$billing_type = Input::post('billing_type', "ALL");

	$is_team_enabled = (int) Input::post('is_team_enabled', 0);

	if($_ss_view_kpi == 'quarter'){

		$m = Input::post('month');

		$q = $clsISO->getQuarter($m,"","n");

		$start_month = $q['start'];

		$end_month = $q['end'];

		$tmp = explode("/",$m);

		$year = $tmp[1];

		$year = (!empty($year))?$year:date("Y");

		$quarter = str_replace("Q","",$tmp[0]);

		$sql_time = " AND (";

		$first = 1;

		for($i = $start_month; $i <= $end_month; $i++){

			$date_my = sprintf('%s/%s', $clsISO->parseNumber($i), $year);

			$sql_time.= (($first == 0)?" OR ":"")." FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$date_my}' ";

			$first = 0;

		}

		$sql_time.= ")";

	}else if($_ss_view_kpi == 'year'){

		$m = Input::post('month', date('Y'));

		$year = $m;

		$sql_time = " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%Y')='{$year}' ";

		// $sql_time.= " AND `t1`.`deposit_date`<'".strtotime('30-06-2025 23:59:59')."'";

	}else{

		$m = Input::post('month', date('m/Y'));

		$tmp = explode('/', $m);

		$month 	= $tmp[0];

		$year 	= $tmp[1];

		$oneKPI = $clsKPI->getByCond("`period`='MONTH' and `year_period`='{$year}' and `month_slash` like '%|{$month}|%'");

		$sql_time = " AND FROM_UNIXTIME(`t1`.`deposit_date`,'%m/%Y')='{$m}'";

	}

	$assign_list['m'] = $m;

	$assign_list['_ss_view_kpi'] = $_ss_view_kpi;

	

	if($billing_type == 'CAO_TANG'){

		$billing_type_CAO_TANG = array_merge(array(_BILLING_TYPE_CT_ID, _BILLING_TYPE_MIK_ID), _BILLING_TYPE_MASTERI_GROUP_ID);

		$sql_time.= " AND (`t1`.`billing_type` in (".implode(',', $billing_type_CAO_TANG)."))";

	} else if($billing_type == 'THAP_TANG'){

		$sql_time.= " AND (`t1`.`billing_type`='"._BILLING_TYPE_TT_ID."')";

	}

	if(empty($oneKPI)){

		$oneKPI = $clsKPI->getByCond("`period`='ALLMONTH' and `year_period`='{$year}'");

	}

	##

	$top_1_score = $top_2_score = $top_3_score = 0;

	if(!empty($oneKPI)){

		$configs = $oneKPI['configs'];

		$configs_arrs = $clsISO->to_array_json($configs);

		$list_configs = array();

		$total_sales = $total_billings = $total_goals = 0;

		if(!empty($configs_arrs)){

			$arr_billings_notin = array();

			foreach($configs_arrs as $department_id => $val){

				if(isset($val['status']) && $val['status']==1 && !in_array($department_id, ['PARTNER','OTHER'])){

					$list_configs[$department_id] = $val;

					$is_team_based = 0;

					if($is_team_enabled == 1){

						$list_teams = array(); 

						$total_team_sales = $total_team_billings = $total_team_goals = $total_team_kpis = 0;

						if(isset($val['teams']) && !empty($val['teams'])){

							$is_team_based = 1;

							foreach($val['teams'] as $team_id => $oval){

								$oTeam = $clsProperty->getOne($team_id, "`property_code`,`title`");

								$list_teams[$team_id] = $oval;

								$list_teams[$team_id]['team_id'] = $team_id;

								$list_teams[$team_id]['team_name'] = $oTeam['title'];

								$regis_goals = (!empty($oval['total_sale'])) 

									? $clsISO->processSmartNumber($oval['total_sale']):0;

								if($_ss_view_kpi=='month'){

									$regis_goals = $regis_goals/12;

								} else if($_ss_view_kpi=='quarter'){

									$regis_goals = $regis_goals/4;

								}

								$total_month_sales = $total_month_billings = 0;

								$list_teams[$team_id]['total_sale'] = $clsISO->shortNumberV2($regis_goals,0);

								$field = "`t1`.`{$clsBilling->pkey}`,`t1`.`totalgrand`";

								$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 

								INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` 

								WHERE `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 AND `t1`.`department_id`='{$department_id}' 

								AND `t2`.`team_id`='{$team_id}' AND `t2`.`department_id`='{$department_id}'".$sql_time);

								if(!empty($tmp)){

									$total_month_billings = count($tmp);

									foreach($tmp as $okey => $oval){

										$arr_billings_notin[] = $oval[$clsBilling->pkey];

										$total_month_sales += $clsISO->processSmartNumber($oval['totalgrand']);

									}

									unset($tmp);

								}

								$list_teams[$team_id]['total_billings'] = $total_month_billings;

								$list_teams[$team_id]['total_sales'] = $total_month_sales;

								$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

								$list_teams[$team_id]['kpi_percent'] = $kpi_percent;

								$total_team_sales += $total_month_sales;

								$total_team_billings += $total_month_billings;

								$total_team_goals += $regis_goals;

								$total_team_kpis += $kpi_percent;

							}

						}

					}

					$list_configs[$department_id]['list_teams'] = $list_teams;

					$field = "`t1`.`{$clsBilling->pkey}`,`t1`.`totalgrand`";

					$total_month_sales = $total_month_billings = 0;

					if($is_team_based == 1){

						$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 

						INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` 

						WHERE `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 AND `t2`.`team_id`=0 AND `t2`.`department_id`='{$department_id}' 

						AND `t1`.`billing_type`<>'"._BILLING_TYPE_SOP_ID."' AND `t1`.`department_id`='{$department_id}'".$sql_time);

					} else {

						$sql_query = "";

						if($department_id == 8975){

							$sql_query = " AND `t1`.`deposit_date`>='".strtotime('30-07-2025')."'";

						}

						$tmp = $dbconn->getAll("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 

						WHERE `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 AND `t1`.`department_id`='{$department_id}' 

						AND `t1`.`billing_type`<>'"._BILLING_TYPE_SOP_ID."'".$sql_query.$sql_time);

					}

					if(!empty($tmp)){

						$total_month_billings = count($tmp);

						foreach($tmp as $okey => $oval){

							$arr_billings_notin[] = $oval[$clsBilling->pkey];

							$total_month_sales += $clsISO->processSmartNumber($oval['totalgrand']);

						}

						unset($tmp);

					}

					$regis_goals = (!empty($val['total_sale'])) 

						? $clsISO->processSmartNumber($val['total_sale']) : 0;

					if($_ss_view_kpi=='month'){

						$regis_goals = $regis_goals/12;

					} else if($_ss_view_kpi=='quarter'){

						$regis_goals = $regis_goals/4;

					}

					$regis_goals += $total_team_goals;

					$total_goals += $regis_goals;

					$list_configs[$department_id]['total_sale'] = $clsISO->shortNumberV2($regis_goals,0);

					if($is_team == 1){

						$total_month_sales += $total_team_sales;

						$total_month_billings += $total_team_billings;

					} 

					$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

					$list_configs[$department_id]['department_id'] = $department_id;

					$list_configs[$department_id]['total_sales'] = $total_month_sales;

					$list_configs[$department_id]['total_billings'] = $total_month_billings;

					$list_configs[$department_id]['kpi_percent'] = $kpi_percent;

					$total_sales += $total_month_sales;

					$total_billings += $total_month_billings;

					unset($tmp, $regis_goals,$kpi_percent);

				}

			}

			$total_sales_arrs = @array_column($list_configs, 'total_sales');

			@array_multisort($total_sales_arrs, SORT_DESC, $list_configs);

			$top_1_score = findNumberLargest($total_sales_arrs,1);

			$top_2_score = findNumberLargest($total_sales_arrs,2);

			$top_3_score = findNumberLargest($total_sales_arrs,3);

			if(array_key_exists('OTHER', $configs_arrs) && $configs_arrs['OTHER']['status']==1){

				$field = "sum(`t1`.`totalgrand`) as `total_sales`,count(1) as `total_billings`";

				if(!empty($arr_billings_notin)){

					$tmp = $dbconn->getRow("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` WHERE `t2`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 AND `t1`.`staff_id`<>'"._PROFILE_PARTNER_ID."' AND `t1`.`{$clsBilling->pkey}` NOT IN (".implode(',',$arr_billings_notin).") AND `t1`.`billing_type`<>'"._BILLING_TYPE_SOP_ID."'".$sql_time);

				} else {

					$tmp = $dbconn->getRow("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` WHERE `t2`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 AND `t1`.`staff_id`<>'"._PROFILE_PARTNER_ID."' AND `t1`.`billing_type`<>'"._BILLING_TYPE_SOP_ID."'".$sql_time);

				}

				$regis_goals = $clsISO->processSmartNumber($configs_arrs['OTHER']['total_sale']);

				if($_ss_view_kpi=='month'){

					$regis_goals = $regis_goals/12;

					$list_configs[$department_id]['total_sale'] = $clsISO->priceFormat($regis_goals);

				} else if($_ss_view_kpi=='quarter'){

					$regis_goals = $regis_goals/4;

					$list_configs[$department_id]['total_sale'] = $clsISO->priceFormat($regis_goals);

				}

				$total_month_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;

				$total_month_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;

				$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

				###

				$list_configs['OTHER']['status'] = 1;

				$list_configs['OTHER']['total_sale'] = $clsISO->shortNumberV2($regis_goals,0);

				$list_configs['OTHER']['department_id'] = 'OTHER';

				$list_configs['OTHER']['total_billings'] = $total_month_billings;

				$list_configs['OTHER']['total_sales'] = $total_month_sales;

				$list_configs['OTHER']['kpi_percent'] = $kpi_percent;

				$total_sales += $total_month_sales;

				$total_billings += $total_month_billings;

			}

			if(array_key_exists('PARTNER', $configs_arrs) && $configs_arrs['PARTNER']['status']==1){

				$field = "sum(`t1`.`totalgrand`) AS `total_sales`,count(1) AS `total_billings`";

				$tmp = $dbconn->getRow("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 

					INNER JOIN {$clsProfile->tbl} AS `t2` ON `t1`.`staff_id`=`t2`.`profile_id` 

					WHERE `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0 AND `t1`.`is_alliance`=0 

					AND `t1`.`staff_id`='"._PROFILE_PARTNER_ID."'".$sql_time);

				$regis_goals = $clsISO->processSmartNumber($configs_arrs['PARTNER']['total_sale']);

				if($_ss_view_kpi=='month'){

					$regis_goals = ($regis_goals/12)/2;

					$list_configs[$department_id]['total_sale'] = $clsISO->priceFormat($regis_goals);

				} else if($_ss_view_kpi=='quarter'){

					$regis_goals = ($regis_goals/4)/2;

					$list_configs[$department_id]['total_sale'] = $clsISO->priceFormat($regis_goals);

				}

				$total_month_sales = !empty($tmp['total_sales']) ? $tmp['total_sales'] : 0;

				$total_month_billings = !empty($tmp['total_billings']) ? (int) $tmp['total_billings'] : 0;

				$kpi_percent = round($total_month_sales/$regis_goals, 2)*100;

				###

				$list_configs['PARTNER']['status'] = 1;

				$list_configs['PARTNER']['total_sale'] = $clsISO->shortNumberV2($regis_goals,0);

				$list_configs['PARTNER']['department_id'] = 'PARTNER';

				$list_configs['PARTNER']['total_sales'] = $total_month_sales;

				$list_configs['PARTNER']['total_billings'] = $total_month_billings;

				$list_configs['PARTNER']['kpi_percent'] = $kpi_percent;

				$total_sales += $total_month_sales;

				$total_billings += $total_month_billings;

			}

		}	

		// $clsISO->print_pre($list_configs); die();

		$smarty->assign('list_configs', $list_configs);

		$smarty->assign('oneKPI', $oneKPI);

		$smarty->assign('clsKPI', $clsKPI);

		$smarty->assign('total_sales', $total_sales);

		$smarty->assign('total_billings', $total_billings);

		$smarty->assign('total_goals', $total_goals);

		$smarty->assign('top_1_score', $top_1_score);

		$smarty->assign('top_2_score', $top_2_score);

		$smarty->assign('top_3_score', $top_3_score);

		// Render

		$html = $core->build('_ajax.view_kpi.tpl');

	} else {

		$html.= '<div class="alert text-center alert-warning" role="alert">

			'.$m.' — chưa được cài đặt chỉ tiêu cho các phòng ban

		</div>';

	}

	// Return

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}