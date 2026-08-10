<?php
/**
 * ISOCMS Memcached Caching Class 
 * @subpackage	Libraries
 * @category	Core
 * @author		Technical Group Team
 * @link		
 */
class Cache_memcached {
	
	private $_memcached;
	 private $_is_memcache = FALSE;
	// Holds the memcached object
	protected $_memcache_conf 	= array(
		'default' => array(
			'default_host'		=> 'localhost',
			'default_port'		=> 11221,
			'default_weight'	=> 1
		)
	);
	// ------------------------------------------------------------------------
	/**
	* Fetch from cache
	* @param 	mixed		unique key id
	* @return 	mixed		data on success/false on failure
	*/	
	public function get($id){	
		$data = $this->_memcached->get($id);
		return (is_array($data)) ? $data[0] : FALSE;
	}
	// ------------------------------------------------------------------------
	/**
	* Save
	* @param 	string		unique identifier
	* @param 	mixed		data being cached
	* @param 	int			time to live
	* @return 	boolean 	true on success, false on failure
	*/
	public function save($id, $data, $ttl = 60){
		if($this->_is_memcache) {
            return $this->_memcached->add($id, array($data, time(), $ttl), FALSE, $ttl);
        } else {
			return $this->_memcached->add($id, array($data, time(), $ttl), $ttl);
		}
	}
	// ------------------------------------------------------------------------
	/**
	* Delete from Cache
	* @param 	mixed		key to be deleted.
	* @return 	boolean 	true on success, false on failure
	*/
	public function delete($id){
		return $this->_memcached->delete($id);
	}
	// ------------------------------------------------------------------------
	/**
	* Clean the Cache
	*
	* @return 	boolean		false on failure/true on success
	*/
	public function clean()
	{
		return $this->_memcached->flush();
	}
	// ------------------------------------------------------------------------
	/**
	* Cache Info
	* @param 	null		type not supported in memcached
	* @return 	mixed 		array on success, false on failure
	*/
	public function cache_info($type = NULL){
		return $this->_memcached->getStats();
	}
	// ------------------------------------------------------------------------
	/**
	* Get Cache Metadata
	* @param 	mixed		key to get cache metadata on
	* @return 	mixed		FALSE on failure, array on success.
	*/
	public function get_metadata($id){
		$stored = $this->_memcached->get($id);
		if (count($stored) !== 3){
			return FALSE;
		}
		list($data, $time, $ttl) = $stored;
		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}
	// ------------------------------------------------------------------------
	/**
	* Setup memcached.
	*/
	private function _setup_memcached(){
		if(class_exists("Memcached")) {
			$this->_memcached = new Memcached();
		} else {
			$this->_memcached = new Memcached();
			$this->_is_memcache = TRUE;
		}
		foreach ($this->_memcache_conf as $name => $cache_server){
			if ( ! array_key_exists('hostname', $cache_server)){
				$cache_server['hostname'] = 'localhost';
			}
			if ( ! array_key_exists('port', $cache_server)){
				$cache_server['port'] = 11211;
			}
			if ( ! array_key_exists('weight', $cache_server)){
				$cache_server['weight'] = 10;
			}
			$this->_memcached->addServer(
					$cache_server['hostname'], $cache_server['port'], $cache_server['weight']
			);
		}
	}
	// ------------------------------------------------------------------------
	/**
	* Is supported
	* Returns FALSE if memcached is not supported on the system.
	* If it is, we setup the memcached object & return TRUE
	*/
	public function is_supported(){
		if ( ! extension_loaded('memcached')){
			log_message('error', 'The Memcached Extension must be loaded to use Memcached Cache.');
			return FALSE;
		}
		$this->_setup_memcached();
		return TRUE;
	}
	 public function server_status() { 
        $server_status = $this->_memcached->getstats(); 
        echo "<table border='1'>"; 
        echo "<tr><td>Memcache Server version:</td><td> " . $server_status["version"] . "</td></tr>"; 
        echo "<tr><td>Process id of this server process </td><td>" . $server_status["pid"] . "</td></tr>"; 
        echo "<tr><td>Number of seconds this server has been running </td><td>" . $server_status["uptime"] . "</td></tr>"; 
        echo "<tr><td>Accumulated user time for this process </td><td>" . $server_status["rusage_user"] . " seconds</td></tr>"; 
        echo "<tr><td>Accumulated system time for this process </td><td>" . $server_status["rusage_system"] . " seconds</td></tr>"; 
        echo "<tr><td>Total number of items stored by this server ever since it started </td><td>" . $server_status["total_items"] . "</td></tr>"; 
        echo "<tr><td>Number of open connections </td><td>" . $server_status["curr_connections"] . "</td></tr>"; 
        echo "<tr><td>Total number of connections opened since the server started running </td><td>" . $server_status["total_connections"] . "</td></tr>"; 
        echo "<tr><td>Number of connection structures allocated by the server </td><td>" . $server_status["connection_structures"] . "</td></tr>"; 
        echo "<tr><td>Cumulative number of retrieval requests </td><td>" . $server_status["cmd_get"] . "</td></tr>"; 
        echo "<tr><td> Cumulative number of storage requests </td><td>" . $server_status["cmd_set"] . "</td></tr>"; 
        $percCacheHit = ((real) $server_status["get_hits"] / (real) $server_status["cmd_get"] * 100); 
        $percCacheHit = round($percCacheHit, 3); 
        $percCacheMiss = 100 - $percCacheHit; 
        echo "<tr><td>Number of keys that have been requested and found present </td><td>" . $server_status["get_hits"] . " ($percCacheHit%)</td></tr>"; 
        echo "<tr><td>Number of items that have been requested and not found </td><td>" . $server_status["get_misses"] . "($percCacheMiss%)</td></tr>"; 
        $MBRead = (real) $server_status["bytes_read"] / (1024 * 1024); 
        echo "<tr><td>Total number of bytes read by this server from network </td><td>" . $MBRead . " Mega Bytes</td></tr>"; 
        $MBWrite = (real) $server_status["bytes_written"] / (1024 * 1024); 
        echo "<tr><td>Total number of bytes sent by this server to network </td><td>" . $MBWrite . " Mega Bytes</td></tr>"; 
        $MBSize = (real) $server_status["limit_maxbytes"] / (1024 * 1024); 
        echo "<tr><td>Number of bytes this server is allowed to use for storage.</td><td>" . $MBSize . " Mega Bytes</td></tr>"; 
        echo "<tr><td>Number of valid items removed from cache to free memory for new items.</td><td>" . $server_status["evictions"] . "</td></tr>"; 
        echo "</table>"; 
    }
}