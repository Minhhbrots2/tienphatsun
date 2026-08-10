<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * BillingCalc — nơi tính tiền DUY NHẤT của giao dịch.
 *
 * Thuần tính toán: KHÔNG truy vấn DB, không đọc $_POST, không phụ thuộc global.
 * Mọi nơi (form nhập, modal quyết toán, import, báo cáo) phải gọi vào đây thay vì
 * chép lại công thức — chép lại là nguồn gốc của "đường tính đôi" (2 chỗ tính ra 2 số khác nhau).
 *
 * ⚠ ÉP KIỂU KHÁC NHAU THEO TỪNG TRƯỜNG — đây là bẫy đã gây sai 10×:
 *   tiền     → processSmartNumber()  : "3.6" ⇒ 36   (xoá dấu phân cách rồi ép int)
 *   phần trăm→ convertToNumber()     : "3.6" ⇒ 3.6  (giữ thập phân)
 * Live có 118/342 GD dùng % thập phân, nên dùng nhầm hàm là sai tiền hàng loạt.
 *
 * ⚠ ĐỤNG TÊN, đọc kỹ trước khi copy:
 *   billing.more_information['commission_value'] = R = Giá tính hoa hồng — một ĐẦU VÀO.
 *   billing_sale.commission_value                = AG = doanh số sau giảm trừ của TỪNG sale — một KẾT QUẢ.
 *   Hai thứ khác hẳn nhau dù trùng tên cột.
 *
 * Quy ước tiền tệ: làm tròn tới ĐỒNG bằng round(), và chỉ làm tròn ở đây.
 */
class BillingCalc {

	/** % hoa hồng sales thực nhận (AI) mặc định khi để trống. */
	const DEFAULT_SALES_COMMISSION_RATE = 50;
	/**
	 * Bộ vai trò ăn hoa hồng theo bậc thang. KHOÁ TẠI ĐÂY để mọi module dùng chung một bộ key.
	 * Trước đây khai báo trong sub_default.php (phạm vi module) nên module report không nạp được.
	 * Key phải khớp đúng cấu hình đã ship (Configuration `billing_commission_tiers`).
	 */
	/**
	 * Vai trò cấp GIAO DỊCH → tiền tố khoá trong `billing.more_information`.
	 *
	 * Khai ở ĐÂY chứ không rải trong module: cùng bộ tiền tố này được dùng ở 4 chỗ
	 * (engine ghi sổ, màn mở quyết toán, màn lưu, template). Rải ra là sớm muộn lệch nhau.
	 */
	public static function billingRolePrefixes(){
		return array(
			'CHANNEL_EXEC' => 'channel_exec',
			'CHANNEL_MANAGER' => 'channel_manager',
			'CHANNEL_DIRECTOR' => 'channel_director',
			'PROJECT_DIRECTOR' => 'project_director',
			'PROJECT_MANAGER' => 'project_manager'
		);
	}

	public static function roles(){
		return array(
			'SALES_MANAGER'     => array('label' => 'Trưởng phòng Kinh doanh', 'role_id' => 8586),
			'SALES_DIRECTOR'       => array('label' => 'Giám đốc Kinh doanh',     'role_id' => 48),
			'CHANNEL_EXEC'  => array('label' => 'Chuyên viên PTĐT',        'role_id' => 9480),
			'CHANNEL_MANAGER'  => array('label' => 'Trưởng phòng PTĐT',       'role_id' => 9479),
			'CHANNEL_DIRECTOR'  => array('label' => 'Giám đốc PTĐT',           'role_id' => 9478),
			// Hai vai trò dự án: ăn trên MỌI giao dịch (cả bán nội bộ lẫn bán qua PTĐT), tỷ lệ do
			// từng dự án quyết chứ không có bậc thang nên tiersDefault() cố ý bỏ trống → nhập tay.
			// TP GDDA chưa có chức danh tương ứng trên hệ thống nên role_id = 0 (chọn người thủ công).
			'PROJECT_DIRECTOR'    => array('label' => 'Giám đốc dự án',          'role_id' => 230),
			'PROJECT_MANAGER'  => array('label' => 'TP GDDA',                 'role_id' => 0)
		);
	}

	/**
	 * Nhãn vai trò dạng phẳng key => tên. Giữ ĐÚNG chuỗi mà màn cấu hình bậc thang
	 * đang hiển thị, để thay `_billing_commission_roles()` của module home mà không vỡ template.
	 */
	public static function roleLabels(){
		return array(
			'SALES_MANAGER'    => 'SALES_MANAGER',
			'SALES_DIRECTOR'      => 'Giám đốc',
			'CHANNEL_EXEC' => 'CV PTĐT',
			'CHANNEL_MANAGER' => 'TP PTĐT',
			'CHANNEL_DIRECTOR' => 'Giám đốc PTĐT',
			'PROJECT_DIRECTOR'   => 'Giám đốc dự án',
			'PROJECT_MANAGER' => 'TP GDDA'
		);
	}

	/**
	 * Bậc thang mặc định (mốc doanh số luỹ kế → tỷ lệ %) khi Configuration chưa có cấu hình.
	 * Chép nguyên bộ số đang chạy ở module home để 2 nơi không lệch nhau.
	 */
	public static function tiersDefault(){
		return array(
			'SALES_MANAGER'    => array(array('min'=>0,'rate'=>4), array('min'=>520000000,'rate'=>5), array('min'=>1040000000,'rate'=>6), array('min'=>1560000000,'rate'=>7), array('min'=>2080000000,'rate'=>8)),
			'SALES_DIRECTOR'      => array(array('min'=>0,'rate'=>4), array('min'=>1560000000,'rate'=>5), array('min'=>3120000000,'rate'=>6), array('min'=>6420000000,'rate'=>7)),
			'CHANNEL_EXEC' => array(array('min'=>0,'rate'=>1.5), array('min'=>800000000,'rate'=>2), array('min'=>2500000000,'rate'=>2.5)),
			'CHANNEL_MANAGER' => array(array('min'=>0,'rate'=>2), array('min'=>1000000000,'rate'=>2.5), array('min'=>3000000000,'rate'=>3)),
			'CHANNEL_DIRECTOR' => array(array('min'=>0,'rate'=>1), array('min'=>2000000000,'rate'=>1.5), array('min'=>4000000000,'rate'=>2))
		);
	}

	/**
	 * Chi phí công ty theo GIAO DỊCH: Ban điều hành + quỹ Back Office. Hằng số toàn công ty,
	 * không phải số nhập tay từng căn — đo 406/406 dòng sổ gốc, KHÔNG có một ngoại lệ nào.
	 *
	 * Hai TẦNG, hai GỐC khác nhau — vì thế tên khoá cố tình khác nhau:
	 *   `rate_ah`   tính trên AH (doanh số tính hoa hồng sau giảm trừ)
	 *   `rate_pool` tính trên QUỸ BACK, không phải trên AH
	 * Dùng chung một tên là sớm muộn có người nhân 30% với AH.
	 *
	 * ⚠ 'Khối back' trên sổ là cột TỔNG, không phải một bên nhận tiền: nó đúng bằng
	 * KT+HCNS+MKT+Trợ lý+Admin (khớp 406/406). Ghi nó thành một dòng chi ngang hàng với
	 * 5 phòng kia thì quỹ back cộng đôi — 996 tr thành 1,33 tỷ trên tập dữ liệu hiện có.
	 * Vì vậy quỹ chỉ là mốc trung gian, backOfficeRows() KHÔNG phát nó ra thành dòng.
	 */
	public static function backOfficeDefault(){
		return array(
			'EXECUTIVE_BOARD' => array('label' => 'Ban điều hành', 'department_id' => 38, 'rate_ah' => 1),
			'BACK_OFFICE' => array('label' => 'Khối Back Office', 'rate_ah' => 0.5, 'chia' => array(
				'SALES_ADMIN' => array('label' => 'Sales Admin', 'department_id' => 12130, 'rate_pool' => 30),
				'MARKETING' => array('label' => 'Marketing', 'department_id' => 324, 'rate_pool' => 25),
				'ACCOUNTING' => array('label' => 'Kế toán', 'department_id' => 12131, 'rate_pool' => 22),
				'HR' => array('label' => 'HR', 'department_id' => 12127, 'rate_pool' => 20),
				'ASSISTANT' => array('label' => 'Trợ lý', 'department_id' => 12126, 'rate_pool' => 3)
			))
		);
	}

	/**
	 * Các khoản chi công ty của MỘT giao dịch, tính từ AH.
	 *
	 * Tiền trả về PHÒNG (quỹ chung), không chia tiếp cho từng người — sổ gốc không có
	 * cột tên nào ở khối này. Trưởng phòng tự chia trong nội bộ.
	 *
	 * $de là mảng số gõ đè theo khoá ('EXECUTIVE_BOARD' => 230178). Cùng cơ chế "để trống = tự tính,
	 * gõ vào = đè" của mọi ô tiền khác. Sổ có ca thật cần tới: TP-22 ghi BĐH 0,0414%.
	 */
	public static function backOfficeRows($ah, $cfg = null, $de = array()){
		$ah = (float) $ah;
		if(!is_array($cfg) || empty($cfg)){
			$cfg = self::backOfficeDefault();
		}
		$ra = array();
		foreach($cfg as $key => $muc){
			$rate = isset($muc['rate_ah']) ? (float) $muc['rate_ah'] : 0;
			$quy = self::roleAmount($ah, $rate);
			$con = isset($muc['chia']) && is_array($muc['chia']) ? $muc['chia'] : array();
			if(empty($con)){
				$ra[] = self::backOfficeRow($key, $muc, $ah, $rate, $quy, $de, 0);
				continue;
			}
			// Mục có chia tiếp thì BẢN THÂN nó không thành dòng chi — xem ghi chú ở backOfficeDefault()
			foreach($con as $ckey => $cmuc){
				$crate = isset($cmuc['rate_pool']) ? (float) $cmuc['rate_pool'] : 0;
				$ra[] = self::backOfficeRow($ckey, $cmuc, $quy, $crate, self::roleAmount($quy, $crate), $de, $rate);
			}
		}
		return $ra;
	}

	/** Một dòng chi công ty. Tách ra để hai nhánh (có/không chia tiếp) không lặp code. */
	private static function backOfficeRow($key, $muc, $base, $rate, $amount, $de, $rate_parent = 0){
		$go_de = isset($de[$key]) && (float) $de[$key] > 0;
		return array(
			'key' => $key,
			'label' => isset($muc['label']) ? $muc['label'] : $key,
			'department_id' => isset($muc['department_id']) ? (int) $muc['department_id'] : 0,
			'base' => round($base),
			'rate' => $rate,
			// Tỷ lệ của quỹ CHA (0 nếu là tầng 1). Màn quyết toán cần đúng hai số này để tính lại
			// tiền khi doanh số đổi — KHÔNG gộp được thành một tỷ lệ tương đương, vì tiền làm tròn
			// HAI LẦN: quỹ tròn trước rồi mới chia. Gộp lại lệch 1đ (MKT ra 633.477 thay vì 633.478),
			// tức số trên form khác số server ghi vào sổ.
			'rate_parent' => $rate_parent,
			'amount' => $go_de ? round((float) $de[$key]) : $amount,
			'manual' => $go_de ? 1 : 0
		);
	}

	/**
	 * Tra tỷ lệ % theo bậc thang: lấy bậc có `min` lớn nhất mà doanh số luỹ kế còn với tới.
	 * $tiers là mảng [['min'=>..,'rate'=>..], ...] KHÔNG bắt buộc sắp xếp sẵn.
	 */
	public static function resolveRate($tiers, $cumulative){
		if(!is_array($tiers) || empty($tiers)){
			return 0;
		}
		$cumulative = self::money($cumulative);
		$rate = 0;
		$best = null;
		foreach($tiers as $tier){
			if(!isset($tier['min'])){
				continue;
			}
			$min = self::money($tier['min']);
			if($cumulative < $min){
				continue;
			}
			if($best === null || $min >= $best){
				$best = $min;
				$rate = self::percent(isset($tier['rate']) ? $tier['rate'] : 0);
			}
		}
		return $rate;
	}

	/**
	 * Tiền: chuỗi thô -> số nguyên đồng.
	 *
	 * ⚠ Dấu chấm KHÔNG phải lúc nào cũng là phân cách nghìn. Bản cũ xoá sạch dấu chấm nên
	 * `"4066383231.0"` — đúng dạng MySQL trả về cho cột `double` — thành 40.663.832.310,
	 * tức GẤP 10 LẦN. Live đã dính 4 giao dịch (SL21915, SL212A02, SL212B12A, SL203A09):
	 * doanh số 1,3 tỷ hiện thành 13 tỷ, và mọi hoa hồng tính trên đó cũng gấp 10.
	 *
	 * Quy tắc phân biệt:
	 *   - Có CẢ dấu phẩy và dấu chấm -> dấu ĐỨNG SAU là thập phân (`"1,234.56"`, `"1.234,56"`)
	 *   - Chỉ dấu chấm -> là phân cách nghìn KHI MỌI nhóm sau dấu đều đúng 3 chữ số
	 *     (`"1.234.567"` = 1234567), ngược lại là thập phân (`"4066383231.0"`, `"3.6"`)
	 *   - Chỉ dấu phẩy -> thập phân kiểu Việt (`"1,5"` = 1,5)
	 */
	public static function money($value, $default = 0){
		if($value === null || $value === '' || $value === false){
			return $default;
		}
		$s = str_replace(array('₫', ' ', ';', '(', ')', "Â "), '', (string) $value);
		$i_cham = strrpos($s, '.');
		$i_phay = strrpos($s, ',');
		if($i_cham !== false && $i_phay !== false){
			$thap_phan = ($i_cham > $i_phay) ? '.' : ',';
		} elseif($i_phay !== false){
			$thap_phan = ',';
		} elseif($i_cham !== false){
			$nhom = explode('.', $s);
			$la_nghin = true;
			for($k = 1; $k < count($nhom); $k++){
				if(!preg_match('/^\d{3}$/', $nhom[$k])){
					$la_nghin = false;
					break;
				}
			}
			$thap_phan = $la_nghin ? '' : '.';
		} else {
			$thap_phan = '';
		}
		if($thap_phan === ''){
			$s = str_replace(array('.', ','), '', $s);
		} else {
			$s = str_replace(($thap_phan === '.') ? ',' : '.', '', $s);
			$s = str_replace($thap_phan, '.', $s);
		}
		return (int) round((float) $s);
	}

	/** Phần trăm: giữ phần thập phân. Dùng cho MỌI trường %. */
	public static function percent($value, $default = 0){
		if($value === null || $value === '' || $value === false){
			return $default;
		}
		$value = str_replace(array('₫', '%', ' ', ';', '(', ')'), '', (string) $value);
		$value = str_replace(',', '.', $value);
		return (float) $value;
	}

	/**
	 * Tính toàn bộ số tiền của MỘT DÒNG SALE (một phần chia của giao dịch).
	 *
	 * Vào (chấp nhận chuỗi thô từ form/sheet, tự ép kiểu):
	 *   commission_value               R  — Giá tính hoa hồng (tiền). Rỗng thì lấy totalgrand.
	 *   commission                     T  — % hoa hồng (%)
	 *   share_ratio                    U  — % chia cho sale này (%). Rỗng = 100.
	 *   totalgrand                     P  — Giá trị bán (tiền)
	 *   total_deduction                AB — Tổng tiền giảm trừ của CẢ CĂN (tiền)
	 *   total_deduction_percent_sales  AC — % phần sales chịu (%)
	 *   total_deduction_sales          AD — tiền sales chịu; null = tự tính AB×AC, khác null = SỬA ĐÈ
	 *   total_deduction_company        AE — công ty chịu (tiền)
	 *   total_deduction_company_commission AF — null = tự tính AB−AD−AE, khác null = SỬA ĐÈ
	 *   sales_commission_rate          AI — % sales thực nhận (%). Rỗng = 50.
	 *
	 * Ra:
	 *   share_value            Q  = P × U
	 *   deduction_sales        AD (cấp CĂN)
	 *   deduction_company_commission AF (cấp CĂN)
	 *   deduction_share        phần AF mà DÒNG SALE này gánh = AF × U
	 *   realized               AG = AH = R×U×T − (AF×U)   ← doanh số tính hoa hồng của sale này
	 *   sales_amount           AJ = AG × AI
	 *   sales_deduct           AL = AD × U
	 *   sales_net              AM = AJ − AL
	 *
	 * Vì sao AB/AD/AE/AF nhân thêm U: các số này là của CẢ CĂN, còn kết quả trả về là của
	 * MỘT sale. Không chia theo tỷ lệ thì mỗi sale trong một GD co-sale đều gánh nguyên
	 * phần giảm trừ của cả căn ⇒ trừ thừa nhiều lần.
	 */
	public static function compute($in){
		$get = function($key) use ($in){
			return isset($in[$key]) ? $in[$key] : null;
		};

		$P  = self::money($get('totalgrand'));
		$R  = self::money($get('commission_value'));
		if($R <= 0){
			$R = $P; // GD chưa nhập giá tính hoa hồng thì lấy giá trị bán
		}
		$T  = self::percent($get('commission'));
		$U  = self::percent($get('share_ratio'), 100);
		$AB = self::money($get('total_deduction'));
		$AC = self::percent($get('total_deduction_percent_sales'));
		$AE = self::money($get('total_deduction_company'));
		// AI dùng ĐÚNG số đã lưu, không thay 0 bằng mặc định: 0 là con số THẬT — căn bán qua
		// đối tác F2 hoặc CTV thì sale nội bộ ăn 0đ. Ép 50% ở đây là trả một khoản không tồn tại;
		// live đã dính GD 17.CH-26 (sổ ghi 0đ, CRM lưu 50% của 345.370.604).
		// Mặc định 50% thuộc về lúc TẠO dòng chia, không thuộc lúc tính.
		$AI = self::percent($get('sales_commission_rate'));

		// AD: mặc định AB×AC, nhưng cho sửa đè khi người nhập truyền vào giá trị khác null
		$AD = $get('total_deduction_sales');
		$AD = ($AD === null || $AD === '') ? ($AB * $AC / 100) : self::money($AD);

		// AF: mặc định AB−AD−AE, cũng cho sửa đè
		$AF = $get('total_deduction_company_commission');
		$AF = ($AF === null || $AF === '') ? ($AB - $AD - $AE) : self::money($AF);

		$ratio = $U / 100;
		$deduction_share = $AF * $ratio;              // phần giảm trừ dòng sale này gánh
		$realized = ($R * $ratio * $T / 100) - $deduction_share;
		$sales_amount = $realized * $AI / 100;
		$sales_deduct = $AD * $ratio;

		return array(
			'share_value'                 => round($P * $ratio),
			'deduction_sales'             => round($AD),
			'deduction_company_commission' => round($AF),
			'deduction_share'             => round($deduction_share),
			'realized'                    => round($realized),
			'sales_amount'                => round($sales_amount),
			'sales_deduct'                => round($sales_deduct),
			'sales_net'                   => round($sales_amount - $sales_deduct)
		);
	}

	/**
	 * Tiền hoa hồng của một vai trò quản lý = doanh số sau giảm trừ × tỷ lệ bậc thang.
	 * $rate là % (vd 0.35 nghĩa là 0,35%), luôn ép bằng percent() để không dính bẫy "3.6"→36.
	 */
	public static function roleAmount($realized, $rate){
		return round(self::money($realized) * self::percent($rate) / 100);
	}
}
