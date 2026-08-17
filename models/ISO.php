<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is �2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class ISO{
	function __construct(){
		// Some code
	}
	function trim_space($url){
		return preg_replace('/\s+/','',$url);
		return $url;
	}
	function replace($subject, $search, $replace){
		return str_replace($search, $replace, $subject);
	}
	function set_attrs($arr){
		if(empty($arr)) return '';
		$attr= ""; $index=0;
		foreach($arr as $k => $v){
			$attr .= ($index==0?'':' ')."{$k}=\"{$v}\"";
			++$index;
		}
		return $attr;
	}
	function make_attrs_builder($arr){
		if(empty($arr)) return '';
		$attr= ""; $index=0;
		foreach($arr as $k => $v){
			$attr .= ($index==0?'':' ')."{$k}=\"{$v}\"";
			++$index;
		}
		return $attr;
	}
	function make_query_string($arr, $type='&'){
		if(empty($arr)) return '';
		$query_string = ""; $index=0;
		foreach($arr as $k => $v){
			$query_string .= ($index==0?'':$type)."{$k}={$v}";
			++$index;
		}
		return $query_string;
	}
	function makeIcon($icon, $text=""){
		if(!empty($text))
			return sprintf('<i class="bx %s"></i> %s', $icon, $text);
		return sprintf('<i class="bx %s"></i>', $icon);
	}
	function buildTree($items = [], $parent_id = 0, $pkey="") {
		$map = $tree = [];
		// build map
		foreach ($items as $id => &$item) {
			$item['children'] = [];
			$map[$item[$pkey]] = &$item;
		}
		// build full tree
		foreach ($map as $id => &$item) {
			if ($item['parent_id'] == 0) {
				$tree[$id] = &$item;
			} elseif (isset($map[$item['parent_id']])) {
				$map[$item['parent_id']]['children'][$id] = &$item;
			}
		}
		// n?u parent_id = 0 ? tr? full tree
		if ($parent_id === 0) {
			return $tree;
		}
		// n?u parent_id != 0 ? tr? subtree
		return $tree[$parent_id]['children'] ?? (
			$map[$parent_id]['children'] ?? []
		);
	}
	function parseP2nl($txt){
		$txt = strip_tags(html_entity_decode(trim($txt),ENT_COMPAT,'UTF-8'),'<p><br>');
		$txt = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $txt);//remove style
		$txt = ltrim($txt,'<p>');
		$txt = rtrim($txt,'</p>');
		$txt = preg_replace("/(>\s+<)/", "><", $txt);
		return html_entity_decode(str_ireplace(array("<p>"), "\r\n",str_ireplace(array("</p><p>","<br />","<br>","<br/>"), "\r\n", $txt)));
	}
	function parseName($name){
		$name = trim($name);
		$name = str_replace("  ", " ", $name);
		$parts = @explode(' ', $name);
		if (count($parts) === 1){
			$first_name = $name;
			$last_name = $name;
		} else {
			$first_name = implode(" ", $parts);
			$last_name = array_pop($parts);
		}
		return array(
			'last_name' => $last_name,
			'first_name' => $first_name
		);
	}
	function clean_cache($block){
		global $core, $dbconn, $clsISO;
		$clsCache = new Cache();
		$cached_arrs = array(); 
		if($block == 'project'){
			$cached_arrs[] = '_header_project_cached';
		} else if($block == 'agency'){
			$cached_arrs[] = '_stock_agency_cached';
		} else if($block == 'bedroom'){
			$cached_arrs[] = '_stock_bedroom_cached';
		} else if($block == 'block'){
			
		} else if($block == 'profile'){
			$cached_arrs[] = '_list_profile_cached';
		}
		if(!empty($cached_arrs)){
			foreach($cached_arrs as $cache_name){
				if($clsCache->has($cache_name)){
					$clsCache->delete($cache_name);
				}	
			}
		}
	}
	function resize_image_url($url,$w,$h,$allowed_full=true){
		if($w > 0 || $h > 0){
			return ($allowed_full?PCMS_URL:"").'/files/thumb/'.$w.'/'.$h.'/'.$url;
		}
		if($w > 0 && $h > 0){
			return ($allowed_full?PCMS_URL:"").'/files/thumb/'.$w.'/'.$h.'/'.$url;
		} else if($w == 0 && $h > 0){
			return ($allowed_full?PCMS_URL:"").'/assets/thumb/'.$h.'/'.$url;
		} else if($w > 0 && $h == 0){
			return ($allowed_full?PCMS_URL:"").'/files/thumb/'.$w.'/'.$url;
		} 
	}
	function force_br_newline($string) {
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	}	
	function to_array_json($json, $def=[]){
		if(!empty($json)){
			if(is_array($json)){
				return $json;
			} else {
				return json_decode(html_entity_decode($json), true);
			}
		} else {
			return $def;
		}
	}
	function getValue($field, $arr, $def=""){
		return isset($arr[$field]) && !empty($arr[$field]) 
			? trim($arr[$field]) : $def; 
	}
	function getListStar($num){
		$star = array();
		for($i=1; $i<=$num; $i++){
			$star[] = $i;
		}
		return $star;
	}
	function get_greeting(){
		global $core, $dbconn;
		$h = (int) date('G');
		$you = 'bạn';
		if($this->checkPermissionGroup('DIRECTOR') 
			|| $this->checkPermissionGroup('SALE_DIRECTOR') 
			|| $this->checkPermissionGroup('PROJECT_DIRECTOR')
			|| $this->checkPermissionGroup('REGIONAL_DIRECTOR')){
			$you = 'Sếp';
		}
		if($h>=5 && $h<=11) {
			if(date('D') == 'Sun'){
				$wellcome = sprintf('Chúc %s một buổi sáng tràn năng lượng và niềm vui!', $you);
			} else {
				$wellcome = sprintf('Chúc %s một buổi sáng làm việc hiệu quả!', $you);
			}
			return array(
				'id' => 'morning',
				'title' => 'buổi sáng',
				'emoji' => URL_IMAGES.'/Icon-Thoi-Tiet/im_80_morning.svg',
				'wellcome' => $wellcome
			);
        } else if($h>=12 && $h<=17){
			if(date('D') == 'Sun'){
				$wellcome = sprintf('Chúc %s một buổi chiều nhẹ nhàng, công việc suôn sẻ!', $you);
			} else {
				$wellcome = sprintf('Chúc %s một buổi chiều làm việc hiệu quả!', $you);
			}
			return array(
				'id' => 'afternoon',
				'title' => 'buổi chiều',
				'emoji' => URL_IMAGES.'/Icon-Thoi-Tiet/im_80_afternoon.svg',
				'wellcome' => $wellcome
			);
        } else{
			$arr_templates = array(
				sprintf('Chúc %s một buổi tối thật dịu dàng & bình an.', $you),
				sprintf('Chúc %s buổi tối ấm áp & đầy yêu thương ❤️!', $you),
				sprintf('Chúc %s một buổi tối bình yên & tràn đầy năng lượng 🌙', $you)
			);
			$wellcome = $arr_templates[array_rand($arr_templates)];
			return array(
				'id' => 'evening',
				'title' => 'buổi tối',
				'emoji' => URL_IMAGES.'/Icon-Thoi-Tiet/im_80_evening.svg',
				'wellcome' => $wellcome
			);
        }
	}
	function generateHTMLStar($num){
		$html = '';
		for($i=1; $i<=$num; $i++){
			$html .= '<i class="star_10"></i>';
		}
		$range = 5-$num;
		if($range > 0){
			for($i=0; $i<$range; $i++){
				$html .= '<i class="star_00"></i>';
			}
		}
		return $html;
	}
	function toInt($str, $def=0){
		if(!empty($str))
			return (int) $str;
		return $def;
	}
	function toPositive($number) {
		return abs($number);
	}
	function toNumber($val) {
		if (is_numeric($val)) {
			$int = (int)$val;
			$float = (float)$val;
			$val = ($int == $float) ? $int : $float;
			return $val;
		} else {
			return $val;
		}
	}
	function toTime($str, $end=""){
		if(!empty($str)){
			if($this->checkContainer($str, "/", "")){
				if(!empty($end)){
					return strtotime(sprintf('%s %s', str_replace('/','-', $str), $end));
				} else {
					return strtotime(str_replace('/','-', $str));
				}
			} else {
				if(!empty($end)){
					return strtotime(sprintf('%s %s', $str, $end));
				} else {
					return strtotime($str);
				}
			}
		}
		return 0;
	}
	function toYMD($str){
		if(!empty($str)){
			$tmp = explode('/', $str);
			return sprintf('%s-%s-%s', $tmp[2], $tmp[1], $tmp[0]);
		}
		return '';
	}
	function toDMY($str){
		if(!empty($str)){
			$tmp = explode('-', $str);
			//$this->print_pre($tmp); die();
			return sprintf('%s/%s/%s', $tmp[2], $tmp[1], $tmp[0]);
		}
		return '';
	}
	function isImage($url) {
		// L?y ph?n m? r?ng c?a file
		$fileExtension = pathinfo($url, PATHINFO_EXTENSION);
		// Danh s�ch c�c du�i m? r?ng h�nh ?nh ph? bi?n
		$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
		// Ki?m tra n?u ph?n m? r?ng n?m trong danh s�ch
		if (in_array(strtolower($fileExtension), $imageExtensions)) {
			return true;
		}
		return false;
	}
	function isPDF($url) {
		// L?y ph?n m? r?ng c?a file
		$fileExtension = pathinfo($url, PATHINFO_EXTENSION);
		// Ki?m tra n?u ph?n m? r?ng l� 'pdf'
		return strtolower($fileExtension) === 'pdf';
	}
	function parsePriceDecimal($price){
		$price = preg_replace('/\s+/','',$price);
		$price = str_replace(';','',$price);
		$price = str_replace('?','',$price);
		$price = str_replace('$','',$price);
		$price = str_replace(",",".",$price);
        $price = preg_replace('/\.(?=.*\.)/', '', $price);
		return floatval($price);
	}
	function checkExitsID($pvalTable, $clsTable) {
		$clsClassTable = new $clsTable();
		return $clsClassTable->countItem("{$clsClassTable->pkey}='{$pvalTable}'");
	}
	function getBrowser(){
		require_once $_SERVER['DOCUMENT_ROOT'].'/inc/Mobile_Detect.php';
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		return $deviceType; 
	}
    function parseNumber($num){
        return (int) $num < 10 ? '0'.(int) $num : $num;
    }
    function parseNumber2($num){
		$len = strlen($num);
		if($len >= 4) {
			return $num;
		}else{
			$str = "";
			for($i=1; $i <= (4-$len); $i++) {
				$str .= "0";
			}
			$str .= $num;
			return $str;
		}
    }
	function getVar($key=''){
		$res = get_defined_constants(false);
		return $res[$key];
	}
	function _DEV(){
		global $dev;
		return (int) $dev==1 ? 1 : 0;
	}
    function print_pre ($expression, $wrap = false) {
        $css = 'border:1px dashed #06f;padding:1em;text-align:left;';
        if ($wrap) {
            $str = '<p style="' . $css . '"><tt>' . str_replace(
                    array('  ', "\n"), array('&nbsp; ', '<br />'),
                    htmlspecialchars(print_r($expression, true))
                ) . '</tt></p>';
        } else {
            $str = '<pre style="' . $css . '">'
                . htmlspecialchars(print_r($expression, true)) . '</pre>';
        }
        echo $str;
    }
	function replaceSpace($str) {
		if(!$str) return false;
		$str = str_replace(array('%',"/","\\",'"','?','<','>',"#","^","`","'","=","!",":" ,",,","..","*","&","__","_",',','/','-',"�","�","�","?","`","?","~",".","�","�","(",")"),array('','','','','','','','','',"","",'','','','','','','','','',' ',' ','','','','','','','','','','','','',''),html_entity_decode(trim($str))); 
		$unicode = array(
			'a'=>'�|�|�|?|?|�|�|?|?|?|?|?|a|?|?|?|?|?|�|�|�|?|?|�|�|?|?|?|?|?|A|?|?|?|?|?',
			'd'=>'�|d',
			'e'=>'�|�|?|?|?|�|?|?|?|?|?|�|�|?|?|?|�|?|?|?|?|?',
			'i'=>'�|�|�|?|?|i|�|�|?|?|I',
			'o'=>'�|�|�|?|?|�|�|?|?|?|?|?|o|?|?|?|?|?|�|�|�|?|?|�|�|?|?|?|?|?|O|?|?|?|?|?|?',
			'u'=>'�|�|�|?|?|u|u|?|?|?|?|?|�|�|�|?|?|U|U|?|?|?|?|?',
			'y'=>'?|�|?|?|?|?|�|?|?|?'	
		);
		$count = 0;
		foreach($unicode as $nonUnicode=>$uni) {
			$str = preg_replace("/($uni)/i", $nonUnicode, addslashes($str));
			$count++;
		}
		if($count>0)
			for($i=0; $i<$count; $i++)
				$str = stripslashes($str);
				
		$str = preg_replace("/&([a-z])[a-z]+;/i","$1",$str);
		$str = preg_replace("/\s+/","-",$str);
		//$str = preg_match("[^A-Za-z0-9\-]", "", $str);
		return strtolower($str);
	}
	function sendEmail($toemail,$toname,$subject,$message, $cc = array()){
		global $core, $dbconn, $clsISO, $clsConfiguration;
		/** Info E-mail */
		$mail_type = $clsConfiguration->getValue('mail_type', 'smtp');
		$fromname  = $clsConfiguration->getValue('mail_'.$mail_type.'_fromname');
		$fromemail = $clsConfiguration->getValue('mail_'.$mail_type.'_fromemail');
		/* End */
		$status = 'error'; $msg = "";
		if($mail_type=='smtp'){
			$mail_configs = $clsConfiguration->getValues(array(
				'mail_smtp_username',
				'mail_smtp_password',
				'mail_smtp_secure',
				'mail_smtp_host',
				'mail_smtp_port',
				'mail_smtp_authentication'
			));
			$mail_smtp_username = trim($mail_configs['mail_smtp_username']);
			$mail_smtp_password = trim($mail_configs['mail_smtp_password']);
			$mail_smtp_secure = trim($mail_configs['mail_smtp_secure']);
			$mail_smtp_host = trim($mail_configs['mail_smtp_host']);
			$mail_smtp_port = $mail_configs['mail_smtp_port'];
			$mail_smtp_authentication = isset($mail_configs['mail_smtp_authentication']) 
				? (int) $mail_configs['mail_smtp_authentication'] : 0;
			require_once(DIR_INCLUDES.'/mailer/PHPMailer/src/Exception.php');
			require_once(DIR_INCLUDES.'/mailer/PHPMailer/src/PHPMailer.php');
			require_once(DIR_INCLUDES.'/mailer/PHPMailer/src/SMTP.php');
			$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
			try {
				$mail->CharSet = 'utf-8';
				$mail->From = $fromemail;
				$mail->FromName = $fromname;
				$mail->AddAddress(trim($toemail));
				// SMTP
				$mail->IsSMTP();
				$mail->SMTPDebug = 0;
				$mail->Sender = $mail->From;
				// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->Hostname = $_SERVER['SERVER_NAME'];
				$mail->Host = $mail_smtp_host;
				$mail->SMTPSecure = $mail_smtp_secure;
				$mail->Port = $mail_smtp_port;
				// Authenticate
				if($mail_smtp_authentication){
					$mail->SMTPAuth = true;
					$mail->Username = $mail_smtp_username;
					$mail->Password = $mail_smtp_password;
				} 
				// Content
				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body = $message;
				// Send
				if($mail->Send()){
					$status = 'success';
					$msg = $core->get_Lang('Send email successfully');
				}
				$mail->ClearAddresses();
			} catch (Exception $e) {
				$status = 'error';
				$msg = $mail->ErrorInfo;
			}
		} else if($mail_type=='sendgrid'){
			$mail_configs = $clsConfiguration->getValues(array(
				'mail_sendgrid_api_enable',
				'mail_sendgrid_api_key',
				'mail_sendgrid_api_url',
				'mail_sendgrid_username',
				'mail_sendgrid_password'
			));
			$mail_sendgrid_api = $clsISO->toInt($mail_configs['mail_sendgrid_api_enable']);
			$mail_sendgrid_api_key = trim($mail_configs['mail_sendgrid_api_key']);
			$mail_sendgrid_api_url = trim($mail_configs['mail_sendgrid_api_url']);
			$mail_sendgrid_username = trim($mail_configs['mail_sendgrid_username']);
			$mail_sendgrid_password = trim($mail_configs['mail_sendgrid_password']);
			if(empty($mail_sendgrid_username) 
				&& empty($mail_sendgrid_username) 
				&& empty($mail_sendgrid_api_key)){
				$data = file_get_contents('https://go.vietiso.com/modules/servers/sendgrid/password.txt');
				list($mail_sendgrid_username, $mail_sendgrid_password) = @explode('|', $data);
				$mail_sendgrid_username = @base64_decode($mail_sendgrid_username);
				$mail_sendgrid_password = @base64_decode($mail_sendgrid_password);
			}
			if($mail_sendgrid_api){
				if(!empty($mail_sendgrid_api_key)){
					$params = array(
						'personalizations' => array(
							array(
								'subject' => $subject,
								'to' => array(
									array(
										'email'	=> $toemail,
										'name' => $toname,
									),
								),
							)
						),
						'from' => array(
							"name" => $fromname,
							"email" => $fromemail
						),
						'reply_to' => array(
							"name" => $fromname,
							"email" => $fromemail
						),
						'content' => array(
							array(
								"type" => 'text/html',
								"value" => html_entity_decode($message)
							),
						)
					);
					// Generate curl request
					$ch = @curl_init($mail_sendgrid_api_url);
					// Tell curl to use HTTP TimeOut
					curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
					curl_setopt($ch, CURLOPT_MAXREDIRS, 30);
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);
					// Tell curl to use HTTP Version
					curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
					// Tell curl not to return headers, but do return the response
					curl_setopt($ch, CURLOPT_HEADER, false);
					// Tell curl to 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// Tell curl to use HTTP POST
					curl_setopt ($ch, CURLOPT_POST, true);
					// Tell curl that this is the body of the POST
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt ($ch, CURLOPT_POSTFIELDS, @json_encode($params));
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Authorization: Bearer '.$mail_sendgrid_api_key
					));
					// obtain response
					$response = curl_exec($ch);
					$err = curl_error($ch);
					@curl_close($ch);
					// print everything out
					$status = 'error';
					$msg =  $core->get_Lang('Send email error');
					if ($err) {
						$status = 'success';
						$msg = $core->get_Lang('Send email successfully');
					}
				} else {
					$params = array(
						'api_user' => $mail_sendgrid_username,
						'api_key' => $mail_sendgrid_password,
						'to' => $toemail,
						'replyto' => $toemail,
						'subject' => $subject,
						'html' => $message,
						'from' => $fromemail,
						'fromname' => $fromname
					);
					// Generate curl request
					$ch = curl_init($mail_sendgrid_api_url);
					// Tell curl to use HTTP POST
					curl_setopt ($ch, CURLOPT_POST, true);
					// Tell curl that this is the body of the POST
					curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
					// Tell curl not to return headers, but do return the response
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					// obtain response
					$response = curl_exec($ch);
					curl_close($ch);
					// print everything out
					$status = 'error';
					$msg =  $core->get_Lang('Send email error');
					if(strpos($response,'success') == true){
						$status = 'success';
						$msg = $core->get_Lang('Send email successfully');
					}
				}
			} else {
				if(!empty($mail_sendgrid_api_key)){
					require_once(DIR_INCLUDES.'/mailer/SendGrid3.0/vendor/autoload.php');
					$email = new \SendGrid\Mail\Mail();
					$email->setFrom($fromemail, $fromname);
					$email->setSubject($subject);
					$email->addTo($toemail, "");
					$email->addContent("text/html", $message);
					$sendgrid = new \SendGrid($mail_sendgrid_api_key);
					try {
						$response = $sendgrid->send($email);
						if($response->statusCode()=='200' || $response->statusCode()=='202'){
							$status = 'success';
							$msg =  $core->get_Lang('Send email successfully');
						}
					} catch (Exception $e) {
						$status = 'error';
						$msg = $e->getMessage();
					}
				} else {
					require_once(DIR_INCLUDES.'/mailer/SendGrid/vendor/autoload.php');
					$sendgrid = new SendGrid(
						$mail_sendgrid_username,
						$mail_sendgrid_password, 
						array("turn_off_ssl_verification" => false)
					);
					// Create object
					$mail = new \SendGrid\Email();
					// Param send email
					$mail->addTo($toemail)
						  ->setFrom($fromemail)
						  ->setFromName($fromname)
						  ->setReplyTo($toemail)
						  ->setSubject($subject)
						  ->setHtml($message)
						  ->addHeader('X-Sent-Using', 'SendGrid-API')
						  ->addHeader('X-Transport', 'web');
					// obtain response
					$response = $sendgrid->send($mail);
					// Return
					$status = 'error';
					$msg =  $core->get_Lang('Send email error');
					if(isset($response->body['message']) 
					   && $response->body['message']=='success'){
						$status = 'success';
						$msg =  $core->get_Lang('Send email successfully');
					}
				}
			}
		}
		return $status == 'success' ? 1 : 0;
	}
	function sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message,$cc = array()){
		global $core, $dbconn, $clsISO, $clsConfiguration;
		/** Info E-mail */
		$clsConfiguration = new Configuration();
		$mail_type = $clsConfiguration->getValue('mail_type', 'smtp');
		$status = 'error'; $msg = "";
		if($mail_type=='smtp'){
			$mail_configs = $clsConfiguration->getValues(array(
				'mail_smtp_username',
				'mail_smtp_password',
				'mail_smtp_secure',
				'mail_smtp_host',
				'mail_smtp_port',
				'mail_smtp_authentication'
			));
			$mail_smtp_username = trim($mail_configs['mail_smtp_username']);
			$mail_smtp_password = trim($mail_configs['mail_smtp_password']);
			$mail_smtp_secure = trim($mail_configs['mail_smtp_secure']);
			$mail_smtp_host = trim($mail_configs['mail_smtp_host']);
			$mail_smtp_port = $mail_configs['mail_smtp_port'];
			$mail_smtp_authentication = isset($mail_configs['mail_smtp_authentication']) 
				? (int) $mail_configs['mail_smtp_authentication'] : 0;
			require_once(DIR_INCLUDES.'/mailer/PHPMailer/src/Exception.php');
			require_once(DIR_INCLUDES.'/mailer/PHPMailer/src/PHPMailer.php');
			require_once(DIR_INCLUDES.'/mailer/PHPMailer/src/SMTP.php');
			$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
			try {
				$mail->CharSet = 'utf-8';
				$mail->From = $fromemail;
				$mail->FromName = $fromname;
				$mail->AddAddress(trim($toemail));
				// SMTP
				$mail->IsSMTP();
				$mail->SMTPDebug = 0;
				$mail->Sender = $mail->From;
				// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->Hostname = $_SERVER['SERVER_NAME'];
				$mail->Host = $mail_smtp_host;
				$mail->SMTPSecure = $mail_smtp_secure;
				$mail->Port = $mail_smtp_port;
				// Authenticate
				if($mail_smtp_authentication){
					$mail->SMTPAuth = true;
					$mail->Username = $mail_smtp_username;
					$mail->Password = $mail_smtp_password;
				} 
				// Content
				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body = $message;
				if(!empty($cc)){
					foreach($cc as $email){
						$mail->AddCC($email);
					}
				}
				// Send
				if($mail->Send()){
					$status = 'success';
					$msg = $core->get_Lang('Send email successfully');
				}
				$mail->ClearAddresses();
			} catch (Exception $e) {
				$status = 'error';
				$msg = $mail->ErrorInfo;
			}
		} else if($mail_type=='sendgrid'){
			$mail_configs = $clsConfiguration->getValues(array(
				'mail_sendgrid_api_enable',
				'mail_sendgrid_api_key',
				'mail_sendgrid_api_url',
				'mail_sendgrid_username',
				'mail_sendgrid_password'
			));
			$mail_sendgrid_api = $clsISO->toInt($mail_configs['mail_sendgrid_api_enable']);
			$mail_sendgrid_api_key = trim($mail_configs['mail_sendgrid_api_key']);
			$mail_sendgrid_api_url = trim($mail_configs['mail_sendgrid_api_url']);
			$mail_sendgrid_username = trim($mail_configs['mail_sendgrid_username']);
			$mail_sendgrid_password = trim($mail_configs['mail_sendgrid_password']);
			if($mail_sendgrid_api){
				$params = array(
					'personalizations' => array(
						array(
							'subject' => $subject,
							'to' => array(
								array(
									'email'	=> $toemail,
									'name' => $toname,
								),
							),
						)
					),
					'from' => array(
						"name" => $fromname,
						"email" => $fromemail
					),
					'replyTo' => array(
						"name" => $fromname,
						"email" => $fromemail
					),
					'content' => array(
						array(
							"type" => 'text/html',
							"value" => html_entity_decode($message)
						),
					)
				);
				// Generate curl request
				$ch = @curl_init($mail_sendgrid_api_url);
				// Tell curl to use HTTP TimeOut
				curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
				curl_setopt($ch, CURLOPT_MAXREDIRS, 30);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				// Tell curl to use HTTP Version
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				// Tell curl not to return headers, but do return the response
				curl_setopt($ch, CURLOPT_HEADER, false);
				// Tell curl to 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Tell curl to use HTTP POST
				curl_setopt ($ch, CURLOPT_POST, true);
				// Tell curl that this is the body of the POST

				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt ($ch, CURLOPT_POSTFIELDS, @json_encode($params));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Authorization: Bearer '.$mail_sendgrid_api_key
				));
				// obtain response
				$response = curl_exec($ch);
				$err = curl_error($ch);
				@curl_close($ch);
				// print everything out
				$status = 'error';
				$msg =  $core->get_Lang('Send email error');
				if ($err) {
					$status = 'success';
					$msg = $core->get_Lang('Send email successfully');
				}
			} else {
				require_once(DIR_INCLUDES.'/mailer/SendGrid3.0/vendor/autoload.php');
				$email = new \SendGrid\Mail\Mail();
				$email->setFrom($fromemail, $fromname);
				$email->setSubject($subject);
				$email->addTo($toemail, "");
				$email->addContent("text/html", $message);
				$sendgrid = new \SendGrid($mail_sendgrid_api_key);
				try {
					$response = $sendgrid->send($email);
					if($response->statusCode()=='200' || $response->statusCode()=='202'){
						$status = 'success';
						$msg =  $core->get_Lang('Send email successfully');
					}
				} catch (Exception $e) {
					$status = 'error';
					$msg = $e->getMessage();
				}
			}
		} else if($mail_type == 'brevo'){
			$mail_configs = $clsConfiguration->getValues(array(
				'mail_brevo_api_key',
				'mail_brevo_api_url',
				'mail_brevo_username',
				'mail_brevo_password',
			));
			// $this->print_pre($mail_configs); die();
			$mail_brevo_api_key = $mail_configs['mail_brevo_api_key'];
			$mail_brevo_api_url = $mail_configs['mail_brevo_api_url'];
			$mail_brevo_username = $mail_configs['mail_brevo_username'];
			$mail_brevo_password = $mail_configs['mail_brevo_password'];
			$more = array();
			if(!empty($cc)){
				foreach($cc as $email){
					$more['cc'][] = array(
						'email' => $email
					);
				}
			}
			// $clsISO->print_pre($more); die();
			$params = array_merge($more, array(
				'to' => array(
					array(
						'email'	=> $toemail,
						'name' => html_entity_decode($toname)
					),
				),
				'sender' => array(
					"name" => html_entity_decode($fromname),
					"email" => $fromemail
				),
				'subject' => $subject,
				'htmlContent' => html_entity_decode($message)
			));
			// Generate curl request
			$ch = @curl_init($mail_brevo_api_url);
			// Tell curl to use HTTP TimeOut
			curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
			curl_setopt($ch, CURLOPT_MAXREDIRS, 30);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			// Tell curl to use HTTP Version
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			// Tell curl not to return headers, but do return the response
			// curl_setopt($ch, CURLOPT_HEADER, false);
			// Tell curl to 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Tell curl to use HTTP POST
			curl_setopt ($ch, CURLOPT_POST, true);
			// Tell curl that this is the body of the POST
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, @json_encode($params));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'accept: application/json',
				'content-type: application/json',
				'api-key: '.$mail_brevo_api_key
			));
			// obtain response
			$response = @curl_exec($ch);
			$http_code = (int) @curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error = @curl_error($ch);
			@curl_close($ch);
			// print everything out
			$status = 'error';
			// Brevo tra 2xx (201) khi nhan de giao; curl_error rong chua du vi HTTP 4xx bi bo qua truoc day
			if (empty($error) && $http_code >= 200 && $http_code < 300) {
				$status = 'success';
			}
			/** End Sendgrid */ 
		}
		return $status == 'success' ? 1 : 0;
	}
	function sendEmailSystemMulti($fromemail,$fromname,$arrtoemail,$subject,$message,$cc = array()){
		global $core, $dbconn, $clsISO, $clsConfiguration;
		/** Info E-mail */
		$clsConfiguration = new Configuration();
		$mail_type = $clsConfiguration->getValue('mail_type', 'brevo');
		$status = 'error'; $msg = "";
		$arr_to = [];
		foreach($arrtoemail as $key => $val) {
			if(!empty($val["email"]) && !empty($val["full_name"])) {
				$arr_to[] = [
					"email"	=>	$val["email"],
					"name"	=>	$val["full_name"]
				];
			}
			
		}		
		$status = 'error';
		if(!empty($arr_to)) {
			if($mail_type=='sendgrid'){
				$mail_configs = $clsConfiguration->getValues(array(
					'mail_sendgrid_api_enable',
					'mail_sendgrid_api_key',
					'mail_sendgrid_api_url',
					'mail_sendgrid_username',
					'mail_sendgrid_password'
				));
				$mail_sendgrid_api = $clsISO->toInt($mail_configs['mail_sendgrid_api_enable']);
				$mail_sendgrid_api_key = trim($mail_configs['mail_sendgrid_api_key']);
				$mail_sendgrid_api_url = trim($mail_configs['mail_sendgrid_api_url']);
				$mail_sendgrid_username = trim($mail_configs['mail_sendgrid_username']);
				$mail_sendgrid_password = trim($mail_configs['mail_sendgrid_password']);
				if($mail_sendgrid_api){
					$params = array(
						'personalizations' => array(
							array(
								'subject' => $subject,
								'to' => $arr_to
							)
						),
						'from' => array(
							"name" => $fromname,
							"email" => $fromemail
						),
						'replyTo' => array(
							"name" => $fromname,
							"email" => $fromemail
						),
						'content' => array(
							array(
								"type" => 'text/html',
								"value" => html_entity_decode($message)
							),
						)
					);
					// Generate curl request
					$ch = @curl_init($mail_sendgrid_api_url);
					// Tell curl to use HTTP TimeOut
					curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
					curl_setopt($ch, CURLOPT_MAXREDIRS, 30);
					curl_setopt($ch, CURLOPT_TIMEOUT, 60);
					// Tell curl to use HTTP Version
					curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
					// Tell curl not to return headers, but do return the response
					curl_setopt($ch, CURLOPT_HEADER, false);
					// Tell curl to 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// Tell curl to use HTTP POST
					curl_setopt ($ch, CURLOPT_POST, true);
					// Tell curl that this is the body of the POST
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt ($ch, CURLOPT_POSTFIELDS, @json_encode($params));
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Authorization: Bearer '.$mail_sendgrid_api_key
					));
					// obtain response
					$response = curl_exec($ch);
					$err = curl_error($ch);
					@curl_close($ch);
					// print everything out
					$status = 'error';
					$msg =  $core->get_Lang('Send email error');
					if ($err) {
						$status = 'success';
						$msg = $core->get_Lang('Send email successfully');
					}
				}
			} else if($mail_type == 'brevo'){
				$mail_configs = $clsConfiguration->getValues(array(
					'mail_brevo_api_key',
					'mail_brevo_api_url',
					'mail_brevo_username',
					'mail_brevo_password',
				));
				// $this->print_pre($mail_configs); die();
				$mail_brevo_api_key = $mail_configs['mail_brevo_api_key'];
				$mail_brevo_api_url = $mail_configs['mail_brevo_api_url'];
				$mail_brevo_username = $mail_configs['mail_brevo_username'];
				$mail_brevo_password = $mail_configs['mail_brevo_password'];
				$more = array();
				if(!empty($cc)){
					foreach($cc as $email){
						$more['cc'][] = array(
							'email' => $email
						);
					}
				}
				// $clsISO->print_pre($more); die();
				$params = array_merge($more, array(
					'to' => $arr_to,
					'sender' => array(
						"name" => html_entity_decode($fromname),
						"email" => $fromemail
					),
					'subject' => $subject,
					'htmlContent' => html_entity_decode($message)
				));
				// Generate curl request
				$ch = @curl_init($mail_brevo_api_url);
				// Tell curl to use HTTP TimeOut
				curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
				curl_setopt($ch, CURLOPT_MAXREDIRS, 30);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				// Tell curl to use HTTP Version
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				// Tell curl not to return headers, but do return the response
				// curl_setopt($ch, CURLOPT_HEADER, false);
				// Tell curl to 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Tell curl to use HTTP POST
				curl_setopt ($ch, CURLOPT_POST, true);
				// Tell curl that this is the body of the POST
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, @json_encode($params));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'accept: application/json',
					'content-type: application/json',
					'api-key: '.$mail_brevo_api_key
				));
				// obtain response
				$response = @curl_exec($ch);
				$error = @curl_error($ch);
				@curl_close($ch);
				// print everything out
				$status = 'error';
				if (empty($error)) {
					$status = 'success';
				}
				/** End Sendgrid */ 
			}
		}
		
		return $status == 'success' ? 1 : 0;
	}
	function getUniqid($more_entropy=true){
		return str_replace('.','',uniqid('',$more_entropy));	
	}
	function getRandomNumber($min, $max){
		return mt_rand($min, $max);	
	}
	function getArrayFromString($string){
		if($string ==''){ return '';}
		return unserialize($string);
	}
	# Convert m�u hex (#RRGGBB ho?c #RGB) sang chu?i rgba(r,g,b,alpha). D�ng cho tint n?n theo m�u (vd bgcolor tr?ng th�i luu trong DB).
	function hexToRgba($hex, $alpha = 1){
		$hex = ltrim(trim((string) $hex), '#');
		if(strlen($hex) === 3){ $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2]; }
		if(strlen($hex) !== 6 || !ctype_xdigit($hex)){ return 'rgba(0,0,0,0)'; } // m�u r?ng/sai -> trong su?t, an to�n
		$a = (float) $alpha; if($a < 0){ $a = 0; } if($a > 1){ $a = 1; }
		$a = rtrim(rtrim(sprintf('%.3f', $a), '0'), '.'); if($a === ''){ $a = '0'; }
		return sprintf('rgba(%d,%d,%d,%s)', hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)), $a);
	}
	function genIMG($url, $w, $h, $style="", $class="thumb-small aspect-ratio__content"){
		return '<div class="aspect-ratio aspect-ratio--square aspect-ratio--square--50 aspect-ratio--interactive"><img class="'.$class.'" src="'.$url.'" width="'.$w.'" height="'.$h.'" style="'.$style.'" onerror="this.src=\''.URL_IMAGES.'/no-image.jpg\'" /></div>';
	}
	function makeLabel($doc, $background, $color='#FFF', $style="", $title=null){
		$style .= "color:{$color}; background:{$background}";
		return '<span class="label" style="'.$style.'">'.$doc.'</span>';
	}
	function getTextList($array){
		$html = '';
		if($array[0]!=''){
			for($i=0;$i<count($array);$i++){
				$html .= ($i!=0?",":'').$array[$i];
			}
		}
		return $html;
	}
	function getArrayByText($text){
		if(str_replace(',','',$text)==$text)
			return explode(',',$text);
		return $this->getArrayByTextSlash($text); 
	}
	function getArrayByTextSlash($str, $slash = ",", $def=[]){
		$ret = $def;
		if(!empty($str) && $str != '|' && $str !='||'){
			$str = ltrim($str, '|');
			$str = rtrim($str, '|');
			$str = str_replace('||','|',$str);
			$str = str_replace('|',$slash,$str);
			$ret = @explode($slash, $str);
		}
		return $ret;
	}
	function renderHTMLNoDocument($text, $svg=false){
		$svgIcon = '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="80px" height="80px" viewBox="0 0 16 16" version="1.1"><title>comment-discussion</title><g id="Octicons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="comment-discussion" fill="#999"><path d="M15 1H6c-.55 0-1 .45-1 1v2H1c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h1v3l3-3h4c.55 0 1-.45 1-1V9h1l3 3V9h1c.55 0 1-.45 1-1V2c0-.55-.45-1-1-1zM9 11H4.5L3 12.5V11H1V5h4v3c0 .55.45 1 1 1h3v2zm6-3h-2v1.5L11.5 8H6V2h9v6z" id="Shape"/></g></g><metadata><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xmlns:dc="http://purl.org/dc/elements/1.1/"><rdf:Description about="https://iconscout.com/legal#licenses" dc:title="comment,discussion" dc:description="comment,discussion" dc:publisher="Iconscout" dc:date="2017-09-14" dc:format="image/svg+xml" dc:language="en"><dc:creator><rdf:Bag><rdf:li>Github</rdf:li></rdf:Bag></dc:creator></rdf:Description></rdf:RDF></metadata></svg>';
		return '<div class="text-center">
			'.($svg?$svgIcon:'<img class="mb-2" src="'._IMG_NODOCUMENT.'" width="40px" />').'
			<p class="type--subdued">'.$text.'</p>
		</div>';
	}
	function renderHTMLNoDocumentV2($text){
		return '<div class="text-center">
			<img class="mb10" src="'._ICON_NODOCUMENT.'" width="40px" />
			<p>'.$text.'<p>
		</div>';
	}
	function renderHTMLLoading(){
		return '<div class="indicator">
			<img src="'.URL_IMAGES.'/loading.gif" width="30px" height="auto" alt="Loading..."> Loading...
		</div>';
	}
	function deleteFile($path){
		$conn = ftp_connect(ftp_host_info) or die("Could not connect");
		ftp_login($conn,ftp_usr_info,ftp_pwd_info);
		ftp_delete($conn,str_replace(ftp_abs_path_info,'',$this->parseImageURL($path, false)));
		ftp_close($conn);
	}
	function getFirstCharacterList($str){
		$tmp = explode(' ',$str);
		$html = '';
		for($i=0;$i<count($tmp);$i++) $html .= strtoupper($tmp[$i][0]);
		return $html;
	}
	function parseImageURL($url, $allow_full=true){
		$url = str_replace(DOMAIN_URL, '', $url);
		return $url;
	}
	function base642imagejpeg($data, $filename, $dirname){
		global $dbconn,$core;
		/** Check folder exists */
		if(!is_dir(ROOTPATH.$dirname)){
			@rmkdir(ROOTPATH.$dirname,0777);
		}
		/** Defined full path */
		$filename = $dirname.'/'.$filename;
		$tmp = @explode(";base64,", $data);
        $data = @base64_decode($tmp[1]);
		/** header('Content-Type: image/jpeg'); */
		@write_file(ROOTPATH.$filename, $data);
		return $filename;
	}
	function cropImage($file,$crop_width,$crop_height){
		global $core;
		$reg_date = time();
		$host = ftp_host_info;
		$usr = ftp_usr_info;
		$pwd = ftp_pwd_info;
		$abs_path = ftp_abs_path_info;
		/*Get File Extension*/
		$path_parts = pathinfo($file);
		$ext = $path_parts['extension'];
		if($ext!='jpg'&&$ext!='png'&&$ext!='gif'){
			$ext = 'jpg';
		}
		/*Connect FTP*/
		$conn_id = ftp_connect($host) or die ("Cannot connect to host");
		ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
		/*File Name*/
		$day = date('d',$reg_date);
		$month = date('m',$reg_date);
		$year = date('Y',$reg_date);
		$dirname = 'content';
		#
		$nMn = md5($file);
		$nMn = 'vietiso-'.$crop_width.'-'.$crop_height.'-'.$path_parts['filename'].'-'.substr($nMn, 0, 10); 
		$name = '/'.$dirname.'/'.$nMn.'.'.$ext;
		$res = ftp_size($conn_id, $name);
		//print_r($abs_path.$name);die();
		if($res != -1){
			//return 'available';
			return $abs_path.$name;  
		} else{
			list($width_orig, $height_orig) = getimagesize($file);
			$temp_file = ftp_temp_file_info;
			if($ext == "jpg"){
				$image_p = imagecreatetruecolor($crop_width, $crop_height);
				$image = imagecreatefromjpeg($file);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $crop_width, $crop_height, $width_orig, $height_orig);
				$temp_file .= $new_name.'.'.$ext;
				imagejpeg($image_p, $temp_file);		
			}elseif($ext == "png"){
				$image_p = imagecreatetruecolor($crop_width, $crop_height);
				$image = imagecreatefrompng($file);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $crop_width, $crop_height, $width_orig, $height_orig);
				$temp_file .= $new_name.'.'.$ext;
				imagepng($image_p, $temp_file);
			}elseif($ext == "gif"){			
				$image_p = imagecreatetruecolor($crop_width, $crop_height);
				$image = imagecreatefromgif($file);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $crop_width, $crop_height, $width_orig, $height_orig);
				$temp_file .= $new_name.'.'.$ext;
				imagegif($image_p, $temp_file);
			}else{
				return '';
			}
			//===================================================================
			$upload = ftp_put($conn_id, $name, $temp_file, FTP_BINARY);
			//===================================================================			
			imagedestroy($image_p);
			unlink($temp_file);
		}
		ftp_close($conn_id);
		return $abs_path.$name;	
	}
	function getListModule(){
		global $core;
		return $core->getListAdminModule();		
	}
	function getRate(){
		return 'đ';
	}
	function getRateSign(){
		return 'đ';
	}
	function getPercent($a, $b){
		return round(($a/$b*100),1);
	}
	function shortNumber($num, $round = 1, $small = 0) {
		global $clsISO;
		$small_s = $small ? '<small class="fs-10">' : '';
		$small_e = $small ? '</small>' : '';
		$units = [$clsISO->getRate(), 'K', 'K', 'triệu', 'tỷ', 'ngìn tỷ','triệu tỷ'];
		// ? Gi? l?i d?u
		$sign = $num < 0 ? '-' : '';
		// ? L?y gi� tr? tuy?t d?i d? x? l�
		$num = abs($num);
		// Logic cu c?a b?n
		if ($num >= (1000 * 1000000000)) {
			$round = 3;
		}
		for ($i = 0; $num >= 1000 && $i < count($units) - 1; $i++) {
			$num /= 1000;
		}
		return $sign . number_format($num, $round, ",", ".") . " " . $small_s . $units[$i] . $small_e;
	}
	function shortNumberV2($num, $round=1) {
		global $clsISO;
		define('MILLION', 1000000);
		if($num >= (1000*1000000000)){
			$round = 3;
			$units = [$clsISO->getRate(), 'K', 'triệu', 'tỷ', 'ngìn tỷ','triệu tỷ'];
			for ($i = 0; $num >= 1000; $i++) {
				$num /= 1000;
			}
			return number_format($num,$round,".",",") ." ". $units[$i];
		} else {
			$units = [$clsISO->getRate(), 'K', 'triệu', 'tỷ', 'ngìn tỷ','triệu tỷ'];
			for ($i = 0; $num >= 1000; $i++) {
				$num /= 1000;
			}
			return number_format($num,$round,".",",") ." ". $units[$i];
		}
	}
	function priceFormat($price, $num=0){
		return number_format($price,$num,',','.');
	}
	function priceFormatV2($price,$num){
		return round(number_format($price,0,',','.'),$num);
	}
	function priceFormatV3($price,$limit='0') {
		if(!empty($price)){
			return number_format((float) $this->priceFormat($price),$limit,',','');
		} else {
			return $price;
		}
	}
	function formatPrice($string,$limit='0') {
		return number_format($string,$limit,",",".");
		if(function_exists('money_format') && 1==2){
			setlocale(LC_MONETARY,"vi_VN");
			return money_format('%!.0n', $string);
		} else {
			return number_format($string,$limit,",",".");
		}
	}
	function formatPriceV2($string,$limit='0') {
		if(function_exists('money_format') && 1==2){
			if(strlen($string) > 5){
				setlocale(LC_MONETARY,"vi_VN");
				$money = @money_format('%!.0n', $string);
				return round($money,3);
			} else {
				setlocale(LC_MONETARY,"vi_VN");
				return money_format('%!.0n', $string);
			}
		} else {
			return number_format($string,$limit,",",".");
		}
	}
	function formatNumber($number){
		$pos = @strpos($number,".");
		if($pos==false)
			return $number.'.0';
		return $number;
	}
	function formatNumber2($number){
		return str_replace(',','.',number_format($number));
	}
	function formatNumberToEasyRead($price){
		return str_replace('.',',',number_format($price));
	}
	function _roundFix($num, $fix){
		return round($num, $fix);
	}
	function formatShortPrice($num, $fix=2){
		$units = ['', 'K', 'triệu', 'tỷ', 'ngìn tỷ','triệu tỷ'];
		for ($i = 0; $num >= 1000; $i++) {
			$num /= 1000;
		}
		return round($num, 3) ." ". $units[$i];
	}
	function formatShortPriceOrigin($price){
        global $clsISO;
		$price2k = substr($price,-3);
        if($price2k=="000")
			$price = substr($price,0,-3);
            return $clsISO->priceFormat($price)."K";
        return $price;
    }
	function makeSlashListFromArrayRoot($arr){
		$html = '';
		if(!empty($arr)){
			$arr = array_unique($arr); 
			foreach($arr as $item){
				$html.= sprintf('|%s|', $item);
			}
		}
		return $html; 
	}
	function makeSlashListFromArray($array, $paid='|', $flag=true){
		global $core, $dbconn;
		$html = $flag ? $paid : '';
		if(!empty($array)){
			for($i=0;$i<count($array);$i++){
				if($flag){
					$html.= $array[$i].$paid;
				}else{
					$html.= $array[$i].($i==count($array)-1 ? '' : $paid);
				}
			}
		}
		return $html;
	}
	function getFirstItemInArray($arr, $def=""){
		if(!empty($arr) && is_array($arr)){
			foreach ($arr as $key => $value) {
				return $value;
			}
		}
		return $def;
	}
	function truncate($string, $length = 80, $etc = '...', $charset='UTF-8',$break_words = false, $middle = false){
		if ($length == 0)
			return '';
		if (mb_strlen($string) > $length) {
			$length -= min($length, mb_strlen($etc));
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length+1, $charset));
			}
			if(!$middle) {
				return mb_substr($string, 0, $length, $charset) . $etc;
			} else {
				return mb_substr($string, 0, $length/2, $charset) . $etc . mb_substr($string, -$length/2, (mb_strlen($string)-$length/2), $charset);
			}
		} else {
			return $string;
		}
	}
	function truncateWord($string, $limit, $pad="...") {
		if($string == '') return $string;
		if(str_word_count($string) <= $limit){ return $string;}
		$tmp = explode(' ',$string);
		if(is_array($tmp) && count($tmp) < $limit) {
			return $string;
		} else {
			$string_new = '';
			for($i=0;$i<$limit;$i++) {
				$string_new.= $tmp[$i].' ';
			}
			return $string_new . $pad;
		}
	}
	function getSelectOptionFromText($selected, $text){
		global $core, $dbconn;
		$html = '';
		$tmp = $this->getArrayByText($text);
		foreach($tmp as $item){
			$html .= '<option '.($item==$selected?'selected="selected"':'').' value="'.$item.'">'.$item.'</option>';
		}
		return $html;
	}
	function makeSelectStar($selected=''){
		$lstStar = $this->getListStar();
		$html = '<option value="">-- Select -- </option>';
		foreach($lstStar as $k=>$v){
			$html .= '<option value="'.$k.'" '.($selected==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		return $html; die();
	}
	function is_valid_email($email){
		if ($email=="" || $email==null) return 0;
		$regex = "/^[A-Za-z0-9_\.\-]+@[A-Za-z0-9_\.\-]+\.";
		$regex = $regex . "[A-Za-z0-9_\-][A-Za-z0-9_\-]+$/iD";
		if (!preg_match($regex, $email)) {
			return 0;
		}		
		return 1;   	
	}
	function getSelect($begin, $end, $selected=''){
		$html='';
		for ($i=$begin; $i < ($end+1); $i++) {
			$select=($selected==$i)?'selected="selected"':'';
			$html.='<option value="'.$i.'"'.$select.'>'.$i.'</option>';
		}
		return $html;
	}
	function makeSelectMonth($selected=''){
		$_month = array(
			'1'	=>	'January',
			'2'	=>	'February',
			'3'	=>	'March',
			'4'	=>	'April',
			'5'	=>	'May',
			'6'	=>	'June',
			'7'	=>	'July',
			'8'	=>	'August',
			'9'	=>	'September',
			'10'	=>	'October',
			'11'	=>	'November',
			'12'	=>	'December'
		);
		$html = '';
		foreach($_month as $k=>$v){
			$html .= '<option value="'.$k.'" '.($selected==$k ? 'selected="selected"':'').'>'.$v.'</option>';	
		}
		return $html;
	}
	function makeSelectYear($selected=''){
		$y = date('Y',time());
		$html = '';
		for($i= $y; $i <= ($y+5); $i++){
			$html .= '<option value="'.$i.'" '.($selected==$i ? 'selected="selected"':'').'>'.$i.'</option>';	
		}
		return $html;
	}
	function makeSelectNumber($limit,$selected,$prefix=""){
		$html = '';
		for($i=0;$i<$limit;$i++){
			$html .= '<option value="'.($i).'" '.($selected==$i?' selected="selected"':'').'>';
			if($prefix!=''){
				$tmp = explode(',',$prefix);
				if($i==1 || $i==0){
					$html .= $i.' '.$tmp[0];
				}else{
					$html .= $i.' '.$tmp[1];
				}
			}else{
				$html .= $i;
			}
			$html .= '</option>';
		}
		return $html;
	}
	function makeSelectNumber2($limit,$selected,$prefix=""){
		$html = '';
		for($i=1;$i<$limit;$i++){
			$html .= '<option value="'.($i).'" '.($selected==$i?' selected="selected"':'').'>';
			if($prefix!=''){
				$tmp = explode(',',$prefix);
				if($i==1){
					$html .= $i.' '.$tmp[0];
				}else{
					$html .= $i.' '.$tmp[1];
				}
			}else{
				$html .= $i;
			}
			$html .= '</option>';
		}
		return $html;
	}
	function is_validate_date($dateString) {
		$format = 'd/m/Y';
		$date = DateTime::createFromFormat($format, $dateString);
		// Ki?m tra:
		// - $date ph?i l� d?i tu?ng DateTime h?p l?
		// - Kh�ng c� l?i d?nh d?ng
		// - K?t qu? format l?i ph?i d�ng v?i input (tr�nh tru?ng h?p 32/01/2025 th�nh 01/02/2025)
		return $date && $date->format($format) === $dateString;
	}
	function validate_alphanumeric_underscore($str){
		return preg_match('/^\w+$/',$str);
	}
	function size_calculator($size){
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
		for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
		return round($size, 2).$units[$i];
    }
	function convertToNumber($str, $def=0){
		if(!empty($str)){
			$str = str_replace('?','',$str);
			$str = str_replace('%','',$str);
			$str = str_replace(' ','',$str);
			$str = str_replace(';','',$str);
			$str = str_replace(',','.',$str);
			$str = str_replace('(','',$str);
			$str = str_replace(')','',$str);
			return (float) $str;
		} else {
			return $def;
		}
	}
	function processSmartNumber($str, $def=0){
		if(!empty($str)) {
			$str = str_replace('?','',$str);
			$str = str_replace(' ','',$str);
			$str = str_replace(';','',$str);
			$str = str_replace('(','',$str);
			$str = str_replace(')','',$str);
			$str = str_replace(',','.',$str);
			$str = str_replace('.','',$str);
			return (int) $str;
		} else {
			return $def;
		}
	}
	function convertStringToNumber($str, $def = 0) {
		if (empty($str)) return $def;
		// B? k� t? r�c
		$str = str_replace(['?', ' ', ';', '(', ')'], '', $str);
		$dotCount = substr_count($str, '.');
		$commaCount = substr_count($str, ',');
		if ($commaCount > 0 && $dotCount == 0) {
			// Ch? c� d?u ph?y ? s? th?c US
			$parts = explode(',', $str);
			$str = $parts[0];
		} elseif ($dotCount > 0 && $commaCount == 0) {
			// Ch? c� d?u ch?m ? c� th? l� h�ng ngh�n ho?c th?p ph�n
			$parts = explode('.', $str);
			$last = end($parts);
			// N?u ph?n cu?i d�i d�ng 3 ch? s? ? gi? d?nh l� ph�n c�ch h�ng ngh�n
			if (strlen($last) === 3) {
				$str = implode('', $parts);
			} else {
				// Ngu?c l?i, coi nhu s? th?c ? b? ph?n th?p ph�n
				$str = $parts[0];
			}
		} elseif ($dotCount > 0 && $commaCount > 0) {
			$lastDot = strrpos($str, '.');
			$lastComma = strrpos($str, ',');
			if ($lastComma > $lastDot) {
				// VN: "1.234.567,89"
				$parts = explode(',', $str);
				$str = str_replace('.', '', $parts[0]);
			} else {
				// US: "1,234,567.89"
				$parts = explode('.', $str);
				$str = str_replace(',', '', $parts[0]);
			}
		}
		return (int)$str;
	}
	function convertPriceShortToFull($price,$type="BILION"){
		global $profile_id;
		$price_int = (int)$price;
		$price = strtolower($price);
		if(strpos($price,"t?") !== false) {
			$price = str_replace("t?","",$price);
			$price = str_replace(",",".",$price);
			$price = (float)$price * 1000000000;
		}else{			
			$total_count = substr_count($price,",");
			if($total_count == 1) {
				$price = (int) str_replace('.', '',$price);
			}
		}
		
		$price = $this->processSmartNumber($price);
		$priceLen = mb_strlen($price);
		$a = 0;
		if($priceLen == 3){
			$price = $price * 10000000;
		}else if($priceLen == 4 || $priceLen == 5) {			
			if($priceLen == 4 && strlen($price_int) > 1 ){
				$price = $price * 10000000;
			}else{
				$price = $price * 1000000;
			}			
		}
		return $price;
	}
	function convertPriceShortToFullUpdate($price,$min=null,$max=null){
		global $profile_id;
		$price = str_replace(" ","",$price);
		$price_int = (int)$price;
		$total_count = substr_count($price,",");
		#
		$total_count_dot = substr_count($price,".");
		if(($total_count == 1 && $total_count_dot == 0) || ($total_count_dot == 1 && $total_count == 0 )  ) {
			$price = str_replace(',', '',$price);
			$price = (int) str_replace('.', '',$price);
		}elseif($total_count == 1 && $total_count_dot == 1) {
			$pos1 = strpos($price, '.');
			$pos2 = strpos($price, ',');
			if($pos1 < $pos2) {
				$price = (int) str_replace('.', '',$price);
			}else{
				$price = str_replace(',', '',$price);
				$price = (int) str_replace('.', ',',$price);
			}
		}elseif($total_count_dot > 1) {
			$price = (int)str_replace('.', '',$price);
		}elseif($total_count > 1) {
			$price = str_replace(',', '',$price);
			$price = (int) str_replace('.', ',',$price);
		}
		/*if($total_count == 1) {
			$price = (int) str_replace('.', '',$price);
		}*/
		#------
		
		$price = $this->processSmartNumber($price);
		$priceLen = mb_strlen($price);
		if($priceLen == 3){
			$price = $price * 10000000;
		}else if($priceLen == 4 || $priceLen == 5) {
			if($priceLen == 4 && strlen($price_int) > 1 && $price_int != $price ){
				$price = $price * 10000000;
			}else{
				$price = $price * 1000000;
			}
		}
		if($profile_id == 289){
			return $price;
		}
		if((!empty($min) && $price < $min) || (!empty($max) && $price > $max)) {
			return 0;
		}
		return $price;
	}
	function formatDate($time,$type=''){
		global $_LANG_ID;
		switch($type){
			case '1':
				return $this->getDayOfWeekAcronym($time).' '.date('m-d-Y',$time);
				break;
			case '2':
				return $this->getDayOfWeek($time).', '.date('m-d-Y H:i',$time);
				break;
			case '3':
				return $this->getDayOfWeekAcronym($time).', '.date('d/m/Y',$time);
				break;
			case '4':
				return date('d/m/Y H:i',$time);
				break;
			case '5':
				return date('Y-m-d\TH:i',$time);
				break;
			default:
				return date('d-m-Y',$time);
		}
	}
	function getDayOfWeek($today){
		global $core;
		$d = date('D',$today);
		if($d=='Sun') return 'Chủ nhật';
		if($d=='Mon') return 'Thứ hai';
		if($d=='Tue') return 'Thứ ba';
		if($d=='Wed') return 'Thứ tu';
		if($d=='Thu') return 'Thứ nam';
		if($d=='Fri') return 'Thú sáu';
		if($d=='Sat') return 'Thứ bẩy';
		return '';
	}
	function getDayOfWeekAcronym($today){
		global $core;
		$d = date('D',$today);
		if($d=='Sun') return 'CN';
		if($d=='Mon') return 'T2';
		if($d=='Tue') return 'T3';
		if($d=='Wed') return 'T4';
		if($d=='Thu') return 'T5';
		if($d=='Fri') return 'T6';
		if($d=='Sat') return 'T7';
		return '';
	}
	function countTime($seconds){
		 return gmdate ('H:i:s', $seconds);
	}
	function getTimeAgo($time){
		global $_LANG_ID;
		if($_LANG_ID=='en'){
			$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		}else{
			$periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
		}
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = time();
		$difference = $now - $time;
		if($_LANG_ID=='en'){
			$tense = "ago";
		} else {
			$tense = "trước";
		}
	   	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   	    $difference /= $lengths[$j];
	   	}
	   	$difference = round($difference);
	   	if($difference != 1) {
	   		if($_LANG_ID=='en'){
	   			$periods[$j].= "s";
	   		}
	   	}
		if($now - $time>24*60*60){
			return "$difference $periods[$j] ".$tense;
		}
	   	return ''."$difference $periods[$j] ".$tense;
	}
	function getTimeMore($time){
		global $_LANG_ID;
		if($_LANG_ID=='en'){
			$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		}else{
			$periods = array("gi�y", "ph�t", "gi?", "ng�y", "tu?n", "th�ng", "nam", "th?p k?");
		}
		$lengths = array("60","60","24","7","4.35","12","10");
		$now = time();
		$difference = $time - $now;
		if($_LANG_ID=='en'){
			$tense = "more";
		} else {
			$tense = "n?a";
		}
	   	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   	    $difference /= $lengths[$j];
	   	}
	   	$difference = round($difference);
	   	if($difference != 1) {
	   		if($_LANG_ID=='en'){
	   			$periods[$j].= "s";
	   		}
	   	}
		if($time - $now > 24*60*60){
			return "$difference $periods[$j] ".$tense;
		}
	   	return "$difference $periods[$j] ".$tense;
	}
	function checkItemInArray($item,$array){
		$array = array_map('strval', $array);
		return @in_array($item,$array);
	}
	function getTimerSelect($timerChoice){
		$listOptions = '';
		for($i=0;$i<24;$i++){
			$hour = $i<10?'0'.$i:$i;
			for($k=0;$k<4;$k++){
				$min = $k*15==0?'00':$k*15;
				$time = $hour.':'.$min;
				$slt = $time==$timerChoice?'selected="selected"':"";
				$listOptions .= '<option value="'.$time.'" '.$slt.'>'.$time.'</option>';
			}
		}
		return $listOptions;
	}
	function getDayWeekList(){
		global $core;
		$listDay = array();
		$listDay[0]['key'] = 1;
		$listDay[0]['val'] = $core->get_Lang('Monday');
		$listDay[0]['val2'] = 'Mon';
		$listDay[1]['key'] = 2;
		$listDay[1]['val'] = $core->get_Lang('Tuesday');
		$listDay[1]['val2'] = 'Tue';
		$listDay[2]['key'] = 3;
		$listDay[2]['val'] = $core->get_Lang('Wednesday');
		$listDay[2]['val2'] = 'Wed';
		$listDay[3]['key'] = 4;
		$listDay[3]['val'] = $core->get_Lang('Thursday');
		$listDay[3]['val2'] = 'Thu';
		$listDay[4]['key'] = 5;
		$listDay[4]['val'] = $core->get_Lang('Friday');
		$listDay[4]['val2'] = 'Fri';
		$listDay[5]['key'] = 6;
		$listDay[5]['val'] = $core->get_Lang('Saturday');
		$listDay[5]['val2'] = 'Sat';
		$listDay[6]['key'] = 7;
		$listDay[6]['val'] = $core->get_Lang('Sunday');
		$listDay[6]['val2'] = 'Sun';
		return $listDay;
	}
	function getListWeeks(){
		$today = \Carbon\Carbon::today();
		$date = $today->copy()->firstOfYear()->startOfDay();
		$eom = $today->copy()->endOfYear()->startOfDay();
		$dates = [];
		for($i = 1; $date->lte($eom); $i++){
			$startDate = $date->copy();
			while($date->dayOfWeek != \Carbon\Carbon::SUNDAY && $date->lte($eom)){
				$date->addDay(); 
			}
			$dates[$i] = array(
				'start_date' => $startDate->format('d/m/Y'),
				'end_date' => $date->format('d/m/Y')
			);
			$date->addDay();
		}
		return $dates;
	}
	function getWeeksInMonth($month, $year) {
		$firstDayOfMonth = strtotime("$year-$month-01"); // L?y ng�y d?u ti�n c?a th�ng
		$lastDayOfMonth = strtotime(date("Y-m-t", $firstDayOfMonth)); // L?y ng�y cu?i c�ng c?a th�ng
		$weeks = []; // M?ng ch?a c�c tu?n
		$weekNumber = 1; // ��nh s? tu?n b?t d?u t? 1
		$currentDay = $firstDayOfMonth;
		while ($currentDay <= $lastDayOfMonth) {
			// L?y c�c ng�y t? th? Hai d?n Ch? Nh?t cho tu?n hi?n t?i
			$weekStart = $currentDay; // Ng�y b?t d?u tu?n
			$weekEnd = strtotime("next Sunday", $weekStart); // Ng�y cu?i tu?n l� Ch? Nh?t
			if ($weekEnd > $lastDayOfMonth) {
				$weekEnd = $lastDayOfMonth; // N?u vu?t qu� th�ng, l?y ng�y cu?i c�ng c?a th�ng
			}
			// Luu tu?n v�o m?ng v?i d?nh d?ng ng�y b?t d?u v� ng�y k?t th�c
			$weeks[] = [
				'week' => $weekNumber,
				'start' => date("d-m-Y", $weekStart),
				'end' => date("d-m-Y", $weekEnd),
			];
			// Chuy?n sang tu?n ti?p theo
			$currentDay = strtotime("next Monday", $weekStart);
			$weekNumber++;
		}
		return $weeks;
	}
	function getListWeeksOfMonth($month, $year){
		$carbon = new Carbon(new Carbon(date('Y-m-d', strtotime('next monday',strtotime($year . '-' . $month . '-01'))), 'Asia/Ho_Chi_Minh'));
		$weeks_array = [];
		while (intval($carbon->month) == intval($month)){
			$week_array[$carbon->weekOfMonth][ $carbon->dayOfWeek ] = $carbon->toDateString();
			$carbon->addDay();
		}
		return $weeks_array;
	}
	function makeArrayUnique($arr){
		$ret = array();
		for($i=0;$i<count($arr);$i++){
			if(in_array($arr[$i],$ret)){
			}
			else{
				$ret[] = $arr[$i];
			}
		}
		return $ret;
	}
	function convertDateCreateFormatDisplay($str,$txt_day=false){//d.m.Y, d-m-Y, m/d/Y
		$date = date_create($str);
		if($txt_day)
			return date_format($date,"D, d/m/Y");
		return date_format($date,"d/m/Y");
	}
	function convertDateCreateFormat($str){//d.m.Y, d-m-Y, m/d/Y
		if(empty($str)) return '';
		$date = date_create($str);
		return date_format($date,"Y-m-d");
	}
	function getYearDateCreateFormat($str){//d.m.Y
		$date = $this->convertDateCreateFormat($str);
		return date("Y",strtotime($date));
	}
	function getMonthDateCreateFormat($str){//d.m.Y
		$date = $this->convertDateCreateFormat($str);
		return date("m",strtotime($date));
	}
	function getDayDateCreateFormat($str){//d.m.Y
		$date = $this->convertDateCreateFormat($str);
		return date("d",strtotime($date));
	}
	function getDateCreateFormat($str,$obj="Y"){//d.m.Y
		$date = $this->convertDateCreateFormat($str);
		return date($obj,strtotime($date));
	}
	function getTimeFromDateCreateFormat($str){
		$date = $this->convertDateCreateFormat($str);
		return strtotime($date);
	}
	function getRangeDate($start_date, $due_date, $night=false){
		if(empty($due_date))
			return array($start_date);
		$range_date = array();
		$start_date = date('d-m-Y',$start_date);
		if($night)
			$due_date = date('d-m-Y',$due_date);
		else
			$due_date = date('d-m-Y',strtotime(date('d-m-Y',$due_date) . '+1 day'));
		if(!empty($start_date) && !empty($due_date)){
			$period = new DatePeriod(
				 new DateTime($start_date),
				 new DateInterval('P1D'),
				 new DateTime($due_date)
			);
			foreach ($period as $key => $value) {
				$range_date[] = strtotime($value->format('d-m-Y'));       
			}
		}	
		return $range_date;
	}
	function getNumDayBetweenDate($from_date, $to_date){//int
		global $core;
		if($from_date > $to_date) return false;
		$range_date = $this->getRangeDate($from_date, $to_date,false);	
		return !empty($range_date)?count($range_date):1;
	}
	function getListYearByRangeDate($start_date=null,$end_date=null){
		$lstYear = array();
		if(!empty($start_date)){
			$start_year = $this->getDateCreateFormat($start_date,"Y");
			$lstYear[] = $start_year;
			if(empty($end_date)){
				$end_year = date("Y");
				if($end_year>$start_year){
					for($i=1,$total=$end_year-$start_year;$i<=$total;$i++){
						$lstYear[] = $start_year+$i;
					}
				}
			}
		}
		if(!empty($end_date)){
			if(empty($start_date)){
				$start_year = 2020;
				$lstYear[] = $start_year;
			}
			$end_year = $this->getDateCreateFormat($end_date,"Y");
			if($end_year>$start_year){
				for($i=1,$total=$end_year-$start_year;$i<=$total;$i++){
					$lstYear[] = $start_year+$i;
				}
			}
		}
		return $lstYear;
	}
	function getYmByRangeDate($start_date=null,$end_date=null){
		if(empty($start_date)&&empty($end_date)){
			return '';
		}
		$lstYm = array();
		if(!empty($start_date)){
			$lstYm[] = $this->getDateCreateFormat($start_date,"Ym");
			if(empty($end_date)){
				$end_date = date('Y-m-d');
			}
		}
		if(!empty($end_date)){
			if(empty($start_date)){
				$start_date = "2022-10-01";
				$lstYm[] = $this->getDateCreateFormat($start_date,"Ym");
			}
		}
		
		$start_date = $this->convertDateCreateFormat($start_date);
		$end_date = $this->convertDateCreateFormat($end_date);
		$end_date = date('Y-m-d',strtotime($end_date . ' +1 day'));
		$range_date = array();
		$period = new DatePeriod(
			 new DateTime($start_date),
			 new DateInterval('P1D'),
			 new DateTime($end_date)
		);
		foreach ($period as $key => $value) {
			//$range_date[] = strtotime($value->format('Y-m-d'));      
			$range_date[] = $value->format('Y-m-d');      
		}
		foreach($range_date as $oneDate){
			$ym = $this->getDateCreateFormat($oneDate,"Ym");
			if(!in_array($ym,$lstYm)){
				$lstYm[] = $ym;
			}
		}
		return $lstYm;
	}
	function convertTextToTime($str, $time="00:00:00"){
		if(empty($str)) return 0;
		$str = preg_replace('/\s+/', '', $str);
		$time = preg_replace('/\s+/', '', $time);
		$tmp = @explode('/', $str);
		return strtotime($tmp[1].'/'.$tmp[0].'/'.$tmp[2]." {$time}");
	}
	function converTimeToText($date) {
		$str_in = array ("Jan", "Feb", "Mar", "Apr", "Fri"," Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		$str_out = array ("January", "February", "March", "April", 'May', "June", "July", " August", " September", " October", " November", " December");
		$time  = gmdate("d M, Y", $date + 7*3600);
		$time  = str_replace( $str_in, $str_out, $time);
		return $time;
	}
	function converTextToText($date) {
		$time  = date("d M Y", $date + 7*3600);
		return $time;
	}
	function convertTimeToText($str, $IsFormatDateFull = false, $paid="/"){
		if($IsFormatDateFull)
			return date('d'.$paid.'m'.$paid.'Y H:i', $str);
		return date('d'.$paid.'m'.$paid.'Y',$str);
	}
	function convertTimeToTextFormat($str, $format="m/d/Y"){
		return date($format, $str);
	}
	function convertTimeToISOString($str){
		return date('Y-m-d\TH:i', $str);
	}
	function checkInArray($haystack, $needle){
		if(empty($haystack) || $needle=='') { return 0;}
		if(!is_array($haystack)) {
			$haystack = explode(',',$haystack);
		}
		if(!in_array($needle,$haystack)) {
			return 0;
		}
		return 1;
	}
	function checkContainer($haystack, $needle, $paid='|'){
		if($paid == '|') {
			return $this->checkContainer2($haystack, $needle);
		} else {
			$pos = strpos($haystack, $needle);
			if($pos === false) {
				return 0;
			}else {
				return 1;
			}
		}
	}
	function checkContainer2($haystack,$needle){
		$pos = strpos($haystack,'|'.$needle.'|');
		if($pos === false) {
			return 0;
		}else {
			return 1;
		}
	}
	function addslash($doc) {
		return addslashes($doc);
	}
	function stripslash($doc) {
		return stripslashes($doc);
	}
	function convertToNormal($doc) {
		$str = $this->addslash(html_entity_decode($doc));
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		$str = preg_replace("/( )/", ' ', $str);
		$str = $this->stripslash($str);
		return $str;
	}
	function getPageTitle($pval,$clsTable){
		global $core;
		$clsMeta = new Meta();
		$clsClassTable = new $clsTable();
		#
		$linkMeta = $clsClassTable->getLink($pval);
		$linkMeta = str_replace('http://'.$_SERVER['HTTP_HOST'],'',$linkMeta);
		$allConfig = $clsMeta->getAll("config_link='$linkMeta'");
		$one = $clsMeta->getValue($linkMeta);
		$meta_id = $allConfig[0]['meta_id'];
		if($meta_id!=''&& $one['config_value_title']!=''){
			return $one['config_value_title'];
		}
		$title = $clsClassTable->getTitle($pval);
		return $title.' | '.PAGE_NAME;
	}
	function getPageDescription($pval,$clsTable){
		global $core;
		$clsMeta = new Meta();
		$clsClassTable = new $clsTable();
		#
		$linkMeta = $clsClassTable->getLink($pval);
		$linkMeta = str_replace('http://'.$_SERVER['HTTP_HOST'],'',$linkMeta);
		$allConfig = $clsMeta->getAll("config_link='$linkMeta'");
		$one = $clsMeta->getValue($linkMeta);
		$meta_id = $allConfig[0]['meta_id'];
		if($meta_id!=''&& $one['config_value_intro']!=''){
			return $one['config_value_intro'];
		}
		#
		$ret = $this->truncateWord($clsClassTable->getStripIntro($pval),80);
		$ret = str_replace('"','',$ret);
		return strip_tags($ret); 
	}
	function getPageKeyword($pval,$clsTable){
		global $_LANG_ID;
		$clsMeta = new Meta();
		$clsClassTable = new $clsTable();
		#
		$linkMeta = $clsClassTable->getLink($pval);
		$linkMeta = str_replace('http://'.$_SERVER['HTTP_HOST'],'',$linkMeta);
		$allConfig = $clsMeta->getAll("config_link='$linkMeta'");
		$one = $clsMeta->getValue($linkMeta);
		$meta_id = $allConfig[0]['meta_id'];
		if($meta_id!=''&& $one['config_value_keyword']!=''){
			return $one['config_value_keyword'];
		}
		$ret = $this->getPageTitle($pval,$clsTable);
		$ret = str_replace(' ',',',$ret);
		return $ret;
	}
	function getListCategory($type){
		$clsCategory = new Category();
		return $clsCategory->getAll("is_trash=0 and _type='$type' order by order_no asc");
	}
	function checkTourHaveCategoryPriceOption($tour_id,$customer_type_id){
		$clsTourPriceCustomerType = new TourPriceCustomerType();
		return $clsTourPriceCustomerType->countItem("tour_id='".$tour_id."' and customer_type_id='".$customer_type_id."' and is_trash='0'"); 
	}
	function checkTourHaveCategoryPriceOptionAge($tour_id,$age_type_id){
		$clsTourPriceAgeType = new TourPriceAgeType();
		return $clsTourPriceAgeType->countItem("tour_id='".$tour_id."' and age_type_id='".$age_type_id."' and is_trash='0'"); 
	}
	function loadYear($year){
		$html = '<option value="0">Nam</option>';
		for($i=0;$i<2;$i++){
			$html .= '<option value="'.($i+date('Y',time())).'" '.($i+date('Y',time())==$year?'selected="selected"':'').'>'.($i+date('Y',time())).'</option>';
		}
		#
		return($html); 
	}
	function getMonthOfYear($today){
		global $core,$_lang;
		$m = date('m',$today);
		if($m=='1') return $core->get_Lang('January');
		if($m=='2') return $core->get_Lang('February');
		if($m=='3') return $core->get_Lang('March');
		if($m=='4') return $core->get_Lang('April');
		if($m=='5') return $core->get_Lang('May');
		if($m=='6') return $core->get_Lang('June');
		if($m=='7') return $core->get_Lang('July');
		if($m=='8') return $core->get_Lang('August');
		if($m=='9') return $core->get_Lang('September');
		if($m=='10') return $core->get_Lang('October');
		if($m=='11') return $core->get_Lang('November');
		if($m=='12') return $core->get_Lang('December');
		return '';
	}
	function loadMonth($year,$month){
		$html = '<option value="0">Tháng</option>';
		for($i=1;$i<13;$i++){ 
			$html .= '<option value="'.$i.'" '.($i==$month?'selected="selected"':'').'>T'.$i.'</option>';
		}
		#
		return($html); 
	}
	function loadDay($year,$month,$day){
		$numberDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		$year_current = date('Y',time());
		$month_current = date('m',time());
		$day_current = date('d',time());
		#
		$html = '<option value="0">Ngày</option>';
		for($i=0;$i<$numberDay;$i++){
			$html .= '<option value="'.($i+1).'" '.(($i+1)==$day?'selected="selected"':'').'>'.($i+1).'</option>';
		}
		#
		return($html); 
	}
	function loadHour($hour){
		$html = '<option value="00">Giờ</option>';
		for($i=0;$i<23;$i++){ 
			$tmp = $i<10?'0'.$i:$i;
			$html .= '<option value="'.$tmp.'" '.($tmp==$hour?'selected="selected"':'').'>'.$tmp.'</option>';
		}
		#
		return($html); 
	}
	function loadMinute($min){
		$html = '<option value="00">Phút</option>';
		for($i=0;$i<12;$i++){
			$n =$i*5;
			$tmp = $n<10?'0'.$n:$n;
			$html .= '<option value="'.$tmp.'" '.($tmp==$min?'selected="selected"':'').'>'.$tmp.'</option>';
		}
		#
		return($html); 
	}
	function getListLangAdmin(){
		$customClsArray = array();
		if (is_dir($_SERVER['DOCUMENT_ROOT'].'/admin/lang')){
			if ($dh = opendir($_SERVER['DOCUMENT_ROOT'].'/admin/lang')) {
				while (($file = readdir($dh)) !== false) {
					if (substr($file, -3)=='php')
					array_push($customClsArray, str_replace('.php','',$file));
				}
				closedir($dh);
			}	
		}
		return $customClsArray;
	}
	function getListLang(){
		$customClsArray = array();
		if (is_dir(ABSPATH.'/lang')){
			if ($dh = opendir(ABSPATH.'/lang')) {
				while (($file = readdir($dh)) !== false) {
					if (substr($file, -3)=='php')
					array_push($customClsArray, str_replace('.php','',$file));
				}
				closedir($dh);
			}	
		}
		return $customClsArray;
	}
	function getRealIP(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	/* COUNT */
	function getTotalRevenue(){
		$clsOrder = new Order();
		$cond = "is_trash=0 and is_done='1' and vpc_status='1'";
		$total = $clsOrder->sumItem("vpc_total_money", $cond);
		return $this->formatPrice($total);
	}
	function getTotalPending(){
		$clsOrder = new Order();
		$cond = "is_trash=0 and is_done='1' and vpc_status<>'1'";
		$total = $clsOrder->sumItem("vpc_total_money", $cond);
		return $this->formatPrice($total);
	}
	function countTotal($clsTable,$cond='1=1') {
		$clsClassTable = new $clsTable();
		return $clsClassTable->countItem($cond);
	}
	function countTotalFeedback($feedback_type,$is_process='') {
		$clsFeedback = new Feedback();
		$cond = "1=1 and feedback_type = '$feedback_type' and is_process = '$is_process'";
		return $clsFeedback->countItem($cond);
	}
	function countTotalBooking($type,$status='') {
		$clsBooking = new Booking();
		$cond = "1=1 and type = '$type' and status = '$status'";
		return $clsBooking->countItem($cond);
	}
	function countTotalSlide($mod_page,$act_page,$target_id) {
		$clsClassTable = new Slide();
		return $clsClassTable->countItem("mod_page = '$mod_page' and act_page = '$act_page' and target_id = '$target_id'");
	}
	function getExName($clsTable) {
		global $core;
		$clsClassTable = new $clsTable;
		$res = $clsClassTable->getAll("is_trash=0 order by reg_date asc limit 0,1");
		if(!empty($res[0][$clsClassTable->pkey])) {
			return $clsClassTable->getTitle($res[0][$clsClassTable->pkey]);
		} else {
			$core->get_Lang('entertitlehere');
		}
	}
	function getLink($str, $ful_link=true) {
		global $clsConfiguration, $_LANG_ID, $extLang;
		switch($str){
			case 'exclusive';
				return '/doc-quyen/';
			case 'stock';
				return '/bang-hang/';
			case 'marketing':
				return $extLang.'/marketing.html';
				break;
			case 'cash_book':
				return $extLang.'/cash-book.html';
				break;
			case 'billing':
				return $extLang.'/giao-dich.html';
				break;
			case 'salary':
				return $extLang.'/salary.html';
				break;
			case 'staff':
				return $extLang.'/staff.html';
				break;
			case 'project':
				return $extLang.'/du-an'.($ful_link?'/':'');
				break;
			case 'faqs':
				return $extLang.'/faqs'.($ful_link?'.html':'');
				break;
			case 'signin':
				return $extLang.'/dang-nhap.html';
				break;
			case 'signup':
				return $extLang.'/dang-ky.html';
				break;
			case 'logout':
				return $extLang.'/logout.html';
				break;
			case 'profile':
				return $extLang.'/profile.html';
				break;
			case 'search':
				return $extLang.'/search.html';
				break;
			case 'finance':
				return $extLang.'/tai-chinh/';
				break;
			case 'fund':
				return $extLang.'/fund.html';
				break;
			case 'fund_transfer':
				return $extLang.'/fund/transfer.html';
				break;
			case 'issue':
				return $extLang.'/issue.html';
				break;		
			case 'log-sale':
				return $extLang.'/logs-sale.html';
				break;		
			case 'course':
				return $extLang.'/lich-dao-tao.html';
				break;		
			case 'loyalty':
				return $extLang.'/loyalty.html';
				break;		
			case 'report_user':
				return $extLang.'/report/report_user.html';
				break;		
			case 'report_ns_club':
				return $extLang.'/bao-cao-clbns.html';
				break;	
			case 'training':
				return $extLang.'/khoa-hoc-dao-tao.html';
				break;	
			case 'inspire':
				return $extLang.'/truyen-cam-hung.html';
				break;		
			case 'request_ptg':
				return $extLang.'/yeu-cau-phieu-tinh-gia.html';
				break;	
			case 'news':
				return $extLang.'/ban-tin.html';
				break;	
			case 'document':
				return $extLang.'/van-ban-he-thong.html';
				break;	
			case 'favourite':
				return $extLang.'/my-favourite/';
				break;	
			case 'agency':
				return $extLang.'/dai-ly.html';
				break;	
			case 'quiz':
				return $extLang.'/trac-nghiem'.($ful_link?'.html':'/');
				break;
			case 'data_central':
				return $extLang.'/data-central'.($ful_link?'.html':'/');
				break;
			case 'campaign_data':
				return $extLang.'/chien-dich-khach-hang'.($ful_link?'.html':'/');
				break;	
			case 'sop':
				return $extLang.'/chuyen-nhuong'.($ful_link?'.html':'/');
				break;	
			case 'operation_fee':
				return $extLang.'/chi-van-hanh.html';
				break;	
			case 'chart_operation_fee':
				return $extLang.'/chi-van-hanh/thong-ke.html';
				break;
			case 'crawl_highfloor':
				return $extLang.'/cap-nhat-cao-tang.html';
				break;
			case 'crawl_lowfloor':
				return $extLang.'/cap-nhat-thap-tang.html';
				break;
			case 'report_crawl':
				return $extLang.'/thong-ke-cap-nhat-bang-hang.html';
				break;
			case 'template':
				return $extLang.'/mau-chuc-mung.html';
				break;
			case 'map':
				return $extLang.'/map.html';
				break;
			case 'project_meta':
				return $extLang.'/kho-tai-lieu.html';
				break;
			case 'booking':
				return $extLang.'/booking.html';
				break;
			case 'edit_MOC':
				return DOMAIN_URL.'/MOC/profile/me/';
				break;
			case 'quote':
				return $extLang.'/trich-dan.html';
				break;
			case 'notification':
				return $extLang.'/notification.html';
				break;
			case 'notify':
				return $extLang.'/thong-bao-noi-bo.html';
				break;
			case 'tool':
				return $extLang.'/tool.html';
				break;
			case 'incentive':
				return $extLang.'/chuong-trinh-thi-dua.html';
				break;
			case 'request_package':
				return $extLang.'/yeu-cau-mua-goi-khach-hang.html';
				break;
			case 'report_share':
				return $extLang.'/report/work.html';
				break;
			case 'crm':
				return $extLang.'/crm/';
				break;
			case 'my_booking':
				return $extLang.'/my-booking.html';
				break;
			case 'dashboard_sale':
				return $extLang.'/dashboard/';
				break;
			case 'online_MOC':
				return $extLang.'/online-myoceancity.html';
				break;
			case 'certification':
				return $extLang.'/chung-nhan-dai-ly.html';
				break;
			case 'worldcup':
				return $extLang.'/wc2026';
				break;
			case 'report-checkin':
				return $extLang.'/tong-quan-checkin.html';
				break;
		}
	}
	function getModIntro($mod) {
		global $clsConfiguration, $_LANG_ID;
		return html_entity_decode($clsConfiguration->getValue('site_'.$mod.'_intro_'.$_LANG_ID));
	}
	function getListTitle(){
		global $core;
		$lstTitle = array();
		$lstTitle['Mr'] = $core->get_Lang('Mr');
		$lstTitle['Mrs'] = $core->get_Lang('Mrs');
		$lstTitle['Ms'] = $core->get_Lang('Ms');
		$lstTitle['Miss'] = $core->get_Lang('Miss');
		$lstTitle['Dr'] = $core->get_Lang('Dr');
		return $lstTitle;
	}
	function formatTime($time){
		return date('H:i', $time);
	}
	function formatTimeDate($time){
		global $core,$_LANG_ID;
		if($_LANG_ID=='vn'){
			return (!empty($time)) ? date('d/m/Y',$time) : '';
		}else{
			return (!empty($time)) ? date('m/d/Y',$time) : '';
		}
	}
	function getNameTitle($val){
		$lstTitle = $this->getListTitle();
		return $lstTitle[$val];
	}
	function makeSelectTitle($selected=''){
		global $core;
		#
		$lstTitle = $this->getListTitle();
		$html = '';
		foreach($lstTitle as $k=>$v){
			$html .= '<option value="'.$k.'" '.($selected==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		return $html; die();
	}
	function getListTime() {
		$options = array();
		foreach (range(0,23) as $fullhour) {
			$parthour = $fullhour > 12 ? $fullhour - 12 : $fullhour;
			$sufix = $fullhour > 11 ? " pm" : " am";
			$options["$fullhour:00"] = $parthour.":00".$sufix;
			$options["$fullhour:30"] = $parthour.":30".$sufix;
		}
		return $options;
	}
	function getNameTime($val){
		$lstTime = $this->getListTime();
		return $lstTime[$val];
	}
	function makeSelectTime($selected=''){
		$lstTime = $this->getListTime();
		#
		$html = '';
		foreach($lstTime as $k=>$v){
			$html .= '<option value="'.$k.'" '.($selected==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		return $html;
	}
	function makeSelectTimeTakeLeave($selected='',$tp='start'){//end
		$html = '';
		if($tp=='start'){
			$html .= '<option value="09:00 AM" '.($selected=='09:00 AM' ? 'selected="selected"':'').'>09:00 AM</option>';	
		}
		$html .= '<option value="12:00 PM" '.($selected=='12:00 PM' ? 'selected="selected"':'').'>12:00 PM</option>';
		if($tp=='end'){
			$html .= '<option value="06:00 PM" '.($selected=='06:00 PM' ? 'selected="selected"':'').'>06:00 PM</option>';	
		}
		return $html;
	}
	function check_is_ajax($script) {
		$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		if(!$isAjax) {

			trigger_error('Access denied - not an AJAX request...' . ' (' . $script . ')', E_USER_ERROR);
		}
	}
	function generateToken($form) {
       // generate a token from an unique value
    	$token = md5(uniqid(microtime(),true));  
    	// Write the generated token to the session variable to check it against the hidden field when the form is sent
    	vnSessionSetVar($form.'_token',$token); 
    	return $token;
	}
	function verifyToken($form) {
		// check if a session is started and a token is transmitted, if not return an error
		if(!vnSessionExist($form.'_token')){
			return false;
		}
		// check if the form is sent with token in it
		if(!isset($_POST['token'])) {
			return false;
		}
		// compare the tokens against each other if they are still the same
		if (vnSessionGetVar($form.'_token') !== $_POST['token']) {
			return false;
		}
		return true;
	}
	function getAlias($type){
		if($type=='frontpage') return 'trang-chu';
		if($type=='catalog') return 'san-pham';
		if($type=='newsall') return 'tin-tuc';
		if($type=='search') return 'tim-kiem';
		if($type=='faqs') return 'faqs';
		return '';
	}
	function getSelectByPropertyTypeTitle($property_type, $selected=0, $title='') {
        global $core, $adminid, $clsISO, $clsConfiguration;
        $clsProperty=new Property();
		$field = "{$clsProperty->pkey},title";
		$cond = "`is_trash`=0 AND `property_type`='{$property_type}'";
		$html='<option value="0">'.$title.'</option>';
        $list_roots = $clsProperty->getAll("{$cond} AND `parent_id`=0 ORDER BY order_no ASC",$field);
        if(!empty($list_roots)){
            foreach($list_roots as $key => $val){
				$p1 = $val[$clsProperty->pkey];
                if(is_array($selected) && !empty($selected)){
					$sltc = (in_array($p1, $selected))?' selected':'';
				} else {
					$sltc = ($selected==$p1)?' selected':'';
				}
                $html.= '<option value="'.$p1.'"'.$sltc.'>'.$clsProperty->getTitle($p1).'</option>';
				$list_childs_1 = $clsProperty->getAll("{$cond} AND `parent_id`='{$p1}' ORDER BY `order_no` ASC",$field);
				if(!empty($list_childs_1)){
					foreach($list_childs_1 as $okey => $item_1){
						$p2 = $item_1[$clsProperty->pkey];
						if(is_array($selected) && !empty($selected)){
							$sltc = (in_array($p2, $selected))?' selected':'';
						} else {
							$sltc = ($selected==$p2)?' selected':'';
						}
						$html.= '<option value="'.$p2.'"'.$sltc.'>|--'.$clsProperty->getTitle($p2, $item_1).'</option>';
						$list_childs_2 = $clsProperty->getAll("{$cond} and parent_id='{$p2}' order by order_no ASC", $field);
						if(!empty($list_childs_2)){
							foreach($list_childs_2 as $item_2){
								$p3 = $item_2[$clsProperty->pkey];
								if(is_array($selected) && !empty($selected)){
									$sltc = (in_array($p3, $selected))?' selected':'';
								} else {
									$sltc = ($selected==$p3)?' selected':'';
								}
								$html.='<option value="'.$p3.'"'.$sltc.'>|--|--'.$clsProperty->getTitle($p3,$item_2).'</option>';
								$list_childs_3 = $clsProperty->getAll("{$cond} and parent_id='{$p3}' order by order_no ASC", $field);
								if(!empty($list_childs_3)){
									foreach($list_childs_3 as $item_3){
										$p4 = $item_3[$clsProperty->pkey];
										if(is_array($selected) && !empty($selected)){
											$sltc = (in_array($p4, $selected))?' selected':'';
										} else {
											$sltc = ($selected==$p4)?' selected':'';
										}
										$html.='<option value="'.$p4.'"'.$sltc.'>|--|--|--'.$clsProperty->getTitle($p4,$item_3).'</option>';
										$list_childs_4 = $clsProperty->getAll("{$cond} and `parent_id`='{$p4}' ORDER BY `order_no` ASC", $field);
										if(!empty($list_childs_4)){
											foreach($list_childs_4 as $item_4){
												$p5 = $item_4[$clsProperty->pkey];
												if(is_array($selected) && !empty($selected)){
													$sltc = (in_array($p5, $selected))?' selected':'';
												} else {
													$sltc = ($selected==$p5)?' selected':'';
												}
												$html.='<option value="'.$p5.'"'.$sltc.'>|--|--|--|--'.$clsProperty->getTitle($p5, $item_4).'</option>';
											}
											unset($list_childs_3);
										}
									}
									unset($list_childs_3);
								}
							}
							unset($list_childs_2);
						}
					}
					unset($list_childs_1);
				}
            }
            unset($list_roots);
        }
        return $html;
    }
    function getSelectByPropertyTypeNotTitle($property_type, $property_id=0) {
        global $core,$_frontIsLoggedin_user_id,$clsISO;
        $clsProperty=new Property();
		
		$html = '';
		$listProperty=$clsProperty->getAll("is_trash=0 and parent_id=0 and property_type='{$property_type}' order by order_no ASC",$clsProperty->pkey);
        if(!empty($listProperty)){
            foreach($listProperty as $property){
                $sltc=($property_id==$property[$clsProperty->pkey])?' selected':'';
                $html.='<option value="'.$property[$clsProperty->pkey].'"'.$sltc.'>'.$clsProperty->getTitle($property[$clsProperty->pkey]).'</option>';
				$lstChild=$clsProperty->getAll("is_trash=0 and property_type='{$property_type}' and parent_id='".$property[$clsProperty->pkey]."' order by order_no ASC",$clsProperty->pkey);
				if(!empty($lstChild)){
					foreach($lstChild as $child){
						$sltc=($property_id==$child[$clsProperty->pkey])?' selected':'';
						$html.='<option value="'.$child[$clsProperty->pkey].'"'.$sltc.'>|--'.$clsProperty->getTitle($child[$clsProperty->pkey]).'</option>';
						$lstChildSub = $clsProperty->getAll("is_trash=0 and property_type='{$property_type}' and parent_id='".$child[$clsProperty->pkey]."' order by order_no ASC",$clsProperty->pkey);
						if(!empty($lstChildSub)){
							foreach($lstChildSub as $childsub){
								$sltc=($property_id==$childsub[$clsProperty->pkey])?' selected':'';
								$html.='<option value="'.$childsub[$clsProperty->pkey].'"'.$sltc.'>|--|-- '.$clsProperty->getTitle($childsub[$clsProperty->pkey]).'</option>';
								$lstChildSubSub = $clsProperty->getAll("is_trash=0 and property_type='{$property_type}' and parent_id='".$childsub[$clsProperty->pkey]."' order by order_no ASC",$clsProperty->pkey);
								if(!empty($lstChildSubSub)){
									foreach($lstChildSubSub as $childsubsub){
										$sltc=($property_id==$childsubsub[$clsProperty->pkey])?' selected':'';
										$html.='<option value="'.$childsubsub[$clsProperty->pkey].'"'.$sltc.'>|--|--|--- '.$clsProperty->getTitle($childsubsub[$clsProperty->pkey]).'</option>';
									}
									unset($lstChildSubSub);
								}
							}
							unset($lstChildSub);
						}
					}
					unset($lstChild);
				}
            }
            unset($listProperty);
        }
        return $html;
    }
    function getSelectBySettingTypeTitle($setting_type, $selected=0, $title='') {
        global $core, $adminid, $clsISO, $clsConfiguration;
        $clsSetting=new Setting();
		$field = "{$clsSetting->pkey},title,code";
		$cond = "`is_trash`=0 and `_type`='{$setting_type}'";
		$html='<option value="">'.$title.'</option>';
        $listSetting = $clsSetting->getAll("{$cond} and parent_id=0 order by order_no ASC",$field);
        if(!empty($listSetting)){
            foreach($listSetting as $key => $val){				
				$code = ($setting_type == "_ACCOUNT") ? '['.$val["code"].']' : "";
				$p1 = $val[$clsSetting->pkey];
                if(is_array($selected) && !empty($selected)){
					$sltc = (in_array($p1, $selected))?' selected':'';
				} else {
					$sltc = ($selected==$p1)?' selected':'';
				}
                $html.= '<option value="'.$p1.'"'.$sltc.'>'.$code.$clsSetting->getTitle($p1).'</option>';
				$lstChild = $clsSetting->getAll("{$cond} and parent_id='{$p1}' order by order_no ASC",$field);
				if(!empty($lstChild)){
					foreach($lstChild as $okey => $child){
						$code_child = ($setting_type == "_ACCOUNT") ? '['.$child["code"].']' : "";
						$p2 = $child[$clsSetting->pkey];
						if(is_array($selected) && !empty($selected)){
							$sltc = (in_array($p2, $selected))?' selected':'';
						} else {
							$sltc = ($selected==$p2)?' selected':'';
						}
						$html.= '<option value="'.$p2.'"'.$sltc.'>|---'.$code_child.$clsSetting->getTitle($p2, $child).'</option>';
						$lstChildSub = $clsSetting->getAll("{$cond} and parent_id='{$p2}' order by order_no ASC", $field);
						if(!empty($lstChildSub)){
							foreach($lstChildSub as $childsub){
								$code_childSub = ($setting_type == "_ACCOUNT") ? '['.$childsub["code"].']' : "";
								$p3 = $childsub[$clsSetting->pkey];
								if(is_array($selected) && !empty($selected)){
									$sltc = (in_array($p3, $selected))?' selected':'';
								} else {
									$sltc = ($selected==$p3)?' selected':'';
								}
								$html.='<option value="'.$p3.'"'.$sltc.'>|---|---'.$code_childSub.$clsSetting->getTitle($p3,$childsub).'</option>';
							}
							unset($lstChildSub);
						}
					}
					unset($lstChild);
				}
            }
            unset($listSetting);
        }
        return $html;
    }
    function getSelectBySettingTypeNotTitle($setting_type, $setting_id=0) {
        global $core,$_frontIsLoggedin_user_id,$clsISO;
        $clsSetting=new Setting();
		
		$html = '';
		$listSetting=$clsSetting->getAll("is_trash=0 and parent_id=0 and _type='{$setting_type}' order by order_no ASC",$clsSetting->pkey);
        if(!empty($listSetting)){
            foreach($listSetting as $setting){
                $sltc=($setting_id==$setting[$clsSetting->pkey])?' selected':'';
                $html.='<option value="'.$setting[$clsSetting->pkey].'"'.$sltc.'>'.$clsSetting->getTitle($setting[$clsSetting->pkey]).'</option>';
				$lstChild=$clsSetting->getAll("is_trash=0 and _type='{$setting_type}' and parent_id='".$setting[$clsSetting->pkey]."' order by order_no ASC",$clsSetting->pkey);
				if(!empty($lstChild)){
					foreach($lstChild as $child){
						$sltc=($setting_id==$child[$clsSetting->pkey])?' selected':'';
						$html.='<option value="'.$child[$clsSetting->pkey].'"'.$sltc.'>|--'.$clsSetting->getTitle($child[$clsSetting->pkey]).'</option>';
						$lstChildSub = $clsSetting->getAll("is_trash=0 and _type='{$setting_type}' and parent_id='".$child[$clsSetting->pkey]."' order by order_no ASC",$clsSetting->pkey);
						if(!empty($lstChildSub)){
							foreach($lstChildSub as $childsub){
								$sltc=($setting_id==$childsub[$clsSetting->pkey])?' selected':'';
								$html.='<option value="'.$childsub[$clsSetting->pkey].'"'.$sltc.'>|--|-- '.$clsSetting->getTitle($childsub[$clsSetting->pkey]).'</option>';
								$lstChildSubSub = $clsSetting->getAll("is_trash=0 and _type='{$setting_type}' and parent_id='".$childsub[$clsSetting->pkey]."' order by order_no ASC",$clsSetting->pkey);
								if(!empty($lstChildSubSub)){
									foreach($lstChildSubSub as $childsubsub){
										$sltc=($setting_id==$childsubsub[$clsSetting->pkey])?' selected':'';
										$html.='<option value="'.$childsubsub[$clsSetting->pkey].'"'.$sltc.'>|--|--|--- '.$clsSetting->getTitle($childsubsub[$clsSetting->pkey]).'</option>';
									}
									unset($lstChildSubSub);
								}
							}
							unset($lstChildSub);
						}
					}
					unset($lstChild);
				}
            }
            unset($listSetting);
        }
        return $html;
    }
	function getEmailNotifier($holderG){
		global $core, $dbconn, $clsISO, $clsConfiguration;
		$field_name = 'email_'.$holderG;
		$field_value = $clsConfiguration->getValue($field_name);
		$tmp = !empty($field_value) ? @json_decode($field_value, true) : array();
		//$this->print_pre($field_value);
		$res = array();
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$res[] = array(
					'status' => $val['status'],
					'email_name' => $val['email_name'],
					'email_address' => $val['email_address']
				);
			}
			unset($tmp);
		}
		return $res;
	}
	function checkGoogleReCAPTCHA(){
		global $dbconn, $core;
		//l?y d? li?u du?c post l�n
    	$site_key_post = $_POST['g-recaptcha-response'];
		//l?y IP c?a khach
		$remoteip = $this->getRealIP();

		//t?o link k?t n?i
		$api_url = reCAPTCHA_APIURL.'?secret='.reCAPTCHA_SECRET.'&response='.$site_key_post.'&remoteip='.$remoteip;
		//l?y k?t qu? tr? v? t? Google
		$response = FALSE;
		if(function_exists('file_get_contents')){
			$response = file_get_contents($api_url);
		}
		if($response===FALSE && function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_URL, $api_url );
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
			$response = curl_exec( $ch );
			curl_close($ch);
		}
		// Json
		if($response !== FALSE){
			$response = json_decode($response);
			if(!isset($response->success)) return false;
			if($response->success===true)
				return true;
			return false;
		}
		return false;
	}
	/**
	 * ger_origenal_url
	 * @param string $url
	 * @return string
	 */
	function get_origenal_url($url) {
		stream_context_set_default(array(
			'http' => array(
				'ignore_errors' => true,
				'method' => 'HEAD',
				'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
			)
		));
		$headers = get_headers($url, 1);
		if ($headers !== false && (isset($headers['location']) || isset($headers['Location']))) {
			$location = (isset($headers['location']))? $headers['location'] : $headers['Location'];
			return is_array($location) ? array_pop($location) : $location;
		}
		return $url;
	}
	function scraper($url) {
        $url_parsed = @parse_url($url);
        if(!isset($url_parsed["scheme"]) ) {
            $url = "http://".$url;
        }
        $url = $this->ger_origenal_url($url);
        $advanced = true;
        if($advanced) {
            require_once(DIR_INCLUDES.'/Embed/v3/autoloader.php');
            $dispatcher = new Embed\Http\CurlDispatcher([
                CURLOPT_FOLLOWLOCATION => false,
            ]);
            $embed = Embed\Embed::create($url, null, $dispatcher);
			var_dump($embed); die();
        } else {
            require_once(DIR_INCLUDES.'/Embed/v1/autoloader.php');
            $config = [
                'image' => [ 'class' => 'Embed\\ImageInfo\\Sngine' ]
            ];
            $embed = Embed\Embed::create($url, $config);
        }
        if($embed) {
            $return = array();
            $return['source_url'] = $url;
            $return['source_title'] = $embed->title;
            $return['source_text'] = $embed->description;
            $return['source_type'] = $embed->type;
            if($return['source_type'] == "link") {
                $return['source_host'] = $url_parsed['host'];
                $return['source_thumbnail'] = $embed->image;
            } else {
                $return['source_html'] = $embed->code;
				$return['source_thumbnail'] = $embed->image;
                $return['source_provider'] = $embed->providerName;                
            }
            return $return;
        } else {
            return false;
        }
    }
	function checkDEV(){
		global $core, $dbconn, $profile_id, $oneProfile;
		if(in_array($profile_id, [9,7,289,1107,1124]))
			return 1;
		return 0;
	}
	function checkSupper(){
		global $core, $dbconn, $profile_id, $oneProfile;
		if($profile_id==_PROFILE_TECH_ID) return 1;
		if($profile_id==_PROFILE_ADMIN_ID) return 1;
		$role_id = $oneProfile['role_id']; // _ROLE_GD_PROJECT
		if(in_array($role_id, array(_ROLE_GD_MANAGER, _ROLE_PGD_MANAGER)))
			return 1;
		return 0;
	}
	function checkPermission($key=""){
		global $core, $dbconn, $profile_id, $oneProfile;
		$clsProperty = new Property();
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		if($key=='access_course') return 1;
		if(in_array($profile_id, _PROFILE_SUPPER_ID)) return 1;
		if(in_array($profile_id, _PROFILE_ROOT_ID)) return 1;
		$permiss_mod = $oneProfile['permiss_mod'];
		$department_id = $oneProfile["department_id"];
		$oneRole = $clsProperty->getArraySearchByKey("_ROLE",$role_id);
		$permiss_role = $core->get_field($oneRole["more_information"],"permiss_mod");
		if(!empty($permiss_role)){
			if(isset($permiss_role[$key]) && (int) $permiss_role[$key] == 1) return 1;
		}
		if(!empty($permiss_mod)){
			if(isset($permiss_mod[$key]) && (int) $permiss_mod[$key] == 1)
				return 1;
			return 0;
		} else {
			return 0;
		}
	}
	function check_view_resource($stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE, 
		$block_id=0, $oneBlock = array()){
		global $core, $dbconn, $profile_id, $oneProfile;
		$is_permiss = false; $key = 'view_highrise_resource';
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
			$is_permiss = true;
			$key = 'view_lowrise_resource';
		} else {
			$more_information = $oneBlock['more_information'];
			$more_information = $this->to_array_json($more_information);
			$project_manager_id = (int) $core->get_field($more_information, 'project_manager', 0); 
			if($project_manager_id == $profile_id) $is_permiss = true;
		}
		$permiss_mod = $oneProfile['permiss_mod'];
		if(!empty($permiss_mod) && $is_permiss == true){
			if(isset($permiss_mod[$key]) && (int) $permiss_mod[$key] == 1)
				return 1;
			return 0;
		} else {
			return 0;
		}
	}
	function checkHeadSale($role_id){
		if(in_array($role_id, array(_ROLE_GD_PROJECT, _ROLE_GD_SALE, _ROLE_REGIONAL_DIRECTOR_ID)))
			return 1;
		return 0;
	}
	function checkPermissHrad(){
		global $core, $dbconn, $profile_id, $oneProfile;
		if($profile_id==_PROFILE_HRAD_ID)
			return 1;
		return 0;
	}
	function checkPermissNs(){
		global $core, $dbconn, $profile_id, $oneProfile;
		$department_id = $oneProfile['department_id'];
		// if($profile_id==9) return 1;
		if(in_array($profile_id, _PROFILE_SUPPER_ID)) return 1;
		if($department_id == _DEPARTMENT_DIRECTOR_ID) return 1;
		return 0;
	}
	function checkPermissMs(){
		global $core, $dbconn, $profile_id, $oneProfile;
		$department_id = $oneProfile['department_id'];
		// if($profile_id==_PROFILE_TECH_ID) return 1;
		if(in_array($profile_id, _PROFILE_SUPPER_ID)) return 1;
		if(in_array($department_id, array(_DEPARTMENT_DIRECTOR_ID, _DEPARTMENT_BO_ID)))
			return 1;
		return 0;
	}
	function checkPermissStock(){
		global $core, $dbconn, $profile_id, $oneProfile;
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		if($profile_id==_PROFILE_TECH_ID) return 1;
		if($department_id == _DEPARTMENT_BO_ID) return 1;
		if(in_array($role_id, array(_ROLE_GD_MANAGER, _ROLE_PGD_MANAGER, _ROLE_GD_PROJECT)))
			return 1;
		return 0;
	}
	function checkPermissionAX(){
		global $core, $profile_id, $oneProfile;
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		if($profile_id==_PROFILE_ACC_ID) return 1;
		if($profile_id==_PROFILE_TECH_ID) return 1;
		if($profile_id==_PROFILE_ADMIN_ID) return 1;
		if(in_array($role_id, array(_ROLE_GD_MANAGER, _ROLE_PGD_MANAGER, _ROLE_GD_PROJECT)))
			return 1;
	}
	function checkPermissionBX(){
		global $core, $profile_id, $oneProfile;
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		if($profile_id==_PROFILE_ACC_ID) return 1;
		if($profile_id==_PROFILE_TECH_ID) return 1;
		if(in_array($role_id, array(_ROLE_GD_MANAGER, _ROLE_PGD_MANAGER)))
			return 1;
	}
	function checkPermissionGroup($group, $mod_page=""){
		global $core, $dbconn, $profile_id, $oneProfile,$header_configs,$clsConfiguration;
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		if($group=='DIRECTOR'){
			// Ban l�nh d?o: Ban L�, T?ng G�, Ph� TG�, Ch? t?ch H�QT
			$home_screen_director_roles = $clsConfiguration->getArray("home_screen_director_roles");
			if(in_array($role_id, $home_screen_director_roles))
				return 1;
			return 0;
		} else if($group=='SALE'){
			// Sale: Kinh doanh + Sale th? vi?c
			if(in_array($role_id, array(_ROLE_STAFF_SALE, _ROLE_TRIAL_SALE)))
				return 1;
			return 0;
		} else if($group == 'HEAD_SALE'){
			// Gi�m d?c kh?i (v�ng) + Tru?ng ph�ng Kinh doanh
			if($role_id == _ROLE_HEAD_SALE)
				return 1;
			return 0;
		} else if($group=='PROJECT_DIRECTOR'){
			// Gi�m d?c d? �n
			if(in_array($profile_id, array(_PROFILE_PVT_ID, _PROFILE_DDK_ID, _PROFILE_DDK_2_ID))) 
				return 1;
			if($role_id == _ROLE_GD_PROJECT) 
				return 1;
			return 0;
		} else if($group=='SALE_DIRECTOR'){
			// Gi�m d?c kinh doanh
			$home_screen_sale_roles = $clsConfiguration->getArray("home_screen_sale_roles");
			if(in_array($role_id, $home_screen_sale_roles))
				return 1;
			return 0;
		}  else if($group=='BUSINESS_AREA' || $group=='REGIONAL_DIRECTOR'){
			// Gi�m d?c kh?i (v�ng) + Tru?ng ph�ng Kinh doanh
			if($role_id == _ROLE_REGIONAL_DIRECTOR_ID)
				return 1;
			return 0;
		} else if($group=='SALE_DIRECTOR_ONLY'){
			if(in_array($profile_id, array(_PROFILE_PVT_ID, _PROFILE_DDK_ID, _PROFILE_DDK_2_ID))) 
				return 1;
			if($role_id == _ROLE_GD_SALE)
				return 1;
			return 0;
		} else if($group=='HR'){
			if(in_array($role_id,_ROLE_HR))
				return 1;
			return 0;
		} else if($group=='BO' && $group != 'ADMIN_PROJECT'){
			if(in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID)))
				return 1;
			return 0;
		} else if($group=='ADMIN_PROJECT'){
			$home_screen_admin_roles = $clsConfiguration->getArray("home_screen_admin_roles");
			if(in_array($role_id, $home_screen_admin_roles))
				return 1;
			return 0;
		} else if($group=='ACCOUNTANT'){
			$home_screen_accountant_roles = $clsConfiguration->getArray("home_screen_accountant_roles");
			if(in_array($role_id, $home_screen_accountant_roles))
				return 1;
			return 0;
		} else if($group=='MARKETING'){
			if(in_array($role_id, [_ROLE_GD_MARKETER, _ROLE_HEAD_FREE, _ROLE_SATFF_FREE]))
				return 1;
			return 0;
		} else if($group=='CTV'){
			if($department_id == _DEPARTMENT_CTV_ID)
				return 1;
			return 0;
		}
	}
	/**
	 * T�n block m�n h�nh trang ch? c?a ngu?i dang dang nh?p.
	 *
	 * Vai tr�/ph�ng ban c?a t?ng m�n h�nh l?y t? C?u h�nh h? th?ng (nh�m home_screen).
	 * � n�o chua c?u h�nh th� m�n h�nh d� l�i v? d�ng nh�m quy?n cu, n�n b?n deploy
	 * d?u ti�n kh�ng d?i h�nh vi so v?i chu?i elseif hardcode trong default.tpl.
	 *
	 * Th? t? x�t C? �?NH, kh�ng c?u h�nh du?c: m?t ngu?i c� th? kh?p nhi?u m�n h�nh,
	 * m�n h�nh d?ng tru?c th?ng � gi? nguy�n th? t? uu ti�n dang ch?y tr�n live.
	 *
	 * @return string T�n block; chu?i r?ng nghia l� d�ng m�n h�nh m?c d?nh c?a nh�n vi�n.
	 */
	function getHomeScreen(){
		global $oneProfile, $profile_id;
		$screens = array(
			'home_screen_sale'       => array('SALE_DIRECTOR', 'HEAD_SALE', 'BUSINESS_AREA'),
			'home_screen_director'   => array('DIRECTOR'),
			'home_screen_admin'      => array('ADMIN_PROJECT'),
			'home_screen_accountant' => array('ACCOUNTANT')
		);
		// Ngo?i l? theo t?ng ngu?i: uu ti�n m�n C?u h�nh h? th?ng, chua khai th� l?y fallback config.php.
		$bypass = $this->getHomeScreenIds('home_screen_bypass_profiles', _PROFILE_HOME_DEFAULT_SCREEN_ID);
		if(in_array((int) $profile_id, $bypass, true)){
			return '';
		}
		$role_id = (int) $oneProfile['role_id'];
		$department_id = (int) $oneProfile['department_id'];
		foreach($screens as $block => $groups){
			$roles = $this->getHomeScreenIds($block.'_roles');
			$departments = $this->getHomeScreenIds($block.'_departments');
			if(empty($roles) && empty($departments)){
				// M�n h�nh chua du?c c?u h�nh ? gi? nguy�n lu?t nh�m quy?n m?c d?nh.
				if($this->matchPermissionGroup($groups)){
					return $block;
				}
				continue;
			}
			if(in_array($role_id, $roles, true)){
				return $block;
			}
			if(in_array($department_id, $departments, true)){
				return $block;
			}
		}
		return '';
	}
	/**
	 *
	 * @return bool
	 */
	function screenSales(){
		return $this->getHomeScreen() !== '';
	}
	/**
	 *
	 * @return array
	 */
	function getHomeScreenIds($keyword, $default = array()){
		global $clsConfiguration;
		if(!is_object($clsConfiguration)){
			return $default;
		}
		$ids = $clsConfiguration->getArray($keyword);
		if(empty($ids)){
			return $default;
		}
		return array_map('intval', $ids);
	}
	/**
	 * Kh?p �t nh?t m?t nh�m quy?n trong danh s�ch.
	 * @return bool
	 */
	function matchPermissionGroup($groups){
		foreach($groups as $group){
			if((int) $this->checkPermissionGroup($group) === 1){
				return true;
			}
		}
		return false;
	}
	function checkSale(){
		global $core, $dbconn, $oneProfile, $profile_id;
		$department_id = $oneProfile['department_id'];
		$list_department_id = $oneProfile['list_department_id'];
		$department_arrs = $this->getArrayByTextSlash($list_department_id, ",", []);
		//if($profile_id == _PROFILE_TECH_ID) return 1;
		//if($department_id == _DEPARTMENT_DIRECTOR_ID) return 1;
		if(in_array(_DEPARTMENT_SALE_ID, $department_arrs))
			return 1;
		return 0;
	}
	function base64url_encode($data){
		$data .='FH';
	  	return rtrim(strtr(base64_encode($data),'+/','-_'), '=');
	}
	function base64url_decode($data){
	  return str_replace('FH','',base64_decode(strtr($data,'-_','+/').str_repeat('=',3-(3+strlen($data))%4)));
	}
	function encryptID($text){
		return base64_encode($text.'-FH');
	}
	function decryptID($text){
		return str_replace('-FH','',base64_decode($text));
	}
	function encrypt($user_id) {
		// Key = secret pad to 32 bytes (AES-256-CBC)
		$key = str_pad(ENCRYPTION_KEY, 32, "\0", STR_PAD_RIGHT);
		// IV = first 16 chars of md5(secret) as raw bytes
		$iv = hex2bin(substr(md5(ENCRYPTION_KEY), 0, 16));
		// Encrypt user_id
		$encrypted = openssl_encrypt(
			$user_id,
			'AES-256-CBC',
			$key,
			0,
			$iv
		);
		return base64_encode($encrypted);
	}
	function getQuarter($txt_quarter,$type=null,$format=null){
		if($txt_quarter != ""){
			$arr = explode("/",$txt_quarter);
			$year = $arr[1];
			$quarter = str_replace("Q","",$arr[0]);
		}else{
			$quarter = ceil(date("n")/3);
			$year = date("Y");
		}
		if($type == "next"){
			if ($quarter == 4) {
		  		$quarter = 1;
			  	++$year;
			} else {
			  	++$quarter;
			}
		}else if($type == "prev"){
			if ($quarter == 1) {
		  		$quarter = 4;
			  	--$year;
			} else {
			  	--$quarter;
			}
		}
		$start = strtotime("01-".(3*$quarter-2)."-".$year." 00:00:00");
		$end = strtotime((($quarter == 1 || $quarter == 4) ? 31 : 30)."-".(3*$quarter)."-".$year." 23:59:59");
		return [
			'year' => $year,
			'quarter' => $quarter,
            'start' => $format ? date($format,$start) : $start,
            'end' => $format ? date($format,$end) : $end,
		];
	}
	function getCurrentQuarter(){
		$month = date('n');
		if($month >= 1 && $month <= 3) return 1;
		if($month >= 4 && $month <= 6) return 2;
		if($month >= 7 && $month <= 9) return 3;
		if($month >= 10 && $month <= 12) return 4;
	}
	function get_dates_of_quarter($quarter = 'current', $year = null, $format = null){
        if ( !is_int($year) ) {        
           $year = (new DateTime)->format('Y');
        }
        $current_quarter = ceil((new DateTime)->format('n') / 3);
		// return $current_quarter;
        switch (  strtolower($quarter) ) {
            case 'this':
            case 'current':
               $quarter = ceil((new DateTime)->format('n') / 3);
               break;
            case 'previous':
               $year = (new DateTime)->format('Y');
               if ($current_quarter == 1) {
                  $quarter = 4;
                  $year--;
                } else {
                  $quarter =  $current_quarter - 1;
                }
                break;
			case 'next':
               $year = (new DateTime)->format('Y');
               if ($current_quarter == 4) {
                  $quarter = 1;
                  $year++;
                } else {
                  $quarter =  $current_quarter + 1;
                }
                break;
            case 'first':
                $quarter = 1;
                break;
            case 'last':
                $quarter = 4;
                break;
            default:
                $quarter = (!is_int($quarter) || $quarter < 1 || $quarter > 4) ? $current_quarter : $quarter;
                break;
        }
        if ( $quarter === 'this' ) {
            $quarter = ceil((new DateTime)->format('n') / 3);
        }
        $start = new DateTime($year.'-'.(3*$quarter-2).'-1 00:00:00');
        $end = new DateTime($year.'-'.(3*$quarter).'-'.($quarter == 1 || $quarter == 4 ? 31 : 30) .' 23:59:59');
        return array(
			'year' => $year,
			'quarter' => $quarter,
            'start' => $format ? $start->format($format) : $start,
            'end' => $format ? $end->format($format) : $end,
        );
    }
	function getRangeTime($time){
        if($time=='TODAY'){
            $current_date = date('d-m-Y');
            $start_time = strtotime("{$current_date} 00:00:00");
            $due_time = strtotime("{$current_date} 23:59:59");
        } else if($time=='YESTERDAY'){
            $current_date = date('d-m-Y', strtotime("-1 days"));
            $start_time = strtotime("{$current_date} 00:00:00");
            $due_time = strtotime("{$current_date} 23:59:59");
        } else if($time=='THIS_WEEK'){
            $start_time = strtotime('monday this week 00:00:00');
            $due_time = strtotime('sunday this week 23:59:59');
        } else if($time=='PREV_WEEK'){
            $start_time = strtotime('monday last week 00:00:00');
            $due_time = strtotime('sunday last week  23:59:59');
        } else if($time=='THIS_MONTH'){
            $start_time = strtotime("first day of this month 00:00:00");
            $due_time = strtotime("last day of this month 23:59:59");
        } else if($time=='PREV_MONTH'){
            $start_time = strtotime('first day of last month 00:00:00');
            $due_time = strtotime('last day of last month 23:59:59');
        } else if($time=='THIS_PERIOD'){
            $tmp = $this->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
            $start_time = strtotime($tmp['start']);
            $due_time = strtotime($tmp['end']." 23:59:59");
        } else if($time=='PREV_PERIOD'){
            $tmp = $this->get_dates_of_quarter('previous', null, 'd-m-Y');
            $start_time = strtotime($tmp['start']);
            $due_time = strtotime($tmp['end']." 23:59:59");
        } else if($time=='THIS_YEAR'){
            $start_time = strtotime('first day of january this year 00:00:00');
            $due_time = strtotime('last day of december this year 23:59:59');
        } else if($time=='PREV_YEAR'){
            $start_time = strtotime('first day of january last year 00:00:00');
            $due_time = strtotime('last day of december last year 23:59:59');
        }
		$start_date = date('d-m-Y', $start_time);
		$due_date = date('d-m-Y', $due_time);
		return array(
			'start_date' => strtotime("{$start_date} 00:00"),
			'due_date' => strtotime("{$due_date} 23:59")
		);
    }
	function getEmbedVideo($url, $w="100%", $h='250', $ctrl=0){
		global $core, $dbconn, $clsISO, $deviceType;
		if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $url)){
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
			return '<iframe class="radius-3 mb-2 overflow-hidden"  width="'.$w.'" height="'.$h.'" src="https://www.youtube.com/embed/'.$matches[1].'?rel=0&controls='.$ctrl.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen oncontextmenu="return false"></iframe>';
		}
		return "";
	}
	function getGoogleId($url){
		$gg_id = "";
		if($this->checkContainer($url,"drive.google.com","")){
			if(preg_match('/folders/', $url)){
				preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $url, $matches);
				$gg_id = $matches[1];
			} else if(preg_match('/file/', $url)){
				if(preg_match('~/d/\K[^/]+(?=/)~', $url)){
					preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
					$gg_id = $matches[0];
				} else if(preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $url)){
					preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
					$gg_id = $matches[0];
				}
			}
		}
		return $gg_id;
	}
	function getGoogleUrl($url,$szw=2000){
		global $profile_id;
		$url = trim($url);
		if($this->checkContainer($url,"drive.google.com","")){
			if(preg_match('~/d/\K[^/]+(?=/)~', $url)){
				preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
				return sprintf('https://drive.google.com/thumbnail?id=%s&sz=w'.$szw, $matches[0]);
			} else if(preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $url)){
				preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
				if(!empty($matches)){
					return sprintf('https://drive.google.com/thumbnail?id=%s&sz=w'.$szw, $matches[0]);
				} else {
					$id = str_replace('https://drive.google.com/uc?export=view&id=','',$url);
					$id = str_replace('https://drive.google.com/thumbnail?id=','',$url);
					$tmp = explode("&",$id);
					$id = $tmp[0];
					return sprintf('https://drive.google.com/thumbnail?id=%s&sz=w'.$szw, $id);
				}
			} 
			return $url;
		} else {
			return $url;
		}
	}
	function getIframeUrl($url){
		global $profile_id;
		$url = trim($url);
		if($this->checkContainer($url,"drive.google.com","")){
			if(preg_match('/folders/', $url)){
				preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $url, $matches);
				return sprintf('https://drive.google.com/embeddedfolderview?id=%s#grid', $matches[1]);
			} else if(preg_match('/file/', $url)){
				if(preg_match('~/d/\K[^/]+(?=/)~', $url)){
					preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
					return sprintf('https://drive.google.com/file/d/%s/preview', $matches[0]);
				} else if(preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $url)){
					preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
					return sprintf('https://drive.google.com/file/d/%s/preview', $matches[0]);
				}
			}
			return $url;
		} else if($this->checkContainer($url,"docs.google.com","")){
			if(preg_match('~/d/\K[^/]+(?=/)~', $url)){
				preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
				return sprintf('https://drive.google.com/file/d/%s/preview', $matches[0]);
			} else if(preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $url)){
				preg_match('~/d/\K[^/]+(?=/)~', $url, $matches);
				return sprintf('https://drive.google.com/file/d/%s/preview', $matches[0]);
			}
		} else {
			if($this->checkContainer($url, 'images/attachments', "")){
				$url = str_replace(FH_URL,'',$url);
				return FH_URL . $url;
			} else {
				return $url;
			}
		}
	}
	function isFileVideo($url){
		$videoExtensions = ['mp4', 'webm', 'ogg', 'mkv', 'avi', 'mov'];
		$pathInfo = pathinfo(parse_url($url, PHP_URL_PATH));
		if (isset($pathInfo['extension']) && in_array(strtolower($pathInfo['extension']), $videoExtensions)) {
			return true;
		}
		return false;
	}
	function getDownloadURL($url){
		global $core, $dbconn, $profile_id;
		$url = trim($url);
		if($this->checkContainer($url,"drive.google.com","")){
			$gg_id = $this->getGoogleId($url);
			return $this->genGoogleURL($gg_id, "download");
		} else if($this->checkContainer($url, 'images/attachments', "")){
			$url = str_replace(FH_URL,'',$url);
			return FH_URL . $url;
		} else {
			return $url;
		}
	}
	function genGoogleURL($id, $link_type = 'view',$is_size=1,$size=1500){
		$sz = !empty($is_size) ? "&sz=w".$size : "";
		switch($link_type){
			case 'download':
				return sprintf('https://drive.google.com/uc?id=%s&export=download',$id);
			case 'direct_link':
				return sprintf('https://drive.google.com/file/d/%s/preview',$id);
			case 'video':
				return sprintf('https://drive.google.com/file/d/%s/view', $id);
			case 'preview':
				return sprintf('https://drive.google.com/file/d/%s/preview', $id);
			case 'spreadsheets':
				return sprintf('https://docs.google.com/spreadsheets/d/%s', $id);
			default:
				return sprintf('https://drive.google.com/thumbnail?id=%s'.$sz,$id);
		}
	}
	function formatFileName($string, $keep=25) {
		global $core, $dbconn, $clsISO;
		$length = @strlen($string);
		if($length > $keep){
			$left = @substr($string, 0, $keep);
			$right = @substr($string, -$keep);
			return sprintf('%s...%s', $left, $right);
		} else {
			return $string;
		}
	}
	function getImageWH($image,$w,$h){		
		$noimage = URL_IMAGES.'/no-image.png';
		$noimage = $this->parseImageURL($noimage);
		$image = !empty($image) ? $image : $noimage;
		if($w > 0 || $h > 0){
			return '/files/thumb/'.$w.'/'.$h.'/'.$this->parseImageURL($image);
		}
		return $image;
	}
	function getUrlImageFH($url,$w=0,$h=0){
		$url = trim($url);
		if(!$this->checkContainer($url,"https","")){
			if($w > 0 || $h > 0) {
				return $this->getImageWH($url,$w,$h);
			}
			return $url;
		} else {
			return $this->getGoogleUrl($url,$w);
		}
	}
	function slugify($str){
		$str = mb_strtolower($str, 'UTF-8');
		$unicode = [
			'a'=>'áàảãạăắằẳẵặâấầẩẫậ',
			'd'=>'đ',
			'e'=>'éèẻẽẹêếềểễệ',
			'i'=>'íìỉĩị',
			'o'=>'óòỏõọôốồổỗộơớờởỡợ',
			'u'=>'úùủũụưứừửữự',
			'y'=>'ýỳỷỹỵ'
		];
		foreach($unicode as $nonUnicode=>$uni){
			$str = preg_replace("/[$uni]/u", $nonUnicode, $str);
		}
		$str = preg_replace('/[^a-z0-9\s]/', '', $str);
		$str = preg_replace('/\s+/', '_', $str);
		return $str;
	}
	function make_slug($text){
		$slug = str_replace(" ", "-", $text);
		$charRep = array(
			"a" => array("á", "à", "ả", "ã", "ạ", "ă", "ắ", "ằ", "ẳ", "ẵ", "ặ", "â", "ấ", "ầ", "ẩ", "ẫ", "ậ"),
			"d" => array("đ"),
			"e" => array("é", "è", "ẻ", "ẽ", "ẹ", "ê", "ế", "ề", "ể", "ễ", "ệ"),
			"i" => array("í", "ì", "ỉ", "ĩ", "ị"),
			"o" => array("ó", "ò", "ỏ", "õ", "ọ", "ô", "ố", "ồ", "ổ", "ỗ", "ộ", "ơ", "ớ", "ờ", "ở", "ỡ", "ợ"),
			"u" => array("ú", "ù", "ủ", "ũ", "ụ", "ư", "ứ", "ừ", "ử", "ữ", "ự"),
			"y" => array("ý", "ỳ", "ỷ", "ỹ", "ỵ"),
			"" => array("/", ":", ";", ".", ",", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "~" , "'", "\"", "\\", "{", "}", "[", "]", "<", ">", "?", "\n")
		);
		foreach($charRep as $key => $arrKeyRep) {
			foreach ($arrKeyRep as $index => $keyRep) {
				$slug = str_replace($keyRep, $key, $slug);
			}
		}
		$slug = strtolower($slug);
		$slug = str_replace("--", "-", $slug);
		return $slug;
	}
	function getTime($time){
		global $_LANG_ID;
		$periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
		$lengths = array("60","60","24","7","4.35","12","10");
	   	for($j = 0; $time >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   	    $time /= $lengths[$j];
	   	}
	   	$time = round($time);
	   	return "$time $periods[$j] ";
	}
	function convertTimeMinute($minute){
		$hour = floor($minute/60);
		$mi = $minute - ($hour * 60);
		return $hour." giờ ".$mi." phút";
	}
	function getTimeHtml($time,$txt=""){
		global $_LANG_ID;
		$periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
		$lengths = array("60","60","24","7","4.35","12","10");
		$class = "text-success";
		if($time/(60*30) > 1) {
			$class = "text-danger";
		}
		$flag = 0;
	   	for($j = 0; $time >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   	    $time /= $lengths[$j];
			$flag = $j;
	   	}
	   	$time = round($time); 
		if($txt != ""){
				return "<span class='fs-11 text-white bg-danger p-1 rounded-1'>".$time." ".$periods[$j]." ".$txt."</span>";
		}
	   	return "<span class='fs-11 ".$class."'>".$time." ".$periods[$j]." ".$txt."</span>";
	}
	function getNumberTime($time){
		$time = date("d/m/Y",$time);
		$startDate = DateTime::createFromFormat('d/m/Y', $time);
		$endDate = new DateTime(); 
		$interval = $startDate->diff($endDate);
		$text = "";
		$years = $interval->y;
		$months = $interval->m;
		$days = $interval->d;
		if ($years > 0 && $months >= 6) {
			return "Gần " . ($years + 1) . " năm";
		} elseif ($years > 0) {
			if($months >= 11){
				return "Gần ". ($years+1). " năm";
			}else if($months == 6){
				return "$years năm rưỡi";
			}else if($months > 3 && $months < 6) {
				return "Gần $years năm rưỡi";
			}else{
				return "Hơn $years năm";
			}
		} elseif ($months >= 11 && $days > 15) {
			return "Gần 1 năm";
		} elseif ($months >= 6 && $days > 15) {
			return "Gần " . ($months + 1) . " tháng";
		} elseif ($months >= 6) {
			return "Hơn $months tháng";
		} elseif ($months > 0 && $days >= 20) {
			return "Gần " . ($months + 1) . " tháng";
		} elseif ($months > 0) {
			if($days >= 21){
				return "Gần ". ($months+1). " tháng";
			}else if($days == 15){
				return "$months tháng rưỡi";
			}else if($days > 10 && $days < 15) {
				return "Gần $months tháng rưỡi";
			}else{
				return "Hơn $months tháng";
			}
		} elseif ($days >= 21) { 
			return "Gần 1 tháng";
		} elseif ($days >= 15) {
			return "Gần nửa tháng";
		} else {
			return "Hơn nửa tháng";
		}
	}
	function getFloor($totalFloor = 40,$selected = array()){
		global $core, $clsISO;
		$html = "";
		for ($i=1; $i <= $totalFloor; $i++) {
			$floor = ($i < 10) ? "0".$i : $i;
			$select = (in_array($i, $selected)) ? "selected" : "";
			$html .= '<option value="'.$i.'" '.$select.'>'.$floor.'</option>';
		}
		return $html;
	}
	function hashArray($stock_ids){
		sort($stock_ids);
		return md5(serialize($stock_ids));
	}
	function get_price_field($arr, $field, $def=0){
		if (isset($arr[$field]) && !empty($arr[$field])){
			if(!empty($arr["min"]) || !empty($arr["max"])) {
				return $this->convertPriceShortToFullUpdate(trim($arr[$field]),$arr["min"],$arr["max"]);
			}
			return $this->convertPriceShortToFullUpdate(trim($arr[$field]));
		}
			
		return $def;
	}
	/*=============�M L?CH================*/
	function timeEquals(string $time1, string $time2) {
		$t1 = strtotime($time1);
		$t2 = strtotime($time2);
		if ($t1 === false || $t2 === false) {
			return false; // time kh�ng h?p l?
		}
		return $t1 === $t2;
	}
	// Chuy?n t? d/m/y sang JDN
	function jdFromDate($d, $m, $y) {
		$a = floor((14 - $m) / 12);
		$y = $y + 4800 - $a;
		$m = $m + 12 * $a - 3;
		$jd = $d + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
		if ($jd < 2299161) $jd = $d + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - 32083;
		return $jd;
	}
	// T�m ng�y m?i trang (new moon) theo k v� timezone (gi? Vi?t Nam = 7)
	function getNewMoonDay($k, $timeZone) {
		$T = $k / 1236.85;
		$T2 = $T * $T;
		$T3 = $T2 * $T;
		$dr = M_PI / 180.0;
		$Jd1 = 2415020.75933 + 29.53058868 * $k + 0.0001178 * $T2 - 0.000000155 * $T3;
		$Jd1 += 0.00033 * sin((166.56 + 132.87 * $T - 0.009173 * $T2) * $dr);
		$M = 359.2242 + 29.10535608 * $k - 0.0000333 * $T2 - 0.00000347 * $T3;
		$Mpr = 306.0253 + 385.81691806 * $k + 0.0107306 * $T2 + 0.00001236 * $T3;
		$F = 21.2964 + 390.67050646 * $k - 0.0016528 * $T2 - 0.00000239 * $T3;
	   $C1 = (0.1734 - 0.000393 * $T) * sin($M * $dr)
		+ 0.0021 * sin(2 * $M * $dr)
		- 0.4068 * sin($Mpr * $dr)
		+ 0.0161 * sin(2 * $Mpr * $dr)
		- 0.0004 * sin(3 * $Mpr * $dr)
		+ 0.0104 * sin(2 * $F * $dr)
		- 0.0051 * sin(($M + $Mpr) * $dr)
		- 0.0074 * sin(($M - $Mpr) * $dr)
		+ 0.0004 * sin((2 * $F + $M) * $dr)
		- 0.0004 * sin((2 * $F - $M) * $dr)
		- 0.0006 * sin((2 * $F + $Mpr) * $dr)
		+ 0.0010 * sin((2 * $F - $Mpr) * $dr)
		+ 0.0005 * sin((2 * $Mpr + $M) * $dr);
		if ($T < -11) {
			$deltaT = 0.001 + 0.000839 * $T + 0.0002261 * $T2 - 0.00000845 * $T3 - 0.000000081 * $T * $T3;
		} else {
			$deltaT = -0.000278 + 0.000265 * $T + 0.000262 * $T2;
		}
		$JdNew = $Jd1 + $C1 - $deltaT;
		return floor($JdNew + 0.5 + $timeZone / 24.0);
	}
	// Kinh d? M?t Tr?i (don v? m�i 0..11)
	function getSunLongitude($jdn, $timeZone) {
		$T = ($jdn - 2451545.5 - $timeZone / 24.0) / 36525.0;
		$T2 = $T * $T;
		$dr = M_PI / 180.0;
		$M = 357.52910 + 35999.05030 * $T - 0.0001559 * $T2 - 0.00000048 * $T2 * $T;
		$L0 = 280.46645 + 36000.76983 * $T + 0.0003032 * $T2;
		$DL = (1.914600 - 0.004817 * $T - 0.000014 * $T2) * sin($dr * $M)
			+ (0.019993 - 0.000101 * $T) * sin($dr * 2 * $M)
			+ 0.000290 * sin($dr * 3 * $M);
		$L = $L0 + $DL;
		$L = $L * $dr;
		$L = $L - 2 * M_PI * floor($L / (2 * M_PI));
		return floor($L / M_PI * 6);
	}
	function getLunarMonth11($yy, $timeZone) {
		$off = $this->jdFromDate(31, 12, $yy) - 2415021;
		$k = floor($off / 29.530588853);
		$nm = $this->getNewMoonDay($k, $timeZone);
		$sunLong = $this->getSunLongitude($nm, $timeZone);
		if ($sunLong >= 9) $nm = $this->getNewMoonDay($k - 1, $timeZone);
		return $nm;
	}
	function getLeapMonthOffset($a11, $timeZone) {
		$k = floor(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
		$last = -1;
		$i = 1;
		$arc = $this->getSunLongitude($this->getNewMoonDay($k + $i, $timeZone), $timeZone);
		do {
			$last = $arc;
			$i++;
			$arc = $this->getSunLongitude($this->getNewMoonDay($k + $i, $timeZone), $timeZone);
		} while ($arc != $last && $i < 14);
		return $i - 1;
	}
	// H�m ch�nh: tr? v? [lunarDay, lunarMonth, lunarYear, isLeap(0/1)]
	function getLunarDate($dd, $mm, $yy, $timeZone = 7) {
		$dayNumber = $this->jdFromDate($dd, $mm, $yy);
		$k = floor(($dayNumber - 2415021.076998695) / 29.530588853);
		$monthStart = $this->getNewMoonDay($k + 1, $timeZone);
		if ($monthStart > $dayNumber) $monthStart = $this->getNewMoonDay($k, $timeZone);
		$a11 = $this->getLunarMonth11($yy, $timeZone);
		$b11 = $a11;
		if ($a11 >= $monthStart) {
			$lunarYear = $yy;
			$a11 = $this->getLunarMonth11($yy - 1, $timeZone);
		} else {
			$lunarYear = $yy + 1;
			$b11 = $this->getLunarMonth11($yy + 1, $timeZone);
		}
		$lunarDay = $dayNumber - $monthStart + 1;
		$diff = floor(($monthStart - $a11) / 29);
		$lunarLeap = 0;
		$lunarMonth = $diff + 11;
		if ($b11 - $a11 > 365) {
			$leapMonthDiff = $this->getLeapMonthOffset($a11, $timeZone);
			if ($diff >= $leapMonthDiff) {
				$lunarMonth = $diff + 10;
				if ($diff == $leapMonthDiff) $lunarLeap = 1;
			}
		}
		if ($lunarMonth > 12) $lunarMonth -= 12;
		if ($lunarMonth >= 11 && $diff < 4) $lunarYear -= 1;
		return array($lunarDay, $lunarMonth, $lunarYear, $lunarLeap);
	}
	/*=============END �M L?CH================*/
	function getRateNumber($unit,$total,$dec=0) {
		if(!empty($total) && (int)$total > 0) {
			$rate = (int)$unit * 100 / (int)$total;
			return round($rate,$dec);
		}
		return "";
	}
	function getListBlockPage(){
		$array = [
			"top_ranking" =>	[
				"slug"	=>	"nhan-vien-xuat-sac",
				"block_name"	=>	"top_ranking",
				"title_page"	=>	"Top 10 nhân viên xuất sắc",
				"title_menu"	=>	"Top 10 NV xuất sắc",
				"type"	=>	"",
				"icon"	=>	"bx bx-crown",
			],
			"ranking_area" =>	[
				"slug"	=>	"khoi-kinh-doanh",
				"block_name"	=>	"ranking_dept",
				"title_page"	=>	"Bảng xếp hạng khối kinh doanh",
				"title_menu"	=>	"BXH khối KD",
				"type"	=>	"area",
				"icon"	=>	"bx bx-globe",
			],
			"ranking_department" =>	[
				"slug"	=>	"phong-kinh-doanh",
				"block_name"	=>	"ranking_dept",
				"title_page"	=>	"Bảng xếp hạng phòng kinh doanh",
				"title_menu"	=>	"BXH phòng KD",
				"type"	=>	"department",
				"icon"	=>	"bx bx-trophy",
			],
		];
		return $array;
	}
	function parseAIJson(string $raw) {
		$raw = trim($raw);
		// Xoá markdown nếu có
		$raw = preg_replace('/^```json|^```|```$/m', '', $raw);
		$data = json_decode($raw, true);
		 return $data;
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception('JSON AI không hợp lệ');
		}
		return $data;
	}
	function getDataImage($url,$data) {
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$curl = new Curl\Curl();			
		$curl->setHeaders(array(
			'Content-Type' => 'application/json'
		));
		$tblData = [];
		$curl->post($url, $data);
		if(!$curl->error){
			$content = $curl->response->content;
			$content = $this->parseAIJson($content);
			$arr_data = $this->to_array_json($content);
			if(isset($arr_data["headers"]) || isset($arr_data["rows"])) {
				$header = $arr_data["headers"];
				$rows = $arr_data["rows"];
				array_unshift($rows, $header);
				$tblData = array_merge($tblData,$rows);
			}else{
				$tblData = $arr_data;
			}
			
		}
		return $tblData;
	}
}
?>