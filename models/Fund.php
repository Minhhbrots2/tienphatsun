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
class Fund extends dbBasic{
	private static $_instance;
	function __construct(){
		$this->pkey = 'fund_id';
		$this->tbl = DB_PREFIX.'fund';
	}
	function genCode($gr='THUCTHU'){
		$cond = "`gr`='{$gr}'";
		$total_record = $this->countItem("`gr`='{$gr}'");
		$prefix = ($gr=='THUCTHU') ? 'PT' : 'PC';
		if($total_record<10) 
			return sprintf('%s0000%s', $prefix, $total_record+1);
		if($total_record >= 10 && $total_record<100) 
			return sprintf('%s000%s', $prefix, $total_record+1);
		if($total_record >= 100 && $total_record<1000) 
			return sprintf('%s00%s', $prefix, $total_record+1);
		if($total_record >= 1000 && $total_record<10000) 
			return sprintf('%s0%s', $prefix, $total_record+1);
		if($total_record >= 10000 && $total_record<100000) 
			return sprintf('%s%s', $prefix, $total_record+1);
	}
	function getTotalStat($bank_account_id = 0){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		// $field = "{$clsProperty->pkey},ms_value";
		$cond = "`is_trash`=0 and `property_type`='BANK_ACCOUNT'";
		if($bank_account_id > 0) {
			$cond.= " and {$clsProperty->pkey}='{$bank_account_id}'";	
		}
		return $clsProperty->sumItem("ms_value", $cond);
	}
	function getTotalBalance($bank_account_id, $payment_date, $gr, $amount){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$clsBankTransfer = new BankTransfer();
		$where = "`is_trash`=0 and `payment_date`<='{$payment_date}'";
		// Số tiền ban đầu
		$total = $clsProperty->getOneField('ms_value', $bank_account_id);
		// + sồ tiền chuyển tới
		$total+= $clsBankTransfer->sumItem("amount", "{$where} and `bank_account_to`='{$bank_account_id}'");
		// - số tiền bị chuyển đi
		$total-= $clsBankTransfer->sumItem("amount", "`{$where} and `bank_account_from`='{$bank_account_id}'");
		// + Tổng thu
		$total+= $this->sumItem("amount", "{$where} and `gr`='THUCTHU' and `bank_account_id`='{$bank_account_id}'");
		// - Tổng chi
		$total-= $this->sumItem("amount", "{$where} and `gr`='THUCCHI' and `bank_account_id`='{$bank_account_id}'");
		// Công [OR] trừ đi số tiền tại thời điểm tính toán
		if($gr=='THUCTHU'){
			$total+= $clsISO->processSmartNumber($amount);
		} else {
			$total-= $clsISO->processSmartNumber($amount);
		}
		// Return
		return $total;
	}
	function getTotalBank($bank_account_id){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$clsBankTransfer = new BankTransfer();
		$Current_Now = time();
		$where = "`is_trash`=0 and `payment_date`<'{$Current_Now}'";
		// Số tiền ban đầu
		$total = $clsProperty->getOneField('ms_value', $bank_account_id);
		// + Tổng thu
		$total+= $this->sumItem("amount", "{$where} and `gr`='THUCTHU' and `bank_account_id`='{$bank_account_id}'");
		// - Tổng chi
		$total-= $this->sumItem("amount", "{$where} and `gr`='THUCCHI' and `bank_account_id`='{$bank_account_id}'");
		// + sồ tiền chuyển tới
		$total+= $clsBankTransfer->sumItem("amount", "{$where} and `bank_account_to`='{$bank_account_id}'");
		// - số tiền bị chuyển đi
		$total-= $clsBankTransfer->sumItem("amount", "{$where} and `bank_account_from`='{$bank_account_id}'");
		// Return
		return $total;
	}
	function getTotalTrans($bank_account_id, $date){
		global $core, $dbconn, $clsISO;
		$clsBankTransfer = new BankTransfer();
		$where = "`is_trash`=0 and `payment_date`<='{$date}'";
		$total = $clsBankTransfer->sumItem("amount", "{$where} and `bank_account_to`='{$bank_account_id}'");
		// - số tiền bị chuyển đi
		$total-= $clsBankTransfer->sumItem("amount", "{$where} and `bank_account_from`='{$bank_account_id}'");
		return $total;
	}
	function renderOptionColumnField($col){
		$arr_fields = array(
			'payment_date' => 'Ngày thanh toán',
			'account_date' => 'Ngày hạch toán',
			'transaction_code' => 'Mã giao dịch',
			'type_id' => 'Loại khoản thu',
			'income' => 'Số tiền thu',
			'expense' => 'Số tiền chi',
			'payment_method' => 'Phương thức nộp',
			'person' => 'Người nhận/nộp',
			'person_address' => 'Địa chỉ',
			'content' => 'Lý do'
		);
		$html_options = "";
		foreach($arr_fields as $key => $val){
			$field = "";
			if($col==0) $field = 'payment_date';
			if($col==1) $field = 'account_date';
			if($col==2) $field = 'income';
			if($col==3) $field = 'expense';
			if($col==4) $field = 'person';
			if($col==5) $field = 'content';
			$selected = ($field==$key) ? 'selected':'';
			$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);
		}
		return $html_options;
	}
	function short_content($content, $len=60){
		if(!empty($content)){
			$length = strlen($content);
			if($length > $len){
				$truncate = substr($content, 0, 60);
				return $truncate. '...<a data-toggle="webui-popover" class="text-link" data-content="'.$content.'">+ Xem thêm</a>';
			} else {
				return $content;
			}
		} else {
			return "";
		}
	}
	function getLinkDashboard($type){
		return "/chi-phi-van-hanh/".$type.".html";
	}
}