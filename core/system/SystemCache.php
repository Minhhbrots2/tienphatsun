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
class SystemCache {
	function __construct(){

	}
	/*
	|--------------------------------------------
	| Save into cache
	| @param 	string		unique key
	| @param 	mixed		data to store
	| @param 	int			length of time (in seconds) the cache is valid 
	| 			- Default is 60 seconds
	| @return 	boolean		true on success/false on failure
	|---------------------------------------------
	|
	*/
	function save($id, $data){
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($data, _CRM_CACHE_JSON_DIR.md5($id).'.json');
	}
	/*
	|--------------------------------------------
	| Fetch from cache
	| @param 	mixed		unique key id
	| @return 	mixed		data on success/false on failure
	|---------------------------------------------
	|
	*/
	function get($id){
		$cachedFile = _CRM_CACHE_JSON_DIR.md5($id).'.json';
		if(!file_exists($cachedFile)){
			return FALSE;
		}
		$decoder = new Webmozart\Json\JsonDecoder();
		$data = $decoder->decodeFile($cachedFile);
		return $data;
	}
	/*
	|--------------------------------------------
	| Delete from Cache
	| @param 	mixed		unique identifier of item in cache
	| @return 	boolean		true on success/false on failure
	|---------------------------------------------
	|
	*/
	public function delete($id){
		$cachedFile = _CRM_CACHE_JSON_DIR.md5($id).'.json';
		if(file_exists($cachedFile)){
			return FALSE;
		}
		return @unlink($cachedFile);
	}
	/*
	|--------------------------------------------
	| Clean the Cache
	| @return 	boolean		false on failure/true on success
	|---------------------------------------------
	|
	*/
	function clean(){
		return delete_files(_CRM_CACHE_JSON_DIR);
	}
}