<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Ghi bảng chốt hoa hồng theo LUỒNG MỚI của CRM (quyết toán → 1 dòng / giao dịch).
 *
 * Dùng chung bảng `default_commission` với model Commission, nhưng tách file vì
 * Commission.php là code crawl Google Sheet đang bị khai tử — trộn vào đó thì
 * khi dọn crawl sẽ kéo theo cả luồng mới. Mọi dòng do đây ghi mang
 * `more_information.source = 'crm'`; reader cũ phải bỏ qua dòng có khoá này.
 *
 * Số tiền ghi ở đây là số CHỐT tại thời điểm quyết toán (để kế toán trả tiền),
 * đồng thời giữ đủ dữ liệu gốc (`base`, `cumulative`, `tiers_sig`) để báo cáo năm
 * tính lại và chỉ ra chênh lệch — user chốt 27/07.
 */
class CommissionEntry extends DbBasic{
	const SOURCE = 'crm';
	const ENGINE = 1;

	function __construct(){
		$this->pkey = "commission_id";
		$this->tbl = DB_PREFIX."commission";
	}

	/**
	 * Dòng này do luồng mới ghi hay không.
	 *
	 * Hai màn cũ (popup "Xem giao dịch" và dashboard) LEFT JOIN sang bảng này rồi mở khối
	 * hoa hồng bằng `if(!empty($commission_information))`. Bảng vốn rỗng nên khối đó im;
	 * dòng đầu tiên của luồng mới sẽ đánh thức chúng, mà chúng đọc bộ khoá của luồng crawl
	 * (contract_comm_base, sales_commission_amount, tax_vat_amount…) nên mọi số ra 0đ.
	 * Chúng phải bỏ qua dòng luồng mới cho tới khi có màn xem hoa hồng riêng.
	 */
	public static function isNewFlow($commission_information){
		if(!is_array($commission_information) || empty($commission_information)){
			return false;
		}
		return isset($commission_information['source']) && $commission_information['source'] === self::SOURCE;
	}

	/** Nhớ tạm trong 1 lần chạy: 1 giao dịch co-sale hay có nhiều dòng cùng phòng. */
	private $period_shares = array();
	private $cumulative_cache = array();

	/** Vai trò per-share: cột trên `billing_sale` giữ người đảm nhiệm của DÒNG đó. */
	private function shareRoleColumn($role_key){
		if($role_key == 'SALES_MANAGER') return 'head_of_dep_id';
		if($role_key == 'SALES_DIRECTOR') return 'sale_dir_id';
		return '';
	}

	/**
	 * Ai giữ vai trò TPKD/GĐKD của DÒNG chia này — ĐỌC THẲNG cột đã lưu, KHÔNG suy đoán.
	 *
	 * Trước đây cột trống thì suy từ cây phòng ban. Bỏ hẳn vì suy từ cây gán vai trò cho
	 * MỌI giao dịch trong khối, còn sổ gốc gán có chọn lọc — 190/408 dòng không có quản lý
	 * nào, phần lớn là giao dịch bán qua đại lý/CTV/PTĐT. Hậu quả đo thật: một GĐKD tháng
	 * 07/2026 sổ gốc ghi 1,08 tỷ mà suy từ cây ra 2,25 tỷ ⇒ vượt mốc ⇒ nhảy bậc ⇒ sai tiền
	 * cả tháng. Tệ hơn, nếu chỉ một trong hai chỗ (tiền trả / gốc bậc thang) suy từ cây thì
	 * hệ thống TRẢ theo người này mà CỘNG LŨY KẾ theo người khác.
	 *
	 * Cột đã được backfill từ sổ gốc 28/07 (388 dòng) nên nguồn này đủ dữ liệu. Việc gợi ý
	 * người từ cây phòng ban thuộc về màn quyết toán (prefill), không thuộc model này.
	 */
	private function shareRolePerson($share, $role_key){
		global $core;
		$col = $this->shareRoleColumn($role_key);
		if($col === ''){
			return 0;
		}
		return (int) $core->get_field($share, $col, 0);
	}

	/** Tiền tố khoá trong `billing.more_information` — bảng chung ở BillingCalc. */
	private function billingRolePrefix($role_key){
		$map = BillingCalc::billingRolePrefixes();
		return isset($map[$role_key]) ? $map[$role_key] : '';
	}


	/**
	 * Phạm vi của vai trò — quyết định loại giao dịch nào thì vai trò đó có ăn.
	 *
	 * - `share`   TPKD/GĐKD: chỉ giao dịch bán nội bộ, tính trên TỪNG dòng chia.
	 * - `ptdt`    CV/TP/GĐ PTĐT: chỉ giao dịch bán qua PTĐT, tính trên tổng cả căn.
	 * - `project` GĐ dự án/TP GDDA: ăn trên MỌI loại giao dịch (trừ F2), tổng cả căn.
	 */
	private function roleScope($role_key){
		if($role_key == 'PROJECT_DIRECTOR' || $role_key == 'PROJECT_MANAGER'){
			return 'project';
		}
		return $this->billingRolePrefix($role_key) === '' ? 'share' : 'channel';
	}

	/**
	 * Kỳ tính bậc thang của một giao dịch = THÁNG KÝ, không phải tháng cọc.
	 *
	 * Đo 530 dòng trên sheet gốc: cộng luỹ kế theo THÁNG khớp 76%, theo NĂM chỉ 41% —
	 * và có ca tỷ lệ GIẢM khi luỹ kế năm tăng, tức không thể là bậc thang năm.
	 * Đối chiếu tiếp: `agree_date` của CRM trùng tháng ký của sheet **321/321 dòng**,
	 * còn `deposit_date` chỉ trùng 69% (nó là "thời gian phát sinh" — khớp 404/406 dòng
	 * với cột đó). Giao dịch chưa có ngày ký thì đành lấy ngày cọc.
	 */
	private function periodStamp($oneBilling){
		global $core;
		$agree = (int) $core->get_field($oneBilling, 'agree_date', 0);
		if($agree > 0){
			return $agree;
		}
		$deposit = (int) $core->get_field($oneBilling, 'deposit_date', 0);
		return $deposit > 0 ? $deposit : time();
	}

	/** Mốc đầu/cuối THÁNG chứa mốc thời gian đã cho (unix). */
	private function monthRange($stamp){
		$stamp = (int) $stamp;
		if($stamp <= 0){
			$stamp = time();
		}
		$m = (int) date('n', $stamp);
		$y = (int) date('Y', $stamp);
		return array(mktime(0, 0, 0, $m, 1, $y), mktime(0, 0, 0, $m + 1, 1, $y));
	}

	/**
	 * Mọi dòng chia của một KỲ (tháng), kèm đủ trường để phân giải vai trò. Nạp 1 lần rồi nhớ.
	 *
	 * Phải nạp về PHP chứ không lọc bằng SQL: người giữ vai trò TPKD/GĐKD của một dòng
	 * có thể do CÂY PHÒNG BAN suy ra chứ không nằm trong cột (đo live 27/07: chỉ 2/404
	 * dòng có sẵn cột). Lọc bằng SQL trên cột thì lũy kế gần như bằng 0, ai cũng rơi
	 * xuống bậc thấp nhất — sai tiền một cách hệ thống.
	 */
	private function periodShares($from, $to){
		global $dbconn;
		
		$from = (int) $from;
		$to = (int) $to;
		$memo = $from.'-'.$to;
		if(isset($this->period_shares[$memo])){
			return $this->period_shares[$memo];
		}
		$clsBilling = new Billing();
		$clsBillingSale = new BillingSale();
		// Kỳ lấy theo NGÀY KÝ; giao dịch chưa có ngày ký thì rơi về ngày cọc — cùng
		// quy tắc với periodStamp(), nếu không thì dòng bị đếm vào kỳ khác lúc cộng.
		$rows = $dbconn->GetAll("SELECT `bs`.`billing_sale_id`, `bs`.`staff_id`, `bs`.`department_id`,
				`bs`.`head_of_dep_id`, `bs`.`sale_dir_id`, `bs`.`commission_value`
			FROM `{$clsBillingSale->tbl}` AS `bs`
			INNER JOIN `{$clsBilling->tbl}` AS `b` ON `b`.`billing_id` = `bs`.`billing_id`
			WHERE `b`.`is_cancel` = 0 AND `b`.`is_trash` = 0
				AND IF(`b`.`agree_date` > 0, `b`.`agree_date`, `b`.`deposit_date`) >= {$from}
				AND IF(`b`.`agree_date` > 0, `b`.`agree_date`, `b`.`deposit_date`) < {$to}");
		$this->period_shares[$memo] = empty($rows) ? array() : $rows;
		return $this->period_shares[$memo];
	}

	/**
	 * TỔNG doanh số sau giảm trừ (AG) của một người ở một vai trò TRONG CẢ THÁNG KÝ —
	 * số dùng để tra bậc thang. Tháng lấy theo ngày ký, xem periodStamp().
	 *
	 * ⚠ Là TỔNG CẢ THÁNG, không phải luỹ kế chạy dần: đối chiếu 521 cặp vai-trò/người trên
	 * sheet gốc, tra bậc trên tổng tháng khớp **521/521**, còn luỹ kế chạy dần chỉ 76% và
	 * cộng theo năm chỉ 41%. Nghĩa là mọi giao dịch trong cùng một tháng của một người đều
	 * ăn CÙNG một tỷ lệ, chốt theo tổng tháng.
	 *
	 * ⚠ Hệ quả: quyết toán GIỮA THÁNG chỉ ra tỷ lệ TẠM — các giao dịch còn lại của tháng
	 * chưa phát sinh nên tổng còn thiếu. Vì thế payload ghi kèm `period` để báo cáo biết
	 * dòng nào thuộc tháng chưa đóng mà tính lại.
	 *
	 * Tính trên MỌI giao dịch chưa huỷ trong kỳ, không chỉ giao dịch đã quyết toán:
	 * bậc thang là "doanh số", không phải "doanh số đã chốt tiền".
	 */
	public function cumulativeFor($role_key, $profile_id, $period_stamp){
		global $dbconn;
		$profile_id = (int) $profile_id;
		if($profile_id <= 0){
			return 0;
		}
		list($from, $to) = $this->monthRange($period_stamp);
		$memo = $role_key.'|'.$profile_id.'|'.$from;
		if(isset($this->cumulative_cache[$memo])){
			return $this->cumulative_cache[$memo];
		}
		$total = 0;
		if($this->shareRoleColumn($role_key) !== ''){
			// TPKD/GĐKD: cộng dòng nào mà người này giữ vai trò đó — phân giải bằng ĐÚNG
			// hàm mà lúc tính tiền dùng, nếu không thì gốc bậc thang lệch khỏi gốc trả tiền.
			// CHỈ đếm dòng được gán TƯỜNG MINH — cùng cách phân giải với lúc tính tiền.
			// Suy từ cây gán vai trò cho MỌI giao dịch trong khối, trong khi sổ gốc gán có
			// chọn lọc — 190/408 dòng không có quản lý nào, phần lớn là giao dịch bán qua
			// đại lý/CTV/PTĐT. Đo thật: một GĐKD tháng 07/2026 sổ gốc ghi 1,08 tỷ mà suy từ
			// cây ra 2,25 tỷ ⇒ vượt mốc 1,56 tỷ ⇒ nhảy từ bậc 4% lên 5% ⇒ sai tiền CẢ THÁNG.
			// Cột đã được backfill từ sổ gốc 28/07 (388 dòng) nên nguồn này đủ dữ liệu.
			foreach($this->periodShares($from, $to) as $_row){
				if($this->shareRolePerson($_row, $role_key) === $profile_id){
					$total += (float) $_row['commission_value'];
				}
			}
		} else {
			$pre = $this->billingRolePrefix($role_key);
			if($pre === ''){
				return 0;
			}
			$key = $pre.'_id';
			// PTĐT chỉ lưu tường minh ở cấp giao dịch (không suy từ cây) nên lọc bằng SQL
			// là nhất quán; ăn trên tổng AG cả căn ⇒ cộng mọi dòng chia của căn đó.
			$clsBilling = new Billing();
			$clsBillingSale = new BillingSale()	;
			$row = $dbconn->GetRow("SELECT SUM(`bs`.`commission_value`) AS `total`
				FROM `{$clsBillingSale->tbl}` AS `bs`
				INNER JOIN `{$clsBilling->tbl}` AS `b` ON `b`.`billing_id` = `bs`.`billing_id`
				WHERE JSON_EXTRACT(`b`.`more_information`, '$.{$key}') = {$profile_id}
					AND `b`.`is_cancel` = 0 AND `b`.`is_trash` = 0
					AND IF(`b`.`agree_date` > 0, `b`.`agree_date`, `b`.`deposit_date`) >= {$from}
					AND IF(`b`.`agree_date` > 0, `b`.`agree_date`, `b`.`deposit_date`) < {$to}");
			$total = empty($row['total']) ? 0 : (float) $row['total'];
		}
		// (float) chứ không money(): đây là cột số của DB, money() sẽ bỏ dấu chấm thập phân
		$this->cumulative_cache[$memo] = round($total);
		return $this->cumulative_cache[$memo];
	}

	/** Chữ ký cấu hình bậc thang, để báo cáo biết dòng cũ chốt bằng bộ số nào. */
	private function tiersSignature($tiers){
		return substr(md5(json_encode($tiers)), 0, 8);
	}

	/** `Q1_2026` — cùng định dạng với khoá cấu hình CommissionSheets. Theo kỳ (tháng ký),
	    cùng mốc với bậc thang, để quý của sổ và quý của hoa hồng không lệch nhau. */
	private function quarterId($stamp){
		$ts = (int) $stamp;
		if($ts <= 0){
			$ts = time();
		}
		return 'Q'.((int) ceil(((int) date('n', $ts)) / 3)).'_'.date('Y', $ts);
	}

	/**
	 * Dựng `roles[]` — DANH SÁCH chứ không phải 1 ô/vai trò: một giao dịch co-sale
	 * hai phòng khác nhau có HAI người cùng vai trò TPKD, mỗi người ăn trên AG dòng mình.
	 */
	private function buildRoles($shares, $billing_information, $realized_total, $period_stamp, $tiers, $deal_type){
		global $core;
		$out = array();
		foreach(BillingCalc::roles() as $role_key => $_role){
			// Hai loại giao dịch ăn hai bộ hoa hồng KHÁC HẲN nhau, không giao nhau chút nào:
				// nội bộ ăn TPKD/GĐKD, PTĐT ăn CV/TP/GĐ PTĐT (GĐ dự án/TP GDDA ăn cả hai).
			// do nhân viên nội bộ đứng tên sẽ được suy ra cả TPKD lẫn GĐKD từ cây phòng ban
			// và ăn thêm 2 khoản không hề tồn tại — màn quyết toán đã chặn, chỗ này phải chặn theo.
			// Bán cho F2: sale nội bộ, TPKD/GĐKD và PTĐT đều KHÔNG ăn gì. Nhưng hai vai trò
			// DỰ ÁN thì vẫn có — sổ gốc ghi tiền cho Giám đốc dự án ở 32/44 căn khối F1 và
			// TP GDDA ở 14/44 (tổng 45 triệu). Trước đây chặn sạch f2 vì luật dựng trên đúng
			// 6 dòng khối F2&CTV của bảng cũ; 44 dòng khối F1 nói ngược lại.
			$scope = $this->roleScope($role_key);
			if($scope !== 'project'){
				if($deal_type === 'f2'){
					continue;
				}
				if($scope !== ($deal_type === 'channel' ? 'channel' : 'share')){
					continue;
				}
			}
			$sig = $this->tiersSignature(isset($tiers[$role_key]) ? $tiers[$role_key] : array());
			$tier = isset($tiers[$role_key]) ? $tiers[$role_key] : array();
			$col = $this->shareRoleColumn($role_key);
			if($col !== ''){
				foreach($shares as $_share){
					// LUÔN tin cột đã lưu, không suy từ cây: tiền trả và gốc bậc thang phải
					// dùng CÙNG một cách phân giải, nếu không sẽ trả theo người này mà cộng
					// lũy kế theo người khác. Cột đã backfill từ sổ gốc 28/07 nên đủ dữ liệu;
					// việc gợi ý người từ cây phòng ban thuộc về màn quyết toán, không thuộc đây.
					$pid = $this->shareRolePerson($_share, $role_key);
					if($pid <= 0){
						continue;
					}
					// (float) chứ không money(): commission_value là cột double của DB,
					// money() bỏ dấu chấm nên một giá trị lẻ sẽ thành gấp 10/100 lần.
					$base = round((float) $core->get_field($_share, 'commission_value', 0));
					$cumulative = $this->cumulativeFor($role_key, $pid, $period_stamp);
					$rate = BillingCalc::resolveRate($tier, $cumulative);
					// Admin gõ đè ở màn quyết toán thì ưu tiên số đó — cùng cơ chế "tự tính,
					// cho sửa đè" của AD/AF/CTV/đại lý. Ghi kèm cờ để báo cáo phân biệt được
					// dòng nào theo bậc thang, dòng nào do người quyết định.
					$col_r = ($role_key == 'SALES_MANAGER') ? 'head_of_dep_rate' : 'sale_dir_rate';
					$col_a = ($role_key == 'SALES_MANAGER') ? 'head_of_dep_amount' : 'sale_dir_amount';
					$de_rate = BillingCalc::percent($core->get_field($_share, $col_r, 0));
					$de_amount = round((float) $core->get_field($_share, $col_a, 0));
					$da_de = ($de_rate > 0 || $de_amount > 0);
					if($de_rate > 0){
						$rate = $de_rate;
					}
					$out[] = array(
						'key' => $role_key,
						'staff_profile_id' => $pid,
						'billing_sale_id' => (int) $core->get_field($_share, 'billing_sale_id', 0),
						'base' => $base,
						'cumulative' => $cumulative,
						'rate' => $rate,
						'amount' => $de_amount > 0 ? $de_amount : BillingCalc::roleAmount($base, $rate),
						'manual' => $da_de ? 1 : 0,
						'tiers_sig' => $sig
					);
				}
				continue;
			}
			// Vai trò cấp GIAO DỊCH: 1 người/vai trò/giao dịch, ăn trên tổng AG cả căn.
			// PTĐT tra bậc thang; GĐ dự án/TP GDDA không có bậc (tier rỗng → rate 0) nên buộc
			// phải có số gõ tay — không nhập thì bỏ qua chứ không ghi dòng 0 đồng vào sổ.
			$pre = $this->billingRolePrefix($role_key);
			$pid = (int) $core->get_field($billing_information, $pre.'_id', 0);
			if($pid <= 0){
				continue;
			}
			$cumulative = $this->cumulativeFor($role_key, $pid, $period_stamp);
			$rate = BillingCalc::resolveRate($tier, $cumulative);
			// Cùng cơ chế "để trống = tự tính, gõ vào = đè" của TPKD/GĐKD ở cấp dòng chia
			$de_rate = BillingCalc::percent($core->get_field($billing_information, $pre.'_rate', 0));
			$de_amount = round((float) $core->get_field($billing_information, $pre.'_amount', 0));
			$da_de = ($de_rate > 0 || $de_amount > 0);
			if($de_rate > 0){
				$rate = $de_rate;
			}
			$amount = $de_amount > 0 ? $de_amount : BillingCalc::roleAmount($realized_total, $rate);
			if($amount <= 0){
				continue;
			}
			$out[] = array(
				'key' => $role_key,
				'staff_profile_id' => $pid,
				'billing_sale_id' => 0,
				'base' => $realized_total,
				'cumulative' => $cumulative,
				'rate' => $rate,
				'amount' => $amount,
				'manual' => $da_de ? 1 : 0,
				'tiers_sig' => $sig
			);
		}
		return $out;
	}

	/**
	 * Các khoản chi phí công ty của giao dịch — ĐỌC SỐ ĐÃ LƯU trước, chỉ tính lại khi chưa có.
	 *
	 * `more_information.back_office` giữ số CHÉP TỪ SỔ GỐC (342 căn, 987.356.458 đ — user chốt
	 * 29/07). Nếu ở đây cứ tính lại từ tỷ lệ thì lần quyết toán đầu tiên là ghi đè sạch công đó.
	 *
	 * Và tính lại vốn KHÔNG an toàn: tỷ lệ nằm trong Configuration mà admin sửa được qua màn
	 * "Tỷ lệ chi phí công ty". Đổi Ban điều hành 1% -> 1,2% một lần là mọi giao dịch cũ ra số
	 * khác — lịch sử bị viết lại lặng lẽ. Tiền đã trả là sự kiện đã xảy ra, không phải thứ suy
	 * ra từ cấu hình hôm nay.
	 *
	 * Số gõ đè ở màn quyết toán (`bo_<KEY>_amount`) vẫn thắng cả hai nguồn.
	 */
	public function backOfficeRows($more_information, $realized_total, $_cfg){
		global $core;
		$de = $this->backOfficeOverrides($more_information);
		$luu = $core->get_field($more_information, 'back_office', null);
		if(is_string($luu) && $luu !== ''){
			$luu = json_decode($luu, true);
		}
		if(!is_array($luu) || empty($luu)){
			return BillingCalc::backOfficeRows($realized_total, $this->backOfficeConfig($_cfg), $de);
		}
		$ra = array();
		foreach($luu as $dong){
			if(!is_array($dong) || empty($dong['key'])){
				continue;
			}
			$k = $dong['key'];
			$go_de = isset($de[$k]) && (float) $de[$k] > 0;
			$ra[] = array(
				'key' => $k,
				'label' => isset($dong['label']) ? $dong['label'] : $k,
				'department_id' => isset($dong['department_id']) ? (int) $dong['department_id'] : 0,
				'base' => isset($dong['base']) ? round((float) $dong['base']) : 0,
				'rate' => isset($dong['rate']) ? (float) $dong['rate'] : 0,
				'amount' => $go_de ? round((float) $de[$k]) : (isset($dong['amount']) ? round((float) $dong['amount']) : 0),
				'manual' => $go_de ? 1 : 0,
				// Giữ dấu nguồn để báo cáo phân biệt số của sổ với số engine tự tính
				'source' => isset($dong['source']) ? $dong['source'] : 'crm'
			);
		}
		return empty($ra)
			? BillingCalc::backOfficeRows($realized_total, $this->backOfficeConfig($_cfg), $de)
			: $ra;
	}

	/** Cấu hình chi phí công ty đã lưu; chưa cấu hình thì dùng bộ mặc định của engine. */
	private function backOfficeConfig($_cfg){
		$_cfg = is_object($_cfg) ? $_cfg : new Configuration();
		$saved = $_cfg->getValue('billing_backoffice_rates', '');
		$cfg = !empty($saved) ? json_decode($saved, true) : array();
		return is_array($cfg) && !empty($cfg) ? $cfg : BillingCalc::backOfficeDefault();
	}

	/**
	 * Số gõ đè của khối chi phí công ty: khoá `bo_<key>_amount` trong billing.more_information.
	 * Chỉ tồn tại khi admin thật sự gõ, nên mảng này thường rỗng.
	 */
	private function backOfficeOverrides($more_information){
		global $core;
		$ra = array();
		foreach((array) $more_information as $k => $v){
			if(strpos($k, 'bo_') !== 0 || substr($k, -7) !== '_amount'){
				continue;
			}
			$key = substr($k, 3, -7);
			if($key !== '' && (float) $v > 0){
				$ra[$key] = (float) $v;
			}
		}
		return $ra;
	}

	/**
	 * Ghi/cập nhật dòng chốt hoa hồng của 1 giao dịch. Idempotent theo `billing_id`
	 * (bảng đã có UNIQUE KEY uq_billing nên ghi trùng là lỗi bắt được, không âm thầm).
	 *
	 * Trả về commission_id > 0 nếu thành công, 0 nếu hỏng — người gọi PHẢI kiểm.
	 */
	public function upsertFromBilling($oneBilling, $shares, $more_information, $profile_id = 0, $deal_type = 'sale'){
		global $core, $clsConfiguration;
		// Ngữ cảnh AJAX không chạy full _header nên $clsConfiguration global có thể NULL → tự tạo, khỏi fatal.
		$clsConfiguration = is_object($clsConfiguration) ? $clsConfiguration : new Configuration();
		$billing_id = (int) $core->get_field($oneBilling, 'billing_id', 0);
		if($billing_id <= 0 || empty($shares)){
			return 0;
		}
		// Bất biến Σ tỷ lệ = 100% — cùng ngưỡng mà bước lưu giao dịch đang dùng. Live đang có
		// GD #316 một dòng duy nhất share_ratio=200: không chặn thì AG và mọi gốc bậc thang
		// của giao dịch đó gấp đôi, âm thầm, ngay trong sổ kế toán dùng để trả tiền.
		$sum_ratio = 0;
		foreach($shares as $_s){
			$sum_ratio += BillingCalc::percent(isset($_s['share_ratio']) ? $_s['share_ratio'] : null, 100);
		}
		if(abs($sum_ratio - 100) > 1){
			return 0;
		}
		$deposit_date = (int) $core->get_field($oneBilling, 'deposit_date', 0);
		// Kỳ bậc thang lấy theo THÁNG KÝ (agree_date), không phải tháng cọc — xem periodStamp()
		$period_stamp = $this->periodStamp($oneBilling);
		$totalgrand = BillingCalc::money($core->get_field($oneBilling, 'totalgrand', 0));
		$r_base = BillingCalc::money($core->get_field($more_information, 'commission_value', 0));
		if($r_base <= 0){
			$r_base = $totalgrand;
		}
		// Bậc thang: lấy cấu hình thật, chưa cấu hình thì dùng bộ mặc định của engine
		$saved = $clsConfiguration->getValue('billing_commission_tiers', '');
		$tiers = !empty($saved) ? json_decode($saved, true) : array();
		if(!is_array($tiers)){
			$tiers = array();
		}
		foreach(BillingCalc::tiersDefault() as $_k => $_v){
			if(empty($tiers[$_k])){
				$tiers[$_k] = $_v;
			}
		}
		// Số từng dòng chia: tính lại bằng ĐÚNG engine mà màn quyết toán dùng, từ số đã lưu
		$inputs = array(
			'totalgrand' => $totalgrand,
			'commission_value' => $r_base,
			'commission' => $core->get_field($more_information, 'commission', 0),
			'total_deduction' => $core->get_field($more_information, 'total_deduction', 0),
			'total_deduction_percent_sales' => $core->get_field($more_information, 'total_deduction_percent_sales', 0),
			'total_deduction_company' => $core->get_field($more_information, 'total_deduction_company', 0),
			'total_deduction_sales' => array_key_exists('total_deduction_sales', (array) $more_information) ? $more_information['total_deduction_sales'] : null
		);
		$rows = array();
		$realized_total = $sales_amount_total = $sales_net_total = 0;
		foreach($shares as $_share){
			// KHÔNG dùng get_field cho share_ratio: nó coi "0" là rỗng nên trả về 100, mà
			// tỷ lệ 0 là trạng thái hợp lệ (sale phụ ăn trọn 100%) ⇒ cả sổ nhân đôi.
			// BillingCalc::percent() đã tự lấy 100 khi giá trị là null/rỗng thật.
			$ratio_raw = isset($_share['share_ratio']) ? $_share['share_ratio'] : null;
			$calc = BillingCalc::compute(array_merge($inputs, array(
				'share_ratio' => $ratio_raw,
				'sales_commission_rate' => $core->get_field($_share, 'sales_commission_rate', 0)
			)));
			$rows[] = array(
				'billing_sale_id' => (int) $core->get_field($_share, 'billing_sale_id', 0),
				// KHÔNG dùng khoá `staff_id`: ở luồng crawl cũ đó là Setting id, không phải profile_id
				'staff_profile_id' => (int) $core->get_field($_share, 'staff_id', 0),
				'seller_name' => (string) $core->get_field($_share, 'seller_name', ''),
				'share_ratio' => BillingCalc::percent($ratio_raw, 100),
				'realized' => $calc['realized'],
				'sales_commission_rate' => BillingCalc::percent($core->get_field($_share, 'sales_commission_rate', 0)),
				'sales_amount' => $calc['sales_amount'],
				'sales_deduct' => $calc['sales_deduct'],
				'sales_net' => $calc['sales_net'],
				'ctv_name' => (string) $core->get_field($_share, 'ctv_name', ''),
				'ctv_rate' => BillingCalc::percent($core->get_field($_share, 'ctv_rate', 0)),
				'ctv_amount' => BillingCalc::money($core->get_field($_share, 'ctv_amount', 0))
			);
			$realized_total += $calc['realized'];
			$sales_amount_total += $calc['sales_amount'];
			$sales_net_total += $calc['sales_net'];
		}
		$payload = array(
			'source' => self::SOURCE,
			'engine' => self::ENGINE,
			// Lấy theo nhận diện của người gọi, KHÔNG đọc lại khoá deal_type: khoá đó chỉ do
			// form bước 1 ghi, giao dịch cũ chưa mở lại lần nào sẽ bị dán nhãn "sale" oan.
			'deal_type' => $deal_type,
			// Kỳ tính bậc thang (tháng ký). Tỷ lệ chốt ở đây chỉ CHẮC khi tháng đã đóng —
			// quyết toán giữa tháng thì tổng tháng còn thiếu, báo cáo phải tính lại theo kỳ này.
			'period' => date('Y-m', $period_stamp),
			'period_stamp' => (int) $period_stamp,
			'settled_date' => (int) $core->get_field($more_information, 'settled_date', time()),
			'settled_by' => (int) $profile_id,
			// Giữ số CHÍNH XÁC ở đây: cột contract_total là `float` (~7 chữ số có nghĩa) nên
			// 2.850.000.000 cất xuống thành 2.849.999.872 — sổ kế toán lệch với giao dịch.
			'totalgrand' => $totalgrand,
			'commission_base' => $r_base,
			'realized_total' => $realized_total,
			'sales_amount_total' => $sales_amount_total,
			'sales_net_total' => $sales_net_total,
			'agency_rate' => BillingCalc::percent($core->get_field($more_information, 'agency_rate', 0)),
			'agency_amount' => BillingCalc::money($core->get_field($more_information, 'agency_amount', 0)),
			'shares' => $rows,
			'roles' => $this->buildRoles($shares, $more_information, $realized_total, $period_stamp, $tiers, $deal_type),
			// Chi phí công ty: Ban điều hành + các phòng back-office. Ăn trên MỌI loại giao dịch,
			// kể cả F2 — F2 không có hoa hồng cá nhân nào nhưng vẫn phát sinh phần công ty.
			// Tiền về PHÒNG (quỹ chung), không chia tiếp cho từng người: sổ gốc không có cột tên.
			// ⚠ Gốc ĐÚNG là AH (doanh số tính hoa hồng), còn $realized_total là AG (thi đua).
			// CRM chưa tách hai số này. Đo 427 dòng sổ gốc: AG = AH ở 426 dòng, lệch duy nhất
			// D2003 (AG 0 / AH 551.529.256) ⇒ thiếu 8.272.939 đ ở riêng căn đó. Dùng AG cho
			// nhất quán với roles[] ở trên; ca lệch thì gõ đè. Tách AG/AH thuộc Phase 4.
			'back_office' => $this->backOfficeRows($more_information, $realized_total, $_cfg)
		);
		$fields = array(
			'billing_id' => $billing_id,
			'project_id' => (int) $core->get_field($oneBilling, 'project_id', 0),
			'stock_code' => (string) $core->get_field($oneBilling, 'stock_code', ''),
			'quarter_id' => $this->quarterId($period_stamp),
			'contract_date' => $deposit_date,
			'contract_total' => $totalgrand,
			'more_information' => json_encode($payload, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => (int) $profile_id,
			'is_trash' => 0
		);
		$existing = $this->getOneByBilling($billing_id);
		if(!empty($existing)){
			$commission_id = (int) $existing['commission_id'];
			$this->updateOne($commission_id, $fields);
			return $commission_id;
		}
		$fields['reg_date'] = time();
		$fields['user_id'] = (int) $profile_id;
		// commission_id KHÔNG auto-increment, getMaxId() là MAX+1 không khoá, bảng MyISAM.
		// Hai người quyết toán 2 giao dịch KHÁC nhau cùng lúc sẽ nhận cùng một id và đâm
		// PRIMARY (không phải uq_billing) ⇒ thử lại vài lần trước khi bỏ cuộc.
		for($attempt = 0; $attempt < 3; $attempt++){
			$commission_id = $this->getMaxId();
			$fields[$this->pkey] = $commission_id;
			if($this->insert($fields)){
				return $commission_id;
			}
			// uq_billing chặn: ai đó vừa ghi dòng của CHÍNH giao dịch này ⇒ cập nhật, không bỏ số
			$existing = $this->getOneByBilling($billing_id);
			if(!empty($existing)){
				unset($fields[$this->pkey], $fields['reg_date'], $fields['user_id']);
				$this->updateOne((int) $existing['commission_id'], $fields);
				return (int) $existing['commission_id'];
			}
			// Không phải trùng billing ⇒ trùng id, vòng sau getMaxId() sẽ ra số mới
		}
		return 0;
	}

	/** Đọc dòng theo billing_id — không SELECT *. */
	public function getOneByBilling($billing_id){
		global $dbconn;
		$billing_id = (int) $billing_id;
		if($billing_id <= 0){
			return array();
		}
		$row = $dbconn->GetRow("SELECT `commission_id`, `billing_id`, `more_information`, `is_trash`
			FROM `{$this->tbl}` WHERE `billing_id` = {$billing_id} LIMIT 0,1");
		return empty($row) ? array() : $row;
	}

	/**
	 * Huỷ theo BILLING_ID, không theo commission_id cất trong billing.more_information —
	 * đó chính là chỗ hỏng của Billing::sync_commission (khoá không bao giờ được ghi
	 * nên updateOne(null,…) trả về ngay, huỷ câm).
	 */
	public function cancelByBilling($billing_id, $profile_id = 0){
		$existing = $this->getOneByBilling($billing_id);
		if(empty($existing)){
			return false;
		}
		$payload = json_decode((string) $existing['more_information'], true);
		if(!is_array($payload)){
			$payload = array();
		}
		$payload['canceled_date'] = time();
		$payload['canceled_by'] = (int) $profile_id;
		$this->updateOne((int) $existing['commission_id'], array(
			'is_trash' => 1,
			'more_information' => json_encode($payload, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => (int) $profile_id
		));
		return true;
	}
}
