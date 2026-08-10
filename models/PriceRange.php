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
class PriceRange extends dbBasic{
	function __construct(){
		$this->pkey = "price_range_id";
		$this->tbl = DB_PREFIX."price_range";
	}
	function getMin($tour_price_range_id){
		$one = $this->getOne($tour_price_range_id);
		return number_format($one['min_rate']);
	}
	function getTitle($tour_price_range_id){
		$one = $this->getOne($tour_price_range_id);
		return $one['title'];
	}
	function getMax($tour_price_range_id){
		$one = $this->getOne($tour_price_range_id);
		return number_format($one['max_rate']);
	}
	function getSelectByPrice($selected=''){
		global $core, $_lang;
		#
		$all=$this->getAll("is_trash=0 order by order_no asc");
		$html='<option value="">-- Khoảng giá --</option>';
		if(!empty($all)){
			$i=0;
			foreach($all as $item){
				$selected_index=($selected==$item[$this->pkey])?'selected="selected"':'';
				$html.='<option value="'.$item[$this->pkey].'" '.$selected_index.'>-- '.$this->getTitle($item[$this->pkey]).' --</option>';
				++$i;
			}
		}
		return $html;
	}
}
?>