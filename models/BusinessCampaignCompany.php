<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Private By VietISO (support@vietiso.com)                     # ||
|| # ---------------------------------------------------------------- # ||
|| # All Script code in this file is ©2007-2013 VietISO JSC.          # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class BusinessCampaignCompany extends dbBasic{
	function BusinessCampaignCompany(){
		$this->pkey = "business_campaign_company_id";
		$this->tbl = DB_PREFIX."business_campaign_company";
		$this->setTable($this->tbl);
	}
	function getMaxId(){
		$res = $this->getAll("1=1 order by business_campaign_company_id desc limit 0,1");
		return intval($res[0]['business_campaign_company_id'])+1;
	}
	function getMaxOrderNo(){
		$one = $this->getAll("1=1 order by order_no desc limit 0,1");
		return intval($one[0]['order_no'])+1;
	}
	function checkExist($business_campaign_id,$pvaltable,$tp){
		global $_LANG_ID;
		if($this->countItem("business_campaign_id='$business_campaign_id' and pvaltable='$pvaltable' and tp='$tp'")>0)
			return 1;
		return 0;
	}
	function insertRow($business_campaign_id,$pvaltable,$tp){
		global $_frontIsLoggedin_user_id,$core,$clsISO;
		if($this->checkExist($business_campaign_id,$company_id)==0){
			$f = "business_campaign_id,pvaltable,tp,user_id,reg_date";
			$v = "'$business_campaign_id','$pvaltable','$tp','$_frontIsLoggedin_user_id','".time()."'";
			$this->insertOne($f,$v);
			
		}
	}
	function deleteRow($business_campaign_id,$pvaltable,$tp){
		global $_frontIsLoggedin_user_id,$core,$clsISO;
		if($this->checkExist($business_campaign_id,$pvaltable,$tp)==1){
			$this->deleteByCond("business_campaign_id='$business_campaign_id' and pvaltable='$pvaltable' and tp='$tp'");
		}
	}
	function deleteAllRow($business_campaign_id){
		global $_frontIsLoggedin_user_id,$core,$clsISO;
		$this->deleteByCond("business_campaign_id='$business_campaign_id'");
	}
}
?>