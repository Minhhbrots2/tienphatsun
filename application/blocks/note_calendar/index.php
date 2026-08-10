<?php
	global $core,$smarty,$dbconn,$clsISO,$profile_id;
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	// ini_set('display_errors',1);
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsBillingMeta = new BillingMeta();
	$list_preloaders = array();
	for($i=0; $i<10; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);

	$lstFollowUp = $clsFollowUp->getAll("`followup_type`='_note' AND `admin_id`='{$profile_id}' GROUP BY `date_time`","FROM_UNIXTIME(`date_id`,'%Y-%m-%d') as date_time, COUNT({$clsFollowUp->pkey}) as total, type_id"); 
	$arr_note = [];
	foreach ($lstFollowUp as $key => $val) {
		$arr_note["{$val['date_time']}"] = (int)$val["total"];
	}

	
	$smarty->assign('arr_note', json_encode($arr_note,JSON_UNESCAPED_UNICODE));
	$decoder = new Webmozart\Json\JsonDecoder();
	$cachedFile = DIR_CACHE_JSON.'/calendar/special.json';
	$arr_special = $decoder->decodeFile($cachedFile);
	$smarty->assign('arr_special', json_encode($arr_special,JSON_UNESCAPED_UNICODE));

?>