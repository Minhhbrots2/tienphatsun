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
class Automation extends dbBasic{
	function __construct(){
		$this->pkey = "automation_id";
		$this->tbl = DB_PREFIX."automation";
	}
	function getTitle($pvalTable){
		$one=$this->getOne($pvalTable);
		return $one['title'];
	}
	function getContent($pvalTable, $oDataTable=array()){
		if(!isset($oDataTable['content'])){
			$oDataTable = $this->getOne($pvalTable, "content");
		}
		return html_entity_decode($oDataTable['content']);
	}
	function getSelect($selected = '') {
        global $core,$profile_id;
		$cond .= "`user_id`='{$profile_id}' OR (`is_share` = 1) ";
		$lstTemplate = $this->getAll($cond,$this->pkey.',title');
        #
        $html = '<option value="">-- Chọn mẫu --</option>';
        foreach ($lstTemplate as $key => $val) {
            $selected_index = ($selected == $val[$this->pkey]) ? 'selected="selected"' : '';
            $html .= '<option value="'.$val[$this->pkey].'" '.$selected_index.'>'.$val["title"].'</option>';
        }
        return $html;
    }
}
?>