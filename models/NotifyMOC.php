<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class NotifyMOC extends dbBasic{

	function __construct(){

		$this->pkey = "notify_id";

		$this->tbl = DB_PREFIX."notify_MOC";

	}

	function insertNotify($tbl,$pkey,$pval,$content,$send_date,$list_user_slash){

		global $core, $dbconn, $profile_id, $clsISO;

		if(IS_ADMIN_PAGE==1) $profile_id = 0;

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

			// $clsISO->print_pre($output); die();

			if(isset($output["success"]) && $output["success"]) {

				$status = false;

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

		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO,$header_configs;

		$clsEmailTemplate = new EmailTemplate();

		$clsMember = new Member();

		$clsStock = new Stock();

		$email_template_id = _MAIL_REQUEST_PTG_MOC;

		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);

		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);

		###

		$oneMember = $clsMember->getOne($member_id, "full_name,first_name,last_name,email,user_name");

		$full_name = $clsMember->getFullName($member_id, $oneProfile);

		$content = "Phiếu tính giá căn hộ ".$stock_code." trên hệ thống đã được cập nhật";

		$from_name = "Quản trị viên hệ thống ".$header_configs["site_name"];

		$text_footer = "Cảm ơn Quý khách đã tin tưởng và sử dụng hệ thống của ".$header_configs["site_name"].". </br>

		Truy cập <a href='".DOMAIN_URL."'>".DOMAIN_NAME."</a> để trải nghiệm các tính năng của hệ thống.";

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

			'{stock_code}' => $stock_code,

			'{text_footer}' => $text_footer,

			'{link}' => "<a href='".(DOMAIN_URL."/".$stock_code.".html")."'>".$stock_code."</a>"

		);

		foreach($mapField as $key => $val){

			$subject = str_replace($key, $val, $subject);

			$message = str_replace($key, $val, $message);

		}

		###

		$toemail = $oneMember["email"];

		$toname = $clsMember->getFullName($member_id, $oneProfile);		

		$fromname = $header_configs["CompanyName"];

		$fromemail = $header_configs["CompanyEmail"];

		

		return $clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);

	}

}

?>