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
class CRMField extends dbBasic{
	function CRMField(){
		$this->pkey = "crm_field_id";
		$this->tbl = "_crm_fields";
	}
	function getListRequiedField(){
		return array(
			'name', // Tên
			'email', // Email
			'phone' // Số điện thoại
		);
	}
	function getId($fieldname){
		$lstCRMField = $this->GetAll("is_trash=0 and status='1' and is_extra='1' limit 0,1", $clsCRMField->pkey.",value");
		if(!empty($lstCRMField)){
			foreach($lstCRMField as $field){
				$value = json_decode($field['value'], true);
				if($value['fieldname']==$fieldname){
					return $field[$this->pkey];
					break;
				}
			}
		}
	}
	function getFieldValue($field, $crm_field_id, $value=null){
		if(is_null($value)){ // check null
			$value = $this->getOneField('value',$crm_field_id);
		}
		$value = !empty($value) ? @json_decode($value, true) : array();
		return isset($value[$field]) ? $value[$field] : false;
	}
	function renderHTMLSimple($crm_field_id, $opts, $value, $cls, $width='100%'){
		global $core, $clsISO;
		$type = $opts['type'];
		$required = $opts['required'];
		if($type=='text'){
			$html = '<input type="text" class="'.$cls.'" value="'.$value.'" name="'.$opts['fieldname'].'" style="width:'.$width.'" />';
		} else if($type=='textarea'){
			$html = '<textarea class="'.$cls.'" value="'.$value.'" name="'.$opts['fieldname'].'" style="width:'.$width.'">'.$value.'</textarea>';
		} else if($type=='checkbox'){
			$html = '<input type="checkbox" class="'.$cls.'" '.($required?'required':'').' value="1" />';
		} else if($type=='select'){
			$options = @json_decode($opts['options'], true);
			$html .= '<select class="'.$cls.'" name="'.$opts['fieldname'].'" style="width:'.$width.'">';
			for($i=0; $i<count($options); $i++){
				$html .= '<option value="'.$options[$i].'">'.$options[$i].'</option>';
			}
			$html .= '</select>';
		}
		return $html;
	}
	function renderHTMLTag($crm_field_id, $opts, $cls="vietiso-input",$prefix=false){
		global $core, $clsISO;
		$type = $opts['type'];
		$required = $opts['required'];
		$is_extra = isset($opts['is_extra']) ? $opts['is_extra'] : 0;
		$prefix = ($is_extra && $prefix) ? 'iso-' : '';
		#
		$html = '<input type="hidden" name="hid_'.$opts['fieldname'].'" value="'.CRM::encryptID($crm_field_id).'" />';
		if($type=='text'){
			$html.= '<input type="text" autocomplete="off" class="'.$cls.'"'.($required?'required':'').''.(!empty($opts['validation'])?' validation="'.$opts['validation'].'"':'').' placeholder="'.$opts['placeholder'].'" name="'.$prefix.$opts['fieldname'].'"/>';
		} else if($type=='textarea'){
			$html.= '<textarea autocomplete="off" class="'.$cls.'"'.($required?'required':'').' placeholder="'.$opts['placeholder'].'" name="'.$prefix.$opts['fieldname'].'"></textarea>';
		} else if($type=='checkbox'){
			$html.= '<input type="checkbox" name="'.$prefix.$opts['fieldname'].'"'.($required?' required':'').' value="1"/>';
		} else if($type=='select'){
			$options = @json_decode($opts['options'], true);
			$html.= '<select class="'.$cls.'"'.($required?' required':'').' name="'.$prefix.$opts['fieldname'].'">';
			for($i=0; $i<count($options); $i++){
				$html .= '<option value="'.$options[$i].'">'.$options[$i].'</option>';
			}
			$html .= '</select>';
		}
		return str_replace('"','\'', $html);
	}
	function getItems($fieldgroup_id, $field="*"){
		return $this->GetAll("fieldgroup_id='{$fieldgroup_id}' and status='1' order by order_no ASC",$field);
	}
}
?>