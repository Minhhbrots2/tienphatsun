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
class CRMFieldValue extends dbBasic{
	function CRMFieldValue(){
		$this->pkey = "id";
		$this->tbl = "_crm_fields_value";
	}
	function getValue($crm_field_id,$potential_id,$notFound=null){
		global $clsISO;
		$res = $this->GetAll("crm_field_id='{$crm_field_id}' and potential_id='{$potential_id}' limit 0,1","field_value");
		if(!empty($res))
			return $res[0]['field_value'];
		return is_null($notFound) ? __('Notassigned') : $notFound;
	}
	function updateValue($crm_field_id, $potential_id, $value){
		global $core, $dbconn, $clsISO;
		$clsCRMField = new CRMField();
		$field_type = $clsCRMField->getFieldValue("type",$crm_field_id);
		$lstcheck = $this->GetAll("crm_field_id='{$crm_field_id}' and potential_id='{$potential_id}' limit 0,1", $this->pkey);
		if(!empty($lstcheck)){
			$this->updateOne($lstcheck[0][$this->pkey], array(
				'field_value'	=> $value,
				'field_type'	=> $field_type
			));
		}else{
			$this->insert(array(
				'id'	=> $this->getMaxId(),
				'crm_field_id'	=> $crm_field_id,
				'potential_id'	=> $potential_id,
				'field_type'	=> $field_type,
				'field_value'	=> $value
			));
		}
	}
}
?>