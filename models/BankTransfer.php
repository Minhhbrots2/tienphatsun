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
class BankTransfer extends dbBasic{
	function __construct(){
		$this->pkey = "bank_transfer_id";
		$this->tbl = DB_PREFIX."bank_transfer";
	}
	function genCode(){
		global $core, $dbconn, $clsISO;
		$cond = "`gr`='{$gr}'";
		$total_record = $this->countItem("`is_trash`=0");
		// $clsISO->print_pre($total_record); die();
		$prefix = 'PCT';
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
}
?>