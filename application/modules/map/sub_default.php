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
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile,$list_projects,$profile_id;
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$project_id = (int)Input::get("project_id",0);
	if($project_id > 0) {
		$array_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
		$arr_location = $location_block = [];
		foreach($list_projects as $key => $val) {
			if($val["project_id"] == $project_id) {
				$more_information = $val["more_information"];
				$location_block = !empty($more_information["location_block"]) ? $more_information["location_block"] : [];
				break;
			}
		}
//		$clsISO->print_pre($location_block);die;
		if(!empty($location_block)) {
			foreach ($location_block as $key => $val) {
				$info_location = [
					"lat"	=>	(float)$val["lat"],
					"lng"	=>	(float)$val["lng"],
					"url"	=>	$clsProject->getLink($project_id,$val["block_id"]),
					"label"	=>	$array_block_cached[$val["block_id"]]["title"],
					"id" 	=> 	$val["block_id"],
					"image" => 	$array_block_cached[$val["block_id"]]["image"],
					"price_per_m2"	=>	"",
					"territory" => []
				];
				$arr_location[] = $info_location;
				$location_block[$key]['link'] = $clsProject->getLinkDetail($project_id,$val["block_id"],0,'overview',$array_block_cached[$val["block_id"]]);
				$location_block[$key]['infomation_ex'] = $array_block_cached[$val["block_id"]]["more_information"];
				$location_block[$key]['more_information'] = $array_block_cached[$val["block_id"]]["more_information"];
				$location_block[$key]['logo'] = $array_block_cached[$val["block_id"]]["image"];
			}
		}
		$assign_list["location"] = json_encode($arr_location,JSON_UNESCAPED_UNICODE);
		$assign_list["info_locations"] = $location_block;
	}else{
		$lstProject = $clsProject->getAll("`is_menu`='1' order by `project_id` ASC");
		$arr_location = [];
		foreach($lstProject as $key => $item){
			$image = $item['image'];
			$more_information = $clsISO->to_array_json($item['more_information']);
			$arcreage = $core->get_field($more_information, 'arcreage', "");
			$apartment = $core->get_field($more_information, 'apartment', "");
			$logo = $core->get_field($more_information, 'logo', "");

			if(!empty($more_information["map_la"]) && !empty($more_information["map_lo"])) {
				$item['image'] = !empty($image) ? sprintf('%s%s', FH_URL, $image) : "";

				$info_location = [
					"lat"	=>	(float)$more_information["map_la"],
					"lng"	=>	(float)$more_information["map_lo"],
					"url"	=>	$clsProject->getLink($item["project_id"]),
					"label"	=>	$item["title"],
					"id" 	=> 	$item['project_id'],
					// "image" => 	FH_URL.$item['image'],
					"image" => 	$item['image'],
					"price_per_m2"	=>	$more_information["price_per_m2"],
					"territory" => !empty($more_information['map_territory']) ? $more_information['map_territory'] : []
				];
				$arr_location[] = $info_location;
				$item['link'] = $clsProject->getLinkDetail($item['project_id'],0,0,'overview',$item);
				$item['infomation_ex'] = $more_information;
				$item['more_information'] = $more_information;
				$item['arcreage'] = $arcreage;
				$item['apartment'] = $apartment;
				$item['logo'] = FH_URL.$logo;

				$lstProject[$key] = $item;
			}
		}

		$assign_list["location"] = json_encode($arr_location,JSON_UNESCAPED_UNICODE);
		$assign_list["info_locations"] = $lstProject;
	}
	
	$assign_list["profile_id"] = $profile_id;
	$assign_list["map_zoom"] = _MAP_ZOOM_LIST_PROJECT;
	/*=============Title & Description Page==================*/
	$title_page = 'Bản đồ dự án - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$assign_list["description_page"] = 'Bản đồ các dự án ở Đà Nẵng';
    if (!empty($title_page)) {
		$keywords = explode('-', $title_page);
		$assign_list["keyword_page"] = implode(',', $keywords);
	}
}
function default_project(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$oneProfile;
	$clsProject = new Project();
	$clsCache = new Cache();
	$project_id = (int) Input::get('project_id', 0);
	$project = $clsProject->getOne($project_id);
	$arr_location =	$arr_info_locations = [];

	$more_information = $project['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	// Danh sách các vị trí nổi bật quanh trung tâm Đà Nẵng
	
	$featured_locations = isset($more_information['location_highlight']) ? $more_information['location_highlight'] : [];
	$locations = $featured_locations;
	$assign_list["locations"] =json_encode($locations,JSON_UNESCAPED_UNICODE);
	if(!empty($more_information["map_la"]) && !empty($more_information["map_lo"])) {
		$project['image'] = !empty($project['image']) ? sprintf('%s%s', FH_URL, $project['image']) : "";
		$location = [
			"lat"	=>	(float)$more_information["map_la"],
			"lng"	=>	(float)$more_information["map_lo"],
			"url"	=>	$clsProject->getLink($project["project_id"]),
			"label"	=>	$project["title"],
			"id" 	=> 	$project['project_id'],
			"image" => 	$project['image'],
		];
		$project['link'] = $clsProject->getLinkDetail($project['project_id'],0,0,'overview',$project);
		$project['infomation_ex'] = json_decode($project['more_information'], true);
	}
	$allResults = [];
	$types = [
		'hospital',
		'airport',
		'university',
		'entertainment', // gộp: park, playground, movie_theater
		'market' // bao gồm chợ và cửa hàng tiện lợi
	];
	$apiKey = _API_KEY_GOOGLE_MAP;
	$lat = $location['lat'];
	$lng = $location['lng'];
	$radius = 5000;
	$allResults['highlight'] = [];
	if (!empty($featured_locations)) {
		foreach ($featured_locations as $key => $value) {
			$distance = !empty($value['lat']) && !empty($value['lng']) ? calculateDistance($lat, $lng, $value['lat'], $value['lng']) : 0;
			$address = getAddressFromLatLng($value['lat'], $value['lng'], $apiKey);
			$allResults['highlight'][] = [
				'type'     => 'highlight',
				'name'     => $value['name'] ?? '',
				'address'  => $address,
				'latlng'   => [$value['lat'], $value['lng']],
				'distance' => round($distance, 2)
			];
			usleep(300000);
		}
	}
	foreach ($types as $type) {
		$_cache_data_type = sprintf('_nearbysearch_%s_%s_CA_cached', $type, $project_id);
		$data_cached = $clsCache->get($_cache_data_type);
		if(!empty($data_cached)){
			$data = $clsISO->to_array_json($data_cached);
			$allResults[$type] = $data;
		} else {
			$places = fetchNearbyPlaces($lat, $lng, $type, $radius, $apiKey);
	
			foreach ($places as $place) {
				$placeLat = $place['geometry']['location']['lat'];
				$placeLng = $place['geometry']['location']['lng'];
				$distance = calculateDistance($lat, $lng, $placeLat, $placeLng);
	
				$allResults[$type][] = [
					'type'     => $type,
					'name'     => $place['name'] ?? '',
					'address'  => $place['vicinity'] ?? '',
					'latlng'   => [$placeLat, $placeLng],
					'distance' => round($distance, 2)
				];
			}
			try {
				$clsCache->set($_cache_data_type, json_encode($allResults[$type], JSON_UNESCAPED_UNICODE), 5*24*60*60);
			} catch (\Throwable $th) {
			}
		}
	}
	$assign_list["infoLocationAround"] = json_encode($allResults,JSON_UNESCAPED_UNICODE);
	$assign_list["location"] = json_encode($location,JSON_UNESCAPED_UNICODE);
	$assign_list["project"] = $project;
	/*=============Title & Description Page==================*/
	$title_page = 'Bản đồ dự án - '. $project['title'];
	$assign_list["title_page"] = $title_page;
    if (!empty($title_page)) {
		$keywords = explode('-', $title_page);
		$assign_list["keyword_page"] = implode(',', $keywords);
	}
}

function getAddressFromLatLng($lat, $lng, $apiKey) {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";

    $response = file_get_contents($url);
    $json = json_decode($response, true);

    if ($json['status'] === 'OK' && !empty($json['results'][0])) {
        return $json['results'][0]['formatted_address'];
    } else {
        return null;
    }
}

function fetchNearbyPlaces($lat, $lng, $type, $radius, $apiKey) {
    $location = $lat . ',' . $lng;

    if ($type === 'entertainment') {
        // Nếu là loại giải trí, tìm nhiều loại
        $entertainmentTypes = ['park', 'playground', 'movie_theater'];
        $results = [];

        foreach ($entertainmentTypes as $entType) {
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" .
                   "location={$location}&radius={$radius}&type={$entType}&key={$apiKey}";

            $response = json_decode(file_get_contents($url), true);
            if (!empty($response['results'])) {
                $results = array_merge($results, $response['results']);
            }
        }

        return $results;
    }

	if ($type === 'market') {
        $marketTypes = ['supermarket', 'convenience_store', 'grocery_or_supermarket', 'shopping_mall'];
        $results = [];

        foreach ($marketTypes as $mType) {
            $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" .
                   "location={$location}&radius={$radius}&type={$mType}&key={$apiKey}";
            $response = json_decode(file_get_contents($url), true);
            if (!empty($response['results'])) {
                $results = array_merge($results, $response['results']);
            }
        }

        return $results;
    }

    // Trường hợp khác
    $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?" .
           "location={$location}&radius={$radius}&type={$type}&key={$apiKey}";

    $response = json_decode(file_get_contents($url), true);
    return $response['results'] ?? [];
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
?>