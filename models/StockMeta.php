<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class StockMeta extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."stock_meta";
	}
}