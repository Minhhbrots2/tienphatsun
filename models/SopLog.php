<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class SopLog extends dbBasic{
	function __construct(){
		global $core, $clsISO;
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."sop_logs";
	}
}
?>