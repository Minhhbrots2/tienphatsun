<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Module Private By Technical Group(buivanthiem.it@gmail.com)  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 by Technical Group       # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- NOT FREE SOFTWARE ----------------              # ||
|| #################################################################### ||
\*======================================================================*/ 
class IssueCurrent extends dbBasic{
	function __construct(){
		$this->pkey = "issue_current_id";
		$this->tbl = DB_PREFIX."issue_current";
	}
}
?>