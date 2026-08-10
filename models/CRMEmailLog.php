<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Private By VietISO (support@vietiso.com)                     # ||
|| # ---------------------------------------------------------------- # ||
|| # All Script code in this file is ©2007-2013 VietISO JSC.          # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class CRMEmailLog extends dbBasic{
	function CRMEmailLog(){
		$this->pkey = "id";
		$this->tbl = "_crm_email_logs";
	}
}
?>