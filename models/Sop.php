<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class Sop extends dbBasic{
	function __construct(){
		global $core, $clsISO;
		$this->pkey = "sop_id";
		$this->tbl = DB_PREFIX."sop";
	}
	function getTitle($pval,$_args=array()){
		if(!isset($_args['title'])){
			$_args = $this->getOne($pval,"title");
		}
		return $_args['title'];
	}
	function doDelete($sop_id){
		// Delete
		$this->deleteOne($sop_id);
		return 1;
	}
	function convertToCurrency($str) {
		// Bỏ khoảng trắng thừa
		$str = trim($str);
		// Lấy đơn vị
		$unit = 'ty';
		if (preg_match('/tỷ|ty/i', $str)) {
			$unit = 'ty';
		} elseif (preg_match('/triệu|trieu/i', $str)) {
			$unit = 'trieu';
		} elseif (preg_match('/nghìn|nghin|ngan/i', $str)) {
			$unit = 'nghin';
		}
		// Lấy phần số (cho phép . hoặc ,)
		$numStr = preg_replace('/[^\d.,]/', '', $str);
		// Chuẩn hóa về dấu thập phân là "."
		if (strpos($numStr, ',') !== false && strpos($numStr, '.') !== false) {
			$numStr = str_replace('.', '', $numStr);
			$numStr = str_replace(',', '.', $numStr);
		} elseif (strpos($numStr, ',') !== false) {
			$numStr = str_replace(',', '.', $numStr);
		}
		$number = floatval($numStr);
		// Nhân theo đơn vị
		switch ($unit) {
			case 'ty':
				$number *= 1000000000;
				break;
			case 'trieu':
				$number *= 1000000;
				break;
			case 'nghin':
				$number *= 1000;
				break;
		}
		return number_format($number, 0, ',', '.');
	}
	function format_stock_code($stock_code){
		$stock_code = strtoupper($stock_code);
		$stock_code = preg_replace('/\s+/', '', $stock_code);
		if(preg_match('/^p/i', $stock_code)){
			$stock_code = str_replace('.', '', $stock_code);
		}
		$stock_code = str_replace('M1.','L26M.', $stock_code);
		$stock_code = str_replace('M2.','T30.', $stock_code);
		$stock_code = str_replace('M3.','L26.', $stock_code);
		$stock_code = str_replace('H1.','L27M.', $stock_code);
		$stock_code = str_replace('H2.','U38.', $stock_code);
		$stock_code = str_replace('H3.','L27.', $stock_code);
		return $stock_code;
	}
	function str_replace_first($search, $replace, $subject)	{
		$search = '/'.preg_quote($search, '/').'/';
		return preg_replace($search, $replace, $subject, 1);
	}
	function getLink($sop_id, $stock_code){
		global $core, $dbconn;
		return sprintf(MYOCEAN_URL.'/cn/%s-%s.html', $stock_code, $sop_id);
	}
	function getLinkEdit($sop_id, $return_url){
		global $core, $dbconn, $clsISO;
		if(!empty($return_url))
			$return_url = $clsISO->base64url_encode($return_url);
		return sprintf(MYOCEAN_URL.'/cn/edit/%s?utm_source=CA&utm_return=%s',$sop_id, $return_url);
	}
	function getCode($oDataTable){
		global $core;
		$more_information = $oDataTable['more_information'];
		$building_id = $oDataTable['building_id'];
		$stock_code = $oDataTable['stock_code'];
		$floor = $oDataTable['floor'];
		$code = $oDataTable['code'];
		//var_dump($more_information); die();
		if(isset($more_information['hide_code'])){
			if((int) $more_information['hide_code'] == _STOCK_HIDECODE_FLOOR_ID){
				return $this->str_replace_first($floor, 'XX', $stock_code);
			} else if((int) $more_information['hide_code'] == _STOCK_HIDECODE_CODE_ID){
				//return str_replace($code, 'XX', $stock_code);
				return preg_replace("((.*)".$code.")", "$1XX", $stock_code);
			} else if((int) $more_information['hide_code'] == _STOCK_HIDECODE_BOTH_ID){
				$stock_code = $this->str_replace_first($floor, 'XX', $stock_code);
				return preg_replace("((.*)".$code.")", "$1XX", $stock_code);
			} else {
				return $stock_code;
			}
		}
		return $stock_code;
	}
	function getRangeFloor($floor){
		if((int) $floor < 10)
			return 'Thấp (1-10)';
		else if((int) $floor > 10 && (int) $floor < 20)
			return 'Trung (10-20)';
		else if((int) $floor > 20)
			return 'Cao (>20)';
		else 
			return '--';
	}
	function getFeeLabel($fee_id){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$field = "title,bgcolor,textcolor";
		$oneProperty = $clsProperty->getOne($fee_id, $field);
		return sprintf('<span style="background:%s" class="d-inline-flex align-items-center text-white fs-11 py-1 px-2 gap-1 rounded-pill">
			<i class="material-icons-outlined fs-12 no-translate">paid</i> %s</span>
		', $oneProperty['bgcolor'], $oneProperty['title']);
	}
	function getInteriorLabel($interior_id){
		global $core, $dbconn;
		$clsProperty = new Property();
		$field = "title,bgcolor,textcolor";
		$oneProperty = $clsProperty->getOne($interior_id, $field);
		return sprintf('<span style="background:%s" class="d-inline-flex align-items-center text-white fs-11 py-0 px-2 rounded-pill mr-1">%s</span>', $oneProperty['bgcolor'], $oneProperty['title']);
	}
	function updateMeta($sop_id){
		global $core, $dbconn, $clsISO;
		$clsStock = new Stock();
		$clsProperty = new Property();
		###
		$field = "{$this->pkey},`sop_type`,`title`,`stock_code`,`more_information`,
		`price`,`home_direction_id`,`bedroom_id`,`building_id`,`project_id`,`block_id`";
		$oneSop = $this->getOne($sop_id, $field);	
		###
		$sop_type = $oneSop['sop_type'];
		$bedroom_id = (int) $oneSop['bedroom_id'];
		$building_id = (int) $oneSop['building_id'];
		$home_direction_id = (int) $oneSop['home_direction_id'];
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$floor_count = (int) $core->get_field($more_information, 'floor_count', 0);
		$type_villa_id = (int) $core->get_field($more_information, 'type_villa_id', 0);
		$oneSop['more_information'] = $more_information;
		$stock_code = $this->getCode($oneSop);
		###
		$arr_property_cached = array();
		if($building_id > 0){
			$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
		}
		if($home_direction_id > 0){
			$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
		}
		###
		if($bedroom_id > 0 && $sop_type == _SOP_TYPE_HIGHLEVEL){
			$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
			$bedroom_name = "Căn ".$arr_property_cached[$bedroom_id];
		} else if(!empty($more_information['bedroom_count'])) {
			$bedroom_name = "Căn ".$more_information['bedroom_count']."PN";
		}
		###
		$title_sub = "";
		if($type_villa_id > 0){
			$title_sub.= sprintf("Căn %s", $clsProperty->getTitle($type_villa_id));
		}
		if($floor_count > 0){
			$title_sub.= sprintf(" %s tầng", $floor_count);
		}
		if($sop_type == _SOP_TYPE_HIGHLEVEL) {
			$txt_building = "Tòa";	
			$title = sprintf('%s,%s %s, Hướng %s, Diện tích %sm2', $bedroom_name, $txt_building,
				$arr_property_cached[$building_id], 
				$arr_property_cached[$home_direction_id], 
				$more_information['DT_TT']
			);
		} else {
			$txt_building = "Dãy";
			$title = sprintf('Bán %s, '.$txt_building.' %s, Hướng %s %sm2', 
				$title_sub, 
				$arr_property_cached[$building_id], 
				$arr_property_cached[$home_direction_id], 
				$more_information['DT_TT']
			);
		}
		$update_data = array();
		$update_data['title'] = $title;
		$update_data['slug'] = $core->replaceSpace($title);
		
		$description_page = "Cần bán {$stock_code} + ".$txt_building. " " . $arr_property_cached[$building_id];
		if($sop_type != _SOP_TYPE_HIGHLEVEL) {
			if(!empty($more_information['bedroom_count'])) {
				$description_page .= (($description_page != "") ? " + " : "") . ($more_information['bedroom_count']."PN");
			}
		}else{
			if($bedroom_id > 0) {
				$description_page .= (($description_page != "") ? " + " : "") . $arr_property_cached[$bedroom_id];
			}
		}
		if(!empty($more_information['DT_TT'])) {
			$description_page .= (($description_page != "") ? " + " : "") . ($more_information['DT_TT']."m2");
		}
		if(!empty($oneItem['price'])) {
			$description_page .= (($description_page != "") ? " + " : "") . $clsISO->shortNumber($oneSop['price']);
		}
		$more_information['stock_code'] = $stock_code;
		$more_information['content'] = $this->gen_content($sop_id, $oneSop);
		$more_information['title_page'] = sprintf('%s - %s', $title, 'Hệ thống giao dịch thứ cấp Ocean City - My Ocean City - Hệ thống hỗ trợ bán hàng Ocean City');
		$more_information['description_page'] = sprintf('%s - %s', $description_page, 'Hệ thống giao dịch thứ cấp Ocean City - My Ocean City - Hệ thống hỗ trợ bán hàng Ocean City');
		$update_data['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
		// $clsISO->print_pre($update_data);die;
		$this->updateOne($sop_id, $update_data);
	}
	function updateMetaNoStockCode($sop_id){
		global $core, $dbconn, $clsISO;
		$clsStock = new Stock();
		$clsProperty = new Property();
		###
		$field = "`title`,`slug`,`stock_code`,`more_information`,`price`,`bedroom_id`,`building_id`";
		$field.= ",`home_direction_id`,`sop_type`";
		$oneItem = $this->getOne($sop_id, $field);
		$more_information = $oneItem['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$floor_count = (int) $core->get_field($more_information, 'floor_count', 0);
		$type_villa_id = (int) $core->get_field($more_information, 'type_villa_id', 0);
		$title = $oneItem['title'];
		$sop_type = $oneItem['sop_type'];
		$bedroom_id = (int) $oneItem['bedroom_id'];
		$building_id = (int) $oneItem['building_id'];
		$home_direction_id = $oneItem['home_direction_id'];
		$stock_code = $oneItem['stock_code'];
		
		$arr_property_cached = array();
		if($bedroom_id > 0 && $sop_type== _SOP_TYPE_HIGHLEVEL) 
			$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);
		if($building_id > 0)
			$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);
		if($home_direction_id > 0)
			$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);
		
		if(!empty($bedroom_id) && $sop_type == _SOP_TYPE_HIGHLEVEL){
			$bedroom_name = "Căn ".$arr_property_cached[$bedroom_id];
		} else if(!empty($more_information['bedroom_count'])) {
			$bedroom_name = "Căn ".$more_information['bedroom_count']."PN";
		} 
		$title_sub = "";
		if($type_villa_id > 0){
			$title_sub.= sprintf("Căn %s", $clsProperty->getTitle($type_villa_id));
		}
		if($floor_count > 0){
			$title_sub.= sprintf(" %s tầng", $floor_count);
		}
		###
		$txt_building = "Tòa";
		if($sop_type == _SOP_TYPE_LOWFLOOR) {
			$txt_building = "Dãy";
			if(empty($oneItem['title'])){}
			if($building_id > 0){
				$title = sprintf('Bán %s '.$txt_building.' %s Hướng %s %sm2', 
					$title_sub, 
					$arr_property_cached[$building_id], 
					$arr_property_cached[$home_direction_id], 
					$more_information['DT_TT']
				);
			} else {
				$title = sprintf('Bán %s Hướng %s %sm2', 
					$title_sub, 
					$arr_property_cached[$home_direction_id], 
					$more_information['DT_TT']
				);
			}
		} else {
			if(empty($oneItem['title'])){}
			$title = sprintf('%s '.$txt_building.' %s Hướng %s %sm2', 
				$bedroom_name, 
				$arr_property_cached[$building_id], 
				$arr_property_cached[$home_direction_id], 
				$more_information['DT_TT']
			);
		}
		$title_page = sprintf('%s - %s', $title, 'Hệ thống giao dịch thứ cấp Ocean City 
		- My Ocean City - Hệ thống hỗ trợ bán hàng Ocean City');
		$more_information['title_page'] = $title_page;
		$more_information['description_page'] = sprintf("%s - Hệ thống giao dịch thứ cấp Ocean City - My Ocean City - Hệ thống hỗ trợ bán hàng Ocean City", $title);
		$more_information['content'] = $this->gen_content($sop_id, $oneItem);
		###
		$update_data = array(
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		);
		// $clsISO->print_pre($more_information); die();
		$this->updateOne($sop_id, $update_data);
	}
	function checkLiked($sop_id){
		global $oneProfile;
		$more_information = $oneProfile['more_information'];
		$liked_sop = isset($more_information['liked_sop']) 
			? $more_information['liked_sop'] : array();
		if(in_array($sop_id, $liked_sop))
			return 1;
		return 0;
	}
	function getUserNotify(){
		global $core, $dbconn, $clsISO, $profile_id;
		$u = array(7,9);
		return $u;
		return @array_diff($u, array($profile_id));
	}
	function checkSolded($sop_id, $oDataTable = array()){
		global $core, $dbconn, $clsISO;
		if(!empty($more_information)){
			if(isset($more_information['is_solded']) && (int) $more_information['is_solded'] == 1)
				return 1;
			return 0;
		} else {
			$more_information = $this->getOneField('more_information', $sop_id);
			$more_information = $clsISO->to_array_json($more_information);
			if(isset($more_information['is_solded']) && (int) $more_information['is_solded'] == 1)
				return 1;
			return 0;
		}
	}
	function checkDeleted($sop_id, $oDataTable = array()){
		global $core, $dbconn, $clsISO;
		if(!empty($more_information)){
			if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1)
				return 1;
			return 0;
		} else {
			$more_information = $this->getOneField('more_information', $sop_id);
			$more_information = $clsISO->to_array_json($more_information);
			if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1)
				return 1;
			return 0;
		}
	}
	function getIconVerified($sop_id, $type_list="publish", $openFrom="_list"){
		global $core, $dbconn, $clsISO;
		$field = "`more_information`,`is_locked`,`is_solded`,`is_online`,`is_verified`";
		$oSop = $this->getOne($sop_id, $field);
		$more_information = $oSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		
		$icon = "";
		$attrs = 'data-bs-toggle="tooltip" data-bs-trigger="hover"';
		if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1){
			$icon = '<span class="text-warning">
				<i '.$attrs.' class="material-icons-outlined" title="Đã xoá">delete</i> Đã xoá
			</span>';
		} else if($oSop['is_solded'] == 1) {
			return '<span class="text-info">
				<i '.$attrs.' class="material-icons-outlined fs-small" title="Đã khóa">sell</i> Đã bán
			</span>';
		} else if($oSop['is_locked'] == 1) {
			$icon = '<span class="text-danger">
				<i '.$attrs.' class="material-icons-outlined fs-small" title="Đã khóa">lock</i> Đang khoá
			</span>';
		} else {
			$icon = '<span class="text-success">
				<i '.$attrs.' class="material-icons-outlined fs-small" title="Còn hàng">local_mall</i> Còn hàng
			</div>';
		}
		// Return
		return $icon;
	}
	function getIcon($sop_id, $type_list="publish", $openFrom="_list"){
		global $core, $dbconn, $clsISO;
		$field = "`more_information`,`is_locked`,`is_solded`";
		$oSop = $this->getOne($sop_id, $field);
		$more_information = $oSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		###
		$icon = ""; $attrs = 'data-bs-toggle="tooltip" data-bs-trigger="hover"';
		if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1){
			$icon = '<span class="text-warning">
				<i '.$attrs.' class="material-icons-outlined" title="Đã xoá">delete</i> Đã xoá
			</span>';
		} else if($oSop['is_solded'] == 1) {
			return '<span class="text-info">
				<i '.$attrs.' class="material-icons-outlined fs-small" title="Đã khóa">sell</i> Đã bán
			</span>';
		} else if($oSop['is_locked'] == 1) {
			$icon = '<span class="text-danger">
				<i '.$attrs.' class="material-icons-outlined fs-small" title="Đã khóa">lock</i> Đang khoá
			</span>';
		} else {
			$icon = '<span class="text-success">
				<i '.$attrs.' class="material-icons-outlined fs-small" title="Còn hàng">local_mall</i> Còn hàng
			</div>';
		}
		// Return
		return $icon;
	}
	function getIframeVideo($more_information,$type="iframe"){
		global $core, $dbconn;
		$html = "";
		$video_type = isset($more_information['video_type']) 
			? $more_information['video_type'] : "upload";
		if($video_type == 'upload'){
			$video_url = isset($more_information['video_url']) && !empty($more_information['video_url']) 
				? trim($more_information['video_url']) : "";
			if(!empty($video_url)){
				if($type == "link") {
					return $video_url;
				}else{
					$html = '<div class="iframe-wrapper">
						<iframe src="'.$video_url.'" frameborder="0" width="100%" height="400px"></iframe>
					</div>';
				}				
			}
		} else if($video_type=='youtube'){
			$youtue_url = isset($more_information['youtue_url']) && !empty($more_information['youtue_url']) 
				? trim($more_information['youtue_url']) : "";
			if(!empty($youtue_url) && preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/',$youtue_url)){
				if($type == "link") {
					return $youtue_url;
				}else{
					preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $youtue_url, $matches);
					$html = '<div class="iframe-wrapper">
						<iframe src="https://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen width="100%" height="400px"></iframe>
					</div>';
				}
			}
		}
		return $html;
	}
	function crawl(){
		// ini_set('display_errors',1);
		// error_reporting(E_ALL);
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsStock = new Stock();
		$clsProperty = new Property();
		#- Require library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');				
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
		// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
		$range = 'Bảng Hàng'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1vRLE9rKm3A2vSpv-6eHI6rYMRkZ3340RwCYjdV2Rf5U';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		// $clsISO->print_pre($tblData); die();
		$total_inserted = $total_updated = 0;
		if(!empty($tblData)){
			for($i=1; $i<count($tblData); $i++){
				$status_name = $tblData[$i][2];
				$sop_code = $tblData[$i][3];
				$stock_code = $tblData[$i][4];
				$stock_hidden_code = $tblData[$i][5];
				$bedroom_name = $tblData[$i][6];
				$DT_TT = $tblData[$i][7];
				$price_owner = $clsISO->processSmartNumber($tblData[$i][8]);
				$fee_included_name = $tblData[$i][9];
				$price = $clsISO->processSmartNumber($tblData[$i][10]);
				$home_direction_name = $tblData[$i][12];
				$view_name = $tblData[$i][13];
				$interior_name = $tblData[$i][14];
				$juridical_name = $tblData[$i][15];
				$short_intro = $tblData[$i][16];
				$image_folder = $tblData[$i][17];
				$status_viewing = $tblData[$i][18];
				$pass_door = $tblData[$i][19];
				$contact_name = $tblData[$i][20];
				$contact_phone = $tblData[$i][21];
				$source_name = $tblData[$i][22];
				$notes = $tblData[$i][23];
				##
				$is_valid = 0;
				if(!empty($stock_code) && !empty($price)){
					$is_valid = 1;
				}
				if($is_valid == 1){
					$sop_type = _SOP_TYPE_HIGHLEVEL;
					if(strlen($price) <= 4) $price *= 1000000;
					if(strlen($price_owner) <= 4) $price_owner *= 1000000;
					$project_id = $block_id = $building_id = $is_stocked = 0;
					$stock_id = $home_direction_id = $bedroom_id = $floor = $code = 0;
					if(!empty($stock_code)){
						$is_stocked = 1;
						$stock_code = $this->format_stock_code($stock_code);
						$field = "{$clsStock->pkey},stock_type,more_information,bedroom_id
						,home_direction_id,project_id,block_id,building_id,floor,code";
						$tmp = $clsStock->getByCond("`is_trash`=0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
						AND `ms_code`='{$stock_code}' limit 0,1", $field);
						if(!empty($tmp)){
							$code = $tmp['code'];
							$floor = $tmp['floor'];
							$stock_id = $tmp[$clsStock->pkey];
							$project_id = $tmp['project_id'];
							$block_id = $tmp['block_id'];
							$building_id = $tmp['building_id'];
							$bedroom_id = $tmp['bedroom_id'];
							$home_direction_id = $tmp['home_direction_id'];
							$more_information = $tmp['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							if(!$DT_TT) $DT_TT = $more_information['DT_TT'];
							if($tmp['stock_type'] == _BLOCK_TYPE_LOWFLOOR_SALE){
								$sop_type = _SOP_TYPE_LOWFLOOR;
							}
						}
					}
					#-- Price/m2
					$price_m2 = 0;
					if($price > 0 && !empty($DT_TT)){
						$price_m2 = round($price / $clsISO->convertToNumber($DT_TT), 2);
					}
					// $clsISO->print_pre($tmp); die();
					$is_locked = $is_solded = $is_deleted = 0;
					$status_slug = $clsISO->replaceSpace($status_name);
					if($status_name == 'Đang lock'){
						$is_locked = 1;
					} else if($status_name == 'Đã bán' || $status_slug == 'da-ban'){
						$is_solded = 1;
					} else if($status_name == 'Đã xóa'){
						$is_deleted = 1;
					}
					if($bedroom_id == 0 && !empty($bedroom_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_BEDROOM' 
							and `slug`='".$core->replaceSpace($bedroom_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $bedroom_id = $tmp[$clsProperty->pkey];
					}
					if($home_direction_id == 0 && !empty($home_direction_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
							and `slug`='".$core->replaceSpace($home_direction_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $home_direction_id = $tmp[$clsProperty->pkey];
					}
					$view_id = 0;
					if(!empty($view_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_VIEW' 
							and `slug`='".$core->replaceSpace($view_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)){
							$view_id = $tmp[$clsProperty->pkey];
						} else {
							$view_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $view_id,
								'property_type' => '_VIEW',
								'title' => $view_name,
								'slug' => $core->replaceSpace($view_name),
								'order_no' => $clsProperty->getMaxOrderNo(),
								'is_locked' => 1,
								'reg_date' => time(),
								'upd_date' => time(),
							));
						}
					}
					$fee_included = _FEE_INCLUDED_NO_ID;
					if(!empty($fee_included_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_FEE_TYPE' 
							and `slug`='".$core->replaceSpace($fee_included_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $fee_included = $tmp[$clsProperty->pkey];
					}
					$interior_id = _INTERIOR_TYPE_BASIC_ID;
					if(!empty($interior_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_INTERIOR_TYPE' 
							and `slug`='".$core->replaceSpace($interior_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $interior_id = $tmp[$clsProperty->pkey];
					}
					$juridical_id = _JURIDICAL_NO_LOAN_ID;
					if(!empty($juridical_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_JURIDICAL' 
							and `slug`='".$core->replaceSpace($juridical_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $juridical_id = $tmp[$clsProperty->pkey];
					}
					$status_viewing_id = _SOP_STATUS_VIEWING_NO_ID;
					if(!empty($status_viewing)){
						$tmp = $clsProperty->getByCond("`property_type`='_STATUS_VIEWING' 
							and `slug`='".$core->replaceSpace($status_viewing)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $status_viewing_id = $tmp[$clsProperty->pkey];
					}
					$agency_id = 0;
					if(!empty($source_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_SOURCE' 
							and `slug`='".$core->replaceSpace($source_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $agency_id = $tmp[$clsProperty->pkey];
					}
					$images = array();
					if(!empty($image_folder)){
						$clsProjectMeta = new ProjectMeta();
						$tmp = $clsProjectMeta->crawl($image_folder);
						if(isset($tmp['list_files']) && !empty($tmp['list_files'])){
							foreach($tmp['list_files'] as $okey => $oval){
								$images[] = $clsISO->genGoogleURL($okey, 'view');
							}
						}
					}
					$tmp = $this->getByCond("`user_id`='"._PROFILE_SOP_ADMIN_ID."' AND `stock_code`='{$stock_code}'");
					if(!empty($tmp)){
						$update_data = array();
						$more_information = $tmp['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$more_information['price_m2'] = $price_m2;
						$more_information['is_locked'] = $is_locked;
						$more_information['is_solded'] = $is_solded;
						$more_information['is_deleted'] = $is_deleted;
						$update_data['is_locked'] = $is_locked;
						$update_data['is_solded'] = $is_solded;
						if(!empty($images)) $more_information['images'] = $images;
						if($project_id > 0) $update_data['project_id'] = $project_id;
						if($block_id > 0) $update_data['block_id'] = $block_id;
						if($building_id > 0) $update_data['building_id'] = $building_id;
						if($stock_id > 0) $update_data['stock_id'] = $stock_id;
						if($bedroom_id > 0) $update_data['bedroom_id'] = $bedroom_id;
						if($home_direction_id > 0 && $this->compare($tmp, "home_direction_id", $home_direction_id)) {
							$update_data['home_direction_id'] = $home_direction_id;
						}
						if(!empty($floor) && $this->compare($tmp, "floor", $floor)) {
							$update_data['floor'] = $floor;
						}
						if(!empty($code) && $this->compare($tmp, "code", $code)) {
							$update_data['code'] = $code;
						}
						if($status_viewing_id>0 
							&& $this->compare($more_information,'status_viewing_id',$status_viewing_id)) {
							$more_information['status_viewing_id'] = $status_viewing_id;
						}
						if($juridical_id>0 && $this->compare($more_information,'juridical_id',$juridical_id)) {
							$update_data['juridical_id'] = $juridical_id;
							$more_information['juridical_id'] = $juridical_id;
						}
						if($fee_included>0 
							&& $this->compare($more_information,'fee_included',$fee_included)) {
							$update_data['fee_included'] = $fee_included;
							$more_information['fee_included'] = $fee_included;
						}
						if($interior_id>0 
							&& $this->compare($more_information,'interior_id',$interior_id)) {
							$update_data['interior_id'] = $interior_id;
							$more_information['interior_id'] = $interior_id;
						}
						if($agency_id > 0 
							&& $this->compare($tmp, 'agency_id', $agency_id)){
							$update_data['agency_id'] = $agency_id;
							$more_information['agency_id'] = $agency_id;
						}
						if(!empty($short_intro) 
								&& $this->compare($more_information,'short_intro',$short_intro)){
							$more_information['short_intro'] = $short_intro;
						}
						if(!empty($contact_name) 
							&& $this->compare($more_information,'contact_name',$contact_name)){
							$more_information['contact_phone'] = $contact_phone;
						}
						if(!empty($contact_phone) 
							&& $this->compare($more_information,'contact_phone',$contact_phone)){
							$more_information['contact_phone'] = $contact_phone;
						}
						// $clsISO->print_pre($more_information); die();
						if($this->updateOne($tmp[$this->pkey], array_merge($update_data, array(
							'stock_code' => $stock_code,
							'price' => $price,
							'price_m2' => $price_m2,
							'price_owner' => $price_owner,
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'user_id_update' => $profile_id,
							'upd_date' => time(),
						)))){
							if($is_stocked){
								$this->updateMeta($tmp[$this->pkey]);
							} else {
								$this->updateMetaNoStockCode($tmp[$this->pkey]);
							}
							$total_updated += 1;
						}
					} else {
						$sop_id = $this->getMaxId();
						$more_information = array(
							'sop_code' => $sop_code,
							'stock_hidden_code' => $stock_hidden_code,
							'hide_code' => _STOCK_HIDECODE_FLOOR_ID,
							'pass_door' => $pass_door,
							'view_id' => $view_id,
							'DT_TT' => $DT_TT,
							'short_intro' => $short_intro,
							'agency_id' => $agency_id,
							'status_viewing_id' => $status_viewing_id,
							'juridical_id' => $juridical_id,
							'interior_id' => $interior_id,
							'fee_included' => $fee_included,
							'contact_name' => $contact_name,
							'contact_phone' => $contact_phone,
							'notes' => $notes,
							'images' => $images,
							'video_type' => 'upload',
							'sop_type' 	=> $sop_type,
							'is_furnished' 	=> 0,
							'is_owner' 		=> 0,
							'is_exclusive' 	=> 0,
							'is_locked' 	=> $is_locked,
							'is_solded' 	=> $is_solded,
							'is_deleted' 	=> $is_deleted
						);
						// $clsISO->print_pre($more_information); die();
						if($this->insert(array(
							$this->pkey => $sop_id,
							'sop_type' => $sop_type,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'building_id' => $building_id,
							'agency_id' => $agency_id,
							'stock_id' => $stock_id,
							'stock_code' => $stock_code,
							'bedroom_id' => $bedroom_id,
							'home_direction_id' => $home_direction_id,
							'floor' => $floor,
							'code' => $code,
							'price' => $price,
							'price_m2' => $price_m2,
							'price_owner' => $price_owner,
							'fee_included' => $fee_included,
							'juridical_id' => $juridical_id,
							'interior_id' => $interior_id,
							'is_online' => 1,
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'user_id' => _PROFILE_SOP_ADMIN_ID,
							'user_id_update' => $profile_id,
							'is_locked' 	=> $is_locked,
							'is_solded' 	=> $is_solded,
							'reg_date' => time(),
							'upd_date' => time(),
						))){
							if($is_stocked){
								$this->updateMeta($sop_id);
							} else {
								$this->updateMetaNoStockCode($sop_id);
							}
							$total_inserted += 1;
						}
					}
				}
			}
		}
		// Return
		return array(
			'msg' => $msg,
			'total_inserted' => $total_inserted,
			'total_updated' => $total_updated
		);
	}
	function crawl_tt(){
		// ini_set('display_errors',1);
		// error_reporting(E_ALL);
		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;
		$clsStock = new Stock();
		$clsProperty = new Property();
		#- Require library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');				
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
		// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
		$range = 'Bảng Hàng'; // here we use the name of the Sheet to get all the rows
		$spreadsheetId = '1dU-YpGzc9-QO3MdC_EJ2NeObJMkdHOnoDHqD1KdKrDk';
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		// $clsISO->print_pre($tblData); die();
		$total_inserted = $total_updated = 0;
		if(!empty($tblData)){
			for($i=1; $i<count($tblData); $i++){
				$status_name = $tblData[$i][2];
				$sop_code = $tblData[$i][3];
				$stock_code = $tblData[$i][4];
				$stock_hidden_code = $tblData[$i][5];
				$type_villa_name = $tblData[$i][6];
				$DT_TT = $tblData[$i][7];
				$DT_Tim = $tblData[$i][8];
				$price_owner = $clsISO->processSmartNumber($tblData[$i][9]);
				$fee_included_name = $tblData[$i][10];
				$price = $clsISO->processSmartNumber($tblData[$i][11]);
				$floor_count = $tblData[$i][13];
				$bedroom_count = $tblData[$i][14];
				$bathroom_count = $tblData[$i][15];
				$balcony_count = $tblData[$i][16];
				$home_direction_name = $tblData[$i][17];
				$view_name = $tblData[$i][18];
				$interior_name = $tblData[$i][19];
				$juridical_name = $tblData[$i][20];
				$short_intro = $tblData[$i][21];
				$image_folder = $tblData[$i][22];
				$status_viewing = $tblData[$i][23];
				$pass_door = $tblData[$i][24];
				$contact_name = $tblData[$i][25];
				$contact_phone = $tblData[$i][26];
				$source_name = $tblData[$i][27];
				$notes = $tblData[$i][28];
				##
				$is_valid = 0;
				if(!empty($stock_code) && !empty($price)){
					$is_valid = 1;
				}
				if($is_valid == 1){
					$sop_type = _SOP_TYPE_LOWFLOOR;
					if(strlen($price) <= 4) $price *= 1000000;
					if(strlen($price_owner) <= 4) $price_owner *= 1000000;
					$project_id = $block_id = $building_id = $is_stocked = 0;
					$stock_id = $home_direction_id = $type_villa_id = $floor = $code = 0;
					if(!empty($stock_code)){
						$is_stocked = 1;
						$stock_code = $this->format_stock_code($stock_code);
						$field = "{$clsStock->pkey},stock_type,more_information,bedroom_id
						,home_direction_id,project_id,block_id,building_id,floor,code,type_id";
						$tmp = $clsStock->getByCond("`is_trash`=0 and `status_id`<>'"._STOCK_STATUS_NON_ID."' 
						AND `ms_code`='{$stock_code}' limit 0,1", $field);
						if(!empty($tmp)){
							$code = $tmp['code'];
							$floor = $tmp['floor'];
							$stock_id = $tmp[$clsStock->pkey];
							$project_id = $tmp['project_id'];
							$block_id = $tmp['block_id'];
							$building_id = $tmp['building_id'];
							$bedroom_id = $tmp['bedroom_id'];
							$type_villa_id = $tmp['type_id'];
							$home_direction_id = $tmp['home_direction_id'];
							$more_information = $tmp['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							if(!$DT_TT) $DT_TT = $more_information['DT_TT'];
							if(!$DT_Tim) $DT_Tim = $more_information['DT_Tim'];
							if($tmp['stock_type'] == _BLOCK_TYPE_LOWFLOOR_SALE){
								$sop_type = _SOP_TYPE_LOWFLOOR;
							}
						}
					}
					#-- Price/m2
					$price_m2 = 0;
					if($price > 0 && !empty($DT_TT)){
						$price_m2 = round($price / $clsISO->convertToNumber($DT_TT), 2);
					}
					// $clsISO->print_pre($tmp); die();
					$is_locked = $is_solded = $is_deleted = 0;
					if($status_name == 'Đang lock'){
						$is_locked = 1;
					} else if($status_name == 'Đã bán'){
						$is_solded = 1;
					} else if($status_name == 'Đã xóa'){
						$is_deleted = 1;
					}
					if($type_villa_id == 0 && !empty($type_villa_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_TYPE_VILLA' 
							and `slug`='".$core->replaceSpace($type_villa_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $type_villa_id = $tmp[$clsProperty->pkey];
					}
					if($home_direction_id == 0 && !empty($home_direction_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
							and `slug`='".$core->replaceSpace($home_direction_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $home_direction_id = $tmp[$clsProperty->pkey];
					}
					$view_id = 0;
					if(!empty($view_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_VIEW' 
							and `slug`='".$core->replaceSpace($view_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)){
							$view_id = $tmp[$clsProperty->pkey];
						} else {
							$view_id = $clsProperty->getMaxId();
							$clsProperty->insert(array(
								$clsProperty->pkey => $view_id,
								'property_type' => '_VIEW',
								'title' => $view_name,
								'slug' => $core->replaceSpace($view_name),
								'order_no' => $clsProperty->getMaxOrderNo(),
								'is_locked' => 1,
								'reg_date' => time(),
								'upd_date' => time(),
							));
						}
					}
					$fee_included = _FEE_INCLUDED_NO_ID;
					if(!empty($fee_included_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_FEE_TYPE' 
							and `slug`='".$core->replaceSpace($fee_included_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $fee_included = $tmp[$clsProperty->pkey];
					}
					$interior_id = _INTERIOR_TYPE_BASIC_ID;
					if(!empty($interior_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_INTERIOR_TYPE' 
							and `slug`='".$core->replaceSpace($interior_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $interior_id = $tmp[$clsProperty->pkey];
					}
					$juridical_id = _JURIDICAL_NO_LOAN_ID;
					if(!empty($juridical_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_JURIDICAL' 
							and `slug`='".$core->replaceSpace($juridical_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $juridical_id = $tmp[$clsProperty->pkey];
					}
					$status_viewing_id = _SOP_STATUS_VIEWING_NO_ID;
					if(!empty($status_viewing)){
						$tmp = $clsProperty->getByCond("`property_type`='_STATUS_VIEWING' 
							and `slug`='".$core->replaceSpace($status_viewing)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $status_viewing_id = $tmp[$clsProperty->pkey];
					}
					$agency_id = 0;
					if(!empty($source_name)){
						$tmp = $clsProperty->getByCond("`property_type`='_SOURCE' 
							and `slug`='".$core->replaceSpace($source_name)."' limit 0,1", $clsProperty->pkey);
						if(!empty($tmp)) $agency_id = $tmp[$clsProperty->pkey];
					}
					$images = array();
					if(!empty($image_folder)){
						$clsProjectMeta = new ProjectMeta();
						$tmp = $clsProjectMeta->crawl($image_folder);
						if(isset($tmp['list_files']) && !empty($tmp['list_files'])){
							foreach($tmp['list_files'] as $okey => $oval){
								$images[] = $clsISO->genGoogleURL($okey, 'view');
							}
						}
					}
					$tmp = $this->getByCond("`user_id`='"._PROFILE_SOP_ADMIN_ID."' AND `stock_code`='{$stock_code}'");
					if(!empty($tmp)){
						$update_data = array();
						$more_information = $tmp['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						// $clsISO->print_pre($contact_name); die();
						$more_information['price_m2'] = $price_m2;
						$more_information['is_locked'] = $is_locked;
						$more_information['is_solded'] = $is_solded;
						$more_information['is_deleted'] = $is_deleted;
						$update_data['is_locked'] = $is_locked;
						$update_data['is_solded'] = $is_solded;
						if(!empty($images)) $more_information['images'] = $images;
						if($project_id > 0) $update_data['project_id'] = $project_id;
						if($block_id > 0) $update_data['block_id'] = $block_id;
						if($building_id > 0) $update_data['building_id'] = $building_id;
						if($stock_id > 0) $update_data['stock_id'] = $stock_id;
						if($home_direction_id > 0 && $this->compare($tmp, "home_direction_id", $home_direction_id)) {
							$update_data['home_direction_id'] = $home_direction_id;
						}
						if(!empty($floor) && $this->compare($tmp, "floor", $floor)) {
							$update_data['floor'] = $floor;
						}
						if(!empty($code) && $this->compare($tmp, "code", $code)) {
							$update_data['code'] = $code;
						}
						if($status_viewing_id>0 
							&& $this->compare($more_information,'status_viewing_id',$status_viewing_id)) {
							$more_information['status_viewing_id'] = $status_viewing_id;
						}
						if($juridical_id>0 && $this->compare($more_information,'juridical_id',$juridical_id)) {
							$update_data['juridical_id'] = $juridical_id;
							$more_information['juridical_id'] = $juridical_id;
						}
						if($fee_included>0 
							&& $this->compare($more_information,'fee_included',$fee_included)) {
							$update_data['fee_included'] = $fee_included;
							$more_information['fee_included'] = $fee_included;
						}
						if($interior_id>0 
							&& $this->compare($more_information,'interior_id',$interior_id)) {
							$update_data['interior_id'] = $interior_id;
							$more_information['interior_id'] = $interior_id;
						}
						if($agency_id > 0 
							&& $this->compare($tmp, 'agency_id', $agency_id)){
							$update_data['agency_id'] = $agency_id;
							$more_information['agency_id'] = $agency_id;
						}
						if(!empty($short_intro) 
								&& $this->compare($more_information,'short_intro',$short_intro)){
							$more_information['short_intro'] = $short_intro;
						}
						if(!empty($contact_name) 
							&& $this->compare($more_information,'contact_name',$contact_name)){
							$more_information['contact_name'] = $contact_name;
						}
						if(!empty($contact_phone) 
							&& $this->compare($more_information,'contact_phone',$contact_phone)){
							$more_information['contact_phone'] = $contact_phone;
						}
						// $clsISO->print_pre($more_information); die();
						if($this->updateOne($tmp[$this->pkey], array_merge($update_data, array(
							'stock_code' => $stock_code,
							'price' => $price,
							'price_m2' => $price_m2,
							'price_owner' => $price_owner,
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'user_id_update' => $profile_id,
							'upd_date' => time(),
						)))){
							if($is_stocked){
								$this->updateMeta($tmp[$this->pkey]);
							} else {
								$this->updateMetaNoStockCode($tmp[$this->pkey]);
							}
							$total_updated += 1;
						}
					} else {
						$sop_id = $this->getMaxId();
						$more_information = array(
							'sop_code' => $sop_code,
							'stock_hidden_code' => $stock_hidden_code,
							'hide_code' => _STOCK_HIDECODE_FLOOR_ID,
							'pass_door' => $pass_door,
							'view_id' => $view_id,
							'DT_TT' => $DT_TT,
							'DT_Tim' => $DT_Tim,
							'short_intro' => $short_intro,
							'agency_id' => $agency_id,
							'type_villa_id' => $type_villa_id,
							'status_viewing_id' => $status_viewing_id,
							'juridical_id' => $juridical_id,
							'interior_id' => $interior_id,
							'fee_included' => $fee_included,
							'contact_name' => $contact_name,
							'contact_phone' => $contact_phone,
							'floor_count' => $floor_count,
							'bedroom_count' => $bedroom_count,
							'bathroom_count' => $bathroom_count,
							'balcony_count' => $balcony_count,
							'notes' => $notes,
							'images' => $images,
							'video_type' => 'upload',
							'sop_type' 	=> $sop_type,
							'is_furnished' 	=> 0,
							'is_owner' 		=> 0,
							'is_exclusive' 	=> 0,
							'is_locked' 	=> $is_locked,
							'is_solded' 	=> $is_solded,
							'is_deleted' 	=> $is_deleted
						);
						// $dbconn->debug = true;
						if($this->insert(array(
							$this->pkey => $sop_id,
							'sop_type' => $sop_type,
							'project_id' => $project_id,
							'block_id' => $block_id,
							'building_id' => $building_id,
							'agency_id' => $agency_id,
							'stock_id' => $stock_id,
							'stock_code' => $stock_code,
							'bedroom_id' => $bedroom_id,
							'home_direction_id' => $home_direction_id,
							'floor' => $floor,
							'code' => $code,
							'price' => $price,
							'price_m2' => $price_m2,
							'price_owner' => $price_owner,
							'fee_included' => $fee_included,
							'juridical_id' => $juridical_id,
							'interior_id' => $interior_id,
							'is_online' => 1,
							'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
							'user_id' => _PROFILE_SOP_ADMIN_ID,
							'user_id_update' => $profile_id,
							'is_locked' 	=> $is_locked,
							'is_solded' 	=> $is_solded,
							'reg_date' => time(),
							'upd_date' => time(),
						))){
							if($is_stocked){
								$this->updateMeta($sop_id);
							} else {
								$this->updateMetaNoStockCode($sop_id);
							}
							$total_inserted += 1;
						}
					}
				}
			}
		}
		// Return
		return array(
			'msg' => $msg,
			'total_inserted' => $total_inserted,
			'total_updated' => $total_updated
		);
	}
	function compare($more_information, $field, $new_value){
		global $core, $dbconn;
		$def_value = "";	
		if(in_array($field, array('status_viewing_id','juridical_id','fee_included','interior_id'
			,'project_id','block_id','building_id','stock_id','bedroom_id','home_direction_id'))){
			$def_value = 0;
			$old_value = (int) $core->get_field($more_information, $field, $def_value);
		} else {
			$old_value = $core->get_field($more_information, $field, $def_value);
		}
		if($old_value != $new_value)
			return 1;
		return 0;
	}
	function gen_content($sop_id, $oneSop = array()){
		global $core, $dbconn, $clsISO;
		$clsProject = new Project();
		$clsProperty = new Property();
		if(empty($oneSop)){
			$oneSop = $this->getOne($sop_id);
		}	
		$more_information = $oneSop['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		
		$content_arrs = array();
		$sop_type = $oneSop['sop_type'];
		$block_id = (int) $oneSop['block_id'];
		$bedroom_id = (int) $oneSop['bedroom_id'];
		$pass_door = $core->get_field($more_information, 'pass_door', "");
		$is_owner = (int) $core->get_field($more_information, 'is_owner', 0);
		$short_intro = $core->get_field($more_information, 'short_intro', "");
		$is_exclusive = (int) $core->get_field($more_information, 'is_exclusive', 0);
		$status_viewing_id = (int) $core->get_field($more_information, 'status_viewing_id', 0);
		$bedroom_count = (int) $core->get_field($more_information, 'bedroom_count', 0);
		if($is_owner == 1){
			$content_arrs[] = '❤️ Chính chủ cần bán ❤️';
		} else if($is_exclusive == 1){
			$content_arrs[] = '💕 Độc quyền cần bán 💕';
		}
		if($block_id > 0){
			// $content_arrs[] = sprintf('✅ Phân khu: %s', $clsProperty->getTitle($block_id));
		}
		if($sop_type == _SOP_TYPE_HIGHLEVEL){
			if($bedroom_id > 0){
				$content_arrs[] = sprintf('✅ Loại căn: %s', $clsProperty->getTitle($bedroom_id));
			} else if($bedroom_count > 0){
				$content_arrs[] = sprintf('✅ Loại căn: %sPN', $bedroom_count);
			}
		}
		# DT_TT
		$content_arrs[] = sprintf('✅ Diện tích: %sm2', $more_information['DT_TT']);
		# view
		$view_id = (int) $core->get_field($more_information, 'view_id', 0);
		if($view_id > 0){
			$content_arrs[] = sprintf('✅ Ban công: %s', $clsProperty->getTitle($view_id));
		}
		# Đồ
		$interior_id = (int) $oneSop['interior_id'];
		if($interior_id > 0){
			$content_arrs[] = sprintf('✅ Nội thất: %s', $clsProperty->getTitle($interior_id));
		}
		# Pháp lý
		$juridical_id = (int) $oneSop['juridical_id'];
		if($juridical_id > 0){
			$content_arrs[] = sprintf('✅ Pháp lý: %s', $clsProperty->getTitle($juridical_id));
		}
		$price = $oneSop['price'];
		$price = !empty($price) ? $clsISO->processSmartNumber($price) : 0;
		$content_arrs[] = sprintf('✅ Giá bán: %s%s.', $clsISO->shortNumber($price), '');
		#- Sẵn xem
		if($status_viewing_id > 0 && $status_viewing_id != _SOP_STATUS_VIEWING_NO_ID){
			$content_arrs[] = sprintf('✅ Tình trạng: %s', $clsProperty->getTitle($status_viewing_id));
		}
		if(!empty($pass_door)) {
			$content_arrs[] = '✅ Khi nào đi xem được: sẵn pass';
		}
		if(!empty($short_intro)){
			$content_arrs[] = sprintf('✅ %s', $short_intro);
		}
		$html_content = implode("\n", $content_arrs);
		// Return
		return $html_content;
	}
}