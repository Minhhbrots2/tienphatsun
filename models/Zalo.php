<?php 

class Zalo {

	function __construct(){}

	function formatPhone($phone){

		// Bỏ hết ký tự không phải số

		$phone = preg_replace('/[^0-9]/', '', $phone);

		// Nếu bắt đầu bằng 84 thì đổi thành 0

		if (strpos($phone, '84') === 0) {

			$phone = '0' . substr($phone, 2);

		}

		// Giữ lại tối đa 10 số

		$phone = substr($phone, 0, 10);

		// Validate: chỉ nhận số di động VN (03|05|07|08|09)

		if (preg_match('/^(03|05|07|08|09)[0-9]{8}$/', $phone)) {

			return $phone;

		}

		// Nếu không hợp lệ thì trả về rỗng

		return '';

	}

	function get_config_field($func, $field){

		global $core, $dbconn, $oneProfile;

		$more_information = $oneProfile['more_information'];

		$zalo_send_configs = $core->get_field($more_information, "zalo_send_configs", []);

		$one_func = $core->get_field($zalo_send_configs, $func, []);

		return $core->get_field($one_func, $field, "");

	}

	function getZaloId($user_id="", $phone_number=""){

		global $core, $dbconn, $clsISO;

		$zaloId = "";

		try {

			$curl = new \Curl\Curl();

			$curl->setHeaders(array(

				'Content-Type' => 'application/json',

				'Authorization' => sprintf('Bearer %s', ZALO_GET_USER_INFO_API_KEY)

			));

			$curl->post('https://public-api.bizflow.vn/functions/680e1a8aea9fef26ac1c1019', array(

				'user_id' => $user_id,

				'phone_number' => $phone_number

			));

			if(!$curl->error){

				$response = toArray($curl->response);

				if(isset($response['status']) && $response['status'] == 200){

					$idList = array_keys($response['data']['data']);

					$zaloId = $idList[0];

				}

			}

		} catch(Exception $e){

			throw new \Exception("Caught exception: {$e->getMessage}");

		}

		return $zaloId;

	}

	function sendMsg($user_id="", $phone_number, $message){

		global $core, $dbconn, $clsISO;

		$msg = "Error";

		try {

			$curl = new \Curl\Curl();

			$curl->setHeaders(array(

				'Content-Type' => 'application/json',

				'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MGI2NmFiOWNlNmZhMjRiNmI4ZjYzYSIsImlhdCI6MTc0NTU3NzY0MywiZXhwIjoxNzc3MTEzNjQzfQ.5KLCxWVvffGidJxfIH1Vw4FoYUxZk1zqAm-nc8-YSes'

			));

			$curl->post('https://public-api.bizflow.vn/functions/680b66ab9ce6fa24b6b8f63a', array(

				'message' => $message,

				'user_id' => $user_id,

				'phone_number' => $phone_number

			));

			$response = toArray($curl->response);

			if(!$curl->error){

				if(isset($response['status']) && $response['status'] == 200){

					$msg = 'Success';

				}

			} else {

				if(isset($response['status']) && $response['status'] == 404){

					$msg = 'Not Found Contact';

				} 

			}

		} catch(Exception $e){

			$msg = sprintf('Caught exception: %s',  $e->getMessage());

		}

		return $msg;

	}

	function sendMsgSchedule($user_id, $phone_number, $message){

		global $core, $dbconn, $clsISO;

		$msg = "Error";

		try {

			$curl = new \Curl\Curl();

			$curl->setHeaders(array(

				'Content-Type' => 'application/json',

				'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MWM2MTUwMzYzMzgxYjcyMjNlZjRiYiIsImlhdCI6MTc0NjY5MDM4NCwiZXhwIjoxNzc4MjI2Mzg0fQ.fK8syfjd19uSzeWDBYQHOhDPWdhag_0lJUllpbbVS8M'

			));

			$curl->post('https://public-api.bizflow.vn/functions/681c6150363381b7223ef4bb', array(

				'message' => $message,

				'user_id' => (string) $user_id,

				'phone_number' => $phone_number,

				'is_broadcast' => 0

			));

			if(!$curl->error){

				$response = toArray($curl->response);

				if(isset($response['status']) && $response['status'] == 200){

					$msg = "Success";

				}

			} else {

				$msg = "Curl Error";

			}

		} catch(Exception $e){

			$msg = sprintf('Caught exception: %s',  $e->getMessage());

		}

		// Return

		return $msg;

	}

	function sendMsgSchedule2($user_id, $phone_number, $body){

		global $core, $dbconn, $clsISO;

		$msg = "Error";

		try {

			$curl = new \Curl\Curl();

			$curl->setHeaders(array(

				'Content-Type' => 'application/json',

				'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MWM2MTUwMzYzMzgxYjcyMjNlZjRiYiIsImlhdCI6MTc0NjY5MDM4NCwiZXhwIjoxNzc4MjI2Mzg0fQ.fK8syfjd19uSzeWDBYQHOhDPWdhag_0lJUllpbbVS8M'

			));

			$curl->post('https://public-api.bizflow.vn/functions/681c6150363381b7223ef4bb', array(

				'message' => $body["message"],

				'styles' => $body["styles"],

				'user_id' => (string) $user_id,

				'phone_number' => $phone_number,

				'is_broadcast' => 0

			));

			if(!$curl->error){

				$response = toArray($curl->response);

				if(isset($response['status']) && $response['status'] == 200){

					$msg = "Success";

				}

			} else {

				$msg = "Curl Error";

			}

		} catch(Exception $e){

			$msg = sprintf('Caught exception: %s',  $e->getMessage());

		}

		// Return

		return $msg;

	}

	function sendZaloUser($profile_id,$message,$oProfile=null) {

		global $clsISO,$core;

		$clsProfile = new Profile();

		if(empty($oProfile)) {

			$oProfile = $clsProfile->getOne($profile_id);

			$oProfile["more_information"] = $clsISO->to_array_json($oProfile["more_information"]);

		}

		$more_information = $oProfile['more_information'];

		

		$id_zalo = $core->get_field($more_information, "zaloId", "");

		$phone = !empty($oProfile["phone"]) ? $oProfile["phone"] : "";

		if(!empty($phone)) {

			if(!empty($id_zalo)){

				$this->sendMsgSchedule($id_zalo, $phone, $message);

			} else {

				$id_zalo = $this->getZaloId("", $phone);

				if(!empty($id_zalo)){

					$more_information['zaloId'] = $id_zalo;

					$clsProfile->updateOne($profile_id, array(

						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

					));

					$this->sendMsgSchedule($id_zalo, $phone, $message);

				}

			}

		}

	}

	function sendZaloUser2($profile_id,$body,$oProfile=null) {

		global $clsISO,$core;

		$clsProfile = new Profile();

		if(empty($oProfile)) {

			$oProfile = $clsProfile->getOne($profile_id);

			$oProfile["more_information"] = $clsISO->to_array_json($oProfile["more_information"]);

		}

		$more_information = $oProfile['more_information'];

		

		$id_zalo = $core->get_field($more_information, "zaloId", "");

		$phone = !empty($oProfile["phone"]) ? $oProfile["phone"] : "";

		if(!empty($phone)) {

			if(!empty($id_zalo)){

				$this->sendMsgSchedule2($id_zalo, $phone, $body);

			} else {

				$id_zalo = $this->getZaloId("", $phone);

				if(!empty($id_zalo)){

					$more_information['zaloId'] = $id_zalo;

					$clsProfile->updateOne($profile_id, array(

						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

					));

					$this->sendMsgSchedule2($id_zalo, $phone, $body);

				}

			}

		}

	}

	function createZaloPayloadFromMarkedMessage($message){

		// map màu tên -> hex (không có #)

		$colorMap = [

			'red'   => 'db342e',

			'green' => '388e3c',

			'blue'  => '1a73e8',

			'cyan'  => '00bcd4',

			'purple'=> '8e24aa',

		];

		// Chuẩn hoá newline

		$message = str_replace(["\r\n", "\r"], "\n", $message);

		// Kiểm tra hỗ trợ grapheme (đếm grapheme cluster đúng cho emoji phức tạp)

		$useGrapheme = function_exists('grapheme_strlen') && function_exists('grapheme_substr');

		// Định nghĩa hàm length và substr phù hợp

		if ($useGrapheme) {

			$lenFn = function(string $s) { return grapheme_strlen($s); };

			$substrFn = function(string $s, int $start, int $len = 1) { return grapheme_substr($s, $start, $len); };

		} else {

			$lenFn = function(string $s) { return mb_strlen($s, 'UTF-8'); };

			$substrFn = function(string $s, int $start, int $len = 1) { return mb_substr($s, $start, $len, 'UTF-8'); };

		}

		// Tách tokens: giữ delimiters (** , {name}, {/name})

		$parts = preg_split('/(\*\*|\{\/?[a-zA-Z0-9_]+\})/u', $message, -1, PREG_SPLIT_DELIM_CAPTURE);

		$plain = '';

		$styles = [];

		$stack = []; // stack item: ['st'=>..., 'start'=>int]

		foreach ($parts as $part) {

			if ($part === '') continue;

			// Toggle bold **

			if ($part === '**') {

				// tìm open bold gần nhất

				$found = null;

				for ($i = count($stack) - 1; $i >= 0; $i--) {

					if ($stack[$i]['st'] === 'b') { $found = $i; break; }

				}

				if ($found === null) {

					$stack[] = ['st' => 'b', 'start' => $lenFn($plain)];

				} else {

					$item = $stack[$found];

					$len = $lenFn($plain) - $item['start'];

					if ($len > 0) $styles[] = ['start' => $item['start']+1, 'len' => $len, 'st' => $item['st']];

					array_splice($stack, $found, 1);

				}

				continue;

			}

			// Open tag {name}

			if (preg_match('/^\{([a-zA-Z0-9_]+)\}$/u', $part, $m)) {

				$name = $m[1];

				if (isset($colorMap[$name])) {

					$st = 'c_' . $colorMap[$name];

				} elseif (is_numeric($name) && intval($name) > 0) {

					$st = 'f_' . intval($name);

				} else {

					$st = $name;

				}

				$stack[] = ['st' => $st, 'start' => $lenFn($plain)];

				continue;

			}

			// Close tag {/name}

			if (preg_match('/^\{\/([a-zA-Z0-9_]+)\}$/u', $part, $m)) {

				$name = $m[1];

				$expected = isset($colorMap[$name]) ? 'c_' . $colorMap[$name] : (is_numeric($name) ? 'f_' . intval($name) : $name);

				$found = null;

				for ($i = count($stack) - 1; $i >= 0; $i--) {

					if ($stack[$i]['st'] === $expected) { $found = $i; break; }

				}

				if ($found !== null) {

					$item = $stack[$found];

					$len = $lenFn($plain) - $item['start'];

					if ($len > 0) $styles[] = ['start' => $item['start']+1, 'len' => $len, 'st' => $item['st']];

					array_splice($stack, $found, 1);

				}

				continue;

			}

			// Text bình thường: append (bao gồm \n và emoji)

			$plain .= $part;

		}

		// Auto-close các tag còn mở tới cuối plain

		while (!empty($stack)) {

			$item = array_pop($stack);

			$len = $lenFn($plain) - $item['start'];

			if ($len > 0) $styles[] = ['start' => $item['start']+1, 'len' => $len, 'st' => $item['st']];

		}

		// Sắp xếp styles theo start tăng dần

		usort($styles, function($a, $b) { return $a['start'] <=> $b['start']; });

		// Áp quy tắc: mỗi newline ("\n") xuất hiện trước vị trí start sẽ cộng +1 vào start

		if (!empty($styles)) {

			$total = $lenFn($plain);

			// build prefix newline count: newlineCountBefore[i] = số '\n' trước index i (0-based)

			$newlineCountBefore = array_fill(0, $total + 1, 0);

			$count = 0;

			for ($i = 0; $i < $total; $i++) {

				$newlineCountBefore[$i] = $count;

				$ch = $substrFn($plain, $i, 1);

				if ($ch === "\n") $count++;

			}

			$newlineCountBefore[$total] = $count;

			// adjust each style.start bằng số newline trước start

			foreach ($styles as &$s) {

				$orig = $s['start'];

				$offset = 0;

				if ($orig >= 0 && $orig <= $total) $offset = $newlineCountBefore[$orig];

				$s['start'] = $s['start'] + $offset;

			}

			unset($s);

		}

		return [

			"message"	=>	$plain,

			"styles"	=>	$styles

		];

	}

	function parseZaloMessage($message) {

		$message = strip_tags($message);

		$message = $this->removeEmoji($message);

		$colorMap = [

			'red'    => 'c_ff0000',

			'green'  => 'c_00aa00',

			'orange' => 'c_ff8800',

			'blue'  => 'c_1a73e8',

			'cyan'  => 'c_00bcd4',

			'purple'=> 'c_8e24aa',

		];

		$styles = [];

		$clean  = '';

		$stack = [];

		$msgLen = mb_strlen($message);

		$posClean = 0; // vị trí trong clean text

		for ($i = 0; $i < $msgLen; $i++) {

			$ch = mb_substr($message, $i, 1);

			// detect bold marker **

			if ($ch === '*' && mb_substr($message, $i, 2) === '**') {

				if (!empty($stack) && end($stack)['type'] === 'b') {

					// close bold

					$open = array_pop($stack);

					$styles[] = [

						'start' => $open['start'],

						'len' => $posClean - $open['start'],

						'st' => 'b',

					];

				} else {

					// open bold

					$stack[] = [

						'type' => 'b',

						'start' => $posClean

					];

				}

				$i++; // skip additional *

				continue;

			}

			if ($ch === '_' && mb_substr($message, $i, 2) === '__') {

				if (!empty($stack) && end($stack)['type'] === 'i') {

					// close bold

					$open = array_pop($stack);

					$styles[] = [

						'start' => $open['start'],

						'len' => $posClean - $open['start'],

						'st' => 'i',

					];

				} else {

					// open bold

					$stack[] = [

						'type' => 'i',

						'start' => $posClean

					];

				}

				$i++; // skip additional *

				continue;

			}

			// detect opening color tag {xxx}

			if ($ch === '{' && preg_match('/^\{([a-zA-Z]+)\}/u', mb_substr($message, $i), $m)) {

				$tag = $m[1];

				if (isset($colorMap[$tag])) {

					$stack[] = [

						'type' => $colorMap[$tag],

						'start' => $posClean

					];

				}

				$i += mb_strlen($m[0])-1;

				continue;

			}

			// detect closing color tag {/xxx}

			if ($ch === '{' && preg_match('/^\{\/([a-zA-Z]+)\}/u', mb_substr($message, $i), $m)) {

				$tag = $m[1];

				$code = $colorMap[$tag] ?? null;

				if ($code) {

					for ($s = count($stack)-1; $s >= 0; $s--) {

						if ($stack[$s]['type'] === $code) {

							$open = $stack[$s];

							$styles[] = [

								'start' => $open['start'],

								'len' => $posClean - $open['start'],

								'st' => $open['type']

							];

							unset($stack[$s]);

							$stack = array_values($stack);

							break;

						}

					}

				}

				$i += mb_strlen($m[0])-1;

				continue;

			}

			// normal character

			$clean .= $ch;

			$posClean++;

		}

		return [

			'message'    => $clean,

			'styles' => $styles

		];

	}

	function removeEmoji($text){

		// Emoji & pictographs (toàn bộ plane emoji)

		$text = preg_replace('/[\x{1F000}-\x{1FAFF}]/u', '', $text);



		// Dingbats, symbols

		$text = preg_replace('/[\x{2600}-\x{27BF}]/u', '', $text);



		// Misc Technical (⏰ ⏳ ⌛ ⌚ ⏱ ...)

		$text = preg_replace('/[\x{2300}-\x{23FF}]/u', '', $text);



		// Variation Selectors (️)

		$text = preg_replace('/[\x{FE00}-\x{FE0F}]/u', '', $text);



		// Zero Width Joiner

		$text = preg_replace('/\x{200D}/u', '', $text);



		// Dọn khoảng trắng dư

		$text = preg_replace('/[ \t]+/', ' ', $text);

		$text = preg_replace('/\n{3,}/', "\n\n", $text);



		return trim($text);

	}	

	function sendNotifyCRMZalo($admin_id, $adProfile=[], $totalInsert, $customer_insert=[]){

		global $profile_id,$oneProfile;

		$clsProfile = new Profile();

		$ad_field = "`full_name`,`first_name`,`last_name`,`phone`,`more_information`";

		if(empty($adProfile)) $adProfile = $clsProfile->getOne($admin_id, $ad_field);

		$zaloId = $clsProfile->getZaloId($admin_id, $adProfile);

		if(!empty($zaloId) && $totalInsert > 0){

			$message = sprintf("Xin chào %s", $clsProfile->getFullName($admin_id, $adProfile));

			$message.= "\r";

			$message.= sprintf("[%s %s] đã giao cho bạn phụ trách thêm +%s khách hàng tiềm năng. ", 

				$oneProfile['role_name'], $clsProfile->getFullName($profile_id, $oneProfile), $totalInsert);

			if(!empty($customer_insert) && count($customer_insert) < 3) {

				$message.= "\r--------------------------------\r";

				foreach ($customer_insert as $key => $val) {

					$message .= "Tên KH: ".$val["customer_name"];

					$message.= "\r";

					$message.= "SĐT: ".$val["phone"];

					$message.= "\r";

					$message.= "Nhu cầu: ".$val["begin_need"];

					$message.= "\r--------------------------------\r";

				}

			}



			$message.= "Hãy truy cập CRM/Quản lý khách hàng (".DOMAIN_URL."/crm/) để bắt đầu chăm sóc khách hàng!";						

			$message.= "\r";

			// $clsISO->print_pre($message);die;

			$this->sendMsgSchedule($zaloId, $adProfile['phone'], $message);

		}

		return 1;

	}

}

?>