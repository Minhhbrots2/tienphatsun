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
class Client extends dbBasic{
	function __construct(){
		$this->pkey = "client_id";
		$this->tbl = DB_PREFIX."client";	
	}
	function getLink($id,$one=null){
		global $clsISO;
		if(!isset($one['slug'])){
			$one = $this->getOne($client_id,"slug");
		}
		return "/khach-hang/".$one['slug']."-c".$id.".html";
	}
	function getCharFirst($str){
		$arr_str = explode(" ",$str);
		$end = end($arr_str);
		return $end[0];
	}
}