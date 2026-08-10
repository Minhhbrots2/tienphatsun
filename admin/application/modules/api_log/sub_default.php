<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	/**/
	$classTable = "APILog";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	$action = Input::get("action","stock");
	$assign_list["action"] = $action;
	#
	$field = "{$clsClassTable->pkey},for_id,type,title,content,more_information";
	$list_APILog = $clsClassTable->getAll("`is_updated`='0' AND `type`='{$action}' LIMIT 0,10", $field);
	
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
	$assign_list["list_projects"] = $list_projects;
}
function default_load_log(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsProject = new Project();
	$clsAPILog = new APILog();
	$clsProperty = new Property();
	$clsUserAdmin = new UserAdmin();
	#
	$action = Input::post("action","stock");
//	$clsAPILog->setDeBug(1);
	$lstItem = $clsAPILog->getAll("`type`='".$action."' ORDER BY reg_date DESC");
//	$clsISO->print_pre($list_docs); die();
	$smarty->assign('lstItem', $lstItem);
	$smarty->assign('clsUserAdmin', $clsUserAdmin);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.logs.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_sync_data_stock(){
	// ini_set('memory_limit','4096M');
	ini_set('max_execution_time', 0);
	global $core,$smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$user_id = $core->_USER['user_id'];
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsAPILog = new APILog();
	$smarty->assign('clsAPILog', $clsAPILog);
	$response = ["result" => false, "msg" => "Lỗi đồng bộ"];
	$arr_bedroom = [
		"21"	=>	"Studio",
		"22"	=>	"1",
		"23"	=>	"1",
		"24"	=>	"2",
		"25"	=>	"2",
		"26"	=>	"2",
		"27"	=>	"3",
		"857"	=>	"4+",
	];
	###
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	$arr_direction = [];
	foreach($lstDirection as $key => $val) {
		if($val['title'] == "ĐB") {
			$arr_direction[$val["property_id"]] = "Đông Bắc";
		}elseif ($val['title'] == "TB") {
			$arr_direction[$val["property_id"]] = "Tây Bắc";
		}elseif ($val['title'] == "ĐN") {
			$arr_direction[$val["property_id"]] = "Đông Nam";
		}elseif ($val['title'] == "TN") {
			$arr_direction[$val["property_id"]] = "Tây Nam";
		}else{
			$arr_direction[$val["property_id"]] = $val['title'];
		}
	}
	$arr_project_ins = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHOP3_ID, _PROJECT_VHGG_ID);
	$list_stocks = $clsStock->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `agency_id`='"._AGENCY_FH_ID."' 
		and `status_id`='"._STOCK_STATUS_DQ_ID."' and `project_id` in (".implode(',',$arr_project_ins).") limit 10");
	$error = $success = 0;
	$success_logs = $error_logs = array();
	if(!empty($list_stocks)){
		$curl = new Curl\Curl();		
		$curl->setHeaders(array(
			'Content-Type' => "application/json",
			'futurehomekey' => "d90d99df-80a3-4a57-816f-2029e1acf5a6" // futurehomekey
		));
		$arr_project_cached = $arr_property_cached = $arr_buildings_cached = array();
		foreach($list_stocks as $key => $val){
			$ms_code = $val['ms_code'];
			$block_id = $val['block_id'];
			$project_id = $val['project_id'];
			$building_id = $val['building_id'];
			$bedroom_id = $val['bedroom_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			### Tòa
			if(isset($arr_property_cached[$building_id])){
				$oneBuilding = $arr_property_cached[$building_id];
			} else {
				$oneBuilding = $clsProperty->getOne($building_id);
				$arr_property_cached[$building_id] = $oneBuilding;
			}
			$building_name = $oneBuilding['title'];
			$building_information = $oneBuilding['more_information'];
			$building_information = $clsISO->to_array_json($building_information);
			### Dự án
			if(!isset($arr_project_cached[$project_id])){
				$oneProject = $clsProject->getOne($project_id, "title,utilities");
				$arr_project_cached[$project_id] = $oneProject;
			}
			$project_name = $arr_project_cached[$project_id]['title'];
			$project_utilities = $arr_project_cached[$project_id]['utilities'];
			### Phân khu
			if(!isset($arr_property_cached[$block_id])){
				$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
			}
			$block_name = $arr_property_cached[$block_id];
			### Tiêu đề
			$title = sprintf("Bán căn hộ %s tòa %s, %s, %s", $ms_code, $building_name, $block_name, $project_name);
			// Địa chỉ
			if($project_id == _PROJECT_DEF_ID) {
				$city_name = "Hà Nội";
				$district_name = "Gia Lâm";
				$ward_name = "Kiêu Kỵ";
				$street_name = "Lý Thánh Tông";
				$address = sprintf("Tòa %s, %s, %s, %s, %s", $building_name, $block_name, $project_name, $district_name, $city_name);
			}else if($val['project_id'] == _PROJECT_VHOP2_ID) {
				$city_name = "Hưng Yên";
				$district_name = "Văn Giang";
				$ward_name = "Nghĩa Trụ, Long Hưng, Tân Quang";
				$street_name = "ĐT379";
				$address = sprintf("Tòa %s, %s, %s, %s, %s", $building_name, $block_name, $project_name, $district_name, $city_name);
			}else if($val['project_id'] == _PROJECT_VHGG_ID) {
				$city_name = "Hà Nội";
				$district_name = "Đông Anh";
				$ward_name = "Đông Hội";
				$street_name = "Trường Xa";
				$address = sprintf("Tòa %s, %s, %s, %s, %s", $building_name, $block_name, $project_name, $district_name, $city_name);
			}
			$images_arr = array();
			$stock_posters = !empty($more_information['stock_poster']) 
				? $more_information['stock_poster'] : array();
			if(!empty($stock_posters)){
				// $stock_posters = @array_reverse($stock_posters);
				// $one_poster = @reset($stock_posters);
				foreach($stock_posters as $okey => $oval){
					$images_arr[] = $clsISO->getGoogleUrl($oval['image']);
				}
			}
			$extraInfo = array();
			if(isset($more_information['total_price_early']) && !empty($more_information['total_price_early'])){
				$extraInfo['priceEarly'] = (string) $more_information['total_price_early'];
			}
			if(isset($more_information['total_price_progress']) && !empty($more_information['total_price_progress'])){
				$extraInfo['priceSchedule'] = (string) $more_information['total_price_progress'];
			}
			if(!isset($arr_buildings_cached[$block_id])){
				$arr_buildings_cached[$block_id] = $clsProperty->countItem("`property_type`='_BUILDING' and `for_id`='{$block_id}'");
			}
			// Phiếu tính giá
			$price_sheets = $more_information['price_sheets'];
			$hide_price_sheets = isset($more_information['hide_price_sheets']) 
				? (int) $more_information['hide_price_sheets'] : 0;
			if(!empty($price_sheets) && $hide_price_sheets==0){
				$price_sheets = @array_reverse($price_sheets);
				$oneSheet = reset($price_sheets);
				foreach($oneSheet['sheets'] as $oval){
					$extraInfo['invoiceImage'] = $oval['image'];
				}
			}
			// Tiện ích
			$amenity = array();
			$project_utilities = $clsISO->to_array_json($project_utilities);
			if(!empty($project_utilities)){
				foreach($project_utilities as $okey => $oval){
					$amenity[$okey] = $oval['title'];
				}
//				$extraInfo['amenity'] = (object) json_encode($amenity, JSON_UNESCAPED_UNICODE);
				$extraInfo['amenity'] = $amenity;
			}
			// Layout
			if(isset($building_information['layout_ns']) && !empty($building_information['layout_ns'])){
				$extraInfo['layoutImage'] = $clsISO->getGoogleUrl($building_information['layout_ns']);
			}
			$total_buildings = $arr_buildings_cached[$block_id];
			// Số lượng tòa nhà trong phân khu
			$extraInfo['buildingCount'] = $total_buildings * 1;
			// Số lượng căn hộ
			$number_floor = $building_information['number_floor'];
			$number_house = $building_information['number_house'];
			$extraInfo['apartmentCount'] = ($number_floor-1) * $number_house;
			$extraInfo['floorCount'] = $building_information['number_floor'];
			$extraInfo['apartmentPerFloor'] = $building_information['number_house'];
			$extraInfo['handoverAt'] = 'Chưa bàn giao';
			if(isset($building_information['number_of_elevator']) && !empty($building_information['number_of_elevator'])){
				$extraInfo['elevatorCount'] = $building_information['number_of_elevator'];
			}
			if(isset($building_information['number_of_besement']) && !empty($building_information['number_of_besement'])){
				$extraInfo['basementCount'] = $building_information['number_of_besement'];
			}
			// $extraInfo['carParkingSlot'] = 'N/A';
			// $extraInfo['pricingInfo'] = '';
			// $extraInfo['serviceFee'] = '';
			// $extraInfo['handOverStandard'] = '';
			$bedroomCount = $arr_bedroom[$bedroom_id];
			$bathroomCount = "N/A";
			if(!empty($lstBedroom)){
				foreach($lstBedroom as $okey => $oval){
					if($okey == $bedroom_id){
						$_more_information = $oval['more_information'];
						$bathroomCount = $_more_information['bathroom'];
						break;
					}
				}
			}
			$balconyDirection = isset($arr_direction[$home_direction_id]) 
				? $arr_direction[$home_direction_id] : "N/A";
			$data = array(
				"phoneNumber"			=>	"0382222286",
				"agentName"				=>	"Future Homes",
				"status"				=>	"Listed",
				"listPrice"				=>	$val["total_price_vat"],
				"title"					=>	$title,
				"description"			=>	$title,
				"bedroomCount"			=>	$bedroomCount,
				"bathroomCount"			=>	$bathroomCount,
				"balconyDirection"		=>	$balconyDirection,
				"furnishedStatus"		=>	"Cơ bản",
				"handoverStatus"		=>	"Chưa bàn giao",
				"paperWorkStatus"		=>	"N/A",
				"images"				=>	implode(",", $images_arr),
				"projectName"			=>	$project_name,
				"unitType"				=>	"Căn hộ",
				// "mainEntranceDirection"	=>	"N/A",
				"totalArea" 			=> 	(float)(str_replace(",",".",$more_information["DT_Tim"])),
				"usableArea"			=>	(float)(str_replace(",",".",$more_information["DT_TT"])),
				"unitCode"				=>	$ms_code,
				"floor"					=>	$val["floor"],
				"publishedDate"			=>	date("Y-m-d\TH:i:s.v\Z"),
				"address"				=>	$address,
				"city"					=>	$city_name,
				"district"				=>	$district_name,
				"ward"					=>	$ward_name,
				"street"				=>	$street_name,
				"tower"					=>	$building_name,
				"projectStreetName"		=>	$street_name,
//				"extraInfo"				=>	(object) json_encode($extraInfo, JSON_UNESCAPED_UNICODE)
				"extraInfo"				=>	$extraInfo
			);
			/*echo json_encode($data, JSON_UNESCAPED_UNICODE);die;
			$clsISO->print_pre($data);die;*/
			// $curl->post('https://data.housenow.com.vn/api/listings',$data);
			$curl->post('https://data.housenow.com.vn/api/listings', $data);
			$response = toArray($curl->response); 
			if(!$curl->error){
				$success_logs[] = array(
					'ms_code' => $ms_code,
					'response' => $response
				);	
				++$success;
			}else{
				$error_logs[] = array(
					'ms_code' => $ms_code,
					'response' => $response
				); 
				++$error;
				echo json_encode($data, JSON_UNESCAPED_UNICODE);die;
			}
		}
	}
	// $clsISO->print_pre($list_stocks); die();
	if($clsAPILog->insert(array(
		"id" => $clsAPILog->getMaxId(),
		"type" => "stock",
		"to_api" => "HouseNow",
		"user_id" => $user_id,
		"more_information" => json_encode(array(
			"success"	=>	$success_logs,
			"error"	=>	$error_logs,
		), JSON_UNESCAPED_UNICODE),
		"reg_date" => time(),
	))) {
		$response = array(
			'result' => true,
			'success' => $success,
			'error' => $error,
			'msg' => sprintf('Đồng bộ thành công: %s, không thành công: %s', $success, $error)
		);
	}
	// Return
	echo json_encode($response,JSON_UNESCAPED_UNICODE); 
	die();
}
function default_sync_data_sop(){
	global $core,$smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsAPILog = new APILog();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsSop = new Sop();
	$clsStock = new Stock();
	$smarty->assign('clsAPILog', $clsAPILog);
	
	$user_id = $core->_USER['user_id'];
	$response = ["result"	=>	false, "msg" =>	"Lỗi đồng bộ"];
	$arr_bedroom = [
		"21"	=>	"Studio",
		"22"	=>	"1",
		"23"	=>	"1",
		"24"	=>	"2",
		"25"	=>	"2",
		"26"	=>	"2",
		"27"	=>	"3",
		"857"	=>	"4+",
	];
	$arr_interior = [
		"956"	=>	"Đầy đủ",
		"980"	=>	"Cơ bản",
		"969"	=>	"Cơ bản",
		"957"	=>	"Bàn giao thô",
	];
	$arr_juridical = [
		"713"	=>	"Đã có sổ",
		"714"	=>	"Hợp đồng mua bán"
	];
	###
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
	$lstDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	$arr_direction = [];
	foreach($lstDirection as $key => $val) {
		if($val['title'] == "ĐB") {
			$arr_direction[$val["property_id"]] = "Đông Bắc";
		}elseif ($val['title'] == "TB") {
			$arr_direction[$val["property_id"]] = "Tây Bắc";
		}elseif ($val['title'] == "ĐN") {
			$arr_direction[$val["property_id"]] = "Đông Nam";
		}elseif ($val['title'] == "TN") {
			$arr_direction[$val["property_id"]] = "Tây Nam";
		}else{
			$arr_direction[$val["property_id"]] = $val['title'];
		}
	}
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$arr_project_ins = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHGG_ID);
	$list_sop = $dbconn->getAll("SELECT `t1`.*,`t2`.`ms_code`,`t2`.`bedroom_id` as `bedroomID` 
		FROM `{$clsSop->tbl}` as `t1` LEFT JOIN `{$clsStock->tbl}` as `t2` ON `t1`.`stock_id` = `t2`.`stock_id` 
		WHERE `t1`.`is_trash`=0 and `t1`.`is_online`='1' and `t1`.`project_id` in (".implode(',',_PROJECT_OCEAN_CITY).")"
	);
	$error = $success = 0;
	$success_logs = $error_logs = array();	
	if(!empty($list_sop)){
		$curl = new Curl\Curl();		
		$curl->setHeaders(array(
			'Content-Type' => "application/json",
			'futurehomekey' => "futurehomekey"
		));
		$arr_project_cached = $arr_property_cached = array();
		foreach($list_sop as $key => $val){
			$ms_code = $val['ms_code'];
			$block_id = $val['block_id'];
			$building_id = $val['building_id'];
			$project_id = $val['project_id'];
			$interior_id = $val['interior_id'];
			$juridical_id = $val['juridical_id'];
			$home_direction_id = $val['home_direction_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(!isset($arr_property_cached[$block_id])) {
				$arr_property_cached[$block_id] = $clsProperty->getTitle($block_id);
			}
			if(!isset($arr_property_cached[$building_id])) {
				$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
			}
			$block_name = $arr_property_cached[$block_id];
			$building_name = $arr_property_cached[$building_id];
			###
			if(!isset($arr_project_cached[$project_id])) {
				$arr_project_cached[$project_id] = $clsProject->getTitle($project_id);
			}
			$project_name = $arr_project_cached[$project_id];
			###
			if($project_id == _PROJECT_DEF_ID) {
				$city = "Hà Nội";
				$district = "Gia Lâm";	
				$address = $building_name.", ".$block_name.", ".$project_name.", Đa Tốn, Gia Lâm, Hà Nội";	
				$ward = (!empty($lstBlock[$val['block_id']]["ward"])) ? $lstBlock[$val['block_id']]["ward"] : "Đa Tốn";
				$street = (!empty($lstBlock[$val['block_id']]["street"])) ? $lstBlock[$val['block_id']]["street"] : "Đa Tốn";
			}else if($val['project_id'] == _PROJECT_VHOP2_ID || $val['project_id'] == _PROJECT_VHOP3_ID) {
				$city = "Hưng Yên";
				$district = "Văn Giang";
				$address = $building_name.", ".$block_name.", ".$project_name.", Nghĩa Trụ, Văn Giang, Hưng Yên";
				$ward = (!empty($lstBlock[$val['block_id']]["ward"])) ? $lstBlock[$val['block_id']]["ward"] : "Nghĩa Trụ";
				$street = (!empty($lstBlock[$val['block_id']]["street"])) ? $lstBlock[$val['block_id']]["street"] : "Nghĩa Trụ";
			}else if($val['project_id'] == _PROJECT_VHGG_ID) {
				$city = "Hà Nội";
				$district = "Đông Anh";	
				$address = $building_name.", ".$block_name.", ".$project_name.", Đông Hội, Đông Anh, Hà Nội";
				$ward = (!empty($lstBlock[$val['block_id']]["ward"])) ? $lstBlock[$val['block_id']]["ward"] : "Đông Hội";
				$street = (!empty($lstBlock[$val['block_id']]["street"])) ? $lstBlock[$val['block_id']]["street"] : "Đông Hội";
			}
			$bedroom_id = !empty($val['bedroom_id']) ? $val['bedroom_id'] : $val['bedroomID'];
			$information_bedroom = (isset($lstBedroom[$bedroom_id])) ? $lstBedroom[$bedroom_id]["more_information"] : array();
			$images = !empty($more_information['images']) ? $more_information['images'] : array();
			$image_arrs = array();
			if(!empty($images)){
				foreach($images as $nkey => $image){
					$image_arrs[] = MYOCEAN_URL.$image;
				}
			}else{
				$image_arrs[] = MYOCEAN_URL."/application/themes/images/no-image.jpg";
			}
			$data = array(
				"phoneNumber"			=>	"0382222286",
				"agentName"				=>	"Future Homes",
				"status"				=>	"Listed",
				"listPrice"				=>	$val["price"],
				"title"					=>	$val["title"],
				"description"			=>	!empty($val['content']) ? $val['content'] : $more_information["description_page"],
				"bedroomCount"			=>	!empty($arr_bedroom[$bedroom_id]) ? $arr_bedroom[$bedroom_id] : "",
				"bathroomCount"			=>	!empty($information_bedroom['bathroom'])? $information_bedroom['bathroom'] : "N/A",
				"balconyDirection"		=>	isset($arr_direction[$home_direction_id]) ? $arr_direction[$home_direction_id] : "N/A",
				"furnishedStatus"		=>	isset($arr_interior[$interior_id]) ? $arr_interior[$interior_id] : "N/A",
				"handoverStatus"		=>	"Đã bàn giao",
				"paperWorkStatus"		=>	isset($arr_juridical[$juridical_id]) ? $arr_juridical[$juridical_id] : "N/A",
				"images"				=>	implode(",", $image_arrs),
				"projectName"			=>	$project_name,
				"unitType"				=>	"Căn hộ",
				// "mainEntranceDirection"	=>	"N/A",
				"usableArea"			=>	(float)(str_replace(",",".",$more_information["DT_TT"])),
				"unitCode"				=>	$ms_code,
				"floor"					=>	$val["floor"],
				"publishedDate"			=>	date("Y-m-d\TH:i:s.v\Z"),
				"address"				=>	$address,
				"city"					=>	$city,
				"district"				=>	$district,
				"ward"					=>	$ward,
				"street"				=>	$street,
				"tower"					=>	$building_name,
				"projectStreetName"		=>	"",
				"extraInfo"				=>	"",
			);
			// Return
			// $clsISO->print_pre($data);die;
			// $curl->post('https://data.housenow.com.vn/api/listings',$data);
			$curl->post('https://data-staging.housenow.com.vn/api/listings',$data);
			if(!$curl->error){					
				$success_logs[] = $data;	
				++$success;
			}else{
				$error_logs[] = $data; 
				++$error;
			}
			unset($title,$building_name,$block_name,$project_name,$more_information);
		}
		if($clsAPILog->insert(array(
			"id" => $clsAPILog->getMaxId(),
			"type" => "sop",
			"user_id" => $user_id,
			"to_api" => "HouseNow",
			"more_information" => json_encode(array(
				"success"	=>	$success_logs,
				"error"	=>	$error_logs,
			)),
			"reg_date" => time()
		))) {
			$response = [
				"result"	=>	true,
				"success"	=>	$success,
				"error"	=>	$error,
				"action"	=>	"sop",
				"msg"		=>	"Đồng bộ thành công: ".$success.". Không thành công: ".$error
			];
		}
	}
	// Return
	echo json_encode($response,JSON_UNESCAPED_UNICODE);
	die();
}
?>