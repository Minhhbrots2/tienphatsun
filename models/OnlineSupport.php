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
class OnlineSupport extends dbBasic{
	function OnlineSupport(){
		$this->pkey = "online_support_id";
		$this->tbl = DB_PREFIX."online_support";
	}
	function getListType(){
		$lstType = array();
		$lstType['_YAHOO'] = 'Yahoo';
		$lstType['_SKYPER'] = 'Skype';
		$lstType['_PHONE'] = 'Phone';
		return $lstType;
	}
	function makeSelectbox($selected=''){
		$lstType = $this->getListType();
		$html = '<option value=""><< Select >></option>';
		foreach($lstType as $k=>$v){
			$html .= '<option value="'.$k.'" '.($selected==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		return $html; die();
	}
	function checkOnlineAvaiable(){
		$res = $this->getAll("is_trash=0");
		return !empty($res)?1:0;	
	}
}
?>