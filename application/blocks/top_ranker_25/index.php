<?php 
	global $core, $smarty, $clsISO,$dbconn;
	$clsCache = new Cache();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	##
	$my = date("n/Y");
	$title_content = "Top 25 cá nhân đặc biệt xuất sắc tháng {$my}" ;
	$title_content_time = "Tính từ ngày ".date("01/m/Y") . " đến ngày ".date("d/m/Y");
	$smarty->assign("title_content",$title_content);
	$smarty->assign("title_content_time",$title_content_time);
	#
	$lstItem = array();
	for($i=0; $i< 25; $i++) {
		$lstItem[] = [
			"bgcolor"	 =>	"#FFF",
			"bg_image"	 =>	URL_IMAGES."/rank/top-4.png",
			"col_height" =>	5 + $i*2,
			"is_none"	 =>	1
		];
	}
	$smarty->assign("lstItem",$lstItem);
?>