<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class AdsGroup extends dbBasic{
	function __construct(){
		global $_LANG_ID;
		$this->pkey = "ads_group_id";
		$this->tbl = DB_PREFIX."ads_group";
	}
	function getTitle($pval) {
		$one = $this->getOne($pval);
		return $one['title'];
	}
	function getIntro($pval) {
		$one = $this->getOne($pval);
		return $one['intro'];
	}
	function getSize($pval){
		$one = $this->getOne($pval);
		return $one['_width'].'x'.$one['_height'];
	}
	function getCode($pval){
		$one = $this->getOne($pval);
		return $one['_code'];
	}
	function makeSelectOption($selected=""){
		$lstItem = $this->getAll("is_trash=0 and parent_id='0' order by order_no ASC");
		$html = '<option value="0">-- Lựa chọn --</option>';
		if(!empty($lstItem)){
			foreach($lstItem as $item){
				$sl = $item[$this->pkey]==$selected ? 'selected="selected"' : '';
				$html.='<option value="'.$item[$this->pkey].'" '.$sl.'>'.$this->getTitle($item[$this->pkey]).'</option>';
			}
		}
		return $html;
	}
	function getChild($ads_group_id=0){
		$res = $this->getAll("is_trash=0 and parent_id='$ads_group_id' order by order_no ASC");
		return $res;
	}
	function checkAds($ads_group_id, $ads_id){
		$clsAds = new Ads();
		#
		$one = $clsAds->getOne($ads_id);
		$list_id = $one['list_id'];
		if($ads_group_id=='' || $list_id==''){ return 0;}
		#
		$pos = strpos($list_id,'|'.$ads_group_id.'|');
		if($pos === false) {
			return 0;
		}else{
			return 1;
		}	
	}
	function doDelete($ads_group_id){
		$all = $this->getAll("is_trash=0 and parent_id='$ads_group_id'");
		if(is_array($all) && count($all)>0){
			foreach($all as $k=>$v){
				$this->doDelete($v[$this->pkey]);
			}
		}
		$this->deleteOne($ads_group_id);
	}
}
?>