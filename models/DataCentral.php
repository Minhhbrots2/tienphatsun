<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class DataCentral extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."data_central";
	}
	function explode_multi($string){
		if ($string === null || $string === '') {
			return [];
		}
		return array_values(
			array_filter(
				array_map(
					'trim',
					preg_split('/[;&\/]/', $string)
				),
				'strlen'
			)
		);
	}
	function formatPhone($phone){
		// Bỏ hết ký tự không phải số
		$phone = preg_replace('/[^0-9]/', '', $phone);
		// Nếu bắt đầu bằng 84 → đổi thành 0
		if (strpos($phone, '84') === 0) {
			$phone = '0' . substr($phone, 2);
		}
		// Nếu không bắt đầu bằng 0 → thêm 0 vào đầu
		if ($phone !== '' && $phone[0] !== '0') {
			$phone = '0' . $phone;
		}
		// Giữ lại tối đa 10 số
		$phone = substr($phone, 0, 10);
		// Validate: chỉ nhận số di động VN
		if (preg_match('/^(03|05|07|08|09)[0-9]{8}$/', $phone)) {
			return $phone;
		}
		return '';
	}
	function format_stock_code($stock_code){
		$stock_code = strtoupper($stock_code);
		$stock_code = preg_replace('/\s+/', '', $stock_code);
		if(preg_match('/^p/i', $stock_code)){
			$stock_code = str_replace('.', '', $stock_code);
		}
		$stock_code = str_replace('M1','L26M.', $stock_code);
		$stock_code = str_replace('M2','T30.', $stock_code);
		$stock_code = str_replace('M3','L26.', $stock_code);
		$stock_code = str_replace('H1','L27M.', $stock_code);
		$stock_code = str_replace('H2','U38.', $stock_code);
		$stock_code = str_replace('H3','L27.', $stock_code);
		return $stock_code;
	}
	function mask($str, $mask=false){
		if($mask==false) return $str;
		if($str==''){ return '';}
		$len = strlen($str);
		if($len > 6){
			$stat = 2; $end = 6;
			return substr_replace($str,'****',$stat,($end-$stat));
		}
		return $str;
	}
	function getTableField(){
		$arr_fields = [
			"full_name"	 => "Họ và tên",
			"phone"		 => "Điện thoại",
			"phone2"	 => "Điện thoại 2",
			"email"		 => "Email",
			"address"	 => "Địa chỉ",
			"gender"	 => "Giới tính",
			"birthday"	 => "Ngày sinh",
			"ms_code" => "Mã căn"
		];
		return $arr_fields;
	}
	function checkMsCodeSearch($ms_code, $array) {
		foreach ($array as $key => $value) {
			if (strpos($ms_code, $value) === 0) {
				return 1;
			}
		}
		return null;
	}
	function shortMsCode($arr_ms_code,$block_ids=array(),$building_ids=array()){
		$clsProperty = new Property();
		if(!empty($arr_ms_code)) {
			sort($arr_ms_code, SORT_STRING);
			if(!empty($building_ids) || !empty($block_ids)) {
				$cond = "`is_trash`='0' and `for_id` IN (".implode($block_ids).")";
				if(!empty($building_ids)) {
					$cond .= " AND `property_id` IN (".implode($building_ids).")";
				}
				$lstChild = $clsProperty->getAll($cond,$clsProperty->pkey.',title_vn,title,property_code');
				$arr_code = [];
				foreach ($lstChild as $k => $v) {
					$key_name = ($val['parent_id'] == _BLOCK_TYPE_HIGHLEVEL_SALE) ? $v['title_vn'] : $v['property_code'];
					$arr_code[] = $key_name;
				}
				$arr_code_search = $arr_diff = [];
				foreach($arr_ms_code as $key => $ms_code) {
					if($this->checkMsCodeSearch($ms_code,$arr_code)) {
						$arr_code_search[] = $ms_code;
					}else{
						$arr_diff[] = $ms_code;
					}
				}
				return array_merge($arr_code_search,$arr_diff);
			}else{
				return $arr_ms_code;
			}
		}
		return [];
	}
	function getHTMLTags($customer_id, $oDataTable = array(), $openFrom="_list"){
		global $core, $dbconn, $mod, $act, $clsISO, $deviceType;
		$clsTag = new Tag();
		$html_tags = array();
		if(!isset($oDataTable['tags'])){
			$oDataTable = $this->getOne($customer_id, "tags");
		}
		$list_tags_id = $oDataTable['tags'];
		$list_tags_arrs = !empty($list_tags_id) 
			? $clsISO->getArrayByTextSlash($list_tags_id) : array();
		if(!empty($list_tags_arrs)){
			foreach($list_tags_arrs as $tag_id){
				if($tag_id > 0){
					$html_tags[] = sprintf('<span class="label label-default">%s</span>', $clsTag->getTitle($tag_id));
				}
			}
		}
		$html= '<div class="d-flex flex-wrap tags-list-'.$customer_id.' gap-1 mt-1">'.($openFrom=='_list' ? '<a href="javascript:void(0);" class="label label-default" '.($deviceType=='phone' ? 'onClick="$Core.crm.open_tags(this, event)"' : 'data-toggle="webui-popover" data-trigger="click" data-type="async" data-closeable="false" data-width="300px" data-placement="auto" data-url="'.PCMS_URL.'/index.php?mod='.$mod.'&act=load_pop_tag&customer_id='.$customer_id.'"').' customer_id="'.$customer_id.'" title="Tags">+ Thêm</a>' : '');
		$html.= !empty($html_tags) ? implode("", $html_tags) : "<small class=\"text-muted\">Chưa có tags</small>";
		$html.= '</div>';
		return $html;
	}
}