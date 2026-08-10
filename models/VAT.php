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
class VAT extends dbBasic{
	function VAT() {
        $this->pkey = "vat_id";
        $this->tbl = DB_PREFIX."vat";
    }
	function getSymbol(){
		return sprintf('1C%sTFH', date('y'));
	}
	function getContractCode(){
		$total_record = $this->countItem("1=1");
		$total_record += 4;
		if($total_record >= 100 && $total_record < 1000){
			return sprintf('00000%s', $total_record);
		} else if($total_record >= 1000 && $total_record < 10000){
			return sprintf('0000%s', $total_record);
		} else if($total_record >= 10000 && $total_record < 100000){
			return sprintf('000%s', $total_record);
		} else if($total_record >= 100000 && $total_record < 1000000){
			return sprintf('00%s', $total_record);
		} else if($total_record >= 1000000 && $total_record < 10000000){
			return sprintf('0%s', $total_record);
		} else {
			return $total_record;
		}
	}
}