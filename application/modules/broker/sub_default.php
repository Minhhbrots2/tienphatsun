<?php 

function default_default(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$title_page,$description_page,$deviceType,$oneProfile;

	$assign_list["clsModule"] = $clsModule;

	$user_id = $core->_USER['user_id'];

	$clsProperty = new Property();

	$assign_list["clsProperty"] = $clsProperty;

	$clsBilling = new Billing();

	$assign_list["clsBilling"] = $clsBilling;

	$array_follow = $oneProfile["more_information"]['follow'];

	$assign_list['array_follow'] = $array_follow;

	#

	/* End Filter */

	$classTable = "Member";

	$clsClassTable = new $classTable;

	$tableName = $clsClassTable->tbl;

	$pkeyTable = $clsClassTable->pkey;

	$assign_list["clsClassTable"] = $clsClassTable;

	$assign_list["pkeyTable"] = $pkeyTable;

	#-------Page Divide---------------------------------------------------------------

	$assign_list['totalRecord'] = $totalRecord;

	/*=============Title & Description Page==================*/

	$title_page = 'Danh sách nhà môi giới - Sale BDS tại Ocean City - '.PAGE_NAME; 

	$assign_list["title_page"] = $title_page;

	$description_page = ' Hệ thống hỗ trợ bán hàng Ocean City - '.PAGE_NAME;

	$assign_list["description_page"] = $description_page;

}

function default_list_broker(){

	/*ini_set('display_errors', '1');

	ini_set('display_startup_errors', '1');

	error_reporting(E_ALL);*/

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType,$assign_list,$oneProfile;

	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;

	$clsBilling = new Billing(); $assign_list["clsBilling"] = $clsBilling;

	$clsProperty = new Property(); $assign_list["clsProperty"] = $clsProperty;

	###

	$_ss_view = 'grid';

	if(vnSessionExist('_ss_view')){

		$_ss_view = vnSessionGetVar('_ss_view');

	}

	$assign_list['_ss_view'] = $_ss_view;

	$cond = "1='1' and `email` IN (SELECT `email` FROM `default_profile` WHERE `is_trash`=0 and `status_id`<>"._STATUS_STAFF_OFF_ID." AND `list_department_id` LIKE '%|"._DEPARTMENT_SALE_ID."|%') and `is_trash`=0 and `phone`<>''";

	$keyword = Input::post("keyword","");

	#Filter By Keyword

	if(!empty($keyword)){

		$slug = $core->replaceSpace($keyword);

		$cond .= " and (`full_name_slug` like '%".$slug."%' 

			or `full_name` like '%".addslashes($keyword)."%' 

		)";

	}

	$array_follow = $oneProfile["more_information"]['follow'];

	$assign_list['array_follow'] = $array_follow;

	// $dbconn->debug = true;

	$recordPerPage 	= 16;

	$currentPage = (int) Input::post('page',1);

	$start_limit = ($currentPage-1)*$recordPerPage;

	$start_limit = ($currentPage-1)*$recordPerPage;

	$limit = " limit $start_limit,$recordPerPage";

	$totalRecord = $clsProfile->countItem($cond);

	$orderBy = " `reg_date` desc";

	$allItem = $clsProfile->getAll($cond." order by ".$orderBy.$limit); 

	if(!empty($allItem)){

		$arrCached = array();

		$clsProperty = new Property();

		foreach($allItem as $key => $val){

			$status_id = $val['status_id'];

			$department_id = $val['department_id'];

			if($department_id > 0 && isset($arrCached[$department_id])){

				$allItem[$key]['department'] = $arrCached[$department_id];

			} else {

				if($department_id > 0){

					$arrCached[$department_id] = $clsProperty->getTitle($department_id);

					$allItem[$key]['department'] = $arrCached[$department_id];

				} else {

					$allItem[$key]['department'] = '--';

				}

			}

			if($status_id > 0 && isset($arrCached[$status_id])){

				$allItem[$key]['status_name'] = $arrCached[$status_id];

			} else {

				if($status_id > 0){

					$arrCached[$status_id] = sprintf(

						'<span class="label label-default">%s</span>', 

						$clsProperty->getTitle($status_id)

					);

					$allItem[$key]['status_name'] = $arrCached[$status_id];

				} else {

					$allItem[$key]['status_name'] = '--';

				}

			}

			$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$val['profile_id']}'");

			$total_in_billings = $total_in_sales = 0;

			if(!empty($list_billings)){

				$total_in_billings += count($list_billings);

				foreach($list_billings as $okey => $oval){

					$total_in_sales += $clsISO->processSmartNumber($oval['totalgrand']);

				}

			}

			$allItem[$key]['total_billings'] = $total_in_billings;

			$allItem[$key]['total_sales'] = $total_in_sales;

			$allItem[$key]['more_information'] = (array)json_decode($val['more_information']);

			if(empty($val["permalink"])) {

				$permalink = $clsProfile->setPermalink($core->replaceSpace($val["full_name"]),1,$val["profile_id"]);	

				$clsProfile->updateOne($val["profile_id"], array(

					'permalink'	=>	$permalink,

					'is_active'	=>	1,

					'is_verified'	=>	1,

				));

				$allItem[$key]['permalink'] = $permalink;

			}

			

		}

	}

	$assign_list["allItem"] = $allItem;

	// Return

	$html = $core->build('_ajax.broker.tpl');

	echo json_encode(array(

		'html' => $html,

		'cond' => $cond,

		'per_page' => $recordPerPage,

		'current_page' => $currentPage,

		'total_record' => $totalRecord

	)); die();

}	

function default_detail1(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$title_page,$description_page,$oneProfile,$profile_id,$image_page,$deviceType,$is_profile_domain,$broker_id;

	$clsShare = new Share();

	$clsMemberMeta = new MemberMeta();

	$clsReview = new Review();

	$classTable = "Member";

	$clsClassTable = new $classTable;

	$tableName = $clsClassTable->tbl;

	$pkeyTable = $clsClassTable->pkey;

	$clsBilling = new Billing(); 

	$array_follow = $oneProfile["more_information"]['follow'];

	

	$slug = Input::get('slug','');

	$template = (int)Input::get("template",0); 

	$action = Input::get("action",""); 

	#

	$assign_list["clsReview"] = $clsReview;

	$assign_list["clsClassTable"] = $clsClassTable;

	$assign_list["pkeyTable"] = $pkeyTable;

	$assign_list['clsMemberMeta'] = $clsMemberMeta;

	$assign_list["clsBilling"] = $clsBilling;

	$assign_list['array_follow'] = $array_follow;

	$assign_list['template'] = $template;

	$assign_list['action'] = $action;

	

	if($slug == '' || ($action == 'preview' && $template == 0)){

		header("Location: /");

		exit();

	}

	$oneItem = $clsClassTable->getByCond("permalink='".$slug."' and is_active=1 and is_verified=1 and is_trash=0 LIMIT 0,1");

	if(empty($oneItem) /*|| ($action =='preview' && $oneItem["profile_id"] != $profile_id)*/){

		header("Location: /");

	}	

	$broker_id = $oneItem['profile_id'];

	$assign_list['broker_id'] = $broker_id;

	$assign_list['avatar'] = $clsClassTable->getAvatar($broker_id,$oneItem,50,50);

	

	$more_information = $clsISO->to_array_json($oneItem['more_information']);

	$view_num = (!empty($more_information['view_num']))?((int)$more_information['view_num'] + 1):1;

	$more_information['view_num'] = $view_num;

	#birthday

	if(!empty($more_information["is_show_birthday"])) {

		if ($more_information["is_show_birthday"] == 2) {

			$oneItem["birthday"] = $clsISO->convertTimeToTextFormat($oneItem["birthday"],"d/m");			

		}else {

			unset($oneItem["birthday"]);

		}

	}else{

		$oneItem["birthday"] = $clsISO->convertTimeToTextFormat($oneItem["birthday"],"d/m/Y");	

	}

	

	$current_template = !empty($more_information['template']) ? $more_information['template'] : 1;

	if(!empty($is_profile_domain) && !empty($template)) {

		$current_template = $template;

	}

	$clsClassTable->updateOne($broker_id,["more_information"=>json_encode($more_information)]);

	if(@file_exists(ROOTPATH.$oneItem["avatar"])) { 

		$oneItem["avatar"] = $oneItem["avatar"];

	}else{

		$oneItem["avatar"] = FH_URL.$oneItem["avatar"];

	}

	$assign_list['oneItem'] = $oneItem;

	$assign_list['current_template'] = $current_template;

	

	$assign_list['more_information'] = $more_information;

	$assign_list['view_num'] = $view_num; 

	$per_page = 20; $assign_list['per_page'] = $per_page;

	$history_sale = !empty($more_information["history_sale"])?$more_information["history_sale"]:[];	

	$total_bill = count($history_sale);

	$assign_list['total_bill'] = $total_bill;	

	

	$image_billing = !empty($more_information["image_billing"]) ? $more_information["image_billing"] : array();

	

	#

	$clsFHProfile = new FHProfile();

	$oneProfileFH = $clsFHProfile->getByCond("`email`='{$oneItem["email"]}'",$clsFHProfile->pkey);

	if($oneProfileFH) {

		#hoạt động tiếp khách

		$fh_profile_id = $oneProfileFH[$clsFHProfile->pkey];

		/*if($broker_id == 289) {

			$lstShare = $clsShare->getAll("`is_trash`='0' AND `share_type`='share' ORDER BY `reg_date` DESC LIMIT 0,10");	

		}else{

			$lstShare = $clsShare->getAll("`is_trash`='0' AND `share_type`='share' AND `user_id`='{$fh_profile_id}'");

		}

		foreach ($lstShare as $key => $val) {

			$images = $clsISO->to_array_json($val['images']);

			if(!empty($images[0])) {

				$lstShare[$key]["avatar"] = $images[0];	

				unset($images[0]);

				$lstShare[$key]["lstImage"] = $images;	

			}else{

				unset($lstShare[$key]);

			}

			

		}*/

		

		#giao dịch chốt

		if($profile_id == 289 && $profile_id == $broker_id) {

			$lsBilling = $clsBilling->getAll("`is_cancel`='0' AND `is_trash`='0' AND JSON_EXTRACT(`more_information`,'$.image_poster') <>''");

		}else{

			$lsBilling = $clsBilling->getAll("`is_cancel`='0' AND `is_trash`='0' AND `staff_id`='{$fh_profile_id}' AND JSON_EXTRACT(`more_information`,'$.image_poster') <>''");

		}

		foreach ($lsBilling as $key => $val) {

			$more_billing = $clsISO->to_array_json($val['more_information']);

			if(!empty($more_billing["image_poster"])) {

				$image_billing[] = $more_billing["image_poster"];

			}

		}

//		$clsISO->print_pre($image_billing);die;

	}

	$assign_list['image_billing'] = $image_billing;

	

	$lstShare = !empty($more_information["image_receive"]) ? $more_information["image_receive"] : array();

	$assign_list['lstShare'] = $lstShare;

	

	#post

	$lstPostMeta = $clsMemberMeta->getAll("`type`='post' AND `profile_id`='{$broker_id}' ORDER BY upd_date DESC");

//	$clsISO->print_pre($lstPostMeta);die;

	$assign_list['lstPostMeta'] = $lstPostMeta;

	

	#menu

	$lstMenu = $lstMenuHidden = [];

	$number_hidden = ($deviceType == 'computer') ? 4 : 2 ;

	

	if (!empty($more_information["about"])) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-about"] = "Giới thiệu") : ($lstMenuHidden["pills-about"] = "Giới thiệu");

	}

	if (!empty($more_information["certificate"])) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-certificate"] = "Bằng cấp, chứng chỉ") : ($lstMenuHidden["pills-certificate"] = "Bằng cấp, chứng chỉ");

	}

	if (!empty($more_information["working_process"])) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-working_process"] = "Quá trình làm việc") : ($lstMenuHidden["pills-working_process"] = "Quá trình làm việc");

	}

	if (!empty($more_information["project"])) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-project"] = "Dự án tiêu biểu") : ($lstMenuHidden["pills-project"] = "Dự án tiêu biểu");

	}

	if (!empty($image_billing)) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-history-sale"] = "Danh sách giao dịch") : ($lstMenuHidden["pills-history-sale"] = "Danh sách giao dịch");

	}

	if (!empty($lstPostMeta)) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-share-sale"] = "Bài viết chia sẻ") : ($lstMenuHidden["pills-share-sale"] = "Bài viết chia sẻ");

	}

	if (!empty($lstPostMeta)) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-share"] = "Hoạt động tiếp khách") : ($lstMenuHidden["pills-share"] = "Hoạt động tiếp khách");

	}

	if (!empty($more_information["image"])) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-image"] = "Hình ảnh") : ($lstMenuHidden["pills-image"] = "Hình ảnh");

	}

	if (!empty($more_information["link_video"])) {

		(count($lstMenu) < $number_hidden) ? ($lstMenu["pills-video"] = "Video") : ($lstMenuHidden["pills-video"] = "Video");

	}

	$assign_list['lstMenu'] = $lstMenu;

	$assign_list['lstMenuHidden'] = $lstMenuHidden;

	

	

	/*=============Title & Description Page==================*/

	if($oneItem['avatar'] != ""){

		$image_page = FH_URL."/".$oneItem['avatar'];

		$assign_list["image_page"] = $image_page;

	}

	if(!empty($is_profile_domain)) {

		$title_page = $oneItem['full_name'].' - Chuyên viên tư vấn bất động sản '.BRAND_NAME.' | Uy tín - Tận tâm - Hiệu quả'; 

		$description_page = $oneItem['full_name'].' - Chuyên viên tư vấn bất động sản tại '.BRAND_NAME.', chuyên phân phối căn hộ cao cấp . Cam kết mang đến giải pháp đầu tư hiệu quả, phù hợp nhu cầu và tài chính của bạn.';

	}else{

		$title_page = $oneItem['full_name'].' - Sale BDS tại Ocean City - '.PAGE_NAME; 

		$description_page = ' Hệ thống hỗ trợ bán hàng Ocean City - '.PAGE_NAME;

	}

	

	$assign_list["title_page"] = $title_page;

	$assign_list["description_page"] = $description_page;

}



function default_save_template(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$title_page,$description_page,$oneProfile,$profile_id,$image_page;

	$clsProfile = new Profile();

	$template_id = (int)Input::post("template_id",0);

	$res = [

		"result"	=>	false

	];

	if(!empty($template_id)) {

		$more_information = $oneProfile["more_information"];

		$more_information["template"] = $template_id;

		if($clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information,JSON_UNESCAPED_UNICODE)])) {

			$res = [

				"result"	=>	true,

				"msg"		=>	"Lưu thành công",

				"url"		=>	$clsProfile->getLink($profile_id,$oneProfile)

			];

		}

	}

	echo json_encode($res,JSON_UNESCAPED_UNICODE);die;

}

function default_detail(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$title_page,$description_page,$oneProfile,$image_page;

	$classTable = "Member";

	$clsClassTable = new $classTable;

	$tableName = $clsClassTable->tbl;

	$pkeyTable = $clsClassTable->pkey;

	$assign_list["clsClassTable"] = $clsClassTable;

	$assign_list["pkeyTable"] = $pkeyTable;

	$clsBilling = new Billing(); $assign_list["clsBilling"] = $clsBilling;

	$array_follow = $oneProfile["more_information"]['follow'];

	$assign_list['array_follow'] = $array_follow;

	$slug = Input::get('slug','');

	if($slug == ''){

		header("Location: /");

		exit();

	}

	$checkBySlug = $clsClassTable->getAll("permalink='".$slug."' and is_active=1 and is_verified=1 and is_trash=0 LIMIT 0,1");	

	if(empty($checkBySlug)){

		header("Location: 404");

	}	

	$oneItem = $checkBySlug[0];

	$broker_id = $oneItem['profile_id'];

	$more_information = $clsISO->to_array_json($oneItem['more_information']);

	$view_num = (!empty($more_information['view_num']))?((int)$more_information['view_num'] + 1):1;

	$more_information['view_num'] = $view_num;

	$clsClassTable->updateOne($broker_id,["more_information"=>json_encode($more_information)]);

	$assign_list['oneItem'] = $oneItem;

	$assign_list['more_information'] = $more_information;

	$assign_list['view_num'] = $view_num; 

	$per_page = 20; $assign_list['per_page'] = $per_page;

	$history_sale = !empty($more_information["history_sale"])?$more_information["history_sale"]:[];	

	$total_bill = count($history_sale);

	$assign_list['total_bill'] = $total_bill;	

	/*=============Title & Description Page==================*/

	if($oneItem['avatar'] != ""){

		$image_page = FH_URL."/".$oneItem['avatar'];

		$assign_list["image_page"] = $image_page;

	}

	$title_page = $oneItem['full_name'].' - Sale BDS tại Ocean City - '.PAGE_NAME; 

	$assign_list["title_page"] = $title_page;

	$description_page = ' Hệ thống hỗ trợ bán hàng Ocean City - '.PAGE_NAME;

	$assign_list["description_page"] = $description_page;

}

function default_demo(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting,$clsConfiguration;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$title_page,$description_page,$oneProfile;

	/*=============Title & Description Page==================*/

	$title_page = $oneItem['full_name'].' - Sale BDS tại Ocean City - '.PAGE_NAME; 

	$assign_list["title_page"] = $title_page;

	$description_page = ' Hệ thống hỗ trợ bán hàng Ocean City - '.PAGE_NAME;

	$assign_list["description_page"] = $description_page;

}

function default_load_list_billing(){

	global $assign_list,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;

	global $oneProfile,$profile_id,$clsISO;

	$clsBilling = new Billing();

	$clsProfile = new Profile();

	$profile_id = (int)Input::post('profile_id',0);

	$per_page = (int)Input::post('perPage',20);

	$page = (int)Input::post('page',1);

	$keyword = Input::post('keyword',"");

	$start = ($page - 1)*$per_page; $assign_list['number_from'] = $start+1;

	$limit = " LIMIT {$start},{$per_page} ";  

	$html = ""; 

	$total_bill = 0;

	if($profile_id > 0){ 

		$oneProfile = $clsProfile->getOne($profile_id);

		$more_information = $clsISO->to_array_json($oneProfile['more_information']);

		$history_sale = !empty($more_information["history_sale"])?$more_information["history_sale"]:[];	

		$arr_history_sale = [];

		foreach($history_sale as $key => $value){

			if($keyword != ""){

				$keyword = strtolower($keyword);

				if(strstr(strtolower($value['stock_code']),$keyword)  || strstr(strtolower($value['project']),$keyword) || strstr(strtolower($value['customer_name']),$keyword)){

					$arr_history_sale[$key]= $value;

				}

			}else{

				$arr_history_sale[$key]= $value;

			}

		}

		$i=0;

		$lstBillings = [];

		foreach($arr_history_sale as $k => $v){

			if($i >= $start && $i< ($start+$per_page)){

				$lstBillings[$k]= $v;

			}

			++$i;

		}

		$total_bill = count($arr_history_sale);

		$assign_list['total_bill'] = $total_bill;

		$assign_list['lstBillings'] = $lstBillings;

		$number_to = $start + count($lstBillings);

		$assign_list['number_to'] = $number_to;

		$html = $core->build('_ajax.list_billing.tpl');

	}

	echo json_encode(['html'=>$html,'total_number'=>$total_bill,"start"=>$start+1,"number_to"=>$number_to]);die;

}

function default_profile(){

	global $assign_list,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;

	global $oneProfile,$profile_id,$clsISO;

	$clsCity = new City(); $assign_list["clsCity"] = $clsCity;

	$clsDistrict = new District(); $assign_list["clsDistrict"] = $clsDistrict;

	#

	$clsBroker = new Member(); 

	$assign_list["clsProfile"] = $clsProfile;

	$assign_list["group_page"] = 'member';

	$current_page = $_SERVER['REQUEST_URI'];

	$assign_list['current_page'] = $current_page;

	$email = $oneProfile["email"];

	$oneItem = $clsBroker->getByCond("`is_trash`='0' AND `email`='{$email}'");

	

	$txt_address = "";

	$first = 1;

	$address = $clsBroker->getFieldValue("address",$oneItem);

	$city = $clsBroker->getFieldValue("city",$oneItem);

	$district = $clsBroker->getFieldValue("district",$oneItem);

	$country = $clsBroker->getFieldValue("country",$oneItem); $assign['country'] = $country;

	$arr_location = [];

	if($address != '') $arr_location[] = $address;

	if($city != '') $arr_location[] = $city;

	if($district != '') $arr_location[] = $district;

	if($country != '') $arr_location[] = $country;	

	$txt_address = implode(", ",$arr_location);

	$assign['txt_address'] = $txt_address;

	

	$lstFieldMoreInfomation = [

		"certificate"	=>	array(

			'title'			=> 'Bằng cấp chứng chỉ'

		),

		"project"	=>	array(

			'title'			=> 'Dự án đã tham gia'

		),

		"image_receive"	=>	array(

			'title'			=> 'Hoạt động tiếp khách'

		),

	];

	$assign_list['lstFieldMoreInfomation'] = $lstFieldMoreInfomation;

	###

	$more_information = $clsISO->to_array_json($oneItem['more_information']);

	$assign_list["more_information"] = $more_information;

	$assign_list["oneItem"] = $oneItem;

	/*=============Title & Description Page==================*/

	$title_page = 'Hồ sơ sale - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = '';

	$assign_list["description_page"] = $description_page;

	$keyword_page = '';

	$assign_list["keyword_page"] = $keyword_page;

}

function default_load_edit_inline_field(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;

	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	###

	$html = $html_input = "";

	$p_id = (int) Input::post('p_id',0);

	$p_field = Input::post('p_field', "");

	$p_action = Input::post('p_action','_open');

	$p_element = Input::post('p_element','');

	if($p_action=='_save'){

		$p_value = Input::post('p_value');

		//$clsISO->print_pre($p_value); die();

		if($p_field=='code'){

			if($clsProfile->countItem("`code`='{$p_value}'") > 0){

				echo '_error_code'; die();

			} else {

				$clsProfile->updateOne($p_id, array(

					$p_field => $p_value

				));

			}

		} else if($p_field=='email'){

			if($clsProfile->countItem("`email`='{$p_value}'") > 0){

				echo '_error_email'; die();

			} else {

				$clsProfile->updateOne($p_id, array(

					$p_field => $p_value

				));

			}

		} else if($p_field=='department_id'){

			$list_department_id = (int) $p_value > 0 

				? $clsProperty->getListParent($p_value) : "";

			$clsProfile->updateOne($p_id, array(

				$p_field => $p_value,

				'list_department_id' => $list_department_id

			));

		} else if(in_array($p_field, array('twitter','facebook', 'linkedin','instagram','number_sale','total_sales','agency','experience','success_rate','average_rate','level_sales'))){

			$more_information = $clsProfile->getOneField('more_information', $p_id);

			$more_information = !empty($more_information) 

				? json_decode(html_entity_decode($more_information), true) : array();

			$more_information[$p_field] = $p_value;

			$clsProfile->updateOne($p_id, array(

				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

			));

		} else if(in_array($p_field, array('about','certificate','work_process','dictum_live','project_joined','achievements'))){

			$more_information = $clsProfile->getOneField('more_information', $p_id);

			$more_information = !empty($more_information) 

				? json_decode($more_information, true) : array();

			$more_information[$p_field] = $p_value;

			$clsProfile->updateOne($p_id, array(

				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

			));

		} else if($p_field=='level_id'){

			$more_information = $clsProfile->getOneField('more_information', $p_id);

			$more_information = !empty($more_information) 

				? json_decode(html_entity_decode($more_information), true) : array();

			$level_logs = isset($more_information['level_logs']) 

				? $more_information['level_logs'] : array();

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

			$more_information['level_logs'] = $level_logs;

			$clsProfile->updateOne($p_id, array(

				$p_field => $p_value,

				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

			));

		} else if($p_field=='birthday'){

			$clsProfile->updateOne($p_id, array(

				$p_field => $clsISO->convertTextToTime($p_value)

			));

		} else if($p_field=='full_name'){

			$getPermalink = $clsProfile->setPermalink($core->replaceSpace($p_value),1,$p_id);

			$clsProfile->updateOne($p_id, array(

				$p_field => $p_value,

				'permalink'	=>	$getPermalink

			));

		} else {

			$clsProfile->updateOne($p_id, array(

				$p_field => $p_value

			));

		}

	}

	if(in_array($p_field, array('full_name','email','phone','code','address','CCID'))){

		$oProfile = $clsProfile->getOne($p_id, $p_field);

		// $clsISO->print_pre($oProfile); die();

		if($p_action=='_cancel' || $p_action=='_save'){

			$html.= $oProfile[$p_field];

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$html_input = '<input class="form-control form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$oProfile[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />';

		}

	} else if(in_array($p_field, array('about','certificate','work_process','dictum_live','project_joined','achievements'))){ 

		$more_information = $clsProfile->getOneField('more_information', $p_id);

		$more_information = !empty($more_information) 

			? json_decode(html_entity_decode($more_information), true) : array();

		if($p_action=='_cancel' || $p_action=='_save'){

			$html.= $more_information[$p_field];

		} else {

			$html_input = '<textarea id="'.$clsISO->getUniqid().'" class="form-control isoTextArea edit_profile_field_'.$p_field.'_'.$p_id.'" cols="255" rows="10">'.$more_information[$p_field].'</textarea>';

		}

	} else if(in_array($p_field, array('facebook', 'twitter', 'linkedin','instagram','number_sale','total_sales','agency','experience','success_rate','average_rate','level_sales'))){

		$more_information = $clsProfile->getOneField('more_information', $p_id);

		$more_information = !empty($more_information) 

			? json_decode(html_entity_decode($more_information), true) : array();

		if($p_action=='_cancel' || $p_action=='_save'){

			$html.= $more_information[$p_field];

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$html_input = '<input class="form-control form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />';

		}

	} else if($p_field=='birthday') {

		$oProfile = $clsProfile->getOne($p_id, $p_field);

		// $clsISO->print_pre($oProfile); die();

		if($p_action=='_cancel' || $p_action=='_save'){

			$html.= $clsISO->convertTimeToText($oProfile[$p_field]);

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$html_input = '<input class="form-control datepicker form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$clsISO->convertTimeToText($oProfile[$p_field]).'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yyyy" />

			<style type="text/css">.ui-datepicker{ z-index:99999 !important;}</style>';

		}

	} else if($p_field=='start_date') {

		$oProfile = $clsProfile->getOne($p_id, $p_field);

		// $clsISO->print_pre($oProfile); die();

		if($p_action=='_cancel' || $p_action=='_save'){

			$html.= $oProfile[$p_field];

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$html_input = '<input class="form-control datepicker form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" value="'.$oProfile[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yyyy" />

			<style type="text/css">.ui-datepicker{ z-index:99999 !important;}</style>';

		}

	} else if($p_field=='status_id' || $p_field=='level_id'){

		$oProfile = $clsProfile->getOne($p_id, $p_field);

		if($p_action=='_cancel' || $p_action=='_save'){

			if(isset($oProfile[$p_field]) && (int) $oProfile[$p_field] > 0){

				$html.= $clsProperty->getTitle($oProfile[$p_field]);

			} else {

				$html.= "--";

			}

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$label = 'Tình trạng';

			$property_type = '_STATUS_STAFF';

			if($p_field=='level_id'){

				$label = 'Cấp bậc';

				$property_type = '_LEVEL_STAFF';

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

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$html_input = '<select class="form-control form-select form-control-sm edit_profile_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />

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

			$html.='<a class="editInlineField ml-1" onClick="$Core.broker.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';

		} else {

			$department_id = $oProfile['department_id'];

			if(!empty($department_id)){

				$arrOption = array();

				$for_id = $clsProperty->getOneField('for_id', $department_id);

				$clsProperty->makeOption($for_id, '_ROLE', 0, 0, $arrOption);

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

	}

	if($p_action=='_cancel' || $p_action=='_save'){

		echo $html; die();

	} else {

		if($p_element == "textarea"){

			$html = '<div class="d-flex justify-content-end input-group inline-editor-container flex-wrap gap-1">

			<div class="w-100">'.$html_input.'</div>

			<div class="d-flex gap-1">

				<button class="btn px-2 btm-sm btn-outline-success" onClick="$Core.broker.save_edit_inline_field(this, event)" p_element="'.$p_element.'" p_field="'.$p_field.'" p_id="'.$p_id.'">Xác nhận</button>

				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.broker.cancel_edit_inline_field(this, event)" p_element="'.$p_element.'" p_field="'.$p_field.'" p_id="'.$p_id.'">Huỷ</button>

			</div>

		</div>';

		}else{

			$html = '<div class="d-flex input-group inline-editor-container">

			'.$html_input.'

			<div class="btn-group">

				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.broker.save_edit_inline_field(this, event)" p_element="'.$p_element.'" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>

				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.broker.cancel_edit_inline_field(this, event)" p_element="'.$p_element.'" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>

			</div>

		</div>';

		}

	}

	// Return

	echo $html; die();

}

function default_uploadImage(){

	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,

	$clsConfiguration,$IsoEditor,$clsSiteCrop,$profile_id;

	$msg = '_error';

	$clsProfile = new Profile();

	$clsBroker = new Broker();

	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){

		$type = Input::post("type","");

		if(!empty($_FILES['images'])) {

			$images = $_FILES['images'];

			if(!empty($images['name']) && $profile_id >0){ 

				$ii = 0; //Init

				$oneProfile = $clsProfile->getOne($profile_id,"more_information,profile_type,target_id");	 

				if(!empty($oneProfile)){

					$more_information = (array)json_decode($oneProfile['more_information']);

					$results = $clsBroker->uploadImageGoogleDriver($images);

					if($type == 'avatar'){

						if(!empty($results) && $clsProfile->updateOne($profile_id,["avatar"=>$results[0]])){

							$msg = '_success|||' .$results[0]; 

						}

					}else if($type == 'banner'){

						$more_information['banner'] = $results[0];

						if(!empty($results) && $clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

							$msg = '_success|||' .$results[0]; 

						}

					}else if($type == 'image_certificate'){

						$more_information['image_certificate'] = $results[0];

						if(!empty($results) && $clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

							$html .= '<div class="" data-fancybox="gallery" href="'.$results[0].'">

										<div class="rounded img_scale">

											<img class="drag-item cursor-pointer" src="'.$results[0].'" alt="avatar" style="width: 100%;height:200px">

										</div>

									</div>';

							$msg = '_success|||' .$html;

						}

					}else{

						$arr_images = (!empty($more_information[$type]))?$more_information[$type]:array();

						$arr_images = array_merge($arr_images,$results);

						$more_information[$type] = $arr_images;

						if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

							// Return

							$html = '';

							if(!empty($results)){

								foreach($results as $image){

									$html .= '<div class="item col-4 mb-2" data-fancybox="gallery" href="'.$image.'">

												<div class="rounded img_scale">

													<img class="drag-item cursor-pointer" src="'.$image.'" alt="avatar" style="width: 100%;height:80px">

												</div>

											</div>';

								}

							}

							$msg = '_success|||' .$html;

						}

					}

				}			

			}

		}

		

	}

	// Return

	echo $msg; die();

}

function default_addVideo(){

	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,

	$clsConfiguration,$IsoEditor,$clsSiteCrop;

	$html = '';

	$clsProfile = new Profile();

	$profile_id = (int)Input::post("profile_id",0);

	$link_file = Input::post("link_file","");

	if(!empty($link_file) && $profile_id >0){ 

		$oneProfile = $clsProfile->getOne($profile_id,"more_information,profile_type,target_id");	

		if(!empty($oneProfile)){

			$more_information = (array)json_decode($oneProfile['more_information']);

			$more_information['link_video'] = $link_file;

			if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

				// Return

				$html = $clsISO->getEmbedVideo($link_file);

			}

		}

	}

	// Return

	echo $html; die();

}

function default_formAddField(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;

	###

	$clsProfile = new Profile();

	$type = Input::post("type","open");

	$field_id = Input::post("field_id",""); $assign_list['field_id'] = $field_id;

	$field = Input::post("field",""); $assign_list['field'] = $field;

	$data=[

		"result"	=>	false

	];

	if($type == "open"){

		if($field_id != ""){

			$Profile = $clsProfile->getOne($profile_id);

			$more_information = $clsISO->to_array_json($Profile['more_information']);

			$arr_field = !empty($more_information[$field])?$more_information[$field]:[];

		 	$assign_list['oneItem'] = $arr_field[$field_id];

		}

//		var_dump($field_id,$field,$arr_field);die;

		$uid = $clsISO->getUniqid();

		$assign_list['uid'] = $uid;

		$html = $core->build('_ajax.formAddField.tpl');

		$data = [

			'result'	=>	true,

			'uid'	=>	$uid,

			'html' => $html,

		];

	}else if($type == 'add'){

		$title = Input::post('title',"");

		$image_hidden = Input::post('image_hidden',"");

		$total_sale_project = (int)Input::post('total_sale_project',"0");

		$star = (int)Input::post('star',"0");

		$content = Input::post('content',"");

		$customer_name = Input::post('customer_name',"");

		$files = $_FILES['imgdata'];

		$clsUploadFile = new UploadFile();

		$image = array();

		$image["name"] = $files['name'];

		$image["type"] = $files['type'];

		$image["tmp_name"] = $files['tmp_name'];

		$image["error"] = $files['error'];

		$image["size"] = $files['size'];

		$date = Input::post("date","");

		$link_image = $image_hidden;

		$Profile = $clsProfile->getOne($profile_id);

		$more_information = $clsISO->to_array_json($Profile['more_information']);

		$arr_field = !empty($more_information[$field])?$more_information[$field]:[];

		if(!empty($image["name"])){

			if(@is_uploaded_file($image['tmp_name'])){

				$clsUploadFile = new UploadFile();

				$upload_file = $clsUploadFile->uploadItem($image,"/BROKER/".$field,EXTENSION_FILE_UPLOAD);

				$file_name = $image['name'];

				$file_size = $image['size'];

				// Upload file to google drive

				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_BROKER_ID, true);

//				echo "[".$profile_id."]".$oneProfile["full_name"];die;

				$folder_id = $clsGoogleUpload->create_folder("[".$profile_id."]".$oneProfile["full_name"]);

				$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);

				$uploadfile = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';							

				if(!empty($uploadfile)){

					$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";

				}

				@unlink(ROOTPATH . $upload_file);

				// Update to DB

			}

		}

		$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();

		if(!empty($arr_field[$field_id])){

			$image_old = $arr_field[$field_id]['image'];

		}

		if($field == "working_process") {

			foreach($_POST as $key=>$val){

				$tmp = explode('-',$key);

				if($tmp[0]=='iso'){

					$arr_field[$field_id][$tmp[1]] = addslashes($val);

				}

			}

			$arr_field[$field_id]["image"] = $link_image;

		}else{

			$arr_field[$field_id] = [

				'title'					=>	addslashes($title),

				'total_sale_project'	=>	$total_sale_project,

				'image'					=>	$link_image,

				'star'					=>	$star,

				'content'				=>	addslashes($content),

				'customer_name'				=>	addslashes($customer_name),

				'date'					=>	date("d/m/Y"),

			];

		}

		$assign_list['arr_field'] = $arr_field;

		$html = $core->build('_ajax.loadListField.tpl');

		$more_information[$field] = $arr_field;

		if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

			$data=[

				"result"	=>	true,

				"html"		=>	$html

			];

		}

	}else if($type == "addHistorySale"){

		$stock_code = Input::post("stock_code","");

		$project = Input::post("project","");

		$customer_name = Input::post("customer_name","");

		$price = Input::post("price","");

		$date_trading = Input::post("date_trading","");

		$image_hidden = Input::post('image_hidden',"");

		$Profile = $clsProfile->getOne($profile_id);

		$more_information = $clsISO->to_array_json($Profile['more_information']);

		$arr_field = !empty($more_information[$field])?$more_information[$field]:[];

		$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();

		$file = $_FILES["imgdata"];

//		$clsISO->print_pre($file);die;

		$link_image = $image_hidden;

		if(!empty($file["name"])){

			if(@is_uploaded_file($file['tmp_name'])){

				$clsUploadFile = new UploadFile();

				$upload_file = $clsUploadFile->uploadItem($file,"/BROKER/".$field,EXTENSION_FILE_UPLOAD);

				$file_name = $file['name'];

				$file_size = $file['size'];

				// Upload file to google drive

				$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_BROKER_ID, true);

//				echo "[".$profile_id."]".$oneProfile["full_name"];die;

				$folder_id = $clsGoogleUpload->create_folder("[".$profile_id."]".$oneProfile["full_name"]);

				$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);

				$uploadfile = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';							

				if(!empty($uploadfile)){

					$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";

				}

				@unlink(ROOTPATH . $upload_file);

				// Update to DB

			}

		}

		

		$arr_field[$field_id] = [

			'stock_code'	=>	$stock_code,

			'project'		=>	$project,

			'customer_name'	=>	$customer_name,

			'price'			=>	$clsISO->processSmartNumber($price),

			'image'			=>	$link_image,

			'date_trading'	=>	$date_trading,

		];

		$assign_list['arr_field'] = $arr_field;

		$html = $core->build('_ajax.loadListField.tpl');

		$more_information[$field] = $arr_field;

		if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

			$data=[

				"result"	=>	true,

				"html"		=>	$html

			];

		}

	}else if($type == "delete"){

		$Profile = $clsProfile->getOne($profile_id);

		$more_information = $clsISO->to_array_json($Profile['more_information']);

		$arr_field = !empty($more_information[$field])?$more_information[$field]:[];

		$field_id = ($field_id != "")?$field_id:$clsISO->getUniqid();

		if($field != "" && $field_id != ""){

			unset($arr_field[$field_id]);

			$assign_list['arr_field'] = $arr_field;

			$html = $core->build('_ajax.loadListField.tpl');

			$more_information[$field] = $arr_field;

			if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

				$data=[

					"result"	=>	true,

					"html"		=>	$html

				];

			}

		}

	}

	echo json_encode($data); die();

}

function default_showHideBirthday(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;

	###

	$clsProfile = new Profile();

	$is_show_birthday = (int)Input::post("is_show_birthday",0); 

	$data=[

		"result"	=>	false

	];

	$_oProfile = $clsProfile->getOne($profile_id,"more_information");

	$more_information = $clsISO->to_array_json($_oProfile['more_information']);

	$more_information["is_show_birthday"] = $is_show_birthday;

	if($clsProfile->updateOne($profile_id,["more_information"=>json_encode($more_information)])){

		$data=[

			"result"	=>	true,

		];

	}

	echo json_encode($data); die();

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

	$keyword_page,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_lang,$clsConfiguration,$profile_id,$oneProfile;

	#

	$clsProfile = new Profile();

	$clsUploadFile = new UploadFile();

	$imgdata = Input::post('imgdata');

	$filename = Input::post('filename');

	$clsAPI = new API();

	if(!$filename) $filename = $clsISO->getUniqid().'.jpg';

	#

	$avatar = ''; 

	$msg = 'error';

	if(!empty($imgdata)){

		$avatar = $clsUploadFile->base642imagejpeg($imgdata, $filename, "/BROKER/avatar");

		if(!empty($avatar) && file_exists(ROOTPATH.$avatar)){

			$old_avatar = $oneProfile['avatar'];

			if(!empty($old_avatar) && @file_exists(ROOTPATH.$old_avatar)){

				@unlink(ROOTPATH.$old_avatar);

			}

			if($clsProfile->updateOne($profile_id, array(

				'avatar' => $avatar

			))){

				$msg = "_success";

			};

		}

		

		/*$data_json = $clsAPI->get("upload-avatar",["profile_id"=>$profile_id,"imgdata"=>$imgdata,'filename'=>$filename]); 

		$res = $clsISO->to_array_json($data_json);	

		if($res["result"]){

			$avatar = FH_URL.$res['avatar'];

			$msg = "success";

		}*/

	}

	// Return

	echo $msg.'|||'.$avatar; die();

}

function default_help_upgrade_package(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;

	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$oneProfile;

	$clsProperty = new Property();

	$package_id = (int)Input::post("package_id",0);

	

	if($package_id > 0){

		$onePackage = $clsProperty->getOne($package_id,"title,more_information");

		if(!empty($onePackage)){

			$title = $onePackage['title'];

			$more_information = (array)json_decode($onePackage['more_information']);

			$price_month = $more_information['price_month'];

			$price_6month = $more_information['price_6month'];

			$price_year = $more_information['price_year'];

			$text_money = $clsISO->priceFormat($price_month).$clsISO->getRate()."/1 tháng hoặc ".$clsISO->priceFormat($price_6month).$clsISO->getRate()."/6 tháng hoặc ".$clsISO->priceFormat($price_year).$clsISO->getRate()."/12 tháng";			

			$html = $core->build('_ajax.help_upgrade_package.tpl');

			$html = str_replace("[%MONEY_PACKAGE%]",$text_money,$html);

			$html = str_replace("[%NAME_PACKAGE%]","&#8221;".$title."&#8246;",$html);

			echo json_encode(array(

				"result"	=>	true,

				'html' => $html,

				'uid' => $clsISO->getUniqid()

			)); die();

		}

	}

	echo json_encode(array(

		"result"	=>	false,

		'html' => ""

	)); die();

}



function default_open_meta(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;

	###

	$clsMemberMeta = new MemberMeta();

	$meta_id = (int)Input::post("meta_id",0); $assign_list['meta_id'] = $meta_id;

	$gId = Input::post("gId",""); $assign_list['gId'] = $gId;

	$res=[

		"result"	=>	false

	];

	if(!empty($meta_id)){

		$oneItem = $clsMemberMeta->getOne($meta_id);

		$assign_list['oneItem'] = $oneItem;

	}

	$uid = $clsISO->getUniqid();

	$assign_list['uid'] = $uid;

	$html = $core->build('_ajax.open_meta.tpl');

	$res = [

		'result'	=>	true,

		'uid'	=>	$uid,

		'html' => $html,

	];

	echo json_encode($res,JSON_UNESCAPED_UNICODE);die;

}

function default_add_meta(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;

	###

	$clsMemberMeta = new MemberMeta();

	$meta_id = (int)Input::post("meta_id",0);

	$title = Input::post("title","");

	$content = Input::post("content","");

	$image_hidden = Input::post('image_hidden',"");

	$res=[

		"result"	=>	false

	];

	$upd_data = [

		"profile_id"	=>	$profile_id,

		"type"	=>	"post",

		"upd_date"	=>	time(),

	];

	$upd_data["content"] = $content;

	foreach($_POST as $key=>$val){

		$tmp = explode('-',$key);

		if($tmp[0]=='iso'){

			$upd_data[$tmp[1]] = addslashes($val);

		}

	}

	$upd_data["title"] = addslashes($title);

//	$clsISO->print_pre($upd_data);die;

	

	$link_image = $image_hidden;

	if(!empty($_FILES["image"])){

		if(@is_uploaded_file($_FILES["image"]['tmp_name'])){

			$clsUploadFile = new UploadFile();

			$upload_file = $clsUploadFile->uploadItem($_FILES["image"],"/BROKER/".$field,EXTENSION_FILE_UPLOAD);

			$file_name = $_FILES["image"]['name'];

			$file_size = $_FILES["image"]['size'];

			// Upload file to google drive

			$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_BROKER_ID, true);

	//				echo "[".$profile_id."]".$oneProfile["full_name"];die;

			$folder_id = $clsGoogleUpload->create_folder("[".$profile_id."]".$oneProfile["full_name"]);

			$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);

			$uploadfile = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';							

			if(!empty($uploadfile)){

				$link_image = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";

			}

			@unlink(ROOTPATH . $upload_file);

			// Update to DB

		}

	}

	$upd_data["image"] = $link_image;

	var_dump($uploadfile,$link_image,$upd_data);	

	$assign_list['field'] = "post";

	$lstPost = $clsMemberMeta->getAll("`type`='post' AND `profile_id`='{$profile_id}'");

	$assign_list['lstPost'] = $lstPost;

	$html = $core->build('_ajax.loadListField.tpl');

	if(!empty($meta_id)) {

		if($clsMemberMeta->updateOne($meta_id,$upd_data)) {

			$res=[

				"result"	=>	true,

				"msg"	=>	"Cập nhật thành công",

			];

		}

	}else{

		$upd_data["reg_date"] = time();

		$upd_data[$clsMemberMeta->pkey] = $clsMemberMeta->getMaxId();

		if($clsMemberMeta->insert($upd_data)) {

			$res=[

				"result"	=>	true,

				"msg"	=>	"Thêm mới thành công",

			];

		}

	}

	echo json_encode($res,JSON_UNESCAPED_UNICODE);die;

}

function default_delete_meta(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page

	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;

	###

	$clsMemberMeta = new MemberMeta();

	$meta_id = (int)Input::post("meta_id",0);

	$res=[

		"result"	=>	false,

	];

	if(!empty($meta_id)) {

		if($clsMemberMeta->deleteOne($meta_id)) {

			$res=[

				"result"	=>	true,

				"msg"	=>	"Xóa thành công",

			];

		}

	}

	echo json_encode($res,JSON_UNESCAPED_UNICODE);die;

}

function default_load_table(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;

	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$oneProfile;

	$clsProperty = new Property();

	$type = Input::get("type","");

	$gId = Input::post("gId","");

	if($type == "post") {

		$clsMemberMeta = new MemberMeta();

		$lstPost = $clsMemberMeta->getAll("`type`='post' AND `profile_id`='{$profile_id}'");

		$assign_list['lstPost'] = $lstPost;

	}else{

		$more_information = $oneProfile["more_information"];

		$arr_field = !empty($more_information[$type]) ? $more_information[$type] : array();

//		$clsISO->print_pre($arr_field);die;

		$assign_list['arr_field'] = $arr_field;

	}

	

	$assign_list['field'] = $type;

	$assign_list['gId'] = $gId;

	

	$html = $core->build('_ajax.loadListField.tpl');

	echo json_encode(array(

		"result"	=>	false,

		'html' => $html

	)); die();

}

?>