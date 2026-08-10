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
class Booking extends dbBasic{
	function __construct(){
		$this->pkey = "booking_id";
		$this->tbl = DB_PREFIX."booking";
	}
	function getCode($booking_type = _BOOKING_TYPE_INTERNAL_ID, $block_id = 0, $reg_date){
		global $core, $dbcoon, $clsISO;
		$clsProperty = new Property();
		$property_code = $clsProperty->getOneField("property_code", $block_id);
		$total_bookings = $this->countItem("`booking_type`='{$booking_type}' AND `block_id`='{$block_id}' AND `reg_date`<{$reg_date}") + 1;
		if($total_bookings < 10) $code = '0000'.$total_bookings;
		if($total_bookings >= 10 && $total_bookings < 100) $code = '000'.$total_bookings;
		if($total_bookings >= 100 && $total_bookings < 1000) $code = '00'.$total_bookings;
		if($total_bookings >= 1000 && $total_bookings < 10000) $code = '0'.$total_bookings;
		if($total_bookings >= 10000 && $total_bookings < 100000) $code = $total_bookings;
		if($booking_type == _BOOKING_TYPE_INTERNAL_ID){ #BK-PARKLAND-2025/12-0001
			return sprintf('#BK.%s-%s/%s-%s', $property_code, date('Y', $reg_date), date('m', $reg_date), $code);
		} else {
			return sprintf('#BKCĐT.%s-%s/%s-%s', $property_code, date('Y', $reg_date), date('m', $reg_date), $code);
		}
	}
	function short_content($content, $len=40){
		if ($content === null || $content === '') {
			return '';
		}
		$content = trim($content);
		$length = mb_strlen($content, 'UTF-8');
		if ($length <= $len) {
			return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
		}
		$short = mb_substr($content, 0, $len, 'UTF-8');
		return htmlspecialchars($short, ENT_QUOTES, 'UTF-8') . ' <a data-toggle="webui-popover" 
			class="btn btn-xs btn-icon btn-outline-default text-link" 
			data-content="' . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . '">+</a>';
	}
	function get_html_floor_type($floor_type){
		global $core, $dbcoon, $clsISO;
		if($floor_type == 'low_floor')
			return '<span class="badge bg-label-primary">Tầng thấp</span>';
		else if($floor_type == 'mid_floor')
			return '<span class="badge bg-label-warning">Tầng trung</span>';
		else if($floor_type == 'high_floor')
			return '<span class="badge bg-label-danger">Tầng cao</span>';
	}
	function getFloorType($floor_type=""){
		$arrs = array(
			'low_floor' => 'Tầng thấp',
			'mid_floor' => 'Tầng trung',
			'high_floor' => 'Tầng cao'
		);
		return $arrs[$floor_type];
	}
	function getFloorOption($floor_type=""){
		$html = "";
		$arrs = array(
			'low_floor' => 'Tầng thấp',
			'mid_floor' => 'Tầng trung',
			'high_floor' => 'Tầng cao'
		);
		foreach($arrs as $key => $val){
			$html.= '<option'.($floor_type==$key ? ' selected' : '').' value="'.$key.'">'.$val.'</option>';
		}
		return $html;
	}
	function getLinkBooking($project_id,$block_id){
		return sprintf("/booking/p%s-bl%s.html",$project_id,$block_id);
	}
	function getArrayFloor($config){
		global $clsISO;
		$arr_floor = [];
		if($config["floor_type"] == "consecutive") {
			for($i=$config["from"]; $i <= $config["to"]; $i ++) {
				$arr_floor[] = $clsISO->parseNumber($i);
			}
		}else{
			$floors = explode(",",$v_config["floor"]);
			foreach ($floors as $floor) {
				$arr_floor[] = $clsISO->parseNumber($floor);
			}
		}
		return $arr_floor;
	}
}
?>