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
class Page extends dbBasic{
	function __construct(){
		$this->pkey = "page_id";
		$this->tbl = DB_PREFIX."page";	
	}
	function getTitle($page_id, $oDataTable=array()){
		global $_LANG_ID;
		if(!isset($oDataTable['title'])){
			$oDataTable = $this->getOne($page_id, "title");
		}
		return $oDataTable['title'];
	}
	function updateLink($page_id){
		global $core, $extLang, $_LANG_ID;
		if($_LANG_ID=='vn'){
			$link = '/pt/'.$this->getOneField('slug', $page_id).'.html';
		} else {
			$link = $extLang.'/pt/'.$this->getOneField('slug', $page_id).'.html';
		}
		$this->updateOne($page_id,"link='".addslashes($link)."'");
	}
	function getImageUrl($page_id){
		$oneTable = $this->getOne($page_id, "image");
		return $oneTable['image'];
	}
	function getLink($page_id){
		$one=$this->getOne($page_id);
		return $one['link'];
	}
	function getUrl($page_id){
		$one=$this->getOne($page_id, "link");
		return $one['link'];
	}
	function getStripIntro($page_id, $oDataTable=array()){
		global $_LANG_ID;
		if(!isset($oDataTable['short_intro'])){
			$oDataTable=$this->getOne($page_id, "short_intro");
		}
		return strip_tags(html_entity_decode($oDataTable['short_intro']));
	}
	function getIntro($page_id){
		global $_LANG_ID;
		$one=$this->getOne($page_id);
		return html_entity_decode($one['intro']);
	}
	function checkContain($haystack,$needle){
		if($needle==''){ return 0;}
		if(strpos($haystack,$needle)===FALSE){
			return 0;
		}else{
			return 1;
		}
	}
}
?>