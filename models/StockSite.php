<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/* StockSite — bảng nối stock ↔ site (default_stock_site): chuẩn hoá cột show_website để INDEX được.
   Site code đang dùng: user.fh | MOC | partner.fh.
   Giai đoạn 1: chạy SONG SONG với cột show_website (mirror). show_website vẫn là nguồn cho hiển thị/checkShow;
   bảng này là bản sao có index để lọc theo site. Mọi nơi ghi show_website nên gọi syncFromShowWebsite() kèm theo.
   Khuyến nghị schema: thêm UNIQUE KEY (stock_id, site_code) để chống trùng (các hàm dưới vẫn an toàn nếu chưa có). */
class StockSite extends dbBasic{
	function __construct(){
		$this->pkey = "stock_site_id";
		$this->tbl  = DB_PREFIX."stock_site";
	}
	/* Mảng site_code của 1 stock, vd ['user.fh','MOC'] */
	function getSites($stock_id){
		$stock_id = (int)$stock_id;
		if($stock_id <= 0) return array();
		$rows = $this->getAll("`stock_id`='{$stock_id}'", "`site_code`");
		$ret = array();
		if(!empty($rows)) foreach($rows as $r){ $ret[] = $r['site_code']; }
		return $ret;
	}
	/* Site đang bật cho stock? */
	function isOn($stock_id, $site_code){
		global $dbconn;
		$stock_id = (int)$stock_id; $site_code = trim($site_code);
		if($stock_id <= 0 || $site_code === '') return false;
		return $this->countItem("`stock_id`='{$stock_id}' AND `site_code`=".$dbconn->qstr($site_code)) > 0;
	}
	/* Bật 1 site (idempotent — đã bật thì bỏ qua) */
	function turnOn($stock_id, $site_code){
		$stock_id = (int)$stock_id; $site_code = trim($site_code);
		if($stock_id <= 0 || $site_code === '') return false;
		if($this->isOn($stock_id, $site_code)) return true;
		return $this->insert(array(
			'stock_id'  => $stock_id,
			'site_code' => $site_code,
			'reg_date'  => time()
		)) ? true : false;
	}
	/* Tắt 1 site */
	function turnOff($stock_id, $site_code){
		global $dbconn;
		$stock_id = (int)$stock_id; $site_code = trim($site_code);
		if($stock_id <= 0 || $site_code === '') return false;
		$this->deleteByCond("`stock_id`='{$stock_id}' AND `site_code`=".$dbconn->qstr($site_code));
		return true;
	}
	/* Toggle 1 site, trả về trạng thái MỚI: true=on, false=off */
	function toggle($stock_id, $site_code){
		if($this->isOn($stock_id, $site_code)){ $this->turnOff($stock_id, $site_code); return false; }
		$this->turnOn($stock_id, $site_code);
		return true;
	}
	/* Đồng bộ TOÀN BỘ site của stock = đúng mảng $codes (diff: chỉ thêm thiếu, xoá thừa → giữ reg_date cũ) */
	function setSites($stock_id, $codes){
		$stock_id = (int)$stock_id;
		if($stock_id <= 0) return false;
		$codes = $this->normalizeCodes($codes);
		$cur   = $this->getSites($stock_id);
		foreach(array_diff($cur, $codes) as $c){ $this->turnOff($stock_id, $c); }
		foreach(array_diff($codes, $cur) as $c){ $this->turnOn($stock_id, $c); }
		return true;
	}
	/* Cầu nối: từ chuỗi show_website (|a||b|) → đồng bộ junction. Gọi kèm mỗi khi ghi show_website. */
	function syncFromShowWebsite($stock_id, $show_website){
		global $clsISO;
		$codes = !empty($show_website) ? $clsISO->getArrayByTextSlash($show_website) : array();
		return $this->setSites($stock_id, $codes);
	}
	/* Xoá toàn bộ site của 1 stock (dùng khi xoá/huỷ stock) */
	function clearStock($stock_id){
		$stock_id = (int)$stock_id;
		if($stock_id <= 0) return false;
		$this->deleteByCond("`stock_id`='{$stock_id}'");
		return true;
	}
	/* Câu con stock_id theo site — dùng cho filter CÓ INDEX (giai đoạn sau):
	   ví dụ: $cond.= " AND `stock_id` IN (".$clsStockSite->subqueryStockIdsBySite('user.fh').")"; */
	function subqueryStockIdsBySite($site_code){
		global $dbconn;
		return "SELECT `stock_id` FROM `{$this->tbl}` WHERE `site_code`=".$dbconn->qstr($site_code);
	}
	/* Chuẩn hoá mảng code: trim, bỏ rỗng, bỏ trùng */
	function normalizeCodes($codes){
		if(empty($codes) || !is_array($codes)) return array();
		$out = array();
		foreach($codes as $c){ $c = trim($c); if($c !== '' && !in_array($c, $out)) $out[] = $c; }
		return $out;
	}
}
