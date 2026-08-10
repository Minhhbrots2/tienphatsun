<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Private By VietISO (support@vietiso.com)                     # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 by Technical Group       # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- NOT FREE SOFTWARE ----------------              # ||
|| #################################################################### ||
\*======================================================================*/
class CI_DB_Cache {
	var $db;
	var $cachedir;
	// allows passing of db object so that multiple database connections and returned db objects can be supported	
	/*
	|-------------------------------
	| Constructor
	| @access	public
	| Grabs the CI super object instance so we can access it.
	| @return	void
	|-------------------------------
	|*/
	function __construct(&$db){
		// Assign the main CI object to $this->CI
		// and load the file helper since we use it a lot
		$this->db = DB::getInstance();
		$this->cachedir = _CRM_DATABASE_CACHE_DIR;
	}
	/*
	|-------------------------------
	| Set Cache Directory Path
	| @access	public
	| @param	string	the path to the cache directory
	| @return	bool
	|-------------------------------
	|*/
	function check_path($path = ''){
		if ($path == ''){
			if ($this->cachedir == ''){
				return $this->db->cache_off();
			}
			$path = $this->db->cachedir;
		}
		// Add a trailing slash to the path if needed
		$path = preg_replace("/(.+?)\/*$/", "\\1/",  $path);
		if ( ! is_dir($path) OR ! Common::is_really_writable($path)){
			// If the path is wrong we'll turn off caching
			return $this->db->cache_off();
		}
		$this->cachedir = $path;
		return TRUE;
	}
	/*
	|-------------------------------
	| Retrieve a cached query
	| The URI being requested will become the name of the cache sub-folder.
	| An MD5 hash of the SQL statement will become the cache file name
	| @access	public
	| @return	bool
	|-------------------------------
	|*/
	function read($sql){
		if ( ! $this->check_path()){
			return $this->db->cache_off();
		}
		$sub = Input::get('sub', 'default');
		$mod = Input::get('mod', 'home');
		$act = Input::get('act', 'default');
		$filepath = $this->cachedir.'+'.$sub.'+'.$mod.'+'.$act.'/'.md5($sql);
		if (FALSE === ($cachedata = read_file($filepath))){
			return FALSE;
		}
		return unserialize($cachedata);
	}
	/*
	|-------------------------------
	| Write a query to a cache file
	| @access	public
	| @return	bool
	|-------------------------------
	|*/
	function write($sql, $object){
		if ( ! $this->check_path()){
			return $this->db->cache_off();
		}
		$sub = Input::get('sub', 'default');
		$mod = Input::get('mod', 'home');
		$act = Input::get('act', 'default');
		$dir_path = $this->cachedir.$sub.'+'.$mod.'+'.$act.'/';
		$filename = md5($sql);
		if ( ! @is_dir($dir_path)){
			if ( ! @mkdir($dir_path, DIR_WRITE_MODE)){
				return FALSE;
			}
			@chmod($dir_path, DIR_WRITE_MODE);
		}
		if (write_file($dir_path.$filename, serialize($object)) === FALSE){
			return FALSE;
		}
		@chmod($dir_path.$filename, FILE_WRITE_MODE);
		return TRUE;
	}
	/*
	|-------------------------------
	| Delete cache files within a particular directory
	| @access	public
	|  @return	bool
	|-------------------------------
	|*/
	function delete($sub='', $mod = '', $act = ''){
		if ($sub == ''){ $sub  = Input::get('sub', 'default'); }
		if ($mod == ''){ $mod  = Input::get('mod', 'home'); }
		if ($act == ''){ $act  = Input::get('act', 'default'); }
		$dir_path = $this->db->cachedir.$sub.'+'.$mod.'+'.$act.'/';
		delete_files($dir_path, TRUE);
	}
	/*
	|-------------------------------
	| Delete all existing cache files
	| @access	public
	| @return	bool
	|-------------------------------
	|*/
	function delete_all(){
		delete_files($this->db->cachedir, TRUE);
	}
}
/* End of file DB_cache.php */
