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
class TmpStockAgent extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."tmp_stock_agent";	
	}
	function updateStockTmp($agency_id,$stock_type,$target_id,$list_stocks){
		global $dbconn, $clsISO;
		$total = !empty($list_stocks) ? count($list_stocks) : 0;
		$list_stock_id = !empty($list_stocks) ? $clsISO->makeSlashListFromArrayRoot($list_stocks) : "";
		$oneTmpStockCrawl = $this->getByCond("`agency_id`='{$agency_id}' AND `target_id`='{$target_id}' 
			AND `stock_type`='{$stock_type}'",$this->pkey);
		if(!empty($oneTmpStockCrawl)) {
			$this->updateOne($oneTmpStockCrawl[$this->pkey], array(
				'list_stock_id' => $list_stock_id,
				'date' => time(),
				'total' => $total,
			));
		}else{
			// $dbconn->debug = true;
			$this->insert(array(
				$this->pkey => $this->getMaxId(),
				'agency_id' => $agency_id,
				'stock_type' => $stock_type,
				'target_id' => $target_id,
				'list_stock_id' => $list_stock_id,
				'total' => $total,
				'date' => time(),
			));
		}
		return 1;
	}
}
?>