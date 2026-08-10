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
class NewsBlock extends dbBasic{
	function __construct(){
		$this->pkey = "news_block_id";
		$this->tbl = DB_PREFIX."news_block";
	}
	function getByType($type,$limit){
		$all = $this->getAll("type='{$type}' order by order_no desc limit 0,$limit");
		return $all;
	}
	function checkExist($news_id,$type){
		$one = $this->getAll("news_id='{$news_id}' and type='{$type}' order by order_no asc limit 0,1");
		if($one[0]['news_id']!='')
			return 1;
		return 0;
	}
}
?>