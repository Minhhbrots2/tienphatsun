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
function default_default(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $oneProfile,$profile_id,$clsISO;
	$clsBilling = new Billing(); $assign_list["clsBilling"] = $clsBilling;
	$clsClient = new Client(); $assign_list["clsClient"] = $clsClient;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	$clsProperty = new Property(); $assign_list["clsProperty"] = $clsProperty;
	
	$per_page = 20;
	$current_page = Input::get('page',1);
	$keyword = Input::get('keyword');
	if(isset($_GET['per_page'])){
		$per_page = Input::get('per_page',20);
		vnSessionSetVar('_ss_per_page', $per_page);
	} else if(vnSessionExist('_ss_per_page')){
		$per_page = vnSessionGetVar('_ss_per_page');
	}
	$link = '/quan-ly-khach-hang.html';
	$hasCond = false;
	if(isset($_POST['hidden_search']) && $_POST['hidden_search'] == 'hidden_search'){
		$keyword = Input::post("keyword","");
		if(!empty($keyword)){
			$link .= ($hasCond?'&':'?') . 'keyword='.$keyword;
			$hasCond = true;
		}
		header('Location:' . $link);
		exit();
	}
	
	if(!empty($keyword)) $cond=" and (`full_name` like '%{$keyword}%' or `email` like '%{$keyword}%' or `phone` like '%{$keyword}%')";
	
	/*if(!$clsISO->checkPermission("customer_sale") && !$clsISO->checkDEV()){
		$cond .= " and staff_id like '%|".$profile_id."|%'";
	}*/
	
	$arr_Gender = $clsProperty->getArraySearchByKey("_GENDER");
	
	$assign_list["keyword"] = $keyword;
	$assign_list["current_page"] = $current_page;
	$assign_list["per_page"] = $per_page;
	$total_all = $clsClient->countItem("is_trash=0");
	$total_record = $clsClient->countItem("is_trash=0 {$cond}");
	$total_page = ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	$lstClientBirthday  = $clsClient->getAll("is_trash=0 and FROM_UNIXTIME(`birthday`,'%d/%m')='".date("d/m")."' {$cond} ORDER BY `reg_date` DESC");
	foreach($lstClientBirthday as $key => $value){
		$lstClientBirthday[$key]['gender'] = $arr_Gender[$value['gender']]['title'];
	}
	
	$lstClient  = $clsClient->getAll("is_trash=0 {$cond} ORDER BY `reg_date` DESC".$limitCond);
	$array_cache = [];
	$check_permiss = $clsISO->checkPermission("customer_sale");
	foreach ($lstClient as $key => $value) {
		$lstClient[$key]['gender'] = $arr_Gender[$value['gender']]['title'];
		$lstClient[$key]['birthday'] = ($value['birthday'] != "")?date("d/m/Y",$value['birthday']):"";
		$arr_billings = $clsISO->getArrayByTextSlash($value['billing_id']);
		$lstBilling = $clsBilling->getAll($clsBilling->pkey." IN (".implode(",",$arr_billings).")",$clsBilling->pkey.",stock_id,stock_code");
		$lstClient[$key]['lst_stock'] = "";
		foreach($lstBilling as $k => $v) {
			$lstClient[$key]['lst_stock'] .= '<a target="_blank" href="/'.$v["stock_code"].'.html">'.$clsISO->makeIcon('bx-link-external', $v["stock_code"]).'<a/><br>';
		}
		$arr_staff = $clsISO->getArrayByTextSlash($value['staff_id']);
		if(in_array($profile_id,$arr_staff) || $check_permiss || $clsISO->checkDEV()){
			$lstClient[$key]['permiss_view'] = 1;
		}else{
			$lstClient[$key]['permiss_view'] = 0;
		}
	} 
	$assign_list["lstClientBirthday"] = $lstClientBirthday;
	$assign_list["lstClient"] = $lstClient;
//	$clsISO->print_pre($lstClient);die;
	
	$assign_list["total_all"] = $total_all;
	$assign_list["total_record"] = $total_record;
	$assign_list["total_page"] = $total_page;
	$assign_list["current_page"] = $current_page;
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $per_page,
		'link_page_1'	=> '/quan-ly-khach-hang.html',
		'link' => '/quan-ly-khach-hang/'
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links_page(true);
	$assign_list["html_pager"] = $html_pager;
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý khách hàng - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
function default_detail(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	global $oneProfile,$profile_id,$clsISO;
	$clsBilling = new Billing(); $assign_list["clsBilling"] = $clsBilling;
	$clsClient = new Client(); $assign_list["clsClient"] = $clsClient;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	$clsProperty = new Property(); $assign_list["clsProperty"] = $clsProperty;
	
	$slug = Input::get('slug',"");
	$client_id = (int)Input::get('client_id',0);
	if($slug == "" || $client_id <= 0){
		header("Location: /quan-ly-khach-hang.html");exit();
	}
	$oneItem = $clsClient->getByCond("slug='{$slug}' AND client_id='{$client_id}' AND is_trash=0");
	if(empty($oneItem)){
		header("Location: /404");exit();
	}
	$oneItem['gender'] = ($oneItem['gender'] != "") ? $clsProperty->getTitle($oneItem['gender']) : "";
	$oneItem['issuance_date'] = ($oneItem['issuance_date'] != "") ? date("d/m/Y",$oneItem['issuance_date']) : "";	
	$oneItem['birthday'] = ($oneItem['birthday'] != "") ? date("d/m/Y",$oneItem['birthday']) : "";	
	$assign_list['oneItem'] = $oneItem;
	
	$arr_billings = $clsISO->getArrayByTextSlash($oneItem['billing_id']);
	$cond = " billing_id IN (".implode(",",$arr_billings).")";
	if(!$clsISO->checkPermission("customer_sale") && !$clsISO->checkDEV()){
		$cond .= " and staff_id='{$profile_id}'";
	}
	$listBilling = $clsBilling->getAll($cond);
	foreach($listBilling as $key => $value){
		$more_information = (!empty($value['more_information'])) ? $clsISO->to_array_json($value['more_information']) : [];
		$listBilling[$key]["more_information"] = $more_information;
	}
	
	$assign_list["listBilling"] = $listBilling;
	
	/*=============Title & Description Page==================*/
	$title_page = 'Quản lý khách hàng - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = '';
	$assign_list["description_page"] = $description_page;
	$keyword_page = '';
	$assign_list["keyword_page"] = $keyword_page;
}
function default_edit_client(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$assign_list;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsClient = new Client();
	$clsProperty = new Property();
	
	$lstGender = $clsProperty->getAllCache("property_type='_GENDER'",$clsProperty->pkey.',title');
	$assign_list["lstGender"] = $lstGender;
	###
	$uid = $clsISO->getUniqid();
	$action = Input::post('action', "open");
	$client_id = (int) Input::post('client_id', 0);
	
	$oneClient = $clsClient->getOne($client_id);
	$oneClient['birthday'] = ($oneClient['birthday'] != "") ? date("d/m/Y",$oneClient['birthday']) : "";
	$oneClient['issuance_date'] = ($oneClient['issuance_date'] != "") ? date("d/m/Y",$oneClient['issuance_date']) : "";
	
	// Return
	$assign_list["uid"] = $uid;
	$assign_list["oneClient"] = $oneClient;
	$assign_list["max_day"] = date("Y-m-d",strtotime("-1 day"));
	$data = [
		"result"	=>	false,
		"message"	=>	"ERROR"
	];
	if($action == "open"){
		$html = $core->build('_ajax.edit_client.tpl');
		$data = [
			'result'	=>	true,
			'uid' 		=> $uid,
			'html'	 	=> $html
		];
	}else if($action == 'edit'){
		$full_name = Input::post("full_name","");
		$birthday = Input::post("birthday","");
		$birthday = ($birthday != "")? strtotime(str_replace("/","-",$birthday)) : "";
		$phone = Input::post("phone","");
		$email = Input::post("email","");
		$address = Input::post("address","");
		$contact_address = Input::post("contact_address","");
		$identity_card = Input::post("identity_card","");
		$issuance_date = Input::post("issuance_date","");
		$issuance_date = ($issuance_date != "")? strtotime(str_replace("/","-",$issuance_date)) : "";
		$issuance_location = Input::post("issuance_location","");
		$gender = (int)Input::post("gender","0");
		if($client_id > 0){
			$array_data = [
				'full_name'			=>	$full_name,
				'birthday'			=>	$birthday,
				'phone'				=>	$phone,
				'email'				=>	$email,
				'address'			=>	$address,
				'contact_address'	=>	$contact_address,
				'identity_card'		=>	$identity_card,
				'issuance_date'		=>	$issuance_date,
				'issuance_location'	=>	$issuance_location,
				'gender'			=>	$gender,
			];
			if($clsClient->updateOne($client_id,$array_data)){
				$data = [
					"result"	=>	true,
					"message"	=>	"Cập nhật thành công"
				];
			}
		}
	}
	echo json_encode($data);die;	
}
function default_view_client(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$assign_list;
	$clsBilling = new Billing();
	$clsClient = new Client();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$tabfocus = (int) Input::post('tabfocus', 1);
	$tabfocus = 2;
	$client_id = (int) Input::post('client_id', 0);
	if($client_id == 0){
		echo '_invalid';
		die();
	}
	$oneClient = $clsClient->getOne($client_id);
	$oneClient['birthday'] = ($oneClient['birthday'] != "") ? date("d/m/Y",$oneClient['birthday']) : "";
	$oneClient['gender'] = ($oneClient['birthday'] != "") ? $clsProperty->getTitle($oneClient['gender']) : "";
	$oneClient['issuance_date'] = ($oneClient['issuance_date'] != "") ? date("d/m/Y",$oneClient['issuance_date']) : "";
	$arr_billings = $clsISO->getArrayByTextSlash($oneClient['billing_id']);
	$cond = " billing_id IN (".implode(",",$arr_billings).")";
	if(!$clsISO->checkPermission("customer_sale") && !$clsISO->checkDEV()){
		$cond .= " and staff_id='{$profile_id}'";
	}
	$listBilling = $clsBilling->getAll($cond);
	foreach($listBilling as $key => $value){
		$more_information = (!empty($value['more_information'])) ? $clsISO->to_array_json($value['more_information']) : [];
		$listBilling[$key]["more_information"] = $more_information;
	}
	
	$assign_list["listBilling"] = $listBilling;
	
	// Return
	$assign_list["oneClient"] = $oneClient;
	$assign_list["more_information"] = $more_information;
	$assign_list["uid"] = $uid;
	$assign_list["tabfocus"] = $tabfocus;
	$html = $core->build('_ajax.client.view.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_delete_client(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$assign_list;
	$clsClient = new Client();
	###
	$data = [
		"result"	=>	false,
		"message"	=>	"Xoá thất bại"
	];
	$client_id = (int)Input::post("client_id",0);
	if($client_id > 0){
		if($clsClient->updateOne($client_id,["is_trash"=>1])){
			$data = [
				"result"	=>	true,
				"message"	=>	"Xoá thành công"
			];
		}
	}
	echo json_encode($data); die();
}

function default_loadFormSubmit(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$assign_list;
	$clsBilling = new Billing();
	$clsClient = new Client();
	###
	$uid = $clsISO->getUniqid();
	$lst_client = Input::post("lst_client","");
	$lst_client = trim($lst_client,"|");
	$arr_client = explode("|",$lst_client);
	$type = Input::post("type","");
	$assign_list['arr_client'] = $arr_client;	
	$assign_list['type'] = $type;	
	$assign_list['uid'] = $uid;
	
	$lstClient = $clsClient->getAll("1=1",$clsClient->pkey.",full_name");
	$assign_list['lstClient'] = $lstClient;
//	var_dump($clsForm);die;
	$html = $core->build('_ajax.client.FormSubmit.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_sendEmailClient(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$profile_id,$clsISO,$assign_list;
	$clsBilling = new Billing();
	$clsClient = new Client();
	###
	$client_ids = Input::post("client_ids",array());
	$title = Input::post("title","");
	$files = $_FILES["files"];
	
	
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}

