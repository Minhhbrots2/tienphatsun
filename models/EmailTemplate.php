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
class EmailTemplate extends dbBasic{
	function __construct(){
		$this->pkey = "email_template_id";
		$this->tbl = DB_PREFIX."email_template";
	}
	function getMaxId(){
		$res = $this->getAll("1=1 order by email_template_id desc");
		return intval($res[0]['email_template_id'])+1;
	}
	function getMaxOrderNo(){
		$all=$this->getAll("1=1 order by order_no desc");
		return intval($all[0]['order_no'])+1;
	}
	function getTitle($pvalTable){
		$one=$this->getOne($pvalTable);
		return $one['title'];
	}
	function getFromName($pvalTable, $oDataTable=array()){
		if(!isset($oDataTable['fromname'])){
			$oDataTable = $this->getOne($pvalTable, "fromname");
		}
		return $oDataTable['fromname'];
	}
	function getFromEmail($pvalTable){
		if(!isset($oDataTable['fromemail'])){
			$oDataTable = $this->getOne($pvalTable, "fromemail");
		}
		return $oDataTable['fromemail'];
	}
	function getSubject($pvalTable, $oDataTable=array()){
		if(!isset($oDataTable['subject'])){
			$oDataTable = $this->getOne($pvalTable, "subject");
		}
		return html_entity_decode($oDataTable['subject']);
	}
	function getContent($pvalTable, $oDataTable=array()){
		if(!isset($oDataTable['content'])){
			$oDataTable = $this->getOne($pvalTable, "content");
		}
		return html_entity_decode($oDataTable['content']);
	}
	function getCopyTo($pvalTable, $oDataTable = array()){
		if(!isset($oDataTable['copyto'])){
			$oDataTable = $this->getOne($pvalTable, "copyto");
		}
		$copyto = $oDataTable['copyto'];
		if(!empty($copyto))
			return explode(',', $copyto);
		return array();
	}
	function getListType(){
		$_array = array();
		$_array['_CONTACT'] = 'Mẫu E-Mail Liên hệ';
		$_array['_NEWSLETTER'] = 'Mẫu E-Mail Nhận bản tin';
		$_array['_CUSTOMIZE_TOUR'] = 'Mẫu E-Mail Customize Tour';
		$_array['_BOOKING_TOUR'] = 'Mẫu E-Mail Booking Tour';
		$_array['_BOOKING_HOTEL'] = 'Mẫu E-Mail Booking Hotel';
		return $_array;
	}
	function makeSelectboxOption($selected=''){
		$lstType = $this->getListType();
		$html = '<option value=""><< Lựa chọn >></option>';
		foreach($lstType as $key=>$val){
			$sl = ($key==$selected)?'selected="selected"':'';
			$html.='<option value="'.$key.'" '.$sl.'>'.$val.'</option>';
		}
		return $html;
	}
}
?>