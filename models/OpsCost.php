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
class OpsCost extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."ops_cost";
	}
	function short_content($content, $len=60){
		if(!empty($content)){
			$length = @strlen($content);
			if($length > $len){
				$truncate = @mb_substr($content, 0, 50, 'UTF-8');
				return $truncate. '...<a data-toggle="webui-popover" class="text-link" data-content="'.$content.'">+ Xem thêm</a>';
			} else {
				return $content;
			}
			
		} else {
			return "";
		}
	}
}
?>