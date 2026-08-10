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
class KPI extends dbBasic{
	function __construct(){
		$this->pkey = "kpi_id";
		$this->tbl = DB_PREFIX."kpi";	
	}
	function getPercent($a, $b){
		if($a==0 && $b==0)
			return 0;
		if($a > 0 && $b ==0)
			return 100;
		return round($a/$b,2)*100;
	}
	function getTargetQuarter($department_id, $start_month, $end_month){
		global $core, $clsISO;
		$total = 0;
		for($month=$start_month; $month<=$end_month; $month++){
			$field = "{$this->pkey},configs";
			$oneKPI = $this->getByCond("`period`='MONTH' and `year_period`='{$year}' and `month_slash` like '%|{$month}|%'", $field);
			if(empty($oneKPI)){
				$oneKPI = $this->getByCond("`period`='ALLMONTH' and `year_period`='{$year}'", $field);
			}
			if(!empty($oneKPI)){
				$configs = $oneKPI['configs'];
				$configs_arrs = !empty($configs) 
					? json_decode(html_entity_decode($configs), true) : array();
				$total_sale = isset($configs_arrs[$department_id]['total_sale']) && !empty($configs_arrs[$department_id]['total_sale']) 
					? $clsISO->processSmartNumber($configs_arrs[$department_id]['total_sale']) : 0;
				$total += $total_sale;
			}
		}
		// Return
		return $total;
	}
	function getTargetYear($department_id, $year){
		global $core, $clsISO;
		if(!$year) $year = date('Y');
		$total = 0;
		for($month=1; $month<=12; $month++){
			$field = "{$this->pkey},configs";
			$oneKPI = $this->getByCond("`period`='MONTH' and `year_period`='{$year}' and `month_slash` like '%|{$month}|%'", $field);
			if(empty($oneKPI)){
				$oneKPI = $this->getByCond("`period`='ALLMONTH' and `year_period`='{$year}'", $field);
			}
			if(!empty($oneKPI)){
				$configs = $oneKPI['configs'];
				$configs_arrs = !empty($configs) 
					? json_decode(html_entity_decode($configs), true) : array();
				$total_sale = isset($configs_arrs[$department_id]['total_sale']) && !empty($configs_arrs[$department_id]['total_sale']) 
					? $clsISO->processSmartNumber($configs_arrs[$department_id]['total_sale']) : 0;
				$total += $total_sale;
			}
		}
		// Return
		return $total;
	}
}