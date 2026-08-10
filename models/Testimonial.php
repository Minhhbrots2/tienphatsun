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
class Testimonial extends dbBasic{
	function __construct(){
		$this->pkey = "testimonial_id";
		$this->tbl = DB_PREFIX."testimonial";
	}
	function getTitle($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['title'] != ''){
			return $_args['title'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['title'];
		}
	}
	function getSlug($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['slug'] != ''){
			return $_args['slug'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['slug'];
		}
	}
	function getAddress($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['address'] != ''){
			return $_args['address'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['address'];
		}
	}
	function getIntro($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['intro'] != ''){
			return html_entity_decode($_args['intro']);
		}else{
			$one=$this->getOne($pvalTable);
			return html_entity_decode($one['intro']);
		}
	}
	function getContent($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['content'] != ''){
			return html_entity_decode($_args['content']);
		}else{
			$one=$this->getOne($pvalTable);
			return html_entity_decode($one['content']);
		}
	}
	function getStripIntro($pvalTable){
		$one=$this->getOne($pvalTable);
		if(!empty($one['intro']))
			return strip_tags(html_entity_decode($one['intro']));
		return strip_tags(html_entity_decode($one['content']));
	}
	function getRegDate($pvalTable) {
		$one=$this->getOne($pvalTable);
		return date('m/d/Y',$one['reg_date']);
	}
	function getLink($pvalTable){
		global $extLang;
		return $extLang.'/testimonials/'.$this->getSlug($pvalTable).'.html';
	}
	function getName($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['name'] != ''){
			return $_args['name'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['name'];
		}
	}
	function getCountry($pvalTable){
		global $_LANG_ID;
		$clsCountry = new _Country();
		$one=$this->getOne($pvalTable);
		return $clsCountry->getTitle($one['country_id']);
	}
	function getImage($pvalTable,$w,$h){
		global $clsISO;
		#
		$oneTable = $this->getOne($pvalTable, "image");
		if($oneTable['image']!=''){
			$image = $oneTable['image'];
			return '/files/thumb/'.$w.'/'.$h.'/'.$image;
		}
		return URL_IMAGES.'/noimage.png';
	}
	function getImageUrl($pvalTable){
		$one = $this->getOne($pvalTable);
		return $one['image'];
	}
	function doDelete($pvalTable){
		// Delete
		$this->deleteOne($pvalTable);
		return 1;
	}
}
?>