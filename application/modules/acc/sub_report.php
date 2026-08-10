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
function report_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	$clsCache = new Cache();
	$clsProperty = new Property();
	$clsCashFund = new CashFund();
	$clsCommission = new Commission();
	
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	###
	$list_blocks = array(
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
			'title' => sprintf('Trung bình %s tháng, %s', $number_month, $year),
			'bgcolor' => '#5605dd'
		), 'all_year' => array(
			'title' => sprintf('Chi vận hành năm <u>%s</u>', $year),
			'bgcolor' => '#124c25'
		)
	);
	$smarty->assign('list_blocks', $list_blocks);
	#
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$assign_list['prev_year'] = $prev_year;
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = strtotime(sprintf('01-01-%s', $end_year));
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $end_year);
	$to_date = strtotime(sprintf('%s-12-%s', $end_day, $end_year));
	$assign_list['start_date'] = $start_date;
	$assign_list['to_date'] = $to_date;
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	
	if($clsCache->has('_assets_cached')){
		$dataCached = $clsCache->get('_assets_cached');
		$total_prepaid = $core->get_field($dataCached, 'total_prepaid', 0); // Tổng tạm ứng
		$total_investment = $core->get_field($dataCached, 'total_investment', 0); // Tổng tạm ứng
		$total_loan_disbursed = $core->get_field($dataCached, 'total_loan_disbursed', 0); // Tổng tạm ứng
		$prepaid_arrs = $core->get_field($dataCached, 'prepaid_arrs', []);
		
		$refundable_deposit_balance = $core->get_field($dataCached, 'refundable_deposit_balance', 0); // Tồn cọc có thể thu hồi
		$financial_debt = $core->get_field($dataCached, 'financial_debt', 0); // Nợ vay tài chính
		
		$total_facility_cost = $core->get_field($dataCached, 'total_facility_cost', 0);  // Cơ sở vật chất
		$total_tax_due = $core->get_field($dataCached, 'total_tax_due', 0);  // Thuế phải nộp
	} else {
		$dataCached = array();
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
		$range = 'BC Tổng'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1ssLb1ce2VjqeK7oJjalQEmBgEPOTtsmCNBHRpFihSlE';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		##
		$prepaid_arrs = array();
		for($i=4; $i<17; $i++){
			$prepaid_arrs[] = array(
				'project_name' => $tblData[$i][2],
				'project_code' => $tblData[$i][3],
				'amount' => $tblData[$i][4],
				'notes' => $tblData[$i][5]
			);
		}
		$total_prepaid = isset($tblData[17][4]) ? $tblData[17][4] : 0; // Tổng tạm ứng
		$total_investment = isset($tblData[16][4]) ? $tblData[16][4] : 0; // Các khoản đầu tư
		$total_loan_disbursed = isset($tblData[15][4]) ? $tblData[15][4] : 0; // Tổng đã cho vay
		// $clsISO->print_pre($tblData); die();
		$dataCached['total_prepaid'] = $total_prepaid;
		$dataCached['total_investment'] = $total_investment;
		$dataCached['total_loan_disbursed'] = $total_loan_disbursed;
		$dataCached['prepaid_arrs'] = $prepaid_arrs;
		###
		$range = 'Báo cáo tổng quan'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1A2a8s9xSCb0gBZip0wYloDt07hmyv0xU6iV8jaM9CFs';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		$refundable_deposit_balance = isset($tblData[19][6]) ? $tblData[19][6] : 0; // Tồn cọc có thể thu hồi
		$dataCached['refundable_deposit_balance'] = $refundable_deposit_balance;
		###
		$range = 'Nợ vay'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1sppLvfhI4Wsx1RgkPpEF9blxC8ckv6C8bjw2TzTtBrg';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		$financial_debt = isset($tblData[0][2]) ? $tblData[0][2] : 0; // Nợ vay tài chính
		$dataCached['financial_debt'] = $financial_debt;
		### 
		$range = 'Cơ sở vật chất'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1sppLvfhI4Wsx1RgkPpEF9blxC8ckv6C8bjw2TzTtBrg';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		$total_facility_cost = isset($tblData[0][3]) ? $tblData[0][3] : 0; // Cơ sở vật chất
		$dataCached['total_facility_cost'] = $total_facility_cost;
		/** Tiền thuế */
		$range = 'THUẾ PHẢI NỘP'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1sppLvfhI4Wsx1RgkPpEF9blxC8ckv6C8bjw2TzTtBrg';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		$total_tax_due = isset($tblData[0][1]) ? $tblData[0][1] : 0; 
		$dataCached['total_tax_due'] = $total_tax_due;
		// Cached
		$clsCache->put('_assets_cached', $dataCached, 30*60);
	}
	// Tổng tạm ứng
	$total_prepaid = $clsISO->convertStringToNumber($total_prepaid);
	$total_investment = $clsISO->convertStringToNumber($total_investment);
	$total_loan_disbursed = $clsISO->convertStringToNumber($total_loan_disbursed);	
	$total_advance_amount = $total_prepaid - $total_loan_disbursed - $total_investment;
	// Tồn cọc có thể thu hồi
	$refundable_deposit_balance = $clsISO->processSmartNumber($refundable_deposit_balance);
	// Cơ sở vật chất
	$total_facility_cost = $clsISO->processSmartNumber($total_facility_cost); 
	// Nợ vay tài chính
	$financial_debt = $clsISO->processSmartNumber($financial_debt);	 
	// Thuế phải nộp
	$total_tax_due = $clsISO->convertStringToNumber($total_tax_due, 0);
	$assign_list['total_prepaid'] = $total_prepaid;
	$assign_list['total_investment'] = $total_investment;
	$assign_list['total_advance_amount'] = $total_advance_amount;
	$assign_list['prepaid_arrs'] = $prepaid_arrs;
	$assign_list['financial_debt'] = $financial_debt;
	// Tiền mội giới chờ về
	$total_pending_commission_broker = $clsCommission->sumItem("JSON_EXTRACT(`more_information`,\"$.receivable_amount\")", "FROM_UNIXTIME(`contract_date`,'%Y')='{$current_year}' and `status_company_id`='"._COMMISSION_UNPAID_STATUS_ID."'");
	// HHMG Phải trả
	$total_commission_payable_amount = $clsCommission->sumItem("JSON_EXTRACT(`more_information`,\"$.commission_payable_amount\")","FROM_UNIXTIME(`contract_date`,'%Y')='{$current_year}' and `status_sale_id`='"._COMMISSION_UNPAID_STATUS_ID."'");
	$assign_list['total_commission_payable_amount'] = $total_commission_payable_amount;
	// $clsISO->print_pre($total_commission_payable_amount); die();
	#- Tính Tiền mặt & Tiền trong NH
	$total_cash = 0; $more_information = array();
	$oneCash = $clsCashFund->getByCond("1=1 order by `upd_date` DESC", "more_information");
	if(!empty($oneCash)){
		if($clsCache->has('_bank_account_cached')){
			$list_bank_accounts = $clsCache->get('_bank_account_cached');
		} else {
			$field = "{$clsProperty->pkey},property_code,title,textcolor,bgcolor,image";
			$list_bank_accounts = $clsProperty->getAll("property_type='BANK_ACCOUNT' order by order_no ASC", $field);
			$clsCache->put('_bank_account_cached', $list_bank_accounts);
		}
		$more_information = $oneCash['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		if(!empty($list_bank_accounts)){
			foreach($list_bank_accounts as $key => $val){
				$bank_account_id = $val[$clsProperty->pkey];
				$total_price = $core->get_field($more_information, $bank_account_id, 0);
				$total_cash += $clsISO->processSmartNumber($total_price);
			}
		}
	}
	$total_assets = 0;
	$total_assets += $total_cash; // Tiền mặt
	$total_assets += $total_facility_cost; // CSVC
	$total_assets += $total_investment; // Đầu tư
	$total_assets += $total_advance_amount; // Tạm ứng
	$total_assets += $refundable_deposit_balance; // Tồn cọc
	$total_assets += $total_pending_commission_broker;
	$total_assets -= $financial_debt; // Nợ
	$total_assets -= $total_tax_due;
	$assign_list['total_assets'] = $total_assets;
	//$clsISO->print_pre($total_cash); die();
	
	$list_total_blocks = array(
		'cash_amount' => array(
			'bgcolor' => '#750e00',
			'title' => 'Tiền mặt',
			'money' => $total_cash,
		), 'amount_collected' => array(
			'bgcolor' => '#474a09',
			'title' => 'Tồn cọc',
			'money' => $refundable_deposit_balance,
		), 'facility_cost' => array(
			'bgcolor' => '#532301',
			'title' => 'Cơ sở vật chất',
			'money' => $total_facility_cost,
		), 'debt_balance' => array(
			'bgcolor' => '#014318',
			'title' => 'Tiền HHMG chờ về',
			'money' => $total_pending_commission_broker
		)
	);
	$list_total_2_blocks  = array(
		'tax_due' => array(
			'bgcolor' => '#8f7c35',
			'title' => 'Thuế phải nộp',
			'money' => $total_tax_due
		),
		'financial_debt' => array(
			'bgcolor' => '#8f7c35',
			'title' => 'Nợ vay phải trả',
			'money' => $financial_debt
		),
		'commission_payable' => array(
			'bgcolor' => '#8f7c35',
			'title' => 'HHMG phải trả',
			'money' => $total_commission_payable_amount
		),
	);
	$assign_list['list_total_blocks'] = $list_total_blocks;
	$assign_list['list_total_2_blocks'] = $list_total_2_blocks;
	/*=============Title & Description Page==================*/
	$title_page = 'Tài chính & Tài sản - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
?>