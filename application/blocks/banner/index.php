<?php
	global $core,$smarty,$profile_id,$oneProfile,$dbconn,$clsISO,$dev;	
	$clsQuote = new Quote();
	$clsGroupProfile = new GroupProfile();
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/quote/quote_profile_'.$profile_id.'.json';
	$encoder = new Webmozart\Json\JsonEncoder();
	#
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	$sql_query = "(`user_id`='{$profile_id}' OR `apply_to`='all' 
		OR (`apply_to`='department' AND `share_ids` LIKE '%|{$department_id}|%') 
		OR (`apply_to`='profile' AND `share_ids` LIKE '%|{$profile_id}|%')";
	$tmp = $clsGroupProfile->getAll("`is_online`='1' AND `list_profile_id` LIKE '%|{$profile_id}|%'", $clsGroupProfile->pkey);
	if(!empty($tmp)) {
		$sql_query.= " OR (`apply_to`='group' AND (";
		foreach($tmp as $key => $val) {
			$sql_query .= (($key > 0) ? " OR " : "") . "`share_ids` LIKE '%|{$val[$clsGroupProfile->pkey]}|%'";
		}
		$sql_query.= " ))";
		unset($tmp);
	}
	$sql_query.= " )";
	$lstQuoteProfile = array();
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lstQuoteProfile = $decoder->decodeFile($cachedFile);
	}
	$sql_query_notin = "";
	$quoteProfile = (!empty($lstQuoteProfile[$profile_id]) && is_array($lstQuoteProfile[$profile_id])) ? $lstQuoteProfile[$profile_id] : array();
	if(!empty($quoteProfile)) {
		$sql_query_notin = " AND `{$clsQuote->pkey}` NOT IN (".implode(',',$quoteProfile).")";
	}
	$oneQuote = $clsQuote->getByCond($sql_query.$sql_query_notin." ORDER BY RAND() LIMIT 1","`{$clsQuote->pkey}`,`content`,`author`");
	if(!empty($oneQuote)) {
		$quoteProfile[] = $oneQuote[$clsQuote->pkey];
		$lstQuoteProfile[$profile_id] = $quoteProfile;
	} else{
		$oneQuote = $clsQuote->getByCond($sql_query." ORDER BY RAND() LIMIT 1","`{$clsQuote->pkey}`,`content`,`author`");
		$lstQuoteProfile[$profile_id][] = $oneQuote[$clsQuote->pkey];
	}
	$encoder->encodeFile($lstQuoteProfile, $cachedFile);
	#color
	$colors = array(
		array("bgcolor"	=> "#8B0000", "color" => "#FFFFFF"),
		array("bgcolor"	=> "#D72638", "color" => "#FFF4E0"),
		array("bgcolor"	=> "#00897B", "color" => "#FFFFFF"),
		array("bgcolor"	=> "#6A1B9A", "color" => "#FCE4EC")
	);
	// Lấy ngẫu nhiên 1 mảng con
	mt_srand((float)microtime()*1000000);
	$rnd = mt_rand(0, count($colors)-1);
	$arr_color = $colors[$rnd];
	$smarty->assign("clsQuote",$clsQuote);
	$smarty->assign("oneQuote",$oneQuote);
	$smarty->assign("arr_color",$arr_color);
	#
	list($d,$m,$y,$leap) = $clsISO->getLunarDate(date("d"),date("m"),date("Y"));
	$luna_date = $d."/".$m;
	$smarty->assign("today",date("d/m/Y"));
	$smarty->assign("luna_date",$luna_date);
?>