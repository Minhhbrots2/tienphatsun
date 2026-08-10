<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| CrawlConfig — NGUỒN DUY NHẤT cấu hình dải màu sold/break/dq cho nhánh ||
|| cao tầng (highfloor, nhánh cấu-hình-cột). Dùng chung bởi              ||
|| Crawl::getDataAPI và CrawlLocal::resolveColorConfig → sửa màu CHỈ Ở   ||
|| ĐÂY.                                                                  ||
||                                                                       ||
|| Ngữ nghĩa: APPEND (khớp Crawl::getDataNew production) — giữ màu mặc    ||
|| định #FF0000/#EA4335 RỒI cộng thêm màu riêng theo agency/combo.       ||
|| (Lưu ý: nhánh sơ đồ điểm stock_point có màu riêng, xử lý ở chỗ khác.) ||
\*======================================================================*/
 
class CrawlConfig {

	/**
	 * Giải dải màu hiệu lực cho (agency, target). Trả về:
	 *   array("color_sold"=>[], "color_break"=>[], "color_dq"=>[])
	 * SỬA MÀU sold/break/dq Ở ĐÂY (1 nơi duy nhất).
	 */
	public static function resolveColors($agency_id, $target_id) {
		$combo = $agency_id . "_" . $target_id;

		// --- color_sold: mặc định + cộng thêm ---
		$color_sold = array("#FF0000", "#EA4335");
		if ($agency_id == 284)  $color_sold[] = "#A5A5A5"; // Đông Đô
		if ($agency_id == 318)  $color_sold[] = "#8E7CC3"; // Tân Long
		if ($agency_id == 239)  $color_sold[] = "#EA4335"; // Đất Việt
		if ($agency_id == 8801) $color_sold[] = "#CC0000"; // Elite Capital
		if ($combo == "280_10177")   $color_sold[] = "#980000"; // EH-ATD
		if ($combo == "10894_10684") $color_sold[] = "#FFFF00"; // Global Property
		if ($combo == "10897_11047") $color_sold[] = "#D9EAD3"; // Tiến Phát
		if ($combo == "162_11475") { $color_sold[] = "#D9EAD3"; $color_sold[] = "#FFF2CC"; } // VHS CXL

		// --- color_break ---
		$color_break = array("#20124D");
		if ($combo == "176_11038")  $color_break = array();
		if ($combo == "262_11043")  $color_break[] = "#FFF2CC";
		if ($combo == "10572_1349") $color_break[] = "#FF0000"; // VSL

		// --- color_dq ---
		$color_dq = array();
		if ($combo == "10897_10684") $color_dq[] = "#F9CB9C"; // Tiến Phát

		return array(
			"color_sold"  => $color_sold,
			"color_break" => $color_break,
			"color_dq"    => $color_dq,
		);
	}
}
?>
