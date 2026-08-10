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
class Folder extends dbBasic{
	function __construct(){
		$this->pkey = "folder_id";
		$this->tbl = DB_PREFIX."folder";
	}
	function getLink($folder_id,$one=null) {
		if(!isset($one['slug'])) {
			$one = $this->getOne($folder_id,"slug");
		}
		return "/van-ban-he-thong/folder/".$one['slug']."-d".$folder_id.".html";
	}
}