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
class Info extends dbBasic{
	function Info(){
		$this->pkey = "info_id";
		$this->tbl = DB_PREFIX."info";
	}
	function countByCountry($country_id){
		$sql="is_trash=0 and country_id='$country_id'";
		return $this->countItem($sql);
	}
	function countByCity($city_id){
		$sql="is_trash=0 and city_id='$city_id'";
		return $this->countItem($sql);
	}
	function getTitle($pval){
		$one = $this->getOne($pval);
		return $one['title'];
	}
	function getSlug($pval){
		$one = $this->getOne($pval);
		return $one['slug'];
	}
	function getBySlug($slug){
		$res = $this->getAll("is_trash=0 and (slug_vn='".$slug."' or slug_en='".$slug."')");
		return $res[0]['info_id'];
	}
	function getByAttraction($slug){
		$res = $this->getAll("is_trash=0 and (slug_vn='".$slug."' or slug_en='".$slug."')");
		return $res[0]['pval'];
	}
	function getIntro($pval){
		$res = $this->getOne($pval);
		return $res['intro']; 
	}
	function getContent($pval){
		$res = $this->getOne($pval);
		return html_entity_decode($res['content']); 
	}
	function getStripContent($pval){
		$res = $this->getOne($pval);
		return strip_tags(html_entity_decode($res['content'])); 
	}
	function getImage($pval,$w,$h){ 
		$one = $this->getOne($pval);
		print_r($one['image']);
	}
	function getLink($pval){
		$clsCountry=new Country();
		
		$oneItem=$this->getOne($pval);
		return '/destinations/'.$clsCountry->getSlug($oneItem['country_id']).'-travel-tips/'.$this->getSlug($pval).'.html';
	}
}
?>

