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
class Post extends dbBasic{
	function __construct(){
		$this->pkey = "post_id";
		$this->tbl = DB_PREFIX."post";
	}
	function getTitle($pval,$_args=array()){
		if(!isset($_args['title'])){
			$_args = $this->getOne($pval,"title");
		}
		return $_args['title'];
	}
	function getSlug($pvalTable){
		$one=$this->getOne($pvalTable);
		return $one['slug'];
	}
	function getLink( $pvalTable){
		global $extLang, $_LANG_ID;
		return $extLang.'/thong-tin/'.$this->getSlug($pvalTable).'.html';
	}
}