<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class StockAgent extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."stock_agent";	
	}
	function updateStockCrawl ($agency_id) {
		global $clsISO;
		$clsTmpStockAgent = new TmpStockAgent();
		$clsStockTotal = new StockTotal();
		$arr_stocks = $arr_stock_type = [];
		$tmp = $clsTmpStockAgent->getAll("`agency_id`='{$agency_id}'");
		if(!empty($tmp)) {
			foreach ($tmp as $key => $val) {
				$list_stock_code = $val['list_stock_id'];
				$arrs = !empty($list_stock_code) ? $clsISO->getArrayByTextSlash($list_stock_code) : array();
				if(!isset($arr_stocks[$val["target_id"]])) {
					$arr_stocks[$val["target_id"]] = [];
				}
				$arr_stocks[$val["target_id"]] = array_merge($arr_stocks[$val["target_id"]], $arrs);
				$arr_stock_type[$val["target_id"]] = $val["stock_type"];
			}
		}
		$total = 0;
		$this->deleteByCond("`agency_id`='{$agency_id}'");
		if(!empty($arr_stocks)){
			$total = count($arr_stocks);
			foreach ($arr_stocks as $target_id => $ms_codes) {
				foreach($ms_codes as $ms_code) {
					$this->insert(array(
						$this->pkey => $this->getMaxId(),
						'agency_id' 	=> $agency_id,
						'ms_code' 		=> $ms_code,
						'target_id' 	=> $target_id,
						'stock_type' 	=> $arr_stock_type[$target_id],
					));
				}
				
			}
		}
		$clsStockTotal->updateStockTotal();
		return [
			"total"	=>	$total, 
			"ms_codes" => $arr_stocks
		];
	}
}
?>