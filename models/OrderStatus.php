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
class OrderStatus extends dbBasic{
	function OrderStatus(){
		$this->pkey = "order_status_id";
		$this->tbl = DB_PREFIX."order_status";
	}
	function getTitle($order_status_id){
		$one = $this->getOne($order_status_id,"title");
		return $one['title'];
	}
	function getColor($order_status_id){
		$one = $this->getOne($order_status_id,"color");
		return $one['color'];
	}
	function getPrefix($order_status_id){
		$one = $this->getOne($order_status_id,"prefix");
		return $one['prefix'];
	}
	function getIntro($order_status_id){
		$one = $this->getOne($order_status_id,"intro");
		return $one['intro'];
	}
	function makeSelectBox($order_status_id=""){
		global $core, $dbconn;
		$html = '<option value="">- Trạng thái -</option>';
		$lstItem = $this->GetAll("is_trash=0");
		if(!empty($lstItem)){
			foreach($lstItem as $status){
				$html .= '<option value="'.$status[$this->pkey].'"> '.$this->getTitle($status[$this->pkey]).'</option>';
			}
			unset($lstItem);
		}
		return $html;
	}
}
?>