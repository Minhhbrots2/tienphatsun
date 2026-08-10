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
/**
 * Tầng truy xuất cấu hình hệ thống.
 *
 * Bản cũ bắn 1 query cho MỖI getValue() — một trang .tpl gọi vài trăm lần là
 * vài trăm query. Bản này nạp TOÀN BỘ cấu hình bằng ĐÚNG 1 query (lazy-load ở
 * lần getValue() đầu tiên), giữ trong static, và đệm thêm một lớp Redis có TTL
 * — tự xoá khi saveBatch() chạy.
 *
 * @see ConfigDeclaration schema khai báo field/nhóm
 */
class Configuration extends DbBasic {
	const CACHE_KEY = 'CONFIGURATION_ALL';
	/** Snapshot phái sinh dựng ở application/_header.php — lưu cấu hình phải xoá kèm. */
	const CACHE_KEY_HEADER = '_header_configs_cached';

	/** @var array|null Cache cả bộ cấu hình — getValue() gọi 500 lần vẫn 1 query. */
	private static $store = null;
	/** @var Configuration|null Instance dùng chung, cho getInstance(). */
	private static $instance = null;
	/** @var array|null Cột thật của bảng — dò 1 lần / request. */
	private static $columns = null;
	/** @var Cache|false|null false nghĩa là đã thử và không dùng được. */
	private static $cacheClient = null;
	/** @var bool Đã đăng ký modifier |cfg và function {config_get} chưa. */
	private static $helpersRegistered = false;

	function __construct(){
		$this->pkey = "setting";
		$this->tbl = DB_PREFIX."configuration";
		$this->registerHelpers();
	}

	/**
	 * Object dùng chung — tránh new lại trong vòng lặp.
	 * @return Configuration
	 */
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new Configuration();
		}
		return self::$instance;
	}

	/**
	 * Đọc 1 giá trị theo keyword phẳng.
	 * Key không tồn tại trả $def, không throw, không notice.
	 * @return string
	 */
	function getValue($keyword, $def = ""){
		$data = $this->load();
		return array_key_exists($keyword, $data) ? $data[$keyword] : $def;
	}

	/**
	 * Có giá trị và khác rỗng — dùng cho {if $clsConfiguration->has('zalo')}.
	 * @return bool
	 */
	public function has($keyword){
		$data = $this->load();
		return isset($data[$keyword]) && $data[$keyword] !== '';
	}

	/**
	 * Toàn bộ cấu hình, dạng keyword => content.
	 * @return array
	 */
	public function all(){
		return $this->load();
	}

	/**
	 * Bề ngang (px) đã lưu của 1 key ảnh; chưa lưu thì trả $def.
	 * @return int
	 */
	public function getImageWidth($keyword, $def = 0){
		$value = (int) $this->getValue($keyword.ConfigDeclaration::WIDTH_SUFFIX, 0);
		return $value > 0 ? $value : (int) $def;
	}

	/**
	 * Chiều cao (px) đã lưu của 1 key ảnh; chưa lưu thì trả $def.
	 * @return int
	 */
	public function getImageHeight($keyword, $def = 0){
		$value = (int) $this->getValue($keyword.ConfigDeclaration::HEIGHT_SUFFIX, 0);
		return $value > 0 ? $value : (int) $def;
	}

	/**
	 * Cụm thuộc tính src/width/height của 1 key ảnh, dùng thẳng trong .tpl:
	 *   <img {$clsConfiguration->getImageAttr('LogoWhite', 0, 40)} alt="..." />
	 * $defWidth/$defHeight là kích thước dùng khi admin chưa cấu hình — truyền
	 * đúng số đang hardcode ở template để đổi sang hàm này không đổi giao diện.
	 * Kích thước bằng 0 thì không in thuộc tính, để CSS tự quyết như trước.
	 * Tự escape vì template autoescape đang tắt.
	 * @return string
	 */
	public function getImageAttr($keyword, $defWidth = 0, $defHeight = 0){
		$attr = 'src="'.htmlspecialchars($this->getValue($keyword, ''), ENT_QUOTES, 'UTF-8').'"';
		$width = $this->getImageWidth($keyword, $defWidth);
		$height = $this->getImageHeight($keyword, $defHeight);
		if($width > 0){
			$attr .= ' width="'.$width.'"';
		}
		if($height > 0){
			$attr .= ' height="'.$height.'"';
		}
		return $attr;
	}

	/**
	 * Giá trị đã json_decode của field khai báo 'json' => true.
	 * @return array
	 */
	public function getArray($keyword){
		$value = $this->getValue($keyword, '');
		if($value === '' || !is_string($value)){
			return array();
		}
		$decoded = json_decode($value, true);
		return is_array($decoded) ? $decoded : array();
	}

	/**
	 * Nguyên cụm nhóm theo schema: getGroup('payment') → ['zalo_credit' => .., ..].
	 * Nhóm lấy từ ConfigDeclaration nên key không cần mang prefix.
	 * @return array
	 */
	public function getGroup($group){
		$values = array();
		if(!class_exists('ConfigDeclaration')){
			return $values;
		}
		$clsDeclaration = new ConfigDeclaration();
		$system = $clsDeclaration->system();
		if(empty($system[$group]['value'])){
			return $values;
		}
		foreach($system[$group]['value'] as $keyword => $field){
			$default = isset($field['default']) ? $field['default'] : '';
			$values[$keyword] = $this->getValue($keyword, $default);
		}
		return $values;
	}

	/** Ghi 1 key. */
	public function set($keyword, $value){
		$this->saveBatch(array($keyword => $value));
	}

	/**
	 * Upsert nhiều key trong 1 lần lưu rồi xoá cache.
	 * Key vắng mặt trong $data giữ nguyên giá trị cũ — không có bước xoá nào.
	 * @return bool
	 */
	public function saveBatch($data, $userId = 0){
		if(empty($data) || !is_array($data)){
			return false;
		}
		$success = true;
		foreach($data as $keyword => $value){
			if(!$this->writeRow((string) $keyword, $value, (int) $userId)){
				$success = false;
			}
		}
		$this->flushCache();
		return $success;
	}

	/** Xoá cache tĩnh lẫn Redis. */
	public function flushCache(){
		self::$store = null;
		$client = self::cacheClient();
		if($client === null){
			return;
		}
		foreach(array(self::CACHE_KEY, self::CACHE_KEY_HEADER) as $cacheKey){
			try {
				$client->delete($cacheKey);
			} catch(Exception $e){
				// Redis lỗi thì cache tĩnh đã xoá ở trên, request sau đọc lại từ DB.
			}
		}
	}

	/*======================================================================*\
	|| Tương thích ngược — các màn hình setting khác vẫn đang gọi
	\*======================================================================*/

	/** @deprecated Dùng all()/getGroup(). Giữ cho code cũ. */
	function getValues($settings){
		$this->arrResults = array();
		if(empty($settings) || !is_array($settings)){
			return $this->arrResults;
		}
		$data = $this->load();
		foreach($settings as $keyword){
			if(array_key_exists($keyword, $data)){
				$this->arrResults[$keyword] = $data[$keyword];
			}
		}
		return $this->arrResults;
	}

	/** @deprecated Dùng saveBatch() để lưu nhiều key trong 1 lần. */
	function updateValue($key, $val){
		$this->saveBatch(array($key => $val));
		return true;
	}

	function stripUnicode($str){
		if(!$str) return false;
		$unicode = array(
			'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'd'=>'đ',
			'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'i'=>'í|ì|ỉ|ĩ|ị',
			'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
		);
		foreach($unicode as $nonUnicode=>$uni){
			$str = preg_replace("/($uni)/i",$nonUnicode,$str);
		}
		$str = str_replace(" ","-",$str);
		return $str;
	}

	/*======================================================================*\
	|| Nội bộ
	\*======================================================================*/

	/**
	 * Nạp lười: static → Redis → DB.
	 * @return array
	 */
	private function load(){
		if(self::$store !== null){
			return self::$store;
		}
		$data = $this->readCache();
		if($data === null){
			$data = $this->readDb();
			$this->writeCache($data);
		}
		self::$store = $data;
		return $data;
	}

	/**
	 * ĐÚNG 1 query cho toàn bộ cấu hình.
	 * @return array
	 */
	private function readDb(){
		global $dbconn;
		$data = array();
		$rows = $dbconn->GetAll("SELECT `setting`, `value` FROM `".$this->tbl."`");
		if(empty($rows)){
			return $data;
		}
		foreach($rows as $row){
			$data[$row['setting']] = $row['value'];
		}
		return $data;
	}

	/**
	 * UPDATE nếu đã có row, chưa có mới INSERT. Không bao giờ DELETE.
	 * @return bool
	 */
	private function writeRow($keyword, $value, $userId){
		global $dbconn;
		$keyword = trim($keyword);
		if($keyword === ''){
			return false;
		}
		if(is_array($value)){
			$value = json_encode($value, JSON_UNESCAPED_UNICODE);
		}
		$cond = "`setting` = ".$dbconn->qstr($keyword);
		$count = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `".$this->tbl."` WHERE ".$cond);
		if($count > 0){
			return $this->updateRow($cond, (string) $value, $userId);
		}
		return $this->insertRow($keyword, (string) $value, $userId);
	}

	/** @return bool */
	private function updateRow($cond, $value, $userId){
		global $dbconn;
		$set = "`value` = ".$dbconn->qstr($value);
		if($this->hasColumn('updated_at')){
			$set .= ", `updated_at` = NOW()";
		}
		if($this->hasColumn('userid_updated')){
			$set .= ", `userid_updated` = ".(int) $userId;
		}
		$sql = "UPDATE `".$this->tbl."` SET ".$set." WHERE ".$cond;
		return $dbconn->Execute($sql) !== false;
	}

	/** @return bool */
	private function insertRow($keyword, $value, $userId){
		global $dbconn;
		$fields = array('`setting`', '`value`');
		$values = array($dbconn->qstr($keyword), $dbconn->qstr($value));
		if($this->hasColumn('created_at')){
			$fields[] = '`created_at`';
			$values[] = 'NOW()';
		}
		if($this->hasColumn('updated_at')){
			$fields[] = '`updated_at`';
			$values[] = 'NOW()';
		}
		if($this->hasColumn('userid_updated')){
			$fields[] = '`userid_updated`';
			$values[] = (int) $userId;
		}
		$sql = "INSERT INTO `".$this->tbl."` (".implode(', ', $fields).") VALUES (".implode(', ', $values).")";
		return $dbconn->Execute($sql) !== false;
	}

	/**
	 * Danh sách cột thật — 1 query SHOW COLUMNS cho cả request.
	 * @return bool
	 */
	private function hasColumn($column){
		global $dbconn;
		if(self::$columns === null){
			self::$columns = array();
			$rows = $dbconn->GetAll("SHOW COLUMNS FROM `".$this->tbl."`");
			if(!empty($rows)){
				foreach($rows as $row){
					$name = isset($row['Field']) ? $row['Field'] : reset($row);
					self::$columns[] = $name;
				}
			}
		}
		return in_array($column, self::$columns, true);
	}

	/**
	 * @return Cache|null null khi không có Redis — mọi thứ vẫn chạy, chỉ mất lớp đệm.
	 */
	private static function cacheClient(){
		if(self::$cacheClient === false){
			return null;
		}
		if(self::$cacheClient !== null){
			return self::$cacheClient;
		}
		if(!defined('CACHE_DRIVER') || CACHE_DRIVER !== 'REDIS' || !class_exists('Cache')){
			self::$cacheClient = false;
			return null;
		}
		try {
			self::$cacheClient = new Cache();
		} catch(Exception $e){
			self::$cacheClient = false;
			return null;
		}
		return self::$cacheClient;
	}

	/**
	 * @return array|null null nghĩa là cache miss.
	 */
	private function readCache(){
		$client = self::cacheClient();
		if($client === null){
			return null;
		}
		try {
			// $decoded = false: KHÔNG html_entity_decode, nếu không mọi &amp; trong
			// script nhúng và meta description sẽ bị biến dạng khi qua cache.
			$cached = $client->get(self::CACHE_KEY, null, false);
		} catch(Exception $e){
			return null;
		}
		return is_array($cached) ? $cached : null;
	}

	private function writeCache($data){
		$client = self::cacheClient();
		if($client === null){
			return;
		}
		try {
			$client->put(self::CACHE_KEY, $data, CACHE_LIFETIME);
		} catch(Exception $e){
			// Không ghi được cache thì thôi, request sau đọc lại DB.
		}
	}

	/**
	 * Rút gọn cho .tpl: {'meta_title'|cfg} và {config_get key='logo' default='/logo.png'}.
	 * Đăng ký ở đây vì _header.php đã mã hoá, không sửa được.
	 */
	private function registerHelpers(){
		if(self::$helpersRegistered || empty($GLOBALS['smarty'])){
			return;
		}
		self::$helpersRegistered = true;
		try {
			$GLOBALS['smarty']->registerPlugin('modifier', 'cfg', array('Configuration', 'smartyModifier'));
			$GLOBALS['smarty']->registerPlugin('function', 'config_get', array('Configuration', 'smartyFunction'));
		} catch(Exception $e){
			// Đã đăng ký ở nơi khác — bỏ qua, template vẫn dùng được getter thường.
		}
	}

	public static function smartyModifier($keyword, $default = ''){
		return self::getInstance()->getValue($keyword, $default);
	}

	public static function smartyFunction($params, $template = null){
		$keyword = isset($params['key']) ? $params['key'] : '';
		$default = isset($params['default']) ? $params['default'] : '';
		if($keyword === ''){
			return $default;
		}
		return self::getInstance()->getValue($keyword, $default);
	}
}
?>
