<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
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
class Customer extends dbBasic{
	function __construct(){
		global $core, $clsISO, $profile_id;
		$this->pkey = "customer_id";
		$this->tbl = DB_PREFIX."customer";
	}
	function normalizeIdArray($ids){
		$arr = array();
		if(is_array($ids)){
			foreach($ids as $val){
				$val = (int) $val;
				if($val > 0){
					$arr[] = $val;
				}
			}
		} else if(is_string($ids) && !empty($ids)){
			if(preg_match_all('/\d+/', $ids, $matches)){
				foreach($matches[0] as $val){
					$val = (int) $val;
					if($val > 0){
						$arr[] = $val;
					}
				}
			}
		} else if(is_numeric($ids)){
			$val = (int) $ids;
			if($val > 0){
				$arr[] = $val;
			}
		}
		$arr = array_values(array_unique($arr));
		sort($arr);
		return $arr;
	}
	function makeSlashIdList($ids){
		$arr = $this->normalizeIdArray($ids);
		return !empty($arr) ? sprintf('|%s|', implode('|', $arr)) : "";
	}
	function relationTableExists($table){
		return 1;
	}
	function getShareIds($customer_id, $oDataTable = array()){
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		$clsCustomerMeta = new CustomerMeta();
		return $clsCustomerMeta->getIdsByCustomerType($customer_id, 'share');
	}
	// D3: chokepoint đổi trạng thái — guard KHÔNG clobber status_id về 0/không hợp lệ.
	// Mọi nơi đổi status nên đi qua đây thay vì updateOne thẳng.
	function changeStatus($customer_id, $new_status_id, $opts = array()){
		$customer_id = (int) $customer_id;
		$new_status_id = (int) $new_status_id;
		if($customer_id <= 0 || $new_status_id <= 0){
			return false;
		}
		$data = array();
		if(!empty($opts['extra']) && is_array($opts['extra'])){
			$data = $opts['extra']; // cột kèm (vd more_information) — ghi atomic cùng status_id
			unset($data['status_id'], $data['upd_date']); // status_id/upd_date do chokepoint quyết, không cho extra ghi đè
		}
		$data['status_id'] = $new_status_id;
		$data['upd_date'] = isset($opts['upd_date']) ? (int) $opts['upd_date'] : time();
		return $this->updateOne($customer_id, $data);
	}
	// A3: bọc khóa read-modify-write more_information (chống lost-update khi 2 request ghi đồng thời).
	// $mutator nhận mảng more_information hiện tại, trả về mảng đã sửa. $extra = cột khác cần set kèm.
	function saveMoreInfo($customer_id, $mutator, $extra = array()){
		global $dbconn, $clsISO;
		$customer_id = (int) $customer_id;
		if($customer_id <= 0 || !is_callable($mutator)){
			return false;
		}
		$lock = addslashes('cmi_'.$this->tbl.'_'.$customer_id);
		$dbconn->GetOne("SELECT GET_LOCK('{$lock}', 5)");
		$one = $this->getOne($customer_id, "more_information");
		$mi = $clsISO->to_array_json($one['more_information']);
		if(!is_array($mi)){
			$mi = array();
		}
		$mi = call_user_func($mutator, $mi);
		$data = array('more_information' => json_encode($mi, JSON_UNESCAPED_UNICODE));
		if(!empty($extra) && is_array($extra)){
			$data = array_merge($data, $extra);
		}
		$ok = $this->updateOne($customer_id, $data);
		$dbconn->GetOne("SELECT RELEASE_LOCK('{$lock}')");
		return $ok;
	}
	function getCampaignIds($customer_id, $oDataTable = array()){
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		$clsCustomerMeta = new CustomerMeta();
		return $clsCustomerMeta->getIdsByCustomerType($customer_id, 'campaign');
	}
	function syncShareIds($customer_id, $ids, $user_id=0, $syncLegacy=false){
		$customer_id = (int) $customer_id;
		$user_id = (int) $user_id;
		if($customer_id <= 0){
			return "";
		}
		$arr = $this->normalizeIdArray($ids);
		$clsCustomerMeta = new CustomerMeta();
		$clsCustomerMeta->syncByCustomerType($customer_id, 'share', $arr, $user_id);
		return $this->makeSlashIdList($arr);
	}
	function syncCampaignIds($customer_id, $ids, $user_id=0, $syncLegacy=false){
		$customer_id = (int) $customer_id;
		$user_id = (int) $user_id;
		if($customer_id <= 0){
			return "";
		}
		$arr = $this->normalizeIdArray($ids);
		$clsCustomerMeta = new CustomerMeta();
		$clsCustomerMeta->syncByCustomerType($customer_id, 'campaign', $arr, $user_id);
		return $this->makeSlashIdList($arr);
	}
	function sqlCondHasShare($admin_id, $alias=''){
		$admin_id = (int) $admin_id;
		$prefix = !empty($alias) ? "`{$alias}`." : "";
		$clsCustomerMeta = new CustomerMeta();
		return "({$prefix}`{$this->pkey}` IN (SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` WHERE `meta_type`='share' AND `meta_id`='{$admin_id}'))";
	}
	function sqlCondHasCampaign($campaign_id, $alias=''){
		$campaign_id = (int) $campaign_id;
		$prefix = !empty($alias) ? "`{$alias}`." : "";
		$clsCustomerMeta = new CustomerMeta();
		return "({$prefix}`{$this->pkey}` IN (SELECT `customer_id` FROM `{$clsCustomerMeta->tbl}` WHERE `meta_type`='campaign' AND `meta_id`='{$campaign_id}'))";
	}
	function mask($str, $mask=false){
		if($str==''){ return '';}
		if($mask==false) return $str;
		$len = strlen($str);
		return sprintf('**%s', substr($str, ($len-4),4));
	}
	// HTML số ĐT che; nếu $isOwner (chủ khách) thì kèm nút 👁 bấm hiện full số (full chỉ nhúng data-full khi là chủ). Cần JS $Core.crm.reveal_phone + CSS .crm-phone-wrap.
	function getPhoneReveal($phone, $isOwner = false){
		if(empty($phone)){ return '<span>—</span>'; }
		$masked = $this->mask($phone, true);
		if($isOwner){
			return '<span class="crm-phone-wrap js__reveal-phone cursor-pointer" data-full="'.$phone.'" onClick="$Core.crm.reveal_phone(this, event)" title="Xem số đầy đủ"><span class="js__ph-text text-nowrap">'.$masked.'</span> <i class="bx bx-show js__ph-eye fs-13"></i></span>';
		}
		return '<span class="crm-phone-wrap"><span class="js__ph-text text-nowrap">'.$masked.'</span></span>';
	}
	function getName($customer_id, $oDataTable = array()){
		if(!isset($oDataTable['name']))
			$oDataTable = $this->getOne($customer_id, "name");
		return $oDataTable['name'];
	}
	function getPhone($customer_id, $oDataTable = array()){
		if(!isset($oDataTable['phone']))
			$oDataTable = $this->getOne($customer_id, "phone");
		if(!empty($oDataTable['phone']))
			return $this->mask($oDataTable['phone'], true);
		return '<span class="text-muted">Chưa có</span>';
	}
	// SĐT 1 khách kèm nút 👁 xem full: tự tính quyền (chủ khách hoặc full-permiss, và đã xác nhận nhận khách) rồi render reveal.
	function getPhoneRevealByCustomer($customer_id, $oDataTable = array()){
		global $profile_id;
		if(!isset($oDataTable['phone']) || !isset($oDataTable['admin_id']))
			$oDataTable = $this->getOne($customer_id, "phone,admin_id");
		$phone = isset($oDataTable['phone']) ? $oDataTable['phone'] : '';
		if(empty($phone)){ return '<span class="text-muted">Chưa có</span>'; }
		$isOwner = ((int) (isset($oDataTable['admin_id']) ? $oDataTable['admin_id'] : 0) === (int) $profile_id || $this->isFullPermiss());
		if($isOwner && $this->isReceivePending($customer_id)){ $isOwner = false; } // chưa xác nhận nhận khách → che, không cho bấm xem
		return $this->getPhoneReveal($phone, $isOwner);
	}
	function formatPhone($phone){
		global $core, $dbconn, $clsISO;
		if(!empty($phone)){
			$phone = preg_replace('/\D/', '', $phone);
			$phone = preg_replace('/[^0-9]/', '', $phone);
			$phone = preg_replace('/^\+?(840|84|83)/','0',$phone);
			if(@substr($phone, 0, 1) != '0'){
				$phone = sprintf('0%s', $phone);
			}
		}
		return $phone;
	}
	function dobToTimestamp(string $date) {
		if (!preg_match('/^(\d{2})([.\-\/])(\d{2})\2(\d{4})$/', $date, $m)) {
			return 0;
		}
		$format = 'd' . $m[2] . 'm' . $m[2] . 'Y';
		$dt = DateTime::createFromFormat($format, $date);
		return $dt ? $dt->setTime(0,0,0)->getTimestamp() : null;
	}
	function isFullPermiss(){
		global $core, $dbconn, $profile_id;
		if(in_array($profile_id, _PROFILE_CRM_SUPER_ID))
			return 1;
		return 0;
	}
	function isRootProfile(){
		global $dbconn, $core, $profile_id, $clsISO;
		if($profile_id == _PROFILE_ROOT_ID || $clsISO->checkPermission('profile_root_customer'))
			return 1;
		return 0;
	}
	// Quản lý theo PHÒNG BAN: true nếu là GĐ Kinh doanh (SALE_DIRECTOR_ONLY) hoặc GĐ Vùng (REGIONAL_DIRECTOR).
	// Bỏ cơ chế nhóm tự tạo GroupProfile; dùng thẳng helper quyền sẵn có trong ISO → đồng bộ y hệt gate dashboard.
	function isTeamManager(){
		global $clsISO, $profile_id;
		return ($clsISO->checkPermissionGroup('SALE_DIRECTOR_ONLY') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')) ? 1 : 0;
	}
	// Nhãn tab "Nhóm" = tên Phòng/Vùng theo phòng ban của người đăng nhập (GĐV → tên Vùng, GĐKD → tên Phòng KD).
	function getTeamLabel(){
		global $dbconn, $profile_id, $oneProfile;
		$deptId = (int) $oneProfile['department_id'];
		if($deptId > 0 && !$this->isFullPermiss()){
			$title = $dbconn->GetOne("SELECT `title` FROM `" . DB_PREFIX . "property` WHERE `property_id`='{$deptId}' AND `property_type`='_DEPARTMENT'");
			if(!empty($title)){
				return $title;
			}
		}
		return 'Phòng ban';
	}
	// Nhân sự Marketing (định danh SERVER-SIDE từ $oneProfile — client không sửa được, KHÔNG phải cờ tự-khai):
	//  (1) phòng MKT (_DEPARTMENT_MKT_ID — chỉ 1 phòng → bắt mọi cấp), HOẶC
	//  (2) role nằm trong cây Marketing: PMKT → GĐMKT/TPMKT/CVMKT đều chung GỐC với _ROLE_GD_MARKETER (so getRootId).
	function isMarketing(){
		global $oneProfile;
		if(!is_array($oneProfile)){ return 0; }
		if((int) (isset($oneProfile['department_id']) ? $oneProfile['department_id'] : 0) === _DEPARTMENT_MKT_ID){ return 1; }
		$role = (int) (isset($oneProfile['role_id']) ? $oneProfile['role_id'] : 0);
		if($role > 0){
			static $mkt_root = null;
			$clsProperty = new Property();
			if($mkt_root === null){ $mkt_root = (int) $clsProperty->getRootId(_ROLE_GD_MARKETER); }
			if($mkt_root > 0 && (int) $clsProperty->getRootId($role) === $mkt_root){ return 1; }
		}
		return 0;
	}
	// Auto-thêm người liên quan _PROFILE_PTH_ID khi TẠO khách: chỉ khi nguồn=Quảng Cáo (_CRM_RESOURCE_ADS_ID)
	// và người tạo (đang đăng nhập) thuộc Marketing (isMarketing). Idempotent: bỏ qua nếu đã là người liên quan.
	function ensureMarketingAdsShare($customer_id, $resource_id){
		global $profile_id;
		$clsCustomerMeta = new CustomerMeta();
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){ return 0; }
		if(!defined('_PROFILE_PTH_ID') || !defined('_CRM_RESOURCE_ADS_ID')){ 
			return 0; 
		} else {
			if((int) $resource_id !== (int) _CRM_RESOURCE_ADS_ID){ 
				return 0;
			}
			$pth = (int) _PROFILE_PTH_ID;
			if($pth <= 0){ return 0; }
			if(!$this->isMarketing()){ 
				return 0; 
			}
			$shares = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'share');
			if(in_array($pth, $shares)){ return 0; }
			$clsCustomerMeta->insert(array(
				'customer_id' => $customer_id,
				'meta_type' => 'share',
				'meta_id' => $pth,
				'reg_date' => time(),
				'user_id' => (int) $profile_id
			));
			$this->updateOne($customer_id, array('use_globe' => 1));
			return 1;
		}
		
	}
	// Người NHẬN chưa xác nhận khách này? true ⇒ chặn mọi thao tác ghi (chỉ được xem) cho tới khi bấm "Xác nhận đã nhận".
	// Áp dụng cho MỌI người nhận, KỂ CẢ full-permiss: phiếu giao gắn theo từng người nhận, không liên quan quyền admin.
	// $recipient_id rỗng = người đang đăng nhập ($profile_id).
	function isReceivePending($customer_id, $recipient_id = 0){
		global $profile_id;
		$customer_id = (int) $customer_id;
		$recipient_id = (int) $recipient_id;
		if($recipient_id <= 0){
			$recipient_id = (int) $profile_id;
		}
		if($customer_id <= 0 || $recipient_id <= 0){
			return 0;
		}
		$clsCustomerAssign = new CustomerAssign();
		return $clsCustomerAssign->isPending($customer_id, $recipient_id);
	}
	// Chặn xem chi tiết/SĐT/hoạt động khi người nhận CHƯA xác nhận: xuất popup nhắc bấm "Nhận" rồi dừng. Trả false nếu không chặn.
	// $withUid=true cho handler mở popup (cần uid); =false cho handler nạp vào holder (chỉ cần html).
	// $mode khớp hàm popup gọi nó: 'openfull' = $Core.popup.openfull (cần modal đầy đủ kèm id),
	// 'open' = $Core.popup.open (tự bọc <div id class="modal">, chỉ cần phần modal-dialog), 'plain' = nạp thẳng holder.
	function blockIfReceivePending($customer_id, $mode = 'openfull'){
		global $clsISO;
		if(!$this->isReceivePending($customer_id)){
			return false;
		}
		$body = '<div class="modal-body p-0">
			<div class="crm-recv-lock">
				<div class="crm-recv-lock-ic"><i class="bx bx-lock-alt"></i></div>
				<div class="crm-recv-lock-t">Khách vừa được giao cho bạn</div>
				<div class="crm-recv-lock-d">Hãy bấm <span class="crm-recv-lock-pill"><i class="bx bx-check-shield"></i>Nhận</span> ở danh sách để xác nhận đã nhận.<br>Sau khi xác nhận mới xem được <b>chi tiết</b>, <b>số điện thoại</b> &amp; <b>hoạt động</b>.</div>
				<button type="button" class="btn btn-primary w-100 crm-recv-lock-btn" data-bs-dismiss="modal">Đã hiểu</button>
			</div>
		</div>';
		if($mode === 'plain'){
			echo json_encode(array('html' => $body), JSON_UNESCAPED_UNICODE);
			die();
		}
		$uid = $clsISO->getUniqid();
		$dialog = '<div class="modal-dialog modal-dialog-centered"><div class="modal-content">' . $body . '</div></div>';
		$html = ($mode === 'open') ? $dialog : '<div class="modal fade" id="' . $uid . '" tabindex="-1" aria-hidden="true">' . $dialog . '</div>';
		echo json_encode(array('uid' => $uid, 'html' => $html), JSON_UNESCAPED_UNICODE);
		die();
	}
	function getHTMLTags($customer_id, $oDataTable = array(), $openFrom="_list"){
		global $core, $dbconn, $mod, $act, $clsISO, $deviceType;
		$clsTag = new Tag();
		$clsCustomerMeta = new CustomerMeta();
		$html_tags = array();
		$list_tags_arrs = $clsCustomerMeta->getIdsByCustomerType($customer_id, 'tag');
		if(!empty($list_tags_arrs)){
			foreach($list_tags_arrs as $tag_id){
				if($tag_id > 0){
					$html_tags[] = sprintf('<span class="label label-default">%s</span>', $clsTag->getTitle($tag_id));
				}
			}
		}
		$html= '<div class="d-flex flex-wrap tags-list-'.$customer_id.' gap-1 mt-1">'.($openFrom=='_list' ? '<a href="javascript:void(0);" class="label label-default" '.($deviceType=='phone' ? 'onClick="$Core.crm.open_tags(this, event)"' : 'data-toggle="webui-popover" data-trigger="click" data-type="async" data-closeable="false" data-width="300px" data-placement="auto" data-url="'.PCMS_URL.'/index.php?mod='.$mod.'&act=load_pop_tag&customer_id='.$customer_id.'"').' customer_id="'.$customer_id.'" title="Tags">+ Thêm</a>' : '');
		$html.= !empty($html_tags) ? implode("", $html_tags) : "<small class=\"text-muted\">Chưa có tags</small>";
		$html.= '</div>';
		return $html;
	}
	function truncate($str, $mask=false){
		if($str==''){ return '';}
		if($mask==false) return $str;
		$len = strlen($str);
		if($len >= 9){
			$start = $len-3;
			return '***'.substr($str, $start, $len);
		}
		return $str;
	}
	function getPurpose($purposes = ""){
		global $dbconn;
		$clsISO = new ISO();
		$clsProperty = new Property();
		
		$list_purpose_id = array();
		$tmp = @explode('|', $purposes);
		if(!empty($tmp)){
			foreach($tmp as $val){
				$m = $clsProperty->getbyCond("`property_type`='PURPOSE' 
					and `slug`='".$clsISO->replaceSpace($val)."'", $clsProperty->pkey);
				if(!empty($m)) $list_purpose_id[] = $m[$clsProperty->pkey];
			}
		}
		return !empty($list_purpose_id) 
			? sprintf('|%s|', implode('|', $list_purpose_id)) : "";
	}
	function getSpreadsheetDataConfig($spreadsheetId, $ranges,$client){
		global $core, $dbconn, $clsISO;
		$service = new Google_Service_Sheets($client);
		try {
			$arr_ranges = [];
			foreach ($ranges as $range) {
				$arr_ranges[] = $range . '!A:ZZ';
			}	
			$response = $service->spreadsheets_values->batchGet($spreadsheetId, ['ranges' => $arr_ranges]);
			return array( "response"	=>	$response->getValueRanges());
		} catch (Exception $e) {
			$msg_error = $e->getMessage();	
			return 0;			
		}
		return 0;
	}
	function getDataConfigColumn($spreadsheetId,$ranges,$client) {
		global $core, $dbconn, $clsISO;
		$sheets = [];
		// Init service
		$service = new Google_Service_Drive($client);
		// Get Data Sheet
		$dataResponse = $this->getSpreadsheetDataConfig($spreadsheetId, $ranges, $client);
		$response = !empty($dataResponse["response"]) ? $dataResponse["response"] : 0;	
		if(!empty($response)) {
			foreach ($response as $valueRange) {
				// Lấy tên sheet từ chuỗi range (ví dụ: "Sheet1!A:Z")
				$range = $valueRange->getRange();
				$parts = explode('!', $range);
				$sheet_name = $parts[0];
				// Lấy dữ liệu của sheet đó
				$sheets[$sheet_name] = $valueRange->getValues();
			}
		}		
		return $sheets;
	}
	function addFollowups($followup_id,$data_follow){
		$clsFollowUp = new FollowUp();
		$success = 0;
		if(!empty($data_follow["customer_id"])) {
			$oneFollowup = $clsFollowUp->getOne($followup_id,$clsFollowUp->pkey);
			if(!empty($oneFollowup)) {
				return $followup_id;
			}else{
				$followup_id = $clsFollowUp->getMaxId();
				if($clsFollowUp->insert(array(
					$clsFollowUp->pkey => $followup_id,
					'type_id' => !empty($data_follow["type_id"]) ? $data_follow["type_id"] : 0,
					'status_id' => !empty($data_follow["status_id"]) ? $data_follow["status_id"] : _CRM_STATUS_LEAD_ID,
					'customer_id' => !empty($data_follow["customer_id"]) ? $data_follow["customer_id"] : 0,
					'intro' => !empty($data_follow["intro"]) ? $data_follow["intro"] : "",
					'_result' => !empty($data_follow["_result"]) ? $data_follow["_result"] : "",
					'date_id' => !empty($data_follow["date_id"]) ? $data_follow["date_id"] : time(),
					'is_reminder' => !empty($data_follow["is_reminder"]) ? $data_follow["is_reminder"] : 0,
					'reminder_before' => !empty($data_follow["reminder_before"]) ? $data_follow["reminder_before"] : 0,
					'reminder_time' => !empty($data_follow["reminder_before"]) ? $data_follow["reminder_before"] : 0,
					'admin_id' => !empty($data_follow["admin_id"]) ? $data_follow["admin_id"] : 0,
					'user_id' => !empty($data_follow["user_id"]) ? $data_follow["user_id"] : 0,
					'user_id_update' => !empty($data_follow["user_id_update"]) ? $data_follow["user_id_update"] : 0,
					'reg_date' => time(),
					'upd_date' => time()
				))) {
					$success = 1;
				}
			}
		}
		if(!empty($success)) {
			return $followup_id;
		}
		return 0;
	}
	function getDataColumnCustomer(){
		$data_select = [
			"name"			=>	"Họ và tên",
			"phone"			=>	"Điện thoại",
			"email"			=>	"Email",
			"address"		=>	"Địa chỉ",
			"gender"		=>	"Giới tính",
			"birthday"		=> 	"Ngày sinh",
			"status_id"		=>	"Tình trạng",
			"begin_need" 	=>	"Nhu cầu",
			'agent_id'		=>	"Đại lý",	
			"facebook_link" =>	"Link FB",
			"tiktok_link" 	=>	"Link Tiktok",
		];
		return $data_select;
	}
	function getMenuStask($customer_id, $holderG = null){
		$html = "";
		$list_stasks = array(
			'today' => 'Kế hoạch hôm nay',
			'tomorrow' => 'Kế hoạch ngày mai',
			'next_10_days' => 'Kế hoạch 10 ngày tiếp',
		);
		foreach($list_stasks as $key => $val){
			$class = "text-primary";
			$action_name = "Thêm";
			if($holderG == $key){
				$class = "text-danger";
				$action_name = "Huỷ";
			}
			$html.= '<a class="dropdown-item cursor-pointer '.$class.'" onclick="$Core.crm.do_stask(this,event)" 
				customer_id="'.$customer_id.'" holderG="'.$key.'"><i class="bx bx-plus-circle"></i> '.$action_name.' '.strtolower($val).'</a>';
		}
		return $html;
	}
}
