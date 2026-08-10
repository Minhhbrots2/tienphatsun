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
class City extends dbBasic {
    function __construct(){
		$this->pkey = "city_id";
		$this->tbl = DB_PREFIX."city";
	}
	function getMaxOrderNo($country_id){
		$res = $this->getAll("1=1 and country_id='$country_id' order by order_no DESC LIMIT 0,1", "order_no");
		return intval($res[0]['order_no'])+1;
	}
	function getTitle($city_id, $oDataTable = array()){
		global $_LANG_ID;
		if(!isset($oDataTable['title'])){
			$oDataTable = $this->getOne($city_id);
		}
		return $oDataTable['title'];
	}
	function getImage($city_id){
		global $clsISO;
		$one = $this->getOne($city_id);
		if($one['image']!='')
			return $one['image'];
		return URL_IMAGES.'/noimage.png';
	}
	function getIntro($city_id){
		global $_LANG_ID;
		$one = $this->getOne($city_id,"intro");
		return html_entity_decode($one['intro']);
	}
	function getSlug($city_id){
		global $_LANG_ID;
		$one = $this->getOne($city_id);
		return $one['slug'];
	}
	function getRegDate($country_id){
		$one = $this->getOne($country_id,"reg_date");
		return date('d-m-Y h:i', $one['reg_date']);
	}
	function makeSelectOption($country_id,$selected='0'){
		global $core, $_lang;
		$html = '<option value="0">Tỉnh/Thành phố</option>';
		if($country_id > 0){
			$cond = "is_trash=0 and country_id ='{$country_id}'";
			$tmp =$this->getAllCache("{$cond} order by order_no asc", "{$this->pkey},title");
			if(!empty($tmp)){
				foreach($tmp as $city){
					$city_id = $city[$this->pkey];
					$html.='<option value="'.$city_id.'"'.($city_id==$selected?' selected':'').'>
						'.$this->getTitle($city_id, $city).'
					</option>';
				}
				unset($tmp);
			}
		}
		return $html;
	}
}
?>