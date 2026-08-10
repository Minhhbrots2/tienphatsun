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
class Gallery extends dbBasic{
	function __construct(){
		global $_LANG_ID;
		$this->pkey = "gallery_id";
		$this->tbl = DB_PREFIX."gallery";
	}
	function getTitle($gallery_id){
		global $_LANG_ID;
		$one=$this->getOne($gallery_id,'title_'.$_LANG_ID);
		return $one['title_'.$_LANG_ID];
	}
	function getSlug($gallery_id){
		global $_LANG_ID;
		$one=$this->getOne($gallery_id, 'slug_'.$_LANG_ID);
		return $one['slug_'.$_LANG_ID];
	}
	function getLink($gallery_id){
		global $_LANG_ID, $extLang;
		if($_LANG_ID=='en'){
			return '/gallery/'.$this->getSlug($gallery_id).'.html';
		}else{
			return '/gallery/'.$this->getSlug($gallery_id).'.html';
		}
	}
	function getImage($pvalTable, $w, $h, $oDataTable=array()){
		global $clsISO;
		if(!isset($oDataTable['image'])){
			$oDataTable = $this->getOne($pvalTable, "image");
		}
		$image = $oDataTable['image'];
		if(!empty($image)){
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		return URL_IMAGES.'/noimage.png';
	}
	function getItems($gallery_id, $type='_GALLERY'){
		$field = "*";
		$clsImage = new Image();
		return $clsImage->getAll("is_trash=0 and type='{$type}' and table_id='{$gallery_id}' order by order_no ASC", $field);
	}
	function countPhoto($gallery_id){
		$clsImage = new Image();
		return $clsImage->countItem("is_trash=0 and type='_GALLERY' and table_id='$gallery_id'");
	}
	function getIntro($gallery_id){
		global $_LANG_ID, $extLang;
		return $this->getOneField('intro_'.$_LANG_ID, $gallery_id);
	}
}
?>
