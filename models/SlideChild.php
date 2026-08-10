<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (luongtiendung@gmail.com)     # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class SlideChild extends dbBasic{
	function __construct(){
		$this->pkey = "slide_id";
		$this->tbl = DB_PREFIX."slide_child";
	}
	function getTitle($slide_id){
		$one = $this->getOne($slide_id,"title");
		return $one['title'];
	}
	function getSlug($slide_id){
		$one=$this->getOne($slide_id,"slug");
		return $one['slug'];
	}
	function getText($slide_id){
		$one=$this->getOne($slide_id,"text");
		return $one['text'];
	}
	function getLargeText($slide_id){
		$one=$this->getOne($slide_id,"large_text");
		return html_entity_decode($one['large_text']);
	}
	function getSmallText($slide_id){
		$one=$this->getOne($slide_id,"small_text");
		return html_entity_decode($one['small_text']);
	}
	function getUrl($slide_id){
		$one=$this->getOne($slide_id,"link");
		return $one['link'];
	}
	function getLink($slide_id){
		$one=$this->getOne($slide_id,"link");
		return $one['link'];
	}
	function getType($slide_id){
		$one=$this->getOne($slide_id,"type");
		return $one['type'];
	}
	function getModPage($slide_id){
		$one=$this->getOne($slide_id,"mod_page");
		return $one['mod_page'];
	}
	function getImage($slide_id,$w,$h){
		$one = $this->getOne($slide_id,"image");
		return $one['image'];
	}
	function getImageUrl($slide_id){
		$one = $this->getOne($slide_id,"image");
		return $one['image'];
	}
	function doDelete($pvalTable){
		// Delete
		$this->deleteOne($pvalTable);
		return 1;
	}
	function getListModPage(){
		global $core;
		$lstModule = array();
		$lstModule['course'] = $core->get_Lang('Course');
		$lstModule['responsibility'] = $core->get_Lang('Responsibility');
		$lstModule['scatter'] = $core->get_Lang('Scatter');
		$lstModule['service'] = $core->get_Lang('Service');
		$lstModule['news'] = $core->get_Lang('News');
		$lstModule['product'] = $core->get_Lang('Shop cà phê');
		$lstModule['about_default'] = $core->get_Lang('About');
		$lstModule['about_contact'] = $core->get_Lang('Contact');
		return $lstModule;
	}
}
?>