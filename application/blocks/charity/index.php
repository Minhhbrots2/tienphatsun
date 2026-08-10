<?php
	global $core,$smarty,$profile_id,$oneProfile,$dbconn,$clsISO,$dev;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/quote/quote_profile_'.$profile_id.'.json';
	$encoder = new Webmozart\Json\JsonEncoder();
	$clsCache = new Cache();
	#
	if($clsCache->has('_charity_cached')){
		$total_charity = $clsCache->get('_charity_cached');
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
		$range = 'Trang tính1'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1LcAcrbJRY9uWfBSwzZ_lsCVbxBgT7gwiE_5t7kK2J_4';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		$total_charity = 0;
		foreach ($tblData as $key => $val) {
			$is_total = 0;
			if($core->replaceSpace($val[1]) == 'tong-cong') {
				$is_total = 1;
				$total_charity = $val[3];
				break;
			}
			
		}
		// Cached
		$clsCache->put('_charity_cached', $total_charity, 30*60);
	}
	$smarty->assign("total_charity",$total_charity);

?>