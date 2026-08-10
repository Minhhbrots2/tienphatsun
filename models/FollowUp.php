<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class FollowUp extends dbBasic{
	function __construct(){
		global $profile_id;
		$this->pkey = "followup_id";
		if($profile_id == _PROFILE_TVT_TECH_ID) {	
			$this->tbl = DB_PREFIX."followups";
		}else{
			$this->tbl = DB_PREFIX."followups";
		}
	}
	function getFollowUp($followup_id){
		return 'Follow-Up(s) #'.$followup_id;
	}
	function getContent($followup_id, $oDataTable = array()){
		if(!isset($oDataTable['intro'])){
			$oDataTable = $this->getOne($followup_id, "intro");
		}
		return $oDataTable['intro'];
	}
	function getStatus($followup_id, $oDataTable=null){
		$text = 'NEW';
		$bgColor = '#00c444';
		return '<label class="label" style="background:'.$bgColor.'">'.$text.'</label>';
	}
	function getHTMLType($followup_id, $oDataTable=null){
		global $core,$dbconn,$clsISO;
		$clsProperty = new Property();
		if(!isset($oDataTable['type_id'])){
			$oDataTable = $this->getOne($followup_id,"type_id");
		}
		$type_id = $oDataTable['type_id'];
		$oneProperty = $clsProperty->getOne($type_id,"title,bgcolor");
		return '<label class="label" style="background:'.$oneProperty['bgcolor'].'">'.$oneProperty['title'].'</label>';
	}
	function getBackground($followup_id, $oDataTable=null){
		global $core;
		$clsProperty = new Property();
		if(!isset($oDataTable['type_id'])){
			$oDataTable = $this->GetOne($followup_id,"type_id");
		}
		return $clsProperty->getTextColor($oDataTable['type_id']);
	}
	function getTotal($resource_id, $holderG='potential'){
		return $this->countItem("resource_id='{$resource_id}' and holderG='{$holderG}'");
	}
	function getTotalDone($resource_id, $holderG='potential'){
		return $this->countItem("resource_id='{$resource_id}' and holderG='{$holderG}' and is_done='1'");
	}
	function getNotes($tp, $pval_id){
		$tmp = $this->getAll("holderG='{$tp}' and resource_id='{$pval_id}' and is_transfer='1' limit 0,1", "content");
		//vsprint_r($tmp); die();
		return !empty($tmp) ? $tmp[0]['content'] : "";
	}
	function senEmail($crm_followup_id){
		
	}
	function checkDone($followup_id, $oDataTable = array()){
		if($oDataTable['date_id'] > time())
			return 1;
		return 0;
	}
}
?>