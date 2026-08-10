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
class StockTotal extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."stock_total";	
	}
	function updateStockTotal () {
		global $clsISO;
		$clsStock = new Stock();
		$clsStockAgent = new StockAgent();
		$lstStockTotal = $clsStockAgent->getAll("1=1 GROUP BY `target_id`","COUNT(id) as total, `target_id`, `stock_type`");
		foreach ($lstStockTotal as $key => $val) {
			$checkStockTotal = $this->getByCond("FROM_UNIXTIME(`date`,'%d/%m/%Y')='".date("d/m/Y")."' AND `target_id`='{$val['target_id']}' ");
			if(!empty($checkStockTotal)) {
				$this->updateOne($checkStockTotal[$this->pkey],[
					"total"	=>	$val['total'],
					"stock_type"	=>	$val['stock_type'],
				]);
			}else{
				$this->insert([
					$this->pkey	=>	$this->getMaxId(),
					"target_id"	=>	$val['target_id'],
					"total"	=>	$val['total'],
					"stock_type"	=>	$val['stock_type'],
					"date"	=>	time(),
				]);
			}
		}
	}
}
?>