<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Log extends dbBasic{
	function Log(){
		$this->pkey = "log_id";
		$this->tbl = DB_PREFIX."log";
	}
	function insertAction($type, $title="", $stock_id="0", $block_id=0){
		global $core, $profile_id, $dbconn, $clsISO, $oneProfile;
		$clsStock = new Stock();
		$clsProfile = new Profile();	
		$f = "`type`,`title`,`intro`,`reg_date`,`user_id`,`stock_id`,`from_site`";
		$v = "'{$type}','".addslashes($title)."','".addslashes($intro)."'
		,'".time()."','".$profile_id."','{$stock_id}','_user'";
		// $dbconn->debug = true;
		$this->insertOne($f,$v);
		#log block search
		if(($type == "view_stock" || $type == "search") && $stock_id > 0 && $block_id > 0) {
			$more_information = $oneProfile["more_information"];
			$block_search = $core->get_field($more_information, "block_search", []);
			if(isset($block_search[$block_id])) {
				$block_search[$block_id] += 1;
			}else{
				$block_search[$block_id] = 1;
			}
			$more_information["block_search"] = $block_search;
			$clsProfile->updateOne($profile_id, array(
				"more_information" => json_encode($more_information,JSON_UNESCAPED_UNICODE)
			));			
		}
		return 1;
	}
	function getFieldTable($field,$log_id){
		$one = $this->getOne($log_id);
		$intro = $one['intro'];
		$un_intro = unserialize($intro);
		return $un_intro[$field];
	}
	function getActionByType($type,$pkeyTable,$pvalTable){
		$all = $this->getAll("type='{$type}' and pkeyTable='$pkeyTable' and pvalTable='$pvalTable'");
		return $all;
	}
	function getTotalSearch($target_id,$from_site=""){
		$cond = "";
		if($from_site != ""){
			$cond = " AND from_site='".$from_site."'";
		}
		return $this->countItem("(`type`='search' or `type`='view_stock') and target_id='{$target_id}'".$cond);
	}
	function getStock($title){
		$clsStock = new Stock();
		$title = trim(strip_tags($title));
		$ms_code = str_replace('Tra cứu căn hộ ', '', $title);
		$ms_code = str_replace('Xem thông tin căn hộ ', '', $ms_code);
		if(!empty($ms_code)){
			$oStock = $clsStock->getByCond("`ms_code`='{$ms_code}'","{$clsStock->pkey},`status_id`,`agency_id`");
			return $oStock;
		} else {
			return 0;
		}
	}
	function getStockId($title){
		$clsStock = new Stock();
		$title = trim(strip_tags($title));
		$ms_code = str_replace('Tra cứu căn hộ ','',$title);
		$ms_code = str_replace('Xem thông tin căn hộ ','',$ms_code);
		if(!empty($ms_code)){
			$oStock = $clsStock->getByCond("ms_code='{$ms_code}'", $clsStock->pkey);
			return !empty($oStock) ? $oStock[$clsStock->pkey] : 0;
		} else {
			return 0;
		}
	}	
	function getProjectByURL($url) {
		preg_match('#/project/p(\d+)(?:/b(\d+))?\.html#', $url, $matches);
		$project_id = isset($matches[1]) ? (int)$matches[1] : null;
		$building_id = isset($matches[2]) ? (int)$matches[2] : null;
		return [
			"project_id"	=>	$project_id,
			"building_id"	=>	$building_id,
		];
	}
	function getLinkByText($text) {
		$pos = strpos($text, '/');
		if ($pos === false) {
			return null;
		}
		return substr($text, $pos);
	}
	
}
?>