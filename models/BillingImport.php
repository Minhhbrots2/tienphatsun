<?php
/**
 * BillingImport — engine nhập giao dịch (billing) từ Google Sheet.
 *
 * Ghi bản ghi GIỐNG HỆT form thủ công (home::default_pop_save_billing): resolve nhân viên theo
 * `code` → suy chuỗi phòng ban; tra dự án (code/slug, trống → _PROJECT_OTHER_ID); tra căn;
 * property (loại/tình trạng/quỹ/nguồn/đại lý) khớp title_vn OR property_code OR slug OR title.
 *
 * CO-SALE: nhiều sale chung 1 căn → gom theo (dự án + mã căn) thành 1 billing (sale chính = tỷ lệ
 * cao nhất, totalgrand = full giá trị) + N dòng default_billing_sale (mỗi sale + tỷ lệ chia + phần giá trị).
 *
 * Cấu hình cột dùng CHUNG lưu Configuration key billing_import_column_config = JSON {colIndex: fieldKey}.
 */
class BillingImport {
	const CONFIG_KEY = 'billing_import_column_config';
	private $clsISO;
	private $clsConfiguration;
	private $clsBilling;
	private $clsBillingSale;
	private $clsProfile;
	private $clsProject;
	private $clsProperty;
	private $clsStock;
	function __construct(){
		global $clsISO, $clsConfiguration;
		$this->clsISO = is_object($clsISO) ? $clsISO : new ISO();
		$this->clsConfiguration = is_object($clsConfiguration) ? $clsConfiguration : new Configuration();
		$this->clsBilling = new Billing();
		$this->clsBillingSale = new BillingSale();
		$this->clsProfile = new Profile();
		$this->clsProject = new Project();
		$this->clsProperty = new Property();
		$this->clsStock = new Stock();
	}
	/** Danh sách trường có thể map cột (theo Form tạo giao dịch). required=1: bắt buộc. */
	function getFields(){
		return array(
			'staff_code'                   => array('label' => 'Mã NV (sales bán)', 'required' => 0),
			'seller_name'                  => array('label' => 'Người bán (Sales)', 'required' => 0),
			'project_name'                 => array('label' => 'Tên dự án', 'required' => 1),
			'stock_code'                   => array('label' => 'Mã căn', 'required' => 1),
			'phan_khu'                     => array('label' => 'Phân khu (Loại - Tên PK)', 'required' => 0),
			'billing_type'                 => array('label' => 'Loại hình (Loại)', 'required' => 0),
			'contract_status'              => array('label' => 'Tình trạng ký', 'required' => 0),
			'state_id'                     => array('label' => 'Trạng thái', 'required' => 0),
			'stock_resource'               => array('label' => 'Nguồn gốc / Quỹ (đại lý)', 'required' => 0),
			'billing_source_id'            => array('label' => 'Nguồn quỹ', 'required' => 0),
			'sold_to_type'                 => array('label' => 'Bán cho', 'required' => 0),
			'sale_agency_id'               => array('label' => 'Đại lý bán', 'required' => 0),
			'admin_code'                   => array('label' => 'Mã NV admin phụ trách', 'required' => 0),
			'project_director_code'        => array('label' => 'Mã NV GĐDA', 'required' => 0),
			'time_signing'                 => array('label' => 'Ngày ký (Tháng ký)', 'required' => 0),
			'type_signing'                 => array('label' => 'Loại hình ký', 'required' => 0),
			'deposit_date'                 => array('label' => 'Ngày cọc/phát sinh', 'required' => 0),
			'agree_date'                   => array('label' => 'Ngày ký VBTT', 'required' => 0),
			'contract_date'                => array('label' => 'Ngày ký HĐMB', 'required' => 0),
			'totalgrand'                   => array('label' => 'Số tiền (Giá trị bán - full)', 'required' => 0),
			'share_value'                  => array('label' => 'Giá trị sales bán (phần của sale)', 'required' => 0),
			'commission_value'             => array('label' => 'Giá tính hoa hồng (tạm tính)', 'required' => 0),
			'share_ratio'                  => array('label' => 'Tỷ lệ chia (%)', 'required' => 0),
			'realized_sales_compete'       => array('label' => 'Doanh số thi đua (sau giảm trừ)', 'required' => 0),
			'realized_sales_commission'    => array('label' => 'Doanh số hoa hồng (sau giảm trừ)', 'required' => 0),
			'commission'                   => array('label' => '% Hoa hồng sale', 'required' => 0),
			'sale_bonus'                   => array('label' => 'Thưởng sale', 'required' => 0),
			'support_sale'                 => array('label' => 'Tiền hỗ trợ', 'required' => 0),
			'hot_bonus_customer'           => array('label' => 'Thưởng nóng khách hàng', 'required' => 0),
			'hot_bonus_agency'             => array('label' => 'Thưởng nóng đại lý (CTV/ĐL)', 'required' => 0),
			'bonus_agency'             	   => array('label' => 'Thưởng đại lý', 'required' => 0),
			'hot_bonus'             	   => array('label' => 'Thưởng nóng', 'required' => 0),
			'sale_dir_commission_rate'     => array('label' => '% HH GĐKD', 'required' => 0),
			'regional_dir_commission_rate' => array('label' => '% HH GĐ Vùng', 'required' => 0),
			'project_dir_commission_rate'  => array('label' => '% HH GĐDA', 'required' => 0),
			'customer_name'                => array('label' => 'Tên khách hàng', 'required' => 0),
			'customer_phone'               => array('label' => 'SĐT khách hàng', 'required' => 0),
			'customer_email'               => array('label' => 'Email khách hàng', 'required' => 0),
			'notes'                        => array('label' => 'Ghi chú', 'required' => 0),
			'block_name'                   => array('label' => 'Khối (đối chiếu)', 'required' => 0),
			'department_name'              => array('label' => 'Phòng (đối chiếu)', 'required' => 0),
			'total_deduction'              => array('label' => 'Tổng tiền giảm trừ', 'required' => 0),
			'total_deduction_percent_sales' => array('label' => '% Tỷ lệ sales chịu', 'required' => 0),
			'total_deduction_company' 		=> array('label' => 'Công ty chịu', 'required' => 0),
		);
	}
	/** Trường property → property_type để resolve. */
	private function propertyTypeMap(){
		return array(
			'billing_type'      => '_BILLING_TYPE',
			'contract_status'   => '_STATUS_CONTRACT',
			'state_id'          => 'BILLING_STATE',
			'stock_resource'    => '_AGENCY',
			'billing_source_id' => 'BILLING_SOURCE',
			'sold_to_type'      => 'SALE_TYPE',
			'sale_agency_id'    => '_AGENCY'
		);
	}
	/** Đổi chỉ số cột 0-based → chữ cột Google Sheet (0=A, 25=Z, 26=AA). */
	function colLetter($index){
		$index = (int) $index + 1;
		$letter = '';
		while($index > 0){
			$rem = ($index - 1) % 26;
			$letter = chr(65 + $rem) . $letter;
			$index = intdiv($index - 1, 26);
		}
		return $letter;
	}
	/** Escape 1 giá trị chuỗi cho mệnh đề WHERE (trả về đã có dấu nháy). */
	private function q($v){
		global $dbconn;
		return $dbconn->qstr(trim((string) $v));
	}
	/** Đọc toàn bộ sheet (FORMATTED_VALUE) → mảng 2 chiều [row][col]. Có thể ném exception. */
	function readSheet($spreadsheetId, $range){
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		// Tên tab có dấu cách (kể cả space đầu/cuối) → PHẢI bọc nháy đơn theo A1 notation; escape nháy đơn bên trong.
		// KHÔNG trim tên tab ở caller: tab có thể có space cuối, trim làm tên lệch → API "Unable to parse range".
		$safeRange = "'" . str_replace("'", "''", $range) . "'";
		$response = $service->spreadsheets_values->get($spreadsheetId, $safeRange, array(
			'valueRenderOption' => 'FORMATTED_VALUE'
		));
		$data = $response->getValues();
		return !empty($data) ? $data : array();
	}
	/** Cấu hình cột đang lưu: [colIndex => fieldKey]. */
	function getConfig(){
		$raw = $this->clsConfiguration->getValue(self::CONFIG_KEY, '');
		if(empty($raw)){
			return array();
		}
		$arr = json_decode($raw, true);
		return is_array($arr) ? $arr : array();
	}
	/** Lưu cấu hình cột dùng chung. $columns: [colIndex => fieldKey]. */
	function saveConfig($columns){
		$clean = array();
		$used = array();
		if(!empty($columns)){
			foreach($columns as $colIndex => $fieldKey){
				$fieldKey = trim($fieldKey);
				if($fieldKey === ''){
					continue;
				}
				if(in_array($fieldKey, $used)){
					return array('result' => false, 'msg' => 'Một trường bị gán cho nhiều cột. Mỗi trường chỉ 1 cột.');
				}
				$used[] = $fieldKey;
				$clean[(int) $colIndex] = $fieldKey;
			}
		}
		$this->clsConfiguration->updateValue(self::CONFIG_KEY, json_encode($clean, JSON_UNESCAPED_UNICODE));
		return array('result' => true, 'msg' => 'Đã lưu cấu hình cột.');
	}
	/** Parse ngày từ chuỗi sheet (hỗ trợ dd/mm/yyyy) → timestamp, 0 nếu rỗng/sai. */
	private function parseDate($str){
		$str = trim((string) $str);
		if($str === ''){
			return 0;
		}
		if(preg_match('#^(\d{1,2})[/\-](\d{1,2})[/\-](\d{4})#', $str, $m)){
			$str = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
		}
		$time = strtotime($str);
		return $time !== false ? $time : 0;
	}
	/** Resolve 1 property theo type, khớp title_vn (mã ngắn) OR property_code OR slug OR title. 0 nếu không thấy. */
	private function resolveProperty($property_type, $value){
		$value = trim((string) $value);
		if($value === ''){
			return 0;
		}
		$slug = $this->clsISO->replaceSpace($value);
		$cond = "`is_trash`=0 AND `property_type`='" . $property_type . "' AND ("
			. "`title_vn`=" . $this->q($value)
			. " OR `property_code`=" . $this->q($value)
			. " OR `slug`=" . $this->q($slug)
			. " OR `slug`=" . $this->q($value)
			. " OR `title`=" . $this->q($value) . ")";
		$one = $this->clsProperty->getByCond($cond, $this->clsProperty->pkey);
		return !empty($one) ? (int) $one[$this->clsProperty->pkey] : 0;
	}
	/** Resolve _BLOCK (phân khu) theo TÊN trong ĐÚNG dự án (for_id=project). 0 nếu không thấy. */
	private function resolveBlockInProject($project_id, $name){
		$name = trim((string) $name);
		if($name === '' || (int) $project_id <= 0){
			return 0;
		}
		$slug = $this->clsISO->replaceSpace($name);
		$cond = "`is_trash`=0 AND `property_type`='_BLOCK' AND `for_id`='" . (int) $project_id . "' AND ("
			. "`title`=" . $this->q($name)
			. " OR `title_vn`=" . $this->q($name)
			. " OR `slug`=" . $this->q($slug) . ")";
		$one = $this->clsProperty->getByCond($cond, $this->clsProperty->pkey);
		return !empty($one) ? (int) $one[$this->clsProperty->pkey] : 0;
	}
	/** Resolve profile_id theo `code`. 0 nếu không thấy. */
	private function resolveStaffId($code){
		$code = trim((string) $code);
		if($code === ''){
			return 0;
		}
		$one = $this->clsProfile->getByCond("`code`=" . $this->q($code), "`" . $this->clsProfile->pkey . "`");
		return !empty($one) ? (int) $one[$this->clsProfile->pkey] : 0;
	}
	/** GĐKD (role _ROLE_GD_SALE=48) của KHỐI: NV role 48 có |khoi_id| trong list_department_id (thuộc subtree khối). */
	private function resolveGdkdOfKhoi($khoi_id){
		$khoi_id = (int) $khoi_id;
		if($khoi_id <= 0){
			return 0;
		}
		$cond = "`role_id`='" . _ROLE_GD_SALE . "' AND `list_department_id` LIKE '%|" . $khoi_id . "|%' AND `is_trash`=0 AND `is_active`=1 AND `status_id`='" . _STATUS_STAFF_ON_ID . "'";
		$one = $this->clsProfile->getByCond($cond, "`" . $this->clsProfile->pkey . "`");
		return !empty($one) ? (int) $one[$this->clsProfile->pkey] : 0;
	}
	/** TPKD (role _ROLE_HEAD_SALE=8586) của PHÒNG: NV role 8586 ở đúng department của sale. */
	private function resolveTpkdOfPhong($department_id){
		$department_id = (int) $department_id;
		if($department_id <= 0){
			return 0;
		}
		$cond = "`role_id`='" . _ROLE_HEAD_SALE . "' AND `department_id`='" . $department_id . "' AND `is_trash`=0 AND `is_active`=1 AND `status_id`='" . _STATUS_STAFF_ON_ID . "'";
		$one = $this->clsProfile->getByCond($cond, "`" . $this->clsProfile->pkey . "`");
		return !empty($one) ? (int) $one[$this->clsProfile->pkey] : 0;
	}
	/** Một dòng rỗng (không đủ dữ liệu để tạo GD) thì bỏ qua im lặng. */
	private function isEmptyRow($vals){
		$staff = isset($vals['staff_code']) ? trim($vals['staff_code']) : '';
		$stock = isset($vals['stock_code']) ? trim($vals['stock_code']) : '';
		$project = isset($vals['project_name']) ? trim($vals['project_name']) : '';
		return ($staff === '' && $stock === '' && $project === '');
	}
	/**
	 * Resolve các trường của 1 dòng (KHÔNG dựng insert, KHÔNG chống trùng — việc đó gom theo căn ở run()).
	 * Trả về ['status'=>ok|error, 'reason'=>?, 'warnings'=>[], 'data'=>[...], 'preview'=>[...]].
	 */
	function resolveRowData($vals, $billing_type_default){
		global $dbconn, $core, $clsISO;
		$warnings = array();
		$get = function($k) use ($vals){ return isset($vals[$k]) ? trim($vals[$k]) : ''; };
		// Phân khu: cột gộp "Loại - Tên phân khu" (vd "Cao tầng - Park Residence"). Tách theo dấu '-' ĐẦU tiên:
		// trái = loại hình, phải = tên phân khu. Không có '-' → chỉ có loại, không phân khu.
		$phan_khu_type = $phan_khu_name = '';
		$phan_khu_raw = $get('phan_khu');
		if($phan_khu_raw !== ''){
			$parts = explode('-', $phan_khu_raw, 2);
			$phan_khu_type = trim($parts[0]);
			$phan_khu_name = isset($parts[1]) ? trim($parts[1]) : '';
		}
		// Loại hình: cột "Loại" riêng; trống thì lấy phần loại của cột Phân khu; vẫn trống → mặc định của lô
		$billing_type = 0;
		$type_title = $get('billing_type');
		if($type_title === '' && $phan_khu_type !== ''){
			$type_title = $phan_khu_type;
		}
		if($type_title !== ''){
			$billing_type = $this->resolveProperty('_BILLING_TYPE', $type_title);
			if($billing_type === 0){
				$warnings[] = 'Không khớp loại hình: ' . $type_title;
			}
		}
		if($billing_type <= 0){
			$billing_type = (int) $billing_type_default;
		}
		if($billing_type <= 0){
			//return $this->rowResult('error', 'Thiếu loại hình giao dịch (cột Loại trống & chưa chọn mặc định)', $warnings, $vals);
		}
		// Nhân viên theo `code`; không có/không thấy → CHO PHÉP staff_id=0, lưu tên người bán
		$staff_code = $get('staff_code');
		$seller_name = $get('seller_name');
		$staff_id = $department_id = $regional_id = $head_of_dep_id = $regional_director_id = 0;
		$gdkd_id = $tpkd_id = 0;
		if($staff_code !== ''){
			$oStaff = $this->clsProfile->getByCond("`code`=" . $this->q($staff_code), "`" . $this->clsProfile->pkey . "`,`department_id`,`full_name`");
			if(!empty($oStaff)){
				$staff_id = (int) $oStaff[$this->clsProfile->pkey];
				$department_id = (int) $oStaff['department_id'];
				$chain = $this->clsProperty->resolveStaffDepChain($department_id);
				$regional_id = (int) $chain['region_id'];
				$head_of_dep_id = (int) $chain['head_of_dep_id'];
				$regional_director_id = (int) $chain['regional_director_id'];
				// GĐKD (role _ROLE_GD_SALE) của KHỐI chứa sale + TPKD (role _ROLE_HEAD_SALE) của PHÒNG sale
				$gdkd_id = $this->resolveGdkdOfKhoi($regional_id);
				$tpkd_id = $this->resolveTpkdOfPhong($department_id);
				if($seller_name === ''){
					$seller_name = $oStaff['full_name'];
				}
			} else {
				$warnings[] = 'Không tìm thấy NV code "' . $staff_code . '" — staff_id=0, lưu tên người bán';
			}
		} else if($seller_name !== ''){
			$warnings[] = 'Không có Mã NV — staff_id=0, người bán: ' . $seller_name;
		}
		// Dự án theo code/slug; trống hoặc không khớp → Dự án khác (_PROJECT_OTHER_ID)
		$project_name = $get('project_name');
		$project_id = _PROJECT_OTHER_ID;
		if($project_name !== ''){
			$slug = $core->replaceSpace($project_name);
			$oProject = $this->clsProject->getByCond("`is_trash`=0 AND (`code`=" . $this->q($project_name) . " OR `slug`=" . $this->q($slug) . ")", "`" . $this->clsProject->pkey . "`");
			if(!empty($oProject)){
				$project_id = (int) $oProject[$this->clsProject->pkey];
			} else {
				$warnings[] = 'Không tìm thấy dự án "' . $project_name . '" — dùng Dự án khác';
			}
		} else {
			$warnings[] = 'Trống Tên dự án — dùng Dự án khác';
		}
		// Mã căn bắt buộc (để gom co-sale); tra căn lấy stock_id/block_id/investor_id
		$stock_code = $get('stock_code');
		if($stock_code === ''){
			return $this->rowResult('error', 'Thiếu Mã căn', $warnings, $vals);
		}
		$stock_id = $block_id = $building_id = $stock_type = $bedroom_id = $type_id = $home_direction_id = $investor_id = 0;
		$field = "`t1`.`" . $this->clsStock->pkey . "`,`t1`.`block_id`,`t1`.`building_id`,`t1`.`stock_type`,`t1`.`bedroom_id`,`t1`.`type_id`,`t1`.`home_direction_id`,`t2`.`more_information`";
		$oStock = $dbconn->getRow("SELECT " . $field . " FROM " . $this->clsStock->tbl . " AS `t1` LEFT JOIN " . $this->clsProperty->tbl . " AS `t2` ON `t1`.`block_id`=`t2`.`property_id` WHERE `t1`.`ms_code`=" . $this->q($stock_code) . " AND `t1`.`project_id`=" . $project_id);
		if(!empty($oStock)){
			$mi = $this->clsISO->to_array_json($oStock['more_information']);
			$investor_id = (int) $core->get_field($mi, 'investor_id', 0);
			$stock_id = (int) $oStock[$this->clsStock->pkey];
			$block_id = (int) $oStock['block_id'];
			$building_id = (int) $oStock['building_id'];
			$stock_type = (int) $oStock['stock_type'];
			$bedroom_id = (int) $oStock['bedroom_id'];
			$type_id = (int) $oStock['type_id'];
			$home_direction_id = (int) $oStock['home_direction_id'];
			$billing_type = 0;
			if($oStock["stock_type"] == _BLOCK_TYPE_LOWFLOOR_SALE ) {
				$billing_type = _BILLING_TYPE_TT_ID;
			}elseif($oStock["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE ) {
				$billing_type = _BILLING_TYPE_CT_ID;
			}
			
		} else {
			// Không check mã căn: căn không có trong DB thì vẫn ghi (stock_id=0), không cảnh báo.
		}
		// Phân khu từ sheet → _BLOCK theo dự án. Chỉ ĐIỀN khi căn chưa cho block (tránh đè dữ liệu căn đúng);
		// cả hai có mà lệch → cảnh báo, giữ block của căn.
		if($phan_khu_name !== ''){
			$pk_block = $this->resolveBlockInProject($project_id, $phan_khu_name);
			if($pk_block > 0){
				if($block_id <= 0){
					$block_id = $pk_block;
				} else if($block_id !== $pk_block){
					$warnings[] = 'Phân khu lệch: sheet="' . $phan_khu_name . '" vs block của căn (#' . $block_id . ')';
				}
			} else {
				$warnings[] = 'Không khớp phân khu "' . $phan_khu_name . '" trong dự án';
			}
		}
		// Property: tình trạng, trạng thái, nguồn gốc, nguồn quỹ, bán cho, đại lý
		$prop = array();
		foreach($this->propertyTypeMap() as $fkey => $ptype){
			if($fkey === 'billing_type'){
				continue;
			}
			$raw = $get($fkey);
			$prop[$fkey] = 0;
			if($raw !== ''){
				$prop[$fkey] = $this->resolveProperty($ptype, $raw);
				if($prop[$fkey] === 0){
					$warnings[] = 'Không khớp ' . $fkey . ': ' . $raw;
				}
			}
		}
		// Admin + GĐDA theo code
		$admin_id = $this->resolveStaffId($get('admin_code'));
		if($get('admin_code') !== '' && $admin_id === 0){
			$warnings[] = 'Không tìm thấy admin code: ' . $get('admin_code');
		}
		$project_director_id = $this->resolveStaffId($get('project_director_code'));
		if($get('project_director_code') !== '' && $project_director_id === 0){
			$warnings[] = 'Không tìm thấy GĐDA code: ' . $get('project_director_code');
		}
		// Ngày + tiền + hoa hồng + tỷ lệ chia
		$deposit_date = $this->parseDate($get('deposit_date'));
		$totalgrand = $this->clsISO->processSmartNumber($get('totalgrand'));
		if($totalgrand <= 0){
			$warnings[] = 'Số tiền = 0';
		}
		$share_value = $this->clsISO->processSmartNumber($get('share_value'));
		$commission_value = $this->clsISO->processSmartNumber($get('commission_value'));
		$share_ratio = $this->clsISO->convertToNumber($get('share_ratio'));
		// Doanh số sau giảm trừ (thi đua / hoa hồng) — số như totalgrand, lưu more_information (billing không có cột).
		$realized_sales_compete = $this->clsISO->processSmartNumber($get('realized_sales_compete'));
		$realized_sales_commission = $this->clsISO->processSmartNumber($get('realized_sales_commission'));
		// Đối chiếu Khối theo NV (cảnh báo, không chặn)
		$block_name = $get('block_name');
		if($block_name !== '' && $regional_id > 0){
			$region_title = $this->clsProperty->getTitle($regional_id);
			if($this->clsISO->replaceSpace($region_title) !== $this->clsISO->replaceSpace($block_name)){
				$warnings[] = 'Khối lệch: sheet="' . $block_name . '" vs NV="' . $region_title . '"';
			}
		}
		#
		$arr_status_contract_by_code_cached = $this->clsProperty->getArraySearchByKeyCode("_STATUS_CONTRACT");
		$type_signing = $get("type_signing");
		$time_signing = $get("time_signing");
		$contract_status_id = $agree_status_id = $agree_date = $contract_date = 0;
		if(str_contains($core->replaceSpace($type_signing), "hdmb")) {
			$contract_date = ($time_signing) ? $this->parseDate($time_signing):  0;
			$contract_status_id = _CONTRACT_STATUS_DONE_ID;
		}elseif($this->clsISO->replaceSpace($type_signing) != ""){
			$agree_date = ($time_signing) ? $this->parseDate($time_signing):  0;
			$agree_status_id = $arr_status_contract_by_code_cached[$type_signing]["property_id"];
		}
		
		$data = array( 
			'billing_type' => $billing_type,
			'staff_code' => $staff_code,
			'staff_id' => $staff_id,
			'seller_name' => $seller_name,
			'department_id' => $department_id,
			'regional_id' => $regional_id,
			'head_of_dep_id' => $head_of_dep_id,
			'regional_director_id' => $regional_director_id,
			'gdkd_id' => $gdkd_id,
			'tpkd_id' => $tpkd_id,
			'project_id' => $project_id,
			'stock_code' => $stock_code,
			'stock_id' => $stock_id,
			'block_id' => $block_id,
			'building_id' => $building_id,
			'stock_type' => $stock_type,
			'bedroom_id' => $bedroom_id,
			'type_id' => $type_id,
			'home_direction_id' => $home_direction_id,
			'investor_id' => $investor_id,
			'contract_status_id' => $contract_status_id,
			'agree_status_id' => $agree_status_id,
			'state_id' => $prop['state_id'],
			'stock_resource' => $prop['stock_resource'],
			'billing_source_id' => $prop['billing_source_id'],
			'sold_to_type' => $prop['sold_to_type'],
			'sale_agency_id' => $prop['sale_agency_id'],
			'admin_id' => $admin_id,
			'project_director_id' => $project_director_id,
			'deposit_date' => $deposit_date,
			'agree_date' => $agree_date,
			'contract_date' => $contract_date,
			'totalgrand' => $totalgrand,
			'share_value' => $share_value,
			'commission_value' => $commission_value,
			'share_ratio' => $share_ratio,
			'phan_khu_name' => $phan_khu_name,
			'realized_sales_compete' => $realized_sales_compete,
			'realized_sales_commission' => $realized_sales_commission,
			'commission' => $this->clsISO->convertToNumber($get('commission')),
			'sale_bonus' => $this->clsISO->processSmartNumber($get('sale_bonus')),
			'support_sale' => $this->clsISO->processSmartNumber($get('support_sale')),
			'hot_bonus_customer' => $this->clsISO->processSmartNumber($get('hot_bonus_customer')),
			'bonus_agency' => $this->clsISO->processSmartNumber($get('bonus_agency')),
			'hot_bonus' => $this->clsISO->processSmartNumber($get('hot_bonus')),
			'hot_bonus_agency' => $this->clsISO->processSmartNumber($get('hot_bonus_agency')),
			'sale_dir_rate' => $this->clsISO->convertToNumber($get('sale_dir_commission_rate')),
			'regional_dir_rate' => $this->clsISO->convertToNumber($get('regional_dir_commission_rate')),
			'project_dir_rate' => $this->clsISO->convertToNumber($get('project_dir_commission_rate')),
			'total_deduction' => $this->clsISO->processSmartNumber($get('total_deduction')),
			'total_deduction_percent_sales' => $this->clsISO->convertToNumber($get('total_deduction_percent_sales')),
			'total_deduction_company' => $this->clsISO->processSmartNumber($get('total_deduction_company')),
			'customer_name' => $get('customer_name'),
			'customer_email' => $get('customer_email'),
			'customer_phone' => $get('customer_phone'),
			'notes' => $get('notes')
		);
		$res = $this->rowResult('ok', '', $warnings, $vals);
		$res['data'] = $data;
		$res['preview'] = array(
			'staff' => $staff_id > 0 ? ($staff_code . ' — ' . $seller_name) : ('(ngoài HT) ' . $seller_name),
			'stock_code' => $stock_code,
			'billing_code' => '',
			'deposit_date' => $deposit_date > 0 ? date('d/m/Y', $deposit_date) : '',
			'totalgrand' => $share_value > 0 ? $share_value : $totalgrand
		);
		return $res;
	}
	/** Dựng mảng insert billing từ dữ liệu của sale CHÍNH (primary). */
	private function buildBillingInsert($d, $profile_id){
		global $clsISO;
		$billing_id = $this->clsBilling->getMaxId();
		$billing_code = $this->clsBilling->genCode();
		$billing_search = sprintf('|%s_%s|', $d['project_id'], $d['billing_type']);
		$rec_date = $d['deposit_date'] > 0 ? $d['deposit_date'] : time();
		$edit_due_date = 0;
		if($d['deposit_date'] > 0){
			$next_month_time = strtotime('+1 month', $d['deposit_date']);
			$edit_due_date = strtotime(sprintf('05-%s 23:59:59', date('m-Y', $next_month_time)));
		}
		
		$more_information = array(
			'stock_id' => $d['stock_id'],
			'block_id' => $d['block_id'],
			'building_id' => $d['building_id'],
			'stock_type' => $d['stock_type'],
			'bedroom_id' => $d['bedroom_id'],
			'type_id' => $d['type_id'],
			'edit_due_date' => $edit_due_date,
			'home_direction_id' => $d['home_direction_id'],
			'deposit_date' => $d['deposit_date'],
			'stock_resource' => $d['stock_resource'],
			'sale_agency_id' => $d['sale_agency_id'],
			'billing_source_id' => $d['billing_source_id'],
			'seller_name' => $d['seller_name'],
			'phan_khu_name' => $d['phan_khu_name'],
			'staff_notes' => '',
			'customer_name' => $d['customer_name'],
			'customer_email' => $d['customer_email'],
			'customer_phone' => $d['customer_phone'],
			'commission' => $d['commission'],
			'sale_bonus' => $d['sale_bonus'],
			'support_sale' => $d['support_sale'],
			'hot_bonus_customer' => $d['hot_bonus_customer'],
			'hot_bonus_agency' => $d['hot_bonus_agency'],
			'leader_commission_rate' => 0,
			'sale_dir_commission_rate' => $d['sale_dir_rate'],
			'regional_dir_commission_rate' => $d['regional_dir_rate'],
			'project_dir_commission_rate' => $d['project_dir_rate'],
			'contract_status_id' => $d['contract_status_id'],
			'agree_status_id' =>$d['agree_status_id'],
			'sale_policy_file' => '',
			'capture_confirm_file' => '',
			'table_bonus_file' => '',
			'is_sendemail' => 0,
			'is_sendpale' => 0,
			'dep_logs' => array(
				'head_of_dep_id' => $d['head_of_dep_id'],
				'regional_director_id' => $d['regional_director_id'],
				'gdkd_id' => $d['gdkd_id'],
				'tpkd_id' => $d['tpkd_id'],
				'region_id' => $d['regional_id'],
				'project_director_id' => $d['project_director_id']
			)
		);
		$logs = array();
		$logs[$this->clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'profile_id' => $profile_id,
			'content' => sprintf('Import giao dịch <strong>%s</strong> từ Google Sheet', $billing_code)
		);
		$insert = array(
			$this->clsBilling->pkey => $billing_id,
			'billing_code' => $billing_code,
			'billing_type' => $d['billing_type'],
			'billing_search' => $billing_search,
			'sold_to_type' => $d['sold_to_type'],
			'staff_id' => $d['staff_id'],
			'regional_id' => $d['regional_id'],
			'department_id' => $d['department_id'],
			'deposit_date' => $d['deposit_date'],
			'agree_date' => $d['agree_date'],
			'contract_date' => $d['contract_date'],
			'stock_code' => $d['stock_code'],
			'admin_id' => $d['admin_id'],
			'investor_id' => $d['investor_id'],
			'is_alliance' => 0,
			'is_fullscore' => 0,
			'totalgrand' => $d['totalgrand'],
			'realized_sales_compete' => $d['realized_sales_compete'],
			'realized_sales_commission' => $d['realized_sales_commission'],
			'state_id' => $d['state_id'],
			'project_id' => $d['project_id'],
			'billing_source_id' => $d['billing_source_id'],
			'contract_status_id' => $d['contract_status_id'],
			'agree_status_id' => $d['agree_status_id'],
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'notes' => $d['notes'],
			'reg_date' => $rec_date,
			'upd_date' => $rec_date,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		);
		return array('insert' => $insert, 'billing_id' => $billing_id);
	}
	private function buildBillingUpdate($d, $profile_id,$billing_id){
		global $clsISO;
		$clsProperty = new Property();
		$oneBilling = $this->clsBilling->getOne($billing_id);
		$arr_status_contract_by_code_cached = $clsProperty->getArraySearchByKeyCode("_STATUS_CONTRACT");
		$project_information = $clsISO->to_array_json($oneProject["more_information"]);
		$status_contract_id = $project_more_information["status_contract"] ?? 0;
		if(!empty($d['project_id']) && !empty($d['billing_type'])) {
			$billing_search = sprintf('|%s_%s|', $d['project_id'], $d['billing_type']);	
		}else{
			$billing_search = $oneBilling["billing_search"];
		}
		$more_information = $clsISO->to_array_json($oneBilling["more_information"]);
		$upd_date = $d['deposit_date'] > 0 ? $this->parseDate($d['deposit_date']) : time();
		$edit_due_date = 0;
		if($d['deposit_date'] > 0){
			$next_month_time = strtotime('+1 month', $d['deposit_date']);
			$edit_due_date = strtotime(sprintf('05-%s 23:59:59', date('m-Y', $next_month_time)));
		}
		$dep_logs = $more_information["dep_logs"];
		$contract_status_id = $agree_status_id = 0;
		foreach($d as $key => $val) {
			if($key == "head_of_dep_id" || $key == "regional_director_id" || $key == "gdkd_id" || $key == "tpkd_id" || $key == "regional_id" || $key == "project_director_id" ){
				$dep_logs[$key] = $val;
			}else if($key == "type_signing" && isset($d["time_signing"])){
				if($key == "type_signing") {
					if($this->clsISO->replaceSpace($val) == "hdmb") {
						$contract_date = $this->parseDate($d["time_signing"]);
						$contract_status_id = _CONTRACT_STATUS_DONE_ID;
					}elseif($this->clsISO->replaceSpace($val) != ""){
						$agree_date = $this->parseDate($d["time_signing"]);
						$agree_status_id = $arr_status_contract_by_code_cached[$val]["property_id"];
					}					
				}
			}else{
				$more_information[$key] = !empty($val) ? $val : $more_information[$key];
			}			
		}
		$more_information["edit_due_date"] = $edit_due_date;
		$more_information["dep_logs"] = $dep_logs;
		
		$logs = $more_information["logs"];
		$logs[$this->clsISO->getUniqid()] = array(
			'reg_date' => time(),
			'profile_id' => $profile_id,
			'content' => sprintf('Import giao dịch <strong>%s</strong> từ Google Sheet', $billing_code)
		);
		$update = array(
			$this->clsBilling->pkey => $billing_id,
			'billing_type' => !empty($d['billing_type']) ? $d['billing_type'] : $oneBilling["billing_type"],
			'billing_search' => $billing_search,
			'sold_to_type' => !empty($d['sold_to_type']) ? $d['sold_to_type'] : $oneBilling["sold_to_type"],
			'staff_id' => !empty($d['staff_id']) ? $d['staff_id'] : $oneBilling["staff_id"],
			'regional_id' => !empty($d['regional_id']) ? $d['regional_id'] : $oneBilling["regional_id"],
			'department_id' => !empty($d['department_id']) ? $d['department_id'] : $oneBilling["department_id"],
			'deposit_date' => !empty($d['deposit_date']) ? $d['deposit_date'] : $oneBilling["deposit_date"],
			'agree_date' => !empty($d['agree_date']) ? $d['agree_date'] : $oneBilling["agree_date"],
			'contract_date' => !empty($d['contract_date']) ? $d['contract_date'] : $oneBilling["contract_date"],
			'stock_code' => !empty($d['stock_code']) ? $d['stock_code'] : $oneBilling["stock_code"],
			'admin_id' => !empty($d['admin_id']) ? $d['admin_id'] : $oneBilling["admin_id"],
			'investor_id' => !empty($d['investor_id']) ? $d['investor_id'] : $oneBilling["investor_id"],
			'is_alliance' => 0,
			'is_fullscore' => 0,
			'totalgrand' => !empty($d['totalgrand']) ? $d['totalgrand'] : $oneBilling["totalgrand"],
			'realized_sales_compete' => !empty($d['realized_sales_compete']) ? $d['realized_sales_compete'] : $oneBilling["realized_sales_compete"],
			'realized_sales_commission' => !empty($d['realized_sales_commission']) ? $d['realized_sales_commission'] : $oneBilling["realized_sales_commission"],
			'state_id' => !empty($d['state_id']) ? $d['state_id'] : $oneBilling["state_id"],
			'project_id' => !empty($d['project_id']) ? $d['project_id'] : $oneBilling["project_id"],
			'billing_source_id' => !empty($d['billing_source_id']) ? $d['billing_source_id'] : $oneBilling["billing_source_id"],
			'contract_status_id' => !empty($d['contract_status_id']) ? $d['contract_status_id'] : $oneBilling["contract_status_id"],
			'agree_status_id' => !empty($d['agree_status_id']) ? $d['agree_status_id'] : $oneBilling["agree_status_id"],
			'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'notes' => !empty($d['notes']) ? $d['notes'] : $oneBilling["notes"],
			'upd_date' => $upd_date,
			'user_id_update' => $profile_id
		);
		return array('update' => $update, 'billing_id' => $billing_id);
	}
	/** Đóng gói kết quả 1 dòng (kèm preview thô từ sheet). */
	private function rowResult($status, $reason, $warnings, $vals){
		return array(
			'status' => $status,
			'reason' => $reason,
			'warnings' => $warnings,
			'data' => array(),
			'preview' => array(
				'staff' => isset($vals['staff_code']) ? $vals['staff_code'] : (isset($vals['seller_name']) ? $vals['seller_name'] : ''),
				'stock_code' => isset($vals['stock_code']) ? $vals['stock_code'] : '',
				'billing_code' => '',
				'deposit_date' => isset($vals['deposit_date']) ? $vals['deposit_date'] : '',
				'totalgrand' => isset($vals['share_value']) ? $vals['share_value'] : (isset($vals['totalgrand']) ? $vals['totalgrand'] : '')
			)
		);
	}
	/** Ghi các dòng billing_sale cho 1 billing. Gộp các dòng CÙNG 1 sale (cùng staff_id / cùng tên). */
	private function writeShares($billing_id, $group, $primary_idx){
		$merged = array();
		foreach($group as $i => $g){
			$d = $g['data'];
			$key = $d['staff_id'] > 0 ? ('s' . $d['staff_id']) : ('n' . mb_strtolower(trim($d['seller_name'])));
			// Phần giá trị của sale: ưu tiên cột share_value; trống thì suy từ % (như form thủ công);
			// không có % (sale đơn) mới lấy full giá trị căn.
			if($d['share_value'] > 0){
				$val = $d['share_value'];
			} else if($d['share_ratio'] > 0){
				$val = round($d['share_ratio'] / 100 * $d['totalgrand']);
			} else {
				$val = $d['totalgrand'];
			}
			if(isset($merged[$key])){
				$merged[$key]['share_ratio'] += $d['share_ratio'];
				$merged[$key]['share_value'] += $val;
				if($i === $primary_idx){
					$merged[$key]['is_primary'] = 1;
				}
			} else {
				$merged[$key] = array(
					'staff_id' => $d['staff_id'],
					'seller_name' => $d['seller_name'],
					'department_id' => $d['department_id'],
					'regional_id' => $d['regional_id'],
					'share_ratio' => $d['share_ratio'],
					'share_value' => $val,
					'is_primary' => ($i === $primary_idx) ? 1 : 0
				);
			}
		}
		return $this->clsBillingSale->saveShares($billing_id, array_values($merged));
	}
	/**
	 * Chạy import. $params: spreadsheetId, sheet_name, start_row, billing_type, is_preview.
	 * Gom co-sale theo (dự án + mã căn). is_preview=1: chỉ resolve + thống kê, KHÔNG ghi DB.
	 */
	function run($params){
		global $profile_id,$clsISO;
		set_time_limit(0);
		ini_set('max_execution_time', 300);
		ini_set('memory_limit', '2048M');
		$config = $this->getConfig();
		if(empty($config)){
			return array('result' => false, 'msg' => 'Chưa cấu hình cột. Bấm nút cấu hình (⚙) để map cột trước.');
		}
		$billing_type_default = (int) (isset($params['billing_type']) ? $params['billing_type'] : 0);
		$has_type_column = in_array('billing_type', $config, true);
		/*if($billing_type_default <= 0 && !$has_type_column){
			return array('result' => false, 'msg' => 'Chưa chọn Loại hình giao dịch (hoặc map cột "Loại hình" trong cấu hình ⚙).');
		}*/
		$spreadsheetId = isset($params['spreadsheetId']) ? trim($params['spreadsheetId']) : '';
		$range = isset($params['sheet_name']) ? (string) $params['sheet_name'] : '';
		if($spreadsheetId === '' || $range === ''){
			return array('result' => false, 'msg' => 'Thiếu Spreadsheet ID hoặc tên sheet.');
		}
		$start_row = max(0, (int) (isset($params['start_row']) ? $params['start_row'] : 1));
		$is_preview = !empty($params['is_preview']);
		try {
			$data = $this->readSheet($spreadsheetId, $range);
		} catch (Exception $e){
			return array('result' => false, 'msg' => 'Không đọc được sheet (kiểm tra ID + quyền chia sẻ tới service account).');
		}
		$total = count($data);
		$report = array(
			'result' => true,
			'is_preview' => $is_preview,
			'total' => 0,
			'inserted' => 0,   // số billing (căn)
			'updated' => 0,   // số billing cập nhật (căn)
			'shares' => 0,     // số dòng sale tham gia
			'skipped' => 0,
			'errors' => 0,
			'rows' => array()
		);
		$out = array();        // line => report row
		$groups = array();     // "project|stock" => list rows [line, data, warnings, preview]
		// PASS 1: resolve từng dòng, gom theo (dự án + mã căn)
		for($i = $start_row; $i < $total; $i++){
			$row = $data[$i];
			$mapped = array();
			foreach($config as $colIdx => $fieldKey){
				$mapped[$fieldKey] = isset($row[$colIdx]) ? trim($row[$colIdx]) : '';
			}
			if($this->isEmptyRow($mapped)){
				continue;
			}
			$report['total']++;
			$line = $i + 1;
			$res = $this->resolveRowData($mapped, $billing_type_default);
			if($res['status'] === 'error'){
				$report['errors']++;
				$out[$line] = array('line' => $line, 'status' => 'error', 'reason' => $res['reason'], 'warnings' => $res['warnings'], 'preview' => $res['preview']);
			} else {
				$key = $res['data']['project_id'] . '|' . mb_strtolower($res['data']['stock_code']);
				$groups[$key][] = array('line' => $line, 'data' => $res['data'], 'warnings' => $res['warnings'], 'preview' => $res['preview']);
			}
		}
		// PASS 2: mỗi nhóm (căn) → 1 billing + N share
		foreach($groups as $key => $group){
			$d0 = $group[0]['data'];			
			// sale chính = tỷ lệ chia cao nhất (fallback dòng đầu)
			$primary_idx = 0; $max_ratio = -1;
			foreach($group as $i2 => $g){
				if($g['data']['share_ratio'] > $max_ratio){
					$max_ratio = $g['data']['share_ratio'];
					$primary_idx = $i2;
				}
			}
			$is_co = count($group) > 1;
			if($is_co){
				$ratio_sum = 0;
				foreach($group as $g){
					$ratio_sum += $g['data']['share_ratio'];
					$group[$primary_idx]["data"] = $g['data'];
				}
				if($ratio_sum > 0 && abs($ratio_sum - 100) > 1){
					$group[$primary_idx]['warnings'][] = 'Tổng tỷ lệ chia = ' . round($ratio_sum) . '% (≠100%) — soi lại';
				}
			}
			// chống trùng theo CĂN (dự án + mã căn), chưa hủy
			$dup = $this->clsBilling->getByCond("`is_cancel`=0 AND `project_id`='" . $d0['project_id'] . "' AND `stock_code`=" . $this->q($d0['stock_code']));
			if($dup){
				$report['skipped'] += count($group);
				foreach($group as $g){
					$out[$g['line']] = array('line' => $g['line'], 'status' => 'skip', 'reason' => 'Căn đã có giao dịch', 'warnings' => $g['warnings'], 'preview' => $g['preview']);
				}
				continue;
				/*if($is_preview){
					continue;
				} else {
					$built = $this->buildBillingUpdate($group[$primary_idx]['data'], $profile_id, $dup[$this->clsBilling->pkey]);
					if($this->clsBilling->updateOne($dup[$this->clsBilling->pkey],$built['update'])){
						$report['updated']++;
					}
				}*/
				
				
			}
			if($is_preview){
				$report['inserted']++;
				$report['shares'] += count($group);
				foreach($group as $i2 => $g){
					$note = $is_co ? (($i2 === $primary_idx) ? 'co-sale: chính' : 'co-sale: đồng bán') : '';
					$out[$g['line']] = array('line' => $g['line'], 'status' => 'ok', 'reason' => $note, 'warnings' => $g['warnings'],'data' => $g['data'], 'preview' => $g['preview']);
				}
			} else {
//				$clsISO->print_pre($group[$primary_idx]['data']);die;
				$built = $this->buildBillingInsert($group[$primary_idx]['data'], $profile_id);
//				$clsISO->print_pre($built);die;
				if($this->clsBilling->insert($built['insert'])){
					$report['inserted']++;
					$report['shares'] += $this->writeShares($built['billing_id'], $group, $primary_idx);
					$this->syncLoyalty($built['billing_id']);
					foreach($group as $i2 => $g){
						$note = $is_co ? (($i2 === $primary_idx) ? 'co-sale: chính' : 'co-sale: đồng bán') : '';
						$out[$g['line']] = array('line' => $g['line'], 'status' => 'ok', 'reason' => $note, 'warnings' => $g['warnings'], 'preview' => $g['preview']);
					}
				} else {
					$report['errors'] += count($group);
					foreach($group as $g){
						$out[$g['line']] = array('line' => $g['line'], 'status' => 'error', 'reason' => 'Ghi DB thất bại', 'warnings' => $g['warnings'], 'preview' => $g['preview']);
					}
				}
			}
		}
		ksort($out);
		$report['rows'] = array_slice(array_values($out), 0, 1000);
		return $report;
	}
	/** Đồng bộ điểm loyalty — TẠM TẮT (tính điểm sau). */
	private function syncLoyalty($billing_id){
		// v1: chưa tính điểm loyalty co-sale. Sẽ bổ sung sau theo tỷ lệ chia trong default_billing_sale.
	}
}
?>
