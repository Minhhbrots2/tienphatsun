<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function default_loginw(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	if(isset($_POST['login_step']) && $_POST['login_step'] == 'login'){
		$USER = Input::post('USER');
		$PASSWORD = Input::post('PASSWORD');
		if(!empty($USER) && !empty($PASSWORD)){
			if($clsProfile->userLoggedIn($USER, $PASSWORD, true)){
				header('Location:/');
				exit();
			} else {
				header('Location:/dang-nhap/ret=/');
				exit();
			}
		} else {
			header('Location:/dang-nhap/ret=/');
			exit();
		}
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Đăng nhập - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = 'Đăng nhập - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_signup(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	if($clsProfile->isLoggedIn()){
		header("location: ".PCMS_URL.'/profile.html');
		exit();
	}
	#
	$err_msg = '';
	$err_count = 0;
	if(isset($_POST['submit']) && $_POST['submit'] =='register'){
		$err_full_name = "";
		$full_name = trim(Input::post('full_name'));
		if(empty($full_name)) {
			$err_count ++;
			$err_full_name = 'Họ tên không được trống';
		}
		$assign_list["err_full_name"] = $err_full_name;
		#
		$err_email = "";
		$email = trim(Input::post('email'));
		if(empty($email)){
			$err_count ++;
			$err_email = 'Email không để trống.';
		}
		if(!empty($email) && ($clsProfile->getIdByEmail($email)!=0 || !$clsProfile->checkValidEmailAddress($email))){
			$err_count++;
			$err_email .= 'Email không đúng hoặc đã tồn tại.';
		}
		$assign_list["err_email"] = $err_email;
		#
		$err_pass = "";
		$user_pass = trim(Input::post('user_pass'));
		if(empty($user_pass)) {
			$err_count ++;
			$err_pass = 'Mật khẩu không để trống.';
		}
		if(!empty($user_pass) && strlen($user_pass)<6) {
			$err_count ++;
			$err_pass = 'Mật khẩu ít nhất có 6 ký tự.';
		}
		$assign_list["err_pass"] = $err_pass;
		#
		$err_cpass = "";
		$user_cpass = trim(Input::post('user_cpass'));
		if(empty($user_cpass)) {
			$err_count ++;
			$err_cpass = 'Nhập mật khẩu không để trống.';
		}
		if(!empty($user_pass) && !empty($user_cpass) && $user_pass != $user_cpass){
			$err_count ++;
			$err_cpass = 'Bạn cần nhập lại đúng mật khẩu.';
		}
		$assign_list["err_cpass"] = $err_cpass;
		#
		if(_ISOCMS_CAPTCHA=='IMG'){
			$security_code = Input::post('security_code'. "");
			$security_code = strtoupper($security_code);
			if(empty($security_code)){
				$err_count++;
				$errMsg.= '&bull; '.$core->get_Lang('Please enter security code').' <br />';
			} else if($security_code != $_SESSION['skey']) {
				$err_count++;
				$errMsg .= $core->get_Lang('Secure code not match').' <br />';
			}
		} else {
			if(!$clsISO->checkGoogleReCAPTCHA()){
				$err_count++;
				$errMsg .= $core->get_Lang('Secure code not match').' <br />';
			}
		}
		#
		$tmp = explode('@',$email);
		if($err_count==0){
			$profile_id = $clsProfile->getMaxId();
			$confirm_code = $clsProfile->genConfirmCode($email);
			$field = "profile_id,user_name,user_pass,email,full_name,full_name_slug,oauth_provider,reg_date,confirm_code,is_active";
			$value = "'".$profile_id."','".addslashes($tmp[0])."','".$clsProfile->encrypt($user_pass)."','".addslashes($email)."',
			'".addslashes($full_name)."','".$core->replaceSpace($full_name)."','_register','".time()."','{$confirm_code}','0'";
			if($clsProfile->insertOne($field,$value)){
				$clsProfile->sendMailRegister($profile_id);
				header("Location: ".PCMS_URL.'/tai-khoan/cam-on.html');
				exit();
			}else{
				$err_msg .= 'Hệ thống đang quá tải. Vui lòng thử lại sau!<br>';
			}
		}else{
			foreach($_POST as $k=>$v){
				$assign_list[$k] = $v;
			}
			$assign_list["errMsg"] = $errMsg;
		}
		$assign_list["err_msg"] = $err_msg;
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Đăng ký thành viên - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_signin(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $clsISO,$clsCookie,$clsConfiguration;
	$clsProfile = new Profile(); 
	$clsProfileSession = new ProfileSession(); 
	$assign_list["clsProfile"] = $clsProfile;
	$assign_list["clsProfileLog"] = $clsProfileLog;
	###
	$show = Input::get('show', 'signin');
	$assign_list["show"] = $show;
	#If already login
	if($clsProfile->isLoggedIn()){
		header("location: ".PCMS_URL.'/profile.html');
		exit();
	}
	// $clsISO->print_pre($_POST); die();
	if(isset($_POST['credential']) && !empty($_POST['credential'])){
		$credential = $_POST['credential'];
		$return_url = Input::get('return_url','/');
		try{
			$data = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.',$credential)[1]))));
			$data = toArray($data);
			$name = $data['name'];
			$email = $data['email'];
			$picture = $data['picture'];
			$full_name = $data['name'];
			$given_name = $data['given_name'];
			$family_name = $data['family_name'];
			$email_verified = $data['email_verified'];
			###
			$field = "{$clsProfile->pkey},`user_pass`,`is_active`,`status_id`";
			$tmp = $clsProfile->getByCond("(`oauth_email`='{$email}' or `email`='{$email}' or `user_name`='{$email}')", $field);
			// $clsISO->print_pre($tmp); die();
			if(!empty($tmp)){
				if($tmp['is_active']==0 || $tmp['status_id'] == _STATUS_STAFF_OFF_ID){
					redirect(PCMS_URL.'/dang-nhap/ret=/');
				} else {
					$profile_id = $tmp[$clsProfile->pkey];
					$clsProfileSession->insertLog($profile_id, 'login');
					if(defined('_cookie_use') && _cookie_use == 1){
						$clsCookie->putVar('loggedIn',1);
						$clsCookie->putVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
						$clsCookie->putVar('logged_key',md5($tmp["user_pass"].ENCRYPTION_KEY));
						$clsCookie->setVar();
					} else {
						vnSessionSetVar('loggedIn',1);
						vnSessionSetVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
						vnSessionSetVar('logged_key',md5($tmp["user_pass"].ENCRYPTION_KEY));
					}
					// Return
					redirect(PCMS_URL);
				}
			} 
		} catch(Exeption $ex){
			redirect(PCMS_URL.'/dang-nhap/ret=/');
		}
	} else {
		$_ss_forgot_password = '';
		if(vnSessionExist('_ss_forgot_password')){
			$_ss_forgot_password = vnSessionGetVar('_ss_forgot_password');
			vnSessionDelVar('_ss_forgot_password');
		}
		$assign_list["_ss_forgot_password"] = $_ss_forgot_password;
		$googleLoginUrl = 'https://accounts.google.com/o/oauth2/auth'
			.'?client_id='.appIdGoogle.'&redirect_uri='.urlencode(PCMS_URL.'/oauth2callback')
			.'&scope='.urlencode('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile')
			.'&response_type=code'.'&access_type=offline';
		$assign_list['googleLoginUrl'] = $googleLoginUrl;
		#
		$facebookLoginUrl = "https://www.facebook.com/v2.11/dialog/oauth"
		."?client_id=".appIdFacebook."&redirect_uri=".MAIN_URL.'/oauthfacebook&scope=email';
		$assign_list['facebookLoginUrl'] = $facebookLoginUrl;
		/** Get message from system*/
		$return = Input::get('return');
		$message = Input::get('message');
		$confirm = Input::get('confirm');
		$assign_list["return"] = $return;
		$assign_list["confirm"] = $confirm;
		$assign_list["message"] = $message;
		if(isset($_POST['submit']) && $_POST['submit']=='signin'){
			if(Input::exists('return', 'POST') && empty($return)){
				$return = Input::post('return');
			}
			$user_email = Input::post('user_email');
			$user_pass = Input::post('user_pass');
			$assign_list["user_email"] = $user_email;
			$assign_list["user_pass"] = $user_pass;
			$isValid = 1;
			if(empty($user_email)){
				$isValid = 0;
				$assign_list["err_user_email"] = 'Bạn chưa nhập Email';
			}
			if(empty($user_pass)){
				$isValid = 0;
				$assign_list["err_user_pass"] = 'Bạn chưa nhập Mật khẩu';
			}
			if($isValid==1){
				if($clsProfile->userLoggedIn($user_email,$user_pass)){
					if(!empty($return)){
						header("Location:".$return);
						exit();
					}else{
						header("Location:".PCMS_URL);
						exit();
					}
					$assign_list["err_login_false"] = 0;
				}else{
					$assign_list["err_login_false"] = 1;
					foreach($_POST as $k=>$v){
						$assign_list[$k] = $v;
					}
				}
			}else{
				foreach($_POST as $k=>$v){
					$assign_list[$k] = $v;
				}
			}
		}
	}
	/*=============Title & Description Page==================*/
	$meta_configs = $clsConfiguration->getValues(array('meta_title', 'meta_description', 'meta_keyword'));
	$title_page = $meta_configs['meta_title'];
	$assign_list["title_page"] = $title_page;
	$description_page = $meta_configs['meta_description'];
	$assign_list["description_page"] = $description_page;
	$keyword_page = $meta_configs['meta_keyword'];
	$assign_list["keyword_page"] = $keyword_page;
}
function default_logout(){
	global $assign_list,$profile_id,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	#If already login
	if(!$clsProfile->isLoggedIn()){
		header("Location: ".PCMS_URL);
		exit();
	}
	$clsProfile->userDoLogout();
	header('Location:/');
	exit();
}
function default_forgot(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page,$clsISO;
	$clsProfile = new Profile(); 
	$assign_list["clsProfile"] = $clsProfile;
	#If already login
	if($clsProfile->isLoggedIn()){
		header("Location: ".PCMS_URL);
		exit();
	}
	#- End
	$return_url = Input::get('return_url','');
	$assign_list["return_url"] = $return_url;
	if(isset($_POST['submit']) && $_POST['submit']=='forgot'){
		$error_no = 0; $errors = array(); $error_msg = "";
		$user_email = trim(Input::post('user_email'));
		$return_url = trim(Input::post('return_url'));
		if(!empty($user_email)){
			if(!$clsProfile->checkValidEmailAddress($user_email)){
				$error_no++;
				$errors[] = '<p class="mb-1">Địa chỉ Email không đúng định dạng <i>example@domain.com</i></p>';
			} else if(!$clsProfile->checkValidUsername($user_email))  {
				$error_no++;
				$errors[] = "<p class=\"mb-1\">Địa chỉ email có thể không tồn tại trong hệ thống!</p>";
			}
		} else {
			$error_no++;
			$errors[] = '<p class="mb-1">Bạn chưa nhập Email</p>';
		}
		if($error_no==0){
			$profile_id = $clsProfile->getIdByEmail($user_email);
			$oneProfile = $clsProfile->getOne($profile_id, "status_id,is_active");
			if($oneProfile['status_id'] == _STATUS_STAFF_OFF_ID || $oneProfile['status_id']==0){
				$error_no ++;
				$errors[] = "<p class=\"mb-1\">Địa chỉ email có tồn tại nhưng chưa được kích hoạt</p>";
			} else {
				$new_password = $clsProfile->randomPassword();
				$user_pass = $clsProfile->encrypt($new_password);
				$clsProfile->updateOne($profile_id, array('user_pass' => $user_pass));
				$clsProfile->sendEmailForgot($profile_id, $new_password);
				vnSessionSetVar('_ss_forgot_password', '_success');
				if(!empty($return_url)){
					header('Location:'.PCMS_URL.'/dang-nhap/ret='.$return_url);
					exit();
				} else {
					header('Location:'.PCMS_URL.'/dang-nhap.html');
					exit();
				}
			}
		} else {
			foreach($_POST as $k=>$v){
				$assign_list[$k] = $v;
			}
			foreach($errors as $err){
				$error_msg.= $err;
			}
		}
		$assign_list["error_no"] = $error_no;
		$assign_list["error_msg"] = $error_msg;
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Quên mật khẩu - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
function default_oauth2callback(){
	global $core,$dbconn,$_LANG_ID,$clsISO,$clsConfiguration,$deviceType,$clsCookie;
	$clsProfile = new Profile();
	$clsProfileSession = new ProfileSession();
	if($deviceType=='phone'){
		$returnCode = trim($_GET['code']);
		$curlPost = "https://www.googleapis.com/oauth2/v4/token?code=".$returnCode
		."&client_id=".appIdGoogle."&client_secret=".appGoogleSecret."&redirect_uri=".PCMS_URL
		.'/oauth2callback'."&grant_type=authorization_code";
		$user = array();
		$curl = new Curl\Curl();
		$curl->post($curlPost);
		if(!$curl->error){
			$response = toArray($curl->response);
			$access_token = isset($response) ? trim($response['access_token']) : "";
			if(!empty($access_token)){
				$curl = new Curl\Curl();
				$curl->get('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$access_token);
				if(!$curl->error){
					$user = toArray($curl->response);
				}
			} else {
				redirect(PCMS_URL.'/dang-nhap/ret=/');
			}
		} else {
			redirect(PCMS_URL.'/dang-nhap/ret=/');
		}
		###
		$id = isset($user['id'])?trim($user['id']):'';
		$avatar = isset($user['picture'])?trim($user['picture']):'';
		$email = isset($user['email'])?trim($user['email']):'';
		$full_name = isset($user['name'])?trim($user['name']):'';
		$family_name = isset($user['family_name'])?trim($user['family_name']):'';
		$given_name = isset($user['given_name'])?trim($user['given_name']):'';
		$verified_email = isset($user['verified_email'])?trim($user['verified_email']):0;
		###
		if(!empty($id) && !empty($email)){
			$field = "{$clsProfile->pkey},`user_pass`,`is_active`,`status_id`";
			$tmp = $clsProfile->getAll("`oauth_email`='{$email}' or `email`='{$email}' or `user_name`='{$email}'  limit 0,1", $field);
			if(!empty($tmp)){
				if($tmp[0]['is_active']==0 || $tmp[0]['status_id'] == _STATUS_STAFF_OFF_ID){
					redirect(PCMS_URL.'/dang-nhap/ret=/');
				} else {
					$profile_id = $tmp[0][$clsProfile->pkey];
					$clsProfileSession->insertLog($profile_id, 'login');
					if(defined('_cookie_use') && _cookie_use == 1){
						$clsCookie->putVar('loggedIn',1);
						$clsCookie->putVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
						$clsCookie->putVar('logged_key',md5($tmp[0]["user_pass"].ENCRYPTION_KEY));
						$clsCookie->setVar();
					} else {
						vnSessionSetVar('loggedIn',1);
						vnSessionSetVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
						vnSessionSetVar('logged_key',md5($tmp[0]["user_pass"].ENCRYPTION_KEY));
					}
				}
			}
			// Return
			redirect(PCMS_URL);
		}else{
			redirect(PCMS_URL.'/dang-nhap/ret=/');
		}
	}
}
function default_checkGoogleAccount(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$clsCookie;
	$clsProfile = new Profile();
	$clsProfileSession  = new ProfileSession();
	###
	$id = Input::post('id');
	$email = Input::post('email');
	$avatar = Input::post('avatar');
	$link = Input::post('link');
	$full_name = Input::post('full_name');
	$family_name = Input::post('family_name');
	$given_name = Input::post('given_name');
	$verified_email = Input::post('verified_email', false);
	###
	$msg = "_error";
	if(!empty($email)){
		$field = "{$clsProfile->pkey},`email`,`user_name`,`user_pass`,`is_active`,`status_id`";
		$tmp = $clsProfile->getAll("(`oauth_email`='{$email}' or `email`='{$email}' or `user_name`='{$email}') limit 0,1", $field);
		// $clsISO->print_pre($tmp); die();
		if(!empty($tmp)){
			if($tmp[0]['is_active']==0 || $tmp[0]['status_id'] == _STATUS_STAFF_OFF_ID){
				$msg = "_locked";
			} else {
				$msg  = "_success";
				$profile_id = $tmp[0][$clsProfile->pkey];
				$clsProfileSession->insertLog($profile_id, 'login');
				$fpoint = new FPoint();
				// $fpoint->insertPoint('login',$profile_id,$profile_log_id,date('d/m/Y h:i:s'));
				if(defined('_cookie_use') && _cookie_use == 1){
					$clsCookie->putVar('loggedIn',1);
					$clsCookie->putVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
					$clsCookie->putVar('logged_key',md5($tmp[0]["user_pass"].ENCRYPTION_KEY));
					$clsCookie->setVar();
				} else {
					vnSessionSetVar('loggedIn',1);
					vnSessionSetVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
					vnSessionSetVar('logged_key',md5($tmp[0]["user_pass"].ENCRYPTION_KEY));
				}
			}
		} else {
			$msg = "_existingAccount";
		}
	} else {
		$msg = "_existingAccount";
	}
	// Return
	echo $msg; die();
}
function default_checkAccountAJAX(){
	global $core,$dbconn,$_LANG_ID,$clsISO,$clsConfiguration,$clsCookie;
	$clsProfile = new Profile();
	$clsProfileSession  = new ProfileSession();
	###
	$fbUser = $_POST['fbUser'];
	$fbid = $fbUser['id'];
	$email = $fbUser['email'];
	if(!empty($fbid) && !empty($email)){
		$oauth_provider = '_facebook';
		$field = "{$clsProfile->pkey},`user_pass`,`is_active`,`status_id`";
		$tmp = $clsProfile->getAll("`oauth_email`='{$email}' or `email`='{$email}' and `user_name`='{$email}' limit 0,1", $field);
		if(!empty($tmp)){
			if($tmp[0]['is_active']==0 || $tmp[0]['status_id'] == _STATUS_STAFF_OFF_ID){
				$msg = "_locked";
			} else {
				$msg = "_success";
				$profile_id = $tmp[0]["profile_id"];
				$clsProfileSession->insertLog($profile_id, 'login');
				$fpoint = new FPoint();
				// $fpoint->insertPoint('login',$profile_id,$profile_log_id,date('d/m/Y h:i:s'));
				if(defined('_cookie_use') && _cookie_use == 1){
					$clsCookie->putVar('loggedIn',1);
					$clsCookie->putVar('logged_id',md5($profile_id.ENCRYPTION_KEY));
					$clsCookie->putVar('logged_key',md5($profile_id.ENCRYPTION_KEY));
					$clsCookie->setVar();
				} else {
					vnSessionSetVar('loggedIn',1);
					vnSessionSetVar('logged_id',md5($profile_id.ENCRYPTION_KEY));
					vnSessionSetVar('logged_key',md5($tmp[0]['user_pass'].ENCRYPTION_KEY));
				}
			}
		} else {
			$msg = "_existingAccount";
		}
	} else {
		$msg = "_existingAccount";
	}
	// Return
	echo($msg);die();
}
function default_checkLoginAJAX(){
	global $core,$dbconn,$_LANG_ID,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	if($clsProfile->isLoggedIn()){
		echo("_SUCCESS");die();
	}
	echo(0);die();
}
function default_confirmation(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$assign_list["group_page"] = 'member';
	$clsProfile = new Profile(); 
	$assign_list["clsProfile"] = $clsProfile;
	#If already login
	if($clsProfile->isLoggedIn()){
		header("Location: ".PCMS_URL.'/profile.html');
		exit();
	}
	/* End */
	if(isset($_POST['submit']) && $_POST['submit']=='confirm'){
		$confirm_code = Input::post('confirm_code');
		if(!empty($confirm_code)){
			$tmp = $clsProfile->getAll("is_trash=0 and is_active='1' and confirm_code='{$confirm_code}' limit 0,1",$clsProfile->pkey);
			if(!empty($tmp)){
				$profile_id = $res[0][$clsProfile->pkey];
				$clsProfile->updateOne($profile_id, array(
					'is_active' => 1,
					'confirm_code' => ""
				));
				header('Location:'.PCMS_URL.'/dang-nhap.html?message=success');
				exit();
			} else {
				$err_confirm_code = 'Mã xác minh không chính xác. Vui lòng thử lại';
			}
		} else {
			$err_confirm_code = 'Bạn cần nhập vào mã xác minh !';
		}
		$assign_list["err_confirm_code"] = $err_confirm_code;
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Xác minh tài khoản - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_active(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$clsProfile = new Profile();
	$confirm_code = Input::get('confirm_code');
	if(!empty($confirm_code)){
		header('Location:'.PCMS_URL.'/tai-khoan/xac-minh-tai-khoan.error');
		exit();
	} else {
		$tmp = $clsProfile->getAll("is_trash=0 and is_active='0' and confirm_code='{$confirm_code}' limit 0,1",$clsProfile->pkey);
		if(!empty($tmp)){
			$profile_id = $tmp[0][$clsProfile->pkey];
			$clsProfile->updateOne($profile_id, array(
				'is_active' => 1,
				'confirm_code' => ""
			));
			header('Location:'.PCMS_URL.'/dang-nhap.html?message=success');
			exit();
		}else{
			header('location:'.PCMS_URL.'/tai-khoan/xac-minh-tai-khoan.error');
			exit();
		}
	}
}
function default_success(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	#
	$assign_list["group_page"] = 'member';
	$message = isset($_GET['message'])?$_GET['message']:'';
	$assign_list["message"] = $message;
	
	if($message=='register'){
		$title_page = 'Đằng ký Tài khoản thành công - '.PAGE_NAME;
	}else{
		$title_page = 'Khôi phục mật khẩu thành công - '.PAGE_NAME;
	}
	/*=============Title & Description Page==================*/
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
/**
 * API: Upload ảnh base64 (tương đương base642imagejpeg)
 * POST params:
 *   - api_key   : Key xác thực
 *   - data      : Base64 string (không có prefix data:image/...)
 *   - filename  : Tên file gốc (VD: avatar.jpg)
 *   - dirname   : Thư mục lưu (VD: /avatar hoặc /BROKER/avatar)
 * Response JSON: { status, message, filepath, url }
 */
function default_api_upload_base64() {
	global $core, $dbconn, $clsISO;
	header('Content-Type: application/json; charset=utf-8');
	// === Xác thực API Key ===
	$api_key = isset($_POST['api_key']) ? trim($_POST['api_key']) : '';
	$valid_key = defined('_API_UPLOAD_KEY') ? _API_UPLOAD_KEY : '4dd60e00-89ed-476d-8877-2aa2f6c6e1e2';
	if (empty($api_key) || $api_key !== $valid_key) {
		echo json_encode(array(
			'status' => 'error', 
			'message' => 'API key không hợp lệ.'
		), JSON_UNESCAPED_UNICODE);
		die;
	}
	// === Validate params ===
	$data = isset($_POST['data']) ? trim($_POST['data']) : '';
	$filename = isset($_POST['filename']) ? trim($_POST['filename']) : '';
	$dirname = isset($_POST['dirname']) ? trim($_POST['dirname']) : '';
	if (empty($data) || empty($filename) || empty($dirname)) {
		echo json_encode(array(
			'status' => 'error',
			'message' => 'Thiếu tham số: data, filename, dirname.'
		), JSON_UNESCAPED_UNICODE);
		die;
	}
	// Loại bỏ prefix data:image/xxx;base64, nếu có
	if (strpos($data, ';base64,') !== false) {
		$parts = explode(';base64,', $data);
		$data = end($parts);
	}
	// === Upload giống base642imagejpeg ===
	$clsUploadFile = new UploadFile();
	$filepath = $clsUploadFile->base642imagejpeg($data, $filename, $dirname);
	if (!empty($filepath) && file_exists(ABSPATH . $filepath)) {
		$url = DOMAIN_URL . $filepath;
		echo json_encode(array(
			'status' => 'ok',
			'message' => 'Upload thành công.',
			'filepath' => $filepath,
			'url' => $url
		), JSON_UNESCAPED_UNICODE);
	} else {
		echo json_encode(array(
			'status' => 'error', 
			'message' => 'Upload thất bại.'
		), JSON_UNESCAPED_UNICODE);
	}
	die;
}
?>