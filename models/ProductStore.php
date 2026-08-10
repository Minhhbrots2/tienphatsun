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
class ProductStore extends dbBasic{
	function __construct(){
		$this->pkey = "product_store_id";
		$this->tbl = DB_PREFIX."product_store";
	}
	function getTitle($type){
		$lstType = $this->getListType();
		return $lstType[$type];
	}
	function checkAvailable($product_type, $for_id, $product_id){
		$where = "is_trash=0 and product_type='{$product_type}' and for_id='{$for_id}' and product_id='{$product_id}'";
		return $this->countItem($where);
	}
	function getId($product_type, $for_id, $product_id){
		$where = "is_trash=0 and product_type='{$product_type}' and for_id='{$for_id}' and product_id='{$product_id}'";
		$tmp = $this->getAll($where . " limit 0,1");
		return !empty($tmp) ? $tmp[0][$this->pkey] : 0;
	}
	function getListType(){
		global $core;
		$lstType = array();
		$lstType['NEW'] = $core->get_Lang('productnew');
		$lstType['PROMOTION'] = $core->get_Lang('productSaleoff');
		$lstType['TOPSELLING'] = $core->get_Lang('producttopselling');
		return $lstType;
	}
	function checkExist($product_id, $type){
		$res = $this->getAll("product_id='{$product_id}' and _type='{$type}' limit 0,1");
		return !empty($res)?1:0;
	}
}
?>