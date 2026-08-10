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
class Partner extends dbBasic{
	function Partner(){
		$this->pkey = "partner_id";
		$this->tbl = DB_PREFIX."partner";
	}
	function getTitle($pval,$_args=array()){
		if(is_array($_args) && $_args[$this->pkey] > 0){
			return $_args['title'];
		}else{
			$one = $this->getOne($pval,"title");
			return $one['title'];
		}
	}
	function getSlug($pval,$_args=array()){
		if(is_array($_args) && $_args[$this->pkey] > 0){
			return $_args['slug'];
		}else{
			$one=$this->getOne($pval,"slug");
			return $one['slug'];
		}
	}
	function getUrl($pval,$_args=array()){
		if( is_array($_args) && $_args[$this->pkey] > 0){
			return $_args['link'];
		} else {
			$one=$this->getOne($pval,"link");
			return $one['link'];
		}
		return 'javascript:void(0);';
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
	function getImageUrl($pval){
		$one = $this->getOne($pval,"image");
		return $one['image'];
	}
	function doDelete($pvalTable){
		// Delete
		$this->deleteOne($pvalTable);
		return 1;
	}
}
?>