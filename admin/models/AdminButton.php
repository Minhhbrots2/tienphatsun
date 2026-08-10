<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class AdminButton extends dbBasic{
	function __construct(){
		$this->pkey = "adminbutton_id";
		$this->tbl = DB_PREFIX."adminbutton";
	}
	function getTitle($adminbutton_id){
		global $_LANG_ID;
		$one = $this->getOne($adminbutton_id);
		return $one['title'];
	}
	function getMenu($_type){
		return $this->getAll("_type='".$_type."' order by order_no asc");
	}
	function getURL($adminbutton_id){
		global $_LANG_ID;
		$one = $this->getOne($adminbutton_id, "url_page,mod_page");
		if(!empty($one)){
			$url_page = $one['url_page'];
			$mod_page = $one['mod_page'];
			if(!empty($url_page))
				return ($url_page=='javascript:void(0);')?'javascript:void(0);' : PCMS_URL.'/?'.$url_page;
			return PCMS_URL.'/?mod='.$mod_page;
		}
		return 'javascript:void(0)';
	}
	function getRootURL($adminbutton_id){
		$one = $this->getOne($adminbutton_id, "url_page,mod_page");
		if(!empty($one)){
			$url_page = $one['url_page'];
			$mod_page = $one['mod_page'];
			if(!empty($url_page))
				return ($url_page=='javascript:void(0);')?'javascript:void(0);' : PCMS_URL.'/?'.$url_page;
		}
		return 'javascript:void(0)';
	}
	function getChild($adminbutton_id){
		return $this->getAll("is_trash=0 and is_active=1 and parent_id='{$adminbutton_id}' order by order_no asc");
	}
	function checkDEV($adminbutton_id){
		if($this->getOneField('dev_access',$adminbutton_id)){
			return (_DEV) ? 1 : 0;
		}
		return 1;
	}
	function checkConfiguration($CONFIGURATION_KEY){
		global $clsConfig;
		if(!empty($CONFIGURATION_KEY)){
			$res = $clsConfig->get($CONFIGURATION_KEY);
			return $res ?? 0;
		}
		return 1;
	}
	function doDelete($adminbutton_id){
		$lstItem = $this->getAll("parent_id='$adminbutton_id'");
		if(is_array($lstItem) && count($lstItem) > 0){
			for($i=0; $i<count($lstItem); $i++){
				$this->doDelete($lstItem[$i][$this->pkey]);
			}
			unset($lstItem);
		}
		$this->deleteOne($adminbutton_id);
		#
		return 1;
	}
}
?>