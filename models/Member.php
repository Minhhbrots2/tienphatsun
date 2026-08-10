<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||

|| #################################################################### ||

\*======================================================================*/

class Member extends dbBasic{

	function __construct(){

		$this->pkey = "profile_id";

		$this->tbl = DB_PREFIX."member";

	}

	function getCode(){

		$total = $this->countItem("1=1");

		return sprintf('CTV%s', $total);

	}

	function getIndentity($member_id, $has_avatar= false, $class="xs"){

		if($member_id > 0){

			$one = $this->getOne($member_id, "code,full_name,avatar");

			if($has_avatar){

				$avatar = '<img class="avatar avatar-'.$class.' mr-2 rounded-pill" 

				src="'.$this->getAvatar($member_id, $one).'" />';

				return $avatar . sprintf('%s-%s', $one['code'],$one['full_name']);

			}	

			return sprintf('%s-%s', $one['code'],$one['full_name']);

		} else {

			return '-';

		}

	}

	function getIndentityV2($member_id, $oDataTable = array(), $has_avatar = false, $style=1){

		if($has_avatar){

			$avatar = '<img alt="'.$oDataTable['full_name'].'" class="avatar avatar-xs mr-2 rounded-pill" 

			src="'.$this->getAvatar($member_id, $oDataTable).'" />';

			return $avatar . sprintf('%s-%s', $oDataTable['code'],$oDataTable['full_name']);

		}	

		if($style==1){

			return sprintf('%s-%s', $oDataTable['code'],$oDataTable['full_name']);

		} else {

			return sprintf('[%s] %s', $oDataTable['code'],$oDataTable['full_name']);

		}

	}

	function getIndentityV3($user_id, $has_avatar = false, $oDataTable = array()){

		global $core, $dbconn, $member_id, $oneProfile;

		if($member_id==$user_id){

			$oDataTable = $oneProfile;

		} else {

			if(empty($oDataTable)){

				$oDataTable = $this->getOne($user_id, "full_name,avatar,profile_type,role_id");

			}

		}

		if($has_avatar){

			$avatar = '<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />';

			return $avatar . $this->getFullName($user_id, $oDataTable);

		}	

		return $oDataTable['full_name'];

	}

	function getIndentityV4($user_id, $oDataTable = array(), $class="", $only_avatar = false ){

		global $core, $dbconn, $member_id, $oneProfile;

		$clsProperty = new Property();

		if($user_id > 0){

			if(empty($oDataTable)){

				if($member_id==$user_id){

					$oDataTable= $oneProfile;

				} else {

					$oDataTable = $this->getOne($user_id, "full_name,avatar,department_id");

				}

			}

			return '<div class="d-inline-flex align-items-center cursor-pointer '.$class.'" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$user_id.'" 

			data-toggle="webui-popover" data-trigger="hover" data-width="400">

				<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />

				'.($only_avatar==false?'<div class="line-height-0">

					<p class="mb-0">'.$this->getFullName($user_id, $oDataTable).'</p>

					<small class="text-muted">'.$clsProperty->getTitle($oDataTable['department_id']).'</small>

				</div>':'').'

			</div>';

		} else {

			return '-';

		}

	}

	function getIndentityV5($user_id, $oDataTable = array(), $class=""){

		global $core, $dbconn, $member_id, $oneProfile;

		$clsProperty = new Property();

		if($user_id > 0){

			if(empty($oDataTable)){

				if($member_id==$user_id){

					$oDataTable= $oneProfile;

				} else {

					$oDataTable = $this->getOne($user_id, "full_name,avatar,department_id");

				}

			}

			return '<div class="d-inline-flex align-items-center'.$class.'">

				<img class="avatar avatar-sm mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />

				<div class="xyz">

					<p class="mb-0">'.$this->getFullName($user_id, $oDataTable).'</p>

					<small class="text-muted">'.$clsProperty->getTitle($oDataTable['department_id']).'</small>

				</div>

			</div>';

		} else {

			return '-';

		}

	}

	function getIndentityV6($user_id, $oDataTable = array(), $class="", $only_avatar = false ){

		global $core, $dbconn, $member_id, $oneProfile;

		if($user_id > 0){

			if(empty($oDataTable)){

				if($member_id==$user_id){

					$oDataTable= $oneProfile;

				} else {

					$oDataTable = $this->getOne($user_id, "full_name,first_name,last_name,avatar");

				}

			}

			return '<div class="d-inline-flex align-items-center cursor-pointer '.$class.'" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$user_id.'" 

			data-toggle="webui-popover" data-trigger="hover" data-width="350">

				<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />

				'.($only_avatar==false?'<div class="line-height-0">

					<p class="mb-0">'.$this->getFullName($user_id, $oDataTable).'</p>

				</div>':'').'

			</div>';

		} else {

			return '-';

		}

	}

	function getIndentityV7($user_id, $oProfile= array(), $has_avatar = false){

		global $core, $dbconn, $member_id, $oneProfile;

		$clsLog = new Log();

		$clsProperty = new Property();

		if(!mb_check_encoding($oProfile['full_name'], 'UTF-8')) {

			$full_name = mb_convert_encoding($oProfile['full_name'], 'UTF-8', 'Windows-1252');

		}else{

			$full_name = $oProfile['full_name'];

		}

		if($has_avatar){

			$site_from = '<sup class="fs-tiny text-primary">MOC</sup>';

			$avatar = '<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oProfile).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />';

			$total_searchs = isset($oProfile['total_searchs']) ? $oProfile['total_searchs'] :  $clsLog->countItem("`user_id`='{$user_id}'");

			return array('html' => '<a href="javascript:void(0);" data-url="/index.php?mod=log&act=load_profile_popover&user_id='.$user_id.'&tbl=member" data-toggle="webui-popover" data-trigger="hover" data-width="350">'.$avatar . $full_name.'</a>'.$site_from.' <strong class="text-black">('.$total_searchs.')</strong>', 'total_searchs' => $total_searchs);

		} else {

			return $full_name;

		}

	}

	function getNameArray($ids){

		$names = "";

		if(!empty($ids)){

			#- Require library

			require_once(DIR_INCLUDES.'/json_master/autoload.php');

			#- End require

			$cachedName = implode('_', $ids);

			$cachedFile = DIR_CACHE_JSON.'/group_names/'.$cachedName.'.json';

			if(@file_exists($cachedFile)){

				$decoder = new Webmozart\Json\JsonDecoder();

				$names = $decoder->decodeFile($cachedFile);

			} else {

				$tmp = array();

				foreach($ids as $member_id){

					$oneProfile = $this->getOne($member_id,"full_name,first_name,last_name,code");

					$tmp[] = $this->getIndentityV2($member_id, $oneProfile, false, 2);

				}

				$names = implode('<br />', $tmp);

				$encoder = new Webmozart\Json\JsonEncoder();

				$encoder->encodeFile($names, $cachedFile);

			}

		}

		return $names;

	}

	function getShortNameArray($ids){

		$names = "";

		if(!empty($ids)){

			#- Require library

			require_once(DIR_INCLUDES.'/json_master/autoload.php');

			#- End require

			$cachedName = implode('_', $ids);

			$cachedFile = DIR_CACHE_JSON.'/group_short_names/'.$cachedName.'.json';

			if(@file_exists($cachedFile)){

				$decoder = new Webmozart\Json\JsonDecoder();

				$names = $decoder->decodeFile($cachedFile);

			} else {

				$tmp = array();

				foreach($ids as $member_id){

					$oneProfile = $this->getOne($member_id,"department_id,last_name");

					$tmp[] = $this->getLastName($member_id, $oneProfile);

				}

				$names = implode('-', $tmp);

				$encoder = new Webmozart\Json\JsonEncoder();

				$encoder->encodeFile($names, $cachedFile);

			}

		}

		return $names;

	}

	function genConfirmCode($email){

		return md5(ENCRYPTION_KEY.$email.time());

	}

	function getConfirmCode($member_id){

		$one = $this->getOne($member_id,"confirm_code");

		return $one['confirm_code'];

	}

	function confirmationProfile($key){

		$all = $this->getAll("is_active=0 and profile_type='register'");

		if($all[0]['member_id']!=''){

			for($i=0;$i<count($all);$i++){

				$temp = md5($all[$i]['member_id'].'-VIETISO');

				if($temp==$key)

					return $all[$i]['member_id'];

			}

			return '0';

		}

		return 'n/a';

	}

	function encrypt($password){

		return md5(md5($password));

	}

	function randomPassword($length=6) {

		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

		$pass = array(); //remember to declare $pass as an array

		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

		for ($i = 0; $i < $length; $i++) {

			$n = rand(0, $alphaLength);

			$pass[] = $alphabet[$n];

		}

		return implode($pass); //turn the array into a string

	}

	function getLastLogin($member_id){

		global $core; 

		return $core->getRegDate(time());

	}

	function getEmail($member_id, $oDataTable = array(), $mask=false){

		if(!isset($oDataTable['email'])){

			$oDataTable = $this->getOne($member_id,"email");

		}

		return $this->mask($oDataTable['email'], true);

	}

	function getName($member_id, $oDataTable=null){

		if(!isset($oDataTable['full_name']) || is_null($oDataTable)){

			$oDataTable = $this->getOne($member_id,"full_name");

		}

		return $oDataTable['full_name'];

	}

	function getFullName($user_id, $one=array()){

		global $_frontIsLoggedin_user_id,$core;

		if(!isset($one['full_name'])){

			$one = $this->getOne($user_id);

		}

		$full_name = trim($one['full_name']);

		if(!empty($full_name))

			return $full_name;

		return $one['last_name'].' '.$one['first_name'];

	}

	function getLastName($member_id, $oDataTable = array(), $has_department=true){

		global $_frontIsLoggedin_user_id,$core;

		$clsProperty = new Property();

		$department_id = $oDataTable['department_id'];

		if($has_department)

			return sprintf('[%s] %s', $clsProperty->getTitle($department_id), $oDataTable['last_name']);

		return sprintf('%s', $oDataTable['last_name']);

	}

	function getPhone($member_id, $oDataTable=array(), $mask=false){

		if(!isset($oDataTable['phone'])){

			$oDataTable = $this->getOne($member_id,"phone");

		}

		return !empty($oDataTable['phone']) ? $this->mask($oDataTable['phone'], $mask) : '[Chưa cập nhật]';

	}

	function getValueField($member_id, $oDataTable, $field){

		global $core, $dbconn, $clsISO;

		if(!isset($oDataTable[$field])){

			$oDataTable = $this->getOne($member_id, $field);

		}

		$value = $oDataTable[$field];

		if(!empty($value)){

			$length = strlen($value);

			return substr($value,($length-4), 4);

		}

		return "";

	}

	function mask($str, $mask=false){

		if($mask==false) return $str;

		if($str==''){ return '';}

		$len = strlen($str);

		if($len > 6){

			$stat = 2; $end = 6;

			return substr_replace($str,'****',$stat,($end-$stat));

		}

		return $str;

	}

	function getCCID($member_id, $oDataTable=array(), $mask=false){

		if(!isset($oDataTable['CCID'])){

			$oDataTable = $this->getOne($member_id,"CCID");

		}

		return !empty($oDataTable['CCID']) ? $this->mask($oDataTable['CCID'], true) : '[Chưa cập nhật]';

	}

	function getBirthday($member_id, $oDataTable=array()){

		if(!isset($oDataTable['birthday'])){

			$oDataTable = $this->getOne($member_id,"birthday");

		}

		return !empty($oDataTable['birthday']) ? date('d/m/Y', $oDataTable['birthday']) : '[Chưa cập nhật]';

	}

	function getSex($member_id, $is_link=true, $oDataTable=array()){

		if(!isset($oDataTable['gender'])){

			$oDataTable = $this->getOne($member_id,"gender");

		}

		if(!empty($oDataTable['gender']))

			return ($oDataTable['gender']==1)?'Nam':'Nữ';

		return ($is_link) ? '<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";	

	}

	function getCountryName($member_id, $is_link=true, $oDataTable=array()){

		$clsCountry = new Country();

		if(!isset($oDataTable['country_id'])){

			$oDataTable = $this->getOne($member_id,"country_id");

		}

		if(!empty($oDataTable['country_id']))

			return $clsCountry->getTitle($oDataTable['country_id']);

		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";

	}

	function getCityName($member_id, $is_link=true, $oDataTable=array()){

		$clsCity = new City();

		if(!isset($oDataTable['city_id'])){

			$oDataTable = $this->getOne($member_id,"city_id");

		}

		if((int) $oDataTable['city_id']>0)

			return $clsCity->getTitle($oDataTable['city_id']);

		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";

	}

	function getDistrictName($member_id, $is_link=true, $oDataTable=array()){

		$clsDistrict = new District();

		if(!isset($oDataTable['district_id'])){

			$oDataTable = $this->getOne($member_id,"district_id");

		}

		if(!empty($oDataTable['district_id']))

			return $clsDistrict->getTitle($oDataTable['district_id']);

		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";

	}

	function getAddress($member_id, $is_link=true){

		$oDataTable = $this->getOne($member_id,"address");

		return !empty($oDataTable['address']) ? $oDataTable['address'] : '[Chưa cập nhật]';

	}

	function getAddressText($member_id, $is_link=true, $oDataTable=array()){

		if(!isset($oDataTable['address'])){

			$oDataTable = $this->getOne($member_id,"address");

		}

		if(!empty($oDataTable['address']))

			return $oDataTable['address'];

		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";

	}

	function getAvatar($user_id, $oDataTable = array(), $w=60, $h=60){

		global $core, $dbconn, $clsISO;

		if(!isset($oDataTable['avatar'])){

			$oDataTable = $this->getOne($user_id, "avatar");

		}

		$avatar = trim($oDataTable['avatar']);

		if(!empty($avatar)){

			if($clsISO->checkContainer($avatar, '/images/avatar/','')){

				return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($avatar);

			} else {

				return $avatar;

			}

		} else {

			return URL_IMAGES.'/no-avatar.jpg';

		}

	}

	function getProfile($member_id){

		$clsProperty = new Property();

		$field = "full_name,first_name,last_name,avatar,level_id,rating_star";

		$this->arrResult = $this->getOne($member_id, $field);

		$level_id = $this->arrResult['level_id'];

		$rating_star = $this->arrResult['rating_star'];

		$name = $this->getFullName($member_id, $this->arrResult);

		$avatar = $this->getAvatar($member_id, $this->arrResult);

		$level = ((int) $level_id > 0) ? $clsProperty->getTitle($level_id) : "";

		return array(

			'name' => $name,

			'avatar' => $avatar,

			'level' => $level,

			'html_star' => $this->genHTMLStar($rating_star)

		);

	}

	function genHTMLStar($star){

		global $core, $dbconn, $clsISO;

		$html = '';

		$diff = 5 - $star;

		for($i=1; $i<=$star; $i++){

			$html.= $core->makeIcon('star star-yellow');

		}

		if($diff > 0){

			for($i=1; $i<=$diff; $i++){

				$html.= $core->makeIcon('star star-blank');

			}

		}

		return $html;

	}

	function getFieldValue($field, $oDataTable){

		if($field=='country'){

			$clsCountry = new Country();

			return $clsCountry->getTitle($oDataTable['country_id']);

		}

		else if($field=='city'){

			$clsCity = new City();

			return $clsCity->getTitle($oDataTable['city_id']);

		}

		else if($field=='district'){

			$clsDistrict = new District();

			return $clsDistrict->getTitle($oDataTable['district_id']);

		}else{

			return $oDataTable[$field];

		}

	}

	function getTotalOrderInOne($member_id){

		global $core, $dbconn, $clsISO;

		$clsOrder = new Order();

		return $clsOrder->countItem("member_id='{$member_id}'");

	}

	function getTotalMoneyInOne($member_id){

		global $core, $dbconn, $clsISO;

		$clsOrder = new Order();

		$total = $clsOrder->sumItem("vpc_total_money", "is_trash=0 and member_id='{$member_id}'");

		return $clsISO->formatPrice($total);

	}	

	function checkValidUsername($user_name){

		return $this->countItem("user_name='{$user_name}' or email='{$user_name}'");

	}

	function checkValidEmailAddress($email){

		if ($email=="" || $email==null){ return false; }

		/** Use regular expression to check valid email address */

		return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? false : true;

	 }

	function checkValidEmail($email, $type="_order" ){

		$cond = "email='{$email}'";

		if(!empty($type)) $cond.= " and oauth_provider='{$type}'";

		return $this->countItem($cond) ? 1: 0;

	}

	function checkValidPhone($phone, $type="_order" ){

		$cond = "phone='{$phone}'";

		if(!empty($type)) $cond.= " and oauth_provider='{$type}'";

		return $this->countItem($cond) ? 1: 0;

	}

	function getIdByEmail($email){

		$tmp = $this->getByCond("`email`='{$email}' or `user_name`='{$email}' limit 0,1", $this->pkey);

		return !empty($tmp) ? $tmp[$this->pkey] : 0;

	}

	function getUserID() {

		global $core, $dbconn, $clsCookie;

		$member_id = 0;

		if(defined('_cookie_use') && _cookie_use == 1){

			$logged_id = $clsCookie->getVar('logged_id', "");

		} else {

			$logged_id = vnSessionExist('logged_id') 

				? vnSessionGetVar('logged_id') : ""; 

		}

		if(!empty($logged_id)){

			$lst = $this->getAll("is_trash=0 and is_active=1", $this->pkey);

			if(!empty($lst)){

				foreach($lst as $usr){

					$reg = md5($usr["member_id"].ENCRYPTION_KEY);

					if($logged_id===$reg){

						$member_id = $usr['member_id'];

						break;

					}

				}

			}

		}

		return $member_id;

    }

	function isLoggedIn() {

		global $core, $dbconn, $clsISO, $clsCookie;

		if(defined('_cookie_use') && _cookie_use == 1){

			$logged_id = $clsCookie->getVar('logged_id', 0);

			$logged_key = $clsCookie->getVar('logged_key', "");

		} else {

			$logged_id = vnSessionExist('logged_id') 

				? vnSessionGetVar('logged_id') : "";

			$logged_key = vnSessionExist('logged_key') 

				? vnSessionGetVar('logged_key') : "";

		}

		// var_dump($logged_key); die();

		if(!empty($logged_id) && !empty($logged_key)){

			$all = $this->getAll("`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $this->pkey);

			foreach($all as $key => $val){

				$reg = md5($val["member_id"].ENCRYPTION_KEY);

				if($logged_id===$reg){

					$member_id = $val['member_id'];

					break;

				}

			}

			$user_pass = $this->getOneField('user_pass', $member_id);

			if($logged_key==md5($user_pass.ENCRYPTION_KEY))

				return true;	

			return false;

		}

		return false;

	}

	function userLoggedIn($email, $user_pass, $login_redirect = false) {

		global $core, $dbconn, $clsISO, $clsCookie;

		$clsProfileLog = new ProfileLog();

		$clsProfileSession = new ProfileSession();

		if(!$login_redirect){

			$user_pass = $this->encrypt($user_pass);

		}

		$field = "member_id,user_name,user_pass";

		$tmp = $this->getByCond("`is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 

			and (`user_name`='{$email}' OR `email`='{$email}') and `user_pass`='{$user_pass}' limit 0,1", $field);

		//$clsISO->print_pre($tmp); die();

		if(!empty($tmp)){

			$member_id = $tmp["member_id"];

			$profile_log_id = $clsProfileLog->insertLog($member_id, 'login');

			$fpoint = new FPoint();

			$content= "Đăng nhập vào hệ thống ngày ".date('d/m/Y h:i:s');

			$fpoint->insertPoint('login',$member_id,$profile_log_id,$content);

			if(defined('_cookie_use') && _cookie_use == 1){

				$clsCookie->putVar('loggedIn', 1);

				$clsCookie->putVar('logged_id', md5($tmp["member_id"].ENCRYPTION_KEY));

				$clsCookie->putVar('logged_key', md5($tmp["user_pass"].ENCRYPTION_KEY));

				$clsCookie->setVar();

			} else {

				vnSessionSetVar('loggedIn', 1);

				vnSessionSetVar('logged_id', md5($tmp["member_id"].ENCRYPTION_KEY));

				vnSessionSetVar('logged_key', md5($tmp["user_pass"].ENCRYPTION_KEY));

			}

			$all = $clsProfileSession->getAll("member_id='{$member_id}' limit 0,1");

			if(!empty($all)){

				$fields = "member_id,ip_address,running_time,loggedin";

				$values = "'".$member_id."','".$_SERVER['REMOTE_ADDR']."','".time()."','1'";

				$clsProfileSession->insertOne($fields,$values);

			}else{

				$set = "ip_address='".$_SERVER['REMOTE_ADDR']."',loggedin='1',running_time='".time()."'";

				$clsProfileSession->updateOne($all[0]['profile_session_id'],$set);

			}

			return true;

		}else {

			return false;

		}

	}	

	function scanRunningUser(){

		$clsProfileSession = new ProfileSession();

		$all = $clsProfileSession->getAll();

		for($i=0;$i<count($all);$i++){

			$temp = $all[$i]["running_time"];

			$now = time();

			if($temp+5*60*60<$now && $all[$i]["loggedin"]==1){

				$clsProfileSession->updateOne($all[$i]["profile_session_id"],"loggedin='0',running_time='".time()."'"); 

			}

		}

	}

	function userDoLogout() {

		global $core,$dbconn, $member_id, $clsCookie;

		$member_id = $this->getUserID();

		$clsProfileLog = new ProfileLog();

		$clsProfileSession = new ProfileSession();

		$clsProfileSession->updateByCond("member_id='{$member_id}'","loggedin='0',running_time='".time()."'");

		$clsProfileLog->insertLog($member_id, 'logout');

		if(defined('_cookie_use') && _cookie_use == 1){

			$clsCookie->clearVar();

		} else {

			VnSessionDelVar('logged_id');

			VnSessionDelVar('logged_key');	

		}

		return true;

	}

	function getIdByKey($key_get){

		$all = $this->getAll();

		for($i=0;$i<count($all);$i++){

			$username = $all[$i]['email'];

			$temp = md5($username.'-VIETISO-RESET');

			if($temp==$key_get && $all[$i]['temp_reset_key']!=''){

				return $all[$i]['member_id'];

			}

		}

		return 0;

	}

	function sendEmailForgot($member_id, $new_password){

		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO;

		$clsEmailTemplate = new EmailTemplate();

		$email_template_id = _MAIL_FORGOT_PASSWORD_ID;

		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);

		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);

		###

		$oneProfile = $this->getOne($member_id, "full_name,first_name,last_name,email,user_name");

		$full_name = $this->getFullName($member_id, $oneProfile);

		$mapField = array(

			'{name}' => $full_name,

			'{user_password}' => $new_password,

			'{user_email}' => $oneProfile['user_name'],

		);

		foreach($mapField as $key => $val){

			$subject = str_replace($key, $val, $subject);

			$message = str_replace($key, $val, $message);

		}

		###

		$toemail = $this->getEmail($member_id, $oneProfile);

		$toname = $this->getFullName($member_id, $oneProfile);

		return $clsISO->sendEmail($toemail,$toname,$subject,$message);

	}

	function sendMailRegister($member_id){

		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO;

		$email_template_id = 10;

		$clsEmailTemplate = new EmailTemplate();

		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);

		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);

		$email_logo_url = $clsEmailTemplate->getLogoUrl($email_template_id);

		$width_logo = $clsEmailTemplate->getLogoWidth($email_template_id);

		$height_logo = $clsEmailTemplate->getLogoHeight($email_template_id);

		$smarty->assign('shop_url', DOMAIN_NAME);

		$smarty->assign('email_logo_url', $email_logo_url);

		$smarty->assign('width_logo', $width_logo?$width_logo:'auto');

		$smarty->assign('height_logo', $height_logo?$height_logo:'auto');

		$smarty->assign('shop_url', DOMAIN_NAME);

		$smarty->assign('shop_name', $clsConfiguration->getValue('company_name'));

		$smarty->assign('shop_phone', $clsConfiguration->getValue('company_phone'));

		$smarty->assign('shop_email', $clsConfiguration->getValue('company_email'));

		$smarty->assign('shop_address', $clsConfiguration->getValue('company_address'));

		$one = $this->getOne($member_id);

		$confirm_code = $one['confirm_code'];

		$confirm_link = DOMAIN_NAME.'/dang-ky/xac-minh-tai-khoan-'.$confirm_code.'.html';

		$smarty->assign('account_name', $one['full_name']);

		$smarty->assign('confirm_code', $confirm_code);

		$smarty->assign('confirm_link', $confirm_link);

		$subject = $smarty->fetch('eval:'.$subject);

		$message = $smarty->fetch('eval:'.$message);

		#- Send to customer.

		$toemail = $one['email'];

		$toname = $one['full_name'];

		$is_send_email = $clsISO->sendEmail($toemail,$toname,$subject,$message);

		return $is_send_email;

	}

	function checkInGroup($member_id, $group_id, $oDataTable=null){

		global $clsISO;

		if(!isset($oDataTable['list_group_id'])){

			$oDataTable = $this->GetOne($member_id,"list_group_id");

		}

		$list_group_id= $oDataTable['list_group_id'];

		if(!empty($list_group_id)){

			$arr = $clsISO->getArrayByTextSlash($list_group_id);

			return in_array($group_id, $arr) ? 1: 0;

		}

		return 0;

	}

	function checkInGroupSale($department_id){

		global $clsISO;

		if($department_id==_DEPARTMENT_SALE_ID)

			return 1;

		$clsProperty = new Property();

		if($clsProperty->countItem("property_id='{$department_id}' and parent_id='"._DEPARTMENT_SALE_ID."'"))

			return 1;

		return 0;

	}

	function getNumberDayTakeleave($user_id,$year){

		$oneUser = $this->getOne($user_id,"info_takeleave");

		$info_takeleave = json_decode($oneUser['info_takeleave'],true);

		$number_day = 0;

		if(!empty($info_takeleave[$year])){

			foreach($info_takeleave[$year] as $oneTakeleave){

				$number_day += $oneTakeleave['number_day'];

			}

		}

		return $number_day;

	}

	function getHeadOfDep($department_id){

		global $core,$dbconn,$member_id,$oneProfile,$clsISO;

		$cond = "`is_trash`=0 and `is_active`=1 and `department_id`={$department_id}";

		// $clsISO->print_pre($department_id); die();

		if($department_id==_DEPARTMENT_DIRECTOR_ID){

			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_GD_MANAGER."'","{$this->pkey}");

			return !empty($tmp) ? $tmp[$this->pkey] : 0;

		} else if($department_id==_DEPARTMENT_BO_ID){

			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_HEAD_BO."'","{$this->pkey}");

			return !empty($tmp) ? $tmp[$this->pkey] : 0;

		} else if($this->checkInGroupSale($department_id)){

			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_GD_SALE."'","{$this->pkey}");

			return !empty($tmp) ? $tmp[$this->pkey] : 0;

		}

	}

	function getNumberDayNoPaidleave($user_id,$year){

		$oneProfile = $this->getOne($user_id,"info_no_paid_leave");

		$info_no_paid_leave = !empty($oneProfile['info_no_paid_leave']) 

			? @json_decode($oneProfile['info_no_paid_leave'],true) : array();

		$number_day = 0;

		if(isset($info_no_paid_leave[$year]) && !empty($info_no_paid_leave[$year])){

			foreach($info_no_paid_leave[$year] as $oneTakeleave){

				$number_day += $oneTakeleave['number_day'];

			}

		}

		return $number_day;

	}

	function getLstCurator($user_id=0){

		global $member_id,$dbconn,$clsISO,$core;

		if($user_id==$member_id){

			$user_group_id = $core->_USER['user_group_id'];

		}else{

			if(empty($user_id)) $user_id = $adminid;

			$user_group_id = $this->getOnefield("user_group_id",$user_id);

		}

		$JSON_GROUP_CURATOR = $clsISO->getVar('JSON_GROUP_CURATOR');

		$JSON_GROUP_CURATOR = json_decode($JSON_GROUP_CURATOR,true);

		$JSON_USER_CURATOR = $clsISO->getVar('JSON_USER_CURATOR');

		$JSON_USER_CURATOR = json_decode($JSON_USER_CURATOR,true);

		$cond = "`is_trash`=0 and `is_active`=1";

		if(in_array($user_group_id,$JSON_GROUP_CURATOR)){

			$cond .= " and (`user_group_id`={$user_group_id} or `user_id` IN(".implode(',',$JSON_USER_CURATOR)."))";

		}else{

			//$cond .= " and user_group_id={$user_group_id}";

		}

		$lstCurator = $this->getAll($cond,"{$this->pkey},`code`,`full_name`,`email`");

		return $lstCurator;

	} 

	function getReasonTakeleave($user_id,$year,$takeleave_id,$tp){

		global $clsISO;

		$uniqid = $clsISO->getUniqid();

		$html = '<a class="underline reason_takeleave'.$uniqid.'" href="#">'.makeIcon('eye').'</a>

		<script async="async" type="text/javascript">

			$easyUI(".reason_takeleave'.$uniqid.'").tooltip({

				position: \'top\',

				content: function(){

					return $("<div></div>");

				},

				onUpdate: function(cc){

					cc.panel({

						id: "panel_reason_takeleave'.$uniqid.'",

						width: 300,

						height: \'auto\',

						href: "'.PCMS_URL.'/index.php?mod=takeleave&act=ajGetReasonUser&user_id='.$user_id.'&year='.$year.'&takeleave_id='.$takeleave_id.'&tp='.$tp.'",

						onLoad:function(){

							$("#panel_reason_takeleave'.$uniqid.'").css({width:"auto"});

							$("#panel_reason_takeleave'.$uniqid.'").closest(".panel").css({width:"auto"});

						}

					});

				},

				onShow: function(){

					$("#panel_reason_takeleave'.$uniqid.'").css({width:"auto"});

					$("#panel_reason_takeleave'.$uniqid.'").closest(".panel").css({width:"auto"});

				},

			});

		</script>';

		return $html;

	}

	function getReasonUser($user_id,$year,$takeleave_id,$tp){

		if($tp=='takeleave'){

			$info_takeleave = $this->getOneField('info_takeleave',$user_id);

			$info_takeleave = json_decode($info_takeleave,true);

			$lstTakeleaveInYear = $info_takeleave[$year];

			$oneTakeleave = $lstTakeleaveInYear[$takeleave_id];

			return '<div class="alert alert-success mb-0">'.html_entity_decode($oneTakeleave['content']).'</div>';

		}

		if($tp=='no_paid_leave'){

			$info_no_paid_leave = $this->getOneField('info_no_paid_leave',$user_id);

			$info_no_paid_leave = json_decode($info_no_paid_leave,true);

			$lstNoPaidLeaveInYear = $info_no_paid_leave[$year];

			$oneNoPaidLeave = $lstNoPaidLeaveInYear[$takeleave_id];

			return '<div class="alert alert-success mb-0">'.html_entity_decode($oneNoPaidLeave['content']).'</div>';

		}

	}

	function getLinkEdit($member_id, $action='edit'){

		if($action=='edit')

			return '/profile/edit/'.$member_id.'.html';

		if($action=='bank')

			return '/profile/edit/'.$member_id.'/bank.html';

		if($action=='password')

			return '/profile/edit/'.$member_id.'/password.html';

	}

	function getHTMLStaff($department_id){

		global $core, $dbconn, $clsISO;

		$html = ""; $total_staffs = 0;

		$field = "{$this->pkey},code,full_name,first_name,last_name,avatar";

		$list_staffs = $this->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 

		and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')", $field);

		if(!empty($list_staffs)){ $ii= 1;

			$total_staffs = count($list_staffs);

			$html.= '<ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">';

			foreach($list_staffs as $oneUser){

				$user_id = $oneUser[$this->pkey];

				if($ii <= 5){

					$html.= '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-sm pull-up"><img src="'.$this->getAvatar($user_id, $oneUser).'" alt="'.$this->getFullName($user_id, $oneUser).'" class="rounded-circle" /></li>';

					++$ii;

				}

			}

			if($total_staffs > 5){

				$html.= '<li class="avatar pull-up">

					<a class="user-plus radius-circle bg-white" >+'.($total_staffs-5).'</a>

				</li>';

			}

			$html.= '</ul>';

		} 

		return $html; 

	}

	function checkInWishlist($stock_id){

		global $clsISO, $member_id, $oneProfile;

		$wishlist = isset($oneProfile['wishlist']) 

			? $oneProfile['wishlist'] : array();

		$wishlist_arrs = !empty($wishlist) 

			? json_decode(html_entity_decode($wishlist), true) 

			: array();

		if(!empty($wishlist_arrs) && (int) $stock_id > 0 && in_array($stock_id, $wishlist_arrs))

			return 1;

		return 0;

	}

	function getLeader($department_id){

		if($department_id==_DEPARTMENT_DIRECTOR_ID){

			$role_id = _ROLE_GD_MANAGER;

			$field = "{$this->pkey},code,full_name,first_name,last_name,avatar";

			$tmp = $this->getByCond("`is_trash`=0 and `is_active`=1 and `department_id`='{$department_id}' 

			and `role_id`='{$role_id}'", $field);

			return $this->getFullName($tmp[$this->pkey], $tmp);

		} else if($department_id==_DEPARTMENT_SALE_ID){

		} else {

			$role_id = _ROLE_GD_SALE;

			$field = "{$this->pkey},code,full_name,first_name,last_name,avatar";

			$tmp = $this->getByCond("`is_trash`=0 and `is_active`=1 and `department_id`='{$department_id}' 

			and (`role_id`='{$role_id}' or `role_id`='"._ROLE_GD_PROJECT."')", $field);

			return $this->getFullName($tmp[$this->pkey], $tmp);

		}

	}

	function uploadImageGoogleDriver($files=[],$member_id){

		$results = array();

		if(!empty($files['name']) && $member_id >0){ 

			$ii = 0; //Init

			for($i = 0; $i<count($files); $i++){

				$clsUploadFile = new UploadFile();

				$image = array();

				$image["name"] = $files['name'][$i];

				$image["type"] = $files['type'][$i];

				$image["tmp_name"] = $files['tmp_name'][$i];

				$image["error"] = $files['error'][$i];

				$image["size"] = $files['size'][$i];

				if(!empty($image["name"])){

					if(@is_uploaded_file($image['tmp_name'])){

						$clsUploadFile = new UploadFile();

						$upload_file = $clsUploadFile->uploadItem($image,"/BROKER",EXTENSION_FILE_UPLOAD);

						$file_name = $image['name'];

						$file_size = $image['size'];

						// Upload file to google drive

						$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);

						$folder_id = $clsGoogleUpload->create_folder($member_id);

						// $clsISO->print_pre($folder_id); die();

						$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);

						$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';							

						if(!empty($upload_file)){

							$results[] = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";

						}

						@unlink(ROOTPATH . $upload_file);

						// Update to DB

					}

				}

			}			

		}

		return $results; 

	}

	function setPermalink($permalink,$check=1,$member_id=0){

		$cond = "";

		if($member_id > 0){

			$cond = " AND member_id <>'{$member_id}'";

		}

		$countCheck = $this->countItem("permalink='{$permalink}'".$cond);

		if($countCheck > 0){

			$permalink = $this->setPermalink($permalink.($check+1),++$check,$member_id); 

		}

		return $permalink;  

	}

	function sendEmailVIP($member_id){

		global $core, $dbconn, $clsISO;

		$clsEmailTemplate = new EmailTemplate();

		$oneMember = $this->getOne($member_id);

		$customer_name = $this->getFullName($member_id, $oneMember);

		$customer_email = trim($oneMember['email']);

		###

		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_MEMBER_VIP_ID);

		$subject = $clsEmailTemplate->getSubject(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);

		$fromname = $clsEmailTemplate->getFromName(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);

		$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);

		$cc = $clsEmailTemplate->getCopyTo(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);

		###

		$replace = array(

			'{time}' => date('d/m/Y H:i:s'),

			'{customer_name}' => $customer_name,

			'{customer_email}' => $customer_email

		);

		foreach($replace as $key => $val){

			$subject = @str_replace($key, $val, $subject);

			$message = @str_replace($key, $val, $message);

		}

		$toname = $customer_name;

		$toemail = trim($customer_email);

		$is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);

		// $is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);

		return $is_send;

	}

	// Email chúc mừng thăng hạng theo nguồn: MOC = template _MAIL_MEMBER_VIP_ID, MF = _MAIL_MEMBER_MF_VIP_ID (có {package_name}).

	// Tên gói tra runtime qua Property: MF đọc cột package_id (_MF_PACKAGE), MOC đọc cột role_id (_PACKAGE).

	function sendEmailVIPBySource($member_id, $source = 'MOC'){

		global $core, $dbconn, $clsISO;

		$clsEmailTemplate = new EmailTemplate();

		$clsProperty = new Property();

		$oneMember = $this->getOne($member_id);

		if(empty($oneMember)){

			return false;

		}

		$customer_name = $this->getFullName($member_id, $oneMember);

		$customer_email = trim($oneMember['email']);

		if(empty($customer_email)){

			return false;

		}

		###

		if($source == 'MF'){

			// Ưu tiên gói active thật từ bảng member_package (cột default_member.package_id có dữ liệu cũ không tin cậy)

			$clsMemberPackage = new MemberPackage();

			$activePkg = $clsMemberPackage->getActive($member_id);

			$_pkgId = !empty($activePkg)

				? (int) $activePkg['package_id']

				: (int) (isset($oneMember['package_id']) ? $oneMember['package_id'] : 0);

		} else {

			$_pkgId = (int) (isset($oneMember['role_id']) ? $oneMember['role_id'] : 0);

		}

		$package_name = ($_pkgId > 0) ? (string) $clsProperty->getOneField('title', $_pkgId) : '';

		if($package_name === ''){

			$package_name = 'VIP';

		}

		$email_template_id = ($source == 'MF') ? _MAIL_MEMBER_MF_VIP_ID : _MAIL_MEMBER_VIP_ID;

		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);

		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);

		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);

		$fromname = $clsEmailTemplate->getFromName($email_template_id, $oneEmailTemplate);

		$fromemail = $clsEmailTemplate->getFromEmail($email_template_id, $oneEmailTemplate);

		$cc = $clsEmailTemplate->getCopyTo($email_template_id, $oneEmailTemplate);

		###

		$replace = array(

			'{time}' => date('d/m/Y H:i:s'),

			'{customer_name}' => $customer_name,

			'{customer_email}' => $customer_email,

			'{package_name}' => $package_name

		);

		foreach($replace as $key => $val){

			$subject = @str_replace($key, $val, $subject);

			$message = @str_replace($key, $val, $message);

		}

		$toname = $customer_name;

		$toemail = trim($customer_email);

		return $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);

	}

	function getMemberCached($scope = "all"){

		global $core, $dbconn,$clsISO;

		$clsCache = new Cache();

		if($clsCache->has('_list_member_cached') && 1==2){

			$tmp = $clsCache->get('_list_member_cached');

			var_dump(1,$tmp);die;

			// $clsCache->delete('_list_profile_cached');

		} else {

			$field = "{$this->pkey},`full_name`,`first_name`,`last_name`,`avatar`,`phone`";

			$tmp = $this->getAll("`is_trash`=0", $field);

			if(!empty($tmp)){

				foreach($tmp as $key => $val) {

					$tmp[$key]["full_name"] = trim(strip_tags($val["full_name"]));

					/*$more_information = $val["more_information"];

					$more_information = $clsISO->to_array_json($more_information);

					$tmp[$key]['more_information'] = $more_information;*/

				}

				$clsCache->put('_list_member_cached', $tmp, 60*60); // Cache 1h

			}

		}

		$arrs = [];

		if(!empty($tmp)){

			foreach($tmp as $key => $val) {

				$id = $val[$this->pkey];

				$arrs[$id] = $val;

			}

		}

		return $arrs;

	}

}

?>