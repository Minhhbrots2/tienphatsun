<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
if(!function_exists('toArray')){
	function toArray($d){
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call*/
			return array_map(__FUNCTION__, $d);
		} else {
			// Return array
			return $d;
		}
	}
}
function shortNumber($num) {
    $units = ['', 'K', 'triệu', 'tỷ', 'T'];
    for ($i = 0; $num >= 1000; $i++) {
        $num /= 1000;
    }
    return round($num, 1) ." ". $units[$i];
}
function default_default(){
	global $assign_list,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $oneProfile,$profile_id,$clsISO;
	$clsCity = new City(); $assign_list["clsCity"] = $clsCity;
	$clsDistrict = new District(); $assign_list["clsDistrict"] = $clsDistrict;
	#
	$clsProfile = new Profile(); 
	$assign_list["clsProfile"] = $clsProfile;
	$assign_list["group_page"] = 'member';
	if(!$clsProfile->isLoggedIn()){
		header("Location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
		exit();
	}
	$_ss_update_sucuess = vnSessionExist('_ss_update_sucuess') 
		? vnSessionGetVar('_ss_update_sucuess') : 0;
	if($_ss_update_sucuess) vnSessionDelVar('_ss_update_sucuess');
	$assign_list["_ss_update_sucuess"] = $_ss_update_sucuess;
	###
	$tabpanel = Input::get('tabpanel','overview');
	$assign_list["tabpanel"] = $tabpanel;
	if($tabpanel=='bank'){
		$list_banks = array();
		$curl = new Curl\Curl();
		$curl->get('https://api.vietqr.io/v2/banks');
		if(!$curl->error){
			$response = $curl->response;
			$response = toArray($response);
			if(isset($response['code']) && $response['code'] == '00'){
				$list_banks = $response['data'];
			}
		}
		$assign_list["list_banks"] = $list_banks;
		// $clsISO->print_pre($list_banks); die();
	}
	###
	$field = "{$clsCity->pkey},title";
	$list_cities = $clsCity->getAll("is_trash=0 and country_id='1' order by order_no ASC", $field);
	if($oneProfile['city_id'] > 0){
		$field = "{$clsDistrict->pkey},title";
		$list_districts = $clsDistrict->getAll("is_trash=0 and city_id='".$oneProfile['city_id']."' 
		order by order_no ASC", $field);
	} else {
		$list_districts = array();
	}
	$assign_list["list_cities"] = $list_cities;
	$assign_list["list_districts"] = $list_districts;
	$more_information = isset($oneProfile['more_information']) && !empty($oneProfile['more_information']) 
		? $oneProfile['more_information'] : array();
	$banks_info = isset($more_information['banks_info']) && !empty($more_information['banks_info']) 
		? $more_information['banks_info'] : array();
	$assign_list["banks_info"] = $banks_info;
	$errorNo = 0; $error_msg = ""; 
	if(isset($_POST['submit'])){
		$tabpanel = Input::post('tabpanel');
		if($_POST['submit'] == 'update_profile'){
			$full_name = Input::post('full_name');
			$birthday = Input::post('birthday');
			$start_date = Input::post('start_date');
			if(!empty($birthday)) $birthday = $clsISO->toDMY($birthday);
			if(!empty($start_date)) $start_date = $clsISO->toDMY($start_date);
			$more_information['linkedin'] = Input::post('linkedin');
			$more_information['instagram'] = Input::post('instagram');
			$more_information['facebook'] = Input::post('facebook');
			$more_information['twitter'] = Input::post('twitter');	
			//$clsISO->print_pre($birthday); die();
			if($clsProfile->updateOne($profile_id, array(
				'full_name' => $full_name,
				'full_name_slug' => $core->replaceSpace($full_name),
				'address' => Input::post('address'),
				'city_id' => Input::post('city_id'),
				'district_id' => Input::post('district_id'),
				'phone' => Input::post('phone'),
				'CCID' => Input::post('CCID'),
				'birthday' => $birthday,
				'start_date' => $start_date,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				vnSessionSetVar('_ss_update_sucuess', 1);
				header('Location:'.$clsISO->getLink('profile'));
				exit();
			}
		} else if($_POST['submit'] == 'update_pass'){
			$errors = array();
			if($oneProfile['oauth_provider']=='_register'){
				$current_password = Input::post('current_password');
				if($clsProfile->encrypt($current_password) != $oneProfile['user_pass']){
					$errorNo += 1;
					$errors[] = 'Mật khẩu hiện tại không khớp';
				}
			}
			$new_password = Input::post('new_password');
			$confirm_password = Input::post('confirm_password');
			if($confirm_password != $new_password){
				$errorNo += 1;
				$errors[] = 'Mật khẩu & xác nhận mật khẩu không khớp';
			}
			// $clsISO->print_pre($errors); die();
			if($errorNo == 0){
				if($clsProfile->updateOne($profile_id, array(
					'user_pass' => $clsProfile->encrypt($new_password)
				))){
					vnSessionSetVar('_ss_update_sucuess', 1);
					header('Location:'.$clsISO->getLink('profile'));
					exit();
				}
			} else {
				foreach($errors as $error){
					$error_msg .= $error . '<br />';
				}
			}
		} else if($_POST['submit'] == 'update_bank'){
			$banks_info = Input::post('banks_info');
			if(!empty($banks_info)){
				foreach($banks_info as $key => $val){
					if(empty($val['account_number']) 
					   && empty($val['account_person']) 
					   && empty($val['bank_name']) 
					   && empty($val['location'])){
						unset($banks_info[$key]);
					}
				}
			}
			$more_information['banks_info'] = $banks_info;
			//$clsISO->print_pre($more_information); die();
			if($clsProfile->updateOne($profile_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				vnSessionSetVar('_ss_update_sucuess', 1);
				header('Location:'.$clsISO->getLink('profile'));
				exit();
			}
		}
	}
	$assign_list["error_msg"] = $error_msg;
	/*=============Title & Description Page==================*/
	$title_page = 'Tài khoản - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
function default_edit(){
	global $assign_list,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page,$clsISO;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	$clsCountry = new Country(); $assign_list["clsCountry"] = $clsCountry;
	$clsDistrict = new District(); $assign_list["clsDistrict"] = $clsDistrict;
	$clsCity = new City(); $assign_list["clsCity"] = $clsCity;
	if(!$clsProfile->isLoggedIn()){
		header("Location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
		exit();
	}
	$tabpanel = Input::get('tabpanel', 'edit');
	$profile_id = (int) Input::get('profile_id', 0);
	//$clsISO->print_pre($profile_id); die();
	$oneProfile = $clsProfile->getOne($profile_id);
	$assign_list["tabpanel"] = $tabpanel;
	$assign_list["profile_id"] = $profile_id;
	$assign_list["oneEditProfile"] = $oneProfile;
	###
	$field = "{$clsCity->pkey},title";
	$list_cities = $clsCity->getAll("`is_trash`=0 and `country_id`='1' order by `order_no` ASC", $field);
	if($oneProfile['city_id'] > 0){
		$field = "{$clsDistrict->pkey},title";
		$list_districts = $clsDistrict->getAll("`is_trash`=0 and `city_id`='".$oneProfile['city_id']."' 
		order by order_no ASC", $field);
	} else {
		$list_districts = array();
	}
	$assign_list["list_cities"] = $list_cities;
	$assign_list["list_districts"] = $list_districts;
	###
	$more_information = $oneProfile['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = $core->get_field($more_information, "banks_info", []);
	$assign_list["banks_info"] = $banks_info;
	$assign_list["more_info"] = $more_information;	###
	$errorNo = 0; $error_msg = ""; 
	if(isset($_POST['submit'])){
		$tabpanel = Input::post('tabpanel');
		if($_POST['submit'] == 'update_profile'){
			$code = Input::post('code');
			$full_name = Input::post('full_name');
			$birthday = Input::post('birthday');
			$start_date = Input::post('start_date');
			if(!empty($birthday)) $birthday = $clsISO->toDMY($birthday);
			if(!empty($start_date)) $start_date = $clsISO->toDMY($start_date);
			$more_information['linkedin'] = Input::post('linkedin');
			$more_information['instagram'] = Input::post('instagram');
			$more_information['facebook'] = Input::post('facebook');
			$more_information['twitter'] = Input::post('twitter');	
			//$clsISO->print_pre($more_information); die();
			if($clsProfile->updateOne($profile_id, array(
				'code' => $code,
				'full_name' => $full_name,
				'full_name_slug' => $core->replaceSpace($full_name),
				'address' => Input::post('address'),
				'city_id' => Input::post('city_id'),
				'district_id' => Input::post('district_id'),
				'phone' => Input::post('phone'),
				'CCID' => Input::post('CCID'),
				'birthday' => $birthday,
				'start_date' => $start_date,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				vnSessionSetVar('_ss_update_sucuess', 1);
				header('Location:'.$clsProfile->getLinkEdit($profile_id, 'edit'));
				exit();
			}
		} else if($_POST['submit'] == 'update_pass'){
			$errors = array();
			if($oneProfile['oauth_provider']=='_register'){
				$current_password = Input::post('current_password');
				if($clsProfile->encrypt($current_password) != $oneProfile['user_pass']){
					$errorNo += 1;
					$errors[] = 'Mật khẩu hiện tại không khớp';
				}
			}
			$new_password = Input::post('new_password');
			$confirm_password = Input::post('confirm_password');
			if($confirm_password != $new_password){
				$errorNo += 1;
				$errors[] = 'Mật khẩu & xác nhận mật khẩu không khớp';
			}
			// $clsISO->print_pre($errors); die();
			if($errorNo == 0){
				if($clsProfile->updateOne($profile_id, array(
					'user_pass' => $clsProfile->encrypt($new_password)
				))){
					vnSessionSetVar('_ss_update_sucuess', 1);
					header('Location:'.$clsProfile->getLinkEdit($profile_id, 'password'));
					exit();
				}
			} else {
				foreach($errors as $error){
					$error_msg .= $error . '<br />';
				}
			}
		} else if($_POST['submit'] == 'update_bank'){
			$banks_info = Input::post('banks_info');
			if(!empty($banks_info)){
				foreach($banks_info as $key => $val){
					if(empty($val['account_number']) 
					   && empty($val['account_person']) 
					   && empty($val['bank_name']) 
					   && empty($val['location'])){
						unset($banks_info[$key]);
					}
				}
			}
			$more_information['banks_info'] = $banks_info;
			if($clsProfile->updateOne($profile_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				header('Location:'.$clsProfile->getLinkEdit($profile_id, 'bank'));
				exit();
			}
		}
	}
	$assign_list["error_msg"] = $error_msg;
	/*=============Title & Description Page==================*/
	$title_page = 'Tài khoản - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_get_select_city(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	#
	$clsCity = new City();
	$country_id = (int) Input::post('country_id',0);
	$html = $clsCity->makeSelectOption($country_id,0);
	// Return
	echo $html; die();
}
function default_get_select_district(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	#
	$clsDistrict = new District();
	$city_id = (int) Input::post('city_id',0);
	$html = $clsDistrict->makeSelectOption($city_id,0);
	// Return
	echo $html; die();
}
function default_notice(){
	global $assign_list,$profile_id,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	#
	$assign_list["group_page"] = 'member';
	if(!$clsProfile->isLoggedIn()){
		header("location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
		exit();
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Thông báo của tôi - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_wishlist(){
	global $assign_list,$profile_id,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	$clsNews = new News(); $assign_list["clsNews"] = $clsNews;
	$clsWishlist = new Wishlist(); $assign_list["clsWishlist"] = $clsWishlist;
	$clsProduct = new Product(); $assign_list["clsProduct"] = $clsProduct;
	#
	$assign_list["group_page"] = 'member';
	if(!$clsProfile->isLoggedIn()){
		header("location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
		exit();
	}
	#
	$lstWishlist = $clsWishlist->getAll("profile_id='{$profile_id}' order by reg_date DESC");
	$assign_list["total"] = !empty($lstWishlist) ? count($lstWishlist) : 0;
	$assign_list["lstWishlist"] = $lstWishlist;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách ưa thích - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_rate(){
	global $assign_list,$profile_id,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	#
	$assign_list["group_page"] = 'member';
	if(!$clsProfile->isLoggedIn()){
		header("location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
		exit();
	}
	#
	/*=============Title & Description Page==================*/
	$title_page = 'Đánh giá sản phẩm - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
function default_makeSelectDistrictOption(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$clsCity = new City();
	$clsDistrict = new District();
	$city_id = (int) Input::post('city_id',0);
	$district_id = (int) Input::post('district_id',0);
	$html = '<option value="">-- Quận/ Huyện --</option>';
	$lstItem = $clsDistrict->getAll("is_trash=0 and city_id='$city_id' order by order_no ASC",$clsDistrict->pkey);
	if(is_array($lstItem) && count($lstItem)>0){
		foreach($lstItem as $k=>$v){
			$selected = ($v[$clsDistrict->pkey]==$district_id)?'selected="selected"':'';
			$html .= '<option value="'.$v[$clsDistrict->pkey].'" '.$selected.'>'.$clsDistrict->getTitle($v[$clsDistrict->pkey]).'</option>';
		}
	}
	echo $html; die();
}
function default_pwd(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $profile_id;
	#
	$assign_list["group_page"] = 'member';
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	#If already login
	if(!$clsProfile->isLoggedIn()){
		header("location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
		exit();
	}
	$isValid = 1;
	if(isset($_POST['submit']) && $_POST['submit']=='pwd'){
		$old_pass = 	isset($_POST['old_pass']) ? trim($_POST['old_pass']):'';
		$new_pass = 	isset($_POST['new_pass']) ? trim($_POST['new_pass']):'';
		$new_cpass = 	isset($_POST['new_cpass']) ? trim($_POST['new_cpass']):'';
		if($old_pass==''){
			$err_old_pass = 'Bạn chưa nhập mật khẩu cũ.<br />';
			$isValid = 0;
		}
		if($old_pass !='' && ($clsProfile->encrypt($old_pass) != $clsProfile->getOneField('user_pass',$profile_id))){
			$err_old_pass .= 'Mật khẩu cũ bạn nhập không chính xác.<br />';
			$isValid = 0;
		}
		$assign_list["err_old_pass"] = $err_old_pass;
		#
		if($new_pass==''){
			$err_new_pass = 'Bạn chưa nhập mật khẩu mới';
			$isValid = 0;
		}
		if($new_pass!='' && strlen($new_pass)<6){
			$err_new_pass = 'Mật khẩu phải có ít nhất 6 ký tự';
			$isValid = 0;
		}
		$assign_list["err_new_pass"] = $err_new_pass;
		#
		if($new_cpass==''){
			$err_new_cpass = 'Bạn chưa nhập xác nhận mật khẩu mới';
			$isValid = 0;
		}
		if($new_pass != '' && $new_cpass != '' && ($new_pass != $new_cpass)){
			$err_new_cpass .= 'Nhập lại mật khẩu mới không khớp';
			$isValid = 0;
		}
		$assign_list["err_new_cpass"] = $err_new_cpass;
		#
		if($isValid){
			$clsProfile->updateOne($profile_id,"user_pass='".$clsProfile->encrypt($new_pass)."'");
			$clsProfile->userDoLogout();
			header('location:/dang-nhap.html');
		}else{
			foreach($_POST as $k=>$v){
				$assign_list[$k] = $v;
			}
		}	
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Đổi mật khẩu - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
function default_change_pass(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	$assign_list["group_page"] = 'member';
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	#If already login
	if($clsProfile->isLoggedIn()){
		header("Location: ".PCMS_URL.'/profile.html');
		exit();
	}
	/** Get Id by temp_reset_key*/
	$temp_reset_key = Input::get('key');
	$tmp = $clsProfile->getAll("temp_reset_key='{$temp_reset_key}' and temp_reset_time>".time()." limit 0,1", $clsProfile->pkey);
	$profile_id = !empty($tmp) ? (int) $tmp[0][$clsProfile->pkey] : 0;
	if($profile_id==0){
		header("Location:/404/");
		exit();
	}
	if(isset($_POST['submit']) && $_POST['submit']=='change'){
		$err_count = 0;
		$err_pass = "";
		$user_pass = trim(Input::post('user_pass'));
		if(empty($user_pass)) {
			$err_count ++;
			$err_pass = 'Bạn không thể để trống Mật khẩu.';
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
			$err_cpass = 'Bạn không thể để trống Nhập mật khẩu.';
		}
		if(!empty($user_pass) && !empty($user_cpass) && $user_pass != $user_cpass){
			$err_count ++;
			$err_cpass = 'Bạn cần nhập lại đúng mật khẩu.';
		}
		$assign_list["err_cpass"] = $err_cpass;
		if($err_count==0){
			if($clsProfile->updateOne($profile_id, array(
				'user_pass' => $clsProfile->encrypt($user_pass),
				'temp_reset_key' => "",
				'temp_reset_time' => 0
			))){
				header('Location:'.PCMS_URL.'/dang-nhap.html?message=success');
				exit();
			}
		}
	}
	/*=============Title & Description Page==================*/
	$title_page = 'Quên mật khẩu - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
function default_add_bank(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$_frontIsLoggedin_user_id;
	$uid = $clsISO->getUniqid();
	$html = '<div class="bank-item">
		<a class="remove_bank" onClick="remove_bank(this, event)"></a>
		<div class="mb-3">
			<label class="colf-form-label">Số tài khoản</label>
			<input type="text" class="form-control account_number numberonly" name="banks_info['.$uid.'][account_number]" 
			placeholder="Số tài khoản" />
		</div>
		<div class="mb-3">
			<label class="colf-form-label">Chủ tài khoản</label>
			<input type="text" class="form-control" name="banks_info['.$uid.'][account_person]" placeholder="Chủ tài khoản" />
		</div>
		<div class="mb-3 row">
			<div class="col-xs-12 col-md-6 pr-0">
				<label class="colf-form-label">Ngân hàng</label>
				<input type="text" class="form-control" mask="Mm/yy" name="banks_info['.$uid.'][bank_name]" placeholder="Tên ngân hàng" />
			</div>
			<div class="col-xs-12 col-md-6">
				<label class="colf-form-label">Chi nhánh</label>
				<input type="text" class="form-control" name="banks_info['.$uid.'][location]" placeholder="Tên chi nhánh (nếu có)" />
			</div>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_itinerary(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsShare = new Share();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	define('START_YEAR', 2024);
	$year = (int) Input::get('year', START_YEAR);
	$role_id = $oneProfile['role_id']; // Id Chức vụ
	$department_id = $oneProfile['department_id']; // Phòng
	$role_name = $clsProperty->getTitle($role_id);
	$department_name = $clsProperty->getTitle($department_id);
	$list_timelines = array();
	$field = "{$clsBilling->pkey},stock_code,deposit_date";
	$list_billings = $clsBilling->getAll("is_trash=0 AND `is_cancel`=0 AND 
		FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}' and staff_id='{$profile_id}' order by reg_date ASC", $field);
	if(!empty($list_billings)){
		foreach($list_billings as $key => $val){
			$list_timelines[] = array(
				'title' => 'Chốt cọc thành công',
				'content' => sprintf('Chúc mừng <strong>%s %s-%s</strong> chốt cọc thành công căn hộ <strong>%s</strong>',
					$role_name,$clsProfile->getFullName($profile_id, $oneProfile), $department_name,
					$val['stock_code']),
				'reg_date' => $val['deposit_date'],
				'icon' => 'fa fa-trophy text-warning',
			);
		}
	}
	$list_shares = $clsShare->getAll("`share_type`='share' and `user_id`='{$profile_id}' 
		and FROM_UNIXTIME(`reg_date`,'%Y')='{$year}' order by `reg_date` DESC");
	if(!empty($list_shares)){
		foreach($list_shares as $key => $val){
			$share_type = $val['share_type'];
			$images = $val['images'];
			$list_images = !empty($images) ? $clsISO->to_array_json($images) : array();
			$html_image = "";
			if(!empty($list_images)){
				$html_image .= '<div>';
				foreach($list_images as $okey => $img){
					$html_image.= '<img class="img-fluid rounded-2" src="'.$img.'" />';
				}
				$html_image.= '<div>';
			}
			$list_timelines[] = array(
				'title' => $val['title'],
				'content' => sprintf('%s', $html_image),
				'reg_date' => $val['reg_date'],
				'icon' => 'bx bx-trip text-primary'
			);
		}
	}
	$list_shares = $clsShare->getAll("`share_type`='honor' and (`staff_id`='{$profile_id}' or `list_staff_id` like '%|{$profile_id}|%') and FROM_UNIXTIME(`reg_date`,'%Y')='{$year}' order by `reg_date` DESC");
	if(!empty($list_shares)){
		foreach($list_shares as $key => $val){
			$share_type = $val['share_type'];
			$images = $val['images'];
			$list_images = !empty($images) ? $clsISO->to_array_json($images) : array();
			$html_image = "";
			if(!empty($list_images)){
				$html_image .= '<div>';
				foreach($list_images as $okey => $img){
					$html_image.= '<img class="img-fluid rounded-2" src="'.$img.'" />';
				}
				$html_image.= '<div>';
			}
			$list_timelines[] = array(
				'title' => '<i class="fa fa-trophy text-warning"></i> Vinh danh bán hàng',
				'content' => sprintf('%s%s', $val['title'], $html_image),
				'reg_date' => $val['reg_date'],
				'icon' => 'fa fa-trophy text-warning'
			);
		}
	}
	$order = array_column($list_timelines, 'reg_date');
	array_multisort($order, SORT_DESC, $list_timelines);
	$assign_list["list_timelines"] = $list_timelines;
	/*=============Title & Description Page==================*/
	$title_page = 'Hành trình - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
}
function default_report(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$start_year = 2023;
	$end_year = $current_year = date('Y');
	$list_years = array();
	for($i=$start_year; $i<=$end_year; $i++){
		$list_years[] = $i;
	}
	$assign_list['list_years'] = $list_years;
	$assign_list['current_year'] = $current_year;
	#
	$total_staff = $total_on = $total_off = $total_sale = $total_bo = 0;
	$sql_query = "{$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
	$tmp = $clsProfile->getAll($sql_query, "`status_id`,`list_department_id`");
	if(!empty($tmp)){
		$total_staff = count($tmp);
		foreach($tmp as $key => $val){
			$status_id = $val['status_id'];
			$list_department_id = $val['list_department_id'];
			$list_department_id_arrs = $clsISO->getArrayByTextSlash($list_department_id);
			if($status_id == _STATUS_STAFF_ON_ID){
				$total_on += 1;
				if(in_array(_DEPARTMENT_SALE_ID, $list_department_id_arrs)){
					$total_sale += 1;
				} else {
					if(!in_array(_DEPARTMENT_DIRECTOR_ID, $list_department_id_arrs)){
						$total_bo += 1;
					}
				}
			} else if($status_id == _STATUS_STAFF_OFF_ID){
				$total_off += 1;
			}
		}
		unset($tmp);
	}
	$assign_list['arr_summary'] = array(
		'total_staff' => $total_staff,
		'total_on' => $total_on,
		'total_off' => $total_off,
		'total_sale' => $total_sale,
		'total_bo' => $total_bo
	);
	/*=============Title & Description Page==================*/
	$title_page = 'Báo cáo nhân sự - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
}
function default_staff_changes(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	#
	$uid = $clsISO->getUniqid();
	$year = Input::post('year', date('Y'));
	#
	$data = $barChartData = array();
	$barChartData['toolTip']["shared"] = true;
	$barChartData['animationEnabled'] = true;
	$dataNewStaffPoints = $dataResignedStaffPoints = array(); 
	for($month = 1; $month<12; $month++){
		$my = sprintf('%s/%s', $clsISO->parseNumber($month), $year);
		$total_new_staffs = $clsProfile->countItem("FROM_UNIXTIME(`start_date`,'%m/%Y')='{$my}'");
		$total_resigned_staffs = $clsProfile->countItem("`status_id`='"._STATUS_STAFF_OFF_ID."' AND FROM_UNIXTIME(`end_date`,'%m/%Y')='{$my}'");
		$dataNewStaffPoints[] = array(
			'label' => sprintf('T%s', $month),
			'y' => (int) $total_new_staffs,
			'indexLabel' => (string) $total_new_staffs
		);
		$dataResignedStaffPoints[] = array(
			'label' => sprintf('T%s', $month),
			'y' => (int) $total_resigned_staffs,
			'indexLabel' => (string) $total_resigned_staffs
		);
	}
	$barChartData['data'] = array(
		array(
			"type"    => "spline",
			"indexLabel" => "{y}",
			"color"	 => "#1d6a01",
			"name"    => "Tiếp nhận",
			"showInLegend" => true,
			"dataPoints"  => $dataNewStaffPoints
		), array(
			"type"  => "spline",
			"indexLabel" => "{y}",
			"axisYType" => "secondary",
			"name"	=> "Nghỉ việc",
			"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataResignedStaffPoints
		)
	);
	$html = '<div class="p-3">
		<div id="'.$uid.'" class="chartContainer h-px-250"></div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function default_month_end_staff_count(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	#
	$uid = $clsISO->getUniqid();
	$year = Input::post('year', date('Y'));
	#
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	for($month=1; $month<12; $month++){
		$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, $month, $year));
		$sql_query = "`is_trash`=0 AND `is_active`=1 AND {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
		$total_staffs = $clsProfile->countItem("{$sql_query} AND `status_id`='"._STATUS_STAFF_ON_ID."' AND `start_date`<='{$end_date}' AND (`end_date` IS NULL OR `end_date`=0 OR `end_date`>='{$end_date}')");
		$dataPoints[] = array(
			'label' => sprintf('T%s', $month),
			'y' => (int) $total_staffs,
			'indexLabel' => $total_staffs
		);
	}
	$html = '<div class="p-3">
		<div id="'.$uid.'" class="chartContainer h-px-250"></div>
	</div>';
	$data['type'] = 'area';
	// $data['legendText'] = '{y}';
	// $data['showInLegend'] = 'False';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function default_staff_count_chart(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	#
	$uid = $clsISO->getUniqid();
	$start_year = 2023;
	$end_year = date('Y');
	#
	$data =  $barChartData = array();
	$dataTotalPoints = $dataActivePoints = $dataOffPoints = array();
	$barChartData['animationEnabled'] = true;
	$sql_query = "{$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
	for($i=$start_year; $i <= ($end_year + 2); $i++){
		if($i > $end_year){
			$total_staffs = 0;
			$total_on_staffs = 0;
			$total_off_staffs = 0;
		} else {
			$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $i);
			$end_date = strtotime(sprintf('%s-%s-%s 23:59:59', $end_day, 12, $i));
			$total_staffs = $clsProfile->countItem("{$sql_query} AND YEAR(FROM_UNIXTIME(`start_date`))<='{$i}' AND (`end_date` IS NULL OR `end_date`=0 OR YEAR(FROM_UNIXTIME(`end_date`))>='{$i}')");
			$total_on_staffs = $clsProfile->countItem("{$sql_query} AND `status_id`='"._STATUS_STAFF_ON_ID."' AND YEAR(FROM_UNIXTIME(`start_date`))<='{$i}' AND (`end_date` IS NULL OR `end_date`=0 OR YEAR(FROM_UNIXTIME(`end_date`))>='{$i}')");
			$total_off_staffs = $total_staffs - $total_on_staffs;
		}
		$dataTotalPoints[] = array(
			'label' => sprintf('Năm %s', $i),
			'y' => (int) $total_staffs
		);
		$dataActivePoints[] = array(
			'label' => sprintf('Năm %s', $i),
			'y' => (int) $total_on_staffs
		);
		$dataOffPoints[] = array(
			'label' => sprintf('Năm %s', $i),
			'y' => (int) $total_off_staffs
		);
	}
	$html = '<div class="p-3">
		<div id="'.$uid.'" class="chartContainer h-px-250"></div>
	</div>';
	$barChartData['data'] = array(
		array(
			"type"    => "column",
			"indexLabel" => "{y}",
			//"color"	 => "#1d6a01",
			"name"    => "Tổng nhân viên",
			"showInLegend" => true,
			"dataPoints"  => $dataTotalPoints
		), array(
			"type"  => "column",
			"indexLabel" => "{y}",
			//"axisYType" => "secondary",
			"name"	=> "Còn làm việc",
			//"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataActivePoints
		), array(
			"type"  => "column",
			"indexLabel" => "{y}",
			//"axisYType" => "secondary",
			"name"	=> "Đã nghỉ việc",
			//"color"	 => "#C00000",
			"showInLegend" => true,
			"dataPoints"   => $dataOffPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function default_staff_dep_pie_chart(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$data = $dataPoints = $barChartData = array();
	$barChartData['animationEnabled'] = true;
	$field = "{$clsProperty->pkey},`title`";
	$sql_query = "`is_active`=1 AND {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
	$list_departments = $clsProperty->getAll("`is_trash`=0 AND `property_type`='_DEPARTMENT' AND `parent_id`=0", $field);
	if(!empty($list_departments)){
		foreach($list_departments as $key => $val){
			$department_id = $val[$clsProperty->pkey];
			$total_staffs = $clsProfile->countItem("{$sql_query} AND `status_id`='"._STATUS_STAFF_ON_ID."' AND (`department_id`='{$department_id}' OR `list_department_id` like '%|{$department_id}|%')");
			$dataPoints[] = array(
				'label' => sprintf('%s', $val['title']),
				'y' => (int) $total_staffs,
				'indexLabel' => sprintf('%s=(%s)', $val['title'], $total_staffs)
			);
		}
	}
	$html = '<div class="p-3">
		<div id="'.$uid.'" class="chartContainer h-px-300"></div>
	</div>';
	$data['type'] = 'pie';
	$data['legendText'] = '{label}';
	$data['showInLegend'] = 'True';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 0,
		'barChartData' => $barChartData
	)); die();
}
function default_staff_dep_line_chart(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$year = Input::post('year', date('Y'));
	#
	$data = $barChartData = array();
	$dataPoints = $dataOnPoints = $dataOffPoints = array();
	$barChartData['animationEnabled'] = true;
	$barChartData['axisX'] = array(
		'titleFontColor' => '#333',
		'lineColor' => '#333',
		'labelFontColor' => '#333',
		'tickColor' => '#333',
		'interval' => 1
	);
	$field = "{$clsProperty->pkey},`title`";
	$list_departments = $dbconn->getAll("SELECT {$field} FROM {$clsProperty->tbl} 
		WHERE `is_trash`=0 AND `property_type`='_DEPARTMENT' AND `parent_id`=0 AND `{$clsProperty->pkey}`<>'"._DEPARTMENT_SALE_ID."' 
		UNION ALL SELECT {$field} FROM {$clsProperty->tbl} 
		WHERE `is_trash`=0 AND `property_type`='_DEPARTMENT' AND `parent_id`='"._DEPARTMENT_SALE_ID."'");
	if(!empty($list_departments)){
		$sql_query = "`is_trash`=0 AND {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
		foreach($list_departments as $key => $val){
			$department_id = $val[$clsProperty->pkey];
			$total_staffs = $total_on_staffs = $total_off_staffs = 0;
			$tmp = $clsProfile->getAll("{$sql_query} AND `department_id`='{$department_id}' AND YEAR(FROM_UNIXTIME(`start_date`))<='{$year}' 
			AND (`end_date` IS NULL OR `end_date`=0 OR YEAR(FROM_UNIXTIME(`end_date`))>='{$year}')", "`status_id`");
			if(!empty($tmp)){
				$total_staffs = count($tmp);
				foreach($tmp as $okey => $oval){
					if($oval['status_id'] == _STATUS_STAFF_ON_ID){
						$total_on_staffs += 1;
					} else if($oval['status_id'] == _STATUS_STAFF_OFF_ID){
						$total_off_staffs += 1;
					}
				}
				unset($tmp);
			}
			$dataPoints[] = array(
				'label' => sprintf('%s', $val['title']),
				'y' => (int) $total_staffs
			);
			$dataOnPoints[] = array(
				'label' => sprintf('%s', $val['title']),
				'y' => (int) $total_on_staffs
			);
			$dataOffPoints[] = array(
				'label' => sprintf('%s', $val['title']),
				'y' => (int) $total_off_staffs
			);
		}
	}
	$html = '<div class="p-3">
		<div id="'.$uid.'" class="chartContainer h-px-300"></div>
	</div>';
	$barChartData['data'] = array(
		array(
			"type"    => "stackedColumn100",
			"indexLabel" => "{y}",
			"indexLabelFontColor"	 => "#FFF",
			"name"    => "Tổng nhân sự",
			"showInLegend" => true,
			"dataPoints"  => $dataPoints
		), array(
			"type"  => "stackedColumn100",
			"indexLabel" => "{y}",
			"name"	=> "Làm việc",
			"indexLabelFontColor"	 => "#FFF",
			"showInLegend" => true,
			"dataPoints"   => $dataOnPoints
		), array(
			"type"  => "stackedColumn100",
			"indexLabel" => "{y}",
			"name"	=> "Nghỉ việc",
			"indexLabelFontColor"	 => "#FFF",
			"showInLegend" => true,
			"dataPoints"   => $dataOffPoints
		)
	);
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => 1,
		'multichart' => 1,
		'barChartData' => $barChartData
	)); die();
}
function default_staff(){
	global $assign_list,$smarty,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsISO,$oneProfile,$deviceType,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsGroupProfile = new GroupProfile();
	$assign_list['clsProfile'] = $clsProfile;
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsGroupProfile'] = $clsGroupProfile;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	// Check permision
	if(!$clsISO->checkPermission('access_staff')){
		$core->redirect('/');
	}
	$is_access_full = $is_dir_sales = 0;
	if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermission('access_staff_all')){
		$is_access_full = 1;
	}
	if($clsISO->checkPermissionGroup('SALE_DIRECTOR') || $clsISO->checkPermissionGroup('REGIONAL_DIRECTOR')){
		$is_dir_sales = 1;
	}
	$assign_list['is_access_full'] = $is_access_full;
	$assign_list['is_dir_sales'] = $is_dir_sales;
	$lstGroupProfile = $clsGroupProfile->getAll("`is_trash`=0 order by reg_date DESC",$clsGroupProfile->pkey.',title,list_profile_id');
	$arr_profiles = [];
	foreach($lstGroupProfile as $key => $value) {
		$arr_profile = $clsISO->getArrayByTextSlash($value["list_profile_id"]);
		$arr_profiles[$value[$clsGroupProfile->pkey]] = $arr_profile;
		if($value[$clsGroupProfile->pkey] == 10) {
			// $clsISO->print_pre($arr_profile);die;
		}
	}
	//	$clsISO->print_pre($lstGroupProfile);die;
	$assign_list['lstGroupProfile'] = $lstGroupProfile;
	##
	$due_range = time();
	$start_range = strtotime('-15 days', $due_range);
	##
	$permiss_login = $clsISO->checkPermissionGroup('DIRECTOR') ? 1 : 0;
	$assign_list['permiss_login'] = $permiss_login;
	$columnNum = ($deviceType=='phone') ? 1 : 3;
	$assign_list['columnNum'] = $columnNum;
	##
	$cond = "`is_trash`=0 and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";
	$keyword = Input::get('keyword');
	$status_ids = Input::get('status_ids', "");
	$status_ids = ($status_ids != "") ? explode(",",$status_ids) : [];
	$date_field = Input::get('date_field', "reg_date");
	$start_date = (int) Input::get('start_date');
	$to_date = (int) Input::get('to_date');
	$block_id = (int) Input::get('block_id',0);
	##
	if(!empty($keyword)){
		$cond .= " and (`full_name_slug` like '%{$core->replaceSpace($keyword)}%' 
			or `email` like '%{$keyword}%' 
			or `phone` like '%{$keyword}%'
			or `code` like '%{$keyword}%'
		)";
	}
	if($block_id > 0) {
		$cond .= " and `block_ids` like '%|".$block_id."|%' ";
	}
	if(!empty($start_date) && !empty($to_date)){
		$cond.= " and ({$date_field} between {$start_date} and {$to_date})";
	}
	$group_profile_id = (int) Input::get('group_profile_id',0);
	if(!empty($group_profile_id)){
		$lstProfile = !empty($arr_profiles[$group_profile_id]) ? $arr_profiles[$group_profile_id] : array();
		$cond .= " and `{$clsProfile->pkey}` IN (".implode(',',$lstProfile).")";
	}
	if($is_access_full){
		$department_id = (int) Input::get('department_id');
		$role_ids = Input::get('role_ids', "");
		$arr_role = !empty($role_ids) ? @explode(",",$role_ids) : array();
		$assign_list['department_id'] = $department_id;
		$assign_list['role_id'] = $role_id;
		$assign_list['role_ids'] = $arr_role;
		$list_teams = array();
		if($department_id > 0){
			$cond .= " and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
			$tf_field = "{$clsProperty->pkey},title";
			$list_teams = $clsProperty->getAll("`parent_id`='{$department_id}' order by `order_no` ASC", $tf_field);
		}
		if(!empty($arr_role)) $cond .= " and `role_id` IN (".implode(',',$arr_role).")";
		$cnd = $cond;
		if(!empty($status_ids)) $cond .= " and `status_id` IN (".implode(",",$status_ids).")";
		$total_staff = $clsProfile->countItem($cnd);
		$total_on = $clsProfile->countItem($cnd." and `status_id`='"._STATUS_STAFF_ON_ID."'");
		$total_off = $clsProfile->countItem($cnd." and `status_id`='"._STATUS_STAFF_OFF_ID."'");
		$total_sale = $clsProfile->countItem($cond." and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."|%' ");
		$total_bo = $clsProfile->countItem($cond." and `list_department_id` not like '%|"._DEPARTMENT_SALE_ID."|%' and `department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'");
		$total_growth = $clsProfile->countItem($cond." and (`reg_date` between {$start_range} AND '{$due_range}')");
		$assign_list['arr_totals'] = array(
			'total_staff' => $total_staff,
			'total_on' => $total_on,
			'total_off' => $total_off,
			'total_sale' => $total_sale,
			'total_bo' => $total_bo,
			'total_growth' => $total_growth
		);
		$assign_list['list_teams'] = $list_teams;
	} else if($is_dir_sales){
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		$cond.= " and (`department_id`='{$department_id}' or `list_department_id` like '%|{$department_id}|%')";
		$cnd = $cond;
		if(!empty($status_ids)) $cond .= " and `status_id` IN (".implode(",",$status_ids).")";
	}
	$assign_list['keyword'] = $keyword;
	$assign_list['date_field'] = $date_field;
	$assign_list['status_ids'] = $status_ids;
	$assign_list['start_date'] = !empty($start_date) ? date('Y-m-d', $start_date) : "";
	$assign_list['to_date'] = !empty($to_date) ? date('Y-m-d', $to_date) : "";
	if(isset($_POST['filter']) && $_POST['filter'] =='filter'){
		$link = '/staff.html';
		$keyword = Input::post('keyword', "");
		$role_ids = Input::post('role_ids', array());
		$department_id = (int) Input::post('department_id', 0);
		$group_profile_id = (int) Input::post('group_profile_id', 0);
		$status_ids =Input::post('status_ids', [_STATUS_STAFF_ON_ID]);
		$date_field = Input::post('date_field', "reg_date");
		$start_date = Input::post('start_date');
		$to_date = Input::post('to_date');
		$hasCond = false;
		if(!empty($keyword)){
			$link .= ($hasCond?'&':'?') . 'keyword='.$keyword;
			$hasCond = true;
		}
		if($department_id > 0){
			$link .= ($hasCond?'&':'?') . 'department_id='.$department_id;
			$hasCond = true;
		}
		if($group_profile_id > 0){
			$link .= ($hasCond?'&':'?') . 'group_profile_id='.$group_profile_id;
			$hasCond = true;
		}
		if(!empty($role_ids)){
			$link .= ($hasCond?'&':'?') . 'role_ids='.implode(",",$role_ids);
			$hasCond = true;
		}
		if($status_ids >= 0){
			$link .= ($hasCond?'&':'?') . 'status_ids='.implode(",",$status_ids);
			$hasCond = true;
		}
		if(!empty($date_field)){
			$link .= ($hasCond?'&':'?') . 'date_field='.$date_field;
			$hasCond = true;
		}
		if(!empty($start_date) && !empty($to_date)){
			$start_date = $clsISO->toTime($start_date);
			$to_date = $clsISO->toTime(sprintf('%s 23:59:59', $to_date));
			$link .= ($hasCond?'&':'?') . 'start_date='.$start_date.'&to_date='.$to_date;
		}
		header('Location:' . $link);
		exit();
	}
	#- Begin pagination
	$current_page = Input::get('page',1);
	$per_page = Input::get('per_page',1000);
	$total_record = $clsProfile->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	// $clsProfile->setDeBug(1);
	$list_staffs = $clsProfile->getAll($cond. " order by `code` DESC".$limitCond);
	// $clsISO->print_pre($list_staffs);die;
	$arr_role_cached = $clsProperty->getArraySearchByKey("_ROLE");
	$arr_department_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if(!empty($list_staffs)){
		$arrCached = array();
		$prop_field = "{$clsProperty->pkey},`title`";
		$list_props = $clsProperty->getAll("`property_type`='_STATUS_STAFF'", $prop_field);
		if(!empty($list_props)){
			foreach($list_props as $key => $val){
				$arrCached[$val[$clsProperty->pkey]] = $val['title'];
			}
		}
		foreach($list_staffs as $key => $val){
			$_profile_id = $val[$clsProfile->pkey];
			$reg_date = $val['reg_date'];
			$department_id = $val['department_id'];
			$role_id = $val['role_id'];
			$status_id = $val['status_id'];
			$start_date = $val['start_date'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$more_information["role_name"] = $arr_role_cached[$role_id]["title"];
			if($more_information["department_name"] == ""){
				$department_arrs = $clsISO->getArrayByTextSlash($val["list_department_id"], ",", []);
				$oDep = $arr_department_cached[$department_id];
				if(in_array(_DEPARTMENT_SALE_ID, $department_arrs) && !empty($oDep["parent_id"]) && $oDep["parent_id"] != _DEPARTMENT_SALE_ID){
					$department_name = sprintf('%s-%s', $arr_department_cached[$department_id]["title"], $arr_department_cached[$oDep["parent_id"]]["title"]);
				} else {
					$department_name = $arr_department_cached[$department_id]["title"];
				}
				$more_information["department_name"] = $department_name;
				
			}
			$list_staffs[$key]['more_information'] = $more_information;
			$state_name = "";
			if($reg_date > $start_range && $reg_date < $due_range){
				$state_name = '<span class="badge bg-label-danger">Mới</span>';
			}
			$social_channels = !empty($more_information["social_channels"]) ? $more_information["social_channels"] : [];
			$list_staffs[$key]['total_social'] = count($social_channels);
			$list_staffs[$key]['state_name'] = $state_name;
			# status
			if($status_id > 0 && isset($arrCached[$status_id])){
				$list_staffs[$key]['status_name'] = $arrCached[$status_id];
			} else {
				$list_staffs[$key]['status_name'] = 'N/A';
			}
			$deposit_last_time = 0;
			$sql_cond = "`is_trash`=0 and `is_cancel`=0 AND `staff_id`='{$_profile_id}'";	
			$tmp = $clsBilling->getByCond($sql_cond." ORDER BY `deposit_date` DESC","deposit_date");
			if(!empty($tmp)){
				// $clsISO->print_pre($tmp); die();
				$deposit_last_time = (int) $tmp['deposit_date'];
				unset($tmp);
			}
			$list_staffs[$key]['deposit_date'] = $deposit_last_time > 0 ? $clsISO->formatDate($deposit_last_time,5) : "--";
			if($start_date > 0 && $to_date == 0){
				$sql_cond.= " and (`deposit_date`>='{$start_date}')";
			} else if($start_date == 0 && $to_date > 0){
				$sql_cond.= " and `deposit_date`<='{$to_date}'";
			} else if($start_date > 0 && $to_date > 0){
				$sql_cond.= " and (`deposit_date` between {$start_date} and {$to_date})";
			}
			$total_billings = $clsBilling->sumItem("totalgrand", $sql_cond." and `staff_id`='{$_profile_id}'");
			// $list_staffs[$key]['total_ds'] = $total_billings;
			$list_staffs[$key]['total_billings'] = $clsISO->shortNumber($total_billings);
		}
		// $arr_orders = array_column($list_staffs, "total_ds");
		// array_multisort($arr_orders, SORT_DESC, $list_staffs);
	}
	$assign_list['list_staffs'] = $list_staffs;
	// $clsISO->print_pre($list_staffs); die();
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('current_page', $current_page);
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link_page_1'	=> '/staff.html',
		'link' => '/staff/'
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(true);
	$assign_list["html_pager"] = $html_pager;
	/*=============Title & Description Page==================*/
	$title_page = 'Danh sách nhân viên - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
}
function default_add_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id;
	$clsIssue = new Issue();
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsIssue', $clsIssue);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$type = Input::post("type","open");
	$group_id = (int)Input::post("group_id",0);
	if($group_id > 0) {
		$oneGroup = $clsGroupProfile->getOne($group_id);
	}
	$smarty->assign('type', $type);
	$smarty->assign('group_id', $group_id);
	$smarty->assign('oneGroup', $oneGroup);
	$data = ["result" => false];
	if($type == "open") {
		$field = "{$clsProfile->pkey},full_name,code";
		$list_staffs = $clsProfile->getAll("is_trash=0 and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."' 
		and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by code ASC", $field);
		$smarty->assign('list_staffs', $list_staffs);
		// Return
		$uid = $clsISO->getUniqid();
		$smarty->assign('uid', $uid);
		$html = $core->build('_ajax.group.tpl');
		$data = [
			"result"	=>	true,
			'uid' => $uid,
			'html' => $html
		];
	}else if($type == "save") {
		$group_id = (int) Input::post("group_id",0);
		$title = Input::post("title","");
		$staff_ids = Input::post("staff_ids",[]);
		if($group_id == 0) {
			$clsGroupProfile->insert(array(
				$clsGroupProfile->pkey 	=> $clsGroupProfile->getMaxID(),
				'title' 				=> addslashes($title),
				'list_profile_id' 		=> $clsISO->makeSlashListFromArrayRoot($staff_ids),
				'user_id'			 	=> $profile_id,
				'user_id_update' 		=> $profile_id,
				'reg_date' 				=> time(),
				'upd_date' 				=> time(),
			));
			$data = [
				"result"	=>	true,
			];
		}else{
			$clsGroupProfile->updateOne($group_id,array(
				'title' 			=> addslashes($title),
				'list_profile_id' 	=> $clsISO->makeSlashListFromArrayRoot($staff_ids),
				'user_id_update' 	=> $profile_id,
				'upd_date' 			=> time()
			));			
			$data = [
				"result"	=>	true,
			];
		}
	}
	echo json_encode($data); die();
}
function default_delete_group(){
	global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
	$clsIssue = new Issue();
	$clsGroupProfile = new GroupProfile();
	$group_id = (int) Input::request('group_id',0);
	#
	$msg= "_error";
	if($clsGroupProfile->getOneField('user_id',$group_id) == $profile_id){
		if($clsGroupProfile->deleteOne($group_id)){
			$msg = "_success";
		}
	} else {
		$msg = "_invaid";
	}
	// Return
	echo $msg; die();
}
function default_manage_group(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsCourse= new Course();
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$lstGroupProfile = $clsGroupProfile->getAll("`is_trash`=0 order by reg_date DESC");
	foreach($lstGroupProfile as $key => $value) {
		$arr_profile = $clsISO->getArrayByTextSlash($value["list_profile_id"]);
		/*if(!empty($arr_profile)) {
			$lstProfile = $clsProfile->countItem("profile_id IN (".implode(',',$arr_profile).")");
			$lstGroupProfile[$key]['lstProfile'] = $lstProfile;
			$lstGroupProfile[$key]['total'] = !empty($lstProfile) ? count($lstProfile) : 0;
			if($value[$clsGroupProfile->pkey] == 10) {
				$clsISO->print_pre($lstProfile);die;
			}
		}*/
		$lstGroupProfile[$key]['total'] = count($arr_profile);
		// var_dump($arr_profile);die;
	}
	$smarty->assign('lstGroupProfile', $lstGroupProfile);
	// Return
	$html = $core->build('_ajax.list_group.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_load_list_profile_popover(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile;
	//ini_set('display_errors',1);
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsGroupProfile = new GroupProfile();
	##
	$group_id = (int) Input::get('group_id', 0);
	$oGroup = $clsGroupProfile->getOne($group_id);
	$arr_profile = $clsISO->getArrayByTextSlash($oGroup["list_profile_id"]);
	$html ="";
	if(!empty($arr_profile)) {
		$lstProfile = $clsProfile->getAll("profile_id IN (".implode(',',$arr_profile).")");
		$html = '<div class="d-flex flex-column gap-2 p-2">
				<h3 class="fs-16 mb-0 text-dark">Danh sách nhân viên nhóm <span class="fw-bold">'.$oGroup["title"].'</span></h3>
				<div class="lst_staff overflow-y-auto d-flex flex-column gap-2 p-2">';
		foreach($lstProfile as $key => $value) {
			$more_information = $clsISO->to_array_json($value['more_information']);
			$html .= '<a href="javascript:void(0);" onClick="$Core.member.view_profile(this, event)" 
					profile_id="'.$value['profile_id'].'" class="d-flex gap-2 align-items-center py-2 border-bottom">
						<div class="avatar avatar-xxs position-relative rounded-pill">
							<img class="rounded-pill" src="'.$clsProfile->getAvatar($value['profile_id'],$value,40,40).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />
							'.$clsProfile->get_icon_verified($value['profile_id'], $more_information).'
						</div>
						<strong>'.$value["full_name"].'</strong>
					</a>';
		}
		$html .= '</div></div>';
	}	
	// Return
	echo $html; die();
}
function default_group_participants(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile();
	#
	$html = "";
	$participants = Input::post('participants', array());
	if(!empty($participants)){
		foreach($participants as $profile_id){
			$html.= '<div class="issue_participant">
				'.$clsProfile->getIndentityV3($profile_id, true).'
			</div>';
		}
	}
	// Return
	echo $html; die();
}
function default_open_cropper(){
	//ini_set('display_errors', 1);
	//error_reporting(E_ALL ^ E_NOTICE);
	global $smarty,$_frontIsLoggedin_user_id,$core,$clsISO;
	#
	$uid = $clsISO->getUniqid();
	$openFrom = Input::post('openFrom', 'image');
	$profile_id = Input::post('profile_id', 0);
	$imgdata = Input::post('imgdata');
	$smarty->assign('objectUrl', $imgdata);
	$smarty->assign('openFrom', $openFrom);
	// Return
	$smarty->assign('uid', $uid);
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.cropper.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_upload_avatar(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,
	$keyword_page,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_lang,$clsConfiguration;
	#
	$clsProfile = new Profile();
	$profile_id = (int) Input::post('profile_id' , 0);
	$imgdata = Input::post('imgdata');
	$filename = Input::post('filename');
	if(!$filename) $filename = $clsISO->getUniqid().'.jpg';
	#
	$avatar = ''; 
	$msg = 'error';
	if($imgdata){
		$clsUploadFile = new UploadFile();
		$avatar = $clsUploadFile->base642imagejpeg($imgdata, $filename, "/avatar");
	}
	if(!empty($avatar) && file_exists(ROOTPATH.$avatar)){
		$old_avatar = $clsProfile->getOneField('avatar', $profile_id);
		if(!empty($old_avatar) && @file_exists(ROOTPATH.$old_avatar)){
			@unlink(ROOTPATH.$old_avatar);
		}
		if($clsProfile->updateOne($profile_id, "`avatar`='".addslashes($avatar)."'")){
			$msg = 'success';
		}
	}
	// Return
	echo $msg.'|||'.$avatar; die();
}
function default_open_change_pass(){
	global $smarty, $core, $clsISO, $profile_id;
	$clsProfile = new Profile();
	if(!$clsProfile->isLoggedIn()){
		echo json_encode(array('html' => '', 'uid' => '')); die();
	}
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.change_pass.tpl');
	echo json_encode(array('html' => $html, 'uid' => 'open_change_pass__'.$uid)); die();
}
function default_save_change_pass(){
	global $clsISO, $profile_id;
	$clsProfile = new Profile();
	if(!$clsProfile->isLoggedIn() || (int) $profile_id <= 0){
		echo json_encode(array('msg' => '_error', 'text' => 'Phiên đăng nhập đã hết hạn.')); die();
	}
	$user_pass = trim(Input::post('user_pass'));
	$user_cpass = trim(Input::post('user_cpass'));
	if($user_pass === '' || strlen($user_pass) < 6){
		echo json_encode(array('msg' => '_error', 'text' => 'Mật khẩu ít nhất 6 ký tự.')); die();
	}
	if($user_pass !== $user_cpass){
		echo json_encode(array('msg' => '_error', 'text' => 'Mật khẩu nhắc lại không khớp.')); die();
	}
	$ok = $clsProfile->updateOne((int) $profile_id, array('user_pass' => $clsProfile->encrypt($user_pass)));
	if($ok){
		echo json_encode(array('msg' => '_success')); die();
	}
	echo json_encode(array('msg' => '_error', 'text' => 'Có lỗi khi lưu. Vui lòng thử lại.')); die();
}
function default_save_secondary(){
	global $core,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#-- Chặn server-side: chỉ người có quyền sửa nhân sự
	if(!$clsISO->checkPermission('edit_staff')){
		echo json_encode(array('result'=>false,'msg'=>'Bạn không có quyền sửa nhân sự.')); die();
	}
	$p_id = (int) Input::post('p_id', 0);
	$sec_dep = (int) Input::post('secondary_department_id', 0);
	$sec_role = (int) Input::post('secondary_role_id', 0);
	if($p_id <= 0){
		echo json_encode(array('result'=>false,'msg'=>'Thiếu hồ sơ nhân viên.')); die();
	}
	#--Phân quyền: không cho tự gán vai trò phụ cho chính mình
	if($p_id == $profile_id){
		echo json_encode(array('result'=>false,'msg'=>'Bạn không được tự gán vai trò cho chính mình.')); die();
	}
	$more_information = $clsProfile->getOneField('more_information', $p_id);
	$more_information = $clsISO->to_array_json($more_information);
	if($sec_dep > 0 && $sec_role > 0){
		$more_information['secondary'] = array(
			'role_id' => $sec_role,
			'department_id' => $sec_dep,
			'list_department_id' => $clsProperty->getListParent($sec_dep),
		);
		$current = sprintf('%s – %s', $clsProperty->getTitle($sec_dep), $clsProperty->getTitle($sec_role));
		$msg = 'Đã lưu vai trò phụ.';
	} else {
		unset($more_information['secondary']);
		$current = 'Chưa gán';
		$msg = 'Đã xoá vai trò phụ.';
	}
	$clsProfile->updateOne($p_id, array('more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)));
	$clsISO->clean_cache('profile');
	echo json_encode(array('result'=>true,'msg'=>$msg,'current'=>$current)); die();
}
function default_load_option_role_secondary(){
	global $core,$clsISO;
	$clsProperty = new Property();
	$html_role_options = '<option value="0">Chọn vai trò</option>';
	$department_id = (int) Input::post('department_id', 0);
	if($department_id > 0){
		$arrOption = array();
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$clsProperty->makeOption($for_id, '_ROLE', 0, $arrOption);
		if(!empty($arrOption)){
			foreach($arrOption as $key => $val){
				$html_role_options .= sprintf('<option value="%s">%s</option>', $key, $val);
			}
		}
	}
	echo json_encode(array('html_role_options' => $html_role_options)); die();
}
function default_view_profile(){
	global $clsISO,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$profile_id,$_loggedIn,$clsConfiguration,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsBilling', $clsBilling);
	###
	$uid = $clsISO->getUniqid(); 
	$_profile_id = (int) Input::post('profile_id', 0);
	$dbProfile = $clsProfile->getOne($_profile_id);
	// Chặn server-side: chỉ được xem hồ sơ trong phạm vi quyền (self / BLĐ+HCNS toàn Cty / quản lý xem cấp dưới)
	if(!$clsProfile->canViewProfile($clsISO, $oneProfile, $profile_id, $dbProfile, $_profile_id)){
		echo json_encode(array(
			'uid' => $uid,
			'html' => '<div class="p-5 text-center text-muted">
				<i class="bx bx-lock-alt fs-1 d-block mb-2"></i>Bạn không có quyền xem hồ sơ nhân sự này.
			</div>'
		)); die();
	}
	$action_logs = $dbProfile['action_logs'];
	$more_information = $dbProfile['more_information'];
	$action_logs = $clsISO->to_array_json($action_logs);
	$more_information = $clsISO->to_array_json($more_information);
	$social_channels = $core->get_field($more_information, 'social_channels', []);
	#
	$smarty->assign('action_logs', $action_logs);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('social_channels', $social_channels);
	#
	$lstAllBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$block_ids = !empty($dbProfile['block_ids']) ? $clsISO->getArrayByTextSlash($dbProfile['block_ids']) : [];
	$block_name = "";
	$bFirst = 0;
	foreach($block_ids as $key => $block_id) {
		if($block_id > 0 && isset($lstAllBlock[$block_id])) {
			$block_name .= (($bFirst > 0)? ", ":"") . $lstAllBlock[$block_id]['title'];
			$bFirst = 1;
		}		
	}
	$dbProfile['block_name'] = $block_name;
	#--Multi-role: prefill vai trò phụ (kiêm nhiệm) cho popup chi tiết
	$secondary_saved = isset($more_information['secondary']) ? $more_information['secondary'] : array();
	$sec_dep_id = (int) $core->get_field($secondary_saved, 'department_id', 0);
	$sec_role_id = (int) $core->get_field($secondary_saved, 'role_id', 0);
	$html_role_options_secondary = '<option value="0">Chọn vai trò</option>';
	if($sec_dep_id > 0){
		$sec_for_id = $clsProperty->getOneField('for_id', $sec_dep_id);
		$sec_arrOption = array();
		$clsProperty->makeOption($sec_for_id, '_ROLE', 0, $sec_arrOption);
		if(!empty($sec_arrOption)){
			foreach($sec_arrOption as $skey => $sval){
				$html_role_options_secondary .= sprintf('<option value="%s"%s>%s</option>', $skey, ($sec_role_id==$skey?' selected':''), $sval);
			}
		}
	}
	$smarty->assign('sec_dep_id', $sec_dep_id);
	$smarty->assign('sec_role_id', $sec_role_id);
	$smarty->assign('html_role_options_secondary', $html_role_options_secondary);
	###
	$sql_cond = "`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$_profile_id}'";
	$total_billings = $clsBilling->sumItem("totalgrand", $sql_cond);
	$smarty->assign('total_billings', $clsBilling->countItem($sql_cond));
	$smarty->assign('total_revenues', shortNumber($total_billings));
	##
	$smarty->assign('uid', $uid);
	$smarty->assign('_profile_id', $_profile_id);
	$smarty->assign('dbProfile', $dbProfile);
	##
	$permis_edit = ($profile_id==$_profile_id) ? 1 : 0;
	$permiss_edit_full = ($clsISO->checkPermission('edit_staff')) ? 1 : 0;
	$smarty->assign('permis_edit', $permis_edit);
	$smarty->assign('permiss_edit_full', $permiss_edit_full);
	// Return
	$html = $core->build('_ajax.staff.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_social_channels(){
	global $clsISO,$core,$smarty,$oneProfile,$profile_id;
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$_profile_id = (int) Input::post('profile_id', 0);
	$more_information = $clsProfile->getOneField('more_information', $_profile_id);
	$more_information = $clsISO->to_array_json($more_information);
	$social_channels = $core->get_field($more_information, 'social_channels', []);
	$smarty->assign('uid', $uid);
	$smarty->assign('_profile_id', $_profile_id);
	$smarty->assign('social_channels', $social_channels);
	// Return
	$html = $core->build('_ajax.social_channels.tpl');
	echo json_encode(['uid' => $uid, 'html' => $html]); die();
}
function default_save_social_channels(){
	global $clsISO,$core,$dbconn,$profile_id;
	$clsProfile = new Profile();
	$msg = "_error";
	$_profile_id = (int) Input::post('profile_id', 0);
	$social_channels = json_decode(Input::post('social_channels', '[]'), true);
	if($_profile_id > 0 && is_array($social_channels)){
		$more_information = $clsProfile->getOneField('more_information', $_profile_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['social_channels'] = $social_channels;
		if($clsProfile->updateOne($_profile_id, [
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		])){
			$msg = "_success";
		}
	}
	echo json_encode(['msg' => $msg]); die();
}
function default_load_option_role(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	#
	$html = '<option value="0">Vai trò/ Quyền hạn</option>';
	$department_id = (int) Input::post('department_id', 0);
	$type = Input::post('type', "");
	if($department_id > 0){
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$arrOption = array();
		$clsProperty->makeOption($for_id, '_ROLE', 0, 0, $arrOption);
		if(!empty($arrOption)){
			foreach($arrOption as $key => $val){
				$html .= sprintf('<option value="%s">%s</option>', $key, $val);
			}
		}
	}else if($type == "show_all"){
		$html = $clsProperty->getSelectByPropertyV2('_ROLE',0,'Vai trò/ Quyền hạn');
	}
	// Return
	echo $html; die();	
}
function default_load_edit_inline_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	##
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action','_open');
	##
	#--Phân quyền: không cho tự sửa Phòng ban / Vai trò của chính mình
	if($p_id > 0 && $p_id == $profile_id && in_array($p_field, array('department_id','role_id'))){
		echo 'Bạn không được tự chỉnh sửa phòng ban / vai trò của mình.'; die();
	}
	if($p_action=='_save'){
		$oProfile = $clsProfile->getOne($p_id);
		$action_logs = $oProfile['action_logs'];
		$action_logs = $clsISO->to_array_json($action_logs);
		#
		$p_value = Input::post('p_value');
		if($p_field=='code'){
			if($clsProfile->countItem("`code`='{$p_value}'") > 0){
				echo '_error_code'; die();
			} else {
				$action_logs[$clsISO->getUniqid()] = array(
					'user_id' => $profile_id,
					'reg_date' => time(),
					'content' => sprintf('<strong>%s</strong> đã cập nhật Mã nhân viên thành: <strong>%s</strong>', 
						$clsProfile->getFullName($profile_id, $oneProfile), $p_value)
				);
				$clsProfile->updateOne($p_id, array(
					$p_field => $p_value,
					'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
				));
			}
		} else if($p_field=='email'){
			if($clsProfile->countItem("`email`='{$p_value}'") > 0){
				echo '_error_email'; die();
			} else {
				$action_logs[$clsISO->getUniqid()] = array(
					'user_id' => $profile_id,
					'reg_date' => time(),
					'content' => sprintf('<strong>%s</strong> đã cập nhật E-mail thành: <strong>%s</strong>', 
						$clsProfile->getFullName($profile_id, $oneProfile), $p_value)
				);
				$clsProfile->updateOne($p_id, array(
					$p_field => $p_value,
					'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
				));
			}
		} else if($p_field=='department_id'){
			$parts = explode('||', $p_value);
			$department_id = (int) $parts[0];
			###
			if($department_id > 0 && $department_id != $oProfile['department_id']){
				$action_logs[$clsISO->getUniqid()] = array(
					'user_id' => $profile_id,
					'reg_date' => time(),
					'content' => sprintf('<strong>%s</strong> đã cập nhật Phòng ban thành: <strong>%s</strong>', 
						$clsProfile->getFullName($profile_id, $oneProfile), $clsProperty->getTitle($department_id))
				);
			}
			$list_department_id = (int) $department_id > 0 ? $clsProperty->getListParent($department_id) : "";
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => $content
			);
			$clsProfile->updateOne($p_id, array(
				$p_field => $department_id,
				'list_department_id' => $list_department_id,
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
			$clsProfile->updateMore($p_id, []);
		} else if($p_field == 'is_star_club'){
			$more_information = $oProfile['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật tình trạng CLBNS thành: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), ($p_value == 1? 'Có' : 'Không'))
			);
			$more_information[$p_field] = $p_value;
			$clsProfile->updateOne($p_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
		} else if(in_array($p_field, array('twitter','facebook','linkedin','instagram'
			,'issue_number','issue_date','issue_location','zaloId'))){
			if($p_field == 'twitter') $action_name = "Twitter";	
			if($p_field == 'facebook') $action_name = "Facebook";
			if($p_field == 'linkedin') $action_name = "Linkedin";
			if($p_field == 'instagram') $action_name = "Instagram";
			if($p_field == 'issue_number') $action_name = "Số CMND";
			if($p_field == 'issue_date') $action_name = "Ngày cấp CMND";
			if($p_field == 'issue_location') $action_name = "Nơi cấp CMND";
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật %s thành: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_name, $p_value)
			);
			$more_information = $clsProfile->getOneField('more_information', $p_id);
			$more_information = $clsISO->to_array_json($more_information);
			if($p_field=='issue_date'){
				$parts = explode('||', $p_value);
				$p_value = $parts[0];
				$issue_time = $parts[1];
				$p_value = !empty($p_value) ? $clsISO->toTime($p_value) : 0;
				$more_information['issue_time'] = $issue_time;
			}
			$more_information[$p_field] = $p_value;
			$clsProfile->updateOne($p_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
		} else if($p_field=='level_id'){
			$more_information = $oProfile['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$level_logs = $core->get_field($more_information, "level_logs", []);
			if(!empty($level_logs)){
				if(function_exists('array_key_last')){
					$last_id = array_key_last($level_logs);
				} else {
					end($level_logs);
					$last_id = key($level_logs);
				}
				$level_logs[$last_id]['end_date'] = time();
				$level_logs[$clsISO->getUniqid()] = array(
					'level_id' => $p_value,
					'start_date' => time(),
					'end_date' => "",
					'user_id' => $profile_id
				);
			} else {
				$level_logs[$clsISO->getUniqid()] = array(
					'level_id' => $p_value,
					'start_date' => time(),
					'end_date' => time(),
					'user_id' => $profile_id
				);
			}
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật Cấp bậc thành: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), ($p_value > 0 ? $clsProperty->getTitle($p_value): "Không xác định"))
			);
			$more_information['level_logs'] = $level_logs;
			$clsProfile->updateOne($p_id, array(
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
		} else if($p_field=='status_id'){
			$is_active = 0; $end_date = 0;
			if($p_value == _STATUS_STAFF_ON_ID){
				$is_active = 1;
			} else if($p_value == _STATUS_STAFF_OFF_ID) {
				$end_date = time();
			}
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật Tình trạng thành: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $clsProperty->getTitle($p_value))
			);
			$clsProfile->updateOne($p_id, array(
				$p_field => $p_value,
				'end_date' => $end_date,
				'is_active' => $is_active,
				'upd_date' => time(),
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
		} else if($p_field=='block_ids'){
			$block_ids = $clsISO->makeSlashListFromArrayRoot($p_value);			
			$clsProfile->updateOne($p_id, array(
				$p_field => $block_ids
			));
		} else if($p_field=='birthday' || $p_field=='start_date' || $p_field== 'contract_date'){
			if($p_field == 'birthday') $action_name = 'Ngày sinh nhật';
			if($p_field == 'start_date') $action_name = 'Ngày vào làm';
			if($p_field == 'contract_date') $action_name = 'Ngày ký HĐLĐ';
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật %s thành: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_name, $p_value)
			);
			$clsProfile->updateOne($p_id, array(
				$p_field => $clsISO->convertTextToTime($p_value),
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
		} else {
			if($p_field == 'full_name') {
				$action_name = 'Họ và tên';
				$pp_value = $p_value;
			} else if($p_field == 'phone') {
				$action_name = 'Số ĐT';
				$pp_value = $p_value;
			} else if($p_field == 'address') {
				$action_name = 'Địa chỉ';
				$pp_value = $p_value;
			} else if($p_field == 'CCID'){
				$action_name = 'Số CCID';
				$pp_value = $p_value;
			} else if($p_field == 'role_id') {
				$action_name = 'Vai trò';
				$pp_value = ($p_value > 0) ? $clsProperty->getTitle($p_value) : "Không xác định";
			} else if($p_field == 'gender_id') {
				$action_name = 'Giới tính';
				$pp_value = ($p_value == 1) ? 'Nam' : ($p_value==2 ? 'Nữ' : 'Khác');
			}
			$action_logs[$clsISO->getUniqid()] = array(
				'user_id' => $profile_id,
				'reg_date' => time(),
				'content' => sprintf('<strong>%s</strong> đã cập nhật %s thành: <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), $action_name, $pp_value)
			);
			$clsProfile->updateOne($p_id, array(
				$p_field => $p_value,
				'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	if(in_array($p_field, array('full_name','email','phone','code','address','CCID'))){
		$oProfile = $clsProfile->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= $oProfile[$p_field];
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input class="form-control form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$oProfile[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />';
		}
	} else if(in_array($p_field, array('facebook', 'twitter', 'linkedin','instagram'
	,'issue_number','issue_date','issue_location','zaloId'))){
		$more_information = $clsProfile->getOneField('more_information', $p_id);
		$more_information = $clsISO->to_array_json($more_information);
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= ($p_field=='issue_date' && !empty($more_information[$p_field])) 
				? $clsISO->convertTimeToText($more_information[$p_field]) 
				: $more_information[$p_field];
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			if($p_field == 'issue_date'){
				$issue_time = isset($more_information['issue_time']) ? (int) $more_information['issue_time'] : 1;
				$html_input = '<input type="'.($p_field=='issue_date'?'date':'text').'" class="form-control form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.($p_field=='issue_date' && !empty($more_information[$p_field]) ? date('Y-m-d', $more_information[$p_field]) : $more_information[$p_field]).'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />
				<select class="form-control form-select edit_profile_field_'.$p_field.'_'.$p_id.' w-px-75">';
				for($i=1; $i<=5; $i++){
					$html_input.= '<option'.($issue_time==$i ? ' selected': '').' value="'.$i.'">'.$i.' năm</value>';
				}
				$html_input.= '</select>';
			} else {
				$html_input = '<input type="'.($p_field=='issue_date'?'date':'text').'" class="form-control form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.($p_field=='issue_date' && !empty($more_information[$p_field]) ? date('Y-m-d', $more_information[$p_field]) : $more_information[$p_field]).'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />';
			}
		}
	}  else if($p_field=='birthday') {
		$oProfile = $clsProfile->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= $clsISO->convertTimeToText($oProfile[$p_field]);
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input class="form-control datepick form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$clsISO->convertTimeToText($oProfile[$p_field]).'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yyyy" />
			<style type="text/css">.ui-datepicker{ z-index:99999 !important;}</style>';
		}
	} else if($p_field=='start_date' || $p_field=='contract_date') {
		$oProfile = $clsProfile->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= $clsISO->convertTimeToText($oProfile[$p_field]);
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input class="form-control datepick form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$clsISO->convertTimeToText($oProfile[$p_field]).'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yyyy" />
			<style type="text/css">.ui-datepicker{ z-index:99999 !important;}</style>';
		}
	} else if($p_field=='status_id' || $p_field=='level_id'){
		$oProfile = $clsProfile->getOne($p_id, "{$p_field},`department_id`");
		$department_id = (int) $oProfile['department_id'];
		if($p_action=='_cancel' || $p_action=='_save'){
			if(isset($oProfile[$p_field]) && (int) $oProfile[$p_field] > 0){
				$html.= $clsProperty->getTitle($oProfile[$p_field]);
			} else {
				$html.= "--";
			}
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$label = 'Tình trạng';
			$property_type = '_STATUS_STAFF';
			if($p_field=='level_id'){
				$label = 'Cấp bậc';
				$property_type = '_LEVEL_STAFF';
				if(in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_MKT_ID, _DEPARTMENT_TECH_ID))){
					$property_type = '_LEVEL_STAFF_BO';
				}
			}
			$html_input = '<select class="form-control form-select form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />
				'.$clsISO->getSelectByPropertyTypeTitle($property_type,$oProfile['status_id'],$label).'
			</select>';
		}
	} else if($p_field=='department_id'){
		$oProfile = $clsProfile->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			if(isset($oProfile[$p_field]) && (int) $oProfile[$p_field] > 0){
				$html.= $clsProperty->getTitle($oProfile[$p_field]);
			} else {
				$html.= "--";
			}
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<select class="form-control form-select form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" 
				name="edit_profile_field_'.$p_field.'_'.$p_id.'">
				'.$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$oProfile['department_id'],'Phòng ban').'
			</select>';
		}
	} else if($p_field=='role_id'){
		$oProfile = $clsProfile->getOne($p_id, "{$p_field},department_id");
		if($p_action=='_cancel' || $p_action=='_save'){
			if(isset($oProfile[$p_field]) && (int) $oProfile[$p_field] > 0){
				$html.= $clsProperty->getTitle($oProfile[$p_field]);
			} else {
				$html.= "--";
			}
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$department_id = $oProfile['department_id'];
			if(!empty($department_id)){
				$arrOption = array();
				$for_id = $clsProperty->getOneField('for_id', $department_id);
				$clsProperty->makeOption($for_id, '_ROLE', 0, $arrOption);
				$html_input = '<select class="form-control form-select form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'">';
				if(!empty($arrOption)){
					foreach($arrOption as $key => $val){
						$html_input .= sprintf('<option value="%s">%s</option>', $key, $val);
					}
				}
				$html_input.= '</select>';
			} else {
				$html_input = '<span>Chọn phòng ban</span>';
			}
		}
	} else if($p_field=='block_ids'){
		$oProfile = $clsProfile->getOne($p_id, "{$p_field}");			
		$lstAllBlock = $clsProperty->getArraySearchByKey("_BLOCK");
		$block_ids = !empty($oProfile['block_ids']) ? $clsISO->getArrayByTextSlash($oProfile['block_ids']) : [];
		if($p_action=='_cancel' || $p_action=='_save'){
			if(isset($oProfile[$p_field]) && $oProfile[$p_field] != ""){	
				$block_name = "";
				$bFirst = 0;
				foreach($block_ids as $key => $block_id) {
					if($block_id > 0 && isset($lstAllBlock[$block_id])) {
						$block_name .= (($bFirst > 0)? ", ":"") . $lstAllBlock[$block_id]['title'];
						$bFirst = 1;
					}		
				}
				$html.= $block_name;
			} else {
				$html.= "--";
			}
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$department_id = $oProfile['department_id'];
			$html_input = '<select class="form-control form-select form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.' iso-select2" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]" multiple>';
			$html_input .= $clsProperty->getSelectByPropertyV2("_BLOCK",$block_ids,'',0);
			$html_input.= '</select>';
		}
	} else if($p_field == 'is_star_club'){
		$more_information = $clsProfile->getOneField('more_information', $p_id);
		$more_information = $clsISO->to_array_json($more_information);
		$is_star_club = (int) $core->get_field($more_information, 'is_star_club', 0);
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= ($is_star_club==1?
				'<span class="label bg-warning">Có ⭐️</span>'
				:'<span class="label bg-dark">Không</span>');
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<select class="form-control form-select edit_profile_field_'.$p_field.'_'.$p_id.' w-px-75">
				<option'.($is_star_club==1?' selected':'').' value="1">Có</option>
				<option'.($is_star_club==0?' selected':'').' value="0">Không</option>
			</select>';
		}
	} else if($p_field == 'gender_id'){
		$oProfile = $clsProfile->getOne($p_id, "{$p_field}");
		$gender_id = (int) $oProfile[$p_field];
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= ($gender_id==1? 'Nam' : ($gender_id=='2' ? 'Nữ' : '--'));
			$html.='<a class="editInlineField" onClick="$Core.member.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<select class="form-control form-select edit_profile_field_'.$p_field.'_'.$p_id.' w-px-75">
				<option'.($gender_id==1?' selected':'').' value="1">Nam</option>
				<option'.($gender_id==2?' selected':'').' value="2">Nữ</option>
				<option'.($gender_id==3?' selected':'').' value="3">Khác</option>
			</select>';
		}
	}
	if($p_action=='_cancel' || $p_action=='_save'){
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container">
			'.$html_input.'
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.member.save_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.member.cancel_edit_inline_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_open_bank(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$list_banks = array();
	$curl = new Curl\Curl();
	$curl->get('https://api.vietqr.io/v2/banks');
	if(!$curl->error){
		$response = $curl->response;
		$response = toArray($response);
		if(isset($response['code']) && $response['code'] == '00'){
			$list_banks = $response['data'];
		}
	}
	$smarty->assign('list_banks', $list_banks);
	// $clsISO->print_pre($list_banks); die();
	$action = "_add"; 
	$oneBank = array();
	$uid = $clsISO->getUniqid();
	$bank_id = Input::post('bank_id');
	$profile_id = Input::post('profile_id');
	$more_information = $clsProfile->getOneField('more_information', $profile_id);
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = $core->get_field($more_information, "banks_info", []);	
	$titlePage = 'Thêm tài khoản Ngân hàng';
	if(!empty($bank_id)){
		$action = "_edit";
		$titlePage = 'Sửa tài khoản Ngân hàng';
		if(!empty($banks_info) && @array_key_exists($bank_id, $banks_info)){
			$oneBank = $banks_info[$bank_id];
			//$clsISO->print_pre($oneBank); die();
		}	
	}
	$smarty->assign('action', $action);
	$smarty->assign('bank_id', $bank_id);
	$smarty->assign('oneBank', $oneBank);
	$smarty->assign('_profile_id', $profile_id);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.bank.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete_bank(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$msg = "_error";
	$bank_id = Input::post('bank_id');
	$profile_id = (int) Input::post('profile_id', 0);
	$oProfile = $clsProfile->getOne($profile_id, "`more_information`,`action_logs`");
	$action_logs = $oProfile['action_logs'];
	$more_information = $oProfile['more_information'];
	$action_logs = $clsISO->to_array_json($action_logs);
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = $core->get_field($more_information, "banks_info", []);
	if(!empty($bank_id) && !empty($banks_info) && array_key_exists($bank_id, $banks_info)){
		$oneBank = $banks_info[$bank_id];
		unset($banks_info[$bank_id]);
		$more_information['banks_info'] = $banks_info;
		// $clsISO->print_pre($action_logs); die();
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' => sprintf('<strong>%s</strong> đã xoá Tài khoản ngân hàng, Số TK: <strong>%s</strong>, 
				Chủ TK: <strong>%s</strong>, Ngân hàng: <strong>%s</strong> Chi nhánh: <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $oneBank['account_number'], $oneBank['account_person'], 
				$oneBank['bank_name'], $oneBank['location'])
		);
		// $clsISO->print_pre($action_logs); die();
		if($clsProfile->updateOne($profile_id, array(
			'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
		}
	}
	// Return
	echo $msg; die();
}
function default_pop_save_bank(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$bank_id = Input::post('bank_id');
	$profile_id = (int) Input::post('profile_id', 0);
	$oProfile = $clsProfile->getOne($profile_id, "`more_information`,`action_logs`");
	$action_logs = $oProfile['action_logs'];
	$more_information = $oProfile['more_information'];
	$action_logs = $clsISO->to_array_json($action_logs);
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = $core->get_field($more_information, "banks_info", []);
	#
	$account_number = Input::post('account_number');
	$account_person = Input::post('account_person');
	$bank_name = Input::post('bank_name');
	$location = Input::post('location');
	if(!empty($bank_id) && array_key_exists($bank_id, $banks_info)){
		$banks_info[$bank_id]['account_number'] = $account_number;
		$banks_info[$bank_id]['account_person'] = $account_person;
		$banks_info[$bank_id]['bank_name'] = $bank_name;
		$banks_info[$bank_id]['location'] = $location;
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' => sprintf('<strong>%s</strong> đã cập nhật Tài khoản ngân hàng, Số TK: <strong>%s</strong>, 
				Chủ TK: <strong>%s</strong>, Ngân hàng: <strong>%s</strong> Chi nhánh: <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $account_number, $account_person, $bank_name, $location)
		);
	} else {
		$bank_id = $clsISO->getUniqid();
		$banks_info[$bank_id] = array(
			'account_number' => $account_number,
			'account_person' => $account_person,
			'bank_name' => $bank_name,
			'location' => $location
		);
		$action_logs[$clsISO->getUniqid()] = array(
			'user_id' => $profile_id,
			'reg_date' => time(),
			'content' => sprintf('<strong>%s</strong> đã thêm tài khoản ngân hàng Số TK: <strong>%s</strong>, 
				Chủ TK: <strong>%s</strong>, Ngân hàng: <strong>%s</strong> Chi nhánh: <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), $account_number, $account_person, $bank_name, $location)
		);
	}
	$more_information['banks_info'] = $banks_info;
	// $clsISO->print_pre($action_logs); die();
	if($clsProfile->updateOne($profile_id, array(
		'action_logs' => json_encode($action_logs, JSON_UNESCAPED_UNICODE),
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = '_success';
	}
	// Return
	echo $msg; die();
}
function default_load_list_bank(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$profile_id = (int) Input::post('profile_id', 0);
	$more_information = $clsProfile->getOneField('more_information', $profile_id);
	$more_information = $clsISO->to_array_json($more_information);
	$banks_info = $core->get_field($more_information, "banks_info", []);
	###
	$html = '';
	if(!empty($banks_info)){
		$html.= '<div class="list-banks row">';
		foreach($banks_info as $bank_id => $_obank){
			$html.= '<div class="col-12 col-md-6">
				<div class="bank-item cursor-pointer position-relative">
					<div class="dropdown position-absolute top-10 right-10">
						<button type="button" class="btn p-0 dropdown-toggle hide-arrow " data-bs-toggle="dropdown" aria-expanded="true">
							<i class="bx bx-dots-vertical-rounded"></i>
						</button>
						<div class="dropdown-menu w-px-100" data-popper-placement="bottom-end">
							<a class="dropdown-item cursor-pointer" onclick="$Core.member.open_bank(this, event)" profile_id="'.$profile_id.'" 
								bank_id="'.$bank_id.'">
								<i class="bx bxs-show me-1"></i> Sửa </a>
							<a class="dropdown-item cursor-pointer" onclick="$Core.member.delete_bank(this, event)" profile_id="'.$profile_id.'" 
								bank_id="'.$bank_id.'">
								<i class="bx bx-trash me-1"></i> Xóa</a>
						</div>
					</div>
					<p class="mb-1">'.$_obank['account_person'].' - '.$_obank['bank_name'].'</p>
					<strong>'.$_obank['account_number'].'</strong>
				</div>
			</div>';
		}
		$html.= '</div>';
	} else {
		$html .= '<div class="p-3 no-result text-center">
			<img src="https://cdn-icons-png.flaticon.com/512/833/833602.png" width="50px" />
			<p class="text-muted mt-2">Không có ghi chú nào được tạo</p>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_ms_save_file(){
	// ini_set('display_errors',1);
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$profile_id;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$msg = '_error';
	$for_id = (int) Input::post('for_id', 0);
	$clsTable = Input::post('clsTable', 'Profile');
	$clsClassTable = new $clsTable();
	###
	$files = $clsClassTable->getOneField('files', $for_id);
	$files = !empty($files) ? @json_decode(html_entity_decode($files), true) : array();
	if(isset($_POST['submit']) && $_POST['submit'] == 'Insert'){
		$file_size = 0; $file_name = ''; $attachment = '';
		if(!empty($_FILES['attachment']['name'])){
			if(@is_uploaded_file($_FILES['attachment']['tmp_name'])){
				$clsUploadFile = new UploadFile();
				$attachment = $clsUploadFile->uploadItem($_FILES["attachment"],"/GD",EXTENSION_FILE_UPLOAD);
				$file_name = $_FILES['attachment']['name'];
				$file_size = $_FILES['attachment']['size'];
				// Upload file to google drive
				if($clsTable=='Billing'){
					$billing_code = $clsClassTable->getOneField('billing_code', $for_id);
					$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
					$folder_id = $clsGoogleUpload->create_folder($billing_code);
					// $clsISO->print_pre($folder_id); die();
					$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$attachment,$folder_id);
					$attachment = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
					@unlink(ROOTPATH . $attachment);
				}
			}
		}
		$files[$clsISO->getUniqid()] = array(
			'description' => Input::post('description'),
			'attachment' => $attachment,
			'file_name'	=> $file_name,
			'file_size'	=> $file_size,
			'user_id'	=> $profile_id,
			'user_id_update'	=> $profile_id,
			'reg_date'	=> time(),
			'upd_date' => time()
		);
		if($clsClassTable->updateOne($for_id, array(
			'files'	=> json_encode($files, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';		
			if(get_class($clsClassTable) == "Billing" ) {
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Billing","update");
			}
		}
	}
	// output
	echo($msg); die();
}
function formatNameFile($name) {
	global $core, $dbconn, $clsISO;
	$part_part = pathinfo($name);
	$filename = $part_part['filename'];
	$extension = $part_part['extension'];
	if(strlen($filename) > 30){
		$l_filename = substr($filename, 0, 10);
		$r_filename = substr($filename, -10);
		return sprintf('%s...%s.%s', $l_filename, $r_filename, $extension);
	} else {
		return $name;
	}
}
function default_load_list_files(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$deviceType;
	$clsNews = new News();
	$clsProperty = new Property();
	$for_id = (int) Input::post('for_id', 0);
	$clsTable = Input::post('clsTable', 'Profile');
	$clsClassTable = new $clsTable();
	$func = ($clsTable=='Customer') ? 'crm' : 'helper';
	##
	$html = '';
	$files = $clsClassTable->getOneField('files', $for_id);

	$list_files = $clsISO->to_array_json($files);
	if($deviceType == 'phone' && $clsTable=='StockMeta'){
		if(!empty($list_files)){ $ii = 0; //init
			foreach($list_files as $file_id => $file){
				$html.= (!empty($file['attachment']) ? '<a class="link download" href="'.$file['attachment'].'" target="_blank">'.formatNameFile($file['file_name']).' ('.$clsISO->size_calculator($file['file_size']).')</a>':'');
			}
		} else {
			$html.= '<div class="p-4 text-center">
				<img class="mb-2" src="'.URL_IMAGES.'/empty.svg" width="80px" />
				<p class="text-muted text-center">Chưa có tài liệu nào</p>
			</div>';
		}
	} else {
		$html.= '<div class="table-container no-shadow overflow-x-auto">
			<table class="table dragable mb-0" border="0" cellpadding="0" cellspacing="0" width="100%">
				<thead><tr>
					<th class="align-center h-px-35 bg-lightest text-left">Miêu tả</th>
					<th class="align-center h-px-35 bg-lightest text-left">File đính kèm</th>
					'.($clsTable=='Customer' ? '<th class="align-center h-px-35 bg-lightest text-left">Nhóm</th>' : '').' 
					<th class="align-center h-px-35 bg-lightest text-right">Cập nhật</th>
					<th class="align-center h-px-35 bg-lightest" width="40px"></th>
				</tr></thead>';
			if(!empty($list_files)){ $ii = 0; //init
				function sortOrder($a, $b){
					return $b['reg_date'] - $a['reg_date'];
				}
				@uasort($list_files, 'sortOrder');
				foreach($list_files as $file_id => $file){
					if(isset($file['attachments'])){
						$html_file = "";
						foreach($file['attachments'] as $oKey => $oFile){
							$html_file.= '<a'.($clsISO->isPDF($oFile) || $clsISO->isImage($oFile) ? ' data-fancybox="true"' : '').' class="link text-nowrap download" href="'.$oFile.'" target="_blank">'.formatNameFile(basename($oFile)).'</a>';
						}
					} else {
						$html_file = '<a class="link mb-n1" href="'.$file['attachment'].'" target="_blank">'.formatNameFile($file['file_name']).' ('.$clsISO->size_calculator($file['file_size']).')</a>';
					}
					$html.= '<tr class="iso_search_item bg-white">
						<td class="text-left">'.($ii+1).'/'.$file['description'].'</td>
						<td class="text-left"> '.$html_file.'</td>
						'.($clsTable=='Customer' ? '
							<td class="text-left text-nowrap">'.$clsProperty->getTitle($file['group_id']).'</td>' : '').' 
						<td class="text-right text-nowrap">'.$clsISO->convertTimeToText($file['reg_date'], true).'</td>
						<td class="text-center">
							<div class="d-flex gap-1 align-items-center">
								<a class="btn btn-sm btn-icon btn-outline-default" onClick="$Core.'.$func.'.open_file(this,event);" file_id="'.$file_id.'" for_id="'.$for_id.'" href="javascript:void(0);"><i class="bx bx-pencil"></i></a>
								<a class="btn btn-sm btn-icon btn-outline-default" onClick="$Core.'.$func.'.delete_file(this,event);" file_id="'.$file_id.'" clsTable="'.$clsTable.'" for_id="'.$for_id.'" href="javascript:void(0);"><i class="bx bx-trash"></i> </a>
							</div>
						</td>
					</tr>';
					++$ii;
				}
			}else{
				$html .= '<tr>
					<td class="text-center" colspan="3">
						Chưa có file đính kèm
					</td>
				</tr>';
			}
		$html .= '</table>
		</div>';
	}
	// Return
	echo @json_encode(array(
		'html'	=> $html
	)); die();
}
function default_load_rating_star(){
    global $profile_id,$core,$clsISO,$clsUser,$clsProperty;
    $clsProfile = new Profile();
    $profile_id = Input::get('profile_id',0);
    $rating_star = $clsProfile->getOneField('rating_star', $profile_id);
    $html = '<form method="post">
        <div class="form-group mb-2">
           <div class="range-slider">
				<input type="range" class="range-slider__range" name="rating_star" min="1" 
				max="5" step="1" value="'.$rating_star.'">
				<span class="range-slider__value">0</span>
			</div>
        </div>
		<hr />
        <div class="form-group">
			<input type="hidden" name="p_field" value="rating_star" />
            <button type="button" class="btn btn-primary" onclick="$Core.member.save_profile_pfield(this,event);" 
			profile_id="'.$profile_id.'">'.$core->makeIcon('check', 'Cập nhật').' </button>
        </div>
    </form>';
    // Return
    echo  $html; die();
}
function default_save_profile_pfield(){
	global $profile_id,$core,$clsISO,$oneProfile; 
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$profile_id = (int) Input::post('profile_id',0);
	$p_field = Input::post('p_field', 'status_id');
	$p_value = Input::post($p_field, 0);
	###
	$msg = "_error";
	// $oneProfile = $clsProfile->getOne($profile_id);
	$set = array($p_field => $p_value);
	if($clsProfile->updateOne($profile_id, $set)){
		$msg = "_success";
	}
	// Return
    echo json_encode(array(
		'msg' => $msg,
		'p_field' => $p_field,
		'p_value' => $p_value
	)); die();
}
function default_load_list_billing(){
	global $smarty,$core,$clsISO,$oneProfile; 
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsBilling = new Billing();
	$clsCustomer = new Customer();
	$clsProject = new Project();
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsBilling', $clsBilling);
	##
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$profile_id = Input::post('profile_id', 0);
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',20);
	##
	$cond = "`is_trash`=0 and `staff_id`='{$profile_id}'";
	$total_record = $clsBilling->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	##
	$field = "*";
	$list_billings = $clsBilling->getAll($cond." order by reg_date DESC".$limitCond, $field);
	if(!empty($list_billings)){
		$arr_property_cached = $arr_projects_cached = array();
		foreach($list_billings as $key => $val){
			$project_id = $val['project_id'];
			$billing_type = $val['billing_type'];
			if(isset($arr_property_cached[$billing_type])){
				$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
			} else {
				$arr_property_cached[$billing_type] = $clsProperty->getTitle($billing_type);
				$list_billings[$key]['billing_type'] = $arr_property_cached[$billing_type];
			}
			if(isset($arr_projects_cached[$project_id])){
				$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
			} else {
				$arr_projects_cached[$project_id] = $clsProject->getCode($project_id);
				$list_billings[$key]['poroject_name'] = $arr_projects_cached[$project_id];
			}
		}
	}
	// $clsISO->print_pre($list_billings); die();
	$smarty->assign('list_billings', $list_billings);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('total_page', $total_page);
	// Return
	$html = $core->build('_ajax.billing.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	)); die();
}
function default_load_tab_profile(){
	global $smarty,$core,$clsISO,$oneProfile,$dbconn; 
	#
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$profile_id = Input::post('profile_id', 0);
	$_type = Input::post('type', "");
	$current_page = (int) Input::post('page',1);
	$per_page = (int) Input::post('per_page',20);
//	$clsISO->print_pre($_POST);die;
	##	
	if($_type == "stock_logs") {
		$clsLog = new Log();
		$cond = "`from_site`='_user' AND `user_id`='{$profile_id}' AND (`type`='search' OR `type`='view_stock')";	
		$total_record = $clsLog->countItem($cond);
		$total_page = @ceil($total_record/$per_page);
		$offset = ($current_page-1)*$per_page;
		$limitCond = " limit {$offset},{$per_page}";
		$field = "{$clsLog->pkey},`type`,`user_id`,`title`,`reg_date`,`from_site`,`stock_id`";
		$lstItem = $clsLog->getAll("{$cond} ORDER BY `reg_date` DESC ".$limitCond, $field);
		foreach($lstItem as $key => $_oLog){
			$type = $_oLog['type'];
			$stock_id = $_oLog['stock_id'];
			$title = !empty($_oLog['title']) ? @strtok($_oLog['title'],'?') : "";
			if($type=='view'){
				$action = "<i class='bx bx-table' ></i> Xem bảng hàng";
				$contentHTML = $title;
			} else if($type='view_stock'){
				$action = "<i class='bx bxs-show'></i> Xem căn hộ";
				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')" stock_id="'.$stock_id.'">'.$title.'</a>';
			} else if($type=='search'){
				$action = "<i class='bx bx-search'></i> Tìm kiếm căn hộ";
				$contentHTML= '<a href="javascript:void(0);" onClick="$Core.helper.open_stock('.$stock_id.')" stock_id="'.$stock_id.'">'.$title.'</a>';
			}
			$lstItem[$key]['profile'] = $profile;
			$lstItem[$key]['action'] = $action;
			$lstItem[$key]['contentHTML'] = $contentHTML;
			$lstItem[$key]['time'] = $clsISO->convertTimeToText($_oLog['reg_date'], true);
		}
	}elseif($_type == "report_share") {
		$clsShare = new Share();
		$clsProfile = new Profile();
		$smarty->assign('clsShare', $clsShare);
		$arr_profile_cached = $clsProfile->getProfileCached();
		$cond = "`share_type`='share' AND `user_id`='{$profile_id}'";			
		$total_record = $clsShare->countItem($cond);
		$total_page = @ceil($total_record/$per_page);
		$offset = ($current_page-1)*$per_page;
		$limitCond = " limit {$offset},{$per_page}";		
//		$dbconn->debug=true;
		$lstItem = $clsShare->getAll($cond . " ORDER BY `reg_date` DESC ".$limitCond,"{$clsShare->pkey},more_information,reg_date,user_id");
		$arr_property_cached = [];
		$arr_purpose_cached = $clsProperty->getArraySearchByKey("PURPOSE");
		$arr_bedroom_cached = $clsProperty->getArraySearchByKey("_BEDROOM");
		foreach($arr_purpose_cached as $key => $val) {
			$arr_property_cached[$val[$clsProperty->pkey]] = $val["title"];
		}
		foreach($arr_bedroom_cached as $key => $val) {
			$arr_property_cached[$val[$clsProperty->pkey]] = $val["title"];
		}
		foreach ($lstItem as $key => $val) {
			$more_information = $clsISO->to_array_json($val['more_information']);
			$status_id = (int) $core->get_field($more_information, "status_id", 0);
			$project_budget = (int) $core->get_field($more_information, "project_budget", 0);
			$target_ids = $core->get_field($more_information, "target_ids", []);
			$interest_ids = $core->get_field($more_information, "interest_ids", []);
			if(!isset($arr_property_cached[$status_id])) {
				$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
			}
			if(!isset($arr_property_cached[$status_id])) {
				$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id);
			}
			$status_name = ($status_id > 0 ) ? $arr_property_cached[$status_id] : "";
			$target_name = $clsProperty->getTitleArray($target_ids, false, $arr_property_cached);
			$interest_name = $clsProperty->getTitleArray($interest_ids, false, $arr_property_cached);
			$more_information['status_name'] = $status_name;
			$more_information['target_name'] = $target_name;
			$more_information['interest_name'] = $interest_name;
			$more_information['project_budget_name'] = !empty($project_budget) ? $clsSetting->getTitle($project_budget) : "";
			$has_info = 0;
			if(!empty($more_information["customer_name"])) $has_info = 1;
			if(!empty($more_information["customer_phone"])) $has_info = 1;
			if(!empty($more_information["location"])) $has_info = 1;
			if(!empty($more_information["guest_count"])) $has_info = 1;
			if(!empty($target_ids)) $has_info = 1;
			if(!empty($interest_ids)) $has_info = 1;
			if(!empty($status_name)) $has_info = 1;
			if(!empty($more_information["content"])) $has_info = 1;
			$more_information["has_info"] = $has_info;
			$is_confirm = $core->get_field($more_information, "is_confirm", 0);
			$confirm = $core->get_field($more_information, "confirm", []);
			if(!empty($confirm)) {
				$profile_confirm = $clsProfile->getProfile($confirm["user_id"], $arr_profile_cached[$confirm["user_id"]]);
				$confirm["full_name"] = $profile_confirm['role']." ".$profile_confirm['name'];
				$confirm["time"] = $clsISO->convertTimeToTextFormat($confirm["reg_date"],"H:i • d/m/Y");
			}
			$more_information["confirm"] = $confirm;
			$more_information["is_confirm"] = $is_confirm;
//			$clsISO->print_pre($more_information);die;
			$lstItem[$key]["more_information"] = $more_information;
			unset($more_information);
		}
	}
//	$clsISO->print_pre($lstItem);die;
	$smarty->assign('_type', $_type);
	$smarty->assign('lstItem', $lstItem);
	// Return
	$html = $core->build('_ajax.tab_profile.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'per_page' => $per_page,
		'current_page' => $current_page,
		'total_page' => $total_page,
		'total_record' => $total_record
	)); die();
}
function default_load_login_logs(){
	global $smarty,$dbconn,$core,$clsISO,$oneProfile; 
	$clsProfile = new Profile();
	$clsProfileLog = new ProfileLog();
	$clsProfileSession = new ProfileSession();
	$_profile_id = (int) Input::post('profile_id', 0);
	$html = '<table with="100%" class="table table-striped">
		<thead><tr>
			<th width="5%">No.</th>
			<th width="10%">Hành động</th>
			<th width="150px">Thời gian</th>
			<th>Địa chỉ IP</th>
			<!-- <th>Trình duyệt</th>-->
		</tr></thead>';
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$list_logs = $clsProfileSession->getAll("`profile_id`='{$_profile_id}' order by `reg_date` DESC");
	if(!empty($list_logs)){ $ii= 0;
		foreach($list_logs as $key => $val){
			$html.= '<tr>
				<td class="text-center">'.($ii+1).'</td>
				<td class="text-left">'.($val['act']=='login' ? 'Đăng nhập' : 'Đăng xuất').'</td>
				<td class="text-left">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td>
				<td class="text-left">'.$val['ip_address'].'</td>
				<!-- <td class="text-left">'.$val['browser'].'</td> -->
			</tr>';
			++$ii;
		}
	}
	$html .= '</table>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_load_level_logs(){
	global $smarty,$dbconn,$core,$clsISO,$oneProfile; 
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$_profile_id = (int) Input::post('profile_id', 0);
	$more_information = $clsProfile->getOneField('more_information', $_profile_id);
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$level_logs = isset($more_information['level_logs']) 
		? $more_information['level_logs'] : array();
	// $clsISO->print_pre($level_logs); die();
	$html = '<table with="100%" class="table table-striped">
		<thead><tr>
			<th width="5%">No.</th>
			<th>Hạng</th>
			<th width="25%">Từ ngày</th>
			<th width="25%">Tới ngày</th>
			<th width="25%">Người thực hiện</th>
		</tr></thead>';
	if(!empty($level_logs)){ $ii= 0;
		$level_logs = array_reverse($level_logs);
		foreach($level_logs as $key => $val){
			$html.= '<tr>
				<td class="text-center">'.($ii+1).'</td>
				<td class="text-left">'.$clsProperty->getTitle($val['level_id']).'</td>
				<td>'.$clsISO->convertTimeToText($val['start_date'], true).'</td>
				<td>'.(!empty($val['end_date']) ? $clsISO->convertTimeToText($val['end_date'], true): '' ).'</td>
				<td class="text-left">'.$clsProfile->getIndentityV3($val['user_id'], true).'</td>
			</tr>';
			++$ii;
		}
	}
	$html .= '</table>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open(){
	global $smarty,$dbconn,$core,$clsISO,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	// Return
	$html = $core->build('_ajax.open.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_handle_dept_changed(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$department_id = (int) Input::post('department_id', 0);
	$html_role_options = '<option value="0">Chọn vai trò</option>';
	$html_team_options = '<option value="0">Chọn team</option>';
	if($department_id > 0){
		$field = "{$clsProperty->pkey},title";
		$list_teams = $clsProperty->getAll("`parent_id`='{$department_id}' order by `order_no` ASC", $field);
		// $clsISO->print_pre($list_teams); die();
		if(!empty($list_teams)){
			foreach($list_teams as $key => $val){
				$html_team_options.= sprintf('<option value="%s">%s</option>', $val[$clsProperty->pkey], $val['title']);
			}
			unset($list_teams);
		}
		$arr_options = array();
		$for_id = $clsProperty->getOneField('for_id', $department_id);
		$clsProperty->makeOption($for_id, '_ROLE', 0, $arr_options);
		if(!empty($arr_options)){
			foreach($arr_options as $key => $val){
				$html_role_options .= sprintf('<option value="%s">%s</option>', $key, $val);
			}
			unset($arr_options);
		}
	}
	// Return
	echo json_encode(array(
		'html_role_options' => $html_role_options,
		'html_team_options' => $html_team_options
	)); die();	
}
function default_get_team_options(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	#
	$department_id = (int) Input::post('department_id', 0);
	$html_team_options = '<option value="0">Chọn team</option>';
	if($department_id > 0){
		$field = "{$clsProperty->pkey},title";
		$list_teams = $clsProperty->getAll("`parent_id`='{$department_id}' order by `order_no` ASC", $field);
		// $clsISO->print_pre($list_teams); die();
		if(!empty($list_teams)){
			foreach($list_teams as $key => $val){
				$html_team_options.= sprintf('<option value="%s">%s</option>', $val[$clsProperty->pkey], $val['title']);
			}
			unset($list_teams);
		}
	}
	// Return
	echo json_encode(array(
		'html_team_options' => $html_team_options
	)); die();	
}
function default_add_new(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$dbconn;
	$clsMember = new Member();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$error_count = 0;
	$msg = "_error"; $errors = array();
	$profile_id = (int) Input::post('profile_id', 0);
	$user_name = Input::post('user_name');
	$user_email = Input::post('user_email');
	$user_pass = Input::post('user_pass');
	$user_cpass = Input::post('user_cpass');
	$first_name = Input::post('first_name');
	$last_name = Input::post('last_name');
	$full_name = sprintf('%s %s', $first_name, $last_name);
	$gender_id = (int) Input::post('gender_id', 0);
	$department_id = (int) Input::post('department_id', 0);
	$role_id = (int) Input::post('role_id', 0); // Vai trò
	$is_sendemail = (int) Input::post('is_sendemail', 0);
	###
	if(empty($user_name)){
		$error_count += 1;
		$errors[] = "Bạn chưa nhập vào tên đăng nhập";
	}
	if(empty($user_email)){
		$error_count += 1;
		$errors[] = "Bạn chưa nhập vào tên e-mail";
	} else {
		if(!$clsISO->is_valid_email($user_email)){
			$error_count += 1;
			$errors[] = "Địa chỉ e-mail không hợp lệ ex:example@gmail.com";
		} else {
			if($clsProfile->checkValidUsername($user_email)){
				$error_count += 1;
				$errors[] = "Địa chỉ e-mail đã tồn tại";
			}
		}
	}
	if(empty($user_pass)){
		$error_count += 1;
		$errors[] = "Bạn chưa nhập vào mật khẩu";
	}
	if(empty($user_cpass)){
		$error_count += 1;
		$errors[] = "Bạn chưa nhập vào xác nhận mật khẩu";
	}
	if(!empty($user_pass) && !empty($user_cpass)){
		if($user_pass != $user_cpass){
			$error_count += 1;
			$errors[] = "Mật khẩu và xác nhận mật khẩu không khớp";
		}
	}
	$html_error = $html_success = "";
	if($error_count == 0){
		$profile_id = $clsProfile->getMaxId();
		$more_information = array('fromsite' => '_frontend');
		$insert_data = array(
			$clsProfile->pkey => $profile_id,
			'user_pass' => $clsProfile->encrypt($user_pass),
			'user_name' => $user_name,
			'email' => $user_email,
			'full_name' => $full_name,
			'code' => Input::post('code'),
			'full_name_slug' => $core->replaceSpace($full_name),
			'first_name' => $first_name,
			'last_name' => $last_name,
			'phone' => Input::post('phone'),
			'address' => Input::post('address'),
			'CCID' => Input::post('CCID'),
			'gender_id' => $gender_id,
			'department_id' => $department_id,
			'role_id' => $role_id,
			'is_active' => 1, // Mặc định kích hoạt
			'level_id' => _RANKING_LEVEL_DEF,
			'status_id' => _STATUS_STAFF_ON_ID,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'reg_date' => time(),
			'upd_date' => time()
		);
		#-- Special Field: birthday
		$birthday = Input::post('birthday');
		if(!empty($birthday)){
			$insert_data['birthday'] = $clsISO->toTime($birthday);
		}
		#-- Special Field: list_department_id
		if($department_id > 0){
			$list_department_id = $clsProperty->getListParent($department_id);
			$insert_data['list_department_id'] = $list_department_id;
		}
		#-- Special Field: start_date
		$start_date = Input::post('start_date');
		if(!empty($start_date)){
			$insert_data['start_date'] = $clsISO->toTime($start_date);
		}
		#-- Special Field: avatar
		$avatar = Input::post('avatar');
		if(!empty($avatar)){
			$insert_data['avatar'] = $avatar;
		}
		if($clsProfile->insert($insert_data)){
			$msg = "_success";
			// Update more_information
			$oneProfile = $clsProfile->getOne($pvalTable);
			$clsProfile->updateMore($pvalTable, $oneProfile);
			// Send data HubJS
			// $clsHubJS = new HubJS();
			// $api_results = $clsHubJS->sendData($oneProfile);
			// Send email
			if($is_sendemail) $clsProfile->sendMailWellcome($profile_id, $oneProfile);
			// End Send Data
			if($clsMember->countItem("`email`='{$user_email}'") == 0){
				$member_id = $clsMember->getMaxId();
				$m_field = array(
					$clsMember->pkey => $member_id,
					'profile_type' => 'MOC',
					'type_account_id' => TYPE_ACCOUNT_SELLER_ID,
					'user_pass' => $clsMember->encrypt($user_pass),
					'target_id' => $profile_id,
					'user_name' => $user_name,
					'email' => $user_email,
					'code' => $clsMember->getCode(),
					'full_name' => $full_name,
					'full_name_slug' => $core->replaceSpace($full_name),
					'first_name' => $first_name,
					'last_name' => $last_name,
					'phone' => Input::post('phone'),
					'address' => Input::post('address'),
					'CCID' => Input::post('CCID'),
					'oauth_provider' => '_register',
					'permalink' => $core->replaceSpace($full_name),
					'oauth_email' => $user_email,
					'status_id' => _STATUS_STAFF_ON_ID,
					'reg_date' => time(),
					'upd_date' => time(),
					'is_active' => 1,
				);
				// $dbconn->debug = true;
				$clsMember->insert($m_field);
			}
		}
	} else {
		foreach($errors as $err){
			$html_error.= "&bull {$err}";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'html_error' => $html_error
	)); die();
}
function default_trans_to_dept(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('_ajax.trans_to_dept.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_open_trans_to_dept(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	###
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$field = "{$clsProperty->pkey},`property_code`,`title`";
	$arr_departments = $clsProperty->getAll("`property_type`='_DEPARTMENT' AND `parent_id`='"._DEPARTMENT_SALE_ID."'", $field);
	$smarty->assign('arr_departments', $arr_departments);
	// Return
	$smarty->assign('template_type', '_form');
	$html = $core->build('_ajax.trans_to_dept.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_active_new_version(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO;
	$clsProfile = new Profile();
	#
	$msg = "_error";
	$profile_id = (int) Input::post('profile_id', 0);
	$is_active_new_version = (int) Input::post('is_active_new_version', 0);
	if($profile_id > 0){
		$more_information = $clsProfile->getOneField('more_information', $profile_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['is_active_new_version'] = $is_active_new_version;
		// $clsISO->print_pre($more_information); die();
		if($clsProfile->updateOne($profile_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function default_export_loyalty(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $profile_id, $oneProfile;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	$callStartTime = microtime(true);
	$objPHPExcel = new PHPExcel();
	$creator = PAGE_NAME;
    $titlePage = 'Export User List';
	// Set document properties
    $objPHPExcel->getProperties()->setCreator($creator)
            ->setLastModifiedBy("")
            ->setTitle($titlePage)
            ->setSubject($titlePage)
            ->setDescription("")
            ->setKeywords("Export user list")
            ->setCategory($creator);
	$objPHPExcel->setActiveSheetIndex(0);
	$tblBackgroundHeader = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'b8cce4;')
        )
    );
	$tblBackgroundCancel = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'C00000;')
        )
    );
	$tblBackgroundHeaderRequired = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'a09f9f;')
        )
    );
	$tblBorderOutline = array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'ccc'),
            ),
        ),
    );
	//$clsISO->print_pre($objPHPExcel); die();
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Mã NV');
	$objPHPExcel->getActiveSheet()->setCellValue('B1','Tên NV');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Phòng ban');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Ngày vào làm');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tổng điểm');
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($tblBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($tblBackgroundHeader);
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
	// Set document autosize column
	PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
	foreach(range('A','E') as $columnID) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	$objPHPExcel->getActiveSheet()->getStyle('A:E')->getNumberFormat()->setFormatCode(
		PHPExcel_Style_NumberFormat::FORMAT_TEXT
	);
	$cond= "`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."'";
	$field = "{$clsProfile->pkey},`code`,`full_name`,`department_id`,`start_date`,`total_LPoint`";
	// $clsISO->print_pre($cond); die();
	$list_staffs = $clsProfile->getAll($cond." ORDER BY `total_LPoint` DESC", $field);
	// $clsISO->print_pre($list_staffs); die();
	if(!empty($list_staffs)){
		$row = 2; $arr_property_cached =  array();
		$tmp = $clsProperty->getAll("`property_type`='_DEPARTMENT'", "{$clsProperty->pkey},title");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_property_cached[$val[$clsProperty->pkey]] = $val['title'];
			}
			unset($tmp);
		}
		$total_LPoints = 0;
		foreach($list_staffs as $key => $val){
			$staff_id = $val[$clsProfile->pkey];
			$department_id = $val["department_id"];
			$total_LPoints += (int) $val['total_LPoint'];
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $val['code']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $clsProfile->getFullName($staff_id, $val));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $arr_property_cached[$department_id]);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $clsISO->convertTimeToText($val['start_date']));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $clsISO->formatNumberToEasyRead($val['total_LPoint']));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray($tblBorderOutline);
			++$row;
		}
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':D'.$row);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, "TỔNG CỘNG");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $clsISO->formatNumberToEasyRead($total_LPoints));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray($tblBorderOutline);
		unset($list_staffs);
	}
	// $clsISO->print_pre($list_staffs); die();
	$nameFile = 'Loyalty_'.date('dmY');
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Redirect output to a client's web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.($nameFile).'.xls');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	ob_end_clean();
    $objWriter->save('php://output');
    exit;
}
function default_recalc_loyalty(){
	global $core, $dbconn, $clsISO, $profile_id;
	// Tính lại điểm Loyalty cho 1 nhân viên — CHỈ full quyền
	if(!in_array($profile_id, _PROFILE_SUPPER_ID)){
		echo json_encode(array('error' => 1, 'message' => 'Bạn không có quyền thực hiện.'), JSON_UNESCAPED_UNICODE);
		die();
	}
	$staff_id = (int) Input::post('staff_id', 0);
	if($staff_id <= 0){
		echo json_encode(array('error' => 1, 'message' => 'Thiếu thông tin nhân viên.'), JSON_UNESCAPED_UNICODE);
		die();
	}
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	// 1) Thâm niên: gỡ + chấm lại 01/2024 → nay (2024-2025 thang BO cũ 30/40/50, từ 2026 thang mới)
	$sen = $clsFPoint->rebuild_LPoint_seniority_staff($staff_id);
	// 2) Giao dịch từ 2026: chấm bổ sung theo cơ chế giá trị (idempotent — GD đã chấm tự bỏ qua)
	$start_2026 = strtotime('2026-01-01 00:00:00');
	$field = "{$clsBilling->pkey},`staff_id`,`totalgrand`,`deposit_date`,`billing_source_id`,`stock_code`,`is_cancel`,`is_trash`";
	$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' and `deposit_date`>='{$start_2026}'", $field);
	$trans_added = 0;
	if(!empty($list_billings)){
		foreach($list_billings as $oBilling){
			if($clsFPoint->insert_billing_LPoint_value((int) $oBilling[$clsBilling->pkey], $oBilling)){
				$trans_added++;
			}
		}
	}
	// 3) Chốt tổng CHÍNH XÁC từ log (total = SUM điểm cộng - điểm trừ '_minus', bỏ log hủy) — hết trôi số dư lịch sử
	$sync = $clsFPoint->recompute_total_from_logs($staff_id);
	$total_Lpoint = $sync['new'];
	echo json_encode(array(
		'error' => 0,
		'message' => sprintf('Thâm niên: gỡ %s log cũ (-%s đ), chấm lại %s tháng (+%s đ). Giao dịch 2026 bổ sung: %s. Tổng điểm hiện tại: %s đ.',
			$sen['deleted'], $sen['sub'] * 1, $sen['inserted'], $sen['add'] * 1, $trans_added, $total_Lpoint * 1),
		'total_Lpoint' => $total_Lpoint * 1
	), JSON_UNESCAPED_UNICODE); die();
}
?>