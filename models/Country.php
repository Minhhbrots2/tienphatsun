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
class Country extends dbBasic{
	function __construct(){
		$this->pkey = "country_id";
		$this->tbl = DB_PREFIX."country";
	}
	function checkSlug($slug){
		global $_LANG_ID;
		$res = $this->getAll("slug_vn='$slug' and slug_en='{$slug}'");
		if(is_array($res) && count($res)>0)
			return 0;
		return 1;
	}
	function getListCity($country_id){
		global $_LANG_ID;
		$clsCity = new City();
		$res = $this->getAll("is_trash=0 and country_id = '{$country_id}' order by order_no desc");
		return $res;
	}
	function getTitle($country_id, $oDataTable=array()){
		global $_LANG_ID;
		$title = 'title_'.$_LANG_ID;
		if(isset($oDataTable[$title])){
			$oDataTable = $this->getOne($country_id,$title);
		}
		return $oDataTable[$title];
	}
	function getSlug($country_id){
		global $_LANG_ID;
		$one = $this->getOne($country_id);
		return $one['slug_'.$_LANG_ID];
	}
	function getRegDate($country_id){
		$one = $this->getOne($country_id,"reg_date");
		return date('d-m-Y h:i', $one['reg_date']);
	}
	function getIntro($country_id){
		global $_LANG_ID;
		$one = $this->getOne($country_id);
		return html_entity_decode($one['intro_'.$_LANG_ID]); 
	}
	function getImage($pvalTable,$width,$height){
		global $_LANG_ID;
		$one = $this->getOne($pvalTable);
		if($one['image_size_'.$width.'_'.$height]!='') {
			return $one['image_size_'.$width.'_'.$height];
		}
		return URL_IMAGES.'/noimage.jpg';
	}
	function makeSelectOption($selected=0){
		global $core, $_LANG_ID;
		$html = '<option value="0">Quốc gia</option>';
		$title = "title_".$_LANG_ID;
		$tmp = $this->getAllCache("is_trash=0 order by order_no ASC", "{$this->pkey},{$title}");
		if(!empty($tmp)){
			foreach($tmp as $country){
				$country_id = $country[$this->pkey];
				$html.='<option value="'.$country_id.'"'.($selected==$country_id?' selected':'').'>
					'.$this->getTitle($country_id, $country).'
				</option>';
			}
			unset($tmp);
		}
		return $html;
	}
	function countNumberCity($country_id){
		$clsCity = new City();
		return $clsCity->countItem("is_trash=0 and country_id='$country_id'");
	}
}
?>