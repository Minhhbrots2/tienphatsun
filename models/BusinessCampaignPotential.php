<?php 
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
class BusinessCampaignPotential extends dbBasic{
	function BusinessCampaignPotential(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."business_campaign_potential";
		$this->setTable($this->tbl);
	}
	function getCampaignList($potential_id){
		$campaignList = array();
		$tmp = $this->getAll("potential_id='{$potential_id}'","business_campaign_id");
		if(!empty($tmp)){
			foreach($tmp as $item){
				$campaignList[] = $item['business_campaign_id'];
			}
			unset($tmp);
		}
		return $campaignList;
	}
}
?>