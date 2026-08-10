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
function default_ajaxMakeSelectboxCity(){
	global $core,$mod,$act;
	$clsCountry = new Country();
	$clsCity = new City();
	$clsTour = new Tour();
	$clsLang = new _Lang();
	#
	$country_id = $_POST['country_id'];
	$departure_id = $_POST['departure_id'];
	$city_id = isset($_POST['city_id'])?$_POST['city_id']:'';
	#
	$html = '<option value="">-- '.$core->get_Lang('Select').' --</option>';
	$lstCity = $clsCity->getAll("is_trash=0 and country_id='".$country_id."' order by order_no asc",$clsCity->pkey);
	if(!empty($lstCity)){
		foreach($lstCity as $item){
			if($clsTour->countTourGolobal($country_id, $departure_id, $item[$clsCity->pkey])>0){
				$selected = ($city_id==$item[$clsCity->pkey])?'selected="selected"':'';
				$html.='<option value="'.$item[$clsCity->pkey].'" '.$selected.'>'.$clsCity->getTitle($item[$clsCity->pkey]).'</option>';
			}
		}
	}
	echo $html; die();
}
function default_initSession(){
	global $core, $dbconn,$clsISO,$clsConfiguration,$_LANG_ID;
	
	$msg = "_error";
	$width = Input::post('width', 1360);
	$height = Input::post('height', 768);
	if($width > 0 && $height > 0){
		$msg = "_success";
		vnSessionSetVar('_ss_layout', array(
			'width' => $width,
			'height' => $height
		));
	}
	// Return
	echo $msg; die();
}
function default_update_setting_field(){
	global $core, $dbconn,$clsISO,$_LANG_ID;
	$clsConfiguration = new Configuration();
	###
	$msg = "_error";
	$to_field = Input::post("to_field");
	$def_value = Input::post("def_value");
	$to_value = Input::post("to_value", $def_value);
	if($clsConfiguration->updateValue($to_field, $to_value)){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_open_info_agency(){
	global $core, $dbconn,$clsISO,$_LANG_ID;
	$clsConfiguration = new Configuration();
	$clsStockLog = new StockLog();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$agency_id = (int) Input::post('agency_id', 0);
	$stock_code = Input::post('stock_code', "");
	$clsStockLog->insertLog('user.FH', $stock_id, $stock_code, $agency_id);
	// Return
	$html = $core->build('_ajax.info_agency.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_fcm_token(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsProfile = new Profile();
	$clsFcmToken = new FcmToken();
	###
	$msg = "_error";
	$push_type = Input::post('push_type', "_fcm");
	$fcm_token = Input::post('fcm_token');
	if(!empty($fcm_token)){
		$tmp = $clsFcmToken->countItem("`push_type`='{$push_type}' 
			and `deviceType`='{$deviceType}' and token='{$fcm_token}'");
		// $clsISO->print_pre($tmp); die();
		if(!empty($tmp)){
			$msg = "_duplicated";
		} else {
			if($clsFcmToken->insert(array(
				$clsFcmToken->pkey => $clsFcmToken->getMaxId(),
				'token' => $fcm_token,
				'push_type' => $push_type,
				'deviceType' => $deviceType,
				'user_id' => $profile_id,
				'reg_date' => time()
			))){
				$msg = "_success";
			}
		}
	}
	// Return
	echo $msg; die();
}
function default_open_loyalty(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$uid = $clsISO->getUniqid();
	$holderG = Input::post('holderG', '_move');
	$fpoint_id = (int) Input::post('fpoint_id', 0);
	$smarty->assign('holderG', $holderG);
	$smarty->assign('fpoint_id', $fpoint_id);
	// Return
	$html = $core->build('_ajax.open_loyalty.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_get_loyalty_user(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	###
	$from_user = (int) Input::post('from_user', 0);
	$total_Lpoint = $clsProfile->getOneField('total_Lpoint', $from_user);
	// Return
	echo json_encode(array(
		'total_Lpoint' => $total_Lpoint
	)); die();
}
function default_save_loyalty(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	###
	$msg = "_error"; $is_valid = true;
	$holderG = Input::post('holderG', "_move");
	$from_user = (int) Input::post('from_user', 0);
	$score = (int) Input::post('score', 0);
	$content = Input::post('content');
	if($holderG == "_move"){
		$to_user = (int) Input::post('to_user', 0);
		if($from_user == 0 || $to_user == 0){
			$is_valid = false;
			$msg = "_empty_user";
		} else if($from_user == $to_user){
			$is_valid = false;
			$msg = "_invalid_user";
		} else if($score == 0){
			$is_valid = false;
			$msg = "_empty_score";
		}
	} else {
		if($from_user == 0){
			$is_valid = false;
			$msg = "_empty_user";
		} else if($score == 0){
			$is_valid = false;
			$msg = "_empty_score";
		}
	}
	if($is_valid == true){
		// Giảm điểm người cho
		$total_Lpoint = $clsProfile->getOneField('total_Lpoint', $from_user);
		$total_Lpoint -= $score;
		if($clsProfile->updateOne($from_user, array(
			'total_Lpoint' => $total_Lpoint
		))){
			$msg = "_success";
			$related_id = 0; // Id liên kết với fpoint (-) điểm
			if($holderG == '_move'){
				// Tăng điểm người nhận
				$total_Lpoint = $clsProfile->getOneField('total_Lpoint', $to_user);
				$total_Lpoint+= $score;
				$clsProfile->updateOne($to_user, array(
					'total_Lpoint' => $total_Lpoint
				));
				// Thêm Log +
				$fpoint_id = $related_id = $clsFPoint->getMaxId();
				$clsFPoint->insert(array(
					$clsFPoint->pkey => $fpoint_id,
					'profile_id' => $to_user,
					'ns_type' => 'Lpoint',
					'act' => '_plus',
					'score' => $score,
					'for_id' => 0,
					'content' => $content,
					'reg_date' => time()
				));
			}
			$clsFPoint->insert(array(
				$clsFPoint->pkey => $clsFPoint->getMaxId(),
				'profile_id' => $from_user,
				'ns_type' => 'Lpoint',
				'act' => '_minus',
				'score' => $score,
				'for_id' => $related_id,
				'content' => $content,
				'reg_date' => time()
			));
		}
	}
	// Return
	echo $msg; die();
}
function default_load_group_staff(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsGroupProfile = new GroupProfile();
	###
	$_results = array();
	$field = "{$clsGroupProfile->pkey},`title`";
	$tmp = $clsGroupProfile->getAll("`is_trash`=0 and `is_online`=1 order by `reg_date` DESC", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$_results[] = array(
				'id' => $val[$clsGroupProfile->pkey],
				'text' => sprintf('%s', $val['title'])
			);
		}
		unset($tmp);
	}
	// Return
	echo json_encode($_results); die();
}
function default_load_department(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	###
	$_results = array();
	$field = "{$clsProperty->pkey},`property_code`,`title`";
	$tmp = $clsProperty->getAll("`is_trash`=0 and `property_type`='_DEPARTMENT' 
		and `property_id`<>'"._DEPARTMENT_SALE_ID."' order by `order_no` ASC", $field);
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$_results[] = array(
				'id' => $val[$clsProperty->pkey],
				'text' => sprintf('%s' , $val['title'])
			);
		}
		unset($tmp);
	}
	// Return
	echo json_encode($_results); die();
}
function default_nearByType(){
    global $core, $dbconn, $clsISO, $profile_id;
    $clsCache = new Cache();
	$clsProject = new Project();
	 
    $type = Input::post('type', "");
    $project_id = (int) Input::post('project_id', 0);
    $data = []; $radius = 20000;
    if ($project_id > 0) {
        $oneProject = $clsProject->getOne($project_id, "more_information");
        $more_information = $oneProject['more_information'];
	    $more_information = $clsISO->to_array_json($more_information);
		$map_la = $core->get_field($more_information, "map_la", null);
		$map_lo = $core->get_field($more_information, "map_lo", null);
		$location_highlight = $core->get_field($more_information, "location_highlight", []);
        if (!empty($oneProject)) {
			if ($type == 'highlight') {
				foreach ($location_highlight as $key => $value) {
					$lat = $core->get_field($value, "lat", null);
					$lng = $core->get_field($value, "lng", null);
					if(!empty($lat) && !empty($lng)){
						$distance = calculateDistance($map_la, $map_lo, $lat, $lng);
						$allResults['highlight'][] = [
							'type'     => 'highlight',
							'name'     => $value['name'] ?? '',
							'address'  => $value['address'] ?? '',
							'latlng'   => [$lat, $lng],
							'distance' => round($distance, 2)
						];
					}
				}
			} else {
				$cached_name = sprintf('_nearbysearch_%s_%s_CA_cached', $type, $project_id);
				if($clsCache->has($cached_name)){
					$data_cached = $clsCache->get($cached_name);
					$data = !empty($data_cached) && $data_cached != 'null' ? $data_cached : [];
					$allResults[$type] = $data;
				} else {
					$allResults = array();
					$configTypeMap = configTypeMapLeaflet($type, $radius, $map_la, $map_lo);
					$getData = getNearbyPlaces($configTypeMap);
					if (!empty($getData['elements'])) {
						foreach ($getData['elements'] as $place) {
							$placeType = $place['type'];
							$placeTags = $place['tags'];
							$placeName = !empty($placeTags['name']) ? $placeTags['name'] : 'Siêu thị';
							if($placeType == 'node') {
								$placeLat = $place['lat'];
								$placeLng = $place['lon'];
							} else if($placeType == 'way') {
								$placeLat = $place['center']['lat'];
								$placeLng = $place['center']['lon'];
							}
							$address = '';
							if (isset($placeTags['add:housenumber'])) {
								$address .= 'Số ' . $placeTags['add:housenumber'];
							} 
							if (isset($placeTags['addr:subdistrict'])) {
								 if (!empty($address)) {
									$address .= ', ';
								}
								$address .= $placeTags['addr:subdistrict'];
							} 
							if (isset($placeTags['addr:district'])) {
								 if (!empty($address)) {
									$address .= ', ';
								}
								$address .= $placeTags['addr:district'];
							} 
							if (isset($placeTags['addr:city'])) {
								 if (!empty($address)) {
									$address .= ', ';
								}
								$address .= $placeTags['addr:city'];
							} 
							if (isset($placeTags['addr:province'])) {
								 if (!empty($address)) {
									$address .= ', ';
								}
								$address .= $placeTags['addr:province'];
							} 
							$distance = calculateDistance($map_la, $map_lo, $placeLat, $placeLng);
							$allResults[$type][] = [
								'type'     => $type,
								'name'     => $placeName?? '',
								'address'  => $address,
								'latlng'   => [$placeLat, $placeLng],
								'distance' => round($distance, 2)
							];
						}
					}
					$clsCache->set($cached_name, json_encode($allResults[$type], JSON_UNESCAPED_UNICODE), 24*60*60);
				}
            }
            $data = [
                'status' => 200,
                'msg' => 'Lấy dữ liệu thành công',
                'data' => $allResults[$type],
            ];
        } else {
            $data = [
                'status' => 404,
                'msg' => 'Không tìm thấy thông tin dự án',
            ];
        }
    } else {
        $data = [
            'status' => 404,
            'msg' => 'Không tìm thấy thông tin dự án',
        ];
    }
	// Return
    echo json_encode($data); die();
}
function configTypeMapLeaflet($types, $radius, $lat, $lng){
    $config = '';
    if (is_array($types)) {
        foreach ($types as $keyType => $type) {
            $config.= templateTypeMapLeaflet($type, $radius, $lat, $lng);
        }
    } else {
        $config = templateTypeMapLeaflet($types, $radius, $lat, $lng);
    }
    return $config;
}
function templateTypeMapLeaflet($type, $radius, $lat, $lng) {
    $queryMapLeaflet = [
        'airport' => "
            node[\"aeroway\"=\"aerodrome\"](around:{$radius},{$lat},{$lng});
            way[\"aeroway\"=\"aerodrome\"](around:{$radius},{$lat},{$lng});
            relation[\"aeroway\"=\"aerodrome\"](around:{$radius},{$lat},{$lng});
        ", 'hospital' => "
            node[\"amenity\"=\"hospital\"](around:{$radius},{$lat},{$lng});
            way[\"amenity\"=\"hospital\"](around:{$radius},{$lat},{$lng});
            relation[\"amenity\"=\"hospital\"](around:{$radius},{$lat},{$lng});
        ",  'university' => "
            node[\"amenity\"=\"university\"](around:{$radius},{$lat},{$lng});
            way[\"amenity\"=\"university\"](around:{$radius},{$lat},{$lng});
            relation[\"amenity\"=\"university\"](around:{$radius},{$lat},{$lng});
        ", 'market'=> "
            node[\"shop\"=\"supermarket\"](around:{$radius},{$lat},{$lng});
            way[\"shop\"=\"supermarket\"](around:{$radius},{$lat},{$lng});
            relation[\"shop\"=\"supermarket\"](around:{$radius},{$lat},{$lng});
        ", 'entertainment'=> "
            node[\"leisure\"=\"park\"](around:{$radius},{$lat},{$lng});
            way[\"leisure\"=\"park\"](around:{$radius},{$lat},{$lng});
            relation[\"leisure\"=\"park\"](around:{$radius},{$lat},{$lng});
            node[\"leisure\"=\"playground\"](around:{$radius},{$lat},{$lng});
            way[\"leisure\"=\"playground\"](around:{$radius},{$lat},{$lng});
            relation[\"leisure\"=\"playground\"](around:{$radius},{$lat},{$lng});
            node[\"amenity\"=\"cinema\"](around:{$radius},{$lat},{$lng});
            way[\"amenity\"=\"cinema\"](around:{$radius},{$lat},{$lng});
            relation[\"amenity\"=\"cinema\"](around:{$radius},{$lat},{$lng});
        "
    ];
    $data = null;
    if (isset($queryMapLeaflet[$type])) {
        $data = $queryMapLeaflet[$type];
    }
    return $data;
}
function getNearbyPlaces($query) {
    global $profile_id;
    $overpassUrl = "https://overpass-api.de/api/interpreter";
    $fullQuery = '[out:json];(' . $query . ');out center;';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $overpassUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "data=" . urlencode($fullQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response !== false) {
        $response = json_decode($response, true);
    } else {
        $response = [];
    }
    return $response;
}
function calculateDistance($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}
function default_search_location(){
    global $clsISO,$Core;
    $location = Input::post('location_keyword', '');
    $lat = Input::post('lat', '');
    $lng = Input::post('lng', '');
    $radius = Input::post('radius', '');

    $deltaLat = $radius / 111; // 1 vĩ độ ≈ 111 km
    $deltaLon = $radius / (111 * cos(deg2rad($lat)));

    $minLat = $lat - $deltaLat;
    $maxLat = $lat + $deltaLat;
    $minLng = $lng - $deltaLon;
    $maxLng = $lng + $deltaLon;
    $limit = 20;
    if (!empty($location)) {
        $url = "https://nominatim.openstreetmap.org/search?"
            . http_build_query([
                'q' => $location,
                'format' => 'json',
                'limit' => $limit,
                'bounded' => 1,
                'viewbox' => "$minLng,$minLat,$maxLng,$maxLat"
            ]);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'MyApp/1.0 (trandanghoang.work@gmail.com)');
        $response = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($response, true);
        $locations = [];
        if (!empty($results)) {
            foreach ($results as $value) {
                $distance = calculateDistance($lat, $lng, $value['lat'], $value['lon']);
                $displayNameArr = explode(",", $value['display_name']);
                array_shift($displayNameArr);
                $displayNameArr = implode(",", $displayNameArr);
                $locations[] = [
                    'type'     => $value['type'] ?? '',
                    'name'     => $value['name']?? '',
                    'address'  => $displayNameArr,
                    'latlng'   => [$value['lat'], $value['lon']],
                    'distance' => round($distance, 2),
                ];
            }
            $data = [
                'status' => 200,
                'msg' => 'Lấy dữ liệu thành công',
                'data' => $locations,
            ];
        } else {
            $data = [
                'status' => 404,
                'msg' => 'Không tìm thấy địa điểm phù hợp',
            ];
        }
    } else {
        $data = [
            'status' => 400,
            'msg' => 'Xin hãy nhập địa điểm tìm kiếm',
            'data' => '',
        ];
    }
	// Return
    echo json_encode($data);
}
function default_load_block(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	$clsProperty = new Property();
	$block_id = Input::post('block_id', 0);
	$project_id = Input::post('project_id', 0);
	
	$msg = "_error"; $arr_blocks = array();
	$field = "{$clsProperty->pkey},`title`";
	$tmp = $clsProperty->getAll("`is_trash`=0 and `property_type`='_BLOCK' 
		and `for_id`='{$project_id}' order by `order_no` ASC", $field);
	if(!empty($tmp)){
		$msg = "_success";
		foreach($tmp as $key => $val){
			$arr_blocks[] = array(
				'id' => $val[$clsProperty->pkey],
				'text' => $val['title'],
				'label' => $val['label']
			);
		}
		unset($tmp);
	}
	// Return
	echo json_encode($arr_blocks); die();
}
function default_open_config_time(){
	global $smarty,$_CONFIG,$dbconn,$mod,$_LANG_ID,$act,$core,$clsModule,$clsConfiguration,$clsISO;
	global $profile_id, $oneProfile;
	#
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', '_view');
	$tmp = $clsISO->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
	$curr_quater = $tmp['quarter'];
	// $more_information = $oneProfile['more_information'];
	// $time_config = $core->get_field($more_information, "time_config", array(
	//	'year' => date('Y'),
	//	'month' => date('n'),
	//	'quarter' => $curr_quater,
	//	'is_time_now' => 0
	// ));
	$time_config = $clsConfiguration->getValue('time_config');
	$time_config = $clsISO->to_array_json($time_config);
	#
	$start_year = 2023;
	$end_year = date('Y');
	$list_quarter = $list_months = $list_years = array();
	$year = $core->get_field($time_config, "year", date('Y'));
	$month = $core->get_field($time_config, "month", date('n'));
	$quarter = $core->get_field($time_config, "quarter", $curr_quater);
	#
	$max_loop = 4; 
	if($year == (int) date('Y')){
		if(date('n') >=1 && date('n') <=3) $max_loop = 1;
		if(date('n') >=4 && date('n') <=6) $max_loop = 2;
		if(date('n') >=7 && date('n') <=9) $max_loop = 3;
		if(date('n') >=10 && date('n') <=12) $max_loop = 4;
	}
	for($i=1; $i<= $max_loop; $i++){
		$list_quarter[] = $i;
	}
	$tmp = $clsISO->get_dates_of_quarter((int)$quarter, (int)$year, 'd-m-Y');
	$start_month = date("n",strtotime($tmp["start"]));
	$end_month = date("n",strtotime($tmp["end"]));
	for($i=$start_month; $i<= $end_month; $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = strtotime(sprintf('01-01-%s', $end_year));
	$end_day = cal_days_in_month(CAL_GREGORIAN, 12, $end_year);
	$to_date = strtotime(sprintf('%s-12-%s', $end_day, $end_year));
	
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	$smarty->assign('time_config', $time_config);
	$smarty->assign('start_date', $start_date);
	$smarty->assign('to_date', $to_date);
	$smarty->assign('list_quarter', $list_quarter);
	$smarty->assign('list_months', $list_months);
	$smarty->assign('list_years', $list_years);
	$smarty->assign('year', $year);
	$smarty->assign('quarter', $quarter);
	$smarty->assign('month', $month);
	// Return
	$html = $core->build('_ajax.config_time.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_save_config_time(){
	global $_CONFIG,$_SITE_ROOT,$mod,$act,$core,$clsModule,$clsISO,$clsConfiguration,$deviceType,$dbconn;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	#
	$msg = "_error";
	$year = Input::post("year", date("Y")); 
	$month = Input::post("month", date("n")); 
	$tmp = $clsISO->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
	$quarter = Input::post("quarter", $tmp["quarter"]);
	$is_time_now = Input::post("is_time_now",0); 
	$time_config = array(
		"year"		=>	$year,
		"quarter"	=>	$quarter,
		"month"		=>	$month,
		"is_time_now" =>	$is_time_now,
	);
	// $clsISO->print_pre($more_information); die();
	if($clsConfiguration->updateValue("time_config", json_encode($time_config, JSON_UNESCAPED_UNICODE))){
		$msg = "_success";
	}
	// Return
	Response::echoResponse(200, array(
		'result' => true,
		'msg' => $msg
	)); die();
}