<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Private By VietISO (support@vietiso.com)                     # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 by Technical Group       # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- NOT FREE SOFTWARE ----------------              # ||
|| #################################################################### ||
\*======================================================================*/
require_once(DIR_INCLUDES.'/database/DB_active_rec.php');
class DB extends CI_DB_active_record{
	var $dbprefix		= DB_PREFIX;
	var $swap_pre		= '';
	var $database		= DB_NAME;
	var $dbdriver		= DB_TYPE;
	var $char_set		= 'utf8';
	var $dbcollat		= 'utf8_general_ci';
	var $db_debug		= TRUE;
	var $result_id		= NULL;
	var $result_array			= array();
	var $result_object			= array();
	var $custom_result_object	= array();
	var $current_row			= 0;
	var $num_rows				= 0;
	var $row_data				= NULL;
	// The character used for escaping
	var $_escape_char = '`';
	var $save_queries	= TRUE;
	var $queries		= array();
	var $query_times	= array();
	var $benchmark		= 0;
	var $query_count	= 0;
	var $data_cache		= array();
	// Private variables
	var $_protect_identifiers	= TRUE;
	var $_reserved_identifiers	= array('*'); // Identifiers that should NOT be escaped
	
	var $_count_string = "SELECT COUNT(*) AS ";
	var $_random_keyword = ' RAND()'; // database specific random keyword
	
	private static $_instance = null;
	function __construct($params){
		if(!is_null($params) && is_array($params)){
			foreach ($params as $key => $val){
				$this->$key = $val;
			}
		}
		// something
	}
	public static function getInstance($params=null){
		if(!isset(self::$_instance)){
			self::$_instance = new DB($params);
		}
		return self::$_instance;
	}
	function query($sql){
		global $dbconn;
		//var_dump($sql); die();
		if ($sql == ''){
			if ($this->db_debug){
				trigger_error('Invalid query: '.$sql, E_USER_ERROR);
			}
			return FALSE;
		}
		// Set mode
		$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
		// Save the  query for debugging
		if ($this->save_queries == TRUE){
			$this->queries[] = $sql;
		}
		
		// Start the Query Timer
		$time_start = list($sm, $ss) = explode(' ', microtime());
		
		// Run the Query
		
		if (FALSE === ($this->result_id = $this->simple_query($sql))){
			if ($this->save_queries == TRUE){
				$this->query_times[] = 0;
			}
			return $this;
		}
		// Run the Query
		$this->result_array = $dbconn->GetAll($sql);
		$this->num_rows = !empty($this->result_array) ? count($this->result_array) : 0;
		$time_end = list($em, $es) = explode(' ', microtime());
		$this->benchmark += ($em + $es) - ($sm + $ss);
		if ($this->save_queries == TRUE){
			$this->query_times[] = ($em + $es) - ($sm + $ss);
		}
		// Increment the query counter
		$this->query_count++;
		// Return TRUE if we don't need to create a result object
		// Currently only the Oracle driver uses this when stored
		// procedures are used
		return $this;
	}
	/*
	|-------------------------------
	| Simple Query
	| @access	public
	| @param	string	the sql query
	| @return	mixed
	|-------------------------------
	|*/
	function simple_query($sql){
		global $dbconn;
		return $dbconn->Execute($sql);
	}
	/*
	|-------------------------------
	| Query result.  Acts as a wrapper function for the following functions.
	| @access	public
	| @param	string	can be "object" or "array"
	| @return	mixed	either a result object or array
	|-------------------------------
	|*/ 
	public function result($type = 'object'){
		if ($type == 'array') return $this->result_array();
		else if ($type == 'object') return $this->result_object();
		else return $this->custom_result_object($type);
	}
	/*
	|-------------------------------
	| Query result.  "array" version.
	| @access	public
	| @return	array
	|-------------------------------
	|*/ 
	public function result_array(){
		global $dbconn;
		// In the event that query caching is on the result_id variable
		// will return FALSE since there isn't a valid SQL resource so
		// we'll simply return an empty array.
		if ($this->result_id === FALSE OR $this->num_rows() == 0){
			return array();
		}
		return $this->result_array;
	}
	/*
	|-------------------------------
	| Query result.  "object" version.
	| @access	public
	| @return	object
	|-------------------------------
	|*/ 
	public function result_object(){
		global $dbconn;
		// In the event that query caching is on the result_id variable
		// will return FALSE since there isn't a valid SQL resource so
		// we'll simply return an empty array.
		if ($this->result_id === FALSE OR $this->num_rows() == 0){
			return array();
		}
		$this->result_object = new stdClass();
		foreach ($this->result_array as $key => $value){
			$this->result_object->$key = $value;
		}
		return $this->result_object;
	}
	/*
	|-------------------------------
	| Returns the "first" row
	| @access	public
	| @return	object
	|-------------------------------
	|*/
	public function first_row($type = 'object'){
		$result = $this->result($type);
		if (count($result) == 0){
			return $result;
		}
		return $result[0];
	}
	/*
	|-------------------------------
	| Protect Identifiers
	| This function adds backticks if appropriate based on db type
	| @access	public
	| @param	mixed	the item to escape
	| @return	mixed	the item with backticks
	|-------------------------------
	|*/
	function protect_identifiers($item, $prefix_single = FALSE){
		return $this->_protect_identifiers($item, $prefix_single);
	}
	/*
	|-------------------------------
	| Protect Identifiers
	| 
	| This function is used extensively by the Active Record class, and by
	| a couple functions in this class.
	| It takes a column or table name (optionally with an alias) and inserts
	| the table prefix onto it.  Some logic is necessary in order to deal with
	| column names that include the path.  Consider a query like this:
	| 
	| SELECT * FROM hostname.database.table.column AS c FROM hostname.database.table
	| 
	| Or a query with aliasing:
	| 
	| SELECT m.member_id, m.member_name FROM members AS m
	| 
	| Since the column name can include up to four segments (host, DB, table, column)
	| or also have an alias prefix, we need to do a bit of work to figure this out and
	| insert the table prefix (if it exists) in the proper position, and escape only
	| the correct identifiers.
	| 
	| @access	private
	| @param	string
	| @param	bool
	| @param	mixed
	| @param	bool
	| @return	string
	|-------------------------------
	*/
	function _protect_identifiers($item, $prefix_single = FALSE, $protect_identifiers = NULL, $field_exists = TRUE){
		if ( ! is_bool($protect_identifiers)){
			$protect_identifiers = $this->_protect_identifiers;
		}
		if (is_array($item)){
			$escaped_array = array();
			foreach ($item as $k => $v){
				$escaped_array[$this->_protect_identifiers($k)] = $this->_protect_identifiers($v);
			}
			return $escaped_array;
		}
		// Convert tabs or multiple spaces into single spaces
		$item = preg_replace('/[\t ]+/', ' ', $item);
		// If the item has an alias declaration we remove it and set it aside.
		// Basically we remove everything to the right of the first space
		if (strpos($item, ' ') !== FALSE){
			$alias = strstr($item, ' ');
			$item = substr($item, 0, - strlen($alias));
		}else{
			$alias = '';
		}
		// This is basically a bug fix for queries that use MAX, MIN, etc.
		// If a parenthesis is found we know that we do not need to
		// escape the data or add a prefix.  There's probably a more graceful
		// way to deal with this, but I'm not thinking of it -- Rick
		if (strpos($item, '(') !== FALSE){
			return $item.$alias;
		}
		// Break the string apart if it contains periods, then insert the table prefix
		// in the correct location, assuming the period doesn't indicate that we're dealing
		// with an alias. While we're at it, we will escape the components
		if (strpos($item, '.') !== FALSE){
			$parts	= explode('.', $item);
			// Does the first segment of the exploded item match
			// one of the aliases previously identified?  If so,
			// we have nothing more to do other than escape the item
			if (in_array($parts[0], $this->ar_aliased_tables)){
				if ($protect_identifiers === TRUE){
					foreach ($parts as $key => $val){
						if ( ! in_array($val, $this->_reserved_identifiers)){
							$parts[$key] = $this->_escape_identifiers($val);
						}
					}
					$item = implode('.', $parts);
				}
				return $item.$alias;
			}
			// Is there a table prefix defined in the config file?  If not, no need to do anything
			if ($this->dbprefix != ''){
				// We now add the table prefix based on some logic.
				// Do we have 4 segments (hostname.database.table.column)?
				// If so, we add the table prefix to the column name in the 3rd segment.
				if (isset($parts[3])){
					$i = 2;
				}
				// Do we have 3 segments (database.table.column)?
				// If so, we add the table prefix to the column name in 2nd position
				elseif (isset($parts[2])){
					$i = 1;
				}
				// Do we have 2 segments (table.column)?
				// If so, we add the table prefix to the column name in 1st segment
				else{
					$i = 0;
				}

				// This flag is set when the supplied $item does not contain a field name.
				// This can happen when this function is being called from a JOIN.
				if ($field_exists == FALSE){
					$i++;
				}

				// Verify table prefix and replace if necessary
				if ($this->swap_pre != '' && strncmp($parts[$i], $this->swap_pre, strlen($this->swap_pre)) === 0){
					$parts[$i] = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $parts[$i]);
				}
				// We only add the table prefix if it does not already exist
				if (substr($parts[$i], 0, strlen($this->dbprefix)) != $this->dbprefix){
					$parts[$i] = $this->dbprefix.$parts[$i];
				}
				// Put the parts back together
				$item = implode('.', $parts);
			}
			if ($protect_identifiers === TRUE){
				$item = $this->_escape_identifiers($item);
			}
			return $item.$alias;
		}

		// Is there a table prefix?  If not, no need to insert it
		if ($this->dbprefix != ''){
			// Verify table prefix and replace if necessary
			if ($this->swap_pre != '' && strncmp($item, $this->swap_pre, strlen($this->swap_pre)) === 0){
				$item = preg_replace("/^".$this->swap_pre."(\S+?)/", $this->dbprefix."\\1", $item);
			}

			// Do we prefix an item with no segments?
			if ($prefix_single == TRUE AND substr($item, 0, strlen($this->dbprefix)) != $this->dbprefix){
				$item = $this->dbprefix.$item;
			}
		}
		if ($protect_identifiers === TRUE AND ! in_array($item, $this->_reserved_identifiers)){
			$item = $this->_escape_identifiers($item);
		}
		return $item.$alias;
	}
	/**
	|-----------------------------------------
	| From Tables
	| This function implicitly groups FROM tables so there is no confusion
	| about operator precedence in harmony with SQL standards
	| @access	public
	| @param	type
	| @return	type
	|----------------------------------------
	*/
	function _from_tables($tables){
		if ( ! is_array($tables)){
			$tables = array($tables);
		}
		return '('.implode(', ', $tables).')';
	}
	function _escape_identifiers($item){
		if ($this->_escape_char == ''){
			return $item;
		}
		foreach ($this->_reserved_identifiers as $id){
			if (strpos($item, '.'.$id) !== FALSE){
				$str = $this->_escape_char. str_replace('.', $this->_escape_char.'.', $item);
				// remove duplicates if the user already included the escape
				return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
			}
		}
		if (strpos($item, '.') !== FALSE){
			$str = $this->_escape_char.str_replace('.', $this->_escape_char.'.'.$this->_escape_char, $item).$this->_escape_char;
		}else{
			$str = $this->_escape_char.$item.$this->_escape_char;
		}
		// remove duplicates if the user already included the escape
		return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
	}
	/**
	|-----------------------------------------
	| Limit string
	| Generates a platform-specific LIMIT clause
	| 
	| @access	public
	| @param	string	the sql query string
	| @param	integer	the number of rows to limit the query to
	| @param	integer	the offset value
	| @return	string
	|----------------------------------------
	*/
	function _limit($sql, $limit, $offset){
		$sql .= "LIMIT ".$limit;
		if ($offset > 0){
			$sql .= " OFFSET ".$offset;
		}
		return $sql;
	}
	/**
	|-----------------------------------------
	| Truncate statement
	| Generates a platform-specific truncate string from the supplied data
	| If the database does not support the truncate() command
	| 
	| @access	public
	| @param	string	the table name
	| @return	string
	|----------------------------------------
	*/
	function _truncate($table){
		return "TRUNCATE ".$table;
	}
	/*
	|-------------------------------
	| "Smart" Escape String
	| Escapes data based on type
	| Sets boolean and null types
	| @access	string
	| @access	public
	| @return	mixed
	|-------------------------------
	|*/
	function escape($str){
		if (is_string($str) || is_numeric($str)){
			$str = "'".$this->escape_str($str)."'";
		}
		elseif (is_bool($str)){
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif (is_null($str)){
			$str = 'NULL';
		}
		return $str;
	}
	/*
	|-------------------------------
	| Escape LIKE String
	| @access	public
	| @param	string
	| @return	mixed
	|-------------------------------
	|*/
	function escape_like_str($str){
		return $this->escape_str($str, TRUE);
	}
	/*
	|-------------------------------
	| Returns an array of table names
	
	| @access	public
	| @param	bool
	| @return	array
	|-------------------------------
	|*/
	function _list_tables($prefix_limit = FALSE){
		$sql = "SHOW TABLES FROM ".$this->_escape_char.$this->database.$this->_escape_char;
		if ($prefix_limit !== FALSE AND $this->dbprefix != ''){
			$sql .= " LIKE '".$this->dbprefix."%'";
		}
		return $sql;
	}
	/*
	|-------------------------------
	| Returns an array of table names
	
	| @access	public
	| @param	bool
	| @return	array
	|-------------------------------
	|*/
	function list_tables($constrain_by_prefix = FALSE){
		if (FALSE === ($sql = $this->_list_tables($constrain_by_prefix))){
			if ($this->db_debug){
				return $this->display_error('db_unsupported_function');
			}
			return FALSE;
		}
		$retval = array();
		$query = $this->query($sql);
		if ($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				if (isset($row['TABLE_NAME'])){
					$retval[] = $row['TABLE_NAME'];
				}else{
					$retval[] = array_shift($row);
				}
			}
		}
		$this->data_cache['table_names'] = $retval;
		return $this->data_cache['table_names'];
	}
	/*
	|-------------------------------
	| Determine if a particular table exists
	
	| @access	public
	| @return	boolean
	|-------------------------------
	|*/
	function table_exists($table_name){
		return ( ! in_array($this->_protect_identifiers($table_name, TRUE, FALSE, FALSE), $this->list_tables())) ? FALSE : TRUE;
	}
	/*
	|-------------------------------
	| Show column query
	| Generates a platform-specific query string so that the column names can be fetched
	| @access	public
	| @param	string	the table name
	| @return	array
	|-------------------------------
	|*/
	function _list_columns($table = ''){
		return "SHOW COLUMNS FROM ".$this->_protect_identifiers($table, TRUE, NULL, FALSE);
	}
	/*
	|-------------------------------
	| Fetch MySQL Field Names
	| @access	public
	| @param	string	the table name
	| @return	array
	|-------------------------------
	|*/
	function list_fields($table = ''){
		// Is there a cached result?
		if (isset($this->data_cache['field_names'][$table])){
			return $this->data_cache['field_names'][$table];
		}
		if ($table == ''){
			if ($this->db_debug){
				return $this->display_error('db_field_param_missing');
			}
			return FALSE;
		}
		if (FALSE === ($sql = $this->_list_columns($table))){
			if ($this->db_debug){
				return $this->display_error('db_unsupported_function');
			}
			return FALSE;
		}
		$query = $this->query($sql);
		$retval = array();
		foreach ($query->result_array() as $row)	{
			if (isset($row['COLUMN_NAME'])){
				$retval[] = $row['COLUMN_NAME'];
			}else{
				$retval[] = current($row);
			}
		}
		$this->data_cache['field_names'][$table] = $retval;
		return $this->data_cache['field_names'][$table];
	}
	/*
	|-------------------------------
	| Determine if a particular field exists
	| @access	public
	| @param	string
	| @param	string
	| @return	boolean
	|-------------------------------
	|*/
	function field_exists($field_name, $table_name){
		return ( ! in_array($field_name, $this->list_fields($table_name))) ? FALSE : TRUE;
	}
	/*
	|-------------------------------
	| Generate an insert string
	| @access	public
	| @param	string	the table upon which the query will be performed
	| @param	array	an associative array data of key/values
	| @return	string
	|-------------------------------
	|*/
	function insert($table, $data){
		global $dbconn;
		$fields = array();
		$values = array();
		
		foreach ($data as $key => $val){
			$fields[] = $this->_escape_identifiers($key);
			$values[] = $this->escape_str($val);
		}
		return $this->_insert($this->_protect_identifiers($table, TRUE, NULL, FALSE), $fields, $values);
	}
	/*
	|-------------------------------
	| Generate an update string
	| @access	public
	| @param	string	the table upon which the query will be performed
	| @param	array	an associative array data of key/values
	| @param	mixed	the "where" statement
	| @return	string
	|-------------------------------
	|*/
	function update($table, $data, $where){
		if ($where == ''){
			return false;
		}
		$fields = array();
		foreach ($data as $key => $val){
			$fields[$this->_protect_identifiers($key)] = $this->escape_str($val);
		}
		if ( ! is_array($where)){
			$dest = array($where);
		}else{
			$dest = array();
			foreach ($where as $key => $val){
				$prefix = (count($dest) == 0) ? '' : ' AND ';
				if ($val !== ''){
					if ( ! $this->_has_operator($key)){
						$key .= ' =';
					}
					$val = ' '.$this->escape_str($val);
				}
				$dest[] = $prefix.$key.$val;
			}
		}
		return $this->_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $fields, $dest);
	}
	/*
	|-------------------------------
	| Tests whether the string has an SQL operator
	| @access	public
	| @access	private
	| @param	string
	| @return	bool
	|-------------------------------
	|*/
	function _has_operator($str){
		$str = trim($str);
		if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str)){
			return FALSE;
		}
		return TRUE;
	}
	/*
	|-------------------------------
	| Set Cache Directory Path
	| @access	public
	| @param	string	the path to the cache directory
	| @return	void
	|-------------------------------
	|*/
	function cache_set_path($path = ''){
		$this->cachedir = $path;
	}
	/*
	|-------------------------------
	| Enable Query Caching
	| @access	public
	| @return	void
	|-------------------------------
	|*/
	function cache_on(){
		$this->cache_on = TRUE;
		return TRUE;
	}
	/*
	|-------------------------------
	| Disable Query Caching
	| @access	public
	| @return	void
	|-------------------------------
	|*/
	function cache_off(){
		$this->cache_on = FALSE;
		return FALSE;
	}
	/*
	|-------------------------------
	| Delete the cache files associated with a particular URI
	| @access	public
	| @return	void
	|-------------------------------
	|*/
	function cache_delete($segment_one = '', $segment_two = ''){
		if ( ! $this->_cache_init()){
			return FALSE;
		}
		return $this->CACHE->delete($segment_one, $segment_two);
	}
	/*
	|-------------------------------
	| Delete All cache files
	| @access public
	| @return void
	|-------------------------------
	|*/
	function cache_delete_all(){
		if ( ! $this->_cache_init()){
			return FALSE;
		}
		return $this->CACHE->delete_all();
	}
	/*
	|-------------------------------
	| Initialize the Cache Class
	| @access	private
	| @return void
	|-------------------------------
	|*/
	function _cache_init(){
		if (is_object($this->CACHE) AND class_exists('DB_Cache')){
			return TRUE;
		}
		if(! class_exists('DB_Cache')){
			if ( ! @include(BASEPATH.'database/DB_cache.php')){
				return $this->cache_off();
			}
		}
		$this->CACHE = new DB_Cache($this); // pass db object to support multiple db connections and returned db objects
		return TRUE;
	}
	/**
	|-----------------------------------------
	| Close DB Connection
	| 
	| @access	public
	| @param	resource
	| @return	void
	|----------------------------------------
	*/
	function _close($conn_id){
		global $dbconn;
		$dbconn->Close();
	}
	/*
	|-------------------------------
	| Display an error message
	| @access	public
	| @param	string	the error message
	| @param	string	any "swap" values
	| @param	boolean	whether to localize the message
	| @return	string	sends the application/error_db.php template
	|-------------------------------
	|*/
	function display_error($error = '', $swap = '', $native = FALSE){
		global $core;
		if ($native == TRUE){
			$message = $error;
		}else{
			$message = ( ! is_array($error)) ? array(str_replace('%s', $swap, __($error))) : $error;
		}
		// Find the most likely culprit of the error by going through
		// the backtrace until the source file is no longer in the
		// database folder.
		$trace = debug_backtrace();
		foreach ($trace as $call){
			if (isset($call['file']) && strpos($call['file'], DIR_LIBRARIES.'database') === FALSE){
				// Found it - use a relative path for safety
				$message[] = 'Filename: '.str_replace(ABSPATH, '', $call['file']);
				$message[] = 'Line Number: '.$call['line'];
				break;
			}
		}
		$error = new Exceptions();
		echo $error->show_error($heading, $message, 'error_db');
		exit;
	}
	/**
	|-----------------------------------------
	| The following functions are normally overloaded by the identically named
	| methods in the platform-specific driver -- except when query caching
	| is used.  When caching is enabled we do not load the other driver.
	| These functions are primarily here to prevent undefined function errors
	| when a cached result object is in use.  They are not otherwise fully
	| operational due to the unavailability of the database resource IDs with
	| cached results.
	*/
	public function num_rows() { return $this->num_rows; }
	public function field_data() { return array(); }
	public function free_result() { return TRUE; }
	protected function _data_seek() { return TRUE; }
	protected function _fetch_assoc() { return array(); }
	protected function _fetch_object() { return array(); }
}
?>