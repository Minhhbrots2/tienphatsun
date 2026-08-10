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
class TemplateTag extends dbBasic{
	function TemplateTag(){
		$this->pkey = "template_tag_id";
		$this->tbl = DB_PREFIX."template_tag";
	}
	function getMaxOrderNo(){
		$all=$this->getAll("1=1 order by order_no desc");
		return intval($all[0]['order_no'])+1;
	}
	function getTitle($pval){
		global $_LANG_ID;
		$one=$this->getOne($pval);
		return $one['title'];
	}
	function getReplaceTag($pval){
		global $_LANG_ID;
		$one=$this->getOne($pval);
		return $one['replace_tag'];
	}
}
?>