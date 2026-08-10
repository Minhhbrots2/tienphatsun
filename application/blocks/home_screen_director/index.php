<?php 
	global $core, $smarty, $dbconn, $clsISO,$profile_id;
	$clsLog = new Log();
	$clsCache = new Cache();
	$clsMember = new Member();
	$clsHelper = new Helper();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsHelper', $clsHelper);
	###
	$current_month = date('n');
	$current_year = date('Y');
	$current_quater = ceil($current_month / 3);
	$prev_year = ($current_year - 1);
	$smarty->assign('prev_year', $prev_year);
	$smarty->assign('current_year', $current_year);
	$smarty->assign('current_quater', $current_quater);
	###
	$list_preloader = array();
	for($i=0; $i<=50; $i++){
		$list_preloader[] = $i;
	}
	$smarty->assign('list_preloader', $list_preloader);
	###
	$list_fund_block = array(
		'paypal' => 'Quỹ đầu kỳ',
		'cc-primary' => 'Tồn quỹ',
		'wallet' => 'Tổng thu',
		'cc-success' => 'Tổng chi',
	);
	$list_opscost_blocks = array(
		'today' => array(
			'title' => 'Chi vận hành hôm nay',
			'bgcolor' => '#410256'
		), 'this_month' => array(
			'title' => 'Chi vận hành tháng này',
			'bgcolor' => '#001131'
		), 'prev_month' => array(
			'title' => 'So với tháng trước',
			'bgcolor' => '#804302'
		), 'avg_3month' => array(
			'title' => 'Trung bình 3 tháng qua',
			'bgcolor' => '#5605dd'
		), 'all_year' => array(
			'title' => 'Chi vận hành cả năm',
			'bgcolor' => '#124c25'
		)
	);
	$smarty->assign('list_fund_block', $list_fund_block);
	$smarty->assign('list_opscost_blocks', $list_opscost_blocks);
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = $list_quaters = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	for($i=1; $i<=$current_quater; $i++){
		$list_quaters[] = $i;
	} 
	###
	$start_date = strtotime(sprintf('01-01-%s', $end_year));
	
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $end_year);
	// var_dump($end_day); die();
	$to_date = strtotime(sprintf('%s-12-%s', $end_day, $end_year));
	$curr_date_type = '_month';
	if($current_month <= 4){
		$curr_date_type = '_quarter';
	}
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$smarty->assign('curr_date_type', $curr_date_type);
	$smarty->assign('list_quaters', $list_quaters);
	##
	$field = "{$clsProperty->pkey},`title`";
	$lstDepartMent = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
		AND (`parent_id`='"._DEPARTMENT_SALE_ID."' OR `property_id`='"._DEPARTMENT_PARTNER."') 
		AND `is_locked`=0 ORDER BY `order_no` ASC", $field);
	$smarty->assign('lstDepartMent', $lstDepartMent);
	##
	$lstProfile = $clsProfile->getProfileCached("active");
	$smarty->assign('lstProfile', $lstProfile);
	###
	$list_bank_accounts = array();
	if($clsCache->has('_bank_account_cached')){
		$list_bank_accounts = $clsCache->get('_bank_account_cached');
	} else {
		$field = "{$clsProperty->pkey},property_code,title,textcolor,bgcolor,image";
		$list_bank_accounts = $clsProperty->getAll("property_type='BANK_ACCOUNT' order by order_no ASC", $field);
		$clsCache->put('_bank_account_cached', $list_bank_accounts);
	}
	$html_bank_account_preloader = '<div class="col text-center">
		<div class="obank bg-primary mb-2">
			<img class="FUND my-2" src="'.URL_IMAGES.'/FUND.png" width="80px" />
			<div class="fs-12 text-white fwd-bold">Tổng tiền</div>
			<hr class="my-1" />
			<div class="text-white fw-bold">
				<div class="animate-bg w-100 h-px-15 mt-2 rounded-pill"></div>
			</div>
		</div>
	</div>';
	if(!empty($list_bank_accounts)){
		foreach($list_bank_accounts as $key => $val){
			$image = $val['image'];
			$html_bank_account_preloader.= '<div class="col text-center">
				<div style="background:'.$val['bgcolor'].'" class="obank mb-2 mb-lg-0">
					<img class="'.$image.' mb-1" src="'.($image=='QTM'?URL_IMAGES.'/QTM.png':'https://api.vietqr.io/img/'.$image.'.png').'" width="80px" />
					<div class="fs-12 text-white text-nowrap fwd-bold">'.$val['title'].'</div>
					<hr class="my-1" />
					<div class="text-white fw-bold">
						<div class="animate-bg w-100 h-px-15 mt-2 rounded-pill"></div>
					</div>
				</div>
			</div>';
		}
	}
	$smarty->assign('html_bank_account_preloader', $html_bank_account_preloader);
?>