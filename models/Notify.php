<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class Notify extends dbBasic{

	function __construct(){

		$this->pkey = "notify_id";

		$this->tbl = DB_PREFIX."notify";

	}

	function insertNotify($tbl,$pkey,$pval,$content,$send_date,$list_user_slash,$user_id = 0){

		global $core, $dbconn, $profile_id, $clsISO;

		if(IS_ADMIN_PAGE==1) $profile_id = 0;

		if($user_id > 0) $profile_id = $user_id;

		if(empty($profile_id)) $profile_id = 0;

		if(!empty($list_user_slash) && is_array($list_user_slash)){

			$list_user_slashed = $clsISO->makeSlashListFromArray($list_user_slash);

		} else {

			$list_user_slashed = $list_user_slash;

		}

		$this->insert(array(

            $this->pkey => $this->getMaxId(),

			'user_id' => $profile_id,

            'tbl'   => $tbl,

            'pkey'  => $pkey,

            'pval'  => $pval,

            'content' => $content,

			'send_date' => $send_date,

			'list_user_read' => '|0|',

            'list_user_slash' => $list_user_slashed,

            'reg_date'  => time()

        ));

	}

	function send_notification($params){

		global $clsISO;

		$title = $params['title'];

		$message = $params['message'];

		$url = $params['url'];

		$icon = URL_IMAGES . '/logo-icon.png';

		

		$status = false;

		$apiKey = PUSHALERT_API_KEY;

		// $apiKey = "0511dd598134fc61291091b3a44cb6e0";

		$curlUrl = "https://api.pushalert.co/rest/v1/send";

		//POST variables

		$post_vars = array(

			"url" => $url,

			"icon" => $icon,

			"title" => $title,

			"message" => $message

		);

		try {

			$headers = Array();

			$headers[] = "Authorization: api_key=".$apiKey;

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $curlUrl);

			curl_setopt($ch, CURLOPT_POST, true);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_vars));

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);

			$output = json_decode($result, true);

			// $clsISO->print_pre($output); die();

			if(isset($output["success"]) && $output["success"]) {

				$status = false;

			}

		} catch(Exception $ex){

			$status = false;

		}

		return $status;

	}

	function send_subscriber_notification($params, $subscribers = array()){

		global $clsISO,$profile_id;

		$title = $params['title'];

		$message = $params['message'];

		$url = $params['url'];

		$icon = URL_IMAGES . '/logo-icon.png';

//		return 0;

		$status = false;

		$apiKey = PUSHALERT_API_KEY;

		// $apiKey = "0511dd598134fc61291091b3a44cb6e0";

		$curlUrl = "https://api.pushalert.co/rest/v1/send";

		//POST variables

		$post_vars = array(

			"url" => $url,

			"icon" => $icon,

			"title" => $title,

			"message" => $message,

			"subscribers" => json_encode($subscribers)

		);

		try {

			$headers = Array();

			$headers[] = "Authorization: api_key=".$apiKey;

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $curlUrl);

			curl_setopt($ch, CURLOPT_POST, true);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_vars));

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);

			$output = json_decode($result, true);

			if(isset($output["success"]) && $output["success"]) {

				$status = true;

			}

		} catch(Exception $ex){

			$status = false;

		}

		return $status;

	}

	function checkIsRead($notify_id, $oDataTable=array()){

		global $profile_id, $clsISO;

		if(!isset($oDataTable['list_user_read'])){

			$oDataTable = $this->getOne($notify_id, "list_user_read");

		}

		$list_user_read = $oDataTable['list_user_read'];

		$list_user_read = !empty($list_user_read) 

			? $clsISO->getArrayByTextSlash($list_user_read) 

			: array();

		if(in_array($profile_id, $list_user_read))

			return 1;

		return 0;

	}

	function sendEmailRequestPTG($member_id, $stock_code,$type=""){

		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO,$oneProfile,$profile_id,$header_configs;

		$clsEmailTemplate = new EmailTemplate();

		$clsProfile = new Profile();

		$clsStock = new Stock();

		$email_template_id = _MAIL_REQUEST_PTG_MOC;

		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);

		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);

		###

		$oneMember = $clsProfile->getOne($member_id, "full_name,first_name,last_name,email,user_name");

		$full_name = $clsProfile->getFullName($member_id, $oneMember);

		$content = "Phiếu tính giá căn hộ ".$stock_code." trên hệ thống đã được cập nhật";

		$from_name = $oneProfile["full_name"];

		$text_footer = "Chúng tôi hy vọng bạn tìm thấy các thông báo hữu ích, tuy nhiên, nếu bạn không muốn nhận các thông báo về công việc hay báo cáo, vui lòng truy cập hồ sơ của bạn tại địa chỉ ".PCMS_URL." để tiến hành cấu hình các chỉnh sửa.";

		$title = "Phiếu tính giá";

		$subject = "Thông báo phiếu tính giá căn ".$stock_code;

		if($type == "soldout"){

			$content = "Căn hộ ".$stock_code." bạn yêu cầu phiếu tính giá đã được bán";

		}else if($type == "exist") {

			$content = "Căn hộ ".$stock_code." bạn yêu cầu đã có phiếu tính giá";

		}

		

		$mapField = array(

			'{name}' => $full_name,

			'{from_name}' => $from_name,

			'{user_password}' => $new_password,

			'{title}' => $title,

			'{content}' => $content,

			'{subject}' => $subject,

			'{text_footer}' => $text_footer,

			'{link}' => "<a href='".(PCMS_URL.sprintf('/gl/%s.html', $stock_code))."'>".$stock_code."</a>"

		);

		foreach($mapField as $key => $val){

			$subject = str_replace($key, $val, $subject);

			$message = str_replace($key, $val, $message);

		}

		###

		$toemail = $oneMember["email"];

		$toname = $clsProfile->getFullName($member_id, $oneMember);		

		$fromname = $header_configs["CompanyName"];

		$fromemail = $header_configs["CompanyEmail"];

		

		return $clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

	}

	function sendEmailCalendar($member_id, $data=array()){

		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO,$oneProfile,$profile_id,$header_configs;

		$clsEmailTemplate = new EmailTemplate();

		$clsProfile = new Profile();

		$clsStock = new Stock();

		$email_template_id = _MAIL_CALENDAR;

		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);

		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);

		###

		$oneMember = $clsProfile->getOne($member_id, "full_name,first_name,last_name,email,user_name");

		$full_name = $clsProfile->getFullName($member_id, $oneMember);

		$name = "Hệ thống CA";

		$i = 0;

		$lstBilling = !empty($data['billings']) ? $data['billings'] : array();

		$admin_name = !empty($data['admin_name']) ? $data['admin_name'] : "";

		$table = '';

		if(!empty($lstBilling)) {

			$table .= '<table border="0" class="table table-bordered mceItemTable" cellpadding="0" cellspacing="0" style="border-collapse:collapse">

							<thead><tr>

								<th rowspan="2" width="40px" style="text-align: center;border: 1px solid;">STT</th>

								<th rowspan="2" width="80px" style="text-align: center;border: 1px solid;">Mã căn</th>

								<th rowspan="2" width="120px" style="text-align: center;border: 1px solid;">Admin phụ trách</th>

								<th rowspan="2" width="120px" style="text-align: center;border: 1px solid;">Ngày ký</th>

								<th rowspan="2" width="100px" style="text-align: center;border: 1px solid;">Loại hình ký</th>

							</tr></thead>

							<tbody>';

			foreach ($lstBilling as $key => $val) {

				$table .='<tr>

						<td width="40px" style="text-align: center;border: 1px solid;">'.(++$i).'</td>

						<td width="80px" style="text-align: center;border: 1px solid;">'.$val["stock_code"].'</td>

						<td width="120px" style="text-align: center;border: 1px solid;">'.$val["admin_name"].'</td>

						<td width="120px" style="text-align: center;border: 1px solid;">'.date("d/m/Y").'</td>

						<td width="100px" style="text-align: center;border: 1px solid;">'.$val["type"].'</td>

					</tr>';

			}

			

			$table .='</tbody>

					</table>';

		}

		

		$mapField = array(

			'{name}' => $name,

			'{table}' => $table,

			'{link}' => "<a href='".(DOMAIN_URL.'/lich-ky.html')."'>Lịch ký</a>"

		);

		foreach($mapField as $key => $val){

			$subject = str_replace($key, $val, $subject);

			$message = str_replace($key, $val, $message);

		}

		###

		$toemail = $oneMember["email"];

		$toname = $full_name;		

		$fromname = $header_configs["CompanyName"];

		$fromemail = $header_configs["CompanyEmail"];

		return $clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

	}

	

	function sendZaloContent($content,$group_zalo_id){

		global $clsISO;

		$curl = new \Curl\Curl();

		$curl->setHeaders(array(

			'Content-Type' => 'application/json',

			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4MDExYjFkYjQ5YzhlZTdkM2VhYzAxMCIsImlhdCI6MTc0NDkwMjk0MSwiZXhwIjoxNzc2NDM4OTQxfQ.lv4JEMevPdhqzWlWBS7vJGwFkwmYCL4ZD6_aaujVtrA'

		));

		$curl->post('https://public-api.bizflow.vn/functions/68011b1db49c8ee7d3eac010', array(

			'message' 		=> $content,

			'group_id' 		=> $group_zalo_id,

		));

		if(!$curl->error){

			$response = toArray($curl->response);

			if(isset($response['status']) && $response['status'] == 200){

				return 1;

			}

		}

		// Return

		return 0;

	}

	function sendZaloImage($image,$group_zalo_id){

		global $clsISO;

		$curl = new \Curl\Curl();

		$curl->setHeaders(array(

			'Content-Type' => 'application/json',

			'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTQ5MjY4NTAxOTEwMmQ0MjhmOWY5ZiIsImlhdCI6MTc1NDU2NzI3MiwiZXhwIjoxNzg2MTAzMjcyfQ.fmEaolefHG9tAJf9oHD0m7Yn-bMjxZhphx7pf7pG_hg'

		));

		$curl->post('https://public-api.bizflow.vn/functions/689492685019102d428f9f9f', array(

			'url'		 	=> $image,

			'desc' 			=> "",

			'group_id' 		=> $group_zalo_id,

			'groupLayoutId' => 0,

		));

		if(!$curl->error){

			$response = toArray($curl->response);

			if(isset($response['status']) && $response['status'] == 200){

				return 1;

			}

		}

		// Return

		return 0;

	}

}

?>