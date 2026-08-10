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
class Help extends DbBasic{
	function __construct(){
		$this->pkey = "setting";
		$this->tbl = DB_PREFIX."help";
	}
	function getValue($key){
		global $dbconn;
		$sql = "select * from ".$this->tbl." where setting='$key'";
		$lst = $dbconn->GetAll($sql); 
		if(isset($lst[0]['setting']))
			if($lst[0]['setting']==$key) return html_entity_decode($lst[0]['value']);
		return '';
	}
	function updateValue($key,$val){
		global $dbconn;
		$sql = "select setting from ".$this->tbl." where setting='$key'";
		$lst = $dbconn->GetAll($sql); 
		if($lst[0]['setting']==$key){
			$this->updateByCond("setting='$key'","value='".addslashes($val)."'");
		}
		else{
			$this->insertOne("setting,value","'$key','".addslashes($val)."'");
		}
		return ''; 
	}
} 
?>