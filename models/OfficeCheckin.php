<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/* Bản ghi điểm danh hằng ngày (Check-in ảnh + GPS). Bảng default_office_checkin.
   1 dòng = đã điểm danh hợp lệ (trong vùng 1 VP + trong khung giờ global). */
class OfficeCheckin extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."office_checkin";
	}
	/* Cấu hình check-in toàn hệ thống (khung giờ...) — lưu ở Configuration key 'checkin_configs'.
	   Fallback về hằng số _CHECKIN_* nếu chưa cấu hình. Dùng chung admin office + front chat. */
	function getConfig(){
		$clsConfiguration = new Configuration();
		$cfg = json_decode($clsConfiguration->getValue('checkin_configs', ''), true);
		if(!is_array($cfg)){ $cfg = array(); }
		$d_start = defined('_CHECKIN_WINDOW_START') ? _CHECKIN_WINDOW_START : '09:00';
		$d_end = defined('_CHECKIN_WINDOW_END') ? _CHECKIN_WINDOW_END : '10:00';
		$d_acc = defined('_CHECKIN_MAX_ACCURACY') ? _CHECKIN_MAX_ACCURACY : 100;
		$d_on = defined('_CHECKIN_ON_TIME') ? _CHECKIN_ON_TIME : '08:30';			// mốc đúng giờ khối khác
		$d_on_sale = defined('_CHECKIN_ON_TIME_SALE') ? _CHECKIN_ON_TIME_SALE : '09:00';	// mốc đúng giờ khối kinh doanh
		$d_checkout = defined('_CHECKIN_CHECKOUT_TIME') ? _CHECKIN_CHECKOUT_TIME : '17:30';	// mốc "về sớm": check-out trước giờ này = về sớm
		return array(
			'window_start' => !empty($cfg['window_start']) ? $cfg['window_start'] : $d_start,
			'window_end'   => !empty($cfg['window_end']) ? $cfg['window_end'] : $d_end,
			'max_accuracy' => !empty($cfg['max_accuracy']) ? (int) $cfg['max_accuracy'] : $d_acc,
			'on_time'      => !empty($cfg['on_time']) ? $cfg['on_time'] : $d_on,
			'on_time_sale' => !empty($cfg['on_time_sale']) ? $cfg['on_time_sale'] : $d_on_sale,
			'checkout_time'=> !empty($cfg['checkout_time']) ? $cfg['checkout_time'] : $d_checkout,
		);
	}
	/* Lưu cấu hình check-in (merge — giữ khoá chưa truyền). */
	function saveConfig($data){
		$clsConfiguration = new Configuration();
		$cfg = json_decode($clsConfiguration->getValue('checkin_configs', ''), true);
		if(!is_array($cfg)){ $cfg = array(); }
		foreach($data as $k => $v){ $cfg[$k] = $v; }
		return $clsConfiguration->updateValue('checkin_configs', json_encode($cfg, JSON_UNESCAPED_UNICODE));
	}
	function chat_checkin_address($moreInfo){
		global $clsISO;
		if(empty($moreInfo)){ return ''; }
		$j = $clsISO->to_array_json($moreInfo);
		$address = (is_array($j) && isset($j['address'])) ? (string) $j['address'] : '';
		$address = $this->replaceAddress($address);
		return $address;
	}
	function replaceAddress($address) {
		$pattern_start = '/^[A-Z0-9]{4,8}\+[A-Z0-9]{2,4}[,\p{Z}]*/u';
		$address = preg_replace($pattern_start, '', $address);
		// 2. Xóa chữ ", Việt Nam" hoặc "Việt Nam" ở cuối chuỗi nếu có
		$address = preg_replace('/[,\p{Z}]+Việt\s+Nam$/ui', '', $address);
		// 3. Xóa sạch mã bưu chính (dãy số từ 5-6 chữ số) ở cuối chuỗi
		// Cho dù trước nó là dấu phẩy, khoảng trắng thường hay khoảng trắng lạ
		$address = preg_replace('/[,\p{Z}]+\d{5,6}$/u', '', $address);
		// 4. Cắt tỉa lại khoảng trắng thừa ở 2 đầu
		return trim(preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $address));
	}
	function sendMsg($arr_msg) {
		global $clsISO,$core,$profile_id,$oneProfile;
		$clsZalo = new Zalo();
		$message = "";
		if(!empty($arr_msg) && $arr_msg["photo"]) {
			$io = ($arr_msg["io"] === 'out') ? '[Check-out] ' : '[Check-in] ';
			$message.= $io.mb_strtoupper($oneProfile["full_name"]);
			if(!empty($arr_msg["tags"])) {
				foreach($arr_msg["tags"] as $k => $tag_name) {	
					$message.= "\n";			
					$message.= $tag_name;
				}
			}
			$message.= "\n";
			$message.= "👤 ".$oneProfile["department_name"];
			$message.= "\n";
			$message.= "📍 ". $arr_msg["location"];
			$message.= "\n";
			$message.= "🕐 ". date("H:i | d/m");
			
			if(!empty($arr_msg["note"])) {
				$message.= "\n";
				$message.= "📒 Ghi chú: ".$arr_msg["note"];
			}
			if($clsISO->_DEV()){
				$group_zalo_id = "8388131316320784986";
			}else{
				$group_zalo_id = _NDLD_ZALO_ID;
			}
			
			$this->sendZaloImage(FH_URL.$arr_msg["photo"],$message,$group_zalo_id);
		}		
	}
	function sendZaloImage($image,$message,$group_zalo_id){
		global $clsISO;
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTQ5MjY4NTAxOTEwMmQ0MjhmOWY5ZiIsImlhdCI6MTc1NDU2NzI3MiwiZXhwIjoxNzg2MTAzMjcyfQ.fmEaolefHG9tAJf9oHD0m7Yn-bMjxZhphx7pf7pG_hg'
		));
		$curl->post('https://public-api.bizflow.vn/functions/689492685019102d428f9f9f', array(
			'url'		 	=> $image,
			'desc' 			=> $message,
			'group_id' 		=> $group_zalo_id,
			'groupLayoutId' => 0,
		));
		if(!$curl->error){
			$response = toArray($curl->response);
			if(isset($response['status']) && $response['status'] == 200){				
				@unlink(ROOTPATH . $path);
				return 1;
			}
		}
		// Return
		return 0;
	}
}
