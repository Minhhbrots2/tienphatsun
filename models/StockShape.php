<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class StockShape extends dbBasic{
	function __construct(){
		global $core, $clsISO;
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."stock_shapes";
	}
}