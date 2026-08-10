<?php
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class Cache {
	protected $_instance = null;   // dtkahl\SimpleRedisCache — giữ nguyên đường cũ (has/get/put/set/delete)
	protected $_client   = null;   // Predis\Client thô — cần cho increment + SET NX (dtkahl giấu client private)
	/** @var string|null Tiền tố khoá riêng của site — dò 1 lần / request. */
	protected static $_prefix = null;

	/**
	 * Tiền tố RIÊNG theo site cho mọi khoá Redis.
	 *
	 * Các site (skyrealty, tienphatsunrise, ...) dùng chung codebase và chạy trên
	 * cùng server → cùng Redis localhost:6379 database 0. Trước đây khoá chỉ mang
	 * 'DUP_' nên tên khoá giống hệt nhau ở mọi site: site nạp cache sau đè lên site
	 * trước, và màn Cấu hình hiện dữ liệu của site khác cho tới khi hết TTL 5 phút.
	 *
	 * DB_NAME (configs/database.php) là thứ chắc chắn khác nhau giữa các site và đã
	 * có sẵn từ lúc core.php kết nối DB — không cần khai báo thêm gì khi deploy.
	 * @return string
	 */
	protected static function prefix(){
		if(self::$_prefix !== null){
			return self::$_prefix;
		}
		if(defined('CACHE_PREFIX') && CACHE_PREFIX !== ''){
			self::$_prefix = CACHE_PREFIX;
		} else if(defined('DB_NAME') && DB_NAME !== ''){
			self::$_prefix = 'TPS_'.DB_NAME.'_';
		} else {
			// Không biết mình là site nào thì giữ nguyên đường cũ, không tự bịa khoá.
			self::$_prefix = 'TPS_';
		}
		return self::$_prefix;
	}

	/**
	 * Khoá thật gửi xuống Redis. MỌI method phải đi qua đây — tự nối chuỗi ở từng
	 * chỗ chính là lỗi đã khiến remember() ghi 'DUP_DUP_...' mà đọc 'DUP_...'.
	 * @return string
	 */
	protected function key($key){
		return self::prefix().$key;
	}

	function __construct(){
		$config = array(
			"scheme" => "tcp",
			"host"   => "localhost",
			"port"   => 6379,
			// pconnect: tái dùng socket giữa các request → bỏ handshake mỗi lần (tải cao).
			// AN TOÀN với php-fpm (bounded workers). Nếu SAPI = mod_php prefork với
			// MaxRequestWorkers rất lớn → cân nhắc đổi "persistent" => false.
			"persistent"         => true,
			"read_write_timeout" => 2,   // giây — không treo request khi Redis lag
			"timeout"            => 1,   // connect timeout
		);
		$this->_instance = new Dtkahl\SimpleRedisCache\Cache($config);
		$this->_client   = new \Predis\Client($config);
	}
	public function has($key){
		global $core, $dbconn, $clsISO;
		return $this->_instance->has($this->key($key));
	}
	public function getInstace(){
		return $this->_instance;
	}
	/** Predis\Client thô — cho INCR / SET NX (dùng trong helper cache bảng hàng). */
	public function getClient(){
		return $this->_client;
	}
	public function put($key, $value, $time = CACHE_LIFETIME){
		global $core, $dbconn, $clsISO;
		if(!empty($value) && is_array($value)){
			$value = json_encode($value, JSON_UNESCAPED_UNICODE);
		}
		// TTL jitter ±12% — tránh hàng loạt key hết hạn cùng nhịp gây stampede đồng bộ.
		$jittered = (int) round($time * (0.88 + (mt_rand(0, 240) / 1000.0)));
		if($jittered < 1){ $jittered = (int)$time; }
		return $this->_instance->put($this->key($key), $value, $jittered);
	}
	public function set($key, $value){
		global $core, $dbconn, $clsISO;
		if(!empty($value) && is_array($value)){
			$value = json_encode($value, JSON_UNESCAPED_UNICODE);
		}
		return $this->_instance->forever($this->key($key), $value);
	}
	public function isJsonString($string) {
		json_decode($string);
		return (json_last_error() === JSON_ERROR_NONE);
	}
	public function get($key, $def = array(), $decoded = true){
		global $core, $dbconn, $clsISO;
		$response = $def;
		$value = $this->_instance->get($this->key($key));
		if(!empty($value)){
			if($this->isJsonString($value)){
				$response = json_decode(($decoded ? html_entity_decode($value): $value), true);
			} else {
				$response = $value;
			}
		}
		return $response;
	}
	public function delete($key){
		global $core, $dbconn, $clsISO;
		if($this->has($key)){
			return $this->_instance->forget($this->key($key));
		} else {
			return false;
		}
	}
	/**
	 * Cache-aside 1 round-trip: hit → trả cache; miss → chạy $callback rồi put.
	 * Bản này KHÁC dtkahl->remember (3 round-trip has+get) và fail-soft: Redis chết → build thẳng.
	 */
	public function remember($key, callable $callback, $time = CACHE_LIFETIME){
		try {
			$raw = $this->_client->get($this->key($key));   // Predis trả null nếu thiếu key
			if($raw !== null && $raw !== ''){
				if($this->isJsonString($raw)){
					// KHÔNG html_entity_decode: data do put() json_encode ra; entity (&quot;/&amp;/&lt;)
					// trong value (note/csbh/intro) sẽ phá cấu trúc JSON → decode null. Round-trip thuần.
					return json_decode($raw, true);
				}
				return $raw;
			}
		} catch (\Throwable $e) {
			return $callback();                 // Redis lỗi → build thẳng, không ném
		}
		$value = $callback();
		try { 
			$this->put($key, $value, $time); 
		} catch (\Throwable $e) {}
		return $value;
	}
	/** INCR nguyên tử — dùng cho version-bump invalidation (bh:ver:p:{pid}). */
	public function increment($key){
		return $this->_client->incr($this->key($key));
	}
	/** SET key value EX ttl NX — khoá dựng (mutex chống stampede). True nếu giành được. */
	public function add($key, $value, $ttlSeconds){
		$result = $this->_client->set($this->key($key), $value, 'EX', (int)$ttlSeconds, 'NX');
		return $result !== null;
	}
}
?>