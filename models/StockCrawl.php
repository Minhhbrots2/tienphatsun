<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class StockCrawl extends dbBasic{

	function __construct(){

		global $core, $clsISO;

		$this->pkey = "stock_id";

		$this->tbl = DB_PREFIX."stock_crawl";

	}

	function getLink($ms_code, $link_type='', $project_id = 0){

		if($link_type == 'PTG'){

			return sprintf('/%s/PTG.html', $ms_code);

		} else {

			return sprintf('/gl/%s.html', $ms_code);

		}

	}

	function getTitleArray($ids, $openFrom="_detail"){

		$titles = "";

		if(!empty($ids)){

			$tmp = array();

			foreach($ids as $stock_id){

				$ms_code = $this->getMsCode($stock_id);

				if($openFrom=="_detail"){

					$tmp[] = '<a class="label label-default mr-1" onClick="$Core.helper.open_stock('.$stock_id.')">'.$ms_code.'</a>';

				} else {

					$tmp[] = '<a href="javascript:void(0);" class="label label-link bg-main text-white" onClick="$Core.helper.open_stock('.$stock_id.')">'.$ms_code.'</a>';

				}

			}

			$titles = implode('', $tmp);

		}

		return $titles;

	}

	function getItems($sop_id){

		global $core, $dbconn;

		$clsSopItem = new SopItem();

		$ret = $clsSopItem->getAll("sop_id='{$sop_id}' order by order_no ASC");

		return $ret;

	}

	function getTitle($sop_id, $oDataTable=null){

		global $core, $dbconn;

		if(!isset($oDataTable['title']) || is_null($oDataTable)){

			$oDataTable = $this->GetOne($sop_id,"title");

		}

		return $oDataTable['title'];

	}

	function checkPermissShow(){

		global $core, $dbconn, $clsISO, $oneProfile;

		if($clsISO->checkPermissionGroup('DIRECTOR') 

			|| $clsISO->checkPermissionGroup('ADMIN_PROJECT') 

			|| $clsISO->checkPermissionGroup('PROJECT_DIRECTOR'))

			return 1;

		return 0;

	}

	function checkShow($show_website, $website='MOC'){

		global $clsISO;

		$show_website_arrs = !empty($show_website) 

			? $clsISO->getArrayByTextSlash($show_website) : array();

		if(in_array($website, $show_website_arrs))

			return 1;

		return 0;

	}

	function getMsCode($stock_id){

		return $this->getOneField('ms_code', $stock_id);

	}

	function getIntro($sop_id, $oDataTable=null){

		global $core, $dbconn;

		if(!isset($oDataTable['intro']) || is_null($oDataTable)){

			$oDataTable = $this->GetOne($sop_id,"intro");

		}

		return $oDataTable['intro'];

	}

	function getImage($sop_id, $w, $h, $oDataTable=null){

		global $core, $dbconn, $clsISO;

		if(!isset($oDataTable['image']) || is_null($oDataTable)){

			$oDataTable = $this->GetOne($sop_id,"image");

		}

		$image = $oDataTable['image'];

		if(!empty($image)){

			return '/files/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);

		}

		return URL_IMAGES.'/noimage.png';

	}

	function getPriceOrigin($str=""){

		global $core, $dbconn, $clsISO;

		$price = 0;

		if(!empty($str)){

			$price = $clsISO->convertToNumber($str);

			if(strlen($price) <= 7){

				$price *= 1000000000;

			}	

		}

		return $price;

	}

	function getPriceOriginV2($str=""){

		global $core, $dbconn, $clsISO;

		$price = 0;

		if(!empty($str)){

			$str = strtolower($str);

			if(stripos($str,"tỷ")) {

				$tmp = explode("tỷ",$str);

				$priceStr = $tmp[0].".".$tmp[1];

				$price = $priceStr * 1000000000;

			}else{

				$price = $clsISO->convertToNumber($str);

				if(strlen($price) <= 7){

					$price *= 1000000000;

				}	

			}

				

		}

		return $price;

	}

	function getStatus($status_id){

		global $core, $dbconn, $profile_id, $clsISO, $loggedIn;

		$clsProperty = new Property();

		$field = "title,textcolor,bgcolor";

		if($status_id > 0){

			if($status_id==_STOCK_STATUS_SOLD_ID){

				$oProperty= $clsProperty->getOne($status_id, $field);

				$status_name= $clsProperty->getTitle($status_id, $oProperty);

			} else {

				if($status_id==_STOCK_STATUS_HIDDEN_ID){

					$status_name= 'Chưa mở bán';

					$oProperty= $clsProperty->getOne($status_id, $field);

				} else {

					$oProperty= $clsProperty->getOne($status_id, $field);

					$status_name= $clsProperty->getTitle($status_id, $oProperty);

				}

			}

		} else{

			$status_name = "Đã bán";

			$oProperty = $clsProperty->getOne(_STOCK_STATUS_SOLD_ID, $field);

		}

		return sprintf('<span class="label" style="background:%s;color:%s">%s</span>', $oProperty['bgcolor'], $oProperty['textcolor'], $status_name);

	}

	function getLoaiHinh($property_id){

		if($property_id==0){

			return 'Mới CĐT';

		} else {

			$clsProperty = new Property();

			return $clsProperty->getTitle($property_id);

		}

	}

	function getContentLog($oLog){

		global $core, $dbconn, $clsISO;

		$clsProperty = new Property();

		$html = "";

		if(!empty($oLog)){

			if($oLog['field'] == 'agency_id'){

				$html = sprintf(

					'Thay đổi <strong>Đại lý</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsProperty->getTitle($oLog['from_id']),

					$clsProperty->getTitle($oLog['to_id'])

				);

			} else if($oLog['field'] == 'status_id'){

				$html = sprintf(

					'Thay đổi <strong>Tình trạng</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsProperty->getTitle($oLog['from_id']),

					$clsProperty->getTitle($oLog['to_id'])

				);

			} else if($oLog['field'] == 'total_price'){

				$html = sprintf(

					'Thay đổi <strong>Giá chưa VAT&KPBT</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['from_value'])),

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['to_value']))

				);

			} else if($oLog['field'] == 'total_price_vat'){

				$html = sprintf(

					'Thay đổi <strong>Tổng giá VAT KPBT</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['from_value'])),

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['to_value']))

				);

			} else if($oLog['field'] == 'total_price_early'){

				$html = sprintf(

					'Thay đổi <strong>Giá TTS</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['from_value'])),

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['to_value']))

				);

			} else if($oLog['field'] == 'total_price_progress'){

				$html = sprintf(

					'Thay đổi <strong>Giá TTTĐ</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['from_value'])),

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['to_value']))

				);

			} else if($oLog['field'] == 'total_price_bank'){

				$html = sprintf(

					'Thay đổi <strong>Giá vay(80%)</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['from_value'])),

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['to_value']))

				);

			} else if($oLog['field'] == 'total_price_bank_half'){

				$html = sprintf(

					'Thay đổi <strong>Giá vay(50%)</strong> từ <strong>%s</strong> tới <strong>%s</strong>',

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['from_value'])),

					$clsISO->priceFormat($clsISO->processSmartNumber($oLog['to_value']))

				);

			} else if($oLog['field']=='price_sheets') {

				$html = 'Thay đổi phiếu tính giá';

			}

		}

		// Return

		return $html;

	}

	function getPriceField($stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE){

		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){

			return array(

				'total_price_early' => 'Giá TTS',

				'total_price_progress' => 'Giá TTTĐ',

				'total_price_bank' => 'Giá vay NH',

				'total_price_bank_half' => 'Giá vay NH (50%)'

			);

		} else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {

			return array(

				'total_price_early' => 'Giá TTS',

				'total_price_progress' => 'Giá TTTĐ',

				'total_price_bank_12' => 'Giá vay 12T',

				'total_price_bank_18' => 'Giá vay 18T',

				'total_price_bank' => 'Giá vay 24T',

				'total_price_bank_30' => 'Giá vay 30T',

				'total_price_bank_36' => 'Giá vay 36T'

			);

		}

	}

	function getTableField($stock_type=_BLOCK_TYPE_HIGHLEVEL_SALE){

		if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){

			$arr_fields = array(

				'block_id' => 'Phân khu',

				'building_id' => 'Tòa nhà',

				'floor' => 'Tầng',

				'code' => 'Căn số',

				'ms_code' => 'Mã căn(Full)',

				'type_id' => 'Loại hình',

				'bedroom_id' => 'Loại căn',

				'home_direction_id' => 'Hướng BC',

				'agency_id' => 'Đại lý',

				'view_id' => 'View',

				'DT_TT' => 'DT Thông thuỷ',

				'DT_Tim' => 'DT Tim tường',

				'total_price' => 'Giá chưa VAT&KPBT',

				'total_price_vat' => 'Giá VAT&KPBT',

				'total_price_early' => 'Giá TTS',

				'total_price_progress' => 'Giá TTTĐ',

				'total_price_bank_half' => 'Giá vay 50%',

				'total_price_bank' => 'Giá vay 80%',

				'price_sheet_title' => 'Tiêu đề PTG',

				'price_sheet_link' => 'Link PTG',

				'sale_bonus' => 'Thưởng sale',

				'date_deposit_sign' => 'Ngày ký TTĐC',

				'status_id' => 'Tình trạng',

				'csbh' => 'Chính sách bán hàng',

				'maintenance_fee' => 'Kinh phí bảo trì'

			);

		} else if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE) {

			$arr_fields = array(

				'agency_id' => 'Đại lý bán',

				'stock_hold_id' => 'Loại quỹ',

				//'block_id' => 'Phân khu',

				'type_id' => 'Loại hình',

				'ms_code' => 'Mã căn',

				'block_name' => 'Dãy nhà',

				'home_direction_id' => 'Hướng nhà',

				'DT_TT' => 'DT Đất',

				'DT_Tim' => 'DT Xây dựng',

				'total_price' => 'Giá chưa VAT&KPBT',

				'total_price_vat' => 'Tổng giá VAT&KPBT',

				'total_price_early' => 'Giá TTS',

				'total_price_progress' => 'Giá TTTĐ',

				'total_price_bank_12' => 'Giá vay 12T',

				'total_price_bank_18' => 'Giá vay 18T',

				'total_price_bank' => 'Giá vay 24T',

				'total_price_bank_30' => 'Giá vay 30T',

				'total_price_bank_36' => 'Giá vay 36T',

				'status_id' => 'Tình trạng',

				'TCBG' => 'Tiêu chuẩn bàn giao',

				'csbh' => 'Chính sách bán hàng',

				'deposit_date' => 'Ngày ký cọc',

				'cs_policy_ns' => 'Chính sách',

				'price_temporary_ns' => 'Phiếu tạm tính',

				'contract_type_id' => 'Loại hình ký HĐ',

				'contract_subject_id' => 'Chủ thể ký HĐ',

				'invest_fund_id' => 'Quỹ đầu tư',

				'sale_status_id' => 'Tình trạng bán',

				'agent_lock_id' => 'ĐL lock',

				'deposit_agent_id' => 'ĐL cọc', 

				'bank_second_id' => 'Giỏ Bank Thứ cấp',

				'bank_id' => 'Giỏ Bank',

				'notes' => 'Ghi chú',

				'maintenance_fee' => 'Kinh phí bảo trì'

			);

		} else if((int) $stock_type==_STOCK_TYPE_LEASING){

			$arr_fields = array(

				'agency_id' => 'Đại lý bán',

				'stock_hold_id' => 'Loại quỹ',

				'status_leasing_id' => 'Tình trạng',

				'type_id' => 'Loại hình',

				'ms_code' => 'Mã căn',

				//'block_id' => 'Phân khu',

				//'service_id' => 'Ngành hàng',

				//'block_name' => 'Dãy nhà',

				'dg_price' => 'Giá thuê',

				'total_price' => 'Tiền thuê',

				'service_price' => 'Phí dịch vụ',

				'setup_price' => 'Tiền HT setup', 

				'construct_price' => 'Tiền HT thi công',

				'DT_TT' => 'DT Đất',

				'DT_Tim' => 'DT Xây dựng',

				'home_direction_id' => 'Hướng nhà',

				// 'DT_Opt1' => 'DT chiếm đất tầng 1',

				// 'DT_Opt2' => 'DT sử dụng bổ sung',

				// 'DT_Opt3' => 'DT sử dụng mở',

				// 'DT_Build_Floor_1' => 'DT XD L1',

				// 'DT_Build_Floor_2' => 'DT XD L2',

				// 'DT_Build_Floor_3' => 'DT XD L3',

				// 'DT_Build_Floor_4' => 'DT XD L4',

				// 'DT_Build_Floor_5' => 'DT XD L5',

				// 'DT_Build_Floor_6' => 'DT XD L6',

				// 'free_deadline' => 'Thời gian kết thúc miễn phí tiền thuê',

				'TCBG' => 'Tiêu chuẩn bàn giao',

				'completed_floor_id' => 'Hoàn thiện',

				'payment_progress_id' => 'Tiến độ thanh toán',

				'fee_included' => 'Phí quản lý',

				'link_image' => 'Link ảnh',

				'notes' => 'Ghi chú',

				'maintenance_fee' => 'Kinh phí bảo trì'

			);

		}

		return $arr_fields;

	}

	function getHtmlColumnFieldCrawl($stock_type=_BLOCK_TYPE_HIGHLEVEL_SALE, $field=""){

		global $core, $dbconn, $clsISO;

		$clsProperty = new Property();

		$html_options = "";

		$arr_fields = $this->getTableField($stock_type);

		foreach($arr_fields as $key => $val){

			$selected = (!empty($field) && $field==$key) ? " selected" : "";

			$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);

		}

		return $html_options;

	} 

	function getHtmlColumnField($col, $stock_type=_BLOCK_TYPE_HIGHLEVEL_SALE, $agency_id=0, $field=""){

		global $core, $dbconn, $clsISO;

		$clsProperty = new Property();

		$more_information = $clsProperty->getOneField('more_information', $agency_id);

		$more_information = $clsISO->to_array_json($more_information);

		$columns = isset($more_information['columns']) ? $more_information['columns'] : array(); 

		// $clsISO->print_pre($more_information); die();

		$html_options = "";

		$arr_fields = $this->getTableField($stock_type);

		if(!empty($columns)){

			foreach($arr_fields as $key => $val){

				$field = isset($columns[$col]) ? $columns[$col] : "";

				$selected = ($field==$key) ? 'selected':'';

				$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);

			}

		} else {

			foreach($arr_fields as $key => $val){

				if(!empty($field)){

					$selected = ($field==$key) ? " selected" : "";

				} else {

					$field = "";

					if($col==0) $field = 'ms_code';

					if($col==1) $field = 'total_price';

					if($col==2) $field = 'total_price_vat';

					if($col==3) $field = 'status_id';

					if($col==4) $field = 'agency_id';

					$selected = ($field==$key) ? 'selected':'';

				}

				$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);

			}

		}

		return $html_options;

	} 

	function renderOptionColumnField($col, $stock_type=_BLOCK_TYPE_HIGHLEVEL_SALE, $group="", $project_id=0){

		global $clsISO;

		$html_options = "";

		$clsProject = new Project();

		$arr_fields = $this->getTableField($stock_type);

		if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){

			foreach($arr_fields as $key => $val){

				if(empty($group) && empty($field)){

					$field = "";

					if($col==0) $field = 'ms_code';

					if($col==1) $field = 'total_price';

					if($col==2) $field = 'total_price_vat';

					if($col==3) $field = 'status_id';

					if($col==4) $field = 'agency_id';

				}

				$selected = ($field==$key) ? 'selected':'';

				$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);

			}

		} else if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){

			$oneProject = $clsProject->getOne($project_id);

			$more_information = $clsISO->to_array_json($oneProject["more_information"]);

			$column_lowfloor = !empty($more_information['column_lowfloor']) ? $more_information['column_lowfloor'] : array();

			foreach($arr_fields as $key => $val){

				$field = "";

				if($col==0) $field = 'agency_id';

				if($col==1) $field = 'ms_code';

				if($col==2) $field = 'total_price_vat';

				if($project_id == _PROJECT_VHGG_ID){

					if($col==3) $field = 'total_price_bank_12';

					if($col==4) $field = 'total_price_bank_18';

					if($col==5) $field = 'total_price_bank'; // 24

					if($col==6) $field = 'total_price_bank_36';

					if($col==7) $field = 'total_price_early';

					if($col==8) $field = 'total_price_progress';

					if($col==9) $field = 'home_direction_id';

					if($col==10) $field = 'DT_TT';

					if($col==11) $field = 'DT_Tim';

					if($col==12) $field = 'TCBG';

					if($col==13) $field = 'contract_type_id';

					if($col==14) $field = 'invest_fund_id';

					if($col==15) $field = 'stock_hold_id';

					if($col==16) $field = 'price_temporary_ns';

					if($col==17) $field = 'type_id';

					if($col==18) $field = 'bank_id';

					if($col==19) $field = 'csbh';

					if($col==20) $field = 'deposit_date';

					if($col==21) $field = 'notes';

				} else if($project_id == _PROJECT_VHOP3_ID){

					if($col==3) $field = 'total_price_bank_18'; 

					if($col==4) $field = 'total_price_bank';// 24

					if($col==5) $field = 'total_price_bank_30';

					if($col==6) $field = 'total_price_bank_12';

					if($col==7) $field = 'total_price_early';

					if($col==8) $field = 'total_price_progress';

					if($col==9) $field = 'home_direction_id';

					if($col==10) $field = 'DT_TT';

					if($col==11) $field = 'DT_Tim';

					if($col==12) $field = 'TCBG';

					if($col==13) $field = 'contract_type_id';

					if($col==14) $field = 'invest_fund_id';

					if($col==15) $field = 'stock_hold_id';

					if($col==16) $field = 'price_temporary_ns';

					if($col==17) $field = 'type_id';

					if($col==18) $field = 'bank_id';

					if($col==19) $field = 'csbh';

					if($col==20) $field = 'deposit_date';

					if($col==21) $field = 'notes';

				} else if($project_id == _PROJECT_VWC_ID){					

					if($col==3) $field = 'total_price_bank_12'; 

					if($col==4) $field = 'total_price_bank_18';

					if($col==5) $field = 'total_price_bank'; // 24

					if($col==6) $field = 'total_price_bank_36';

					if($col==7) $field = 'total_price_early';

					if($col==8) $field = 'total_price_progress';

					if($col==9) $field = 'home_direction_id';

					if($col==10) $field = 'DT_TT';

					if($col==11) $field = 'DT_Tim';

					if($col==12) $field = 'TCBG';

					if($col==13) $field = 'contract_type_id';

					if($col==14) $field = 'invest_fund_id';

					if($col==15) $field = 'stock_hold_id';

					if($col==16) $field = 'price_temporary_ns';

					if($col==17) $field = 'type_id';

					if($col==18) $field = 'bank_id';

					if($col==19) $field = 'csbh';

					if($col==20) $field = 'deposit_date';

					if($col==21) $field = 'notes';

				} else {

					if($col==3) $field = 'total_price_bank'; // 24

					if($col==4) $field = 'total_price_bank_36';

					if($col==5) $field = 'total_price_progress';

					if($col==6) $field = 'total_price_early';

					if($col==7) $field = 'home_direction_id';

					if($col==8) $field = 'DT_TT';

					if($col==9) $field = 'DT_Tim';

					if($col==10) $field = 'TCBG';

					if($col==11) $field = 'contract_type_id';

					if($col==12) $field = 'invest_fund_id';

					if($col==13) $field = 'stock_hold_id';

					if($col==14) $field = 'price_temporary_ns';

					if($col==15) $field = 'type_id';

					if($col==16) $field = 'bank_id';

					if($col==17) $field = 'csbh';

					if($col==18) $field = 'deposit_date';

					if($col==19) $field = 'notes';

				}

				if(!empty($column_lowfloor)) {

					$selected = ($column_lowfloor[$col]==$key) ? 'selected':'';

				}else{

					$selected = ($field==$key) ? 'selected':'';

				}

				

				$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $val);

			}

		} else if($stock_type==_STOCK_TYPE_LEASING){

			foreach($arr_fields as $key => $val){

				$html_options.= sprintf('<option value="%s" %s>%s</option>', $key, "", $val);

			}

		}

		return $html_options;

	}

	function getDataAccount(){

		require_once(DIR_INCLUDES.'/json_master/autoload.php');

		$cachedFile = DIR_CACHE_JSON.'/account/account.json';

		$lst_account = array();

		$api_key = $model_id = "";

		$number = 0;

		if(@file_exists($cachedFile)){

			$decoder = new Webmozart\Json\JsonDecoder();

			$lst_account = $decoder->decodeFile($cachedFile);

			if (!empty($lst_account)) {

				foreach ($lst_account as $key => $value) {

					if(!empty($value['api_key'])) {

						$api_key = $value['api_key'];

						if(!empty($value['model_id']) && $value['model_id']['key'] != "" && $value['model_id']['number'] < 100){

							if(!isset($value['model_id']['is_use']) || $value['model_id']['is_use'] == 1) {

								$model_id = $value['model_id']['key'];

								$number = $value['model_id']['number'];

								$lst_account[$key]["time_upd_last"] = time();

								break;

							}							

						}						

					}

				}				

				$encoder = new Webmozart\Json\JsonEncoder();

				$encoder->encodeFile($lst_account, $cachedFile);

			}

		}

		return array(

			"api_key"		=>	$api_key,

			"model_id"		=>	$model_id,

			"number"		=>	$number

		);

	}

	

	function str_replace_first($search, $replace, $subject)	{

		$search = '/'.preg_quote($search, '/').'/';

		return preg_replace($search, $replace, $subject, 1);

	}

	function getLinkSearch($building_id,$stock_id,$type="building"){

		global $clsISO;

		$clsProperty = new Property();

		$oneStock = $this->getOne($stock_id);

		$oneBuilding = $clsProperty->getOne($building_id,"property_code");

		$stock_code = $oneStock['ms_code'];

		$floor = $oneStock['floor'];

		$code = $oneStock['code'];

		$bedroom_id = $oneStock['bedroom_id'];

		$home_direction_id = $oneStock['home_direction_id'];

		$prev_code = "";

		if($clsISO->checkContainer($stock_code, ".", "")){

			$parts = @explode(".", $stock_code);

			$prev_code = $parts[0]."."; 

			$stock_code = $parts[1];

		} 

		if($type == "code"){

			return sprintf('/tim-kiem/%s.html', $prev_code. $this->str_replace_first($floor, 'xx', $stock_code));

		} else if($type == "floor"){

			if(empty($code)){

				$_stock_code = $this->str_replace_first($floor, 'xx', $stock_code);

				$tmp = @explode('xx', $_stock_code);

				$code = $tmp[1];

			}

			return sprintf('/tim-kiem/%s.html', $prev_code.preg_replace("((.*)".$code.")", "$1xx", $stock_code));

		} else if($type == "building"){

			return sprintf('/tim-kiem/%sxxxx.html', $oneBuilding["property_code"]);

		} else if($type == "bedroom"){

			//return sprintf('/tim-kiem/%s+%s.html', $oneBuilding["property_code"],$clsProperty->getTitle($bedroom_id));

			return sprintf('/tool.html?s=1&project_id=%s&block_ids=%s&building_ids=%s&bedroom_ids=%s', $oneStock["project_id"], $oneStock["block_id"],$building_id,$bedroom_id);

		} else if($type == "direction"){

			//return sprintf('/tim-kiem/%s+%s.html', $oneBuilding["property_code"],$clsProperty->getTitle($home_direction_id));

			return sprintf('/tool.html?s=1&project_id=%s&block_ids=%s&building_ids=%s&direction_ids=%s', $oneStock["project_id"], $oneStock["block_id"],$building_id,$home_direction_id);

		}

		return "";

	}

	function checkStockFundType($stock_id,$building_id,$oneStock=array(),$oneBuilding=array()){

		global $core, $dbconn, $clsISO;

		$clsProperty = new Property();

		if(!isset($oneStock['floor'])) {

			$oneStock = $this->getOne($stock_id, "floor");

		}

		if(!isset($oneBuilding['more_information'])) {

			$oneBuilding = $clsProperty->getOne($building_id,"more_information");

		}

		$building_information = $oneBuilding['more_information'];

		$building_information = $clsISO->to_array_json($building_information);

		$floor_hierarchy = !empty($building_information['floor_hierarchy']) 

			? $building_information['floor_hierarchy'] : array();

		//var_dump($oneStock); die();

		if(!empty($floor_hierarchy) && in_array($oneStock['floor'], $floor_hierarchy)){

			return 1;

		} else {

			return 0;

		}

	}

	function getCode($code){

		global $core, $dbconn, $clsISO;

		switch ($code) {

			case 4:

				return "05A";

				break;

			case 7:

				return "08A";

				break;

			case 13:

				return "12A";

				break;

			case 14:

				return "15A";

				break;

			case 17:

				return "18A";

				break;

			default :

				if($code < 10){

					return "0".$code;

				}

				return $code;

				break;

		}

	}

}