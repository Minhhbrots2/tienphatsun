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
class OrderLog extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."order_logs";
	}
	function log($order_id, $message, $data){
		global $core, $clsISO;
		return $this->insert(array(
			$this->pkey => $this->getMaxId(),
			'order_id' 	=> $order_id,
			'message' 	=> $message,
			'_logVal' 	=> @json_encode($data),
			'user_id' 	=> $core->_USER['user_id'],
			'reg_date'	=> time()
		), false);
	}
}
?>