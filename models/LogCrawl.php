<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class LogCrawl extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."logcrawl";
		if(IS_ADMIN_PAGE == 1) {
			$this->_site = "_admin";
		}else{
			$this->_site = "_front";
		}
	}
	function insertLog($action, $stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE,
		$block_id = 0, $target_id = 0, $description = array(), $from_site = '_admin',$type=0){
		global $core, $dbconn, $profile_id;
		if($from_site == '_admin'){
			$user_id = $core->_USER['user_id'];
		} else {
			$user_id = $profile_id;
		}
		$this->insert(array(
			$this->pkey => $this->getMaxId(),
			'action' => $action,
			'stock_type' => $stock_type,
			'block_id' => $block_id,
			'target_id' => $target_id,
			'description' => json_encode($description, JSON_UNESCAPED_UNICODE),
			'user_id' => $user_id,
			'from_site' => $from_site,
			'type' => $type,
			'date' => time()
		));
	}
	function log($agency_id,$target_id, $data, $stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE){
		global $core, $dbconn, $profile_id,$clsISO;
		if($this->_site == '_admin'){
			$user_id = $core->_USER['user_id'];
		} else {
			$user_id = $profile_id;
		}
		$time_now = time();
		$oneLog = $this->getByCond("FROM_UNIXTIME(`date`,'%d/%m/%Y')='".date("d/m/Y")."' AND `agency_id`='{$agency_id}' AND `stock_type`='{$stock_type}' ORDER BY `date` DESC");
		$description = $logs = $totals = array();
		if(!empty($oneLog)) {
			$id = $oneLog[$this->pkey];
			$description = $clsISO->to_array_json($oneLog['description']);
			$logs = !empty($description["logs"]) ? $description["logs"] : array();
			$totals = !empty($description["totals"]) ? $description["totals"] : array();
		}	
		if(empty($data["title_log"]) && $data["result_type"] == "update") {			
			$data["title_log"] = 'Tổng quỹ: '.$data["total_stock"].', Ðã bán: '.$data["total_stock_sold"].', Nhập mới: '.$data["total_stock_new"];
			$user_id = 0;
		}
		$arr_data = [
			"title_log" 		=>  !empty($data["title_log"]) ? $data["title_log"] : "",
			"type"  			=>  !empty($data["type"]) ? $data["type"] : 0,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
			"result_type" 		=>  !empty($data["result_type"]) ? $data["result_type"] : "update",	//change_field,copy_speadsheet,read_speadsheet
			"_from_site" 		=>  $this->_site,
			"user_id" 			=> 	$user_id,
			"time"  			=>	$time_now,			
		];
//		echo "<pre>";
//		var_dump($arr_data);die;
//		echo "</pre>";
		if(!empty($data["total_stock_sold"])) $arr_data["total_stock_sold"] = $data["total_stock_sold"];
		if(!empty($data["total_stock_new"])) $arr_data["total_stock_new"] = $data["total_stock_new"];
		if(!empty($data["total_stock"])) $arr_data["total_stock"] = $data["total_stock"];
		if(!empty($data["stock_not_upd"])) $arr_data["stock_not_upd"] = $data["stock_not_upd"];
		if(!empty($data["data_log"])) $arr_data["data_log"] = $data["data_log"];
		$logs[$target_id][] = $arr_data;
		$totals[$target_id] = !empty($totals[$target_id]) ? ($totals[$target_id] + 1) : 1;
		$description["logs"] = $logs;
		$description["totals"] = $totals;
		if(!empty($id)) {
			$this->updateOne($oneLog[$this->pkey],["description" => json_encode($description, JSON_UNESCAPED_UNICODE)]);
		}else{
			$this->insert(array(
				$this->pkey => $this->getMaxId(),
				'agency_id' => $agency_id,
				'stock_type' => $stock_type,
				'description' => json_encode($description, JSON_UNESCAPED_UNICODE),
				'date' => $time_now
			));
		}
	}
	function getTimeAgo($time){
		global $_LANG_ID;
		if($_LANG_ID=='en'){
			$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		}else{
			$periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
		}
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = time();
		$difference = $now - $time;
		if($_LANG_ID=='en'){
			$tense = "ago";
		} else {
			$tense = "trước";
		}
	   	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   	    $difference /= $lengths[$j];
	   	}
	   	$difference = round($difference);
	   	if($difference != 1) {
	   		if($_LANG_ID=='en'){
	   			$periods[$j].= "s";
	   		}
	   	}
		if($j < 2 || ($j == 2 && $difference <= 4)) {
			return "<strong class='text-success'>" . "$difference $periods[$j] ".$tense . "</strong>";
		}
		return "<strong class='text-main'>" . "$difference $periods[$j] ".$tense . "</strong>";
//	   	return ''."$difference $periods[$j] ".$tense;
	}
	function isOverTime($time, $time_str="hour", $number_time=4){
		global $_LANG_ID;
		$arr_time_str = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		if($_LANG_ID=='en'){
			$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		}else{
			$periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
		}
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = time();
		$difference = $now - $time;
		if($_LANG_ID=='en'){
			$tense = "ago";
		} else {
			$tense = "trước";
		}
	   	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   	    $difference /= $lengths[$j];
	   	}
	   	$difference = round($difference);
	   	if($difference != 1) {
	   		if($_LANG_ID=='en'){
	   			$periods[$j].= "s";
	   		}
	   	}
		$key_time = !empty($time_str) ? array_search($time_str, $arr_time_str): 2;
		if($j < $key_time || ($j == $key_time && $difference <= $number_time)) {
			return 0;
		}
		return 1;
//	   	return ''."$difference $periods[$j] ".$tense;
	}
	
}