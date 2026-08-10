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
class BusinessCampaign extends dbBasic{
	function BusinessCampaign(){
		$this->pkey = "business_campaign_id";
		$this->tbl = DB_PREFIX."business_campaign";
		$this->setTable($this->tbl);
	}
	function getMaxId(){
		$res = $this->getAll("1=1 order by business_campaign_id desc limit 0,1");
		return intval($res[0]['business_campaign_id'])+1;
	}
	function getMaxOrderNo(){
		$one = $this->getAll("1=1 order by order_no desc limit 0,1");
		return intval($one[0]['order_no'])+1;
	}
	function checkSlug($slug){
		global $_LANG_ID;
		if($this->countItem("slug='".$slug."'")>0)
			return 0;
		return 1;
	}
	function getTitle($pvalTable){
		$one=$this->getOne($pvalTable);
		return $one['title'];
	}
	function getTp($pvalTable){
		$clsBusinessCampaignCompany = new BusinessCampaignCompany();
		$all = $clsBusinessCampaignCompany->getAll("tp<>'CRMPotential' and business_campaign_id='$pvalTable'");
		return $all[0]['tp'];
	}
	function getTotalBySOP($business_campaign_id){
		$total = 0;
		$clsSOP = new SOP();
		$lstSOP = $clsSOP->getAll("is_trash=0 and business_campaign_id='$business_campaign_id'");
		if($lstSOP[0]['sop_id']!=''){
			for($i=0;$i<count($lstSOP);$i++){
				$total += $lstSOP[$i]['price'];
			}
		}
		return $total;
	}
	function getTotalBySOPWin($business_campaign_id){
		$total = 0;
		$clsSOP = new SOP();
		$lstSOP = $clsSOP->getAll("is_trash=0 and business_campaign_id='$business_campaign_id' and giaidoan_id='960'");
		if($lstSOP[0]['sop_id']!=''){
			for($i=0;$i<count($lstSOP);$i++){
				$total += $lstSOP[$i]['price'];
			}
		}
		return $total;
	}
	function getTotalBySOPLose($business_campaign_id){
		$total = 0;
		$clsSOP = new SOP();
		$lstSOP = $clsSOP->getAll("is_trash=0 and business_campaign_id='$business_campaign_id' and giaidoan_id='961'");
		if($lstSOP[0]['sop_id']!=''){
			for($i=0;$i<count($lstSOP);$i++){
				$total += $lstSOP[$i]['price'];
			}
		}
		return $total;
	}
	function getTotalBySOPDoing($business_campaign_id){
		return $this->getTotalBySOP($business_campaign_id)-$this->getTotalBySOPWin($business_campaign_id)-$this->getTotalBySOPLose($business_campaign_id);
	}
	
	function getTotalByOrder($business_campaign_id){
		$total = 0;
		$clsCrmOrder = new CrmOrder();
		$lstCrmOrder = $clsCrmOrder->getAll("is_trash=0 and business_campaign_id='$business_campaign_id'");
		if($lstCrmOrder[0]['crm_order_id']!=''){
			for($i=0;$i<count($lstCrmOrder);$i++){
				$total += $lstCrmOrder[$i]['price_total'];
			}
		}
		return $total;
	}
	
	function getTotalByOrderStatus($business_campaign_id,$status_id){
		$total = 0;
		$clsCrmOrder = new CrmOrder();
		$lstCrmOrder = $clsCrmOrder->getAll("is_trash=0 and business_campaign_id='$business_campaign_id' and status_id='$status_id'");
		if($lstCrmOrder[0]['crm_order_id']!=''){
			for($i=0;$i<count($lstCrmOrder);$i++){
				$total += $lstCrmOrder[$i]['price_total'];
			}
		}
		return $total;
	}
	
	function getTotalByContract($business_campaign_id){
		$total = 0;
		$clsContract = new Contract();
		$lstContract = $clsContract->getAll("is_trash=0 and business_campaign_id='$business_campaign_id'");
		if($lstContract[0]['contract_id']!=''){
			for($i=0;$i<count($lstContract);$i++){
				$total += $lstContract[$i]['price_total'];
			}
		}
		return $total;
	}
	function getTotalByContractStatus($business_campaign_id,$contract_status_id){
		$total = 0;
		$clsContract = new Contract();
		$lstContract = $clsContract->getAll("is_trash=0 and business_campaign_id='$business_campaign_id' and contract_status_id='$contract_status_id'");
		if($lstContract[0]['contract_id']!=''){
			for($i=0;$i<count($lstContract);$i++){
				$total += $lstContract[$i]['price_total'];
			}
		}
		return $total;
	}
	function getValueFilter($arrFilters, $column, $field){
		$value = ($field=='status') ? 0 : "";
		if(isset($arrFilters[$column][0][$field])){
			$value = $arrFilters[$column][0][$field];
		}
		return $value;
	}
	function checkCompare($source, $hasystack){
		$arraysAreEqual = ($source == $hasystack);
		return $arraysAreEqual;
	}
	function sync($business_campaign_id){
		global $adminid, $dbconn, $core;
		$clsPotential = new Potential();
		$clsBusinessCampaignPotential = new BusinessCampaignPotential();
		$lstPotential = $clsPotential->GetAll("campaign_id='{$business_campaign_id}'", $clsPotential->pkey);
		if(!empty($lstPotential)){
			foreach($lstPotential as $potential){
				$potential_id = $potential[$clsPotential->pkey];
				if($clsBusinessCampaignPotential->countItem("potential_id='{$potential_id}' and business_campaign_id='{$business_campaign_id}'")==0){
					$clsBusinessCampaignPotential->insert(array(
						'id'	=> $clsBusinessCampaignPotential->getMaxId(),
						'potential_id' => $potential_id,
						'business_campaign_id' => $business_campaign_id,
						'status_id'	=> 1,
						'user_id'	=> $adminid,
						'reg_date'	=> time()
					));
				}
			}
		}
		$this->updateOne($business_campaign_id, array(
			'number_val'	=> $clsBusinessCampaignPotential->countItem("business_campaign_id='{$business_campaign_id}'")
		));
	}
	function updateTotal($business_campaign_id){
		$clsBusinessCampaignPotential = new BusinessCampaignPotential();
		$this->updateOne($business_campaign_id, array(
			'number_val'	=> $clsBusinessCampaignPotential->countItem("business_campaign_id='{$business_campaign_id}'")
		));
	}
	function countContactByAdmin($adminid, $business_campaign_id){
		$clsBusinessCampaignPotential = new BusinessCampaignPotential();
		return $clsBusinessCampaignPotential->countItem("user_id='{$adminid}' and business_campaign_id='{$business_campaign_id}'");
	}
}
?>