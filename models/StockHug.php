<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class StockHug extends dbBasic{
	function __construct(){
		global $core, $clsISO;
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."stock_hug";
	}
	function getStatus($status_id, $oDataTable = array()){
		$clsProperty = new Property();
		if(empty($oDataTable)){
			$field = "title,bgcolor,textcolor";
			$oDataTable = $clsProperty->getOne($status_id, $field);
		}
		return sprintf('<span style="color:%s">%s</span>', $oDataTable['textcolor'], $oDataTable['title']);
	}
	function getTableField(){
		$arr_fields = array(
			'stock_code' => "Mã căn",
			'bedroom_name' => "Loại hình",
			'otp_customer' => "Người ký",
			'otp_date' => "Ngày ký",
			'otp_reg_link' => "Link ký",
			'status_name' => "Tình trạng",
			'deposit_date' => 'Ngày cọc',
			'deposit_amount' => 'Tiền cọc',
			'agree_date' => 'Ngày ký TTĐC',
			'contract_date' => 'Ngày ký HĐMB',
			'sale_price' => 'Giá bán',
			'state_name' => 'Trạng thái',
			'first_payment_amount' => 'Tiền đóng 10%',
			'developer_deposit_amount' => 'Tiền cọc CĐT',
			'fund_type' => 'Loại quỹ',
			'stock_resource' => 'Nguồn quỹ',
			'refund_date' => "Ngày thu hồi",
			'commision_rate' => '%Hoa hồng',
			'sale_bonus_amount' => 'Thưởng sale',
			'notes' => 'Ghi chú'
		);
		return $arr_fields;
	}
	function renderOptionColumnField($col=0, $field=""){
		$html = "";
		$arr_fields = $this->getTableField();
		if(!empty($arr_fields)){
			foreach($arr_fields as $key => $val){
				$html.= '<option'.($field==$key ? ' selected="selected"': '').' value="'.$key.'">'.$val.'</option>';
			}
		}
		// Return
		return $html;
	}
}