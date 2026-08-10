<?php 
function api_log_sync_data_stock(){
	global $core,$smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsAPILog = new APILog();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$smarty->assign('clsAPILog', $clsAPILog);
	
	$user_id = $core->_USER['user_id'];
	
	$response = ["result"	=>	false, "msg"		=>	"Lỗi đồng bộ"];
	if($user_id > 0) {
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

		$arr_projects = array(1,2,3);
		$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
		$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
		$lstDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
		$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
		$arr_project_ins = array(_PROJECT_DEF_ID, _PROJECT_VHOP2_ID, _PROJECT_VHGG_ID);
		$list_stocks = $clsStock->getAll("`agency_id`='"._AGENCY_FH_ID."' and `status_id`='"._STOCK_STATUS_DQ_ID."' and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and `project_id` in (".implode(',',$arr_project_ins).")");
		
		$curl = new Curl\Curl();		
		$curl->setHeaders(array(
			'Content-Type' => "application/json",
			'futurehomekey' => "futurehomekey"
		));
		$error = 0;
		$success = 0;
		$data_log = [];		
		if(!empty($list_stocks)){
			$arr_cache_project = $arr_cache = array();
			foreach($list_stocks as $key => $val){
				$more_information = $clsISO->to_array_json($val['more_information']);
				
				if(!isset($arr_cache[$val['block_id']])) {
					$arr_cache[$val['block_id']] = $clsProperty->getTitle($val['block_id']);
				}
				if(!isset($arr_cache[$val['building_id']])) {
					$arr_cache[$val['building_id']] = $clsProperty->getTitle($val['building_id']);
				}
				
				$building_name = $arr_cache[$val['building_id']];
				$block_name = $arr_cache[$val['block_id']];
				if(!isset($arr_cache_project[$val['project_id']])) {
					$arr_cache_project[$val['project_id']] = $clsProject->getTitle($val['project_id']);
				}
				$project_name = $arr_cache_project[$val['project_id']];
				$title = "Bán căn hộ ".$val['ms_code']." tòa ".$building_name.", ".$block_name.", ".$project_name;
				if($val['project_id'] == _PROJECT_DEF_ID) {
					$address = " Tòa ".$building_name.", ".$block_name.", ".$project_name.", Gia Lâm, Hà Nội";
					$city = "Hà Nội";
					$district = "Gia Lâm";
				}else if($val['project_id'] == _PROJECT_VHOP2_ID) {
					$address = " Tòa ".$building_name.", ".$block_name.", ".$project_name.", Văn Giang, Hưng Yên";
					$city = "Hưng Yên";
					$district = "Văn Giang";
				}else if($val['project_id'] == _PROJECT_VHGG_ID) {
					$address = " Tòa ".$building_name.", ".$block_name.", ".$project_name.", Đông Anh, Hà Nội";
					$city = "Hà Nội";
					$district = "Đông Anh";
				}
				$information_bedroom = (isset($lstBedroom[$val["bedroom_id"]])) ? $lstBedroom[$val["bedroom_id"]]["more_information"] : array();
				$stock_posters = !empty($more_information['stock_poster']) ? $more_information['stock_poster'] : array();
				$arr_image = array();
				if(!empty($stock_posters)){
					foreach($stock_posters as $nkey => $nval){
						$arr_image[] = $clsISO->getGoogleUrl($nval['image']);
					}
				}
				$data = array(
					"phoneNumber"			=>	"0382222286",
					"agentName"				=>	"Future Homes",
					"status"				=>	"Listed",
					"listPrice"				=>	$val["total_price_vat"],
					"title"					=>	$title,
					"description"			=>	"",
					"bedroomCount"			=>	!empty($arr_bedroom[$val["bedroom_id"]]) ? $arr_bedroom[$val["bedroom_id"]] : "",
					"bathroomCount"			=>	!empty($information_bedroom['bathroom'])? $information_bedroom['bathroom'] : "N/A",
					"balconyDirection"		=>	isset($lstDirection[$val['home_direction_id']]) ? $lstDirection[$val['home_direction_id']]["title_vn"] : "N/A",
					"furnishedStatus"		=>	"N/A",
					"handoverStatus"		=>	!empty($more_information["TCBG"]) ? $more_information["TCBG"] : "N/A",
					"paperWorkStatus"		=>	"N/A",
					"images"				=>	implode(",",$arr_image),
					"projectName"			=>	$project_name,
					"unitType"				=>	"Căn hộ",
					"mainEntranceDirection"	=>	"N/A",
					"usableArea"			=>	(float)(str_replace(",",".",$more_information["DT_TT"])),
					"unitCode"				=>	$val["ms_code"],
					"floor"					=>	$val["floor"],
					"publishedDate"			=>	date("Y-m-d\TH:i:s.v\Z"),
					"address"				=>	$address,
					"city"					=>	$city,
					"district"				=>	$district,
					"ward"					=>	"",
					"street"				=>	"",
					"tower"					=>	$building_name,
					"projectStreetName"		=>	"",
					"extraInfo"				=>	"",
				);
				
				
				// Return
		//		$curl->post('https://data.housenow.com.vn/api/listings',$data);
				$curl->post('https://data-staging.housenow.com.vn/api/listings',$data);
				if(!$curl->error){
					$data_log_success[] = $data;	
					++$success;
				}else{
					$data_log_error[] = $data; 
					++$error;
				}
				unset($title,$building_name,$block_name,$project_name,$more_information);
			}
			
			
			if($clsAPILog->insert(array(
				"id" => $clsAPILog->getMaxID(),
				"user_id" => $user_id,
				"more_information" => json_encode(array(
					"success"	=>	$data_log_success,
					"error"	=>	$data_log_error,
				)),
				"to_api" => "HouseNow",
				"reg_date" => time(),
			))) {
				$response = [
					"result"	=>	true,
					"success"	=>	$success,
					"error"	=>	$error,
					"msg"		=>	"Đồng bộ thành công: ".$success.". Không thành công: ".$error
				];
			}

		}
	}
	
	echo json_encode($response,JSON_UNESCAPED_UNICODE);die;
}
?>