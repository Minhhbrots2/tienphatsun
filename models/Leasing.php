<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class Leasing extends dbBasic{

	function __construct(){

		global $core, $clsISO;

		$this->pkey = "leasing_id";

		$this->tbl = DB_PREFIX."leasing";

	}

	function getTitle($pval,$_args=array()){

		if(!isset($_args['title'])){

			$_args = $this->getOne($pval,"title");

		}

		return $_args['title'];

	}

	function getLink($leasing_id, $stock_code){

		global $core, $dbconn;

		return DOMAIN_URL.sprintf('/ct/%s-%s.html', $stock_code, $leasing_id);

	}

	function getLinkEdit($leasing_id){

		global $core, $dbconn;

		return DOMAIN_URL.sprintf('/ct/edit/%s', $leasing_id);

	}

	function doDelete($leasing_id){

		// Delete

		$this->deleteOne($leasing_id);

		return 1;

	}

	function str_replace_first($search, $replace, $subject)	{

		$search = '/'.preg_quote($search, '/').'/';

		return preg_replace($search, $replace, $subject, 1);

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

		// $clsISO->print_pre($oneProperty); die();

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

	function getCodeField($field=''){

		$array = [

			"base_utensils_id"			=>	"lbu",

			"status_leasing_id"			=>	"lstt",

			"payment_electric_id"		=>	"lpe",

			"payment_water_id"			=>	"lpw",

			"rental_term_leasing_id"	=>	"lrt",

		];

		if($field == ""){

			return $array;

		}else{

			if(!empty($array[$field])){

				return $array[$field];

			}

			return "";

		}		

	}

	function getStrListCode($array_post){

		

		$str = "";

		$terms = $this->getCodeField();

		if(!empty($array_post)){

			$str .= "|";

			foreach($array_post as $key => $val){

				if(array_key_exists($key, $terms)){

					$str.= sprintf('|%s:%s|', $terms[$key], $val);

				}

			}

			$str .= "|";

		}	

		

		return $str;

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

	function getIcon($leasing_id,$type_list="grid", $openFrom="_list"){

		global $core, $dbconn, $clsISO;

		$field = "`more_information`,`is_locked`,`is_solded`,`is_online`";

		$oneItem = $this->getOne($leasing_id, $field);

		$more_information = $oneItem['more_information'];

		$more_information = $clsISO->to_array_json($more_information);

		

		$icon = "";

		$attrs = 'data-bs-toggle="tooltip" data-bs-trigger="hover"';

		if(isset($more_information['is_deleted']) && (int) $more_information['is_deleted'] == 1){

			$icon = '<i '.$attrs.' class="material-icons-outlined text-danger" title="Đã xoá">delete</i>';

		} else if($oneItem['is_solded'] == 1) {

			return '<img '.$attrs.' title="Đã cho thuê" src="'.URL_IMAGES.'/sold.png" width="14px" />';

		} else if($oneItem['is_locked'] == 1) {

			$icon = '<i '.$attrs.' class="material-icons-outlined fs-small text-danger" title="Đã khóa">lock</i>';

		} else {

			if($oneItem['is_online'] == 0){

				$icon = '<i '.$attrs.' class="material-icons-outlined fs-small text-muted" title="Chờ phê duyệt">schedule</i>';

			} else if($oneItem['is_online'] == 1){

				$icon = '<i '.$attrs.' class="material-icons-outlined fs-small text-success" title="Đã phê duyệt">verified</i>';

			} else {

				$icon = '<i '.$attrs.' class="material-icons-outlined fs-small" title="Không phê duyệt">block</i>';

			}

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

	function updateMeta($leasing_id){

		global $core, $dbconn, $clsISO;

		$clsStock = new Stock();

		$clsProperty = new Property();

		###

		$field = $this->pkey.",`title`,`stock_code`,`more_information`,`price`,`home_direction_id`,`bedroom_id`,`building_id`,`floor`,`code`,`project_id`,`block_id`";

		###

		$oneLeasing = $this->getOne($leasing_id,$field);		

		$title = $oneLeasing['title'];

		$bedroom_id = $oneLeasing['bedroom_id'];

		$building_id = $oneLeasing['building_id'];

		$home_direction_id = $oneLeasing['home_direction_id'];

		$more_information = $oneLeasing['more_information'];

		$more_information = $clsISO->to_array_json($more_information);

		$oneLeasing['more_information'] = $more_information;

		$stock_code = $this->getCode($oneLeasing);

		$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);

		$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);

		$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);

		$description_page = sprintf('%s + %s + %s + %sm2 + %s + %s tỷ - %s', $stock_code, $arr_property_cached[$building_id], $arr_property_cached[$bedroom_id], $more_information['DT_TT'], $arr_property_cached[$home_direction_id],$clsISO->formatPriceV2($oneLeasing['price'],3), PAGE_NAME);

		###

		if(!empty($bedroom_id)){

			$bedroom = "Căn ".$arr_property_cached[$bedroom_id];

		}else if(!empty($more_information['bedroom_num'])) {

			$bedroom = "Căn ".$more_information['bedroom_num']."PN";

		} 		

		if(!empty($more_information['sop_type']) && $more_information['sop_type'] != _TYPE_HIGHLEVEL) {

			$txt_building = "Dãy";

		} else{

			$txt_building = "Tòa";

		}

		if(empty($oneLeasing['title'])){

			$title = sprintf('%s '.$txt_building.' %s Hướng %s %sm2', 

				$bedroom, 

				$arr_property_cached[$building_id], 

				$arr_property_cached[$home_direction_id], 

				$more_information['DT_TT']

			);

		}

		$title_page = sprintf('%s - %s', $title, PAGE_NAME);

		$more_information['stock_code'] = $stock_code;

		$more_information['title_page'] = $title_page;

		$more_information['description_page'] = $description_page;

		$more_information['DT_TT'] = $more_information['DT_TT'];

		$description_page = $stock_code." + " . $arr_property_cached[$building_id];

		if(!empty($more_information['sop_type']) && $more_information['sop_type'] != _TYPE_HIGHLEVEL) {

			$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;

			if(!empty($more_information['bedroom_num'])) {

				$description_page .=  (($description_page != "") ? " + " : "") . ($more_information['bedroom_num']."PN");

			}

		}else{

			$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;

			if($oneLeasing['bedroom_id'] > 0) {

				$description_page .=  (($description_page != "") ? " + " : "") . ($clsProperty->getTitle($oneLeasing['bedroom_id']));

			}

		}

		if(!empty($more_information['DT_TT'])) {

			$description_page .=  (($description_page != "") ? " + " : "") . ($more_information['DT_TT']."m2");

		}

		if(!empty($oneLeasing['price'])) {

			$description_page .=  (($description_page != "") ? " + " : "") . $clsISO->shortNumber($oneLeasing['price'])."/1 tháng";

		}

		$more_information['description_page'] = $description_page." - ".PAGE_NAME;

		$arr_upd = array(

			'title' => $title,

			'slug' => $core->replaceSpace($title),

			'floor' => $oneLeasing['floor'],			

			'project_id'=>$oneLeasing['project_id'],

			'building_id'=>$oneLeasing['building_id'],

			'bedroom_id'=>$oneLeasing['bedroom_id'],

			'block_id'=>$oneLeasing['block_id'],

			'home_direction_id'=>$oneLeasing['home_direction_id'],

			'stock_type'=>$stock_type,

			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

		);

//		$clsISO->print_pre($arr_upd);die;

		$this->updateOne($leasing_id, $arr_upd);

	}

	function updateMetaNoStockCode($leasing_id){

		global $core, $dbconn, $clsISO;

		$clsStock = new Stock();

		$clsProperty = new Property();

		###

		

		$oneLeasing = $this->getOne($leasing_id,"`title`,`slug`,`stock_code`,`more_information`,`price`,`bedroom_id`,`building_id`,`home_direction_id`");

		$more_information = $clsISO->to_array_json($oneLeasing['more_information']);

		

		$title = $oneLeasing['title'];

		$bedroom_id = $oneLeasing['bedroom_id'];

		$building_id = $oneLeasing['building_id'];

		$home_direction_id = $oneLeasing['home_direction_id'];

		$stock_code = $oneLeasing['stock_code'];

		

		$arr_property_cached[$bedroom_id] = $clsProperty->getTitle($bedroom_id);

		$arr_property_cached[$building_id] = $clsProperty->getTitle($building_id);

		$arr_property_cached[$home_direction_id] = $clsProperty->getTitle($home_direction_id);

		

		if(!empty($bedroom_id)){

			$bedroom = "Căn ".$arr_property_cached[$bedroom_id];

		}else if(!empty($more_information['bedroom_num'])) {

			$bedroom = "Căn ".$more_information['bedroom_num']."PN";

		} 

		###

		

		if(!empty($more_information['sop_type']) && $more_information['sop_type'] != _TYPE_HIGHLEVEL) {

			$txt_building = "Dãy";

		} else{

			$txt_building = "Tòa";

		}

		if(empty($oneLeasing['title'])){

			if(empty($oneLeasing['title'])){

				$title = sprintf('%s '.$txt_building.' %s Hướng %s %sm2', 

					$bedroom, 

					$arr_property_cached[$building_id], 

					$arr_property_cached[$home_direction_id], 

					$stock_information['DT_TT']

				);

			}

		}

		$title_page = sprintf('%s - %s', $oneLeasing['title'], PAGE_NAME);

		$more_information['stock_code'] = $stock_code;

		$more_information['title_page'] = $title_page;

		$description_page = $stock_code." + " . $arr_property_cached[$building_id];

		if(!empty($more_information['sop_type']) && $more_information['sop_type'] != _TYPE_HIGHLEVEL) {

			$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;

			if(!empty($more_information['bedroom_num'])) {

				$description_page .= (($description_page != "") ? " + " : "") . ($more_information['bedroom_num']."PN");

			}

		}else{

			$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;

			if($oneLeasing['bedroom_id'] > 0) {

				$description_page .= (($description_page != "") ? " + " : "") . $arr_property_cached[$bedroom_id];

			}

		}

		if(!empty($more_information['DT_TT'])) {

			$description_page .=  (($description_page != "") ? " + " : "") . ($more_information['DT_TT']."m2");

		}

		if(!empty($oneLeasing['price'])) {

			$description_page .=  (($description_page != "") ? " + " : "") . $clsISO->shortNumber($oneLeasing['price'])."/1 tháng";

		}

		$more_information['description_page'] = $description_page." - ".PAGE_NAME;

		$arr_upd = array(

			'title' => $title,

			'slug' => $core->replaceSpace($title),

			'stock_type'=>$stock_type,

			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

		);

//		$clsISO->print_pre($arr_upd);die;

		$this->updateOne($leasing_id, $arr_upd);

	}

	function checkLiked($leasing_id){

		global $oneProfile;

		$more_information = $oneProfile['more_information'];

		$liked_sop = isset($more_information['liked_sop']) 

			? $more_information['liked_sop'] : array();

		if(in_array($leasing_id, $liked_sop))

			return 1;

		return 0;

	}

	function getUserNotify(){

		global $core, $dbconn, $clsISO, $profile_id;

		$u = array(7,9);

		return $u;

		return @array_diff($u, array($profile_id));

	}

}