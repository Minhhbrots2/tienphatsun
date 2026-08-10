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
class BusinessCampaignStatus extends dbBasic{
	function BusinessCampaignStatus(){
		$this->pkey = "id";
		$this->tbl = "_crm_campaign_status";
		$this->setTable($this->tbl);
	}
	function getTitle($id){
		return $this->getOneField('title',$id);
	}
}
?>