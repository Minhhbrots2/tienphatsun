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
class Profile extends dbBasic{
	function __construct(){
		$this->pkey = "profile_id";
		$this->tbl = DB_PREFIX."profile";
	}
	// Leader dashboard: tập profile_id cấp dưới theo CÂY PHÒNG BAN (subtree dưới $deptId).
	// list_department_id mã hoá sẵn tổ-tiên |team|40|vung| → 1 LIKE, không đệ quy. KHÔNG dùng GroupProfile.
	function getSubordinateStaffIds($deptId, $excludeProfileId = 0){
		global $dbconn;
		$d = (int) $deptId;
		if ($d <= 0) return array();
		$prop = DB_PREFIX."property";
		$isDept = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$prop}` WHERE `property_id`='{$d}' AND `property_type`='_DEPARTMENT' AND `is_trash`=0");
		if ($isDept <= 0) return array();
		$rows = $dbconn->GetAll("SELECT `profile_id` FROM `{$this->tbl}` WHERE `is_trash`=0 AND `status_id`<>'" . _STATUS_STAFF_OFF_ID . "' AND (`department_id`='{$d}' OR `list_department_id` LIKE '%|{$d}|%')");
		$ids = array();
		if (!empty($rows)) {
			foreach ($rows as $r) {
				$rid = (int) $r['profile_id'];
				if ($rid > 0 && $rid !== (int) $excludeProfileId) $ids[$rid] = $rid;
			}
		}
		return array_values($ids);
	}
	function genCode(){
		global $core, $dbconn, $clsISO;
		$tmp = $this->getByCond("1=1 order by `reg_date` DESC", "code");
		$code = !empty($tmp) ? $tmp['code'] : "";
		if(!empty($code)){
			$gen_code = str_replace('S','',$code);
			$gen_code = (int) $gen_code;
			$gen_code = $gen_code + 1;
			if(strlen($gen_code) == 3)
				return sprintf('S0%s', $gen_code);
			else if(strlen($gen_code) == 4)
				return sprintf('S%s', $gen_code);
		} else {
			return $this->getCode();
		}
	}
	function getCode(){
		$total = $this->countItem("1=1");
		return sprintf('S0%s', $total);
	}
	function normalizeCode($code, $length = 4) {
		preg_match('/^([A-Z]+)(\d+)$/', $code, $m);
		$prefix = $m[1];
		$number = (int)$m[2]; // ép về số -> tự bỏ 0 dư
		return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
	}
	function getZaloId($profile_id, $oDataTable = array()){
		global $core, $clsISO;
		if(!isset($oDataTable['more_information'])){
			$oDataTable = $this->getOne($profile_id, "`phone`,`more_information`");
			$phone_number = $oDataTable["phone"];
			$more_information = $oDataTable['more_information'];
		} else {
			$phone_number = $oDataTable["phone"];
			$more_information = $oDataTable['more_information'];
		}
		$more_information = $clsISO->to_array_json($more_information);
		if(isset($more_information['zaloId']) && !empty($more_information['zaloId'])){
			return trim($more_information['zaloId']);
		} else {
			$clsZalo = new Zalo();
			$zaloId = $clsZalo->getZaloId("", $phone_number);
			$more_information['zaloId'] = $zaloId;
			$this->updateOne($profile_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	function get_icon_verified($profile_id, $more_information = array(), $icon="2"){
		global $core, $dbconn, $clsISO;
		if(empty($more_information)){
			$more_information = $this->getOneField('more_information', $profile_id);
			$more_information = $clsISO->to_array_json($more_information);
		}
		$html = "";
		$issue_number = $core->get_field($more_information, 'issue_number', null);
		$is_star_club = (int) $core->get_field($more_information, 'is_star_club', 0);
		if($is_star_club == 1 && ($icon=="2" || $icon=="star")){
			$html.= '<span class="is-star-club rainbow-box" title="Thành viên CLB Starters"><i class=\'bx bxs-star text-main\'></i></span>';
		}
		if(!empty($issue_number) && ($icon=="2" || $icon=="verify")){
			$html.= '<span class="verified" data-bs-trigger="hover" title="Đã có chứng chỉ môi giới BĐS"><i class=\'bx bx-check-circle\'></i></span>';
		}
		return $html;
	}
	function getIndentity($user_id, $is_avatar = false, $class = "xs"){
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		if($user_id > 0){
			if($user_id == $profile_id){
				$one = $oneProfile;
			} else {
				$arr_profile_cached = $this->getProfileCached("all");
				$one = $arr_profile_cached[$user_id];
			}
			if($is_avatar){
				$more_information = $one['more_information'];
				$avatar = '<div class="avatar avatar-'.$class.'">
					<img class="avatar avatar-'.$class.' mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $one).'" />
					'.$this->get_icon_verified($user_id, $more_information).'
				</div>';
				return sprintf('%s %s-%s', $avatar, $one['code'], $one['full_name']);
			} else {
				return sprintf('%s-%s', $one['code'], $one['full_name']);
			}
		} else {
			return '-';
		}
	}
	function getIndentityV2($profile_id, $oDataTable = array(), $has_avatar = false, $style=1){
		if($has_avatar){
			$avatar = '<img alt="'.$oDataTable['full_name'].'" class="avatar avatar-xxs me-2 rounded-pill" 
			src="'.$this->getAvatar($profile_id, $oDataTable).'" />';
			return $avatar . sprintf('%s-%s', $oDataTable['code'],$oDataTable['full_name']);
		} else {
			$format = ($style==1) ? '%s-%s' : '[%s] %s';
			return sprintf($format, $oDataTable['code'], $oDataTable['full_name']);
		}
	}
	function getIndentityV3($user_id, $has_avatar = false, $oDataTable = array()){
		global $clsISO,$core, $dbconn, $profile_id, $oneProfile;
		if ($profile_id == $user_id) {
			$oDataTable = $oneProfile;
		} elseif (empty($oDataTable)) {
			$oDataTable = $this->getOne($user_id, "`code`,`full_name`,`avatar`");
		}
		if($has_avatar){
			if(!empty($oDataTable)) {
				$avatar = '<img class="avatar avatar-xxs rounded-pill noExl" src="'.$this->getAvatar($user_id, $oDataTable).'" 
					onerror="this.src=\''.URL_IMAGES.'/avatars/1.png\'" />';
				return '<a class="d-flex align-items-center gap-1 cursor-pointer" data-toggle="webui-popover" data-trigger="hover" 
				data-width="300" data-url="'.PCMS_URL.'/index.php?mod=home&act=load_profile_popover&user_id='.$user_id.'">
					'.$avatar. (!empty($oDataTable['code']) ? sprintf('%s-', $oDataTable['code']) : '') . $this->getFullName($user_id, $oDataTable).'
				</a>';
			}else{
				return "";
			}			
		}	
		return $oDataTable['full_name'];
	}
	function getIndentityV4($user_id, $oDataTable = array(), $class="", $only_avatar = false, $is_start_date=false ){
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsProperty = new Property();
		if($user_id > 0){
			if ($profile_id == $user_id) {
				$oDataTable = $oneProfile;
			} elseif (empty($oDataTable)) {
				$oDataTable = $this->getOne($user_id, "`full_name`,`avatar`,`start_date`,`more_information`");
			}
			$more_information = $oDataTable['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$role_name = $core->get_field($more_information, "role_name", "");
			$department_name = $core->get_field($more_information, "department_name", "");
			$start_date = ($is_start_date) ? sprintf('-- %s', date("d/m/Y", $oDataTable["start_date"])) : "";
			return '<div class="d-inline-flex align-items-center cursor-pointer '.$class.'" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$user_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="300">
				<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />
				'.($only_avatar==false?'<div class="line-height-0">
					<p class="mb-n1 text-nowrap">'.$this->getFullName($user_id, $oDataTable).'</p>
					<small class="text-muted">'.$department_name.$start_date.'</small>
				</div>':'').'
			</div>';
		} else {
			return '-';
		}
	}
	function getIndentityV5($user_id, $oDataTable = array(), $class="", $cls="xxs"){
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsProperty = new Property();
		if($user_id > 0){
			if(empty($oDataTable)){
				if ($profile_id == $user_id) {
					$oDataTable = $oneProfile;
				} elseif (empty($oDataTable)) {
					$oDataTable = $this->getOne($user_id, "`first_name`,`last_name`,`full_name`,`avatar`,`more_information`");
				}
			}
			$more_information = $oDataTable['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$role_name = $core->get_field($more_information, "role_name", "");
			$department_name = $core->get_field($more_information, "department_name", "");
			return '<div class="d-inline-flex gap-2 align-items-center'.$class.'">
				<img class="avatar avatar-'.$cls.' rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />
				<div class="lh-lg">
					<p class="mb-0">'.$this->getFullName($user_id, $oDataTable).'</p>
					<small class="text-muted text-fs-12">'.sprintf('%s - %s', $role_name, $department_name).'</small>
				</div>
			</div>';
		} else {
			return '-';
		}
	}
	function getIndentityV6($user_id, $oDataTable = array(), $class="", $only_avatar = false ){
		global $core, $dbconn, $profile_id, $oneProfile;
		$clsProperty = new Property();
		if($user_id > 0){
			if(empty($oDataTable)){
				if ($profile_id == $user_id) {
					$oDataTable = $oneProfile;
				} elseif (empty($oDataTable)) {
					$oDataTable = $this->getOne($user_id, "`first_name`,`last_name`,`full_name`,`avatar`");
				}
			}
			return '<div class="d-inline-flex align-items-center cursor-pointer '.$class.'" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$user_id.'" 
			data-toggle="webui-popover" data-trigger="hover" data-width="400">
				<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oDataTable).'" />
				'.($only_avatar==false?'<div class="line-height-0">
					<p class="mb-0">'.$this->getLastName($user_id, $oDataTable, false).'</p>
				</div>':'').'
			</div>';
		} else {
			return '-';
		}
	}
	function getIndentityV7($user_id, $oProfile= array(), $has_avatar = false){
		global $core, $dbconn, $profile_id, $oneProfile;
		$clsLog = new Log();
		$clsProperty = new Property();
		if($has_avatar){
			$total_searchs = isset($oProfile['total_searchs']) ? $oProfile['total_searchs'] : $clsLog->countItem("`user_id`='{$user_id}'");
			$avatar = '<img class="avatar avatar-xxs mr-2 rounded-pill" src="'.$this->getAvatar($user_id, $oProfile).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />';
			return array( 'html' => '<a href="javascript:void(0);" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$user_id.'" 
			data-toggle="webui-popover" data-trigger="hover" data-width="360">'.$avatar . $oProfile['full_name'].'</a> <strong class="text-black">('.$total_searchs.')</strong>', 'total_searchs' => $total_searchs);
		} else {
			return $oProfile['full_name'];
		}
	}
	function getNameArray($arrs, $glue = '<br />'){
		global $dbconn, $core, $clsISO;
		$names = "";
		if(!empty($arrs)){
			$list = $this->getAll("{$this->pkey} in (".implode(',', $arrs).")", "{$this->pkey},`code`,`full_name`,`first_name`,`last_name`");
			if(!empty($list)){
				$tmp = array();
				foreach($list as $key => $val){
					$tmp[] = $this->getIndentityV2($val[$this->pkey], $val, false, 2);
				}
				unset($list);
				$names = implode($glue, $tmp);
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
				foreach($ids as $profile_id){
					$oneProfile = $this->getOne($profile_id,"department_id,last_name");
					$tmp[] = $this->getLastName($profile_id, $oneProfile);
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
	function getConfirmCode($profile_id){
		$one = $this->getOne($profile_id,"confirm_code");
		return $one['confirm_code'];
	}
	function confirmationProfile($key){
		$all = $this->getAll("is_active=0 and profile_type='register'");
		if($all[0]['profile_id']!=''){
			for($i=0;$i<count($all);$i++){
				$temp = md5($all[$i]['profile_id'].'-VIETISO');
				if($temp==$key)
					return $all[$i]['profile_id'];
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
	function getLastLogin($profile_id){
		global $core; 
		return $core->getRegDate(time());
	}
	function getEmail($profile_id, $oDataTable = array(), $mask=false){
		if(!isset($oDataTable['email'])){
			$oDataTable = $this->getOne($profile_id,"email");
		}
		return $this->mask($oDataTable['email'], true);
	}
	function getName($profile_id, $oDataTable=null){
		if(!isset($oDataTable['full_name']) || is_null($oDataTable)){
			$oDataTable = $this->getOne($profile_id,"full_name");
		}
		return $oDataTable['full_name'];
	}
	function getFullName($user_id, $oDataTable=array()){
		global $_frontIsLoggedin_user_id,$core;
		if(empty($oDataTable)){
			$oDataTable = $this->getOne($user_id, "`first_name`,`last_name`,`full_name`");
		}
		if($oDataTable['full_name'] != '')
			return $oDataTable['full_name'];
		return sprintf('%s %s', $oDataTable['last_name'], $oDataTable['first_name']);
	}
	function getLastName($profile_id, $oDataTable = array(), $has_department=true){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		if($has_department){
			if(!isset($oDataTable["more_information"]) || !isset($oDataTable["last_name"])) {
				$oDataTable = $this->getOne($profile_id,"`last_name`,`more_information`");
			}			
			$more_information = $oDataTable['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$department_name = $core->get_field($more_information, "department_name", "");
			return sprintf('[%s] %s', $department_name, $oDataTable['last_name']);
		}elseif(!isset($oDataTable["last_name"])) {
			$oDataTable = $this->getOne($profile_id,"last_name");
		}			
		return sprintf('%s', $oDataTable['last_name']);
	}
	function getPhone($profile_id, $oDataTable=array(), $mask=false){
		if(!isset($oDataTable['phone'])){
			$oDataTable = $this->getOne($profile_id,"phone");
		}
		return !empty($oDataTable['phone']) ? $this->mask($oDataTable['phone'], $mask) : '[Chưa cập nhật]';
	}
	function getValueField($profile_id, $oDataTable, $field){
		global $core, $dbconn, $clsISO;
		if(!isset($oDataTable[$field])){
			$oDataTable = $this->getOne($profile_id, $field);
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
	function getCCID($profile_id, $oDataTable=array(), $mask=false){
		if(!isset($oDataTable['CCID'])){
			$oDataTable = $this->getOne($profile_id,"CCID");
		}
		return !empty($oDataTable['CCID']) ? $this->mask($oDataTable['CCID'], true) : '[Chưa cập nhật]';
	}
	function getBirthday($profile_id, $oDataTable=array()){
		if(!isset($oDataTable['birthday'])){
			$oDataTable = $this->getOne($profile_id,"birthday");
		}
		return !empty($oDataTable['birthday']) ? date('d/m/Y', $oDataTable['birthday']) : '[Chưa cập nhật]';
	}
	function getSex($profile_id, $is_link=true, $oDataTable=array()){
		if(!isset($oDataTable['gender'])){
			$oDataTable = $this->getOne($profile_id,"gender");
		}
		if(!empty($oDataTable['gender']))
			return ($oDataTable['gender']==1)?'Nam':'Nữ';
		return ($is_link) ? '<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";	
	}
	function getCountryName($profile_id, $is_link=true, $oDataTable=array()){
		$clsCountry = new Country();
		if(!isset($oDataTable['country_id'])){
			$oDataTable = $this->getOne($profile_id,"country_id");
		}
		if(!empty($oDataTable['country_id']))
			return $clsCountry->getTitle($oDataTable['country_id']);
		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";
	}
	function getCityName($profile_id, $is_link=true, $oDataTable=array()){
		$clsCity = new City();
		if(!isset($oDataTable['city_id'])){
			$oDataTable = $this->getOne($profile_id,"city_id");
		}
		if((int) $oDataTable['city_id']>0)
			return $clsCity->getTitle($oDataTable['city_id']);
		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";
	}
	function getDistrictName($profile_id, $is_link=true, $oDataTable=array()){
		$clsDistrict = new District();
		if(!isset($oDataTable['district_id'])){
			$oDataTable = $this->getOne($profile_id,"district_id");
		}
		if(!empty($oDataTable['district_id']))
			return $clsDistrict->getTitle($oDataTable['district_id']);
		return ($is_link)?'<a href="/edit-profile.html">[Chưa cập nhật]</a>' : "";
	}
	function getAddress($profile_id, $oDataTable = array()){
		if(!isset($oDataTable['address'])){
			$oDataTable = $this->getOne($profile_id,"address");
		}
		return !empty($oDataTable['address']) ? $oDataTable['address'] : '';
	}
	function getAddressText($profile_id, $is_link=true, $oDataTable=array()){
		if(!isset($oDataTable['address'])){
			$oDataTable = $this->getOne($profile_id,"address");
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
			if($clsISO->checkContainer($avatar, '/images/avatar/','') || $clsISO->checkContainer($avatar, '/images/BROKER/avatar/','')){
				return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($avatar);
			} else {
				return $avatar;
			}
		} else {
			return URL_IMAGES.'/no-avatar.jpg';
		}
	}
	function getProfile($profile_id, $oDataTable = array()){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		if(isset($oDataTable['full_name'])){
			$field = "`full_name`,`first_name`,`last_name`,`avatar`,`level_id`,`rating_star`,`more_information`,`role_id`,`department_id`,`list_department_id`";
			$this->arrResult = $this->getOne($profile_id, $field);
		} else {
			$this->arrResult = $oDataTable;
		}
		$level_id = $this->arrResult['level_id'];
		$rating_star = $this->arrResult['rating_star'];
		$more_information = $this->arrResult['more_information'];
		$department_id = $this->arrResult['department_id'];
		$list_department_id = $this->arrResult['list_department_id'];
		$department_name = $clsProperty->getTitle($department_id);
		#
		$name = $this->getFullName($profile_id, $this->arrResult);
		$avatar = $this->getAvatar($profile_id, $this->arrResult);
		$level = ((int) $level_id > 0) ? $clsProperty->getTitle($level_id) : "";
		$more_information = $clsISO->to_array_json($more_information);
		#
		$role_id = $this->arrResult['role_id'];
		$role = $clsProperty->getCode($role_id);
		return array(
			'name' => $name,
			'avatar' => $avatar,
			'level' => $level,
			'role' => $role,
			'department_id' => $department_id,
			'list_department_id' => $list_department_id,
			'department_name' => $department_name,
			'more_information' => $more_information,
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
	function getTotalOrderInOne($profile_id){
		global $core, $dbconn, $clsISO;
		$clsOrder = new Order();
		return $clsOrder->countItem("profile_id='{$profile_id}'");
	}
	function getTotalMoneyInOne($profile_id){
		global $core, $dbconn, $clsISO;
		$clsOrder = new Order();
		$total = $clsOrder->sumItem("vpc_total_money", "is_trash=0 and profile_id='{$profile_id}'");
		return $clsISO->formatPrice($total);
	}	
	function checkValidUsername($user_name){
		return $this->countItem("`user_name`='{$user_name}' or `email`='{$user_name}'");
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
		if(isset($this->profile_id)){
			$profile_id = $this->profile_id;
		} else {
			$profile_id = 0;
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
						$reg = md5($usr["profile_id"].ENCRYPTION_KEY);
						if($logged_id===$reg){
							$profile_id = $usr['profile_id'];
							break;
						}
					}
				}
			}
		}
		return $profile_id;
    }
	function isLoggedInBkc() {
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
		if(!empty($logged_id) && !empty($logged_key)){
			$field = "{$this->pkey},user_pass";
			$all = $this->getAll("`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'", $field);
			foreach($all as $key => $val){
				$reg = md5($val["profile_id"].ENCRYPTION_KEY);
				if($logged_id===$reg){
					$user_pass = $val['user_pass'];
					$profile_id = $val['profile_id'];
					$this->user_pass = $user_pass;
					$this->profile_id = $profile_id;
					break;
				}
			}
			if($logged_key==md5($this->user_pass.ENCRYPTION_KEY))
				return true;	
			return false;
		}
		return false;
	}
	function isLoggedIn() {
		global $core, $dbconn, $clsISO, $clsCookie;
		$utm_source = Input::get('utm_source', 'web');
		if(trim($utm_source) == 'app'){
			$loggedIn = false;
			$utm_uid = Input::get('utm_uid');
			if(!empty($utm_uid)){
				$cond = "`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
				$cond.= " and MD5(`{$this->pkey}`)='{$utm_uid}'";
				$oneProfile = $this->getByCond($cond);
				if(!empty($oneProfile)){
					$loggedIn = true;
					$user_pass = $oneProfile['user_pass'];
					$profile_id = $oneProfile[$this->pkey];
					$this->profile_id = $profile_id;
					$this->oneProfile = $oneProfile;
					if(defined('_cookie_use') && _cookie_use == 1){
						$clsCookie->putVar('loggedIn',1);
						$clsCookie->putVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
						$clsCookie->putVar('logged_key',md5($user_pass.ENCRYPTION_KEY));
						$clsCookie->setVar();
					} else {
						vnSessionSetVar('loggedIn',1);
						vnSessionSetVar('logged_id', md5($profile_id.ENCRYPTION_KEY));
						vnSessionSetVar('logged_key',md5($user_pass.ENCRYPTION_KEY));
					}
				}
			}
		} else {
			if(defined('_cookie_use') && _cookie_use == 1){
				$logged_id = $clsCookie->getVar('logged_id', 0);
				$logged_key = $clsCookie->getVar('logged_key', "");
			} else {
				$logged_id = vnSessionExist('logged_id') ? vnSessionGetVar('logged_id') : "";
				$logged_key = vnSessionExist('logged_key') ? vnSessionGetVar('logged_key') : "";
			}
			$loggedIn = false;
			if(!empty($logged_id) && !empty($logged_key)){
				$cond = "`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
				$cond.= " and MD5(CONCAT(`{$this->pkey}`,'".ENCRYPTION_KEY."'))='{$logged_id}'";
				$oneProfile = $this->getByCond($cond);
				if(!empty($oneProfile)){
					$user_pass = $oneProfile['user_pass'];
					$profile_id = $oneProfile[$this->pkey];
					if($logged_key == md5($user_pass.ENCRYPTION_KEY)){
						$loggedIn = true;
						$this->profile_id = $profile_id;
						$this->oneProfile = $oneProfile;
					}
				}
			}
		}
		return $loggedIn;
	}
	function userLoggedIn($email, $user_pass, $login_redirect = false) {
		global $core, $dbconn, $clsISO, $clsCookie;
		$clsProfileSession = new ProfileSession();
		if(!$login_redirect){
			$user_pass = $this->encrypt($user_pass);
		}
		$field = "profile_id,user_name,user_pass";
		$tmp = $this->getByCond("`is_active`='1' and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
			and (`user_name`='{$email}' OR `email`='{$email}') and `user_pass`='{$user_pass}' limit 0,1", $field);
		//$clsISO->print_pre($tmp); die();
		if(!empty($tmp)){
			$profile_id = $tmp["profile_id"];
			$profile_log_id = $clsProfileSession->insertLog($profile_id, 'login');
			$fpoint = new FPoint();
			$content= "Đăng nhập vào hệ thống ngày ".date('d/m/Y h:i:s');
			$fpoint->insertPoint('login',$profile_id,$profile_log_id,$content);
			if(defined('_cookie_use') && _cookie_use == 1){
				$clsCookie->putVar('loggedIn', 1);
				$clsCookie->putVar('logged_id', md5($tmp["profile_id"].ENCRYPTION_KEY));
				$clsCookie->putVar('logged_key', md5($tmp["user_pass"].ENCRYPTION_KEY));
				$clsCookie->setVar();
			} else {
				vnSessionSetVar('loggedIn', 1);
				vnSessionSetVar('logged_id', md5($tmp["profile_id"].ENCRYPTION_KEY));
				vnSessionSetVar('logged_key', md5($tmp["user_pass"].ENCRYPTION_KEY));
			}
			return true;
		}else {
			return false;
		}
	}
	function userDoLogout() {
		global $core,$dbconn, $profile_id, $clsCookie;
		$profile_id = $this->getUserID();
		$clsProfileSession = new ProfileSession();
		$clsProfileSession->insertLog($profile_id, 'logout');
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
				return $all[$i]['profile_id'];
			}
		}
		return 0;
	}
	function sendEmailForgot($profile_id, $new_password){
		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO,$header_configs;
		$clsEmailTemplate = new EmailTemplate();
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_FORGOT_PASSWORD_ID);
		$subject = $clsEmailTemplate->getSubject(_MAIL_FORGOT_PASSWORD_ID, $oneEmailTemplate);
		$message = $clsEmailTemplate->getContent(_MAIL_FORGOT_PASSWORD_ID, $oneEmailTemplate);
		// Fallback: template forgot (_MAIL_FORGOT_PASSWORD_ID) co the chua ton tai trong DB clone -> tranh gui subject/content rong (Brevo 400)
		if(trim($subject) === ''){
			$subject = 'Khôi phục mật khẩu'.$header_configs["CompanyName"];
		}
		if(trim(strip_tags($message)) === ''){
			$message = '<p>Xin chào {name},</p><p>Bạn (hoặc ai đó) vừa yêu cầu khôi phục mật khẩu cho tài khoản <b>{user_email}</b>.</p>
			<p>Mật khẩu mới của bạn là: <b>{user_password}</b></p><p>Vui lòng đăng nhập và đổi lại mật khẩu ngay để bảo mật.</p>';
		}
		###
		$oneProfile = $this->getOne($profile_id, "full_name,first_name,last_name,email,user_name");
		$full_name = $this->getFullName($profile_id, $oneProfile);
		$mapField = array(
			'{name}' => $full_name,
			'{user_password}' => $new_password,
			'{user_pass}' => $new_password,
			'{user_email}' => $oneProfile['email'],
		);
		foreach($mapField as $key => $val){
			$subject = str_replace($key, $val, $subject);
			$message = str_replace($key, $val, $message);
		}
		###
		$toemail = trim($oneProfile['email']);
		$toname = $this->getFullName($profile_id, $oneProfile);
		// Gui qua sendEmailSystem (ho tro Brevo/SMTP/Sendgrid) thay sendEmail cu (chi smtp)
		$fromemail = $header_configs["CompanyEmail"];
		$fromname = $header_configs["CompanyName"];
		return $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message);
	}
	function sendMailWellcome($profile_id, $oDataTable = []){
		global $core,$smarty,$dbconn,$clsConfiguration,$clsISO;
		$clsEmailTemplate = new EmailTemplate();
		if(empty($oDataTable)) $oDataTable = $this->getOne($profile_id);
		$staff_name = $this->getFullName($profile_id, $oDataTable);
		$last_name = $this->getLastName($profile_id, $oDataTable, false);
		$more_information = $oDataTable['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$staff_email = $oDataTable['email'];
		$role_name = $more_information["role_name"];
		$department_name = $more_information["department_name"];
		###
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_WELLCOME_STAFF_ID);
		$subject = $clsEmailTemplate->getSubject(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$message = $clsEmailTemplate->getContent(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$fromname = $clsEmailTemplate->getFromName(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		$cc = $clsEmailTemplate->getCopyTo(_MAIL_WELLCOME_STAFF_ID, $oneEmailTemplate);
		###
		$replace = array(
			'{time}' => date('d/m/Y H:i:s'),
			'{last_name}' => $staff_name,
			'{staff_name}' => (!empty($department_name) ? "[".$department_name."]" : "").$staff_name,
			'{staff_email}' => $staff_email
		);
		foreach($replace as $key => $val){
			$subject = str_replace($key, $val, $subject);
			$message = str_replace($key, $val, $message);
		}
		$toname = $staff_name;
		$toemail = trim($staff_email);
		$is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);
		// Return
		return $is_send;
	}
	function checkInGroup($profile_id, $group_id, $oDataTable=null){
		global $clsISO;
		if(!isset($oDataTable['list_group_id'])){
			$oDataTable = $this->GetOne($profile_id,"list_group_id");
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
	function getNumberDayTakeleave($user_id, $year){
		global $core, $clsISO, $clsConfiguration;
		$oneUser = $this->getOne($user_id, "info_takeleave");
		$info_takeleave = $oneUser['info_takeleave'];
		$info_takeleave = $clsISO->to_array_json($info_takeleave);
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
		global $core,$dbconn,$profile_id,$oneProfile,$clsISO;
		$cond = "`is_trash`=0 and `is_active`=1 and `department_id`={$department_id}";
		// $clsISO->print_pre($department_id); die();
		if($department_id==_DEPARTMENT_DIRECTOR_ID){
			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_GD_MANAGER."'","{$this->pkey}");
			return !empty($tmp) ? $tmp[$this->pkey] : 0;
		} else if($department_id == _DEPARTMENT_TECH_ID){
			return _PROFILE_TECH_ID;
		} else if($department_id==_DEPARTMENT_BO_ID){
			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_HEAD_BO."'","{$this->pkey}");
			return !empty($tmp) ? $tmp[$this->pkey] : 0;
		} else if($this->checkInGroupSale($department_id)){
			$tmp = $this->getByCond("{$cond} and (`role_id`='"._ROLE_GD_SALE."' or role_id='"._ROLE_GD_PROJECT."')","{$this->pkey}");
			return !empty($tmp) ? $tmp[$this->pkey] : 0;
		} else {
			$clsProperty = new Property();
			$more_information = $clsProperty->getOneField('more_information', $department_id);
			$more_information = $clsISO->to_array_json($more_information);
			return $core->get_field($more_information, "head_of_dep_id", 0);
		}
	}
	function getDirectorOfDep($department_id){
		global $core,$dbconn,$profile_id,$oneProfile,$clsISO;
		$cond = "`is_trash`=0 and `is_active`=1 and `department_id`={$department_id}";
		// $clsISO->print_pre($department_id); die();
		if($department_id==_DEPARTMENT_DIRECTOR_ID){
			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_GD_MANAGER."'","{$this->pkey}");
			return !empty($tmp) ? $tmp[$this->pkey] : 0;
		} else if($department_id == _DEPARTMENT_TECH_ID){
			return _PROFILE_LTD_ID;
		} else if($department_id==_DEPARTMENT_BO_ID){
			$tmp = $this->getByCond("{$cond} and `role_id`='"._ROLE_HEAD_BO."'","{$this->pkey}");
			return !empty($tmp) ? $tmp[$this->pkey] : 0;
		} else if($this->checkInGroupSale($department_id)){
			$tmp = $this->getByCond("{$cond} and (`role_id`='"._ROLE_GD_SALE."' or role_id='"._ROLE_GD_PROJECT."')","{$this->pkey}");
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
		global $profile_id,$dbconn,$clsISO,$core,$oneProfile;
		$department_id = $oneProfile['department_id'];
		$JSON_GROUP_CURATOR = $clsISO->getVar('JSON_GROUP_CURATOR');
		$JSON_GROUP_CURATOR = $clsISO->to_array_json($JSON_GROUP_CURATOR);
		$JSON_USER_CURATOR = $clsISO->getVar('JSON_USER_CURATOR');
		$JSON_USER_CURATOR = $clsISO->to_array_json($JSON_USER_CURATOR);
		$cond = "`is_trash`=0 and `is_active`=1 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
		if(in_array($department_id, $JSON_GROUP_CURATOR)){
			$cond .= " and (`department_id`='{$department_id}' or `user_id` IN(".implode(',',$JSON_USER_CURATOR)."))";
		}else{
			$cond .= " and `department_id`='{$department_id}'";
		}
		$lstCurator = $this->getAll($cond,"{$this->pkey},`code`,`full_name`,`last_name`,`first_name`");
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
	function getLinkEdit($profile_id, $action='edit'){
		if($action=='edit')
			return '/profile/edit/'.$profile_id.'.html';
		if($action=='bank')
			return '/profile/edit/'.$profile_id.'/bank.html';
		if($action=='password')
			return '/profile/edit/'.$profile_id.'/password.html';
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
		global $clsISO, $profile_id, $oneProfile;
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
			if(!empty($tmp)){
				return $this->getFullName($tmp[$this->pkey], $tmp);
			} else {
				$role_id = _ROLE_HEAD_SALE;
				$tmp = $this->getByCond("`is_trash`=0 and `is_active`=1 and `department_id`='{$department_id}' 
				and `role_id`='{$role_id}'", $field);
				return $this->getFullName($tmp[$this->pkey], $tmp);
			}
		}
	}
	function getQLeader($department_id){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$more_information = $clsProperty->getOneField('more_information', $department_id);
		$more_information = $clsISO->to_array_json($more_information);
		if(isset($more_information['head_of_dep_id']) && !empty($more_information['head_of_dep_id']))
			return $this->getIndentityV3($more_information['head_of_dep_id'], true);
		return "N/A";
	}
	function uploadImageGoogleDriver($files=[],$profile_id){
		$results = array();
		if(!empty($files['name']) && $profile_id >0){ 
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
						$folder_id = $clsGoogleUpload->create_folder($profile_id);
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
	function setPermalink($permalink,$check=1,$profile_id=0){
		$cond = "";
		if($profile_id > 0){
			$cond = " AND profile_id <>'{$profile_id}'";
		}
		$countCheck = $this->countItem("permalink='{$permalink}'".$cond);
		if($countCheck > 0){
			$permalink = $this->setPermalink($permalink.($check+1),++$check, $profile_id); 
		}
		return $permalink;  
	}
	function updateMore($profile_id, $oDataTable = array()){
		global $dbconn, $core, $clsISO;
		$clsProperty = new Property();
		if(!isset($oDataTable['role_id']) 
			&& !isset($oDataTable['department_id']) 
			&& !isset($oDataTable['list_department_id'])){
			$oDataTable = $this->getOne($profile_id, "`role_id`,`department_id`,`list_department_id`,`more_information`");
		} 
		$role_id = $oDataTable['role_id'];
		$department_id = $oDataTable['department_id'];
		$list_department_id = $oDataTable['list_department_id'];
		$more_information = $oDataTable['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$department_arrs = $clsISO->getArrayByTextSlash($list_department_id, ",", []);
		$root_role_id = $clsProperty->getRootId($role_id);
		// $clsISO->print_pre($more_information); die();
		$oneDep = $clsProperty->getOne($department_id, "parent_id");
		$parent_id = $oneDep['parent_id'];
		#- regional_id = vùng kinh doanh: tổ tiên (hoặc chính phòng ban) có more_information.is_business_area=1; 0 nếu không thuộc vùng KD
		$regional_id = $clsProperty->getBusinessAreaId($department_id);
		$role_name = $clsProperty->getTitle($role_id);
		if(in_array(_DEPARTMENT_SALE_ID, $department_arrs) && $parent_id != _DEPARTMENT_SALE_ID){
			$department_name = sprintf('%s-%s', $clsProperty->getTitle($department_id), $clsProperty->getTitle($parent_id));
		} else {
			$department_name = $clsProperty->getTitle($department_id);
		}
		// $clsISO->print_pre($regional_id); die();
		$more_information['role_name'] = $role_name;
		$more_information['root_role_id'] = $root_role_id;
		$more_information['department_name'] = $department_name;
		// $dbconn->debug = true;
		$this->updateOne($profile_id, array(
			'regional_id' => $regional_id,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));
	}
	function getProfileCached($scope = "all"){
		global $core, $dbconn,$clsISO;
		$clsCache = new Cache();
		if($clsCache->has('_list_profile_cached')){
			$tmp = $clsCache->get('_list_profile_cached');
			// $clsCache->delete('_list_profile_cached');
		} else {
			$field = "{$this->pkey},`full_name`,`first_name`,`last_name`,`more_information`,`avatar`";
			$field.= ",`phone`,`email`,`department_id`,`list_department_id`,`role_id`,`status_id`,`total_Lpoint`,`code`,`start_date`,`end_date`";
			$tmp = $this->getAll("`is_trash`=0", $field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val) {
					$more_information = $val["more_information"];
					$more_information = $clsISO->to_array_json($more_information);
					$tmp[$key]['more_information'] = $more_information;
				}
				$clsCache->put('_list_profile_cached', $tmp, 60*60); // Cache 1h
			}
		}
		$arrs = [];
		if(!empty($tmp)){
			foreach($tmp as $key => $val) {
				$id = $val[$this->pkey];
				$status_id = $val['status_id'];
				if($scope == 'active'){
					if($status_id != _STATUS_STAFF_OFF_ID){
						$arrs[$id] = $val;
					}
				} else if($scope == 'all'){
					$arrs[$id] = $val;
				}
			}
		}
		return $arrs;
	}
	function getProfileDep($department_id, $is_all_area = 0,$status = 'all'){
		global $core, $dbconn, $clsISO;
		$arrResult = array();
		$arr_staffs = $this->getProfileCached($status);
		if(!empty($arr_staffs)){
			if((int) $is_all_area == 1){
				foreach($arr_staffs as $key => $val){
					$list_department_id = $val['list_department_id'];
					$department_arrs = $clsISO->getArrayByTextSlash($list_department_id, ",", []);
					if(in_array($department_id, $department_arrs)){
						$arrResult[$key] = $val;
					}
				}
			} else {
				foreach($arr_staffs as $key => $val){
					if($val["department_id"] == $department_id){
						$arrResult[$key] = $val;
					}
				}
			}
		}
		return $arrResult;
	}

	/**
	 * Phòng HCNS của tenant này. Khai báo TẠI ĐÂY chứ không đưa vào config.php:
	 * file đó bị revert nhiều lần, mà quyền xem hồ sơ nhân sự thì không được phép
	 * phụ thuộc vào một file hay biến mất.
	 */
	const DEPARTMENT_HCNS_ID = 12127;
	/** Hồ sơ này có nằm trong nhánh cây của một phòng ban không. */
	function thuocPhongBan($oProfile, $department_id){
		global $core;
		$department_id = (int) $department_id;
		if($department_id <= 0 || empty($oProfile)){
			return false;
		}
		if((int) $core->get_field($oProfile, 'department_id', 0) === $department_id){
			return true;
		}
		// list_department_id mã hoá sẵn tổ tiên dạng |40|82|1358| → 1 phép tìm chuỗi
		$chuoi = (string) $core->get_field($oProfile, 'list_department_id', '');
		return $chuoi !== '' && strpos($chuoi, '|'.$department_id.'|') !== false;
	}
	/**
	 * Người đang đăng nhập có được xem hồ sơ đầy đủ của một nhân sự không.
	 *
	 * ⚠ VIẾT LẠI 28/07 — bản cũ bị mất khi file bị revert, không còn ở bất kỳ đâu
	 * (không có trong backup, lịch sử VS Code, hay 2 repo anh em ca.futurehomes.vn /
	 * dev.myfuture.vn). Luật dưới đây dựng lại từ chú thích còn sót tại 2 chỗ gọi:
	 *   home/sub_default.php:498   "self / BLĐ+HCNS / quản lý xem cấp dưới"
	 *   member/sub_default.php:1480 "self / BLĐ+HCNS toàn Cty / quản lý xem cấp dưới"
	 *
	 * @param ISO   $clsISO      để dùng checkSupper/checkPermissionGroup
	 * @param array $oneProfile  hồ sơ NGƯỜI XEM (biến toàn cục cùng tên)
	 * @param int   $profile_id  id người xem
	 * @param array $dbProfile   hồ sơ NGƯỜI BỊ XEM
	 * @param int   $target_id   id người bị xem
	 * @return bool
	 */
	function canViewProfile($clsISO, $oneProfile, $profile_id, $dbProfile, $target_id){
		global $core;
		$profile_id = (int) $profile_id;
		$target_id = (int) $target_id;
		// Fail-closed: thiếu dữ liệu thì từ chối, đừng cho qua
		if($profile_id <= 0 || $target_id <= 0 || empty($dbProfile)){
			return false;
		}
		// 1. Hồ sơ của chính mình
		if($profile_id === $target_id){
			return true;
		}
		if(!is_object($clsISO)){
			return false;
		}
		// 2. Quản trị hệ thống
		if($clsISO->checkSupper()){
			return true;
		}
		// 3. Ban lãnh đạo: xem được toàn công ty.
		//    Nhóm DIRECTOR tra theo role và các role đó CÓ THẬT ở tenant này
		//    (43 Ban lãnh đạo · 44 Tổng giám đốc · 50 Phó tổng giám đốc).
		if($clsISO->checkPermissionGroup('DIRECTOR')){
			return true;
		}
		// 4. Hành chính nhân sự: xem được toàn công ty.
		//    ⚠ KHÔNG dùng checkPermissionGroup('HR'): nhóm đó tra `_ROLE_HR = [52,60]`,
		//    mà hai role ấy KHÔNG TỒN TẠI ở tenant này (hằng số clone từ hệ thống cha).
		//    HCNS nằm rải ở 6 role khác nhau (360/570/12151/12152/12154/12155)
		//    và 4/16 người còn có `role_id = 0` ⇒ tra theo role là bất khả thi.
		//    Phòng ban mới là dấu hiệu ổn định, nên tra theo nhánh Phòng HCNS.
		if($this->thuocPhongBan($oneProfile, self::DEPARTMENT_HCNS_ID)){
			return true;
		}
		// Giữ thêm đường theo role: vô hại, và tự chạy đúng nếu sau này _ROLE_HR được vá
		if($clsISO->checkPermissionGroup('HR')){
			return true;
		}
		// 5. Quản lý xem cấp dưới — PHẢI đủ cả hai vế:
		//    (a) người xem giữ vai trò quản lý, và
		//    (b) người bị xem nằm trong nhánh cây phòng ban của người xem.
		//    Thiếu vế (a) thì mọi nhân viên cùng phòng xem được hồ sơ của nhau.
		$la_quan_ly = $clsISO->checkPermissionGroup('HEAD_SALE')
			|| $clsISO->checkPermissionGroup('SALE_DIRECTOR')
			|| $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')
			|| $clsISO->checkPermissionGroup('PROJECT_DIRECTOR');
		if(!$la_quan_ly){
			return false;
		}
		$dep_xem = (int) $core->get_field($oneProfile, 'department_id', 0);
		if($dep_xem <= 0){
			return false;
		}
		return $this->thuocPhongBan($dbProfile, $dep_xem);
	}
}
?>