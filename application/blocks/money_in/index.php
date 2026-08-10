<?php 
	global $core, $smarty, $dbconn, $clsISO;
	$clsCache = new Cache();
	$clsProperty = new Property();
	
	$list_money_blocks = array(
		'invoiced_amount' => array(
			'bgcolor' => '#002d39',
			'title' => 'Tổng tiền hóa đơn đã xuất',
			'money' => 0,
		), 'amount_collected' => array(
			'bgcolor' => '#474a09',
			'title' => 'Tổng tiền đã nhận',
			'money' => 0,
		), 'amount_pending' => array(
			'bgcolor' => '#532301',
			'title' => 'Tổng tiền chờ về',
			'money' => 0,
		), 'debt_balance' => array(
			'bgcolor' => '#014318',
			'title' => 'Tổng tiền cty nợ HHMG',
			'money' => 0
		), 'cash_on_hand' => array(
			'bgcolor' => '#01094e',
			'title' => 'Số tiền cty còn',
			'money' => 0
		)
	);
	
	$current_year = date('Y');
	$cache_name = sprintf('_sales_commission_%s_cached', $current_year);
	
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
	$service = new Google_Service_Sheets($client);
	
	$invoiced_amount = $amount_collected = $amount_pending = $debt_balance = $cash_on_hand = 0;
	// get all the rows of a sheet
	if($current_year == 2025){
		try {
			$range = 'ĐỐI CHIẾU TIỀN VÀO 2025'; // here we use the name of the Sheet to get all the rows
			$spreadsheetId = '1dFTBk7904QvYMuq53XQL1UHUQoCTNekt_lBB599dZf0';
			$response = $service->spreadsheets_values->get($spreadsheetId, $range);
			$tblData = $response->getValues();
			
			$invoiced_amount = $clsISO->processSmartNumber($tblData[2][2]);
			$amount_collected = $clsISO->processSmartNumber($tblData[3][2]);
			$amount_pending = $clsISO->processSmartNumber($tblData[4][2]);
			$debt_balance = $clsISO->processSmartNumber($tblData[5][2]);
			$cash_on_hand = $clsISO->processSmartNumber($tblData[6][2]);
		} catch(Exception $ex){
			
		}
	} else if($current_year == 2026){
		try {
			// Sơ cấp
			$range = 'ĐỐI CHIẾU TIỀN VÀO SƠ CẤP 2026'; // here we use the name of the Sheet to get all the rows
			$spreadsheetId = '1dFTBk7904QvYMuq53XQL1UHUQoCTNekt_lBB599dZf0';
			$response = $service->spreadsheets_values->get($spreadsheetId, $range);
			$tblData = $response->getValues();
			
			$invoiced_amount = $clsISO->processSmartNumber($tblData[2][2]);
			$amount_collected = $clsISO->processSmartNumber($tblData[3][2]);
			$amount_pending = $clsISO->processSmartNumber($tblData[6][2]);
			$debt_balance = $clsISO->processSmartNumber($tblData[7][2]);
			$cash_on_hand = $clsISO->processSmartNumber($tblData[8][2]);
		} catch(Exception $ex){
			
		}
	}
	$list_money_blocks['invoiced_amount']['money'] = $invoiced_amount;
	$list_money_blocks['amount_collected']['money'] = $amount_collected;
	$list_money_blocks['amount_pending']['money'] = $amount_pending;
	$list_money_blocks['debt_balance']['money'] = $debt_balance;
	$list_money_blocks['cash_on_hand']['money'] = $cash_on_hand;
	###
	$smarty->assign('list_money_blocks', $list_money_blocks);
?>