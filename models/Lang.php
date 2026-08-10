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
class _Lang extends dbBasic{
	function _Lang(){
		$this->pkey = "lang_id";
		$this->tbl = DB_PREFIX."lang";
		#Create Table If Not Exist
		$sqlCreate =    $this->pkey." INT(0) NOT NULL AUTO_INCREMENT, 
						`user_id` int(10) NOT NULL,
						`name` varchar(255) NOT NULL default '',
						`val_en` varchar(255) NOT NULL default '',
						`val_fr` varchar(255) NOT NULL default '',
						`is_trash` tinyint(1) NOT NULL";		
		#
		$sqlInit_f = '';
		$sqlInit_v = '';
		$this->createTableDB($sqlCreate,$sqlInit_f,$sqlInit_v);
		#End Create
	}	
	function get_Lang($key){
		global $_LANG_ID;
		$one = $this->getAll("name='$key'");
		$ret = $one[0]['val_'.$_LANG_ID]!=''?$one[0]['val_'.$_LANG_ID]:'['.$key.']';
		return $ret;
	}
}
?>