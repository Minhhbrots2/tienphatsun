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
class District extends dbBasic{
	function __construct(){
		$this->pkey = "district_id";
		$this->tbl = DB_PREFIX."district";
	}
	function getMaxOrderNo($city_id){
		$res = $this->getAll("1=1 and city_id='$city_id' order by order_no DESC LIMIT 0,1", "order_no");
		return intval($res[0]['order_no'])+1;
	}
	function getTitle($district_id){
		global $_LANG_ID;
		$one = $this->getOne($district_id);
		return $one['title'];
	}
	function getImage($district_id){
		global $clsISO;
		$one = $this->getOne($district_id);
		if($one['image']!='')
			return $one['image'];
		return URL_IMAGES.'/noimage.png';
	}
	function getIntro($district_id){
		global $_LANG_ID;
		$one = $this->getOne($district_id,"intro");
		return html_entity_decode($one['intro']);
	}
	function getSlug($district_id){
		global $_LANG_ID;
		$one = $this->getOne($district_id);
		return $one['slug'];
	}
	function getRegDate($district_id){
		$one = $this->getOne($district_id,"reg_date");
		return date('d-m-Y h:i', $one['reg_date']);
	}
	function makeSelectOption($city_id,$selected=''){
		global $core, $_lang;
		#
		$tmp=$this->getAll("is_trash=0 and city_id = '$city_id' order by order_no asc");
		$html='<option value="">Quận/Huyện</option>';
		if(!empty($tmp)){
			foreach($tmp as $item){
				$sltc =($selected==$item[$this->pkey])?' selected="selected"':'';
				$html.='<option value="'.$item[$this->pkey].'"'.$sltc.'>'.$this->getTitle($item[$this->pkey]).'</option>';
			}
			unset($tmp);
		}
		return $html;
	}
}
?>